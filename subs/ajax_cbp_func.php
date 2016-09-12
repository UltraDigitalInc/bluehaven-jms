<?php

function get_CommProfilesJSON($oid,$sid,$renov,$catg,$db)
{
    $out=array();
    
    $link=mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qryA  = "select CB.cmid,CB.d1,CB.d2,CB.ctype,(CB.rwdrate * 100) as rwdrate,CB.rwdamt,CB.trgwght,CB.trgsrc,CB.trgsrcval,CB.linkid,CB.ctgry as catid,CB.active ";
    $qryA .= "from jest..CommissionBuilder as CB ";
    $qryA .= "where oid=".(int) $oid." ";
    $qryA .= "and renov=".(int) $renov." ";
    
    if ($catg!=0)
    {
        $qryA .= "and ctgry=".(int) $catg." ";
    }
    
    $qryA .= "and secid=".(int) $sid." ";
    
    //$qryA .= "and active=1 ";
    $qryA .= "order by trgwght asc, ctgry asc, secid asc;";
	$resA = mssql_query($qryA);
    $nrowA= mssql_num_rows($resA);
	
	if ($nrowA > 1)
	{
		while ($rowA = mssql_fetch_array($resA))
		{
			$out['profiles'][]=$rowA;
		}
	}
	else
	{
		$rowA = mssql_fetch_array($resA);
		$out['profiles']=$rowA;
	}
    
	//echo $qryA;
	//echo '<pre>';
	//print_r($out);
	//echo '</pre>';
	
    return json_encode($out);
}

function save_CurrentCommProfile($oid,$cmid,$rwd,$trgsrcval,$db)
{
	$out=array();
    
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	if (isset($cmid) and $cmid!=0)
	{
		$qry0 = "select * from jest..CommissionBuilder where oid=".(int) $oid." and cmid=".(int) $cmid.";";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		$nrow0 = mssql_num_rows($res0);
		
		if ($nrow0==1)
		{	
			$qry1  = "update jest..CommissionBuilder set";
			
			if ($row0['ctype']==1)
			{
				$qry1 .= " rwdamt='".$rwd."',";
			}
			elseif ($row0['ctype']==2)
			{
				$rwd=($rwd * .01);
				
				$qry1 .= " rwdrate='".$rwd."',";
			}			
			
			$qry1 .= " trgsrcval='".$trgsrcval."'";
			$qry1 .= " where oid=".(int) $oid." and cmid=".(int) $cmid.";";
			$res1 = mssql_query($qry1);
			
			if (!$res1)
			{
				return 'DB Error ('.__LINE__.')';
			}
			else
			{
				return 'Profile Updated';
			}
		}
		else
		{
			return 'Profile not Found (' . __LINE__ .')';
		}
	}
	else
	{
		return 'CMID error (' . __LINE__ .')';
	}
	
	//return $out;
}

function ChangeProfileState($oid,$cmid,$state,$db)
{
	$out['result']=0;
	
	$link=mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$qryA = "select cmid,oid,ctgry,renov,active,linkid from jest..CommissionBuilder where oid=".(int) $oid." and cmid=".(int) $cmid.";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if ($rowA['linkid'] != 0)
	{
		$qryZa  = "update jest..CommissionBuilder set active=". (int) $state ." where oid=".(int) $oid." and linkid = ". (int) $rowA['linkid'] .";";
		$resZa = mssql_query($qryZa);
		$arowsZa = mssql_rows_affected($link);
		$out['result']=$arowsZa;
	}
	else
	{
		$qryZa  = "update jest..CommissionBuilder set active=". (int) $state ." where oid=".(int) $oid." and cmid = ". (int) $cmid .";";
		$resZa = mssql_query($qryZa);
		$arowsZa = mssql_rows_affected($link);
		$out['result']=$arowsZa;
	}
	
	return json_encode($out);
}

