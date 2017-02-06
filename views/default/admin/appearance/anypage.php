<?php

/**
 * Settings for anypage
 */
elgg_push_context('anypage');

if (anypage_needs_upgrade()) {
	$title = elgg_echo('anypage:needs_upgrade');
	$body = elgg_echo('anypage:needs_upgrade_body');
	$body .= elgg_view('output/url', array(
		'href' => 'upgrade.php',
		'text' => elgg_echo('anypage:upgrade_now')
	));

	echo elgg_view_module('info', $title, $body, array('class' => 'anypage-message pvm elgg-message elgg-state-error'));

	// don't show the pages because they will probably be wrong.
	return;
}

$page_guid = get_input('guid');
if ($page_guid) {
	// for BC, we support the guid query element
	forward("admin/appearance/anypage/edit?guid=$page_guid");
}

elgg_register_menu_item('title', array(
	'name' => 'anypage:new',
	'href' => "admin/appearance/anypage/new",
	'text' => elgg_echo("anypage:new"),
	'link_class' => 'elgg-button elgg-button-action',
));

$dbprefix = elgg_get_config('dbprefix');
echo elgg_list_entities([
	'types' => 'object',
	'subtypes' => 'anypage',
	'joins' => [
		"JOIN {$dbprefix}objects_entity oe ON oe.guid = e.guid",
	],
	'order_by' => 'oe.title ASC',
	'no_results' => elgg_echo('anypage:no_pages'),
	'item_view' => 'object/anypage/summary',
	'list_class' => 'anypage-list',
]);
