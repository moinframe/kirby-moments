<?php

declare(strict_types=1);

header('Content-Type: application/rss+xml');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<?xml-stylesheet type="text/xsl" href="' . $site->getMomentsPage()?->url() . '/feed.xsl"?>';

$feedLanguage = $kirby->language()?->code() ?? option('moinframe.moments.feed.language', 'en');
?>
<rss version="2.0">
	<channel>
		<title><?= $page->title()->html() ?></title>
		<link><?= $page->url() ?></link>
		<description><?= $page->description()->html() ?></description>
		<language><?= $feedLanguage ?></language>
		<?php foreach (collection('moments/all') as $moment) : ?>
			<item>
				<title><?= $moment->title()->html() ?></title>
				<link><?= $moment->url() ?></link>
				<?php if ($image = $moment->image()) : ?>
					<description>
						<![CDATA[<img src="<?= $image->resize(900)->url() ?>" alt="<?= $moment->alt()->or($moment->text())->or($moment->title())->html() ?>"/><br><?= $moment->text()->html() ?>]]>
					</description>
				<?php else : ?>
					<description><?= $moment->text()->html() ?></description>
				<?php endif; ?>
				<pubDate><?= $moment->date()->toDate(DATE_RSS) ?></pubDate>
				<guid isPermaLink="true"><?= $moment->url() ?></guid>
			</item>
		<?php endforeach; ?>
	</channel>
</rss>