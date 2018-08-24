<?php
$title = 'Панель управления сайтом - Главная';
require_once('../Sys/head.php');
reg();
level(2);
		$plaformsNoMod = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `status` = '0'")->num_rows;
		$plaformsAll = $mysqli->query("SELECT `id` FROM `".$prefix."sait`")->num_rows;
		$usersAll = $mysqli->query("SELECT `id` FROM `".$prefix."users`")->num_rows;
		$newsAll = $mysqli->query("SELECT `id` FROM `".$prefix."news`")->num_rows;
		$categoryAll = $mysqli->query("SELECT `id` FROM `".$prefix."cat`")->num_rows;
		$complaints = $mysqli->query("SELECT `id` FROM `".$prefix."complaint` WHERE `status` = '0'")->num_rows;
		$counters = $mysqli->query("SELECT `id` FROM `".$prefix."images`")->num_rows;
		echo '<div class="main">';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/moderacia">Модерация сайтов</a> (<font color="red">'.$plaformsNoMod.'</font>)<br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/category">Управление категориями</a> (<font color="red">'.$categoryAll.'</font>)<br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/settings">Настройки системы</a><br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/cacheSettings">Настройки кэша статистики</a><br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/mail">Массовая рассылка сообщений (e-mail)</a><br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/users">Управление пользователями</a> (<font color="red">'.$usersAll.'</font>)<br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms">Управление площадками</a> (<font color="red">'.$plaformsAll.'</font>)<br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news">Управление новостями</a> (<font color="red">'.$newsAll.'</font>)<br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/complaint">Управление жалобами</a> (<font color="red">'.$complaints.'</font>)<br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/counters">Управление счётчиками</a> (<font color="red">'.$counters.'</font>)<br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/rules">Управление правилами</a><br/>';
		echo '</div>';
?>
