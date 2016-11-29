<?php
/**
 * Imports AnyPages from flat HTML files or serialized AnyPage objects
 */

use Symfony\Component\HttpFoundation\File\UploadedFile;

$overwrite = get_input('overwrite', false);
$files = _elgg_services()->request->files;
$imports = $files->get('imports');
$layout = get_input('layout', '');
$html_fallback = get_input('html_fallback', true);

/* @var UploadedFile $data */
if (!$imports || $imports[0] == null) {
	register_error(elgg_echo('anypage:import:data_missing'));
	forward(REFERRER);
}

$new_pages = [];

/* @var UploadedFile $file */
foreach ($imports as $file) {
	$sdata = file_get_contents($file->getRealPath());
	if ($sdata === false) {
		register_error(elgg_echo('anypage:import:error_reading_file', [$file->getClientOriginalName()]));
		continue;
	}

	$data = unserialize($sdata);
	if ($data) {
		// it's a serialized anypage array
		foreach ($data as $page_data) {
			$page = AnyPage::newFromExported($page_data);
			if (!$page) {
				register_error(elgg_echo('anypage:import:unknown_error_processing_entry'));
			}
			$new_pages[] = $page;
		}
	} else {
		// it's not serialized, so check if we need to interpret as html
		if ($html_fallback) {
			$content = file_get_contents($file->getRealPath());

			// convert to UTF8, trying to transliterate chars but ignoring any errors
			// this is needed because of paste from Word >:O
			$content = iconv(mb_detect_encoding($content, mb_detect_order(), true), "UTF-8//TRANSLIT//IGNORE", $content);

			$page = AnyPage::newFromHtml($file->getClientOriginalName(), $content);

			if (!$page) {
				register_error(elgg_echo('anypage:import:error_processing_file', [$file->getClientOriginalName()]));
			} else {
				$page->setLayout($layout);
				$new_pages[] = $page;
			}
		} else {
			register_error(elgg_echo('anypage:import:error_processing_file', [$file->getClientOriginalName()]));
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