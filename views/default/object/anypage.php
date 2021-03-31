<?php
/**
 * Any page default view
 */

$page = elgg_extract('entity', $vars);

if ($page->allow_unsafe_content) {
	echo $page->description;
} else {
	echo elgg_view('output/longtext', [
		'value' => $page->description,
	]);
}
