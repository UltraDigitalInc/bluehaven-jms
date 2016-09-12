<?php

class JMSSYS_ENV
{
    const SYS_ADMIN1 = 2;
}

function frm_ftr()
{
	echo "</form>\n";
}

function arr_val_count($val,$arr)
{
    $out=0;
    foreach ($arr as $v)
    {
        if ($v==$val)
        {
            $out++;
        }
    }
    return $out;
}

function is_base64_encoded($d)
{
	if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $d))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function digreportexists($dd)
{
	if (isset($dd) and $dd >= strtotime('1/1/2005'))
	{
		$prd_mo=date('m',$dd);
		$prd_yr=date('Y',$dd);
		
		$qry	= "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$prd_mo."' AND rept_yr='".$prd_yr."';";
		$res	= mssql_query($qry);
		$nrow	= mssql_num_rows($res);
		
		if ($nrow > 0)
		{
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

function set_session_state($sid)
{
	$qry0 = "SELECT securityid,officeid,login,fname,lname,slevel,mlevel,off_demo,csrep,emailtemplateaccess,officelist,modcomm,acctngrelease,tester FROM security WHERE securityid=".$sid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$qry1 = "SELECT officeid,code,altcode,name,pb_code,pft_sqft,manphsadj,logging,otype,timeshift FROM offices WHERE officeid=".$row0['officeid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	$_SESSION['last_access']=time();
	$_SESSION['securityid']	=$row0['securityid'];
	$_SESSION['officeid']	=$row1['officeid'];
	$_SESSION['plogin']		=$row0['login'];
	$_SESSION['fname']		=$row0['fname'];
	$_SESSION['lname']		=$row0['lname'];
	$_SESSION['fullname']	=$row0['fname'].' '.$row0['lname'];
	$_SESSION['csrep']		=$row0['csrep'];
	$_SESSION['modcomm']	=$row0['modcomm'];
	$_SESSION['tester']		=$row0['tester'];
	$_SESSION['offname']	=$row1['name'];
	$_SESSION['altcode']	=$row1['altcode'];
	$_SESSION['manphsadj']	=$row1['manphsadj'];
	$_SESSION['acctngrelease']=$row0['acctngrelease'];
	$_SESSION['admin_offs'] =array(89);
	$_SESSION['emailtemplates']=$row0['emailtemplateaccess'];
	$_SESSION['otype']		=$row1['otype'];
	$_SESSION['timeshift']	=$row1['timeshift'];

	if ($row1['altcode']!=0)
	{
		$_SESSION['pb_code']		=$row1['altcode'];
	}
	else
	{
		if ($row1['pb_code']==0)
		{
			$_SESSION['pb_code']		="";
		}
		else 
		{
			$_SESSION['pb_code']		=$row1['pb_code'];
		}
	}

	if ($row1['code']!=0 || $row1['code']!=$row1['altcode']) // Sets Alt Office Code if necessary
	{
		$_SESSION['offcode'] 	=$row1['altcode'];
	}

	$_SESSION['offcode'] 	=$row1['code'];

	list($_SESSION['elev'],$_SESSION['clev'],$_SESSION['jlev'],$_SESSION['llev'],$_SESSION['rlev'],$_SESSION['mlev'],$_SESSION['tlev'])=split(",",$row0['slevel'],7);
	list($_SESSION['m_plev'],$_SESSION['m_llev'],$_SESSION['m_ulev'],$_SESSION['m_mlev'],$_SESSION['m_tlev'])=split(",",$row0['mlevel'],5);

	$_SESSION['aid']  		=$_SESSION['securityid'];
	$_SESSION['off_demo']  	=$row0['off_demo'];

	if ($row1['logging']==1) // Sets Logging function
	{
		$_SESSION['logging']		=1;
	}
}

function HelpNode($nodeid,$overlay)
{
    if (HELPNODES && isset($nodeid) && strlen($nodeid) > 4)
    {
		$hlpobj_ar=array('helpid'=>'manoverlay'.$overlay,'showid'=>'showman'.$overlay,'hideid'=>'hideman'.$overlay,'cnerid'=>'hlptxt'.$overlay);
		
		$qry0 = "SELECT nid,nodeid,nodetitle,nodetext,nodefoot,imgtext FROM jest_doc..HelpNode WHERE nodeid='".$nodeid."';";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 != 0)
		{
			$row0=mssql_fetch_array($res0);
			
			$imgtxt=$row0['imgtext'];
			$hdtxt =$row0['nodetitle'];
			
			if ($_SESSION['securityid'] == 26 || $_SESSION['securityid'] == 332 || $_SESSION['securityid'] == 1732)
			{
				$hdtxt.=" <a href=\"https://jms.bhnmi.com/docs/helpnodes.php?nodeid=".$row0['nodeid']."&call=edit\" target=\"subDocViewer\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','subDocViewer','HEIGHT=700,WIDTH=900,status=0,scrollbars=1,dependent=1,resizable=0,toolbar=0,menubar=0,location=0,directories=0'); window.status=''; return true;\">Edit</a>";
			}
	
			$bdtxt=$row0['nodetext'];
			$fttxt=$row0['nodefoot'];
			
			echo "<img id=\"".$hlpobj_ar['showid']."\" src=\"images/help.png\" title=\"".$imgtxt."\">\n";
			echo "<span id=\"".$hlpobj_ar['cnerid']."\"></span>\n";
			
			echo "
					<div class=\"yui-overlay\" id=\"".$hlpobj_ar['helpid']."\" style=\"visibility:hidden\">
					<div class=\"hd\"><div class=\"black\">".$hdtxt."</div></div>
					<div class=\"bd\"><div class=\"black\">".$bdtxt."</div></div>
					<div class=\"ft\"><div class=\"black\">".$fttxt."</div></div>
					</div>
			";
			
		}
		else
		{
			if ($_SESSION['securityid'] == 26 || $_SESSION['securityid'] == 332 || $_SESSION['securityid'] == 1732)
			{
				$imgtxt=$nodeid;
				$hdtxt ="".$nodeid."";
				$hdtxt.=" <a href=\"https://jms.bhnmi.com/docs/helpnodes.php?nodeid=".$nodeid."&call=create\" target=\"subDocViewer\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','subDocViewer','HEIGHT=700,WIDTH=900,status=0,scrollbars=1,dependent=1,resizable=0,toolbar=0,menubar=0,location=0,directories=0'); window.status=''; return true;\">Add</a>";			
				$bdtxt="This Help Node has not been completed";
				$fttxt="";
				
				echo "<img id=\"".$hlpobj_ar['showid']."\" src=\"images/help.png\" title=\"".$imgtxt."\">\n";
				echo "<span id=\"".$hlpobj_ar['cnerid']."\"></span>\n";
				
				echo "
						<div class=\"yui-overlay\" id=\"".$hlpobj_ar['helpid']."\" style=\"visibility:hidden\">
						<div class=\"hd\"><div class=\"black\">".$hdtxt."</div></div>
						<div class=\"bd\"><div class=\"black\">".$bdtxt."</div></div>
						<div class=\"ft\"><div class=\"black\">".$fttxt."</div></div>
						</div>
				";
			}
		}
    }
}

function IndexOnce(&$ar, $index) {
	return $ar[$index];
}

function SendingEmailMSG()
{
	echo "<table class=\"outer\" width=\"250px\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\" align=\"center\"><strong>Sending Email, this may take up to 60 seconds.....</strong></td>\n";
	echo "	</tr>\n";
	echo "</table>";
	echo "<br>";
}

function process_template_email()
{
    //ini_set('display_errors','On');
    error_reporting(E_ALL);
    $errors=false;
    $errtext='';
    
	if ($_SESSION['securityid']==2699999999999999999999)
	{
		display_array($_REQUEST);
	}
	
	//Process Email Template
	if (isset($_REQUEST['etid']) && $_REQUEST['etid'] != 0)
	{
		if (isset($_REQUEST['chistory']) && $_REQUEST['chistory']==1)
		{
			$chistory=true;
		}
		else
		{
			$chistory=false;
		}
	
		if (!isset($_SESSION['et_uid']) && isset($_REQUEST['et_uid']))
		{
			if (isset($_REQUEST['cid']) && $_REQUEST['cid'] > 0)
			{
				//echo 'IN2<br>';
				$qry = "SELECT * FROM jest..EmailTemplate WHERE etid=".$_REQUEST['etid'].";";
				$res = mssql_query($qry);
				$row = mssql_fetch_array($res);
				$nrow= mssql_num_rows($res);
				
				if (isset($_REQUEST['restype']) && $_REQUEST['restype']=='Network')
				{
					$qry1 = "SELECT cnid,oid,cfname,clname,cemail,sid,cnid as c2id FROM jest..cinfo_net WHERE cnid=".$_REQUEST['cid'].";";
				}
				else
				{
					$qry1 = "SELECT cid,officeid,cfname,clname,cemail,stage,apptmnt,callback,securityid,opt1,opt2,cid as c2id FROM jest..cinfo WHERE cid=".$_REQUEST['cid'].";";
				}
				
				$res1 = mssql_query($qry1);
				$row1 = mssql_fetch_array($res1);
				$nrow1= mssql_num_rows($res1);
				
				$qry1a = "SELECT * FROM jest..EmailProfile WHERE pid=".$row['epid'].";";
				$res1a = mssql_query($qry1a);
				$row1a = mssql_fetch_array($res1a);
				$nrow1a= mssql_num_rows($res1a);
				
				$emcnt=1;
				
				if ($nrow1 > 0 && $nrow1a > 0)
				{					
					if (valid_email_addr(trim($row1['cemail'])))
					{		
						$qry2 = "SELECT esid,sdate FROM jest..EmailTracking WHERE cid=".$row1['c2id']." and tid=".$_REQUEST['etid']." and active=1;";
						$res2 = mssql_query($qry2);
						$row2 = mssql_fetch_array($res2);
						
						if ($_SESSION['emailtemplates'] >= 5)
						{
							$sendauth=true;
						}
						else
						{
							if (mssql_num_rows($res2) <= $row['sendallowance'])
							{
								$sendauth=true;
							}
							else
							{
								$sendauth=false;
							}
						}
						
						if ($nrow1 > 0 && $sendauth)
						{
							$erecp		=trim($row1['cemail']);
							$efile		=trim($row['fileattach']);
							$efrom		=trim($row1a['elogin']);
							$ereply		=trim($row1a['ereply']);
							$epswd		=trim($row1a['epswd']);
							$ename		=trim($row1a['ename']);
							$ehost		=trim($row1a['ehost']);
							$eport		=$row1a['eport'];
							$SMTPdebug	=1;
							$corpname	='Blue Haven Pools & Spas';
							
							if (isset($row1['cid']) && $row1['cid']!=0)
							{						
								$cfname=$row1['cfname'];
								$clname=$row1['clname'];
								$cemail=$row1['cemail'];
								$apptmnt=date('l F jS Y',strtotime($row1['apptmnt'])).' at '.date('h:i A',strtotime($row1['apptmnt']));;
								$cname=$cfname." ".$clname." <".$cemail.">";
							}
							else
							{
								$cfname='John';
								$clname='Customer';
								$cemail='customer@anywhere.com';
								$apptmnt='1/1/1970 12:00 AM';
								$cname=htmlspecialchars($cfname." ".$clname." <".$cemail.">");
							}
							
							if (isset($rowB['officeid']) && $rowB['officeid']!=0)
							{
								//echo 'From Office<br>';
								$qryC = "SELECT O.phone,O.gm,O.am,(select fname from jest..security where securityid=O.gm) as ogmfn,(select lname from jest..security where securityid=O.gm) as ogmln FROM jest..offices as O WHERE O.officeid = ".$rowB['officeid'].";";
							}
							else
							{
								//echo 'From Corporate<br>';
								$qryC = "SELECT O.phone,O.gm,O.am,(select fname from jest..security where securityid=O.gm) as ogmfn,(select lname from jest..security where securityid=O.gm) as ogmln FROM jest..offices as O WHERE O.officeid = ".$_SESSION['officeid'].";";
							}
							
							$resC = mssql_query($qryC);
							$rowC = mssql_fetch_array($resC);
							
							$ophone =trim($rowC['phone']);
							$ogmfull=trim($rowC['ogmfn']).' '.trim($rowC['ogmln']);
							
							if (isset($row1['securityid']) && $row1['securityid']!=0)
							{
								$qryD = "SELECT fname,lname,phone,ext FROM jest..security WHERE securityid = ".$row1['securityid'].";";
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
							
							$emc_ar=array(
										'to'=>		$erecp,
										'from'=>	$efrom,
										'efrom'=>	$efrom,
										'replyto'=>	$ereply,
										'fromname'=>$ename,
										'esubject'=>trim($esubj),
										'ebody'=>	trim($ebody),
										'oid'=> 	$row1['officeid'],
										'lid'=> 	$row1['stage'],
										'tid'=> 	$row['etid'],
										'cid'=> 	$row1['cid'],
										'uid'=> 	$_SESSION['securityid'],
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
							
							if ($_SESSION['securityid']==2699999999999999999)
							{
								display_array($emc_ar);
								
								//echo $qry.'<br>';
							}
							
							$mresult=ExtEmailSendSSL($emc_ar);
							
							if (!$mresult)
							{
								$errors=true;
								$errtext=$errtext.' Mail Server Send Error<br>';	
							}
						}
						else
						{
							$errors=true;
							$errtext=$errtext.' No Send Authority<br>';
						}
					}
					else
					{
						$errors=true;
						$errtext=$errtext.' Invalid Email: '.$erecp.'<br>';
					}
				}
				else
				{
					$errors=true;
					$errtext=$errtext.' CID not Found<br>';
				}
				
				$_SESSION['et_uid']=$_REQUEST['et_uid'];
			}
			else
			{
				$errors=true;
				$errtext=$errtext.' No Assigned CID<br>';
			}
		}
		else
		{
			$errors=true;
			$errtext=$errtext.' This Email has already been sent!<br>';
		}
	}
	else
	{
		$errors=true;
		$errtext=$errtext.' Template Not Set<br>';
	}
    
    if ($errors)
    {
		echo "<table class=\"outer\" width=\"400px\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\"><b>Send Email Failed for the following reason(s):</b></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\">".$errtext."</td>\n";
		echo "	<tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\">\n";
		
		if (isset($_REQUEST['etcid'][0]) && $_REQUEST['etcid'][0] !=0)
		{
			echo "                     		<form method=\"POST\">\n";
			echo "                     			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "                     			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "                     			<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['etcid'][0]."\">\n";
			echo "                     			<input type=\"hidden\" name=\"uid\" value=\"".md5(time())."\">\n";
			echo "								<button type=\"submit\">Click to return to Lead</button>\n";
			echo "                     		</form>\n";
		}
		
		echo "		</td>\n";
		echo "	<tr>\n";
		echo "</table>";
    }
	else
	{
		echo "<table class=\"outer\" width=\"250px\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\" align=\"center\"><h3><b>Email Sent!</b></h3></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\" align=\"center\">\n";
		echo "                     		<form method=\"POST\">\n";
		echo "                     			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "                     			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
		echo "                     			<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['etcid'][0]."\">\n";
		echo "                     			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['et_uid']."\">\n";
		echo "								<button type=\"submit\">Return to Lead</button>\n";
		echo "                     		</form>\n";
		echo "		</td>\n";
		echo "	<tr>\n";
		echo "</table>";
	}
    //echo 'OUT';
}

function SendSystemEmail($from,$erecp,$esubj,$ebody)
{
	//echo $erecp;
	$emc_ar=array(
		'to'=>		$erecp,
		'from'=>	$from,
		'fromname'=>'JMS System Admin',
		'esubject'=>trim($esubj),
		'ebody'=>	trim($ebody),
		'oid'=> 	89,
		'lid'=> 	0,
		'tid'=> 	0,
		'cid'=> 	0,
		'uid'=> 	$_SESSION['securityid'],
		'ename'=>	'',
		'chistory'=>false,
		'SMTPdbg'=>	1
	);
	
	if ($_SESSION['securityid']==2699999999999999999999999999999999999999)
	{
		display_array($emc_ar);
	}
	
	ExtEmailSendPlain($emc_ar);
}

function ScanReplaceTextData()
{
	$srch_ar=array(0=>'/CUSTOMERFULLNAME/',1=>'/CUSTOMERFIRSTNAME/',2=>'/CUSTOMERLASTNAME/',3=>'/CUSTOMEREMAIL/',4=>'/OFFICEPHONE/',5=>'/EMAILSENDER/');
    $res_ar =array(0=>$cname,1=>$cfname,2=>$clname,3=>$cemail,4=>$ophone,5=>$esender);
}

function AccessRightsError()
{
	echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Resource</b>";
	exit;
}

function display_array($ar)
{
	if (isset($_SESSION['securityid']) && $_SESSION['securityid']==26)
	{
		echo "<table><tr><td align=\"left\">\n";
		echo '<div>';
		echo '	<pre>';
		
		print_r($ar);
		
		echo '	</pre>';
		echo '</div>';
		echo "</td></tr></table>\n";
	}
}

function srep_page_link($oid,$sid)
{
	if (isset($sid) && $sid!=0)
	{
		if ($oid==$_SESSION['officeid'])
		{
			//echo "View S&C<br>";
			echo "<form method=\"post\">\n";
			echo "	<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "	<input type=\"hidden\" name=\"call\" value=\"srpage\">\n";
			echo "	<input type=\"hidden\" name=\"stg\" value=\"2\">\n";
			echo "	<input type=\"hidden\" name=\"incsecondary\" value=\"1\">\n";
			echo "	<input type=\"hidden\" name=\"showcommission\" value=\"1\">\n";
			echo "	<input type=\"hidden\" name=\"showadjust\" value=\"1\">\n";
			echo "	<input type=\"hidden\" name=\"showbonus\" value=\"1\">\n";
			echo "	<input type=\"hidden\" name=\"showloan\" value=\"1\">\n";
			echo "	<input type=\"hidden\" name=\"showdraw\" value=\"1\">\n";
			echo "	<input type=\"hidden\" name=\"showpipeline\" value=\"1\">\n";
			echo "	<input type=\"hidden\" name=\"sid\" value=\"".$sid."\">\n";
			echo "	<input class=\"transnb JMStooltip\" type=\"image\" src=\"images/table_go.png\" height=\"12\" width=\"12\" title=\"View Sales Rep Page\">\n";
			echo "</form>\n";
		}
	}
}

function newmaplink($a1,$c1,$s1,$z1)
{
	if ($a1==0)
	{
		$link	='';
	}
	else
	{
		$amp	="&";
		$base	="http://www.mapquest.com/maps/map.adp?";
		$a1v	="address=";
		$c1v	="city=";
		$s1v	="state=";
		$z1v	="zipcode=";
		$cyv	="country=";

		$aop	="<A TARGET=\"_new\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" HREF=";
		$a1p	=rtrim(preg_replace('/ /','+',$a1));
		$c1p	=rtrim(preg_replace('/ /','+',$c1));
		$s1p	=$s1;
		$z1p	=$z1;
		$cyp	="US";
		$cid	="&cid=lfmaplink";
		$acl	=">Map</A>";

		$link	=$aop.$base.$a1v.$a1p.$amp.$c1v.$c1p.$amp.$s1v.$s1p.$amp.$z1v.$z1p.$amp.$cyv.$cyp.$cid.$acl;
	}

	return $link;
}

function gen_CustLinkNode($cid,$uid,$saddr1,$scity,$sstate,$szip1)
{
	if (isset($cid) && $cid!=0)
	{
		echo "<a onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" href=\".\index.php?action=leads&call=view&cid=".$cid."&uid=".$uid."\">Lead</a>";
		echo " - <a onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" href=\".\index.php?action=leads&call=chistory&rcall=view&cid=".$cid."&uid=".$uid."\">Comments</a>";
		
		if (isset($saddr1) && strlen(trim($saddr1)) > 1)
		{
			$maplink=newmaplink($saddr1,$scity,$sstate,$szip1);
			echo " - " . $maplink;
		}
	}
}

function AddManualCommAdjust()
{
	$qry1 = "SELECT hid FROM jest..CommissionHistory WHERE ranhash='".$_REQUEST['ranhash']."';";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	if ($nrow1==0)
	{
		if (isset($_REQUEST['amt']) && $_REQUEST['amt'] != 0)
		{
			$ramt=$_REQUEST['amt'];
		}
		else
		{
			$ramt=0;
		}
		
		$qry2  = "INSERT INTO jest..CommissionHistory ";
		$qry2 .= "(drid,oid,njobid,jobid,jadd,secid,amt,trandate,descrip,cid,uid,htype,ranhash) VALUES ";
		$qry2 .= "(0,".$_REQUEST['officeid'].",'DEL','NA',0,".$_REQUEST['sid'].",".$ramt.", ";
		$qry2 .= "'".date('m/d/Y',strtotime($_REQUEST['trandate']))."','".$_REQUEST['descrip']."',0, ";
		$qry2 .= "".$_SESSION['securityid'].",'".$_REQUEST['htype']."','".$_REQUEST['ranhash']."');";
		$res2  = mssql_query($qry2);
		
		//echo $qry2.'<br>';
	}
}

function DeleteAllCommissions($oid,$jobid)
{
	$qry = "DELETE FROM jest..CommissionHistory WHERE oid=".$oid." and jobid='".$jobid."';";
	$res = mssql_query($qry);
}

function DeleteManualCommAdjust()
{
	$qry1 = "SELECT hid FROM jest..CommissionHistory WHERE hid='".$_REQUEST['hid']."';";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	if ($nrow1==1)
	{
		$qry2  = "DELETE FROM jest..CommissionHistory WHERE hid=".$_REQUEST['hid'].";";
		$res2  = mssql_query($qry2);
	}
}

function PullStoreCommissions($oid,$jobid)
{
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	
	$qry0 = "SELECT hid,cbtype FROM CommissionHistory WHERE oid=".$oid." AND jobid='".$jobid."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	/*$qry0a = "SELECT hid,cbtype FROM CommissionHistory WHERE oid=".$oid." AND jobid='".$jobid."' and cbtype=4;";
	$res0a = mssql_query($qry0a);
	$nrow0a= mssql_num_rows($res0a);

	if ($nrow0a >= 0)
	{
		while($row0a = mssql_fetch_array($res0a))
		{
			if ($row0a['cbtype']==4)
			{
				$qry0b = "UPDATE CommissionHistory set njobid='".$_REQUEST['njobid']."' FROM CommissionSchedule WHERE hid=".$row0a['hid'].";";
				$res0b = mssql_query($qry0b);
			}
		}
	}*/
	
	if ($nrow0 == 0)
	{
		try
		{
			$qry1a = "SELECT * FROM CommissionSchedule WHERE oid=".$oid." AND jobid='".$jobid."';";
			$res1a = mssql_query($qry1a);
			$nrow1a= mssql_num_rows($res1a);
			
			///echo $qry1a.'<br>';
			
			if ($nrow1a > 0)
			{
				$dr			=0;
				$phsid		=4;
				$commdata	=array();
				$qry1aA = "SELECT cid FROM jest..cinfo WHERE officeid=".$oid." AND jobid='".$jobid."';";
				$res1aA = mssql_query($qry1aA);
				$row1aA = mssql_fetch_array($res1aA);
				
				$qry1aB = "SELECT officeid,jobid,njobid,digdate,sidm FROM jobs as J WHERE officeid=".$oid." AND jobid='".$jobid."';";
				$res1aB = mssql_query($qry1aB);
				$row1aB = mssql_fetch_array($res1aB);
				$nrow1aB= mssql_num_rows($res1aB);
				
				$crate=0;
				$destxt='';
				while ($row1a = mssql_fetch_array($res1a))
				{
					if ($row1a['jadd']==0)
					{
						if ($row1a['cbtype']==0) //Manual Adjust
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRM ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'M');
						}
						elseif ($row1a['cbtype']==2) // O/U Comm
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRO ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'O');
						}
						elseif ($row1a['cbtype']==4) //Sales Manager
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SMC ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'S');
						}
						elseif ($row1a['cbtype']==6) //Bullets
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRU ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'U');
						}
						elseif ($row1a['cbtype']==8) //Override
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SOV ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'V');
						}
						elseif ($row1a['cbtype']==9) //Merit
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'STU ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'T');
						}
						else //Base Comm, etc
						{
							$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),'SRC ',$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'C');
						}
					}
					else
					{
						$commdata[]=array($dr,$oid,$row1aB['njobid'],$jobid,$row1a['jadd'],$row1a['secid'],number_format(($row1a['amt']),2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),('SRA '.$row1a['jadd']),$row1aA['cid'],$row1a['cbtype'],$row1a['rate'],$row1a['type'],'N');
					}
				}
				
				/*// Sales Manager Commission Data, If any
				$qry1b = "SELECT * FROM jdetail as J WHERE officeid=".$oid." AND jobid='".$jobid."' AND jadd=(select MAX(jadd) from jdetail where officeid=J.officeid and jobid=J.jobid and post_add=0);";
				$res1b = mssql_query($qry1b);
				$row1b = mssql_fetch_array($res1b);
				$nrow1b= mssql_num_rows($res1b);
				
				//Sales Rep Commission Line
				//$commdata[]=array($dr,$row1a['officeid'],$row1a['njobid'],$row1a['jobid'],$row1b['jadd'],$row1a['securityid'],number_format(($row1a['comm'] + $row1a['ovcommission']),2,'.',''),date('m/d/Y',strtotime($row1a['digdate'])),'SR Comm',$row1aA['cid']);
				
				if ($nrow1b > 0)
				{
					$dojt =explode(',',$row1b['costdata_l']);
					$pdojt=explode(',',$row1b['pcostdata_l']);
					
					foreach ($dojt as $don => $dov)
					{
						$dijt=explode(':',$dov);
						
						$ddesc='';
						if ($dijt[8]==$phsid)
						{
							//Sales Manager Commission Line, If any
							$ddesc='SMC';
							$commdata[]=array($dr,$row1b['officeid'],$row1b['njobid'],$jobid,$row1b['jadd'],$row1aB['sidm'],number_format($dijt[3],2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),$ddesc,$row1aA['cid'],4,0,1,'C');
						}
					}
					
					foreach ($pdojt as $pdon => $pdov)
					{
						$pdijt=explode(':',$pdov);
						
						$pdesc='';
						if ($pdijt[8]==$phsid)
						{
							//Sales Manager Commission Line, If any
							$pdesc='SMC';
							$commdata[]=array($dr,$row1b['officeid'],$row1b['njobid'],$jobid,$row1b['jadd'],$row1aB['sidm'],number_format($pdijt[3],2,'.',''),date('m/d/Y',strtotime($row1aB['digdate'])),$pdesc,$row1aA['cid'],4,0,1,'C');
						}
					}
				}*/
			}
			else
			{
				throw new Exception('Error: Job Not Found');
			}
	
			if (count($commdata) > 0)
			{
				/*echo '<pre>';
				print_r($commdata);
				echo '</pre>';*/
				
				$pc=0;
				//echo 'Processing....<br />';
				foreach ($commdata as $cn => $cv)
				{
					$qry2 = "INSERT INTO CommissionHistory (drid,oid,njobid,jobid,jadd,secid,amt,trandate,descrip,cid,cbtype,rate,ratetype,htype,uid) VALUES ($cv[0],$cv[1],'".$cv[2]."','".$cv[3]."',$cv[4],$cv[5],$cv[6],'".$cv[7]."','".$cv[8]."',$cv[9],$cv[10],$cv[11],$cv[12],'".$cv[13]."',".$_SESSION['securityid'].");";
					$res2 = mssql_query($qry2);
					
					//echo $qry2.'<br>';
					//echo $qry1b.'<br>';
					$pc++;
				}
				
				if ($pc!=count($commdata))
				{
					throw new Exception('Error: Data Process Miscount');
				}
				/*else
				{
					throw new Exception($pc.' Commissions Added');
				}*/
			}
		}
		catch (Exception $e)
		{
			echo 'Output: '.$e->getMessage();
		}
	}
	else
	{
		echo $nrow0.' Commissions Exist. New Commissions not Added<br>';
	}
}

