<?php
error_reporting(E_ALL);

function get_EmailTemplates($ttype=null){
	$out='';
	
	$qryET = "SELECT * FROM EmailTemplate WHERE oid=0 and active <= ".$_SESSION['emailtemplates']." AND active >= 1 and ttype=1 ORDER BY name ASC;";	
	$resET = mssql_query($qryET);
	$nrowET= mssql_num_rows($resET);

	$qryET1 = "SELECT * FROM EmailTemplate WHERE oid=".(int) $_SESSION['officeid']." and active <= ".$_SESSION['emailtemplates']." AND active >= 1 and ttype=1 ORDER BY name ASC;";	
	$resET1 = mssql_query($qryET1);
	$nrowET1= mssql_num_rows($resET1);
	
	$out.='<form id="frmEmailQueueProc">';
	$out.='<div>Select an Email Template:</div>';
	$out.='<select id="emq_etid" name="emq_etid" autocomplete="off">';
	$out.='	<option value="0">None</option>';

	if ($nrowET1 > 0) {
		$out.='	<optgroup label="'.$_SESSION['offname'].' Templates">';
		while ($rowET1 = mssql_fetch_array($resET1)){
			if ($rowET1['active']==0){
				$out.='		<option class="fontred" value="'.$rowET1['etid'].'">'.$rowET['name'].'</option>';
			}
			else {
				$out.='		<option value="'.$rowET['etid'].'">'.$rowET1['name'].'</option>';
			}
		}
		$out.='	</optgroup>';
	}
	
	if ($nrowET > 0) {
		$out.='	<optgroup label="Provided Templates">';
		while ($rowET = mssql_fetch_array($resET)) {
			if ($rowET['active']==0) {
				$out.='		<option class="fontred" value="'.$rowET['etid'].'">'.$rowET['name'].'</option>';
			}
			else {
				$out.='		<option value="'.$rowET['etid'].'">'.$rowET['name'].'</option>';
			}
		}
		$out.='	</optgroup>';
	}
		
	$out.='	</select>';
	$out.='</form>';
	$out.='<p id="EmailQueueStat"><em>then Select Individual Recipients or Check All.</em></p>';
	
	return $out;
}

function save_EmailQueue($in=null) {
	$out=array('error'=>true,'result'=>'Queue Process Error ('.__LINE__.')');
	$etid=(isset($_REQUEST['etid']) and $_REQUEST['etid'] > 0)?$_REQUEST['etid']:null;
	$cid_ar=(isset($_REQUEST['cid_ar']) and is_array($_REQUEST['cid_ar']))?$_REQUEST['cid_ar']:null;
	
	if (!is_null($etid) and !is_null($cid_ar)) {
		$chkin		=time();
		$adminid	=$_SESSION['securityid'];
		
		$hostname   = "192.168.100.67";
		$username   = "jmsauth";
		$password   = "into99black";
		$dbname     = "jms_email_queue";
		
		mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
		mssql_select_db($dbname) or die("Table unavailable");
		
		$qry0 = "INSERT INTO EmailProc (checkin,sid) VALUES (".$chkin.",".$adminid."); SELECT @@IDENTITY;";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_row($res0);
		
		if (isset($row0[0]) and $row0[0]!=0) {
			$pid=$row0[0];			
			$qc=0;
			foreach ($cid_ar as $nc=>$vc) {
				if (isset($vc) and $vc!=0) {
					$prt  = time();
					$qry1 = "INSERT INTO EmailQueue (etid,pid,sid,cid,adate) VALUES (".$etid.",".$pid.",".$adminid.",".$vc.",".$prt.");";
					$res1 = mssql_query($qry1);
					$qc++;
				}
			}
			
			if ($qc > 0) {
				$out=array('error'=>false,'result'=>$qc.' Email(s) Queued');
			}
			else {
				$out=array('error'=>true,'result'=>'Queue Process Error ('.__LINE__.')');
			}
		}
		else {
			$out=array('error'=>true,'result'=>'Queue Process Error ('.__LINE__.')');
		}
	}
	else {
		$out=array('error'=>true,'result'=>'Queue Process Error ('.__LINE__.')');
	}
	
	return $out;
}

function format_phonenumber($n)
{
	$out='';
	
	$n=preg_replace('/\.|-|\s/i','$1$2$3',trim($n));
	
	if (strlen($n)==10)
	{
		$out=substr($n,0,3).'-'.substr($n,3,3).'-'.substr($n,6,4);
	}
	elseif (strlen($n)==7)
	{
		$out=substr($n,0,3).'-'.substr($n,3,4);
	}
	else
	{
		$out=$n;
	}
	
	return $out;
}

function removequote($data)
{
	$qs=array("/'/","/''/","/\"/","/;/");
	$rp='';
	$out=preg_replace($qs,$rp,$data);
	return $out;
}

function is_base64_encoded($d)
{
	if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $d))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function set_sdate()
{
		$rtime	=time();
		$pdate	=getdate();
		$dcnt	=86400;
		
		if ($pdate['weekday']=="Sunday")
		{
			$stime=time() - ($dcnt* 2);
		}
		elseif ($pdate['weekday']=="Monday")
		{
			$stime=time() - ($dcnt* 3);
		}
		else
		{
			$stime=time() - $dcnt;
		}
		
		$out	=array(date("m/d/Y",$stime)." 6:00 PM",date("m/d/Y g:i A",$rtime));
		return $out;
}

function get_Appointment($cid)
{
	$out=array('mo'=>0,'da'=>0,'yr'=>0,'hr'=>0,'mn'=>0,'pa'=>0);
	
	$qry0 = "
			SELECT
				C.cid,C.appt_mo,C.appt_da,C.appt_yr,C.appt_hr,C.appt_mn,C.appt_pa
			FROM
				cinfo as C
			WHERE
				C.cid=".(int) $cid.";";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 >0)
	{
		$row0 = mssql_fetch_array($res0);
		$out=array('mo'=>$row0['appt_mo'],'da'=>$row0['appt_da'],'yr'=>$row0['appt_yr'],'hr'=>$row0['appt_hr'],'mn'=>$row0['appt_mn'],'pa'=>$row0['appt_pa']);
	}
	
	return $out;
}

function show_Appointment($cid)
{
	$out='';
	$appt=get_Appointment($cid);
	
	if (isset($appt['mo']) and $appt['mo']!=0) {
		$ampm=($appt['pa']==1)?'AM':'PM';
		$out=str_pad($appt['mo'],2,'0',LEFT).'/'.str_pad($appt['da'],2,'0',LEFT).'/'.$appt['yr'].' '.$appt['hr'].':'.str_pad($appt['mn'],2,'0',LEFT).$ampm;
	}
	
	return $out;
}

