<?php
define('NTOP', 1);
$title = 'Категории';
require_once ('../system/connect.php');
require_once ('../system/core.php');
require_once ('../system/function.php');
require_once ('head.php');

switch($act)
	{
		default:
		echo '<div class="top"><div class="title2"><font color="#fff"><a href="http://'.$set['home'].'/?">TOP-100</a> | <strong>Категории</strong></font></div><div class="clear"></div></div>';
		$cats = mysql_query("SELECT * FROM `".$prefix."cat` ORDER BY `position`");
		while($cat = mysql_fetch_array($cats))
			{
				$count_sites = mysql_num_rows(mysql_query("SELECT * FROM `".$prefix."sait` WHERE `category` = '".$cat['id']."' AND `status` = '1' AND `hosts` > '0'"));
				echo '<div class = "title">';
				echo '» <a href = "http://'.$set['home'].'/m/category/view/'.$cat['id'].'">'.$cat['name'].'</a> ('.$count_sites.')</div>';
			}
		break;
		case 'view':
		$isset = mysql_query("SELECT * FROM `".$prefix."cat` WHERE `id` = '".$id."'");
		if(mysql_num_rows($isset) > 0)
			{
				$catt = mysql_fetch_array($isset);
				echo '<div class="top"><div class="title2"><font color="#fff"><a href="http://'.$set['home'].'/m/category">Категории</a> | <strong>'.$catt['name'].'</strong></font></div><div class="clear"></div></div>';
				$count = mysql_num_rows(mysql_query("SELECT * FROM `".$prefix."sait` WHERE `category` = '".$id."' AND `status` = '1' AND `ban` = '0' AND `hosts` > '0'"));
				if($count > 0)
					{
						$total=intval(($count-1)/$page_sait)+1; 
						$page=abs(intval($_GET['page'])); 
						if(empty($page) OR $page < 0)
							{
								$page = 1; 
							}
						if($page > $total)
							{
								$page = $total; 
							}
						$past=intval($count/$page_sait);  
						$start=$page*$page_sait-$page_sait; 
						$saits = mysql_query("SELECT * FROM `".$prefix."sait` WHERE `category` = '".$id."' AND `status` = '1' AND `ban` = '0' AND `hosts` > '0' ORDER BY `hosts` DESC LIMIT ".$start.",".$page_sait."");
						while($row = mysql_fetch_array($saits)) 
							{
								$start++;
								
								echo '<div class="title">'.$start.'.<a href="http://'.$set['home'].'/out/'.$row['id'].'"><strong>'.$row['url'].'</strong></a>  ';
                                                                echo '['.$row['hosts'].'|'.$row['hits'].']  <a href="http://'.$set['home'].'/stats/'.$row['id'].'"><img src="/images/stat.png" alt="s" /></a><br/>';
								
								if(mb_strlen($row['about']) > 150)
									{
										$text = substr($row['about'],0,150);
										echo ''.$text.'...<br/>'; 
									}
								else
									{
										echo ''.$row['about'].'<br/>'; 
									}
								echo '</div>';
							}
						navigation($count,$page_sait,$page,'http://'.$set['home'].'/m/category/view/'.$id.'/',$total);
					}
				else
					{
						echo '<div class="main">';
						echo 'Сайтов в данной категории нет!<br/>';
						echo '<a href="http://'.$set['home'].'/m/category">К списку категорий</a><br/>';
						echo '</div>';
					}
			}
		else
			{
				echo '<div class="error">';
				echo 'Ошибка!Данной категории не существует!<br/>';
				echo '<a href="http://'.$set['home'].'/m/category">К списку категорий</a><br/>';
				echo '</div>';
			}
		break;
	}

require_once ('foot.php');
?>