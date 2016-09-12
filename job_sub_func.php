<?php

function job_search()
{
    $cyr=date("Y");
    
	$acclist=explode(",",$_SESSION['aid']);
	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid=".$_SESSION['officeid']." order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
	
	//$qry2 = "select * from bonus_schedule_config order by brept_yr desc;";
    $qry2 = "select distinct(datepart(yy,added)) as yradded from jobs where officeid=".$_SESSION['officeid']." order by yradded desc;";
	$res2 = mssql_query($qry2);
    $nrow2 = mssql_num_rows($res2);
	
    if ($nrow2 > 0)
    {
        while ($row2 = mssql_fetch_array($res2))
        {
            $byr_ar[]=$row2['yradded'];
        }
        
        array_unshift($byr_ar,(max($byr_ar) +1));
    }
    else
    {
        $byr_ar[]=$cyr;
    }
    
	echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
	echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	//echo "				<tr>\n";
	//echo "					<td>\n";
	//echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr class=\"tblhd\">\n";
	echo "								<td align=\"left\"><b>Job Search Tool</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                  <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Data Field</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Input Parameter</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Sales Year</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Contract/Dig</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Sort by</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Order by</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Renov Only</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>MAS Review</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Inc Errors</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b></b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"incerrs\" value=\"0\">\n";
	echo "                              	<td align=\"right\" valign=\"top\">\n";
	echo "												<select name=\"subq\">\n";
	echo "                                 		            <option value=\"lname\">Customer Last Name</option>\n";
	echo "                                 		            <option value=\"njobid\">Job Number</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input name=\"sval\" size=\"20\" title=\"Enter Full/Partial Customer Name in this Field\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                              		<select name=\"cyear\">\n";
    
    //for ($y = ($cyr+1); $y >= 2005; $y--)
    foreach ($byr_ar as $n=>$y)
	{
        if ((int) $y == (int) $cyr)
        {
            echo "						<option value=\"".$y."\" SELECTED>".$y." </option>\n";
        }
        else
        {
            echo "						<option value=\"".$y."\">".$y."</option>\n";
        }
    }
    
	echo "                              		</select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"cyearfield\">\n";
	echo "                                 		<option value=\"J2.contractdate\">Contract Date</option>\n";
	echo "                                 		<option value=\"J1.digdate\">Dig Date</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"J1.njobid\" SELECTED>Job #</option>\n";
	echo "                                 		<option value=\"J2.contractdate\">Contract Date</option>\n";
	echo "                                 		<option value=\"J1.added\">Insert Date</option>\n";
	echo "                                 		<option value=\"C.clname\">Last Name</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                 		<option value=\"DESC\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\">\n";
	echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this box to Show only Renovations\">\n";
	echo "											</td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\">\n";
	echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"maspos\" value=\"1\" title=\"Check this box to include Jobs that have been flagged for MAS Review or processed into MAS\">\n";
	echo "											</td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\">\n";
	echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"incerrs\" value=\"1\" title=\"Check this box to include Jobs that may have erroneous Job Numbers\">\n";
	echo "											</td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\">\n";
	//echo "												<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"  title=\"Click to Perform Search\">\n";
    echo "                                      <button class=\"btnsysmenu\">Search</button>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                                 <td align=\"right\" valign=\"top\">\n";
	echo "                                    <select name=\"ctrinsdate\">\n";
	echo "                                 		<option value=\"J2.contractdate\">Contract Date</option>\n";
	echo "                                 		<option value=\"J1.added\">Insert Date</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" id=\"d1\" size=\"20\" title=\"Begin Date\"><br>";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" id=\"d2\" size=\"20\" title=\"End Date\">\n";
	echo "         			</form>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "				<tr>\n";
	echo "                                 <td align=\"center\" colspan=\"10\"><hr width=\"90%\"</td>\n";
	echo "				</tr>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "										<tr>\n";
		echo "         								<form method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
		echo "											<input type=\"hidden\" name=\"incerrs\" value=\"0\">\n";
		echo "                              	<td align=\"right\" valign=\"bottom\"><b>Salesman:</b></td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"assigned\">\n";

		while ($row1 = mssql_fetch_array($res1))
		{
			if (in_array($row1['securityid'],$acclist))
			{
				$secl=explode(",",$row1['slevel']);
				if ($secl[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}

				echo "                                    	<option value=\"".$row1['securityid']."\" class=\"".$ostyle."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
			}
		}

		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                              		<select name=\"cyear\">\n";
		
		//foreach ($byr_ar as $n3=>$v3)
		//{
		//	echo "                                    	<option value=\"".$v3."\">".$v3."</option>\n";
		//}
        
        //for ($y = ($cyr+1); $y >= 2005; $y--)
        foreach ($byr_ar as $n=>$y)
        {
            if ((int) $y == (int) $cyr)
            {
                echo "						<option value=\"".$y."\" SELECTED>".$y." </option>\n";
            }
            else
            {
                echo "						<option value=\"".$y."\">".$y."</option>\n";
            }
        }
		
		echo "                              		</select>\n";
		echo "											</td>\n";
		//J2.contractdate
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"cyearfield\">\n";
		echo "                                 		<option value=\"J1.contractdate\">Contract Date</option>\n";
		echo "                                 		<option value=\"J1.digdate\">Dig Date</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "                                 		<option value=\"J1.jobid\" SELECTED>Job #</option>\n";
		echo "                                 		<option value=\"J2.contractdate\">Contract Date</option>\n";
		echo "                                 		<option value=\"J1.added\">Insert Date</option>\n";
		echo "                                 		<option value=\"C.clname\">Last Name</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"ascdesc\">\n";
		echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
		echo "                                 		<option value=\"DESC\">Descending</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                                 <td align=\"center\" valign=\"bottom\">\n";
		echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this box to Show only Renovations\">\n";
		echo "											</td>\n";
		echo "                                 <td align=\"center\" valign=\"bottom\">\n";
		echo "											</td>\n";
		echo "                                 <td align=\"center\" valign=\"bottom\">\n";
		echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"incerrs\" value=\"1\">\n";
		echo "											</td>\n";
		echo "                                 <td align=\"center\" valign=\"bottom\">\n";
		//echo "												<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
        echo "                                      <button class=\"btnsysmenu\">Search</button>\n";
		echo "											</td>\n";
		echo "         								</form>\n";
		echo "										</tr>\n";
	}

	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	//echo "					</td>\n";
	//echo "				</tr>\n";
	//echo "			</table>\n";
	echo "</div>\n";
	
	//$_REQUEST['tset']=992;
	
	//echo "TSET: ".$_REQUEST['tset'];
	
}

