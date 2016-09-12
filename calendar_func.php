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
	echo "									<td align=\"center\">\n";
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

function showDay_Headers() {
	$day_ar	=array(0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday');
	
	for ($d=0; $d<=6; $d++) {
		$tday=$day_ar[$d];
		echo "<td align=\"center\"><b>".$tday."</b></td>\n";
	}
}

function showDay_full($day,$month,$year,$secid,$appts,$callb) {
	//var_dump($appts);
	$cdate	=date("j");
	$cmonth	=date("n");
	$tdb	="gray";
	$apperr	="&nbsp;";
	$pday="";
		
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
	if (isset($day) && $day!=0) {
		echo "							<table class=\"outer\" width=\"125px;\" border=$brdr>\n";
		echo "								<tr>\n";
		echo "									<td class=\"".$bgc."\" align=\"center\" >\n";
		echo "										<table height=\"110\" width=\"100%\" border=$brdr>\n";
		echo "											<tr>\n";
		echo "												<td height=\"15px\" width=\"15px\" align=\"left\" valign=\"top\"><b>".$day."</b></td>\n";
		echo "												<td align=\"right\" valign=\"top\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td align=\"left\" valign=\"top\"></td>\n";
		echo "												<td align=\"left\" valign=\"top\">\n";
		//print_r ($callb);
		echo "													<table width=\"100%\" border=$brdr>\n";
		
		$ti=0;
		//if ($nrowA > 0) {
		if (isset($callb[$day]) and  count($callb[$day]) > 0) {
			foreach ($callb[$day] as $nc=>$vc) {
				$uidA  =md5(session_id().time().$vc['cid']).".".$_SESSION['securityid'];
				
				$qry0a   = "select count(id) as cntid from chistory where custid=".$vc['cid'].";";
				$res0a   = mssql_query($qry0a);
				$row0a 	= mssql_fetch_array($res0a);
				
				$tdb="magenta";
				echo "														<tr>\n";
				echo "															<td class=\"".$tdb."\" align=\"right\" width=\"5\"><img src=\"images/pixel.gif\"></td>\n";
				echo "															<td class=\"".$tdb."\" align=\"left\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
				echo "															<td class=\"".$tdb."\" align=\"right\">".$vc['clname']."</td>\n";
				echo "															<td class=\"".$tdb."\" align=\"center\">\n";
				echo "                        										<form method=\"POST\">\n";
				echo "                           									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "                           									<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
				echo "                           									<input type=\"hidden\" name=\"cid\" value=\"".$vc['cid']."\">\n";
				echo "                           									<input type=\"hidden\" name=\"uid\" value=\"".$uidA."\">\n";
				echo "                              								<input class=\"transnb\" type=\"submit\" value=\"".$row0a['cntid']."\" title=\"Click to open Customer Info & Comments\">\n";
				echo "                        										</form>\n";
				echo "															</td>\n";
				echo "														</tr>\n";
				$ti++;
			}
		}
		
		if (isset($appts[$day]) and  count($appts[$day]) > 0) {
		//if ($nrow > 0) {
			//while ($row = mssql_fetch_array($res)) {
			foreach ($appts as $na=>$va) {
				/*
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
				*/
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
		echo "							<table class=\"outer\" width=\"125px;\" border=$brdr>\n";
		echo "								<tr>\n";
		echo "									<td class=\"white\" align=\"center\" >\n";
		echo "										<table height=\"110\" width=\"100%\" border=$brdr>\n";
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

function showWeek_full($sday,$month,$year,$secid,$cadates) {
	global $sdayset,$fdayset,$totaldayscmon;
	echo "					<tr>\n";

	for ($d=0; $d<=6; $d++) {
		if ($sday != $d && $sdayset == 0 && $fdayset == 0) {
			showDay_full(0,$month,$year,$secid,$cadates);
		}
		elseif ($sday == $d && $sdayset == 0 && $fdayset == 0) {
			showDay_full(1,$month,$year,$secid,$cadates);
			$sdayset=1;
			$fdayset=1;
		}
		else {
			$fdayset=$fdayset+1;

			if ($fdayset <= $totaldayscmon)
			{
				showDay_full($fdayset,$month,$year,$secid,$cadates);
			}
			else
			{
				showDay_full(0,$month,$year,$secid,$cadates);
			}
		}
	}

	echo "					</tr>\n";
}

function getCalAppnts($oid,$secid,$month,$year) {
	$out	= array();
	$qry	= "select cid,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,apptmnt,clname,custid from cinfo where officeid=".(int) $oid." and securityid=".(int) $secid." and appt_mo=".(int) $month." and appt_yr=".(int) $year." and dupe!=1 order by appt_da,appt_pa,appt_hr,appt_mn;";
	$res	= mssql_query($qry);
	
	while ($row = mssql_fetch_array($res)) {
		$out[$row['appt_da']][$row['appt_hr']][$row['appt_mn']][$row['appt_pa']]=array('cid'=>$row['custid'],'clname'=>$row['clname']);
	}
	
	return $out;
}

function getCallbacks($oid,$secid,$month,$year) {
	$out = array();	
	$qry = "select cid,hold_da,clname from cinfo where officeid=".(int) $oid." and securityid=".(int) $secid." and hold_mo=".(int) $month." and hold_yr=".(int) $year." and dupe!=1 and hold=1 order by hold_da;";
	$res = mssql_query($qry);
	
	//echo $qry.'<br>';
	
	while ($row = mssql_fetch_array($res)) {
		$out[$row['hold_da']][]=array('cid'=>$row['cid'],'clname'=>$row['clname']);
	}
	
	return $out;
}

function showMonth_full() {
	// Globals
	global $sdayset,$fdayset,$totaldayscmon;
	//Constants
	$sdayset=0;
	$fdayset=0;
	$cdate	=date("j");
	$cmonth	=date("n");
	$cyear	=date("Y");
	$acclist=explode(",",$_SESSION['aid']);
	
	$oid	=(isset($_SESSION['officeid']) and $_SESSION['officeid']!=0)?$_SESSION['officeid']:null;
	$secid	=(isset($_REQUEST['secid']) and $_REQUEST['secid']!=0)?$_REQUEST['secid']:null;
	
	$day	=(empty($_REQUEST['day']))?$cdate:$_REQUEST['day'];
	$month	=(empty($_REQUEST['month']))?$cmonth:$_REQUEST['month'];
	$year	=(empty($_REQUEST['year']))?$cyear:$_REQUEST['year'];
	
	$sday			=date("w", mktime(0, 0, 0, $month, 1, $year));
	$tday			=date("D", mktime(0, 0, 0, $month, $day, $year));
	$tmonth			=date("F", mktime(0, 0, 0, $month, $day, $year));
	$totaldayscmon	=date("t", mktime(0, 0, 0, $month, $day, $year));
	
	$appts	=array();
	$callb	=array();	
	$mo_ar	=array(1=>'Janurary',2=>'Feburary',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
	$day_ar	=array(0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday');

	$qry0 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($secid!=null) {
		$appts=getCalAppnts($oid,$secid,$month,$year);
		$callb=getCallbacks($oid,$secid,$month,$year);
	}

	/*
	echo '<pre>';
	echo 'Appointments:<br>';
	print_r($appts);
	echo 'Callbacks:<br>';
	print_r($callb);
	echo '</pre>';
	*/
	
	echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
	echo "<table width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\"><b>Event</b> Status Codes:</td>\n";
	echo "		<td align=\"center\" class=\"lightgreen\" width=\"100\"><b>Appointment</b></td>\n";
	echo "		<td align=\"center\" class=\"magenta\" width=\"100\"><b>Call Back</b></td>\n";
	echo "		<td align=\"right\" width=\"20px\">\n";
	echo "			<div class=\"JMStooltip\" title=\"Select the SalesRep and Period then click Display\">\n";	
	echo "				<img src=\"images/help.png\">\n";
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	
	echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
	echo "<table width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\">\n";
	echo "		<form method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"showcalendar\">\n";
	echo "							<table width=\"100%\">\n";
	echo "								<tr>\n";
	echo "									<td align=\"left\">\n";
	echo "										<b>Appointment Calendar</b>";
	echo "									</td>\n";
	echo "									<td align=\"right\" valign=\"top\">\n";

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

	for ($m=1; $m<=12; $m++) {
		$tm=$mo_ar[$m];

		if ($m==$month) {
			echo "										<option value=\"".$m."\" SELECTED>".$tm."</option>\n";
		}
		else {
			echo "										<option value=\"".$m."\">".$tm."</option>\n";
		}
	}

	echo "							</select>\n";
	echo "							<select name=\"year\">\n";

	for ($y=$cyear-2; $y<=$cyear+2; $y++) {
		if ($y==$year) {
			echo "										<option value=\"".$y."\" SELECTED>".$y."</option>\n";
		}
		else {
			echo "										<option value=\"".$y."\">".$y."</option>\n";
		}
	}

	echo "							</select>\n";
	echo "							<button class=\"btnsysmenu\">Display</button>\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	
	// Weeks/Days Table
	echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
	echo "	<table width=\"950px\">\n";
	echo "		<tr>\n";

	for ($d=0; $d<=6; $d++) {
		$tday=$day_ar[$d];
		echo "<td align=\"center\"><b>".$tday."</b></td>\n";
	}

	echo "		</tr>\n";

	for ($w=0; $w<=5; $w++) {
		//showWeek_full($sday,$month,$year,$secid);
		echo "					<tr>\n";

		for ($d=0; $d<=6; $d++) {
			if ($sday != $d && $sdayset == 0 && $fdayset == 0) {
				showDay_full(0,$month,$year,$secid,$appts,$callb);
			}
			elseif ($sday == $d && $sdayset == 0 && $fdayset == 0) {
				showDay_full(1,$month,$year,$secid,$appts,$callb);
				$sdayset=1;
				$fdayset=1;
			}
			else {
				$fdayset=$fdayset+1;
	
				if ($fdayset <= $totaldayscmon)
				{
					showDay_full($fdayset,$month,$year,$secid,$appts,$callb);
				}
				else
				{
					showDay_full(0,$month,$year,$secid,$appts,$callb);
				}
			}
		}
	
		echo "					</tr>\n";
	}

	echo "	</table>\n";
	echo "</div>\n";
}

function showCalendar_full()
{
	showMonth_full();
}

?>