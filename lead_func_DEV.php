<?php

//echo __FILE__.'<br>';

function get_AP_list_JSON()
{
	$dev_ar= array(2699999999999999999999999);
	$sdate	 =set_sdate();
	$nph_ar= array('**********','0000000000','none','N/A','na');
	
	$ap_ar=array();
	
	$qry = "
		select 
			C.cid,C.securityid,C.sidm,(C.clname +', '+ C.cfname) as cfullname,
			(select (fname +' '+ lname) from security where securityid=C.securityid) as srname,
			C.apptmnt as appt,
			C.scity as scity,
			C.szip1 as szip
		from 
			cinfo as C
		where
			C.officeid=".$_SESSION['officeid']."
			";
		
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			$qry  .= "	AND C.securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qry  .= "	AND C.securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
		}
	}		
	elseif ($_SESSION['llev'] < 4)
	{
		$qry .= "	and C.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry .= " 	
			and apptmnt BETWEEN '".date('m/d/y',(time() - 172800))."' and (getdate()+16)
			and appt_yr!=0
			and dupe!=1
		order by
			apptmnt asc,clname asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($_SESSION['securityid']==26999999999999999999999999999999999)
	{
		echo $qry;
	}
	
	if ($nrow > 0) {
		while ($row= mssql_fetch_array($res)) {
			$ap_ar[]=array('cid'=>$row['cid'],'cfullname'=>$row['cfullname'],'srname'=>$row['srname'],'scity'=>$row['scity'],'szip'=>$row['szip'],'cappt'=>date('m/d/y h:i a',strtotime($row['appt'])));
		}
	}
	
	return json_encode($ap_ar);
}

