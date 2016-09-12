<?php
// Auto Lead Processing
// This script will:
// 1. Log into a designated email account
// 2. Parse out the Emails for a specific Subject Line
// 3. Attempt to auto deliver to appropriate office


error_reporting(E_ALL);

function splitonspaceOLD($data)
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

function replacequoteOLD($data)
{
	$out=preg_replace("/'/","''",$data);
	return $out;
}

function autosort_ZIP()
{
	//echo 'SORT_ZIP<br>';
	//ini_set('display_errors','On');
	//error_reporting(E_ALL);
	
	$recdate	=time();
	$cdate		=time();
	$secid      =1797;
	$out_ar		=array();
	$cid_ar		=array();
	
	$qry		= "SELECT * FROM lead_inc WHERE sorted!=1 and source not in (select statusid from leadstatuscodes where active=2 and oid!=0);";
	$res		= mssql_query($qry);
	$nrow		= mssql_num_rows($res);

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
		//$cid_ar=array();
		while($row=mssql_fetch_array($res))
		{
			$inscnt	=0;
			$trzip	=trim($row['zip']);
			if (strlen($trzip) == 5)
			{
				$qryA	= "SELECT * FROM zip_to_zip WHERE czip='".$trzip."';";
				$resA	= mssql_query($qryA);
				$nrowA	= mssql_num_rows($resA);

				if ($nrowA == 1)
				{
					while($rowA=mssql_fetch_array($resA))
					{
						if ($inscnt==0)
						{
							$qryB	= "SELECT officeid,am,name,active,leadforward FROM offices WHERE zip='".$rowA['ozip']."';";
							$resB	= mssql_query($qryB);
							$rowB	= mssql_fetch_array($resB);

							if ($rowB['leadforward']==0)
							{
								//echo 'ORG';
								if ($rowB['am']!=0 && $rowB['active']==1)
								{
									//echo $row['phone']."(".$split[0].") SUB<br>";
									$ndata=splitonspace($row['lname']);

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
									$qryC .= "(added,updated,officeid,securityid,cfname,clname,";
									$qryC .= "caddr1,ccity,cstate,czip1,";
									$qryC .= "saddr1,scity,sstate,szip1,ssame,";
									$qryC .= "cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
									$qryC .= "VALUES (";
									$qryC .= "'".$row['submitted']."',getdate(),'".$rowB['officeid']."','".$rowB['am']."','".$ndata[0]."','".replacequote($ndata[1])."',";
									$qryC .= "'".replacequote(trim($row['addr']))."','".replacequote(trim($row['city']))."','".replacequote(trim($row['state']))."','".trim($row['zip'])."',";
									$qryC .= "'".replacequote(trim($row['addr']))."','".replacequote(trim($row['city']))."','".replacequote(trim($row['state']))."','".trim($row['zip'])."',1,";
									$qryC .= "'".trim($row['bphone'])."',";

									if ($row['bphone']=="wk")
									{
										$qryC .= "'','".trim($row['phone'])."',";
									}
									else
									{
										$qryC .= "'".trim($row['phone'])."','',";
									}

									$qryC .= "'".replacequote(trim($row['email']))."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
									$qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."'); select @@IDENTITY as cidid;";
									$resC	= mssql_query($qryC);
									$rowC  = mssql_fetch_array($resC);
									
									$cid_ar[]=$rowC['cidid'];

									if (isset($rowC['cidid']) and $rowC['cidid']!=0)
									{
										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowB['officeid']."',secid='".$secid."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);
										
										$qryE	= "INSERT INTO jest..EmailIntrosSent (cid) VALUES (".$rowC['cidid'].");";
										$resE	= mssql_query($qryE);
										
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
							}
							else
							{
								//echo 'FWD';
								$qryBa	= "SELECT officeid,am,name,active FROM offices WHERE officeid='".$rowB['leadforward']."';";
								$resBa	= mssql_query($qryBa);
								$rowBa	= mssql_fetch_array($resBa);

								if ($rowBa['am']!=0 && $rowBa['active']==1)
								{
									$ndata=splitonspace($row['lname']);

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
										$ncid=$rowCa[0] + 1;
									}

									$qryC	= "INSERT INTO cinfo ";
									$qryC .= "(added,updated,officeid,securityid,cfname,clname,";
									$qryC .= "caddr1,ccity,cstate,czip1,";
									$qryC .= "saddr1,scity,sstate,szip1,ssame,";
									$qryC .= "cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
									$qryC .= "VALUES (";
									$qryC .= "'".$row['submitted']."',getdate(),'".$rowBa['officeid']."','".$rowBa['am']."','".$ndata[0]."','".replacequote($ndata[1])."',";
									$qryC .= "'".replacequote(trim($row['addr']))."','".replacequote(trim($row['city']))."','".replacequote(trim($row['state']))."','".trim($row['zip'])."',";
									$qryC .= "'".replacequote(trim($row['addr']))."','".replacequote(trim($row['city']))."','".replacequote(trim($row['state']))."','".trim($row['zip'])."',1,";
									$qryC .= "'".trim($row['bphone'])."',";

									if ($row['bphone']=="wk")
									{
										$qryC .= "'','".trim($row['phone'])."',";
									}
									else
									{
										$qryC .= "'".trim($row['phone'])."','',";
									}

									$qryC .= "'".replacequote(trim($row['email']))."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
									$qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."'); select @@IDENTITY as cidid;";
									$resC  = mssql_query($qryC);
									$rowC  = mssql_fetch_array($resC);
							
									//echo $qryC.'<br>';
							
									$cid_ar[]=$rowC['cidid'];

									if (isset($rowC['cidid']) and $rowC['cidid']!=0)
									{
										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowBa['officeid']."',secid='".$secid."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);
										
										$qryE	= "INSERT INTO jest..EmailIntrosSent (cid) VALUES (".$rowC['cidid'].");";
										$resE	= mssql_query($qryE);
	
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
							}
						}
					}
				}
			}
		}
	}
	
	return $cid_ar;
	//if (isset($inscnt))
	//{
	//	echo $inscnt . ' Leads Processed'.chr(13);
	//}
}

function autosort_DIRECT()
{
	$recdate	=time();
	$cdate		=time();
	$secid      =1797;
	$cid_ar		=array();
	
	$qry		= "SELECT * FROM lead_inc WHERE sorted!=1 and source in (select statusid from leadstatuscodes where oid!=0);";
	$res		= mssql_query($qry);
	$nrow		= mssql_num_rows($res);

	$inscnt=0;
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

			if ($row1['active']==1)
			{
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
			}
			else
			{
				$qry1z	= "SELECT officeid,leadforward,am,active FROM offices WHERE officeid=89;";
				$res1z	= mssql_query($qry1z);
				$row1z	= mssql_fetch_array($res1z);
				
				$offid	=$row1z['officeid'];
				$am		=$row1z['am'];	
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
				$qryC .= "(added,updated,officeid,securityid,cfname,clname,";
				$qryC .= "caddr1,ccity,cstate,czip1,";
				$qryC .= "saddr1,scity,sstate,szip1,ssame,";
				$qryC .= "cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
				$qryC .= "VALUES (";
				$qryC .= "'".$row['submitted']."',getdate(),'".$offid."','".$am."','".$ndata[0]."','".replacequote($ndata[1])."',";
				$qryC .= "'".replacequote($row['addr'])."','".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."',";
				$qryC .= "'".replacequote($row['addr'])."','".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."',1,";
				$qryC .= "'".$row['bphone']."',";

				if ($row['bphone']=="wk")
				{
					$qryC .= "'','".$row['phone']."',";
				}
				else
				{
					$qryC .= "'".$row['phone']."','',";
				}

				$qryC .= "'".replacequote($row['email'])."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
				$qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."'); SELECT @@IDENTITY as cidid;";
				$resC	= mssql_query($qryC);
				$rowC  = mssql_fetch_array($resC);
									
				$cid_ar[]=$rowC['cidid'];

				$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$offid."',secid=".$secid." WHERE lid='".$row['lid']."';";
				$resD	= mssql_query($qryD);
				
				$qryE	= "INSERT INTO jest..EmailIntrosSent (cid) VALUES (".$rowC['cidid'].");";
				$resE	= mssql_query($qryE);
				
				$inscnt++;
			}
		}
	}
	
	//echo $inscnt . ' Leads Processed'.chr(13);
	return $cid_ar;
}

