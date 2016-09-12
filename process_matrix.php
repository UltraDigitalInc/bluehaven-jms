<?php
// Auto Lead Processing
// This script will:
// 1. Log into a designated email account
// 2. Parse out the Emails for a specific Subject Line
// 3. Attempt to auto deliver to appropriate office

ini_set('display_errors','On');
error_reporting(E_ALL);
date_default_timezone_set('America/Los_Angeles');

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

function check_dupe($msgid,$email,$phone,$addrs)
{
    $out     =array(false);
    $msgid_ar=array();
    $email_ar=array();
    $phone_ar=array();
    $addrs_ar=array();
    $intrvl  =30;
    
    $qry = "SELECT lid,fname,lname,phone,email,addr,zip,added,url_ref from jest..lead_inc where sorted!=0 and added between (getdate()-".(int) $intrvl.") and getdate();";
	$res = mssql_query($qry);
	while($row = mssql_fetch_array($res))
	{
        $msgid_ar[$row['lid']]=trim($row['url_ref']);
        $email_ar[$row['lid']]=trim($row['email']);
        $phone_ar[$row['lid']]=trim($row['phone']);
        $addrs_ar[$row['lid']]=trim($row['addr']);
    }

    $msgid = trim($msgid);    
    if (!empty($msgid) and arr_val_count(trim($msgid),$msgid_ar) > 0)
    {
        $out[0]=true;
        $out[1][]='Duplicate Lead';
    }
    
    $email = trim($email);
    if (!empty($email) and arr_val_count(trim($email),$email_ar) > 0)
    {
        $out[0]=true;
        $out[1][]='Duplicate Email';
    }
    
    $phone = trim($phone);
    if (!empty($phone) and arr_val_count(trim($phone),$phone_ar) > 0)
    {
        $out[0]=true;
        $out[1][]='Duplicate Phone';
    }
    
    return $out;
}

function parse_email_XML($c)
{
	$p = xml_parser_create();
	xml_parse_into_struct($p,trim($c),$v,$i);
	xml_parser_free($p);
	return array($v,$i);
}

function parse_email_body_xml($src,$ref)
{
	$out	=array('URLREF'=>trim($ref),'XTRA'=>'','ERRORS'=>0);
	$ntags	=array('LEAD','NAME','ADDRESS','LIKES');
	$ttags	=array('FIRST','LAST');
	$utags	=array('SUBMITTED','FIRST','LAST','PHONE','EMAIL','STREET','CITY','STATE','ZIP'); //usable tags
	$xtags	=array('LIKE1','LIKE2','LIKE3','LIKE4','COMMENTS','HOW'); //xtra tags
	
	$xml_ar=parse_email_XML($src);
	$x= (array_key_exists('FIRST',$xml_ar[1])||array_key_exists('LAST',$xml_ar[1])) ? true:false;

	foreach($xml_ar[0] as $n=>$v)
	{
		if (!in_array($v['tag'],$ntags))
		{
			$tval = (!empty($v['value']))? $v['value'] : '';
			
			if (in_array($v['tag'],$xtags))
			{
				if (!empty($tval))
				{
						$out['XTRA']=$out['XTRA'].$v['tag'].': '.htmlspecialchars(removequote(trim($tval))).chr(13);
				}
			}
			else
			{
				$out[$v['tag']]=htmlspecialchars(removequote(trim($tval)));
			}
		}
	}
	
	if (!$x)
	{
		foreach($xml_ar[0] as $n1=>$v1)
		{
			if ($v1['tag']=='NAME')
			{
				$out['LAST']=$v1['value'];
			}
		}
	}
	
	if (empty($out['XTRA']))
	{
		unset($out['XTRA']);
	}
	
	return $out;
}