function LeadNew()
{
	//echo __FUNCTION__.'<br>';
	
	$officeid 	=$_SESSION['officeid'];
	$dates		=dateformat();
	$uid		=md5(session_id().time()).".".$_SESSION['securityid'];
	$acclist	=explode(",",$_SESSION['aid']);
	$curryr		=date("Y");
	$futyr 		=$curryr+1;

	//if ($_SESSION['llev'] >= 7)
	//{
	//	$qryA = "SELECT officeid,name,stax FROM offices ORDER BY name ASC;";
	//}
	//else
	//{
		$qryA = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	//}
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT stax,intro_etid as ietid FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[0]!=0)
	{
		$qryD = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resD = mssql_query($qryD);

		$qryE = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resE = mssql_query($qryE);
	}

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=2 AND statusid!=0 AND access!=9 AND provided=0 and (oid=0 or oid=".$_SESSION['officeid'].") ORDER by name ASC;";
	$resG = mssql_query($qryG);

	$hlpnd=1;

	echo "<script type=\"text/javascript\" src=\"js/jquery_leads_new.js?".time()."\"></script>\n";	
	echo "<table width=\"950px\" align=\"center\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "		<table width=\"100%\" align=\"center\">\n";
	echo "			<tr>\n";
	echo "				<td>\n";
	echo "				<form id=\"newlead\" method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"add\">\n";
	echo "				<input type=\"hidden\" name=\"recdate\" value=\"".$dates[1]."\">\n";
	echo "				<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "				<input type=\"hidden\" name=\"comments\" value=\"\">\n";
	
	if (isset($rowC[1]) and $rowC[1]!=0)
	{
		echo "				<input type=\"hidden\" name=\"intro_email\" id=\"intro_email\" value=\"1\">\n";
	}
	
	echo "					<table border=\"0\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "								<table border=\"0\" width=\"100%\">\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\" align=\"left\">\n";
	echo "											<div class=\"outerrnd\">\n";
	echo "											<table width=\"100%\">\n";
	echo "												<tr class=\"tblhd\">\n";
	echo "													<td align=\"left\"><b><b>Lead Entry</b></td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "											</div>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\">\n";
	echo "											<div class=\"outerrnd\">\n";
	echo "											<table border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Date</b>\n";
	echo "													<td align=\"left\">".$dates[0]."</td>\n";
	echo "													<td align=\"right\"><b>Office </b></td>\n";
	echo "													<td align=\"left\">\n";

	/*
	if ($_SESSION['llev'] >= 7)
	{
		echo "													<select name=\"site\" id=\"soid\">\n";
		while ($rowA = mssql_fetch_row($resA))
		{
			if ($_SESSION['officeid']==$rowA[0])
			{
				echo "												<option value=\"".$rowA[0]."\" SELECTED>".$rowA[1]."</option>\n";
			}
			else
			{
				echo "												<option value=\"".$rowA[0]."\">".$rowA[1]."</option>\n";
			}
		}
		echo "													</select>\n";
	}
	else
	{
	*/
		$rowA = mssql_fetch_row($resA);
		//print_r($rowA)."<BR>";
		echo "                                 	".$rowA[1]."<input type=\"hidden\" name=\"officeid\" id=\"soid\" value=\"".$rowA[0]."\">\n";
	//}

	echo "													</td>\n";
	echo "													<td align=\"right\"></td>\n";
	echo "													<td align=\"right\"><b>Salesrep</b> \n";

	if ($_SESSION['llev'] >= 4)
	{
		echo "														<select name=\"estorig\">\n";
		while ($rowB = mssql_fetch_row($resB))
		{
			if (in_array($rowB[0],$acclist))
			{
				$slev=explode(",",$rowB[3]);
				if ($slev[6]!=0)
				{
					if ($_SESSION['securityid']==$rowB[0])
					{
						echo "													<option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
					}
					else
					{
						echo "													<option value=\"".$rowB[0]."\">".$rowB[1]." ".$rowB[2]."</option>\n";
					}
				}
			}
		}
		echo "														</select>\n";

	}
	else
	{
		echo "                                 ".$_SESSION['fname']." ".$_SESSION['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$_SESSION['securityid']."\">\n";
	}

	echo "													</td>\n";
	echo "													<td width=\"20px\" align=\"right\">\n";

	HelpNode('cformadddatepanel',$hlpnd++);

	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "											</div>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<div class=\"outerrnd\">\n";
	echo "											<table border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\">\n";
	echo "											<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\"><b>Customer</b></td>\n";
	echo "													<td align=\"right\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "											<tr>\n";
		echo "												<td align=\"right\">Company Name</td>\n";
		echo "												<td align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"30\" name=\"cpname\" id=\"cpname\"></td>\n";
		echo "											</tr>\n";
		echo "												<tr>\n";
		echo "													<td width=\"100px\" align=\"right\"></td>\n";
		echo "													<td align=\"left\"><div id=\"CompanyNameData\"></div></td>\n";
		echo "												</tr>\n";
	}
	
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\"><font color=\"blue\">First Name</font></td>\n";
	echo "													<td align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"30\" id=\"cfname\" name=\"cfname\" autocomplete=\"off\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\"><font color=\"blue\">Last Name</font></td>\n";
	echo "													<td align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"30\" name=\"clname\" id=\"clname\" autocomplete=\"off\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\"></td>\n";
	echo "													<td align=\"left\"><div id=\"LeadNameData\"></div></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\"><span class=\"JMStooltip\" title=\"Input the Customer Home Phone without dashes or dots. e.g. 123456789\"><font color=\"blue\">Home Phone</font></span></td>\n";
	echo "													<td align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\" id=\"chome\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">Work Phone</td>\n";
	echo "													<td align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\" id=\"cwork\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">Cell Phone</td>\n";
	echo "													<td align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\" id=\"ccell\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">Fax</td>\n";
	echo "													<td align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" id=\"cfax\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">Best Phone</td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select name=\"cconph\" id=\"cconph\">\n";
	echo "															<option value=\"hm\">Home</option>\n";
	echo "															<option value=\"wk\">Work</option>\n";
	echo "															<option value=\"ce\">Cell</option>\n";
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">Contact Time</td>\n";
	echo "													<td align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"30\" name=\"ccontime\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\"><span class=\"JMStooltip\" title=\"Input <strong><b>NA</b></strong> in this field if you do not have, or the Customer will not provide, a valid email\"><font color=\"blue\">E-Mail</font></span></td>\n";
	echo "													<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"cemail\" size=\"30\" id=\"cemail\"></td>\n";
	echo "												</tr>\n";
	
	//if (($_SESSION['securityid']==26 or $_SESSION['securityid']==1950) && $rowC[1]!=0)
	if (isset($rowC[1]) and $rowC[1]!=0)
	{
		echo "												<tr>\n";
		echo "													<td align=\"right\"><input class=\"transnb JMStooltip\" type=\"checkbox\" name=\"introbypass\" value=\"1\" title=\"Check the box to prevent an Introduction Letter from being sent when adding the Lead\"></td>\n";
		echo "													<td align=\"left\"><div class=\"JMStooltip\" title=\"Check the box to prevent an Introduction Letter from being sent when adding the Lead\">Bypass Introduction Letter</td>\n";
		echo "												</tr>\n";
	}
	
	echo "												</table>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "											</div\n";
	echo "										</td>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<div class=\"outerrnd\">\n";
	echo "											<table border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\">\n";
	echo "											<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\"><b>Current Address</b></td>\n";
	echo "													<td align=\"right\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\"><font color=\"blue\">Street</font></td>\n";
	echo "													<td><input class=\"bboxb\" type=\"text\" size=\"50\" name=\"caddr1\" id=\"caddr1\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\"><font color=\"blue\">City</font></td>\n";
	echo "													<td><input class=\"bboxb\" type=\"text\" size=\"20\" name=\"ccity\" id=\"ccity\"> State <input class=\"bboxb\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" id=\"cstate\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\"><font color=\"blue\">Zip</font></td>\n";
	echo "													<td><input class=\"bboxb\" type=\"text\" size=\"6\" maxlength=\"5\" id=\"czip1\" name=\"czip1\">-<input class=\"bboxb\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\" id=\"czip2\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">Cnty/Twnshp</td>\n";
	echo "													<td>\n";

	if ($rowC[0]==0)
	{
		echo "												<input class=\"bboxb\" type=\"text\" size=\"18\" name=\"ccounty\" id=\"ccounty\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "												<select name=\"ccounty\" id=\"ccounty\">\n";
		while ($rowD = mssql_fetch_row($resD))
		{
			echo "												<option value=\"".$rowD[0]."\">".$rowD[2]."</option>\n";
		}
		echo "												</select>\n";
	}
	else
	{
		echo "											<input class=\"bboxb\" type=\"text\" size=\"18\" name=\"ccounty\" id=\"ccounty\">\n";
	}

	echo "												Map <input class=\"bboxb\" type=\"text\" size=\"10\" name=\"cmap\" id=\"cmap\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td colspan=\"2\" valign=\"top\"><b>Pool Site Address</b> <input class=\"transnb\" type=\"checkbox\" name=\"ssame\" id=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">Street</td>\n";
	echo "													<td><input class=\"bboxb\" type=\"text\" size=\"50\" name=\"saddr1\" id=\"saddr1\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">City</td>\n";
	echo "													<td><input class=\"bboxb\" type=\"text\" size=\"20\" name=\"scity\" id=\"scity\"> State <input class=\"bboxb\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" id=\"sstate\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">Zip</td>\n";
	echo "													<td><input class=\"bboxb\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" id=\"szip1\">-<input class=\"bboxb\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" id=\"szip2\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td width=\"100px\" align=\"right\">Cnty/Twnshp</td>\n";
	echo "													<td>\n";

	if ($rowC[0]==0)
	{
		echo "													<input class=\"bboxb\" type=\"text\" size=\"18\" name=\"scounty\" id=\"scounty\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "													<select name=\"scounty\" id=\"scounty\">\n";
		while ($rowE = mssql_fetch_row($resE))
		{
			echo "														<option value=\"".$rowE[0]."\">".$rowE[2]."</option>\n";
		}
		echo "														</select>\n";
	}
	else
	{
		echo "													<input class=\"bboxb\" type=\"text\" size=\"18\" name=\"scounty\" id=\"scounty\">\n";
	}

	echo "											Map <input class=\"bboxb\" type=\"text\" size=\"10\" name=\"smap\" id=\"smap\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "											</div>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"left\" valign=\"top\">\n";
	echo "											<div class=\"outerrnd\">\n";
	echo "											<table border=\"0\" width=\"100%\" height=\"100\">\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\">\n";
	echo "											<table class=\"transnb\" width=\"100%\">\n";
	echo "                           								<tr>\n";
	echo "													<td align=\"left\" valign=\"top\"><b>Appointment / Source</b></td>\n";
	echo "													<td align=\"right\">\n";
	echo "													</td>\n";
	echo "                           								</tr>\n";
	echo "                     									<tr>\n";
	echo "                        										<td colspan=\"2\" valign=\"top\">\n";
	echo "                           										<table border=\"0\" width=\"100%\">\n";
	echo "															<tr>\n";
	echo "																<td align=\"right\"><b>Date</b></td>\n";
	echo "																<td align=\"left\" valign=\"top\">\n";
	echo "																	<table border=0>\n";
	echo "																		<tr>\n";
	echo "																			<td valign=\"top\">\n";
	echo "                                             																<select name=\"appt_mo\" id=\"appt_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		echo "                                             																	<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "																			</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_da\" id=\"appt_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		echo "                                             																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "																			</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_yr\" id=\"appt_yr\">\n";
	echo "                                             																	<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr; $yr<=$futyr; $yr++)
	{
		echo "                                             																	<option value=\"".$yr."\">".$yr."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "																			</td>\n";
	echo "																		</tr>\n";
	echo "																	</table>\n";
	echo "                           														</td>\n";
	echo "                           													</tr>\n";
	echo "                           													<tr>\n";
	echo "																<td align=\"right\"><b>Time</b></td>\n";
	echo "																<td align=\"left\" valign=\"top\">\n";
	echo "																	<table border=0>\n";
	echo "																		<tr>\n";
	echo "																			<td align=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_hr\" id=\"appt_hr\">\n";

	for ($hr=0; $hr<=12; $hr++)
	{
		echo "                                             																<option value=\"".$hr."\">".$hr."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "                                             															</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_mn\" id=\"appt_mn\">\n";

	for ($mn=0; $mn<=60; $mn++)
	{
		echo "                                             																<option value=\"".$mn."\">".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "                                             															</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_pa\" id=\"appt_pa\">\n";
	echo "                                             																	<option value=\"1\">AM</option>\n";
	echo "                                             																	<option value=\"2\">PM</option>\n";
	echo "                                             																</select>\n";
	echo "                                             															</td>\n";
	echo "                                             														</tr>\n";
	echo "                                             													</table>\n";
	echo "                                             												</td>\n";
	echo "                                             											</tr>\n";
	echo "                                             											<tr>\n";
	echo "                                             												<td align=\"right\"><font color=\"blue\">Lead Source</font></td>\n";
	echo "                                             												<td align=\"left\">\n";
	echo "                                             													<select name=\"source\" id=\"source\">\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		echo "                                             													<option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
	}

	echo "                                             													</select>\n";
	echo "                                             												</td>\n";
	echo "                                             											</tr>\n";	
	echo "                                             										</table>\n";
	echo "                                             									</td>\n";
	echo "                                             								</tr>\n";
	echo "                                             							</table>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "											</div>\n";
	echo "                                             						</td>\n";
	echo "                                             						<td colspan=\"2\" align=\"right\" valign=\"top\">\n";
	echo "											<div class=\"outerrnd\">\n";
	echo "											<table width=\"100%\" height=\"100\">\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\">\n";
	echo "                                             							<table class=\"transnb\" width=\"100%\">\n";
	echo "                                             								<tr>\n";
	echo "                                             									<td align=\"left\" valign=\"top\"><b>Comments/Directions</b></td>\n";
	echo "																	<td align=\"right\">\n";
	echo "															</td>\n";
	echo "                                             								</tr>\n";
	echo "                                             								<tr>\n";
	echo "                                             									<td colspan=\"2\" valign=\"top\" align=\"left\">\n";
	echo "																<textarea name=\"comments\" rows=\"3\" cols=\"80\"></textarea>\n";
	echo "															</td>\n";
	echo "                                             								</tr>\n";
	echo "                                             							</table>\n";
	echo "                                             						</td>\n";
	echo "                                             					</tr>\n";
	echo "                                             				</table>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "											</div>\n";
	echo "                                             			</td>\n";
	echo "                                             		</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"left\" valign=\"top\">\n";
	echo "											<table class=\"transnb\" width=\"100%\" height=\"50\">\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\">\n";
	echo "											<div class=\"outerrnd\">\n";
	echo "											<table width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\">\n";
	echo "														<table class=\"transnb\" width=\"100%\">\n";
	echo "                           								<tr>\n";
	echo "																<td colspan=\"2\" align=\"left\" valign=\"top\"><b>Privacy Policy</b></td>\n";
	echo "                           								</tr>\n";
	echo "                           								<tr>\n";
	echo "													<td width=\"20px\" align=\"right\" valign=\"top\">\n";
	echo "														<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\">\n";
	echo "														<input type=\"hidden\" name=\"opt2\" value=\"0\">\n";
	echo "														<input type=\"hidden\" name=\"opt3\" value=\"0\">\n";
	echo "														<input type=\"hidden\" name=\"opt4\" value=\"0\">\n";
	echo "													</td>\n";
	echo "																<td align=\"left\">Customer does not wish to receive any future information about updates, special offers, or other communications regarding Blue Haven-related products, supplies, or services.</td>\n";	
	echo "                           								</tr>\n";
	echo "														</table>\n";
	echo "                                             						</td>\n";
	echo "                                             					</tr>\n";
	echo "                                             				</table>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "                           </tr>\n";
	echo "								</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	<td valign=\"top\" align=\"left\">\n";
	echo "		<table border=0>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "						<button class=\"leadBtns\" onClick=\"return VerifyLeadForm();\">Submit</button>\n";
	//echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add Lead\" onClick=\"return VerifyLeadForm();\" title=\"Click this button to Add the Lead\">\n";
	echo "					</div>\n";
	echo "				</td>\n";
	echo "			</form>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function showLeadListHdr($nrows=null) {
    echo "<script type=\"text/javascript\" src=\"js/jquery_lead_search_new.js?".time()."\"></script>\n";		
    echo "<table align=\"center\" width=\"950px\">\n";
    echo "	<tr>\n";
    echo "		<td align=\"left\">\n";
    echo "          <div class=\"outerrnd\">\n";
    echo "			<table width=\"100%\">\n";
    echo "				<tr>\n";
    echo "					<td align=\"left\"></td>\n";
    echo "					<td align=\"left\"><b>".$_SESSION['offname']."</b></td>\n";
    echo "					<td align=\"right\">Color Key:</td>\n";
    echo "					<td align=\"center\" class=\"lightgreen\" style=\"width:75px;\"><b>Appointment</b></td>\n";
    echo "					<td align=\"center\" class=\"magenta\" style=\"width:75px;\"><b>Call Back</b></td>\n";
    echo "					<td align=\"center\" class=\"yellow\" style=\"width:75px;\"><b>Aged 7 Days</b></td>\n";
    echo "				</tr>\n";
    echo "			</table>\n";
    echo "          </div>\n";
    echo "		</td>\n";
    echo "	</tr>\n";
    echo "	<tr>\n";
    echo "		<td>\n";
    echo "          <div id=\"lead_result_container\" class=\"outerrnd\" style=\"width:950px;\">\n";
	echo "				<table width=\"100%\">\n";
	echo "				<tr class=\"tblhd\">\n";
    echo "				<td align=\"center\"></td>\n";
    echo "				<td align=\"center\"><b>Lead ID</b></td>\n";
    echo "				<td align=\"left\" width=\"100\"><b>Name</b></td>\n";
    echo "				<td align=\"left\"><b>Phone</b></td>\n";
    echo "				<td align=\"left\"><b>Site</b></td>\n";
    echo "				<td align=\"left\"><b>Rep</b></td>\n";
	echo "				<td align=\"left\" width=\"150\"><b>Status</b></td>\n";
    echo "				<td align=\"left\" width=\"110\"><b>Dates</b></td>\n";
    echo "				<td align=\"left\" title=\"JMS LifeCycle\"><b>LifeCycle</b></td>\n";
    echo "				<td align=\"left\" title=\"Total Comments for this Lead\"><b>Cmnts</b></td>\n";
	echo "				<td class=\"noPrint\" align=\"right\"><span id=\"ls_res_cnt\"></span> Result(s)</td>\n";
	echo "				</tr>\n";
}

function showLeadListHdrQ($nrows=null) {
    echo "<script type=\"text/javascript\" src=\"js/jquery_lead_search_new.js?".time()."\"></script>\n";		
    echo "<table align=\"center\" width=\"950px\">\n";
    echo "	<tr>\n";
    echo "		<td align=\"left\">\n";
    echo "          <div class=\"outerrnd\">\n";
    echo "			<table width=\"100%\">\n";
    echo "				<tr>\n";
    echo "					<td align=\"left\"></td>\n";
    echo "					<td align=\"left\"><b>".$_SESSION['offname']."</b></td>\n";
    echo "					<td align=\"right\">Color Key:</td>\n";
    echo "					<td align=\"center\" class=\"lightgreen\" style=\"width:75px;\"><b>Appointment</b></td>\n";
    echo "					<td align=\"center\" class=\"magenta\" style=\"width:75px;\"><b>Call Back</b></td>\n";
    echo "					<td align=\"center\" class=\"yellow\" style=\"width:75px;\"><b>Aged 7 Days</b></td>\n";
    echo "				</tr>\n";
    echo "			</table>\n";
    echo "          </div>\n";
    echo "		</td>\n";
    echo "	</tr>\n";
    echo "	<tr>\n";
    echo "		<td>\n";
    echo "          <div id=\"lead_result_container\" class=\"outerrnd\" style=\"width:950px;\">\n";
	echo "				<table width=\"100%\">\n";
	echo "				<tr class=\"tblhd\">\n";
    echo "				<td align=\"center\"></td>\n";
    echo "				<td align=\"center\"><b>Lead ID</b></td>\n";
    echo "				<td align=\"left\" width=\"100\"><b>Name</b></td>\n";
    echo "				<td align=\"left\"><b>Phone</b></td>\n";
    echo "				<td align=\"left\"><b>Site</b></td>\n";
	echo "				<td align=\"center\"><img id=\"openEmailQueueDlg\" class=\"setpointer\" src=\"images/email.png\" title=\"Email Queue Processing\"></td>\n";
    echo "				<td align=\"left\"><b>Rep</b></td>\n";
	echo "				<td align=\"left\" width=\"150\"><b>Status</b></td>\n";
    echo "				<td align=\"left\" width=\"110\"><b>Dates</b></td>\n";
    echo "				<td align=\"left\" title=\"JMS LifeCycle\"><b>LifeCycle</b></td>\n";
    echo "				<td align=\"left\" title=\"Total Comments for this Lead\"><b>Cmnts</b></td>\n";
	echo "				<td class=\"noPrint\" align=\"right\"><span id=\"ls_res_cnt\"></span> Result(s)</td>\n";
	echo "				</tr>\n";
}

function showLeadListHdrJSON($nrows=null) {
    //echo "<script type=\"text/javascript\" src=\"js/jquery_lead_search_new.js?".time()."\"></script>\n";		
    echo "<table align=\"center\" width=\"950px\">\n";
    echo "	<tr>\n";
    echo "		<td align=\"left\">\n";
    echo "          <div class=\"outerrnd\">\n";
    echo "			<table width=\"100%\">\n";
    echo "				<tr>\n";
    echo "					<td align=\"left\"></td>\n";
    echo "					<td align=\"left\"><b>".$_SESSION['offname']."</b></td>\n";
    echo "					<td align=\"right\">Color Key:</td>\n";
    echo "					<td align=\"center\" class=\"lightgreen\" style=\"width:75px;\"><b>Appointment</b></td>\n";
    echo "					<td align=\"center\" class=\"magenta\" style=\"width:75px;\"><b>Call Back</b></td>\n";
    echo "					<td align=\"center\" class=\"yellow\" style=\"width:75px;\"><b>Aged 7 Days</b></td>\n";
    echo "				</tr>\n";
    echo "			</table>\n";
    echo "          </div>\n";
    echo "		</td>\n";
    echo "	</tr>\n";
    echo "	<tr>\n";
    echo "		<td>\n";
    echo "          <div id=\"lead_result_container\" class=\"outerrnd\" style=\"width:950px;\">\n";
	echo "          </div>\n";
}

function showLeadListTail() {
    echo "</div>";
}

function LeadColor($ts_tdate,$data) {    
    $out='wh_und';
    $day=86400;
    
    $odiff_date=$ts_tdate[0]-$data['ts_odate'];
    $udiff_date=$ts_tdate[0]-$data['ts_udate'];
    
	if ($data['appt']['mo']==date("n") and $data['appt']['da']==date("j") and $data['appt']['yr']==date("Y")) {
        $out='lightgreen';
    }
	elseif ($data['hold']['mo']==date("n") and $data['hold']['da']==date("j") and $data['hold']['yr']==date("Y")) {
        $out='magenta';
    }
    elseif ($data['ts_udate'] == 0 and ($odiff_date > ($day*7))) {
        $out='yellow';
    }
    elseif ($udiff_date > ($day*7)) {
        $out='yellow';
    }

    return $out;
}

function ApptColor($data) {    
    $out='';
	$gd=time();
    $day=86400;
	$week=$day*7;
	$week2=$day*14;
	$fdate=$data['appt']['mo'].'/'.$data['appt']['da'].'/'.$data['appt']['yr'];
	$idate=strtotime($fdate);

	if ($data['appt']['mo']==date("n") and $data['appt']['da']==date("j") and $data['appt']['yr']==date("Y")) {
        $out='lightgreen';
    }

    return $out;
}

function CallbColor($data) {
    $out='';
	$gd=time();
    $day=86400;
	$week=$day*7;
	$week2=$day*14;
	$fdate=$data['hold']['mo'].'/'.$data['hold']['da'].'/'.$data['hold']['yr'];
	$idate=strtotime($fdate);

	if (($data['hold']['mo']==date("n") and $data['hold']['da']==date("j") and $data['hold']['yr']==date("Y")) or ($idate <= ($week2+$gd) and $idate >= $gd)) {
        $out='magenta';
    }

    return $out;
}

function AgedColor($data) {    
    $out='';
	$gd=time();
    $day=86400;
	$fdate=$data['updated'];
	$idate=strtotime($fdate);
	
	if ($idate < ($gd - ($day*7))) {
        $out='yellow';
    }

    return $out;
}

function showLifeCycle($d)
{
    $out='';
    
    if (isset($d['njobid']) and $d['njobid'] != '0') {
        $out.="<b title=\"Job\">J</b>";
    }
    elseif (isset($d['jobid']) and $d['jobid'] != '0') {
        $out.="<b title=\"Contract\">C</b>";
    }
    elseif (isset($d['estcnt']) and $d['estcnt'] > 0) {
        $out.="<b title=\"Quote/Estimate\">Q/E</b>";
    }
    else {
        $out.="<b title=\"Lead\">L</b>";
    }
    
    return $out;
}

function showSrcing($src) {
	$out='';
	
    if ($src['srcing']['source']==0) {
        $out.='Source: bluehaven.com';
    }
    elseif ($src['srcing']['source'] >= 1) {
        $out.='Source: '.$src['srcing']['srcname'];
    }
	
    $out.='<br>';
	
    if ($src['srcing']['stage']==6) {
        $out.='Result: '.$src['srcing']['resname'];
    }
    else {
        $out.='Result: '.$src['srcing']['resname'];
    }
	
	$out.='<br>';
	
	$out.='Added: '.date('m/d/y',strtotime($src['dates']['system']['added']));
	
	return $out;
}

function showAppointDate($appt) {
    $pa=($appt['mo']!=0 and $appt['pa']==1)?'AM':'PM';
    return ($appt['mo']!=0)?str_pad($appt['mo'],2,'0',STR_PAD_LEFT)."/".str_pad($appt['da'],2,'0',STR_PAD_LEFT)."/".$appt['yr']:'No Appointment';
}

function showAppointTime($appt) {
    $pa=($appt['mo']!=0 and $appt['pa']==1)?'AM':'PM';
    return ($appt['mo']!=0)?$appt['hr'].":".str_pad($appt['mn'],2,'0',STR_PAD_LEFT).$pa:'';
}

function CleanFormatPhones($phone=null) {
    $nph_ar= array('0000000000','none','N/A');
    $typ_ar= array('home','cell','work');
    $out='';
	$cphone='';
	
    foreach ($phone as $n => $v) {
        if (isset($v) and strlen($v) > 2 and !in_array($v,$nph_ar) and in_array($n,$typ_ar)) {
            $cphone.=preg_replace('/\.|-|\s/i','$1$2$3',trim($v));
        
            if (strlen(trim($cphone)) == 7) {
                $out.=substr($cphone,0,3)."-".substr($cphone,3,4).' '.substr(strtoupper($n),0,1).'<br>';
            }
            elseif (strlen(trim($cphone)) == 10) {
                $out.=substr($cphone,0,3)."-".substr($cphone,3,3)."-".substr($cphone,6,4).' '.substr(strtoupper($n),0,1).'<br>';
            }
        }
    }
    
    return $out;
}

function SingleFormatPhone($v=null) {
    $nph_ar= array('0000000000','none','N/A');
    $typ_ar= array('home','cell','work');
    $cphone='';
    
    if (isset($v) and strlen($v) > 2 and !in_array($v,$nph_ar)) {
        $cphone	=preg_replace('/\.|-|\s/i','$1$2$3',trim($v));
        
        if (strlen(trim($cphone)) == 7) {
            $cphone=substr($cphone,0,3)."-".substr($cphone,3,4);
        }
        elseif (strlen(trim($cphone)) == 10) {
            $cphone=substr($cphone,0,3)."-".substr($cphone,3,3)."-".substr($cphone,6,4);
        }
    }
    
    return $cphone;
}

function showDatesOLD($tsdate,$dates,$system) {
	$out='';
	$out.='<span class="adjustAppt setpointer" title="Click to Adjust Appointment Date & Time">Apptmnt</span>:<span class="'.LeadColor($tsdate,$dates).'" id="appt'.$system['cid'].'">'.showAppointment($dates['appt']).'</span><br>';
	$out.='<span class="adjustCallb setpointer" title="Click to Adjust Callback Date">Callback</span>:<span id="callb'.$system['cid'].'">'.showCallback($dates['hold']).'</span><br>';
    $out.='Add/Upd: '.$dates['odate'].' / '.$dates['udate'];
	return $out;
}

function showDates($tsdate,$dates,$system) {
	$out='';
	$out.='<table width="145px">';
	$out.='	<tr><td width="40px"><span class="adjustAppt setpointer" title="Click to Adjust Appointment Date & Time">Apptmnt</span></td><td align="left" class="'.ApptColor($dates).'"><span id="appt'.$system['cid'].'">'.showAppointment($dates['appt']).'</span></td></tr>';
	$out.='	<tr><td width="40px"><span class="adjustCallb setpointer" title="Click to Adjust Callback Date">Callback</span></td><td align="left" class="'.CallbColor($dates).'"><span id="callb'.$system['cid'].'">'.showCallback($dates['hold']).'</span></td></tr>';
	$out.='	<tr><td width="40px">Update</td><td align="left" class="'.AgedColor($dates).'">'.date('m/d/y',strtotime($dates['updated'])).'</td></tr>';
	$out.='</table>';
	return $out;
}

function showCustomerLine($lcnt,$tsdate,$d) {
	$lclass=($lcnt%2)?'even':'odd';
	echo "                  <tr class=\"".$lclass."\">\n";
    echo "						<td class=\"pullrec\" align=\"center\" valign=\"top\">".$lcnt."</td>\n";
    echo "						<td class=\"pullrec LeadCID\" align=\"center\" valign=\"top\">".$d['system']['cid']."</td>\n";
    echo "						<td class=\"pullrec allnames\" align=\"left\" valign=\"top\" width=\"100px\"><span class=\"clname\">".htmlspecialchars_decode($d['lead']['lname']).'</span><br>'.htmlspecialchars_decode($d['lead']['fname'])."</td>";
    echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\">".CleanFormatPhones($d['contact'])."</td>";
    echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\">".trim($d['site']['addr1']).'<br>'.$d['site']['city'].'<br>'.$d['site']['zip1']."</td>\n";
    echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\"><font class=\"".$d['format']['fstyle']."\">".$d['srep']['fname'].'<br>'.$d['srep']['lname']."</font></td>\n";
	echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\">".showSrcing($d)."</td>\n";
    echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\" width=\"150px\">".showDates($tsdate,$d['dates'],$d['system'])."</td>\n";
    echo "						<td class=\"pullrec\" align=\"center\" valign=\"top\">".showLifeCycle($d['system'])."</td>\n";
    echo "                      <td class=\"pullrec\" align=\"center\" valign=\"top\"><span class=\"setpointer leadCommentDialog\"><span class=\"cmntCnt\">".$d['system']['lcmtcnt']."</span></span></td>\n";
    echo "                      <td class=\"pullrec viewForms noPrint\" align=\"center\" valign=\"top\">\n";
    echo "                     	    <form class=\"viewLeadForm\" method=\"POST\">\n";
    echo "                     		    <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
    echo "                     		    <input type=\"hidden\" name=\"call\" value=\"view\">\n";
    echo "                     		    <input class=\"sysCID\" type=\"hidden\" name=\"cid\" value=\"".$d['system']['cid']."\">\n";
    echo "                     		    <input type=\"hidden\" name=\"uid\" value=\"".$d['system']['uid']."\">\n";
	echo "							    <button class=\"btnsysmenu\">Open Lead</button>\n";
    echo "					        </form>\n";
    echo "                     	</td>\n";
    echo "                  </tr>\n";
}

function showCustomerLineQ($lcnt,$tsdate,$d) {
	$lclass=($lcnt%2)?'even':'odd';
	echo "                  <tr class=\"".$lclass."\">\n";
    echo "						<td class=\"pullrec\" align=\"center\" valign=\"top\">".$lcnt."</td>\n";
    echo "						<td class=\"pullrec LeadCID\" align=\"center\" valign=\"top\">".$d['system']['cid']."</td>\n";
    echo "						<td class=\"pullrec allnames\" align=\"left\" valign=\"top\" width=\"100px\"><span class=\"clname\">".htmlspecialchars_decode($d['lead']['lname']).'</span><br>'.htmlspecialchars_decode($d['lead']['fname'])."</td>";
    echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\">".CleanFormatPhones($d['contact'])."</td>";
    echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\">".trim($d['site']['addr1']).'<br>'.$d['site']['city'].'<br>'.$d['site']['zip1']."</td>\n";
	echo "                      <td class=\"pullrec\" align=\"center\" valign=\"top\"><input type=\"checkbox\" class=\"emailqueue\" value=\"".$d['system']['cid']."\" style=\"display:none;\"></td>\n";
    echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\"><font class=\"".$d['format']['fstyle']."\">".$d['srep']['fname'].'<br>'.$d['srep']['lname']."</font></td>\n";
	echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\">".showSrcing($d)."</td>\n";
    echo "                      <td class=\"pullrec\" align=\"left\" valign=\"top\" width=\"150px\">".showDates($tsdate,$d['dates'],$d['system'])."</td>\n";
    echo "						<td class=\"pullrec\" align=\"center\" valign=\"top\">".showLifeCycle($d['system'])."</td>\n";
    echo "                      <td class=\"pullrec\" align=\"center\" valign=\"top\"><span class=\"setpointer leadCommentDialog\"><span class=\"cmntCnt\">".$d['system']['lcmtcnt']."</span></span></td>\n";
    echo "                      <td class=\"pullrec viewForms noPrint\" align=\"center\" valign=\"top\">\n";
    echo "                     	    <form class=\"viewLeadForm\" method=\"POST\">\n";
    echo "                     		    <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
    echo "                     		    <input type=\"hidden\" name=\"call\" value=\"view\">\n";
    echo "                     		    <input class=\"sysCID\" type=\"hidden\" name=\"cid\" value=\"".$d['system']['cid']."\">\n";
    echo "                     		    <input type=\"hidden\" name=\"uid\" value=\"".$d['system']['uid']."\">\n";
	echo "							    <button class=\"btnsysmenu\">Open Lead</button>\n";
    echo "					        </form>\n";
    echo "                     	</td>\n";
    echo "                  </tr>\n";
}

function LeadSearchResult() {
	if ($_SESSION['securityid']==2699999999999999999) {	
		echo __FUNCTION__.'<br>';
	}
    
    $list_ar    =array();
    $ts_tdate=getdate();
    echo "<script type=\"text/javascript\" src=\"js/jquery_lead_search_new.js?".time()."\"></script>\n";
    
	if (isset($_SESSION['tqry'])) {
		//echo "ZERO<br>";
		$qry	=$_SESSION['tqry'];
        echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
		echo "<table align=\"center\" width=\"950px\">\n";
        echo "  <tr>\n";
		echo "      <td align=\"center\"><b>NOTE:</b> These Search Results are based upon previously entered Search parameters. Click <b>New Search</b> to clear this condition.</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
        echo "</div>";
	}
	else {
		$qry   = "DECLARE @pdate varchar(10) ";
		$qry  .= "SET @pdate = (CAST(DATEPART(m,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(d,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(yy,(getdate() - 30)) AS varchar)) ";
		$qry  .= "SELECT ";
		$qry  .= "		* ";
		$qry  .= "FROM ";
		$qry  .= "	list_cinfo ";
		$qry  .= "WHERE ";
		$qry  .= "	officeid=".$_SESSION['officeid']." ";
		
		if (isset($_SESSION['llev']) && $_SESSION['llev'] < 5) {
			$qry  .= "	AND securityid=".$_SESSION['securityid']." ";
		}
	
        $qry  .=(isset($_REQUEST['showdupe']) && $_REQUEST['showdupe']==1)?"	AND dupe=1 ":"	AND dupe=0 ";
		
		if ((isset($_REQUEST['d1']) && !empty($_REQUEST['d1'])) && (isset($_REQUEST['d2']) && !empty($_REQUEST['d2'])))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' ";
		}
		else
		{
			if (isset($_REQUEST['showaged']) && $_REQUEST['showaged']==0)
			{
				$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN @pdate AND getdate() ";
			}
		}
		
		if ($_REQUEST['call']=="search_results" && $_REQUEST['subq']=="sstring")
		{
			$qry  .= "	AND ".$_REQUEST['field']." LIKE '".htmlspecialchars_decode($_REQUEST['ssearch'])."%' ";
		}
		else
		{
			$qry  .= "	AND ".$_REQUEST['field']."='".$_REQUEST['ssearch']."' ";
			
            $qry  .=(isset($_REQUEST['lsource']) and $_REQUEST['lsource']!='NA')?"	AND source='".$_REQUEST['lsource']."' ":'';
			$qry  .=(isset($_REQUEST['lresult']) and $_REQUEST['lresult']!='NA')?"	AND stage='".$_REQUEST['lresult']."' ":'';
		}
		
		$qry  .= "ORDER BY ";
		$qry  .= "	".$_REQUEST['order']." ".$_REQUEST['dir'].";";
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);
    
	$_SESSION['tqry']=$qry;
    
	if ($_SESSION['securityid']==2319) {
		print_r($_REQUEST).'<br>';
	}
	
    while ($row=mssql_fetch_array($res))
	{
        $ts_odate=(!empty($row['added']))?strtotime($row['added']):0;
        $ts_udate=(!empty($row['updated'])||strlen($row['updated'])!=0)?strtotime($row['updated']):0;
        
        $list_ar[$row['cid']]=array(
            'system'=>array(
                'oid'=>$row['officeid'],
                'cid'=>$row['cid'],
                'jobid'=>$row['jobid'],
                'njobid'=>$row['njobid'],
                'uid'=>md5(session_id().time().$row['cid']).".".$_SESSION['securityid'],
                'estcnt'=>$row['estcnt'],
                'lcmtcnt'=>$row['lcmtcnt']),
            'srep'=>array('sid'=>$row['sid'],'fname'=>$row['fname'],'lname'=>$row['lname']),
            'lead'=>array('fname'=>$row['cfname'],'lname'=>$row['clname']),
            'addr'=>array('addr1'=>$row['caddr1'],'city'=>$row['ccity'],'zip1'=>$row['czip1']),
            'site'=>array('addr1'=>$row['saddr1'],'city'=>$row['scity'],'zip1'=>$row['szip1']),
            'contact'=>array('home'=>$row['chome'],'cell'=>$row['ccell'],'work'=>$row['cwork'],'email'=>$row['cemail']),
            'srcing'=>array('hold'=>$row['hold'],'dupe'=>$row['dupe'],'source'=>$row['source'],'stage'=>$row['stage'],'srcname'=>$row['srcname'],'resname'=>$row['resname'],'market'=>$row['market']),
            'dates'=>array(
                'system'=>array('added'=>$row['added'],'updated'=>$row['updated']),
                'appt'=>array('mo'=>$row['appt_mo'],'da'=>$row['appt_da'],'yr'=>$row['appt_yr'],'hr'=>$row['appt_hr'],'mn'=>$row['appt_mn'],'pa'=>$row['appt_pa']),
                'hold'=>array('mo'=>$row['hold_mo'],'da'=>$row['hold_da'],'yr'=>$row['hold_yr']),
                'odate'=>($ts_odate!=0)?date("m/d/y", $ts_odate):'',
                'udate'=>($ts_udate!=0 and $ts_udate!=$ts_odate)?date("m/d/y", $ts_udate):'',
				'updated'=>date("m/d/y", strtotime($row['updated'])),
                'ts_odate'=>$ts_odate,
                'ts_udate'=>$ts_udate),
            'format'=>array('disp'=>false,'fstyle'=>(IndexOnce(explode(",",$row['slevel']),6)==0)?'red':'black')
        );
    }
    
	if (count($list_ar) == 0) {
        echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
		echo "<table width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\">\n";
		echo "         <b>Your search returned ".$nrows." results.</b>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
        echo "</div>\n";
	}
	else {
        showLeadListHdr(count($list_ar));

		$etemp_ar=array();
		$lcnt=0;
        
		foreach ($list_ar as $c => $d) {
			$lcnt++;
			showCustomerLine($lcnt,$ts_tdate,$d);
		}
		
        showLeadListTail();
	}
}

