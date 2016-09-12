<?php

ini_set('display_errors','On');
error_reporting(E_ALL);

function valid_date($strDate)
{
	$isValid = false;

	if (ereg('^([0-9]{1,2})[-,/]([0-9]{1,2})[-,/](([0-9]{2})|([0-9]{4}))$', $strDate))
	{
		$dateArr = split('[-,/]', $strDate);
		$m=$dateArr[0]; $d=$dateArr[1]; $y=$dateArr[2];
		$isValid = checkdate($m, $d, $y);
	}
	return $isValid;
}

function get_Prior_Job_Store($oid,$jobid,$jadd,$db)
{
    $inc_data=array('oid'=>$oid,'jobid'=>$jobid,'jadd'=>$jadd);
    
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qryS   ="select * from jest..JobCost_Service where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resS   =mssql_query($qryS);
	$nrowS  =mssql_num_rows($resS);
    
    $qryM   ="select * from jest..JobCost_Material where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resM   =mssql_query($qryM);
	$nrowM  =mssql_num_rows($resM);
    
    $qryI   ="select * from jest..JobCost_Inventory where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resI   =mssql_query($qryI);
	$nrowI  =mssql_num_rows($resI);
    
    $qryB   ="select * from jest..JobCost_BidCost where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resB   =mssql_query($qryB);
    $nrowB  =mssql_num_rows($resB);
    
    $qryA   ="select * from jest..JobCost_Adjusts where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resA   =mssql_query($qryA);
	$nrowA  =mssql_num_rows($resA);
    
    $out=$nrowS+$nrowM+$nrowI+$nrowB+$nrowA;
    
	return array($out,$nrowS,$nrowM,$nrowI,$nrowB,$nrowA);
}

function clear_Accounting_State($oid,$qid,$db)
{
    //echo 'Cleared!';
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qry0	= "SELECT quickbooks_queue_id,qb_status FROM quickbooks_queue where quickbooks_queue_id='".$qid."';";    
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
    
    if ($nrow0==1)
    {
        $qry1	= "update quickbooks_queue set qb_status='q' where quickbooks_queue_id='".$qid."';";    
        $res1	= mssql_query($qry1);
    }
    
    list_QB_Queue($oid,'q',$db);
}

function clear_Accounting_State_Log($oid,$qid,$db)
{
    //echo 'Cleared!';
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qry0	= "SELECT quickbooks_queue_id,qb_status FROM quickbooks_queue where quickbooks_queue_id='".$qid."';";    
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
    
    if ($nrow0==1)
    {
        $qry1	= "update quickbooks_queue set qb_status='q' where quickbooks_queue_id='".$qid."';";    
        $res1	= mssql_query($qry1);
    }
    
    //list_QB_Queue($oid,'q',$db);
    //list_Log($oid,'e',10,$db);
}

function delete_from_Accounting_Log($oid,$qid,$db)
{
    //echo 'Cleared!';
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qry0	= "SELECT quickbooks_queue_id,qb_status FROM quickbooks_queue where quickbooks_queue_id='".$qid."';";    
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
    
    if ($nrow0==1)
    {
        $qry1	= "delete from quickbooks_queue where quickbooks_queue_id='".$qid."';";   
        $res1	= mssql_query($qry1);
        //echo $qry1.'<br>';
    }
    
    //list_QB_Queue($oid,'q',$db);
    list_Log($oid,'q',$db);
}

function send_Customer_to_Accounting($oid,$jid,$jadd,$a,$db)
{
    $out='';
    //ob_end_flush();
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qry	= "
                SELECT
                    J.jid,J.jobid,ListID as JListID,
                    (select cid from cinfo where officeid=J.officeid and jobid=J.jobid) as cid,
                    (select ListID from cinfo where officeid=J.officeid and jobid=J.jobid) as CListID
                FROM
                    jobs AS J
                WHERE
                    J.officeid=".$oid."
                    AND J.jid=".$jid.";
            ";
            
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
	$nrow	= mssql_num_rows($res);
    
    //echo $qry.'<br>';
    
    if ($nrow==1)
    {
        if (!preg_match('/-/',$row['CListID'])) //Customer Add Action only occurs if cinfo ListID is not matched
        {
            $base_url='http://'.$_SERVER['SERVER_NAME'].'/qb/bhsoap/QB_Process_PID.php?qact='.$a.'&oid='.(int) $oid.'&pid[]='.$row['cid'];

            header('Location: '.$base_url);
        }
    }
}

function send_Customer_Info_by_CID($oid,$cid,$a)
{
    $pcnt=0;
    $qry	= "SELECT cid FROM cinfo WHERE cid=".$cid.";";
	$res	= mssql_query($qry);
	$nrow	= mssql_num_rows($res);
    
    if ($nrow > 0 and $cid!=0)
    {
        set_include_path(get_include_path() . PATH_SEPARATOR .'E:\www\htdocs\QB');
        include('../QB/bhsoap/QB_Support.php');
        
        $row = mssql_fetch_array($res);
        $sid=$_SESSION['securityid'];
        $pid[]=$row['cid'];
        $pcnt++;

        request_multi_process($pid,$a,$sid,$oid);
    }
    
    echo $pcnt;
}

function send_Payment_Info($oid,$cid,$a)
{
    $pcnt=0;
    
    $qry0	= "SELECT id as psid FROM constructiondates WHERE cid=".$cid." AND dtype=3;";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
    
    $qry1	= "SELECT id as psid FROM constructiondates WHERE cid=".$cid." AND dtype=3 AND TxnID!='0';";
	$res1	= mssql_query($qry1);
	$nrow1	= mssql_num_rows($res1);
    
    $qry2	= "SELECT id as psid FROM constructiondates WHERE cid=".$cid." AND dtype=3 AND TxnID='0';";
	$res2	= mssql_query($qry2);
	$nrow2	= mssql_num_rows($res2);
    
    if ($nrow2 > 0 and $cid!=0)
    {
        set_include_path(get_include_path() . PATH_SEPARATOR .'E:\www\htdocs\QB');
        include('../QB/bhsoap/QB_Support.php');
        
        $pid=array();
        while ($row2    = mssql_fetch_array($res2))
        {
            $pid[]=$row2['psid'];
            $pcnt++;
        }
        
        request_multi_process($pid,$a,$_SESSION['securityid'],$oid);
    }
    
    //echo $pcnt;
    echo $nrow0.' : '.$nrow1.' : '.$pcnt;
}

function send_Invoice_Info($oid,$cid,$a)
{
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    
    $jinfo=array('oid'=>$oid,'cid'=>$cid);
    
    $qryP	= "
            SELECT
                C1.cid,C1.jobid,J1.psched,J1.psched_perc
            FROM
                cinfo AS C1
            INNER JOIN
                jdetail as J1
            ON
                C1.jobid=J1.jobid
            WHERE
                C1.cid=".$cid."
                AND J1.jadd=0
            ;";
	$resP	= mssql_query($qryP);
	$rowP	= mssql_fetch_array($resP);
    
    $prior_ps_ar=build_prior_ps_array($rowP['psched'],$rowP['psched_perc']);
    
    $qry0	= "SELECT psid,psTxnID FROM payment_schedule WHERE cid=".$cid.";";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);

    if ($nrow0 > 0)
    {
        $pcnt=0;
        
        $qry1	= "SELECT psid FROM payment_schedule WHERE cid=".$cid." AND psTxnID!='0';";
        $res1	= mssql_query($qry1);
        $nrow1	= mssql_num_rows($res1);
        
        $qry2	= "SELECT psid FROM payment_schedule WHERE cid=".$cid." AND psTxnID='0';";
        $res2	= mssql_query($qry2);
        $nrow2	= mssql_num_rows($res2);
        
        if ($nrow2 > 0 and $cid!=0)
        {
            set_include_path(get_include_path() . PATH_SEPARATOR .'E:\www\htdocs\QB');
            include('../QB/bhsoap/QB_Support.php');
            
            $pid=array();
            while ($row2 = mssql_fetch_array($res2))
            {
                $pid[]=$row2['psid'];
                $pcnt++;
            }
            
            request_multi_process($pid,$a,$_SESSION['securityid'],$oid);
        }
        
        //echo $pcnt;
        
        echo $nrow0.' : '.$nrow1.' : '.$pcnt;
    }
    else
    {
        insert_payment_schedule($jinfo,$prior_ps_ar);
        send_Invoice_Info($oid,$cid,$a);
    }
}

function build_prior_ps_array($p1,$p2)
{
    $out    =array();
    $phs_ar =array();
    $p1p=explode(',',$p1);
    $p2a=explode(',',$p2);
    
    $qry = "SELECT phsid,phscode FROM phasebase;";
    $res = mssql_query($qry);
    
    while ($row = mssql_fetch_array($res))
    {
        $phs_ar[$row['phscode']]=$row['phsid'];
    }
    
    foreach ($p1p as $n1=>$v1)
    {
        $out[]=array('phsid'=>$phs_ar[$v1],'amt'=>$p2a[$n1]);
    }
    
    return $out;
}