function set_Appointment(){
	$out='';
	$cid	=(isset($_REQUEST['appt_cid']) and $_REQUEST['appt_cid']!=0)?$_REQUEST['appt_cid']:0;
	$appt_mo=(isset($_REQUEST['appt_mo']) and $_REQUEST['appt_mo']!=0)?$_REQUEST['appt_mo']:0;
	$appt_da=(isset($_REQUEST['appt_da']) and $_REQUEST['appt_da']!=0)?$_REQUEST['appt_da']:0;
	$appt_yr=(isset($_REQUEST['appt_yr']) and $_REQUEST['appt_yr']!=0)?$_REQUEST['appt_yr']:0;
	$appt_hr=(isset($_REQUEST['appt_hr']) and $_REQUEST['appt_hr']!=0)?$_REQUEST['appt_hr']:0;
	$appt_mn=(isset($_REQUEST['appt_mn']) and $_REQUEST['appt_mn']!=0)?$_REQUEST['appt_mn']:0;
	$appt_pa=(isset($_REQUEST['appt_pa']) and $_REQUEST['appt_pa']!=0)?$_REQUEST['appt_pa']:0;
	
	$appt_ar=array(
		'appt_mo'=>$appt_mo,
		'appt_da'=>$appt_da,
		'appt_yr'=>$appt_yr,
		'appt_hr'=>$appt_hr,
		'appt_mn'=>$appt_mn,
		'appt_pa'=>$appt_pa
	);
	
	foreach ($appt_ar as $n => $v) {
		if ($v!=0)
		{
			$qry = "UPDATE cinfo SET ".$n."=".$v." WHERE cid=".(int) $cid.";";
			$res = mssql_query($qry);
		}
	}

	$out=show_Appointment($cid);
	
	return $out;
}

function get_ExtLeadMenu()
{
	$cid=(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:0;
	
	if ($cid!=0)
	{
		$out=array();
		$qry1 = "SELECT estid,esttype FROM est WHERE cid=".(int) $cid." ORDER BY estid DESC;";
		$res1 = mssql_query($qry1);
		$nrow1= mssql_num_rows($res1);
		
		if ($nrow1)
		{
			while ($row1 = mssql_fetch_array($res1))
			{
				$out[]=array('id'=>$row1['estid'],'type'=>($row1['esttype']=='E')?'E':'Q','sort'=>($row1['esttype']=='E')?2:1);
			}
		}
		
		$qry2 = "SELECT jid,jobid,njobid FROM jobs WHERE custid=".(int) $cid." ORDER BY added DESC;";
		$res2 = mssql_query($qry2);
		$nrow2= mssql_num_rows($res2);
		
		if ($nrow2)
		{
			while ($row2 = mssql_fetch_array($res2))
			{
				if ($row2['jobid']!='0'){
					$out[]=array('id'=>$row2['jobid'],'type'=>'C','sort'=>1);
				}
				
				if ($row2['njobid']!='0'){
					$out[]=array('id'=>$row2['jobid'],'type'=>'J','sort'=>2);
				}
			}
		}
	}
	
	return $out;
}

function get_OneSheetCmntSelector($cid)
{
	$out='';
	
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
		
		$out=$out."												<select name=\"commentflag\" id=\"cmntflag\">\n";
		$out=$out."													<option value=\"0\">Select...</option>\n";
		
		if ($_SESSION['csrep'] >= 6)
		{		
			if (!empty($sfol_ar) && is_array($sfol_ar))
			{
				foreach (array_unique($sfol_ar) as $sfn => $sfv)
				{
					if (!in_array($sfv,$scls_ar))
					{
						$out=$out."													<option value=\"SF:".$sfv."\">Service Followup: ".$sfv."</option>\n";
					}
				}
			}
			
			if (!empty($sres_ar) && is_array($sres_ar))
			{
				foreach (array_unique($sres_ar) as $srn => $srv)
				{
					if (!in_array($srv,$scls_ar))
					{
						$out=$out."													<option value=\"SR:".$srv."\">Service Resolve: ".$srv."</option>\n";
					}
				}
			}
			
			if (!empty($cfol_ar) && is_array($cfol_ar))
			{
				foreach (array_unique($cfol_ar) as $fn => $fv)
				{
					if (!in_array($fv,$ccls_ar))
					{
						$out=$out."													<option value=\"CF:".$fv."\">Complaint Followup: ".$fv."</option>\n";
					}
				}
			}
			
			if (!empty($cres_ar) && is_array($cres_ar))
			{
				foreach (array_unique($cres_ar) as $rn => $rv)
				{
					if (!in_array($rv,$ccls_ar))
					{
						$out=$out."													<option value=\"CR:".$rv."\">Complaint Resolve: ".$rv."</option>\n";
					}
				}
			}
			
			$out=$out."													<option value=\"S:1\">Add Service</option>\n";
			$out=$out."													<option value=\"C:1\">Add Complaint</option>\n";
		}
		
		$out=$out."													<option value=\"C:0\">Add Comment</option>\n";
		$out=$out."												</select>\n";
		
	}
	
	return $out;
}

