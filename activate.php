<?php
/**
 * Anypage
 *
 * Set version
 * Register classes
 * Add example pages
 * Add admin notice
 */

if (!elgg_get_plugin_setting('version', 'anypage')) {
	elgg_set_plugin_setting('version', '2012083000', 'anypage');
}

if (get_subtype_id('object', 'anypage')) {
	update_subtype('object', 'anypage', 'AnyPage');
} else {
	add_subtype('object', 'anypage', 'AnyPage');
}

// add example if no pages
$count = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'anypage',
	'count' => true
));

if (!$count) {
	$page = new AnyPage();
	$page->title = elgg_echo('anypage:example:view:title');
	$page->setPagePath('/example/test_view');
	$page->setRenderType('view');
	$page->save();

	$page = new AnyPage();
	$page->title = elgg_echo('anypage:example:title');
	$page->setPagePath('/anypage/example');
	$page->description = elgg_echo('anypage:example_page:description');
	$page->setRenderType('html');
	$page->setLayout('one_column');
	$page->save();

	elgg_add_admin_notice('anypage', elgg_echo('anypage:activate:admin_notice',
			array(elgg_normalize_url('admin/appearance/anypage'))));
}