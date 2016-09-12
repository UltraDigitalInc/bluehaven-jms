<?php

function OneSheetDisplay() {
    //echo __FUNCTION__.'<br>';
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    
    $oid=(isset($_SESSION['officeid']) and $_SESSION['officeid']!=0)?$_SESSION['officeid']:0;
    $sid=(isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)?$_SESSION['securityid']:0;
    $cid=(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
    
    if ($cid==0 or $oid==0 or $sid==0) {die('Invalid Request (' . __LINE__.')');}
    
    $sdata	=getOneSheetSecurity($oid,$sid);
    $cdata	=getOneSheetCustomer($cid,$sdata);
	$osm	=OneSheetMenu();
	$osc	=OneSheetContact($cdata);
	$osl	=OneSheetLifeCycle($cdata,$sdata);
	$ocd	=OneSheetConstructionDatesStub($cdata);
    
    //echo '<table><tr><td><pre>';
    //print_r($cdata);
    //echo '</pre></td></tr></table>';
	//exit;

	echo "<script type=\"text/javascript\" src=\"js/jquery_onesheet_new.js?".time()."\"></script>\n";
	echo "<input type=\"hidden\" id=\"acct_OID\" value=\"".$cdata['lead']['oid']."\">\n";
	echo "<input type=\"hidden\" id=\"usr_cid\" value=\"".$cdata['lead']['cid']."\">\n";
	echo "<table id=\"tblWrap\" width=\"950px\" style=\"display:none\">\n";
	echo "   <tr>\n";
	echo "      <td align=\"right\" colspan=\"2\">\n";
	
	echo $osm;
	
	echo "		</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td align=\"left\" colspan=\"2\">\n";
    echo "      <div class=\"outerrnd\">\n";
	echo "			<table width=\"100%\">\n";
	echo " 			  <tr>\n";
	echo "				<td align=\"left\" valign=\"bottom\"><b>Customer OneSheet</b></td>\n";
	echo "				<td align=\"right\" valign=\"bottom\"><b>\n";
	
	?>
        
    <script type="text/javascript">
        setLocalTime();
    </script>
        
    <?php
	
	echo "				</b></td>\n";
	echo "			  </tr>\n";
	echo "			</table>\n";
    echo "      </div>";
	echo "		</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td width=\"40%\" valign=\"top\">\n";
    echo "          <div class=\"outerrnd\">\n";
	
    echo $osc;
	
    echo "				<p>\n";
    echo "          </div>";
	echo "			<p>\n";
	echo "          <div class=\"outerrnd\">\n";

	echo $osl;
	
	echo "				<p>\n";
    echo "          </div>\n";
	echo "			<p>\n";
	echo "		</td>\n";
	echo "		<td width=\"60%\" valign=\"top\">\n";
    echo "      	<div class=\"outerrnd\">\n";
	echo "				<div id=\"ConstructionDates\">\n";
	
	echo $ocd;
	
	echo "				</div>\n";
	echo "				<p>\n";
    echo "      	</div>";
	echo "			<p>\n";
	echo "          <div class=\"outerrnd\">\n";
	echo "              <span id=\"OneSheetComments\"></span>\n";
	echo "				<p>\n";
    echo "          </div>";
	echo "			<p>\n";
	echo "		</td>\n";
	echo "   </tr>\n";
    echo "</table>\n";
	echo "<span id=\"finalEl\"></span>";
}

function OneSheetMenu() {
	$out='';
	$out.="<div class=\"noPrint\">\n";
	$out.="  <table align=\"right\">\n";
	$out.="      <tr>\n";

	if (isset($_SESSION['tqry']))
	{
		$out.="				<td>\n";
		$out.="         		<form name=\"tsearch1\" method=\"post\">\n";
		$out.="						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		$out.="						<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		$out.="						<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
		$out.="						<button class=\"btnsysmenu\" title=\"Return to the Last Search Results\">Results</button>\n";
		$out.="					</form>\n";
		$out.="				</td>\n";
	}

    $out.="      </tr>\n";
	$out.="  </table>\n";
	$out.="</div>\n";
	
	return $out;
}

function OneSheetConstructionDatesStub($cdata) {
	
	if (isset($cdata['jobs']) and count($cdata['jobs']) > 0) {
		$sCD='<b>Job</b> <select id="getConstrDates" autocomplete="off">';
		//$sCD.='	<option value="0">Select a Job...</option>';
		$sCD.='	<option value="0">Clear Job</option>';
		
		$i=0;
		foreach ($cdata['jobs'] as $n=>$v) {
			$seljob=($i==0)?'SELECTED':'';
			$sCD.='	<option value="'.$v['jobid'].'" '.$seljob.'>'.$v['njobid'].'</option>';
			$i++;
		}
		
		$sCD.='</select>';
	}
	else {
		$sCD='No Jobs';
	}
	
	$out  = '';
	$out .="<table width=\"100%\">\n";
	$out .="	<tr>\n";
	$out .="		<td align=\"left\"><b>Construction Dates</b></td>\n";
	$out .="		<td align=\"center\">\n";
	$out .=$sCD;
	$out .="		</td>\n";
	$out .="		<td align=\"right\">\n";
	$out .="			<div class=\"radio-toolbar\" class=\"setpointer\">\n";
	$out .="				<label id=\"osCDDisplay\" title=\"Click to Show/Hide Construction Dates\">Hide Dates</label>\n";
	$out .="			</div>\n";
	$out .="		</td>\n";
    $out .="	</tr>\n";
    $out .="</table>\n";
	$out .="<div id=\"osCDDisplayWrap\"></div>\n";
	
	return $out;
}

function OneSheetLifeCycle($cdata,$sdata) {
    $out    = '';
    $tranid=time().".".$cdata['lead']['cid'].".".$_SESSION['securityid'];
    $out=$out."						<table align=\"center\" width=\"100%\">\n";
    $out=$out."	   						<tr>\n";
    $out=$out."      						<td colspan=\"5\" align=\"left\"><b>Lifecycle Information and Control</b></td>\n";
    $out=$out."   						</tr>\n";
	$out=$out."	   					<tr>\n";
    $out=$out."      						<td align=\"left\"></td>\n";
	$out=$out."      						<td align=\"left\"></td>\n";
    $out=$out."      						<td align=\"left\"><b>Sales Rep</b></td>\n";
    $out=$out."      						<td align=\"center\"><b>Added</b></td>\n";
    $out=$out."      						<td align=\"center\"><b>Updated</b></td>\n";
    $out=$out."      						<td align=\"center\"><b>View</b></td>\n";
	$out=$out."      						<td align=\"center\"></td>\n";
    $out=$out."   					</tr>\n";

    if ($_SESSION['llev']!=0 && $cdata['lead']['cid']!=0)
    {
        $uid	=md5(session_id().time().$cdata['lead']['cid']).".".$_SESSION['securityid'];
        
        $out=$out."	   					<tr class=\"even\">\n";
        $out=$out."      						<td align=\"right\" width=\"90\"><b>Lead</b></td>\n";
        $out=$out."      						<td align=\"left\" width=\"100\">".$cdata['lead']['cid']."</td>\n";
		$out=$out."      						<td align=\"left\" width=\"100\">".$cdata['lead']['srname']."</td>\n";
        $out=$out."      						<td align=\"center\">".$cdata['lead']['sdate']."</td>\n";
        $out=$out."      						<td align=\"center\">".$cdata['lead']['udate']."</td>\n";
        $out=$out."      						<td align=\"center\">\n";
        $out=$out."                        			<form method=\"POST\">\n";
        $out=$out."                        			   <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
        $out=$out."                        			   <input type=\"hidden\" name=\"call\" value=\"view\">\n";
        $out=$out."                        			   <input type=\"hidden\" name=\"cid\" value=\"".$cdata['lead']['cid']."\">\n";
        $out=$out."                        			   <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
        $out=$out."                        			   <input class=\"transnb\" type=\"image\" src=\"images/layout.png\" alt=\"View Lead\">\n";
        $out=$out."                        			</form>\n";        
        $out=$out."								</td>\n";
		$out=$out."								<td></td>\n";
        $out=$out."   						</tr>\n";
    }

    if ($_SESSION['elev']!=0 && count($cdata['estimates']) > 0)
    {
        foreach ($cdata['estimates'] as $ek=>$ev)
        {
			$eqtype=($ev['esttype']=='E')?'Estimate':'Quote';
            $out=$out."	   					<tr class=\"even\">\n";
            $out=$out."      						<td align=\"right\" width=\"90\"><b>".$eqtype."</b></td>\n";
            $out=$out."      						<td align=\"left\">".$ev['estid']."</td>\n";
			$out=$out."      						<td align=\"left\">".$ev['srname']."</td>\n";
            $out=$out."      						<td align=\"center\">".$ev['added']."</td>\n";
            $out=$out."      						<td align=\"center\">".$ev['updated']."</td>\n";
            $out=$out."      						<td align=\"center\">\n";
            $out=$out."                        <form name=\"viewest\" method=\"POST\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
            //$out=$out."                           <input type=\"hidden\" name=\"call\" value=\"EstimateView\">\n";
			$out=$out."                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"estid\" value=\"".$ev['estid']."\">\n";
            $out=$out."                           <input type=\"hidden\" name=\"esttype\" value=\"".$ev['esttype']."\">\n";
			$out=$out."                           <input class=\"transnb\" type=\"image\" src=\"images/layout.png\" alt=\"View ".$eqtype."\">\n";            
			$out=$out."							</form>\n";            
            $out=$out."								</td>\n";
			$out=$out."								<td></td>\n";
            $out=$out."   						</tr>\n";
        }
    }
    
    if ($_SESSION['clev']!=0 and count($cdata['jobs']) > 0)
    {
		foreach ($cdata['jobs'] as $nj => $vj) {
			$out=$out."	   					<tr class=\"even\">\n";
			$out=$out."      						<td align=\"right\" width=\"90\"><b>Contract</b></td>\n";
			$out=$out."      						<td align=\"left\" width=\"100\">".$vj['jobid']."</td>\n";
			$out=$out."      						<td align=\"left\">".$vj['srname']."</td>\n";
			$out=$out."      						<td align=\"center\">".$vj['added']."</td>\n";
			$out=$out."      						<td align=\"center\">".$vj['updated']."</td>\n";
			$out=$out."      						<td align=\"center\">\n";
			$out=$out."                        <form method=\"POST\">\n";
			$out=$out."                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			$out=$out."                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
			$out=$out."                           <input type=\"hidden\" name=\"jobid\" id=\"usr_jobid\" value=\"".$vj['jobid']."\">\n";
			$out=$out."                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			$out=$out."                           <input class=\"transnb\" type=\"image\" src=\"images/layout.png\" alt=\"View Contract\">\n";
			$out=$out."                        </form>\n";
			$out=$out."								</td>\n";
			$out=$out."								<td></td>\n";
			$out=$out."   						</tr>\n";
		}
    }

    if ($_SESSION['jlev']!=0 and count($cdata['jobs']) > 0)
    {
		foreach ($cdata['jobs'] as $nr => $vr) {
			if ($vr['njobid']!='0') {
				$out=$out."	   					<tr class=\"even\">\n";
				$out=$out."      						<td align=\"right\" width=\"90\"><b>Job</b></td>\n";
				$out=$out."      						<td align=\"left\" width=\"100\">".$vr['njobid']."</td>\n";
				$out=$out."      						<td align=\"left\">".$vr['srname']."</td>\n";
				$out=$out."      						<td align=\"center\">".$vr['added']."</td>\n";
				$out=$out."      						<td align=\"center\">".$vr['updated']."</td>\n";
				$out=$out."      						<td align=\"center\">\n";
				$out=$out."                        <form method=\"POST\">\n";
				$out=$out."                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
				$out=$out."                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				$out=$out."                           <input type=\"hidden\" name=\"njobid\" value=\"".$vr['njobid']."\">\n";
				$out=$out."                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				$out=$out."                           <input class=\"transnb\" type=\"image\" src=\"images/layout.png\" alt=\"View Job\">\n";
				$out=$out."                        </form>\n";
				$out=$out."								</td>\n";
				//$out=$out."								<td><img class=\"setpointer getConstrDates\" src=\"images/application_form.png\"></td>\n";
				$out=$out."								<td></td>\n";
				$out=$out."   						</tr>\n";
			}
		}
    }
    
	/*
    if ($_SESSION['jlev']!=0 && $row['njobid']!='0' && (isset($ddate) and valid_date($ddate) and strtotime($ddate) >= strtotime('1/1/2000')))
    {
        $out=$out."	   						<tr class=\"even\">\n";
        $out=$out."      						<td align=\"right\" width=\"90\"><b>Dig Date</b></td>\n";
        $out=$out."      						<td align=\"left\" width=\"100\"><img src=\"images/pixel.gif\"></td>\n";
        $out=$out."      						<td align=\"center\">".$ddate."</td>\n";
        $out=$out."      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        $out=$out."      						<td align=\"center\"></td>\n";
        $out=$out."   						</tr>\n";
    }
    */

    if ((isset($sdata['system']['office']['fscustomer']) and $sdata['system']['office']['fscustomer'] == 1) and (isset($sdata['system']['security']['filestoreaccess']) and $sdata['system']['security']['filestoreaccess'] >= 1))
    {
        $out=$out."	   						<tr class=\"even\">\n";
        $out=$out."      						<td align=\"right\" width=\"90\"><b>Files</b></td>\n";
        $out=$out."      						<td align=\"left\" width=\"100\">".$cdata['files']['filecnt']."</td>\n";
		$out=$out."      						<td align=\"left\"></td>\n";
        $out=$out."      						<td align=\"center\">".$cdata['files']['added']."</td>\n";
        $out=$out."      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        $out=$out."      						<td align=\"center\">\n";
        $out=$out."									<form method=\"POST\">\n";
        $out=$out."										<input type=\"hidden\" name=\"action\" value=\"file\">\n";
        $out=$out."										<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
        $out=$out."										<input type=\"hidden\" name=\"cid\" value=\"".$cdata['lead']['cid']."\">\n";
        $out=$out."										<input class=\"transnb\" type=\"image\" src=\"images/layout.png\" alt=\"View Files\">\n";
        $out=$out."									</form>\n";
        $out=$out."								</td>\n";
        $out=$out."   						</tr>\n";
    }

    $out=$out."						</table>\n";
	
	//$out=$out.print_r($cdata);
    
    return $out;
}

function OneSheetCommentInput() {
	echo "<table width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\" align=\"left\"><b>Comment Input</b></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\">Type</td>\n";
	echo "		<td valign=\"top\" align=\"left\">\n";
	echo "			<div id=\"OneSheetCmntSelector\"></div>\n";	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\"></td>\n";
	echo "		<td align=\"left\">\n";
	echo "			<textarea name=\"mtext\" id=\"mtext\" rows=\"5\" cols=\"45\"></textarea>\n";
	echo "			<input class=\"transnb\" id=\"saveLeadComment\" type=\"image\" src=\"images/save.gif\" alt=\"Save Comment\">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function getOneSheetCustomer($cid,$sdata) {
	$qry = "SELECT C.* FROM cinfo AS C WHERE C.cid=".(int) $cid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
    
    $cdata['lead']['cid']   =$row['cid'];
    $cdata['lead']['oid']   =$row['officeid'];
    $cdata['lead']['sdate'] =date('m/d/Y',strtotime($row['added']));
    $cdata['lead']['udate'] =date('m/d/Y',strtotime($row['updated']));
    $cdata['lead']['fname'] =$row['cfname'];
    $cdata['lead']['lname'] =$row['clname'];
    $cdata['lead']['saddr1']=$row['saddr1'];
    $cdata['lead']['scity'] =$row['scity'];
    $cdata['lead']['sstate']=$row['sstate'];
    $cdata['lead']['szip1'] =$row['szip1'];
    $cdata['lead']['cpname']=$row['cpname'];
    $cdata['lead']['chome'] =$row['chome'];
    $cdata['lead']['ccell'] =$row['ccell'];
	$cdata['lead']['cemail']=$row['cemail'];
    $cdata['srep']['sid']   =$row['securityid'];

	$qryD = "SELECT fname,lname,mas_div,filestoreaccess,constructdateaccess FROM security WHERE securityid=".(int) $cdata['srep']['sid'].";";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);
    
	$cdata['lead']['srname']=substr($rowD['fname'],0,2).substr($rowD['lname'],0,3);
    $cdata['srep']['fname']	=$rowD['fname'];
    $cdata['srep']['lname']	=$rowD['lname'];
	$cdata['srep']['lname']	=$rowD['lname'];
    $cdata['srep']['mas_div']=$rowD['mas_div'];
    $cdata['srep']['filestoreaccess']=$rowD['filestoreaccess'];
    $cdata['srep']['constructdateaccess']=$rowD['constructdateaccess'];
	
    $cdata['estimates']=array();
	$qryE = "SELECT E.estid,E.officeid as oid,E.esttype,E.added,E.updated,(select (substring(fname,1,2) + substring(lname,1,3)) as srname from security where securityid=E.securityid) as srname FROM est AS E WHERE E.cid=".(int) $cdata['lead']['cid'].";";
	$resE = mssql_query($qryE);
    while ($rowE = mssql_fetch_array($resE)) {
        $cdata['estimates'][$rowE['estid']]=array('estid'=>$rowE['estid'],'oid'=>$rowE['oid'],'esttype'=>$rowE['esttype'],'srname'=>$rowE['srname'],'added'=>date("m/d/Y", strtotime($rowE['added'])),'updated'=>(strtotime($rowE['updated']) > strtotime('1/1/2000'))?date("m/d/Y", strtotime($rowE['updated'])):'');
    }
    
    $cdata['jobs']=array();
    $qryF = "SELECT J.jid,J.jobid,J.njobid,J.added,J.updated,(select (substring(fname,1,2) + substring(lname,1,3)) as srname from security where securityid=J.securityid) as srname FROM jobs AS J WHERE J.officeid=".(int) $cdata['lead']['oid']." AND J.custid=".(int) $cdata['lead']['cid']." ORDER BY J.added DESC;";
	$resF = mssql_query($qryF);
    while ($rowF = mssql_fetch_array($resF)) {
        $cdata['jobs'][$rowF['jid']]=array('jobid'=>$rowF['jobid'],'njobid'=>$rowF['njobid'],'srname'=>$rowF['srname'],'added'=>date("m/d/Y", strtotime($rowF['added'])),'updated'=>date("m/d/Y", strtotime($rowF['updated'])));
    }

	$qryS = "select isnull(count(F.docid),0) as tfiles from jest..jestFileStore AS F inner join jest..jestFileStoreCategory AS C on F.fscid=C.fscid where F.cid=".(int) $cdata['lead']['cid']." and F.active=1 and C.slevel <=".(int) $sdata['system']['security']['filestoreaccess'].";";
	$resS = mssql_query($qryS);
	$rowS = mssql_fetch_array($resS);
    $cdata['files']['filecnt']=$rowS['tfiles'];
	$cdata['files']['added']='';
	
	if ($rowS['tfiles']!=0) {
		$qrySa = "select top 1 (F.adate) from jest..jestFileStore AS F inner join jest..jestFileStoreCategory AS C on F.fscid=C.fscid where F.cid=".(int) $cdata['lead']['cid']." and F.active=1 and C.slevel <=".(int) $sdata['system']['security']['filestoreaccess']." order by F.adate desc;";
		$resSa = mssql_query($qrySa);
		$rowSa = mssql_fetch_array($resSa);
		$cdata['files']['added']=(isset($rowSa['adate']) and strtotime($rowSa['adate']) > strtotime('1/1/2000'))?date('m/d/Y',strtotime($rowSa['adate'])):'';
	}
    
    $cdata['lead']['tranid']=time().".".$cdata['lead']['cid'].".".$sdata['system']['security']['sid'];
    
    return $cdata;
}

