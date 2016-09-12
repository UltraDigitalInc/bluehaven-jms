<?php
session_start();
//WC Audit REport Export
if (!isset($_POST['cpny']) || !isset($_POST['division']) || !isset($_SESSION['securityid']))
{
   exit;
}

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=wcexport_".$_POST['cpny']."_".date("m-d-Y").".xls");
header("Pragma: no-cache"); 
header("Expires: 0");

function removecomma($data)
{
	$out=str_replace(","," ",$data);
	return $out;
}

function removespec($data)
{
   $out=str_replace("\015\012","",$data);
	return $out;
}

$hostname = "192.168.1.22";
$username = "sa";
$password = "date1995";
$dbname   = "ZE_Stats";

mssql_connect($hostname,$username,$password) or die("DATABASE FAILED TO RESPOND.");
mssql_select_db($dbname) or die("database unavailable");

$qry1  = "SELECT ";
$qry1 .= "	a.Division, ";
$qry1 .= "	a.VendorNumber, ";
$qry1 .= "	a.VendorName, ";
$qry1 .= "	a.AddressLine1, ";
$qry1 .= "	a.AddressLine2, ";
$qry1 .= "	a.City, ";
$qry1 .= "	a.State, ";
$qry1 .= "	a.ZipCode, ";
$qry1 .= "	a.PhoneNumber, ";
$qry1 .= "	b.[CB_UDF_APV_ANY_EES],  ";
$qry1 .= "	b.[ML_UDF_APV_LICENSE_NUMBER],  ";
$qry1 .= "	b.[ML_UDF_APV_LIC_EXP_DATE],  ";
$qry1 .= "	b.[ML_UDF_APV_LIC_ISSUE_DATE],  ";
$qry1 .= "	b.[ML_UDF_APV_WC_END_DATE],  ";
$qry1 .= "	b.[ML_UDF_APV_WC_INS_CARRIER],  ";
$qry1 .= "	b.[ML_UDF_APV_WC_POLICY_NUM],  ";
$qry1 .= "	b.[ML_UDF_APV_WC_START_DATE],  ";
$qry1 .= "	b.[ML_UDF_APV_WORK_TYPE],  ";
$qry1 .= "	b.[ML_UDF_APV_GEN_END_DATE],  ";
$qry1 .= "	b.[ML_UDF_APV_GEN_INS_CARRIER],  ";
$qry1 .= "	b.[ML_UDF_APV_GEN_POLICY_NUM], ";
$qry1 .= "	b.[ML_UDF_APV_GEN_START_DATE] ";
$qry1 .= "FROM ";
$qry1 .= "	MAS_".$_POST['cpny'].".dbo.AP1_VendorMaster AS a ";
$qry1 .= "INNER JOIN ";
$qry1 .= "	MAS_".$_POST['cpny'].".dbo.AP_90_UDF_AP_Vendor AS b ";
$qry1 .= "ON ";
$qry1 .= "	a.VendorNumber=b.VendorNumber ";
$qry1 .= "WHERE ";
$qry1 .= "	b.[CB_UDF_APV_PRINT_ON_COMP]='Y' ";
$qry1 .= "ORDER BY  ";
$qry1 .= "	a.VendorNumber; ";

$res1 = mssql_query($qry1);

//$csv_output="".$qry1."";
//$csv_output .= "\015\012";
$csv_output  ="
<table border=1>
   <tr>
      <td>Division</td>
      <td>Vendor Number</td>
      <td>Vendor Name</td>
      <td>Address 1</td>
      <td>Address 2</td>
      <td>City</td>
      <td>State</td>
      <td>Zip Code</td>
      <td>Phone Number</td>
      <td>Lic Number</td>
      <td>Lic Issue Date</td>
      <td>Lic Exp Date</td>
      <td>WC Policy Number</td>
      <td>WC Ins Carrier</td>
      <td>WC Start Date</td>
      <td>WC End Date</td>
      <td>Gen Policy Number</td>
      <td>Gen Ins Carrier</td>
      <td>Gen Start Date</td>
      <td>Gen End Date</td>
   </tr>
";

while ($row1 = mssql_fetch_array($res1))
{
   $csv_output .="<tr>";
   $csv_output .="<td>".removespec(removecomma($row1['Division']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['VendorNumber']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['VendorName']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['AddressLine1']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['AddressLine2']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['City']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['State']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ZipCode']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['PhoneNumber']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_LICENSE_NUMBER']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_LIC_ISSUE_DATE']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_LIC_EXP_DATE']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_WC_POLICY_NUM']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_WC_INS_CARRIER']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_WC_START_DATE']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_WC_END_DATE']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_GEN_POLICY_NUM']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_GEN_INS_CARRIER']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_GEN_START_DATE']))."</td>";
	$csv_output .="<td>".removespec(removecomma($row1['ML_UDF_APV_GEN_END_DATE']))."</td>";
   $csv_output .="</tr>";
}

$csv_output .="</table>";
print $csv_output;
exit;

?>
