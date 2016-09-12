<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

include ('../subs/ajax_common_func.php');

//get_Request_Info();

function get_RetailCategories($oid)
{
	$out=array();
	
	$qryA  = "SELECT officeid as oid,pb_code,active FROM [jest]..[offices] WHERE officeid=".(int) $oid.";";
	$resA  = mssql_query($qryA);
	$rowA  = mssql_fetch_array($resA);
	
	// Builds a list of exisiting categories in the retail accessory table by office
	$qryB  = "SELECT DISTINCT a.catid,a.name,a.seqn ";
	$qryB .= "FROM [jest]..[AC_cats] AS a INNER JOIN [jest]..[".trim($rowA['pb_code'])."acc] AS b ";
	$qryB .= "ON a.catid=b.catid ";
	$qryB .= "AND a.officeid=".(int) $oid." ";
	$qryB .= "AND a.active=1 ";
	$qryB .= "AND a.privcat!=1 ";
	$qryB .= "ORDER BY a.seqn ASC;";
	$resB = mssql_query($qryB);

	while ($rowB = mssql_fetch_array($resB))
	{
		$out[]=array('catid'=>$rowB['catid'],'catname'=>$rowB['name']);
	}
	
	return $out;
}

function get_RetailItems($oid,$catid,$estid)
{
	$out=array();
	
	$estdata = estdata($oid,$estid);

	$qryA  = "SELECT officeid as oid,pb_code,active FROM [jest]..[offices] WHERE officeid=".(int) $oid.";";
	$resA  = mssql_query($qryA);
	$rowA  = mssql_fetch_array($resA);
	
	$qryB   = "SELECT ";
	$qryB  .= "A.id as rid,A.qtype,A.item as ritem,A.rp as rprice,A.mtype,A.commtype,A.crate,A.quan_calc as qcalc, ";
	$qryB  .= "(SELECT abrv FROM jest..mtypes WHERE mid=A.mtype) as mabrv ";
	$qryB  .= "FROM [".trim($rowA['pb_code'])."acc] as A ";
	$qryB  .= "WHERE A.officeid=".(int) $oid." AND A.catid=".(int) $catid." AND A.disabled!='1' ORDER BY A.seqn;";
	$resB  = mssql_query($qryB);
	$nrowB = mssql_num_rows($resB);

	if ($nrowB > 0)
	{
		while ($rowB=mssql_fetch_array($resB))
		{
			$out[$rowB['rid']]=array(
									 'rid'=>$rowB['rid'],
									 'qtype'=>$rowB['qtype'],
									 'ritem'=>$rowB['ritem'],
									 'mabrv'=>$rowB['mabrv'],
									 'rprice'=>number_format($rowB['rprice'],2,'.',''),
									 'commtype'=>$rowB['commtype'],
									 'qcalc'=>$rowB['qcalc'],
									 'crate'=>$rowB['crate'],
									 'estinfo'=>array(),
									 'bidinfo'=>array(),
									 );
			
			if (array_key_exists($rowB['rid'],$estdata))
			{
				$out[$rowB['rid']]['estinfo']=$estdata[$rowB['rid']];
			}
			
			if ($rowB['qtype']==33)
			{
				$qryC  = "SELECT bidinfo FROM est_bids WHERE officeid=".(int) $oid." AND estid=".(int) $estid." AND bidaccid=".(int) $rowB['rid'].";";
				$resC  = mssql_query($qryC);
				$rowC  = mssql_fetch_array($resC);
				$nrowC = mssql_num_rows($resC);
				
				if ($nrowC==1)
				{
					$out[$rowB['rid']]['bidinfo']=array('bidinfo'=>$rowC['bidinfo']);
				}
			}
		}
	}
	
	return $out;
}

function estdata($oid,$estid)
{
	$out=array();
	
	if ($estid!=0)
	{		
		$qry = "SELECT estdata FROM est_acc_ext WHERE officeid=".(int) $oid." AND estid=".(int) $estid.";";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		
		if (strlen($row['estdata']) > 2)
		{
			$e=explode(",",trim($row['estdata']));
			foreach($e as $n1 => $v1)
			{
				$i=explode(":",$v1);
				$out[$i[0]]=array('id'=>$i[0],'qn'=>$i[2],'rp'=>number_format(($i[2]*$i[3]),2,'.',''),'cd'=>$i[4]);
			}
		}
	}
	
	return $out;
}

function basematrix()
{
	$data='';
	if (isset($_SESSION['securityid']) and !empty($_SESSION['securityid']) and $_SESSION['securityid']!=0)
	{
		include ('../connect_db.php');
		
		ajaxEventProc(0);
		
		if (isset($_REQUEST['call']) and $_REQUEST['call']=='get_RetailCategories')
		{
			$data=get_RetailCategories($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='get_RetailItems')
		{
			$data=get_RetailItems($_REQUEST['goid'],$_REQUEST['catid'],$_REQUEST['estid']);
		}
		elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='get_CustomerInfo_Estimate_Edit')
		{
			$data=get_CustomerInfo_Estimate_Edit($_REQUEST['oid'],$_REQUEST['estid']);
		}
		else
		{
			$data=array('errcnt'=>1,'error'=>"Malformed Request (" . __LINE__ . ")");
		}
	}
	else
	{
		$data=array('errcnt'=>1,'error'=>"Unauthorized (" . __LINE__ . ")");
	}
	
	if (isset($_REQUEST['optype']) and $_REQUEST['optype']=='json')
	{
		echo json_encode($data);
	}
	elseif (isset($_REQUEST['optype']) and $_REQUEST['optype']=='test')
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	else
	{
		echo $data;
	}
}

basematrix();

?>