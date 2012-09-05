<?php
/**
 * Rewrite the use_view to the new render_type md.
 */

$options = array(
	'type' => 'object',
	'subtype' => 'anypage',
	'limit' => 0
);

$batch = new ElggBatch('elgg_get_entities', $options);

foreach ($batch as $page) {
	if ($page->use_view) {
		$type = 'view';
	} else {
		$type = 'html';
	}

	$page->setRenderType($type);
	unset($page->use_view);
}

system_message(elgg_echo('anypage:upgrade_success'));