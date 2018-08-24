<?php
$title = 'Новости';
require_once ('Sys/head.php');
switch($act)
	{
		default:
		echo '<div class="title2">Новости сайта</div>';
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
				$news = $mysqli->query("SELECT `id`,`text`,`name`,`time`,`author`,`comments` FROM `".$prefix."news` ORDER BY `time` DESC LIMIT ".$start.",".$pageNews."");
				while($row = $news->fetch_assoc()) 
					{
						$start++;
						echo '<div class="d">'.$start.'.<strong>'.$row['name'].' ('.data($row['time']).')</strong></div>';
						echo '<div class="main">';
						echo $row['text'].'<br/>';
						echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/news/comments/'.$row['id'].'">Комментарии</a>('.$row['comments'].')<br/>';
						echo '</div>';
				}
				if($count > $pageNews)
					{
						echo navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/m/news/');
					}
			}
		else
			{
				echo '<div class="main">';
				echo 'Новостей нет.<br/>';
				echo '</div>';
			}
		break;
		case 'comments':
					echo '<div class="title2">Комментарии к новости</div>';
					$issetNews = $mysqli->query("SELECT `id` FROM `".$prefix."news` WHERE `id` = '".$id."'")->num_rows;
					if($issetNews > 0)
						{
							if(isset($_POST['ok']))
								{
									$name = isset($user_data) ? $user_data['login'] : 'Гость';
									$comment = filter($_POST['comment']);
									$kod = filter($_POST['kod']);
									$error = '';
									if(empty($comment))
										{
											$error.= 'Не введен текст комментария.<br/>';
										}
									if(empty($kod))
										{
											$error.='Не введен код с картинки.<br/>';
										}
									if($user_data)
										{
											if($user_data['antiflud'] + $set['antifludTime'] > $time)
												{
													$error .= 'Вы писали сообщение меньше чем '.$set['antifludTime'].' секунд назад.<br/>';
												}
										}
									else
										{
											if($set['guestAntiflud'] + $set['antifludTime'] > $time)
												{
													$error .= 'Вы писали сообщение меньше чем '.$set['antifludTime'].' секунд назад.<br/>';
												}									
										}
									if($_SESSION['code'] != $kod)
										{
											$error .= 'Код с картинки введён не верно.<br/>';
										}
									if(mb_strlen($comment) > 100)
										{
											$error.='Комментарий содержит больше 100 символов.<br/>';
										}
									if(!empty($error))
										{
											echo '<div class="error">';
											echo 'В результате заполнения полей , выявились ошибки:<br/>';
											echo $error;
											echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/news/comments/'.$id.'">Назад</a><br/>';
											echo '</div>';
										}
									else
										{
											unset($_SESSION['code']);
											$mysqli->query("INSERT INTO `".$prefix."news_comments` VALUES ('', '".$id."', '".$name."', '".$comment."', '".$time."')");
											$mysqli->query("UPDATE `".$prefix."news` SET `comments` = (`comments` + 1) WHERE `id` = '".$id."'");
											if($user_data)
												{
													$updateAntiflud = $mysqli->query("UPDATE `".$prefix."users` SET `antiflud` = '".$time."' WHERE `id` = '".$user_data['id']."'");
												}
											else
												{
													$updateAntifludGuest = $mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$time."' WHERE `name` = 'guestAntiflud'");
												}										
											echo '<div class="main">';
											echo 'Комментарий добавлен.<br/>';
											echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/news/comments/'.$id.'">К комментариям</a></br>';
											echo '</div>';
										}
								}
							else
								{
									$count = $mysqli->query("SELECT * FROM `".$prefix."news_comments` WHERE `nid` = '".$id."'")->num_rows;
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
												$comments = $mysqli->query("SELECT `name`,`time`,`text` FROM `".$prefix."news_comments` WHERE `nid` = '".$id."' ORDER BY `time` ASC LIMIT ".$start.",".$pageNc."");
												while($row = $comments->fetch_assoc()) 
													{
														$start++;
														echo '<div class="title">'.$start.'.<strong>'.$row['name'].' ('.data($row['time']).')</strong></div>';
														echo '<div class="main">';
														echo $row['text'];
														echo '</div>';
													}
												if($count > $pageNc)
													{
														echo navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/m/news/comments/'.$id.'/');
													}
													echo '<hr/>';
													echo '<div class="title">Добавление комментария</div>';
													echo '<div class="main">';
													echo '
													<form action="" method="post">
													Комментарий (max. 100):<br/>
													<textarea class="form" name="comment" cols="38" rows="8"></textarea><br/>
													';
													echo 'Код с картинки: <br/><img src="http://'.$_SERVER['HTTP_HOST'].'/captcha_'.rand(1111111111,999999999).'" alt="Включите картинки" /><br/>';
													echo '<input name="kod" type="text" class="form" value="" /><br/>';				
													echo '<input name="ok" type="submit" class="button" value="Добавить" />
													</form>
													</div>';
													echo '<hr/>';
										}
									else
										{
											echo '<div class="main">';
											echo 'Комментариев нет.<br/>';
											echo '</div>';
											echo '<hr/>';
											echo '<div class="title">Добавление комментария</div>';
											echo '<div class="main">';
											echo '
											<form action="/m/news/comments/'.$id.'" method="post">
											Комментарий (max. 100):<br/>
											<textarea class="form" name="comment" cols="38" rows="8"></textarea><br/>
											';
											echo 'Код с картинки: <br/><img src="http://'.$_SERVER['HTTP_HOST'].'/captcha_'.rand(1111111111,999999999).'" alt="Включите картинки" /><br/>';
											echo '<input name="kod" type="text" class="form" value="" /><br/>';				
											echo '<input name="ok" type="submit" class="button" value="Добавить" />
											</form>
											</div>';
											echo '<hr/>';
										}
								}
						}
					else
						{
							echo '<div class="error">';
							echo 'Вы ошиблись новостью</br>';
							echo '</div>';
						}
								
							echo '<div class="main">';
							echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/news">К новостям</a></br>';
							echo '</div>';
		break;
	}
?>