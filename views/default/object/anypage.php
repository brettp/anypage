<?php
/**
 * Any page default view
 */

$page = elgg_extract('entity', $vars);

echo elgg_view('output/longtext', [
	'value' => $page->description,
]);
