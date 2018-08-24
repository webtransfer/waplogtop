<?php
$title = 'Жалобы';
require_once ('Sys/head.php');
		$sait = $mysqli->query("SELECT `id`,`name` FROM `".$prefix."sait` WHERE `id` = '".$id."'");
		if($sait->num_rows == 0)
			{
				echo '<div class="error">';
				echo 'Данного сайта нет.<br/>';
				echo '</div>';
				require_once ('Sys/foot.php');
				exit;
			}
		$row = $sait->fetch_assoc();
		$countC = $mysqli->query("SELECT `id` FROM `".$prefix."complaint` WHERE `time` > ('".$time."' - 86400) AND `ip` = '".$ip."' AND `sid` = '".$id."'")->num_rows;
		if($countC > 0)
			{
				echo '<div class="error">';
				echo 'Вы уже подавали жалобу на этот сайт в течении 24 часов!<br/>';
				echo '</div>';
				require_once ('Sys/foot.php');
				exit;				
			}
		if(!isset($_POST['ok']))
			{
				echo '<div class="title2">Жалоба на сайт <strong>'.$check['name'].'</strong></div>';
				echo '
				<div class="d">
				<form action="/m/complaint/'.$id.'" method="post">
				Текст жалобы (max.150):<br/>
				<textarea class="form" name="text" cols="38" rows="8"></textarea><br/><br/>
				<input type="submit" class="button" name="ok" value="Отправить"/>
				</form></div>';
			}
		else
			{
				$text = filter($_POST['text']);
				$error = '';
				if(empty($text))
					{
						$error .= 'Не заполнено поле "Текст жалобы".<br/>';
					}
				if(mb_strlen($text) > 150)
					{
						$error .= 'Поле "Текст жалобы" содержит больше 150 символов.<br/>';
					}
				if(!empty($error))
					{
								echo '<div class="error">';
								echo 'В результате заполнения полей , выявились ошибки:<br/>';
								echo $error;
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/complaint/'.$id.'">Назад</a><br/>';
								echo '</div>';													
					}
				else
					{
						$mysqli->query("INSERT INTO `".$prefix."complaint` SET `sid` = '".$id."', `text` = '".$text."', `time` = '".$time."', `status` = '0', `ip` = '".$ip."'");
						echo '<div class="d">';
						echo 'Жалоба отправлена на рассмотрение.<br/>';
						echo '</div>';
					}								
			}
?>
