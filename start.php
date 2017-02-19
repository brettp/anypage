<?php

/**
 * Anypage
 */
elgg_register_event_handler('init', 'system', 'anypage_init');
elgg_register_event_handler('ready', 'system', 'anypage_ready');
elgg_register_plugin_hook_handler('route:rewrite', 'all', 'anypage_router');

/**
 * Anypage init
 * @return void
 */
function anypage_init() {
	elgg_register_admin_menu_item('configure', 'anypage', 'appearance');
	// fix for selecting the right section in admin area
	elgg_register_plugin_hook_handler('prepare', 'menu:page', 'anypage_init_fix_admin_menu');

	$actions = dirname(__FILE__) . '/actions/anypage';

	elgg_register_action('anypage/save', "$actions/save.php", 'admin');
	elgg_register_action('anypage/check_path', "$actions/check_path.php", 'admin');

	elgg_extend_view('admin.css', 'anypage/admin.css');

	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'anypage_walled_garden_public_pages');

	elgg_register_event_handler('upgrade', 'system', 'anypage_upgrader');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'anypage_entity_menu_setup');

	elgg_register_page_handler('anypage', 'anypage_page_handler');

}

/**
 * Setup menus when system is ready
 * @return void
 */
function anypage_ready() {

	$menus = anypage_get_supported_menus();
	foreach ($menus as $menu) {
		elgg_register_plugin_hook_handler('register', "menu:$menu", 'anypage_prepare_menu');
	}

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
 * Rewrite route if it is a configured anypage route
 *
 * @param string $hook   "route:rewrite"
 * @param string $type   "all"
 * @param array  $route  Route
 * @param array  $params Hook params
 * @return array
 */
function anypage_router($hook, $type, $route, $params) {
	if (!$route) {
		return;
	}

	$handler = elgg_extract('identifier', $route);
	$segments = elgg_extract('segments', $route, []);
	array_unshift($segments, $handler);
	
	$path = AnyPage::normalizePath(implode('/', $segments));

	$page = AnyPage::getAnyPageEntityFromPath($path);
	if (!$page) {
		return;
	}

	return [
		'identifier' => 'anypage',
		'segments' => $segments,
	];
}

/**
 * /anypage handler
 *
 * @param array $segments URL segments
 * @return bool
 */
function anypage_page_handler($segments) {

	$path = AnyPage::normalizePath(implode('/', $segments));

	echo elgg_view_resource('anypage', [
		'path' => $path,
	]);
	
	return true;
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
		'layout' => 'one_column',
		'guid' => null,
		'entity' => $page,
		'unsafe_html' => false,
		'menu_name' => '',
		'menu_section' => '',
		'menu_parent' => '',
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
 * Add links to any registered pages to the menu.
 *
 * @param string         $hook   "register"
 * @param string         $type   "menu:<menu_name>"
 * @param ElggMenuItem[] $return Menu
 * @param array          $params Hook params
 * @return ElggMenuItem[]
 */
function anypage_prepare_menu($hook, $type, $return, $params) {

	list($prefix, $menu_name) = explode(':', $type);
	if ($prefix !== 'menu') {
		return;
	}

	$pages = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'anypage',
		'metadata_name' => 'menu_name',
		'metadata_value' => $menu_name,
		'limit' => 0,
		'batch' => true,
	));

	foreach ($pages as $page) {
		$return[] = ElggMenuItem::factory([
			'name' => "anypage:$page->guid",
			'text' => $page->getDisplayName(),
			'href' => $page->getURL(),
			'section' => $page->menu_section ? : 'default',
			'parent_name' => $page->menu_parent ? : null,
		]);
	}

	return $return;
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

/**
 * Returns a list of menus that page links can be added to
 * @return array
 */
function anypage_get_supported_menus() {

	$menus = [
		'site',
		'footer',
	];

	return elgg_trigger_plugin_hook('menus', 'anypage', null, $menus);
}