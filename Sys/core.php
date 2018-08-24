<?php
error_reporting(E_ALL & ~ E_NOTICE);
mb_internal_encoding('UTF-8');
session_name('SESID');
session_start();
date_default_timezone_set('Etc/GMT-4');
$time = time();
$set = array();
$query = $mysqli->query("SELECT * FROM `".$prefix."settings`");
while($queryy = $query->fetch_assoc())
	{
		$set[$queryy['name']] = $queryy['value'];
	}
$cache = array();
$query = $mysqli->query("SELECT * FROM `".$prefix."cacheSettings`");
while($queryy = $query->fetch_assoc())
	{
		$cache[$queryy['name']] = $queryy['value'];
	}
if(isset($_COOKIE['login']) AND isset($_COOKIE['password']) AND empty($user_data))
	{
		$log = mysqli_real_escape_string($mysqli,htmlspecialchars(trim($_COOKIE['login'])));
		$pas = mysqli_real_escape_string($mysqli,htmlspecialchars(trim($_COOKIE['password'])));
		$user = $mysqli->query("SELECT * FROM `".$prefix."users` WHERE `login`='".$log."' AND `password`='".$pas."'");
		if($user->num_rows > 0)
			{
				$user_data = $user->fetch_assoc();
			}
	}
$pages = (isset($user_data)) ? $user_data['pages'] : $set['pages'];
$pagePlatforms = (isset($user_data)) ? $user_data['pagePlatforms'] : $set['pagePlatforms'];
$pageSait = (isset($user_data)) ? $user_data['pageSait'] : $set['pageSait'];
$pageTop = (isset($user_data)) ? $user_data['pageTop'] : $set['pageTop'];
$style = (isset($user_data)) ? $user_data['style'] : $set['style'];
$pageNews = (isset($user_data)) ? $user_data['pageNews'] : $set['pageNews'];
$pageNc = (isset($user_data)) ? $user_data['pageNewsc'] : $set['pageNewsc'];
$pageUsers = $set['pageUsers'];
$themeDir = 'Design/themes/'.$style;
$imageDir = 'Design/images/'.$style;
$ip = trim(mysqli_real_escape_string($mysqli,htmlspecialchars($_SERVER['REMOTE_ADDR'])));
//Определение UA
if (isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']))
	{
		$ua = trim(mysqli_real_escape_string($mysqli,htmlspecialchars($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])));
	}
elseif (isset($_SERVER['HTTP_USER_AGENT']))
	{
		$ua = trim(mysqli_real_escape_string($mysqli,htmlspecialchars($_SERVER['HTTP_USER_AGENT'])));
	}
else
	{
		$ua = 'Скрыт';
	}
$ua=strtok($ua, '/');
$ua=strtok($ua, ' ');
$id = isset($_GET['id']) ? abs(intval($_GET['id'])) : '';
$act = isset($_GET['act']) ? htmlspecialchars($_GET['act']) : '';
?>