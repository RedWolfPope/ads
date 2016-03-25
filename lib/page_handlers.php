<?php

/**
 * Handles campaigns pages
 *
 * @param array $segments URL segments
 * @return boolean
 */
function ads_campaigns_page_handler($segments) {

	$page = array_shift($segments);

	switch ($page) {

		default :
		case 'owner' :
		case 'publisher' :
			echo elgg_view('resources/lists/ad_campaign', array(
				'owner_guid' => $segments[0],
			));
			return true;

		case 'add' :
			echo elgg_view('resources/add/object/ad_campaign', array(
				'container_guid' => $segments[0],
			));
			return true;

		case 'edit' :
			echo elgg_view('resources/edit/object/ad_campaign', array(
				'guid' => $segments[0],
			));
			return true;

		case 'add_funds' :
			echo elgg_view('resources/edit/object/ad_campaign/add_funds', array(
				'guid' => $segments[0],
			));
			return true;

		case 'view' :
			echo elgg_view('resources/view/object/ad_campaign', array(
				'guid' => $segments[0],
			));
			return true;
	}

	return false;
}

/**
 * Handles ads pages
 *
 * @param array $segments URL segments
 * @return boolean
 */
function ads_unit_page_handler($segments) {

	$page = array_shift($segments);

	switch ($page) {

		case 'add' :
			echo elgg_view('resources/add/object/ad_unit', array(
				'container_guid' => $segments[0],
			));
			return true;

		case 'edit' :
			echo elgg_view('resources/edit/object/ad_unit', array(
				'guid' => $segments[0],
			));
			return true;

		case 'view' :
			echo elgg_view('resources/view/object/ad_unit', array(
				'guid' => $segments[0],
			));
			return true;
	}

	return false;
}
