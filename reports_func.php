<?php

function BaseMatrix()
{
	if ($_SESSION['call']=="logins")
	{
		logins_general();
	}
	elseif ($_SESSION['call']=="activity_job_full")
	{
		activity_job_full();
	}
	elseif ($_SESSION['call']=="loggedin")
	{
		loggedusers();
	}
	elseif ($_SESSION['call']=="user_activity_detail")
	{
		user_activity_detail();
	}
	elseif ($_SESSION['call']=="clist")
	{
		clist_general();
	}
	elseif ($_SESSION['call']=="sleads")
	{
		leads_general();
	}
	elseif ($_SESSION['call']=="rleads")
	{
		leads_general();
	}
	elseif ($_SESSION['call']=="LeadSource")
	{
		LeadSourceReportMain();
	}
	elseif ($_SESSION['call']=="salesman_gen")
	{
		salesman_general();
	}
	elseif ($_SESSION['call']=="tinternet")
	{
		internet_total();
	}
	elseif ($_SESSION['call']=="distinct_matlist")
	{
		distinct_matlist();
	}
	elseif ($_SESSION['call']=="csearch")
	{
		if (isset($_SESSION['tester']) and $_SESSION['tester']==1) {
			csearch_DEV();
		}
		else {
			csearch();
		}
	}
	elseif ($_SESSION['call']=="csearch_results")
	{
		csearch_results();
	}
	elseif ($_SESSION['call']=="csearch_results_mainmenu")
	{
		csearch_results();
	}
	elseif ($_SESSION['call']=="chistory_add")
	{
		chistory_add();
	}
	elseif ($_SESSION['call']=="chistory")
	{
		//echo "HISTORY";
		
		if ($_SESSION['securityid']==269999999999999999999)
		{
			onesheet_all_modules();
		}
		else
		{
			chistory_list();
		}
	}
	elseif ($_SESSION['call']=="IVRreport")
	{
		IVR_report();
	}
	elseif ($_SESSION['call']=="complaints")
	{
		complaints();
	}
	elseif ($_SESSION['call']=="exportpb")
	{
		include ("./pbgen_func.php");
		if ($_SESSION['subq']=="list_ret")
		{
			list_avail_ret();
		}
		elseif ($_SESSION['subq']=="list_cst")
		{
			list_avail_cst();
		}
		elseif ($_SESSION['subq']=="gen_ret")
		{
			generate_ret();
		}
		elseif ($_SESSION['subq']=="gen_cst")
		{
			generate_cst();
		}
		elseif ($_SESSION['subq']=="del_ret")
		{
			delete_ret();
		}
		elseif ($_SESSION['subq']=="view")
		{
			//view_pb();
		}
	}
	elseif ($_SESSION['call']=="showretpb")
	{
		include ("./pdf/retailpb_gen_func.php");
	}
	elseif ($_SESSION['call']=="showcstpb")
	{
		include ("./pdf/costpb_gen_func.php");
	}
	elseif ($_SESSION['call']=="srsearch")
	{
		statussearch();
	}
	elseif ($_SESSION['call']=="statusreport")
	{
		statusreport();
	}
	elseif ($_SESSION['call']=="finleads" || $_SESSION['call']=="sfinleads")
	{
		leads_general_fin();
	}
	elseif ($_SESSION['call']=="fnsearch")
	{
		fnsearch();
	}
	elseif ($_SESSION['call']=="finanreport")
	{
		finanreport();
	}
	elseif ($_SESSION['call']=="fnexport")
	{
		fnexport();
	}
	elseif ($_SESSION['call']=="officerep")
	{
		include ("./office_demo_maint_func.php");
		if (empty($_SESSION['subq'])||$_SESSION['subq']=="list"||$_SESSION['subq']=="search")
		{
			listoff();
		}
		elseif ($_SESSION['subq']=="view")
		{
			viewoff();
		}
		elseif ($_SESSION['subq']=="add")
		{
			addoff();
		}
		elseif ($_SESSION['subq']=="update")
		{
			updateoff();
		}
		elseif ($_SESSION['subq']=="advrep")
		{
			if ($_REQUEST['req']=="1")
			{
				advrep_search();
			}
			elseif ($_REQUEST['req']=="2")
			{
				advrep_result();
			}
		}
	}
	elseif ($_SESSION['call']=="set_digdate")
	{
		set_digdate();
	}
	elseif ($_SESSION['call']=="set_clsdate")
	{
		set_clsdate();
	}
	elseif ($_SESSION['call']=="set_condate")
	{
		set_condate();
	}
	elseif ($_SESSION['call']=="digreports")
	{
		include ("./reports_digs_func.php");
		dig_matrix();
	}
	elseif ($_SESSION['call']=="digreports_pdf")
	{
		include ("./chkrequest_gen_func.php");
		chkrequest_gen();
	}
	elseif ($_SESSION['call']=="zipreports")
	{
		include ("./reports_zip_func.php");
		zip_matrix();
	}
	elseif ($_SESSION['call']=="renovreports")
	{
		include ("./reports_renov_func.php");
		renov_matrix();
	}
	elseif ($_SESSION['call']=="operating")
	{
		include ("./reports_operating_func.php");
		//op_matrix_wordtest();
		op_matrix();
	}
	elseif ($_SESSION['call']=="cursorlist")
	{
		echo "Cursor List:<br>";
		findcursor();
	}
	elseif ($_SESSION['call']=="off_total")
	{
		off_total();
	}
	elseif ($_SESSION['call']=="offfeesched")
	{
		off_feesched();
	}
	elseif ($_SESSION['call']=="standings")
	{
		include ("./reports_standings_func.php");
		sales_standings();
	}
	elseif ($_SESSION['call']=="standings_config")
	{
		include ("./reports_standings_func.php");
		sales_standings_config();
	}
	elseif ($_SESSION['call']=="standings_config_view")
	{
		include ("./reports_standings_func.php");
		sales_standings_config_view();
	}
	elseif ($_SESSION['call']=="standings_config_create")
	{
		include ("./reports_standings_func.php");
		sales_standings_config_create();
	}
	elseif ($_SESSION['call']=="updt_standings_config")
	{
		include ("./reports_standings_func.php");
		updt_standings_config();
	}
	elseif ($_SESSION['call']=="contrval")
	{
		include ("./reports_standings_func.php");
		getleadtocontvalue();
	}
	elseif ($_REQUEST['call']=="office")
	{
		include ("./office_comments_func.php");
		base_matrix();
	}
	elseif ($_SESSION['call']=="srpage")
	{
		if (isset($_SESSION['tester']) and $_SESSION['tester']==1) {
			include('./report_sc_func_DEV.php');
		}
		else {
			include('./report_sc_func.php');
		}
		srpage();
	}
	elseif ($_SESSION['call']=="officepipeline")
	{
		OfficePipelineReport();
	}
	elseif ($_SESSION['call']=="employeeinfo_401k")
	{
		employeeinfo_401k();
	}
	elseif ($_SESSION['call']=="DeleteSingleCommission")
	{
		DeleteSingleCommission($_REQUEST['hid']);
	}
	elseif ($_SESSION['call']=="Conversion")
	{
		Conversion();
	}
	elseif ($_SESSION['call']=="construction_comments_add")
	{
		add_construction_comments();
	}
	elseif ($_SESSION['call']=="ShowEmailLog")
	{
		ShowEmailSendLog();
	}
	elseif ($_SESSION['call']=="upload_file_cid")
	{
		upload_file_cid();
	}
	elseif ($_SESSION['call']=="delete_file_cid")
	{
		delete_file_cid();
	}
	elseif ($_SESSION['call']=="undelete_file_cid")
	{
		undelete_file_cid();
	}
	//elseif ($_SESSION['call']=="changelog")
	//{
    //    echo "<script type=\"text/javascript\" src=\"js/jquery_changelog_func.js\"></script>\n";
    //    echo "<div id=\"ChangeLog\"></div>";
	//}
}

function add_construction_comments()
{
	if (isset($_REQUEST['mcomment']) && strlen($_REQUEST['mcomment']) >= 2)
	{
		$qryA1 = "SELECT ccid FROM jest..construction_comments WHERE oid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."' and tranid='".$_REQUEST['ccuid']."';";
		$resA1 = mssql_query($qryA1);
		$nrowA1 = mssql_num_rows($resA1);
		
		if ($nrowA1 == 0)
		{
			$inputtext=removequote($_REQUEST['mcomment']);			
			$qryA2  = "INSERT INTO jest..construction_comments (oid,sid,cid,act,tranid,mtext) ";
			$qryA2 .= "VALUES ";
			$qryA2 .= "('".$_SESSION['officeid']."','".$_SESSION['securityid']."','".$_REQUEST['cid']."','Construction','".$_REQUEST['ccuid']."','".htmlspecialchars(removequote($inputtext),ENT_QUOTES)."');";
			$resA2  = mssql_query($qryA2);
		}
	}
	
	chistory_list();
}

function Conversion()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$qry = "select smo,syr,emo,eyr from bonus_schedule_config where active=1;";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_extend.js\"></script>\n";
	
	echo "<div id=\"masterdiv\">\n";
	echo "	<table class=\"transnb\" align=\"center\" width=\"750px\">\n";
	
	//echo "		<tr>\n";
	//echo "			<td colspan=\"2\" align=\"center\">\n";
	//echo "				<div align=\"left\"><b><font color=\"#8B0000\">NOTE: This Report is in BETA Development.<br>Continued Feedback is necessary to ensure it is working properly, please submit Feedback if you find a discrepancy.</font></b></div>\n";
	//echo "			</td>\n";
	//echo "		</tr>\n";
	
	echo "		<tr>\n";
	echo "			<td colspan=\"2\" align=\"center\">\n";
	echo "				<table class=\"outer\" width=\"100%\">\n";
	echo "					<tr>\n";
	echo "						<td class=\"gray\" align=\"left\">\n";
	echo "							<b>Conversion Report</b>";
	echo "						</td>\n";
	echo "						<td class=\"gray\" align=\"right\">\n";
	echo "							<table>\n";
	echo "								<tr>\n";
	echo "									<td align=\"right\">Feedback</td>\n";
	echo "									<td class=\"gray\" align=\"right\">\n";
	echo "         								<form method=\"post\">\n";
	echo "										<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "										<input type=\"hidden\" name=\"call\" value=\"new_feedback\">\n";
	echo "										<input type=\"hidden\" name=\"subject\" value=\"Conversion Report Feedback\">\n";
	echo "										<input class=\"transnb\" type=\"image\" src=\"images/pencil.png\" alt=\"Feedback\">\n";
	echo "      			   					</form>\n";
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "							</table>\n";
	echo "						</td>\n";
	echo "						<td class=\"gray\" align=\"right\" width=\"20px\">\n";
	
	HelpNode('ConversionReport',2);
	
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	echo "		<tr>\n";
	echo "			<td colspan=\"2\" align=\"center\">\n";
	echo "				<table class=\"outer\" width=\"100%\">\n";
	echo "					<tr>\n";
	echo "						<td class=\"gray\" align=\"left\">\n";
	
	echo 'Print Date ' . date('m/d/Y',time());
	
	echo "						</td>\n";
	echo "						<td class=\"gray\" align=\"right\">\n";

	if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='SalesRep')
	{
		
	}
	else
	{
		$qry0 = "
			SELECT
				O.name,O.officeid,O.active
			FROM 
				offices as O
			WHERE
				O.active=1
				and O.grouping=0
			";
		
		if ($_SESSION['rlev'] < 9)
		{
			$qry0 .= "
				and O.officeid=".$_SESSION['officeid']." ";
		}
		
		$qry0 .= "	
				order by O.active DESC,O.name ASC;
			";
		$res0 = mssql_query($qry0);
		
		while ($row0 = mssql_fetch_array($res0))
		{
			$oid_ar[$row0['officeid']]=$row0['name'];
		}
		
		if (isset($oid_ar) && count($oid_ar) > 0)
		{
			if ($nrow > 0)
			{
				$sd1=date('m/d/Y',strtotime($row['smo'].'/01/'.$row['syr']));
			}
			else
			{
				$sd1=date('m',time()).'/01/'.date('Y',time());
			}
			
			
			echo "         		        <form name=\"tsearch1\" method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"Conversion\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"Office\">\n";
			echo "							<table class=\"transnb\">\n";
			echo "							<tr>\n";
			echo "								<td class=\"gray\" align=\"right\"><b>Report Date Range</b></td>\n";
			echo "								<td class=\"gray\" align=\"center\">\n";
			
			if (isset($_REQUEST['d1']) && valid_date($_REQUEST['d1']))
			{
				echo "									<input class=\"bboxbc\" type=\"text\" name=\"d1\" id=\"d1\" value=\"".$_REQUEST['d1']."\" size=\"6\" maxlength=\"10\">\n";
			}
			else
			{
				
				echo "									<input class=\"bboxbc\" type=\"text\" name=\"d1\" id=\"d1\" value=\"".$sd1."\" size=\"6\" maxlength=\"10\">\n";
			}
			
			echo "								</td>\n";
			echo "								<td class=\"gray\" align=\"center\">\n";
			
			if (isset($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
			{
				echo "									<input class=\"bboxbc\" type=\"text\" name=\"d2\" id=\"d2\" value=\"".$_REQUEST['d2']."\" size=\"6\" maxlength=\"10\">\n";
			}
			else
			{
				
				echo "									<input class=\"bboxbc\" type=\"text\" name=\"d2\" id=\"d2\" value=\"". date('m/d/Y',time()) ."\" size=\"6\" maxlength=\"10\">\n";
			}
			
			echo "								</td>\n";
			echo "								<td class=\"gray\" align=\"right\">\n";
			echo "										<select name=\"oid\" onChange=\"this.form.submit();\">\n";
			echo "  										<option value=\"0\">Select Office...</option>\n";
			
			if ($_SESSION['rlev'] >= 9 && $_SESSION['officeid']==89)
			{
				if (isset($_REQUEST['oid']) && $_REQUEST['oid']==0)
				{
					echo "  										<option value=\"0\" SELECTED>All</option>\n";
				}
				else
				{
					echo "  										<option value=\"0\">All</option>\n";
				}
				
				echo "  										<option value=\"0\" DISABLED>================</option>\n";
			}
	
			foreach ($oid_ar as $nO => $vO)
			{	
				if (isset($_REQUEST['oid']) && $_REQUEST['oid']==$nO)
				{
					echo "										<option value=\"".$nO."\" SELECTED>".$vO."</option>\n";
				}
				else
				{
					echo "										<option value=\"".$nO."\">".$vO."</option>\n";
				}
			}
	
			echo "										</select>\n";
			echo "                                  </td>\n";
			echo "                                  <td><input class=\"transnb\" type=\"image\" src=\"images/arrow_refresh_small.png\" alt=\"Refresh\"></td>\n";
			echo "					            </tr>\n";
			echo "				            </table>\n";
			echo "                      </form>\n";
		}
	}
	
    echo "                      </td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
	
	if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='SalesRep')
	{
		echo "		<tr>\n";
		echo "			<td colspan=\"2\" align=\"center\">\n";
		
		echo 'Sales Reps';
		
		echo "			</td>\n";
		echo "		</tr>\n";
	}
	else
	{		
		if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
		{
			echo "		<tr>\n";
			echo "			<td colspan=\"2\" align=\"center\">\n";
			echo "				<table class=\"outer\" width=\"100%\">\n";
			echo "					<tr class=\"tblhd\">\n";
			echo "						<td align=\"left\"><b>Office</b></td>\n";
			echo "						<td align=\"center\" title=\"Total number of Leads added to the Office in the Date Range provided\"><b>Leads</b></td>\n";
			echo "						<td align=\"center\" title=\"Total number of Contracts written in the Office in the Date Range provided\"><b>Contracts</b></td>\n";
			echo "						<td align=\"center\" title=\"Lead to Contract Ratio\"><b>LtoC %</b></td>\n";
			echo "						<td align=\"center\" title=\"Total number of Digs registered by the Office in the Date Range provided\"><b>Digs</b></td>\n";
			echo "						<td align=\"center\" title=\"Lead to Dig Ratio\"><b>LtoD %</b></td>\n";
			echo "						<td align=\"center\" title=\"Contract to Dig Ratio\"><b>CtoD %</b></td>\n";
			echo "						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
			echo "					</tr>\n";
			
			$lcnt=1;
			$qryZ = "exec tlh_ConversionReport @oid=".$_REQUEST['oid'].",@d1='".$_REQUEST['d1']."',@d2='".$_REQUEST['d2']." 23:59:59';";
			$resZ = mssql_query($qryZ);
			$rowZ = mssql_fetch_array($resZ);
			
			$qryX = "select distinct(C.securityid),(select lname from security where securityid=C.securityid) as lname,(select fname from security where securityid=C.securityid) as fname from cinfo as C where C.officeid=".$_REQUEST['oid']." and C.dupe=0 and C.added >= '".$_REQUEST['d1']."' and C.added < '".$_REQUEST['d2']." 23:59:59' order by lname,fname asc;";
			$resX = mssql_query($qryX);
			$nrowX= mssql_num_rows($resX);
			
			//echo $qryX.'<br>';
			
			if ($rowZ['Leads'] > 0)
			{
				$line_ar=array('<b>'.$rowZ['Office'].'</b>','<b>'.$rowZ['Leads'].'</b>','<b>'.$rowZ['Contracts'].'</b>','<b>'.$rowZ['LtoC'].'</b>','<b>'.$rowZ['Digs'].'</b>','<b>'.$rowZ['LtoD'].'</b>','<b>'.$rowZ['CtoD'].'</b>','');
				$posi_ar=array('left','center','center','center','center','center','center','center');
				DisplayTableRow($lcnt,$line_ar,$posi_ar);
				$lcnt++;
			}
			
			if ($nrowX > 0)
			{
				$line_ar=array('<img src=\"images/pixel.gif\">','<img src=\"images/pixel.gif\">','<img src=\"images/pixel.gif\">','<img src=\"images/pixel.gif\">','<img src=\"images/pixel.gif\">','<img src=\"images/pixel.gif\">','<img src=\"images/pixel.gif\">','<img src=\"images/pixel.gif\">');
				$posi_ar=array('right','left','center','center','center','center','center','center','center');
				DisplayTableRow($lcnt,$line_ar,$posi_ar);
				$lcnt++;
				
				while($rowX= mssql_fetch_array($resX))
				{
					$qryY = "exec tlh_ConversionReportSR @sid=".$rowX['securityid'].",@d1='".$_REQUEST['d1']."',@d2='".$_REQUEST['d2']." 23:59:59';";
					$resY = mssql_query($qryY);
					$rowY = mssql_fetch_array($resY);
					
					if ($rowY['Leads'] > 0)
					{
						$line_ar=array('&nbsp;&nbsp'.$rowY['SalesRep'],$rowY['Leads'],$rowY['Contracts'],$rowY['LtoC'],$rowY['Digs'],$rowY['LtoD'],$rowY['CtoD'],'');
						$posi_ar=array('left','center','center','center','center','center','center','center');
						DisplayTableRow($lcnt,$line_ar,$posi_ar);
						$lcnt++;
					}
				}
			}
			
			echo "				</table>\n";
			echo "			</td>\n";
			echo "		</tr>\n";
		}
		elseif (isset($_REQUEST['oid']) && $_REQUEST['oid']==0)
		{
			echo "		<tr>\n";
			echo "			<td colspan=\"2\" align=\"center\">\n";
			echo "				<table class=\"outer\" width=\"100%\">\n";
			echo "					<thead>\n";
			echo "					<tr class=\"tblhd\">\n";
			echo "						<td align=\"left\"><b>Office</b></td>\n";
			echo "						<td align=\"center\" title=\"Total number of Leads added to the Office in the Date Range provided\"><b>Leads</b></td>\n";
			echo "						<td align=\"center\" title=\"Total number of Contracts written in the Office in the Date Range provided\"><b>Contracts</b></td>\n";
			echo "						<td align=\"center\" title=\"Lead to Contract Ratio\"><b>LtoC %</b></td>\n";
			echo "						<td align=\"center\" title=\"Total number of Digs registered by the Office in the Date Range provided\"><b>Digs</b></td>\n";
			echo "						<td align=\"center\" title=\"Lead to Dig Ratio\"><b>LtoD %</b></td>\n";
			echo "						<td align=\"center\" title=\"Contract to Dig Ratio\"><b>CtoD %</b></td>\n";
			echo "						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
			echo "					</tr>\n";
			echo "					</thead>\n";
			echo "					<tbody>\n";
			
			$lcnt=1;
			foreach ($oid_ar as $n1 => $v1)
			{
				$qryZ = "exec tlh_ConversionReport @oid=".$n1.",@d1='".$_REQUEST['d1']."',@d2='".$_REQUEST['d2']." 23:59:59';";
				$resZ = mssql_query($qryZ);
				$rowZ = mssql_fetch_array($resZ);
				
				if ($rowZ['Leads'] > 0)
				{
					if (isset($rowZ['Contracts']) && $rowZ['Contracts'] > 0)
					{
						$ldrill ="         		        <form method=\"post\">\n";
						$ldrill.="						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
						$ldrill.="						<input type=\"hidden\" name=\"call\" value=\"Conversion\">\n";
						$ldrill.="						<input type=\"hidden\" name=\"subq\" value=\"Office\">\n";
						$ldrill.="						<input type=\"hidden\" name=\"oid\" value=\"".$n1."\">\n";
						$ldrill.="						<input type=\"hidden\" name=\"d1\" value=\"".$_REQUEST['d1']."\">\n";
						$ldrill.="						<input type=\"hidden\" name=\"d2\" value=\"".$_REQUEST['d2']."\">\n";
						$ldrill.="                      <input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Detail\"></td>\n";
						$ldrill.="						</form>\n";
					}
					else
					{
						$ldrill='';
					}
					
					$line_ar=array($rowZ['Office'],$rowZ['Leads'],$rowZ['Contracts'],$rowZ['LtoC'],$rowZ['Digs'],$rowZ['LtoD'],$rowZ['CtoD'],$ldrill);
					$posi_ar=array('left','center','center','center','center','center','center','center');
					DisplayTableRow($lcnt,$line_ar,$posi_ar);
					$lcnt++;
				}
			}
			
			echo "					</tbody>\n";
			echo "				</table>\n";
			echo "				</div>\n";
			
			echo "			</td>\n";
			echo "		</tr>\n";
		}
	}
    
	echo "</table>\n";
	
	//display_array($_REQUEST);
}

function DisplayTableRow($i,$d_ar,$p_ar)
{	
	if (count($d_ar)==count($p_ar))
	{
		if ($i%2)
		{
			$tbg='even';
		}
		else
		{
			$tbg='odd';
		}
		
		echo "		<tr class=\"".$tbg."\">\n";
		
		foreach ($d_ar as $dO=>$vO)
		{
			echo "			<td align=\"".$p_ar[$dO]."\">".$vO."</td>\n";
		}

		echo "		</tr>\n";
	
	}
}

function ShowEmailSendLog()
{

	//echo "<iframe src=\"subs/EmailTrackerAdmin.php\" frameborder=\"0\" width=\"1024px\" height=\"500px\"></iframe>\n";
	$sdate=date('m/d/Y',(time() - 1296000));
	
	
		$qryZ = "
		SELECT
			E.sdate,
			E.emailaddr,
			E.emailaddrfrom,
			E.efile,
			(select clname from jest..cinfo where cid=E.cid) as clname,
			(select cfname from jest..cinfo where cid=E.cid) as cfname,
			(select lname from jest..security where securityid=E.uid) as slname,
			(select fname from jest..security where securityid=E.uid) as sfname,
			(select name from jest..offices where officeid=E.oid) as oname,
			(select name from jest..EmailTemplate where etid=E.tid) as tname,
			(select filename from jest..jestFileStore where docid=E.efile) as filename
		FROM jest..EmailTracking AS E
		WHERE
			sdate between '".$sdate."'
			and (getdate() + 1)
		ORDER BY sdate desc;
		";
	$resZ = mssql_query($qryZ);
	$nrowZ= mssql_num_rows($resZ);	
	//echo $qryZ.'<br>';
	
	if ($nrowZ > 0) {
		$cnt=1;
		echo "			<table class=\"outer\" align=\"center\">\n";
		echo "				<tr>\n";
		echo "  	        	<td class=\"gray\" align=\"center\" colspan=\"3\"><b>Outgoing Email Log: ". $sdate ." to Present</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr class=\"tblhd\">\n";
		echo "  	        	<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "  	        	<td align=\"center\">Recipient</td>\n";
		echo "  	        	<td align=\"center\">Sender</td>\n";
		echo "				</tr>\n";
		
		while ($rowZ = mssql_fetch_array($resZ)) {
			$tbg	=($cnt%2)?'even':'odd';
			$efile	=($rowZ['efile']!=0)?$rowZ['filename']:'';
			echo "			<tr class=\"".$tbg."\" >\n";
			echo "				<td align=\"right\" valign=\"top\">\n";
			
			echo $cnt++;
			
			echo "				.</td>\n";
			echo "				<td align=\"left\" valign=\"top\">\n";
			echo "					<table>\n";
			echo "						<tr>\n";
			echo "  	    		    	<td align=\"left\" width=\"40px\">Date:</td>\n";
			echo "  	    		    	<td align=\"left\">\n";
			
			echo date('m/d/y G:i a',strtotime($rowZ['sdate']));
			
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "						<tr>\n";
			echo "  	    		    	<td align=\"left\" width=\"40px\">Lead:</td>\n";
			echo "  	    		    	<td align=\"left\">\n";
			
			echo $rowZ['clname'].', '.$rowZ['cfname'];
			
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "						<tr>\n";
			echo "  	    		    	<td align=\"left\" width=\"40px\">Email:</td>\n";
			echo "  	    		    	<td align=\"left\">\n";
			
			echo $rowZ['emailaddr'];
			
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "						<tr>\n";
			echo "  	    		    	<td align=\"left\" width=\"40px\">File:</td>\n";
			echo "  	    		    	<td align=\"left\">\n";
			
			echo $efile;
			
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "					</table>\n";
			echo "				</td>\n";
			echo "				<td align=\"left\" valign=\"top\">\n";
			echo "					<table>\n";
			echo "						<tr>\n";
			echo "  	    		    	<td align=\"left\" width=\"50px\">Sender</td>\n";
			echo "  	    		    	<td align=\"left\">\n";
			
			echo $rowZ['slname'].', '.$rowZ['sfname'];
			
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "						<tr>\n";
			echo "  	    		    	<td align=\"left\" width=\"50px\">Office</td>\n";
			echo "  	    		    	<td align=\"left\">\n";
			
			echo $rowZ['oname'];
			
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "						<tr>\n";
			echo "  	    		    	<td align=\"left\" width=\"50px\">Email</td>\n";
			echo "  	    		    	<td align=\"left\">\n";
			
			echo $rowZ['emailaddrfrom'];
			
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "						<tr>\n";
			echo "  	    		    	<td align=\"left\" width=\"50px\">Template</td>\n";
			echo "  	    		    	<td align=\"left\">\n";
			
			echo $rowZ['tname'];
			
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "					</table>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
			//$cnt++;
		}
		
		echo "			</table>\n";
	}
}

function LeadSourceReportMain()
{
	error_reporting(E_ALL);
	global $retar;
    
    $qryALTo = "SELECT securityid,altoffices,officeid FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resALTo = mssql_query($qryALTo);
	$rowALTo = mssql_fetch_array($resALTo);

	if ($rowALTo['altoffices']!=0)
	{
		$alto=explode(",",$rowALTo['altoffices']);
	}

	$rdate = date("m-d-Y", time());
	echo "<table align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
	echo "         <table class=\"outer\" width=\"100%\">\n";
	echo "   			<tr>\n";
	echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Lead Source Report</b></td>\n";
	echo "      			<td class=\"gray\" align=\"right\">\n";
	echo "         			<table width=\"100%\">\n";
	echo "         			<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"sleads\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"drange\">\n";
	echo "   						<tr>\n";
	echo "      						<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";

	if (!empty($_REQUEST['d1']))
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\" value=\"".$_REQUEST['d1']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
	}

	if (!empty($_REQUEST['d2']))
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['d2']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
	}


	echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
	echo "      						</td>\n";
	echo "      						<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
	echo "      						</td>\n";
	echo "   						</tr>\n";
	echo "         				</form>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";

	if (!empty($_REQUEST['subq']) && $_REQUEST['subq']=="drange")
	{
        if ($_SESSION['rlev'] >= 5)
        {
            if (empty($_REQUEST['subq']))
            {
                //$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1';";
                $qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' and source in (select statusid from leadstatuscodes where ivr=0);";
            }
            elseif ($_REQUEST['subq']=="drange")
            {
                if (empty($_REQUEST['d2']))
                {
                    //$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added='".$_REQUEST['d1']."';";
                    $qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND added='".$_REQUEST['d1']."' and source in (select statusid from leadstatuscodes where ivr=0);";
                }
                else
                {
                    //$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
                    $qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' and source in (select statusid from leadstatuscodes where ivr=0);";
                }
            }
        }
        else
        {
            echo "<b>You do not have appropriate Access to View this Resource.</b>";
            exit;
        }
    
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);
        $nrow1= mssql_num_rows($res1);
    
        $qrypre1 = "SELECT statusid,name FROM leadstatuscodes WHERE active='2' and statusid!='1' and statusid!='0' and ivr=0;"; // Source Code =2
        $respre1 = mssql_query($qrypre1);
    
        while ($rowpre1 = mssql_fetch_array($respre1))
        {
            $srccodes[]=$rowpre1['statusid'];
        }
            
		if ($row1['cnt'] > 0)
		{
			echo "   			<tr>\n";
			echo "      			<td colspan=\"2\" class=\"gray\" align=\"left\" valign=\"top\">\n";
			echo "         			<table width=\"100%\">\n";

			if ($_SESSION['rlev'] >=5)
			{
				$qry4 = "SELECT officeid,name FROM offices WHERE active=1 and finan_off=0 ORDER BY grouping,name;";
				$res4 = mssql_query($qry4);

				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">&nbsp<b>Office</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">Total</td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">Internet</td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">Manual</td>\n";


				foreach($srccodes as $n1 => $v1)
				{
					$qryST = "SELECT name FROM leadstatuscodes WHERE statusid='".$v1."';";
					$resST = mssql_query($qryST);
					$rowST = mssql_fetch_array($resST);

					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">".$rowST['name']."</td>\n";
				}

				echo "   			</tr>\n";

				$ocon	=0;
				$oicon=0;
				$omcon=0;
				//$o_ar	=array();
				
				foreach($srccodes as $nI => $vI)
				{
					$o_ar[]=0;
				}

				while ($row4 = mssql_fetch_array($res4))
				{
					if ($_SESSION['rlev'] >=8) // Anyone with Report Level 8+
					{
						$tt="1";
						leads_gen_sub($srccodes,$row4['officeid'],$row4['name']);
						$ocon=$ocon+$retar[0];
						$oicon=$oicon+$retar[1];
						$omcon=$omcon+$retar[2];
						
						//echo $row4['name']." : ";
						//echo count($retar[3])."<br>";
						foreach($srccodes as $nX => $vX)
						{
							if (is_array($retar[3]))
							{
								$o_ar[$nX]=$o_ar[$nX]+$retar[3][$nX];
							}
							else
							{
								$o_ar[$nX]=$o_ar[$nX]+0;
							}
						}
					}
					elseif ($_SESSION['rlev'] >=7 && in_array($row4['officeid'],$alto)) // Anyone with Report Level 7+
					{
						$tt="2";
						leads_gen_sub($srccodes,$row4['officeid'],$row4['name']);
						$ocon=$ocon+$retar[0];
						$oicon=$oicon+$retar[1];
						$omcon=$omcon+$retar[2];
						
						if (is_array($retar[3]))
						{
							//echo $row4['name']." : ";
							//print_r($retar[3])."<br>";
							foreach($srccodes as $nX => $vX)
							{
								if (isset($retar[3][$nX]))
								{
									$o_ar[$nX]=$o_ar[$nX]+$retar[3][$nX];
								}
							}
						}
					}
					elseif ($_SESSION['rlev'] >=5 && $row4['officeid']==$_SESSION['officeid']) // Anyone with Report Level 5+
					{
						$tt="3";
						leads_gen_sub($srccodes,$row4['officeid'],$row4['name']);
						$ocon=$ocon+$retar[0];
						$oicon=$oicon+$retar[1];
						$omcon=$omcon+$retar[2];
						
						if (is_array($retar[3]))
						{
							//echo $row4['name']." : ";
							//print_r($retar[3])."<br>";
							foreach($srccodes as $nX => $vX)
							{
								//echo "<br>";
								//print_r($retar[3]);
								//echo "<br>";
								if (isset($retar[3][$nX]))
								{
									$o_ar[$nX]=$o_ar[$nX]+$retar[3][$nX];
								}
							}
						}
					}
					/*
					echo "   			<tr>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp<b>Test Total($tt)(".$row4['name'].")</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$ocon."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$oicon."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$omcon."</b></td>\n";
					echo "   			</tr>\n";
					*/
				}

				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp<b>Total</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$ocon."</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$oicon."</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$omcon."</b></td>\n";

				foreach($srccodes as $n1 => $v1)
				{
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>".$o_ar[$n1]."</b></td>\n";
				}

				echo "   			</tr>\n";
			}

			echo "						</table>\n";
			echo "					</td>\n";
			echo "   			</tr>\n";
		}
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function masempfinconsolidated()
{
    if ($_SESSION['securityid']!=26)
    {
        exit;
    }
    
    $qry0a = "SELECT psdate,pedate,brept_yr,active FROM [jest].[dbo].[bonus_schedule_config] order by brept_yr desc;";
    $res0a = mssql_query($qry0a);
    $nrow0a= mssql_num_rows($res0a);
    
    echo "<h2><font color=\"#8B0000\">NOTE: This Report is in Development. Not released for Production use.</font></h2>\n";
	echo "	<table align=\"center\" width=\"975\">\n";
	echo "		<tr>\n";
	echo "			<td>\n";
	echo "				<table class=\"outer\" width=\"100%\" align=\"right\">\n";
	echo "					<tr>\n";
    echo "                      <td class=\"gray\" align=\"left\"><b>Blue Haven Sales & Commission Report</b></td>\n";
	echo "                      <td align=\"right\" class=\"gray\">Key</td>\n";
    //echo "                      <td align=\"center\" class=\"wh_und\" width=\"100\"><b>no Addn(s)</b></td>\n";
	echo "                      <td align=\"center\" class=\"blu_undsidesb\" width=\"100\"><b>has Addn(s)</b></td>\n";
    echo "                      <td align=\"center\" class=\"ltgrn_undsidesb\" width=\"100\"><b>Draw</b></td>\n";
    echo "						<td class=\"gray\" align=\"right\"><b>Print Date</b> \n";
    
    echo date('m/d/y g:iA T',time());
    
    echo "                      </td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
}

