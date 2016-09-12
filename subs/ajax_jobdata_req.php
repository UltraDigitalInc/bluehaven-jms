<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);

include ('./ajax_common_func.php');

if (isLoggedIn())
{	
	if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
	{
		include ('../connect_db.php');
		include ('./ajax_jobdata_func.php');
		
		$qry = "SELECT securityid,acctngrelease from security where securityid=".(int) $_SESSION['securityid'].";";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		
		if (isset($_REQUEST['call']) and $_REQUEST['call']=='job')
		{
			if ($row['acctngrelease']==1)
			{
				ajaxEventProc(1);
				
				if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='jsonJobData')
				{
					$out	=JobData($_REQUEST['oid'],$_REQUEST['jid']);
					
					header('Content-Type: text/javascript');
					echo json_encode($out);
				}
				elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='expJobData')
				{
					$frtype	=trim($_REQUEST['frtype']);
					$out	=JobData($_REQUEST['oid'],$_REQUEST['jid']);
					
					if ($frtype=='Summary')
					{
						$fopts	=array('Customer'=>1,'OfficeCode'=>1,'JobNumber'=>1,'FirstName'=>1,'LastName'=>1,'Address'=>1,'City'=>1,'State'=>1,'Zip'=>1,'Phone'=>1,'JobType'=>1,'ContractAmount'=>1,'CostTotal'=>1);
					}
					else
					{
						$fopts	=array('Customer'=>1,'Item'=>1,'Description'=>1,'Quantity'=>1,'Price'=>1);
					}
					
					//echo '<br>------------------------------------<br>';
					//echo '<pre>';
					//print_r($out['cst']);
					//echo '</pre>';
					exportJob($out,$frtype,$fopts);
				}
			}
			else
			{
				header('HTTP/1.1 401 Unauthorized');
				echo '401 Unauthorized ('.__LINE__.')';
			}
		}
		else
		{
			header('HTTP/1.1 400 Bad Request');
			echo '400 Bad Request ('.__LINE__.')';
		}
	}
	else
	{
		header('HTTP/1.1 401 Unauthorized');
		echo '401 Unauthorized ('.__LINE__.')';
	}
}
else
{
	header('HTTP/1.1 403 Forbidden');
	echo '403 Forbidden ('.__LINE__.')';
}