<?php

$entity = elgg_extract('entity', $vars);
$full_view = elgg_extract('full_view', $vars, false);

if (!$entity instanceof \hypeJunction\Ads\Campaign) {
	return;
}

$title = elgg_view('output/url', array(
	'text' => $entity->getDisplayName(),
	'href' => $entity->getURL(),
	'is_trusted' => true,
));

$currency = elgg_get_plugin_setting('currency', 'ads', 'EUR');

$subtitle = array();
$subtitle[] = elgg_echo('campaigns:budget:used', array(
	hypeJunction\Payments\Price::format($entity->getSpent(), $currency),
	hypeJunction\Payments\Price::format($entity->getBudget(), $currency),
));
$subtitle[] = elgg_echo('campaigns:status:current', array(elgg_echo('campaigns:status:' . $entity->getPublishedStatus())));

$metadata = elgg_view_menu('entity', array(
	'sort_by' => 'priority',
	'entity' => $entity,
	'class' => 'elgg-menu-hz',
	'handler' => 'ads',
));

$summary = elgg_view('object/elements/summary', array(
	'title' => $title,
	'subtitle' => implode('<br />', array_filter($subtitle)),
	'metadata' => $metadata,
	'tags' => false,
));

if (!$full_view) {
	echo $summary;
	return;
}

$content = elgg_view('lists/ad_unit', array(
	'container_guid' => $entity->guid,
));

$content .= elgg_view_form('add_funds', array(), array(
	'entity' => $entity,
));

echo elgg_view('object/elements/full', array(
	'entity' => $entity,
	'summary' => $summary,
	'body' => $content,
));