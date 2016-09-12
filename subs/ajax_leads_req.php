<?php
session_start();
error_reporting(E_ALL);
//echo 'START<br>';

$data='';
if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0) {
	include ('../connect_db.php');
	include ('./ajax_common_func.php');
	include ('./ajax_leads_func.php');
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='leads') {
		if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_AP_list') {
			$data=get_AP_list();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_AP_list_JSON') {
			$data=get_AP_list_JSON();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_CB_list') {
			$data=get_CB_list();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_ER_list') {
			$data=get_ER_list();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_NM_list') {
			$data=get_NM_list();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_LeadsbyName_list') {
			$data=get_LeadsbyName_List($_REQUEST['oid'],$_SESSION['securityid'],trim($_REQUEST['clname']));
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_LeadsbyCompany_list') {
			$data=get_LeadsbyCompany_List($_REQUEST['oid'],$_SESSION['securityid'],trim($_REQUEST['cpname']));
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_LeadCommentList') {
			$data=get_LeadCommentList_NEW($_REQUEST['sysCID']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='save_LeadComment') {
			$data=save_LeadComment($_SESSION['officeid'],$_SESSION['securityid'],$_REQUEST['sysCID'],$_REQUEST['cmnt'],$_REQUEST['cmntflag']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_OneSheetCmntSelector') {
			$data=get_OneSheetCmntSelector($_REQUEST['usr_cid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='getAppt') {
			$data=get_Appointment($_REQUEST['cid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='setAppt') {
			$data=set_Appointment();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='getCallback') {
			$data=get_Callback($_REQUEST['cid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='setCallback') {
			$data=set_Callback();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_FileList') {
			$data=get_FileList();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_EmailTemplates') {
			$data=get_EmailTemplates();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='getExtLeadMenu') {
			$data=get_ExtLeadMenu();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='save_EmailQueue') {
			$data=save_EmailQueue();
		}
		
		ajaxEventProc(0);
	}
	else
	{
		$data=$data."Malformed Request (" . __LINE__ . ")";
	}
}
else
{
	$data=$data."Unauthorized (" . __LINE__ . ")";
}


if (isset($_REQUEST['optype']) and $_REQUEST['optype']=='json') {
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($data);
}
else {
	echo $data;
}

?>