function insert_emailOLD($email)
{
	$err=0;
	
	if (!empty($email['URLREF']))
	{
		$qryP  = "SELECT url_ref FROM jest..lead_inc WHERE url_ref='".trim($email['URLREF'])."';";
		$resP  = mssql_query($qryP);
		$nrowP  = mssql_num_rows($resP);
		
		if ($nrowP > 0)
		{
			$err++;
		}
	}

	if ($err==0)
	{
		$qry  = "INSERT INTO jest..lead_inc (";
		$qry .= 'submitted,';
		$qry .= 'zip,';
		$qry .= (!empty($email['PHONE'])) ? 'phone,':'';
		$qry .= (!empty($email['EMAIL'])) ? 'email,':'';
		$qry .= (!empty($email['FIRST'])) ? 'fname,':'';
		$qry .= 'lname,';
		$qry .= (!empty($email['STREET'])) ? 'addr,':'';	
		$qry .= (!empty($email['CITY'])) ? 'city,':'';
		$qry .= (!empty($email['STATE'])) ? 'state,':'';	
		$qry .= (!empty($email['XTRA'])) ? 'comments,':'';
		$qry .= (!empty($email['URLREF'])) ? 'url_ref,':'';
		$qry .= "opt1,";
		$qry .= "opt2,";
		$qry .= "opt3,";
		$qry .= "opt4,";
		$qry .= "source";
		$qry .= ") VALUES (";
		$qry .= (!empty($email['SUBMITTED'])) ? "'".date('m/d/Y H:i:s',strtotime($email['SUBMITTED']))."',": "'".date('m/d/Y H:i:s',time())."',";
		$qry .= (!empty($email['ZIP'])) ? "'".$email['ZIP']."',":"'00000',";
		$qry .= (!empty($email['PHONE'])) ? "'".$email['PHONE']."',":'';
		$qry .= (!empty($email['EMAIL'])) ? "'".$email['EMAIL']."',":'';
		$qry .= (!empty($email['FIRST'])) ? "'".$email['FIRST']."',":'';
		$qry .= (!empty($email['LAST'])) ? "'".$email['LAST']."',":"'Not Provided',";
		$qry .= (!empty($email['STREET'])) ? "'".$email['STREET']."',":'';
		$qry .= (!empty($email['CITY'])) ? "'".$email['CITY']."',":'';
		$qry .= (!empty($email['STATE'])) ? "'".$email['STATE']."',":'';
		$qry .= (!empty($email['XTRA'])) ? "'".$email['XTRA']."',":'';
		$qry .= (!empty($email['URLREF'])) ? "'".$email['URLREF']."',":'';
		$qry .= "'0',";
		$qry .= "'0',";
		$qry .= "'0',";
		$qry .= "'0',";
		$qry .= "'0');";
		$qry .= " SELECT @@IDENTITY;";
		$res  = mssql_query($qry);
		$row  = mssql_fetch_row($res);
		
		$out=$row[0];
	}
	else
	{
		$out=0;
	}
	
	return $out;
}

