<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';
$data='';

include ('./ajax_common_func.php');
if (isTimeOut())
{
	echo "Session Expired (" . __LINE__ . ")";
	exit;
}

if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
{
	//echo 'SEC<br>';
	include ('../connect_db.php');
	include ('./ajax_user_func.php');
	//echo 'INC<br>';
	
	$jms_db	=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest');
	$res_db	=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest_ext');
	
	$qryp0 = "select O.officeid,O.name,O.enquickbooks from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);
	
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
	//echo 'INC<br>';

	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='user')
	{
		//echo 'CALL<br>';
		if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_JMSUserInfo')
		{
			//echo 'list<br>';
			$data=get_JMSUserInfo($_REQUEST['sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_JMSUserInfo')
		{
			//echo 'list<br>';
			$data=update_JMSUserInfo($_REQUEST['usr_sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_JMSSecurityInfo')
		{
			//echo 'list<br>';
			$data=get_JMSSecurityInfo($_REQUEST['sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_JMSSecurityInfo')
		{
			//echo 'list<br>';
			$data=update_JMSSecurityInfo($_REQUEST['usr_sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_JMSFunctionalInfo')
		{
			//echo 'list<br>';
			$data=get_JMSFunctionalInfo($_REQUEST['sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_JMSFunctionalInfo')
		{
			//echo 'list<br>';
			$data=update_JMSFunctionalInfo($_REQUEST['usr_sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_JMSProfilesInfo')
		{
			//echo 'list<br>';
			$data=get_JMSProfilesInfo($_REQUEST['sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='insert_JMSUserProfile')
		{
			//echo 'list<br>';
			$data=insert_JMSUserProfile($_REQUEST['usr_sid'],$_REQUEST['usr_asid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_JMSSalesRepInfo')
		{
			//echo 'list<br>';
			$data=get_JMSSalesRepInfo($_REQUEST['sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_MASAccountingInfo')
		{
			$data=get_MASAccountingInfo($_REQUEST['sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_QBSAccountingInfo')
		{
			//echo 'list<br>';
			$data=get_QBSAccountingInfo($_REQUEST['sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_MASAccountingInfo')
		{
			//$data=print_r($_REQUEST);
			$data=update_MASAccountingInfo($_REQUEST['usr_sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_QBSAccountingInfo')
		{
			//echo 'list<br>';
			$data=update_QBSAccountingInfo($_REQUEST['usr_sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='send_EmployeeAdd')
		{
			//echo 'list<br>';
			$data=send_EmployeeAdd($_REQUEST['usr_oid'],$_REQUEST['usr_sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_JMSUserSysInfo')
		{
			//echo 'list<br>';
			$data=get_JMSUserSysInfo($_REQUEST['sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_JMSAltOfficeAccessInfo')
		{
			//echo 'list<br>';
			$data=get_JMSAltOfficeAccessInfo($_REQUEST['sid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_systemLogIds')
		{
			//echo 'list<br>';
			$data=get_systemLogIds($_REQUEST['itext']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_EmployeeStatus')
		{
			//echo 'list<br>';
			$data=get_EmployeeStatus($_REQUEST['sid'],$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_QueryResults_List')
		{
			//echo 'list<br>';
			$data=get_QueryResults_List($_REQUEST['sid'],$res_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_QBSQueryResult')
		{
			$data=get_QBSQueryResult($_REQUEST['sid'],$_REQUEST['qid'],$_REQUEST['qact'],$res_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='query_EmployeeQBSConfig')
		{
			//echo 'list<br>';
			$data=query_EmployeeQBSConfig($_REQUEST['oid'],$_REQUEST['sid'],$_REQUEST['qb_LI'],$_REQUEST['qb_ES'],$_REQUEST['qb_lname'],$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='remove_EmployeeQueryResults')
		{
			//echo 'list<br>';
			$data=remove_EmployeeQueryResults($_REQUEST['sid'],$_REQUEST['qid'],$_REQUEST['qact'],$res_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='query_SalesRepQBSConfig')
		{
			//echo 'list<br>';
			$data=query_SalesRepQBSConfig($_REQUEST['oid'],$_REQUEST['sid'],$_REQUEST['qb_SRLI'],$_REQUEST['qb_SRES'],$_REQUEST['qb_lname'],$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_SalesRepQueryResults')
		{
			//echo 'list<br>';
			$data=get_SalesRepQueryResults($_REQUEST['sid'],$_REQUEST['qid'],$_REQUEST['qact'],$res_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_JMSSalesRepInfo')
		{
			//echo 'list<br>';
			$data=update_JMSSalesRepInfo($_REQUEST['usr_sid'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='insert_Alt_Security')
		{
			//echo 'list<br>';
			$data=insert_Alt_Security($_REQUEST['oid'],$_REQUEST['sid'],$_REQUEST['level'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='delete_Alt_Security')
		{
			//echo 'list<br>';
			$data=delete_Alt_Security($_REQUEST['oid'],$_REQUEST['sid'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_Alt_Security')
		{
			//echo 'list<br>';
			$data=update_Alt_Security($_REQUEST['oid'],$_REQUEST['sid'],$_REQUEST['mod'],$_REQUEST['lvl'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_SalesReps_JSON')
		{
			//echo $_REQUEST['subq'];
			$data=get_SalesReps_JSON($_REQUEST['oid'],$jms_db);
			//$data=$_REQUEST;
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