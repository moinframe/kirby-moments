<?php
if ($image = $moment->image()) :
	$type = $type ?? 'lightbox';
	$sizes = $type === 'grid' ? option('moinframe.moments.thumbs.sizes.grid', 'auto') : option('moinframe.moments.thumbs.sizes.lightbox', '100vw');
	$alt = $moment->alt()->or($moment->text())->or($moment->title())->escape();
?>
	<figure class="moment-image">
		<picture>
			<?php if ($type === 'grid') : ?>
				<?php if (option('moinframe.moments.thumbs.srcsets.grid-webp')) : ?>
					<source srcset="<?= $image->srcset(option('moinframe.moments.thumbs.srcsets.grid-webp')) ?>" sizes="<?= $sizes ?>" type="image/webp">
				<?php endif; ?>
				<?php $gridCrop = $image->crop(600); ?>
				<img alt="<?= $alt ?>" src="<?= $gridCrop->url() ?>" srcset="<?= $image->srcset(option('moinframe.moments.thumbs.srcsets.grid')) ?>" sizes="<?= $sizes ?>" width="<?= $gridCrop->width() ?>" height="<?= $gridCrop->height() ?>">
			<?php else : ?>
				<?php if (option('moinframe.moments.thumbs.srcsets.lightbox-webp')) : ?>
					<source srcset="<?= $image->srcset(option('moinframe.moments.thumbs.srcsets.lightbox-webp')) ?>" sizes="<?= $sizes ?>" type="image/webp">
				<?php endif; ?>
				<?php $lightboxCrop = $image->crop(900); ?>
				<img alt="<?= $alt ?>" src="<?= $lightboxCrop->url() ?>" srcset="<?= $image->srcset(option('moinframe.moments.thumbs.srcsets.lightbox')) ?>" sizes="<?= $sizes ?>" width="<?= $lightboxCrop->width() ?>" height="<?= $lightboxCrop->height() ?>">
			<?php endif; ?>
		</picture>
	</figure>
	<div class="moment-image-footer">
		<?php snippet('moments-image-footer', ['page' => $moment]); ?>
	</div>
<?php endif; ?>