function OfficePipelineReport()
{
	
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
    $acclist =explode(",",$_SESSION['aid']);
	$tbc='tan_undsidesb';
	$sales_yr=array();
	
	$qry0a1 = "SELECT brept_yr,active FROM [jest].[dbo].[bonus_schedule_config] order by brept_yr desc;";
    $res0a1 = mssql_query($qry0a1);
    $nrow0a1= mssql_num_rows($res0a1);

	while ($row0a1 = mssql_fetch_array($res0a1))
	{
		$sales_yr[]=array(
						  'brept_yr'=>$row0a1['brept_yr'],
						  'active'=>$row0a1['active']
						  );
	}
    
	$qry0a2 = "SELECT psdate,pedate,brept_yr,active FROM [jest].[dbo].[bonus_schedule_config] where active = 1;";
    $res0a2 = mssql_query($qry0a2);
	$row0a2 = mssql_fetch_array($res0a2);
    $nrow0a2= mssql_num_rows($res0a2);
	
	//display_array($row0a2);
	
    $qry0b = "SELECT securityid,officeid,conspiperpt FROM [jest].[dbo].[security] where securityid=".$_SESSION['securityid'].";";
    $res0b = mssql_query($qry0b);
    $row0b = mssql_fetch_array($res0b);

    if ($row0b['conspiperpt'] >= 6)
    {
		$qry0 = "
		SELECT 
			S.securityid,S.officeid,S.lname,S.fname,S.slevel,
			(select name from jest..offices where officeid=S.officeid) as oname,
			(select count([id]) from secondaryids where secid=S.securityid) as scnt
		FROM 
			security as S 
		WHERE 
			S.officeid=".$_SESSION['officeid']." 
			and S.srep=1
		order by SUBSTRING(S.slevel,13,1) DESC,S.lname ASC;
		";
        $res0 = mssql_query($qry0);
        $nrow0= mssql_num_rows($res0);
    }
    else
    {
        echo "<b>You do not have the appropriate security level to view this resource</b>";
        exit;
    }
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_pipline.js\"></script>\n";
	echo "	<table class=\"transnb\" align=\"center\" width=\"950px\">\n";
	echo "		<tr>\n";
	echo "			<td colspan=\"2\" align=\"center\">\n";
	echo "				<div class=\"noPrint\">\n";
	echo "				<table class=\"outer\" width=\"100%\">\n";
	echo "					<tr>\n";
	echo "						<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo"						</td>\n";
	echo "						<td class=\"gray\" align=\"right\">\n";
	echo "							<table class=\"transnb\">\n";

    if ($nrow0 > 0)
    {
		echo "							<tr>\n";
        echo "         		        			<form method=\"post\">\n";
        echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
        echo "									<input type=\"hidden\" name=\"call\" value=\"officepipeline\">\n";
        echo "									<input type=\"hidden\" name=\"stg\" value=\"2\">\n";
		
		//if ($_SESSION['securityid']==26 || $_SESSION['securityid']==1950 || $_SESSION['securityid']==332)
		//{
		echo "								<td class=\"gray\" align=\"right\"><b>Sales Yr</b></td>\n";
        echo "								<td class=\"gray\" align=\"left\">\n";
		echo "									<select class=\"JMStooltip\" name=\"syr\" title=\"Select the Sales Year\">\n";
		
		foreach ($sales_yr as $sn => $sv)
		{
			if (isset($_REQUEST['syr']) and $_REQUEST['syr'] > 0)
			{
				if ($_REQUEST['syr']==$sv['brept_yr'])
				{
					echo "<option value=\"".$sv['brept_yr']."\" SELECTED>".$sv['brept_yr']."</option>";
				}
				else
				{
					echo "<option value=\"".$sv['brept_yr']."\">".$sv['brept_yr']."</option>";
				}
			}
			else
			{
				if ($sv['active']==1)
				{
					echo "<option value=\"".$sv['brept_yr']."\" SELECTED>".$sv['brept_yr']."</option>";
				}
				else
				{
					echo "<option value=\"".$sv['brept_yr']."\">".$sv['brept_yr']."</option>";
				}
			}
		}
		
		echo "									</select>\n";
		echo"								</td>\n";
		//}
		
        echo "								<td class=\"gray\" align=\"left\"><b>Sales Rep</b></td>\n";
        echo "								<td class=\"gray\" align=\"right\">\n";
        echo "									<select class=\"JMStooltip\" name=\"sid\" onChange=\"this.form.submit();\" title=\"Select View All or an individual Sales Rep\">\n";
		
		if ($row0b['conspiperpt'] >= 6)
        {
			echo "  										<option value=\"0\">View All</option>\n";
		}

        while ($row0 = mssql_fetch_array($res0))
        {
            if ($_SESSION['rlev'] >= 9)
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

                if (isset($_REQUEST['sid']) && $_REQUEST['sid']==$row0['securityid'])
                {
                    echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row0['lname'].", ".$row0['fname']." (".$row0['oname'].") (".$row0['securityid'].")</option>\n";
                }
                else
                {
                    echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\">".$row0['lname'].", ".$row0['fname']." (".$row0['oname'].") (".$row0['securityid'].")</option>\n";
                }
            }
            else
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
    
                    if (isset($_REQUEST['sid']) && $_REQUEST['sid']==$row0['securityid'])
                    {
                        echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row0['lname'].", ".$row0['fname']." (".$row0['oname'].")</option>\n";
                    }
                    else
                    {
                        echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\">".$row0['lname'].", ".$row0['fname']." (".$row0['oname'].")</option>\n";
                    }
                }
            }
        }

        echo "										</select>\n";
        echo "                                  </td>\n";
		echo "									<td class=\"gray\" align=\"right\">\n";
		echo "									</td>\n";
        echo "                                  <td><input class=\"transnb_button\" type=\"image\" src=\"images/arrow_refresh_small.png\" title=\"Refresh\"></td>\n";
		echo "					            </tr>\n";
        echo "                      </form>\n";
    }
    else
    {
		echo "							<tr>\n";
        echo "								<td class=\"gray\" align=\"right\" valign=\"top\"><b>No Sales Reps in this Company</b></td>\n";
		echo "					        </tr>\n";
    }
    
	echo "				            </table>\n";
    echo "                      </td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
    
	if ($nrow0 > 0)
	{
		if (isset($_REQUEST['syr']) and $_REQUEST['syr'] > 0)
		{
			//Selected Sales Year
			$qryApre = "SELECT psdate,pedate,brept_yr,active FROM [jest].[dbo].[bonus_schedule_config] where brept_yr = ".$_REQUEST['syr'].";";
			$resApre = mssql_query($qryApre);
			$rowApre = mssql_fetch_array($resApre);
			$nrowApre= mssql_num_rows($resApre);

			if ($nrowApre > 0)
			{
				$sdate=array('psdate'=>date('m/d/Y',strtotime($rowApre['psdate'])),'pedate'=>date('m/d/Y',strtotime($rowApre['pedate'])).' 23:59:59');
			}
			else
			{
				echo '<tr><td colspan="2">No Sales Year Configured. Exiting....</td></tr>';
				echo '</table>';
				exit;
			}
			
		}
		else
		{
			// System Active Sales Year
			if ($nrow0a2 > 0)
			{
				$sdate=array('psdate'=>date('m/d/Y',strtotime($row0a2['psdate'])),'pedate'=>date('m/d/Y',strtotime($row0a2['pedate'])).' 23:59:59');
			}
			else
			{
				echo '<tr><td colspan="2">No Default Sales Year Configured. Exiting....</td></tr>';
				echo '</table>';
				exit;
			}
		}
		
		$qryA =
		"
			declare @oid int
			declare @sid int
			declare @d1 datetime
			declare @d2 datetime
			set @oid=".$_SESSION['officeid']."
			set @d1='".$sdate['psdate']."'
			
		";
		
		if (date('Y',strtotime($sdate['pedate']))==date('Y'))
		{
			$qryA .="set @d2=getdate()";
		}
		else
		{
			$qryA .="set @d2='".$sdate['pedate']."'";
		}
		
		$qryA .="
			--set @d1='12/1/2009'
			--set @d2='12/1/2009'
			
			select 
				J1.jobid,
				J1.njobid,
				J1.securityid,
				(select clname + ', ' + cfname from jest..cinfo where jobid=J1.jobid) as Customer,
				(select cid from jest..cinfo where jobid=J1.jobid) as cid,
				J1.renov,
				(select lname + ', ' + fname from jest..security where securityid=J1.securityid) as SalesRep,
				cast(J2.contractamt as money) as contractamt,
				(select recdate from jest..tfinan_detail where cid=(select cid from jest..cinfo where jobid=J1.jobid)) as frecdate,
				(select financlose from jest..tfinan_detail where cid=(select cid from jest..cinfo where jobid=J1.jobid)) as fclosed,
				J2.contractdate,
				J1.digdate,
				J2.updated
			from 
				jest..jobs as J1
			inner join 
				jest..jdetail as J2
			on
				J1.jobid=J2.jobid
			where
				J2.jadd=0
				
		";
		
		if (isset($_REQUEST['sid']) && $_REQUEST['sid']!=0)
		{
			$qryA .= " and J1.securityid=".$_REQUEST['sid']." ";
		}
		else
		{
			$qryA .= "
			
				and J1.officeid=@oid
				
			";
		}
		
		$qryA .= "
		
				and J1.digdate is NULL 
				--and J2.contractdate >= @d1
				and J2.contractdate between @d1 and @d2
			order by
				--SalesRep asc,
				J2.contractdate asc
				
		";
        $resA = mssql_query($qryA);
        $nrowA= mssql_num_rows($resA);
		
        echo "		<tr>\n";
        echo "			<td colspan=\"2\">\n";
        echo "				<table class=\"outer\" width=\"100%\" align=\"right\">\n";
        echo "					<tr>\n";
		echo "						<td class=\"gray\" align=\"left\">\n";
		echo "							<b>Pipeline Report</b> ";
		echo "							<img class=\"JMStooltip\" src=\"images/help.png\" title=\"This Report displays all Contracts and/or Jobs in the JMS not flagged with a Dig Date<br>Defaults to the current Sales Year as defined by BHNM<br>Select a Sales Year to the right to view a previous Sales Year and/or a specific Sales Rep<br>Click the Refresh icon when selecting a different Sales Year\">\n";
		echo "						</td>\n";
        echo "						<td class=\"gray\" align=\"right\"><b>Print Date</b> \n";
        
        ?>
        
        <script type="text/javascript">
            setLocalTime();
        </script>
        
        <?php

        echo "                      </td>\n";
        echo "					</tr>\n";
        echo "				</table>\n";
        echo "			</td>\n";
        echo "		</tr>\n";
        echo "		<tr>\n";
        echo "			<td colspan=\"2\">\n";
        echo "				<table class=\"outer\" width=\"100%\" align=\"right\">\n";
        echo "					<tr>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"55\"><b>Contract Date</b></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"45\"><b><div class=\"JMStooltip\" title=\"Missing Job # indicates only Contract entered in JMS but no Job # was assigned\">Job #</div></b></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"125\"><b>Customer</b></td>\n";
		echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"90\"><b>SalesRep</b></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"60\"><b><div class=\"JMStooltip\" title=\"Contract Amount not including Addendums or Adjusts\">Contract Amt<div></b></td>\n";
		echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"30\"><b>Renov</b></td>\n";
		echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"55\"><b><div class=\"JMStooltip\" title=\"Date of Last Update to Contract Breakdown or Job Breakdown\">Last Update</div></b></td>\n";
		echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"55\"><b><div class=\"JMStooltip\" title=\"Date Finance Information was received\">Fin Recvd</div></b></td>\n";
		echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"30\"><b><div class=\"JMStooltip\" title=\"Date Financing Closed\">Fin Clsd</div></b></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
		echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
        echo "					</tr>\n";
		
		if ($nrowA > 0)
        {
			$cnt=0;
			while ($rowA = mssql_fetch_array($resA))
			{
				$cnt++;
				echo "					<tr>\n";
                echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/Y',strtotime($rowA['contractdate']))."</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
				
				if (isset($rowA['njobid']) and $rowA['njobid']!='0')
				{
					echo $rowA['njobid'];
				}
				
				echo "						</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"left\">".$rowA['Customer']."</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"left\">".$rowA['SalesRep']."</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($rowA['contractamt'],2,'.',',')."</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
				
				if (isset($rowA['renov']) and $rowA['renov']==1)
				{
					echo 'Yes';
				}
				
				echo "						</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/Y',strtotime($rowA['updated']))."</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"center\">";
				
				if (strtotime($rowA['frecdate']) >= strtotime('1/1/2000'))
				{
					echo date('m/d/Y',strtotime($rowA['frecdate']));
				}
				
				echo "						</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
				
				if (isset($rowA['fclosed']) and $rowA['fclosed']==1)
				{
					echo 'Yes';
				}
				
				echo "						</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
				
				if ($_SESSION['jlev'] >= 6)
				{
					if ($rowA['njobid']=='0')
					{
						echo "					<form method=\"POST\">\n";
						echo "					    <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
						echo "					    <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
						echo "					    <input type=\"hidden\" name=\"jobid\" value=\"".$rowA['jobid']."\">\n";
						echo "					    <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
						echo "					    <input class=\"transnb JMStooltip\" type=\"image\" src=\"images/folder_open.gif\" title=\"View Contract\">\n";
						echo "					</form>\n";
					}
					else
					{
						echo "					<form method=\"POST\">\n";
						echo "					    <input type=\"hidden\" name=\"action\" value=\"job\">\n";
						echo "					    <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
						echo "					    <input type=\"hidden\" name=\"njobid\" value=\"".$rowA['njobid']."\">\n";
						echo "					    <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
						echo "					    <input class=\"transnb JMStooltip\" type=\"image\" src=\"images/folder_open.gif\" title=\"View Job\">\n";
						echo "					</form>\n";
					}
				}
				
				echo "						</td>\n";
				echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
				
				if ($_SESSION['jlev'] >= 6)
				{
					echo "						<form method=\"post\">\n";
					echo "							<input type=\"hidden\" name=\"action\" value=\"job\">\n";
					echo "							<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
					echo "							<input type=\"hidden\" name=\"cid\" value=\"".$rowA['cid']."\">\n";
					echo "					    	<input class=\"transnb JMStooltip\" type=\"image\" src=\"images/application_view_list.png\" title=\"View OneSheet\">\n";
					echo "						</form>\n";
				}
				
				echo "						</td>\n";
				echo "					</tr>\n";
			}
        }
        else
        {
            echo "					<tr>\n";
            echo "                      <td colspan=\"16\" class=\"wh_und\" align=\"center\"><b>No Records Found</b></td>\n";
            echo "					</tr>\n";
        }
        
        echo "				</table>\n";
        echo "			</td>\n";
        echo "		</tr>\n";
	}
	
    echo "	</table>\n";
	echo "</div>";
}

function activity_job_full()
{
    error_reporting(E_ALL);    
    if (isset($_REQUEST['d1']) && strtotime($_REQUEST['d1']) >= strtotime('1/1/2002'))
	{
        $sdate=array(date("m/d/Y",strtotime($_REQUEST['d1'])),date("m/d/Y",strtotime($_REQUEST['d2'])),date("m/d/Y",time()));
    }
    else
    {
        $sdate=array(date("m/01/Y",(time()-2592000)),date("m/d/Y",time()),date("m/d/Y",time()));
        //$sdate=array('12/1/07',date("m/d/Y",time()));
    }
    
    //$sdate[]=date("m/d/Y",time());
    
	if (isset($_REQUEST['reno']) && $_REQUEST['reno']==1)
	{
		$reno=$_REQUEST['reno'];
	}
	else
	{
		$reno=0;
	}
	
	$qry0 = "SELECT name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	if ($_SESSION['tlev'] >= 8)
	{
		$qry1 = "SELECT altoffices FROM security WHERE securityid='".$_SESSION['securityid']."';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);

		if (!empty($row1['altoffices'])||$row1['altoffices']!=0)
		{
			if (preg_match("/,/i",$row1['altoffices']))
			{
				$offids=explode(",",$row1['altoffices']);
			}
			else
			{
				$offids=$row1['altoffices'];
			}
		}
	}
	
	$qry = "SELECT * FROM offices WHERE active=1 and grouping=0 and encon=1 ORDER by grouping,name ASC;";
	$res = mssql_query($qry);
	
    $br=0;
    
    if (isset($_REQUEST['expand']) && $_REQUEST['expand']==1)
    {
    }
    else
    {
        echo "<div id=\"masterdiv\">\n";
    }
        
	echo "<script type=\"text/javascript\" src=\"js/jquery_extend.js\"></script>\n";
	echo "<table align=\"center\" border=\"".$br."\" width=\"800px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\">\n";
	echo "			<table class=\"outer\" width=\"100%\" border=\"".$br."\" >\n";
    echo "				<tr>\n";
	echo "					<td colspan=\"5\" align=\"right\" class=\"gray\"><b>Report Date:</b> ".$sdate[2]."</td>\n";
    echo "				</tr>\n";
    echo "				<tr>\n";
	echo "         		<form  method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"activity_job_full\">\n";
    echo "						<input type=\"hidden\" name=\"subq\" value=\"stage2\">\n";
    //echo "						<input type=\"hidden\" name=\"print\" value=\"1\">\n";
	echo "					<td align=\"left\" class=\"gray\"><b>Lead - Contract - Dig Activity</b></td>\n";
	echo "					<td align=\"center\" class=\"gray\">Date Range <input class=\"bboxbc\" type=\"text\" name=\"d1\" id=\"d1\" value=\"".$sdate[0]."\" size=\"11\" maxlength=\"10\"> - <input class=\"bboxbc\" type=\"text\" name=\"d2\" id=\"d2\" value=\"".$sdate[1]."\" size=\"11\" maxlength=\"10\"></td>\n";
    echo "					<td align=\"left\" class=\"gray\">Expand\n";
    
    if (isset($_REQUEST['expand']) && $_REQUEST['expand']==1)
    {
        echo "						<input class=\"transnb\" type=\"checkbox\" name=\"expand\" value=\"1\" CHECKED title=\"Check this box to view in Expanded Mode\">\n";
    }
    else
    {
        echo "						<input class=\"transnb\" type=\"checkbox\" name=\"expand\" value=\"1\" title=\"Check this box to view in Expanded Mode\">\n";
    }
    
    echo "                  </td>\n";
	echo "					<td align=\"right\" valign=\"top\" class=\"gray\">\n";
	echo "   					<select name=\"oid\">\n";

	if ($_SESSION['rlev'] >= 9)
	{
		echo "   		<option value=\"0\">All Offices</option>\n";
	}
	
    if ($_SESSION['tlev'] >= 8)
    {
        while ($row = mssql_fetch_array($res))
        {
            if ($_SESSION['officeid']==$row['officeid'] || !empty ($_REQUEST['oid']) && $_REQUEST['oid']==$row['officeid'])
            {
                echo "   		<option value=\"".$row['officeid']."\" SELECTED>".$row['name']."</option>\n";
            }
            else
            {
                echo "   		<option value=\"".$row['officeid']."\">".$row['name']."</option>\n";
            }
        }
    }
    else
    {
        echo "   		<option value=\"".$_SESSION['officeid']."\">".$_SESSION['offname']."</option>\n";
    }

	echo "   					</select>\n";
	echo "					</td>\n";
    echo "					<td align=\"center\" class=\"gray\">\n";
    echo "                      <input class=\"transnb\" type=\"image\" src=\".\closeup.gif\" alt=\"Refresh\">\n";
    echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
    
    if (isset($_REQUEST['subq']) && $_REQUEST['subq']=="stage2")
    {
        $qryA   ="select [coid] ";
        $qryA  .="      ,[j1oid] ";
        $qryA  .="      ,[oname] ";
        $qryA  .="      ,[cid] ";
        $qryA  .="      ,[clname] ";
        $qryA  .="      ,[cfname] ";
        $qryA  .="      ,[caddr1] ";
        $qryA  .="      ,[securityid] ";
        $qryA  .="      ,[masdiv] ";
        $qryA  .="      ,[sidm] ";
        $qryA  .="      ,[salesrep] ";
        $qryA  .="      ,[salesmanager] ";
        $qryA  .="      ,[cadded] ";
        $qryA  .="      ,[estid] ";
        $qryA  .="      ,[eadded] ";
        $qryA  .="      ,[cjobid] ";
        $qryA  .="      ,[j1jobid] ";
        $qryA  .="      ,[contrdate] ";
        //$qryA  .="      ,[contramt] ";
        $qryA  .="      ,[j1jcost] ";
        $qryA  .="      ,[cnjobid] ";
        $qryA  .="      ,[j1njobid] ";
        $qryA  .="      ,[j1added] ";
        $qryA  .="      ,[digdate] ";
        $qryA  .="      ,[renov] ";
        $qryA  .="from [jest].[dbo].[job_disp] ";
        $qryA  .="where ";
        
        if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
        {
            $qryA  .="coid=".$_REQUEST['oid']." and ";
            $qryA  .="j1oid=".$_REQUEST['oid']." and ";
        }
        elseif ($_SESSION['officeid'] != 89)
        {
            $qryA  .="coid=".$_SESSION['officeid']." and ";
            $qryA  .="j1oid=".$_SESSION['officeid']." and ";
        }
        
        $qryA  .= "j1jobid!='0' and ";
        $qryA  .= "renov = ".$reno." and ";	
        $qryA  .= "contrdate >= '".$sdate[0]."' and ";
        $qryA  .= "contrdate < '".$sdate[1]." 23:59:59' ";
        
        $qryA  .= "order by ";
        
        if (isset($_REQUEST['order']) && strlen($_REQUEST['order']) > 3)
        {
            $qryA  .= "oname,masdiv,".$_REQUEST['order']." ";
        }
        else
        {
            $qryA  .= "oname,masdiv,contrdate ";
        }
        
        if (isset($_REQUEST['dir']) && $_REQUEST['dir']=='desc')
        {
            $qryA  .= " desc ";
        }
        else
        {
            $qryA  .= " asc ";
        }
        
        $qryA  .= ";";
        $resA  = mssql_query($qryA);
        $nrowA = mssql_num_rows($resA);
        
        
        //echo $qryA."<br>";
        
        echo "  <tr>\n";
        echo "      <td align=\"center\" valign=\"top\">\n";
        echo "			<table class=\"outer\" width=100% border=\"".$br."\" >\n";
        
        if ($nrowA > 0)
        {
            $oidt=0;
            $digs=0;
            $ccnt=0;
            $adds=0;
            $ltot=0;
            $ctot=0;
            $jtot=0;
            $dtot=0;
            while ($rowA = mssql_fetch_array($resA))
            {
                $uid  =md5(session_id().".".time().".".$rowA['cid']).".".$_SESSION['securityid'];
                if ($oidt!=$rowA['j1oid'])
                {
                    $ccnt=0;
                    // Table Close
                    if ($oidt!=0)
                    {
                        echo "			            </table>\n";
                        
                        if (isset($_REQUEST['expand']) && $_REQUEST['expand']==1)
                        {
                        }
                        else
                        {
                            echo "                  </span>\n";
                        }
                        
                        echo "                  </td>\n";
                        echo "				</tr>\n";
                        
                        if (isset($_REQUEST['expand']) && $_REQUEST['expand']==1)
                        {
                            echo "<p style=\"page-break-before: always\">\n";
                        }
                        
                    }
                    
                    echo "				<tr>\n";
                    echo "					<td class=\"gray\" align=\"left\">\n";
                    echo "                      <table border=\"".$br."\" width=\"100%\">\n";
                    echo "	            			<tr>\n";
                    echo "	            				<td class=\"gray\" align=\"left\" width=\"200px\">\n";
                    
                    if (isset($_REQUEST['expand']) && $_REQUEST['expand']==1)
                    {
                        echo "                          &nbsp<font color=\"blue\"><b>".$rowA['oname']."</b></font>\n";
                    }
                    else
                    {
                        echo "                          <div onclick=\"SwitchMenu('sub".$rowA['j1oid']."')\">&nbsp<img src=\".\plus.gif\" alt=\"Expand\">&nbsp<font color=\"blue\"><b>".$rowA['oname']."</b></font></div>\n";
                    }

                    echo "                              </td>\n";
                    echo "	            				<td class=\"gray\" align=\"right\" width=\"600px\">\n";
                    echo "                                  <table border=\"".$br."\">\n";
                    //echo "                                  <table border=\"1\">\n";
                    echo "	            		            	<tr>\n";
                    echo "	            		            		<td class=\"gray\" align=\"right\" width=\"50px\">Leads:</td>\n";
                    echo "	            		            		<td class=\"gray\" align=\"left\" width=\"75px\">&nbsp<b>\n";
                    
                    $qryA0 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$rowA['j1oid']."' AND dupe!='1' AND added BETWEEN '".$sdate[0]."' AND '".$sdate[1]." 23:59:59';";
                    $resA0  = mssql_query($qryA0);
                    $rowA0  = mssql_fetch_array($resA0);
                    
                    echo $rowA0['cnt'];
                    
                    $ltot=$ltot+$rowA0['cnt'];
                    
                    echo "	            		            		</b></td>\n";
                    echo "	            		            		<td class=\"gray\" align=\"right\" width=\"50px\">Contracts:</td>\n";
                    echo "	            		            		<td class=\"gray\" align=\"left\" width=\"75px\">&nbsp<b>\n";
                    
                    $qryAa   = "select ";
                    $qryAa  .= "	count(cid) as cnt ";
                    $qryAa  .= "from  ";
                    $qryAa  .= "	cinfo as c  ";
                    $qryAa  .= "inner join  ";
                    $qryAa  .= "	jdetail as j1 ";
                    $qryAa  .= "on  ";
                    $qryAa  .= "	c.jobid=j1.jobid  ";
                    $qryAa  .= "where  ";
                    $qryAa  .= "	c.officeid=".$rowA['j1oid']." and ";
                    $qryAa  .= "	j1.officeid=".$rowA['j1oid']." and ";
                    $qryAa  .= "	j1.jadd=0 and ";
                    $qryAa  .= "	(select renov from jobs where officeid=j1.officeid and jobid=j1.jobid)=0 and ";
                    $qryAa  .= "	j1.contractdate >= '".$sdate[0]."' and ";
                    $qryAa  .= "	j1.contractdate < '".$sdate[1]." 23:59:59' ;";
                    $resAa  = mssql_query($qryAa);
                    $rowAa  = mssql_fetch_array($resAa);
                    
                    echo $rowAa['cnt'];
                    $ctot=$ctot+$rowAa['cnt'];
                    
                    if ($rowAa['cnt']!=0)
                    {
                        $crat= round(($rowAa['cnt'] / $rowA0['cnt']) * 100);
                    }
                    else
                    {
                        $crat=0;
                    }
                    
                    echo "	            		            		</b> (".$crat."%)</td>\n";
                    /*
                    echo "	            		            		<td class=\"gray\" align=\"right\" width=\"50px\">Jobs:</td>\n";
                    echo "	            		            		<td class=\"gray\" align=\"left\" width=\"75px\">&nbsp<b>\n";
                    
                    $qryAb   = "select ";
                    $qryAb  .= "	count(cid) as cnt ";
                    $qryAb  .= "from  ";
                    $qryAb  .= "	cinfo as c  ";
                    $qryAb  .= "inner join  ";
                    $qryAb  .= "	jdetail as j  ";
                    $qryAb  .= "on  ";
                    $qryAb  .= "	c.jobid=j.jobid  ";
                    $qryAb  .= "where  ";
                    $qryAb  .= "	c.officeid=".$rowA['j1oid']." and ";
                    $qryAb  .= "	j.officeid=".$rowA['j1oid']." and ";
                    $qryAb  .= "	c.njobid!='0' and ";
                    $qryAb  .= "	j.jadd=0 and ";
                    $qryAb  .= "	(select renov from jobs where officeid=j.officeid and jobid=j.jobid)=0 and ";
                    $qryAb  .= "	j.contractdate >= '".$sdate[0]."' and ";
                    $qryAb  .= "	j.contractdate < '".$sdate[1]."' ;";
                    $resAb  = mssql_query($qryAb);
                    $rowAb  = mssql_fetch_array($resAb);
                    
                    echo $rowAb['cnt'];
                    $jtot=$jtot+$rowAb['cnt'];
                    
                    if ($rowAb['cnt']!=0)
                    {
                        $jrat= round(($rowAb['cnt'] / $rowAa['cnt']) * 100);
                    }
                    else
                    {
                        $jrat=0;
                    }
                    
                    echo "	            		            		</b> (".$jrat."%)</td>\n";
                    */
                    echo "	            		            		<td class=\"gray\" align=\"right\" width=\"50px\">Digs:</td>\n";
                    echo "	            		            		<td class=\"gray\" align=\"left\" width=\"75px\">&nbsp<b>\n";
                    
                    $qryAc   = "select ";
                    $qryAc  .= "	count(cid) as cnt ";
                    $qryAc  .= "from  ";
                    $qryAc  .= "	cinfo as c  ";
                    $qryAc  .= "inner join  ";
                    $qryAc  .= "	jdetail as j  ";
                    $qryAc  .= "on  ";
                    $qryAc  .= "	c.jobid=j.jobid  ";
                    $qryAc  .= "where  ";
                    $qryAc  .= "	c.officeid=".$rowA['j1oid']." and ";
                    $qryAc  .= "	j.officeid=".$rowA['j1oid']." and ";
                    $qryAc  .= "	c.njobid!='0' and ";
                    $qryAc  .= "	j.jadd=0 and ";
                    $qryAc  .= "	(select renov from jobs where officeid=j.officeid and jobid=j.jobid)=0 and ";
                    $qryAc  .= "	j.contractdate >= '".$sdate[0]."' and ";
                    $qryAc  .= "	j.contractdate < '".$sdate[1]." 23:59:59' and";
                    $qryAc  .= "	(select digdate from jobs where officeid=j.officeid and jobid=j.jobid) < '".$sdate[2]."' ;";
                    $resAc  = mssql_query($qryAc);
                    $rowAc  = mssql_fetch_array($resAc);
                    
                    echo $rowAc['cnt'];
                    $dtot=$dtot+$rowAc['cnt'];
                    
                    if ($rowAc['cnt']!=0)
                    {
                        $drat= round(($rowAc['cnt'] / $rowAa['cnt']) * 100);
                    }
                    else
                    {
                        $drat=0;
                    }
                    
                    //echo $qryAc."<br>";
                    echo "                                    </b> (".$drat."%)</td>\n";
                    echo "          				            </tr>\n";
                    echo "                                </table>\n";
                    echo "                              </td>\n";
                    echo "				            </tr>\n";
                    echo "                      </table>\n";
                    echo "					</td>\n";
                    echo "				</tr>\n";
                    echo "				<tr>\n";
                    echo "					<td class=\"gray_und\" align=\"left\">\n";
                    
                    if (isset($_REQUEST['expand']) && $_REQUEST['expand']==1)
                    {
                    }
                    else
                    {
                        echo "                  <span class=\"submenu\" id=\"sub".$rowA['j1oid']."\">\n";
                    }
                    
                    echo "			            <table width=100% border=\"".$br."\">\n";
                    echo "				            <tr>\n";
                    echo "					            <td class=\"ltgray_und\" align=\"left\" width=\"25px\">&nbsp</td>\n";
                    echo "			            		<td class=\"ltgray_und\" align=\"center\" width=\"80px\"><b>Job #</b></td>\n";
                    echo "			            		<td class=\"ltgray_und\" align=\"left\" width=\"10px\"></td>\n";
                    echo "				            	<td class=\"ltgray_und\" align=\"left\" width=\"200px\"><b>Customer</b></td>\n";
                    echo "		            			<td class=\"ltgray_und\" align=\"left\" width=\"225px\"><b>Sales Rep</b></td>\n";
                    echo "		            			<td class=\"ltgray_und\" align=\"center\" width=\"100px\"><b>Contract Amt</b></td>\n";
                    echo "		            			<td class=\"ltgray_und\" align=\"center\" width=\"100px\"><b></b></td>\n";
                    //echo "		            			<td class=\"ltgray_und\" align=\"center\" width=\"100px\"><b>Commisson</b></td>\n";
                    echo "			            		<td class=\"ltgray_und\" align=\"center\" width=\"125px\"><b>Contract Dt</b></td>\n";
                    echo "			            		<td class=\"ltgray_und\" align=\"center\" width=\"150px\"><b>Dig Date</b></td>";
                    echo "				            </tr>\n";
                }
                
                $qryBa = "SELECT contractamt FROM jdetail WHERE officeid='".$rowA['j1oid']."' and jobid='".$rowA['j1jobid']."' and jadd=0;";
                $resBa = mssql_query($qryBa);
                $rowBa = mssql_fetch_array($resBa);
                
                $qryBb = "SELECT raddnpr FROM jdetail WHERE officeid='".$rowA['j1oid']."' and jobid='".$rowA['j1jobid']."' and jadd!=0;";
                $resBb = mssql_query($qryBb);
                $nrowBb= mssql_num_rows($resBb);
                
                //$tconn=$rowBa['contractamt']+$rowBb['taddamt'];
                
                if ($nrowBb > 0)
                {
                    $sadds=0;
                    while ($rowBb = mssql_fetch_array($resBb))
                    {
                        $sadds=$sadds+$rowBb['raddnpr'];
                    }
                    
                    $tconn=$rowBa['contractamt']+$sadds;
                }
                else
                {
                    $tconn=$rowBa['contractamt'];
                }
                
                //$tconn=$rowBa['contractamt'];
                
                $qryCa = "SELECT (comm + ovcommission) as tcomm FROM jobs as j1 WHERE officeid='".$rowA['j1oid']."' and jobid='".$rowA['j1jobid']."' and jadd=0;";
                $resCa = mssql_query($qryCa);
                $rowCa = mssql_fetch_array($resCa);
                
                //$qryCa = "SELECT bpcomm FROM jdetail WHERE officeid='".$rowA['j1oid']."' and jobid='".$rowA['j1jobid']."' and jadd=0;";
                //$resCa = mssql_query($qryCa);
                //$rowCa = mssql_fetch_array($resCa);
                
                $tcomm = $rowCa['tcomm'];
                
                $ccnt++;
                echo "				            <tr>\n";
                echo "			        		    <td class=\"wh_und\" align=\"right\" width=\"25px\">".$ccnt.".</td>\n";
                
                if (isset($rowA['j1njobid']) && $rowA['j1njobid']!='0')
                {
                    echo "		            			<td class=\"wh_und\" align=\"center\" width=\"80px\">".str_pad($rowA['masdiv'],2,'0',STR_PAD_LEFT).str_pad($rowA['j1njobid'],5,'0',STR_PAD_LEFT)."</td>\n";
                }
                else
                {
                    echo "		            			<td class=\"wh_und\" align=\"center\" width=\"80px\"></td>\n";
                }
                
                echo "		            			<td class=\"wh_und\" align=\"left\" width=\"10px\">\n";
                echo "                              </td>\n";
                echo "		            			<td class=\"wh_und\" align=\"left\" width=\"200px\">".substr($rowA['clname'],0,20)."</td>\n";
                echo "		            			<td class=\"wh_und\" align=\"left\" width=\"150px\">".ucfirst($rowA['salesrep'])."</td>\n";
                echo "		            			<td class=\"wh_und\" align=\"right\" width=\"100px\">".number_format($tconn)."</td>\n";
                //echo "		            			<td class=\"wh_und\" align=\"right\" width=\"100px\">".number_format($tcomm)."</td>\n";
                echo "		            			<td class=\"wh_und\" align=\"right\" width=\"100px\"></td>\n";
                echo "		            			<td class=\"wh_und\" align=\"center\" width=\"150px\">".date("m/d/y",strtotime($rowA['contrdate']))."</td>\n";
                echo "		            			<td class=\"wh_und\" align=\"center\" width=\"150px\">";
                
                if (!empty($rowA['digdate']))
                {
                    $digs++;
                    echo date("m/d/y",strtotime($rowA['digdate']));
                }
                
                echo "</td>\n";
                echo "				            </tr>\n";
                $oidt=$rowA['j1oid'];
            }
        }
        else
        {
            echo "				<tr>\n";
            echo "					<td class=\"wh_und\" align=\"left\">None during this timeframe</td>\n";
            echo "				</tr>\n";
        }
        
        echo "			</table>\n";
        //echo "      </span>\n";
        echo "		</td>\n";
        echo "	</tr>\n";
    }
    
	echo "</table>\n";
    
    if (isset($_REQUEST['expand']) && $_REQUEST['expand']==1)
    {
    }
    else
    {
        echo "</div>\n";
    }
    
    if (isset($_REQUEST['subq']) && $_REQUEST['subq']=="stage2" && $nrowA > 0)
    {
        if ($ltot!=0 && $ctot!=0)
        {
            $ctc= round(($ctot / $ltot) * 100);
        }
        else
        {
            $ctc=0;
        }
        
        /*if ($ctot!=0 && $jtot!=0)
        {
            $jtc= round(($jtot / $ctot) * 100);
        }
        else
        {
            $jtc=0;
        }*/
        
        if ($ctot!=0 && $dtot!=0)
        {
            $dtc= round(($dtot / $ctot) * 100);
        }
        else
        {
            $dtc=0;
        }
        
        echo "<table class=\"outer\" border=\"".$br."\">\n";
        echo "  <tr>\n";
        echo "      <td>\n";
        echo "          <table border=\"".$br."\">\n";
        echo "              <tr>\n";
        echo "                  <td class=\"gray\" align=\"right\" width=\"200px\"><b>Totals</b></td>\n";
        echo "                  <td class=\"gray\" align=\"right\" width=\"700px\">\n";
        echo "                      <table border=\"".$br."\">\n";
        echo "                          <tr>\n";
        echo "                              <td class=\"gray\" align=\"right\" width=\"50px\">Leads:</td>\n";
        echo "                              <td class=\"gray\" align=\"left\" width=\"75px\">&nbsp<b>".$ltot."</b></td>\n";
        echo "                              <td class=\"gray\" align=\"right\" width=\"50px\">Contracts:</td>\n";
        echo "                              <td class=\"gray\" align=\"left\" width=\"75px\">&nbsp<b>".$ctot."</b> (".$ctc."%)</td>\n";
        /*echo "                              <td class=\"gray\" align=\"right\" width=\"50px\">Jobs:</td>\n";
        echo "                              <td class=\"gray\" align=\"left\" width=\"75px\">".$jtot." (".$jtc."%)</td>\n";*/
        echo "                              <td class=\"gray\" align=\"right\" width=\"50px\">Digs:</td>\n";
        echo "                              <td class=\"gray\" align=\"left\" width=\"75px\">&nbsp<b>".$dtot."</b> (".$dtc."%)</td>\n";
        echo "                          </tr>\n";
        echo "                      </table>\n";
        echo "                  </td>\n";
        echo "              </tr>\n";
        echo "          </table>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
    }
}