function ChangeProfileSettings($oid,$cmid,$sdata,$db)
{
	$out=array('result'=>0);
	$c_ar=array(1,2,4,6,8,9);
	$odata=get_object_vars(json_decode($sdata));
	
	$link=mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	if (is_array($odata) and in_array($odata['catid'],$c_ar))
	{
		$qryA = "select * from jest..CommissionBuilder where oid=".(int) $oid." and cmid=".(int) $cmid.";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		if ($odata['catid']==1 or $odata['catid']==2)
		{
			if ($odata['ctype']==1)
			{
				$qryB  = "update jest..CommissionBuilder set rwdamt=". (float) $odata['rwdamt'] .",trgsrcval=". (int) $odata['trgsrcval'] ." where oid=".(int) $odata['oid']." and cmid = ". (int) $odata['cmid'] .";";
				$resB = mssql_query($qryB);
			}
			elseif ($odata['ctype']==2)
			{
				$qryB  = "update jest..CommissionBuilder set rwdrate=". (float) ($odata['rwdrate'] * .01) .",trgsrcval=". (int) $odata['trgsrcval'] ." where oid=".(int) $odata['oid']." and cmid = ". (int) $odata['cmid'] .";";
				$resB = mssql_query($qryB);
			}
		}
		elseif ($odata['catid']==3)
		{
			// Reserved
		}
		elseif ($odata['catid']==4)
		{
			// Reserved
		}
		elseif ($odata['catid']==6 or $odata['catid']==9) // Bullet or Merit Bonus
		{
			if ($odata['cmid']==$rowA['linkid']) // Updating Primary Tier
			{
				
			}
			else
			{
				
			}
		}
		elseif ($odata['catid']==7)
		{
			// Reserved
		}
		elseif ($odata['catid']==8) // Minimum Commission
		{
			if ($odata['ctype']==1)
			{
				$qryB  = "update jest..CommissionBuilder set rwdamt=". (float) $odata['rwdamt'] .",trgsrc=". (int) $odata['trgsrc'] ." where oid=".(int) $odata['oid']." and cmid = ". (int) $odata['cmid'] .";";
				$resB = mssql_query($qryB);
			}
			elseif ($odata['ctype']==2)
			{
				$qryB  = "update jest..CommissionBuilder set rwdrate=". (float) ($odata['rwdrate'] * .01) .",trgsrc=". (int) $odata['trgsrc'] ." where oid=".(int) $odata['oid']." and cmid = ". (int) $odata['cmid'] .";";
				$resB = mssql_query($qryB);
			}
		}
		
		$out['result']=mssql_rows_affected($link);
		return json_encode($out);
	}
	else
	{
		header('HTTP/1.1 400 Bad Request');
	}
}

