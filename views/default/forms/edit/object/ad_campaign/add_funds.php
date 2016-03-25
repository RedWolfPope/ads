<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof hypeJunction\Ads\Campaign || !$entity->canEdit()) {
	return;
}

$required = elgg_format_attributes(array(
	'title' => elgg_echo('campaigns:required'),
	'class' => 'required'
		));
?>
<div>
	<label <?= $required ?>><?= elgg_echo('campaigns:amount', array(elgg_get_plugin_setting('currency', 'ads', 'EUR'))) ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'amount',
		'value' => elgg_extract('amount', $vars, $entity->amount),
		'required' => true,
	));
	?>
</div>

<div class="elgg-foot text-right">
	<?php
	echo elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => elgg_extract('guid', $vars, $entity->guid),
	));
	echo elgg_view('input/submit', array(
		'value' => elgg_echo('campaigns:add_funds')
	));
	?>
</div>