function getOneSheetSecurity($oid,$sid) {    
    $qryA = "SELECT officeid,finan_off,finan_from,fsenable,fscustomer FROM offices WHERE officeid=".(int) $oid.";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
    
    $sdata['system']['office']['finan_off']=$rowA['finan_off'];
    $sdata['system']['office']['finan_from']=$rowA['finan_from'];
    $sdata['system']['office']['fsenable']=$rowA['fsenable'];
    $sdata['system']['office']['fscustomer']=$rowA['fscustomer'];
    
    $qryB = "SELECT securityid,officeid,filestoreaccess,constructdateaccess FROM security WHERE securityid=".(int) $sid.";";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
    
    $sdata['system']['security']['sid']=$rowB['securityid'];
    $sdata['system']['security']['oid']=$rowB['officeid'];
    $sdata['system']['security']['filestoreaccess']=$rowB['filestoreaccess'];
    $sdata['system']['security']['constructdateaccess']=$rowB['constructdateaccess'];
    
    $qryC = "select officeid as oid from offices where constructiondates!=0;";
	$resC = mssql_query($qryC);
	while ($rowC = mssql_fetch_array($resC)) {
        $sdata['system']['constructiondateaccess'][]=$rowC['oid'];
    }
    
    return $sdata;
}

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

