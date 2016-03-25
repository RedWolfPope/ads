<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \hypeJunction\Ads\Ad) {
	return;
}

$label = elgg_format_element('div', array(
	'class' => 'ads-unit-label',
		), elgg_echo('ads:label'));

$img = elgg_view('output/img', array(
	'src' => $entity->getInlineUrl(),
		));

$link = elgg_view('output/url', array(
	'text' => $img,
	'href' => elgg_http_add_url_query_elements('/action/ad_unit/click', array(
		'guid' => $entity->guid,
		'referrer_url' => current_page_url(),
	)),
	'is_action' => true,
	'target' => '_blank',
		));

$width = $entity->width;
$height = $entity->height;

echo elgg_format_element('div', array(
	'class' => 'ads-unit',
	'style' => "max-width:{$width}px;max-height:{$height}px;",
		), $label . $link);
