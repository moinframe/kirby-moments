---
title: Installation
---

You can install this plugin in three different ways, depending on personal preference.

### Install via download

[Download](https://github.com/moinframe/kirby-moments/releases/latest) and copy the latest release to `/site/plugins/kirby-moments`.

### Install as git submodule

```sh
git submodule add https://github.com/moinframe/kirby-moments.git site/plugins/kirby-moments
```

### Install as composer package

```sh
composer require moinframe/kirby-moments
```

## Content

This plugin needs a page to store all images. By default, the plugin will look for a page with the slug `moments` and the template of `moments`.

After install, the plugin will create the store page itself and set it to published.

> [!TIPP] **Custom page slug**
> You can customize the slug being used through the [configuration](/configuration). If you change the slug after initialization, be sure to delete the previously generated page.

## Apple Shortcut

You can use an Apple Shortcut to upload images quickly. See the [configuration](/shortcuts).
