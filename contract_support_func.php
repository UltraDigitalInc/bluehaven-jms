<?php

function contr_search()
{
	$acclist=explode(",",$_SESSION['aid']);
    $yr_ar=array();
    
	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
    
    $qry2 = "SELECT distinct(datepart(yyyy,added)) as yradded FROM jobs WHERE officeid='".$_SESSION['officeid']."' order by yradded DESC;";
	$res2 = mssql_query($qry2);
    
    while ($row2 = mssql_fetch_array($res2))
    {
        $yr_ar[]=$row2['yradded'];
    }

	echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
	echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	//echo "   <tr>\n";
	//echo "					<td>\n";
	//echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr class=\"tblhd\">\n";
	echo "								<td align=\"left\"><b>Contract Search Tool</b></td>\n";
	echo "      							<td align=\"right\">\n";

	HelpNode('ContractsSearch',1);

	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td colspan=\"2\" valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                  <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Data Field</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Input Parameter</b></td>\n";
    echo "                              	<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Renov Only</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Sort by</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Order by</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b></b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\">\n";
	echo "												<select name=\"subq\">\n";
	echo "                                 		<option value=\"lname\">Customer Last Name</option>\n";
	//echo "                                 		<option value=\"cnum\">Contract #</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"bboxl\" name=\"sval\" size=\"20\" title=\"Enter Full/Partial Customer Name or Contract Number in this Field\"></td>\n";
    echo "                              	<td align=\"center\" valign=\"bottom\">\n";
    /*
	echo "												<select name=\"yradded\">\n";
    
    foreach ($yr_ar as $ny=>$vy)
    {
        echo "                                 		        <option value=\"yradded\">".$vy."</option>\n";
    }
    
	echo "												</select>\n";
    */
	echo "											</td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\">\n";
	echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this box to Show only Renovations\">\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"J1.jobid\" SELECTED>Contract #</option>\n";
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
	echo "                                 <td align=\"left\" valign=\"bottom\"><button class=\"btnsysmenu\">Search</button></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "                                 <td align=\"right\" valign=\"top\">\n";
	echo "                                    <select name=\"ctrinsdate\">\n";
	echo "                                 		<option value=\"J2.contractdate\">Contract Date</option>\n";
	echo "                                 		<option value=\"J1.added\">Insert Date</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" id=\"d1\" size=\"20\" title=\"Begin Date\"><br>";
	echo "                                 				<input class=\"bboxl\" type=\"text\" name=\"d2\" id=\"d2\" size=\"20\" title=\"End Date\">\n";
	echo "											</td>\n";
	echo "         			</form>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "                                 <td align=\"center\" colspan=\"7\"><hr width=\"90%\"</td>\n";
	echo "				</tr>\n";

	if ($_SESSION['clev'] >= 5)
	{
		echo "										<tr>\n";
		echo "         								<form method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
		echo "                              	<td align=\"right\" valign=\"bottom\"><b>Salesman</b></td>\n";
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
        echo "                              	<td align=\"center\" valign=\"bottom\"><b>Year</b>\n";
        echo "												<select name=\"yradded\">\n";
        
        foreach ($yr_ar as $ny=>$vy)
        {
            echo "                                 		        <option value=\"".$vy."\">".$vy."</option>\n";
        }
        
        echo "												</select>\n";
        echo "											</td>\n";
		echo "                                 <td align=\"center\" valign=\"bottom\">\n";
		echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this box to Show only Renovations\">\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "                                 		<option value=\"J1.jobid\" SELECTED>Contract #</option>\n";
		echo "                                 		<option value=\"J1.added\">Insert Date</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"ascdesc\">\n";
		echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
		echo "                                 		<option value=\"DESC\">Descending</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\"><button class=\"btnsysmenu\">Search</button></td>\n";
		echo "         								</form>\n";
		echo "										</tr>\n";
	}

	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</div>\n";
}

