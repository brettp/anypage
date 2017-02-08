<?php
/**
 * Checks the path and returns JSON like:
 * {
 *	normalized_path: "path",
 * 	valid: Bool,
 * 	// optional
 * 	html: "pre-rendered HTML to display"
 * }
 */
$path = get_input('path');
$page = get_entity(get_input('guid'));
$warning = AnyPage::viewPathConflicts($path, $page);

$data = [
	'normalized_path' => AnyPage::normalizePath($path),
	'valid' => !(bool) $warning,
	'html' => $warning
];

return elgg_ok_response($data);