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
	include ('./ajax_accounting_func.php');
	//echo 'INC<br>';
	
	$jms_db	=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest');
	
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
	
	$qry0 = "select S.securityid,S.officeid from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='accountingsystem')
	{
		if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='list_JMS_Released')
		{
			$data=list_JMS_Released($_REQUEST['oid'],$_REQUEST['yr'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='list_Queue_QB')
		{
			if (isset($_REQUEST['qstat']))
			{
				$qstat=$_REQUEST['qstat'];
			}
			else
			{
				$qstat='q';
			}
			
			$data=list_QB_Queue($_REQUEST['oid'],$qstat,$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='list_Processed_QB')
		{
			$data=list_QB_Processed($_REQUEST['oid'],'s',$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='release_Job_to_Accounting')
		{
			$data=release_Job_to_Accounting($_REQUEST['usr_oid'],$_REQUEST['usr_jobid'],$_REQUEST['usr_jadd'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='release_Job')
		{
			$data=release_Job($_REQUEST['usr_oid'],$_REQUEST['usr_jobid'],$_REQUEST['usr_jadd'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='revert_Job_to_System')
		{
			$data=revert_Job_to_System($_REQUEST['usr_oid'],$_REQUEST['usr_jid'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='send_Job_to_Accounting')
		{
			$data=send_Job_to_Accounting($_REQUEST['usr_oid'],$_REQUEST['usr_jid'],0,$_REQUEST['usr_qaction'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='send_Job_to_Accounting')
		{
			$data=send_PaySched_to_Accounting($_REQUEST['usr_oid'],$_REQUEST['usr_cid'],0,'InvoiceAdd',$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='send_Customer_to_Accounting')
		{
			$data=send_Customer_to_Accounting($_REQUEST['usr_oid'],$_REQUEST['usr_jid'],0,$_REQUEST['usr_qaction'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='revert_Job_to_Contract')
		{
			$data=revert_Job_to_Contract($_REQUEST['sys_oid'],$_REQUEST['usr_jid'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='clear_Accounting_State')
		{
			$data=clear_Accounting_State($_REQUEST['usr_oid'],$_REQUEST['usr_qid'],$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='clear_Accounting_State_Log')
		{
			$data=clear_Accounting_State_log($_REQUEST['usr_oid'],$_REQUEST['usr_qid'],$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='delete_from_Accounting_Log')
		{
			//$data='OUT';
			$data=delete_from_Accounting_Log($_REQUEST['usr_oid'],$_REQUEST['usr_qid'],$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='revert_Job_to_JMS_Released')
		{
			$data=revert_Job_to_JMS_Released($_REQUEST['usr_oid'],$_REQUEST['usr_qid'],$jms_db,$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='set_JMS_Job_Status')
		{
			$data=set_JMS_Job_Status($_REQUEST['usr_oid'],$_REQUEST['usr_jid'],'0',0,$_REQUEST['usr_jst'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='set_JMS_Job_Status_from_Job')
		{
			$data=set_JMS_Job_Status($_REQUEST['usr_oid'],0,$_REQUEST['usr_jobid'],0,$_REQUEST['usr_jst'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='list_Log')
		{
			$data=list_Log($_REQUEST['usr_oid'],$_REQUEST['usr_qstat'],$_REQUEST['usr_qact'],$_REQUEST['usr_lcnt'],$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_Job_Status')
		{
			$data=get_Job_Status($_REQUEST['usr_oid'],$_REQUEST['usr_jobid'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='list_JMS_Closed')
		{
			//$data=$_REQUEST['usr_oid'].':'.$_REQUEST['usr_cnt'];
			$data=list_JMS_Closed($_REQUEST['usr_oid'],$_REQUEST['usr_cnt'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='send_Customer_Info_by_CID')
		{
			//$data=$_REQUEST['usr_oid'].':'.$_REQUEST['usr_cnt'];
			$data=send_Customer_Info_by_CID($_REQUEST['usr_oid'],$_REQUEST['usr_cid'],$_REQUEST['usr_qact']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='send_Payment_Info')
		{
			//$data='Sent';
			//$data=$_REQUEST['usr_oid'].':'.$_REQUEST['usr_cnt'];
			$data=send_Payment_Info($_REQUEST['usr_oid'],$_REQUEST['usr_cid'],$_REQUEST['usr_qact']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='send_Invoice_Info')
		{
			//$data='Sent';
			//$data=$_REQUEST['usr_oid'].':'.$_REQUEST['usr_cnt'];
			$data=send_Invoice_Info($_REQUEST['usr_oid'],$_REQUEST['usr_cid'],$_REQUEST['usr_qact']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_Customer_Status')
		{
			//$data='Sent';
			$data=get_Customer_Status($_REQUEST['usr_cid'],$jms_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='send_Contract_Info')
		{
			//$data='Sent Contract';
			$data=send_Contract_Info($_REQUEST['usr_oid'],$_REQUEST['usr_cid'],$_REQUEST['usr_qact']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='list_JobCostData')
		{
			//$data='Sent';
			include ('../job_support_func.php');
			$data=proc_prior_jobcost($_REQUEST['proc_oid'],$_REQUEST['proc_jobid'],0,false);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_CustomerLifeCycle')
		{
			//$data='Sent';
			include ('../job_support_func.php');
			$data=get_CustomerLifeCycle($_REQUEST['usr_oid'],$_REQUEST['usr_cid'],$jms_db,$qbs_db);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_Prior_Job_Store')
		{
			//$data='Sent';
			include ('../job_support_func.php');
			$data=get_Prior_Job_Store($_REQUEST['usr_oid'],$_REQUEST['usr_jobid'],0,$jms_db);
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