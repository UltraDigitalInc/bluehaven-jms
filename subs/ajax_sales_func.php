<?php
error_reporting(E_ALL);

function get_SIDInfo($oid,$sid,$uoid,$usid)
{
	$out=array();
	
	$qry = "select
				C.securityid as sid,
				C.fname,
				C.lname,
				C.officeid as oid,
				C.slevel,
				C.sidm,
				(select lname from security where securityid=C.sidm) as smlname,
				(select fname from security where securityid=C.sidm) as smfname
			from
				security as C
			where
				C.securityid=". $sid .";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	//echo $qry;
	
	if ($nrow == 1)
	{
		if ($row['oid']==$oid)
		{
			$out=array('sid'=>$row['sid'],'oid'=>$row['oid'],'sidm'=>$row['sidm'],'slevel'=>explode(',',$row['slevel']));
		}
		else
		{
			$out=array('sid'=>0,'Error'=>'OID - SID Mismatch');
		}
	}
	else
	{
		$out=array('sid'=>0,'Error'=>'No SID Found');
	}
	
	return $out;
}

function get_CustomerQuery($oid,$sid,$clev,$stext)
{
	$out = "
		select 
			 C.cid
			,C.officeid as oid
			,C.clname
			,C.cfname
			,C.securityid as sid
			,(select lname from security where securityid=C.securityid) as srlname
			,(select fname from security where securityid=C.securityid) as srfname
			,C.sidm
			,(select lname from security where securityid=C.sidm) as smlname
			,(select fname from security where securityid=C.sidm) as smfname
			,(select count(digdate) from jobs where custid=C.cid) as digs
			,C.added
		from 
			cinfo AS C
		where
			C.officeid=".$oid." 
		";
		
		if ($clev == 4)
		{
			$out .= "
				and (C.securityid = ".$sid." or C.sidm = ".$sid.")
			";
		}
		elseif ($clev == 1)
		{
			$out .= "
				and C.securityid = ".$sid." 
			";
		}
		
		if (!empty($stext) and strlen($stext) > 0)
		{
			$out .= "
				and C.clname like '".$stext."%'
			";
		}
		
		$out .= "
			and hidden = 0
			and dupe = 0;
			";
	
	return $out;
}

function get_CustomerList($oid,$sid,$stext)
{
	$out=array();
	$uoid=$_SESSION['officeid'];
	$usid=$_SESSION['securityid'];
	$oid=55;
	$sid=1550;
	
	$s=get_SIDInfo($oid,$sid,$uoid,$usid);
	
	//print_r($s);
	//echo $s['slevel'][2];
	//exit;
	
	if ($s['sid']!=0)
	{
		$qry0 = get_CustomerQuery($oid,$sid,$s['slevel'][2],$stext);
		
		//echo $qry0;
		//exit;
		$res0 = mssql_query($qry0);	
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			while ($row0= mssql_fetch_array($res0))
			{
				$cres[$row0['cid']] = array('cid'=>$row0['cid'],'oid'=>$row0['oid'],'sid'=>$row0['sid'],'clname'=>$row0['clname'],'cfname'=>$row0['cfname'],'sid'=>$row0['sid'],'srlname'=>$row0['srlname'],'srfname'=>$row0['srfname'],'sidm'=>$row0['sidm'],'smlname'=>$row0['smlname'],'smfname'=>$row0['smfname'],'digs'=>$row0['digs'],'added'=>strtotime($row0['added']));
			}
			
			$out['count']=$nrow0;
			$out['result']=$cres;
		}
		else
		{
			$out['count']=$nrow0;
			$out['result']=array('Error'=>'No Customer Records Found');
		}
	}
	else
	{
		$out['count']=0;
		$out['result']=array('Error'=>$s['Error']);
	}
	
	return $out;
}

function get_Catalog($oid,$catid)
{
	$out=array();
	
	$qry = "select pb_code from offices where officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry0 = "select
				A.id as iid,A.item,A.catid,A.rp
			from [".trim($row['pb_code'])."acc] as A
			where
				A.officeid=".$oid."
				and catid=".$catid.";";
	$res0 = mssql_query($qry0);	
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{
		while ($row0= mssql_fetch_array($res0))
		{
			$cres[$row0['iid']] = array('iid'=>$row0['iid'],'iname'=>$row0['item'],'rp'=>$row0['rp']);
		}
		
		$out['count']=$nrow0;
		$out['result']=$cres;
	}
	else
	{
		$out['count']=$nrow0;
		$out['result']=array('Error'=>'No Catalog Records Found');
	}
	
	return $out;
}

function get_Category($oid)
{
	$out=array();
	
	$qry0 = "select catid,name from AC_cats where officeid=".$oid." order by name ASC;";
	$res0 = mssql_query($qry0);	
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{
		while ($row0= mssql_fetch_array($res0))
		{
			$cres[$row0['catid']] = array('catid'=>$row0['catid'],'cname'=>$row0['name'],'catdiv'=>'catdiv_' . $row0['catid']);
		}
		
		$out['count']=$nrow0;
		$out['result']=$cres;
	}
	else
	{
		$out['count']=$nrow0;
		$out['result']=array('Error'=>'No Catalog Records Found');
	}
	
	return $out;
}

function get_CustomerInfo($oid,$sid,$cid)
{
	$out=array();
	
	if (isset($cid) and $cid!=0)
	{
		$qry = "
			select 
				 C.cid
				,C.officeid as oid
				,C.securityid as sid
				,C.clname
				,C.cfname
				,(select lname from security where securityid=C.securityid) as srlname
				,(select fname from security where securityid=C.securityid) as srfname
				,C.sidm
				,(select lname from security where securityid=C.sidm) as smlname
				,(select fname from security where securityid=C.sidm) as smfname
				,(select count(digdate) from jobs where custid=C.cid) as digs
				,C.added
			from 
				cinfo AS C
			where
				C.officeid = ".$oid."
				and C.cid = ".$cid.";";
		$res = mssql_query($qry);	
		$nrow= mssql_num_rows($res);
		
		if ($nrow == 1)
		{
			$row= mssql_fetch_array($res);

			$out['count']=$nrow;
			$out['result']=array('cid'=>$row['cid'],'oid'=>$row['oid'],'sid'=>$row['sid'],'clname'=>$row['clname'],'cfname'=>$row['cfname'],'sid'=>$row['sid'],'srlname'=>$row['srlname'],'srfname'=>$row['srfname'],'sidm'=>$row['sidm'],'smlname'=>$row['smlname'],'smfname'=>$row['smfname'],'digs'=>$row['digs'],'added'=>strtotime($row['added']));
		}
		elseif ($nrow == 0)
		{
			$out['count']=$nrow;
			$out['result']=array('Error'=>'No Customer Records Found');
		}
		else
		{
			$out['count']=$nrow;
			$out['result']=array('Error'=>$nrow.' Customer Record(s) Found');
		}
	}
	else
	{
		$out['count']=0;
		$out['result']=array('Error'=>'CID: '.$cid);
	}
	
	return $out;
}

?>