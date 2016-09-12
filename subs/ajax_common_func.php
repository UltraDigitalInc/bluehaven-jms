<?php

function isValidEmail($s) {
	$p='/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i';
	
	if (preg_match($p,$s)) {
		return true;
	}
	else {
		return false;
	}
}

function get_EmailProfile($data=null) {
	$out=array();
	$pid=(is_null($data))?3:$data; // null==noreply@bluehaven.com
	
	$qry = "SELECT * FROM jest..EmailProfile WHERE pid=".$pid.";";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($nrow==1) {
		$row = mssql_fetch_array($res);
		$out=array('ehost'=>$row['ehost'],'elogin'=>$row['elogin'],'epswd'=>$row['epswd'],'eport'=>$row['eport']);
	}
	
	return $out;
}

function ajax_EmailSendSSL($emc)
{
    require_once('../phpmail/class.phpmailer.php');
	
	$msgid= md5(uniqid()).'@jms.bhnmi.com';
    
    $mail			= new PHPMailer();
    $mail->IsSMTP(); 							// telling the class to use SMTP
    $mail->SMTPDebug= $emc['SMTPdbg'];      	// enables SMTP debug information (for testing)
                                                // 1 = errors and messages
                                                // 2 = messages only
    $mail->SMTPAuth	= true;                  	// enable SMTP authentication
    $mail->SMTPSecure= "ssl";                	// sets the prefix to the servier
    $mail->Host		= $emc['ehost'];      		// sets GMAIL as the SMTP server
    $mail->Port		= $emc['eport'];           	// set the SMTP port for the GMAIL server
	
	$mail->Username	= $emc['elogin'];  	   		// GMAIL username
	$mail->Password	= $emc['epswd'];            // GMAIL password

    $mail->From		= $emc['from'];
    $mail->FromName	= $emc['fromname'];
	
	if (isset($emc['ereply']) && strlen($emc['ereply']) > 1)
	{
		$mail->AddReplyTo($emc['ereply'],$emc['ername']);
	}

    $mail->Subject	= $emc['esubject'];
    $mail->Body		= $emc['ebody'];
	
	if (is_array($emc['to']))
	{
		foreach ($emc['to'] as $vy)
		{
			$mail->AddAddress($vy);
		}
	}
	else
	{
		$mail->AddAddress($emc['to']);
	}
	
	if (isset($emc['efile']) && strlen($emc['efile']) > 1)
	{
		$mail->AddAttachment($emc['efile']);
	}
	
	$mail->MessageID= $msgid;
	
	if (isset($_SESSION['securityid']) and $_SESSION['securityid']==269999999999999)
	{
		print_r($emc);
	}
    
    if(!$mail->Send()) {		
		return false;
    }
    else {
		return true;
    }
}

class AuthUser {
	public $sid;
	
	function __construct($rsid) {
		$this->sid=$rsid;
	}
	
	function get_sid() {
		return $this->sid;
	}
	
	function validSidType() {
		if (is_numeric($this->sid))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function validLogid($logid) {
		
	}
	
	function validPass($pass) {
		
	}
}

function checkSecurityAccess($sid,$oid,$slev,$type) {
    $out=false;
    $tmap=array('elev'=>1,'clev'=>3,'jlev'=>5,'llev'=>7,'rlev'=>9,'mlev'=>11,'tlev'=>13);
	$kmap=array('elev'=>0,'clev'=>1,'jlev'=>2,'llev'=>3,'rlev'=>4,'mlev'=>6,'tlev'=>6);
    
    $qry0 = "SELECT securityid,officeid,slevel FROM security WHERE securityid=".(int) $sid." and substring(slevel,13,1)!=0;";
    $res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0!=0) {
		$row0 = mssql_fetch_array($res0);
		$slevel=explode(',',$row0['slevel']);
		
        if ($row0['officeid']==89) {
            $out=true;
        }
		elseif ($row0['officeid']==$oid) {
			if ($slev >= $slevel[$kmap[$type]]) {
				$out=true;
			}
		}		
        else {
            $qry1 = "SELECT oid FROM alt_security_levels WHERE sid=".(int) $sid." and oid=".(int) $oid." and substring(slevel,".$tmap[$type].",1) >= ".(int) $slev." and substring(slevel,13,1)!=0;";
            $res1 = mssql_query($qry1);
            $nrow1= mssql_num_rows($res1);
            
            if ($nrow0>=1) {
                $out=true;
            }
        }
    }
    
