<?php
$title = 'Главная страница';
require_once ('Sys/head.php');
            	if(!isset($user_data))
	{
		if($str != 'authentication' AND $str != 'registration')
			{


echo '<div class="d"><a href="http://'.$_SERVER['HTTP_HOST'].'/m/authentication">Вход</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/m/registration">Регистрация</a> </div>';

                   	}
	}

echo '<div class="title2"><a href="http://'.$_SERVER['HTTP_HOST'].'/m/category">Категории</a> | Топ-100</div>';
$all = $mysqli->query("SELECT * FROM `".$prefix."sait` WHERE `status` = '1' AND `ban` = '0' AND `hosts` > '0' AND `category` != '6' AND `category` != '12' AND `category` != '15'")->num_rows;
if($all == 0)
	{
		echo '<div class="main">';
		echo 'Активных сайтов не найдено.<br/>';
		echo '</div>';
	}
else
	{
		$total=intval(($all-1)/$pageTop)+1;
		$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
		if(empty($page) OR $page < 0)
			{
				$page = 1;
			}
		if($page > $total)
			{
				$page = $total;
			}
		$past = intval($all/$pageTop);
		$start = $page*$pageTop-$pageTop;
$top100 = $mysqli->query("SELECT `id`,`name`,`about`,`hosts`,`hits`,`in`,`out`,`url` FROM `".$prefix."sait` WHERE `status` = '1' AND `ban` = '0' AND `hosts` > '0' AND `category` != '6' AND `category` != '12' AND `category` != '15' ORDER BY `hosts` DESC LIMIT ".$start.",".$pageTop."");
		 $idd = intval($_GET['id']);
		while($row = $top100->fetch_assoc())
			{
			 if($row['id']==$idd){$u1='<font color=#8c5301><u>'; $u2='</u></font>';} else{$u1=''; $u2='';}
				$start++;
echo '<div class="title"><font class="tit">'.$start.'</font> <a href="http://'.$_SERVER['HTTP_HOST'].'/out/'.$row['id'].'"><strong>'.$u1.''.$row['url'].''.$u2.'</strong></a>';
echo ' ('.$row['hosts'].'/'.$row['hits'].')   <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$row['id'].'"><img src="http://'.$_SERVER['HTTP_HOST'].'/Design/themes/wap/img/st.png"></a><br/><br/>';

if(mb_strlen($row['about']) > 50){
$text = mb_substr($row['about'], 0, 60, 'utf-8');
echo ''.$text.'...';
}
else echo ''.$row['about'].'';

				echo '</div>';
			}
		if($all > $pageTop)
			{
				navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/');
			}
	}
//$newSait = $mysqli->query("SELECT `id`,`name` FROM `".$prefix."sait` ORDER BY `regdate` DESC LIMIT 1");
//$nSait = $newSait->fetch_assoc();

//$new = ($newSait->num_rows > 0)  ? '<a href = "http://'.$_SERVER['HTTP_HOST'].'/stats/'.$nSait['id'].'">'.$nSait['name'].'</a>' : 'Нет';
  ///$lastNews = $mysqli->query("SELECT `time` FROM `".$prefix."news` ORDER BY `time` DESC LIMIT 1");
///$lNews = $lastNews->fetch_assoc();
///$new2 = ($lastNews->num_rows > 0)  ? ' ('.data($lNews['time']).')' : '(0)';
///echo '<div class="title2">Меню</div><div class="d">';
///echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/news">Новости</a> '.$new2.' | ';
///echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/ban">Нарушители</a> | ';
///echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/rules">Правила</a>';
//echo '<small>Последний сайт: '.$new.'</small>';
///echo '</div>';
?>
