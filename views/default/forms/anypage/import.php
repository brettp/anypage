<?php
/**
 * Import form
 */

echo elgg_format_element('p', [], elgg_echo('anypage:import:instructions'));

echo elgg_view_input('file', [
	'label' => elgg_echo('anypage:import:file'),
	'name' => 'imports[]',
	'multiple' => 'multiple',
]);

echo elgg_view_input('dropdown', [
	'label' => "Default layout for HTML file imports (use none if importing full HTML pages):",
	'options_values' => AnyPage::getLayoutOptions(),
	'name' => 'layout',
	'class' => 'anypage-layout',
	'value' => '',
]);

echo elgg_view_input('checkbox', [
	'name' => 'overwrite',
	'value' => 1,
	'label' => elgg_echo('anypage:import:overwrite'),
]);

echo elgg_view_input('submit');

