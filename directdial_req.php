<?php

header("Content-type: text/xml");
header("Content-Disposition: inline");
header("Pragma: no-cache");
header("Expires: 0");

$username   = "matrix_ro";
$password   = "matrix_ro";
$hostname   = "192.168.100.1";
$dbname     = "jest";

mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
mssql_select_db($dbname) or die("Table unavailable");

$qry0 = "exec jest..tlh_directdial_SMB @cani='".$_REQUEST['cani']."',@did='".$_REQUEST['did']."';";
$res0 = mssql_query($qry0);
$row0 = mssql_fetch_row($res0);
$nrow = mssql_num_rows($res0);

if (isset($_REQUEST['debug']) && $_REQUEST['debug']==1)
{
   echo $qry0;
}
else
{
   echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
   echo "<!DOCTYPE ivr_info SYSTEM \"http://www.switchvox.com/xml/ivr.dtd\">";
   echo "<ivr_info>";
   
   if ($nrow > 0)
   {
      echo "<variable name=\"ring\">".trim($row0[0])."</variable>";
   }
   else
   {
      echo "<variable name=\"ring\">0</variable>";
   }
   
   echo "</ivr_info>";
}

?>