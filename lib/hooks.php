<?php

/**
 * Build entity URL
 *
 * @param string $hook   "entity:url"
 * @param string $type   "object"
 * @param string $return URL
 * @param array  $params Hook params
 * @return string
 */
function ads_entity_url_hook($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);

	if ($entity instanceof \hypeJunction\Ads\Campaign) {
		return "/campaigns/view/$entity->guid";
	} else if ($entity instanceof \hypeJunction\Ads\Ad) {
		return "/ads/view/$entity->guid";
	}
}

/**
 * Setup campagin menu
 * 
 * @param string         $hook   "register"
 * @param string         $type   "entity:menu"
 * @param ElggMenuItem[] $return Menu
 * @param array          $params Hook params
 * @return ElggMenuItem[]
 */
function ads_setup_campaign_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof \hypeJunction\Ads\Campaign) {
		return;
	}

	return array();
}

/**
 * Setup add unit menu
 *
 * @param string         $hook   "register"
 * @param string         $type   "entity:menu"
 * @param ElggMenuItem[] $return Menu
 * @param array          $params Hook params
 * @return ElggMenuItem[]
 */
function ads_setup_ad_unit_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof \hypeJunction\Ads\Ad) {
		return;
	}

	$return[] = ElggMenuItem::factory(array(
		'name' => 'edit',
		'text' => elgg_echo('edit'),
		'href' => "/ads/edit/$entity->guid",
	));

	$return[] = ElggMenuItem::factory(array(
		'name' => 'delete',
		'text' => elgg_view_icon('delete'),
		'href' => "/delete/$entity->guid",
		'confirm' => true,
	));

	return $return;
}

/**
 * Publish campaign whenever a payment is received
 *
 * @param string $hook   "transaction:paid"
 * @param string $type   "payments"
 * @param mixed  $return Void
 * @param array  $params Hook params
 * @return mixed
 */
function ads_process_payment($hook, $type, $return, $params) {

	$transaction = elgg_extract('entity', $params);
	if (!$transaction instanceof hypeJunction\Payments\Transaction) {
		return;
	}

	$campaign_data = $transaction->getDetails('_campaign');
	$campaign = get_entity($campaign_data['_id']);

	if ($campaign instanceof hypeJunction\Ads\Campaign) {
		$campaign->updateBudget($transaction->getAmount());
	}
}

/**
 * Update campaign budget on refund
 *
 * @param string $hook   "transaction:refunded"
 * @param string $type   "payments"
 * @param mixed  $return Void
 * @param array  $params Hook params
 * @return mixed
 */
function ads_refund_payment($hook, $type, $return, $params) {

	$transaction = elgg_extract('entity', $params);
	if (!$transaction instanceof hypeJunction\Payments\Transaction) {
		return;
	}

	$campaign_data = $transaction->getDetails('_campaign');
	$campaign = get_entity($campaign_data['_id']);

	if ($campaign instanceof hypeJunction\Ads\Campaign) {
		$campaign->updateBudget(-$transaction->getAmount());
	}
}

/**
 * Update campaign budget on refund
 *
 * @param string $hook   "transaction:partially_refunded"
 * @param string $type   "payments"
 * @param mixed  $return Void
 * @param array  $params Hook params
 * @return mixed
 */
function ads_partially_refund_payment($hook, $type, $return, $params) {

	$transaction = elgg_extract('entity', $params);
	if (!$transaction instanceof hypeJunction\Payments\Transaction) {
		return;
	}

	$campaign_data = $transaction->getDetails('_campaign');
	$campaign = get_entity($campaign_data['_id']);

	if ($campaign instanceof hypeJunction\Ads\Campaign) {
		$refund_amount = elgg_extract('refund_amount', $params, 0);
		$campaign->updateBudget(-$refund_amount);
	}
}