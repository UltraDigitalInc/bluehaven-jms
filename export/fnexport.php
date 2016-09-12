<?php
session_start();

if (!isset($_POST['foid']) || !isset($_POST['d1']) || !isset($_POST['d2']) || !isset($_SESSION['securityid']))
{
   exit;
}


header("Content-type: application/vnd.ms-excel");
//header("Content-type: application/text"); 
header("Content-Disposition: ".$_POST['disp']."; filename=fnexport_".date("m-d-Y").".xls");
//header("Content-Disposition: attachment; filename=fnexport_".date("mdY").".txt"); 
header("Pragma: no-cache"); 
header("Expires: 0");

include('../connect_db.php');

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

//$hostname = "192.168.1.59";
//$username = "jestadmin";
//$password = "into99black";
//$dbname   = "jest";
//
//mssql_connect($hostname,$username,$password) or die("DATABASE FAILED TO RESPOND.");
//mssql_select_db($dbname) or die("database unavailable");

$qry1  = "select ";
$qry1 .= "	C.clname, ";
$qry1 .= "	C.cfname, ";
$qry1 .= "	F.inclstatreport, ";
$qry1 .= "	C.finan_src, ";
$qry1 .= "	DATEPART(m,F.closedate) as mclosed, ";
$qry1 .= "	DATEPART(d,F.closedate) as dclosed, ";
$qry1 .= "	DATEPART(yy,F.closedate) as yclosed, ";
$qry1 .= "	DATEPART(m,F.recdate) as mrecd, ";
$qry1 .= "	DATEPART(d,F.recdate) as drecd, ";
$qry1 .= "	DATEPART(yy,F.recdate) as yrecd, ";
$qry1 .= "	(select lname from security where securityid=F.closer) as closer, ";
$qry1 .= "	(select lenderabbrev from tlender where lid=F.lender) as lender, ";
$qry1 .= "	(select name from offices where officeid=F.finan_from) as ffrom, ";
$qry1 .= "	(select name from offices where officeid=c.officeid) as soid, ";
//$qry1 .= "	(select contractamt from jdetail where officeid=c.officeid and jobid=c.jobid and jadd=(SELECT MAX(jadd) FROM jdetail WHERE officeid=c.officeid and jobid=c.jobid)) as ctramt, ";
$qry1 .= "	(select contractamt from jdetail where officeid=c.officeid and jobid=c.jobid and jadd=0) as ctramt, ";
$qry1 .= "	F.amtfinan, ";
$qry1 .= "	F.bfee, ";
$qry1 .= "	F.cfee, ";
$qry1 .= "	F.p1fee, ";
$qry1 .= "	F.p2fee, ";
$qry1 .= "	F.ofee, ";
$qry1 .= "	DATEPART(m,F.datefeesent) as mfeed, ";
$qry1 .= "	DATEPART(d,F.datefeesent) as dfeed, ";
$qry1 .= "	DATEPART(yy,F.datefeesent) as yfeed, ";
$qry1 .= "	F.fcomment ";
$qry1 .= "from  ";
$qry1 .= "	tfinan_detail as F ";
$qry1 .= "inner join ";
$qry1 .= "	cinfo as C ";
$qry1 .= "on ";
$qry1 .= "	F.cid=C.cid ";
$qry1 .= "where ";
$qry1 .= "	F.".$_POST['dtype']." >= '".$_POST['d1']."' and";
$qry1 .= "	F.".$_POST['dtype']." <= '".$_POST['d2']." 11:59:59' ";

if ($_POST['foid']!=0)
{
	$qry1 .= "	and ";
	$qry1 .= "	F.finan_from='".$_POST['foid']."' ";
}

if ($_POST['finansrc']!=0)
{
	$qry1 .= "	and ";
	$qry1 .= "	C.finan_src='".$_POST['finansrc']."' ";
}

if ($_POST['iactive']!=99)
{
	$qry1 .= "	and ";
	$qry1 .= "	F.inclstatreport='".$_POST['iactive']."' ";
}

$qry1 .= "order by ";
$qry1 .= "	".$_POST['order1'].", ";
$qry1 .= "	".$_POST['order2']."  ";
$qry1 .= "  ".$_POST['ascdesc']."  ";

