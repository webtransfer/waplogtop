<?php
$title = 'Статистика сайта';
require_once('Sys/head.php');
/* Проверка сайта */
$sait = $mysqli->query("SELECT * FROM `".$prefix."sait` WHERE `id` = '".$id."'");
if($sait->num_rows == 0)
	{
		echo '<div class="error">';
		echo 'Сайт не обнаружен в базе.<br/>';
		echo '</div>';
		require_once('Sys/foot.php');
		exit;
	}
$saitOnline = $mysqli->query("SELECT * FROM `".$prefix."saitsOnline` WHERE `sid` = '".$id."' AND `time` > '".($time-180)."'")->num_rows;
$saitRow = $sait->fetch_assoc();
/* Проверка бана */
if($saitRow['ban'] == 1)
	{
		echo '<div class="error">';
		echo 'Сайт заблокирован.<br/>';
		echo '</div>';
		require_once('Sys/foot.php');
		exit;
	}
/* Проверка активации */
if($saitRow['status'] == 0)
	{
		echo '<div class="error">';
		echo 'Сайт не активирован администартором.<br/>';
		echo '</div>';
		require_once('Sys/foot.php');
		exit;
	}
/* Начало статистики */
echo '<div class="title2"><b>'.$saitRow['name'].'</b> <small>('.$saitOnline.' чел. онлайн)</small></div>';
switch($act)
	{
						default:
								if (file_exists('Cache/statsIndex_'.$id.'.cache') AND ($time-60) < filemtime('Cache/statsIndex_'.$id.'.cache') AND $cache['indexStats'] > 0)
									{
										echo implode('', file ('Cache/statsIndex_'.$id.'.cache'));
									}
								else
									{
										ob_start();
										/* Информация о сайте */
										$category = $mysqli->query("SELECT `name` FROM `".$prefix."cat` WHERE `id` = '".$saitRow['category']."'")->fetch_assoc();
								        $day = $mysqli->query("SELECT `hosts`,`hits`,`in`,`out`,`allHosts`,`allHits`,`allIn`,`allOut` FROM `".$prefix."sait` WHERE `id` = '".$id."'")->fetch_assoc();
                                         $daym = date("d");
										if($daym - 1 > 0)
											{
												$yd = $daym - 1;
												if(strlen($yd) == 1)
													{
																$yd = '0'.$yd;
													}
												$month = date("m");
												$yday1 = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".$month."'");
												if($yday1->num_rows > 0)
													{
														$row = $yday1->fetch_assoc();
														if(!empty($row[''.$yd.'']))
															{
																$yday = explode('|',$row[''.$yd.'']);
															}
														else
															{
																$yday[0] = 0;
																$yday[1] = 0;
																$yday[2] = 0;
																$yday[3] = 0;
															}
													}
												else
													{
														$yday[0] = 0;
														$yday[1] = 0;
														$yday[2] = 0;
														$yday[3] = 0;
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
														$yday1 = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".$month."'");
														if($yday1->num_rows > 0)
															{
																$row = $yday1->fetch_assoc();
																if(!empty($row[''.$yd.'']))
																	{
																		$yday = explode('|',$row[''.$yd.'']);
																	}
																else
																	{
																		$yday[0] = 0;
																		$yday[1] = 0;
																		$yday[2] = 0;
																		$yday[3] = 0;
																	}
															}
														else
															{
																$yday[0] = 0;
																$yday[1] = 0;
																$yday[2] = 0;
																$yday[3] = 0;
															}
													}
												else
													{
														$yday[0] = 0;
														$yday[1] = 0;
														$yday[2] = 0;
														$yday[3] = 0;
													}
											}
echo '<div class="title" align="center"><img src="http://mini.s-shot.ru/140x320/240/png/?http://'.$saitRow['url'].'" align="top" alt="Скриншот сайта '.$saitRow['url'].'"/></div>';										echo '<div class="title">';
										echo '<strong>Общее</strong><br/>';
										echo 'Название: <strong>'.$saitRow['name'].'</strong><br/>';
										echo 'Адрес: <a href="http://'.$_SERVER['HTTP_HOST'].'/out/'.$saitRow['id'].'"><strong>'.$saitRow['url'].'</strong></a><br/>';
										echo 'Категория: <a href="http://'.$_SERVER['HTTP_HOST'].'/m/category/view/'.$saitRow['category'].'"><strong>'.$category['name'].'</strong></a><br/>';
										echo 'Описание: '.nl2br($saitRow['about']).'</div><div class="title">
										<strong>Сегодня</strong><br />
										Хосты: '.$day['hosts'].' Хиты: '.$day['hits'].'<br />
										В топ: '.$day['in'].' Из топа: '.$day['out'].'</div>';


										echo '<div class="title">';
										echo '<strong>SEO</strong><br/>';
										echo '<font color=red>Google PR </font>'.$saitRow['pr'].' | <font color=red>Яндекс ТИц </font>'.$saitRow['cy'].'<br/>';
										echo '</div>';
echo '<div class="main" align="center">';
                                                                                echo '<strong>Информация о сайте</strong><br/>';
										echo 'Статистика : ';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/all">Общая</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/hours">По часам</a> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/month">По месяцам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/days">По дням</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators">По операторам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online">По Онлайн</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers">По браузерам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/compression">По Сжатию</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country">По Странам</a><br/>';
										echo '</div>';
										$body = ob_get_contents();
										ob_end_clean();
										if($cache['indexStats'] > 0)
											{
												wCache($body, 'statsIndex_'.$id.'.cache');
												echo $body;
											}
											else
											{
												echo $body;
											}
									}
						break;
						case 'all':
								if (file_exists('Cache/statsAll_'.$id.'.cache') AND ($time-60) < filemtime('Cache/statsAll_'.$id.'.cache') AND $cache['allStats'] > 0)
									{
										echo implode('', file('Cache/statsAll_'.$id.'.cache'));
									}
								else
									{
										ob_start();
										/* За сегодня */
										$day = $mysqli->query("SELECT `hosts`,`hits`,`in`,`out`,`allHosts`,`allHits`,`allIn`,`allOut` FROM `".$prefix."sait` WHERE `id` = '".$id."'")->fetch_assoc();
										/* За вчера */
										$daym = date("d");
										if($daym - 1 > 0)
											{
												$yd = $daym - 1;
												if(strlen($yd) == 1)
													{
																$yd = '0'.$yd;
													}
												$month = date("m");
												$yday1 = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".$month."'");
												if($yday1->num_rows > 0)
													{
														$row = $yday1->fetch_assoc();
														if(!empty($row[''.$yd.'']))
															{
																$yday = explode('|',$row[''.$yd.'']);
															}
														else
															{
																$yday[0] = 0;
																$yday[1] = 0;
																$yday[2] = 0;
																$yday[3] = 0;
															}
													}
												else
													{
														$yday[0] = 0;
														$yday[1] = 0;
														$yday[2] = 0;
														$yday[3] = 0;
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
														$yday1 = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".$month."'");
														if($yday1->num_rows > 0)
															{
																$row = $yday1->fetch_assoc();
																if(!empty($row[''.$yd.'']))
																	{
																		$yday = explode('|',$row[''.$yd.'']);
																	}
																else
																	{
																		$yday[0] = 0;
																		$yday[1] = 0;
																		$yday[2] = 0;
																		$yday[3] = 0;
																	}
															}
														else
															{
																$yday[0] = 0;
																$yday[1] = 0;
																$yday[2] = 0;
																$yday[3] = 0;
															}
													}
												else
													{
														$yday[0] = 0;
														$yday[1] = 0;
														$yday[2] = 0;
														$yday[3] = 0;
													}
											}
										/* За текущий месяц */
										$monthh = $mysqli->query("SELECT * FROM `".$prefix."month` WHERE `sid` = '".$id."'");
										if($monthh->num_rows > 0)
											{
												$row = $monthh->fetch_assoc();
												if(!empty($row[''.date("m").'']))
													{
														$month = explode('|',$row[''.date("m").'']);
													}
												else
													{
														$month[0] = 0;
														$month[1] = 0;
														$month[2] = 0;
														$month[3] = 0;
													}
											}
										else
											{
												$month[0] = 0;
												$month[1] = 0;
												$month[2] = 0;
												$month[3] = 0;
											}
										/* Вывод статистики */
										$array_m = array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль','08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь');
										echo '
										<table width="100%" border="0" cellspacing="1" cellpadding="2">
										<tr class="table_name">
<td colspan=2><center>Сегодня</center></span></td></tr>
										<tr class="table"><td>Хосты:</td> <td>'.$day['hosts'].'</td></tr>
										<tr class="table"><td>Хиты:</td> <td>'.$day['hits'].'</td> </tr>
										<tr class="table"><td>В топ:</td> <td>'.$day['in'].'</td> </tr>
										<tr class="table"><td>Из топа:</td> <td>'.$day['out'].'</td> </tr>

										<tr class="table_name">
<td colspan=2><center>Вчера</center></span></td></tr><tr>
										<tr class="table"><td>Хосты:</td> <td>'.$yday[0].'</td></tr>
										<tr class="table"><td>Хиты:</td> <td>'.$yday[1].'</td> </tr>
										<tr class="table"><td>В топ:</td> <td>'.$yday[2].'</td> </tr>
										<tr class="table"><td>Из топа:</td> <td>'.$yday[3].'</td> </tr>

										<tr class="table_name">
<td colspan=2><center>За <strong>'.$array_m[date("m")].'</center></strong></span></td></tr><tr>
										<tr class="table"><td>Хосты:</td> <td>'.$month[0].'</td></tr>
										<tr class="table"><td>Хиты:</td> <td>'.$month[1].'</td> </tr>
										<tr class="table"><td>В топ:</td> <td>'.$month[2].'</td> </tr>
										<tr class="table"><td>Из топа:</td> <td>'.$month[3].'</td> </tr>

										<tr class="table_name">
<td colspan=2><center>Всего</center></span></td></tr><tr>
										<tr class="table"><td>Хосты:</td> <td>'.$day['allHosts'].'</td></tr>
										<tr class="table"><td>Хиты:</td> <td>'.$day['allHits'].'</td> </tr>
										<tr class="table"><td>В топ:</td> <td>'.$day['allIn'].'</td> </tr>
										<tr class="table"><td>Из топа:</td> <td>'.$day['allOut'].'</td> </tr></table>
								    ';
                                                                                echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'"><strong>Информация о сайте</strong></a></div>';
echo '<div class="main" align="center">';
										echo 'Статистика : ';
										echo '<strong>Общая</strong> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/hours">По часам</a> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/month">По месяцам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/days">По дням</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators">По операторам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online">По Онлайн</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers">По браузерам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/compression">По Сжатию</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country">По Странам</a><br/>';
										echo '</div>';
										$body = ob_get_contents();
										ob_end_clean();
										if($cache['allStats'] > 0)
											{
												wCache($body, 'statsAll_'.$id.'.cache');
												echo $body;
											}
											else
											{
												echo $body;
											}
									}
						break;
						/* Статистика по месяцам */
						case 'month':
								if (file_exists('Cache/statsMonth_'.$id.'.cache') AND ($time-60) < filemtime('Cache/statsMonth_'.$id.'.cache') AND $cache['monthStats'] > 0)
									{
										echo implode('', file ('Cache/statsMonth_'.$id.'.cache'));
									}
								else
									{
										ob_start();
										echo '<tr class="table_name">
										<table width="100%" border="0" cellspacing="1" cellpadding="2">
										<tr class="table_name">
										<td>Месяц</td><td>Хосты</td><td>Хиты</td><td>В топ</td><td>Из топа</td></tr>
										';
										for($month = date("m",$saitRow['regdate']); $month <= date("m"); $month++)
											{
												$days = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));
												$array_m = array('01'=>'Январь','02'=>'Февраль','03'=>'Март','04'=>'Апрель','05'=>'Май','06'=>'Июнь','07'=>'Июль','08'=>'Август','09'=>'Сентябрь','10'=>'Октябрь','11'=>'Ноябрь','12'=>'Декабрь');
												$monthh = $mysqli->query("SELECT * FROM `".$prefix."month` WHERE `sid` = '".$id."'");
												if(strlen($month) == 1)
													{
														$month = '0'.$month.'';
													}
												else
													{
														$month = $month;
													}
												if($monthh->num_rows > 0)
													{
														$row = $monthh->fetch_assoc();
														if(!empty($row[''.$month.'']))
															{
																$monthArray = explode('|',$row[''.$month.'']);
															}
														else
															{
																$monthArray[0] = 0;
																$monthArray[1] = 0;
																$monthArray[2] = 0;
																$monthArray[3] = 0;
															}
													}
												else
													{
														$monthArray[0] = 0;
														$monthArray[1] = 0;
														$monthArray[2] = 0;
														$monthArray[3] = 0;
													}
												echo '<tr class="table"><td><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/days/'.$id.'/'.$month.'">'.$array_m[$month].'</a></td><td>'.$monthArray[0].'</td><td>'.$monthArray[1].'</td><td>'.$monthArray[2].'</td><td>'.$monthArray[3].'</td></tr>';
											}
										echo '</table>';
                                                                                echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'"><strong>Информация о сайте</strong></a></div>';
