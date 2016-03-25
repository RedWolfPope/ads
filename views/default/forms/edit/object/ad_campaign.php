<?php

$entity = elgg_extract('entity', $vars);
$container = elgg_extract('container', $vars);

$required = elgg_format_attributes(array(
	'title' => elgg_echo('campaigns:required'),
	'class' => 'required'
		));
?>
<div>
	<label <?= $required ?>><?= elgg_echo('campaigns:title') ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'title',
		'value' => elgg_extract('title', $vars, $entity->title),
		'required' => true,
	));
	?>
</div>
<div>
	<label <?= $required ?>><?= elgg_echo('campaigns:description') ?></label>
	<?php
	echo elgg_view('input/longtext', array(
		'name' => 'description',
		'value' => elgg_extract('description', $vars, $entity->description),
		'required' => true,
	));
	?>
</div>
<?php
if (elgg_is_active_plugin('hypeCategories')) {
	$category = elgg_view('input/category', array(
		'value' => elgg_extract('category', $vars),
		'entity' => $entity,
		'label' => false,
	));
	if ($category) {
		?>
		<div>
			<label><?= elgg_echo('campaigns:category') ?></label>
			<?php
			echo $category;
			?>
		</div>
		<?php
	}
}
?>
<div>
	<label><?= elgg_echo('campaigns:tags') ?></label>
	<?php
	echo elgg_view('input/tags', array(
		'name' => 'tags',
		'value' => elgg_extract('tags', $vars, $entity->tags),
	));
	?>
</div>

<?php
echo elgg_view('forms/campaigns/edit/extend', $vars);
?>

<div class="elgg-foot text-right">
	<?php
	echo elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => elgg_extract('guid', $vars, $entity->guid),
	));
	echo elgg_view('input/hidden', array(
		'name' => 'container_guid',
		'value' => elgg_extract('container_guid', $vars, $container->guid),
	));
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('save')
	));
	?>
</div>