function LeadSearchResultQ() {
	if ($_SESSION['tester']==1) {	
		//echo __FUNCTION__.'<br>';
	}
    
    $list_ar    =array();
    $ts_tdate=getdate();
    echo "<script type=\"text/javascript\" src=\"js/jquery_lead_search_queue.js?".time()."\"></script>\n";
    
	if (isset($_SESSION['tqry'])) {
		//echo "ZERO<br>";
		$qry	=$_SESSION['tqry'];
        echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
		echo "<table align=\"center\" width=\"950px\">\n";
        echo "  <tr>\n";
		echo "      <td align=\"center\"><b>NOTE:</b> These Search Results are based upon previously entered Search parameters. Click <b>New Search</b> to clear this condition.</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
        echo "</div>";
	}
	else {
		$qry   = "DECLARE @pdate varchar(10) ";
		$qry  .= "SET @pdate = (CAST(DATEPART(m,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(d,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(yy,(getdate() - 30)) AS varchar)) ";
		$qry  .= "SELECT ";
		$qry  .= "		* ";
		$qry  .= "FROM ";
		$qry  .= "	list_cinfo ";
		$qry  .= "WHERE ";
		$qry  .= "	officeid=".$_SESSION['officeid']." ";
		
		if (isset($_SESSION['llev']) && $_SESSION['llev'] < 5) {
			$qry  .= "	AND securityid=".$_SESSION['securityid']." ";
		}
	
        $qry  .=(isset($_REQUEST['showdupe']) && $_REQUEST['showdupe']==1)?"	AND dupe=1 ":"	AND dupe=0 ";
		
		if ((isset($_REQUEST['d1']) && !empty($_REQUEST['d1'])) && (isset($_REQUEST['d2']) && !empty($_REQUEST['d2'])))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' ";
		}
		else
		{
			if (isset($_REQUEST['showaged']) && $_REQUEST['showaged']==0)
			{
				$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN @pdate AND getdate() ";
			}
		}
		
		if ($_REQUEST['call']=="search_results" && $_REQUEST['subq']=="sstring")
		{
			$qry  .= "	AND ".$_REQUEST['field']." LIKE '".htmlspecialchars_decode($_REQUEST['ssearch'])."%' ";
		}
		else
		{
			$qry  .= "	AND ".$_REQUEST['field']."='".$_REQUEST['ssearch']."' ";
			
            $qry  .=(isset($_REQUEST['lsource']) and $_REQUEST['lsource']!='NA')?"	AND source='".$_REQUEST['lsource']."' ":'';
			$qry  .=(isset($_REQUEST['lresult']) and $_REQUEST['lresult']!='NA')?"	AND stage='".$_REQUEST['lresult']."' ":'';
		}
		
		$qry  .= "ORDER BY ";
		$qry  .= "	".$_REQUEST['order']." ".$_REQUEST['dir'].";";
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);
    
	$_SESSION['tqry']=$qry;
    
	if ($_SESSION['securityid']==2319) {
		print_r($_REQUEST).'<br>';
	}
	
    while ($row=mssql_fetch_array($res))
	{
        $ts_odate=(!empty($row['added']))?strtotime($row['added']):0;
        $ts_udate=(!empty($row['updated'])||strlen($row['updated'])!=0)?strtotime($row['updated']):0;
        
        $list_ar[$row['cid']]=array(
            'system'=>array(
                'oid'=>$row['officeid'],
                'cid'=>$row['cid'],
                'jobid'=>$row['jobid'],
                'njobid'=>$row['njobid'],
                'uid'=>md5(session_id().time().$row['cid']).".".$_SESSION['securityid'],
                'estcnt'=>$row['estcnt'],
                'lcmtcnt'=>$row['lcmtcnt']),
            'srep'=>array('sid'=>$row['sid'],'fname'=>$row['fname'],'lname'=>$row['lname']),
            'lead'=>array('fname'=>$row['cfname'],'lname'=>$row['clname']),
            'addr'=>array('addr1'=>$row['caddr1'],'city'=>$row['ccity'],'zip1'=>$row['czip1']),
            'site'=>array('addr1'=>$row['saddr1'],'city'=>$row['scity'],'zip1'=>$row['szip1']),
            'contact'=>array('home'=>$row['chome'],'cell'=>$row['ccell'],'work'=>$row['cwork'],'email'=>$row['cemail']),
            'srcing'=>array('hold'=>$row['hold'],'dupe'=>$row['dupe'],'source'=>$row['source'],'stage'=>$row['stage'],'srcname'=>$row['srcname'],'resname'=>$row['resname'],'market'=>$row['market']),
            'dates'=>array(
                'system'=>array('added'=>$row['added'],'updated'=>$row['updated']),
                'appt'=>array('mo'=>$row['appt_mo'],'da'=>$row['appt_da'],'yr'=>$row['appt_yr'],'hr'=>$row['appt_hr'],'mn'=>$row['appt_mn'],'pa'=>$row['appt_pa']),
                'hold'=>array('mo'=>$row['hold_mo'],'da'=>$row['hold_da'],'yr'=>$row['hold_yr']),
                'odate'=>($ts_odate!=0)?date("m/d/y", $ts_odate):'',
                'udate'=>($ts_udate!=0 and $ts_udate!=$ts_odate)?date("m/d/y", $ts_udate):'',
				'updated'=>date("m/d/y", strtotime($row['updated'])),
                'ts_odate'=>$ts_odate,
                'ts_udate'=>$ts_udate),
            'format'=>array('disp'=>false,'fstyle'=>(IndexOnce(explode(",",$row['slevel']),6)==0)?'red':'black')
        );
    }
    
	if (count($list_ar) == 0) {
        echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
		echo "<table width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\">\n";
		echo "         <b>Your search returned ".$nrows." results.</b>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
        echo "</div>\n";
	}
	else {
        showLeadListHdrQ(count($list_ar));

		$etemp_ar=array();
		$lcnt=0;
        
		foreach ($list_ar as $c => $d) {
			$lcnt++;
			showCustomerLineQ($lcnt,$ts_tdate,$d);
		}
		
        showLeadListTail();
	}
}

function LeadSearchResultJSON() {
	if ($_SESSION['securityid']==2699999999999999999) {	
		echo __FUNCTION__.'<br>';
	}
    
    $list_ar    =array();
    $ts_tdate	=getdate();
	
	if (isset($_SESSION['tqry'])) {
		$prior	=true;
		$qry	=$_SESSION['tqry'];
		/*
        echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
		echo "<table align=\"center\" width=\"950px\">\n";
        echo "  <tr>\n";
		echo "      <td align=\"center\"><b>NOTE:</b> These Search Results are based upon previously entered Search parameters. Click <b>New Search</b> to clear this condition.</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
        echo "</div>";
        */
	}
	else {
		$prior = false;
		$qry   = "DECLARE @pdate varchar(10) ";
		$qry  .= "SET @pdate = (CAST(DATEPART(m,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(d,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(yy,(getdate() - 30)) AS varchar)) ";
		$qry  .= "SELECT ";
		$qry  .= "		* ";
		$qry  .= "FROM ";
		$qry  .= "	list_cinfo ";
		$qry  .= "WHERE ";
		$qry  .= "	officeid=".$_SESSION['officeid']." ";
		
		if (isset($_SESSION['llev']) && $_SESSION['llev'] < 5) {
			$qry  .= "	AND securityid=".$_SESSION['securityid']." ";
		}
	
        $qry  .=(isset($_REQUEST['showdupe']) && $_REQUEST['showdupe']==1)?"	AND dupe=1 ":"	AND dupe=0 ";
		
		if ((isset($_REQUEST['d1']) && !empty($_REQUEST['d1'])) && (isset($_REQUEST['d2']) && !empty($_REQUEST['d2'])))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' ";
		}
		else
		{
			if (isset($_REQUEST['showaged']) && $_REQUEST['showaged']==0)
			{
				$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN @pdate AND getdate() ";
			}
		}
		
		if ($_REQUEST['call']=="search_results" && $_REQUEST['subq']=="sstring")
		{
			$qry  .= "	AND ".$_REQUEST['field']." LIKE '".htmlspecialchars_decode($_REQUEST['ssearch'])."%' ";
		}
		else
		{
			$qry  .= "	AND ".$_REQUEST['field']."='".$_REQUEST['ssearch']."' ";
			
            $qry  .=(isset($_REQUEST['lsource']) and $_REQUEST['lsource']!='NA')?"	AND source='".$_REQUEST['lsource']."' ":'';
			$qry  .=(isset($_REQUEST['lresult']) and $_REQUEST['lresult']!='NA')?"	AND stage='".$_REQUEST['lresult']."' ":'';
		}
		
		$qry  .= "ORDER BY ";
		$qry  .= "	".$_REQUEST['order']." ".$_REQUEST['dir'].";";
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);
    
	$_SESSION['tqry']=$qry;
	
    while ($row=mssql_fetch_array($res))
	{
        $ts_odate=(!empty($row['added']))?strtotime($row['added']):0;
        $ts_udate=(!empty($row['updated'])||strlen($row['updated'])!=0)?strtotime($row['updated']):0;        
        $list_ar[$row['cid']]=array(
            'system'=>array(
            'oid'=>$row['officeid'],
            'cid'=>$row['cid'],
            'jobid'=>$row['jobid'],
            'njobid'=>$row['njobid'],
            'uid'=>md5(session_id().time().$row['cid']).".".$_SESSION['securityid'],
            'estcnt'=>$row['estcnt'],
            'lcmtcnt'=>$row['lcmtcnt']),
            'srep'=>array('sid'=>$row['sid'],'fname'=>$row['fname'],'lname'=>$row['lname']),
            'lead'=>array('fname'=>$row['cfname'],'lname'=>$row['clname']),
            'addr'=>array('addr1'=>$row['caddr1'],'city'=>$row['ccity'],'zip1'=>$row['czip1']),
            'site'=>array('addr1'=>$row['saddr1'],'city'=>$row['scity'],'zip1'=>$row['szip1']),
            'contact'=>array('home'=>$row['chome'],'cell'=>$row['ccell'],'work'=>$row['cwork'],'email'=>$row['cemail']),
            'srcing'=>array('hold'=>$row['hold'],'dupe'=>$row['dupe'],'source'=>$row['source'],'stage'=>$row['stage'],'srcname'=>$row['srcname'],'resname'=>$row['resname'],'market'=>$row['market']),
            'dates'=>array(
            'system'=>array('added'=>$row['added'],'updated'=>$row['updated']),
            'appt'=>array('mo'=>$row['appt_mo'],'da'=>$row['appt_da'],'yr'=>$row['appt_yr'],'hr'=>$row['appt_hr'],'mn'=>$row['appt_mn'],'pa'=>$row['appt_pa']),
            'hold'=>array('mo'=>$row['hold_mo'],'da'=>$row['hold_da'],'yr'=>$row['hold_yr']),
            'odate'=>($ts_odate!=0)?date("m/d/y", $ts_odate):'',
            'udate'=>($ts_udate!=0 and $ts_udate!=$ts_odate)?date("m/d/y", $ts_udate):'',
			'updated'=>date("m/d/y", strtotime($row['updated'])),
            'ts_odate'=>$ts_odate,
            'ts_udate'=>$ts_udate),
            'format'=>array('disp'=>false,'fstyle'=>(IndexOnce(explode(",",$row['slevel']),6)==0)?'red':'black')
        );
    }
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_lead_search_json.js?".time()."\"></script>\n";

	if (count($list_ar) == 0) {
        echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
		echo "<table width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\">\n";
		echo "         <b>Your search returned ".$nrows." results.</b>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
        echo "</div>\n";
	}
	else {
		$json_out=json_encode($list_ar);
		echo '<script type="text/javascript">'.chr(13);
		echo '	var lead_search_result='.$json_out;
		echo '</script>'.chr(13);
	
        showLeadListHdrJSON(count($list_ar));

		/*
		$etemp_ar=array();
		$lcnt=0;
        
		foreach ($list_ar as $c => $d) {
			$lcnt++;
			showCustomerLine($lcnt,$ts_tdate,$d);
		}
		*/
	}
}

function getSystemData(){
    $sdata=array(
        'offices'=>array()
    );
    
    if ($_SESSION['llev'] >= 5)
	{
		if ($_SESSION['officeid']==89)
		{
			//echo "Not Admin<br>";
			$qryA = "SELECT officeid as oid,name,stax,enest FROM offices WHERE active=1 ORDER BY grouping,name ASC;";
		}
		else
		{
			//echo "Admin<br>";
			$qryA = "SELECT officeid as oid,name,stax,enest FROM offices WHERE active=1 AND adminonly!=1 ORDER BY grouping,name ASC;";
		}
	}
	else
	{
		$qryA = "SELECT officeid as oid,name,stax,enest FROM offices WHERE officeid=".(int) $_SESSION['officeid'].";";
	}
    
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

    if ($nrowsA > 0){
        while ($rowA = mssql_fetch_array($resA)){
            $sdata['offices'][$rowA['oid']]=array('name'=>$rowA['name'],'stax'=>$rowA['stax'],'enest'=>$rowA['enest']);
        }
    }
    
    $qryB = "SELECT securityid as srid,fname,lname,sidm,slevel,assistant,substring(slevel,13,13) as slev FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY substring(slevel,13,13) desc, lname ASC;";
	$resB = mssql_query($qryB);
	$nrowsB = mssql_num_rows($resB);
    
    if ($nrowsB > 0){
        while ($rowB = mssql_fetch_array($resB)){
            $sdata['sreps'][$rowB['srid']]=array('fname'=>$rowB['fname'],'lname'=>$rowB['lname'],'slev'=>$rowB['slev']);
        }
    }
    
    $qryC = "SELECT officeid as oid,name,stax,enest,encon,finan_off,finan_from,otype_code FROM offices WHERE officeid=".(int) $_SESSION['officeid'].";";
	$resC = mssql_query($qryC);
    $rowC = mssql_fetch_array($resC);
	
    $sdata['office']=array(
        'oid'=>$rowC['oid'],
        'name'=>$rowC['name'],
        'stax'=>array('active'=>$rowC['stax'],'rates'=>array()),
        'enest'=>$rowC['enest'],
        'encon'=>$rowC['encon'],
        'finan_off'=>$rowC['finan_off'],
        'finan_from'=>$rowC['finan_from'],
        'otype_code'=>$rowC['otype_code'],
        'aiupdate'=>($rowC['otype_code'] == 2)?5:6
    );
    
    if ($sdata['office']['stax']['active']!=0)
	{
		$qryD = "SELECT id,city,taxrate,permit,wryder FROM taxrate WHERE officeid=".(int) $_SESSION['officeid']." AND active=1 ORDER BY city ASC;";
		$resD = mssql_query($qryD);
        while ($rowD = mssql_fetch_array($resD))
        {
            $sdata['office']['stax']['rates'][$rowD['id']]=array('city'=>$rowD['city'],'taxrate'=>$rowD['taxrate'],'permit'=>$rowD['permit'],'wryder'=>$rowD['wryder']);
        }
	}
    
    $qryG = "SELECT * FROM leadstatuscodes WHERE active=1 AND access!=0 AND (oid=0 OR oid=".(int) $_SESSION['officeid'].") ORDER by name ASC;";
	$resG = mssql_query($qryG);
    
    while ($rowG = mssql_fetch_array($resG))
    {
        $sdata['office']['codes']['result'][$rowG['statusid']]=array('statusid'=>$rowG['statusid'],'name'=>$rowG['name'],'active'=>$rowG['active'],'access'=>$rowG['access'],'lsource'=>$rowG['lsource']);
    }
    
    $qryGa = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$resGa = mssql_query($qryGa);
	
	while ($rowGa = mssql_fetch_array($resGa))
	{
		//$src_ex[]=$rowGa['statusid'];
        $sdata['office']['codes']['access'][9]=$rowGa['statusid'];
	}
    
    $qryH = "SELECT * FROM leadstatuscodes WHERE active=2 AND access!=0  AND access!=9 and (oid=0 or oid=".(int) $_SESSION['officeid'].") ORDER by name ASC;";
	$resH = mssql_query($qryH);
    
    while ($rowH = mssql_fetch_array($resH))
    {
        $sdata['office']['codes']['source'][$rowH['statusid']]=array('statusid'=>$rowH['statusid'],'name'=>$rowH['name'],'active'=>$rowH['active'],'access'=>$rowH['access'],'lsource'=>$rowH['lsource']);
    }
    
    $qryM = "SELECT securityid,emailtemplateaccess,filestoreaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resM = mssql_query($qryM);
	$rowM = mssql_fetch_array($resM);
    
    return $sdata;
}

