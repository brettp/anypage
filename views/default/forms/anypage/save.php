<?php
/**
 * Edit / add a page
 */
elgg_require_js('forms/anypage/save');

$entity = elgg_extract('entity', $vars);

$values = anypage_prepare_form_vars($entity, $vars);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $entity->guid,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('title'),
	'name' => 'title',
	'value' => $values['title'],
]);

$conflicts = '';
if ($entity) {
	// display any path conflicts
	$conflicts = AnyPage::viewPathConflicts($entity->getPagePath(), $entity);
}

$notice = elgg_format_element('div', [
	'id' => 'anypage-notice',
		], $conflicts);

$entity_path = $entity ? $entity->getPagePath() : '';
$link = elgg_echo('anypage:path_full_link') . ': ';
$link .= elgg_view('output/url', array(
	'href' => $entity_path,
	'text' => elgg_normalize_url($entity_path),
	'class' => 'anypage-updates-on-path-change'
		));

$link = elgg_format_element('div', [], $link);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('anypage:path'),
	'#help' => $link . $notice,
	'name' => 'page_path',
	'value' => $values['page_path'],
	'id' => 'anypage-path',
	'required' => true,
]);

if (elgg_get_config('walled_garden')) {
	echo elgg_view_field([
		'#type' => 'checkbox',
		'label' => elgg_echo('anypage:visible_through_walled_garden'),
		'name' => 'visible_through_walled_garden',
		'checked' => $values['visible_through_walled_garden'],
		'value' => 1,
	]);

	echo elgg_view_field([
		'#type' => 'checkbox',
		'label' => elgg_echo('anypage:requires_login'),
		'name' => 'requires_login',
		'value' => 1,
		'disabled' => true,
		'checked' => true,
		'field_class' => 'elgg-quiet',
	]);
} else {
	echo elgg_view_field([
		'#type' => 'checkbox',
		'label' => elgg_echo('anypage:visible_through_walled_garden'),
		'#help' => elgg_echo('anypage:walled_garden_disabled'),
		'name' => 'visible_through_walled_garden',
		'checked' => $values['visible_through_walled_garden'],
		'value' => 1,
		'disabled' => true,
		'field_class' => 'elgg-quiet',
	]);

	echo elgg_view_field([
		'#type' => 'checkbox',
		'label' => elgg_echo('anypage:requires_login'),
		'name' => 'requires_login',
		'value' => 1,
		'checked' => $values['requires_login'],
	]);
}


$menus = anypage_get_supported_menus();
if ($menus) {
	array_unshift($menus, '');
	echo elgg_view_field([
		'#type' => 'fieldset',
		'#label' => elgg_echo('anypage:menu_item'),
		'align' => 'horizontal',
		'fields' => [
			[
				'#type' => 'select',
				'#label' => elgg_echo('anypage:menu_name'),
				'options' => $menus,
				'name' => 'menu_name',
				'value' => $values['menu_name'],
			],
			[
				'#type' => 'text',
				'#label' => elgg_echo('anypage:menu_section'),
				'name' => 'menu_section',
				'value' => $values['menu_section'],
			],
			[
				'#type' => 'text',
				'#label' => elgg_echo('anypage:menu_parent'),
				'name' => 'menu_parent',
				'value' => $values['menu_parent'],
			],
		],
	]);
}

echo elgg_view_field([
	'#type' => 'select',
	'name' => 'render_type',
	'id' => 'anypage-render-type',
	'options_values' => array(
		'html' => elgg_echo('anypage:use_editor'),
		'view' => elgg_echo('anypage:use_view'),
	),
	'value' => $values['render_type'],
]);
?>

<div id="anypage-render-options" data-render-type="<?= $values['render_type'] ?>">
	<div id="anypage-layout" data-render-type="html">
		<?php
		echo elgg_view_field([
			'#type' => 'select',
			'#label' => elgg_echo('anypage:layout'),
			'options_values' => AnyPage::getLayoutOptions(),
			'name' => 'layout',
			'class' => 'anypage-layout',
			'value' => $values['layout'],
		]);
		echo elgg_view_field([
			'#type' => 'longtext',
			'#label' => elgg_echo('anypage:body'),
			'name' => 'description',
			'value' => $values['description'],
			'id' => 'anypage-description',
		]);
		echo elgg_view_field([
			'#type' => 'checkbox',
			'label' => elgg_echo('anypage:unsafe_html'),
			'#help' => elgg_echo('anypage:unsafe_html:help'),
			'name' => 'unsafe_html',
			'default' => false,
			'value' => 1,
			'checked' => (bool) $values['unsafe_html'],
		]);
		?>
	</div>
	<div id="anypage-view-info" data-render-type="view">
		<p>
			<?php
			echo elgg_echo('anypage:view_info');
			echo " <strong>anypage<span class=\"anypage-updates-on-path-change\">$entity_path</span></strong>";
			?>
		</p>
	</div>
</div>

<?php
$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
		]);

elgg_set_form_footer($footer);