function mail_outOLD($to,$sub,$mess)
{
}

function send_proc_notifyOLD($c)
{
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
	$s_sub1a= "FW: Web Site Info Request";
	$s_sub2	= "Web Site Credit Application";
	
	//echo "POST MB CALL<br>";

	$errors=imap_errors();
	if (is_array($errors))
	{
		//echo "Status: " . $errors[0];
		//echo "{success: true, results: {'emailProcessed':'0','reason':'".$errors[0]."'}}";
		$e=$errors[0];
		$y=0;
		$total=0;
		//exit;
	}
	else
	{
		//echo "EMAILS (Inbox) : ".$total."<br />";
		$e='';
		$y=0;
		if ($total > 0)
		{
			$w=0;
			for($x=$total; $x > 0; $x--)
			{
				$z=0;
				$header = imap_header($mbox, $x);
				$body 	= imap_fetchbody($mbox, $x,1);
				
				$body	= str_replace("<br>","\n",$body);
	
				if ($header->subject==$s_sub1||$header->subject==$s_sub1a)
				{
					// Match Incoming Email Info
					//if (preg_match("/was submitted +[0-9]{1,}\/[0-9]{1,}\/[0-9]{1,} +[0-9]{1,}:[0-9]{1,}:[0-9]{1,} +[A-Z]{1,2}/",$body,$matches))
					if (preg_match("/was\ssubmitted\s.*\n/",$body,$matches))
					{
						$u_sub=preg_split("/ +/",$matches[0]);
						//$sub=$u_sub[2]." ".$u_sub[3]." ".$u_sub[4];
						$sub=$u_sub[3]." ".$u_sub[4]." ".$u_sub[5]." ".$u_sub[6]." ".$u_sub[7];
						$sub=date('m/d/Y G:i:s',strtotime($sub));
						//$sub=str_replace("\n","",$sub);
					}
					else
					{
						$sub="";
						$z++;
					}
					
					//echo 'Submitted: '.$sub."\n";
	
					//if (preg_match("/Name: +[\w+\s\'\-\&\.]+\n/",$body,$matches))
					if (preg_match("/Name:\s.*\n/",$body,$matches))
					{
						$u_name=preg_split("/ +/",$matches[0]);
						$u_name=array_slice($u_name,1);
	
						$name="";
						foreach($u_name as $n => $v)
						{
							$name=$name." ".$v;
						}
						$name=preg_replace('/^ /','',$name);
						$name=str_replace("\n","",$name);
					}
					else
					{
						$name="";
						$z++;
					}
					
					///echo 'Name:'.$name."\n";
	
					if (preg_match("/Address:\s+[\w+\s\'\.\#\@]+\n/",$body,$matches))
					{
						$u_addr=preg_split("/ +/",$matches[0]);
						$u_addr=array_slice($u_addr,1);
	
						$addr="";
						foreach($u_addr as $n => $v)
						{
							$addr=$addr." ".$v;
						}
						$addr=preg_replace('/^ /','',$addr);
						$addr=str_replace("\n","",$addr);
					}
					else
					{
						$addr="";
						$z++;
					}
					
					//echo 'Addr:'.$addr."\n";
	
					//if (preg_match("/City: +[\w+\s\'\.\#\@]+ State:/",$body,$matches))
					if (preg_match("/City:\s.*\n/",$body,$matches))
					{
						$u_city=preg_split("/ +/",$matches[0]);
						//$u_city=array_slice($u_city,1,-1);
						$u_city=array_slice($u_city,1);
	
						$city="";
						foreach($u_city as $n => $v)
						{
							$city=$city." ".$v;
						}
						$city=preg_replace('/^ /','',$city);
						$city=str_replace("\n","",$city);
						$z++;
					}
					else
					{
						$city="";
					}
					
					//echo 'City:'.$city."\n";
	
					if (preg_match("/State:\s+[a-zA-Z0-9]{1,2}/",$body,$matches))
					{
						$u_state=preg_split("/ +/",$matches[0]);
						$u_state=array_slice($u_state,1);
						$state=$u_state[0];
					}
					else
					{
						$state="";
						$z++;
					}
					//echo 'State:'.$state."\n";
	
					if (preg_match("/Zip:\s+[0-9]{1,}/",$body,$matches))
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
					//echo 'Zip:'.$zip."\n";
	
					if (preg_match("/E-mail:\s([a-z][a-z0-9_.-\/]*@[^\s\"\)\?<>]+\.[a-z]{2,6})/i",$body,$matches))
					{
						$u_email=preg_split("/ +/",$matches[0]);
						$u_email=array_slice($u_email,1);
						$email=$u_email[0];
					}
					else
					{
						$email="";
						$z++;
					}
					//echo 'Email:'.$email."\n";
	
					//if (preg_match("/Phone Number: +\(?[0-9]{1,3}\)?(-|.|\/|\w)[0-9]{1,3}(-|.|\/|\w)[0-9]{1,4} +\([a-zA-Z0-9]{1,}\)\n/",$body,$matches))
					if (preg_match("/Phone Number:\s\(?[0-9]{1,3}\)?(-|.|\/|\w)[0-9]{1,3}(-|.|\/|\w)[0-9]{1,4}\s[a-zA-Z0-9]{1,}\n/",$body,$matches))
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
	
						if (trim($conph)=="home")
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
					//echo 'Phone:'.$phone."\n";
					//echo 'Conph:'.$conph."\n";
	
					if (preg_match("/Contact Time:\s+[0-9]{1,}(\-?[0-9]{1,})?\s+[A-Z]{1,2}\n/",$body,$matches))
					{
						$u_time=preg_split("/ +/",$matches[0]);
						$time=$u_time[2]." ".$u_time[3];
					}
					else
					{
						$time="";
					}
	
					if (preg_match("/Opt1:\s+[0-1]/",$body,$matches))
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
					
					//echo 'Opt1:'.$opt1."\n";
					
					if (preg_match("/Opt2:\s+[0-1]/",$body,$matches))
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
					//echo 'Opt2:'.$opt2."\n";
					
					if (preg_match("/Opt3:\s+[0-1]/",$body,$matches))
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
					//echo 'Opt3:'.$opt3."\n";
					
					if (preg_match("/Opt4:\s+[0-1]/",$body,$matches))
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
					//echo 'Opt4:'.$opt4."\n";
						
					if (preg_match("/Source:\s+[0-9]{1,3}/",$body,$matches))
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
					//echo 'Source:'.$src1."\n";
	
					if (preg_match("/URL_ref:\s+http:\/\/.+\s*/",$body,$matches))
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
					//if (preg_match("/Requests\r[\-\s\S]+/",$body,$matches))
					if (preg_match("/-{1,}\nRequests.*(\n.*){1,}/i",$body,$matches))
					{
						$comments=$matches[0];
					}
					else
					{
						$comments='';
					}
					
					//echo 'Comments:'."\n".$comments;
					
					//echo $body."\n";
	
					$z=0; // Force program to accept all leads, Remove when bluehaven.com lead processing form/script is fixed. TLH
					if ($z==0)
					{
						$qry0  = "INSERT INTO lead_inc ";
						$qry0 .= "(submitted,lname,addr,city,state,zip,phone,bphone,email,contime,comments,opt1,opt2,opt3,opt4,source,url_ref) ";
						$qry0 .= "VALUES (";
						$qry0 .= "'".$sub."','".replacequote($name)."','".replacequote($addr)."',";
						$qry0 .= "'".replacequote($city)."','".$state."','".$zip."','".$phone."',";
						$qry0 .= "'".$conph."','".$email."','".$time."','".replacequote($comments)."',";
						$qry0 .= "'".$opt1."','".$opt2."','".$opt3."','".$opt4."','".$src1."','".$url1."');";
						$res0	= mssql_query($qry0);
						$y++;
						//echo $qry0."\n";
					}
					
					//echo $z."\n";
	
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
					//$w++;
				}
			}
			
			imap_expunge($mbox);
			imap_close($mbox);
		}
	}
	
	$cid_ZIP=autosort_ZIP();
	$cid_DIR=autosort_DIRECT();
	
	$email_ecnt=0;
	if (count($cid_ZIP) > 0)
	{
		foreach ($cid_ZIP as $nz=>$vz)
		{
			JMS_email_notify($vz,true,false,true,false,'JMS Notification: New Territory Lead from BHNM!');
			$email_ecnt++;
		}
	}
	
	if (count($cid_DIR) > 0)
	{
		foreach ($cid_DIR as $nd=>$vd)
		{
			JMS_email_notify($vd,true,false,true,false,'JMS Notification: New Direct Lead from BHNM!');
			$email_ecnt++;
		}
	}
	
	//Process Intro Emails
	process_email_intro(0);
	
	echo "{success: true, results: {'Mailbox': '".$total."','Processed':'".$y."','Completed': {'ZIP':'".count($cid_ZIP)."','DIR':'".count($cid_DIR)."','EML':'".$email_ecnt."'},'Errors':'".$e."'}}";
}

function welcome_email($cids)
{
	
}

include(".\connect_db.php");
include(".\common_func.php");
include(".\email_notify.php");
getleadmail();

?>