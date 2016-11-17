<?php
/**
 * Edit / add a page
 */

extract($vars);

$is_walled_garden = elgg_get_config('walled_garden');

$desc_class = $render_type != 'html' ? 'class="hidden"' : '';
$view_info_class = $render_type != 'view' ? 'class="hidden"' : '';
$layout_class = $render_type == 'view' ? 'class="hidden"' : '';

$visible_check = $visible_through_walled_garden ? 'checked="checked"' : '';
if ($is_walled_garden) {
	$requires_login_check = 'checked="checked"';
} else {
	$requires_login_check = $requires_login ? 'checked="checked"' : '';
}

$show_in_footer_check = $show_in_footer ? 'checked="checked"' : '';

$layout_options = AnyPage::getLayoutOptions();

?>
<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'title',
		'value' => $title
	));
	?>
</div>

<div>
	<label><?php echo elgg_echo('anypage:path'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'page_path',
		'value' => $page_path,
		'id' => 'anypage-path'
	));

	// display any path conflicts
	?><div id="anypage-notice"><?php
	if ($entity) {
		echo AnyPage::viewPathConflicts($entity->getPagePath(), $entity);
	}
	?></div><?php

	echo elgg_echo('anypage:path_full_link') . ': ';
	echo elgg_view('output/url', array(
		'href' => $entity ? $entity->getPagePath() : '',
		'text' => elgg_normalize_url($entity ? $entity->getPagePath() : ''),
		'class' => 'anypage-updates-on-path-change'
	));
	?>
</div>

<div>
<?php if ($is_walled_garden) { ?>
	<label>
		<input type="checkbox" name="visible_through_walled_garden"
			   value="1" <?php echo $visible_check; ?> />
		<?php echo elgg_echo('anypage:visible_through_walled_garden'); ?>
	</label>
	<br />

	<label class="elgg-quiet">
		<input type="checkbox" name="requires_login" value="1"
			<?php echo $requires_login_check; ?> disabled="disabled"/>
		<?php echo elgg_echo('anypage:requires_login'); ?>
	</label>
<?php } else { ?>
	<label class="elgg-quiet">
		<input type="checkbox" name="visible_through_walled_garden"
			   value="1" <?php echo $visible_check; ?> disabled="disabled"/>
		<?php echo elgg_echo('anypage:visible_through_walled_garden:disabled'); ?>
	</label>
	<br />

	<label>
		<input type="checkbox" name="requires_login" value="1"
			<?php echo $requires_login_check; ?> />
		<?php echo elgg_echo('anypage:requires_login'); ?>
	</label>
<?php } ?>
</div>

<div>
	<label>
	<input type="checkbox" name="show_in_footer" value="1" <?php echo $show_in_footer_check; ?> />
	<?php
		echo elgg_echo('anypage:show_in_footer');
	?>
	</label>
</div>

<div>
	<label>
	<?php
		echo elgg_view('input/dropdown', array(
			'name' => 'render_type',
			'id' => 'anypage-render-type',
			'options_values' => array(
				'html' => elgg_echo('anypage:use_editor'),
				'view' => elgg_echo('anypage:use_view'),
			),
			'value' => $render_type
		));
	?>
	</label>
</div>

<div id="anypage-layout" class="<?php echo $layout_class;?>">
	<label>
		<?php echo elgg_echo('anypage:layout'); ?>:

		<?php
			echo elgg_view('input/dropdown', array(
				'options_values' => $layout_options,
				'name' => 'layout',
				'class' => 'anypage-layout',
				'value' => $layout
			));
		?>

		<span class="elgg-text-help elgg-quiet">
			<?php echo elgg_echo('anypage:layout:help'); ?>
		</span>
	</label>
</div>

<div id="anypage-view-info" <?php echo $view_info_class;?>>
	<p>
	<?php
	echo '<p>' . elgg_echo('anypage:view_info');
	echo " anypage<span class=\"anypage-updates-on-path-change\">$page_path</span>";
	echo '</p>';
	?>
	</p>
</div>

<div id="anypage-description" <?php echo $desc_class;?>>
	<label><?php echo elgg_echo('anypage:body'); ?></label><br />
	<?php
	echo elgg_view('input/longtext', array(
		'name' => 'description',
		'value' => $description
	));
	?>
</div>

<div class="elgg-foot">
<?php

	if ($guid) {
		echo elgg_view('input/hidden', ['name' => 'guid', 'value' => $guid]);
		echo elgg_view('output/url', [
			'class' => 'float elgg-button elgg-button-action',
			'text' => elgg_echo('delete'),
			'href' => 'action/anypage/delete?guid=' . $guid,
			'confirm' => true
		]);
	}

echo elgg_view('input/submit', array(
	'value' => elgg_echo("save"),
	'class' => 'float-alt elgg-button elgg-button-action'
	));

?>
</div>