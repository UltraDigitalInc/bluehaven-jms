<?php

function selectemailtemplateDB($oid,$sid,$cid,$ttid)
{
	$qryET = "SELECT * FROM EmailTemplate WHERE active <= ".$_SESSION['emailtemplates']." AND active >= 1 and ttype=".$ttid." ORDER BY name ASC;";	
	$resET = mssql_query($qryET);
	$nrowET= mssql_num_rows($resET);

	if ($nrowET > 0)
	{
		echo "<table>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<select id=\"etid\" name=\"etid\" autocomplete=\"off\" title=\"Selecting an Email Template will send an Email to the Customer upon update.\">\n";
		echo "				<option value=\"0\">None</option>\n";
		
		while ($rowET = mssql_fetch_array($resET))
		{
			if ($rowET['active']==0)
			{
				echo "				<option class=\"fontred\"value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
			else
			{
				echo "				<option value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
		}
		
		echo "			</select>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function getAppoint($cid)
{
	$out=array('mo'=>0,'da'=>0,'yr'=>0,'hr'=>0,'mn'=>0,'pa'=>0);    
	
	$qry0 = "
			SELECT
				C.cid,C.appt_mo,C.appt_da,C.appt_yr,C.appt_hr,C.appt_mn,C.appt_pa
			FROM
				cinfo as C
			WHERE
				C.cid=".(int) $cid.";";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 >0)
	{
		$row0 = mssql_fetch_array($res0);
		$out=array('mo'=>$row0['appt_mo'],'da'=>$row0['appt_da'],'yr'=>$row0['appt_yr'],'hr'=>$row0['appt_hr'],'mn'=>$row0['appt_mn'],'pa'=>$row0['appt_pa']);
	}
	
	return $out;
}

function setAppoint(){
	$out='';
	$cid	=(isset($_REQUEST['appt_cid']) and $_REQUEST['appt_cid']!=0)?$_REQUEST['appt_cid']:0;
	$appt_mo=(isset($_REQUEST['appt_mo']) and $_REQUEST['appt_mo']!=0)?$_REQUEST['appt_mo']:0;
	$appt_da=(isset($_REQUEST['appt_da']) and $_REQUEST['appt_da']!=0)?$_REQUEST['appt_da']:0;
	$appt_yr=(isset($_REQUEST['appt_yr']) and $_REQUEST['appt_yr']!=0)?$_REQUEST['appt_yr']:0;
	$appt_hr=(isset($_REQUEST['appt_hr']) and $_REQUEST['appt_hr']!=0)?$_REQUEST['appt_hr']:0;
	$appt_mn=(isset($_REQUEST['appt_mn']) and $_REQUEST['appt_mn']!=0)?$_REQUEST['appt_mn']:0;
	$appt_pa=(isset($_REQUEST['appt_pa']) and $_REQUEST['appt_pa']!=0)?$_REQUEST['appt_pa']:0;
    $astate =(isset($_REQUEST['astate']) and !empty($_REQUEST['astate']))?$_REQUEST['astate']:null;
	
	$appt_ar=array(
		'appt_mo'=>$appt_mo,
		'appt_da'=>$appt_da,
		'appt_yr'=>$appt_yr,
		'appt_hr'=>$appt_hr,
		'appt_mn'=>$appt_mn,
		'appt_pa'=>$appt_pa
	);
	
    if ($cid!=0) {
        $qry0 = "SELECT cid,officeid,securityid FROM cinfo WHERE cid=".(int) $cid.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        if (isValidSecurity($row0['securityid'],$row0['officeid'],1,'llev')) { 
            foreach ($appt_ar as $n => $v) {
                if ($v!=0) {
                    $qry = "UPDATE cinfo SET ".$n."=".$v." WHERE cid=".(int) $cid.";";
                    $res = mssql_query($qry);
                }
            }
            
            $app = date_format_US($appt_ar['appt_mo'],$appt_ar['appt_da'],$appt_ar['appt_yr'],$appt_ar['appt_hr'],$appt_ar['appt_mn'],$appt_ar['appt_pa']);
            $qry = "UPDATE cinfo SET apptmnt=cast('".$app."' as datetime),updated=getdate() WHERE cid=".(int) $cid.";";
            $res = mssql_query($qry);
            
            $out=jsonAppointAr($cid,$astate);
        }
        else {
            $out['date']='Security Error ('.__LINE__.')';
        }
    }
	
	return $out;
}

function showCallback($cid)
{
	$out='';
	$hold=getCallback($cid);
	
	if (isset($hold['mo']) and $hold['mo']!=0) {
		$out=str_pad($hold['mo'],2,'0',LEFT).'/'.str_pad($hold['da'],2,'0',LEFT).'/'.$hold['yr'];
	}
	
	return $out;
}

function setCallback() {
	$out='';
	$cid	=(isset($_REQUEST['hold_cid']) and !empty($_REQUEST['hold_cid']))?$_REQUEST['hold_cid']:null;
	$hold_mo=(isset($_REQUEST['hold_mo']) and !empty($_REQUEST['hold_mo']))?$_REQUEST['hold_mo']:null;
	$hold_da=(isset($_REQUEST['hold_da']) and !empty($_REQUEST['hold_da']))?$_REQUEST['hold_da']:null;
	$hold_yr=(isset($_REQUEST['hold_yr']) and !empty($_REQUEST['hold_yr']))?$_REQUEST['hold_yr']:null;
    $astate =(isset($_REQUEST['astate']) and !empty($_REQUEST['astate']))?$_REQUEST['astate']:null;
	
	if (!is_null($cid) and !is_null($hold_mo) and !is_null($hold_da) and !is_null($hold_yr)) {
        $qry0 = "SELECT cid,officeid,securityid FROM cinfo WHERE cid=".(int) $cid.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        if (isValidSecurity($row0['securityid'],$row0['officeid'],1,'llev')) {        
            $cal = $hold_mo.'/'.$hold_da.'/'.$hold_yr;
            $qry = "UPDATE cinfo SET hold_mo=".$hold_mo.",hold_da=".$hold_da.",hold_yr=".$hold_yr.",callback='".$cal."',updated=getdate() WHERE cid=".(int) $cid.";";
            $res = mssql_query($qry);
        
            $out=jsonCallbackAr($cid,$astate);
        }
        else {
            $out['date']='Security Error ('.__LINE__.')';
        }
	}
	else {
		$out='Parameter Error ('.__LINE__.')';
	}
	
	return $out;
}

function removeCallback() {
	$out='';
	$cid	=(isset($_REQUEST['hold_cid']) and !empty($_REQUEST['hold_cid']))?$_REQUEST['hold_cid']:null;
    $astate =(isset($_REQUEST['astate']) and !empty($_REQUEST['astate']))?$_REQUEST['astate']:null;
	
	if (!is_null($cid)) {
        $qry0 = "SELECT cid,officeid,securityid FROM cinfo WHERE cid=".(int) $cid.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        if (isValidSecurity($row0['securityid'],$row0['officeid'],1,'llev')) {
            $qry = "UPDATE cinfo SET hold_mo=0,hold_da=0,hold_yr=0,callback=NULL,updated=getdate() WHERE cid=".(int) $cid.";";
            $res = mssql_query($qry);
            $out['date']='No Callback';
            $out['lclass']='outerrnd';
        }
        else {
            $out['date']='Security Error ('.__LINE__.')';
        }
	}
	else {
		$out['date']='Parameter Error ('.__LINE__.')';
	}
	
	return $out;
}

function removeApptmnt() {
	$out='';
	$cid	=(isset($_REQUEST['appt_cid']) and !empty($_REQUEST['appt_cid']))?$_REQUEST['appt_cid']:null;
    $astate =(isset($_REQUEST['astate']) and !empty($_REQUEST['astate']))?$_REQUEST['astate']:null;
	
	if (!is_null($cid)) {
        $qry0 = "SELECT cid,officeid,securityid FROM cinfo WHERE cid=".(int) $cid.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        if (isValidSecurity($row0['securityid'],$row0['officeid'],1,'llev')) {
            $qry = "UPDATE cinfo SET appt_mo=0,appt_da=0,appt_yr=0,appt_hr=0,appt_mn=0,appt_pa=0,apptmnt=NULL,updated=getdate() WHERE cid=".(int) $cid.";";
            $res = mssql_query($qry);
            $out['date']='No Appointment';
            $out['time']='';
            $out['lclass']='outerrnd';
        }
        else {
            $out['date']='Security Error ('.__LINE__.')';
        }
	}
	else {
		$out['date']='Parameter Error ('.__LINE__.')';
	}
	
	return $out;
}

function getCallback($cid) {
	$out=array('mo'=>0,'da'=>0,'yr'=>0);	
	$qry0 = "SELECT C.cid,C.hold_mo,C.hold_da,C.hold_yr FROM cinfo as C WHERE C.cid=".(int) $cid.";";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 >0)
	{
		$row0 = mssql_fetch_array($res0);
		$out=array('mo'=>$row0['hold_mo'],'da'=>$row0['hold_da'],'yr'=>$row0['hold_yr'],'db'=>true);
	}
	
	return $out;
}

function jsonAppointAr($cid,$astate)
{
	$out=array();
    $appt=getAppoint($cid);
    $lcolor=ApptColor_lview($appt,$astate);
	
	if (isset($appt['mo']) and $appt['mo']!=0) {
		$ampm=($appt['pa']==1)?'AM':'PM';
		$out['date']=str_pad($appt['mo'],2,'0',STR_PAD_LEFT).'/'.str_pad($appt['da'],2,'0',STR_PAD_LEFT).'/'.substr($appt['yr'],2,2);
        $out['time']=$appt['hr'].':'.str_pad($appt['mn'],2,'0',STR_PAD_LEFT).$ampm;
        $out['lclass']=$lcolor;
	}
	
	return $out;
}

function jsonCallbackAr($cid,$astate) {
	$out=array();
	$hold=getCallback($cid);
    $lcolor=CallbColor_lview($hold,$astate);
	
	if (isset($hold['mo']) and $hold['mo']!=0) {
		$out['date']=str_pad($hold['mo'],2,'0',STR_PAD_LEFT).'/'.str_pad($hold['da'],2,'0',STR_PAD_LEFT).'/'.$hold['yr'];
        $out['lclass']=$lcolor;
	}
	
	return $out;
}

function ApptColor_lview($data,$astate) {    
    //$out='outerrnd';
	$gd=time();
    $day=86400;
	$week=$day*7;
	$week2=$day*14;
	$fdate=$data['mo'].'/'.$data['da'].'/'.$data['yr'];
	$idate=strtotime($fdate);

	if ($data['mo']==date("n") and $data['da']==date("j") and $data['yr']==date("Y")) {
        
        if (!is_null($astate) and $astate=='search') {
            $out='lightgreen';
        }
        else {
            $out='outerrnd_ltgrn';
        }
    }
    else {
        if (!is_null($astate) and $astate=='search') {
            $out='';
        }
        else {
            $out='outerrnd';
            //$out='outerrnd_ltgrn';
        }
    }

    return $out;
}

function CallbColor_lview($data,$astate) {
    //$out='outerrnd';
	$gd=time();
    $day=86400;
	$week=$day*7;
	$week2=$day*14;
	$fdate=$data['mo'].'/'.$data['da'].'/'.$data['yr'];
	$idate=strtotime($fdate);

	if (($data['mo']==date("n") and $data['da']==date("j") and $data['yr']==date("Y")) or ($idate <= ($week2+$gd) and $idate >= $gd)) {
        if (!is_null($astate) and $astate=='search') {
            $out='magenta';
        }
        else {
            $out='outerrnd_mgnta';
        }
    }
    else {
        if (!is_null($astate) and $astate=='search') {
            $out='';
        }
        else {
            $out='outerrnd';
        }
    }

    return $out;
}

function setLeadColor($type,$dates) {    
    $tbg='wh_und';
    $day=86400;
    
	if ($type='appt' and ($dates['mo']==date("n") and $dates['da']==date("j") and $dates['yr']==date("Y"))) {
        $tbg='lightgreen_und';
    }
	
    if ($type='callb' and ($dates['mo']==date("n") and $dates['da']==date("j") and $dates['yr']==date("Y"))) {
        $tbg='magenta_und';
    }

    return $tbg;
}

function getChangeOfficeForm() {
    $out ='';
    $cid =(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    $sid =(isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)?$_SESSION['securityid']:0;
    $llv =(isset($_SESSION['llev']) and $_SESSION['llev']!=0)?$_SESSION['llev']:0;
    
    if ($llv >= 6) {
        if ($cid!=0 and $sid!=0) {
            $qry = "SELECT estid FROM est WHERE ccid=".(int) $cid.";";
            $res = mssql_query($qry);
            $nrow= mssql_num_rows($res);
            
            if ($nrow == 0) {            
                $oadmn= array('oid'=>89,'oname'=>'BHNM: Active','active'=>1);
                $qry0 = "SELECT securityid,officeid,slevel FROM security WHERE securityid=".(int) $sid." and substring(slevel,13,1)!=0;";
                $res0 = mssql_query($qry0);
                $nrow0= mssql_num_rows($res0);
                
                if ($nrow0!=0) {
                    $row0 = mssql_fetch_array($res0);
                    
                    $oid_ar=array();
                    $oid_ar[]=array('oid'=>0,'oname'=>'Select...','active'=>1);
                    
                    if ($row0['officeid']==89) {
                        $qry1 = "SELECT officeid as oid,name as oname,active FROM offices ORDER BY active DESC,name ASC;";
                        $res1 = mssql_query($qry1);
                        
                        while ($row1 = mssql_fetch_array($res1)) {
                            if ($row1['oid']!=$_SESSION['officeid']) {
                                $oid_ar[]=array('oid'=>$row1['oid'],'oname'=>$row1['oname'],'active'=>(int) $row1['active']);
                            }
                        }
                    }
                    else {
                        $oid_ar[]=array('oid'=>$oadmn['oid'],'oname'=>$oadmn['oname'],'active'=>$oadmn['active']);
                        
                        $qry1 = "SELECT S.oid,(select name from offices where officeid=S.oid) as oname,substring(S.slevel,13,1) as slev FROM alt_security_levels AS S WHERE S.sid=".(int) $sid." and substring(S.slevel,13,1)!=0 ORDER BY oname ASC;";
                        $res1 = mssql_query($qry1);
                        
                        while ($row1 = mssql_fetch_array($res1)) {
                            if ($row1['oid']!=$_SESSION['officeid']) {
                                $oid_ar[]=array('oid'=>$row1['oid'],'oname'=>$row1['oname'],'active'=>(int) $row1['slev']);
                            }
                        }
                    }
                    
                    $out.='<b>Change Office</b>';
                    $out.='<form id="changeOfficeForm" method="post">';
                    $out.='	<select id="tnoid">';
                    
                    foreach ($oid_ar as $n=>$v) {
                        $fstyle=($v['active']==0)?'red':'black';
                        $out.='	<option value="'.$v['oid'].'" style="color:'.$fstyle.';">'.$v['oname'].'</option>';
                    }
                    
                    $out.='	</select>';
                    $out.='</form>';
                }
                else {
                    $out='SID Error '.__LINE__;
                }
            }
            else {
                $out='Error: This Customer/Lead has Estimate(s) ('.__LINE__.')';
            }
        }
        else {
            $out='Invalid Input Parameters '.__LINE__;
        }
    }
    else {
        $out='Unauthorized  '.__LINE__;
    }
    
    return $out;
}

function saveChangeOfficeForm() {
    $out =array('error'=>true,'result'=>'');
    $cid =(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    $noid=(isset($_REQUEST['noid']) and $_REQUEST['noid']!=0)?$_REQUEST['noid']:0;
    $sid =(isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)?$_SESSION['securityid']:0;
    $llv =(isset($_SESSION['llev']) and $_SESSION['llev']!=0)?$_SESSION['llev']:0;
    
    if ($llv >= 5) {
        if ($cid!=0 and $noid!=0 and $sid!=0) {            
            if (checkSecurityAccess($sid,$noid,$llv,'llev')) {
                $qry0 = "SELECT officeid as oid,am,gm FROM offices WHERE officeid=".(int) $noid.";";
                $res0 = mssql_query($qry0);
                $row0 = mssql_fetch_array($res0);
                $lsid = (isset($row0['am']) and $row0['am']!=0)?$row0['am']:$row0['gm'];
                
                if (!LeadAssets($cid)) {
                    $qry1 = "UPDATE cinfo SET officeid=".(int) $noid.",securityid=".(int) $lsid." WHERE cid=".(int) $cid.";";
                    $res1 = mssql_query($qry1);
                    
                    //$qry2 = "INSERT INTO chistory ()";
                    //$res2 = mssql_query($qry2);
    
                    $out['error']=false;
                    $out['result'].="
                    <span>Lead Transferred. Click below to return to Leads Search</span><br/>
                    <form id=\"returntosearch\" method=\"post\" target=\"_top\">
                        <input type=\"hidden\" name=\"action\" value=\"leads\">
                        <input type=\"hidden\" name=\"call\" value=\"search\">
                        <button title=\"Return to the Lead Search\">Lead Search</button>
                    </form>\n";
                }
                else {
                    $out['result']='This Lead has an Estimate, Contract, or Job attached. It cannot be transferred until these assets have been removed. ('.__LINE__.')';
                }
            }
            else {
                $out['result']='Do not have access to that Office ('.__LINE__.')';
            }
        }
        else {
            $out['result']='Invalid Input Parameters '.__LINE__.')';
        }
    }
    else {
        $out['result']='Unauthorized Session '.__LINE__.')';
    }
    
    return $out;
}

function updateLeadSrcRes(){
	$out    =array('error'=>false);
    $ucnt   =0;
    $sr_ar  =array();
	$cid	=(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    $source =(isset($_REQUEST['source']) and $_REQUEST['source']!=0)?$_REQUEST['source']:0;
    $stage  =(isset($_REQUEST['stage']) and $_REQUEST['stage']!=0)?$_REQUEST['stage']:0;
	
	$qry1 = "SELECT source,stage FROM cinfo WHERE cid=".(int) $cid.";";
    $res1 = mssql_query($qry1);
    $row1 = mssql_fetch_array($res1);
    
    if ($source!=0 and $source!=$row1['source']) {
        $sr_ar['source']=$source;
    }
    
    if ($stage!=0 and $stage!=$row1['stage']) {
        $sr_ar['stage']=$stage;
    }
    
    if ($cid!=0 and count($sr_ar) > 0) {
        foreach ($sr_ar as $n => $v) {
            if ($v!=0) {
                $qry = "UPDATE cinfo SET ".$n."=".$v." WHERE cid=".(int) $cid.";";
                $res = mssql_query($qry);
                $ucnt++;
            }
        }
        
        if ($ucnt >= 1) {
            $qry = "UPDATE cinfo SET updated=getdate() WHERE cid=".(int) $cid.";";
            $res = mssql_query($qry);
        }
    }
    
    $qry2 = "SELECT source,stage FROM cinfo WHERE cid=".(int) $cid.";";
    $res2 = mssql_query($qry2);
    $row2 = mssql_fetch_array($res2);

	$out['source']=$row2['source'];
    $out['stage']=$row2['stage'];
	
	return $out;
}

function getHistory() {
    
    if (count($cdata['history']) > 0){
        echo '<table>';
        
        foreach ($cdata['history'] as $n=>$v){
            echo "<tr><td>".$v['date']."</td><td>".$v['fname']." ".$v['lname']."</td></tr>\n";
        }
        
        echo '</table>';
    }
}

function updateLeadOwner() {
    $out    =array('error'=>false,'result'=>0);
    $debug  =(isset($_REQUEST['debug']) and $_REQUEST['debug']==1)?true:false;
    $cid    =(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    
    try {
        $conn = new PDO("mssql:host=".HOST.";dbname=".DB,USER,PWD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        $out['error']=true;
        if ($debug) {
            $out['result']=$e->getMessage();
        }
        else {
            $out['result']='DB Connect Error'.__LINE__;
        }
    }
    
    if ($cid!=0) {
        $qry0 = "SELECT cid,officeid,securityid FROM cinfo WHERE cid=".(int) $cid.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        if (isValidSecurity($row0['securityid'],$row0['officeid'],1,'llev')) {
            $srep   =(isset($_REQUEST['srep']) and $_REQUEST['srep']!=0)?$_REQUEST['srep']:0;
        
            try {
                $stmt=$conn->prepare("update cinfo set securityid=? where cid=?;");
                $stmt->bindParam(1,$srep);
                $stmt->bindParam(2,$cid);
                if ($stmt->execute())
                {
                    $out['result']='Saved';
                }
                else {
                    $out['error']=true;
                    $out['result']='Not Saved '.__LINE__;
                }
            }
            catch(PDOException $e) {
                //echo 'Error '.__LINE__;
                $out['error']=true;
                if ($debug) {
                    $out['result']=$e->getMessage();
                }
                else {
                    $out['result']='DB Save Error'.__LINE__;
                }
            }
        }
        else {
            $out['error']=true;
            $out['result']='Security Error ('.__LINE__.')';
        }
        
        //echo 'Error '.__LINE__;
    }
    else {
        $out['error']=true;
        $out['result']='CID Error'.__LINE__;
    }
    
    return $out;
}

function updateLeadStatus() {
    $out    =array('error'=>false,'result'=>0);
    $debug  =(isset($_REQUEST['debug']) and $_REQUEST['debug']==1)?true:false;
    $cid    =(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    
    try {
        $conn = new PDO("mssql:host=".HOST.";dbname=".DB,USER,PWD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        $out['error']=true;
        if ($debug) {
            $out['result']=$e->getMessage();
        }
        else {
            $out['result']='DB Connect Error'.__LINE__;
        }
    }
    
    if ($cid!=0)
    {
        $cstat   =(isset($_REQUEST['cstat']) and $_REQUEST['cstat']!=0)?$_REQUEST['cstat']:0;
    
        try {
            $stmt=$conn->prepare("update cinfo set dupe=? where cid=?;");
            $stmt->bindParam(1,$cstat);
            $stmt->bindParam(2,$cid);
            if ($stmt->execute())
            {
                $out['result']='Saved';
            }
            else {
                $out['error']=true;
                $out['result']='Not Saved '.__LINE__;
            }
        }
        catch(PDOException $e) {
            echo 'Error '.__LINE__;
            $out['error']=true;
            if ($debug) {
                $out['result']=$e->getMessage();
            }
            else {
                $out['result']='DB Save Error'.__LINE__;
            }
        }
        
        //echo 'Error '.__LINE__;
    }
    else {
        $out['error']=true;
        $out['result']='CID Error'.__LINE__;
    }
    
    return $out;
}

function updateNames() {
    $out    =array('error'=>false,'result'=>'Not Saved');
    $debug  =(isset($_REQUEST['debug']) and $_REQUEST['debug']==1)?true:false;
    $cid    =(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    
    try {
        $conn = new PDO("mssql:host=".HOST.";dbname=".DB,USER,PWD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        $out['error']=true;
        if ($debug) {
            $out['result']=$e->getMessage();
        }
        else {
            $out['result']='DB Connect Error'.__LINE__;
        }
    }
    
    if ($cid!=0) {
        $qry0 = "SELECT cid,officeid,securityid FROM cinfo WHERE cid=".(int) $cid.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        if (isValidSecurity($row0['securityid'],$row0['officeid'],1,'llev')) {            
            $cfname=(isset($_REQUEST['cfname']) and strlen($_REQUEST['cfname']) >= 1)?trim($_REQUEST['cfname']):'';
            $clname=(isset($_REQUEST['clname']) and strlen($_REQUEST['clname']) >= 1)?trim($_REQUEST['clname']):'';
        
            try {
                $stmt=$conn->prepare("update cinfo set cfname=?,clname=? where cid=?;");
                $stmt->bindParam(1,$cfname);
                $stmt->bindParam(2,$clname);
                $stmt->bindParam(3,$cid);
                if ($stmt->execute())
                {
                    $out['result']='Saved';
                }
                else {
                    $out['error']=true;
                    $out['result']='Not Saved '.__LINE__;
                }
            }
            catch(PDOException $e) {
                echo 'Error '.__LINE__;
                $out['error']=true;
                if ($debug) {
                    $out['result']=$e->getMessage();
                }
                else {
                    $out['result']='DB Save Error'.__LINE__;
                }
            }
        }
        else {
            $out['error']=true;
            $out['result']='Security Error ('.__LINE__.')';
        }
    }
    else {
        $out['error']=true;
        $out['result']='CID Error'.__LINE__;
    }
    
    return $out;
}

function setPrivacy()
{
    $out    =array('error'=>false,'result'=>'Not Saved');
    $debug  =(isset($_REQUEST['debug']) and $_REQUEST['debug']==1)?true:false;
    $cid    =(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    
    try {
        $conn = new PDO("mssql:host=".HOST.";dbname=".DB,USER,PWD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        $out['error']=true;
        if ($debug) {
            $out['result']=$e->getMessage();
        }
        else {
            $out['result']='DB Connect Error '.__LINE__;
        }
    }
    
    if ($cid!=0)
    {
        $opt=(isset($_REQUEST['optin']) and $_REQUEST['optin'] == 1)?trim($_REQUEST['optin']):0;
    
        try {
            $stmt=$conn->prepare("update cinfo set opt1=? where cid=?;");
            $stmt->bindParam(1,$opt);
            $stmt->bindParam(2,$cid);
            if ($stmt->execute())
            {
                $out['result']='Saved';
            }
            else {
                $out['error']=true;
                $out['result']='Not Saved '.__LINE__;
            }
        }
        catch(PDOException $e) {
            $out['error']=true;
            if ($debug) {
                $out['result']=$e->getMessage();
            }
            else {
                $out['result']='DB Save Error'.__LINE__;
            }
        }
    }
    else {
        $out['error']=true;
        $out['result']='CID Error '.__LINE__;
    }
    
    return $out;
}

function updateAddresses() {
    $out    =array('error'=>false,'result'=>'Not Saved');
    $debug  =(isset($_REQUEST['debug']) and $_REQUEST['debug']==1)?true:false;
    $cid    =(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    
    try {
        $conn = new PDO("mssql:host=".HOST.";dbname=".DB,USER,PWD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        $out['error']=true;
        if ($debug) {
            $out['result']=$e->getMessage();
        }
        else {
            $out['result']='DB Connect Error'.__LINE__;
        }
    }
    
    if ($cid!=0) {
        $qry0 = "SELECT cid,officeid,securityid FROM cinfo WHERE cid=".(int) $cid.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        if (isValidSecurity($row0['securityid'],$row0['officeid'],1,'llev')) {
            $ssame  =(isset($_REQUEST['ssame']) and strlen($_REQUEST['ssame']) >= 1)?trim($_REQUEST['ssame']):'';
            $caddr1 =(isset($_REQUEST['caddr1']) and strlen($_REQUEST['caddr1']) >= 1)?trim($_REQUEST['caddr1']):'';
            $ccity  =(isset($_REQUEST['ccity']) and strlen($_REQUEST['ccity']) >= 1)?trim($_REQUEST['ccity']):'';
            $cstate =(isset($_REQUEST['cstate']) and strlen($_REQUEST['cstate']) >= 1)?trim($_REQUEST['cstate']):'';
            $czip1  =(isset($_REQUEST['czip1']) and strlen($_REQUEST['czip1']) >= 1)?trim($_REQUEST['czip1']):'';
            $czip2  =(isset($_REQUEST['czip2']) and strlen($_REQUEST['czip2']) >= 1)?trim($_REQUEST['czip2']):'';
            $ccounty=(isset($_REQUEST['ccounty']) and strlen($_REQUEST['ccounty']) >= 1)?trim($_REQUEST['ccounty']):'';
            $saddr1 =(isset($_REQUEST['saddr1']) and strlen($_REQUEST['saddr1']) >= 1)?trim($_REQUEST['saddr1']):'';
            $scity  =(isset($_REQUEST['scity']) and strlen($_REQUEST['scity']) >= 1)?trim($_REQUEST['scity']):'';
            $sstate =(isset($_REQUEST['sstate']) and strlen($_REQUEST['sstate']) >= 1)?trim($_REQUEST['sstate']):'';
            $szip1  =(isset($_REQUEST['szip1']) and strlen($_REQUEST['szip1']) >= 1)?trim($_REQUEST['szip1']):'';
            $szip2  =(isset($_REQUEST['szip2']) and strlen($_REQUEST['szip2']) >= 1)?trim($_REQUEST['szip2']):'';
            $scounty=(isset($_REQUEST['scounty']) and strlen($_REQUEST['scounty']) >= 1)?trim($_REQUEST['scounty']):'';
        
            try {
                $stmt=$conn->prepare("update cinfo set
                                     caddr1=?,ccity=?,cstate=?,czip1=?,czip2=?,ccounty=?,
                                     saddr1=?,scity=?,sstate=?,szip1=?,szip2=?,scounty=?,
                                     ssame=?
                                     where cid=?;");
                $stmt->bindParam(1,$caddr1);
                $stmt->bindParam(2,$ccity);
                $stmt->bindParam(3,$cstate);
                $stmt->bindParam(4,$czip1);
                $stmt->bindParam(5,$czip2);
                $stmt->bindParam(6,$ccounty);
                $stmt->bindParam(7,$saddr1);
                $stmt->bindParam(8,$scity);
                $stmt->bindParam(9,$sstate);
                $stmt->bindParam(10,$szip1);
                $stmt->bindParam(11,$szip2);
                $stmt->bindParam(12,$scounty);
                $stmt->bindParam(13,$ssame);
                $stmt->bindParam(14,$cid);
                if ($stmt->execute())
                {
                    $out['result']='Saved';
                }
                else {
                    $out['error']=true;
                    $out['result']='Not Saved '.__LINE__;
                }
            }
            catch(PDOException $e) {
                $out['error']=true;
                if ($debug) {
                    $out['result']=$e->getMessage();
                }
                else {
                    $out['result']='DB Save Error'.__LINE__;
                }
            }
        }
        else {
            $out['error']=true;
            $out['result']='Security Error ('.__LINE__.')';
        }
        
        //echo 'Error '.__LINE__;
    }
    else {
        $out['error']=true;
        $out['result']='CID Error'.__LINE__;
    }
    
    return $out;
}

function updateContacts()
{
    $out    =array('error'=>false,'result'=>'Not Saved');
    $debug  =(isset($_REQUEST['debug']) and $_REQUEST['debug']==1)?true:false;
    $cid    =(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    
    try {
        $conn = new PDO("mssql:host=".HOST.";dbname=".DB,USER,PWD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        $out['error']=true;
        if ($debug) {
            $out['result']=$e->getMessage();
        }
        else {
            $out['result']='DB Connect Error'.__LINE__;
        }
    }
    
    if ($cid!=0)
    {
        $chome =(isset($_REQUEST['chome']) and strlen($_REQUEST['chome']) >= 1)?trim($_REQUEST['chome']):'';
        $cwork =(isset($_REQUEST['cwork']) and strlen($_REQUEST['cwork']) >= 1)?trim($_REQUEST['cwork']):'';
        $ccell =(isset($_REQUEST['ccell']) and strlen($_REQUEST['ccell']) >= 1)?trim($_REQUEST['ccell']):'';
        $cfax  =(isset($_REQUEST['cfax']) and strlen($_REQUEST['cfax']) >= 1)?trim($_REQUEST['cfax']):'';
        $ctime =(isset($_REQUEST['ccontime']) and strlen($_REQUEST['ccontime']) >= 1)?trim($_REQUEST['ccontime']):'';
        $cemail=(isset($_REQUEST['cemail']) and strlen($_REQUEST['cemail']) >= 1)?trim($_REQUEST['cemail']):'';
        $cconph=(isset($_REQUEST['cconph']) and strlen($_REQUEST['cconph']) >= 1)?trim($_REQUEST['cconph']):'';
    
        try {
            $stmt=$conn->prepare("update cinfo set chome=?,cwork=?,ccell=?,cfax=?,cemail=?,ccontime=?,cconph=? where cid=?;");
            $stmt->bindParam(1,$chome);
            $stmt->bindParam(2,$cwork);
            $stmt->bindParam(3,$ccell);
            $stmt->bindParam(4,$cfax);
            $stmt->bindParam(5,$cemail);
            $stmt->bindParam(6,$ctime);
            $stmt->bindParam(7,$cconph);
            $stmt->bindParam(8,$cid);
            if ($stmt->execute())
            {
                $out['result']='Saved';
            }
            else {
                $out['error']=true;
                $out['result']='Not Saved '.__LINE__;
            }
        }
        catch(PDOException $e) {
            $out['error']=true;
            if ($debug) {
                $out['result']=$e->getMessage();
            }
            else {
                $out['result']='DB Save Error'.__LINE__;
            }
        }
        
        //echo 'Error '.__LINE__;
    }
    else {
        $out['error']=true;
        $out['result']='CID Error'.__LINE__;
    }
    
    return $out;
}

function updateAppointmentsXXX()
{
    $out    =array('error'=>false,'result'=>'Not Saved');
    $debug  =(isset($_REQUEST['debug']) and $_REQUEST['debug']==1)?true:false;
    $cid    =(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    
    try {
        $conn = new PDO("mssql:host=".HOST.";dbname=".DB,USER,PWD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        $out['error']=true;
        if ($debug) {
            $out['result']=$e->getMessage();
        }
        else {
            $out['result']='DB Connect Error'.__LINE__;
        }
    }
    
    if ($cid!=0)
    {
        $appt_mo =(isset($_REQUEST['appt_mo']) and strlen($_REQUEST['appt_mo']) >= 1)?trim($_REQUEST['appt_mo']):'';
        $appt_da =(isset($_REQUEST['cwork']) and strlen($_REQUEST['cwork']) >= 1)?trim($_REQUEST['cwork']):'';
        $appt_yr =(isset($_REQUEST['ccell']) and strlen($_REQUEST['ccell']) >= 1)?trim($_REQUEST['ccell']):'';
        $appt_hr =(isset($_REQUEST['cfax']) and strlen($_REQUEST['cfax']) >= 1)?trim($_REQUEST['cfax']):'';
        $appt_mn =(isset($_REQUEST['ccontime']) and strlen($_REQUEST['ccontime']) >= 1)?trim($_REQUEST['ccontime']):'';
        $appt_pa =(isset($_REQUEST['cemail']) and strlen($_REQUEST['cemail']) >= 1)?trim($_REQUEST['cemail']):'';
        $hold_mo =(isset($_REQUEST['appt_mo']) and strlen($_REQUEST['appt_mo']) >= 1)?trim($_REQUEST['appt_mo']):'';
        $hold_da =(isset($_REQUEST['cwork']) and strlen($_REQUEST['cwork']) >= 1)?trim($_REQUEST['cwork']):'';
        $hold_yr =(isset($_REQUEST['ccell']) and strlen($_REQUEST['ccell']) >= 1)?trim($_REQUEST['ccell']):'';
        //$lsource =(isset($_REQUEST['cconph']) and strlen($_REQUEST['cconph']) >= 1)?trim($_REQUEST['cconph']):'';
        //$lresult =(isset($_REQUEST['stage']) and strlen($_REQUEST['stage']) >= 1)?trim($_REQUEST['stage']):'';
    
        try {
            $stmt=$conn->prepare("update cinfo set chome=?,cwork=?,ccell=?,cfax=?,cemail=?,ccontime=?,cconph=? where cid=?;");
            $stmt->bindParam(1,$chome);
            $stmt->bindParam(2,$cwork);
            $stmt->bindParam(3,$ccell);
            $stmt->bindParam(4,$cfax);
            $stmt->bindParam(5,$cemail);
            $stmt->bindParam(6,$ctime);
            $stmt->bindParam(7,$cconph);
            $stmt->bindParam(8,$cid);
            if ($stmt->execute())
            {
                $out['result']='Saved';
            }
            else {
                $out['error']=true;
                $out['result']='Not Saved '.__LINE__;
            }
        }
        catch(PDOException $e) {
            $out['error']=true;
            if ($debug) {
                $out['result']=$e->getMessage();
            }
            else {
                $out['result']='DB Save Error'.__LINE__;
            }
        }
    }
    else {
        $out['error']=true;
        $out['result']='CID Error'.__LINE__;
    }
    
    return $out;
}

function showAppointment($appt)
{
    $pa=($appt['mo']!=0 and $appt['pa']==1)?'AM':'PM';
    return ($appt['mo']!=0)?str_pad($appt['mo'],2,'0',STR_PAD_LEFT)."/".str_pad($appt['da'],2,'0',STR_PAD_LEFT)."/".substr($appt['yr'],2,2)." ".$appt['hr'].":".str_pad($appt['mn'],2,'0',STR_PAD_LEFT).$pa:'';
}