<?php
ini_set('display_errors','On');
error_reporting(E_ALL);

//print_r($_REQUEST);

function getEmailStart($in=false) {
	$out=array('error'=>true,'result'=>'Queue Process Error ('.__LINE__.')');
	if ($in) {
		$chkin		= time();
		$adminid	= 1797;		
		$hostname   = "192.168.100.67";
		$username   = "jmsauth";
		$password   = "into99black";
		$dbname     = "jms_email_queue";
		
		mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
		mssql_select_db($dbname) or die("Table unavailable");
		
		$qry0 = "SELECT pid,checkin,checkout,sid,numproc FROM EmailProc WHERE checkout=0 ORDER BY checkin DESC;";
		$res0 = mssql_query($qry0);
        $nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0) {
            $pid=array();
            while ($row0 = mssql_fetch_array($res0)) {
                $pid[$row0['pid']]=array(
                     'checkin'=>	$row0['checkin']
                    ,'checkout'=>	$row0['checkout']
                    ,'sid'=>		$row0['sid']
                    ,'numproc'=>	$row0['numproc']
					,'queue'=>		array()
                );
            }
            
			foreach ($pid as $np=>$vp) {
				$qry1 = "SELECT qid,pid,etid,cid,sid,adate,pdate,msgid FROM EmailQueue WHERE pid=".$np." AND pdate=0 ORDER BY qid ASC;";
				$res1 = mssql_query($qry1);
				$nrow1= mssql_num_rows($res1);
				
				if ($nrow1 > 0) {
					while ($row1 = mssql_fetch_array($res1)) {
						$pid[$np]['queue'][$row1['qid']]=array(
							 'qid'=>	$row1['qid']
							,'pid'=>	$row1['pid']
							,'etid'=>	$row1['etid']
							,'cid'=>	$row1['cid']
							,'sid'=>	$row1['sid']
							,'adate'=>	$row1['adate']
							,'pdate'=>	$row1['pdate']
							,'msgid'=>	$row1['msgid']
						);
					}
				}				
			}

            $out['error']=false;
            $out['result']=$pid;
		}
		else {
			$out=array('error'=>true,'result'=>'Nothing in Queue ('.__LINE__.')');
		}
		mssql_close();
	}
	else {
		$out=array('error'=>true,'result'=>'Queue Process Error ('.__LINE__.')');
	}	
	return $out;
}

