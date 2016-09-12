<?php
//Test sid copy
ini_set('display_errors','On');
error_reporting(E_ALL);
include ('connect_db.php');

function getcids($from)
{
    // Attempts to retrieve a list of Customers from an Office ($from)
    // Will only return cids for Customers that are not marked hidden, duplicate, and have no Est
    
    $cids_out=array();
    
    if (isset($from) and $from != 0)
    {
        $qry  = "
                declare @oid int;
                set @oid=".(int) $from.";
                
                select
                    f.cid, f.clname
                from
                    jest..cinfo as f
                where 
                    f.officeid=@oid
                    and f.jobid='0'
                    and f.dupe!=1
                    and f.hidden!=1
                order by f.cid;
        ";
        $res  = mssql_query($qry);
        $nrow = mssql_num_rows($res);
        
        if ($nrow > 0)
        {
            while ($row = mssql_fetch_array($res))
            {
                $cids_out[]=$row['cid'];
            }
        }
    }
    
    return $cids_out;
}

function moveleads($from,$to,$tsid,$psid)
{
    $out=array(false,0,0,'Nothing to do');
    
    if (isset($from) and $from!=0)
    {
        $cids=getcids($from);
        
        if (count($cids) > 0 and (isset($to) and $to!=0))
        {
            $cnt=0;
            foreach ($cids as $n => $v)
            {
                $cnt++;
                $uid  = md5($n).'.'.$psid;
                $qry  = "DECLARE @tmid int ";
                $qry .= "DECLARE @oname char(32) ";
                $qry .= "DECLARE @pname char(32) ";
                $qry .= "SET @tmid=((SELECT MAX(custid) FROM cinfo WHERE officeid=".(int) $to.") + 1) ";
                $qry .= "SET @oname=(SELECT name FROM offices WHERE officeid=".(int) $from.") ";
                $qry .= "SET @pname=(SELECT (fname+' '+lname) FROM security WHERE securityid=".(int) $psid.") ";
                $qry .= "BEGIN TRAN ";
                $qry .= "UPDATE cinfo SET officeid=".(int) $to.",securityid=".(int) $tsid.",custid=@tmid WHERE officeid=".(int) $from." and cid=".(int) $v." ";
                $qry .= "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
                $qry .= "VALUES ";
                $qry .= "(".(int) $v.",".(int) $to.",".(int) $psid.",'leads','Lead Moved from '+ @oname +' by '+,'".$uid."')";
                $qry .= "COMMIT; ";
                //$resU = mssql_query($qryU);
                //echo $qry.'<br>';
            }
            
            $out=array(true,count($cids),$cnt,'Customers moved');
        }
        else
        {
            $out[3]='No Customers found';
        }
    }
    
    return $out;
}

