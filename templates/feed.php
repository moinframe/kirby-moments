<?php

declare(strict_types=1);

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<?xml-stylesheet type="text/xsl" href="' . $site->getMomentsPage()?->url() . '/feed.xsl"?>';

$feedLanguage = $kirby->language()?->code() ?? option('moinframe.moments.feed.language', 'en');
$momentsPage = $site->getMomentsPage();
?>
<rss version="2.0">
    <channel>
        <title><?= $site->title()->html() ?></title>
        <link><?= $momentsPage?->url() ?? $site->url() ?></link>
        <description><?= $site->description()->html() ?></description>
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
                <?php if ($moment->date()->isNotEmpty()) : ?>
                    <pubDate><?= date(DATE_RSS, $moment->date()->toTimestamp()) ?></pubDate>
                    <formattedDate><?= $moment->date()->toMomentsDate() ?></formattedDate>
                <?php endif; ?>
                <guid isPermaLink="true"><?= $moment->url() ?></guid>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>