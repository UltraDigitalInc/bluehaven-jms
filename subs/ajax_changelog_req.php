<?php

function send_ChangelogEmail($data) {
    
    $sp=get_EmailProfile();
    
    $emc_ar=array(
        'to'=>		$data['to'],
        'from'=>	$sp['elogin'],
        'replyto'=>	'',
        'fromname'=>'',
        'esubject'=>trim($data['esubj']),
        'ebody'=>	trim($data['ebody']),
        'elogin'=>	$sp['elogin'],
        'ehost'=>	$sp['ehost'],
        'epswd'=>	$sp['epswd'],
        'eport'=>	$sp['eport'],
        'SMTPdbg'=>	0
	);
    
	$mresult=ajax_EmailSendSSL($emc_ar);
    
    return $mresult;
}

function get_Modules() {
    $out=array();
    $qry = "select mid,modname from jest..JModule order by modname asc;";
	$res = mssql_query($qry);
    
    while($row = mssql_fetch_array($res))
    {
        $out[$row['mid']]=$row['modname'];
    }
    
    return $out;
}

function add_ChangeLogComment() {
    $out=null;
    if ((isset($_REQUEST['clid']) and $_REQUEST['clid']!=0) and isset($_REQUEST['clc'])) {
        $clid=$_REQUEST['clid'];
        $clc=$_REQUEST['clc'];
        $uid=$_SESSION['securityid'];
        
        $email_ar=array('schirmer@bluehaven.com','rnamer@bluehaven.com','tedh@datasystemintegration.com');
        
        $qry = "insert into jest_stats..ChangeLogComment (
                clid,ctext,uid
                ) values (
                ".$clid.",'".htmlspecialchars($clc)."',".$uid."
                ); SELECT @@IDENTITY as clcid;";
        $res = mssql_query($qry);
        $out = mssql_fetch_array($res);
        
        if (isset($out['clcid']) and $out['clcid']!=0) {            
            $qry1 = "select C.clid,C.cltitle,C.uid,(select email from jest..security where securityid=C.uid) as uidemail from jest_stats..ChangeLog as C where C.clid=".(int) $clid.";";
            $res1 = mssql_query($qry1);
            $row1 = mssql_fetch_array($res1);
            
            if ($row1['uid']!=$uid) {
                $qry2 = "update jest_stats..ChangeLog set ruid=".$uid.",rdate=getdate() where clid=".(int) $clid.";";
                $res2 = mssql_query($qry2);
                
            }
            
            if (isValidEmail($row1['uidemail'])) {
                $to=array($row1['uidemail']);
                $em=array('to'=>$to,'esubj'=>'New JMS ChangeLog Comment','ebody'=>'ChangeLog Subject: '.$row1['cltitle']);
                
                if (send_ChangelogEmail($em)) {
                    $out['mailsent']=1;
                }
                else {
                    $out['mailsent']=0;
                }
            }
        }
    }
    
    return $out;
}

function get_ChangeLogComment()
{
    $out=null;
    $qry = "select
                 C.clcid,C.clid,C.ctext,C.uid,C.adate,
                (select substring(fname,1,1) + substring(lname,1,1) from jest..security where securityid=C.uid) as clowner
            from
                jest_stats..ChangeLogComment as C
            where
                clid=".(int) $_REQUEST['clid']."
            order by C.adate desc;";
	$res = mssql_query($qry);
    $nrow= mssql_num_rows($res);

    if ($nrow > 0)
    {
        while($row = mssql_fetch_array($res))
        {
            $out['cmnts'][$row['clcid']]=array(
                'idx'=>strtotime($row['adate']),
                'clcid'=>$row['clcid'],
                'clid'=>$row['clid'],
                'ctext'=>$row['ctext'],
                'uid'=>$row['uid'],
                'adate'=>date('m/d/y',strtotime($row['adate'])),
                'clowner'=>$row['clowner']
            );
        }
    }
    
    return $out;
}

