<?php
/**
 * Settings for anypage
 */
elgg_push_context('anypage');

$page_guid = get_input('guid');
$page = get_entity($page_guid);

if ($page_guid && !elgg_instanceof($page, 'object', 'anypage')) {
	forward(REFERER, 404);
}

if (anypage_needs_upgrade()) {
	$title = elgg_echo('anypage:needs_upgrade');
	$body = elgg_echo('anypage:needs_upgrade_body');
	$body .= elgg_view('output/url', [
		'href' => 'upgrade.php',
		'text' => elgg_echo('anypage:upgrade_now'),
	]);

	echo elgg_view_module('info', $title, $body, ['class' => 'anypage-message pvm elgg-message elgg-state-error']);

	// don't show the pages because they will probably be wrong.
	return true;
}

if (!$page_guid) {
	// default to first page if it exists
	$pages = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'anypage',
		'limit' => 1,
	]);
	if ($pages) {
		$page = $pages[0];
	}
}

echo elgg_view('output/url', [
	'href' => "admin/appearance/anypage/new",
	'text' => elgg_echo("anypage:new"),
	'class' => 'elgg-button elgg-button-action float-alt',
]);

$tabs = elgg_view('anypage/admin_tabs', ['current_page' => $page]);

if (!$tabs) {
	echo elgg_echo('anypage:no_pages');
} else {
	echo $tabs;

	$form_vars = anypage_prepare_form_vars($page);
	echo elgg_view_form('anypage/save', [], $form_vars);
}