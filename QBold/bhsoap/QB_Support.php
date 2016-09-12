<?php

function action_map($s)
{
    $out='';
    
    if ($s==='CustomerAdd' or $s==='CustomerMod' or $s==='CustomerQuery')
    {
        $out='Customer';
    }
    elseif ($s==='EstimateAdd' or $s==='EstimateMod' or $s==='EstimateQuery')
    {
        $out='Estimate';
    }
    elseif ($s==='ItemInventoryAdd' or $s==='ItemInventoryMod' or $s==='ItemInventoryQuery')
    {
        $out='ItemInventory';
    }
    elseif ($s==='ItemServiceAdd' or $s==='ItemServiceMod' or $s==='ItemServiceQuery')
    {
        $out='ItemService';
    }
    
    return $out;
}

function pri_level($s)
{
    $out=0;
    
    if ($s==='CustomerAdd')
    {
        $out=10;
    }
    elseif ($s==='CustomerMod')
    {
        $out=10;
    }
    elseif ($s==='CustomerQuery')
    {
        $out=0;
    }
    elseif ($s==='SalesRepAdd')
    {
        $out=10;
    }
    elseif ($s==='SalesRepMod')
    {
        $out=10;
    }
    elseif ($s==='SalesRepQuery')
    {
        $out=0;
    }
    elseif ($s==='EstimateAdd')
    {
        $out=0;
    }
    elseif ($s==='EstimateMod')
    {
        $out=0;
    }
    elseif ($s==='EstimateQuery')
    {
        $out=0;
    }
    elseif ($s==='ItemInventoryAdd')
    {
        $out=0;
    }
    elseif ($s==='ItemInventoryMod')
    {
        $out=0;
    }
    elseif ($s==='ItemInventoryQuery')
    {
        $out=0;
    }
    elseif ($s==='ItemServiceAdd')
    {
        $out=0;
    }
    elseif ($s==='ItemServiceMod')
    {
        $out=0;
    }
    elseif ($s==='ItemServiceQuery')
    {
        $out=0;
    }
    
    return $out;
}

function check_cid($cid,$oid)
{
    $out=array();
    $link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
    $qry = 'select cid,officeid from jest..cinfo where cid=' . (int) $cid .' and officeid=' . (int) $oid;
    $res = mssql_query($qry);
    $row = mssql_fetch_array($res);
    $nrow= mssql_num_rows($res);
    
    //echo $qry.'<br>('.__LINE__.')('.$oid.')('.$cid.')<br>';
    
    if ($nrow==1)
    {
        $out=array(true);
    }
    else
    {
        $out=array(false);
    }
    
    return $out;
}

function check_jobid($jobid,$oid)
{
    $out=array();
    $link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
    $qry = 'select cid,jobid,officeid from jest..cinfo where jobid=' . trim($jobid) .' and officeid=' . (int) $oid;
    $res = mssql_query($qry);
    $row = mssql_fetch_array($res);
    $nrow= mssql_num_rows($res);
    
    echo $qry.'<br>('.__LINE__.')('.$oid.')('.$cid.')<br>';
    
    if ($nrow==1)
    {
        $out=array(true);
    }
    else
    {
        $out=array(false);
    }
    
    return $out;
}

function check_invid($invid,$oid,$act)
{
    $out=array();
    $link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
    $qry0 = mssql_query("select pb_code from jest..offices where officeid=" . (int) $oid);
    $row0 = mssql_fetch_array($qry0);
    $nrow0= mssql_num_rows($qry0);
    $MAS_CODE=$row0['pb_code'];
    
    if ($nrow0 > 0)
    {
        $qry = mssql_query("select invid from jest..[".$MAS_CODE."inventory] where invid=" . (int) $invid);
        $nrow= mssql_num_rows($qry);
        
        if ($nrow==1)
        {
            $out=array(true);
        }
        else
        {
            $out=array(false);
        }
    }
    else
    {
        $out=array(false);
    }
    
    return $out;
}

