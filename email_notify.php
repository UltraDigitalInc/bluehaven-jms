<?php

//ini_set('display_errors','On');
//error_reporting(E_ALL);

function set_intro_email_processed($iid,$etid,$ptype)
{
	$qry = "UPDATE jest..EmailIntrosSent SET pcomplete=".$ptype.",etid=".$etid.",pdate=getdate() WHERE iid=".$iid.";";
	$res = mssql_query($qry);
}

function process_email_intro($etidi)
{
	if (isset($_REQUEST['introbypass']) && $_REQUEST['introbypass']==1)
	{
		//set_intro_email_processed($n,$row['etid'],9);
	}
	else
	{
		$errors=false;
		$chistory=true;
		$errtext='';
		$out='';
		$iprocs_ar=array();
		
		if (isset($etidi) and $etidi!=0)
		{
			$bprovide=false;
			$iprocs_ar[]=$etidi;
		}
		else
		{
			$bprovide=true;
			$qryPRE = "
						select iid,cid from jest..EmailIntrosSent WHERE pcomplete=0 and adate >= (getdate() - 7) ORDER by adate ASC;				
			";
			$resPRE = mssql_query($qryPRE);
			$nrowPRE= mssql_num_rows($resPRE);
			
			if ($nrowPRE > 0)
			{
				while($rowPRE = mssql_fetch_array($resPRE))
				{
					$iprocs_ar[$rowPRE['iid']]=$rowPRE['cid'];
				}
			}
		}
		
		//echo 'CID count: '. count($iprocs_ar) .'<br>';
		
		//print_r($iprocs_ar);
		
		if (count($iprocs_ar) > 0)
		{
			$iesent=0;
			foreach ($iprocs_ar as $n => $v)
			{
				$qryPRE1 = "
							select
								C.cid,C.officeid,C.cfname,C.clname,C.cemail,C.stage,C.apptmnt,C.callback,C.securityid,C.opt1,C.opt2,C.cid as c2id,
								(select intro_etid from offices where officeid=C.officeid) as ietid
							from cinfo AS C
							where cid=".$v.";
							";
				$resPRE1 = mssql_query($qryPRE1);
				$rowPRE1 = mssql_fetch_array($resPRE1);
				$nrowPRE1 = mssql_num_rows($resPRE1);
	
				if ($nrowPRE1 > 0 and (isset($rowPRE1['ietid']) and $rowPRE1['ietid'] != 0))
				{
					$qry = "SELECT * FROM jest..EmailTemplate WHERE etid=".$rowPRE1['ietid'].";";
					$res = mssql_query($qry);
					$row = mssql_fetch_array($res);
					$nrow= mssql_num_rows($res);
					
					$qry1a = "SELECT * FROM jest..EmailProfile WHERE pid=".$row['epid'].";";
					$res1a = mssql_query($qry1a);
					$row1a = mssql_fetch_array($res1a);
					$nrow1a= mssql_num_rows($res1a);
					$emcnt=1;
					
					if ($nrow1a > 0)
					{					
						if (valid_email_addr(trim($rowPRE1['cemail'])))
						{		
							$qry2 = "SELECT esid,sdate FROM jest..EmailTracking WHERE cid=".$rowPRE1['c2id']." and tid=".$rowPRE1['ietid']." and active=1;";
							$res2 = mssql_query($qry2);
							$row2 = mssql_fetch_array($res2);
							
							if (mssql_num_rows($res2) <= $row['sendallowance'])
							{
								$efile		=trim($row['fileattach']);
								$erecp		=trim($rowPRE1['cemail']);
								$cfname		=$rowPRE1['cfname'];
								$clname		=$rowPRE1['clname'];
								$cemail		=$rowPRE1['cemail'];
								$apptmnt	=date('l F jS Y',strtotime($rowPRE1['apptmnt'])).' at '.date('h:i A',strtotime($rowPRE1['apptmnt']));;
								$cname		=$cfname." ".$clname." <".$cemail.">";
								$efrom		=trim($row1a['elogin']);
								$ereply		=trim($row1a['ereply']);
								$epswd		=trim($row1a['epswd']);
								$ename		=trim($row1a['ename']);
								$ehost		=trim($row1a['ehost']);
								$eport		=$row1a['eport'];
								$SMTPdebug	=1;
								$corpname	='Blue Haven Pools & Spas';
								
								//echo 'From Office<br>';
								$qryC = "SELECT O.phone,O.gm,O.am,(select fname from jest..security where securityid=O.gm) as ogmfn,(select lname from jest..security where securityid=O.gm) as ogmln FROM jest..offices as O WHERE O.officeid = ".$rowPRE1['officeid'].";";
								$resC = mssql_query($qryC);
								$rowC = mssql_fetch_array($resC);
								
								$ophone =trim($rowC['phone']);
								$ogmfull=trim($rowC['ogmfn']).' '.trim($rowC['ogmln']);
								
								if (isset($rowPRE1['securityid']) && $rowPRE1['securityid']!=0)
								{
									$qryD = "SELECT fname,lname,phone,ext FROM jest..security WHERE securityid = ".$rowPRE1['securityid'].";";
									$resD = mssql_query($qryD);
									$rowD = mssql_fetch_array($resD);
										
									$esender=$rowD['fname']." ".$rowD['lname'];
									
									if (isset($rowD['phone']) && (strlen(trim($rowD['phone'])) == 10 || strlen(trim($rowD['phone'])) == 12))
									{
										$ephone=trim($rowD['phone']) . " " . trim($rowD['ext']);
									}
									elseif (isset($rowC['phone']) && (strlen(trim($rowC['phone'])) == 10 || strlen(trim($rowC['phone'])) == 12))
									{
										$ephone=$ophone;
									}
									else
									{
										$ephone='';
									}
								}
								else
								{
									$esender='';
									$ephone='';
								}
				
								$srch_ar=array(
										0=>'/CUSTOMERFULLNAME/',
										1=>'/CUSTOMERFIRSTNAME/',
										2=>'/CUSTOMERLASTNAME/',
										3=>'/CUSTOMEREMAILADDRESS/',
										4=>'/OFFICEPHONENUMBER/',
										5=>'/GMFULLNAME/',
										6=>'/SALESREPFULLNAME/',
										7=>'/CORPORATEFULLNAME/',
										8=>'/APPOINTMENTDATETIME/',
										9=>'/SALESREPPHONENUMBER/'
										);
								 
								 $res_ar =array(
										0=>$cname,
										1=>$cfname,
										2=>$clname,
										3=>$cemail,
										4=>$ophone,
										5=>$ogmfull,
										6=>$esender,
										7=>$corpname,
										8=>$apptmnt,
										9=>$ephone
										);
						
								$esubj=preg_replace($srch_ar,$res_ar,trim($row['esubject']));
								$ebody=preg_replace($srch_ar,$res_ar,trim($row['ebody']));
								
								if (isset($etidi) and $etidi!=0)
								{
									$iuid=$_SESSION['securityid'];
								}
								else
								{
									$iuid=1797;
								}
								
								$emc_ar=array(
											'to'=>		$erecp,
											'from'=>	$efrom,
											'efrom'=>	$efrom,
											'replyto'=>	$ereply,
											'fromname'=>$ename,
											'esubject'=>trim($esubj),
											'ebody'=>	trim($ebody),
											'oid'=> 	$rowPRE1['officeid'],
											'lid'=> 	$rowPRE1['stage'],
											'tid'=> 	$row['etid'],
											'cid'=> 	$rowPRE1['cid'],
											'uid'=> 	$iuid,
											'appt'=> 	'',
											'callb'=> 	'',
											'ename'=>	$row['name'],
											'ehost'=>	$ehost,
											'epswd'=>	$epswd,
											'eport'=>	$eport,
											'efile'=>	$efile,
											'chistory'=>$chistory,
											'SMTPdbg'=>	$SMTPdebug
										);
								
								if (isset($etidi) and $etidi!=0)
								{
									if (ExtEmailSendSSL($emc_ar))
									{
										echo $row['name'].' Email Sent<br>';
										$iesent++;
									}
								}
								else
								{
									if (ExtEmailSendSSL($emc_ar))
									{
										//echo 'Query IID processed: '. $n .'<br>';
										set_intro_email_processed($n,$rowPRE1['ietid'],1);
										$iesent++;
									}
									else
									{
										set_intro_email_processed($n,$rowPRE1['ietid'],9);
									}
								}
							}
						}
					}
				}
				else
				{
					set_intro_email_processed($n,$rowPRE1['ietid'],9);
				}
			}
			//echo 'CID processed: '. $iesent .'<br>';
		}
	}
}