function getEmailContent($in=null) {
	if (!is_null($in) and is_array($in)) {
		$bme		= (isset($_REQUEST['bme']) and strlen($_REQUEST['bme']) > 0)?$_REQUEST['bme']:'';
		$btime		= strtotime('1/1/2000');
		$hostname   = "192.168.100.45";
		$username   = "jestadmin";
		$password   = "into99black";
		$dbname     = "jest";
		mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
		mssql_select_db($dbname) or die("Table unavailable");
		
		foreach ($in as $n=>$v) {
			$qryC = "SELECT
						c.cid,c.cemail,c.cfname,c.clname,c.officeid as oid,
						(select name from offices where officeid=c.officeid) as office,
						(select phone from offices where officeid=c.officeid) as officephone,
						(select fname + ' ' + lname  from security where securityid=(select gm from offices where officeid=c.officeid)) as gmfull,
						(select fname + ' ' + lname  from security where securityid=c.securityid) as srep,
						c.apptmnt
					FROM jest..cinfo as c WHERE c.cid=".$v['cid'].";";
			$resC = mssql_query($qryC);
			$nrowC= mssql_num_rows($resC);
			
			if ($nrowC > 0) {
				while ($rowC = mssql_fetch_array($resC)) {
					$apptmnt=(isset($rowC['apptmnt']) and (strtotime($rowC['apptmnt']) >= $btime))?date('l F jS Y',strtotime($rowC['apptmnt'])).' at '.date('h:i A',strtotime($rowC['apptmnt'])):'';
					$in[$n]['lead']=array(
						'cid'=>$rowC['cid'],
						'oid'=>$rowC['oid'],
						'email'=>$rowC['cemail'],
						'fname'=>htmlspecialchars_decode($rowC['cfname']),
						'lname'=>htmlspecialchars_decode($rowC['clname']),
						'name'=>htmlspecialchars_decode($rowC['cfname']).' '.htmlspecialchars_decode($rowC['clname']),
						'office'=>$rowC['office'],
						'officephone'=>$rowC['officephone'],
						'gmfull'=>$rowC['gmfull'],
						'srep'=>$rowC['srep'],
						'srepphone'=>$rowC['officephone'],
						'apptmnt'=>$apptmnt,
						'bme'=>$bme
					);
				}
			}
			
			$qryT = "SELECT t.etid,t.name,t.esubject,t.ebody,t.active,t.fileattach,t.ishtml,t.epid FROM jest..EmailTemplate AS t WHERE t.etid=".$v['etid'].";";
			$resT = mssql_query($qryT);
			$nrowT= mssql_num_rows($resT);
			
			if ($nrowT > 0) {
				while ($rowT = mssql_fetch_array($resT)) {
					$lead=$in[$n]['lead'];
					$ef=EmailFormat($lead,$rowT['esubject'],$rowT['ebody']);
					
					$in[$n]['template']=array(
						'procid'=>$n,
						'pid'=>$in[$n]['pid'],
						'cid'=>$lead['cid'],
						'oid'=>$lead['oid'],
						'etid'=>$rowT['etid'],
						'email'=>$lead['email'],
						'subject'=>$ef['subject'],
						'body'=>$ef['body'],
						'active'=>$rowT['active'],
						'name'=>$rowT['name'],
						'fileattach'=>trim($rowT['fileattach']),
						'ishtml'=>$rowT['ishtml'],
						'epid'=>$rowT['epid']
					);
				}
			}
		}		
	}
	return $in;
}

function setEmailList($in=null) {
	$out=array();
	if (!is_null($in) and is_array($in)) {
		$ec=array();
		foreach ($in['result'] as $n1=>$v1) {
			if (isset($v1['queue']) and count($v1['queue']) > 0) {
				foreach ($v1['queue'] as $n2=>$v2) {
					$ec[$n2]=array('pid'=>$v2['pid'],'etid'=>$v2['etid'],'cid'=>$v2['cid']);
				}
			}
		}
		
		$out=$ec;
	}
	return $out;
}

function EmailFormat($content,$subject=null,$body=null) {
	$out=array();
	
	$srch_ar=array(
		0=>'/CUSTOMERFULLNAME/',
		1=>'/CUSTOMERFIRSTNAME/',
		2=>'/CUSTOMERLASTNAME/',
		3=>'/CUSTOMEREMAILADDRESS/',
		4=>'/OFFICEPHONENUMBER/',
		5=>'/GMFULLNAME/',
		6=>'/SALESREPFULLNAME/',
		7=>'/CORPORATEFULLNAME/',
		8=>'/SALESREPPHONENUMBER/',
		9=>'/APPOINTMENTDATETIME/',
		10=>'/BLANKMESSAGEENTRY/'
	);
        
    $res_ar =array(
        0=>$content['name'],
		1=>$content['fname'],
		2=>$content['lname'],
		3=>$content['email'],
		4=>$content['officephone'],
		5=>$content['gmfull'],
		6=>$content['srep'],
		7=>$content['office'],
		8=>$content['srepphone'],
		9=>$content['apptmnt'],
		10=>$content['bme']
    );

	if (!is_null($subject) and !is_null($body)) {
		$out['subject']=preg_replace($srch_ar,$res_ar,$subject);
		$out['body']=preg_replace($srch_ar,$res_ar,$body);
	}
	
	return $out;
}


