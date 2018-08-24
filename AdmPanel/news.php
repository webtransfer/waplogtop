<?php
$title = 'Панель управления сайтом - Управление новостями';
require_once('../Sys/head.php');
reg();
level(2);
switch($act)
	{
			default:
			echo '<div class="title2">Управление новостями</div>';
			echo '<div class="main"><a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/add">Добавить новость</a><br/></div>';
			$count = $mysqli->query("SELECT `id` FROM `".$prefix."news`")->num_rows;
			if($count > 0)
				{
					$total = intval(($count-1)/$pageNews)+1;
					$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
					if(empty($page) OR $page < 0)
						{
							$page = 1;
						}
					if($page > $total)
						{
							$page = $total;
						}
					$past = intval($count/$pageNews);
					$start = $page*$pageNews-$pageNews;
					$news = $mysqli->query("SELECT * FROM `".$prefix."news` ORDER BY `time` DESC LIMIT ".$start.",".$pageNews."");
					while($row = $news->fetch_assoc())
						{
							$start++;
							echo '<div class="title">'.$start.'.<strong>'.$row['name'].' ('.data($row['time']).')</strong></div>';
							echo '<div class="main">';
							echo $row['text'].'<br/>';
							echo '<hr/>';
							echo 'Добавил: '.$row['author'].'<br/>';
							echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/comments/'.$row['id'].'">Управление Комментариями</a>('.$row['comments'].')<br/>';
							echo '<hr/>';
							echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/deleteNews/'.$row['id'].'">Удалить новость</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/editNews/'.$row['id'].'">Изменить новость</a><br/>';
							echo '</div>';
						}
					if($count > $pageSait)
						{
							navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/');
						}
				}
			else
				{
					echo '<div class="main">';
					echo 'Новостей нет.<br/>';
					echo '</div>';
				}
		break;
		case 'add':
		if(!isset($_POST['ok']))
			{
				echo '
				<div class="main">
				<form action="" method="post">
				Название новости (max. 100):<br/>
				<input type="text" class="form" name="name" class="input" maxlength="30" /><br />
				Текст новости (max. 4000):<br/>
				<textarea class="form" name="text" cols="38" rows="8"></textarea><br/>
				<input name="ok" type="submit" class="button" value="Добавить" />
				</form>
				</div>
				';
			}
		else
			{
				$name = filter($_POST['name']);
				$text = filter($_POST['text']);
				$error = '';
				if(empty($name) OR empty($text))
					{
						$error .= 'Не заполнены поля.<br/>';
					}
				if(mb_strlen($name) > 100)
					{
						$error .= 'Поле "Название новости" больше 100 символов.<br/>';
					}
				if(mb_strlen($text) > 4000)
					{
						$error .= 'Поле "Текст новости" больше 4000 символов.<br/>';
					}
				if(!empty($error))
					{
						echo '<div class="error">';
						echo 'В результате заполнения полей , выявились ошибки:<br/>';
						echo $error;
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/add">Назад</a><br/>';
						echo '</div>';
					}
				else
					{
						$mysqli->query("INSERT INTO `".$prefix."news` VALUES ('', '".$name."', '".$text."', '".$time."', '".$user_data['login']."', '0')");
						echo '<div class="main">';
						echo 'Новость успешно добавлена! <br/>';
						echo '</div>';
					}
			}
		break;
		case 'deleteNews':
		if($id)
			{
				$mysqli->query("DELETE FROM `".$prefix."news_comments` WHERE `nid` = '".$id."'");
				$mysqli->query("DELETE FROM `".$prefix."news` WHERE `id` = '".$id."'");
				echo '<div class="main">';
				echo 'Новость успешно удалена.<br/>';
				echo '</div>';
			}
		else
			{
				echo '<div class="error">';
				echo 'Не выбрана новость.<br/>';
				echo '</div>';
			}
		break;
		case 'editNews':
		if($id)
			{
				$isset = $mysqli->query("SELECT `id`,`name`,`text` FROM `".$prefix."news` WHERE `id` = '".$id."'");
				if($isset->num_rows > 0)
					{
						$news = $isset->fetch_assoc();
						echo '<div class="title">Изменение новости</div>';
						if(!isset($_POST['ok']))
							{
								echo '
								<div class="main">
								<form action="" method="post">
								Название новости(max.100):<br/>
								<input type="text" class="form" name="name" maxlength="30" value="'.$news['name'].'"/><br/>
								Текст новости(max.4000):<br/>
								<textarea class="form" name="text" cols="38" rows="8">'.$news['text'].'</textarea><br/>
								<input type="submit" class="button" name="ok" value="Изменить"/>
								</form></div>';
							}
						else
							{
								$name = filter($_POST['name']);
								$text = filter($_POST['text']);
								$error = '';
								if(empty($name) OR empty($text))
									{
										$error .= 'Ошибка!Не заполнены поля!<br/>';
									}
								if(mb_strlen($name) > 100)
									{
										$error .= 'Поле "Название новости" больше 100 символов.<br/>';
									}
								if(mb_strlen($text) > 4000)
									{
										$error .= 'Поле "Текст новости" больше 4000 символов.<br/>';
									}
								if(!empty($error))
									{
										echo '<div class="error">';
										echo 'В результате заполнения полей , выявились ошибки:<br/>';
										echo $error;
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/editNews/'.$id.'">Назад</a><br/>';
										echo '</div>';
									}
								else
									{
										$mysqli->query("UPDATE `".$prefix."news` SET `name` = '".$name."', `text` = '".$text."' WHERE `id` = '".$id."'");
										echo '<div class="main">';
										echo 'Новость успешно изменена<br/>';
										echo '</div>';
									}
							}
					}
				else
					{
						echo '<div class="error">';
						echo 'Данной новости нет.<br/>';
						echo '</div>';
					}
			}
		else
			{
				echo '<div class="error">';
				echo 'Не выбрана новость.<br/>';
				echo '</div>';
			}
		break;
		case 'comments':
		echo '<div class="title2">Управление комментариями</div>';
		$issetNews = $mysqli->query("SELECT `id` FROM `".$prefix."news` WHERE `id` = '".$id."'")->num_rows;
		if($issetNews > 0)
			{
				$count = $mysqli->query("SELECT `id` FROM `".$prefix."news_comments` WHERE `nid` = '".$id."'")->num_rows;
				if($count > 0)
					{
						$total = intval(($count-1)/$pageNc)+1;
						$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
						if(empty($page) OR $page < 0)
							{
								$page = 1;
							}
						if($page > $total)
							{
								$page = $total;
							}
						$past = intval($count/$pageNc);
						$start = $page*$pageNc-$pageNc;
						$comments = $mysqli->query("SELECT * FROM `".$prefix."news_comments` WHERE `nid` = '".$id."' ORDER BY `time` DESC LIMIT ".$start.",".$pageNc."");
						while($row = $comments->fetch_assoc())
							{
								$start++;
								echo '<div class="title2">'.$start.'.<strong>'.$row['name'].' ('.data($row['time']).')</strong></div>';
								echo '<div class="main">';
								echo $row['text'].'<br/>';
								echo '<hr/>';
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/deleteComment/'.$row['id'].'">Удалить</a><br/>';
								echo '</div>';
							}
						echo '<hr/>';
						echo '<div class="main">';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/deleteComments/'.$id.'">Удалить все комментарии</a><br/>';
						echo '</div>';
						if($count > $pageNc)
							{
								navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news/comments/'.$id.'/');
							}
					}
				else
					{
						echo '<div class="main">';
						echo 'Комментариев к данной новости нет.<br/>';
						echo '</div>';
					}
			}
		else
			{
				echo '<div class="error">';
				echo 'Нет такой новости.</br>';
				echo '</div>';
			}
		break;
		case 'deleteComment':
		echo '<div class="title2">Управление комментариями</div>';
		$newsId = $mysqli->query("SELECT `nid` FROM `".$prefix."news_comments` WHERE `id` = '".$id."'")->fetch_assoc();
		$mysqli->query("DELETE FROM `".$prefix."news_comments` WHERE `id` = '".$id."'");
		$mysqli->query("UPDATE `".$prefix."news` SET `comments` = (`comments` - 1) WHERE `id` = '".$newsId['nid']."'");
		echo '<div class="main">';
		echo 'Комментарий успешно удален.<br/>';
		echo '</div>';
		break;
		case 'deleteComments':
		echo '<div class="title2">Управление комментариями</div>';
		$mysqli->query("DELETE FROM `".$prefix."news_comments` WHERE `nid` = '".$id."'");
		$mysqli->query("UPDATE `".$prefix."news` SET `comments` = '0' WHERE `id` = '".$id."'");
		echo '<div class="main">';
		echo 'Все комментарии успешно удалены.<br/>';
		echo '</div>';
		break;
	}
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/news">К управлению новостями</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>
