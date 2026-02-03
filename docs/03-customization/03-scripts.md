---
title: Javascript
---

This plugin comes with a light javascript implementation that is **totally optional**.

The scripts do two things:
1. Convert the timestamp to a human readable date like "two minutes ago" if the moment is not older than 3 days.
2. Add keyboard controls to the lightbox. You can use the Arrow keys to navigate back and forth and the Esc key to go back to the grid.

> [!TIPP] **Default scripts**
> If you just use the default `moments` page template to display your images, the scripts are already included for you and you don't have to add anything manually.

## Add scripts

If you use the snippet or block on your pages, you have to include the scripts in the body of your page.

```html
<head>
  <!-- other content-->
  <?= js('/media/plugins/moinframe/moments/moments.min.js'); ?>
</head>
```



## Remove scripts

If you don't want to include the scripts, you can overwrite the `layout/moments` snippet and remove the tags. Here is [how you do it](/output#overwrite-core-parts).