function autosort_ZIP()
{
	echo '\nSORT_ZIP<br>';
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
	$nrow0	= mssql_num_rows($res0);
	
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
			$isdupe	=check_dupe($row['url_ref'],$row['email'],$row['phone'],$row['addr']);
			//$isdupe = false;
			$trzip	=trim($row['zip']);
			if (strlen($trzip) == 5 and !$isdupe[0])
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
								echo 'ORG';
								if ($rowB['am']!=0 && $rowB['active']==1)
								{
									//echo $row['phone']."(".$split[0].") SUB<br>";
									if (preg_match("/\\s/",trim($row['lname'])))
									{
										$ndata=splitonspace(($row['lname']));
									}
									else
									{
										$ndata=array($row['fname'],$row['lname']);
									}

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
									$qryC .= "'".$row['submitted']."',getdate(),'".$rowB['officeid']."','".$rowB['am']."','".replacequote($ndata[0])."','".replacequote($ndata[1])."',";
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
										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowB['officeid']."',secid='".$secid."',cid=".$rowC['cidid']." WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);
										
										if (!empty($row['comments']))
										{
												$qryDa	= "
														INSERT INTO jest..chistory (officeid,custid,secid,act,mtext)
														VALUES (".$rowB['officeid'].",".$rowC['cidid'].",".$secid.",'leads','".replacequote($row['comments'])."');";
												$resDa	= mssql_query($qryDa);
										}
										
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
								echo 'FWD';
								$qryBa	= "SELECT officeid,am,name,active FROM offices WHERE officeid='".$rowB['leadforward']."';";
								$resBa	= mssql_query($qryBa);
								$rowBa	= mssql_fetch_array($resBa);

								if ($rowBa['am']!=0 && $rowBa['active']==1)
								{
									//$ndata=array($row['fname'],$row['lname']);
									if (preg_match("/\\s/",trim($row['lname'])))
									{
										$ndata=splitonspace(($row['lname']));
									}
									else
									{
										$ndata=array($row['fname'],$row['lname']);
									}

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
									$qryC .= "'".$row['submitted']."',getdate(),'".$rowBa['officeid']."','".$rowBa['am']."','".replacequote($ndata[0])."','".replacequote($ndata[1])."',";
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
							
									echo $qryC.'<br>';
							
									$cid_ar[]=$rowC['cidid'];

									if (isset($rowC['cidid']) and $rowC['cidid']!=0)
									{
										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowBa['officeid']."',secid='".$secid."',cid=".$rowC['cidid']." WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);
										
										if (!empty($row['comments']))
										{
												$qryDa	= "
														INSERT INTO jest..chistory (officeid,custid,secid,act,mtext)
														VALUES (".$rowB['officeid'].",".$rowC['cidid'].",".$secid.",'leads','".replacequote($row['comments'])."');";
												$resDa	= mssql_query($qryDa);
										}
										
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
				else
				{
					$qryX	= "UPDATE lead_inc SET syscomment=':No Matching Zip Code:' WHERE lid='".$row['lid']."';";
				    $resX	= mssql_query($qryX);	
				}
			}
			else
			{
				if (is_array($isdupe[1]))
				{
				    $sysmsg='';
					foreach ($isdupe[1] as $v)
					{
						$sysmsg=$sysmsg.' : '.$v;
					}
					
					$qryX	= "UPDATE lead_inc SET syscomment='".$sysmsg."' WHERE lid='".$row['lid']."';";
				    $resX	= mssql_query($qryX);
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

// As of 06-11-2015 - new colon delimited format
function parse_email_body($body,$stp_err)
{
	$out=array('errors'=>array());
	$z=0;
	
	//var_dump($body).'<br>========<br>';
	echo "Step 1";

	// Default current date because email no longer has date and time
	$sub=date('m/d/Y G:i:s',time());
	$out['submitted']=$sub;
	
	// Match Incoming Email Info
	if (preg_match("/\s[0-9][0-9] [A-z][A-z][A-z] [0-9][0-9][0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9] [A-Z][A-Z][A-Z]/i",$body,$matches))
	{
		$sub=date('m/d/Y G:i:s',strtotime($matches[0]));
		$out['submitted']=$sub;
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='sub';
		}
	}
	echo "Step 2";

	if (preg_match("/Name: \w*\s\w*?\s?\w*/",$body,$matches))
	//if (preg_match("/Name:\s.*\n/",$body,$matches))
	{
		$u_name=preg_split("/ +/",$matches[0]);
		$u_name=array_slice($u_name,1);

		$name="";
		foreach($u_name as $n => $v)
		{
			$name=$name." ".$v;
		}
		$name=preg_replace('/^ /','',$name);
		$out['name']=str_replace("\n","",trim($name));
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='name';
		}
	}

	if (preg_match("/Address:\s+[\w+\s\'\.\#\@]+/",$body,$matches))
	{
		$u_addr=preg_split("/ +/",$matches[0]);
		$u_addr=array_slice($u_addr,1);

		$addr="";
		foreach($u_addr as $n => $v)
		{
			$addr=$addr." ".$v;
		}
		$addr=preg_replace('/^ /','',$addr);
		$out['addr']=str_replace("\n","",trim($addr));
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='addr';
		}
	}

	if (preg_match("/City: \w*\s?\w*\s?/",$body,$matches))
	{
		$u_city=preg_split("/ +/",$matches[0]);
		$u_city=array_slice($u_city,1);

		$city="";
		foreach($u_city as $n => $v)
		{
			$city=$city." ".$v;
		}
		$city=preg_replace('/^ /','',$city);
		$out['city']=str_replace("\n","",trim($city));
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='city';
		}
	}
	
	if (preg_match("/State: [a-zA-Z]{1,2}/",$body,$matches))
	{
		$u_state=preg_split("/ +/",$matches[0]);
		$u_state=array_slice($u_state,1);
		$out['state']=trim($u_state[0]);
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='state';
		}
	}
	
	if (preg_match("/Zip: [0-9]{1,5}/",$body,$matches))
	{
		$u_zip=preg_split("/ +/",$matches[0]);
		$u_zip=array_slice($u_zip,1);
		$out['zip']=trim($u_zip[0]);
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='zip';
		}
	}

	if (preg_match("/E-Mail:\s([a-z][a-z0-9_.-\/]*@[^\s\"\)\?<>]+\.[a-z]{2,6})/i",$body,$matches))
	{
		$u_email=preg_split("/ +/",$matches[0]);
		$u_email=array_slice($u_email,1);
		$out['email']=trim($u_email[0]);
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='email';
		}
	}

	echo "Step 3";
	if (preg_match("/Phone: 1?\W*([0-9][0-9][0-9])\W*([0-9][0-9]{2})\W*([0-9]{4})(\se?x?t?(\d*))?/",$body,$matches))
	{
		$u_phone=preg_split("/ +/",$matches[0]);
		if (count($u_phone)==2)
		{
			$phone=$u_phone[1];
		}

		$pat='/\(?\)?\-?\.?\s?/';
		$rep='';
		$out['phone']=preg_replace($pat,$rep,$phone);
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='phone';
		}
	}

	if (preg_match("/Contact Time: [0-9]{1,} Time [0-9]{1,}: [a-zA-Z][a-zA-Z]/",$body,$matches))
	{
		$u_time=preg_split("/ +/",$matches[0]);
		$out['time']=$u_time[2]." ".$u_time[3];
	}
	
	if (preg_match("/Opt1:\s[0-1]/",$body,$matches))
	{
		$u_opt1=preg_split("/ +/",$matches[0]);
		
		if ($u_opt1[1]==1)
		{
			$out['opt1']=$u_opt1[1];
		}
		else
		{
			$out['opt1']=0;
		}
	}
	else
	{
		$out['opt1']=0;
	}
	
	if (preg_match("/Opt2:\s[0-1]/",$body,$matches))
	{
		$u_opt2=preg_split("/ +/",$matches[0]);
		
		if ($u_opt2[1]==1)
		{
			$out['opt2']=$u_opt2[1];
		}
		else
		{
			$out['opt2']=0;
		}
	}
	else
	{
		$out['opt2']=0;
	}
	
	if (preg_match("/Opt3:\s[0-1]/",$body,$matches))
	{
		$u_opt3=preg_split("/ +/",$matches[0]);
		
		if ($u_opt3[1]==1)
		{
			$out['opt3']=$u_opt3[1];
		}
		else
		{
			$out['opt3']=0;
		}
	}
	else
	{
		$out['opt3']=0;
	}
	
	if (preg_match("/Opt4:\s[0-1]/",$body,$matches))
	{
		$u_opt4=preg_split("/ +/",$matches[0]);
		
		if ($u_opt4[1]==1)
		{
			$out['opt4']=$u_opt4[1];
		}
		else
		{
			$out['opt4']=0;
		}
	}
	else
	{
		$out['opt4']=0;
	}
		
	if (preg_match("/Source:\s[0-9]{1,3}/",$body,$matches))
	{
		$u_src1=preg_split("/ +/",$matches[0]);
		
		if (isset($u_src1[1]))
		{
			$out['src1']=$u_src1[1];
		}
		else
		{
			$out['src1']=0;
		}
	}
	else
	{
		$out['src1']=0;
	}

	// Comments Code
	if (preg_match("/Requests.*\n.*\n.*/i",$body,$matches))
	{
		$out['requests']=$matches[0];
	}
	else
	{
		$out['errors'][]='requests';
	}
	
	//echo '<pre>';
	//var_dump($out);
	//echo '</pre><br>';
	$out['body']=$body;
	echo $out;
	return $out;
}

