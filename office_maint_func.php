<?php

function update_taxrate()
{
	$qry0 = "UPDATE taxrate SET city='".replacequote($_REQUEST['city'])."',taxrate='".$_REQUEST['taxrate']."',wryder='".$_REQUEST['wryder']."',permit='".$_REQUEST['permit']."' WHERE officeid='".$_REQUEST['officeid']."' AND id='".$_REQUEST['id']."';";
	$res0 = mssql_query($qry0);

	viewoff($_REQUEST['officeid']);
}

function add_taxrate()
{
	$qry = "SELECT city FROM taxrate WHERE officeid='".$_REQUEST['officeid']."' AND city='".replacequote($_REQUEST['city'])."';";
	$res = mssql_query($qry);
	$nrow = mssql_num_rows($res);

	if ($nrow == 0)
	{
		$qry0 = "INSERT INTO taxrate (officeid,city,taxrate,wryder,permit) VALUES ('".$_REQUEST['officeid']."','".replacequote($_REQUEST['city'])."','".$_REQUEST['taxrate']."','".$_REQUEST['wryder']."','".$_REQUEST['permit']."');";
		$res0 = mssql_query($qry0);

		viewoff($_REQUEST['officeid']);
	}
	else
	{
		echo "Permit Entry Already EXISTS";
		viewoff($_REQUEST['officeid']);
	}
}

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

function moveLeads($from,$to,$tsid,$psid)
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
                $qry .= "SET @tmid=((SELECT isnull(MAX(custid),0) FROM cinfo WHERE officeid=".(int) $to.") + 1) ";
                $qry .= "SET @oname=(SELECT name FROM offices WHERE officeid=".(int) $from.") ";
                $qry .= "SET @pname=(SELECT (fname+' '+lname) FROM security WHERE securityid=".(int) $psid.") ";
                $qry .= "BEGIN TRAN ";
                $qry .= "UPDATE cinfo SET officeid=".(int) $to.",securityid=".(int) $tsid.",custid=@tmid WHERE officeid=".(int) $from." and cid=".(int) $v." ";
                $qry .= "COMMIT; ";
                $res = mssql_query($qry);
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

function moveZipMatrix($from,$to)
{
	$out=array(false,0,'');
	
	if ((isset($from) and $from!=0) and (isset($to) and $to!=0))
    {
		$qry0 = "select zip from jest..offices where officeid=".(int) $from.";";
        $res0 = mssql_query($qry0);
		$row0= mssql_fetch_array($res0);
		$nrow0= mssql_num_rows($res0);
		
		$qry1 = "select zip from jest..offices where officeid=".(int) $to.";";
        $res1 = mssql_query($qry1);
		$row1= mssql_fetch_array($res1);
		$nrow1= mssql_num_rows($res1);
		
		if (($nrow0!=0 and $nrow1!=0) and $row0['zip']!=$row1['zip'])
		{			
			$qry2  = "update jest..zip_to_zip set ozip='".$row1['zip']."' where ozip='".$row0['zip']."';";
			$res2 = mssql_query($qry2);
			
			$qry3 = "select count(id) as idcnt from jest..zip_to_zip where ozip='".$row1['zip']."';";
			$res3 = mssql_query($qry3);
			$row3 = mssql_fetch_array($res3);
			
			$out[0]=true;
			$out[1]=$row3['idcnt'];
			$out[2]=' Zips Moved';
		}
		else
		{
			$out[2]='Zip Move Error occurred';
		}
    }
	
	return $out;
}

function copyPriceBookRetail($from,$to,$psid)
{
	$out=array();
	
	if (isset($from) and $from!=0)
	{
		$rpb=getretailpb($from);
	}
	
	return $out;
}

function getcommissions($from)
{
    // Attempts to retrieve a list of Commission Profiles from an Office ($from)
    $cbs_out=array();

    if (isset($from) and $from != 0)
    {
        $qry  = "
				select 
					cmid,oid,sid,secid,ctgry,ctype,rwdrate,rwdamt,bcnt,trgwght,d1,d2,
					active,uid,name,trgsrc,linkid,trgsrcval,stype,renov,cat_override 
				from commissionbuilder where oid=".(int) $from." and active=1 and secid=0;";
        $res = mssql_query($qry);
        $row = mssql_fetch_array($res);
        
        $cbs_out[$row['cmid']]=$row;
    }
    
    return $cbs_out;
}

function copyCommissions($from,$to)
{
	$out=0;
	$comms=getcommissions($from);
	
	if (is_array($comms) and count($comms) > 0)
	{
		$qry = "select cmid from commissionbuilder where oid=".(int) $to.";";
        $res = mssql_query($qry);
		$nrow= mssql_num_rows($res);
		
		if ($nrow==0)
		{
			foreach($comms as $n => $v)
			{
				$qry1  = "insert into jest..CommissionBuilder (
					oid,sid,secid,ctgry,ctype,rwdrate,rwdamt,bcnt,trgwght,d1,d2,active,uid,name,trgsrc,linkid,trgsrcval,stype,renov,cat_override
					) values (
					".$v['oid'].",".$v['sid'].",".$v['secid'].",".$v['ctgry'].",".$v['type'].",'".$v['rwdrate']."','".$v['rwdamt']."',".$v['bcnt'].",".$v['trgwght'].",'".$v['d1']."','".$v['d2']."',
					".$v['active'].",'".$v['uid']."','".$v['name']."',".$v['trgsrc'].",".$v['linkid'].",".$v['trgsrcval'].",".$v['stype'].",".$v['renov'].",".$v['cat_override'].");";
				$res1 = mssql_query($qry1);
			}
		}
	}
}

