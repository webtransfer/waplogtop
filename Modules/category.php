<?php
$title = 'Категории сайта';
require_once ('Sys/head.php');
switch($act)
	{
		default:
				echo '<div class="title2"><a href="http://'.$_SERVER['HTTP_HOST'].'/?">TOP-100</a> | <strong>Категории</strong></div>';
				$cats = $mysqli->query("SELECT `id`,`name`,`count`,`about`  FROM `".$prefix."cat` ORDER BY `position`");
				while($cat = $cats->fetch_assoc())
					{
						echo '';
echo '<div class = "title"><img src="http://'.$_SERVER['HTTP_HOST'].'/Design/themes/wap/img/cat.png"> <a href = "http://'.$_SERVER['HTTP_HOST'].'/m/category/view/'.$cat['id'].'">'.$cat['name'].'</a> (<strong>'.$cat['count'].'</strong>)</div>';
					}
		break;
		case 'view':
					$query = $mysqli->query("SELECT * FROM `".$prefix."cat` WHERE `id` = '".$id."'");
					if($query->num_rows == 0)
						{
							echo '<div class="error">';
							echo 'Вы ошиблись категорией.<br/>';
							echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/category">Категории</a><br/>';
							echo '</div>';
							require_once ('Sys/foot.php');
							exit;
						}
						$cat = $query->fetch_array();
						echo '<div class="title2"><a href="http://'.$_SERVER['HTTP_HOST'].'/m/category">Категории</a> | <strong>'.$cat['name'].'</strong></div>';
						$count = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `category` = '".$id."' AND `status` = '1' AND `ban` = '0' AND `hosts` > '0'")->num_rows;
						if($count == 0)
							{
								echo '<div class="main">';
								echo 'Сайтов в данной категории не обнаружено.<br/>';
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
						$saits = $mysqli->query("SELECT `id`,`name`,`hosts`,`hits`,`about`,`out`,`in`,`url` FROM `".$prefix."sait` WHERE `category` = '".$id."' AND `status` = '1' AND `ban` = '0' AND `hosts` > '0' ORDER BY `hosts` DESC LIMIT ".$start.",".$pageSait."");
						while($row = $saits->fetch_assoc())
							{
								$start++;
echo '<div class="title"><font class="tit">'.$start.'</font> <a href="http://'.$_SERVER['HTTP_HOST'].'/out/'.$row['id'].'"><strong>'.$row['url'].'</strong></a> ('.$row['hosts'].'/'.$row['hits'].') <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$row['id'].'"><img src="http://'.$_SERVER['HTTP_HOST'].'/Design/themes/wap/img/st.png"></a><br/><br/>';
if(mb_strlen($row['about']) > 50){
$text = mb_substr($row['about'], 0, 60, 'utf-8');
echo ''.$text.'...';
}
else echo ''.$row['about'].'';


								echo '</div>';
							}
						if($count > $pageSait)
							{
								navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/m/category/view/'.$id.'/');
							}
		break;
	}
?>
