<?php

ini_set('display_errors','On');
error_reporting(E_ALL);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../');


if (!isset($_SESSION['securityid']))
{
    die('Not Authorized ('. __LINE__ .')');
}

require_once 'QuickBooks.php';

$link = mssql_connect('CORP-DB02', 'sa', 'date1995');

if (!$link)
{
    die('Unable to connect to database! ('. __LINE__ .')');
}

$dselect= mssql_select_db('jest', $link);

if (!$dselect)
{
    die('Unable to select database! ('. __LINE__ .')');
}

if (isset($_GET['cid']))
{
    $qry = mssql_query('select cid from cinfo where cid=' . (int) $_GET['cid']);
    $row = mssql_fetch_array($qry);
    $nrow = mssql_num_rows($qry);
    
    if ($nrow==1)
    {
        //echo '<pre>';
        //print_r($row);
        //echo '</pre>';
        
        $cust_id=$row['cid'];
        
        $queue = new QuickBooks_Queue('mssql://sa:date1995@CORP-DB01/BH_SOAP');
        $queue->enqueue($_GET['saction'], $cust_id);
        
        echo 'CID '. $cust_id .' Queued!';
    }
    else
    {
        echo 'CID '. $_GET['cid'] .' not found!';
    }
}
else
{
    echo 'CID not set';
}

// Clean up
mssql_free_result($qry);

?>