function eventlogreport()
{
	error_reporting(E_ALL);
	
	$dcnt=0;
	$qry0 = "SELECT * FROM offices WHERE active=1 ORDER by grouping,name;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT securityid,gmreports FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	if ($_SESSION['officeid']!=89 && $_SESSION['rlev'] < 9 && $_SESSION['securityid']!=SYS_ADMIN)
	{
		die('You do not have appropriate Access Rights to view this Report');
	}
	else
	{
		$order="evdate";
		$asc	="desc";
		
		if (isset($_REQUEST['order']) && $_REQUEST['order']!="evdate")
		{
			$order=$_REQUEST['order'];
		}
		
		if (isset($_REQUEST['asc']) && $_REQUEST['asc']!="desc")
		{
			$asc=$_REQUEST['asc'];
		}
		
		if (isset($_REQUEST['d1']) && valid_date($_REQUEST['d1']) && isset($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
		{
			$d1=date("m/d/Y",strtotime($_REQUEST['d1']));
			$d2=date("m/d/Y",strtotime($_REQUEST['d2']));
		}
		else
		{
			$d1=date("m/d/Y",(time() - (84600 * 30)));
			$d2=date("m/d/Y",time());
		}
	
		echo "<table align=\"center\" width=\"700px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
		echo "         <table class=\"outer\" width=\"100%\" border=0>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP colspan=\"2\">&nbsp<b>Event Log Report</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Order</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Date Range</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Non Admin</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Archive</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp</td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Office:</b></td>\n";
		echo "         			<form name=\"report1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"evlogreport\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"results\">\n";
		echo "      			<td class=\"gray\" align=\"left\">\n";
		echo "						<select name=\"oid\">\n";
		
		if (isset($_REQUEST['oid']) && $_REQUEST['oid']==0)
		{
			echo "							<option value=\"0\" SELECTED>All</option>\n";
		}
		else
		{
			echo "							<option value=\"0\">All</option>\n";
		}
		
		while ($row0 = mssql_fetch_array($res0))
		{
			if ($_REQUEST['oid']==$row0['officeid'])
			{
				echo "							<option value=\"".$row0['officeid']."\" SELECTED>".$row0['name']."</option>\n";
			}
			else
			{
				echo "							<option value=\"".$row0['officeid']."\">".$row0['name']."</option>\n";
			}
		}
		
		echo "						</select>\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"left\">\n";
		echo "						<select name=\"asc\">\n";
		
		if ($_REQUEST['asc']=='asc')
		{
			echo "							<option value=\"asc\" SELECTED>ASC</option>\n";
			echo "							<option value=\"desc\">DESC</option>\n";
		}
		else
		{
			echo "							<option value=\"desc\" SELECTED>DESC</option>\n";
			echo "							<option value=\"asc\">ASC</option>\n";
		}

		echo "						</select>\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"left\">\n";
		echo "         			<table width=\"100%\">\n";
		echo "   						<tr>\n";
		echo "      						<td class=\"gray\" align=\"left\">\n";
	
		if (!empty($d1))
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"12\" value=\"".$d1."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"12\">\n";
		}
	
		echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	
		if (!empty($d2))
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"12\" maxlength=\"10\" value=\"".$d2."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"12\">\n";
		}
	
		echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
		echo "      						</td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "      						<td class=\"gray\" align=\"center\">\n";
		
		if (isset($_REQUEST['excladmin']) && $_REQUEST['excladmin']==1)
		{
			echo "<input type=\"checkbox\" class=\"transnb\" name=\"excladmin\" value=\"1\" title=\"Check this box to exclude BHNM: Active\" CHECKED>\n";
		}
		else
		{
			echo "<input type=\"checkbox\" class=\"transnb\" name=\"excladmin\" value=\"1\" title=\"Check this box to exclude BHNM: Active\">\n";
		}
		
		echo "      						</td>\n";
		echo "      						<td class=\"gray\" align=\"center\">\n";
		
		if (isset($_REQUEST['archive']) && $_REQUEST['archive']==1)
		{
			echo "<input type=\"checkbox\" class=\"transnb\" name=\"archive\" value=\"1\" title=\"Check this box to exclude BHNM: Active\" CHECKED>\n";
		}
		else
		{
			echo "<input type=\"checkbox\" class=\"transnb\" name=\"archive\" value=\"1\" title=\"Check this box to exclude BHNM: Active\">\n";
		}
		
		echo "      						</td>\n";
		echo "      						<td class=\"gray\" align=\"center\">\n";
		echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
		echo "      						</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "         				</form>\n";
	
		echo "         						<script language=\"JavaScript\">\n";
		echo "         						var cal1 = new calendar2(document.forms['report1'].elements['d1']);\n";
		echo "         						cal1.year_scroll = false;\n";
		echo "         						cal1.time_comp = false;\n";
		echo "         						var cal2 = new calendar2(document.forms['report1'].elements['d2']);\n";
		echo "         						cal2.year_scroll = false;\n";
		echo "         						cal2.time_comp = false;\n";
		echo "         						//-->\n";
		echo "         						</script>\n";
		
		if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='results')
		{
			$s_ar=array();
			if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
			{
				$otxt=" oid='".$_REQUEST['oid']."' AND ";
			}
			else
			{
				$otxt="";
			}
			
			if (isset($_REQUEST['excladmin']) && $_REQUEST['excladmin']==1)
			{
				$qryA  = "SELECT securityid FROM security WHERE officeid=89;";
				$resA  = mssql_query($qryA);
				
				while ($rowA = mssql_fetch_array($resA))
				{
					$s_ar[]=$rowA['securityid'];	
				}
				
				$atxt=" oid!='89' AND ";
			}
			else
			{
				$atxt="";
			}
			
			if (isset($_REQUEST['archive']) && $_REQUEST['archive']==1)
			{
				$rtxt="jest..events";
			}
			else
			{
				$rtxt="jest_stats..events";
			}
			
			$qry2  = "SELECT ";
			$qry2 .= "*, ";
			$qry2 .= "(SELECT name FROM offices WHERE officeid=e.oid) as office, ";
			$qry2 .= "(SELECT lname FROM security WHERE securityid=e.sid) as lname, ";
			$qry2 .= "(SELECT fname FROM security WHERE securityid=e.sid) as fname ";
			$qry2 .= "FROM ".$rtxt." as e WHERE ".$otxt." ".$atxt." evdate >='".$d1."' AND evdate < '".$d2."' ORDER BY ".$order." ".$asc.";";
			$res2  = mssql_query($qry2);
			$nrow2 = mssql_num_rows($res2);
			
			//echo $qry2."<br>";
			
			if ($nrow2 > 0)
			{
				echo "<table align=\"center\" width=\"700px\">\n";
				echo "   <tr>\n";
				echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
				echo "         <table class=\"outer\" width=\"100%\">\n";
				//echo "   			<tr>\n";
				//echo "      			<td class=\"gray\" align=\"right\" colspan=\"5\" NOWRAP><font color=\"red\">".$nrow2."</font> Record(s)</td>\n";
				//echo "   			</tr>\n";
				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"left\" NOWRAP>&nbsp</td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"left\" NOWRAP>&nbsp<b>Office</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>User</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>String</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Date</b></td>\n";
				echo "   			</tr>\n";
				
				$ccnt=0;
				while($row2 = mssql_fetch_array($res2))
				{
					if (!in_array($row2['sid'],$s_ar))
					{
						$ccnt++;
						if ($ccnt%2)
						{
							$tbg = "white";
						}
						else
						{
							$tbg = "gray";
						}
						
						echo "   			<tr>\n";
						echo "      			<td class=\"".$tbg."\" align=\"right\" NOWRAP>".$ccnt.".</td>\n";
						echo "      			<td class=\"".$tbg."\" align=\"left\" NOWRAP>".$row2['office']."</td>\n";
						echo "      			<td class=\"".$tbg."\" align=\"left\" NOWRAP>".$row2['lname'].", ".$row2['fname']."</td>\n";
						echo "      			<td class=\"".$tbg."\" align=\"left\" NOWRAP>".$row2['evdescrip']."</td>\n";
						echo "      			<td class=\"".$tbg."\" align=\"center\" NOWRAP>".date("m/d/Y h:m",strtotime($row2['evdate']))."</td>\n";
						echo "   			</tr>\n";
					}
				}
				
				echo "			</td>\n";
				echo "   	</tr>\n";
				echo "	</table>\n";
			}
		}
	}
}

function get_sidm_ids($id)
{
	$out		=array();
	$s_ar		=array();
	
	$qry = "SELECT securityid FROM security WHERE sidm='".$id."';";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($nrow > 0)
	{
		while($row = mssql_fetch_array($res))
		{
			$s_ar[]=$row['securityid'];
		}
	}
	else
	{
		$s_ar[]=0;
	}
	
	$out = array($nrow,$s_ar);
	return $out;
}

function complaints()
{
	error_reporting(E_ALL);
	//show_post_vars();
	
	if (isset($_REQUEST['d1']) && valid_date($_REQUEST['d1']) && isset($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
	{
		$d1=$_REQUEST['d1'];
		$d2=$_REQUEST['d2'];
	}
	else
	{
		if (isset($_REQUEST['tdate']) && $_REQUEST['tdate']==1)
		{
			echo "TDATE SET!<br>";
			$d1=date("m/d/Y",time());
			$d2=date("m/d/Y",time());
		}
		else
		{
			$d1="";
			$d2="";
		}
	}
	
    $qrypre0 = "SELECT securityid,officeid,slevel,csrep FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$respre0 = mssql_query($qrypre0);
    $rowpre0 = mssql_fetch_array($respre0);
    
	$qry0 = "SELECT * FROM offices WHERE active=1 order by grouping,name asc;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT * FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
    
    if ($_SESSION['securityid']==26999999999999999)
    {
        //echo 'TEST!!<br>';
    }
	
	if ($rowpre0['csrep']==0)
	{
		echo 'You do not have appropriate Access Rights to view this Reource<br>';
		exit;
	}
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_extend.js\"></script>\n";
	//echo "<div class=\"noPrint\">\n";
	echo "<form name=\"complaintreport1\" method=\"post\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
    echo "<input type=\"hidden\" name=\"call\" value=\"complaints\">\n";
    echo "<input type=\"hidden\" name=\"subq\" value=\"stage2\">\n";
    echo "<table width=\"950px\">\n";
    echo "   <tr>\n";
    echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
    echo "         <table class=\"outer\" width=\"100%\">\n";
    echo "   			<tr>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP></td>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>Customer Service Report</b></td>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>Status</b></td>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>Dates</b>\n";
    echo "                  </td>\n";
    echo "      			<td class=\"gray\" align=\"center\" NOWRAP><b></b></td>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP></td>\n";
    echo "   			</tr>\n";
    echo "   			<tr>\n";
    echo "      			<td class=\"gray\" align=\"right\" NOWRAP><b>Office</b></td>\n";
    echo "      			<td class=\"gray\" align=\"left\">\n";
    echo "						<select name=\"oid\">\n";
    
    if ($_SESSION['rlev'] >= 6 && $rowpre0['officeid']==89)
    {
        //if (isset($_REQUEST['oid']) and $_REQUEST['oid']==0)
        //{
        //    echo "							<option value=\"0\" SELECTED>All</option>\n";
        //}
        //elseif (isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)
        //{
            echo "							<option value=\"0\">All</option>\n";
        //}
        
        while ($row0 = mssql_fetch_array($res0))
        {
            if (isset($_REQUEST['oid']) and $_REQUEST['oid']==$row0['officeid'])
            {
                echo "							<option value=\"".$row0['officeid']."\" SELECTED>".$row0['name']."</option>\n";
            }
            else
            {
                echo "							<option value=\"".$row0['officeid']."\">".$row0['name']."</option>\n";
            }
        }
    }
    else
    {
        echo "							<option value=\"".$row1['officeid']."\" SELECTED>".$row1['name']."</option>\n";
    }
    
    echo "						</select>\n";
    echo "      			</td>\n";
    echo "      			<td class=\"gray\" align=\"left\">\n";
    echo "                      <select name=\"status\">\n";
    
    if (isset($_REQUEST['status']) && $_REQUEST['status']=='SA')
    {
        echo "                          <optgroup label=\"Service Request\">\n";
        echo "                          <option value=\"SA\" SELECTED>All Service Requests</option>\n";
        echo "                          <option value=\"SO\">Open Service Requests</option>\n";
        echo "                          <option value=\"SR\">Resolved Service Requests</option>\n";
        echo "                          <optgroup label=\"Complaint\">\n";
        echo "                          <option value=\"CA\">All Complaints</option>\n";
        echo "                          <option value=\"CO\">Open Complaints</option>\n";
        echo "                          <option value=\"CR\">Resolved Complaints</option>\n";
    }
    elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='SO')
    {
        echo "                          <optgroup label=\"Service Request\">\n";
        echo "                          <option value=\"SA\">All Service Requests</option>\n";
        echo "                          <option value=\"SO\" SELECTED>Open Service Requests</option>\n";
        echo "                          <option value=\"SR\">Resolved Service Requests</option>\n";
        echo "                          <optgroup label=\"Complaint\">\n";
        echo "                          <option value=\"CA\">All Complaints</option>\n";
        echo "                          <option value=\"CO\">Open Complaints</option>\n";
        echo "                          <option value=\"CR\">Resolved Complaints</option>\n";
    }
    elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='SR')
    {
        echo "                          <optgroup label=\"Service Request\">\n";
        echo "                          <option value=\"SA\">All Service Requests</option>\n";
        echo "                          <option value=\"SO\">Open Service Requests</option>\n";
        echo "                          <option value=\"SR\" SELECTED>Resolved Service Requests</option>\n";
        echo "                          <optgroup label=\"Complaint\">\n";
        echo "                          <option value=\"CA\">All Complaints</option>\n";
        echo "                          <option value=\"CO\">Open Complaints</option>\n";
        echo "                          <option value=\"CR\">Resolved Complaints</option>\n";
    }
    elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='CA')
    {
        echo "                          <optgroup label=\"Service Request\">\n";
        echo "                          <option value=\"SA\">All Service Requests</option>\n";
        echo "                          <option value=\"SO\">Open Service Requests</option>\n";
        echo "                          <option value=\"SR\">Resolved Service Requests</option>\n";
        echo "                          <optgroup label=\"Complaint\">\n";
        echo "                          <option value=\"CA\" SELECTED>All Complaints</option>\n";
        echo "                          <option value=\"CO\">Open Complaints</option>\n";
        echo "                          <option value=\"CR\">Resolved Complaints</option>\n";
    }
    elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='CO')
    {
        echo "                          <optgroup label=\"Service Request\">\n";
        echo "                          <option value=\"SA\">All Service Requests</option>\n";
        echo "                          <option value=\"SO\">Open Service Requests</option>\n";
        echo "                          <option value=\"SR\">Resolved Service Requests</option>\n";
        echo "                          <optgroup label=\"Complaint\">\n";
        echo "                          <option value=\"CA\">All Complaints</option>\n";
        echo "                          <option value=\"CO\" SELECTED>Open Complaints</option>\n";
        echo "                          <option value=\"CR\">Resolved Complaints</option>\n";
    }
    elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='CR')
    {
        echo "                          <optgroup label=\"Service Request\">\n";
        echo "                          <option value=\"SA\">All Service Requests</option>\n";
        echo "                          <option value=\"SO\">Open Service Requests</option>\n";
        echo "                          <option value=\"SR\">Resolved Service Requests</option>\n";
        echo "                          <optgroup label=\"Complaint\">\n";
        echo "                          <option value=\"CA\">All Complaints</option>\n";
        echo "                          <option value=\"CO\">Open Complaints</option>\n";
        echo "                          <option value=\"CR\" SELECTED>Resolved Complaints</option>\n";
    }
    else
    {
        echo "                          <optgroup label=\"Service Request\">\n";
        echo "                          <option value=\"SA\">All Service Requests</option>\n";
        echo "                          <option value=\"SO\">Open Service Requests</option>\n";
        echo "                          <option value=\"SR\">Resolved Service Requests</option>\n";
        echo "                          <optgroup label=\"Complaint\">\n";
        echo "                          <option value=\"CA\">All Complaints</option>\n";
        echo "                          <option value=\"CO\" SELECTED>Open Complaints</option>\n";
        echo "                          <option value=\"CR\">Resolved Complaints</option>\n";
    }
    
    echo "                      </select>\n";
    echo "      			</td>\n";
    echo "      			<td class=\"gray\" align=\"left\">\n";
    echo "         			    <table>\n";
    echo "   						<tr>\n";
    echo "      						<td class=\"gray\">\n";

    if (!empty($d1))
    {
        echo "									<input class=\"bboxbc\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\" value=\"".$d1."\">\n";
    }
    else
    {
        echo "									<input class=\"bboxbc\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
    }

    if (!empty($d2))
    {
        echo "									<input class=\"bboxbc\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\" maxlength=\"10\" value=\"".$d2."\">\n";
    }
    else
    {
        echo "									<input class=\"bboxbc\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
    }

    echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
    echo "      						</td>\n";
    echo "   						</tr>\n";
    echo "						</table>\n";
    echo "					</td>\n";
    echo "      			<td class=\"gray\" align=\"center\">\n";
    echo "      			</td>\n";
    echo "      			<td class=\"gray\" width=\"25px\" align=\"center\">\n";
    echo "					    <input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
    echo "      			</td>\n";
    echo "   			</tr>\n";
    
    if ($rowpre0['officeid']==89)
    {
        echo "   			<tr>\n";
        echo "      			<td class=\"gray\" align=\"right\"><b>Customer Last Name</b></td>\n";
        echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\">\n";
        
        if (isset($_REQUEST['stxt']) && strlen($_REQUEST['stxt']) >= 1)
        {
            echo "									<input type=\"text\" name=\"stxt\" size=\"25\" value=\"".$_REQUEST['stxt']."\">\n";
        }
        else
        {
            echo "									<input type=\"text\" name=\"stxt\" size=\"25\">\n";
        }
        
        echo "      			* BHNM: Active Only</td>\n";
		echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\">\n";
		echo "						<table width=\"100%\"><tr><td>\n";
		
		//if (isset($_REQUEST['allcomplaints']) && $_REQUEST['allcomplaints']==1)
		//{	
		//	echo "                  <input class=\"transnb\" type=\"checkbox\" name=\"allcomplaints\" value=\"1\" title=\"Check this box to return ALL tickets\" CHECKED> All\n";
		//}
		//else
		//{
		//	echo "                  <input class=\"transnb\" type=\"checkbox\" name=\"allcomplaints\" value=\"1\" title=\"Check this box to return ALL tickets\"> All\n";
		//}
		
		echo "						</td><td>\n";
			
		if (isset($_REQUEST['reccomplaints']) && ($_REQUEST['reccomplaints']==0 or $_REQUEST['reccomplaints']==5 or $_REQUEST['reccomplaints']==10 or $_REQUEST['reccomplaints']==15))
		{
			//echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"5\" title=\"Check this box to limited the search results to the most recently added or updated tickets\" CHECKED> 5\n";
			
			if (isset($_REQUEST['reccomplaints']) && $_REQUEST['reccomplaints']==0)
			{
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"0\" CHECKED> All\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"5\"> 5\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"10\"> 10\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"15\"> 15\n";
			}
			elseif (isset($_REQUEST['reccomplaints']) && $_REQUEST['reccomplaints']==5)
			{
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"0\"> All\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"5\" CHECKED> 5\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"10\"> 10\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"15\"> 15\n";
			}
			elseif (isset($_REQUEST['reccomplaints']) && $_REQUEST['reccomplaints']==10)
			{
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"0\"> All\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"5\"> 5\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"10\" CHECKED> 10\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"15\"> 15\n";	
			}
			elseif (isset($_REQUEST['reccomplaints']) && $_REQUEST['reccomplaints']==15)
			{
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"0\"> All\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"5\"> 5\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"10\"> 10\n";
				echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"15\" CHECKED> 15\n";
			}
			
			echo " Days Prior";
		}
		elseif (!isset($_REQUEST['reccomplaints']))
		{
			echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"0\"> All\n";
			echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"5\"> 5\n";
			echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"10\"> 10\n";
			echo "                  <input class=\"transnb\" type=\"radio\" name=\"reccomplaints\" value=\"15\"> 15\n";
			
			echo " Days Prior";
		}
		
		echo "						</td></tr></table>\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"center\">\n";
		echo "      			</td>\n";
        echo "   			</tr>\n";
		echo "   			<tr>\n";
        echo "      			<td class=\"gray\" align=\"right\"><b>Comments</b></td>\n";
        echo "      			<td class=\"gray\" align=\"left\" colspan=\"3\">\n";
        
        if (isset($_REQUEST['ctxt']) && strlen($_REQUEST['ctxt']) >= 1)
        {
            echo "									<input type=\"text\" name=\"ctxt\" size=\"75\" value=\"".$_REQUEST['ctxt']."\">\n";
        }
        else
        {
            echo "									<input type=\"text\" name=\"ctxt\" size=\"75\">\n";
        }
        
		if (isset($_REQUEST['cwild']) && $_REQUEST['cwild'] == 'full')
        {
            echo "									<input type=\"checkbox\" class=\"transnb\" name=\"cwild\" value=\"full\" CHECKED> Full Wildcard\n";
        }
        else
        {
            echo "									<input type=\"checkbox\" class=\"transnb\" name=\"cwild\" value=\"full\"> Full Wildcard\n";
        }
		
        echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"center\">\n";
		echo "      			</td>\n";
        echo "   			</tr>\n";
    }
    
    echo "</table>\n";
    echo "</form>\n";
	//echo "</div>\n";
	
	if ($_SESSION['subq']=="stage2")
	{
		if (!isset($_REQUEST['reccomplaints']))
		{
			if (!valid_date($d1) || !valid_date($d2))
			{
				echo "<center><b>Invalid Date</b><br>Format must be:<br> <b>mm/dd/yy or mm/dd/yyyy</b><br>Please correct and search again.<br></center>";
				exit;
			}
		}
        
        $qry0co  = "SELECT * FROM jest..view_complaints WHERE ";
        
        if (isset($_REQUEST['stxt']) && strlen($_REQUEST['stxt']) >= 1)
        {
            $qry0co .= "clname like '".trim($_REQUEST['stxt'])."%' and ";
        }
		
		if (isset($_REQUEST['ctxt']) && strlen($_REQUEST['ctxt']) >= 1)
        {
			if (isset($_REQUEST['cwild']) && $_REQUEST['cwild']=='full')
			{
				$qry0co .= "mtext like '%".trim($_REQUEST['ctxt'])."%' and ";
			}
			else
			{
				$qry0co .= "mtext like '".trim($_REQUEST['ctxt'])."%' and ";
			}
        }
        
        if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
        {
            $qry0co .= "oid=".$_REQUEST['oid']." and ";
        }
        
        if (isset($_REQUEST['status']) && $_REQUEST['status']=='CA')
        {
            $qry0co .= "complaint=1 and followup=0 and resolved=0 ";
        }
        elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='CO')
        {
            $qry0co .= "complaint=1 and followup=0 and resolved=0 and cres!=1";
        }
        elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='CR')
        {
            $qry0co .= "complaint=1 and followup=0 and resolved=0 and cres=1";
        }
        elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='SA')
        {
            $qry0co .= "cservice=1 and followup=0 and resolved=0 ";
        }
        elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='SO')
        {
            $qry0co .= "cservice=1 and followup=0 and resolved=0 and sres!=1";
        }
        elseif (isset($_REQUEST['status']) && $_REQUEST['status']=='SR')
        {
            $qry0co .= "cservice=1 and followup=0 and resolved=0 and sres=1";
        }
        
        //$dbg=1;
		if (isset($_REQUEST['reccomplaints']) && $_REQUEST['reccomplaints']==0)
		{
			//$qry0co .= " and mdate between (getdate() - ".$_REQUEST['reccomplaints'].") and getdate() ";
		}
		elseif (isset($_REQUEST['reccomplaints']) && ($_REQUEST['reccomplaints']==5 or $_REQUEST['reccomplaints']==10 or $_REQUEST['reccomplaints']==15))
		{
			$qry0co .= " and mdate between (getdate() - ".$_REQUEST['reccomplaints'].") and getdate() ";
		}
        else
        {
			if (isset($_REQUEST['d1']) && valid_date($_REQUEST['d1']) && isset($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
			{
				$qry0co .= " and mdate between '".date('m/d/y',strtotime($_REQUEST['d1']))."' and '".date('m/d/y',strtotime($_REQUEST['d2']))." 23:59:59'";
			}
        }
        
        $qry0co .= " order by oname, clname;";
        $res0co = mssql_query($qry0co);
        $nrow0co= mssql_num_rows($res0co);
        
        if ($_SESSION['securityid']==26999999999999999999999999999999999999999999999999999999999999)
        {
            echo $qry0co."<br>";
            //echo $nrow0co."<br>";
        }

		if ($nrow0co > 0)
		{
			$bdar = split('[-,/]', $d1);
			$edar = split('[-,/]', $d2);
			
			echo "<table align=\"center\" width=\"950px\">\n";
			echo "   <tr>\n";
			echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
			echo "         <table class=\"outer\" width=\"100%\">\n";
			echo "   			<tr class=\"tblhd\">\n";
			echo "      			<td align=\"left\">&nbsp</td>\n";
            echo "      			<td align=\"center\" width=\"50\"><b>Ticket</b></td>\n";
            echo "      			<td align=\"left\" width=\"75\"><b>Date</b></td>\n";
			echo "      			<td align=\"left\"><b>Customer</b></td>\n";
            echo "      			<td align=\"left\"><b>Office</b></td>\n";
			echo "      			<td align=\"left\"><b>Rep</b></td>\n";
			echo "      			<td align=\"left\"><b>Comment</b></td>\n";
            echo "      			<td align=\"left\" title=\"Resolved\"><b>R</b></td>\n";
			echo "      			<td align=\"center\"></td>\n";
			echo "   			</tr>\n";
			
			$cnt    =1;
            $coid   =0;
			while ($row0co = mssql_fetch_array($res0co))
			{
                //$cnt++;
                $uid  =md5(session_id().time().$row0co['cid']).".".$_SESSION['securityid'];
				
				if ($cnt%2)
				{
					$tbg = 'whlist';
				}
				else
				{
					$tbg = 'ltgraylist';
				}
				
                if ($coid!=$row0co['oid'])
                {
                    if ($coid!=0)
                    {
                        echo "   			<tr>\n";
                        echo "      			<td class=\"".$tbg."\" colspan=\"9\">&nbsp</td>\n";
                        echo "   			</tr>\n";
                    }
                    
                    echo "   			<tr>\n";
                    echo "      			<td class=\"gray_und\">&nbsp</td>\n";
                    echo "      			<td class=\"gray_und\" align=\"left\" colspan=\"8\"><b>".$row0co['oname']."</b></td>\n";
                    echo "   			</tr>\n";
                }
                
				echo "   			<tr>\n";
				echo "      			<td class=\"".$tbg."\" valign=\"top\" align=\"right\">".$cnt++.".</td>\n";
				echo "      			<td class=\"".$tbg."\" valign=\"top\" align=\"center\">".$row0co['id']."</td>\n";
                echo "      			<td class=\"".$tbg."\" valign=\"top\" align=\"left\">".date('m/d/y h:iA',strtotime($row0co['mdate']))."</td>\n";
				echo "      			<td class=\"".$tbg."\" valign=\"top\" align=\"left\">".$row0co['clname'].", ".$row0co['cfname']."</td>\n";
				echo "      			<td class=\"".$tbg."\" valign=\"top\" align=\"left\">".$row0co['oname']."</td>\n";
				echo "      			<td class=\"".$tbg."\" valign=\"top\" align=\"left\">".$row0co['csrlname']."</td>\n";
				echo "      			<td class=\"".$tbg."\" valign=\"top\" align=\"left\" width=\"550px\">".htmlspecialchars_decode($row0co['mtext'])."</td>\n";
                echo "      			<td class=\"".$tbg."\" valign=\"top\" align=\"center\">\n";
                
                if ($row0co['cres'] != 0 || $row0co['sres'] != 0)
                {
                    echo "<img src=\"images\srvup.gif\" height=\"15px\" width=\"15px\">";
                }

                echo "                  </td>\n";
                echo "      			<td class=\"".$tbg."\" valign=\"top\" align=\"center\">\n";
                echo "                  <form method=\"POST\">\n";
                echo "                      <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
                echo "                      <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
                echo "                      <input type=\"hidden\" name=\"cid\" value=\"".$row0co['cid']."\">\n";
                
                if ($rowpre0['officeid']==89)
                {
                    echo "                           <input type=\"hidden\" name=\"noffid\" value=\"".$row0co['oid']."\">\n";
                }
                
                echo "                      <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
                echo "                      <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" value=\"View\">\n";
                echo "                  </form>\n";
                echo "                  </td>\n";
				echo "   			</tr>\n";
                $coid=$row0co['oid'];
			}
			
			echo "			</td>\n";
			echo "   	</tr>\n";
			echo "	</table>\n";
		}
		else
		{
			echo 'No results';
		}
	}
}

