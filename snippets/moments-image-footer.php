<?php if ($page->date()->isNotEmpty()) : ?>
    <?php if ($page->text()->isNotEmpty()) : ?>
        <div class="moments-image-footer__text">
            <p><?= $page->text()->escape() ?></p>
        </div>
    <?php endif; ?>
    <div class="moments-image-footer__time">
        <?php snippet('moments-icon/clock'); ?>
        <time class="moment-time" datetime="<?= $page->date()->toMomentsTimestamp() ?>"><?= $page->date()->toMomentsDate() ?></time>
    </div>
<?php endif; ?>