function save_LeadComment($oid=null,$sid=null,$cid=null,$cmnt=null,$cmntflg=null)
{
	$out=0;
	
	if (
		(!is_null($oid) and $oid!=0) and
		(!is_null($sid) and $sid!=0) and
		(!is_null($cid) and $cid!=0) and
		!is_null($cmnt) and !is_null($cmntflg)
		)
	{
		$tranid=rand(1000001,100000001).'.'.$sid;
		
		if (isset($cmntflg) and ($cmntflg=='0' or $cmntflg=='C:0'))
		{
			$inputtext=removequote($cmnt);
			$action='leads';
			$complaint=0;
			$cservice=0;
			$followup=0;
			$resolve=0;
			$relid=0;
		}
		else
		{
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
	
	return $out;
}

function get_LeadCommentList_OLD_121012($cid=null)
{
	$out='';
	
	if (isset($cid) and !is_null($cid))
	{
		$qryL = "SELECT
					C1.*
					,(SELECT lname FROM security WHERE securityid=C1.secid) as slname
					,(SELECT fname FROM security WHERE securityid=C1.secid) as sfname
					,(SELECT count(chid) FROM chistory_files where chid=C1.id) as fcnt
				FROM chistory AS C1 WHERE C1.custid=".(int) $cid." ORDER BY C1.mdate DESC;";
		$resL = mssql_query($qryL);
		$nrowL= mssql_num_rows($resL);
		
		/*
		if ($_SESSION['securityid']==26)
		{
			echo $qryL.'<br>';
		}
		*/
		//echo $nrowL.'<br>';
		if ($nrowL > 0)
		{
			$tsize=50;
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
				'/=/',
				'/------Original Message------/',
				'/----- Original message -----/');
				
			$replace_ar=array('','','','','','','','','',' ',' ',' ',' ','');
			
			$out=$out."<table width=\"100%\">\n";
			$out=$out."	<tr>\n";
			$out=$out."		<td align=\"left\" width=\"90px\"><b>Date</b></td>\n";
			$out=$out."		<td align=\"left\" width=\"30px\"><b>Name</b></td>\n";
			$out=$out."		<td align=\"center\" width=\"30px\"><b>Stage</b></td>\n";
			$out=$out."		<td align=\"center\" width=\"30px\"><b>Ticket</b></td>\n";
			$out=$out."		<td align=\"left\"><b>Comments</b></td>\n";
			$out=$out."		<td align=\"left\" width=\"20px\"></td>\n";
			$out=$out."	</tr>\n";
		
			$cmntcnt=0;
			while ($rowL = mssql_fetch_array($resL))
			{
				$cmntcnt++;
				$stage	='';
				$cfiles	=(isset($rowL['fcnt']) and $rowL['fcnt'] > 0)?'<img id="cfiles_'.$rowL['id'].'" class="cfiles_attached pointer" src="images/attach.png" title="This message has File(s) associated with it.">':'';
				
				if ($cmntcnt%2)
				{
					$cmt_tbg="even";
				}
				else
				{
					$cmt_tbg="odd";
				}
				
				if ($rowL['act']=="leads")
				{
					$stage="<div title=\"Lead\">L</div>";
				}
				elseif ($rowL['act']=="est")
				{
					$stage="<div title=\"Estimate\">E</div>";
				}
				elseif ($rowL['act']=="contract")
				{
					$stage="<div title=\"Contract\">C</div>";
				}
				elseif ($rowL['act']=="jobs")
				{
					$stage="<div title=\"Job\">J</div>";
				}
				elseif ($rowL['act']=="mas")
				{
					$stage="<div title=\"MAS\">M</div>";
				}
				elseif ($rowL['act']=="reports")
				{
					$stage="<div title=\"Reports\">R</div>";
				}
				elseif ($rowL['act']=="fin")
				{
					$stage="<div title=\"Finance\">F</div>";
				}
				elseif ($rowL['act']=="Complaint")
				{
					$stage="<div title=\"Complaint\">CP</div>";
					$cmt_tbg="ltred";
				}
				elseif ($rowL['act']=="Service")
				{
					$stage="<div title=\"Service\">SR</div>";
					$cmt_tbg="ltblue";
				}
				elseif ($rowL['act']=="Followup")
				{
					$stage="<div title=\"Followup\">FL</div>";
					
					if ($rowL['complaint']!=0)
					{
						$cmt_tbg="ltred";
					}
					elseif($rowL['cservice']!=0)
					{
						$cmt_tbg="ltblue";
					}
					else
					{
						$cmt_tbg='';
					}
				}
				elseif ($rowL['act']=="Resolved")
				{
					$stage="<div title=\"Resolved\">RS</div>";					
					$cmt_tbg="ltgrn";
				}
				elseif ($rowL['act']=="cresp")
				{
					$stage="<div title=\"Email Response\">ER</div>";
				}
				
				if ($rowL['act']=='cresp')
				{
					if (is_base64_encoded($rowL['mtext']))
					{
						$mtext=strip_tags(preg_replace($detect_ar,$replace_ar,base64_decode($rowL['mtext'])));
					}
					else
					{
						$mtext=strip_tags(preg_replace($detect_ar,$replace_ar,$rowL['mtext']));
					}
					
					//if ($_SESSION['securityid']==26)
					//{
					//	$out=$out.'<br>'.var_dump(base64_decode($rowL['mtext'],true));
					//}
				}
				else
				{
					$mtext=htmlspecialchars_decode(preg_replace($detect_ar,$replace_ar,$rowL['mtext']));
				}
		
				$out=$out."	<tr class=\"".$cmt_tbg."\">\n";
				$out=$out."		<td align=\"left\" valign=\"top\" NOWRAP><table width=\"100%\"><tr><td align=\"left\">".date('m/d/y',strtotime($rowL['mdate']))."</td><td align=\"right\">".date('g:ia',strtotime($rowL['mdate']))."</td></tr></table></td>\n";
				$out=$out."		<td align=\"center\" valign=\"top\" title=\"".trim($rowL['sfname'])." ".trim($rowL['slname'])."\" NOWRAP>".substr($rowL['sfname'],0,2)." ".substr($rowL['slname'],0,6)."</td>\n";
				$out=$out."		<td align=\"center\" valign=\"top\" NOWRAP>".$stage."</td>\n";
				$out=$out."		<td align=\"left\" valign=\"top\">\n";
				
				if ($rowL['complaint']==1 || $rowL['cservice']==1)
				{
					if ($rowL['relatedcomplaint']!=0)
					{
						$out=$out.$rowL['relatedcomplaint'];
					}
					else
					{
						$out=$out.$rowL['id'];
					}
				}
		
				$out=$out."		</td>\n";
				$out=$out."		<td align=\"left\">\n";
				
				if (strlen($rowL['mtext']) > $tsize)
				{
					$out=$out."<span class=\"texpandtext setpointer\" title=\"Click to Expand\">".substr($mtext,0,$tsize)." ...</span><span class=\"thiddentext\" style=\"display: none\">".$mtext."</span>\n";
				}
				else
				{
					$out=$out.$mtext;
				}

				$out=$out."		</td>\n";
				$out=$out."		<td align=\"left\" valign=\"top\" width=\"20px\">".$cfiles."</td>\n";
				$out=$out."	</tr>\n";
			}
			
			$out=$out."</table>\n";
		}
		else
		{
			$out=$out."<table width=\"100%\">\n";
			$out=$out."	<tr>\n";
			$out=$out."		<td align=\"left\">No Customer Comments (Err:".__LINE__.")</td>\n";
			$out=$out."	</tr>\n";
			$out=$out."</table>\n";
		}
	}

	return $out;
}

function get_LeadCommentList($cid=null) {
	//echo __FUNCTION__.'<br>';
	$out='';
	
	if (isset($cid) and !is_null($cid))
	{
		$qryL = "SELECT
					C1.*
					,(SELECT lname FROM security WHERE securityid=C1.secid) as slname
					,(SELECT fname FROM security WHERE securityid=C1.secid) as sfname
					,(SELECT count(chid) FROM chistory_files where chid=C1.id) as fcnt
				FROM chistory AS C1 WHERE C1.custid=".(int) $cid." ORDER BY C1.mdate DESC;";
		$resL = mssql_query($qryL);
		$nrowL= mssql_num_rows($resL);
		
		/*
		if ($_SESSION['securityid']==26)
		{
			echo $qryL.'<br>';
		}
		*/
		//echo $nrowL.'<br>';
		if ($nrowL > 0)
		{
			$tsize=60;
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
			$out=$out."		<td align=\"left\" width=\"90px\"><b>Date</b></td>\n";
			$out=$out."		<td align=\"left\" width=\"30px\"><b>Name</b></td>\n";
			$out=$out."		<td align=\"center\" width=\"30px\"><b>Stage</b></td>\n";
			$out=$out."		<td align=\"center\" width=\"30px\"><b>Ticket</b></td>\n";
			$out=$out."		<td align=\"left\"><b>Comments</b></td>\n";
			$out=$out."		<td align=\"left\" width=\"20px\"></td>\n";
			$out=$out."	</tr>\n";
		
			$cmntcnt=0;
			while ($rowL = mssql_fetch_array($resL))
			{
				$cmntcnt++;
				$stage	='';
				$cfiles	=(isset($rowL['fcnt']) and $rowL['fcnt'] > 0)?'<img id="cfiles_'.$rowL['id'].'" class="cfiles_attached pointer" src="images/attach.png" title="This message has File(s) associated with it.">':'';
				
				if ($cmntcnt%2)
				{
					$cmt_tbg="even";
				}
				else
				{
					$cmt_tbg="odd";
				}
				
				if ($rowL['act']=="leads")
				{
					$stage="<div title=\"Lead\">L</div>";
				}
				elseif ($rowL['act']=="est")
				{
					$stage="<div title=\"Estimate\">E</div>";
				}
				elseif ($rowL['act']=="contract")
				{
					$stage="<div title=\"Contract\">C</div>";
				}
				elseif ($rowL['act']=="jobs")
				{
					$stage="<div title=\"Job\">J</div>";
				}
				elseif ($rowL['act']=="mas")
				{
					$stage="<div title=\"MAS\">M</div>";
				}
				elseif ($rowL['act']=="reports")
				{
					$stage="<div title=\"Reports\">R</div>";
				}
				elseif ($rowL['act']=="fin")
				{
					$stage="<div title=\"Finance\">F</div>";
				}
				elseif ($rowL['act']=="Complaint")
				{
					$stage="<div title=\"Complaint\">CP</div>";
					$cmt_tbg="ltred";
				}
				elseif ($rowL['act']=="Service")
				{
					$stage="<div title=\"Service\">SR</div>";
					$cmt_tbg="ltblue";
				}
				elseif ($rowL['act']=="Followup")
				{
					$stage="<div title=\"Followup\">FL</div>";
					
					if ($rowL['complaint']!=0)
					{
						$cmt_tbg="ltred";
					}
					elseif($rowL['cservice']!=0)
					{
						$cmt_tbg="ltblue";
					}
					else
					{
						$cmt_tbg='';
					}
				}
				elseif ($rowL['act']=="Resolved")
				{
					$stage="<div title=\"Resolved\">RS</div>";					
					$cmt_tbg="ltgrn";
				}
				elseif ($rowL['act']=="cresp")
				{
					$stage="<div title=\"Email Response\">ER</div>";
				}
				
				if ($rowL['act']=='cresp') {
					if (is_base64_encoded($rowL['mtext']))
					{
						$mtext=strip_tags(preg_replace($detect_ar,$replace_ar,base64_decode($rowL['mtext'])));
					}
					else
					{
						$mtext=strip_tags(preg_replace($detect_ar,$replace_ar,$rowL['mtext']));
					}
					
					$mt_ar=array('/----- Original Message -----/i','/Blue Haven Pools & Spas/i');
					$nmtext=preg_split('/----- Original Message -----/i',$mtext);
					$mtext=$nmtext[0];
				}
				else
				{
					$mtext=htmlspecialchars_decode(preg_replace($detect_ar,$replace_ar,$rowL['mtext']));
				}
		
				$out=$out."	<tr class=\"".$cmt_tbg."\">\n";
				$out=$out."		<td align=\"left\" valign=\"top\" NOWRAP><table width=\"100%\"><tr><td align=\"left\">".date('m/d/y',strtotime($rowL['mdate']))."</td><td align=\"right\">".date('g:ia',strtotime($rowL['mdate']))."</td></tr></table></td>\n";
				$out=$out."		<td align=\"center\" valign=\"top\" title=\"".trim($rowL['sfname'])." ".trim($rowL['slname'])."\" NOWRAP>".substr($rowL['sfname'],0,2)." ".substr($rowL['slname'],0,6)."</td>\n";
				$out=$out."		<td align=\"center\" valign=\"top\" NOWRAP>".$stage."</td>\n";
				$out=$out."		<td align=\"left\" valign=\"top\">\n";
				
				if ($rowL['complaint']==1 || $rowL['cservice']==1)
				{
					if ($rowL['relatedcomplaint']!=0)
					{
						$out=$out.$rowL['relatedcomplaint'];
					}
					else
					{
						$out=$out.$rowL['id'];
					}
				}
		
				$out=$out."		</td>\n";
				$out=$out."		<td align=\"left\">\n";
				
				if (strlen($rowL['mtext']) > $tsize)
				{
					$out=$out."<span class=\"texpandtext setpointer\" title=\"Click to Expand\">".substr($mtext,0,$tsize)." ...</span><span class=\"thiddentext\" style=\"display: none\">".$mtext."</span>\n";
				}
				else
				{
					$out=$out.$mtext;
				}

				$out=$out."		</td>\n";
				$out=$out."		<td align=\"left\" valign=\"top\" width=\"20px\">".$cfiles."</td>\n";
				$out=$out."	</tr>\n";
			}
			
			$out=$out."</table>\n";
		}
		else
		{
			$out=$out."<table width=\"100%\">\n";
			$out=$out."	<tr>\n";
			$out=$out."		<td align=\"left\">No Customer Comments (Err:".__LINE__.")</td>\n";
			$out=$out."	</tr>\n";
			$out=$out."</table>\n";
		}
	}

	return $out;
}

function get_LeadCommentList_NEW($cid=null) {
	//echo __FUNCTION__.'<br>';
	$out='';
	
	if (isset($cid) and !is_null($cid))
	{
		$qryL = "SELECT
					C1.*
					,(SELECT lname FROM security WHERE securityid=C1.secid) as slname
					,(SELECT fname FROM security WHERE securityid=C1.secid) as sfname
					,(SELECT count(chid) FROM chistory_files where chid=C1.id) as fcnt
				FROM chistory AS C1 WHERE C1.custid=".(int) $cid." ORDER BY C1.mdate DESC;";
		$resL = mssql_query($qryL);
		$nrowL= mssql_num_rows($resL);
		
		/*
		if ($_SESSION['securityid']==26)
		{
			echo $qryL.'<br>';
		}
		*/
		//echo $nrowL.'<br>';
		if ($nrowL > 0)
		{
			$tsize=60;
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
			$out=$out."		<td align=\"left\" width=\"90px\"><b>Date</b></td>\n";
			$out=$out."		<td align=\"left\" width=\"30px\"><b>Name</b></td>\n";
			$out=$out."		<td align=\"center\" width=\"30px\"><b>Stage</b></td>\n";
			$out=$out."		<td align=\"center\" width=\"30px\"><b>Ticket</b></td>\n";
			$out=$out."		<td align=\"left\"><b>Comments</b></td>\n";
			$out=$out."		<td align=\"left\" width=\"20px\"></td>\n";
			$out=$out."	</tr>\n";
		
			$cmntcnt=0;
			while ($rowL = mssql_fetch_array($resL))
			{
				$cmntcnt++;
				$stage	='';
				$cfiles	=(isset($rowL['fcnt']) and $rowL['fcnt'] > 0)?'<img id="cfiles_'.$rowL['id'].'" class="cfiles_attached pointer" src="images/attach.png" title="This message has File(s) associated with it.">':'';
				
				if ($cmntcnt%2)
				{
					$cmt_tbg="even";
				}
				else
				{
					$cmt_tbg="odd";
				}
				
				if ($rowL['act']=="leads")
				{
					$stage="<div title=\"Lead\">L</div>";
				}
				elseif ($rowL['act']=="est")
				{
					$stage="<div title=\"Estimate\">E</div>";
				}
				elseif ($rowL['act']=="contract")
				{
					$stage="<div title=\"Contract\">C</div>";
				}
				elseif ($rowL['act']=="jobs")
				{
					$stage="<div title=\"Job\">J</div>";
				}
				elseif ($rowL['act']=="mas")
				{
					$stage="<div title=\"MAS\">M</div>";
				}
				elseif ($rowL['act']=="reports")
				{
					$stage="<div title=\"Reports\">R</div>";
				}
				elseif ($rowL['act']=="fin")
				{
					$stage="<div title=\"Finance\">F</div>";
				}
				elseif ($rowL['act']=="Complaint")
				{
					$stage="<div title=\"Complaint\">CP</div>";
					$cmt_tbg="ltred";
				}
				elseif ($rowL['act']=="Service")
				{
					$stage="<div title=\"Service\">SR</div>";
					$cmt_tbg="ltblue";
				}
				elseif ($rowL['act']=="Followup")
				{
					$stage="<div title=\"Followup\">FL</div>";
					
					if ($rowL['complaint']!=0)
					{
						$cmt_tbg="ltred";
					}
					elseif($rowL['cservice']!=0)
					{
						$cmt_tbg="ltblue";
					}
					else
					{
						$cmt_tbg='';
					}
				}
				elseif ($rowL['act']=="Resolved")
				{
					$stage="<div title=\"Resolved\">RS</div>";					
					$cmt_tbg="ltgrn";
				}
				elseif ($rowL['act']=="cresp")
				{
					$stage="<div title=\"Email Response\">ER</div>";
				}
				
				if ($rowL['act']=='cresp') {
					if (is_base64_encoded($rowL['mtext']))
					{
						$mtext=strip_tags(preg_replace($detect_ar,$replace_ar,base64_decode($rowL['mtext'])));
					}
					else
					{
						$mtext=strip_tags(preg_replace($detect_ar,$replace_ar,$rowL['mtext']));
					}
					
					//$mt_ar=array('/----- Original Message -----/i','/Blue Haven Pools & Spas/i');
					$nmtext=preg_split('/----- Original Message -----|Blue Haven Pools & Spas|---/i',$mtext);
					$mtext=$nmtext[0];
				}
				else
				{
					$mtext=htmlspecialchars_decode(preg_replace($detect_ar,$replace_ar,$rowL['mtext']));
				}
		
				$out=$out."	<tr class=\"".$cmt_tbg."\">\n";
				$out=$out."		<td align=\"left\" valign=\"top\" NOWRAP><table width=\"100%\"><tr><td align=\"left\">".date('m/d/y',strtotime($rowL['mdate']))."</td><td align=\"right\">".date('g:ia',strtotime($rowL['mdate']))."</td></tr></table></td>\n";
				$out=$out."		<td align=\"center\" valign=\"top\" title=\"".trim($rowL['sfname'])." ".trim($rowL['slname'])."\" NOWRAP>".substr($rowL['sfname'],0,2)." ".substr($rowL['slname'],0,6)."</td>\n";
				$out=$out."		<td align=\"center\" valign=\"top\" NOWRAP>".$stage."</td>\n";
				$out=$out."		<td align=\"left\" valign=\"top\">\n";
				
				if ($rowL['complaint']==1 || $rowL['cservice']==1)
				{
					if ($rowL['relatedcomplaint']!=0)
					{
						$out=$out.$rowL['relatedcomplaint'];
					}
					else
					{
						$out=$out.$rowL['id'];
					}
				}
		
				$out=$out."		</td>\n";
				$out=$out."		<td align=\"left\">\n";
				
				if (strlen($rowL['mtext']) > $tsize)
				{
					$out=$out."<span class=\"texpandtext setpointer\" title=\"Click to Expand\">".substr($mtext,0,$tsize)." ...</span><span class=\"thiddentext\" style=\"display: none\">".$mtext."</span>\n";
				}
				else
				{
					$out=$out.$mtext;
				}

				$out=$out."		</td>\n";
				$out=$out."		<td align=\"left\" valign=\"top\" width=\"20px\">".$cfiles."</td>\n";
				$out=$out."	</tr>\n";
			}
			
			$out=$out."</table>\n";
		}
		else
		{
			$out=$out."<table width=\"100%\">\n";
			$out=$out."	<tr>\n";
			$out=$out."		<td align=\"left\">No Customer Comments (Err:".__LINE__.")</td>\n";
			$out=$out."	</tr>\n";
			$out=$out."</table>\n";
		}
	}

	return $out;
}

function get_FileList() {
	$cid=(isset($_REQUEST['cid']) and $_REQUEST['cid']!=0)?$_REQUEST['cid']:null;
	$chid=(isset($_REQUEST['chid']) and $_REQUEST['chid']!=0)?$_REQUEST['chid']:null;
	
	$out='';
	
	if (!is_null($cid) and !is_null($chid)) {
		$qry = "SELECT filename FROM chistory_files WHERE chid=".(int) $chid.";";
		$res = mssql_query($qry);
		$nrow= mssql_num_rows($res);
		
		if ($nrow > 0) {
			while ($row = mssql_fetch_array($res))
			{
				$basename=basename($row['filename']);
				$out=$out.'<a class="cfileDL" href="http://jms.bhnmi.com/export/fileout.php?cid='.$cid.'&filename='.$basename.'&storetype=file_fc" target="_blank" title="Click to download this file"><img src="images/download.gif"> '.$basename.'</a><br>';
			}
		}
	}
	
	return $out;
}

function get_LeadsbyName_List($oid,$sid,$clname)
{
	$out='';
	
	if (isset($clname) and !empty($clname))
	{
		$qry = "
			select 
				cid,officeid,securityid,sidm,clname,cfname
			from 
				cinfo AS C
			where
				C.officeid=".(int) $oid."
				and C.clname like '".$clname."%'
			order by
				C.clname ASC;";
		$res = mssql_query($qry);	
		$nrow= mssql_num_rows($res);
		
		if ($nrow > 0) {
			$out=$out.'<div style="float:right;"><a id="removeLeadZList" href="#">(Clear)</a></div><br>';
			$out=$out.'<p>Leads that start with <b>'.$clname.'</b><br>';
			$out=$out.'<ul>';
			
			$i=1000;
			while ($row= mssql_fetch_array($res)) {
				/*
				$uid=md5($row['cid']).$_SESSION['securityid'];
				$out=$out.'<div>';
				$out=$out.'<form class="viewLeadForm" method="POST">';
				$out=$out.'	<input type="hidden" value="leads" name="action">';
				$out=$out.'	<input type="hidden" value="view" name="call">';
				$out=$out.'	<input class="sysCID" type="hidden" value="'.$row['cid'].'" name="cid">';
				$out=$out.'	<input type="hidden" value="'.$uid.'" name="uid">';
				$out=$out.'	<button class="btnsysmenu>'.$row['cid'].'</span> - '.$row['clname'].', '.$row['cfname'].'</button>';
				$out=$out.'</form><br>';
				$out=$out.'</div>';
				*/
				//$out=$out.'<div><span class="selectLDbyCID">'.$row['cid'].'</span> - '.$row['clname'].', '.$row['cfname'].'</div>';
				$uid=md5($row['cid']).$_SESSION['securityid'];
				$out=$out.'<li><a tabindex="'.$i++.'" href="http://jms.bhnmi.com/index.php?action=leads&call=view&cid='.$row['cid'].'&uid='.$uid.'" title="Open Lead"><span class="selectLDbyCID">'.$row['cid'].'</span> - '.$row['clname'].', '.$row['cfname'].'</a></li>';
			}
			
			$out=$out.'</ul>';
		}
	}
	
	return $out;
}

function get_LeadsbyCompany_List($oid,$sid,$cpname)
{
	session_start();
	error_reporting(E_ALL);
	
	if (isset($cpname) and strlen($cpname) > 2)
	{
		$qry = "
			select 
				C.cid,C.officeid,C.securityid,C.sidm,C.cpname,(select name from offices where officeid=C.officeid) as oname
			from 
				cinfo AS C
			where
				C.officeid=".$oid."
				and C.cpname like '".$cpname."%';";
		$res = mssql_query($qry);	
		$nrow= mssql_num_rows($res);
		
		if ($nrow > 0)
		{
			while ($row= mssql_fetch_array($res))
			{
				//echo $row['cid'].' - '.$row['cpname'].' - '.$row['oname'].'<br>';
				echo $row['cid'].' - '.$row['cpname'].'<br>';
			}
		}
		/*
		else
		{
			//echo 'No Entries Found';
			//echo $qry;
		}
		*/
	}
}

function get_AP_list()
{
	$dev_ar= array(2699999999999999999999999);
	$sdate	 =set_sdate();
	$nph_ar= array('**********','0000000000','none','N/A','na');
	
	$odata='';
	
	$qry = "
		select 
			cid,officeid,securityid,lname,fname,sidm,clname,cfname,saddr1,scity,sstate,szip1,added,updated,
			apptmnt,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,
			chome,cwork,ccell 
		from 
			list_cinfo 
		where
			officeid=".$_SESSION['officeid']."
			";
		
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
		}
	}		
	elseif ($_SESSION['llev'] < 4)
	{
		$qry .= "	and securityid=".$_SESSION['securityid']." ";
	}
	
	$qry .= " 	
			and apptmnt BETWEEN '".date('m/d/y',(time() - 172800))."' and (getdate()+16)
			and appt_yr!=0
			and dupe!=1
		order by
			apptmnt asc,clname asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($_SESSION['securityid']==26999999999999999999999999999999999)
	{
		echo $qry;
	}
	
	if ($nrow > 0)
	{
		while ($row= mssql_fetch_array($res))
		{
			$ap_ar[$row['cid']]=$row;
		}
		
		$odata=$odata."			<table class=\"transnb\" width=\"100%\">\n";
		$odata=$odata."			<thead>\n";
		$odata=$odata."				<tr>\n";
		$odata=$odata."					<td align=\"center\" class=\"lightgreen\" width=\"20\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"lightgreen\" width=\"150\"><b>Customer</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"lightgreen\"><b>City</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"lightgreen\"><b>Zip</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"lightgreen\"><b>SalesRep</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"lightgreen\"><b>Appt Date/Time</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"lightgreen\"><b>Phone</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"lightgreen\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."				</tr>\n";
		$odata=$odata."			</thead>\n";
		
		$rcntAP=1;
		$ccnt=0;
		foreach ($ap_ar as $nAP => $vAP)
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbgAP = 'even';
			}
			else
			{
				$tbgAP = 'odd';
			}
			
			$uidAP  =md5(session_id().time().$vAP['cid']).".".$_SESSION['securityid'];
			$odata=$odata."				<tr class=\"".$tbgAP."\">\n";
			$odata=$odata."					<td align=\"right\">".$rcntAP++.".</td>\n";
			$odata=$odata."					<td align=\"left\">".trim($vAP['clname']).", ".trim($vAP['cfname'])."</td>\n";
			$odata=$odata."					<td align=\"left\">".trim($vAP['scity'])."</td>\n";
			$odata=$odata."					<td align=\"center\">".$vAP['szip1']."</td>\n";
			$odata=$odata."					<td align=\"left\">".$vAP['lname'].", ".$vAP['fname']."</td>\n";
			$odata=$odata."					<td align=\"center\">\n";
			$odata=$odata."						<table width=\"110px\">\n";
			$odata=$odata."							<tr>\n";
			$odata=$odata."								<td align=\"left\">\n";
			$odata=$odata."						".str_pad($vAP['appt_mo'],2,'0',STR_PAD_LEFT)."/".str_pad($vAP['appt_da'],2,'0',STR_PAD_LEFT)."/".$vAP['appt_yr']." ";	
			$odata=$odata."								</td>\n";
			$odata=$odata."								<td align=\"right\">\n";
			$odata=$odata."						".$vAP['appt_hr'].":". str_pad(trim($vAP['appt_mn']), 2, STR_PAD_LEFT) ."";
			
			if ($vAP['appt_pa']==2)
			{
				$odata=$odata."PM";
			}
			else
			{
				$odata=$odata."AM";
			}
			
			$odata=$odata."								</td>\n";
			$odata=$odata."							</tr>\n";
			$odata=$odata."						</table>\n";
			$odata=$odata."					</td>\n";
			
			if (isset($vAP['chome']) && !in_array($vAP['chome'],$nph_ar) && strlen($vAP['chome']) > 2)
			{
				$odata=$odata."					<td align=\"center\">". format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($vAP['chome'])))."</td>\n";
			}
			elseif (isset($vAP['ccell']) && !in_array($vAP['ccell'],$nph_ar) && strlen($vAP['ccell']) > 2)
			{
				$odata=$odata."					<td align=\"center\">". format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($vAP['ccell'])))."</td>\n";
			}
			elseif (isset($vAP['cwork']) && !in_array($vAP['cwork'],$nph_ar) && strlen($vAP['cwork']) > 2)
			{
				$odata=$odata."					<td align=\"center\">". format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($vAP['cwork'])))."</td>\n";
			}
			
			$odata=$odata."					<td align=\"center\">\n";
			$odata=$odata."					<form method=\"POST\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$vAP['cid']."\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"uid\" value=\"".$uidAP."\">\n";
			
			if ($_SESSION['officeid']==$vAP['officeid'])
			{
				$odata=$odata."						<input class=\"transnb\" type=\"image\" src=\"../images/folder_open.gif\" alt=\"View Lead\">\n";
			}
			
			$odata=$odata."					</form>\n";
			$odata=$odata."					</td>\n";
			$odata=$odata."				</tr>\n";
		}
		
		$odata=$odata."				</tr>\n";
		$odata=$odata."			</table>\n";
	}
	else
	{
		$odata=$odata."No Appointments Found";
	}
	
	return $odata;
}