function list_jobs()
{
	$officeid=$_SESSION['officeid'];
	$securityid=$_SESSION['securityid'];
	$acclist=explode(",",$_SESSION['aid']);
	$brdr=0;

	if (isset($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	else
	{
		$order="estid";
	}

	if (isset($_REQUEST['ascdesc']))
	{
		$dir=$_REQUEST['ascdesc'];
	}
	else
	{
		$dir="ASC";
	}

	if ($_REQUEST['call']=="search_results")
	{
		if ($_REQUEST['subq']=="lname")
		{
			if (empty($_REQUEST['d1']) && isset($_REQUEST['d2']) )
			{
				if (empty($_REQUEST['sval']))
				{
					echo "<b><font color=\"red\">Error!</font> Search String or Date Parameter required.</b>";
					exit;
				}
			}

			/*
			$qry    = "SELECT *,b.jcost,b.jprof FROM [cinfo] AS a ";
			$qry   .= "INNER JOIN [jobs] AS b ";
			$qry   .= "ON a.jobid=b.jobid ";
			//$qry   .= "ON a.estid=b.estid ";
			$qry   .= "WHERE a.officeid='".$_SESSION['officeid']."' ";
			$qry   .= "AND b.officeid='".$_SESSION['officeid']."' ";
			$qry   .= "AND a.jobid!='0' ";
			$qry   .= "AND a.njobid='0' ";
			$qry   .= "AND b.jadd='0' ";
			$qry   .= "AND a.clname LIKE '".$_REQUEST['sval']."%' ";

			if (!empty($_REQUEST['d1']) && isset($_REQUEST['d2']) )
			{
				$qry   .= " AND b.added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59'  ";
			}

			$qry   .= "ORDER BY ".$order." ".$dir.";";
			*/
			
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
			$qry   .= "AND J1.jobid!='0' ";
			$qry   .= "AND J1.njobid='0' ";
			$qry   .= "AND C.clname LIKE '".$_REQUEST['sval']."%'  ";

			if (!empty($_REQUEST['d1']) && isset($_REQUEST['d2']) )
			{
				$qry   .= " AND ".$_REQUEST['ctrinsdate']." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59'  ";
			}
			
			if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
			{
				$qry   .="AND J1.renov = '1'  ";
			}

			$qry   .= "ORDER BY J1.renov,".$_REQUEST['order']." ".$_REQUEST['ascdesc'].";";
		}
		elseif ($_REQUEST['subq']=="salesman")
		{
            if ($_SESSION['securityid']==26)
            {
                echo "SalesMan<br>";
            }
            
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
			$qry   .= "AND J1.jobid!='0' ";
			$qry   .= "AND J1.njobid='0' ";
			$qry   .= "AND J1.securityid='".$_REQUEST['assigned']."' ";
			
			if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
			{
				$qry   .="AND J1.renov = '".$_REQUEST['renov']."' ";
			}
            
            if (isset($_REQUEST['yradded']) && $_REQUEST['yradded']!=0)
			{
				$qry   .="AND datepart(yyyy,J1.added) = ".$_REQUEST['yradded']." ";
			}
			
			$qry   .= "ORDER BY J1.renov,".$_REQUEST['order']." ".$_REQUEST['ascdesc'].";";
		}
	}

    if ($_SESSION['securityid']==26999999999999999999999999999999999999999999999)
    {
        echo $qry."<br>";
    }
    
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	if ($nrows < 1)
	{
		echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
		echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\">\n";
		echo "         <h4>Contract Search did not produce any results.</h4>\n";
		echo "      </td>\n";
		echo "      <td align=\"right\">\n";

		HelpNode('ContractsSearchResults',1);

		echo "		</td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}
	else
	{
		echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
		echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "	<tr>\n";
		echo "					<td align=\"left\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "					<td align=\"right\">\n";

		HelpNode('ContractsSearchResults',1);

		echo "					</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
		echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "                  <tr>\n";
		echo "                     <td class=\"tblhd\" align=\"center\"></td>\n";
		echo "                     <td class=\"tblhd\" align=\"center\"><b>Contract ID</b></td>\n";
		echo "                     <td class=\"tblhd\" align=\"center\"><b>Addn</b></td>\n";
		echo "                     <td class=\"tblhd\" align=\"center\"><b>Renov</b></td>\n";
		echo "                     <td class=\"tblhd\" align=\"left\"><b>Customer</b></td>\n";
		echo "                     <td class=\"tblhd\" align=\"left\"><b>Phone</b></td>\n";
		echo "                     <td class=\"tblhd\" align=\"right\"><b>Contract Total</b></td>\n";

		if ($_SESSION['clev'] >= 5)
		{
			echo "                     <td class=\"tblhd\" align=\"right\"><b>Total Cost</b></td>\n";
			echo "                     <td class=\"tblhd\" align=\"right\"><b>Net Prof</b></td>\n";
			//echo "                     <td class=\"tblhd\" align=\"right\"><b>Net %</b></td>\n";
		}
		
		echo "                     <td class=\"tblhd\" align=\"left\"><b>SalesRep</b></td>\n";
		echo "                     <td class=\"tblhd\" align=\"center\"><b>Contract Date</b></td>\n";
		echo "                     <td class=\"tblhd\" align=\"center\"><b>Insert Date</b></td>\n";
		echo "                     <td class=\"tblhd\" align=\"center\"><b></b></td>\n";
		echo "                     <td class=\"tblhd\" align=\"center\"></td>\n";
		echo "                  </tr>\n";

		$tcon=0;
		$xi = 0;
		while($row=mssql_fetch_array($res))
		{
			$qryA = "SELECT jobid,status,custid,securityid,renov FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND estid='".$row['estid']."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);
			$nrowA = mssql_num_rows($resA);

			$qryB = "SELECT cfname,clname,chome,mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$rowA['jobid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			
			/*if ($_SESSION['securityid']==26)
			{
				echo $qryB.'<br>';
			}*/

			$qryC = "SELECT fname,lname,slevel FROM security WHERE securityid='".$rowA['securityid']."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			$secl=explode(",",$rowC['slevel']);

			if ($secl[6]==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}

			$qryDpre = "SELECT contractamt,added,updated,contractdate FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."' AND jadd='0';";
			$resDpre = mssql_query($qryDpre);
			$rowDpre = mssql_fetch_array($resDpre);

			$ctramt=$rowDpre['contractamt'];

			$qryD = "SELECT jobid,contractamt FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."' AND jadd!='0';";
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_array($resD);
			$nrowD = mssql_num_rows($resD);

			if ($nrowD >= 1)
			{
				$qryDa = "SELECT raddnpr_man FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."' AND jadd!='0';";
				$resDa = mssql_query($qryDa);

				$jaddamt=0;
				while ($rowDa = mssql_fetch_array($resDa))
				{
					$jaddamt=$jaddamt+$rowDa['raddnpr_man'];
				}
			}
			else
			{
				$jaddamt=0;
			}

			$qryF = "SELECT MAX(jadd) as mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."';";
			$resF = mssql_query($qryF);
			$rowF = mssql_fetch_array($resF);

			$uid  =md5(session_id().time().$rowA['custid']).".".$_SESSION['securityid'];

			if (in_array($rowA['securityid'],$acclist)||$_SESSION['jlev'] >= 6)
			{
				$xi++;
				
				if ($xi%2)
				{
					$tbg	= "ltgray_und";
				}
				else
				{
					$tbg	= "wh_und";
				}
				
				$tctramt=$ctramt+$jaddamt;
				$ftctramt=number_format($tctramt, 2, '.', ',');
				$ftcstamt=number_format($row['jcost'], 2, '.', ',');
				$ftprfamt=number_format($row['jprof'], 2, '.', ',');
				$tcon=$tcon+$tctramt;

				if (isset($rowDpre['contractdate']))
				{
					$odate = date("m-d-Y", strtotime($rowDpre['contractdate']));
				}
				else
				{
					$odate = "";
				}

				if (isset($row['updated']))
				{
					$udate = date("m-d-Y", strtotime($row['updated']));
				}
				else
				{
					$udate = "";
				}

				if (isset($row['submitted']))
				{
					$sdate = date("m-d-Y", strtotime($row['submitted']));
				}
				else
				{
					$sdate = "";
				}

				if (isset($row['added']))
				{
					$idate = date("m-d-Y", strtotime($row['added']));
				}
				else
				{
					$idate = "";
				}

				echo "                  <tr>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['jobid']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">\n";

				if ($nrowD >= 1)
				{
					echo "<b>".$nrowD."</b>";
				}

				echo "							</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">\n";

				if ($rowA['renov'] == 1)
				{
					echo "<b>R</b>";
				}

				echo "							</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$rowB['clname']."</b>, ".$rowB['cfname']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\">".$rowB['chome']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$ftctramt."</td>\n";

				if ($_SESSION['clev'] >= 5)
				{
					echo "                     <td class=\"".$tbg."\" align=\"right\">".$ftcstamt."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">".$ftprfamt."</td>\n";
				}

				echo "                     <td class=\"".$tbg."\" align=\"left\"><font class=\"".$fstyle."\">".$rowC['lname'].", ".$rowC['fname']."</font></td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">".$odate."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">".$idate."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
				echo "                        <form method=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$row['jobid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				echo "				 <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Contract\">\n";
				echo "                        </form>\n";
				echo "                     </td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi."</td>\n";
				echo "                  </tr>\n";
			}
		}

		$ftcon        =number_format($tcon, 2, '.', ',');
		echo "                  <tr>\n";
		echo "                     <td align=\"right\" colspan=\"6\"><b>Total Contracts</b></td>\n";
		echo "                     <td align=\"right\"><b>".$ftcon."</b></td>\n";
		echo "                     <td align=\"left\" colspan=\"7\"></td>\n";
		echo "                  </tr>\n";
		echo "                  </table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </div>\n";
	}
}

function view_job_retail()
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	
	unset($_SESSION['viewarray']);

	$jobid		=$_REQUEST['jobid'];
	$securityid	=$_SESSION['securityid'];
	$officeid	=$_SESSION['officeid'];
	$fname		=$_SESSION['fname'];
	$lname		=$_SESSION['lname'];

	if (!isset($jobid)||$jobid=='0'||$jobid=='')
	{
		echo "Fatal Error: Job ID (".$jobid.") not set!";
		exit;
	}

	if ($_REQUEST['call']=="view_retail"||$_REQUEST['call']=="post_save_add")
	{
		$jaddn	=$_REQUEST['jadd'];
	}
	elseif ($_REQUEST['call']=="delete_job2")
	{
		if ($_REQUEST['jadd']!=0)
		{
			$jaddn=$_REQUEST['jadd'] - 1;
		}
		else
		{
			$jaddn	=0;	
		}
	}
	else
	{
		$jaddn	=0;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid=".$_SESSION['officeid']." AND jobid='".$jobid."' AND jadd='".$jaddn."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);
	
	//echo $qrypreA.'<br>';

	$qrypreAa = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='0';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreAb = "SELECT jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreAb = mssql_query($qrypreAb);
	$nrowpreAb= mssql_num_rows($respreAb);
	
	$qrypreAc = "SELECT MAX(jadd) as mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreAc = mssql_query($qrypreAc);
	$rowpreAc = mssql_fetch_array($respreAc);
	
	$qrypreB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);
	
	$qrypreBa = "SELECT estid,added FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$rowpreB ['estid']."';";
	$respreBa = mssql_query($qrypreBa);
	$rowpreBa = mssql_fetch_array($respreBa);
	
	$qrypreC = "SELECT securityid,filestoreaccess FROM security WHERE securityid='".$securityid."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_array($respreC);

	$_SESSION['aid']=aidbuilder($_SESSION['jlev'],"j");
	$acclist	= explode(",",$_SESSION['aid']);
	$uid		= md5(session_id().time().$rowpreB['custid']).".".$_SESSION['securityid'];

	//print_r($acclist);
	if (!in_array($rowpreB['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Job</b>";
		exit;
	}

	//print_r($rowpreB);

	$viewarray=array(
	'ps1'=>		$rowpreA['pft'],
	'ps2'=>		$rowpreA['sqft'],
	'spa1'=>	$rowpreA['spa_pft'],
	'spa2'=>	$rowpreA['spa_sqft'],
	'spa3'=>	$rowpreA['spa_type'],
	'tzone'=>	$rowpreA['tzone'],
	'camt'=>	$rowpreA['contractamt'],
	'condate'=>	strtotime($rowpreAa['contractdate']),
	'status'=>	$rowpreB['status'],
	'ps5'=>		$rowpreA['shal'],
	'ps6'=>		$rowpreA['mid'],
	'ps7'=>		$rowpreA['deep'],
	'cid'=>		$rowpreB['custid'],
	'estsecid'=>$rowpreB['securityid'],
	'deck'=>	$rowpreA['deck'],
	'erun'=>	$rowpreA['erun'],
	'prun'=>	$rowpreA['prun'],
	'jobid'=>	$rowpreB['jobid'],
	'sidm'=>	$rowpreB['sidm'],
	'tax'=>		$rowpreB['tax'],
	'taxrate'=>	$rowpreB['taxrate'],
	'applyov'=>	$rowpreB['applyov'],
	'comadj'=>	$rowpreB['ovcommission'],
	'refto'=>	$rowpreA['refto'],
	'ps1a'=>	$rowpreA['apft'],
	'bpprice'=>	$rowpreA['bpprice'],
	'bpcomm'=>	$rowpreA['bpcomm'],
	'tax'=>		$rowpreB['tax'],
	'taxrate'=>	$rowpreB['taxrate'],
	'addnpr'=>	$rowpreA['raddnpr_man'],
	'addncm'=>	$rowpreA['raddncm_man'],
	'royadj'=>	$rowpreA['raddnroy_man'],
	'custallow'=>0,
	'added'=>	strtotime($rowpreBa['added']),
	'cdate'=>	strtotime($rowpreBa['added']),
	'estid'=>	$rowpreB['estid'],
	'njobid'=>	$rowpreB['njobid'],
	'jobid'=>	$rowpreB['jobid'],
	'jadd'=>	$rowpreA['jadd'],
	'mjadd'=>	$rowpreAc['mjadd'],
	'renov'=>	$rowpreB['renov'],
	'acc_status'=>$rowpreB['acc_status'],
	'allowdel'=>0
	);

	$r_estdata = $rowpreA['estdata'];

	$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft,encost,encon,newcommdate FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	if ($rowC['encon']!=1)
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>Contracts have been disabled in ".$rowC[1]."</b>";
		exit;
	}

	if ($rowC['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryD = "SELECT securityid,fname,lname,newcommdate FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT cid,custid,estid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);
	
	//echo $qryF.'<br>';

	$viewarray['cid']	=$rowF['cid'];
	//echo $viewarray['cid'].'<br>';
	
	$viewarray['ncommdate']	=strtotime($rowD['newcommdate']);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$viewarray['cid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_array($resL);

	$qryN = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resN = mssql_query($qryN);
	$rowN = mssql_fetch_array($resN);

	$qryP = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resP = mssql_query($qryP);
	$rowP = mssql_fetch_array($resP);

	if ($viewarray['jadd'] >= 1)
	{
		$tjaddpr	=0;
		$qryO 	= "SELECT jadd,raddnpr_man FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd!='0';";
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

	// Sets Tax Rate
	if ($rowC['stax']==1)
	{
		if (!empty($viewarray['taxrate']) and $viewarray['taxrate']!="0.00")
		{
			$taxrate=array(0=>$viewarray['tax'],1=>$viewarray['taxrate']);
		}
		else
		{
			$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
			$resJ = mssql_query($qryJ);
			$rowJ = mssql_fetch_array($resJ);

			$taxrate=array(0=>$viewarray['camt']*$rowJ['taxrate'],1=>$rowJ['taxrate']);
		}

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}
	
	$qryQ  = "select csid,rate,cbtype from jest..CommissionSchedule where jobid='".$viewarray['jobid']."' and cbtype=1;";
	$resQ  = mssql_query($qryQ);
	$rowQ  = mssql_fetch_array($resQ);
    $nrowQ = mssql_num_rows($resQ);
	
	if ($nrowQ > 0)
	{
		$viewarray['com_base_rate']=($rowQ['rate'] * .01);
	}

	$sdate			=date("m/d/Y", strtotime($rowpreAa['added']));
	$cdate 			=date("m/d/Y", strtotime($viewarray['cdate']));
	$poolcomm_adj	=detect_package($r_estdata);
	$set_deck   	=deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    	=round($set_deck[0]);
	$set_ia     	=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals		=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$tbullets   	=0;
	$estidret		=$jobid;
	$vdiscnt		=$viewarray['camt'];
	$pbaseprice		=$viewarray['bpprice'];
	$bquan			=$defmeas;

	if ($poolcomm_adj >= 1)
	{
		$bcomm      =0;
	}
	else
	{
		if (isset($viewarray['com_base_rate']) && $viewarray['com_base_rate']!=0)
		{
			$bcomm	=$viewarray['bpprice'] * $viewarray['com_base_rate'];
		}
		else
		{
			$bcomm	=$viewarray['bpcomm'];
		}
	}

	$fpbaseprice	=number_format($pbaseprice, 2, '.', '');
	$fbcomm			=number_format($bcomm, 2, '.', '');
	$ctramt			=$viewarray['camt']+$tjaddpr;
	$fctramt		=number_format($ctramt, 2, '.', '');

	if ($rowI[10]==1)
	{
		$tbg		="magenta";
	}
	else
	{
		$tbg		="gray";
	}

	$qryV = "SELECT jadd,raddnpr,raddncm,raddnpr_man,raddncm_man,taxrate,add_type,post_add FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd!=0;";
	$resV = mssql_query($qryV);
	$nrowV= mssql_num_rows($resV);

	$qryX = "SELECT jadd,raddnpr,raddncm,raddnpr_man,raddncm_man,taxrate,add_type,post_add FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd!=0;";
	$resX = mssql_query($qryX);
	$nrowX= mssql_num_rows($resX);

	$brdr=0;
	
	$_SESSION['viewarray']=$viewarray;

	echo "<script type=\"text/javascript\" src=\"js/jquery_contract_func.js\"></script>\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table class=\"transnb\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\">\n";
	echo "			<table class=\"transnb\" width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" colspan=\"3\">\n";

	//info_display_job($tbg,$offid,$jobid,$jadd,$sfname,$slname,$mfname,$mlname,$ver,$typ,$secid,$njobid)
	info_display_job($tbg,$rowC['name'],$estidret,$viewarray['jadd'],$rowD['fname'],$rowD['lname'],$rowL['fname'],$rowL['lname'],"Retail","Contract",$viewarray['estsecid'],'0',$viewarray['jobid']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	//echo 'CID '.$viewarray['cid'];
	cinfo_display_job($_SESSION['officeid'],$viewarray['cid'],$rowC['stax']);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"33%\">\n";

	pool_detail_display_job($viewarray['jobid'],$viewarray['jadd']);

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
	echo "         <table class=\"outer\" cellpadding=0 cellspacing=0 width=\"100%\" bordercolor=\"gray\" border=\"1\">\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"left\" width=\"100\"><b>Category</b></td>\n";
	echo "              <td class=\"wh\" align=\"left\"><b>Item</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"30\"><b>Quan.</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"30\"><b>Units</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"60\"><b>Retail</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"60\"><b>Comm</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"left\" width=\"100\">Base</td>\n";
	echo "              <td class=\"wh\" valign=\"top\" align=\"left\"><b>Basic Pool</b></td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"30\">".$bquan."</td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"30\">";

	if ($rowC['pft_sqft']=="p")
	{
		echo "pft";
	}
	else
	{
		echo "sqft";
	}

	echo "					</td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"60\">".$fpbaseprice."</td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"60\">".$fbcomm."</td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";

	//echo "D: ".$rowpreA['estdata']."<BR>";
	calcbyacc($rowpreA['estdata'],$rowpreA['filters']);

	// Totals Table Calcs
	$bccost		=$bctotal;
	$rccost		=$rctotal;
	$cccost		=$cctotal;
	$bmcost		=$bmtotal;
	$rmcost		=$rmtotal;
	$trccost	=$rccost+$rmcost;
	$cmcost		=$cmtotal;
	$tbcost		=$bccost+$bmcost;
	$trcost		=$pbaseprice+$trccost+$tbid;
	$tccost		=$cccost+$cmcost;
	$trcomm		=$bcomm+$tccost;
	$prof		=($trcost-$tbcost)-$trcomm;
	$perprof	=($trcost!=0)?$prof/$trcost:0;

	if ($rowC['stax']==1)
	{
		$rtax		=$ctramt*$taxrate[1];
		$grtcost	=$ctramt+$rtax;
		$frtax		=number_format($rtax, 2, '.', '');
		$fgrtcost	=number_format($grtcost, 2, '.', '');
	}

	$fbccost		=number_format($bccost, 2, '.', '');
	$fbmcost		=number_format($bmcost, 2, '.', '');
	$fcccost		=number_format($cccost, 2, '.', '');
	$frccost		=number_format($rccost, 2, '.', '');
	$frmcost		=number_format($rmcost, 2, '.', '');
	$fcmcost		=number_format($cmcost, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftrcost		=number_format($trcost, 2, '.', '');
	$ftccost		=number_format($tccost, 2, '.', '');
	$ftrcomm		=number_format($trcomm, 2, '.', '');

	echo "           <tr>\n";
	echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Price per Book</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">".$ftrcost."</td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"60\">".$ftrcomm."</td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"60\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";

	calc_adjusts();

	if (!isset($rowC['bullet_rate'])||!is_numeric($rowC['bullet_rate']))
	{
		//echo "OBR: ". $rowC[5]."<br>";
		$bullet_rate=0;
	}
	else
	{
		$bullet_rate=$rowC['bullet_rate'];
	}

	$adjbookamt	=$trcost+$discount;
	$fadjbookamt=number_format($adjbookamt, 2, '.', '');
	$adjctramt	=$ctramt-$adjbookamt;
	$fadjctramt	=number_format($adjctramt, 2, '.', '');
	$adjcomm	=0;
	$ou_out		=calc_ou($adjctramt,$adjcomm,$tbullets,$rowC['bullet_cnt'],$viewarray['applyov'],$viewarray['comadj'],$bullet_rate,$rowC['over_split']);
	$tadjcomm	=$trcomm;
	$foucomm	=number_format($ou_out[0], 2, '.', '');
	$fadjcomm	=number_format($ou_out[1], 2, '.', '');
	$ftadjcomm	=number_format($tadjcomm, 2, '.', '');

	echo "           <tr>\n";
	echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Adjusted Book Price</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">".$fadjbookamt."</td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Retail Contract Price</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>".$fctramt."</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "              </td>\n";
	echo "           </tr>\n";

	//Over/Under Split Percentage
	$osplitperc=0;
	
	if (isset($fctramt) && $fctramt!=0)
	{
		$osplitperc=round(($fadjctramt/$fctramt)*100);
	}
		
	echo "           <tr>\n";
	echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Overage/Underage</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";

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

	echo "		</td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";

	if ($adjctramt < 0)
	{
		echo "              <td class=\"wh\" align=\"right\"><font color=\"red\">".$fadjctramt."</font></td>\n";
	}
	else
	{
		echo "              <td class=\"wh\" align=\"right\">".$fadjctramt."</td>\n";
	}

	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";

	$viewarray['overunder']=$fadjctramt;

	if ($rowC['stax']==1)
	{
		$ftaxrate=number_format($taxrate[1], 3, '.', '');
		echo "            <tr>\n";
		echo "               <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Tax:</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\">".$ftaxrate."</td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td class=\"wh\" align=\"right\">".$frtax."</td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Total:</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n"; 
		echo "               <td class=\"wh\" align=\"right\"><b>".$fgrtcost."</b></td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "            </tr>\n";
	}

	//if ($_SESSION['officeid']!=55)
	if ($viewarray['added'] < $viewarray['ncommdate'])
	{
		if ($viewarray['applyov']==1)
		{
			$tadjcomm=$trcomm+$fadjcomm;
		}
		else
		{
			$tadjcomm=$trcomm;
		}
	
		$ftadjcomm	=number_format($tadjcomm, 2, '.', '');
	
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Commission Adjust</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($tbullets > 0)
		{
			echo $tbullets." Bullets";
		}
	
		echo "				</td>\n";
	
		if ($_SESSION['clev'] >= 4)
		{
			echo "<form method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			echo "<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
			echo "<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
			echo "<input type=\"hidden\" name=\"comm\" value=\"".$ftrcomm."\">\n";
	
			if ($viewarray['applyov']==1)
			{
				echo "              <td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" name=\"comadj\" value=\"".$fadjcomm."\" size=\"7\"></td>\n";
			}
			else
			{
				echo "              <td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" name=\"comadj\" value=\"".$foucomm."\" size=\"7\"></td>\n";
			}
	
			echo "              <td class=\"wh\" align=\"center\">\n";
			echo "					<div class=\"noPrint\">\n";
	
			if ($rowF['njobid']!=0)
			{
				if ($nrowV==0 && $_REQUEST['jadd']==0)
				{
					if ($viewarray['applyov']==1)
					{
						echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteov\">\n";
						echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
						echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Delete\" DISABLED>\n";
					}
					else
					{
						echo "                  <input type=\"hidden\" name=\"call\" value=\"applyov\">\n";
						echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
						echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
					}
				}
			}
			else
			{
				if ($nrowV==0 && $_REQUEST['jadd']==0)
				{
					if ($viewarray['applyov']==1)
					{
						echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteov\">\n";
						echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
						echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Delete\">\n";
					}
					else
					{
						echo "                  <input type=\"hidden\" name=\"call\" value=\"applyov\">\n";
						echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
						echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\">\n";
					}
				}
			}
	
			echo "					</div>\n";
			echo "				</td>\n";
			echo "</form>\n";
		}
		else
		{
			if ($viewarray['applyov']==1)
			{
				if ($fadjcomm < 0)
				{
					echo "              <td align=\"right\"><font color=\"red\">".$fadjcomm."</font></td>\n";
				}
				else
				{
					echo "              <td align=\"right\">".$fadjcomm."</td>\n";
				}
			}
			else 
			{
				echo "              <td align=\"center\"></td>\n";
			}
	
			echo "              <td align=\"center\"></td>\n";
		}
	
	
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Total Commission:</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
	
		if ($ftadjcomm < 0)
		{
			echo "              <td class=\"wh\" align=\"right\"><font color=\"red\">".$ftadjcomm."</font></td>\n";
		}
		else
		{
			echo "              <td class=\"wh\" align=\"right\">".$ftadjcomm."</td>\n";
		}
	
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
	}
	else
	{
		CommissionScheduleRW_Cont($viewarray);
	}
	
	//echo "        </table>\n";
	// End Original Contract Display
	// Addendum Display, if any
	$traddnpr		=0;
	$traddncm		=0;
	$traddnpr_man	=0;
	$traddncm_man	=0;
	$traddnpr_subman=0;
	$traddncm_subman=0;
	$prevctramt		=0;
	$prevcmamt		=0;
    
    //echo $nrowV."\n";
    //echo $_REQUEST['jadd']."\n";

	if ($nrowV > 0 && $_REQUEST['jadd']==0)
	{
		echo "           <tr>\n";
		echo "              <td colspan=\"7\" class=\"gray\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\"align=\"left\"><b>Contract Adjustments</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\" width=\"60\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\" width=\"60\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" valign=\"bottom\" width=\"60\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";

		while ($rowV = mssql_fetch_array($resV))
		{
			if ($rowV['jadd'] >=1)
			{				
				if ($rowC['stax']==1)
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
					$fVaddtrate		=number_format($Vaddtrate, 3, '.', '');
					$fraddntr_subman=number_format($rowV['raddnpr_man']*$Vaddtrate, 2, '.', '');
				}
				
				if ($rowV['add_type']==0)
				{
					$add_type="Customer Addendum";
				}
				else
				{
					$add_type="GM Adjust";
				}
				
				$fraddnpr_subman	=number_format($rowV['raddnpr_man'], 2, '.', '');
				
				if ($viewarray['added'] < $viewarray['ncommdate'])
				{
                    //echo 'BEFORE';
					$fraddncm_subman	=number_format($rowV['raddncm_man'], 2, '.', '');
				}
				else
				{
                    //echo 'AFTER';
					$qryVa = "SELECT amt FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' and jadd=".$rowV['jadd'].";";
					$resVa = mssql_query($qryVa);
					$rowVa = mssql_fetch_array($resVa);
					$fraddncm_subman	=number_format($rowVa['amt'], 2, '.', '');
                    
                    //echo $qryVa;
				}

				echo "<form method=\"post\">\n";
				echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
				echo "<input type=\"hidden\" name=\"call\" value=\"view_job_addendum_retail\">\n";
				echo "<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
				echo "<input type=\"hidden\" name=\"jadd\" value=\"".$rowV['jadd']."\">\n";
				echo "           <tr>\n";
				echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$add_type." # ".$rowV['jadd']." Total:</b></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";

				if ($fraddnpr_subman < 0)
				{
					echo "              <td class=\"wh\" align=\"right\"><font color=\"red\" width=\"60\">".$fraddnpr_subman."</font></td>\n";
				}
				else
				{
					echo "              <td class=\"wh\" align=\"right\">".$fraddnpr_subman."</td>\n";
				}

				echo "              <td class=\"wh\" align=\"right\">".$fraddncm_subman."</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				echo "					<div class=\"noPrint\">\n";
				echo "              		<input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
				echo "					</div>\n";
				echo "				</td>\n";
				echo "           </tr>\n";

				if ($rowC['stax']==1)
				{
					echo "           <tr>\n";
					echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$add_type." # ".$rowV['jadd']."  Tax:</b></td>\n";
					echo "              <td class=\"wh\" align=\"right\">".$fVaddtrate."</td>\n";
					echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";

					if ($fraddnpr_subman < 0)
					{
						echo "              <td class=\"wh\" align=\"right\"><font color=\"red\">".$fraddntr_subman."</font></td>\n";
					}
					else
					{
						echo "              <td class=\"wh\" align=\"right\">".$fraddntr_subman."</td>\n";
					}

					echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "           </tr>\n";
				}

				echo "</form>";
			}

			if ($rowC['stax']==1)
			{
				$traddnpr_subman		=$traddnpr_subman+($fraddnpr_subman+$fraddntr_subman);
			}
			else
			{
				$traddnpr_subman		=$traddnpr_subman+$fraddnpr_subman;
			}

			$traddncm_subman			=$traddncm_subman+$fraddncm_subman;
		}

		$sftraddnpr_subman			=number_format($traddnpr_subman, 2, '.', '');
		$sftraddncm_subman			=number_format($traddncm_subman, 2, '.', '');
		
		if ($viewarray['added'] < $viewarray['ncommdate'])
		{
		}
		else
		{
			$qryVz = "SELECT SUM(amt) as tamt FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' and jadd=0 and cbtype!=4;";
			$resVz = mssql_query($qryVz);
			$rowVz = mssql_fetch_array($resVz);
			$ftadjcomm	=number_format($rowVz['tamt'], 2, '.', '');
		}

		if ($rowC['stax']==1)
		{
			$prevctramt=$fgrtcost;
			$prevcmamt=$ftadjcomm;
		}
		else
		{
			$prevctramt=$ctramt;
			$prevcmamt=$ftadjcomm;
		}

		$ftraddnpr_man	=number_format(($prevctramt+$sftraddnpr_subman), 2, '.', '');
		$ftraddncm_man	=number_format(($prevcmamt+$sftraddncm_subman), 2, '.', '');

		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Addendum SubTotal:</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">".$sftraddnpr_subman."</td>\n";
		echo "              <td class=\"wh\" align=\"right\">".$sftraddncm_subman."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Revised Total:</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>".$ftraddnpr_man."</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>".$ftraddncm_man."</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
	}
	else
	{
		if ($viewarray['added'] < $viewarray['ncommdate'])
		{
		}
		else
		{
			$qryVz = "SELECT SUM(amt) as tamt FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' and jadd=0;";
			$resVz = mssql_query($qryVz);
			$rowVz = mssql_fetch_array($resVz);
			$ftadjcomm	=number_format($rowVz['tamt'], 2, '.', '');
		}
		
		if ($rowC['stax']==1)
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

	echo "         </table>\n";
	echo "         </td>\n";
	echo "      </td>\n";
	// End Retail Addendum Items

	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "			<table class=\"transnb\" cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td valign=\"top\">\n";
	echo "						<div class=\"noPrint\">\n";
	echo "						<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	echo "							<input type=\"hidden\" name=\"rcall\" value=\"view\">\n";
	echo "							<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	echo "							<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	echo "							<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "							<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "							<input type=\"hidden\" name=\"custid\" value=\"".$rowF['custid']."\">\n";
	echo "							<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"OneSheet\" title=\"View the Customer OneSheet\"><br>\n";
	echo "						</form>\n";
	echo "						</div>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	
	if ($viewarray['jadd'] >= 1)
	{
		echo "            <tr>\n";
		echo "               <td align=\"center\">\n";
		echo "					<div class=\"noPrint\">\n";
		echo "						<hr width=\"90%\">\n";
		echo "					</div>\n";
		echo "				</td>\n";
		echo "            </tr>\n";
		echo "                        <form method=\"POST\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "                           <input type=\"hidden\" name=\"call\" value=\"view_job_addendum_retail\">\n";
		echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "					<div class=\"noPrint\">\n";
		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Addn ".$viewarray['jadd']." Detail\">\n";
		echo "					</div>\n";
		echo "               <td>\n";
		echo "            </tr>\n";
		echo "                        </form>\n";
	}

	echo "			<tr>\n";
	echo "				<td align=\"center\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "						<hr width=\"90%\">\n";
	echo "					</div>\n";
	echo "				</td>\n";
	echo "			</tr>\n";

	$qrypreD = "SELECT MAX(jadd) AS mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);
	//$uid  =md5(session_id().time().$rowpreB['custid']).".".$_SESSION['securityid'];

	// Contract add_type
	echo "            <tr>\n";
	echo "               <td align=\"left\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "                        <form method=\"POST\">\n";
	echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "                           <input type=\"hidden\" name=\"call\" value=\"create_add\">\n";
	echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$rowpreB['estid']."\">\n";
	echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$rowpreD['mjadd']."\">\n";
	echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "                           <input type=\"hidden\" name=\"add_type\" value=\"0\">\n";

	if ($rowF['njobid']!=0 || $viewarray['applyov']!=1)
	{
		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Cust Addn\" DISABLED title=\"Use Customer Addendum to create an addendum that adjusts the Retail value of the Pool\">\n";
	}
	else
	{
		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Cust Addn\" title=\"Use Customer Addendum to create an addendum that adjusts the Retail value of the Pool\">\n";
	}
	
	echo "                        </form>\n";
	echo "					</div>\n";
	echo "               <td>\n";
	echo "            </tr>\n";

	if ($_SESSION['clev'] >= 6)
	{
		// GM add_type
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "					<div class=\"noPrint\">\n";
		echo "                        <form method=\"POST\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "                           <input type=\"hidden\" name=\"call\" value=\"create_add\">\n";
		echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$rowpreB['estid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$rowpreD['mjadd']."\">\n";
		echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
		echo "                           <input type=\"hidden\" name=\"add_type\" value=\"1\">\n";

		if ($rowF['njobid']!=0)
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"GM Adjust\" DISABLED title=\"Use Customer Addendum to create an addendum that adjusts the Cost of the Pool\">\n";
		}
		else
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"GM Adjust\" title=\"Use Customer Addendum to create an addendum that adjusts the Cost of the Pool\">\n";
		}
		
		echo "                        </form>\n";
		echo "					</div>\n";
		echo "               <td>\n";
		echo "            </tr>\n";
	}

	if ($_SESSION['clev'] >= 5)
	{
		if ($viewarray['mjadd']==0)
		{
			echo "            <tr>\n";
			echo "               <td align=\"left\">\n";
			echo "					<div class=\"noPrint\">\n";
			echo "                        <form method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"delete_job1\">\n";
			echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
	
			if ($viewarray['njobid']!='0')
			{
				echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Revert to Est\" title=\"This Contract has beed assigned a Job Number and cannot be Reverted to Estimate\" DISABLED>\n";
			}
			else
			{
				echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Revert to Est\" title=\"Revert this Contract to Estimate\">\n";
			}
	
			echo "                        </form>\n";
			echo "					</div>\n";
			echo "               <td>\n";
			echo "            </tr>\n";
		}
	}
	//elseif  ($viewarray['jadd'] >= 1)
	//{
	//	echo "                        <form method=\"POST\">\n";
	//	echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	//	echo "                           <input type=\"hidden\" name=\"call\" value=\"delete_job1\">\n";
	//	//echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$viewarray['estid']."\">\n";
	//	echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	//	echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	//	echo "            <tr>\n";
	//	echo "               <td align=\"left\">\n";
	//	echo "					<div class=\"noPrint\">\n";
	//
	//	if ($rowF['njobid']!=0)
	//	{
	//		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete Addn\" DISABLED>\n";
	//	}
	//	else
	//	{
	//		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete Addn\">\n";
	//	}
	//
	//	echo "					</div>\n";
	//	echo "               <td>\n";
	//	echo "            </tr>\n";
	//	echo "                        </form>\n";
	//}

	if ($_SESSION['jlev'] >= 5)
	{
		if ($rowF['njobid']!=0 or $viewarray['acc_status'] > 1)
		{
			//echo "Job Created";
		}
		else
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
			echo "                        <form method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"create_job\">\n";
			echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Create Job\" title=\"Assign Job Number to this Contract\">\n";
			echo "                        </form>\n";
			echo "					</div>\n";
			echo "				</td>\n";
			echo "            </tr>\n";
		}
	}

	if ($_SESSION['clev'] >= 6)
	{
		$viewarray['tcomm']=$ftraddncm_man;
		$viewarray['tretail']=$ftraddnpr_man;
		$viewarray['tcontract']=$ctramt;
		$viewarray['acctotal']=$trccost;
		$viewarray['discount']=$vdiscnt;
		
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
		echo "						<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
		echo "						<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "						<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "						<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
		echo "						<input type=\"hidden\" name=\"itgr\" value=\"1\">\n";
		
		if ($rowC[9]==1)
		{
			echo "                  	<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Cost\" title=\"Standard View. Displays addendum details as separate Phases.\">\n";
		}

		echo "						</form>\n";
		echo "					</div>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
	}

	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	
	//display_array($viewarray);
	
	$_SESSION['viewarray']=$viewarray;
}

function view_job_retail_OLD_071409()
{
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	
	unset($_SESSION['viewarray']);

	$jobid		=$_REQUEST['jobid'];
	$securityid	=$_SESSION['securityid'];
	$officeid	=$_SESSION['officeid'];
	$fname		=$_SESSION['fname'];
	$lname		=$_SESSION['lname'];

	if (!isset($jobid)||$jobid=='')
	{
		echo "Fatal Error: Job ID (".$jobid.") not set!";
		exit;
	}

	if ($_REQUEST['call']=="view_retail"||$_REQUEST['call']=="post_save_add")
	{
		$jaddn	=$_REQUEST['jadd'];
	}
	else
	{
		$jaddn	=0;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jaddn."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	//echo $qrypreA."<br>";

	$qrypreAa = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='0';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreAb = "SELECT jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreAb = mssql_query($qrypreAb);
	$nrowpreAb= mssql_num_rows($respreAb);
	
	$qrypreAc = "SELECT MAX(jadd) as mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreAc = mssql_query($qrypreAc);
	$rowpreAc = mssql_fetch_array($respreAc);
	
	$qrypreB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$acclist	= explode(",",$_SESSION['aid']);
	$uid		= md5(session_id().time().$rowpreB['custid']).".".$_SESSION['securityid'];

	if (!in_array($rowpreB['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Job</b>";
		exit;
	}

	//print_r($rowpreB);

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
	//'cid'=>	$rowpreB['custid'],
	'estsecid'=>$rowpreB['securityid'],
	'deck'=>	$rowpreA['deck'],
	'erun'=>	$rowpreA['erun'],
	'prun'=>	$rowpreA['prun'],
	'jobid'=>	$rowpreB['jobid'],
	'sidm'=>	$rowpreB['sidm'],
	'tax'=>		$rowpreB['tax'],
	'taxrate'=>	$rowpreB['taxrate'],
	'applyov'=>	$rowpreB['applyov'],
	'comadj'=>	$rowpreB['ovcommission'],
	'refto'=>	$rowpreA['refto'],
	'ps1a'=>	$rowpreA['apft'],
	'bpprice'=>	$rowpreA['bpprice'],
	'bpcomm'=>	$rowpreA['bpcomm'],
	'tax'=>		$rowpreB['tax'],
	'taxrate'=>	$rowpreB['taxrate'],
	'addnpr'=>	$rowpreA['raddnpr_man'],
	'addncm'=>	$rowpreA['raddncm_man'],
	'royadj'=>	$rowpreA['raddnroy_man'],
	'custallow'=>0,
	'jadd'=>	$rowpreA['jadd'],
	'mjadd'=>	$rowpreAc['mjadd'],
	'renov'=>	$rowpreB['renov'],
	'allowdel'=>0
	);

	$_SESSION['viewarray']=$viewarray;
	$r_estdata = $rowpreA['estdata'];

	$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft,encost,encon FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[10]!=1)
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>Contracts have been disabled in ".$rowC[1]."</b>";
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

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT cid,custid,estid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	$viewarray['cid']	=$rowF['cid'];

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$viewarray['custid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_array($resL);

	$qryN = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resN = mssql_query($qryN);
	$rowN = mssql_fetch_array($resN);

	$qryP = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resP = mssql_query($qryP);
	$rowP = mssql_fetch_array($resP);

	if ($viewarray['jadd'] >= 1)
	{
		$tjaddpr	=0;
		$qryO 	= "SELECT jadd,raddnpr_man FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd!='0';";
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

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		if (!empty($viewarray['taxrate']) && $viewarray['taxrate']!="0.00")
		//if ($viewarray['taxrate']=="0.00")
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

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}

	$sdate			=date("m-d-Y", strtotime($rowpreAa['added']));
	$cdate 			=date("m-d-Y", strtotime($viewarray['cdate']));
	$poolcomm_adj	=detect_package($r_estdata);
	$set_deck   	=deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    	=round($set_deck[0]);
	$set_ia     	=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals		=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$tbullets   	=0;
	$estidret		=$jobid;
	$vdiscnt		=$viewarray['camt'];
	$pbaseprice		=$viewarray['bpprice'];
	$bquan			=$defmeas;

	if ($poolcomm_adj >= 1)
	{
		$bcomm      =0;
	}
	else
	{
		$bcomm      =$viewarray['bpcomm'];
	}

	$fpbaseprice	=number_format($pbaseprice, 2, '.', '');
	$fbcomm			=number_format($bcomm, 2, '.', '');
	$ctramt			=$viewarray['camt']+$tjaddpr;
	$fctramt		=number_format($ctramt, 2, '.', '');

	if ($rowI[10]==1)
	{
		$tbg		="magenta";
	}
	else
	{
		$tbg		="gray";
	}

	$qryV = "SELECT jadd,raddnpr,raddncm,raddnpr_man,raddncm_man,taxrate,add_type FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd!=0;";
	$resV = mssql_query($qryV);
	$nrowV= mssql_num_rows($resV);

	$qryX = "SELECT jadd,raddnpr,raddncm,raddnpr_man,raddncm_man,taxrate,add_type FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd!=0;";
	$resX = mssql_query($qryX);
	$nrowX= mssql_num_rows($resX);

	$brdr=0;

	echo "<table width=\"100%\" border=".$brdr.">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\">\n";

	echo "			<table width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" colspan=\"3\">\n";

	info_display_job($tbg,$rowC[1],$estidret,$viewarray['jadd'],$rowD['fname'],$rowD['lname'],$rowL['fname'],$rowL['lname'],"Retail","Contract");

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	cinfo_display_job($_SESSION['officeid'],$viewarray['cid'],$rowC[2]);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"33%\">\n";

	pool_detail_display_job($viewarray['jobid'],$viewarray['jadd']);

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
	echo "      <td class=\"outer\" valign=\"top\" align=\"center\">\n";
	echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" width=\"100%\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"left\" width=\"100\"><b>Category</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Item</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b>Quan.</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b>Units</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"><b>Retail</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"><b>Comm</b></td>\n";
	echo "              <td NOWRAP class=\"lg\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\" width=\"100\">Base</td>\n";
	echo "              <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\"><b>Basic Pool</b></td>\n";
	echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">".$bquan."</td>\n";
	echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">";

	if ($rowC[8]=="p")
	{
		echo "pft";
	}
	else
	{
		echo "sqft";
	}

	echo "					</td>\n";
	echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"60\">".$fpbaseprice."</td>\n";
	echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"60\">".$fbcomm."</td>\n";
	echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";

	//echo "D: ".$rowpreA['estdata']."<BR>";
	calcbyacc($rowpreA['estdata'],$rowpreA['filters']);

	// Totals Table Calcs
	$bccost		=$bctotal;
	$rccost		=$rctotal;
	$cccost		=$cctotal;
	$bmcost		=$bmtotal;
	$rmcost		=$rmtotal;
	$trccost	=$rccost+$rmcost;
	$cmcost		=$cmtotal;
	$tbcost		=$bccost+$bmcost;
	$trcost		=$pbaseprice+$trccost+$tbid;
	$tccost		=$cccost+$cmcost;
	$trcomm		=$bcomm+$tccost;
	$prof		=($trcost-$tbcost)-$trcomm;
	$perprof	=$prof/$trcost;

	if ($rowC[2]==1)
	{
		$rtax		=$ctramt*$taxrate[1];
		$grtcost	=$ctramt+$rtax;
		$frtax		=number_format($rtax, 2, '.', '');
		$fgrtcost	=number_format($grtcost, 2, '.', '');
	}

	$fbccost		=number_format($bccost, 2, '.', '');
	$fbmcost		=number_format($bmcost, 2, '.', '');
	$fcccost		=number_format($cccost, 2, '.', '');
	$frccost		=number_format($rccost, 2, '.', '');
	$frmcost		=number_format($rmcost, 2, '.', '');
	$fcmcost		=number_format($cmcost, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftrcost		=number_format($trcost, 2, '.', '');
	$ftccost		=number_format($tccost, 2, '.', '');
	$ftrcomm		=number_format($trcomm, 2, '.', '');

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b> Pool Price per Book:</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftrcost."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$ftrcomm."</td>\n";
	echo "              <td NOWRAP class=\"lg\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";

	calc_adjusts();

	if (!isset($rowC[5])||!is_numeric($rowC[5]))
	{
		//echo "OBR: ". $rowC[5]."<br>";
		$bullet_rate=0;
	}
	else
	{
		$bullet_rate=$rowC[5];
	}

	$adjbookamt	=$trcost+$discount;
	$fadjbookamt=number_format($adjbookamt, 2, '.', '');
	
	if ($viewarray['renov']==1)
	{
		$adjctramt	=0;
	}
	else
	{
		$adjctramt	=$ctramt-$adjbookamt;
	}
	
	//$adjctramt	=$ctramt-$adjbookamt;
	$fadjctramt	=number_format($adjctramt, 2, '.', '');
	$adjcomm		=0;
	$ou_out		=calc_ou($adjctramt,$adjcomm,$tbullets,$rowC[6],$viewarray['applyov'],$viewarray['comadj'],$bullet_rate,$rowC[7]);
	$tadjcomm	=$trcomm;
	$foucomm		=number_format($ou_out[0], 2, '.', '');
	$fadjcomm	=number_format($ou_out[1], 2, '.', '');
	$ftadjcomm	=number_format($tadjcomm, 2, '.', '');

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Adjusted Book Price:</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fadjbookamt."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Retail Contract Price:</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fctramt."</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"lg\" align=\"center\">\n";
	echo "              </td>\n";
	echo "           </tr>\n";

	//Over/Under Split Percentage
	$osplitperc=0;
	if ($viewarray['renov']==1)
	{
		$osplitperc=0;
	}
	else
	{
		if (isset($fctramt) && $fctramt!=0)
		{
			$osplitperc=round(($fadjctramt/$fctramt)*100);
		}
	}
	/*
	if (isset($fctramt) && $fctramt!=0)
	{
		$osplitperc=round(($fadjctramt/$fctramt)*100);
	}
	*/

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Overage/Underage:</b></td>\n";
	//echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";

	if ($osplitperc < 0)
	{
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\">".$osplitperc."%</font></td>\n";
	}
	else
	{
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$osplitperc."%</td>\n";
	}

	//echo "		</td>\n";
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
	echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
	echo "           </tr>\n";

	if ($rowC[2]==1)
	{
		$ftaxrate=number_format($taxrate[1], 3, '.', '');
		echo "            <tr>\n";
		echo "               <td colspan=\"2\" class=\"wh\" align=\"right\"><b>Tax:</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftaxrate."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "               <td align=\"right\" class=\"wh\">".$frtax."</td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td class=\"lg\" align=\"right\"></td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td colspan=\"2\" class=\"wh\" align=\"right\"><b>Total:</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "               <td align=\"right\" class=\"wh\"><b>".$fgrtcost."</b></td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td class=\"lg\" align=\"right\"></td>\n";
		echo "            </tr>\n";
	}

	if ($viewarray['applyov']==1)
	{
		$tadjcomm=$trcomm+$fadjcomm;
	}
	else
	{
		$tadjcomm=$trcomm;
	}

	$ftadjcomm	=number_format($tadjcomm, 2, '.', '');

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Commission Adjust:</b></td>\n";
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
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
		echo "<input type=\"hidden\" name=\"comm\" value=\"".$ftrcomm."\">\n";

		if ($viewarray['applyov']==1)
		{
			echo "              <td NOWRAP class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" name=\"comadj\" value=\"".$fadjcomm."\" size=\"7\"></td>\n";
		}
		else
		{
			echo "              <td NOWRAP class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" name=\"comadj\" value=\"".$foucomm."\" size=\"7\"></td>\n";
		}

		echo "              <td NOWRAP class=\"lg\" align=\"center\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($rowF['njobid']!=0)
		{
			if ($nrowV==0 && $_REQUEST['jadd']==0)
			{
				if ($viewarray['applyov']==1)
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteov\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
					echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Delete\" DISABLED>\n";
				}
				else
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"applyov\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
					echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
				}
			}
		}
		else
		{
			if ($nrowV==0 && $_REQUEST['jadd']==0)
			{
				if ($viewarray['applyov']==1)
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteov\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
					echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Delete\">\n";
				}
				else
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"applyov\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
					echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\">\n";
				}
			}
		}


		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "                                        </td>\n";
		echo "</form>\n";
	}
	else
	{
		if ($viewarray['applyov']==1)
		{
			if ($fadjcomm < 0)
			{
				echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\">".$fadjcomm."</font></td>\n";
			}
			else
			{
				echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fadjcomm."</td>\n";
			}
		}
		else 
		{
			echo "              <td NOWRAP class=\"wh\" align=\"center\"></td>\n";
		}

		echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
	}


	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Total Commission:</b></td>\n";
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

	echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	//echo "        </table>\n";
	// End Original Contract Display
	// Addendum Display, if any
	$traddnpr		=0;
	$traddncm		=0;
	$traddnpr_man	=0;
	$traddncm_man	=0;
	$traddnpr_subman=0;
	$traddncm_subman=0;
	$prevctramt		=0;
	$prevcmamt		=0;

	if ($nrowV > 0 && $_REQUEST['jadd']==0)
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"7\" class=\"gray\" align=\"left\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"left\"><b>Contract Adjustments</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b></b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b></b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"><b></b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"><b></b></td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"center\" width=\"60\"></td>\n";
		echo "           </tr>\n";

		while ($rowV = mssql_fetch_array($resV))
		{
			if ($rowV['jadd'] >=1)
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
					$fVaddtrate		=number_format($Vaddtrate, 3, '.', '');
					$fraddntr_subman	=number_format($rowV['raddnpr_man']*$Vaddtrate, 2, '.', '');
				}
				
				if ($rowV['add_type']==0)
				{
					$add_type="Customer Addendum";
				}
				else
				{
					$add_type="GM Adjust";
				}

				$fraddnpr_subman	=number_format($rowV['raddnpr_man'], 2, '.', '');
				$fraddncm_subman	=number_format($rowV['raddncm_man'], 2, '.', '');

				echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
				echo "<input type=\"hidden\" name=\"call\" value=\"view_job_addendum_retail\">\n";
				echo "<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
				echo "<input type=\"hidden\" name=\"jadd\" value=\"".$rowV['jadd']."\">\n";
				echo "           <tr>\n";
				echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>".$add_type." # ".$rowV['jadd']." Total:</b></td>\n";
				echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
				echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";

				if ($fraddnpr_subman < 0)
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\" width=\"60\">".$fraddnpr_subman."</font></td>\n";
				}
				else
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$fraddnpr_subman."</td>\n";
				}

				echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$fraddncm_subman."</td>\n";
				echo "              <td NOWRAP class=\"lg\" align=\"center\" width=\"60\">\n";

				if ($_SESSION['subq']=="print")
				{
					echo "<div class=\"noPrint\">\n";
				}

				echo "              	<input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";

				if ($_SESSION['subq']=="print")
				{
					echo "</div>\n";
				}

				echo "					</td>\n";
				echo "           </tr>\n";

				if ($rowC[2]==1)
				{
					echo "           <tr>\n";
					echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>".$add_type." # ".$rowV['jadd']."  Tax:</b></td>\n";
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
					echo "              <td NOWRAP class=\"lg\" align=\"center\" width=\"60\"></td>\n";
					echo "           </tr>\n";
				}

				echo "</form>";
			}

			if ($rowC[2]==1)
			{
				$traddnpr_subman		=$traddnpr_subman+($fraddnpr_subman+$fraddntr_subman);
			}
			else
			{
				$traddnpr_subman		=$traddnpr_subman+$fraddnpr_subman;
			}

			$traddncm_subman			=$traddncm_subman+$fraddncm_subman;
		}

		$sftraddnpr_subman			=number_format($traddnpr_subman, 2, '.', '');
		$sftraddncm_subman			=number_format($traddncm_subman, 2, '.', '');

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

		$ftraddnpr_man	=number_format(($prevctramt+$sftraddnpr_subman), 2, '.', '');
		$ftraddncm_man	=number_format(($prevcmamt+$sftraddncm_subman), 2, '.', '');

		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Addendum SubTotal:</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b></b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b></b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$sftraddnpr_subman."</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">".$sftraddncm_subman."</td>\n";
		echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Revised Total:</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftraddnpr_man."</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftraddncm_man."</b></td>\n";
		echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
		echo "           </tr>\n";
	}
	else
	{
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

	echo "         </table>\n";
	echo "         </td>\n";
	echo "      </td>\n";
	// End Retail Addendum Items

	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";
	echo "            <tr>\n";
	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"print\">\n";
	echo "<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "               <td align=\"left\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Print View\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "               </td>\n";
	echo "</form>\n";
	echo "            </tr>\n";

	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	echo "         <input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
	echo "			<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	echo "			<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "         <input type=\"hidden\" name=\"custid\" value=\"".$rowF['custid']."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Comments\"><br>\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";
	
	if ($viewarray['jadd'] >= 1)
	{
		echo "            <tr>\n";
		echo "               <td align=\"center\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "						<hr width=\"90%\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>\n";
		echo "            </tr>\n";

		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "                           <input type=\"hidden\" name=\"call\" value=\"view_job_addendum_retail\">\n";
		echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Addn ".$viewarray['jadd']." Detail\">\n";
		echo "               <td>\n";
		echo "            </tr>\n";
		echo "                        </form>\n";
	}

	echo "            <tr>\n";
	echo "               <td align=\"center\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "						<hr width=\"90%\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "					</td>\n";
	echo "            </tr>\n";

	$qrypreD = "SELECT MAX(jadd) AS mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);
	//$uid  =md5(session_id().time().$rowpreB['custid']).".".$_SESSION['securityid'];

	// Contract add_type
	echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
	echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "                           <input type=\"hidden\" name=\"call\" value=\"create_add\">\n";
	echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$rowpreB['estid']."\">\n";
	echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$rowpreD['mjadd']."\">\n";
	echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "                           <input type=\"hidden\" name=\"add_type\" value=\"0\">\n";
	echo "            <tr>\n";
	echo "               <td align=\"left\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	if ($rowF['njobid']!=0 || $viewarray['applyov']!=1)
	{
		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Cust Addendum\" DISABLED>\n";
	}
	else
	{
		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Cust Addendum\">\n";
	}
	
	echo "									<font size=\"2\"><a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=hp&hpc=CA\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">?</a></font>\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "               <td>\n";
	echo "            </tr>\n";
	echo "                        </form>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		// GM add_type
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "                           <input type=\"hidden\" name=\"call\" value=\"create_add\">\n";
		echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$rowpreB['estid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$rowpreD['mjadd']."\">\n";
		echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
		echo "                           <input type=\"hidden\" name=\"add_type\" value=\"1\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($rowF['njobid']!=0)
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"GM Adjust\" DISABLED>\n";
		}
		else
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"GM Adjust\">\n";
		}
		
		echo "									<font size=\"2\"><a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=hp&hpc=GA\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">?</a></font>\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "               <td>\n";
		echo "            </tr>\n";
		echo "                        </form>\n";
	}

	if ($viewarray['jadd']==0)
	{
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "                           <input type=\"hidden\" name=\"call\" value=\"delete_job1\">\n";
		//echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$viewarray['estid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($rowF['njobid']!=0)
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Revert to Est\" DISABLED>\n";
		}
		else
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Revert to Est\">\n";
		}

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "               <td>\n";
		echo "            </tr>\n";
		echo "                        </form>\n";
	}
	elseif  ($viewarray['jadd'] >= 1)
	{
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "                           <input type=\"hidden\" name=\"call\" value=\"delete_job1\">\n";
		//echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$viewarray['estid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";

		if ($rowF['njobid']!=0)
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete Addn\" DISABLED>\n";
		}
		else
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete Addn\">\n";
		}

		echo "               <td>\n";
		echo "            </tr>\n";
		echo "                        </form>\n";
	}

	if ($_SESSION['clev'] >=5 && $_SESSION['jlev'] >=5)
	{
		echo "            <tr>\n";
		echo "               <td align=\"center\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "						<hr width=\"90%\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>\n";
		echo "            </tr>\n";


		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "                           <input type=\"hidden\" name=\"call\" value=\"create_job\">\n";
		echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($rowF['njobid']!=0)
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Save to Job\" DISABLED>\n";
		}
		else
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Save to Job\">\n";
		}

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>\n";
		echo "            </tr>\n";
		echo "                        </form>\n";
	}


	if ($_SESSION['clev'] >= 6)
	{
		echo "            <tr>\n";
		echo "               <td align=\"center\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "						<hr width=\"90%\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>\n";
		echo "            </tr>\n";
		
		$viewarray['tcomm']=$ftraddncm_man;
		$viewarray['tretail']=$ftraddnpr_man;
		$viewarray['tcontract']=$ctramt;
		$viewarray['acctotal']=$trccost;
		$viewarray['discount']=$vdiscnt;

		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
		echo "<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
		echo "<input type=\"hidden\" name=\"itgr\" value=\"1\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($rowC[9]==1)
		{
			echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Cost\" title=\"Standard View. Displays addendum details as separate Phases.\">\n";
		}

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "               </td>\n";
		echo "</form>\n";
		
		if ($rowpreAc['mjadd'] > 0)
		{
			echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
			echo "<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
			echo "<input type=\"hidden\" name=\"jadd\" value=\"".$rowpreAc['mjadd']."\">\n";
			echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
			//echo "<input type=\"hidden\" name=\"tcomm\" value=\"".$ftraddncm_man."\">\n";
			//echo "<input type=\"hidden\" name=\"tretail\" value=\"".$ftraddnpr_man."\">\n";
			//echo "<input type=\"hidden\" name=\"tcontract\" value=\"".$ctramt."\">\n";
			//echo "<input type=\"hidden\" name=\"discount\" value=\"".$vdiscnt."\">\n";
			echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
			//echo "<input type=\"hidden\" name=\"acctotal\" value=\"".$trccost."\">\n";
			echo "<input type=\"hidden\" name=\"itgr\" value=\"0\">\n";
			//echo "<input type=\"hidden\" name=\"showtotals\" value=\"1\">\n";
			echo "            <tr>\n";
			echo "               <td align=\"left\">\n";
	
			if ($_SESSION['subq']=="print")
			{
				echo "<div class=\"noPrint\">\n";
			}
	
			if ($rowC[9]==1)
			{
				//echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Integrate Cost\" title=\"Integrated View. Displays addendum(s) as part of their respective phase.\">\n";
			}
	
			if ($_SESSION['subq']=="print")
			{
				echo "</div>\n";
			}
	
			echo "               </td>\n";
			echo "</form>\n";
		}
		
		echo "            </tr>\n";
	}

	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	
	//$cbid_cnt=bid_cost_detect($_SESSION['officeid'],$viewarray['jobid']);
	
	//echo 'Bid Cost Item Error Count: '.$cbid_cnt.'<br>';
	
	$_SESSION['viewarray']=$viewarray;
}

