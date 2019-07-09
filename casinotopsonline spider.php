<?php

$xhe_host = "127.0.0.1:7010";

require("../Templates/xweb_human_emulator.php");
require("MysqliDb.php");
require("dbObject.php");

$db = new MysqliDb ('localhost', 'root', '', 'data');
$table_name = 'casinotopsonline_casinos';

$sqlCreateTable = "CREATE TABLE IF NOT EXISTS `data`.`" . $table_name . "` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(20) NOT NULL , `logo_image` BLOB NULL DEFAULT NULL , `logo_url` TEXT NULL DEFAULT NULL , `images_url` TEXT NULL DEFAULT NULL , `affiliate_url` TEXT NULL DEFAULT NULL , `website` TEXT NOT NULL , `overview_text` TEXT NULL DEFAULT NULL , `software` TEXT NULL DEFAULT NULL , `languages` TEXT NULL DEFAULT NULL , `deposit_methods` TEXT NULL DEFAULT NULL , `bonuses` TEXT NULL DEFAULT NULL , `year` TEXT NULL DEFAULT NULL ,`pros` TEXT NULL DEFAULT NULL , `cons` TEXT NULL DEFAULT NULL , `rating` INT NOT NULL , `created_at` TIMESTAMP NOT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$db->rawQuery($sqlCreateTable);

$startUrl = 'https://www.casinotopsonline.com/casino-reviews';
$linksParam = ['class', 'review-link'];

include("casinotopsonline_spider_vars.php");

$crawledLinks = $linksCrawler($startUrl, $linksParam);

include("casinotopsonline_grab_vars.php");

$task = ['name', 'logo_url', 'overview_text', 'software', 'website', 'languages', 'deposit_methods', 'year', 'bonuses'];
var_dump($crawledLinks);
foreach($crawledLinks as $url) {
	$grab($url, $task, $table_name);
}

$app->quit();
?>