---
title: API Tokens
---

API tokens let you authenticate uploads to your Moments feed. Each user can create named tokens directly from the Kirby Panel.

## Setup

Add the token section to a user blueprint, for example `site/blueprints/users/default.yml`:

```yaml
tabs:
  moments:
    label: Moments
    sections:
      tokens:
        type: moments-tokens
```

This adds an "API Tokens" section to the user account page in the Panel.

## Creating a token

1. Go to your user account in the Panel
2. Open the **Moments** tab
3. Click **New token**
4. Enter a name (e.g. "My iPhone")
5. Copy the token immediately -- it will only be shown once

The token is sent as an `X-MOMENTS-TOKEN` header when uploading. Only a hash of the token is stored in your account, so the plaintext cannot be recovered.

## Deleting a token

Click the options menu on any token in the list and select **Delete**. Devices using that token will immediately lose upload access.

## Using a token

Send a `POST` request to `/v1/moments/new` with the token in the `X-MOMENTS-TOKEN` header:

```sh
curl -X POST https://your-website.test/v1/moments/new \
  -H "X-MOMENTS-TOKEN: your-token-here" \
  -F "file=@photo.jpg"
```

This works well with the [Apple Shortcut](/docs/moinframe-moments/05-shortcuts).

## Config token

Alternatively, you can set a single shared token in your config. This is useful if you don't need per-user tokens or prefer a simpler setup.

```php
return [
  'moinframe.moments.token' => 'my-secret',
];
```

If both a config token and per-user tokens exist, the config token is checked first.
