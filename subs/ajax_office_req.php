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
	include ('./ajax_office_func.php');
	//echo 'INC<br>';
	
	$qryp0 = "select O.officeid,O.name,O.accountingsystem from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);
	
	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	//echo __FILE__.'<br>';
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='office')
	{
		//echo 'CALL<br>';
		if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_LastOfficeUpdate')
		{
			$data=get_LastOfficeUpdate($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_GeneralOfficeInfo')
		{
			$data=get_GeneralOfficeInfo($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_GeneralOfficeInfo')
		{
			$data=update_GeneralOfficeInfo($_REQUEST['gi_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_GeneralOfficeConfig')
		{
			$data=get_GeneralOfficeConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_GeneralOfficeConfig')
		{
			$data=update_GeneralOfficeConfig($_REQUEST['gc_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_PaymentScheduleConfig')
		{
			$data=get_PaymentScheduleConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_PaymentScheduleConfig')
		{
			$data=update_PaymentScheduleConfig($_REQUEST['ps_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_PricebookConfig')
		{
			$data=get_PricebookConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_PricebookConfig')
		{
			$data=update_PricebookConfig($_REQUEST['pb_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_FeeScheduleConfig')
		{
			$data=get_FeeScheduleConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_FeeScheduleConfig')
		{
			$data=update_FeeScheduleConfig($_REQUEST['es_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_MASAccountingConfig')
		{
			$data=get_MASAccountingConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_QBAccountingConfig')
		{
			$data=get_QBAccountingConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_AccountingTypeConfig')
		{
			$data=update_AccountingTypeConfig($_REQUEST['ac_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_AccountingXMLConfig')
		{
			$data=update_AccountingXMLConfig($_REQUEST['xl_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_AccountingQBXMLConfig')
		{
			$data=update_AccountingQBXMLConfig($_REQUEST['qb_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_FileStorageConfig')
		{
			$data=get_FileStorageConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_FileStorageConfig')
		{
			$data=update_FileStorageConfig($_REQUEST['fs_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_RoutingMatrixConfig')
		{
			$data=get_RoutingMatrixConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_RoutingMatrixConfig')
		{
			$data=update_RoutingMatrixConfig($_REQUEST['rm_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_SalesTaxConfig')
		{
			$data=get_SalesTaxConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_SalesTaxBaseConfig')
		{
			$data=update_SalesTaxBaseConfig($_REQUEST['st_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='add_SalesTaxItem')
		{
			$data=add_SalesTaxItem($_REQUEST['st_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='update_SalesTaxItem')
		{
			$data=update_SalesTaxItem($_REQUEST['st_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='delete_SalesTaxItem')
		{
			$data=delete_SalesTaxItem($_REQUEST['st_oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_FinanceConfig')
		{
			$data=get_FinanceConfig($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_OfficeInfo')
		{
			$data=get_OfficeInfo($_REQUEST['ffld'],$_REQUEST['vval']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_UserInfo')
		{
			$data=get_UserInfo($_REQUEST['vval']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_RetailPB')
		{
			$data=getretailpbcnt($_REQUEST['vval']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_CommsCnt')
		{
			$data=getcomms($_REQUEST['vval']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_CostCnt')
		{
			//echo 'CostCnt';
			$data=getcostcnt($_REQUEST['vval']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_ZipMatrix')
		{
			$data=get_ZipMatrix($_REQUEST['vval']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_AddOfficeForm')
		{
			$data=get_AddOfficeForm();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='proc_addOffice')
		{
			$data=proc_AddOffice();
			//$data=print_r($_REQUEST);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='copycost')
		{
			$data=copyPriceBookCost($_REQUEST['from'],$_REQUEST['to'],$_SESSION['securityid']);
			//$data=print_r($_REQUEST);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='add_NewTaxPermit')
		{
			$data=add_NewTaxPermit($_REQUEST['goid'],$_REQUEST['gcity'],$_REQUEST['gpermit'],$_REQUEST['gwryder'],$_REQUEST['gtaxrate'],$_SESSION['securityid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='upd_thisTaxRate')
		{
			$data=upd_thisTaxRate($_REQUEST['goid'],$_REQUEST['gstid'],$_SESSION['securityid']);
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

//echo '<pre>';
//print_r($_REQUEST);
//print_r($data);
//echo '</pre>';
//echo 'END<br>';