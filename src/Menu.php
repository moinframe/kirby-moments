<?php

declare(strict_types=1);

namespace Moinframe\Moments;

use Kirby\Cms\App;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

class Menu
{
    /** @var array<int, array<string, mixed>> Stores menu pages */
    protected static array $pages = [];

    /** @var string|null Caches the current path */
    protected static ?string $path = null;

    /**
     * Gets the current path
     * @return string The current path
     */
    public static function path(): string
    {
        return static::$path ??= App::instance()->path();
    }

    /**
     * Adds a page to the menu
     * @param string $label The label for the menu item
     * @param string $icon The icon for the menu item
     * @return array The added menu item
     */
    public static function page(string | null $label = null, string $icon = 'moments'): array
    {
        $link = 'pages/' . option('moinframe.moments.storeId', 'moments');
        return static::$pages[] = [
            'label'   => $label ?? t('moinframe.moments.panel.menu.label'),
            'link'    => $link,
            'icon'    => $icon,
            'current' => fn() => str_contains(static::path(), $link)
        ];
    }

    /**
     * Creates the site menu item
     * @param string $label The label for the site menu item
     * @param string $icon The icon for the site menu item
     * @return array The site menu item
     */
    public static function site(string $label = 'Site', string $icon = 'dashboard'): array
    {
        return [
            'label'   => $label,
            'icon'    => $icon,
            'current' => fn(string $id = null) => $id === 'site' && A::every(
                static::$pages,
                fn($page) => !Str::contains(static::path(), $page['link'])
            ),
        ];
    }
}
