<?php

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars);
$entity = get_entity($guid);

$owner = $entity->getOwnerEntity();

if (!$entity || !$entity->canEdit()) {
	forward('', '404');
}

$container = $entity->getContainerEntity();
$publisher = $container->getContainerEntity();

elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
elgg_push_breadcrumb($publisher->getDisplayName(), "/campaigns/publisher/$publisher->guid");
elgg_push_breadcrumb($container->getDisplayName(), $container->getURL());

$title = $entity->getDisplayName();
$content = elgg_view_entity($entity, array(
	'full_view' => true,
));

if (elgg_is_xhr()) {
	echo $content;
} else {
	$layout = elgg_view_layout('one_sidebar', array(
		'title' => $title,
		'content' => $content,
	));
	echo elgg_view_page($title, $layout);
}