<?php

elgg_push_context('anypage');

$page_guid = get_input('guid');
elgg_entity_gatekeeper($page_guid, 'object', 'anypage');

$page = get_entity($page_guid);

$form_vars = anypage_prepare_form_vars($page);
echo elgg_view_form('anypage/save', array(), $form_vars);
