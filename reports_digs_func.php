<?php

function get_secids_by_oid()
{
	$secids=array();
	$qry = "SELECT securityid,lname,fname,mas_div FROM security WHERE officeid='".$_REQUEST['oid']."';";
	$res = mssql_query($qry);

	while ($row = mssql_fetch_array($res))
	{
		$secids[]=array($row['securityid'],$row['lname'],$row['fname']);
	}

	//echo $qry."<br>";
	
	//show_array_vars($secids);
	return $secids;
}

function getrecodigs($oid,$rid,$div)
{
	if (isset($mdiv) && $div != 0)
	{
		$qry  = "SELECT COUNT(oroid) as jcnt FROM recognized_digs WHERE rept_id='".$rid."' AND eoid='".$oid."' and mdiv='".$div."';";
	}
	else
	{
		$qry  = "SELECT COUNT(oroid) as jcnt FROM recognized_digs WHERE rept_id='".$rid."' AND eoid='".$oid."';";
	}
	$res  = mssql_query($qry);
	$row	= mssql_fetch_array($res);
	
	//echo $qry."<br>";
	
	//echo $row['jcnt'];
	
	return $row['jcnt'];
}

//function getmasperiods($oid,$masid,$prd)
function getmasperiods($masid,$prd,$yr)
{
	//echo "PRD:".$prd;
	error_reporting(E_ALL);
	$y	=str_pad($masid,3,"0",STR_PAD_LEFT);
	$d 	=substr($y,0,2);
	$d_ar=array();
	
	$odbc_ser	=	"192.168.1.22"; #the name of the SQL Server
	$odbc_add	=	"192.168.1.22";
	$odbc_db	=	"MAS_SYSTEM"; #the name of the database
	$odbc_user	=	"MAS_Reports"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username
	
	$odbc_conn0	= odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry0  = "SELECT company FROM ZE_Stats..divtocomp WHERE division='".$d."';";
	$odbc_res0	= odbc_exec($odbc_conn0, $odbc_qry0);
	$odbc_ret0 	= odbc_result($odbc_res0, 1);
	
	$odbc_conn1	= odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry1  = "SELECT CurrentFiscalYr FROM MAS_".$odbc_ret0."..GL0_Parameters WHERE CurrentFiscalYr is not null;";
	$odbc_res1	= odbc_exec($odbc_conn1, $odbc_qry1);
	$odbc_ret1 	= odbc_result($odbc_res1, 1);
	
	if (isset($_REQUEST['useprevfy']) && $_REQUEST['useprevfy']==1)
	{
		$setfy=$odbc_ret1 - 1;
	}
	else
	{
		$setfy=$odbc_ret1;
	}

	$odbc_conn2	= odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry2  = "SELECT ";
	$odbc_qry2 .= "Period1EndingDate,";
	$odbc_qry2 .= "Period2EndingDate,";
	$odbc_qry2 .= "Period3EndingDate,";
	$odbc_qry2 .= "Period4EndingDate,";
	$odbc_qry2 .= "Period5EndingDate,";
	$odbc_qry2 .= "Period6EndingDate,";
	$odbc_qry2 .= "Period7EndingDate,";
	$odbc_qry2 .= "Period8EndingDate,";
	$odbc_qry2 .= "Period9EndingDate,";
	$odbc_qry2 .= "Period10EndingDate,";
	$odbc_qry2 .= "Period11EndingDate,";
	$odbc_qry2 .= "Period12EndingDate";
	//$odbc_qry2 .= " FROM MAS_".$odbc_ret0."..GLC_FiscalYrMasterFile WHERE FiscalYr='".($odbc_ret1 - 1)."';";
	$odbc_qry2 .= " FROM MAS_".$odbc_ret0."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$setfy."';";
	$odbc_res2	= odbc_exec($odbc_conn2, $odbc_qry2);
	$odbc_ret21 = odbc_result($odbc_res2, 1);
	$odbc_ret22 = odbc_result($odbc_res2, 2);
	$odbc_ret23 = odbc_result($odbc_res2, 3);
	$odbc_ret24 = odbc_result($odbc_res2, 4);
	$odbc_ret25 = odbc_result($odbc_res2, 5);
	$odbc_ret26 = odbc_result($odbc_res2, 6);
	$odbc_ret27 = odbc_result($odbc_res2, 7);
	$odbc_ret28 = odbc_result($odbc_res2, 8);
	$odbc_ret29 = odbc_result($odbc_res2, 9);
	$odbc_ret210 = odbc_result($odbc_res2, 10);
	$odbc_ret211 = odbc_result($odbc_res2, 11);
	$odbc_ret212 = odbc_result($odbc_res2, 12);
	
	$d_ar=array(
						strtotime(substr($odbc_ret21,0,10)),
						strtotime(substr($odbc_ret22,0,10)),
						strtotime(substr($odbc_ret23,0,10)),
						strtotime(substr($odbc_ret24,0,10)),
						strtotime(substr($odbc_ret25,0,10)),
						strtotime(substr($odbc_ret26,0,10)),
						strtotime(substr($odbc_ret27,0,10)),
						strtotime(substr($odbc_ret28,0,10)),
						strtotime(substr($odbc_ret29,0,10)),
						strtotime(substr($odbc_ret210,0,10)),
						strtotime(substr($odbc_ret211,0,10)),
						strtotime(substr($odbc_ret212,0,10))
					);
	
	$pyr=$setfy-1;
	
	$odbc_conn3	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry3  = "SELECT ";
	$odbc_qry3 .= "Period12EndingDate";
	$odbc_qry3 .= " FROM MAS_".$odbc_ret0."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$pyr."';";
	$odbc_res3	= odbc_exec($odbc_conn3, $odbc_qry3);
	$odbc_ret3  = odbc_result($odbc_res3, 1);
	
	echo "<input type=\"hidden\" name=\"pmc".$masid."\" value=\"".$masid."\">\n"; // Parent Code
	
	if (is_array($d_ar))
	{
		echo "<select name=\"mas_prd".$masid."\">\n";
		echo "	<option value=\"0:0:0\"></option>\n";
		
		//echo $prd."<br>";
		foreach ($d_ar as $n => $v)
		{
			//echo $v."<br>\n";
			//print_r($d_ar);
			//echo $n."<br>";
			//echo strtotime($v)."<br>";
			$z				=$n+1;
			//$e				=date("m/d/Y",strtotime($v));
			$e				=date("m/d/Y",$v);
			//echo $e."<br>";
			if ($n!=0)
			{
				$nx		=$n-1;
				//$dplus1 	=strtotime($d_ar[$nx])+87000;
				$dplus1 	=$d_ar[$nx]+87000;
				$b	 		=date("m/d/Y",$dplus1);
			}
			else
			{
				//$b=date("m",strtotime($v))."/01/".date("Y",strtotime($v));
				//$dplus1 	=strtotime($odbc_ret3)+87000;
				$dplus1 	=strtotime(substr($odbc_ret3,0,10))+87000;
				$b			=date("m/d/Y",$dplus1);
			}
			
			if (date("n",$v)==$prd)
			{
				echo "	<option class=\"small\" value=\"".$z.":".$b.":".$e.":".$odbc_ret1."\" SELECTED>".$z." - ".$e."</option>\n";
			}
			else
			{
				echo "	<option class=\"small\" value=\"".$z.":".$b.":".$e.":".$odbc_ret1."\">".$z." - ".$e."</option>\n";
			}
		}
		
		echo "</select>\n";
	}
	
	//DisplayArray($d_ar);
}

//function getmasdigdata($eoid,$masid,$jids,$d)
function getmasdigdata($masid,$d)
{
	//echo "DATEI: ".$d."<br>";
	error_reporting(E_ALL);
	global $tjobs,$rjobs;
	$eoid		=0;
	
	$jar0		=	array();
	$jar1		=	array();
	$jdt1		=	array();
	$rdivs		=	array(97,98,99);
	$dconst		=	explode(":",$d);
	$pmasid		=	str_pad($masid,3,"0",STR_PAD_LEFT);
	//$div			=	substr($pmasid,0,2);
	$odbc_ser	=	"192.168.1.22"; #the name of the SQL Server
	$odbc_add	=	"192.168.1.22";
	$odbc_db	=	"MAS_SYSTEM"; #the name of the database
	$odbc_user	=	"MAS_Reports"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username
			
	//$odbc_conn =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	//$odbc_qry  = "SELECT company FROM ZE_Stats..divtocomp WHERE division='".$div."';";
	//$odbc_res	 = odbc_exec($odbc_conn, $odbc_qry);
	//$odbc_ret 	 = odbc_result($odbc_res, 1);

	$odbc_conn0	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry0   = "SELECT ";
	$odbc_qry0  .= "	t.JobNumber,t.TransactionDate ";
	$odbc_qry0  .= "FROM  ";
	$odbc_qry0  .= "	MAS_".$pmasid."..JC3_TransactionDetail AS t ";
	$odbc_qry0  .= "WHERE  ";
	$odbc_qry0  .= "	t.CostCode 	like '508L%'  ";
	$odbc_qry0  .= "AND t.SourceCode 	= 'AP' ";
	$odbc_qry0  .= "AND t.TransactionDate	< '".$dconst[1]." 00:00'  ";
	$odbc_qry0  .= "ORDER BY t.JobNumber ASC;";
	$odbc_res0	 = odbc_exec($odbc_conn0, $odbc_qry0);
			
	//echo $odbc_qry0."<br>";
	while (odbc_fetch_row($odbc_res0))
	{
		//echo substr(odbc_result($odbc_res0, 1),0,2)."<br>";
		$jar0[]		= odbc_result($odbc_res0, 1);
	}
	
	//Renovation Detect
	$odbc_conn0x  =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry0x   = "SELECT ";
	$odbc_qry0x  .= "	t.JobNumber,t.TransactionDate ";
	$odbc_qry0x  .= "FROM  ";
	$odbc_qry0x  .= "	MAS_".$pmasid."..JC3_TransactionDetail AS t ";
	$odbc_qry0x  .= "WHERE  ";
	//$odbc_qry0x  .= "	t.CostCode 	like '508L%'  ";
	$odbc_qry0x  .= "t.SourceCode 	= 'AP' ";
	$odbc_qry0x  .= "AND t.TransactionDate	< '".$dconst[1]." 00:00'  ";
	$odbc_qry0x  .= "ORDER BY t.JobNumber ASC;";
	$odbc_res0x	  = odbc_exec($odbc_conn0x, $odbc_qry0x);
			
	//echo $odbc_qry0."<br>";
	while (odbc_fetch_row($odbc_res0x))
	{
		//echo substr(odbc_result($odbc_res0, 1),0,2)."<br>";
		if (!in_array(odbc_result($odbc_res0x, 1),$jar0) && in_array(substr(odbc_result($odbc_res0x, 1),0,2),$rdivs))
		{
			$jar0[]		= odbc_result($odbc_res0x, 1);
		}
	}
	
	$odbc_conn0a	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry0a   = "SELECT ";
	$odbc_qry0a  .= "	t.JobNumber,t.TransactionDate ";
	$odbc_qry0a  .= "FROM  ";
	$odbc_qry0a  .= "	MAS_".$pmasid."..JC3_TransactionDetail AS t ";
	$odbc_qry0a  .= "WHERE  ";
	$odbc_qry0a  .= "	t.CostCode 	like '508L%'  ";
	$odbc_qry0a  .= "AND t.SourceCode 	= 'AP' ";
	$odbc_qry0a  .= "AND t.TransactionDate	>= '".$dconst[1]." 00:00' ";
	$odbc_qry0a  .= "AND t.TransactionDate	< '".$dconst[2]." 23:59:59' ";
	$odbc_qry0a  .= "ORDER BY t.JobNumber ASC;";
	$odbc_res0a	 = odbc_exec($odbc_conn0a, $odbc_qry0a);
			
	//echo $odbc_qry0a."<br>";
	while (odbc_fetch_row($odbc_res0a))
	{
		if (!in_array(odbc_result($odbc_res0a, 1),$jar0))
		{
			$jar1[]		= odbc_result($odbc_res0a, 1);
		}
	}
	
	//Renovation Detects
	$odbc_conn0ax	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry0ax   = "SELECT ";
	$odbc_qry0ax  .= "	t.JobNumber,t.TransactionDate ";
	$odbc_qry0ax  .= "FROM  ";
	$odbc_qry0ax  .= "	MAS_".$pmasid."..JC3_TransactionDetail AS t ";
	$odbc_qry0ax  .= "WHERE  ";
	//$odbc_qry0a  .= "	t.CostCode 	like '508L%'  ";
	$odbc_qry0ax  .= "t.SourceCode 	= 'AP' ";
	$odbc_qry0ax  .= "AND t.TransactionDate	>= '".$dconst[1]." 00:00' ";
	$odbc_qry0ax  .= "AND t.TransactionDate	< '".$dconst[2]." 23:59:59' ";
	$odbc_qry0ax  .= "ORDER BY t.JobNumber ASC;";
	$odbc_res0ax	 = odbc_exec($odbc_conn0ax, $odbc_qry0ax);
			
	//echo $odbc_qry0ax."<br>";
	while (odbc_fetch_row($odbc_res0ax))
	{
		if (
				!in_array(odbc_result($odbc_res0ax, 1),$jar0) &&
				!in_array(odbc_result($odbc_res0ax, 1),$jar1) &&
				in_array(substr(odbc_result($odbc_res0ax, 1),0,2),$rdivs)
			)
		{
			$jar1[]		= odbc_result($odbc_res0ax, 1);
		}
	}

	$jar1 =array_unique($jar1);
	
	//$jar1tst =array_unique($jar1tst);
	
	//echo "<br>";
	
	//print_r($jar1tst);
	//print_r($jar1);

	//echo "<br>";
	//if (count($jar1) > 0)
	//{
		//$cspan	=6;
		$brdr		=0;
		
		echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
		echo "   			<tr>\n";
		echo " 			  		<td colspan=\"3\" align=\"left\" valign=\"bottom\"><b>Company: ".$pmasid."</b></td>\n";
		echo " 			  		<td colspan=\"2\" align=\"center\" valign=\"bottom\"><b>Fiscal Period ".$dconst[0]." (".$dconst[1]." - ".$dconst[2].") </b></td>\n";
		echo " 			  		<td colspan=\"2\" align=\"right\" valign=\"bottom\"><b>Total Dig(s): ".count($jar1)."</b></td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"><b>Job Number</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"><b>Cost Code</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"><b>Tran Date</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"200px\"><b>Customer</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"50px\"><b>Dig/Reno</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"><b>Contract Amt</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"><b>Cost of Constr</b></td>\n";
		echo "   			</tr>\n";
		
		$divcnt	=0;
		$tdigs	=0;
		$rdigs	=0;
		$sdigs	=0;
		$j3tot	=0;
		$j4tot	=0;
		$j5tot	=0;
		$tj3tot	=0;
		$tj4tot	=0;
		$tj5tot	=0;
		$pjdiv	=0;
		$sj3tot	=0;
		$sj4tot	=0;
		$sj5tot	=0;
		foreach ($jar1 as $jo => $jv)
		{
			$tdc			="wh_und";
			$odbc_conn2	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
			
			if (in_array(substr($jv,0,2),$rdivs))
			{
				//echo $jv."<br>";
				$odbc_qry2   = "SELECT TOP 1";
				$odbc_qry2  .= "	t.JobNumber,t.TransactionDate,t.CostCode ";
				$odbc_qry2  .= "FROM  ";
				$odbc_qry2  .= "	MAS_".$pmasid."..JC3_TransactionDetail as t ";
				$odbc_qry2  .= "WHERE  ";
				$odbc_qry2  .= "	t.JobNumber = ".$jv." ";
				//$odbc_qry2  .= "AND t.CostCode like '508L%' ";
				$odbc_qry2  .= "AND t.SourceCode = 'AP' ";
				$odbc_qry2  .= "ORDER BY t.TransactionDate ASC;";
			}
			else
			{
				$odbc_qry2   = "SELECT TOP 1";
				$odbc_qry2  .= "	t.JobNumber,t.TransactionDate,t.CostCode ";
				$odbc_qry2  .= "FROM  ";
				$odbc_qry2  .= "	MAS_".$pmasid."..JC3_TransactionDetail as t ";
				$odbc_qry2  .= "WHERE  ";
				$odbc_qry2  .= "	t.JobNumber = ".$jv." ";
				$odbc_qry2  .= "AND t.CostCode like '508L%' ";
				$odbc_qry2  .= "AND t.SourceCode = 'AP' ";
				$odbc_qry2  .= "ORDER BY t.TransactionDate ASC;";	
			}
			
			$odbc_res2	 = odbc_exec($odbc_conn2, $odbc_qry2);
			
			$odbc_ret21	= odbc_result($odbc_res2, 1);
			$odbc_ret22	= odbc_result($odbc_res2, 2);
			$odbc_ret23	= odbc_result($odbc_res2, 3);
			
			$odbc_conn1	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
			$odbc_qry1   = "SELECT ";
			$odbc_qry1  .= "	j.JobNumber,j.JobDescription,j.RevisedEstimate,j.RevisedContract,j.JTDActualCosts,j.JTDpaymentReceived ";
			$odbc_qry1  .= "FROM  ";
			$odbc_qry1  .= "	MAS_".$pmasid."..JC1_JobMaster as j ";
			$odbc_qry1  .= "WHERE  ";
			$odbc_qry1  .= "	j.JobNumber=".$jv.";";
			$odbc_res1	 = odbc_exec($odbc_conn1, $odbc_qry1);
			
			$odbc_ret11	= odbc_result($odbc_res1, 1);
			$odbc_ret12	= odbc_result($odbc_res1, 2);
			$odbc_ret13	= odbc_result($odbc_res1, 3);
			$odbc_ret14	= odbc_result($odbc_res1, 4);
			$odbc_ret15	= odbc_result($odbc_res1, 5);
			$odbc_ret16	= odbc_result($odbc_res1, 6);
			
			$f3			= number_format($odbc_ret13, 2, '.', '');
			$f4			= number_format($odbc_ret14, 2, '.', '');
			$f5			= number_format($odbc_ret15, 2, '.', '');
			$ccode		= substr($odbc_ret23,0,4);
			
			if ($odbc_ret13 <= 0 || $odbc_ret14 <= 0 || $odbc_ret13 >= $odbc_ret14)
			{
				$tdc		="yel_und";	
			}
			
			if ($pjdiv == 0)
			{
				$pjdiv	=substr($odbc_ret11,0,2);
			}
			
			$cjdiv	=substr($odbc_ret11,0,2);
			
			echo "			<input type=\"hidden\" name=\"jid".$odbc_ret11."\" value=\"".$odbc_ret11."\">\n"; // MAS Job ID
			echo "			<input type=\"hidden\" name=\"oid".$odbc_ret11."\" value=\"".$eoid."\">\n"; // est officeid
			echo "			<input type=\"hidden\" name=\"mid".$odbc_ret11."\" value=\"".$pmasid."\">\n"; // masid
			echo "			<input type=\"hidden\" name=\"div".$odbc_ret11."\" value=\"".$cjdiv."\">\n"; // Fiscal Period End
			echo "			<input type=\"hidden\" name=\"scc".$odbc_ret11."\" value=\"".$ccode."\">\n"; // Source Cost Code
			echo "			<input type=\"hidden\" name=\"vcs".$odbc_ret11."\" value=\"".$f4."\">\n"; // Value Contract
			echo "			<input type=\"hidden\" name=\"dcc".$odbc_ret11."\" value=\"".$f3."\">\n"; // Cost of Construction
			echo "			<input type=\"hidden\" name=\"ovcs".$odbc_ret11."\" value=\"".$f4."\">\n"; // Value Contract
			echo "			<input type=\"hidden\" name=\"odcc".$odbc_ret11."\" value=\"".$f3."\">\n"; // Cost of Construction
			echo "			<input type=\"hidden\" name=\"cst".$odbc_ret11."\" value=\"".preg_replace("/'/","",trim($odbc_ret12))."\">\n"; // Customer
			echo "			<input type=\"hidden\" name=\"prd".$odbc_ret11."\" value=\"".$dconst[0]."\">\n"; // Fiscal Period
			echo "			<input type=\"hidden\" name=\"bdt".$odbc_ret11."\" value=\"".$dconst[1]."\">\n"; // Fiscal Period Calendar Date Begin
			echo "			<input type=\"hidden\" name=\"edt".$odbc_ret11."\" value=\"".$dconst[2]."\">\n"; // Fiscal Period Calendar Date End
			echo "			<input type=\"hidden\" name=\"fyr".$odbc_ret11."\" value=\"".$dconst[3]."\">\n"; // Fiscal Year
			echo "			<input type=\"hidden\" name=\"trd".$odbc_ret11."\" value=\"".date("m/d/Y",strtotime(substr($odbc_ret22,0,10)))."\">\n"; // Fiscal Year
			
			if (in_array(substr($odbc_ret11,0,2),$rdivs))
			{
				echo "			<input type=\"hidden\" name=\"rno".$odbc_ret11."\" value=\"1\">\n"; // Flags Renovation
				$rdigs++;
			}
			else
			{
				echo "			<input type=\"hidden\" name=\"rno".$odbc_ret11."\" value=\"0\">\n"; // Flags Dig
				$tdigs++;
			}
			
			//$tdigs++;
			
			if ($pjdiv != $cjdiv)
			{
				$divcnt++;
				$fsj3tot		= number_format($sj3tot, 2, '.', '');
				$fsj4tot		= number_format($sj4tot, 2, '.', '');
				echo "   			<tr>\n";
				echo " 			  		<td class=\"ltgray_und\" colspan=\"4\" align=\"right\" valign=\"bottom\" NOWRAP><b>Division ".$pjdiv." Subtotal</b> </td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sdigs."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP>".$fsj4tot."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP>".$fsj3tot."</td>\n";
				echo "   			</tr>\n";
				echo "   			<tr>\n";
				echo " 			  		<td class=\"ltgray_und\" colspan=\"7\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
				echo "   			</tr>\n";
				
				$sj3tot		=0;
				$sj4tot		=0;
				$sdigs		=0;
			}
			
			echo "   			<tr>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"center\" valign=\"bottom\" NOWRAP>".$odbc_ret11."</td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"center\" valign=\"bottom\" NOWRAP>".$ccode."</td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"center\" valign=\"bottom\" NOWRAP>".date("m/d/Y",strtotime(substr($odbc_ret22,0,10)))."</td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"left\" valign=\"bottom\" NOWRAP>".preg_replace("/'/","",trim($odbc_ret12))."</td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"right\" valign=\"bottom\" NOWRAP><input class=\"bboxnobr\" type=\"text\" name=\"vcs".$odbc_ret11."\" value=\"".$f4."\" size=\"12\" maxlength=\"12\"></td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"right\" valign=\"bottom\" NOWRAP><input class=\"bboxnobr\" type=\"text\" name=\"dcc".$odbc_ret11."\" value=\"".$f3."\" size=\"12\" maxlength=\"12\"></td>\n";
			echo "   			</tr>\n";
			
			$sdigs++;
			
			$pjdiv	=$cjdiv;
			$j3tot	=$j3tot+$odbc_ret13;
			$j4tot	=$j4tot+$odbc_ret14;
			$j5tot	=$j5tot+$odbc_ret15;
			
			$sj3tot	=$sj3tot+$odbc_ret13;
			$sj4tot	=$sj4tot+$odbc_ret14;
			$sj5tot	=$sj5tot+$odbc_ret15;
			
			if ($divcnt > 0 && ($tdigs + $rdigs) == count($jar1))
			{
				$divcnt++;
				$xjdiv	   =substr($odbc_ret11,0,2);
				$fsj3tot		= number_format($sj3tot, 2, '.', '');
				$fsj4tot		= number_format($sj4tot, 2, '.', '');
				echo "   			<tr>\n";
				echo " 			  		<td class=\"ltgray_und\" colspan=\"4\" align=\"right\" valign=\"bottom\" NOWRAP><b>Division ".$xjdiv." Subtotal</b> </td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sdigs."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP>".$fsj4tot."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP>".$fsj3tot."</td>\n";
				echo "   			</tr>\n";

				$sj3tot		=0;
				$sj4tot		=0;
				$sdigs		=0;
			}
		}
		
		$tj3tot		= number_format($j3tot, 2, '.', '');
		$tj4tot		= number_format($j4tot, 2, '.', '');
		$tj5tot		= number_format($j5tot, 2, '.', '');
		
		$rjobs=$rjobs+$rdigs;
		$tjobs=$tjobs+$tdigs;
		echo "   			<tr>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP>". ($tdigs + $rdigs) ."</td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP>".$tj4tot."</td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP>".$tj3tot."</td>\n";
		echo "   			</tr>\n";
		echo "   			</table>\n";		
	//}
}

function admin_digrpt_pub_post()
{
	error_reporting(E_ALL);
	$q=0;
	$qry0 = "SELECT rept_id FROM recognized_digs WHERE rept_id='".$_REQUEST['rept_id']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{
		echo "Report for this Period has already been Published<br>\n";
		exit;
	}
	
	foreach ($_POST as $np => $nv)
	{
		if (substr($np,0,3) == "jid")
		{
			$q++;
			$jid		= $nv;
			$eoid		= $_REQUEST['oid'.$jid];
			$moid 		= $_REQUEST['mid'.$jid];
			$mdiv 		= $_REQUEST['div'.$jid];
			//$rept_id 	= $_REQUEST['rept_id'];
			
			if (!empty($_REQUEST['rept_ida']) && $_REQUEST['rept_ida']!=$_REQUEST['rept_id'])
			{
				$rept_id 	= $_REQUEST['rept_ida'];
			}
			else
			{
				$rept_id 	= $_REQUEST['rept_id'];
			}
			
			$rept_mo 	= $_REQUEST['rept_mo'];
			$rept_yr 	= $_REQUEST['rept_yr'];
			$prd 		= $_REQUEST['prd'.$jid];
			$fyr 		= $_REQUEST['fyr'.$jid];
			$vcs 		= $_REQUEST['vcs'.$jid];
			$dcc 		= $_REQUEST['dcc'.$jid];
			$cst 		= $_REQUEST['cst'.$jid];
			$bdt 		= $_REQUEST['bdt'.$jid];
			$edt 		= $_REQUEST['edt'.$jid];
			$trd 		= $_REQUEST['trd'.$jid];
			$rno 		= $_REQUEST['rno'.$jid];
			
			$qry1  = "INSERT INTO recognized_digs (eoid, moid, mdiv, rept_id, rept_mo, rept_yr, jid, vcs, dcc, bdt, edt, cst, prd, fyr, trandate, pubdate, sid, reno) ";
			$qry1 .= "VALUES ('".$eoid."', '".$moid."', '".$mdiv."', '".$rept_id."', '".$rept_mo."', '".$rept_yr."', '".$jid."', ";
			$qry1 .= "'".$vcs."', '".$dcc."', '".$bdt."', '".$edt."', '".$cst."', '".$prd."', '".$fyr."', '".$trd."', '".date("m/d/Y",time())."', '".$_SESSION['securityid']."', '".$rno."');";
			$res1  = mssql_query($qry1);
		}
	}

	admin_digrpt_pub_arch_list();
}

function admin_digrpt_pub_arch_list()
{
	//echo $rept_id."<br>";
	error_reporting(E_ALL);
	
	//$qry = "SELECT DISTINCT(rept_id),rept_mo,rept_yr,pubdate,sid FROM recognized_digs ORDER BY rept_yr DESC,rept_mo DESC;";
	//$qry = "SELECT DISTINCT(rept_id),rept_mo,rept_yr,pubdate FROM recognized_digs ORDER BY rept_yr,rept_mo DESC;";
	$qry  = "SELECT  ";
	$qry .= "	DISTINCT(rd1.rept_id) ";
	$qry .= "	,rd2.rept_mo ";
	$qry .= "	,rd2.rept_yr ";
	$qry .= "	,(select top 1 pubdate from recognized_digs where rept_id=rd1.rept_id order by pubdate desc) as pubdate ";
	$qry .= "	,(select top 1 sid from recognized_digs where rept_id=rd1.rept_id order by pubdate desc) as sid ";
	$qry .= "FROM  ";
	$qry .= "	recognized_digs as rd1 ";
	$qry .= "INNER JOIN ";
	$qry .= "	recognized_digs as rd2 ";
	$qry .= "ON  ";
	$qry .= "	rd1.rept_id=rd2.rept_id ";
	$qry .= "ORDER BY  ";
	$qry .= "	rd2.rept_yr DESC, ";
	$qry .= "	rd2.rept_mo DESC; ";
	
	$res = mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	//echo $qry."<br>";
	
	echo "			<table width=\"100%\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td>\n";
	echo "						<table class=\"outer\" width=\"100%\">\n";
	echo "   						<tr>\n";
	echo " 			  					<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Recognized Dig Reports</b></td>\n";
	echo "         					<form method=\"post\">\n";
	echo "								<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
	echo "								<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
	echo "								<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub\">\n";
	echo "								<input type=\"hidden\" name=\"publish\" value=\"0\">\n";
	echo "      						<td class=\"gray\" align=\"right\">Calendar M/Y</td>\n";
	echo "      						<td class=\"gray\" align=\"left\">\n";
	echo "								<select name=\"rept_mo\" alt=\"Report will be stored as Month selected\">\n";
	
	for ($m=1;$m <= 12; $m++)
	{
		if ($m == date("n",time()))
		{
			echo "									<option value=\"".$m."\" SELECTED>".$m."</option>\n";
		}
		else
		{
			echo "									<option value=\"".$m."\">".$m."</option>\n";
		}
	}
	
	echo "								</select>\n";
	echo "								<select name=\"rept_yr\" alt=\"Report will be stored as Year selected\">\n";
	
	for ($y=(date("Y",time()) - 1);$y <= (date("Y",time()) + 1); $y++)
	{
		if ($y == date("Y",time()))
		{
			echo "									<option value=\"".$y."\" SELECTED>".$y."</option>\n";
		}
		else
		{
			echo "									<option value=\"".$y."\">".$y."</option>\n";
		}
	}
	
	echo "								</select>\n";
	echo "								</td>\n";
	echo "      						<td class=\"gray\" align=\"right\" valign=\"bottom\">Use Prev FY Year</td>\n";
	echo "      						<td class=\"gray\" align=\"center\" valign=\"bottom\" width=\"15px\">\n";
	echo "									<input class=\"transnb\" type=\"checkbox\" name=\"useprevfy\" value=\"1\" alt=\"Use Previous FY Periods\">\n";
	echo "								</td>\n";
	echo "      						<td class=\"gray\" align=\"center\" valign=\"bottom\" width=\"15px\">\n";
	echo "									<input class=\"transnb\" type=\"image\" src=\"images/application_add.png\" alt=\"New Publish\">\n";
	echo "								</td>\n";
	echo "         					</form>\n";
	echo "   						</tr>\n";
	echo " 			  			</table>\n";
	echo " 			  		</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	
	if ($nrow > 0)
	{
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "   			<tr>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Rpt Mo</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Rpt Yr</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Digs</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Pub Date</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Creator</b></td>\n";
		//echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		
		if ($_SESSION['securityid']==269999999999999)
		{
			echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		}
		
		echo "   			</tr>\n";
		
		$rcnt=0;
		while($row=mssql_fetch_array($res))
		{
			$rcnt++;
			
			$qryA = "SELECT lname,fname,mas_div FROM security WHERE securityid='".$row['sid']."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);
			
			$qryB = "SELECT COUNT(oroid) as ocnt FROM recognized_digs WHERE rept_id='".$row['rept_id']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			
			echo "		   	<tr>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\">".$rcnt."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$row['rept_mo']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$row['rept_yr']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowB['ocnt']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".date("m/d/Y",strtotime($row['pubdate']))."</td>\n";
			//echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowA['lname']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowA['lname']."</td>\n";
			
			if ($_SESSION['securityid']==26999999999999)
			{
				echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
				echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
				echo "						<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub_hist_view\">\n";
				echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$row['rept_id']."\">\n";
				echo "      			<td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"15px\">\n";
				echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"Open this History\">\n";
				echo "					</td>\n";
				echo "         		</form>\n";
			}
			
			echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub_arch_view\">\n";
			echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$row['rept_id']."\">\n";
			echo "      			<td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"15px\">\n";
			echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"Open this Archive\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
			echo "		   	</tr>\n";
		}
		
		echo "			</table>\n";
	}
	else
	{
		echo "No Stored Recognized Dig Reports found.";
	}
}