function JMS_email_notify($cid,$gm,$sm,$la,$sid,$sub)
{
	$out=0;
	if (isset($cid) and $cid!=0)
	{		
		$to=array();		
		$qry1 = "SELECT  c.officeid
						,c.securityid
						,c.cfname
						,c.clname
						,c.added
						,(select name from offices where officeid=c.officeid) as oname
						,(select substring(slevel,13,1) from security where securityid=c.securityid) as srslevel
						,(select enotify from security where securityid=c.securityid) as srnotify
						,(select email from security where securityid=c.securityid) as sremail
				FROM
					cinfo as c WHERE c.cid=".(int) $cid.";";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		$nrow1= mssql_num_rows($res1);
		
		if ($nrow1 > 0)
		{
			if ($gm or $sm or $la)
			{
				$qry2 = "SELECT  o.gm
								,(select substring(slevel,13,1) from security where securityid=o.gm) as gmslevel
								,(select enotify from security where securityid=o.gm) as gmnotify
								,(select email from security where securityid=o.gm) as gmemail
								,o.sm
								,(select substring(slevel,13,1) from security where securityid=o.sm) as smslevel
								,(select enotify from security where securityid=o.sm) as smnotify
								,(select email from security where securityid=o.sm) as smemail
								,o.am
								,(select substring(slevel,13,1) from security where securityid=o.am) as laslevel
								,(select enotify from security where securityid=o.am) as lanotify
								,(select email from security where securityid=o.am) as laemail
								,o.name
						FROM
							offices as o WHERE o.officeid=".(int) $row1['officeid'].";";
				$res2 = mssql_query($qry2);
				$row2 = mssql_fetch_array($res2);
				
				if ($gm and isset($row2['gm']) and $row2['gm']!=0 and $row2['gmslevel']!=0 and $row2['gmnotify']==1 and valid_email_addr($row2['gmemail']))
				{
					if (!in_array($row2['gm'],$to))
					{
						$to[]=trim($row2['gmemail']);
					}
				}
				
				if ($la and isset($row2['am']) and $row2['am']!=0 and $row2['laslevel']!=0 and $row2['lanotify']==1 and valid_email_addr($row2['laemail']))
				{
					if (!in_array($row2['am'],$to))
					{
						$to[]=trim($row2['laemail']);
					}
				}
			}
			
			$to[]='sschirmer@corp.bluehaven.com';
			
			if (isset($to) and is_array($to) and count($to) > 0 and (isset($sub) and strlen($sub) > 0))
			{
				$qryP = "SELECT * FROM jest..EmailProfile WHERE pid=1;";
				$resP = mssql_query($qryP);
				$rowP = mssql_fetch_array($resP);
				$nrowP= mssql_num_rows($resP);
				
				$mess	 = "Lead  : ".htmlspecialchars_decode(trim($row1['cfname']))." ".htmlspecialchars_decode(trim($row1['clname']))."\r\n";
				$mess	.= "Office: ".trim($row1['oname'])."\r\n";
				$mess	.= "Added : ".date('m/d/Y g:iA T',strtotime($row1['added']))."\r\n";
				$mess	.= "----------------------\r\n";
				$mess	.= "This is an automated message from the Blue Haven JMS.\r\nDo not reply.\r\n";
				
				$emc_ar=array(
					'to'=>		$to,
					'from'=>	trim($rowP['elogin']),
					'efrom'=>	trim($rowP['elogin']),
					'fromname'=>trim($rowP['ename']),
					'esubject'=>trim($sub),
					'ebody'=>	trim($mess),
					'ehost'=>	trim($rowP['ehost']),
					'epswd'=>	trim($rowP['epswd']),
					'eport'=>	trim($rowP['eport']),
					'replyto'=>	'noreply@bluehaven.com',
					'oid'=> 	89,
					'lid'=> 	0,
					'tid'=> 	0,
					'cid'=> 	$cid,
					'uid'=> 	1797,
					'ename'=>	'',
					'chistory'=>false,
					'SMTPdbg'=>	1
				);
	
				ExtEmailSendSSL($emc_ar);
				$out=$cid;
			}
		}
	}
	
	return $out;
}

