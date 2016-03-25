<?php

$entity = elgg_extract('entity', $vars);
$full_view = elgg_extract('full_view', $vars, false);

if (!$entity instanceof \hypeJunction\Ads\Ad) {
	return;
}

$title = elgg_view('output/url', array(
	'text' => $entity->getDisplayName(),
	'href' => $entity->getURL(),
	'is_trusted' => true,
));

$subtitle = array();
$subtitle[] = elgg_view('output/url', array(
	'href' => $entity->address,
	'target' => '_blank',
));
$subtitle[] = elgg_echo('ads:unit:dimensions', array($entity->width, $entity->height));
$subtitle[] = elgg_echo('ads:unit:impressions', array($entity->countAnnotations('impression')));
$subtitle[] = elgg_echo('ads:unit:clicks', array($entity->countAnnotations('click')));

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

$body = elgg_view('object/ad_unit/elements/impression', array(
	'entity' => $entity,
));

echo elgg_view('object/elements/full', array(
	'entity' => $entity,
	'summary' => $summary,
	'body' => $body,
));