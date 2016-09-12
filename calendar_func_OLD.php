<?php
//calendar_func.php

function showDay_expanded()
{
	if ($day!=0)
	{
		$qry   = "select * from cinfo where officeid='".$_SESSION['officeid']."' and securityid='".$_SESSION['securityid']."' and appt_mo='".$_POST['month']."' and appt_da='".$_POST['day']."' and appt_yr='".$_POST['year']."' and dupe!=1 and hold!=1 and estid=0 order by appt_hr,appt_da ASC;";
		$res   = mssql_query($qry);
		$nrows = mssql_num_rows($res);
		$pday="<b>".$day."</b>";
	}
	else
	{
		$nrows =0;
		$pday="";
	}

	$brdr=1;
	echo "						<td align=\"center\">\n";
	echo "							<table class=\"outer\" width=\"100%\">\n";
	echo "								<tr>\n";
	echo "									<td class=\"gray\" align=\"center\">\n";
	echo "										<table height=\"110\" width=\"135\" border=$brdr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"left\" valign=\"top\">".$pday."</td>\n";
	//echo "											</tr>\n";
	//echo "											<tr>\n";
	echo "												<td align=\"left\" valign=\"top\">\n";
	echo "													<table>\n";

	if ($nrows > 0)
	{
		while ($row = mssql_fetch_array($res))
		{
			$hr	=$row['appt_hr'];
			$mn	=str_pad($row['appt_mn'],2,"0",STR_PAD_LEFT);

			if ($row['appt_pa']==1)
			{
				$pa="AM";
			}
			elseif ($row['appt_pa']==2)
			{
				$pa="PM";
			}
			else
			{
				$pa="";
			}

			echo "														<tr>\n";
			echo "															<td align=\"right\"><font style=\"font-size: 10pt;\">".$hr.":".$mn."".$pa."</font></td>\n";
			echo "															<td align=\"left\">&nbsp<font style=\"font-size: 10pt;\">".$row['clname']." ".$row['securityid']."</font></td>\n";
			echo "														</tr>\n";
		}
	}

	echo "													</table>\n";
	echo "												</td>\n";
	echo "											</tr>\n";
	echo "										</table>\n";
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "							</table>\n";
	echo "						</td>\n";
}

function showDay_Headers()
{
	for ($d=0; $d<=6; $d++)
	{
		//$td=$d+1;
		//$tday		=date("l", mktime(0, 0, 0, $td, 0, 0));

		if ($d==0)
		{
			$tday="Sunday";
		}
		elseif ($d==1)
		{
			$tday="Monday";
		}
		elseif ($d==2)
		{
			$tday="Tuesday";
		}
		elseif ($d==3)
		{
			$tday="Wednesday";
		}
		elseif ($d==4)
		{
			$tday="Thursday";
		}
		elseif ($d==5)
		{
			$tday="Friday";
		}
		elseif ($d==6)
		{
			$tday="Saturday";
		}
		else
		{
			$tday="";
		}

		echo "						<td align=\"center\">\n";
		echo "							<table class=\"outer\" width=\"100%\">\n";
		echo "								<tr>\n";
		echo "									<td class=\"gray\" align=\"center\">\n";
		echo "										<table height=\"10\" width=\"90\">\n";
		echo "											<tr>\n";
		echo "												<td align=\"center\" valign=\"top\">\n";

		echo "													<b>".$tday."</b>";

		echo "												</td>\n";
		echo "											</tr>\n";
		echo "										</table>\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</td>\n";
	}
}

