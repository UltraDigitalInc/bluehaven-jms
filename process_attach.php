<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
//echo "start: script<br>";

function decode_quoprint($str) {
	$str = preg_replace("/\=([A-F][A-F0-9])/","",$str);
    return $str;
}

function getemail() {  
	//echo "start: ".__FUNCTION__."<br>";
	$process	=true;
	$testonly	=false;
	$cid_ar		=array();
	$fcid_ar	=array();
	$mencode	=0;
	$ex_oid		=(isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)?$_REQUEST['oid']:null;
	
    $MAIL_HOST			= "imap.gmail.com";
    $MAIL_HOST_CONNECT	= "{".$MAIL_HOST.":993/imap/ssl}INBOX";
	//$MAIL_USER_NAME		= "thelton@bluehaven.com";
    //$MAIL_USER_PASS		= "Th654321";
	$MAIL_USER_NAME		= "bhcustcare@bluehaven.com";
    $MAIL_USER_PASS		= "nuvo2029";
    
    if ($process) {
        $mbox	= imap_open($MAIL_HOST_CONNECT,$MAIL_USER_NAME,$MAIL_USER_PASS) or die("ERROR: ".imap_last_error());
        $mcheck = imap_check($mbox);
		
		$errors=imap_errors();
		if (is_array($errors)) {
			echo "Status: " . $errors[0];
			$e=$errors[0];
			$y=0;
			$total=0;
			exit;
		}
		
        if ($testonly) {
            echo "<h1>Mailbox Status</h1>\n";
            
            echo "<pre>";
            var_dump($mcheck);
            echo "</pre>";
			imap_close($mbox);
        }
		else {			
			if ($mcheck->Nmsgs > 0) {
				//echo 'Emails: '.$mcheck->Nmsgs.'<br>';
				$x=1;
				$kf	=EmailKillList($ex_oid);

				$result = imap_fetch_overview($mbox,"1:{$mcheck->Nmsgs}",0);
				foreach ($result as $overview) {
					$acnt	=0;
					$head	=imap_headerinfo($mbox, $overview->msgno) or die("HEAD {$overview->msgno} not retrieved");
					$strct	=imap_fetchstructure($mbox, $overview->msgno) or die("STRUCTURE {$overview->msgno} not retrieved");
					
					$emailn	=$overview->msgno;
					$date	=(valid_date($date=$head->date))?$head->date:date('m/d/y G:i:s',time());
					$subj	=$head->Subject;
					$from	=$head->from[0];
					$msgd	=$head->message_id;
					$ecid	=cid_lookup(trim($from->mailbox.'@'.$from->host),$msgd);
					$mfail	=(in_array($from->host,$kf))?true:false;

					//var_dump($ecid);
					//echo '<br><pre>';
					//print_r($strct);
					//echo '</pre>';
					
					if (!$mfail) {
						//echo '<br><pre>';
						//echo 'MSGD: '.$msgd.'<br>';
						//echo 'FROM: '.$ecid['emailaddr'].'<br>'; //echo $ecid[3];
						//echo 'SUBJ: '.$subj.'<br>';
						//echo 'MCNT: '.$ecid['mcnt'].'<br>';
						
						if ($strct->type==0 or $strct->type==1) {
							$body=imap_fetchbody($mbox,$overview->msgno,1) or die("BODY {$overview->msgno} not retrieved");
							switch($strct->encoding) {
								//case 0: $body=$body;// 7BIT
								//case 1: $body=$body;// 8BIT								
								//case 2: $body=$body;// BINARY
								case 3: $body=base64_decode($body);// BASE64
								case 4: $body=quoted_printable_decode($body);// QUOTED_PRINTABLE
								//case 5: $body=$body;// OTHER
							}
							
							//$body=substr($body, 0, strpos($body, 0, "-----Original Message-----"));
						}
						
						$attachments = array();
						if(isset($strct->parts) && count($strct->parts)) {
							for($i = 0; $i < count($strct->parts); $i++) {						
								$attachments[$i] = array(
									'is_attachment' => false,
									'filename' => '',
									'name' => '',
									'attachment' => ''
								);
								
								if($strct->parts[$i]->ifdparameters) {
									foreach($strct->parts[$i]->dparameters as $object) {
										if(strtolower($object->attribute) == 'filename') {
											$attachments[$i]['is_attachment'] = true;
											$attachments[$i]['filename'] = $object->value;
										}
									}
								}
								
								if($strct->parts[$i]->ifparameters) {
									foreach($strct->parts[$i]->parameters as $object) {
										if(strtolower($object->attribute) == 'name') {
											$attachments[$i]['is_attachment'] = true;
											$attachments[$i]['name'] = $object->value;
										}
									}
								}
								
								if($attachments[$i]['is_attachment']) {
									$attachments[$i]['attachment'] = imap_fetchbody($mbox,$overview->msgno, $i+1);
									if($strct->parts[$i]->encoding == 3) { // 3 = BASE64
										$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
									}
									elseif($strct->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
										$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
									}
								}
							}
						}
						
						// Message Injection Logic
						if (isset($ecid['cid']) and $ecid['cid'] != 0) {
							//echo 'CID: '.$ecid['cid'].'<br>';
							//echo 'MCN: '.$ecid['mcnt'].'<br>';
							
							if ($ecid['mcnt'] == 0) {
								$mencode=$strct->encoding;
								$qry  = "insert into jest..chistory (custid,officeid,secid,mdate,mtext,tranid,act,mencode) values ";
								$qry .= "(".$ecid['cid'].",".$ecid['oid'].",1797,'".date('m/d/y H:i',strtotime($date))."','".strip_tags(removequote(trim($body)))."','".trim($msgd)."','cresp',".$mencode."); select @@IDENTITY as chid;";
								$res  = mssql_query($qry);
								$row  = mssql_fetch_array($res);
							}
							
							$FileDir="F:\\FileStore\\CustomerEmailFiles\\".$ecid['cid'];
							$mproc=true;
						}
						else {
							$fileoid=(isset($ex_oid) and !is_null($ex_oid) and $ex_oid!=0)?$ex_oid:0;
							$cloc=trim($from->mailbox.'.'.$from->host);
							
							$FileDir="F:\\FileStore\\".$fileoid."\\CustomerNotFoundEmailFiles";
							
							if (!is_dir($FileDir)) {
								mkdir($FileDir,'0777',true);
							}
						
							$fp = fopen($FileDir ."\\". $cloc.".txt", "w+");
							//$fwrite=fwrite($fp, strip_tags(removequote(trim($body))));
							$fwrite=fwrite($fp, $body);
							fclose($fp);
							
							$mproc=($fwrite!=false)?true:false;
						}
						
						//File Attachment Processing
						foreach($attachments as $attachment) {
							if($attachment['is_attachment'] == 1) {
								if (!is_dir($FileDir)) {
									mkdir($FileDir,'0777',true);
								}
								
								$filename = $attachment['name'];
								if(empty($filename)) $filename = $attachment['filename'];
								if(empty($filename)) $filename = time() . ".dat";

								$ff=$FileDir ."\\". $filename;
								$fp = fopen($ff, "w+");
								fwrite($fp, $attachment['attachment']);
								fclose($fp);
								$acnt++;
								
								//echo $filename.' written<br>';
								if (strpos($ff, " ") !== false) {
									$newff = str_replace(' ','_',$ff);
									rename($ff,$newff);

									$ftodb=$newff;
								}
								else {
									$ftodb=$ff;
								}
								
								if (isset($row['chid']) and $row['chid']!=0) {
									$qry1  = "insert into jest..chistory_files (chid,filename) values (".$row['chid'].",'".$ftodb."')";
									$res1  = mssql_query($qry1);
								}
							}
						}

						//echo 'BODY: '.trim($body);
						//echo 'FILES: '.count($attachments);
						//echo 'Files Written: '.$acnt.'<br>';
						//echo '</pre>';
						
						if ($mproc) {
							imap_mail_move($mbox, $emailn, 'Processed');
							//echo $msgd. ' Processed. ('.$acnt.' Files)<br>';
						}
						//imap_delete($mbox,$overview->msgno);
					}
					else {
						imap_mail_move($mbox, $emailn, 'Processed');
						//echo $msgd. ' Not Processed. (Illegal Email)<br>';
					}
				}
			}
			else {
				echo 'No Msgs';
			}
			
			imap_close($mbox);
		}
	}
}