function getretailpb($from)
{
    // Attempts to retrieve a list of Pricebook Retail entries from an Office ($from)
    // Will only return ids for entries that are not marked disabled
    
    $aids_out=array('result'=>false,'category'=>0,'retail'=>0,'otext'=>'Nothing to do');
    
    if (isset($from) and $from != 0)
    {
        //Pre: Pull Staging Info
        $qry0  = "select officeid,pb_code from offices where officeid=".(int) $from.";";
        $res0  = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        $pbc   = ($row0['pb_code']=='0')? '': trim($row0['pb_code']);
        
        //Stage 1: Pull Pricebook Categories
        $qry1  = "select catid,name,active,seqn,privcat,irequired,salestype from AC_cats where officeid=".(int) $from.";";
        $res1  = mssql_query($qry1);
        $nrow1 = mssql_num_rows($res1);
        
        if ($nrow1 > 0)
        {
            $cats_out=array();
            while ($row1 = mssql_fetch_array($res1))
            {
                $cats_out[]=array(
                                  'catid'=>$row1['catid'],
                                  'name'=>$row1['name'],
                                  'active'=>$row1['active'],
                                  'seqn'=>$row1['seqn'],
                                  'privcat'=>$row1['privcat'],
                                  'irequired'=>$row1['irequired'],
                                  'salestype'=>$row1['salestype']);
            }
            
            $aids_out['result']=true;
            $aids_out['category']=$cats_out;
            $aids_out['otext']='';
           
            //Stage 2: Setup Retail Pricebook Items
            $qry2  = "
                        select
                            id,aid,officeid,phsid,catid,matid,subid,item,atrib1,atrib2,atrib3,
                            rp,commtype,crate,qtype,spaitem,quan_calc,mtype,lrange,hrange,seqn,
                            supplier,bullet,def_quan,pdetect,royrelease,poolcalc
                        from [".$pbc."acc] where officeid=".$row0['officeid']." and disabled!=1";
            $res2  = mssql_query($qry2);
            $nrow2 = mssql_num_rows($res2);
            
            if ($nrow2 > 0)
            {
                $itms_out=array();
                while ($row2 = mssql_fetch_array($res2))
                {
                    $itms_out[$row2['id']]=array(
                        'id'=>$row2['id'],'aid'=>$row2['aid'],'officeid'=>$row2['officeid'],
                        'phsid'=>$row2['phsid'],'catid'=>$row2['catid'],
                        'matid'=>$row2['matid'],'subid'=>$row2['subid'],
                        'item'=>trim($row2['item']),'atrib1'=>trim($row2['atrib1']),'atrib2'=>trim($row2['atrib2']),'atrib3'=>trim($row2['atrib3']),
                        'rp'=>number_format($row2['rp'], 2, '.', ','),'commtype'=>$row2['commtype'],'crate'=>$row2['crate'],
                        'qtype'=>$row2['qtype'],'spaitem'=>$row2['spaitem'],'quan_calc'=>$row2['quan_calc'],'mtype'=>$row2['mtype'],
                        'lrange'=>$row2['lrange'],'hrange'=>$row2['hrange'],'seqn'=>$row2['seqn'],
                        'supplier'=>$row2['supplier'],'bullet'=>$row2['bullet'],'def_quan'=>$row2['def_quan'],
                        'pdetect'=>$row2['pdetect'],'royrelease'=>$row2['royrelease'],'poolcalc'=>$row2['poolcalc']
                    );
                }
                
                $aids_out['result']=true;
                $aids_out['retail']=$itms_out;
                $aids_out['otext']='';
            }
        }
    }
    
    return $aids_out;
}

function getCommsCnt($from)
{
    // Attempts to retrieve a list of Commission Profiles from an Office ($from)
    
    $cbs_out=array('ccnt'=>0);
    
    if (isset($from) and $from != 0)
    {
        $qry  = "select count(cmid) as ccnt from CommissionBuilder where oid=".(int) $from.";";
        $res = mssql_query($qry);
        $row = mssql_fetch_array($res);        
        
        $cbs_out=array('ccnt'=>$row['ccnt']);
    }
    
    return $cbs_out;
}

function checkOfficeInfo($name,$zip)
{
	$out=array('name_err'=>0,'zip_err'=>0);
	$name_err	=0;
	$zip_err	=0;
	$eoffname_ar=array();
	$eoffzip_ar	=array();
	
	$qry1 = "SELECT name,zip FROM offices order by name;";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	if ($nrow1 > 0)
	{
		while ($row1 = mssql_fetch_array($res1))
		{
			$eoffname_ar[]	=trim($row1['name']);
			$eoffzip_ar[]	=trim($row1['zip']);
		}
	}
	
    if (isset($name) && !empty($name))
    {
        foreach ($eoffname_ar as $n1 => $v1)
        {
            if (trim($name)===$v1)
            {
                $out['name_err']++;
            }
        }
    }
	
    if (isset($zip) && !empty($zip))
    {
        foreach ($eoffzip_ar as $n2 => $v2)
        {
            if (trim($zip)===$v2)
            {
                $out['zip_err']++;
            }
        }
    }
	
	return $out;
}