function insert_payment_schedule($va,$s)
{
    foreach ($s as $nn => $vv)
	{
		if ($vv['amt'] > 0)
		{
			$qry1	= "INSERT INTO payment_schedule (oid,cid,phsid,amount,sid) VALUES (".$va['oid'].",".$va['cid'].",'".$vv['phsid']."',cast('".$vv['amt']."' as money),".$_SESSION['securityid'].");";
			$res1 	= mssql_query($qry1);
			//echo $qry.'<br>';
		}
	}
}

function send_Job_to_Accounting($oid,$jid,$jadd,$a,$db)
{
    $jcnt=0;
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qry	= "
                SELECT
                    J.jid,J.officeid as oid,J.jobid,ListID as JListID,
                    (select cid from cinfo where officeid=J.officeid and jobid=J.jobid) as cid,
                    (select ListID from cinfo where officeid=J.officeid and jobid=J.jobid) as CListID
                FROM
                    jobs AS J
                WHERE
                    J.officeid=".$oid."
                    AND J.jid=".$jid.";
            ";
    
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
	$nrow	= mssql_num_rows($res);

    if ($nrow > 0 and trim($row['JListID'])==='0')
    {
        set_include_path(get_include_path() . PATH_SEPARATOR .'E:\www\htdocs\QB');
        include('../QB/bhsoap/QB_Support.php');
        
        $a='EstimateAdd';
        $pid=array($row['jobid']);
        $sid=$_SESSION['securityid'];
        $oid=$row['oid'];
        
        request_multi_process($pid,$a,$sid,$oid);
        $jcnt++;
    }
    
    if ($jcnt > 0)
    {
        set_JMS_Job_Status($oid,$jid,'0',0,4,$db);
        return true;
    }
    else
    {
        return false;
    }
}

