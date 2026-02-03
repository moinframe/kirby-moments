<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns="http://www.w3.org/1999/xhtml">
<xsl:output method="html" doctype-public="XSLT-compat" encoding="UTF-8" indent="yes"/>

<xsl:template match="/">
    <html>
        <head>
            <title>Photo Feed</title>
			<style>
				<?= F::read(asset('/media/plugins/moinframe/moments/reset.css')->root()) ?>
				<?= F::read(asset('/media/plugins/moinframe/moments/moments.css')->root()) ?>
				ul.moments-grid {
					max-width: 1140px;
					margin: 0 auto;
					padding: 1.5rem;
					box-sizing: border-box;
				}

				.moment-image > img {
					aspect-ratio: 1;
					width: 100%;
					height: 100%;
					object-fit: cover;
					opacity: 1 !important;
				}
			</style>

        </head>
        <body>
			<ul class="moments-grid">
				<xsl:for-each select="rss/channel/item">
				<li>
					<a class="moment" href="{link}" target="_blank">
						<div class="moment-image">
							<xsl:value-of select="description" disable-output-escaping="yes"/>
						</div>
					</a>
				</li>
				</xsl:for-each>
			</ul>
        </body>
    </html>
</xsl:template>
</xsl:stylesheet>
