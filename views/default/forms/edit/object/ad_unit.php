<?php
$entity = elgg_extract('entity', $vars);
$container = elgg_extract('container', $vars);

$required = elgg_format_attributes(array(
	'title' => elgg_echo('ads:required'),
	'class' => 'required'
		));
?>
<div>
	<label <?= $required ?>><?= elgg_echo('ads:title') ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'title',
		'value' => elgg_extract('title', $vars, $entity->title),
		'required' => true,
	));
	?>
</div>
<div>
	<label <?= $required ?>><?= elgg_echo('ads:address') ?></label>
	<?php
	echo elgg_view('input/url', array(
		'name' => 'address',
		'value' => elgg_extract('address', $vars, $entity->address),
		'required' => true,
	));
	?>
</div>
<div>
	<label <?= $required ?>><?= elgg_echo('ads:image') ?></label>
	<?php
	echo elgg_view('input/file', array(
		'name' => 'image',
		'value' => ($entity),
		'required' => (!$entity),
	));
	?>
</div>
<div>
	<label <?= $required ?>><?= elgg_echo('ads:size') ?></label>
	<?php
	$sizes = ads_get_sizes();
	$options_values = array('' => elgg_echo('ads:size:select'));
	foreach ($sizes as $name => $dimensions) {
		$options_values[$name] = elgg_echo("ads:unit:$name") . ' ' . elgg_echo("ads:unit:dimensions", array($dimensions['width'], $dimensions['height']));
	}
	echo elgg_view('input/dropdown', array(
		'name' => 'size',
		'value' => elgg_extract('size', $vars, $entity->size),
		'options_values' => $options_values,
		'required' => true,
	));
	?>
</div>
<?php
echo elgg_view('forms/ads/edit/extend', $vars);
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