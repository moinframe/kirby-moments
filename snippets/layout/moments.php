<!DOCTYPE html>
<html lang="<?= $kirby->language()?->code() ?? 'en' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title() ?></title>
    <?= css('/media/plugins/moinframe/moments/reset.css'); ?>
    <?= css('/media/plugins/moinframe/moments/moments.css'); ?>
</head>

<body>
    <?= $slot ?>
    <?= js('/media/plugins/moinframe/moments/moments.min.js'); ?>
</body>

</html>