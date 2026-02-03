<?php

snippet('layout/moments', slots: true);
$allMoments = collection('moments/all')->flip();
?>

<dialog class="moment-lightbox">
	<a href="<?= $page->parent()->url() ?>" class="moment-close" aria-label="<?= t('moinframe.moments.aria-label.close') ?>">
		<?php snippet('moments-icon/close'); ?>
	</a>
	<div class="moment">
		<?php snippet('moments-image', ['moment' => $page]);
		?>
		<div class="moment-controls">
			<?php if ($page->hasPrev($allMoments)) : ?>
				<a href="<?= $page->prev($allMoments)->url() ?>" class="moment-controls__prev" aria-label="<?= t('moinframe.moments.aria-label.prev') ?>">
					<?php snippet('moments-icon/prev'); ?>
				</a>
			<?php endif; ?>
			<?php if ($page->hasNext($allMoments)) : ?>
				<a href="<?= $page->next($allMoments)->url() ?>" class="moment-controls__next" aria-label="<?= t('moinframe.moments.aria-label.next') ?>">
					<?php snippet('moments-icon/next'); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</dialog>
<?php endsnippet();
