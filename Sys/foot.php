<?php
echo '




<div class="title2">
';


				echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/m/allStats">Cтатистика</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/m/contacts">Контакты</a>';

	echo' </div>';

echo '<div class="main">';
include 'fteaser.php';
echo '</div>';
echo '<div class="title2"><a href="/">&copy; UZLOG.TOP 2016</a></div><div class="foot">';
include 'banner.php';
echo '</div>';

echo'
</body>
</html> ';


?>