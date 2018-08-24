<?php
$title = 'Бан-лист';
require_once ('Sys/head.php');
$count = $mysqli->query("SELECT * FROM `".$prefix."sait` WHERE `ban` = '1'")->num_rows;
if($count == 0)
	{
		echo '<div class="main">';
		echo 'Бан-лист пуст.<br/>';
		echo '</div>';
		require_once ('Sys/foot.php');
		exit;
	}	
$total = intval(($count-1)/$pageSait)+1; 
$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : ''; 
if(empty($page) OR $page < 0)
	{
		$page = 1; 
	}
if($page > $total)
	{
		$page = $total; 
	}
$past = intval($count/$pageSait);  
$start = $page*$pageSait-$pageSait; 
$saits = $mysqli->query("SELECT `id`,`name`,`ban_reason` FROM `".$prefix."sait` WHERE `ban` = '1' ORDER BY `id` ASC LIMIT ".$start.",".$pageSait."");
while($row = $saits->fetch_assoc()) 
	{
		$start++;
		echo '<div class="title">'.$start.'.<strong>'.$row['name'].'</strong>[ID: '.$row['id'].']</div>';
		echo '<div class="main">';
		echo 'Причина блокировки : '.$row['ban_reason'].'<br/>';
		if($user_data['level'] == 2)
			{
				echo '<a href = "http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/ban/'.$row['id'].'">[Разблокировать]</a>';
			}
		echo '</div>';
	}
if($count > $pageSait)
	{
		echo navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/m/ban/');
	}
?>
