<?php
$hostname = "192.168.1.22";
$username = "MAS_REPORTS";
$password = "reports";
$dbname = "MAS_SYSTEM";

mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to database", E_USER_ERROR);
mssql_select_db($dbname) or die("Table unavailable");

1;
?>
