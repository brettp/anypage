<?php
/**
 * Display the title from a page
 *
 * @uses $vars['path']        The CMS page path
 * @uses $vars['as_heading']  If true, the title will be returned inside heading markup
 */

$path = $vars['path'];

$page = AnyPage::getAnyPageEntityFromPath($path);
$title = $page ? $page->title : "<!-- no content for path $path -->";

if (empty($vars['as_heading'])) {
	echo $title;
	return;
}

?>
<div class="elgg-head clearfix">
	<?php echo elgg_view_title($title, array('class' => 'elgg-heading-main')); ?>
</div>
