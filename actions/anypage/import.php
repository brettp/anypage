<?php
/**
 * Imports AnyPages from flat HTML files or serialized AnyPage objects
 */

use Symfony\Component\HttpFoundation\File\UploadedFile;

$overwrite = get_input('overwrite', false);
$files = _elgg_services()->request->files;
$imports = $files->get('imports');
$layout = get_input('layout', '');

/* @var UploadedFile $data */
if (!$imports) {
	register_error(elgg_echo('anypage:import:data_missing'));
	forward(REFERRER);
}

$new_pages = [];

/* @var UploadedFile $file */
foreach ($imports as $file) {
	if ($file->guessExtension() == 'html' || $file->guessClientExtension() == 'html') {
		$page = AnyPage::newFromHtml($file->getClientOriginalName(), file_get_contents($file->getRealPath()));
		$page->setLayout($layout);
		if (!$page) {
			register_error(elgg_echo('anypage:import:error_processing_file', [$file->getClientOriginalName()]));
		} else {
			$new_pages[] = $page;
		}
	} else {
		$sdata = file_get_contents($file->getRealPath());
		if (!$sdata) {
			register_error(elgg_echo('anypage:import:error_reading_file', [$file->getClientOriginalName()]));
		}

		$data = unserialize($sdata);
		if (!$data) {
			register_error(elgg_echo('anypage:import:error_processing_file', [$file->getClientOriginalName()]));
			forward(REFERRER);
		}

		foreach ($data as $page) {
			// clone to remove any data from the export that doesn't make sense for the import
			$new_pages[] = clone $page;
		}
	}
}

$imported = 0;

/* @var \AnyPage $page */
foreach ($new_pages as $page) {
	$existing = AnyPage::getAnyPageEntityFromPath($page->getPagePath());

	if ($existing && $overwrite) {
		$existing->delete();
	} else if ($existing) {
		register_error(elgg_echo('anypage:import:not_overwriting_path', [$existing->title, $page->getPagePath()]));
		continue;
	}

	if (!$page->save()) {
		register_error(elgg_echo('anypage:import:error_saving', [$page->title]));
	} else {
		$imported++;
	}
}

if ($imported > 0) {
	system_message(elgg_echo('anypage:import:success', [$imported]));
}

forward(REFERRER);