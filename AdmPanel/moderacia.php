<?php
$title = 'Панель управления сайтом - Модерация сайтов';
require_once('../Sys/head.php');
reg();
level(2);
switch($act)
	{
		default:
		echo '<div class="title2">Модерация сайтов</div>';
		$count = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `status` = '0'")->num_rows;
		if($count > 0)
			{
				$total = intval(($count-1)/$set['pageModeracia'])+1; 
				$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : ''; 
				if(empty($page) OR $page < 0)
					{
						$page = 1; 
				}	
				if($page > $total)
					{
						$page = $total; 
					}
				$past = intval($count/$set['pageModeracia']);  
				$start = $page*$set['pageModeracia']-$set['pageModeracia']; 
				$moderation = $mysqli->query("SELECT `id`,`url`,`name`,`about` FROM `".$prefix."sait` WHERE `status` = '0' ORDER BY `id` DESC LIMIT ".$start.",".$set['pageModeracia']."");
				while($row = $moderation->fetch_assoc()) 
					{
						$start++;
						echo '<div class="title">'.$start.'.<strong>'.$row['name'].'</strong></div>';
						echo '<div class="main">';
						echo 'URL: <a href="http://'.$row['url'].'">http://'.$row['url'].'</a><br/>';
						echo 'Описание:<br/>';
						echo $row['about'].'<br/>';
						echo '<hr/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/moderacia/activate/'.$row['id'].'">[Активировать]</a><br/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/moderacia/delete/'.$row['id'].'">[Удалить]</a><br/>';
						echo '</div>';
					}
				if($count > $pageSait)
					{
						navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/controlPanel/moderacia/');
					}
			}
		else
			{
				echo '<div class="main">';
				echo 'Сайтов на модерации нет.<br/>';
				echo '</div>';
			}
		break;
		case 'activate':
		echo '<div class="title2">Модерация сайтов</div>';
		$mysqli->query("UPDATE `".$prefix."sait` SET  `status` = '1' WHERE `id` = '".$id."'");
		echo '<div class="main">';
		echo 'Сайт успешно активирован.<br/>';
		echo '</div>';
		break;
		case 'delete':
		echo '<div class="title2">Модерация сайтов</div>';
		$row = $mysqli->query("SELECT `category`,`uid` FROM `".$prefix."sait` WHERE `id` = '".$id."'")->fetch_assoc();
		$mysqli->query("DELETE FROM `".$prefix."sait` WHERE `id` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."browsers` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."complaint` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."compression` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."country` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."hours` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."days` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."month` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."operators` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."saitsOnline` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."go` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."shows` WHERE `sid` = '".$id."'");
		$mysqli->query("DELETE FROM `".$prefix."sait` WHERE `id` = '".$id."'");
		$mysqli->query("UPDATE `".$prefix."users` SET `platformsCount` = (`platformsCount` - 1) WHERE `id` = '".$row['uid']."'");
		$mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` - 1) WHERE `id` = '".$row['category']."'");		
		echo '<div class="main">';
		echo 'Сайт успешно удален<br/>';
		echo '</div>';
		break;
	}
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/moderacia">К модерации сайтов</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>
