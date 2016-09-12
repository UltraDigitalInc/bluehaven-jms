<?php

ini_set('display_errors','On');
error_reporting(E_ALL);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../');

require('QB_Support.php');

if (!isset($_SESSION['securityid']))
{
    $sid=0;
    //die('Error! Not Authorized ('. __LINE__ .')');
}
else
{
    $sid=$_SESSION['securityid'];
}

if (!isset($_REQUEST['oid']))
{
    $oid=0;
    //die('Error! Not Authorized ('. __LINE__ .')');
}
else
{
    $oid=$_REQUEST['oid'];
}

if (!isset($_REQUEST['srvid']) or $_REQUEST['srvid'] == '' or $_REQUEST['srvid'] == 0)
{
    die('Error! SRVID not set ('. __LINE__ .')');
}
else
{
    $srvid=$_REQUEST['srvid'];
}

if (!isset($_REQUEST['qact']) or empty($_REQUEST['qact']))
{
    die('Error! Action not set ('. __LINE__ .')');
}
else
{
    $qact=$_REQUEST['qact'];
}

if (!isset($_REQUEST['qfrc']) or empty($_REQUEST['qfrc']))
{
    $qfrc=0;
}
else
{
    $qfrc=$_REQUEST['qfrc'];
}

function request_process($i,$o,$s,$a)
{
    if (check_srvid($i,$o))
    {
        $q='mssql://sa:date1995@CORP-DB01/BH_SOAP';
        
        require_once 'QuickBooks.php';
        
        $queue = new QuickBooks_Queue($q);
        foreach ($a as $qn => $qv)
        {
            if (!find_prior_service_process($i,$o,$qv))
            {
                $t=service_action_queued($i,$o,$s,$qv);
                
                if ($t!=0)
                {
                    $queue->enqueue(trim($qv), $t, pri_level($qv));
                    
                    echo 'SRVID: '. $i .' OID: '. $o .' Request: '. $qv .' Priority: '. pri_level($qv) .'<br>';
                }
                else
                {
                    echo 'ERROR! SRVID: '. $i .' OID: '. $o .' Request: '. $qv .' Priority: '. pri_level($qv) .' not queued<br>';
                }
            }
            else
            {
                echo 'ERROR! SRVID: '. $i .' OID: '. $o .' Request: '. $qv .' Priority: '. pri_level($qv) .' already queued<br>';
            }
        }
    }
    else
    {
        die('Error! SRVID Invalid ('. __LINE__ .')');
    }
}

// Run Main Process
request_process($srvid,$oid,$sid,$qact);

?>