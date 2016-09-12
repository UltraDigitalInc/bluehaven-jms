<?php

ini_set('display_errors','On');
error_reporting(E_ALL);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../');

//$dbg=0;

if (!isset($_REQUEST['sid']) or empty($_REQUEST['sid']))
{
    $sid=0;
    //die('Error! Not Authorized ('. __LINE__ .')');
}
else
{
    $sid=$_REQUEST['sid'];
}

if (!isset($_REQUEST['oid']))
{
    //$oid=0;
    die('Error! OID not set ('. __LINE__ .')');
}
else
{
    $oid=$_REQUEST['oid'];
}

if (!isset($_REQUEST['pid']) or $_REQUEST['pid'] == '' or $_REQUEST['pid'] == 0)
{
    die('Error! PID not set ('. __LINE__ .')');
}
else
{
    $pid=$_REQUEST['pid'];
}

if (!isset($_REQUEST['qact']) or empty($_REQUEST['qact']))
{
    die('Error! Queue Action not set ('. __LINE__ .')');
}
else
{
    $qact=$_REQUEST['qact'];
}

//echo '<pre>';
//print_r($_REQUEST);
//echo '</pre>';
//exit;

function request_multi_process($pid,$a,$s,$o)
{
    require_once('QB_Support.php');
    
    $user='sa';
    $pass='date1995';
    $host='CORP-DB01';
    $catl='BH_SOAP';
    
    $q="mssql://".$user.":".$pass."@".$host."/".$catl;
    
    require_once 'QuickBooks.php';
    
    $queue = new QuickBooks_Queue($q);
    
    foreach ($pid as $n => $i)
    {
        //echo $i.'<br>';
        $cpp=check_pid($i,$o,$a);
        if ($cpp[0])
        {
            $fpp=_find_prior_qb_process($i,$a,$user,$pass,$host,$catl);
            if ($fpp[0])
            {
                echo $a . ' Action Exists:<br>';
                echo print_r($fpp[4]).'<br>';
                
                if (isset($fpp[3]) and ($fpp[3]=='i' or $fpp[3]=='e' or $fpp[3]=='h' or $fpp[3]=='n'))
                {
                    // Incomplete or Error or QB Handler Process
                    if (isset($_REQUEST['qforce']) and $_REQUEST['qforce']=='r')
                    {
                        //echo 'Requeue Set<br>';
                        _action_requeue($a,$i,$user,$pass,$host,$catl);
                    }
                }
            }
            else
            {
                $queue->enqueue($a, $i, pri_level($a),$o);
                echo 'Prior '.$a.' ('.$i.')('.$o.') Process not Found: Action Queued<BR>';
            }
            
            //echo '<pre>';
            //print_r($fpp);
            //echo '</pre>';
        }
        else
        {
            echo 'Error! PID Invalid or not Authorized ('. __LINE__ .')('. $a .')('. $i .')<br>';
        }
    }
}

// Run Main Process Logic
request_multi_process($pid,$qact,$sid,$oid);

?>