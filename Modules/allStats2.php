<?php
$title = 'Полная статистика рейтинга';
require_once ('Sys/head.php');
if (file_exists('Cache/statsAllTop.cache') AND ($time-60) < filemtime('Cache/statsAllTop.cache') AND $cache['AllTopStats'] > 0) 
	{ 
		echo implode('', file ('Cache/statsAllTop.cache'));
	}
else
	{
		ob_start();
		$stats = array();
		$query = $mysqli->query("SELECT * FROM `".$prefix."stats`");
		while($queryy = $query->fetch_assoc())
			{
				$stats[$queryy['name']] = $queryy['value'];
			}
		$plaformsNoMod = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `status` = '0'")->num_rows;
		$plaformsMod = $mysqli->query("SELECT `id` FROM `".$prefix."sait` WHERE `status` = '1'")->num_rows;
		$plaformsAll = $mysqli->query("SELECT `id` FROM `".$prefix."sait`")->num_rows;
		$usersAll = $mysqli->query("SELECT `id` FROM `".$prefix."users`")->num_rows;
		$newsAll = $mysqli->query("SELECT `id` FROM `".$prefix."news`")->num_rows;
		$catAll = $mysqli->query("SELECT `id` FROM `".$prefix."cat`")->num_rows;
								$daym = date("d");
								$yesterdayHosts = 0;
								$yesterdayHits = 0;
								$yesterdayIn = 0;
								$yesterdayOut = 0;
								if($daym - 1 > 0)
									{
										$yd = $daym - 1;
										if(strlen($yd) == 1)
											{
												$yd = '0'.$yd;
											}
										$month = date("m");
										$yday1 = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `month` = '".$month."'");
										if($yday1->num_rows > 0)
											{
												while($row = $yday1->fetch_assoc())
													{
														if(!empty($row[''.$yd.'']))
															{
																$yday = explode('|',$row[''.$yd.'']);
																$yesterdayHosts += $yday[0];
																$yesterdayHits += $yday[1];
																$yesterdayIn += $yday[2];
																$yesterdayOut += $yday[3];
															}
													}
											}
									}
								else
									{
										if(date("m") - 1 > 0)
											{
												$month = date("m") - 1;
												if(strlen($month) == 1)
													{
														$month = '0'.$month;
													}
													$yd = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));
													$yday1 = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `month` = '".$month."'");
													if($yday1->num_rows > 0)
														{
															while($row = $yday1->fetch_assoc())
																{
																	if(!empty($row[''.$yd.'']))
																		{
																			$yday = explode('|',$row[''.$yd.'']);
																			$yesterdayHosts += $yday[0];
																			$yesterdayHits += $yday[1];
																			$yesterdayIn += $yday[2];
																			$yesterdayOut += $yday[3];
																		}
																}
														}
											}
									}
		$online = $mysqli->query("SELECT * FROM `".$prefix."saitsOnline`")->num_rows;
		echo '<div class="title2">Общее<br/></div>';
		echo '<div class="main">';
		echo 'Всего пользователей: <strong>'.$usersAll.'</strong><br/>';
		echo 'Всего площадок: <strong>'.$plaformsAll.'</strong><br/>';
		echo 'Активированных площадок: <strong>'.$plaformsMod.'</strong><br/>';
		echo 'Неактивированных площадок: <strong>'.$plaformsNoMod.'</strong><br/>';
		echo 'Всего категорий: <strong>'.$catAll.'</strong><br/>';
		echo 'Всего новостей: <strong>'.$newsAll.'</strong><br/>';
		echo 'Сейчас на сайтах сидит: <strong>'.$online.'</strong> человек(а)</div>';	
		echo '<div class="title2">Сегодня<br/></div>';
		echo '<div class="main">';
		echo 'Хостов: <strong>'.$stats['hosts'].'</strong><br/>';
		echo 'Хитов: <strong>'.$stats['hits'].'</strong><br/>';
		echo 'Пришло в топ: <strong>'.$stats['in'].'</strong><br/>';
		echo 'Ушло из топа: <strong>'.$stats['out'].'</strong></div>';
		echo '<div class="title2">За весь период<br/></div>';
		echo '<div class="main">';
		echo 'Хостов: <strong>'.$stats['allHosts'].'</strong><br/>';
		echo 'Хитов: <strong>'.$stats['allHits'].'</strong><br/>';
		echo 'Пришло в топ: <strong>'.$stats['allIn'].'</strong><br/>';
		echo 'Ушло из топа: <strong>'.$stats['allOut'].'</strong><br/>';	
		echo '</div>';
		$body = ob_get_contents(); 
		ob_end_clean(); 
		if($cache['AllTopStats'] > 0)
			{
				wCache($body, 'statsAllTop.cache');
				echo $body;
			}
		else
			{
				echo $body; 
			}
	}
?>