function getCustData($cid){
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
    $cdata=array();
    $cdata['acl']=explode(",",$_SESSION['aid']);
    
    $qry = "SELECT * FROM cinfo WHERE officeid=".(int) $_SESSION['officeid']." AND cid=".(int) $cid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
    
    $cdata['oid']=$row['officeid'];
    
    $cdata['lead']=array(
        'cid'=>$cid,
        'names'=>array(
            'fname'=>$row['cfname'],
            'lname'=>$row['clname'],
			'cpname'=>$row['cpname']
        )
    );
    
    $cdata['address']['mail']=array(
        'addr1'=>$row['caddr1'],
        'city'=>$row['ccity'],
        'state'=>$row['cstate'],
        'zip1'=>$row['czip1'],
        'zip2'=>$row['czip2'],
        'county'=>$row['ccounty'],
        'maplink'=>maplink($row['caddr1'],$row['ccity'],$row['cstate'],$row['czip1'])
    );
    
    $cdata['address']['site']=array(
        'addr1'=>$row['saddr1'],
        'city'=>$row['scity'],
        'state'=>$row['sstate'],
        'zip1'=>$row['szip1'],
        'zip2'=>$row['szip2'],
        'county'=>$row['scounty'],
        'maplink'=>maplink($row['saddr1'],$row['scity'],$row['sstate'],$row['szip1'])
    );
    
    $cdata['address']['ssame']=$row['ssame'];
    
    $cdata['contact']=array(
        'home'=>$row['chome'],
        'work'=>$row['cwork'],
        'cell'=>$row['ccell'],
        'fax'=>$row['cfax'],
        'contime'=>$row['ccontime'],
        'conph'=>$row['cconph'],
        'contact'=>$row['ccontact'],
        'contactby'=>$row['ccontactby'],
        'contactdate'=>$row['ccontactdate'],
        'email'=>$row['cemail'],
        'opt1'=>$row['opt1'],
        'opt2'=>$row['opt2'],
        'opt3'=>$row['opt3'],
        'opt4'=>$row['opt4']
    );
    
    $cdata['status']=array(
        'source'=>$row['source'],
        'stage'=>$row['stage'],
        'dupe'=>$row['dupe'],
        'hold'=>$row['hold'],
        'jobid'=>$row['jobid'],
        'njobid'=>$row['njobid']
    );
    
    $cdata['tranid']=time().".".$cid.".".$_SESSION['securityid'];
    
	$qryI = "SELECT securityid,fname,lname,sidm FROM security WHERE officeid=".(int) $cdata['oid']." AND securityid=".(int) $row['securityid'].";";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);
    
    $cdata['srep']=array(
        'sid'=>$rowI['securityid'],
        'fname'=>$rowI['fname'],
        'lname'=>$rowI['lname'],
        'sidm'=>$rowI['sidm']
    );

	$qryJ = "SELECT securityid,fname,lname,sidm,assistant FROM security WHERE officeid=".(int) $cdata['oid']." AND securityid=".(int) $row['sidm'].";";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);
    
    $cdata['sman']=array(
        'sid'=>$rowJ['securityid'],
        'fname'=>$rowJ['fname'],
        'lname'=>$rowJ['lname'],
        'sidm'=>$rowJ['sidm'],
        'assistant'=>$rowJ['assistant']
    );

	$qryK = "SELECT MIN(appt_yr) as minyr FROM cinfo WHERE officeid=".(int) $cdata['oid'].";";
	$resK = mssql_query($qryK);
	$rowK = mssql_fetch_array($resK);
    
    $cdata['dates']['minyr']    =$rowK['minyr'];
    $cdata['dates']['adate']    =date("m-d-Y (g:i A)", strtotime($row['added']));
	$cdata['dates']['udate']    =date("m-d-Y (g:i A)", strtotime($row['updated']));
	$cdata['dates']['curryr']   =date("Y");
    $cdata['dates']['futyr']    =$cdata['dates']['curryr']+2;
    $cdata['dates']['appt']     =array('mo'=>$row['appt_mo'],'da'=>$row['appt_da'],'yr'=>$row['appt_yr'],'hr'=>$row['appt_hr'],'mn'=>$row['appt_mn'],'pa'=>$row['appt_pa']);
    $cdata['dates']['hold']     =array('mo'=>$row['hold_mo'],'da'=>$row['hold_da'],'yr'=>$row['hold_yr'],'until'=>$row['hold_until']);
    
    $cdata['estimates']=array();
    $qryE = "SELECT estid,added,esttype FROM est WHERE officeid=".(int) $cdata['oid']." AND cid=".(int) $cdata['lead']['cid'].";";
	$resE = mssql_query($qryE);
    $nrowE= mssql_num_rows($resE);
    
    if ($nrowE > 0)
    {
        while ($rowE = mssql_fetch_array($resE)){
            $cdata['estimates'][$rowE['estid']]=array('estid'=>$rowE['estid'],'esttype'=>$rowE['esttype'],'adate'=>$rowE['added']);
        }
    }
    
    $cdata['jobs']=array();
    $qryJ = "SELECT jid,jobid,njobid,added FROM jobs WHERE officeid=".(int) $cdata['oid']." AND custid=".(int) $cdata['lead']['cid'].";";
	$resJ = mssql_query($qryJ);
    $nrowJ= mssql_num_rows($resJ);
    
    if ($nrowJ > 0)
    {
        while ($rowJ = mssql_fetch_array($resJ)){
            $cdata['jobs'][$rowJ['jid']]=array('jobid'=>$rowJ['jobid'],'njobid'=>$rowJ['njobid'],'adate'=>$rowJ['added']);
        }
    }
    
    $cdata['history']=array();
    $qryH  = "SELECT  L.id as lhid,L.udate,L.uby,(select fname from security where securityid=L.uby) as ufname,(select lname from security where securityid=L.uby) as ulname  FROM leadhistory as L WHERE cinfo_id=".(int) $cdata['lead']['cid']." ORDER BY L.udate DESC;";
	$resH = mssql_query($qryH);
	$nrowH= mssql_num_rows($resH);
    
    if ($nrowH > 0)
    {
        while ($rowH = mssql_fetch_array($resH)){
            $cdata['history'][$rowH['lhid']]=array('date'=>date("m/d/y h:i A",strtotime($rowH['udate'])),'fname'=>$rowH['ufname'],'lname'=>$rowH['ulname']);
        }
    }

    /*
	$qryL = "SELECT C1.*,(SELECT lname FROM security WHERE securityid=C1.secid) as slname,(SELECT fname FROM security WHERE securityid=C1.secid) as sfname FROM chistory AS C1 WHERE C1.custid='".$cid."' ORDER BY C1.mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);
    
    if ($nrowL > 0){
        while($rowL = mssql_fetch_array($resL)){
            $cdata['comments'][$rowL['id']]=$rowL;
        }
    }
    */

    /*
	if ($_SESSION['llev'] < 9 && !in_array($rowI['securityid'],$acclist))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font><br>You do not have appropriate Access to view this Information.\n";
		exit;
	}

	$appt_dt	="";
	if ($rowF['appt_mo']!="00" && $rowF['appt_da']!="00" && $rowF['appt_yr']!="0000")
	{
		$appt_dt=old_date_disp($rowF['appt_mo'],$rowF['appt_da'],$rowF['appt_yr'],$rowF['appt_hr'],$rowF['appt_mn'],$rowF['appt_pa']);
	}
    */	
    
    $_SESSION['ifcid']=$cdata['lead']['cid'];
    return $cdata;
}

