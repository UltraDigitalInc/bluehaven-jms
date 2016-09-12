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
	include ('./ajax_estimate_func.php');
	
	//$data='TEST';
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='saveManualCommissionAdjust') {
		$data=saveManualCommissionAdjust();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='deleteManualCommissionAdjust') {
		$data=deleteManualCommissionAdjust();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getCommissionSchedule') {
		$data=getCommissionSchedule();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getEstimateSearchResult') {
		$data=EstimateSearchResult();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='getPriceBook') {
		$data=getPriceBook();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='CalcPBItems') {
		$data=CartItemAdd();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updatePoolDimensions') {
		$data=updatePoolDimensions();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='updateContractAmt') {
		$data=updateContractAmt();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='calcDimensions') {
		$data=calcDimensions();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='removeCartItem') {
		$data=CartItemRemove();
	}
	
	ajaxEventProc(0);
}
else {
	//$data=$data."Unauthorized (" . __LINE__ . ")";
	$data['error']=__LINE__;
	$data['result']='Unauthorized';
}

if (isset($_REQUEST['optype']) and $_REQUEST['optype']=='json') {
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($data);
}
else {
	//echo '<html><body><pre>';
	//echo print_r($data);
	//echo '</pre></body></html>';
	echo $data;
}