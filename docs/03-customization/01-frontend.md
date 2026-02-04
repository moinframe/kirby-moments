---
title: Frontend
---

By default the configured store page (e.g. `moments`) will display the grid of your moments. You can also include a block or a snippet to output your moments on another page.

> [!TIP]
> To make the lightbox aware of the page, where your moments are displayed, you can adjust the `pageid` in your configuration. [See how](/docs/moinframe-moments/02-configuration)


## Use the block

To use the `moments` block, include it anywhere in your `type: blocks` or `type: layout` fields.

```yml
myblocks:
  type: blocks
  fieldsets:
    - moments
```

## Use the snippet

To use the snippet, just place it anywhere on your page. It will automatically show the grid of your moments.

```html
<!-- Other content -->
<?php snippet('moments'); ?>
<!-- Other content -->
```


## Overwrite core parts

### Overwrite the layout

The plugin ships with a minimal layout (`layout/moments`) that wraps both the grid and the single-moment views. It contains a bare HTML document with no navigation, fonts, or meta tags — so you will most likely want to replace it with your own.

Create the file `site/snippets/layout/moments.php`:

```php
<?php // site/snippets/layout/moments.php ?>
<?php snippet('your-header'); ?>
<?= $slot ?>
<?php snippet('your-footer'); ?>
```

The `$slot` variable contains the page content (grid or lightbox). Wrap it with your site's header and footer snippets — or any HTML you need.

Have a look at the [source of the original snippet](https://github.com/moinframe/kirby-moments/blob/main/snippets/layout/moments.php).

Of course you can also overwrite one or both templates in your own installation.

### Change an icon

The icons used by the plugin are also snippets. This means, you can overwrite it, too.
If you want to overwrite the `clock` icon, simply create a snippet `/site/snippets/moments-icon/clock.php` and put in your svg code.

Here you can find [all icons used](https://github.com/moinframe/kirby-moments/blob/main/snippets/moments-icon).

### Adjust the overlay of each moment

Each moment displays information such as the date in a snippet called `moments-image-footer.php`. If you want to change that, just create a snippet with the same name in your installation.
