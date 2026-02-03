---

title: Configuration

---

You can change certain aspects of the plugin through your `site/config/config.php` configuration.

## Change date format

You can change, how the date is being displayed. By default, the plugin will check wether you're using `intl` or `datetime` in your installation and will set an english date like this `MM/dd/YYYY`. You can overwrite the format like this:

```php
return [
  // ... other options
  'moinframe.moments' => [
    'dateformat' => 'dd.MM.YYYY',
  ]
];
```

## Use a different page for display

You can change the visible parent of your moments. This will replace your `storeid` in the lightbox url, but still upload all images to your storage page. The storage page will redirect to this page.

```php
return [
  // ... other options
  'moinframe.moments' => [
    'pageid' => 'stories',
  ]
];
```

In this example, the images will still be stored in the page `moments` but display as `https://your-website.test/stories/image-1`.

### Use your homepage as the display page

You can set `pageid` to `'/'` to display moments directly on your homepage. This will make moment URLs appear at the root level of your site.

```php
return [
  // ... other options
  'moinframe.moments' => [
    'pageid' => '/',
  ]
];
```

In this example, the images will be stored in `moments` but display as `https://your-website.test/image-1`. The RSS feed will also be available at `https://your-website.test/feed.xml`.

## Use a different page for storage

You can change the page used to store all images by changing the slug here. Be sure to also change your template to `moments`, e.g. rename the content file to `moments.txt` or `moments.xx.txt` on a multi-language page. You can also delete the currently used store page and the plugin will generate a new store page automatically.

```php
return [
  // ... other options
  'moinframe.moments' => [
    'storeid' => 'momentsstore',
  ]
];
```

##  Change the thumbnails

You can adjust the thumbnails being created by modifying the srcsets. You can also set `xx-avif` or `xx-webp` to `null` if you don't want to use those formats.

```php
return [
  // ... other options
  'moinframe.moments' => [
    'thumbs' => [
      'sizes' => [
        'grid' => '(min-width: 900px) 25vw, (min-width: 600px) 33vw, (min-width: 400px) 50vw, 100vw',
        'lightbox' => '100vw',
      ],
      'srcsets' => [
        'lightbox' => [
          '300w'  => ['width' => 300, 'height' => 300],
          '600w'  => ['width' => 600, 'height' => 600],
          '900w'  => ['width' => 900, 'height' => 900],
          '1800w'  => ['width' => 1800, 'height' => 1800]
        ],
        'lightbox-avif' => [
          '300w'  => ['width' => 300, 'format' => 'avif', 'height' => 300],
          '600w'  => ['width' => 600, 'format' => 'avif', 'height' => 600],
          '900w'  => ['width' => 900, 'format' => 'avif', 'height' => 900],
          '1800w'  => ['width' => 1800, 'format' => 'avif', 'height' => 1800]
        ],
        'lightbox-webp' => [
          '300w'  => ['width' => 300, 'format' => 'webp', 'height' => 300],
          '600w'  => ['width' => 600, 'format' => 'webp', 'height' => 600],
          '900w'  => ['width' => 900, 'format' => 'webp', 'height' => 900],
          '1800w'  => ['width' => 1800, 'format' => 'webp', 'height' => 1800]
        ],
        'grid' => [
          '300w'  => ['width' => 300, 'height' => 300, 'crop' => true],
          '600w'  => ['width' => 600, 'height' => 600, 'crop' => true],
          '900w'  => ['width' => 900, 'height' => 900, 'crop' => true]
        ],
        'grid-avif' => [
          '300w'  => ['width' => 300, 'format' => 'avif', 'height' => 300, 'crop' => true],
          '600w'  => ['width' => 600, 'format' => 'avif', 'height' => 600, 'crop' => true],
          '900w'  => ['width' => 900, 'format' => 'avif', 'height' => 900, 'crop' => true]
        ],
        'grid-webp' => [
          '300w'  => ['width' => 300, 'format' => 'webp', 'height' => 300, 'crop' => true],
          '600w'  => ['width' => 600, 'format' => 'webp', 'height' => 600, 'crop' => true],
          '900w'  => ['width' => 900, 'format' => 'webp', 'height' => 900, 'crop' => true]
        ],
      ]
    ],
  ]
];
```

## Enable endpoint for Apple Shortcuts

You can use an Apple Shortcut to upload images quickly. See the [configuration](/shortcuts).


## Disable Enhanced Lightbox

By default, the JavaScript-enhanced lightbox is enabled. When enabled, clicking a moment opens it in an overlay without a full page reload, with keyboard navigation (arrow keys, escape) and URL updates via the History API. The lightbox is progressively enhanced - it still works without JavaScript. You can disable it like this:

```php
return [
  // ... other options
  'moinframe.moments' => [
    'lightbox' => false,
  ]
];
```

## Disable Moments Overview Page

By default, the moments overview page (listing all moments) is enabled. You can disable it to redirect visitors to the homepage instead. This is useful if you only want moments to be accessible via direct links or embedded in other pages.

```php
return [
  // ... other options
  'moinframe.moments' => [
    'overview' => false,
  ]
];
```

## Disable RSS Feed

By default, the plugin creates an RSS feed that can be subscribed to. You can disable the feed like this.

```php
return [
  // ... other options
  'moinframe.moments' => [
    'feed' => [
      'active' => false
    ],
  ]
];
```