function check_srvid($id,$oid,$act)
{
    $out=array();
    
    $link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
    $qry0 = mssql_query("select pb_code from [jest]..[offices] where [officeid]=" . (int) $oid);
    $row0 = mssql_fetch_array($qry0);
    $nrow0= mssql_num_rows($qry0);
    $MAS_CODE=$row0['pb_code'];
    
    if ($nrow0 > 0)
    {
        $qry1 = mssql_query("select id from [jest]..[".$MAS_CODE."accpbook] where [id]=" . (int) $id);
        $nrow1= mssql_num_rows($qry1);
        
        if ($nrow1==1)
        {
            //$qry1 = mssql_query("select * from [jest_ext]..[jms_qb_ident_service_map] where [srvid]=" . (int) $id);
            //$nrow1= mssql_num_rows($qry1);
            $out=array(true);
        }
        else
        {
            $out=array(false);
        }
    }
    else
    {
        $out=array(false);
    }
    
    return $out;
}

function inventory_action_queued($i,$o,$s,$a)
{
    $out=0;
    
    $link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
    $qry = mssql_query("INSERT INTO jest_ext..qb_process_inventory_queue (invid,oid,sid,qaction,qdate) values (".$i.",".$o.",".$s.",'". trim($a) ."',getdate()); SELECT @@IDENTITY");
    $row = mssql_fetch_array($qry);
    
    if (isset($row[0]) and $row[0] != 0)
    {
        $qry1 = mssql_query("SELECT invid FROM jest_ext..jms_qb_ident_inventory_map  WHERE invid=".$i." and oid=".$o.";");
        $nrow1 = mssql_num_rows($qry1);
        
        if ($nrow1==0)
        {
            $qry2 = mssql_query("INSERT INTO jest_ext..jms_qb_ident_inventory_map (invid,oid) values (".$i.",".$o.");SELECT @@IDENTITY;");
            $row2 = mssql_fetch_array($qry2);
            $out  = $row2[0];
        }
    }

    return $out;
}

function service_action_queued($i,$o,$s,$a)
{
    $out=0;
    
    $link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
    $qry = mssql_query("INSERT INTO jest_ext..qb_process_service_queue (srvid,oid,sid,qaction,qdate) values (".$i.",".$o.",".$s.",'". trim($a) ."',getdate()); SELECT @@IDENTITY");
    $row = mssql_fetch_array($qry);
    
    if (isset($row[0]) and $row[0] != 0)
    {
        $qry1 = mssql_query("SELECT srvid FROM jest_ext..jms_qb_ident_service_map  WHERE srvid=".$i." and oid=".$o.";");
        $nrow1 = mssql_num_rows($qry1);
        
        if ($nrow1==0)
        {
            $qry2 = mssql_query("INSERT INTO jest_ext..jms_qb_ident_service_map (srvid,oid) values (".$i.",".$o.");SELECT @@IDENTITY;");
            $row2 = mssql_fetch_array($qry2);
            $out  = $row2[0];
        }
    }

    return $out;
}

function check_pid($i,$o,$a)
{
    $out=false;
    $sa=action_map($a);
    
    echo $i.'<br>';
    echo $o.'<br>';
    echo $a.'<br>';
    echo $sa.'<br>';
    
    if ($sa=='Customer')
    {
        //echo 'Going CUST<br>';
        $out=check_cid($i,$o);
    }
    elseif ($sa=='ItemService')
    {
        //echo 'Going SRV<br>';
        $out=check_srvid($i,$o,$a);
    }
    elseif ($sa=='ItemInventory')
    {
        //echo 'Going INV<br>';
        $out=check_invid($i,$o,$a);
    }
    elseif ($sa=='Estimate')
    {
        echo 'Going CUST<br>';
        $out=check_jobid($i,$o);
    }
    
    return $out;
}

