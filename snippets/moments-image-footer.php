<?php if ($page->date()->isNotEmpty()) : ?>
    <?php if ($page->text()->isNotEmpty()) : ?>
        <div class="moment-image-footer__text">
            <p><?= $page->text()->escape() ?></p>
        </div>
    <?php endif; ?>
    <moment-time class="moment-image-footer__time">
        <?php snippet('moments-icon/clock'); ?>
        <time datetime="<?= $page->date()->toMomentsTimestamp() ?>"><?= $page->date()->toMomentsDate() ?></time>
    </moment-time>
<?php endif; ?>