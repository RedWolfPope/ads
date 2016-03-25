<?php

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars);
$entity = get_entity($guid);

if (!$entity || !$entity->canEdit()) {
	forward('', '404');
}

$container = $entity->getContainerEntity();
elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
elgg_push_breadcrumb($container->getDisplayName(), "/campaigns/publisher/$campaign->guid");
elgg_push_breadcrumb($entity->getDisplayName(), $entity->getURL());

if (elgg_is_sticky_form('edit/object/ad_campaign/add_funds')) {
	$sticky = elgg_get_sticky_values('edit/object/ad_campaign/add_funds');
	$vars = array_merge($vars, $sticky);
	elgg_clear_sticky_form('edit/object/ad_campaign/add_funds');
}
$vars['entity'] = $entity;

$title = elgg_echo('campaigns:add_funds');
$content = elgg_view_form('edit/object/ad_campaign/add_funds', array(), $vars);

if (elgg_is_xhr()) {
	echo $content;
} else {
	$layout = elgg_view_layout('one_sidebar', array(
		'title' => $title,
		'content' => $content,
	));
	echo elgg_view_page($title, $layout);
}