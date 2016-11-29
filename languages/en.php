<?php
/**
 * Anypage language
 */

return [
	'admin:appearance:anypage' => 'AnyPage Pages',
	'admin:appearance:anypage:new' => 'New Page',
	'item:object:anypage' => 'Anypages',

	'anypage:warning' => 'Warning',
	'anypage:error' => 'Error',
	'anypage:unsupported_page_handler_character' => "This path uses a character that is unsupported "
		. "in the default version of Elgg's .htaccess rewrite rules. You can only use letters, "
		. "numbers, _, and - in paths before a /. Example: /test/page.html works but /page.html doesn't. <br /><br />"
		. "If you are using Apache and Elgg's default rewrite rules, this page will not work!."
	,

	'anypage:page_handler_conflict' => 'This path conflicts with a built-in Elgg page '
		. 'and could cause unexpected behavior. Only keep this path if you know what you are doing.',

	'anypage:anypage_conflict' => 'This path conflicts with the AnyPage page "%s". Click its title to view, edit, or delete that page.',

	'anypage:bad_idea' => 'This path conflicts with an important built-in page, so it will not be saved.',

	'anypage:new' => 'New Page',
	'anypage:no_pages' => 'You have not created any pages yet. Click the "New Page" link above to add a page.',

	'anypage:needs_upgrade' => 'AnyPage Upgrade Required',
	'anypage:needs_upgrade_body' => 'AnyPage requires an upgrade. ',
	'anypage:upgrade_now' => 'Upgrade now.',
	'anypage:upgrade_success' => 'Successfully updated AnyPages',

	// form
	'anypage:path' => 'Page path',
	'anypage:path_full_link' => 'Full link',
	'anypage:view_info' => 'This page will use the following view:',
	'anypage:body' => 'Page body',
	'anypage:visible_through_walled_garden' => 'Visible through Walled Garden',
	'anypage:visible_through_walled_garden:disabled' => 'Visible through Walled Garden (Walled Garden is not enabled)',
	'anypage:requires_login' => 'Requires login',
	'anypage:show_in_footer' => 'Add a link in the site footer',
	'anypage:layout' => 'Layout',
	'anypage:layout:help' => '(See the readme file for more information)',

	'anypage:use_view' => 'Display a custom view',
	'anypage:use_editor' => 'Use an editor to write this page',
	'anypage:use_composer' => 'Use the composer to build this page',

	// actions
	'anypage:save:success' => 'Saved page',
	'anypage:delete:success' => 'Page deleted',
	'anypage:no_path' => 'You must enter a path',
	'anypage:admin_path' => 'Cannot override /admin.',
	'anypage:no_view' => 'You must enter a view.',
	'anypage:no_description' => 'You must enter a page body.',
	'anypage:any_page_handler_conflict' => 'The path you entered is already registered to a page.',
	'anypage:delete:failed' => 'Could not delete page.',

	// example pages
	'anypage:example:title' => 'AnyPage Example Page',
	'anypage:example_page:description' => 'This is an example of a page rendered using AnyPage!',

	'anypage:example:view:title' => 'AnyPage Example Page (Using View)',
	'anypage:test_page_view' => 'This is an example of a page rendered by AnyPage using a view!',

	'anypage:activate:admin_notice' => 'AnyPage has added example pages. Use the <a href="%s">admin interface</a> to add more pages.',

	'admin:appearance:anypage_import_export' => 'Import / Export AnyPage',

	'anypage:export' => 'Export',
	'anypage:export:instructions' => 'Check the pages you want to export.',
	'anypage:export:invert_selection' => 'Invert selection',

	'anypage:import' => 'Import',
	'anypage:import:instructions' => 'Select AnyPage .sphp or .html files. If you use a HTML files, the name of '
		. 'each file will be used as the title and URL of the imported AnyPage. HTML files will always create a new page. '
		. 'Use shift or control to select multiple files.',

	'anypage:import:file' => '.sphp or .html files',
	'anypage:import:html_fallback' => 'Allow importing plain HTML files. If off, all files must be AnyPage exports.',
	'anypage:import:overwrite' => 'Overwrite existing pages with the same path, otherwise conflicting files are ignored',
	'anypage:import:error_reading_file' => 'Error reading file: %s',
	'anypage:import:error_processing_file' => 'Error processing file: %s',

	'anypage:import:not_overwriting_path' => 'Not overwriting page "%s" (%s)',
	'anypage:import:error_saving' => 'Could not save page',
	'anypage:import:data_missing' => 'Missing data file',
	'anypage:import:unknown_error_processing_entry' => "Unknown error processing AnyPage export entry",
	'anypage:import:success' => 'Imported AnyPages: %s',
];
