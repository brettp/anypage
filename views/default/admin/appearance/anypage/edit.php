<?php

elgg_push_context('anypage');

$page_guid = get_input('guid');
elgg_entity_gatekeeper($page_guid, 'object', 'anypage');

$page = get_entity($page_guid);

echo elgg_view_form('anypage/save', [], [
	'entity' => $page,
]);