function get_ChangeLog() {
    $out=array('errors'=>array());    
    $qry = "select
                C.clid,C.cltype,C.mid,C.sysversion,C.cltitle,C.cldescription,C.uid,C.adate,C.rdate,C.cdate,
                (select modname from jest..jModule where mid=C.mid) as modulename,
                (select substring(fname,1,1) + substring(lname,1,1) from jest..security where securityid=C.uid) as clowner,
                (select substring(fname,1,1) + substring(lname,1,1) from jest..security where securityid=C.ruid) as reviewer,
                (select substring(fname,1,1) + substring(lname,1,1) from jest..security where securityid=C.cuid) as completer
            from
                jest_stats..ChangeLog as C ";
    
    if (isset($_REQUEST['cltype']) and ($_REQUEST['cltype']=='M' OR $_REQUEST['cltype']=='P')) {
        $qry .="WHERE C.cltype='".$_REQUEST['cltype']."' ";
    }
    else {
        $qry .="WHERE (C.cltype='M'  OR C.cltype='P') ";
    }
    
    if (isset($_REQUEST['showcompleted']) and $_REQUEST['showcompleted']==1) {
        $qry .="AND C.cuid!=0 ";
    }
    else {
        $qry .="AND C.cuid=0 ";
    }

    $qry .="order by C.adate desc;";
	$res = mssql_query($qry);
    $nrow= mssql_num_rows($res);

    if ($nrow > 0) {
        while($row = mssql_fetch_array($res)) {
            $out['data'][$row['clid']]=array(
                'idx'=>strtotime($row['adate']),
                'clid'=>$row['clid'],
                'cltype'=>$row['cltype'],
                'mid'=>$row['mid'],
                'sysversion'=>$row['sysversion'],
                'cltitle'=>$row['cltitle'],
                'cldescription'=>$row['cldescription'],
                'uid'=>$row['uid'],
                'adate'=>date('m/d/y',strtotime($row['adate'])),
                'rdate'=>date('m/d/y',strtotime($row['rdate'])),
                'cdate'=>date('m/d/y',strtotime($row['cdate'])),
                'modulename'=>$row['modulename'],
                'clowner'=>$row['clowner'],
                'reviewer'=>$row['reviewer'],
                'completer'=>$row['completer']
            );
        }
    }
    else
    {
        $out['errors'][]='No Data Found';
        //$out['data'][0]=array('Name1','Name2','Name3');
    }
    
    return $out;
}

function get_SystemVersion() {
    $out='';
    
    $qry = "select SYS_VER from jest..system_config where MASTER_OID=89;";
    $res = mssql_query($qry);
    $row = mssql_fetch_array($res);
    
    $out=$row['SYS_VER'];
    return $out;
}

function add_ChangeLogRequest() {
    $out=0;
    
    if (isset($_REQUEST['mid']) && isset($_REQUEST['nct']) && isset($_REQUEST['ncd'])) {
        $mid=$_REQUEST['mid'];
        $nct=$_REQUEST['nct'];
        $clt=$_REQUEST['clt'];
        $ncd=$_REQUEST['ncd'];
        $nca=(isset($_REQUEST['nca']) and $_REQUEST['nca']==1)?true:false;
        $ncc=(isset($_REQUEST['ncc']) and $_REQUEST['ncc']==1)?true:false;
        $uid=$_SESSION['securityid'];
        $sv=get_SystemVersion();
        
        $qry = "insert into jest_stats..ChangeLog (
                mid,sysversion,cltype,cltitle,cldescription,uid
                ) values (
                ".$mid.",'".$sv."','".$clt."','".$nct."','".$ncd."',".$uid."
                ); SELECT @@IDENTITY as clid;";
        $res = mssql_query($qry);
        $out = mssql_fetch_array($res);
        
        if ($nca and (isset($out['clid']) and $out['clid'] != 0)) {
            $to=array('sschirmer@bluehaven.com','rnamer@bluehaven.com','tedh@datasystemintegration.com');
            $em=array('to'=>$to,'esubj'=>'New JMS ChangeLog Entry!','ebody'=>'ChangeLog Subject: '.$nct);
            
            if (send_ChangelogEmail($em)) {
                $out['mailsent']=1;
            }
            else {
                $out['mailsent']=0;
            }
        }
    }
    
    return $out;
}

