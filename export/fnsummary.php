<?php
session_start();

if (!isset($_POST['foid']) || !isset($_POST['d1']) || !isset($_POST['d2']) || !isset($_SESSION['securityid']))
{
   exit;
}


header("Content-type: application/vnd.ms-excel");
//header("Content-type: application/text"); 
header("Content-Disposition: ".$_POST['disp']."; filename=fnsummary_".date("m-d-Y").".xls");
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
$qry1 .= "	(select lname from security where securityid=F.assigned) as aslname, ";
$qry1 .= "	(select lenderabbrev from tlender where lid=F.lender) as lender, ";
$qry1 .= "	(select name from offices where officeid=F.finan_from) as ffrom, ";
$qry1 .= "	(select name from offices where officeid=c.officeid) as soid, ";
//$qry1 .= "	(select contractamt from jdetail where officeid=c.officeid and jobid=c.jobid and jadd=(SELECT MAX(jadd) FROM jdetail WHERE officeid=c.officeid and jobid=c.jobid)) as ctramt, ";
$qry1 .= "	(select contractamt from jdetail where officeid=c.officeid and jobid=c.jobid and jadd=0) as ctramt, ";
$qry1 .= "	(select rcode from tfinanresultcodes where rid=f.reasnotclosed) as reasnc, ";
$qry1 .= "	F.lientype, ";
$qry1 .= "	F.assigned, ";
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

if (!empty($_POST['d1']) && !empty($_POST['d2']))
{
   $qry1 .= "	F.".$_POST['dtype']." >= '".$_POST['d1']."' and";
   $qry1 .= "	F.".$_POST['dtype']." <= '".$_POST['d2']." 11:59:59' ";
}

if (isset($_POST['foid']) && $_POST['foid']!=0)
{
   if (!empty($_POST['d1']) && !empty($_POST['d2']))
   {   
     $qry1 .= "	and ";
   }
   
   $qry1 .= "	F.finan_from='".$_POST['foid']."' ";
}

if (isset($_POST['finansrc']) && $_POST['finansrc']!=0)
{
   if (isset($_POST['foid']) && $_POST['foid']!=0)
   {
      $qry1 .= "	and ";
   }
	$qry1 .= "	C.finan_src='".$_POST['finansrc']."' ";
}

if (isset($_POST['lientype']) && $_POST['lientype']!=0)
{
	$qry1 .= "	and ";
	$qry1 .= "	F.lientype='".$_POST['lientype']."' ";
}

if (isset($_POST['iactive']) && $_POST['iactive']!=99)
{
	$qry1 .= "	and ";
	$qry1 .= "	F.inclstatreport='".$_POST['iactive']."' ";
}

if (isset($_POST['assigned']) && $_POST['assigned']!=0)
{
   $qry1 .= "	and F.assigned='".$_POST['assigned']."' ";
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
      <td align=\"left\" colspan=\"9\"><b>".$typer." Report</b></td>
      <td align=\"right\" colspan=\"9\"><b>Generated on: ".date('m/d/Y',time())."</b></td>
   </tr>
   <tr>
      <td></td>
      <td><b>Last Name</b></td>
      <td><b>First Name</b></td>
      <td><b>Date Received</b></td>
      <td><b>Date Closed</b></td>
      <td><b>Reas NC</b></td>
      <td><b>Office Name</b></td>
      <td><b>Lien Type</b></td>
      <td><b>Contract Amt</b></td>
      <td><b>Amount Finan</b></td>
      <td><b>Lender</b></td>
      <td><b>Broker</b></td>
      <td><b>Fin Src</b></td>
      <td><b>Fin Rep</b></td>
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
$psoid  =0;

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
   
   if (!isset($row1['ctramt']) || $row1['ctramt']==0)
   {
      $ctamt=$row1['amtfinan'];
   }
   else
   {
      $ctamt=$row1['ctramt'];
   }

   if ($psoid!=0 && $psoid!=$row1['soid'])
   {
      $csv_output .= "
      <tr>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>      
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
         <td bgcolor=\"gray\"></td>
      </tr>
      ";
   }

   $rec++;
   $csv_output .= "<tr>";
   $csv_output .= "<td align=\"right\">".$rec."</td>";
   $csv_output .= "<td align=\"left\">".removespec($row1['clname'])."</td>";
   $csv_output .= "<td align=\"left\">".removespec($row1['cfname'])."</td>";
   $csv_output .= "<td align=\"center\">".$rdate."</td>";
   $csv_output .= "<td align=\"center\">".$cdate."</td>";
   $csv_output .= "<td align=\"center\">".$row1['reasnc']."</td>";
   $csv_output .= "<td align=\"left\">".removespec($row1['soid'])."</td>";
   $csv_output .= "<td align=\"center\">\n";
   
   if ($row1['lientype']==1)
   {
      $csv_output .= "1st";
   }
   elseif ($row1['lientype']==2)
   {
      $csv_output .= "2nd";
   }
   elseif ($row1['lientype']==3)
   {
      $csv_output .= "3rd";
   }
   elseif ($row1['lientype']==4)
   {
      $csv_output .= "Uns";
   }

   $csv_output .= "</td>";  
   $csv_output .= "<td align=\"right\">".number_format($ctamt)."</td>";
   $csv_output .= "<td align=\"right\">".number_format($row1['amtfinan'])."</td>";
   $csv_output .= "<td align=\"center\">".$row1['lender']."</td>";
   $csv_output .= "<td align=\"left\">".removespec($row1['ffrom'])."</td>";
   $csv_output .= "<td align=\"center\">\n";
   
   if ($row1['lientype']==1)
   {
      $csv_output .= "Winrs";
   }
   elseif ($row1['lientype']==2)
   {
      $csv_output .= "Cst Fin";
   }
   elseif ($row1['lientype']==3)
   {
      $csv_output .= "Cash";
   }
   elseif ($row1['lientype']==4)
   {
      $csv_output .= "BH Fin";
   }

   $csv_output .= "</td>";  
   
   if ($row1['assigned']!=0)
   {
      $csv_output   .= "					<td align=\"center\" valign=\"top\">".$row1['aslname']."</td>";
   }
   else
   {
      $csv_output   .= "					<td align=\"center\" valign=\"top\"></td>";
   }
   
   $csv_output .= "<td align=\"right\">".number_format($row1['bfee'])."</td>";
   $csv_output .= "<td align=\"right\">".number_format($row1['cfee'])."</td>";
   $csv_output .= "<td align=\"right\">".number_format($row1['p1fee'])."</td>";
   $csv_output .= "<td align=\"right\">".number_format($row1['p2fee'])."</td>";
   $csv_output .= "<td align=\"right\">".number_format($row1['ofee'])."</td>";
   $csv_output .= "<td align=\"center\">".$fdate."</td>";
   $csv_output .= "<td align=\"left\">".removespec($row1['fcomment'])."</td>";
   $csv_output .= "</tr>";
   $psoid=$row1['soid'];
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
      <td></td>
      <td></td>
      <td><b>=sum(I3:I". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(J3:J". (3 + ($nrows-1)) .")</b></td>
      <td></td>      
      <td></td>
      <td></td>
      <td></td>
      <td><b>=sum(O3:O". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(P3:P". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(Q3:Q". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(R3:R". (3 + ($nrows-1)) .")</b></td>
      <td><b>=sum(S3:S". (3 + ($nrows-1)) .")</b></td>
      <td></td>
      <td></td>
   </tr>
</table>";
print $csv_output;
exit;

?>
