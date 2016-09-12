<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

$data='';
if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0) {
	//echo 'SEC<br>';
	include ('../connect_db.php');
	include ('./ajax_common_func.php');
	include ('./ajax_onesheet_func.php');
	
	//$data='TEST';
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='getConstructionDates') {
		$data=getConstructionDates();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getOneSheetComments') {
		$data=getOneSheetComments();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getCustomerLifeCycle')	{
		$data=getCustomerLifeCycle();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getOneSheetCmntSelector') {
		$data=getOneSheetCmntSelector();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='saveConstructionDate')	{
		$data=saveConstructionDate();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='saveOneSheetComment')	{
		$data=saveOneSheetComment();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='saveConstructionRecvAmt')	{
		$data=saveConstructionRecvAmt();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='clearConstructionDateLine') {
		$data=clearConstructionDateLine();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updateDigDate') {
		$data=updateDigDate();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='clearConstructionDigDate')	{
		$data=clearDigDate();
	}
	
	ajaxEventProc(0);
}
else
{
	//$data=$data."Unauthorized (" . __LINE__ . ")";
	$data['error']=__LINE__;
	$data['result']='Unauthorized';
}


if (isset($_REQUEST['optype']) and $_REQUEST['optype']=='json')
{
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($data);
}
else
{
	echo $data;
}