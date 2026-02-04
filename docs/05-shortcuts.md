---
title: Apple Shortcuts
---

To upload a new image on iOS or Mac OS, you can use an Apple Shortcut to do so.

You can install the shortcut via this link:


[Install Apple Shortcut](https://www.icloud.com/shortcuts/8e095f1e30f84ecab7f408a1473d3a5a)



## Enable Apple Shortcut

If you want to enable the usage of Apple Shortcuts to upload new images, you have to set a secret in your `site/config/config.php`. This secret protects your website, so only you can upload images to your feed.

```php
return [
  // ... Other options
  "moinframe.moments.token" => "my-secret"
]
```

> [!WARNING] **Secrets**
> Do not make this token public. Don't commit it to a git repository. If you store your `config.php` in git, please use a `.env` file to securely store your secrets.
> [How to use .env with Kirby](https://github.com/bnomei/kirby3-dotenv)


## Configuration of the shortcut

To connect the shortcut to your website you need to configure two parameters in the shortcut itself. When installing the shortcut, you will be prompted to add these parameters.

### Authorization token

Replace `your-token` with the `moinframe.moments.token` you defined in your `site/config/config.php`.

### API Endpoint

Add your websites domain followed by `/v1/moments/new`, for example `https://your-website.test/v1/moments/new`.
