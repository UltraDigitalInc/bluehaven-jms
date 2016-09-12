<?php

function get_Prior_Job_Store($oid,$jobid,$jadd)
{
    $inc_data=array('oid'=>$oid,'jobid'=>$jobid,'jadd'=>$jadd);
    
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

function getOneSheetCmntSelector()
{
    $out='';
    $cid=(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;	

    if ($cid!=0) {
        $qry0 = "
                SELECT
                    C.*
                    ,(select lname from jest..security where securityid=C.secid) as lname
                    ,(select substring(fname,1,1) from jest..security where securityid=C.secid) as fname
                FROM
                    chistory as C
                WHERE
                    C.custid=".(int) $cid." ORDER BY C.mdate DESC;";
        $res0 = mssql_query($qry0);
        $nrow0= mssql_num_rows($res0);
        
        if ($_SESSION['securityid']==269999999999999999999)
        {
            echo $qry0.'<br>';
        }
        
        if ($nrow0 > 0)
        {
            $cfol_ar=array();
            $cres_ar=array();
            $ccls_ar=array();
            $sfol_ar=array();
            $sres_ar=array();
            $scls_ar=array();
            while ($row0 = mssql_fetch_array($res0))
            {
                $cstatus='';
                if ($row0['complaint']==1)
                {
                    if ($row0['followup']==0 && $row0['resolved']==0)
                    {
                        $cstatus=$row0['id'];
                        
                        $cfol_ar[]=$row0['id'];
                        $cres_ar[]=$row0['id'];
                    }
                    elseif ($row0['followup']==1 && $row0['resolved']==0)
                    {
                        $cstatus=$row0['relatedcomplaint'];
                        
                        if (!in_array($row0['relatedcomplaint'],$cfol_ar))
                        {
                            $cfol_ar[]=$row0['relatedcomplaint'];
                        }
                        
                        if (!in_array($row0['relatedcomplaint'],$cres_ar))
                        {
                            $cres_ar[]=$row0['relatedcomplaint'];
                        }
                    }
                    elseif ($row0['resolved']==1)
                    {					
                        $cstatus=$row0['relatedcomplaint'];
                        $ccls_ar[]=$row0['relatedcomplaint'];
                    }
                }
                
                if ($row0['cservice']==1)
                {
                    if ($row0['followup']==0 && $row0['resolved']==0)
                    {					
                        $cstatus=$row0['id'];
                        $sfol_ar[]=$row0['id'];
                        $sres_ar[]=$row0['id'];
                    }
                    elseif ($row0['followup']==1 && $row0['resolved']==0)
                    {					
                        $cstatus=$row0['relatedcomplaint'];
                        
                        if (!in_array($row0['relatedcomplaint'],$sfol_ar))
                        {
                            $sfol_ar[]=$row0['relatedcomplaint'];
                        }
                        
                        if (!in_array($row0['relatedcomplaint'],$sres_ar))
                        {
                            $sres_ar[]=$row0['relatedcomplaint'];
                        }
                    }
                    elseif ($row0['resolved']==1)
                    {					
                        $cstatus=$row0['relatedcomplaint'];
                        $scls_ar[]=$row0['relatedcomplaint'];
                    }
                }
            }
            
            $out=$out."<select name=\"commentflag\" id=\"cmntflag\">\n";
            $out=$out." <option value=\"0\">Select...</option>\n";
            //$out=$out." <optgroup label=\"New\">\n";
            $out=$out."     <option value=\"C:0\">Lead Comment</option>\n";
            $out=$out."     <option value=\"CC:0\">Construction Comment</option>\n";
            $out=$out."     <option value=\"S:1\">Service Ticket</option>\n";
            $out=$out."     <option value=\"C:1\">Complaint</option>\n";
            //$out=$out." </optgroup>\n";
            
            /*
            if ($_SESSION['csrep'] >= 6)
            {
                $xx=false;
                if (
                    (is_array($sfol_ar) and count($sfol_ar) > 0) or
                    (is_array($sres_ar) and count($sres_ar) > 0) or
                    (is_array($cfol_ar) and count($cfol_ar) > 0) or
                    (is_array($cres_ar) and count($cres_ar) > 0)) {
                    $xx =true;
                    $out=$out." <optgroup label=\"Resolve\">\n";
                }
                
                if (is_array($sfol_ar) and count($sfol_ar) > 0) {
                    foreach (array_unique($sfol_ar) as $sfn => $sfv) {
                        if (!in_array($sfv,$scls_ar)) {
                            $out=$out."     <option value=\"SF:".$sfv."\">Service Ticket Followup: ".$sfv."</option>\n";
                        }
                    }
                }
                
                if (is_array($sres_ar) and count($sres_ar) > 0) {
                    foreach (array_unique($sres_ar) as $srn => $srv) {
                        if (!in_array($srv,$scls_ar)) {
                            $out=$out."     <option value=\"SR:".$srv."\">Service Ticket Resolve: ".$srv."</option>\n";
                        }
                    }
                }
                
                if (is_array($cfol_ar) and count($cfol_ar) > 0) {
                    foreach (array_unique($cfol_ar) as $fn => $fv) {
                        if (!in_array($fv,$ccls_ar)) {
                            $out=$out."     <option value=\"CF:".$fv."\">Complaint Followup: ".$fv."</option>\n";
                        }
                    }
                }
                
                if (is_array($cres_ar) and count($cres_ar) > 0) {
                    foreach (array_unique($cres_ar) as $rn => $rv) {
                        if (!in_array($rv,$ccls_ar)) {
                            $out=$out."     <option value=\"CR:".$rv."\">Complaint Resolve: ".$rv."</option>\n";
                        }
                    }
                }
                
                if ($xx) {
                    $out=$out." </optgroup>\n";
                }
            }
            */
            
            $out=$out."</select>\n";
            
        }
    }
	return $out;
}

function removequote($data)
{
	$qs=array("/'/","/''/");
	$rp='';
	$out=preg_replace($qs,$rp,$data);
	return $out;
}

function saveOneSheetComment()
{
	$out=0;
    $oid=(isset($_SESSION['officeid']) and $_SESSION['officeid']!=0)?$_SESSION['officeid']:null;
    $sid=(isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)?$_SESSION['securityid']:null;
    $cid=(isset($_REQUEST['cid']) and is_numeric($_REQUEST['cid']))?$_REQUEST['cid']:null;
    $cmnt=(isset($_REQUEST['cmnt']) and strlen($_REQUEST['cmnt'])!=0)?$_REQUEST['cmnt']:null;
    $cmntflg=(isset($_REQUEST['cmntflg']) and strlen($_REQUEST['cmntflg'])!=0)?$_REQUEST['cmntflg']:null;
	
	if (
		(!is_null($oid) and $oid!=0) and
		(!is_null($sid) and $sid!=0) and
		(!is_null($cid) and $cid!=0) and
		!is_null($cmnt) and !is_null($cmntflg)
		)
	{
		$tranid=rand(1000001,100000001).'.'.$sid;
		
		if (isset($cmntflg) and ($cmntflg=='0' or $cmntflg=='C:0')) {
			$inputtext=removequote($cmnt);
			$action='leads';
			$complaint=0;
			$cservice=0;
			$followup=0;
			$resolve=0;
			$relid=0;
		}
        elseif (isset($cmntflg) and $cmntflg=='CC:0') {
			$inputtext=removequote($cmnt);
			$action='Construction';
			$complaint=0;
			$cservice=0;
			$followup=0;
			$resolve=0;
			$relid=0;
		}
		else {
			$cmtflg_ar=explode(":",$cmntflg);
			$inputtext=removequote($cmnt);
			$action='leads';
			$complaint=0;
			$cservice=0;
			$followup=0;
			$resolve=0;
			$relid=0;
			
			if ($cmtflg_ar[0]=="C")
			{
				if ($cmtflg_ar[1]==1)
				{
					$inputtext="Complaint Created.\r".removequote($cmnt);
					$action="Complaint";
					$complaint=1;
					$cservice=0;
					$followup=0;
					$resolve=0;
					$relid=0;
				}
			}
			elseif ($cmtflg_ar[0]=="S")
			{
				if ($cmtflg_ar[1]==1)
				{
					$inputtext="Service Request Created.\r".removequote($cmnt);
					$action="Service";
					$complaint=0;
					$cservice=1;
					$followup=0;
					$resolve=0;
					$relid=0;
				}
			}
			elseif ($cmtflg_ar[0]=="CF")
			{
				$inputtext="Complaint Followup.\r".removequote($cmnt);
				$action="Followup";
				$complaint=1;
				$cservice=0;
				$followup=1;
				$resolve=0;
				$relid=$cmtflg_ar[1];
			}
			elseif ($cmtflg_ar[0]=="CR")
			{
				$inputtext="Complaint Resolved.\r".removequote($cmnt);
				$action="Resolved";
				$complaint=1;
				$cservice=0;
				$followup=1;
				$resolve=1;
				$relid=$cmtflg_ar[1];
			}
			elseif ($cmtflg_ar[0]=="SF")
			{
				$inputtext="Service Followup.\r".removequote($cmnt);
				$action="Followup";
				$complaint=0;
				$cservice=1;
				$followup=1;
				$resolve=0;
				$relid=$cmtflg_ar[1];
			}
			elseif ($cmtflg_ar[0]=="SR")
			{
				$inputtext="Service Resolved.\r".removequote($cmnt);
				$action="Resolved";
				$complaint=0;
				$cservice=1;
				$followup=1;
				$resolve=1;
				$relid=$cmtflg_ar[1];
			}
		}
		
        if (isset($cmntflg) and $cmntflg=='CC:0') {
            $qry  = "INSERT INTO jest..construction_comments (oid,sid,cid,act,tranid,mtext) ";
            $qry .= "VALUES ";
            $qry .= "(".(int) $oid.",".(int) $sid.",".(int) $cid.",'".$action."','".$tranid."','".htmlspecialchars(removequote($inputtext),ENT_QUOTES)."');";
            $qry .= "SELECT @@IDENTITY;";
            $res  = mssql_query($qry);
            $out  = mssql_fetch_row($res);
            
            if (isset($out[0]) and $out[0]!=0) {
                $qry1 = "UPDATE cinfo SET updated=getdate() WHERE cid=".(int) $cid.";";
                $res1 = mssql_query($qry1);
            }
        }
        else {
            $qry  = "INSERT INTO jest..chistory (officeid,secid,custid,act,tranid,mtext,complaint,followup,resolved,relatedcomplaint,cservice) ";
            $qry .= "VALUES ";
            $qry .= "(".(int) $oid.",".(int) $sid.",".(int) $cid.",'".$action."','".$tranid."','".htmlspecialchars(removequote($inputtext),ENT_QUOTES)."',".$complaint.",".$followup.",".$resolve.",".$relid.",".$cservice.");";
            $qry .= "SELECT @@IDENTITY;";
            $res  = mssql_query($qry);
            $out  = mssql_fetch_row($res);
            
            if (isset($out[0]) and $out[0]!=0) {
                $qry1 = "UPDATE cinfo SET updated=getdate() WHERE cid=".(int) $cid.";";
                $res1 = mssql_query($qry1);
            }
        }
	}
	
	return $out;
}

function getCustomerLifeCycle()
{
    $cid=(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    
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
    $out    = '';
    
    $qry = "SELECT C.* FROM cinfo AS C WHERE C.cid=".(int) $cid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
    
    $oid=(isset($row['officeid']) and $row['officeid']!=0)?$row['officeid']:0;
    
    $qryA = "SELECT officeid,finan_off,finan_from,fsenable,fscustomer,enquickbooks FROM offices WHERE officeid=".(int) $_SESSION['officeid'].";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
    
    $qryB = "SELECT estid,officeid,cid,esttype,added,updated FROM est WHERE officeid=".$row['officeid']." and ccid=".(int) $row['cid'].";";
	$resB = mssql_query($qryB);
    
    while ($rowB = mssql_fetch_array($resB))
    {
        $estinfo[]=$rowB;
    }

	$qryC = "SELECT * FROM offices WHERE officeid=".(int) $oid.";";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$qryD = "SELECT mas_div,filestoreaccess FROM security WHERE securityid=".(int) $row['securityid'].";";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);
	
    /*
	if ($row['estid']!=0)
	{
		$qryE = "SELECT estid,added,updated FROM est WHERE officeid=".(int) $oid." AND estid='".$row['estid']."';";
		$resE = mssql_query($qryE);
		$rowE = mssql_fetch_array($resE);
		
		$eadate= date("m/d/Y", strtotime($rowE['added']));
		$eudate= date("m/d/Y", strtotime($rowE['updated']));
	}
	*/
    
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
    
    $pjstore=get_Prior_Job_Store($oid,$row['jobid'],0);
    
    $out=$out."						<table align=\"center\" width=\"100%\">\n";
    $out=$out."	   						<tr>\n";
    $out=$out."      						<td colspan=\"5\" align=\"left\"><b>Lifecycle Information and Control</b></td>\n";
    $out=$out."   						</tr>\n";

    if ($_SESSION['llev']!=0 && $row['cid']!=0)
    {
        $uid	=md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];
        
        $out=$out."	   					<tr>\n";
        $out=$out."      						<td align=\"left\"></td>\n";
        $out=$out."      						<td align=\"left\"></td>\n";
        $out=$out."      						<td align=\"center\"><b>Added</b></td>\n";
        $out=$out."      						<td align=\"center\"><b>Updated</b></td>\n";
        $out=$out."      						<td align=\"center\"><b>View</b></td>\n";
        $out=$out."   					</tr>\n";
        $out=$out."	   					<tr class=\"even\">\n";
        $out=$out."      						<td align=\"right\" width=\"90\"><b>Lead</b></td>\n";
        $out=$out."      						<td align=\"left\" width=\"100\">".$row['cid']."</td>\n";
        $out=$out."      						<td align=\"center\">".$sdate."</td>\n";
        $out=$out."      						<td align=\"center\">".$udate."</td>\n";
        $out=$out."      						<td align=\"center\">\n";
        
        if ($rowA['finan_off']==0)
        {
            $out=$out."                        <form method=\"POST\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
            $out=$out."                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
            $out=$out."                        </form>\n";
        }
        
        $out=$out."								</td>\n";
        $out=$out."   						</tr>\n";
    }

    if ($_SESSION['elev']!=0 && count($estinfo) > 0)
    {
        //while ($rowB = mssql_fetch_array($resB))
        foreach ($estinfo as $ek=>$ev)
        {
            $out=$out."	   					<tr class=\"even\">\n";
            $out=$out."      						<td align=\"right\" width=\"90\"><b>\n";
            
            //print_r($rowB);
            
            if ($ev['esttype']=='E')
            {
                $out=$out.'Estimate';
            }
            else
            {
                $out=$out.'Quote';
            }
            
            $out=$out."</b></td>\n";
            $out=$out."      						<td align=\"left\">\n";
            $out=$out.$ev['estid'];
            $out=$out."								</td>\n";
            $out=$out."      						<td align=\"center\">".date("m/d/Y", strtotime($ev['added']))."</td>\n";
            $out=$out."      						<td align=\"center\">\n";
            
            if (empty($ev['updated']) || strtotime($ev['updated']) < strtotime('1/1/2000'))
            {
                $out=$out."<img src=\"images/pixel.gif\">\n";
            }
            else
            {
                $out=$out.date("m/d/Y", strtotime($ev['updated']));
            }
            
            $out=$out."								</td>\n";
            $out=$out."      						<td align=\"center\">\n";
            
            if ($rowA['finan_off']==0)
            {
                if ($ev['esttype']=='E')
                {
                    if ($row['jobid']!='0')
                    {
                        $out=$out."                          <img src=\"images/action_delete.gif\" title=\"Contract Created. View Contract.\">\n";
                    }
                    else
                    {
                        $out=$out."                        <form name=\"viewest\" method=\"POST\">\n";
                        $out=$out."                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
                        $out=$out."                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
                        $out=$out."                           <input type=\"hidden\" name=\"estid\" value=\"".$ev['estid']."\">\n";
                        $out=$out."                           <input type=\"hidden\" name=\"esttype\" value=\"".$ev['esttype']."\">\n";
                        $out=$out."                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Estimate\">\n";
                        $out=$out."						</form>\n";
                    }
                }
                else
                {
                    $out=$out."                        <form name=\"viewest\" method=\"POST\">\n";
                    $out=$out."                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
                    $out=$out."                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
                    $out=$out."                           <input type=\"hidden\" name=\"estid\" value=\"".$ev['estid']."\">\n";
                    $out=$out."                           <input type=\"hidden\" name=\"esttype\" value=\"".$ev['esttype']."\">\n";
                    $out=$out."                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Quote\">\n";
                    $out=$out."						</form>\n";
                }
            }
            
            $out=$out."								</td>\n";
            $out=$out."   						</tr>\n";
        }
    }
    
    if ($_SESSION['clev']!=0 && $row['jobid']!='0')
    {
        $out=$out."	   					<tr class=\"even\">\n";
        $out=$out."      						<td align=\"right\" width=\"90\"><b>Contract</b></td>\n";
        $out=$out."      						<td align=\"left\" width=\"100\">".$row['jobid']."</td>\n";
        $out=$out."      						<td align=\"center\">".$cadate."</td>\n";
        $out=$out."      						<td align=\"center\">".$cudate."</td>\n";
        $out=$out."      						<td align=\"center\">\n";
        
        if ($rowA['finan_off']==0)
        {
            $out=$out."                        <form method=\"POST\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"jobid\" id=\"usr_jobid\" value=\"".$row['jobid']."\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
            $out=$out."                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Contract\">\n";
            //$out=$out."                           <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View\" title=\"Click to View this Contract\">\n";
            $out=$out."                        </form>\n";
        }
        
        $out=$out."								</td>\n";
        $out=$out."   						</tr>\n";
    }

    if ($_SESSION['jlev']!=0 and $row['njobid']!='0')
    {
        $out=$out."	   					<tr class=\"even\">\n";
        $out=$out."      						<td align=\"right\" width=\"90\"><b>Job</b></td>\n";
        $out=$out."      						<td align=\"left\" width=\"100\">".$row['njobid']."</td>\n";
        $out=$out."      						<td align=\"center\">".$cadate."</td>\n";
        $out=$out."      						<td align=\"center\">".$cudate."</td>\n";
        $out=$out."      						<td align=\"center\">\n";
        
        if ($rowA['finan_off']==0)
        {
            $out=$out."                        <form method=\"POST\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
            $out=$out."                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Job\">\n";
            $out=$out."                        </form>\n";
        }
        
        $out=$out."								</td>\n";
        $out=$out."   						</tr>\n";
    }
    
    if ($_SESSION['jlev']!=0 && $row['njobid']!='0' && (isset($ddate) and isValidDate($ddate) and strtotime($ddate) >= strtotime('1/1/2000')))
    {
        $out=$out."	   						<tr class=\"even\">\n";
        $out=$out."      						<td align=\"right\" width=\"90\"><b>Dig Date</b></td>\n";
        $out=$out."      						<td align=\"left\" width=\"100\"><img src=\"images/pixel.gif\"></td>\n";
        $out=$out."      						<td align=\"center\">".$ddate."</td>\n";
        $out=$out."      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        $out=$out."      						<td align=\"center\"></td>\n";
        $out=$out."   						</tr>\n";
    }

    if ((isset($rowA['fscustomer']) and $rowA['fscustomer'] == 1) and (isset($rowS['filestoreaccess']) and $rowS['filestoreaccess'] >= 1))
    {
        $out=$out."	   						<tr class=\"even\">\n";
        $out=$out."      						<td align=\"right\" width=\"90\"><b>Files</b></td>\n";
        $out=$out."      						<td align=\"left\" width=\"100\">".$rowSa['tfiles']."</td>\n";
        $out=$out."      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        $out=$out."      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        $out=$out."      						<td align=\"center\">\n";
        $out=$out."									<form method=\"POST\">\n";
        $out=$out."										<input type=\"hidden\" name=\"action\" value=\"file\">\n";
        $out=$out."										<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
        $out=$out."										<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
        $out=$out."										<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Files\">\n";
        $out=$out."									</form>\n";
        $out=$out."								</td>\n";
        $out=$out."   						</tr>\n";
    }

    $out=$out."						</table>\n";
    
    return $out;
}

function checkDigReport($oid,$dte) {
    $qry	= "SELECT id FROM digreport_main WHERE officeid=".(int) $oid." AND rept_mo='".date("m", strtotime($dte))."' AND rept_yr='".date("Y", strtotime($dte))."';";
    $res	= mssql_query($qry);
    $nrow	= mssql_num_rows($res);
    return ($nrow!=0)?true:false;
}

function saveConstructionRecvAmt() {
    ini_set('display_errors','On');
    error_reporting(E_ALL);    
	$out=array();
    $out['error']=true;
    
    $jid=(isset($_REQUEST['jid']) and strlen($_REQUEST['jid']) > 0)?$_REQUEST['jid']:null;
    $phsid=(isset($_REQUEST['phsid']) and $_REQUEST['phsid'] != 0)?$_REQUEST['phsid']:null;
    $amt=(isset($_REQUEST['amt']) and is_numeric($_REQUEST['amt']))?$_REQUEST['amt']:null;
    
    if (!is_null($jid) and !is_null($phsid) and !is_null($amt)) {
        $qry0 = "SELECT J.officeid as oid,J.custid as cid,J.jobid,digdate FROM jest..jobs AS J WHERE J.jobid='".(string) $jid."';";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        $nrow0= mssql_num_rows($res0);
        
        if ($nrow0!=0) {
            $qry1 = "SELECT * FROM jest..constructiondates WHERE jobid='".(string) $row0['jobid']."' AND dtype=3 AND phsid=".(int) $phsid.";";
            $res1 = mssql_query($qry1);
            $nrow1= mssql_num_rows($res1);
            
            if ($nrow1!=0) {
                $row1 = mssql_fetch_array($res1);
                
                $uuid =(isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)?$_SESSION['securityid']:0;
                $qry2 = "UPDATE jest..constructiondates SET ramt=cast('".$amt."' as money),udate=getdate(),uuid=".$uuid." WHERE id=".(int) $row1['id']." AND dtype=3 AND phsid=".(int) $row1['phsid'].";";
                $res2 = mssql_query($qry2);
                
                $out['error']=false;
                $out['result']='Amount Updated';
            }
            else {
                $out['result']='Receivable Amount not Set. Receivable Date not Found. ('.__LINE__.')';
            }
        }
        else {
            $out['result']='Job not Found ('.__LINE__.')';
        }
    }
    else
    {
        $out['result']='Invalid Request Parameters ('.__LINE__.')';
    }
    
    return $out;
}

function clearConstructionDateLine() {
    ini_set('display_errors','On');
    error_reporting(E_ALL);    
	$out=array();
    $out['error']=true;
    $out['result']='Not Processed ('.__LINE__.')';
    
    $jid=(isset($_REQUEST['jid']) and strlen($_REQUEST['jid']) > 0)?$_REQUEST['jid']:null;
    $tid=(isset($_REQUEST['tid']) and strlen($_REQUEST['tid']) > 0)?$_REQUEST['tid']:null;
    
    if (!is_null($jid) and !is_null($tid)) {
        
        $qry0 = "SELECT J.officeid as oid,J.custid as cid,J.jobid,digdate FROM jest..jobs AS J WHERE J.jobid='".(string) $jid."';";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        $nrow0= mssql_num_rows($res0);

        if ($nrow0!=0) {
            $phsid=explode("_",$tid);
            $id_ar=array();
            
            $qryS = "SELECT id FROM jest..constructiondates WHERE jobid='".(string) $row0['jobid']."' AND phsid=".(int) $phsid[1].";";
            $resS = mssql_query($qryS);
            
            while ($rowS = mssql_fetch_array($resS)) {
                $id_ar[]=$rowS['id'];
            }
            
            if (count($id_ar) > 0) {
                foreach ($id_ar as $n=>$v) {
                    $qryD = "DELETE FROM jest..constructiondates WHERE jobid='".(string) $row0['jobid']."' AND phsid=".(int) $phsid[1]." AND id=".(int) $v.";";
                    $resD = mssql_query($qryD);
                }
                
                $out['error']=false;
            }
            else {
                //$out['result']='Line not Deleted. CD Entries not Found('.__LINE__.')';
                $out['result']=$qryS;
            }
        }
        else {
            $out['result']='Line not Deleted. Job not Found ('.__LINE__.')';
        }
    }
    else {
        $out['result']='Line not Deleted. Invalid Request Parameters ('.__LINE__.')';
    }
    
    return $out;
}

function saveConstructionDate() {
    ini_set('display_errors','On');
    error_reporting(E_ALL);    
	$out=array();
    $out['error']=true;
    
    $jid=(isset($_REQUEST['jid']) and strlen($_REQUEST['jid']) > 0)?$_REQUEST['jid']:null;
    $prc=(isset($_REQUEST['proc']) and strlen($_REQUEST['proc']) > 0)?$_REQUEST['proc']:null;
    $dte=(isset($_REQUEST['pdate']) and $_REQUEST['pdate']!=0)?$_REQUEST['pdate']:null;
    
    if (!is_null($jid) and !is_null($prc) and !is_null($dte)) {
        $ierr   =false;
        $ires   ='';
        $pdates =array('idate'=>0,'sdate'=>0,'edate'=>0,'rdate'=>0,'ramt'=>0);
        $ex_prc =explode('_',$prc);
        
        // Set PhaseID
        $phsid=$ex_prc[2];
        
        //Set Date Type
        if ($ex_prc[1]=='recv') {
            $dtype=3;
        }
        elseif ($ex_prc[1]=='end'){
            $dtype=2;
        }
        elseif ($ex_prc[1]=='start'){
            $dtype=1;
        }
        
        if ($phsid!=0 and $dtype!=0 and isValidDate($dte)) {
            $qry0 = "SELECT J.officeid as oid,J.custid as cid,J.jobid,digdate FROM jest..jobs AS J WHERE J.jobid='".(string) $jid."';";
            $res0 = mssql_query($qry0);
            $row0 = mssql_fetch_array($res0);
            $nrow0= mssql_num_rows($res0);
            
            if ($nrow0!=0) {
                $dr   = checkDigReport($row0['oid'],$dte);
                $pdates['idate']=strtotime(date('m/d/y',strtotime($dte)));
                
                $qryS = "SELECT id,cdate FROM jest..constructiondates WHERE jobid='".(string) $row0['jobid']."' AND dtype=1 AND phsid=".(int) $phsid.";";
                $resS = mssql_query($qryS);
                $rowS = mssql_fetch_array($resS);
                $nrowS= mssql_num_rows($resS);
                    
                if ($nrowS!=0) {
                    $pdates['sdate']=strtotime(date('m/d/y',strtotime($rowS['cdate'])));
                }
                
                $qryE = "SELECT id,cdate FROM jest..constructiondates WHERE jobid='".(string) $row0['jobid']."' AND dtype=2 AND phsid=".(int) $phsid.";";
                $resE = mssql_query($qryE);
                $rowE = mssql_fetch_array($resE);
                $nrowE= mssql_num_rows($resE);
                    
                if ($nrowE!=0) {
                    $pdates['edate']=strtotime(date('m/d/y',strtotime($rowE['cdate'])));
                }
                
                $qryR = "SELECT id,cdate,ramt FROM jest..constructiondates WHERE jobid='".(string) $row0['jobid']."' AND dtype=3 AND phsid=".(int) $phsid.";";
                $resR = mssql_query($qryE);
                $rowR = mssql_fetch_array($resE);
                $nrowR= mssql_num_rows($resE);
                    
                if ($nrowR!=0) {
                    $pdates['rdate']=strtotime(date('m/d/y',strtotime($rowR['cdate'])));
                    $pdates['ramt']=$rowR['ramt'];
                }
                
                if ($dtype==1) {
                    if ((isset($pdates['edate']) and $pdates['edate'] > 0) and $pdates['edate'] < $pdates['idate']) {
                        $ierr=true;
                        $ires='Scheduled Date is greater than Completed Date. ('.__LINE__.')('.$pdates['edate'].')';
                    }
                }
                
                if ($dtype==2) {
                    if ($pdates['sdate'] > $pdates['idate']) {
                        $ierr=true;
                        $ires='Completed Date is less than Scheduled Date. ('.__LINE__.')';
                    }
                    
                    if ($phsid==9 and $dr) {
                        $ierr=true;
                        $ires='A Dig Report exists for the selected Date. Date not Updated. ('.__LINE__.')';
                    }
                }
                
                if ($dtype==3) {
                }
                
                if (!$ierr) {
                    $qry1 = "SELECT * FROM jest..constructiondates WHERE jobid='".(string) $row0['jobid']."' AND dtype=".(int) $dtype." AND phsid=".(int) $phsid.";";
                    $res1 = mssql_query($qry1);
                    $nrow1= mssql_num_rows($res1);
                    
                    if ($nrow1!=0) { //Update
                        $row1 = mssql_fetch_array($res1);
                        
                        $uuid =(isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)?$_SESSION['securityid']:0;
                        $qry2 = "UPDATE jest..constructiondates SET cdate='".$dte."',udate=getdate(),uuid=".$uuid." WHERE id=".(int) $row1['id']." AND dtype=".(int) $row1['dtype']." AND phsid=".(int) $row1['phsid'].";";
                        $res2 = mssql_query($qry2);
                        
                        $out['error']=false;
                        $out['result']='Date Updated';
                        
                        if ($phsid==9 and $dtype==2) {
                            $qry2a = "UPDATE jest..jobs SET digdate='".$dte."' WHERE jobid='".$row0['jobid']."';";
                            $res2a = mssql_query($qry2a);
                            StoreCommissionHistory($row0['oid'],$row0['jobid'],true);
                        }
                    }
                    else { // Insert
                        $auid =(isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)?$_SESSION['securityid']:0;
                        $qry2 = "INSERT INTO jest..constructiondates (cid,phsid,cdate,dtype,jobid,auid) VALUES (".$row0['cid'].",".$phsid.",'".$dte."',".$dtype.",'".$row0['jobid']."',".$auid.");";
                        $res2 = mssql_query($qry2);
                        
                        $out['error']=false;
                        $out['result']='Date Added';
                        
                        if ($phsid==9 and $dtype==2) {
                            $qry2a = "UPDATE jest..jobs SET digdate='".$dte."' WHERE jobid='".$row0['jobid']."';";
                            $res2a = mssql_query($qry2a);
                            StoreCommissionHistory($row0['oid'],$row0['jobid'],false);
                        }
                    }
                }
                else {
                    $out['error']=$ierr;
                    $out['result']=$ires;
                }
            }
            else {
                $out['result']='Date not Updated. Job not Found ('.__LINE__.')';
            }
        }
        else {
            $out['result']='Date not Updated. Invalid Request Parameters ('.__LINE__.')';
        }
    }
    else
    {
        $out['result']='Date not Updated. Invalid Request Parameters ('.__LINE__.')';
    }
    
    return $out;
}

function DeleteAllCommissions($oid,$jobid) {
	$qry = "DELETE FROM jest..CommissionHistory WHERE oid=".(int) $oid." and jobid='".$jobid."';";
	$res = mssql_query($qry);
}

function setDigDate_New($in=null) {
    $out=array();
	$out['error']=true;
    $out['result']='Not Processed '.__LINE__;
    $secid=$in['secid'];    
    $isvalid=isValidDate($in['digdate']);
    
    if ($isvalid) {
        $dd	=$in['digdate']." 00:01";
        $ct	=strtotime($in['condate']);
        $dt	=strtotime($in['digdate']);

        if ($dt >= $ct) {
            $qry = "UPDATE jobs SET digdate='".$dd."',digsec=".(int) $secid." WHERE officeid=".(int) $in['oid']." AND jobid='".$in['jobid']."';";
            $res = mssql_query($qry);
            
            DeleteAllCommissions($in['oid'],$in['jobid']);
            $out=PullStoreCommissions($in['oid'],$in['jobid']);
        }
        else {
            $out['error']=true;
            $out['result']='Construction Date must be past the Dig Date';
        }
    }
    else {
        $out['error']=true;
        $out['result']='Invalid Dig Date';
    }
    
    return $out;
}

function updateDigDate() {
    $out=array();
    $tar=array();
    $tar['digdate']=(isset($_REQUEST['ddate']) and strlen($_REQUEST['ddate']) > 4)?trim($_REQUEST['ddate']):null;
    $tar['jobid']=(isset($_REQUEST['jid']) and strlen($_REQUEST['jid']) > 4)?trim($_REQUEST['jid']):null;
    
    if (!is_null($tar['digdate']) and !is_null($tar['jobid'])) {
        $qry0 = "SELECT J1.officeid as oid,J1.jobid,J1.njobid,J1.digdate,(SELECT contractdate FROM jdetail WHERE jobid=J1.jobid and jadd=0) as condate FROM jobs AS J1 WHERE J1.jobid='".(string) $tar['jobid']."';";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        $nrow0= mssql_num_rows($res0);
        
        if ($nrow0==1) {
            $tar['oid']=$row0['oid'];
            $tar['condate']=$row0['condate'];
            
            $qry1 = "SELECT officeid as oid,slevel,securityid as secid FROM security WHERE securityid=".(int) $_SESSION['securityid'].";";
            $res1 = mssql_query($qry1);
            $row1 = mssql_fetch_array($res1);
            $nrow1= mssql_num_rows($res1);            
            $slevel=explode(",",$row1['slevel']);
            
            if ($row1['oid']==89 or $slevel[6] >= 5) {
                $tar['secid']=$row1['secid'];
                
                $out=setDigDate_New($tar);
            }
            else {
                $out['error']=true;
                $out['result']='Unauthorized '.__LINE__;
            }
        }
        else {
            $out['error']=true;
            $out['result']='JOBID: '.$tar['jobid'].' Not Found'.__LINE__;
        }
    }
    else {
        $out['error']=true;
        $out['result']='Invalid Parameters: '.__LINE__;
    }

    return $out;
}

function clearDigDate() {
    $out=array();
    $jobid=(isset($_REQUEST['jid']) and strlen($_REQUEST['jid']) > 4)?$_REQUEST['jid']:null;
    if (!is_null($jobid)) {
        $qry0 = "SELECT J1.officeid as oid,J1.jobid,J1.njobid,J1.digdate,(SELECT contractdate FROM jdetail WHERE jobid=J1.jobid and jadd=0) as condate FROM jobs AS J1 WHERE J1.jobid='".(string) $jobid."';";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        $nrow0= mssql_num_rows($res0);
        
        if ($nrow0==1) {            
            $qry1 = "SELECT officeid as oid,slevel,securityid as secid FROM security WHERE securityid=".(int) $_SESSION['securityid'].";";
            $res1 = mssql_query($qry1);
            $row1 = mssql_fetch_array($res1);
            $nrow1= mssql_num_rows($res1);            
            $slevel=explode(",",$row1['slevel']);
            
            if ($row1['oid']==89 or $slevel[6] >= 5) {
                $qry = "UPDATE jobs SET digdate=null,digsec=".(int) $row1['secid']." WHERE officeid=".(int) $row0['oid']." AND jobid='".$row0['jobid']."';";
                $res = mssql_query($qry);
                
                $out['error']=false;
                $out['result']='Dig Date Cleared';
            }
            else {
                $out['error']=true;
                $out['result']='Unauthorized '.__LINE__;
            }
        }
        else {
            $out['error']=true;
            $out['result']='JOBID: '.$tar['jobid'].' Not Found'.__LINE__;
        }
    }
    else {
        $out['error']=true;
        $out['result']='Invalid Parameters: '.__LINE__;
    }

    return $out;
}

function PullStoreCommissions($oid,$jobid) {
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	$out=array();
    $out['error']=true;
    $out['result']='Not Processed '.__LINE__;
    
	$qry0 = "SELECT hid,cbtype FROM CommissionHistory WHERE oid=".$oid." AND jobid='".$jobid."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 == 0) {
		try {
			$qry1a = "SELECT * FROM CommissionSchedule WHERE oid=".$oid." AND jobid='".$jobid."';";
			$res1a = mssql_query($qry1a);
			$nrow1a= mssql_num_rows($res1a);
			
			///echo $qry1a.'<br>';
			
			if ($nrow1a > 0) {
				$dr			=0;
				$phsid		=4;
				$commdata	=array();
				$qry1aA = "SELECT cid FROM jest..cinfo WHERE officeid=".$oid." AND jobid='".$jobid."';";
				$res1aA = mssql_query($qry1aA);
				$row1aA = mssql_fetch_array($res1aA);
				
				$qry1aB = "SELECT officeid,jobid,njobid,digdate,sidm FROM jobs as J WHERE officeid=".$oid." AND jobid='".$jobid."';";
				$res1aB = mssql_query($qry1aB);
				$row1aB = mssql_fetch_array($res1aB);
				$nrow1aB= mssql_num_rows($res1aB);
				
				$crate=0;
				$destxt='';
				while ($row1a = mssql_fetch_array($res1a))
				{
					if ($row1a['jadd']==0)
					{
						if ($row1a['cbtype']==0) //Manual Adjust
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRM ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'M');
						}
						elseif ($row1a['cbtype']==2) // O/U Comm
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRO ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'O');
						}
						elseif ($row1a['cbtype']==4) //Sales Manager
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SMC ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'S');
						}
						elseif ($row1a['cbtype']==6) //Bullets
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRU ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'U');
						}
						elseif ($row1a['cbtype']==8) //Override
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SOV ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'V');
						}
						elseif ($row1a['cbtype']==9) //Merit
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'STU ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'T');
						}
						else //Base Comm, etc
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRC ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'C');
						}
					}
					else
					{
						$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),('SRA '.$row1a['jadd']),$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'N');
					}
				}
			}
			else
			{
				//throw new Exception('Error: Job Not Found');
                $out['error']=true;
                $out['result']='Error: Job Not Found: '.__LINE__;
			}
	
			if (count($commdata) > 0) {				
				$pc=0;
				//echo 'Processing....<br />';
				foreach ($commdata as $cn => $cv) {
					$qry2 = "INSERT INTO CommissionHistory (drid,oid,njobid,jobid,jadd,secid,amt,trandate,descrip,cid,cbtype,rate,ratetype,htype,uid) VALUES ($cv[0],$cv[1],'".$cv[2]."','".$cv[3]."',$cv[4],$cv[5],$cv[6],'".$cv[7]."','".$cv[8]."',$cv[9],$cv[10],$cv[11],$cv[12],'".$cv[13]."',".$_SESSION['securityid'].");";
					$res2 = mssql_query($qry2);
					$pc++;
				}
				
				if ($pc!=count($commdata)) {
					//throw new Exception('Error: Data Process Miscount');
                    $out['error']=true;
                    $out['result']='Error: Data Process Miscount '.__LINE__;
				}
				else {
                    $out['error']=false;
                    $out['result']='Commissions Added '.__LINE__;
					//throw new Exception($pc.' Commissions Added');
				}
			}
		}
		catch (Exception $e) {
			//echo 'Output: '.$e->getMessage();
            $out['error']=true;
            $out['result']='Error: Comm Schedule Not Found: '.__LINE__;
		}
	}
	else
	{
		//echo $nrow0.' Commissions Exist. New Commissions not Added<br>';
        $out['error']=true;
        $out['result']='Error: Commissions Exist: '.__LINE__;
	}
    
    return $out;
}

