<?php
ini_set('display_errors','On');
error_reporting(E_ALL);

include('../connect_db.php');
include('../common_func.php');

$oid = 55;
$dte = '1/1/2011';

$qry ="select j1.jid,j1.jobid,(select estdata from jdetail as j2 where j2.officeid=j1.officeid and j2.jobid=j1.jobid and j2.jadd=0) as jobdata from jobs as j1 where j1.officeid=".$oid." and j1.digdate >='".$dte ."';";
$res = mssql_query($qry);
$nrow = mssql_num_rows($res);

while ($row = mssql_fetch_array($res))
{
    echo $row['jobid'].'\cr';
}


//echo $qry;
//echo 'Jobs: '. $nrow;