function admin_digrpt_pub_arch_view()
{
	//echo $rept_id;
	$oid_ar=array();
	
	//$qry = "SELECT * FROM recognized_digs WHERE rept_id='".$_REQUEST['rept_id']."';";
	$qry = "SELECT * FROM recognized_digs WHERE rept_id='".$_REQUEST['rept_id']."' order by moid,mdiv,jid;";
	$res = mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	$qry0 = "SELECT distinct(moid) FROM recognized_digs WHERE rept_id='".$_REQUEST['rept_id']."' order by moid;";
	$res0 = mssql_query($qry0);
	$nrow0 = mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			$oid_ar[]=$row0['moid'];
		}
	}
	
	$qryp = "SELECT rept_mo,rept_yr FROM recognized_digs WHERE rept_id='".$_REQUEST['rept_id']."';";
	$resp = mssql_query($qryp);
	$rowp = mssql_fetch_array($resp);
	
	if ($nrow > 0)
	{
		echo "<input type=\"hidden\" name=\"#anchor\">\n";
		echo "			<table width=\"100%\" border=\"0\">\n";
		echo "   			<tr>\n";
		echo " 			  		<td align=\"left\" valign=\"bottom\"><b>Companies Reported</b></td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo " 			  		<td align=\"center\" valign=\"bottom\">\n";
		
		$o		=0;
		$ocnt	=count($oid_ar);
		foreach ($oid_ar as $no => $vo)
		{
			$o++;
			echo "<a href=\"#".$vo."\">".$vo."</a>";
			
			/*if ($o!=$ocnt)
			{
				echo " - ";
			}*/
			
			if ($o==(round(($ocnt/2))))
			{
				echo "<br/>";
			}
			elseif ($o!=$ocnt)
			{
				echo " - ";
			}
		}
		
		echo " 			  		</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "			<table width=\"100%\" border=\"0\">\n";
		echo "   			<tr>\n";
		echo " 			  		<td>\n";
		echo "						<table width=\"100%\" border=\"0\">\n";
		echo "   						<tr>\n";
		echo " 			  					<td align=\"left\" valign=\"bottom\"><b>Recognized Dig Report Archive</b></td>\n";
		echo " 			  					<td align=\"left\" valign=\"bottom\"><b>Report Period: ".$rowp['rept_mo']."/".$rowp['rept_yr']."</b></td>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
		echo "								<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub_delete\">\n";
		echo "								<input type=\"hidden\" name=\"publish\" value=\"0\">\n";
		echo "								<input type=\"hidden\" name=\"rept_id\"	value=\"".$_REQUEST['rept_id']."\">\n";
		echo "								<input type=\"hidden\" name=\"rept_mo\"	value=\"".$rowp['rept_mo']."\">\n";
		echo "								<input type=\"hidden\" name=\"rept_yr\"	value=\"".$rowp['rept_yr']."\">\n";
		echo "      						<td align=\"right\" valign=\"bottom\">\n";
		echo "									<input class=\"transnb\" type=\"checkbox\" name=\"confirmdelete\" value=\"1\" title=\"Check this box and click the red X to delete this Archived Report\">\n";
		echo "									<input class=\"transnb\" type=\"image\" src=\"images/deletesm.gif\" alt=\"Check the box and click to delete this Archived Report\">\n";
		echo "								</td>\n";
		echo "         					</form>\n";
		echo "   						</tr>\n";
		echo " 			  			</table>\n";
		echo " 			  		</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "			<table width=\"100%\" border=\"0\">\n";
		echo "   			<tr>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP><b>Job ID</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP><b>508L Date</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP><b>Customer</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP><b>Digs</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP><b>Contract Value</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP><b>Cost of Constr</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP><b>Save</b></td>\n";
		echo "   			</tr>\n";

		$jcnt	=0;
		$vtot	=0;
		$ctot	=0;
		$pjdiv	=0;
		$vcstot	=0;
		$dcctot	=0;
		$tdigs	=0;
		$sdigs	=0;
		$divcnt	=0;
		$pjcpy	=0;
		$cjcpy	=0;
		$cpycnt	=0;
		$ctdigs	=0;
		while ($row = mssql_fetch_array($res))
		{
			$tdc		="wh_und";
			
			if ($row['vcs'] <= 0 || $row['dcc'] <= 0 || $row['dcc'] >= $row['vcs'])
			{
				$tdc		="yel_und";	
			}
			
			if ($pjdiv == 0)
			{
				$pjdiv	=$row['mdiv'];
			}
			
			if ($pjcpy == 0)
			{
				$pjcpy	=$row['moid'];
			}
			
			$cjdiv	=$row['mdiv'];
			$cjcpy	=$row['moid'];
			
			//$jcnt++;
			
			if ($pjdiv != $cjdiv)
			{
				$divcnt++;
				echo "   			<tr>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP>Division: ".$pjdiv."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP>".$sdigs."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP>".number_format($vcstot, 2, '.','')."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP>".number_format($dcctot, 2, '.','')."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
				echo "   			</tr>\n";
				
				$vcstot		=0;
				$dcctot		=0;
				$sdigs		=0;
			}
			
			if ($jcnt==0 || $pjcpy != $cjcpy)
			{
				echo "   			<tr>\n";
				echo " 			  		<td colspan=\"9\" class=\"gray\" align=\"center\" NOWRAP></td>\n";
				echo "   			</tr>\n";		
				echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "								<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
				echo "								<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
				echo "								<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub_delete\">\n";
				echo "								<input type=\"hidden\" name=\"publish\" value=\"0\">\n";
				echo "								<input type=\"hidden\" name=\"rept_id\"	value=\"".$_REQUEST['rept_id']."\">\n";
				echo "								<input type=\"hidden\" name=\"moid\"	value=\"".$cjcpy."\">\n";
				echo "								<input type=\"hidden\" name=\"rept_mo\"	value=\"".$rowp['rept_mo']."\">\n";
				echo "								<input type=\"hidden\" name=\"rept_yr\"	value=\"".$rowp['rept_yr']."\">\n";
				echo "   			<tr>\n";
				echo " 			  		<td colspan=\"2\" class=\"gray_und\" align=\"left\" NOWRAP><b>Company: ".$cjcpy."</b><input type=\"hidden\" name=\"#".$cjcpy."\"> <a href=\"#anchor\">Up</a></td>\n";
				echo "      			<td colspan=\"2\" class=\"gray_und\" align=\"left\" valign=\"bottom\">\n";
				echo "						<input class=\"checkboxgry\" type=\"checkbox\" name=\"confirmdelete\" value=\"1\" title=\"Check this box and click the red X to delete this Company within this Archived Report\">\n";
				echo "						<input class=\"checkboxgry\" type=\"image\" src=\"images/deletesm.gif\" alt=\"Check the box and click to delete this Company within this Archived Report\">\n";
				echo "					</td>\n";
				echo " 			  		<td colspan=\"5\" class=\"gray_und\" align=\"right\" NOWRAP><b>Period: ".$row['prd']." (".date("m/d/Y",strtotime($row['bdt']))." - ".date("m/d/Y",strtotime($row['edt'])).")</b></td>\n";
				echo "   			</tr>\n";
				echo "         					</form>\n";
			}
			
			$jcnt++;
			
			echo "   			<tr>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"center\" NOWRAP>".$row['jid']."</td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"center\" NOWRAP>".date("m/d/Y",strtotime($row['trandate']))."</td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"left\" NOWRAP>".$row['cst']."</td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"center\" NOWRAP></td>\n";
			echo "         			<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "								<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
			echo "								<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
			echo "								<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub_update_item\">\n";
			echo "								<input type=\"hidden\" name=\"rept_id\"	value=\"".$_REQUEST['rept_id']."\">\n";
			echo "								<input type=\"hidden\" name=\"oroid\"	value=\"".$row['oroid']."\">\n";
			echo "								<input type=\"hidden\" name=\"rept_mo\"	value=\"".$rowp['rept_mo']."\">\n";
			echo "								<input type=\"hidden\" name=\"rept_yr\"	value=\"".$rowp['rept_yr']."\">\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"right\" NOWRAP><input class=\"bboxnobr\" type=\"text\" name=\"vcs\" value=\"".number_format($row['vcs'], 2, '.','')."\" size=\"12\" maxlength=\"12\"></td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"left\" NOWRAP>\n";
			
			if (!empty($row['modvcs']) && $row['modvcs']==1)
			{
				echo "		<img src=\"images/action_check.gif\" alt=\"This amount has been modified\">";
			}
			
			echo "					</td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"right\" NOWRAP><input class=\"bboxnobr\" type=\"text\" name=\"dcc\" value=\"".number_format($row['dcc'], 2, '.','')."\" size=\"12\" maxlength=\"12\"></td>\n";
			echo " 			  		<td class=\"".$tdc."\" align=\"left\" NOWRAP>\n";
			
			if (!empty($row['moddcc']) && $row['moddcc']==1)
			{
				echo "		<img src=\"images/action_check.gif\" alt=\"This amount has been modified\">";
			}
			
			echo "					</td>\n";
			echo "  				<td class=\"".$tdc."\" align=\"center\" valign=\"bottom\">\n";
			echo "						<input class=\"checkboxwh\" type=\"image\" src=\"images/save.gif\" alt=\"Save this Entry\">\n";
			echo "					</td>\n";
			echo "</form>\n";
			echo "   			</tr>\n";
			
			$sdigs++;
			
			if ($divcnt > 0 && $jcnt == $nrow)
			{
				$divcnt++;
				echo "   			<tr>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"left\" NOWRAP></td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP>Division: ".$pjdiv."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP>".$sdigs."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP>".number_format($vcstot, 2, '.','')."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP>".number_format($dcctot, 2, '.','')."</td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
				echo "   			</tr>\n";
				
				$vcstot		=0;
				$dcctot		=0;
				$sdigs		=0;
			}
			
			$pjdiv	=$cjdiv;
			$pjcpy	=$cjcpy;
			$vcstot	=$vcstot+$row['vcs'];
			$dcctot	=$dcctot+$row['dcc'];
		}
		
		echo "   			<tr>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" NOWRAP>".$jcnt."</td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"right\" NOWRAP></td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
	}
	
}

function admin_digrpt_pub_preview()
{
	error_reporting(E_ALL);
	global $tjobs,$rjobs,$ajobs;
	
	$qry0 = "SELECT * FROM recognized_digs WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0 = mssql_num_rows($res0);
	
	$tjobs =0;
	$rjobs =0;
	$ajobs =0;
	$de_err=array();
	$ce_err=array();
	$on_arr=array();
	$mc_arr=array();
	$jn_arr=array();
	$jc_arr=array();
	$of_arr=$_REQUEST['ex_off'];
	
	sort($of_arr);

	if (!empty($_REQUEST['publish']) && $_REQUEST['publish']==1)
	{
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
		echo "						<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub\">\n";
		echo "						<input type=\"hidden\" name=\"publish\" value=\"2\">\n";
		echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$_REQUEST['rept_id']."\">\n";
		echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$_REQUEST['rept_mo']."\">\n";
		echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$_REQUEST['rept_yr']."\">\n";
	}
	
	echo "			<table width=\"100%\" border=\"0\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Recognized Digs and Renovations Publication Worksheet</b></td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	
	//print_r($of_arr);
	
	foreach ($of_arr as $n => $v)
	{
		//echo "MID: ".$v."<br>";
		getmasdigdata($_REQUEST['pmc'.$v],$_REQUEST['mas_prd'.$v]);
	}
	
	echo "					<input type=\"hidden\" name=\"tjobs\"	value=\"".$tjobs."\">\n";
	echo "			<table width=\"100%\" border=\"0\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP width=\"200px\"><b>Total Digs</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"50px\">".$tjobs."</td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP width=\"200px\"><b>Total Renos</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"50px\">".$rjobs."</td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP width=\"200px\"><b>Total Addn</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"50px\">".$ajobs."</td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"80px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" NOWRAP width=\"200px\"><b>Total Digs/Renos/Addn</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"50px\">". ($tjobs + $rjobs + $ajobs) ."</td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Publish\"></td>\n";
	//echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP width=\"90px\">\n";
	
	if ($nrow0 > 0)
	{
		echo "						<input class=\"checkboxwh\" type=\"checkbox\" name=\"rept_ida\" value=\"".$row0['rept_id']."\" title=\"Check box to Attach this Dataset to a previously Stored Reco Dig Report for this Fiscal Period\">";
		echo "						Attach";
	}
	
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "         		</form>\n";
}

function sub_eqpbuild($njobid,$cost,$pcost)
{
	$MAS		=$_SESSION['pb_code'];
	$cost_ar	=explode(",",$cost);
	$pcost_ar	=explode(",",$pcost);
	$out_ar		=array();
	$out		="";

	foreach ($cost_ar as $n1 => $v1)
	{
		$icost_ar=explode(":",$v1);

		$qry1  = "select ";
		$qry1 .= "	(select abrev from material_grp_codes where masgrp=b.masgrp) as masabrev ";
		$qry1 .= "from [".$MAS."inventory] as a ";
		$qry1 .= "inner join material_master as b ";
		$qry1 .= "	on a.matid=b.id  ";
		$qry1 .= "where a.officeid='".$_SESSION['officeid']."' ";
		$qry1 .= "	and a.invid='".$icost_ar[1]."' ";
		$qry1 .= "	and b.masgrp!='0' ";
		$qry1 .= "	and (select active from material_grp_codes where masgrp=b.masgrp)!='0';";

		$res1 = mssql_query($qry1);
		$nrow1= mssql_num_rows($res1);

		if ($nrow1!=0)
		{
			$row1= mssql_fetch_array($res1);
			$out_ar[]=$row1['masabrev'];
		}
	}

	foreach ($pcost_ar as $n2 => $v2)
	{
		$ipcost_ar=explode(":",$v2);

		$qry2  = "select ";
		$qry2 .= "	(select abrev from material_grp_codes where masgrp=b.masgrp) as masabrev ";
		$qry2 .= "from [".$MAS."inventory] as a ";
		$qry2 .= "inner join material_master as b ";
		$qry2 .= "	on a.matid=b.id  ";
		$qry2 .= "where a.officeid='".$_SESSION['officeid']."' ";
		$qry2 .= "	and a.invid='".$ipcost_ar[5]."' ";
		$qry2 .= "	and b.masgrp!='0' ";
		$qry2 .= "	and (select active from material_grp_codes where masgrp=b.masgrp)!='0';";

		$res2 = mssql_query($qry2);
		$nrow2=mssql_num_rows($res2);

		if ($nrow2!=0)
		{
			$row2= mssql_fetch_array($res2);
			$out_ar[]=$row2['masabrev'];
		}
	}

	$out_ar=array_unique($out_ar);

	foreach($out_ar as $o => $r)
	{
		$or=$r."|";
		$out=$out.$or;
	}
	//echo $njobid." : ";
	//print_r($out_ar);
	//echo "<br>";
	$out=preg_replace("/\|\Z/","",$out);
	//echo $out."<br>";
	return $out;
}

function job_period_rpt()
{
	//
	$curryr	=2007;
	$priyr	=$curryr-1;

	if (isset($_REQUEST['oidrpt']) && $_REQUEST['oidrpt']!=0)
	{
		$oidset="and officeid='".$_SESSION['officeid']."'";
	}
	else
	{
		$oidset="";
	}

	$qry0 = "SELECT periodptr FROM bonus_schedule_config WHERE brept_yr='".$curryr."';";
	$res0 = mssql_query($qry0);
	$row0= mssql_fetch_array($res0);
	
	//echo $qry0."<br>";

	$qry1 = "SELECT officeid,name FROM offices WHERE active=1 and endigreport >=1 ".$oidset." ORDER BY name ASC;";
	$res1 = mssql_query($qry1);

	echo "			<table border=\"0\">\n";
	echo "   				<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"center\" valign=\"bottom\" colspan=\"17\"><b>".$curryr." Dig Totals</b></td>\n";
	echo "   				</tr>\n";
	echo "   				<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" width=\"160px\" NOWRAP><b>Name</b></td>\n";
	//echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" width=\"40px\" NOWRAP><b>ID</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>DEC</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>JAN</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>FEB</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>MAR</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>APR</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>MAY</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>JUN</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>JUL</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>AUG</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>SEP</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>OCT</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>NOV</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>".substr($curryr,2,2)." YTD</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>".substr($priyr,2,2)." YTD</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>Var</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"40px\" NOWRAP><b>%</b></td>\n";
	echo "   				</tr>\n";
	$xyz		=0;
	$zyx		=0;
	$pp_ar		=array(0,0,0,0,0,0,0,0,0,0,0,0);
	$tp_ar		=array(0,0,0,0,0,0,0,0,0,0,0,0);
	$xtip_ar	=array(0,0,0,0,0,0,0,0,0,0,0,0);
	$oxtip_ar	=array(0,0,0,0,0,0,0,0,0,0,0,0);
	while ($row1= mssql_fetch_array($res1))
	{
		$p_ar=array(0,0,0,0,0,0,0,0,0,0,0,0);
		$qry2 = "SELECT officeid,dsgfperiod,dsgfarray FROM security WHERE officeid='".$row1['officeid']."' and dsgfperiod='".$curryr."' ;";
		$res2 = mssql_query($qry2);
		$nrow2= mssql_num_rows($res2);

		$qry3z = "SELECT SUM(p1) as p1,SUM(p2) as p2,SUM(p3) as p3,SUM(p4) as p4,SUM(p5) as p5,SUM(p6) as p6,SUM(p7) as p7,SUM(p8) as p8,SUM(p9) as p9,SUM(p10) as p10,SUM(p11) as p11,SUM(p12) as p12 FROM olddigs WHERE officeid='".$row1['officeid']."' and yr='".$priyr."' ;";
		$res3z = mssql_query($qry3z);
		$nrow3z= mssql_num_rows($res3z);
		//echo $qry2."<br>";

		if ($nrow2 > 0)
		{
			$trip0="yel_und";
			$trip1="yel_und";
			$trip2="yel_und";
			$trip3="yel_und";
			$trip4="wh_und";
			$trip5="wh_und";
			$trip6="wh_und";
			$trip7="wh_und";
			$trip8="wh_und";
			$trip9="wh_und";
			$trip10="wh_und";
			$trip11="wh_und";

			$tip_ar=array(0,0,0,0,0,0,0,0,0,0,0,0);

			while ($row2= mssql_fetch_array($res2))
			{
				$ip_ar	=explode(",",$row2['dsgfarray']);
				$ip_ar[4]=0;
				$ip_ar[5]=0;
				$ip_ar[6]=0;
				$ip_ar[7]=0;
				$ip_ar[8]=0;
				$ip_ar[9]=0;
				$ip_ar[10]=0;
				$ip_ar[11]=0;


				$tip_ar	=array(
				0=>$tip_ar[0]+$ip_ar[0],
				1=>$tip_ar[1]+$ip_ar[1],
				2=>$tip_ar[2]+$ip_ar[2],
				3=>$tip_ar[3]+$ip_ar[3],
				4=>$tip_ar[4]+$ip_ar[4],
				5=>$tip_ar[5]+$ip_ar[5],
				6=>$tip_ar[6]+$ip_ar[6],
				7=>$tip_ar[7]+$ip_ar[7],
				8=>$tip_ar[8]+$ip_ar[8],
				9=>$tip_ar[9]+$ip_ar[9],
				10=>$tip_ar[10]+$ip_ar[10],
				11=>$tip_ar[11]+$ip_ar[11]
				);

				$xtip_ar	=array(
				0=>$xtip_ar[0]+$ip_ar[0],
				1=>$xtip_ar[1]+$ip_ar[1],
				2=>$xtip_ar[2]+$ip_ar[2],
				3=>$xtip_ar[3]+$ip_ar[3]
				);

			}

			$qry7 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='4';";
			$res7 = mssql_query($qry7);
			$row7= mssql_fetch_array($res7);
			$nrow7= mssql_num_rows($res7);

			if ($nrow7 > 0)
			{
				$tip_ar[4]=$row7['no_digs'];
			}
			else
			{
				$tip_ar[4]=0;
			}

			$qry8 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='5';";
			$res8 = mssql_query($qry8);
			$row8=mssql_fetch_array($res8);
			$nrow8= mssql_num_rows($res8);

			if ($nrow8 > 0)
			{
				$tip_ar[5]=$row8['no_digs'];
			}
			else
			{
				$tip_ar[5]=0;
			}

			$qry9 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='6';";
			$res9 = mssql_query($qry9);
			$row9= mssql_fetch_array($res9);
			$nrow9= mssql_num_rows($res9);

			if ($nrow9 > 0)
			{
				$tip_ar[6]=$row9['no_digs'];
			}
			else
			{
				$tip_ar[6]=0;
			}

			$qry10 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='7';";
			$res10 = mssql_query($qry10);
			$row10= mssql_fetch_array($res10);
			$nrow10= mssql_num_rows($res10);

			if ($nrow10 > 0)
			{
				$tip_ar[7]=$row10['no_digs'];
			}
			else
			{
				$tip_ar[7]=0;
			}

			$qry11 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='8';";
			$res11 = mssql_query($qry11);
			$row11= mssql_fetch_array($res11);
			$nrow11= mssql_num_rows($res11);

			if ($nrow11 > 0)
			{
				$tip_ar[8]=$row11['no_digs'];
			}
			else
			{
				$tip_ar[8]=0;
			}

			$qry12 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='9';";
			$res12 = mssql_query($qry12);
			$row12= mssql_fetch_array($res12);
			$nrow12= mssql_num_rows($res12);

			if ($nrow12 > 0)
			{
				$tip_ar[9]=$row12['no_digs'];
			}
			else
			{
				$tip_ar[9]=0;
			}

			$qry13 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='10';";
			$res13 = mssql_query($qry13);
			$row13= mssql_fetch_array($res13);
			$nrow13= mssql_num_rows($res13);

			if ($nrow13 > 0)
			{
				$tip_ar[10]=$row13['no_digs'];
			}
			else
			{
				$tip_ar[10]=0;
			}

			$qry14 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='11';";
			$res14 = mssql_query($qry14);
			$row14= mssql_fetch_array($res14);
			$nrow14= mssql_num_rows($res14);

			if ($nrow14 > 0)
			{
				$tip_ar[11]=$row14['no_digs'];
			}
			else
			{
				$tip_ar[11]=0;
			}

			$p_ar	=array(
			0=>$tip_ar[0],1=>$tip_ar[1],2=>$tip_ar[2],3=>$tip_ar[3],
			4=>$tip_ar[4],5=>$tip_ar[5],6=>$tip_ar[6],7=>$tip_ar[7],
			8=>$tip_ar[8],9=>$tip_ar[9],10=>$tip_ar[10],11=>$tip_ar[11]
			);

			//$xyz=$xyz+array_sum($p_ar);
			//echo $xyz;
		}
		else
		{
			$trip0="wh_und";
			$trip1="wh_und";
			$trip2="wh_und";
			$trip3="wh_und";
			$trip4="wh_und";
			$trip5="wh_und";
			$trip6="wh_und";
			$trip7="wh_und";
			$trip8="wh_und";
			$trip9="wh_und";
			$trip10="wh_und";
			$trip11="wh_und";
			$qry3 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$priyr."' and rept_mo='12';";
			$res3 = mssql_query($qry3);
			$row3= mssql_fetch_array($res3);
			$nrow3= mssql_num_rows($res3);

			if ($nrow3 > 0)
			{
				$p_ar[0]=$row3['no_digs'];
			}
			else
			{
				$p_ar[0]=0;
			}

			$qry4 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='1';";
			$res4 = mssql_query($qry4);
			$row4= mssql_fetch_array($res4);
			$nrow4= mssql_num_rows($res4);

			if ($nrow4 > 0)
			{
				$p_ar[1]=$row4['no_digs'];
			}
			else
			{
				$p_ar[1]=0;
			}

			$qry5 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='2';";
			$res5 = mssql_query($qry5);
			$row5= mssql_fetch_array($res5);
			$nrow5= mssql_num_rows($res5);

			if ($nrow5 > 0)
			{
				$p_ar[2]=$row5['no_digs'];
			}
			else
			{
				$p_ar[2]=0;
			}

			$qry6 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='3';";
			$res6 = mssql_query($qry6);
			$row6= mssql_fetch_array($res6);
			$nrow6= mssql_num_rows($res6);

			if ($nrow6 > 0)
			{
				$p_ar[3]=$row6['no_digs'];
			}
			else
			{
				$p_ar[3]=0;
			}

			$qry7 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='4';";
			$res7 = mssql_query($qry7);
			$row7= mssql_fetch_array($res7);
			$nrow7= mssql_num_rows($res7);

			if ($nrow7 > 0)
			{
				$p_ar[4]=$row7['no_digs'];
			}
			else
			{
				$p_ar[4]=0;
			}

			$qry8 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='5';";
			$res8 = mssql_query($qry8);
			$row8= mssql_fetch_array($res8);
			$nrow8= mssql_num_rows($res8);

			if ($nrow8 > 0)
			{
				$p_ar[5]=$row8['no_digs'];
			}
			else
			{
				$p_ar[5]=0;
			}

			$qry9 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='6';";
			$res9 = mssql_query($qry9);
			$row9= mssql_fetch_array($res9);
			$nrow9= mssql_num_rows($res9);

			if ($nrow9 > 0)
			{
				$p_ar[6]=$row9['no_digs'];
			}
			else
			{
				$p_ar[6]=0;
			}

			$qry10 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='7';";
			$res10 = mssql_query($qry10);
			$row10= mssql_fetch_array($res10);
			$nrow10= mssql_num_rows($res10);

			if ($nrow10 > 0)
			{
				$p_ar[7]=$row10['no_digs'];
			}
			else
			{
				$p_ar[7]=0;
			}

			$qry11 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='8';";
			$res11 = mssql_query($qry11);
			$row11= mssql_fetch_array($res11);
			$nrow11= mssql_num_rows($res11);

			if ($nrow11 > 0)
			{
				$p_ar[8]=$row11['no_digs'];
			}
			else
			{
				$p_ar[8]=0;
			}

			$qry12 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='9';";
			$res12 = mssql_query($qry12);
			$row12= mssql_fetch_array($res12);
			$nrow12= mssql_num_rows($res12);

			if ($nrow12 > 0)
			{
				$p_ar[9]=$row12['no_digs'];
			}
			else
			{
				$p_ar[9]=0;
			}

			$qry13 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='10';";
			$res13 = mssql_query($qry13);
			$row13= mssql_fetch_array($res13);
			$nrow13= mssql_num_rows($res13);

			if ($nrow13 > 0)
			{
				$p_ar[10]=$row13['no_digs'];
			}
			else
			{
				$p_ar[10]=0;
			}

			$qry14 = "SELECT no_digs FROM digreport_main WHERE officeid='".$row1['officeid']."' and rept_yr='".$curryr."' and rept_mo='11';";
			$res14 = mssql_query($qry14);
			$row14= mssql_fetch_array($res14);
			$nrow14= mssql_num_rows($res14);

			if ($nrow14 > 0)
			{
				$p_ar[11]=$row14['no_digs'];
			}
			else
			{
				$p_ar[11]=0;
			}
		}

		$priytddiff=0;
		$priytdper=0;
		if ($nrow3z > 0)
		{
			$row3z= mssql_fetch_array($res3z);
			if ($row0['periodptr']==1)
			{
				$priytd=$row3z['p1'];
			}
			elseif ($row0['periodptr']==2)
			{
				$priytd=$row3z['p1']+$row3z['p2'];
			}
			elseif ($row0['periodptr']==3)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3'];
			}
			elseif ($row0['periodptr']==4)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3']+$row3z['p4'];
			}
			elseif ($row0['periodptr']==5)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3']+$row3z['p4']+$row3z['p5'];
			}
			elseif ($row0['periodptr']==6)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3']+$row3z['p4']+$row3z['p5']+$row3z['p6'];
			}
			elseif ($row0['periodptr']==7)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3']+$row3z['p4']+$row3z['p5']+$row3z['p6']+$row3z['p7'];
			}
			elseif ($row0['periodptr']==8)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3']+$row3z['p4']+$row3z['p5']+$row3z['p6']+$row3z['p7']+$row3z['p8'];
			}
			elseif ($row0['periodptr']==9)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3']+$row3z['p4']+$row3z['p5']+$row3z['p6']+$row3z['p7']+$row3z['p8']+$row3z['p9'];
			}
			elseif ($row0['periodptr']==10)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3']+$row3z['p4']+$row3z['p5']+$row3z['p6']+$row3z['p7']+$row3z['p8']+$row3z['p9']+$row3z['p10'];
			}
			elseif ($row0['periodptr']==11)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3']+$row3z['p4']+$row3z['p5']+$row3z['p6']+$row3z['p7']+$row3z['p8']+$row3z['p9']+$row3z['p10']+$row3z['p11'];
			}
			elseif ($row0['periodptr']==12)
			{
				$priytd=$row3z['p1']+$row3z['p2']+$row3z['p3']+$row3z['p4']+$row3z['p5']+$row3z['p6']+$row3z['p7']+$row3z['p8']+$row3z['p9']+$row3z['p10']+$row3z['p11']+$row3z['p12'];
			}
			else
			{
				$priytd=0;
			}

			$priytddiff=array_sum($p_ar)-$priytd;

			if ($priytd!=0)
			{
				$priytdper=round(($priytddiff/$priytd)*100);
			}
			else
			{
				$priytdper=0;
			}
		}

		echo "   				<tr>\n";
		echo " 			  		<td class=\"wh_und\" align=\"left\" valign=\"bottom\" NOWRAP><b>".$row1['name']."</b></td>\n";
		//echo " 			  		<td class=\"wh_und\" align=\"left\" valign=\"bottom\" NOWRAP><b>".$row1['officeid']."</b></td>\n";
		echo " 			  		<td class=\"".$trip0."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[0]."</td>\n";
		echo " 			  		<td class=\"".$trip1."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[1]."</td>\n";
		echo " 			  		<td class=\"".$trip2."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[2]."</td>\n";
		echo " 			  		<td class=\"".$trip3."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[3]."</td>\n";
		echo " 			  		<td class=\"".$trip4."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[4]."</td>\n";
		echo " 			  		<td class=\"".$trip5."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[5]."</td>\n";
		echo " 			  		<td class=\"".$trip6."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[6]."</td>\n";
		echo " 			  		<td class=\"".$trip7."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[7]."</td>\n";
		echo " 			  		<td class=\"".$trip8."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[8]."</td>\n";
		echo " 			  		<td class=\"".$trip9."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[9]."</td>\n";
		echo " 			  		<td class=\"".$trip10."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[10]."</td>\n";
		echo " 			  		<td class=\"".$trip11."\" align=\"center\" valign=\"bottom\" NOWRAP>".$p_ar[11]."</td>\n";
		echo " 			  		<td class=\"".$trip11."\" align=\"center\" valign=\"bottom\" NOWRAP>".array_sum($p_ar)."</td>\n";
		echo " 			  		<td class=\"".$trip11."\" align=\"center\" valign=\"bottom\" NOWRAP>".$priytd."</td>\n";
		echo " 			  		<td class=\"".$trip11."\" align=\"center\" valign=\"bottom\" NOWRAP>".$priytddiff."</td>\n";
		echo " 			  		<td class=\"".$trip11."\" align=\"right\" valign=\"bottom\" NOWRAP>".$priytdper."%</td>\n";
		echo "   				</tr>\n";

		$tp_ar	=array(
		0=>$tp_ar[0]+$p_ar[0],1=>$tp_ar[1]+$p_ar[1],2=>$tp_ar[2]+$p_ar[2],3=>$tp_ar[3]+$p_ar[3],
		4=>$tp_ar[4]+$p_ar[4],5=>$tp_ar[5]+$p_ar[5],6=>$tp_ar[6]+$p_ar[6],7=>$tp_ar[7]+$p_ar[7],
		8=>$tp_ar[8]+$p_ar[8],9=>$tp_ar[9]+$p_ar[9],10=>$tp_ar[10]+$p_ar[10],11=>$tp_ar[11]+$p_ar[11]
		);

		$oxtip_ar	=array(
		0=>$oxtip_ar[0]+$p_ar[0],1=>$oxtip_ar[1]+$p_ar[1],2=>$oxtip_ar[2]+$p_ar[2],3=>$oxtip_ar[3]+$p_ar[3],
		4=>$oxtip_ar[4]+$p_ar[4],5=>$oxtip_ar[5]+$p_ar[5],6=>$oxtip_ar[6]+$p_ar[6],7=>$oxtip_ar[7]+$p_ar[7],
		8=>$oxtip_ar[8]+$p_ar[8],9=>$oxtip_ar[9]+$p_ar[9],10=>$oxtip_ar[10]+$p_ar[10],11=>$oxtip_ar[11]+$p_ar[11]
		);

		if ($nrow2 > 0)
		{
			$xyz=$xyz+array_sum($p_ar);
		}
		else
		{
			$zyx=$zyx+array_sum($p_ar);
		}
		//echo array_sum($p_ar);
	}

	if ($_SESSION['officeid']==89)
	{
		echo "   				<tr>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP><b>".$curryr." Period Totals</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[0]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[1]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[2]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[3]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[4]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[5]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[6]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[7]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[8]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[9]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[10]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp_ar[11]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>".array_sum($tp_ar)."</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo "   				</tr>\n";
	}

	if ($_SESSION['rlev'] >= 9 && $_SESSION['officeid']==89)
	{
		$qry9 = "SELECT SUM(p1) as p1,SUM(p2) as p2,SUM(p3) as p3,SUM(p4) as p4,SUM(p5) as p5,SUM(p6) as p6,SUM(p7) as p7,SUM(p8) as p8,SUM(p9) as p9,SUM(p10) as p10,SUM(p11) as p11,SUM(p12) as p12 FROM olddigs WHERE yr='".$priyr."' ;";
		$res9 = mssql_query($qry9);
		$row9 = mssql_fetch_array($res9);
		$nrow9= mssql_num_rows($res9);

		if ($nrow9 > 0)
		{
			$tpytsum=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4']+$row9['p5']+$row9['p6']+$row9['p7']+$row9['p8']+$row9['p9']+$row9['p10']+$row9['p11']+$row9['p12'];
			echo "   				<tr>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP><b>".$priyr." Period Totals</b></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p1']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p2']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p3']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p4']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p5']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p6']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p7']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p8']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p9']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p10']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p11']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$row9['p12']."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>".$tpytsum."</b></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo "   				</tr>\n";

			$tp1d	=$tp_ar[0]-$row9['p1'];
			$tp2d	=$tp_ar[1]-$row9['p2'];
			$tp3d	=$tp_ar[2]-$row9['p3'];
			$tp4d	=$tp_ar[3]-$row9['p4'];
			$tp5d	=$tp_ar[4]-$row9['p5'];
			$tp6d	=$tp_ar[5]-$row9['p6'];
			$tp7d	=$tp_ar[6]-$row9['p7'];
			$tp8d	=$tp_ar[7]-$row9['p8'];
			$tp9d	=$tp_ar[8]-$row9['p9'];
			$tp10d	=$tp_ar[9]-$row9['p10'];
			$tp11d	=$tp_ar[10]-$row9['p11'];
			$tp12d	=$tp_ar[11]-$row9['p12'];

			$tpydsum	=$tp1d+$tp2d+$tp3d+$tp4d+$tp5d+$tp6d+$tp7d+$tp8d+$tp9d+$tp10d+$tp11d+$tp12d;

			echo "   				<tr>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP><b>Period Difference</b></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp1d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp2d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp3d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp4d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp5d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp6d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp7d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp8d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp9d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp10d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp11d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tp12d."</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo "   				</tr>\n";

			if ($tp_ar[0]!=0)
			{
				$vtp1=	round(($tp1d/$tp_ar[0])*100);
			}
			else
			{
				$vtp1=	0;
			}

			if ($tp_ar[1]!=0)
			{
				$vtp2=	round(($tp2d/$tp_ar[1])*100);
			}
			else
			{
				$vtp2=	0;
			}

			if ($tp_ar[2]!=0)
			{
				$vtp3=	round(($tp3d/$tp_ar[2])*100);
			}
			else
			{
				$vtp3=	0;
			}

			if ($tp_ar[3]!=0)
			{
				$vtp4=	round(($tp4d/$tp_ar[3])*100);
			}
			else
			{
				$vtp4=	0;
			}

			if ($tp_ar[4]!=0)
			{
				$vtp5=	round(($tp5d/$tp_ar[4])*100);
			}
			else
			{
				$vtp5=	0;
			}

			if ($tp_ar[5]!=0)
			{
				$vtp6=	round(($tp6d/$tp_ar[5])*100);
			}
			else
			{
				$vtp6=	0;
			}

			if ($tp_ar[6]!=0)
			{
				$vtp7=	round(($tp7d/$tp_ar[6])*100);
			}
			else
			{
				$vtp7=	0;
			}

			if ($tp_ar[7]!=0)
			{
				$vtp8=	round(($tp8d/$tp_ar[7])*100);
			}
			else
			{
				$vtp8=	0;
			}

			if ($tp_ar[8]!=0)
			{
				$vtp9=	round(($tp9d/$tp_ar[8])*100);
			}
			else
			{
				$vtp9=	0;
			}

			if ($tp_ar[9]!=0)
			{
				$vtp10=	round(($tp10d/$tp_ar[9])*100);
			}
			else
			{
				$vtp10=	0;
			}

			if ($tp_ar[10]!=0)
			{
				$vtp11=	round(($tp11d/$tp_ar[10])*100);
			}
			else
			{
				$vtp11=	0;
			}

			if ($tp_ar[11]!=0)
			{
				$vtp12=	round(($tp12d/$tp_ar[11])*100);
			}
			else
			{
				$vtp12=	0;
			}

			echo "   				<tr>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP><b>% Variance</b></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp1."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp2."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp3."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp4."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp5."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp6."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp7."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp8."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp9."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp10."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp11."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$vtp12."%</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
			echo "   				</tr>\n";
			echo "   				<tr>\n";
			echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" colspan=\"17\" NOWRAP></td>\n";
			echo "   				</tr>\n";
		}
	}

	$sper0	=$tp_ar[0];
	$sper1	=$tp_ar[0]+$tp_ar[1];
	$sper2	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2];
	$sper3	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2]+$tp_ar[3];
	$sper4	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2]+$tp_ar[3]+$tp_ar[4];
	$sper5	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2]+$tp_ar[3]+$tp_ar[4]+$tp_ar[5];
	$sper6	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2]+$tp_ar[3]+$tp_ar[4]+$tp_ar[5]+$tp_ar[6];
	$sper7	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2]+$tp_ar[3]+$tp_ar[4]+$tp_ar[5]+$tp_ar[6]+$tp_ar[7];
	$sper8	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2]+$tp_ar[3]+$tp_ar[4]+$tp_ar[5]+$tp_ar[6]+$tp_ar[7]+$tp_ar[8];
	$sper9	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2]+$tp_ar[3]+$tp_ar[4]+$tp_ar[5]+$tp_ar[6]+$tp_ar[7]+$tp_ar[8]+$tp_ar[9];
	$sper10	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2]+$tp_ar[3]+$tp_ar[4]+$tp_ar[5]+$tp_ar[6]+$tp_ar[7]+$tp_ar[8]+$tp_ar[9]+$tp_ar[10];
	$sper11	=$tp_ar[0]+$tp_ar[1]+$tp_ar[2]+$tp_ar[3]+$tp_ar[4]+$tp_ar[5]+$tp_ar[6]+$tp_ar[7]+$tp_ar[8]+$tp_ar[9]+$tp_ar[10]+$tp_ar[11];

	if ($_SESSION['officeid']==89)
	{
		echo "   				<tr>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP><b>2007 YTD Totals</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper0."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper1."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper2."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper3."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper4."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper5."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper6."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper7."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper8."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper9."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper10."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$sper11."</td>\n";
		//echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>".array_sum($tp_ar)." (".array_sum($xtip_ar).") (".array_sum($oxtip_ar).")</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>".array_sum($tp_ar)."</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo "   				</tr>\n";
	}

	if ($_SESSION['rlev'] >= 9 && $_SESSION['officeid']==89)
	{
		$tytd1	=$row9['p1'];
		$tytd2	=$row9['p1']+$row9['p2'];
		$tytd3	=$row9['p1']+$row9['p2']+$row9['p3'];
		$tytd4	=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4'];
		$tytd5	=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4']+$row9['p5'];
		$tytd6	=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4']+$row9['p5']+$row9['p6'];
		$tytd7	=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4']+$row9['p5']+$row9['p6']+$row9['p7'];
		$tytd8	=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4']+$row9['p5']+$row9['p6']+$row9['p7']+$row9['p8'];
		$tytd9	=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4']+$row9['p5']+$row9['p6']+$row9['p7']+$row9['p8']+$row9['p9'];
		$tytd10	=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4']+$row9['p5']+$row9['p6']+$row9['p7']+$row9['p8']+$row9['p9']+$row9['p10'];
		$tytd11	=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4']+$row9['p5']+$row9['p6']+$row9['p7']+$row9['p8']+$row9['p9']+$row9['p10']+$row9['p11'];
		$tytd12	=$row9['p1']+$row9['p2']+$row9['p3']+$row9['p4']+$row9['p5']+$row9['p6']+$row9['p7']+$row9['p8']+$row9['p9']+$row9['p10']+$row9['p11']+$row9['p12'];

		echo "   				<tr>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP><b>2006 YTD Totals</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd1."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd2."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd3."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd4."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd5."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd6."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd7."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd8."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd9."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd10."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd11."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytd12."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>".$tytd12."</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo "   				</tr>\n";

		$tytdv1=$sper0-$tytd1;
		$tytdv2=$sper1-$tytd2;
		$tytdv3=$sper2-$tytd3;
		$tytdv4=$sper3-$tytd4;
		$tytdv5=$sper4-$tytd5;
		$tytdv6=$sper5-$tytd6;
		$tytdv7=$sper6-$tytd7;
		$tytdv8=$sper7-$tytd8;
		$tytdv9=$sper8-$tytd9;
		$tytdv10=$sper9-$tytd10;
		$tytdv11=$sper10-$tytd11;
		$tytdv12=$sper11-$tytd12;

		echo "   				<tr>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP><b>YTD Difference</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv1."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv2."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv3."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv4."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv5."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv6."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv7."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv8."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv9."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv10."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv11."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdv12."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>".$tytdv12."</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo "   				</tr>\n";

		if ($sper0 != 0)
		{
			$tytdvp1	= round(($tytdv1/$sper0)*100);
		}
		else
		{
			$tytdvp1	=0;
		}

		if ($sper1 != 0)
		{
			$tytdvp2	= round(($tytdv2/$sper1)*100);
		}
		else
		{
			$tytdvp2	=0;
		}

		if ($sper2 != 0)
		{
			$tytdvp3	= round(($tytdv3/$sper2)*100);
		}
		else
		{
			$tytdvp3	=0;
		}

		if ($sper3 != 0)
		{
			$tytdvp4	= round(($tytdv4/$sper3)*100);
		}
		else
		{
			$tytdvp4	=0;
		}

		if ($sper4 != 0)
		{
			$tytdvp5	= round(($tytdv5/$sper4)*100);
		}
		else
		{
			$tytdvp5	=0;
		}

		if ($sper5 != 0)
		{
			$tytdvp6	= round(($tytdv6/$sper5)*100);
		}
		else
		{
			$tytdvp6	=0;
		}

		if ($sper6 != 0)
		{
			$tytdvp7	= round(($tytdv7/$sper6)*100);
		}
		else
		{
			$tytdvp7	=0;
		}

		if ($sper7 != 0)
		{
			$tytdvp8	= round(($tytdv8/$sper7)*100);
		}
		else
		{
			$tytdvp8	=0;
		}

		if ($sper8 != 0)
		{
			$tytdvp9	= round(($tytdv9/$sper8)*100);
		}
		else
		{
			$tytdvp9	=0;
		}

		if ($sper9 != 0)
		{
			$tytdvp10	= round(($tytdv10/$sper9)*100);
		}
		else
		{
			$tytdvp10	=0;
		}

		if ($sper10 != 0)
		{
			$tytdvp11	= round(($tytdv11/$sper10)*100);
		}
		else
		{
			$tytdvp11	=0;
		}

		if ($sper11 != 0)
		{
			$tytdvp12	= round(($tytdv12/$sper11)*100);
		}
		else
		{
			$tytdvp12	=0;
		}

		echo "   				<tr>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP><b>% Variance</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp1."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp2."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp3."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp4."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp5."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp6."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp7."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp8."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp9."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp10."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp11."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP>".$tytdvp12."%</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>".$tytdvp12."%</b></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" NOWRAP></td>\n";
		echo "   				</tr>\n";
	}

	echo "			</table>\n";
}

