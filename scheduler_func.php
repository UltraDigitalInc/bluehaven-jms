<?php

$s_ar  =array();
$s_ar[]='https://jms.bhnmi.com/process_matrix.php';
$s_ar[]='https://jms.bhnmi.com/process_attach.php';
$s_ar[]='https://jms.bhnmi.com/process_bhcustcare.php';

function processURL($url)
{
    $handle= fopen($url,'r');
    $contents = stream_get_contents($handle);
    fclose($handle);
    
    echo $contents;
}

foreach ($s_ar as $n => $v)
{
    processURL($v);
}

?>