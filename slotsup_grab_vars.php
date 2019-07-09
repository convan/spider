<?php

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
	function() use (&$get) {
		$pattern = "#<h4 class=\"widgettitle nbox\">(.*?)<\/h4>#i";
		return $get($pattern);
	}
);

$getLogo_url = (
	function() use (&$get) {
		$pattern = "#<img.*?class=.*?attachment-full size-full.*? src=\"(.*?)\"#i";
		return $get($pattern);
	}
);

$getOverview_text = (
	function() use(&$get) {
		$pattern = "#<h2 id=.*?>(.*?)<div class=\"toggle-action\"#i";
		$text = $get($pattern, true);
		
		
		return implode("\n", $text);
		
	}
);

$getSoftware = (
	function() use(&$get) {
		$pattern = "#Software</th><td>(.*?)<\/td>#i";
		return $get($pattern);	
	}
);

$getDeposit_methods = (
	function() use(&$get) {
		$pattern = "#Deposit Methods</th><td>(.*?)<span#i";
		return $get($pattern);	
	}
);

$getBonuses = (
	function() use(&$get) {
		$pattern = "#Bonus</th><td>(.*?)<\/td#i";
		return $get($pattern);	
	}
);

$getYear = (
	function() use(&$get) {
		$pattern = "#Year Established</th><td>(.*?)<\/td#i";
		return $get($pattern);	
	}
);

$getLanguages = (
	function() use(&$get) {
		$pattern = "#Languages</th><td>(.*?)<\/td#i";
		return $get($pattern);	
	}
);

$casinoIsExist = function($data, $checkParam = 'name') use(&$db) {
	$db->where('name', $data[$checkParam]);
	return (($db->get('casinos')) ? true : false);
};

$casinoInsert = function($casino) use(&$db, &$casinoIsExist) {
	if ($casinoIsExist($casino)) return;
	$db->insert('casinos', $casino);
};

$getData = function($task) use(&$getName, &$getLogo_url, &$getOverview_text, &$getSoftware, &$getDeposit_methods, &$getBonuses, &$getYear, &$getLanguages, &$filterAnchors, &$db) {
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

$grab = (function($url, $task) use(&$browser, $getData, $casinoInsert) {
	$browser->navigate($url);
	$browser->wait_js();
	$casinoInsert($getData($task));
});



?>