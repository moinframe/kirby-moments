<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns="http://www.w3.org/1999/xhtml">
    <xsl:output method="html" doctype-public="XSLT-compat" encoding="UTF-8" indent="yes" />

    <xsl:template match="/">
        <html>

        <head>
            <title><xsl:value-of select="rss/channel/title" /> — Feed</title>
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <style>
                *,
                *::before,
                *::after {
                    box-sizing: border-box;
                    margin: 0;
                    padding: 0;
                }

                body {
                    font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
                    background: #f5f5f5;
                    color: #1a1a1a;
                    line-height: 1.5;
                    -webkit-font-smoothing: antialiased;
                }

                .feed-banner {
                    background: #fff;
                    border-bottom: 1px solid #e5e5e5;
                    padding: 2rem 1.5rem;
                }

                .feed-banner-inner {
                    max-width: 1140px;
                    margin: 0 auto;
                    display: flex;
                    align-items: flex-start;
                    gap: 1rem;
                }

                .feed-icon {
                    flex-shrink: 0;
                    width: 2.5rem;
                    height: 2.5rem;
                    background: #f4a123;
                    border-radius: .5rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-top: .15rem;
                }

                .feed-icon svg {
                    width: 1.4rem;
                    height: 1.4rem;
                    fill: #fff;
                }

                .feed-info h1 {
                    font-size: 1.25rem;
                    font-weight: 600;
                    line-height: 1.3;
                }

                .feed-info p {
                    color: #666;
                    font-size: .9rem;
                    margin-top: .25rem;
                }

                .feed-info .feed-hint {
                    margin-top: .75rem;
                    font-size: .8rem;
                    color: #999;
                }

                .feed-info .feed-hint code {
                    background: #f0f0f0;
                    padding: .15em .4em;
                    font-size: .85em;
                }

                .grid {
                    max-width: 1140px;
                    margin: 0 auto;
                    padding: 1.5rem;
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                    gap: 1rem;
                    list-style: none;
                }

                .grid li {
                    background: #fff;
                }

                .grid li a {
                    display: block;
                    overflow: hidden;
                    text-decoration: none;
                    color: inherit;
                    transition: box-shadow .2s ease, transform .2s ease;
                }

                .grid li a:hover {
                    box-shadow: 0 4px 20px rgba(0, 0, 0, .12);
                    transform: translateY(-2px);
                }

                .grid li a img {
                    display: block;
                    width: 100%;
                    aspect-ratio: 1;
                    object-fit: cover;
                }

                .item-image {
                    font-size: 0;
                    line-height: 0;
                }

                .item-meta {
                    padding: .75rem;
                    background: #fff;
                }

                .item-meta .item-title {
                    font-size: .85rem;
                    font-weight: 500;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .item-meta .item-date {
                    font-size: .75rem;
                    color: #999;
                    margin-top: .2rem;
                }

                @media (prefers-color-scheme: dark) {
                    body {
                        background: #111;
                        color: #e5e5e5;
                    }

                    .feed-banner {
                        background: #1a1a1a;
                        border-color: #333;
                    }

                    .feed-info p {
                        color: #999;
                    }

                    .feed-info .feed-hint {
                        color: #666;
                    }

                    .feed-info .feed-hint code {
                        background: #2a2a2a;
                    }

                    .grid li a {
                        background: #2a2a2a;
                    }

                    .grid li a:hover {
                        box-shadow: 0 4px 20px rgba(0, 0, 0, .4);
                    }

                    .item-meta {
                        background: #1a1a1a;
                    }

                    .item-meta .item-date {
                        color: #666;
                    }
                }
            </style>
        </head>

        <body>
            <div class="feed-banner">
                <div class="feed-banner-inner">
                    <div class="feed-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="6.18" cy="17.82" r="2.18" />
                            <path d="M4 4.44v2.83c7.03 0 12.73 5.7 12.73 12.73h2.83c0-8.59-6.97-15.56-15.56-15.56zm0 5.66v2.83c3.9 0 7.07 3.17 7.07 7.07h2.83c0-5.47-4.43-9.9-9.9-9.9z" />
                        </svg>
                    </div>
                    <div class="feed-info">
                        <h1><xsl:value-of select="rss/channel/title" /></h1>
                        <xsl:if test="rss/channel/description != ''">
                            <p><xsl:value-of select="rss/channel/description" /></p>
                        </xsl:if>
                        <p class="feed-hint">This is an RSS feed. Copy the URL into your feed reader to subscribe.</p>
                    </div>
                </div>
            </div>
            <ul class="grid">
                <xsl:for-each select="rss/channel/item">
                    <li>
                        <a href="{link}" target="_blank">
                            <div class="item-image"><xsl:value-of select="description" disable-output-escaping="yes" /></div>
                            <div class="item-meta">
                                <div class="item-title"><xsl:value-of select="title" /></div>
                                <div class="item-date"><xsl:value-of select="formattedDate" /></div>
                            </div>
                        </a>
                    </li>
                </xsl:for-each>
            </ul>
        </body>

        </html>
    </xsl:template>
</xsl:stylesheet>