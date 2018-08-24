<?php
function data($time)
	{
		$month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
		$month_rus = array('Янв','Фев','Мар','Апр','Мая','Июн','Июл','Авг','Сент','Окт','Ноя','Дек');
		$timep = date("j M Y - H:i:s", $time);
		$timep = str_replace($month,$month_rus,$timep);
		return $timep;
	}
function navigation($total,$page,$url)
	{
		if($page-1 > 0)
			{
				$left='<a href="'.$url.''.($page-1).'"><-Пред.</a>';
			}
		else
			{
				$left = '<-Пред.';
			}
		if($page+1 > 0 AND $page < $total)
			{
				$right = '<a href="'.$url.''.($page+1).'">След.-></a>';
			}
		else
			{
				$right = 'След.->';
			}
echo '<div class="d">';
		if($page-3 > 0)
			{
				$first='<a href="'.$url.'1">1</a>..';
			}
		if($page-2 > 0)
			{
$page2left='<a href="'.$url.''.($page-2).'">'.($page-2).'</a> ';
			}
		if($page-1 > 0)
			{
$page1left='<a href="'.$url.''.($page-1).'">'.($page-1).'</a> ';
			}
		if($page+1 <= $total)
			{
$page1right=' <a href="'.$url.''.($page+1).'">'.($page + 1).'</a>';
			}
		if($page+2 <= $total)
			{
$page2right=' <a href="'.$url.''.($page+2).'">'.($page + 2).'</a>';
			}
		if($page+3 <= $total)
{
				$page3right='..<a href="'.$url.''.($total).'">'.($total).'</a>';
			}
echo ''.$first.$page2left.$page1left.'<font class="tit">'.$page.'</font>'.$page1right.$page2right.$page3right.'</div>';
	}
function wCache($content, $filename)
	{
		$fp = fopen('Cache/'.$filename, 'w');
		fwrite($fp, $content);
		fclose($fp);
	}
function unreg()
	{
		global $user_data;
		if($user_data)
			{
				echo '<div class="error">Доступ только не авторизованным пользователям.</div>';
				require_once('foot.php');
				exit;
			}
	}
function reg()
	{
		global $user_data;
		if(!$user_data)
			{
				echo '<div class="error">Доступ только авторизованным пользователям.</div>';
				require_once('foot.php');
				exit;
			}
	}
function level($level)
	{
		global $user_data;
		if($user_data AND $user_data['level'] < $level OR !$user_data)
			{
				echo '<div class="error">Доступ только администратору.</div>';
				require_once('foot.php');
				exit;
			}
	}
function filter($text)
	{
		global $mysqli;
		$text = htmlspecialchars($text);
		$text = str_replace("\'", "&#39;", $text);
		$text = str_replace('\\', "&#92;", $text);
		$text = str_replace("|", "I", $text);
		$text = str_replace("||", "I", $text);
		$text = str_replace("/\\\$/", "&#36;", $text);
		$text = mysqli_real_escape_string($mysqli,$text);
		return $text;
	}
function password()
	{
		$s = str_split('aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789');
		$pass = '';
		for ($i = 0; $i <= 15; $i++)
			{
				$rand = mt_rand(5,25);
				$pass .= $s[$rand];
			}
		return $pass;
	}
function keyRand()
	{
		$s = str_split('aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789');
		$key = '';
		for ($i = 0; $i <= 45; $i++)
			{
				$rand = mt_rand(2,30);
				$key .= $s[$rand];
			}
		return $key;
	}

function mobile()
	{
		 $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
		 $useragent = str_replace('windows ce', '',$useragent);
		 if(
		 strpos($useragent, "win") !== false OR
		 strpos($useragent, "linux") !== false OR
		 strpos($useragent, "lynx") !== false OR
		 strpos($useragent, "unix") !== false OR
		 strpos($useragent, "macintosh") !== false OR
		 strpos($useragent, "powerpc") !== false)
		 {
			return false;
		 }
		 else
		 {
			return true;
		 }
	}


	/*function mobile()
	{

			return true;

	}  */



function proxy()
	{
		if(isset($_SERVER['HTTP_VIA']))
			{
				return true;
			}
	}
function cy($url)
    {
		$url = str_replace("www.", "", $url);
		$ci_url = "http://bar-navig.yandex.ru/u?ver=2&show=32&url=http://www.".$url."/";
		$ci_data = implode("", file("$ci_url"));
		preg_match("/value=\"(.\d*)\"/", $ci_data, $ci);
		if ($ci[1] == "")
			{
				return 0;
			}
			else
			{
				return $ci[1];
			}
     }
function compression()
	{
		if(preg_match('#gzip#iU',$_SERVER['HTTP_ACCEPT_ENCODING']))
			{
				return 1;
			}
		else
			{
				return 0;
			}
	}
function bbCodes($text) /*некоторая часть с JohnCms 4.3.0*/
    {
        $search = array(
            '#\[b](.+?)\[/b]#is',                                              // Жирный
            '#\[i](.+?)\[/i]#is',                                              // Курсив
            '#\[red](.+?)\[/red]#is',                                          // Красный
            '#\[green](.+?)\[/green]#is',                                      // Зеленый
            '#\[blue](.+?)\[/blue]#is'                                         // Синий
        );
        $replace = array(
            '<span style="font-weight: bold">$1</span>',                       // Жирный
            '<span style="font-style:italic">$1</span>',                       // Курсив
            '<span style="color:red">$1</span>',                               // Красный
            '<span style="color:green">$1</span>',                             // Зеленый
            '<span style="color:blue">$1</span>'                              // Синий
        );
        return preg_replace($search, $replace, $text);
    }
function StrToNum($Str, $Check, $Magic)
{
    $Int32Unit = 4294967296;

    $length = strlen($Str);
    for ($i = 0; $i < $length; $i++) {
        $Check *= $Magic;

        if ($Check >= $Int32Unit) {
            $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));

            $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
        }
        $Check += ord($Str{$i});
    }
    return $Check;
}
function HashURL($String)
{
    $Check1 = StrToNum($String, 0x1505, 0x21);
    $Check2 = StrToNum($String, 0, 0x1003F);

    $Check1 >>= 2;
    $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
    $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
    $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);

    $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 &
0xF0F );
    $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) |
($Check2 & 0xF0F0000 );

    return ($T1 | $T2);
}
function CheckHash($Hashnum)
{
    $CheckByte = 0;
    $Flag = 0;

    $HashStr = sprintf('%u', $Hashnum) ;
    $length = strlen($HashStr);

    for ($i = $length - 1;  $i >= 0;  $i --) {
        $Re = $HashStr{$i};
        if (1 === ($Flag % 2)) {
            $Re += $Re;
            $Re = (int)($Re / 10) + ($Re % 10);
        }
        $CheckByte += $Re;
        $Flag ++;
    }

    $CheckByte %= 10;
    if (0 !== $CheckByte) {
        $CheckByte = 10 - $CheckByte;
        if (1 === ($Flag % 2) ) {
            if (1 === ($CheckByte % 2)) {
                $CheckByte += 9;
            }
            $CheckByte >>= 1;
        }
    }

    return '7'.$CheckByte.$HashStr;
}
function getPageRank($aUrl)
	{
		 $url = 'info:'.$aUrl;
		 $ch = CheckHash(HashURL($aUrl));
		 $pr = implode("", file("http://toolbarqueries.google.com/tbr?client=navclient-auto&ch=$ch&features=Rank&q=$url&num=100&filter=0"));
		 $pr_str = explode(":",$pr);
		 return $pr_str[2];
	}
?>