$res1 = mssql_query($qry1);
$nrows = mssql_num_rows($res1);

//echo $qry1."<br>";

if ($_POST['iactive']=="99")
{
   $typer="Combined Status & Yearly Overall";
}
elseif ($_POST['iactive']=="1")
{
   $typer="Status";
}
else
{
   $typer="Yearly Overall";
}

$csv_output  = "
<table border=\"1\">
 	<tr>
      <td align=\"center\" colspan=\"8\"><b>".$typer." Report</b></td>
      <td align=\"center\" colspan=\"8\"><b>Generated on: ".date('m/d/Y',time())."</b></td>
   </tr>
   <tr>
      <td></td>
      <td><b>Last Name</b></td>
      <td><b>First Name</b></td>
      <td><b>Date Received</b></td>
   	<td><b>Date Closed</b></td>
      <td><b>Office Name</b></td>
      <td><b>Contract Amt</b></td>
      <td><b>Amount Finan</b></td>
      <td><b>Lender</b></td>
      <td><b>Broker</b></td>
      <td><b>Broker Fee</b></td>
      <td><b>Closing Fee</b></td>
      <td><b>Property Fee</b></td>
      <td><b>Processing Fee</b></td>
      <td><b>Other Fee</b></td>
      <td><b>Date Sent</b></td>
      <td><b>Fee Comment</b></td>
   </tr>
";

$rdate='';
$cdate='';
$fdate='';
$rec	=0;

while ($row1 = mssql_fetch_array($res1))
{
	//$rec++;
	if (isset($row1['yrecd']) && $row1['yrecd'] > 2000)
	{
		$rdate=$row1['mrecd'].'/'.$row1['drecd'].'/'.$row1['yrecd'];
	}
	
	if (isset($row1['yclosed']) && $row1['yclosed'] > 2000)
	{
		$cdate=$row1['mclosed'].'/'.$row1['dclosed'].'/'.$row1['yclosed'];
	}
	
	if (isset($row1['yfeed']) && $row1['yfeed'] > 2000)
	{
		$fdate=$row1['mfeed'].'/'.$row1['dfeed'].'/'.$row1['yfeed'];
	}

	$rec++;
	$csv_output .= "<tr>";
	$csv_output .= "<td align=\"right\">".$rec."</td>";
	$csv_output .= "<td align=\"left\">".removespec($row1['clname'])."</td>";
   $csv_output .= "<td align=\"left\">".removespec($row1['cfname'])."</td>";
	$csv_output .= "<td align=\"center\">".$rdate."</td>";
	$csv_output .= "<td align=\"center\">".$cdate."</td>";
   $csv_output .= "<td align=\"left\">".removespec($row1['soid'])."</td>";
	$csv_output .= "<td align=\"right\">".$row1['ctramt']."</td>";
	$csv_output .= "<td align=\"right\">".$row1['amtfinan']."</td>";
	$csv_output .= "<td align=\"center\">".$row1['lender']."</td>";
   $csv_output .= "<td align=\"left\">".removespec($row1['ffrom'])."</td>";
	$csv_output .= "<td align=\"right\">".$row1['bfee']."</td>";
	$csv_output .= "<td align=\"right\">".$row1['cfee']."</td>";
	$csv_output .= "<td align=\"right\">".$row1['p1fee']."</td>";
	$csv_output .= "<td align=\"right\">".$row1['p2fee']."</td>";
	$csv_output .= "<td align=\"right\">".$row1['ofee']."</td>";
	$csv_output .= "<td align=\"center\">".$fdate."</td>";
	$csv_output .= "<td align=\"left\">".removespec($row1['fcomment'])."</td>";
	$csv_output .= "</tr>";
	//$rec++;
//    $csv_output .= "\015\012";
}

$csv_output  .= "
   <tr>
		<td></td>
      <td></td>
		<td></td>
  		<td></td>
		<td></td>
      <td></td>
      <td><b>=sum(G3:G". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(H3:H". (3 + ($nrows-1)) .")</b></td>
      <td></td>
      <td><b>=sum(J3:J". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(K3:K". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(L3:L". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(M3:M". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(N3:N". (3 + ($nrows-1)) .")</b></td>
      <td></td>
      <td></td>
   </tr>
</table>";
print $csv_output;
exit;

?>