function fixjobinfo()
{
	$i=0;
	$tt=0;
	$j=0;
	//$accu="";
	$qry1 = "SELECT * FROM digreport_main ORDER BY added ASC;";
	$res1 = mssql_query($qry1);

	while ($row1= mssql_fetch_array($res1))
	{
		if (strlen($row1['jtext']) > 10)
		{
			$accu="";
			$jtext=explode(",",$row1['jtext']);
			foreach ($jtext as $n1 => $v1)
			{
				$inner=explode(":",$v1);
				if (isset($inner[8]))
				//if (isset($inner[10]) && $inner[10]==0)
				{
					$tt++;
					$i++;

					if (!isset($inner[16]))
					{
						$sa=0;
					}
					else
					{
						$sa=$inner[16];
					}
					//echo $inner[10]."<br>";
					//echo "OLD: ".$inner[0].":".$inner[1].":".$inner[2].":".$inner[3].":".$inner[4].":".$inner[5].":".$inner[6].":".$inner[7].":".$inner[8].":".$inner[9].":".$inner[10].":".$inner[11].":".$inner[12].":".$inner[13].":".$inner[14].":".$inner[15].",<br>";
					//echo "NEW: ".$inner[0].":".$inner[1].":".$inner[2].":".$inner[3].":".$inner[4].":".$inner[5].":".$inner[6].":".$inner[7].":".$inner[8].":".$inner[9].":".$inner[8].":".$inner[11].":".$inner[12].":".$inner[13].":".$inner[14].":".$inner[15].",<br>";

					$t=$inner[0].":".$inner[1].":".$inner[2].":".$inner[3].":".$inner[4].":".$inner[5].":".$inner[6].":".$inner[7].":".$inner[8].":".$inner[9].":".$inner[8].":".$inner[11].":".$inner[12].":".$inner[13].":".$inner[14].":".$inner[15].":".$sa.",";
					$accu=$accu.$t;
				}
			}

			//if ($tt >0)
			//{
				//echo $accu;
				$j++;
				$accu=preg_replace("/,\Z/","",$accu);
				$qry2 = "UPDATE digreport_main SET jtext='".$accu."' WHERE officeid='".$row1['officeid']."' AND id='".$row1['id']."';";
				$res2 = mssql_query($qry2);
				$tt=0;
				//echo $qry2;
				echo "<br>Updated<br>";
			//}
		}
	}

	echo "Count: ".$i." ($j)<br>";
}



function unlock_dig_report()
{
	if ($_SESSION['rlev'] < 8)
	{
		echo "You do not have appropriate Permission to perform this Function.";
		exit;
	}

	$qry0 = "SELECT * FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['rept_id']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0==1)
	{
		$qry1 = "UPDATE digreport_main SET locked='0' WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['rept_id']."';";
		$res1 = mssql_query($qry1);
	}

	dig_rep_lists();
}

function sess_build_jdata()
{
	//show_array_vars($_POST);
	$icount	=0;
	$p0		="rmo_";	//Report Month
	$p1		="ryr_";	//Report Year
	$p2		="jid_";	//Job ID
	$p3		="cln_";	//Customer Name
	$p4		="ctr_";	//Contract Total
	$p5		="ddt_";	//Dig Date
	$p6		="sid_";	//Salesman
	$p7		="add_";	//Addendum
	$p8		="all_";	//Allowance
	$p9		="adp_";	//Addendum Total
	$p10		="per_";	//Perimeter
	$p11		="sur_";	//Surface Area
	$p12		="ren_";	//Surface Area
	$b		=":";		//Data Field Separator
	$t		=",";		//Record Separator
	$out		="";

	$qry0 = "SELECT abrev FROM material_grp_codes WHERE estgrp!=0 ORDER BY abrev ASC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		while ($row0= mssql_fetch_array($res0))
		{
			$mgc_ar[]=$row0['abrev'];
		}
	}

	if (is_array($_POST))
	{
		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,4)==$p2)
			{
				$asid=substr($n,4);
				if (
				array_key_exists($p0.$asid,$_POST) &&
				array_key_exists($p1.$asid,$_POST) &&
				array_key_exists($p2.$asid,$_POST) &&
				array_key_exists($p3.$asid,$_POST) &&
				array_key_exists($p4.$asid,$_POST) &&
				array_key_exists($p5.$asid,$_POST) &&
				array_key_exists($p6.$asid,$_POST) &&
				array_key_exists($p7.$asid,$_POST) &&
				array_key_exists($p8.$asid,$_POST) &&
				array_key_exists($p9.$asid,$_POST) &&
				array_key_exists($p10.$asid,$_POST) &&
				array_key_exists($p11.$asid,$_POST) &&
				array_key_exists($p12.$asid,$_POST) &&
				strlen($_REQUEST[$p2.$asid]) >= 4
				)
				{
					$mgc_out="";

					$d=$_REQUEST[$p0.$asid].$b.$_REQUEST[$p1.$asid].$b.$_REQUEST[$p2.$asid].$b.$_REQUEST[$p3.$asid].$b.$_REQUEST[$p4.$asid].$b.$_REQUEST[$p5.$asid].$b.$_REQUEST[$p6.$asid].$b.$_REQUEST[$p7.$asid].$b.$_REQUEST[$p8.$asid].$b.$_REQUEST[$p9.$asid].$b.$_REQUEST[$p10.$asid].$b.$_REQUEST[$p11.$asid].$b.$_REQUEST[$p12.$asid].$mgc_out.$t;
					$out=$out.$d;
					//echo $d."<br>";
				}
			}
		}
	}

	$out=preg_replace("/,\Z/","",$out);
	//echo $out."<br>XXXX";
	return $out;
}

function sess_jcreate_store()
{
	$_SESSION['jcreate_sess']=sess_build_jdata();
	gen_create();
}

function sess_form_items($i,$rmo,$ryr,$jid,$add,$cln,$ctr,$adp,$all,$ddt,$sid,$mgc,$per,$sur,$ren)
{
	$x			=0;
	$rcal		=.03;
	$rcal1		=.01;
	$err		=0;
	
	if (isset($adp) && $adp!=0) {
		$adp=$adp;
	}
	else {
		$adp=0;
	}

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);

	if (strlen($cln) > 1 && $ctr >= 0)
	{		
		$proy	=calc_royalty_digreport(($ctr+$adp)+$all);
		$roy	=number_format($proy, 2, '.', '');
		$ctr	=number_format($ctr, 2, '.', '');
		$adp	=number_format($adp, 2, '.', '');
		$all	=number_format($all, 2, '.', '');
		$dig	=1;
	}
	else
	{
		$dig	=0;
		$roy	='';
	}

	// Date Testing
	if (strlen($ddt) > 999)
	{
		$f_date	=$_REQUEST['rept_mo']."/01/".$_REQUEST['rept_yr'];
		$l_date	=$_REQUEST['rept_mo']."/".date("t",strtotime($f_date))."/".$_REQUEST['rept_yr'];

		//echo $f_date."<br>";
		//echo $l_date."<br>";
		//echo $ddt."<br>";

		if ($ddt < $f_date || $ddt > $l_date)
		{
			$a	="bboxbcrit";
			$err++;
		}
		else
		{
			$a	="bboxbc";
		}
	}
	else
	{
		$a	="bboxbc";
	}

	$bboxb	="bboxb";
	$bboxbc	="bboxbc";
	$bboxbr	="bboxbright";

	if (empty($ddt) || !valid_date($ddt) || date("n",strtotime($ddt))!=$_REQUEST['rept_mo'] || date("Y",strtotime($ddt))!=$_REQUEST['rept_yr'])
	{
		$ddt=$_REQUEST['rept_mo']."/15/".$_REQUEST['rept_yr'];
	}
	
	//echo "DATE: ".$ddt."<br>";
	//echo "Year: ".$_REQUEST['rept_yr']."<br>";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"center\">\n";
	echo "						<table width=\"100%\" border=\"0\">\n";

	if ($i==1)
	{
		echo "   						<tr>\n";
		echo " 						  		<td class=\"gray_und\" align=\"center\" width=\"20px\"><b></b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Job ID</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Renov/Addn</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\" width=\"160px\"><b>Customer</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Peri</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>SA</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Dig Date</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Salesman</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Contract</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Addn</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Adden (+/-)</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Allow (+/-)</b></td>\n";
		echo " 			  					<td class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Royalty</b></td>\n";
		echo "   						</tr>\n";
	}

	echo "									<input type=\"hidden\" name=\"rmo_".$i."\" value=\"".$rmo."\">\n";
	echo "									<input type=\"hidden\" name=\"ryr_".$i."\" value=\"".$ryr."\">\n";
	echo "									<input type=\"hidden\" name=\"roy_".$i."\" value=\"".$roy."\">\n";
	echo "									<input type=\"hidden\" name=\"cid_".$i."\" value=\"".$x."\">\n";
	echo "									<input type=\"hidden\" name=\"acc_".$i."\" value=\"".$x."\">\n";
	echo "									<input type=\"hidden\" name=\"con_".$i."\" value=\"".$x."\">\n";
	echo "									<input type=\"hidden\" name=\"dct_".$i."\" value=\"".$x."\">\n";
	echo "									<input type=\"hidden\" name=\"sln_".$i."\" value=\"".$sid."\">\n";
	echo "									<input type=\"hidden\" name=\"djd_".$i."\" value=\"".$jid."\">\n";
	echo "									<input type=\"hidden\" name=\"per_".$i."\" value=\"".$x."\">\n";
	echo "									<input type=\"hidden\" name=\"sur_".$i."\" value=\"".$x."\">\n";
	echo "									<input type=\"hidden\" name=\"tgp_".$i."\" value=\"".$x."\">\n";
	echo "									<input type=\"hidden\" name=\"com_".$i."\" value=\"".$x."\">\n";
	echo "									<input type=\"hidden\" name=\"ren_".$i."\" value=\"".$ren."\">\n";
	echo "   						<tr>\n";
	echo " 			  					<td align=\"right\" width=\"20px\"><b>".$i."</b></td>\n";
	echo " 			  					<td align=\"center\"><input class=\"".$bboxbr."\" type=\"text\" name=\"jid_".$i."\" value=\"".$jid."\" size=\"5\" maxlength=\"7\"></td>\n";
	echo " 			  					<td align=\"center\">\n";
	echo "									<select name=\"ren_".$i."\">\n";
	
	
	if ($ren==2)
	{
		echo "										<option value=\"0\">New Dig</option>\n";
		echo "										<option value=\"1\">Renov</option>\n";
		echo "										<option value=\"2\" SELECTED>Addn</option>\n";
	}
	elseif ($ren==1)
	{
		echo "										<option value=\"0\">New Dig</option>\n";
		echo "										<option value=\"1\" SELECTED>Renov</option>\n";
		echo "										<option value=\"2\">Addn</option>\n";
	}
	else
	{
		echo "										<option value=\"0\" SELECTED>New Dig</option>\n";
		echo "										<option value=\"1\">Renov</option>\n";
		echo "										<option value=\"2\">Addn</option>\n";
	}
	
	echo "									</select>\n";
	echo "								</td>\n";
	echo " 			  					<td align=\"center\" width=\"160px\"><input class=\"$bboxb\" type=\"text\" name=\"cln_".$i."\" value=\"".$cln."\" size=\"20\" maxlength=\"29\"></td>\n";
	echo " 			  					<td align=\"center\"><input class=\"$bboxbr\" type=\"text\" name=\"per_".$i."\" value=\"".$per."\" size=\"2\" maxlength=\"3\"></td>\n";
	echo " 			  					<td align=\"center\"><input class=\"$bboxbc\" type=\"text\" name=\"sur_".$i."\" value=\"".$sur."\" size=\"2\" maxlength=\"3\"></td>\n";
	echo " 			  					<td align=\"center\"><input class=\"".$a."\" type=\"text\" name=\"ddt_".$i."\" value=\"".$ddt."\" size=\"10\" maxlength=\"10\"></td>\n";
	echo " 			  					<td align=\"right\">\n";
	echo "									<select name=\"sid_".$i."\">\n";

	while ($row1 = mssql_fetch_array($res1))
	{
		$slev=explode(",",$row1['slevel']);

		if ($slev[6]==0)
		{
			$ostyle="fontred";
		}
		else
		{
			$ostyle="fontblack";
		}

		if ($row1['securityid']==$sid)
		{
			echo " 			  						<option value=\"".$row1['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row1['lname'].", ".$row1['fname']."</option>\n";
		}
		else
		{
			echo " 			  						<option value=\"".$row1['securityid']."\" class=\"".$ostyle."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
		}
	}

	echo "									</select>\n";
	echo "								</td>\n";
	echo " 			  					<td align=\"center\"><input class=\"$bboxbr\" type=\"text\" name=\"ctr_".$i."\" value=\"".$ctr."\" size=\"8\" maxlength=\"9\"></td>\n";
	echo " 			  					<td align=\"center\"><input class=\"$bboxbc\" type=\"text\" name=\"add_".$i."\" value=\"".$add."\" size=\"2\" maxlength=\"2\"></td>\n";
	echo " 			  					<td align=\"center\"><input class=\"$bboxbr\" type=\"text\" name=\"adp_".$i."\" value=\"".$adp."\" size=\"8\" maxlength=\"9\"></td>\n";
	echo " 			  					<td align=\"center\"><input class=\"$bboxbr\" type=\"text\" name=\"all_".$i."\" value=\"".$all."\" size=\"8\" maxlength=\"9\"></td>\n";
	echo " 			  					<td align=\"center\" valign=\"bottom\"><input class=\"bboxgray\" type=\"text\" value=\"".$roy."\" size=\"8\" maxlength=\"9\"></td>\n";
	echo "   						</tr>\n";

	/*
	if (is_array($mgc))
	{
		$ttext	="No Descriptive";
		echo "   			<tr>\n";
		echo " 			  		<td align=\"right\" width=\"20px\"><b></b></td>\n";
		echo " 			  		<td align=\"left\" colspan=\"6\">\n";
		echo "						<table border=\"0\">\n";
		echo "   						<tr>\n";
		echo " 						  		<td align=\"center\" width=\"30px\"><b></b></td>\n";

		foreach ($mgc AS $n => $v)
		{
			$qry2 = "SELECT abrev,name FROM material_grp_codes WHERE abrev='".$n."';";
			$res2 = mssql_query($qry2);
			$nrow2= mssql_num_rows($res2);

			if ($nrow2 > 0)
			{
				$row2= mssql_fetch_array($res2);
				$ttext=$row2['name'];
			}

			if (isset($_REQUEST[$n.$i]) && $_REQUEST[$n.$i]==1)
			{
				echo " 			  				<td align=\"center\">".$n."<br><input class=\"checkboxgry\" type=\"checkbox\" name=\"".$n.$i."\" value=\"1\" title=\"".$ttext."\" CHECKED></td>\n";
			}
			else
			{
				echo " 			  				<td align=\"center\">".$n."<br><input class=\"checkboxgry\" type=\"checkbox\" name=\"".$n.$i."\" value=\"1\" title=\"".$ttext."\" ></td>\n";
			}
		}

		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
	}
	*/

	echo "						</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";

	$out=array($ctr,$adp,$all,$roy,$dig,$err,$ren);
	return $out;
}

function gen_create()
{
	//echo 'Dig Report Create Tool for Franchise Type Offices';
	$qry0p = "SELECT consfee,acctfee,pacctfee,all_code FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0p = mssql_query($qry0p);
	$row0p = mssql_fetch_array($res0p);

	$brdr		=1;
	$fcnt		=1;
	$lcnt		=5;
	$tctr		=0;
	$tadp		=0;
	$tall		=0;
	$troy		=0;
	$tdig		=0;
	$rdig		=0;
	$adig		=0;
	$tcon		=0;
	$tacc		=0;
	//$tpcc	=$row0p['pacctfee'];

	$ydate=date("Y");
	$mdate=date("m");
	$mgcar=array();

	$qry0 = "SELECT estgrp,masgrp,abrev FROM material_grp_codes WHERE estgrp!='0' AND active='1' ORDER BY estgrp ASC;";
	$res0 = mssql_query($qry0);

	while($row0= mssql_fetch_array($res0))
	{
		$mgcar[]=$row0['abrev'];
	}

	$mgcar=array_flip($mgcar);
	
	$qry0a = "SELECT * FROM bonus_schedule_config WHERE active=1;";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
	$nrow0a= mssql_num_rows($res0a);

	//echo $_REQUEST['rept_mo']."<br>";
	//echo $_REQUEST['rept_yr']."<br>";

	echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
	echo "				<input type=\"hidden\" name=\"subq\" value=\"digrpt_store_sess\">\n";
	echo "				<input type=\"hidden\" name=\"brept_yr\" value=\"".$row0a['brept_yr']."\">\n";
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" colspan=\"3\" align=\"left\" ><b>Create Dig Report</b></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	//echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Month:<b> ".$mdate." </b>Year: <b>".$ydate."</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"left\">Month\n";
	echo "						<select name=\"rept_mo\">\n";

	for ($m=01;$m <= 12;$m++)
	{
		$m=str_pad($m, 2, "0", STR_PAD_LEFT);
		if (isset($_REQUEST['rept_mo']) && $_REQUEST['rept_mo']==$m)
		{
			echo "						<option value=\"".$m."\" SELECTED>".$m."</option>\n";
			//echo "H1";
		}
		else
		{
			echo "						<option value=\"".$m."\">".$m."</option>\n";
			//echo "H3";
		}
	}

	echo "						</select>Year\n";
	echo "						<select name=\"rept_yr\">\n";

	for ($y=$ydate+1;$y >= $ydate-3;$y--)
	{

		if (isset($_REQUEST['rept_yr']) && $_REQUEST['rept_yr']==$y)
		{
			echo "						<option value=\"".$y."\" SELECTED>".$y."</option>\n";
		}
		else
		{
			echo "						<option value=\"".$y."\">".$y."</option>\n";
		}
	}

	echo "						</select>\n";
	echo "					</td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" name=\"subq1\" value=\"Preview\">\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	//echo "	<br>\n";
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";

	if (isset($_SESSION['jcreate_sess']) && strlen($_SESSION['jcreate_sess']) > 10)
	{
		$tacc		=0;
		$sess_data	=explode(",",$_SESSION['jcreate_sess']);
		$no_digs	=count($sess_data);
		$lcnt		=$lcnt+$no_digs;
		//echo "JSESS: ".$_SESSION['jcreate_sess']."<br>";

		$tccnt=0;
		$rccnt=0;
		$accnt=0;
		foreach ($sess_data AS $n => $v)
		{
			$in		=explode(":",$v);
			$pr_out		=sess_form_items($fcnt,$in[0],$in[1],$in[2],$in[7],$in[3],$in[4],$in[9],$in[8],$in[5],$in[6],$mgcar,$in[10],$in[11],$in[12]);
			$tctr		=$tctr+$pr_out[0];
			$tadp		=$tadp+$pr_out[1];
			$tall		=$tall+$pr_out[2];
			$troy		=$troy+$pr_out[3];
			
			if ($pr_out[6]==2)
			{
				//$adig		=$adig+$pr_out[6];
				$adig++;
			}
			elseif ($pr_out[6]==1)
			{
				//$rdig		=$rdig+$pr_out[6];
				$rdig++;
			}
			else
			{
				//$tdig		=$tdig+$pr_out[4];
				$tdig++;
			}
			
			$tacc		=$tacc+$row0p['pacctfee'];
			$fcnt++;
		}

		for ($j=$fcnt;$j <= $lcnt;$j++)
		{
			sess_form_items($j,$mdate,$ydate,'',0,'','','','','',0,$mgcar,0,0,0);
		}
	}
	else
	{
		for ($j=$fcnt;$j <= $lcnt;$j++)
		{
			sess_form_items($j,$mdate,$ydate,'',0,'','','','','',0,$mgcar,0,0,0);
		}
	}

	if (isset($_SESSION['jcreate_sess']))
	{
		$mtacc	=$row0p['acctfee'];
		$tacc		=$tacc+$mtacc;
		$tctr		=number_format($tctr, 2, '.', '');
		$tadp		=number_format($tadp, 2, '.', '');
		$tall		=number_format($tall, 2, '.', '');
		$troy		=number_format($troy, 2, '.', '');

		echo "									<input type=\"hidden\" name=\"rsid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "									<input type=\"hidden\" name=\"brept_yr\" value=\"".$row0a['brept_yr']."\">\n";
		echo "									<input type=\"hidden\" name=\"tdig\" value=\"".$tdig."\">\n";
		echo "									<input type=\"hidden\" name=\"rdig\" value=\"".$rdig."\">\n";
		echo "									<input type=\"hidden\" name=\"adig\" value=\"".$adig."\">\n";
		echo "									<input type=\"hidden\" name=\"tctr\" value=\"".$tctr."\">\n";
		echo "									<input type=\"hidden\" name=\"tadp\" value=\"".$tadp."\">\n";
		echo "									<input type=\"hidden\" name=\"tall\" value=\"".$tall."\">\n";
		echo "									<input type=\"hidden\" name=\"troy\" value=\"".$troy."\">\n";
		echo "									<input type=\"hidden\" name=\"tcon\" value=\"".$tcon."\">\n";
		echo "									<input type=\"hidden\" name=\"tacc\" value=\"".$tacc."\">\n";
		echo "									<input type=\"hidden\" name=\"tcos\" value=\"0.00\">\n";
		echo "									<input type=\"hidden\" name=\"mtacc\" value=\"".$mtacc."\">\n";
		echo "			<tr>\n";
		echo "				<td class=\"gray\">\n";
		echo "					<table width=\"100%\" border=\"".$brdr."\">\n";
		echo "						<tr>\n";
		echo "							<td align=\"right\">Total Digs</td>\n";
		echo " 			  				<td align=\"right\" width=\"145px\"><b>".$tdig."</b></td>\n";
		echo "   					</tr>\n";
		echo "						<tr>\n";
		echo "							<td align=\"right\">Total Renov</td>\n";
		echo " 			  				<td align=\"right\" width=\"145px\"><b>".$rdig."</b></td>\n";
		echo "   					</tr>\n";
		echo "						<tr>\n";
		echo "							<td align=\"right\">Total Addn</td>\n";
		echo " 			  				<td align=\"right\" width=\"145px\"><b>".$adig."</b></td>\n";
		echo "   					</tr>\n";
		echo "   					<tr>\n";
		echo " 			  				<td align=\"right\">Total Contracts</td>\n";
		echo " 			  				<td align=\"right\" width=\"145px\"><b>".$tctr."</b></td>\n";
		echo "   					</tr>\n";
		echo "   					<tr>\n";
		echo " 			  				<td align=\"right\">Total Addendums</td>\n";
		echo " 			  				<td align=\"right\" width=\"145px\"><b>".$tadp."</b></td>\n";
		echo "   					</tr>\n";
		echo "   					<tr>\n";
		echo " 			  				<td align=\"right\">Total Allowances</td>\n";
		echo " 			  				<td align=\"right\" width=\"145px\"><b>".$tall."</b></td>\n";
		echo "   					</tr>\n";
		echo "   					<tr>\n";
		echo " 			  				<td align=\"right\">Total Royalty</td>\n";
		echo " 			  				<td align=\"right\" width=\"145px\"><b>".$troy."</b></td>\n";
		echo "   					</tr>\n";

		if ($tacc > 0)
		{
			$ftacc		=number_format($tacc, 2, '.', '');
			echo "   					<tr>\n";
			echo " 			  				<td align=\"right\">Total Accounting</td>\n";
			echo " 			  				<td align=\"right\" width=\"145px\"><b>".$ftacc."</b></td>\n";
			echo "   					</tr>\n";
		}

		echo "   					<tr>\n";
		echo " 			  				<td align=\"right\">Validate and Save Dig Report:</td>\n";
		echo " 			  				<td align=\"right\"><input class=\"checkboxgry\" type=\"checkbox\" name=\"valdigs\" value=\"1\"><input class=\"buttondkgrypnl60\" type=\"submit\" name=\"subq1\" value=\"Save\"></td>\n";
		echo "   					</tr>\n";
		echo "					</table>\n";
		echo "   			</td>\n";
		echo "   		</tr>\n";
	}

	echo "			</table>\n";
	echo "         </form>\n";
}