function get_AP_list_JSON()
{
	$dev_ar= array(2699999999999999999999999);
	$sdate	 =set_sdate();
	$nph_ar= array('**********','0000000000','none','N/A','na');
	
	$ap_ar=array();
	
	$qry = "
		select 
			C.cid,C.securityid,C.sidm,(C.clname +', '+ C.cfname) as cfullname,
			(select (fname +' '+ lname) from security where securityid=C.securityid) as srname,
			C.apptmnt as appt,
			C.scity as scity,
			C.szip1 as szip
		from 
			cinfo as C
		where
			C.officeid=".$_SESSION['officeid']."
			";
		
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			$qry  .= "	AND C.securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qry  .= "	AND C.securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
		}
	}		
	elseif ($_SESSION['llev'] < 4)
	{
		$qry .= "	and C.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry .= " 	
			and apptmnt BETWEEN '".date('m/d/y',(time() - 172800))."' and (getdate()+16)
			and appt_yr!=0
			and dupe!=1
		order by
			apptmnt asc,clname asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($_SESSION['securityid']==26999999999999999999999999999999999)
	{
		echo $qry;
	}
	
	if ($nrow > 0) {
		while ($row= mssql_fetch_array($res)) {
			$ap_ar[]=array('cid'=>$row['cid'],'cfullname'=>$row['cfullname'],'scity'=>$row['scity'],'szip'=>$row['szip'],'srname'=>$row['srname'],'cappt'=>date('m/d/y h:i a',strtotime($row['appt'])));
		}
	}
	
	return $ap_ar;
}

