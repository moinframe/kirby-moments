<?php

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\F;

require __DIR__ . '/models/Moments.php';
require __DIR__ . '/models/Moment.php';

F::loadClasses([
    'moinframe\\moments\\menu' => 'lib/Menu.php'
], __DIR__);


Kirby::plugin('moinframe/moments', [
    'blueprints' => [
        'files/moment' => __DIR__ . '/blueprints/files/moment.yml',
        'pages/moments' => __DIR__ . '/blueprints/pages/moments.yml',
        'blocks/moments' => __DIR__ . '/blueprints/blocks/moments.yml',
        'sections/moments' => __DIR__ . '/blueprints/sections/moments.yml',
    ],
    'collections' => [
        'moments/all' => require_once __DIR__ . '/collections/moments/all.php'
    ],
    'components' => [
        'file::url' => function ($kirby, $file) {
            if ($kirby->visitor()->prefersJson() && $file->template() === 'moment') {
                return $kirby->url() . '/' . $file->parent()->slug() . '/' . $file->name();
            }
            return $file->mediaUrl();
        }
    ],
    'hooks' => [
        'system.loadPlugins:after' => function () {
            $kirby = Kirby::instance();
            $storeId = option('moinframe.moments.storeid', 'moments');

            if ($kirby->page($storeId)?->exists()) {
                return;
            }

            $kirby->impersonate('kirby');
            $momentsPage = $kirby->site()->createChild([
                'slug' => $storeId,
                'template' => 'moments',
                'content' => [
                    'title' => t('moinframe.moments.panel.section.label'),
                    'uuid' => 'moments'
                ]
            ]);
            $momentsPage->changeStatus('unlisted');
            $kirby->impersonate('nobody');
        },
    ],
    'options' => [
        'dateformat' => '',
        'overview' => false,
        'pageid' => '',
        'storeid' => 'moments',
        'feed' => [
            'active' => false,
            'language' => ''
        ],
        'lightbox' => true,
        'thumbs' => [
            'sizes' => [
                'grid' => '(min-width: 900px) 25vw, (min-width: 600px) 33vw, (min-width: 400px) 50vw, 100vw'
            ],
            'srcsets' => [
                'lightbox' => [
                    '300w'  => ['width' => 300, 'height' => 300],
                    '600w'  => ['width' => 600, 'height' => 600],
                    '900w'  => ['width' => 900, 'height' => 900],
                    '1800w' => ['width' => 1800, 'height' => 1800]
                ],
                'lightbox-webp' => [
                    '300w'  => ['width' => 300, 'format' => 'webp', 'height' => 300],
                    '600w'  => ['width' => 600, 'format' => 'webp', 'height' => 600],
                    '900w'  => ['width' => 900, 'format' => 'webp', 'height' => 900],
                    '1800w' => ['width' => 1800, 'format' => 'webp', 'height' => 1800]
                ],
                'grid' => [
                    '300w'  => ['width' => 300, 'height' => 300, 'crop' => true],
                    '600w'  => ['width' => 600, 'height' => 600, 'crop' => true],
                    '900w'  => ['width' => 900, 'height' => 900, 'crop' => true]
                ],
                'grid-webp' => [
                    '300w'  => ['width' => 300, 'format' => 'webp', 'height' => 300, 'crop' => true],
                    '600w'  => ['width' => 600, 'format' => 'webp', 'height' => 600, 'crop' => true],
                    '900w'  => ['width' => 900, 'format' => 'webp', 'height' => 900, 'crop' => true]
                ],
            ]
        ],
        'token' => '',
    ],
    'fieldMethods' => [
        'toMomentsTimestamp' => function ($field) {
            $format = option('date.handler') === 'intl' ? 'yyyy-MM-dd\'T\'HH:mm:ssXXX' : 'c';
            return $field->exists() && $field->isNotEmpty() ? $field->toDate($format) : '';
        },
        'toMomentsDate' => function ($field) {
            if (!$field->exists() || $field->isEmpty()) return '';

            $format = option('moinframe.moments.dateformat');
            if ($format) {
                return $field->toDate($format);
            }

            $locale = kirby()->language()?->code() ?? 'en';
            $formatter = new IntlDateFormatter(
                $locale,
                IntlDateFormatter::SHORT,
                IntlDateFormatter::NONE
            );

            return $formatter->format($field->toTimestamp());
        }
    ],
    'pageModels' => [
        'moments' => 'MomentsPage',
        'moment'  => 'MomentPage',
    ],
    'routes' => require __DIR__ . '/config/routes.php',
    'siteMethods' => [
        'getMomentsStorePage' => function () {
            return option('moinframe.moments.storeid') ? kirby()->page(option('moinframe.moments.storeid')) : site();
        },
        'getMomentsPage' => function () {
            $pageid = option('moinframe.moments.pageid');
            if (!$pageid) {
                return site()->getMomentsStorePage();
            }
            if ($pageid === '/') {
                return site()->homePage();
            }
            return kirby()->page($pageid);
        }
    ],
    'snippets' => [
        'moments' => __DIR__ . '/snippets/moments.php',
        'moments-image' => __DIR__ . '/snippets/moments-image.php',
        'moments-image-footer' => __DIR__ . '/snippets/moments-image-footer.php',
        'moments-icon/clock' => __DIR__ . '/snippets/moments-icon/clock.php',
        'moments-icon/close' => __DIR__ . '/snippets/moments-icon/close.php',
        'moments-icon/prev' => __DIR__ . '/snippets/moments-icon/prev.php',
        'moments-icon/next' => __DIR__ . '/snippets/moments-icon/next.php',
        'moments-lightbox' => __DIR__ . '/snippets/moments-lightbox.php',
        'layout/moments' => __DIR__ . '/snippets/layout/moments.php',
        'blocks/moments' => __DIR__ . '/snippets/blocks/moments.php',
    ],
    'templates' => [
        'moment' => __DIR__ . '/templates/moment.php',
        'moments' => __DIR__ . '/templates/moments.php',
        'feed.xsl' => __DIR__ . '/templates/feed.xsl.php',
        'feed' => __DIR__ . '/templates/feed.php'
    ],
    'translations' => require __DIR__ . '/config/translations.php',
]);
