<?php
require_once ('connect.php');
require_once ('core.php');
require_once ('function.php');

$mysqli->query("DELETE FROM `".$prefix."browsers`");

?>
