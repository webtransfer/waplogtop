<?php
$title = 'Панель управления сайтом - Управление пользователями';
require_once('../Sys/head.php');
reg();
level(2);
switch($act)
	{
		default:
		echo '<div class="title2">Управление пользователями</div>';
		if(!isset($_GET['ok']))
			{
				echo '<div class="main">';
				echo 'Сортировать пользователей по: <br/>';
				echo '
				<form action="" method="GET">
				<select name="sort">
				<option value="id">ID</option>
				<option value="login">Логину</option>
				<option value="timeReg">Дате регистрации</option>
				</select><br />
				В порядке:<br/>
				<select name="por">
				<option value="asc">Возрастания</option>
				<option value="desc">Убывания</option>
				</select><br />
				<input name="ok" type="submit" class="button" class="go" value="Просмотреть" />
				</form></div><br />
				';
			}
		else
			{
				$sort = filter($_GET['sort']);
				$por = filter($_GET['por']);
				$error = '';
				if(empty($sort) OR empty($por))
					{
						$error .= 'Не заполнены поля.<br/>';
					}
				if(!empty($error))
					{
						echo '<div class="error">';
						echo 'В результате заполнения полей , выявились ошибки:<br/>';
						echo $error;
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/users">Назад</a><br/>';
						echo '</div>';
					}
				else
					{
						$count = $mysqli->query("SELECT `id` FROM `".$prefix."users`")->num_rows;
						if($count > 0)
							{
								$total=intval(($count-1)/$pageUsers)+1;
								$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
								if(empty($page) OR $page < 0)
									{
										$page = 1;
									}
								if($page > $total)
									{
										$page = $total;
									}
								$past = intval($count/$pageUsers);
								$start = $page*$pageUsers-$pageUsers;
								$users = $mysqli->query("SELECT `id`,`login`,`platformsCount` FROM `".$prefix."users` ORDER BY `".$sort."` ".$por." LIMIT ".$start.",".$pageUsers."");
								while($row = $users->fetch_assoc())
									{
										$start++;
										echo '<div class="title">'.$start.'. Логин: <strong>'.$row['login'].'</strong> (Площадок: <strong>'.$row['platformsCount'].'</strong>)</div>';
										echo '<div class="main">';
										echo '<hr><a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/users/deleteUser/'.$row['id'].'"><strong>(Удалить)</strong></a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/users/editUser/'.$row['id'].'"><strong>(Редактировать)</strong></a><br/>';
										echo '</div>';
									}
								if($count > $pageUsers)
									{
										navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/controlPanel/users/view/'.$sort.'/'.$por.'/ok/');
									}
							}
						else
							{
								echo '<div class="error">';
								echo 'По вашему запросу ничего не найдено.<br/>';
								echo '</div>';
							}
					}
			}
		break;
		case 'deleteUser':
		$userIsset = $mysqli->query("SELECT `id`,`login`,`level` FROM `".$prefix."users` WHERE `id` = '".$id."' LIMIT 1");
		if($userIsset->num_rows > 0)
			{
				$user = $userIsset->fetch_assoc();
				echo '<div class="title2">Удаление пользователя <strong>'.$user['login'].'</strong></div>';
				if($id == $user_data['id'] OR $user['level'] == 2)
					{
						echo '<div class="error">Удаление невозможно.</div>';
					}
				else
					{
						if(!isset($_GET['ok']))
							{
								echo '<div class="main">';
								echo 'Вы действительно хотите удалить пользователя <strong>'.$user['login'].'</strong> ?<br/>';
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/users/deleteUser/'.$id.'/ok">Да</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">Нет</a><br/>';
								echo '</div>';
							}
						else
						{
							$platforms = $mysqli->query("SELECT `id`,`category` FROM `".$prefix."sait` WHERE `uid` = '".$id."' LIMIT 1");
							while($sait = $platforms->fetch_assoc())
								{
									$deleteBrowsers = $mysqli->query("DELETE FROM `".$prefix."browsers` WHERE `sid` = '".$sait['id']."'");
									$deleteComplaint = $mysqli->query("DELETE FROM `".$prefix."complaint` WHERE `sid` = '".$sait['id']."'");
									$deleteCompression = $mysqli->query("DELETE FROM `".$prefix."compression` WHERE `sid` = '".$sait['id']."'");
									$deleteCountry = $mysqli->query("DELETE FROM `".$prefix."country` WHERE `sid` = '".$sait['id']."'");
									$deleteHours = $mysqli->query("DELETE FROM `".$prefix."hours` WHERE `sid` = '".$sait['id']."'");
									$deleteDays = $mysqli->query("DELETE FROM `".$prefix."days` WHERE `sid` = '".$sait['id']."'");
									$deleteMonth = $mysqli->query("DELETE FROM `".$prefix."month` WHERE `sid` = '".$sait['id']."'");
									$deleteOperators = $mysqli->query("DELETE FROM `".$prefix."operators` WHERE `sid` = '".$sait['id']."'");
									$deleteOnline = $mysqli->query("DELETE FROM `".$prefix."saitsOnline` WHERE `sid` = '".$sait['id']."'");
									$deleteGo = $mysqli->query("DELETE FROM `".$prefix."go` WHERE `sid` = '".$sait['id']."'");
									$deleteShows = $mysqli->query("DELETE FROM `".$prefix."shows` WHERE `sid` = '".$sait['id']."'");
									$updateCat = $mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` - 1) WHERE `id` = '".$sait['category']."'");
									$deletePlatform = $mysqli->query("DELETE FROM `".$prefix."sait` WHERE `uid` = '".$id."'");
								}
							$deleteUser = $mysqli->query("DELETE FROM `".$prefix."users` WHERE `id` = '".$id."'");
							echo '<div class="main">';
							echo 'Пользователь успешно удален.<br/>';
							echo '</div>';
						}
					}
			}
		else
			{
				echo '<div class="error">';
				echo 'Пользователь не найден.<br/>';
				echo '</div>';
			}
		break;
		case 'editUser':
		$userIsset = $mysqli->query("SELECT `id`,`login`,`level` ,`timeReg` FROM `".$prefix."users` WHERE `id` = '".$id."' LIMIT 1");
		if($userIsset->num_rows > 0)
			{
				$user = $userIsset->fetch_assoc();
				echo '<div class="title2">Редактирование пользователя <strong>'.$user['login'].'</strong></div>';
						if(!isset($_POST['ok']))
							{
								echo '<div class="main">';
								echo '<form action="" method="post" name="form">';
								echo 'Логин (max.30):<br/>';
								echo '<input type="text" class="form" name="login" maxlength="30" class="do_button" value="'.$user['login'].'"/><br/>';
								echo 'Уровень доступа:<br/>';
								echo '<select name="level">';
								if ($user['level'] == 1)
									{
										echo '<option value="1">Пользователь</option><br/>';
										echo '<option value="2">Администратор</option><br/>';
									}
								else
									{
										echo '<option value="2">Администратор</option><br/>';
										echo '<option value="1">Пользователь</option><br/>';
									}
								echo '</select><br/>';

								echo 'Дата регистрации: <br/><b>'.date("j-m-Y H:i:s",$user['timeReg']).'</b><br/>';

								echo '<input name="ok" type="submit" class="button" value="Сохранить" /></form></div>';
							}
						else
							{
								$login = filter($_POST['login']);
								$level = abs(intval($_POST['level']));
								$error = '';
								if(empty($login))
									{
										$error .= 'Поле "Логин" пустое.<br/>';
									}
								if(!empty($error))
									{
										echo '<div class="error">';
										echo 'В результате заполнения полей , выявились ошибки:<br/>';
										echo $error;
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/users/editUser/'.$id.'">Назад</a><br/>';
										echo '</div>';
									}
								else
									{
										$mysqli->query("UPDATE `".$prefix."users` SET `login` = '".$login."', `level` = '".$level."' WHERE `id` = '".$id."'");
										echo '<div class="main">';
										echo 'Пользователь успешно отредактирован.<br/>';
										echo '</div>';
									}
							}
			}
		else
			{
				echo '<div class="error">';
				echo 'Пользователь не найден.<br/>';
				echo '</div>';
			}
		break;
	}
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/users">К управлению пользователями</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>