function store_jobcost($oid,$jobid,$p)
{
    include('job_support_func.php');
    
    if (proc_prior_jobcost($oid,$jobid,$p))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function get_CustomerQBStatus($cid,$db)
{
    $nrow0=0;
    
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qry0 = "select * from [quickbooks_queue] where qb_action='CustomerAdd' and ident=". (int) $cid;
	$res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
    
    return $nrow0;
}

function get_CustomerLifeCycle($oid,$cid,$jdb,$qdb)
{
    $tranid=time().".".$cid.".".$_SESSION['securityid'];
	$sdate  = '';
	$udate  = '';
    $eadate = '';
	$eudate = '';
    $cadate = '';
	$cudate = '';
    $cdate  = '';
	$ddate  = '';
	$fdate  = '';
	$fudate = '';
	$fdadate= '';
    
    $qry = "SELECT C.* FROM cinfo AS C WHERE C.officeid=".$oid." AND C.cid=".$cid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
    
    $qryA = "SELECT officeid,finan_off,finan_from,fsenable,fscustomer,enquickbooks FROM offices WHERE officeid=".$row['officeid'].";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
    
    $qryB = "SELECT estid,officeid,cid,esttype,added,updated FROM est WHERE officeid=".$row['officeid']." and ccid=".$row['cid'].";";
	$resB = mssql_query($qryB);
	//$nrowB= mssql_num_rows($resB);
    
    while ($rowB = mssql_fetch_array($resB))
    {
        $estinfo[]=$rowB;
    }

	$qryC = "SELECT * FROM offices WHERE officeid='".$oid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$qryD = "SELECT mas_div,filestoreaccess FROM security WHERE securityid='".$row['securityid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);
	
	if ($row['estid']!=0)
	{
		$qryE = "SELECT estid,added,updated FROM est WHERE officeid='".$oid."' AND estid='".$row['estid']."';";
		$resE = mssql_query($qryE);
		$rowE = mssql_fetch_array($resE);
		
		$eadate= date("m/d/Y", strtotime($rowE['added']));
		$eudate= date("m/d/Y", strtotime($rowE['updated']));
	}
	
	if ($row['jobid']!='0')
	{
		//$qryF = "SELECT jobid,added,updated FROM jdetail WHERE officeid='".$oid."' AND jobid='".$row['jobid']."';";
        $qryF = "SELECT jobid,added,updated FROM jobs WHERE officeid=".$oid." AND jobid='".$row['jobid']."';";
		$resF = mssql_query($qryF);
		$rowF = mssql_fetch_array($resF);
		
		$cadate= date("m/d/Y", strtotime($rowF['added']));
		$cudate= date("m/d/Y", strtotime($rowF['updated']));
	}
	
	if ($row['njobid']!='0')
	{
		$qryG = "SELECT J1.njobid,J1.added,J2.digdate,J2.ListID,J2.JobListID FROM jdetail AS J1 inner join jobs as J2 on J1.jobid=J2.jobid WHERE J1.officeid=".$oid." AND J1.njobid='".$row['njobid']."';";
		$resG = mssql_query($qryG);
		$rowG = mssql_fetch_array($resG);
		
		$cdate=date("m/d/Y", strtotime($rowG['added']));
		$ddate=date("m/d/Y", strtotime($rowG['digdate']));	
	}

	if (isset($row['added']) and (strtotime($row['added']) >= strtotime('1/1/1990')))
	{
		$sdate = date("m/d/Y", strtotime($row['added']));
	}

	if (isset($row['updated']) and (strtotime($row['updated']) >= strtotime('1/1/1990')))
	{
		$udate = date("m/d/Y", strtotime($row['updated']));
	}

	$qryS = "SELECT securityid,filestoreaccess,constructdateaccess FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$resS = mssql_query($qryS);
	$rowS = mssql_fetch_array($resS);
	
	$qrySa = "select isnull(count(F.docid),0) as tfiles from jest..jestFileStore AS F inner join jest..jestFileStoreCategory AS C on F.fscid=C.fscid where F.cid=".$row['cid']." and F.active=1 and C.slevel <=".$rowS['filestoreaccess'].";";
	$resSa = mssql_query($qrySa);
	$rowSa = mssql_fetch_array($resSa);
    
    $qryPSa = "select count(psid) as InvTot from payment_schedule where cid=".$row['cid'].";";
    $resPSa = mssql_query($qryPSa);
	$rowPSa = mssql_fetch_array($resPSa);
    
    $qryPSb = "select count(psid) as InvPrc from payment_schedule where cid=".$row['cid']." and psTxnID!='0'";
    $resPSb = mssql_query($qryPSb);
	$rowPSb = mssql_fetch_array($resPSb);
    
    $qryCDa = "select count(id) as PmtTot from constructiondates where cid=".$row['cid']." and jobid='".$row['jobid']."' and dtype=3";
    $resCDa = mssql_query($qryCDa);
	$rowCDa = mssql_fetch_array($resCDa);
    
    $qryCDb = "select count(id) as PmtPrc from constructiondates where cid=".$row['cid']." and jobid='".$row['jobid']."' and dtype=3 and TxnID!='0'";
    $resCDb = mssql_query($qryCDb);
	$rowCDb = mssql_fetch_array($resCDb);
    
    $pjstore=get_Prior_Job_Store($oid,$row['jobid'],0,$jdb);
    
    echo "						<table align=\"center\" width=\"100%\">\n";
    echo "	   						<tr>\n";
    echo "      						<td colspan=\"4\" class=\"ltgray_und\" align=\"left\"><b>Lifecycle Information and Control</b></td>\n";
    echo "      						<td class=\"ltgray_und\" align=\"right\">\n";    
    echo "      						</td>\n";
    echo "      						<td class=\"ltgray_und\" align=\"right\"><img class=\"JMStooltip\" src=\"images/help.png\" title=\"".date(time())."\"></td>\n";
    echo "   						</tr>\n";

    if ($_SESSION['llev']!=0 && $row['cid']!=0)
    {
        $uid	=md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];
        
        echo "	   					<tr>\n";
        echo "      						<td class=\"gray\" align=\"left\"></td>\n";
        echo "      						<td class=\"gray\" align=\"left\"></td>\n";
        echo "      						<td class=\"gray\" align=\"center\"><b>Added</b></td>\n";
        echo "      						<td class=\"gray\" align=\"center\"><b>Updated</b></td>\n";
        echo "      						<td class=\"gray\" align=\"center\"><b>View</b></td>\n";
        echo "      						<td class=\"gray\" align=\"right\">\n";
        
        if (isset($rowA['enquickbooks']) and $rowA['enquickbooks']==1)
        {
            //echo '<b>QB</b> ';
            
            if ($_SESSION['jlev'] >= 9)
            {
                echo "         				            <form method=\"post\">\n";
                echo "						                <input type=\"hidden\" name=\"action\" value=\"accountingsystem\">\n";
                echo "						                <input type=\"hidden\" name=\"call\" value=\"list_Queues\">\n";
                echo "                                      <input class=\"transnb JMStooltip\" type=\"image\" src=\"images/application_view_columns.png\" title=\"Accounting Status Panel\">\n";
                echo "         				             </form>\n";
            }
            /*
            echo "					                <table>\n";
            echo "                                      <tr>\n";
            echo "      						            <td align=\"center\" width=\"20px\"><div id=\"update_status_JobPackageXfer\"></div></td>\n";
            echo "                                          <td align=\"center\" width=\"25px\">\n";
            
            echo "      						<b>QB</b>\n";
            
            echo "                                          </td>\n";
            echo "                                          <td align=\"center\" width=\"25px\">\n";
    
            if ($_SESSION['jlev'] >= 9)
            {
                echo "                              <a href=\"#\"><img class=\"JMStooltip\" id=\"send_JobPackage\" src=\"images/folder_go.png\" title=\"Send Job Package to Quickbooks\"></a>\n";
            }
            
            echo "                                          </td>";
            echo "                                      </tr>\n";
            echo "                                  </table>\n";
            */
        }
        
        echo "      						</td>\n";
        echo "   					</tr>\n";
        echo "	   					<tr class=\"even\">\n";
        echo "      						<td align=\"right\" width=\"90\"><b>Lead</b></td>\n";
        echo "      						<td align=\"left\" width=\"100\">".$row['cid']."</td>\n";
        echo "      						<td align=\"center\">".$sdate."</td>\n";
        echo "      						<td align=\"center\">".$udate."</td>\n";
        echo "      						<td align=\"center\">\n";
        
        if ($rowA['finan_off']==0)
        {
            echo "                        <form method=\"POST\">\n";
            echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
            echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
            echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
            echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
            echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
            echo "                        </form>\n";
        }
        
        echo "								</td>\n";
        echo "      						<td align=\"center\">\n";
        echo "								</td>\n";
        echo "   						</tr>\n";
    }

    if ($_SESSION['elev']!=0 && count($estinfo) > 0)
    {
        //while ($rowB = mssql_fetch_array($resB))
        foreach ($estinfo as $ek=>$ev)
        {
            echo "	   					<tr class=\"even\">\n";
            echo "      						<td align=\"right\" width=\"90\"><b>\n";
            
            //print_r($rowB);
            
            if ($ev['esttype']=='E')
            {
                echo 'Estimate';
            }
            else
            {
                echo 'Quote';
            }
            
            echo "</b></td>\n";
            echo "      						<td align=\"left\">\n";
            echo $ev['estid'];
            echo "								</td>\n";
            echo "      						<td align=\"center\">".date("m/d/Y", strtotime($ev['added']))."</td>\n";
            echo "      						<td align=\"center\">\n";
            
            if (empty($ev['updated']) || strtotime($ev['updated']) < strtotime('1/1/2000'))
            {
                echo "<img src=\"images/pixel.gif\">\n";
            }
            else
            {
                echo date("m/d/Y", strtotime($ev['updated']));
            }
            
            echo "								</td>\n";
            echo "      						<td align=\"center\">\n";
            
            if ($rowA['finan_off']==0)
            {
                if ($ev['esttype']=='E')
                {
                    if ($row['jobid']!='0')
                    {
                        echo "                          <img src=\"images/action_delete.gif\" title=\"Contract Created. View Contract.\">\n";
                    }
                    else
                    {
                        echo "                        <form name=\"viewest\" method=\"POST\">\n";
                        echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
                        echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
                        echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$ev['estid']."\">\n";
                        echo "                           <input type=\"hidden\" name=\"esttype\" value=\"".$ev['esttype']."\">\n";
                        echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Estimate\">\n";
                        echo "						</form>\n";
                    }
                }
                else
                {
                    echo "                        <form name=\"viewest\" method=\"POST\">\n";
                    echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
                    echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
                    echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$ev['estid']."\">\n";
                    echo "                           <input type=\"hidden\" name=\"esttype\" value=\"".$ev['esttype']."\">\n";
                    echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Quote\">\n";
                    echo "						</form>\n";
                }
            }
            
            echo "								</td>\n";
            echo "      						<td align=\"center\">\n";
            echo "								</td>\n";
            echo "   						</tr>\n";
        }
    }
    
    if ($_SESSION['clev']!=0 && $row['jobid']!='0')
    {
        echo "	   					<tr class=\"even\">\n";
        echo "      						<td align=\"right\" width=\"90\"><b>Contract</b></td>\n";
        echo "      						<td align=\"left\" width=\"100\">".$row['jobid']."</td>\n";
        echo "      						<td align=\"center\">".$cadate."</td>\n";
        echo "      						<td align=\"center\">".$cudate."</td>\n";
        echo "      						<td align=\"center\">\n";
        
        if ($rowA['finan_off']==0)
        {
            echo "                        <form method=\"POST\">\n";
            echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
            echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
            echo "                           <input type=\"hidden\" name=\"jobid\" id=\"usr_jobid\" value=\"".$row['jobid']."\">\n";
            echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
            echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Contract\">\n";
            //echo "                           <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View\" title=\"Click to View this Contract\">\n";
            echo "                        </form>\n";
        }
        
        echo "								</td>\n";
        echo "      						<td align=\"center\">\n";
        
        if (isset($rowA['enquickbooks']) and $rowA['enquickbooks']==1)
        {
            echo "					                <table>\n";
            echo "                                      <tr>\n";
            echo "      						            <td align=\"center\" width=\"20px\"><div id=\"update_status_Customer\"></div></td>\n";
            echo "                                          <td align=\"center\" width=\"40px\">\n";
            echo "                                              <div id=\"CustomerCount\">\n";
            
            if (isset($row['ListID']) and $row['ListID']!=='0')
            {
                //echo $custstat;
                echo "<img src=\"images/action_check.gif\" title=\"Customer Information Processed by Quickbooks: ".$row['ListID']."\">";
            }
            else
            {
                //echo $custstat;
                echo "<img src=\"images/action_delete.gif\" title=\"No Customer Information in Quickbooks\">";
            }
            
            echo "                                              </div>\n";
            echo "                                          </td>\n";
            echo "                                          <td align=\"center\" width=\"25px\">\n";
    
            if ($_SESSION['jlev'] >= 9)
            {
                echo "                                          <a href=\"#\"><img class=\"JMStooltip\" id=\"send_CustomerInfo\" src=\"images/user_go.png\" title=\"Send Customer Info to Quickbooks\"></a>\n";
            }
            
            echo "                                          </td>";
            echo "                                      </tr>\n";
            echo "                                  </table>\n";
        }

        echo "								</td>\n";
        echo "   						</tr>\n";
    }
    
    if (isset($rowA['enquickbooks']) and $rowA['enquickbooks']==1)
    {
        echo "	<tr class=\"even\">\n";
        echo "		<td align=\"right\" width=\"90\"><b>Invoices</b></td>\n";
        echo "      <td align=\"left\" width=\"100\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      <td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      <td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      <td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "		<td align=\"center\">\n";
        echo "          <table>\n";
        echo "              <tr>\n";
        echo "                  <td align=\"center\" width=\"20px\"><div id=\"update_status_Invoices\"></div></td>\n";
        echo "                  <td align=\"center\" width=\"40px\">\n";
        echo "                      <div id=\"InvoiceCount\" title=\"Invoices in JMS : Invoices in QB : Invoices Processed\">\n";
        
        echo $rowPSa['InvTot'].' : '.$rowPSb['InvPrc'].' : 0';
        
        echo "                      </div>\n";
        echo "                  </td>\n";
        echo "                  <td align=\"center\" width=\"25px\">\n";
    
        if ($_SESSION['jlev'] >= 9)
        {
            echo "                      <a href=\"#\"><img class=\"JMStooltip\" id=\"send_InvoiceInfo\" src=\"images/layout_add.png\" title=\"Send Invoice Info to Quickbooks\"></a>";
        }
        
        echo "                  </td>";
        echo "              </tr>\n";
        echo "          </table>\n";
        echo "		</td>\n";
        echo "	</tr>\n";
    }

    if ($_SESSION['jlev']!=0 and $row['njobid']!='0')
    {
        //$destidret  =disp_mas_div_jobid($rowD['mas_div'],$row['njobid']);
        echo "	   					<tr class=\"even\">\n";
        echo "      						<td align=\"right\" width=\"90\"><b>Job</b></td>\n";
        echo "      						<td align=\"left\" width=\"100\">".$row['njobid']."</td>\n";
        echo "      						<td align=\"center\">".$cadate."</td>\n";
        echo "      						<td align=\"center\">".$cudate."</td>\n";
        echo "      						<td align=\"center\">\n";
        
        if ($rowA['finan_off']==0)
        {
            echo "                        <form method=\"POST\">\n";
            echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
            echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
            echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
            echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
            echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Job\">\n";
            echo "                        </form>\n";
        }
        
        echo "								</td>\n";
        echo "      						<td align=\"center\">\n";
        
        if (isset($rowA['enquickbooks']) and $rowA['enquickbooks']==1)
        {
            echo "					                <table>\n";
            echo "                                      <tr>\n";
            echo "      						            <td align=\"center\" width=\"20px\"><div id=\"update_status_ContractListID\"></div></td>\n";
            echo "                                          <td align=\"center\" width=\"40px\">\n";
            echo "                                              <div id=\"ContractCount\">\n";
            
                if (isset($rowG['ListID']) and $rowG['ListID']!=='0')
                {
                    echo "<img src=\"images/action_check.gif\" title=\"Contract Information Processed by Quickbooks: ".$rowG['ListID']."\">";
                }
                else
                {
                    if ($pjstore[0] > 0)
                    {
                        echo "<img src=\"images/arrow_right.png\" title=\"Contract Information ready to send to Quickbooks\">";
                    }
                    else
                    {
                        echo "<img src=\"images/action_remove.gif\" title=\"No Contract Information in Quickbooks\">";
                    }
                }
            
            echo "                                              </div>\n";
            echo "                                          </td>\n";
            echo "                                          <td align=\"center\" width=\"25px\">\n";
    
            if ($_SESSION['jlev'] >= 9 and $_SESSION['securityid']==26)
            {
                echo "                                          <a href=\"#\"><img class=\"JMStooltip\" id=\"send_ContractInfo\" src=\"images/application_go.png\" title=\"Send Contract Info to Quickbooks\"></a>\n";
            }
            
            echo "                                          </td>";
            echo "                                      </tr>\n";
            echo "                                  </table>\n";
        }
        
        /*
        if (isset($rowA['enquickbooks']) and $rowA['enquickbooks']==999)
        {
            echo "					                <table>\n";
            echo "                                      <tr>\n";
            echo "      						            <td align=\"center\" width=\"20px\"><div id=\"update_status_JobListID\"></div></td>\n";
            echo "                                          <td align=\"center\" width=\"40px\">\n";
            echo "                                              <div id=\"ContractListID\">\n";
            
            if (isset($rowG['JobListID']) and $rowG['JobListID']!=='0')
            {
                echo "<img src=\"images/action_check.gif\" title=\"Job Information Processed by Quickbooks: ".$rowG['JobListID']."\">";
            }
            else
            {
                echo "<img src=\"images/action_remove.gif\" title=\"No Job Information in Quickbooks\">";
            }
            
            echo "                                              </div>\n";
            echo "                                          </td>\n";
            echo "                                          <td align=\"center\" width=\"25px\">\n";
    
            if ($_SESSION['jlev'] >= 9)
            {
                echo "                                          <a href=\"#\"><img class=\"JMStooltip\" id=\"send_JobInfo\" src=\"images/application_go.png\" title=\"Send Job Info to Quickbooks\"></a>\n";
            }
            
            echo "                                          </td>";
            echo "                                      </tr>\n";
            echo "                                  </table>\n";
        }
        */
        
        echo "								</td>\n";
        echo "   						</tr>\n";
    }
    
    if ($_SESSION['jlev']!=0 && $row['njobid']!='0' && (isset($ddate) and valid_date($ddate) and strtotime($ddate) >= strtotime('1/1/2000')))
    {
        echo "	   						<tr class=\"even\">\n";
        echo "      						<td align=\"right\" width=\"90\"><b>Dig Date</b></td>\n";
        echo "      						<td align=\"left\" width=\"100\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      						<td align=\"center\">".$ddate."</td>\n";
        echo "      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      						<td align=\"center\"></td>\n";
        echo "      						<td align=\"center\"></td>\n";
        echo "   						</tr>\n";
    }
    
    if (isset($rowA['enquickbooks']) and $rowA['enquickbooks']==1)
    {
        echo "	<tr class=\"even\">\n";
        echo "		<td align=\"right\" width=\"90\"><b>Payments</b></td>\n";
        echo "      <td align=\"left\" width=\"100\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      <td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      <td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      <td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "		<td align=\"center\">\n";
        echo "          <table>\n";
        echo "              <tr>\n";
        echo "                  <td align=\"center\" width=\"20px\"><div id=\"update_status_Payments\"></div></td>\n";
        echo "                  <td align=\"center\" width=\"40px\">\n";
        echo "                      <div id=\"PaymentCount\" title=\"Payments in JMS : Payments in QB : Payments Processed\">\n";
        
        echo $rowCDa['PmtTot'].' : '.$rowCDb['PmtPrc'].' : 0';
        
        echo "                      </div>\n";
        echo "                  </td>\n";
        echo "                  <td align=\"center\" width=\"25px\">\n";
    
        if ($_SESSION['jlev'] >= 9)
        {
            echo "                      <a href=\"#\"><img class=\"JMStooltip\" id=\"send_PaymentInfo\" src=\"images/money_add.png\" title=\"Send Payment Info to Quickbooks\"></a>";
        }
        
        echo "                  </td>";
        echo "              </tr>\n";
        echo "          </table>\n";
        echo "		</td>\n";
        echo "	</tr>\n";
    }

    if ((isset($rowA['fscustomer']) and $rowA['fscustomer'] == 1) and (isset($rowS['filestoreaccess']) and $rowS['filestoreaccess'] >= 1))
    {
        echo "	   						<tr class=\"even\">\n";
        echo "      						<td align=\"right\" width=\"90\"><b>Files</b></td>\n";
        echo "      						<td align=\"left\" width=\"100\">".$rowSa['tfiles']."</td>\n";
        echo "      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "      						<td align=\"center\">\n";
        echo "									<form method=\"POST\">\n";
        echo "										<input type=\"hidden\" name=\"action\" value=\"file\">\n";
        echo "										<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
        echo "										<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
        echo "										<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Files\">\n";
        echo "									</form>\n";
        echo "								</td>\n";
        echo "      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "   						</tr>\n";
    }

    echo "						</table>\n";
}

function resend_Job_to_Accounting($oid,$jobid,$jadd,$db)
{
    $out='';
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    //echo '<pre>';
    //print_r($_REQUEST);
    //print_r($db);
    //echo '</pre>';
    
    $qry	= "SELECT * FROM jobs WHERE officeid=".$oid." AND jobid='".$jobid."';";
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
	$nrow	= mssql_num_rows($res);
    
    if ($nrow==1)
    {
        if ($row['acc_status']==2)
        {
            $qry0	= "UPDATE jobs SET acc_status=4,acc_released=getdate() WHERE jid=".$row['jid'].";";
            $res0	= mssql_query($qry0);
        }
        elseif ($row['acc_status']==3)
        {
            echo "Job Previously Released";
        }
    }
    else
    {
        echo "Job Release Error";
    }
    
    //echo $qry0;
    
    return $out;
}

function release_Job($oid,$jobid,$jadd,$db)
{
    $out=0;
    
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    //echo '<pre>';
    //print_r($_REQUEST);
    //print_r($db);
    //echo '</pre>';
    
    $qry	= "SELECT * FROM jobs WHERE officeid=".$oid." AND jobid='".$jobid."';";
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
	$nrow	= mssql_num_rows($res);
    
    if ($nrow==1)
    {
        include('../job_support_func.php');
        
        if ($row['acc_status']==0)
        {
            $pcnt=get_Prior_Job_Store($oid,$jobid,0,$db);
            
            if ($pcnt[0]!=0)
            {
                $qry0 = "UPDATE jobs SET acc_status=1 WHERE officeid=".$oid." AND jid=".$row['jid'].";";
                $res0 = mssql_query($qry0);

                $out=1;
            }
            else
            {
                $icnt=proc_prior_jobcost($oid,$jobid,$jadd,false);
                
                $out=2;
            }
        }
        elseif ($row['acc_status']==1 or $row['acc_status']==2)
        {
            echo 'Job Previously Released.<br>';
        }
        
        if ($_SESSION['jlev'] >=9)
        {
            echo "<form method=\"post\">\n";
            echo "<input type=\"hidden\" name=\"action\" value=\"accountingsystem\">\n";
            echo "<input type=\"hidden\" name=\"call\" value=\"list_Queues\">\n";
            echo "<input class=\"buttondkgrypnl\" type=\"submit\" value=\"Accounting\" title=\"Accounting System\">\n";
            echo "</form>\n";
        }
    }
    else
    {
        //echo 'Job not found ('.__LINE__.')';
        $out=65535;
    }
    
    return $out;
}

function set_JMS_Job_Status($oid,$jid,$jobid,$jadd,$jstat,$db)
{
    $out='';
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    //echo '<pre>';
    //print_r($_REQUEST);
    //print_r($db);
    //echo '</pre>';
    
    if (isset($jid) and $jid!=0)
    {
        $qry	= "SELECT * FROM jobs WHERE officeid=".$oid." AND jid=".$jid.";";
    }
    else
    {
        $qry	= "SELECT * FROM jobs WHERE officeid=".$oid." AND jobid='".$jobid."';";
    }
    
    //echo $qry.'<br>';
    
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
	$nrow	= mssql_num_rows($res);
    
    if ($nrow==1)
    {
        $qry0	= "UPDATE jobs SET acc_status=".$jstat." WHERE officeid=".$oid." AND jid=".$row['jid'].";";
        $res0	= mssql_query($qry0);
    }    
    
    //return true;
}

function revert_Job_to_JMS_Released($oid,$qid,$jms_db,$qbs_db)
{
    $out='';
    mssql_connect($qbs_db['hostname'],$qbs_db['username'],$qbs_db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($qbs_db['dbname']) or die("Table unavailable");
    
    //echo '<pre>';
    //print_r($_REQUEST);
    //print_r($db);
    //echo '</pre>';
    
    $qry	= "SELECT * FROM quickbooks_queue WHERE quickbooks_queue_id='".$qid."';";
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
	$nrow	= mssql_num_rows($res);
    
    if ($nrow==1)
    {
        if ($row['qb_status']=='q' and $row['qb_action']=='EstimateAdd')
        {
            $qry0	= "DELETE FROM quickbooks_queue WHERE quickbooks_queue_id='".$qid."';";
            $res0	= mssql_query($qry0);
            
            set_JMS_Job_Status($oid,0,$row['ident'],0,2,$jms_db);
            list_QB_Queue($oid,'q',$qbs_db);
        }
    }
    else
    {
        echo 'Job not found ('. __LINE__ .')';
    }
}

function get_Job_Status($oid,$jobid,$db)
{
    $out=65535;
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qry    = "SELECT acc_status FROM jobs where officeid=".$oid." and jobid='".$jobid."';";
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
    $nrow   = mssql_num_rows($res);
    
    if ($nrow > 0)
    {
        $out=$row['acc_status'];
    }
    
    $ps_store=get_Prior_Job_Store($oid,$jobid,0,$db);
    
    if ($ps_store[0] > 0)
    {
        return (int) $out;
    }
    else
    {
        return 65535;
    }
}

function list_JMS_Released($oid,$yr,$db)
{
    $out='';
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    /*
    echo '<pre>';
    print_r($_REQUEST);
    echo '</pre>';
    
    
    ACC Status Codes
    0 - Unreleased
    1 - Released Unsent
    2 - Released Reverted
    3 - Reserved
    4 - Transmitted to Accounting
    5 - Processed
    6 - Reserved
    7 - Reserved
    8 - Reserved
    9 - Closed
    */
    
    $qry    = "SELECT officeid,name FROM offices as O where officeid=".$oid.";";
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
    
    $qry0	= "
                SELECT
                    J1.jid,J1.custid,J1.jobid,J1.njobid,C1.cid,J1.officeid,
                    J1.EditSequence,J1.added,J1.acc_released,
                    J1.acc_transmitted,J1.acc_status,
                    C1.ListID as cid_status,
                    J1.ListID as con_status,
                    J1.JobListID as job_status,
                    C1.clname,C1.cfname
                    ,(select fname + ' ' + lname from security where securityid=C1.securityid) as SalesRep
                    ,(select count(id) from est where cid=C1.cid) as EstTot
                    ,(select count(id) from est where cid=C1.cid and ListID!='0') as EstPrc
                    ,(select count(psid) from payment_schedule where cid=C1.cid) as InvTot
                    ,(select count(psid) from payment_schedule where cid=C1.cid and psTxnID!='0') as InvPrc
                    ,(select count(id) from constructiondates where cid=C1.cid and jobid=J1.jobid and dtype=3) as PmtTot
                    ,(select count(id) from constructiondates where cid=C1.cid and jobid=J1.jobid and dtype=3 and TxnID!='0') as PmtPrc
                    --,(select count(jid) from jdetail where officeid=J1.officeid and jobid=J1.jobid and jadd > 0) as AddTot
                    --,(select count(jid) from jdetail where officeid=J1.officeid and jobid=J1.jobid and jadd > 0 and ListID!='0') as AddPrc
                FROM
                    jobs J1
                INNER JOIN
                    cinfo C1
                ON
                    J1.custid=C1.cid
                WHERE
                    C1.officeid=".$oid."
                    AND (J1.acc_status != 0 OR C1.ListID!='0')";
                    
    
    if (isset($yr) and $yr!=0)
    {
        $qry0	.= "
                        and datepart(yyyy,J1.added) = ".$yr."
                    ";
    }
    
    $qry0	.= "
                    --and J1.acc_released >='1/1/2010'
                    --and J1.ListID!='0'
                ORDER BY
                    J1.njobid,
                    C1.clname;
                ";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
	
    $colcnt=13;
    
    //print_r(PDO::getAvailableDrivers());
    echo "<table width=\"915px\">\n";
    echo "	<tr>\n";
    echo "		<td align=\"left\" colspan=\"".$colcnt."\">\n";
    echo "          <table class=\"outer\" width=\"100%\">\n";
    echo "          	<tr>\n";
    echo "            		<td align=\"left\"><b>JMS Customer Status</b></td>\n";
    echo "          		<td align=\"right\"><b>Records Found " . $nrow0 . "</b></td>\n";
    echo "          	</tr>\n";
    echo "          </table>\n";
    echo "		</td>\n";
    echo "	</tr>\n";
    echo "	<tr>\n";
    echo "		<td align=\"right\"></td>\n";
    echo "		<td align=\"left\"><b>Customer Last</b></td>\n";
    echo "		<td align=\"left\"><b>Customer First</b></td>\n";
    echo "		<td align=\"left\"><b>SalesRep</b></td>\n";
    echo "		<td align=\"center\"><b>Contract #</b></td>\n";
    echo "		<td align=\"center\"><b>Job #</b></td>\n";
    //echo "		<td align=\"center\"><b>Transmitted</b></td>\n";
    echo "		<td align=\"center\"><div class=\"JMStooltip\" title=\"Customer Information Transfer Status\"><b>Cst</b></div></td>\n";
    echo "		<td align=\"center\"><div class=\"JMStooltip\" title=\"Invoices Stored in JMS : Invoices Processed to Quickbooks\"><b>Inv</b></div></td>\n";
    echo "		<td align=\"center\"><div class=\"JMStooltip\" title=\"Payments Stored in JMS : Payments Processed to Quickbooks\"><b>Pay</b></div></td>\n";
    //echo "		<td align=\"center\"><div class=\"JMStooltip\" title=\"Contract Information Transfer Status\"><b>Con</b></div></td>\n";
    echo "		<td align=\"center\"><div class=\"JMStooltip\" title=\"Job Information Transfer Status\"><b>Job</b></div></td>\n";
    echo "		<td align=\"center\"><div class=\"JMStooltip\" title=\"Addn Information Transfer Status\"><b>Add</b></div></td>\n";
    //echo "		<td align=\"right\"><b>Release Control</b></td>\n";
    echo "		<td align=\"right\"><b></b></td>\n";
    echo "	</tr>\n";
    
    //echo '<pre>';
    $ccnt=0;
    while ($row0 = mssql_fetch_array($res0))
    {
        $ccnt++;
        if ($ccnt%2)
        {
            $trbg = 'odd';
        }
        else
        {
            $trbg = 'even';
        }
        
        $uid = md5(session_id().time().$row0['cid']).".".$_SESSION['securityid'];
        
        echo "	<tr class=\"".$trbg."\">\n";
        echo "		<td align=\"right\">".$ccnt."</td>\n";
        echo "		<td align=\"left\">\n";
        
        echo $row0['clname'];

        echo "      </td>\n";
        echo "		<td align=\"left\">".$row0['cfname']."</td>\n";
        echo "		<td align=\"left\">".$row0['SalesRep']."</td>\n";
        echo "		<td align=\"center\">\n";
        
        echo $row0['jobid'];
        
        echo "      </td>\n";
        echo "		<td align=\"center\">\n";
        
        echo $row0['njobid'];
        
        echo "      </td>\n";
        echo "		<td align=\"center\" width=\"25px\">\n";
        echo "          <div id=\"cid_status\">\n";
        
        if (isset($row0['cid_status']) and $row0['cid_status']!=='0')
        {
            echo "<img src=\"images/action_check.gif\" title=\"Customer Information Processed by Quickbooks\">";
        }
        else
        {
            echo "<img src=\"images/action_delete.gif\">";
        }

        echo "          </div>\n";
        echo "      </td>\n";
        echo "		<td align=\"center\" width=\"25px\">\n";
        echo "          <div id=\"inv_status\">\n";
        
        echo '<span id=\"InvTot_status\">'.$row0['InvTot'].'</span> : <span id=\"InvPrc_status\">'.$row0['InvPrc'].'</span>';
        
        echo "          </div>\n";
        echo "      </td>\n";
        echo "		<td align=\"center\" width=\"25px\">\n";
        echo "          <div id=\"pmt_status\">\n";
        
        echo '<span id=\"PmtTot_status\">'.$row0['PmtTot'].'</span> : <span id=\"PmtPrc_status\">'.$row0['PmtPrc'].'</span>';
        
        echo "          </div>\n";
        echo "      </td>\n";
        /*
        echo "		<td align=\"center\" width=\"25px\">\n";
        echo "          <div id=\"con_status\">\n";
        
        if (isset($row0['con_status']) and $row0['con_status']!=='0')
        {
            echo "          <img class=\"JMStooltip\" src=\"images/action_check.gif\" title=\"Contract Info Sent\">\n";
        }
        else
        {
            echo "          <img class=\"JMStooltip\" src=\"images/action_remove.gif\" title=\"Contract Info not Sent\">\n";
        }
        
        echo "          </div>\n";
        echo "      </td>\n";
        */
        echo "		<td align=\"center\" width=\"25px\">\n";
        echo "          <div id=\"job_status\">\n";
        
        if (isset($row0['job_status']) and $row0['job_status']!=='0')
        {
            echo "          <img class=\"JMStooltip\" src=\"images/action_check.gif\" title=\"Job Sent\">\n";
        }
        else
        {
            $pjstore=get_Prior_Job_Store($row0['officeid'],$row0['jobid'],0,$db);
            
            if ($pjstore[0] > 0)
            {
                echo "          <img class=\"JMStooltip\" src=\"images/arrow_right.png\" title=\"Contract Info ready to be Sent\">\n";
            }
            else
            {
                echo "          <img class=\"JMStooltip\" src=\"images/action_remove.gif\" title=\"Contract Info not ready to be sent\">\n";
            }
        }
        
        echo "          </div>\n";
        echo "      </td>\n";
        echo "		<td align=\"center\" width=\"20px\">\n";
        echo "          <div id=\"addn_status\">\n";
        
        /*
        if (isset($row0['addn_status']) and preg_match('/-/i',$row0['addn_status']))
        {
            //echo 'Released';
            echo "<img class=\"JMStooltip\" src=\"images/action_check.gif\">";
        }
        else
        {
            echo "<img class=\"JMStooltip\" src=\"images/action_delete.gif\">";
        }
        */
        
        echo "          </div>\n";
        echo "      </td>\n";
        echo "      <td align=\"right\">\n";
        
        /*
        echo "          <table class=\"acc_control_box\" width=\"70px\">\n";
        echo "              <tr>\n";
        echo "		            <td class=\"proc_status_all\" align=\"center\" width=\"20px\">\n";
        echo "                  </td>\n";
        echo "		            <td class=\"proc_status_del\" align=\"center\" width=\"20px\">\n";
        
        if ($row0['acc_status'] == 1 or $row0['acc_status'] == 2)
        {
            echo "          <div>\n";
            echo "              <input type=\"hidden\" class=\"usr_jid\" value=\"".$row0['jid']."\">\n";
            echo "              <input type=\"hidden\" class=\"usr_cid\" value=\"".$row0['cid']."\">\n";
            echo "              <input type=\"hidden\" class=\"usr_jst\" value=\"0\">\n";
            echo "              <a class=\"set_JMS_Job_Status\" href=\"#\"><img src=\"images/action_delete.gif\" title=\"Remove this job from Accounting Release\"></a>\n";
            echo "          </div>\n";
        }
        
        echo "                  </td>\n";
        echo "		            <td class=\"proc_status_snd\" align=\"center\" width=\"20px\">\n";
        
        if (($row0['acc_status'] == 1 or $row0['acc_status'] == 2) and (isset($row0['cid_status']) and $row0['cid_status']!='0'))
        {
            echo "          <div>\n";
            echo "              <input type=\"hidden\" class=\"usr_jid\" value=\"".$row0['jid']."\">\n";
            echo "              <input type=\"hidden\" class=\"usr_cid\" value=\"".$row0['cid']."\">\n";
            echo "              <input type=\"hidden\" class=\"usr_jst\" value=\"0\">\n";
            echo "              <a class=\"send_Job_Package_to_Accounting\" href=\"#\"><img src=\"images/application_add.png\" title=\"Send Customer/Job Package to Quickbooks\"></a>\n";
            echo "          </div>\n";
        }
        
        echo "                  </td>\n";
        echo "              </tr>\n";
        echo "          </table>\n";
        */
        
        
        echo "          <form method=\"POST\" target>\n";
        echo "          <input type=\"hidden\" name=\"action\" value=\"job\">\n";
        echo "          <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
        echo "          <input type=\"hidden\" name=\"rcall\" value=\"view_retail\">\n";
        echo "          <input type=\"hidden\" name=\"jobid\" value=\"".$row0['jobid']."\">\n";
        echo "          <input type=\"hidden\" name=\"njobid\" value=\"".$row0['njobid']."\">\n";
        echo "          <input type=\"hidden\" name=\"cid\" value=\"".$row0['cid']."\">\n";
        echo "          <input type=\"hidden\" name=\"custid\" value=\"".$row0['cid']."\">\n";
        echo "          <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
        echo "          <input type=\"hidden\" name=\"uid\" value=\"0\">\n";
        echo "          <input class=\"transnb JMStooltip\" type=\"image\" src=\"images/application_form_magnify.png\" title=\"Open OneSheet\">\n";
        echo "          </form>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
    }
    
    echo "</table>\n";
    
    return $out;
}

function list_JMS_Closed($oid,$c,$db)
{
    $out='';
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    /*
    echo '<pre>';
    print_r($_REQUEST);
    echo '</pre>';
    
    ACC Status Codes
    0 - Unreleased
    1 - Released Unsent
    2 - Released Reverted
    3 - Reserved
    4 - Transmitted to Accounting
    5 - Resent to Accounting
    6 - Reserved
    7 - Reserved
    8 - Reserved
    9 - Closed
    */
    
    $qry    = "SELECT officeid,name FROM offices as O where officeid=".$oid.";";
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
    
    $qry0	= "
                SELECT
                    J1.jid,J1.custid,J1.jobid,
                    J1.ListID,J1.EditSequence,J1.added,J1.acc_released,J1.acc_transmitted,J1.acc_status,
                    (select clname from cinfo where cid=J1.custid) as clname,
                    (select cfname from cinfo where cid=J1.custid) as cfname
                FROM
                    jobs J1
                WHERE
                    J1.officeid=".$oid."
                    and J1.acc_status = 9
                    --and J1.acc_released >='1/1/2010'
                    --and J1.ListID!='0'
                ORDER BY
                    J1.acc_status,
                    clname;
                ";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{
        echo "<table width=\"915px\">\n";
        echo "	<tr>\n";
        echo "		<td align=\"left\" colspan=\"10\">\n";
        echo "          <table class=\"outer\" width=\"100%\">\n";
        echo "          	<tr>\n";
        echo "            		<td align=\"left\">Closed Jobs</b></td>\n";
        echo "          		<td align=\"right\"><b>Records Found " . $nrow0 . "</b></td>\n";
        echo "          	</tr>\n";
        echo "          </table>\n";
        echo "		</td>\n";
        echo "	</tr>\n";
        echo "	<tr>\n";
        echo "		<td align=\"right\"></td>\n";
        echo "		<td align=\"left\"><b>Customer</b></td>\n";
        echo "		<td align=\"center\"><b>JobID</b></td>\n";
        echo "		<td align=\"center\"><b>Status</b></td>\n";
        echo "		<td align=\"center\"><b>Added</b></td>\n";
        echo "		<td align=\"center\"><b>Released</b></td>\n";
        echo "		<td align=\"center\"><b>Transmitted</b></td>\n";
        echo "		<td align=\"right\"></td>\n";
        echo "		<td align=\"right\"></td>\n";
        echo "	</tr>\n";
        
		//echo '<pre>';
		$ccnt=0;
		while ($row0 = mssql_fetch_array($res0))
		{
            $ccnt++;
            if ($ccnt%2)
			{
				$trbg = 'odd';
			}
			else
			{
				$trbg = 'even';
			}
            
            //$li_es=$row0['ListID'].':'.$row0['EditSequence'];
            $li_es='';
            
            echo "	<tr class=\"".$trbg."\">\n";
            echo "		<td align=\"right\" title=\"".$li_es."\">".$ccnt."</td>\n";
            echo "		<td align=\"left\">".$row0['clname']." ".$row0['cfname']."</td>\n";
            echo "		<td align=\"center\">".$row0['jobid']."</td>\n";
            echo "		<td align=\"center\">\n";
            
            if (isset($row0['acc_status']) and $row0['acc_status']==1)
            {
                echo 'Released';
            }
            elseif (isset($row0['acc_status']) and $row0['acc_status']==2)
            {
                echo 'Reverted';
            }
            elseif (isset($row0['acc_status']) and $row0['acc_status']==4)
            {
                echo 'Transmitted';
            }
            elseif (isset($row0['acc_status']) and $row0['acc_status']==9)
            {
                echo 'Closed';
            }
            
            echo "      </td>\n";
            echo "		<td align=\"center\">".date('m/d/Y',strtotime($row0['added']))."</td>\n";
            echo "		<td align=\"center\">\n";
            
            if (strtotime($row0['acc_released']) > strtotime('1/1/2010'))
            {
                echo date('m/d/Y',strtotime($row0['acc_released']));
            }
            
            echo "      </td>\n";
            echo "		<td align=\"center\">\n";
            
            if (strtotime($row0['acc_transmitted']) > strtotime('1/1/2010'))
            {
                echo date('m/d/Y',strtotime($row0['acc_transmitted']));
            }
            
            echo "      </td>\n";
            echo "		<td align=\"right\">\n";
            
            if ($row0['acc_status'] == 1 or $row0['acc_status'] == 2)
            {
                echo "          <div>\n";
                echo "              <input type=\"hidden\" class=\"usr_jid\" value=\"".$row0['jid']."\">\n";
                echo "              <input type=\"hidden\" class=\"usr_jst\" value=\"0\">\n";
                echo "              <img class=\"set_JMS_Job_Status\" src=\"images/delete.png\" title=\"Click to Remove this job from Accounting Release\">\n";
                echo "          </div>\n";
            }
            
            echo "      </td>\n";
            echo "		<td align=\"right\">\n";
            
            if ($row0['acc_status'] == 1 or $row0['acc_status'] == 2)
            {
                echo "          <div>\n";
                echo "              <input type=\"hidden\" class=\"usr_jid\" value=\"".$row0['jid']."\">\n";
                echo "              <img class=\"send_Job_to_Accounting\" src=\"images/arrow_right.png\" title=\"Click to Send this Customer to Quickbooks\">\n";
                echo "          </div>\n";
            }
            
            echo "      </td>\n";
		}
		
        echo "</table>\n";
	}
	else
	{
		echo 'No Jobs Closed<br>';
	}
    
    return $out;
}

function list_QB_Queue($oid,$s,$db)
{
    $out='';    
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    //echo '<pre>';
    //print_r($db);
    //echo '</pre>';
	//echo '<pre>';
    //print_r($_REQUEST);
    //echo '</pre>';
	
    if (isset($s) and ($s=='q' or $s=='s' or $s=='e' or $s=='i'))
	{
        $qry0	= "SELECT * FROM quickbooks_queue where qb_action like 'Estimate%' and qb_status='".$s."' order by enqueue_datetime desc;";
    }
    elseif (isset($s) and $s=='a')
    {
        $qry0	= "SELECT * FROM quickbooks_queue where qb_action like 'Estimate%' and qb_status!='s';";
    }
    
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
    
    if ($s=='q')
    {
        $qtbg='lightblue';
        $qtype=" <b>Pending</b> Processing";
    }
    elseif ($s=='i')
    {
        $qtbg='yellow';
        $qtype=" <b>Incomplete</b> Processing";
    }
    elseif ($s=='e')
    {
        $qtbg='red';
        $qtype=" <b>Errors</b> Processing";
    }
    elseif ($s=='s')
    {
        $qtbg='lightgreen';
        $qtype=" <b>Processed</b> ";
    }
    elseif ($s=='a')
    {
        $qtbg='';
        $qtype=" <b>All</b> ";
    }
    else
    {
        $qtbg='lightblue';
        $qtype=" <b>Pending</b> ";
    }
    
    echo "<table width=\"915px\">\n";
    echo "	<tr>\n";
    echo "		<td align=\"left\" colspan=\"10\">\n";
    echo "          <table class=\"outer\" width=\"100%\">\n";
    echo "          	<tr>\n";
    echo "            		<td class=\"".$qtbg."\" align=\"left\">\n";
    
    echo $qtype;
    
    echo "                  </td>\n";
    echo "          		<td class=\"".$qtbg."\" align=\"right\">Record(s) <b>" . $nrow0 . "</b></td>\n";
    echo "          	</tr>\n";
    echo "          </table>\n";
    echo "		</td>\n";
    echo "	</tr>\n";
    echo "	<tr>\n";
    echo "		<td align=\"left\"></td>\n";
    echo "		<td align=\"left\"><b>JobID</b></td>\n";
    echo "		<td align=\"left\"><b>Status</b></td>\n";
    echo "		<td align=\"center\"><b>Queue Date</b></td>\n";
    echo "		<td align=\"center\"><b>Process Date</b></td>\n";
    echo "		<td align=\"center\"></td>\n";
    echo "	</tr>\n";
    
	if ($nrow0 > 0)
	{
        $ccnt=0;
		while ($row0 = mssql_fetch_array($res0))
		{
            $ccnt++;
            if ($ccnt%2)
			{
				$trbg = 'odd';
			}
			else
			{
				$trbg = 'even';
			}
            
            echo "	<tr class=\"".$trbg."\">\n";
            echo "		<td align=\"right\">".$ccnt."</td>\n";
            //echo "		<td align=\"left\">".$row0['clname']." ".$row0['cfname']."</td>\n";
            echo "		<td align=\"left\">".$row0['ident']."</td>\n";
            echo "		<td align=\"left\">\n";
            
            if (isset($row0['qb_status']) and $row0['qb_status']=='s')
            {
                echo 'Processed';
            }
            elseif (isset($row0['qb_status']) and $row0['qb_status']=='q')
            {
                echo 'Queued';
            }
            elseif (isset($row0['qb_status']) and $row0['qb_status']=='i')
            {
                echo 'Incomplete';
            }
            elseif (isset($row0['qb_status']) and $row0['qb_status']=='e')
            {
                echo 'Errors';
            }
            
            echo "      </td>\n";
            echo "		<td align=\"center\">\n";
            
            if (strtotime($row0['enqueue_datetime']) > strtotime('1/1/2010'))
            {
                echo "          <table width=\"100px\">\n";
                echo "          	<tr>\n";
                echo "            		<td align=\"left\">\n";
                echo date('m/d/y',strtotime($row0['enqueue_datetime']));
                echo "                  </td>\n";
                echo "            		<td align=\"right\">\n";
                echo date('g:iA',strtotime($row0['enqueue_datetime']));
                echo "                  </td>\n";
                echo "          	</tr>\n";
                echo "          </table>\n";
            }
            
            echo "      </td>\n";
            echo "		<td align=\"center\">\n";
            
            if (strtotime($row0['dequeue_datetime']) > strtotime('1/1/2010'))
            {
                echo "          <table width=\"100px\">\n";
                echo "          	<tr>\n";
                echo "            		<td align=\"left\">\n";
                echo date('m/d/y',strtotime($row0['dequeue_datetime']));
                echo "                  </td>\n";
                echo "            		<td align=\"right\">\n";
                echo date('g:iA',strtotime($row0['dequeue_datetime']));
                echo "                  </td>\n";
                echo "          	</tr>\n";
                echo "          </table>\n";
            }
            
            echo "      </td>\n";
            echo "		<td align=\"center\">\n";
            
            if (isset($row0['qb_status']) and $row0['qb_status']=='q')
            {
                echo "          <div>\n";
                echo "              <input type=\"hidden\" class=\"usr_qid\" value=\"".$row0['quickbooks_queue_id']."\">\n";
                echo "              <img class=\"revertJobtoJMSReleased\" src=\"images/action_delete.gif\" title=\"Click to remove Job from Queue and revert to Released\">\n";
                echo "          </div>\n";
            }
            elseif (isset($row0['qb_status']) and ($row0['qb_status']=='i' or $row0['qb_status']=='e'))
            {
                echo "          <div>\n";
                echo "              <input type=\"hidden\" class=\"usr_qid\" value=\"".$row0['quickbooks_queue_id']."\">\n";
                echo "              <img class=\"clearAccountingState\" src=\"images/arrow_refresh.png\" title=\"Click to clear state and re-queue\">\n";
                echo "          </div>\n";
            }
            
            echo "      </td>\n";
            echo "	</tr>\n";
            
            if (isset($row0['qb_status']) and ($row0['qb_status']=='i' or $row0['qb_status']=='e'))
            {
                echo "	<tr class=\"".$trbg."\">\n";
                echo "		<td align=\"left\"></td>\n";
                echo "		<td align=\"left\" colspan=\"9\">".$row0['msg']."</td>\n";
                echo "	</tr>\n";
            }
		}
	}
	else
	{
        echo "	<tr>\n";
        echo "		<td align=\"left\" colspan=\"10\">Nothing in the Queue</td>\n";
        echo "	</tr>\n";
	}
    
    echo '</table>';
    return $out;
}

function list_QB_Processed($oid,$s,$db)
{
    //$out=$oid.':'.$s;
    $out='';
	
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qry0	= "
                SELECT
                    Q1.*,Q2.*
                FROM
                    quickbooks_queue AS Q1
                INNER JOIN
                    quickbooks_ident AS Q2
                ON
                    Q1.ident=Q2.unique_id
                WHERE
                    Q1.qb_action like 'Estimate%'
                    and Q1.qb_status='s'
                    and Q2.qb_object='Estimate'
                ORDER BY Q2.map_datetime desc;
                ";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{
        echo "<table width=\"915px\">\n";
        echo "	<tr>\n";
        echo "		<td align=\"left\" colspan=\"10\">\n";
        echo "          <table class=\"outer\" width=\"100%\">\n";
        echo "          	<tr>\n";
        echo "            		<td class=\"lightgreen\" align=\"left\"><b>Jobs Completed Processing</b></td>\n";
        echo "          		<td class=\"lightgreen\" align=\"right\"><b>Records Found " . $nrow0 . "</b></td>\n";
        echo "          	</tr>\n";
        echo "          </table>\n";
        echo "		</td>\n";
        echo "	</tr>\n";
		echo "	<tr>\n";
        echo "		<td align=\"center\"></td>\n";
        echo "		<td align=\"left\"><b>JobID</b></td>\n";
        echo "		<td align=\"left\"><b>Object</b></td>\n";
        echo "		<td align=\"left\"><b>Action</b></td>\n";
        echo "		<td align=\"left\"><b>Status</b></td>\n";
        echo "		<td align=\"left\"><b>Send Date</b></td>\n";
        echo "		<td align=\"left\"><b>Proc Date</b></td>\n";
        echo "		<td align=\"left\"></td>\n";
        echo "	</tr>\n";
		
        $ccnt=0;
		while ($row0 = mssql_fetch_array($res0))
		{
            $ccnt++;
            if ($ccnt%2)
			{
				$trbg = 'odd';
			}
			else
			{
				$trbg = 'even';
			}
            
            echo "	<tr class=\"".$trbg."\" title=\"".trim($row0['msg'])."\">\n";
            echo "		<td align=\"right\">".$ccnt."</td>\n";
            //echo "		<td align=\"left\">".$row0['quickbooks_queue_id']."</td>\n";
            //echo "		<td align=\"left\">".$row0['quickbooks_ticket_id']."</td>\n";
            echo "		<td align=\"left\">".$row0['ident']."</td>\n";
            echo "		<td align=\"left\">".$row0['qb_object']."</td>\n";
            echo "		<td align=\"left\">".$row0['qb_action']."</td>\n";
            echo "		<td align=\"left\">Processed</td>\n";
            echo "		<td align=\"left\">\n";
            
            if (strtotime($row0['enqueue_datetime']) > strtotime('1/1/2010'))
            {
                echo "          <table width=\"100px\">\n";
                echo "          	<tr>\n";
                echo "            		<td align=\"left\">\n";
                echo date('m/d/y',strtotime($row0['enqueue_datetime']));
                echo "                  </td>\n";
                echo "            		<td align=\"right\">\n";
                echo date('g:iA',strtotime($row0['enqueue_datetime']));
                echo "                  </td>\n";
                echo "          	</tr>\n";
                echo "          </table>\n";
            }
            
            echo "      </td>\n";
            echo "		<td align=\"left\">\n";
            
            if (strtotime($row0['dequeue_datetime']) > strtotime('1/1/2010'))
            {
                echo "          <table width=\"100px\">\n";
                echo "          	<tr>\n";
                echo "            		<td align=\"left\">\n";
                echo date('m/d/y',strtotime($row0['dequeue_datetime']));
                echo "                  </td>\n";
                echo "            		<td align=\"right\">\n";
                echo date('g:iA',strtotime($row0['dequeue_datetime']));
                echo "                  </td>\n";
                echo "          	</tr>\n";
                echo "          </table>\n";
            }
            
            echo "      </td>\n";
            echo "		<td align=\"right\">\n";
            echo "          <div class=\"reset_ContractInfo\">\n";
            echo "             <input type=\"hidden\" class=\"quickbooks_queue_id\" value=\"".$row0['quickbooks_queue_id']."\">\n";
            echo "             <img class=\"JMStooltip\" src=\"images/page_refresh.png\" title=\"Click to Resend this Customer to Quickbooks\">\n";
            echo "          </div>\n";
            echo "      </td>\n";
            echo "	</tr>\n";
		}
		
		//echo '</pre>';
        echo "</table>\n";
	}
	else
	{
        echo "	<tr>\n";
        echo "		<td align=\"left\">Nothing in the Queue</td>\n";
        echo "	</tr>\n";
	}
    
    return $out;
}

