<?php

$getLinksByAttribute = (function($param, $value, $strict = false) use(&$anchor) {
	return $anchor->get_all_by_attribute($param, $value, $strict)->get_href();
});

$linksCrawler = (function($url, $linksParam) use(&$getLinksByAttribute, &$browser) {

	$browser->navigate($url);
	$browser->wait();
	$browser->wait_js();
	
	return $getLinksByAttribute(...$linksParam);
});



?>