function get_CB_list()
{
	$dev_ar= array(2699999999999999999999999);
	$sdate	 =set_sdate();
	$nph_ar= array('**********','0000000000','none','N/A','na');
	
	$odata='';
	
	$qry  = "
		select 
			cid,officeid,securityid,lname,fname,sidm,clname,cfname,saddr1,scity,sstate,szip1,szip1,added,updated,
			hold,hold_until,hold_mo,hold_da,hold_yr,
			chome,cwork,ccell,cemail
		from 
			list_cinfo 
		where
			officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] < 4)
	{
		$qry .= "	and securityid=".$_SESSION['securityid']." ";
	}
	elseif ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
		}
	}

	$qry .= "
			and callback BETWEEN (getdate() - 7) and (getdate() + 7)
			and dupe!=1
		order by
			callback asc,clname asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($_SESSION['securityid']==269999999999999999999999999) {
		echo $qry.'<br>';
	}
	
	if ($nrow > 0) {
		while ($row= mssql_fetch_array($res)) {
			$cb_ar[$row['cid']]=$row;
		}
		
		$odata=$odata."			<table class=\"transnb\" width=\"100%\">\n";
		$odata=$odata."				<tr>\n";
		$odata=$odata."					<td align=\"center\" class=\"magenta\" width=\"20\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"magenta\" width=\"150\"><b>Customer</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"magenta\"><b>City</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"magenta\"><b>Zip</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"magenta\"><b>SalesRep</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"magenta\"><b>Callback Date</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"magenta\"><b>Phone</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"magenta\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."				</tr>\n";	
		
		$rcntCB=1;
		$ccntCB=0;
		foreach ($cb_ar as $nCB => $vCB)
		{
			$ccntCB++;
			if ($ccntCB%2)
			{
				$tbgCB = 'even';
			}
			else
			{
				$tbgCB = 'odd';
			}
			
			$uidCB  =md5(session_id().time().$vCB['cid']).".".$_SESSION['securityid'];
			$odata=$odata."				<tr class=\"".$tbgCB."\">\n";
			$odata=$odata."					<td align=\"right\">".$rcntCB++.".</td>\n";
			$odata=$odata."					<td align=\"left\">".$vCB['clname'].", ".$vCB['cfname']."</td>\n";
			$odata=$odata."					<td align=\"left\">".$vCB['scity']."</td>\n";
			$odata=$odata."					<td align=\"left\">".$vCB['szip1']."</td>\n";
			$odata=$odata."					<td align=\"left\">".$vCB['lname'].", ".$vCB['fname']."</td>\n";
			$odata=$odata."					<td align=\"center\">".$vCB['hold_mo']."/".$vCB['hold_da']."/".$vCB['hold_yr']."</td>\n";
			$odata=$odata."					<td align=\"center\">";
			
			if (isset($vCB['chome']) && !in_array($vCB['chome'],$nph_ar) && strlen($vCB['chome']) > 2)
			{
				$odata=$odata.format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($vCB['chome'])));
			}
			elseif (isset($vCB['ccell']) && !in_array($vCB['ccell'],$nph_ar) && strlen($vCB['ccell']) > 2)
			{
				$odata=$odata.format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($vCB['ccell'])));
			}
			elseif (isset($vCB['cwork']) && !in_array($vCB['cwork'],$nph_ar) && strlen($vCB['cwork']) > 2)
			{
				$odata=$odata.format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($vCB['cwork'])));
			}
			
			$odata=$odata."					</td>\n";
			$odata=$odata."					<td align=\"center\">\n";
			$odata=$odata."					<form method=\"POST\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$vCB['cid']."\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"uid\" value=\"".$uidCB."\">\n";
			
			if ($_SESSION['officeid']==$vCB['officeid'])
			{
				$odata=$odata."						<input class=\"transnb\" type=\"image\" src=\"../images/folder_open.gif\" alt=\"View Lead\">\n";
			}
			
			$odata=$odata."					</form>\n";
			$odata=$odata."					</td>\n";
			$odata=$odata."				</tr>\n";
		}
		
		$odata=$odata."			</table>\n";
	}
	else
	{
		$odata=$odata."No Callbacks Found";
	}
	
	return $odata;
}

