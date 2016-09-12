<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

$data='';

//include ('./ajax_common_func.php');

if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
{
	include ('../connect_db.php');
	include ('./ajax_sales_func.php');
	
	if (isset($_REQUEST['qt']) and $_REQUEST['qt']=='get_CustomerList')
	{
		$data=get_CustomerList($_REQUEST['oid'],$_REQUEST['sid'],$_REQUEST['stext']);
	}
	elseif (isset($_REQUEST['qt']) and $_REQUEST['qt']=='get_CustomerInfo')
	{
		$data=get_CustomerInfo($_REQUEST['oid'],$_REQUEST['sid'],$_REQUEST['cid']);
	}
	elseif (isset($_REQUEST['qt']) and $_REQUEST['qt']=='get_Catalog')
	{
		$data=get_Catalog($_REQUEST['oid'],$_REQUEST['catid']);
	}
	elseif (isset($_REQUEST['qt']) and $_REQUEST['qt']=='get_Category')
	{
		$data=get_Category($_REQUEST['oid']);
	}
	else
	{
		$data=array('Error'=>'Malformed Request','Line'=>__LINE__);
	}
}
else
{
	$data=array('Error'=>'Unauthorized','Line'=>__LINE__);
}

echo json_encode($data);

?>