function view_job_cost()
{
	//echo "TESTING COST<br>";
	if (!isset($_SESSION['viewarray']))
	{
		echo "Fatal Error: Job Cost variables not set!";
		exit;
	}
	
	global 		$bctotal,$bcadjtotal,$rctotal,$cctotal,$bmtotal,$bmadjtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	
	$viewarray	=$_SESSION['viewarray'];
	$jobid		=$viewarray['jobid'];
	$jadd		=$viewarray['jadd'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];

	if (!isset($jobid)||$jobid=='')
	{
		echo "Fatal Error: Job ID (".$jobid.") not set!";
		exit;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jadd."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreAa = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='0';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreAb = "SELECT MAX(jadd) as maxjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreAb = mssql_query($qrypreAb);
	$rowpreAb = mssql_fetch_array($respreAb);

	$qrypreB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qrypreC = "SELECT costdata_l,costdata_m FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jadd."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_row($respreC);

	$qrypreD = "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);

	$jsecurityid =$rowpreB['securityid'];

	$acclist=explode(",",$_SESSION['aid']);

	if (!in_array($_SESSION['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Contract</b>";
		exit;
	}

	$viewarray['maxjadd']=$rowpreAb['maxjadd'];
	
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

	$qryC = "SELECT officeid,name,stax,sm,gm FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$jsecurityid."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

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

	$sdate		=date("m/d/Y", strtotime($rowpreAa['added']));
	$cdate 		=date("m/d/Y", strtotime($viewarray['cdate']));
	$condate	=date("m/d/Y", $viewarray['condate']);
	$set_ia		=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$estidret   =$rowpreA['jobid'];
	$vdiscnt    =$viewarray['discount'];
	$pbaseprice =$rowB['price'];
	$bcomm      =$rowB['comm'];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	$brdr			=0;
	$ittext 		="";

	if ($viewarray['maxjadd'] != 0)
	{
		if (!empty($_REQUEST['itgr']) && $_REQUEST['itgr'] == 1)
		{
			$ittext 	="Standard Addendum View";
		}
		else
		{
			$ittext 	="Integrated Addendum View";
		}
	}

	//display_array($viewarray);
	echo "<script type=\"text/javascript\" src=\"js/jquery_contract_func.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"js/jquery_estimate_cost_func.js\"></script>\n";
	echo "<table width=\"950px\">\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"2\" align=\"right\">\n";
	echo "                  <table class=\"outer\" width=\"100%\" border=".$brdr.">\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"left\" NOWRAP><b>Contract Cost Breakdown for ".$rowC['name']."</b> ".$ittext."</td>\n";
	echo "                        <td class=\"gray\" align=\"center\" NOWRAP><b>Contract Date: </b>".$condate."</td>\n";
	echo "                        <td class=\"gray\" align=\"right\" NOWRAP><b>System Insert Date: </b>".$sdate."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"left\" NOWRAP><b>Contract <font color=\"red\">".$estidret."</font></b></td>\n";
	echo "                        <td colspan=\"3\" class=\"gray\" align=\"right\">\n";
	echo "                  			<table border=".$brdr.">\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray\" align=\"right\"><b>SalesRep</b></td>\n";
	echo "                        			<td class=\"gray\" align=\"right\">\n";
	echo "                           			<input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowD['fname']." ".$rowD['lname']."\">\n";
	echo "                        			</td>\n";
	echo "                        			<td class=\"gray\" align=\"right\"><b>Sales Manager</b></td>\n";
	echo "                        			<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "                           			<input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowL['fname']." ".$rowL['lname']."\">\n";
	echo "                        			</td>\n";
	echo "                     			</tr>\n";
	echo "                  			</table>\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"50%\">\n";

	//	Customer Display Start
	cinfo_display_job($_SESSION['officeid'],$viewarray['cid'],$rowC[2]);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"50%\">\n";

	// Set Pool Detail Display
	pool_detail_display_job($viewarray['jobid'],$viewarray['jadd']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"100%\">\n";

	//	Bids Rollup Display
	costadj_rollup_disp($_SESSION['officeid'],$viewarray['cid'],$viewarray['jobid'],$viewarray['mjadd'],$viewarray['allowdel']);
	
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";

	if ($_SESSION['manphsadj']==1)
	{
		echo "			<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"inscostadj\">\n";
		echo "			<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	}

	echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"black\" border=1>\n";

	//calcbyphsL($c_jobdata_l,$b_jobdata_l,$p_jobdata_l,1,$_REQUEST['itgr']);
	calcbyphsL($c_jobdata_l,$b_jobdata_l,$p_jobdata_l,1);

	$bcestcost 	=$bctotal;
	$bcadjcost 	=$bcadjtotal;
	$tbccost	=$bcestcost+$bcadjcost;
	$fbcestcost =number_format($bcestcost, 2, '.', '');
	$fbcadjcost	=number_format($bcadjcost, 2, '.', '');
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
	echo "         <br>\n";
	echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"black\" border=1>\n";

	calcbyphsM($c_jobdata_m,$p_jobdata_m,1);

	$bmestcost  =$bmtotal;
	$bmadjcost  =$bmadjtotal;
	$tbmcost		=$bmestcost+$bmadjcost;
	$fbmestcost	=number_format($bmestcost, 2, '.', '');
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

	if ($_SESSION['manphsadj']==1)
	{
		echo "           <tr>\n";

		if (!empty($_REQUEST['showtotals']) && $_REQUEST['showtotals']=="1")
		{
			echo "              <td NOWRAP colspan=\"5\" class=\"wh\" align=\"right\"></td>\n";
		}
		else
		{
			echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"right\"></td>\n";
			echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
			echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		}

		echo "              	<td colspan=\"2\" class=\"wh\" align=\"right\">\n";
		echo "						<div class=\"noPrint\">\n";
		echo "                  		<input class=\"buttondkgry\" type=\"submit\" value=\"Adjust\">\n";
		echo "						</div>\n";
		echo "					</td>\n";
		echo "              	<td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "				</form>\n";
		echo "           </tr>\n";
	}

	echo "         </table>\n";
	
	$pymntsched	=0;
	$pymntschedp=0;
	
	if ($viewarray['maxjadd'] !=0)
	{
		$qry1 = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='0';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		
		$pymntsched=$row1['psched'];
		$pymntschedp=$row1['psched_perc'];
	}
	else
	{
		$pymntsched=$rowpreA['psched'];
		$pymntschedp=$rowpreA['psched_perc'];
	}

	// Payment Schedule Table
	//echo $pymntsched."<br>";
	if ($pymntsched != 0)
	{
		$taretail=0;
		echo "         <br>\n";
		echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"black\" border=1>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"left\"><b>Retail Payment Schedule</b></td>\n";
		echo "           </tr>\n";

		$phsar=explode(",",$pymntsched);
		$perar=explode(",",$pymntschedp);

		if (count($phsar)==count($perar))
		{
			foreach ($phsar as $an => $pc)
			{
				$qryZ = "SELECT phscode,extphsname FROM phasebase WHERE phscode='".$pc."';";
				$resZ = mssql_query($qryZ);
				$rowZ = mssql_fetch_array($resZ);

				$paymnt	=$perar[$an];
				$fpaymnt	=number_format($paymnt, 2, '.', '');

				echo "           <tr>\n";

				if ($pc=="501L")
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"50\"><b>501L</b></td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Down Payment</b></td>\n";
				}
				else
				{
					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"50\"><b>".$rowZ['phscode']."</b></td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$rowZ['extphsname']."</b></td>\n";
				}

				echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$fpaymnt."</b></td>\n";
				echo "           </tr>\n";
				$taretail=$taretail+$paymnt;
			}
		}
		else
		{
			$taretail=$viewarray['camt'];
		}

		if ($rowC[2]==1)
		{
			echo "           <tr>\n";
			echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Retail Sales Tax Included in Payment Schedule</b></td>\n";
			echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b></b></td>\n";
			echo "           </tr>\n";
		}

		$ocontract  =$taretail;
		$focontract =number_format($ocontract, 2, '.', '');

		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Original Contract Total</b></td>\n";
		echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$focontract."</b></td>\n";
		echo "           </tr>\n";

		$qryX = "SELECT jadd,psched_adj,add_type FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd!=0;";
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

					$fpsched_adj	=number_format($rowX['psched_adj'], 2, '.', '');
					echo "           <tr>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"50\"><b>60".$rowX['jadd']."L</b></td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$add_type." Addendum ".$rowX['jadd']."</b></td>\n";
					echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"65\"><b>".$fpsched_adj."</b></td>\n";
					echo "           </tr>\n";
					$taretail=$taretail+$rowX['psched_adj'];
				}
			}
		}

		$ftaretail =number_format($taretail, 2, '.', '');
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Addendum Adjusted Contract Total</b></td>";
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"65\"><b>".$ftaretail."</b></td>\n";
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
	$tretail  	=$viewarray['tretail'];
	$ftretail 	=number_format(round($tretail), 2, '.', '');

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

	//echo "PER: ".$netper."<br>";

	if ($tcustallow != 0)
	{
		$qryY = "UPDATE jobs SET tgp='".$netper."', jcost='".$tadjbcost."', jprof='".$tprofit."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
	}
	else
	{
		$qryY = "UPDATE jobs SET tgp='".$netper."', jcost='".$tbcost."', jprof='".$tprofit."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
	}
	$resY = mssql_query($qryY);

	//echo $qryY."<br>";

	$ftcustallow	=number_format($tcustallow, 2, '.', '');
	$ftcontract 	=number_format($tcontract, 2, '.', '');
	$ftadjcontract 	=number_format($tadjcontract, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftadjbcost		=number_format($tadjbcost, 2, '.', '');
	$ftprofit		=number_format($tprofit, 2, '.', '');
	$fnetper 		=round($netper, 2)*100;

	echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"black\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"left\"><b>Totals</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Contract Price</b></td>\n";
	echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftcontract."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Adjusted Contract Price</b></td>\n";
		echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftadjcontract."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Construction Total</b></td>\n";
	echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftbcost."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Adjusted Construction Total</b></td>\n";
		echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftadjbcost."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Net</b></td>\n";
	echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftprofit."</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Net %</b></td>\n";
	echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$fnetper."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\">\n";
	echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";
	//echo "            <tr>\n";
	//echo "               <td align=\"left\">\n";
	//echo "					<div class=\"noPrint\">\n";
	//echo "						<form method=\"post\">\n";
	//echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	//echo "						<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
	//echo "						<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	//echo "						<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	//echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	//echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	//echo "						<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	//
	//if (empty($_REQUEST['showtotals']))
	//{
	//	echo "					<input type=\"hidden\" name=\"showtotals\" value=\"1\">\n";
	//}
	//
	//if (!empty($_REQUEST['showtotals']) && $_REQUEST['showtotals']=="1")
	//{
	//	echo "                  <input class=\"buttondkgrypnl\" type=\"submit\" value=\"View Expanded\">\n";
	//}
	//else
	//{
	//	echo "                  <input class=\"buttondkgrypnl\" type=\"submit\" value=\"View Compressed\">\n";
	//}
	//
	//echo "						</form>\n";
	//echo "					</div>\n";
	//echo "               </td>\n";
	//echo "            </tr>\n";
	echo "            <tr>\n";
	echo "      			<td valign=\"bottom\" align=\"center\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "						<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "						<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	echo "						<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "						<input type=\"hidden\" name=\"discount\" value=\"".$vdiscnt."\">\n";
	echo "						<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "         				<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View Retail\">\n";
	echo "						</form>\n";
	echo "					</div>\n";
	echo "				</td>\n";
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
	
	_show_hide_objects();
	
	echo "               </td>\n";
	echo "            </tr>\n";		
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}


function CommissionScheduleRW_Cont($v)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	$dbg=0;
	$tsecid=1952;
	$tcomm=0;
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Commissions</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	
	if ($dbg==1 && $_SESSION['securityid']==$tsecid)
	{
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"7\">\n";
		
		echo "<pre>";
		print_r($v);
		echo '<br><br>';
		print_r($comar);
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 1 SR Specific Comm
	$qry1a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=1;";
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);
    $nrow1a= mssql_num_rows($res1a);
	
	if ($nrow1a==1)
	{			
		$tcomm=$tcomm + $row1a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Base Comm</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row1a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row1a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row1a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo1\">".number_format($row1a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo1\">".number_format($row1a['amt'], 2, '.', '')."</div>";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}

	//Grab Category 2 SR OU Specific Comm
	$qry2a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=2;";
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
    $nrow2a= mssql_num_rows($res2a);
	
	if ($nrow2a==1)
	{		
		$tcomm=$tcomm + $row2a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Over/Under Comm</font></b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row2a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
			
		if ($row2a['type'] == 2)
		{
			echo '%';
		}
			
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row2a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo2\">".number_format($row2a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo2\">".number_format($row2a['amt'], 2, '.', '')."</div>\n";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}

	//Grab Category 6 Comms SmartFeature
	$qry6a  = "select top 1 * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=6 order by adate desc;";
	$res6a = mssql_query($qry6a);
	$row6a = mssql_fetch_array($res6a);
    $nrow6a= mssql_num_rows($res6a);
	
	if ($nrow6a > 0)
	{		
		$tcomm=$tcomm + $row6a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>SmartFeature</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row6a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row6a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row6a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 9 Comms Tiered
	$qry9a  = "select top 1 * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=9 order by adate desc;";
	$res9a = mssql_query($qry9a);
	$row9a = mssql_fetch_array($res9a);
    $nrow9a= mssql_num_rows($res9a);
	
	if ($nrow9a > 0)
	{		
		$tcomm=$tcomm + $row9a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Merit Bonus</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row9a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row9a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row9a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 8 Comms
	$qry8a  = "select top 1 * from jest..CommissionSchedule where oid=".$_SESSION['officeid']." and jobid='".$v['jobid']."' and cbtype=8 order by adate desc;";
	$res8a = mssql_query($qry8a);
	$row8a = mssql_fetch_array($res8a);
    $nrow8a= mssql_num_rows($res8a);
	
	if ($nrow8a > 0)
	{
		$tcomm=$tcomm + $row8a['amt'];
		echo "           <tr>\n";
		//echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$row8a['label']."</b></td>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\">Commission Override</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//echo $row8a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//if ($row8a['type'] == 2)
		//{
		//	echo '%';
		//}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row8a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<div class=\"noPrint JMStooltip\" title=\"Minimum Commission Override enabled\"><img src=\"images/information.png\" width=\"11px\" height=\"11px\"></div>\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 0 Manual Adjust Comm
	$qry3a  = "select top 1 * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=0 order by adate desc;";
	$res3a = mssql_query($qry3a);
	$row3a = mssql_fetch_array($res3a);
    $nrow3a= mssql_num_rows($res3a);
	
	if ($nrow3a > 0)
	{		
		$tcomm=$tcomm + $row3a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b><div title=\"".$row3a['notes']."\">Manual Adjust</div></b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//echo $row3a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row3a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		if ($_SESSION['clev'] >= 4 && $v['mjadd'] == 0 && $v['njobid']=='0')
		{
			echo "					<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"updateMA\">\n";
			echo "						<input type=\"hidden\" name=\"csid\" value=\"".$row3a['csid']."\">\n";
			echo "						<input type=\"hidden\" name=\"jobid\" value=\"".$v['jobid']."\">\n";
			echo "						<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "						<input class=\"brdrtxtrght\" type=\"text\" name=\"amt\" id=\"ouo0\" value=\"".number_format($row3a['amt'], 2, '.', '')."\" size=\"7\" onChange=\"updTotalComm('ouo1','ouo2','ouo0','tcommamt');\">\n";
			/*echo "        			<span class=\"submenu\" id=\"commnote\">\n";
			echo "						<textarea name=\"csched[0][notes]\" cols=\"21\" rows=\"3\"></textarea>\n";
			echo "        			</span>\n";*/
		}
		else
		{
			echo number_format($row3a['amt'], 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($_SESSION['clev'] >= 4 && $v['mjadd'] == 0 && $v['njobid']=='0')
		{
			//echo "					<div onclick=\"SwitchMenu('commnote')\"><img src=\"images/note_add.png\" title=\"Click to Add a Note\"></div>";
			echo "                  <input class=\"transnb\" type=\"image\" src=\"images/save.gif\" value=\"Update\" title=\"Update Manual Adjust\">\n";
		}
		
		echo "				</td>\n";
		echo "           </tr>\n";
		
		/*if ($_SESSION['clev'] >= 4 && $v['mjadd'] == 0 && $v['njobid']=='0')
		{
			echo "        	<tr>\n";
			echo "        		<td colspan=\"2\" align=\"right\"><b>Manual Adjust Notes</b></td>\n";
			echo "        		<td colspan=\"5\" align=\"right\">\n";
			echo "        			<span class=\"submenu\" id=\"commnote\">\n";
			echo "						<textarea name=\"csched[0][notes]\" cols=\"21\" rows=\"3\"></textarea>\n";
			echo "        			</span>\n";
			echo "				</td>\n";
			echo "           </tr>\n";
		}*/
		
		if ($_SESSION['clev'] >= 4 && $v['mjadd'] == 0 && $v['njobid']=='0')
		{
			echo "					</form>\n";
		}
	}
    
    //Grab Category 10 Fixed Manual Override Comm
	$qry10a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=10;";
	$res10a = mssql_query($qry10a);
	$row10a = mssql_fetch_array($res10a);
    $nrow10a= mssql_num_rows($res10a);
	
	if ($nrow10a==1)
	{			
		$tcomm=$tcomm + $row10a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Manual Override</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row10a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row10a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row10a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo1\">".number_format($row10a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo1\">".number_format($row10a['amt'], 2, '.', '')."</div>";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}

	//Grab Category 11 Percent Manual Override Comm
	$qry11a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=11;";
	$res11a = mssql_query($qry11a);
	$row11a = mssql_fetch_array($res11a);
    $nrow11a= mssql_num_rows($res11a);
	
	if ($nrow11a==1)
	{		
		$tcomm=$tcomm + $row11a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Manual Override</font></b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row11a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
			
		if ($row11a['type'] == 2)
		{
			echo '%';
		}
			
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row11a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo2\">".number_format($row11a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo2\">".number_format($row11a['amt'], 2, '.', '')."</div>\n";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}

	//Grab Category 4+ Comms
	/*$qry3a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry >=4 and active=1 order by secid asc,ctgry asc;";
	$res3a = mssql_query($qry3a);
    $nrow3a= mssql_num_rows($res3a);
	
	if ($nrow3a > 0)
	{
		while ($row3a = mssql_fetch_array($res3a))
        {
            $comar[]=array(
						'cmid'=>$row3a['cmid'],
						'secid'=>$row3a['secid'],
						'catid'=>$row3a['ctgry'],
						'ctype'=>$row3a['ctype'],
						'rate'=>$row3a['rate'],
						'thresh'=>$row3a['thresh'],
						'd1'=>strtotime($row3a['d1']),
						'd2'=>strtotime($row3a['d2']),
						'active'=>$row3a['active'],
						'label'=>$row3a['name'],
						'amt'=>$row3a['amt']
					);
        }
	}
	
	if ($nrow3a > 0)
	{
		foreach ($comar as $cn => $cv)
		{
			if ($drange >= $cv['d1'] && $drange < $cv['d2'])
			{
				if ($cv['ctype']==1)
				{
					$ctype='fx';
				}
				elseif ($cv['ctype']==2)
				{
					$ctype='%';
				}
				else
				{
					$ctype='';
				}
				
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][cmid]\" value=\"".$cv['cmid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][secid]\" value=\"".$cv['secid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\" value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\" value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rate]\" value=\"".$cv['rate']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][thresh]\" value=\"".$cv['thresh']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][d1]\" value=\"".$cv['d1']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][d2]\" value=\"".$cv['d2']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\" value=\"".$cv['label']."\">\n";
				
				if ($cv['catid'] == 3)
				{
					
					if ($cv['secid']==0 || $cinar['estsecid']==$cv['secid'])
					{
						if ($ctype==1)
						{
							$amt=($cinar['fctramt'] * $cv['rate']);
						}
						else
						{
							$amt=$cv['amt'];
						}
						
						$tcomm=$tcomm+$amt;
						echo "           <tr>\n";
						echo "              <td colspan=\"2\" align=\"right\"><b>".$cv['label']."</b></td>\n";
						echo "              <td align=\"center\"></td>\n";
						echo "              <td align=\"center\">".$ctype."</td>\n";
						echo "              <td align=\"right\"></td>\n";
						echo "              <td align=\"right\">".number_format($amt, 2, '.', '')."</td>\n";
						echo "              <td align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][amt]\" value=\"".number_format($amt, 2, '.', '')."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}

				}
				elseif ($cv['catid'] == 4)
				{

					if ($cv['secid']==0 || $cinar['estsecid']==$cv['secid'])
					{
						if ($ctype==1)
						{
							$amt=($cinar['fctramt'] * $cv['rate']);
						}
						else
						{
							$amt=$cv['amt'];
						}
						
						$tcomm=$tcomm+$amt;
						echo "           <tr>\n";
						echo "              <td colspan=\"2\" align=\"right\"><b>".$cv['label']."</b></td>\n";
						echo "              <td align=\"center\"></td>\n";
						echo "              <td align=\"center\">".$ctype."</td>\n";
						echo "              <td align=\"right\"></td>\n";
						echo "              <td align=\"right\">".number_format($amt, 2, '.', '')."</td>\n";
						echo "              <td align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][amt]\" value=\"".number_format($amt, 2, '.', '')."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}

				}
			}
		}
	}*/
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Total Comm</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\"><div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div></font>";
	}
	else
	
	{
		echo "					<div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div>";
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "              	<img src=\"images/pixel.gif\">\n";
	//echo "                  <input class=\"transnb\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract\">\n";
	echo "				</td>\n";
	echo "           </tr>\n";
	echo "			</form>\n";
}

?>