function dispLeadControl($cdata=null,$sdata=null)
{
    echo "<div class=\"outerrnd\">\n";
    echo "<table border=\"0\" width=\"100%\">\n";
	echo "  <tr>\n";
    echo "      <td align=\"left\"><b>Lead <span id=\"sysCID\">".$cdata['lead']['cid']."</span></b></td>\n";
	echo "      <td align=\"right\"><b>Added</b>\n";
	echo "      <td align=\"left\">".$cdata['dates']['adate']."</td>\n";
	echo "      <td align=\"right\"><b>Office: </b></td>\n";
	echo "      <td align=\"left\">\n";
    
    if ($_SESSION['llev'] >= 6){
        echo '<span class="setpointer" id="openchangeOfficeDialog" style="color:blue;" title="Change Office">'.$_SESSION['offname'].'</span>';
    }
    else {
        $_SESSION['offname'];
    }
    /*
	if (count($cdata['estimates']) > 0){
		echo $_SESSION['offname']."<input type=\"hidden\" name=\"site\" value=\"".$_SESSION['officeid']."\">\n";
	}
	else{
		if ($_SESSION['llev'] >= 6){
			echo "<select name=\"site\" id=\"updateLeadOffice\">\n";
            foreach ($sdata['offices'] as $no=>$vo){
				if ($no==$_SESSION['officeid']){
					echo "<option value=\"".$no."\" SELECTED>".$vo['name']."</option>\n";
				}
				else{
					echo "<option value=\"".$no."\">".$vo['name']."</option>\n";
				}
			}
			echo "</select>\n";
		}
		elseif ($_SESSION['llev'] == 5){
			if ($_SESSION['officeid']==89 || $_SESSION['officeid']==138){
				if ($cdata['status']['stage']==29){
					echo "<select name=\"site\" id=\"updateLeadOffice\">\n";
					foreach ($sdata['offices'] as $no=>$vo){
						if ($no==$_SESSION['officeid']){
                            echo "<option value=\"".$no."\" SELECTED>".$vo['name']."</option>\n";
                        }
                        else{
                            echo "<option value=\"".$no."\">".$vo['name']."</option>\n";
                        }
					}
					echo "</select>\n";
				}
				else{
					echo $_SESSION['offname']."<input type=\"hidden\" name=\"site\" value=\"".$_SESSION['officeid']."\">\n";
				}
			}
			else{
				if ($cdata['status']['source']==0 && $cdata['status']['stage']==29){
					echo "<select name=\"site\" id=\"updateLeadOffice\">\n";
					foreach ($sdata['offices'] as $no=>$vo){
						if ($no==$_SESSION['officeid']){
							echo "<option value=\"".$no."\" SELECTED>".$vo['name']."</option>\n";
						}
						elseif ($no==89){
							echo "<option value=\"".$no."\">".$vo['name']."</option>\n";
						}
					}
					echo "</select>\n";
				}
				else{
					echo $_SESSION['offname']."<input type=\"hidden\" name=\"site\" value=\"".$_SESSION['officeid']."\">\n";
				}
			}
		}
		else{
			echo $_SESSION['offname']."<input type=\"hidden\" name=\"site\" value=\"".$_SESSION['officeid']."\">\n";
		}
	}
    */
	//echo "  </td>\n";
	echo "  <td align=\"right\"><b>Sales Rep</b>\n";

	if ($_SESSION['llev'] == 4) {
		if (count($cdata['estimates']) > 0){
			echo "<select name=\"srep\" id=\"updateLeadOwner\">\n";

            foreach ($sdata['sreps'] as $nsr=>$vsr) {
				if (in_array($nsr,$cdata['acl'])){					
					$ostyle=($vsr['slev']==0)?"fontred":"fontblack";
	
					if ($nsr==$cdata['srep']['sid']) {
						echo "<option value=\"".$nsr."\" class=\"".$ostyle."\" SELECTED>".ucwords(strtolower($vsr['fname']))." ".ucwords(strtolower($vsr['lname']))."</option>\n";
					}
					else{
						echo "<option value=\"".$nsr."\" class=\"".$ostyle."\">".ucwords(strtolower($vsr['fname']))." ".ucwords(strtolower($vsr['lname']))."</option>\n";
					}
				}
			}
			echo "</select>\n";
		}
		else{
			echo $cdata['srep']['fname']." ".$cdata['srep']['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$cdata['srep']['sid']."\">\n";
		}
	}
	elseif ($_SESSION['llev'] >= 5) { // Manager List
		if (count($cdata['estimates']) == 0){
			echo "<select name=\"srep\" id=\"updateLeadOwner\">\n";
			
			foreach ($sdata['sreps'] as $nsr=>$vsr) {
				$ostyle=($vsr['slev']==0)?"fontred":"fontblack";
	
				if ($nsr==$cdata['srep']['sid']) {
					echo "<option value=\"".$nsr."\" class=\"".$ostyle."\" SELECTED>".ucwords(strtolower($vsr['fname']))." ".ucwords(strtolower($vsr['lname']))."</option>\n";
				}
				else{
					echo "<option value=\"".$nsr."\" class=\"".$ostyle."\">".ucwords(strtolower($vsr['fname']))." ".ucwords(strtolower($vsr['lname']))."</option>\n";
				}
			}
	
			echo "</select>\n";
		}
		else
		{
			echo $cdata['srep']['fname']." ".$cdata['srep']['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$cdata['srep']['sid']."\">\n";
		}
	}
	else
	{
		echo $cdata['srep']['fname']." ".$cdata['srep']['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$cdata['srep']['sid']."\">\n";
	}

	echo "      </td>\n";
    echo "      <td align=\"left\" width=\"40px\"><span id=\"updateOwnerResult\"></span></td>\n";
    echo "      <td align=\"right\"><b>Status</b> ";

	if ($_SESSION['llev'] >= $sdata['office']['aiupdate']) {
		if (count($cdata['estimates']) > 0){
			echo " <select name=\"dupe\" id=\"updateLeadStatus\" DISABLE>\n";
		}
		else
		{
			echo " <select name=\"dupe\" id=\"updateLeadStatus\">\n";
		}
        
		if ($cdata['status']['dupe']==1) {
			echo "<option value=\"1\" SELECTED>Inactive</option>\n";
			echo "<option value=\"0\">Active</option>\n";
		}
		else {
			echo "<option value=\"1\">Inactive</option>\n";
			echo "<option value=\"0\" SELECTED>Active</option>\n";
		}
	}
	else {
		echo "<input type=\"hidden\" name=\"dupe\" value=\"0\">\n";
	}

	echo "          </select>\n";
	echo "      </td>\n";
    echo "      <td align=\"right\" width=\"40px\"><span id=\"updateStatusResult\"></span></td>\n";
	echo "      <td align=\"right\" width=\"20px\">\n";
	echo "          <div class=\"noPrint\"><img class=\"getHelpNode\" id=\"CformViewDatePanel\" src=\"images/help.png\" title=\"Lead Help\"></div>\n";
	echo "      </td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
    echo "</div>\n";
}

function dispHeaderName($cdata=null){
	echo "<div class=\"outerrnd\">\n";
	echo "	<table width=\"100%\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"center\">\n";
	echo "				<table>\n";
	echo "					<tr>\n";
	echo "						<td align=\"center\">\n";
	echo "							<span style=\"font-size:15px;font-weight:bold;\">".$cdata['lead']['names']['lname'].', '.$cdata['lead']['names']['fname']."</span>";
	echo "  			        </td>\n";
	echo "  			    </tr>\n";
    echo "				</table>\n";
	echo "          </td>\n";
	echo "      </tr>\n";
    echo "	</table>\n";
	echo "</div>\n";
}

function dispName($cdata=null){
    echo "<div class=\"outerrnd\" style=\"height:75px\">\n";
	echo "  <table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "      <tr>\n";
	echo "          <td valign=\"top\"><b>Name</b></td>\n";
	echo "          <td align=\"right\" valign=\"top\">\n";
	echo "              <span class=\"saveResult\"></span>\n";
    echo "              <img class=\"saveLeadFormData setpointer noPrint\" src=\"images/save.gif\" title=\"Save\">\n";
	echo "          </td>\n";
	echo "      </tr>\n";
    echo "  </table>\n";
    echo "  <div id=\"updateNames\" class=\"inputContainer\">\n";
	echo "  <table class=\"transnb\" border=\"0\" width=\"100%\">\n";
    
	if ($cdata['oid']==193 or $cdata['oid']==199)
	{
		echo "              <tr>\n";
		echo "                  <td align=\"right\" width=\"100px\">Company Name</td>\n";
		echo "                  <td align=\"left\"><input type=\"text\" size=\"30\" name=\"cpname\" value=\"".trim($cdata['lead']['names']['cpname'])."\" autocomplete=\"off\"></td>\n";
		echo "              </tr>\n";
	}
	
	echo "      <tr>\n";
	echo "          <td align=\"right\" width=\"100px\">First</td>\n";
	echo "          <td align=\"left\"><input type=\"text\" size=\"30\" name=\"cfname\" value=\"".trim($cdata['lead']['names']['fname'])."\" autocomplete=\"off\"></td>\n";
	echo "      </tr>\n";
	echo "      <tr>\n";
	echo "          <td align=\"right\" width=\"100px\">Last</td>\n";
	echo "          <td align=\"left\"><input type=\"text\" size=\"30\" name=\"clname\" value=\"".trim($cdata['lead']['names']['lname'])."\" autocomplete=\"off\"></td>\n";
	echo "      </tr>\n";
	echo "  </table>\n";
    echo "  </div>\n";
    echo "  <p>";
	echo "</div>\n";
    echo '<p>';
}

function dispContact($cdata=null,$sdata=null){
    echo "<div class=\"outerrnd\">\n";
	echo "<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
    echo "  <tr>\n";
	echo "      <td valign=\"top\"><b>Contact Options</b></td>\n";
	echo "      <td align=\"right\">\n";
	echo "          <span class=\"saveResult\"></span>\n";
    echo "          <img class=\"saveLeadFormData setpointer noPrint\" src=\"images/save.gif\" title=\"Save\">\n";
	echo "      </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    /*
    echo "<table class=\"transnb\" width=\"100%\">\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"100px\">Lead Contacted</td>\n";
	echo "      <td align=\"left\" colspan=\"5\">\n";

	if (isset($cdata['contact']['contactby']) && $cdata['contact']['contactby']!=0){
		$cconby=($sdata['sreps'][$cdata['srep']['sid']]['slev']==0)?" by <font color=\"red\">".$sdata['sreps'][$cdata['srep']['sid']]['lname'].", ".$sdata['sreps'][$cdata['srep']['sid']]['fname']."</font>":" by ".$sdata['sreps'][$cdata['srep']['sid']]['lname'].", ".$sdata['sreps'][$cdata['srep']['sid']]['fname'];		
		echo date("m/d/Y",strtotime($cdata['contact']['contactdate']))." ".$cconby;
		echo "<input type=\"hidden\" name=\"ccontact\" value=\"1\">\n";
	}
	else{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"ccontact\" value=\"1\" title=\"Check this box to indicate the Customer has been contacted.\">\n";
	}

	echo "      </td>\n";
	echo "  </tr>\n";
    echo "</table>\n";
    */
    
    echo "  <div id=\"updateContacts\" class=\"inputContainer\">\n";
    echo "  <table class=\"transnb\" border=\"0\" width=\"100%\">\n";
    echo "      <tr>\n";
	echo "          <td align=\"right\" width=\"100px\">Home</td>\n";
	
	if (isset($cdata['contact']['home']) && strlen($cdata['contact']['home']) > 3)
	{
		echo "          <td align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"chome\" value=\"".SingleFormatPhone($cdata['contact']['home'])."\" autocomplete=\"off\"></td>\n";
	}
	else
	{
		echo "          <td align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"chome\" autocomplete=\"off\"></td>\n";
	}
	
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"100px\">Work</td>\n";
	
	if (isset($cdata['contact']['work']) && strlen($cdata['contact']['work']) > 3)
	{
		echo "          <td align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" value=\"".SingleFormatPhone($cdata['contact']['work'])."\" autocomplete=\"off\"></td>\n";	
	}
	else
	{
		echo "          <td align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" autocomplete=\"off\"></td>\n";
	}
	
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"100px\">Cell</td>\n";
	
	if (isset($cdata['contact']['cell']) && strlen($cdata['contact']['cell']) > 3)
	{
		echo "      <td align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" value=\"".SingleFormatPhone($cdata['contact']['cell'])."\" autocomplete=\"off\"></td>\n";
	}
	else
	{
		echo "      <td align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" autocomplete=\"off\"></td>\n";
	}
	
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"100px\">Fax</td>\n";
	echo "      <td align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" value=\"".SingleFormatPhone($cdata['contact']['fax'])."\" autocomplete=\"off\"></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"100px\">Best Phone</td>\n";
	echo "      <td align=\"left\">\n";
	echo "          <select name=\"cconph\">\n";

	if ($cdata['contact']['conph']=="hm") {
		echo "              <option value=\"hm\" SELECTED>Home</option>\n";
		echo "              <option value=\"wk\">Work</option>\n";
		echo "              <option value=\"ce\">Cell</option>\n";
	}
	elseif ($cdata['contact']['conph']=="wk") {
		echo "              <option value=\"hm\">Home</option>\n";
		echo "              <option value=\"wk\" SELECTED>Work</option>\n";
		echo "              <option value=\"ce\">Cell</option>\n";
	}
	elseif ($cdata['contact']['conph']=="ce") {
		echo "              <option value=\"hm\">Home</option>\n";
		echo "              <option value=\"wk\">Work</option>\n";
		echo "              <option value=\"ce\" SELECTED>Cell</option>\n";
	}
	else {
		echo "              <option value=\"hm\" SELECTED>Home</option>\n";
		echo "              <option value=\"wk\">Work</option>\n";
		echo "              <option value=\"ce\">Cell</option>\n";
	}

	echo "              </select>\n";
	echo "          </td>\n";
	echo "      </tr>\n";
	echo "      <tr>\n";
	echo "          <td align=\"right\" width=\"100px\">Contact Time</td>\n";
	echo "          <td align=\"left\"><input type=\"text\" size=\"30\" name=\"ccontime\" value=\"".$cdata['contact']['contime']."\" autocomplete=\"off\"></td>\n";
	echo "      </tr>\n";
	echo "      <tr>\n";
	echo "          <td align=\"right\" width=\"100px\">Email</td>\n";
	echo "          <td align=\"left\"><input type=\"text\" name=\"cemail\" size=\"30\" value=\"".$cdata['contact']['email']."\" autocomplete=\"off\"></td>\n";
	echo "      </tr>\n";
	echo "  </table>\n";
    echo "  </div>";
    
    if ($_SESSION['emailtemplates'] >= 1 && valid_email_addr(trim($cdata['contact']['email']))) {
        echo "  <table class=\"transnb\" border=\"0\" width=\"100%\">\n";
        echo "  <tr>\n";
        echo "      <td align=\"right\" width=\"100px\"><div class=\"noPrint\">Send Email</div></td>\n";
        echo "      <td align=\"left\" colspan=\"5\"><div class=\"noPrint\">\n";
        
        unset($_SESSION['et_uid']);
        $et_uid = md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
        
        echo "          <input type=\"hidden\" name=\"etcid[]\" value=\"".$cdata['lead']['cid']."\">\n";
        echo "          <input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid."\">\n";
        echo "          <input type=\"hidden\" name=\"chistory\" value=\"1\">\n";
        echo "          <input type=\"hidden\" name=\"etest\" value=\"0\">\n";
        
        selectemailtemplate_TLH($cdata['oid'],$_SESSION['securityid'],$cdata['lead']['cid'],1);
        
        echo "      </div></td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
	}
    echo "</div>";
    echo '<p>';
}


function selectemailtemplate_TLH($oid,$sid,$cid,$ttid) {
	$qryET = "SELECT * FROM EmailTemplate WHERE oid=0 and active <= ".$_SESSION['emailtemplates']." AND active >= 1 and ttype=".$ttid." ORDER BY name ASC;";	
	$resET = mssql_query($qryET);
	$nrowET= mssql_num_rows($resET);

	$qryET1 = "SELECT * FROM EmailTemplate WHERE oid=".(int) $_SESSION['officeid']." and active <= ".$_SESSION['emailtemplates']." AND active >= 1 and ttype=".$ttid." ORDER BY name ASC;";	
	$resET1 = mssql_query($qryET1);
	$nrowET1= mssql_num_rows($resET1);
	
	//echo $qryET1.'<br>';
	
	echo "<table>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<select id=\"etid\" name=\"etid\" autocomplete=\"off\" title=\"Selecting an Email Template will send an Email to the Customer upon update.\">\n";
	echo "				<option value=\"0\">None</option>\n";

	if ($nrowET1 > 0) {
		echo "				<optgroup label=\"".$_SESSION['offname']." Templates\">\n";
		
		while ($rowET1 = mssql_fetch_array($resET1)) {
			if ($rowET1['active']==0){
				echo "				<option class=\"fontred\"value=\"".$rowET1['etid']."\">".$rowET['name']."</option>\n";
			}
			else {
				echo "				<option value=\"".$rowET1['etid']."\">".$rowET1['name']."</option>\n";
			}
		}
		
		echo "				</optgroup>\n";
	}
	
	if ($nrowET > 0) {
		echo "				<optgroup label=\"Provided Templates\">\n";
		
		while ($rowET = mssql_fetch_array($resET)) {
			if ($rowET['active']==0) {
				echo "				<option class=\"fontred\"value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
			else {
				echo "				<option value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
		}
		
		echo "				</optgroup>\n";
	}
	
	echo "			</select>\n";
	echo "			<img class=\"setpointer\" id=\"empreviewNEW\" src=\"images/email_open.png\" title=\"Select an Email Template then click to Preview\">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function dispAddress($cdata=null,$sdata=null) {
    echo "<div class=\"outerrnd\">\n";
	echo "<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
    echo "  <tr>\n";
	echo "      <td valign=\"top\"><b>Mailing Address</b></td>\n";
    echo "      <td align=\"right\">\n";
	echo "          <span class=\"saveResult\"></span>\n";
    echo "          <img class=\"saveLeadFormData setpointer noPrint\" src=\"images/save.gif\" title=\"Save\">\n";
	echo "      </td>\n";
    echo "</table>\n";
    echo "  <div id=\"updateAddresses\" class=\"inputContainer\">\n";
    echo "  <table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\" width=\"100px\">Street</td>\n";
	echo "												<td><input type=\"text\" size=\"39\" name=\"caddr1\" value=\"".trim($cdata['address']['mail']['addr1'])."\" autocomplete=\"off\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\" width=\"100px\">City</td>\n";
	echo "												<td><input type=\"text\" size=\"20\" name=\"ccity\" value=\"".trim($cdata['address']['mail']['city'])."\" autocomplete=\"off\"> State: <input type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" value=\"".$cdata['address']['mail']['state']."\" autocomplete=\"off\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\" width=\"100px\">Zip</td>\n";
	echo "												<td><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".trim($cdata['address']['mail']['zip1'])."\" autocomplete=\"off\">-<input type=\"text\" size=\"5\" maxlength=\"4\" name=\"zip2\" value=\"".$cdata['address']['mail']['zip2']."\" autocomplete=\"off\"> ".$cdata['address']['mail']['maplink']."</td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\" width=\"100px\">Cnty/Twnshp</td>\n";
	echo "												<td>\n";

	if ($sdata['office']['stax']==0)
	{
		echo "												<input type=\"text\" size=\"18\" name=\"ccounty\" value=\"".$cdata['address']['mail']['county']."\" autocomplete=\"off\">\n";
	}
	elseif ($sdata['office']['stax']==1)
	{
		echo "												<select name=\"ccounty\">\n";

        foreach ($sdata['office']['stax']['rates'] as $sn1=>$sv1)
		{
			if ($sn1==$cdata['address']['mail']['county'])
			{
				echo "												<option value=\"".$sn1."\" SELECTED>".$sv1['name']."</option>\n";
			}
			else
			{
				echo "												<option value=\"".$sn1."\">".$sv1['name']."</option>\n";
			}
		}
		echo "												</select>\n";
	}

	echo "												</td>\n";
	echo "											</tr>\n";
    echo "											<tr>\n";

    if ($cdata['address']['ssame']==1)
    {
        echo "												<td colspan=\"2\" valign=\"top\"><b>Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
    }
    else
    {
        echo "												<td colspan=\"2\" valign=\"top\"><b>Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\"> Same as above</td>\n";
    }

    echo "											</tr>\n";
    echo "											<tr>\n";
    echo "												<td align=\"right\" width=\"100px\">Street</td>\n";
    echo "												<td><input type=\"text\" size=\"39\" name=\"saddr1\" value=\"".$cdata['address']['site']['addr1']."\" autocomplete=\"off\"></td>\n";
    echo "											</tr>\n";
    echo "											<tr>\n";
    echo "												<td align=\"right\" width=\"100px\">City</td>\n";
    echo "												<td><input type=\"text\" size=\"20\" name=\"scity\" value=\"".$cdata['address']['site']['city']."\" autocomplete=\"off\"> State <input type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$cdata['address']['site']['state']."\" autocomplete=\"off\"></td>\n";
    echo "											</tr>\n";
    echo "											<tr>\n";
    echo "												<td align=\"right\" width=\"100px\">Zip</td>\n";
    echo "												<td><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$cdata['address']['site']['zip1']."\" autocomplete=\"off\">-<input type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$cdata['address']['site']['zip2']."\" autocomplete=\"off\"> ".$cdata['address']['site']['maplink']."</td>\n";
    echo "											</tr>\n";
    echo "											<tr>\n";
    echo "												<td align=\"right\" width=\"100px\">Cnty/Twnshp</td>\n";
    echo "												<td>\n";

    if ($sdata['office']['stax']==0)
    {
        echo "													<input type=\"text\" size=\"18\" name=\"scounty\" value=\"".$cdata['address']['site']['county']."\" autocomplete=\"off\">\n";
    }
    elseif ($sdata['office']['stax']==1)
    {
        echo "													<select name=\"scounty\">\n";
        foreach ($sdata['office']['stax']['rates'] as $sn2=>$sv2)
		{
            if ($sn2==$cdata['address']['site']['county'])
            {
                echo "												<option value=\"".$sn2."\" SELECTED>".$sv2['name']."</option>\n";
            }
            else
            {
                echo "												<option value=\"".$sn2."\">".$sv2['name']."</option>\n";
            }
        }
        echo "														</select>\n";
    }

    echo "          </td>\n";
    echo "      </tr>\n";
	echo "  </table>\n";
	echo "  </div>\n";
    echo "</div>\n";
    echo '<p>';
}

function dispSourceResult($cdata=null,$sdata=null){
    echo "<div class=\"outerrnd\" style=\"height:75px\">\n";
    echo "<table class=\"transnb\" width=\"100%\">\n";
    echo "  <tr>\n";
	echo "      <td align=\"left\"><b>Source / Result</b></td>\n";
    echo "      <td align=\"right\">\n";
	echo "          <span class=\"saveResult\"></span>\n";
    echo "          <img class=\"saveLeadFormData setpointer noPrint\" src=\"images/save.gif\" title=\"Save\">\n";
	echo "      </td>\n";
	echo "  </tr>\n";
    echo "</table>\n";
    echo "<div id=\"updateSourceResult\" class=\"inputContainer\">\n";
    echo "<table class=\"transnb\" width=\"100%\">\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\">Source</td>\n";
    echo "      <td align=\"left\">\n";

    if ($cdata['status']['source']==0) {
        echo "bluehaven.com\n";
    }
    else {
        echo "          <select name=\"source\" id=\"ldsource\">\n";

        foreach ($sdata['office']['codes']['source'] as $srcn=>$srcv){
			if ($_SESSION['llev'] >= $srcv['access']){
				if ($srcn==$cdata['status']['source']){
					echo "          <option value=\"".$srcn."\" SELECTED>".$srcv['name']."</option>\n";
				}
				else
				{
					echo "          <option value=\"".$srcn."\">".$srcv['name']."</option>\n";
				}
			}
		}

		echo "          </select>\n";
    }
    
    /*
	if (array_key_exists($cdata['status']['source'],$sdata['office']['codes']['source'])){
		if ($cdata['status']['source']==0){
			echo "bluehaven.com\n";
			echo "      <input type=\"hidden\" name=\"source\" value=\"0\">\n";
		}
		else{
			$qryGaa = "SELECT statusid,name FROM leadstatuscodes WHERE statusid=".$cdata['status']['source'].";";
			$resGaa = mssql_query($qryGaa);
			$rowGaa = mssql_fetch_array($resGaa);
			
            echo $rowGaa['name'];
			echo "      <input type=\"hidden\" name=\"source\" value=\"".$rowGaa['statusid']."\">\n";
		}
	}
	else{
		echo "          <select name=\"source\">\n";

        foreach ($sdata['office']['codes']['source'] as $srcn=>$srcv){
			if ($_SESSION['llev'] >= $srcv['access']){
				if ($srcn==$cdata['status']['source']){
					echo "          <option value=\"".$srcn."\" SELECTED>".$srcv['name']."</option>\n";
				}
				else
				{
					echo "          <option value=\"".$srcn."\">".$srcv['name']."</option>\n";
				}
			}
		}

		echo "          </select>\n";
	}
    */
    
    echo "      </td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\">Result</td>\n";
	echo "      <td align=\"left\">\n";

	if ($cdata['status']['jobid']=='0'){
		echo "          <select name=\"stage\" id=\"ldstage\">\n";
	}
	else{
		echo "          <input type=\"hidden\" name=\"stage\" value=\"".$cdata['status']['stage']."\">\n";
		echo "          <select name=\"stage\" DISABLED>\n";
	}

	echo "              <option value=\"1\"></option>\n";
    
    foreach ($sdata['office']['codes']['result'] as $resn=>$resv){
		if ($resn==$cdata['status']['stage']){
			echo "          <option value=\"".$resn."\" SELECTED>".$resv['name']."</option>\n";
		}
		else{
			echo "          <option value=\"".$resn."\">".$resv['name']."</option>\n";
		}
	}

	echo "          </select>\n";	
	echo "      </td>\n";
	echo "  </tr>\n";
    echo "</table>\n";
    echo "<p>";
    echo "</div>\n";
	
    /*
	if ($rowC[2]!=1 && $rowC[3]!=1 && $rowC[4]!=0)
	{	
		if (isset($rowF['finan_src']) && $rowF['finan_src'] > 0)
		{
			$disfr=" DISABLED ";
		}
		else
		{
			$disfr='';
		}
		
		echo "                             			   <tr>\n";
		echo "                        						<td align=\"right\"><b>Finance Release</b></td>\n";
		echo "                        						<td align=\"left\" colspan=\"5\">\n";
		echo "                                    			<select name=\"finansrc\" ".$disfr." title=\"Set the Finance Source\">\n";
		
		if (!isset($rowF['finan_src']) || $rowF['finan_src']==0)
		{
			echo "                                    	<option value=\"0\">Select...</option>\n";
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==1)
		{
			echo "                                    	<option value=\"1\" SELECTED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==2)
		{
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\" selected>Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==3)
		{
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\" selected>Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==4)
		{
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\" SELECTED>BH Finance</option>\n";
		}
		
		echo "                                    			</select>\n";
		
		if (isset($rowF['finan_src']) && $rowF['finan_src'] > 0)
		{
			echo "												<input type=\"hidden\" name=\"finansrc\" value=\"".$rowF['finan_src']."\">\n";
		}
		
		echo "                        						</td>\n";
		echo "                        					</tr>\n";	
	}
	*/

    echo "</div>\n";
	echo '<p>';
}

function dispCallback($cdata=null,$sdata=null) {
	$cpc=CallbColor($cdata['dates']);
	$bgcolor=(isset($cpc) and strlen($cpc) > 0)?'outerrnd_mgnta':'outerrnd';
	
    echo "<div class=\"".$bgcolor."\" style=\"height:75px\" id=\"CallbackContainer\">\n";
    echo "<table class=\"transnb\" width=\"100%\">\n";
    echo "  <tr>\n";
	echo "      <td align=\"left\"><b>Callback</b></td>\n";
	echo "      <td width=\"20px\" align=\"center\"><img id=\"removeCallbackDate\" class=\"setpointer noPrint\" src=\"images/deletesm.gif\" title=\"Click to Remove this Callback Date\"></td>\n";
    echo "      <td width=\"20px\" align=\"center\"><img id=\"showCallbackDialog\" class=\"setpointer noPrint\" src=\"images/pencil.png\" title=\"Click to Add or Adjust a Callback Date\"></td>\n";
	echo "  </tr>\n";
    echo "  <tr>\n";
	echo "      <td align=\"center\" colspan=\"3\"><span id=\"CallbackDate\">".showCallback($cdata['dates']['hold'])."</span></td>\n";
	echo "  </tr>\n";
    echo "</table>\n";
    echo "<p>";
    echo "</div>\n";
}

function showCallback($callb) {
    return ($callb['mo']!=0)?str_pad($callb['mo'],2,'0',STR_PAD_LEFT)."/".str_pad($callb['da'],2,'0',STR_PAD_LEFT)."/".substr($callb['yr'],2,2):'';
}

function showAppointment($appt) {
    $pa=($appt['mo']!=0 and $appt['pa']==1)?'AM':'PM';
    return ($appt['mo']!=0)?str_pad($appt['mo'],2,'0',STR_PAD_LEFT)."/".str_pad($appt['da'],2,'0',STR_PAD_LEFT)."/".substr($appt['yr'],2,2)." ".$appt['hr'].":".str_pad($appt['mn'],2,'0',STR_PAD_LEFT).$pa:'';
}

function dispAppointment($cdata=null,$sdata=null) {
	
	$apc=ApptColor($cdata['dates']);
	$bgcolor=(isset($apc) and strlen($apc) > 0)?'outerrnd_ltgrn':'outerrnd';
	
    echo "<div class=\"".$bgcolor."\" id=\"AppointmentContainer\" style=\"height:75px\">\n";
    echo "<table class=\"transnb\" width=\"100%\">\n";
    echo "  <tr>\n";
	echo "      <td align=\"left\"><b>Appointment</b></td>\n";
	echo "      <td width=\"20px\" align=\"center\"><img id=\"removeApptDate\" class=\"setpointer noPrint\" src=\"images/deletesm.gif\" title=\"Click to Remove this Appointment\"></td>\n";
    echo "      <td width=\"20px\" align=\"center\"><img id=\"showApptDialog\" class=\"setpointer noPrint\" src=\"images/pencil.png\" title=\"Click to Add or Adjust an Appointment\"></td>\n";
	echo "  </tr>\n";
    echo "  <tr>\n";
	echo "      <td align=\"center\" colspan=\"3\"><span id=\"AppointmentDate\">".showAppointDate($cdata['dates']['appt'])."</span></td>\n";
	echo "  </tr>\n";
    echo "  <tr>\n";
	echo "      <td align=\"center\" colspan=\"3\"><span id=\"AppointmentTime\">".showAppointTime($cdata['dates']['appt'])."</span></td>\n";
	echo "  </tr>\n";
    echo "</table>\n";
    echo "<p>";
    echo "</div>\n";
}

function dispMarketing($cdata){
    
    if (isset($cdata['mrktproc']) and strlen($cdata['mrktproc']) > 2)
	{
        $lwidth='340px';
		// Marketing Table Start
		echo "							<table class=\"outer\" width=\"".$lwidth."\">\n";
		echo "								<tr>\n";
		echo "									<td valign=\"top\" align=\"left\"><b>Marketing Data</b></td>\n";
		echo "									<td valign=\"top\" align=\"right\">\n";
		echo "										<div class=\"noPrint\">\n";
		echo "											<button id=\"showMarketingData\">Show</button>\n";
		echo "										</div>\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "                           	<tr>\n";
		echo "									<td colspan=\"2\" valign=\"top\">\n";
		echo "										<table width=\"100%\" id=\"MarketingDataTable\" style=\"display:none;\">\n";
		echo "											<tr>\n";
		echo "												<td valign=\"top\" align=\"left\">\n";
		echo "													<pre>".wordwrap(preg_replace('/-----------------/','---',$cdata['mrktproc'],45))."</pre>\n";
		echo "												</td>\n";
		echo "											</tr>\n";
		echo "										</table>\n";
		echo "									</td>\n";
		echo "                          	</tr>\n";
		echo "							</table>\n";
        echo '<p>';
		// Marketing Table End
	}
}

function dispPrivacy($cdata=null){
    echo "<div class=\"outerrnd\">\n";
    echo "<table width=\"100%\">\n";
	echo "  <tr>\n";
	echo "      <td align=\"left\"><b>Privacy Setting</b></td>\n";
	echo "      <td align=\"right\"><span id=\"savePrivacyResult\"></span></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	echo "<table width=\"100%\">\n";
	echo "  <tr>\n";
    echo "      <td width=\"100px\" align=\"right\" valign=\"top\">\n";
    
	if ($cdata['contact']['opt1']==1) {
		if ($cdata['status']['source']==0) {
			echo "          <input class=\"privacyOpt\" class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\" title=\"Cannot be Modified. This Lead was sourced from bluehaven.com\" CHECKED DISABLED>\n";
		}
		else {
			echo "          <input class=\"privacyOpt\" class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\" CHECKED>\n";
		}
	}
	else {
		echo "          <input class=\"privacyOpt\" class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\">\n";
	}

    echo "      <td>\n";
	echo "          Customer does not wish to receive any future information about updates, special offers, or other communications regarding Blue Haven-related products, supplies, or services";
    echo "      </td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
    echo "</div>\n";
    echo '<p>';
}

function displayCommentTable_TED()
{
    echo "<div class=\"outerrnd\">\n";
	echo "<table width=\"100%\">\n";
	echo "  <tr>\n";
	echo "      <td align=\"left\"><b>Comments/Directions</b></td>\n";
	echo "      <td align=\"right\">\n";
	echo "          <table>\n";
	echo "              <tr>\n";
	echo "                  <td align=\"right\">\n";
	echo "                      <img class=\"setpointer noPrint\" id=\"expandLeadComments\" src=\"images/arrow_out.png\" title=\"Expand Comment List\">\n";
	echo "                  </td>\n";
	echo "                  <td align=\"right\">\n";
	echo "                      <img class=\"setpointer noPrint\" id=\"refreshLeadComments\" src=\"images/arrow_refresh.png\" title=\"Refresh Comment List\">\n";
	echo "                  </td>\n";
	echo "              </tr>\n";
	echo "          </table>\n";
	echo "      </td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
    echo "<p>";
	echo "  <textarea style=\"margin-left:5px\" class=\"noPrint\" name=\"addcomment\" id=\"addcomment\" cols=\"80\" rows=\"2\"></textarea>\n";
	echo "  <img class=\"setpointer noPrint\" id=\"saveLeadComment\" src=\"images/save.gif\" title=\"Save New Comment\">\n";
    echo "  <div id=\"LeadCommentList\"></div>\n";
    echo "</div>\n";
}

function dispHistory($cdata=null)
{
    echo "<div id=\"dispLeadHistoryContainer\">\n";
    echo "  <h3><a href=\"#\">Lead Update History</a></h3>\n";
    echo "  <div>\n";
    
    if (count($cdata['history']) > 0){
        echo '<table>';
        foreach ($cdata['history'] as $n=>$v){
            echo "<tr><td>".$v['date']."</td><td>".$v['fname']." ".$v['lname']."</td></tr>\n";
        }
        
        echo '</table>';
    }
    
    echo "  </div>\n";
    echo "</div>\n";
}

function LeadSearch()
{
	$dev_ar= array(26,289,332,419,443,641,1950,1984,2139);
	
	/*
	if ($_SESSION['securityid']==26)
	{
		echo $_SESSION['otype'].'<br>';
		echo $_SESSION['sotype_code'];
	}
	*/
	
	if ($_SESSION['otype']==2)
	{
		search_panel_VENDOR();
	}
	elseif ($_SESSION['otype']==3)
	{
		search_panel_TRACK();
	}
	else
	{
		LeadSearchPanel();
	}
	
	if ($_SESSION['otype']==2)
	{
		//display_CB_AP_TRACK();
	}
	elseif ($_SESSION['otype']==3)
	{
		display_CB_AP_TRACK();
	}
	else
	{
		display_Lead_Search_Ajax();
	}
}

function LeadSearchPanelNEW()
{
	echo __FUNCTION__.'<br>';
	unset($_SESSION['tqry']);
	unset($_SESSION['et_uid']);
	
	$cr_ar=array();
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 AND ivr!=1 ORDER BY name ASC;";
	$res = mssql_query($qry);
	
	while ($row = mssql_fetch_array($res))
	{
		$lsrc_ar[$row['statusid']]=array('oid'=>$row['oid'],'name'=>$row['name']);
	}

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 AND (oid=0 OR oid=".(int) $_SESSION['officeid'].") ORDER BY name ASC;";
	$res0 = mssql_query($qry0);
	
	while ($row0 = mssql_fetch_array($res0))
	{
		$lres_ar[$row0['statusid']]=array('oid'=>$row0['oid'],'name'=>$row0['name']);
	}
	
	$htitle	=($_SESSION['officeid']==199)?"<b>Vendor Search</b>\n":"<b>Lead Search</b> <img class=\"getHelpNode\" id=\"LeadSearchPanel\" src=\"images/help.png\" title=\"Lead Search Help\">\n";
	echo "<script type=\"text/javascript\" src=\"js/jquery_search_panel_DEV.js?".time()."\"></script>\n";
	echo "<table width=\"950px\" border=1>\n";
	echo "	<tr>\n";
	echo "		<td>".$htitle."</td>\n";
	echo "		<td></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\">\n";
	
	if ($_SESSION['officeid']!=199) {
		LeadSearchPanel_Source($lsrc_ar);
	}
	
	echo "		</td>\n";
	echo "		<td align=\"center\">\n";
	
	LeadSearchPanel_String();
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\">\n";
	
	if ($_SESSION['officeid']!=199) {
		LeadSearchPanel_Result($lres_ar);
	}
	
	echo "		</td>\n";
	echo "		<td align=\"center\">\n";

	LeadSearchPanel_Srep($lsrc_ar,$lres_ar);
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function LeadSearchPanel_Source($lsrc_ar) {
	$et_uid =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	echo "<div class=\"outerrnd\">\n";
	echo "<form name=\"tsearch2\" method=\"post\">\n";
	echo "	<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "	<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "	<input type=\"hidden\" name=\"subq\" value=\"srcstatus\">\n";
	echo "	<input type=\"hidden\" name=\"field\" value=\"source\">\n";
	echo "	<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid."\">\n";
	echo "	<table border=\"0\" width=\"470px\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" width=\"150px\"><b>Source Code</b></td>\n";
	echo "			<td align=\"left\"><b>Data Field</b></td>\n";
	echo "			<td align=\"left\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{		
		echo "<b>Market</b>\n";
	}
	
	echo "			</td>\n";
	echo "			<td align=\"left\"><b>Sort by</b></td>\n";
	echo "			<td align=\"left\"><b>Direction</b></td>\n";
	echo "			<td align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">Aged 30+</td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "			<td align=\"center\">Inactive</td>\n";
	}
	
	echo "			<td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">Address</td>\n";
	echo "			<td align=\"center\" title=\"Select the number of comments to display\">Comments</td>\n";
	echo "			<td align=\"left\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" width=\"150px\">\n";
	echo "				<select name=\"ssearch\">\n";

	foreach ($lsrc_ar as $ns=>$vs)
	{
		if ($ns==0)
		{
			echo "                                    	<option value=\"".$ns."\">bluehaven.com</option>\n";
		}
		elseif ($ns==1)
		{
			echo "                                    	<option value=\"".$ns."\">Manual</option>\n";
		}
		else
		{
			if ($vs['oid']==0 || $vs['oid']==$_SESSION['officeid'])
			{
				echo "                                    	<option value=\"".$ns."\">".$vs['name']."</option>\n";
			}
		}
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";

	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "												<select name=\"market\">\n";
		
		foreach ($mar_ar as $nM => $vM)
		{
			echo "												<option value=\"".$vM."\">".$vM."</option>\n";
		}
		
		echo "												</select>\n";
	}
	
	echo "											</td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                    	<option value=\"clname\">Last Name</option>\n";
	echo "										<option value=\"cfname\">First Name</option>\n";
	echo "                                    	<option value=\"custid\">Lead ID</option>\n";
	echo "										<option value=\"scity\">Site City</option>\n";
	echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
	echo "                                    	<option value=\"added\">Date Added</option>\n";
	echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\">\n";
	echo "                                    <select name=\"dir\">\n";
	echo "                                    	<option value=\"asc\">Ascending</option>\n";
	echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "                                    </select>\n";
	echo "									</td>";
	echo "                                 <td align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">\n";
	echo "										<select name=\"showaged\">\n";
	echo "											<option value=\"0\">No</option>\n";
	echo "											<option value=\"1\">Yes</option>\n";
	echo "										</select>\n";
	echo "								   </td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
		echo "										<select name=\"showdupe\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	}

	echo "                                 <td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">\n";
	echo "										<select name=\"incaddr\">\n";
	echo "											<option value=\"0\">No</option>\n";
	echo "											<option value=\"1\">Yes</option>\n";
	echo "										</select>\n";
	echo "								   </td>\n";
	echo "                                 <td align=\"center\">\n";
	echo "                                    <select name=\"cmtcnt\">\n";
	echo "                                    	<option value=\"0\">0</option>\n";
	echo "                                    	<option value=\"1\">1</option>\n";
	echo "                                    	<option value=\"2\">2</option>\n";
	echo "                                    	<option value=\"3\">3</option>\n";
	echo "                                    	<option value=\"4\">4</option>\n";
	echo "                                    	<option value=\"5\">5</option>\n";
	echo "                                    </select>\n";
	echo "								   </td>\n";
	echo "                                	<td align=\"left\">\n";
	echo "										<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "									</td>\n";
	echo "								<tr>\n";
	echo "											<td align=\"right\">\n";

	echo "											</td>\n";
	echo "                              	<td align=\"right\">\n";
	echo "                                   	<select name=\"dtype\">\n";
	echo "                                    		<option value=\"added\">Date Added</option>\n";
	echo "                                    		<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "                                    	</select>\n";
	echo "									</td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "										<input class=\"datesp\" type=\"text\" name=\"d3\" id=\"d3\" size=\"11\">\n";
	echo "										<input class=\"datesp\" type=\"text\" name=\"d4\" id=\"d4\" size=\"11\">\n";
	echo "									</td>\n";
	echo "                                 	<td align=\"left\" colspan=\"2\">(Date Optional)</td>\n";
	echo "                                 	<td colspan=\"4\">\n";
	
	//selectemaillisttemplate();
	
	echo "									</td>\n";
	echo "                                 	<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "								</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "</div>\n";
}

function LeadSearchPanel_Result($lres_ar) {
	$et_uid =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	echo "<div class=\"outerrnd\">\n";
	echo "<form name=\"tsearch3\" method=\"post\">\n";
	echo "	<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "	<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "	<input type=\"hidden\" name=\"subq\" value=\"resstatus\">\n";
	echo "	<input type=\"hidden\" name=\"field\" value=\"stage\">\n";
	echo "	<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid."\">\n";
	echo "	<table border=\"0\" width=\"470px\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" colspan=\"2\"></td>\n";
	echo "			<td align=\"left\"><b>Data Field</b></td>\n";
	echo "			<td align=\"left\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{		
		echo "<b>Market</b>\n";
	}
	
	echo "			</td>\n";
	echo "			<td align=\"left\"><b>Sort by</b></td>\n";
	echo "			<td align=\"left\"><b>Direction</b></td>\n";
	echo "			<td align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">Aged 30+</td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "			<td align=\"center\">Inactive</td>\n";
	}
	
	echo "			<td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">Address</td>\n";
	echo "			<td align=\"center\" title=\"Select the number of comments to display\">Comments</td>\n";
	echo "			<td align=\"left\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" width=\"75px\"><b>Result Code</b>\n";
	echo "			<td align=\"left\">\n";
	echo "				<select name=\"ssearch\">\n";

	foreach ($lres_ar as $nr => $vr)
	{
		echo "                                    	<option value=\"".$nr."\">".$vr['name']."</option>\n";
	}

	echo "				</select>\n";
	echo "			</td>\n";
	echo "			<td align=\"left\">\n";

	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "												<select name=\"market\">\n";
		
		foreach ($mar_ar as $nM => $vM)
		{
			echo "												<option value=\"".$vM."\">".$vM."</option>\n";
		}
		
		echo "												</select>\n";
	}
	
	echo "											</td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                    	<option value=\"clname\">Last Name</option>\n";
	echo "										<option value=\"cfname\">First Name</option>\n";
	echo "                                    	<option value=\"custid\">Lead ID</option>\n";
	echo "										<option value=\"scity\">Site City</option>\n";
	echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
	echo "                                    	<option value=\"added\">Date Added</option>\n";
	echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "                                    </select>\n";
	echo "									</td>\n";
	echo "                                 <td align=\"left\">\n";
	echo "                                    <select name=\"dir\">\n";
	echo "                                    	<option value=\"asc\">Ascending</option>\n";
	echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "                                    </select>\n";
	echo "								   </td>";
	echo "                                 <td align=\"center\" title=\"Check this box to include Leads that have not been updated within the last 30 days\">\n";
	echo "										<select name=\"showaged\">\n";
	echo "											<option value=\"0\">No</option>\n";
	echo "											<option value=\"1\">Yes</option>\n";
	echo "										</select>\n";
	echo "								   </td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
		echo "										<select name=\"showdupe\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	}

	echo "                                 <td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">\n";
	echo "										<select name=\"incaddr\">\n";
	echo "											<option value=\"0\">No</option>\n";
	echo "											<option value=\"1\">Yes</option>\n";
	echo "										</select>\n";
	echo "								   </td>\n";
	echo "                                 <td align=\"center\">\n";
	echo "                                    <select name=\"cmtcnt\">\n";
	echo "                                    	<option value=\"0\">0</option>\n";
	echo "                                    	<option value=\"1\">1</option>\n";
	echo "                                    	<option value=\"2\">2</option>\n";
	echo "                                    	<option value=\"3\">3</option>\n";
	echo "                                    	<option value=\"4\">4</option>\n";
	echo "                                    	<option value=\"5\">5</option>\n";
	echo "                                    </select>\n";
	echo "								   </td>\n";
	echo "                                 <td align=\"left\">\n";
	echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "									</td>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">\n";
	
	echo "											</td>\n";
	echo "                              	<td align=\"right\">\n";
	echo "                                    <select name=\"dtype\">\n";
	echo "                                    	<option value=\"added\">Date Added</option>\n";
	echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "                                    </select>\n";
	echo "									</td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "										<input class=\"datesp\" type=\"text\" name=\"d5\" id=\"d5\" size=\"11\">\n";
	echo "										<input class=\"datesp\" type=\"text\" name=\"d6\" id=\"d6\" size=\"11\">\n";
	echo "									</td>\n";
	echo "                                 	<td align=\"left\" colspan=\"2\">(Date Optional)</td>\n";
	echo "                                 	<td colspan=\"4\">\n";
	
	//selectemaillisttemplate();
	
	echo "											</td>\n";
	echo "                                 			<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "										</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "</div>\n";
}

function LeadSearchPanel_Srep($lsrc_ar,$lres_ar) {
	$qry1  = "SELECT ";
	$qry1 .= "	 s.securityid ";
	$qry1 .= "	,s.lname ";
	$qry1 .= "	,s.fname ";
	$qry1 .= "	,SUBSTRING(s.slevel,13,1) as slevel ";
	$qry1 .= "	,(SELECT count(cid) FROM cinfo WHERE dupe=0 AND jobid='0' AND securityid=S.securityid) as lcnt ";
	$qry1 .= "FROM  ";
	$qry1 .= "	security AS s ";
	$qry1 .= "WHERE  ";
	$qry1 .= "	s.officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=$_SESSION['securityid'] && $_SESSION['asstto']!=0)
		{
			$qry1 .= "	and s.sidm=".$_SESSION['asstto']." OR s.securityid=".$_SESSION['securityid']." ";
		}
		else
		{
			$qry1 .= "	and s.sidm=".$_SESSION['securityid']." OR s.securityid=".$_SESSION['securityid']." ";
		}
	}
	elseif ($_SESSION['llev'] <= 3)
	{
		$qry1 .= "	and s.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry1 .= "ORDER BY ";
	$qry1 .= "	SUBSTRING(s.slevel,13,1) DESC, ";
	$qry1 .= "	s.lname ASC; ";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	if ($nrow1 > 0)
	{
		$qry2 = "SELECT ldexport,gm,am FROM offices WHERE officeid=".(int) $_SESSION['officeid'].";";
		$res2 = mssql_query($qry2);
		$row2 = mssql_fetch_array($res2);
		
		$htitle=($_SESSION['officeid']==193 and $_SESSION['officeid']==199)?'<b>Manager</b>':'<b>Sales Rep</b>';
		
		echo "<div class=\"outerrnd\">\n";
		echo "<form name=\"tsearch4\" method=\"post\">\n";
		echo "	<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "	<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "	<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
		echo "	<input type=\"hidden\" name=\"field\" value=\"securityid\">\n";
		echo "	<table border=\"0\" width=\"470px\">\n";
		echo "		<tr>\n";
		echo "			<td align=\"left\" colspan=\"2\"><b>Search Type</b></td>\n";
		echo "			<td align=\"left\"><strong>Data Field</strong></td>\n";
		echo "			<td align=\"left\">\n";
		
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{		
			echo "<b>Market</b>\n";
		}
		
		echo "			</td>\n";
		echo "			<td align=\"left\"><b>Sort by</b></td>\n";
		echo "			<td align=\"left\"><b>Direction</b></td>\n";
		echo "			<td align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">Aged 30+</td>\n";
	
		if ($_SESSION['llev'] >= 5)
		{
			echo "			<td align=\"center\">Inactive</td>\n";
		}
		
		echo "			<td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">Address</td>\n";
		echo "			<td align=\"center\" title=\"Select the number of comments to display\">Comments</td>\n";
		echo "			<td align=\"left\"></td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td align=\"left\">".$htitle."</td>\n";		
		echo "			<td align=\"left\">\n";
		echo "				<select name=\"ssearch\">\n";
	
		$dtxt='';
		while ($row1 = mssql_fetch_array($res1)) {
			if ($row1['securityid']==$row2['am'] or $_SESSION['securityid']==$row1['securityid']) {
				if ($row1['slevel']==0) {
					echo "				<option value=\"".$row1['securityid']."\" class=\"fontred\" SELECTED>".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
				else {
					echo "				<option value=\"".$row1['securityid']."\" class=\"fontblack\" SELECTED>".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
			}
			else {
				if ($row1['slevel']==0) {
					echo "				<option value=\"".$row1['securityid']."\" class=\"fontred\">".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
				else {
					echo "				<option value=\"".$row1['securityid']."\" class=\"fontblack\">".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
			}
		}
	
		echo "				</select>\n";
		echo "			</td>\n";
		echo "			<td align=\"left\">\n";
	
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199) {
			echo "				<select name=\"market\">\n";
			
			foreach ($mar_ar as $nM => $vM) {
				echo "					<option value=\"".$vM."\">".$vM."</option>\n";
			}
			
			echo "				</select>\n";
		}
		
		echo "			</td>\n";
		echo "			<td align=\"left\">\n";
		echo "				<select name=\"order\">\n";
		
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199) {
			echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
			echo "													<option value=\"clname\">Last Name</option>\n";
		}
		else {
			echo "													<option value=\"clname\" SELECTED>Last Name</option>\n";
		}

		echo "										<option value=\"cfname\">First Name</option>\n";
		echo "                                    	<option value=\"custid\">Lead ID</option>\n";
		echo "										<option value=\"scity\">Site City</option>\n";
		echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "			</td>\n";
		echo "			<td align=\"left\">\n";
		echo "                                    <select name=\"dir\">\n";
		echo "                                    	<option value=\"asc\">Ascending</option>\n";
		echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "                                    </select>\n";
		echo "									</td>";
		echo "			<td align=\"center\" title=\"Check this box to include Leads that have not been updated within the last 30 days\">\n";
		echo "										<select name=\"showaged\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "			</td>\n";
	
		if ($_SESSION['llev'] >= 5) {
			echo "				<td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
			echo "										<select name=\"showdupe\">\n";
			echo "											<option value=\"0\">No</option>\n";
			echo "											<option value=\"1\">Yes</option>\n";
			echo "										</select>\n";
			echo "					</td>\n";
		}
	
		echo "			<td align=\"center\" title=\"Check this box to include the Address and Email in the displayed search results\">\n";
		echo "										<select name=\"incaddr\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "			</td>\n";
		echo "			<td align=\"center\">\n";
		echo "                                    <select name=\"cmtcnt\">\n";
		echo "                                    	<option value=\"0\">0</option>\n";
		echo "                                    	<option value=\"1\">1</option>\n";
		echo "                                    	<option value=\"2\">2</option>\n";
		echo "                                    	<option value=\"3\">3</option>\n";
		echo "                                    	<option value=\"4\">4</option>\n";
		echo "                                    	<option value=\"5\">5</option>\n";
		echo "                                    </select>\n";
		echo "			</td>\n";
		echo "			<td align=\"left\">\n";
		//echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
		echo "				<button>Submit</button>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td align=\"right\"></td>\n";
		echo "			<td align=\"right\">\n";
		echo "                                    <select name=\"dtype\">\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "			</td>\n";
		echo "			<td align=\"left\">\n";
		echo "									<input class=\"datesp\" type=\"text\" name=\"d7\" id=\"d7\" size=\"11\">\n";
		echo "									<input class=\"datesp\" type=\"text\" name=\"d8\" id=\"d8\" size=\"11\">\n";
		echo "			</td>\n";
		echo "			<td align=\"left\" colspan=\"2\">(Date Optional)</td>\n";
		echo "			<td align=\"left\" colspan=\"2\"><b>Source</b>\n";
		echo "                                    <select name=\"lsource\">\n";
		echo "                                    		<option value=\"NA\">All</option>\n";
	
		foreach ($lsrc_ar as $ns => $vs) {
			if ($ns==0) {
				echo "                                    	<option value=\"".$ns."\">bluehaven.com</option>\n";
			}
			elseif ($ns==1) {
				echo "                                    	<option value=\"".$ns."\">Manual</option>\n";
			}
			else {
				if ($vs['oid']==0 || $vs['oid']==$_SESSION['officeid']) {
					echo "                                    	<option value=\"".$ns."\">".$vs['name']."</option>\n";
				}
			}
		}
	
		echo "                                    </select>\n";
		echo "		</td>\n";
		echo "		<td align=\"left\" colspan=\"2\"><b>Result</b>\n";
		echo "			<select name=\"lresult\">\n";
		echo "				<option value=\"NA\">All</option>\n";
	
		foreach ($lres_ar as $nr => $vr) {
			echo "				<option value=\"".$nr."\">".$vr['name']."</option>\n";
		}
	
		echo "			</select>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo "</div>\n";
	}
}

function LeadSearchPanel_String() {
	$et_uid =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	echo "<div class=\"outerrnd\" style=\"width:475px\">\n";
	echo "<form name=\"tsearch1\" method=\"post\">\n";
	echo "	<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "	<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "	<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "	<table>\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\"><b>String Search</b></td>\n";
	echo "			<td align=\"left\"><b>Sort</b></td>\n";
	echo "			<td align=\"left\"><b>Dates</b></td>\n";
	echo "			<td align=\"left\"><b>Inactive</b></td>\n";
	echo "			<td align=\"left\"></td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "				<select name=\"field\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "					<option value=\"cpname\" SELECTED>Company Name</option>\n";
		echo "					<option value=\"clname\">Last Name</option>\n";
	}
	else
	{
		echo "					<option value=\"clname\" SELECTED>Last Name</option>\n";
	}
	
	echo "					<option value=\"cfname\">First Name</option>\n";
    echo "					<option value=\"cemail\">Email</option>\n";
	echo "					<option value=\"chome\">Home Phone</option>\n";
	echo "					<option value=\"cwork\">Work Phone</option>\n";
	echo "					<option value=\"ccell\">Cell Phone</option>\n";
	echo "					<option value=\"custid\">Lead ID</option>\n";
	echo "					<option value=\"caddr1\">Customer Addr</option>\n";
	echo "					<option value=\"ccity\">Customer City</option>\n";
	echo "					<option value=\"czip1\">Customer Zip</option>\n";
	echo "					<option value=\"saddr1\">Site Addr</option>\n";
	echo "					<option value=\"scity\">Site City</option>\n";
	echo "					<option value=\"szip1\">Site Zip</option>\n";
	echo "				</select><br>\n";
	echo "				<input type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Information in this Field\"></td>\n";
	echo "			</td>\n";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "				<select name=\"order\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "					<option value=\"updated\">Last Update</option>\n";
		echo "					<option value=\"cpname\" SELECTED>Company Name</option>\n";
		echo "					<option value=\"clname\">Last Name</option>\n";
	}
	else
	{
		echo "					<option value=\"clname\" SELECTED>Last Name</option>\n";
		echo "					<option value=\"updated\">Last Update</option>\n";
	}
	
	echo "					<option value=\"cfname\">First Name</option>\n";
	echo "					<option value=\"custid\">Lead ID</option>\n";
	echo "					<option value=\"scity\">Site City</option>\n";
	echo "					<option value=\"szip1\">Site Zip Code</option>\n";
	echo "					<option value=\"added\">Date Added</option>\n";
	echo "				</select><br>\n";
	echo "				<select name=\"dir\">\n";
	echo "					<option value=\"asc\">Ascending</option>\n";
	echo "					<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "				</select>\n";
	echo "			</td>\n";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "				<select name=\"dtype\">\n";
	echo "					<option value=\"added\">Date Added</option>\n";
	echo "					<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "				</select><br>\n";
	echo "				<input class=\"datesp\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\"><br>\n";
	echo "				<input class=\"datesp\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
	echo "			</td>";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "				<select name=\"showdupe\">\n";
	echo "					<option value=\"0\">No</option>\n";
	echo "					<option value=\"1\">Yes</option>\n";
	echo "				</select>\n";
	echo "			</td>";
	echo "			<td align=\"left\" valign=\"top\">\n";
	echo "				<button>Submit</button>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "	</table>\n";
	echo "</form>\n";
	echo "</div>\n";
}

function LeadSearchPanel() {
	$dev_ar= array(SYS_ADMIN);
	//echo 'xx';
	unset($_SESSION['tqry']);
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
	unset($_SESSION['d3']);
	unset($_SESSION['d4']);
	unset($_SESSION['d5']);
	unset($_SESSION['d6']);
	unset($_SESSION['d7']);
	unset($_SESSION['d8']);
	unset($_SESSION['et_uid']);
	
	$cr_ar=array();
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 AND ivr!=1 ORDER BY name ASC;";
	$res = mssql_query($qry);
	
	while ($row = mssql_fetch_array($res))
	{
		$lsrc_ar[$row['statusid']]=array('oid'=>$row['oid'],'name'=>$row['name']);
	}

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 AND (oid=0 OR oid=".(int) $_SESSION['officeid'].") ORDER BY name ASC;";
	$res0 = mssql_query($qry0);
	
	while ($row0 = mssql_fetch_array($res0))
	{
		$lres_ar[$row0['statusid']]=array('oid'=>$row0['oid'],'name'=>$row0['name']);
	}

	$qry1  = "SELECT ";
	$qry1 .= "	 s.securityid ";
	$qry1 .= "	,s.lname ";
	$qry1 .= "	,s.fname ";
	$qry1 .= "	,SUBSTRING(s.slevel,13,1) as slevel ";
	$qry1 .= "	,(SELECT count(cid) FROM cinfo WHERE dupe=0 AND jobid='0' AND securityid=S.securityid) as lcnt ";
	$qry1 .= "FROM  ";
	$qry1 .= "	security AS s ";
	$qry1 .= "WHERE  ";
	$qry1 .= "	s.officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=$_SESSION['securityid'] && $_SESSION['asstto']!=0)
		{
			$qry1 .= "	and s.sidm=".$_SESSION['asstto']." OR s.securityid=".$_SESSION['securityid']." ";
		}
		else
		{
			$qry1 .= "	and s.sidm=".$_SESSION['securityid']." OR s.securityid=".$_SESSION['securityid']." ";
		}
	}
	elseif ($_SESSION['llev'] <= 3)
	{
		$qry1 .= "	and s.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry1 .= "ORDER BY ";
	$qry1 .= "	SUBSTRING(s.slevel,13,1) DESC, ";
	$qry1 .= "	s.lname ASC; ";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	$qry2 = "SELECT ldexport,gm,am FROM offices WHERE officeid=".(int) $_SESSION['officeid'].";";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
	
	$qry3 = "SELECT securityid,lname,fname,slevel,gmreports FROM security WHERE securityid=".(int) $_SESSION['securityid'].";";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
	
	if ($_SESSION['officeid']==193)
	{
		$mar_ar=array();
		$qryM = "SELECT DISTINCT(market) as markets FROM cinfo WHERE officeid=".(int) $_SESSION['officeid']." and dupe!=1;";
		$resM = mssql_query($qryM);
		$nrowM= mssql_num_rows($resM);
		
		if ($nrowM > 0)
		{
			while ($rowM = mssql_fetch_array($resM))
			{
				$mar_ar[]=$rowM['markets'];
			}
		}
	}
	
	//$acclist=explode(",",$_SESSION['aid']);
	
	if (in_array($_SESSION['securityid'],$dev_ar))
	{
		$tbgS='transnb';
	}
	else
	{
		$tbgS='gray';
	}

	$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	$hlpnd=1;

	echo "<script type=\"text/javascript\" src=\"js/jquery_search_panel_DEV.js?".time()."\"></script>\n";
	echo "<table width=\"950px\" align=\"center\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<div class=\"outerrnd  noPrint\">\n";
	//echo "			<table border=\"0\" width=\"100%\">\n";
	//echo "				<tr>\n";
	//echo "					<td>\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr class=\"tblhd\">\n";
	
	if ($_SESSION['officeid']==199)
	{
		echo "								<td align=\"left\"><b>Vendor Search</b></td>\n";
	}
	else
	{
		echo "								<td align=\"left\"><b>Lead Search</b> <img class=\"getHelpNode\" id=\"LeadSearchPanel\" src=\"images/help.png\" title=\"Lead Search Help\"></td>\n";
	}
	
	echo "								<td align=\"right\">\n";
	//echo "									<img class=\"getHelpNode\" id=\"LeadSearchPanel\" src=\"images/help.png\" title=\"Leads Search Panel Help\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td colspan=\"2\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "											<td align=\"left\" colspan=\"2\"><b>Search Type</b></td>\n";
	echo "											<td align=\"left\"><strong>Data Field</strong></td>\n";
	echo "											<td align=\"left\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{		
		echo "<b>Market</b>\n";
	}
	
	echo "											</td>\n";
	echo "											<td align=\"left\"><b>Sort by</b></td>\n";
	echo "											<td align=\"left\"><b>Direction</b></td>\n";
	echo "											<td align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">Aged 30+</td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "											<td align=\"center\">Inactive</td>\n";
	}
	
	echo "											<td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">Address</td>\n";
	echo "											<td align=\"center\" title=\"Select the number of comments to display\">Comments</td>\n";
	echo "											<td align=\"left\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	
	// String Search
	echo "         		<form name=\"tsearch1\" method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "				<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "											<td align=\"right\">\n";
	echo "												<img class=\"getHelpNode\" id=\"LeadSearchPanelText\" src=\"images/help.png\" title=\"Lead Search Help\">\n";
	echo "											</td>\n";
	echo "											<td align=\"right\">\n";
	echo "												<select name=\"field\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
		echo "													<option value=\"clname\">Last Name</option>\n";
	}
	else
	{
		echo "													<option value=\"clname\" SELECTED>Last Name</option>\n";
	}
	
	echo "													<option value=\"cfname\">First Name</option>\n";
    echo "                                    				<option value=\"cemail\">Email</option>\n";
	echo "                                    				<option value=\"chome\">Home Phone</option>\n";
	echo "                                    				<option value=\"cwork\">Work Phone</option>\n";
	echo "                                    				<option value=\"ccell\">Cell Phone</option>\n";
	echo "                                    				<option value=\"custid\">Lead ID</option>\n";
	echo "                                    				<option value=\"caddr1\">Customer Addr</option>\n";
	echo "                                    				<option value=\"ccity\">Customer City</option>\n";
	echo "                                    				<option value=\"czip1\">Customer Zip</option>\n";
	echo "                                    				<option value=\"saddr1\">Site Addr</option>\n";
	echo "                                    				<option value=\"scity\">Site City</option>\n";
	echo "                                    				<option value=\"szip1\">Site Zip</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\"><input type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Information in this Field\"></td>\n";
	echo "											<td align=\"left\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "												<select name=\"market\">\n";
		
		foreach ($mar_ar as $nM => $vM)
		{
			echo "												<option value=\"".$vM."\">".$vM."</option>\n";
		}
		
		echo "												</select>\n";
	}
	
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<select name=\"order\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "													<option value=\"updated\">Last Update</option>\n";
		echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
		echo "													<option value=\"clname\">Last Name</option>\n";
	}
	else
	{
		echo "													<option value=\"clname\" SELECTED>Last Name</option>\n";
		echo "													<option value=\"updated\">Last Update</option>\n";
	}
	
	echo "													<option value=\"cfname\">First Name</option>\n";
	echo "													<option value=\"custid\">Lead ID</option>\n";
	echo "													<option value=\"scity\">Site City</option>\n";
	echo "													<option value=\"szip1\">Site Zip Code</option>\n";
	echo "													<option value=\"added\">Date Added</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<select name=\"dir\">\n";
	echo "													<option value=\"asc\">Ascending</option>\n";
	echo "													<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "												</select>\n";
	echo "											</td>";
	echo "											<td align=\"center\"></td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "											<td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
		echo "												<select name=\"showdupe\">\n";
		echo "													<option value=\"0\">No</option>\n";
		echo "													<option value=\"1\">Yes</option>\n";
		echo "												</select>\n";
		echo "											</td>\n";
	}

	echo "											<td align=\"center\" title=\"Check this box to include the Address and Email in the displayed search results\">\n";
	echo "												<select name=\"incaddr\">\n";
	echo "													<option value=\"0\">No</option>\n";
	echo "													<option value=\"1\">Yes</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"center\">\n";
	echo "												<select name=\"cmtcnt\">\n";
	echo "													<option value=\"0\">0</option>\n";
	echo "													<option value=\"1\">1</option>\n";
	echo "													<option value=\"2\">2</option>\n";
	echo "													<option value=\"3\">3</option>\n";
	echo "													<option value=\"4\">4</option>\n";
	echo "													<option value=\"5\">5</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "											<td align=\"right\">\n";
	echo "												<select name=\"dtype\">\n";
	echo "													<option value=\"added\">Date Added</option>\n";
	echo "													<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<input class=\"datesp\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
	echo "												<input class=\"datesp\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
	echo "											</td>\n";
	echo "											<td align=\"left\" colspan=\"7\">(Date Optional)</td>\n";
	echo "										</tr>\n";
	echo "         			</form>\n";
	
	if ($_SESSION['officeid']!=199)
	{
		echo "										<tr>\n";
		echo "                              		<td align=\"center\" colspan=\"10\"><hr width=\"100%\"></td>\n";
		echo "										</tr>\n";
		
		// Lead Source
		echo "										<tr>\n";
		echo "											<td align=\"right\">\n";
		echo "												<img class=\"getHelpNode\" id=\"LeadSearchPanelSource\" src=\"images/help.png\" title=\"Lead Search Help\">\n";
		echo "											</td>\n";
		echo "         								<form name=\"tsearch2\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"srcstatus\">\n";
		echo "											<input type=\"hidden\" name=\"field\" value=\"source\">\n";
		echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid."\">\n";
		echo "                              	<td align=\"right\"><b>Source Code</b>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"ssearch\">\n";
	
		foreach ($lsrc_ar as $ns=>$vs)
		{
			if ($ns==0)
			{
				echo "                                    	<option value=\"".$ns."\">bluehaven.com</option>\n";
			}
			elseif ($ns==1)
			{
				echo "                                    	<option value=\"".$ns."\">Manual</option>\n";
			}
			else
			{
				if ($vs['oid']==0 || $vs['oid']==$_SESSION['officeid'])
				{
					echo "                                    	<option value=\"".$ns."\">".$vs['name']."</option>\n";
				}
			}
		}
	
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "											<td align=\"left\">\n";
	
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "												<select name=\"market\">\n";
			
			foreach ($mar_ar as $nM => $vM)
			{
				echo "												<option value=\"".$vM."\">".$vM."</option>\n";
			}
			
			echo "												</select>\n";
		}
		
		echo "											</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "                                    	<option value=\"clname\">Last Name</option>\n";
		echo "										<option value=\"cfname\">First Name</option>\n";
		echo "                                    	<option value=\"custid\">Lead ID</option>\n";
		echo "										<option value=\"scity\">Site City</option>\n";
		echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "                                    <select name=\"dir\">\n";
		echo "                                    	<option value=\"asc\">Ascending</option>\n";
		echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "                                    </select>\n";
		echo "									</td>";
		echo "                                 <td align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">\n";
		echo "										<select name=\"showaged\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	
		if ($_SESSION['llev'] >= 5)
		{
			echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
			echo "										<select name=\"showdupe\">\n";
			echo "											<option value=\"0\">No</option>\n";
			echo "											<option value=\"1\">Yes</option>\n";
			echo "										</select>\n";
			echo "								   </td>\n";
		}
	
		echo "                                 <td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">\n";
		echo "										<select name=\"incaddr\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"center\">\n";
		echo "                                    <select name=\"cmtcnt\">\n";
		echo "                                    	<option value=\"0\">0</option>\n";
		echo "                                    	<option value=\"1\">1</option>\n";
		echo "                                    	<option value=\"2\">2</option>\n";
		echo "                                    	<option value=\"3\">3</option>\n";
		echo "                                    	<option value=\"4\">4</option>\n";
		echo "                                    	<option value=\"5\">5</option>\n";
		echo "                                    </select>\n";
		echo "								   </td>\n";
		echo "                                	<td align=\"left\">\n";
		echo "										<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
		echo "									</td>\n";
		echo "								<tr>\n";
		echo "											<td align=\"right\">\n";
	
		echo "											</td>\n";
		echo "                              	<td align=\"right\">\n";
		echo "                                   	<select name=\"dtype\">\n";
		echo "                                    		<option value=\"added\">Date Added</option>\n";
		echo "                                    		<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    	</select>\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "										<input class=\"datesp\" type=\"text\" name=\"d1\" id=\"d3\" size=\"11\">\n";
		echo "										<input class=\"datesp\" type=\"text\" name=\"d2\" id=\"d4\" size=\"11\">\n";
		echo "									</td>\n";
		echo "                                 	<td align=\"left\" colspan=\"2\">(Date Optional)</td>\n";
		echo "                                 	<td colspan=\"4\">\n";
		
		//selectemaillisttemplate();
		
		echo "									</td>\n";
		echo "                                 	<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "								</tr>\n";
		echo "         						</form>\n";
		echo "										<tr>\n";
		echo "                              			<td align=\"center\" colspan=\"10\"><hr width=\"100%\"></td>\n";
		echo "										</tr>\n";
		
		// Lead Result
		echo "										<tr>\n";
		echo "											<td align=\"right\">\n";
		echo "												<img class=\"getHelpNode\" id=\"LeadSearchPanelResult\" src=\"images/help.png\" title=\"Lead Search Help\">\n";
		echo "											</td>\n";
		echo "         								<form name=\"tsearch3\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"resstatus\">\n";
		echo "											<input type=\"hidden\" name=\"field\" value=\"stage\">\n";
		echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid."\">\n";
		echo "                              			<td align=\"right\"><b>Result Code</b>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"ssearch\">\n";
	
		foreach ($lres_ar as $nr => $vr)
		{
			echo "                                    	<option value=\"".$nr."\">".$vr['name']."</option>\n";
		}
	
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "											<td align=\"left\">\n";
	
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "												<select name=\"market\">\n";
			
			foreach ($mar_ar as $nM => $vM)
			{
				echo "												<option value=\"".$vM."\">".$vM."</option>\n";
			}
			
			echo "												</select>\n";
		}
		
		echo "											</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "                                    	<option value=\"clname\">Last Name</option>\n";
		echo "										<option value=\"cfname\">First Name</option>\n";
		echo "                                    	<option value=\"custid\">Lead ID</option>\n";
		echo "										<option value=\"scity\">Site City</option>\n";
		echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "                                    <select name=\"dir\">\n";
		echo "                                    	<option value=\"asc\">Ascending</option>\n";
		echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "                                    </select>\n";
		echo "								   </td>";
		echo "                                 <td align=\"center\" title=\"Check this box to include Leads that have not been updated within the last 30 days\">\n";
		echo "										<select name=\"showaged\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	
		if ($_SESSION['llev'] >= 5)
		{
			echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
			echo "										<select name=\"showdupe\">\n";
			echo "											<option value=\"0\">No</option>\n";
			echo "											<option value=\"1\">Yes</option>\n";
			echo "										</select>\n";
			echo "								   </td>\n";
		}
	
		echo "                                 <td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">\n";
		echo "										<select name=\"incaddr\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"center\">\n";
		echo "                                    <select name=\"cmtcnt\">\n";
		echo "                                    	<option value=\"0\">0</option>\n";
		echo "                                    	<option value=\"1\">1</option>\n";
		echo "                                    	<option value=\"2\">2</option>\n";
		echo "                                    	<option value=\"3\">3</option>\n";
		echo "                                    	<option value=\"4\">4</option>\n";
		echo "                                    	<option value=\"5\">5</option>\n";
		echo "                                    </select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
		echo "									</td>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\">\n";
		
		echo "											</td>\n";
		echo "                              	<td align=\"right\">\n";
		echo "                                    <select name=\"dtype\">\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "										<input class=\"datesp\" type=\"text\" name=\"d1\" id=\"d5\" size=\"11\">\n";
		echo "										<input class=\"datesp\" type=\"text\" name=\"d2\" id=\"d6\" size=\"11\">\n";
		echo "									</td>\n";
		echo "                                 	<td align=\"left\" colspan=\"2\">(Date Optional)</td>\n";
		echo "                                 	<td colspan=\"4\">\n";
		
		//selectemaillisttemplate();
		
		echo "											</td>\n";
		echo "                                 			<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "										</tr>\n";
		echo "         								</form>\n";
	}
	
	if ($nrow1 > 0)
	{
		// SalesRep
		echo "										<tr>\n";
		echo "                              			<td align=\"center\" colspan=\"10\"><hr width=\"100%\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\">\n";
		echo "												<img class=\"getHelpNode\" id=\"LeadSearchPanelSalesRep\" src=\"images/help.png\" title=\"Lead Search Help\">\n";
		echo "											</td>\n";
		echo "         								<form name=\"tsearch4\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
		echo "											<input type=\"hidden\" name=\"field\" value=\"securityid\">\n";
		
		if ($_SESSION['officeid']==193 and $_SESSION['officeid']==199)
		{
			echo "                              	<td align=\"right\"><b>Manager</b></td>\n";
		}
		else
		{
			echo "                              	<td align=\"right\" title=\"JMS recognized Sales Reps. The number in parenthesis represents the total number of leads allocated to that Sales Rep. This number does not include Leads that have gone to contract. If this list is empty or a name is missing please contact BHNM IT Support \"><b>Sales Rep</b></td>\n";
		}
		
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"ssearch\">\n";
	
		$dtxt='';
		while ($row1 = mssql_fetch_array($res1))
		{
			if ($row1['securityid']==$row2['am'] or $_SESSION['securityid']==$row1['securityid'])
			{
				if ($row1['slevel']==0)
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontred\" SELECTED>".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
				else
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontblack\" SELECTED>".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
			}
			else
			{
				if ($row1['slevel']==0)
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontred\">".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
				else
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontblack\">".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
			}
		}
	
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "											<td align=\"left\">\n";
	
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "												<select name=\"market\">\n";
			
			foreach ($mar_ar as $nM => $vM)
			{
				echo "												<option value=\"".$vM."\">".$vM."</option>\n";
			}
			
			echo "												</select>\n";
		}
		
		echo "											</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"order\">\n";
		
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
			echo "													<option value=\"clname\">Last Name</option>\n";
		}
		else
		{
			echo "													<option value=\"clname\" SELECTED>Last Name</option>\n";
		}
		//echo "                                    	<option value=\"clname\">Last Name</option>\n";
		echo "										<option value=\"cfname\">First Name</option>\n";
		echo "                                    	<option value=\"custid\">Lead ID</option>\n";
		echo "										<option value=\"scity\">Site City</option>\n";
		echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "                                    <select name=\"dir\">\n";
		echo "                                    	<option value=\"asc\">Ascending</option>\n";
		echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "                                    </select>\n";
		echo "									</td>";
		echo "                                 <td align=\"center\" title=\"Check this box to include Leads that have not been updated within the last 30 days\">\n";
		echo "										<select name=\"showaged\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	
		if ($_SESSION['llev'] >= 5)
		{
			echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
			echo "										<select name=\"showdupe\">\n";
			echo "											<option value=\"0\">No</option>\n";
			echo "											<option value=\"1\">Yes</option>\n";
			echo "										</select>\n";
			echo "								   </td>\n";
		}
	
		echo "                                 <td align=\"center\" title=\"Check this box to include the Address and Email in the displayed search results\">\n";
		echo "										<select name=\"incaddr\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"center\">\n";
		echo "                                    <select name=\"cmtcnt\">\n";
		echo "                                    	<option value=\"0\">0</option>\n";
		echo "                                    	<option value=\"1\">1</option>\n";
		echo "                                    	<option value=\"2\">2</option>\n";
		echo "                                    	<option value=\"3\">3</option>\n";
		echo "                                    	<option value=\"4\">4</option>\n";
		echo "                                    	<option value=\"5\">5</option>\n";
		echo "                                    </select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "											<td align=\"right\">\n";

		echo "											</td>\n";
		echo "                              	<td align=\"right\">\n";
		echo "                                    <select name=\"dtype\">\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "									<input class=\"datesp\" type=\"text\" name=\"d1\" id=\"d7\" size=\"11\">\n";
		echo "									<input class=\"datesp\" type=\"text\" name=\"d2\" id=\"d8\" size=\"11\">\n";
		echo "									</td>\n";
		echo "                                 	<td align=\"left\" colspan=\"2\">(Date Optional)</td>\n";
		echo "                              	<td align=\"left\" colspan=\"2\"><b>Source</b>\n";
		echo "                                    <select name=\"lsource\">\n";
		echo "                                    		<option value=\"NA\">All</option>\n";

		foreach ($lsrc_ar as $ns => $vs)
		{
			if ($ns==0)
			{
				echo "                                    	<option value=\"".$ns."\">bluehaven.com</option>\n";
			}
			elseif ($ns==1)
			{
				echo "                                    	<option value=\"".$ns."\">Manual</option>\n";
			}
			else
			{
				if ($vs['oid']==0 || $vs['oid']==$_SESSION['officeid'])
				{
					echo "                                    	<option value=\"".$ns."\">".$vs['name']."</option>\n";
				}
			}
		}

		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\" colspan=\"2\"><b>Result</b>\n";
		echo "                                    <select name=\"lresult\">\n";
		echo "                                    		<option value=\"NA\">All</option>\n";

		foreach ($lres_ar as $nr => $vr)
		{
			echo "                                    	<option value=\"".$nr."\">".$vr['name']."</option>\n";
		}

		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "										</tr>\n";
		echo "         								</form>\n";
	}
	
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	//echo "					</td>\n";
	//echo "				</tr>\n";
	//echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function LeadAppointmentView() {
    $age30=2592000; //30 Days
    $age15=1296000; //15 Days
    $age07=604800; // 7 Days
    $curr_date=getdate();
    $acclist=explode(",",$_SESSION['aid']);

	$ndate=(isset($_REQUEST['appt_mo']) and !empty($_REQUEST['appt_mo']))?date("F", mktime(0, 0, 0, $_REQUEST['appt_mo'], 1, $curr_date['year'])):$curr_date['month'];
	$mdate=(isset($_REQUEST['appt_mo']) and !empty($_REQUEST['appt_mo']))?$_REQUEST['appt_mo']:$curr_date['mon'];
	$ddate=(isset($_REQUEST['appt_da']) and !empty($_REQUEST['appt_da']))?$_REQUEST['appt_da']:$curr_date['mday'];
	$ydate=(isset($_REQUEST['appt_yr']) and !empty($_REQUEST['appt_yr']))?$_REQUEST['appt_yr']:$curr_date['year'];

    $pstyr=2004;
    $futyr=$curr_date['year']+1;
    //$qry   = "select * from cinfo where officeid='".$_SESSION['officeid']."' and appt_mo='".$mdate."' and appt_da!='0' and appt_yr='".$ydate."' and dupe!=1 and hold!=1 order by appt_da DESC;";
	$qry   = "select * from cinfo where officeid='".$_SESSION['officeid']."' and appt_mo='".$mdate."' and appt_da!='0' and appt_yr='".$ydate."' and dupe!=1 order by appt_da DESC,appt_pa DESC,appt_hr DESC,appt_mn DESC;";
    $res   = mssql_query($qry);
    $nrows = mssql_num_rows($res);

    /*if ($_SESSION['securityid']==26)
    {
            echo $qry;
    }*/

    if ($nrows < 1)
    {
		echo "<div class=\"outerrnd\">\n";
        echo "<table align=\"center\" width=\"400px\">\n";
        echo "   <tr>\n";
        echo "      <td>\n";
        echo "         <b>No Appointments for ".$ndate.", ".$curr_date['year']."</b>\n";
        echo "      </td>\n";
        echo "   </tr>\n";
        echo "</table>\n";
		echo "</div>\n";
    }
    else
    {
		echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
        echo "<table align=\"center\" width=\"950px\">\n";
        echo "  <tr>\n";
        echo "      <td align=\"left\">\n";
        echo "          <form method=\"post\">\n";
        echo "          <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
        echo "          <input type=\"hidden\" name=\"call\" value=\"appts\">\n";
        echo "          <table width=\"100%\">\n";
        echo "              <tr>\n";
        echo "                  <td align=\"left\"><b>Appointments</b> \n";
        echo "                      <select name=\"appt_mo\">\n";

        for ($x = 1; $x <= 12; $x++)
        {
            $m_name=date("F", mktime(0, 0, 0, $x, 1, $curr_date['year']));
            if ($x == $mdate)
            {
                echo "                          <option value=\"".$x."\" SELECTED>".$m_name."</option>\n";
            }
            else
            {
                echo "                          <option value=\"".$x."\">".$m_name."</option>\n";
            }
        }

        echo "                      </select>\n";
        echo "                      <select name=\"appt_yr\">\n";

        for ($x = $pstyr; $x <= $futyr; $x++)
        {
            if ($x == $ydate)
            {
                echo "                          <option value=\"".$x."\" SELECTED>".$x."</option>\n";
            }
            else
            {
                echo "                          <option value=\"".$x."\">".$x."</option>\n";
            }
        }

        echo "                      </select>\n";
        echo "                      <input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
        echo "                  </td>\n";
        echo "                  <td align=\"left\"><b>".$_SESSION['offname']."</b></td>\n";
        echo "                  <td align=\"right\"><b>Lead</b> Color Codes:</td>\n";
        echo "                  <td align=\"center\" class=\"white\" width=\"75px\"><b>Normal</b></td>\n";
        echo "                  <td align=\"center\" class=\"lightgreen\" width=\"75px\"><b>Appt Today</b></td>\n";
        echo "                  <td align=\"center\" class=\"yellow\" width=\"75px\"><b>Aged 7 Days</b></td>\n";
		echo "                  <td align=\"center\" class=\"magenta\" width=\"75px\"><b>Callback Set</b></td>\n";
        echo "                  <td align=\"center\" width=\"20px\">\n";
        
        HelpNode('LeadApptListing',1);
        
        echo "                  </td>\n";
        echo "              </tr>\n";
        echo "          </table>\n";
        echo "          </form>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "      <td>\n";
        echo "         <table width=\"100%\">\n";
        echo "            <tr>\n";
        echo "               <td align=\"left\">\n";
        echo "                  <table width=\"100%\">\n";
        echo "                      <tr class=\"tblhd\">\n";
        echo "						    <td align=\"center\"><b>Lead ID</b></td>\n";
        echo "							<td align=\"left\"><b>Last Name</b></td>\n";
        echo "							<td align=\"left\"><b>First Name</b></td>\n";
        echo "                     	    <td align=\"left\"><b>Phone</b></td>\n";
        echo "							<td align=\"left\"><b>Assigned</b></td>\n";
        echo "							<td align=\"left\"><b>Added</b></td>\n";
        echo "							<td align=\"left\"><b>Updated</b></td>\n";
        echo "                     	    <td align=\"left\"><b>Apptmnt</b></td>\n";
        echo "							<td align=\"left\"><b>Source</b></td>\n";
        echo "							<td align=\"left\"><b>Status</b></td>\n";
        echo "                     	    <td align=\"right\">\n";
        echo "                     	</td>\n";
        echo "                  	</tr>\n";

        $ts_tdate=getdate();
        while($row=mssql_fetch_array($res))
        {
                $nrowsA =0;

                $qryC = "SELECT fname,lname,securityid,sidm FROM security WHERE securityid='".$row['securityid']."'";
                $resC = mssql_query($qryC);
                $rowC = mssql_fetch_array($resC);

                //$idarray=accessidlist(1,5,$rowC['sidm'],$rowC['securityid']);

                $qryD = "SELECT estid,cid FROM est WHERE officeid='".$_SESSION['officeid']."' AND cid='".$row['custid']."';";
                $resD = mssql_query($qryD);
                $rowD = mssql_fetch_array($resD);
                $nrowD= mssql_num_rows($resD);

                $qryE = "SELECT statusid,name FROM leadstatuscodes WHERE statusid='".$row['stage']."' and active=1;";
                $resE = mssql_query($qryE);
                $rowE = mssql_fetch_array($resE);
                
                $qryF = "SELECT statusid,name FROM leadstatuscodes WHERE statusid='".$row['source']."';";
                $resF = mssql_query($qryF);
                $rowF = mssql_fetch_array($resF);

                $uid  =md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];

                if ($nrowD==0)
                {
                        if (in_array($row['securityid'],$acclist)||$_SESSION['llev'] >= 5)
                        {
                                if (!empty($row['added']))
                                {
                                        $ts_odate=strtotime($row['added']);
                                        $odate = date("m-d-Y", strtotime($row['added']));
                                }
                                else
                                {
                                        $ts_odate=0;
                                        $odate = "";
                                }

                                if (!empty($row['updated'])||$row['updated']!="")
                                {
                                        $ts_udate=strtotime($row['updated']);
                                        $udate = date("m-d-Y", strtotime($row['updated']));
                                }
                                else
                                {
                                        $ts_udate=0;
                                        $udate = "";
                                }

                                if ($row['appt_mo']!=0)
                                {
                                        if ($row['appt_pa']==1)
                                        {
                                                $pa="AM";
                                        }
                                        else
                                        {
                                                $pa="PM";
                                        }
                                        $adate = str_pad($row['appt_mo'],2,"0",STR_PAD_LEFT)."-".str_pad($row['appt_da'],2,"0",STR_PAD_LEFT)."-".$row['appt_yr']." (".$row['appt_hr'].":".str_pad($row['appt_mn'],2,"0",STR_PAD_LEFT)." ".$pa.")";
                                }
                                else
                                {
                                        $adate = "";
                                }

                                $udiff_date=$ts_tdate[0]-$ts_udate;
                                $odiff_date=$ts_tdate[0]-$ts_odate;

                                if ($row['dupe']==1)
                                {
                                        $tbg="red_und";
                                }
								/*
                                elseif ($row['hold']==1)
                                {
                                        $tbg="magenta_und";
                                }
								*/
                                else
                                {
                                        if ($ts_udate == 0)
                                        {
                                                if ($odiff_date > $age07)
                                                {
                                                        $tbg="yel_und";
                                                }
                                                else
                                                {
                                                        $tbg="wh_und";
                                                }
                                        }
                                        elseif ($udiff_date > $age07)
                                        {
                                                $tbg="yel_und";
                                        }
                                        else
                                        {
                                                if ($row['appt_mo']==date("n") && $row['appt_da']==date("j") && $row['appt_yr']==date("Y"))
                                                {
                                                        $tbg="grn_und";
                                                }
												elseif ($row['hold']==1)
												{
													$tbg="magenta_und";
												}
                                                else
                                                {
                                                        $tbg="wh_und";
                                                }
                                        }
                                }

                                if ($row['cconph']=="hm")
                                {
                                        $cphone=$row['chome'];
                                }
                                elseif ($row['cconph']=="wk")
                                {
                                        $cphone=$row['cwork'];
                                }
                                elseif ($row['cconph']=="ce")
                                {
                                        $cphone=$row['ccell'];
                                }
                                else
                                {
                                        $cphone="";
                                }

                                echo "                  <tr class=\"".$tbg."\">\n";
                                echo "                     <td align=\"center\"><b>".$row['custid']."</b></td>\n";
                                echo "                     <td align=\"left\"><b>".$row['clname']."</b></td>\n";
                                echo "                     <td align=\"left\">".$row['cfname']."</td>\n";
                                echo "                     <td align=\"left\"><b>".$cphone."</b></td>\n";
                                echo "                     <td align=\"left\">".$rowC['lname'].", ".$rowC['fname']."</td>\n";
                                echo "                     <td align=\"left\">".$odate."</td>\n";
                                echo "                     <td align=\"left\">".$udate."</td>\n";
                                echo "                     <td align=\"left\">".$adate."</td>\n";

                                if ($row['source']==0)
                                {
                                        echo "                     <td align=\"left\">bluehaven.com</b></td>\n";
                                }
                                elseif ($row['source'] >= 1)
                                {
                                        echo "                     <td align=\"left\">".$rowF['name']."</td>\n";
                                }

                                if ($rowE['statusid']==6)
                                {
                                        echo "                     <td align=\"left\"><b>".$rowE['name']."</b></td>\n";
                                }
                                else
                                {
                                        echo "                     <td align=\"left\">".$rowE['name']."</td>\n";
                                }

                                echo "                     <td align=\"right\">\n";
                                echo "                        <form method=\"POST\">\n";
                                echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
                                echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
                                echo "                           <input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$row['cid']."\">\n";
                                echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
                                echo "				             <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
                                echo "                        </form>\n";
                                echo "                     </td>\n";
                                echo "                  </tr>\n";
                        }
                }
        }

        echo "                  </table>\n";
        echo "               </td>\n";
        echo "            </tr>\n";
        echo "         </table>\n";
        echo "      </td>\n";
        echo "   </tr>\n";
        echo "</table>\n";
		echo "</div>\n";
    }
}