function admin_build_store()
{
	//print_r($_POST);
	$icount	=0;
	$p0		="oid_"; //Office Id
	$p1		="ctr_"; //Contract Price
	$p2		="roy_"; //Royalty Fee
	$p3		="acc_"; //Accounting Fee
	$p4		="con_"; //Consulting Fee
	$p5		="cto_"; //Creator
	$p6		="dig_"; //Total Digs
	$p7		="dat_"; //Total Digs
	$b			=":"; // Data Field Separator
	$t			=","; // Record Separator
	$out		="";

	if (is_array($_POST))
	{
		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,4)==$p0)
			{
				$asid=substr($n,4);
				//echo $asid."<BR>";
				if (
				array_key_exists($p0.$asid,$_POST) &&
				array_key_exists($p1.$asid,$_POST) &&
				array_key_exists($p2.$asid,$_POST) &&
				array_key_exists($p3.$asid,$_POST) &&
				array_key_exists($p4.$asid,$_POST) &&
				array_key_exists($p5.$asid,$_POST) &&
				array_key_exists($p6.$asid,$_POST) &&
				array_key_exists($p7.$asid,$_POST)
				)
				{
					$d=$_REQUEST[$p0.$asid].$b.$_REQUEST[$p1.$asid].$b.$_REQUEST[$p2.$asid].$b.$_REQUEST[$p3.$asid].$b.$_REQUEST[$p4.$asid].$b.$_REQUEST[$p5.$asid].$b.$_REQUEST[$p6.$asid].$b.$_REQUEST[$p7.$asid].$t;
					$out=$out.$d;
					//echo $d."<br>";
				}
				//else
				//{
				//	echo "FALSE<br>";
				//}
			}
		}
	}

	$out=preg_replace("/,\Z/","",$out);
	//echo $out."<br>";
	return $out;
}

function build_store()
{
	$icount	=0;
	$p0		="jid_"; //Jobid
	$p1		="cid_"; //Customer ID
	$p2		="ctr_"; //Contract Price
	$p3		="roy_"; //Royalty
	$p4		="acc_"; //Accounting Fee
	$p5		="con_"; //Consulting Fee
	$p6		="ddt_"; //
	$p7		="dct_"; //
	$p8		="sid_"; //Salesman
	$p9		="cln_"; //Customer Last Name
	$p10		="sln_"; //Salesman Last Name
	$p11		="add_"; //Addendum Cnt
	$p12		="djd_"; //Real Job ID
	$p13		="all_"; //Allowance
	$p14		="adp_"; //Addendum Total
	$p15		="per_"; //Perimeter Foot
	$p16		="sur_"; //Surface Area
	$p17		="com_"; //Commission Total
	$p18		="tgp_"; //GPTotal
	$p19		="ren_"; //Renovation/Adden Flag
	$mgc		=""; 	//Material Group Codes Place Holder
	$b			=":"; // Data Field Separator
	$ib		="|"; // Material Group Item Boundery
	$t			=","; // Record Separator
	$out		="";

	//Item Group Codes for Franchise Offices - temp until I can figure out looping
	$ic0		="CLEA";
	$ic1		="2SPC";
	$ic2		="2SPP";
	$ic3		="FOPL";
	$ic4		="LEDH";
	$ic5		="JAZZ";
	$ic6		="OZON";
	$ic7		="PS8G";
	$ic8		="SP3H";
	$ic9		="FS6H";
	$ic10		="SRSM";
	$ic11		="WALL";
	$ic12		="FILT";
	$ic13		="HEAT";
	$ic14		="GENE";

	if (is_array($_POST))
	{
		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,4)==$p0)
			{
				$asid=substr($n,4);

				if (
				array_key_exists($p1.$asid,$_POST) &&
				array_key_exists($p2.$asid,$_POST) &&
				array_key_exists($p3.$asid,$_POST) &&
				array_key_exists($p4.$asid,$_POST) &&
				array_key_exists($p5.$asid,$_POST) &&
				array_key_exists($p6.$asid,$_POST) &&
				array_key_exists($p7.$asid,$_POST) &&
				array_key_exists($p8.$asid,$_POST) &&
				array_key_exists($p9.$asid,$_POST) &&
				array_key_exists($p10.$asid,$_POST)&&
				array_key_exists($p11.$asid,$_POST)&&
				array_key_exists($p12.$asid,$_POST)&&
				array_key_exists($p13.$asid,$_POST)&&
				array_key_exists($p14.$asid,$_POST)&&
				array_key_exists($p15.$asid,$_POST)&&
				array_key_exists($p16.$asid,$_POST)&&
				array_key_exists($p17.$asid,$_POST)&&
				array_key_exists($p18.$asid,$_POST)&&
				array_key_exists($p19.$asid,$_POST)&&
				strlen($_REQUEST[$p0.$asid]) >= 4
				)
				{
					if (array_key_exists($ic0.$asid,$_POST) && $_REQUEST[$ic0.$asid]==1)
					{
						$c=$ic0.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic1.$asid,$_POST) && $_REQUEST[$ic1.$asid]==1)
					{
						$c=$ic1.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic2.$asid,$_POST) && $_REQUEST[$ic2.$asid]==1)
					{
						$c=$ic2.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic3.$asid,$_POST) && $_REQUEST[$ic3.$asid]==1)
					{
						$c=$ic3.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic4.$asid,$_POST) && $_REQUEST[$ic4.$asid]==1)
					{
						$c=$ic4.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic5.$asid,$_POST) && $_REQUEST[$ic5.$asid]==1)
					{
						$c=$ic5.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic6.$asid,$_POST) && $_REQUEST[$ic6.$asid]==1)
					{
						$c=$ic6.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic7.$asid,$_POST) && $_REQUEST[$ic7.$asid]==1)
					{
						$c=$ic7.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic8.$asid,$_POST) && $_REQUEST[$ic8.$asid]==1)
					{
						$c=$ic8.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic9.$asid,$_POST) && $_REQUEST[$ic9.$asid]==1)
					{
						$c=$ic9.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic10.$asid,$_POST) && $_REQUEST[$ic10.$asid]==1)
					{
						$c=$ic10.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic11.$asid,$_POST) && $_REQUEST[$ic11.$asid]==1)
					{
						$c=$ic11.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic12.$asid,$_POST) && $_REQUEST[$ic12.$asid]==1)
					{
						$c=$ic12.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic13.$asid,$_POST) && $_REQUEST[$ic13.$asid]==1)
					{
						$c=$ic13.$ib;
						$mgc=$mgc.$c;
					}

					if (array_key_exists($ic14.$asid,$_POST) && $_REQUEST[$ic14.$asid]==1)
					{
						$c=$ic14.$ib;
						$mgc=$mgc.$c;
					}

					$d=$_REQUEST[$p0.$asid].$b.$_REQUEST[$p1.$asid].$b.$_REQUEST[$p2.$asid].$b.$_REQUEST[$p3.$asid].$b.$_REQUEST[$p4.$asid].$b.$_REQUEST[$p5.$asid].$b.$_REQUEST[$p6.$asid].$b.$_REQUEST[$p7.$asid].$b.$_REQUEST[$p8.$asid].$b.removequote($_REQUEST[$p9.$asid]).$b.$_REQUEST[$p10.$asid].$b.$_REQUEST[$p11.$asid].$b.$_REQUEST[$p12.$asid].$b.$_REQUEST[$p13.$asid].$b.$_REQUEST[$p14.$asid].$b.$_REQUEST[$p15.$asid].$b.$_REQUEST[$p16.$asid].$b.$mgc.$b.$_REQUEST[$p17.$asid].$b.$_REQUEST[$p18.$asid].$b.$_REQUEST[$p19.$asid].$t;
					//echo $d."<br>";
					$mgc="";
					$out=$out.$d;
				}
			}
		}
	}
	//}

	$out=preg_replace("/,\Z/","",$out);
	return $out;
}

function getpcodesold()
{
	$ex_arr		=array(0=>270);
	$actoff_ar	=array();
	
	$qry0a = "SELECT DISTINCT(parentmcode),name FROM offices WHERE active='1' and endigreport!='2' ORDER BY parentmcode ASC;";
	$res0a = mssql_query($qry0a);

	while ($row0a = mssql_fetch_array($res0a))
	{
		//echo $row0a['parentmcode']."<br>";
		if (is_numeric($row0a['parentmcode']) && !in_array($row0a['parentmcode'],$actoff_ar))
		{
			if (!in_array($row0a['parentmcode'],$ex_arr))
			{
				$actoff_ar[]=$row0a['parentmcode'];
			}
		}
	}
	
	return $actoff_ar;
}

function getpcodes()
{
	$ex_arr		=array('098','099','010','020','050','080',240,282,311,399,420,540,570,610,620,630,660,700,730,750,790,801,810,820,830,840,850,860,870,880,890,900,910,920,931,940,950,990,999);
	//$ex_arr		=array();
	$actoff_ar	=array();
	
	$odbc_ser	=	"192.168.1.22"; #the name of the SQL Server
	$odbc_add	=	"192.168.1.22";
	$odbc_db	=	"MAS_SYSTEM"; #the name of the database
	$odbc_user	=	"MAS_Reports"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username
	
	$odbc_conn0	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry0  = "SELECT DISTINCT(company) FROM ZE_Stats..divtocomp ORDER BY company ASC;";	
	$odbc_res0	 = odbc_exec($odbc_conn0, $odbc_qry0);
			
	//echo $odbc_qry0;
	while (odbc_fetch_row($odbc_res0))
	{
		$intemp1=odbc_result($odbc_res0, 1);
		if (!in_array($intemp1,$ex_arr) && !in_array($intemp1,$actoff_ar))
		{
			//$actoff_ar[] = array($intemp1,$intemp2);
			$actoff_ar[] = $intemp1;
		}
	}
	
	//print_r($actoff_ar);
	//echo count($actoff_ar)."<br>";
	return $actoff_ar;
}

function getoffdata($c,$n)
{
	$out=array();
	$qry1 = "SELECT officeid,name,encon,code,parentmcode FROM offices WHERE parentmcode='".$c."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	
	//echo $qry1."<br>";
	
	if ($nrow1 >= 1)
	{
		if ($row1['encon']==1)
		{
			$out=array($row1['name'],$row1['parentmcode'],$row1['encon'],"",0);
		}
		else
		{
			$out=array($row1['name'],$row1['parentmcode'],$row1['encon'],"<font color=\"red\">Contracts Not Enabled</font>",1);
		}
	}
	else
	{
		//$out=array("<font title=\"Contact System Support\">Setup not Complete</font>",0,0,"<font color=\"red\">JMS Setup Required</font>",1);
		if ($c=='330')
		{
			$out=array('330 - Inland Empire',330,1,"",0);
		}
		else
		{
			$out=array("<font title=\"Contact System Support\">Setup not Complete</font>",0,0,"<font color=\"red\">JMS Setup Required</font>",1);
		}
	}
	
	return $out;
}

function admin_digrpt_pub()
{
	error_reporting(E_ALL);
	$brdr				=1;
	$actoff_ar		=array();
	$enaoff_ar		=array();
	$rptoff_ar		=array();
	$op_ar			=array();
	$ex_arr			=array(270,98,99);
	$cspan			=7;
	$mrid			=md5($_SESSION['securityid'].":".time());
	
	$qry0 = "SELECT oroid FROM recognized_digs WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_num_rows($res0);
	
	
	if ($row0 > 0)
	{
		echo "Report already exists for ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']."\n";
		//exit;
	}
	
	$actoff_ar=getpcodes();

	echo "         		<form name=\"offselect\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
	echo "						<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub\">\n";
	echo "						<input type=\"hidden\" name=\"publish\" value=\"1\">\n";
	echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$mrid."\">\n";
	echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$_REQUEST['rept_mo']."\">\n";
	echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$_REQUEST['rept_yr']."\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" colspan=\"5\" align=\"left\" valign=\"bottom\"><b>Recognized Dig Report: Period Dates: ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']."</td>\n";
	echo " 			  		<td class=\"gray\" align=\"center\" valign=\"bottom\">\n";
	echo "						<input type=\"button\" onclick=\"SetAllCheckBoxes('offselect', 'ex_off[]', false);\" value=\"None\">\n";
	echo "					</td>\n";
	echo " 			  		<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	
	if (isset($_REQUEST['useprevfy']) && $_REQUEST['useprevfy']==1)
	{
		echo 'Using Prev FY Periods';	
	}
	
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Office</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>JMS</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>MAS</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>Period</b></td>\n";
	//echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>Include</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
	echo "						<input type=\"button\" onclick=\"SetAllCheckBoxes('offselect', 'ex_off[]', true);\" value=\"Include\">\n";
	echo "					</td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>Status</b></td>\n";
	echo "   			</tr>\n";

	$tcpy	=0;
	foreach ($actoff_ar AS $n => $v)
	{
		$tcpy++;
		
		$odata=getoffdata($v,$n);
		
		echo "   			<tr>\n";
		echo " 			  		<td class=\"wh_und\" align=\"right\" valign=\"bottom\">".$tcpy.".</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$odata[0]."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\">\n";
			
		if ($odata[2]=='1')
		{
			echo str_pad($odata[1],3,"0",STR_PAD_LEFT);
		}
			
		echo "					</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\">\n";
			
		//if ($odata[2]=='1')
		if (!empty($v))
		{
			//echo str_pad($odata[1],3,"0",STR_PAD_LEFT);
			echo str_pad($v,3,"0",STR_PAD_LEFT);
		}
			
		echo "					</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\" valign=\"bottom\">\n";
			
		//if ($odata[2]=='1')
		//{
			//getmasperiods($odata[1],$_REQUEST['rept_mo'],$_REQUEST['rept_yr']);
		getmasperiods($v,$_REQUEST['rept_mo'],$_REQUEST['rept_yr']);
		//}
			
		echo "					</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
			
		if ($odata[4]==0)
		{
			echo "<input class=\"checkboxwh\" type=\"checkbox\" name=\"ex_off[]\" value=\"".$odata[1]."\" title=\"Check box to include this Company in the Recognized Dig Storage\" CHECKED>";	
		}
		else
		{
			echo "<input class=\"checkboxwh\" type=\"checkbox\" name=\"ex_off[]\" value=\"".$odata[1]."\" title=\"Check box to include this Company in the Recognized Dig Storage\">";
		}
		
		echo "					</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			
		echo $odata[3];
			
		echo "					</td>\n";
		echo "   			</tr>\n";
	}

	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Preview\"></td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "			</form>\n";
}

function admin_digrpt_view()
{
	$brdr			=1;
	$cspan			=11;
	$actoff_ar		=array();
	$enaoff_ar		=array();
	$rptoff_ar		=array();
	
	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		$cspan			=14;
	}

	$qry0a = "SELECT officeid FROM offices WHERE active='1' and endigreport!='2' ORDER BY name;";
	//$qry0a = "SELECT officeid FROM offices WHERE [grouping]=0 and endigreport!='2' ORDER BY name;";
	$res0a = mssql_query($qry0a);

	while ($row0a = mssql_fetch_array($res0a))
	{
		$actoff_ar[]=$row0a['officeid'];
	}

	$qry0b = "SELECT officeid FROM offices WHERE endigreport='1';";
	$res0b = mssql_query($qry0b);

	while ($row0b = mssql_fetch_array($res0b))
	{
		$enaoff_ar[]=$row0b['officeid'];
	}

	$qry = "SELECT * FROM digreport_admin WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry0c = "SELECT lname FROM security WHERE securityid='".$row['creator']."';";
	$res0c = mssql_query($qry0c);
	$row0c = mssql_fetch_array($res0c);

	$dis	="";
	$j_ids	=array();
	$tpdigs	=0;
	$f_date	=$row['rept_mo']."/01/".$row['rept_yr'];

	$adminrptadd	=$row['added'];
	//$arptadd		=strtotime($adminrptadd);

	$jcnt=0;
	$jcon=explode(",",$row['jtext']);

	foreach ($jcon AS $n => $v)
	{
		$ijrpt=explode(":",$v);
		$rptoff_ar[]=$ijrpt[0];
	}

	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
		echo "						<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub\">\n";
		//echo "						<input type=\"hidden\" name=\"print\" 	value=\"1\">\n";
		echo "						<input type=\"hidden\" name=\"publish\" 	value=\"1\">\n";
		echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$_REQUEST['rept_id']."\">\n";
		echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$_REQUEST['rept_mo']."\">\n";
		echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$_REQUEST['rept_yr']."\">\n";
	}
		
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"><b>Archived Dig Report: </b> ".date("F",strtotime($f_date))." ".$row['rept_yr']."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Office</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Type</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Digs</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Contracts</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Royalty</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Acct Fee</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Consult Fee</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Creator</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Date Created</b></td>\n";
	
	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>Publish Period</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>Reco Digs</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>MAS</b></td>\n";
	}
	
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"60\" NOWRAP><b>Errors</b></td>\n";
	echo "   			</tr>\n";

	$tdigs	=0;
	foreach ($actoff_ar AS $n => $v)
	{
		$jcnt++;

		//Constants
		$digs	=0;
		$tdcolor="gray_und";
		$clname	="";
		$digs	="";
		$camt	="";
		$fcamt	="";
		$froy	="";
		$facc	="";
		$fcon	="";
		$cdate	="";
		$joid	="";
		$otype	="";
		//

		$qry1 = "SELECT officeid,name,encon,code,parentmcode FROM offices WHERE officeid='".$v."';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		$oname=$row1['name'];
		
		if ($row1['encon']!='1')
		{
			$otype="F";
		}

		//Enabled Offices
		if (in_array($v,$enaoff_ar))
		{
			//Offices with Reports
			if (in_array($v,$rptoff_ar))
			{
				foreach ($jcon AS $n1 => $v1)
				{
					$ijtext1=explode(":",$v1);
					if ($v==$ijtext1[0])
					{
						//echo $ijtext1[0]."<br>";
						$qry2 	= "SELECT securityid,lname FROM security WHERE securityid='".$ijtext1[5]."';";
						$res2 	= mssql_query($qry2);
						$row2 	= mssql_fetch_array($res2);
						$clname	= $row2['lname'];

						$qry3 	= "SELECT * FROM digreport_main WHERE officeid='".$ijtext1[0]."' AND rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
						$res3 	= mssql_query($qry3);
						$row3 	= mssql_fetch_array($res3);
						$nrow3 	= mssql_num_rows($res3);

						$joid		=$ijtext1[0];
						//$tag	="YS1";
						$tag		="";
						$etag		="";
						$digs		=$ijtext1[6];
						$camt		=number_format($ijtext1[2], 2, '.', '');
						$fcamt	=number_format($ijtext1[1], 2, '.', '');
						$froy		=number_format($ijtext1[2], 2, '.', '');
						$facc		=number_format($ijtext1[3], 2, '.', '');
						$fcon		=number_format($ijtext1[4], 2, '.', '');
						$cdate	=date("m/d/Y", strtotime($ijtext1[7]));

						if ($nrow3 > 0)
						{

							if ($ijtext1[6]!= $row3['no_digs'])
							{
								$ddiff=$row3['no_digs']-$ijtext1[6];
								//echo "hit";
								$tdcolor	="yel_und";
								$etag		="Dig Diff: ".$ddiff;
								$tag		="<font color=\"red\">!</font>";
							}
							elseif (strtotime($row3['added']) > strtotime($adminrptadd))
							{
								//echo "hit";
								$tdcolor	="yel_und";
								$etag		="Date Diff: ".date("m/d/Y", strtotime($row3['added']));
								$tag		="<font color=\"red\">!</font>";
							}
							else
							{
								$tdcolor	="wh_und";
							}
						}
						else
						{
							//echo "<br>No Report";
							//$arptadd	=strtotime($ijtext1[7]);
							$etag		="Deleted";
							$tag		="<font color=\"red\">!</font>";
							$tdcolor	="yel_und";
						}
					}
				}
			}
			else
			{
				$etag		="Missing";
				$tag		="<font color=\"red\">!</font>";
				$tdcolor	="yel_und";
			}
		}
		else
		{
			if (in_array($v,$rptoff_ar))
			{
				foreach ($jcon AS $n2 => $v2)
				{
					$ijtext2=explode(":",$v2);
					if ($v==$ijtext2[0])
					{
						$qry2 	= "SELECT securityid,lname FROM security WHERE securityid='".$ijtext2[5]."';";
						$res2 	= mssql_query($qry2);
						$row2 	= mssql_fetch_array($res2);
						$clname	=$row2['lname'];

						$joid		=$ijtext2[0];
						$tag		="YS2";
						$digs		=$ijtext2[6];
						$camt		=number_format($ijtext2[2], 2, '.', '');
						$fcamt	=number_format($ijtext2[1], 2, '.', '');
						$froy		=number_format($ijtext2[2], 2, '.', '');
						$facc		=number_format($ijtext2[3], 2, '.', '');
						$fcon		=number_format($ijtext2[4], 2, '.', '');
						$cdate	=date("m/d/Y", strtotime($ijtext2[7]));
						$tdcolor	="gray_und";
					}
					else
					{
						//$tag		="NO2";
						$tag		="";
						$tdcolor="gray_und";
					}
				}
			}
			else
			{
				//$tag		="NO3";
				$tag		="";
				$tdcolor="gray_und";
			}
		}

		echo "   			<tr>\n";
		//echo " 			  		<td class=\"".$tdcolor."\" align=\"left\" valign=\"bottom\">(".$tag.") ($v) (".$joid.") ".$oname."</td>\n";
		echo " 			  		<td class=\"".$tdcolor."\" align=\"center\" valign=\"bottom\"><b>".$tag."</b></td>\n";
		echo " 			  		<td class=\"".$tdcolor."\" align=\"left\" valign=\"bottom\">".$oname."</td>\n";
		echo " 			  		<td align=\"center\" valign=\"bottom\">".$otype."</td>\n";
		echo " 			  		<td align=\"center\" valign=\"bottom\">".$digs."</td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\">".$fcamt."</td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\">".$froy."</td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\">".$facc."</td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\">".$fcon."</td>\n";
		echo " 			  		<td align=\"left\" valign=\"bottom\">".$clname."</td>\n";
		echo " 			  		<td align=\"center\" valign=\"bottom\">".$cdate."</td>\n";
		
		if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
		{
			echo "					<input type=\"hidden\" name=\"roid".$v."\" value=\"".$v."\">\n";
			echo "					<input type=\"hidden\" name=\"rdig".$v."\" value=\"".$digs."\">\n";
			echo " 			  		<td align=\"center\" valign=\"bottom\">\n";
			
			if ($row1['encon']=='1' && $digs!=0)
			{
				$ptag=getmasperiods($v,$row1['parentmcode'],$_REQUEST['rept_mo']);
			}
			
			echo "					</td>\n";
			echo " 			  		<td align=\"center\" valign=\"bottom\">\n";
			
			if ($row1['encon']=='1')
			{
				$pdigs	=getrecodigs($v,$_REQUEST['rept_id'],0);
				$tpdigs	=$tpdigs+$pdigs;
				echo $pdigs;
			}
			
			echo "					</td>\n";
			echo " 			  		<td align=\"center\" valign=\"bottom\">\n";
			
			if ($row1['encon']=='1' && $digs!=0)
			{
				echo str_pad($row1['parentmcode'],3,"0",STR_PAD_LEFT);
			}
			
			echo "					</td>\n";
		}
		
		echo " 			  		<td align=\"left\" valign=\"bottom\">".$etag."</td>\n";
		echo "   			</tr>\n";
		$tdigs=$tdigs+$digs;
		//$tcon=$tcon+$tctramt;
		//$rcon=$rcon+$roy;
		//$acon=$acon+$acc;
		//$ccon=$ccon+$consf;
	}

	$fttcon	=number_format($row['cont_total'], 2, '.', ',');
	$ftrcon	=number_format($row['admin_fee'], 2, '.', ',');
	$ftacon	=number_format($row['acct_fee'], 2, '.', ',');
	$ftccon	=number_format($row['cons_fee'], 2, '.', ',');

	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"><b>Totals</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b>".$tdigs."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$fttcon."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$ftrcon."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$ftacon."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$ftccon."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b>".$row0c['lname']."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\">".date("m/d/Y", strtotime($adminrptadd))."</td>\n";
	
	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		if (isset($pdigs) && $pdigs > 0)
		{
			echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Preview\" DISABLED></td>\n";
		}
		else
		{
			echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Preview\"></td>\n";
		}
		
		echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b>".$tpdigs."</b></td>\n";
		echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"></td>\n";
	}
	
	echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"></td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	
	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		echo "			</form>\n";
	}
}

function admin_digrpt_hist()
{
	$brdr			=1;
	$cspan			=11;
	$actoff_ar		=array();
	$enaoff_ar		=array();
	$rptoff_ar		=array();
	
	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		$cspan			=14;
	}

	//$qry0a = "SELECT officeid FROM offices WHERE active='1' and endigreport!='2' ORDER BY name;";
	$qry0a = "SELECT officeid FROM offices WHERE [grouping]=0 ORDER BY name;";
	$res0a = mssql_query($qry0a);

	while ($row0a = mssql_fetch_array($res0a))
	{
		$actoff_ar[]=$row0a['officeid'];
	}

	$qry0b = "SELECT officeid FROM offices WHERE endigreport='1';";
	$res0b = mssql_query($qry0b);

	while ($row0b = mssql_fetch_array($res0b))
	{
		$enaoff_ar[]=$row0b['officeid'];
	}

	$qry = "SELECT * FROM digreport_admin WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry0c = "SELECT lname FROM security WHERE securityid='".$row['creator']."';";
	$res0c = mssql_query($qry0c);
	$row0c = mssql_fetch_array($res0c);

	$dis	="";
	$j_ids	=array();
	$tpdigs	=0;
	$f_date	=$row['rept_mo']."/01/".$row['rept_yr'];

	$adminrptadd	=$row['added'];
	//$arptadd		=strtotime($adminrptadd);

	$jcnt=0;
	$jcon=explode(",",$row['jtext']);

	foreach ($jcon AS $n => $v)
	{
		$ijrpt=explode(":",$v);
		$rptoff_ar[]=$ijrpt[0];
	}

	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
		echo "						<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_pub\">\n";
		echo "						<input type=\"hidden\" name=\"publish\" value=\"1\">\n";
		echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$_REQUEST['rept_id']."\">\n";
		echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$_REQUEST['rept_mo']."\">\n";
		echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$_REQUEST['rept_yr']."\">\n";
	}
		
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"><b>Historical Dig Report: </b> ".date("F",strtotime($f_date))." ".$row['rept_yr']."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Office</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Type</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Digs</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Contracts</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Royalty</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Acct Fee</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Consult Fee</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Creator</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Date Created</b></td>\n";
	
	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>Publish Period</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>Reco Digs</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"80\" NOWRAP><b>MAS</b></td>\n";
	}
	
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"60\" NOWRAP><b>Errors</b></td>\n";
	echo "   			</tr>\n";

	$tdigs	=0;
	foreach ($actoff_ar AS $n => $v)
	{
		$jcnt++;

		//Constants
		$digs		=0;
		$tdcolor	="gray_und";
		$clname	="";
		$digs		="";
		$camt		="";
		$fcamt	="";
		$froy		="";
		$facc		="";
		$fcon		="";
		$cdate	="";
		$joid		="";
		$otype	="";
		//

		$qry1 = "SELECT officeid,name,encon,code,parentmcode FROM offices WHERE officeid='".$v."';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		$oname=$row1['name'];
		
		if ($row1['encon']!='1')
		{
			$otype="F";
		}

		//Enabled Offices
		if (in_array($v,$enaoff_ar))
		{
			//Offices with Reports
			if (in_array($v,$rptoff_ar))
			{
				foreach ($jcon AS $n1 => $v1)
				{
					$ijtext1=explode(":",$v1);
					if ($v==$ijtext1[0])
					{
						//echo $ijtext1[0]."<br>";
						$qry2 	= "SELECT securityid,lname FROM security WHERE securityid='".$ijtext1[5]."';";
						$res2 	= mssql_query($qry2);
						$row2 	= mssql_fetch_array($res2);
						$clname	= $row2['lname'];

						$qry3 	= "SELECT * FROM digreport_main WHERE officeid='".$ijtext1[0]."' AND rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
						$res3 	= mssql_query($qry3);
						$row3 	= mssql_fetch_array($res3);
						$nrow3 	= mssql_num_rows($res3);

						$joid		=$ijtext1[0];
						//$tag	="YS1";
						$tag		="";
						$etag		="";
						$digs		=$ijtext1[6];
						$camt		=number_format($ijtext1[2], 2, '.', '');
						$fcamt	=number_format($ijtext1[1], 2, '.', '');
						$froy		=number_format($ijtext1[2], 2, '.', '');
						$facc		=number_format($ijtext1[3], 2, '.', '');
						$fcon		=number_format($ijtext1[4], 2, '.', '');
						$cdate	=date("m/d/Y", strtotime($ijtext1[7]));

						if ($nrow3 > 0)
						{

							if ($ijtext1[6]!= $row3['no_digs'])
							{
								$ddiff=$row3['no_digs']-$ijtext1[6];
								//echo "hit";
								$tdcolor	="yel_und";
								$etag		="Dig Diff: ".$ddiff;
								$tag		="<font color=\"red\">!</font>";
							}
							elseif (strtotime($row3['added']) > strtotime($adminrptadd))
							{
								//echo "hit";
								$tdcolor	="yel_und";
								$etag		="Date Diff: ".date("m/d/Y", strtotime($row3['added']));
								$tag		="<font color=\"red\">!</font>";
							}
							else
							{
								$tdcolor	="wh_und";
							}
						}
						else
						{
							//echo "<br>No Report";
							//$arptadd	=strtotime($ijtext1[7]);
							$etag		="Deleted";
							$tag		="<font color=\"red\">!</font>";
							$tdcolor	="yel_und";
						}
					}
				}
			}
			else
			{
				$etag		="Missing";
				$tag		="<font color=\"red\">!</font>";
				$tdcolor	="yel_und";
			}
		}
		else
		{
			if (in_array($v,$rptoff_ar))
			{
				foreach ($jcon AS $n2 => $v2)
				{
					$ijtext2=explode(":",$v2);
					if ($v==$ijtext2[0])
					{
						$qry2 	= "SELECT securityid,lname FROM security WHERE securityid='".$ijtext2[5]."';";
						$res2 	= mssql_query($qry2);
						$row2 	= mssql_fetch_array($res2);
						$clname	=$row2['lname'];

						$joid		=$ijtext2[0];
						$tag		="YS2";
						$digs		=$ijtext2[6];
						$camt		=number_format($ijtext2[2], 2, '.', '');
						$fcamt	=number_format($ijtext2[1], 2, '.', '');
						$froy		=number_format($ijtext2[2], 2, '.', '');
						$facc		=number_format($ijtext2[3], 2, '.', '');
						$fcon		=number_format($ijtext2[4], 2, '.', '');
						$cdate	=date("m/d/Y", strtotime($ijtext2[7]));
						$tdcolor	="gray_und";
					}
					else
					{
						//$tag		="NO2";
						$tag		="";
						$tdcolor="gray_und";
					}
				}
			}
			else
			{
				//$tag		="NO3";
				$tag		="";
				$tdcolor="gray_und";
			}
		}

		echo "   			<tr>\n";
		//echo " 			  		<td class=\"".$tdcolor."\" align=\"left\" valign=\"bottom\">(".$tag.") ($v) (".$joid.") ".$oname."</td>\n";
		echo " 			  		<td class=\"".$tdcolor."\" align=\"center\" valign=\"bottom\"><b>".$tag."</b></td>\n";
		echo " 			  		<td class=\"".$tdcolor."\" align=\"left\" valign=\"bottom\">".$oname."</td>\n";
		echo " 			  		<td align=\"center\" valign=\"bottom\">".$otype."</td>\n";
		echo " 			  		<td align=\"center\" valign=\"bottom\">".$digs."</td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\">".$fcamt."</td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\">".$froy."</td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\">".$facc."</td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\">".$fcon."</td>\n";
		echo " 			  		<td align=\"left\" valign=\"bottom\">".$clname."</td>\n";
		echo " 			  		<td align=\"center\" valign=\"bottom\">".$cdate."</td>\n";
		
		if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
		{
			echo "					<input type=\"hidden\" name=\"roid".$v."\" value=\"".$v."\">\n";
			echo "					<input type=\"hidden\" name=\"rdig".$v."\" value=\"".$digs."\">\n";
			echo " 			  		<td align=\"center\" valign=\"bottom\">\n";
			
			if ($row1['encon']=='1' && $digs!=0)
			{
				$ptag=getmasperiods($v,$row1['parentmcode'],$_REQUEST['rept_mo']);
			}
			
			echo "					</td>\n";
			echo " 			  		<td align=\"center\" valign=\"bottom\">\n";
			
			if ($row1['encon']=='1')
			{
				$pdigs	=getrecodigs($v,$_REQUEST['rept_id'],0);
				$tpdigs	=$tpdigs+$pdigs;
				echo $pdigs;
			}
			
			echo "					</td>\n";
			echo " 			  		<td align=\"center\" valign=\"bottom\">\n";
			
			if ($row1['encon']=='1' && $digs!=0)
			{
				echo str_pad($row1['parentmcode'],3,"0",STR_PAD_LEFT);
			}
			
			echo "					</td>\n";
		}
		
		echo " 			  		<td align=\"left\" valign=\"bottom\">".$etag."</td>\n";
		echo "   			</tr>\n";
		$tdigs=$tdigs+$digs;
		//$tcon=$tcon+$tctramt;
		//$rcon=$rcon+$roy;
		//$acon=$acon+$acc;
		//$ccon=$ccon+$consf;
	}

	$fttcon	=number_format($row['cont_total'], 2, '.', ',');
	$ftrcon	=number_format($row['admin_fee'], 2, '.', ',');
	$ftacon	=number_format($row['acct_fee'], 2, '.', ',');
	$ftccon	=number_format($row['cons_fee'], 2, '.', ',');

	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"><b>Totals</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b>".$tdigs."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$fttcon."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$ftrcon."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$ftacon."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$ftccon."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b>".$row0c['lname']."</b></td>\n";
	echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\">".date("m/d/Y", strtotime($adminrptadd))."</td>\n";
	
	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		if (isset($pdigs) && $pdigs > 0)
		{
			echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Preview\" DISABLED></td>\n";
		}
		else
		{
			echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Preview\"></td>\n";
		}
		
		echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b>".$tpdigs."</b></td>\n";
		echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"></td>\n";
	}
	
	echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"></td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	
	if (!empty($_REQUEST['publish']) && $_REQUEST['publish'] >= 1)
	{
		echo "			</form>\n";
	}
}

