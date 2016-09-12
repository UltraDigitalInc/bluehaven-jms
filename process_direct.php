<?php
// Auto Lead Processing
// This script will:
// 1. Log into a designated email account
// 2. Parse out the Emails for a specific Subject Line
// 3. Attempt to auto deliver to appropriate office


error_reporting(E_ALL);

function splitonspace($data)
{
	$u_data=preg_split("/ +/",$data);
	$lname=array_pop($u_data);

	$fn="";
	foreach ($u_data as $n => $v)
	{
		$fn=$v." ";
	}

	$dout=array(0=>$fn,1=>$lname);
	return $dout;
}

function replacequote($data)
{
	$out=preg_replace("/'/","''",$data);
	return $out;
}

function autosort_DIRECTLEADS()
{
	$recdate	=time();
	$cdate		=time();
	
	$qry		= "SELECT * FROM lead_inc WHERE sorted!='1' and source in (select statusid from leadstatuscodes where oid!=0);";
	$res		= mssql_query($qry);
	$nrow		= mssql_num_rows($res);

	if ($nrow > 0)
	{
		while($row=mssql_fetch_array($res))
		{
			$qry0	= "SELECT oid FROM leadstatuscodes WHERE statusid=".$row['source'].";";
			$res0	= mssql_query($qry0);
			$row0	= mssql_fetch_array($res0);
			
			$qry1	= "SELECT officeid,leadforward,am,active FROM offices WHERE officeid=".$row0['oid'].";";
			$res1	= mssql_query($qry1);
			$row1	= mssql_fetch_array($res1);			

			if ($row1['leadforward']==0)
			{
				$offid	=$row1['officeid'];
				$am		=$row1['am'];
			}
			else
			{
				$qry1a	= "SELECT officeid,leadforward,am,active FROM offices WHERE officeid=".$row1['leadforward'].";";
				$res1a	= mssql_query($qry1a);
				$row1a	= mssql_fetch_array($res1a);
				
				$offid	=$row1a['officeid'];
				$am		=$row1a['am'];
			}
			
			if (isset($offid) && $offid!=0)
			{
				$ndata=splitonspace($row['lname']);

				$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$offid."';";
				$resCa = mssql_query($qryCa);
				$rowCa = mssql_fetch_row($resCa);
				$nrowCa= mssql_num_rows($resCa);

				if ($nrowCa==0)
				{
					$ncid=1;
				}
				else
				{
					$ncid=$rowCa[0]+1;
				}

				$qryC	= "INSERT INTO cinfo ";
				$qryC .= "(added,updated,officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
				$qryC .= "VALUES (";
				$qryC .= "'".$row['submitted']."',getdate(),'".$offid."','".$am."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
				$qryC .= "'".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."','".$row['bphone']."',";

				if ($row['bphone']=="wk")
				{
					$qryC .= "'','".$row['phone']."',";
				}
				else
				{
					$qryC .= "'".$row['phone']."','',";
				}

				$qryC .= "'".replacequote($row['email'])."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
				$qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."');";
				$resC	= mssql_query($qryC);

				$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$offid."',secid=1797 WHERE lid='".$row['lid']."';";
				$resD	= mssql_query($qryD);

				$inscnt++;
			}
		}
	}
}

function mail_out($to,$sub,$mess)
{
	ini_set('SMTP','ZE_EMX01');
	ini_set('sendmail_from','jmsadmin@bluehaven.com');
	ini_set('sendmail_path','d:\tools\sendmail\sendmail.exe -t');

	$to		=	$to;
	$head	=	"From: JMS Mail System <jmsadmin@bluehaven.com>\r\n" .
	"Reply-To: jmsadmin@bluehaven.com\r\n" .
	"X-Mailer: PHP/" . phpversion();

	mail($to,$sub,$mess,$head);
}


function send_proc_notify($c)
{
	$to	 	= "thelton@bluehaven.com";
	$sub	= "JMS Process ($c)";
	$mess	= "Do Not Reply\r\n";
	
	mail_out($to,$sub,$mess);
}

