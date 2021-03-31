<?php
/**
 * Elgg anypage save action
 */

$page_path = get_input('page_path', null, false);
$title = get_input('title', null, false);
$description = get_input('description', null, false);
$render_type = get_input('render_type');
$visible_through_walled_garden = get_input('visible_through_walled_garden', false);
$requires_login = get_input('requires_login', false);
$show_in_footer = get_input('show_in_footer', false);
$allow_unsafe = get_input('allow_unsafe_content', false);
$layout = get_input('layout', 'one_column');
$guid = get_input('guid');

elgg_make_sticky_form('anypage');

// check path
if (!$page_path) {
	register_error(elgg_echo('anypage:no_path'));
	forward(REFERER);
}

if (AnyPage::isPathBadIdea($page_path)) {
	register_error(elgg_echo('anypage:bad_idea'));
	forward(REFERER);
}

// check renderer
switch ($render_type) {
	case 'html':
		if (!$description) {
			register_error(elgg_echo('anypage:no_description'));
			forward(REFERER);
		}
		break;

	case 'view':
//		register_error(elgg_echo('anypage:no_view'));
//		forward(REFERER);
		break;
//	
//	case 'composer':
//		register_error('todo');
//		forward(REFERER);
//		break;

	default:
		register_error(elgg_echo('anypage:invalid_render_type'));
		forward(REFERER);
		break;
}

if ($guid == 0) {
	$page = new AnyPage();
} else {
	$page = get_entity($guid);
	if (!elgg_instanceof($page, 'object', 'anypage')) {
		system_message(elgg_echo('anypage:save:failed'));
		forward(REFERRER);
	}

	if (AnyPage::hasAnyPageConflict($page_path, $page)) {
		register_error(elgg_echo('anypage:any_page_handler_conflict'));
		forward(REFERER);
	}
}

/* @var Page \AnyPage */
$page->setPagePath($page_path);
$page->title = $title;
$page->description = $description;
$page->setRenderType($render_type);
$page->setRequiresLogin($requires_login);
$page->setVisibleThroughWalledGarden($visible_through_walled_garden);
$page->setShowInFooter($show_in_footer);
$page->setLayout($layout);
$page->setAllowUnsafeContent($allow_unsafe);

if ($page->save()) {
	elgg_clear_sticky_form('anypage');
	system_message(elgg_echo('anypage:save:success'));

	if ($guid) {
		forward(REFERER);
	} else {
		forward('admin/appearance/anypage/');
	}
} else {
	register_error(elgg_echo('anypage:save:failed'));
	forward(REFERER);
}
