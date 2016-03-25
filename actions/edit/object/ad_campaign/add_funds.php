<?php

$guid = get_input('guid');
$campaign = get_entity($guid);

if (!$campaign instanceof hypeJunction\Ads\Campaign) {
	register_error(elgg_echo('noaccess'));
	forward('', '404');
}

$customer = elgg_get_logged_in_user_entity();
$merchant = elgg_get_site_entity();
$amount = get_input('amount');
$currency = elgg_get_plugin_setting('currency', 'ads', 'EUR');

$money = SebastianBergmann\Money\Money::fromString($amount, new \SebastianBergmann\Money\Currency($currency));

if (elgg_is_admin_logged_in()) {
	$campaign->updateBudget($money->getAmount());
	system_message(elgg_echo('campaigns:add_funds:success'));
	forward($campaign->getURL());
} else {

	$campaign_obj = (array) $campaign->toObject();
	$campaign_obj['_id'] = $campaign->guid;
	$data = array(
		'_campaign' => $campaign_obj,
	);

	$transaction = hypeJunction\Payments\Transaction::factory($customer, $merchant, $money->getAmount(), $money->getCurrency()->getCurrencyCode(), $data);
	$transaction->origin = 'ads';
	$transaction->payment_method = 'paypal';
	
	$paypal_adapter = new \hypeJunction\Payments\PayPal\Adapter();
	$url = $paypal_adapter->getPaymentUrl($transaction);
	
	forward($url);
}
