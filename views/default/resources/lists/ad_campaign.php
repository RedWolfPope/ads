<?php

elgg_gatekeeper();

$owner_guid = elgg_extract('owner_guid', $vars);
$owner = get_entity($owner_guid);
if (!$owner || !$owner->canEdit()) {
	$owner = elgg_get_logged_in_user_entity();
}

elgg_set_page_owner_guid($owner->guid);
elgg_register_title_button();

elgg_push_breadcrumb(elgg_echo('campaigns'), '/campaigns');

$vars['owner'] = $owner;

$title = elgg_echo('campaigns');
$content = elgg_view('lists/ad_campaign', array(
	'owner_guid' => $owner->guid,
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