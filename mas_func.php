<?php

function send_XML()
{
	
	//echo "function XML Resend XML";
	error_reporting(E_ALL);	
	$qry = "SELECT officeid,exportserver,exportlogin,exportpass,exportcatalog FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	//show_array_vars($_SESSION);
	
	$srvstat=checkserverportstatus($row['exportserver'],1433,5);
	
	if ($srvstat[2])
	{
		echo "<iframe width=\"900px\" height=\"900px\" src=\"subs/dataout.php?oid=".$_SESSION['officeid']."&njobid=".$_REQUEST['njobid']."\"></iframe>\n";
	}
	else
	{
		echo "Server Down or Not Available!<br />\n";
	}
}

function MAS_resend()
{
	$qry = "SELECT officeid,exportserver,exportlogin,exportpass,exportcatalog FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$srvstat=checkserverportstatus($row['exportserver'],1433,5);
	
	if ($srvstat[2])
	{
		//include (".\xml_func.php");
		/*
		$odbc_ser	=	$row['exportserver']; #the name of the SQL Server
		$odbc_add	=	$row['exportserver'];
		$odbc_user	=	$row['exportlogin']; #a valid username
		$odbc_pass	=	$row['exportpass']; #a password for the username
		$odbc_db	=	$row['exportcatalog']; #the name of the database
		*/
		$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
		$odbc_add	=	"192.168.100.45";
		$odbc_db	=	"ZE_Stats"; #the name of the database
		$odbc_user	=	"jestadmin"; #a valid username
		$odbc_pass	=	"into99black"; #a password for the username
		
		$odbc_conn0	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
		$odbc_qry0   = "SELECT officeid,mascode,jobid,sstatus,chksum,tdate,rdate FROM ZE_Stats.dbo.BHESTJobData_Stats WHERE officeid='".$_REQUEST['officeid']."' AND jobid='".$_REQUEST['njobid']."';";	
		$odbc_res0	=	odbc_exec($odbc_conn0, $odbc_qry0);
	
		$odbc_officeid	= odbc_result($odbc_res0, 1);
		$odbc_mascode	= odbc_result($odbc_res0, 2);
		$odbc_jobid		= odbc_result($odbc_res0, 3);
		$odbc_sstatus	= odbc_result($odbc_res0, 4);
		$odbc_chksum	= odbc_result($odbc_res0, 5);
		$odbc_tdate		= odbc_result($odbc_res0, 6);
		$odbc_rdate		= odbc_result($odbc_res0, 7);
		//odbc_free_result ($odbc_res1);
	
		if ($odbc_officeid==$_REQUEST['officeid'] && $odbc_jobid==$_REQUEST['njobid'])
		{
			odbc_free_result ($odbc_res0);
	
			$odbc_conn1	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	
			$odbc_qry1   = "exec sp_BHEST_XML_Import_Delete ";
			$odbc_qry1  .= "@officeid='".$_SESSION['officeid']."',";
			//$odbc_qry1  .= "@mascode='".$row0a['code']."',";
			$odbc_qry1  .= "@mascode='830',"; // Remove after tests
			$odbc_qry1  .= "@jobid='".$_REQUEST['njobid']."';";
	
			$odbc_res1	=	odbc_exec($odbc_conn1, $odbc_qry1);
			odbc_free_result ($odbc_res1);
	
			$qry0 = "UPDATE cinfo SET mas_prep='1' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
			$res0 = mssql_query($qry0);
			//$row0= mssql_fetch_array($res0);
	
			MAS_export();
		}
		else
		{
			echo "Resend Error Occurred (ErrorNo 2). Please report this error to IT Support.";
		}
	}
	else
	{
		echo "Resend Error Occurred (ErrorNo 1). Please report this error to IT Support.";
	}
}

function UPDATE_job_send_status($officeid,$njobid,$sstatus)
{
	$qry0 = "SELECT tattempt FROM masstatus WHERE officeid='".$officeid."' AND njobid='".$njobid."';";
	$res0 = mssql_query($qry0);
	$row0= mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		$sumat=$row0['tattempt']+1;

		if ($sumat > 3)
		{
			$sstatus=7;
		}

		$qry1 = "UPDATE masstatus SET sstatus='".$sstatus."',tattempt='".$sumat."',tdate=getdate() WHERE officeid='".$officeid."' AND njobid='".$njobid."';";
		$res1 = mssql_query($qry1);
	}
}

function UPDATE_job_recv_status()
{
	$qry0 = "SELECT * FROM masstatus WHERE officeid='".$_REQUEST['joid']."' AND njobid='".$_REQUEST['njobid']."';";
	$res0 = mssql_query($qry0);
	$row0= mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0==1)
	{
		if ($row0['sstatus'] <= 3)
		{
			$qry1 = "UPDATE masstatus SET sstatus='".$_REQUEST['scode']."',rdate=getdate() WHERE officeid='".$_REQUEST['joid']."' AND njobid='".$_REQUEST['njobid']."';";
			$res1 = mssql_query($qry1);
		}
	}
}

