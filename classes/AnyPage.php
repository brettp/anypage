<?php

/**
 * AnyPage page
 *
 * Access handled by setRequiresLogin() instead of access_id to allow proper redirects instead of
 * 404 errors.
 *
 */
class AnyPage extends ElggObject {
	private $renderTypes = array(
		'view', 'html', 'composer'
	);

	/**
	 * Set subclass.
	 * @return bool
	 */
	public function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'anypage';

		return true;
	}

	/**
	 * Override access id, owner, and container.
	 *
	 * @return int
	 */
	public function save() {
		$site = elgg_get_site_entity();

		$this->access_id = ACCESS_PUBLIC;
		$this->container_guid = $site->getGUID();
		$this->owner_guid = $site->getGUID();

		return parent::save();
	}

	/**
	 * Sets the path for this page. The path is normalized.
	 *
	 * @param string $path
	 * @return bool
	 */
	public function setPagePath($path) {
		return $this->page_path = $this->normalizePath($path);
	}

	/**
	 * Returns the path for this page.
	 * @return string
	 */
	public function getPagePath() {
		return $this->page_path;
	}

	/**
	 * Returns the view for this page.
	 *
	 * Currently only magic views are supported as anypage/<view_name>
	 * @return mixed False if not set to use a view, else the view name as a string
	 */
	public function getView() {
		if ($this->getRenderType() != 'view') {
			return false;
		}

		return 'anypage' . $this->getPagePath();
	}

	/**
	 * Sets the render type for the page. One of html, view, or composer.
	 *
	 * @param string $type The render type
	 * @return bool
	 */
	public function setRenderType($type) {
		if (!in_array($type, $this->renderTypes)) {
			return false;
		}
		
		return $this->render_type = $type;
	}

	/**
	 * How should this page be rendered?
	 *
	 * @return One of view, html, or composer
	 */
	public function getRenderType() {
		return $this->render_type;
	}

	/**
	 * Set the layout for this page to use
	 *
	 * @param string $layout
	 * @return bool
	 */
	public function setLayout($layout) {
		if (!in_array($layout, $this->getLayouts())) {
			return false;
		}
		return $this->layout = $layout;
	}

	/**
	 * Return the layout to use for this page.
	 *
	 * @return string
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * Gets the public facing URL for the page.
	 * 
	 * @return string
	 */
	public function getURL() {
		return elgg_normalize_url($this->getPagePath());
	}

	/**
	 * Set if this page is visible though walled gardens
	 *
	 * @param bool $visible
	 * @return bool
	 */
	public function setVisibleThroughWalledGarden($visible = true) {
		return $this->visible_through_walled_garden = (bool) $visible;
	}

	/**
	 * Is this page visible through walled gardens?
	 *
	 * @return bool
	 */
	public function isVisibleThroughWalledGarden() {
		return $this->visible_through_walled_garden;
	}

	/**
	 * Set if this page requires a login.
	 *
	 * @param type $requires
	 * @return type
	 */
	public function setRequiresLogin($requires = false) {
		return $this->requires_login = (bool) $requires;
	}

	/**
	 * Does this page require a login?
	 * 
	 * @return bool
	 */
	public function requiresLogin() {
		return $this->requires_login;
	}

	/**
	 * Set if a link to this page should be shown in the footer menu.
	 *
	 * @param bool $show
	 * @return bool
	 */
	public function setShowInFooter($show = false) {
		return $this->show_in_footer = $show;
	}

	/**
	 * Should a link to this page be added to the footer menu?
	 *
	 * @return type bool
	 */
	public function showInFooter() {
		return $this->show_in_footer;
	}

	/**
	 * Does $path conflict with a registered page handler?
	 *
	 * Path is normalized.
	 *
	 * @param string $path
	 * @return boolean
	 */
	static function hasPageHandlerConflict($path) {
		$page_handlers = _elgg_services()->router->getPageHandlers();

		// remove first slashes to get the real handler
		$path = ltrim(AnyPage::normalizePath($path), '/');

		$pages = explode('/', $path);
		$handler = array_shift($pages);
		
		if (isset($page_handlers[$handler])) {
			return true;
		}

		return false;
	}

	/**
	 * Does $path conflict with a registered AnyPage path?
	 * If $page is passed, its location is ignored.
	 *
	 * Path is normalized.
	 *
	 * @param string $path
	 * @param AnyPage $page
	 * @return bool
	 */
	static function hasAnyPageConflict($path, $page = null) {
		$path = AnyPage::normalizePath($path);

		if ($page && $page->getPagePath() == $path) {
			return false;
		}

		$paths = AnyPage::getRegisteredPagePaths();
		return in_array($path, $paths);
	}

	/**
	 * Normalize a path. Removes trailing /s. Adds leading /s.
	 *
	 * @param string $path
	 * @return string
	 */
	static function normalizePath($path) {
		return '/' . ltrim(sanitise_filepath($path, false), '/');
	}

	/**
	 * Returns all registered AnyPage paths from the plugin settings
	 *
	 * @return array guid => path_name
	 */
	static function getRegisteredPagePaths() {
		$entities = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => 'anypage',
			'limit' => 0
				));

		$paths = array();
		foreach ($entities as $entity) {
			$paths[$entity->getGUID()] = $entity->getPagePath();
		}

		return $paths;
	}

	/**
	 * Returns an AnyPage entity from its path
	 *
	 * @param string $path
	 * @return mixed AnyPage entity or false
	 */
	public static function getAnyPageEntityFromPath($path) {
		$path = AnyPage::normalizePath($path);
		$entities = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => 'anypage',
			'metadata_name' => 'page_path',
			'metadata_value' => $path,
			'limit' => 1
				));

		if (!$entities) {
			return false;
		}

		return $entities[0];
	}

	/**
	 * Returns paths for pages marked as public through walled garden
	 *
	 * @param string $path
	 * @return mixed AnyPage entity or false
	 */
	public static function getPathsVisibleThroughWalledGarden() {
		$entities = elgg_get_entities_from_metadata(array(
			'type' => 'object',
			'subtype' => 'anypage',
			'metadata_name' => 'visible_through_walled_garden',
			'metadata_value' => '1'
				));

		$paths = array();
		foreach ($entities as $page) {
			$paths[] = $page->getPagePath();
		}

		return $paths;
	}

	/**
	 * Check if the first segment of a path would fail Elgg's default rewrite rules,
	 * which only support a-z0-9_-
	 *
	 * @param string $path
	 * @return bool
	 */
	public static function hasUnsupportedPageHandlerCharacter($path) {
		// get "page handler" chunk
		$path = ltrim(AnyPage::normalizePath($path), '/');
		$pages = explode('/', $path);
		$handler = array_shift($pages);

		$regexp = '/[^A-Za-z0-9\_\-]/';
		return preg_match($regexp, $handler);
	}

	/**
	 * Returns rendered HTML for any path conflicts.
	 *
	 * @param string  $path
	 * @param AnyPage $page An AnyPage object that will be ignored when checking for conflicts.
	 * @return string Rendered HTML.
	 */
	public static function viewPathConflicts($path, $page = null) {
		$return = '';

		// unsupported characters
		if (AnyPage::hasUnsupportedPageHandlerCharacter($path)) {
			$module_title = elgg_echo('anypage:warning');
			$msg = elgg_echo('anypage:unsupported_page_handler_character');
			$return .= elgg_view_module('info', $module_title, $msg, array('class' => 'anypage-message pvm elgg-message elgg-state-error'));
		}

		// page handler conflict
		if (AnyPage::hasPageHandlerConflict($path)) {
			$module_title = elgg_echo('anypage:warning');
			$msg = elgg_echo('anypage:page_handler_conflict');
			$return .= elgg_view_module('info', $module_title, $msg, array('class' => 'anypage-message pvm elgg-message elgg-state-error'));
		}

		// any page conflict
		if (AnyPage::hasAnyPageConflict($path, $page)) {
			$module_title = elgg_echo('anypage:warning');
			$conflicting_page = AnyPage::getAnyPageEntityFromPath($path);
			$link = elgg_view('output/url', array(
				'href' => "admin/appearance/anypage?guid=$conflicting_page->guid",
				'text' => $conflicting_page->title
			));
			$msg = elgg_echo('anypage:anypage_conflict', array($link));
			$return .= elgg_view_module('info', $module_title, $msg, array('class' => 'anypage-message pvm elgg-message elgg-state-error'));
		}

		return $return;
	}

	/**
	 * Returns layouts in an array of view => Pretty Name
	 * Suitable for use in dropdown input views
	 *
	 */
	public static function getLayoutOptions() {
		$views = self::getLayouts();

		// core views that don't work right
		$ignored_views = array(
			'two_column_left_sidebar',
			'widgets',
		);

		$options = array();

		foreach ($views as $view) {
			if (!in_array($view, $ignored_views)) {
				$options[$view] = ucwords(str_replace('_', ' ', $view));
			}
		}

		return $options;
	}

	/**
	 * Return all valid layouts WITHOUT the page/layouts/ prefix.
	 *
	 * @global type $CONFIG
	 * @return array
	 */
	public static function getLayouts() {
		global $CONFIG;
		
		// some helper functions borrowed from the dev's mod
		$view_list = array();

		$dir = $CONFIG->viewpath . "default/page/layouts/";

		$handle = opendir($dir);
		while ($file = readdir($handle)) {
			if ($file[0] != '.') {
				$extension = strrchr(trim($file, "/"), '.');
				
				if ($extension === ".php") {
					$view_list[] = $dir . $file;
				}
			}
		}
		closedir($handle);

		// remove base path and php extension
		array_walk($view_list, create_function('&$v,$k', 'global $CONFIG; $v = substr($v, strlen($CONFIG->viewpath . "default/"), -4);'));

		$views = elgg_get_config('views');

		$plugin_layouts = array_filter(array_keys($views->locations['default']), create_function('$view', 'return (0 === strpos($view, "page/layouts"));'));

		$view_list = array_merge($plugin_layouts, $view_list);
		array_walk($view_list, create_function('&$v,$k', '$v = str_replace("page/layouts/", "", $v);'));
		sort($view_list);
	
		return $view_list;
	}


	/**
	 * Deprecated methods 1.3
	 */

	/**
	 * Sets if this page should use a view instead of the built in object display.
	 *
	 * @param bool $use_view
	 * @return bool
	 * @deprecated 1.3 Use setRenderType('view') or ('html')
	 */
	public function setUseView($use_view = false) {
		if ($use_view) {
			return $this->setRenderType('view');
		} else {
			return $this->setRenderType('html');
		}
	}

	/**
	 * Returns if this page uses a view.
	 *
	 * @return bool
	 * @deprecated 1.3 Use 'getRenderType() === 'view'
	 */
	public function usesView() {
		return $this->getRenderType() == 'view';
	}
}