function MailSend($in) {
	require_once('../phpmail/class.phpmailer.php');
    include_once('../phpmail/class.smtp.php'); // optional, gets called from within class.phpmailer.php if not already loaded
	$out=array();
	$msgid			  = md5(uniqid()).'@jms.bhnmi.com';
    $mail             = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing) 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "bhcustcare@bluehaven.com";  // GMAIL username
    $mail->Password   = "nuvo2029";            // GMAIL password
    
    $mail->SetFrom('bhcustcare@bluehaven.com', 'Blue Haven Customer Care');    
    $mail->Subject    = $in['subject'];
	
	if (isset($in['ishtml']) && $in['ishtml'] == 1) {
		$mail->isHTML(true);
		$mail->MsgHTML($in['body']);
	}
	else {
		$mail->isHTML(false);
		$mail->Body    = $in['body'];
	}
    
    $mail->AddAddress($in['email']);
    $mail->MessageID	= $msgid;
	
    if($mail->Send()) {
		$out=array('sent'=>true,'msgid'=>$mail->MessageID);
    }
	else {
		$out=array('sent'=>false,'msgid'=>'');
	}
	
	return $out;
}

function procMailSend($in=null) {
	$out=array();
	
	if (!is_null($in)) {
		foreach ($in as $n=>$v) {
			$ms=MailSend($v['template']);
			$in[$n]['processed']=$ms;
		}
	}

	return $in;
}

function updateMailQueue($in) {
	$out=array();
	if(!is_null($in) and count($in) > 0) {	
		$hostname   = "192.168.100.67";
		$username   = "jmsauth";
		$password   = "into99black";
		$dbname     = "jms_email_queue";
		mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
		mssql_select_db($dbname) or die("Table unavailable");
		
		$pid_ar=array();
		foreach ($in as $n=>$v) {
			if (isset($v['processed']['sent']) and $v['processed']['sent']) {
				$btime= time();
				$msgid= $v['processed']['msgid'];				
				$qry1 = "UPDATE jms_email_queue..EmailQueue SET pdate=".$btime.",msgid='".$msgid."' WHERE qid=".(int) $n.";";
				$res1 = mssql_query($qry1);
				$pid_ar[]=$in[$n]['pid'];
			}
		}
		
		$pid_ar_cnt=array_count_values($pid_ar);		
		foreach ($pid_ar_cnt as $np=>$vp) {
			$ptime= time();
			$qry2 = "UPDATE jms_email_queue..EmailProc SET checkout=".(int) $ptime.",numproc=".(int) $vp." WHERE pid=".(int) $np.";";
			$res2 = mssql_query($qry2);
		}
		
		mssql_close();
	}
	
	return $out;
}

function updateEmailTrack($in=null) {
	if(!is_null($in) and count($in) > 0) {	
		$hostname   = "192.168.100.45";
		$username   = "jestadmin";
		$password   = "into99black";
		$dbname     = "jest";
		
		mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
		mssql_select_db($dbname) or die("Table unavailable");
		
		foreach ($in as $n=>$v) {
			if (isset($v['processed']['sent']) and $v['processed']['sent']) {
				$qry = "insert into jest..EmailTracking (oid,lid,tid,cid,uid,sdate,emailaddr,emailaddrfrom,msgid) values ";
				$qry.= "(".(int) $v['template']['oid'].",0,".(int) $v['template']['etid'].",".(int) $v['template']['cid'].",1797,getdate(),'".$v['template']['email']."','bhcustcare@bluehaven.com','".$v['processed']['msgid']."');";
				$res = mssql_query($qry);
			}
		}
		
		mssql_close();
	}
}

function procEmailQueue() {
	$in	=(isset($_REQUEST['proc']) and $_REQUEST['proc'] == 1)?true:false;
	if ($in) {
		$gep=getEmailStart($in);
		
		if (isset($gep['error']) and !$gep['error']) {
			$sel=setEmailList($gep);
			$gec=getEmailContent($sel);
			$pms=procMailSend($gec);
			
			updateEmailTrack($pms);
			$out=updateMailQueue($pms);
		}
		else {
			$out=$gep;
		}
	}
	
	return $out;
}

$out=procEmailQueue();
header('Content-type: application/json; charset=utf-8');
echo json_encode($out);