function _action_requeue($a,$c,$user,$pass,$host,$catl)
{
    error_reporting(E_ALL);
    
    $out=false;
    
    $link = mssql_connect($host, $user, $pass);

    if (!$link)
    {
        return $out=array(false,'','','Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db($catl, $link);
    
    if (!$dselect)
    {
        return $out=array(false,'','','Error! Unable to select database! ('. __LINE__ .')');
    }

    $qry  = "select [quickbooks_queue_id],[qb_action],[qb_status],[msg] from [quickbooks_queue] where qb_action='". trim($a) ."' and [ident]=". $c .";";
    $res  = mssql_query($qry);
    $row  = mssql_fetch_array($res);
    $nrow = mssql_num_rows($res);
    
    if ($nrow > 0)
    {
        $qry1  = "update [quickbooks_queue] set [qb_status]='q' where [quickbooks_queue_id]=". $row['quickbooks_queue_id'] .";";
        $res1  = mssql_query($qry1);
        
        //echo $qry1;
        $out=true;
    }
    
    return $out;
}

function _action_delete($c,$qv,$user,$pass,$host,$catl)
{
    error_reporting(E_ALL);
    
    $out=false;
    
    $link = mssql_connect($host, $user, $pass);

    if (!$link)
    {
        return $out=array(false,'','','Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db($catl, $link);
    
    if (!$dselect)
    {
        return $out=array(false,'','','Error! Unable to select database! ('. __LINE__ .')');
    }

    $qry  = "select [quickbooks_queue_id],[qb_action],[qb_status],[msg] from [quickbooks_queue] where qb_action='". trim($qv) ."' and [ident]=". $c .";";
    $res  = mssql_query($qry);
    $row  = mssql_fetch_array($res);
    $nrow = mssql_num_rows($res);
    
    if ($nrow > 0)
    {
        //$qry1  = "update [quickbooks_queue] set [qb_status]='q' where [quickbooks_queue_id]=". $row['quickbooks_queue_id'] .";";
        $qry1  = "update [quickbooks_queue] set [qb_status]='q' where [quickbooks_queue_id]=". $row['quickbooks_queue_id'] .";";
        $res1  = mssql_query($qry1);
        
        $qry2  = "update [quickbooks_queue] set [qb_status]='q' where [quickbooks_queue_id]=". $row['quickbooks_queue_id'] .";";
        $res2  = mssql_query($qry2);
        
        //echo $qry1;
        $out=true;
    }
    
    return $out;
}

function _find_prior_qb_process($c,$qaction,$user,$pass,$conn,$db)
{
    error_reporting(E_ALL);
    
    /*
    $out array parameters:
    0=Record Exists (BOOL)
    1=Queue Status (CHAR(1))
    2=Queue Status Msg (CHAR(40))
    */
    
    $add_ar=array('CustomerAdd','ItemServiceAdd','ItemInventoryAdd');
    $mod_ar=array('CustomerMod','ItemServiceMod','ItemInventoryMod');
    $qry_ar=array('CustomerQuery','ItemServiceQuery','ItemInventoryQuery');
    $out=array();
    
    $link = mssql_connect($conn, $user, $pass);

    if (!$link)
    {
        return $out=array(false,'','','Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db($db, $link);
    
    if (!$dselect)
    {
        return $out=array(false,'','','Error! Unable to select database! ('. __LINE__ .')');
    }
    
    //echo $qry;
    if (in_array($qaction,$add_ar)) // Add Test
    {
        $qry  = "select [quickbooks_queue_id],[qb_action],[qb_status],[msg] from [quickbooks_queue] where [qb_action]='". trim($qaction) ."' and [ident]=". $c .";";
        $res  = mssql_query($qry);
        $row  = mssql_fetch_array($res);
        $nrow = mssql_num_rows($res);
        
        if ($nrow == 1)
        {
            if ($row['qb_status']=='s')
            {
                $qry1  = mssql_query("select [quickbooks_ident_id],[qb_ident],[map_datetime] from [quickbooks_ident] where qb_object='". action_map($qaction) ."' and [unique_id]=". $c .";");
                $row1  = mssql_fetch_array($qry1);
                $nrow1 = mssql_num_rows($qry1);
                
                if ($nrow1 > 0)
                {
                    $out=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],$row1['qb_ident']);
                }
                else
                {
                    $out=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'QB ListID Error');
                }
            }
            elseif ($row['qb_status']=='q')
            {
                $out=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Action Process Status: Queued');
            }
            elseif ($row['qb_status']=='i')
            {
                $out=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Action Process Status: Process Incomplete Error: '.$row['msg']);
            }
            elseif ($row['qb_status']=='e')
            {
                $out=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Action Process Status: Fatal Error: '.$row['msg']);
            }
            elseif ($row['qb_status']=='h')
            {
                $out=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Action Process Status: QB Handler Error: '.$row['msg']);
            }
            elseif ($row['qb_status']=='n')
            {
                $out=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Action Process Status: QB No-op: '.$row['msg']);
            }
            else
            {
                $out=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],$row['msg']);
            }
        }
        elseif ($nrow > 1)
        {
            $out=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Action Process Status: Queued > 1');
        }
        else
        {
            $out=array(false,'','','','Prior Action Process Not Found. New Action Queued');
        }
    }
    elseif (in_array($qaction,$mod_ar) or in_array($qaction,$qry_ar)) // Mod or Qry Tests
    {
        $qry  = "select [quickbooks_queue_id],[qb_action],[qb_status],[msg] from [quickbooks_queue] where [qb_action]='". trim($qaction) ."' and [qb_status]!='s' and [ident]=". $c .";";
        $res  = mssql_query($qry);
        $nrow = mssql_num_rows($res);
        
        if ($nrow > 0)
        {
            $stat_ar=array();
            
            while ($row  = mssql_fetch_array($res))
            {                
                if ($row['qb_status']=='q')
                {
                    $stat_ar[]=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Prior Action ('. $nrow .'): Queued');
                }
                elseif ($row['qb_status']=='i')
                {
                    $stat_ar[]=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Prior Action ('. $nrow .'): Process Incomplete Error: '.$row['msg']);
                }
                elseif ($row['qb_status']=='e')
                {
                    $stat_ar[]=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Prior Action ('. $nrow .'): Fatal Error: '.$row['msg']);
                }
                elseif ($row['qb_status']=='h')
                {
                    $stat_ar[]=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Prior Action ('. $nrow .'): QB Handler Error: '.$row['msg']);
                }
                elseif ($row['qb_status']=='n')
                {
                    $stat_ar[]=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Prior Action ('. $nrow .'): QB No-op: '.$row['msg']);
                }
                else
                {
                    $stat_ar[]=array(true,$row['quickbooks_queue_id'],$row['qb_action'],$row['qb_status'],'Prior Action ('. $nrow .'): Status Not Found: '.$row['msg']);
                }
            }
            
            $out=array(true,'','','',$stat_ar);
        }
        else
        {
            $out=array(false,'','','','Prior Action Not Found. New Action Queued');
        }
    }
    else
    {
        $out=array(false,'','','','Prior Action Not Found. New Action Queued');
    }

    return $out;
}

function find_prior_customer_process($c,$qaction,$user,$pass,$conn,$db)
{
    /*
    $out array parameters:
    0=Record Exists (BOOL)
    1=Queue Status (CHAR(1))
    2=Queue Status Msg (CHAR(40))
    */
    $out=array();
    
    $link = mssql_connect($conn, $user, $pass);

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db($db, $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
    //$action_map=action_map($qaction);

    $qry  = mssql_query("select [quickbooks_queue_id],[qb_status],[msg] from [quickbooks_queue] where qb_action='". trim($qaction) ."' and [ident]=". $c .";");
    $row  = mssql_fetch_array($qry);
    $nrow = mssql_num_rows($qry);
    
    if ($nrow > 0)
    {
        if ($row['qb_status']=='s')
        {
            $qry1  = mssql_query("select [quickbooks_ident_id],[qb_ident],[map_datetime] from [quickbooks_ident] where qb_object='". action_map($qaction) ."' and [unique_id]=". $c .";");
            $row1  = mssql_fetch_array($qry1);
            $nrow1 = mssql_num_rows($qry1);
            
            if ($nrow1 > 0)
            {
                return $out=array(true,$row['quickbooks_queue_id'],$row['qb_status'],$row1['qb_ident']);
            }
            else
            {
                return $out=array(false,$row['quickbooks_queue_id'],$row['qb_status'],'QB ListID Error');
            }
        }
        else
        {
            return $out=array(false,$row['quickbooks_queue_id'],$row['qb_status'],$row['msg']);
        }
    }
    else
    {
        return $out=array(false,'','','Process Not Found');
    }
}

function find_prior_inventory_process($i,$o,$a)
{
    $link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
    $qry = mssql_query("select qid,qaction,qdate from jest_ext..qb_process_inventory_queue where invid=". $i ." and oid=". $o ." and qaction='". trim($a) ."';");
    $nrow = mssql_num_rows($qry);
    
    if ($nrow > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function find_prior_service_process($i,$o,$a)
{
    $link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
    $qry = mssql_query("select qid,qaction,qdate from jest_ext..qb_process_service_queue where srvid=". $i ." and oid=". $o ." and qaction='". trim($a) ."';");
    $nrow = mssql_num_rows($qry);
    
    if ($nrow > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>