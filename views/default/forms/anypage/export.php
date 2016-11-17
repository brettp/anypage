<?php
/**
 * Export form
 */

elgg_require_js('anypage');

echo elgg_view('output/url', [
	'href' => '#',
	'class' => 'anypage-invert-checkboxes float-alt',
	'text' => elgg_echo('anypage:export:invert_selection'),
]);

echo elgg_format_element('p', [], elgg_echo('anypage:export:instructions'));

$pages = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'anypage',
	'limit' => 0
]);

/* @var \AnyPage $page */
foreach ($pages as $page) {
	echo elgg_view_input("checkbox", [
		'name' => 'guids[]',
		'value' => $page->getGUID(),
		'label' => $page->getDisplayName(),
		'class' => 'anypage-export',
		'checked' => 'checked',
		'default' => false
	]);
}

echo elgg_view_input('submit', [
	'value' => elgg_echo('anypage:export'),
]);