function set_Reviewed()
{
    $out=null;
    
    if (isset($_REQUEST['clid']) and $_REQUEST['clid']!=0)
    {
        $clid=$_REQUEST['clid'];
        $sid =$_SESSION['securityid'];
        $qry1= "select securityid,(substring(fname,1,1) + substring(lname,1,1)) as ninit from jest..security where securityid=".(int) $sid.";";
        $res1= mssql_query($qry1);
        $row1= mssql_fetch_array($res1);
        
        $qry2= "select * from jest_stats..ChangeLog where clid=".(int) $clid.";";
        $res2= mssql_query($qry2);
        $row2= mssql_fetch_array($res2);
        $nrow2=mssql_num_rows($res2);
        
        if ($nrow2==1 and $row2['ruid']==0)
        {
            $qry3= "update jest_stats..ChangeLog set ruid=".(int) $sid.",rdate=getdate() where clid=".(int) $clid.";";
            $res3= mssql_query($qry3);
            
            $out=$row1['ninit'];
        }
    }
    
    return $out;
}

function set_Completed()
{
    $out=null;
    
    if (isset($_REQUEST['clid']) and $_REQUEST['clid']!=0)
    {
        $clid=$_REQUEST['clid'];
        $sid =$_SESSION['securityid'];
        $qry1= "select securityid,(substring(fname,1,1) + substring(lname,1,1)) as ninit from jest..security where securityid=".(int) $sid.";";
        $res1= mssql_query($qry1);
        $row1= mssql_fetch_array($res1);
        
        $qry2= "select * from jest_stats..ChangeLog where clid=".(int) $clid.";";
        $res2= mssql_query($qry2);
        $row2= mssql_fetch_array($res2);
        $nrow2=mssql_num_rows($res2);
        
        if ($nrow2==1 and $row2['cuid']==0)
        {
            $qry3= "update jest_stats..ChangeLog set cuid=".(int) $sid.",cdate=getdate() where clid=".(int) $clid.";";
            $res3= mssql_query($qry3);
            
            $out=$row1['ninit'];
        }
    }
    
    return $out;
}

session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);

include ('./ajax_common_func.php');

//echo '<pre>';
//print_r($_SESSION);
//echo '</pre>';

if (isTimeOut()) {
	header('HTTP/1.1 403 Forbidden');
}
else {
    $out='';
    if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0) {
        include ('../connect_db.php'); 
        if (isset($_REQUEST['call']) and $_REQUEST['call']=='get_ChangeLog') {
            $out=get_ChangeLog();
            ajaxEventProc(0);
        }
        elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='get_Modules') {
            $out=get_Modules();
            ajaxEventProc(0);
        }
        elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='Add_ChangeLogRequest') {
            $out=add_ChangeLogRequest();
            ajaxEventProc(0);
        }
        elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='set_Reviewed') {
            $out=set_Reviewed();
            ajaxEventProc(0);
        }
        elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='set_Completed') {
            $out=set_Completed();
            ajaxEventProc(0);
        }
        elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='Add_ChangeLogComment') {
            $out=add_ChangeLogComment();
            ajaxEventProc(0);
        }
        elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='get_ChangeLogComment') {
            $out=get_ChangeLogComment();
            ajaxEventProc(0);
        }
    }
    
    if (isset($_REQUEST['optype']) and $_REQUEST['optype']=='json') {
        $out=json_encode($out);
    }
    
    echo $out;
}