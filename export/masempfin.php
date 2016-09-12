<?php
session_start();

if (!isset($_SESSION['securityid']))
{
   echo 'Exit!';
   exit;
}
else
{
   if (isset($_REQUEST['stage']) && $_REQUEST['stage']==2)
   {
	  //echo 'Processed';
	  header("Content-type: application/vnd.ms-excel"); 
	  header("Content-Disposition: attachment; filename=MASEmpData_".date("mdY").".xls"); 
	  header("Pragma: no-cache"); 
	  header("Expires: 0");
	  
	  include('../connect_mas_db.php');
	  
	  //$qtype='A';
	  //$d1='04/01/09';
	  //$d2='04/30/09';
	  
	  if (!isset($_REQUEST['qtype']) || !isset($_REQUEST['d1']) || !isset($_REQUEST['d2']))
	  {
		 echo "Incomplete Parameters:";
		 echo "<br />";
		 echo "<pre>\n";
		 print_r($_REQUEST);
		 echo "</pre>\n";
		 exit;
	  }
	  
	  $qtype=$_REQUEST['qtype'];
	  $d1=$_REQUEST['d1'];
	  $d2=$_REQUEST['d2'];
	  
	  function removespec($data)
	  {
		 $out=str_replace(",","",$data);
		 $out=str_replace("'","",$data);
		 $out=str_replace("\t","",$data);
		 $out=str_replace("\r","",$data);
		 $out=str_replace("\n","",$data);
		 $out=str_replace("\015\012","",$data);
		 return $out;
	  }
	  
	  $qry1 = "SELECT * from ZE_Stats..divtocomp order by company,division;";
	  $res1 = mssql_query($qry1);
	  //$row1 = mssql_fetch_array($res1);
	  
	  
	  $qry1a = "SELECT [EarningsDeductionCode],[Description] FROM [MAS_300].[dbo].[PRD_EarningsDeductionMaster] WHERE [EarningsDeductionCode]='40';";
	  $res1a = mssql_query($qry1a);
	  $row1a = mssql_fetch_array($res1a);
	  
	  $qry1b = "SELECT [EarningsDeductionCode],[Description] FROM [MAS_300].[dbo].[PRD_EarningsDeductionMaster] WHERE [EarningsDeductionCode]='41';";
	  $res1b = mssql_query($qry1b);
	  $row1b = mssql_fetch_array($res1b);
	  
	  $qry1c = "SELECT [EarningsDeductionCode],[Description] FROM [MAS_300].[dbo].[PRD_EarningsDeductionMaster] WHERE [EarningsDeductionCode]='42';";
	  $res1c = mssql_query($qry1c);
	  $row1c = mssql_fetch_array($res1c);
	  
	  $qry1d = "SELECT [EarningsDeductionCode],[Description] FROM [MAS_300].[dbo].[PRD_EarningsDeductionMaster] WHERE [EarningsDeductionCode]='43';";
	  $res1d = mssql_query($qry1d);
	  $row1d = mssql_fetch_array($res1d);
	  
	  $xls_output =
	  "   
	  <table id=\"datagrid\" border=1>
	  <!-- header row -->
		 <tr class=\"head\">
			<td colspan=6><b>Enterprise 401K Data</b></td>
			<td colspan=6 align=\"right\"><b>".date('m/d/Y',strtotime($d1))." - ".date('m/d/Y',strtotime($d2))."</b></td>
		 </tr>
		 <tr>
			<td xls:format=\"greyhead\"><b>Company Code</b></td>
			<td><b>Company Name</b></td>
			<td><b>SSN</b></td>
			<td><b>Last Name</b></td>
			<td><b>First Name</b></td>
			<td><b>Hire Date</b></td>
			<td><b>Term Date</b></td>
			<td><b>".$row1a['Description']." (".$row1a['EarningsDeductionCode'].")</b></td>
			<td><b>".$row1b['Description']." (".$row1b['EarningsDeductionCode'].")</b></td>
			<td><b>".$row1c['Description']." (".$row1c['EarningsDeductionCode'].")</b></td>
			<td><b>".$row1d['Description']." (".$row1d['EarningsDeductionCode'].")</b></td>
			<td><b>401k Summary</b></td>
			<td><b>Gross Wages</b></td>
		 </tr>
	  ";
	  
	  while ($row1 = mssql_fetch_array($res1))
	  {
		 $qry2 = "SELECT top 1 CompanyName FROM [MAS_".$row1['company']."].[dbo].[SY0_CompanyParameters];";
		 $res2 = mssql_query($qry2);
		 $row2 = mssql_fetch_array($res2);
	  
		 if ($qtype=='A')
		 {
			$qry2a = "SELECT * FROM [MAS_".$row1['company']."].[dbo].[PR1_EmployeeMaster] WHERE EmployeeStatus_AIT='".$qtype."';";
		 }
		 elseif ($qtype=='I')
		 {
			$qry2a = "SELECT * FROM [MAS_".$row1['company']."].[dbo].[PR1_EmployeeMaster] WHERE EmployeeStatus_AIT='".$qtype."' AND TerminationDate BETWEEN '".$d1."' AND '".$d2." 23:59:59' ORDER BY LastName;";
		 }
		 else
		 {
			$qry2a = "SELECT * FROM [MAS_".$row1['company']."].[dbo].[PR1_EmployeeMaster]";
		 }
		 $res2a = mssql_query($qry2a);
		 
		 while ($row2a = mssql_fetch_array($res2a))
		 {
			$s401k=0;
			$qry3 = "SELECT IsNull(SUM(Amount),0) as DedAmt FROM [MAS_".$row1['company']."].[dbo].[PR_23PerptHistoryDetail] WHERE EmployeeNumber='".removespec($row2a['EmployeeNumber'])."' AND DeductionCode='40' AND CheckDate BETWEEN '".$d1."' AND '".$d2."';";
			$res3 = mssql_query($qry3);
			$row3 = mssql_fetch_array($res3);
			
			$qry4 = "SELECT IsNull(SUM(Amount),0) as DedAmt FROM [MAS_".$row1['company']."].[dbo].[PR_23PerptHistoryDetail] WHERE EmployeeNumber='".removespec($row2a['EmployeeNumber'])."' AND DeductionCode='41' AND CheckDate BETWEEN '".$d1."' AND '".$d2."';";
			$res4 = mssql_query($qry4);
			$row4 = mssql_fetch_array($res4);
			
			$qry5 = "SELECT IsNull(SUM(Amount),0) as DedAmt FROM [MAS_".$row1['company']."].[dbo].[PR_23PerptHistoryDetail] WHERE EmployeeNumber='".removespec($row2a['EmployeeNumber'])."' AND DeductionCode='42' AND CheckDate BETWEEN '".$d1."' AND '".$d2."';";
			$res5 = mssql_query($qry5);
			$row5 = mssql_fetch_array($res5);
			
			$qry6 = "SELECT IsNull(SUM(Amount),0) as DedAmt FROM [MAS_".$row1['company']."].[dbo].[PR_23PerptHistoryDetail] WHERE EmployeeNumber='".removespec($row2a['EmployeeNumber'])."' AND DeductionCode='43' AND CheckDate BETWEEN '".$d1."' AND '".$d2."';";
			$res6 = mssql_query($qry6);
			$row6 = mssql_fetch_array($res6);
			
			$qry7 = "SELECT IsNull(SUM(GrossWagesThisCheck),0) as ChkAmt FROM [MAS_".$row1['company']."].[dbo].[PR_22PerpetChkHistoryHeader] WHERE EmployeeNumber='".removespec($row2a['EmployeeNumber'])."' AND CheckDate BETWEEN '".$d1."' AND '".$d2."';";
			$res7 = mssql_query($qry7);
			$row7 = mssql_fetch_array($res7);
			
			$s401k=(removespec($row3['DedAmt']) + removespec($row4['DedAmt']) + removespec($row5['DedAmt'])) - removespec($row6['DedAmt']);
			$xls_output .= "	<tr>";
			$xls_output .= "		<td>".removespec(str_pad($row1['company'],3,'0',STR_PAD_LEFT))."</td>";
			$xls_output .= "		<td>".removespec($row2['CompanyName'])."</td>";
			//$xls_output .= "		<td>".removespec($row2a['EmployeeNumber'])."</td>";
			$xls_output .= "		<td>".removespec($row2a['SocialSecurityNumber'])."</td>";
			$xls_output .= "		<td>".removespec($row2a['LastName'])."</td>";
			$xls_output .= "		<td>".removespec($row2a['FirstName'])."</td>";
			$xls_output .= "		<td>".removespec($row2a['HireDate'])."</td>";
			$xls_output .= "		<td>".removespec($row2a['TerminationDate'])."</td>";
			$xls_output .= "		<td>".removespec($row3['DedAmt'])."</td>";
			$xls_output .= "		<td>".removespec($row4['DedAmt'])."</td>";
			$xls_output .= "		<td>".removespec($row5['DedAmt'])."</td>";
			$xls_output .= "		<td>".removespec($row6['DedAmt'])."</td>";
			$xls_output .= "		<td>".$s401k."</td>";
			$xls_output .= "		<td>".removespec($row7['ChkAmt'])."</td>";
			$xls_output .= "	</tr>";
		 }
	  }
	  
	  $xls_output .=
	  "
	  </table>
	  ";
	  
	  print $xls_output;
	  exit;
   }
}


?>