function PullandStoreSingleCommission($o,$j)
{
	//echo 'Entering...';
	try
    {
		$qry1a = "SELECT officeid,njobid,jobid,securityid,sidm,custid,digdate,comm,ovcommission FROM jobs WHERE officeid=".$o." AND jobid='".$j."';";
		$res1a = mssql_query($qry1a);
		$row1a = mssql_fetch_array($res1a);
		$nrow1a= mssql_num_rows($res1a);
		
		if ($nrow1a > 0)
		{
			$dr=0;
			$phsid=4;
			$qry1aA = "SELECT cid FROM jest..cinfo WHERE officeid=".$row1a['officeid']." AND jobid='".$row1a['jobid']."';";
			$res1aA = mssql_query($qry1aA);
			$row1aA = mssql_fetch_array($res1aA);
			
			$qry1b = "SELECT * FROM jdetail as J WHERE officeid=".$row1a['officeid']." AND jobid='".$row1a['jobid']."' AND jadd=(select MAX(jadd) from jdetail where officeid=J.officeid and jobid=J.jobid and post_add=0);";
			$res1b = mssql_query($qry1b);
			$row1b = mssql_fetch_array($res1b);
			$nrow1b= mssql_num_rows($res1b);
			
			//Sales Rep Commission Line
			$commdata[]=array(
								$dr,
								$row1a['officeid'],
								$row1a['njobid'],
								$row1a['jobid'],
								$row1b['jadd'],
								$row1a['securityid'],
								number_format(($row1a['comm'] + $row1a['ovcommission']),2,'.',''),
								date('m/d/Y',strtotime($row1a['digdate'])),
								'SRC',
								$row1aA['cid'],
								'C',
								1,
								1
							);
			
			if ($nrow1b > 0)
			{
				$dojt =explode(',',$row1b['costdata_l']);
				$pdojt=explode(',',$row1b['pcostdata_l']);
				
				
				foreach ($dojt as $don => $dov)
				{
					$dijt=explode(':',$dov);
					
					if ($dijt[8]==$phsid)
					{
						//Sales Manager Commission Line, If any
						$commdata[]=array(
											$dr,
											$row1a['officeid'],
											$row1a['njobid'],
											$row1a['jobid'],
											$row1b['jadd'],
											$row1a['sidm'],
											number_format($dijt[3],2,'.',''),
											date('m/d/Y',strtotime($row1a['digdate'])),
											'SMC',
											$row1aA['cid'],
											'C',
											4,
											1
										);
					}
				}
				
				foreach ($pdojt as $pdon => $pdov)
				{
					$pdijt=explode(':',$pdov);
					
					if ($pdijt[8]==$phsid)
					{
						//Sales Manager Commission Line, If any
						$commdata[]=array(
											$dr,
											$row1a['officeid'],
											$row1a['njobid'],
											$row1a['jobid'],
											$row1b['jadd'],
											$row1a['sidm'],
											number_format($pdijt[3],2,'.',''),
											date('m/d/Y',strtotime($row1a['digdate'])),
											'SMC',
											$row1aA['cid'],
											'C',
											4,
											1
										);
					}
				}
			}
		}
		else
		{
			throw new Exception('Error: Job Not Found');
		}

		if (count($commdata) > 0)
		{
			$pc=0;
			//echo 'Processing....<br />';
			foreach ($commdata as $cn => $cv)
			{
				$qry2 = "INSERT INTO CommissionHistory (drid,oid,njobid,jobid,jadd,secid,amt,trandate,descrip,cid,uid,htype,cbtype,ratetype) VALUES ($cv[0],$cv[1],'".$cv[2]."','".$cv[3]."',".$cv[4].",".$cv[5].",".$cv[6].",'".$cv[7]."','".$cv[8]."',".$cv[9].",".$_SESSION['securityid'].",'".$cv[10]."',".$cv[11].",".$cv[12].");";
				$res2 = mssql_query($qry2);
				
				//echo $qry2.'<br>';
				//echo $qry1b.'<br>';
				$pc++;
			}
			
			if ($pc!=count($commdata))
			{
				throw new Exception('Error: Data Process Miscount');
			}
			/*else
			{
				throw new Exception($pc.' Commissions Added');
			}*/
		}
	}
    catch (Exception $e)
    {
        echo 'Output: '.$e->getMessage();
    }
}

function DeleteSingleCommission($h)
{
	try
    {
		$qry1a = "SELECT hid FROM CommissionHistory WHERE hid='".$h."';";
		$res1a = mssql_query($qry1a);
		$nrow1a= mssql_num_rows($res1a);
		
		if ($nrow1a > 0)
		{
			$pc=0;
			while ($row1a = mssql_fetch_array($res1a))
			{
				$qry1a = "DELETE FROM CommissionHistory WHERE hid='".$row1a['hid']."';";
				$res1a = mssql_query($qry1a);
				$pc++;
			}
			
			echo $pc.' Commission(s) Removed';
		}
		else
		{
			throw new Exception('No Commissions Found');
		}
	}
    catch (Exception $e)
    {
        echo 'Error: '.$e->getMessage();
    }
}

function DeleteSingleCommissionHistory($h)
{
	try
    {
		$qry1a = "SELECT hid FROM CommissionHistory WHERE hid='".$h."';";
		$res1a = mssql_query($qry1a);
		$nrow1a= mssql_num_rows($res1a);
		
		if ($nrow1a > 0)
		{
			$pc=0;
			while ($row1a = mssql_fetch_array($res1a))
			{
				$qry1a = "DELETE FROM CommissionHistory WHERE hid='".$row1a['hid']."';";
				$res1a = mssql_query($qry1a);
				$pc++;
			}
			
			echo $pc.' Commission(s) Removed';
		}
		else
		{
			throw new Exception('No Commissions Found');
		}
	}
    catch (Exception $e)
    {
        echo 'Error: '.$e->getMessage();
    }
}

