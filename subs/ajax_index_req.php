<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
$data='';

include ('./ajax_common_func.php');

if (isTimeOut())
{
	header('HTTP/1.1 403 Forbidden');
}
else
{
	if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
	{
		include ('../connect_db.php');
		include ('./ajax_index_func.php');
		
		if (isset($_REQUEST['call']) and $_REQUEST['call']=='index')
		{
			if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='LeadReport')
			{
				$data=LeadReport();
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='CustServ')
			{
				$data=CustServ();
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='SysAnn')
			{
				$data=SysAnn();
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='ConList')
			{
				$data=ConList();
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='LeadImportStatus')
			{
				$data=LeadImportStatus();
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='lead_report_daily_office')
			{
				$data=lead_report_daily_office();
			}
			elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='lead_report_daily_admin')
			{
				$data=lead_report_daily_admin();
			}
			
			ajaxEventProc(0);
			
			$data=(isset($_REQUEST['optype']) and $_REQUEST['optype']=='json')? json_encode($data) : $data ;
			
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