    return $out;
}

function LeadAssets($cid) {
    $out=false;
	
    $qry0 = "SELECT cid FROM cinfo WHERE cid=".(int) $cid.";";
    $res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0!=0) {
		$row0 = mssql_fetch_array($res0);
		
		$qry1 = "SELECT estid FROM est WHERE ccid=".(int) $row0['cid'].";";
        $res1 = mssql_query($qry1);
        $nrow1= mssql_num_rows($res1);
		
        $qry2 = "SELECT jid FROM jobs WHERE custid=".(int) $row0['cid'].";";
        $res2 = mssql_query($qry2);
        $nrow2= mssql_num_rows($res2);
		
		if ($nrow1!=0 or $nrow2!=0) {
			$out=true;
		}
    }
    
    return $out;
}

function isValidDate($strDate)
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

function checkCrud($req,$x,$d,$u,$r,$c) {
	$out=false;
	
	if (($req==$x and $x==1) || ($req==$d and $d==1) || ($req==$u and $u==1) || ($req==$r and $r==1) || ($req==$c and $c==1)) {
		$out=true;
	}
	
	return $out;
}

function testRequest() {
	$out=array();
	
    foreach ($_REQUEST as $n=>$v) {
        $out[$n]=$v;
	}

	return $out;
}

function checkAuth() {
	$out =false;
	$sid =(isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)?$_SESSION['securityid']:null;
	$grp =(isset($_REQUEST['grp']) and strlen($_REQUEST['grp']) > 0)?$_REQUEST['grp']:null;
	$obj =(isset($_REQUEST['call']) and strlen($_REQUEST['call']) > 0)?$_REQUEST['call']:null;
	$req =(isset($_REQUEST['req']) and strlen($_REQUEST['req']) > 0)?$_REQUEST['req']:null;
	
	if ((!is_null($sid) and $sid!=0) and (!is_null($grp) and !is_null($obj) and !is_null($req))) {
		echo 'Tier 1<br>';
		
		$qry1 = "select sessionid from logstate where securityid=".(int) $sid.";";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		$nrow1= mssql_num_rows($res1);
		
		if ($nrow1 == 1 and trim($row1['sessionid'])===session_id()) {
			echo 'Tier 2<br>';
			
			$qry2 = "SELECT a.aclid,a.grp,a.call,a.x,a.d,a.u,a.r,a.c FROM security_acl AS a WHERE a.sid=".(int) $sid." AND call='".$obj."';";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);
			$nrow2= mssql_num_rows($res2);
			
			if ($nrow2==1 and checkCrud($req,$row2['x'],$row2['d'],$row2['u'],$row2['r'],$row2['c'])) {
				echo 'Tier 3<br>';
			}
			else
			{
				echo 'Fail Tier 3<br>';
			}
		}
	}
}

function get_Request_Info()
{
	echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';
	exit;
}

