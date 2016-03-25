<?php

$container_guid = (int) elgg_extract('container_guid', $vars);
$limit = elgg_extract('limit', $vars, 10);
$offset = elgg_extract('offset', $vars, 0);

echo elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => \hypeJunction\Ads\Ad::SUBTYPE,
	'container_guids' => $container_guid,
	'limit' => $limit,
	'offset' => $offset,
	'no_results' => elgg_echo('ads:no_results'),
));
