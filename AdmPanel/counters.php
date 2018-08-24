<?php
$title = 'Панель управления сайтом - Управление счётчиками';
require_once('../Sys/head.php');
require_once('../Sys/upload.php');
reg();
level(2);
switch($act)
	{
		default:
		echo '<div class="title2">Список счётчиков</div>';
		echo '<div class="main">';
		$imageBig = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `type` = 'big'  ORDER BY `id` ");
		if($imageBig->num_rows > 0)
			{
				while($imBig = $imageBig->fetch_array())
					{
						echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/Counters/'.$imBig['name'].'.png" alt="" /><a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/counters/deleteCounter/'.$imBig['id'].'">- Удалить</a><br/>';
					}
			}
		else
			{
				echo 'Больших счетчиков нет.';
			}
		echo '</div><hr/><div class="main">';
		$imageSmall = $mysqli->query("SELECT * FROM `".$prefix."images` WHERE `type` = 'small' ORDER BY `id` ");
		if($imageSmall->num_rows > 0)
			{
				while($imSmall = $imageSmall->fetch_array())
					{
						echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/Counters/'.$imSmall['name'].'.png" alt="" /><a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/counters/deleteCounter/'.$imSmall['id'].'">- Удалить</a><br/><br/>';
					}
			}
		else
			{
				echo 'Маленьких счетчиков нет.';
			}
		echo '</div>';
		echo '<div class="main"><a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/counters/uploadCounter"><strong>Загрузить счётчик</strong></a></div>';
		break;
		case 'uploadCounter':
		echo '<div class="title2">Загрузка счётчика</div>';
        if (!isset($_POST['ok']))
			{
            	echo '<div class="main"><form enctype="multipart/form-data" method="POST" action="">
				Выберите картинку:<br/>
				<input type="file" name="image" value="" /><br/>
				Категория счётчика:<br/>
				<input type="radio" checked name="sizee" value="big" /> большой (72*25)<br/>
				<input type="radio" name="sizee" value="small" /> маленький (72*15)<br/>
				<p><input type="submit" class="button" name="ok" value="Загрузить" /><br/>
				<small>Загрузчик поддерживает форматы: jpeg,gif,png. Максимальный размер файла: 10Кб.</small>
				</p></div></form>';


            }
		else
			{
				$sizee = filter($_POST['sizee']);
				$name = $sizee.'_'.rand(12345678,99999999);
				$x = 72;
				$y = ($sizee == 'big') ? 25 : 15;
				$handle = new upload($_FILES['image']);
				if ($handle->uploaded)
					{
						$handle->file_new_name_body = $name;
						$handle->allowed = array('image/jpeg','image/gif','image/png');
						$handle->file_max_size = 10240;
						$handle->file_overwrite = true;
						$handle->image_resize = true;
						$handle->image_x = $x;
						$handle->image_y = $y;
						$handle->image_convert = 'png';
						$handle->process('../Counters/');
						if ($handle->processed)
							{
								$mysqli->query("INSERT INTO `".$prefix."images` SET `name` = '".$name."', `type` = '".$sizee."'");
								echo '<div class="main">Счётчик '.$name.'.png загружен.<br/></div>';
							}
						else
							{
								echo '<div class="error">Ошибка загрузки:<br/> '.$handle->error.'</div>';
							}
						$handle->clean();
					}
			}
		break;
		case 'deleteCounter':
		echo '<div class="title2">Удаление счётчика</div>';
		$issetCounter = $mysqli->query("SELECT `id`,`name` FROM `".$prefix."images` WHERE `id` = '".$id."'");
		if($issetCounter->num_rows > 0)
			{
				$row = $issetCounter->fetch_assoc();
				$mysqli->query("DELETE FROM `".$prefix."images` WHERE `id` = '".$id."'");
				if(file_exists('../Counters/'.$row['name'].'.png'))
					{
						unlink('../Counters/'.$row['name'].'.png');
					}
				echo '<div class="main">Счётчик '.$row['name'].'.png удален.<br/></div>';
			}
		else
			{
				echo '<div class="error">Счётчик не найден.<br/></div>';
			}
		break;
	}
echo '<hr/>';
echo '<div class="main">';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel/counters">К управлению счётчиками</a><br/>';
echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/controlPanel">В Админку</a><br/>';
echo '</div>';
?>
