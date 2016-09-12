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
	include ('./ajax_job_func.php');
	//echo 'INC<br>';
	
	$jms_db	=array('hostname'=>'CORP-DB02','username'=>'jestadmin','password'=>'into99black','dbname'=>'jest');
	
	$qryp0 = "select O.officeid,O.name,O.accountingsystem from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);

	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='job')
	{
		if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_NextJobNumber')
		{
			$data=get_NextJobNumber($_REQUEST['sys_oid'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='checkContractDate')
		{
			$data=checkContractDate($_REQUEST['usr_oid'],$_REQUEST['usr_cid'],$_REQUEST['usr_NewContractDate'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='set_SandC')
		{
			$data=set_SandC($_REQUEST['usr_oid'],$_REQUEST['usr_jobid'],$_REQUEST['usr_sandc'],$jms_db);
		}
	}
	else
	{
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