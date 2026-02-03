<?php
$moments = collection('moments/all');
?>
<?php if ($moments->count() === 0) : ?>
	<p class="moment-grid-empty-text"><?= t('moinframe.moments.no-moments') ?></p>
<?php else: ?>
	<ul class="moments-grid">
		<?php foreach ($moments as $moment) : ?>
			<li>
				<a href="<?= $moment->url() ?>" class="moment">
					<?php
					snippet('moments-image', ['moment' => $moment, 'type' => 'grid']);
					?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>