function LeadView($cid=null,$uid=null) {
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    
    if (is_null($cid) || !is_numeric($cid)) {
		echo "<font color=\"red\"><b>ERROR!</b></font> You must provide a Valid Customer ID number.\n";
		exit;
	}
    
    if (empty($uid)) {
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error has occurred.\n";
		exit;
	}
    
	$acl    =explode(",",$_SESSION['aid']);
	$dates	=dateformat();
    $cdata  =getCustData($cid);
    $sdata  =getSystemData($cid);
    //echo session_id();
    //echo '<table><tr><td><pre>';
    //echo count($cdata['dates']);
    //print_r($cdata);
    //echo '</pre></td></tr></table>';
	//exit;
    $cappts=get_AP_list_JSON();
    echo "<script type=\"text/javascript\" src=\"js/jquery_lead_view_new.js?".time()."\"></script>\n";
	echo "<script type=\"text/javascript\">\n";
	echo "	var cappts=".$cappts;
	echo "</script>\n";
	
	echo "<table id=\"tblLeadWrap\" width=\"950px\" style=\"display:none\">\n";
    echo "  <tr>\n";
	echo "      <td align=\"left\" width=\"375px\">\n";
    
	dispHeaderName($cdata);
	
	echo "      </td>\n";
    echo "      <td align=\"right\">\n";
	
    dispLeadButtonMenu($cdata,$sdata['office']['aiupdate']);
    
	echo "      </td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td colspan=\"2\">\n";
	echo "          <table width=\"100%\" cellpadding=\"0\">\n";
	echo "              <tr>\n";
	echo "                  <td colspan=\"2\" align=\"right\">\n";
	
    dispLeadControl($cdata,$sdata);
    
	echo "                  </td>\n";
	echo "              </tr>\n";
	echo "              <tr>\n";
	echo "                  <td valign=\"top\" align=\"left\" width=\"375px\">\n";

    dispName($cdata);
    dispAddress($cdata);
    dispContact($cdata,$sdata);
    dispMarketing($cdata);
    dispPrivacy($cdata);
    //dispHistory($cdata);
	
	echo "                  </td>\n";
	echo "                  <td valign=\"top\" align=\"left\">\n";
    echo "                      <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "                          <tr>\n";
	echo "                              <td width=\"40%\" valign=\"top\">\n";
    
    dispSourceResult($cdata,$sdata);
	
    echo "                              </td>\n";
    echo "                              <td width=\"30%\" valign=\"top\">\n";
    
    dispCallBack($cdata,$sdata);
	
    echo "                              </td>\n";
    echo "                              <td width=\"30%\" valign=\"top\">\n";
    
    dispAppointment($cdata,$sdata);
    
	echo "                              </td>\n";
    echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td colspan=\"3\" valign=\"top\">\n";
    
	displayCommentTable_TED();
    
	echo "                              </td>\n";
	echo "                          </tr>\n";
	echo "                      </table>\n";
	echo "                  </td>\n";
	echo "              </tr>\n";
	echo "          </table>\n";
	echo "      </td>\n";
	echo "</table>\n";
	echo "<span id=\"finalEl\"></span>";
	$qry	= "UPDATE jest..cinfo SET viewed=getdate(), viewedby=".$_SESSION['securityid']." WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$res	= mssql_query($qry);
}

