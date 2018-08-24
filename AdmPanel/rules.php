<?php
$title = 'Панель управления сайтом - Управление правилами';
require_once('../Sys/head.php');
reg();
level(2);
if(!isset($_POST['ok']))
	{
		$row = $mysqli->query("SELECT `text` FROM `".$prefix."rules`")->fetch_assoc();
		echo '<div class="title2">Правила сайта</div>';
		echo '
		<div class="main">
		<form action="" method="post">
		Текст правил (max. 3000):<br/>
		<textarea class="form" name="text" cols="59" rows="30">'.$row['text'].'</textarea><br/>
		<input name="ok" type="submit" class="button" value="Отправить" />
		</form>
		<small>
		[b]текст[/b] - <span style="font-weight: bold">текст</span><br/>
		[i]текст[/i] - <span style="font-style:italic">текст</span><br/>
		[red]текст[/red] - <span style="color:red">текст</span><br/>
		[green]текст[/green] - <span style="color:green">текст</span><br/>
		[blue]текст[/blue] - <span style="color:blue">текст</span><br/>
		</div>
		';
	}
else
	{
		$text = filter($_POST['text']);
		$error = '';
		if(empty($text))
			{
				$error .= 'Правила пустые.<br/>';
			}
		if(mb_strlen($text) > 3000)
			{
				$error .= 'Поле "Текст правил" содержит больше 3000 символов.<br/>';
			}
		if(!empty($error))
			{
				echo '<div class="error">';
				echo 'В результате заполнения полей , выявились ошибки:<br/>';
				echo $error;
				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/rules">Повторить<a/><br/>';
				echo '</div>';	
			}
		else
			{
				$mysqli->query("UPDATE `".$prefix."rules` SET `text` = '".$text."'");
				echo '<div class="main">';
				echo 'Правила сохранены.<br/>';
				echo '</div>';
			}
	}
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/rules">К управлению правилами</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>