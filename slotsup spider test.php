<?php

$xhe_host = "127.0.0.1:7010";

// The following code is required to properly run XWeb Human Emulator
require("../Templates/xweb_human_emulator.php");
require("MysqliDb.php");
require("dbObject.php");

$db = new MysqliDb ('localhost', 'root', '', 'data');
$table_name = 'slotsup_casinos';
$startUrl = 'https://www.slotsup.com/online-casinos';
$linksParam = ['title', ' Casino Review'];


include("slotsup_spider_vars.php");

$crawledLinks = $linksCrawler($startUrl, $linksParam);

include("slotsup_grab_vars.php");

$task = ['name', 'logo_url', 'overview_text', 'software', 'languages', 'deposit_methods', 'bonuses', 'year'];

foreach($crawledLinks as $url) {
	$grab($url, $task, $table_name);
}

// Quit
$app->quit();
?>