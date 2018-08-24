<?php
ob_start();
$str = 'authentication';
$title = 'Аутентификация пользователя';
require_once('Sys/head.php');
switch ($act)
	{
		default:
				unreg();
				if(!isset($_POST['ok']))
					{
						echo '<div class="d">';
						echo'
						<form action="" method="POST">
						Логин: <br/>
						<input type="text" class="form" class="input" name="login" maxlength="30" value="" size="20" maxlength="50" /><br/>
						Пароль: <br/>
						<input type="password" class="form" class="input" name="password" maxlength="30" value="" size="20" maxlength="50" /><br/><br/>
						<input name="ok" type="submit" class="button" value="Войти" /></div>';
						echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/m/recoveryPassword"><small>Восстановление пароля</small></a><br/></div>';
						echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/m/registration"><small>Регистрация</small></a></div>';
						
					}
				else
					{	
						$error = '';
						if(empty($_POST['login']) OR empty($_POST['password']))
							{
								$error .= 'Одно из полей не было заполнено.';
							}
						if(mb_strlen($_POST['login']) > 30 OR mb_strlen($_POST['login']) < 5)
							{
								$error .= 'Поле "Логин" должно быть не меньше 5 и не больше 30 символов.<br/>';
							}
						if(mb_strlen($_POST['password']) > 30 OR mb_strlen($_POST['password']) < 5)
							{
								$error .= 'Поле "Пароль" должно быть не меньше 5 и не больше 30 символов.<br/>';
							}
						if(!empty($error))
							{
								echo '<div class="error">';
								echo 'В результате заполнения полей , выявились ошибки:<br/>';
								echo $error;
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/authentication">Повторить<a/><br/>';
								echo '</div>';		
							}
						else
							{
								$login = filter($_POST['login']);
								$password = filter($_POST['password']);
								$userIsset = $mysqli->query("SELECT `id` FROM `".$prefix."users` WHERE `login` = '".$login."' AND `password` = '".md5($password)."'");
								if($userIsset->num_rows > 0)
									{
										SetCookie('login',$login,$time+3600*24*365, '/');
										SetCookie('password',md5($password),$time+3600*24*365, '/');
										echo '
										<div class="main">
										Добро пожаловать , <font color="green"><strong>'.$login.'</strong></font><br/>
										Ваш текущий IP: <font color="green"><strong>'.$ip.'</strong></font><br/>
										<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office">Перейти в кабинет</a><br/>
										</div>
										';
									}
								else
									{
										echo '<div class="error">';
										echo 'Авторизация не прошла , возможно данные введены не верно.<br/>';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/authentication">Повторить<a/><br/>';
										echo '</div>';
									}
							}
					}
		break;
		case 'exit':
					reg();
					if(isset($_COOKIE['login']) AND isset($_COOKIE['password']))
						{
							SetCookie('login','',$time, '/');
							SetCookie('password','',$time, '/');
							echo '<div class="main">';
							echo 'Выход успешно произведен.<br/>';
							echo '</div>';
						}
					else
						{
							echo '<div class="error">';
							echo 'Авторизуйтесь , чтобы выходить.<br/>';
							echo '</div>';
						}
		break;
	}
?>
