<?php

$owner_guid = (int) elgg_extract('owner_guid', $vars);
$limit = elgg_extract('limit', $vars, 10);
$offset = elgg_extract('offset', $vars, 0);

echo elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => \hypeJunction\Ads\Campaign::SUBTYPE,
	'owner_guids' => $owner_guid,
	'limit' => $limit,
	'offset' => $offset,
	'no_results' => elgg_echo('campaigns:no_results'),
));