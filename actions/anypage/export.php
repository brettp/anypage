<?php
/**
 * Exports any page objects as serialized php
 */

$guids = get_input('guids', ELGG_ENTITIES_ANY_VALUE);

if (is_array($guids)) {
	$guids = array_unique($guids);
}

$pages = elgg_get_entities([
	'type' => 'object',
	'subtype' => 'anypage',
	'guids' => $guids,
	'limit' => 0
]);

$export = [];
foreach ($pages as $page) {
	$export[] = $page->export();
}

$domain = parse_url(elgg_get_site_url(), PHP_URL_HOST);
$dt = date('Y-m-d');
$filename = implode('-', array('static-content', $domain, $dt)) . '.sphp';

$data = serialize($export);

header('Content-Type: text/x-serialized-php');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($data));

echo $data;

exit;