function get_NM_list()
{
	$dev_ar= array(2699999999999999999999999);
	$sdate	 =set_sdate();	
	$nph_ar= array('**********','0000000000','none','N/A','na');
	
	$odata='';
	
	$qry = "
		select 
			cid,officeid,securityid,lname,fname,sidm,clname,cfname,saddr1,scity,sstate,szip1,szip1,added,updated,
			apptmnt,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,source,(select name from leadstatuscodes where statusid=LC.source) as lsource,
			chome,cwork,ccell 
		from 
			list_cinfo AS LC
		where
			officeid=".$_SESSION['officeid']."
			";
		
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
		}
	}		
	elseif ($_SESSION['llev'] < 4)
	{
		$qry .= "	and securityid=".$_SESSION['securityid']." ";
	}
	
	$qry .= " 	
			and added >= '".$sdate[0]."'
			and source in (select statusid from leadstatuscodes where provided=1)
		order by
			added asc,clname asc
	";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($_SESSION['securityid']==269999999999999999999999999999999999)
	{
		echo $qry.'<br>';
	}
	
	if ($nrow > 0)
	{
		while ($row= mssql_fetch_array($res))
		{
			$nm_ar[$row['cid']]=$row;
		}
		
		$odata=$odata."			<table class=\"transnb\" width=\"100%\">\n";
		$odata=$odata."			<thead>\n";
		$odata=$odata."				<tr>\n";
		$odata=$odata."					<td align=\"center\" class=\"gray\" width=\"20\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"gray\" width=\"150\"><b>Customer</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"gray\"><b>City</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"gray\"><b>Zip</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"gray\"><b>Assigned</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"gray\"><b>Source</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"gray\"><b>Source Date</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"gray\"><b>Phone</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"gray\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."				</tr>\n";
		$odata=$odata."			</thead>\n";
		
		$rcnt=1;
		$ccnt=0;
		foreach ($nm_ar as $n => $v)
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbg = 'even';
			}
			else
			{
				$tbg = 'odd';
			}
			
			$uid  =md5(session_id().time().$v['cid']).".".$_SESSION['securityid'];
			$odata=$odata."				<tr class=\"".$tbg."\">\n";
			$odata=$odata."					<td align=\"right\">".$rcnt++.".</td>\n";
			$odata=$odata."					<td align=\"left\">".trim($v['clname']).", ".trim($v['cfname'])."</td>\n";
			$odata=$odata."					<td align=\"left\">".trim($v['scity'])."</td>\n";
			$odata=$odata."					<td align=\"center\">".$v['szip1']."</td>\n";				
			$odata=$odata."					<td align=\"left\">".trim($v['lname']).", ".trim($v['fname'])."</td>\n";
			
			if ($v['source']==0)
			{
				$odata=$odata."					<td align=\"left\">bluehaven.com</td>\n";
			}
			else
			{
				$odata=$odata."					<td align=\"left\">".$v['lsource']."</td>\n";
			}
			
			$odata=$odata."					<td align=\"center\">\n";
			$odata=$odata."						<table width=\"110px\">\n";
			$odata=$odata."							<tr>\n";
			$odata=$odata."								<td align=\"left\">". date('m/d/y',strtotime($v['added'])) ."</td>\n";
			$odata=$odata."								<td align=\"right\">". date('g:iA',strtotime($v['added'])) ."</td>\n";
			$odata=$odata."							</tr>\n";
			$odata=$odata."						</table>\n";
			$odata=$odata."					</td>\n";
			
			if (isset($v['chome']) && !in_array($v['chome'],$nph_ar) && strlen($v['chome']) > 2)
			{
				$odata=$odata."					<td align=\"center\">".format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($v['chome']))) ."</td>";
			}
			elseif (isset($v['ccell']) && !in_array($v['ccell'],$nph_ar) && strlen($v['ccell']) > 2)
			{
				$odata=$odata."					<td align=\"center\">".format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($v['ccell']))) ."</td>";
			}
			elseif (isset($v['cwork']) && !in_array($v['cwork'],$nph_ar) && strlen($v['cwork']) > 2)
			{
				$odata=$odata."					<td align=\"center\">".format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($v['cwork']))) ."</td>";
			}
			else
			{
				$odata=$odata."					<td align=\"center\"><img src=\"../images/pixel.gif\"></td>";
			}
			
			$odata=$odata."					<td align=\"center\">\n";
			$odata=$odata."					<form method=\"POST\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$v['cid']."\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			
			if ($_SESSION['officeid']==$v['officeid'])
			{
				$odata=$odata."						<input class=\"transnb\" type=\"image\" src=\"../images/folder_open.gif\" alt=\"View Lead\">\n";
			}
			
			$odata=$odata."					</form>\n";
			$odata=$odata."					</td>\n";
			$odata=$odata."				</tr>\n";
			
			if (in_array($_SESSION['securityid'],$dev_ar))
			{
				$odata=$odata."				<tr class=\"".$tbg."\">\n";
				$odata=$odata."					<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
				$odata=$odata."					<td colspan=\"9\" align=\"left\">\n";
				
				//@gen_CustLinkNode($v['cid'],$uid,$v['saddr1'],$v['scity'],$v['sstate'],$v['szip1']);
				
				$odata=$odata."					</td>\n";
				$odata=$odata."				</tr>\n";
			}
		}
		
		$odata=$odata."			</table>\n";
	}
	else
	{
		$odata=$odata."No recent Leads from BHNM";
	}
	
	return $odata;
}

