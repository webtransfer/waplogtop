<?php
require_once('Sys/connect.php');
require_once('Sys/core.php');
require_once('Sys/function.php');
if(!$id OR $id < 1)
	{
		exit('Не верно введен ID');
	}
$isset = $mysqli->query("SELECT `id`,`hosts` FROM `".$prefix."sait` WHERE `status` != '0' AND `id` = '".$id."'");
if($isset->num_rows == 0)
	{
		exit('Данного сайт нет , либо он не активирован.');
	}
$sait = $isset->fetch_assoc();
$mysqli->query("UPDATE `".$prefix."sait` SET `in` = (`in` + 1), `allIn` = (`allIn` + 1) WHERE `id` = '".$id."'");
$mysqli->query("UPDATE `".$prefix."stats` SET `value` = (`value` + 1) WHERE `name` = 'in'");
$mysqli->query("UPDATE `".$prefix."stats` SET `value` = (`value` + 1) WHERE `name` = 'allIn'");
$day = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
if($day->num_rows > 0)
	{
		$dayRow = $day->fetch_assoc();
		if(!empty($dayRow[''.date("d").'']))
			{
				$dayArray = explode('|',$dayRow[''.date("d").'']);
				$mysqli->query("UPDATE `".$prefix."days` SET `".date("d")."` = '".$dayArray[0]."|".$dayArray[1]."|".($dayArray[2] + 1)."|".$dayArray[3]."' WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
			}
		else
			{
				$mysqli->query("UPDATE `".$prefix."days` SET `".date("d")."` = '0|0|1|0' WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
			}
	}
else
	{
		$mysqli->query("INSERT INTO `".$prefix."days` SET `sid` = '".$id."', `month` = '".date("m")."', `".date("d")."` = '0|0|1|0'");
	}
$month = $mysqli->query("SELECT * FROM `".$prefix."month` WHERE `sid` = '".$id."'");
if($month->num_rows > 0)
	{
		$monthRow = $month->fetch_assoc();
		if(!empty($monthRow[''.date("m").'']))
			{
				$monthArray = explode('|',$monthRow[''.date("m").'']);
				$mysqli->query("UPDATE `".$prefix."month` SET `".date("m")."` = '".$monthArray[0]."|".$monthArray[1]."|".($monthArray[2] + 1)."|".$monthArray[3]."' WHERE `sid` = '".$id."'");
			}
		else
			{
				$mysqli->query("UPDATE `".$prefix."month` SET `".date("m")."` = '0|0|1|0' WHERE `sid` = '".$id."'");
			}
	}
else
	{
		$mysqli->query("INSERT INTO `".$prefix."month` SET `sid` = '".$id."', `".date("m")."` = '0|0|1|0'");
	}
$hour = (int)date("H");
if($hour == 23)
	{
		$hourD = '23:00-00:00';
	}
else
	{
		if(strlen($hour) == 1)
			{
				$hourr = '0'.$hour;
			}
		else
			{
				$hourr = $hour;
			}
		if(strlen($hour + 1) == 1)
			{
				$hour2 = '0'.($hour + 1);
			}
		else
			{
				$hour2 = $hour + 1;
			}
		$hourD = ''.$hourr.':00-'.$hour2.':00';
	}
$hours = $mysqli->query("SELECT * FROM `".$prefix."hours` WHERE `sid` = '".$id."'");
if($hours->num_rows > 0)
	{
		$hoursRow = $hours->fetch_assoc();
		if(!empty($hoursRow[''.$hourD.'']))
			{
				$hoursArray = explode('|',$hoursRow[''.$hourD.'']);
				$mysqli->query("UPDATE `".$prefix."hours` SET `".$hourD."` = '".$hoursArray[0]."|".$hoursArray[1]."|".($hoursArray[2] + 1)."|".$hoursArray[3]."' WHERE `sid` = '".$id."'");
			}
		else
			{
				$mysqli->query("UPDATE `".$prefix."hours` SET `".$hourD."` = '0|0|1|0' WHERE `sid` = '".$id."'");
			}
	}
else
	{
		$mysqli->query("INSERT INTO `".$prefix."hours` SET `sid` = '".$id."', `".$hourD."` = '0|0|1|0'");
	}
$page = ceil($mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `status` = '1' AND `ban` = '0' AND `hosts` >= '".$sait['hosts']."' AND `category` != '12'")->num_rows / $pageTop);
header('Location: http://'.$_SERVER['HTTP_HOST'].'/'.$page.'/'.$id);
?>
