<?php
$title = 'Контакты';
require_once ('Sys/head.php');
$icq = !empty($set['icq']) ? $set['icq'] : '<strong>Не указано</strong>';
$mail= !empty($set['mail']) ? $set['mail'] : '<strong>Не указано</strong>';
echo '<div class="title2">Контакты</div>';
echo '<div class="main">';
echo '<li>ICQ: '.$icq.'</li>';
echo '<li>E-MAIL: '.$mail.'</li>';
echo '</div>';
?>