function list_jobs()
{
	$officeid	=$_SESSION['officeid'];
	$securityid	=$_SESSION['securityid'];
	$acclist	=explode(",",$_SESSION['aid']);
	$njblist	=array();
	$brdr		=0;
	$jcost		=0;
	$jprof		=0;
	//$mjar		=array();
	$mjar1		=array();
	$mjar2		=array();
	$mjar		=get_MAS_Job_Status();

	//print_r($mjar);
	$qrypre = "SELECT enmas,enexp,masimport,tgp,vgp,accountingsystem,enquickbooks FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre = mssql_query($qrypre);
	$rowpre = mssql_fetch_array($respre);

	if (isset($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	else
	{
		$order="jobid";
	}

	if (isset($_REQUEST['ascdesc']))
	{
		$dir=$_REQUEST['ascdesc'];
	}
	else
	{
		$dir="ASC";
	}

	$qrypreA = "SELECT * FROM bonus_schedule_config WHERE brept_yr='".$_REQUEST['cyear']."';";
	$respreA = mssql_query($qrypreA);
	$nrowpreA= mssql_num_rows($respreA);
    
    if ($nrowpreA > 0)
    {
        $rowpreA = mssql_fetch_array($respreA);
        $sdate=$rowpreA['smo'].'/1/'.$rowpreA['syr'];
        $edate=$rowpreA['emo'].'/30/'.$rowpreA['eyr'];
    }
    else
    {
        if (isset($_REQUEST['cyear']) and (int) $_REQUEST['cyear'] > 2001)
        {
            $sdate='1/1/'.trim($_REQUEST['cyear']);
            $edate='12/31/'.trim($_REQUEST['cyear']);
        }
        else
        {
            $sdate='1/1/'.date("Y");
            $edate='12/31/'.date("Y");
        }
    }

	if ($_REQUEST['call']=="search_results")
	{
		if ($_REQUEST['subq']=="lname" or $_REQUEST['subq']=="njobid")
		{
			if (empty($_REQUEST['d1']) && isset($_REQUEST['d2']) )
			{
				if (empty($_REQUEST['sval']))
				{
					echo "<b><font color=\"red\">Error!</font> Search String required.</b>";
					exit;
				}
			}
			
			if ($_REQUEST['subq']=="lname")
			{
				$qry    = "SELECT ";
				$qry   .= "J1.*, ";
				$qry   .= "J2.*, ";
				$qry   .= "C.* ";
				$qry   .= "FROM [jobs] AS J1 ";
				$qry   .= "INNER JOIN [jdetail] AS J2 ";
				$qry   .= "ON J1.jobid=J2.jobid ";
				$qry   .= "INNER JOIN [cinfo] AS C ";
				$qry   .= "ON J1.jobid=C.jobid ";
				$qry   .= "WHERE J1.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND J2.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND C.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND J2.jadd='0' ";
				$qry   .= "AND J1.njobid!='0' ";
	
				if (isset($_REQUEST['maspos']) && $_REQUEST['maspos']==1)
				{
					$qry   .="AND C.mas_prep >= '1'  ";
				}
	
				$qry   .= "AND C.clname LIKE '".$_REQUEST['sval']."%'  ";
				
				if (isset($_REQUEST['cyear']) && $nrowpreA > 0)
				{
					$qry   .= " AND ".$_REQUEST['cyearfield']." BETWEEN '".$sdate."' AND '".$edate." 23:59:59'  ";
				}
				
				if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
				{
					$qry   .="AND J1.renov = '1'  ";
				}
	
				$qry   .= "ORDER BY J1.renov,".$order." ".$dir.";";
				$res   = mssql_query($qry);
				$nrows = mssql_num_rows($res);
			}
			elseif ($_REQUEST['subq']=="njobid")
			{
				//echo "H2<BR>";
				$qry    = "SELECT ";
				$qry   .= "J1.*, ";
				$qry   .= "J2.*, ";
				$qry   .= "C.* ";
				$qry   .= "FROM [jobs] AS J1 ";
				$qry   .= "INNER JOIN [jdetail] AS J2 ";
				$qry   .= "ON J1.jobid=J2.jobid ";
				$qry   .= "INNER JOIN [cinfo] AS C ";
				$qry   .= "ON J1.jobid=C.jobid ";
				$qry   .= "WHERE J1.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND J2.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND C.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND J2.jadd='0' ";
				$qry   .= "AND J1.njobid!='0' ";
	
				if (isset($_REQUEST['maspos']) && $_REQUEST['maspos']==1)
				{
					$qry   .="AND C.mas_prep >= '1'  ";
				}
	
				$qry   .= "AND (J1.njobid LIKE '".$_REQUEST['sval']."%' OR J1.njobid LIKE '0".$_REQUEST['sval']."%') ";
				
				if (isset($_REQUEST['cyear']) && $nrowpreA > 0)
				{
					$qry   .= " AND ".$_REQUEST['cyearfield']." BETWEEN '".$sdate."' AND '".$edate." 23:59:59'  ";
				}
				
				if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
				{
					$qry   .="AND J1.renov = '1'  ";
				}
	
				$qry   .= "ORDER BY J1.renov,".$order." ".$dir.";";
				$res   = mssql_query($qry);
				$nrows = mssql_num_rows($res);
			}
		}
		elseif ($_REQUEST['subq']=="salesman")
		{
			//echo "H2<BR>";
			$qry    = "SELECT ";
			$qry   .= "J1.*, ";
			$qry   .= "J2.*, ";
			$qry   .= "C.* ";
			$qry   .= "FROM [jobs] AS J1 ";
			$qry   .= "INNER JOIN [jdetail] AS J2 ";
			$qry   .= "ON J1.jobid=J2.jobid ";
			$qry   .= "INNER JOIN [cinfo] AS C ";
			$qry   .= "ON J1.jobid=C.jobid ";
			$qry   .= "WHERE J1.officeid='".$_SESSION['officeid']."' ";
			$qry   .= "AND J2.officeid='".$_SESSION['officeid']."' ";
			$qry   .= "AND C.officeid='".$_SESSION['officeid']."' ";
			$qry   .= "AND J2.jadd='0' ";
			$qry   .= "AND J1.njobid!='0' ";
			$qry   .= "AND J1.securityid='".$_REQUEST['assigned']."' ";
			
			if (isset($_REQUEST['cyear']) && $nrowpreA > 0)
			{
				$qry   .= " AND ".$_REQUEST['cyearfield']." BETWEEN '".$sdate."' AND '".$edate." 23:59:59'  ";
			}
			
			if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
			{
				$qry   .="AND J1.renov = '".$_REQUEST['renov']."'  ";
			}
			
			$qry   .= "ORDER BY J1.renov,".$order." ".$dir.";";
            $res   = mssql_query($qry);
            $nrows = mssql_num_rows($res);
		}
		//$_SESSION['jqry']=$qry;
	}
	
	if ($_SESSION['securityid']==2699999999999999999999)
	{
		//echo $qry."<br>";
		echo '<pre>';
		print_r($_REQUEST);
		echo '</pre>';
	}

	while($row=mssql_fetch_array($res))
	{
		$njblist[]=$row['njobid'];
	}
    
    if ($nrowpreA == 0)
    {
        echo 'NOTICE: Bonus Schedule for '.$_REQUEST['cyear'].' not configured<br>';
    }

	if ($nrows < 1)
	{
		echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
		echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\" class=\"gray\">\n";
		echo "         <h4><b>Job Search did not produce any results</b></h4>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}
	else
	{
		echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
		echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"center\"><b>".$_SESSION['offname']." Jobs</b></td>\n";
		//echo "		<td align=\"right\"><b>Job</b> Status Codes:</td>\n";
		//echo "		<td align=\"center\" width=\"100\"><b>Unsubmitted</b></td>\n";
		//echo "		<td align=\"center\" class=\"magenta\" width=\"100\"><b>Review</b></td>\n";
		//echo "		<td align=\"center\" class=\"lightgreen\" width=\"100\"><b>Processed</b></td>\n";
		//echo "		<td align=\"center\" class=\"yel\" width=\"100\"><b>New Activity</b></td>\n";
		echo "		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		
		echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
		echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "                  <tr class=\"tblhd\">\n";
		echo "                     <td align=\"center\"><b></b></td>\n";
		echo "                     <td align=\"center\"><b>Job #</b></td>\n";
		echo "                     <td align=\"center\"><b>Addn</b></td>\n";
		echo "                     <td align=\"center\"><b>Ren</b></td>\n";
		echo "                     <td align=\"left\"><b>Customer</b></td>\n";
		echo "                     <td align=\"left\"><b>Phone</b></td>\n";
		echo "                     <td align=\"right\"><b>Contr Amt</b></td>\n";

		if ($_SESSION['jlev'] >= 6)
		{
			echo "                     <td align=\"right\"><b>Total Cost</b></td>\n";
			echo "                     <td align=\"right\"><b>Net Prof</b></td>\n";
			echo "                     <td align=\"center\"><b>TGP</b></td>\n";
		}
	
		echo "                     <td align=\"center\"><b></b></td>\n";
		echo "                     <td align=\"left\"><b>SalesRep</b></td>\n";
		echo "                     <td align=\"center\"><b>Contr Date</b></td>\n";
		echo "                     <td align=\"center\"><b>System Date</b></td>\n";
		//echo "                     <td align=\"center\"><b>Status</b></td>\n";
		echo "                     <td align=\"right\" colspan=\"4\"></td>\n";
		echo "                  </tr>\n";

		$tcon	=0;
		$xi 	=0;
		$xj 	=0;

		foreach ($njblist as $n => $v)
		{
			$tjob=0;
			$xi++;
			$jerr=0;
			$post_add=0;
			
			if ($xi%2) {
				$tbg	= "even";
			}
			else {
				$tbg	= "odd";
			}
			
			//$qryA = "SELECT jobid,status,custid,securityid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!='0';";
			$qryA = "SELECT jobid,status,custid,securityid,tgp,jcost,jprof,njobid,renov,acc_status FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$v."' AND jadd='0';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);
			$nrowA = mssql_num_rows($resA);

			//echo $qryA."<br>";

			$qryB = "SELECT cfname,clname,chome,mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$rowA['njobid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			//$tbg	= "wh_und";
			/*
			if (isset($rowpre['accountingsystem']) and ($rowpre['accountingsystem']==2 or $rowpre['accountingsystem']==3))
			{
				//echo $rowA['acc_status'].'<br>';
				if ($rowA['acc_status'] == 9)
				{
					$xtbg	= "ltgray"; // Closed
					$xsta	= "Closed";
				}
				elseif ($rowA['acc_status'] == 8)
				{
					$xtbg	= "red"; // Reserved
					$xsta	= "Reserved";
				}
				elseif ($rowA['acc_status'] == 7)
				{
					$xtbg	= "red"; // Error
					$xsta	= "Hold";
				}
				elseif ($rowA['acc_status'] == 6)
				{
					$xtbg	= "lightgreen"; // Exists
					$xsta	= "Exists";
				}
				elseif ($rowA['acc_status'] == 5)
				{
					$xtbg	= "lightgreen"; // Processed
					$xsta	= "Processed";
				}
				elseif ($rowA['acc_status'] == 4)
				{
					$xtbg	= "lightblue"; // Transmitted
					$xsta	= "Transmitted";
				}
				elseif ($rowA['acc_status'] == 3)
				{
					$xtbg	= ""; // Reserved
					$xsta	= "Reserved";
				}
				elseif ($rowA['acc_status'] == 2)
				{
					$xtbg	= "magenta"; // Flagged reReview
					$xsta	= "reReview";
				}
				elseif ($rowA['acc_status'] == 1)
				{
					$xtbg	= "magenta"; // Flagged Review
					$xsta	= "Review";
				}
				elseif ($rowA['acc_status'] == 0)
				{
					$xtbg	= "ltgray"; // Unsubmitted
					$xsta	= "Unsubmitted";
				}
				else
				{
					$xtbg	= ""; // Unknown
					$xsta	= "Unknown";
				}
			}
			else
			{
				$xtbg	= "magenta"; // Review Flagged
				$xsta	= "Review";
				$mstat=	0;
				
				if ($rowB['mas_prep'] == 0)
				{
					$xtbg	= "gray"; // MAS not ready
					$xsta	= "Unsubmitted";
					$mstat=	0;
				}
				elseif ($rowB['mas_prep'] == 1)
				{
					$xtbg	= "magenta"; // MAS Ready
					$xsta	= "Review";
				}
				else
				{
					//echo 'reHit<br>';
					if (array_key_exists($rowA['njobid'],$mjar))
					{
						//echo "AR<br>";
						$mstat=$mjar[$rowA['njobid']];
						if ($mstat == 9)
						{
							$xtbg	= "ltgray"; // Closed
							$xsta	= "Closed";
						}
						elseif ($mstat == 8)
						{
							$xtbg	= "red"; // Reserved
							$xsta	= "Reserved";
						}
						elseif ($mstat == 7)
						{
							$xtbg	= "red"; // Rejected (Processor Hold)
							$xsta	= "Hold";
						}
						elseif ($mstat == 6)
						{
							$xtbg	= "lightgreen"; // Accepted (Exists)
							$xsta	= "Exists";
						}
						elseif ($mstat == 5)
						{
							$xtbg	= "lightgreen"; // Accepted (Processed)
							$xsta	= "Processed";
						}
						elseif ($mstat == 4)
						{
							$xtbg	= "yellow"; // Reserved
							$xsta	= "Reserved";
						}
						elseif ($mstat == 3)
						{
							$xtbg	= "yellow"; // Error (Incomplete)
							$xsta	= "Incomplete";
						}
						elseif ($mstat == 2)
						{
							$xtbg	= "blue"; // Transmit Sent
							$xsta	= "Sent";
						}
						elseif ($mstat == 1)
						{
							$xtbg	= "blue"; // Transmit Flagged
							$xsta	= "Flagged";
						}
						else
						{
							//$xtbg	= "wh_und";
							//$xsta	= "";
							$xtbg	= "magenta"; // Review Flagged
							$xsta	= "Review";
						}
					}
				}
			}
			*/
			$qryC = "SELECT fname,lname,slevel,mas_div,rmas_div FROM security WHERE securityid='".$rowA['securityid']."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			//echo $qryC."<br>";

			$secl=explode(",",$rowC['slevel']);

			if ($secl[6]==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}

			//$qryD = "SELECT jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!='0';";

			$qryDpre = "SELECT contractamt,added,updated,contractdate FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$v."' AND jadd='0';";
			$resDpre = mssql_query($qryDpre);
			$rowDpre = mssql_fetch_array($resDpre);

			$qryD = "SELECT njobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$v."' AND jadd!='0';";
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_array($resD);
			$nrowD = mssql_num_rows($resD);

			$ctramt=$rowDpre['contractamt'];

			if ($nrowD >= 1)
			{
				//$qryDa = "SELECT raddnpr_man FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!='0';";
				$qryDa = "SELECT raddnpr_man,post_add,pmasreq FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$rowD['njobid']."' AND jadd!='0';";
				$resDa = mssql_query($qryDa);

				$jaddamt=0;
				while ($rowDa = mssql_fetch_array($resDa))
				{
					$jaddamt=$jaddamt+$rowDa['raddnpr_man'];

					if ($rowDa['pmasreq']==1)
					{
						$post_add++;
						//$xtbg	= "lightgreen";
						///$xsta	= "Post MAS P";
						//echo $xtbg."<br>";
					}
					elseif ($rowDa['post_add']==1)
					{
						$post_add++;
						//$xtbg	= "yellow";
						//$xsta	= "Post MAS";
					}
				}
			}
			else
			{
				$jaddamt=0;
			}

			$tctramt=$ctramt+$jaddamt;
			$ftctramt=number_format($tctramt, 2, '.', ',');

			//$qryE = "SELECT njobid,jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!='0';";
			$qryE = "SELECT njobid,jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$v."' AND jadd!='0';";
			$resE = mssql_query($qryE);
			$nrowE = mssql_num_rows($resE);

			$pstadd=0;
			while ($rowE = mssql_fetch_array($resE))
			{
				if ($rowE['jobid']=="0")
				{
					$pstadd++;
				}
			}

			$qryF = "SELECT MAX(jadd) as mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$v."';";
			$resF = mssql_query($qryF);
			$rowF = mssql_fetch_array($resF);

			$uid  =md5(session_id().time().$row['custid']).".".$_SESSION['securityid'];

			if ($rowA['renov']==1 && $rowC['rmas_div']!=0)
			{
				$dnjobid=disp_mas_div_jobid($rowC['rmas_div'],$v);
			}
			else
			{
				$dnjobid=disp_mas_div_jobid($rowC['mas_div'],$v);
			}

			if ($dnjobid[1] > 0)
			{
				$jerr++;
			}

			if (in_array($rowA['securityid'],$acclist)||$_SESSION['jlev'] >= 6)
			{
				if ($jerr==0||$_REQUEST['incerrs']==1)
				{
					if (isset($rowDpre['added']))
					{
						$odate = date("m/d/Y", strtotime($rowDpre['added']));
					}
					else
					{
						$odate = "";
					}

					if (isset($rowDpre['updated']))
					{
						$udate = date("m/d/Y", strtotime($rowDpre['updated']));
					}
					else
					{
						$udate = "";
					}

					if (isset($rowDpre['contractdate']))
					{
						$cdate = date("m/d/Y", strtotime($rowDpre['contractdate']));
					}
					else
					{
						$cdate = "";
					}

					$tcon=$tcon+$tctramt;
					//$dnjobid=disp_mas_div_jobid($rowC['mas_div'],$row['njobid']);
					echo "                  <tr class=\"".$tbg."\">\n";
					echo "                     <td align=\"right\">".$xi.".</td>\n";

					if ($dnjobid[1] > 0)
					{
						echo "                     <td align=\"right\">(".$v.") ".$dnjobid[0]."</td>\n";
					}
					else
					{
						echo "                     <td align=\"right\">".$dnjobid[0]."</td>\n";
					}

					echo "                     <td align=\"center\">\n";

					if ($nrowE >= 1)
					{
						echo "<b>".$nrowE."</b>";
					}

					echo "							</td>\n";
					echo "                     <td align=\"center\">\n";

					if ($rowA['renov'] == 1)
					{
						echo "<b>R</b>";
					}

					echo "							</td>\n";
					echo "                     <td align=\"left\"><b>".str_replace('\\','',$rowB['clname'])."</b>, ".$rowB['cfname']."</td>\n";
					echo "                     <td align=\"left\">".format_phonenumber($rowB['chome'])."</td>\n";
					echo "                     <td align=\"right\">".$ftctramt."</td>\n";

					if ($_SESSION['jlev'] >= 6)
					{
						$jcost	=number_format($rowA['jcost'], 2, '.', ',');
						$jprof	=number_format($rowA['jprof'], 2, '.', ',');
						$tgp	=round($rowA['tgp'], 2)*100;
						if ($rowpre['tgp'] != 0)
						{
							$vagp=round($rowpre['vgp'], 2)*100;
							$oagp=round($rowpre['tgp'], 2)*100;
							if ($tgp > $oagp+$vagp || $tgp < $oagp-$vagp)
							{
								$ftgp		="<font color=\"red\"><b>".$tgp."%</b></font>";
							}
							else
							{
								$ftgp		=$tgp."%";
							}
						}
						else
						{
							$ftgp		=$tgp."%";
						}

						echo "                     <td align=\"right\">".$jcost."</td>\n";
						echo "                     <td align=\"right\">".$jprof."</td>\n";
						echo "                     <td align=\"right\">".$ftgp."</td>\n";
					}

					echo "                     <td align=\"right\"></td>\n";
					echo "                     <td align=\"left\"><font class=\"".$fstyle."\">".$rowC[1].", ".$rowC[0]."</font></td>\n";
					echo "                     <td align=\"center\">".$cdate."</td>\n";
					echo "                     <td align=\"center\">".$odate."</td>\n";
					//echo "                     <td class=\"".$xtbg."\" align=\"center\"><b>".$xsta."</b></td>\n";
					echo "                     <td align=\"center\">\n";
					echo "                        <form method=\"POST\">\n";
					echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
					echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
					echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$v."\">\n";
					echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
					echo "				             <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
					echo "                        </form>\n";
					echo "                     </td>\n";
					echo "                  </tr>\n";
				}
				$jerr=0;
			}
		}

		$ftcon        =number_format($tcon, 2, '.', ',');
		echo "                  <tr>\n";
		echo "                     <td class=\"gray_und\" align=\"right\" colspan=\"6\"><b>Total Jobs</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"right\"><b>".$ftcon."</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"right\" colspan=\"12\"></td>\n";
		echo "                  </tr>\n";
		echo "                  </table>\n";
		echo "</div>\n";
		echo "<br>";
		//echo $_SESSION['mqry'];
	}
}

function view_job_addendum_retail()
{
	//echo "Addendum";
	//error_reporting(E_ALL);
	global $viewarray,$bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets,$addproc;

	$njobid		=$_REQUEST['njobid'];
	$jaddn		=$_REQUEST['jadd'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname     	=$_SESSION['lname'];

	if (!isset($njobid)||$njobid=='')
	{
		echo "Fatal Error: Job ID (".$njobid.") not set!";
		exit;
	}

	if ($jaddn >= 1)
	{
		$ojaddn=$jaddn-1;
	}
	else
	{
		$ojaddn=0;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='".$jaddn."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreAa = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='".$ojaddn."';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreAb = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='0';";
	$respreAb = mssql_query($qrypreAb);
	$rowpreAb = mssql_fetch_array($respreAb);

	$qrypreB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$acclist	= explode(",",$_SESSION['aid']);

	if (!in_array($rowpreB['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Job</b>";
		exit;
	}

	$viewarray=array(
	'ps1'=>		$rowpreA['pft'],
	'ps2'=>		$rowpreA['sqft'],
	'spa1'=>	$rowpreA['spa_pft'],
	'spa2'=>	$rowpreA['spa_sqft'],
	'spa3'=>	$rowpreA['spa_type'],
	'tzone'=>	$rowpreA['tzone'],
	'camt'=>	$rowpreA['contractamt'],
	'cdate'=>	$rowpreAb['contractdate'],
	'status'=>	$rowpreB['status'],
	'ps5'=>		$rowpreA['shal'],
	'ps6'=>		$rowpreA['mid'],
	'ps7'=>		$rowpreA['deep'],
	'estsecid'=>$rowpreB['securityid'],
	'deck'=>	$rowpreA['deck'],
	'erun'=>	$rowpreA['erun'],
	'prun'=>	$rowpreA['prun'],
	'njobid'=>	$rowpreB['njobid'],
	'comadj'=>	$rowpreA['ouadj'],
	'sidm'=>	$rowpreB['sidm'],
	'applyou'=>	1,
	'refto'=>	$rowpreA['refto'],
	'ps1a'=>	$rowpreA['apft'],
	'bpprice'=>	$rowpreA['bpprice'],
	'bpcomm'=>	$rowpreA['bpcomm'],
	'jadd'=>	$rowpreA['jadd'],
	'added'=>	$rowpreA['added'],
	'renov'=>	$rowpreB['renov'],
	'oldbid'=>	$rowpreA['oldbidflg'],
	'add_type'=>$rowpreA['add_type'],
	'pmasreq'=>	$rowpreA['pmasreq'],
	'jobid'=>	$rowpreB['jobid']
	);

	$r_estdata = $rowpreA['estdata'];

	$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname,mas_div,rmas_div FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);
	
	$qryDs = "SELECT securityid,fname,lname,JobCommEdit,modcomm FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resDs = mssql_query($qryDs);
	$rowDs = mssql_fetch_array($resDs);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,mas_prep,cid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$viewarray['cid']	= $rowI[11];

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_array($resL);

	$qryN = "SELECT securityid,fname,lname FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1';";
	$resN = mssql_query($qryN);

	$qryP = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1';";
	$resP = mssql_query($qryP);

	$qryO = "SELECT MAX(jadd) as mjadd FROM jdetail WHERE  officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."';";
	$resO = mssql_query($qryO);
	$rowO = mssql_fetch_array($resO);
	
	$qryOa = "SELECT COUNT(id) as eqjadd FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' and jadd='".$viewarray['jadd']."';";
	$resOa = mssql_query($qryOa);
	$rowOa = mssql_fetch_array($resOa);
	
	$qryOb = "SELECT COUNT(id) as eqjadd FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' and jadd='".$viewarray['jadd']."';";
	$resOb = mssql_query($qryOb);
	$rowOb = mssql_fetch_array($resOb);
	
	//echo $qryOa."<br>";

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}

	if ($rowC[8]=="p")
	{
		$defmeas		=$viewarray['ps1'];
		$defmeasa	=$rowpreAa['pft'];
	}
	else
	{
		$defmeas		=$viewarray['ps2'];
		$defmeasa	=$rowpreAa['sqft'];
	}

	if ($rowpreA['raddnroy_man']=="1")
	{
		$ck="CHECKED";
	}
	else
	{
		$ck="";
	}

	if ($rowI[10]==1)
	{
		$tbg		="magenta";
	}
	else
	{
		$tbg		="gray";
	}

	if ($rowpreA['post_add']==1)
	{
		$tbg	= "yellow";
	}
	
	if ($rowpreA['pmasreq']==1)
	{
		$tbg	= "lightgreen";
	}

	$sdate		=date("m-d-Y", strtotime($rowpreA['added']));
	$cdate 		=date("m-d-Y", strtotime($viewarray['cdate']));
	$poolcomm_adj=detect_package($r_estdata);
	$set_deck   =deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    =round($set_deck[0]);
	$set_ia     =calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals   =calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$tbullets   =0;
	$estidret   =$njobid;
	
	if ($viewarray['renov']==1 && $rowD['rmas_div']!=0)
	{
		$destidret  =disp_mas_div_jobid($rowD['rmas_div'],$njobid);
	}
	else
	{
		$destidret  =disp_mas_div_jobid($rowD['mas_div'],$njobid);
	}
	//$destidret  =disp_mas_div_jobid($rowD['mas_div'],$njobid);
	$vdiscnt    =$viewarray['camt'];
	$pbaseprice =0;
	$ctramt     =$viewarray['camt'];
	$fctramt    =number_format($ctramt, 2, '.', '');
	$brdr			=0;
	$addproc		=1;
	
	//display_array($viewarray);

	echo "<script type=\"text/javascript\" src=\"js/jquery_job_func.js\"></script>\n";
	echo "<table class=\"transnb\" width=\"950px\">\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "			<table width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" colspan=\"3\">\n";

	info_display_addn($tbg,$rowC[1],$destidret[0],$viewarray['jadd'],$rowD['fname'],$rowD['lname'],$rowL['fname'],$rowL['lname'],"Retail","Job",$viewarray['estsecid'],$njobid);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	cinfo_display_job($rowpreB['officeid'],$viewarray['cid'],$rowC[2]);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"33%\">\n";

	pool_detail_display_job($viewarray['njobid'],$viewarray['jadd']);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	dates_display_addn($viewarray['cid'],$viewarray['jadd']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"bottom\" align=\"left\"></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\">\n";
	echo "         <table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "              	<td class=\"gray\" align=\"left\">\n";
	echo "         			<table width=\"100%\">\n";
	echo "           				<tr>\n";
	echo "              				<td class=\"gray\" align=\"left\"><b>Addendum Comments:</b></td>\n";
	echo "           				</tr>\n";
	echo "           				<tr>\n";
	echo "              				<td class=\"gray\" align=\"left\">".$rowpreA['comments']."</td>\n";
	echo "           				</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" width=\"100%\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"left\" width=\"100\"><b>Category</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Item</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b>Quan.</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b>Units</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Retail</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Comm</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" valign=\"bottom\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";

	if ($defmeas!=$defmeasa||$viewarray['bpprice']!=$rowpreAa['bpprice']||$viewarray['bpcomm']!=$rowpreAa['bpcomm'])
	{
		//echo "DIFF<br>";
		$bquan		=$defmeas-$defmeasa;
		//echo "p1: ".$viewarray['bpprice']."<br>";
		//echo "p2: ".$rowpreAa['bpprice']."<br>";

		if ($viewarray['bpprice']!=$rowpreAa['bpprice']||$viewarray['bpcomm']!=$rowpreAa['bpcomm'])
		{
			$pbaseprice	=$viewarray['bpprice']-$rowpreAa['bpprice'];
			$bcomm		=$viewarray['bpcomm']-$rowpreAa['bpcomm'];
		}
		else
		{
			$pbaseprice	=0;
			$bcomm		=0;
		}
		$fpbaseprice=number_format($pbaseprice, 2, '.', '');
		$fbcomm     =number_format($bcomm, 2, '.', '');

		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\" width=\"100\">Base</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\"><b>Basic Pool Perimeter Change</b></td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">".$bquan."</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">pft</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fpbaseprice."</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fbcomm."</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"center\" width=\"60\"></td>\n";
		echo "           </tr>\n";
	}
	else
	{
		$bcomm		=0;
	}

	//echo "EST: ".$rowpreA['estdata']."<br>FIL: ".$rowpreA['filters']."<br>";
	calcbyacc_add($rowpreA['raddnacc'],0);

	// Totals Table Calcs
	$bccost  	=$bctotal;
	$rccost  	=$rctotal;
	$cccost  	=$cctotal;
	$bmcost  	=$bmtotal;
	$rmcost  	=$rmtotal;
	$trccost 	=$rccost+$rmcost;
	$cmcost  	=$cmtotal;
	$tbcost  	=$bccost+$bmcost;
	$trcost  	=$pbaseprice+$trccost+$tbid;
	$tccost  	=$cccost+$cmcost;
	$trcomm  	=$bcomm+$tccost;
	//$trcomm  	=$tccost;
	$ftrcost    =number_format($trcost, 2, '.', '');
	$ftccost    =number_format($tccost, 2, '.', '');
	$ftrcomm    =number_format($trcomm, 2, '.', '');
	$ftrprman	=number_format($rowpreA['raddnpr_man'], 2, '.', '');
	$ftrcmman	=number_format($rowpreA['raddncm_man'], 2, '.', '');
	$fpschadj	=number_format($rowpreA['psched_adj'], 2, '.', '');
	
	$qryDa = "SELECT C.*,(select lname from security where securityid=C.secid) as lname,(select fname from security where securityid=C.secid) as fname FROM CommissionHistory AS C WHERE C.jobid='".$viewarray['jobid']."' AND C.jadd=".$viewarray['jadd'].";";
	$resDa = mssql_query($qryDa);
	$rowDa = mssql_fetch_array($resDa);
	$nrowDa= mssql_num_rows($resDa);
	
	$qryDx = "SELECT cmid FROM CommissionBuilder WHERE oid=".$_SESSION['officeid']." AND active=1;";
	$resDx = mssql_query($qryDx);
	$nrowDx= mssql_num_rows($resDx);

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Price per Book</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftrcost."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$ftrcomm."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"7\" class=\"wh\" align=\"left\"><b>Totals</b></td>\n";
	echo "           </tr>\n";
	
	if ((isset($rowDs['JobCommEdit']) and $rowDs['JobCommEdit']==1) or (isset($rowDs['modcomm']) and $rowDs['modcomm']==1))
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">\n";
		
		if (isset($viewarray['add_type']) and $viewarray['add_type'] == 1)
		{
			echo '<b>GM Adjust Total</b>';
		}
		else
		{
			echo '<b>Customer Addendum & Pay Schedule Total</b>';
		}
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";
		echo "					<form method=\"post\">\n";
		echo "					<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "					<input type=\"hidden\" name=\"call\" value=\"edit_add_price\">\n";
		echo "					<input type=\"hidden\" name=\"jobid\" value=\"".$rowpreA['jobid']."\">\n";
		echo "					<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
		echo "					<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
		echo "					<input type=\"hidden\" name=\"cid\" value=\"".$viewarray['cid']."\">\n";
		echo "					<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "					<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "					<input type=\"hidden\" name=\"royadj\" value=\"0\">\n";
		echo "					<input type=\"hidden\" name=\"add_type\" value=\"".$viewarray['add_type']."\">\n";
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">\n";
		
		if (isset($viewarray['add_type']) and $viewarray['add_type'] == 1)
		{
			echo "				<input class=\"bbox formatCurrency JMStooltip\" type=\"text\" name=\"prmanadj\" size=\"8\" maxlength=\"10\" value=\"".$ftrprman."\" title=\"Adjusts Retail Amount (Does not affect Payment Schedule)\">\n";
			echo "              <input type=\"hidden\" name=\"pschadj\" value=\"".$fpschadj."\">\n";
		}
		else
		{
			echo "              <input class=\"bbox formatCurrency JMStooltip\" type=\"text\" name=\"pschadj\" size=\"8\" maxlength=\"10\" value=\"".$fpschadj."\" title=\"Adjusts Payment Schedule\">\n";
			echo "              <input type=\"hidden\" name=\"prmanadj\" value=\"".$ftrprman."\">\n";
		}
		
		echo "              </td>\n";
		echo "				<td NOWRAP class=\"wh\" align=\"right\" width=\"60\">\n";
	
		if ($rowpreA['jobid']=='0')
		{
			echo "              <input class=\"bbox formatCurrency\" type=\"text\" name=\"cmmanadj\" size=\"8\" maxlength=\"10\" value=\"".$ftrcmman."\">\n";
		}
		else
		{
			echo "              <input class=\"bbox formatCurrency\" type=\"text\" name=\"cmmanadj\" size=\"8\" maxlength=\"10\" value=\"".$ftrcmman."\">\n";
		}
	
		echo "           	   </td>\n";
		echo "           	   <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
		echo "           	</tr>\n";
		echo "				<tr>\n";
		echo "              	<td colspan=\"2\" class=\"wh\" align=\"right\"><b>Commission Recipient</b></td>\n";
		echo "              	<td colspan=\"4\" class=\"wh\" align=\"right\">\n";
		
		if ($rowpreA['pmasreq']==1 or $rowO['mjadd']!=$viewarray['jadd'])
		{
			if ($nrowDa!=0 and $rowDa['secid']!=0)
			{
				echo $rowDa['fname']. " " . $rowDa['lname'];
			}
		}
		else
		{
			$qryDz = "SELECT securityid,fname,lname,substring(slevel,13,1) as slev FROM security WHERE officeid=".$_SESSION['officeid']." and substring(slevel,13,1) >=1 order by lname asc;";
			$resDz = mssql_query($qryDz);
			$nrowDz= mssql_num_rows($resDz);
			
			echo "					<select name=\"addsecid\">\n";
			echo "						<option value=\"0\"></option>\n";
		
			while ($rowDz = mssql_fetch_array($resDz))
			{
				if ($rowDz['securityid']==$rowDa['secid'])
				{
					echo "<option value=\"".$rowDz['securityid']."\" SELECTED>".ucwords($rowDz['lname']).", ".ucwords($rowDz['fname'])."</option>\n";
				}
				else
				{
					echo "<option value=\"".$rowDz['securityid']."\">".ucwords($rowDz['lname']).", ".ucwords($rowDz['fname'])."</option>\n";
				}
			}
			
			echo "					</select>\n";
		}
		
		echo "					</td>\n";
		echo "              	<td NOWRAP class=\"wh\" align=\"center\" width=\"60\">\n";
	
		if ($rowpreA['pmasreq']==1 or $rowO['mjadd']!=$viewarray['jadd'])
		{
			echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Apply Adjust\" DISABLED>\n";
		}
		else
		{
			echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Apply Adjust\">\n";
		}
	
		echo "						</form>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
	}
	else
	{
		echo "			<input type=\"hidden\" name=\"addsecid\" value=\"0\">\n";
		echo "			<tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">\n";
		
		if ($viewarray['add_type']==1)
		{
			echo '<b>GM Adjust Total</b>';
		}
		else
		{
			echo '<b>Customer Addendum Total</b>';
		}
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">\n";
		echo $ftrprman;
		echo "              </td>\n";
		echo "				<td NOWRAP class=\"wh\" align=\"right\" width=\"60\">\n";
		echo $ftrcmman;	
		echo "              </td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
		
		if (isset($viewarray['add_type']) and $viewarray['add_type'] != 1)
		{
			echo "           <tr>\n";
			echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Payment Schedule Amount</b></td>\n";
			echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
			echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
			echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fpschadj."</td>\n";
			echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\"></td>\n";
			echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
			echo "           </tr>\n";
		}
		
		echo "				<tr>\n";
		echo "              	<td colspan=\"2\" class=\"wh\" align=\"right\"><b>Commission Recipient</b></td>\n";
		echo "              	<td colspan=\"4\" class=\"wh\" align=\"right\">\n";
		
		if ($nrowDa!=0 and $rowDa['secid']!=0)
		{
			echo $rowDa['fname']. " " . $rowDa['lname'];
		}

		echo "					</td>\n";
		echo "              	<td NOWRAP class=\"wh\" align=\"center\" width=\"60\">\n";	
		echo "					</td>\n";
		echo "				</tr>\n";
	}
	
	echo "			</table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";

	if  ($viewarray['jadd'] >= 1)
	{
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "               	<form method=\"POST\">\n";
		echo "                  <input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "                  <input type=\"hidden\" name=\"call\" value=\"delete_job1\">\n";
        echo "                  <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "                  <input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
		echo "                  <input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	
		if ($rowpreA['pmasreq']==1)
		{
			echo "                           <input class=\"buttondkredpnl80\" type=\"submit\" value=\"Delete Addn\" title=\"This Addn has been Processed. It cannot be removed.\" DISABLED>\n";
		}
		elseif ($rowO['mjadd']!=$viewarray['jadd'])
		{
			echo "                           <input class=\"buttondkredpnl80\" type=\"submit\" value=\"Delete Addn\" title=\"This Job has other Addn's applied. Addn's must be removed starting with the last Addn applied\" DISABLED>\n";
		}
		elseif ($rowOa['eqjadd'] > 0)
		{
			echo "                           <input class=\"buttondkredpnl80\" type=\"submit\" value=\"Delete Addn\" title=\"This Addn has Bid Cost applied. Please remove any Bid Cost Items on the Cost Breakdown before attempting to delete the Addn.\" DISABLED>\n";
		}
		elseif ($rowOb['eqjadd'] > 0)
		{
			echo "                           <input class=\"buttondkredpnl80\" type=\"submit\" value=\"Delete Addn\" title=\"This Addn has Manual Phase Adjusts applied. Please remove any Manual Phase Adjusts on the Cost Breakdown before attempting to delete the Addn.\" DISABLED>\n";
		}
		else
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete Addn\">\n";
		}

		echo "					</form>\n";
		echo "               <td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"center\">\n";
		echo "					<div class=\"noPrint\">\n";
		echo "						<hr width=\"90%\">\n";
		echo "					</div>\n";
		echo "				</td>\n";
		echo "            </tr>\n";

	}

	echo "            <tr>\n";
	echo "               <td align=\"left\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "					<form method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "					<input type=\"hidden\" name=\"njobid\" value=\"".$estidret."\">\n";
	echo "					<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
	echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Retail\">\n";
	echo "					</form>\n";
	echo "					</div>\n";
	echo "               </td>\n";
	echo "            <tr>\n";
	echo "               <td align=\"center\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "						<hr width=\"90%\">\n";
	echo "					</div>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            </tr>\n";

	if ($_SESSION['jlev'] >= 8)
	{
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		
		echo "<div class=\"noPrint\">\n";
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"set_postmas_proc\">\n";
		echo "<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		
		if ($viewarray['jadd']==0 && $rowOa['eqjadd'] == 0)
		{
			echo "                  <input class=\"buttondkredpnl80\" type=\"submit\" value=\"Process\" title=\"This Addn has Bids without Bid Cost. Add Bid Cost to Process\" DISABLED>\n";
		}
		elseif ($rowpreA['pmasreq']==1)
		{
			echo "                  <input class=\"buttondkredpnl80\" type=\"submit\" value=\"Process\" title=\"This Addn has been Processed\" DISABLED>\n";
		}
		else
		{
			echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Process\">\n";
		}

		echo "</form>\n";
		echo "</div>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
	}

	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function view_job_retail()
{
	//echo "TEST";
	error_reporting(E_ALL);
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets,$addproc;

	unset($_SESSION['viewarray']);

	$njobid		=$_REQUEST['njobid'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	
	$dbg=0;
	
	if (!isset($njobid)||$njobid=='')
	{
		echo "Fatal Error: Job ID (".$njobid.") not set!";
		exit;
	}

	if ($_REQUEST['call']=="view_retail"||$_REQUEST['call']=="post_save_add")
	{
		$jaddn	=$_REQUEST['jadd'];
	}
	elseif ($_REQUEST['call']=="delete_job2")
	{
		$jaddn	=0;
	}
	else
	{
		$jaddn	=0;
	}

	$masjinfo=getmasjobinfo($njobid);
	
	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='".$jaddn."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreAa = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='0';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qrypreBa = "SELECT njobid,jobid,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd!='0';";
	$respreBa = mssql_query($qrypreBa);
	$nrowpreBa = mssql_num_rows($respreBa);

	$pstadd=0;
	while ($rowpreBa = mssql_fetch_row($respreBa))
	{
		if ($rowpreBa[1]=="0")
		{
			$pstadd++;
		}
	}
	
	$qrypreBb = "SELECT MAX(jadd) as mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."';";
	$respreBb = mssql_query($qrypreBb);
	$rowpreBb = mssql_fetch_array($respreBb);
	
	//echo $qrypreBb;
	//echo $rowpreBb['mjadd']."<br>";
	
	$qrypreBc = "SELECT njobid,jobid,post_add,pmasreq FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='".$rowpreBb['mjadd']."';";
	$respreBc = mssql_query($qrypreBc);
	$rowpreBc = mssql_fetch_array($respreBc);
	
	$qrypreBd = "SELECT estid,added FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$rowpreB ['estid']."';";
	$respreBd = mssql_query($qrypreBd);
	$rowpreBd = mssql_fetch_array($respreBd);
	
	$qrypreC = "SELECT securityid,filestoreaccess,acctngrelease FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_array($respreC);

	$_SESSION['aid']=aidbuilder($_SESSION['jlev'],"j");
	$acclist	= explode(",",$_SESSION['aid']);
	$uid		= md5(session_id().time().$rowpreB['custid']).".".$_SESSION['securityid'];

	if (!in_array($rowpreB['securityid'],$acclist))
	{
		echo "<br><font color=\"red\">Access Error</font><br>You do not have appropriate Access Rights to view this Job";
		exit;
	}

	//echo $njobid;
	$viewarray=array(
	'ps1'=>		$rowpreA['pft'],
	'ps2'=>		$rowpreA['sqft'],
	'spa1'=>	$rowpreA['spa_pft'],
	'spa2'=>	$rowpreA['spa_sqft'],
	'spa3'=>	$rowpreA['spa_type'],
	'tzone'=>	$rowpreA['tzone'],
	'camt'=>	$rowpreA['contractamt'],
	'cdate'=>	$rowpreAa['contractdate'],
	'status'=>	$rowpreB['status'],
	'ps5'=>		$rowpreA['shal'],
	'ps6'=>		$rowpreA['mid'],
	'ps7'=>		$rowpreA['deep'],
	'estsecid'=>$rowpreB['securityid'],
	'custid'=>	$rowpreB['custid'],
	'deck'=>	$rowpreA['deck'],
	'erun'=>	$rowpreA['erun'],
	'prun'=>	$rowpreA['prun'],
	'njobid'=>	$rowpreB['njobid'],
	'sidm'=>	$rowpreB['sidm'],
	'tax'=>		$rowpreB['tax'],
	'taxrate'=>	$rowpreB['taxrate'],
	'applyov'=>	$rowpreB['applyov'],
	'comadj'=>	$rowpreB['ovcommission'],
	'refto'=>	$rowpreA['refto'],
	'ps1a'=>	$rowpreA['apft'],
	'bpprice'=>	$rowpreA['bpprice'],
	'bpcomm'=>	$rowpreA['bpcomm'],
	'addnpr'=>	$rowpreA['raddnpr_man'],
	'addncm'=>	$rowpreA['raddncm_man'],
	'royadj'=>	$rowpreA['raddnroy_man'],
	'added'=>	strtotime($rowpreBd['added']),
	'cdate'=>	strtotime($rowpreBd['added']),
	'digdate'=>	strtotime($rowpreB['digdate']),
	'estid'=>	$rowpreB['estid'],
	'jid'=>		$rowpreB['jid'],
	'njobid'=>	$rowpreB['njobid'],
	'jobid'=>	$rowpreB['jobid'],
	'jadd'=>	$rowpreA['jadd'],
	'mjadd'=>	$rowpreBb['mjadd'],
	'renov'=>	$rowpreB['renov'],
	'oldbid'=>	$rowpreA['oldbidflg'],
	'allowdel'=>0,
	'acc_status'=>$rowpreB['acc_status']
	);

	if ($masjinfo[1] >= 1)
	{
		if ($viewarray['mjadd'] > $viewarray['jadd'] && $rowpreBc['pmasreq']==0)
		{
			$viewarray['allowdel']	= 0;
		}
		else
		{
			$viewarray['allowdel']	= $masjinfo[1];
		}
	}
	else
	{
		$viewarray['allowdel']	= 0;
	}

	//print_r($viewarray);

	$r_estdata = $rowpreA['estdata'];

	$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft,encost,enjob,newcommdate,accountingsystem,enmas,enquickbooks FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[10]!=1)
	{
		echo "<br><font color=\"red\">ERROR!</font><br>Jobs have been disabled in ".$rowC[1]."";
		exit;
	}

	if ($rowC[8]=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	if ($viewarray['renov']==1)
	{
		$rbtable="rbpricep_renov";
	}
	else
	{
		$rbtable="rbpricep";
	}
	
	$qryB = "SELECT id,quan,price,comm FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryD = "SELECT securityid,fname,lname,mas_div,rmas_div,newcommdate FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	$viewarray['cid']		= $rowF['cid'];
	$viewarray['ncommdate']	=strtotime($rowD['newcommdate']);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$viewarray['cid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);
	
	$viewarray['mas_prep']=$rowI[10];

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_array($resL);

	$mrttext	='';
	$masstat	='';
	
	/*
	//echo $rowI[10]."<br>";
	if ($rowC[12] <= 1) {
		if ($viewarray['mas_prep'] >= 2)
		{
			$masstat	='DISABLED';
			$mrttext	='Job Processed';
		}
		elseif ($viewarray['mas_prep'] == 1)
		{
			$masstat	='DISABLED';
			$mrttext	='Job Under Review';
		}
		else
		{
			if ($rowpreB['jcost']=="0.00")
			{
				$masstat	='DISABLED';
				$mrttext	='Must View Job Cost';
			}
			else
			{
				$masstat='';
			}
		}
	}
	else
	{
		$masstat='';
	}
	*/

	if ($viewarray['jadd'] >= 1)
	{
		$tjaddpr	=0;
		$qryO 	= "SELECT jadd,raddnpr_man FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd!='0';";
		$resO 	= mssql_query($qryO);

		while ($rowO = mssql_fetch_array($resO))
		{
			if ($rowO['jadd'] <= $viewarray['jadd'])
			{
				$tjaddpr=$tjaddpr+$rowO['raddnpr_man'];
			}
		}
	}
	else
	{
		$tjaddpr	=0;
	}

	if ($rowC[2]==1)
	{
		if (!empty($viewarray['taxrate']) && $viewarray['taxrate']!="0.00")
		{
			$taxrate=array(0=>$viewarray['tax'],1=>$viewarray['taxrate']);
			//echo "from Static<br>";
		}
		else
		{
			$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
			$resJ = mssql_query($qryJ);
			$rowJ = mssql_fetch_row($resJ);

			//$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
			$taxrate=array(0=>$viewarray['camt']*$rowJ[0],1=>$rowJ[0]);
			//echo "from Dynamic<br>";
		}

		//print_r($taxrate);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}

	$qryQ  = "select csid,rate,cbtype from jest..CommissionSchedule where jobid='".$viewarray['jobid']."' and cbtype=1;";
	$resQ = mssql_query($qryQ);
	$rowQ = mssql_fetch_array($resQ);
	$nrowQ= mssql_num_rows($resQ);
	
	if ($nrowQ > 0)
	{
		$viewarray['com_base_rate']=($rowQ['rate'] * .01);
	}

	$sdate	=date("m/d/Y", strtotime($rowpreAa['added']));
	$cdate 	=date("m/d/Y", strtotime($viewarray['cdate']));
	$poolcomm_adj=detect_package($r_estdata);
	$set_deck   =deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    =round($set_deck[0]);
	$set_ia     =calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals   =calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$tbullets   =0;
	$estidret   =$njobid;
	
	if ($viewarray['renov']==1 && $rowD['rmas_div']!=0)
	{
		$destidret  =disp_mas_div_jobid($rowD['rmas_div'],$njobid);
	}
	else
	{
		$destidret  =disp_mas_div_jobid($rowD['mas_div'],$njobid);
	}
	
	$viewarray['masjobid']=$destidret[0];
	
	$vdiscnt    =$viewarray['camt'];
	$pbaseprice =$viewarray['bpprice'];
	$bquan      =$defmeas;
	$addproc		=0;

	if ($poolcomm_adj >= 1)
	{
		$bcomm      =0;
	}
	else
	{
		if (isset($viewarray['com_base_rate']) && $viewarray['com_base_rate']!=0)
		{
			$bcomm      =$viewarray['bpprice'] * $viewarray['com_base_rate'];
		}
		else
		{
			$bcomm      =$viewarray['bpcomm'];
		}
	}

	$fpbaseprice = number_format($pbaseprice, 2, '.', '');
	$fbcomm      = number_format($bcomm, 2, '.', '');
	$ctramt      = $viewarray['camt']+$tjaddpr;
	$fctramt     = number_format($ctramt, 2, '.', '');

	if ($rowI[10] > 1 or $masjinfo[1] >= 5)
	{
		$tbg		="lightgreen";
	}
	elseif ($rowI[10]==1)
	{
		$tbg		="magenta";
	}
	else
	{
		$tbg		="gray";
	}

	if ($rowpreBb['mjadd'] > 0)
	{
		if ($rowpreBc['pmasreq']==1)
		{
			$tbg	= "lightgreen";
		}
		elseif ($rowpreBc['post_add']==1)
		{
			$tbg	= "yellow";
		}
	}
	
	if (isset($rowC[12]) and $rowC[12]==2)
	{
		if (isset($viewarray['acc_status']))
		{
			if ($viewarray['acc_status']==1)
			{
				$tbg		="magenta";
			}
			elseif ($viewarray['acc_status']==5)
			{
				$tbg		="lightgreen";
			}
		}
	}

	$brdr=0;

	create_old_bid_info($_SESSION['officeid'],$viewarray['njobid'],$viewarray['jadd']);

	$_SESSION['viewarray']=$viewarray;
	
	
	if (isset($rowC[14]) and $rowC[14] == 1)
	{
		echo "<script type=\"text/javascript\" src=\"js/jquery_job_status_onload.js\"></script>\n";
	}
    
	echo "<script type=\"text/javascript\" src=\"js/jquery_job_func.js\"></script>\n";
    
    if ($_SESSION['securityid']==269999999999999999999999)
    {
        echo "<div id=\"debug_dialog\" title=\"JMS Debug\"></div>\n";
    }
    
	echo "<table class=\"transnb\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"100%\" align=\"right\">\n";
	echo "			<table class=\"transnb\" width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" colspan=\"3\">\n";

	info_display_job($tbg,$rowC[1],$destidret[0],$viewarray['jadd'],$rowD['fname'],$rowD['lname'],$rowL['fname'],$rowL['lname'],"Retail","Job",$viewarray['estsecid'],$njobid,$viewarray['jobid']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	cinfo_display_job($_SESSION['officeid'],$viewarray['cid'],$rowC[2]);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"33%\">\n";

	pool_detail_display_job($viewarray['njobid'],$viewarray['jadd']);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	dates_display_job($viewarray['cid']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";

	// Start Retail Items
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	echo "         <table class=\"outer\" width=\"100%\">\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"left\" width=\"100\"><b>Category</td>\n";
	echo "              <td class=\"wh\" align=\"left\"><b>Item</td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"30\"><b>Quan</td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"30\"><b>Units</td>\n";
	echo "              <td class=\"wh\" align=\"center\"><b>Retail</td>\n";
	echo "              <td class=\"wh\" align=\"center\"><b>Comm</td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"left\" width=\"100\">Base</td>\n";
	echo "              <td class=\"wh\" align=\"left\">Basic Pool</td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"30\">".$bquan."</td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"30\">";

	if ($rowC[8]=="p")
	{
		echo "pft";
	}
	else
	{
		echo "sqft";
	}

	echo "					</td>\n";
	echo "              <td class=\"wh\" align=\"right\">".$fpbaseprice."</td>\n";
	echo "              <td class=\"wh\" align=\"right\">".$fbcomm."</td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";

	//echo "D: ".$rowpreA['estdata']."<BR>";
	calcbyacc($rowpreA['estdata'],$rowpreA['filters']);

	// Totals Table Calcs
	$bccost  =$bctotal;
	$rccost  =$rctotal;
	$cccost  =$cctotal;
	$bmcost  =$bmtotal;
	$rmcost  =$rmtotal;
	$trccost =$rccost+$rmcost;
	$cmcost  =$cmtotal;
	$tbcost  =$bccost+$bmcost;
	$trcost  =$pbaseprice+$trccost+$tbid;
	$tccost  =$cccost+$cmcost;
	$trcomm  =$bcomm+$tccost;
	$prof    =($trcost-$tbcost)-$trcomm;
	$perprof =($trcost!=0)?$prof/$trcost:0;

	if ($rowC[2]==1)
	{
		$rtax    =$ctramt*$taxrate[1];
		$grtcost =$ctramt+$rtax;
		$frtax   =number_format($rtax, 2, '.', '');
		$fgrtcost=number_format($grtcost, 2, '.', '');
	}

	$fbccost    =number_format($bccost, 2, '.', '');
	$fbmcost    =number_format($bmcost, 2, '.', '');
	$fcccost    =number_format($cccost, 2, '.', '');
	$frccost    =number_format($rccost, 2, '.', '');
	$frmcost    =number_format($rmcost, 2, '.', '');
	$fcmcost    =number_format($cmcost, 2, '.', '');
	$ftbcost    =number_format($tbcost, 2, '.', '');
	$ftrcost    =number_format($trcost, 2, '.', '');
	$ftccost    =number_format($tccost, 2, '.', '');
	$ftrcomm    =number_format($trcomm, 2, '.', '');

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Price per Book</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftrcost."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$ftrcomm."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";

	calc_adjusts();

	$adjbookamt		=$trcost+$discount;
	$fadjbookamt	=number_format($adjbookamt, 2, '.', '');
	$adjctramt		=$ctramt-$adjbookamt;
	$fadjctramt		=number_format($adjctramt, 2, '.', '');

	$adjcomm=0;

	$ou_out=calc_ou($adjctramt,$adjcomm,$tbullets,$rowC[6],$viewarray['applyov'],$viewarray['comadj'],$rowC[5],$rowC[7]);

	$tadjcomm		=$trcomm;
	$foucomm		=number_format($viewarray['comadj'], 2, '.', '');
	$fadjcomm		=number_format($ou_out[1], 2, '.', '');
	$ftadjcomm		=number_format($tadjcomm, 2, '.', '');

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Adjusted Book Price</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fadjbookamt."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Retail Contract Price</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fctramt."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\">\n";
	echo "              </td>\n";
	echo "           </tr>\n";

	//Over/Under Split Percentage
	$osplitperc=0;
	
	if (isset($fctramt) && $fctramt!=0)
	{
		$osplitperc=round(($fadjctramt/$fctramt)*100);
	}

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Overage/Underage</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";
	
	if ($viewarray['added'] < $viewarray['ncommdate'])
	{
		if ($osplitperc < 0)
		{
			echo "              <font color=\"red\">".$osplitperc."%</font>\n";
		}
		else
		{
			echo "              ".$osplitperc."%\n";
		}
	}

	echo "				</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";

	if ($adjctramt < 0)
	{
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\">".$fadjctramt."</font></td>\n";
	}
	else
	{
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fadjctramt."</td>\n";
	}

	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";

	if ($rowC[2]==1)
	{
		$ftaxrate=number_format($taxrate[1], 3, '.', '');
		echo "            <tr>\n";
		echo "               <td colspan=\"2\" class=\"wh\" align=\"right\">Tax</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\">".$ftaxrate."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "               <td align=\"right\" class=\"wh\">".$frtax."</td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td colspan=\"2\" class=\"wh\" align=\"right\">Total:</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "               <td align=\"right\" class=\"wh\">".$fgrtcost."</td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "            </tr>\n";
	}

	if ($viewarray['applyov']==0)
	{
		$tadjcomm=$trcomm+$fadjcomm;
	}
	else
	{
		$tadjcomm=$trcomm+$foucomm;
	}

	$ftadjcomm	=number_format($tadjcomm, 2, '.', '');

	if ($viewarray['added'] < $viewarray['ncommdate'])
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Commission Adjust</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";
	
		if ($tbullets > 0)
		{
			echo $tbullets." Bullets";
		}
	
		echo "</td>\n";
	
		if ($_SESSION['clev'] >= 4)
		{
			echo "              <td NOWRAP class=\"wh\" align=\"right\">".$foucomm."</td>\n";
			echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
		}
		else
		{
			if ($foucomm < 0)
			{
				echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\">".$foucomm."</font></td>\n";
			}
			else
			{
				echo "              <td NOWRAP class=\"wh\" align=\"right\">".$foucomm."</td>\n";
			}
	
			echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
		}
	
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Total Commission</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	
		if ($ftadjcomm < 0)
		{
			echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\">".$ftadjcomm."</font></td>\n";
		}
		else
		{
			echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftadjcomm."</td>\n";
		}
	
		echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
	}
	else
	{
		CommissionScheduleRW_Job($viewarray);
	}

	// Addendum Selection Display, if any
	$traddnpr		=0;
	$traddncm		=0;
	$traddnpr_man	=0;
	$traddncm_man	=0;
	$traddnpr_subman=0;
	$traddncm_subman=0;
	$prevctramt		=0;
	$prevcmamt		=0;
    
    $qryV = "SELECT jadd,raddnpr,raddncm,raddnpr_man,raddncm_man,psched_adj,taxrate,add_type,pmasreq,post_add FROM jdetail WHERE officeid=". (int) $_SESSION['officeid']." AND njobid='".$_REQUEST['njobid']."' AND post_add=0 AND jadd!=0;";
	$resV = mssql_query($qryV);
	$nrowV= mssql_num_rows($resV);

	$qryY = "SELECT jadd,raddnpr,raddncm,raddnpr_man,raddncm_man,psched_adj,taxrate,add_type,pmasreq,post_add FROM jdetail WHERE officeid=". (int) $_SESSION['officeid']." AND njobid='".$_REQUEST['njobid']."' AND post_add=1 AND jadd!=0;";
	$resY = mssql_query($qryY);
	$nrowY= mssql_num_rows($resY);

	if ($nrowV > 0 && $jaddn==0)
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"7\" class=\"gray\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"left\">Contract Adjustments</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" valign=\"bottom\" align=\"center\" width=\"60\"></td>\n";
		echo "           </tr>\n";

		while ($rowV = mssql_fetch_array($resV))
		{
			if ($rowV['jadd'] >= 1)
			{
				if ($rowC[2]==1)
				{
					if (!empty($rowV['taxrate']) && $rowV['taxrate']!='0.0')
					{
						$Vaddtrate=$rowV['taxrate'];
						$tx="1";
					}
					else
					{
						$Vaddtrate=$taxrate[1];
						$tx="2";
					}
					$fVaddtrate			=number_format($Vaddtrate, 3, '.', '');
					$fraddntr_subman	=number_format($rowV['raddnpr_man']*$Vaddtrate, 2, '.', '');
				}
				
				if ($rowV['add_type']==0)
				{
					$add_type			="Customer Addendum";
					
					if (isset($rowV['psched_adj']))
					{
						$fraddnpr_subman	=number_format($rowV['psched_adj'], 2, '.', '');
					}
					else
					{
						$fraddnpr_subman	=number_format(0, 2, '.', '');
					}
					
					$add_txt='This amount affects the Retail Payment Schedule';
				}
				else
				{
					$add_type			="GM Adjust";
					$fraddnpr_subman	=number_format($rowV['raddnpr_man'], 2, '.', '');
					$add_txt='This amount does not affect the Retail Payment Schedule';
				}
				
				if ($viewarray['added'] < $viewarray['ncommdate'])
				{
					$fraddncm_subman	=number_format($rowV['raddncm_man'], 2, '.', '');
				}
				else
				{
					//$qryVa = "SELECT amt FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$rowpreB['jobid']."' and jadd=".$rowV['jadd'].";";
                    $qryVa = "SELECT amt FROM jest..CommissionHistory WHERE oid=".$_SESSION['officeid']." AND jobid='".$rowpreB['jobid']."' and jadd=".$rowV['jadd'].";";
					$resVa = mssql_query($qryVa);
					$rowVa = mssql_fetch_array($resVa);
					$fraddncm_subman	=number_format($rowVa['amt'], 2, '.', '');
				}
				
				echo "           <tr>\n";
				echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">".$add_type." # ".$rowV['jadd']." Total:</td>\n";
				echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
				echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";

				if ($fraddnpr_subman < 0)
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><span class=\"JMStooltip\" title=\"".$add_txt."\"><font color=\"red\" width=\"60\">".$fraddnpr_subman."</font></span></td>\n";
				}
				else
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\"><span class=\"JMStooltip\" title=\"".$add_txt."\">".$fraddnpr_subman."</span></td>\n";
				}

				echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$fraddncm_subman."</td>\n";
				echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\">\n";

				echo "<div class=\"noPrint\">\n";
				echo "<form method=\"post\">\n";
				echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
				echo "<input type=\"hidden\" name=\"call\" value=\"view_job_addendum_retail\">\n";
				echo "<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
				echo "<input type=\"hidden\" name=\"jadd\" value=\"".$rowV['jadd']."\">\n";

				if ($rowV['pmasreq']==1)
				{
					echo "              	<input class=\"buttondkgrn\" type=\"submit\" value=\"View\">\n";
				}
				else
				{
					echo "              	<input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
				}

				echo "</form>";
				echo "</div>\n";
				echo "					</td>\n";
				echo "           </tr>\n";

				if ($rowC[2]==1)
				{
					echo "           <tr>\n";
					echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">".$add_type." # ".$rowV['jadd']."  Tax:</td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fVaddtrate."</td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";

					if ($fraddnpr_subman < 0)
					{
						echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\" width=\"60\">".$fraddntr_subman."</font></td>\n";
					}
					else
					{
						echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$fraddntr_subman."</td>\n";
					}

					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\"></td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
					echo "           </tr>\n";
				}
			}

			if ($rowC[2]==1)
			{
				$traddnpr_subman		=$traddnpr_subman+($fraddnpr_subman+$fraddntr_subman);
			}
			else
			{
				$traddnpr_subman		=$traddnpr_subman+$fraddnpr_subman;
			}

			$traddncm_subman=$traddncm_subman+$fraddncm_subman;
		}

		$sftraddnpr_subman	=number_format($traddnpr_subman, 2, '.', '');
		$sftraddncm_subman	=number_format($traddncm_subman, 2, '.', '');
		
		if ($viewarray['added'] < $viewarray['ncommdate'])
		{
		}
		else
		{
			$qryVz = "SELECT SUM(amt) as tamt FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$rowpreB['jobid']."' and jadd=0 and cbtype!=4;";
			$resVz = mssql_query($qryVz);
			$rowVz = mssql_fetch_array($resVz);
			$ftadjcomm	=number_format($rowVz['tamt'], 2, '.', '');
		}

		if ($rowC[2]==1)
		{
			$prevctramt=$fgrtcost;
			$prevcmamt=$ftadjcomm;
		}
		else
		{
			$prevctramt=$ctramt;
			$prevcmamt=$ftadjcomm;
		}

		$ftraddnpr_man	=number_format($prevctramt+$sftraddnpr_subman, 2, '.', '');
		$ftraddncm_man	=number_format($prevcmamt+$sftraddncm_subman, 2, '.', '');

		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Addendum SubTotal</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$sftraddnpr_subman."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$sftraddncm_subman."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Revised Total</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftraddnpr_man."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftraddncm_man."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
		//echo "         </table>\n";
	}
	else
	{
		if ($viewarray['added'] < $viewarray['ncommdate'])
		{
		}
		else
		{
			$qryVz = "SELECT SUM(amt) as tamt FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$rowpreB['jobid']."' and jadd=0 and cbtype!=4;";
			$resVz = mssql_query($qryVz);
			$rowVz = mssql_fetch_array($resVz);
			$ftadjcomm	=number_format($rowVz['tamt'], 2, '.', '');
		}
		
		if ($rowC[2]==1)
		{
			$ftraddnpr_man=$fgrtcost;
			$ftraddncm_man=$ftadjcomm;
		}
		else
		{
			$ftraddnpr_man=$ctramt;
			$ftraddncm_man=$ftadjcomm;
		}
	}

	if ($nrowY > 0 && $jaddn==0)
	{
		echo "         </table>\n";
		
		//echo $qryY;
		echo "<br>\n";
		echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" width=\"100%\" border=1>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"left\">Post Job Addendums</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" valign=\"bottom\" align=\"center\" width=\"60\"></td>\n";
		echo "           </tr>\n";

		$traddnpr_pst	=0;
		$traddncm_pst	=0;
		while ($rowY = mssql_fetch_array($resY))
		{
			if ($rowY['jadd'] >=1)
			{
				if (strtotime($rowpreA['added']) < strtotime($rowD['newcommdate']))
				{
					$raddncm_pst	=$rowY['raddncm_man'];
					$fraddncm_pst	=number_format($raddncm_pst, 2, '.', '');
				}
				else
				{
					//echo 'HIT';
					$qryYa = "SELECT amt FROM jest..CommissionHistory WHERE jobid='".$rowpreB['jobid']."' and jadd=".$rowY['jadd'].";";
					$resYa = mssql_query($qryYa);
					$rowYa = mssql_fetch_array($resYa);
					
					$raddncm_pst	=$rowYa['amt'];
					$fraddncm_pst	=number_format($raddncm_pst, 2, '.', '');
				}
				
				if ($rowC[2]==1)
				{
					if (!empty($rowY['taxrate']) && $rowY['taxrate']!='0.0')
					{
						$PVaddtrate=$rowY['taxrate'];
						$ptx="1";
					}
					else
					{
						$PVaddtrate=$taxrate[1];
						$ptx="2";
					}
					$fPVaddtrate		=number_format($PVaddtrate, 3, '.', '');
					$fPraddntr_subman	=number_format($rowY['raddnpr_man']*$PVaddtrate, 2, '.', '');
				}
				
				if ($rowY['add_type']==0)
				{
					$padd_type="Customer Addendum";
					
					if (isset($rowY['psched_adj']) and $rowY['psched_adj'] != 0)
					{
						$raddnpr_pst	=$rowY['psched_adj'];
					}
					else
					{
						$raddnpr_pst	=0;
					}
					
					$fraddnpr_pst	=number_format($raddnpr_pst, 2, '.', '');
					$padd_txt='This amount affects the Retail Payment Schedule';
					
					//echo $fraddnpr_pst;
				}
				else
				{
					$padd_type		="GM Adjust";
					$raddnpr_pst	=$rowY['raddnpr_man'];
					$fraddnpr_pst	=number_format($raddnpr_pst, 2, '.', '');
					$padd_txt='This amount does not affect the Retail Payment Schedule';
				}

				echo "<form method=\"post\">\n";
				echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
				echo "<input type=\"hidden\" name=\"call\" value=\"view_job_addendum_retail\">\n";
				echo "<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
				echo "<input type=\"hidden\" name=\"jadd\" value=\"".$rowY['jadd']."\">\n";
				echo "           <tr>\n";
				echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">".$padd_type." # ".$rowY['jadd']." Total:</td>";
				echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
				echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";

				if ($fraddnpr_pst < 0)
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><span class=\"JMStooltip\" title=\"".$padd_txt."\"><font color=\"red\" width=\"60\">".$fraddnpr_pst."</font></span></td>\n";
				}
				else
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\"><span class=\"JMStooltip\" title=\"".$padd_txt."\">".$fraddnpr_pst."</span></td>\n";
				}

				if ($fraddncm_pst < 0)
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\" width=\"60\">".$fraddncm_pst."</font></td>\n";
				}
				else
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$fraddncm_pst."</td>\n";
				}

				echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\">\n";
				echo "					<div class=\"noPrint\">\n";
				
				if ($rowY['pmasreq']==1)
				{
					echo "              	<input class=\"buttondkgrn\" type=\"submit\" value=\"View\">\n";
				}
				else
				{
					echo "              	<input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
				}

				echo "					</div>\n";
				echo "				</td>\n";
				echo "           </tr>\n";
				
				if ($rowC[2]==1)
				{
					echo "           <tr>\n";
					echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">".$padd_type." # ".$rowY['jadd']."  Tax:</td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fPVaddtrate."</td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";

					if ($fPraddntr_subman < 0)
					{
						echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\" width=\"60\">".$fPraddntr_subman."</font></td>\n";
					}
					else
					{
						echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$fPraddntr_subman."</td>\n";
					}

					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\"></td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
					echo "           </tr>\n";
				}

				echo "</form>";

				if ($rowC[2]==1)
				{
					$traddnpr_pst		=$traddnpr_pst+ ($raddnpr_pst+$fPraddntr_subman);
					$traddncm_pst		=$traddncm_pst+$raddncm_pst;
				}
				else
				{
					$traddnpr_pst		=$traddnpr_pst+$raddnpr_pst;
					$traddncm_pst		=$traddncm_pst+$raddncm_pst;
				}
			}
		}

		$ftraddnpr_pst	=number_format($traddnpr_pst, 2, '.', '');
		$ftraddncm_pst	=number_format($traddncm_pst, 2, '.', '');
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Addendum SubTotal:</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";
		
		if ($traddnpr_pst < 0)
		{
			echo "              <font color=\"red\">".$ftraddnpr_pst."</font></td>\n";
		}
		else
		{
			echo $ftraddnpr_pst;
		}
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";
		
		if ($traddncm_pst < 0)
		{
			echo "              <font color=\"red\">".$ftraddncm_pst."</font></td>\n";
		}
		else
		{
			echo $ftraddncm_pst;
		}
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";

		$traddnpr_pstd		=$ftraddnpr_man+$traddnpr_pst;
		$traddncm_pstd		=$ftraddncm_man+$ftraddncm_pst;
		$ftraddnpr_pstd	=number_format($traddnpr_pstd, 2, '.', '');
		$ftraddncm_pstd	=number_format($traddncm_pstd, 2, '.', '');
		
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\">Post Job Contract and Commission Total:</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftraddnpr_pstd."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftraddncm_pstd."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
	}
	else
	{
		$traddnpr_pstd		=$ftraddnpr_man;
		$traddncm_pstd		=$ftraddncm_man;
		$ftraddnpr_pstd	=number_format($traddnpr_pstd, 2, '.', '');
		$ftraddncm_pstd	=number_format($traddncm_pstd, 2, '.', '');
	}
	
	$viewarray['tcomm']		=$ftraddncm_pstd;
	$viewarray['tretail']	=$adjbookamt;
	$viewarray['tcontract']	=$ctramt;
	$viewarray['acctotal']	=$trccost;
	$viewarray['discount']	=$vdiscnt;
	$viewarray['royrel']	=0;
	$viewarray['custallow']	=0;

	echo "         </table>\n";
	echo "      </td>\n";
	
	// End Retail Items

	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table class=\"transnb\" cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";
	echo "      	<form method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	//echo "         <input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
	echo "         <input type=\"hidden\" name=\"rcall\" value=\"view\">\n";
	echo "			<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
	echo "			<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "			<input type=\"hidden\" name=\"fofficeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "         <input type=\"hidden\" name=\"custid\" value=\"".$viewarray['custid']."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"OneSheet\"><br>\n";
	echo "					</div>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";

	echo "            <tr>\n";
	echo "				<td align=\"center\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "						<hr width=\"90%\">\n";
	echo "					</div>\n";
	echo "				</td>\n";
	echo "            </tr>\n";

	$qrypreD = "SELECT MAX(jadd) AS mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);

	echo "            <tr>\n";
	echo "               <td align=\"left\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "                        <form method=\"POST\">\n";
	echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$rowpreB['estid']."\">\n";
	echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
	echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$rowpreD['mjadd']."\">\n";
	echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "                           <input type=\"hidden\" name=\"add_type\" value=\"0\">\n";

	if ($rowI[10] >= 2 || $masjinfo[1] >= 5)
	{
		if ($_SESSION['jlev'] >= 4)
		{
			echo "                           <input type=\"hidden\" name=\"call\" value=\"create_add_post_mas\">\n";
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Cust Addn\">\n";
		}
	}
	else
	{
		echo "                           <input type=\"hidden\" name=\"call\" value=\"create_add\">\n";
		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Cust Addn\" ".$masstat." title=\"".$mrttext."\">\n";
	}

	echo "                        </form>\n";
	echo "					</div>\n";
	echo "               </td>\n";
	echo "            </tr>\n";

	if ($_SESSION['jlev'] >= 6)
	{
		// GM add_type
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "					<div class=\"noPrint\">\n";
		echo "                        <form method=\"POST\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$rowpreB['estid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$rowpreD['mjadd']."\">\n";
		echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
		echo "                           <input type=\"hidden\" name=\"add_type\" value=\"1\">\n";

		if ($rowI[10] >= 2 || $masjinfo[1] >= 5)
		{
			echo "                           <input type=\"hidden\" name=\"call\" value=\"create_add_post_mas\">\n";
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"GM Adjust\">\n";
		}
		else
		{
			echo "                           <input type=\"hidden\" name=\"call\" value=\"create_add\">\n";
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"GM Adjust\" ".$masstat.">\n";
		}

		echo "                        </form>\n";
		echo "					</div>\n";
		echo "               <td>\n";
		echo "            </tr>\n";
	}

	if ($_SESSION['jlev'] >= 6)
	{
		echo "            <tr>\n";
		echo "               <td align=\"center\">\n";
		echo "					<div class=\"noPrint\">\n";
		echo "						<hr width=\"90%\">\n";
		echo "					</div>\n";
		echo "				</td>\n";
		echo "            </tr>\n";

		if ($rowI[10]==0 && $pstadd==0 && $rowpreD['mjadd']==0)
		{
			//if (isset($viewarray['digdate']) and  strtotime($viewarray['digdate']))
			if (!digreportexists($viewarray['digdate']))
			{
				echo "            <tr>\n";
				echo "               <td align=\"left\">\n";
				echo "					<div class=\"noPrint\">\n";
				echo "                  	<form id=\"submit_RevertToContract\" method=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"delete_job1\">\n";
				echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"jobid\" id=\"usr_jobid\" value=\"".$viewarray['jobid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"oid\" id=\"usr_oid\" value=\"".$_SESSION['officeid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"jid\" id=\"usr_jid\" value=\"".$viewarray['jid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				
				if ($rowC[14] == 1) //Setting for QB
				{
					echo "                           <input class=\"buttondkgrypnl80\" id=\"usr_RevertToContract\" value=\"Revert to Contract\" ".$masstat.">\n";
				}
				else
				{
					echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Revert to Contract\" ".$masstat.">\n";
				}
				
				echo "                        </form>\n";
				echo "					</div>\n";
				echo "               </td>\n";
				echo "            </tr>\n";
			}
		}

		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		
		if ($rowC[9]==1)
		{
			echo "					<div class=\"noPrint\">\n";
			echo "					<form method=\"post\">\n";
			echo "					<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "					<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
			echo "					<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
			echo "					<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "					<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "					<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
			echo "					<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
			echo "					<input type=\"hidden\" name=\"showtotals\" value=\"0\">\n";
			echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Cost\">\n";
			echo "					</form>\n";
			echo "					</div>\n";
		}

		echo "               </td>\n";
		echo "            </tr>\n";
	}

	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332) {		
		if (isset($rowC[13]) and $rowC[13] == 1) {
			// Detect lack of Cost Bid Items
			$cbid_ecnt=bid_cost_detect($_SESSION['officeid'],$viewarray['njobid'],$viewarray['mjadd']);
			
			if ($_REQUEST['action']=="job" && $rowpreC['acctngrelease'] >= 1)
			{
				echo "            <tr>\n";
				echo "               <td align=\"center\">\n";
				echo "					<div class=\"noPrint\">\n";
				echo "						<hr width=\"90%\">\n";
				echo "					</div>\n";
				echo "				</td>\n";
				echo "            </tr>\n";
				echo "            <tr>\n";
				echo "               <td align=\"left\">\n";
				echo "					<div class=\"noPrint\">\n";
				echo "					<form method=\"post\">\n";
				echo "					<input type=\"hidden\" name=\"action\" value=\"job\">\n";
				echo "					<input type=\"hidden\" name=\"call\" value=\"set_mas\">\n";
				echo "					<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
				echo "					<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
		
				if ($viewarray['mas_prep']==1)
				{
					echo "<input type=\"hidden\" name=\"setmas\" value=\"0\">\n";
				}
				else
				{
					echo "<input type=\"hidden\" name=\"setmas\" value=\"1\">\n";
				}
		
				if ($viewarray['mas_prep'] >= 1)
				{
					if ($_SESSION['jlev'] >= 8)
					{
						if ($viewarray['mas_prep'] >= 2 || $masjinfo[1] >= 5)
						{
							echo "                  <input class=\"buttondkgrnpnl80\" type=\"submit\" value=\"MAS Not Ready\" DISABLED title=\"This job has been Processed\">\n";
						}
						else
						{
							echo "                  <input class=\"buttondkgrnpnl80\" type=\"submit\" value=\"MAS Not Ready\">\n";
						}
					}
					else
					{
						echo "                  <input class=\"buttondkgrnpnl80\" type=\"submit\" value=\"MAS Not Ready\" ".$masstat."  title=\"".$mrttext."\">\n";
					}
					//echo "                  <input class=\"buttondkgrnpnl80\" type=\"submit\" value=\"MAS Not Ready\" ".$masstat."  title=\"".$mrttext."\">\n";
				}
				else
				{
					/*
					if ($cbid_ecnt > 0 && $viewarray['oldbid']!=1)
					{
						echo "                  <input class=\"buttondkredpnl80\" type=\"submit\" value=\"MAS Ready\" DISABLED title=\"You must add Cost to all Bid Items\">\n";
					}
					else
					{
						echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"MAS Ready\" ".$masstat." title=\"".$mrttext."\">\n";
					}
					*/
					echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"MAS Ready\" ".$masstat." title=\"".$mrttext."\">\n";
				}
		
				echo "						</form>\n";
				echo "					</div>\n";
				echo "               </td>\n";
				echo "            </tr>\n";
			}
		}
	}
	
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	
	//$dbg=1;
	if (isset($dbg) && $dbg==1 && $_SESSION['securityid']==SYS_ADMIN)
	{
		echo "<pre>";
		print_r($viewarray);
		echo "</pre>";
	}

	$_SESSION['viewarray']=$viewarray;
}

function view_job_cost()
{
	error_reporting(E_ALL);
	
	if (!isset($_SESSION['viewarray']))
	{
		echo "Fatal Error: Job Cost variables not set!";
		exit;
	}
	
	global $bctotal,$bcadjtotal,$rctotal,$cctotal,$bmtotal,$bmadjtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	
	$dbg		=0;
	$viewarray	=$_SESSION['viewarray'];
	
	//print_r($viewarray);
	
	$njobid		=$viewarray['njobid'];
	$jobid		=$viewarray['jobid'];
	$jadd		=$viewarray['jadd'];
	$securityid =$_SESSION['securityid'];
	$officeid	=$_SESSION['officeid'];
	$fname		=$_SESSION['fname'];
	$lname		=$_SESSION['lname'];
	
	if (!isset($njobid)||$njobid=='')
	{
		//echo "Fatal Error: Job ID (".$njobid.") not set!";
		//exit;
		$viewarray['njobid']=$_REQUEST['njobid'];
		$njobid		=$viewarray['njobid'];
	}
	
	//echo "OLDBID: ".$viewarray['oldbid']."<br>";

	//$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='".$jadd."';";
	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='0';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreAa = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='0';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreAb = "SELECT MAX(jadd) as maxjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."';";
	$respreAb = mssql_query($qrypreAb);
	$rowpreAb = mssql_fetch_array($respreAb);

	//echo $qrypreA."<br>";
	$masjinfo=getmasjobinfo($njobid);

	$qrypreB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	//echo $qrypreB."<br>";
	
	$viewarray['jadd']	=$rowpreA['jadd'];
	$viewarray['mjadd']	=$rowpreAb['maxjadd'];

	$qrypreC = "SELECT costdata_l,costdata_m FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='".$jadd."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_row($respreC);

	$qrypreD = "SELECT officeid,pft_sqft,accountingsystem FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);

	$jsecurityid =$rowpreB['securityid'];

	$acclist=explode(",",$_SESSION['aid']);

	//if (!in_array($rowpreB['securityid'],$acclist))
	if (!in_array($_SESSION['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Job</b>";
		exit;
	}

	if ($rowpreD['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$c_jobdata_l = $rowpreA['costdata_l'];
	$c_jobdata_m = $rowpreA['costdata_m'];
	$b_jobdata_l = $rowpreA['bcostdata_l'];
	$b_jobdata_m = $rowpreA['bcostdata_m'];
	$p_jobdata_l = $rowpreA['pcostdata_l'];
	$p_jobdata_m = $rowpreA['pcostdata_m'];

	if (isset($viewarray['acctotal'])||$viewarray['acctotal']!=0)
	{
		$acctotal=$viewarray['acctotal'];
	}
	else
	{
		$acctotal=0;
	}

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryC = "SELECT officeid,name,stax,sm,gm,accountingsystem FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$qryD = "SELECT securityid,fname,lname,mas_div,rmas_div FROM security WHERE securityid='".$jsecurityid."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,mas_prep,cid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$viewarray['cid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$viewarray['mas_prep']	=$rowI['mas_prep'];
	//$viewarray['cid']			=$rowI['cid'];

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_array($resL);

	if ($rowC[2]==1)
	{
		if (!empty($viewarray['taxrate']) && $viewarray['taxrate']!="0.00")
		{
			$taxrate=array(0=>$viewarray['tax'],1=>$viewarray['taxrate']);
			$viewarray['were']		="from Stored<br>";
		}
		else
		{
			$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI['scounty']."';";
			$resJ = mssql_query($qryJ);
			$rowJ = mssql_fetch_row($resJ);

			$viewarray['taxrate']	=$rowJ[0];
			$viewarray['tax']		=$viewarray['camt']*$viewarray['taxrate'];
			$taxrate				=array(0=>$viewarray['tax'],1=>$viewarray['taxrate']);
			$viewarray['were']		="from Dynamic<br>";
		}

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}

	if ($rowI[10] > 1 || $masjinfo[1] >= 5)
	{
		$tbg		="lightgreen";
	}
	elseif ($rowI[10]==1)
	{
		$tbg		="magenta";
	}
	else
	{
		$tbg		="gray";
	}
	
	if (isset($rowC[5]) and $rowC[5]==2)
	{
		if (isset($viewarray['acc_status']) and $viewarray['acc_status']==1)
		{
			$tbg		="magenta";
		}
	}

	$sdate		=date("m/d/Y", strtotime($rowpreAa['added']));
	$cdate 		=date("m/d/Y", strtotime($viewarray['cdate']));

	if (isset($rowpreB['digdate']))
	{
		$ddate 	=date("m/d/Y", strtotime($rowpreB['digdate']));
	}
	else
	{
		$ddate	="N/A";
	}

	if (isset($rowpreB['closed']))
	{
		$cldate 	=date("m/d/Y", strtotime($rowpreB['closed']));
	}
	else
	{
		$cldate	="Open";
	}

	$set_ia		=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$estidret   =$viewarray['njobid'];
	
	if ($viewarray['renov']==1 && $rowD['rmas_div']!=0)
	{
		$destidret  =disp_mas_div_jobid($rowD['rmas_div'],$njobid);
	}
	else
	{
		$destidret  =disp_mas_div_jobid($rowD['mas_div'],$njobid);
	}
	
	//$destidret  =disp_mas_div_jobid($rowD['mas_div'],$njobid);
	$vdiscnt    =$viewarray['discount'];
	$pbaseprice =$rowB['price'];
	$bcomm      =$rowB['comm'];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');

	//print_r($viewarray);
	//show_post_vars();

	$brdr=0;
    
    echo "<script type=\"text/javascript\" src=\"js/jquery_job_cost_func.js\"></script>\n";
    echo "<script type=\"text/javascript\" src=\"js/jquery_estimate_cost_func.js\"></script>\n";
	echo "<table width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\">\n";
	echo "			<table width=\"100%\">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" colspan=\"3\">\n";

	info_display_job($tbg,$rowC['name'],$destidret[0],$viewarray['jadd'],$rowD['fname'],$rowD['lname'],$rowL['fname'],$rowL['lname'],"Cost","Job",$viewarray['estsecid'],$njobid);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"33%\">\n";
	
	cinfo_display_job($_SESSION['officeid'],$viewarray['cid'],$rowC[2]);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";
	
	pool_detail_display_job($viewarray['njobid'],$viewarray['jadd']);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";
	
	dates_display_job($viewarray['cid']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td>\n";
	// Placeholder
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"100%\">\n";

	//echo "MJADD1: ".$viewarray['mjadd']."<br>";
	//	Bids Rollup Display
	
	//print_r($viewarray);
	costadj_rollup_disp($_SESSION['officeid'],$viewarray['cid'],$viewarray['njobid'],$viewarray['mjadd'],$viewarray['allowdel']);
	
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table class=\"outer\" width=\"100%\" border=1>\n";

	//echo "PP: (".$p_jobdata_l.")<br>";

	calcbyphsL($c_jobdata_l,$b_jobdata_l,$p_jobdata_l,1);

	//$bccost  =$bctotal;
	//$fbccost =number_format(round($bccost), 2, '.', '');

	$bcestcost  =$bctotal;
	$bcadjcost  =$bcadjtotal;
	$tbccost	=$bcestcost+$bcadjcost;
	$fbcestcost =number_format($bcestcost, 2, '.', '');
	$fbcadjcost =number_format($bcadjcost, 2, '.', '');
	$ftbccost 	=number_format($tbccost, 2, '.', '');

	echo "           <tr>\n";

	if (!empty($_REQUEST['showtotals']) && $_REQUEST['showtotals']=="1")
	{
		echo "              <td NOWRAP colspan=\"5\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	}

	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fbcestcost."</b></td>\n";

	if ($_SESSION['manphsadj']==1)
	{
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fbcadjcost."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftbccost."</b></td>\n";
	}

	echo "           </tr>\n";
	echo "         </table>\n";
	//echo "         <div class=\"pagebreak\">\n";
	//echo "         <hr width=\"100%\">\n";
	echo "         <br>\n";
	echo "         <table class=\"outer\" width=\"100%\" border=1>\n";

	calcbyphsM($c_jobdata_m,$p_jobdata_m,1);

	//$bmcost  =$bmtotal;
	//$fbmcost =number_format(round($bmcost), 2, '.', '');
	//$fbmcost =number_format($bmcost, 2, '.', '');

	$bmestcost  =$bmtotal;
	$bmadjcost  =$bmadjtotal;
	$tbmcost		=$bmestcost+$bmadjcost;
	$fbmestcost =number_format($bmestcost, 2, '.', '');
	$fbmadjcost	=number_format($bmadjcost, 2, '.', '');
	$ftbmcost	=number_format($tbmcost, 2, '.', '');

	echo "           <tr>\n";

	if (!empty($_REQUEST['showtotals']) && $_REQUEST['showtotals']=="1")
	{
		echo "              <td NOWRAP colspan=\"5\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	}

	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fbmestcost."</b></td>\n";

	if ($_SESSION['manphsadj']==1)
	{
		echo "              	<td class=\"wh\" align=\"right\">".$fbmadjcost."</td>\n";
		echo "					</td>\n";
		echo "              	<td NOWRAP class=\"wh\" align=\"right\"><b>".$ftbmcost."</b></td>\n";
	}

	echo "           </tr>\n";
	echo "         </table>\n";

	// Payment Schedule Table
	if ($rowpreA['psched']!=0)
	{
		$taretail=0;
		echo "         <br>\n";
		echo "         <table class=\"outer\" width=\"100%\" border=1>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"50\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Retail Payment Schedule</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\">\n";
		
		if (isset($rowpreD['accountingsystem']) and $rowpreD['accountingsystem'] >= 2)
		{
			echo "<div id=\"InvoiceProcessStatus\"><img id=\"submit_PaySched_to_Invoice\" src=\"images/arrow_refresh_small.png\" title=\"Create Invoices in QB for this Job\"></div>\n";
		}
		
		echo "				</td>\n";
		echo "           </tr>\n";

		$phsar=explode(",",$rowpreA['psched']);
		$perar=explode(",",$rowpreA['psched_perc']);
		
		//print_r($perar);

		if (count($phsar)==count($perar))
		{
			foreach ($phsar as $an => $pc)
			{
				$qryZ = "SELECT phsid,phscode,extphsname,phsname FROM phasebase WHERE phscode='".$pc."';";
				$resZ = mssql_query($qryZ);
				$rowZ = mssql_fetch_array($resZ);

				$paymnt	=$perar[$an];
				$fpaymnt	=number_format($paymnt, 2, '.', '');

				echo "           <tr>\n";

				if ($pc=="501L")
				{
					echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"50\"><b>501L</b></td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Down Payment</b></td>\n";
				}
				else
				{
					echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"50\"><b>".$rowZ['phscode']."</b></td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$rowZ['phsname']."</b></td>\n";
				}

				echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><b>".$fpaymnt."</b></td>\n";
				echo "           </tr>\n";
				$taretail=$taretail+$paymnt;
			}
		}
		else
		{
			$taretail=$viewarray['camt'];
		}

		$ocontract  =$taretail;
		$focontract =number_format($ocontract, 2, '.', '');

		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"50\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Original Contract Total</b></td>\n";
		echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><b>".$focontract."</b></td>\n";
		echo "           </tr>\n";

		$qryX = "SELECT jadd,psched_adj,add_type,post_add,taxrate FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd!=0;";
		$resX = mssql_query($qryX);
		$nrowX= mssql_num_rows($resX);

		if ($nrowX > 0)
		{
			while ($rowX = mssql_fetch_array($resX))
			{
				if ($rowX['jadd'] >=1)
				{
					if ($rowX['add_type']==0)
					{
						$add_type="Customer";
					}
					else
					{
						$add_type="GM";
					}

					if ($rowX['post_add']==0)
					{
						$padd_type="";
					}
					else
					{
						$padd_type=" (Post MAS)";
					}

					$fpsched_adj	=number_format($rowX['psched_adj'], 2, '.', '');
					echo "           <tr>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"50\"><b>60".$rowX['jadd']."L</b></td>";
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$padd_type." ".$add_type." Addendum ".$rowX['jadd']."</b></td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"70\"><b>".$fpsched_adj."</b></td>\n";
					echo "           </tr>\n";
					
					if ($rowC[2]==1)
					{
						if (!empty($rowX['taxrate']) && $rowX['taxrate']!='0.0')
						{
							$vtxrate=$rowX['taxrate'];
						}
						else
						{
							$vtxrate=$taxrate[1];
						}
						$vtxcalc=$fpsched_adj*$vtxrate;
						
						$fvtxrate		=number_format($vtxrate, 3, '.', '');
						$fvtxcalc		=number_format($vtxcalc, 2, '.', '');
						echo "           <tr>\n";
						echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"50\"><b>60".$rowX['jadd']."L</b></td>";
						echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Addendum ".$rowX['jadd']." Sales Tax (".$fvtxrate.")</b></td>\n";
						echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"65\"><b>".$fvtxcalc."</b></td>\n";
						echo "           </tr>\n";
					}
					
					if ($rowC[2]==1)
					{
						$taretail=$taretail+($rowX['psched_adj']+$vtxcalc);
					}
					else
					{
						$taretail=$taretail+$rowX['psched_adj'];
					}
				}
			}
		}

		$ftaretail =number_format($taretail, 2, '.', '');
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"50\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Addendum Adjusted Contract Total</b></td>";
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"70\"><b>".$ftaretail."</b></td>\n";
		echo "           </tr>\n";
		echo "         </table>\n";
	}
	else
	{
		$taretail=$viewarray['camt'];
	}

	echo "         <br>\n";
	// Total Table
	//echo $viewarray['custallow'];

	$custallow	=$viewarray['custallow'];
	$tcustallow	=$custallow*-1;
	$tcontract  =$taretail;
	$ftcontract =number_format($tcontract, 2, '.', '');
	$tretail  =$viewarray['tretail'];
	$ftretail =number_format(round($tretail), 2, '.', '');

	if ($_SESSION['manphsadj']==1)
	{
		$tbcost  =$tbccost+$tbmcost;
	}
	else
	{
		$tbcost  =$bcestcost+$bmestcost;
	}

	if ($tcustallow != 0)
	{
		$tadjcontract	=$tcontract+$tcustallow;
	}
	else
	{
		$tadjcontract	=$tcontract;
	}

	if ($tcustallow != 0)
	{
		$tadjbcost		=round($tbcost+$tcustallow);
	}
	else
	{
		$tadjbcost		=round($tbcost);
	}

	if ($tcustallow != 0)
	{
		$tprofit		=$tadjcontract-$tadjbcost;
	}
	else
	{
		$tprofit		=$tcontract-$tbcost;
	}

	if ($tcontract!=0)
	{
		if ($tcustallow != 0)
		{
			$netper  =$tprofit/$tadjcontract;
		}
		else
		{
			$netper  =$tprofit/$tcontract;
		}
	}
	else
	{
		$netper  =0;
	}

	if ($tcustallow != 0)
	{
		$qryY = "UPDATE jobs SET tgp='".$netper."', jcost='".$tadjbcost."', jprof='".$tprofit."' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
	}
	else
	{
		$qryY = "UPDATE jobs SET tgp='".$netper."', jcost='".$tbcost."', jprof='".$tprofit."' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
	}

	$resY = mssql_query($qryY);

	$ftcustallow	=number_format($tcustallow, 2, '.', '');
	$ftcontract 	=number_format($tcontract, 2, '.', '');
	$ftadjcontract  =number_format($tadjcontract, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftadjbcost		=number_format($tadjbcost, 2, '.', '');
	$ftprofit		=number_format($tprofit, 2, '.', '');
	$fnetper 		=round($netper, 2)*100;

	echo "         <table class=\"outer\" width=\"100%\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"left\"><b>Totals</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Adjusted Contract Price</b></td>\n";
	echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><b>".$ftcontract."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Adjusted Contract Price</b></td>\n";
		echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><b>".$ftadjcontract."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Construction Total</b></td>\n";
	echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><b>".$ftbcost."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Adjusted Construction Total</b></td>\n";
		echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><b>".$ftadjbcost."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Net</b></td>\n";
	echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><b>".$ftprofit."</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Net %</b></td>\n";
	echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><b>".$fnetper."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\">\n";
	echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";
	echo "            <tr>\n";
	echo "      		<td valign=\"bottom\" align=\"left\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "						<form method=\"post\" id=\"ViewRetail\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "						<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
    echo "						<input type=\"hidden\" name=\"jobid\" id=\"costjobid\" value=\"".$viewarray['jobid']."\">\n";
	echo "						<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "						<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "	         			<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"View Retail\">\n";
	echo "						</form>\n";
	echo "					</div>\n";
	echo "      		</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"center\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "						<hr width=\"90%\">\n";
	echo "					</div>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"center\">\n";
	
    _show_hide_objectsNEW();
    
    /*
    if ($_SESSION['securityid']==26999999999999999999999999)
    {
        _show_hide_objectsNEW();
    }
    else
    {
        _show_hide_objects();
    }
    */
	
	echo "               </td>\n";
	echo "            </tr>\n";
    
    if (isset($_SESSION['acctngrelease']) and $_SESSION['acctngrelease']==1)
    {
        echo "            <tr>\n";
        echo "               <td align=\"center\">\n";
        echo "					<div class=\"noPrint\">\n";
        echo "						<hr width=\"60%\">\n";
        echo "					</div>\n";
        echo "				</td>\n";
        echo "            </tr>\n";
        echo "            <tr>\n";
        echo "               <td align=\"center\">\n";
        echo "                  <button id=\"objExportCst\" title=\"Export Customer: Provides Customer Information in CSV format\">Export Cust</div></button>\n";
        echo "               </td>\n";
        echo "            </tr>\n";
        echo "            <tr>\n";
        echo "               <td align=\"center\">\n";
        echo "                  <button id=\"objExportJob\" title=\"Export Cost: Provides a detailed list of Job Cost in CSV format\">Export Cost</div></button>\n";
        echo "               </td>\n";
        echo "            </tr>\n";
    }
	
	/*
	echo "            <tr>\n";
	echo "      		<td valign=\"bottom\" align=\"center\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "         				<hr width=\"70%\">\n";
	echo "					</div>\n";
	echo "      		</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"left\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "					<form method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"view_wo\">\n";
	echo "					<input type=\"hidden\" name=\"subq\" value=\"print\">\n";
	echo "					<input type=\"hidden\" name=\"njobid\" value=\"".$viewarray['njobid']."\">\n";
	echo "					<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	echo "					<input type=\"hidden\" name=\"estid\" value=\"$estidret\">\n";
	echo "					<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
	echo "					<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
	echo "					<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "                  <input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Work Order\">\n";
	echo "					</form>\n";
	echo "					</div>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	*/
	
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	
	$dbg=0;
	if (isset($dbg) && $dbg==1 && $_SESSION['securityid']==26)
	{
		echo "<pre>";
		print_r($viewarray);
		echo "</pre>";
	}
	
	$_SESSION['viewarray']=$viewarray;
	//show_array_vars($viewarray);
}

function view_workorder()
{
	//echo "TESTING COST<br>";
	global $bctotal,$bcadjtotal,$rctotal,$cctotal,$bmtotal,$bmadjtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	$njobid		=$_REQUEST['njobid'];
	$jadd		=$_REQUEST['jadd'];
	$securityid =$_SESSION['securityid'];
	$officeid	=$_SESSION['officeid'];
	$fname		=$_SESSION['fname'];
	$lname		=$_SESSION['lname'];

	if (!isset($njobid)||$njobid=='')
	{
		echo "Fatal Error: Job ID (".$jobid.") not set!";
		exit;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='".$jadd."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreAa = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='0';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreAb = "SELECT MAX(jadd) as maxjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."';";
	$respreAb = mssql_query($qrypreAb);
	$rowpreAb = mssql_fetch_array($respreAb);

	//echo $qrypreA."<br>";
	$masjinfo=getmasjobinfo($njobid);

	$qrypreB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	//echo $qrypreB."<br>";

	$qrypreC = "SELECT costdata_l,costdata_m FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$njobid."' AND jadd='".$jadd."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_row($respreC);

	$qrypreD = "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);

	$jsecurityid =$rowpreB['securityid'];

	$acclist=explode(",",$_SESSION['aid']);

	if (!in_array($rowpreB['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Estimate</b>";
		exit;
	}

	$viewarray=array(
	'ps1'=>		$rowpreA['pft'],
	'ps2'=>		$rowpreA['sqft'],
	'spa1'=>		$rowpreA['spa_pft'],
	'spa2'=>		$rowpreA['spa_sqft'],
	'spa3'=>		$rowpreA['spa_type'],
	'tzone'=>	$rowpreA['tzone'],
	'camt'=>		$rowpreA['contractamt'],
	'cdate'=>	$rowpreAa['contractdate'],
	'status'=>	$rowpreB['status'],
	'ps5'=>		$rowpreA['shal'],
	'ps6'=>		$rowpreA['mid'],
	'ps7'=>		$rowpreA['deep'],
	'custid'=>	$rowpreB['custid'],
	'custallow'=>0,
	'estsecid'=>$rowpreB['securityid'],
	'deck'=>		$rowpreA['deck'],
	'erun'=>		$rowpreA['erun'],
	'prun'=>		$rowpreA['prun'],
	'njobid'=>	$rowpreB['njobid'],
	'comadj'=>	$rowpreA['ouadj'],
	'sidm'=>		$rowpreB['sidm'],
	'tax'=>		$rowpreB['tax'],
	'taxrate'=>	$rowpreB['taxrate'],
	'applyou'=>	1,
	'refto'=>	$rowpreA['refto'],
	'ps1a'=>		$rowpreA['apft'],
	'bpprice'=>	$rowpreA['bpprice'],
	'bpcomm'=>	$rowpreA['bpcomm'],
	'addnpr'=>	$rowpreA['raddnpr_man'],
	'addncm'=>	$rowpreA['raddncm_man'],
	'royadj'=>	$rowpreA['raddnroy_man'],
	'jadd'=>		$rowpreA['jadd'],
	'maxjadd'=>	$rowpreAb['maxjadd'],
	'phsjadd'=>	0,
	'royrel'=>	0,
	'mas_prep'=>0,
	'discount'=>0
	);


	if ($rowpreD['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$c_jobdata_l = $rowpreA['costdata_l'];
	$c_jobdata_m = $rowpreA['costdata_m'];
	$b_jobdata_l = $rowpreA['bcostdata_l'];
	$b_jobdata_m = $rowpreA['bcostdata_m'];
	$p_jobdata_l = $rowpreA['pcostdata_l'];
	$p_jobdata_m = $rowpreA['pcostdata_m'];

	if (isset($_REQUEST['acctotal'])||$_REQUEST['acctotal']!=0)
	{
		$acctotal=$_REQUEST['acctotal'];
	}
	else
	{
		$acctotal=0;
	}

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryC = "SELECT officeid,name,stax,sm,gm FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$qryD = "SELECT securityid,fname,lname,mas_div FROM security WHERE securityid='".$jsecurityid."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,mas_prep,cid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$viewarray['custid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$viewarray['mas_prep']	=$rowI['mas_prep'];
	$viewarray['cid']			=$rowI['cid'];

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_array($resL);

	if ($rowC[2]==1)
	{
		if (!empty($viewarray['taxrate']) && $viewarray['taxrate']!="0.00")
		{
			$taxrate=array(0=>$viewarray['tax'],1=>$viewarray['taxrate']);
			$viewarray['were']		="from Stored<br>";
		}
		else
		{
			$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI['scounty']."';";
			$resJ = mssql_query($qryJ);
			$rowJ = mssql_fetch_row($resJ);

			$viewarray['taxrate']	=$rowJ[0];
			$viewarray['tax']			=$viewarray['camt']*$viewarray['taxrate'];
			$taxrate						=array(0=>$viewarray['tax'],1=>$viewarray['taxrate']);
			$viewarray['were']		="from Dynamic<br>";
		}

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}

	if ($rowI[10] > 1 || $masjinfo[1] >= 5)
	{
		$tbg		="lightgreen";
	}
	elseif ($rowI[10]==1)
	{
		$tbg		="magenta";
	}
	else
	{
		$tbg		="gray";
	}

	$sdate		=date("m/d/Y", strtotime($rowpreAa['added']));
	$cdate 		=date("m/d/Y", strtotime($viewarray['cdate']));

	if (isset($rowpreB['digdate']))
	{
		$ddate 	=date("m/d/Y", strtotime($rowpreB['digdate']));
	}
	else
	{
		$ddate	="N/A";
	}

	if (isset($rowpreB['closed']))
	{
		$cldate 	=date("m/d/Y", strtotime($rowpreB['closed']));
	}
	else
	{
		$cldate	="Open";
	}

	$set_ia		=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$estidret   =$viewarray['njobid'];
	$destidret  =disp_mas_div_jobid($rowD['mas_div'],$njobid);
	$vdiscnt    =$viewarray['discount'];
	$pbaseprice =$rowB['price'];
	$bcomm      =$rowB['comm'];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');

	//show_post_vars();

	$brdr=0;
	echo "<table width=\"100%\" border=".$brdr.">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\">\n";
	echo "			<table width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" colspan=\"3\">\n";

	info_display_job($tbg,$rowC['name'],$destidret[0],$viewarray['jadd'],$rowD['fname'],$rowD['lname'],$rowL['fname'],$rowL['lname'],"Work Order","Job",$viewarray['estsecid'],$njobid);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"33%\">\n";

	cinfo_display_job($rowpreB['officeid'],$viewarray['cid'],$rowC[2]);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	pool_detail_display_job($viewarray['njobid'],$viewarray['jadd']);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	dates_display_job($viewarray['cid']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td>\n";
	// Placeholder
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table width=\"100%\" bordercolor=\"black\" border=1>\n";

	calcbyphsL($c_jobdata_l,$b_jobdata_l,$p_jobdata_l,1);
	
	echo "         </table>\n";
	echo "         <br>\n";
	echo "         <table width=\"100%\" bordercolor=\"black\" border=1>\n";

	calcbyphsM($c_jobdata_m,$p_jobdata_m,1);

	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

?>