function StoreCommissionHistory($oid,$jobid,$upd) {
    $out=0;
	error_reporting(E_ALL);
	ini_set('display_errors','On');
    
    if ($upd) {
        DeleteCommissionHistory($oid,$jobid);
    }

    try {
        $qry1a = "SELECT * FROM CommissionSchedule WHERE oid=".(int) $oid." AND jobid='".$jobid."';";
        $res1a = mssql_query($qry1a);
        $nrow1a= mssql_num_rows($res1a);
        
        ///echo $qry1a.'<br>';
        
        if ($nrow1a > 0) {
            $dr			=0;
            $phsid		=4;
            $commdata	=array();
            $qry1aA = "SELECT cid FROM jest..cinfo WHERE officeid=".(int) $oid." AND jobid='".$jobid."';";
            $res1aA = mssql_query($qry1aA);
            $row1aA = mssql_fetch_array($res1aA);
            
            $qry1aB = "SELECT officeid,jobid,njobid,digdate,sidm FROM jobs as J WHERE officeid=".(int) $oid." AND jobid='".$jobid."';";
            $res1aB = mssql_query($qry1aB);
            $row1aB = mssql_fetch_array($res1aB);
            $nrow1aB= mssql_num_rows($res1aB);
            
            $crate=0;
            $destxt='';
            while ($row1a = mssql_fetch_array($res1a)) {
                if ($row1a['jadd']==0) {
                    if ($row1a['cbtype']==0)  {//Manual Adjust
                        $commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRM ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'M');
                    }
                    elseif ($row1a['cbtype']==2) {// O/U Comm
                        $commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRO ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'O');
                    }
                    elseif ($row1a['cbtype']==4) {//Sales Manager
                        $commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SMC ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'S');
                    }
                    elseif ($row1a['cbtype']==6) {//Bullets
                        $commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRU ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'U');
                    }
                    elseif ($row1a['cbtype']==8) {//Override
                        $commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SOV ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'V');
                    }
                    elseif ($row1a['cbtype']==9) {//Merit
                        $commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'STU ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'T');
                    }
                    else {//Base Comm, etc
                        $commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRC ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'C');
                    }
                }
                else {
                    $commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),('SRA '.$row1a['jadd']),$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'N');
                }
            }
            
            /*// Sales Manager Commission Data, If any
            $qry1b = "SELECT * FROM jdetail as J WHERE officeid=".$oid." AND jobid='".$jobid."' AND jadd=(select MAX(jadd) from jdetail where officeid=J.officeid and jobid=J.jobid and post_add=0);";
            $res1b = mssql_query($qry1b);
            $row1b = mssql_fetch_array($res1b);
            $nrow1b= mssql_num_rows($res1b);
            
            //Sales Rep Commission Line
            //$commdata[]=array($dr,$row1a['officeid'],$row1a['njobid'],$row1a['jobid'],$row1b['jadd'],$row1a['securityid'],number_format(($row1a['comm'] + $row1a['ovcommission']),2,'.',''),date('m/d/Y',strtotime($row1a['digdate'])),'SR Comm',$row1aA['cid']);
            
            if ($nrow1b > 0)
            {
                $dojt =explode(',',$row1b['costdata_l']);
                $pdojt=explode(',',$row1b['pcostdata_l']);
                
                foreach ($dojt as $don => $dov)
                {
                    $dijt=explode(':',$dov);
                    
                    $ddesc='';
                    if ($dijt[8]==$phsid)
                    {
                        //Sales Manager Commission Line, If any
                        $ddesc='SMC';
                        $commdata[]=array($dr,$row1b['officeid'],$row1b['njobid'],$jobid,$row1b['jadd'],$row1aB['sidm'],number_format($dijt[3],2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),$ddesc,$row1aA['cid'],4,0,1,'C');
                    }
                }
                
                foreach ($pdojt as $pdon => $pdov)
                {
                    $pdijt=explode(':',$pdov);
                    
                    $pdesc='';
                    if ($pdijt[8]==$phsid)
                    {
                        //Sales Manager Commission Line, If any
                        $pdesc='SMC';
                        $commdata[]=array($dr,$row1b['officeid'],$row1b['njobid'],$jobid,$row1b['jadd'],$row1aB['sidm'],number_format($pdijt[3],2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),$pdesc,$row1aA['cid'],4,0,1,'C');
                    }
                }
            }*/
        }
        else {
            $out=__LINE__;
        }

        if (count($commdata) > 0) {
            foreach ($commdata as $cn => $cv) {
                $qry2 = "INSERT INTO CommissionHistory (drid,oid,njobid,jobid,jadd,secid,amt,trandate,descrip,cid,cbtype,rate,ratetype,htype,uid) VALUES ($cv[0],$cv[1],'".$cv[2]."','".$cv[3]."',$cv[4],$cv[5],$cv[6],'".$cv[7]."','".$cv[8]."',$cv[9],$cv[10],$cv[11],$cv[12],'".$cv[13]."',".$_SESSION['securityid'].");";
                $res2 = mssql_query($qry2);
            }
        }
    }
    catch (Exception $e)
    {
        $out=$e->getMessage();
    }
    
    return $out;
}

