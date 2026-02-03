<?php

use Kirby\Cms\App as Kirby;
?>

<!DOCTYPE html>
<html lang="<?= $kirby->language()?->code() ?? 'en' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title() ?></title>
    <?= css(Kirby::plugin('moinframe/moments')->asset('reset.css')->url()) ?>
    <?= css(Kirby::plugin('moinframe/moments')->asset('moments.css')->url()) ?>
</head>

<body>
    <?= $slot ?>
</body>

</html>