<?php

$entity = elgg_extract('entity', $vars);
$currency = elgg_get_plugin_setting('currency', 'ads', 'EUR');
?>
<h3><?php echo elgg_echo('ads:pricing') ?></h3>
<div>
	<label><?php echo elgg_echo('ads:currency') ?></label>
	<?php 
	echo elgg_view('input/payments/currency', array(
		'name' => 'params[currency]',
		'value' => $currency,
	));
	?>
</div>
<div>
	<label><?php echo elgg_echo('ads:unit:cost_per_mille', array($currency)) ?></label>
	<?php 
	echo elgg_view('input/text', array(
		'name' => 'params[cost_per_mille]',
		'value' => $entity->cost_per_mille,
	));
	?>
</div>

<div>
	<label><?php echo elgg_echo('ads:unit:cost_per_click', array($currency)) ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'params[cost_per_click]',
		'value' => $entity->cost_per_click,
	));
	?>
</div>

<h3><?php echo elgg_echo('ads:adsense') ?></h3>
<div>
	<label><?php echo elgg_echo('ads:adsense_publisher_id') ?></label>
	<?php
	echo elgg_view('input/text', array(
		'name' => 'params[adsense_publisher_id]',
		'value' => $entity->adsense_publisher_id,
	));
	?>
</div>


