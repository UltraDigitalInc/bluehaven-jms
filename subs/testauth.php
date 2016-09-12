<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
require ('auth_func.php');

$ival=isValidUserExt($_REQUEST['logid'],$_REQUEST['pswd'],$_REQUEST['efunc']);
	
if ($ival[0])
{
	//echo 'Valid Login!!<br>';
	echo $ival[1];
}
else
{
	echo $ival[1];
}

?>