function parse_email_bodyOLD($body,$stp_err)
{
	$out=array('errors'=>array());
	$z=0;
	
	//var_dump($body).'<br>========<br>';
	
	// Match Incoming Email Info
	if (preg_match("/\s[0-9][0-9] [A-z][A-z][A-z] [0-9][0-9][0-9][0-9] [0-9][0-9]:[0-9][0-9]:[0-9][0-9] [A-Z][A-Z][A-Z]/i",$body,$matches))
	{
		$sub=date('m/d/Y G:i:s',strtotime($matches[0]));
		$out['submitted']=$sub;
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='sub';
		}
	}

	if (preg_match("/Name: \w*\s\w*?\s?\w*/",$body,$matches))
	//if (preg_match("/Name:\s.*\n/",$body,$matches))
	{
		$u_name=preg_split("/ +/",$matches[0]);
		$u_name=array_slice($u_name,1);

		$name="";
		foreach($u_name as $n => $v)
		{
			$name=$name." ".$v;
		}
		$name=preg_replace('/^ /','',$name);
		$out['name']=str_replace("\n","",trim($name));
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='name';
		}
	}

	if (preg_match("/Address:\s+[\w+\s\'\.\#\@]+/",$body,$matches))
	{
		$u_addr=preg_split("/ +/",$matches[0]);
		$u_addr=array_slice($u_addr,1);

		$addr="";
		foreach($u_addr as $n => $v)
		{
			$addr=$addr." ".$v;
		}
		$addr=preg_replace('/^ /','',$addr);
		$out['addr']=str_replace("\n","",trim($addr));
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='addr';
		}
	}

	if (preg_match("/City: \w*\s?\w*\s?/",$body,$matches))
	{
		$u_city=preg_split("/ +/",$matches[0]);
		$u_city=array_slice($u_city,1);

		$city="";
		foreach($u_city as $n => $v)
		{
			$city=$city." ".$v;
		}
		$city=preg_replace('/^ /','',$city);
		$out['city']=str_replace("\n","",trim($city));
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='city';
		}
	}
	
	if (preg_match("/State: [a-zA-Z]{1,2}/",$body,$matches))
	{
		$u_state=preg_split("/ +/",$matches[0]);
		$u_state=array_slice($u_state,1);
		$out['state']=trim($u_state[0]);
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='state';
		}
	}
	
	if (preg_match("/Zip: [0-9]{1,5}/",$body,$matches))
	{
		$u_zip=preg_split("/ +/",$matches[0]);
		$u_zip=array_slice($u_zip,1);
		$out['zip']=trim($u_zip[0]);
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='zip';
		}
	}

	if (preg_match("/E-mail:\s([a-z][a-z0-9_.-\/]*@[^\s\"\)\?<>]+\.[a-z]{2,6})/i",$body,$matches))
	{
		$u_email=preg_split("/ +/",$matches[0]);
		$u_email=array_slice($u_email,1);
		$out['email']=trim($u_email[0]);
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='email';
		}
	}

	if (preg_match("/Phone Number: \(?[0-9]{3}\)?(-|.|\/|\w)\(?[0-9]{3}\)?(-|.|\/|\w)\(?[0-9]{4}\)? [a-zA-Z]{1,4}/",$body,$matches))
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

		if (trim($conph)=="home")
		{
			$conph="hm";
		}
		else
		{
			$conph="wk";
		}
		
		$pat='/\(?\)?\-?\.?\s?/';
		$rep='';
		$out['phone']=preg_replace($pat,$rep,$phone);
	}
	else
	{
		if ($stp_err)
		{
			$out['errors'][]='phone';
		}
	}

	if (preg_match("/Contact Time: [0-9]{1,} Time [0-9]{1,}: [a-zA-Z][a-zA-Z]/",$body,$matches))
	{
		$u_time=preg_split("/ +/",$matches[0]);
		$out['time']=$u_time[2]." ".$u_time[3];
	}
	
	if (preg_match("/Opt1:\s[0-1]/",$body,$matches))
	{
		$u_opt1=preg_split("/ +/",$matches[0]);
		
		if ($u_opt1[1]==1)
		{
			$out['opt1']=$u_opt1[1];
		}
		else
		{
			$out['opt1']=0;
		}
	}
	else
	{
		$out['opt1']=0;
	}
	
	if (preg_match("/Opt2:\s[0-1]/",$body,$matches))
	{
		$u_opt2=preg_split("/ +/",$matches[0]);
		
		if ($u_opt2[1]==1)
		{
			$out['opt2']=$u_opt2[1];
		}
		else
		{
			$out['opt2']=0;
		}
	}
	else
	{
		$out['opt2']=0;
	}
	
	if (preg_match("/Opt3:\s[0-1]/",$body,$matches))
	{
		$u_opt3=preg_split("/ +/",$matches[0]);
		
		if ($u_opt3[1]==1)
		{
			$out['opt3']=$u_opt3[1];
		}
		else
		{
			$out['opt3']=0;
		}
	}
	else
	{
		$out['opt3']=0;
	}
	
	if (preg_match("/Opt4:\s[0-1]/",$body,$matches))
	{
		$u_opt4=preg_split("/ +/",$matches[0]);
		
		if ($u_opt4[1]==1)
		{
			$out['opt4']=$u_opt4[1];
		}
		else
		{
			$out['opt4']=0;
		}
	}
	else
	{
		$out['opt4']=0;
	}
		
	if (preg_match("/Source:\s[0-9]{1,3}/",$body,$matches))
	{
		$u_src1=preg_split("/ +/",$matches[0]);
		
		if (isset($u_src1[1]))
		{
			$out['src1']=$u_src1[1];
		}
		else
		{
			$out['src1']=0;
		}
	}
	else
	{
		$out['src1']=0;
	}

	/*
	// Comments Code
	if (preg_match("/\-{1,}\nRequests\n\-{1,}\n(.{1,}\n){1,}/i",$body,$matches))
	{
		$out['requests']=$matches[0];
	}
	else
	{
		$out['errors'][]='requests';
	}
	*/
	
	//echo '<pre>';
	//var_dump($out);
	//echo '</pre><br>';
	$out['body']=$body;
	
	return $out;
}