function get_Customer_Status($cid,$db)
{
    mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
    $qry0 = "SELECT ListID from cinfo where cid=". (int) $cid;
	$res0 = mssql_query($qry0);
    $row0 = mssql_fetch_array($res0);
	
    return $row0['ListID'];
    
    //return $qry0;
}

function list_Log($oid,$q,$a,$c,$db)
{
	$out='';    
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
    $qry0  = "SELECT";

    if ($c!=0)
    {
        $qry0 .= " TOP ".$c;
    }
    
    $qry0 .= " Q1.*,(select top 1 qb_ident from quickbooks_ident where unique_id=Q1.ident) as ListID FROM quickbooks_queue AS Q1 WHERE";
    
    if (isset($q) and $q=='e')
    {
        $qry0 .= " (Q1.qb_status='e' OR Q1.qb_status='i')";
    }
    else
    {
        $qry0 .= " Q1.qb_status='".$q."'";
    }
    
    if (isset($a) and $a!='A')
    {
        $qry0 .= "  AND Q1.qb_action='".$a."' ";
    }
    
    $qry0 .= " order by Q1.enqueue_datetime desc;";
    
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
    
    //echo $qry0.'<br>';
    
    echo "<table width=\"915px\">\n";
    echo "	<tr>\n";
    echo "		<td align=\"left\" colspan=\"11\">\n";
    echo "          <table class=\"outer\" width=\"100%\">\n";
    echo "          	<tr>\n";
    echo "            		<td align=\"left\"><b>Complete Log</b></td>\n";
    echo "          		<td align=\"right\">Record(s) <b>" . $nrow0 . "</b></td>\n";
    echo "          	</tr>\n";
    echo "          </table>\n";
    echo "		</td>\n";
    echo "	</tr>\n";
    echo "	<tr>\n";
    echo "		<td align=\"left\"></td>\n";
    echo "		<td align=\"left\"><b>QB QueueID</b></td>\n";
    echo "		<td align=\"left\"><b>JMS UniqueID</b></td>\n";
    echo "		<td align=\"left\"><b>QB ListID</b></td>\n";
    echo "		<td align=\"left\"><b>QB Action</b></td>\n";
    echo "		<td align=\"center\"><b>QB Priority</b></td>\n";
    echo "		<td align=\"center\"><b>QB Status</b></td>\n";
    echo "		<td align=\"center\"><b>Queue Date</b></td>\n";
    echo "		<td align=\"center\"><b>Process Date</b></td>\n";
    echo "		<td align=\"left\"></td>\n";
    echo "		<td align=\"left\"></td>\n";
    echo "	</tr>\n";
    
	if ($nrow0 > 0)
	{
        $ccnt=0;
		while ($row0 = mssql_fetch_array($res0))
		{
            $ccnt++;
            if ($ccnt%2)
			{
				$trbg = 'odd';
			}
			else
			{
				$trbg = 'even';
			}
            
            echo "	<tr class=\"".$trbg."\">\n";
            echo "		<td align=\"right\">".$ccnt."</td>\n";
            echo "		<td align=\"left\">".$row0['quickbooks_queue_id']."</td>\n";
            echo "		<td align=\"left\">".$row0['ident']."</td>\n";
            echo "		<td align=\"left\">".$row0['ListID']."</td>\n";
            echo "		<td align=\"left\">".$row0['qb_action']."</td>\n";
            echo "		<td align=\"center\">".$row0['priority']."</td>\n";
            echo "		<td align=\"center\">\n";
            
            if ($row0['qb_status']=='e')
            { 
                echo 'Error';
            }
            elseif ($row0['qb_status']=='i')
            {
                echo 'Incomplete';
            }
            elseif ($row0['qb_status']=='q')
            {
                echo 'Queued';
            }
            elseif ($row0['qb_status']=='s')
            {
                echo 'Processed';
            }
            
            echo "      </td>\n";
            echo "		<td align=\"center\">\n";
            
            if (strtotime($row0['enqueue_datetime']) > strtotime('1/1/2010'))
            {
                echo "          <table width=\"100px\">\n";
                echo "          	<tr>\n";
                echo "            		<td align=\"left\">\n";
                echo date('m/d/y',strtotime($row0['enqueue_datetime']));
                echo "                  </td>\n";
                echo "            		<td align=\"right\">\n";
                echo date('g:iA',strtotime($row0['enqueue_datetime']));
                echo "                  </td>\n";
                echo "          	</tr>\n";
                echo "          </table>\n";
            }
            
            echo "      </td>\n";
            echo "		<td align=\"center\">\n";
            
            if (strtotime($row0['dequeue_datetime']) > strtotime('1/1/2010'))
            {
                echo "          <table width=\"100px\">\n";
                echo "          	<tr>\n";
                echo "            		<td align=\"left\">\n";
                echo date('m/d/y',strtotime($row0['dequeue_datetime']));
                echo "                  </td>\n";
                echo "            		<td align=\"right\">\n";
                echo date('g:iA',strtotime($row0['dequeue_datetime']));
                echo "                  </td>\n";
                echo "          	</tr>\n";
                echo "          </table>\n";
            }
            
            echo "      </td>\n";
            echo "		<td align=\"center\">\n";

            if ($row0['qb_status']!='q' and ($_SESSION['securityid']==26))
            {
                echo "          <div>\n";
                echo "              <input type=\"hidden\" class=\"usr_qid\" value=\"".$row0['quickbooks_queue_id']."\">\n";
                echo "              <a class=\"delete_from_Accounting_Log\" href=\"#\"><img src=\"images/action_delete.gif\" title=\"Click to Delete this Entry\"></a>\n";
                echo "          </div>\n";
            }
            
            echo "      </td>\n";
            echo "		<td align=\"center\">\n";
            
            if ($row0['qb_status']!='q' and ($_SESSION['securityid']==26))
            {
                echo "          <div>\n";
                echo "              <input type=\"hidden\" class=\"usr_qid\" value=\"".$row0['quickbooks_queue_id']."\">\n";
                echo "              <a class=\"clearAccountingState_Log\" href=\"#\"><img src=\"images/arrow_refresh_small.png\" title=\"Click to clear state and re-queue\"></a>\n";
                echo "          </div>\n";
            }

            echo "      </td>\n";
            echo "	</tr>\n";
            
            if (isset($row0['qb_status']) and ($row0['qb_status']!='q'))
            {
                echo "	<tr class=\"".$trbg."\">\n";
                echo "		<td align=\"left\"></td>\n";
                echo "		<td align=\"left\" colspan=\"10\">".$row0['msg']."</td>\n";
                echo "	</tr>\n";
            }
		}
	}
	else
	{
        echo "	<tr>\n";
        echo "		<td align=\"left\" colspan=\"10\">Nothing in the Queue</td>\n";
        echo "	</tr>\n";
	}
    
    echo '</table>';
    return $out;
}

?>