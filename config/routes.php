<?php

declare(strict_types=1);

/**
 * Defines routes for the Kirby Moments plugin
 * @return array Routes configuration
 */

use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Http\Response;

const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic'];
const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/heic'];
const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20MB

return function () {
    $momentsPage = option('femundfilou.kirby-moments.pageid') ? page(option('femundfilou.kirby-moments.pageid')) : null;
    $momentsStore = option('femundfilou.kirby-moments.storeid') ? page(option('femundfilou.kirby-moments.storeid')) : null;

    if (!$momentsStore) {
        return [];
    }

    $useStorePageOnly = !$momentsPage || ($momentsPage && $momentsPage->is($momentsStore));
    $momentsSlug = $useStorePageOnly ? $momentsStore->uid() : $momentsPage->uid();

    $routes = [];

    if (!$useStorePageOnly) {
        $routes = array_merge($routes, getRedirectRoutes($momentsSlug, $momentsStore, $momentsPage));
    }

    if (option('femundfilou.kirby-moments.feed.active', true) !== false) {
        $routes = array_merge($routes, getFeedRoutes($momentsSlug));
    }

    $routes[] = getNewMomentRoute();

    return $routes;
};

/**
 * Get redirect routes for moments
 * @param string $momentsSlug
 * @param Page $momentsStore
 * @param Page|null $momentsPage
 * @return array<int, array<string, mixed>>
 */
function getRedirectRoutes(string $momentsSlug, Page $momentsStore, ?Page $momentsPage): array
{
    return [
        [
            'pattern' => "/{$momentsSlug}/(:all)",
            'method' => 'GET',
            'action' => function ($id) use ($momentsStore) {
                if (in_array($id, ['feed.xml', 'feed.xsl'])) {
                    return $this->next();
                }
                $page = $momentsStore->children()->find($id) ?? site()->errorPage();
                return site()->visit($page);
            }
        ],
        [
            'pattern' => "/{$momentsStore->uid()}/(:all)",
            'method' => 'GET',
            'action' => function ($id) use ($momentsPage) {
                go("{$momentsPage}/{$id}", 302);
            }
        ]
    ];
}

/**
 * Get feed routes
 * @param string $momentsSlug
 * @return array<int, array<string, mixed>>
 */
function getFeedRoutes(string $momentsSlug): array
{
    return [
        [
            'pattern' => "/{$momentsSlug}/feed.xsl",
            'action' => function () {
                return renderFeedPage('text/xsl', 'xsl');
            }
        ],
        [
            'pattern' => "/{$momentsSlug}/feed.xml",
            'method' => 'GET',
            'action' => function () {
                return renderFeedPage('text/xml');
            }
        ]
    ];
}

/**
 * Render feed page
 * @param string $contentType
 * @param string $renderType
 * @return string
 */
function renderFeedPage(string $contentType, string $renderType = 'html'): string
{
    kirby()->response()->type($contentType);
    return Page::factory([
        'slug' => 'feed',
        'template' => 'feed',
        'model' => 'feed',
        'content' => ['title' => t('feed')],
    ])->render(contentType: $renderType);
}

/**
 * Get route for creating new moment
 * @return array<string, mixed>
 */
function getNewMomentRoute(): array
{
    return [
        'pattern' => '/v1/moments/new',
        'method' => 'POST',
        'action' => function () {
            if (!verifyToken()) {
                return ['status' => 'error', 'message' => 'Unauthorized access.'];
            }

            $page = site()->getMomentsStorePage();
            if (!$page) {
                return ['status' => 'error', 'message' => 'Page not found.'];
            }

            try {
                $file = uploadFile($page);
                return ['status' => 'success', 'url' => $page->url()];
            } catch (InvalidArgumentException $e) {
                return ['status' => 'error', 'message' => $e->getMessage()];
            } catch (Exception $e) {
                error_log('Moments upload error: ' . $e->getMessage());
                return ['status' => 'error', 'message' => 'Failed to upload image.'];
            }
        }
    ];
}

/**
 * Verify bearer token using timing-safe comparison
 * @return bool
 */
function verifyToken(): bool
{
    $authHeader = kirby()->request()->header('X-MOMENTS-TOKEN');
    $token = option('femundfilou.kirby-moments.token', '');

    if (empty($token) || empty($authHeader)) {
        return false;
    }

    return hash_equals($token, $authHeader);
}

/**
 * Upload file to page with security validations
 * @param Page $page
 * @return File
 * @throws InvalidArgumentException For validation errors
 * @throws Exception For other upload errors
 */
function uploadFile(Page $page): File
{
    $upload = kirby()->request()->file('file');

    if (!$upload || $upload['error'] !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('Upload failed.');
    }

    // File size validation
    if ($upload['size'] > MAX_FILE_SIZE) {
        throw new InvalidArgumentException('File too large.');
    }

    // Extension whitelist
    $extension = strtolower(pathinfo($upload['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ALLOWED_EXTENSIONS, true)) {
        throw new InvalidArgumentException('Invalid file type.');
    }

    // MIME type verification
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($upload['tmp_name']);
    if (!in_array($mimeType, ALLOWED_MIME_TYPES, true)) {
        throw new InvalidArgumentException('Invalid file type.');
    }

    // Secure filename using cryptographically secure random bytes
    $filename = bin2hex(random_bytes(16)) . '.' . $extension;

    kirby()->impersonate('kirby');
    return $page->createFile([
        'source'   => $upload['tmp_name'],
        'filename' => $filename,
        'template' => 'moment',
        'content'  => ['date' => date('Y-m-d H:i:s'), 'text' => '']
    ]);
}