function PullandStoreCommissions($dr)
{
	try
    {
		$qry0 = "SELECT id,officeid,no_digs,no_rens,no_addn,jtext FROM digreport_main WHERE id=".$dr.";";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		$nrow0= mssql_num_rows($res0);
	
		//echo $qry0.'<br />';
		
		if ($nrow0 > 0)
		{
			if ($row0['no_digs'] > 0 || $row0['no_rens'] > 0 || $row0['no_addn'] > 0)
			{
				$commdata=array();
				$phsid=4;
				$ojt=explode(',',$row0['jtext']);
				
				foreach ($ojt as $on => $ov)
				{
					$ijt=explode(':',$ov);

					$qry1a = "SELECT officeid,njobid,jobid,securityid,sidm,custid,digdate FROM jobs WHERE officeid=".$row0['officeid']." AND njobid='".$ijt[12]."';";
					$res1a = mssql_query($qry1a);
					$row1a = mssql_fetch_array($res1a);
					$nrow1a= mssql_num_rows($res1a);
					
					if ($nrow1a > 0)
					{
						$qry1aA = "SELECT cid FROM jest..cinfo WHERE officeid=".$row1a['officeid']." AND jobid='".$row1a['jobid']."';";
						$res1aA = mssql_query($qry1aA);
						$row1aA = mssql_fetch_array($res1aA);
						
						$qry1b = "SELECT * FROM jdetail AS J WHERE officeid=".$row0['officeid']." AND jobid='".$row1a['jobid']."' and jadd=(select MAX(jadd) from jdetail where officeid=J.officeid and jobid=J.jobid and post_add=0);";
						$res1b = mssql_query($qry1b);
						$row1b = mssql_fetch_array($res1b);
						$nrow1b= mssql_num_rows($res1b);
						
						$commdata[]=array($dr,$row0['officeid'],$row1a['njobid'],$row1b['jobid'],$row1b['jadd'],$row1a['securityid'],number_format($ijt[18],2,'.',''),date('m/d/Y',strtotime($row1a['digdate'])),'SR Comm',$row1aA['cid']);
						
						if ($nrow1b > 0)
						{
							$dojt =explode(',',$row1b['costdata_l']);
							$pdojt=explode(',',$row1b['pcostdata_l']);
							
							foreach ($dojt as $don => $dov)
							{
								$dijt=explode(':',$dov);
								
								if ($dijt[8]==$phsid)
								{
									$commdata[]=array($dr,$row0['officeid'],$row1a['njobid'],$row1b['jobid'],$row1b['jadd'],$row1a['sidm'],number_format($dijt[3],2,'.',''),date('m/d/Y',strtotime($row1a['digdate'])),'SM Comm',$row1aA['cid']);
								}
							}
							
							foreach ($pdojt as $pdon => $pdov)
							{
								$pdijt=explode(':',$pdov);
								
								if ($pdijt[8]==$phsid)
								{
									$commdata[]=array($drs,$row0['officeid'],$row1a['njobid'],$row1b['jobid'],$row1b['jadd'],$row1a['sidm'],number_format($pdijt[3],2,'.',''),date('m/d/Y',strtotime($row1a['digdate'])),'SM Comm',$row1aA['cid']);
								}
							}
						}
					}
				}

				if (count($commdata) > 0)
				{
					$pc=0;
					echo 'Processing: ';
					foreach ($commdata as $cn => $cv)
					{
						$qry2 = "INSERT INTO CommissionHistory (drid,oid,njobid,jobid,jadd,secid,amt,trandate,descrip,cid,uid) VALUES ($cv[0],$cv[1],'".$cv[2]."','".$cv[3]."',$cv[4],$cv[5],$cv[6],'".$cv[7]."','".$cv[8]."',$cv[9],".$_SESSION['securityid'].");";
						$res2 = mssql_query($qry2);
						
						//echo $qry2.'<br>';
						$pc++;
					}
					
					if ($pc!=count($commdata))
					{
						throw new Exception('Data Process Miscount');
					}
					else
					{
						throw new Exception($pc .' Completed<br>');
					}
				}
			}
			else
			{
				throw new Exception('No Digs/Rens/Addns');
			}
		}
		else
		{
			throw new Exception('No DIG Report Found');
		}
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
}

function valid_email_addr($s)
{
	//echo $s."<br>";
	//$p = "/^[-+\\.0-9=a-z_]+@([-0-9a-z]+\\.)+([0-9a-z]){2,4}$/i";
	$p='/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i';
	
	if (preg_match($p,$s))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function display_Lead_Search_Ajax()
{
	?>

	<script type="text/javascript" src="js/jquery_lead_search_ajx.js?<?php echo time(); ?>"></script>
	<table class="transnb" align="center" width="950px">
		<tr>
			<td class="transnb" align="left">
				<div id="AP_CB_menu">
					<ul>
						<li><a href="#ap"><span>Appointments</span></a></li>
						<li><a href="#cb"><span>Callbacks</span></a></li>
						<li><a href="#er"><span>Email Response</span></a></li>
						<li><a href="#nm"><span>New from BHNM</span></a></li>
					</ul>
	
					<div id="ap">
						<p>
							<div id="ResultsAP">
								<div id="LoadingContentAP"><img class="system_busy" src="images/mozilla_blu.gif"> Retrieving...</div>
							</div>
						</p>
					</div>
				
					<div id="cb">
						<p>
							<div id="ResultsCB">
								<div id="LoadingContentCB"><img class="system_busy" src="images/mozilla_blu.gif"> Retrieving...</div>
							</div>
						</p>
					</div>
					
					<div id="er">
						<p>
							<div id="ResultsER">
								<div id="LoadingContentER"><img class="system_busy" src="images/mozilla_blu.gif"> Retrieving...</div>
							</div>
						</p>
					</div>
					
					<div id="nm">
						<p>
							<div id="ResultsNM">
								<div id="LoadingContentNM"><img class="system_busy" src="images/mozilla_blu.gif"> Retrieving...</div>
							</div>
						</p>
					</div>
				</div>
			</td>
		</tr>
	</table>
	
	<?php
}

function set_sdate()
{
	if ($_SESSION['securityid']!=269999999999999999999999999999999999999999999999999999)
	{
		
		$rtime	=time();
		$pdate	=getdate();		
		$dcnt	=86400;
		
		/*
		if ($_SESSION['securityid']==26)
		{
			echo '<pre>';
			print_r(getdate());
			echo '</pre>';
		}
		*/
		
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
	else
	{
		$tshift =$_SESSION['timeshift'];
		
		$out	=array(date("m/d/Y",(time() + $tshift)).' 00:00:00',date("m/d/Y g:i A",(time() + $tshift)));	
		return $out;
	}
}

function set_sdate_new()
{
	$rtime	=time();	
	$tshift =$_SESSION['timeshift'];
	$pdate	=getdate();
	
	/*
	if ($pdate['weekday']==='Monday')
	{
		$stime=time() - 345600;
	}
	elseif ($pdate['weekday']==='Saturday')
	{
		$stime=time() - 172800;
	}
	elseif ($pdate['weekday']==='Sunday')
	{
		$stime=time() - 259200;
	}
	else
	{
		$stime=time() - 86400;
	}
	*/
	
	$out	=array(date("m/d/Y",(time() + $tshift)).' 00:00:00',date("m/d/Y g:i A",(time() + $tshift)));

	return $out;
}

function set_wdate()
{
	$out	=array(date("m/d/Y",strtotime("last Sunday")),date("m/d/Y g:i A",time()));
	return $out;
}

function SetPriorDateRange($NumberOfDays,$EndTime,$r)
{
	$TotalSeconds=86400;
	
	if ($NumberOfDays==0)
	{
		$StartTime=time();
	}
	else
	{
		$StartTime=time() - ($TotalSeconds * $NumberOfDays);
	}

	$out	=array(date("m/d/Y",$StartTime),date("m/d/Y g:i A",$EndTime));
	return $out;
}

function SetWeekDateRange()
{
	$out=array();
	$objDate=getdate();
	$WeekDay=$objDate['wday'];
	//$WeekDay=0;
	
	$out=SetPriorDateRange($WeekDay,time());
	
	/*
	if ($WeekDay==='Monday')
	{
		$out=SetPriorDateRange(1,time());
	}
	elseif ($WeekDay==='Tuesday')
	{
		$out=SetPriorDateRange(2,time());
	}
	elseif ($WeekDay==='Wednesday')
	{
		$out=SetPriorDateRange(3,time());
	}
	elseif ($WeekDay==='Thursday')
	{
		$out=SetPriorDateRange(4,time());
	}
	elseif ($WeekDay==='Friday')
	{
		$out=SetPriorDateRange(5,time());
	}
	elseif ($WeekDay==='Saturday')
	{
		$out=SetPriorDateRange(6,time());
	}
	elseif ($WeekDay==='Sunday')
	{
		$out=SetPriorDateRange(6,time());
	}
	*/
	
	echo date("m/d/Y g:i A",strtotime("last Sunday")), "\n";

	
	return $out;
}

function lead_report_daily_admin()
{
	if ($_SESSION['officeid']==89)
	{

		$sdate	 =set_sdate();
		$icnt	 =0;
		$mcnt	 =0;
		
		$qry0  	 = "SELECT ";
		$qry0	.= "	DISTINCT(C.officeid), ";
		$qry0	.= "	O.name, ";
		$qry0	.= "	(SELECT COUNT(cid) FROM cinfo as C2 WHERE C2.officeid=O.officeid and C2.source in (select statusid from leadstatuscodes where provided=1) and C2.added >= '".$sdate[0]."') as lcnt ";
		$qry0	.= "FROM ";
		$qry0	.= "	cinfo as C ";
		$qry0	.= "INNER JOIN ";
		$qry0	.= "	offices as O ";
		$qry0	.= "ON ";
		$qry0	.= "	C.officeid=O.officeid ";
		$qry0	.= "WHERE ";
		$qry0	.= "	C.added >= '".$sdate[0]."' ";
		$qry0	.= "	and (O.[grouping] = 0 or O.[grouping] = 4) ";
		$qry0	.= "ORDER BY ";
		$qry0	.= "	O.name ASC;";
		$res0	= mssql_query($qry0);
		$nrow0	= mssql_num_rows($res0);
		
		if ($_SESSION['securityid']==269999999999999999999999999)
		{
			echo $qry0.'<br>';
		}
		
		$qry1	 = "SELECT ";
		$qry1	.= "	DISTINCT(c.officeid), ";
		$qry1	.= "	o.name, ";
		$qry1	.= "	(SELECT COUNT(cid) FROM cinfo WHERE officeid=c.officeid and added >= '".$sdate[0]."' and source=c.source and dupe!=1) as ccnt ";
		$qry1	.= "FROM ";
		$qry1	.= "	cinfo AS c ";
		$qry1	.= "INNER JOIN ";
		$qry1	.= "	offices AS o ";
		$qry1	.= "ON ";
		$qry1	.= "	c.officeid=o.officeid ";
		$qry1	.= "WHERE ";
		$qry1	.= "	c.added >= '".$sdate[0]."' and c.source!=0 and c.source!=44 and c.source!=85 and c.source!=193 ";
		$qry1	.= "	and (o.[grouping] = 0 or o.[grouping] = 4) ";
		$qry1	.= "ORDER BY ";
		$qry1	.= "	o.name ASC;";
		$res1	= mssql_query($qry1);
		$nrow1	= mssql_num_rows($res1);
		
		
		if ($_SESSION['securityid']==269999999999999999999999999999)
		{
			
			echo $qry1.'<br>';
		}
		
		//echo "<script language=\"Javascript\" type=\"text/javascript\" src=\"js/lead_activity_qtips.js\"></script>\n";
		echo "<table align=\"center\" width=\"100%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" align=\"center\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>Enterprise Lead Activity</b></td>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>".$sdate[0]." - ".$sdate[1]."</b></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr class=\"tblhd\">\n";
		echo "					<td align=\"center\" colspan=\"3\"><b>BHNM - Provided Leads</b></td>\n";
		echo "				</tr>\n";
	
		if ($nrow0 > 0)
		{
			$acnt=1;
			while ($row0= mssql_fetch_array($res0))
			{
				if ($row0['lcnt']!=0)
				{
					$acnt++;
					
					if ($acnt%2)
					{
						$tbg='white';
					}
					else
					{
						$tbg='ltgray';
					}
					
					echo "			<tr class=\"".$tbg."\">\n";
					echo "				<td align=\"right\">".$row0['name']."</td>\n";
					echo "				<td align=\"center\" width=\"30px\">".$row0['lcnt']."</td>\n";
					echo "				<td align=\"left\" width=\"20px\">\n";
					echo "         		<form method=\"post\">\n";
					echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
					echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
					echo "						<input type=\"hidden\" name=\"noffid\" value=\"".$row0['officeid']."\">\n";
					echo "						<input type=\"hidden\" name=\"shownm\" value=\"1\">\n";
					echo "						<input class=\"provided_lead_fwd\"  type=\"image\" src=\"images/search.gif\" height=\"10\" width=\"10\" alt=\"View Leads\">\n";
					echo "         		</form>\n";					
					echo "				</td>\n";
					echo "			</tr>\n";
					$icnt=$icnt+$row0['lcnt'];
				}
			}
			
			if ($icnt!=0)
			{
				echo "				<tr>\n";
				echo "					<td class=\"gray\" align=\"right\"><b>Total</b></td>\n";
				echo "					<td class=\"gray\" align=\"center\">".$icnt."</td>\n";
				echo "					<td class=\"gray\" align=\"right\">\n";
				echo "						<img src=\"images/pixel.gif\">\n";
				echo "					</td>\n";
				echo "				</tr>\n";
			}
		}
		else
		{
			echo "			<tr>\n";
			echo "				<td class=\"gray\" align=\"center\" width=\"100%\" colspn=\"3\"><b>No bluehaven.com Leads for this Time Period</b></td>\n";
			echo "			</tr>\n";
		}
		
		echo "			</table>\n";	
		echo "		</td>\n";
		echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
		echo "			<table class=\"outer\" cellpadding=\"2\" width=\"100%\">\n";
		echo "				<tr class=\"tblhd\">\n";
		echo "					<td align=\"center\" colspan=\"2\">\n";
		echo "						<b>Manual Leads</b>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
			
		if ($nrow1 > 0)
		{
			$bcnt=1;
			while ($row1= mssql_fetch_array($res1))
			{
				$bcnt++;
				
				if ($bcnt%2)
				{
					$tbgb='white';
				}
				else
				{
					$tbgb='ltgray';
				}
				
				
				echo "			<tr class=\"".$tbgb."\">\n";
				echo "				<td align=\"right\">".$row1['name']."</td>\n";
				echo "				<td align=\"center\" width=\"30px\">".$row1['ccnt']."</td>\n";
				echo "			</tr>\n";
				$mcnt=$mcnt+$row1['ccnt'];
			}
			
			if ($mcnt!=0)
			{
				echo "				<tr>\n";
				echo "					<td class=\"gray\" align=\"right\"><b>Total</b></td>\n";
				echo "					<td class=\"gray\" align=\"center\">".$mcnt."</td>\n";
				echo "				</tr>\n";
			}
		}
		else
		{
			echo "			<tr>\n";
			echo "				<td class=\"gray\" colspan=\"2\" align=\"center\" width=\"100%\"><b>No Lead Entries for this Time Period</b></td>\n";
			echo "			</tr>\n";
		}
		
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function lead_report_daily_office()
{
	$sdate=set_sdate();

	//$qry0  = "SELECT ";
	//$qry0	.= "	DISTINCT(L.tooffice), ";
	//$qry0	.= "	O.name, ";
	//$qry0	.= "	(SELECT COUNT(lid) FROM lead_inc as L2 WHERE tooffice=O.officeid and L2.added >= '".$sdate[0]."') as lcnt ";
	//$qry0	.= "FROM ";
	//$qry0	.= "	lead_inc as L ";
	//$qry0	.= "INNER JOIN ";
	//$qry0	.= "	offices as O ";
	//$qry0	.= "ON ";
	//$qry0	.= "	L.tooffice=O.officeid ";
	//$qry0	.= "WHERE ";
	//$qry0	.= "	L.added >= '".$sdate[0]."' and ";
	//$qry0	.= "	O.officeid = '".$_SESSION['officeid']."' ";
	////$qry0	.= "	c.source in (select statusid from leadstatuscodes where provided=0) ";
	//$qry0	.= "ORDER BY ";
	//$qry0	.= "	O.name ASC;";
	
	$qry0  	 = "SELECT ";
	$qry0	.= "	DISTINCT(C.officeid), ";
	$qry0	.= "	O.name, ";
	$qry0	.= "	(SELECT COUNT(cid) FROM cinfo as C2 WHERE C2.officeid=O.officeid and C2.source in (select statusid from leadstatuscodes where provided=1) and C2.added >= '".$sdate[0]."') as lcnt ";
	$qry0	.= "FROM ";
	$qry0	.= "	cinfo as C ";
	$qry0	.= "INNER JOIN ";
	$qry0	.= "	offices as O ";
	$qry0	.= "ON ";
	$qry0	.= "	C.officeid=O.officeid ";
	$qry0	.= "WHERE ";
	$qry0	.= "	C.added >= '".$sdate[0]."' AND ";
	$qry0	.= "	O.officeid = '".$_SESSION['officeid']."' ";
	$qry0	.= "ORDER BY ";
	$qry0	.= "	O.name ASC;";
	
	$res0	 = mssql_query($qry0);
	$nrow0 = mssql_num_rows($res0);
	
	$qry1	 = "SELECT ";
	$qry1	.= "	DISTINCT(c.source),  ";
	$qry1	.= "	l.name,  ";
	$qry1	.= "	(SELECT COUNT(cid) FROM cinfo WHERE officeid=c.officeid AND source=c.source AND added >= '".$sdate[0]."') as ccnt  ";
	$qry1	.= "FROM  ";
	$qry1	.= "	cinfo AS c  ";
	$qry1	.= "INNER JOIN  ";
	$qry1	.= "	leadstatuscodes AS l  ";
	$qry1	.= "ON  ";
	$qry1	.= "	c.source=l.statusid  ";
	$qry1	.= "WHERE  ";
	$qry1	.= "	c.added >= '".$sdate[0]."' and  ";
	$qry1	.= "	c.officeid='".$_SESSION['officeid']."' and ";
	$qry1	.= "	c.source not in (select statusid from leadstatuscodes where provided=1) ";
	/*
	$qry1	.= "	c.source!=0 and ";
	$qry1	.= "	c.source!=44 and ";
	$qry1	.= "	c.source!=85 and ";
	$qry1	.= "	c.source!=193 and ";
	$qry1	.= "	c.source!=1 ";
	*/
	$qry1	.= "ORDER BY  ";
	$qry1	.= "	l.name ASC;	 ";
	
	$res1	 = mssql_query($qry1);
	$nrow1 = mssql_num_rows($res1);
	
	if ($_SESSION['securityid']==2699999999999999)
	{
		//echo $qry0.'<br>';
		echo $qry1.'<br>';
		//echo date('w',time()).'<br>';
	}
	
	echo "<table align=\"center\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\" align=\"center\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>Lead Activity</b></td>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>".$sdate[0]." - ".$sdate[1]."</b></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr class=\"tblhd\">\n";
	echo "					<td align=\"center\" colspan=\"3\"><b>BHNM - Provided Leads</b></td>\n";
	echo "				</tr>\n";

	if ($nrow0 > 0)
	{
		$acnt=1;
		while ($row0= mssql_fetch_array($res0))
		{
			$acnt++;
			if ($acnt%2)
			{
				$tbg='white';
			}
			else
			{
				$tbg='ltgray';
			}
			
			echo "			<tr class=\"".$tbg."\">\n";
			echo "				<td align=\"right\">".$row0['name']."</td>\n";
			echo "				<td align=\"center\" width=\"40px\"> ".$row0['lcnt']."</td>\n";
			echo "				<td align=\"center\" width=\"20px\">\n";
			
			if ($row0['lcnt'] > 0)
			{
				echo "         		<form method=\"post\">\n";
				echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
				echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
				echo "						<input type=\"hidden\" name=\"shownm\" value=\"1\">\n";
				echo "						<input class=\"provided_lead_fwd\" id=\"recid\" type=\"image\" src=\"images/search.gif\" height=\"10\" width=\"10\" alt=\"View Leads\">\n";
				echo "         		</form>\n";
			}
			else
			{
				echo "			<img src=\"images/pixel.gif\">\n";
			}
			
			echo "				</td>\n";
			echo "			</tr>\n";
		}
	}
	else
	{
		echo "			<tr>\n";
		echo "				<td class=\"gray\" align=\"center\" colspan=\"3\" width=\"100%\"><b>None</b></td>\n";
		echo "			</tr>\n";
	}
	
	echo "			</table>\n";	
	echo "		</td>\n";
	echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr class=\"tblhd\">\n";
	echo "					<td align=\"center\" colspan=\"2\"><b>Manual Leads</b></td>\n";
	echo "				</tr>\n";
	
	if ($nrow1 > 0)
	{
		$bcnt=1;
		while ($row1= mssql_fetch_array($res1))
		{
			$bcnt++;
			if ($bcnt%2)
			{
				$tbgb='white';
			}
			else
			{
				$tbgb='ltgray';
			}
			
			echo "			<tr class=\"".$tbgb."\">\n";
			echo "				<td align=\"right\" width=\"50%\">".$row1['name']."</td>\n";
			echo "				<td align=\"center\" width=\"50%\">".$row1['ccnt']."</td>\n";
			echo "			</tr>\n";
		}
	}
	else
	{
		echo "			<tr>\n";
		echo "				<td class=\"gray\" align=\"center\" width=\"100%\"><b>None</b></td>\n";
		echo "			</tr>\n";
	}
	
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function pbvalperiod()
{
	global 	$pbvalidate;
	$valar	= array(1,0,0,0);
	$cdate	= date("m/d/Y",time());

	$qry0	= "SELECT * FROM pbval_conf WHERE prdb <='".$cdate."' AND prde>='".$cdate."';";
	$res0	= mssql_query($qry0);
	$row0	= mssql_fetch_array($res0);

	$qry1	= "SELECT * FROM pbvalidate WHERE offid='".$_SESSION['officeid']."' AND valdate between '".$row0['prdb']."' AND '".$row0['prde']."';";
	$res1	= mssql_query($qry1);
	$row1	= mssql_fetch_array($res1);
	$nrow1	= mssql_num_rows($res1);

	if ($nrow1 > 0)
	{
		$valar		=array(0,$row0['prd'],$row0['prdb'],$row0['prde']);
	}
	else
	{
		$valar		=array(1,$row0['prd'],$row0['prdb'],$row0['prde']);
	}

	return 	$valar;
}

function drvalperiod()
{
	$critd		= 05;
	$currmdate	= date("m",time());
	$currtdate	= date("t",time());
	$currddate	= date("d",time());
	$drarray	= array();
	
	if ($currmdate == 01)
	{
		$currydate	= date("Y",time()) - 1;
	}
	else
	{
		$currydate	= date("Y",time());
	}

	if ($currmdate == 01)
	{
		$prevmdate	= 12;
	}
	else
	{
		$prevmdate	= $currmdate - 1;
	}

	$qry1	= "SELECT brept_yr FROM [jest]..[bonus_schedule_config] WHERE active=1;";
	$res1	= mssql_query($qry1);
	$row1	= mssql_fetch_array($res1);
	$nrow1	= mssql_num_rows($res1);

	$qry	= "SELECT name,endigreport as ndrt FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);

	if ($nrow1==1 and $row['ndrt']==1)
	{
		$qry0	= "SELECT rept_mo FROM digreport_main WHERE officeid=".$_SESSION['officeid']." and brept_yr=".$currydate.";";
		$res0	= mssql_query($qry0);
		$nrow0	= mssql_num_rows($res0);
		
		//if ($nrow0 )
		while ($row0	= mssql_fetch_array($res0))
		{
			$drarray[]	=$row0['rept_mo'];
		}

		if ($_SESSION['securityid']==269999999999999999)
		{
			echo $qry0.'<br>';
			echo $nrow0.'<br>';
			display_array($drarray);
			echo $prevmdate.'<br>';
			echo $currydate.'<br>';
		}
		
		if (count($drarray) > 0)
		{
			if ($prevmdate!=11)
			{
				sort($drarray);
				if ($currddate > $critd && !in_array($prevmdate,$drarray))
				{
					echo "
						<div id=\"overlay1\">
							<table><tr><td>
							<font size=\"10\" color=\"red\">!! Attention !!</font>
							</td></tr>
							<tr><td>
							<font size=\"10\"  color=\"red\">Dig Report for ".date("F Y",strtotime($prevmdate."/1/".$currydate))." is due</font>
							</td></tr>
							<tr><td>
							Contact Management if you need assistance submitting a Dig Report<br>619-233-3522 x10111
							</td></tr></table>
						</div>
						";
				}
			}
		}
		else
		{
			echo "
				<div id=\"overlay1\">
					<table><tr><td>
					<font size=\"10\" color=\"red\">!! Attention !!</font>
					</td></tr>
					<tr><td>
					<font size=\"10\" color=\"red\">Dig Reports are missing for ".$currydate."</font>
					</td></tr>
					<tr><td>
					Contact Management if you need assistance submitting a Dig Report or correcting this error<br>619-233-3522 x10111
					</td></tr></table>
				</div>
				";
		}
	}
}

function passchgeval()
{
	$qry0	= "SELECT passchg,loginbypass FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res0	= mssql_query($qry0);
	$row0	= mssql_fetch_array($res0);
	$nrow0	= mssql_num_rows($res0);

	if ($row0['loginbypass']==0)
	{
		//$_SESSION['elev'],$_SESSION['clev'],$_SESSION['jlev'],$_SESSION['llev'],$_SESSION['rlev'],$_SESSION['mlev'],$_SESSION['tlev']
	
		//$passmsg="";
		$ptime	=time()-strtotime($row0['passchg']);
		$pwindow	= 3888000; // 45 Days
		$cwindow	= 1209600; // 14 Days
		//$pwindow	= 86000; // ~1 Day
	
		//echo "PERI: ".$ptime."<br>";
		//echo "CURR: ".time()."<br>";
		//echo "WIND: ".$pwindow."<br>";
		
		if ($nrow0 == 1)
		{
			//echo strtotime('09/01/2004')."<br>";
			//echo strtotime($row0['passchg'])."<br>";
			if (strtotime($row0['passchg']) < strtotime('09/01/2004'))
			{
				echo "
									<div class=\"droverlay\" id=\"overlay1\">
										<font color=\"red\"><strong>!! Attention !!</strong></font><br>
										<font color=\"red\">Please change your password.</font><br>
										Go to Maintenance -> Options to change your password<br>
									</div>
					";
			}
			else
			{
				if ($ptime >= $pwindow)
				{
					/*
					echo "
									<div class=\"droverlay\" id=\"overlay1\">
										<font color=\"red\"><strong>!! Attention !!</strong></font><br>
										<font color=\"red\">Your Password has not changed since ".date("m/d/Y",strtotime($row0['passchg']))."</font><br>
										<a href=\"./index.php?action=maint&call=users&subq=rp&userid=". $_SESSION['securityid'] ."\">Go to Maintenance -> Options</a> to change your password<br>
									</div>
					";
					*/
					
					echo "
									<div class=\"droverlay\" id=\"overlay1\">
										<font color=\"red\"><strong>!! Attention !!</strong></font><br>
										<font color=\"red\">Your Password has not changed since ".date("m/d/Y",strtotime($row0['passchg']))."</font><br>
										Go to Maintenance -> Options to change your password<br>
									</div>
					";
				}
			}
		}
	}
}

function popup_msg($var1)
{
	if (isset($var1))
	{
		echo "<a href=\"help.php?msgid=".$var1."\" target=\"winName\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=400,toolbar=no,menubar=no,location=no,directories=no'); return true;\">Test</a>\n";
	}
}

function findcursor()
{
	$qry3 = "exec sp_cursor_list 1,1;";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
}


function get_secids()
{
	$secids=array();
	$qry = "SELECT securityid,lname,fname,mas_div FROM security WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);

	while ($row = mssql_fetch_array($res))
	{
		$secids[]=array($row['securityid'],$row['lname'],$row['fname']);
	}

	return $secids;
}

function tst_vals($item1,$key,$ndata)
{
	global $t_chg_ar;
	$d2_ar=$ndata;
	
	//print_r($ndata);
	//echo "<br>";
	$in1_ar = explode(":",$item1);
	foreach ($d2_ar as $n1 => $v1)
	{
		$in2_ar=explode(":",$v1);
		if ($in1_ar[0]==$in2_ar[0])
		{
			if ($in1_ar[2]!=$in2_ar[2]) //Quan Diff
			{
				$qu_res	=$in2_ar[2]-$in1_ar[2];
				$ppr_res	=$in2_ar[3]-$in1_ar[3];

				if ($ppr_res==0)
				{
					$pr_res	=$in2_ar[3];
				}
				else
				{
					$pr_res	=$in2_ar[3];
					//$pr_res	=$in2_ar[3]*$qu_res;
				}

				$cm_res	=$pr_res*$in2_ar[6];
				$cal_quan=$qu_res;
				//$dis_quan=$qu_res; *3/5/07*
				$dis_quan=$in2_ar[2];
				$chg_val	=1;
				
				if (!empty($in2_ar[7]))
				{
					$def_val=$in2_ar[7];
				}
				else
				{
					$def_val=0;
				}
				
			}
			else
			{
				$qu_res	=0;
				$pr_res	=0;
				$cm_res	=0;
				$cal_quan=0;
				$dis_quan=0;
				$def_val=0;
				$chg_val	=1;
			}

			if ($in1_ar[2]!=$in2_ar[2])
			{
				$pr_res	=number_format($pr_res, 2, '.', '');
				$cm_res	=number_format($cm_res, 2, '.', '');
				$chg_ar	=$in2_ar[0].":".$in2_ar[1].":".$cal_quan.":".$pr_res.":".$in2_ar[4].":".$in2_ar[5].":".$in2_ar[6].":".$dis_quan.":".$def_val.":".$chg_val.",";

				/*
				echo "$in1_ar[0].$in2_ar[0]<br/>";
				echo "IN1PR: ".$in1_ar[3]."<br>";
				echo "IN2PR: ".$in2_ar[3]."<br>";
				echo "Quant Diff: ".$qu_res."<br>";
				echo "Price Diff: ".$pr_res."<br>";
				echo "Comm Diff: ".$cm_res."<br>";
				echo "String: ".$chg_ar."<br>";
				echo "------------------<br>";
				*/
				$t_chg_ar=$t_chg_ar.$chg_ar;
			}
		}
	}
}

function getmasjobinfo($jobid)
{
    //error_reporting(E_ALL);
    $tempj		=	array(0=>0,1=>0);

    //if ($_SESSION['securityid']!=26)
    //{
	/*
	$odbc_ser="67.154.183.30"; #the name of the SQL Server
	$odbc_add="67.154.183.30";
	$odbc_db="ZE_Stats"; #the name of the database
	$odbc_user="jestuser"; #a valid username
	$odbc_pass="bhestuser"; #a password for the username
	*/
	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"ZE_Stats"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username
	
	$odbc_conn0=odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
    
	$odbc_qry0 = "SELECT id,jobid,sstatus FROM BHESTJobData_Stats WHERE ";
	$odbc_qry0.= "officeid='".$_SESSION['officeid']."' AND jobid = '".$jobid."';"; // Remove after tests
    
	$odbc_res0=odbc_exec($odbc_conn0, $odbc_qry0);
    
	$odbc_ret1 =odbc_result($odbc_res0, 1);
	$odbc_ret2 =odbc_result($odbc_res0, 2);
	$odbc_ret3 =odbc_result($odbc_res0, 3);
    
	if ($jobid==$odbc_ret2)
	{
	    $tempj = array(0=>$odbc_ret2,1=>$odbc_ret3);
	}
    //}

    return $tempj;
}

function initRand()
{
	static $randCalled = FALSE;
	if (!$randCalled)
	{
		srand((double) microtime() * 1234567);
		$randCalled = TRUE;
	}
}

function randNum($low, $high)
{
	initRand();
	$rNum = rand($low, $high);
	return $rNum;
}

function filter_diffs(&$a1,&$a2)
{
	$r_add	=array();
	$r_del	=array();
	$r_cng	=array();
	$rr_del	=array();

	foreach ($a1 as $pl) // Add Construct
	{
		if (! in_array($pl, $a2, true) )
		{
			$r_add[] = $pl;
		}
	}

	foreach ($a2 as $pl) // Del Construct
	{
		if (! in_array($pl, $a1, true) && ! in_array($pl, $r_add, true) )
		{
			$r_del[] = $pl;
		}
	}

	$adjtypeflag	=0;
	$adjpriceflag	=0;
	$adjquanflag	=0;

	foreach ($r_add as $n1a=>$pla) // Diff Construct
	{
		$ri_add=explode(":",$pla);
		foreach ($r_del as $n1d=>$pld)
		{
			$ri_del=explode(":",$pld);
			if ($ri_del[0]==$ri_add[0])
			{
				if ($ri_del[1]==$ri_add[1])
				{
					if ($ri_add[6]!=$ri_del[6])
					{
						$n_adjtype	=$ri_add[6];
						$adjtypeflag++;
					}
					else
					{
						$n_adjtype	=$ri_del[6];
					}

					if ($ri_add[7]!=$ri_del[7])
					{
						$n_adjprice	=$ri_add[7]-$ri_del[7];
						$adjpriceflag++;
					}
					else
					{
						$n_adjprice	=$ri_add[7];
					}

					if ($ri_add[8]!=$ri_del[8])
					{
						$n_adjquan	=$ri_add[8]-$ri_del[8];
						$adjquanflag++;
					}
					else
					{
						$n_adjquan	=$ri_add[8];
					}

					if ($adjtypeflag > 0 ||	$adjpriceflag > 0 ||$adjquanflag > 0)
					{
						$r_cng[]=$ri_add[0].":".$ri_add[1].":".$ri_add[2].":".$ri_add[3].":".$ri_add[4].":".$ri_add[5].":".$n_adjtype.":".$n_adjprice.":".$n_adjquan;

						unset($r_add[$n1a]);
						unset($r_del[$n1d]);
					}
				}
			}
			$adjtypeflag	=0;
			$adjpriceflag	=0;
			$adjquanflag	=0;
		}
	}

	foreach ($r_del as $n2d=>$p2d)
	{
		$r2_del=explode(":",$p2d);
		$rr_del[]=$r2_del[0].":".$r2_del[1].":".$r2_del[2].":".$r2_del[3].":".$r2_del[4].":".$r2_del[5].":".$r2_del[6].":".$r2_del[7].":".$r2_del[8]*-1;
	}

	/*
	echo "<pre>";
	echo "ADDS: <br>";
	array2table($r_add);
	echo "DELS: <br>";
	array2table($r_del);
	echo "RDELS: <br>";
	array2table($rr_del);
	echo "CNGH: <br>";
	array2table($r_cng);
	echo "</pre>";
	*/

	$r_out=array(0=>$r_add,1=>$rr_del,2=>$r_cng);
	return $r_out;
}

function multi_unique($array)
{
	foreach ($array as $k=>$na)
	$new[$k] = serialize($na);
	$uniq = array_unique($new);
	foreach($uniq as $k=>$ser)
	$new1[$k] = unserialize($ser);
	return ($new1);
}

function array2table($array, $recursive = false, $return = false, $null = '&nbsp;')
{
	// Sanity check
	if (empty($array) || !is_array($array)) {
		return false;
	}

	if (!isset($array[0]) || !is_array($array[0])) {
		$array = array($array);
	}

	// Start the table
	$table = "<table border=1>\n";

	// The header
	$table .= "\t<tr>";
	// Take the keys from the first row as the headings
	foreach (array_keys($array[0]) as $heading) {
		$table .= '<th>' . $heading . '</th>';
	}
	$table .= "</tr>\n";

	// The body
	foreach ($array as $row) {
		$table .= "\t<tr>" ;
		foreach ($row as $cell) {
			$table .= '<td>';

			// Cast objects
			if (is_object($cell)) { $cell = (array) $cell; }

			if ($recursive === true && is_array($cell) && !empty($cell)) {
				// Recursive mode
				$table .= "\n" . array2table($cell, true, true) . "\n";
			} else {
				$table .= (strlen($cell) > 0) ?
				htmlspecialchars((string) $cell) :
				$null;
			}

			$table .= '</td>';
		}

		$table .= "</tr>\n";
	}

	// End the table
	$table .= '</table>';

	// Method of output
	if ($return === false) {
		echo $table;
	} else {
		return $table;
	}
}

function valid_date($strDate)
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

function dateoutofrange($strDate,$dr)
{
	$isValid 	= false;
	$secseed	= 86400 * $dr; // day seed
	$ctime		= strtotime(date("m/d/Y",time()));
	$ptime		= strtotime($strDate);
	$ftime		= $ptime - $ctime;

	if ($ftime > $secseed)
	{
		$isValid = true;
	}
	return $isValid;
}

function mail_out($to,$sub,$mess)
{
	ini_set('SMTP','192.168.1.17');
	ini_set('sendmail_from','jmsadmin@bluehaven.com');
	ini_set('sendmail_path','d:\tools\sendmail\sendmail.exe -t');

	$qry	= "SELECT ADMIN_ADDR FROM [jest]..[jest_config];";
	$res	= mssql_query($qry);
	$row 	= mssql_fetch_array($res);

	$to		=	$to;
	$head	=	"From: JMS Mail System <".$row['ADMIN_ADDR'].">\r\n" .
	"Reply-To: ".$row['ADMIN_ADDR']."\r\n" .
	"X-Mailer: PHP/" . phpversion();

	mail($to,$sub,$mess,$head);
}

function ExtEmailSendSSL($emc)
{
    require_once('phpmail/class.phpmailer.php');
    //include('phpmail/class.smtp.php'); // optional, gets called from within class.phpmailer.php if not already loaded
	
	//$msgid=time().'.'.$emc['cid'].'.'.$emc['tid'].'.'.$emc['uid'].'@jms.bhnmi.com';
	$msgid= md5(uniqid()).'@jms.bhnmi.com';
    
    $mail			= new PHPMailer();
    $mail->IsSMTP(); 							// telling the class to use SMTP
    $mail->SMTPDebug= $emc['SMTPdbg'];      	// enables SMTP debug information (for testing)
                                                // 1 = errors and messages
                                                // 2 = messages only
    $mail->SMTPAuth	= true;                  	// enable SMTP authentication
    $mail->SMTPSecure= "ssl";                // sets the prefix to the servier
    $mail->Host		= $emc['ehost'];      		// sets GMAIL as the SMTP server
    $mail->Port		= $emc['eport'];           	// set the SMTP port for the GMAIL server
	
	$mail->Username	= $emc['efrom'];  	   		// GMAIL username
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
	
	if (isset($emc['ishtml']) && $emc['ishtml'] == 1) {
		$mail->isHTML(true);
	}
	
	$mail->MessageID= $msgid;
	
	if (isset($_SESSION['securityid']) and $_SESSION['securityid']==269999999999999)
	{
		print_r($emc);
	}
    
    if(!$mail->Send())
    {
	    if (is_array($emc['to']))
		{
			$xito='';
			foreach ($emc['to'] as $vx)
			{
				$xito=$xito.':'.$vx;
			}
		}
		else
		{
			$xito=$emc['to'];
		}
		
		//echo 'Error';
	    $qry	 = "insert into jest..EmailTracking (oid,lid,tid,cid,uid,sdate,emailaddr,emailaddrfrom,errors,msgid,efile) values ";
	    $qry	.= "(".$emc['oid'].",".$emc['lid'].",".$emc['tid'].",".$emc['cid'].",".$emc['uid'].",getdate(),'".$xito."','".$emc['from']."','".$mail->ErrorInfo."','".$mail->MessageID."',".$emc['docid'].");";
	    $res	 = mssql_query($qry);
		
		return false;
    }
    else
    {
		if (is_array($emc['to']))
		{
			$xito='';
			foreach ($emc['to'] as $vx)
			{
				$xito=$xito.':'.$vx;
			}
		}
		else
		{
			$xito=$emc['to'];
		}
		
		$qry	 = "insert into jest..EmailTracking (oid,lid,tid,cid,uid,sdate,emailaddr,emailaddrfrom,apptmnt,callback,msgid,efile) values ";
		$qry	.= "(".$emc['oid'].",".$emc['lid'].",".$emc['tid'].",".$emc['cid'].",".$emc['uid'].",getdate(),'".$xito."','".$emc['from']."','".$emc['appt']."','".$emc['callb']."','".$mail->MessageID."',".$emc['docid'].");";
		$res	 = mssql_query($qry);
		
		if ($emc['chistory'])
		{
			$xet_uid=(isset($_REQUEST['et_uid'])) ? $_REQUEST['et_uid']:'';
			$imtext=$emc['ename'].' Email Sent to '.$xito;
			
			$qry1  = "INSERT INTO chistory (officeid,secid,custid,act,tranid,mtext,complaint,followup,resolved,relatedcomplaint,cservice) ";
			$qry1 .= "VALUES ";
			$qry1 .= "('".$emc['oid']."','".$emc['uid']."','".$emc['cid']."','leads','".$xet_uid."','".$imtext."',0,0,0,0,0);";
			$res1  = mssql_query($qry1);
			
			if (isset($emc['bme']) and strlen($emc['bme']) > 4) {
				$bmetext='Email Message: '.$emc['bme'];
				$qry1a  = "INSERT INTO chistory (officeid,secid,custid,act,tranid,mtext,complaint,followup,resolved,relatedcomplaint,cservice) VALUES ";
				$qry1a .= "('".$emc['oid']."','".$emc['uid']."','".$emc['cid']."','leads','".$xet_uid."','".$bmetext."',0,0,0,0,0);";
				$res1a  = mssql_query($qry1a);
			}
			
			$qry2 = "UPDATE jest..cinfo SET updated=getdate() WHERE cid = ".$emc['cid'].";";
			$res2 = mssql_query($qry2);
		}
		
		return true;
    }
}

function ExtEmailSendPlain($emc)
{
    require_once('phpmail/class.phpmailer.php');

	$msgid= md5(uniqid()).'@jms.bhnmi.com';
    
	//print_r($emc);
	
    $mail				= new PHPMailer();
    $mail->IsSMTP(); 							// telling the class to use SMTP
    $mail->SMTPDebug	= $emc['SMTPdbg'];      // enables SMTP debug information (for testing)
												// 1 = errors and messages
												// 2 = messages only
    $mail->SMTPAuth		= false;				// enable SMTP authentication
    $mail->Host			= "192.168.1.17";		// sets ZE_EMX01 as the SMTP server
    $mail->Port			= 25;                   // set the SMTP port for the ZE_EMX01 server
	$mail->From			= $emc['from'];
	
	if (isset($emc['FromName']))
	{
		$mail->FromName		= $emc['FromName'];	
	}
	else
	{
		$mail->FromName		= 'JMS Admin';
	}
	
    $mail->Subject		= $emc['esubject'];
    $mail->Body			= $emc['ebody'];
	
	foreach ($emc['to'] as $n1=>$v1)
	{
		$mail->AddAddress($v1);
	}
	
	$mail->MessageID	= $msgid;
	$mail->Send();
}

function ExtEmailSendNoLog($emc)
{
    require_once('phpmail/class.phpmailer.php');

	$msgid= md5(uniqid()).'@jms.bhnmi.com';
    
	//print_r($emc);
	
    $mail				= new PHPMailer();
    $mail->IsSMTP(); 							// telling the class to use SMTP
    $mail->SMTPDebug	= $emc['SMTPdbg'];      // enables SMTP debug information (for testing)
												// 1 = errors and messages
												// 2 = messages only
    $mail->SMTPAuth	= true;                  	// enable SMTP authentication
    $mail->SMTPSecure= "ssl";                // sets the prefix to the servier
    $mail->Host		= $emc['ehost'];      		// sets GMAIL as the SMTP server
    $mail->Port		= $emc['eport'];           	// set the SMTP port for the GMAIL server
	
	$mail->Username	= $emc['efrom'];  	   		// GMAIL username
	$mail->Password	= $emc['epswd'];            // GMAIL password

    $mail->From		= $emc['from'];
    $mail->FromName	= $emc['fromname'];
	
    $mail->Subject		= $emc['esubject'];
    $mail->Body			= $emc['ebody'];
	
	foreach ($emc['to'] as $n1=>$v1)
	{
		$mail->AddAddress($v1);
	}
	
	$mail->MessageID	= $msgid;
	$mail->Send();
}

function checklastleadimport()
{
	$qry1 = "SELECT top 1 added FROM cinfo WHERE source=0 and added is not null order by added desc;";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry1a = "SELECT top 1 added FROM lead_inc WHERE added is not null order by added desc;";
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);

	echo "Last Lead <i>Submitted</i>: <b>".date('m/d/y g:i A',strtotime($row1['added']))."</b> ";
	echo "<i>Processed</i>: <b>".date('m/d/y g:i A',strtotime($row1a['added']))."</b>";
}

function deletesessionid($sessionid)
{
	$orgpath = getcwd();
	chdir(session_save_path());
	$path = realpath(getcwd()).'\\';

	if(file_exists($path.'sess_'.$sessionid))
	{
		unlink($path.'sess_'.$sessionid);
		session_unset();
	}
	else
	{
	}
	chdir($orgpath);
}

function apply_overage()
{
	if ($_SESSION['action']=="est")
	{
		$qry = "UPDATE est SET applyov='1',comadj='".$_REQUEST['comadj']."',comm='".$_REQUEST['comm']."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res = mssql_query($qry);
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qry = "UPDATE jobs SET applyov='1',ovcommission='".$_REQUEST['comadj']."',comm='".$_REQUEST['comm']."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
		$res = mssql_query($qry);
	}

	if ($_SESSION['action']=="est")
	{
		viewest_retail();
	}
	elseif ($_SESSION['action']=="contract")
	{
		view_job_retail();
	}
}

function delete_overage()
{
	if ($_SESSION['action']=="est")
	{
		$qry = "UPDATE est SET applyov='0',comadj='0.00' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res = mssql_query($qry);
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qry = "UPDATE jobs SET applyov='0',ovcommission='0.00' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
		$res = mssql_query($qry);
	}

	if ($_SESSION['action']=="est")
	{
		viewest_retail();
	}
	elseif ($_SESSION['action']=="contract")
	{
		view_job_retail();
	}
}

function apply_bullet()
{
	$qry = "UPDATE jobs SET applybu='1',bucommission='".$_REQUEST['buladj']."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	$res = mssql_query($qry);

	view_job_retail();
}

function delete_bullet()
{
	$qry = "UPDATE jobs SET applybu='0',bucommission='0.00' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	$res = mssql_query($qry);

	view_job_retail();
}

function show_session_info()
{
	echo "   <tr>\n";
	echo "      <td align=\"left\">\n";
	echo "_SESSION: <br>";
	echo "      <pre>\n";

	print_r($_SESSION);
	//echo $_SESSION['aid'];
	echo "      </pre>\n";
	echo "_POST: <br>";
	echo "<pre>";

	print_r($_POST);

	echo "      </pre>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
}

function stored_package_items($rid,$filters)
{
	//echo "Stored Package Items: <br>";
	//echo $filters."<br>";
	global $rctotal,$viewarray;
	$MAS=$_SESSION['pb_code'];

	$qry = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$edata=explode(",",$filters);
	foreach ($edata as $en1 => $ev1)
	{
		//echo $ev1."<br>";
		$idata=explode(":",$ev1);
		//echo $idata[0]."<br>";
		if ($idata[0]==$rid)
		{
			$qry1 = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$idata[1]."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);

			$qry2 = "SELECT * FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row1['catid']."';";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);

			$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
			$res3 = mssql_query($qry3);
			$row3 = mssql_fetch_array($res3);

			$adjquan=package_quan_set($idata[3],$row1['quan_calc'],$idata[8],$viewarray['ps1'],$viewarray['ps2'],$viewarray['tzone'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7'],$viewarray['spa1'],$viewarray['spa2'],$viewarray['spa3'],$viewarray['deck']);
			$adjamt=$idata[7];

			if ($idata[6]==1) // Adjusts
			{
				$adjquan=$row1['quan_calc']+$idata[8];
				$adjamt=$row1['rp']+$idata[7];
			}
			elseif ($idata[6]==2) // Price Percent Adjust
			{
				$adjamt=($row1['rp']*$idata[7])*$adjquan;
			}
			elseif ($idata[6]==3)
			{
				$adjquan=$row1['quan_calc']+$idata[8];
			}
			elseif ($idata[6]==4) // Zero Price
			{
				$adjamt=($row1['rp']+($row1['rp'] * -1))*$idata[8];
			}
			elseif ($idata[6]==5)
			{
				$adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
			}
			elseif ($idata[6]==6)
			{
				$adjamt=($idata[2]+($idata[2] * -1))*$idata[8];
				$adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
			}

			$fadjamt=number_format($adjamt, 2, '.', '');

			echo "                  <tr>\n";
			echo "                     <td class=\"wh\" valign=\"bottom\" align=\"left\"></td>\n";
			echo "                     <td class=\"wh\" valign=\"top\" align=\"left\">\n";
			echo "								<table align=\"left\" width=\"100%\" border=0>\n";
			echo "								   <tr>\n";
			echo "								   	<td align=\"left\">".$row1['item']."</td>\n";
			echo "								   	<td align=\"right\">(".$row['item'].")</td>\n";
			echo "								   </tr>\n";
			echo "								</table>\n";
			echo "							</td>\n";
			echo "                     <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"30\">".$adjquan."</td>\n";
			echo "                     <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"30\">".$row3['abrv']."</td>\n";
			echo "                     <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"60\">".$fadjamt."</td>\n";
			echo "                     <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"60\"><img src=\"images/pixel.gif\"></td>\n";
			echo "                     <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"60\"><img src=\"images/pixel.gif\"></td>\n";
			echo "                  </tr>\n";
			$rctotal=$rctotal+$adjamt;
		}
	}
}

function detect_package($estdata)
{
	// This function will detect any package with a positive commission
	$MAS=$_SESSION['pb_code'];
	$x=0;
	
	if ($estdata!='') {
		$edata=explode(",",$estdata);
		
		if (is_array($edata)) {
			foreach($edata as $n1 => $v1) {
				$subedata=explode(":",$v1);
				
				if (is_array($subedata)) {
					$qryA = "SELECT qtype,crate,pdetect FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$subedata[0]."';";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_array($resA);
			
					if ($rowA['qtype']==55||$rowA['qtype']==72) {
						if (!is_float($rowA['crate']) && $rowA['crate'] > 0 && $rowA['pdetect']==1) {
							$x++;
						}
					}
				}
			}
		}
	}
	
	return $x;
}

function package_items($rid,$estdata)
{
   global $rctotal,$viewarray;
   
   $MAS=$_SESSION['pb_code'];

   $qry = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
   $res = mssql_query($qry);
   $row = mssql_fetch_array($res);

   $qry0 = "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rid."';";
   $res0 = mssql_query($qry0);
   $nrow0= mssql_num_rows($res0);

   if ($nrow0 > 0)
   {
      while ($row0 = mssql_fetch_array($res0))
      {
        $qry1 = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row0['iid']."';";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);

        $qry2 = "SELECT * FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row1['catid']."';";
        $res2 = mssql_query($qry2);
        $row2 = mssql_fetch_array($res2);

        $qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
        $res3 = mssql_query($qry3);
        $row3 = mssql_fetch_array($res3);

        $adjquan=package_quan_set($row1['qtype'],$row1['quan_calc'],$row0['adjquan'],$viewarray['ps1'],$viewarray['ps2'],$viewarray['tzone'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7'],$viewarray['spa1'],$viewarray['spa2'],$viewarray['spa3'],$viewarray['deck']);
        $adjamt=$row0['adjamt'];

        if ($row0['adjtype']==1) // Adjusts
        {
           $adjquan=$row1['quan_calc']+$row0['adjquan'];
           $adjamt=$row1['rp']+$row0['adjamt'];
        }
        elseif ($row0['adjtype']==2) // Price Percent Adjust
        {
           $adjamt=($row1['rp']*$row0['adjamt'])*$adjquan;
        }
        elseif ($row0['adjtype']==3)
        {
           $adjquan=$row1['quan_calc']+$row0['adjquan'];
        }
        elseif ($row0['adjtype']==4) // Zero Price
        {
           $adjamt=($row1['rp']+($row1['rp'] * -1))*$row0['adjquan'];
        }
        elseif ($row0['adjtype']==5)
        {
           $adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
        }
        elseif ($row0['adjtype']==6)
        {
           $adjamt=($row1['rp']+($row1['rp'] * -1))*$row0['adjquan'];
           $adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
        }

        $fadjamt=number_format($adjamt, 2, '.', '');

        echo "                  <tr>\n";
        echo "                     <td class=\"wh\" align=\"right\" width=\"90\">Pkg Item</td>\n";
        echo "                     <td class=\"wh\" valign=\"top\" align=\"left\">\n";
        echo "                        <table align=\"left\" width=\"100%\" border=0>\n";
        echo "                           <tr>\n";
        echo "                              <td class=\"transbackfill\" align=\"center\" width=\"40\"></td>\n";
        echo "                              <td align=\"left\">".$row1['item']."</td>\n";
        echo "                           </tr>\n";
        echo "                        </table>\n";
        echo "                     </td>\n";
        echo "                     <td class=\"wh\" align=\"center\" width=\"30\">".$adjquan."</td>\n";
        echo "                     <td class=\"wh\" align=\"center\" width=\"30\">".$row3['abrv']."</td>\n";
        echo "                     <td class=\"wh\" align=\"right\" width=\"65\">\n";
        
        if (isset($viewarray['esttype']) && $viewarray['esttype']=='Q')
        {
            echo "                  <input type=\"hidden\" name=\"acc_pb_src[".$row0['iid']."][0]\" value=\"".$fadjamt."\">\n";
            echo "                  <input class=\"bboxnobr\" type=\"text\" name=\"acc_pb_src[".$row0['iid']."][1]\" value=\"".$fadjamt."\" size=\"7\">\n";
        }
        else
        {
            echo $fadjamt;
        }
        
        echo "                     </td>\n";
        
        if (isset($viewarray['esttype']) && $viewarray['esttype']=='E')
        {
            //echo "                     <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
        }
        
        echo "                     <td class=\"wh\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
        echo "                     <td class=\"wh\" align=\"right\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
        echo "                  </tr>\n";
        $rctotal=$rctotal+$adjamt;
      }
   }
}

function package_quan_set($qtype,$quan,$adjquan,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck)
{
	global $viewarray;

	$quan_out=0;
	$ia=calc_internal_area($pft,$sqft,$shal,$mid,$deep);
	$gl=calc_gallons($pft,$sqft,$shal,$mid,$deep);

	if ($qtype==1||$qtype==38) // Fixed - Nocharge Fixed
	{
		$quan_out=1;
	}
	elseif ($qtype==2||$qtype==39||$qtype==55||$qtype==58||$qtype==72) // Quantity - Nocharge Quantity
	{
		$quan_out=$adjquan;
	}
	elseif ($qtype==3||$qtype==34) // PFT - No Charge PFT
	{
		$quan_out=$pft;
	}
	elseif ($qtype==4||$qtype==35) // SQFT - No Charge SQFT
	{
		$quan_out=$sqft;
	}
	elseif ($qtype==8) // Base+ (Fixed)
	{
		$quan_out=$quan;
	}
	elseif ($qtype==5||$qtype==41) // Base+ (PFT) - NO Charge
	{
		$quan_out=$pft;
	}
	elseif ($qtype==6||$qtype==42) // Base+ (SQFT) - No Charge
	{
		$quan_out=$sqft;
	}
	elseif ($qtype==7||$qtype==43) // Base+ (IA) - No Charge
	{
		$quan_out=$ia;
	}
	elseif ($qtype==13) // Checkbox (PFT)
	{
		$quan_out=1;
	}
	elseif ($qtype==14) // Checkbox (SQFT)
	{
		$quan_out=1;
	}
	elseif ($qtype==15) // Checkbox (Quantity)
	{
		$quan_out=1;
	}
	elseif ($qtype==16||$qtype==36||$qtype==56) // Checkbox (IA) - No Charge
	{
		$quan_out=$ia;
	}
	elseif ($qtype==17||$qtype==37||$qtype==57) // Checkbox (Gallons) - No Charge
	{
		$quan_out=$gl;
	}
	elseif ($qtype==18) // Code (PFT)
	{
		$quan_out=$pft;
	}
	elseif ($qtype==19) // Code (SQFT)
	{
		$quan_out=$sqft;
	}
	elseif ($qtype==20||$qtype==40) // Code (Quantity) - No Charge
	{
		$quan_out=$quan;
	}
	elseif ($qtype==21) // Code (IA)
	{
		$quan_out=$ia;
	}
	elseif ($qtype==22) // Code (Gallons)
	{
		$quan_out=$gl;
	}
	elseif ($qtype==23) // Code (Checkbox)
	{
		$quan_out=1;
	}
	elseif ($qtype==33) // Bid Item
	{
		$quan_out=1;
	}
	elseif ($qtype==45) // Deck calc
	{
		$deckar=deckcalc($pft,$deck);
		$quan_out=round($deckar[1],0);
	}
	elseif ($qtype==47) // IA (Mult by)
	{
		$quan_out=$ia;
	}
	elseif ($qtype==48) // Base Inclusion
	{
		$quan_out=$adjquan;
	}
	elseif ($qtype==49) // Base Inclusion (Deck)
	{
		$deckar=deckcalc($pft,$deck);
		$quan_out=round($deckar[0],0);
	}
	elseif ($qtype==50) // Base Inclusion (PFT)
	{
		$quan_out=$pft;
	}
	elseif ($qtype==51) // Base Inclusion (SQFT)
	{
		$quan_out=$sqft;
	}
	elseif ($qtype==52) // Base Inclusion (IA)
	{
		$quan_out=$ia;
	}
	elseif ($qtype==54) // Referral
	{
		$quan_out=$adjquan;
	}

	return $quan_out;
}

function aidbuilder($acc,$area)
{
	//echo "aidbuilder: <BR>";
	$aidarray=$_SESSION['securityid'].",";
	$qry0 = "SELECT securityid,slevel,sidm FROM security WHERE officeid='".$_SESSION['officeid']."';";
	$res0 = mssql_query($qry0);

	// System Variables placerholder

	if ($area=="s")
	{
		$gmgr=0;
		$smgr=0;
	}
	elseif ($area=="e")
	{
		$gmgr=6;
		$smgr=5;
	}
	elseif ($area=="c")
	{
		$gmgr=6;
		$smgr=5;
	}
	elseif ($area=="j")
	{
		$gmgr=6;
		$smgr=5;
	}
	elseif ($area=="l")
	{
		$gmgr=5;
		$smgr=4;
	}
	elseif ($area=="r")
	{
		$gmgr=5;
		$smgr=4;
	}
	elseif ($area=="m")
	{
		$gmgr=5;
		$smgr=4;
	}
	else
	{
		$gmgr=0;
		$smgr=0;
	}

	//echo $gmgr;
	//echo $smgr;

	while ($row0 = mssql_fetch_array($res0))
	{
		if ($acc >= $gmgr)
		{
			//echo "ACC: ".$acc.":".$gmgr."<BR>";
			$aid=$row0['securityid'].",";
			$aidarray=$aidarray.$aid;
		}
		elseif ($acc >= $smgr && $_SESSION['securityid']==$row0['sidm'])
		{
			//echo "SET grt4<BR>";
			//echo "SET :".$_SESSION['securityid'].":".$row0['sidm']."<br>";
			$aid=$row0['securityid'].",";
			$aidarray=$aidarray.$aid;
		}
	}

	$qry1 = "SELECT securityid FROM security WHERE assistant='".$_SESSION['securityid']."';";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);

	if ($nrow1 > 0)
	{
		while ($row1 = mssql_fetch_array($res1))
		{
			$aid=$row1['securityid'].",";
			$aidarray=$aidarray.$aid;

			$qry2 = "SELECT securityid FROM security WHERE sidm='".$row1['securityid']."';";
			$res2 = mssql_query($qry2);
			$nrow2= mssql_num_rows($res2);

			if ($nrow2 > 0)
			{
				while ($row2 = mssql_fetch_array($res2))
				{
					$aid=$row2['securityid'].",";
					$aidarray=$aidarray.$aid;
				}
			}
		}
	}

	$aidarray=preg_replace("/,\Z/","",$aidarray);
	return($aidarray);
}

function fixcinfo()
{
	$qry1 = "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' order by cid;";
	$res1 = mssql_query($qry1);
	$nrow1 = mssql_num_rows($res1);

	if ($nrow1 > 0)
	{
		while ($row1 = mssql_fetch_array($res1))
		{
			$qry2 = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row1['cid']."';";
			$res2 = mssql_query($qry2);
			$nrow2 = mssql_num_rows($res2);

			if ($nrow2==1)
			{
				$qry3 = "UPDATE cinfo SET estid='".$row1['estid']."',jobid='".$row1['jobid']."' WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row1['cid']."';";
				$res3 = mssql_query($qry3);
				//$row3 = mssql_num_rows($res2);
				echo "Fixed!<br>";
			}
		}
	}
}

function splitonspace($data)
{
	//echo $data."<br>";
	$u_data=preg_split("/ +/",$data);
	//print_r($u_data)."<br>";
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

function replacecomma($data)
{
	$out=preg_replace("/,/","",$data);
	$out=preg_replace("/:/","",$data);
	return $out;
}

function removecomma($data)
{
	$out=preg_replace("/,/","",$data);
	return $out;
}

function removequote($data)
{
	$qs=array("/'/","/''/");
	$rp='';
	//$out=preg_replace("/'/","",$data);
	$out=preg_replace($qs,$rp,$data);
	return $out;
}

function replaceamp($data)
{
	$out=preg_replace("/&/","and",$data);
	return $out;
}

function set_office_NEW()
{
	$qry0 = "SELECT label_masoff_code,name FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry0a = "SELECT officelist FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);

	if ($_SESSION['tlev'] == 7)
	{
		$offids=array();
		$qry1 = "SELECT oid FROM alt_security_levels WHERE sid=".$_SESSION['securityid'].";";
		$res1 = mssql_query($qry1);
		$nrow1= mssql_num_rows($res1);
		
		if ($nrow1 > 0)
		{
			while($row1 = mssql_fetch_array($res1))
			{
				$offids[]=$row1['oid'];
			}
		}
	}

	$qry = "
		select 
			O.officeid,
		";
	
	if (isset($row0a['officelist']) && $row0a['officelist']=='N')
	{
		$qry .= "(O.label_masoff_code + ' ' + O.name) as name ";
	}
	else
	{
		$qry .= "O.name ";
	}
	
	$qry .= "
		from 
			offices as O
		inner join
			officegroupcodes as G
		on
			G.code=O.[grouping]
		where 
			O.active=1
		order by G.seqn asc,O.name asc;
	";

	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($_SESSION['securityid']==26999999999)
	{
		echo $qry.'<br>';
	}

	if ($nrow > 0)
	{
		//echo "<table align=\"center\" border=0>\n";
		//echo "	<tr>\n";
		//echo "   	<td class=\"gray\" align=\"right\">\n";
		echo "			<form method=\"post\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"update_off\">\n";
		echo "   		<select class=\"JMStooltip\" name=\"noffid\" onChange=\"this.form.submit();\" title=\"Change Office by selecting from this menu\">\n";
	
		while ($row = mssql_fetch_array($res))
		{
			if ($_SESSION['tlev'] >= 8)
			{
				if ($_SESSION['officeid']==$row['officeid'])
				{
					echo "   		<option value=\"".$row['officeid']."\" SELECTED>".$row['name']."</option>\n";
				}
				else
				{
					echo "   		<option value=\"".$row['officeid']."\">".$row['name']."</option>\n";
				}
			}
			elseif ($_SESSION['tlev'] == 7)
			{
				if (is_array($offids) && in_array($row['officeid'],$offids))
				{
					if ($_SESSION['officeid']==$row['officeid'])
					{
						echo "   		<option value=\"".$row['officeid']."\" SELECTED>".$row['name']."</option>\n";
					}
					else
					{
						echo "   		<option value=\"".$row['officeid']."\">".$row['name']."</option>\n";
					}
				}
			}
		}
	
		echo "   		</select>\n";
		echo "   		</form>\n";
		//echo "   	</td>\n";
		//echo "	</tr>\n";
		//echo "</table>\n";
	}
}

function set_office()
{
	$qry0 = "SELECT label_masoff_code,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry0a = "SELECT officelist FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);

	if ($_SESSION['tlev'] == 7)
	{
		$offids=array();
		$qry1 = "SELECT oid FROM alt_security_levels WHERE sid=".$_SESSION['securityid'].";";
		$res1 = mssql_query($qry1);
		$nrow1= mssql_num_rows($res1);
		
		if ($nrow1 > 0)
		{
			while($row1 = mssql_fetch_array($res1))
			{
				$offids[]=$row1['oid'];
			}
		}
		/*
		//echo "SET OFFICE<br>";
		$qry1 = "SELECT altoffices FROM security WHERE securityid='".$_SESSION['securityid']."';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);

		if (!empty($row1['altoffices'])||$row1['altoffices']!=0)
		{
			if (preg_match("/,/i",$row1['altoffices']))
			{
				$offids=explode(",",$row1['altoffices']);
			}
			else
			{
				$offids=$row1['altoffices'];
			}
		}
		*/
	}

	$qry = "
		select 
			O.officeid,
		";
	
	if (isset($row0a['officelist']) && $row0a['officelist']=='N')
	{
		$qry .= "(O.label_masoff_code + ' ' + O.name) as name ";
	}
	else
	{
		$qry .= "O.name ";
	}
	
	$qry .= "
		from 
			offices as O
		inner join
			officegroupcodes as G
		on
			G.code=O.[grouping]
		where 
			O.active=1
		order by G.seqn asc,O.name asc;
	";

	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($_SESSION['securityid']==26999999999)
	{
		echo $qry.'<br>';
	}

	if ($nrow > 0)
	{
		echo "<table align=\"center\" border=0>\n";
		echo "	<form method=\"post\">\n";
		echo "	<input type=\"hidden\" name=\"action\" value=\"update_off\">\n";
		echo "	<tr>\n";
		echo "   	<td class=\"gray\" align=\"right\">\n";
		echo "   		<select class=\"JMStooltip\" name=\"noffid\" onChange=\"this.form.submit();\" title=\"Change Office by selecting from this menu\">\n";
	
		while ($row = mssql_fetch_array($res))
		{
			if ($_SESSION['tlev'] >= 8)
			{
				if ($_SESSION['officeid']==$row['officeid'])
				{
					echo "   		<option value=\"".$row['officeid']."\" SELECTED>".$row['name']."</option>\n";
				}
				else
				{
					echo "   		<option value=\"".$row['officeid']."\">".$row['name']."</option>\n";
				}
			}
			elseif ($_SESSION['tlev'] == 7)
			{
				if (is_array($offids) && in_array($row['officeid'],$offids))
				{
					if ($_SESSION['officeid']==$row['officeid'])
					{
						echo "   		<option value=\"".$row['officeid']."\" SELECTED>".$row['name']."</option>\n";
					}
					else
					{
						echo "   		<option value=\"".$row['officeid']."\">".$row['name']."</option>\n";
					}
				}
			}
		}
	
		echo "   		</select>\n";
		echo "   	</td>\n";
		echo "	</tr>\n";
		echo "   </form>\n";
		echo "</table>\n";
	}
}

function update_set_office()
{
	if ($_SESSION['tlev'] < 7)
	{
		exit;
	}
	else
	{
		$qry = "SELECT officeid,code,name,pb_code,altcode,manphsadj,otype,timeshift FROM jest..offices WHERE officeid='".$_REQUEST['noffid']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);

		$_SESSION['officeid']	=$row['officeid'];
		$_SESSION['offcode']	=$row['code'];
		$_SESSION['offname']	=$row['name'];
		$_SESSION['manphsadj']	=$row['manphsadj'];
		$_SESSION['otype']		=$row['otype'];
		$_SESSION['timeshift']	=$row['timeshift'];
        
        $qry1 = "SELECT * FROM jest..alt_security_levels WHERE oid='".$_REQUEST['noffid']."' and sid=".$_SESSION['securityid'].";";
		$res1 = mssql_query($qry1);
        $nrow1= mssql_num_rows($res1);
        
        if ($nrow1 > 0)
        {
            $row1 = mssql_fetch_array($res1);
            list($_SESSION['elev'],$_SESSION['clev'],$_SESSION['jlev'],$_SESSION['llev'],$_SESSION['rlev'],$_SESSION['mlev'],$_SESSION['tlev'])=split(",",$row1['slevel'],7);         
        }        

		if ($row['pb_code']=="0") // Sets PB Code
		{
			$_SESSION['pb_code']		="";
		}
		else
		{
			$_SESSION['pb_code']		=$row['pb_code'];
		}

		$_SESSION['aid']		   =$_SESSION['securityid'];
	}
}

function update_set_officeOLD()
{
	if ($_SESSION['tlev'] < 7)
	{
		echo "You do not have appropriate Access Rights to use this Resource";
		exit;
	}
	else
	{
		$qry = "SELECT officeid,code,name,pb_code,altcode,manphsadj FROM offices WHERE officeid='".$_REQUEST['noffid']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);

		//echo $qry."<br>";

		//$_SESSION['officeid']	=$_REQUEST['noffid'];
		$_SESSION['officeid']	=$row['officeid'];
		$_SESSION['offcode']	=$row['code'];
		$_SESSION['offname']	=$row['name'];
		$_SESSION['manphsadj']	=$row['manphsadj'];

		if ($row['pb_code']=="0") // Sets PB Code
		{
			$_SESSION['pb_code']		="";
		}
		else
		{
			$_SESSION['pb_code']		=$row['pb_code'];
		}

		$_SESSION['aid']		   =$_SESSION['securityid'];
	}
}

function set_assistant()
{
   $qry = "SELECT s.officeid,s.securityid,s.fname,s.lname,(SELECT name FROM offices WHERE officeid=s.officeid) AS oname FROM security AS s WHERE s.assistant='".$_SESSION['securityid']."' AND SUBSTRING(s.slevel,13,1) = 1;";
   $res = mssql_query($qry);
   $nrow = mssql_num_rows($res);
   //echo "ASTI";
   if ($nrow > 0)
   {
		echo "<table align=\"center\" border=0>\n";
		echo "	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "	<input type=\"hidden\" name=\"set_asst\" value=\"1\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\" align=\"right\"><b>Assistant to</b></td>\n";
		echo "		<td class=\"gray\" align=\"right\">\n";
		echo "			<select name=\"chg_asst\" OnChange=\"this.form.submit();\">\n";
		echo "				<option value=\"0\">None</option>\n";

		while ($row = mssql_fetch_array($res))
		{
			if ($_SESSION['asstto']==$row['securityid'])
			{
				echo "				<option value=\"".$row['securityid']."\" SELECTED>".$row['lname'].", ".$row['fname']." - ".$row['oname']."</option>\n";
			}
			else
			{
				echo "				<option value=\"".$row['securityid']."\">".$row['lname'].", ".$row['fname']." - ".$row['oname']."</option>\n";
			}
		}

		echo "			</select>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	</form>\n";
		echo "</table>\n";
   }
}

function display_assistant_to()
{
	$qry = "SELECT s.officeid,s.securityid,s.fname,s.lname,(SELECT name FROM offices WHERE officeid=s.officeid) AS oname FROM security AS s WHERE s.securityid='".$_SESSION['asstto']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	echo "<b>Assistant to [</b> ".$row['lname'].", ".$row['fname']." - ".$row['oname']."<b> ]</b>\n";
}

function update_set_assistant()
{
	//echo "Setting AsstTo (Outer)....";
	if (isset($_REQUEST['chg_asst']) && $_REQUEST['chg_asst']!=$_SESSION['asstto']) // Sets PB Code
	{
		//echo "Setting AsstTo (Inner).... ";
		$_SESSION['asstto']		=$_REQUEST['chg_asst'];
	}
}

function events()
{
	if (isset($_SESSION['subq']))
	{
		$subq=$_SESSION['subq'];
	}
	else
	{
		$subq='';
	}

	if (isset($_SESSION['action']) and $_SESSION['action']=="leads" and isset($_REQUEST['cid']))
	{
		$subq2=$_REQUEST['cid'];;
	}
	elseif ($_SESSION['action']=="file" and isset($_REQUEST['cid']))
	{
		$subq2=$_REQUEST['cid'];
	}
	elseif (isset($_SESSION['action']) and $_SESSION['action']=="est" and isset($_SESSION['estid']) and $_SESSION['estid']!=0)
	{
		$subq2=$_SESSION['estid'];
	}
	elseif (isset($_SESSION['action']) and $_SESSION['action']=="contract" and isset($_REQUEST['jobid']) and $_REQUEST['jobid']!='0')
	{
		$subq2=$_REQUEST['jobid'];
	}
	elseif (isset($_SESSION['action']) and $_SESSION['action']=="job" and isset($_REQUEST['njobid']) and $_REQUEST['njobid']!='0')
	{
		$subq2=$_REQUEST['njobid'];
	}
	else
	{
		$subq2='';
	}

	$qry = "INSERT INTO jest_stats..events (evdescrip,status,sid,oid,ip,host,brwsr) VALUES ('".$_SESSION['action']."|".$_SESSION['call']."|".$subq."|".$subq2."','3','".$_SESSION['securityid']."','".$_SESSION['officeid']."','".getenv("REMOTE_ADDR")."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['HTTP_REFERER']."');";
	$res = mssql_query($qry);
}

function accessidlist($sa,$sl,$manager,$owner)
{
	// sa=functional area (0-4),sl=level (0-9)
	$idarray=array(0=>$manager,1=>$owner);

	$qry = "SELECT securityid,slevel,assistant FROM security WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);

	while($row= mssql_fetch_row($res))
	{
		$d1=explode(",",$row[1]);

		//if ($d1[$sa] >= $sl && $row[0]!=$_SESSION['securityid']) //Sets Local Office Managers
		if ($d1[$sa] >= $sl) //Sets Local Office Managers
		{
			$idarray[]=$row[0];
		}

		// Testing Assistant Code
		if ($row[0]==$manager && $row[2]==$_SESSION['securityid']) //Sets Assistant for the Manager
		{
			$idarray[]=$row[2];
		}

		if ($row[0]==$owner && $row[2]==$_SESSION['securityid']) //Sets Assistant for the Manager if Manager is also Owner
		{
			$idarray[]=$row[2];
		}
	}

	$qry2 = "SELECT securityid,slevel,admstaff,altoffices FROM security;";
	$res2 = mssql_query($qry2);

	while($row2= mssql_fetch_row($res2))
	{
		$d1=explode(",",$row2[1]);
		$d2=explode(",",$row2[3]);

		if ($d1[$sa] >= 7 && $row2[2]==1) // Sets Admin Staff Access (level 7 Admins and above)
		{
			$idarray[]=$row2[0];
		}
		elseif ($d1[$sa] >= $sl && in_array($_SESSION['officeid'],$d2)) // Sets Managers with Multi Site Access
		{
			$idarray[]=$row2[0];
		}
	}
	return $idarray;
}

function systemwidemessageNEW()
{
	$qry	= "SELECT officeid,admstaff,lname FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
	
	$qry1	= "SELECT endigreport,gm,am,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1	= mssql_query($qry1);
	$row1	= mssql_fetch_array($res1);
	
	$showleads	=(($_SESSION['officeid']==89 && $_SESSION['llev'] >= 5) || ($_SESSION['llev'] >= 5 || $_SESSION['securityid'] == $row1['gm'] || $_SESSION['securityid'] == $row1['am'])) ? true : false;
	$showcsr	=($_SESSION['rlev'] >= 5 || $_SESSION['csrep'] >= 5) ? true : false;

	echo "<script type=\"text/javascript\" src=\"js/jquery_index_func.js\"></script>\n";
	echo "<table border=0 width=\"950px\">\n";
	echo "   <tr>\n";
	echo "      <td width=\"100%\" valign=\"top\">\n";
	echo "			<div id=\"SystemMessageViewer\">\n";
	echo "				<ul>\n";
	
	if ($showleads)
	{
		echo "					<li><a href=\"#LeadReport\"><em>Lead Activity</em></a></li>\n";
	}
	
	if ($showcsr)
	{
		echo "					<li><a href=\"#CustServ\"><em>Customer Service</em></a></li>\n";
	}
	
	echo "					<li><a href=\"#SysAnn\"><em>System Messages</em></a></li>\n";
	echo "					<li><a href=\"#ConList\"><em>Contact List</em></a></li>\n";
	echo "				</ul>\n";
	
	if ($showleads)
	{
		echo "				<div id=\"LeadReport\"></div>\n";
	}
	
	if ($showcsr)
	{
		echo "				<div id=\"CustServ\"></div>\n";
	}
	
	echo "				<div id=\"SysAnn\"></div>\n";
	echo "				<div id=\"ConList\"></div>\n";
	echo "			</div>\n";
	
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function activity_csr_office($oid)
{
	if (!isset($oid))
	{
		$oid=$_SESSION['officeid'];
	}
	
	$qry0  = "SELECT id FROM jest..view_complaints WHERE ";
	
	if ($oid!=89)
	{
		$qry0 .= "oid=".$oid." and ";
	}
	
	$qry0 .= "cservice=1 and followup=0 and resolved=0 and cres!=1";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	$qry1  = "SELECT id FROM jest..view_complaints WHERE ";
	
	if ($oid!=89)
	{
		$qry1 .= "oid=".$oid." and ";
	}
	
	$qry1 .= "complaint=1 and followup=0 and resolved=0 and cres!=1";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	echo "<table align=\"center\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>Customer Service</b></td>";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\" valign=\"top\">\n";
	echo "				<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr class=\"tblhd\">\n";
	echo "						<td colspan=\"2\" align=\"center\"><b>";
	
	if ($oid==89)
	{
		echo "Systemwide";
	}
	
	echo " Activity</b></td>\n";
	echo "					</tr>\n";
	echo "					<tr class=\"white\">\n";
	echo "						<td align=\"right\">Open Service Requests</td>\n";
	echo "						<td align=\"center\" width=\"30px\">\n";
	echo "		         		<form name=\"openSR\" method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"complaints\">\n";
	echo "							<input type=\"hidden\" name=\"subq\" value=\"stage2\">\n";
	
	if (isset($oid) && $oid!=89)
	{
		echo "							<input type=\"hidden\" name=\"oid\" value=\"".$oid."\">\n";	
	}
	
	echo "							<input type=\"hidden\" name=\"reccomplaints\" value=\"0\">\n";
	echo "							<input type=\"hidden\" name=\"status\" value=\"SO\">\n";
	//echo "							<span OnClick=\"this.form.submit();\">\n";
	
	if ($nrow0 > 0)
	{
		echo "<input class=\"buttonwhiteblue\" type=\"submit\" value=\"".$nrow0."\">\n";
		//echo "<font color=\"blue\"><b>".$nrow0."</b></font>";
	}
	else
	{
		echo "<font color=\"black\"><b>".$nrow0."</b></font>";
	}
	
	//echo "							</span>\n";
	echo "         				</form>\n";
	echo "						</td>\n";
	echo "						</tr>\n";
	echo "					<tr class=\"ltgray\">\n";
	echo "					<td align=\"right\">Open Complaints</td>\n";
	echo "					<td align=\"center\" width=\"30px\">\n";
	echo "		         		<form name=\"openCP\" method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"complaints\">\n";
	echo "							<input type=\"hidden\" name=\"subq\" value=\"stage2\">\n";
	
	if (isset($oid) && $oid!=89)
	{
		echo "							<input type=\"hidden\" name=\"oid\" value=\"".$oid."\">\n";	
	}
	
	echo "							<input type=\"hidden\" name=\"reccomplaints\" value=\"0\">\n";
	echo "							<input type=\"hidden\" name=\"status\" value=\"CO\">\n";
	
	if ($nrow1 > 0)
	{
		echo "<input class=\"buttonwhitered\" type=\"submit\" value=\"".$nrow1."\">\n";
	}
	else
	{
		echo "<font color=\"black\"><b>".$nrow1."</b></font>";
	}
	
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function contact_list()
{
    $qryC  = "
                SELECT
                    processor
                    ,(select lname from security as s1 where securityid=o.processor) as plname
                    ,(select fname from security as s2 where securityid=o.processor) as pfname
                    ,(select (select name from offices where officeid=s3.officeid) from security as s3 where securityid=o.processor) as poname
                    ,(select (select phone from offices where officeid=ss3.officeid) from security as ss3 where securityid=o.processor) as pphone
					,(select phone from security as ps10 where securityid=o.processor) as psphone
					,(select ext from security as ps11 where securityid=o.processor) as psext
                    ,finan_from
                    ,(select lname from security as s4 where securityid=o.finan_rep) as flname
                    ,(select fname from security as s5 where securityid=o.finan_rep) as ffname
                    ,(select name from offices where officeid=o.finan_from) as foname
                    ,(select phone from offices where officeid=o.finan_from) as fphone
                    ,am
					,(select substring(slevel,13,1) from security as s6 where securityid=o.am) as lactive
                    ,(select lname from security as s7 where securityid=o.am) as llname
                    ,(select fname from security as s8 where securityid=o.am) as lfname
					,csrep
					,(select substring(slevel,13,1) from security as s9 where securityid=o.csrep) as csactive
					,(select lname from security as s10 where securityid=o.csrep) as cslname
                    ,(select fname from security as s11 where securityid=o.csrep) as csfname
					,(select phone from security as cs10 where securityid=o.csrep) as csphone
					,(select ext from security as cs11 where securityid=o.csrep) as csext
                    ,(select (select name from offices where officeid=s9.officeid) from security as s9 where securityid=o.am) as loname
                    ,(select (select phone from offices where officeid=ss9.officeid) from security as ss9 where securityid=o.am) as lphone
					,(select phone from security as ss10 where securityid=o.am) as lsphone
					,(select ext from security as ss11 where securityid=o.am) as lsext
                FROM offices as o WHERE officeid=".$_SESSION['officeid'].";
            ";
	$resC  = mssql_query($qryC);
	$rowC  = mssql_fetch_array($resC);
    $nrowC = mssql_num_rows($resC);
    
    if ($_SESSION['llev'] >= 5 && $nrowC > 0)
	{
        $qryCa  = "select fname,lname,(select name from offices where officeid=ss.officeid) as aname,(select phone from offices where officeid=ss.officeid) as aphone from security as ss where securityid=".MTRX_ADMIN.";";
        $resCa  = mssql_query($qryCa);
        $rowCa  = mssql_fetch_array($resCa);
        $nrowCa = mssql_num_rows($resCa);
        
		echo "<table width=100% border=\"0\">\n";
		echo "	<tr>\n";
		echo "		<td>\n";
        echo "			<table class=\"outer\" width=100% border=\"0\">\n";
        echo "				<tr>\n";
        echo "      			<td colspan=\"4\" class=\"gray\" align=\"center\"><b>Contact List</b> ".$rowC['loname']."</td>\n";
        echo "				</tr>\n";
		echo "			</table>";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\">\n";
        echo "			<table class=\"outer\" width=100% border=\"0\">\n";
		
        if (isset($rowC['am']) && $rowC['am']!=0)
        {
			if ($rowC['lactive'] > 0)
			{
				$lfnt="black";
			}
			else
			{
				$lfnt="red";
			}
			
			echo "				<tr>\n";
			echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\"><b>Lead Admin</b></td>\n";
			echo "				</tr>\n";
            echo "				<tr>\n";
            echo "					<td class=\"gray\" align=\"right\"><font color=\"".$lfnt."\">".$rowC['lfname']." ".$rowC['llname']."</font></td>\n";
			
			if (strlen($rowC['lsphone']) < '10')
			{
				echo "					<td class=\"gray\" align=\"left\">".$rowC['lphone']."</td>\n";
			}
			else
			{
				echo "					<td class=\"gray\" align=\"left\">".$rowC['lsphone']."";
				
				if (strlen($rowC['lsext']) > '2')
				{
					echo " x".$rowC['lsext'];
				}
				
				echo " </td>\n";
			}
			
            echo "				</tr>\n";
        }
		
		if (isset($rowC['csrep']) && $rowC['csrep']!=0)
		{
			if ($rowC['csactive'] > 0)
			{
				$csfnt="black";
			}
			else
			{
				$csfnt="red";
			}
			
			echo "				<tr>\n";
			echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\"><b>Customer Service</b></td>\n";
			echo "				</tr>\n";
            echo "				<tr>\n";
            echo "					<td class=\"gray\" align=\"right\"><font color=\"".$csfnt."\">".$rowC['csfname']." ".$rowC['cslname']."</font></td>\n";
			
			if ($_SESSION['officeid']==89)
			{
				echo "					<td class=\"gray\" align=\"left\">800-543-3883</td>\n";
			}
			else
			{
				if (strlen($rowC['csphone']) < '10')
				{
					echo "					<td class=\"gray\" align=\"left\">".$rowC['lphone']."</td>\n";
				}
				else
				{
					echo "					<td class=\"gray\" align=\"left\">".$rowC['csphone']."";
					
					if (strlen($rowC['csext']) > '2')
					{
						echo " x".$rowC['csext'];
					}
					
					echo " </td>\n";
				}
			}
            echo "				</tr>\n";
        }
        
        if (isset($rowC['processor']) && $rowC['processor']!=0)
        {
			echo "				<tr>\n";
			echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\"><b>Processor</b></td>\n";
			echo "				</tr>\n";
            echo "				<tr>\n";
            echo "					<td class=\"gray\" align=\"right\">".$rowC['pfname']." ".$rowC['plname']."</td>\n";
			
			if (strlen($rowC['psphone']) < '10')
			{
				echo "					<td class=\"gray\" align=\"left\">".$rowC['pphone']."</td>\n";
			}
			else
			{
				echo "					<td class=\"gray\" align=\"left\">".$rowC['psphone']."";
				
				if (strlen($rowC['psext']) > '2')
				{
					echo " x".$rowC['psext'];
				}
				
				echo " </td>\n";
			}
			
            echo "				</tr>\n";
        }
        
		echo "				<tr>\n";
        echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\"><b>Pricebooks</b></td>\n";
		echo "				</tr>\n";
        echo "				<tr>\n";
        echo "					<td class=\"gray\" align=\"right\">Serena Schirmer</td>\n";
		echo "					<td class=\"gray\" align=\"left\">619-233-3522 x10111</td>\n";
        echo "				</tr>\n";
        
        if (isset($rowC['finan_from']) && $rowC['finan_from']!=0)
        {
			echo "				<tr>\n";
			echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\"><b>Finance</b></td>\n";
			echo "				</tr>\n";
            echo "				<tr>\n";
            echo "					<td class=\"gray\" align=\"right\">Janet Shawen</td>\n";
			echo "					<td class=\"gray\" align=\"left\">972-316-8033</td>\n";            
            echo "				</tr>\n";
        }
		
		if ($_SESSION['officeid']!=89)
        {
			echo "				<tr>\n";
			echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\"><b>BH Natl Customer Care</b></td>\n";
			echo "				</tr>\n";
            echo "				<tr>\n";
            echo "					<td class=\"gray\" align=\"left\"></td>\n";
			echo "					<td class=\"gray\" align=\"left\">800-543-3883</td>\n";            
            echo "				</tr>\n";
        }
		
		if ($_SESSION['officeid']!=89 || $_SESSION['officeid']!=138)
        {
			echo "				<tr>\n";
			echo "      			<td class=\"gray\" align=\"left\" colspan=\"2\"><b>BH Supplies Direct</b></td>\n";
			echo "				</tr>\n";
            echo "				<tr>\n";
            echo "					<td class=\"gray\" align=\"right\">Customer Inquiry</td>\n";
			echo "					<td class=\"gray\" align=\"left\">888-256-8121</td>\n";
            echo "				</tr>\n";
        }
    }
	
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function show_post_vars()
{
	if (is_array($_POST))
	{
		foreach ($_POST as $postname => $postvalue)
		{
			echo "_POST: $postname = $postvalue<br><br>";
			//var_dump($postvalue);
			//echo '<br>';
			if (is_array($postvalue))
			{
				echo "<blockquote>";
				
				foreach ($postvalue as $name => $value)
				{
					echo "$name = $value<br>";
					
					if (is_array($value))
					{
						echo "<blockquote>";
						
						foreach ($value as $iname => $ivalue)
						{
							echo "$iname = $ivalue<br>";							
						}
						
						echo "</blockquote>";
					}
					
				}
				
				echo "</blockquote>";
			}
		}
	}
}

function show_invarray_vars()
{
	global $invarray;
	if (is_array($invarray))
	{
		foreach ($invarray as $postname => $postvalue)
		{
			echo "invarray: $postname = $postvalue<br>";
			if (is_array($postvalue))
			{
				foreach ($postvalue as $name => $value)
				echo "$name = $value<br>";
			}
		}
	}
}

function show_array_vars($array)
{
	if (is_array($array))
	{
		echo "<table>\n";
		foreach ($array as $n=>$v)
		{
			echo "<tr>\n";
			
			echo "	<td valign=\"top\">array: $n=$v</td>";
			if (is_array($v))
			{
				echo "<td>\n";
				echo "	<table>\n";
				foreach ($v as $subn=>$subv)
				{
					echo "		<tr>\n";
					echo "			<td valign=\"top\">$subn = $subv</td>\n";
					if (is_array($subv))
					{
						echo "			<td>\n";
						echo "				<table>\n";
						
						foreach ($subv as $ssubn=>$ssubv)
						{
							echo "			<tr>\n";
							echo "				<td valign=\"top\">$ssubn = $ssubv</td>\n";
							echo "			<tr>\n";
						}
						
						echo "			</table>\n";
						echo "		</td>\n";
					}
					echo "		</tr>\n";
				}
				echo "	</table>\n";
				echo "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}

function show_array_pre($array)
{
	if (is_array($array))
	{
		//echo '<table><tr><td>';
		echo '<pre>';
		
		print_r($array);
		
		echo '</pre>';
		//echo '</td></tr></table>';
	}
	else
	{
		echo 'NOT ARRAY!';
	}
}


function dateformat()
{
	$ctime=time();
	$qry = "SELECT tzone FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);

	if ($row[0]=="EST")
	{
		$dmod=$ctime-10800;
	}
	elseif ($row[0]=="CST")
	{
		$dmod=$ctime-7200;
	}
	elseif ($row[0]=="PST")
	{
		$dmod=$ctime;
	}
	else
	{
		$dmod=$ctime;
	}

	$dt  =getdate($dmod);
	if (strlen($dt['minutes']) < 2) // Pads minutes with 0 if $dt['minutes'] less than 10
	{
		$mmod="0".$dt['minutes'];
	}
	else
	{
		$mmod=$dt['minutes'];
	}
	$fdt=array(0=>$dt['mon']."/".$dt['mday']."/".$dt['year']." (".$dt['hours'].":".$mmod.")",1=>$dmod);
	return $fdt;
}

function timeout()
{
	$retval=1;
	
	if ($_SESSION['securityid']==26)
	{
		$max_seconds=86400;
	}
	else
	{
		$max_seconds=10800;
	}
	
	$idle_time=time()-$_SESSION['last_access'];

	if ($idle_time < $max_seconds)
	{
		$_SESSION['last_access']=time();
		$retval=1;
	}
	else
	{
		$retval=0;
	}

	return $retval;
}

function timeout_new()
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

function sys_maintenance()
{
	bh_html_header();

	if (isset($_REQUEST['plogin']))
	{
		$id=$_REQUEST['plogin'];
	}
	else
	{
		$id=$_SESSION['plogin'];
	}

	$qry0 = "INSERT INTO jest_stats..events (evdescrip,status,sid,oid,ip) VALUES ('logoff','2','".$_SESSION['securityid']."','".$_SESSION['officeid']."','".getenv("REMOTE_ADDR")."');";
	$res0 = mssql_query($qry0);

	$qry = "DELETE FROM logstate WHERE securityid='".$_SESSION['securityid']."';";
	$res = mssql_query($qry);

	session_unset();
}

function logoff_proc()
{
	bh_html_header();

	if (isset($_REQUEST['plogin']))
	{
		$id=$_REQUEST['plogin'];
	}
	else
	{
		$id=$_SESSION['plogin'];
	}

	$qry0 = "INSERT INTO jest_stats..events (evdescrip,status,sid,oid,ip,brwsr) VALUES ('logoff','2','".$_SESSION['securityid']."','".$_SESSION['officeid']."','".getenv("REMOTE_ADDR")."','".$_SERVER['HTTP_REFERER']."');";
	$res0 = mssql_query($qry0);

	$qry = "DELETE FROM logstate WHERE securityid='".$_SESSION['securityid']."';";
	$res = mssql_query($qry);

	session_unset();

	echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$_SERVER['PHP_SELF']."\">";
	
}

function _show_hide_objects()
{
	echo "<ul id=\"icons\" class=\"ui-widget ui-helper-clearfix\">\n";
	echo "	<li class=\"ui-state-default ui-corner-all JMStooltip\" id=\"objSetShow\" title=\"Show Detail\"><div class=\"ui-icon ui-icon-zoomin\"></div></li>\n";
	echo "	<li class=\"ui-state-default ui-corner-all JMStooltip\" id=\"objSetHidden\" title=\"Hide Detail\"><div class=\"ui-icon ui-icon-zoomout\"></div></li>\n";
	echo "</ul>\n";
}

function _show_hide_objectsNEW()
{
	echo "	<button class=\"JMStooltip\" id=\"objSetShow\" title=\"Show Detail\"><div class=\"ui-icon ui-icon-zoomin\"></div></button><br>\n";
	echo "	<button class=\"JMStooltip\" id=\"objSetHidden\" title=\"Hide Detail\"><div class=\"ui-icon ui-icon-zoomout\"></div></button>\n";
}

function PageLoadMsg()
{
	echo "
	
	<div id=\"prepage\" style=\"hidden:true; position:absolute; font-size:12; text-align:center;\"> 
		<table>
			<tr>
				<td>
					<b>Loading...</b>  <img src=\"images/mozilla_blu.gif\">
				</td>
			</tr>
		</table>
	</div>
	
	";
}

function bh_html_header()
{
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
	echo "<html>\n";
	echo "	<head>\n";
	echo "		<meta name=\"ROBOTS\" content=\"NOINDEX, NOFOLLOW\">\n";
	echo "		<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\">\n";
	echo "		<title>".SYS_NAME." ".SYS_VER." (".SYS_ENV.")</title>\n";
	
	?>
		<noscript>Javascript is not enabled. Javascript is required for this application to function.</noscript>
        <!-- Core + Skin CSS -->
		<link rel="stylesheet" type="text/css" href="yui/build/reset-fonts/reset-fonts.css">
		<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.custom.css"/>
		<link rel="stylesheet" type="text/css" href="js/jquery-tooltip/jquery.tooltip.css">
		<link rel="stylesheet" type="text/css" href="js/jquery-treeview/jquery.treeview.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/bh_yui.css">
		<link rel="stylesheet" type="text/css" href="bh_main.css" media="screen">
		<link rel="stylesheet" type="text/css" href="bh_main_print.css" media="print">
	
	<?php
	
		if ($_SESSION['action']=='sales')
		{
			echo "		<link rel=\"stylesheet\" type=\"text/css\" href=\"css/bh_sales.css\" media=\"screen\">\n";
			echo "		<link rel=\"stylesheet\" type=\"text/css\" href=\"css/bh_sales_print.css\" media=\"print\">\n";
		}
	
	?>
		
		<!-- Dependencies --> 
        <script type="text/javascript" src="yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
        
        <!-- Source File -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/jqueryui-1.8.16/jquery.ui.core.js"></script>
		<script type="text/javascript" src="js/jqueryui-1.8.16/jquery.ui.mouse.js"></script>
		<script type="text/javascript" src="js/jqueryui-1.8.16/jquery.ui.position.js"></script>
		<script type="text/javascript" src="js/jquery.bgiframe.js"></script>
		<script type="text/javascript" src="js/jquery-treeview/lib/jquery.cookie.js"></script>
		<script type="text/javascript" src="js/jquery-tooltip/jquery.tooltip.min.js"></script>
		<script type="text/javascript" src="js/jquery-treeview/jquery.treeview.min.js"></script>
		<script type="text/javascript" src="js/jsTree/jquery.jstree.js"></script>
		<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
		<script type="text/javascript" src="js/jquery.formatCurrency-1.3.0.js"></script>
		<script type="text/javascript" src="js/extension.js"></script>
		<script type="text/javascript" src="js/jquery.init.js?<?php echo time(); ?>"></script>
		<script type="text/javascript" src="js/jms_help.js?<?php echo time(); ?>"></script>
		
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
	
	<?php
}

function bh_html_header_DEV() {
	echo "<!DOCTYPE HTML>\n";
	echo "<html lang=\"en\">\n";
	echo "	<head>\n";
	echo "		<meta name=\"ROBOTS\" content=\"NOINDEX, NOFOLLOW\">\n";
	echo "		<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\">\n";
	echo "		<title>".SYS_NAME." ".SYS_VER." (".SYS_ENV.")</title>\n";
	
	?>
		<noscript>Javascript is not enabled. Javascript is required for this application to function.</noscript>
        <!-- Core + Skin CSS -->
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"/>
		<link rel="stylesheet" type="text/css" href="js/jquery-treeview/jquery.treeview.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="bh_main.css" media="screen">
		<link rel="stylesheet" type="text/css" href="bh_main_print.css" media="print">
        
        <!-- Source File -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
		<script type="text/javascript" src="js/jquery-treeview/lib/jquery.cookie.js"></script>
		<script type="text/javascript" src="js/jquery-treeview/jquery.treeview.min.js"></script>
		<script type="text/javascript" src="js/jsTree/jquery.jstree.js"></script>
		<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
		<script type="text/javascript" src="js/jquery.formatCurrency-1.3.0.js"></script>
		<script type="text/javascript" src="js/extension.js"></script>
		<script type="text/javascript" src="js/jquery.init.js?<?php echo time(); ?>"></script>
		<script type="text/javascript" src="js/jms_help.js?<?php echo time(); ?>"></script>
		
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
	
	<?php
}

function bh_html_subs_header()
{
	$qry = "SELECT SYS_NAME,SYS_VER,SYS_ENV FROM jest..jest_config;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);

	//echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
	echo "<html>\n";
	echo "	<head>\n";
	echo "		<meta name=\"ROBOTS\" content=\"NOINDEX, NOFOLLOW\">\n";
	echo "		<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\">\n";
	echo "		<title>JMS Document Viewer</title>\n";
	
	?>        
        <!-- Core + Skin CSS -->
		<link rel="stylesheet" type="text/css" href="../yui/build/reset-fonts/reset-fonts.css">
		<link rel="stylesheet" type="text/css" href="../yui/build/container/assets/skins/sam/container.css" />
		<link rel="stylesheet" type="text/css" href="../yui/build/tabview/assets/skins/sam/tabview.css" />
		<link rel="stylesheet" type="text/css" href="../yui/build/menu/assets/skins/sam/menu.css" />
		<link rel="stylesheet" type="text/css" href="../yui/build/editor/assets/skins/sam/simpleeditor.css" />
		
		<!-- Dependencies --> 
        <script type="text/javascript" src="../yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
        
        <!-- Source File -->
		<script type="text/javascript" src="../yui/build/container/container_core-min.js"></script>
		<script type="text/javascript" src="../yui/build/element/element-min.js"></script>
		<script type="text/javascript" src="../yui/build/tabview/tabview-min.js"></script>
		<script type="text/javascript" src="../yui/build/menu/menu-min.js"></script>
		<script type="text/javascript" src="../yui/build/editor/simpleeditor-min.js"></script>
		<script type="text/javascript" src="../mainmenu/menu.js"></script>
		<script type="text/javascript" src="../js/overlay.js"></script>
	
	<?php
	
	//echo "		<script language=\"JavaScript\" type=\"text/javascript\" src=\"../calendar1.js\"></script>\n";
	//echo "		<script language=\"JavaScript\" type=\"text/javascript\" src=\"../calendar2.js\"></script>\n";
	echo "		<script language=\"Javascript\" type=\"text/javascript\" src=\"../js/extension.js\"></script>\n";
}

function bh_html_footer()
{
	//echo "		<div id=\"footer\" style=\"position: absolute;\">\n";
	
	//@footertext();
	
	//echo "		</div>\n";
	echo "	</body>\n";
	echo "</html>\n";
}

function footertext()
{
	echo "<div class=\"PrintOnly\">\n";
	echo "<center>\n";
	echo "Copyright &copy; ".date('Y')." Blue Haven Pools &amp; Spas";
	echo "</center>\n";
	echo "</div>\n";
}

function image_tray($cid)
{
	$qry0	= "SELECT F.docid,filename FROM jest..jestFileStore AS F WHERE F.cid=".$cid." AND F.active=1 AND substring(F.filetype,1,5)='image';";
    $res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
	
	//echo $qry0.'<br>';
	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			//echo $qry0.'<br>';
			echo "<a href=\".\subs\showimage.php?docid=".$row0['docid']."\" target=\"JMSShowImage\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSShowImage','HEIGHT=550,WIDTH=700,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"><img src=\"export/fileout.php?storetype=file&docid=".$row0['docid']."\" height=\"50px\" width=\"50px\" title=\"".$row0['filename']."\"></a><br><br>\n";
			//echo "<img src=\"export/fileout.php?storetype=file&docid=".$row0['docid']."\" height=\"50px\" width=\"50px\" title=\"".$row0['filename']."\"><p>\n";
		}
	}
}

function watermarkImage ($SourceFile, $WaterMarkText, $DestinationFile)
{
   
   list($width, $height) = getimagesize($SourceFile);
   $image_p = imagecreatetruecolor($width, $height);
   $image = imagecreatefromjpeg($SourceFile);
   
   imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
   
   $fcolor = imagecolorallocate($image_p, 255, 255, 255);
   $font = 'arial.ttf';
   $font_size = 10;
   
   imagettftext($image_p, $font_size, 0, 10, 20, $fcolor, $font, $WaterMarkText);
   
   if ($DestinationFile<>'')
   {
      imagejpeg ($image_p, $DestinationFile, 100); 
   }
   else
   {
      header('Content-Type: image/jpeg');
      imagejpeg($image_p, null, 100);
   }
   
   imagedestroy($image); 
   imagedestroy($image_p); 
}

function menu()
{
	define ("HELPNODES", true);
	$_SESSION['SessHash']=md5($_SESSION['securityid'].'.'.substr($_SESSION['lname'],0,2));
	
    $qry0	= "SELECT S.officeid,S.menutype,S.emailtemplateaccess,(select otype_code from jest..offices where officeid=S.officeid) AS sotype_code,tester FROM security AS S WHERE S.securityid=".(int) $_SESSION['securityid'].";";
    $res0	= mssql_query($qry0);
    $row0	= mssql_fetch_array($res0);
    
    $menutype					=$row0['menutype'];
    $_SESSION['emailtemplates']	=$row0['emailtemplateaccess'];
	$_SESSION['sotype_code']	=$row0['sotype_code'];
	$_SESSION['tester']			=$row0['tester'];
	
	$devaccess=(isset($row0['tester']) and $row0['tester']==1)?'<span title="You have access to Test Functions" style="color:red;"><b>Test Access</b></span>':'';
    
    $qry1	= "SELECT endigreport,gm,am,finan_off,otype,otype_code FROM offices WHERE officeid='".$_SESSION['officeid']."';";
    $res1	= mssql_query($qry1);
    $row1	= mssql_fetch_array($res1);
	
	$_SESSION['otype']		=$row1['otype'];
	
	if (!empty($_SESSION['action']) && $_SESSION['action']=="update_off")
    {
		update_set_office();
    }
    
    if	( // auto change officeid session variable when select customer from Quick Search
		    !empty($_REQUEST['noffid']) &&
		    $_REQUEST['noffid']!=$_SESSION['officeid']
	    )
    {
	    update_set_office();
    }	
	
	?>
	
	<script type="text/javascript">
			window.name='JMSmain';
			window.ActiveOffice=<?php echo $_SESSION['officeid']; ?>
	</script>

	<?php
    echo "	</head>\n";
	
	/*
	if (isset($_SESSION['tester']) and $_SESSION['tester']!=0) {
		echo "	<body>\n";
	}
	else {
		echo "	<body class=\"yui-skin-sam\">\n";
	}
	*/
	
	echo "	<body>\n";
	echo "	<script type=\"text/javascript\" src=\"js/jquery_browser_detect.js\"></script>\n";
	echo "	<input type=\"hidden\" id=\"active_office\" value=\"".$_SESSION['officeid']."\">\n";
	echo "	<div id=\"commondialog\" title=\"JMS Message\"></div>\n";

    $brdr=0;
    $dev_ar=array(SYS_ADMIN,FDBK_ADMIN);
    
    if (LOCK_SYS == 1)
    {
		echo "<center><b>JMS is DOWN for Maintenance</b></center><br>";
		//echo $_SESSION['securityid'];
	
		if (!in_array($_SESSION['securityid'],$dev_ar))
		{
			exit;
		}
    }

    $pbvalidate=array(0,0,0,0);
    
    if (!empty($_REQUEST['set_asst']) && $_REQUEST['set_asst']==1)
    {
	    update_set_assistant();
    }
    
	include (".\menus_func.php");

    echo "	<table class=\"transnb\" align=\"center\" width=\"100%\" cellpadding=0 cellspacing=0 border=\"".$brdr."\">\n";
    echo "		<tr>\n";
	echo "			<td align=\"center\" valign=\"top\">\n";
	
	button_matrix($pbvalidate[0]);
	
    echo "			</td>\n";
    echo "		</tr>\n";
	echo "	</table>\n";
	echo "	<table class=\"transnb\" align=\"center\" width=\"100%\" cellpadding=0 cellspacing=0 style=\"margin-top:30px;\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"center\" valign=\"top\">\n";

	echo $devaccess;
	
    echo "			</td>\n";
    echo "		</tr>\n";
    echo "		<tr>\n";
	echo "			<td align=\"center\" valign=\"top\">\n";

    menu_matrix();

    echo "			</td>\n";
    echo "		</tr>\n";

    if (empty($_SESSION['action'])||$_SESSION['action']=="None"||$_SESSION['action']=="main"||$_SESSION['action']=="update_off")
    {
		echo "		<tr>\n";
		echo "			<td align=\"center\" valign=\"top\">\n";
	
		systemwidemessageNEW();
		
		echo "			</td>\n";
		echo "		</tr>\n";
    }

    echo "		<tr>\n";
	echo "			<td align=\"center\" valign=\"top\">\n";
    
	//@footertext();
    
    echo "			</td>\n";
    echo "		</tr>\n";
    echo "	</table>\n";
    echo "<br>\n";
	
	passchgeval();
	
	if ($_SESSION['jlev'] >= 6 && $_SESSION['rlev'] >= 5 && $row0['officeid']!=89)
    {
	    if ($row1['endigreport']==1 and $_SESSION['securityid']==26)
	    {
		    drvalperiod();
	    }
    }

	$mnu_dbg=0;
    if ($mnu_dbg==1 && $_SESSION['securityid']===26)
    {
	    echo "<pre>\n";
		print_r($_SESSION);
	    echo "</pre>\n";
    }
}

function jquery_notify_popup($etitle,$etxt)
{
	echo "<div id=\"".$etitle."\" title=\"Attention\">\n";
	echo "	<div class=\"ui-widget\">\n";
	//echo "		<div class=\"ui-state-error ui-corner-all\" style=\"padding: 0 .7em;\">\n";
	echo "			<p>\n";
	//echo "				<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span>\n";
	
	echo $etxt;
	
	//echo "				</span>\n";
	echo "			</p>\n";
	//echo "		</div>\n";
	echo "	</div>\n";
	echo "</div>\n";
}

function jquery_error_popup($etitle,$etxt)
{
	echo "<div id=\"".$etitle."\" title=\"Attention\">\n";
	echo "	<div class=\"ui-widget\">\n";
	echo "		<div class=\"ui-state-error ui-corner-all\" style=\"padding: 0 .7em;\">\n";
	echo "			<p>\n";
	echo "				<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span>\n";
	
	echo $etxt;
	
	echo "				</span>\n";
	echo "			</p>\n";
	echo "		</div>\n";
	echo "	</div>\n";
	echo "</div>\n";
}

function display_user_menubar_short_info()
{
	echo "<b>[ </b>".$_SESSION['fname']." ".$_SESSION['lname']."<b> ]</b>\n";
}

function display_user_menubar_info()
{
	echo "<b>[ </b>".$_SESSION['fname']." ".$_SESSION['lname']."<b> ]<br/>[ </b>".$_SESSION['offname']."<b> ]</b>\n";
}

function constructiondates_display($cid,$jobid,$cdaccess)
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$qry0 = "SELECT
				cid
				,officeid
				,jobid
				,njobid
				,(select contractamt from jdetail where jobid=C.jobid and jadd=0) as contractamt
				,(select contractdate from jdetail where jobid=C.jobid and jadd=0) as contractdate
				,(select renov from jobs where jobid=C.jobid) as renov
			FROM
				jest..cinfo AS C
			WHERE
				C.jobid='".$jobid."'
		";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	$qry0p = "SELECT officeid,enquickbooks FROM offices where officeid=".$row0['officeid'];
	$res0p = mssql_query($qry0p);
	$row0p = mssql_fetch_array($res0p);
	
	$qry1p = "SELECT officeid FROM security where securityid=".$_SESSION['securityid'];
	$res1p = mssql_query($qry1p);
	$row1p = mssql_fetch_array($res1p);

	$qry = "SELECT
				p.*
				,(select cdate from constructiondates where cid=".$cid." and phsid=p.phsid and dtype=1) as act_sdate
				,(select cdate from constructiondates where cid=".$cid." and phsid=p.phsid and dtype=2) as act_edate
				,(select cdate from constructiondates where cid=".$cid." and phsid=p.phsid and dtype=3) as act_rdate
				,(select ramt from constructiondates where cid=".$cid." and phsid=p.phsid and dtype=3) as act_ramt
				,(select TxnID from constructiondates where cid=".$cid." and phsid=p.phsid and dtype=3) as act_TxnID
			FROM
				phasebase AS p
			WHERE
				p.condate=1
			ORDER BY
				p.seqnum ASC;
			";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($_SESSION['securityid']==26999999999)
	{
		echo $qry.'<br>';
	}
	
	if (isset($row0['renov']) and $row0['renov']==1)
	{
		$rtxtr='Setting a date in this field sets the Dig Date for Renovations';
		$rtxtn='';
	}
	elseif (isset($row0['renov']) and $row0['renov']==0)
	{
		$rtxtr='';
		$rtxtn='Setting a date in this field sets the Dig Date for New Builds';
	}
	else
	{
		$rtxtr='';
		$rtxtn='';
	}
	
	if ($nrow > 0 and $cdaccess > 0)
	{
		setlocale(LC_MONETARY, 'en_US');
		
		if ($cdaccess >= 5)
		{
			echo "<form method=\"POST\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"constructiondates_process\">\n";
			echo "<input type=\"hidden\" name=\"jobid\" value=\"".$jobid."\">\n";
			echo "<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
			echo "<input type=\"hidden\" name=\"renov\" value=\"".$row0['renov']."\">\n";
		}
		
		echo "<table>\n";
		echo "	<tr>\n";
		echo "		<td></td>\n";
		echo "		<td colspan=\"4\" align=\"center\"><b>Construction</b></td>\n";
		echo "		<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";		
		echo "		<td colspan=\"3\" align=\"center\"><b>Receivable</b></td>\n";
		echo "		<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><b>Phase</b></td>\n";
		echo "		<td align=\"center\"><b>Code</b></td>\n";
		echo "		<td align=\"center\"><b>Scheduled</b></td>\n";
		echo "		<td><img src=\"images/pixel.gif\"></td>\n";
		echo "		<td align=\"center\"><b>Complete</b></td>\n";
		echo "		<td><img src=\"images/pixel.gif\"></td>\n";
		echo "		<td align=\"center\"><b>Date</b></td>\n";
		echo "		<td align=\"center\"><b>Amount</b></td>\n";
		echo "		<td><img src=\"images/pixel.gif\"></td>\n";
		echo "		<td align=\"center\"><b>Clear</b></td>\n";
		echo "	</tr>\n";
		
		$dcnt=0;
		$dramt=0;
		$pramt=0;
		$cramt=0;
		$dx_phs=array(45,46,48); // Display Exclude
		$ex_phs=array(45); // Logic Exclude
		
		while ($row = mssql_fetch_array($res))
		{
			$dcnt++;
			echo "	<tr>\n";
			echo "		<td>".$row['extphsname']."</td>\n";
			echo "		<td align=\"center\">\n";
			
			if (!in_array($row['phsid'],$dx_phs))
			{
				echo $row['phscode'];
			}
			
			echo "		</td>\n";
			echo "		<td align=\"center\">\n";
			
			if (isset($row['sdate']) && $row['sdate']==1 && !in_array($row['phsid'],$ex_phs))
			{
				if ($cdaccess >= 5)
				{
					if (isset($row['act_sdate']) && strtotime($row['act_sdate']) >= strtotime('1/1/2005'))
					{
						if ($row['phsid']==9)
						{
							$jddate = date("m/d/Y", strtotime($row['act_sdate']));
							$prd_mo	= date("m", strtotime($row['act_sdate']));
							$prd_yr	= date("Y", strtotime($row['act_sdate']));
					
							$qryDD	= "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$prd_mo."' AND rept_yr='".$prd_yr."';";
							$resDD	= mssql_query($qryDD);
							$nrowDD	= mssql_num_rows($resDD);
							//echo $qryDD.'<br>';
		
							if ($nrowDD >= 1)
							{
								echo "<div class=\"JMStooltip\" title=\"Dig Report created for this time period. Edit disabled.\">".date('m/d/Y',strtotime($row['act_sdate']))."</div>\n";
							}
							else
							{
								echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][sdate]\" id=\"datep".$dcnt."\" value=\"".date('m/d/Y',strtotime($row['act_sdate']))."\" size=\"9\" maxlength=\"10\" title=\"".$rtxtr."\">\n";
							}
						}
						else
						{
							echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][sdate]\" id=\"datep".$dcnt."\" value=\"".date('m/d/Y',strtotime($row['act_sdate']))."\" size=\"9\" maxlength=\"10\">\n";
						}
					}
					else
					{
						if ($row['phsid']==9)
						{
							echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][sdate]\" id=\"datep".$dcnt."\" size=\"9\" maxlength=\"10\" title=\"".$rtxtr."\">\n";
						}
						else
						{
							echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][sdate]\" id=\"datep".$dcnt."\" size=\"9\" maxlength=\"10\">\n";	
						}
					}
				}
				else
				{
					if (valid_date(date('m/d/Y',strtotime($row['act_sdate']))) and strtotime($row['act_sdate']) > strtotime('1/1/2005'))
					{
						echo date('m/d/Y',strtotime($row['act_sdate']));
					}
				}
			}
			
			echo "		</td>\n";
			echo "		<td></td>\n";
			echo "		<td align=\"center\">\n";
			
			if (isset($row['edate']) && $row['edate']==1 && !in_array($row['phsid'],$ex_phs))
			{
				if ($cdaccess >= 5)
				{
					$dcnt++;
					if (isset($row['act_edate']) && strtotime($row['act_edate']) >= strtotime('1/1/2005'))
					{
						if ($row['phsid']==9)
						{
							$jddate = date("m/d/Y", strtotime($row['act_edate']));
							$prd_mo	= date("m", strtotime($row['act_edate']));
							$prd_yr	= date("Y", strtotime($row['act_edate']));
					
							$qryDD	= "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$prd_mo."' AND rept_yr='".$prd_yr."';";
							$resDD	= mssql_query($qryDD);
							$nrowDD	= mssql_num_rows($resDD);
							//echo $qryDD.'<br>';
		
							if ($nrowDD >= 1)
							{
								echo "<div class=\"JMStooltip\" title=\"Dig Report created for this time period. Edit disabled.\">".date('m/d/Y',strtotime($row['act_edate']))."</div>\n";
								//echo "<input type=\"hidden\" name=\"condates[".$row['phsid']."][edate]\" id=\"datep".$dcnt."\" value=\"".date('m/d/Y',strtotime($row['act_edate']))."\" size=\"9\" maxlength=\"10\" title=\"".$rtxtn."\">\n";
							}
							else
							{
								echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][edate]\" id=\"datep".$dcnt."\" value=\"".date('m/d/Y',strtotime($row['act_edate']))."\" size=\"9\" maxlength=\"10\" title=\"".$rtxtn."\">\n";
							}
						}
						else
						{
							echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][edate]\" id=\"datep".$dcnt."\" value=\"".date('m/d/Y',strtotime($row['act_edate']))."\" size=\"9\" maxlength=\"10\">\n";
						}
					}
					else
					{
						if ($row['phsid']==9)
						{
							echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][edate]\" id=\"datep".$dcnt."\" size=\"9\" maxlength=\"10\" title=\"".$rtxtn."\">\n";
						}
						else
						{
							echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][edate]\" id=\"datep".$dcnt."\" size=\"9\" maxlength=\"10\">\n";	
						}
					}
				}
				else
				{
					if (valid_date(date('m/d/Y',strtotime($row['act_edate']))) and strtotime($row['act_edate']) > strtotime('1/1/2005'))
					{
						echo date('m/d/Y',strtotime($row['act_edate']));
					}
				}
			}
			
			echo "		</td>\n";
			echo "		<td></td>\n";
			echo "		<td align=\"center\">\n";
			
			if (isset($row['rdate']) && $row['rdate']==1 && !in_array($row['phsid'],$ex_phs))
			{
				if ($cdaccess >= 5)
				{
					$dcnt++;
					if (isset($row['act_rdate']) && strtotime($row['act_rdate']) >= strtotime('1/1/2005'))
					{
						echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][rdate]\" id=\"datep".$dcnt."\" value=\"".date('m/d/Y',strtotime($row['act_rdate']))."\" size=\"9\" maxlength=\"10\">\n";
					}
					else
					{
						echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][rdate]\" id=\"datep".$dcnt."\" size=\"9\" maxlength=\"10\">\n";
					}
				}
				else
				{
					if ($row['phsid']==45)
					{
						if (valid_date(date('m/d/Y',strtotime($row0['contractdate']))) and strtotime($row0['contractdate']) > strtotime('1/1/2005'))
						{
							echo date('m/d/Y',strtotime($row0['contractdate']));
						}
					}
					else
					{
						if (valid_date(date('m/d/Y',strtotime($row['act_rdate']))) and strtotime($row['act_rdate']) > strtotime('1/1/2005'))
						{
							echo date('m/d/Y',strtotime($row['act_rdate']));
						}
					}
				}
			}
			else
			{
				if ($row['phsid']==45)
				{
					if (valid_date(date('m/d/Y',strtotime($row0['contractdate']))) and strtotime($row0['contractdate']) > strtotime('1/1/2005'))
					{
						if ($row1p['officeid']==89)
						{
							echo '<a href="#" id="editContractDate" title="Edit Contract Date">'.date('m/d/Y',strtotime($row0['contractdate'])).'</a>';
						}
						else
						{
							echo date('m/d/Y',strtotime($row0['contractdate']));
						}
					}
				}
				else
				{
					if (valid_date(date('m/d/Y',strtotime($row['act_rdate']))) and strtotime($row['act_rdate']) > strtotime('1/1/2005'))
					{
						echo date('m/d/Y',strtotime($row['act_rdate']));
					}
				}
			}
			
			echo "		</td>\n";
			echo "		<td align=\"right\">\n";
			
			if (isset($row['rdate']) && $row['rdate']==1  && !in_array($row['phsid'],$ex_phs))
			{
				if ($cdaccess >= 5)
				{
					if (isset($row['act_ramt']) && $row['act_ramt'] > 0)
					{
						echo "		<input type=\"text\" class=\"bboxbr formatCurrency\" name=\"condates[".$row['phsid']."][ramt]\" id=\"ramt".$row['phsid']."\" value=\"".number_format($row['act_ramt'],2,'.','')."\" size=\"8\" maxlength=\"10\">\n";
						$pramt=$pramt + $row['act_ramt'];
					}
					else
					{
						echo "		<input type=\"text\" class=\"bboxbr formatCurrency\" name=\"condates[".$row['phsid']."][ramt]\" id=\"ramt".$row['phsid']."\" value=\"0.00\" size=\"8\" maxlength=\"10\">\n";
					}
				}
				else
				{
					echo number_format($row['act_ramt'],2,'.','');
					$pramt=$pramt + $row['act_ramt'];
				}
			}
			else
			{
				if ($row['phsid']==45)
				{
					if (valid_date(date('m/d/Y',strtotime($row0['contractdate']))))
					{
						echo number_format($row0['contractamt'],2,'.','');
						$cramt=	number_format($row0['contractamt'],2,'.','');
					}
				}
			}
			
			echo "		</td>\n";
			echo "		<td align=\"center\">\n";
			
			if (isset($row['act_TxnID']) and $row['act_TxnID']!=='0')
			{
				echo "<img class=\"JMStooltip\" src=\"images/action_check.gif\" title=\"Payment Processed by Quickbooks: ".$row['act_TxnID']."\">";
			}
			
			//if ($_SESSION['securityid']==26)
			//{
			//	echo $row['TxnID'];
			//}
			
			echo "		</td>\n";
			echo "		<td align=\"center\">\n";
			
			if ($cdaccess >= 5)
			{
				if (!in_array($row['phsid'],$ex_phs))
				{
					echo "			<div class=\"noPrint\"><input class=\"transnb\" type=\"checkbox\" name=\"condates[".$row['phsid']."][clear]\" value=\"1\" title=\"Check this box and Update to remove the entries for this Phase\"></div>\n";
				}
			}
			
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		
		//Addendum Loop		
		$qry9  = "SELECT jobid,jadd,raddnpr_man,psched_adj,added FROM jdetail WHERE officeid=".$_SESSION['officeid']." and jobid='".$row0['jobid']."' and jadd >= 1;";
		$res9  = mssql_query($qry9);
		$nrow9 = mssql_num_rows($res9);
		
		//echo $qry9.'<br>';
		
		if ($nrow9 > 0)
		{
			while ($row9  = mssql_fetch_array($res9))
			{
				$cramt=$cramt+$row9['psched_adj'];
				echo "	<tr>\n";
				echo "		<td>Addn</td>\n";
				echo "		<td>". (600 + $row9['jadd']) ."L</td>\n";
				echo "		<td></td>\n";
				echo "		<td></td>\n";
				echo "		<td></td>\n";
				echo "		<td></td>\n";
				echo "		<td></td>\n";
				echo "		<td align=\"right\">".number_format($row9['psched_adj'],2,'.','')."</td>\n";
				echo "		<td></td>\n";
				echo "	</tr>\n";
			}
		}
		
		echo "	<tr>\n";
		echo "		<td colspan=\"10\"><hr width=\"100%\"></td>\n";
		echo "	</tr>\n";
		
		echo "	<tr>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td colspan=\"4\" align=\"right\"><b>Total Received<b></td>\n";
		echo "		<td align=\"right\">".number_format($pramt,2,'.','')."</td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "	</tr>\n";
		
		echo "	<tr>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td colspan=\"2\" align=\"right\"><b>Total Due<b></td>\n";
		echo "		<td align=\"right\">".number_format(($cramt - $pramt),2,'.','')."</td>\n";
		echo "		<td></td>\n";
		echo "		<td align=\"center\">\n";
		
		if ($cdaccess >= 5)
		{
			echo "			<div class=\"noPrint\"><input class=\"transnb\" type=\"image\" src=\"images/save.gif\" title=\"Update\"></div>\n";
		}
		
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";

		if ($cdaccess >= 5)
		{
			echo "</form>\n";
		}
	}
	
	ini_set('display_errors','Off');
}

