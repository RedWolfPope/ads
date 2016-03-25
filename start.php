<?php

/**
 * Ads
 *
 * @package hypeJunction
 * @subpackage ads
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 */
require_once __DIR__ . '/autoloader.php';

elgg_register_event_handler('init', 'system', 'ads_init');

/**
 * Initialize
 * @return void
 */
function ads_init() {

	$user = elgg_get_logged_in_user_entity();
	if ($user && $user->canWriteToContainer(0, 'object', \hypeJunction\Ads\Campaign::SUBTYPE)) {
		elgg_register_menu_item('topbar', array(
			'name' => 'ads',
			'href' => '/campaigns',
			'text' => elgg_echo('ads'),
			'section' => 'alt',
		));
	}

	elgg_register_action('edit/object/ad_unit', __DIR__ . '/actions/edit/object/ad_unit.php');
	elgg_register_action('edit/object/ad_campaign', __DIR__ . '/actions/edit/object/ad_campaign.php');
	elgg_register_action('edit/object/ad_campaign/add_funds', __DIR__ . '/actions/edit/object/ad_campaign/add_funds.php');
	elgg_register_action('ad_unit/click', __DIR__ . '/actions/ad_unit/click.php', 'public');

	elgg_extend_view('css/elgg', 'ads/stylesheet.css');

	elgg_register_page_handler('campaigns', 'ads_campaigns_page_handler');
	elgg_register_page_handler('ads', 'ads_unit_page_handler');
	
	elgg_register_plugin_hook_handler('entity:url', 'object', 'ads_entity_url_hook');

	elgg_register_plugin_hook_handler('register', 'menu:entity', 'ads_setup_campaign_menu');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'ads_setup_ad_unit_menu');

	elgg_register_plugin_hook_handler('transaction:paid', 'payments', 'ads_process_payment');
	elgg_register_plugin_hook_handler('transaction:refunded', 'payments', 'ads_refund_payment');
	elgg_register_plugin_hook_handler('transaction:partially_refunded', 'payments', 'ads_partially_refund_payment');

	elgg_extend_view('page/elements/sidebar', 'ads/sidebar');
	elgg_extend_view('page/elements/comments', 'ads/comments', 1);
}
