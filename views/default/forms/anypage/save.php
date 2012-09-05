<?php
/**
 * Edit / add a page
 */

extract($vars);

$desc_class = $render_type != 'html' ? 'class="hidden"' : '';
$view_info_class = $render_type != 'view' ? 'class="hidden"' : '';
$composer_class = $render_type != 'composer' ? 'class="hidden"' : '';

$visible_check = $visible_through_walled_garden ? 'checked="checked"' : '';
$requires_login_check = $requires_login ? 'checked="checked"' : '';

$show_in_footer_check = $show_in_footer ? 'checked="checked"' : '';

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
	<label>
<?php if (elgg_get_config('walled_garden')) { ?>
		<input type="checkbox" name="visible_through_walled_garden" value="1" <?php echo $visible_check; ?> />
		<?php
			echo elgg_echo('anypage:visible_through_walled_garden');
		?>
<?php } else { ?>
		<input type="checkbox" name="requires_login" value="1" <?php echo $requires_login_check; ?> />
		<?php
			echo elgg_echo('anypage:requires_login');
		?>
<?php } ?>
	</label>
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
				'composer' => elgg_echo('anypage:use_composer'),
			),
			'value' => $render_type
		));
	?>
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
	<label><?php echo elgg_echo('anypage:body'); ?></label>
	<?php
	echo elgg_view('input/longtext', array(
		'name' => 'description',
		'value' => $description
	));
	?>
</div>

<div id="anypage-composer" <?php echo $composer_class ;?>>

</div>

<div class="elgg-foot">
<?php

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
	echo elgg_view('output/confirmlink', array(
		'class' => 'float-alt elgg-button elgg-button-action',
		'text' => elgg_echo('delete'),
		'href' => 'action/anypage/delete?guid=' . $guid
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo("save")));

?>
</div>