function OneSheetContact($cdata) {
	$out='';
    $wi1='75px';
	$out.="<table width=\"100%\" border=0>\n";
	$out.="	<tr>\n";
	$out.="		<td colspan=\"2\" align=\"left\"><b>Contact Information</b></td>\n";
	$out.="	</tr>\n";
	$out.="	<tr>\n";
	$out.="		<td width=\"".$wi1."\" align=\"right\" valign=\"top\"><b>Name</b></td>\n";
	$out.="		<td align=\"left\">".str_replace('\\','',$cdata['lead']['fname'])." ".str_replace('\\','',$cdata['lead']['lname'])."</td>\n";
	$out.="	</tr>\n";
	$out.="	<tr>\n";
	$out.="		<td width=\"".$wi1."\" valign=\"top\" align=\"right\"><b>Home</b></td>\n";
	$out.="		<td align=\"left\">".format_phonenumber($cdata['lead']['chome'])."</td>\n";
	$out.="	</tr>\n";
	$out.="	<tr>\n";
	$out.="		<td width=\"".$wi1."\" valign=\"top\" align=\"right\"><b>Cell</b></td>\n";
	$out.="		<td align=\"left\">".format_phonenumber($cdata['lead']['ccell'])."</td>\n";
	$out.="	</tr>\n";
	$out.="	<tr>\n";
	$out.="		<td width=\"".$wi1."\" valign=\"top\" align=\"right\"><b>Email</b></td>\n";
	$out.="		<td align=\"left\">".$cdata['lead']['cemail']."</td>\n";
	$out.="	</tr>\n";
	$out.="</table>\n";
	
	return $out;
}

function OneSheetAddress($cdata) {
	echo "<table width=\"100%\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\"><b>Site Address</b></td>\n";
	echo "		<td align=\"left\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\"></td>\n";
	echo "		<td align=\"left\">".$cdata['lead']['saddr1']." ".$cdata['lead']['scity'].", ".$cdata['lead']['sstate']." ".$cdata['lead']['szip1']."</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}