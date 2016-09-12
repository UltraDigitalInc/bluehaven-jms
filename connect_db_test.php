<?php
define ("SYS_VER",".99.96");
define ("SYS_ENV","(BETA-PROD)");
define ("ADMIN_ADDR","leadproc@bluehaven.com");

//$hostname = "192.168.1.59";
//$username = "jestadmin";
$hostname = "192.168.1.25";
$username = "jestadmin";
$password = "into99black";
$dbname = "jest";

mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
mssql_select_db($dbname) or die("Table unavailable");

$qry0 = "SELECT * FROM master..bhest_config;";
$res0 = mssql_query($qry0);
$row0 = mssql_fetch_array($res0);

// System Constants
define ("LOCK_SYS",  $row0['LOCK_SYS']);
define ("L_SYS",     $row0['L_SYS']);
define ("E_SYS",     $row0['E_SYS']);
define ("C_LOCK",    $row0['C_SYS']);
define ("J_LOCK",    $row0['J_SYS']);
define ("M_LOCK",    $row0['M_SYS']);
define ("R_LOCK",    $row0['R_SYS']);
define ("SYS_ADMIN", $row0['SYS_ADMIN']);
define ("FDBK_ADMIN",$row0['FDBK_ADMIN']);
define ("MTRX_ADMIN",$row0['MTRX_ADMIN']);
define ("OPER_ADMIN",$row0['OPER_ADMIN']);
define ("PROC_SPVSR",$row0['PROC_SPVSR']);
define ("MAS_ADDR",  $row0['MAS_ADDR']);
define ("JMS_ADDR",  $row0['JMS_ADDR']);
define ("MAS_DB",    $row0['MAS_ADDR']);
define ("MAS_LGN_ID",$row0['MAS_LGN_ID']);
define ("MAS_LGN_PS",$row0['MAS_LGN_PS']);
define ("MAS_CAT",   "ZE_Stats");
define ("EMAIL_OP",  $row0['EMAIL_OP']);
define ("EMAIL_SD",  $row0['EMAIL_SD']);
define ("JMS_DEBUG", "E_ALL");
define ("SYS_DEBUG", $row0['SYS_DEBUG']);


1;
?>