function get_ER_list()
{
	$dev_ar= array(2699999999999999999999999);
	$sdate	 =set_sdate();
	$nph_ar= array('**********','0000000000','none','N/A','na');
	
	$odata='';
	
	$qry = "
			select
			 C.cid
			,C.officeid
			,H.custid
			,H.mdate
			,(select max(mdate) from chistory where officeid=H.officeid and custid=H.custid and act='cresp') as MaxDate
			,(select clname + ', ' + cfname from jest..cinfo where cid=C.cid) as CustName
			,H.mtext
			,C.securityid
			,C.scity
			,C.szip1
			,C.chome
			,C.ccell
			,C.cwork
			,C.added
			,H.mdate
			,(select lname + ', ' + fname from jest..security where securityid=C.securityid) as SalesRep
		from 
			chistory as H
		inner join
			cinfo as C
		on
			H.custid=C.cid
		where
			H.officeid=".$_SESSION['officeid']."
			and C.officeid=".$_SESSION['officeid']."
			and H.act='cresp'
			and H.mdate between (getdate() - 7) and (getdate() + 1)
		";
		
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			$qry  .= "	AND C.securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qry  .= "	AND C.securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
		}
	}		
	elseif ($_SESSION['llev'] < 4)
	{
		$qry .= "	and C.securityid=".$_SESSION['securityid']." ";
	}
			
	$qry  .= "
		order by 
			MaxDate desc
	";
	
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($nrow > 0)
	{
		while ($row= mssql_fetch_array($res))
		{
			$er_ar[$row['cid']]=$row;
		}
		
		$odata=$odata."			<table class=\"transnb\" width=\"100%\">\n";
		$odata=$odata."			<thead>\n";
		
		if ($_SESSION['securityid']==2699999999999999999)
		{
			$odata=$odata."				<tr>\n";
			$odata=$odata."					<td align=\"left\" class=\"white\" colspan=\"8\">".$qry."</td>\n";
			$odata=$odata."				</tr>\n";
		}
		
		$odata=$odata."				<tr>\n";
		$odata=$odata."					<td align=\"center\" class=\"yellow\" width=\"20\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"yellow\" width=\"150\"><b>Customer</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"yellow\"><b>City</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"yellow\"><b>Zip</b></td>\n";
		$odata=$odata."					<td align=\"left\" class=\"yellow\"><b>Assigned</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"yellow\"><b>Response Date</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"yellow\"><b>Phone</b></td>\n";
		$odata=$odata."					<td align=\"center\" class=\"yellow\"><img src=\"../images/pixel.gif\"></td>\n";
		$odata=$odata."				</tr>\n";
		$odata=$odata."			</thead>\n";
		
		$rcnt=1;
		$ccnt=0;
		foreach ($er_ar as $n => $v)
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbg = 'even';
			}
			else
			{
				$tbg = 'odd';
			}
			
			$uid  =md5(session_id().time().$v['cid']).".".$_SESSION['securityid'];
			$odata=$odata."				<tr class=\"".$tbg."\">\n";
			$odata=$odata."					<td align=\"right\">".$rcnt++.".</td>\n";
			$odata=$odata."					<td align=\"left\">".trim($v['CustName'])."</td>\n";
			$odata=$odata."					<td align=\"left\">".trim($v['scity'])."</td>\n";
			$odata=$odata."					<td align=\"center\">".$v['szip1']."</td>\n";				
			$odata=$odata."					<td align=\"left\">".trim($v['SalesRep'])."</td>\n";
			$odata=$odata."					<td align=\"center\">\n";
			$odata=$odata."						<table width=\"110px\">\n";
			$odata=$odata."							<tr>\n";
			$odata=$odata."								<td align=\"left\">". date('m/d/y',strtotime($v['MaxDate'])) ."</td>\n";
			$odata=$odata."								<td align=\"right\">". date('g:iA',strtotime($v['MaxDate'])) ."</td>\n";
			$odata=$odata."							</tr>\n";
			$odata=$odata."						</table>\n";
			$odata=$odata."					</td>\n";
			
			if (isset($v['chome']) && !in_array($v['chome'],$nph_ar) && strlen($v['chome']) > 2)
			{
				$odata=$odata."					<td align=\"center\">".format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($v['chome']))) ."</td>";
			}
			elseif (isset($v['ccell']) && !in_array($v['ccell'],$nph_ar) && strlen($v['ccell']) > 2)
			{
				$odata=$odata."					<td align=\"center\">".format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($v['ccell']))) ."</td>";
			}
			elseif (isset($v['cwork']) && !in_array($v['cwork'],$nph_ar) && strlen($v['cwork']) > 2)
			{
				$odata=$odata."					<td align=\"center\">".format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($v['cwork']))) ."</td>";
			}
			else
			{
				$odata=$odata."					<td align=\"center\"><img src=\"../images/pixel.gif\"></td>";
			}
			
			$odata=$odata."					<td align=\"center\">\n";
			$odata=$odata."					<form method=\"POST\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$v['cid']."\">\n";
			$odata=$odata."						<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			
			if ($_SESSION['officeid']==$v['officeid'])
			{
				$odata=$odata."						<input class=\"transnb\" type=\"image\" src=\"../images/folder_open.gif\" alt=\"View Lead\">\n";
			}
			
			$odata=$odata."					</form>\n";
			$odata=$odata."					</td>\n";
			$odata=$odata."				</tr>\n";
		}
		
		$odata=$odata."			</table>\n";
	}
	else
	{
		$odata=$odata."No recent Customer Email Responses";
	}
	
	return $odata;
}
?>