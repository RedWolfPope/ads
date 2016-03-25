<?php

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars);
$entity = get_entity($guid);

if (!$entity || !$entity->canEdit()) {
	forward('', '404');
}

$container = $entity->getContainerEntity();
$publisher = $container->getContainerEntity();

elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
elgg_push_breadcrumb($publisher->getDisplayName(), "/campaigns/publisher/$publisher->guid");
elgg_push_breadcrumb($container->getDisplayName(), $container->getURL());
elgg_push_breadcrumb($entity->getDisplayName(), $entity->getURL());

if (elgg_is_sticky_form('ads/edit')) {
	$sticky = elgg_get_sticky_values('ads/edit');
	$vars = array_merge($vars, $sticky);
	elgg_clear_sticky_form('ads/edit');
}
$vars['entity'] = $entity;

$title = elgg_echo('ads:edit');
$content = elgg_view_form('edit/object/ad_unit', array(
	'enctype' => 'multipart/form-data',
		), $vars);

if (elgg_is_xhr()) {
	echo $content;
} else {
	$layout = elgg_view_layout('one_sidebar', array(
		'title' => $title,
		'content' => $content,
	));
	echo elgg_view_page($title, $layout);
}