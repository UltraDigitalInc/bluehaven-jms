<?php
session_start();

ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

$data='';

include ('./ajax_common_func.php');

$usid = new AuthUser($_REQUEST['sid']);

echo $usid->get_sid();

exit;
//include ('./ajax_common_func.php');

//if (isTimeOut())
//{
//	echo "Session Expired (" . __LINE__ . ")";
//	exit;
//}

//if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
//{
	//echo 'SEC<br>';
	include ('./ajax_retail_func.php');
	//echo 'INC<br>';
	
	$jms_db	=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest');

	/*
	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	*/
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='get_CustInfo')
	{
		$data=get_CustInfo($_REQUEST['cid']);
	}
	else
	{
		$data+="Malformed Request (" . __LINE__ . ")<br>";
		//$data=$data."Debug:<br> " . print_r($_REQUEST) . "";
	}
/*
}
else
{
	$data=$data."Unauthorized (" . __LINE__ . ")";
}

if (isset($_REQUEST['optype']) and $_REQUEST['optype']=='json')
{
	echo json_encode($data);
}
else
{
	echo $data;
}

//print_r($_REQUEST);

//echo 'END<br>';
*/

?>