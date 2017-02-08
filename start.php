<?php

/**
 * Anypage
 */
elgg_register_event_handler('init', 'system', 'anypage_init');

/**
 * Anypage init
 */
function anypage_init() {
	elgg_register_admin_menu_item('configure', 'anypage', 'appearance');
	// fix for selecting the right section in admin area
	elgg_register_plugin_hook_handler('prepare', 'menu:page', 'anypage_init_fix_admin_menu');

	$actions = dirname(__FILE__) . '/actions/anypage';

	elgg_register_action('anypage/save', "$actions/save.php", 'admin');
	elgg_register_action('anypage/check_path', "$actions/check_path.php", 'admin');

	elgg_extend_view('admin.css', 'anypage/admin.css');

	elgg_register_plugin_hook_handler('route', 'all', 'anypage_router');
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'anypage_walled_garden_public_pages');

	elgg_register_event_handler('upgrade', 'system', 'anypage_upgrader');

	// add marked pages to footer menu
	elgg_register_plugin_hook_handler('register', 'menu:footer', 'anypage_prepare_footer_menu');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'anypage_entity_menu_setup');

}

/**
 * Select the right menu entry for admin section
 *
 * @param type $hook
 * @param type $type
 * @param type $value
 * @param type $params
 * @return null
 */
function anypage_init_fix_admin_menu($hook, $type, $value, $params) {
	if (!(elgg_in_context('admin') && elgg_in_context('anypage'))) {
		return null;
	}

	if (isset($value['configure'])) {
		foreach ($value['configure'] as $item) {
			if ($item->getName() == 'appearance') {
				foreach ($item->getChildren() as $child) {
					if ($child->getName() == 'appearance:anypage') {
						$item->setSelected();
						$child->setSelected();
						break;
					}
				}
				break;
			}
		}
	}
}

/**
 * Route to the correct page if defined. Allows a fallthrough to the 404 error page otherwise.
 *
 * @param $hook
 * @param $type
 * @param $value
 * @param $params
 */
function anypage_router($hook, $type, $value, $params) {
	if (!$value) {
		return;
	}

	$handler = elgg_extract('handler', $value);
	$pages = elgg_extract('segments', $value, array());
	array_unshift($pages, $handler);
	$path = AnyPage::normalizePath(implode('/', $pages));

	$page = AnyPage::getAnyPageEntityFromPath($path);
	if (!$page) {
		return;
	}

	if ($page->requiresLogin()) {
		gatekeeper();
	}

	if ($page->getRenderType() === 'view') {
		// route to view
		echo elgg_view($page->getView());
		exit;
	} else {
		// display entity
		$content = elgg_view_entity($page);
		$body = elgg_view_layout($page->getLayout(), array(
			'content' => $content,
			'title' => $page->title,
		));
		echo elgg_view_page($page->title, $body);
		exit;
	}
}

/**
 * Prepare form variables for page edit form.
 *
 * @param AnyPage $page Page instance
 * @param array   $vars View vars
 * @return array
 */
function anypage_prepare_form_vars($page = null, array $vars = []) {
	$values = array(
		'title' => '',
		'page_path' => '',
		'description' => '',
		'render_type' => 'html',
		'visible_through_walled_garden' => false,
		'requires_login' => false,
		'show_in_footer' => false,
		'layout' => 'one_column',
		'guid' => null,
		'entity' => $page,
	);

	$values = array_merge($values, $vars);
	
	if ($page instanceof AnyPage) {
		foreach (array_keys($values) as $field) {
			if (isset($page->$field)) {
				$values[$field] = $page->$field;
			}
		}
	}

	if (elgg_is_sticky_form('anypage')) {
		$sticky_values = elgg_get_sticky_values('anypage');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('anypage');

	return $values;
}

/**
 * Registers pages visible through walled garden with public pages
 *
 * @param type $hook
 * @param type $type
 * @param type $value
 * @param type $params
 * @return type
 */
function anypage_walled_garden_public_pages($hook, $type, $value, $params) {
	$paths_tmp = AnyPage::getPathsVisibleThroughWalledGarden();
	$paths_tmp = array_map('preg_quote', $paths_tmp);
	// the return value expect no leading slash. blarg

	$paths = array();
	foreach ($paths_tmp as $path) {
		$paths[] = ltrim($path, '/');
	}

	$value = array_merge($value, $paths);
	return $value;
}

/**
 * Add links to any registered pages to the footer menu.
 *
 * @param type $hook
 * @param type $type
 * @param type $value
 * @param type $params
 */
function anypage_prepare_footer_menu($hook, $type, $value, $params) {
	$pages = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'anypage',
		'metadata_name' => 'show_in_footer',
		'metadata_value' => true
	));

	foreach ($pages as $page) {
		$item = new ElggMenuItem($page->guid, $page->title, $page->getURL());
		$value[] = $item;
	}

	return $value;
}

/**
 * Setup entity menu
 *
 * @param string         $hook   "register"
 * @param string         $type   "menu:entity"
 * @param ElggMenuItem[] $return Menu
 * @param array          $params Hook params
 * @return ElggMenuItem[]
 */
function anypage_entity_menu_setup($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof AnyPage) {
		return;
	}

	if ($entity->canEdit()) {
		$return[] = ElggMenuItem::factory([
					'name' => 'edit',
					'text' => elgg_echo('edit'),
					'href' => "admin/appearance/anypage/edit?guid=$entity->guid",
		]);
	}

	if ($entity->canDelete()) {
		$return[] = ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_echo('delete'),
			'confirm' => true,
			'is_action' => true,
			'href' => "action/entity/delete?guid=$entity->guid",
		]);
	}

	return $return;
}

/**
 * Runs all upgrades in /upgrades/ dir.
 *
 * @return void
 */
function anypage_upgrader() {
	$db_version = elgg_get_plugin_setting('version', 'anypage');
	include dirname(__FILE__) . '/version.php';

	if ($db_version && ($db_version >= $version)) {
		return;
	}

	$path = dirname(__FILE__) . '/upgrades/';
	$files = elgg_get_upgrade_files($path);

	foreach ($files as $file) {
		$file_version = elgg_get_upgrade_file_version($file);

		if ($file_version <= $db_version) {
			continue;
		}

		if (include "$path{$file}") {
			elgg_set_plugin_setting('version', $file_version, 'anypage');
		}
	}
}

/**
 * Does AnyPage need an upgrade?
 *
 * @return bool
 */
function anypage_needs_upgrade() {
	include dirname(__FILE__) . '/version.php';

	$db_version = elgg_get_plugin_setting('version', 'anypage');

	if (!$db_version) {
		return true;
	}

	return $version > $db_version;
}
