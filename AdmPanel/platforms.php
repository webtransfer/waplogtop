<?php
$title = 'Панель управления сайтом - Управление площадками';
require_once('../Sys/head.php');
reg();
level(2);
switch($act)
	{
		default:
		echo '<div class="title2">Управление площадками</div>';
		echo '<div class="main">';
		echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/view"><strong>Список площадок</strong></a><br/>';
		echo '
		<form action="platforms/view/" method="GET">
		Текст поиска (max.50):<br />
		<input type="text" class="form" name="text" maxlength="50" /><br />
		Искать по:<br />
		<select name="type">
		<option value="id">ID</option>
		<option value="url">URL</option>
		<option value="name">Названию</option>
		<option value="about">Описанию</option>
		</select><br />
		Сортировать по:<br />
		<select name="sort">
		<option value="id">ID</option>
		<option value="hosts">Хостам</option>
		<option value="hits">Хитам</option>
		</select><br />
		<input name="search" type="submit" class="button" class="go" value="Искать" />
		</form></div><br/>
		';
		break;
		case 'view':
		if(isset($_GET['search']))
			{
				$text = filter($_GET['text']);
				$order = filter($_GET['type']);
				$sort = filter($_GET['sort']);
				$error = '';
				if(empty($text) OR empty($order) OR empty($sort))
					{
						$error .= 'Не заполнены поля.<br/>';
					}
				if(mb_strlen($text) > 50)
					{
						$error .= 'Поле "Текст поиска" больше 50 символов.<br/>';
					}
				if(!empty($error))
					{
						echo '<div class="error">';
						echo 'В результате заполнения полей , выявились ошибки:<br/>';
						echo $error;
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms">Назад</a><br/>';
						echo '</div>';
					}
				else
					{
						$count = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `".$order."` LIKE '%".$text."%'")->num_rows;
						echo $count;
						if($count > 0)
							{
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
								$saits = $mysqli->query("SELECT `id`,`name`,`hosts`,`hits`,`about`,`ban` FROM `".$prefix."sait` WHERE `".$order."` LIKE '%".$text."%' ORDER BY `".$sort."` DESC LIMIT ".$start.",".$pageSait."");
								while($row = $saits->fetch_assoc())
									{
										$start++;
										$ban = ($row['ban'] == 0) ? '<a href = "http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/ban/'.$row['id'].'">Блокировать</a>' : '<a href = "http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/ban/'.$row['id'].'">Разблокировать</a>';
										echo '<div class="title">'.$start.'.<a href="http://'.$_SERVER['HTTP_HOST'].'/out/'.$row['id'].'"><strong>'.$row['name'].'</strong></a></div>';
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
										echo 'Хостов: <strong>'.$row['hosts'].'</strong> | Хитов: <strong>'.$row['hits'].'</strong><br/>';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/editSait/'.$row['id'].'"><strong>Редактировать</strong></a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/deleteSait/'.$row['id'].'"><strong>Удалить</strong></a> | '.$ban.'<br/>';
										echo '</div>';
									}
								if($count > $pageSait)
									{
										navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/view/'.$order.'/'.$sort.'/'.$text.'/search/');
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
		else
			{
				$count = $mysqli->query("SELECT `id` FROM `".$prefix."sait`")->num_rows;
				if($count > 0)
					{
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
						$saits = $mysqli->query("SELECT `id`,`name`,`hosts`,`hits`,`about`,`ban` FROM `".$prefix."sait` ORDER BY `hosts` DESC LIMIT ".$start.",".$pageSait."");
						while($row = $saits->fetch_assoc())
									{
										$start++;
										$ban = ($row['ban'] == 0) ? '<a href = "http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/ban/'.$row['id'].'">Блокировать</a>' : '<a href = "http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/ban/'.$row['id'].'">Разблокировать</a>';
										echo '<div class="title">'.$start.'.<a href="http://'.$_SERVER['HTTP_HOST'].'/out/'.$row['id'].'"><strong>'.$row['name'].'</strong></a></div>';
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
										echo 'Хостов: <strong>'.$row['hosts'].'</strong> | Хитов: <strong>'.$row['hits'].'</strong><br/>';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/editSait/'.$row['id'].'"><strong>Редактировать</strong></a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/deleteSait/'.$row['id'].'"><strong>Удалить</strong></a> | '.$ban.'<br/>';
										echo '</div>';
									}
						if($count > $pageSait)
									{
										navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/view/');
									}
					}
				else
					{
						echo '<div class="main">';
						echo 'Площадок нет! <br/>';
						echo '</div>';
					}
			}
		break;
		case 'editSait':
		echo '<div class="title2">Изменение площадки</div>';
		if(!isset($_POST['ok']))
			{
				$platform = $mysqli->query("SELECT `name`,`url`,`about`,`category`,`image`,`imageOther`,`regdate` FROM `".$prefix."sait` WHERE `id` = '".$id."' LIMIT 1");
				if($platform->num_rows > 0)
					{
						$userPlatform = $platform->fetch_assoc();
						echo '<div class="main">';
						echo '
						<form action="" method="post">
						Дата добавления площадки : <br/><b>'.date("j-m-Y H:i:s",$userPlatform['regdate']).'</b><br/>
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
						$imageBig = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `type` = 'big'  ORDER BY `id`");
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
						echo '<small><strong>Для остальных страниц сайта</small><br/>';
						$imageSmall = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `type` = 'small'  ORDER BY `id`");
						if($imageSmall->num_rows > 0)
							{
								while($imSmall = $imageSmall->fetch_array())
									{
										$checked = ($userPlatform['imageOther'] == $imSmall['name']) ? ' checked="checked"' : '';
										echo '<input type="radio" '.$checked.' name="imageOther" value="'.$imSmall['name'].'" />';
										echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/Counters/'.$imSmall['name'].'.png" alt="" /><br/>';
										while($imSmall = $imageSmall->fetch_array())
									{
										$checked = ($userPlatform['imageOther'] == $imSmall['name']) ? ' checked="checked"' : '';
										echo '<input type="radio" '.$checked.' name="imageOther" value="'.$imSmall['name'].'" />';
										echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/Counters/'.$imSmall['name'].'.png" alt="" /><br/>';
									}
									}
							}
						else
							{
								echo 'Маленьких счетчиков нет.';
							}
						echo '<input name="ok" type="submit" class="button" value="Изменить" /></form></div>';
					}
				else
					{
						echo '<div class="error">';
						echo 'Такой площадки нет.<br/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/editSait/'.$id.'">Назад</a><br/>';
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
						$platformIsset = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `id` = '".$id."' LIMIT 1")->num_rows;
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
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/editSait/'.$id.'">Назад</a><br/>';
								echo '</div>';
							}
						else
							{
								$category = $mysqli->query("SELECT `category` FROM `".$prefix."sait` WHERE `id` = '".$id."' LIMIT 1")->fetch_assoc();
								$mysqli->query("UPDATE `".$prefix."sait` SET `category` = '".$cat."', `image` = '".$image."', `imageOther` = '".$imageOther."', `name` = '".$name."', `url` = '".$url."', `pr` = '".$pr."', `cy` = '".$cy."', `about` = '".$about."', `status` = '1' WHERE `id` = '".$id."'");
								$mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` - 1) WHERE `id` = '".$category['category']."'");
								$mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` + 1) WHERE `id` = '".$cat."'");
								echo '<div class="main">';
								echo 'Площадка успешно отредактирована.<br/>';
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/view">Назад</a><br/>';
								echo '</div>';
							}
			}
		break;
		case 'deleteSait':
		$platformIsset = $mysqli->query("SELECT `id`,`name`,`category`,`uid` FROM `".$prefix."sait` WHERE `id` = '".$id."' LIMIT 1");
		if($platformIsset->num_rows > 0)
			{
				$platform = $platformIsset->fetch_assoc();
				echo '<div class="title2">Удаление площадки <strong>'.$platform['name'].'</strong></div>';
				if(!isset($_GET['ok']))
					{
						echo '<div class="main">';
						echo 'Вы действительно хотите удалить площадку <strong>'.$platform['name'].'</strong>?<br/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/deleteSait/'.$id.'/ok">Да</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/view">Нет</a><br/>';
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
						$updateCat = $mysqli->query("UPDATE `".$prefix."cat` SET `count` = (`count` - 1) WHERE `id` = '".$platform['category']."'");
						$updateUser = $mysqli->query("UPDATE `".$prefix."users` SET `platformsCount` = (`platformsCount` - 1) WHERE `id` = '".$platform['uid']."'");
						echo '<div class="main">';
						echo 'Площадка удалена.<br/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/view">К площадкам</a><br/>';
						echo '</div>';
					}
			}
		else
			{
				echo '<div class="error">';
				echo 'Нет такой площадки.<br/>';
				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/view">К площадкам</a><br/>';
				echo '</div>';
			}
		break;
		case 'ban':
			if($id)
				{
					$checking = $mysqli->query("SELECT `id`,`ban` FROM `".$prefix."sait` WHERE `id` = '".$id."'");
						if($checking->num_rows > 0)
							{
								$check = $checking->fetch_assoc();
								if($check['ban'] == 0)
									{
										if(!isset($_POST['ok']))
											{
												echo '
												<div class="main">
												<form action="" method="post">
												Причина бана(max.50):<br/>
												<input type="text" class="form" name="reason" maxlength="30" value=""/><br/>
												<input type="submit" class="button" name="ok" value="Выдать БАН"/>
												</form></div>';
											}
										else
											{
												$reason = filter($_POST['reason']);
												$error = '';
												if(empty($reason))
													{
														$error .= 'Не заполнено поле.<br/>';
													}
												if(mb_strlen($reason) > 50)
													{
														$error .= 'Поле "Причина" содержит больше 50 символов.<br/>';
													}
												if(!empty($error))
													{
														echo '<div class="error">';
														echo 'В результате заполнения полей , выявились ошибки:<br/>';
														echo $error;
														echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms/ban/'.$id.'">Назад</a><br/>';
														echo '</div>';
													}
												else
													{
														$mysqli->query("UPDATE `".$prefix."sait` SET `ban` = '1', `ban_reason` = '".$reason."', `ban_who` = '".$user_data['login']."' WHERE `id` = '".$id."'");
														echo '<div class="main">';
														echo 'Сайт успешно забанен.<br/>';
														echo '</div>';
													}
											}
									}
									else
									{
										$mysqli->query("UPDATE `".$prefix."sait` SET `ban` = '0', `ban_reason` = '', `ban_who` = '' WHERE `id` = '".$id."'");
										echo '<div class="main">';
										echo 'Сайт успешно разбанен.<br/>';
										echo '</div>';
									}
							}
						else
							{
								echo '<div class="error">';
								echo 'Данного сайта нет в базе.<br/>';
								echo '</div>';
							}
				}
		break;
	}
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/platforms">К управлению площадками</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>