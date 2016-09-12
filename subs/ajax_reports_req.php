<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

$data='';
if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
{
	//echo 'SEC<br>';
	include ('../connect_db.php');
	include ('./ajax_reports_func.php');
	include ('./ajax_common_func.php');
	//echo 'INC<br>';

	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess,SCPageAdjust from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='reports') {
		//echo 'CALL<br>';
		if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='getSCAdjustForm') {
			//echo 'list<br>';
			if (isset($row0['SCPageAdjust']) && $row0['SCPageAdjust'] >=9) {
				ajaxEventProc(0);
				$data=get_SCAdjustForm($_REQUEST['sid']);
			}
			else {
				$data='Error: You are not authorized for this action';
			}
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='getSCValidateForm')
		{
			//echo 'list<br>';
			if (isset($row0['SCPageAdjust']) && $row0['SCPageAdjust'] >=9) {
				ajaxEventProc(0);
				$data=get_SCValidateForm($_REQUEST['sid'],$_REQUEST['tbal']);
			}
			else {
				$data='Error: You are not authorized for this action';
			}
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='getSCDeleteItemForm') {
			//echo 'list<br>';
			if (isset($row0['SCPageAdjust']) && $row0['SCPageAdjust'] >=9) {
				ajaxEventProc(0);
				$data=get_SCDeleteItemForm($_REQUEST['sid'],$_REQUEST['hid']);
			}
			else {
				$data='Error: You are not authorized for this action';
			}
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='getSCLegend') {
			$data=get_SCLegend();
		}
	}
	else {
		$data=$data."Malformed Request (" . __LINE__ . ")<br>";
	}
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

?>