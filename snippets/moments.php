<?php

use Kirby\Cms\App as Kirby;
use Kirby\Data\Json;

$moments = collection('moments/all');
$enhanced = option('moinframe.moments.lightbox', false);
$closeUrl = site()->getMomentsPage()->url() . '#moments';
?>
<?php if ($moments->count() === 0) : ?>
    <p class="moment-grid-empty-text"><?= t('moinframe.moments.no-moments') ?></p>
<?php else: ?>
    <?php if ($enhanced) : ?><moments-lightbox close-url="<?= $closeUrl ?>"><?php endif; ?>
        <ul class="moments-grid" id="moments">
            <?php foreach ($moments as $moment) : ?>
                <li>
                    <a href="<?= $moment->url() ?>" class="moment" rel="nofollow"<?= $enhanced ? ' data-moment="' . esc(Json::encode($moment->toMomentsInterface()), 'attr') . '"' : '' ?>>
                        <?php snippet('moments-image', ['moment' => $moment, 'type' => 'grid']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if ($enhanced) : ?>
            <?php snippet('moments-lightbox'); ?>
        </moments-lightbox>
        <?= js(Kirby::plugin('moinframe/moments')->asset('moments.min.js')->url(), ['type' => 'module']) ?>
    <?php endif; ?>
<?php endif; ?>