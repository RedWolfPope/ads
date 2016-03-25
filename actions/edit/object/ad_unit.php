<?php

use hypeJunction\Ads\Ad;

$params = new stdClass();

$input_keys = array_keys((array) elgg_get_config('input'));
$request_keys = array_keys((array) $_REQUEST);
$keys = array_unique(array_merge($input_keys, $request_keys));
foreach ($keys as $key) {
	if ($key) {
		$params->$key = get_input($key);
	}
}

$entity = get_entity($params->guid);
if ($params->guid && !$entity instanceof Ad) {
	register_error(elgg_echo('ads:error:not_found'));
	forward(REFERRER);
}

if ($entity instanceof Ad) {
	$container = $entity->getContainerEntity();
	$fields = array(
		'title',
		'address',
		'size',
	);
	foreach ($fields as $key) {
		if (empty($params->$key) && !empty($entity->$key)) {
			$params->$key = $entity->$key;
		}
	}
} else if (isset($params->container_guid)) {
	$container = get_entity($params->container_guid);
} else {
	$container = elgg_get_logged_in_user_entity();
}
if (!$container instanceof ElggEntity) {
	register_error(elgg_echo('ads:error:not_found'));
	forward(REFERRER);
}

if (!$entity) {
	$entity = new Ad();
	$entity->container_guid = $container ? $container->guid : elgg_get_logged_in_user_guid();
	$entity->access_id = isset($params->access_id) ? $params->access_id : get_default_access();
}

if (!$entity->canEdit() || !$container->canWriteToContainer(0, $entity->getType(), $entity->getSubtype())) {
	register_error(elgg_echo('ads:error:permission_denied'));
	forward(REFERRER);
}


$entity->title = $params->title;
$entity->address = $params->address;
$entity->size = $params->size;
$sizes = ads_get_sizes();
$dimensions = elgg_extract($params->size, $sizes);
$entity->width = $dimensions['width'];
$entity->height = $dimensions['height'];

if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == UPLOAD_ERR_OK && substr_count($_FILES['image']['type'], 'image/')) {
	if (!$entity->exists()) {
		$entity->setFilename("ads/" . time() . $_FILES['image']['name']);
	}
	$entity->open('write');
	$entity->close();
	move_uploaded_file($_FILES['image']['tmp_name'], $entity->getFilenameOnFilestore());

	$entity->mimetype = $entity->detectMimeType($_FILES['image']['tmp_name'], $_FILES['image']['type']);
	$entity->simpletype = 'image';
	$entity->originafilename = $_FILES['image']['name'];
}

if (!$entity->exists()) {
	$entity->delete();
	register_error(elgg_echo('ads:edit:error:invalid_file'));
	forward(REFERRER);
} else if ($entity->save()) {
	system_message(elgg_echo('ads:edit:success'));
	forward($entity->getContainerEntity()->getURL());
} else {
	register_error(elgg_echo('ads:edit:error'));
	forward(REFERRER);
}