function insert_email($email)
{
	$out=0;
	
	if (is_array($email))
	{
		$qry0  = "INSERT INTO lead_inc ";
		$qry0 .= "(submitted,lname,addr,city,state,zip,phone,email,comments,opt1,opt2,opt3,opt4,source) ";
		$qry0 .= "VALUES (";
		$qry0 .= "'".trim($email['submitted'])."','".replacequote($email['name'])."','".replacequote($email['addr'])."',";
		$qry0 .= "'".replacequote($email['city'])."','".$email['state']."','".$email['zip']."','".$email['phone']."',";
		$qry0 .= "'".$email['email']."','".replacequote($email['body'])."',";
		$qry0 .= "'".$email['opt1']."','".$email['opt2']."','".$email['opt3']."','".$email['opt4']."','".$email['src1']."'); SELECT @@IDENTITY;";
		$res0  = mssql_query($qry0);
		$row0  = mssql_fetch_row($res0);
		
		$out=$row0[0];
	}
	
	return $out;
}

function getleadmail()
{	
	$ssubject			= "Web Site Info Request";
	$MAIL_HOST			= "imap.gmail.com";
	$MAIL_HOST_CONNECT	= "{".$MAIL_HOST.":993/imap/ssl/novalidate-cert}";
	$MAIL_USER_NAME		= "datasysproc@bluehaven.com";
	$MAIL_USER_PASS		= "swimming1";
	$stop_on_error		= false;

	$mbox	= imap_open($MAIL_HOST_CONNECT,$MAIL_USER_NAME,$MAIL_USER_PASS) or die("Error: Could not Connect: ".print_r(imap_errors()));
	$emails = imap_search($mbox,'SUBJECT "'.$ssubject.'"',SE_UID);
	
	if ($emails)
	{
		$ce=0;
		$co=0;
		sort($emails);		
		foreach ($emails as $emailnum)
		{
			$ce++;
			$overview = imap_fetch_overview($mbox,$emailnum,FT_UID);
			
			if ($overview[0]->seen == 0)
			{
				$mailout = parse_email_body(imap_body($mbox,$emailnum,FT_UID|FT_PEEK),$stop_on_error);
				$mbody	=imap_body($mbox,$emailnum,FT_UID|FT_PEEK);
				//$mailout=parse_email_body_xml($mbody,$overview[0]->message_id);
				
				$ie=insert_email($mailout);
				if ($ie!=0)
				{
					imap_setflag_full($mbox,$emailnum,"//Seen",ST_UID);
						
					if (imap_mail_move($mbox,$emailnum,'Old',CP_UID))
					{
						imap_delete($mbox,$emailnum,FT_UID);
					}
						
					$co++;
				}
			}
		}
	}
	
	imap_close($mbox,CL_EXPUNGE);
	
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
	
	//echo "{success: true, results: {'Mailbox': '".$total."','Processed':'".$y."','Completed': {'ZIP':'".count($cid_ZIP)."','DIR':'".count($cid_DIR)."','EML':'".$email_ecnt."'},'Errors':'".$e."'}}";
	//echo 'Emails: '.$ce.' Processed: '.$co;
}

function welcome_email($cids)
{
	
}

include(".\connect_db.php");
include(".\common_func.php");
include(".\email_notify.php");
getleadmail();

?>