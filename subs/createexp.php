<?php
//header ("Content-type: application/text");
//header ("Content-Disposition: \"inline; filename=export.csv\"");

function removecomma($data)
{
	$out=preg_replace("/,/","",$data);
	return $out;
}

$hostname	= "192.168.100.25";
$username 	= "jestadmin";
$password 	= "into99black";
$dbname 		= "jest";

mssql_connect($hostname,$username,$password) or die("DATABASE FAILED TO RESPOND.");
mssql_select_db($dbname) or die("database unavailable");

$qry1 = "SELECT cfname,clname,saddr1,sstate,szip1,chome FROM cinfo WHERE officeid='".$_POST['oid']."' AND added >= '".$_POST['d9']."' AND added <= '".$_POST['d10']." 11:59:59';";
$res1 = mssql_query($qry1);
//$row1 = mssql_fetch_array($res1);

echo "FirstName,LastName,Address,State,Zip,Phone\n";

while ($row1 = mssql_fetch_array($res1))
{
   echo removecomma($row1['cfname']).",".removecomma($row1['clname']).removecomma($row1['saddr1']).",".removecomma($row1['sstate']).",".removecomma($row1['szip1']).",".removecomma($row1['chome'])."\n";
}

?>