function menu_matrix() {
	/*
	if ($_SESSION['securityid']==26) {
		ini_set('display_errors','On');
		error_reporting(E_ALL);
		echo '<BR>'.__FUNCTION__.'<BR>';
		echo '<pre>';
		print_r($_REQUEST);
		echo '</pre>';
	}
	*/
	
	$qry	= "SELECT finan_off FROM offices WHERE officeid=".(int) $_SESSION['officeid'].";";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
	
	$_SESSION['admin_offs'] =array(89);
	
	if ($_SESSION['action']=="leads")
	{
		if ($row['finan_off']==0)
		{
			include ("./lead_func.php");
		}
		else
		{
			include ("./lead_finan_func.php");
		}
		
		if ($_SESSION['securityid']==26) {
			include ("./calendar_func.php");
		}
		else {
			include ("./calendar_func_OLD.php");
		}
		
		basematrix();
	}
	// Network Matrix Process Hook
	elseif ($_SESSION['action']=="network")
	{
		include ("./network_func.php");
		
		basematrix();
	}
	elseif ($_SESSION['action']=="Purchasing")
	{
		include ("./purchasing_func.php");
		
		basematrix();
	}
	//Estimate/Costing Menu Items
	elseif ($_SESSION['action']=="est")
	{
		if (isset($_REQUEST['estid']))
		{
			$_SESSION['estid']=$_REQUEST['estid'];
		}
		else
		{
			$_SESSION['estid']=0;
		}

		if (isset($_SESSION['tester']) and $_SESSION['tester']==12) {
			if (isset($_SESSION['etype']) && $_SESSION['etype']=='Q') {
				require ("./estimate_func.php");
				
				quote_matrix();
			}
			else {
				require ("./estimates_DEV.php");
				BaseMatrix();
			}
		}
		else {
			if (isset($_SESSION['etype']) && $_SESSION['etype']=='Q') {
				require ("./estimate_func.php");
				
				quote_matrix();
			}
			else {
				require ("./estimatematrix_func.php");
				estimate_matrix();
			}
		}
	}
	// Contract Menu Items
	elseif ($_SESSION['action']=="contract")
	{
		include ("./contract_func.php");
		basematrix();
	}
	//Job Menu Items
	elseif ($_SESSION['action']=="job")
	{
		include ("./job_func.php");
		basematrix();
	}
	// MAS funtions
	elseif ($_SESSION['action']=="mas")
	{
		include ("./mas_func.php");
		masmatrix();
	}
	//Report Menu Items
	elseif ($_SESSION['action']=="reports")
	{
		include ("./reports_func.php");
		include ("./file_func.php");
		BaseMatrix();
	}
	//Messaging Menu Items
	elseif ($_SESSION['action']=="message")
	{
		include ("./messages_func.php");
		BaseMatrix();
	}
	elseif ($_SESSION['action']=="sales")
	{
		include ("./sales_func.php");
		BaseMatrix();
	}
	elseif ($_SESSION['action']=="file")
	{
		include ("./file_func_loader.php");
		BaseMatrix();
	}
	elseif ($_SESSION['action']=="maint")
	{
		if ($_SESSION['call']=="users")
		{
			include ("./user_maint_func.php");
			if (!isset($_SESSION['subq'])||$_SESSION['subq']=="None")
			{
				listusers();
			}
			elseif ($_SESSION['subq']=="view")
			{
				if ($_SESSION['securityid']===269999999999999999999999)
				{
					viewuser_TED();
				}
				else
				{
					viewuser();
				}
			}
			elseif ($_SESSION['subq']=="add")
			{
				adduser();
			}
			elseif ($_SESSION['subq']=="edit")
			{
				edituser($_REQUEST['userid']);
			}
			elseif ($_SESSION['subq']=="update")
			{
				updateuser($_REQUEST['userid']);
			}
			elseif ($_SESSION['subq']=="delete")
			{
				deleteuser($_REQUEST['userid']);
			}
			elseif ($_SESSION['subq']=="cl")
			{
				clearuserlogin();
			}
			elseif ($_SESSION['subq']=="rp")
			{
				resetuserpword();
			}
			elseif ($_SESSION['subq']=="set_offlist")
			{
				set_altoffice();
			}
			elseif ($_REQUEST['subq']=="pp")
			{
				//echo $_REQUEST['subq'].'<br>';
				pad_display();
			}
			elseif ($_REQUEST['subq']=="ppu")
			{
				//echo $_REQUEST['subq'].'<br>';
				pad_update();
			}
			elseif ($_REQUEST['subq']=="ppia")
			{
				//echo $_REQUEST['subq'].'<br>';
				pad_item_add();
			}
			elseif ($_REQUEST['subq']=="ppiu")
			{
				//echo $_REQUEST['subq'].'<br>';
				pad_item_update();
			}
		}
		elseif ($_SESSION['call']=="off")
		{
			include ("./office_maint_func.php");
			basematrix();
		}
		elseif ($_SESSION['call']=="events")
		{
			listevents();
		}
		elseif($_SESSION['call']=="leads")
		{
			include ("./lead_func.php");
			if ($_SESSION['subq']=="mailproc")
			{
				if (!empty($_REQUEST['conf']) && $_REQUEST['conf']==1)
				{
					//echo "Getting Internet Leads. This may take a few minutes...<br>";
					//echo "<b>Do not</b> click confirm again!";
				}

				mailproc();
			}
			elseif ($_SESSION['subq']=="autosort")
			{
				autosort();
			}
			elseif ($_SESSION['subq']=="autosort_zip")
			{
				autosort_zip();
			}
			elseif ($_SESSION['subq']=="mansort")
			{
				mansort();
			}
			elseif ($_SESSION['subq']=="viewproclist")
			{
				viewproclist();
			}
			elseif ($_SESSION['subq']=="viewunproclist")
			{
				viewunproclist();
			}
			elseif ($_SESSION['subq']=="view_lform")
			{
				lform_view();
			}
			elseif ($_SESSION['subq']=="upd_ringto")
			{
				upd_ringto();
			}
			elseif ($_SESSION['subq']=="access_report")
			{
				access_report();
			}
			elseif ($_SESSION['subq']=="upfile1")
			{
				upfile1();
			}
			elseif ($_SESSION['subq']=="upfile2")
			{
				upfile2();
			}
			elseif ($_SESSION['subq']=="upfile3")
			{
				upfile3();
			}
			elseif ($_SESSION['subq']=="upfile4")
			{
				upfile4();
			}
			elseif ($_SESSION['subq']=="lead_source_list")
			{
				lead_source_list();
			}
			elseif ($_SESSION['subq']=="lead_source_add")
			{
				lead_source_add();
			}
			elseif ($_SESSION['subq']=="lead_source_upd")
			{
				lead_source_upd();
			}
			elseif ($_SESSION['subq']=="lead_result_list")
			{
				lead_result_list();
			}
			elseif ($_SESSION['subq']=="lead_result_add")
			{
				lead_result_add();
			}
			elseif ($_SESSION['subq']=="lead_result_upd")
			{
				lead_result_upd();
			}
			elseif
				(
					   $_SESSION['subq']=="move1"
					|| $_SESSION['subq']=="move2"
					|| $_SESSION['subq']=="move3"
					|| $_SESSION['subq']=="move4"
				)
			{
				move_leads();
			}
			
		}
		elseif ($_SESSION['call']=="sysmess")
		{
			include ("./sysmessages_func.php");
			if ($_SESSION['subq']=="list")
			{
				listm();
			}
			elseif ($_SESSION['subq']=="add")
			{
				addm();
			}
			elseif ($_SESSION['subq']=="add2")
			{
				addm2();
			}
			elseif ($_SESSION['subq']=="view")
			{
				viewm();
			}
			elseif ($_SESSION['subq']=="update")
			{
				updatem();
			}
		}
		elseif ($_SESSION['call']=="IVR")
		{
			include ("./IVR_maint_func.php");
			
			sys_matrix();
		}
		elseif ($_SESSION['call']=="mail_send")
		{
			mail_out();
		}
		elseif ($_SESSION['call']=="commbuilder")
		{
			include ("./comm_builder_func.php");
			basematrix();
		}
		elseif ($_SESSION['call']=="srcrescodes")
		{
			include ("./srcres_codes_func.php");
			basematrix();
		}
		elseif ($_SESSION['call']=="emailtemplate")
		{
			include ("./email_template_func.php");
			basematrix();
		}
		elseif ($_SESSION['call']=="changelog")
		{
			echo "<script type=\"text/javascript\" src=\"js/jquery_changelog_func.js\"></script>\n";
			echo "<div id=\"ChangeLog\"></div>";
		}
		elseif ($_SESSION['call']=="email")
		{
			include ("./email_manage_func.php");
			BaseMatrix();
		}
	}
	//Maintenance Menu Items
	elseif ($_SESSION['action']=="pbconfig")
	{
		if ($_SESSION['call']=="bpool")
		{
			include ("./bpt_func.php");
			if (!isset($_SESSION['subq'])||$_SESSION['subq']=="None")
			{
				showbpt();
			}
			elseif ($_SESSION['subq']=="add")
			{
				insertbp();
			}
			elseif ($_SESSION['subq']=="edit")
			{
				editbp();
			}
			elseif ($_SESSION['subq']=="update")
			{
				updatebp();
			}
			elseif ($_SESSION['subq']=="update_base_pool_calcs")
			{
				update_base_pool_calc_settings();
			}
			elseif ($_SESSION['subq']=="update_base_descrip")
			{
				update_base_descrip();
			}
			elseif ($_SESSION['subq']=="defmeas")
			{
				update_base_meas();
			}
			elseif ($_SESSION['subq']=="delete")
			{
				delete_base_meas();
			}
			elseif ($_SESSION['subq']=="updatebp_perc")
			{
				updatebp_perc();
			}
			elseif ($_SESSION['subq']=="updatebpcm_perc")
			{
				updatebpcm_perc();
			}
		}
		elseif ($_SESSION['call']=="cat")
		{
			include ("./cost_maint_func.php");
			if (!isset($_SESSION['subq'])||$_SESSION['subq']=="list")
			{
				catlist();
			}
			elseif ($_SESSION['subq']=="add")
			{
				addcat();
			}
			elseif ($_SESSION['subq']=="update")
			{
				updatecat();
			}
			elseif ($_SESSION['subq']=="delete")
			{
				deletecat();
			}
		}
		elseif ($_SESSION['call']=="cost")
		{
			include ("./cost_maint_func.php");
			if ($_SESSION['subq']=="None")
			{
				//echo "COST";
				costing_maint_submenu();
			}
			else
			{
				costing_acc_maint_subsys();
			}
		}
		elseif ($_SESSION['call']=="inv")
		{
			include ("./cost_maint_func.php");
			if ($_SESSION['subq']=="None")
			{
				costing_maint_submenu();
				//costing_inv_maint_subsys();
			}
			elseif ($_SESSION['subq']=="add_mm2")
			{
				invadd_mm2();
			}
			elseif ($_SESSION['subq']=="add_mm3")
			{
				invadd_mm3();
			}
			else
			{
				costing_inv_maint_subsys();
			}
		}
		elseif ($_SESSION['call']=="acc")
		{
			//echo "RETAIL";
			include ("./cost_maint_func.php");
			if ($_SESSION['subq']=="None"||$_SESSION['subq']=="list")
			{
				if ($_SESSION['m_plev'] == 3)
				{
					pbpub_acc_code_list();
				}
				else
				{
					acc_code_list();
				}
			}
			elseif ($_SESSION['subq']=="pbpub")
			{
				pbpub();
			}
			elseif ($_SESSION['subq']=="add")
			{
				accessory_add();
			}
			elseif ($_SESSION['subq']=="add_rmm1")
			{
				accessory_add_rmm1();
			}
			elseif ($_SESSION['subq']=="add_rmm2")
			{
				accessory_add_rmm2($_REQUEST['catid']);
			}
			elseif ($_SESSION['subq']=="add_rmm3")
			{
				accessory_add_rmm3($_REQUEST['id']);
			}
			elseif ($_SESSION['subq']=="ins")
			{
				accessory_insert();
			}
			elseif ($_SESSION['subq']=="ed")
			{
				accessory_edit($_REQUEST['id']);
			}
			elseif ($_SESSION['subq']=="ed2")
			{
				accessory_edit2($_REQUEST['id']);
			}
			elseif ($_SESSION['subq']=="edrp")
			{
				accessory_editrp();
			}
			elseif ($_SESSION['subq']=="adj_package")
			{
				adjust_package_item();
			}
			elseif ($_SESSION['subq']=="list_package_selects")
			{
				accessory_edit($_REQUEST['id']);
			}
			elseif ($_SESSION['subq']=="add_package_item")
			{
				add_package_item();
			}
			elseif ($_SESSION['subq']=="del_package_item")
			{
				del_package_item();
			}
			elseif ($_SESSION['subq']=="lfr")
			{
				laboritemfromretail();
			}
			elseif ($_SESSION['subq']=="mfr")
			{
				materialitemfromretail();
			}
			elseif ($_SESSION['subq']=="reseq")
			{
				resequence_acc();
			}
			elseif ($_SESSION['subq']=="reseqinc")
			{
				resequence_acc_inc();
			}
			elseif ($_SESSION['subq']=="reseqdec")
			{
				resequence_acc_dec();
			}
			elseif ($_SESSION['subq']=="list_labor_cost")
			{
				accessory_edit($_REQUEST['id']);
			}
			elseif ($_SESSION['subq']=="list_mat_cost")
			{
				accessory_edit($_REQUEST['invid']);
				//inved($_REQUEST['invid'],$_REQUEST['phsid']);
			}
			elseif ($_SESSION['subq']=="add_labor_cost_item")
			{
				add_labor_cost_item($_REQUEST['rid'],$_REQUEST['cid']);
			}
			elseif ($_SESSION['subq']=="rem_labor_cost_item")
			{
				rem_labor_cost_item($_REQUEST['rid'],$_REQUEST['cid']);
			}
			elseif ($_SESSION['subq']=="add_mat_cost_item")
			{
				add_mat_cost_item($_REQUEST['rid'],$_REQUEST['cid']);
			}
			elseif ($_SESSION['subq']=="rem_mat_cost_item")
			{
				rem_mat_cost_item($_REQUEST['rid'],$_REQUEST['cid']);
			}
			elseif ($_SESSION['subq']=="copy_list")
			{
				acc_copy_list();
			}
			elseif ($_SESSION['subq']=="copy_op")
			{
				copy_operation();
			}
			elseif ($_SESSION['subq']=="search")
			{
				retail_item_search();
			}
			else
			{
				costing_trav_maint_subsys();
			}
		}
		elseif ($_SESSION['call']=="mat")
		{
			include ("./cost_maint_func.php");
			if ($_SESSION['subq']=="None"||$_SESSION['subq']=="base_cat_list")
			{
				//material_cat_list();
				material_list();
			}
			elseif ($_SESSION['subq']=="base_vendor_list")
			{
				//material_vendor_list();
				material_list();
			}
			elseif ($_SESSION['subq']=="cat_list")
			{
				material_list_by_cat($_REQUEST['catid']);
			}
			elseif ($_SESSION['subq']=="vendor_list")
			{
				material_list_by_vendor($_REQUEST['vid']);
			}
			elseif ($_SESSION['subq']=="add_by_cat")
			{
				material_add_by_cat($_REQUEST['catid']);
			}
			elseif ($_SESSION['subq']=="add_by_vendor")
			{
				material_add_by_vendor($_REQUEST['vid']);
			}
			elseif ($_SESSION['subq']=="insert_by_vendor")
			{
				material_insert_by_vendor();
			}
			elseif ($_SESSION['subq']=="insert")
			{
				material_insert();
			}
			elseif ($_SESSION['subq']=="edit")
			{
				material_edit($_REQUEST['id']);
			}
			elseif ($_SESSION['subq']=="update")
			{
				material_update($_REQUEST['id']);
			}
			elseif ($_SESSION['subq']=="list_edit")
			{
				material_update_from_list();
			}
			elseif ($_SESSION['subq']=="item_search")
			{
				material_item_search();
			}
			elseif ($_SESSION['subq']=="activematlist")
			{
				activematlist();
			}
		}
		elseif ($_SESSION['call']=="trav")
		{
			include ("./cost_maint_func.php");
			if ($_SESSION['subq']=="None")
			{
				costing_maint_submenu();
			}
			else
			{
				costing_trav_maint_subsys();
			}
		}
		elseif ($_SESSION['call']=="pbanalyze")
		{
			include ("./pb_analyze_func.php");
			admintool();
		}
		elseif ($_SESSION['call']=="procPids") {
			include ("./cost_maint_func.php");
			procPids($_REQUEST['oid']);
		}
	}
	elseif ($_SESSION['action']=="docs")
	{
		include ("./doc_func.php");
		docmatrix();
	}
	elseif ($_SESSION['action']=="accountingsystem")
	{
		include ("./accountingsystem_func.php");
		BaseMatrix();
	}
	elseif ($_SESSION['action']=="logoff")
	{
		logoff_proc();
	}
	elseif ($_SESSION['action']=="conv_slevels")
	{
		conv_slevels();
	}
	
	@events();
	
	$hostname   = "192.168.100.45";
	$username   = "jestadmin";
	$password   = "into99black";
	$dbname     = "jest";
	
	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
}