echo '<div class="main" align="center">';
										echo 'Статистика : ';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/all">Общая</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/hours">По часам</a> |  <strong>По месяцам</strong> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/days">По дням</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators">По операторам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online">По Онлайн</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers">По браузерам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/compression">По Сжатию</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country">По Странам</a><br/>';
										echo '</div>';
										$body = ob_get_contents();
										ob_end_clean();
										if($cache['monthStats'] > 0)
											{
												wCache($body, 'statsMonth_'.$id.'.cache');
												echo $body;
											}
											else
											{
												echo $body;
											}
									}
						break;
						case 'days':
										if(!isset($_GET['month']))
											{
												/* Статистика по дням */
												if (file_exists('Cache/statsDays_'.$id.'.cache') AND ($time-60) < filemtime('Cache/statsDays_'.$id.'.cache') AND $cache['daysStats'] > 0)
													{
														echo implode('', file ('Cache/statsDays_'.$id.'.cache'));
													}
												else
													{
														ob_start();
														echo '<tr class="table_name">
														<table width="100%" border="0" cellspacing="1" cellpadding="2">
														<tr class="table_name">
<td><center>День</center></td><td><center>Хосты</center></td><td><center>Хиты</center></td><td><center>В топ</center></td><td><center>Из топа</center></td></tr></center>
														';
														for($day = 1; $day <= date("d"); $day++)
															{
																if(strlen($day) == 1)
																	{

																		$day = '0'.$day;

																	}
																$array_m = array('01'=>'Января','02'=>'Февраля','03'=>'Марта','04'=>'Апреля','05'=>'Мая','06'=>'Июня','07'=>'Июля','08'=>'Августа','09'=>'Сентября','10'=>'Октября','11'=>'Ноября','12'=>'Декабря');
																$dd = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
																if($dd->num_rows > 0)
																	{
																		$row = $dd->fetch_assoc();
																		if(!empty($row[''.$day.'']))
																			{
																				$dday = explode('|',$row[''.$day.'']);
																			}
																		else
																			{
																				$dday[0] = 0;
																				$dday[1] = 0;
																				$dday[2] = 0;
																				$dday[3] = 0;
																			}
																	}
																else
																	{
																		$dday[0] = 0;
																		$dday[1] = 0;
																		$dday[2] = 0;
																		$dday[3] = 0;
																	}
																echo '<tr class="table"><td>'.$day.' '.$array_m[date("m")].'</td><td>'.$dday[0].'</td><td>'.$dday[1].'</td><td>'.$dday[2].'</td><td>'.$dday[3].'</td></tr>';
															}
														echo '</table>';
														$body = ob_get_contents();
														ob_end_clean();
														if($cache['daysStats'] > 0)
															{
																wCache($body, 'statsDays_'.$id.'.cache');
																echo $body;
															}
															else
															{
																echo $body;
															}
													}
											}
										else
											{
												/* Статистика по дням */
												if (file_exists('Cache/statsDays_'.$id.'_'.intval($_GET['month']).'.cache') AND ($time-60) < filemtime('Cache/statsDays_'.$id.'_'.intval($_GET['month']).'.cache') AND $cache['daysStats'] > 0)
													{
														echo implode('', file ('Cache/statsDays_'.$id.'_'.intval($_GET['month']).'.cache'));
													}
												else
													{
														ob_start();
														$month = abs(intval($_GET['month']));
														if($month <= date("m"))
															{
																$dd = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".$month."'");
																if($dd->num_rows > 0)
																	{
																		echo '<tr class="table_name">
																		<table width="100%" border="0" cellspacing="1" cellpadding="2">
																		<tr class="table_name">
																		<td>День</td><td>Хосты</td><td>Хиты</td><td>В топ</td><td>Из топа</td></tr>
																		';
																		if($month == date("m"))
																			{
																				$days = date("d");
																			}
																		else
																			{
																				$days = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));
																			}
																				for($day = 1; $day <= $days; $day++)
																					{
																						if(strlen($day) == 1)
																							{

																								$day = '0'.$day;

																							}
																						$array_m = array('1'=>'Января','2'=>'Февраля','3'=>'Марта','4'=>'Апреля','5'=>'Мая','6'=>'Июня','7'=>'Июля','8'=>'Августа','9'=>'Сентября','10'=>'Октября','11'=>'Ноября','12'=>'Декабря');
																						$dd1 = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".$month."'");
																						$row = $dd1->fetch_assoc();
																						if(!empty($row[''.$day.'']))
																							{
																								$dday = explode('|',$row[''.$day.'']);
																							}
																						else
																							{
																								$dday[0] = 0;
																								$dday[1] = 0;
																								$dday[2] = 0;
																								$dday[3] = 0;
																							}
																						echo '<tr class="table"><td>'.$day.' '.$array_m[$month].'</td><td>'.$dday[0].'</td><td>'.$dday[1].'</td><td>'.$dday[2].'</td><td>'.$dday[3].'</td></tr>';
																					}
																					echo '</table>';
																	}
																else
																	{
																		echo '<div class="error">Статистика не найдена.</div>';
																	}
															}
														else
															{
																echo '<div class="error">Статистика не найдена.</div>';
															}
														$body = ob_get_contents();
														ob_end_clean();
														if($cache['daysStats'] > 0)
															{
																wCache($body, 'statsDays_'.$id.'_'.intval($_GET['month']).'.cache');
																echo $body;
															}
															else
															{
																echo $body;
															}
													}
											}
                                                                                echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'"><strong>Информация о сайте</strong></a></div>';
                                                                                echo '<div class="main">';
										echo 'Статистика : ';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/all">Общая</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/hours">По часам</a> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/month">По месяцам</a> | <strong>По дням</strong> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators">По операторам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online">По Онлайн</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers">По браузерам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/compression">По Сжатию</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country">По Странам</a><br/>';
										echo '</div>';
						break;
						/* Статистика по часам */
						case 'hours':
								if (file_exists('Cache/statsHours_'.$id.'.cache') AND ($time-60) < filemtime('Cache/statsHours_'.$id.'.cache') AND $cache['hoursStats'] > 0)
									{
										echo implode('', file ('Cache/statsHours_'.$id.'.cache'));
									}
								else
									{
										ob_start();
										echo '<tr class="table_name">
										<table width="100%" border="0" cellspacing="1" cellpadding="2">
										<tr class="table_name">
										<td>Промежуток времени</td><td>Хосты</td><td>Хиты</td><td>В топ</td><td>Из топа</td></tr>
										';
										for($hour = 0; $hour <= (int)date("H"); $hour++)
											{
												if($hour == 23)
													{
														$hourD = '23:00-00:00';
													}
												else
													{
														if(strlen($hour) == 1)
															{

																$hourr = '0'.$hour;

															}
														else
															{
																$hourr = $hour;

															}
														if(strlen($hour + 1) == 1)
															{
																$hour2 = '0'.($hour + 1);
															}
														else
															{
																$hour2 = $hour + 1;
															}
														$hourD = ''.$hourr.':00-'.$hour2.':00';
													}
													$hd = $mysqli->query("SELECT * FROM `".$prefix."hours` WHERE `sid` = '".$id."'");
													if($hd->num_rows > 0)
														{
															$row = $hd->fetch_assoc();
															if(!empty($row[''.$hourD.'']))
																{
																	$hday = explode('|',$row[''.$hourD.'']);
																}
															else
																{
																	$hday[0] = 0;
																	$hday[1] = 0;
																	$hday[2] = 0;
																	$hday[3] = 0;
																}
														}
													else
														{
															$hday[0] = 0;
															$hday[1] = 0;
															$hday[2] = 0;
															$hday[3] = 0;
														}
													echo '<tr class="table"><td>'.$hourD.'</td><td>'.$hday[0].'</td><td>'.$hday[1].'</td><td>'.$hday[2].'</td><td>'.$hday[3].'</td></tr>';
											}
											echo '</table>';
                                                                                echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'"><strong>Информация о сайте</strong></a></div>';
                                                                                echo '<div class="main">';
											echo 'Статистика : ';
											echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/all">Общая</a> | <strong>По часам</strong> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/month">По месяцам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/days">По дням</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators">По операторам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online">По Онлайн</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers">По браузерам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/compression">По Сжатию</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country">По Странам</a><br/>';
											echo '</div>';
											$body = ob_get_contents();
											ob_end_clean();
											if($cache['daysStats'] > 0)
												{
													wCache($body, 'statsHours_'.$id.'.cache');
													echo $body;
												}
											else
												{
													echo $body;
												}
									}
						break;
						/* Статистика по операторам */
						case 'operators':
						$pageCache = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '1';
						if (file_exists('Cache/statsOperators_'.$id.'_'.$pageCache.'.cache') AND ($time-60) < filemtime('Cache/statsOperators_'.$id.'_'.$pageCache.'.cache') AND $cache['operatorsStats'] > 0)
									{
										echo implode('', file ('Cache/statsOperators_'.$id.'_'.$pageCache.'.cache'));
									}
								else
									{
										ob_start();
										$allPages = $mysqli->query("SELECT DISTINCT `operator` FROM `".$prefix."operators` WHERE `sid` = '".$id."'")->num_rows;
										$all = $mysqli->query("SELECT `operator` FROM `".$prefix."operators` WHERE `sid` = '".$id."'")->num_rows;
										if($all > 0)
											{
												echo '<tr class="table_name">
												<table width="100%" border="0" cellspacing="1" cellpadding="2">
												<tr class="table_name">
												<td>Оператор</td><td>Хосты</td><td>Проценты</td>
												</tr>
												';
												$total = intval(($allPages-1)/$pages)+1;
												$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
												if(empty($page) OR $page < 0)
													{
														$page = 1;
													}
												if($page > $total)
													{
														$page = $total;
													}
												$past = intval($allPages/$pages);
												$start = $page*$pages-$pages;
												$oper = $mysqli->query("SELECT DISTINCT `operator`, COUNT(`operator`) AS `count` FROM `".$prefix."operators` WHERE `sid` = '".$id."' GROUP BY `operator` ORDER BY `count` DESC LIMIT ".$start.",".$pages."");
												while($operator = $oper->fetch_assoc())
													{
														$procent = ($operator['count'] / $all) * 100;
														echo '<tr class="table"><td>'.$operator['operator'].'</td><td>'.$operator['count'].'</td><td>'.round($procent, 2).'%</td></tr>';
													}
												echo '</table>';
												if($total > 1)
													{
														navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators/');
													}
											}
										else
											{
												echo '<div class="error">Статистика не найдена.</div>';
											}
                                                                                echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'"><strong>Информация о сайте</strong></a></div>';
                                                                                echo '<div class="main">';
										echo 'Статистика : ';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/all">Общая</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/hours">По часам</a> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/month">По месяцам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/days">По дням</a> | <strong>По операторам</strong> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online">По Онлайн</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers">По браузерам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/compression">По Сжатию</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country">По Странам</a><br/>';
										echo '</div>';
										$body = ob_get_contents();
										ob_end_clean();
										if($cache['operatorsStats'] > 0)
											{
												wCache($body, 'statsOperators_'.$id.'_'.$pageCache.'.cache');
												echo $body;
											}
										else
											{
												echo $body;
											}
									}
						break;
						/* Статистика по онлайн */
						case 'online':
						$pageCache = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '1';
						if (file_exists('Cache/statsOnline_'.$id.'_'.$pageCache.'.cache') AND ($time-60) < filemtime('Cache/statsOnline_'.$id.'_'.$pageCache.'.cache') AND $cache['onlineStats'] > 0)
									{
										echo implode('', file ('Cache/statsOnline_'.$id.'_'.$pageCache.'.cache'));
									}
								else
									{
										ob_start();
										$all = $mysqli->query("SELECT * FROM `".$prefix."saitsOnline` WHERE `sid` = '".$id."'")->num_rows;
										if($all > 0)
											{
												echo '<tr class="table_name">
												<table width="100%" border="0" cellspacing="1" cellpadding="2">
												<tr class="table_name">
												<td>IP</td><td>Браузер</td><td>Время</td>
												</tr>';
												$total = intval(($all-1)/$pages)+1;
												$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
												if(empty($page) OR $page < 0)
													{
														$page = 1;
													}
												if($page > $total)
													{
														$page = $total;
													}
												$past = intval($all/$pages);
												$start = $page*$pages-$pages;
												$online = $mysqli->query("SELECT * FROM `".$prefix."saitsOnline` WHERE `sid` = '".$id."' LIMIT ".$start.",".$pages."");
												while($row = $online->fetch_assoc())
													{
														echo '<tr class="table"><td>'.long2ip($row['ip']).'</td> <td>'.$row['ua'].'</td> <td>'.data($row['time']).'</td></tr>';
													}
												echo '</table>';
												if($total > 1)
													{
														navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online/');
													}
											}
										else
											{
												echo '<div class="error">Статистика не найдена.</div>';
															}
                                                                                echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'"><strong>Информация о сайте</strong></a></div>';
                                                                                echo '<div class="main">';
										echo 'Статистика : ';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/all">Общая</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/hours">По часам</a> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/month">По месяцам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/days">По дням</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators">По операторам</a> | <strong>По Онлайн</strong> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers">По браузерам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/compression">По Сжатию</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country">По Странам</a><br/>';
										echo '</div>';
										$body = ob_get_contents();
										ob_end_clean();
										if($cache['onlineStats'] > 0)
											{
												wCache($body, 'statsOnline_'.$id.'_'.$pageCache.'.cache');
												echo $body;
											}
										else
											{
												echo $body;
											}
									}
						break;
						/* Статистика по браузерам */
						case 'browsers':
						$pageCache = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '1';
						if (file_exists('Cache/statsBrowsers_'.$id.'_'.$pageCache.'.cache') AND ($time-60) < filemtime('Cache/statsBrowsers_'.$id.'_'.$pageCache.'.cache') AND $cache['browsersStats'] > 0)
									{
										echo implode('', file ('Cache/statsBrowsers_'.$id.'_'.$pageCache.'.cache'));
									}
								else
									{
										ob_start();
										$allPages = $mysqli->query("SELECT DISTINCT `browser` FROM `".$prefix."browsers` WHERE `sid` = '".$id."'")->num_rows;
										$all = $mysqli->query("SELECT `browser` FROM `".$prefix."browsers` WHERE `sid` = '".$id."'")->num_rows;
										if($all > 0)
											{
												echo '<tr class="table_name">
												<table width="100%" border="0" cellspacing="1" cellpadding="2">
												<tr class="table_name">
												<td>Браузер</td><td>Хосты</td><td>Проценты</td>
												</tr>
												';
												$total = intval(($allPages-1)/$pages)+1;
												$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
												if(empty($page) OR $page < 0)
													{
														$page = 1;
													}
												if($page > $total)
													{
														$page = $total;
													}
												$past = intval($allPages/$pages);
												$start = $page*$pages-$pages;
												$browser1 = $mysqli->query("SELECT DISTINCT `browser`, COUNT(`browser`) AS `count` FROM `".$prefix."browsers` WHERE `sid` = '".$id."' GROUP BY `browser` ORDER BY `count` DESC LIMIT ".$start.",".$pages."");
												while($browser = $browser1->fetch_assoc())
													{
														$procent = ($browser['count'] / $all) * 100;
														echo '<tr class="table"><td>'.$browser['browser'].'</td><td>'.$browser['count'].'</td><td>'.round($procent, 2).'%</td></tr>';
													}
												echo '</table>';
												if($total > 1)
													{
														navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers/');
													}
											}
										else
											{
												echo '<div class="error">Статистика не найдена.</div>';
											}
                                                                                echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'"><strong>Информация о сайте</strong></a></div>';
                                                                                echo '<div class="main">';
										echo 'Статистика : ';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/all">Общая</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/hours">По часам</a> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/month">По месяцам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/days">По дням</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators">По операторам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online">По Онлайн</a> | <strong>По браузерам</strong> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/compression">По Сжатию</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country">По Странам</a><br/>';
										echo '</div>';
										$body = ob_get_contents();
										ob_end_clean();
										if($cache['browsersStats'] > 0)
											{
												wCache($body, 'statsBrowsers_'.$id.'_'.$pageCache.'.cache');
												echo $body;
											}
										else
											{
												echo $body;
											}
									}
						break;
						/* Статистика по компрессии */
						case 'compression':
						$pageCache = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '1';
						if (file_exists('Cache/statsCompression_'.$id.'.cache') AND ($time-60) < filemtime('Cache/statsCompression_'.$id.'.cache') AND $cache['compressionStats'] > 0)
									{
										echo implode('', file ('Cache/statsCompression_'.$id.'.cache'));
									}
								else
									{
										ob_start();
										$all = $mysqli->query("SELECT * FROM `".$prefix."compression` WHERE `sid` = '".$id."'")->num_rows;
										if($all > 0)
											{
												echo '<tr class="table_name">
												<table width="100%" border="0" cellspacing="1" cellpadding="2">
												<tr class="table_name">
												<td>Сжатие</td><td>Хосты</td><td>Проценты</td>
												</tr>
												';
												$total = intval(($all-1)/$pages)+1;
												$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
												if(empty($page) OR $page < 0)
													{
														$page = 1;
													}
												if($page > $total)
													{
														$page = $total;
													}
												$past = intval($all/$pages);
												$start = $page*$pages-$pages;
												$compressionYes = $mysqli->query("SELECT DISTINCT `compression`, COUNT(`compression`) AS `count` FROM `".$prefix."compression` WHERE `sid` = '".$id."' AND `compression` = '1' GROUP BY `compression` ORDER BY `count`")->fetch_assoc();
												$compressionNo = $mysqli->query("SELECT DISTINCT `compression`, COUNT(`compression`) AS `count` FROM `".$prefix."compression` WHERE `sid` = '".$id."' AND `compression` = '0' GROUP BY `compression` ORDER BY `count`")->fetch_assoc();
												$procentYes = ($compressionYes['count'] / $all) * 100;
												$procentNo = ($compressionNo['count'] / $all) * 100;
												$countYes = ($compressionYes['count'] == '') ? '0' : $compressionYes['count'];
												$countNo = ($compressionNo['count'] == '') ? '0' : $compressionNo['count'];
												echo '<tr class="table"><td>Поддерживается</td> <td>'.$countYes.'</td> <td>'.round($procentYes, 1).'%</td></tr>';
												echo '<tr class="table"><td>Не поддерживается</td> <td>'.$countNo.'</td> <td>'.round($procentNo, 1).'%</td></tr>';
												echo '</table>';
											}
										else
											{
												echo '<div class="error">Статистика не найдена.</div>';
											}
                                                                                echo '<div class="title"><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'"><strong>Информация о сайте</strong></a></div>';
                                                                                echo '<div class="main">';
										echo 'Статистика : ';
										echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/all">Общая</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/hours">По часам</a> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/month">По месяцам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/days">По дням</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators">По операторам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online">По Онлайн</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers">По браузерам</a> | <strong>По Сжатию</strong> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country">По Странам</a><br/>';
										echo '</div>';
										$body = ob_get_contents();
										ob_end_clean();
										if($cache['compressionStats'] > 0)
											{
												wCache($body, 'statsCompression_'.$id.'.cache');
												echo $body;
											}
										else
											{
												echo $body;
											}
									}
						break;
						/* Статистика по странам */
						case 'country':
						$pageCache = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '1';
						if (file_exists('Cache/statsCountry_'.$id.'_'.$pageCache.'.cache') AND ($time-60) < filemtime('Cache/statsCountry_'.$id.'_'.$pageCache.'.cache') AND $cache['countryStats'] > 0)
							{
								echo implode('', file ('Cache/statsCountry_'.$id.'_'.$pageCache.'.cache'));
							}
						else
							{
								ob_start();
								$allPages = $mysqli->query("SELECT DISTINCT `country` FROM `".$prefix."country` WHERE `sid` = '".$id."'")->num_rows;
								$all = $mysqli->query("SELECT `country` FROM `".$prefix."country` WHERE `sid` = '".$id."'")->num_rows;
								if($all > 0)
									{
										echo '<tr class="table_name">
										<table width="100%" border="0" cellspacing="1" cellpadding="2">
										<tr class="table_name">
										<td>Страна</td><td>Хосты</td><td>Проценты</td>
										</tr>
										';
										$total = intval(($allPages-1)/$pages)+1;
										$page = (isset($_GET['page'])) ? abs(intval($_GET['page'])) : '';
										if(empty($page) OR $page < 0)
											{
												$page = 1;
											}
										if($page > $total)
											{
												$page = $total;
											}
										$past = intval($allPages/$pages);
										$start = $page*$pages-$pages;
										$coun = $mysqli->query("SELECT DISTINCT `country`, COUNT(`country`) AS `count` FROM `".$prefix."country` WHERE `sid` = '".$id."' GROUP BY `country` ORDER BY `count` DESC LIMIT ".$start.",".$pages."");
										while($country = $coun->fetch_assoc())
											{
												$procent = ($country['count'] / $all) * 100;
												echo '<tr class="table"><td>'.$country['country'].'</td><td>'.$country['count'].'</td><td>'.round($procent, 2).'%</td></tr>';
											}
										echo '</table>';
										if($total > 1)
											{
												navigation($total,$page,'http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/country/');
											}
									}
								else
									{
										echo '<div class="error">Статистика не найдена.</div>';
									}
echo '<div class="title" align="center"><a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'"><strong>Информация о сайте</strong></a></div>';
echo '<div class="main" align="center">';
								echo 'Статистика : ';
								echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/all">Общая</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/hours">По часам</a> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/month">По месяцам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/days">По дням</a> |  <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/operators">Общая</a>| <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/online">По Онлайн</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/browsers">По браузерам</a> | <a href="http://'.$_SERVER['HTTP_HOST'].'/stats/'.$id.'/compression">По Сжатию</a> | <strong>По Странам</strong><br/>';
								echo '</div>';
								$body = ob_get_contents();
								ob_end_clean();
								if($cache['countryStats'] > 0)
									{
										wCache($body, 'statsCountry_'.$id.'_'.$pageCache.'.cache');
										echo $body;
									}
								else
									{
										echo $body;
									}
							}
						break;
	}
?>