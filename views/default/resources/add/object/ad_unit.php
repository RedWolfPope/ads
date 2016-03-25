<?php

elgg_gatekeeper();

$container_guid = elgg_extract('container_guid', $vars);
$container = get_entity($container_guid);

if (!$container instanceof \hypeJunction\Ads\Campaign || !$container->canWriteToContainer(0, 'object', \hypeJunction\Ads\Ad::SUBTYPE)) {
	forward('', '403');
}

$publisher = $container->getContainerEntity();

elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
elgg_push_breadcrumb($publisher->getDisplayName(), "/campaigns/publisher/$publisher->guid");
elgg_push_breadcrumb($container->getDisplayName(), $container->getURL());

if (elgg_is_sticky_form('ads/edit')) {
	$sticky = elgg_get_sticky_values('ads/edit');
	$vars = array_merge($vars, $sticky);
	elgg_clear_sticky_form('ads/edit');
}
$vars['container'] = $container;

$title = elgg_echo('ads:add');
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