function isTimeOut()
{	
	if (isset($_SESSION['last_access']))
	{
		$max_seconds=10800;
		$idle_time=time()-$_SESSION['last_access'];
	
		if ($idle_time < $max_seconds)
		{
			$_SESSION['last_access']=time();
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return false;
	}
}

function isLoggedIn()
{
	if (isset($_SESSION['last_access']))
	{
		$last_access	=$_SESSION['last_access'];
		$max_seconds	=10800;
		$idle_time		=time()-$last_access;
		
		/*
		echo $last_access.'<br>';
		echo $max_seconds.'<br>';
		echo $idle_time.'<br>';
		*/
		
		if ($idle_time < $max_seconds)
		{
			$_SESSION['last_access']=time();
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function isLoggedInNew()
{
	if (isset($_SESSION['last_access']))
	{
		$last_access	=$_SESSION['last_access'];
		$max_seconds	=10800;
		$idle_time		=time()-$last_access;
		
		if ($idle_time < $max_seconds)
		{
			$_SESSION['last_access']=time();
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function getLogState($sid)
{	
	$qry = "SELECT id,securityid from jest..logstate where securityid=".(int) $sid.";";
	$res = mssql_query($qry);
	$nrows = mssql_num_rows($res);
	
	if ($nrows >= 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function checkOfficeAccess($tstoid,$sid) {
	$out=false;
	
	$qryS = "SELECT securityid,officeid,substring(slevel,13,1) as aslevel from jest..security where securityid=".(int) $sid.";";
	$resS = mssql_query($qryS);
	$nrowS = mssql_num_rows($resS);
	
	if ($nrowS == 1) {
		$rowS = mssql_fetch_array($resS);
		
		if ($rowS['aslevel'] >=1) {
			$o_ar=array(89);
			
			$qryO = "SELECT officeid,securityid,substring(slevel,13,1) as aslevel from jest..offices where officeid=".(int) $tstoid.";";
			$resO = mssql_query($qryO);
			$rowO = mssql_fetch_array($resO);
			
			$o_ar[]=$rowO['officeid'];
			
			$qryA = "SELECT oid from jest..alt_security_levels where sid=".(int) $rowS['securityid']." AND substring(slevel,13,1) >= 1;";
			$resA = mssql_query($qryA);
			
			while ($rowA = mssql_fetch_array($resA)) {
				$o_ar[]=$rowA['oid'];
			}
			
			if (in_array($rowS['officeid'],$o_ar)) {
				return true;
			}
		}
	}
	
	return $out;
}

function ValidOffice($soid,$roid,$sauth)
{
	if ((int)$soid === (int)$roid or (int)$sauth===9)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function date_format_US($m,$d,$y,$h,$i,$a)
{
	$dtxt	="";
	$ap	="";
	
	if ($a==1)
	{
		$ap="am";
	}
	else
	{
		$ap="pm";
	}
	
	if ($i=="0")
	{
		$mn="00";
	}
	else
	{
		$mn=$i;
	}

	$dtxt=$m."/".$d."/".$y." ".$h.":".$mn." ".$ap;
	
	//echo $dtxt;
	return trim($dtxt);
}

function calc_internal_area($pft,$sqft,$shallow,$middle,$deep) {
	$ia=((($shallow+$middle+$deep)/3)*$pft)+$sqft;
	$ia=(is_float($ia))?round($ia):$ia;
	return $ia;
}

function calc_gallons($pft,$sqft,$shallow,$middle,$deep) {
	$gals=((($shallow+$middle+$deep)/3)*$sqft)*7.5;
	$gals=(is_float($gals))?round($gals):$gals;
	return $gals;
}

function ajaxCalcLoop($ivals,$qtype,$bp,$rp,$lr,$hr,$quan,$def_quan,$a1,$a2,$a3,$poolcalc) {
	if ($_SESSION['securityid']==26999999999999999) {
		error_reporting(E_ALL);
		ini_set('display_errors','On');
		display_array($ivals);
	}
	
	$subcm=0;
	
	if ($qtype==1||$qtype==31||$qtype==33||$qtype==77) {// Fixed - Quantity - Bid Item
		//temp fix
		if ($quan < 0) {
			$quan_out	= -1;
		}
		else {
			$quan_out	= 1;
		}
		
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
		//echo $rp;
	}
	elseif ($qtype==2) {// Quantity
		$quan_out=$quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==3) {// PFT
		$quan_out=$ivals['ps1'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==4) { // SQFT
		$quan_out=$ivals['ps2'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==5) {// Base+ (PFT)
		$quan_out=$ivals['ps1'];
		if ($quan_out > $hr) {
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else {
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==6) { // Base+ (SQFT)
		$quan_out=$ivals['ps2'];
			if ($quan_out > $hr) {
				$subbp=$bp+(($quan_out-$hr)*$def_quan);
				$subrp=$rp+(($quan_out-$hr)*$def_quan);
			}
			else {
				$subbp=$bp;
				$subrp=$rp;
			}
	}
	elseif ($qtype==7) { // Base+ (IA)
		$quan_out=$iarea;
		if ($quan_out > $hr) {
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else {
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==8) {// Base+ (Fixed)
		$quan_out=$quan;
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==9) {// Bracket (PFT)
		$quan_out=$ivals['ps1'];
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==10) {// Bracket (SQFT)
		$quan_out=$ivals['ps2'];
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==11) {// Bracket (IA)
		$quan_out=$iarea;
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==12) { // Bracket (Gallons)
		$quan_out=$gals;
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==13) { // Checkbox (PFT)
		$quan_out=$ivals['ps1'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==14) {// Checkbox (SQFT)
		$quan_out=$ivals['ps2'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==15) {// Checkbox (Quantity)
		$quan_out=$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==16) {// Checkbox (IA)
		$quan_out=$iarea;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==17) {// Checkbox (Gallons)
		$quan_out=$gals;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==45) {// Peri Deck Incl(Cost is Base+)
		$quan_out=$ivals['ps1']*2.16;
		if ($quan_out > $hr) {
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else {
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==46) {// IA (Div by CalcAmt)
		$quan_out=$iarea/$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==47) {// IA (Mult by CalcAmt)
		$quan_out=$iarea*$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==48) {// Base Inclusion (Quantity)
		$quan_out=$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==53) {// Permit
		if ($bp==0) {
			$qrypst0 ="SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
			$respst0 =mssql_query($qrypst0);
			$rowpst0 =mssql_fetch_array($respst0);

			if ($rowpst0['stax']==1) {
				$qry1a ="SELECT cid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
				$res1a =mssql_query($qry1a);
				$row1a =mssql_fetch_array($res1a);

				$qry1b ="SELECT scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row1a[0]."';";
				$res1b =mssql_query($qry1b);
				$row1b =mssql_fetch_array($res1b);

				$qry1 ="SELECT permit,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1b[0]."';";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$quan_out=$row1['permit'];
				$subbp=$quan_out;
				$subrp=$quan_out;
			}
			else {
				$quan_out=$quan;
				$subbp=$quan_out;
				$subrp=$quan_out;
			}
		}
		else {
			$quan_out=$bp;
			$subbp=$quan_out;
			$subrp=$quan_out;
		}
	}
	elseif ($qtype==54) {// Referral
		$quan_out=$quan;
		$subbp=$quan_out;
		$subrp=$quan_out;
	}
	elseif ($qtype==55 || $qtype==72) {// Package (Quantity) - Package (Checkbox)
		$quan_out=$quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==56) {// IA (Div by CalcAmt) Base+
		if ($iarea > $a2) {
			$calc1=$a2/$def_quan;
			$calc2=($iarea-$a2)/$def_quan;
			$quan_out=$calc1+$calc2;
			$subbp=$bp+($calc2*$a3);
			$subrp=$rp+($calc2*$a3);			
		}
		else {
			$calc=$iarea/$def_quan;
			$quan_out=$calc;
			$subbp=$bp;
			$subrp=$rp;
			//echo "INSIDE ($subbp)<BR>";
		}
	}
	elseif ($qtype==57) // Gallons (Total)
	{
		$quan_out=$gals;
		$subbp=$quan_out;
		$subrp=$quan_out;
	}
	elseif ($qtype==58) // Base+ (Quantity)
	{
		$quan_out=$quan;
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==59) // Elec Run (Total)
	{
		$quan_out=$ivals['erun'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==60) // Plumb Run (Total)
	{
		$quan_out=$ivals['prun'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==61) // SPA PFT
	{
		$quan_out=$spa2;
		$subbp=$bp*$quan_out;
		$subrp=$bp*$quan_out;
	}
	elseif ($qtype==62) // SPA SQFT
	{
		$quan_out=$spa3;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==63) // SPA IA
	{
		$quan_out=$spa_ia;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==64) // SPA Gallons
	{
		$subbp=$bp*$spa_gl;
		$subrp=$bp*$spa_gl;
	}
	elseif ($qtype==65) // SPA PFT Base+ (Quantity)
	{
		$quan_out=$spa2;
		if ($quan_out > $hr) {
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else {
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==66) // SPA SQFT Base+ (Quantity)
	{
		$quan_out=$spa3;
		if ($quan_out > $hr) {
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else {
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==67) // SPA IA Base+ (Quantity)
	{
		$quan_out=$spa_ia;
		if ($quan_out > $hr) {
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else {
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==68) // SPA IA Base+ (Quantity)
	{
		$quan_out=$spa_gl;
		if ($quan_out > $hr) {
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else {
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==69) // Base+ (Depth)
	{
		$quan_out=$ivals['ps7'];
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==70) // Depth
	{
		$quan_out=$ivals['ps7'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==71) // Deck (Total Base+)
	{
		$quan_out=$ivals['deck'];
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==73) // Peri (Div by CalcAmt)
	{
		$quan_out=$ivals['ps1']/$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==74) // SA (Div by CalcAmt)
	{
		$quan_out=$ivals['ps2']/$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==75) // Peri (Div by CalcAmt) Base +
	{
		if ($ivals['ps1'] > $a2)
		{
			$calc1=$a2/$def_quan;
			$calc2=($ivals['ps1']-$a2)/$def_quan;
			$quan_out=$calc1+$calc2;
			$subbp=$bp+($calc2*$a3);
			$subrp=$rp+($calc2*$a3);
		}
		else
		{
			$calc=$ivals['ps1']/$def_quan;
			$quan_out=$calc;
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==76) // SA (Div by CalcAmt) Base +
	{
		if ($ivals['ps2'] > $a2)
		{
			$calc1=$a2/$def_quan;
			$calc2=($ivals['ps2']-$a2)/$def_quan;
			$quan_out=$calc1+$calc2;
			$subbp=$bp+($calc2*$a3);
			$subrp=$rp+($calc2*$a3);
		}
		else
		{
			$calc=$ivals['ps2']/$def_quan;
			$quan_out=$calc;
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==77) // Base+ (Quan Calc Fixed)
	{
		$quan_out=$quan;
		if ($quan_out > $hr)
		{
			$subbp=$def_quan;
			$subrp=$def_quan;
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==78) // Contract Amt Multiplier
	{
		$quan_out=$ivals['camt'] * $def_quan;
		$subbp=$quan_out;
		$subrp=$quan_out;
	}
	else // Catch Bucket
	{
		$quan_out	=0;
		$subbp		=0;
		$subrp		=0;
	}
	
	if (isset($poolcalc) and !is_null($poolcalc) and $poolcalc!=0) {
		//$pquan=(isset($ivals['ps1']) and $ivals['ps1']!=0)?$ivals['ps1']:(isset($ivals['ps2']) and $ivals['ps2']!=0)?$ivals['ps2']:0;
		$qryPC ="SELECT officeid,quan,quan1,price,comm FROM rbpricep WHERE officeid=".(int) $ivals['oid']." AND quan=".$quan.";";
		$resPC =mssql_query($qryPC);
		$rowPC =mssql_fetch_array($resPC);
		
		$quan_out	=$rowPC['quan'];
		$subbp		=$rowPC['price'];
		$subrp		=$rowPC['price'];
		$subcm		=$rowPC['comm'];
	}

	$out=array('calcbp'=>round($subbp),'calcrp'=>$subrp,'calcqn'=>$quan_out,'calccm'=>$subcm);
	return $out;
}

function ajaxEventProc($rec=null)
{
	if (is_array($_SERVER) and is_array($_SESSION) and is_array($_REQUEST)){		
		if ($_SERVER['REQUEST_METHOD']=='POST' or $rec > 0)	{
			$oid=(isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)?$_REQUEST['oid']:0;
			$sid=(isset($_REQUEST['sid']) and $_REQUEST['sid']!=0)?$_REQUEST['sid']:0;
			$qs=($_SERVER['REQUEST_METHOD']=='GET')?$_SERVER['QUERY_STRING']:json_encode($_REQUEST);
			
			$qry  = "insert into jest_stats..ajaxevents ";
			$qry .= "(sessionOID,sessionSID,requestOID,requestSID,REQUEST_TIME,SERVER_ADDR,HTTP_HOST,REMOTE_ADDR,REQUEST_METHOD,SCRIPT_NAME,QUERY_STRING,HTTP_USER_AGENT) values ";
			$qry .= "(". (int) $_SESSION['officeid'].",". (int) $_SESSION['securityid'].",";
			$qry .= "". $oid .",". $sid .",". (int) $_SERVER['REQUEST_TIME'] .",";
			$qry .= "'". $_SERVER['SERVER_ADDR'] ."','". $_SERVER['HTTP_HOST'] ."',";
			$qry .= "'". $_SERVER['REMOTE_ADDR'] ."','". $_SERVER['REQUEST_METHOD'] ."','". $_SERVER['SCRIPT_NAME'] ."',";
			$qry .= "'". $qs ."','". $_SERVER['HTTP_USER_AGENT'] ."');";
			$res = mssql_query($qry);
		}
	}
}

function XMLToArray($xml)
{
	if ($xml instanceof SimpleXMLElement)
	{
		$children = $xml->children();
		$return = null;
	}
	
	foreach ($children as $element => $value)
	{
		if ($value instanceof SimpleXMLElement)
		{
			$values = (array)$value->children();
		   
			if (count($values) > 0)
			{
				$return[$element] = XMLToArray($value);
			}
			else
			{
				if (!isset($return[$element]))
				{
					$return[$element] = (string)$value;
				}
				else
				{
					if (!is_array($return[$element]))
					{
						$return[$element] = array($return[$element], (string)$value);
					}
					else
					{
						$return[$element][] = (string)$value;
					}
				}
			}
		}
	}
	
	if (is_array($return))
	{
		return $return;
	}
	else
	{
		return $false;
	}
} 

function isValidUser($esid) {
	// This function will validate the integrity of the User when processing requests via JMS Ajax
	$dbg=0;

	if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0) {
		$sessUserVar=md5($_SESSION['securityid'].'.'.substr($_SESSION['lname'],0,2));
		if (isset($_SESSION['SessHash']) and trim($_SESSION['SessHash'])===trim($sessUserVar)) {
			if (isset($esid) and strlen($esid) > 5) {
				//include ('../connect_db.php');
				$qry0 = "SELECT securityid,officeid,login,slevel FROM security WHERE securityid=".(int) $_SESSION['securityid'].";";
				$res0 = mssql_query($qry0);
				$nrow0= mssql_num_rows($res0);
				
				//echo '<br>'.$qry0.'<br>';
				
				if ($nrow0 == 1) {
					$row0 = mssql_fetch_array($res0);
					$slevs=explode(',',$row0['slevel']);
					
					if ($slevs[6] > 0) {
						if ($dbg==1){echo "<br>Authorized User (" . __LINE__ . ")";}
						return true;
					}
					else {
						if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
						return false;	
					}
				}
				else {
					if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
					return false;
				}
			}
			else {
				if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
				return false;
			}
		}
		else {
			if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
			return false;
		}
	}
	else {
		if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
		return false;
	}
}

function isValidSecurity($sid,$oid,$slev,$type) {
    $out=false;
	if ((isset($sid) and $sid!=0) and (isset($oid) and $oid!=0) and (isset($slev) and $slev!=0)) {
		$qry0 = "SELECT securityid,officeid,slevel FROM security WHERE securityid=".(int) $sid.";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0!=0) {
			$row0 	= mssql_fetch_array($res0);
			
			$slevel	= explode(',',$row0['slevel']);
			$aoid	= 89;
			$ooid	= $row0['officeid'];
			$ssid	= (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)?$_SESSION['securityid']:0;
			
			$qry1 = "SELECT securityid,officeid,slevel FROM security WHERE securityid=".(int) $ssid.";";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);
			$xoid = $row1['officeid'];
			$xlevel	= explode(',',$row1['slevel']);
			
			$soid	= (isset($_SESSION['officeid']) and $_SESSION['officeid']!=0)?$_SESSION['officeid']:0;			
			$tmap	= array('elev'=>0,'clev'=>1,'jlev'=>2,'llev'=>3,'rlev'=>4,'mlev'=>5,'tlev'=>6,'slev'=>7);
			
			//echo $xoid.':'.$aoid;
			if ((isset($slevel[$tmap[$type]]) and $slevel[$tmap[$type]] < 5) and ($ooid==$oid and $sid==$ssid and $slevel[$tmap[$type]] >= $slev)) {
				// Below Security Level 5 in Office Context
				$out=true;
			}
			elseif ((isset($xlevel[$tmap[$type]]) and $xlevel[$tmap[$type]] >= 5) and (($ooid==$oid or $xoid==$oid) and $xlevel[$tmap[$type]] >= $slev)) {
				// Security Level 5 and above in Office Context
				$out=true;
			}
			elseif ((isset($xlevel[$tmap[$type]]) and $xlevel[$tmap[$type]] >= 5) and $xoid==$aoid) {
				// Security Level 5 and above in Admin Office Context
				$out=true;
			}
			else {
				// Alternate Office Security Levels
				$qry1 = "SELECT slevel FROM alt_security_levels WHERE sid=".(int) $sid." and oid=".(int) $oid.";";
				$res1 = mssql_query($qry1);
				$nrow1= mssql_num_rows($res1);
				
				if ($nrow1=!0) {
					$row1 = mssql_fetch_array($res1);
					$aslevel=explode(',',$row1['slevel']);
					if (isset($aslevel[$tmap[$type]])) {
						if ($aslevel[$tmap[$type]] < 5 and ($sid==$ssid and $aslevel[$tmap[$type]] >= $slev)) {
							$out=true;
						}
						elseif ($aslevel[$tmap[$type]] >= 5 and $aslevel[$tmap[$type]] >= $slev) {
							$out=true;
						}
					}
				}
			}
		}
	}

    return $out;
}

function BlockIllegalChar($in)
{
	//$ichr=array('\'','\"',';',':','%','^');
	$ichr='/\'/i';
	
	if (preg_match($ichr,$in))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function ConvertToBool($in)
{
	$out=array();
	
	if (is_array($in))
	{
		foreach($in as $n=>$v)
		{
			$out[$n]=settype($v,'bool');
			
		}
	}
	
	return $out;
}

function CheckFunctionAccess($s,$a)
{
	$r=false;
	$o=array();
	$out=array();
	$qry0 = "SELECT * FROM SecurityLevel WHERE sid=".$s." AND sgKeyword='".$a."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 == 1)
	{
		$row0 = mssql_fetch_array($res0);
		
		if ($row0['sgRead'] > 0)
		{
			$o=array('C'=>$row0['sgCreate'],'R'=>$row0['sgRead'],'U'=>$row0['sgUpdate'],'D'=>$row0['sgDelete']);
			$r=true;
		}
	}
	
	$out=array($r,$o);
	
	return $out;
}

function isValidUserExt($elog,$epwd,$efnc=0)
{
	// This function will validate the integrity of the User when processing requests via AJAX Request
	$t='';
	$r=false;

	if (isset($elog) and (strlen($elog) >= 4 and strlen($elog) < 17))
	{
		if (isset($epwd) and (strlen($epwd) > 5 and strlen($epwd) < 17))
		{
			/*
			if (BlockIllegalChar($_REQUEST['logid']) or BlockIllegalChar($_REQUEST['pswd']))
			{
				$t="Illegal Login Data (" . __LINE__ . ")";
				exit;
			}
			else
			{
				
			}
			*/
			
			include ('../connect_db.php');
			$qry0 = "SELECT securityid,officeid,login,pswd,slevel,passcnt FROM security WHERE login='".trim($elog)."';";
			$res0 = mssql_query($qry0);
			$nrow0= mssql_num_rows($res0);
			
			if ($nrow0 == 1)
			{
				$row0 = mssql_fetch_array($res0);
				$slevs=explode(',',$row0['slevel']);
				
				if ($slevs[6] != 0)
				{
					if ($row0['passcnt'] < 5)
					{
						if (md5(trim($epwd)) === trim($row0['pswd']))
						{
							$qry1 = " UPDATE security set passcnt=0 WHERE securityid=".$row0['securityid'].";";
							$res1 = mssql_query($qry1);
							
							$ecfc=CheckFunctionAccess($row0['securityid'],$efnc);
							
							if ($ecfc[0])
							{
								$r=true;
								$t="Authorized (" . __LINE__ . ") (Create:".$ecfc[1]['C']." Read:".$ecfc[1]['R']." Update:".$ecfc[1]['U']." Delete:".$ecfc[1]['D'].")";
							}
							else
							{
								$t="No Function Access (" . __LINE__ . ") (".$row0['passcnt'].")";
							}
						}
						else
						{
							$qry1 = " UPDATE security set passcnt=(passcnt + 1) WHERE securityid=".$row0['securityid'].";";
							$res1 = mssql_query($qry1);
							
							$t="Password Incorrect (" . __LINE__ . ") (".$row0['passcnt'].")";
						}
					}
					else
					{
						$t="Account Locked Out (" . __LINE__ . ") (".$row0['passcnt'].")";
					}
				}
				else
				{
					$t="Account Deactivated (" . __LINE__ . ")";
				}
			}
			elseif ($nrow0 > 1)
			{
				$t="Account Error (" . __LINE__ . ")";
			}
			else
			{
				$t="Account Not Found (" . __LINE__ . ")";
			}
		}
		else
		{
			$t="Invalid Password (" . __LINE__ . ")";
		}
	}
	else
	{
		$t="Invalid Login (" . __LINE__ . ")";
	}
	
	$out=array($r,$t);
	
	return $out;
}

function get_CustomerInfo_Estimate_Edit($oid,$estid)
{
	if ($oid!=0 and $estid!=0)
	{
		$qryA = "SELECT
						e.estid,e.pft,e.sqft,e.spatype,e.spa_pft,e.spa_sqft,
						e.status,e.shal,e.mid,e.deep,e.cid,e.securityid,e.renov,
						(select cfname from jest..cinfo where cid=e.cid) as ccfname,
						(select clname from jest..cinfo where cid=e.cid) as cclname,
						(select fname from jest..security where securityid=e.securityid) as srfname,
						(select lname from jest..security where securityid=e.securityid) as srlname
					FROM est AS e WHERE officeid=".(int) $oid." AND estid=".(int) $estid.";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		$out ='';
		$out.="			<table class=\"transnb\" align=\"center\" width=\"100%\" border=0>\n";
		$out.="				<tr>\n";
		$out.="					<td colspan=\"2\" align=\"left\"><b>Edit Retail Estimate</td>\n";
		$out.="					<td align=\"right\">\n";
	
		if ($rowA['renov']==1)
		{
			$out.="               <b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\" CHECKED>\n";	
		}
		else
		{
			$out.="               <b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\">\n";	
		}
		
		$out.="					</td>\n";
		$out.="					<td colspan=\"2\" class=\"gray\" align=\"right\"><b>Estimate</b> ".$estid."</td>\n";
		$out.="				</tr>\n";
		$out.="				<tr>\n";
		$out.="					<td colspan=\"2\" align=\"left\"><b>Customer</b> ".$rowA['ccfname']." ".$rowA['cclname']."</td>\n";
		$out.="					<td align=\"center\"><b>Perimeter</b> ".$rowA['pft']."  <b>Surface</b> ".$rowA['sqft']."  <b>Shallow</b> ".$rowA['shal']."  <b>Middle</b> ".$rowA['mid']."  <b>Deep</b> ".$rowA['deep']."</td>\n";
		$out.="					<td colspan=\"2\" valign=\"bottom\" align=\"right\"><b>SalesRep</b> ".$rowA['srfname']." ".$rowA['srlname']."</td>\n";
		$out.="            </tr>\n";
		$out.="         </table>\n";
	}
	else
	{
		$out="Est==0";
	}
	
	return $out;
}

?>