<?php
require_once ('connect.php');
require_once ('core.php');
require_once ('function.php');
$cats = $mysqli->query("SELECT `id` FROM `".$prefix."cat`");
while($row = $cats->fetch_assoc())
	{
		$saits = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `category` = '".$row['id']."'")->num_rows;
		$mysqli->query("UPDATE `".$prefix."cat` SET `count` = '".$saits."' WHERE `id` = '".$row['id']."'");		
	}
$users = $mysqli->query("SELECT `id` FROM `".$prefix."users`");
while($row = $users->fetch_assoc())
	{
		$saits = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `uid` = '".$row['id']."'")->num_rows;
		$mysqli->query("UPDATE `".$prefix."users` SET `platformsCount` = '".$saits."' WHERE `id` = '".$row['id']."'");		
	}
$mysqli->query("OPTIMIZE TABLE `".$prefix."browsers`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."cat`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."complaint`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."compression`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."country`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."days`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."hours`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."images`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."ip`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."month`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."news`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."news_comments`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."online`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."operators`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."sait`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."saitsOnline`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."shows`");
$mysqli->query("OPTIMIZE TABLE `".$prefix."users`");
?>
