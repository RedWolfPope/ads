<?php

$entity = elgg_extract('entity', $vars);
$params = array(
	'size' => 'leaderboard',
	'limit' => 1,
	'tags' => (array) $entity->tags,
);
if (is_callable('hypeCategories')) {
	$params['categories'] = hypeCategories()->categories->getItemCategories($entity, array(), true);
}

echo elgg_view('ads/block', $params);