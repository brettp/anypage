<?php
/**
 * Add a layout param
 */

$options = array(
	'type' => 'object',
	'subtype' => 'anypage',
	'limit' => 0,
	'metadata_names' => ['show_in_footer'],
);

$batch = new ElggBatch('elgg_get_entities_from_metadata', $options);
$batch->setIncrementOffset(false);

foreach ($batch as $page) {
	if ($page->show_in_footer) {
		$page->menu_name = 'footer';
		$page->menu_section = 'default';
		$page->menu_parent = '';
	} else {
		$page->menu_name = '';
		$page->menu_section = '';
		$page->menu_parent = '';
	}
	unset($page->show_in_footer);
}

system_message(elgg_echo('anypage:upgrade_success'));