function do_website()
{
	//error_reporting(E_ALL);
	//Primary Placement Arg
	if (isset($_REQUEST['action'])) {
		$_SESSION['action']=$_REQUEST['action'];
	}
	else {
		$_SESSION['action']="None";
	}
	
	//Secondary Placement Arg
	if (isset($_REQUEST['call']))
	{
		$_SESSION['call']=$_REQUEST['call'];
	}
	else
	{
		$_SESSION['call']="None";
	}
	
	//Process Arg
	if (isset($_REQUEST['subq']))
	{
		$_SESSION['subq']=$_REQUEST['subq'];
	}
	else
	{
		$_SESSION['subq']="None";
	}

	if ($_SESSION['action']=='logoff')
	{
		//echo "Logging off....";
		logoff_proc();
	}
	else
	{
		if (isset($_SESSION['securityid']) && $_SESSION['securityid']==269999999999999999999999999999999999)
		{
		    echo '<pre>';
			print_r($_SESSION);
			echo '</pre>';
		}
		
		if (isset($_SESSION['securityid']) && $_SESSION['securityid']==269999999999999999999999)
		{
			bh_html_header_DEV();
		}
		else {
			bh_html_header();
		}
		
		menu();
		bh_html_footer();
	}
}

function sess_expired() {
	// Logoff Error 6 (Session Expired)
	bh_html_header();

	if (isset($_REQUEST['plogin']))
	{
		$id=$_REQUEST['plogin'];
	}
	else
	{
		$id=$_SESSION['plogin'];
	}

	$qry0 = "INSERT INTO jest_stats..events (evdescrip,status,sid,oid,ip) VALUES ('logoff','2','".$_SESSION['securityid']."','".$_SESSION['officeid']."','".getenv("REMOTE_ADDR")."');";
	$res0 = mssql_query($qry0);

	$qry = "DELETE FROM logstate WHERE securityid='".$_SESSION['securityid']."';";
	$res = mssql_query($qry);

	session_unset();

	echo "   </HEAD>\n";
	echo "   <BODY>\n";
	echo "		<table align=\"center\" border=\"0\">";
	echo "   		<tr><td><font color=\"red\"><b>Session Timeout </b></font></td></tr>";
	echo "			<tr><td>Contact <b>Management</b> if this Error persists. (619-233-3522) </td></tr>";
	echo "			<tr><td>Click <a href=\"".$_SERVER['PHP_SELF']."\" target=\"_top\">HERE</a> to Log Back in.</td></tr>";
	echo "		</table>";
	echo "   </BODY>\n";
	echo "</HTML>\n";

	exit;
}

