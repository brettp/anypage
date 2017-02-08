<?php

$path = elgg_extract('path', $vars);
$page = AnyPage::getAnyPageEntityFromPath($path);

if (!$page) {
	forward('', '404');
}

if ($page->requiresLogin()) {
	gatekeeper();
}

if ($page->getRenderType() === 'view') {
	// route to view
	echo elgg_view($page->getView());
} else {
	// display entity
	$content = elgg_view_entity($page);
	$body = elgg_view_layout($page->getLayout(), array(
		'content' => $content,
		'title' => $page->title,
	));
	echo elgg_view_page($page->title, $body);
}