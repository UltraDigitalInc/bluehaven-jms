<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);

include_once('connect_db.php');

function getDigReports() {	
	$qry1 = "select * from digreport_main;";
	$res1 = mssql_query($qry1);
    
	$digs_ar=array();
    while ($row1 = mssql_fetch_array($res1)) {
		$sub_ar1=array();
		$sub_ar2=array();
		
		if ($row1['no_digs'] > 0) {
			$sub_ar1=explode(',',$row1['jtext']);
			
			foreach ($sub_ar1 as $n=>$v) {
				$sub_ar2[]=explode(':',$v);
			}
			
			$digs_ar[$row1['officeid']][$row1['rept_yr']][$row1['rept_mo']][$row1['id']]=$sub_ar2;
		}
    }
	
	
	return $digs_ar;
}

$r=getDigReports();

if (count($r) > 0) {
	echo '<pre>';
	print_r($r);
	echo '</pre>';
}

?>