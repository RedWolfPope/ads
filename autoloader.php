<?php

$path = __DIR__;
if (file_exists("{$path}vendor/autoload.php")) {
	require_once "{$path}vendor/autoload.php";
}

require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/lib/hooks.php';
require_once __DIR__ . '/lib/page_handlers.php';