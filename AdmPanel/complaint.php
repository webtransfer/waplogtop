<?php
$title = 'Панель управления сайтом - Управление категориями';
require_once('../Sys/head.php');
reg();
level(2);
switch($act)
	{		
		default:
			echo '<div class="title2">Список жалоб</div>';
					$count = $mysqli->query("SELECT `id` FROM `".$prefix."complaint` WHERE `status` = '0'")->num_rows;
					if($count > 0)
						{
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
							$saits = $mysqli->query("SELECT * FROM `".$prefix."complaint` WHERE `status` = '0' ORDER BY `status` ASC LIMIT ".$start.",".$pageSait."");
							while($row = $saits->fetch_assoc()) 
								{
									$sait1 = $mysqli->query("SELECT * FROM `".$prefix."sait` WHERE `id` = '".$row['sid']."'");
									$start++;
									if($sait1->num_rows > 0)
										{	
											$sait = $sait1->fetch_assoc();
											echo '<div class="title">'.$start.'.<strong>'.$sait['name'].'</strong> <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$row['sid'].'"><strong>(Статистика)</strong></a><br/></div>';
											echo '<div class="main">';
											echo 'Текст жалобы:<br/> <strong>'.$row['text'].'</strong><br/>';
											echo 'IP отправителя:<br/> <strong>'.$row['ip'].'</strong><br/>';
											echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/editSait/'.$row['sid'].'"><strong>(Редактировать сайт)</strong></a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/deleteSait/'.$row['sid'].'"><strong>[Удалить сайт]</strong> |<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/complaint/readed/'.$row['id'].'"><strong>(Пометить как прочтенное)</strong></a>';
											echo '</div>';
										}
									else
										{
											echo '<div class="title">'.$start.'.<strong>DELETED</strong></div>';
											echo '<div class="main">';
											echo 'Сайт был удален ранее.';
											echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/complaint/readed/'.$row['id'].'"><strong>*Пометить как прочтенное)</strong></a>';
											echo '</div>';
										}
									
								}
								if($count > $pageSait)
									{
										navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/controlPanel/complaint/');
									}
						}
					else
						{
							echo '<div class="main">';
							echo 'Свежих жалоб нет.<br/>';
							echo '</div>';
						}	
					break;
					case 'readed':
					if($id)
						{
							$mysqli->query("UPDATE `".$prefix."complaint` SET `status` = '1' WHERE `id` = '".$id."'");
							echo '<div class="main">';
							echo 'Жалоба успешно помечена как прочтенная.<br/>';
							echo '</div>';
						}
					break;	
	}
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/complaint">К управлению жалобами</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>
