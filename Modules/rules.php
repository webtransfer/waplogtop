<?php
$str = 'office';
$title = 'Кабинет';
require_once('Sys/head.php');
echo '<div class="title2">Правила сайта</div><div class="main">';
$row = $mysqli->query("SELECT `text` FROM `".$prefix."rules`")->fetch_assoc();
echo bbCodes(nl2br($row['text']));
echo '</div>';
?>