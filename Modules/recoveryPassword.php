<?php
$title = 'Восстановление пароля';
require_once ('Sys/head.php');
switch ($act)
	{
		default:
		unreg();
		if(!isset($_POST['ok']))
			{
				echo '<div class="d">';
				echo '<small>На E-Mail, указанный при регистрации, будет выслана ссылка для восстановления пароля.<br/></small>';
				echo'
				<form action="" method="POST">
				Логин:<br/>
				<input type="text" class="form" class="input" name="login" maxlength="30" value="" size="20" maxlength="50" /><br/><br/>
				<input name="ok" type="submit" class="button" value="Восстановить" /><br/>
				';
				echo '</div>';
			}
		else
			{
				$error = '';
				if(empty($_POST['login']))
					{
						$error .= 'Логин не введен.<br/>';
					}
				if(mb_strlen($_POST['login']) > 30 OR mb_strlen($_POST['login']) < 4)
					{
						$error .= 'Поле "Логин" должно быть не меньше 4 и не больше 30 символов.<br/>';
					}
				if(!empty($error))
					{
						echo '<div class="error">';
						echo 'В результате заполнения полей , выявились ошибки:<br/>';
						echo $error;
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/recoveryPassword">Повторить</a><br/>';
						echo '</div>';	
					}
				else
					{
						$login = filter($_POST['login']);
						$userIsset = $mysqli->query("SELECT `id`,`losttime`,`mail` FROM `".$prefix."users` WHERE `login` = '".$login."'");
						
						if($userIsset->num_rows > 0)
							{	
								$row = $userIsset->fetch_assoc();
								$error = '';
								if($row['losttime'] > $time-86400)
									{
										$error .= 'Восстанавливать пароль можно 1 раз в сутки.<br/>';
									}
								if($row['mail'] == '')
									{
										$error .= 'У данного пользователя не указан e-mail, обратитесь к администратору.<br/>';
									}
								if(!empty($error))
									{
										echo '<div class="error">';
										echo 'В результате заполнения полей , выявились ошибки:<br/>';
										echo $error;
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/recoveryPassword">Повторить</a><br/>';
										echo '</div>';	
									}
								else
									{
										$key = keyRand();
										$update = $mysqli->query("UPDATE `".$prefix."users` SET `losttime` = '".$time."', `lostkey` = '".$key."' WHERE `id` = '".$row['id']."'");
										$subject = "Восстановление пароля на сайте ".$_SERVER['HTTP_HOST'];
										$body = "Вы создали запрос на восстановление пароля к аккаунту на сайте ".$_SERVER['HTTP_HOST']."\n";
										$body .= "Для восстановления пароля , вам необходимо пройти по ссылке ниже:\n";
										$body .= "http://".$_SERVER['HTTP_HOST']."/m/recoveryPassword/".$row['id']."/".$key." \n";
										$headers = "From: ".$set['mail']." \n";
										$headers .= "Content-Type: text/plain; charset=utf-8\n";
										mail($row['mail'], $subject, $body, $headers);
										echo '<div class="main">На e-mail, указанный в аккаунте, отправлена инструкция по восстановлению пароля.</div>';
									}
							}
						else
							{
								echo '<div class="error">';
								echo 'Пользователь не найден, или данные не верны.<br/>';
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/recoveryPassword">Повторить</a><br/>';
								echo '</div>';
							}
					}
			}
		break;
		case 'good':
		if(isset($_GET['uid']) AND isset($_GET['key']))
			{
				$uid = abs(intval($_GET['uid']));
				$key = filter($_GET['key']);
				$usr = $mysqli->query("SELECT `id`,`mail` FROM `".$prefix."users` WHERE `id` = '".$uid."' AND `lostkey` = '".$key."' AND `losttime` > '".($time-86400)."'");
				if($usr->num_rows > 0)
					{	
						$row = $usr->fetch_assoc();
						$newPass = password();
						$update = $mysqli->query("UPDATE `".$prefix."users` SET `lostkey` = '', `password` = '".md5($newPass)."' WHERE `id` = '".$uid."'");
						echo '<div class="d">';
						echo 'Пароль успешно восстановлен.<br/>';
						echo 'Новый пароль: '.$newPass.'<br/>';
						echo '<small>Новый пароль также отправлен на E-MAIL указанный при регистрации.</small>';
						echo '</div>';
						$subject = "Новый пароль на сайте ".$_SERVER['HTTP_HOST'];
						$body = "У вас установлен новый пароль от аккаунта на сайте ".$_SERVER['HTTP_HOST']."\n";
						$body .= "Новый пароль: ".$newPass." \n";
						$headers = "From: ".$set['mail']." \n";
						$headers .= "Content-Type: text/plain; charset=utf-8\n";
						mail($row['mail'], $subject, $body, $headers);
					}
				else
					{
								echo '<div class="error">';
								echo 'Пароль не восстановлен.<br/>';
								echo '</div>';				
					}
			}
		else
			{
				echo '<div class="error">';
				echo 'Пароль не восстановлен.<br/>';
				echo '</div>';				
			}
		break;
	}
?>