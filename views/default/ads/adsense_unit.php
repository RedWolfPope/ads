<?php
/**
 * Display an adsense ad
 *
 * @uses $vars['size'] One of the configured ad sizes
 */
$publisher_id = elgg_get_plugin_setting('adsense_publisher_id', 'ads');
if (!$publisher_id) {
	return;
}

$size = elgg_extract('size', $vars);
$sizes = ads_get_sizes();
if (!$size || !array_key_exists($size, $sizes)) {
	return;
}

$width = $sizes[$size]['width'];
$height = $sizes[$size]['height'];
?>

<div class="ads-adsense-unit">
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<ins class="adsbygoogle"
		 style="display:inline-block;width:<?php echo $width?>px;max-width:100%;min-height:<?php echo $height ?>px;"
		 data-ad-client="ca-<?php echo $publisher_id ?>"></ins>
	<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
</div>