<?php

$subtypes = array(
	hypeJunction\Ads\Campaign::SUBTYPE => hypeJunction\Ads\Campaign::CLASSNAME,
	hypeJunction\Ads\Ad::SUBTYPE => hypeJunction\Ads\Ad::CLASSNAME,
);

foreach ($subtypes as $subtype => $class) {
	update_subtype('object', $subtype);
}