<?php

$momentsPage = site()->getMomentsPage();

// Add redirect routes if store and page are different
if ($momentsPage && !$momentsPage->is($page)) {
	go($momentsPage->url(), 301);
}

snippet('layout/moments', slots: true);
snippet('moments');
endsnippet();
