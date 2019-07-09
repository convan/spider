<?php

function flatten(array $array) {
    $return = array();
    @array_walk_recursive(array_unique(array_values(array_filter($array))), function($a) use (&$return) { $return[] = $a; });
    return $return;
}

$getLinksByAttribute = (function($param, $value, $strict = false) use(&$anchor) {
	return $anchor->get_all_by_attribute($param, $value, $strict)->get_href();
});

$paginate = (function() use(&$li) {
	return $li->get_by_attribute("class", "next", false)->get_child_by_number(0)->click();
});

$linksCrawler = (function($url, $linksParam) use(&$getLinksByAttribute, &$browser, $paginate) {
	$links = array();
	$browser->navigate($url);
	$browser->wait();
	$browser->wait_js();
	
	do {
		array_push($links, $getLinksByAttribute(...$linksParam));
	} while($paginate());
	return flatten($links);
});



?>