function complaintsACTIVE()
{
	error_reporting(E_ALL);
	//show_post_vars();
	
	if (isset($_REQUEST['d1']) && valid_date($_REQUEST['d1']) && isset($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
	{
		$d1=$_REQUEST['d1'];
		$d2=$_REQUEST['d2'];
	}
	else
	{
		if (isset($_REQUEST['tdate']) && $_REQUEST['tdate']==1)
		{
			echo "TDATE SET!<br>";
			$d1=date("m/d/Y",time());
			$d2=date("m/d/Y",time());
		}
		else
		{
			$d1="";
			$d2="";
		}
	}
    
    $qrypre0 = "SELECT securityid,officeid,slevel FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$respre0 = mssql_query($qrypre0);
    $rowpre0 = mssql_fetch_array($respre0);
	
	$qry0 = "SELECT * FROM offices WHERE active=1 order by grouping,name asc;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT * FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
    echo "<table width=\"900\">\n";
    echo "   <tr>\n";
    echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
    echo "      <form name=\"complaintreport1\" method=\"post\">\n";
    echo "      <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
    echo "      <input type=\"hidden\" name=\"call\" value=\"complaints\">\n";
    echo "      <input type=\"hidden\" name=\"subq\" value=\"stage2\">\n";
    echo "         <table class=\"outer\" width=\"100%\">\n";
    echo "   			<tr>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP></td>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>Customer Service Report</b></td>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>Status</b></td>\n";
    //echo "      			<td class=\"gray\" align=\"center\" NOWRAP></td>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>Dates</b>\n";
    
    if (isset($_REQUEST['allcomplaints']) && $_REQUEST['allcomplaints']==1)
    {
        echo "                  <input class=\"transnb\" type=\"checkbox\" name=\"allcomplaints\" value=\"1\" CHECKED> All Dates\n";
    }
    else
    {
        echo "                  <input class=\"transnb\" type=\"checkbox\" name=\"allcomplaints\" value=\"1\"> All Dates\n";
    }
    
    echo "                  </td>\n";
    echo "      			<td class=\"gray\" align=\"center\" NOWRAP><b></b></td>\n";
    echo "      			<td class=\"gray\" align=\"left\" NOWRAP></td>\n";
    echo "   			</tr>\n";
    echo "   			<tr>\n";
    echo "      			<td class=\"gray\" align=\"right\" NOWRAP><b>Office</b></td>\n";
    echo "      			<td class=\"gray\" align=\"left\">\n";
    echo "						<select name=\"oid\">\n";
    
    if ($_SESSION['rlev'] >= 6 && $_SESSION['officeid']==89)
    {
        if ($_REQUEST['oid']==0)
        {
            echo "							<option value=\"0\" SELECTED>All</option>\n";
        }
        elseif ($_REQUEST['oid']!=0)
        {
            echo "							<option value=\"0\">All</option>\n";
        }
        
        while ($row0 = mssql_fetch_array($res0))
        {
            if ($_REQUEST['oid']==$row0['officeid'])
            {
                echo "							<option value=\"".$row0['officeid']."\" SELECTED>".$row0['name']."</option>\n";
            }
            else
            {
                echo "							<option value=\"".$row0['officeid']."\">".$row0['name']."</option>\n";
            }
        }
    }
    else
    {
        echo "							<option value=\"".$row1['officeid']."\" SELECTED>".$row1['name']."</option>\n";
    }
    
    echo "						</select>\n";
    echo "      			</td>\n";
    echo "      			<td class=\"gray\" align=\"left\">\n";
    echo "                      <select name=\"complaintstatus\">\n";
    
    if (isset($_REQUEST['complaintstatus']) && $_REQUEST['complaintstatus']=='A')
    {
        echo "                          <option value=\"A\" SELECTED>All</option>\n";
        echo "                          <option value=\"O\">Open</option>\n";
        echo "                          <option value=\"R\">Resolved</option>\n";
    }
    elseif (isset($_REQUEST['complaintstatus']) && $_REQUEST['complaintstatus']=='O')
    {
        echo "                          <option value=\"A\">All</option>\n";
        echo "                          <option value=\"O\" SELECTED>Open</option>\n";
        echo "                          <option value=\"R\">Resolved</option>\n";
    }
    elseif (isset($_REQUEST['complaintstatus']) && $_REQUEST['complaintstatus']=='R')
    {
        echo "                          <option value=\"A\">All</option>\n";
        echo "                          <option value=\"O\">Open</option>\n";
        echo "                          <option value=\"R\" SELECTED>Resolved</option>\n";
    }
    else
    {
        echo "                          <option value=\"A\">All</option>\n";
        echo "                          <option value=\"O\" SELECTED>Open</option>\n";
        echo "                          <option value=\"R\">Resolved</option>\n";
    }
    
    echo "                      </select>\n";
    echo "      			</td>\n";
    echo "      			<td class=\"gray\" align=\"left\">\n";
    echo "         			    <table>\n";
    echo "   						<tr>\n";
    echo "      						<td class=\"gray\">\n";

    if (!empty($d1))
    {
        echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\" value=\"".$d1."\">\n";
    }
    else
    {
        echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
    }

    echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";

    if (!empty($d2))
    {
        echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\" maxlength=\"10\" value=\"".$d2."\">\n";
    }
    else
    {
        echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
    }

    echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
    echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
    echo "      						</td>\n";
    echo "   						</tr>\n";
    echo "						</table>\n";
    echo "					</td>\n";
    echo "      			<td class=\"gray\" align=\"center\">\n";
    
    
    echo "      			</td>\n";
    echo "      			<td class=\"gray\" width=\"25px\" align=\"center\">\n";
    echo "					    <input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
    echo "      			</td>\n";
    echo "   			</tr>\n";
    echo "			</table>\n";
    echo "      </form>\n";	
    
    echo "         						<script language=\"JavaScript\">\n";
    echo "         						var cal1 = new calendar2(document.forms['complaintreport1'].elements['d1']);\n";
    echo "         						cal1.year_scroll = false;\n";
    echo "         						cal1.time_comp = false;\n";
    echo "         						var cal2 = new calendar2(document.forms['complaintreport1'].elements['d2']);\n";
    echo "         						cal2.year_scroll = false;\n";
    echo "         						cal2.time_comp = false;\n";
    echo "         						//-->\n";
    echo "         						</script>\n";
	
	if ($_SESSION['subq']=="stage2")
	{
        if (empty($_REQUEST['allcomplaints']) || $_REQUEST['allcomplaints']!=1)
        {
            if (!valid_date($d1) || !valid_date($d2))
            {
                echo "<center><b>Invalid Date</b><br>Format must be:<br> <b>mm/dd/yy or mm/dd/yyyy</b><br>Please correct and search again.<br></center>";
                exit;
            }
        }
        
        $qry0co  = "SELECT * FROM jest..view_complaints WHERE ";
        
        if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
        {
            $qry0co .= "oid=".$_REQUEST['oid']." and ";
        }
        
        if (isset($_REQUEST['complaintstatus']) && $_REQUEST['complaintstatus']=='A')
        {
            $qry0co .= "followup=0 and resolved=0 ";
        }
        elseif (isset($_REQUEST['complaintstatus']) && $_REQUEST['complaintstatus']=='O')
        {
            $qry0co .= "followup=0 and resolved=0 and cres!=1";
        }
        elseif (isset($_REQUEST['complaintstatus']) && $_REQUEST['complaintstatus']=='R')
        {
            $qry0co .= "followup=0 and resolved=0 and cres=1";
        }
        
        $qry0co .= "order by oname, clname;";
        $res0co = mssql_query($qry0co);
        $nrow0co= mssql_num_rows($res0co);
		
        //echo $qry0co."<br>";
        //echo $qry0cr."<br>";
        
		//echo $nrow0co."<br>";
        //echo $nrow0cr."<br>";

		if ($nrow0co > 0)
		{
			$bdar = split('[-,/]', $d1);
			$edar = split('[-,/]', $d2);
			
			echo "<table align=\"center\" width=\"900\">\n";
			echo "   <tr>\n";
			echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
			echo "         <table class=\"outer\" width=\"100%\">\n";
			echo "   			<tr>\n";
			echo "      			<td class=\"ltgray_und\" align=\"left\">&nbsp</td>\n";
            echo "      			<td class=\"ltgray_und\" align=\"center\" width=\"50\"><b>Ticket</b></td>\n";
            echo "      			<td class=\"ltgray_und\" align=\"left\" width=\"75\"><b>Date</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Customer</b></td>\n";
            echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Office</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"left\"><b>CS Rep</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Comment</b></td>\n";
            echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Resolved</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" width=\"25\"><b>View</b></td>\n";
			echo "   			</tr>\n";
			
			$cnt    =0;
            $coid   =0;
			while ($row0co = mssql_fetch_array($res0co))
			{
                $cnt++;
                $uid  =md5(session_id().time().$row0co['cid']).".".$_SESSION['securityid'];
                
                if ($coid!=$row0co['oid'])
                {
                    if ($coid!=0)
                    {
                        echo "   			<tr>\n";
                        echo "      			<td class=\"wh_und\" colspan=\"9\">&nbsp</td>\n";
                        echo "   			</tr>\n";
                    }
                    
                    echo "   			<tr>\n";
                    echo "      			<td class=\"gray_und\">&nbsp</td>\n";
                    echo "      			<td class=\"gray_und\" align=\"left\" colspan=\"8\"><b>".$row0co['oname']."</b></td>\n";
                    echo "   			</tr>\n";
                }
                
				echo "   			<tr>\n";
				echo "      			<td class=\"wh_und\" align=\"right\">".$cnt.".</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\">".$row0co['id']."</td>\n";
                echo "      			<td class=\"wh_und\" align=\"left\">".date('m/d/y h:iA',strtotime($row0co['mdate']))."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"left\">".$row0co['clname'].", ".$row0co['cfname']."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"left\">".$row0co['oname']."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"left\">".$row0co['csrlname']."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"left\">".$row0co['mtext']."</td>\n";
                echo "      			<td class=\"wh_und\" align=\"center\">\n";
                
                if ($row0co['cres'] != 0)
                {
                    echo "<img src=\"images\srvup.gif\" height=\"15px\" width=\"15px\">";
                }

                echo "                  </td>\n";
                echo "      			<td class=\"wh_und\" align=\"center\">\n";
                echo "                  <form method=\"POST\">\n";
                echo "                      <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
                echo "                      <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
                echo "                      <input type=\"hidden\" name=\"cid\" value=\"".$row0co['cid']."\">\n";
                
                if ($rowpre0['officeid']==89)
                {
                    echo "                           <input type=\"hidden\" name=\"noffid\" value=\"".$row0co['oid']."\">\n";
                }
                
                echo "                      <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
                echo "                      <input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" value=\"View\">\n";
                echo "                  </form>\n";
                echo "                  </td>\n";
				echo "   			</tr>\n";
                $coid=$row0co['oid'];
			}
			
			echo "			</td>\n";
			echo "   	</tr>\n";
			echo "	</table>\n";
		}
	}
}

function leads_general_fin()
{
	global $retar;
	
	//echo "Finan Contact Summary<br>";

	$qryALTo = "SELECT securityid,altoffices FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resALTo = mssql_query($qryALTo);
	$rowALTo = mssql_fetch_array($resALTo);

	if ($rowALTo['altoffices']!=0)
	{
		$alto=explode(",",$rowALTo['altoffices']);
	}

	if ($_SESSION['rlev'] >= 5)
	{
		if (empty($_REQUEST['subq']))
		{
			//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1';";
			$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1';";
		}
		elseif ($_REQUEST['subq']=="drange")
		{
			if (empty($_REQUEST['d2']))
			{
				//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added='".$_REQUEST['d1']."';";
				$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND finan_date='".$_REQUEST['d1']."';";
			}
			else
			{
				//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
				$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND finan_date BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59';";
			}
		}
	}
	else
	{
		echo "<b>You do not have appropriate Access to View this Resource.</b>";
		exit;
	}
	
	//echo $qry1."<br>";

	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);

	if ($_REQUEST['call']=="finleads")
	{
		$qrypre1 = "SELECT rid,rcode,descrip FROM tfinanresultcodes ORDER BY rcode;"; //
		$respre1 = mssql_query($qrypre1);

		while ($rowpre1 = mssql_fetch_array($respre1))
		{
			$srccodes[$rowpre1['rid']]=$rowpre1['rcode'];
		}
		
		//echo $qrypre1."<BR>";
	}
	else
	{
		$srccodes=array(0=>'',4=>'BH Finance',1=>'Winners',2=>'Cust Finan',3=>'Cash');
	}

	$rdate = date("m-d-Y", time());
	
	//print_r($srccodes);
	
	if ($_REQUEST['call']=="finleads")
	{
		echo "<table align=\"center\">\n";
	}
	else
	{
		echo "<table align=\"center\" width=\"60%\">\n";
	}
	
	echo "   <tr>\n";
	echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
	echo "         <table class=\"outer\" width=\"100%\">\n";
	echo "   			<tr>\n";

	if ($_REQUEST['call']=="finleads")
	{
		echo "      			<td class=\"gray\" align=\"left\" valign=\"bottom\" NOWRAP>&nbsp<b>Finance Result Report</b></td>\n";
	}
	else
	{
		echo "      			<td class=\"gray\" align=\"left\" valign=\"bottom\" NOWRAP>&nbsp<b>Finance Source Report</b></td>\n";
	}

	echo "      			<td class=\"gray\" align=\"right\" valign=\"bottom\">\n";
	echo "         			<table width=\"100%\">\n";
	echo "         			<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";

	if ($_REQUEST['call']=="finleads")
	{
		echo "						<input type=\"hidden\" name=\"call\" value=\"finleads\">\n";
	}
	else
	{
		echo "						<input type=\"hidden\" name=\"call\" value=\"sfinleads\">\n";
	}

	echo "						<input type=\"hidden\" name=\"subq\" value=\"drange\">\n";
	echo "   						<tr>\n";
	echo "      						<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";

	if (!empty($_REQUEST['d1']))
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\" value=\"".$_REQUEST['d1']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
	}

	echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";

	if (!empty($_REQUEST['d2']))
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['d2']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
	}

	echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";

	echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
	echo "      						</td>\n";
	echo "      						<td class=\"gray\" align=\"right\">Display Breakout:</font>\n";

	if (isset($_REQUEST['brkout']) && $_REQUEST['brkout']==1)
	{
		echo "									<input class=\"transnb\" type=\"checkbox\" name=\"brkout\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "									<input class=\"transnb\" type=\"checkbox\" name=\"brkout\" value=\"1\">\n";
	}

	echo "      						</td>\n";
	echo "      						<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
	echo "      						</td>\n";
	echo "   						</tr>\n";
	echo "         				</form>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch1'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch1'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "						</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";

	//if (isset($_REQUEST['full'])&&$_REQUEST['full']==1)
	if (!empty($_REQUEST['subq']) && $_REQUEST['subq']=="drange")
	{
		if ($row1['cnt'] > 0)
		{
			echo "   			<tr>\n";
			echo "      			<td colspan=\"2\" class=\"gray\" align=\"left\" valign=\"top\">\n";
			echo "         			<table width=\"100%\">\n";

			if ($_SESSION['rlev'] >=5)
			{
				if ($_SESSION['rlev'] >=9)
				{
					$qry4 = "SELECT DISTINCT(finan_from),officeid,name FROM offices WHERE active=1 and finan_off=1 ORDER BY name;";
					$res4 = mssql_query($qry4);
				}
				else
				{
					$qry4 = "SELECT DISTINCT(finan_from),officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."' AND active=1 ORDER BY name;";
					$res4 = mssql_query($qry4);
				}

				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" width=\"150px\">&nbsp<b>Office</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"50px\">Total</td>\n";

				if (!empty($_REQUEST['brkout']) && $_REQUEST['brkout']==1)
				{
					foreach($srccodes as $n1 => $v1)
					{
						if ($n1!=0)
						{
							echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">".$v1."</td>\n";
						}
					}
				}

				echo "   			</tr>\n";

				$ocon	=0;
				$o_ar	=array();

				while ($row4 = mssql_fetch_array($res4))
				{
					$socon=0;
					$so_ar=array();
					$qry4a = "SELECT officeid,name FROM offices WHERE active=1 and finan_from=".$row4['officeid']." ORDER BY name;";
					$res4a = mssql_query($qry4a);
					
					//echo $qry4a."<br>";
					echo "   			<tr>\n";
					echo "      			<td class=\"blu_und\" align=\"left\" valign=\"bottom\" width=\"200px\"><b>".$row4['name']."</b></td>\n";
					echo "      			<td class=\"blu_und\" align=\"right\" valign=\"bottom\">&nbsp</td>\n";

					if (!empty($_REQUEST['brkout']) && $_REQUEST['brkout']==1)
					{
						foreach($srccodes as $n1 => $v1)
						{
							if ($n1!=0)
							{
								echo "      			<td class=\"blu_und\" align=\"center\" valign=\"bottom\">&nbsp</td>\n";
							}
						}
					}

					echo "   			</tr>\n";
					
					while ($row4a = mssql_fetch_array($res4a))
					{
						if ($_SESSION['rlev'] >=8) // Anyone with Report Level 8+
						{
							$tt="1";
							leads_gen_sub_fin($srccodes,$row4a['officeid'],$row4a['name']);
							$ocon=$ocon+$retar[0];
							$socon=$socon+$retar[0];
							//$oicon=$oicon+$retar[1];
							//$omcon=$omcon+$retar[2];
							
							foreach($srccodes as $nX => $vX)
							{
								if (is_array($retar[3]))
								{
									$o_ar[$nX]	=$o_ar[$nX]+$retar[3][$nX];
									$so_ar[$nX]	=$so_ar[$nX]+$retar[3][$nX];
								}
								else
								{
									$o_ar[$nX]	=$o_ar[$nX]+0;
									$so_ar[$nX]	=$so_ar[$nX]+0;
								}
							}	
						}
						elseif ($_SESSION['rlev'] >=7 && in_array($row4['officeid'],$alto)) // Anyone with Report Level 7+
						{
							$tt="2";
							leads_gen_sub_fin($srccodes,$row4a['officeid'],$row4a['name']);
							$ocon=$ocon+$retar[0];
							$socon=$socon+$retar[0];
							//$oicon=$oicon+$retar[1];
							//$omcon=$omcon+$retar[2];
							
							foreach($srccodes as $nX => $vX)
							{
								if (is_array($retar[3]))
								{
									$o_ar[$nX]	=$o_ar[$nX]+$retar[3][$nX];
									$so_ar[$nX]	=$so_ar[$nX]+$retar[3][$nX];
								}
								else
								{
									$o_ar[$nX]	=$o_ar[$nX]+0;
									$so_ar[$nX]	=$so_ar[$nX]+0;
								}
							}	
						}
						elseif ($_SESSION['rlev'] >=6 && $row4['officeid']==$_SESSION['officeid']) // Anyone with Report Level 5+
						{
							$tt="3";
							leads_gen_sub_fin($srccodes,$row4a['officeid'],$row4a['name']);
							$ocon=$ocon+$retar[0];
							$socon=$socon+$retar[0];
							//$oicon=$oicon+$retar[1];
							//$omcon=$omcon+$retar[2];
							
							foreach($srccodes as $nX => $vX)
							{
								if (is_array($retar[3]))
								{
									$o_ar[$nX]	=$o_ar[$nX]+$retar[3][$nX];
									$so_ar[$nX]	=$so_ar[$nX]+$retar[3][$nX];
								}
								else
								{
									$o_ar[$nX]	=$o_ar[$nX]+0;
									$so_ar[$nX]	=$so_ar[$nX]+0;
								}
							}	
						}
					}
					
					//$socon=$socon+$ocon;
					echo "   			<tr>\n";
					echo "      			<td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"150px\">&nbsp<b>SubTotal</b></td>\n";
					echo "      			<td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"50px\"><b>".$socon."</b></td>\n";
					
					if (!empty($_REQUEST['brkout']) && $_REQUEST['brkout']==1)
					{
						foreach($srccodes as $n1 => $v1)
						{
							if ($n1!=0)
							{
								echo "      			<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$so_ar[$n1]."</b></td>\n";
							}
						}
					}
	
					echo "   			</tr>\n";
					echo "   			<tr>\n";
					echo "      			<td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"150px\">&nbsp</td>\n";
					echo "      			<td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"50px\">&nbsp</td>\n";
					
					if (!empty($_REQUEST['brkout']) && $_REQUEST['brkout']==1)
					{
						foreach($srccodes as $n1 => $v1)
						{
							if ($n1!=0)
							{
								echo "      			<td class=\"wh_und\" align=\"center\" valign=\"bottom\"&nbsp</td>\n";
							}
						}
					}
	
					echo "   			</tr>\n";
					$socon=0;
					$so_ar=array();
				}

				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"150px\">&nbsp<b>Grand Total</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"50px\"><b>".$ocon."</b></td>\n";
				
				if (!empty($_REQUEST['brkout']) && $_REQUEST['brkout']==1)
				{
					foreach($srccodes as $n1 => $v1)
					{
						if ($n1!=0)
						{
							echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>".$o_ar[$n1]."</b></td>\n";
						}
					}
				}

				echo "   			</tr>\n";
			}

			echo "						</table>\n";
			echo "					</td>\n";
			echo "   			</tr>\n";
		}
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function leads_gen_sub_fin($srccodes,$officeid,$oname)
{
	global $retar;
	$s_ar=array();

	if (empty($_REQUEST['subq']))
	{
		//$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1';";
		$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND finan_src!=0 AND dupe!='1';";
	}
	elseif ($_REQUEST['subq']=="drange")
	{
		//$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
		$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND finan_src!=0 AND dupe!='1' AND finan_date BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59';";

	}
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	echo "   			<tr>\n";
	echo "      			<td class=\"wh_und\" align=\"left\" valign=\"bottom\" width=\"150px\" NOWRAP>&nbsp&nbsp".$oname."</td>\n";
	echo "      			<td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"50px\"><b>".$row5['cnt']."</b></td>\n";

	if ($_REQUEST['call']=="finleads")
	{
		$ffield="finan_status";
	}
	else
	{
		$ffield="finan_src";
	}

	if (!empty($_REQUEST['brkout']) && $_REQUEST['brkout']==1)
	{
		foreach($srccodes as $n1 => $v1)
		{
			if ($n1!=0)
			{
				// Tabulates statusids from leadstatuscodes
				if (empty($_REQUEST['subq']))
				{
					//$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND ".$ffield."='".$v1."';";
					$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND ".$ffield."='".$n1."';";
				}
				elseif ($_REQUEST['subq']=="drange")
				{
					//$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND ".$ffield."='".$v1."' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
					$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND ".$ffield."='".$n1."' AND finan_date BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59';";
		
				}
				$res5z = mssql_query($qry5z);
				$row5z = mssql_fetch_array($res5z);
				
				//echo $qry5z."<br>";
		
				if ($row5z['cnt'] < 1)
				{
					$s_cnt="";
				}
				else
				{
					$s_cnt=$row5z['cnt'];
						
					if (!is_numeric($s_cnt) || $s_cnt < 1)
					{
						$s_ar[$n1]=0;
					}
					else
					{
						$s_ar[$n1]=$s_cnt;
					}
				}
		
				echo "      			<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$s_cnt."</b></td>\n";
			}
		}
	}

	echo "   			</tr>\n";

	$retar=array(0=>$row5['cnt'],1=>$row5b['cnt'],2=>$row5a['cnt'],$s_ar);
	//return $retar;
	//}
}

function statusreport()
{
	error_reporting(E_ALL);
	
	//show_post_vars();
	if (empty($_REQUEST['d1']) && empty($_REQUEST['d2']) && empty($_REQUEST['ssearch']))
	{
		echo "<font color=\"red\"><b>Error</b></font><br>Not enough search parameters!<br>Click BACK and Add a \"Search by\" parameter or Date Range.";
		exit;
	}
	
	$acclist=explode(",",$_SESSION['aid']);
	
	$qry0 = "SELECT officeid,name,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT name FROM offices WHERE officeid='".$_REQUEST['foid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2 = "SELECT finan_off,finan_from FROM offices WHERE officeid='".$_REQUEST['oid']."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
	
	$qry2 = "SELECT * FROM offices WHERE finan_from='".$_SESSION['officeid']."' order by name ASC;";
	$res2 = mssql_query($qry2);
	
	if ($_REQUEST['iactive']==0)
	{
		$iactivetxt	="Yearly";
	}
	else
	{
		$iactivetxt	="Active";
	}
	
	$qry  = "SELECT ";
	$qry .= "	C.cid, ";
	$qry .= "	C.securityid, ";
	$qry .= "	C.clname, ";
	$qry .= "	C.cfname, ";
	$qry .= "	C.ccity, ";
	$qry .= "	C.scity, ";
	$qry .= "	C.finan_src, ";
	$qry .= "	C.estid, ";
	$qry .= "	C.jobid, ";
	$qry .= "	C.njobid, ";
	$qry .= "	F.*, ";
	$qry .= "	(SELECT name FROM offices WHERE officeid=F.officeid) as rfoid, ";
	$qry .= "	(SELECT lenderabbrev FROM tlender WHERE lid=F.lender) as rlndnm, ";
	$qry .= "	(SELECT rcode FROM tfinanresultcodes WHERE rid=F.reasnotclosed) as rsm, ";
	$qry .= "	(SELECT lname FROM security WHERE securityid=C.securityid) as sname, ";
	$qry .= "	(SELECT contractamt FROM jdetail WHERE officeid=C.officeid AND jobid=C.jobid AND jadd=0) as rctamt, ";
	$qry .= "	(SELECT contractdate FROM jdetail WHERE officeid=C.officeid AND jobid=C.jobid AND jadd=0) as rctdt, ";
	$qry .= "	(SELECT digdate FROM jobs WHERE officeid=C.officeid AND jobid=C.jobid) as rdgdt ";
	$qry .= "FROM ";
	$qry .= "	cinfo as C ";
	$qry .= "INNER JOIN ";
	$qry .= "	tfinan_detail as F ";
	$qry .= "ON ";
	$qry .= "	C.cid=F.cid ";
	$qry .= "WHERE ";
	$qry .= "	F.inclstatreport='".$_REQUEST['iactive']."' ";
	
	if ($_SESSION['rlev']==1 && $_SESSION['llev']==1 && $row2['finan_off']==0)
	{
		$qry .= "	and C.securityid='".$_SESSION['securityid']."' ";	
	}
	
	if ($_REQUEST['finansrc']!=0)
	{
		$qry .= "	and C.finan_src='".$_REQUEST['finansrc']."' ";
	}
	
	if ($_REQUEST['lientype']!=0)
	{
		$qry .= "	and F.lientype='".$_REQUEST['lientype']."' ";
	}
	
	$qry .= "	and ".$_REQUEST['field']." LIKE '".$_REQUEST['ssearch']."%' ";
	
	if (!empty($_REQUEST['oid']) && $_REQUEST['oid']!=0)
	{
		$qry .= "	and C.officeid='".$_REQUEST['oid']."' ";	
	}
	else
	{
		if ($row0['finan_off']==1)
		{
			$qry .= "	and C.finan_from='".$_SESSION['officeid']."' ";
		}
		else
		{
			$qry .= "	and C.officeid='".$_SESSION['officeid']."' ";
		}
	}
	
	if (!empty($_REQUEST['d1']) && valid_date($_REQUEST['d1']) && !empty($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
	{
		$qry .= "	and F.recdate >='".$_REQUEST['d1']."' ";
		$qry .= "	and F.recdate <='".$_REQUEST['d2']." 11:59:59' ";
		$dtext="Date Range: ".date("m/d/y",strtotime($_REQUEST['d1']))." - ".date("m/d/y",strtotime($_REQUEST['d2']));
	}
	else
	{
		$dtext="";
	}
	
	$qry .= "ORDER BY ";
	$qry .= "	".$_REQUEST['order']." ".$_REQUEST['ascdesc'].";";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	//echo $qry."<br>";

	echo "<table>\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\">&nbsp<b>".$iactivetxt." Status Report for</b> ".$row0['name']."</td>\n";
	echo "					<td class=\"gray\" align=\"center\">".$dtext."</td>\n";
	echo "					<td class=\"gray\" align=\"right\"><b><font color=\"red\">".$nrow."</font> Record(s) Found</b></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "      </td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" border=\"0\" width=\"850px\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"left\"><b>Name</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"left\"><b>City</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"left\"><b>Sales Off</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"left\"><b>Sales Rep</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>Stage</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>Contr Date</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>Finan Rec'd</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>Apprv Date</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>Broker</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>Ctr Amt</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>Fin Amt</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>Cls Dt</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>Dig Dt</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><b>SM</b></td>\n";
	echo "				</tr>\n";

	if ($nrow > 0)
	{
		$rcnt=0;
		while ($row = mssql_fetch_array($res))
		{
			$rcnt++;
			
			if (!empty($row['rctdt']) && strtotime($row['rctdt']) > strtotime('1/1/1980'))
			{
				$rcdt=date("m/d/y",strtotime($row['rctdt']));
			}
			else
			{
				$rcdt="";
			}
			
			if (!empty($row['recdate']) && strtotime($row['recdate']) > strtotime('1/1/1980'))
			{
				$recdate=date("m/d/y",strtotime($row['recdate']));
			}
			else
			{
				$recdate="";
			}
			
			if (!empty($row['dateapprove']) && strtotime($row['dateapprove']) > strtotime('1/1/1980'))
			{
				$dateapprove=date("m/d/y",strtotime($row['dateapprove']));
			}
			else
			{
				$dateapprove="";
			}
			
			if (!empty($row['closedate']) && strtotime($row['closedate']) > strtotime('1/1/1980'))
			{
				$closedate=date("m/d/y",strtotime($row['closedate']));
			}
			else
			{
				$closedate="";
			}
			
			if (!empty($row['rdgdt']) && strtotime($row['rdgdt']) > strtotime('1/1/1980'))
			{
				$rdgdt=date("m/d/y",strtotime($row['rdgdt']));
			}
			else
			{
				$rdgdt="";
			}
			
			echo "				<tr>\n";
			echo "					<td class=\"blu_und\" align=\"right\" valign=\"top\"><b>".$rcnt.".</b></td>\n";
			echo "					<td class=\"blu_und\" align=\"left\" valign=\"top\">".$row['clname'].", ".$row['cfname']."</td>\n";
			
			if (!empty($row['scity']) && strlen($row['scity']) >= 2)
			{
				echo "					<td class=\"blu_und\" align=\"left\" valign=\"top\">".$row['scity']."</td>\n";
			}
			else
			{
				echo "					<td class=\"blu_und\" align=\"left\" valign=\"top\">".$row['ccity']."</td>\n";
			}
			
			echo "					<td class=\"blu_und\" align=\"left\" valign=\"top\" NOWRAP>".$row['rfoid']."</td>\n";
			echo "					<td class=\"blu_und\" align=\"left\" valign=\"top\">".$row['sname']."</td>\n";
			echo "					<td class=\"blu_und\" align=\"center\" valign=\"top\">\n";
			
			if ($row['njobid']!="0")
			{
				echo "Job";
			}
			elseif ($row['jobid']!="0")
			{
				echo "Contract";
			}
			elseif ($row['estid']!=0)
			{
				echo "Estimate";
			}
			
			echo "					</td>\n";
			echo "					<td class=\"blu_und\" align=\"center\" valign=\"top\">".$rcdt."</td>\n";
			echo "					<td class=\"blu_und\" align=\"center\" valign=\"top\">".$recdate."</td>\n";
			echo "					<td class=\"blu_und\" align=\"center\" valign=\"top\">".$dateapprove."</td>\n";
			echo "					<td class=\"blu_und\" align=\"center\" valign=\"top\">\n";
			
			if ($row['finan_src']==3)
			{
				echo "CSH";
			}
			elseif ($row['finan_src']==2)
			{
				echo "CF";
			}
			elseif ($row['finan_src']==1)
			{
				echo "WF";
			}
	
			echo "					</td>\n";
			echo "					<td class=\"blu_und\" align=\"right\" valign=\"top\">".number_format($row['rctamt'])."</td>\n";
			echo "					<td class=\"blu_und\" align=\"right\" valign=\"top\">".number_format($row['amtfinan'])."</td>\n";
			echo "					<td class=\"blu_und\" align=\"center\" valign=\"top\">".$closedate."</td>\n";
			echo "					<td class=\"blu_und\" align=\"center\" valign=\"top\">".$rdgdt."</td>\n";
			echo "					<td class=\"blu_und\" align=\"center\" valign=\"top\">".$row['rsm']."</td>\n";
			echo "				</tr>\n";
			
			if (!empty($_REQUEST['comment']) && is_array($_REQUEST['comment']))
			{
				echo "				<tr>\n";
				echo "					<td class=\"gray\" align=\"left\" colspan=\"15\">\n";
				echo "						<table>\n";
				echo "							<tr>\n";
				echo "								<td class=\"gray\" align=\"right\" NO WRAP><b>Comments</b></td>\n";
				echo "								<td width=\"50px\" class=\"ltgray_und\" align=\"center\"><b><i>Date</i></b></td>\n";
				echo "								<td width=\"50px\" class=\"ltgray_und\" align=\"left\"><b><i>Name</i></b></td>\n";
				echo "								<td width=\"50px\" class=\"ltgray_und\" align=\"center\"><b><i>Stage</i></b></td>\n";
				echo "								<td class=\"ltgray_und\" align=\"left\" colspan=\"11\">&nbsp</td>\n";
				echo "							</tr>\n";
				
				foreach ($_REQUEST['comment'] as $cn => $cv)
				{
					if ($cv=="f")
					{
						viewfcomments($row['fcomment']);
					}
					elseif ($cv=="i")
					{	
						viewicomments($row['cid']);
					}
					elseif ($cv=="e")
					{	
						viewecomments($row['cid']);
					}
				}
				
				echo "						</table>\n";
				echo "					</td>\n";
				echo "				</tr>\n";
			}
		}
	}
	
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	//show_post_vars();
}

function viewfcomments($c)
{
	//$qry0 = "SELECT lname FROM security WHERE securityid='".$s."';";
	//$res0 = mssql_query($qry0);
	//$row0 = mssql_fetch_array($res0);
	
	if (!empty($c) && strlen($c) >= 3)
	{
		echo "				<tr>\n";
		echo "					<td class=\"gray\">&nbsp</td>\n";
		echo "					<td width=\"50px\" class=\"wh_und\">&nbsp</td>\n";
		echo "					<td width=\"50px\" class=\"wh_und\">&nbsp</td>\n";
		echo "					<td width=\"50px\" class=\"wh_und\" align=\"center\">Fee</td>\n";
		echo "					<td width=\"650px\" class=\"wh_und\" colspan=\"11\">\n";
		echo 							stripcslashes(removequote($c));
		echo "					</td>\n";
		echo "				</tr>\n";
	}
}

function add_days($d,$n)
{
	$xxy=strtotime($d);
	$xxz=$n * 84600;
	$xxa=$xxy+$xxz;
	$xxx=date("m/d/y",$xxa);
	
	return $xxx;
}

function viewecomments($cid)
{
	$qry = "SELECT TOP 1 mdate FROM chistory WHERE custid='".$cid."' order by mdate asc;";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);

	if (!empty($_REQUEST['dlimit']) && $_REQUEST['dlimit']=="A" && $nrow==0)
	{
		$qry0 = "SELECT * FROM chistory WHERE custid='".$cid."' ORDER by mdate DESC;";
	}
	else
	{
		if (!empty($_REQUEST['rlimit']) && $_REQUEST['rlimit']==1)
		{
			$d=array(date("m/d/y",strtotime($_REQUEST['d1'])),add_days(date("m/d/y",strtotime($_REQUEST['d1'])),$_REQUEST['dlimit']));
		}
		else
		{
			$d=array(date("m/d/y",strtotime($row['mdate'])),add_days(date("m/d/y",strtotime($row['mdate'])),$_REQUEST['dlimit']));
		}
		
		$qry0 = "SELECT * FROM chistory WHERE custid='".$cid."' AND mdate BETWEEN '".$d[0]."' AND '".$d[1]." 11:59:59' ORDER by mdate DESC;";
	}
	
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
				
	if ($nrow0 > 0)
	{	
		while ($row0= mssql_fetch_array($res0))
		{
			$qryZa1 = "SELECT lname FROM security WHERE securityid='".$row0['secid']."';";
			$resZa1 = mssql_query($qryZa1);
			$rowZa1 = mssql_fetch_array($resZa1);
			
			$elname	=	$rowZa1['lname'];
			
			if ($row0['act']=="leads")
			{
				$stage="Lead";
			}
			elseif ($row0['act']=="reports")
			{
				$stage="Report";
			}
			elseif ($row0['act']=="est")
			{
				$stage="Estimate";
			}
			elseif ($row0['act']=="contract")
			{
				$stage="Contract";
			}
			elseif ($row0['act']=="job")
			{
				$stage="Job";
			}
			elseif ($row0['act']=="mas")
			{
				$stage="MAS";
			}
			elseif ($row0['act']=="fin")
			{
				$stage="Finance";
			}
			else
			{
				$stage="";
			}
			
			echo "							<tr>\n";
			echo "								<td class=\"gray\" align=\"left\"></td>\n";
			echo "								<td width=\"50px\" class=\"wh_und\" align=\"center\" valign=\"top\">".date("m/d/y",strtotime($row0['mdate']))."</td>\n";
			echo "								<td width=\"50px\" class=\"wh_und\" align=\"left\" valign=\"top\">".$elname."</td>\n";
			echo "								<td width=\"50px\" class=\"wh_und\" align=\"center\" valign=\"top\">".$stage."</td>\n";
			echo "								<td width=\"650px\" class=\"wh_und\" align=\"left\" colspan=\"11\" valign=\"top\">".$row0['mtext']."</td>\n";
			echo "							</tr>\n";
		}
	}
}

function viewicomments($cid)
{
	$qry = "SELECT TOP 1 adate FROM tfinanicomments WHERE cid='".$cid."' order by adate asc;";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);

	if (!empty($_REQUEST['dlimit']) && $_REQUEST['dlimit']=="A" || $nrow==0)
	{
		$qry0 = "SELECT * FROM tfinanicomments WHERE cid='".$cid."' ORDER by adate DESC;";
	}
	else
	{
		if (!empty($_REQUEST['rlimit']) && $_REQUEST['rlimit']==1)
		{
			$d=array(date("m/d/y",strtotime($_REQUEST['d1'])),add_days(date("m/d/y",strtotime($_REQUEST['d1'])),$_REQUEST['dlimit']));
		}
		else
		{
			$d=array(date("m/d/y",strtotime($row['adate'])),add_days(date("m/d/y",strtotime($row['adate'])),$_REQUEST['dlimit']));
		}
		
		$qry0 = "SELECT * FROM tfinanicomments WHERE cid='".$cid."' AND adate BETWEEN '".$d[0]."' AND '".$d[1]." 11:59:59' ORDER by adate DESC;";
	}
	
	//$qry0 = "SELECT * FROM tfinanicomments WHERE cid='".$cid."' ORDER by adate DESC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{	
		while ($row0= mssql_fetch_array($res0))
		{
			$qry0a = "SELECT lname FROM security WHERE securityid='".$row0['secid']."';";
			$res0a = mssql_query($qry0a);
			$row0a = mssql_fetch_array($res0a);
			
			$ilname	=	$row0a['lname'];

			echo "							<tr>\n";
			echo "								<td class=\"gray\" align=\"left\"></td>\n";
			echo "								<td width=\"50px\" class=\"wh_und\" align=\"center\" valign=\"top\">".date("m/d/y",strtotime($row0['adate']))."</td>\n";
			echo "								<td width=\"50px\" class=\"wh_und\" align=\"left\" valign=\"top\">".$ilname."</td>\n";
			echo "								<td width=\"50px\" class=\"wh_und\" align=\"center\" valign=\"top\">Internal</td>\n";
			echo "								<td width=\"650px\" class=\"wh_und\" align=\"left\" colspan=\"11\" valign=\"top\">".$row0['mbody']."</td>\n";
			echo "							</tr>\n";
		}
	}
}

function fnexport()
{
	error_reporting(E_ALL);
	unset($_SESSION['tqry']);
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
    
    $alloff=0;
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
	
	$qry2 = "SELECT * FROM offices WHERE finan_from='".$_SESSION['officeid']."' order by name ASC;";
	$res2 = mssql_query($qry2);
	
	$qry2a = "SELECT * FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
	
	$qry2b = "SELECT officeid,name FROM offices WHERE finan_off='1' ORDER BY name;";
	$res2b = mssql_query($qry2b);
    
    $qry4 = "SELECT * FROM alt_security_levels WHERE sid='".$_SESSION['securityid']."';";
	$res4 = mssql_query($qry4);
	$nrow4= mssql_num_rows($res4);
	
	if ($nrow4 > 0)
	{
		while ($row4 = mssql_fetch_array($res4))
		{
			$altoidacc[$row4['oid']]=explode(",",$row4['slevel']);
            $alloff++;
		}
	}

    $qry5  = "select ";
	$qry5 .= "	s.officeid, ";
	$qry5 .= "	s.securityid, ";
	$qry5 .= "	s.lname, ";
	$qry5 .= "	s.fname, ";
	$qry5 .= "	s.slevel, ";
	$qry5 .= "	o.officeid, ";
	$qry5 .= "	o.name ";
	$qry5 .= "from ";
	$qry5 .= "	offices as o ";
	$qry5 .= "inner join ";
	$qry5 .= "	security as s ";
	$qry5 .= "on  ";
	$qry5 .= "	o.officeid=s.officeid ";
	$qry5 .= "where ";
	$qry5 .= "	o.finan_off=1 ";
    $qry5 .= "	and substring(s.slevel,13,13) > 0 ";
	$qry5 .= "	and o.officeid=".$_SESSION['officeid']." ";
	
    if ($row2a['finan_off']==1)
	{
        if ($_SESSION['llev'] < 6)
        {
            $qry5 .= "	and s.securityid='".$_SESSION['securityid']."' ";
            //$qry2 .= "	and s.securityid='".$closer[1]."' ";
        }
    }
    
	$qry5 .= "order by ";
	$qry5 .= "	o.name asc,substring(s.slevel,13,13) desc,s.lname asc;";
	$res5 = mssql_query($qry5);
	$nrow5= mssql_num_rows($res5);

	$acclist		=explode(",",$_SESSION['aid']);

	$tindex=1;
    //show_array_vars($altoidacc);
    //echo "Trip<br>";
	echo "<table width=\"400px\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td class=\"ltgray_und\" align=\"center\"><b>Finance Summary Report Query</b></td>\n";
	echo "								<td class=\"ltgray_und\" align=\"center\"><font size=\"2\"><a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=hp&hpc=FSR\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">help</a></font></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td colspan=\"2\">\n";
	echo "         							<form name=\"tsearch1\" action=\"export/fnsummary.php\" method=\"post\" target=\"_new\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                              			<td align=\"right\"><b>Office:</b></td>\n";
	echo "                              			<td align=\"left\" valign=\"top\">\n";
	echo "												<select name=\"foid\" tabindex=\"". $tindex++ ."\" title=\"Select an Office\">\n";
    
    if ($_SESSION['llev'] >= 9)
    {
        echo "													<option value=\"0\">All Offices</option>\n";
    }
    elseif ($_SESSION['llev'] >= 6 && $alloff > 0)
    {
        echo "													<option value=\"0\">All Offices</option>\n";
    }
	
    if ($_SESSION['llev'] >= 9)
    {
        while ($row2b=mssql_fetch_array($res2b))
        {
            echo "													<option value=\"".$row2b['officeid']."\">".$row2b['name']."</option>\n";
        }
    }
    else
    {
        while ($row2b=mssql_fetch_array($res2b))
        {
            if (array_key_exists($row2b['officeid'],$altoidacc) && $altoidacc[$row2b['officeid']][3] >= 6)
            {
                echo "													<option value=\"".$row2b['officeid']."\">".$row2b['name']."</option>\n";
            }
        }		
    }
    
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
    
    if ($row2a['finan_off']==1)
	{
        echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Assigned:</b></td>\n";
		echo "								<td align=\"left\">\n";
		
		if ($_SESSION['llev'] >= 5)
		{
			if ($nrow5 > 0)
			{
				echo "      							<select name=\"assigned\">\n";
				
				if ($_SESSION['llev'] >= 6)
				{
					echo "      							<option value=\"0\">All Fin Reps</option>\n";
				}
				
                $x=0;
				while ($row5= mssql_fetch_array($res5))
				{
                    if ($x==0 || $x!=$row5['officeid'])
					{
						echo "      							<optgroup class=\"plain\" label=\"".$row5['name']."\">\n";
					}
                    
					if ($_SESSION['llev'] < 6 && $_SESSION['securityid']==$row5['securityid'])
					{
						echo "      							<option class=\"fontblue\" value=\"".$row5['securityid']."\" SELECTED>".$row5['lname'].", ".$row5['fname']." - ".$row5['name']."</option>\n";
					}
					else
					{
						echo "      							<option value=\"".$row5['securityid']."\">".$row5['lname'].", ".$row5['fname']." - ".$row5['name']."</option>\n";
					}
				}
                $x=$row5['officeid'];
				
				echo "      							</select\">\n";
			}
		}
		else
		{
            $row5= mssql_fetch_array($res5);
			echo $row5['lname'] .", " . $row5['fname'] . " - " . $row5['name'];
			echo "<input type=\"hidden\" name=\"assigned\" value=\"".$_SESSION['securityid']."\">\n";
		}
		
		echo "								</td>\n";
		echo "							</tr>\n";
    }
	
	//if ($row2a['finan_off']==0)
	//{
		echo "									<tr>\n";
		echo "                              	    <td align=\"right\"><b>Fin Source:</b></td>\n";
		echo "                              	    <td align=\"left\" valign=\"top\">\n";
		echo "                               	    <select name=\"finansrc\" tabindex=\"". $tindex++ ."\" title=\"Set the Finance Source\">\n";
		echo "                                        	<option value=\"0\">Any</option>\n";
		echo "                                        	<option value=\"1\">Winners</option>\n";
		echo "                                        	<option value=\"2\">Cust Finan</option>\n";
		echo "                                        	<option value=\"3\">Cash</option>\n";
        echo "                                        	<option value=\"4\" SELECTED>BH Finance</option>\n";
		echo "                                      </select>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
	//}
	//else
	//{
	//	echo "											<input type=\"hidden\" name=\"finansrc\" value=\"1\">\n";
	//}
	
	/*echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>Fin Source:</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"top\">\n";
	echo "                               		<select name=\"finansrc\" tabindex=\"". $tindex++ ."\" title=\"Set the Finance Source\">\n";
	echo "                                    		<option value=\"0\">Any</option>\n";
	echo "                                    		<option value=\"1\">Winners</option>\n";
	echo "                                 		   <option value=\"2\">Cust Finan</option>\n";
	echo "                               		   	<option value=\"3\">Cash</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";*/
	echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>Lien Type:</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"top\">\n";
	echo "                               		<select name=\"lientype\" tabindex=\"". $tindex++ ."\" title=\"Set the Lien Type\">\n";
	echo "                                    		<option value=\"0\">Any</option>\n";
	echo "                                    		<option value=\"1\">1st</option>\n";
	echo "                                 		   <option value=\"2\">2nd</option>\n";
	echo "                               		   	<option value=\"3\">3rd</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              		<td align=\"right\"><b>Group by:</b></td>\n";
	echo "                          		    	<td align=\"left\">\n";
	echo "                                  		<select name=\"order1\" tabindex=\"". $tindex++ ."\" title=\"Set the Field Grouping of the Search\">\n";
	echo "                                    		<option value=\"ffrom\">Fin Office</option>\n";
    echo "                                    		<option value=\"soid\" SELECTED>Sales Office</option>\n";
	echo "                                    	</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                             			 	<td align=\"right\"><b>Sort by:</b></td>\n";
	echo "                              			<td align=\"left\">\n";
	echo "                              		    	<select name=\"order2\" tabindex=\"". $tindex++ ."\" title=\"Set the Field Sort of the Search\">\n";
	echo "													<option value=\"C.clname\" SELECTED>Last Name</option>\n";
	echo "                                    				<option value=\"F.recdate\">Date Received</option>\n";
	echo "                                    				<option value=\"F.closedate\">Date Closed</option>\n";
	echo "                                    				<option value=\"F.datefeesent\">Date Sent</option>\n";
	echo "                                    				<option value=\"F.lientype\">Lien Type</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              			<td align=\"right\"><b>Order:</b></td>\n";
	echo "                              			<td align=\"left\">\n";
	echo "                                  			<select name=\"ascdesc\" tabindex=\"". $tindex++ ."\" title=\"Set the Sort Order of the Search\">\n";
	echo "                                   				<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                    				<option value=\"DESC\">Descending</option>\n";
	echo "                                    			</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              			<td align=\"right\"><b>Date Searched:</b></td>\n";	
	echo "                              			<td align=\"left\">\n";
	echo "                                    			<select name=\"dtype\" tabindex=\"". $tindex++ ."\" title=\"Set the Date Search Field\">\n";
	echo "                                    				<option value=\"closedate\" SELECTED>Date Closed</option>\n";
	echo "                                    				<option value=\"recdate\">Date Received</option>\n";
	echo "                                    				<option value=\"datefeesent\">Date Sent</option>\n";
	echo "                                 				</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                            			  	<td align=\"right\" valign=\"top\"><b>Date Range:</b></td>\n";
	echo "                                 			<td align=\"left\">\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\" tabindex=\"". $tindex++ ."\" title=\"Begin Date\">\n";
	echo "												<a href=\"javascript:cal1.popup();\" tabindex=\"". $tindex++ ."\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a><br>\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\" tabindex=\"". $tindex++ ."\" title=\"End Date\">\n";
	echo "												<a href=\"javascript:cal2.popup();\" tabindex=\"". $tindex++ ."\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "											</td>";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              			<td align=\"right\"><b>Type</b>:</td>\n";	
	echo "                              			<td align=\"left\">\n";
	echo "                                    			<select name=\"iactive\" tabindex=\"". $tindex++ ."\" title=\"Set the Type of Report\">\n";
	echo "                                    				<option value=\"99\">Both</option>\n";
	echo "                                    				<option value=\"1\" SELECTED>Status</option>\n";
	echo "                                 					<option value=\"0\">Yearly</option>\n";
	echo "                                 				</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	/*echo "										<tr>\n";
	echo "                              			<td align=\"right\"><b>Date Field:</b></td>\n";	
	echo "                              			<td align=\"left\">\n";
	echo "                                    			<select name=\"dtype\" tabindex=\"". $tindex++ ."\" title=\"Set the Date Search Field\">\n";
	echo "                                    				<option value=\"closedate\" SELECTED>Date Closed</option>\n";
	echo "                                    				<option value=\"recdate\">Date Received</option>\n";
	echo "                                    				<option value=\"datefeesent\">Date Sent</option>\n";
	echo "                                 				</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";*/
	echo "										<tr>\n";
	echo "                              			<td align=\"right\"><b>View:</b></td>\n";	
	echo "                              			<td align=\"left\">\n";
	echo "                                    			<select name=\"disp\" tabindex=\"". $tindex++ ."\" title=\"Select Yes to view in Internet Explorer or No to view as attachment in Excel\">\n";
	echo "                                    				<option value=\"inline\">Internet Explorer</option>\n";
	echo "                                    				<option value=\"attachment\" SELECTED>Excel</option>\n";
	echo "                                 				</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                                			<td align=\"right\" colspan=\"2\"><input tabindex=\"". $tindex++ ."\" class=\"buttondkgrypnl80\" type=\"submit\" value=\"View\" title=\"Click Here to view the Financial Summary Report\"></td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	
	echo "         						<script language=\"JavaScript\">\n";
	echo "         							var cal1 = new calendar2(document.forms['tsearch1'].elements['d1']);\n";
	echo "         							cal1.year_scroll = false;\n";
	echo "         							cal1.time_comp = false;\n";
	echo "         							var cal2 = new calendar2(document.forms['tsearch1'].elements['d2']);\n";
	echo "         							cal2.year_scroll = false;\n";
	echo "         							cal2.time_comp = false;\n";
	echo "         							//-->\n";
	echo "         						</script>\n";
}

function statussearch()
{
	unset($_SESSION['tqry']);
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
	
	$qry2 = "SELECT * FROM offices WHERE finan_from='".$_SESSION['officeid']."' order by name ASC;";
	$res2 = mssql_query($qry2);
	
	$qry2a = "SELECT * FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
    
    $qry3 = "SELECT officeid FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
	
	if ($row2a['finan_off']==1)
	{
		$qry2b = "SELECT * FROM offices WHERE finan_from='".$_SESSION['officeid']."' order by name ASC;";
		$res2b = mssql_query($qry2b);
	}

    $qry4  = "select ";
	$qry4 .= "	s.officeid, ";
	$qry4 .= "	s.securityid, ";
	$qry4 .= "	s.lname, ";
	$qry4 .= "	s.fname, ";
	$qry4 .= "	s.slevel, ";
	$qry4 .= "	o.officeid, ";
	$qry4 .= "	o.name ";
	$qry4 .= "from ";
	$qry4 .= "	offices as o ";
	$qry4 .= "inner join ";
	$qry4 .= "	security as s ";
	$qry4 .= "on  ";
	$qry4 .= "	o.officeid=s.officeid ";
	$qry4 .= "where ";
	$qry4 .= "	o.finan_off=1 ";
    $qry4 .= "	and substring(s.slevel,13,13) > 0 ";
	$qry4 .= "	and o.officeid=".$_SESSION['officeid']." ";
	
    if ($row2a['finan_off']==1)
	{
        if ($_SESSION['llev'] < 6)
        {
            $qry4 .= "	and s.securityid='".$_SESSION['securityid']."' ";
            //$qry2 .= "	and s.securityid='".$closer[1]."' ";
        }
    }
    
	$qry4 .= "order by ";
	$qry4 .= "	o.name asc,substring(s.slevel,13,13) desc,s.lname asc;";
	$res4 = mssql_query($qry4);
	$nrow4= mssql_num_rows($res4);

	$acclist=explode(",",$_SESSION['aid']);
	$tidx=1;
	echo "<table align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td class=\"ltgray_und\" align=\"center\"><b>Finance Status Report Query</b> (<font size=\"2\"><a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=hp&hpc=FSR\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">Help</a></font>)</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	//echo "         								<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         							<form name=\"tsearch1\" action=\"export/fnstatus.php\" method=\"post\" target=\"_new\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"statusreport\">\n";
	
	if ($row2a['finan_off']==1)
	{
		echo "											<input type=\"hidden\" name=\"foid\" value=\"".$_SESSION['officeid']."\">\n";
	}
	else
	{
		echo "											<input type=\"hidden\" name=\"foid\" value=\"".$row2a['finan_from']."\">\n";
	}
	
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                 <td class=\"gray\" rowspan=\"2\" align=\"right\" valign=\"top\"><b>Search by:</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"top\">\n";
	echo "                                    <select name=\"field\" tabindex=\"".$tidx++."\" title=\"Select the Data Field to Search\">\n";
	echo "                                    	<option value=\"C.clname\" SELECTED>Last Name</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"left\" valign=\"top\"><input tabindex=\"".$tidx++."\" class=\"bboxb\" type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo "										</tr>\n";
	
	if ($row2a['finan_off']==1)
	{
		echo "								     <tr>\n";
		echo "                              	<td class=\"gray\" align=\"right\"><b>Office:</b></td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "												<select name=\"oid\" tabindex=\"".$tidx++."\" title=\"Select an Office\">\n";
        echo "													<option value=\"0\">All Offices</option>\n";
		
		while ($row2=mssql_fetch_array($res2))
		{
			//echo "													<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
            if ($row2['officeid']==$row3['officeid'])
            {
                echo "													<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
            }
            else
            {

                echo "													<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
            }
		}
		
		echo "												</select>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
	}
	else
	{
		echo "											<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
	}
	
    if ($row2a['finan_off']==1)
	{
        echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Assigned:</b></td>\n";
		echo "								<td align=\"left\">\n";
		
		if ($_SESSION['llev'] >= 5)
		{
			if ($nrow4 > 0)
			{
				echo "      							<select name=\"assigned\">\n";
				
				if ($_SESSION['llev'] >= 6)
				{
					echo "      							<option value=\"0\">All Fin Reps</option>\n";
				}
				
                $x=0;
				while ($row4= mssql_fetch_array($res4))
				{
                    if ($x==0 || $x!=$row4['officeid'])
					{
						echo "      							<optgroup class=\"plain\" label=\"".$row4['name']."\">\n";
					}
                    
					if ($_SESSION['llev'] < 6 && $_SESSION['securityid']==$row4['securityid'])
					{
						echo "      							<option class=\"fontblue\" value=\"".$row4['securityid']."\" SELECTED>".$row4['lname'].", ".$row4['fname']." - ".$row4['name']."</option>\n";
					}
					else
					{
						echo "      							<option value=\"".$row4['securityid']."\">".$row4['lname'].", ".$row4['fname']." - ".$row4['name']."</option>\n";
					}
				}
                $x=$row4['officeid'];
				
				echo "      							</select\">\n";
			}
		}
		else
		{
            $row4= mssql_fetch_array($res4);
			echo $row4['lname'] .", " . $row4['fname'] . " - " . $row4['name'];
			echo "<input type=\"hidden\" name=\"assigned\" value=\"".$_SESSION['securityid']."\">\n";
		}
		
		echo "								</td>\n";
		echo "							</tr>\n";
    }

	echo "										<tr>\n";
	echo "                              	<td class=\"gray\" align=\"right\"><b>Fin Source:</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"finansrc\" tabindex=\"".$tidx++."\" title=\"Set the Finance Source\">\n";
	echo "                                    	<option value=\"0\" SELECTED>Any</option>\n";
	echo "                                    	<option value=\"1\">Winners</option>\n";
	echo "                                    	<option value=\"2\">Cust Finan</option>\n";
	echo "                                    	<option value=\"3\">Cash</option>\n";
	echo "                                    	<option value=\"4\">BH Finance</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td class=\"gray\" align=\"right\"><b>Lien Type:</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"lientype\" tabindex=\"".$tidx++."\" title=\"Set the Lien Type\">\n";
	echo "                                    	<option value=\"0\">Any</option>\n";
	echo "                                    	<option value=\"1\">1st</option>\n";
	echo "                                    	<option value=\"2\">2nd</option>\n";
	echo "                                    	<option value=\"3\">3rd</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td class=\"gray\" align=\"right\"><b>Group by:</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"group\" tabindex=\"".$tidx++."\" title=\"Set the Field Grouping of the Search\">\n";
	echo "                                    	<option value=\"xx\"></option>\n";
	echo "                                    	<option value=\"F.closedate\">Close Date</option>\n";
	echo "                                    </select>\n";
	echo "                                    <select name=\"ascdesc1\" tabindex=\"".$tidx++."\" title=\"Set the Group Order of the Search. A = Ascending, D = Descending\">\n";
	echo "                                    	<option value=\"ASC\">A</option>\n";
	echo "                                    	<option value=\"DESC\" SELECTED>D</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td class=\"gray\" align=\"right\"><b>Sort by:</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"order\" tabindex=\"".$tidx++."\" title=\"Set the Field Sort of the Search\">\n";
	echo "                                    	<option value=\"C.clname\" SELECTED>Last Name</option>\n";
	echo "                                    	<option value=\"rfoid\">Office Name</option>\n";
	echo "                                    	<option value=\"F.frecdate\">Fin Rec'd Date</option>\n";
	echo "                                    	<option value=\"rctdt\">Contract Date</option>\n";
	echo "                                    </select>\n";
	echo "                                    <select name=\"ascdesc2\" tabindex=\"".$tidx++."\" title=\"Set the Sort Order of the Search. A = Ascending, D = Descending\">\n";
	echo "                                    	<option value=\"ASC\" SELECTED>A</option>\n";
	echo "                                    	<option value=\"DESC\">D</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td class=\"gray\" align=\"right\" valign=\"top\"><b>Date Range:</b><br>(Optional)</td>\n";
	echo "                                 <td align=\"left\" valign=\"top\">\n";
	echo "												<input class=\"bboxbc\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\" tabindex=\"".$tidx++."\" title=\"Begin Date\"><br>\n";
	echo "												<input class=\"bboxbc\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\" tabindex=\"".$tidx++."\" title=\"End Date\">\n";
	echo "											</td>";
	echo "										</tr>\n";
	
	if ($row2a['finan_off']==1)
	{
		echo "										<tr>\n";
		echo "                              	<td class=\"gray\" align=\"right\"><b>Type:</b></td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"iactive\" tabindex=\"".$tidx++."\" title=\"Set the Sort Order of the Search\">\n";
		echo "                                    	<option value=\"1\" SELECTED>Status</option>\n";
		echo "                                    	<option value=\"0\">Yearly</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "										<tr>\n";
		echo "										</tr>\n";
		echo "                              	<td class=\"gray\" align=\"right\" valign=\"top\"><b>Comments:</b></td>\n";
		echo "                              	<td align=\"left\" valign=\"top\" >\n";
		echo "                                    <select name=\"comment[]\" tabindex=\"".$tidx++."\" title=\"Choose the Comments field to display\" MULTIPLE>\n";
		echo "                                    	<option value=\"f\">Fee</option>\n";
		echo "                                    	<option value=\"i\">Internal</option>\n";
		echo "                                    	<option value=\"e\">External</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
	}
	else
	{
		echo "											<input type=\"hidden\" name=\"iactive\" value=\"1\">\n";
		echo "											<input type=\"hidden\" name=\"comment[]\" value=\"e\">\n";
	}
	
	echo "										<tr>\n";
	echo "                              	<td class=\"gray\" align=\"right\"><b>No. Comments:</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"top\">\n";
	echo "                                    <select name=\"dlimit\" tabindex=\"".$tidx++."\" title=\"Choose the number of Comments to include per Customer\">\n";
	echo "                                    	<option value=\"0\">All</option>\n";
	
	for ($x=1;$x <= 30;$x++)
	{
		if ($x == 5)
		{
			echo "                                    	<option value=\"".$x."\" SELECTED>".$x."</option>\n";
		}
		else
		{
			echo "                                    	<option value=\"".$x."\">".$x."</option>\n";
		}
	}
	
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>View:</b></td>\n";	
	echo "                              	<td align=\"left\">\n";
	echo "                                 	<select name=\"disp\" tabindex=\"".$tidx++."\" title=\"Select Excel to view as attachment in Excel\">\n";
	echo "                                 		<option value=\"inline\" SELECTED>Internet Explorer</option>\n";
	
	if ($row2a['finan_off']==1)
	{
		echo "                                 		<option value=\"attachment\">Excel</option>\n";
	}
	
	echo "                                 	</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "										<tr>\n";
		echo "                              			<td class=\"gray\" align=\"right\" valign=\"top\"><b>* Text Only</b></td>\n";
		echo "                                 			<td align=\"left\" valign=\"top\">\n";
		echo "												<input class=\"transnb JMStooltip\" type=\"checkbox\" name=\"textonly\" value=\"1\" tabindex=\"".$tidx++."\" title=\"Available only to tedh and sschirmer for troubleshooting\"><br>\n";
		echo "											</td>";
		echo "										</tr>\n";
	}
		
	echo "										<tr>\n";
	echo "                                 <td align=\"center\" valign=\"top\" colspan=\"2\"><input class=\"buttondkgrypnl80\" type=\"submit\" tabindex=\"".$tidx++."\" value=\"View\" title=\"Click Here to Submit the View Request\"></td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function IVR_report()
{
	error_reporting(E_ALL);
	//show_post_vars();
	
	if (isset($_REQUEST['d1']) && valid_date($_REQUEST['d1']) && isset($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
	{
		$d1=$_REQUEST['d1'];
		$d2=$_REQUEST['d2'];
	}
	else
	{
		if (isset($_REQUEST['tdate']) && $_REQUEST['tdate']==1)
		{
			echo "TDATE SET!<br>";
			$d1=date("m/d/Y",time());
			$d2=date("m/d/Y",time());
		}
		else
		{
			$d1="";
			$d2="";
		}
	}
	
	$qry0 = "SELECT * FROM offices WHERE active=1 order by grouping,name asc;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT * FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
		echo "<script type=\"text/javascript\" src=\"js/jquery_extend.js\"></script>\n";
		echo "<table align=\"center\" width=\"600px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
		echo "         <table class=\"outer\" width=\"100%\">\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\"><b>IVR Matrix Call Report</b></td>\n";
		echo "      			<td class=\"gray\" align=\"center\"><b>Date Range</b></td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\"><b>Office:</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\">\n";
		echo "         			<form id=\"IVRreport1\" name=\"IVRreport1\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"IVRreport\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"stage2\">\n";
		echo "						<select name=\"oid\">\n";
		
		if ($_SESSION['rlev'] >= 6 && $_SESSION['officeid']==89)
		{
			if ($_REQUEST['oid']==0)
			{
				echo "							<option value=\"0\" SELECTED>All</option>\n";
			}
			elseif ($_REQUEST['oid']!=0)
			{
				echo "							<option value=\"0\">All</option>\n";
			}
			
			while ($row0 = mssql_fetch_array($res0))
			{
				if ($_REQUEST['oid']==$row0['officeid'])
				{
					echo "							<option value=\"".$row0['officeid']."\" SELECTED>".$row0['name']."</option>\n";
				}
				else
				{
					echo "							<option value=\"".$row0['officeid']."\">".$row0['name']."</option>\n";
				}
			}
		}
		else
		{
			echo "							<option value=\"".$row1['officeid']."\" SELECTED>".$row1['name']."</option>\n";
		}
		
		echo "						</select>\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" colspan=\"2\" align=\"right\">\n";
		echo "         			<table>\n";
		echo "   						<tr>\n";
		echo "      						<td class=\"gray\" align=\"left\">\n";
	
		if (!empty($d1))
		{
			echo "									<input class=\"bboxbc\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\" value=\"".$d1."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxbc\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
		}
	
		if (!empty($d2))
		{
			echo "									<input class=\"bboxbc\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\" maxlength=\"10\" value=\"".$d2."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxbc\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
		}
	
		echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
		echo "      						</td>\n";
		echo "      						<td class=\"gray\" width=\"25px\" align=\"center\">\n";
        echo "						            <input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
		echo "         				</form>\n";
		echo "      						</td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
	
	if ($_SESSION['subq']=="stage2")
	{		
		if (!valid_date($d1) || !valid_date($d2))
		{
			echo "<center><b>Invalid Date</b><br>Format must be:<br> <b>mm/dd/yy or mm/dd/yyyy</b><br>Please correct and search again.<br></center>";
			exit;
		}
		
		if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
		{
			$qry2 = "SELECT ringto FROM offices WHERE officeid='".$_REQUEST['oid']."';";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);
			
			$rgto	= $row2['ringto'];
			$goid	= $_REQUEST['oid'];
		}
		else
		{
			$rgto	= 0;
			$goid	= 0;
		}
		
		$qry  = "select ";
		$qry .= "	distinct(substring(I.tollfree,1,10)) as tf, ";
		$qry .= "	(SELECT category FROM IVR_stats..tollfreetoDID WHERE tollfree=SUBSTRING(I.tollfree,1,10)) as category, ";
		$qry .= "	(SELECT description FROM IVR_stats..tollfreetoDID WHERE tollfree=SUBSTRING(I.tollfree,1,10)) as descrip, ";
		$qry .= "	(SELECT displaytollfree FROM IVR_stats..tollfreetoDID WHERE tollfree=SUBSTRING(I.tollfree,1,10)) as TollFree, ";
		$qry .= "	(SELECT tollfree FROM IVR_stats..tollfreetoDID WHERE tollfree=SUBSTRING(I.tollfree,1,10)) as TollFreeL, ";
		$qry .= "	(SELECT Active FROM IVR_stats..tollfreetoDID WHERE tollfree=SUBSTRING(I.tollfree,1,10)) as Active, ";
		$qry .= "	(SELECT rpt_display FROM IVR_stats..tollfreetoDID WHERE tollfree=SUBSTRING(I.tollfree,1,10)) as RptDsp, ";
		$qry .= "	(SELECT count(id) FROM IVR_stats..tIVR_events WHERE SUBSTRING(tollfree,1,10)=SUBSTRING(I.tollfree,1,10) ";
		
		if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
		{
			$qry .= "	and oid = I.oid ";	
		}
		
		$qry .= "			and indate >= '".$d1."' and indate <= '".$d2." 23:59:59') as Calls ";
		$qry .= "from ";
		$qry .= "	IVR_stats..tIVR_events as I ";
		$qry .= "where ";
		$qry .= "	I.tollfree is not null ";
		$qry .= "	and I.indate >= '".$d1."' ";
		$qry .= "	and I.indate <= '".$d2." 23:59:59' ";
		//$qry .= "	and RptDsp != 1 ";
		
		if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
		{
			$qry .= "	and I.oid = '".$_REQUEST['oid']."' ";	
		}
		
		$qry .= "order by ";
		$qry .= "	Calls DESC;";
		
		//echo $qry."<br>";
		
		$res 	= mssql_query($qry);
		$nrow	= mssql_num_rows($res);
		
		//echo $nrow."<br>";

		if ($nrow > 0)
		{
			$bdar = split('[-,/]', $d1);
			$edar = split('[-,/]', $d2);
			
			echo "<table align=\"center\" width=\"600px\">\n";
			echo "   <tr>\n";
			echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
			echo "         <table class=\"outer\" width=\"100%\">\n";
			echo "   			<tr>\n";
			echo "      			<td class=\"ltgray_und\" align=\"left\" NOWRAP>&nbsp</td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"left\" NOWRAP>&nbsp<b>Description</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Number</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Calls</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp</td>\n";
			echo "   			</tr>\n";
			
			$cnt=0;
			while ($row = mssql_fetch_array($res))
			{
				if ($row['RptDsp']==1)
				{
					echo "   			<tr>\n";
					echo "      			<td class=\"wh_und\" align=\"right\" NOWRAP>\n";
					
					if ($row['category']==0)
					{
						echo "&nbsp";
					}
					elseif ($row['category']==1)
					{
						echo "NAT-OPT:";
					}
					elseif ($row['category']==2)
					{
						echo "LOCAL:";
					}
					elseif ($row['category']==3)
					{
						echo "NAT-AUTO:";
					}
					elseif ($row['category']==4)
					{
						echo "BHFin:";
					}
					
					echo "					</td>\n";
					echo "      			<td class=\"wh_und\" align=\"left\" NOWRAP>&nbsp".$row['descrip']."</td>\n";
					echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>&nbsp".$row['TollFree']."</td>\n";
					echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>\n";
					
					/*
					if ($bdar[0]!=$edar[0] || $bdar[2]!=$edar[2])
					{
						echo $row['Calls'];
					}
					else
					{
					*/
						echo "						<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=IVR&oid=".$goid."&tfn=".$row['TollFreeL']."&d1=".$_REQUEST['d1']."&d2=".$_REQUEST['d2']."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$row['Calls']."</a>\n";
					//}
					
					echo "					</td>\n";
					echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>\n";
					
					echo "						<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=IVRd&oid=".$goid."&tfn=".$row['TollFreeL']."&d1=".$_REQUEST['d1']."&d2=".$_REQUEST['d2']."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">Detail</a>\n";
					
					echo "					</td>\n";
					echo "   			</tr>\n";
					$cnt=$cnt+$row['Calls'];
				}
			}
			
			echo "   			<tr>\n";
			echo "      			<td class=\"ltgray_und\" align=\"right\" NOWRAP>&nbsp</td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"right\" NOWRAP>&nbsp</td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Total</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP><b>".$cnt."</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"right\" NOWRAP>&nbsp</td>\n";
			echo "   			</tr>\n";
			echo "			</td>\n";
			echo "   	</tr>\n";
			echo "	</table>\n";
		}
	}
}

function off_feesched()
{
	error_reporting(E_ALL);
	if ($_SESSION['tlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}
	elseif ($_SESSION['tlev'] > 6)
	{
		$saddr="192.168.1.30";

		$qry0 = "SELECT id FROM zip_link;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		if (!empty($_REQUEST['req']))
		{
			$qry = "SELECT * FROM offices ORDER BY grouping,name ASC";
		}
		else
		{
			$qry = "SELECT * FROM offices WHERE active=1 ORDER BY grouping,name ASC";
		}
		//$qry = "SELECT * FROM offices ORDER BY name ASC";
		$res = mssql_query($qry);

		echo "<table align=\"center\" border=0>\n";
		echo "<tr>\n";
		echo "   <td class=\"gray\">\n";
		echo "<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
		echo "<tr>\n";
		echo "   <td class=\"gray\" colspan=\"3\"><b>Office Fee Schedule</b></td>\n";
		echo "   <td class=\"gray\" align=\"right\" colspan=\"5\"></td>\n";
		/*
		echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "   	<input type=\"hidden\" name=\"call\" value=\"off\">\n";
		echo "   	<input type=\"hidden\" name=\"subq\" value=\"add\">\n";
		echo "   <td class=\"gray\" align=\"right\"><input class=\"buttondkgry\" type=\"submit\" value=\"New Office\"></td>\n";
		echo "   </form>\n";
		echo "</tr>\n";
		*/
		echo "<tr>\n";
		echo"   <td class=\"ltgray_und\" align=\"center\"><b>OID</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Name</b></td>\n";
		echo"   <td class=\"ltgray_und\">&nbsp<b>Consult Fee</b></td>\n";
		echo"   <td class=\"ltgray_und\">&nbsp<b>Accting Fee</b></td>\n";
		echo"   <td class=\"ltgray_und\">&nbsp<b>Computer Fee</b></td>\n";
		echo"</tr>\n";

		while ($row = mssql_fetch_array($res))
		{
			echo "<tr>\n";
			echo"   <td class=\"wh_und\" align=\"right\">".$row['officeid']."&nbsp&nbsp</td>\n";

			if ($row['active']==1)
			{
				echo"   <td class=\"wh_und\"><b>".$row['name']."</b></td>\n";
			}
			else
			{
				echo"   <td class=\"wh_und\">".$row['name']."</td>\n";
			}

			echo"   <td class=\"wh_und\" align=\"right\">".number_format($row['consfee'])."</td>\n";
			echo"   <td class=\"wh_und\" align=\"right\">".number_format($row['acctfee'])."</td>\n";
			echo"   <td class=\"wh_und\" align=\"right\">".number_format($row['pacctfee'])."</td>\n";
			echo"</tr>\n";
		}

		echo "</table>\n";
		echo "</td>\n";
		echo"</tr>\n";
		echo "</table>\n";
	}
}

function off_total()
{
	$etot=0;
	$ecnt=0;

	$eqry = "select contractamt from est where officeid='".$_SESSION['officeid']."'";
	$eres = mssql_query($eqry);

	while($erow=mssql_fetch_array($eres))
	{
		$esubtot=$erow['contractamt'];
		$etot=$etot+$esubtot;
		$ecnt++;
	}

	$etot		=number_format($etot, 2, '.', ',');

	echo "<br>------------------------------------<br>";
	echo $eqry."<BR>";
	echo "<br>Retail Estimate Total ($ecnt) is: ".$etot;

	$ctot=0;
	$catot=0;
	$ccnt=0;
	$cacnt=0;

	$cqry = "select contractamt from jdetail where officeid='".$_SESSION['officeid']."' and jobid!='0' and njobid='0' and jadd=0;";
	$cres = mssql_query($cqry);

	while($crow=mssql_fetch_array($cres))
	{
		$csubtot=$crow['contractamt'];
		$ctot=$ctot+$csubtot;
		$ccnt++;
	}

	$cqry1 = "select raddnpr_man from jdetail where officeid='".$_SESSION['officeid']."' and jobid!='0' and njobid='0' and raddnpr_man!='0' and jadd!=0;";
	$cres1 = mssql_query($cqry1);

	while($crow1=mssql_fetch_array($cres1))
	{
		$csubtot1=$crow1['raddnpr_man'];
		$catot=$catot+$csubtot1;
		$cacnt++;
	}

	$catot	=number_format($catot, 2, '.', ',');
	$ctot		=number_format($ctot, 2, '.', ',');

	echo "<br>------------------------------------<br>";
	echo $cqry."<BR>";
	echo $cqry1."<BR>";
	echo "<br>Estimated Retail Contract Total ($ccnt) is: ".$ctot;
	echo "<br>Estimated Retail Contract Add Total ($cacnt) is: ".$catot;

	$jtot=0;
	$jatot=0;
	$jcnt=0;
	$jacnt=0;

	$jqry = "select contractamt from jdetail where officeid='".$_SESSION['officeid']."' and njobid!='0' and jadd=0;";
	$jres = mssql_query($jqry);

	while($jrow=mssql_fetch_array($jres))
	{
		$jsubtot=$jrow['contractamt'];
		$jtot=$jtot+$jsubtot;
		$jcnt++;
	}

	$jqry1 = "select raddnpr_man from jdetail where officeid='".$_SESSION['officeid']."' and njobid!='0' and jadd!=0;";
	$jres1 = mssql_query($jqry1);

	while($jrow1=mssql_fetch_array($jres1))
	{
		$jsubtot1=$jrow1['raddnpr_man'];
		$jatot=$jatot+$jsubtot1;
		$jacnt++;
	}

	$jatot	=number_format($jatot, 2, '.', ',');
	$jtot	=number_format($jtot, 2, '.', ',');

	echo "<br>------------------------------------<br>";
	echo $jqry."<BR>";
	echo $jqry1."<BR>";
	echo "<br>Estimated Retail Job Total ($jcnt) is: ".$jtot;
	echo "<br>Estimated Retail Job Add Total ($jacnt) is: ".$jatot;
}

function csearch_DEV()
{
	unset($_SESSION['tqry']);
	$acclist=explode(",",$_SESSION['aid']);
    
    $qrypre0 = "SELECT securityid,officeid,slevel FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$respre0 = mssql_query($qrypre0);
    $rowpre0 = mssql_fetch_array($respre0);
	
	$qry0 = "SELECT * FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0	= mssql_fetch_array($res0);
	
	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
	$res1 = mssql_query($qry1);
	
	if ($row0['finan_off']==1) {
		$qry2 = "SELECT * FROM offices WHERE finan_from='".$_SESSION['officeid']."' and active=1 and grouping!=3 ORDER BY grouping,name ASC;";
		$res2 = mssql_query($qry2);
	}
	else {
		if ($_SESSION['officeid']==89 || $rowpre0['officeid']==89) {
			$qry2 = "SELECT * FROM offices WHERE active=1 and grouping!=3 ORDER BY grouping,name ASC;";
			$res2 = mssql_query($qry2);
		}
	}
    
    $qry3 = "
        select
            L2.srcid
           ,L2.name as Sname
           ,L1.statusid
           ,L1.name as Lname
        from 
           leadstatuscodes as L1
        inner join
           leadsourcecodes as L2
        on
           L1.lsource=L2.srcid
        WHERE
           L1.active=2
           and (L2.srcid!=5 and L2.srcid!=0)
        Order By
           L2.name asc,L1.Name
    ";
    
	$res3 = mssql_query($qry3);

	echo "<div class=\"outerrnd\" style=\"width:300px\">\n";
	echo "						<table>\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\"><b>Customer Search</b></td>\n";
	echo "								<td align=\"right\">\n";

	HelpNode('CustomerSearchReport',1);

	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td colspan=\"2\" valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "         								<form method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"csearch_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"clname\">\n";
	echo "										<tr>\n";
	echo " 			                             	<td align=\"right\" valign=\"bottom\"><b>Data Field:</b></td>\n";
	echo " 			                             	<td align=\"left\" valign=\"bottom\">\n";
	echo "												<select name=\"spar\">\n";
	echo " 		                                			<option value=\"clname\">Last Name</option>\n";
	echo " 		                                			<option value=\"cfname\">First Name</option>\n";
	echo " 		                                			<option value=\"caddr1\">Cust Addr</option>\n";
	echo " 		                                			<option value=\"saddr1\">Site Addr</option>\n";
	echo " 		                                			<option value=\"chome\">Home Ph</option>\n";
	echo " 		                                			<option value=\"ccell\">Cell Ph</option>\n";
	echo " 		                                			<option value=\"cwork\">Work Ph</option>\n";
	echo " 		                                			<option value=\"cemail\">Email</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Search String:</b></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"bboxb\" name=\"sval\" id=\"nsval\" size=\"20\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo "										</tr>\n";
	
	if ($row0['finan_off']==1 || $rowpre0['officeid']==89) {
		echo "										<tr>\n";
		echo "                              	<td align=\"right\" valign=\"bottom\"><b>Office:</b></td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "										<select name=\"oid\">\n";
        
        if ($rowpre0['officeid']==89) {
            echo "                                 		<option value=\"0\">All Offices</option>\n";
            echo "                                 		<option value=\"0\">----------------------</option>\n";
        }
		
		while ($row2=mssql_fetch_array($res2)) {
            if ($_SESSION['officeid']==$row2['officeid'] && $_SESSION['officeid']!=89) {
                echo "                                 		<option value=\"".$row2['officeid']."\" selected>".$row2['name']."</option>\n";
            }
            else {
                echo "                                 		<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
            }
		}
		
		echo "												</select>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
        
        if ($rowpre0['officeid']==89) {
            echo "										<tr>\n";
            echo "                              	<td align=\"right\" valign=\"bottom\"><b>Source Code:</b></td>\n";
            echo "                              	<td align=\"left\" valign=\"bottom\">\n";
            echo "										<select name=\"psrc\">\n";
            echo "                                 		<option value=\"NA\">All</option>\n";
            
            $LScnt=1;
            $SRCid=0;
            while ($row3=mssql_fetch_array($res3)) {
                if ($SRCid!=$row3['srcid']) {
                    if ($LScnt!=1) {
                        echo "</optgroup>\n";
                    }
                    
                    echo "<optgroup label=\"".$row3['Sname']."\">\n";
                }
                
                $SRCid=$row3['srcid'];
                if (isset($_REQUEST['psrc']) && $_REQUEST['psrc']==$row3['statusid']) {
                    if ($row3['statusid']==0) {
                        echo "                                 		<option value=\"".$row3['statusid']."\" selected>bluehaven.com</option>\n";
                    }
                    else {
                        echo "                                 		<option value=\"".$row3['statusid']."\" selected>".$row3['Lname']."</option>\n";
                    }
                }
                else {
                    if ($row3['statusid']==0) {
                        echo "                                 		<option value=\"".$row3['statusid']."\">bluehaven.com</option>\n";
                    }
                    else {
                        echo "                                 		<option value=\"".$row3['statusid']."\">".$row3['Lname']."</option>\n";
                    }
                }
                
                $LScnt++;
            }
            
            echo "												</select>\n";
            echo "											</td>\n";
            echo "										</tr>\n";
        }
	}
	
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Group by:</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"group\">\n";
	echo "                                 		<option value=\"o.name\" SELECTED>Office</option>\n";
	echo "                                 		<option value=\"c.clname\">Last Name</option>\n";
	echo "                                 		<option value=\"c.added\">Date Added</option>\n";
	echo "                                    </select>\n";
	echo "                                    <select name=\"ascdesc1\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>A</option>\n";
	echo "                                 		<option value=\"DESC\">D</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Order by:</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"c.clname\" SELECTED>Last Name</option>\n";
	echo "                                 		<option value=\"c.added\">Date Added</option>\n";
	echo "                                    </select>\n";
	echo "                                    <select name=\"ascdesc2\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>A</option>\n";
	echo "                                 		<option value=\"DESC\">D</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	
	if ($_SESSION['llev'] >=5) {
		echo "										<tr>\n";
		echo "                              	<td align=\"right\" valign=\"bottom\"><b>Inactive:</b></td>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\">\n";
		echo "												<input class=\"transnb\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this Box to Include Inactive Customer Records\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
	}

	echo "										<tr>\n";
	echo "                                 <td colspan=\"2\" align=\"right\" valign=\"bottom\">\n";
	
	if ($_SESSION['llev'] >=5) {
		//echo "						<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\" onClick=\"return EmptyString('nsval');\">\n";
		echo "						<button onClick=\"return EmptyString('nsval');\">Search</button>\n";
	}

	echo "											</td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "</div>\n";
}

function csearch()
{
	unset($_SESSION['tqry']);
	$acclist=explode(",",$_SESSION['aid']);
    
    $qrypre0 = "SELECT securityid,officeid,slevel FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$respre0 = mssql_query($qrypre0);
    $rowpre0 = mssql_fetch_array($respre0);
	
	$qry0 = "SELECT * FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0	= mssql_fetch_array($res0);
	
	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
	$res1 = mssql_query($qry1);
	
	if ($row0['finan_off']==1)
	{
		$qry2 = "SELECT * FROM offices WHERE finan_from='".$_SESSION['officeid']."' and active=1 and grouping!=3 ORDER BY grouping,name ASC;";
		$res2 = mssql_query($qry2);
	}
	else
	{
		if ($_SESSION['officeid']==89 || $rowpre0['officeid']==89)
		{
			$qry2 = "SELECT * FROM offices WHERE active=1 and grouping!=3 ORDER BY grouping,name ASC;";
			$res2 = mssql_query($qry2);
		}
	}
    
    //$qry3 = "SELECT statusid,name FROM jest..leadstatuscodes WHERE active=2 and ivr=0 and statusid!=1 ORDER BY name ASC;";
    
    $qry3 = "
        select
            L2.srcid
           ,L2.name as Sname
           ,L1.statusid
           ,L1.name as Lname
        from 
           leadstatuscodes as L1
        inner join
           leadsourcecodes as L2
        on
           L1.lsource=L2.srcid
        WHERE
           L1.active=2
           and (L2.srcid!=5 and L2.srcid!=0)
        Order By
           L2.name asc,L1.Name
    ";
    
	$res3 = mssql_query($qry3);


	echo "<div class=\"outerrnd\" style=\"width:400px;\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr class=\"tblhd\">\n";
	echo "								<td align=\"left\"><b>Customer Search</b></td>\n";
	echo "								<td align=\"right\"></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td colspan=\"2\" valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "         								<form method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"csearch_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"clname\">\n";
	echo "										<tr>\n";
	echo " 			                             	<td align=\"right\" valign=\"bottom\"><b>Data Field:</b></td>\n";
	echo " 			                             	<td align=\"left\" valign=\"bottom\">\n";
	echo "												<select name=\"spar\">\n";
	echo " 		                                			<option value=\"clname\">Last Name</option>\n";
	echo " 		                                			<option value=\"cfname\">First Name</option>\n";
	echo " 		                                			<option value=\"caddr1\">Cust Addr</option>\n";
	echo " 		                                			<option value=\"saddr1\">Site Addr</option>\n";
	echo " 		                                			<option value=\"chome\">Home Ph</option>\n";
	echo " 		                                			<option value=\"ccell\">Cell Ph</option>\n";
	echo " 		                                			<option value=\"cwork\">Work Ph</option>\n";
	echo " 		                                			<option value=\"cemail\">Email</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Search String:</b></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"bboxb\" name=\"sval\" id=\"nsval\" size=\"20\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo "										</tr>\n";
	
	if ($row0['finan_off']==1 || $rowpre0['officeid']==89)
	{
		echo "										<tr>\n";
		echo "                              	<td align=\"right\" valign=\"bottom\"><b>Office:</b></td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "										<select name=\"oid\">\n";
        
        if ($rowpre0['officeid']==89)
        {
            echo "                                 		<option value=\"0\">All Offices</option>\n";
            echo "                                 		<option value=\"0\">----------------------</option>\n";
        }
		
		while ($row2=mssql_fetch_array($res2))
		{
            if ($_SESSION['officeid']==$row2['officeid'] && $_SESSION['officeid']!=89)
            {
                echo "                                 		<option value=\"".$row2['officeid']."\" selected>".$row2['name']."</option>\n";
            }
            else
            {
                echo "                                 		<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
            }
		}
		
		echo "												</select>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
        
        if ($rowpre0['officeid']==89) // Home Office == BHNM Active
        //if ($_SESSION['officeid']==89)
        {
            echo "										<tr>\n";
            echo "                              	<td align=\"right\" valign=\"bottom\"><b>Source Code:</b></td>\n";
            echo "                              	<td align=\"left\" valign=\"bottom\">\n";
            echo "										<select name=\"psrc\">\n";
            echo "                                 		<option value=\"NA\">All</option>\n";
            
            $LScnt=1;
            $SRCid=0;
            while ($row3=mssql_fetch_array($res3))
            {
                if ($SRCid!=$row3['srcid'])
                {
                    if ($LScnt!=1)
                    {
                        echo "</optgroup>\n";
                    }
                    
                    echo "<optgroup label=\"".$row3['Sname']."\">\n";
                }
                
                $SRCid=$row3['srcid'];
                if (isset($_REQUEST['psrc']) && $_REQUEST['psrc']==$row3['statusid'])
                {
                    if ($row3['statusid']==0)
                    {
                        echo "                                 		<option value=\"".$row3['statusid']."\" selected>bluehaven.com</option>\n";
                    }
                    else
                    {
                        echo "                                 		<option value=\"".$row3['statusid']."\" selected>".$row3['Lname']."</option>\n";
                    }
                }
                else
                {
                    if ($row3['statusid']==0)
                    {
                        echo "                                 		<option value=\"".$row3['statusid']."\">bluehaven.com</option>\n";
                    }
                    else
                    {
                        echo "                                 		<option value=\"".$row3['statusid']."\">".$row3['Lname']."</option>\n";
                    }
                }
                
                $LScnt++;
            }
            
            echo "												</select>\n";
            echo "											</td>\n";
            echo "										</tr>\n";
        }
	}
	
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Group by:</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"group\">\n";
	echo "                                 		<option value=\"o.name\" SELECTED>Office</option>\n";
	echo "                                 		<option value=\"c.clname\">Last Name</option>\n";
	echo "                                 		<option value=\"c.added\">Date Added</option>\n";
	echo "                                    </select>\n";
	echo "                                    <select name=\"ascdesc1\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>A</option>\n";
	echo "                                 		<option value=\"DESC\">D</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Order by:</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"c.clname\" SELECTED>Last Name</option>\n";
	echo "                                 		<option value=\"c.added\">Date Added</option>\n";
	echo "                                    </select>\n";
	echo "                                    <select name=\"ascdesc2\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>A</option>\n";
	echo "                                 		<option value=\"DESC\">D</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	
	if ($_SESSION['llev'] >=5)
	{
		echo "										<tr>\n";
		echo "                              	<td align=\"right\" valign=\"bottom\"><b>Inactive:</b></td>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\">\n";
		echo "												<input class=\"transnb\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this Box to Include Inactive Customer Records\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
	}

	echo "										<tr>\n";
	echo "                                 <td colspan=\"2\" align=\"right\" valign=\"bottom\">\n";
	
	if ($_SESSION['llev'] >=5)
	{
		//echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
		echo "						<button onClick=\"return EmptyString('nsval');\">Search</button>\n";
	}

	echo "											</td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "</div>\n";
}

function csearch_results() {
	//echo $_SESSION['tqry']."<br>";

	$officeid		=$_SESSION['officeid'];
	$securityid		=$_SESSION['securityid'];
	$acclist		=explode(",",$_SESSION['aid']);
	$brdr			=0;

	// Add current Finan User to Access List
	//$acclist[]		=$securityid;
    
    $qrypre0 = "SELECT securityid,officeid,slevel,searchlandingpage FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$respre0 = mssql_query($qrypre0);
    $rowpre0 = mssql_fetch_array($respre0);
    
	$qrypre = "SELECT officeid,name,enmas,enexp,masimport,tgp,finan_off,finan_from,otype FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre = mssql_query($qrypre);
	$rowpre = mssql_fetch_array($respre);
	
	if (empty($_REQUEST['sval']))
	{
		echo "<b><font color=\"red\">Error!</font><br><br>Search String required</b>";
		exit;
	}
    
    if ($_REQUEST['sval']=='%')
    {
        $sv='All Customers';
    }
    else
    {
        $sv=$_REQUEST['sval'];
    }
    
    if (isset($_REQUEST['psrc']) && $_REQUEST['psrc']!='NA')
    {
        $qrypre1 = "SELECT statusid,name FROM leadstatuscodes WHERE statusid='".$_REQUEST['psrc']."';";
        $respre1 = mssql_query($qrypre1);
        $rowpre1 = mssql_fetch_array($respre1);
        
        $psrc_text=$sv.' <b>AND</b> '.$rowpre1['name'];
    }
    else
    {
        $psrc_text=$sv;
    }
	
	if (isset($_REQUEST['ascdesc1']))
	{
		$dir1=$_REQUEST['ascdesc1'];
	}
	else
	{
		$dir1="ASC";
	}
	
	if (isset($_REQUEST['ascdesc2']))
	{
		$dir2=$_REQUEST['ascdesc2'];
	}
	else
	{
		$dir2="ASC";
	}

	if (isset($_REQUEST['group']))
	{
		$group=$_REQUEST['group']." ".$dir1.",";
	}
	else
	{
		$group="o.name"." ".$dir1.",";
	}
	
	if (isset($_REQUEST['order']))
	{
		$order=$_REQUEST['order']." ".$dir2.",c.cfname ASC ";
	}
	else
	{
		$order="c.clname"." ".$dir2.",c.cfname ASC ";
	}

	if (isset($_REQUEST['showdupe']) && $_REQUEST['showdupe']==1)
	{
		$showdupe=" AND c.dupe=1 ";
	}
	else
	{
		$showdupe=" AND c.dupe=0 ";
	}

	if ($rowpre['finan_off']==1)
	{
		if (isset($_REQUEST['oid']) && $_REQUEST['oid']==0)
		{
			$ooid		="o.finan_from";
		}
		else
		{
			$ooid		="c.officeid";
		}
	}
	else
	{
		if ($rowpre0['officeid']==89)
		{
			if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
			{
				$ooid		="c.officeid='".$_REQUEST['oid']."' AND ";
			}
			else
			{
				$ooid		='';
			}
		}
		else
		{
			$ooid		="c.officeid='".$_SESSION['officeid']."' AND ";
		}
	}
	
	if ($rowpre['finan_off']==1)
	{
		$qry   = "	SELECT ";
		$qry  .= "		DISTINCT(o.name), ";
		$qry  .= "		c.cid, ";
		$qry  .= "		c.officeid, ";
		$qry  .= "		c.clname, ";
		$qry  .= "		c.cfname, ";
        $qry  .= "		c.caddr1, ";
        $qry  .= "		c.saddr1, ";
		$qry  .= "		c.added, ";
		$qry  .= "		c.securityid, ";
		$qry  .= "		c.estid, ";
		$qry  .= "		(select count(estid) from est where officeid=o.officeid and cid=c.cid) as estcnt, ";
		$qry  .= "		c.jobid, ";
		$qry  .= "		c.dupe, ";
		$qry  .= "		c.njobid, ";
		$qry  .= "		c.added, ";
		$qry  .= "		c.cpname, ";
		$qry  .= "		c.fullname, ";
		$qry  .= "		c.cemail ";
		$qry  .= "	FROM ";
		$qry  .= "		offices as o ";
		$qry  .= "	INNER JOIN ";
		$qry  .= "		cinfo as c ";
		$qry  .= "	ON ";
		$qry  .= "		c.officeid=o.officeid ";
		$qry  .= "	WHERE ";
		$qry  .= "		o.finan_from='".$_SESSION['officeid']."' ";
		$qry  .= "		AND c.".$_REQUEST['spar']." LIKE '".strip_tags($_REQUEST['sval'])."%' ";
		
		if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
		{
			$qry  .= "		AND o.officeid=".$_REQUEST['oid']." ";
		}
        
        if (isset($_REQUEST['psrc']) && $_REQUEST['psrc']!='NA')
		{
			$qry  .= "		AND c.source=".$_REQUEST['psrc']." ";
		}
		
		$qry  .= "		AND c.clname LIKE '".strip_tags($_REQUEST['sval'])."%' ";
		$qry  .= "		".$showdupe." ";
		$qry  .= "	ORDER BY ";
		$qry  .= "		".$group." ";
		$qry  .= "		".$order." ;";
	}
	else
	{
		$qry	 = "SELECT c.*,(select name from offices where officeid=c.officeid) as name, ";
		$qry	.= "(select count(estid) from est where officeid=c.officeid and cid=c.cid) as estcnt ";
        $qry	.= "FROM jest..cinfo as c ";
        $qry	.= "WHERE ".$ooid." ";
		
		//if ($_SESSION['llev'] <= 5)
		//{
		//	$qry  .= " and c.sidm=".$_SESSION['securityid']." AND ";
		//}
		
        
        if (isset($_REQUEST['psrc']) && $_REQUEST['psrc']!='NA')
		{
			$qry  .= " c.source=".$_REQUEST['psrc']." AND ";
		}
        
        $qry   .= "c.".$_REQUEST['spar']." LIKE '".strip_tags($_REQUEST['sval'])."%' ".$showdupe." ORDER BY ".$order.";";
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);
	
	if ($_SESSION['securityid']==26999999999999999999999999)
	{
		echo $qry.'<br>';
	}

	if ($nrows < 1)
	{
		echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
		echo "<table align=\"center\" width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\">\n";
		echo "         <h4>Customer Search did not produce any results.</h4>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}
	else
	{
		
		/*
		echo "<table align=\"center\" width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td>\n";
		echo "         <table class=\"outer\" width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" align=\"left\">\n";
		*/
		
		echo "<script type=\"text/javascript\" src=\"js/jquery_qsearch_list.js?".time()."\"></script>\n";
		echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                     <tr>\n";
		
		if ($rowpre['officeid']==199 or ($rowpre['otype'] == 2 || $rowpre['otype'] == 3))
		{
			echo "                     		<td align=\"left\"><b>Company Search Results:</b> ";
		}
		else
		{
			echo "                     		<td align=\"left\"><b>Customer Search Results:</b> ";
		}
        
        echo $psrc_text;
        
        echo "                          </td>\n";
		echo "	                   		<td align=\"right\"><b>Record(s): <font color=\"red\">".$nrows."</font></b></td>\n";
		echo "                     </tr>\n";
		echo "                   </table>\n";
		echo "</div>\n";
		
		/*
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" align=\"left\">\n";
		*/
		
		echo "<div class=\"outerrnd\" style=\"width:950px;\">\n";
		echo "                  <table width=\"950px\">\n";
		echo "                  <tr class=\"tblhd\" >\n";
		echo "                     <td align=\"center\"><b></b></td>\n";
		
		if ($rowpre['officeid']==199 or ($rowpre['otype'] == 2 || $rowpre['otype'] == 3))
		{
			echo "                     <td align=\"left\" colspan=\"2\"><b>Company</b></td>\n";
		}
		else
		{
			echo "                     <td align=\"left\" colspan=\"2\"><b>Customer</b></td>\n";
		}
		
		echo "                     <td align=\"left\"><b>Email</b></td>\n";
		echo "                     <td align=\"left\"><b>Phone</b></td>\n";
		echo "                     <td align=\"left\"><b>Sales Rep</b></td>\n";
		echo "                     <td align=\"left\"><b>Office</b></td>\n";
		echo "                     <td align=\"center\"><b>Added</b></td>\n";
		echo "                     <td align=\"center\"><b>Inactive</b></td>\n";
		echo "			            <td align=\"center\" width=\"50px\" colspan=\"5\"><b>Lifecycle</b></td>\n";
		
		if ($rowpre['finan_off']!=1)
		{
			echo "                     <td align=\"center\"><b></b></td>\n";
		}
		else
		{
			echo "                     <td align=\"center\"><b>Finan Info</b></td>\n";
			echo "                     <td align=\"right\">&nbsp</td>\n";
		}
		
		echo "                  </tr>\n";

		$ssid	= 0;
		$xi 	= 0;
		$ls	= "<div title=\"Lead\"><b>L</b></div>";
		$es	= "";
		$cs	= "";
		$js	= "";
		$fi	= "";
		
		while($row=mssql_fetch_array($res)) {
			$xi++;
			$mstat=	0;
			
			if ($xi%2)
			{
				$tbg	= "white";
			}
			else
			{
				$tbg	= "ltgray";
			}

			$uid  =md5(session_id().time().$row['custid']).".".$_SESSION['securityid'];

			$qryA = "SELECT jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!='0';";
			$resA = mssql_query($qryA);
			$nrowA = mssql_num_rows($resA);

			$qryB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			$qryC = "SELECT fname,lname,slevel,mas_office,mas_div,masid FROM security WHERE securityid='".$row['securityid']."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);
			
			$qryD = "SELECT officeid,name,finan_off FROM offices WHERE officeid='".$row['officeid']."';";
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_array($resD);
			
            $qryE = "SELECT * FROM tfinan_detail WHERE cid='".$row['cid']."';";
            $resE = mssql_query($qryE);
            $rowE = mssql_fetch_array($resE);
            $nrowE= mssql_num_rows($resE);
            
            if ($nrowE > 0)
            {
                if ($rowE['amtfinan'] > 0 || $rowE['reasnotclosed']!=0 || $rowE['inclstatreport']!=0)
                {
                    $fi="<div title=\"Finance Record\"><b>F</b></div>";
                }
            }
			
			$secl=explode(",",$rowC['slevel']);

			if ($secl[6]==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}
			
			if ($rowpre['finan_off']!=1)
			{
				$ssid=$row['securityid'];
			}
			else
			{
				$ssid=$_SESSION['securityid'];
			}
			
			//echo $row['securityid']."<BR>";
			//print_r($acclist);
			//echo "<br>";
			
			$addr=(isset($_REQUEST['spar']) && $_REQUEST['spar']=='caddr1')?str_replace('\\','',htmlspecialchars_decode(trim($row['caddr1']))):str_replace('\\','',htmlspecialchars_decode(trim($row['saddr1'])));
			
			if (in_array($ssid,$acclist) || ($_SESSION['jlev'] >= 6 || $rowpre0['officeid'] == 89))
			{
				//if ($row['estid']!=0)
				if ($row['estcnt'] > 1)
				{
					$es="<div title=\"Multiple Estimates or Quotes\"><b>E+</b></div>";
				}
				elseif ($row['estcnt']==1)
				{
					$es="<div title=\"Estimate or Quote\"><b>E</b></div>";
				}
				else
				{
					if ($row['estid']!=0)
					{
						$es="<div title=\"Estimate or Quote\"><b>E</b></div>";
					}
				}

				if ($row['jobid']!=0)
				{
					$cs="<div title=\"Contract\"><b>C</b></div>";
				}

				if ($row['njobid']!=0)
				{
					$js="<div title=\"Job\"><b>J</b></div>";
				}

				if (isset($row['added']))
				{
					$sdate = date("m/d/y", strtotime($row['added']));
				}
				else
				{
					$sdate = '';
				}

				echo "                  <tr class=\"".$tbg."\" >\n";
				echo "                     <td align=\"right\" valign=\"top\"><b>".$xi.".</b></td>\n";
				
				if ($rowpre['officeid']==199 or ($rowpre['otype'] == 2 || $rowpre['otype'] == 3))
				{
					echo "                     <td align=\"left\" valign=\"top\">".str_replace('\\','',htmlspecialchars_decode(trim($row['cpname'])))."</td>\n";
					echo "                     <td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
				}
				else
				{
					echo "                     <td align=\"left\" valign=\"top\">".str_replace('\\','',htmlspecialchars_decode(trim($row['clname'])))."<br>".$addr."</td>\n";
					echo "                     <td align=\"left\" valign=\"top\">".$row['cfname']."</td>\n";
				}
				
                echo "                     <td align=\"left\" valign=\"top\">".trim($row['cemail'])."</td>\n";                
				
				if ($_REQUEST['spar']=='cwork')
				{
					echo "                     <td align=\"left\" valign=\"top\">".format_phonenumber($row['cwork'])." (Work)</td>\n";
				}
				elseif ($_REQUEST['spar']=='ccell')
				{
					echo "                     <td align=\"left\" valign=\"top\">".format_phonenumber($row['ccell'])." (Cell)</td>\n";
				}
				else
				{
					echo "                     <td align=\"left\" valign=\"top\">".format_phonenumber($row['chome'])." (Home)</td>\n";
				}
				
				echo "                     <td align=\"left\" valign=\"top\"><font class=\"".$fstyle."\">".$rowC['lname'].", ".$rowC['fname']."</font></td>\n";
				echo "                     <td align=\"left\" valign=\"top\">".$row['name']."</td>\n";
				echo "                     <td align=\"center\" valign=\"top\">".$sdate."</td>\n";
				echo "                     <td align=\"center\" valign=\"top\">\n";
				
				if ($row['dupe']==1)
				{
					echo "Y";
				}
				
				echo "							</td>\n";
				echo "			            <td align=\"center\" width=\"10px\">".$ls."</td>\n";
				echo "			            <td align=\"center\" width=\"10px\">".$es."</td>\n";
				echo "			            <td align=\"center\" width=\"10px\">".$cs."</td>\n";
				echo "			            <td align=\"center\" width=\"10px\">".$js."</td>\n";
				echo "			            <td align=\"center\" width=\"10px\">".$fi."</td>\n";
				echo "                      <td align=\"right\" width=\"80px\">\n";
				
				if ($rowpre['finan_off']!=1)
				{
					if (isset($rowpre0['searchlandingpage']) and $rowpre0['searchlandingpage']==0)
					{
						echo "                     		<form method=\"POST\">\n";
						echo "                     			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
						echo "                     			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
						echo "                     			<input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$row['cid']."\">\n";
						
						if ($rowpre0['officeid']==89)
						{
							echo "							<input type=\"hidden\" name=\"noffid\" value=\"".$row['officeid']."\">\n";
						}
						
						echo "                     			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
						echo "								<button class=\"btnsysmenu viewlead\" style=\"display:none;\">View Lead</button>\n";
						echo "							</form>\n";
					}
					else
					{
						echo "                        <form method=\"POST\">\n";
						echo "							<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
						echo "							<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
						echo "							<input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$row['cid']."\">\n";
						
						if ($rowpre0['officeid']==89)
						{
							echo "							<input type=\"hidden\" name=\"noffid\" value=\"".$row['officeid']."\">\n";
						}
						
						echo "							<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
						echo "							<input class=\"transnb_button\" type=\"image\" src=\"images/application_view_list.png\" title=\"Open OneSheet\">\n";
						echo "						</form>\n";
					}
				}
				else
				{
					echo "                        <form method=\"POST\">\n";
					echo "									<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
					echo "									<input type=\"hidden\" name=\"call\" value=\"view_fin_detail\">\n";
					echo "									<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
					echo "									<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
					echo "									<input type=\"hidden\" name=\"oid\" value=\"".$row['officeid']."\">\n";
					echo "									<input type=\"hidden\" name=\"foid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "									<input type=\"hidden\" name=\"csearch\" value=\"1\">\n";
					echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Finance Detail\">\n";
					echo "                        </form>\n";
				}
			
				echo "                     </td>\n";
				echo "                  </tr>\n";
				$es	= '';
				$cs	= '';
				$js	= '';
				$fi	= '';
			}
		}

		echo "                  </table>\n";
		echo "</div>\n";
		echo "<span id=\"closerEl\"></span>\n";
		
		/*
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		*/
	}
}

function distinct_matlist()
{
	$matid_ar	= array();
	$companyid	= array(
	0=>'',
	1=>70,
	2=>210,
	3=>220,
	4=>230,
	5=>250,
	6=>260,
	7=>270,
	8=>280,
	9=>290,
	10=>300,
	11=>302,
	12=>310,
	13=>320,
	14=>350,
	15=>370,
	16=>380,
	17=>400,
	18=>420,
	19=>440,
	20=>450,
	21=>460,
	22=>470,
	23=>500,
	24=>510,
	25=>520,
	26=>540
	);

	foreach ($companyid as $n1 => $v1)
	{
		$qry0	= "select distinct(matid) as matid from [".$v1."inventory] where matid!=0;";
		$res0 = mssql_query($qry0);

		//echo $qry0."<br>";

		while($row0	= mssql_fetch_array($res0))
		{
			$qrya	= "select id,vid from [material_master] where id='".$row0['matid']."';";
			$resa = mssql_query($qrya);
			$rowa	= mssql_fetch_array($resa);

			if (!in_array($row0['matid'],$matid_ar) && $rowa['vid']!=12)
			{
				$matid_ar[]=$row0['matid'];
			}
		}
	}

	//print_r($matid_ar);

	sort($matid_ar);

	//print_r($matid_ar);

	if (is_array($matid_ar) && count($matid_ar) > 0)
	{
		echo "<table>\n";
		/*
		echo "	<tr>\n";
		echo "		<td><b>ID</b></td>\n";
		echo "		<td><b>Part Number</b></td>\n";
		echo "		<td><b>Item</b></td>\n";
		echo "		<td><b>Price</b></td>\n";
		echo "		<td><b>VID</b></td>\n";
		echo "		<td><b>Vendor</b></td>\n";
		echo "	</tr>\n";
		*/

		foreach ($matid_ar as $n2 => $v2)
		{
			$qry1	= "select id,vid,vpnum,item,bp from [material_master] where id='".$v2."';";
			$res1 = mssql_query($qry1);
			$row1	= mssql_fetch_array($res1);

			$qry2	= "select vid,name from [vendors] where vid='".$row1['vid']."';";
			$res2 = mssql_query($qry2);
			$row2	= mssql_fetch_array($res2);

			echo "	<tr>\n";
			echo "		<td>".$row1['id']."</td>\n";
			echo "		<td>".$row1['vpnum']."</td>\n";
			echo "		<td>".$row1['item']."</td>\n";
			echo "		<td>".$row1['bp']."</td>\n";
			echo "		<td>".$row2['vid']."</td>\n";
			echo "		<td>".$row2['name']."</td>\n";
			echo "	</tr>\n";
		}

		echo "<table>\n";
	}
}

function listevents()
{
	if ($_SESSION['tlev'] < 9)
	{
		echo "You do not have appropriate Access Rights to view this Resource";
		exit;
	}

	if (!isset($_REQUEST['order'])||$_REQUEST['order']=="")
	{
		$order="evdate";
	}
	else
	{
		$order=$_REQUEST['order'];
	}

	if (!isset($_REQUEST['sort'])||$_REQUEST['sort']=="")
	{
		$sort="ASC";
	}
	else
	{
		$sort=$_REQUEST['sort'];
	}

	if (!isset($_REQUEST['oid'])||$_REQUEST['oid']==""||$_REQUEST['oid']==0)
	{
		$qry = "SELECT * FROM events ORDER BY ".$order." ".$sort.";";
	}
	else
	{
		$qry = "SELECT * FROM events WHERE oid='".$_REQUEST['oid']."' ORDER BY ".$order." ".$sort.";";
	}

	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($nrow > 0)
	{
		$qry1 = "SELECT * FROM offices ORDER BY name ASC;";
		$res1 = mssql_query($qry1);

		//echo $qry;
		echo "<table class=\"outer\" width=\"60%\" align=\"center\">\n";
		echo "   <tr>\n";
		echo "      <td class=\"ltgray_und\" colspan=\"6\">\n";
		echo "			<table align=\"right\">\n";
		echo "   			<tr>\n";
		echo "         	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "					<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "					<input type=\"hidden\" name=\"call\" value=\"events\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<select name=\"oid\">\n";
		echo "							<option value=\"0\">All</option>\n";

		while ($row1=mssql_fetch_array($res1))
		{
			if (isset($_REQUEST['oid']) && $_REQUEST['oid']==$row1['officeid'])
			{
				echo "							<option value=\"".$row1['officeid']."\" SELECTED>".$row1['name']."</option>\n";
			}
			else
			{
				echo "							<option value=\"".$row1['officeid']."\">".$row1['name']."</option>\n";
			}
		}

		echo "						</select>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "						<select name=\"order\">\n";
		echo "							<option value=\"evdate\" SELECTED>Date</option>\n";
		echo "							<option value=\"evdescrip\">Description</option>\n";
		echo "							<option value=\"status\">Status</option>\n";
		echo "							<option value=\"sid\">User</option>\n";
		echo "							<option value=\"oid\">Office</option>\n";
		echo "							<option value=\"ip\">IP Address</option>\n";
		echo "						</select>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "						<select name=\"sort\">\n";
		echo "							<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "							<option value=\"asc\">Ascending</option>\n";
		echo "						</select>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View\">\n";
		echo "					</td>\n";
		echo "         	</form>\n";
		echo "   			</tr>\n";
		echo "   		</table>\n";
		echo "      </td>\n";
		echo "   <tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>Date</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>Event</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>Status</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>User</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>Office</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>IP Addr</b></td>\n";
		echo "	<tr>\n";

		while ($row=mssql_fetch_array($res))
		{
			echo "	<tr>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[1]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[2]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[3]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[4]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[5]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[6]."</td>\n";
			echo "	<tr>\n";
		}

		echo "</table>\n";
	}
	else
	{
		echo "No Records.";
	}
}

function loggedusers()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	$order		=(isset($_REQUEST['order']))?$_REQUEST['order']:"acttime";
	$ascdesc	=(isset($_REQUEST['ascdesc']))?$_REQUEST['ascdesc']:"DESC";

	$orderar	=array("acttime"=>"Last Activity","logtime"=>"Login Time","rem_addr"=>"Remote Address");
	$ascdescar	=array("ASC"=>"Ascending","DESC"=>"Descending");
	$sactar		=array();
	$ssact		="";

	$qryX = "SELECT
				L.id,L.officeid,L.securityid,L.logtime,L.acttime,L.sessionid,L.rem_addr,L.queries,L.sact,L.brwsr,
				S.fname,S.lname,S.slevel,S.login,S.tester,
				(SELECT name FROM offices WHERE officeid=L.officeid) AS oname
			FROM logstate as L
			INNER JOIN security as S
			ON L.securityid=S.securityid
			ORDER BY L.".$order." ".$ascdesc.";";
	$resX = mssql_query($qryX);
	$nrowX= mssql_num_rows($resX);

	if ($nrowX > 0) {
		echo "<table class=\"outer\" width=\"950px\">\n";
		echo "<tr>\n";
		echo "   <td class=\"ltgray_und\" colspan=\"11\">\n";
		echo "		<table align=\"right\">\n";
		echo "			<tr>\n";
		echo "   			<td>\n";
		echo "					<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"loggedin\">\n";
		echo "   			<td>\n";
		echo "						<select name=\"order\">\n";

		foreach ($orderar AS $on=>$ov) {
			if ($on==$order) {
				echo "							<option value=\"".$on."\" SELECTED>".$ov."</option>\n";
			}
			else {
				echo "							<option value=\"".$on."\">".$ov."</option>\n";
			}
		}

		echo "						</select>\n";
		echo "					</td>\n";
		echo "					<td>\n";
		echo "						<select name=\"ascdesc\">\n";

		foreach ($ascdescar AS $adn=>$adv) {
			if ($adn==$ascdesc) {
				echo "							<option value=\"".$adn."\" SELECTED>".$adv."</option>\n";
			}
			else {
				echo "							<option value=\"".$adn."\">".$adv."</option>\n";
			}
		}

		echo "						</select>\n";
		echo "					</td>\n";
		echo "   				<td>\n";
		echo "   					<input class=\"transnb_button\" type=\"image\" src=\"images/arrow_refresh.png\" alt=\"Refresh\">\n";
		echo "					</td>\n";
		echo "         			</form>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td class=\"ltgray_und\" align=\"left\" colspan=\"4\"><b>Users Logged On</b></td>\n";
		echo "   <td class=\"ltgray_und\" align=\"left\" colspan=\"4\">\n";
		
		?>
        
        <script type="text/javascript">
            setLocalTime();
        </script>
        
        <?php
		
		echo "	</td>\n";
		echo "   <td class=\"ltgray_und\" align=\"right\" colspan=\"3\"><b>".$nrowX."</b> Logged On</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Name</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Security Levels</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Last Login</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Last Activity</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Remote Addr</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Office</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Area</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Agent</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Test</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"><b>Detail</b></td>\n";
		echo "   <td class=\"tblhd\" align=\"left\"></td>\n";
		echo "</tr>\n";

		$ccnt=0;
		while ($rowX = mssql_fetch_array($resX)) {

			$ccnt++;
			$trb=($ccnt%2)?'even':'odd';
			
			if ($rowX['sact']=="est") {
				$ssact="Estimates";
			}
			elseif ($rowX['sact']=="contract") {
				$ssact="Contracts";
			}
			elseif ($rowX['sact']=="job") {
				$ssact="Jobs";
			}
			elseif ($rowX['sact']=="reports") {
				$ssact="Reports";
			}
			elseif ($rowX['sact']=="leads") {
				$ssact="Leads";
			}
			elseif ($rowX['sact']=="message") {
				$ssact="Messages";
			}
			elseif ($rowX['sact']=="main") {
				$ssact="Main Menu";
			}

			$tstr=(isset($rowX['tester']) and $rowX['tester']==1)?'<img src="images/action_check.gif" title="Account is flagged for Test Functions">':'';
			echo "<tr class=\"".$trb."\">\n";
			echo "   <td align=\"left\"><b>".$rowX['lname']."</b>, ".$rowX['fname']." (".$rowX['login'].")</td>\n";
			echo "   <td>".$rowX['slevel']."</td>\n";
			echo "   <td align=\"left\">".date('m/d/y g:iA',strtotime($rowX['logtime']))."</td>\n";
			echo "   <td align=\"left\">".date('m/d/y g:iA',strtotime($rowX['acttime']))."</td>\n";
			echo "   <td align=\"left\">".$rowX['rem_addr']."</td>\n";
			echo "   <td align=\"left\">".$rowX['oname']."</td>\n";
			echo "   <td align=\"left\">".$ssact."</td>\n";
			echo "   <td align=\"left\" title=\"".$rowX['brwsr']."\">".substr($rowX['brwsr'],0,35)."</td>\n";
			echo "   <td align=\"center\">".$tstr."</td>\n";
			echo "   <td align=\"center\">\n";
			echo "   <form method=\"post\">\n";
			echo "   	<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "   	<input type=\"hidden\" name=\"call\" value=\"user_activity_detail\">\n";
			echo "   	<input type=\"hidden\" name=\"userid\" value=\"".$rowX['securityid']."\">\n";
			echo "   	<input class=\"transnb_button\" type=\"image\" src=\"images/search.gif\" alt=\"Detail\">\n";
			echo "   </form>\n";
			echo "   </td>\n";
			echo "   <td align=\"center\">\n";
			echo "   <form method=\"post\">\n";
			echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "   	<input type=\"hidden\" name=\"call\" value=\"users\">\n";
			echo "   	<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
			echo "   	<input type=\"hidden\" name=\"userid\" id=\"recid\" value=\"".$rowX['securityid']."\">\n";
			echo "   	<input type=\"hidden\" name=\"officeid\" value=\"".$rowX['officeid']."\">\n";
			echo "   	<input type=\"hidden\" name=\"noffid\" value=\"".$rowX['officeid']."\">\n";
			echo "   	<input class=\"transnb_button\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
			echo "   </form>\n";
			echo "   </td>\n";
			echo "</tr>\n";
			//$sactar[]=$rowX['sact'];
			
		}
		echo "</table>\n";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"75%\">\n";
		echo "	<tr>\n";
		echo "   	<td class=\"gray\" align=\"right\"><b>Users Logged On:</b></td>\n";
		echo "   	<td class=\"gray\" align=\"left\"><b>".$nrowX."</b> Logged On</td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
	}

}

function user_activity_detail()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	if (isset($_REQUEST['userid']) and $_REQUEST['userid']!=0)
	{
		$ssact		='';
	
		$qry0 = "SELECT securityid,officeid,fname,lname,slevel,login FROM security WHERE securityid='".$_REQUEST['userid']."'";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		
		$qryX = "SELECT top 200 E.*,(select name from jest..offices where officeid=E.oid) as oname FROM jest_stats..events AS E where E.sid=".$row0['securityid']." ORDER BY E.evdate desc;";
		$resX = mssql_query($qryX);
		$nrowX= mssql_num_rows($resX);
	
		//echo $qry;
		if ($nrowX > 0)
		{
			echo "<table class=\"outer\" width=\"950px\">\n";
			echo "<tr>\n";
			echo "   <td class=\"ltgray_und\" colspan=\"6\" align=\"right\">\n";
			echo "		<table>\n";
			echo "			<tr>\n";
			echo "   			<td>\n";
			echo "					<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"loggedin\">\n";
			//echo "						<input type=\"hidden\" name=\"userid\" value=\"".$_REQUEST['userid']."\">\n";
			echo "   					<input class=\"transnb_button\" type=\"image\" src=\"images/application_view_list.png\" title=\"Logged Users\">\n";
			echo "         			</form>\n";
			echo "				</td>\n";
			echo "   			<td>\n";
			echo "					<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"user_activity_detail\">\n";
			echo "						<input type=\"hidden\" name=\"userid\" value=\"".$_REQUEST['userid']."\">\n";
			echo "   					<input class=\"transnb_button\" type=\"image\" src=\"images/arrow_refresh.png\" title=\"Refresh\">\n";
			echo "         			</form>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "		</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "   <td class=\"ltgray_und\" align=\"left\" colspan=\"4\"><b>".$row0['fname']." ".$row0['lname']." System Activity</b></td>\n";
			echo "   <td class=\"ltgray_und\" align=\"right\" colspan=\"2\">\n";
		
			?>
			
			<script type="text/javascript">
				setLocalTime();
			</script>
			
			<?php
			
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "   <td class=\"ltgray_und\" align=\"left\"></td>\n";
			echo "   <td class=\"ltgray_und\" align=\"left\"><b>Event</b></td>\n";
			echo "   <td class=\"ltgray_und\" align=\"left\"><b>Referer</b></td>\n";
			echo "   <td class=\"ltgray_und\" align=\"left\"><b>Office</b></td>\n";
			echo "   <td class=\"ltgray_und\" align=\"center\"><b>OID</b></td>\n";
			echo "   <td class=\"ltgray_und\" align=\"center\"><b>Timestamp</b></td>\n";
			echo "</tr>\n";
	
			$ccnt=0;
			while ($rowX = mssql_fetch_array($resX))
			{
				$ccnt++;
				
				if ($ccnt%2)
				{
					$trb='white';
				}
				else
				{
					$trb='ltgray';
				}
	
				echo "<tr class=\"".$trb."\">\n";
				echo "   <td align=\"right\">".$ccnt.".</td>\n";
				echo "   <td align=\"left\">".$rowX['evdescrip']."</td>\n";
				echo "   <td align=\"left\">".$rowX['brwsr']."</td>\n";
				echo "   <td align=\"left\">".$rowX['oname']."</td>\n";
				echo "   <td align=\"center\">".$rowX['oid']."</td>\n";
				echo "   <td align=\"center\"><table width=\"130px\"><tr><td align=\"left\">".date('m/d/Y',strtotime($rowX['evdate']))."</td><td align=\"right\">".date('g:iA',strtotime($rowX['evdate']))."</td></tr></table></td>\n";
				echo "</tr>\n";
				//$sactar[]=$rowX['sact'];
			}
			echo "</table>\n";
		}
		else
		{
			echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
			echo "	<tr>\n";
			echo "   	<td class=\"gray\" align=\"right\"><b>No Detail</b></td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
		}
	}
}

function internet_total()
{
	if (!isset($_REQUEST['d1']) || !isset($_REQUEST['d2']))
	{
		echo "<table width=\"100%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"3\" align=\"right\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\" align=\"left\"><b>Total Internet Leads</b></td>\n";
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"tinternet\">\n";
		echo "      			<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";
		echo "						<input class=\"bboxl\" type=\"text\" id=\"data\" name=\"d1\" size=\"10\" maxlength=\"10\">\n";
		echo "						<input class=\"bboxl\" type=\"text\" id=\"data\" name=\"d2\" size=\"10\" maxlength=\"10\">\n";
		echo "						<input type=\"hidden\" name=\"full\" value=\"1\">\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Select\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	else
	{
		$qry = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND source='0' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		//$nrow= mssql_num_rows($res);

		//echo $qry;
		echo "<table width=\"100%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"3\" align=\"right\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\" align=\"left\"><b>Total Internet Leads from bluehaven.com: </b>&nbsp<input class=\"bboxl\" type=\"text\" name=\"inum\" size=\"10\" maxlength=\"10\" value=\"".$row['cnt']."\"></td>\n";
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"tinternet\">\n";
		echo "      			<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";
		echo "						<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['d1']."\"> to \n";
		echo "						<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['d2']."\">\n";
		echo "						<input type=\"hidden\" name=\"full\" value=\"1\">\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Select\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function salesman_general()
{
	error_reporting(0);
	$aid=explode(",",$_SESSION['aid']);

	if (isset($_REQUEST['d1']))
	{
		$d1=$_REQUEST['d1'];
	}
	else
	{
		$d1="";
	}

	if (isset($_REQUEST['d2']))
	{
		$d2=$_REQUEST['d2'];
	}
	else
	{
		$d2="";
	}

	if (isset($_REQUEST['addupd']) && $_REQUEST['addupd']=="added")
	{
		$addupd="added";
	}
	else
	{
		$addupd="updated";
	}

	//$qry = "SELECT * FROM security WHERE officeid='".$_SESSION['officeid']."' and SUBSTRING(slevel,13,13)='1' ORDER BY lname ASC";
	$qry = "SELECT * FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	echo "<script type=\"text/javascript\" src=\"js/jquery_extend.js\"></script>\n";
	echo "<table width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"right\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "         		<form name=\"tsearch\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "					 <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"salesman_gen\">\n";
	echo "					<td class=\"gray\" align=\"left\"><b>Sales Rep Report for ".$_SESSION['offname']."</b></td>\n";
	echo "      			<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";
	echo "						<input class=\"bboxc\" type=\"text\" name=\"d1\" id=\"d1\" size=\"20\" value=\"".$d1."\" title=\"Begin Date\">\n";
	echo "                     	<input class=\"bboxc\" type=\"text\" name=\"d2\" id=\"d2\" size=\"20\" value=\"".$d2."\" title=\"End Date\">\n";
	echo "					<input type=\"hidden\" name=\"addupd\" value=\"updated\">\n";
	/*if (isset($_REQUEST['todate']) && $_REQUEST['todate']==1)
	{
	$addupd="All";
	}
	else
	{
	echo "						<select name=\"addupd\">\n";

	if ($addupd=="added")
	{
	echo "							<option value=\"added\" SELECTED>Added</option>\n";
	echo "							<option value=\"updated\">Updated</option>\n";
	}
	else
	{
	echo "							<option value=\"added\">Added</option>\n";
	echo "							<option value=\"updated\" SELECTED>Updated</option>\n";
	}

	echo "						</select>\n";
	}*/

	echo "						<input type=\"hidden\" name=\"full\" value=\"1\">\n";
	echo "      			</td>\n";
	echo "					<td class=\"gray\" align=\"right\">\n";

	/*if (isset($_REQUEST['todate']) && $_REQUEST['todate']==1)
	{
	echo "To Date:";
	echo "						<input class=\"transnb\" type=\"checkbox\" name=\"todate\" value=\"1\" CHECKED>\n";
	}
	else
	{
	echo "						<input class=\"transnb\" type=\"checkbox\" name=\"todate\" value=\"1\">\n";
	}*/

	echo "						<select name=\"sid\">\n";
	echo "							<option value=\"***\" class=\"fontblack\">All Salesreps</option>\n";
	echo "							<option value=\"***\" class=\"fontblack\">----------------</option>\n";

	while ($row = mssql_fetch_array($res))
	{
		if (in_array($row['securityid'],$aid))
		{
			$secl=explode(",",$row['slevel']);

			if ($secl[6]==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}

			if (!empty($_REQUEST['sid']) && $_REQUEST['sid']==$row['securityid'])
			{
				echo "						<option value=\"".$row['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row['lname'].", ".$row['fname']." ".$dis."</option>\n";
			}
			else
			{
				echo "						<option value=\"".$row['securityid']."\" class=\"".$ostyle."\">".$row['lname'].", ".$row['fname']." ".$dis."</option>\n";
			}
		}
	}

	echo "						</select>\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Select\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "				</tr>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";

	if (!empty($_REQUEST['sid'])&&!empty($_REQUEST['d1'])&&!empty($_REQUEST['d2']) || !empty($_REQUEST['todate']))
	{
		// Leads Info
		$qry1 = "SELECT * FROM leadstatuscodes WHERE active='2' ORDER BY name ASC";
		$res1 = mssql_query($qry1);

		$qry2 = "SELECT * FROM leadstatuscodes WHERE active='1' ORDER BY name ASC";
		$res2 = mssql_query($qry2);

		$br=0;
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"35%\">\n";
		echo "			<table class=\"outer\" width=\"100%\" border=$br>\n";
		echo "				<tr>\n";
		//echo "					<td class=\"ltgray_und\" align=\"left\"><b>".ucfirst($addupd)." Leads</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Lead Activity</b></td>\n";

		/*
		if (!empty($_REQUEST['todate']) && $_REQUEST['todate']==1)
		{
		$tdate=date("m/d/Y");
		echo "					<td class=\"ltgray_und\" align=\"right\">As of ".$tdate."</td>\n";
		}
		else
		{
		echo "					<td class=\"ltgray_und\" align=\"right\">".$_REQUEST['d1']." to ".$_REQUEST['d2']."</td>\n";
		}
		*/

		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" class=\"gray\" align=\"left\" valign=\"top\">\n";
		echo "						<table width=\"100%\" border=$br>\n";
		echo "						<tr>\n";
		echo "							<td colspan=\"3\" class=\"gray\" align=\"left\"><b>Source</b></td>\n";
		echo "						</tr>\n";

		//if (!empty($_REQUEST['todate']) && $_REQUEST['todate']==1)
		//{
		//	$qry1z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_REQUEST['sid']."' AND dupe='0';";
		//}
		//else
		//{
		$qry1z  = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ";

		if ($_REQUEST['sid']!="***")
		{
			$qry1z .= "AND securityid='".$_REQUEST['sid']."' ";
		}

		$qry1z .= "AND dupe='0' AND ".$addupd." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
		//}
		$res1z = mssql_query($qry1z);
		$row1z = mssql_fetch_array($res1z);

		//echo $qry1z;

		$srccnt=0;
		$srctotal=$row1z['cnt'];
		while ($row1 = mssql_fetch_array($res1))
		{
			//if (!empty($_REQUEST['todate']) && $_REQUEST['todate']==1)
			//{
			//	$qry1a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_REQUEST['sid']."' AND dupe='0' AND source='".$row1['statusid']."';";
			//}
			//else
			//{
			$qry1a  = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ";

			if ($_REQUEST['sid']!="***")
			{
				$qry1a .= "AND securityid='".$_REQUEST['sid']."' ";
			}

			$qry1a .= "AND dupe='0' AND source='".$row1['statusid']."' AND ".$addupd." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
			//}
			$res1a = mssql_query($qry1a);
			$row1a = mssql_fetch_array($res1a);

			//echo $qry1a."<br>";

			$cnt=$row1a['cnt'];
			$srccnt=$srccnt+$cnt;

			if ($row1a['cnt']!=0)
			{
				$perc=number_format(($cnt/$srctotal)*100);
			}
			else
			{
				$perc=0;
			}

			echo "							<tr>\n";

			if ($row1['statusid'] == "0")
			{
				echo "								<td class=\"wh_und\" align=\"left\">Internet</td>\n";
			}
			elseif ($row1['statusid'] == 1)
			{
				echo "								<td class=\"wh_und\" align=\"left\">Manual (Source Unset)</td>\n";
			}
			else
			{
				echo "								<td class=\"wh_und\" align=\"left\">".$row1['name']."</td>\n";
			}

			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\">".$cnt."</td>\n";
			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\" title=\"Percentage of Total Leads\">".$perc."%</td>\n";
			echo "							</tr>\n";
		}


		if ($srccnt!=0)
		{
			$srcperc=number_format(($srccnt/$srctotal)*100);
		}
		else
		{
			$srcperc=0;
		}

		echo "							<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\">Total</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$srccnt."</td>\n";
		//echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$srcperc."%</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\"></td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" class=\"gray\" align=\"left\">\n";
		echo "						<table width=\"100%\" border=$br>\n";
		echo "						<tr>\n";
		echo "							<td colspan=\"3\" class=\"gray\" align=\"left\"><b>Result</b></td>\n";
		echo "						</tr>\n";

		//if (!empty($_REQUEST['todate']) && $_REQUEST['todate']==1)
		//{
		//	$qry2z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_REQUEST['sid']."' AND dupe='0';";
		//}
		//else
		//{
		$qry2z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ";

		if ($_REQUEST['sid']!="***")
		{
			$qry2z .= "AND securityid='".$_REQUEST['sid']."' ";
		}

		$qry2z .= "AND dupe='0' AND ".$addupd." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
		//}
		$res2z = mssql_query($qry2z);
		$row2z = mssql_fetch_array($res2z);

		//echo $qry2z;

		$stgcnt=0;
		$stgtotal=$row2z['cnt'];
		while ($row2 = mssql_fetch_array($res2))
		{
			//if (!empty($_REQUEST['todate']) && $_REQUEST['todate']==1)
			//{
			//	$qry2a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_REQUEST['sid']."' AND dupe='0' AND stage='".$row2['statusid']."';";
			//}
			//else
			//{
			$qry2a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ";

			if ($_REQUEST['sid']!="***")
			{
				$qry2a .= "AND securityid='".$_REQUEST['sid']."' ";
			}

			$qry2a .= "AND dupe='0' AND stage='".$row2['statusid']."' AND ".$addupd." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
			//}
			$res2a = mssql_query($qry2a);
			$row2a = mssql_fetch_array($res2a);

			//echo $qry2a;

			$cnt=$row2a['cnt'];
			$stgcnt=$stgcnt+$cnt;

			if ($row2a['cnt']!=0)
			{
				$perc=number_format(($cnt/$stgtotal)*100);
			}
			else
			{
				$perc=0;
			}

			echo "							<tr>\n";
			echo "								<td class=\"wh_und\" align=\"left\">".$row2['name']."</td>\n";
			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\">".$cnt."</td>\n";
			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\" title=\"Percentage of Total Leads\">".$perc."%</td>\n";
			//echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\"></td>\n";
			echo "							</tr>\n";
		}

		if ($srccnt!=0)
		{
			$stgperc=number_format(($stgcnt/$srctotal)*100);
		}
		else
		{
			$stgperc=0;
		}

		echo "							<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\">Total</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$stgcnt."</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$stgperc."%</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"65%\">\n";
		// Contracts

		$qry3z  = "select  ";
		$qry3z .= "		c.officeid as oid, ";
		$qry3z .= "		c.cid, ";
		$qry3z .= "		c.clname, ";
		$qry3z .= "		c.cfname, ";
		$qry3z .= "		c.added, ";
		$qry3z .= "		c.jobid, ";
		$qry3z .= "		j.contractdate, ";
		$qry3z .= "		j.contractamt, ";
		$qry3z .= "		DATEDIFF(day,c.added,j.contractdate) as cdays, ";
		$qry3z .= "		(SELECT digdate from jobs where officeid='".$_SESSION['officeid']."' and jobid=j.jobid) as ddate, ";
		$qry3z .= "		DATEDIFF(day,j.contractdate,(SELECT digdate from jobs where officeid='".$_SESSION['officeid']."' and jobid=j.jobid)) as ddays, ";
		$qry3z .= "		(SELECT lname from security where officeid='".$_SESSION['officeid']."' and securityid=c.securityid) as salesrep, ";
		$qry3z .= "		(SELECT comm from jobs where officeid='".$_SESSION['officeid']."' and jobid=j.jobid) as jcomm ";
		$qry3z .= "	from  ";
		$qry3z .= "		cinfo as c ";
		$qry3z .= "	inner join  ";
		$qry3z .= "		jdetail as j ";
		$qry3z .= "	on  ";
		$qry3z .= "		c.jobid=j.jobid ";
		$qry3z .= "	where  ";
		$qry3z .= "		c.officeid='".$_SESSION['officeid']."' and  ";
		$qry3z .= "		c.jobid!='0' and ";
		$qry3z .= "		j.jadd='0' and ";

		if (isset($_REQUEST['sid']) && $_REQUEST['sid']!="***")
		{
			$qry3z .= "		c.securityid='".$_REQUEST['sid']."' and ";
		}

		$qry3z .= "		j.contractdate between ";
		$qry3z .= "		'".$_REQUEST['d1']."' and ";
		$qry3z .= "		'".$_REQUEST['d2']."' ";
		$qry3z .= "	order by ";
		$qry3z .= "		j.contractdate ";
		$qry3z .= "	asc;";

		//echo $qry3z."<br>";
		$res3z = mssql_query($qry3z);
		$nrow3z = mssql_num_rows($res3z);

		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\" colspan=\"7\"><b>Contract Activity</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\" colspan=\"2\"><b>".$nrow3z."</b> Record(s)</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Customer</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Added</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Contract Date</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Lead to Contr</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Dig Date</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Contr to Dig</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Sales Rep</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Contr Amt</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Comm</b></td>\n";
		echo "				</tr>\n";

		$tjcomm	=0;
		$tavgltc	=0;
		$tavgctd	=0;
		$ltc		=0;
		$ctd		=0;
		$trprc	=0;
		$tovc	=0;
		while ($row3z = mssql_fetch_array($res3z))
		{
			$qry3z1  = "select  ";
			$qry3z1 .= "		SUM(CONVERT(money,raddnpr)) as traddnpr";
			$qry3z1 .= "	from  ";
			$qry3z1 .= "		jdetail ";
			$qry3z1 .= "	where  ";
			$qry3z1 .= "		officeid='".$_SESSION['officeid']."' and  ";
			$qry3z1 .= "		jobid='".$row3z['jobid']."' and ";
			$qry3z1 .= "		jadd!='0';";
			$res3z1  = mssql_query($qry3z1);
			$row3z1 = mssql_fetch_array($res3z1);
			
			$trprc=$trprc+$row3z1['traddnpr'];
			
			$qry3z2  = "select  ";
			$qry3z2 .= "		SUM(CONVERT(money,ovcommission)) as ovcomm";
			$qry3z2 .= "	from  ";
			$qry3z2 .= "		jobs ";
			$qry3z2 .= "	where  ";
			$qry3z2 .= "		officeid='".$_SESSION['officeid']."' and  ";
			$qry3z2 .= "		jobid='".$row3z['jobid']."';";
			$res3z2  = mssql_query($qry3z2);
			$row3z2 = mssql_fetch_array($res3z2);
			
			$tovc=$tovc+$row3z2['ovcomm'];
			//echo $qry3z1."<br>";
			//if (checkdate(date("m",strtotime($row3z['ddate'])), date("d",strtotime($row3z['ddate'])), date("Y",strtotime($row3z['ddate']))) && date("m/d/Y",strtotime($row3z['ddate']))!="12/31/1969")
			if (strlen($row3z['ddate']) > 5)
			{
				$ddays=date("m/d/Y",strtotime($row3z['ddate']));
			}
			else
			{
				$ddays="";
			}

			$tjcomm	=$tjcomm+$row3z['jcomm'];

			if ($row3z['cdays'] <= 0)
			{
				//$preddays=0;
				$preddays=$row3z['cdays']*(2*-1);
				//$preddays=$row3z['cdays']*-1;
			}
			else
			{
				$preddays=$row3z['cdays'];
			}

			$tavgltc	=$tavgltc+$preddays;

			if ($row3z['ddays'] > 0)
			{
				$tavgctd	=$tavgctd+$row3z['ddays'];
				$ctd++;
			}

			echo "				<tr>\n";
			echo "					<td class=\"wh_und\" align=\"left\" width=\"100\">&nbsp".$row3z['clname']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".date("m/d/Y",strtotime($row3z['added']))."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".date("m/d/Y",strtotime($row3z['contractdate']))."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$row3z['cdays']."</td>\n";
			//echo "					<td class=\"wh_und\" align=\"center\">".$ddays." (".$row3z['ddate'].")</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$ddays."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$row3z['ddays']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\">".$row3z['salesrep']."</td>\n";
			//echo "					<td class=\"wh_und\" align=\"right\">".number_format($row3z['contractamt'], 2, '.', '')." (".$row3z1['traddnpr'].")</td>\n";
			//echo "					<td class=\"wh_und\" align=\"right\">".number_format($row3z['jcomm']+$row3z2['ovcomm'], 2, '.', '')."(".$row3z2['ovcomm'].")</td>\n";
			echo "					<td class=\"wh_und\" align=\"right\">".number_format($row3z['contractamt']+$row3z1['traddnpr'], 2, '.', '')."</td>\n";
			echo "					<td class=\"wh_und\" align=\"right\">".number_format($row3z['jcomm']+$row3z2['ovcomm'], 2, '.', '')."</td>\n";
			echo "				</tr>\n";
		}

		$qry3a  = "select  ";
		$qry3a .= "		SUM(CONVERT(money,j.contractamt)) as sumctr, ";
		$qry3a .= "		AVG(DATEDIFF(day,c.added,j.contractdate)) as avg_lcdays";
		$qry3a .= "	from  ";
		$qry3a .= "		cinfo as c ";
		$qry3a .= "	inner join  ";
		$qry3a .= "		jdetail as j ";
		$qry3a .= "	on  ";
		$qry3a .= "		c.jobid=j.jobid ";
		$qry3a .= "	where  ";
		$qry3a .= "		c.officeid='".$_SESSION['officeid']."' and  ";
		$qry3a .= "		c.jobid!='0' and ";
		$qry3a .= "		j.jadd='0' and ";

		if (isset($_REQUEST['sid']) && $_REQUEST['sid']!="***")
		{
			$qry3a .= "		c.securityid='".$_REQUEST['sid']."' and ";
		}

		$qry3a .= "		j.contractdate between ";
		$qry3a .= "		'".$_REQUEST['d1']."' and ";
		$qry3a .= "		'".$_REQUEST['d2']."' ";
		$res3a = mssql_query($qry3a);
		$row3a = mssql_fetch_array($res3a);

		$ttavgltc=round($tavgltc/$nrow3z);
		$ttavgctd=round($tavgctd/$ctd);
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\" colspan=\"3\">&nbsp<b>Totals</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\" title=\"Total days Lead to Contr (Negative Entries are inverted and doubled)\">".$tavgltc."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\" title=\"Total days Contr to Dig (Calc only includes Contracts with a Dig ($ctd))\">".$tavgctd."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\">".number_format($row3a['sumctr']+$trprc, 2, '.', '')."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\">".number_format($tjcomm+$tovc, 2, '.', '')."</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\" colspan=\"3\">&nbsp<b>Averages</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\" title=\"Avg # days Lead to Contr (Negative Entries are inverted and doubled)\">".$ttavgltc."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\" title=\"Avg # days Contr to Dig (Calc only includes Contracts with a Dig ($ctd))\">".$ttavgctd."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\">".number_format(($row3a['sumctr']+$trprc)/$nrow3z, 2, '.', '')."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\">".number_format(($tjcomm+$tovc)/$nrow3z, 2, '.', '')."</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}

	echo "</table>\n";
}

function logins_general()
{
	if ($_SESSION['tlev'] < 9 && $_SESSION['rlev'] < 9)
	{
		echo "<font color=\"red\">Error</font> You do not have the appropriate Access Level to view this Resource.";
		exit;
	}

	if (empty($_REQUEST['conf'])||$_REQUEST['conf']!=1)
	{
		echo "<table width=\"25%\">\n";
		echo "		<td colspan=\"2\" align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">\n";
		echo "						<b>Date Range:</b>";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"logins\">\n";
		echo "						<input type=\"hidden\" name=\"conf\" value=\"1\">\n";
		echo "				<tr>\n";
		echo "      			<td bgcolor=\"#d3d3d3\" align=\"right\"><b></b></td>\n";
		echo "      			<td bgcolor=\"#d3d3d3\" align=\"right\">\n";
		echo "						<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"10\" maxlength=\"10\"> to \n";
		echo "						<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "         		</form>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	elseif ($_REQUEST['conf']==1)
	{
		$qry = "SELECT * FROM security WHERE laccess BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."' ORDER BY lname ASC";
		$res = mssql_query($qry);
		$nrow= mssql_num_rows($res);

		//echo $qry."<br>";

		echo "<table class=\"outer\" align=\"center\" width=\"80%\">\n";
		echo "<tr>\n";
		echo"   <td class=\"ltgray_und\" colspan=\"4\"><b>User Activity between ".$_REQUEST['d1']." and ".$_REQUEST['d2']."</b></td>\n";
		echo"   <td class=\"ltgray_und\" align=\"right\" colspan=\"2\"><b>".$nrow."</b> Records Found</td>\n";
		echo"</tr>\n";
		echo "<tr>\n";
		echo"   <td class=\"ltgray_und\"><b>Name</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Login ID</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Last Login</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Last Activity</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Office</b></td>\n";
		echo"   <td class=\"ltgray_und\"></td>\n";
		echo"</tr>\n";

		while ($row = mssql_fetch_array($res))
		{
			$qry1 = "SELECT name FROM offices WHERE officeid='".$row['officeid']."'";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);

			$qry2 = "SELECT MAX(evdate) as mdate FROM events WHERE sid='".$row['securityid']."'";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);

			echo "<tr>\n";
			echo "   <td class=\"wh_und\" NOWRAP><b>".$row['lname']."</b>, ".$row['fname']."</td>\n";
			echo "   <td class=\"wh_und\" NOWRAP>".$row['login']."</td>\n";
			echo "   <td class=\"wh_und\" NOWRAP>".$row['curr_login']."</td>\n";
			//echo "   <td class=\"wh_und\">".$row2['mdate']."</td>\n";
			echo "   <td class=\"wh_und\" NOWRAP>".$row['laccess']."</td>\n";
			echo "   <td class=\"wh_und\" NOWRAP><b>".$row1['name']."</b></td>\n";
			echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "   	<input type=\"hidden\" name=\"call\" value=\"users\">\n";
			echo "   	<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
			echo "   	<input type=\"hidden\" name=\"userid\" value=\"".$row['securityid']."\">\n";
			echo "   	<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
			echo "   <td class=\"wh_und\" align=\"right\" NOWRAP>\n";
			echo "   	<input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
			echo "   </td>\n";
			echo "   </form>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}

function clist_general()
{
	if (isset($_REQUEST['order']))
	{
		if (isset($_REQUEST['dir']))
		{
			$order=$_REQUEST['order'];
			$dir=$_REQUEST['dir'];
		}
		else
		{
			$order=$_REQUEST['order'];
			$dir="ASC";
		}
	}
	else
	{
		$order="clname";
		$dir="ASC";
	}

	if (isset($_REQUEST['dir']) && $_REQUEST['dir']=="ASC")
	{
		$sdir="DESC";
	}
	elseif (isset($_REQUEST['dir']) && $_REQUEST['dir']=="DESC")
	{
		$sdir="ASC";
	}
	else
	{
		$sdir="ASC";
	}

	$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND dupe!=1 ORDER BY ".$order." ".$dir.";";
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	if ($nrows < 1)
	{
		echo "<table align=\"center\" width=\"60%\">\n";
		echo "   <tr>\n";
		echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      <td class=\"gray\">\n";
		echo "         <b>Your search did not return any results.</b>\n";
		echo "      </td>\n";
		echo "   </form>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"70%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                     <tr>\n";
		//echo "                        <td align=\"left\" class=\"ltgray_und\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "                     </tr>\n";
		echo "                   </table>\n";
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\">\n";
		echo "         <table width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                  	<tr>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"clname\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Last Name\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">First Name</td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Phone</td>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"added\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Origin Date\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"custid\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Lead ID\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"estid\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Estimate ID\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"jobid\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Job ID\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "							<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">\n";
		echo "							</td>\n";
		echo "                  	</tr>\n";

		while($row=mssql_fetch_array($res))
		{
			$tbg="wh_und";

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

			$udiff_date=$ts_tdate[0]-$ts_udate;
			$odiff_date=$ts_tdate[0]-$ts_odate;

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

			echo "                  <tr>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$row['clname']."</b></td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\">".$row['cfname']."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$cphone."</b></td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\">".$odate."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['custid']."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['estid']."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['jobid']."</td>\n";
			//echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
			//echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			//echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
			//echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
			//echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Contact\">\n";
			echo "                     </td>\n";
			//echo "                        </form>\n";
			echo "                  </tr>\n";
		}
	}

	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function events_general()
{
	if ($_SESSION['rlev'] < 9 && $_SESSION['tlev'] < 9)
	{
		echo "You do not have appropriate Access to view this Resource.";
		exit;
	}

	$qry1 = "SELECT COUNT(*) FROM events;";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	$qry2 = "SELECT COUNT(*) FROM events WHERE status=1;";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	$qry3 = "SELECT COUNT(*) FROM events WHERE status=2;";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);

	$qry4 = "SELECT COUNT(*) FROM events WHERE status=3;";
	$res4 = mssql_query($qry4);
	$row4 = mssql_fetch_array($res4);

	if ($row2[0] != 0)
	{
		echo $row2[0]. " Logons!<br>";
	}

	if ($row4[0] != 0)
	{
		echo $row4[0]. " Functions!<br>";
	}

	if ($row3[0] != 0)
	{
		echo $row3[0]. " Logoffs!<br>";
	}

	if ($row1[0] != 0)
	{
		echo $row1[0]. " Total Processes!<br>";
	}
}

function leads_general()
{
	error_reporting(E_ALL);
	global $retar;
	$qryALTo = "SELECT securityid,altoffices,officeid FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resALTo = mssql_query($qryALTo);
	$rowALTo = mssql_fetch_array($resALTo);
	
	if ($_SESSION['officeid']==89 && $_SESSION['rlev'] >= 9)
	{
		$qryPRE = "
                select 
					o.officeid,o.name
                from
					offices as o
				inner join
					officegroupcodes as G
				on
					G.code=o.[grouping]
				where
					o.active=1
					and o.finan_off=0
				order by G.seqn asc,o.name asc;
				";
	}
	else
	{
		$qryPRE = "
                select 
					o.officeid,o.name
                from
					offices as o
				inner join
					officegroupcodes as G
				on
					G.code=o.[grouping]
				where
					officeid=".$_SESSION['officeid']."
				order by G.seqn asc,o.name asc;
				";
	}
	$resPRE = mssql_query($qryPRE);
	$nrowPRE= mssql_num_rows($resPRE);

	if ($rowALTo['altoffices']!=0)
	{
		$alto=explode(",",$rowALTo['altoffices']);
	}

	if ($_SESSION['rlev'] >= 5)
	{
		if (empty($_REQUEST['subq']))
		{
			//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1';";
			
			if (isset($_REQUEST['soid']) && $_REQUEST['soid']!=0)
			{
				$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid=".$_REQUEST['soid']." and dupe!='1' and source in (select statusid from leadstatuscodes where ivr=0);";
			}
			else
			{
				$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' and source in (select statusid from leadstatuscodes where ivr=0);";
			}
		}
		elseif ($_REQUEST['subq']=="drange")
		{
			if (empty($_REQUEST['d2']))
			{
				//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added='".$_REQUEST['d1']."';";
				if (isset($_REQUEST['soid']) && $_REQUEST['soid']!=0)
				{
					$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid=".$_REQUEST['soid']." and dupe!='1' AND added='".$_REQUEST['d1']."' and source in (select statusid from leadstatuscodes where ivr=0);";
				}
				else
				{
					$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND added='".$_REQUEST['d1']."' and source in (select statusid from leadstatuscodes where ivr=0);";
				}
			}
			else
			{
				//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
				if (isset($_REQUEST['soid']) && $_REQUEST['soid']!=0)
				{
					$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid=".$_REQUEST['soid']." and dupe!='1' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' and source in (select statusid from leadstatuscodes where ivr=0);";
				}
				else
				{
					$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' and source in (select statusid from leadstatuscodes where ivr=0);";
				}
			}
		}
	}
	else
	{
		echo "<b>You do not have appropriate Access to View this Resource.</b>";
		exit;
	}

	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);

	if ($_REQUEST['call']=="rleads")
	{
		$qrypre1 = "SELECT statusid,name,lsource,oid,provided FROM leadstatuscodes WHERE active='1' and statusid!='0' and ivr=0 order by lsource,name;"; // Result Code =1
		$qrypre2 = "SELECT statusid,name,lsource,oid,provided FROM leadstatuscodes WHERE active='1' and statusid!='0' and ivr=0 order by lsource,name;"; // Result Code =1
	}
	else
	{
		if ($_SESSION['officeid']==89)
		{
			$qrypre1 = "SELECT statusid,name,lsource,oid,provided FROM leadstatuscodes WHERE active='2' and statusid!='1' and statusid!='0' and ivr=0 order by lsource,name;"; // Source Code =2
			$qrypre2 = "SELECT statusid,name,lsource,oid,provided FROM leadstatuscodes WHERE active='2' and statusid!='1' and statusid!='0' and ivr=0 order by lsource,name;"; // Source Code =2
		}
		else
		{
			$qrypre1 = "SELECT statusid,name,lsource,oid,provided FROM leadstatuscodes WHERE active='2' and statusid!='1' and statusid!='0' and ivr=0 and (oid=0 or oid=".$_SESSION['officeid'].") order by lsource,name;"; // Source Code =2
			$qrypre2 = "SELECT statusid,name,lsource,oid,provided FROM leadstatuscodes WHERE active='2' and statusid!='1' and statusid!='0' and ivr=0 and (oid=0 or oid=".$_SESSION['officeid'].") order by lsource,name;"; // Source Code =2	
		}
	}
	$respre1 = mssql_query($qrypre1);
	$respre2 = mssql_query($qrypre1);

	if (isset($_REQUEST['lc']) && is_array($_REQUEST['lc']))
	{
		while ($rowpre1 = mssql_fetch_array($respre1))
		{
			if (in_array($rowpre1['statusid'],$_REQUEST['lc']))
			{
				$srccodes[]=$rowpre1['statusid'];
			}
		}
	}
	else
	{
		while ($rowpre1 = mssql_fetch_array($respre1))
		{
            if ($rowpre1['provided']==0)
			{
                $srccodes[]=$rowpre1['statusid'];
            }
		}
	}

	while ($rowpre2 = mssql_fetch_array($respre2))
	{
        if ($_SESSION['llev'] >= 9)
        {
            $allcodes[]=array($rowpre2['statusid'],$rowpre2['lsource'],$rowpre2['name']);
        }
        else
        {
            if ($rowpre2['oid']==0 || $rowpre2['oid']==$_SESSION['officeid'])
            {
                $allcodes[]=array($rowpre2['statusid'],$rowpre2['lsource'],$rowpre2['name']);
            }
        }
	}

	$rdate = date("m-d-Y", time());
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_extend.js\"></script>\n";
	
	echo "<div id=\"masterdiv\">\n";
	
	echo "         			<form name=\"tsearch1\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "<table class=\"transnb\" align=\"center\" width=\"900\">\n";
	echo "   <tr>\n";
	echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
	echo "         <table class=\"outer\" width=\"100%\">\n";
	echo "   			<tr>\n";

	if ($_REQUEST['call']=="rleads")
	{
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>Online Lead Status - Result Report</b></td>\n";
	}
	else
	{
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>Online Lead Status - Sourcing Report</b></td>\n";
	}

	echo "      			<td class=\"gray\" align=\"center\">\n";
	
	//if ($_SESSION['officeid']==89)
	//{
		if ($nrowPRE > 0)
		{
			echo "         			<select name=\"soid\">\n";
			
			if ($_SESSION['officeid']==89 && $_SESSION['rlev'] >= 9)
			{
				echo "         				<option value=\"0\">Search All Offices</option>\n";
			}
			
			while ($rowPRE= mssql_fetch_array($resPRE))
			{
				if (isset($_REQUEST['soid']) && $_REQUEST['soid']==$rowPRE['officeid'])
				{
					echo "         				<option value=\"".$rowPRE['officeid']."\" SELECTED>".$rowPRE['name']."</option>\n";
				}
				else
				{
					echo "         				<option value=\"".$rowPRE['officeid']."\">".$rowPRE['name']."</option>\n";
				}
			}
			
			echo "         			</select>\n";
		}
	//}
	
	echo "					</td>\n";
	echo "      			<td class=\"gray\" align=\"right\">\n";
	echo "         				<table width=\"100%\">\n";

	if ($_REQUEST['call']=="rleads")
	{
		echo "						<input type=\"hidden\" name=\"call\" value=\"rleads\">\n";
	}
	else
	{
		echo "						<input type=\"hidden\" name=\"call\" value=\"sleads\">\n";
	}

	echo "						<input type=\"hidden\" name=\"subq\" value=\"drange\">\n";
	echo "   						<tr>\n";
	echo "      						<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";

	if (!empty($_REQUEST['d1']))
	{
		echo "									<input class=\"bboxbc\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\" value=\"".$_REQUEST['d1']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxbbc\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
	}

	//echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";

	if (!empty($_REQUEST['d2']))
	{
		echo "									<input class=\"bboxc\" type=\"text\" name=\"d2\" id=\"d2\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST['d2']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxbc\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
	}

	//echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
	echo "      						</td>\n";
	echo "      						<td class=\"gray\" align=\"center\">\n";
	echo "									<div onclick=\"SwitchMenu('leadcodes')\"><img src=\"images/bullet_toggle_plus.png\" title=\"Click to Select Individual Source Codes\"></div>";
	echo "      						</td>\n";
	echo "      						<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"View Lead\">\n";
	echo "      						</td>\n";
	echo "   						</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "   						<tr>\n";
	echo "      						<td class=\"gray\" align=\"left\" colspan=\"4\">\n";
	echo "        							<span class=\"submenu\" id=\"leadcodes\">\n";
	echo "										<table class=\"transnb\" width=\"100%\">\n";
	
	$icnt=0;
	$wrap=0;
	foreach($allcodes as $nc => $vc)
	{
		$icnt++;
		if ($icnt==8)
		{
			echo "											</tr>\n";
			echo "											<tr>\n";
			$icnt=0;
		}
		
		if ($icnt==1 && $wrap==0)
		{
			echo "											<tr>\n";
			$wrap++;
		}
		
		echo "              								<td align=\"right\">\n";
		
		if (isset($_REQUEST['lc']) && in_array($vc[0],$_REQUEST['lc']))
		{
			echo "													<input class=\"transnb\" type=\"checkbox\" name=\"lc[]\" value=\"".$vc[0]."\" CHECKED>\n";
		}
		else
		{
			echo "													<input class=\"transnb\" type=\"checkbox\" name=\"lc[]\" value=\"".$vc[0]."\">\n";
		}
		
		echo "              								</td>\n";
		echo "              								<td align=\"left\">".$vc[2]." (".$vc[0].")</td>\n";		
	}
	
	echo "										</table>\n";
	echo "        							</span>\n";
	echo "      						</td>\n";
	echo "   						</tr>\n";
	
	echo "         				</form>\n";

	if (!empty($_REQUEST['subq']) && $_REQUEST['subq']=="drange")
	{
		if ($row1['cnt'] > 0)
		{
			echo "   			<tr>\n";
			echo "      			<td colspan=\"3\" class=\"gray\" align=\"left\" valign=\"top\">\n";
			echo "         			<table width=\"100%\">\n";

			if ($_SESSION['rlev'] >=5)
			{
                $qry4 = "
                select 
					o.officeid,o.name
                from
					offices as o
				inner join
					officegroupcodes as G
				on
					G.code=o.[grouping]
				where ";
				
				if (isset($_REQUEST['soid']) && $_REQUEST['soid']!=0)
				{
					$qry4 .= "
						o.officeid=".$_REQUEST['soid'].";
					";
				}
				else
				{
					$qry4 .= "
						o.active=1
						and o.finan_off=0
					order by G.seqn asc,o.name asc;
					";
				}
				
				$res4 = mssql_query($qry4);
				
				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Office</b></td>\n";
				
				if (!isset($_REQUEST['lc']))
				{
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">Total</td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">BHNM<br>Provided</td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">Office<br>Entered</td>\n";
				}

				foreach($srccodes as $n1 => $v1)
				{
					$qryST = "SELECT name FROM leadstatuscodes WHERE statusid='".$v1."';";
					$resST = mssql_query($qryST);
					$rowST = mssql_fetch_array($resST);

					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">".$rowST['name']."</td>\n";
				}

				echo "   			</tr>\n";

				$ocon =0;
				$oicon=0;
				$omcon=0;
				
				foreach($srccodes as $nI => $vI)
				{
					$o_ar[]=0;
				}

				while ($row4 = mssql_fetch_array($res4))
				{
					if ($_SESSION['rlev'] >=8) // Anyone with Report Level 8+
					{
						$tt="1";
						leads_gen_sub($srccodes,$row4['officeid'],$row4['name']);
						$ocon=$ocon+$retar[0];
						$oicon=$oicon+$retar[1];
						$omcon=$omcon+$retar[2];
						
						//echo $row4['name']." : ";
						//echo count($retar[3])."<br>";
						foreach($srccodes as $nX => $vX)
						{
							if (is_array($retar[3]))
							{
								$o_ar[$nX]=$o_ar[$nX]+$retar[3][$nX];
							}
							else
							{
								$o_ar[$nX]=$o_ar[$nX]+0;
							}
						}
					}
					elseif ($_SESSION['rlev'] >=7 && in_array($row4['officeid'],$alto)) // Anyone with Report Level 7+
					{
						$tt="2";
						leads_gen_sub($srccodes,$row4['officeid'],$row4['name']);
						$ocon=$ocon+$retar[0];
						$oicon=$oicon+$retar[1];
						$omcon=$omcon+$retar[2];
						
						if (is_array($retar[3]))
						{
							//echo $row4['name']." : ";
							//print_r($retar[3])."<br>";
							foreach($srccodes as $nX => $vX)
							{
								if (isset($retar[3][$nX]))
								{
									$o_ar[$nX]=$o_ar[$nX]+$retar[3][$nX];
								}
							}
						}
					}
					elseif ($_SESSION['rlev'] >=5 && $row4['officeid']==$_SESSION['officeid']) // Anyone with Report Level 5+
					{
						$tt="3";
						leads_gen_sub($srccodes,$row4['officeid'],$row4['name']);
						$ocon=$ocon+$retar[0];
						$oicon=$oicon+$retar[1];
						$omcon=$omcon+$retar[2];
						
						if (is_array($retar[3]))
						{
							//echo $row4['name']." : ";
							//print_r($retar[3])."<br>";
							foreach($srccodes as $nX => $vX)
							{
								//echo "<br>";
								//print_r($retar[3]);
								//echo "<br>";
								if (isset($retar[3][$nX]))
								{
									$o_ar[$nX]=$o_ar[$nX]+$retar[3][$nX];
								}
							}
						}
					}
				}

				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp<b>Total</b></td>\n";
				
				if (!isset($_REQUEST['lc']))
				{
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>".$ocon."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>".$oicon."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>".$omcon."</b></td>\n";
				}

				foreach($srccodes as $n1 => $v1)
				{
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>".$o_ar[$n1]."</b></td>\n";
				}

				echo "   			</tr>\n";
			}

			echo "						</table>\n";
			echo "					</td>\n";
			echo "   			</tr>\n";
		}
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function leads_gen_sub($srccodes,$officeid,$oname)
{
	global $retar;
	$s_ar=array();

	if (!isset($_REQUEST['lc']))
	{
		if (empty($_REQUEST['subq']))
		{
			//$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1';";
			$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1';";
		}
		elseif ($_REQUEST['subq']=="drange")
		{
			//$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
			$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59';";
	
		}
		$res5 = mssql_query($qry5);
		$row5 = mssql_fetch_array($res5);
	
		// Manual Src
		if (empty($_REQUEST['subq']))
		{
			//$qry5a = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND source!='0';";
			//$qry5a = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND source!='0';";
            $qry5a = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND source in (select statusid from leadstatuscodes where provided=0);";
		}
		elseif ($_REQUEST['subq']=="drange")
		{
			//$qry5a = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND source!='0' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
			//$qry5a = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND source!='0' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59';";
            $qry5a = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND source in (select statusid from leadstatuscodes where provided=0) AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59';";
		}
		$res5a = mssql_query($qry5a);
		$row5a = mssql_fetch_array($res5a);
	
		// Provided Src
		if (empty($_REQUEST['subq']))
		{
			//$qry5b = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND source='0';";
			//$qry5b = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND source='0';";
            $qry5b = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND source in (select statusid from leadstatuscodes where provided=1);";
		}
		elseif ($_REQUEST['subq']=="drange")
		{
			//$qry5b = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND source='0' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
			//$qry5b = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND source='0' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59';";
            $qry5b = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND source in (select statusid from leadstatuscodes where provided=1) AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59';";
		}
		$res5b = mssql_query($qry5b);
		$row5b = mssql_fetch_array($res5b);
	
		$tcnt=$row5['cnt'];
		$mcnt=$row5a['cnt'];
		$icnt=$row5b['cnt'];
	}
	else
	{
		$tcnt=0;
		$mcnt=0;
		$icnt=0;
	}
	

	echo "   			<tr>\n";
	echo "      			<td class=\"wh_und\" align=\"left\" valign=\"bottom\" NOWRAP><b>".$oname."</b></td>\n";
	
	if (!isset($_REQUEST['lc']))
	{
		echo "      			<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$tcnt."</b></td>\n";
		echo "      			<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$icnt."</b></td>\n";
		echo "      			<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$mcnt."</b></td>\n";
	}

	if ($_REQUEST['call']=="rleads")
	{
		$ffield="stage";
	}
	else
	{
		$ffield="source";
	}

	//print_r($srccodes);
	//echo "<br>";
	foreach($srccodes as $n0 => $v0)
	{
		$s_ar[]=0;
	}
	
	foreach($srccodes as $n1 => $v1)
	{
		//if ($n1!=0)
		//{
			// Tabulates statusids from leadstatuscodes
			if (empty($_REQUEST['subq']))
			{
				//$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND ".$ffield."='".$v1."';";
				$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND ".$ffield."='".$v1."';";
			}
			elseif ($_REQUEST['subq']=="drange")
			{
				//$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND ".$ffield."='".$v1."' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']."';";
				$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND ".$ffield."='".$v1."' AND added BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59';";
	
			}
			$res5z = mssql_query($qry5z);
			$row5z = mssql_fetch_array($res5z);
	
			if ($row5z['cnt'] < 1)
			{
				$s_cnt="";
			}
			else
			{
				if (isset($_REQUEST['percs']) && $_REQUEST['percs']==1)
				{
					$ps_cnt	=$row5z['cnt']/$row5['cnt'];
					$s_cnt	=number_format($ps_cnt, 4, '.', '');
				}
				else
				{
					$s_cnt=$row5z['cnt'];
					
					if (!is_numeric($s_cnt) || $s_cnt < 1)
					{
						$s_ar[$n1]=0;
					}
					else
					{
						$s_ar[$n1]=$s_cnt;
					}
				}
			}
	
			echo "      			<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$s_cnt."</b></td>\n";
		//}
	}

	echo "   			</tr>\n";

	$retar=array(0=>$tcnt,1=>$icnt,2=>$mcnt,$s_ar);
	//return $retar;
	//}
}

1;
?>
