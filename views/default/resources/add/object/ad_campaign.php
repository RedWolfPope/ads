<?php

elgg_gatekeeper();

$container_guid = elgg_extract('container_guid', $vars);
$container = get_entity($container_guid);
if (!$container) {
	$container = elgg_get_logged_in_user_entity();
}

if (!$container || !$container->canWriteToContainer(0, 'object', \hypeJunction\Ads\Campaign::SUBTYPE)) {
	forward('', '403');
}

elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');
elgg_push_breadcrumb($container->getDisplayName(), "/campaigns/publisher/$container->guid");

if (elgg_is_sticky_form('campaigns/edit')) {
	$sticky = elgg_get_sticky_values('campaigns/edit');
	$vars = array_merge($vars, $sticky);
	elgg_clear_sticky_form('campaigns/edit');
}
$vars['container'] = $container;

$title = elgg_echo('campaigns:add');
$content = elgg_view_form('edit/object/ad_campaign', array(
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