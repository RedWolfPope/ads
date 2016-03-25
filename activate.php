<?php

require_once __DIR__ . '/autoloader.php';

$subtypes = array(
	hypeJunction\Ads\Campaign::SUBTYPE => hypeJunction\Ads\Campaign::CLASSNAME,
	hypeJunction\Ads\Ad::SUBTYPE => hypeJunction\Ads\Ad::CLASSNAME,
);

foreach ($subtypes as $subtype => $class) {
	if (!update_subtype('object', $subtype, $class)) {
		add_subtype('object', $subtype, $class);
	}
}