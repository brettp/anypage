<?php
/**
 * Checks the path and returns JSON like:
 * {
 * 	valid: Bool,
 * 	// optional
 * 	html: "pre-rendered HTML to display"
 */
$path = get_input('path');

$warning = AnyPage::viewPathConflicts($path);

echo json_encode(array(
	'valid' => !(bool) $path,
	'html' => $warning
));