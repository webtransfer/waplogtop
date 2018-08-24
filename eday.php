<?php
require_once ('Sys/connect.php');
require_once ('Sys/core.php');
require_once ('Sys/function.php');
$mysqli->query("UPDATE `".$prefix."sait` SET `hosts` = '0', `hits` = '0', `in` = '0', `out` = '0'");
$mysqli->query("UPDATE `".$prefix."stats` SET `value` = '0' WHERE `name` = 'in'");
$mysqli->query("UPDATE `".$prefix."stats` SET `value` = '0' WHERE `name` = 'out'");
$mysqli->query("UPDATE `".$prefix."stats` SET `value` = '0' WHERE `name` = 'hosts'");
$mysqli->query("UPDATE `".$prefix."stats` SET `value` = '0' WHERE `name` = 'hits'");
$saits = $mysqli->query("SELECT `id`,`url` FROM `".$prefix."sait`");
while($row = $saits->fetch_assoc())
	{
		$pr = getPageRank($row['url']);
		$cy = intval(cy($row['url']));
		$mysqli->query("UPDATE `".$prefix."sait` SET `pr`='".$pr."', `cy`='".$cy."' WHERE `id` = '".$row['id']."'");
	}
$mysqli->query("TRUNCATE TABLE `".$prefix."browsers`");
$mysqli->query("TRUNCATE TABLE `".$prefix."compression`");
$mysqli->query("TRUNCATE TABLE `".$prefix."country`");
$mysqli->query("TRUNCATE TABLE `".$prefix."hours`");
$mysqli->query("TRUNCATE TABLE `".$prefix."online`");
$mysqli->query("TRUNCATE TABLE `".$prefix."operators`");
$mysqli->query("TRUNCATE TABLE `".$prefix."saitsOnline`");
$mysqli->query("TRUNCATE TABLE `".$prefix."shows`");
$mysqli->query("DELETE FROM `".$prefix."complaint` WHERE `status` = '1'");
?>
