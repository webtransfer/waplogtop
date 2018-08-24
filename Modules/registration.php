<?php
$title = 'Регистрация сайта';
require_once('Sys/head.php');
if($set['powerRegistration'] == 0)
	{
		echo '<div class="error">';
		echo 'Регистрация закрыта на неопределенное время.<br/>';
		echo '</div>';
		require_once ('Sys/foot.php');
		exit;
	}
echo '<div class="title2">Регистрация</div>';
if(isset($_POST['ok']))
	{
		$login = filter($_POST['login']);
		$mail = filter($_POST['mail']);
		$pass = filter($_POST['password']);
		$pass1 = filter($_POST['password1']);
		$kod = filter($_POST['kod']);
		$error = '';
		if(empty($login) or empty($mail) or empty($pass) or empty($pass1) or empty($kod))
			{
				$error .= 'Все поля обязательны к заполнению.<br/>';
			}
		if($pass != $pass1)
			{
				$error .= 'Пароли не совпадают.<br/>';
			}
		if($_SESSION['code']!=$kod)
			{
				$error .= 'Код с картинки введён не верно.<br/>';
			}
		if ($mysqli->query("SELECT `id` FROM `".$prefix."users` WHERE `login` = '".$login."' LIMIT 1")->num_rows != 0)
			{
				$error .= 'Логин уже зарегистрирован. Выберите другой.<br/>';
			}
		if (!preg_match('|^[A-Za-z0-9\-_]+$|i',$login))
			{
				$error .= 'В логине можно использовать только латиницу и цифры.<br/>';
			}
		if (!empty($mail) and !preg_match('#^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+(\.([A-Za-z0-9])+)+$#', $mail))
			{
				$error .= 'Вы ввели неверный адрес e-mail, необходим формат name@site.domen.<br/>';
			}
		if (!empty($mail) and ($mysqli->query("SELECT `id` FROM `".$prefix."users` WHERE `mail` = '".$mail."' LIMIT 1")->num_rows != 0))
			{
				$error .= 'Пользователь с данным e-mail уже зарегистрирован.<br/>';
			}
		if(mb_strlen($login) > 30 OR mb_strlen($login) < 5)
			{
				$error .= 'Поле "Логин" должно быть не меньше 5 и не больше 30 символов.<br/>';
			}
		if(mb_strlen($pass) > 30 OR mb_strlen($pass) < 5)
			{
				$error .= 'Поле "Пароль" должно быть не меньше 5 и не больше 30 символов.<br/>';
			}

		if(!empty($error))
			{
				echo '<div class="error">';
				echo 'В результате заполнения полей , выявились ошибки:<br/>';
				echo $error;
				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/registration">Назад</a><br/>';
				echo '</div>';
			}
		else
			{
				$subject = "Регистрация в Топ-рейтинге сайтов ".$_SERVER['HTTP_HOST'];
				$body = "Вы зapeгиcтриpoвaлись в Топ-рейтинге сайтов ".$_SERVER['HTTP_HOST'].".\nВaши дaнные:\n";
				$body .= "Логин: ".$login."\nПароль: ".$pass."\n";
				$body .= "Не теряйте свои данные.\n";
				$body .= "С Уважением Администрация сайта.\n";
				$headers = "From: ".$set['mail']." \n";
				$headers .= "Content-Type: text/plain; charset=utf-8\n";
				mail($mail, $subject, $body, $headers);
				unset($_SESSION['code']);
				$mysqli->query("INSERT INTO `".$prefix."users` SET `login` = '".$login."', `password` = '".md5($pass)."', `mail` = '".$mail."', `pageNews` = '".$set['pageNews']."', `pageNewsc` = '".$set['pageNewsc']."', `pageSait` = '".$set['pageSait']."', `pagePlatforms` = '".$set['pagePlatforms']."', `pageTop` = '".$set['pageTop']."', `pages` = '".$set['pages']."', `level` = '1', `timeReg` = '".$time."', `style` = '".$set['style']."', `platformsCount` = '0'");
				echo '<div class="d">Регистрация прошла успешно.<br/>';
				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/authentication"><font color=#8c5301><b>Вход</b></font></a></div>';
			}
	}
else
	{
		echo '<div class="d">';
		echo'
		<form action="" method="post">
		Логин: [A-Za-z0-9_-] (max.30)<br/>
		<input name="login" type="text" class="form" value="" /><br/>
		Пароль(max.30):<br/>
		<input name="password" type="password" class="form" value="" /><br/>
		Повторите пароль:<br/>
		<input name="password1" type="password" class="form" value="" /><br/>
		E-mail:<br/>
		<input name="mail" type="text" class="form" value="" /><br/>
		Код с картинки: <br/><img src="http://'.$_SERVER['HTTP_HOST'].'/captcha_'.rand(123456789,99999999).'" alt="Включите картинки" /><br/>
		<input name="kod" type="text" class="form" value="" /><br/>		
		<input name="ok" type="submit" class="button" value="Регистрация" /></form>
		';
		echo '<small>Все поля ОБЯЗАТЕЛЬНЫ к заполнению.</small><br/>';
		echo '<small>Регистрируясь, вы соглашаетесь с <a href="http://'.$_SERVER['HTTP_HOST'].'/m/rules"><font color=#8c5301><b>ПРАВИЛАМИ</b><font></a> рейтинга.</small>';
		echo '</div>';
	}
?>
