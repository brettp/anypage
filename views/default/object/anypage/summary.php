<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof AnyPage) {
	return;
}

$metadata = elgg_view_menu('entity', [
	'entity' => $entity,
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
]);

$summary = elgg_view('output/longtext', [
	'value' => elgg_get_excerpt($entity->description),
]);

echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'content' => $summary,
	'metadata' => $metadata,
]);