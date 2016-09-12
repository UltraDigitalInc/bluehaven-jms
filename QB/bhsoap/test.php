<?php

ini_set('display_errors','On');
error_reporting(E_ALL);

//require('../Quickbooks.php');

echo 'START<BR>';

$ID=114;

mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
mssql_select_db('jest') or die("Table unavailable");
	
$row0 = mssql_fetch_array(mssql_query("select oid,invid,ListId,EditSequence from jest_ext..jms_qb_ident_inventory_map where invid=" . (int) $ID));
$row1 = mssql_fetch_array(mssql_query("select pb_code from jest..offices where officeid=" . (int) $row0['oid']));
$row9 = mssql_fetch_array(mssql_query("SELECT item,bprice FROM [".trim($row1['pb_code'])."inventory] WHERE invid =" . (int) $row0['invid']));

echo '<pre>';

print_r($row0);
print_r($row1);
print_r($row9);

echo '</pre>';

exit;
$row9 = mssql_fetch_array(mssql_query("SELECT * FROM [cinfo] WHERE cid =" . (int) $ID));

/*
echo '<pre>';
print_r($row9);
echo '</pre><br>';
echo number_format($row9['bprice'],2,'.','');
*/
// Create new customer object from existing customer
$Customer = new QuickBooks_Object_Customer();

// change some properties
$Customer->setFirstName($row9['cfname']);
$Customer->setLastName($row9['clname']);
$Customer->setPhone($row9['chome']);
$Customer->setEmail($row9['cemail']);

echo '<pre>';
//print_r($Customer);
print($Customer->asQBXML(QUICKBOOKS_MOD_CUSTOMER));
echo '</pre><br>';

?>