function showDay_full($day,$month,$year,$secid)
{
	$cdate	=date("j");
	$cmonth	=date("n");
	$tdb	="gray";
	$apperr	="&nbsp;";

	if ($day!=0) {
		$qry   = "select cid,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,apptmnt,clname,custid from cinfo where officeid='".$_SESSION['officeid']."' and securityid='".$secid."' and appt_mo='".$month."' and appt_da='".$day."' and appt_yr='".$year."' and dupe!=1 order by appt_pa,appt_hr,appt_mn,appt_da ASC;";
		$res   = mssql_query($qry);
		$nrows = mssql_num_rows($res);
		
		//if ($_SESSION['securityid']==26)
		//{
		//	echo $qry.'<br>';
		//}
		
		$qryA = "select cid,securityid,officeid,clname,cfname from cinfo where officeid='".$_SESSION['officeid']."' and securityid='".$secid."' and hold_mo='".$month."' and hold_da='".$day."' and hold_yr='".$year."' and dupe!=1 and hold=1 order by clname ASC;";
		$resA	= mssql_query($qryA);
		$nrowA= mssql_num_rows($resA);
		
		$pday="<b>".$day."</b>";
	}
	else
	{
		$nrows =0;
		$pday="";
	}	 

	if ($day==$cdate && $month==$cmonth)
	{
		$bgc="ltgray";
	}
	else
	{
		$bgc="gray";
	}

	$brdr=0;
	echo "						<td align=\"center\">\n";
	
	if (isset($day) && $day!=0)
	{
		echo "							<table class=\"outer\" width=\"100%\" border=$brdr>\n";
		echo "								<tr>\n";
		echo "									<td class=\"".$bgc."\" align=\"center\" >\n";
		echo "										<table height=\"110\" width=\"125\" border=$brdr>\n";
		echo "											<tr>\n";
		echo "												<td height=\"20px\" width=\"20px\" align=\"left\" valign=\"top\">\n";
	
		if (isset($day) && $day!=0)
		{
			echo '<b>'.$day.'</b>';
		}
	
		echo "												</td>\n";	
		echo "												<td height=\"20px\" align=\"right\" valign=\"top\">\n";
		echo "												</td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td align=\"left\" valign=\"top\"></td>\n";
		echo "												<td align=\"left\" valign=\"top\">\n";
		echo "													<table width=\"100%\" border=$brdr>\n";
		
		$ti=0;
		if ($nrowA > 0)
		{
			while ($rowA = mssql_fetch_array($resA))
			{
				$uidA  =md5(session_id().time().$rowA['cid']).".".$_SESSION['securityid'];
				
				$qry0a   = "select count(id) as cntid from chistory where custid=".$rowA['cid'].";";
				$res0a   = mssql_query($qry0a);
				$row0a 	= mssql_fetch_array($res0a);
				
				$tdb="magenta";
				echo "														<tr>\n";
				echo "															<td class=\"".$tdb."\" align=\"right\" width=\"5\"><img src=\"images/pixel.gif\"></td>\n";
				echo "															<td class=\"".$tdb."\" align=\"left\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
				echo "															<td class=\"".$tdb."\" align=\"right\">".$rowA['clname']."</td>\n";
				echo "															<td class=\"".$tdb."\" align=\"center\">\n";
				echo "                        										<form method=\"POST\">\n";
				echo "                           									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "                           									<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
				echo "                           									<input type=\"hidden\" name=\"cid\" value=\"".$rowA['cid']."\">\n";
				echo "                           									<input type=\"hidden\" name=\"uid\" value=\"".$uidA."\">\n";
				echo "                              								<input class=\"transnb\" type=\"submit\" value=\"".$row0a['cntid']."\" title=\"Click to open Customer Info & Comments\">\n";
				echo "                        										</form>\n";
				echo "															</td>\n";
				echo "														</tr>\n";
				$ti++;
			}
		}
		
		if ($nrows > 0)
		{
			while ($row = mssql_fetch_array($res))
			{
				$uid  =md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];
			  
				$qry0   = "select count(id) as cntid from chistory where custid=".$row['cid'].";";
				$res0   = mssql_query($qry0);
				$row0 	= mssql_fetch_array($res0);
				//$nrows0 = mssql_num_rows($res);
			  
			  
				if ($ti < 6)
				{
					$hr	=$row['appt_hr'];
					$mn	=str_pad($row['appt_mn'],2,"0",STR_PAD_LEFT);
	
					if ($row['appt_pa']==1)
					{
						$pa="AM";
					}
					elseif ($row['appt_pa']==2)
					{
						$pa="PM";
					}
					else
					{
						$pa="";
					}
	
					$tdb="lightgreen";
					
					if ($hr == 0)
					{
						$apperr="<font color=\"red\">!</font>";
					}
	
					echo "														<tr>\n";
					echo "															<td class=\"".$tdb."\" align=\"right\" width=\"5\"><img src=\"images/pixel.gif\"></td>\n";
					echo "															<td class=\"".$tdb."\" align=\"right\" width=\"25\">".$hr.":".$mn."".$pa."</font></td>\n";
					echo "															<td class=\"".$tdb."\" align=\"right\">".$row['clname']."</td>\n";
					echo "															<td class=\"".$tdb."\" align=\"center\">\n";
					echo "                        										<form method=\"POST\">\n";
					echo "                           									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
					echo "                           									<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
					echo "                           									<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
					echo "                           									<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
					echo "                              								<input class=\"transnb\" type=\"submit\" value=\"".$row0['cntid']."\" title=\"Click to open Customer Info & Comments\">\n";
					echo "                        										</form>\n";
					echo "															</td>\n";
					echo "														</tr>\n";
				}
				elseif  ($ti==7)
				{
					echo "														<tr>\n";
					echo "															<td align=\"right\"></td>\n";
					echo "															<td align=\"left\">More...</td>\n";
					echo "														</tr>\n";
				}
				$ti++;
				$apperr	="&nbsp;";
			}
		}
	
		echo "													</table>\n";
		echo "												</td>\n";
		echo "											</tr>\n";
		echo "										</table>\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
	}
	else
	{
		echo "							<table class=\"outer\" width=\"100%\" border=$brdr>\n";
		echo "								<tr>\n";
		echo "									<td class=\"white\" align=\"center\" >\n";
		echo "										<table height=\"110\" width=\"135\" border=$brdr>\n";
		echo "											<tr>\n";
		echo "												<td height=\"20px\" width=\"20px\" align=\"left\" valign=\"top\">\n";	
		echo "													<img src=\"images/pixel.gif\">\n";
		echo "												</td>\n";
		echo "											</tr>\n";
		echo "										</table>\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
	}
	
	echo "						</td>\n";
}