function cid_lookup($e,$msgd) {
	$o=array('cid'=>0,'mcnt'=>0);
	
	if (valid_email_addr($e)) {
		$qry = "SELECT cid,cemail,officeid FROM jest..cinfo WHERE cemail like '" . trim($e) . "%' order by recdate desc;";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		
		//echo $qry.'<br>';
		
		$o['cid']=$row['cid'];
		$o['oid']=$row['officeid'];
		$o['emailaddr']=$row['cemail'];
		
		$qry0 = "select top 1 tranid from chistory where tranid='".trim($msgd)."' order by mdate desc;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		//$row0 = mssql_fetch_array($res0);
			
		$o['mcnt']=$nrow0;
	}
	
	return $o;
}

function EmailKillList($oid=null) {
	$out	=array();
	$ioid	=(is_null($oid) or $oid==0)?0:$oid; // 0 == Global KillFile
	
	$qry = "SELECT emailhost FROM jest..EmailKillList WHERE oid=".(int) $ioid." ORDER BY emailaddr ASC;";
	$res = mssql_query($qry);
	
	while ($row = mssql_fetch_array($res)) {
		$out[]=$row['emailhost'];
	}
	
	return $out;
}

//echo "start<br>";
include ('connect_db.php');
include ('common_func.php');
getemail();
//echo "end<br>";

?>