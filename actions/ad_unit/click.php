<?php

$guid = get_input('guid');
$ad_unit = get_entity($guid);

if (!$ad_unit instanceof hypeJunction\Ads\Ad) {
	forward(REFERRER);
}

$ad_unit->logClick();
forward($ad_unit->address);