function ajxEventProc($rec)
{
	
	error_reporting(E_ALL);
	
	/*
	if (is_array($_SERVER))
	{
		echo 'SERVER:<br>';
		echo '<pre>';
		print_r($_SERVER);
		echo '</pre>';
	}
	
	if (is_array($_SESSION))
	{
		echo 'SESSION:<br>';
		echo '<pre>';
		print_r($_SESSION);
		echo '</pre>';
	}
	
	if (is_array($_REQUEST))
	{
		echo 'REQUEST:<br>';
		echo '<pre>';
		print_r($_REQUEST);
		echo '</pre>';
	}
	*/
	
	if (is_array($_SERVER) and is_array($_SESSION) and is_array($_REQUEST))
	{		
		if ($_SERVER['REQUEST_METHOD']=='POST' or $rec > 0)
		{
			if (isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)
			{
				$oid=$_REQUEST['oid'];
			}
			else
			{
				$oid=0;
			}
			
			if (isset($_REQUEST['sid']) and $_REQUEST['sid']!=0)
			{
				$sid=$_REQUEST['sid'];
			}
			else
			{
				$sid=0;
			}
			
			if ($_SERVER['REQUEST_METHOD']=='GET')
			{
				$qs=$_SERVER['QUERY_STRING'];
			}
			else
			{
				$qs=json_encode($_REQUEST);
			}
			
			//$db	=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest_stats');
			//$link=mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
			//mssql_select_db($db['dbname']) or die("Table unavailable");
			
			$qry  = "insert into jest_stats..ajaxevents ";
			$qry .= "(sessionOID,sessionSID,requestOID,requestSID,REQUEST_TIME,SERVER_ADDR,HTTP_HOST,REMOTE_ADDR,REQUEST_METHOD,SCRIPT_NAME,QUERY_STRING,HTTP_USER_AGENT) values ";
			$qry .= "(". (int) $_SESSION['officeid'].",". (int) $_SESSION['securityid'].",";
			$qry .= "". $oid .",". $sid .",". (int) $_SERVER['REQUEST_TIME'] .",";
			$qry .= "'". $_SERVER['SERVER_ADDR'] ."','". $_SERVER['HTTP_HOST'] ."',";
			$qry .= "'". $_SERVER['REMOTE_ADDR'] ."','". $_SERVER['REQUEST_METHOD'] ."','". $_SERVER['SCRIPT_NAME'] ."',";
			$qry .= "'". $qs ."','". $_SERVER['HTTP_USER_AGENT'] ."');";
			$res = mssql_query($qry);
			
			//echo $qry;
		}
	}
}