function save_NewCommProfile($oid,$sdata,$db)
{
	$out=array('result'=>0,'reason'=>'');
	$c_ar=array(1,2,6,8,9);
	$odata=get_object_vars(json_decode($sdata));
	
	$link=mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	if (is_array($odata) and in_array($odata['catid'],$c_ar))
	{
		$qryA ="select catid,label from jest..CommissionBuilderCategory where catid=".(int) $odata['catid'];
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		$qryAa ="select * from jest..CommissionBuilder where oid=".(int) $odata['oid']." and secid=".(int) $odata['sid']." and renov=".(int) $odata['btype']." and ctgry=".(int) $odata['catid'];
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		$nrowAa= mssql_num_rows($resAa);
		
		if ($odata['catid']==1 or $odata['catid']==2)
		{
			if ($nrowAa==0)
			{
				$qryB  = "insert into jest..CommissionBuilder (";
				$qryB .= "oid,sid,uid,secid,renov,ctgry,ctype,rwdrate,rwdamt,trgsrc,trgsrcval,trgwght,active,d1,d2,name,linkid";
				$qryB .= ") values (";
				$qryB .= (int) $odata['oid'].",". (int) $_SESSION['securityid'] .",". (int) $_SESSION['securityid'] .",". (int) $odata['sid'] .",";
				$qryB .= (int) $odata['btype'].",". (int) $odata['catid'] .",". (int) $odata['ctype'] .",". (float) ($odata['rwdrate'] * .01).",";
				$qryB .= (float) $odata['rwdamt'].",". (int) $odata['trgsrc'] .",". (int) $odata['trgsrcval'] .",";
				$qryB .= (float) $odata['trgwght'].",0,'1/1/1970','1/1/2025','".$rowA['label']."',0);";
				$resB = mssql_query($qryB);
			}
			else
			{
				$out['reason']='Commission for this Category or Profile already exists';
			}
		}
		elseif ($odata['catid']==6 or $odata['catid']==9) // Bullets or Merit Bonus
		{
			if ($nrowAa==0)
			{
				$qryB  = "insert into jest..CommissionBuilder (";
				$qryB .= "oid,sid,uid,secid,renov,ctgry,ctype,rwdrate,rwdamt,trgsrc,trgsrcval,trgwght,active,d1,d2,name";
				$qryB .= ") values (";
				$qryB .= (int) $odata['oid'].",". (int) $_SESSION['securityid'] .",". (int) $_SESSION['securityid'] .",". (int) $odata['sid'] .",";
				$qryB .= (int) $odata['btype'].",". (int) $odata['catid'] .",". (int) $odata['ctype'] .",". (float) ($odata['rwdrate'] * .01).",";
				$qryB .= (float) $odata['rwdamt'].",". (int) $odata['trgsrc'] .",". (int) $odata['trgsrcval'] .",";
				$qryB .= (float) $odata['trgwght'].",0,'1/1/1970','1/1/2025','".$rowA['label']."');";
				$qryB .= "update jest..CommissionBuilder set linkid=(select scope_identity()) where oid=200 and cmid=(select scope_identity());";
				$resB = mssql_query($qryB);
				//$rowB = mssql_fetch_array($resB);
				//$out['reason']=$qryB;
			}
			else
			{
				$out['reason']='Commission for this Category or Profile already exists. Create New Tier.';
			}
		}
		elseif ($odata['catid']==8) // Min Comm
		{
			if ($nrowAa==0)
			{
				$qryB  = "insert into jest..CommissionBuilder (";
				$qryB .= "oid,sid,uid,secid,renov,ctgry,ctype,rwdrate,rwdamt,trgsrc,trgsrcval,trgwght,active,d1,d2,name,linkid";
				$qryB .= ") values ("; 
				$qryB .= (int) $odata['oid'].",". (int) $_SESSION['securityid'] .",". (int) $_SESSION['securityid'] .",". (int) $odata['sid'] .",";
				$qryB .= (int) $odata['btype'].",". (int) $odata['catid'] .",". (int) $odata['ctype'] .",". (float) ($odata['rwdrate'] * .01).",";
				$qryB .= (float) $odata['rwdamt'].",". (int) $odata['trgsrc'] .",". (int) $odata['trgsrcval'] .",";
				$qryB .= (float) $odata['trgwght'].",0,'1/1/1970','1/1/2025','".$rowA['label']."',0);";
				$resB = mssql_query($qryB);
			}
			else
			{
				$out['reason']='Commission for this Category or Profile already exists';
			}
		}
	}
	else
	{
		$out['reason']='No Valid catid';
	}
	
	$out['result']=mssql_rows_affected($link);
	return json_encode($out);
}

