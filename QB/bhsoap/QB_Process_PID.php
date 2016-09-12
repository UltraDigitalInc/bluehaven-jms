<?php

ini_set('display_errors','On');
error_reporting(E_ALL);
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../');

require_once('QB_Support.php');

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

if (!isset($_REQUEST['qact']) or empty($_REQUEST['qact']))
{
    die('Error! Queue Action not set ('. __LINE__ .')');
}
else
{
    $qact=$_REQUEST['qact'];
}

if (!isset($_REQUEST['pid']) or $_REQUEST['pid'] == '' or $_REQUEST['pid'] == 0)
{
    $pid=get_item_list($oid,$qact);
}
else
{
    $pid=$_REQUEST['pid'];
}

// Run Main Process Logic
request_multi_process($pid,$qact,$sid,$oid);

?>