function equip_search1()
{
	$brdr	=0;
	
	$qry1 = "SELECT gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	if ($row1['gmreports']!=1)
	{
		echo "You do not have appropriate access for this Report";
		exit;
	}
	
	$qry1 = "SELECT officeid,name FROM offices WHERE active='1' and endigreport!='2' ORDER BY name;";
	$res1 = mssql_query($qry1);
	
	$qry2 = "SELECT vid,name FROM vendors WHERE vid!='0' and etrack='1' ORDER BY name ASC;";
	$res2 = mssql_query($qry2);
	
	$qry3 = "SELECT DISTINCT(masgrp) FROM material_master WHERE masgrp!='0' ORDER BY masgrp ASC;";
	$res3 = mssql_query($qry3);
	
	while ($row2 = mssql_fetch_array($res2))
	{
		$vn_ar[$row2['vid']]=$row2['name'];
	}
	
	while ($row3 = mssql_fetch_array($res3))
	{
		$mg_ar[]=$row3['masgrp'];
	}

	echo " 			<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
	echo "			<input type=\"hidden\" name=\"subq\" value=\"equip2\">\n";
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"2\" align=\"left\"><b>JMS Sales Rep Equipment Report</b></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"2\" align=\"center\">\n";
	echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   						<tr>\n";
	echo " 			  					<td align=\"left\" valign=\"bottom\"><b>Office</b>\n";
	echo " 			  						<select name=\"oid\">\n";
	//echo " 			  							<option value=\"0\">All</option>\n";
	//echo " 			  							<option value=\"0\">------------------</option>\n";
	
	
	while ($row1 = mssql_fetch_array($res1))
	{
		echo " 			  							<option value=\"".$row1['officeid']."\">".$row1['name']."</option>\n";
	}
	
	echo " 			  						</select>\n";
	echo "								</td>\n";
	echo " 			  					<td align=\"right\" valign=\"bottom\"><b>Date Range</b>\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
	echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
	echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "								</td>\n";
	echo "			 			  		<td align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Select\"></td>\n";
	echo "   						</tr>\n";
	echo " 			  			</table>\n";
	echo " 			  		</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"2\" align=\"center\" valign=\"bottom\">\n";
	echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   						<tr>\n";
	echo " 			  					<td class=\"gray_und\" align=\"center\"><b>Vendor & Product Line</b></td>\n";
	echo " 			  					<td class=\"gray_und\" align=\"center\">Enable</td>\n";
	
	foreach ($mg_ar as $n0 => $v0)
	{
		echo "	 			  							<td class=\"gray_und\" align=\"center\" width=\"25px\">".$v0."</td>\n";	
	}
	
	echo "   						</tr>\n";
	
	foreach ($vn_ar as $n1 => $v1)
	{
		echo "   									<tr>\n";
		echo "	 			  							<td class=\"gray_und\" align=\"left\">".$v1."</td>\n";
		echo "	 			  							<td class=\"gray_und\" align=\"center\"><input type=\"checkbox\" class=\"checkboxgry\" name=\"vids[]\" value=\"".$n1."\"></td>\n";
		
		foreach ($mg_ar as $n2 => $v2)
		{
			$qryZ	= "SELECT count(id) as mcnt from material_master where masgrp='".$v2."' and vid='".$n1."' and vpnum!='';";
			$resZ	= mssql_query($qryZ);
			$rowZ	= mssql_fetch_array($resZ);
			
			//echo $qryZ;
			
			if ($rowZ['mcnt'] > 0)
			{
				$dis="";
				echo "	 			  							<td class=\"gray_lbr\" align=\"center\"><input type=\"checkbox\" class=\"checkboxgry\" name=\"mg".$n1."[]\" value=\"".$v2."\" ".$dis."></td>\n";
			}
			else
			{
				$dis="DISABLED";
				echo "	 			  							<td class=\"gray_lbr\" align=\"center\"></td>\n";
			}
			
			//echo "	 			  							<td class=\"gray_lbr\" align=\"center\"><input type=\"checkbox\" class=\"checkboxgry\" name=\"mg".$n1."[]\" value=\"".$v2."\" ".$dis."></td>\n";
			$dis="";
		}
		
		echo "   									</tr>\n";
	}
	
	echo "						</table>\n";
	echo " 			  		</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "			</form>\n";
	
	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch1'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch1'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";
}

