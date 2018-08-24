<?php
$title = 'Панель управления сайтом - Рассылка почты';
require_once('../Sys/head.php');
reg();
level(2);
if(!isset($_POST['ok']))
	{
		echo '<div class="title2">Сообщения отсылаются всем участникам на e-mail указанный при регистрации<br/></div>';
		echo '
		<div class="main">
		<form action="mail/send" method="post">
		Тема сообщения (max.100):<br/>
		<input type="text" class="form" name="topic" class="input" maxlength="100" /><br />
		Текст сообщения (max. 300):<br/>
		<textarea class="form" name="text" cols="38" rows="8"></textarea><br/>
		<input name="ok" type="submit" class="button" value="Отправить" />
		</form>
		</div>
		';
	}
else
	{
		$topic = filter($_POST['topic']);
		$text = filter($_POST['text']);
		$error = '';
		if(empty($topic) OR empty($text))
			{
				$error .= 'Не заполнены поля.<br/>';
			}
		if(mb_strlen($topic) > 100)
			{
				$error .= 'Поле "Тема сообщения" содержит больше 100 символов.<br/>';
			}
		if(mb_strlen($text) > 300)
			{
				$error .= 'Поле "Текст сообщения" содержит больше 300 символов.<br/>';
			}
		if(!empty($error))
			{
				echo '<div class="error">';
				echo 'В результате заполнения полей , выявились ошибки:<br/>';
				echo $error;
				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/mail">Повторить<a/><br/>';
				echo '</div>';	
			}
		else
			{
				$headers = '';
				$headers .= "From: ".$set['mail']." \n";
				$headers .= "Content-Type: text/plain; charset=utf-8\n";
				$mails = $mysqli->query("SELECT DISTINCT `mail` FROM `".$prefix."users` WHERE `mail` <> ''");
				if($mails->num_rows > 0)
					{
						while($row = $mails->fetch_assoc())
							{
								mail($row['mail'], $topic, $text, $headers);
							}
						echo '<div class="main">';
						echo 'Ваше сообщение отправлено '.$mails->num_rows.' участнику(ам).<br/>';
						echo '</div>';
					}
				else
					{
						echo '<div class="main">';
						echo 'E-MAIL`ов не найдено.<br/>';
						echo '</div>';
					}
			}
	}
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/mail">К массовой отправке почты</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>