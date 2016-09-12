<?php

session_start();

if (!isset($_SESSION['securityid']) || !isset($_REQUEST['oid']) || !isset($_REQUEST['sid']))
{
   echo 'System Error.';
   exit;
}

if (!isset($_REQUEST['d1']) || !isset($_REQUEST['d2']))
{
   echo 'Date Error.';
   exit;
}

if (!isset($_REQUEST['certify']))
{
   echo 'Release/Certify Error.';
   exit;
}

//header("Content-type: application/vnd.ms-excel");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=jmsleadexp_".date("mdY").".xls");
header ("Cache-Control: no-cache,must-revalidate"); 
header ("Expires: 0");

include ('../connect_db.php');
include ('../common_func.php');

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

$qry0 = "INSERT INTO texportauth (oid,sid,d1,d2,certify) VALUES ('".$_REQUEST['oid']."','".$_REQUEST['sid']."','".$_REQUEST['d1']."','".$_REQUEST['d2']."','".$_REQUEST['certify']."');";
$res0 = mssql_query($qry0);

$qry1  = "SELECT ";
$qry1 .= "	cfname,clname,saddr1,scity,sstate,szip1,cemail,chome,cwork,ccell,added,updated,source ";

if (isset($_REQUEST['srccode']))
{
   $qry1 .= "	,(select name from leadstatuscodes where statusid=c.source) as src ";
}
   
if (isset($_REQUEST['srep']))
{
   $qry1 .= "	,(select lname from security where securityid=c.securityid) as lsrep ";
   $qry1 .= "	,(select fname from security where securityid=c.securityid) as fsrep ";
}

$qry1 .= "	,(select name from leadstatuscodes where statusid=c.stage) as tres ";
$qry1 .= "	FROM cinfo AS c WHERE ";

if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
{
   $qry1 .= "	c.officeid='".$_REQUEST['oid']."' AND ";
}

$qry1 .= "	c.dupe!=1 ";

if (isset($_REQUEST['privrelease']) && $_REQUEST['privrelease']==1)
{
}
else
{
   $qry1 .= "	AND c.opt1=0 ";   
}

$qry1 .= "	AND c.".$_REQUEST['dtype']." >= '".$_REQUEST['d1']."' AND c.".$_REQUEST['dtype']." <= '".$_REQUEST['d2']." 23:59:59' ";

if (isset($_REQUEST['srccode']) && $_REQUEST['srccode']!='A')
{
   $qry1 .= "	AND c.source=".$_REQUEST['srccode']." ";
}

if (isset($_REQUEST['rescode']) && $_REQUEST['rescode']!='A')
{
   $qry1 .= "	AND c.stage=".$_REQUEST['rescode']." ";
}

if (isset($_REQUEST['srep']) && $_REQUEST['srep']!='A')
{
   $qry1 .= "	AND c.securityid=".$_REQUEST['srep']." ";
}

$qry1 .= "ORDER by added;";
$res1 = mssql_query($qry1);

$xls_output =
"
<html>
<body>
<table border=1>
   <tr>
      <td><b>First Name</b></td>
	  <td><b>Last Name</b></td>
	  <td><b>Address</b></td>
	  <td><b>City</b></td>
	  <td><b>State</b></td>
	  <td><b>Zip</b></td>
	  <td><b>Email</b></td>
	  <td><b>Home Ph</b></td>
	  <td><b>Work Ph</b></td>
	  <td><b>Cell Ph</b></td>
	  <td><b>Added</b></td>
	  <td><b>Updated</b></td>
";

if (isset($_REQUEST['srccode']))
{
   $xls_output .=
   "
   	  <td><b>Source</b></td>
   ";
}

if (isset($_REQUEST['srep']))
{
   $xls_output .=
   "
	  <td><b>Sales Rep</b></td>
   ";   
}

$xls_output .=
"
  <td><b>Result</b></td>
";

$xls_output .=
"
   </tr>
";

while ($row1 = mssql_fetch_array($res1))
{
   if (isset($_REQUEST['validemail']) && $_REQUEST['validemail']==1)
   {
	  if (valid_email_addr(removespec($row1['cemail'])))
	  {
		 $xls_output .="
		 <tr>
			<td>".removespec($row1['cfname'])."</td>
			<td>".removespec($row1['clname'])."</td>
			<td>".removespec($row1['saddr1'])."</td>
			<td>".removespec($row1['scity'])."</td>
			<td>".removespec($row1['sstate'])."</td>
			<td>".removespec($row1['szip1'])."</td>
			<td>".removespec($row1['cemail'])."</td>
			<td>".removespec($row1['chome'])."</td>
			<td>".removespec($row1['cwork'])."</td>
			<td>".removespec($row1['ccell'])."</td>
			<td>".removespec($row1['added'])."</td>
			<td>".removespec($row1['updated'])."</td>
			";
		 
		 if ($row1['source']==0)
		 {
			$xls_output .="	<td>bluehaven.com</td> ";
		 }
		 else
		 {
			$xls_output .="	<td>".removespec($row1['src'])."</td> ";
		 }
		 
		 if (isset($_REQUEST['srep']))
		 {
			$xls_output .=
			"
			   <td>".removespec($row1['lsrep']).", ".removespec($row1['fsrep'])."</td>
			";   
		 }
		 
		 $xls_output .="		<td>".removespec($row1['tres'])."</td> ";
		 
		 $xls_output .="	</tr>
		 ";
	  }
   }
   else
   {
	  $xls_output .="
	  <tr>
		 <td>".removespec($row1['cfname'])."</td>
		 <td>".removespec($row1['clname'])."</td>
		 <td>".removespec($row1['saddr1'])."</td>
		 <td>".removespec($row1['scity'])."</td>
		 <td>".removespec($row1['sstate'])."</td>
		 <td>".removespec($row1['szip1'])."</td>
		 <td>".removespec($row1['cemail'])."</td>
		 <td>".removespec($row1['chome'])."</td>
		 <td>".removespec($row1['cwork'])."</td>
		 <td>".removespec($row1['ccell'])."</td>
		 <td>".removespec($row1['added'])."</td>
		 <td>".removespec($row1['updated'])."</td>
		 ";
	  
	  if ($row1['source']==0)
	  {
		 $xls_output .="	<td>bluehaven.com</td> ";
	  }
	  else
	  {
		 $xls_output .="	<td>".removespec($row1['src'])."</td> ";
	  }
	  
	  if (isset($_REQUEST['srep']))
	  {
		 $xls_output .=
		 "
			<td>".removespec($row1['lsrep']).", ".removespec($row1['fsrep'])."</td>
		 ";   
	  }
	  
	  $xls_output .="		<td>".removespec($row1['tres'])."</td> ";
	  
	  $xls_output .="	</tr>
	  ";
   }
}

$xls_output .=
"
</table>
</body>
</html>
";

//$xls_output = $qry1;

print $xls_output;
exit;

?>
