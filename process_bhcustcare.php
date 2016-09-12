<?php

function decode_quoprint($str) {
	$str = preg_replace("/\=([A-F][A-F0-9])/","",$str);
    return $str;
}

function getemail()
{
	error_reporting(E_ALL);
	ini_set('display_errors','On');
  
	//echo "start: getemail<br>";
	$process	=true;
	$testonly	=false;
	$cid_ar		=array();
	$fcid_ar	=array();
	$mencode	=0;
	
    $MAIL_HOST			= "imap.gmail.com";
    $MAIL_HOST_CONNECT	= "{".$MAIL_HOST.":993/imap/ssl}INBOX";
    //$MAIL_USER_NAME		= "testemail@bluehaven.com";
    //$MAIL_USER_PASS		= "testemail";
	$MAIL_USER_NAME		= "bhcustcare@bluehaven.com";
    $MAIL_USER_PASS		= "nuvo2029";

    
    if ($process)
    {
        $mbox	= imap_open($MAIL_HOST_CONNECT,$MAIL_USER_NAME,$MAIL_USER_PASS) or die("ERROR: ".imap_last_error());
        $mcheck = imap_check($mbox);
		//$mcheck = imap_status($mbox, "{".$MAIL_HOST.":995/pop3/ssl}INBOX", SA_MESSAGES);  
		
		$errors=imap_errors();
		if (is_array($errors))
		{
			echo "Status: " . $errors[0];
			$e=$errors[0];
			$y=0;
			$total=0;
			exit;
		}
		
        if ($testonly)
        {
            echo "<h1>Mailbox Status</h1>\n";
            
            echo "<pre>";
            var_dump($mcheck);
            echo "</pre>";
			imap_close($mbox);
        }
		else
		{
			if ($mcheck->Nmsgs > 0)
			{
				$x=1;
				$result = imap_fetch_overview($mbox,"1:{$mcheck->Nmsgs}",0);
				foreach ($result as $overview)
				{
					$strct	=imap_fetchstructure($mbox, $overview->msgno) or die("STRUCTURE {$overview->msgno} not retrieved");
					$head	=imap_headerinfo($mbox,$overview->msgno) or die("HEAD {$overview->msgno} not retrieved");
					
					$from=$head->from[0];
					$msgd=$head->message_id;
					
					if (valid_date($date=$head->date))
					{
						$date=$head->date;
					}
					else
					{
						$date=date('m/d/y G:i:s',time());
					}
				
					$body	=imap_fetchbody($mbox,$overview->msgno,'1',2) or die("BODY {$overview->msgno} not retrieved");
					preg_match('/^.+---/is',$body,$matches);
					
					if (
							$from->mailbox == 'mailer-daemon' &&
							$from->host == 'googlemail.com' &&
							strlen($head->Subject) > 0 &&
							(
								$head->Subject=='Delivery Status Notification (Failure)'
							)
					   )
					{
						//echo $head->Subject.'<br>';
						preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/is',$body,$ematch);
						
						if (isset($ematch) && count($ematch) >=1)
						{
							$ecid=cid_lookup(trim($ematch[0]));
						}
						else
						{
							$ecid=array(0=>0);
						}
					}
					else
					{
						if (isset($from->host))
						{
							$ecid=cid_lookup(trim($from->mailbox.'@'.$from->host));
						}
					}
					
					if (isset($ecid) && $ecid[0]!=0)
					{
						$qry0 = "select count(id) as mcnt from chistory where tranid='".trim($msgd)."';";
						$res0 = mssql_query($qry0);
						$row0 = mssql_fetch_array($res0);
						
						if ($row0['mcnt']==0)
						{
							$mencode=$strct->encoding;
							if ($strct->encoding == 3) //base64
							{
								$tfbody=substr(removequote(base64_decode($body)),0,512);
							}
							elseif ($strct->encoding == 4) //quoted printable
							{
								$tfbody=substr(removequote(imap_qprint($body)),0,512);
							}
							else
							{
								//$srch_ar=array('/=C2=A0/','/=A0/','/=0A/','/=20/','/= /','/ =/','/=/','/------Original Message------/','/----- Original message -----/');
								$srch_ar=array('/=C2=A0/','/=C2=B7/','/=C2/','/C2=/','/=A0/','/=0A/','/A0/','/0A/','/=20/','/= /','/ =/','/=/','/------Original Message------/','/----- Original message -----/');
								
								if (isset($matches) && count($matches) >=1)
								{
									$tfbody=preg_replace($srch_ar,'',$matches[0]);
								}
								else
								{
									$tfbody=substr(removequote($body),0,512);
								}	
							}
							
							$qry  = "insert into jest..chistory (custid,officeid,secid,mdate,mtext,tranid,act,mencode) values ";
							$qry .= "(".$ecid[0].",".$ecid[1].",1797,'".date('m/d/y H:i',strtotime($date))."','".trim(removequote($tfbody))."','".trim($msgd)."','cresp',".$mencode."); select @@IDENTITY as chid;";
							$res  = mssql_query($qry);
							$row  = mssql_fetch_array($res);
							
							if (isset($row['chid']) && $row['chid']!=0)
							{
								echo $msgd. ' Processed<br>';
								imap_delete($mbox,$overview->msgno);
								
								if (preg_match('/failed permanently/i',trim(removequote($tfbody))))
								{
									$fcid_ar[]=$ecid[0];
								}
								else
								{
									$cid_ar[]=$ecid[0];
								}
							}
							else
							{
								echo $msgd. ' Not Processed<br>';
							}
						}
						else
						{
							echo htmlspecialchars(trim($msgd)). ' Already Processed<br>';
							imap_delete($mbox,$overview->msgno);
						}
					}
					else
					{
						if (isset($from->host))
						{							
							echo 'No '. $from->mailbox.'@'.$from->host . ' in database<br>';
							$evilhosts=array('linkedin.com','googlemail.com');
							if (in_array($from->host,$evilhosts)) {
								imap_delete($mbox,$overview->msgno);
								echo 'Deleting...<br>';
							}
						}
						else
						{
							echo 'No '. $from->mailbox.' in database<br>';
						}
					}
				}
			}
			else
			{
				echo 'No Msgs';
			}
			
			imap_close($mbox);
		}
	}
	
	$email_ecnt=0;
	if (count($cid_ar) > 0)
	{
		foreach ($cid_ar as $nz=>$vz)
		{
			JMS_customer_email_notify($vz,true,false,true,true,false,'JMS Notification: New Email Response!');
			$email_ecnt++;
		}
	}
	
	$email_ecnt=0;
	if (count($fcid_ar) > 0)
	{
		foreach ($fcid_ar as $nzf=>$vzf)
		{
			JMS_customer_email_notify($vzf,true,false,true,true,false,'JMS Notification: Email Delivery Issue');
			$email_ecnt++;
		}
	}
	
	//echo "end:getemail<br>";
}

