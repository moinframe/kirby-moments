---
title: Styles
---

This plugin comes with prebuilt css styles.

> [!TIP] **Default styles**
> If you set `'moinframe.moments.overview' => true` and use the default `moments` page template to display your images, the styles are already included for you and you don't have to add anything manually.


If you use the **snippet** or **block** on your pages, you have to include the styles in the head of your page.

```html
<head>
  <!-- other content-->
  <?= css(Kirby::plugin('moinframe/moments')->asset('moments.css')->url()) ?>
</head>
```


## Remove styles

If you don't want to include the styles, you can overwrite the `layout/moments` snippet and remove the tags. Here is [how you do it](/docs/moinframe-moments/03-customization/01-frontend).

## Modify styles

You can modify the look of the grid and lightbox by overwriting some custom properties.

```css

:root {
  --moments-grid-gap: 1.5rem;
  --moments-grid-columns-xs: 1;
  --moments-grid-columns-s: 2;
  --moments-grid-columns-sm: 3;
  --moments-grid-columns-md: 4;
  --moments-grid-color: #fff;
  --moments-grid-color-empty: lightgrey;
  --moments-grid-background: linear-gradient(rgba(0 0 0 /0.5), #000);
  --moments-lightbox-background: #fff;
  --moments-lightbox-color: #000;
  --moments-lightbox-controls-color: #595959;
  --moments-lightbox-controls-hover-color: #000;
  --moments-lightbox-time-color: #595959;
  --moments-font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  --moments-font-weight: normal;
  --moments-font-size: 0.9rem;
  --moments-line-height: 1;
}
```
