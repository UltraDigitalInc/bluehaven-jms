<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
include ('connect_db.php');
require ('subs/auth_func.php');

$tout='';
if (isset($_REQUEST['logid']) and isset($_REQUEST['token']) and isset($_REQUEST['efunc']))
{
	$ival=isValidUserExt($_REQUEST['logid'],$_REQUEST['token'],$_REQUEST['efunc']);
	if ($ival[0])
	{
		//echo 'Valid Login!!<br>';
		$tout = $ival[1];
	}
	else
	{
		$tout = $ival[1];
	}
}
else
{
	echo 'Parameter Missing';
}

print(trim($tout));

?>