function getleadmail()
{
	//echo "START<br>";
	
	//send_proc_notify('START');
	$MAIL_HOST="mail.masterlink.com";
	$MAIL_HOST_CONNECT="{".$MAIL_HOST."/pop3:110}INBOX";
	
	//$MAIL_HOST_CONNECT="{".$MAIL_HOST."/imap}INBOX";
	$MAIL_USER_NAME="bluehaven2@masterlink.com";
	$MAIL_USER_PASS="nuvo1991";

	$mbox	= imap_open($MAIL_HOST_CONNECT,$MAIL_USER_NAME,$MAIL_USER_PASS) or die("Error: Could not Connect");
	$total	= imap_num_msg($mbox);
	$s_sub1	= "Web Site Info Request";
	$s_sub2	= "Web Site Credit Application";
	
	//echo "POST MB CALL<br>";

	$errors=imap_errors();
	if (is_array($errors))
	{
		//echo "Status: " . $errors[0];
		echo "{success: true, results: {'emailProcessed':'0','reason':'".$errors[0]."'}}";
		exit;
	}
	
	//echo "EMAILS (Inbox) : ".$total."<br />";
	$y=0;
	if ($total > 0)
	{
		$w=0;
		for($x=$total; $x > 0; $x--)
		{
			$z=0;
			$header = imap_header($mbox, $x);
			$body 	= imap_fetchbody($mbox, $x,1);

			if ($header->subject==$s_sub1)
			{
				// Match Incoming Email Info
				if (preg_match("/was submitted +[0-9]{1,}\/[0-9]{1,}\/[0-9]{1,} +[0-9]{1,}:[0-9]{1,}:[0-9]{1,} +[A-Z]{1,2}/",$body,$matches))
				{
					$u_sub=preg_split("/ +/",$matches[0]);
					$sub=$u_sub[2]." ".$u_sub[3]." ".$u_sub[4];
				}
				else
				{
					$sub="";
				}

				if (preg_match("/Name: +[\w+\s\'\-\&\.]+\n/",$body,$matches))
				{
					$u_name=preg_split("/ +/",$matches[0]);
					$u_name=array_slice($u_name,1);

					$name="";
					foreach($u_name as $n => $v)
					{
						$name=$name." ".$v;
					}
					$name=preg_replace('/^ /','',$name);
				}
				else
				{
					$name="";
					$z++;
				}

				if (preg_match("/Address: +[\w+\s\'\.\#\@]+\n/",$body,$matches))
				{
					$u_addr=preg_split("/ +/",$matches[0]);
					$u_addr=array_slice($u_addr,1);

					$addr="";
					foreach($u_addr as $n => $v)
					{
						$addr=$addr." ".$v;
					}
					$addr=preg_replace('/^ /','',$addr);
				}
				else
				{
					$addr="";
				}

				if (preg_match("/City: +[\w+\s\'\.\#\@]+ State:/",$body,$matches))
				{
					$u_city=preg_split("/ +/",$matches[0]);
					$u_city=array_slice($u_city,1,-1);

					$city="";
					foreach($u_city as $n => $v)
					{
						$city=$city." ".$v;
					}
					$city=preg_replace('/^ /','',$city);
				}
				else
				{
					$city="";
				}

				if (preg_match("/State: +[a-zA-Z0-9]{1,2}/",$body,$matches))
				{
					$u_state=preg_split("/ +/",$matches[0]);
					$u_state=array_slice($u_state,1);
					$state=$u_state[0];
				}
				else
				{
					$state="";
				}

				if (preg_match("/Zip: +[0-9]{1,}/",$body,$matches))
				{
					$u_zip=preg_split("/ +/",$matches[0]);
					$u_zip=array_slice($u_zip,1);
					$zip=$u_zip[0];
				}
				else
				{
					$zip="";
					$z++;
				}

				if (preg_match("/E-mail: ([a-z][a-z0-9_.-\/]*@[^\s\"\)\?<>]+\.[a-z]{2,6})/i",$body,$matches))
				{
					$u_email=preg_split("/ +/",$matches[0]);
					$u_email=array_slice($u_email,1);
					$email=$u_email[0];
				}
				else
				{
					$email="";
				}


				if (preg_match("/Phone Number: +\(?[0-9]{1,3}\)?(-|.|\/|\w)[0-9]{1,3}(-|.|\/|\w)[0-9]{1,4} +\([a-zA-Z0-9]{1,}\)/",$body,$matches))
				{
					$u_phone=preg_split("/ +/",$matches[0]);
					if (count($u_phone)==4)
					{
						$phone=$u_phone[2];
						$conph=$u_phone[3];
					}
					elseif (count($u_phone)==5)
					{
						$phone=$u_phone[2]." ".$u_phone[3];
						$conph=$u_phone[4];
					}
					elseif (count($u_phone)==6)
					{
						$phone=$u_phone[2]." ".$u_phone[3]." ".$u_phone[4];
						$conph=$u_phone[5];
					}
					else
					{
						$phone="";
						$conph="";
						$z++;
					}

					if ($conph=="(home)")
					{
						$conph="hm";
					}
					else
					{
						$conph="wk";
					}
				}
				else
				{
					$phone="";
					$conph="";
					$z++;
				}

				$pat='/\(?\)?\-?\.?\s?/';
				$rep='';
				$phone=preg_replace($pat,$rep,$phone);

				if (preg_match("/Contact Time: +[0-9]{1,}(\-?[0-9]{1,})? +[A-Z]{1,2}/",$body,$matches))
				{
					$u_time=preg_split("/ +/",$matches[0]);
					$time=$u_time[2]." ".$u_time[3];
				}
				else
				{
					$time="";
				}

				if (preg_match("/Opt1: +[0-1]/",$body,$matches))
				{
					$u_opt1=preg_split("/ +/",$matches[0]);
					
					if ($u_opt1[1]==1)
					{
						$opt1=$u_opt1[1];
					}
					else
					{
						$opt1=0;
					}
				}
				else
				{
					$opt1=0;
				}
				
				if (preg_match("/Opt2: +[0-1]/",$body,$matches))
				{
					$u_opt2=preg_split("/ +/",$matches[0]);
					
					if ($u_opt2[1]==1)
					{
						$opt2=$u_opt2[1];
					}
					else
					{
						$opt2=0;
					}
				}
				else
				{
					$opt2=0;
				}
				
				if (preg_match("/Opt3: +[0-1]/",$body,$matches))
				{
					$u_opt3=preg_split("/ +/",$matches[0]);
					
					if ($u_opt3[1]==1)
					{
						$opt3=$u_opt3[1];
					}
					else
					{
						$opt3=0;
					}
				}
				else
				{
					$opt3=0;
				}
				
				if (preg_match("/Opt4: +[0-1]/",$body,$matches))
				{
					$u_opt4=preg_split("/ +/",$matches[0]);
					
					if ($u_opt4[1]==1)
					{
						$opt4=$u_opt4[1];
					}
					else
					{
						$opt4=0;
					}
				}
				else
				{
					$opt4=0;
				}
					
				if (preg_match("/Source: +[0-9]{1,3}/",$body,$matches))
				{
					$u_src1=preg_split("/ +/",$matches[0]);
					
					if (isset($u_src1[1]))
					{
						$src1=$u_src1[1];
					}
					else
					{
						$src1=0;
					}
				}
				else
				{
					$src1=0;
				}

				if (preg_match("/URL_ref: +http:\/\/.+\s*/",$body,$matches))
				{
					$u_url1=preg_split("/ +/",$matches[0]);
					
					if (is_array($u_url1))
					{
						$url1=trim($u_url1[1]);
					}
					else
					{
						$url1='Improper';
					}
				}
				else
				{
					$url1='None';
				}

				// Comments Code
				if (preg_match("/Requests\r[\-\s\S]+/",$body,$matches))
				{
					$comments=$matches[0];
				}
				else
				{
					$comments='';
				}

				if ($z==0)
				{
					$qry0	 = "INSERT INTO lead_inc ";
					$qry0 .= "(submitted,lname,addr,city,state,zip,phone,bphone,email,contime,comments,opt1,opt2,opt3,opt4,source,url_ref) ";
					$qry0 .= "VALUES (";
					$qry0 .= "'".$sub."','".replacequote($name)."','".replacequote($addr)."',";
					$qry0 .= "'".replacequote($city)."','".$state."','".$zip."','".$phone."',";
					$qry0 .= "'".$conph."','".$email."','".$time."','".replacequote($comments)."',";
					$qry0 .= "'".$opt1."','".$opt2."','".$opt3."','".$opt4."','".$src1."','".$url1."');";
					$res0	= mssql_query($qry0);
					$y++;
				}

				unset($u_sub);
				unset($u_name);
				unset($u_addr);
				unset($u_city);
				unset($u_state);
				unset($u_zip);
				unset($u_email);
				unset($u_phone);
				unset($u_time);

				imap_delete($mbox,$x);
			}
			else
			{
				imap_delete($mbox,$x);
				$w++;
			}
		}
		
		//echo "EMAILS (non-Lead): ".$w."<br />";
		imap_expunge($mbox);
		imap_close($mbox);
	}
	
	//echo "RECEIVED<br>";
	
	autosort();
	
	//send_proc_notify('LEAD_DL_END');
	
	//echo "SORTED<br>";
	
	//send_proc_notify($y);
	
	//echo "NOTIFIED<br>";
	
	echo "{success: true, results: {'emailProcessed':'".$y."','reason':'ProcessCompleted'}}";
	//echo "EMAILS (Processed): ".$y."<br />";
}

include(".\connect_db.php");
getleadmail();

?>