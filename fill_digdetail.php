<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);

function get_jobid($oid,$cid)
{
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    //echo $oid.':'.$cid.'<br>';
    
    $qry 	= "
            select
                 J.jid
                ,J.jobid
                ,C.cid
                ,J.custid
                ,C.clname
                ,J.officeid
                ,C.officeid
                ,C.added
            from jobs as J
            inner join cinfo as C
            ON C.cid=J.custid
            where J.officeid=".(int) $oid."
            and C.officeid=".(int) $oid."
            and C.cid=".(int) $cid.";
        ";
    $res 	= mssql_query($qry);
    $nrow 	= mssql_num_rows($res);
    
    if ($nrow==1)
    {
        $row = mssql_fetch_array($res);
        $out=$row['jid'];
    }
    else
    {
        $out=0;
    }
    
    return $out;
}

function proc_digdetail($mo,$yr)
{
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    $cc=1;
    $qry 	= "
            SELECT
                DM.id,DM.officeid,DM.jtext,DM.no_digs,DM.no_rens,DM.no_addn,
                (select name from offices where officeid=DM.officeid) as oname
            FROM
                digreport_main as DM
            WHERE
                DM.rept_yr=".$yr."
                and DM.rept_mo=".$mo."
                and DM.no_digs!=0
            ORDER BY DM.officeid;";
    $res 	= mssql_query($qry);
    $nrow 	= mssql_num_rows($res);
    
    echo '<table border="1">';
    echo '  <tr><td colspan="2">Dig Report Analysis</td><td colspan="2">New Build</td><td colspan="2">Addendum</td><td colspan="2">Renovation</td></tr>';
    echo '  <tr><td>ReportID</td><td>Office</td><td>Header</td><td>Actual</td><td>Header</td><td>Actual</td><td>Header</td><td>Actual</td></tr>';
    
    $nbh=0;
    $nrh=0;
    $nah=0;
    $nba=0;
    $nra=0;
    $naa=0;
    while ($row = mssql_fetch_array($res))
    {
        $nb=0;
        $nr=0;
        $na=0;
        $fj=explode(',',$row['jtext']);
        foreach ($fj as $n1=>$v1)
        {
            $sj=explode(':',$v1);
            
            if ($sj[20]==0)
            {
                $nb++;
            }
            elseif ($sj[20]==1)
            {
                $nr++;
            }
            elseif ($sj[20]==2)
            {
                $na++;
            }
            
            /*
            $qry1  = "INSERT INTO digdetail (";
            $qry1 .= "[drid]";
            $qry1 .= ",[cid]";
            $qry1 .= ",[clname]";
            $qry1 .= ",[srid]";
            $qry1 .= ",[jid]";
            $qry1 .= ",[jobid]";
            $qry1 .= ",[dispjn]";
            $qry1 .= ",[realjn]";
            $qry1 .= ",[digdate]";
            $qry1 .= ",[digtype]";
            $qry1 .= ",[contractprice]";
            $qry1 .= ",[commission]";
            $qry1 .= ",[accountingfee]";
            $qry1 .= ",[consultingfee]";
            $qry1 .= ",[allowance]";
            $qry1 .= ",[royalty]";
            $qry1 .= ",[grossprofit]";
            $qry1 .= ",[addendum]";
            $qry1 .= ",[perimeter]";
            $qry1 .= ",[surfacearea]";
            $qry1 .= ",[items]";
            $qry1 .= ") VALUES (";
            $qry1 .= "".$row['id'].""; //drid
            $qry1 .= (!empty($sj[1]))? ",".$sj[1]."": ",0"; //cid
            $qry1 .= (!empty($sj[9]))? ",'".trim($sj[9])."'": ",''"; //clname
            $qry1 .= (!empty($sj[8]))? ",".$sj[8]."": ",0"; //srid
            $qry1 .= (!empty($sj[1]) && $sj[1]!=0)? ",".get_jobid($row['officeid'],$sj[1]) : ",0"; //jid
            $qry1 .= (!empty($sj[0]))? ",'".$sj[0]."'" : ",'0'"; //jobid
            $qry1 .= (!empty($sj[0]))? ",'".$sj[0]."'" : ",'0'"; //dispjn
            $qry1 .= (!empty($sj[12]))? ",'".$sj[12]."'" : ",'0'"; //realjn
            $qry1 .= (!empty($sj[6]))?",'".$sj[6]."'" : '1/1/1970'; //digdate
            $qry1 .= (!empty($sj[20]))? ",".$sj[20]."" : ",0"; //digtype
            $qry1 .= (!empty($sj[2]))? ",cast('".$sj[2]."' as money)" : ",cast('0' as money)"; //contractprice
            $qry1 .= (!empty($sj[19]))? ",cast('".$sj[19]."' as money)" : ",cast('0' as money)"; //commission
            $qry1 .= (!empty($sj[4]))? ",cast('".$sj[4]."' as money)" : ",cast('0' as money)"; //accountingfee
            $qry1 .= (!empty($sj[5]))? ",cast('".$sj[5]."' as money)" : ",cast('0' as money)"; //consultingfee
            $qry1 .= (!empty($sj[13]))? ",cast('".$sj[13]."' as money)" : ",cast('0' as money)"; //allowance
            $qry1 .= (!empty($sj[3]))? ",cast('".$sj[3]."' as money)" : ",cast('0' as money)"; //royalty
            $qry1 .= (!empty($sj[18]))? ",cast('".$sj[18]."' as money)" : ",cast('0' as money)"; //grossprofit
            $qry1 .= (!empty($sj[14]))? ",cast('".$sj[14]."' as money)" : ",cast('0' as money)"; //addendum
            $qry1 .= (!empty($sj[15]))? ",".$sj[15]."": ",0"; //perimeter
            $qry1 .= (!empty($sj[16]))? ",".$sj[16]."": ",0"; //surfacearea
            $qry1 .= (!empty($sj[17]))? ",'".trim($sj[17])."'" : ",''"; //items
            $qry1 .= ");";
            $res1  = mssql_query($qry1);
            */
        }
        
        $nbh=$nbh+$row['no_digs'];
        $nah=$nah+$row['no_addn'];
        $nrh=$nrh+$row['no_rens'];
        $nba=$nba+$nb;
        $naa=$naa+$na;
        $nra=$nra+$nr;
        
        echo '  <tr><td>'.$row['id'].'</td><td>'.$row['oname'].'</td><td>'.$row['no_digs'].'</td><td>'.$nb.'</td><td>'.$row['no_addn'].'</td><td>'.$na.'</td><td>'.$row['no_rens'].'</td><td>'.$nr.'</td></tr>';
    }
    
    echo '  <tr><td colspan="2">Totals</td><td>'.$nbh.'</td><td>'.$nba.'</td><td>'.$nah.'</td><td>'.$naa.'</td><td>'.$nrh.'</td><td>'.$nra.'</td></tr>';
    echo '</table>';
}

if (isset($_SESSION['securityid']) && $_SESSION['officeid']==89)
{
    include('connect_db.php');
    //echo 'START ANALYZE: <br>';
    proc_digdetail($_REQUEST['mo'],$_REQUEST['yr']);
}
else
{
    echo 'Not Authorized';
}