function MAS_detail()
{
	/*
	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"ZE_Stats"; #the name of the database
	$odbc_user	=	"jestuser"; #a valid username
	$odbc_pass	=	"bhestuser"; #a password for the username
	*/
	
	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"ZE_Stats"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username

	$odbc_conn0	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);

	$odbc_qry0   = "SELECT officeid,mascode,jobid,sstatus,chksum,tdate,rdate FROM ZE_Stats.dbo.BHESTJobData_Stats WHERE officeid='".$_REQUEST['officeid']."' AND jobid='".$_REQUEST['njobid']."';";

	$odbc_res0	=	odbc_exec($odbc_conn0, $odbc_qry0);

	$odbc_officeid	= odbc_result($odbc_res0, 1);
	$odbc_mascode	= odbc_result($odbc_res0, 2);
	$odbc_jobid		= odbc_result($odbc_res0, 3);
	$odbc_sstatus	= odbc_result($odbc_res0, 4);
	$odbc_chksum	= number_format(odbc_result($odbc_res0, 5), 0, '.', ',');

	$odbc_tdate		= odbc_result($odbc_res0, 6);
	$odbc_rdate		= odbc_result($odbc_res0, 7);

	//echo $odbc_qry0."<br>";
	//echo $obdc_nrow."=NROWS<br>";

	if (!empty($odbc_jobid))
	{
		$qry1		= "SELECT * FROM offices WHERE officeid='".$odbc_officeid."';";
		$res1		= mssql_query($qry1);
		$row1		= mssql_fetch_array($res1);

		$qry2		= "SELECT * FROM cinfo WHERE officeid='".$odbc_officeid."' AND njobid='".$odbc_jobid."';";
		$res2		= mssql_query($qry2);
		$row2		= mssql_fetch_array($res2);

		$qry3		= "SELECT * FROM jobs WHERE officeid='".$odbc_officeid."' AND njobid='".$odbc_jobid."';";
		$res3		= mssql_query($qry3);
		$row3		= mssql_fetch_array($res3);

		$qry3a	= "SELECT * FROM jdetail WHERE officeid='".$odbc_officeid."' AND njobid='".$odbc_jobid."';";
		$res3a	= mssql_query($qry3a);
		$row3a	= mssql_fetch_array($res3a);

		$qry3b	= "SELECT raddnpr_man FROM jdetail WHERE officeid='".$odbc_officeid."' AND njobid='".$odbc_jobid."';";
		$res3b	= mssql_query($qry3b);

		$tradd=0;
		while($row3b	= mssql_fetch_array($res3b))
		{
			$tradd	=$tradd+$row3b['raddnpr_man'];
		}

		$contramt	= number_format($row3a['contractamt'], 2, '.', '');
		$ftradd		= number_format($tradd, 2, '.', '');

		$tcontr		= number_format($contramt+$ftradd, 2, '.', '');

		$qry4		= "SELECT securityid,fname,lname,mas_div,rmas_div FROM security WHERE officeid='".$odbc_officeid."' AND securityid='".$row3['securityid']."';";
		$res4		= mssql_query($qry4);
		$row4		= mssql_fetch_array($res4);

		$uid		= md5(session_id().time().$row2['custid']).".".$_SESSION['securityid'];

		$brdr=1;

		//BHEST Stat
		$xtbg	= "";
		$xsta	= "";
		if ($odbc_sstatus == 9)
		{
			$xtbg	= "gray"; // Closed
			$xsta	= "Closed";
		}
		elseif ($odbc_sstatus == 8)
		{
			$xtbg	= "red"; // Reserved
			$xsta	= "Reserved";
		}
		elseif ($odbc_sstatus == 7)
		{
			$xtbg	= "red"; // Rejected (Processor Hold)
			$xsta	= "Hold";
		}
		elseif ($odbc_sstatus == 6)
		{
			$xtbg	= "lightgreen"; // Accepted (Exists)
			$xsta	= "Exists";
		}
		elseif ($odbc_sstatus == 5)
		{
			$xtbg	= "lightgreen"; // Accepted (Processed)
			$xsta	= "Processed";
		}
		elseif ($odbc_sstatus == 4)
		{
			$xtbg	= "yellow"; // Reserved
			$xsta	= "Reserved";
		}
		elseif ($odbc_sstatus == 3)
		{
			$xtbg	= "yellow"; // Error (Incomplete)
			$xsta	= "Incomplete";
		}
		elseif ($odbc_sstatus == 2)
		{
			$xtbg	= "lightblue"; // Transmit Sent
			$xsta	= "Sent";
		}
		elseif ($odbc_sstatus == 1)
		{
			$xtbg	= "lightblue"; // Transmit Flagged
			$xsta	= "Flagged";
		}
		else
		{
			$xtbg	= "white";
			$xsta	= "";
		}

		// MAS Stat
		$rtbg	= "";
		$rsta	= "";

		if ($row3['renov']==1 && $row4['rmas_div']!=0)
		{
			$dnjobid  =disp_mas_div_jobid($row4['rmas_div'],$row3['njobid']);
		}
		else
		{
			$dnjobid  =disp_mas_div_jobid($row4['mas_div'],$row3['njobid']);
		}

		//$dnjobid=disp_mas_div_jobid($row4['mas_div'],$row3['njobid']);
		echo "<table align=\"center\" width=\"50%\" border=\"0\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" align=\"center\">\n";
		echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=\"0\">\n";
		echo "   			<tr>\n";
		echo "      			<td align=\"center\" class=\"".$xtbg."\">\n";
		echo "						<table align=\"left\" width=\"100%\" border=\"0\">\n";
		echo "   						<tr>\n";
		echo "      							<form method=\"post\">\n";
		echo "                     	<td align=\"left\">\n";
		echo "         						<input type=\"hidden\" name=\"action\" value=\"mas\">\n";
		echo "         						<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
		echo "         						<input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
		echo "									<input type=\"hidden\" name=\"njobid\" value=\"".$odbc_jobid."\">\n";
		echo "									<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "									<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
		echo "									<input type=\"hidden\" name=\"cid\" value=\"".$row2['cid']."\">\n";
		echo "									<input type=\"hidden\" name=\"custid\" value=\"".$row2['custid']."\">\n";
		echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Comments\"><br>\n";
		echo "                     	</td>\n";
		echo "									</form>\n";

		echo "      							<form method=\"post\">\n";
		echo "                     	<td align=\"center\">\n";
		echo "         						<input type=\"hidden\" name=\"action\" value=\"mas\">\n";
		echo "         						<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "         						<input type=\"hidden\" name=\"incerrs\" value=\"0\">\n";
		echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Return to List\"><br>\n";
		echo "                     	</td>\n";
		echo "									</form>\n";

		echo "                        	<form method=\"POST\">\n";
		echo "                     	<td align=\"right\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"mas\">\n";
		echo "                           <input type=\"hidden\" name=\"officeid\" value=\"".$odbc_officeid."\">\n";
		echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$odbc_jobid."\">\n";
		echo "                           <input type=\"hidden\" name=\"custid\" value=\"".$row2['custid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "							 <select name=\"call\">\n";
		echo "								<option value=\"MAS_resend\" SELECTED>MAS</option>\n";
		echo "								<option value=\"send_XML\">XML</option>\n";
		echo "							 </select>\n";

		if ($odbc_sstatus >= 3)
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Resend\">\n";
		}
		else
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Resend\" DISABLED>\n";
		}

		echo "                     	</td>\n";
		echo "                        </form>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" align=\"center\">\n";
		echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
		echo "   			<tr>\n";
		echo "      			<td align=\"center\" class=\"".$xtbg."\">\n";
		echo "						<table align=\"left\" width=\"100%\" border=\"0\">\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"left\"><b>MAS Import Status:</b><td>\n";
		echo "      						<td align=\"right\">MAS:<b>".$xsta." (".$odbc_sstatus.")</b><td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "	<tr>\n";
		// LEFT Panel
		echo "		<td align=\"center\" width=\"50%\">\n";
		echo "			<table class=\"outer\" align=\"center\" width=\"100%\" height=\"200px\" border=\"".$brdr."\">\n";
		echo "   			<tr>\n";
		echo "      			<td align=\"center\" valign=\"top\" class=\"gray\">\n";
		echo "						<table align=\"left\" width=\"100%\" border=\"0\">\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>Office:</b><td>\n";
		echo "      						<td align=\"left\">".$row1['name']."</b><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>Job ID:</b><td>\n";
		echo "      						<td align=\"left\">".$dnjobid[0]."</b><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>Contract Date:</b><td>\n";
		echo "      						<td align=\"left\">".$row3a['contractdate']."</b><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>System Insert Date:</b><td>\n";
		echo "      						<td align=\"left\">".$row3['added']."</b><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>Original Contract Amount:</b><td>\n";
		echo "      						<td align=\"right\">".$contramt."</b><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>Addendum Amount:</b><td>\n";
		echo "      						<td align=\"right\">".$ftradd."</b><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>Total Contract Amount:</b><td>\n";
		echo "      						<td align=\"right\">".$tcontr."</b><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>Salesperson:</b><td>\n";
		echo "      						<td align=\"left\">".$row4['fname']." ".$row4['lname']."</b><td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "      			</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "      </td>\n";
		// RIGHT Panel
		echo "		<td align=\"center\" width=\"50%\">\n";
		echo "			<table class=\"outer\" align=\"center\" width=\"100%\" height=\"200px\" border=\"".$brdr."\">\n";
		echo "   			<tr>\n";
		echo "      			<td align=\"center\" valign=\"top\" class=\"gray\">\n";
		echo "						<table align=\"left\" width=\"100%\" border=\"0\">\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>CheckSum:</b><td>\n";
		echo "      						<td align=\"left\">".$odbc_chksum."</b><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>Date Sent:</b><td>\n";
		echo "      						<td align=\"left\">".$odbc_tdate."</b><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"right\"><b>Date Processed:</b><td>\n";
		echo "      						<td align=\"left\">".$odbc_rdate."</b><td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "      			</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" align=\"center\">\n";

		//chistory_list($row2['custid'],$_REQUEST['action']);

		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";

		//echo "<pre>";
		//print_r($row0);
		//echo "</pre>";
	}
	else
	{
		echo "<table align=\"center\" width=\"50%\" border=\"0\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" align=\"center\">\n";
		echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=\"0\">\n";
		echo "   			<tr>\n";
		echo "      			<td align=\"center\" class=\"gray\">\n";
		echo "						<table align=\"left\" width=\"100%\" border=\"0\">\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"left\"><b>MAS Import Status:</b><td>\n";
		echo "      						<td align=\"left\"><td>\n";
		echo "   						</tr>\n";
		echo "   						<tr>\n";
		echo "      						<td align=\"center\" valign=\"top\"><font color=\"red\"><b>ERROR</b></font><b>:<td>\n";
		echo "      						<td align=\"left\">Job ID: <b>".$_REQUEST['njobid']."</b><br> Record Not Found!<br>Has it been sent to MAS yet?<br>Try clicking \"<b>Send</b>\" from the MAS Listing.<td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function create_tcode()
{
	$recd=md5(randNum(1,32000).$_SESSION['officeid'].$_SESSION['securityid']);
	return $recd;
}

function MAS_exportTEST()
{
	error_reporting(E_ALL);
	$qry = "SELECT officeid,exportserver,exportlogin,exportpass,exportcatalog FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$srvstat=checkserverportstatus($row['exportserver'],1433,5);
	
	if ($srvstat[2])
	{
		$_SESSION['xml_njob']=$_REQUEST['njobid'];
		
		echo "<iframe src=\"subs/xml_out.php?officeid=".$_SESSION['officeid']."&njobid=".$_REQUEST['njobid']."\" width=\"800px\" height=\"700px\"></iframe>\n";
	}
}

function MAS_export()
{
	//echo "EXPORT";
	error_reporting(E_ALL);
	$qry = "SELECT officeid,exportserver,exportlogin,exportpass,exportcatalog FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$srvstat=checkserverportstatus($row['exportserver'],1433,5);
	
	//print_r($srvstat);
	
	if ($srvstat[2])
	{
		//echo "INS1";
		include (".\xml_func.php");
		/*
		$odbc_ser	=	$row['exportserver']; #the name of the SQL Server
		$odbc_add	=	$row['exportserver'];
		$odbc_user	=	$row['exportlogin']; #a valid username
		$odbc_pass	=	$row['exportpass']; #a password for the username
		$odbc_db	=	$row['exportcatalog']; #the name of the database
		*/
		$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
		$odbc_add	=	"192.168.100.45";
		$odbc_db	=	"ZE_Stats"; #the name of the database
		$odbc_user	=	"jestadmin"; #a valid username
		$odbc_pass	=	"into99black"; #a password for the username	
		
		$odbcnrowX=0;
	
		$odbc_connX	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	
		$odbc_qryX   = "SELECT * FROM BHESTJobData_Stats WHERE ";
		$odbc_qryX  .= "officeid='".$_SESSION['officeid']."' AND ";
		$odbc_qryX  .= "jobid='".$_REQUEST['njobid']."';";
	
		$odbc_resX	=	odbc_exec($odbc_connX, $odbc_qryX);
		while (odbc_fetch_row($odbc_resX))
		{
			$odbcnrowX++;
		}
	
		$qry0	= "SELECT * FROM masstatus WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
		$res0	= mssql_query($qry0);
		$nrows0	= mssql_num_rows($res0);
	
		$qry01	= "SELECT securityid FROM jobs WHERE njobid='".$_REQUEST['njobid']."';";
		$res01	= mssql_query($qry01);
		$row01	= mssql_fetch_array($res01);
	
		$qry0a	= "SELECT mas_office as code FROM security WHERE securityid='".$row01['securityid']."';";
		$res0a	= mssql_query($qry0a);
		$row0a	= mssql_fetch_array($res0a);
	
		//print_r($row01);
		//echo "<br>";
		//print_r($row0a);
	
		if ($odbcnrowX > 0)
		{
			$odbcnrow=0;
	
			$odbc_conn0	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	
			$odbc_qry0   = "SELECT * FROM BHESTJobData_Stats WHERE ";
			$odbc_qry0  .= "officeid='".$_SESSION['officeid']."' AND ";
			$odbc_qry0  .= "jobid='".$_REQUEST['njobid']."';";
	
			$odbc_res0	=	odbc_exec($odbc_conn0, $odbc_qry0);
			while (odbc_fetch_row($odbc_res0))
			{
				$odbcnrow++;
			}
			//$odbc_nrow0	=	odbc_num_rows($odbc_res0);
	
			echo "<table align=\"center\" width=\"50%\" border=\"0\">\n";
			echo "	<tr>\n";
			echo "		<td colspan=\"2\" align=\"center\">\n";
			echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=\"0\">\n";
			echo "   			<tr>\n";
			echo "      			<td align=\"center\" class=\"gray\">\n";
			echo "						<table align=\"left\" width=\"100%\" border=\"0\">\n";
			echo "   						<tr>\n";
			echo "      						<td align=\"left\"><b>MAS Import Status:</b><td>\n";
			echo "      						<td align=\"left\"><td>\n";
			echo "   						</tr>\n";
			echo "   						<tr>\n";
			echo "      						<td align=\"center\" valign=\"top\"><font color=\"red\"><b>ERROR</b></font> ($odbcnrow)<b>:<td>\n";
			echo "      						<td align=\"left\">Duplicate Job Found.<br>Try refreshing the Job Listing to get the latest Job Status.<br> If this is erroneous and persists Contact a System Administrator.<b><td>\n";
			echo "   						</tr>\n";
			echo "						</table>\n";
			echo "					</td>\n";
			echo "   			</tr>\n";
			echo "			</table>\n";
			echo "      </td>\n";
			echo "   </tr>\n";
			echo "</table>\n";
		}
		else
		{
			$sstatus=2;
	
			$xcont=removequote(trim(XML_content($_SESSION['officeid'],$_REQUEST['njobid'])));
			
			//echo $xcont;
			
			//exit;
			
			$sx		=strlen($xcont);
			$sdiv	=ceil($sx/4);
			
			if ($sx > 31000)
			{
				echo "<font color=\"red\"><b>ERROR</b></font><b>: Job DataSet too large, Contact System Administrator";
			}
			else
			{
				$xslot1	=substr($xcont,0,$sdiv);
				$xslot2	=substr($xcont,$sdiv,$sdiv);
				$xslot3	=substr($xcont,$sdiv+$sdiv,$sdiv);
				$xslot4	=substr($xcont,$sdiv+$sdiv+$sdiv);
	
				$sx1		=strlen($xslot1);
				$sx2		=strlen($xslot2);
				$sx3		=strlen($xslot3);
				$sx4		=strlen($xslot4);
	
				$nsx		=$sx1+$sx2+$sx3+$sx4;
	
				if ($nsx!=$sx)
				{
					echo "<font color=\"red\"><b>ERROR</b></font><b>: Job DataSet Mismatch, Contact System Administrator";
					exit;
				}
				else
				{
					//echo $xcont."<br>";
	
					$odbc_conn1	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	
					$odbc_qry1   = "exec sp_BHEST_XML_Import ";
					$odbc_qry1  .= "@officeid='".$_SESSION['officeid']."',";
					//$odbc_qry1  .= "@mascode='".$row0a['code']."',";
					$odbc_qry1  .= "@mascode='830',"; // Remove after tests
					$odbc_qry1  .= "@jobid='".$_REQUEST['njobid']."',";
					$odbc_qry1  .= "@jtext1='".$xslot1."',";
					$odbc_qry1  .= "@jtext2='".$xslot2."',";
					$odbc_qry1  .= "@jtext3='".$xslot3."',";
					$odbc_qry1  .= "@jtext4='".$xslot4."',";
					$odbc_qry1  .= "@chksum='".$nsx."',";
					$odbc_qry1  .= "@sstatus='".$sstatus."';";
	
					//echo $odbc_qry1."<br>";
					
					$odbc_res1	=	odbc_exec($odbc_conn1, $odbc_qry1);
					$odbc_ret1	=	odbc_result($odbc_res1, 1);
					odbc_free_result ($odbc_res1);
	
					//echo "RET1: ".$odbc_ret1."<br>";
					
					$odbc_conn2	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	
					$odbc_qry2   = "SELECT id,mascode,jobid,sstatus FROM BHESTJobData_Stats WHERE officeid='".$_SESSION['officeid']."' AND ";
					$odbc_qry2  .= "jobid='".$_REQUEST['njobid']."';"; // Remove after tests
	
					//echo $odbc_qry2."<br>";
	
					$odbc_res2	=	odbc_exec($odbc_conn2, $odbc_qry2);
					$odbc_ret21	=	odbc_result($odbc_res2, 1);
					$odbc_ret22	= 	odbc_result($odbc_res2, 2);
					$odbc_ret23	= 	odbc_result($odbc_res2, 3);
					$odbc_ret24	= 	odbc_result($odbc_res2, 4);
	
					if ($_REQUEST['njobid']==$odbc_ret23)
					{
						$qrypost0 = "UPDATE cinfo SET mas_prep='2' WHERE officeid='".$_SESSION['officeid']."' and njobid='".$odbc_ret23."';";
						$respost0 = mssql_query($qrypost0);
						$rowpost0 = mssql_fetch_array($respost0);
	
						MAS_detail();
					}
					else
					{
						echo "EJOB: ".$_REQUEST['njobid']."<br>";
						echo "MJOB: ".$odbc_ret23."<br>";
						echo "Error on Send!";
					}
				}
			}
		}
	}
	else
	{
		
	}
}

function MAS_list()
{
	//echo $_SESSION['tqry']."<br>";
	//show_post_vars();
	$officeid		=$_SESSION['officeid'];
	$securityid		=$_SESSION['securityid'];
	$acclist		=explode(",",$_SESSION['aid']);
	
	$qry = "SELECT officeid,exportserver,exportlogin,exportpass,exportcatalog FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	//$srvstat=checkserverportstatus($row['exportserver'],1433,5);
	
	$brdr		=0;
	$mjar		=array();
	$mjar1		=array();
	$mjar2		=array();

	/*
	$odbc_ser	=	$row['exportserver']; #the name of the SQL Server
	$odbc_add	=	$row['exportserver'];
	$odbc_user	=	$row['exportlogin']; #a valid username
	$odbc_pass	=	$row['exportpass']; #a password for the username
	$odbc_db	=	$row['exportcatalog']; #the name of the database
	*/
	
	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"ZE_Stats"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username

	$odbc_conn0	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);

	$odbc_qry0   = "SELECT id,jobid,sstatus,tdate,rdate FROM BHESTJobData_Stats WHERE ";
	$odbc_qry0  .= "officeid='".$_SESSION['officeid']."' AND sstatus <= 9;"; // Remove after tests

	$odbc_res0	=	odbc_exec($odbc_conn0, $odbc_qry0);

	//echo $odbc_qry0."<br>";

	while (odbc_fetch_row($odbc_res0))
	{
		$odbc_ret1 	= odbc_result($odbc_res0, 1);
		$odbc_ret2 	= odbc_result($odbc_res0, 2);
		$odbc_ret3 	= odbc_result($odbc_res0, 3);
		$odbc_ret4 	= odbc_result($odbc_res0, 4);
		$odbc_ret5 	= odbc_result($odbc_res0, 5);

		$mjar1[]		=$odbc_ret2;
		$mjar2[]		=$odbc_ret3;
		$mjar3[]		=$odbc_ret4;
		$mjar4[]		=$odbc_ret5;
	}

	if(count($mjar1) == count($mjar2))
	{
		for($x=0; $x<count($mjar1); $x++)
		{
			$mjar[$mjar1[$x]] = array($mjar2[$x],$mjar3[$x],$mjar4[$x]);
		}
	}

	//print_r($mjar1);
	//echo "<p>";
	//print_r($mjar2);

	$qrypre = "SELECT enmas,enexp,masimport,tgp FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre = mssql_query($qrypre);
	$rowpre = mssql_fetch_array($respre);
	
	$qrypreA = "SELECT * FROM bonus_schedule_config WHERE brept_yr='".$_REQUEST['cyear']."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);
	$nrowpreA= mssql_num_rows($respreA);
	
	$sdate=$rowpreA['smo'].'/1/'.$rowpreA['syr'];
	$edate=$rowpreA['emo'].'/30/'.$rowpreA['eyr'];

	if (isset($_REQUEST['masstatus']))
	{
		$masstatus=$_REQUEST['masstatus'];
	}
	else
	{
		$masstatus=1;
	}

	if (isset($_REQUEST['masstatus']))
	{
		//$masstatus=$_REQUEST['masstatus'];
		if ($_REQUEST['masstatus']==0)
		{
			$mstatqual=">=";
			$masstatus=1;
		}
		elseif ($_REQUEST['masstatus']==1)
		{
			$mstatqual="=";
			$masstatus=1;
		}
		elseif ($_REQUEST['masstatus']==2)
		{
			$mstatqual=">=";
			$masstatus=2;
		}
		else
		{
			$mstatqual="=";
			$masstatus=$_REQUEST['masstatus'];
		}
	}
	else
	{
		$mstatqual="=";
		$masstatus=1;
	}

	//echo "PRE<br>";
	if (isset($_SESSION['tqry']))
	{
		//echo "ZERO<br>";
		$qry=$_SESSION['tqry'];
	}
	else
	{
		if ($_REQUEST['call']=="search_results")
		{
			if ($_REQUEST['subq']=="C.clname" || $_REQUEST['subq']=="J1.njobid")
			{
				if (empty($_REQUEST['d1']) && isset($_REQUEST['d2']) )
				{
					if (empty($_REQUEST['sval']))
					{
						echo "<b><font color=\"red\">Error!</font> Search String required.</b>";
						exit;
					}
				}
				//ONE
				$qry    = "SELECT ";
				$qry   .= "J1.*, ";
				$qry   .= "J2.*, ";
				$qry   .= "C.* ";
				$qry   .= "FROM [jobs] AS J1 ";
				$qry   .= "INNER JOIN [jdetail] AS J2 ";
				$qry   .= "ON J1.njobid=J2.njobid ";
				$qry   .= "INNER JOIN [cinfo] AS C ";
				$qry   .= "ON J1.njobid=C.njobid ";
				$qry   .= "WHERE J1.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND J2.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND C.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND J2.jadd='0' ";
				$qry   .= "AND J1.njobid!='0' ";			
				$qry   .= "AND C.clname LIKE '".$_REQUEST['sval']."%'  ";
				$qry   .= "AND C.mas_prep ".$mstatqual." '".$masstatus."' ";
	
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
				//echo "TWO<br>";
				$qry    = "SELECT ";
				$qry   .= "J1.*, ";
				$qry   .= "J2.*, ";
				$qry   .= "C.* ";
				$qry   .= "FROM [jobs] AS J1 ";
				$qry   .= "INNER JOIN [jdetail] AS J2 ";
				$qry   .= "ON J1.njobid=J2.njobid ";
				$qry   .= "INNER JOIN [cinfo] AS C ";
				$qry   .= "ON J1.njobid=C.njobid ";
				$qry   .= "WHERE J1.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND J2.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND C.officeid='".$_SESSION['officeid']."' ";
				$qry   .= "AND J2.jadd='0' ";
				$qry   .= "AND J1.njobid!='0' ";
				$qry   .= "AND C.mas_prep ".$mstatqual." '".$masstatus."' ";
				$qry   .= "AND J1.securityid='".$_REQUEST['assigned']."' ";
				
				if (isset($_REQUEST['cyear']) && $nrowpreA > 0)
				{
					$qry   .= " AND J2.contractdate BETWEEN '".$sdate."' AND '".$edate." 23:59:59'  ";
				}
				
				if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
				{
					$qry   .="AND J1.renov = '1'  ";
				}
				
				$qry   .= "ORDER BY J1.renov,".$_REQUEST['order']." ".$_REQUEST['ascdesc'].";";
			}
		}
		else
		{
			//echo "THREE<br>";
			$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND mas_prep ".$mstatqual." '".$masstatus."' AND njobid!='0' ORDER BY ".$order." ".$dir.";";
		}
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	$_SESSION['tqry']=$qry;
	
	if ($_SESSION['securityid']==2699999999999999999999)
	{
		echo $qry."<br>";
	}

	if ($nrows < 1)
	{
		echo "<table class=\"outer\" width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\" class=\"gray\">\n";
		echo "         <h4>MAS Job Search did not produce any results.</h4>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		//print_r($mjar)."<br>";
		echo "<table width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td>\n";
		echo "         <table width=\"100%\" border=\"".$brdr."\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
		//echo "                     <tr>\n";
		//echo "                        <td align=\"left\" class=\"gray\" colspan=\"5\"><b>MAS Status</b></td>\n";
		//echo "                        <td align=\"right\" class=\"gray\" colspan=\"3\">Job Import: ".$srvstat[0]." ".$srvstat[1]."</td>\n";
		//echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>";

		if (isset($_REQUEST['masstatus']))
		{
			if ($_REQUEST['masstatus']==0)
			{
				echo "All Jobs";
			}
			elseif ($_REQUEST['masstatus']==1)
			{
				echo "All Unreleased";
			}
			elseif ($_REQUEST['masstatus']==2)
			{
				echo "All Released";
			}
			else
			{
				echo "";
			}
		}

		echo "									</b></td>\n";
		echo "                        <td align=\"right\" class=\"ltgray_und\">Status Codes:</td>\n";
		echo "                        <td align=\"center\" class=\"magenta_und\" width=\"100\"><b>Review</b></td>\n";
		echo "                        <td align=\"center\" class=\"blu_und\" width=\"100\"><b>In Transit</b></td>\n";
		echo "                        <td align=\"center\" class=\"grn_und\" width=\"100\"><b>Accepted</b></td>\n";
		echo "                        <td align=\"center\" class=\"yel_und\" width=\"100\"><b>New Activity</b></td>\n";
		echo "                        <td align=\"center\" class=\"red_und\" width=\"100\"><b>Error</b></td>\n";
		echo "                     </tr>\n";
		echo "                   </table>\n";
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table class=\"outer\" width=\"100%\" bgcolor=\"white\" border=\"".$brdr."\">\n";
		echo "                  <tr>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b></b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Job ID</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Addn</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Reno</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>Customer</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\"><b>Total</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>TGP</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>SalesRep</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\" title=\"Date of Contract\"><b>Contract Date</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\" title=\"Date Job added to JMS\"><b>System Date</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\" title=\"Date/Time Transmitted\"><b>TX</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\" title=\"Date/Time Received\"><b>RX</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Status</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b></b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b></b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\"><b>Total Job(s) Found:</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\"><b><font color=\"red\">".$nrows."</font></b></td>\n";
		echo "                  </tr>\n";

		$tcon=0;
		$xi = 0;
		$xj = 0;
		while($row=mssql_fetch_array($res))
		{
			$xi++;
			$jerr=0;
			$add_type=0;
			$mtx ='';
			$mrx ='';
			$qryA = "SELECT jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!='0';";
			$resA = mssql_query($qryA);
			$nrowA = mssql_num_rows($resA);

			$qryB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			$tbg	= "wh_und";
			$xtbg	= "magenta_und"; // Review Flagged
			$xsta	= "Review";
			$mstat=	0;

			if (array_key_exists($row['njobid'],$mjar))
			{
				$mstat	=$mjar[$row['njobid']][0];
				
				if (isset($mjar[$row['njobid']][1]))
				{
					$mtx	=date('m/d/y H:i',strtotime($mjar[$row['njobid']][1]));
				}
				else
				{
					$mtx	='';	
				}
				
				if (isset($mjar[$row['njobid']][2]))
				{
					$mrx	=date('m/d/y H:i',strtotime($mjar[$row['njobid']][2]));
				}
				else
				{
					$mrx	='';
				}
				
				if ($mstat == 9)
				{
					$xtbg	= "ltgray_und"; // Closed
					$xsta	= "Closed";
				}
				elseif ($mstat == 8)
				{
					$xtbg	= "red_und"; // Reserved
					$xsta	= "Reserved";
				}
				elseif ($mstat == 7)
				{
					$xtbg	= "red_und"; // Rejected (Processor Hold)
					$xsta	= "Hold";
				}
				elseif ($mstat == 6)
				{
					$xtbg	= "grn_und"; // Accepted (Exists)
					$xsta	= "Exists";
				}
				elseif ($mstat == 5)
				{
					$xtbg	= "grn_und"; // Accepted (Processed)
					$xsta	= "Processed";
				}
				elseif ($mstat == 4)
				{
					$xtbg	= "yel_und"; // Reserved
					$xsta	= "Reserved";
				}
				elseif ($mstat == 3)
				{
					$xtbg	= "yel_und"; // Error (Incomplete)
					$xsta	= "Incomplete";
				}
				elseif ($mstat == 2)
				{
					$xtbg	= "blu_und"; // Transmit Sent
					$xsta	= "Sent";
				}
				elseif ($mstat == 1)
				{
					$xtbg	= "blu_und"; // Transmit Flagged
					$xsta	= "Flagged";
				}
				else
				{
					//$xtbg	= "wh_und";
					//$xsta	= "";
					$xtbg	= "magenta_und"; // Review Flagged
					$xsta	= "Review";
				}
			}

			$qryC = "SELECT fname,lname,slevel,mas_office,mas_div,masid,rmas_div FROM security WHERE securityid=".$row['securityid'].";";
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

			$qryD = "SELECT contractamt,contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd='0';";
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_array($resD);

			$ctramt=$rowD['contractamt'];

			if ($nrowA >= 1)
			{
				$qryDa = "SELECT raddnpr_man,post_add,pmasreq FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!='0';";
				$resDa = mssql_query($qryDa);

				//echo $qryDa."<br>";

				$jaddamt=0;
				while ($rowDa = mssql_fetch_array($resDa))
				{
					$jaddamt=$jaddamt+$rowDa['raddnpr_man'];
					
					if ($rowDa['pmasreq']==1)
					{
						$add_type++;
						$xtbg	= "grn_und";
						$xsta	= "Post MAS P";
						//echo $xtbg."<br>";
					}
					elseif ($rowDa['post_add']==1)
					{
						$add_type++;

						$xtbg	= "yel_und"; // Reserved
						$xsta	= "Post MAS";
					}
				}
			}
			else
			{
				$jaddamt=0;
			}

			$qryF = "SELECT MAX(jadd) as mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."';";
			$resF = mssql_query($qryF);
			$rowF = mssql_fetch_array($resF);

			$uid  =md5(session_id().time().$row['custid']).".".$_SESSION['securityid'];

			if ($rowpre['enexp'] == 0)
			{
				$jerr++;
			}

			if (strtotime($rowpre['masimport']) > strtotime($rowD['added']))
			{
				//echo "MAS: ".strtotime($rowpre['masimport'])."<br>";
				//echo "ADD: ".strtotime($rowD['added'])."<br>";

				$jerr++;
			}

			if ($rowC['mas_office']==0 || $rowC['mas_div']==0 || $rowC['masid']==0)
			{
				$jerr++;
			}

			if (!is_numeric($row['njobid']))
			{
				$jerr++;
			}

			if (in_array($row['securityid'],$acclist)||$_SESSION['jlev'] >= 6)
			{
				//if ($jerr==0||$_REQUEST['incerrs']==1)
				//{
					$xj++;
					$tctramt=$ctramt+$jaddamt;
					$ftctramt=number_format($tctramt, 2, '.', ',');
					$tcon=$tcon+$tctramt;

					//if (isset($row['added']))
					if (isset($rowD['contractdate']))
					{
						$odate = date("m/d/y", strtotime($rowD['contractdate']));
					}
					else
					{
						$odate = "";
					}

					if (isset($row['updated']))
					{
						$udate = date("m/d/y", strtotime($row['updated']));
					}
					else
					{
						$udate = "";
					}

					if (isset($rowD['added']))
					{
						$sdate = date("m/d/y", strtotime($rowD['added']));
					}
					else
					{
						$sdate = "";
					}


					$tgp		=round($rowB['tgp'], 2)*100;
					
					if ($rowB['renov']==1 && $rowC['rmas_div']!=0 && strtotime($rowB['added']) >= strtotime('9/28/07'))
					{
						$dnjobid	=disp_mas_div_jobid($rowC['rmas_div'],$row['njobid']);
					}
					else
					{
						$dnjobid	=disp_mas_div_jobid($rowC['mas_div'],$row['njobid']);
					}

					if ($rowpre['tgp']!="0")
					{
						$vagp=2;
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

					echo "                  <tr>\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi."</td>\n";
					//echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['njobid']."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">".$dnjobid[0]."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"center\">\n";

					if ($nrowA >= 1)
					{
						echo "<b>".$nrowA."</b>";
					}

					echo "							</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"center\">\n";

					if ($rowB['renov'] == 1)
					{
						echo "<b>R</b>";
					}

					echo "							</td>\n";
					//echo "                     <td class=\"".$tbg."\" align=\"left\" NOWRAP><b>".stripslashes($row['clname'])."</b>, ".$row['cfname']."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"left\" NOWRAP><b>".str_replace('\\','',$row['clname'])."</b>, ".$row['cfname']."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">".$ftctramt."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">".$ftgp."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"left\" NOWRAP><font class=\"".$fstyle."\">".$rowC['lname'].", ".$rowC['fname']."</font></td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"center\">".$odate."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"center\">".$sdate."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"center\">".$mtx."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"center\">".$mrx."</td>\n";
					echo "                     <td class=\"".$xtbg."\" align=\"center\"><b>".$xsta."</b></td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
					echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
					echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
					echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
					echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
					echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
					//echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$rowF['mjadd']."\">\n";
					echo "                           <input type=\"hidden\" name=\"custid\" value=\"".$row['custid']."\">\n";
					echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Job Details\">\n";
					echo "                        </form>\n";
					echo "                     </td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
					/*
					echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
					echo "                           <input type=\"hidden\" name=\"action\" value=\"mas\">\n";
					echo "                           <input type=\"hidden\" name=\"call\" value=\"MAS_detail\">\n";
					echo "                           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
					echo "                           <input type=\"hidden\" name=\"custid\" value=\"".$row['custid']."\">\n";
					echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";

					if (array_key_exists($row['njobid'],$mjar))
					{
						echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"MAS Details\">\n";
					}
					else
					{
						echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"MAS Details\" DISABLED>\n";
					}

					echo "                        </form>\n";
					*/
					echo "                     </td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
					/*
					echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
					echo "                           <input type=\"hidden\" name=\"action\" value=\"mas\">\n";
					echo "                           <input type=\"hidden\" name=\"call\" value=\"MAS_export\">\n";
					echo "                           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
					echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$rowF['mjadd']."\">\n";
					echo "                           <input type=\"hidden\" name=\"custid\" value=\"".$row['custid']."\">\n";
					*/

					/*
					if ($rowpre['enexp'] == 0)
					{
						//echo "                           <input class=\"buttondkredpnl80\" type=\"submit\" value=\"Office Enable\" DISABLED>\n";
						//echo "Err:Office Enable";
					}
					elseif (strtotime($rowpre['masimport']) > strtotime($rowD['added']))
					{
						//echo "                           <input class=\"buttondkredpnl80\" type=\"submit\" value=\"Insert Date\" DISABLED>\n";
						echo "Err:Insert Date";
					}
					elseif ($rowC['mas_office']==0 || $rowC['mas_div']==0 || $rowC['masid']==0)
					{
						echo "Err:Rep Config";
					}
					elseif (!is_numeric($row['njobid']))
					{
						echo "Err:Job Number";
						//echo "                           <input class=\"buttondkredpnl80\" type=\"submit\" value=\"Job Numb\" DISABLED>\n";
					}
					elseif ($mstat == '3')
					{
						//echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Resend\">\n";
					}
					elseif ($mstat >= '1')
					{
						echo "Proccessed";
						//echo "                           <input class=\"buttondkgrnpnl80\" type=\"submit\" value=\"Proccessed\" DISABLED>\n";
					}
					else
					{
						//echo "                           <input type=\"checkbox\" name=\"oktosend\" value=\"1\">\n";
						//echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Send\">\n";
					}

					//echo "                        </form>\n";
					*/
					echo "                     </td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi."</td>\n";
					echo "                  </tr>\n";
				//}
				$jerr=0;
			}
		}

		$ftcon        =number_format($tcon, 2, '.', ',');
		echo "                  <tr>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\" colspan=\"5\"><b>Total</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\"><b>".$ftcon."</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\" colspan=\"3\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\" colspan=\"7\"><b>Total Job(s) Displayed:</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\"><b><font color=\"red\">".$xj."</font></b></td>\n";
		echo "                  </tr>\n";
		echo "                  </table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function MAS_search()
{
	unset($_SESSION['tqry']);
	$acclist=explode(",",$_SESSION['aid']);
	
	$qry = "SELECT officeid,exportserver,exportlogin,exportpass,exportcatalog FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	//$srvstat=checkserverportstatus($row['exportserver'],1433,5);
	
	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
	
	$qry2 = "select * from bonus_schedule_config order by brept_yr desc;";
	$res2 = mssql_query($qry2);
	
	while ($row2 = mssql_fetch_array($res2))
	{
		$byr_ar[]=$row2['brept_yr'];
	}

	//echo $_SESSION['tqry']."<br>";
	//echo "<table width=\"950px\">\n";
	//echo "   <tr>\n";
	//echo "      <td>\n";
	//echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	//echo "				<tr>\n";
	//echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
	echo "						<table width=\"950px\">\n";
	echo "							<tr class=\"tblhd\">\n";
	echo "								<td align=\"left\"><b>Accounting Search Tool</b></td>\n";
	echo "								<td align=\"right\"></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\" colspan=\"2\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                  <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Data Field</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Input Parameter</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Build Year</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Renov Only</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Status</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Sort by</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Order by</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Inc Errors</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b></b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"mas\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"incerrs\" value=\"0\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\">\n";
	echo "												<select name=\"subq\">\n";
	echo "                                 		<option value=\"C.clname\">Customer Last Name</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"bboxl\" name=\"sval\" size=\"20\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                              		<select name=\"cyear\">\n";
	
	foreach ($byr_ar as $n2=>$v2)
	{
		echo "                                    	<option value=\"".$v2."\">".$v2."</option>\n";
	}
	
	echo "                              		</select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\">\n";
	echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\">\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"masstatus\">\n";
	echo "                                 		<option value=\"0\" SELECTED>All</option>\n";
	echo "                                 		<option value=\"1\">Unreleased</option>\n";
	echo "                                 		<option value=\"2\">Released</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td>\n";
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
	echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"incerrs\" value=\"1\">\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><button>Search</button></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\" valign=\"top\">\n";
	echo "						<select name=\"ctrinsdate\">\n";
	echo "							<option value=\"J2.contractdate\">Contract Date</option>\n";
	echo "							<option value=\"J1.added\">Insert Date</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
	echo "					<td align=\"left\" >\n";
	echo "						<input class=\"bboxl\" type=\"text\" name=\"d1\" id=\"d1\" size=\"20\" title=\"Enter Begin Date in this Field\"><br>\n";
	echo "						<input class=\"bboxl\" type=\"text\" name=\"d2\" id=\"d2\" size=\"20\" title=\"Enter End Date in this Field\">\n";
	echo "         			</form>\n";	
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td align=\"center\" colspan=\"8\"><hr width=\"90%\"</td>\n";
	echo "				</tr>\n";

	if ($_SESSION['clev'] >= 5)
	{
		echo "								<tr>\n";
		echo "                              	<td align=\"right\"><b>Salesman:</b></td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "         								<form method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"mas\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
		echo "											<input type=\"hidden\" name=\"incerrs\" value=\"0\">\n";
		echo "                                    		<select name=\"assigned\">\n";

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

		echo "                                    		</select>\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                              		<select name=\"cyear\">\n";
		
		foreach ($byr_ar as $n2=>$v2)
		{
			echo "                                    	<option value=\"".$v2."\">".$v2."</option>\n";
		}
		
		echo "                              		</select>\n";
		echo "									</td>\n";
		echo "                                 	<td align=\"center\" valign=\"bottom\">\n";
		echo "												<input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\">\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"masstatus\">\n";
		echo "                                 		<option value=\"0\" SELECTED>All</option>\n";
		echo "                                 		<option value=\"1\">Unreleased</option>\n";
		echo "                                 		<option value=\"2\">Released</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "                                 		<option value=\"J1.njobid\" SELECTED>Job #</option>\n";
		echo "                                 		<option value=\"J1.added\">Insert Date</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"ascdesc\">\n";
		echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
		echo "                                 		<option value=\"DESC\">Descending</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                                	<td align=\"center\" valign=\"bottom\">\n";
		echo "										<input class=\"checkboxgry\" type=\"checkbox\" name=\"incerrs\" value=\"1\">\n";
		echo "									</td>\n";
		echo "                                 	<td align=\"left\" valign=\"bottom\"><button>Search</button></form></td>\n";
		echo "								</tr>\n";
	}

	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "</div>\n";
	//echo "					</td>\n";
	//echo "				</tr>\n";
	//echo "			</table>\n";
	//echo "		</td>\n";
	//echo "	</tr>\n";
	//echo "</table>\n";
}

function masmatrix()
{
	if ($_SESSION['call']=="MAS_list")
	{
		//echo "List";
		MAS_list();
	}
	elseif ($_SESSION['call']=="search_results")
	{
		//echo "List";
		MAS_list();
	}
	elseif ($_SESSION['call']=="MAS_export")
	{
		//echo "Export";
		MAS_export();
	}
	elseif ($_SESSION['call']=="MAS_search")
	{
		//echo "Search";
		MAS_search();
	}
	elseif ($_SESSION['call']=="MAS_resend")
	{
		//echo "Search";
		MAS_resend();
	}
	elseif ($_SESSION['call']=="send_XML")
	{
		//echo "XML Send";
		send_XML();
	}
	elseif ($_SESSION['call']=="MAS_detail")
	{
		//echo "detail";
		MAS_detail();
	}
	elseif ($_SESSION['call']=="chistory_add")
	{
		chistory_add();
	}
	elseif ($_SESSION['call']=="chistory")
	{
		//echo "HISTORY";
		chistory_list();
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
}

?>