function dispLeadButtonMenu($cdata=null){
    echo "<div class=\"noPrint\">\n";
	echo "  <table>\n";
	echo "      <tr>\n";

	//if ($_SESSION['elev'] >= 1 && $rowC[1]==1)
    if ($_SESSION['elev'] >= 1)
	{
        echo "				<td>\n";
        echo "      			<form method=\"post\">\n";
        echo "         				<input type=\"hidden\" name=\"action\" value=\"est\">\n";
        echo "         				<input type=\"hidden\" name=\"call\" value=\"new\">\n";
        echo "         				<input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
        echo "         				<input type=\"hidden\" name=\"uid\" value=\"".$cdata['tranid']."\">\n";
        echo "         				<input type=\"hidden\" name=\"cid\" value=\"".$cdata['lead']['cid']."\">\n";
        echo "         				<input type=\"hidden\" name=\"estorig\" value=\"".$cdata['srep']['sid']."\">\n";
        echo "         				<input type=\"hidden\" name=\"securityid\" value=\"".$cdata['srep']['sid']."\">\n";
        echo "						<button class=\"btnsysmenu\" title=\"Create a new Quote\">Quote</button>\n";
        echo "					</form>\n";
        echo "				</td>\n";
        echo "				<td>\n";
        echo "      			<form method=\"post\">\n";
        echo "         				<input type=\"hidden\" name=\"action\" value=\"est\">\n";
        echo "         				<input type=\"hidden\" name=\"call\" value=\"matrix0\">\n";
        echo "         				<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
        echo "         				<input type=\"hidden\" name=\"cid\" value=\"".$cdata['lead']['cid']."\">\n";
        echo "         				<input type=\"hidden\" name=\"estorig\" value=\"".$cdata['srep']['sid']."\">\n";
        echo "         				<input type=\"hidden\" name=\"securityid\" value=\"".$cdata['srep']['sid']."\">\n";
        echo "						<button class=\"btnsysmenu\" title=\"Create a new Estimate\">Estimate</button>\n";
        echo "					</form>\n";
        echo "				</td>\n";
	}
	
	if (isset($rowM['filestoreaccess']) && $rowM['filestoreaccess'] >= 9)
	{
		echo "				<td>\n";
		echo "					<form method=\"POST\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
		echo "						<input type=\"hidden\" name=\"cid\" value=\"".$cdata['lead']['cid']."\">\n";
		echo "						<button class=\"btnsysmenu\" title=\"View files for this Lead\">Files</button>\n";
		echo "					</form>\n";
		echo "				</td>\n";
	}
    
    echo "          <td>\n";
	echo "      		<form method=\"post\">\n";
	echo "         			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         			<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	echo "         			<input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
	echo "         			<input type=\"hidden\" name=\"uid\" value=\"".$cdata['tranid']."\">\n";
	echo "         			<input type=\"hidden\" name=\"cid\" value=\"".$cdata['lead']['cid']."\">\n";
	echo "         			<input type=\"hidden\" name=\"custid\" value=\"".$cdata['lead']['cid']."\">\n";
	echo "					<button class=\"btnsysmenu\" title=\"View OneSheet for this Customer\">OneSheet</button>\n";
	echo "				</form>\n";
	echo "          </td>\n";

	if (isset($_SESSION['tqry']))
	{
		echo "				<td>\n";
		echo "         			<form name=\"tsearch1\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
		echo "						<button class=\"btnsysmenu\" title=\"Return to the Last Search Results\">Results</button>\n";
		echo "					</form>\n";
		echo "				</td>\n";
	}
	
    echo "      </tr>\n";
	echo "  </table>\n";
	echo "</div>\n";
}