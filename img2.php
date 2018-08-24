<?php
include("Sys/connect.php");
include("Sys/core.php");
include("Sys/function.php");
$sait = $mysqli->query("SELECT `id`,`ban`,`status`,`imageOther`,`hits`,`hosts` FROM `".$prefix."sait` WHERE `id` = '".$id."'");
if($sait->num_rows > 0)
	{
		$row = $sait->fetch_assoc();
		if($row['ban'] == 0)
			{
				if($row['status'] == 1)
					{
								$day = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
								/*if(!isset($_COOKIE['sait'.$id]))
									{
										SetCookie('sait'.$id,$time,$day, '/');
										$Cookie = 0;
									}
								else
									{
										$Cookie = 1;
									}*/
								if(isset($_SESSION['topStat']))
									{
										$topStat = explode('|',$_SESSION['topStat']);
										if($topStat[0] == date('Y-m-d') AND $topStat[1] == $id)
											{
												$Session = 1;
											}
										else
											{
												$Session = 0;
												$_SESSION['topStat'] = date('Y-m-d').'|'.$id;
											}
									}
								else
									{
										$Session = 0;
										$_SESSION['topStat'] = date('Y-m-d').'|'.$id;
									}
								$image = imagecreatefrompng('Counters/'.$row['imageOther'].'.png');
								$imType = $mysqli->query("SELECT `type` FROM `".$prefix."images` WHERE `name` = '".$row['imageOther']."'")->fetch_assoc();
								$oldDay = mktime(23, 59, 59, date('m'), (date('d')-1), date('Y'));
								$operator = $mysqli->query("SELECT `operator`,`country` FROM `".$prefix."ip` WHERE INET_ATON('".$ip."') BETWEEN `start` AND `finish`");
								if($operator->num_rows > 0)
									{
										$operatorRow = $operator->fetch_array();
										$operator = $operatorRow['operator'];
										$country = $operatorRow['country'];
									}
								else
									{
										$operator = 'Неизвестно';
										$country = 'Неизвестно';
									}
								if(mobile() == true)
									{
										$query = $mysqli->query("SELECT * FROM `".$prefix."shows` WHERE `sid` = '".$id."' AND `ip` = INET_ATON('".$ip."') AND `time` > '".$oldDay."' AND `browser` = '".$ua."'");
										if($query->num_rows == 0 AND $Session == 0)
											{
												$mysqli->query("INSERT INTO `".$prefix."shows` (`sid` ,`time` ,`ip` ,`browser`) VALUES ('".$id."', '".$time."', INET_ATON('".$ip."'), '".$ua."')");
												$mysqli->query("UPDATE `".$prefix."sait` SET `hosts` = (`hosts` + 1), `hits` = (`hits` + 1), `allHosts` = (`allHosts` + 1), `allHits` = (`allHits` + 1) WHERE `id` = '".$id."'");
												$mysqli->query("UPDATE `".$prefix."stats` SET `value` = (`value` + 1) WHERE `name` = 'hosts'");
												$mysqli->query("UPDATE `".$prefix."stats` SET `value` = (`value` + 1) WHERE `name` = 'allHosts'");
												$day = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
												if($day->num_rows > 0)
													{
														$dayRow = $day->fetch_assoc();
														if(!empty($dayRow[''.date("d").'']))
															{
																$dayArray = explode('|',$dayRow[''.date("d").'']);
																$hosts = $dayArray[0] + 1;
																$hits = $dayArray[1] + 1;
																$mysqli->query("UPDATE `".$prefix."days` SET `".date("d")."` = '".$hosts."|".$hits."|".$dayArray[2]."|".$dayArray[3]."' WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
															}
														else
															{
																$mysqli->query("UPDATE `".$prefix."days` SET `".date("d")."` = '1|1|0|0' WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
															}
													}
												else
													{
														$mysqli->query("INSERT INTO `".$prefix."days` SET `sid` = '".$id."', `month` = '".date("m")."', `".date("d")."` = '1|1|0|0'");
													}
												$month = $mysqli->query("SELECT * FROM `".$prefix."month` WHERE `sid` = '".$id."'");
												if($month->num_rows > 0)
													{
														$monthRow = $month->fetch_assoc();
														if(!empty($monthRow[''.date("m").'']))
															{
																$monthArray = explode('|',$monthRow[''.date("m").'']);
																$hosts = $monthArray[0] + 1;
																$hits = $monthArray[1] + 1;
																$mysqli->query("UPDATE `".$prefix."month` SET `".date("m")."` = '".$hosts."|".$hits."|".$monthArray[2]."|".$monthArray[3]."' WHERE `sid` = '".$id."'");
															}
														else
															{
																$mysqli->query("UPDATE `".$prefix."month` SET `".date("m")."` = '1|1|0|0' WHERE `sid` = '".$id."'");
															}
													}
												else
													{
														$mysqli->query("INSERT INTO `".$prefix."month` SET `sid` = '".$id."', `".date("m")."` = '1|1|0|0'");
													}
												$hour = (int)date("H");
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
												$hours = $mysqli->query("SELECT * FROM `".$prefix."hours` WHERE `sid` = '".$id."'");
												if($hours->num_rows > 0)
													{
														$hoursRow = $hours->fetch_assoc();
														if(!empty($hoursRow[''.$hourD.'']))
															{
																$hoursArray = explode('|',$hoursRow[''.$hourD.'']);
																$hosts = $hoursArray[0] + 1;
																$hits = $hoursArray[1] + 1;
																$mysqli->query("UPDATE `".$prefix."hours` SET `".$hourD."` = '".$hosts."|".$hits."|".$hoursArray[2]."|".$hoursArray[3]."' WHERE `sid` = '".$id."'");
															}
														else
															{
																$mysqli->query("UPDATE `".$prefix."hours` SET `".$hourD."` = '1|1|0|0' WHERE `sid` = '".$id."'");
															}
													}
												else
													{
														$mysqli->query("INSERT INTO `".$prefix."hours` SET `sid` = '".$id."', `".$hourD."` = '1|1|0|0'");
													}
												$mysqli->query("INSERT INTO `".$prefix."operators` (`sid` ,`operator` ,`ip`) VALUES ('".$id."', '".$operator."', '".$ip."')");
												$mysqli->query("INSERT INTO `".$prefix."browsers` (`sid` ,`browser` ,`ip`) VALUES ('".$id."', '".$ua."', '".$ip."')");
												$mysqli->query("INSERT INTO `".$prefix."country` (`sid` ,`country` ,`ip`) VALUES ('".$id."', '".$country."', '".$ip."')");
												$mysqli->query("INSERT INTO `".$prefix."compression` (`sid` ,`compression` ,`ip`) VALUES ('".$id."', '".compression()."', '".$ip."')");
											}
										else
											{
												$mysqli->query("UPDATE `".$prefix."stats` SET `value` = (`value` + 1) WHERE `name` = 'hits'");
												$mysqli->query("UPDATE `".$prefix."stats` SET `value` = (`value` + 1) WHERE `name` = 'allHits'");
												$day = $mysqli->query("SELECT * FROM `".$prefix."days` WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
												if($day->num_rows > 0)
													{
														$dayRow = $day->fetch_assoc();
														if(!empty($dayRow[''.date("d").'']))
															{
																$dayArray = explode('|',$dayRow[''.date("d").'']);
																$hits = $dayArray[1] + 1;
																$mysqli->query("UPDATE `".$prefix."days` SET `".date("d")."` = '".$dayArray[0]."|".$hits."|".$dayArray[2]."|".$dayArray[3]."' WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
															}
														else
															{
																$mysqli->query("UPDATE `".$prefix."days` SET `".date("d")."` = '0|1|0|0' WHERE `sid` = '".$id."' AND `month` = '".date("m")."'");
															}
													}
												else
													{
														$mysqli->query("INSERT INTO `".$prefix."days` SET `sid` = '".$id."', `month` = '".date("m")."', `".date("d")."` = '0|1|0|0'");
													}
												$month = $mysqli->query("SELECT * FROM `".$prefix."month` WHERE `sid` = '".$id."'");
												if($month->num_rows > 0)
													{
														$monthRow = $month->fetch_assoc();
														if(!empty($monthRow[''.date("m").'']))
															{
																$monthArray = explode('|',$monthRow[''.date("m").'']);
																$hits = $monthArray[1] + 1;
																$mysqli->query("UPDATE `".$prefix."month` SET `".date("m")."` = '".$monthArray[0]."|".$hits."|".$monthArray[2]."|".$monthArray[3]."' WHERE `sid` = '".$id."'");
															}
														else
															{
																$mysqli->query("UPDATE `".$prefix."month` SET `".date("m")."` = '0|1|0|0' WHERE `sid` = '".$id."'");
															}
													}
												else
													{
														$mysqli->query("INSERT INTO `".$prefix."month` SET `sid` = '".$id."', `".date("m")."` = '0|1|0|0'");
													}
												if(date("H") == 23)
													{
														$hourD = '23:00-00:00';
													}
												else
													{
														$hourD = ''.date("H").':00-'.(date("H") + 1).':00';
													}
												$hours = $mysqli->query("SELECT * FROM `".$prefix."hours` WHERE `sid` = '".$id."'");
												if($hours->num_rows > 0)
													{
														$hoursRow = $hours->fetch_assoc();
														if(!empty($hoursRow[''.$hourD.'']))
															{
																$hoursArray = explode('|',$hoursRow[''.$hourD.'']);
																$hits = $hoursArray[1] + 1;
																$mysqli->query("UPDATE `".$prefix."hours` SET `".$hourD."` = '".$hoursArray[0]."|".$hits."|".$hoursArray[2]."|".$hoursArray[3]."' WHERE `sid` = '".$id."'");
															}
														else
															{
																$mysqli->query("UPDATE `".$prefix."hours` SET `".$hourD."` = '0|1|0|0' WHERE `sid` = '".$id."'");
															}
													}
												else
													{
														$mysqli->query("INSERT INTO `".$prefix."hours` SET `sid` = '".$id."', `".$hourD."` = '0|1|0|0'");
													}
												$mysqli->query("UPDATE `".$prefix."sait` SET `hits` = (`hits` + 1), `allHits` = (`allHits` + 1) WHERE `id` = '".$id."'");
											}
									}
										if ($mysqli->query("SELECT * FROM `".$prefix."saitsOnline` WHERE `ip` = INET_ATON('".$ip."') AND `ua` = '".$ua."' AND `time` > '".($time-180)."' AND `sid` = '".$id."'")->num_rows == 1)
											{
												$mysqli->query("UPDATE `".$prefix."saitsOnline` SET `time` = '".$time."' WHERE `ip` = INET_ATON('".$ip."') AND `ua` = '".$ua."' AND `time` > '".($time-180)."' AND `sid` = '".$id."'");
											}
										else
											{
												$mysqli->query("DELETE FROM `".$prefix."saitsOnline` WHERE `time` < '".($time-180)."' AND `sid` = '".$id."'");
												$mysqli->query("INSERT INTO `".$prefix."saitsOnline` (`sid`,`ip`, `ua`, `time`) values('".$id."',INET_ATON('".$ip."'), '".$ua."', '".$time."')");
											}
								if($imType['type'] == 'big')
									{
										$hits = 72 - (strlen($row['hits']) * 5);
										$hosts = 32 - (strlen($row['hosts']) * 5);
										$black = imagecolorallocate($image, 000, 000, 000);
										ImageString($image,1,$hosts,15,$row['hosts'],$black);
										ImageString($image,1,$hits,15,$row['hits'],$black);
									}
					}
				else
					{
						$image = imagecreatefrompng('Counters/noactiv.png');
					}
			}
		else
			{
				$image = imagecreatefrompng('Counters/block.png');
			}
	}
else
	{
		$image = imagecreatefrompng('Counters/block.png');
	}
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>