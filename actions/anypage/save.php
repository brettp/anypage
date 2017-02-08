<?php
/**
* Elgg anypage save action
*/

$page_path = get_input('page_path', null, false);
$title = get_input('title', null, false);
$unsafe_html = (bool) get_input('unsafe_html', false);
$filter_html = !$unsafe_html;
$description = get_input('description', null, $filter_html);
$render_type = get_input('render_type');
$visible_through_walled_garden = get_input('visible_through_walled_garden', false);
$requires_login = get_input('requires_login', false);
$show_in_footer = get_input('show_in_footer', false);
$layout = get_input('layout', 'one_column');
$guid = get_input('guid');

elgg_make_sticky_form('anypage');

// check path
if (!$page_path) {
	return elgg_error_response(elgg_echo('anypage:no_path'));
}

// check renderer
switch ($render_type) {
	case 'html':
		if (!$description) {
			return elgg_error_response(elgg_echo('anypage:no_description'));
		}
		break;
	
	case 'view':
		break;

	default:
		return elgg_error_response(elgg_echo('anypage:invalid_render_type'));
}

if ($guid == 0) {
	$page = new AnyPage();
} else {
	$page = get_entity($guid);
	if (!elgg_instanceof($page, 'object', 'anypage')) {
		return elgg_error_response(elgg_echo('anypage:save:failed'));
	}

	if (AnyPage::hasAnyPageConflict($page_path, $page)) {
		return elgg_error_response(elgg_echo('anypage:any_page_handler_conflict'));
	}
}

$page->setPagePath($page_path);
$page->title = $title;
$page->description = $description;
$page->setRenderType($render_type);
$page->setRequiresLogin($requires_login);
$page->setVisibleThroughWalledGarden($visible_through_walled_garden);
$page->setShowInFooter($show_in_footer);
$page->setLayout($layout);
$page->unsafe_html = $unsafe_html;

if ($page->save()) {
	elgg_clear_sticky_form('anypage');

	if ($guid) {
		$forward_url = REFERER;
	} else {
		$forward_url = 'admin/appearance/anypage/';
	}

	return elgg_ok_response('', elgg_echo('anypage:save:success'), $forward_url);
} else {
	return elgg_error_response(elgg_echo('anypage:save:failed'));
}
