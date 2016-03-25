<?php

use hypeJunction\Ads\Campaign;

$params = new stdClass();

$input_keys = array_keys((array) elgg_get_config('input'));
$request_keys = array_keys((array) $_REQUEST);
$keys = array_unique(array_merge($input_keys, $request_keys));
foreach ($keys as $key) {
	if ($key) {
		$params->$key = get_input($key);
	}
}

$params->entity = get_entity($params->guid);
$params->container = get_entity($params->container_guid);
if ($params->entity instanceof Campaign) {
	$params->container = $params->entity->getContainerEntity();
	$fields = array(
		'title',
		'description',
		'tags',
	);
	foreach ($fields as $key) {
		if (empty($params->$key) && !empty($params->entity->$key)) {
			$params->$key = $params->entity->$key;
		}
	}
}

if (!$params->title) {
	register_error(elgg_echo('campaigns:edit:error:title_required'));
	forward(REFERRER);
}

if ($params->guid && !$params->entity instanceof Campaign) {
	register_error(elgg_echo('campaigns:error:not_found'));
	forward(REFERRER);
}


if (!$params->entity) {
	$params->entity = new Campaign();
	$params->entity->container_guid = $params->container ? $params->container->guid : elgg_get_logged_in_user_guid();
	$params->entity->access_id = ACCESS_PUBLIC;
}

$params->entity->title = $params->title;
$params->entity->tags = is_string($params->tags) ? string_to_tag_array($params->tags) : $params->tags;

if ($params->entity->save()) {
	if (is_callable('hypeCategories')) {
		hypeCategories()->categories->setItemCategories($params->entity, $params->categories);
	}
	system_message(elgg_echo('campaigns:edit:success'));
	forward($params->entity->getURL());
} else {
	register_error(elgg_echo('campaigns:edit:error'));
	forward(REFERRER);
}