function save_NewCommTier($oid,$sdata,$db)
{
	$out=array('result'=>0,'reason'=>'');
	$c_ar=array(6,9);
	$odata=get_object_vars(json_decode($sdata));
	
	$link=mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	if (is_array($odata) and in_array($odata['catid'],$c_ar))
	{
		$qryA ="select catid,label from jest..CommissionBuilderCategory where catid=".(int) $odata['catid'];
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		$qryAa ="select * from jest..CommissionBuilder where oid=".(int) $odata['oid']." and cmid=".(int) $odata['pcmid'];
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		$nrowAa= mssql_num_rows($resAa);
		
		if ($nrowAa==1)
		{
			$qryB  = "insert into jest..CommissionBuilder (";
			$qryB .= "oid,sid,uid,secid,renov,ctgry,ctype,rwdrate,rwdamt,trgsrc,trgsrcval,trgwght,active,d1,d2,name,linkid";
			$qryB .= ") values (";
			$qryB .= (int) $rowAa['oid'].",". (int) $_SESSION['securityid'] .",". (int) $_SESSION['securityid'] .",". (int) $rowAa['secid'] .",";
			$qryB .= (int) $rowAa['renov'].",". (int) $rowAa['ctgry'] .",". (int) $rowAa['ctype'] .",". (float) ($odata['nr'] * .01).",";
			$qryB .= (float) $odata['na'].",". (int) $rowAa['trgsrc'] .",". (int) $rowAa['trgsrcval'] .",";
			$qryB .= (float) $odata['nt'].",0,'1/1/1970','1/1/2025','".$rowA['label']."',".(int) $rowAa['linkid'].");";
			$resB = mssql_query($qryB);
		}
		else
		{
			$out['reason']='Base Tier not found';
		}
	}
	else
	{
		$out['reason']='No Valid catid';
	}
	
	$out['result']=mssql_rows_affected($link);
	return json_encode($out);
}

function get_AllCats($oid,$secid,$renov,$db)
{
    $out=array();
    
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qryA = "select distinct(ctgry) as catid,active from jest..CommissionBuilder where oid=".(int) $oid." and secid=".(int) $secid." and renov=".(int) $renov." order by ctgry asc;";
	$resA = mssql_query($qryA);
    $nrowA= mssql_num_rows($resA);
    
    while ($rowA = mssql_fetch_array($resA))
    {
        $out[]=array('catid'=>$rowA['catid'],'active'=>$rowA['active']);
    }
    
    return json_encode($out);
}

function get_CommBuildTypesJSON($db)
{
    $out=array();
    
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qryA = "SELECT catid,fullname FROM jest..CommissionBuilderCategory WHERE defaultcp=1 ORDER BY catid ASC;";
	$resA = mssql_query($qryA);
    $nrowA= mssql_num_rows($resA);
    
    while ($rowA = mssql_fetch_array($resA))
    {
        $out[$rowA['catid']]=array('cbcats'=>$rowA['fullname']);
    }
    
    return json_encode($out);
}

function get_SalesRepsJSON($oid,$db)
{
    $out=array();
    
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qryA = "SELECT securityid,(lname + ', ' + fname) as fullname FROM jest..security WHERE officeid=".(int) $oid." and srep=1 and substring(slevel,13,1) > 0 ORDER BY substring(slevel,13,1) desc,lname ASC,fname ASC;";
	$resA = mssql_query($qryA);
    $nrowA= mssql_num_rows($resA);
    
    while ($rowA = mssql_fetch_array($resA))
    {
        $out[$rowA['securityid']]=array('srcats'=>$rowA['fullname']);
    }
    
    return json_encode($out);
}

function delete_CommTier($oid,$cmid,$db)
{
	$c_ar=array(6,9);
	$link=mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$qryA = "SELECT cmid,ctgry as catid FROM jest..CommissionBuilder WHERE oid=".(int) $oid." and cmid=".(int) $cmid;
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if (in_array($rowA['catid'],$c_ar))
	{
		//$out='Deleting';
		$out=delete_CommProfile($oid,$cmid,$db);
		return $out;
	}
	else
	{
		$out['result']=0;
		$out['reason']='Not Tiered Commission Profile';
		return json_encode($out);
	}
}

function delete_CommProfile($oid,$cmid,$db)
{
	$out=array('result'=>0,'reason'=>'');
	
	$link=mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$qryA = "DELETE FROM jest..CommissionBuilder WHERE oid=".(int) $oid." and cmid=".(int) $cmid;
	$resA = mssql_query($qryA);
	
	$out['result']=mssql_rows_affected($link);
	return json_encode($out);
}

?>