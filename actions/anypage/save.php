<?php
/**
* Elgg anypage save action
*/

$page_path = get_input('page_path', null, false);
$title = get_input('title', null, false);
$description = get_input('description', null, false);
$use_view = get_input('use_view');
$guid = get_input('guid');

elgg_make_sticky_form('anypage');

// check path
if (!$page_path) {
	register_error(elgg_echo('anypage:no_path'));
	forward(REFERER);
}

if (AnyPage::hasPageHandlerConflict($page_path)) {
	register_error(elgg_echo('anypage:page_handler_conflict'));
	forward(REFERER);
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

$page->setPagePath($page_path);
$page->title = $title;
$page->description = $description;
$page->setUseView($use_view);

if ($page->save()) {
	elgg_clear_sticky_form('anypage');
	system_message(elgg_echo('anypage:save:success'));

	if ($guid) {
		forward(REFERER);
	} else {
		forward('admin/appearance/anypage');
	}
} else {
	register_error(elgg_echo('anypage:save:failed'));
	forward(REFERER);
}
