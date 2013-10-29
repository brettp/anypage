<?php
/**
 * Add a layout param
 */

$options = array(
	'type' => 'object',
	'subtype' => 'anypage',
	'limit' => 0
);

$batch = new ElggBatch('elgg_get_entities', $options);

foreach ($batch as $page) {
	if ($page->getRenderType() == 'html') {
		$page->setLayout('one_column');
	}
}

system_message(elgg_echo('anypage:upgrade_success'));