<?php
// Auto Lead Processing
// This script will:
// 1. Log into a designated email account
// 2. Parse out the Emails for a specific Subject Line
// 3. Attempt to auto deliver to appropriate office

//echo "START1<br>";

error_reporting(E_ALL);

//$mssql_ser	= "192.168.1.59";
//$mssql_db	= "jest";
//$mssql_user	= "jestadmin";
//$mssql_pass	= "into99black";
//
//mssql_connect($mssql_ser,$mssql_user,$mssql_pass) or die("Could not connect to MSSQL database");
//mssql_select_db($mssql_db) or die("Table unavailable");

//echo "START2<br>";

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

//echo "START3<br>";

function replacequote($data)
{
	$out=preg_replace("/'/","''",$data);
	return $out;
}

function autosortXX()
{
	$recdate	=time();
	$cdate		=time();
	$qry		= "SELECT * FROM lead_inc WHERE sorted!='1';";
	$res		= mssql_query($qry);
	$nrow		= mssql_num_rows($res);

	//echo "N1: ".$nrow."<br>";

	$qry0	= "SELECT * FROM offices WHERE active=1 AND am!='0';";
	$res0	= mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	$ap=0;
	if ($nrow0 > 0)
	{
		$sarray=array(0=>0);
		while($row0=mssql_fetch_array($res0))
		{
			$sarray[$row0['officeid']]=0;
		}
	}
	else
	{
		echo "<font color=\"red\"><b>ERROR!</b> no active Offices!</font>\n";
	}

	if ($nrow > 0)
	{
		while($row=mssql_fetch_array($res))
		{
			$inscnt	=0;
			$trzip	=trim($row['zip']);
			if (strlen($trzip) == 5)
			{
				//$split=array(0=>substr($row['phone'],0,3),1=>substr($row['phone'],3,3));

				$qryA	= "SELECT * FROM zip_to_zip WHERE czip='".$trzip."';";
				$resA	= mssql_query($qryA);
				$nrowA	= mssql_num_rows($resA);
				//echo "N2: ".$nrowA."<br>";
				//echo $row['phone']."(".$split[0].") ".$nrowA."<br>";

				if ($nrowA > 0)
				{
					//echo $row['phone']."(".$split[0].") ".$nrowA."<br>";
					while($rowA=mssql_fetch_array($resA))
					{
						if ($inscnt==0)
						{
							//if ($rowA['pre']==$split[1])
							//{
								//echo $row['phone']."(".$split[0].") SUB<br>";
								$qryB	= "SELECT officeid,am,name,active,leadforward FROM offices WHERE zip='".$rowA['ozip']."';";
								$resB	= mssql_query($qryB);
								$rowB	= mssql_fetch_array($resB);

								if ($rowB['leadforward']==0)
								{
									if ($rowB['am']!=0 && $rowB['active']==1)
									{
										//echo $row['phone']."(".$split[0].") SUB<br>";
										$ndata=splitonspace($row['lname']);

										//echo $ndata[0]."<br>";
										//echo $ndata[1]."<br>";

										$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowB['officeid']."';";
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
										$qryC .= "'".$row['submitted']."',getdate(),'".$rowB['officeid']."','".$rowB['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
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
										//echo "P1: ".$rowA['pre']."<br>";
										//echo "I1: ".$qryC."<br>";
										//echo $ndata[1]."<br>";

										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowB['officeid']."',secid='".$_SESSION['securityid']."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);
										//echo "U2: ".$qryD."<br>";
										//echo "-----------";
										$oid=$rowB['officeid'];
										if (is_array($sarray))
										{
											$sarray[$oid]=$sarray[$oid]+1;
										}
										else
										{
											$sarray=array($oid=>1);
										}
										$inscnt++;
									}
								}
								else
								{
									$qryBa	= "SELECT officeid,am,name,active FROM offices WHERE officeid='".$rowB['leadforward']."';";
									$resBa	= mssql_query($qryBa);
									$rowBa	= mssql_fetch_array($resBa);

									if ($rowBa['am']!=0 && $rowBa['active']==1)
									{
										$ndata=splitonspace($row['lname']);

										//echo $ndata[0]."<br>";
										//echo $ndata[1]."<br>";

										$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowBa['officeid']."';";
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
										$qryC .= "'".$row['submitted']."',getdate(),'".$rowBa['officeid']."','".$rowBa['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
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
										//echo "I3: ".$qryC."<br>";
										//echo $ndata[1]."<br>";

										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowBa['officeid']."',secid='".$_SESSION['securityid']."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);
										//echo "U3: ".$qryD."<br>";

										$oid=$rowBa['officeid'];

										if (is_array($sarray))
										{
											$sarray[$oid]=$sarray[$oid]+1;
										}
										else
										{
											$sarray=array($oid=>1);
										}
										$inscnt++;
									}
								}
							//}
						}
					}
				}
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

function procbhleademail($header,$body,$print)
{
	$z=0;
	$z_err=array();
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
		
		$z_err[]='name';
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
		$z_err[]='zip';
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
			$z_err[]='phone1';
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
		$z_err[]='phone2';
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
		include(".\connect_db_test.php");
		
		$qry0	 = "INSERT INTO lead_inc ";
		$qry0 .= "(submitted,lname,addr,city,state,zip,phone,bphone,email,contime,comments,opt1,opt2,opt3,opt4,source) ";
		$qry0 .= "VALUES (";
		$qry0 .= "'".$sub."','".replacequote($name)."','".replacequote($addr)."',";
		$qry0 .= "'".replacequote($city)."','".$state."','".$zip."','".$phone."',";
		$qry0 .= "'".$conph."','".$email."','".$time."','".replacequote($comments)."',";
		$qry0 .= "'".$opt1."','".$opt2."','".$opt3."','".$opt4."',".$src1.");";
		
		if (!$print)
		{
			$res0	= mssql_query($qry0);
		}
		else
		{
			echo $qry0.'<br>';
		}
	}

	//print_r ($z_err);
	return $z;
}

function getleadmail()
{
	$dbg				= array('process'=>true,'printstatus'=>false,'printcheck'=>false,'printstruct'=>false,'printhead'=>false,'printbody'=>false);
	$MAIL_HOST			= "pop.gmail.com";
	$MAIL_HOST_CONNECT	= "{".$MAIL_HOST.":995/pop3/ssl}INBOX";
	$MAIL_USER_NAME		= "datasysproc@bluehaven.com";
	$MAIL_USER_PASS		= "nuvo1992";

	$mbox	= imap_open($MAIL_HOST_CONNECT,$MAIL_USER_NAME,$MAIL_USER_PASS) or die("ERROR: ".imap_last_error());
	$mcheck = imap_check($mbox);
	$s_sub1	= "Web Site Info Request";
	$s_sub2	= "Tester";
	
	if ($dbg['process'])
	{
		if ($dbg['printcheck'])
		{
			echo "<h1>Checking Mailbox</h1>\n";
			
			echo "<pre>";
			var_dump($mcheck);
			echo "</pre>";
		}
		
		if ($dbg['printstatus'])
		{
			$mstatus = imap_status($mbox, $MAIL_HOST_CONNECT, SA_ALL);
			echo "<h1>Mailbox Status</h1>\n";
			
			echo "<pre>";
			var_dump($mstatus);
			echo "</pre>";
		}
		
		if ($mcheck->Nmsgs > 0)
		{
			$headers = imap_headers($mbox);
			
			if ($headers == false)
			{
				echo "No Messages <br />\n";
			}
			else
			{
				$x=1;
				$y=0;
				foreach ($headers as $val)
				{
					$header=array();
					$header=imap_headerinfo($mbox,$x) or die('Header '.$x.' not retrieved');
					
					if ($dbg['printhead'])
					{
						echo '<b>Header</b><br>';
						
						echo "<pre>";
						var_dump($header);
						echo "</pre>";
					}
					
					if ($dbg['printstruct'])
					{
						$structure=imap_fetchstructure($mbox,$x) or die('Structure '.$x.' not retrieved');
						echo '<b>Structure</b><br>';
						
						echo "<pre>";
						print_r($structure);
						echo "</pre>";
					}
					
					if ($header->subject == $s_sub1)
					{
						$body=imap_fetchbody($mbox,$x,'1',2) or die('Body '.$x.' not retrieved');
						//$body=imap_body($mbox,$x,2) or die('Body '.$x.' not retrieved');
						procbhleademail($header,$body,$dbg['printbody']);
						$y++;
					}
					
					$x++;
				}
			}
			
			imap_close($mbox,1);
			
			if ($y > 0)
			{
				//autosort();
				echo "{success:true,results: {'emailProcessed':'".$y."','reason':'Critical'}}";
			}
			else
			{
				echo "{success:false,results: {'emailProcessed':'".$y."','reason':'NonCritical'}}";
			}
		}
		else
		{
			echo "{success:true,results: {'emailProcessed':'0','reason':'mailboxEmpty'}}";
		}
	}
	else
	{
		echo "{success:false,results: {'emailProcessed':'0','reason':'processingDisabled'}}";
	}
}

getleadmail();

?>