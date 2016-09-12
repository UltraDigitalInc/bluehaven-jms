<?php

function request_wrapper($pid,$a,$sid,$o)
{
    set_include_path(get_include_path() . PATH_SEPARATOR .'E:\www\htdocs\QB');
    include('../QB/bhsoap/QB_Support.php');

    request_multi_process($pid,$a,$sid,$o);
}

function SyncItems($o,$a,$pb,$db)
{
    $pid=array();
    
    if ($a==trim('ItemServiceAdd'))
    {
        $t='accpbook';
        $i='I.id';
    }
    else
    {
        $t='inventory';
        $i='I.invid';
    }
    
    $qry1   = "
			SELECT
				".$i." as pid
			FROM
				[".$pb.$t."] AS I
			inner join
				phasebase as P
			on
				I.phsid=P.phsid
			WHERE
				I.officeid=".$o."
				and I.ListID='0' ";
    
    if ($a==trim('ItemNonInventoryAdd'))
    {
        $qry1   .= "and P.qb_inventory_phs=0 ";
    }
    elseif ($a==trim('ItemInventoryAdd'))
    {
        $qry1   .= "and P.qb_inventory_phs=1 ";
    }

    $qry1   .= ";";
	$res1   = mssql_query($qry1);
	$nrow1  = mssql_num_rows($res1);
    
    //echo $qry1.'<br>';
    
    if ($nrow1 > 0)
    {
        while ($row1 = mssql_fetch_array($res1))
        {
            $pid[]=$row1['pid'];
        }
        
        if (count($pid) > 0)
        {
            request_wrapper($pid,$a,$_SESSION['securityid'],$o);
        }
    }
    
    //print_r($pid);
    
    return count($pid);
}

?>