function PullandStoreSingleCommission($o,$j)
{
	//echo 'Entering...';
	try
    {
		$qry1a = "SELECT officeid,njobid,jobid,securityid,sidm,custid,digdate,comm,ovcommission FROM jobs WHERE officeid=".$o." AND jobid='".$j."';";
		$res1a = mssql_query($qry1a);
		$row1a = mssql_fetch_array($res1a);
		$nrow1a= mssql_num_rows($res1a);
		
		if ($nrow1a > 0)
		{
			$dr=0;
			$phsid=4;
			$qry1aA = "SELECT cid FROM jest..cinfo WHERE officeid=".$row1a['officeid']." AND jobid='".$row1a['jobid']."';";
			$res1aA = mssql_query($qry1aA);
			$row1aA = mssql_fetch_array($res1aA);
			
			$qry1b = "SELECT * FROM jdetail as J WHERE officeid=".$row1a['officeid']." AND jobid='".$row1a['jobid']."' AND jadd=(select MAX(jadd) from jdetail where officeid=J.officeid and jobid=J.jobid and post_add=0);";
			$res1b = mssql_query($qry1b);
			$row1b = mssql_fetch_array($res1b);
			$nrow1b= mssql_num_rows($res1b);
			
			//Sales Rep Commission Line
			$commdata[]=array(
								$dr,
								$row1a['officeid'],
								$row1a['njobid'],
								$row1a['jobid'],
								$row1b['jadd'],
								$row1a['securityid'],
								number_format(($row1a['comm'] + $row1a['ovcommission']),2,'.',''),
								date('m/d/Y',strtotime($row1a['digdate'])),
								'SRC',
								$row1aA['cid'],
								'C',
								1,
								1
							);
			
			if ($nrow1b > 0)
			{
				$dojt =explode(',',$row1b['costdata_l']);
				$pdojt=explode(',',$row1b['pcostdata_l']);
				
				
				foreach ($dojt as $don => $dov)
				{
					$dijt=explode(':',$dov);
					
					if ($dijt[8]==$phsid)
					{
						//Sales Manager Commission Line, If any
						$commdata[]=array(
											$dr,
											$row1a['officeid'],
											$row1a['njobid'],
											$row1a['jobid'],
											$row1b['jadd'],
											$row1a['sidm'],
											number_format($dijt[3],2,'.',''),
											date('m/d/Y',strtotime($row1a['digdate'])),
											'SMC',
											$row1aA['cid'],
											'C',
											4,
											1
										);
					}
				}
				
				foreach ($pdojt as $pdon => $pdov)
				{
					$pdijt=explode(':',$pdov);
					
					if ($pdijt[8]==$phsid)
					{
						//Sales Manager Commission Line, If any
						$commdata[]=array(
											$dr,
											$row1a['officeid'],
											$row1a['njobid'],
											$row1a['jobid'],
											$row1b['jadd'],
											$row1a['sidm'],
											number_format($pdijt[3],2,'.',''),
											date('m/d/Y',strtotime($row1a['digdate'])),
											'SMC',
											$row1aA['cid'],
											'C',
											4,
											1
										);
					}
				}
			}
		}
		else
		{
			throw new Exception('Error: Job Not Found');
		}

		if (count($commdata) > 0)
		{
			$pc=0;
			//echo 'Processing....<br />';
			foreach ($commdata as $cn => $cv)
			{
				$qry2 = "INSERT INTO CommissionHistory (drid,oid,njobid,jobid,jadd,secid,amt,trandate,descrip,cid,uid,htype,cbtype,ratetype) VALUES ($cv[0],$cv[1],'".$cv[2]."','".$cv[3]."',".$cv[4].",".$cv[5].",".$cv[6].",'".$cv[7]."','".$cv[8]."',".$cv[9].",".$_SESSION['securityid'].",'".$cv[10]."',".$cv[11].",".$cv[12].");";
				$res2 = mssql_query($qry2);
				
				//echo $qry2.'<br>';
				//echo $qry1b.'<br>';
				$pc++;
			}
			
			if ($pc!=count($commdata))
			{
				throw new Exception('Error: Data Process Miscount');
			}
			/*else
			{
				throw new Exception($pc.' Commissions Added');
			}*/
		}
	}
    catch (Exception $e)
    {
        echo 'Output: '.$e->getMessage();
    }
}

