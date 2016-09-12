<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';
$data='';

include ('./ajax_common_func.php');

if (isTimeOut())
{
	header('HTTP/1.1 403 Forbidden');
}
else
{
	if (
		isset($_SESSION['securityid'])
		and $_SESSION['securityid']!=0
		//and ValidOffice($_SESSION['officeid'],$_REQUEST['oid'],$_SESSION['tlev'])
		)
	{
		include ('../connect_db.php');
		include ('./ajax_cbp_func.php');
		
		$jms_db	=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest');
		$res_db	=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest_ext');
	
		$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		$nrow0= mssql_num_rows($res0);
		
		if (isset($_REQUEST['call']) and $_REQUEST['call']=='cbp')
		{
			//echo 'CALL<br>';
			if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_CommProfilesJSON')
			{
				$data=get_CommProfilesJSON($_REQUEST['oid'],$_REQUEST['sid'],$_REQUEST['reno'],$_REQUEST['catg'],$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='save_CurrentCommProfile')
			{
				$data=save_CurrentCommProfile($_REQUEST['oid'],$_REQUEST['cmid'],$_REQUEST['rwd'],$_REQUEST['trgsrcval'],$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_AllCats')
			{
				//echo 'list<br>';
				$data=get_AllCats($_REQUEST['oid'],$_REQUEST['sid'],$_REQUEST['renov'],$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_CommProfilesJSON')
			{
				$data=get_CommProfilesJSON($_REQUEST['oid'],$_REQUEST['sid'],$_REQUEST['reno'],$_REQUEST['catg'],$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_CommBuildTypes')
			{
				if ($_REQUEST['optype']=='html')
				{
					$data=get_CommBuildTypesHTML($jms_db);
				}
				else
				{
					$data=get_CommBuildTypesJSON($jms_db);
				}
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_SalesRepsJSON')
			{
				$data=get_SalesRepsJSON($oid,$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_OfficeInfo')
			{
				$data=get_OfficeInfoJSON($oid,$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='ChangeProfileState')
			{
				$data=ChangeProfileState($_REQUEST['oid'],$_REQUEST['cmid'],$_REQUEST['state'],$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='ChangeProfileSettings')
			{
				$data=ChangeProfileSettings($_REQUEST['oid'],$_REQUEST['cmid'],$_REQUEST['odata'],$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='save_NewCommProfile')
			{
				$data=save_NewCommProfile($_REQUEST['oid'],$_REQUEST['odata'],$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='save_NewCommTier')
			{
				$data=save_NewCommTier($_REQUEST['oid'],$_REQUEST['odata'],$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='delete_CommProfile')
			{
				$data=delete_CommProfile($_REQUEST['oid'],$_REQUEST['cmid'],$jms_db);
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='delete_CommTier')
			{
				$data=delete_CommTier($_REQUEST['oid'],$_REQUEST['cmid'],$jms_db);
			}
			
			ajaxEventProc(0);
			
			echo $data;
		}
		else
		{
			header('HTTP/1.1 400 Bad Request');
		}
	}
	else
	{
		header('HTTP/1.1 401 Unauthorized');
	}
}
?>