function last_access()
{
	$qry0 = "SELECT securityid,rem_addr FROM logstate WHERE securityid='".$_SESSION['securityid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0==0)
	{
		sess_expired();
	}
	else
	{
		$qry = "UPDATE logstate SET acttime=getdate(),sact='".$_SESSION['action']."',brwsr='".$_SERVER['HTTP_USER_AGENT']."' WHERE securityid='".$_SESSION['securityid']."';";
		$res = mssql_query($qry);
	}
}

function s_rtimes($securityid)
{
	$qryA = "SELECT tzone FROM security WHERE securityid='$securityid';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);

	$stime=time();
	if ($rowA[0]=="EST")
	{
		$rtime=$stime+10800;
	}
	elseif ($rowA[0]=="CST")
	{
		$rtime=$stime+7200;
	}
	elseif ($rowA[0]=="MST")
	{
		$rtime=$stime+3600;
	}
	elseif ($rowA[0]=="PST")
	{
		$rtime=$stime;
	}

	$systemtime=getdate($stime);
	$remotetime=getdate($rtime);

	$smonth = $systemtime['mon'];
	$smday  = $systemtime['mday'];
	$syear  = $systemtime['year'];
	$shours = $systemtime['hours'];
	$smins  = $systemtime['minutes'];
	$ssecs  = $systemtime['seconds'];

	$rmonth = $remotetime['mon'];
	$rmday  = $remotetime['mday'];
	$ryear  = $remotetime['year'];
	$rhours = $remotetime['hours'];
	$rmins  = $remotetime['minutes'];
	$rsecs  = $remotetime['seconds'];

	//echo "<tr><td NOWRAP align=\"right\" valign=\"bottom\"><b>Your Time:</b> $rmonth/$rmday/$ryear ($rhours:$rmins:$rsecs)</td></tr>\n";
	//echo "<tr><td NOWRAP align=\"right\" valign=\"bottom\"><b>System Time:</b> $smonth/$smday/$syear ($shours:$smins:$ssecs)</td></tr>\n";
	echo "<b>Your Time:</b> $rmonth/$rmday/$ryear ($rhours:$rmins:$rsecs) ||";
	echo " <b>System Time:</b> $smonth/$smday/$syear ($shours:$smins:$ssecs)\n";

}

function postlogin()
{
	$qry = "SELECT securityid FROM logstate WHERE securityid='".$_SESSION['securityid']."';";
	$res = mssql_query($qry);
	$nrows = mssql_fetch_array($res);

	$qryA = "SELECT securityid,login FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	//echo $nrows."<br>";
	if ($nrows==0 && $rowA['securityid']==$_SESSION['securityid'])
	{
		if ($rowA['securityid']==$_SESSION['securityid'])
		{
			$qryB  = "INSERT INTO logstate (officeid,securityid,logtime,acttime,sessionid,rem_addr)";
			$qryB .= " VALUES ";
			$qryB .= "('".$_SESSION['officeid']."','".$_SESSION['securityid']."',getdate(),getdate(),'". session_id() ."','".getenv("REMOTE_ADDR")."') ;";
			//$qryB .= "('".$_SESSION['officeid']."','".$_SESSION['securityid']."',getdate(),getdate(),'".$PHPSESSID."','".getenv("REMOTE_ADDR")."') ;";
			$resB = mssql_query($qryB);

			$qryC = "UPDATE security SET curr_login=getdate() WHERE securityid='".$_SESSION['securityid']."';";
			$resC = mssql_query($qryC);

			$qryD = "INSERT INTO jest_stats..events (evdescrip,status,sid,oid,ip) VALUES ('logon','1','".$_SESSION['securityid']."','".$_SESSION['officeid']."','".getenv("REMOTE_ADDR")."');";
			$resD = mssql_query($qryD);
			
			$qryE = "INSERT INTO jest_stats..AgentLog(sid,brwsr) VALUES (".$_SESSION['securityid'].",'".$_SERVER['HTTP_USER_AGENT']."');";
			$resE = mssql_query($qryE);
		}
	}
	else
	{
		clearlogin();
	}
}

function clearlogin()
{
	$qry = "SELECT DATEDIFF(hh, acttime, getdate()) AS hours,securityid FROM logstate WHERE securityid='".$_SESSION['securityid']."';"; //Hours
	//$qry = "SELECT DATEDIFF(mi, acttime, getdate()) AS hours,securityid FROM logstate WHERE securityid='".$_SESSION['securityid']."';"; //Minutes
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($nrow==1)
	{
		$row = mssql_fetch_array($res);
		echo $row['securityid']." (".$row['hours'].")<br>";
		if ($row['hours'] > 2) // Clears any logins at 3+ hours without activity
		{
			$qry1 = "DELETE FROM logstate WHERE securityid='".$row['securityid']."';";
			$res1 = mssql_query($qry1);
			//echo $row['securityid']." (".$row['hours'].")<br>";
		}
	}
	else
	{
		$qry1 = "DELETE FROM logstate WHERE securityid='".$_SESSION['securityid']."';";
		$res1 = mssql_query($qry1);
	}
}

function checklogon($plogin,$securityid)
{
	print "<b>User Error!</b><br>";
	// print "You appear to be logged in already<br>";
	print "Click <a href=\"".$_SERVER['PHP_SELF']."?call=logoff&recid=$recid\" target=\"_top\">HERE</a> to clear current login.";
}

function logonerror($plogin,$securityid)
{
	print "<b>Logon Error!</b><br>";
	print "You appear to be logged in already or did not input your user name.<br>";
	print "Click <a href=\"".$_SERVER['PHP_SELF']."?call=logoff&recid=$recid\" target=\"_top\">HERE</a> to try again";
}

function authfailed($plogin)
{
	echo "<HTML>\n";
	echo "   <HEAD>\n";
	echo "      <TITLE>Blue Haven Pools and Spas</TITLE>\n";
	echo "      <META http-equiv=Content-Type content=text/html;charset=utf-8>\n";
	echo "      <LINK href=\"bh.css\" type=text/css rel=stylesheet>\n";
	echo "   </HEAD>\n";
	echo "</HTML>\n";

	echo "<table align=\"center\" border=\"0\">";
	echo "   <tr><td><b>Logon Error</b> (Error Type: 4)</td></tr>";
	echo "   <tr><td>Contact Management if this Error persists. (619-233-3522) </td></tr>";
	echo "   <tr><td>Click <a href=\"".$_SERVER['PHP_SELF']."\" target=\"_top\">HERE</a> to try again</td></tr>";
	echo "</table>";

	session_unregister("plogin");
}

1;
?>