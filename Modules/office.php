<?php
$str = 'office';
$title = 'Кабинет';
require_once('Sys/head.php');
reg();
switch($act)
	{
		default:
		echo '<div class="title2"><strong>Кабинет</strong></div>';
		echo '<div class="main">';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/platforms">Площадки</a><small> ('.$user_data['platformsCount'].' из '.$set['maxPlatforms'].')</small><br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/settings">Настройки аккаунта</a><br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/code">Код счётчика</a><br/>';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/changePassword">Смена пароля аккаунта</a></div>';
		break;
		case 'platforms':
		echo '<div class="title2">Площадки | <small>Всего площадок: <strong>'.$user_data['platformsCount'].'</strong> из <strong>'.$set['maxPlatforms'].'</strong></small></div>';
		if($user_data['platformsCount'] == 0)
			{
				echo '<div class="main">';
				echo 'На данный момент у вас нет площадок.<br/>';
				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/addPlatform">Добавить площадку</a><br/>';
				echo '</div>';
			}
		else
			{
				$total = intval(($user_data['platformsCount']-1)/$pagePlatforms)+1;
				$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
				if(empty($page) OR $page < 0)
					{
						$page = 1;
					}
				if($page > $total)
					{
						$page = $total;
					}
				$past = intval($user_data['platformsCount']/$pagePlatforms);
				$start = $page*$pagePlatforms-$pagePlatforms;
				$platform = $mysqli->query("SELECT `id`,`name`,`about`,`status` FROM `".$prefix."sait` WHERE `uid` = '".$user_data['id']."' ORDER BY `id` ASC LIMIT ".$start.",".$pagePlatforms."");
				while($row = $platform->fetch_assoc())
					{
						$status = $row['status'] == 1 ? '<font color=green>Модерация пройдена</font>' : '<font color=red>Проходит модерацию</font>';
						$start++;
						echo '<div class="title"><strong>'.$row['name'].'</strong> <small>(ID: '.$row['id'].' | '.$status.')</small></div>';
						echo '<div class="main">';
						if(mb_strlen($row['about']) > 50)
							{
								$text = mb_substr($row['about'],0,50);
								echo ''.$text.'...<br/>';
							}
						else
							{
								echo ''.$row['about'].'<br/>';
							}
						echo '<hr>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/editPlatform/'.$row['id'].'">(Редактировать)</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/deletePlatform/'.$row['id'].'">(Удалить)</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$row['id'].'">(Статистика)</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/code/'.$row['id'].'">(Код счётчика)</a></br>';
						echo '</div>';
					}
					if($total > 1)
						{
							navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/m/office/platforms/',$total);
						}
				echo '<div class="main"><a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/addPlatform">Добавить площадку</a></div>';
			}
		break;
		case 'addPlatform':
		echo '<div class="title2">Добавление площадки</div>';
		if(!isset($_POST['ok']))
			{
				echo '<div class="main">';
				echo '
				<form action="/m/office/addPlatform" method="post">
				Название сайта (max.35):<br/>
				<input type="text" class="form"  class="input" name="name" maxlength="35" value="" /><br/>
				URL сайта (без http:// и т.п) (max. 35):<br/>
				<input type="text" class="form"  class="input" name="url" maxlength="35" value="" /><br/>
				Описание сайта (max. 250):<br/>
				<textarea class="form" name="about" cols="38" rows="8"></textarea><br/>
				Категория:<br/>
				<select name="cat">
				';
				$cats = $mysqli->query("SELECT * FROM `".$prefix."cat` ORDER BY `position` ASC");
				if($cats->num_rows > 0)
					{
						while($cat = $cats->fetch_array())
							{
								echo '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
							}
					}
				echo '</select><br/><br/>';
				echo '
				Выберите счётчик:<br/>
				';
				echo '';
				echo '<small><strong>Для главной страницы сайта:</strong></small><br/>';
				$imageBig = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `type` = 'big'   ORDER BY `id` ");
				if($imageBig->num_rows > 0)
					{
						while($imBig = $imageBig->fetch_array())
							{
								$start++;
								if($start==1){$chek = ' checked="checked"';}
								else {$chek = '';}
								echo '<input type="radio"'.$chek.' name="image" value="'.$imBig['name'].'" />';
								echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/Counters/'.$imBig['name'].'.png" alt="" /><br/>';
							}
					}
				else
					{
						echo 'Больших счётчиков нет.';
					}
				echo '<hr/>';
				echo '<small><strong>Для остальных страниц сайта <font size=2>*</font></strong></small><br/>';
				$imageSmall = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `type` = 'small'   ORDER BY `id` ");
				if($imageSmall->num_rows > 0)
					{
						while($imSmall = $imageSmall->fetch_array())
							{
							$startt++;
								if($startt==1){$chekk = ' checked="checked"';}
								else {$chekk = '';}
								echo '<input type="radio"'.$chekk.' name="imageOther" value="'.$imSmall['name'].'" />';
								echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/Counters/'.$imSmall['name'].'.png" alt="" /><br/>';
							}
					}
				else
					{
						echo 'Маленьких счетчиков нет.';
					}
				echo '<font size=2>*</font></strong> - Для остальных страниц сайта, установка счётчика по вашему желанию.<br/>';
				echo '<input name="ok" type="submit" class="button" value="Добавить" />
				</form>
				</div>';
			}
		else
			{
				if($userdata['platformsCount'] >= $set['maxPlatforms'])
					{
						echo '<div class="error">';
						echo 'Вы превысили лимит площадок , на данный момент можно создавать только '.$set['maxPlatforms'].'. <br/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/platforms">К площадкам</a><br/>';
						echo '</div>';
					}
				else
					{
						$url = filter($_POST['url']);
						$name = filter($_POST['name']);
						$about = filter($_POST['about']);
						$cat = intval($_POST['cat']);
						$image = filter($_POST['image']);
						$imageOther = filter($_POST['imageOther']);
						$pr = intval(getPageRank($url));
						$cy = intval(cy($url));
						$error = '';
						$issetImage = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `name` = '".$image."' AND `type` = 'big'")->num_rows;
						if($issetImage == 0)
							{
								$error .= 'Нет такого счётчика для главной страницы.<br/>';
							}
						$issetImageOther = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `name` = '".$imageOther."' AND `type` = 'small'")->num_rows;
						if($issetImageOther == 0)
							{
								$error .= 'Нет такого счётчика для остальных страниц.<br/>';
							}
						$issetCat = $mysqli->query("SELECT `id` FROM `".$prefix."cat` WHERE `id` = '".$cat."'")->num_rows;
						if($issetCat == 0)
							{
								$error .= 'Нет такой категории.<br/>';
							}
						if(empty($url) OR empty($name) OR empty($about) OR empty($cat) OR empty($image))
							{
								$error .= 'Не заполнены обязательные поля.<br/>';
							}
						if(mb_strlen($url) >= 35)
							{
								$error .= 'Поле "URL сайта" не должно быть больше 35 символов.<br/>';
							}
						if(mb_strlen($name) >= 35)
							{
								$error .= 'Поле "Название сайта" не должно быть больше 35 символов.<br/>';
							}
						if(mb_strlen($about) >= 250)
							{
								$error .= 'Поле "Описание сайта" не должно быть больше 250 символов.<br/>';
							}
						if(!empty($error))
							{
								echo '<div class="error">';
								echo 'В результате заполнения полей , выявились ошибки:<br/>';
								echo $error;
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/addPlatform">Назад</a><br/>';
								echo '</div>';
							}
						else
							{

								$add = $mysqli->query("INSERT INTO `".$prefix."sait` VALUES ('', '".$user_data['id']."', '".$name."', '".$about."', '".$time."', '', '', '', '', '', '', '', '', '".$cy."', '".$pr."', '".$cat."', '".$image."', '".$imageOther."', '".$url."', '".$set['moderacia']."', '', '', '')");
								$updateUser = $mysqli->query("UPDATE `".$prefix."users` SET `platformsCount` = (`platformsCount` + 1) WHERE `id` = '".$user_data['id']."'");
								$updateCat = $mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` + 1) WHERE `id` = '".$cat."'");
								echo '<div class="main">';
								echo 'Площадка успешно добавлена!<br/>';
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/platforms">К площадкам</a><br/>';
								echo '</div>';
							}
					}
			}
		break;
		case 'editPlatform':
		if(!isset($_POST['ok']))
			{
				echo '<div class="title2">Редактирование площадки</div>';
				$platform = $mysqli->query("SELECT `name`,`url`,`about`,`category`,`image`,`imageOther` FROM `".$prefix."sait` WHERE `id` = '".$id."' AND `uid` = '".$user_data['id']."' LIMIT 1");
				if($platform->num_rows > 0)
					{
						$userPlatform = $platform->fetch_assoc();
						echo '<div class="main">';
						echo '
						<form action="/m/office/editPlatform/'.$id.'" method="post">
						Название сайта(max. 35):<br/>
						<input type="text" class="form"  class="input" name="name" maxlength="35" value="'.$userPlatform['name'].'" /><br/>
						URL сайта(без http:// и т.п)(max. 35):<br/>
						<input type="text" class="form"  class="input" name="url" maxlength="35" value="'.$userPlatform['url'].'" /><br/>
						Описание сайта (max. 250):<br/>
						<textarea class="form" name="about" cols="38" rows="8">'.$userPlatform['about'].'</textarea><br/>
						Категория:<br/>
						<select name="cat">';
						$cats = $mysqli->query("SELECT * FROM `".$prefix."cat` ORDER BY `position` ASC");
						if($cats->num_rows > 0)
							{
								while($cat = $cats->fetch_assoc())
									{
										$selected = ($userPlatform['category'] == $cat['id']) ? ' selected="selected"' : '';
										echo '<option value="'.$cat['id'].'"'.$selected.'>'.$cat['name'].'</option>';
									}
							}
						echo '</select><br/><br/>';
						echo '
						Выберите счётчик:<br/>
						';
						echo '<small><strong>Для главной страницы сайта:</strong></small><br/>';
						$imageBig = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `type` = 'big'   ORDER BY `id` ");
						if($imageBig->num_rows > 0)
							{
								while($imBig = $imageBig->fetch_array())
									{
										$checked = ($userPlatform['image'] == $imBig['name']) ? ' checked="checked"' : '';
										echo '<input type="radio" '.$checked.' name="image" value="'.$imBig['name'].'" />';
										echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/Counters/'.$imBig['name'].'.png" alt="" /><br/>';
									}
							}
						else
							{
								echo 'Больших счетчиков нет.';
							}
						echo '<hr/>';
						echo '<small><strong>Для остальных страниц сайта <font size=2>*</font></strong></small><br/>';
						$imageSmall = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `type` = 'small'   ORDER BY `id` ");
						if($imageSmall->num_rows > 0)
							{
								while($imSmall = $imageSmall->fetch_array())
									{
										$checked = ($userPlatform['imageOther'] == $imSmall['name']) ? ' checked="checked"' : '';
										echo '<input type="radio" '.$checked.' name="imageOther" value="'.$imSmall['name'].'" />';
										echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/Counters/'.$imSmall['name'].'.png" alt="" /><br/>';
									}
							}
						else
							{
								echo 'Маленьких счетчиков нет.';
							}
						echo '<font size=2>*</font></strong> - Для остальных страниц сайта, установка счётчика по вашему желанию.<br/>';
						echo '<input name="ok" type="submit" class="button" value="Изменить" /></form></div>';
					}
				else
					{
						echo '<div class="error">';
						echo 'Такой площадки нет.<br/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/platforms">Назад</a><br/>';
						echo '</div>';
					}
			}
		else
			{
						$url = filter($_POST['url']);
						$name = filter($_POST['name']);
						$about = filter($_POST['about']);
						$cat = intval($_POST['cat']);
						$image = filter($_POST['image']);
						$imageOther = filter($_POST['imageOther']);
						$error = '';
						$issetImage = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `name` = '".$image."' AND `type` = 'big'")->num_rows;
						if($issetImage == 0)
							{
								$error .= 'Нет такого счётчика для главной страницы.<br/>';
							}
						$issetImageOther = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `name` = '".$imageOther."' AND `type` = 'small'")->num_rows;
						if($issetImageOther == 0)
							{
								$error .= 'Нет такого счётчика для остальных страниц.<br/>';
							}
						$issetCat = $mysqli->query("SELECT `id` FROM `".$prefix."cat` WHERE `id` = '".$cat."'")->num_rows;
						$issetCat = $mysqli->query("SELECT `id` FROM `".$prefix."cat` WHERE `id` = '".$cat."'")->num_rows;
						if($issetCat == 0)
							{
								$error .= 'Нет такой категории.<br/>';
							}
						$platformIsset = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `id` = '".$id."' AND `uid` = '".$user_data['id']."' LIMIT 1")->num_rows;
						if($platformIsset == 0)
							{
								$error .= 'У вас нет такой площадки.<br/>';
							}
						if(empty($url) OR empty($name) OR empty($about) OR empty($cat) OR empty($image))
							{
								$error .= 'Не заполнены обязательные поля.<br/>';
							}
						if(mb_strlen($url) >= 35)
							{
								$error .= 'Поле "URL сайта" не должно быть больше 35 символов.<br/>';
							}
						if(mb_strlen($name) >= 35)
							{
								$error .= 'Поле "Название сайта" не должно быть больше 35 символов.<br/>';
							}
						if(mb_strlen($about) >= 250)
							{
								$error .= 'Поле "Описание сайта" не должно быть больше 250 символов.<br/>';
							}
						if(!empty($error))
							{
								echo '<div class="error">';
								echo 'В результате заполнения полей , выявились ошибки:<br/>';
								echo $error;
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/addPlatform">Назад</a><br/>';
								echo '</div>';
							}
						else
							{
								$category = $mysqli->query("SELECT `category` FROM `".$prefix."sait` WHERE `id` = '".$id."' AND `uid` = '".$user_data['id']."'LIMIT 1")->fetch_assoc();
								$mysqli->query("UPDATE `".$prefix."sait` SET `category` = '".$cat."', `image` = '".$image."', `imageOther` = '".$imageOther."', `name` = '".$name."', `url` = '".$url."', `about` = '".$about."', `status` = '1' WHERE `id` = '".$id."'");
								$mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` - 1) WHERE `id` = '".$category['category']."'");
								$mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` + 1) WHERE `id` = '".$cat."'");
								echo '<div class="main">';
								echo 'Площадка успешно отредактирована.<br/>';
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/platforms">К площадкам</a><br/>';
								echo '</div>';
							}
			}
		break;
		case 'deletePlatform':
		$platformIsset = $mysqli->query("SELECT `id`,`name` FROM `".$prefix."sait` WHERE `id` = '".$id."' AND `uid` = '".$user_data['id']."' LIMIT 1");
		if($platformIsset->num_rows > 0)
			{
				$platform = $platformIsset->fetch_assoc();
				echo '<div class="title2">Удаление площадки <strong>'.$platform['name'].'</strong></div>';
				if(!isset($_GET['ok']))
					{
						echo '<div class="main">';
						echo 'Вы действительно хотите удалить площадку <strong>'.$platform['name'].'</strong>?<br/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/deletePlatform/'.$id.'/ok">Да</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/platforms">Нет</a><br/>';
						echo '</div>';
					}
				else
					{
						$deleteBrowsers = $mysqli->query("DELETE FROM `".$prefix."browsers` WHERE `sid` = '".$id."'");
						$deleteComplaint = $mysqli->query("DELETE FROM `".$prefix."complaint` WHERE `sid` = '".$id."'");
						$deleteCompression = $mysqli->query("DELETE FROM `".$prefix."compression` WHERE `sid` = '".$id."'");
						$deleteCountry = $mysqli->query("DELETE FROM `".$prefix."country` WHERE `sid` = '".$id."'");
						$deleteHours = $mysqli->query("DELETE FROM `".$prefix."hours` WHERE `sid` = '".$id."'");
						$deleteDays = $mysqli->query("DELETE FROM `".$prefix."days` WHERE `sid` = '".$id."'");
						$deleteMonth = $mysqli->query("DELETE FROM `".$prefix."month` WHERE `sid` = '".$id."'");
						$deleteOperators = $mysqli->query("DELETE FROM `".$prefix."operators` WHERE `sid` = '".$id."'");
						$deleteOnline = $mysqli->query("DELETE FROM `".$prefix."saitsOnline` WHERE `sid` = '".$id."'");
						$deleteGo = $mysqli->query("DELETE FROM `".$prefix."go` WHERE `sid` = '".$id."'");
						$deleteShows = $mysqli->query("DELETE FROM `".$prefix."shows` WHERE `sid` = '".$id."'");
						$deletePlatform = $mysqli->query("DELETE FROM `".$prefix."sait` WHERE `id` = '".$id."'");
						$updateUser = $mysqli->query("UPDATE `".$prefix."users` SET `platformsCount` = (`platformsCount` - 1) WHERE `id` = '".$user_data['id']."'");
						$updateCat = $mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` - 1) WHERE `id` = '".$platform['category']."'");
						echo '<div class="main">';
						echo 'Площадка удалена.<br/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office">В кабинет</a><br/>';
						echo '</div>';
					}
			}
		else
			{
				echo '<div class="error">';
				echo 'У вас нет такой площадки.<br/>';
				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/platforms">К площадкам</a><br/>';
				echo '</div>';
			}
		break;
		case 'code':
			echo '<div class="title2">HTML-код , для установки на сайте</div>';
			if(!isset($_GET['id']))
				{
					echo '<div class="main">';
					echo 'Код счётчика для главной страницы:<br/>';
echo '<strong>'.htmlspecialchars('<a').' href="http://uzlog.top/go/<font color=red>ID_ПЛОЩАДКИ</font>">'.htmlspecialchars('<img').' src="http://uzlog.top/image/<font color=red>ID_ПЛОЩАДКИ</font>" alt="'.$set['copyText'].'"/>'.htmlspecialchars('</a><br/>').'<br/></strong>';
					echo '<hr/>';
					echo 'Код счётчика для остальных страниц:<br/>';
echo '<strong>'.htmlspecialchars('<a').' href="http://uzlog.top/go/<font color=red>ID_ПЛОЩАДКИ</font>">'.htmlspecialchars('<img').' src="http://uzlog.top/imageOther/<font color=red>ID_ПЛОЩАДКИ</font>" alt="'.$set['copyText'].'"/>'.htmlspecialchars('</a><br/>').'<br/></strong>';
					echo '<hr/>';
					echo '<small>Где <strong>ID_ПЛОЩАДКИ</strong> - ID площадки , счётчик которой вы хотите поставить , ID - показывается рядом с площадкой!</small><br/>';
					echo '</div>';
				}
			else
				{
					$id = htmlspecialchars($_GET['id']);
					echo '<div class="main">';
					echo 'Код счётчика для главной страницы (ID площадки: '.$id.'):<br/>';
echo '<strong>'.htmlspecialchars('<a').' href="http://uzlog.top/go/'.$id.'">'.htmlspecialchars('<img').' src="http://uzlog.top/image/'.$id.'" alt="'.$set['copyText'].'"/>'.htmlspecialchars('</a><br/>').'<br/></strong>';
echo '<input type="text" class="form" name="" maxlength="200" class="do_button" value="'.htmlspecialchars('<a href="http://uzlog.top/go/'.$id.'"><img src="http://uzlog.top/image/'.$id.'" alt="'.$set['copyText'].'"/>').''.htmlspecialchars('</a><br/>').'"/><br/>';
					echo '<hr/>';
					echo 'Код счётчика для остальных страниц (ID площадки: '.$id.'):<br/>';
echo '<strong>'.htmlspecialchars('<a').' href="http://uzlog.top/go/'.$id.'">'.htmlspecialchars('<img').' src="http://uzlog.top/imageOther/'.$id.'" alt="'.$set['copyText'].'"/>'.htmlspecialchars('</a><br/>').'<br/></strong>';
echo '<input type="text" class="form" name="" maxlength="200" class="do_button" value="'.htmlspecialchars('<a href="http://uzlog.top/go/'.$id.'"><img src="http://uzlog.top/imageOther/'.$id.'" alt="'.$set['copyText'].'"/>').''.htmlspecialchars('</a><br/>').'"/><br/>';
					echo '<hr/>';
					echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office">В кабинет</a><br/>';
					echo '</div>';
				}
		break;
		case 'settings':
		echo '<div class="title2">Мои настройки</div>';
		if(!isset($_POST['ok']))
			{
				echo '<div class="main">';
				echo '<form action="/m/office/settings" method="post" name="form">';
				echo 'Площадок на страницу в кабинете (max.99):<br/>';
				echo '<input type="text" class="form" name="pagePlatforms" maxlength="2" class="do_button" value="'.$user_data['pagePlatforms'].'"/><br/>';
				echo 'Элементов на страницу в статистике (max.99):<br/>';
				echo '<input type="text" class="form" name="pages" maxlength="2" class="do_button" value="'.$user_data['pages'].'"/><br/>';
				echo 'Сайтов на страницу в категории (max.99):<br/>';
				echo '<input type="text" class="form" name="pageSait" maxlength="2" class="do_button" value="'.$user_data['pageSait'].'"/><br/>';
				echo 'Сайтов на страницу Топ-100 (Главная стр.) (max.99):<br/>';
				echo '<input type="text" class="form" name="pageTop" maxlength="2" class="do_button" value="'.$user_data['pageTop'].'"/><br/>';
				echo 'Новостей на страницу (max.99):<br/>';
				echo '<input type="text" class="form" name="pageNews" maxlength="2" class="do_button" value="'.$user_data['pageNews'].'"/><br/>';
				echo 'Комментариев к новостям на страницу (max.99):<br/>';
				echo '<input type="text" class="form" name="pageNewsc" maxlength="2" class="do_button" value="'.$user_data['pageNewsc'].'"/><br/>';
				echo 'Тема сайта:<br />';
				echo '<select name="style">';
				$dir = opendir('Design/themes');
				while ($styles = readdir($dir))
					{
						if (is_dir('Design/themes/'.$styles) AND $styles != '.' AND $styles != '..')
							{
								$style = ($styles == $user_data['style']) ? 'selected' : '';
								echo '<option '.$style.' value="'.$styles.'">'.$styles.'</option><br/>';
							}
					}
				echo '</select><br/>';
				echo '<input name="ok" type="submit" class="button" value="Сохранить" /></form></div>';
				echo '</div>';
			}
		else
			{
				$pagePlatforms = abs(intval($_POST['pagePlatforms']));
				$pages = abs(intval($_POST['pages']));
				$pageSait = abs(intval($_POST['pageSait']));
				$pageTop = abs(intval($_POST['pageTop']));
				$pageNews = abs(intval($_POST['pageNews']));
				$pageNewsc = abs(intval($_POST['pageNewsc']));
				$style = filter($_POST['style']);
				$error = '';
				if(empty($pagePlatforms) or empty($pages) or empty($pageSait) or empty($pageTop) or empty($pageNews) or empty($pageNewsc) or empty($style))
					{
						$error .= 'Одно из полей не заполнено.<br/>';
					}
				if(mb_strlen($pages) > 2)
					{
						$error .= 'Поле "Элементов на страницу в статистике" содержит больше 2 символов.<br/>';
					}
				if(mb_strlen($pageSait) > 2)
					{
						$error .= 'Поле "Сайтов на страницу в категории" содержит больше 2 символов.<br/>';
					}
				if(mb_strlen($pagePlatforms) > 2)
					{
						$error .= 'Поле "Площадок на страницу" содержит больше 2 символов.<br/>';
					}
				if(mb_strlen($pageTop) > 2)
					{
						$error .= 'Поле "Сайтов на страницу Топ-100" содержит больше 2 символов.<br/>';
					}
				if(mb_strlen($pageNews) > 2)
					{
						$error .= 'Поле "Новостей на страницу" содержит больше 2 символов.<br/>';
					}
				if(mb_strlen($pageNewsc) > 2)
					{
						$error .= 'Поле "Комментариев к новостям на страницу" содержит больше 2 символов.<br/>';
					}
				if(!empty($error))
					{
						echo '<div class="error">';
						echo 'В результате заполнения полей , выявились ошибки:<br/>';
						echo $error;
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/settings">Назад</a><br/>';
						echo '</div>';
					}
				else
					{
						$update = $mysqli->query("UPDATE `".$prefix."users` SET `pagePlatforms` = '".$pagePlatforms."', `pages` = '".$pages."', `pageSait` = '".$pageSait."', `pageTop` = '".$pageTop."', `pageNews` = '".$pageNews."', `pageNewsc` = '".$pageNewsc."', `style` = '".$style."' WHERE `id` = '".$user_data['id']."'");
						echo '<div class="main">';
						echo 'Настройки сохранены.<br/>';
						echo '</div>';
					}
			}
		break;
		case 'changePassword':
		if(!isset($_POST['ok']))
			{
				echo '<div class="title2">Изменение пароля</div>';
				echo '<div class="main">';
				echo '<form action="/m/office/changePassword" method="post" name="form">';
				echo 'Старый пароль:<br/>';
				echo '<input type="password" class="form" name="old" maxlength="20" class="do_button" value=""/><br/>';
				echo 'Новый пароль:<br/>';
				echo '<input type="password" class="form" name="new" maxlength="20" class="do_button" value=""/><br/>';
				echo 'Повторите новый пароль:<br/>';
				echo '<input type="password" class="form" name="new2" maxlength="20" class="do_button" value=""/><br/>';
				echo '<input name="ok" type="submit" class="button" value="Изменить" /></form></div>';
				echo '</div>';
			}
		else
			{
				$oldP = filter($_POST['old']);
				$newP = filter($_POST['new']);
				$newP2 = filter($_POST['new2']);
				$error = '';
				if(md5($oldP) != $user_data['password'])
					{
						$error .= 'Старый пароль введен неверно.<br/>';
					}
				if($newP != $newP2)
					{
						$error .= 'Введенные пароли не совпадают.<br/>';
					}
				if(!empty($error))
					{
						echo '<div class="error">';
						echo 'В результате заполнения полей , выявились ошибки:<br/>';
						echo $error;
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/office/changePassword">Назад</a><br/>';
						echo '</div>';
					}
				else
					{
						$subject = "Новый пароль на сайте ".$_SERVER['HTTP_HOST'];
						$body = "Вы сменили пароль от аккаунта на сайте ".$_SERVER['HTTP_HOST'].".\n";
						$body .= "Новый пароль: ".$newP."\n";
						$body .= "Спасибо за использование нашего сервиса.\n";
						$headers = "From: ".$set['mail']." \n";
						$headers .= "Content-Type: text/plain; charset=utf-8\n";
						mail($user_data['mail'], $subject, $body, $headers);
						$updateP = $mysqli->query("UPDATE `".$prefix."users` SET `password` = '".md5($newP)."' WHERE `id` = '".$user_data['id']."'");
						echo '<div class="main">';
						echo 'Пароль успешно изменен.<br/>';
						echo 'Новый пароль: <strong>'.$newP.'</strong><br/>';
						echo 'Вам необходимо перезайти на сайт, с новым паролем.<br/>';
						echo '<small>Пароль выслан на ваш E-Mail.</small><br/>';
						echo '</div>';
					}
			}
		break;
	}
?>
