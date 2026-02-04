<?php
/** @var ?Kirby\Cms\Page $moment */
$moment = $moment ?? null;
$allMoments = collection('moments/all')->flip();
$closeUrl = site()->getMomentsPage()->url() . '#moments';
?>
<dialog class="moments-lightbox"<?= $moment ? ' open' : '' ?>>
    <a href="<?= $closeUrl ?>" class="moments-close" rel="nofollow" aria-label="<?= t('moinframe.moments.aria-label.close') ?>">
        <?php snippet('moments-icon/close'); ?>
    </a>
    <div class="moments-item"<?= $moment ? '' : ' data-placeholder' ?>>
        <?php if ($moment) : ?>
            <?php snippet('moments-image', ['moment' => $moment]); ?>
            <div class="moments-controls">
                <?php if ($moment->hasPrev($allMoments)) : ?>
                    <a href="<?= $moment->prev($allMoments)->url() ?>" class="moments-controls__prev" rel="nofollow" aria-label="<?= t('moinframe.moments.aria-label.prev') ?>">
                        <?php snippet('moments-icon/prev'); ?>
                    </a>
                <?php endif; ?>
                <?php if ($moment->hasNext($allMoments)) : ?>
                    <a href="<?= $moment->next($allMoments)->url() ?>" class="moments-controls__next" rel="nofollow" aria-label="<?= t('moinframe.moments.aria-label.next') ?>">
                        <?php snippet('moments-icon/next'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</dialog>