function securityelements($array)
{
	if (is_array($array))
	{
		if ($_SESSION['tlev']==9)
		{
			$elemcnt=9;
		}
		else
		{
			$elemcnt=5;
		}

		echo "<table width=\"100%\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\"><b>Job/Est</b></td>\n";
		echo "      <td align=\"center\"><b>Leads</b></td>\n";
		echo "      <td align=\"center\"><b>Reports</b></td>\n";
		echo "      <td align=\"center\"><b>Messages</b></td>\n";
		echo "      <td align=\"center\"><b>Maint</b></td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\">\n";
		echo "         <select name=\"jlev\">\n";

		$e=0;
		while ($e <= $elemcnt)
		{

			if ($e==$array[0])
			{
				echo "         <option value=\"$e\" SELECTED>$e</option>\n";
			}
			else
			{
				echo "         <option value=\"$e\">$e</option>\n";
			}
			$e++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td align=\"center\">\n";
		echo "         <select name=\"llev\">\n";

		$l=0;
		while ($l <= $elemcnt)
		{

			if ($l==$array[1])
			{
				echo "         <option value=\"$l\" SELECTED>$l</option>\n";
			}
			else
			{
				echo "         <option value=\"$l\">$l</option>\n";
			}
			$l++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td align=\"center\">\n";
		echo "         <select name=\"rlev\">\n";

		$r=0;
		while ($r <= $elemcnt)
		{

			if ($r==$array[2])
			{
				echo "         <option value=\"$r\" SELECTED>$r</option>\n";
			}
			else
			{
				echo "         <option value=\"$r\">$r</option>\n";
			}
			$r++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td align=\"center\">\n";
		echo "         <select name=\"mlev\">\n";

		$m=0;
		while ($m <= $elemcnt)
		{

			if ($m==$array[3])
			{
				echo "         <option value=\"$m\" SELECTED>$m</option>\n";
			}
			else
			{
				echo "         <option value=\"$m\">$m</option>\n";
			}
			$m++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td align=\"center\">\n";
		echo "         <select name=\"tlev\">\n";

		$t=0;
		while ($t <= $elemcnt)
		{
			if ($t==$array[4])
			{
				echo "         <option value=\"$t\" SELECTED>$t</option>\n";
			}
			else
			{
				echo "         <option value=\"$t\">$t</option>\n";
			}
			$t++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function InsertOffice()
{
	$name_err	=0;
	$zip_err	=0;
	$code_err	=0;
	$eoffname_ar=array();
	$eoffzip_ar	=array();
	$eoffcode_ar	=array();
	
	$qry1 = "SELECT name,zip,code FROM offices order by name;";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	if ($nrow1 > 0)
	{
		while ($row1 = mssql_fetch_array($res1))
		{
			$eoffname_ar[]	=trim($row1['name']);
			$eoffzip_ar[]	=trim($row1['zip']);
			$eoffcode_ar[]	=trim($row1['code']);
		}
	}
	
	foreach ($eoffname_ar as $n1 => $v1)
	{
		if (trim($_REQUEST['Name'])===$v1)
		{
			$name_err++;
		}
	}
	
	foreach ($eoffzip_ar as $n2 => $v2)
	{
		if (trim($_REQUEST['Zip'])===$v2)
		{
			$zip_err++;
		}
	}
	
	foreach ($eoffcode_ar as $n3 => $v3)
	{
		if (trim($_REQUEST['Zip'])===$v3)
		{
			$code_err++;
		}
	}
	
	if ($name_err == 0 && $zip_err == 0 && $code_err == 0)
	{
		$qry2  = "INSERT INTO offices (active,grouping,code,name,addr1,addr2,city,state,zip,phone) values (";
		$qry2 .= "0,";
		$qry2 .= "0,";
		$qry2 .= "convert(int,'".trim($_REQUEST['Zip'])."'),";
		$qry2 .= "'".trim($_REQUEST['Name'])."',";
		$qry2 .= "'".trim($_REQUEST['Addr1'])."',";
		$qry2 .= "'".trim($_REQUEST['Addr2'])."',";
		$qry2 .= "'".trim($_REQUEST['City'])."',";
		$qry2 .= "'".trim($_REQUEST['State'])."',";
		$qry2 .= "'".trim($_REQUEST['Zip'])."',";
		$qry2 .= "'".trim($_REQUEST['Phone'])."'); ";
		$qry2 .= "SELECT @@IDENTITY;";
		$res2  = mssql_query($qry2);
		$row2  = mssql_fetch_row($res2);
		
		//echo $qry2.'<br>';
		
		if (isset($row2[0]) && $row2[0]!=0)
		{
			$to	 	 = "sschirmer@corp.bluehaven.com,thelton@corp.bluehaven.com";
			$sub	 = "New Office ".$_REQUEST['Name']."";
			$mess	 = "Name  : ".$_REQUEST['Name']."\r\n";
			$mess	 = "Zip   : ".$_REQUEST['Zip']."\r\n";
			$mess	.= "----------------------\r\n";
			$mess	.= "Admin : ".$_SESSION['fname']." ".$_SESSION['lname']."\r\n";
			$mess	.= "LHost : ".$_SERVER['SERVER_NAME']."\r\n";
			$mess	.= "RHost : ".getenv('REMOTE_ADDR')."\r\n";
			$mess	.= "----------------------\r\n";
			
			mail_out($to,$sub,$mess);
			
			EditOffice($row2[0]);
		}
		else
		{
			echo "<font color=\"red\"><b>Error!</b></font> Add Office failed.";
		}
	}
	else
	{
		if ($name_err!=0)
		{
			echo "<font color=\"red\"><b>Error!</b></font> The Office Name <b>".trim($_REQUEST['Name'])."</b> already Exists. Click the Back button and change the Name.<br>";
		}
		
		if ($zip_err!=0)
		{
			echo "<font color=\"red\"><b>Error!</b></font> The Office Zip Code: <b>".trim($_REQUEST['Zip'])."</b> already Exists. Click the Back button and change the Zip Code.<br>";
		}
		
		if ($code_err!=0)
		{
			echo "<font color=\"red\"><b>Error!</b></font> Office Code: <b>".trim($_REQUEST['Zip'])."</b> already Exists. Click the Back button and change the Zip Code.<br>";
		}
	}
	
}

function setOfficeCreateOpts()
{
	$opts			=array();
	
	$opts['oidnew']	= 0;
	$opts['otypecd']= (isset($_REQUEST['otype_code']) and !empty($_REQUEST['otype_code']))? trim($_REQUEST['otype_code']):0;
	$opts['Name']	= (isset($_REQUEST['Name']) and !empty($_REQUEST['Name']))? trim($_REQUEST['Name']):'';
	$opts['Addr1']	= (isset($_REQUEST['Addr1']) and !empty($_REQUEST['Addr1']))? trim($_REQUEST['Addr1']):'';
	$opts['Addr2']	= (isset($_REQUEST['Addr2']) and !empty($_REQUEST['Addr2']))? trim($_REQUEST['Addr2']):'';
	$opts['City']	= (isset($_REQUEST['City']) and !empty($_REQUEST['City']))? trim($_REQUEST['City']):'';
	$opts['State']	= (isset($_REQUEST['State']) and !empty($_REQUEST['State']))? trim($_REQUEST['State']):'';
	$opts['Zip']	= (isset($_REQUEST['Zip']) and !empty($_REQUEST['Zip']))? trim($_REQUEST['Zip']):'';
	$opts['Phone']	= (isset($_REQUEST['Phone']) and !empty($_REQUEST['Phone']))? trim($_REQUEST['Phone']):'';
	
	$opts['gmsid']	= 0;
	$opts['gmlogid']= (isset($_REQUEST['gmlogid']) and !empty($_REQUEST['gmlogid']))? trim($_REQUEST['gmlogid']):'';
	$opts['gmpass']	= (isset($_REQUEST['gmpass']) and !empty($_REQUEST['gmpass']))? trim($_REQUEST['gmpass']):'';
	$opts['gmfirst']= (isset($_REQUEST['gmfirst']) and !empty($_REQUEST['gmfirst']))?trim($_REQUEST['gmfirst']):'';
	$opts['gmlast']	= (isset($_REQUEST['gmlast']) and !empty($_REQUEST['gmlast']))? trim($_REQUEST['gmlast']):'';
	
	$opts['oidsrc']	= (isset($_REQUEST['srcoffice']) and $_REQUEST['srcoffice']!=0)? trim($_REQUEST['srcoffice']):'';
	$opts['mvleads']= (isset($_REQUEST['mvleads']) and $_REQUEST['mvleads']==1)? true:false;
	$opts['mvzips']	= (isset($_REQUEST['mvzips']) and $_REQUEST['mvzips']==1)? true:false;
	$opts['cpyretail']=(isset($_REQUEST['cpyretail']) and $_REQUEST['cpyretail']==1)? true:false;
	$opts['cpycost']= (isset($_REQUEST['cpycost']) and $_REQUEST['cpycost']==1)? true:false;
	$opts['cpycomms']= (isset($_REQUEST['cpycomms']) and $_REQUEST['cpycomms']==1)? true:false;
	$opts['tleads']	= 0;
	$opts['tzips']	= 0;
	$opts['tretail']= 0;
	$opts['tcost']	= 0;
	$opts['tcomms']	= 0;
	
	return $opts;
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

function InsertOfficeNew()
{
	$opts	=setOfficeCreateOpts();
	$oiderr	=checkOfficeInfo($opts['Name'],$opts['Zip']);
	
	if ($oiderr['name_err'] == 0 && $oiderr['zip_err'] == 0)
	{
		$qry2  = "INSERT INTO offices (active,grouping,code,otype_code,name,addr1,addr2,city,state,zip,phone) values (";
		$qry2 .= "1,";
		$qry2 .= "0,";
		$qry2 .= "convert(int,'".$opts['Zip']."'),";
		$qry2 .= "convert(int,'".$opts['otypecd']."'),";
		$qry2 .= "'".$opts['Name']."',";
		$qry2 .= "'".$opts['Addr1']."',";
		$qry2 .= "'".$opts['Addr2']."',";
		$qry2 .= "'".$opts['City']."',";
		$qry2 .= "'".$opts['State']."',";
		$qry2 .= "'".$opts['Zip']."',";
		$qry2 .= "'".$opts['Phone']."'); ";
		$qry2 .= "SELECT @@IDENTITY;";
		$res2  = mssql_query($qry2);
		$row2  = mssql_fetch_row($res2);
		$opts['oidnew']= $row2[0];
		
		if (isset($opts['oidnew']) and $opts['oidnew']!=0)
		{
			$opts['gmsid']=addUserNew($opts);
		}
		
		if (
			(isset($opts['oidnew']) and $opts['oidnew']!=0)
			and (isset($opts['oidsrc']) and $opts['oidsrc']!=0)
			and ($opts['mvleads'] or $opts['mvzips'] or $opts['cpyretail'] or $opts['cpycost'] or $opts['cpycomms'])
			)
		{			
			if ($opts['mvleads'] and $opts['gmsid']!=0)
			{
				$opts['tleads']=moveLeads($opts['oidsrc'],$opts['oidnew'],$opts['gmsid'],$_SESSION['securityid']);
			}
			
			if ($opts['mvzips'])
			{
				$opts['tzips']=moveZipMatrix($opts['oidsrc'],$opts['oidnew']);
			}
			
			/*
			if ($opts['cpyretail'])
			{
				$opts['tretail']=copyPricebookRetail($opts['oidsrc'],$opts['oidnew'],$_SESSION['securityid']);
			}
			*/
			
			//if ($opts['cpycost'])
			//{
			//	$opts['tcost']=copyPricebookCost();
			//}
			
			/*
			if ($opts['cpycomms'])
			{
				$opts['tcomms']=copyCommissions($opts['oidsrc'],$opts['oidnew'],$_SESSION['securityid']);
			}
			*/
			
		}
		
		//sendOfficeCreateMailer($opts);
		
		//print_r($opts);
		EditOffice($opts['oidnew']);
	}
	else
	{
		if ($oiderr['name_err']!=0)
		{
			echo "<font color=\"red\"><b>Error!</b></font> The Office Name <b>".trim($_REQUEST['Name'])."</b> already exists.<br>";
		}
		
		if ($oiderr['zip_err']!=0)
		{
			echo "<font color=\"red\"><b>Error!</b></font> The Office Zip Code: <b>".trim($_REQUEST['Zip'])."</b> already exists.<br>";
		}
	}
}

function sendOfficeCreateMailer($opts)
{
	$to	 	 = "sschirmer@corp.bluehaven.com,thelton@corp.bluehaven.com";
	$sub	 = "New Office ".$opts['Name']."";
	$mess	 = "Name  : ".$opts['Name']."\r\n";
	$mess	 = "Zip   : ".$opts['Zip']."\r\n\r\n";
	
	if (isset($opts['mvleads']) and $opts['mvleads']!=0)
	{
		$mess	 = "      : ".$opts['mvleads']." Leads were moved\r\n\r\n";
	}
	
	if (isset($opts['mvzipmatrix']) and $opts['mvzipmatrix']!=0)
	{
		$mess	 = "      : ".$opts['mvleads']." Zip Codes were moved in the Matrix\r\n\r\n";
	}
	
	if (isset($opts['cppbretail']) and $opts['cppbretail']!=0)
	{
		$mess	 = "      : ".$opts['cppbretail']." Retail Pricebook entries were copied\r\n\r\n";
	}
	
	if (isset($opts['cppbcost']) and $opts['cppbcost']!=0)
	{
		$mess	 = "      : ".$opts['cppcost']." Cost Pricebook entries were copied\r\n\r\n";
	}
	
	if (isset($opts['cpcomms']) and $opts['cpcomms']!=0)
	{
		$mess	 = "      : ".$opts['cpcomms']." Commission Profiles were copied\r\n\r\n";
	}
	
	$mess	.= "----------------------\r\n";
	$mess	.= "Admin : ".$_SESSION['fname']." ".$_SESSION['lname']."\r\n";
	$mess	.= "LHost : ".$_SERVER['SERVER_NAME']."\r\n";
	$mess	.= "RHost : ".getenv('REMOTE_ADDR')."\r\n";
	$mess	.= "----------------------\r\n";
	
	echo $to.'<br>';
	echo $from.'<br>';
	echo $mess.'<br>';
	//mail_out($to,$sub,$mess);
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
		
		if ($out!=0)
		{
			$qry  = "update jest..offices set gm=".$out.",am=".$out." where officeid=".$opts['oidnew'].";";
			$res  = mssql_query($qry);	
		}
    }

	return $out;
}

function addUser()
{
	//show_post_vars();
	//exit;
	
	$qry0 = "SELECT cmid FROM CommissionBuilder WHERE oid=".$_SESSION['officeid']." and active=1;";
	$res0 = mssql_query($qry0);
	$nrow0 = mssql_num_rows($res0);
	
	$qry = "SELECT securityid FROM security WHERE login='".$_REQUEST['login']."'";
	$res = mssql_query($qry);
	$nrow = mssql_num_rows($res);

	if (empty($_REQUEST['login']))
	{
		echo "<b>Login ID is Blank.... Click BACK and try again.</b>\n";
	}
	elseif (strlen($_REQUEST['login']) < 4)
	{
		echo "<b>Login ID is too Short. Must be between 4 and 8 characters.... Click BACK and try Again.</b>";
	}
	elseif (strlen($_REQUEST['login']) > 8)
	{
		echo "<b>Login ID is too Long. Must be between 4 and 8 characters.... Click BACK and try Again.</b>";
	}
	elseif (empty($_REQUEST['p1']))
	{
		echo "<b>Password Field is Blank.... Click BACK and try again.</b>\n";
	}
	elseif (strlen($_REQUEST['p1']) < 4)
	{
		echo "<b>Password is too Short. Password must be between 4 and 8 characters.... Click BACK and try Again.</b>";
	}
	elseif (strlen($_REQUEST['p1']) > 8)
	{
		echo "<b>Password is too Long. Password must be between 4 and 8 characters.... Click BACK and try Again.</b>";
	}
	elseif ($nrow > 0)
	{
		echo "<b>User Login: <font color=\"red\">".$_REQUEST['login']."</font> already exists.... Click BACK and try Again.</b>";
	}
	elseif (strlen($_REQUEST['hdate']) < 6 || !valid_date($_REQUEST['hdate']))
	{
		echo "<b>Hire Date: Not Filled in or Invalid Format.... Click BACK and try Again.</b>";
	}
	else
	{
		$npswd= md5($_REQUEST['p1']);
		
		if (isset($_REQUEST['salesrep']) && $_REQUEST['salesrep']==1)
		{
			$sr=1;
		}
		else
		{
			$sr=0;
		}
		
		if (isset($_REQUEST['alevel']) && is_array($_REQUEST['alevel']) && $_REQUEST['alevel'][6]==0)
		{
			$alevel='0,0,0,0,0,0,0';
		}
		elseif (isset($_REQUEST['alevel']) && is_array($_REQUEST['alevel']))
		{
			foreach ($_REQUEST['alevel'] as $n => $v)
			{
				if ($n==(count($_REQUEST['alevel']) - 1))
				{
					$alevel=$alevel.$v;
				}
				else
				{
					$alevel=$alevel.$v.",";
				}
			}
		}
		else
		{
			$alevel='0,0,0,0,0,0,0';
		}
		
		//if ($nrow0 > 0)
		//{
		//	$ncdate=date('m/d/Y',time());
		//}
		//else
		//{
		//	$ncdate='12/31/2025';
		//}

		$qryB  = "INSERT INTO jest..security ";
		$qryB .= "(officeid,fname,lname,login,pswd,slevel,srep,hdate,email,newcommdate,stitle) ";
		$qryB .= "VALUES ";
		$qryB .= "('".$_SESSION['officeid']."','".$_REQUEST['fname']."','".$_REQUEST['lname']."','".$_REQUEST['login']."','".$npswd."','".$alevel."','".$sr."','".$_REQUEST['hdate']."','".$_REQUEST['email']."','".$_REQUEST['hdate']."','".$_REQUEST['stitle']."');";
		$qryB .= "SELECT @@IDENTITY;";
		$resB  = mssql_query($qryB);
		$rowB  = mssql_fetch_row($resB);
		
		///echo $qryB.'<br>';
		///exit;
		if (is_numeric($rowB[0]) && $rowB[0] > 0)
		{
			if (EMAIL_OUT==true && $_REQUEST['alevel'][6]==1)
			{
				$to=array();
				$qry4 = "SELECT isnull(o.processor,0),s.email FROM offices as o inner join security on o.processor=s.securityid WHERE o.officeid='".$_SESSION['officeid']."';";
				$res4 = mssql_query($qry4);
				$row4 = mssql_fetch_array($res4);
				
				if ($row4['processor']!=0)
				{
					$to[]=$row4['email'];
				}
				
				$to[]	= 'thelton@corp.bluehaven.com';
				
				$sub	 = "JMS User Activated - ".$_SESSION['offname']." - ".$_REQUEST['lname']." ".$_REQUEST['fname'];
				$mess	 = "Office: ".$_SESSION['offname']."\r\n";
				$mess	.= "Name  : ".$_REQUEST['lname']." ".$_REQUEST['fname']."\r\n";
				$mess	.= "----------------------\r\n";
				$mess	.= "Admin : ".$_SESSION['lname']." ".$_SESSION['fname']."\r\n";
				$mess	.= "RHost : ".getenv('REMOTE_ADDR')."\r\n";
	
				SendSystemEmail('jmsadmin@bhnmi.com',$to,$sub,$mess);
			}

			listusers();
		}
		else
		{
			echo "<b>User Not Added (DB Return: Error -> secid)</b>";
		}
	}
}

function NewOffice()
{
	if ($_SESSION['tlev'] < 9)
	{
		AccessRightsError();
	}
	
	$eoff_ar=array(0=>'from...');
	$eoffname_ar=array();
	$eoffzip_ar	=array();
	
	$qry0 = "SELECT abrev,state FROM states ORDER BY abrev ASC;";
	$res0 = mssql_query($qry0);
	
	$qry1 = "SELECT officeid,name FROM offices where active=1 ORDER BY name ASC;";
	$res1 = mssql_query($qry1);
	
	while ($row1 = mssql_fetch_array($res1))
	{
		$eoff_ar[$row1['officeid']]=$row1['name'];
	}

	echo "<form id=\"NewOffice\" method=\"post\" onSubmit=\"return BasicFormCheck('NewOffice','ErrorText');\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"off\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"add2\">\n";
	echo "<input type=\"hidden\" name=\"active\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"grouping\" value=\"0\">\n";
	echo "<table align=\"center\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"left\">\n";
	echo "			<table class=\"outer\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td class=\"tblhd\" colspan=\"3\"><b>Add New Office</b></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\"><b>Name</b></td>\n";
	echo "					<td><input type=\"text\" name=\"Name\" size=\"25\"></td>\n";
	echo "					<td></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\"><b>Address 1</b></td>\n";
	echo "               	<td><input type=\"text\" name=\"Addr1\" size=\"25\"></td>\n";
	echo "					<td></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\"><b>Address 2</b></td>\n";
	echo "               	<td><input type=\"text\" name=\"Addr2\" size=\"25\"></td>\n";
	echo "					<td></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\"><b>City</b></td>\n";
	echo "               	<td><input type=\"text\" name=\"City\" size=\"25\"></td>\n";
	echo "					<td></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\"><b>State</b></td>\n";
	echo "               	<td>\n";
	echo "						<select name=\"State\">\n";
	echo "							<option value=\"0\">Select...</option>\n";
	
	while ($row0 = mssql_fetch_array($res0))
	{
		echo "							<option value=\"".$row0['abrev']."\">".$row0['abrev']."</option>\n";
	}
	
	echo "						</select>\n";
	echo "					</td>\n";
	echo "					<td></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               	<td align=\"right\"><b>Zip</b></td>\n";
	echo "               	<td><input type=\"text\" name=\"Zip\" size=\"25\"></td>\n";
	echo "					<td></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               	<td align=\"right\"><b>Phone</b></td>\n";
	echo "               	<td><input type=\"text\" name=\"Phone\" size=\"25\"></td>\n";
	echo "					<td></td>\n";
	echo "            </tr>\n";	
	echo "            <tr>\n";
	echo "               	<td align=\"right\" colspan=\"3\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<tr>\n";
	echo "               				<td align=\"center\">\n";
	echo "									<div id=\"ErrorText\"></div>\n";
	echo "								</td>\n";
	echo "								<td align=\"right\" width=\"20\">\n";
	echo "									<button title=\"Save New Office\">Save</button>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

}

function NewOfficeNew()
{
	if ($_SESSION['tlev'] < 9)
	{
		AccessRightsError();
	}
	
	$eoff_ar=array(0=>'Select...');
	$eoffname_ar=array();
	$eoffzip_ar	=array();
	
	$qry0 = "SELECT abrev,state FROM states ORDER BY abrev ASC;";
	$res0 = mssql_query($qry0);
	
	$qry1 = "SELECT officeid,name FROM offices where active=1 ORDER BY name ASC;";
	$res1 = mssql_query($qry1);
	
	while ($row1 = mssql_fetch_array($res1))
	{
		$eoff_ar[$row1['officeid']]=$row1['name'];
	}

	echo __FUNCTION__.'<br>';
	//echo "<script type=\"text/javascript\" src=\"js/jquery_office_add.js\"></script>\n";
	echo "<form id=\"addNewOffice\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"off\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"add2\">\n";
	echo "<input type=\"hidden\" name=\"active\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"grouping\" value=\"0\">\n";
	echo "<table class=\"outer\" width=\"400px\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"left\" width=\"400px\">\n";
	echo "			<table width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td class=\"tblhd\" colspan=\"3\"><b>Add New Office</b></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\"><b>Name</b></td>\n";
	echo "					<td><input class=\"JMStooltip officeffval\" type=\"text\" id=\"oname\" name=\"Name\" size=\"25\" title=\"Office Name is required\"></td>\n";
	echo "					<td></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\"><b>Address 1</b></td>\n";
	echo "               	<td><input class=\"JMStooltip\" type=\"text\" id=\"oaddr1\" name=\"Addr1\" size=\"25\" title=\"Office Address 1 is required\"></td>\n";
	echo "					<td></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\"><b>Address 2</b></td>\n";
	echo "               	<td><input type=\"text\" id=\"oaddr2\" name=\"Addr2\" size=\"25\"></td>\n";
	echo "					<td></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\"><b>City</b></td>\n";
	echo "               	<td><input class=\"JMStooltip\" type=\"text\" id=\"ocity\" name=\"City\" size=\"25\" title=\"City is required\"></td>\n";
	echo "					<td></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\"><b>State</b></td>\n";
	echo "               	<td>\n";
	echo "						<select class=\"JMStooltip\" id=\"ostate\" name=\"State\" title=\"Select a State\">\n";
	echo "							<option value=\"0\">Select...</option>\n";
	
	while ($row0 = mssql_fetch_array($res0))
	{
		echo "							<option value=\"".$row0['abrev']."\">".$row0['abrev']."</option>\n";
	}
	
	echo "						</select>\n";
	echo "					</td>\n";
	echo "					<td></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               	<td align=\"right\"><b>Zip</b></td>\n";
	echo "               	<td><input class=\"JMStooltip officeffval\" type=\"text\" id=\"ozip\" name=\"Zip\" size=\"25\" title=\"Office Zip Code is required and must not be used by another office\"></td>\n";
	echo "					<td></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               	<td align=\"right\"><b>Phone</b></td>\n";
	echo "               	<td><input class=\"JMStooltip officeffval\" type=\"text\" id=\"ophone\" name=\"Phone\" size=\"25\" title=\"Office Phone Number is required and must not be used by another office\"></td>\n";
	echo "					<td></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td colspan=\"3\"><img src=\"images/pixel.gif\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td class=\"tblhd\" colspan=\"3\" align=\"left\"><b>Owner/GM</b></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\"><b>Login ID</b></td>\n";
	echo "				<td><input class=\"JMStooltip userffval\" type=\"text\" id=\"gmlogid\" name=\"gmlogid\" size=\"15\" title=\"Login ID is required and must be between 4 and 8 characters in length\" autocomplete=\"off\"></td>\n";
	echo "				<td align=\"left\">\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\"><b>Password</b></td>\n";
	echo "				<td><input class=\"JMStooltip\" type=\"text\" id=\"gmpass\" name=\"gmpass\" size=\"15\" title=\"Password is required and must be between 4 and 16 characters in length\" autocomplete=\"off\"></td>\n";
	echo "				<td align=\"left\">\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\"><b>First Name</b></td>\n";
	echo "				<td><input class=\"JMStooltip\" type=\"text\" id=\"gmfirst\" name=\"gmfirst\" size=\"15\" title=\"First Name is required and must be more than 2 characters\"></td>\n";
	echo "				<td align=\"left\">\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\"><b>Last Name</b></td>\n";
	echo "				<td><input class=\"JMStooltip\" type=\"text\" id=\"gmlast\" name=\"gmlast\" size=\"15\" title=\"Last Name is required and must be more than 2 characters\"></td>\n";
	echo "				<td align=\"left\">\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td colspan=\"3\"><img src=\"images/pixel.gif\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td class=\"tblhd\" colspan=\"3\" align=\"left\"><b>Copy/Move Operations</b></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\"><b>Move Leads</b></td>\n";
	echo "				<td><input class=\"JMStooltip\" type=\"checkbox\" id=\"mvleads\" name=\"mvleads\" value=\"1\" title=\"Check this box to move leads from the office selected below\"></td>\n";
	echo "				<td align=\"left\">\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td align=\"right\"><b>Copy Pricebook</b></td>\n";
	echo "              <td><input class=\"JMStooltip\" type=\"checkbox\" id=\"cpyretail\" name=\"cpyretail\" value=\"1\" title=\"Check this box to copy the active Pricebook from the office selected below\"></td>\n";
	echo "				<td>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\"><b>Copy Cost</b></td>\n";
	echo "              <td><input class=\"JMStooltip\" type=\"checkbox\" id=\"cpycost\" name=\"cpycost\" value=\"1\" title=\"Check this box to copy active Cost Items (Labor & Material) from the office selected below\"></td>\n";
	echo "				<td>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\"><b>Copy Commissions</b></td>\n";
	echo "              <td><input class=\"JMStooltip\" type=\"checkbox\" id=\"cpycomm\" name=\"cpycomm\" value=\"1\" title=\"Check this box to copy active Commission Profiles from the office selected below\"></td>\n";
	echo "				<td>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td align=\"right\"><b>Source Office</b></td>\n";
	echo "              <td>\n";
	echo "					<select id=\"srcoffice\" name=\"srcoffice\">\n";
	
	foreach ($eoff_ar as $n0=>$v0)
	{
		echo "					<option value=\"".$n0."\">".$v0."</option>";
	}
	
	echo "					</select>\n";
	echo "				</td>\n";
	echo "				<td></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               	<td align=\"right\" colspan=\"3\">\n";
	echo "						<button id=\"SaveNewOfficeBtn\" title=\"Save New Office\">Save</button>\n";	
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function OfficeMenuBar()
{

	echo "<script type=\"text/javascript\" src=\"js/jquery_office_add.js\"></script>\n";	
	echo "<table class=\"outer\" align=\"center\" width=\"950px\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<table>\n";
	echo "				<tr>\n";
	echo "					<td><b>Office Menu</b></td>\n";
	echo "					<td><a class=\"getHelpNode\" id=\"OfficeMenuBar\" href=\"#\" title=\"Office Menu Bar Help\"><img src=\"images/help.png\"></a></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "		<td align=\"right\">\n";
	echo "			<table>\n";
	echo "				<tr>\n";
	echo "					<td>\n";
	
	if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332)
	{
		echo "   					<button id=\"addNewOfficebtn\"> Add Office </button>\n";
	}
	elseif ($_SESSION['securityid']==332)
	{
		echo "   					<form method=\"post\">\n";
		echo "   						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "   						<input type=\"hidden\" name=\"call\" value=\"off\">\n";
		echo "   						<input type=\"hidden\" name=\"subq\" value=\"add\">\n";
		echo "   						<button> Add Office </button>\n";
		echo "   					</form>\n";
	}
	
	echo "					</td>\n";
	echo "					<td>\n";
	echo "   					<form method=\"post\">\n";
	echo "   						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "   						<input type=\"hidden\" name=\"call\" value=\"off\">\n";
	echo "							<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
	echo "   						<input type=\"hidden\" name=\"req\" value=\"disabled\">\n";
	echo "							<button> View Disabled </button>\n";
	echo "   					</form>\n";
	echo "					</td>\n";
	echo "					<td>\n";
	echo "   					<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"off\">\n";
	echo "							<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
	echo "							<button> View Active </button>\n";
	echo "   					</form>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function ListOffices()
{
	error_reporting(E_ALL);
	if ($_SESSION['tlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}
	elseif ($_SESSION['tlev'] > 6)
	{
		$saddr="192.168.1.30";

		$qry0 = "SELECT id FROM zip_link;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		$qry0a = "SELECT officelist FROM security WHERE securityid=".$_SESSION['securityid'].";";
		$res0a = mssql_query($qry0a);
		$row0a = mssql_fetch_array($res0a);

		if (!empty($_REQUEST['req']) and $_REQUEST['req']=='disabled')
		{
			$qry = "
				select 
					o.*
					,(select otype_code from OfficeTypes where id=o.otype_code) as 'OffCode'
					,(select count(securityid) from security where officeid=o.officeid) as 'Users'
					,(select count(cid) from cinfo where officeid=o.officeid) as 'Leads'
					,(select count(estid) from est where officeid=o.officeid) as 'Estimates'
					,(select count(id) from jdetail where officeid=o.officeid and jadd=0) as 'Contracts'
					,(select count(id) from chistory where officeid=o.officeid) as 'Comments'
					,(select count(ozip) from zip_to_zip where ozip=o.zip) as 'Matrix'
				from 
					offices as o
				inner join
					officegroupcodes as G
				on
					G.code=o.[grouping]
				where 
					o.active!=1
				order by G.seqn asc,
			";
			
			if (isset($row0a['officelist']) && $row0a['officelist']=='N')
			{
				$qry .= "
					o.label_masoff_code asc;
				";
			}
			else
			{
				$qry .= "
					o.name asc;
					";
			}
		}
		else
		{
			$qry = "
				select 
					o.*
					,(select otype_code from OfficeTypes where id=o.otype_code) as 'OffCode'
					,(select count(securityid) from security where officeid=o.officeid) as 'Users'
					,(select count(cid) from cinfo where officeid=o.officeid) as 'Leads'
					,(select count(estid) from est where officeid=o.officeid) as 'Estimates'
					,(select count(id) from jdetail where officeid=o.officeid and jadd=0) as 'Contracts'
					,(select count(id) from chistory where officeid=o.officeid) as 'Comments'
					,(select count(ozip) from zip_to_zip where ozip=o.zip) as 'Matrix'
				from 
					offices as o
				inner join
					officegroupcodes as G
				on
					G.code=o.[grouping]
				where 
					o.active=1
				order by
					G.seqn asc,
			";
			
			if (isset($row0a['officelist']) && $row0a['officelist']=='N')
			{
				$qry .= "
					o.label_masoff_code asc;
				";
			}
			else
			{
				$qry .= "
					o.name asc;
					";
			}
		}

		$res = mssql_query($qry);
		
		echo "<table width=\"950px\" border=0>\n";
		echo "<tr>\n";
		echo "   <td>\n";
		echo "<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
		echo "<tr>\n";
		echo "   <td class=\"ltgray_und\" align=\"center\"><b>OID</b></td>\n";
		echo "   <td class=\"ltgray_und\" align=\"center\"><b>Own</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Name</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Label</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>City</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>ZIP</b></td>\n";
		echo "   <td class=\"ltgray_und\" width=\"90px\"><b>Phone</b></td>\n";
		echo "   <td class=\"ltgray_und\" width=\"90px\"><b>Ringto</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>GM</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Lead Admin</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>CS Rep</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Users</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Leads</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Esti</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Cont</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Comm</b></td>\n";
		echo "   <td class=\"ltgray_und\" align=\"center\"><b>Matrix</b></td>\n";
		echo "   <td class=\"ltgray_und\"></td>\n";
		echo "   <td class=\"ltgray_und\"></td>\n";
		echo "</tr>\n";

		$zipcnt=0;
		$ccnt	=0;
		while ($row = mssql_fetch_array($res))
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbg = 'ltgray';
                $tbgc = 'transnb';
			}
			else
			{
				$tbg = 'white';
                $tbgc = 'transnb';
			}
			
			$qryA = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['gm']."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);

			$qryB = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['sm']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			$qryC = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['am']."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			/*$qryD = "SELECT id FROM zip_to_zip WHERE ozip='".$row['zip']."';";
			$resD = mssql_query($qryD);
			$nrowD= mssql_num_rows($resD);*/
			
			$qryE = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['csrep']."';";
			$resE = mssql_query($qryE);
			$rowE = mssql_fetch_array($resE);

			//$zipcnt=$zipcnt+$nrowD;

			echo "<tr class=\"".$tbg."\">\n";
			echo"   <td align=\"center\">".$row['officeid']."</td>\n";
			echo"   <td align=\"center\">".$row['OffCode']."</td>\n";

			if ($row['active']==1)
			{
				echo"   <td align=\"left\"><b>".$row['name']."</b></td>\n";
			}
			else
			{
				echo"   <td align=\"left\"><font color=\"red\">".$row['name']."</font></td>\n";
			}

			echo "   <td>".$row['label_masoff_code']."</td>\n";
			echo "   <td align=\"left\">".$row['city']."</td>\n";
			echo "   <td align=\"center\">".$row['zip']."</td>\n";
			echo "   <td align=\"left\">".$row['phone']."</td>\n";
			echo "   <td align=\"left\">".$row['ringto']."</td>\n";
			echo "   <td align=\"left\">".$rowA['fname']." ".$rowA['lname']."</td>\n";
			echo "   <td align=\"left\">".$rowC['fname']." ".$rowC['lname']."</td>\n";
			echo "   <td align=\"left\">".$rowE['fname']." ".$rowE['lname']."</td>\n";
			echo "   <td align=\"center\">\n";

			if ($row['Users'] > 0)
			{
				echo "<b>".$row['Users']."</b>";
			}

			echo "	</td>\n";
			echo "   <td align=\"center\">\n";

			if ($row['Leads'] > 0)
			{
				echo "<b>".$row['Leads']."</b>";
			}

			echo "	</td>\n";
			echo "   <td align=\"center\">\n";

			if ($row['Estimates'] > 0)
			{
				echo "<b>".$row['Estimates']."</b>";
			}

			echo "	</td>\n";
			echo "   <td align=\"center\">\n";

			if ($row['Contracts'] > 0)
			{
				echo "<b>".$row['Contracts']."</b>";
			}

			echo "	</td>\n";
			echo "   <td align=\"center\">\n";

			if ($row['Comments'] > 0)
			{
				echo "<b>".$row['Comments']."</b>";
			}

			echo "	</td>\n";
			echo "   <td align=\"center\">\n";

			if ($row['Matrix'] > 0)
			{
				echo "<b>".$row['Matrix']."</b>";
			}

			echo "	</td>\n";
			echo "	<td align=\"center\">\n";
			echo "	<form method=\"post\">\n";
			echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
			echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
			echo "		<input type=\"hidden\" name=\"oid\" value=\"".$row['officeid']."\">\n";
			echo "		<input class=\"".$tbgc."\" type=\"image\" src=\"images/comments.png\" alt=\"Office Comments\">\n";
			echo "	</form>\n";
			echo "	</td>\n";
			echo "   <td align=\"right\">\n";
			echo "   <form method=\"post\">\n";
			echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "   	<input type=\"hidden\" name=\"call\" value=\"off\">\n";
			echo "   	<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
			echo "   	<input type=\"hidden\" name=\"officeid\" id=\"recid\" value=\"".$row['officeid']."\">\n";
			echo "		<input class=\"".$tbgc."\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Office\">\n";
			echo "   </form>\n";
			echo "   </td>\n";
			echo"</tr>\n";
		}

		echo "</table>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
}

function ViewPaySchedInfo($oid)
{
	$qry = "SELECT O.* FROM offices AS O WHERE O.officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	echo "         <table>\n";
	echo "            <tr>\n";
	echo "               <td align=\"left\" colspan=\"2\"><b>Current Payment Schedule</b></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\" valign=\"top\"></td>\n";
	echo "               <td>\n";
	//echo $row['psched'];

	$psched	=explode(",",$row['psched']);
	$pperc	=explode(",",$row['psched_perc']);
	$pcnt	=count($psched);

	if (is_array($psched) && $psched[0]!=0)
	{
		echo "<table>\n";

		foreach ($psched as $n1 => $v1)
		{
			//echo $v1."<br>";
			$qryZ		="SELECT phsname,phscode FROM phasebase WHERE phscode='".$v1."';";
			$resZ		= mssql_query($qryZ);
			$rowZ		= mssql_fetch_array($resZ);

			echo "<tr>\n";
			echo "	<td align=\"left\">".$rowZ['phscode']."</td>\n";
			echo "	<td align=\"left\">".$rowZ['phsname']."</td>\n";
			echo "	<td align=\"right\"><b>".$pperc[$n1]."</b></td>\n";
			echo "</tr>\n";
		}

		echo "<tr>\n";
		echo "	<td colspan=\"3\" align=\"right\"> Check to Delete Schedule: <input class=\"transnb\" type=\"checkbox\" name=\"del_psched\" value=\"1\"></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
	}
	else
	{
		$qryZa = "SELECT * FROM phasebase ORDER BY phscode ASC;";
		$resZa = mssql_query($qryZa);

		echo "		<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"off\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"psched_update\">\n";
		echo "		<input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";
		echo "         <table>\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" colspan=\"4\" align=\"left\"><font color=\"red\"><b>Payment Schedule has not been configured for this Office:</b></font></td>\n";
		echo "            </tr>\n";

		$xi=0;
		while ($rowZa = mssql_fetch_array($resZa))
		{
			$tbg = ($xi++ & 1) ? 'gray' : 'white';
			echo "<tr>\n";
			echo "	<td class=\"".$tbg."\" align=\"left\">".$rowZa['phscode']."</td>\n";
			echo "	<td class=\"".$tbg."\" align=\"left\">".$rowZa['phsname']."</td>\n";
			echo "	<td class=\"".$tbg."\" align=\"right\">\n";
			//echo "		<input type=\"hidden\" name=\"phs_".$rowZa['phscode']."\" value=\"".$rowZa['phscode']."\">\n";
			echo "		<input type=\"text\" name=\"per_".$rowZa['phscode']."\" value=\"0\" size=\"4\" maxlength=\"3\">\n";
			echo "	</td>\n";
			echo "</tr>\n";
		}

		echo "   			<tr>\n";
		echo "               <td class=\"gray\" colspan=\"4\" align=\"right\">\n";
		//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Update\">\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "         </table>\n";
		echo "		</form>\n";
	}
}

function ViewMatrixInfo($oid)
{
	$qry = "SELECT O.*,isnull((select count(ozip) from zip_to_zip where ozip=O.zip),0) as zcnt FROM offices AS O WHERE O.officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry1 = "SELECT COUNT(id) as mcnt FROM zip_to_zip WHERE ozip='".$row['zip']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	if ($row['zcnt']!=0 && strlen($row['ringto'])!=10)
	{
		$zmerrcnt=1;
		$zmerrtxt="<font color=\"red\"><b>Matrix Config Invalid!</b></font><br>Office has Matrix Ties but improper Ringto. Calls will not be processed properly";
	}
	else
	{
		$zmerrcnt=0;
		$zmerrtxt='';
	}
	
	$qryM = "SELECT SYS_ADMIN,MTRX_ADMIN FROM master..bhest_config;";
	$resM = mssql_query($qryM);
	$rowM = mssql_fetch_array($resM);
	
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"off\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"update_matrixinfo\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";
	echo "         <table>\n";
	echo "            <tr>\n";
	echo "               <td align=\"left\" colspan=\"3\"><font color=\"red\"><b>Caution!</b></font></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"left\" colspan=\"3\">Modify these fields with extreme care. Changes can<br>have unwanted effects on the Routing Systems</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "					<td align=\"right\"><b>Matrix Ties:</b></td>\n";
	echo "					<td align=\"left\">".$row1['mcnt']."</td>\n";
	echo "					<td align=\"left\">".$zmerrtxt."</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "					<td align=\"right\"><b>Zip Code:</b></td>\n";
	echo "               	<td align=\"left\" colspan=\"2\"><input class=\"JMStooltip\" type=\"text\" name=\"nozip\" value=\"".$row['zip']."\" size=\"20\" title=\"This should match the Office Zip Code\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "					<td align=\"right\"><b>Ringto:</b></td>\n";
	echo "               	<td align=\"left\" colspan=\"2\"><input class=\"JMStooltip\" type=\"text\" name=\"noringto\" value=\"".$row['ringto']."\" size=\"20\" title=\"Calls will be routed to this number\"></td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "</form\n";
}

function ViewSalesTaxInfo($oid)
{
	$qryA = "SELECT * FROM taxrate WHERE officeid=".$oid." ORDER BY city ASC;";
	$resA = mssql_query($qryA);
	
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\" >\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"off\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"add_taxrate\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";
	echo "         <table class=\"outer\" align=\"right\" border=0 width=\"100%\">\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" colspan=\"5\"><b>Tax & Permit Table:</b></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <input class=\"bboxl\" type=\"text\" name=\"city\" size=\"20\">\n";
	echo "               </td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <input class=\"bbox\" type=\"text\" name=\"permit\" size=\"5\">\n";
	echo "               </td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <input class=\"bbox\" type=\"text\" name=\"wryder\"  size=\"5\">\n";
	echo "               </td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <input class=\"bboxl\" type=\"text\" name=\"taxrate\" value=\"0.00\" size=\"5\">\n";
	echo "               </td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add New\">\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "</form>\n";
	
	echo "         <table class=\"outer\" align=\"right\" border=0 width=\"100%\">\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\"><b>City</b></td>\n";
	echo "               <td class=\"gray\"><b>Permit</b></td>\n";
	echo "               <td class=\"gray\"><b>/w Ryder</b</td>\n";
	echo "               <td class=\"gray\"><b>Tax Rate</b></td>\n";
	echo "               <td class=\"gray\"></td>\n";
	echo "            </tr>\n";

	while ($rowA = mssql_fetch_array($resA))
	{
		echo "            <tr>\n";
		//echo "                  <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		//echo "                  <input type=\"hidden\" name=\"action\" value=\"maint\" >\n";
		//echo "                  <input type=\"hidden\" name=\"call\" value=\"off\">\n";
		//echo "                  <input type=\"hidden\" name=\"subq\" value=\"update_taxrate\">\n";
		//echo "                  <input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";
		//echo "                  <input type=\"hidden\" name=\"id\" value=\"".$rowA['id']."\">\n";
		echo "               <td class=\"gray\">\n";
		echo $rowA['city'];
		//echo "                  <input class=\"bboxl\" type=\"text\" name=\"city\" value=\"".$rowA['city']."\" size=\"20\">\n";
		echo "               </td>\n";
		echo "               <td class=\"gray\">\n";
		echo $rowA['permit'];
		//echo "                  <input class=\"bbox\" type=\"text\" name=\"permit\" value=\"".$rowA['permit']."\" size=\"5\">\n";
		echo "               </td>\n";
		echo "               <td class=\"gray\">\n";
		echo $rowA['wryder'];
		//echo "                  <input class=\"bbox\" type=\"text\" name=\"wryder\" value=\"".$rowA['wryder']."\" size=\"5\">\n";
		echo "               </td>\n";
		echo "               <td class=\"gray\">\n";
		echo $rowA['taxrate'];
		//echo "                  <input class=\"bboxl\" type=\"text\" name=\"taxrate\" value=\"".$rowA['taxrate']."\" size=\"5\">\n";
		echo "               </td>\n";
		echo "               <td class=\"gray\">\n";
		//echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\">\n";
		echo "               </td>\n";
		//echo "                  </form>\n";
		echo "            </tr>\n";
	}
	
	echo "         </table>\n";

}			

function GenOffInfoPanel($oid)
{
	if ($_SESSION['tlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}

	if (isset($oid) && $oid!=0)
	{
		$officeid=$oid;
	}
	else
	{
		$officeid=$_REQUEST['officeid'];
	}

	$securityid=$_SESSION['securityid'];

	$qry = "SELECT O.*,isnull((select count(ozip) from zip_to_zip where ozip=O.zip),0) as zcnt FROM offices AS O WHERE O.officeid='".$officeid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($row['pb_code']==0)
	{
		$MAS='';
	}
	else
	{
		$MAS=$row['pb_code'];
	}

	$qryA = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resA = mssql_query($qryA);

	$qryD = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resD = mssql_query($qryD);

	$qryE = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT officeid,name FROM offices WHERE active='1' ORDER BY name;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT officeid,name,code,altcode FROM offices WHERE active='1' ORDER BY name;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT id,aid,item FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!='1' AND qtype='33' ORDER BY item;";
	$resH = mssql_query($qryH);
	
	$qryI = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['lupdate']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);
	
	$qryJ = "SELECT COUNT(id) as mcnt FROM zip_to_zip WHERE ozip='".$row['zip']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);
	
	$qryK = "SELECT securityid,lname,fname,substring(slevel,13,13) as tlevel FROM security WHERE officeid=89 and substring(slevel,13,13)>='1' ORDER BY lname;";
	$resK = mssql_query($qryK);
	
	$qry0 = "SELECT abrev,state FROM states ORDER BY abrev ASC;";
	$res0 = mssql_query($qry0);
	
	$qryP = "SELECT * FROM officegroupcodes ORDER BY seqn,name ASC;";
	$resP = mssql_query($qryP);
	
	$qryQ = "SELECT * FROM otypes ORDER BY otname ASC;";
	$resQ = mssql_query($qryQ);
	
	$qryR = "SELECT * FROM OfficeTypes ORDER BY otype_name ASC;";
	$resR = mssql_query($qryR);

	$mdate = date("m/d/Y", strtotime($row['masimport']));
	
	if ($row['lupdate']!=0)
	{
		$lupdate = $rowI['lname']." ".$rowI['fname']." (".date("m/d/Y h:m", strtotime($row['lupdtime'])).")";
	}
	else 
	{
		$lupdate = '&nbsp;';
	}
	
	if ($row['zcnt']!=0 && strlen($row['ringto'])!=10)
	{
		$zmerrcnt=1;
		$zmerrtxt="<font color=\"red\"><b>Matrix Config Invalid!</b></font><br>Office has Matrix Ties but improper Ringto. Calls will not be processed properly";
	}
	else
	{
		$zmerrcnt=0;
		$zmerrtxt='';
	}
	
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"off\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"update_geninfo\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"addr1\" value=\"".$row['addr1']."\">\n";
	echo "<input type=\"hidden\" name=\"addr2\" value=\"".$row['addr2']."\" >\n";
	echo "<input type=\"hidden\" name=\"city\" 	value=\"".$row['city']."\">\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"".$row['state']."\">\n";
	echo "<input type=\"hidden\" name=\"type\" 	value=\"".$row['type']."\">\n";
	echo "<input type=\"hidden\" name=\"comment\" value=\"".$row['comment']."\">\n";
	echo "         <table>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Name:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"name\" value=\"".trim($row['name'])."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Label:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"label_masoff_code\" value=\"".trim($row['label_masoff_code'])."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Office ID:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" value=\"".$row['officeid']."\" size=\"25\" DISABLED><input class=\"bboxl\" type=\"hidden\" name=\"code\" value=\"".$row['code']."\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Address:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"addr1\" value=\"".$row['addr1']."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"addr2\" value=\"".$row['addr2']."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>City:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"city\" value=\"".$row['city']."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>State:</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "					<select name=\"state\">\n";
	
	while ($row0 = mssql_fetch_array($res0))
	{
		if ($row0['abrev']==$row['state'])
		{
			echo "<option value=\"".$row0['abrev']."\" SELECTED>".$row0['abrev']."</option>\n";
		}
		else
		{
			echo "<option value=\"".$row0['abrev']."\">".$row0['abrev']."</option>\n";
		}
	}
	
	echo "					</select>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Active:</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"active\">\n";

	if ($row['active']==1)
	{
		echo "							<option value=\"1\" SELECTED>Yes</option>\n";
		echo "							<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "							<option value=\"1\">Yes</option>\n";
		echo "							<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Admin Only:</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"adminonly\">\n";

	if ($row['adminonly']==1)
	{
		echo "							<option value=\"1\" SELECTED>Yes</option>\n";
		echo "							<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "							<option value=\"1\">Yes</option>\n";
		echo "							<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Finance Office:</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"finan_off\">\n";

	if ($row['finan_off']==1)
	{
		echo "							<option value=\"1\" SELECTED>Yes</option>\n";
		echo "							<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "							<option value=\"1\">Yes</option>\n";
		echo "							<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Grouping:</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"grouping\">\n";

	while ($rowP=mssql_fetch_array($resP))
	{
		if ($row['grouping']==$rowP['code'])
		{
			echo "							<option value=\"".$rowP['code']."\" SELECTED>".$rowP['name']."</option>\n";
		}
		else
		{
			echo "							<option value=\"".$rowP['code']."\">".$rowP['name']."</option>\n";
		}
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Ownership:</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"otype_code\">\n";
	echo "							<option value=\"0\">NA</option>\n";

	while ($rowR=mssql_fetch_array($resR))
	{
		if ($row['otype_code']==$rowR['id'])
		{
			echo "							<option value=\"".$rowR['id']."\" SELECTED>".$rowR['otype_name']."</option>\n";
		}
		else
		{
			echo "							<option value=\"".$rowR['id']."\">".$rowR['otype_name']."</option>\n";
		}
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>System Type:</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"otype\">\n";

	while ($rowQ=mssql_fetch_array($resQ))
	{
		if ($row['otype']==$rowQ['otid'])
		{
			echo "							<option value=\"".$rowQ['otid']."\" SELECTED>".$rowQ['otname']."</option>\n";
		}
		else
		{
			echo "							<option value=\"".$rowQ['otid']."\">".$rowQ['otname']."</option>\n";
		}
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Accounting Code:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" value=\"".$row['code']."\" size=\"20\" DISABLED><input class=\"bboxl\" type=\"hidden\" name=\"code\" value=\"".$row['code']."\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Contractor Lic:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" value=\"".trim($row['conlicense'])."\" name=\"conlicense\" size=\"20\"></td>\n";
	echo "            </tr>\n";	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Phone:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"phone\" value=\"".$row['phone']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Fax:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"fax\" value=\"".$row['fax']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>GM:</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"gm\">\n";

	if ($row['gm']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
	}

	while ($rowA=mssql_fetch_array($resA))
	{
		if ($rowA['slev']==0)
		{
			$ostyle="fontred";
		}
		else
		{
			$ostyle="fontblack";
		}
		
		if ($rowA['securityid']==$row['gm'])
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowA['securityid']."\" SELECTED>".$rowA['lname']." ".$rowA['fname']."</option>\n";
		}
		else
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowA['securityid']."\">".$rowA['lname']." ".$rowA['fname']."</option>\n";
		}
	}
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>SM:</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"sm\">\n";

	if ($row['sm']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
	}

	while ($rowD=mssql_fetch_array($resD))
	{
		if ($rowD['slev']==0)
		{
			$ostyle="fontred";
		}
		else
		{
			$ostyle="fontblack";
		}
		
		if ($rowD['securityid']==$row['sm'])
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowD['securityid']."\" SELECTED>".$rowD['lname']." ".$rowD['fname']."</option>\n";
		}
		else
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowD['securityid']."\">".$rowD['lname']." ".$rowD['fname']."</option>\n";
		}
	}
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Lead Admin:</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"am\">\n";

	if ($row['am']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
	}

	while ($rowE=mssql_fetch_array($resE))
	{
		if ($rowE['slev']==0)
		{
			$ostyle="fontred";
		}
		else
		{
			$ostyle="fontblack";
		}
		
		if ($rowE['securityid']==$row['am'])
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowE['securityid']."\" SELECTED>".$rowE['lname']." ".$rowE['fname']."</option>\n";
		}
		else
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowE['securityid']."\">".$rowE['lname']." ".$rowE['fname']."</option>\n";
		}
	}
	
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Processor:</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"processor\">\n";
	echo "                     <option value=\"0\">None</option>\n";

	while ($rowK=mssql_fetch_array($resK))
	{
		if ($rowK['securityid']==$row['processor'])
		{
			echo "                     <option value=\"".$rowK['securityid']."\" SELECTED>".$rowK['fname']." ".$rowK['lname']."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowK['securityid']."\">".$rowK['fname']." ".$rowK['lname']."</option>\n";
		}
	}
	
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Leads Forward to:</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"leadforward\">\n";

	if ($row['leadforward']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">None</option>\n";
	}

	while ($rowF=mssql_fetch_array($resF))
	{
		if ($rowF['officeid']==$row['leadforward'])
		{
			echo "                     <option value=\"".$rowF['officeid']."\" SELECTED>".$rowF['name']."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowF['officeid']."\">".$rowF['name']."</option>\n";
		}
	}
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Alt PriceBook:</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"altcode\">\n";

	if ($row['altcode']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">None</option>\n";
	}

	while ($rowG=mssql_fetch_array($resG))
	{
		if ($rowG['code']==$row['altcode'])
		{
			echo "                     <option value=\"".$rowG['code']."\" SELECTED>".$rowG['name']."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowG['code']."\">".$rowG['name']."</option>\n";
		}
	}
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Commission Rate:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"com_rate\" value=\"".$row['com_rate']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Bullet Comm:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"bullet_rate\" value=\"".$row['bullet_rate']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Bullet Count:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"bullet_cnt\" value=\"".$row['bullet_cnt']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Overage Split:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"over_split\" value=\"".$row['over_split']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Avg GP:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"tgp\" value=\"".$row['tgp']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Retail vs Cost Var:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"vgp\" value=\"".$row['vgp']."\" size=\"5\">%</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Consult Fee:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"consfee\" value=\"".number_format($row['consfee'], 2, '.', '')."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Per Pool Acct Fee:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"pacctfee\" value=\"".number_format($row['pacctfee'], 2, '.', '')."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Accounting Fee:</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"acctfee\" value=\"".number_format($row['acctfee'], 2, '.', '')."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "			</table>\n";
	echo "</form>\n";
}

function GenOffConfigPanel($oid)
{
	
	if ($_SESSION['tlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}

	if (isset($oid) && $oid!=0)
	{
		$officeid=$oid;
	}
	else
	{
		$officeid=$_REQUEST['officeid'];
	}

	$securityid=$_SESSION['securityid'];

	$qry = "SELECT O.*,isnull((select count(ozip) from zip_to_zip where ozip=O.zip),0) as zcnt FROM offices AS O WHERE O.officeid='".$officeid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($row['pb_code']==0)
	{
		$MAS='';
	}
	else
	{
		$MAS=$row['pb_code'];
	}

	$qryA = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resA = mssql_query($qryA);

	$qryD = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resD = mssql_query($qryD);

	$qryE = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT officeid,name FROM offices WHERE active='1' ORDER BY name;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT officeid,name,code,altcode FROM offices WHERE active='1' ORDER BY name;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT id,aid,item FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!='1' AND qtype='33' ORDER BY item;";
	$resH = mssql_query($qryH);
	
	$qryI = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['lupdate']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);
	
	$qryJ = "SELECT COUNT(id) as mcnt FROM zip_to_zip WHERE ozip='".$row['zip']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);
	
	$qryK = "SELECT securityid,lname,fname,substring(slevel,13,13) as tlevel FROM security WHERE officeid=89 and substring(slevel,13,13)>='1' ORDER BY lname;";
	$resK = mssql_query($qryK);
	
	$qry0 = "SELECT abrev,state FROM states ORDER BY abrev ASC;";
	$res0 = mssql_query($qry0);
	
	$qryP = "SELECT * FROM officegroupcodes ORDER BY seqn,name ASC;";
	$resP = mssql_query($qryP);
	
	$qryQ = "SELECT * FROM otypes ORDER BY otname ASC;";
	$resQ = mssql_query($qryQ);
	
	$qryR = "SELECT * FROM OfficeTypes ORDER BY otype_name ASC;";
	$resR = mssql_query($qryR);

	$mdate = date("m/d/Y", strtotime($row['masimport']));
	
	if ($row['lupdate']!=0)
	{
		$lupdate = $rowI['lname']." ".$rowI['fname']." (".date("m/d/Y h:m", strtotime($row['lupdtime'])).")";
	}
	else 
	{
		$lupdate = '&nbsp;';
	}
	
	if ($row['zcnt']!=0 && strlen($row['ringto'])!=10)
	{
		$zmerrcnt=1;
		$zmerrtxt="<font color=\"red\"><b>Matrix Config Invalid!</b></font><br>Office has Matrix Ties but improper Ringto. Calls will not be processed properly";
	}
	else
	{
		$zmerrcnt=0;
		$zmerrtxt='';
	}
	
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"off\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"update_genconfig\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"addr1\" value=\"".$row['addr1']."\">\n";
	echo "<input type=\"hidden\" name=\"addr2\" value=\"".$row['addr2']."\" >\n";
	echo "<input type=\"hidden\" name=\"city\" 	value=\"".$row['city']."\">\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"".$row['state']."\">\n";
	echo "<input type=\"hidden\" name=\"type\" 	value=\"".$row['type']."\">\n";
	echo "<input type=\"hidden\" name=\"comment\" value=\"".$row['comment']."\">\n";
	echo "         <table>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Time Shift:</b></td>\n";
	echo "               <td><input class=\"bboxb JMStooltip\" type=\"text\" name=\"timeshift\" value=\"".$row['timeshift']."\" size=\"15\" title=\"Sets the Time Difference between the JMS System and the Office in seconds\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Estimate Menu</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"enest\">\n";

	if ($row['enest']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Contract Menu</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"encon\">\n";

	if ($row['encon']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Job Menu</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"enjob\">\n";

	if ($row['enjob']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Dig Report</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"endigreport\">\n";

	if ($row['endigreport']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
		echo "                     <option value=\"2\">Exclude</option>\n";
	}
	elseif ($row['endigreport']==1)
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
		echo "                     <option value=\"2\">Exclude</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
		echo "                     <option value=\"2\" SELECTED>Exclude</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Yearly Trip Info</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"entripinfo\">\n";

	if ($row['entripinfo']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>MAS Export</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"enexp\">\n";

	if ($row['enexp']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Sales Tax</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"stax\">\n";

	if ($row['stax']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Cost System</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"encost\">\n";

	if ($row['encost']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Deck Included</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"deckinc\">\n";

	if ($row['deckinc']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Manual Phase Adjust</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"manphsadj\">\n";

	if ($row['manphsadj']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>MAS Import</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"enmas\">\n";

	if ($row['enmas']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>MAS Import Date:</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" name=\"masimport\" id=\"d5\" value=\"".$mdate."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Lead Import/Export</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"ldexport\">\n";

	if ($row['ldexport']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Lead CC Email</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"leadmail\">\n";

	if ($row['leadmail']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Allowance Code:</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"all_code\">\n";
	echo "                     <option value=\"0\">None</option>\n";

	while ($rowH=mssql_fetch_array($resH))
	{
		$item=substr($rowH['item'], 0,20);
		if ($rowH['id']==$row['all_code'])
		{
			echo "                     <option value=\"".$rowH['id']."\" SELECTED>(".$rowH['aid'].") ".$item."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowH['id']."\">(".$rowH['aid'].") ".$item."</option>\n";
		}
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Logging:</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"logging\">\n";

	if ($row['logging']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>OpState Join:</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" name=\"gmrjoin\" value=\"".$row['gmrjoin']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	
	$qryFIN		="SELECT officeid,name FROM offices WHERE finan_off!=0 order by grouping,name;";
	$resFIN		= mssql_query($qryFIN);
	//$rowFIN		= mssql_fetch_array($resFIN);
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Financing Office:</b></td>\n";
	echo "               <td>\n";
	echo "               	<select name=\"finan_from\">\n";
	echo "                     <option value=\"0\">None</option>\n";
		
		while ($rowFIN=mssql_fetch_array($resFIN))
		{
			if ($rowFIN['officeid']==$row['finan_from'])
			{
				echo "                     <option value=\"".$rowFIN['officeid']."\" SELECTED>".$rowFIN['name']."</option>\n";
			}
			else
			{
				echo "                     <option value=\"".$rowFIN['officeid']."\">".$rowFIN['name']."</option>\n";
			}
		}
		
	echo "               	</select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	
	$qryFREP = "SELECT securityid,lname,fname,substring(slevel,13,1) FROM security WHERE officeid='".$row['finan_from']."' AND substring(slevel,13,1) >= 1 ORDER BY lname;";
	$resFREP = mssql_query($qryFREP);
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Def Finance Rep:</b></td>\n";
	echo "               <td>\n";
	echo "               	<select name=\"finan_rep\">\n";
	echo "                     <option value=\"0\">None</option>\n";
		
	while ($rowFREP=mssql_fetch_array($resFREP))
	{
		if ($rowFREP['securityid']==$row['finan_rep'])
		{
			echo "                     <option value=\"".$rowFREP['securityid']."\" SELECTED>".$rowFREP['lname'].", ".$rowFREP['fname']."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowFREP['securityid']."\">".$rowFREP['lname'].", ".$rowFREP['fname']."</option>\n";
		}
	}
		
	echo "               	</select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	
	$qryCSREP = "SELECT securityid,lname,fname,substring(slevel,13,1) FROM security WHERE officeid='".$row['officeid']."' AND substring(slevel,13,1) >= 1 ORDER BY lname;";
	$resCSREP = mssql_query($qryCSREP);
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Cust Service Rep:</b></td>\n";
	echo "               <td>\n";
	echo "               	<select name=\"csrep\">\n";
	echo "                     <option value=\"0\">None</option>\n";
		
	while ($rowCSREP=mssql_fetch_array($resCSREP))
	{
		if ($rowCSREP['securityid']==$row['csrep'])
		{
			echo "                     <option value=\"".$rowCSREP['securityid']."\" SELECTED>".$rowCSREP['lname'].", ".$rowCSREP['fname']."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowCSREP['securityid']."\">".$rowCSREP['lname'].", ".$rowCSREP['fname']."</option>\n";
		}
	}
		
	echo "               	</select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";	
	echo "            <tr>\n";
	echo "               <td align=\"right\" valign=\"top\"><b>File Storage:</b></td>\n";
	echo "               <td>\n";
	echo "					<table>\n";
	echo "            			<tr>\n";
	echo "							<td align=\"center\">Customer</td>\n";
	echo "							<td align=\"center\">Shared</td>\n";
	echo "							<td align=\"center\">Office</td>\n";
	echo "            			</tr>\n";
	echo "            			<tr>\n";
	echo "							<td align=\"center\">\n";
	echo "								<select name=\"fscustomer\">\n";
	
	if ($row['fscustomer']==0)
	{
		echo "									<option value=\"0\" SELECTED>No</option>\n";
		echo "									<option value=\"1\">Yes</option>\n";
	}
	else
	{
		echo "									<option value=\"0\">No</option>\n";
		echo "									<option value=\"1\" SELECTED>Yes</option>\n";
	}
	
	echo "								</select>\n";
	echo "							</td>\n";
	echo "							<td align=\"center\">\n";
	echo "								<select name=\"fsshared\">\n";
	
	if ($row['fsshared']==0)
	{
		echo "									<option value=\"0\" SELECTED>No</option>\n";
		echo "									<option value=\"1\">Yes</option>\n";
	}
	else
	{
		echo "									<option value=\"0\">No</option>\n";
		echo "									<option value=\"1\" SELECTED>Yes</option>\n";
	}
	
	echo "								</select>\n";
	echo "							</td>\n";
	echo "							<td align=\"center\">\n";
	echo "								<select name=\"fsoffice\">\n";
	
	if ($row['fsoffice']==0)
	{
		echo "									<option value=\"0\" SELECTED>No</option>\n";
		echo "									<option value=\"1\">Yes</option>\n";
	}
	else
	{
		echo "									<option value=\"0\">No</option>\n";
		echo "									<option value=\"1\" SELECTED>Yes</option>\n";
	}
	
	echo "								</select>\n";
	echo "							</td>\n";
	echo "            			</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\" valign=\"top\"><b>File Storage Limit:</b></td>\n";
	echo "               <td><input class=\"bboxbr\" type=\"text\" name=\"fslimit\" value=\"".$row['fslimit']."\" size=\"15\"> Mb</td>\n";
	echo "            </tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\"><b>Purchase Order</b></td>\n";
	echo "				<td align=\"left\">\n";
	echo "					<select name=\"PurchaseOrder\">\n";

	for ($po=9;$po>=0;$po--)
	{
		if ($row['PurchaseOrder']==$po)
		{
			echo "								<option value=\"".$po."\" SELECTED>".$po."</option>\n";	
		}
		else
		{
			echo "								<option value=\"".$po."\">".$po."</option>\n";	
		}
	}

	echo "					</select>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	
	$qryETID = "SELECT etid,name FROM EmailTemplate WHERE (oid=".$row['officeid']." or oid=0) AND active = 6 ORDER BY name ASC;";
	$resETID = mssql_query($qryETID);
	
	echo "			<tr>\n";
	echo "				<td align=\"right\"><b>Introductory Email</b></td>\n";
	echo "				<td align=\"left\">\n";
	echo "					<select name=\"intro_etid\">\n";
	
	if ($row['intro_etid']==0)
	{
		echo "								<option value=\"0\" SELECTED>None</option>\n";	
	}
	else
	{
		echo "								<option value=\"0\">None</option>\n";	
	}

	while ($rowETID=mssql_fetch_array($resETID))
	{
		if ($row['intro_etid']==$rowETID['etid'])
		{
			echo "								<option value=\"".$rowETID['etid']."\" SELECTED>".$rowETID['name']."</option>\n";	
		}
		else
		{
			echo "								<option value=\"".$rowETID['etid']."\">".$rowETID['name']."</option>\n";	
		}
	}

	echo "					</select>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "         </table>\n";
	echo "		</form>\n";
}

function ViewAccountingConfig($oid)
{
	$qry = "SELECT accountingsystem FROM offices WHERE officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	echo "<table>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Accounting System:</b></td>\n";
	echo "               <td>\n";
	echo "					<select name=\"accountingsystem\">\n";
	
	if (isset($row['accountingsystem']) and $row['accountingsystem']==1)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\" SELECTED>Standard (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
	}
	elseif (isset($row['accountingsystem']) and $row['accountingsystem']==2)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\">Standard (XML)</option>\n";
		echo "					<option value=\"2\" SELECTED>Quickbooks (qbXML)</option>\n";
	}
	else
	{
		echo "					<option value=\"0\" SELECTED>None</option>\n";
		echo "					<option value=\"1\">Standard (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
	}
	
	echo "					</select>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	
	if (isset($row['accountingsystem']) and $row['accountingsystem']==1)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\"><b>Job Export Server:</b></td>\n";
		echo "               <td><input class=\"bboxb\" type=\"text\" name=\"exportserver\" value=\"".$row['exportserver']."\" size=\"15\"> ".$srvstat[0]." ".$srvstat[1]."</td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"right\"><b>Job Export Login ID:</b></td>\n";
		echo "               <td><input class=\"bboxb\" type=\"text\" name=\"exportlogin\" value=\"".$row['exportlogin']."\" size=\"15\"></td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"right\"><b>Job Export Password:</b></td>\n";
		echo "               <td><input class=\"bboxb\" type=\"text\" name=\"exportpass\" value=\"".$row['exportpass']."\" size=\"15\"></td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"right\"><b>Job Export Catalog:</b></td>\n";
		echo "               <td><input class=\"bboxb\" type=\"text\" name=\"exportcatalog\" value=\"".$row['exportcatalog']."\" size=\"15\"></td>\n";
		echo "            </tr>\n";
	}
	elseif (isset($row['accountingsystem']) and $row['accountingsystem']==2)
	{
		$qryACCSYS = "SELECT * FROM qbwcConfig WHERE oid=".$oid.";";
		$resACCSYS = mssql_query($qryACCSYS);
		$rowACCSYS = mssql_fetch_array($resACCSYS);
		$nrowACCSYS= mssql_num_rows($resACCSYS);
		
		//if ($nrowACCSYS==1)
		//{
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>AppDescription:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"AppDescription\" value=\"".$rowACCSYS['AppDescription']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>AppDisplayName:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"AppDisplayName\" value=\"".$rowACCSYS['AppDisplayName']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>AppID:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"AppID\" value=\"".$rowACCSYS['AppID']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>AppName:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"AppName\" value=\"".$rowACCSYS['AppName']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>AppSupport:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"AppSupport\" value=\"".$rowACCSYS['AppSupport']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>AppUniqueName:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"AppUniqueName\" value=\"".$rowACCSYS['AppUniqueName']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>AppURL:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"AppURL\" value=\"".$rowACCSYS['AppURL']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>AuthFlags:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"AuthFlags\" value=\"".$rowACCSYS['AuthFlags']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>FileID:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"FileID\" value=\"".$rowACCSYS['FileID']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>IsReadOnly:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"IsReadOnly\" value=\"".$rowACCSYS['IsReadOnly']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>Notify:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"Notify\" value=\"".$rowACCSYS['Notify']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>OwnerID:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"OwnerID\" value=\"".$rowACCSYS['OwnerID']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>PersonalDataPref:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"PersonalDataPref\" value=\"".$rowACCSYS['PersonalDataPref']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>QBType:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"QBType\" value=\"".$rowACCSYS['QBType']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>Scheduler:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"Scheduler\" value=\"".$rowACCSYS['Scheduler']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>Style:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"Style\" value=\"".$rowACCSYS['Style']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>UnattendedModePref:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"UnattendedModePref\" value=\"".$rowACCSYS['UnattendedModePref']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"><b>UserName:</b></td>\n";
			echo "               <td><input class=\"bboxb\" type=\"text\" name=\"UserName\" value=\"".$rowACCSYS['UserName']."\" size=\"15\"></td>\n";
			echo "            </tr>\n";
		//}
		//else
		//{
			
		//}
	}
	
	/*
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Job Export Server:</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" name=\"exportserver\" value=\"".$row['exportserver']."\" size=\"15\"> ".$srvstat[0]." ".$srvstat[1]."</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Job Export Login ID:</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" name=\"exportlogin\" value=\"".$row['exportlogin']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Job Export Password:</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" name=\"exportpass\" value=\"".$row['exportpass']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Job Export Catalog:</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" name=\"exportcatalog\" value=\"".$row['exportcatalog']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	*/
	
	echo "</table>\n";	
}

function EditOffice($oid=0)
{
	if ($_SESSION['tlev'] < 8)
	{
		die("<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Resource</b>");
		//exit;
	}
	
	if ($oid==0)
	{
		die('Invalid Office ID request');
	}
	
	$qry = "SELECT O.officeid as oid,O.name,O.accountingsystem,O.enmas,O.enquickbooks FROM offices AS O WHERE O.officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	echo "<input type=\"hidden\" id=\"OffConfigOID\" value=\"".$oid."\">\n";
	echo "<script type=\"text/javascript\" src=\"js/jquery_office_maint.js\"></script>\n";
	echo "<table width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\"><b>Office Configuration: </b>".$row['oname']."</td>\n";
	echo "					<td class=\"gray\" align=\"right\"><b>Last Update:</b> <span id=\"LastOfficeUpdate\"></span></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "  <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	
	echo "	<div id=\"ViewOfficeInfo\">\n";
	echo "		<ul>\n";
	echo "			<li><a href=\"#tab0\"><em>General</em></a></li>\n";
	echo "			<li><a href=\"#tab1\"><em>System</em></a></li>\n";
	echo "			<li><a href=\"#tab2\"><em>Payment Schedule</em></a></li>\n";
	echo "			<li><a href=\"#tab3\"><em>Accounting</em></a></li>\n";
	echo "			<li><a href=\"#tab4\"><em>Pricebook</em></a></li>\n";
	echo "			<li><a href=\"#tab5\"><em>Fee Schedule</em></a></li>\n";
	echo "			<li><a href=\"#tab6\"><em>File Storage</em></a></li>\n";
	echo "			<li><a href=\"#tab7\"><em>Call Routing</em></a></li>\n";
	echo "			<li><a href=\"#tab8\"><em>Sales Tax</em></a></li>\n";
	echo "			<li><a href=\"#tab9\"><em>Finance Office</em></a></li>\n";
	echo "		</ul>\n";
	
	echo "		<div id=\"tab0\">\n";
	echo "			<p>\n";
	
	echo "				<div id=\"panel_GeneralOfficeInfo\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";
	
	echo "			</p>\n";
	echo "		</div>\n";
	
	echo "		<div id=\"tab1\">\n";
	echo "			<p>\n";
	
	echo "				<div id=\"panel_GeneralOfficeConfig\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";
	
	echo "			</p>\n";
	echo "		</div>\n";
	
	echo "		<div id=\"tab2\">\n";
	echo "			<p>\n";
	
	echo "				<div id=\"panel_PaymentScheduleConfig\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";
	
	echo "			</p>\n";
	echo "		</div>\n";
	
	echo "		<div id=\"tab3\">\n";
	echo "			<p>\n";
	
	echo "				<div id=\"panel_MASAccountingConfig\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";
	
	echo "			</p>\n";
	echo "		</div>\n";
	
	echo "		<div id=\"tab4\">\n";
	echo "			<p>\n";
	
	echo "				<div id=\"panel_PricebookConfig\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";
	
	echo "			</p>\n";
	echo "		</div>\n";
	
	echo "		<div id=\"tab5\">\n";
	echo "			<p>\n";
	
	echo "				<div id=\"panel_FeeScheduleConfig\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";
	
	echo "			</p>\n";
	echo "		</div>\n";
	
	echo "		<div id=\"tab6\">\n";
	echo "			<p>\n";

	echo "				<div id=\"panel_FileStorageConfig\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";
	
	echo "			</p>\n";
	echo "		</div>\n";
	
	echo "		<div id=\"tab7\">\n";
	echo "			<p>\n";
	
	echo "				<div id=\"panel_RoutingMatrixConfig\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";
	
	echo "			</p>\n";
	echo "		</div>\n";
	
	echo "		<div id=\"tab8\">\n";
	echo "			<p>\n";
	
	echo "				<div id=\"panel_SalesTaxConfig\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";

	echo "			</p>\n";
	echo "		</div>\n";
	
	echo "		<div id=\"tab9\">\n";
	echo "			<p>\n";

	echo "				<div id=\"panel_FinanceConfig\"><img class=\"system_busy\" src=\"images/mozilla_blu.gif\"> Retrieving...</div>\n";
	
	echo "			</p>\n";
	echo "		</div>\n";
	echo "	</div>\n";
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function ViewFinanOfficeInfo($oid)
{
	$qryZz = "SELECT officeid,name FROM offices WHERE finan_from=".$oid." ORDER BY name ASC;";
	$resZz = mssql_query($qryZz);
		
	echo "         <table class=\"outer\" align=\"right\" width=\"100%\">\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\"align=\"left\"><b>Financing For:</b></td>\n";
	echo "               <td class=\"gray\"align=\"left\"></td>\n";
	echo "            </tr>\n";

	while ($rowZz = mssql_fetch_array($resZz))
	{
		echo "            <tr>\n";
		echo "               <td class=\"gray\"align=\"left\"></td>\n";
		echo "               <td class=\"gray\"align=\"left\">".$rowZz['name']."</td>\n";
		echo "            </tr>\n";
	}
	
	echo "         </table>\n";
}

function UpdateOfficeOLD($officeid)
{
	$chgzip=0;
	$qry0 = "SELECT * FROM offices WHERE officeid='".$officeid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT COUNT(id) as mcnt FROM zip_to_zip WHERE ozip='".$row0['zip']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry  = "UPDATE offices SET ";
	$qry .= "name='".$_REQUEST['name']."',";
	$qry .= "label_masoff_code='".$_REQUEST['label_masoff_code']."',";
	$qry .= "addr1='".$_REQUEST['addr1']."',";
	$qry .= "addr2='".$_REQUEST['addr2']."',";
	$qry .= "city='".$_REQUEST['city']."',";
	$qry .= "state='".$_REQUEST['state']."',";
	$qry .= "phone='".$_REQUEST['phone']."',";
	$qry .= "gm='".$_REQUEST['gm']."',";
	$qry .= "sm='".$_REQUEST['sm']."',";
	$qry .= "am='".$_REQUEST['am']."',";
	$qry .= "leadforward='".$_REQUEST['leadforward']."',";
	$qry .= "altcode='".$_REQUEST['altcode']."',";
	$qry .= "fax='".$_REQUEST['fax']."',";
	$qry .= "type='".$_REQUEST['type']."',";
	$qry .= "comment='".$_REQUEST['comment']."',";
	$qry .= "stax='".$_REQUEST['stax']."',";
	$qry .= "deckinc='".$_REQUEST['deckinc']."',";
	$qry .= "bullet_rate='".$_REQUEST['bullet_rate']."',";
	$qry .= "bullet_cnt='".$_REQUEST['bullet_cnt']."',";
	$qry .= "over_split='".$_REQUEST['over_split']."',";
	$qry .= "com_rate='".$_REQUEST['com_rate']."', ";

	if (isset($_REQUEST['del_psched']) && $_REQUEST['del_psched']==1)
	{
		$qry .= "psched='0',";
		$qry .= "psched_perc='0',";
	}

	$qry .= "enest='".$_REQUEST['enest']."', ";
	$qry .= "encost='".$_REQUEST['encost']."', ";
	$qry .= "encon='".$_REQUEST['encon']."', ";
	$qry .= "enjob='".$_REQUEST['enjob']."', ";
	$qry .= "enmas='".$_REQUEST['enmas']."', ";
	$qry .= "enexp='".$_REQUEST['enexp']."', ";
	$qry .= "endigreport='".$_REQUEST['endigreport']."', ";
	$qry .= "entripinfo='".$_REQUEST['entripinfo']."', ";
	$qry .= "tgp='".$_REQUEST['tgp']."', ";
	$qry .= "vgp='".$_REQUEST['vgp']."', ";
	$qry .= "consfee='".$_REQUEST['consfee']."', ";
	$qry .= "acctfee='".$_REQUEST['acctfee']."', ";
	$qry .= "pacctfee='".$_REQUEST['pacctfee']."', ";
	$qry .= "manphsadj='".$_REQUEST['manphsadj']."', ";
	$qry .= "adminonly='".$_REQUEST['adminonly']."', ";
	$qry .= "masimport='".$_REQUEST['masimport']."', ";
	$qry .= "all_code='".$_REQUEST['all_code']."', ";
	$qry .= "logging='".$_REQUEST['logging']."', ";
	$qry .= "gmrjoin='".$_REQUEST['gmrjoin']."', ";
	$qry .= "leadmail='".$_REQUEST['leadmail']."', ";
	$qry .= "grouping='".$_REQUEST['grouping']."', ";
	$qry .= "otype='".$_REQUEST['otype']."', ";
	$qry .= "finan_off='".$_REQUEST['finan_off']."', ";
	$qry .= "finan_from='".$_REQUEST['finan_from']."', ";
	$qry .= "finan_rep='".$_REQUEST['finan_rep']."', ";
	$qry .= "csrep='".$_REQUEST['csrep']."', ";
	$qry .= "processor='".$_REQUEST['processor']."', ";
	$qry .= "fslimit='".$_REQUEST['fslimit']."', ";
	$qry .= "fscustomer='".$_REQUEST['fscustomer']."', ";
	$qry .= "fsshared='".$_REQUEST['fsshared']."', ";
	$qry .= "fsoffice='".$_REQUEST['fsoffice']."', ";
	$qry .= "PurchaseOrder='".$_REQUEST['PurchaseOrder']."', ";
	$qry .= "accountingsystem='".$_REQUEST['accountingsystem']."', ";
	$qry .= "otype_code='".$_REQUEST['otype_code']."', ";
	$qry .= "ldexport='".$_REQUEST['ldexport']."', ";
	$qry .= "intro_etid='".$_REQUEST['intro_etid']."', ";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate(), ";
	
	/*
	if ($_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==MTRX_ADMIN)
	{
		if (isset($_REQUEST['accountingsystem']) and $_REQUEST['accountingsystem']==1)
		{
			$qry .= "exportserver='".$_REQUEST['exportserver']."', ";
			$qry .= "exportlogin='".$_REQUEST['exportlogin']."', ";
			$qry .= "exportpass='".$_REQUEST['exportpass']."', ";
			$qry .= "exportcatalog='".$_REQUEST['exportcatalog']."', ";
		}
	}
	
	if (!empty($_REQUEST['nozip']) && strlen ($_REQUEST['nozip']) >= 5 && $_REQUEST['nozip'] != $row0['zip'])
	{
		$qry .= "zip='".$_REQUEST['nozip']."',";
		$chgzip=1;
	}
	
	if (!empty($_REQUEST['noringto']) && $_REQUEST['noringto'] != $row0['ringto'])
	{
		$qry .= "ringto='".$_REQUEST['noringto']."',";
	}
	
	*/
	
	$qry .= "conlicense='".$_REQUEST['conlicense']."',";
	$qry .= "timeshift='".$_REQUEST['timeshift']."',";
	$qry .= "active='".$_REQUEST['active']."'";
	$qry .= " WHERE officeid=".$officeid.";";
	$res  = mssql_query($qry);
	
	/*
	if ($row0['zip']!=$_REQUEST['nozip'] && $chgzip==1 && $row1['mcnt'] > 0)
	{
		$qry2 = "UPDATE zip_to_zip SET ozip='".$_REQUEST['nozip']."' WHERE ozip='".$row0['zip']."';";
		$res2 = mssql_query($qry2);
	}
	*/
	
	EditOffice($officeid);
}

function psched_update()
{
	$i=0;
	$psar=array();
	$ppar=array();
	$psched="";
	$psched_perc="";
	foreach ($_POST as $n=>$v)
	{
		if (substr($n,0,4)=="per_")
		{
			if ($v!=0)
			{
				$idata=substr($n,4);
				$psar[]=$idata;
				$ppar[]=$v;
				$i++;
			}
		}
	}

	if (count($psar)==count($ppar))
	{
		/*
		echo "<pre>";
		echo $i."<br>";
		print_r($psar);
		print_r($ppar);
		echo "</pre>";
		echo "MATCH<br>";
		*/
		foreach ($psar as $n1=>$v1)
		{
			if ($i==1)
			{
				$psched=$psched.$v1;
				$psched_perc=$psched_perc.$ppar[$n1];
			}
			else
			{
				$psched=$psched.$v1.",";
				$psched_perc=$psched_perc.$ppar[$n1].",";
			}
			$i--;
		}

		$qry  = "UPDATE offices SET ";
		$qry .= "psched='".$psched."',";
		$qry .= "psched_perc='".$psched_perc."' ";
		$qry .= " WHERE officeid=".$_REQUEST['officeid'].";";
		$res  = mssql_query($qry);

		//echo $qry;
		viewoff($_REQUEST['officeid']);
	}
}

function basematrix()
{
	OfficeMenuBar();
	
	if ($_SESSION['subq']=="list")
	{
		ListOffices();
	}
	elseif ($_SESSION['subq']=="view")
	{
		if ($_SESSION['officeid']==89 or ($_SESSION['securityid']==26 or $_SESSION['securityid']==332))
		{
			EditOffice($_REQUEST['officeid']);
		}
		else
		{
			echo "<b>Office Edit Disabled</b>";
		}
	}
	/*
	elseif ($_SESSION['subq']=="add")
	{
		if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332)
		{
			NewOfficeNew();
		}
		else
		{
			NewOffice();
		}
	}
	elseif ($_SESSION['subq']=="add2")
	{
		if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332)
		{
			//InsertOfficeNew();
		}
		else
		{
			InsertOffice();
		}
	}
	elseif ($_SESSION['subq']=="addOffice")
	{
		if ($_SESSION['securityid']==26)
		{
			InsertOfficeNew();
		}
		else
		{
			echo 'AddOffice Disabled';
		}
	}
	*/
	elseif ($_SESSION['subq']=="edit")
	{
		editoff($_REQUEST['userid']);
	}
	elseif ($_SESSION['subq']=="update")
	{
		UpdateOffice($_REQUEST['officeid']);
	}
	elseif ($_SESSION['subq']=="psched_update")
	{
		psched_update();
	}
	elseif ($_SESSION['subq']=="set")
	{
		update_set_office();
	}
	elseif ($_SESSION['subq']=="delete")
	{
		deleteoff($_REQUEST['userid']);
	}
	elseif ($_SESSION['subq']=="add_taxrate")
	{
		add_taxrate();
	}
	elseif ($_SESSION['subq']=="update_taxrate")
	{
		update_taxrate();
	}
	elseif ($_SESSION['subq']=="upd_zip_ringto")
	{
		upd_zip_ringto();
	}
}

?>
