<?
$prefix = 'top33_';
$mysqli = new mysqli("localhost", "db1456417968", "uzlogtop", "db1456417968");
if(mysqli_connect_errno())
{
die("Ошибка соединения: ".mysqli_connect_error());
}
$mysqli->query("SET NAMES 'utf8'");
?>
