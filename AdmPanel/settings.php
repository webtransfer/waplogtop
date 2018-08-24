<?php 
$title = 'Панель управления сайтом - Настройки рейтинга';
require_once('../Sys/head.php');
reg();
level(2);
echo '<div class="title">Настройки сайта</div>';
if(!isset($_POST['ok']))
	{
		echo '<div class="main">';
		echo '<form action="" method="post" name="form">';
		echo 'Название Топ-Рейтинга (max.30):<br/>';
		echo '<input type="text" class="form" name="topName" maxlength="30" class="do_button" value="'.$set['topName'].'"/><br/>';
		echo 'Максимальное количество площадок у пользователя:<br/>';
		echo '<input type="text" class="form" name="maxPlatforms" maxlength="2" class="do_button" value="'.$set['maxPlatforms'].'"/><br/>';
		echo 'Площадок на страницу в кабинете:<br/>';
		echo '<input type="text" class="form" name="pagePlatforms" maxlength="2" class="do_button" value="'.$set['pagePlatforms'].'"/><br/>';
		echo 'Сайтов на страницу в категории:<br/>';
		echo '<input type="text" class="form" name="pageSait" maxlength="2" class="do_button" value="'.$set['pageSait'].'"/><br/>';
		echo 'Сайтов на страницу Топ100 (Главная Топ-Рейтинга):<br/>';
		echo '<input type="text" class="form" name="pageTop" maxlength="2" class="do_button" value="'.$set['pageTop'].'"/><br/>';
		echo 'Элементов на страницу в статистике сайта:<br/>';
		echo '<input type="text" class="form" name="pages" maxlength="2" class="do_button" value="'.$set['pages'].'"/><br/>';
		echo 'Сайтов на страницу модерации:<br/>';
		echo '<input type="text" class="form" name="pageModeracia" maxlength="2" class="do_button" value="'.$set['pageModeracia'].'"/><br/>';
		echo 'Новостей на страницу:<br/>';
		echo '<input type="text" class="form" name="pageNews" maxlength="2" class="do_button" value="'.$set['pageNews'].'"/><br/>';
		echo 'Комментариев к новости на страницу:<br/>';
		echo '<input type="text" class="form" name="pageNewsc" maxlength="2" class="do_button" value="'.$set['pageNewsc'].'"/><br/>';
		echo 'E-Mail адрес топ-рейтинга , с него отправляются системные сообщения(также отображается в контактах):<br/>';
		echo '<input type="text" class="form" name="mail" maxlength="50" class="do_button" value="'.$set['mail'].'"/><br/>';
		echo 'Номер ICQ (отображается в контактах):<br/>';
		echo '<input type="text" class="form" name="icq" maxlength="9" class="do_button" value="'.$set['icq'].'"/><br/>';
		echo 'Время антифлуда (Время в течении которого пользователь не может писать сообщения после предыдущего) :<br/>';
		echo '<input type="text" class="form" name="antiflud" maxlength="2" class="do_button" value="'.$set['antifludTime'].'"/><br/>';
		echo 'Регистрация включена?:<br/>';
		echo '<select name="powerRegistration">';
		if ($set['powerRegistration'] == 0)
			{
				echo '<option value="0">Нет</option><br/>';
				echo '<option value="1">Да</option><br/>';
			}
		else
			{
				echo '<option value="1">Да</option><br/>';
				echo '<option value="0">Нет</option><br/>';
			}
		echo '</select><br/>';
		echo 'Модерация включена?:<br/>';
		echo '<select name="powerModeracia">';
		if ($set['moderacia'] == 0)
			{
				echo '<option value="0">Да</option><br/>';
				echo '<option value="1">Нет</option><br/>';
			}
		else
			{
				echo '<option value="1">Нет</option><br/>';
				echo '<option value="0">Да</option><br/>';
			}
		echo '</select><br/>';
		echo 'Тема сайта:<br />';
		echo '<select name="style">';
		$dir = opendir('../Design/themes');
		while ($styles = readdir($dir))
			{
				if (is_dir('../Design/themes/'.$styles) AND $styles != '.' AND $styles != '..')
					{
						$style = ($styles == $set['style']) ? 'selected' : '';
						echo '<option '.$style.' value="'.$styles.'">'.$styles.'</option><br/>';
					}
			}
				echo '</select><br/>';
		echo 'Текст копирайта (внизу сайта):<br/>';
		echo '<input type="text" class="form" name="copyText" maxlength="50" class="do_button" value="'.$set['copyText'].'"/><br/>';
		echo 'Ссылка копирайта (например: '.$_SERVER['HTTP_HOST'].'):<br/>';
		echo '<input type="text" class="form" name="copyLink" maxlength="50" class="do_button" value="'.$set['copyLink'].'"/><br/>';
		echo '<input name="ok" type="submit" class="button" value="Сохранить" /></form></div>';
	}	