function cid_lookup($e)
{
	$o=array(0=>0);
	
	if (valid_email_addr($e))
	{
		$qry = "SELECT top 1 cid,emailaddr,oid FROM jest..EmailTracking WHERE emailaddr = '" . $e . "' order by sdate desc;";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		
		$o=array(0=>$row['cid'],1=>$row['oid'],3=>$row['emailaddr']);
	}
	
	return $o;
}

function cid_lookupNEW($e) {
	$o=array(0=>0);
	
	if (valid_email_addr($e)) {
		$qry = "SELECT top 1 cid,emailaddr,oid FROM jest..EmailTracking WHERE emailaddr = '" . trim($e) . "' order by sdate desc;";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		$nrow= mssql_num_rows($res);
		
		//if ($nrow > 0) {
			$email_in	= explode("@",$e);
			$email_db	= explode("@",$row['emailaddr']);		
			$hst_repl	= array('aol.com','aim.com');
			//$sub		=(in_array($email_in[1],$hst_repl) && in_array($email_db[1],$hst_repl))?true:false;
			var_dump($email_in);
			
			//echo 'REPL: '.var_dump($sub).'<br>';
			$o=array(0=>$row['cid'],1=>$row['oid'],3=>$row['emailaddr']);
		//}
	}
	
	return $o;
}

//echo "start<br>";
include ('connect_db.php');
include ('common_func.php');
include ('email_notify.php');
getemail();
//echo "end<br>";

?>