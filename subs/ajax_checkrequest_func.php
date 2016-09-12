<?php

function get_Pending_List($dbg=0)
{
	$data='';
	if ($dbg==1)
	{
		$data=$data.'SESSID:'.session_id().'<br>';
		$data=$data.'SESSHS:'.$_SESSION['SessHash'].'<br>';
		$data=$data.'Pending List<br>';
	}
	
	return $data;
}

function get_Processed_List($dbg=0)
{
	$data='';
	if ($dbg==1)
	{
		$data=$data.'SESSID:'.session_id().'<br>';
		$data=$data.'SESSHS:'.$_SESSION['SessHash'].'<br>';
		$data=$data.'Processed List<br>';
	}
	
	return $data;
}

function get_Search_List($dbg=0)
{
	$data='';
	if ($dbg==1)
	{
		$data=$data.'SESSID:'.session_id().'<br>';
		$data=$data.'SESSHS:'.$_SESSION['SessHash'].'<br>';
		$data=$data.'Search List<br>';
	}
	
	return $data;
}

?>