<?php
$title = 'Панель управления сайтом - Управление категориями';
require_once('../Sys/head.php');
reg();
level(2);
switch($act)
	{
		default:
		echo'<div class="main"><a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/category/add">Добавить категорию</a><br/></div>';
		$cat = $mysqli->query("SELECT `id`,`name`,`position`,`count` FROM `".$prefix."cat` ORDER BY `position` ASC");
		if($cat->num_rows > 0)
			{
				while($row = $cat->fetch_assoc())
					{
						echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/m/category/view/'.$row['id'].'"><strong>'.$row['name'].'</strong></a><br/></div>';
						echo '<div class="main">';
						echo 'Позиция: '.$row['position'].'<br/>';
						echo 'Сайтов: '.$row['count'].'<br/>';
						echo '<hr/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/category/delete/'.$row['id'].'">Удалить</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/category/edit/'.$row['id'].'">Изменить</a><br/>';
						echo '</div>';
					}
			}
		else
			{
				echo 'Категории не созданы.<br/>';
			}
		break;
		case 'delete':
		$proverka = $mysqli->query("SELECT `name` FROM `".$prefix."cat` WHERE `id` = '".$id."'");
		if($proverka->num_rows > 0)
			{
				$row = $proverka->fetch_assoc();
				if(!isset($_POST['ok']))
					{
						echo '
						<div class="main">
						<form action="" method="post">
						При удалении категории '.$array['name'].' переместить все сайты в категорию:<br/>
						<select name="cid">';
						$cats = $mysqli->query("SELECT `id`,`name` FROM `".$prefix."cat` WHERE `id` != '".$id."' ORDER BY `position` ASC");
						if($cats->num_rows > 0)
							{
								while($cat = $cats->fetch_assoc())
									{
										echo '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
									}
							}
						echo '
						</select><br/>
						<input type="submit" class="button" name="ok" value="Удалить" />
						</form>
						</div>
						';
					}
					else
					{
						$error = '';
						$cid = isset($_POST['cid']) ? abs(intval($_POST['cid'])) : '';
						if($cid == 0)
							{
								$error = 'Не выбрана категория в которую перемещать сайты.<br/>';
							}
						$issetNewCat = $mysqli->query("SELECT `id` FROM `".$prefix."cat` WHERE `id` = '".$cid."'");
						if($issetNewCat->num_rows == 0)
							{
								$error = 'Категории , в которую вы хотели переместить сайты, нет.<br/>';
							}
						if($cid == $id)
							{
								$error = 'Нельзя перемещать в удаляемую категорию.<br/>';
							}
						if(!empty($error))
							{
								echo '<div class="error">';
								echo 'В результате заполнения полей , выявились ошибки:<br/>';
								echo $error;
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/delete/'.$id.'">Назад</a><br/>';
								echo '</div>';
							}	
							else
							{
								$countS = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `category` = '".$id."'")->num_rows;
								$mysqli->query("UPDATE `".$prefix."sait` SET `category` = '".$cid."' WHERE `category` = '".$id."'");
								$mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` + ".$countS.") WHERE `id` = '".$cid."'");
								$mysqli->query("DELETE FROM `".$prefix."cat` WHERE `id` = '".$id."'");
								echo '<div class="main">';
								echo 'Категория '.$array['name'].' удалена.<br/>';
								echo '</div>';
							}
					}
			}
		else
			{
				echo '<div class="error">';
				echo 'Категории нет.<br/>';
				echo '</div>';
			}
		break;
		case 'add':
		echo '<div class="title">Добавление категории</div>';
		if(!isset($_POST['ok']))
			{
				echo '
				<div class="main">
				<form action="" method="post">
				Имя категории (max.30):<br/>
				<input type="text" class="form" name="name" maxlength="30" value=""/><br/>
				Описание (max.75):<br/>
				<input type="text" class="form" name="about" maxlength="50" value=""/><br/>
				Позиция (max.100):<br/>
				<input type="text" class="form" name="position" maxlength="3" value=""/><br/>
				<input type="submit" class="button" name="ok" value="Добавить"/>
				</form></div>';
			}
		else
			{
				$name = filter($_POST['name']);
				$about = filter($_POST['about']);
				$position = abs(intval($_POST['position']));
				$error = '';
				if(empty($name) OR empty($about) OR empty($position))
					{
						$error .= 'Не заполнены поля.<br/>';
					}
				if(mb_strlen($name) > 30)
					{
						$error .= 'Поле "Имя категории" больше 30 символов.<br/>';
					}
				if(mb_strlen($about) > 75)
					{
						$error .= 'Поле "Описание" больше 50 символов.<br/>';
					}
				if(mb_strlen($position) > 3)
					{
						$error .= 'Поле "Позиция" больше 3 символов/<br/>';
					}
				if(!empty($error))
					{
						echo '<div class="error">';
						echo 'В результате заполнения полей , выявились ошибки:<br/>';
						echo $error;
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/category/add">Повторить<a/><br/>';
						echo '</div>';	
					}
				else
					{
						$mysqli->query("INSERT INTO `".$prefix."cat` VALUES ('', '".$name."', '".$about."', '0', '".$position."')");
						echo '<div class="main">';
						echo 'Категория успешно создана.<br/>';
						echo '</div>';
					}
			}
		break;
		case 'edit':
		if($id)
			{
				$isset = $mysqli->query("SELECT * FROM `".$prefix."cat` WHERE `id` = '".$id."'");
				if($isset->num_rows > 0)
					{
						$cat = $isset->fetch_assoc();
						echo '<div class="title">Изменение категории</div>';
						if(!isset($_POST['ok']))
							{
								echo '
								<div class="main">
								<form action="" method="post">
								Имя категории (max.30):<br/>
								<input type="text" class="form" name="name" maxlength="30" value="'.$cat['name'].'"/><br/>
								Описание (max.50):<br/>
								<input type="text" class="form" name="about" maxlength="50" value="'.$cat['about'].'"/><br/>
								Позиция (max.100):<br/>
								<input type="text" class="form" name="position" maxlength="3" value="'.$cat['position'].'"/><br/>
								<input type="submit" class="button" name="ok" value="Изменить"/>
								</form></div>';
							}
						else
							{
								$name = filter($_POST['name']);
								$about = filter($_POST['about']);
								$poz = abs(intval($_POST['position']));
								$error = '';
								if(empty($name) OR empty($about) OR empty($position))
									{
										$error .= 'Не заполнены поля.<br/>';
									}
								if(mb_strlen($name) > 30)
									{
										$error .= 'Поле "Имя категории" больше 30 символов.<br/>';
									}
								if(mb_strlen($about) > 50)
									{
										$error .= 'Поле "Описание" больше 50 символов.<br/>';
									}
								if(mb_strlen($poz) > 3)
									{
										$error .= 'Поле "Позиция" больше 3 символов.<br/>';
									}
								if(!empty($error))
									{
										echo '<div class="error">';
										echo 'В результате заполнения полей , выявились ошибки:<br/>';
										echo $error;
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/category/edit/'.$id.'">Повторить<a/><br/>';
										echo '</div>';	
									}
								else
									{
										$mysqli->query("UPDATE `".$prefix."cat` SET `name` = '".$name."', `position` = '".$position."', `about` = '".$about."' WHERE `id` = '".$id."'");
										echo '<div class="main">';
										echo 'Категория успешно изменена.<br/>';
										echo '</div>';
									}
							}
					}
				else
					{
						echo '<div class="error">';
						echo 'Данной категории нет.<br/>';
						echo '</div>';
					}
			}
		else
			{
				echo '<div class="error">';
				echo 'Не выбрана категория.<br/>';
				echo '</div>';
			}
		break;
	}
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/category">К управлению категориями</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>