function JMS_customer_email_notify($cid,$gm,$sm,$la,$sr,$sid,$sub)
{
	$out=0;
	if (isset($cid) and $cid!=0)
	{
		$to=array();		
		$qry1 = "SELECT  c.officeid
						,c.securityid
						,c.cfname
						,c.clname
						,c.added
						,(select name from offices where officeid=c.officeid) as oname
						,(select substring(slevel,13,1) from security where securityid=c.securityid) as srslevel
						,(select enotify from security where securityid=c.securityid) as srnotify
						,(select email from security where securityid=c.securityid) as sremail
				FROM
					cinfo as c WHERE c.cid=".$cid.";";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		$nrow1= mssql_num_rows($res1);
		
		if ($nrow1 > 0)
		{
			if ($gm or $sm or $la)
			{
				$qry2 = "SELECT  o.gm
								,(select substring(slevel,13,1) from security where securityid=o.gm) as gmslevel
								,(select enotify from security where securityid=o.gm) as gmnotify
								,(select email from security where securityid=o.gm) as gmemail
								,o.sm
								,(select substring(slevel,13,1) from security where securityid=o.sm) as smslevel
								,(select enotify from security where securityid=o.sm) as smnotify
								,(select email from security where securityid=o.sm) as smemail
								,o.am
								,(select substring(slevel,13,1) from security where securityid=o.am) as laslevel
								,(select enotify from security where securityid=o.am) as lanotify
								,(select email from security where securityid=o.am) as laemail
								,o.name
						FROM
							offices as o WHERE o.officeid=".$row1['officeid'].";";
				$res2 = mssql_query($qry2);
				$row2 = mssql_fetch_array($res2);
				
				if ($gm and (isset($row2['gm']) and $row2['gm']!=0) and $row2['gmslevel']!=0 and $row2['gmnotify']==1 and valid_email_addr($row2['gmemail']))
				{
					if (!in_array($row2['gm'],$to))
					{
						$to[]=trim($row2['gmemail']);
					}
				}
				
				if ($la and (isset($row2['am']) and $row2['am']!=0) and $row2['laslevel']!=0 and $row2['lanotify']==1 and valid_email_addr($row2['laemail']))
				{
					if (!in_array($row2['am'],$to) and $row2['am']!=$row2['gm'])
					{
						$to[]=trim($row2['laemail']);
					}
				}
			}
			
			if ($sr and (isset($row1['srnotify']) and $row1['srnotify']!=0) and valid_email_addr($row1['sremail']))
			{
				$to[]=trim($row1['sremail']);
			}
			
			$to[]='sschirmer@corp.bluehaven.com';
			
			if (isset($to) and is_array($to) and count($to) > 0 and (isset($sub) and strlen($sub) > 0))
			{
				$mess	 = "Lead  : ".htmlspecialchars_decode(trim($row1['cfname']))." ".htmlspecialchars_decode(trim($row1['clname']))."\r\n";
				$mess	.= "Office: ".trim($row1['oname'])."\r\n";
				$mess	.= "----------------------\r\n";
				$mess	.= "This is an automated message from the Blue Haven JMS.\r\nDo not reply.\r\n";
				
				$emc_ar=array(
					'to'=>		$to,
					'from'=>	'jmsadmin@bhnmi.com',
					'FromName'=>'JMS System Admin',
					'esubject'=>trim($sub),
					'ebody'=>	trim($mess),
					'oid'=> 	89,
					'lid'=> 	0,
					'tid'=> 	0,
					'cid'=> 	$cid,
					'uid'=> 	1797,
					'ename'=>	'',
					'chistory'=>false,
					'SMTPdbg'=>	1
				);
	
				ExtEmailSendPlain($emc_ar);
				$out=$cid;
			}
		}
	}
	
	return $out;
}

//include ('.\connect_db.php');
//include ('.\common_func.php');

//JMS_email_notify(260124,true,true,true,true,'JMS Notification: New Lead from BHNM!');

?>