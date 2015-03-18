<?php
/**
 * Display the content from a page
 *
 * @uses $vars['path'] The CMS page path
 */

$path = $vars['path'];

$page = AnyPage::getAnyPageEntityFromPath($path);
if (!$page) {
	echo "<!-- no content for path $path -->";
	return;
}

echo elgg_view('output/longtext', array(
	'value' => $page->description
));
