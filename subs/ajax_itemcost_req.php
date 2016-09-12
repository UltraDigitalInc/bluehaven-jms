<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='send_Invoice_InfoXX')
{
echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
exit;
}

$data='';
if (isset($_SESSION['securityid']) and !empty($_SESSION['securityid']) and $_SESSION['securityid']!=0)
{
	//echo 'SEC<br>';
	include ('../connect_db.php');
	include ('./ajax_itemcost_func.php');
	//echo 'INC<br>';
	
	$jms_db	=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest');
	
	$qryp0 = "select O.officeid,O.name,O.enquickbooks,O.pb_code from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);
	
	if ($rowp0['pb_code']=='0')
	{
		$pb_code='';
	}
	else
	{
		$pb_code=$rowp0['pb_code'];
	}
	
	if (isset($rowp0['enquickbooks']) and $rowp0['enquickbooks']==1)
	{
		$qryp1 = "select * from qbwcConfig where oid=".$_SESSION['officeid'].";";
		$resp1 = mssql_query($qryp1);
		$rowp1 = mssql_fetch_array($resp1);
		$nrowp1= mssql_num_rows($resp1);
		
		if ($nrowp1!=0 and (isset($rowp1['qb_soap_host'])))
		{
			$qbs_db	=array('hostname'=>$rowp1['qb_soap_host'],'username'=>$rowp1['qb_soap_user'],'password'=>$rowp1['qb_soap_pass'],'dbname'=>$rowp1['qb_soap_db']);
		}
		else
		{
			echo 'Error. DB not defined or could not connect. ('.__LINE__ .')';
			exit;
		}
	}
	else
	{
		$qbs_db=array();
	}
	
	$qry0 = "select S.securityid,S.officeid from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='itemcost')
	{
		if (isset($_REQUEST['qact']) and ($_REQUEST['qact']=='ItemServiceAdd' or $_REQUEST['qact']=='ItemNonInventoryAdd' or $_REQUEST['qact']=='ItemInventoryAdd'))
		{
			//$data=SyncServiceItems($_REQUEST['oid'],$_REQUEST['qact'],$pb_code,$jms_db);
			$data=SyncItems($_REQUEST['oid'],$_REQUEST['qact'],$pb_code,$jms_db);
		}
		else
		{
			$data='Action Not Found ('.__LINE__.')';
		}
	}
	else
	{
		$data=$data."Malformed Request (" . __LINE__ . ")<br>";
		//$data=$data."Debug:<br> " . print_r($_REQUEST) . "";
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