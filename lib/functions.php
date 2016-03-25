<?php

/**
 * Returns ad size config
 * @return array
 */
function ads_get_sizes() {

	$sizes = array(
		'medium_rectangle' => array(
			'width' => 300,
			'height' => 250,
		),
		'large_rectangle' => array(
			'width' => 336,
			'height' => 280,
		),
		'leaderboard' => array(
			'width' => 728,
			'height' => 90,
		),
		'half_page' => array(
			'width' => 300,
			'height' => 600,
		),
		'large_mobile_banner' => array(
			'width' => 320,
			'height' => 100,
		),
	);
	return elgg_trigger_plugin_hook('sizes', 'ads', null, $sizes);
}