function equip_search2()
{
	$brdr	=0;
	
	//show_post_vars();
	
	if (valid_date($_REQUEST['d1']) && valid_date($_REQUEST['d2']))
	{
		$d1=$_REQUEST['d1'];
		$d2=$_REQUEST['d2'];
	}
	else
	{
		//echo "Invalid Date. Click BACK and Reenter.";
		//exit;
	}
	
	$qry1 = "SELECT gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	if ($row1['gmreports']!=1)
	{
		echo "You do not have appropriate access for this Report";
		exit;
	}
	
	$qry1 = "SELECT officeid,name,pb_code FROM offices WHERE officeid='".$_REQUEST['oid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	//echo $qry1."<br>";
	
	foreach ($_REQUEST['vids'] as $pn => $pv)
	{
		//$qry2 = "SELECT DISTINCT(m.vid),v.name FROM material_master as m inner join vendors as v on m.vid=v.vid WHERE m.vid!='0' and v.etrack!=0 ORDER BY v.name ASC;";
		$qry2 = "SELECT v.vid,v.name FROM vendors as v WHERE v.vid='".$pv."';";
		$res2 = mssql_query($qry2);
	
		//echo $qry2."<br>";
		while ($row2 = mssql_fetch_array($res2))
		{
			//$mg_ar[]=$row2['vid'];
			$vn_ar[$row2['vid']]=$row2['name'];
			$mg_ar[]=$row2['vid'];
		}
	}
	
	$fmg_ar=array_flip($mg_ar);
	
	foreach ($vn_ar as $n1 => $v1)
	{
		$vpn_ar	=array();
		if ($_REQUEST["mg".$n1])
		{
			foreach ($_REQUEST["mg".$n1] as $nmg => $vmg)
			{
				$qry3 	= "SELECT DISTINCT(vpnum) FROM material_master WHERE vid='".$n1."' and masgrp='".$vmg."' and vpnum!='' ORDER BY vpnum;";
				$res3 	= mssql_query($qry3);
				
				while ($row3 = mssql_fetch_array($res3))
				{
					$vpn_ar[]=$row3['vpnum'];
				}
			}
		}
		$fmg_ar[$n1]=$vpn_ar;
	}
	
	//show_array_vars($fmg_ar);
	echo " 			<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
	echo "			<input type=\"hidden\" name=\"subq\" value=\"equip3\">\n";
	echo "			<input type=\"hidden\" name=\"oid\" value=\"".$row1['officeid']."\">\n";
	echo "			<input type=\"hidden\" name=\"d1\" value=\"".$_REQUEST['d1']."\">\n";
	echo "			<input type=\"hidden\" name=\"d2\" value=\"".$_REQUEST['d2']."\">\n";
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"2\" align=\"left\"><b>JMS Sales Rep Equipment Report</b></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"2\" align=\"center\">\n";
	echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   						<tr>\n";
	echo " 			  					<td align=\"left\" valign=\"bottom\"><b>Office:</b> ".$row1['name']."</td>\n";
	echo " 			  					<td align=\"right\" valign=\"bottom\"><b>Date Range:</b> ".$_REQUEST['d1']." - ".$_REQUEST['d2']."</td>\n";
	echo "			 			  		<td align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Select\"></td>\n";
	echo "   						</tr>\n";
	echo " 			  			</table>\n";
	echo " 			  		</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td align=\"center\" valign=\"bottom\">\n";
	echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   						<tr>\n";
	
	foreach ($fmg_ar as $nn => $vn)
	{		
		echo " 			  					<td class=\"gray_lbr\" align=\"center\" valign=\"bottom\"><b>".$vn_ar[$nn]."</b></td>\n";
	}
		
	echo "   						</tr>\n";	
	echo "   						<tr>\n";
	
	foreach ($fmg_ar as $n2 => $v2)
	{
		echo " 			  					<td align=\"center\" valign=\"top\">\n";
		echo "									<table width=\"100%\" border=\"".$brdr."\">\n";
	
		foreach ($v2 as $n3 => $v3)
		{
			$qryZa = "SELECT id,masgrp FROM material_master WHERE vpnum='".$v3."';";
			$resZa = mssql_query($qryZa);
			$rowZa = mssql_fetch_array($resZa);
			
			$qryZb = "SELECT matid FROM [".$row1['pb_code']."inventory] WHERE matid='".$rowZa['id']."';";
			$resZb = mssql_query($qryZb);
			$rowZb = mssql_fetch_array($resZb);
			$nrowZb = mssql_num_rows($resZb);
			
			//echo $qryZa."<br>";
			//echo $qryZb."<br>";
			if ($nrowZb > 0)
			{
				echo "										<tr>\n";
				echo "											<td class=\"gray_lbr\" align=\"center\"><input type=\"checkbox\" class=\"checkboxgry\" name=\"pn[]\" value=\"".$v3."\"></td>\n";
				echo "											<td class=\"gray_und\" align=\"center\">".$rowZa['masgrp']."</td>\n";
				echo "											<td class=\"gray_und\" align=\"left\">".$v3."</td>\n";
				echo "										</tr>\n";
			}
		}
	
		echo "									</table>\n";
		echo " 						  		</td>\n";
	}
	
	echo "   						</tr>\n";
	echo "						</table>\n";
	echo " 			  		</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
}

function equip_search3()
{
	error_reporting(E_ALL);
	
	//show_post_vars();
	global $tncod_ar;
	$brdr			=0;
	$incmat		=0;
	$showmat		=true;
	$job_ar		=array();
	$mat_ar		=array();
	$msg_ar		=array();
	$exp_ar		=array();
	$slr_ar		=array();
	$ppn_ar		=array();
	$sca_ar		=array();
	$tncod_ar 	=array();
	$secids		=get_secids_by_oid();
	
	if (!empty($_REQUEST['d1']) && valid_date($_REQUEST['d1']))
	{
		$d1mo		=date("m",strtotime($_REQUEST['d1']));
		$d1yr		=date("Y",strtotime($_REQUEST['d1']));
	}
	else
	{
		echo "No date input. You must fill in a Date.";
		exit;
	}
	
	if (!empty($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
	{
		$d2mo		=date("m",strtotime($_REQUEST['d2']));
		$d2yr		=date("Y",strtotime($_REQUEST['d2']));
	}

	if (empty($_REQUEST['d2']) && !valid_date($_REQUEST['d2']))
	{
		$qry	= "SELECT * FROM digreport_main WHERE officeid='".$_REQUEST['oid']."' AND rept_mo='".$d1mo."' AND rept_yr='".$d1yr."' and no_digs!=0;";
		$qrya = "SELECT SUM(no_digs) as dcnt FROM digreport_main WHERE officeid='".$_REQUEST['oid']."' AND rept_mo='".$d1mo."' AND rept_yr='".$d1yr."' and no_digs!=0;";
	}
	else
	{
		//$qry	= "SELECT * FROM digreport_main WHERE officeid='".$_REQUEST['oid']."' AND rept_mo >= '".$d1mo."' AND rept_mo <= '".$d2mo."' AND rept_yr='".$d1yr."' and no_digs!=0;";
		$qry= "SELECT * FROM digreport_main WHERE officeid='".$_REQUEST['oid']."' AND rept_mo >= '".$d1mo."' AND rept_yr='".$d1yr."' and no_digs!=0;";
		$qrya = "SELECT SUM(no_digs) as dcnt FROM digreport_main WHERE officeid='".$_REQUEST['oid']."' AND rept_mo >= '".$d1mo."' AND rept_mo <= '".$d2mo."' AND rept_yr='".$d1yr."' and no_digs!=0;";
	}
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	$resa	= mssql_query($qrya);
	$rowa	= mssql_fetch_array($resa);
	
	//show_array_vars($job_ar);
	
	if ($nrow > 0)
	{
		while ($row	= mssql_fetch_array($res))
		{
			$ijt	=explode(",",$row['jtext']);
			foreach ($ijt as $nj => $vj)
			{
				$iijt=explode(":",$vj);
				$job_ar[$iijt[8]][]=$iijt[12];
			}
		}
	}
	
	if (count($job_ar)==0)
	{
		echo "Job Data Missing<br>";
		exit;
	}
	//show_array_vars($job_ar);
	
	$qry0 = "SELECT pb_code,name FROM offices WHERE officeid='".$_REQUEST['oid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	foreach ($_REQUEST['pn'] as $pn => $pv)
	{
		$qry1 = "SELECT id,masgrp FROM material_master WHERE vpnum='".$pv."';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		
		$qry2 = "SELECT invid FROM [".$row0['pb_code']."inventory] WHERE matid='".$row1['id']."';";
		$res2 = mssql_query($qry2);
		$nrow2= mssql_num_rows($res2);
		
		//echo $qry2."<br>";
		
		if ($nrow2 > 0)
		{
			while ($row2 = mssql_fetch_array($res2))
			{
				$ppn_ar[$pv][]=$row2['invid'];
				$pmn_ar[$pv]=$row1['masgrp'];
			}
		}
	}
	
	//show_array_vars($ppn_ar);
	
	//$fppn_ar=array_flip($ppn_ar);
	
	$icnt=0;
	foreach ($job_ar as $jd1 => $vd1)
	{
		$tt_ar=array();
		foreach ($vd1 as $jd2 => $vd2)
		{
			//echo $vd2."<br>";
			$qry3a  = "SELECT costdata_m,pcostdata_m,";
			$qry3a .= "(SELECT securityid FROM jobs where officeid='".$_REQUEST['oid']."' and njobid='".$vd2."') as jsec, ";
			$qry3a .= "(SELECT tgp FROM jobs where officeid='".$_REQUEST['oid']."' and njobid='".$vd2."') as jtgp ";
			$qry3a .= "FROM jdetail WHERE officeid='".$_REQUEST['oid']."' AND njobid='".$vd2."' AND jadd ";
			$qry3a .= "= (SELECT MAX(jadd) FROM jdetail WHERE officeid='".$_REQUEST['oid']."' AND njobid='".$vd2."');";
			$res3a = mssql_query($qry3a);
			$row3a = mssql_fetch_array($res3a);
			
			//echo $qry3a."<br>";
			
			$cdataC = explode(",",$row3a['costdata_m']);
			$cdataP = explode(",",$row3a['pcostdata_m']);
			
			foreach ($cdataC as $idn1 => $idv1)
			{
				$cdataCi = explode(":",$idv1);
				
				foreach ($ppn_ar as $idn2 => $idv2)
				{
					foreach ($idv2 as $idn3 => $idv3)
					{
						if ($cdataCi[1]==$idv3)
						{
							//echo $cdataCi[1].":".$idv3."<br>";
							$tt_ar[]=$cdataCi[1];
						}
					}
				}
				
				
				/*
				if (in_array($cdataCi[1],$ppn_ar))
				{
					$tt_ar[]=$cdataCi[1];
				}
				*/
				$sca_ar[$row3a['jsec']]=array($tt_ar);
				//$tt_ar=array();
			}
			
			$tgp_ar[$vd2]=$row3a['jtgp'];
		}
	}
	
	//show_array_vars($sca_ar);
	$ftgp_ar=array_flip($tgp_ar);
	
	foreach ($job_ar as $jtn => $jtv)
	{
		$isrt=0;
		foreach ($jtv as $ijtn => $ijtv)
		{
			//if (in_array($ijtn,$ftgp_ar)
			//{
				$isrt=$isrt+$tgp_ar[$ijtv];
			//}
		}
		
		$srt_ar[$jtn]=$isrt;
	}
	
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 	 				<td colspan=\"3\" align=\"left\">\n";
	echo "						<table width=\"100%\" border=\"0\">\n";
	echo "   						<tr>\n";
	echo " 	  							<td align=\"left\" width=\"225px\"><b>JMS Sales Rep Equipment Report</b></td>\n";
	echo " 	 							<td align=\"left\">".$row0['name']."</td>\n";
	echo "								<td align=\"right\">".$d1mo."/".$d1yr." - ".$d2mo."/".$d2yr."</td>\n";
	echo "   						</tr>\n";
	echo "   					</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td align=\"left\">\n";
	echo "					<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   					<tr>\n";
	echo " 			  				<td align=\"left\" valign=\"bottom\"><b>Sales Rep</b></td>\n";
	echo " 			  				<td align=\"left\" valign=\"bottom\"><b>Jobs</b></td>\n";
	echo " 			  				<td align=\"left\" valign=\"bottom\"><b>Avg %</b></td>\n";
	echo " 			 		 		<td align=\"left\">\n";
	echo "								<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   								<tr>\n";
	
	foreach ($ppn_ar as $dpn => $dpv)
	{
		echo " 			  						<td class=\"ltgray_und\" align=\"center\" width=\"90px\">".$pmn_ar[$dpn]."<br><b>".$dpn."</b></td>\n";
	}
	
	echo "   						</tr>\n";
	echo "   					</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";

	foreach ($sca_ar as $sn => $sv)
	{
		//print_r($sv);
		$qry4  = "SELECT securityid,fname,lname FROM security where securityid='".$sn."';";
		$res4 = mssql_query($qry4);
		$row4 = mssql_fetch_array($res4);
		
		$ftgp=round(($srt_ar[$sn]/count($job_ar[$sn])) * 100);
		
		echo "   			<tr>\n";
		echo " 			  		<td class=\"wh_und\" align=\"left\">".$row4['lname'].", ".$row4['fname']."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\">".count($job_ar[$sn])."</td>\n";	
		echo " 			  		<td class=\"wh_und\" align=\"center\">".$ftgp."</td>\n";
		echo "			  		<td class=\"wh_und\" align=\"left\">\n";
		echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
		echo " 	  						<tr>\n";
		
		foreach ($ppn_ar as $dpn => $dpv)
		{
			$itn=0;
			//foreach ($sv[0] as $idn2 => $idv2)
			foreach ($sv as $idn2 => $idv2)
			{
				//echo "T:".$idv2."<br>";
				//print_r($idv2);
				foreach ($idv2 as $idn3 => $idv3)
				{
					//echo "T: ".$ppn_ar[$dpn].":".$idv3."<br>";
					//print_r($ppn_ar[$dpn]);
					//if ($ppn_ar[$dpn]==$idv2)
					foreach ($ppn_ar[$dpn] as $pdn1 => $pdv1)
					{
						if ($pdv1==$idv3)
						{
							$itn++;
						}
					}
				}
			}
			
			echo "   							<td align=\"center\" width=\"90px\">".$itn."</td>\n";
			unset($itn);
		}
		
		echo "   						</tr>\n";
		echo "   					</table>\n";
		echo "					</td>\n";	
		echo "   			</tr>\n";
	}
	unset($itn);
	
	echo "					</table>\n";
	echo "					</td>\n";
	echo "					<td>\n";
	
	//echo "Parts Definition";
	echo "					</td>\n";	
	echo "   			</tr>\n";
	echo "			</table>\n";
	//show_array_vars($job_ar);
	//show_array_vars($sca_ar);
	
	//show_array_vars($job_ar);
	
	//echo "-----<br>";
	
	//show_array_vars($tgp_ar);
	
	//echo "-----<br>";
	
	//show_array_vars($srt_ar);
	
}

function equip_search3old()
{
	error_reporting(E_ALL);
	
	//show_post_vars();
	global $tncod_ar;
	$brdr			=0;
	$incmat		=0;
	$showmat		=true;
	$job_ar		=array();
	$mat_ar		=array();
	$msg_ar		=array();
	$exp_ar		=array();
	$slr_ar		=array();
	$ppn_ar		=array();
	$sca_ar		=array();
	$tncod_ar 	=array();
	$secids		=get_secids_by_oid();
	
	if (!empty($_REQUEST['d1']) && valid_date($_REQUEST['d1']))
	{
		$d1mo		=date("m",strtotime($_REQUEST['d1']));
		$d1yr		=date("Y",strtotime($_REQUEST['d1']));
	}
	else
	{
		echo "No date input. You must fill in a Date.";
		exit;
	}
	
	if (!empty($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
	{
		$d2mo		=date("m",strtotime($_REQUEST['d2']));
		$d2yr		=date("Y",strtotime($_REQUEST['d2']));
	}

	if (empty($_REQUEST['d2']) && !valid_date($_REQUEST['d2']))
	{
		$qry	= "SELECT * FROM digreport_main WHERE officeid='".$_REQUEST['oid']."' AND rept_mo='".$d1mo."' AND rept_yr='".$d1yr."' and no_digs!=0;";
		$qrya = "SELECT SUM(no_digs) as dcnt FROM digreport_main WHERE officeid='".$_REQUEST['oid']."' AND rept_mo='".$d1mo."' AND rept_yr='".$d1yr."' and no_digs!=0;";
	}
	else
	{
		$qry	= "SELECT * FROM digreport_main WHERE officeid='".$_REQUEST['oid']."' AND rept_mo >= '".$d1mo."' AND rept_mo <= '".$d2mo."' AND rept_yr='".$d1yr."' and no_digs!=0;";
		$qrya = "SELECT SUM(no_digs) as dcnt FROM digreport_main WHERE officeid='".$_REQUEST['oid']."' AND rept_mo >= '".$d1mo."' AND rept_mo <= '".$d2mo."' AND rept_yr='".$d1yr."' and no_digs!=0;";
	}
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	$resa	= mssql_query($qrya);
	$rowa	= mssql_fetch_array($resa);
	
	//show_array_vars($job_ar);
	
	if ($nrow > 0)
	{
		while ($row	= mssql_fetch_array($res))
		{
			$ijt	=explode(",",$row['jtext']);
			foreach ($ijt as $nj => $vj)
			{
				$iijt=explode(":",$vj);
				$job_ar[$iijt[8]][]=$iijt[12];
			}
		}
	}
	
	//show_array_vars($job_ar);
	
	$qry0 = "SELECT pb_code,name FROM offices WHERE officeid='".$_REQUEST['oid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	foreach ($_REQUEST['pn'] as $pn => $pv)
	{
		$qry1 = "SELECT id,masgrp FROM material_master WHERE vpnum='".$pv."';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		
		$qry2 = "SELECT invid FROM [".$row0['pb_code']."inventory] WHERE matid='".$row1['id']."';";
		$res2 = mssql_query($qry2);
		$nrow2= mssql_num_rows($res2);
		
		if ($nrow2 > 0)
		{
			while ($row2 = mssql_fetch_array($res2))
			{
				$ppn_ar[$pv]=$row2['invid'];
				$pmn_ar[$pv]=$row1['masgrp'];
			}
		}
	}
	
	//show_array_vars($ppn_ar);
	
	//$fppn_ar=array_flip($ppn_ar);
	
	$icnt=0;
	foreach ($job_ar as $jd1 => $vd1)
	{
		$tt_ar=array();
		foreach ($vd1 as $jd2 => $vd2)
		{
			//echo $vd2."<br>";
			$qry3a  = "SELECT costdata_m,pcostdata_m,";
			$qry3a .= "(SELECT securityid FROM jobs where officeid='".$_REQUEST['oid']."' and njobid='".$vd2."') as jsec, ";
			$qry3a .= "(SELECT tgp FROM jobs where officeid='".$_REQUEST['oid']."' and njobid='".$vd2."') as jtgp ";
			$qry3a .= "FROM jdetail WHERE officeid='".$_REQUEST['oid']."' AND njobid='".$vd2."' AND jadd ";
			$qry3a .= "= (SELECT MAX(jadd) FROM jdetail WHERE officeid='".$_REQUEST['oid']."' AND njobid='".$vd2."');";
			$res3a = mssql_query($qry3a);
			$row3a = mssql_fetch_array($res3a);
			
			//echo $qry3a."<br>";
			
			$cdataC = explode(",",$row3a['costdata_m']);
			$cdataP = explode(",",$row3a['pcostdata_m']);
			
			foreach ($cdataC as $idn1 => $idv1)
			{
				$cdataCi = explode(":",$idv1);
				
				if (in_array($cdataCi[1],$ppn_ar))
				{
					$tt_ar[]=$cdataCi[1];
				}
				
				$sca_ar[$row3a['jsec']]=array($tt_ar);
				//$tt_ar=array();
			}
			
			$tgp_ar[$vd2]=$row3a['jtgp'];
		}
	}
	
	$ftgp_ar=array_flip($tgp_ar);
	
	foreach ($job_ar as $jtn => $jtv)
	{
		$isrt=0;
		foreach ($jtv as $ijtn => $ijtv)
		{
			//if (in_array($ijtn,$ftgp_ar)
			//{
				$isrt=$isrt+$tgp_ar[$ijtv];
			//}
		}
		
		$srt_ar[$jtn]=$isrt;
	}
	
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 	 				<td colspan=\"3\" align=\"left\">\n";
	echo "						<table width=\"100%\" border=\"0\">\n";
	echo "   						<tr>\n";
	echo " 	  							<td align=\"left\" width=\"225px\"><b>JMS Sales Rep Equipment Report</b></td>\n";
	echo " 	 							<td align=\"left\">".$row0['name']."</td>\n";
	echo "								<td align=\"right\">".$d1mo."/".$d1yr." - ".$d2mo."/".$d2yr."</td>\n";
	echo "   						</tr>\n";
	echo "   					</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td align=\"left\">\n";
	echo "					<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   					<tr>\n";
	echo " 			  				<td align=\"left\" valign=\"bottom\"><b>Sales Rep</b></td>\n";
	echo " 			  				<td align=\"left\" valign=\"bottom\"><b>Jobs</b></td>\n";
	echo " 			  				<td align=\"left\" valign=\"bottom\"><b>Avg %</b></td>\n";
	echo " 			 		 		<td align=\"left\">\n";
	echo "								<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   								<tr>\n";
	
	foreach ($ppn_ar as $dpn => $dpv)
	{
		echo " 			  						<td class=\"ltgray_und\" align=\"center\" width=\"90px\">".$pmn_ar[$dpn]."<br><b>".$dpn."</b></td>\n";
	}
	
	echo "   						</tr>\n";
	echo "   					</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";

	foreach ($sca_ar as $sn => $sv)
	{
		$qry4  = "SELECT securityid,fname,lname FROM security where securityid='".$sn."';";
		$res4 = mssql_query($qry4);
		$row4 = mssql_fetch_array($res4);
		
		$ftgp=round(($srt_ar[$sn]/count($job_ar[$sn])) * 100);
		
		echo "   			<tr>\n";
		echo " 			  		<td class=\"wh_und\" align=\"left\">".$row4['lname'].", ".$row4['fname']."</td>\n";
		echo " 			  		<td class=\"wh_und\" align=\"center\">".count($job_ar[$sn])."</td>\n";
		
		echo " 			  		<td class=\"wh_und\" align=\"center\">".$ftgp."</td>\n";
		
		echo "			  		<td class=\"wh_und\" align=\"left\">\n";
		echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
		echo " 	  						<tr>\n";
		
		foreach ($ppn_ar as $dpn => $dpv)
		{
			$itn=0;
			foreach ($sv[0] as $idn2 => $idv2)
			{
				if ($ppn_ar[$dpn]==$idv2)
				{
					$itn++;
				}
			}
			
			echo "   							<td align=\"center\" width=\"90px\">".$itn."</td>\n";
			unset($itn);
		}
		
		echo "   						</tr>\n";
		echo "   					</table>\n";
		echo "					</td>\n";	
		echo "   			</tr>\n";
	}
	unset($itn);
	
	echo "					</table>\n";
	echo "					</td>\n";
	echo "					<td>\n";
	
	//echo "Parts Definition";
	echo "					</td>\n";	
	echo "   			</tr>\n";
	echo "			</table>\n";
	//show_array_vars($job_ar);
	//show_array_vars($sca_ar);
	
	//show_array_vars($job_ar);
	
	//echo "-----<br>";
	
	//show_array_vars($tgp_ar);
	
	//echo "-----<br>";
	
	//show_array_vars($srt_ar);
	
}

function eqprpt_view()
{
	$brdr=1;
	$incmat=0;
	$showmat=true;
	$mat_ar=array();
	$cod_ar=array();
	$exp_ar=array();
	$secids=get_secids();

	//$qry0 = "SELECT * FROM material_grp_codes WHERE active=1 ORDER by abrev;";
	$qry0 = "SELECT * FROM material_grp_codes WHERE estgrp!='0' AND active='1' ORDER BY estgrp ASC;";
	$res0 = mssql_query($qry0);

	while ($row0 = mssql_fetch_array($res0))
	{
		$cod_ar[]=$row0['abrev'];
		$exp_ar[]=$row0['name'];
	}

	$qry = "SELECT * FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	//echo $qry."<br>";

	if ($showmat && $row['no_digs']!=0)
	{
		$prejtext=explode(",",$row['jtext']);
		foreach ($prejtext AS $npre => $vpre)
		{
			$preijtext=explode(":",$vpre);
			if (isset($preijtext[17]) && strlen($preijtext[17]) >= 4)
			{
				$incmat++;
			}
		}
	}

	$cnt_cod=count($cod_ar)+6;
	$dis		="";
	$j_ids	=array();
	$f_date	=$row['rept_mo']."/01/".$row['rept_yr'];
	$l_date	=$row['rept_mo']."/".date("t",strtotime($f_date))."/".$row['rept_yr'];

	//echo "        <span class=\"submenu\" id=\"subTHX1138\">\n";
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td colspan=\"".$cnt_cod."\" align=\"left\" valign=\"bottom\"><b>Stored Equipment Report Detail: </b> ".date("F",strtotime($f_date))." ".$row['rept_yr']."</td>\n";
	echo "   			</tr>\n";

	if ($incmat==0)
	{
		//$zo=0;
		echo "   			<tr>\n";
		echo " 			  		<td colspan=\"9\" align=\"left\" valign=\"bottom\"><b>No Equipment Listed</b></td>\n";
		echo "   			</tr>\n";
	}
	else
	{
		$jcnt=0;
		$jtext=explode(",",$row['jtext']);

		echo "   			<tr>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" NOWRAP><b></b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Job ID</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" width=\"175px\" NOWRAP><b>Customer</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"120px\" NOWRAP><b>Salesman</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"20px\"><b>GP</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"20px\"><b>P</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"20px\"><b>S</b></td>\n";

		if ($incmat > 0)
		{
			foreach ($cod_ar as $n0 => $v0)
			{
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP title=\"".$exp_ar[$n0]."\"><b>".$v0."</b></td>\n";
			}
		}

		echo "   			</tr>\n";


		//echo $row['jtext']."<br><br>";
		//show_array_vars($jtext);

		foreach ($jtext AS $n => $v)
		{
			$jcnt++;

			$ijtext=explode(":",$v);

			$qry1 = "SELECT securityid,lname,fname FROM security WHERE securityid='".$ijtext[8]."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);

			$camt=number_format($ijtext[2], 2, '.', '');

			$tctramt	=$camt;
			$fcamt	=number_format($tctramt, 2, '.', '');

			if (isset($ijtext[15]))
			{
				$fpft		=$ijtext[15];
			}
			else
			{
				$fpft		=0;
			}

			if (isset($ijtext[16]) && is_numeric($ijtext[16]))
			{
				$fsqft	=$ijtext[16];
			}
			else
			{
				$fsqft	=0;
			}

			if (isset($ijtext[19]))
			{
				$ftgp=$ijtext[19];
			}
			else
			{
				$ftgp=0;
			}

			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>".$jcnt."</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\">".$ijtext[0]."</td>\n";
			echo " 			  		<td align=\"left\" valign=\"bottom\">".$ijtext[9]."</td>\n";
			echo " 			  		<td align=\"left\" valign=\"bottom\">".$row1['lname'].", ".$row1['fname']."</td>\n";
			echo " 			  		<td align=\"center\" valign=\"bottom\">".$ftgp."%</td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\">".$fpft."</td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\">".$fsqft."</td>\n";

			if ($incmat > 0)
			{
				//echo " 			  		<td align=\"left\" valign=\"bottom\">\n";

				$mgc_items=explode("|",$ijtext[17]);
				foreach ($cod_ar AS $ni => $vi)
				{
					echo " 			  		<td align=\"center\" valign=\"bottom\">\n";

					if (in_array($vi,$mgc_items))
					{
						echo "X";

						if (strlen($vi) >= 4)
						{
							$mat_ar[]=$vi;
						}
					}

					echo "					</td>\n";
				}
			}

			echo "   			</tr>\n";


		}

		echo "   			<tr>\n";
		echo "				<td align=\"right\" colspan=\"7\"><b>Equipment Totals:</b></td>\n";

		$i=0;

		foreach ($cod_ar as $nc => $vc)
		{
			$rcod_ar=array_flip($cod_ar);
			echo "				<td align=\"center\"><b>\n";

			if (in_array($vc,$mat_ar))
			{
				foreach ($mat_ar as $nz => $vz)
				{
					if ($vz==$vc)
					{
						$i++;
					}
				}
				echo $i;
				$i=0;
			}

			echo "				</b></td>\n";
		}

		echo "   			</tr>\n";
		echo "			</table>\n";
	}
}

function digrpt_view()
{
	$brdr	=1;
	$incmat	=0;
	$tcnt	=0;
	$jdiv_ar=array();
	$mat_ar	=array();

	$qry = "SELECT * FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	/*
	if ($_SESSION['securityid']==26)
	{
		echo $qry.'<br>';
	}
	*/

	if (isset($_REQUEST['showmat']) && $_REQUEST['showmat']==1 && $row['no_digs']!=0)
	{
		$prejtext=explode(",",$row['jtext']);
		foreach ($prejtext AS $npre => $vpre)
		{
			$preijtext=explode(":",$vpre);
			if (isset($preijtext[17]) && strlen($preijtext[17]) >= 4)
			{
				$incmat++;
			}
		}
	}

	if ($_SESSION['rlev'] >= 8)
	{
		if ($incmat > 0)
		{
			$cspan=17;
			$tspan=16;
		}
		else
		{
			$cspan=16;
			$tspan=15;
		}
	}
	else
	{
		if ($incmat > 0)
		{
			$cspan=15;
			$tspan=14;
		}
		else
		{
			$cspan=14;
			$tspan=13;
		}
	}

	$dis		="";
	$j_ids	=array();
	$f_date	=$row['rept_mo']."/01/".$row['rept_yr'];
	$l_date	=$row['rept_mo']."/".date("t",strtotime($f_date))."/".$row['rept_yr'];

	echo "			<table class=\"outer\" width=\"950px\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"><b>Stored Dig Report Detail: </b> ".date("F",strtotime($f_date))." ".$row['rept_yr']."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" NOWRAP><b></b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Job ID</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" width=\"135px\" NOWRAP><b>Customer</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Cont Date</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Dig Date</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" width=\"100px\" NOWRAP><b>Salesman</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Contract</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Addn</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Addn Amt</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Allowance</b></td>\n";
	
		if ($_SESSION['rlev'] >= 8)
		{
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Acct Fee</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Consult Fee</b></td>\n";
		}
	
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Royalty</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>GP</b></td>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Est Cost</b></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
	echo " 			  		<td class=\"ltgray_und\" colspan=\"".$tspan."\" align=\"left\" valign=\"bottom\"><b>Digs</b></td>\n";
	echo "   			</tr>\n";

	if ($row['no_digs']==0 && $row['no_rens']==0 && $row['no_addn']==0)
	{
		//$zo=0;
		echo "   			<tr>\n";
		echo " 			  		<td colspan=\"9\" align=\"left\" valign=\"bottom\"><b>No Digs, Renovations, or Addendums</b></td>\n";
		echo "   			</tr>\n";
		$fttcon	=number_format($row['cont_total'], 2, '.', ',');
		$ftrcon	=number_format($row['admin_fee'], 2, '.', ',');
		$ftacon	=number_format($row['acct_fee'], 2, '.', ',');
		$ftccon	=number_format($row['cons_fee'], 2, '.', ',');
		$ftdcon	=number_format($row['addn_fee'], 2, '.', ',');
		$ftlcon	=number_format($row['allo_fee'], 2, '.', ',');
	}
	else
	{
		$jdiv_ar		=array();
		$jtot_ar		=array();
		$rtot_ar		=array();
		$atot_ar		=array();
		$ttcon_ar		=array();
		$trcon_ar		=array();
		$tacon_ar		=array();
		$tccon_ar		=array();
		$tdcon_ar		=array();
		$tlcon_ar		=array();
		$tjest_ar		=array();
		$tecost			=0;
		$tcnt			=0;
		$jcnt			=0;
		$rcnt			=0;
		$acnt			=0;
		$tren			=0;
		$jtext		=explode(",",$row['jtext']);

		foreach ($jtext AS $n => $v) // Digs
		{
			$tcnt++;
			$ijtext	=explode(":",$v);
			
			if (empty($ijtext[20]) || $ijtext[20]==0)
			{
				$jcnt++;
				
				if ($jcnt%2)
				{
					$jtbg='white';
				}
				else
				{
					$jtbg='gray';
				}
				
				$qry1 	= "SELECT securityid,lname,fname,mas_div FROM security WHERE securityid='".$ijtext[8]."';";
				$res1 	= mssql_query($qry1);
				$row1 	= mssql_fetch_array($res1);
	
				$camt		=number_format($ijtext[2], 2, '.', '');
				$tctramt	=$camt;
				$fcamt		=number_format($tctramt, 2, '.', '');
				$roy		=$ijtext[3];
	
				$froy		=number_format($roy, 2, '.', '');
				$facc		=number_format($ijtext[4], 2, '.', '');
				$fcon		=number_format($ijtext[5], 2, '.', '');
				$fall		=number_format($ijtext[13], 2, '.', '');
				$fadp		=number_format($ijtext[14], 2, '.', '');
	
				if (isset($ijtext[18]))
				{
					$fcomm		=$ijtext[18];
				}
				else
				{
					$fcomm		=0;
				}
	
				if (isset($ijtext[19]))
				{
					$ftgp=$ijtext[19];
				}
				else
				{
					$ftgp=0;
				}
	
				if ($ijtext[7]==0)
				{
					$cdate='';
				}
				else
				{
					$cdate=date("m/d/Y", strtotime($ijtext[7]));
				}
	
				if ($ijtext[6]==0 || strlen($ijtext[6]) < 3)
				{
					$fdate="";
				}
				else
				{
					$fdate=date("m/d/Y", strtotime($ijtext[6]));
				}
	
				if ($ijtext[19]!=0)
				{
					$ecost = ($tctramt+$ijtext[14]) - (($tctramt+$ijtext[14]) * ($ijtext[19] * .01));
				}
				else
				{
					$ecost=0;
				}
	
				$tecost=$tecost+$ecost;
				
				echo "   			<tr>\n";				
				echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\"><b>".$jcnt."</b></td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\">".$ijtext[0]."</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"left\" valign=\"bottom\">".$ijtext[9]."</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"center\" valign=\"bottom\">".$cdate."</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"center\" valign=\"bottom\">".$fdate."</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"left\" valign=\"bottom\">".$row1['lname'].", ".$row1['fname']."</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\">".$fcamt."</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"center\" valign=\"bottom\">".$ijtext[11]."</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\">".$fadp."</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\">".$fall."</td>\n";
	
				if ($_SESSION['rlev'] >= 8)
				{
					echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\">".$facc."</td>\n";
					echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\">".$fcon."</td>\n";
				}
	
				echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\">".$froy."</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\">".$ftgp."%</td>\n";
				echo " 			  		<td class=\"".$jtbg."\" align=\"right\" valign=\"bottom\">".number_format($ecost)."</td>\n";
	
				if ($incmat > 0 && $ijtext[17]==0 && strlen($ijtext[17]) >= 4)
				{
					echo " 			  		<td class=\"".$jtbg."\" align=\"left\" valign=\"bottom\">\n";
	
					$mgc_items=explode("|",$ijtext[17]);
					foreach ($mgc_items AS $ni => $vi)
					{
						echo "".$vi."";
						if (strlen($vi) >= 4)
						{
							$mat_ar[]=$vi;
						}
					}
	
					echo "					</td>\n";
				}
				
				elseif ($incmat > 0)
				{
					echo " 			  		<td class=\"".$jtbg."\" align=\"left\" valign=\"bottom\"></td>\n";
				}
	
				echo "   			</tr>\n";
				
				$jdiv_ar[]=$row1['mas_div'];
				$jtot_ar[]=array($row1['securityid'],$row1['mas_div'],$fcamt,$froy,$facc,$fcon,$fall,$fadp,$ecost);
			}
		}
		
		foreach ($jtext AS $n => $v) // Addendums
		{
			$tcnt++;
			$ijtext	=explode(":",$v);
			if (isset($ijtext[20]) && $ijtext[20]==2)
			{
				$acnt++;
				$qry1 	= "SELECT securityid,lname,fname,mas_div FROM security WHERE securityid='".$ijtext[8]."';";
				$res1 	= mssql_query($qry1);
				$row1 	= mssql_fetch_array($res1);
	
				$camt		=number_format($ijtext[2], 2, '.', '');
				$tctramt	=$camt;
				$fcamt		=number_format($tctramt, 2, '.', '');
				$roy		=$ijtext[3];
	
				$froy		=number_format($roy, 2, '.', '');
				$facc		=number_format($ijtext[4], 2, '.', '');
				$fcon		=number_format($ijtext[5], 2, '.', '');
				$fall		=number_format($ijtext[13], 2, '.', '');
				$fadp		=number_format($ijtext[14], 2, '.', '');
	
				if (isset($ijtext[18]))
				{
					$fcomm		=$ijtext[18];
				}
				else
				{
					$fcomm		=0;
				}
	
				if (isset($ijtext[19]))
				{
					$ftgp=$ijtext[19];
				}
				else
				{
					$ftgp=0;
				}
	
				if ($ijtext[7]==0)
				{
					$cdate='';
				}
				else
				{
					$cdate=date("m/d/Y", strtotime($ijtext[7]));
				}
	
				if ($ijtext[6]==0 || strlen($ijtext[6]) < 3)
				{
					$fdate="";
				}
				else
				{
					$fdate=date("m/d/Y", strtotime($ijtext[6]));
				}
	
				if ($ijtext[19]!=0)
				{
					$ecost = ($tctramt+$ijtext[14]) - (($tctramt+$ijtext[14]) * ($ijtext[19] * .01));
					//$ecost = number_format($tctramt * ($ijtext[19] * .01));
				}
				else
				{
					$ecost=0;
				}
	
				$tecost=$tecost+$ecost;
				
				if ($acnt==1)
				{
					echo "   			<tr>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
					echo " 			  		<td class=\"ltgray_und\" colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"><b>Addendums</b></td>\n";
					echo "   			</tr>\n";
				}
				
				echo "   			<tr>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\"><b>".$acnt."</b></td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$ijtext[0]."</td>\n";
				echo " 			  		<td align=\"left\" valign=\"bottom\">".$ijtext[9]."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$cdate."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$fdate."</td>\n";
				echo " 			  		<td align=\"left\" valign=\"bottom\">".$row1['lname'].", ".$row1['fname']."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$fcamt."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$ijtext[11]."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$fadp."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$fall."</td>\n";
	
				if ($_SESSION['rlev'] >= 8)
				{
					echo " 			  		<td align=\"right\" valign=\"bottom\">".$facc."</td>\n";
					echo " 			  		<td align=\"right\" valign=\"bottom\">".$fcon."</td>\n";
				}
	
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$froy."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$ftgp."%</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".number_format($ecost)."</td>\n";
	
				if ($incmat > 0 && $ijtext[17]==0 && strlen($ijtext[17]) >= 4)
				{
					echo " 			  		<td align=\"left\" valign=\"bottom\">\n";
	
					$mgc_items=explode("|",$ijtext[17]);
					foreach ($mgc_items AS $ni => $vi)
					{
						echo "".$vi."";
						if (strlen($vi) >= 4)
						{
							$mat_ar[]=$vi;
						}
					}
	
					echo "					</td>\n";
				}
				elseif ($incmat > 0)
				{
					echo " 			  		<td align=\"left\" valign=\"bottom\"></td>\n";
				}
	
				echo "   			</tr>\n";
				
				$jdiv_ar[]=$row1['mas_div'];
				$atot_ar[]=array($row1['securityid'],$row1['mas_div'],$fcamt,$froy,$facc,$fcon,$fall,$fadp,$ecost);
			}
		}
		
		foreach ($jtext AS $n => $v) // Renovations
		{
			$tcnt++;
			$ijtext	=explode(":",$v);
			if (isset($ijtext[20]) && $ijtext[20]==1)
			{
				$rcnt++;
				
				if ($rcnt%2)
				{
					$rtbg='white';
				}
				else
				{
					$rtbg='gray';
				}
				
				$qry1 	= "SELECT securityid,lname,fname,mas_div FROM security WHERE securityid='".$ijtext[8]."';";
				$res1 	= mssql_query($qry1);
				$row1 	= mssql_fetch_array($res1);
	
				$camt		=number_format($ijtext[2], 2, '.', '');
				$tctramt	=$camt;
				$fcamt	=number_format($tctramt, 2, '.', '');
				$roy		=$ijtext[3];
	
				$froy		=number_format($roy, 2, '.', '');
				$facc		=number_format($ijtext[4], 2, '.', '');
				$fcon		=number_format($ijtext[5], 2, '.', '');
				$fall		=number_format($ijtext[13], 2, '.', '');
				$fadp		=number_format($ijtext[14], 2, '.', '');
	
				if (isset($ijtext[18]))
				{
					$fcomm		=$ijtext[18];
				}
				else
				{
					$fcomm		=0;
				}
	
				if (isset($ijtext[19]))
				{
					$ftgp=$ijtext[19];
				}
				else
				{
					$ftgp=0;
				}
	
				if ($ijtext[7]==0)
				{
					$cdate='';
				}
				else
				{
					$cdate=date("m/d/Y", strtotime($ijtext[7]));
				}
	
				if ($ijtext[6]==0 || strlen($ijtext[6]) < 3)
				{
					$fdate="";
				}
				else
				{
					$fdate=date("m/d/Y", strtotime($ijtext[6]));
				}
	
				if ($ijtext[19]!=0)
				{
					$ecost = ($tctramt+$ijtext[14]) - (($tctramt+$ijtext[14]) * ($ijtext[19] * .01));
					//$ecost = number_format($tctramt * ($ijtext[19] * .01));
				}
				else
				{
					$ecost=0;
				}
	
				$tecost=$tecost+$ecost;
				
				if ($rcnt==1)
				{
					echo "   			<tr>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
					echo " 			  		<td class=\"ltgray_und\" colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"><b>Renovations</b></td>\n";
					echo "   			</tr>\n";
				}
				
				echo "   			<tr>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\"><b>".$rcnt."</b></td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\">".$ijtext[0]."</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"left\" valign=\"bottom\">".$ijtext[9]."</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"center\" valign=\"bottom\">".$cdate."</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"center\" valign=\"bottom\">".$fdate."</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"left\" valign=\"bottom\">".$row1['lname'].", ".$row1['fname']."</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\">".$fcamt."</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"center\" valign=\"bottom\">".$ijtext[11]."</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\">".$fadp."</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\">".$fall."</td>\n";
	
				if ($_SESSION['rlev'] >= 8)
				{
					echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\">".$facc."</td>\n";
					echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\">".$fcon."</td>\n";
				}
	
				echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\">".$froy."</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\">".$ftgp."%</td>\n";
				echo " 			  		<td class=\"".$rtbg."\" align=\"right\" valign=\"bottom\">".number_format($ecost)."</td>\n";
	
				if ($incmat > 0 && $ijtext[17]==0 && strlen($ijtext[17]) >= 4)
				{
					echo " 			  		<td class=\"".$rtbg."\" align=\"left\" valign=\"bottom\">\n";
	
					$mgc_items=explode("|",$ijtext[17]);
					foreach ($mgc_items AS $ni => $vi)
					{
						echo "".$vi."";
						if (strlen($vi) >= 4)
						{
							$mat_ar[]=$vi;
						}
					}
	
					echo "					</td>\n";
				}
				elseif ($incmat > 0)
				{
					echo " 			  		<td class=\"".$rtbg."\" align=\"left\" valign=\"bottom\"></td>\n";
				}
	
				echo "   			</tr>\n";
				
				$jdiv_ar[]=$row1['mas_div'];
				$rtot_ar[]=array($row1['securityid'],$row1['mas_div'],$fcamt,$froy,$facc,$fcon,$fall,$fadp,$ecost);
			}
		}
		
		$fttcon	=number_format($row['cont_total'], 2, '.', ',');
		$ftrcon	=number_format($row['admin_fee'], 2, '.', ',');
		$ftacon	=number_format($row['acct_fee'], 2, '.', ',');
		$ftmcon	=number_format($row['macct_fee'], 2, '.', ',');
		$ftccon	=number_format($row['cons_fee'], 2, '.', ',');
		$ftdcon	=number_format($row['addn_fee'], 2, '.', ',');
		$ftlcon	=number_format($row['allo_fee'], 2, '.', ',');

		echo "			</table>\n";
	}
	
	// Summary Section
	$cjdivs	=count($jdiv_ar);
	$ujdivs	=array_unique($jdiv_ar);
	$cujdivs=count($ujdivs);
	$tecost	=0;
	
	//echo "<br>\n";
	echo "			<table class=\"outer\" width=\"250px\" align=\"right\" border=\"1\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Dig Summary</b></td>\n";

	if ($cujdivs > 1)
	{
		foreach ($ujdivs as $nt => $vt)
		{
			$qry2 = "SELECT mas_div,div_name FROM masdiv_names WHERE mas_div='".$vt."';";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);
			$nrow2 = mssql_num_rows($res2);

			if ($nrow2 > 0)
			{
				$nm=$row2['div_name'];
			}
			else
			{
				//$nm="?";
				$nm=$row2['div_name'];
			}

			echo " 			  		<td class=\"gray\" align=\"center\" valign=\"bottom\"><b>".$nm."</b></td>\n";
		}
	}
	
	echo " 			  		<td class=\"gray\" align=\"center\" valign=\"bottom\" width=\"100px\"><b>Totals</b></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Month</b></td>\n";

	if ($cujdivs > 1)
	{
		foreach ($ujdivs as $nt => $vt)
		{
			echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".date("F",strtotime($f_date))."</td>\n";
		}
	}
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".date("F",strtotime($f_date))."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Digs</b></td>\n";

	if ($cujdivs > 1)
	{
		$jt=0;
		foreach ($ujdivs as $nt => $vt)
		{
			foreach ($jtot_ar as $nt1 => $vt1)
			{
				if ($vt1[1] == $vt)
				{
					$jt++;
				}
			}

			echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".$jt."</td>\n";
			$jt=0;
		}
	}
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".$row['no_digs']."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Addendums</b></td>\n";

	if ($cujdivs > 1)
	{
		$at=0;
		foreach ($ujdivs as $na => $va)
		{
			foreach ($atot_ar as $na1 => $va1)
			{
				if ($va1[1] == $va)
				{
					$at++;
				}
			}

			echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".$at."</td>\n";
			$at=0;
		}
	}
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".$row['no_addn']."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Renovations</b></td>\n";

	if ($cujdivs > 1)
	{
		$rt=0;
		foreach ($ujdivs as $nr => $vr)
		{
			foreach ($rtot_ar as $nr1 => $vr1)
			{
				if ($vr1[1] == $vr)
				{
					$rt++;
				}
			}

			echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".$rt."</td>\n";
			$rt=0;
		}
	}
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".$row['no_rens']."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Contracts</b></td>\n";

	if ($cujdivs > 1)
	{
		$jtc=0;
		foreach ($ujdivs as $nt2 => $vt2)
		{
			foreach ($jtot_ar as $nt21 => $vt21)
			{
				if ($vt21[1] == $vt2)
				{
					$jtc=$jtc+$vt21[2];
				}
			}
			
			foreach ($rtot_ar as $nr21 => $vr21)
			{
				if ($vr21[1] == $vt2)
				{
					$jtc=$jtc+$vr21[2];
				}
			}

			echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".number_format($jtc, 2, '.', ',')."</td>\n";
			$jtc=0;
		}
	}
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".$fttcon."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Addendums</b></td>\n";

	if ($cujdivs > 1)
	{
		$jad=0;
		foreach ($ujdivs as $nt3 => $vt3)
		{
			foreach ($jtot_ar as $nt31 => $vt31)
			{
				//print_r($vt3);
				//echo "----<br>";
				if ($vt31[1] == $vt3)
				{
					$jad=$jad+$vt31[7];
				}
			}
			
			foreach ($rtot_ar as $nr31 => $vr31)
			{
				//print_r($vr51);
				//echo "----<br>";
				if ($vr31[1] == $vt3)
				{
					$jad=$jad+$vr31[7];
				}
			}

			echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".number_format($jad, 2, '.', ',')."</td>\n";
			$jad=0;
		}
	}

	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".$ftdcon."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Allowances</b></td>\n";

	if ($cujdivs > 1)
	{
		$jall=0;
		foreach ($ujdivs as $nt4 => $vt4)
		{
			foreach ($jtot_ar as $nt41 => $vt41)
			{
				//print_r($vt3);
				//echo "----<br>";
				if ($vt41[1] == $vt4)
				{
					$jall=$jall+$vt41[6];
				}
			}
			
			foreach ($rtot_ar as $nr41 => $vr41)
			{
				//print_r($vr51);
				//echo "----<br>";
				if ($vr41[1] == $vt4)
				{
					$jall=$jall+$vr41[6];
				}
			}
			
			echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".number_format($jall, 2, '.', ',')."</td>\n";
			$jall=0;
		}
	}

	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".$ftlcon."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Royalty</b></td>\n";

	if ($cujdivs > 1)
	{
		$jroy=0;
		foreach ($ujdivs as $nt5 => $vt5)
		{
			foreach ($jtot_ar as $nt51 => $vt51)
			{
				if ($_SESSION['securityid']==26)
				{
				print_r($vt51);
				echo "----<br>";
				}
				
				if ($vt51[1] == $vt5)
				{
					$jroy=$jroy+$vt51[3];
				}
			}
			
			foreach ($rtot_ar as $nr51 => $vr51)
			{
				if ($_SESSION['securityid']==26)
				{
				print_r($vr51);
				echo "----<br>";
				}

				if ($vr51[1] == $vt5)
				{
					$jroy=$jroy+$vr51[3];
				}
			}
			
			echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".number_format($jroy, 2, '.', ',')."</td>\n";
			$jroy=0;
		}
	}

	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".$ftrcon."</td>\n";
	echo "   			</tr>\n";

	if ($_SESSION['rlev'] >= 8)
	{
		echo "   			<tr>\n";
		echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Accounting Fee</b></td>\n";

		if ($cujdivs > 1)
		{
			$jacc=0;
			foreach ($ujdivs as $nt6 => $vt6)
			{
				foreach ($jtot_ar as $nt61 => $vt61)
				{
					//print_r($vt3);
					//echo "----<br>";
					if ($vt61[1] == $vt6)
					{
						$jacc=$jacc+$vt61[4];
					}
				}

				foreach ($rtot_ar as $nr61 => $vr61)
				{
					//print_r($vt3);
					//echo "----<br>";
					if ($vr61[1] == $vt6)
					{
						$jacc=$jacc+$vr61[4];
					}
				}

				echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".number_format($jacc, 2, '.', ',')."</td>\n";
				$jacc=0;
			}
		}

		echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".$ftacon."</td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Consulting Fee</b></td>\n";

		if ($cujdivs > 1)
		{
			$jcon=0;
			foreach ($ujdivs as $nt7 => $vt7)
			{
				foreach ($jtot_ar as $nt71 => $vt71)
				{
					if ($vt71[1] == $vt7)
					{
						$jcon=$jcon+$vt71[5];
					}
				}
				
				foreach ($rtot_ar as $nr71 => $vr71)
				{
					if ($vr71[1] == $vt7)
					{
						$jcon=$jcon+$vr71[5];
					}
				}
				
				echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".number_format($jcon, 2, '.', ',')."</td>\n";
				$jcon=0;
			}
		}

		echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".$ftccon."</td>\n";
		echo "   			</tr>\n";
	}


	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Estimated Cost</b></td>\n";

	if ($cujdivs > 1)
	{
		$tjest=0;
		$jest=0;
		foreach ($ujdivs as $nt8 => $vt8)
		{
			foreach ($jtot_ar as $nt81 => $vt81)
			{
				if ($vt81[1] == $vt8)
				{
					$jest=$jest+round($vt81[8]);
				}
			}
			$tjest=$tjest+$jest;
			echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\">".number_format($jest, 2, '.', ',')."</td>\n";
			$jest=0;
		}
		echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".number_format($tjest, 2, '.', ',')."</td>\n";
	}
	else
	{
		$tjest=$tecost;
		echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\" width=\"100px\">".number_format($tjest, 2, '.', ',')."</td>\n";
	}

	echo "   			</tr>\n";	
	echo "			</table>\n";
}

function admin_digrpt_store()
{
		if ($_SESSION['securityid']==269999999999999999999999999999999999999)
		{
				ini_set('display_errors','On');
				error_reporting(E_ALL);
		}
		
	//gen_new();
	if (isset($_REQUEST['valdigs']) && $_REQUEST['valdigs']==1)
	{
		$qry0 = "SELECT id FROM digreport_admin WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		if ($nrow0==0)
		{
			$jstore=admin_build_store();
			//show_post_vars();

			$qry		= "INSERT INTO digreport_admin";
			$qry	  .= "(officeid,";
			$qry	  .= "securityid,";
			$qry	  .= "rept_mo,";
			$qry	  .= "rept_yr,";
			$qry	  .= "no_digs,";
			$qry	  .= "no_rens,";
			$qry	  .= "no_addn,";
			$qry	  .= "cont_total,"; //Contract
			$qry	  .= "admin_fee,"; //Royalty
			$qry	  .= "cons_fee,"; //Consulting
			$qry	  .= "acct_fee,"; //Accting
			$qry	  .= "creator,";
			$qry	  .= "jtext) ";
			$qry	  .= "VALUES ";
			$qry	  .= "('".$_SESSION['officeid']."',";
			$qry	  .= "'".$_REQUEST['rsid']."',";
			$qry	  .= "'".$_REQUEST['rept_mo']."',";
			$qry	  .= "'".$_REQUEST['rept_yr']."',";
			$qry	  .= "'".$_REQUEST['tdig']."',";
			$qry	  .= "'".$_REQUEST['rdig']."',";
			$qry	  .= "'".$_REQUEST['adig']."',";
			$qry	  .= "CONVERT(money,'".$_REQUEST['tctr']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['troy']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['tcon']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['tacc']."'),";
			$qry	  .= "'".$_REQUEST['rsid']."',";
			$qry	  .= "'".$jstore."');";
			$res5		= mssql_query($qry);

			$qry2 = "UPDATE digreport_main SET locked='1' WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
			$res2 = mssql_query($qry2);
			//$row2	= mssql_fetch_array($res2);

			$qry1 = "SELECT * FROM digreport_admin WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
			$res1 = mssql_query($qry1);
			$row1= mssql_fetch_array($res1);

			//show_array_vars($row1);

			//echo "QRY:<br>".$qry."<br>";
			//echo "JSTORE: ".$jstore."<br>";
			admin_dig_rep_lists();
		}
		else
		{
			echo "<font color=\"red\"><b>Error</b></font><br>Report for ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']." already exists.";
		}
	}
	else
	{
		echo "<font color=\"red\"><b>Validation Error</b></font>";
	}
}

function digrpt_store()
{
	if ($_SESSION['securityid']==2699999999999999999999)
		{
				ini_set('display_errors','On');
				error_reporting(E_ALL);
		}
	
	if (isset($_REQUEST['valdigs']) && $_REQUEST['valdigs']==1)
	{
		$qry0 = "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		$qry0a = "SELECT smo,syr,brept_yr from bonus_schedule_config;";
		$res0a = mssql_query($qry0a);
		$row0a = mssql_fetch_array($res0a);

		/*
		if ($_REQUEST['rept_mo']==$row0a['smo'] && $_REQUEST['rept_yr']==$row0a['syr'])
		{
			$brept_yr=$row0a['brept_yr'];
		}
		elseif ($_REQUEST['rept_yr']==$row0a['brept_yr'])
		{
			$brept_yr=$row0a['brept_yr'];
		}
		else
		{
			$brept_yr=$_REQUEST['rept_yr'];
		}
		*/
		
		$brept_yr=$_REQUEST['brept_yr'];

		if ($nrow0==0)
		{
			if ($_SESSION['subq']!="dig_rept_store_sess")
			{
				$type=true;
			}
			else
			{
				$type=false;
			}

			$jstore=build_store();
			//show_post_vars();
			//echo $jstore."<br>";
			
			$qry	   = "INSERT INTO digreport_main";
			$qry	  .= "(officeid,";
			$qry	  .= "securityid,";
			$qry	  .= "rept_mo,";
			$qry	  .= "rept_yr,";
			$qry	  .= "no_digs,";
			$qry	  .= "no_rens,";
			$qry	  .= "no_addn,";
			$qry	  .= "cont_total,"; //Contract
			$qry	  .= "admin_fee,"; //Royalty
			$qry	  .= "cons_fee,"; //Consulting
			$qry	  .= "acct_fee,"; //Accting
			$qry	  .= "macct_fee,"; //Monthly Accting
			$qry	  .= "allo_fee,"; //Addendum
			$qry	  .= "addn_fee,"; //Allowance
			$qry	  .= "cost_total,"; //Allowance
			$qry	  .= "creator,";
			$qry	  .= "brept_yr,";
			$qry	  .= "jtext) ";
			$qry	  .= "VALUES ";
			$qry	  .= "('".$_SESSION['officeid']."',";
			$qry	  .= "'".$_REQUEST['rsid']."',";
			$qry	  .= "'".$_REQUEST['rept_mo']."',";
			$qry	  .= "'".$_REQUEST['rept_yr']."',";
			$qry	  .= "'".$_REQUEST['tdig']."',";
			$qry	  .= "'".$_REQUEST['rdig']."',";
			
			if (isset($_REQUEST['adig']) and $_REQUEST['adig'] > 0)
			{
				$qry	  .= "'".$_REQUEST['adig']."',";
			}
			else
			{
				$qry	  .= "0,";
			}
			
			$qry	  .= "CONVERT(money,'".$_REQUEST['tctr']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['troy']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['tcon']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['tacc']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['mtacc']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['tall']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['tadp']."'),";
			$qry	  .= "CONVERT(money,'".$_REQUEST['tcos']."'),";
			$qry	  .= "'".$_REQUEST['rsid']."',";
			$qry	  .= "'".$brept_yr."',";
			$qry	  .= "'".$jstore."');";
			$res	  = mssql_query($qry);

			$qry1 = "SELECT * FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
			$res1 = mssql_query($qry1);
			$row1= mssql_fetch_array($res1);
			
			//echo $jstore."<br>";

			dig_rep_lists();
		}
		else
		{
			echo "<font color=\"red\"><b>Error</b></font><br>Report for ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']." already exists.";
		}
	}
	else
	{
		echo "<font color=\"red\"><b>Validation Error</b></font>";
	}
}

function admin_digrpt_delete()
{
	$brdr=1;
	$qry0 = "SELECT id FROM digreport_admin WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."' AND id='".$_REQUEST['rept_id']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0!=0)
	{
		if (isset($_REQUEST['confdel']) && $_REQUEST['confdel']==1)
		{
			$qry1 = "DELETE FROM digreport_admin WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
			$res1 = mssql_query($qry1);

			//echo $qry1."<br>";
			dig_rep_lists();
		}
		else
		{
			echo "<b>Click the Confirmation checkbox and resubmit.</b>";
		}
	}
	else
	{
		echo "<font color=\"red\"><b>Error</b></font><br>Report for ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']." does not exist.";
	}
}

function admin_digrpt_pub_delete()
{
	$brdr=1;
	//$qry0 = "SELECT * FROM recognized_digs WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
	
	if (empty($_REQUEST['moid']))
	{
		$qry0 = "SELECT * FROM recognized_digs WHERE rept_id='".$_REQUEST['rept_id']."';";
	}
	else
	{
		$qry0 = "SELECT * FROM recognized_digs WHERE rept_id='".$_REQUEST['rept_id']."' AND moid='".$_REQUEST['moid']."';";
	}
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0!=0)
	{
		if (isset($_REQUEST['confirmdelete']) && $_REQUEST['confirmdelete']==1)
		{
			if (empty($_REQUEST['moid']))
			{
				$qry1 = "DELETE FROM recognized_digs WHERE rept_id='".$_REQUEST['rept_id']."';";
			}
			else
			{
				$qry1 = "DELETE FROM recognized_digs WHERE rept_id='".$_REQUEST['rept_id']."' AND moid='".$_REQUEST['moid']."';";
			}
			$res1 = mssql_query($qry1);

			//echo $qry1."<br>";
			admin_digrpt_pub_arch_list();
		}
		else
		{
			echo "<b>Click the Confirmation checkbox and resubmit.</b>";
		}
	}
	else
	{
		echo "<font color=\"red\"><b>Error</b></font><br>Recognized Dig Report for ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']." does not exist.";
	}
}

function admin_digrpt_pub_deleteOLD()
{
	$brdr=1;
	$qry0 = "SELECT * FROM recognized_digs WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0!=0)
	{
		if (isset($_REQUEST['confirmdelete']) && $_REQUEST['confirmdelete']==1)
		{
			$qry1 = "DELETE FROM recognized_digs WHERE rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
			//$res1 = mssql_query($qry1);

			echo $qry1."<br>";
			admin_digrpt_pub_arch_list();
		}
		else
		{
			echo "<b>Click the Confirmation checkbox and resubmit.</b>";
		}
	}
	else
	{
		echo "<font color=\"red\"><b>Error</b></font><br>Recognized Dig Report for ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']." does not exist.";
	}
}

function admin_digrpt_pub_update_item()
{
	error_reporting(E_ALL);
	$brdr=1;
	$qry0 = "SELECT * FROM recognized_digs WHERE oroid='".$_REQUEST['oroid']."' and rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	
			/*var_dump($_REQUEST['vcs']);
			echo "<br>";
			var_dump($_REQUEST['dcc']);
			echo "<br>";*/
	
	if (!preg_match("/[0-9]*\.[0-9][0-9]/",$_REQUEST['vcs']))
	{
		echo "Value Contracts Started Money format incorrect. Please click back and ensure amounts are format as 2222.22";
		exit;
	}
	
	if (!preg_match("/[0-9]*\.[0-9][0-9]/",$_REQUEST['dcc']))
	{
		echo "Value Contracts Started Money format incorrect. Please click back and ensure amounts are format as 2222.22";
		exit;
	}

	if ($nrow0!=0)
	{
		//if (isset($_REQUEST['confirmdelete']) && $_REQUEST['confirmdelete']==1)
		//{
			//echo money_format('%i', $number) . "\n";
			$prepvcs=$_REQUEST['vcs'];
			$prervcs=number_format($row0['vcs'], 2, '.','');
			if ($prepvcs != $prervcs)
			{
				$uvcs=1;
			}
			else
			{
				$uvcs=0;
			}
			
			$prepdcc=$_REQUEST['dcc'];
			$prerdcc=number_format($row0['dcc'], 2, '.','');
			if ($prepdcc != $prerdcc)
			{
				$udcc=1;
			}
			else
			{
				$udcc=0;
			}
			
			/*var_dump($prepvcs);
			echo "<br>";
			var_dump($prervcs);
			echo "<br>";
			var_dump($prepdcc);
			echo "<br>";
			var_dump($prerdcc);
			echo "<br>";*/
			
			if ($uvcs==1 && $udcc==1)
			{
				$qry1 = "UPDATE recognized_digs SET vcs='".$prepvcs."',modvcs='".$uvcs."',dcc='".$prepdcc."',moddcc='".$udcc."' WHERE oroid='".$_REQUEST['oroid']."' and rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
				$res1 = mssql_query($qry1);
			}
			elseif ($uvcs==1)
			{
				$qry1 = "UPDATE recognized_digs SET vcs='".$prepvcs."',modvcs='".$uvcs."' WHERE oroid='".$_REQUEST['oroid']."' and rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
				$res1 = mssql_query($qry1);
			}
			elseif ($udcc==1)
			{
				$qry1 = "UPDATE recognized_digs SET dcc='".$prepdcc."',moddcc='".$udcc."' WHERE oroid='".$_REQUEST['oroid']."' and rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
				$res1 = mssql_query($qry1);
			}
			else
			{
				echo "No Changes Detected.";
			}

			//echo $qry1."<br>";
			admin_digrpt_pub_arch_view();
		//}
		//else
		//{
		//	echo "<b>Click the Confirmation checkbox and resubmit.</b>";
		//}
	}
	else
	{
		echo "<font color=\"red\"><b>Error</b></font><br>Recognized Dig Report for ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']." does not exist.";
	}
}

function digrpt_delete()
{
	$brdr=1;
	$qry0 = "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."' AND id='".$_REQUEST['rept_id']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0!=0)
	{
		if (isset($_REQUEST['confdel']) && $_REQUEST['confdel']==1)
		{
			$qry1 = "DELETE FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$_REQUEST['rept_mo']."' AND rept_yr='".$_REQUEST['rept_yr']."';";
			$res1 = mssql_query($qry1);

			dig_rep_lists();
		}
		else
		{
			echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\"	value=\"digrpt_delete\">\n";
			echo "						<input type=\"hidden\" name=\"confdel\"	value=\"1\">\n";
			echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$_REQUEST['rept_id']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$_REQUEST['rept_mo']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$_REQUEST['rept_yr']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$_REQUEST['rept_yr']."\">\n";
			echo "      			<td align=\"right\">Confirm Delete of ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']." Dig Report?\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Delete\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
			echo "   			</tr>\n";
			echo "			</table>\n";
		}
	}
	else
	{
		echo "<font color=\"red\"><b>Error</b></font><br>Report for ".$_REQUEST['rept_mo']."/".$_REQUEST['rept_yr']." does not exist.";
	}
}

function admin_gen_preview()
{
	$brdr=1;

	$qryA = "SELECT securityid,officeid,admindigreport FROM jest..security WHERE securityid = '".$_SESSION['securityid']."';";
	$resA = mssql_query($qryA);
	$rowA= mssql_fetch_array($resA);

	if (isset($rowA['admindigreport']) && $rowA['admindigreport'] >= 1)
	{
		admin_gen_new();
	}
	
	if (!empty($_REQUEST['d_moyr']))
	{
		$dis		="";
		$j_ids	=array();
		$d_date	=split(":",$_REQUEST['d_moyr']);
		$f_date	=$d_date[1]."/01/".$d_date[0];
		$actoff_ar=array();
		$enaoff_ar=array();
		$rptoff_ar=array();

		$qry0 = "SELECT * FROM digreport_admin WHERE rept_yr = '".$d_date[0]."' AND rept_mo = '".$d_date[1]."';";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		if ($nrow0 > 0)
		{
			$dis="DISABLED";
		}

		$qry0a = "SELECT officeid FROM offices WHERE active=1 and [grouping]=0 and endigreport!='2' ORDER BY name;";
		$res0a = mssql_query($qry0a);

		while ($row0a = mssql_fetch_array($res0a))
		{
			$actoff_ar[]=$row0a['officeid'];
		}

		$qry0b = "SELECT officeid FROM offices WHERE endigreport='1';";
		$res0b = mssql_query($qry0b);

		while ($row0b = mssql_fetch_array($res0b))
		{
			$enaoff_ar[]=$row0b['officeid'];
		}

		$qry0c = "SELECT id FROM digreport_main WHERE rept_yr = '".$d_date[0]."' AND rept_mo = '".$d_date[1]."' ORDER BY officeid ASC;";
		$res0c = mssql_query($qry0c);
		$nrow0c= mssql_num_rows($res0c);
		
		if ($_SESSION['securityid']==999999999999999999999)
		{
			echo $qry0c.'<br>';	
		}

		if ($nrow0c > 0)
		{
			while($row0c=mssql_fetch_array($res0c))
			{
				$rptoff_ar[]=$row0c['id'];
			}
		}

		if ($nrow0c > 0)
		{
			/*
			echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td valign=\"top\">\n";
			show_array_vars($actoff_ar);
			echo "					</td>\n";
			echo " 			  		<td valign=\"top\">\n";
			show_array_vars($enaoff_ar);
			echo "					</td>\n";
			echo " 			  		<td valign=\"top\">\n";
			show_array_vars($rptoff_ar);
			echo "					</td>\n";
			echo " 			  	</tr>\n";
			echo " 			  </table>\n";
			*/
			
			echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" NOWRAP><b></b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" NOWRAP><b>Office</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Type</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Digs</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Reno</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Addn</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Contracts</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Royalties</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Acct Fees</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Consult Fees</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Creator</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Created</b></td>\n";
			echo "   			</tr>\n";
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"admindigrpt_store\">\n";

			$jcnt	=0;
			$tcon	=0;
			$rcon	=0;
			$acon	=0;
			$ccon	=0;
			$tdig	=0;
			$tren	=0;
			$tadd	=0;
			$acc	=95;
			
			foreach($actoff_ar AS $n => $v)
			{
				//Loop Constants
				$digs	="";
				$rens	="";
				$addn	="";
				$tdcolor="gray_und";
				$clname	="";
				$camt	="";
				$fcamt	="";
				$froy	="";
				$facc	="";
				$fcon	="";
				$adate	="";
				$joid	="";
				$sid	="";
				$otype	="";
				//End Constants

				$qry1 = "SELECT officeid,name,encon FROM offices WHERE officeid='".$v."';";
				$res1 = mssql_query($qry1);
				$row1 = mssql_fetch_array($res1);
				$oname=$row1['name'];
				
				if ($row1['encon']!='1')
				{	
					$otype="F";
				}

				//Enabled Offices
				if (in_array($v,$enaoff_ar))
				{
					//Enabled Offices with Reports
					$qry1a 	= "SELECT * FROM digreport_main WHERE officeid='".$v."' AND rept_yr = '".$d_date[0]."' AND rept_mo = '".$d_date[1]."';";
					$res1a 	= mssql_query($qry1a);
					$row1a 	= mssql_fetch_array($res1a);
					$nrow1a	= mssql_num_rows($res1a);

					if (in_array($row1a['id'],$rptoff_ar))
					{
						$jcnt++;
						$qry2 	= "SELECT securityid,lname FROM security WHERE securityid='".$row1a['creator']."';";
						$res2 	= mssql_query($qry2);
						$row2 	= mssql_fetch_array($res2);
						$clname	=$row2['lname'];
						$sid		=$row2['securityid'];

						$joid		=$row1a['officeid'];
						//$tag		="YS1";
						$tag		="";
						$digs		=$row1a['no_digs'];
						$rens		=$row1a['no_rens'];
						$addn		=$row1a['no_addn'];
						$fcamt	=number_format($row1a['cont_total'], 2, '.', '');
						$froy		=number_format($row1a['admin_fee'], 2, '.', '');
						$facc		=number_format($row1a['acct_fee'], 2, '.', '');
						$fcon		=number_format($row1a['cons_fee'], 2, '.', '');
						$adate	=date("m/d/Y", strtotime($row1a['added']));
						$tdcolor	="wh_und";

						echo "						<input type=\"hidden\" name=\"oid_".$jcnt."\" value=\"".$joid."\">\n";
						echo "						<input type=\"hidden\" name=\"cto_".$jcnt."\" value=\"".$sid."\">\n";
						echo "						<input type=\"hidden\" name=\"ctr_".$jcnt."\" value=\"".$fcamt."\">\n";
						echo "						<input type=\"hidden\" name=\"roy_".$jcnt."\" value=\"".$froy."\">\n";
						echo "						<input type=\"hidden\" name=\"acc_".$jcnt."\" value=\"".$facc."\">\n";
						echo "						<input type=\"hidden\" name=\"con_".$jcnt."\" value=\"".$fcon."\">\n";
						echo "						<input type=\"hidden\" name=\"dig_".$jcnt."\" value=\"".$digs."\">\n";
						echo "						<input type=\"hidden\" name=\"ren_".$jcnt."\" value=\"".$rens."\">\n";
						echo "						<input type=\"hidden\" name=\"add_".$jcnt."\" value=\"".$addn."\">\n";
						echo "						<input type=\"hidden\" name=\"dat_".$jcnt."\" value=\"".$adate."\">\n";
					}
					else
					{
						//Enabled Offices without Reports
						$tag		="<font color=\"red\">!</font>";
						$tdcolor	="yel_und";
					}
				}
				else
				{
					//Disabled Offices with Reports
					$qry1a 	= "SELECT * FROM digreport_main WHERE officeid='".$v."' AND rept_yr = '".$d_date[0]."' AND rept_mo = '".$d_date[1]."';";
					$res1a 	= mssql_query($qry1a);
					$row1a 	= mssql_fetch_array($res1a);
					$nrow1a	= mssql_num_rows($res1a);

					if (in_array($row1a['id'],$rptoff_ar))
					{
						$jcnt++;
						$qry2 	= "SELECT securityid,lname FROM security WHERE securityid='".$row1a['creator']."';";
						$res2 	= mssql_query($qry2);
						$row2 	= mssql_fetch_array($res2);
						$clname	=$row2['lname'];
						$sid		=$row2['securityid'];

						$joid		=$row1a['officeid'];
						//$tag		="YS2";
						$tag		="";
						$digs		=$row1a['no_digs'];
						$rens		=$row1a['no_rens'];
						$addn		=$row1a['no_addn'];
						$fcamt	=number_format($row1a['cont_total'], 2, '.', '');
						$froy		=number_format($row1a['admin_fee'], 2, '.', '');
						$facc		=number_format($row1a['acct_fee'], 2, '.', '');
						$fcon		=number_format($row1a['cons_fee'], 2, '.', '');
						$adate	=date("m/d/Y", strtotime($row1a['added']));
						$tdcolor	="gray_und";

						echo "						<input type=\"hidden\" name=\"oid_".$jcnt."\" value=\"".$joid."\">\n";
						echo "						<input type=\"hidden\" name=\"cto_".$jcnt."\" value=\"".$sid."\">\n";
						echo "						<input type=\"hidden\" name=\"ctr_".$jcnt."\" value=\"".$fcamt."\">\n";
						echo "						<input type=\"hidden\" name=\"roy_".$jcnt."\" value=\"".$froy."\">\n";
						echo "						<input type=\"hidden\" name=\"acc_".$jcnt."\" value=\"".$facc."\">\n";
						echo "						<input type=\"hidden\" name=\"con_".$jcnt."\" value=\"".$fcon."\">\n";
						echo "						<input type=\"hidden\" name=\"dig_".$jcnt."\" value=\"".$digs."\">\n";
						echo "						<input type=\"hidden\" name=\"ren_".$jcnt."\" value=\"".$rens."\">\n";
						echo "						<input type=\"hidden\" name=\"add_".$jcnt."\" value=\"".$addn."\">\n";
						echo "						<input type=\"hidden\" name=\"dat_".$jcnt."\" value=\"".$adate."\">\n";
					}
					else
					{
						//Disabled Offices without Reports
						//$tag		="NO3";
						$tag		="";
						$tdcolor="gray_und";
					}
				}

				echo "   			<tr>\n";
				//echo " 			  		<td class=\"".$tdcolor."\" align=\"left\" valign=\"bottom\">(".$tag.") ($v) (".$joid.")</td>\n";
				echo " 			  		<td class=\"".$tdcolor."\" align=\"center\" valign=\"bottom\"><b>".$tag."</b></td>\n";
				echo " 			  		<td class=\"".$tdcolor."\" align=\"left\" valign=\"bottom\">".$oname."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$otype."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$digs."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$rens."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$addn."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$fcamt."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$froy."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$facc."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$fcon."</td>\n";
				echo " 			  		<td align=\"left\" valign=\"bottom\">".$clname."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$adate."</td>\n";
				echo "   			</tr>\n";
				$tcon=$tcon+$fcamt;
				$rcon=$rcon+$froy;
				$acon=$acon+$facc;
				$ccon=$ccon+$fcon;
				$tdig=$tdig+$digs;
				$tren=$tren+$rens;
				$tadd=$tadd+$addn;
			}

			$fttcon	=number_format($tcon, 2, '.', ',');
			$ftrcon	=number_format($rcon, 2, '.', ',');
			$ftacon	=number_format($acon, 2, '.', ',');
			$ftccon	=number_format($ccon, 2, '.', ',');

			echo "   			<tr>\n";
			echo " 			  		<td colspan=\"10\" class=\"gray\" align=\"left\" valign=\"bottom\"></td>\n";
			echo "				</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"><b>Totals</b></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b></b></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b>".$tdig."</b></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b>".$tren."</b></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"center\" valign=\"bottom\"><b>".$tadd."</b></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$fttcon."</b></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$ftrcon."</b></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$ftacon."</b></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"right\" valign=\"bottom\"><b>".$ftccon."</b></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
			echo " 			  		<td class=\"gray_dblund\" align=\"left\" valign=\"bottom\"></td>\n";
			echo "			</table>\n";
			echo "			<br>\n";
			echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"left\" valign=\"bottom\"><b>Dig Report Summary</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\"></td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Month:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".date("F",strtotime($f_date))."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Digs:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$tdig."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Reno:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$tren."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Addn:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$tadd."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Contracts:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$fttcon."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Royalty:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$ftrcon."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Accounting Fee:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$ftacon."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Consulting Fee:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$ftccon."</td>\n";
			echo "   			</tr>\n";
			echo "						<input type=\"hidden\" name=\"rept_mo\" value=\"".$d_date[1]."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\" value=\"".$d_date[0]."\">\n";
			echo "						<input type=\"hidden\" name=\"tdig\" value=\"".$tdig."\">\n";
			echo "						<input type=\"hidden\" name=\"tren\" value=\"".$tren."\">\n";
			echo "						<input type=\"hidden\" name=\"tadd\" value=\"".$tadd."\">\n";
			echo "						<input type=\"hidden\" name=\"tctr\" value=\"".$fttcon."\">\n";
			echo "						<input type=\"hidden\" name=\"troy\" value=\"".$ftrcon."\">\n";
			echo "						<input type=\"hidden\" name=\"tacc\" value=\"".$ftacon."\">\n";
			echo "						<input type=\"hidden\" name=\"tcon\" value=\"".$ftccon."\">\n";
			echo "						<input type=\"hidden\" name=\"rsid\" value=\"".$_SESSION['securityid']."\">\n";
			
			if (isset($rowA['admindigreport']) && $rowA['admindigreport'] >= 2)
			{
				echo "   			<tr>\n";
				echo " 			  		<td colspan=\"2\" align=\"right\" valign=\"bottom\"> Validate and Save Report: \n";
				echo "						<input class=\"checkbox\" type=\"checkbox\" name=\"valdigs\" value=\"1\" ".$dis.">\n";
				echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Save\" ".$dis.">\n";
				echo "					</td>\n";
				echo "   			</tr>\n";
			}
			
			echo "   			</form>\n";
			echo "			</table>\n";
		}
		else
		{
			echo "<br>No Stored Dig Reports found for ".$d_date[0]."/".$d_date[1] ." Period";
		}
	}
}

function calc_royalty_digreport($a) {
	$t=100000;
	$r=0;
	
	if ($a > $t) {
		$g=($a - $t) * .01;
		$b=$t * .03;
		$r=$b + $g;
		
		if ($_SESSION['securityid']==26) {
			echo 'over: '.$g.'<br>';
			echo 'under: '.$b.'<br>';
			echo 'total: '.$r.'<br>';
		}
	}
	else {
		$r=($a * .03);
	}
	
	return $r;
}

function gen_preview()
{
	// Dig Report Create Tool for Estimating System Offices
	$brdr		=1;
	$fcnt		=1;
	$secids	=get_secids();

	//show_array_vars($secids);

	$mgcar=array();

	$qry0pre = "SELECT estgrp,masgrp,abrev FROM material_grp_codes WHERE estgrp!='0' AND active='1' ORDER BY estgrp ASC;";
	$res0pre = mssql_query($qry0pre);

	while($row0pre= mssql_fetch_array($res0pre))
	{
		$mgcar[]=$row0pre['abrev'];
	}

	$mgcar=array_flip($mgcar);

	$qry0 = "SELECT consfee,acctfee,pacctfee,all_code FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	//print_r($row0);

	gen_new();

	if (isset($_REQUEST['d_moyr']))
	{
		$dis		="";
		$j_ids	=array();
		$d_date	=split(":",$_REQUEST['d_moyr']);
		$f_date	=$d_date[1]."/01/".$d_date[0];
		$l_date	=$d_date[1]."/".date("t",strtotime($f_date))."/".$d_date[0];
		
		//$qry0a = "SELECT * FROM bonus_schedule_config WHERE psdate <= '".$f_date."' AND pedate >= '".$l_date."';";
		$qry0a = "SELECT * FROM bonus_schedule_config WHERE active=1;";
		$res0a = mssql_query($qry0a);
		$row0a = mssql_fetch_array($res0a);
		$nrow0a= mssql_num_rows($res0a);
		
		if ($nrow0a==0)
		{
			echo "<center>\n";
			echo "<font color=\"red\">Error!</font><br>Schedule not configured for that Period.<br>Contact Management";
			echo "</center>\n";
			exit;
		}
		
		//echo $qry0a."<br>";	
		//echo $row0a['brept_yr']."<br>";

		$qry  = "SELECT a.njobid,a.digdate,b.securityid,b.lname,b.mas_div,a.tgp,a.comm,a.ovcommission,a.jcost,a.renov ";
		$qry .= "FROM jobs AS a ";
		$qry .= "inner join security AS b ON a.securityid=b.securityid ";
		//$qry .= "WHERE a.officeid='".$_SESSION['officeid']."' AND a.renov=0 AND a.digdate BETWEEN '".$f_date."' AND '".$l_date." 23:59:59' ";
		//$qry .= "WHERE a.officeid='".$_SESSION['officeid']."' AND a.digdate BETWEEN '".$f_date."' AND '".$l_date." 23:59:59' ";
		$qry .= "WHERE a.officeid='".$_SESSION['officeid']."' AND a.digdate BETWEEN '".$f_date."' AND '".$l_date." 23:59:59' AND njobid!='0' ";
		$qry .= "ORDER BY a.renov,b.mas_div,a.njobid;";
		$res	= mssql_query($qry);
		$nrow = mssql_num_rows($res);

		$qry1 = "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_yr='".$d_date[0]."' AND rept_mo='".$d_date[1]."';";
		$res1 = mssql_query($qry1);
		$nrow1= mssql_num_rows($res1);

		//echo $qry."<br>";

		if ($nrow1 > 0)
		{
			$dis="DISABLED";
			//$dis="";
		}

		if ($nrow > 0)
		{
			if ($_SESSION['rlev'] >=8)
			{
				$cspan=14;
			}
			else
			{
				$cspan=12;
			}
			
			echo "<br>\n";
			echo "			<table class=\"outer\" width=\"950px\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
			echo " 			  		<td class=\"ltgray_und\" colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"><b>Digs</b></td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" NOWRAP><b></b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Job ID</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" width=\"160px\" NOWRAP><b>Customer</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Contract Date</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Dig Date</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" width=\"80px\" NOWRAP><b>Salesman</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Contract Amt</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Addn</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Addendum</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Allowance</b></td>\n";

			if ($_SESSION['rlev'] >= 8)
			{
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Acct Fee</b></td>\n";
				echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Consult Fee</b></td>\n";
			}

			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Royalty</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>GP</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Est Cost</b></td>\n";
			echo "   			</tr>\n";
			
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"digrpt_store\">\n";

			$tcnt	=0;
			$jcnt	=0;
			$rcnt	=0;
			$tcon	=0;
			$rcon	=0;
			$acon	=0;
			$ccon	=0;
			$dcon	=0;
			$lcon	=0;
			$lcos	=0;
			$ecos	=0;
			$reno =0;
			$tren =0;

			$consf=$row0['consfee'];
			$acctf=$row0['acctfee'];
			while($row=mssql_fetch_array($res))
			{
				if ($row['renov']==0)
				{
					$jcnt++;
				}
				elseif ($row['renov']==1)
				{
					$rcnt++;
				}
				
				$fcnt++;
				$tcnt++;
				$ctr		=0;
				$jaddamt	=0;
				$addn		=0;
				$all		=0;
				$adp		=0;

				$qryA 	= "SELECT cid,clname,cfname FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."';";
				$resA 	= mssql_query($qryA);
				$rowA		= mssql_fetch_array($resA);

				$qryB 	= "SELECT contractamt,contractdate,pft,sqft FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd=0;";
				$resB 	= mssql_query($qryB);
				$rowB		= mssql_fetch_array($resB);

				if ($row['renov']==1)
				{
					$acc	=0;
					$consf	=0;
				}
				else
				{
					if (strtotime($rowB['contractdate']) >=  strtotime('04/01/06'))
					{
						$acc		=$row0['pacctfee'];
					}
					else
					{
						if ($_SESSION['officeid']==55)
						{
							$acc		=105;
						}
						else
						{
							$acc		=95;
						}
					}
				}

				$qryBa = "SELECT njobid,raddnpr_man,post_add FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!=0;";
				$resBa = mssql_query($qryBa);
				$nrowBa= mssql_num_rows($resBa);

				$ctr=number_format($rowB['contractamt'], 2, '.', '');

				if ($nrowBa >= 1)
				{
					$qryBb = "SELECT raddnpr_man,post_add FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!='0';";
					$resBb = mssql_query($qryBb);

					$addn		=$nrowBa;
					while ($rowBb = mssql_fetch_array($resBb))
					{
						$jaddamt=$jaddamt+$rowBb['raddnpr_man'];
					}
				}

				//$qryC 	= "SELECT comm,ovcommision FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."';";
				//$resC 	= mssql_query($qryC);
				//$rowC	= mssql_fetch_array($resC);

				//$tcomm	= $rowC['comm']+$rowC['ovcommission'];

				$qryD = "SELECT njobid,dbid,bidamt FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND dbid='".$row0['all_code']."';";
				$resD = mssql_query($qryD);
				$rowD = mssql_fetch_array($resD);
				$nrowD= mssql_num_rows($resD);

				if ($nrowD > 0)
				{
					$all=$all+$rowD['bidamt'];
				}

				$roy	=calc_royalty_digreport((($ctr+$jaddamt)-$all));
				
				if ($_SESSION['securityid']==26) {
					echo 'ROY: '.$roy.'<br>';
				}

				$fjaddamt		=number_format($jaddamt, 2, '.', '');
				$fctr 			=number_format($ctr, 2, '.', '');
				$froy			=number_format(round($roy), 2, '.', '');
				$facc			=number_format($acc, 2, '.', '');
				$fall			=number_format($all, 2, '.', '');
				$fadp			=number_format($adp, 2, '.', '');
				$fcon			=number_format($consf, 2, '.', '');
				//$fcon	=number_format(0, 2, '.', '');
				
				$ccos	=$row['jcost'];

				if (isset($row['tgp']))
				{
					$tgp=round($row['tgp']*100);
				}
				else
				{
					$tgp=0;
				}

				if (isset($row['comm']))
				{
					$tcomm=$row['comm']+$row['ovcommission'];
				}
				else
				{
					$tcomm=0;
				}

				$cdate	=date("m/d/Y", strtotime($rowB['contractdate']));
				$fdate	=date("m/d/Y", strtotime($row['digdate']));

				$qryC 	= "SELECT lname,fname,mas_div,rmas_div FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$row['securityid']."';";
				$resC 	= mssql_query($qryC);
				$rowC	= mssql_fetch_array($resC);

				//$destidret	=disp_mas_div_jobid($rowC['mas_div'],$row['njobid']);
				if ($row['renov']==1)
				{
					$destidret	=disp_mas_div_jobid($rowC['rmas_div'],$row['njobid']);
				}
				else
				{
					$destidret	=disp_mas_div_jobid($rowC['mas_div'],$row['njobid']);
				}
				
				if ($row['tgp']!=0)
				{
					$ecost=($ctr+$jaddamt) - (($ctr+$jaddamt) * $row['tgp']);
				}
				else
				{
					$ecost=0;
				}

				// per Job Data Points
				echo "						<input type=\"hidden\" name=\"jid_".$tcnt."\" value=\"".$destidret[0]."\">\n";
				echo "						<input type=\"hidden\" name=\"cid_".$tcnt."\" value=\"".$rowA['cid']."\">\n";
				echo "						<input type=\"hidden\" name=\"ctr_".$tcnt."\" value=\"".$fctr."\">\n";
				echo "						<input type=\"hidden\" name=\"roy_".$tcnt."\" value=\"".$froy."\">\n";
				echo "						<input type=\"hidden\" name=\"acc_".$tcnt."\" value=\"".$facc."\">\n";
				echo "						<input type=\"hidden\" name=\"con_".$tcnt."\" value=\"".$fcon."\">\n";
				echo "						<input type=\"hidden\" name=\"ddt_".$tcnt."\" value=\"".$fdate."\">\n";
				echo "						<input type=\"hidden\" name=\"dct_".$tcnt."\" value=\"".$cdate."\">\n";
				echo "						<input type=\"hidden\" name=\"sid_".$tcnt."\" value=\"".$row['securityid']."\">\n";
				echo "						<input type=\"hidden\" name=\"cln_".$tcnt."\" value=\"".$rowA['clname']."\">\n";
				echo "						<input type=\"hidden\" name=\"add_".$tcnt."\" value=\"".$addn."\">\n";
				echo "						<input type=\"hidden\" name=\"djd_".$tcnt."\" value=\"".$row['njobid']."\">\n";
				echo "						<input type=\"hidden\" name=\"all_".$tcnt."\" value=\"".$fall."\">\n";
				echo "						<input type=\"hidden\" name=\"adp_".$tcnt."\" value=\"".$fjaddamt."\">\n";
				echo "						<input type=\"hidden\" name=\"per_".$tcnt."\" value=\"".$rowB['pft']."\">\n";
				echo "						<input type=\"hidden\" name=\"sur_".$tcnt."\" value=\"".$rowB['sqft']."\">\n";
				echo "						<input type=\"hidden\" name=\"tgp_".$tcnt."\" value=\"".$tgp."\">\n";
				echo "						<input type=\"hidden\" name=\"com_".$tcnt."\" value=\"".$tcomm."\">\n";
				echo "						<input type=\"hidden\" name=\"cos_".$tcnt."\" value=\"".$ecost."\">\n";
				echo "						<input type=\"hidden\" name=\"ren_".$tcnt."\" value=\"".$row['renov']."\">\n";
				
				if ($tren != $row['renov'])
				{
					echo "   			<tr>\n";
					echo " 			  		<td align=\"left\" valign=\"bottom\"></td>\n";
					echo " 			  		<td colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"></td>\n";
					echo "   			</tr>\n";
					echo "   			<tr>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
					echo " 			  		<td class=\"ltgray_und\" colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"><b>Renovations</b></td>\n";
					echo "   			</tr>\n";
					echo "   			<tr>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" NOWRAP><b></b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Job ID</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" width=\"160px\" NOWRAP><b>Customer</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Contract Date</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Dig Date</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" width=\"80px\" NOWRAP><b>Salesman</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Contract Amt</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Addn</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Addendum</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Allowance</b></td>\n";
		
					if ($_SESSION['rlev'] >= 8)
					{
						echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Acct Fee</b></td>\n";
						echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Consult Fee</b></td>\n";
					}
		
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Royalty</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>GP</b></td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" NOWRAP><b>Est Cost</b></td>\n";
					echo "   			</tr>\n";
				}
				
				echo "   			<tr>\n";
				
				if ($row['renov']==0)
				{
					echo " 			  		<td align=\"right\" valign=\"bottom\"><b>".$jcnt."</b></td>\n";
				}
				else
				{
					echo " 			  		<td align=\"right\" valign=\"bottom\"><b>".$rcnt."</b></td>\n";
				}
				
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$destidret[0]."</td>\n";
				echo " 			  		<td align=\"left\" valign=\"bottom\">".$rowA['clname']."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$cdate."</td>\n";
				echo " 			  		<td align=\"center\" valign=\"bottom\">".$fdate."</td>\n";
				echo " 			  		<td align=\"left\" valign=\"bottom\">\n";

				if ($_SESSION['rlev'] >=8)
				{
					echo "						<select name=\"sln_".$tcnt."\">\n";

					foreach ($secids AS $n=>$v)
					{
						if ($row['securityid']==$v[0])
						{
							echo "							<option value=\"".$v[0]."\" SELECTED>".$v[1].", ".$v[2]."</option>\n";
						}
						else
						{
							echo "							<option value=\"".$v[0]."\">".$v[1].", ".$v[2]."</option>\n";
						}
					}

					echo "						</select>\n";
				}
				else
				{
					echo $rowC['lname'].", ".$rowC['fname'];
					echo "						<input type=\"hidden\" name=\"sln_".$tcnt."\" value=\"".$rowC['securityid']."\">\n";
				}

				echo "					</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$fctr."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$addn."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$fjaddamt."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$fall."</td>\n";

				if ($_SESSION['rlev'] >= 8)
				{
					echo " 			  		<td align=\"right\" valign=\"bottom\">".$facc."</td>\n";
					echo " 			  		<td align=\"right\" valign=\"bottom\">".$fcon."</td>\n";
				}

				echo " 			  		<td align=\"right\" valign=\"bottom\">".$froy."</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".$tgp."%</td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\">".number_format($ecost)."</td>\n";
				echo "   			</tr>\n";

				$tcon=$tcon+$ctr;
				$rcon=$rcon+$froy;
				$acon=$acon+$acc;
				$dcon=$dcon+$fjaddamt;
				$lcon=$lcon+$fall;
				$ccon=$ccon+$consf;
				$lcos=$lcos+$ccos;
				$ecos=$ecos+$ecost;
				//$acc=0;

				if (is_array($mgcar))
				{
					$ttext	="No Descriptive";
					echo "   			<tr style=\"display:none;\">\n";
					echo " 			  		<td align=\"right\" width=\"20px\"><b></b></td>\n";
					echo " 			  		<td align=\"left\" colspan=\"14\">\n";
					echo "						<table border=\"0\">\n";
					echo "   						<tr>\n";
					echo " 						  		<td align=\"center\" width=\"30px\"><b></b></td>\n";

					foreach ($mgcar AS $n => $v)
					{
						$qry2 = "SELECT abrev,name FROM material_grp_codes WHERE abrev='".$n."';";
						$res2 = mssql_query($qry2);
						$nrow2= mssql_num_rows($res2);

						if ($nrow2 > 0)
						{
							$row2= mssql_fetch_array($res2);
							$ttext=$row2['name'];
						}

						if (isset($_REQUEST[$n.$jcnt]) && $_REQUEST[$n.$jcnt]==1)
						{
							echo " 			  				<td align=\"center\">".$n."<br><input class=\"checkboxgry\" type=\"checkbox\" name=\"".$n.$jcnt."\" value=\"1\" title=\"".$ttext."\" CHECKED></td>\n";
						}
						else
						{
							echo " 			  				<td align=\"center\">".$n."<br><input class=\"checkboxgry\" type=\"checkbox\" name=\"".$n.$jcnt."\" value=\"1\" title=\"".$ttext."\" ></td>\n";
						}
					}

					echo "   						</tr>\n";
					echo "						</table>\n";
					echo "					</td>\n";
					echo "   			</tr>\n";
					$tren=$row['renov'];
				}

			}

			$fttcon	=number_format($tcon, 2, '.', ',');
			$ftrcon	=number_format($rcon, 2, '.', ',');
			$ftacon	=number_format($acon+$acctf, 2, '.', ',');
			$ftmcon	=number_format($acctf, 2, '.', ',');
			$ftccon	=number_format($ccon, 2, '.', ',');
			$ftdcon	=number_format($dcon, 2, '.', ',');
			$ftlcon	=number_format($lcon, 2, '.', ',');
			$ftlcos	=number_format($lcos, 2, '.', ',');
			$ftecos	=number_format($ecos);

			echo "			</table>\n";
			echo "<br>\n";
			echo "			<table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"left\" valign=\"bottom\"><b>Dig Report Summary for ".$_SESSION['offname']."</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\"></td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Month:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".date("F",strtotime($f_date))."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Digs:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$jcnt."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Renovations:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$rcnt."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Contracts:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$fttcon."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Addendums:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$ftdcon."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Allowances:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$ftlcon."</td>\n";
			echo "   			</tr>\n";

			if ($_SESSION['rlev'] >= 8)
			{
				echo "   			<tr>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Accounting Fee:</b></td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$ftacon."</td>\n";
				echo "   			</tr>\n";
				echo "   			<tr>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Consulting Fee:</b></td>\n";
				echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$ftccon."</td>\n";
				echo "   			</tr>\n";
			}

			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Royalty:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$ftrcon."</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\"><b>Total Estimated Cost:</b></td>\n";
			echo " 			  		<td align=\"right\" valign=\"bottom\" width=\"100px\">".$ftecos."</td>\n";
			echo "   			</tr>\n";
			
			echo "						<input type=\"hidden\" name=\"rept_mo\" value=\"".$d_date[1]."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\" value=\"".$d_date[0]."\">\n";
			echo "						<input type=\"hidden\" name=\"brept_yr\" value=\"".$row0a['brept_yr']."\">\n";
			echo "						<input type=\"hidden\" name=\"tdig\" value=\"".$jcnt."\">\n";
			echo "						<input type=\"hidden\" name=\"rdig\" value=\"".$rcnt."\">\n";
			echo "						<input type=\"hidden\" name=\"tctr\" value=\"".$fttcon."\">\n";
			echo "						<input type=\"hidden\" name=\"troy\" value=\"".$ftrcon."\">\n";
			echo "						<input type=\"hidden\" name=\"tacc\" value=\"".$ftacon."\">\n";
			echo "						<input type=\"hidden\" name=\"mtacc\" value=\"".$ftmcon."\">\n";
			echo "						<input type=\"hidden\" name=\"tcon\" value=\"".$ftccon."\">\n";
			echo "						<input type=\"hidden\" name=\"tadp\" value=\"".$ftdcon."\">\n";
			echo "						<input type=\"hidden\" name=\"tall\" value=\"".$ftlcon."\">\n";
			echo "						<input type=\"hidden\" name=\"tcos\" value=\"".$ftlcos."\">\n";
			echo "						<input type=\"hidden\" name=\"ecos\" value=\"".$ftecos."\">\n";
			echo "						<input type=\"hidden\" name=\"rsid\" value=\"".$_SESSION['securityid']."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td colspan=\"2\" align=\"right\" valign=\"bottom\"> Validate and Save Report: \n";
			echo "						<input class=\"checkbox\" type=\"checkbox\" name=\"valdigs\" value=\"1\" ".$dis.">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Save\" ".$dis.">\n";
			echo "					</td>\n";
			echo "   			</tr>\n";
			echo "   			</form>\n";
			echo "			</table>\n";
		}
		else
		{
			//echo "No Digs found for ".$f_date." to ".$l_date;

			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"digrpt_store_nodigs\">\n";
			echo "						<input type=\"hidden\" name=\"rept_mo\" value=\"".$d_date[1]."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\" value=\"".$d_date[0]."\">\n";
			echo "						<input type=\"hidden\" name=\"brept_yr\" value=\"".$row0a['brept_yr']."\">\n";
			echo "						<input type=\"hidden\" name=\"tdig\" value=\"0\">\n";
			echo "						<input type=\"hidden\" name=\"tctr\" value=\"0\">\n";
			echo "						<input type=\"hidden\" name=\"troy\" value=\"0\">\n";
			echo "						<input type=\"hidden\" name=\"tacc\" value=\"0\">\n";
			echo "						<input type=\"hidden\" name=\"tall\" value=\"0\">\n";
			echo "						<input type=\"hidden\" name=\"tadp\" value=\"0\">\n";
			echo "						<input type=\"hidden\" name=\"rsid\" value=\"".$_SESSION['securityid']."\">\n";
			echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td colspan=\"2\" align=\"right\" valign=\"bottom\">No Digs found for <b>".$f_date."</b> to <b>".$l_date."</b>. Save to Report?";
			echo "						<input class=\"checkbox\" type=\"checkbox\" name=\"valdigs\" value=\"1\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Submit\">\n";
			echo "					</td>\n";
			echo "   			</tr>\n";
			echo "   			</form>\n";
			echo "			</table>\n";
		}
	}
}

function admin_gen_new()
{
	$brdr	=0;
	$cyr	=date("Y");
	$strtyr	=date("Y")-3;
	$stopyr	=date("Y");

	$qryA = "SELECT securityid,officeid,admindigreport FROM jest..security WHERE securityid = '".$_SESSION['securityid']."';";
	$resA = mssql_query($qryA);
	$rowA= mssql_fetch_array($resA);

	if (!empty($_REQUEST['d_moyr']))
	{
		$d_date	=explode(":",$_REQUEST['d_moyr']);
	}
	else
	{
		$d_date	=array(0,0);
	}

	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td align=\"left\" valign=\"bottom\">\n";
	
	if (isset($rowA['admindigreport']) && $rowA['admindigreport'] >= 2)
	{
		echo "						<b>Generate New Admin Dig Report</b>";
	}
	else
	{
		echo "						<b>Enterprise Dig Report</b>";
	}
	
	echo "					</td>\n";
	echo " 			  		<td align=\"left\" valign=\"bottom\">\n";
	echo "					</td>\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"admingen_preview\">\n";
	echo "						<input type=\"hidden\" name=\"access\" value=\"".$_REQUEST['access']."\">\n";
	echo "			   	<td align=\"center\" valign=\"bottom\">\n";
	echo "					<b>".date('m/d/Y h:iA T')."</b>\n";
	echo "				</td>\n";
	echo "			   	<td align=\"right\" valign=\"bottom\">Month/Year: \n";
	echo "			   		<select name=\"d_moyr\" onChange=\"this.form.submit();\">\n";

	//if (isset) for ($y=$stopyr+1;$y >= $strtyr;$y--)

	for ($y=$stopyr+1;$y >= $strtyr;$y--)
	{
		for ($m=12;$m >= 1;$m--)
		{
			$m=str_pad($m, 2, "0", STR_PAD_LEFT);
			if ($m==$d_date[1] && $y==$d_date[0])
			{
				echo "			   			<option value=\"".$y.":".$m."\" SELECTED>".$m." / ".$y."</option>\n";
			}
			else
			{
				echo "			   			<option value=\"".$y.":".$m."\">".$m." / ".$y."</option>\n";
			}
		}
	}

	echo "			   		</select>\n";
	//echo "   				</td>\n";
	//echo "      			<td align=\"right\" valign=\"bottom\">\n";
	//	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Preview\">\n";
	echo "   				</td>\n";
	echo "         		</form>\n";
	echo "		   	</tr>\n";
	echo "			</table>\n";
}

function gen_new()
{
	$brdr	=0;

	$currentStartDtTm = strtotime(date("Y-m-01") . "+3 months");
	$currentDtTm = $currentStartDtTm;


	if (!empty($_REQUEST['d_moyr']))
	{
		$d_date	=explode(":",$_REQUEST['d_moyr']);
	}
	else
	{
		$d_date	=array(0,0);
	}

	//echo $_REQUEST['d_moyr']."<br>";
	echo "			<table class=\"outer\" width=\"950px\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td align=\"left\" valign=\"bottom\">\n";
	echo "					<b>Create New Dig Report</b>";
	echo "					</td>\n";
	echo " 			  		<td align=\"left\" valign=\"bottom\">\n";
	echo "					</td>\n";
	echo "			   		<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "			   		<td align=\"right\">Month and Year: \n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
	echo "							<input type=\"hidden\" name=\"subq\" value=\"gen_preview\">\n";
	echo "			   				<select name=\"d_moyr\">\n";

	for ($count=36;$count >= 0;$count--)
	{
		$y = date("Y", $currentDtTm);
		$m = date("m", $currentDtTm);

		if ($m==$d_date[1] && $y==$d_date[0])
		{
			echo "			   			<option value=\"".$y.":".$m."\" SELECTED>".$m."/".$y."</option>\n";
		}
		else
		{
			echo "			   			<option value=\"".$y.":".$m."\">".$m."/".$y."</option>\n";
		}

		$currentDtTm = strtotime("-1 months", date($currentDtTm));
	}

	echo "			   				</select>\n";
	echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Preview\">\n";
	echo "         				</form>\n";
	echo "   				</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
}


function admin_dig_rep_lists() {
	$cyr=date("Y");
	$qry0 = "SELECT DISTINCT(rept_yr) AS pyr FROM digreport_admin ORDER BY rept_yr;";
	$res0 = mssql_query($qry0);
	//$row0 = mssql_fetch_array($res0);

	if (isset($_REQUEST['pyr'])) {
		//echo "YR set<br>";
		$ryr = $_REQUEST['pyr'];
	}
	else {
		$ryr = $cyr;
	}

	$qry = "SELECT * FROM digreport_admin ORDER BY rept_yr DESC,rept_mo DESC;";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	$brdr=0;

	if ($_SESSION['rlev'] >= 8) {
		$cspan=13;
		$mspan=13-2;
	}
	else {
		$cspan=11;
		$mspan=11-2;
	}

	if ($nrow > 0) {
		echo "			<table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
		echo "   			<tr>\n";
		echo " 			  		<td class=\"gray\" colspan=\"".$mspan."\" align=\"left\" valign=\"bottom\"><b>Stored Admin Registered Dig Reports</b></td>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
		echo "									<input type=\"hidden\" name=\"subq\" value=\"admingen_new\">\n";
		echo "      			<td class=\"gray\" colspan=\"2\" align=\"center\">\n";
		echo "						<input class=\"transnb\" type=\"image\" src=\"images/action_add.gif\" alt=\" Create Report\">\n";
		echo "					</td>\n";
		echo "         					</form>\n";
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Month/Year</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Digs</b></td>\n";
		//echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Contracts</b></td>\n";
		//echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Addendums</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Total Contracts</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Royalty</b></td>\n";

		if ($_SESSION['rlev'] >= 8) {
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Acct Fee</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Consult Fee</b></td>\n";
		}

		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Report Date</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Creator</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">Analyze</td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">Historical</td>\n";
		echo " 			  		<td class=\"ltgray_und\" colspan=\"3\" align=\"center\" valign=\"bottom\">Publishing</td>\n";
		echo "   			</tr>\n";

		$ftcamt=0;
		$ftaamt=0;
		$ftsamt=0;
		$fttamt=0;
		$ftdigs=0;
		$rcnt=0;
		while($row=mssql_fetch_array($res)) {
			$rcnt++;
			
			if ($rcnt%2)
			{
				$tbg='white';
			}
			else
			{
				$tbg='gray';
			}
			
			$mrid=md5($row['id']);

			$qryA = "SELECT lname,fname,mas_div FROM security WHERE securityid='".$row['securityid']."';";
			$resA = mssql_query($qryA);
			$rowA	= mssql_fetch_array($resA);

			$m=str_pad($row['rept_mo'], 2, "0", STR_PAD_LEFT);
			$cdate=date("m/d/Y", strtotime($row['added']));
			$fcamt=number_format($row['cont_total'], 2, '.', ',');
			$faamt=number_format($row['admin_fee'], 2, '.', ',');
			$fsamt=number_format($row['cons_fee'], 2, '.', ',');
			$ftamt=number_format($row['acct_fee'], 2, '.', ',');

			echo "		   	<tr>\n";
			echo " 			  		<td class=\"".$tbg."\" align=\"right\" valign=\"bottom\">".$rcnt.".</td>\n";
			echo " 			  		<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">".$m."/".$row['rept_yr']."</td>\n";
			echo " 			  		<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">".$row['no_digs']."</td>\n";
			echo " 			  		<td class=\"".$tbg."\" align=\"right\" valign=\"bottom\">".$fcamt."</td>\n";
			echo " 			  		<td class=\"".$tbg."\" align=\"right\" valign=\"bottom\">".$faamt."</td>\n";

			if ($_SESSION['rlev'] >= 8)
			{
				echo " 			  		<td class=\"".$tbg."\" align=\"right\" valign=\"bottom\">".$ftamt."</td>\n";
				echo " 			  		<td class=\"".$tbg."\" align=\"right\" valign=\"bottom\">".$fsamt."</td>\n";
			}

			echo " 			  	<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">".$cdate."</td>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"left\" valign=\"bottom\">".$rowA['lname'].", ".$rowA['fname']."</td>\n";
			echo "      		<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\"><a target=\"new\" href=\"./fill_digdetail.php?mo=".$row['rept_mo']."&yr=".$row['rept_yr']."\"><img src=\"../images/search.gif\"></a>\n";
			echo "				</td>\n";
			echo "      		<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">\n";
			echo "         			<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_hist\">\n";
			echo "						<input type=\"hidden\" name=\"print\" 	value=\"1\">\n";
			echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$row['id']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$row['rept_mo']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$row['rept_yr']."\">\n";
			echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"Historical View\">\n";
			echo "         			</form>\n";
			echo "				</td>\n";
			echo "      		<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\" title=\"Check this box to Confirm\">\n";
			echo "         			<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_delete\">\n";
			echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$row['id']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$row['rept_mo']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$row['rept_yr']."\">\n";
			echo "						<input class=\"transnb\" type=\"checkbox\" name=\"confdel\" value=\"1\">\n";
			echo "						<input class=\"transnb\" type=\"image\" src=\"images/action_delete.gif\" alt=\"Delete Report\">\n";
			echo "         			</form>\n";
			echo "				</td>\n";
			echo "      		<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">\n";
			echo "         			<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\"	value=\"admindigrpt_view\">\n";
			echo "						<input type=\"hidden\" name=\"print\" 	value=\"1\">\n";
			echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$row['id']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$row['rept_mo']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$row['rept_yr']."\">\n";
			echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"Publishing View\">\n";
			echo "         			</form>\n";
			echo "				</td>\n";
			echo "		   	</tr>\n";
			$ftcamt=$ftcamt+$row['cont_total'];
			$ftaamt=$ftaamt+$row['admin_fee'];
			$ftsamt=$ftsamt+$row['cons_fee'];
			$fttamt=$fttamt+$row['acct_fee'];
			$ftdigs=$ftdigs+$row['no_digs'];

		}

		/*
		$ftcamt=number_format($ftcamt, 2, '.', ',');
		$ftaamt=number_format($ftaamt, 2, '.', ',');
		$ftsamt=number_format($ftsamt, 2, '.', ',');
		$fttamt=number_format($fttamt, 2, '.', ',');

		echo "		   	<tr>\n";
		echo " 			  		<td colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"><b>Totals</b></td>\n";
		echo "		   	</tr>\n";
		echo "		   	<tr>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\"></td>\n";
		echo " 			  		<td align=\"center\" valign=\"bottom\"></td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\"><b>".$ftdigs."</b></td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\"><b>".$ftcamt."</b></td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\"><b>".$ftaamt."</b></td>\n";

		if ($_SESSION['rlev'] >= 8)
		{
		echo " 			  		<td align=\"right\" valign=\"bottom\"><b>".$fttamt."</b></td>\n";
		echo " 			  		<td align=\"right\" valign=\"bottom\"><b>".$ftsamt."</b></td>\n";
		}

		echo " 			  		<td align=\"center\" valign=\"bottom\"></td>\n";
		echo " 			  		<td align=\"left\" valign=\"bottom\"></td>\n";
		echo "      			<td align=\"right\" valign=\"bottom\"></td>\n";
		echo "      			<td align=\"right\" valign=\"bottom\"></td>\n";
		echo "		   	</tr>\n";
		*/
		echo "			</table>\n";
	}
	else
	{
		echo "No Stored Dig Reports found.";
	}
}

function dig_rep_lists()
{
	$cyr=date("Y");
	$fyr=date("Y") + 1;
	$pyr=2004;
	
	$qry0a = "SELECT MAX(rept_yr) AS pyr FROM digreport_main WHERE officeid='".$_SESSION['officeid']."';";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);

	$qry1 = "SELECT encon,enjob FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	if (isset($_REQUEST['pyr']))
	{
		//echo "YR set<br>";
		$ryr = $_REQUEST['pyr'];
	}
	else
	{
		if ($cyr==$row0a['pyr'])
		{
			$ryr = $cyr;
		}
		else
		{
			$ryr = $row0a['pyr'];
		}
	}

	if (!empty($_REQUEST['showall']) && $_REQUEST['showall']==1)
	{
		$qry = "SELECT * FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' ORDER BY rept_mo,rept_yr DESC;";
	}
	else
	{
		$qry = "SELECT * FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' and rept_yr='".$ryr."' ORDER BY rept_mo DESC;";
	}
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	$brdr=1;
	$cspan=9;

	if ($_SESSION['rlev'] >= 8)
	{
		$cspan=13;
		$mspan=13-2;
	}
	else
	{
		$cspan=11;
		$mspan=11-2;
	}

	if ($nrow > 0)
	{
		echo "			<table class=\"outer\" width=\"950px\">\n";
		echo "   			<tr>\n";
		echo " 			  		<td class=\"gray\" colspan=\"".$cspan."\">\n";
		echo "						<table width=\"100%\">\n";
		echo "   						<tr>\n";
		echo " 			  					<td align=\"left\" valign=\"bottom\"><b>Stored Dig Reports</b></td>\n";
		echo "         					<form method=\"post\">\n";
		echo " 			  					<td colspan=\"1\" align=\"right\" valign=\"bottom\">\n";
		echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
		echo "									<input type=\"hidden\" name=\"subq\" value=\"list\"> Year:\n";
		echo "									<select name=\"pyr\">\n";
		
		for ($y = $fyr; $y > $pyr; $y--)
		{
			if ((int) $y == (int) $ryr)
			{
				echo "						<option value=\"".$y."\" SELECTED>".$y." </option>\n";
			}
			else
			{
				echo "						<option value=\"".$y."\">".$y."</option>\n";
			}
		}

		echo "									</select>\n";
		echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"showall\" value=\"1\" title=\"Check this box to show ALL Stored Dig Reports\">\n";
		echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Refresh\">\n";
		echo "								</td>\n";
		echo "   						</tr>\n";
		echo "   					</table>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Month/Year</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Digs</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Renovations</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Contract Total</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Royalty</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Report Date</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Creator</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Status</b></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";

		if ($_SESSION['rlev'] >= 8)
		{
			//echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		}

		//echo " 			  		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		echo "   			</tr>\n";

		$disabled="";
		$ftcamt=0;
		$ftaamt=0;
		$ftsamt=0;
		$fttamt=0;
		$ftdigs=0;
		$ftrens=0;
		$rcnt=0;
		while($row=mssql_fetch_array($res))
		{
			$rcnt++;
			
			if ($rcnt%2)
			{
				$tbg='white';
			}
			else
			{
				$tbg='gray';
			}

			if ($row['locked']==1)
			{
				$disabled="DISABLED";
				$status	="Locked";
			}
			else
			{
				$disabled="";
				$status	="";
			}

			$qryA = "SELECT lname,fname,mas_div FROM security WHERE securityid='".$row['securityid']."';";
			$resA = mssql_query($qryA);
			$rowA	= mssql_fetch_array($resA);

			$cdate=date("m/d/Y", strtotime($row['added']));
			$fcamt=number_format($row['cont_total'], 2, '.', ',');
			$faamt=number_format($row['admin_fee'], 2, '.', ',');
			$fsamt=number_format($row['cons_fee'], 2, '.', ',');
			$ftamt=number_format($row['acct_fee'], 2, '.', ',');

			echo "		   	<tr>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"right\" valign=\"bottom\">".$rcnt."</td>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">".str_pad($row['rept_mo'], 2, "0", STR_PAD_LEFT)."/".$row['rept_yr']."</td>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">".$row['no_digs']."</td>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">".$row['no_rens']."</td>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"right\" valign=\"bottom\">".$fcamt."</td>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"right\" valign=\"bottom\">".$faamt."</td>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">".$cdate."</td>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"left\" valign=\"bottom\">".$rowA['lname'].", ".$rowA['fname']."</td>\n";
			echo " 			  	<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">".$status."</td>\n";
			echo "      			<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">\n";
			echo "         				<form method=\"post\">\n";
			echo "					<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
			echo "					<input type=\"hidden\" name=\"call\"	value=\"digreports\">\n";
			echo "					<input type=\"hidden\" name=\"rept_id\"	value=\"".$row['id']."\">\n";
			echo "					<input type=\"hidden\" name=\"rept_mo\"	value=\"".$row['rept_mo']."\">\n";
			echo "					<input type=\"hidden\" name=\"rept_yr\"	value=\"".$row['rept_yr']."\">\n";
			//echo $row['locked']==1;

			if ($_SESSION['rlev'] >= 8 && $row['locked']==1)
			{
				echo "						<input type=\"hidden\" name=\"subq\"	value=\"unlock_digrpt\">\n";
				echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Unlock\">\n";
			}
			else
			{
				echo "						<input type=\"hidden\" name=\"subq\"	value=\"digrpt_delete\">\n";
				echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Delete\" ".$disabled.">\n";
			}

			echo "         		</form>\n";
			echo "					</td>\n";

			if ($row1['encon']==1 && $row1['enjob']==1)
			{
				echo "      			<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">\n";
				echo "         		<form action=\"./pdf/chkrequest_gen_func.php\" method=\"post\" target=\"_new\">\n";
				echo "						<input type=\"hidden\" name=\"action\"	value=\"chkrequest_gen\">\n";
				echo "						<input type=\"hidden\" name=\"officeid\"	value=\"".$_SESSION['officeid']."\">\n";
				echo "						<input type=\"hidden\" name=\"chkreqid\"	value=\"".$_SESSION['securityid']."\">\n";
				echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$row['id']."\">\n";
				echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$row['rept_mo']."\">\n";
				echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$row['rept_yr']."\">\n";
				echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Chk Request\">\n";
				echo "         		</form>\n";
				echo "					</td>\n";
			}
			else
			{
				echo "      			<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\"></td>\n";
			}

			echo "      			<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">\n";
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\"		value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\"	value=\"eqprpt_view\">\n";
			echo "						<input type=\"hidden\" name=\"print\"	value=\"1\">\n";
			echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$row['id']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$row['rept_mo']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$row['rept_yr']."\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Equipment\">\n";
			echo "         		</form>\n";
			echo "					</td>\n";
			echo "      			<td class=\"".$tbg."\" align=\"center\" valign=\"bottom\">\n";
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\"	value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\"		value=\"digreports\">\n";
			echo "						<input type=\"hidden\" name=\"subq\"	value=\"digrpt_view\">\n";
			echo "						<input type=\"hidden\" name=\"print\"	value=\"1\">\n";
			echo "						<input type=\"hidden\" name=\"rept_id\"	value=\"".$row['id']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_mo\"	value=\"".$row['rept_mo']."\">\n";
			echo "						<input type=\"hidden\" name=\"rept_yr\"	value=\"".$row['rept_yr']."\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Digs\">\n";
			echo "         		</form>\n";
			echo "					</td>\n";
			echo "		   	</tr>\n";
			$ftcamt=$ftcamt+$row['cont_total'];
			$ftaamt=$ftaamt+$row['admin_fee'];
			$ftsamt=$ftsamt+$row['cons_fee'];
			$fttamt=$fttamt+$row['acct_fee'];
			$ftdigs=$ftdigs+$row['no_digs'];
			$ftrens=$ftrens+$row['no_rens'];

		}

		$ftcamt=number_format($ftcamt, 2, '.', ',');
		$ftaamt=number_format($ftaamt, 2, '.', ',');
		$ftsamt=number_format($ftsamt, 2, '.', ',');
		$fttamt=number_format($fttamt, 2, '.', ',');

		echo "		   	<tr>\n";
		echo " 			  		<td class=\"gray\" colspan=\"".$cspan."\"><hr width=\"100%\"></td>\n";
		echo "		   	</tr>\n";
		echo "		   	<tr>\n";
		echo " 			  		<td class=\"gray\" colspan=\"".$cspan."\" align=\"left\" valign=\"bottom\"><b>Totals</b></td>\n";
		echo "		   	</tr>\n";
		echo "		   	<tr>\n";
		echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"gray\" align=\"center\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"gray\" align=\"center\" valign=\"bottom\"><b>".$ftdigs."</b></td>\n";
		echo " 			  		<td class=\"gray\" align=\"center\" valign=\"bottom\"><b>".$ftrens."</b></td>\n";
		echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>".$ftcamt."</b></td>\n";
		echo " 			  		<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>".$ftaamt."</b></td>\n";
		echo " 			  		<td class=\"gray\" align=\"center\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"gray\" align=\"center\" valign=\"bottom\"></td>\n";
		echo " 			  		<td class=\"gray\" align=\"left\" valign=\"bottom\"></td>\n";
		echo "      				<td class=\"gray\" align=\"right\" valign=\"bottom\"></td>\n";
		echo "      				<td class=\"gray\" align=\"right\" valign=\"bottom\"></td>\n";
		echo "      				<td class=\"gray\" align=\"right\" valign=\"bottom\"></td>\n";
		echo "      				<td class=\"gray\" align=\"right\" valign=\"bottom\"></td>\n";

		//if ($_SESSION['rlev'] >= 8)
		//{
		//echo " 			  		<td align=\"left\" valign=\"bottom\"></td>\n";
		//}

		echo "		   	</tr>\n";
		echo "			</table>\n";
	}
	else
	{
		//$qryZ = "SELECT DISTINCT(rept_yr) AS pyr FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' ORDER BY rept_yr;";
		//$resZ = mssql_query($qryZ);
		echo "No Stored Dig Reports found.";
	}
}

function admin_digmenu()
{
	$brdr =0;
	$qry0 = "SELECT DISTINCT(rept_yr) AS pyr FROM digreport_admin ORDER BY rept_yr DESC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if (isset($_REQUEST['pyr']))
	{
		$ryr=$_REQUEST['pyr'];
	}
	else
	{
		$ryr=date("Y");
	}

	//echo "<div id=\"masterdiv\">\n";

	if (isset($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "<table  width=\"950px\">\n";
	echo "   <tr>\n";
	echo "   	<td>\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td class=\"gray\" align=\"left\"><b>Admin Dig Report Menu </b></td>\n";
	echo "			   		<td class=\"gray\" align=\"right\">\n";
	echo "						<table >\n";
	echo "   						<tr>\n";

	if ($_SESSION['rlev'] == 9)
	{
		echo " 				        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
		echo "									<input type=\"hidden\" name=\"subq\" value=\"job_period_rpt\">\n";
		echo "									<input type=\"hidden\" name=\"print\" value=\"1\">\n";
		echo "      						<td align=\"right\" valign=\"bottom\">\n";
		echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Period\">\n";
		echo "								</td>\n";
		echo "         					</form>\n";
		
		if ($_SESSION['officeid'] == 89)
		{
			if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332 || $_SESSION['securityid']==50)
			{
				echo " 				        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
				echo "									<input type=\"hidden\" name=\"subq\" value=\"equip1\">\n";
				echo "      						<td align=\"right\" valign=\"bottom\">\n";
				echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Equipment\">\n";
				echo "								</td>\n";
				echo "         					</form>\n";
			}
			
			echo " 				        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
			echo "									<input type=\"hidden\" name=\"subq\" value=\"admindigrpt_pub_arch_list\">\n";
			echo "      						<td align=\"right\" valign=\"bottom\">\n";
			echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Recognized\">\n";
			echo "								</td>\n";
			echo "         					</form>\n";
		}
	}

	echo " 				        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
	echo "									<input type=\"hidden\" name=\"subq\" value=\"adminlist\">\n";
	echo "      						<td align=\"right\">\n";
	echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Registered\">\n";
	echo "								</td>\n";
	echo "         					</form>\n";
	echo "   						</tr>\n";
	echo "						</table>\n";
	echo "   				</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";

	if (isset($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "</div>\n";
	}
}

function digmenu()
{
	$brdr=0;
	$ydate=date("Y");
	$mdate=date("m");
	//echo "<div id=\"masterdiv\">\n";

	if (isset($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\">\n";
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td align=\"left\"><b>Dig Report Menu: </b>".$_SESSION['offname']."</td>\n";
	echo "			   	<td align=\"right\">\n";
	echo "						<table border=\"".$brdr."\">\n";
	echo "   						<tr>\n";

	if ($_SESSION['rlev'] >= 5)
	{
		echo " 				        		<form method=\"post\">\n";
		echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
		echo "									<input type=\"hidden\" name=\"subq\" value=\"job_period_rpt\">\n";
		echo "									<input type=\"hidden\" name=\"oidrpt\" value=\"1\">\n";
		echo "      						<td align=\"right\" valign=\"bottom\">\n";
		echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Period Report\">\n";
		echo "								</td>\n";
		echo "         					</form>\n";
	}

	
	if ($_SESSION['securityid'] == 0)
	{
		echo " 				        		<form method=\"post\">\n";
		echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
		echo "									<input type=\"hidden\" name=\"subq\" value=\"fixjobinfo\">\n";
		echo "      						<td align=\"right\" valign=\"bottom\">\n";
		echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Fix Jobs\">\n";
		echo "								</td>\n";
		echo "         					</form>\n";
	}
	

	echo " 				        		<form method=\"post\">\n";
	echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
	echo "									<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
	echo "      						<td align=\"right\" valign=\"bottom\">\n";
	//echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"showall\" value=\"1\" title=\"Check this box to show ALL Stored Dig Reports\">\n";
	echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"List Reports\">\n";
	echo "								</td>\n";
	echo "         					</form>\n";
	echo "         					<form method=\"post\">\n";
	echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "									<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
	echo "									<input type=\"hidden\" name=\"rept_mo\" value=\"".$mdate."\">\n";
	echo "									<input type=\"hidden\" name=\"rept_yr\" value=\"".$ydate."\">\n";
	echo "									<input type=\"hidden\" name=\"subq\" value=\"gen_new\">\n";
	echo "      						<td align=\"right\" valign=\"bottom\">\n";
	echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Create\">\n";
	echo "								</td>\n";
	echo "         					</form>\n";
	echo "   						</tr>\n";
	echo "						</table>\n";
	echo "   				</td>\n";
	echo "		   	</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";

	if (isset($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "</div>\n";
	}
	//echo "";
}

function dig_matrix()
{
	//show_post_vars();
	$brdr=1;
	error_reporting(E_ALL);
	
	$qry = "SELECT encon,enjob,endigreport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qryA = "SELECT securityid,officeid,admindigreport FROM jest..security WHERE securityid = '".$_SESSION['securityid']."';";
	$resA = mssql_query($qryA);
	$rowA= mssql_fetch_array($resA);

	$_SESSION['encon']			=$row['encon'];
	$_SESSION['enjob']			=$row['enjob'];
	$_SESSION['endigreport']	=$row['endigreport'];

	if ($_SESSION['officeid']==89 && $rowA['admindigreport'])
	{
		admin_digmenu();
	}
	elseif (isset($row['endigreport']) && $row['endigreport']==1)
	{
		digmenu();
	}

	if (isset($_REQUEST['subq']))
	{
		echo "<table width=\"900px\">\n";
		echo "   <tr>\n";
		echo "   	<td>\n";

		if ($_REQUEST['subq']=="list")
		{
			dig_rep_lists();
		}
		elseif ($_REQUEST['subq']==="gen_new")
		{
			//gen_new();
			if ($row['enjob']==1)
			{
				//echo "PREVIEW<br>";
				gen_new();
			}
			else
			{
				//echo "CREATE<br>";
				gen_create();
			}
		}
		elseif ($_REQUEST['subq']==="gen_preview")
		{
			gen_preview();
		}
		elseif ($_REQUEST['subq']==="digrpt_store")
		{
			digrpt_store();
		}
		elseif ($_REQUEST['subq']==="digrpt_store_nodigs")
		{
			digrpt_store();
		}
		elseif ($_REQUEST['subq']==="digrpt_view")
		{
			digrpt_view();
		}
		elseif ($_REQUEST['subq']==="eqprpt_view")
		{
			eqprpt_view();
		}
		elseif ($_REQUEST['subq']==="digrpt_delete")
		{
			digrpt_delete();
		}
		elseif ($_REQUEST['subq']==="unlock_digrpt")
		{
			unlock_dig_report();
		}
		elseif ($_REQUEST['subq']==="gen_new")
		{
			add_jobs();
		}
		elseif ($_REQUEST['subq']==="adminlist")
		{
			admin_dig_rep_lists();
		}
		elseif ($_REQUEST['subq']==="admingen_new")
		{
			admin_gen_new();
		}
		elseif ($_REQUEST['subq']==="admingen_preview")
		{
			admin_gen_preview();
		}
		elseif ($_REQUEST['subq']==="admindigrpt_store")
		{
			admin_digrpt_store();
		}
		elseif ($_REQUEST['subq']==="admindigrpt_view")
		{
			admin_digrpt_view();
		}
		elseif ($_REQUEST['subq']==="admindigrpt_hist")
		{
			admin_digrpt_hist();
		}
		elseif ($_REQUEST['subq']==="admindigrpt_delete")
		{
			admin_digrpt_delete();
		}
		elseif ($_REQUEST['subq']==="admindigrpt_pub_arch_list")
		{
			admin_digrpt_pub_arch_list();
		}
		elseif ($_REQUEST['subq']==="admindigrpt_pub_arch_view")
		{
			admin_digrpt_pub_arch_view();
		}
		elseif ($_REQUEST['subq']==="admindigrpt_pub")
		{
			if ($_REQUEST['publish']==0)
			{
				admin_digrpt_pub();
			}
			elseif ($_REQUEST['publish']==1)
			{
				admin_digrpt_pub_preview();
			}
			elseif ($_REQUEST['publish']==2)
			{
				admin_digrpt_pub_post();	
			}
		}
		elseif ($_REQUEST['subq']==="admindigrpt_pub_delete")
		{
			admin_digrpt_pub_delete();
		}
		elseif ($_REQUEST['subq']==="admindigrpt_pub_update_item")
		{
			admin_digrpt_pub_update_item();
		}
		elseif ($_REQUEST['subq']==="digrpt_store_sess")
		{
			if ($_REQUEST['subq1']==="Preview")
			{
				//show_array_vars($_POST);
				sess_jcreate_store();
			}
			elseif ($_REQUEST['subq1']==="Save")
			{
				digrpt_store();
				unset($_SESSION['jcreate_sess']);
			}
			elseif ($_REQUEST['subq1']==="Reset")
			{
				unset($_SESSION['jcreate_sess']);
				gen_create();
			}
			elseif ($_REQUEST['subq1']==="Material")
			{
				gen_create_mats();
			}
		}
		elseif ($_REQUEST['subq']==="sales_standings")
		{
			sales_standings();
		}
		elseif ($_REQUEST['subq']==="equip_report")
		{
			equip_report();
		}
		elseif ($_REQUEST['subq']==="fixjobinfo")
		{
			fixjobinfo();
		}
		elseif ($_REQUEST['subq']==="job_period_rpt")
		{
			job_period_rpt();
		}
		elseif ($_REQUEST['subq']==="equip1")
		{
			equip_search1();
		}
		elseif ($_REQUEST['subq']==="equip2")
		{
			equip_search2();
		}
		elseif ($_REQUEST['subq']==="equip3")
		{
			equip_search3();
		}

		echo "   	</td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}

	echo "</table>\n";
	//echo "</div>\n";
}

?>