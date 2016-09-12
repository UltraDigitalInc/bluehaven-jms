<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

$data='';

if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
{
	//echo 'SEC<br>';
	define('SYS_CR_LF',"\r\n");
	include ('../connect_db.php');
	include ('./xml_func.php');
	//echo 'INC<br>';
	
	$qryp0 = "select O.officeid,O.name from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);
	
	$qry0  = "select S.securityid,S.officeid,S.filestoreaccess,(select count(id) from jest..logstate where securityid=S.securityid) as clog ";
	$qry0 .= "from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	//if ($nrow0 > 0 and $row0[''])
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='xml')
	{
		//echo 'CALL<br>';
		if (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_qbwcXML_config')
		{
			$data .=get_qbwcXML_config($_REQUEST['oid']);
		}
		elseif (isset($_REQUEST['subq']) and $_REQUEST['subq']=='get_CustomerBaseData')
		{
			$data .=get_CustomerBaseData($_REQUEST['cid'],$_REQUEST['dtype']);
		}
	}
	else
	{
		$data .="<Error>Malformed Request (" . __LINE__ . ")</Error>";
		//$data=$data."Debug:<br> " . print_r($_REQUEST) . "";
	}
}
else
{
	$data .="<Error>Unauthorized (" . __LINE__ . ")</Error>";
}

header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="qbwcXML-file.qwc"');

//echo '<PRE>';
print($data);
//echo '</PRE>';
exit;

?>