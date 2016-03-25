<?php

/**
 * Display a block of ads
 *
 * @uses $vars['size'] One of the predefined sizes
 * @uses $vars['limit'] Number of ads to show (defaults to 1)
 * @uses $vars['tags'] Optional tags to match on
 * @uses $vars['categories'] Optional categories to match on
 */
$size = elgg_extract('size', $vars);
$limit = elgg_extract('limit', $vars, 1);

$tags = elgg_extract('tags', $vars);

$private_access_id = (int) ACCESS_PRIVATE;
$options = array(
	'types' => 'object',
	'subtypes' => \hypeJunction\Ads\Ad::SUBTYPE,
	'limit' => $limit ? : 1, // do not allow 0
	'item_view' => 'object/ad_unit/elements/impression',
	'list_class' => 'ads-block',
	'metadata_name_value_pairs' => array(
		array(
			'name' => 'size',
			'value' => $size
		),
	),
	'wheres' => array(
		"e.access_id != $private_access_id"
	),
	'order_by' => 'RAND()',
);

$dbprefix = elgg_get_config('dbprefix');

if (!empty($tags) && is_array($tags)) {
	$tags_name_id = elgg_get_metastring_id('tags');
	$tags_value_ids = array();
	foreach ($tags as $tag) {
		$tags_value_ids[] = (int) elgg_get_metastring_id($tag);
	}
	$tags_value_ids = implode(',', $tags_value_ids);
	$options['joins'] = "JOIN {$dbprefix}metadata md ON md.entity_guid = e.container_guid";
	$options['wheres'][] = "md.name_id = $tags_name_id AND md.value_id IN ($tags_value_ids)";
}

$categories = (array) elgg_extract('categories', $vars);
if (!empty($categories)) {
	$category_guids = array();
	foreach ($categories as $category) {
		if ($category instanceof ElggObject) {
			$category_guids[] = $category->guid;
		} else {
			$category_guids[] = (int) $category;
		}
	}
	$category_guids_in = implode(',', $category_guids);
	$options['joins'][] = "JOIN {$dbprefix}entity_relationships er ON er.guid_one = e.container_guid";
	$options['wheres'][] = "er.relationship = 'filed_in' AND er.guid_two IN ($category_guids_in)";
}

$ads = elgg_get_entities_from_metadata($options);
if ($ads) {
	foreach ($ads as $ad) {
		/* @var $ad \hypeJunction\Ads\Ad */
		$ad->logImpression();
		echo elgg_view('object/ad_unit/elements/impression', array('entity' => $ad));
	}
}

$ads_count = $ads ? count($ads) : 0;
$google_ads_count = $limit - $ads_count;

if ($google_ads_count > 0) {
	for ($i=0;$i<$google_ads_count;$i++) {
		echo elgg_view('ads/adsense_unit', array(
			'size' => $size,
		));
	}
}

