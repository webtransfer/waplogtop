<?php
require_once ('../Sys/connect.php');
require_once ('../Sys/core.php');
require_once ('../Sys/function.php');
if (isset($_GET['adminm']))
	{
		$modull = str_replace(array('/', '.', '\\'), '', preg_replace('/\0/s', '', $_GET['adminm']));
		$modull = $modull.'.php';
		if (is_file($modull))
			{
				include $modull ;
			}
		else
			{
				include 'main.php';
			}
	}
else
	{
		include 'main.php';
	}	
require_once ('../Sys/foot.php');
?>