else
	{
		$topName = filter($_POST['topName']);
		$maxPlatforms = abs(intval($_POST['maxPlatforms']));
		$pagePlatforms = abs(intval($_POST['pagePlatforms']));
		$pageSait = abs(intval($_POST['pageSait']));
		$pageTop = abs(intval($_POST['pageTop']));
		$pages = abs(intval($_POST['pages']));
		$pageModeracia = abs(intval($_POST['pageModeracia']));
		$pageNews = abs(intval($_POST['pageNews']));
		$pageNewsc = abs(intval($_POST['pageNewsc']));
		$powerRegistration = abs(intval($_POST['powerRegistration']));
		$mail = filter($_POST['mail']);
		$antifludTime = abs(intval($_POST['antiflud']));
		$moderacia = abs(intval($_POST['powerModeracia']));
		$icq = abs(intval($_POST['icq']));
		$style = filter($_POST['style']);
		$copyText = filter($_POST['copyText']);
		$copyLink = filter($_POST['copyLink']);
		$error = '';
		if(empty($maxPlatforms) or empty($pagePlatforms) or empty($pageSait) or empty($pageTop) or empty($pages) or empty($pageModeracia) or empty($pageNews) or empty($pageNewsc) or empty($mail) or empty($style))
			{
				$error .= 'Одно из полей не заполнено.<br/>';
			}
		if (!empty($mail) and !preg_match('#^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+(\.([A-Za-z0-9])+)+$#', $mail))
			{
				$error .= 'Вы ввели неверный адрес e-mail, необходим формат name@site.domen.<br/>';
			}
		if(!empty($error))
			{
				echo '<div class="error">';
				echo 'В результате заполнения полей , выявились ошибки:<br/>';
				echo $error;
				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/settings">Назад</a><br/>';
				echo '</div>';
			}
		else
			{
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$mail."' WHERE `name` = 'mail'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$topName."' WHERE `name` = 'topName'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$maxPlatforms."' WHERE `name` = 'maxPlatforms'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$pagePlatforms."' WHERE `name` = 'pagePlatforms'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$pageSait."' WHERE `name` = 'pageSait'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$pageTop."' WHERE `name` = 'pageTop'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$pages."' WHERE `name` = 'pages'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$pageModeracia."' WHERE `name` = 'pageModeracia'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$pageNews."' WHERE `name` = 'pageNews'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$pageNewsc."' WHERE `name` = 'pageNewsc'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$powerRegistration."' WHERE `name` = 'powerRegistration'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$antifludTime."' WHERE `name` = 'antifludTime'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$moderacia."' WHERE `name` = 'moderacia'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$icq."' WHERE `name` = 'icq'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$style."' WHERE `name` = 'style'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$copyText."' WHERE `name` = 'copyText'");
				$mysqli->query("UPDATE `".$prefix."settings` SET `value` = '".$copyLink."' WHERE `name` = 'copyLink'");
				echo '<div class="main">';
				echo 'Настройки успешно изменены.<br/>';
				echo '</div>';
			}
	}	
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/settings">К управлению настройками</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>