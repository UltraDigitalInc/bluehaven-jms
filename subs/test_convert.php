<?php

include('ajax_common_func.php');

//$tst=array('tst1'=>1,'tst2'=>1);

$tst=$_REQUEST['fopts'];
echo '<br>';
var_dump($tst);

//$out=ConvertToBool($tst);
$out=$_REQUEST['fopts'];
echo '<br>';
var_dump($out);


if ($out['Customer']==1)
{
    echo '<br>Customer Included';
}

if ($out['Item']==1)
{
    echo '<br>Item Included';
}