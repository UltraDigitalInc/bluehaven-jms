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
	include ('./ajax_help_nodes_func.php');
	//echo 'INC<br>';
	
	$jms_db	=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest_doc');
	
	$qryp0 = "select O.officeid,O.name,O.accountingsystem from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);

	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='helpnodes')
	{
		//echo 'CALL<br>';
		if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='getHelpNode')
		{
			//echo 'list<br>';
			$data=get_HelpNode($_REQUEST['nodeid']);
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