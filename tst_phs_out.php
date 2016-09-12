<?php
ini_set('display_errors','On');
error_reporting(E_ALL);

set_include_path(get_include_path() . PATH_SEPARATOR .'E:\www\htdocs\QB');
include('bhsoap/QB_Support.php');

//include('connect_db.php');
//include('job_support_func.php');

//$out=proc_prior_jobcost($_REQUEST['oid'],$_REQUEST['jobid'],$_REQUEST['jadd'],false);

echo '<pre>';

//print_r($out);

echo action_map('CustomerAdd');

echo '</pre>';

?>