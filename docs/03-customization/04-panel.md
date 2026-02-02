---
title: Panel
---

You can add your moments to the sidebar of your Kirby panel. This plugin provides two static methods to quickly add the menu entry. To add the menu page, you can copy and paste the following snippet to your `site/config/config.php`.

![Screenshot of panel menu](./menu.jpg)


```php
return [
  "ready" => fn() => [
    'panel' => [
      'menu' => [
        'site' => \Femundfilou\Moments\Menu::site(),
        'moments' => \Femundfilou\Moments\Menu::page(),
        '-',
        'users',
        'system'
      ]
    ]
  ]
];
```

## Modify the menu item

You can pass a label and an icon name to both methods to change label or icon of each of the menu entries. Both values need to be a `string`. You can use [all icons](https://getkirby.com/docs/reference/panel/icons) that are provided by the panel or add a [custom icon](https://getkirby.com/docs/reference/plugins/extensions/icons).
```php
return [
  "ready" => fn() => [
    'panel' => [
      'menu' => [
        'site' => \Femundfilou\Moments\Menu::site("Dashboard"),
        'moments' => \Femundfilou\Moments\Menu::page("Momente", "images"),
        '-',
        'users',
        'system'
      ]
    ]
  ]
];
```

<style>
  img {
    width: 100%;
  }
</style>
