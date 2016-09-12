<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

$data='';
if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
{
	define('HOST','192.168.100.45,1433');
	define('DB','jest');
	define('USER','jestadmin');
	define('PWD','into99black');
	
	//echo 'SEC<br>';
	include ('../connect_db.php');
	include ('./ajax_common_func.php');
	include ('./ajax_lead_view_func.php');
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='updateNames') {
		//echo 'TEST';
		//exit;
		$data=updateNames();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updateAddresses') {
		$data=updateAddresses();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updateContacts') {
		$data=updateContacts();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updateAppointments') {
		$data=updateAppointments();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updatePrivacy') {
		$data=updatePrivacy();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='setAppt') {
		$data=setAppoint();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getAppt') {
		$data=getAppoint();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='setCallback') {
		$data=setCallback();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='removeCallback') {
		$data=removeCallback();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='removeApptmnt') {
		$data=removeApptmnt();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getCallback') {
		$data=getCallback($_REQUEST['cid']);
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='setPrivacy') {
		$data=setPrivacy();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getHistory') {
		$data=getHistory();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updateLeadOwner') {
		$data=updateLeadOwner();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updateLeadStatus') {
		$data=updateLeadStatus();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updateSourceResult') {
		$data=updateLeadSrcRes();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getChangeOfficeForm') {
		$data=getChangeOfficeForm();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='saveChangeOfficeForm') {
		$data=saveChangeOfficeForm();
	}
	
	ajaxEventProc(0);
}
else
{
	$data=$data."Unauthorized (" . __LINE__ . ")";
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

?>