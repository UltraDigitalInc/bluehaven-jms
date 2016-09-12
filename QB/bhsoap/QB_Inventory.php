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

if (!isset($_REQUEST['invid']) or $_REQUEST['invid'] == '' or $_REQUEST['invid'] == 0)
{
    die('Error! INVID not set ('. __LINE__ .')');
}
else
{
    $invid=$_REQUEST['invid'];
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
    if (check_invid($i,$o))
    {
        $q='mssql://sa:date1995@CORP-DB01/BH_SOAP';
        
        require_once 'QuickBooks.php';
        
        $queue = new QuickBooks_Queue($q);
        foreach ($a as $qn => $qv)
        {
            if (!find_prior_inventory_process($i,$o,$qv))
            {
                $t=inventory_action_queued($i,$o,$s,$qv);
                
                if ($t!=0)
                {
                    $queue->enqueue(trim($qv), $t, pri_level($qv));
                    
                    echo 'INVID: '. $i .' OID: '. $o .' Request: '. $qv .' Priority: '. pri_level($qv) .'<br>';
                }
                else
                {
                    echo 'ERROR! INVID: '. $i .' OID: '. $o .' Request: '. $qv .' Priority: '. pri_level($qv) .' not queued<br>';
                }
            }
            else
            {
                echo 'ERROR! INVID: '. $i .' OID: '. $o .' Request: '. $qv .' Priority: '. pri_level($qv) .' already queued<br>';
            }
        }
    }
    else
    {
        die('Error! CID Invalid ('. __LINE__ .')');
    }
}

// Run Main Process
request_process($invid,$oid,$sid,$qact);

?>