<?php

// Redirect to homepage if overview is disabled
if (!option('moinframe.moments.overview', true)) {
	go(site()->homePage()->url(), 302);
}

$momentsPage = site()->getMomentsPage();

// Add redirect routes if store and page are different
if ($momentsPage && !$momentsPage->is($page)) {
	go($momentsPage->url(), 301);
}

snippet('layout/moments', slots: true);
snippet('moments');
endsnippet();
