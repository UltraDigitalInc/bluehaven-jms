<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);

include ('../connect_db.php');
include ('./ajax_common_func.php');

$data='';

if (isset($_REQUEST['ssid']) and isValidUser($_REQUEST['ssid']))
{
	//echo 'SEC<br>';
	include ('./ajax_checkrequest_func.php');
	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='CheckRequest')
	{
		//echo 'CALL<br>';
		if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_Pending_List')
		{
			//$data=$data.$_REQUEST['subq'].'<br>';
			$data=$data.get_Pending_List();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_Processed_List')
		{
			//$data=$data.$_REQUEST['subq'].'<br>';
			$data=$data.get_Processed_List();
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_Search_List')
		{
			//$data=$data.$_REQUEST['subq'].'<br>';
			$data=$data.get_Search_List();
		}
		else
		{
			//$data=$data.'<br>'.$_REQUEST['call'];
			//$data=$data.'<br>'.$_REQUEST['subq'];
			$data=$data."<br>Malformed Request (" . __LINE__ . ")";
		}
		
	}
	else
	{
		$data=$data."<br>Malformed Request (" . __LINE__ . ")";
	}
}
else
{
	$data=$data."<br>Unauthorized (" . __LINE__ . ")";
}


if (isset($_REQUEST['optype']) and $_REQUEST['optype']=='json')
{
	echo json_encode($data);
}
else
{
	echo $data;
}

//echo 'END<br>';

?>