<?php

$table_name = 'casinotopsonline_casinos';

$get = (
	function($pattern, $all = false) use (&$webpage) {
		if ($all === true) 
			preg_match_all($pattern, $webpage->get_body(), $matches);
		else preg_match($pattern, $webpage->get_body(), $matches);
		if ($matches && !empty($matches)) {
			return $matches[1];
		} else return false;
	}
);

$filter = (function(&$subject, $pattern) {
	return preg_replace($pattern, "", $subject);
});

$filterAnchors = (function($subject) use($filter) {
	$patterns[] = "#<a.*?>#i";
	$patterns[] = "#<\/a>#";
	return $filter($subject, $patterns);
});


$filterHtml = (function($subject) use(&$filter) {
	$patterns[] = "#<.*?>#i";
	$patterns[] = "#<\/.*?>#";
	return $filter($subject, $patterns);
});

$getName = (
	function() use (&$span) {
		return $span->get_by_attribute('itemprop', 'itemreviewed', true)->get_inner_html();
	}
);

$getLogo_url = (
	function() use (&$image) {
		return $image->get_by_attribute("class","hideImgLoader", false)->get_src();
	}
);

$getOverview_text = (
	function() use(&$div) {
		return ($div->get_by_attribute("class","more_review_text", false)->get_inner_text());		
	}
);

$getSoftware = (
	function() use(&$get) {
		$pattern = "#Software</strong></td><td>(.*?)<\/td>#i";
		return $get($pattern);	
	}
);

$getDeposit_methods = (
	function() use(&$get) {
		$pattern = "# Methods</strong></td><td>(.*?)<\/td>#i";
		return $get($pattern);	
	}
);

$getBonuses = (
	function() use(&$get, &$browser, &$anchor) {
		$anchor->get_by_attribute("href","casino-review/bonuses", false)->click();
		$browser->wait_js();
		$pattern = "#<p(?:style=\"\"|)><strong(?:style=\"\"|)>(.*?)<\/strong><\/p>#i";
		$browser->go_back();
		$browser->wait_js();
		return $get($pattern, true);	
	}
);

$getWebsite = (
	function() use(&$get) {
		$pattern = "#Website</strong></td><td>(.*?)<\/td>#i";
		return $get($pattern);	
	}
);

$getYear = (
	function() use(&$get) {
		$pattern = "#Online<\/strong><\/td><td>(?:Since |)(.*?)<\/td#i";
		return (int) $get($pattern);	
	}
);


$getLanguages = (
	function() use(&$get) {
		$pattern = "#Languages<\/strong><\/td><td>(.*?)<\/td#i";
		return $get($pattern);	
	}
);

$casinoIsExist = function($data, $table_name, $checkParam = 'name') use(&$db) {
	$db->where('name', $data[$checkParam]);
	return (($db->get($table_name)) ? true : false);
};

$casinoInsert = function($casino, $table_name) use(&$db, &$casinoIsExist) {
	if ($casinoIsExist($casino, $table_name)) return;
	$db->insert($table_name, $casino);
};

$getData = function($task) use(&$getName, &$getLogo_url, &$getOverview_text, &$getSoftware, &$getDeposit_methods, &$getBonuses, &$getWebsite, &$getYear, &$getLanguages, &$filterAnchors, &$db) {
	$data = array();
	foreach($task as $key) {
		$func = ${'get' . ucfirst($key)};

		$data[$key] = $func();
	}

	$data = array_map($filterAnchors, $data);
	$data['created_at'] = $db->now();
	$data['updated_at'] = $db->now();

	return $data;
};

$grab = (function($url, $task, $table_name) use(&$browser, $getData, $casinoInsert) {
	$browser->navigate($url);
	$browser->wait_js();
	$casinoInsert($getData($task), $table_name);
});



?>