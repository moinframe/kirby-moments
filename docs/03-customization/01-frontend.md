---
title: Frontend
---

By default the configured store page (e.g. `moments`) will display the grid of your moments. You can also include a block or a snippet to output your moments on another page.

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

> [!TIPP]
> To make the lightbox aware of the page, where your moments are displayed, you can adjust the `pageid` in your configuration. [See how](/docs/femundfilou-kirby-moments/03-configuration)


## Overwrite core parts

### Adjust the template used for `moments` and `moment`

The `moments` and `moment` template both use a shared snippet called `layout/moments`. You can overwrite that by adding it to your `site/snippets` folder.

Have a look at the [source of the original snippet](https://github.com/femundfilou/kirby-moments/blob/main/snippets/layout/moments.php).

Of course you can also overwrite one or both templates in your own installation.

### Change an icon

The icons used by the plugin are also snippets. This means, you can overwrite it, too.
If you want to overwrite the `clock` icon, simple create a snippet `/site/snippets/moments-icon/clock.php` and put in your svg code.

Here you can find [all icons used](https://github.com/femundfilou/kirby-moments/blob/main/snippets/moments-icon).

### Adjust the overlay of each moment

Each moment displays information such as the date in a snippet called `moments-image-footer.php`. If you want to change that, just create a snippet with the same name in your installation.
