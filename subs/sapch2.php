<?php

    session_start();
    
    try
    {
        if (isset($_SESSION['securityid']))
        {
            include('../connect_db.php');
            include('../common_func.php');
            
            $qry0 = "SELECT * FROM digreport_main WHERE officeid=".$_REQUEST['oid']." and added >='1/1/08';";
            $res0 = mssql_query($qry0);
            $nrow0= mssql_num_rows($res0);
        
            //echo $qry0.'<br />';
            
            if ($nrow0 > 0)
            {
                while($row0 = mssql_fetch_array($res0))
                {
                    //echo $row0['id'].'<br>';
                    PullandStoreCommissions($row0['id']);
                }
            }
            else
            {
                throw new Exception('No DIG Reports Found');
            }
        }
        else
        {
            throw new Exception('Session or ID Failure');
        }
    }
    catch (Exception $e)
    {
        echo 'Error: ' . $e->getMessage();
    }

?>