function getConstructionDates() {
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
    $jid=(isset($_REQUEST['jid']) and $_REQUEST['jid']!=0)?$_REQUEST['jid']:null;
    
    if (!is_null($jid) and $jid!=0) {
        $qry0 = "SELECT
                    custid
                    ,officeid
                    ,jobid
                    ,njobid
                    ,(select name from offices where officeid=J.officeid) as oname
                    ,(select contractamt from jdetail where jobid=J.jobid and jadd=0) as contractamt
                    ,(select contractdate from jdetail where jobid=J.jobid and jadd=0) as contractdate
                    ,(select saddr1 from cinfo where cid=J.custid) as saddr1
                    ,(select scity from cinfo where cid=J.custid) as scity
                    ,(select sstate from cinfo where cid=J.custid) as sstate
                    ,(select szip1 from cinfo where cid=J.custid) as szip1
                    ,digdate
                FROM
                    jest..jobs AS J
                WHERE
                    J.jobid='".(string) $jid."';";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        $nrow0= mssql_num_rows($res0);
        
        $digdate=(isset($row0['digdate']) and strtotime($row0['digdate']) >= strtotime('1/1/2005'))?date('m/d/Y',strtotime($row0['digdate'])):'';
        
        $dgrpt=0;
        $drpt_lbl='';
        if (isset($row0['digdate']) and strtotime($row0['digdate']) >= strtotime('1/1/2005')) {
            //$jddate = date("m/d/Y", strtotime($row0['digdate']));
            $dprd_mo	= date("m", strtotime($row0['digdate']));
            $dprd_yr	= date("Y", strtotime($row0['digdate']));
            $drpt_lbl   =$dprd_mo.'/'.$dprd_yr;
            
            $qryDR	= "SELECT id FROM digreport_main WHERE officeid=".(int) $_SESSION['officeid']." AND rept_mo='".$dprd_mo."' AND rept_yr='".$dprd_yr."';";
            $resDR	= mssql_query($qryDR);
            $dgrpt	= mssql_num_rows($resDR);
            //echo $qryDR.'<br>';
        }
        
        $saddr=array('street'=>$row0['saddr1'],'city'=>$row0['scity'],'state'=>$row0['sstate'],'zip'=>$row0['szip1']);
        
        $qry0p = "SELECT officeid,enquickbooks FROM offices where officeid=".$row0['officeid'];
        $res0p = mssql_query($qry0p);
        $row0p = mssql_fetch_array($res0p);
        
        $qry1p = "SELECT officeid,constructdateaccess as cdaccess FROM security where securityid=".$_SESSION['securityid'];
        $res1p = mssql_query($qry1p);
        $row1p = mssql_fetch_array($res1p);
        
        $cdaccess=$row1p['cdaccess'];
        $qry = "SELECT
                    p.*
                    ,(select cdate from constructiondates where jobid='".(string) $row0['jobid']."' and phsid=p.phsid and dtype=1) as act_sdate
                    ,(select cdate from constructiondates where jobid='".(string) $row0['jobid']."' and phsid=p.phsid and dtype=2) as act_edate
                    ,(select cdate from constructiondates where jobid='".(string) $row0['jobid']."' and phsid=p.phsid and dtype=3) as act_rdate
                    ,(select ramt from constructiondates where jobid='".(string) $row0['jobid']."' and phsid=p.phsid and dtype=3) as act_ramt
                    ,(select TxnID from constructiondates where jobid='".(string) $row0['jobid']."' and phsid=p.phsid and dtype=3) as act_TxnID
                FROM
                    phasebase AS p
                WHERE
                    p.condate=1
                ORDER BY
                    p.seqnum ASC;
                ";
        $res = mssql_query($qry);
        $nrow= mssql_num_rows($res);
        
        //echo $qry.'<br>';
        
        if (isset($row0['renov']) and $row0['renov']==1) {
            $rtxtr='Setting a date in this field sets the Dig Date for Renovations';
            $rtxtn='';
        }
        elseif (isset($row0['renov']) and $row0['renov']==0) {
            $rtxtr='';
            $rtxtn='Setting a date in this field sets the Dig Date for New Builds';
        }
        else {
            $rtxtr='';
            $rtxtn='';
        }
        
        if ($nrow > 0 and $cdaccess > 0) {
            echo "<table align=\"center\">\n";
            echo "	<tr>\n";
            echo "		<td align=\"left\"><b>Office</b></td>\n";
            echo "		<td colspan=\"9\" align=\"left\">".$row0['oname']."</td>\n";
            echo "	</tr>\n";
            echo "	<tr>\n";
            echo "		<td align=\"left\"><b>Site Address</b></td>\n";
            echo "		<td colspan=\"9\" align=\"left\">".$saddr['street'].", ".$saddr['city'].", ".$saddr['state']." ".$saddr['zip']."</td>\n";
            echo "	</tr>\n";
            echo "	<tr>\n";
            echo "		<td colspan=\"2\" align=\"left\"></td>\n";
            echo "		<td colspan=\"3\" align=\"center\"><b>Construction</b></td>\n";
            echo "		<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";		
            echo "		<td colspan=\"3\" align=\"center\"><b>Receivable</b></td>\n";
            echo "		<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
            echo "	</tr>\n";
            echo "	<tr>\n";
            echo "		<td width=\"100px\"><b>Phase</b></td>\n";
            echo "		<td align=\"center\"><b>Code</b></td>\n";
            echo "		<td align=\"center\"><b>Scheduled</b></td>\n";
            echo "		<td><img src=\"images/pixel.gif\"></td>\n";
            echo "		<td align=\"center\"><b>Complete</b></td>\n";
            echo "		<td><img src=\"images/pixel.gif\"></td>\n";
            echo "		<td align=\"center\"><b>Date</b></td>\n";
            echo "		<td align=\"center\"><b>Amount</b></td>\n";
            echo "		<td align=\"center\"><b>Clear</b></td>\n";
            echo "		<td align=\"left\" width=\"25px\"><b>Status</b></td>\n";
            echo "	</tr>\n";
            
            $dcnt=0;
            $dramt=0;
            $pramt=0;
            $cramt=0;
            $phsseq=array();
            $phstst=array();
            $dx_phs=array(45,46,48); // Display Exclude
            $ex_phs=array(45); // Logic Exclude
            
            while ($row = mssql_fetch_array($res)) {
                $dcnt++;
                $phsseq[]=$row['phsid'];
                
                echo "	<tr id=\"phsid_".$row['phsid']."\">\n";
                echo "		<td class=\"phsname\">".$row['phsname']."</td>\n";
                echo "		<td align=\"center\">\n";
                
                if (!in_array($row['phsid'],$dx_phs)) {
                    echo $row['phscode'];
                }
                
                echo "		</td>\n";
                echo "		<td align=\"center\">\n";
                
                if (isset($row['sdate']) && $row['sdate']==1 && !in_array($row['phsid'],$ex_phs)) {
                    $act_sdate=(isset($row['act_sdate']) && strtotime($row['act_sdate']) >= strtotime('1/1/2005'))?date('m/d/Y',strtotime($row['act_sdate'])):'';
                    $tst_sdate=strtotime($act_sdate);
                    
                    if ($cdaccess >= 5) {
                        if ($row['phsid']==9) {
                            $jddate = date("m/d/Y", strtotime($row['act_sdate']));
                            $prd_mo	= date("m", strtotime($row['act_sdate']));
                            $prd_yr	= date("Y", strtotime($row['act_sdate']));
                    
                            $qryDD	= "SELECT id FROM digreport_main WHERE officeid=".(int) $_SESSION['officeid']." AND rept_mo='".$prd_mo."' AND rept_yr='".$prd_yr."';";
                            $resDD	= mssql_query($qryDD);
                            $nrowDD	= mssql_num_rows($resDD);
                            //echo $qryDD.'<br>';
        
                            if ($nrowDD >= 1)
                            {
                                echo "<div class=\"JMStooltip\" title=\"Dig Report created for this time period. Edit disabled.\">".$act_sdate."</div>\n";
                            }
                            else
                            {
                                echo "		<input type=\"text\" class=\"bboxbc datepick CDInputField\" name=\"condates[".$row['phsid']."][sdate]\" id=\"cdate_start_".$row['phsid']."\" value=\"".$act_sdate."\" size=\"9\" maxlength=\"10\" title=\"".$rtxtr."\">\n";
                            }
                        }
                        else {
                            echo "		<input type=\"text\" class=\"bboxbc datepick CDInputField\" name=\"condates[".$row['phsid']."][sdate]\" id=\"cdate_start_".$row['phsid']."\" value=\"".$act_sdate."\" size=\"9\" maxlength=\"10\">\n";
                        }
                    }
                    else {
                        if (isValidDate(date('m/d/Y',strtotime($row['act_sdate']))) and strtotime($row['act_sdate']) > strtotime('1/1/2005')) {
                            echo date('m/d/Y',strtotime($row['act_sdate']));
                        }
                    }
                    
                    echo "<span id=\"val_start_".$row['phsid']."\" style=\"display:none;\">".$act_sdate."</span>\n";
                }
                
                echo "		</td>\n";
                echo "		<td></td>\n";
                echo "		<td align=\"center\">\n";
                
                if (isset($row['edate']) && $row['edate']==1 && !in_array($row['phsid'],$ex_phs)) {
                    $act_edate=(isset($row['act_edate']) && strtotime($row['act_edate']) >= strtotime('1/1/2005'))?date('m/d/Y',strtotime($row['act_edate'])):'';
                    $tst_edate=strtotime($act_edate);
                    
                    if ($cdaccess >= 5) {
                        $dcnt++;
                        if ($row['phsid']==9) {
                            $jddate = date("m/d/Y", strtotime($row['act_edate']));
                            $prd_mo	= date("m", strtotime($row['act_edate']));
                            $prd_yr	= date("Y", strtotime($row['act_edate']));
                    
                            $qryDD	= "SELECT id FROM digreport_main WHERE officeid=".(int) $_SESSION['officeid']." AND rept_mo='".$prd_mo."' AND rept_yr='".$prd_yr."';";
                            $resDD	= mssql_query($qryDD);
                            $nrowDD	= mssql_num_rows($resDD);
                            //echo $qryDD.'<br>';
        
                            if ($nrowDD >= 1) {
                                echo "<div class=\"JMStooltip\" title=\"Dig Report created for this time period. Edit disabled.\">".$act_edate."</div>\n";
                            }
                            else {
                                echo "		<input type=\"text\" class=\"bboxbc datepick CDInputField\" name=\"condates[".$row['phsid']."][edate]\" id=\"cdate_end_".$row['phsid']."\" value=\"".$act_edate."\" size=\"9\" maxlength=\"10\" title=\"".$rtxtn."\">\n";
                            }
                        }
                        else {
                            echo "		<input type=\"text\" class=\"bboxbc datepick CDInputField\" name=\"condates[".$row['phsid']."][edate]\" id=\"cdate_end_".$row['phsid']."\" value=\"".$act_edate."\" size=\"9\" maxlength=\"10\">\n";
                        }
                    }
                    else {
                        if (isValidDate(date('m/d/Y',strtotime($row['act_edate']))) and strtotime($row['act_edate']) > strtotime('1/1/2005')) {
                            echo date('m/d/Y',strtotime($row['act_edate']));
                        }
                    }
                    
                    echo "<span id=\"val_end_".$row['phsid']."\" style=\"display:none;\">".$act_edate."</span>\n";
                }
                
                echo "		</td>\n";
                echo "		<td></td>\n";
                echo "		<td align=\"center\">\n";
                
                if (isset($row['rdate']) && $row['rdate']==1 && !in_array($row['phsid'],$ex_phs)) {
                    $act_rdate=(isset($row['act_rdate']) && strtotime($row['act_rdate']) >= strtotime('1/1/2005'))?date('m/d/Y',strtotime($row['act_rdate'])):'';
                    $tst_rdate=strtotime($act_rdate);
                    
                    if ($cdaccess >= 5) {
                        $dcnt++;
                        echo "<input type=\"text\" class=\"bboxbc datepick CDInputField\" name=\"condates[".$row['phsid']."][rdate]\" id=\"cdate_recv_".$row['phsid']."\" value=\"".$act_rdate."\" size=\"9\" maxlength=\"10\">\n";
                        echo "<span id=\"val_recv_".$row['phsid']."\" style=\"display:none;\">".$act_rdate."</span>\n";
                    }
                    else {
                        if ($row['phsid']==45) {
                            if (isValidDate(date('m/d/Y',strtotime($row0['contractdate']))) and strtotime($row0['contractdate']) > strtotime('1/1/2005'))
                            {
                                echo date('m/d/Y',strtotime($row0['contractdate']));
                            }
                        }
                        else {
                            if (isValidDate(date('m/d/Y',strtotime($row['act_rdate']))) and strtotime($row['act_rdate']) > strtotime('1/1/2005'))
                            {
                                echo date('m/d/Y',strtotime($row['act_rdate']));
                            }
                        }
                    }
                }
                else {
                    if ($row['phsid']==45) {
                        if (isValidDate(date('m/d/Y',strtotime($row0['contractdate']))) and strtotime($row0['contractdate']) > strtotime('1/1/2005')) {
                            if ($row1p['officeid']==89) {
                                echo '<a href="#" id="editContractDate" title="Edit Contract Date">'.date('m/d/Y',strtotime($row0['contractdate'])).'</a>';
                            }
                            else {
                                echo date('m/d/Y',strtotime($row0['contractdate']));
                            }
                        }
                    }
                    else {
                        if (isValidDate(date('m/d/Y',strtotime($row['act_rdate']))) and strtotime($row['act_rdate']) > strtotime('1/1/2005')) {
                            echo date('m/d/Y',strtotime($row['act_rdate']));
                        }
                    }
                }
                
                echo "		</td>\n";
                echo "		<td align=\"right\">\n";
                
                if (isset($row['rdate']) && $row['rdate']==1  && !in_array($row['phsid'],$ex_phs)) {
                    $act_ramt=(isset($row['act_ramt']) && $row['act_ramt'] > 0)?number_format($row['act_ramt'],2,'.',''):'0.00';
                    
                    if ($cdaccess >= 5) {
                        echo "<input type=\"text\" class=\"bboxbr formatCurrency CDCurrencyField\" name=\"condates[".$row['phsid']."][ramt]\" id=\"cdate_ramt_".$row['phsid']."\" value=\"".$act_ramt."\" size=\"8\" maxlength=\"10\">\n";
                        echo "<span id=\"val_ramt_".$row['phsid']."\" style=\"display:none;\">".$act_ramt."</span>\n";
                    }
                    else {
                        echo number_format($row['act_ramt'],2,'.','');
                    }
                    
                    $pramt=$pramt + $act_ramt;
                }
                else {
                    if ($row['phsid']==45) {
                        if (isValidDate(date('m/d/Y',strtotime($row0['contractdate'])))) {
                            echo '<span id="ContractAmt">'.number_format($row0['contractamt'],2,'.','').'</span>';
                            $cramt=	number_format($row0['contractamt'],2,'.','');
                        }
                    }
                }
                
                echo "		</td>\n";
                echo "		<td align=\"center\">\n";
                
                if ($cdaccess >= 5) {
                    if (!in_array($row['phsid'],$ex_phs) and ((isset($tst_sdate) and $tst_sdate!=0) or (isset($tst_edate) and $tst_edate!=0) or (isset($tst_rdate) and $tst_rdate!=0))) {
                        echo "<img class=\"setpointer ClearDateLine\" id=\"clear_".$row['phsid']."\" src=\"../images/action_delete.gif\">";
                    }
                }
                
                echo "		</td>\n";
                echo "		<td align=\"left\" class=\"clear_phase_status\" id=\"status_".$row['phsid']."\"></td>\n";
                echo "	</tr>\n";
            }
            
            //Addendum Loop		
            $qry9  = "SELECT jobid,jadd,raddnpr_man,psched_adj,added FROM jdetail WHERE officeid=".$_SESSION['officeid']." and jobid='".$row0['jobid']."' and jadd >= 1;";
            $res9  = mssql_query($qry9);
            $nrow9 = mssql_num_rows($res9);
            
            if ($nrow9 > 0) {
                while ($row9  = mssql_fetch_array($res9)) {
                    $cramt=$cramt+$row9['psched_adj'];
                    echo "	<tr>\n";
                    echo "		<td>Addn</td>\n";
                    echo "		<td>". (600 + $row9['jadd']) ."L</td>\n";
                    echo "		<td></td>\n";
                    echo "		<td></td>\n";
                    echo "		<td></td>\n";
                    echo "		<td></td>\n";
                    echo "		<td></td>\n";
                    echo "		<td align=\"right\" class=\"adj_line_amt\">".number_format($row9['psched_adj'],2,'.','')."</td>\n";
                    echo "		<td></td>\n";
                    echo "	</tr>\n";
                }
            }
            
            echo "	<tr>\n";
            ///echo "		<td colspan=\"6\"></td>\n";
            echo "		<td colspan=\"9\"><hr width=\"100%\"></td>\n";
            echo "		<td></td>\n";
            echo "	</tr>\n";
            
            echo "	<tr>\n";
            
            if ($row1p['officeid']==89 or $_SESSION['jlev']>=5) {
                echo "		<td>Dig Date</td>\n";
                echo "		<td></td>\n";
                
                if ($dgrpt==0) {
                    echo "		<td><input type=\"text\" class=\"bboxbc datepick_dd\" name=\"setDigDate\" id=\"setDigDate\" value=\"".$digdate."\" size=\"9\" maxlength=\"10\"><span id=\"val_DigDate\" style=\"display:none;\">".$digdate."</span></td>\n";
                    echo "		<td><img class=\"setpointer ClearDigDateLine\" id=\"clearDigDate\" src=\"../images/action_delete.gif\" title=\"Clear Dig Date\"></td>\n";
                    echo "		<td></td>\n";
                }
                else {
                    echo "		<td>".$digdate."</td>\n";
                    echo "		<td colspan=\"2\"><span class=\"redtext\" title=\"Dig Date LOCKED: Dig Report created for this time period\">! Dig Report !</span></td>\n";
                }
            }
            else {
                echo "		<td>Dig Date</td>\n";
                echo "		<td></td>\n";
                echo "		<td>".$digdate."</td>\n";
                echo "		<td colspan=\"2\"></td>\n";
            }

            echo "		<td colspan=\"2\" align=\"right\"><b>Total Received<b></td>\n";
            echo "		<td align=\"right\" id=\"total_recv\">".number_format($pramt,2,'.','')."</td>\n";
            echo "		<td></td>\n";
            echo "		<td></td>\n";
            echo "	</tr>\n";            
            echo "	<tr>\n";
            echo "		<td></td>\n";
            echo "		<td colspan=\"4\"><span id=\"CDUpdateStatus\"></span></td>\n";
            echo "		<td colspan=\"2\" align=\"right\"><b>Total Due<b></td>\n";
            echo "		<td align=\"right\" id=\"total_due\">".number_format(($cramt - $pramt),2,'.','')."</td>\n";
            echo "		<td></td>\n";
            echo "		<td align=\"center\">\n";            
            echo "		</td>\n";
            echo "	</tr>\n";
            echo "</table>\n";
            //print_r($phsseq);
        }
    }
	ini_set('display_errors','Off');
}