function setOfficeCreateOpts()
{
	$opts			=array();
	
	$opts['oidnew']	= 0;
	$opts['Name']	=(isset($_REQUEST['Name']) and !empty($_REQUEST['Name']))? trim($_REQUEST['Name']):'';
	$opts['Addr1']	=(isset($_REQUEST['Addr1']) and !empty($_REQUEST['Addr1']))? trim($_REQUEST['Addr1']):'';
	$opts['Addr2']	=(isset($_REQUEST['Addr2']) and !empty($_REQUEST['Addr2']))? trim($_REQUEST['Addr2']):'';
	$opts['City']	=(isset($_REQUEST['City']) and !empty($_REQUEST['City']))? trim($_REQUEST['City']):'';
	$opts['State']	=(isset($_REQUEST['State']) and !empty($_REQUEST['State']))? trim($_REQUEST['State']):'';
	$opts['Zip']	=(isset($_REQUEST['Zip']) and !empty($_REQUEST['Zip']))? trim($_REQUEST['Zip']):'';
	$opts['Phone']	=(isset($_REQUEST['Phone']) and !empty($_REQUEST['Phone']))? trim($_REQUEST['Phone']):'';
	$opts['gmlogid']=(isset($_REQUEST['gmlogid']) and !empty($_REQUEST['gmlogid']))? trim($_REQUEST['gmlogid']):'';
	$opts['gmpass']	=(isset($_REQUEST['gmpass']) and !empty($_REQUEST['gmpass']))? trim($_REQUEST['gmpass']):'';
	$opts['gmfirst']=(isset($_REQUEST['gmfirst']) and !empty($_REQUEST['gmfirst']))?trim($_REQUEST['gmfirst']):'';
	$opts['gmlast']	=(isset($_REQUEST['gmlast']) and !empty($_REQUEST['gmlast']))? trim($_REQUEST['gmlast']):'';
	$opts['oidsrc']	=(isset($_REQUEST['srcoffice']) and $_REQUEST['srcoffice']!=0)? trim($_REQUEST['srcoffice']):'';
	$opts['mvleads']=(isset($_REQUEST['mvleads']) and $_REQUEST['mvleads']==1)? true:false;
	$opts['tleads']	= 0;
	$opts['mvzips']	=(isset($_REQUEST['mvzips']) and $_REQUEST['mvzips']==1)? true:false;
	$opts['tzips']	= 0;
	$opts['cpyretail']=(isset($_REQUEST['cpyretail']) and $_REQUEST['cpyretail']==1)? true:false;
	$opts['tretail']= 0;
	$opts['cpycost']=(isset($_REQUEST['cpycost']) and $_REQUEST['cpycost']==1)? true:false;
	$opts['tcost']	= 0;
	$opts['cpycomms']=(isset($_REQUEST['cpycomms']) and $_REQUEST['cpycomms']==1)? true:false;
	$opts['tcomms']	=0;

	return $opts;
}

function gen_randstring($l = 8)
{
    $c = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";    
    $rs = '';
    for ($i = 0; $i < $l; $i++)
    {
        $rs .= $c[rand(0, strlen($c) - 1)];
    }
    return $rs;
}

function addUserNew($opts)
{
	$out=0;
   
    if (isset($opts['oidnew']) and $opts['oidnew']!=0)
    {
        if ((isset($opts['gmlogid']) and !empty($opts['gmlogid'])) and (isset($opts['gmpass']) and !empty($opts['gmpass'])))
        //if (isset($opts['oidnew']) and $opts['oidnew']!=0)
        {
            $gmlogid= $opts['gmlogid'];
            $gmpass	= md5($opts['gmpass']);
            $gmfirst= (isset($opts['gmfirst']) and !empty($opts['gmfirst']))? $opts['gmfirst']:'';
            $gmlast = (isset($opts['gmlast']) and !empty($opts['gmlast']))? $opts['gmlast']:'';
            $alevel	= '6,6,6,6,6,6,1';
        }
        else
        {
            do {
                $rs	=gen_randstring();
                $qry= "select login from jest..security where login='".$rs."';";
                $res= mssql_query($qry);
                $row= mssql_num_rows($res);
            }	
            while ($row > 0);
            
            $gmlogid= $rs;
            $gmpass	= md5($rs);
            $gmfirst= 'Temp';
            $gmlast = 'User';
            $alevel	= '0,0,0,1,0,0,1';
        }
        
        $qry  = "INSERT INTO jest..security ";
        $qry .= "(officeid,fname,lname,login,pswd,slevel,srep,hdate,email,newcommdate,stitle) ";
        $qry .= "VALUES ";
        $qry .= "(".$opts['oidnew'].",'".$gmfirst."','".$gmlast."','".$gmlogid."','".$gmpass."','".$alevel."',0,getdate(),'None',getdate(),'GM');";
        $qry .= "SELECT @@IDENTITY;";
        $res  = mssql_query($qry);
        $row  = mssql_fetch_row($res);
        $out  = $row[0];
    }

	return $out;
}

echo '<html>';
echo '<body>';
echo '<pre>';

//$cids=getcids(93);
//$ritems=getretailpb(93);
//$cc=getCommsCnt(96);
//var_dump(setOfficeCreateOpts());
//var_dump(moveleads(93,26,1797,26));
//var_dump(getretailpb(93));
//echo count($ritems['retail']);
//echo $cc['ccnt'];
//print_r($items);
$t=addUserNew(array('oidnew'=>53));
echo $t;
//echo generaterandstring();
echo '</pre>';
echo '</body>';
echo '</html>';

