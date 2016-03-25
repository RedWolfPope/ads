Ad Campaigns
============
![Elgg 1.11+](https://img.shields.io/badge/Elgg-1.11+.x-orange.svg?style=flat-square)

## Features

* Allow your users to create image ad campaigns (supports GIF ads)
* Choose between CPM and CPC pricing model
* Match ad units by content tags and content categories
* Fallback to Google Adsense responsive ad units


## Usage

* To display an ad unit, add the following code to any of your views:

```php
echo elgg_view('ads/block', array(
	'size' => 'medium_rectangle',
	'limit' => 3,
    'tags' => array('dogs', 'cats'),
    // 'categories' => optional array of category guids
));
```

where `size` is one of the following:

 * `medium_rectangle` - 300x250
 * `large_rectangle` - 336x280
 * `leaderboard` - 728x90
 * `half_page` - 300x600
 * `large_mobile_banner` - 320x100

and `limit` is the maximum number of ads to display

 * The plugin algorithm will first try to find active user generated ad units,
and will fallback to Google AdSense ad unit display, if no ads match the requirements

 * See `ads/sidebar` and `ads/comments` views to see some examples