function showWeek_full($sday,$month,$year,$secid)
{
	global $sdayset,$fdayset,$totaldayscmon;
	echo "					<tr>\n";

	for ($d=0; $d<=6; $d++)
	{
		if ($sday != $d && $sdayset == 0 && $fdayset == 0)
		{
			showDay_full(0,$month,$year,$secid);
		}
		elseif ($sday == $d && $sdayset == 0 && $fdayset == 0)
		{
			showDay_full(1,$month,$year,$secid);
			$sdayset=1;
			$fdayset=1;
		}
		else
		{
			$fdayset=$fdayset+1;

			if ($fdayset <= $totaldayscmon)
			{
				showDay_full($fdayset,$month,$year,$secid);
			}
			else
			{
				showDay_full(0,$month,$year,$secid);
			}
		}
	}

	echo "					</tr>\n";
}

function showMonth_full()
{
	// Globals
	global $sdayset,$fdayset,$totaldayscmon;
	//Constants
	$sdayset		=0;
	$fdayset		=0;
	$cdate			=date("j");
	$cmonth			=date("n");
	$cyear			=date("Y");
	$acclist		=explode(",",$_SESSION['aid']);

	if (empty($_POST['day']))
	{
		$day		=$cdate;
	}
	else
	{
		$day		=$_POST['day'];
	}

	if (empty($_POST['month']))
	{
		$month	=$cmonth;
	}
	else
	{
		$month	=$_POST['month'];
	}

	if (empty($_POST['year']))
	{
		$year		=$cyear;
	}
	else
	{
		$year		=$_POST['year'];
	}

	if (empty($_POST['secid']))
	{
		$secid		=$_SESSION['securityid'];
	}
	else
	{
		$secid		=$_POST['secid'];
	}

	$sday			=date("w", mktime(0, 0, 0, $month, 1, $year));
	$tday			=date("D", mktime(0, 0, 0, $month, $day, $year));
	$tmonth			=date("F", mktime(0, 0, 0, $month, $day, $year));
	$totaldayscmon	=date("t", mktime(0, 0, 0, $month, $day, $year));

	$qry0 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	echo "	<table align=\"center\" width=\"950px\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"right\">\n";
	echo "				<table class=\"outer\" width=\"100%\" align=\"right\">\n";
	echo "					<tr>\n";
	echo "                      <td align=\"right\" class=\"gray\"><b>Event</b> Status Codes:</td>\n";
	echo "                      <td align=\"center\" class=\"lightgreen\" width=\"100\"><b>Appointment</b></td>\n";
	echo "                      <td align=\"center\" class=\"magenta\" width=\"100\"><b>Call Back</b></td>\n";
	echo "						<td align=\"right\" class=\"gray\" width=\"20px\">\n";
	echo "							<div class=\"JMStooltip\" title=\"Select the SalesRep and Period then click Display\">\n";	
	echo "								<img src=\"images/help.png\">\n";
	echo "							</div>\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"center\">\n";
	echo "				<table class=\"outer\" width=\"100%\">\n";
	echo "					<tr>\n";
	echo "						<td class=\"gray\" align=\"center\">\n";
	// Calender Header Table
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"showcalendar\">\n";
	echo "							<table width=\"100%\">\n";
	echo "								<tr>\n";
	echo "									<td class=\"gray\" align=\"left\">\n";
	echo "										<b>Appointment Calendar</b>";
	echo "									</td>\n";
	echo "									<td class=\"gray\" align=\"right\" valign=\"top\">\n";

	if ($_SESSION['llev'] >= 4)
	{
		if ($nrow0 > 0)
		{
			echo "										<select name=\"secid\">\n";

			while ($row0 = mssql_fetch_array($res0))
			{
				if (in_array($row0['securityid'],$acclist))
				{
					$secl=explode(",",$row0['slevel']);
					if ($secl[6]!=0)
					{
						$ostyle="fontblack";
					}
					else
					{
						$ostyle="fontred";
					}

					if ($secid==$row0['securityid'])
					{
						echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row0['lname'].", ".$row0['fname']."</option>\n";
					}
					else
					{
						echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\">".$row0['lname'].", ".$row0['fname']."</option>\n";
					}
				}
			}

			echo "										</select>\n";
		}
	}

	echo "										<select name=\"month\">\n";

	for ($m=1; $m<=12; $m++)
	{
		if ($m==1)
		{
			echo $tm="Janurary";
		}
		elseif ($m==2)
		{
			echo $tm="Feburary";
		}
		elseif ($m==3)
		{
			echo $tm="March";
		}
		elseif ($m==4)
		{
			echo $tm="April";
		}
		elseif ($m==5)
		{
			echo $tm="May";
		}
		elseif ($m==6)
		{
			echo $tm="June";
		}
		elseif ($m==7)
		{
			echo $tm="July";
		}
		elseif ($m==8)
		{
			echo $tm="August";
		}
		elseif ($m==9)
		{
			echo $tm="September";
		}
		elseif ($m==10)
		{
			echo $tm="October";
		}
		elseif ($m==11)
		{
			echo $tm="November";
		}
		elseif ($m==12)
		{
			echo $tm="December";
		}
		else
		{
			echo $tm="";
		}

		if ($m==$month)
		{
			echo "										<option value=\"".$m."\" SELECTED>".$tm."</option>\n";
		}
		else
		{
			echo "										<option value=\"".$m."\">".$tm."</option>\n";
		}
		//unset($m);
	}

	echo "										</select>\n";
	echo "										<select name=\"year\">\n";

	for ($y=$cyear-2; $y<=$cyear+2; $y++)
	{
		if ($y==$year)
		{
			echo "										<option value=\"".$y."\" SELECTED>".$y."</option>\n";
		}
		else
		{
			echo "										<option value=\"".$y."\">".$y."</option>\n";
		}
	}

	echo "										</select>\n";
	echo "                              <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Display\">\n";
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "							</table>\n";
	echo "         		</form>\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"center\">\n";
	// Weeks/Days Table
	echo "				<table width=\"100%\">\n";
	echo "					<tr>\n";

	showDay_Headers();

	echo "					</tr>\n";

	for ($w=0; $w<=5; $w++)
	{
		showWeek_full($sday,$month,$year,$secid);
	}

	echo "				</table>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
}

function showCalendar_full()
{
	showMonth_full();
}

?>