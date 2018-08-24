<?php
$microtime = microtime(1);
echo '
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
    <head>
<meta http-equiv="Content-Type" content="application/vnd.wap.xhtml+xml; charset=UTF-8"/>
<link href="http://'.$_SERVER['HTTP_HOST'].'/Design/themes/'.$style.'/style.css" rel="stylesheet" type="text/css">
<link href="http://'.$_SERVER['HTTP_HOST'].'/favicon.ico" rel="shortcut icon">
<meta name="viewport" content="width=device-width, initial- scale=1.0, maximum-scale=1.0, user-scalable=0"/>
<meta name="keywords" content="статистика, мобильных, рейтинг, регистрация, игры, сайт, бесплатно, api, месяц, апреля, новый, связь, посетители, площадок, страница, общая, топа, знакомства" />
<meta name="description" content="Mswap.ru - это популярный каталог мобильных сайтов. Участие в рейтинге даст вам тысячи бесплатных целевых посетителей каждый месяц." />
<title>'.$title.' - '.$set['topName'].'</title></head>

<body>
<div class="head"><a href="http://'.$_SERVER['HTTP_HOST'].'"><img src="http://'.$_SERVER['HTTP_HOST'].'/Design/themes/'.$style.'/logo.png" alt="" /></a></div>
<div class="main">';
include 'teaser.php';
echo '</div>';
?>
<?php
if(!isset($user_data))
	{
		if($str != 'authentication' AND $str != 'registration')
			{

			}
	}
else
	{
		if($str != 'authentication')
		{
			$adminPanel = ($user_data['level'] == 2 AND $str != 'adminPanel') ? '- <a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">Управление сайтом</a><br/>' : '';
			$office = ($str != 'office') ? '- <a href="http://'.$_SERVER['HTTP_HOST'].'/m/office">Кабинет</a><br/>' : '';
			echo '<div class="main">';
			echo '&bull; Вы зашли , <strong>'.$user_data['login'].'</strong><br/> '.$adminPanel.$office.' - <a href="http://'.$_SERVER['HTTP_HOST'].'/exit">Завершить сеанс</a><br/>';
			echo '</div>';
		}
	}
?>
