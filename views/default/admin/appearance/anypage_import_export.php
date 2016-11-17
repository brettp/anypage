<?php
/**
 * Interface for importing and exporting any pages
 */

$export = elgg_view_form('anypage/export');

$import = elgg_view_form('anypage/import', [
	'enctype' => 'multipart/form-data'
]);

echo elgg_view_module('featured', elgg_echo('anypage:export'), $export, [
	'class' => 'mbl'
]);

echo elgg_view_module('featured', elgg_echo('anypage:import'), $import);