function smallSiteAddress($cid) {
    $out='';
    
    return $out;
}

function is_base64_encoded($d) {
    return (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $d))?true:false;
}

function getOneSheetComments($cid=null) {
	$out='';
	$cid=(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:((isset($cid) and !is_null($cid))?$cid:0);   
    
	if ($cid!=0) {
        $car=array();
		$qry0 = "SELECT c.*
                    ,(SELECT lname FROM security WHERE securityid=c.secid) as slname
                    ,(SELECT fname FROM security WHERE securityid=c.secid) as sfname
                FROM chistory AS c WHERE c.custid=".(int) $cid." ORDER BY c.mdate DESC;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
        
        while ($row0 = mssql_fetch_array($res0)) {
            $car[]=array(strtotime($row0['mdate']),
                array(
                'id'=>$row0['id'],
                'act'=>$row0['act'],
                'mtext'=>$row0['mtext'],
                'mdate'=>$row0['mdate'],
                'cservice'=>$row0['cservice'],
                'complaint'=>$row0['complaint'],
                'relatedcomplaint'=>$row0['relatedcomplaint'],
                'resolved'=>$row0['resolved'],
                'followup'=>$row0['followup'],
                'slname'=>$row0['slname'],
                'sfname'=>$row0['sfname'],
                'sort'=>strtotime($row0['mdate'])
            ));
        }
        
        $qry1 = "SELECT
                    c.*
                    ,(SELECT lname FROM security WHERE securityid=c.sid) as slname
                    ,(SELECT fname FROM security WHERE securityid=c.sid) as sfname
                FROM
                    construction_comments AS c WHERE c.cid=".(int) $cid." ORDER BY c.mdate DESC;";
        $res1 = mssql_query($qry1);
        $nrow1= mssql_num_rows($res1);
        
        while ($row1 = mssql_fetch_array($res1)) {
            $car[]=array(strtotime($row1['mdate']),
                array(
                'id'=>$row1['ccid'],
                'act'=>$row1['act'],
                'mtext'=>$row1['mtext'],
                'mdate'=>$row1['mdate'],
                'cservice'=>0,
                'complaint'=>0,
                'relatedcomplaint'=>0,
                'resolved'=>0,
                'followup'=>0,
                'slname'=>$row1['slname'],
                'sfname'=>$row1['sfname'],
                'sort'=>strtotime($row1['mdate'])
            ));
        }
        
        ///echo '<table><tr><td><pre>';
        //print_r($car);
        //echo '</pre></td></tr></table>';
        //exit;
        rsort($car);
        
		if (count($car) > 0)
		{
            $resfol=array();
            
            foreach ($car as $rn=>$rv) {
                if ($rv[1]['resolved']==1) {
                    $resfol[]=$rv[1]['relatedcomplaint'];
                }
            }
            
            //print_r($resfol);
            
			$tsize=75;
			$detect_ar=array(
				'/=C2=A0/',
				'/=C2=B7/',
				'/=C2/',
				'/C2=/',
				'/=A0/',
				'/=0A/',
				'/A0/',
				'/0A/',
				'/=20/',
				'/= /',
				'/ =/',
				'/=/');
				
			$replace_ar=array('','','','','','','','','',' ',' ',' ');
			$out=$out."<table width=\"100%\">\n";
            $out=$out."	<tr>\n";
			$out=$out."		<td align=\"left\"><b>Comments</b></td>\n";
            $out=$out."     <td align=\"right\">\n";
            $out=$out."         <div class=\"radio-toolbar\">\n";
            $out=$out."             <label id=\"openCommentDialog\" class=\"pointer\" title=\"Click to Add Comment\">Add Comment</label>\n";
            $out=$out."         </div>\n";
            $out=$out."     </td>\n";
			$out=$out."	</tr>\n";
            $out=$out."</table>\n";
            $out=$out."<table width=\"100%\">\n";
            $out=$out." <tr>\n";
            $out=$out."     <td align=\"left\">\n";
            $out=$out."         <div class=\"radio-toolbar\">\n";
            $out=$out."             <input type=\"radio\" id=\"radio1\" class=\"osCmntCntrl\" name=\"osCmntCntrl\" value=\"A\" checked>\n";
            $out=$out."             <label for=\"radio1\" class=\"btnCmntCntrl pointer\">All</label>\n";
            $out=$out."             <input type=\"radio\" id=\"radio2\" class=\"osCmntCntrl\" name=\"osCmntCntrl\" value=\"L\">\n";
            $out=$out."             <label for=\"radio2\" class=\"btnCmntCntrl pointer\">Lead</label>\n";
            $out=$out."             <input type=\"radio\" id=\"radio3\" class=\"osCmntCntrl\" name=\"osCmntCntrl\" value=\"C\">\n";
            $out=$out."             <label for=\"radio3\" class=\"btnCmntCntrl pointer\">Construction</label>\n";
            $out=$out."             <input type=\"radio\" id=\"radio4\" class=\"osCmntCntrl\" name=\"osCmntCntrl\" value=\"S\">\n";
            $out=$out."             <label for=\"radio4\" class=\"btnCmntCntrl pointer\">Service/Complaint</label>\n";
            $out=$out."             <input type=\"radio\" id=\"radio5\" class=\"osCmntCntrl\" name=\"osCmntCntrl\" value=\"R\">\n";
            $out=$out."             <label for=\"radio5\" class=\"btnCmntCntrl pointer\">Email Response</label>\n";
            $out=$out."         </div>\n";
            $out=$out."     </td>\n";
            $out=$out."     <td align=\"right\">\n";
            $out=$out."         <div class=\"radio-toolbar\">\n";
            $out=$out."             <input type=\"radio\" id=\"refreshOneSheetComments\" class=\"osCmntDsp\" name=\"osCmntDsp\" value=\"refresh\">\n";
            $out=$out."             <label for=\"refreshOneSheetComments\" class=\"btnCmntCntrl pointer\">Refresh</label>\n";
            $out=$out."             <input type=\"radio\" id=\"expandOneSheetComments\" class=\"osCmntDsp\" name=\"osCmntDsp\" value=\"expand\">\n";
            $out=$out."             <label for=\"expandOneSheetComments\" class=\"btnCmntCntrl pointer\">Expand All</label>\n";
            $out=$out."         </div>\n";
            $out=$out."     </td>\n";
            $out=$out." </tr>\n";
            $out=$out."</table>\n";
            $out=$out."<table width=\"100%\">\n";
			$out=$out."	<tr>\n";
			$out=$out."		<td align=\"left\" width=\"90px\"><b>Date</b></td>\n";
			$out=$out."		<td align=\"left\" width=\"30px\"><b>Name</b></td>\n";
			$out=$out."		<td align=\"center\" width=\"30px\"><b>Stage</b></td>\n";
			$out=$out."		<td align=\"center\" width=\"30px\"><b>Ticket</b></td>\n";
			$out=$out."		<td align=\"left\"><b>Comments</b></td>\n";
			$out=$out."	</tr>\n";
		
			$cmntcnt=0;

            foreach ($car as $cn=>$cv) {
				$cmntcnt++;
				$stage='';
				$cmt_tbg=($cmntcnt%2)?'even':'odd';
				
				if ($cv[1]['act']=="leads")
				{
					$stage="<div title=\"Lead\">L</div>";
				}
				elseif ($cv[1]['act']=="est")
				{
					$stage="<div title=\"Estimate\">E</div>";
				}
				elseif ($cv[1]['act']=="contract")
				{
					$stage="<div title=\"Contract\">C</div>";
				}
				elseif ($cv[1]['act']=="jobs")
				{
					$stage="<div title=\"Job\">J</div>";
				}
				elseif ($cv[1]['act']=="mas")
				{
					$stage="<div title=\"MAS\">M</div>";
				}
				elseif ($cv[1]['act']=="reports")
				{
					$stage="<div title=\"Reports\">R</div>";
				}
				elseif ($cv[1]['act']=="fin")
				{
					$stage="<div title=\"Finance\">F</div>";
				}
				elseif ($cv[1]['act']=="Complaint")
				{
					$stage="<div title=\"Complaint\">CP</div>";
					$cmt_tbg="ltred";
				}
				elseif ($cv[1]['act']=="Service")
				{
					$stage="<div title=\"Service\">SR</div>";
					$cmt_tbg="ltblue";
				}
				elseif ($cv[1]['act']=="Followup")
				{
					$stage="<div title=\"Followup\">FL</div>";
					
					if ($cv[1]['complaint']!=0)
					{
						$cmt_tbg="ltred";
					}
					elseif($cv[1]['cservice']!=0)
					{
						$cmt_tbg="ltblue";
					}
					else
					{
						$cmt_tbg='';
					}
				}
				elseif ($cv[1]['act']=="Resolved")
				{
					$stage="<div title=\"Resolved\">RS</div>";					
					$cmt_tbg="ltgrn";
				}
				elseif ($cv[1]['act']=="cresp")
				{
					$stage="<div title=\"Email Response\">ER</div>";
				}
                elseif ($cv[1]['act']=="Construction")
				{
					$stage="<div title=\"Construction\">CC</div>";
				}
				
				if ($cv[1]['act']=='cresp')
				{
					if (is_base64_encoded($cv[1]['mtext']))
					{
						$mtext=strip_tags(preg_replace($detect_ar,$replace_ar,base64_decode($cv[1]['mtext'])));
					}
					else
					{
						$mtext=strip_tags(preg_replace($detect_ar,$replace_ar,$cv[1]['mtext']));
					}
                    
                    $mt_ar=array('/----- Original Message -----/i','/Blue Haven Pools & Spas/i');
					$nmtext=preg_split('/----- Original Message -----/i',$mtext);
					$mtext=$nmtext[0];
                    
                    $nmtext1=preg_split('/Blue Haven Pools & Spas/i',$mtext);
					$mtext=$nmtext1[0];
				}
				else
				{
					$mtext=htmlspecialchars_decode(preg_replace($detect_ar,$replace_ar,$cv[1]['mtext']));
				}
		
				$out=$out."	<tr class=\"".$cmt_tbg." disp_".$cv[1]['act']." cmnt_disp\">\n";
				$out=$out."		<td align=\"left\" valign=\"top\" NOWRAP><table width=\"100%\"><tr><td align=\"left\">".date('m/d/y',strtotime($cv[1]['mdate']))."</td><td align=\"right\">".date('g:ia',strtotime($cv[1]['mdate']))."</td></tr></table></td>\n";
				$out=$out."		<td align=\"center\" valign=\"top\" title=\"".trim($cv[1]['sfname'])." ".trim($cv[1]['slname'])."\" NOWRAP>".substr($cv[1]['sfname'],0,2)." ".substr($cv[1]['slname'],0,6)."</td>\n";
				$out=$out."		<td align=\"center\" valign=\"top\" NOWRAP>".$stage."</td>\n";
				$out=$out."		<td align=\"left\" valign=\"top\">\n";
				
				if ($cv[1]['complaint']==1 or $cv[1]['cservice']==1) {

                    $scs=strtolower($cv[1]['act']);
                    
                    if (in_array($cv[1]['id'],$resfol)) {
                        $out=$out.$cv[1]['id'];
                    }
                    elseif (in_array($cv[1]['relatedcomplaint'],$resfol)) {
                        $out=$out.$cv[1]['relatedcomplaint'];
                    }
                    elseif ($cv[1]['relatedcomplaint']!=0) {
                        $out=$out.$cv[1]['relatedcomplaint'];
					}
					else {
						$out=$out.'<span class="resfolOSComment setpointer '.$scs.'" style="color:blue;">'.$cv[1]['id'].'</span>';
					}
				}
		
				$out=$out."		</td>\n";
                $out=$out."		<td align=\"left\">\n";
				
				if (strlen($cv[1]['mtext']) > $tsize) {
					$out=$out."<span class=\"texpandtext setpointer\" title=\"Click to Expand\">".substr($mtext,0,$tsize)." ...</span><span class=\"thiddentext\" style=\"display: none\">".$mtext."</span>\n";
				}
				else {
					$out=$out.$mtext;
				}

				$out=$out."		</td>\n";				
				$out=$out."	</tr>\n";
			}
			
			$out=$out."</table>\n";
		}
		else
		{
			$out=$out."<table width=\"100%\">\n";
			$out=$out."	<tr>\n";
			$out=$out."		<td align=\"left\">No Customer Comments</td>\n";
			$out=$out."	</tr>\n";
			$out=$out."</table>\n";
		}
	}

	return $out;
}
