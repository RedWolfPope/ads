<?php

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars);
$entity = get_entity($guid);

$owner = $entity->getOwnerEntity();

if (!$entity || !$entity->canEdit()) {
	forward('', '404');
}

$container = $entity->getContainerEntity();
elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
elgg_push_breadcrumb($container->getDisplayName(), "/campaigns/publisher/$publisher->guid");

if ($entity->canWriteToContainer(0, 'object', \hypeJunction\Ads\Ad::SUBTYPE)) {
	elgg_register_menu_item('title', array(
		'name' => 'add',
		'text' => elgg_echo('ads:add'),
		'href' => "/ads/add/$entity->guid",
		'link_class' => 'elgg-button elgg-button-action',
	));
	elgg_register_menu_item('title', array(
		'name' => 'add_funds',
		'text' => elgg_echo('campaigns:add_funds'),
		'href' => "/campaigns/add_funds/$entity->guid",
		'link_class' => 'elgg-button elgg-button-action',
	));
}

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