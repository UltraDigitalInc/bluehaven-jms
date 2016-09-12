<?php

function get_NextJobNumber($oid,$db)
{
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
    mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $out='Error';
    $qry ="select (max(njobid) + 1) as maxjobn  from jobs where officeid=" .(int) $oid;
    $res = mssql_query($qry);
    $row = mssql_fetch_array($res);
    
    if (isset($row['maxjobn']) and $row['maxjobn']!=0)
    {
        //$out=str_pad($row['maxjobn'], 5, "0", STR_PAD_LEFT);
        $out=$row['maxjobn'];
    }
    
    return $out;
}

function updateContractDate($oid,$jobid,$ndate,$db)
{
    $link = mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname'],$link) or die("Table unavailable");
    
    $qry0 = "update jdetail set contractdate='".$ndate."' where officeid=".$oid." and jobid='".$jobid."' and jadd=0;";
    $res0 = mssql_query($qry0,$link);
    
    if (mssql_rows_affected($link)==1)
    {
        return mssql_rows_affected($link);
    }
    else
    {
        return 'Error: More than 1 record was updated.';
    }
}

function set_SandC($oid,$jobid,$sandc,$db) {
	$out=0;
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$qry0 = "UPDATE jobs SET sandc=".(int) $sandc." WHERE officeid=".(int) $oid." and jobid='".$jobid."';";
    $res0 = mssql_query($qry0);
    //$row0 = mssql_fetch_array($res0);
	//echo $qry0;
}

function checkContractDate($oid,$cid,$ndate,$db)
{
    $out=0;
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    include ('../common_func.php');
    
    if (valid_date($ndate))
    {
        $qry0 = "select officeid,custid,jobid from jobs where officeid=".(int) $oid." and custid=" .(int) $cid . ";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        $qry1 = "select cdate from constructiondates where cid=" .(int) $cid . " and jobid='" .$row0['jobid'] . "' and phsid=9;";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);
        $nrow1= mssql_num_rows($res1);
        
        if ($nrow1 > 0)
        {
            if (strtotime($ndate) > strtotime($row1['cdate']))
            {
                $out="Contract Date exceeds Dig Date!<br>Not Updated";
            }
            else
            {
                $out=updateContractDate($oid,$row0['jobid'],$ndate,$db);
            }
        }
        else
        {
            $out=updateContractDate($oid,$row0['jobid'],$ndate,$db);
        }
    }
    else
    {
        $out='Date Syntax Error';
    }
    
    return $out;
}

?>