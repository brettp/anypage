<?php
/**
 * Any page default view
 */

$page = elgg_extract('entity', $vars);

echo elgg_view('output/longtext', array(
	'value' => $page->description,
	'sanitize' => !$page->unsafe_html,
));
