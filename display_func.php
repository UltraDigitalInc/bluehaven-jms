<?php

function format_phonenumber($n)
{
	$out='';
	
	$n=preg_replace('/\.|-|\s/i','$1$2$3',trim($n));
	
	if (strlen($n)==10)
	{
		$out=substr($n,0,3).'-'.substr($n,3,3).'-'.substr($n,6,4);
	}
	elseif (strlen($n)==7)
	{
		$out=substr($n,0,3).'-'.substr($n,3,4);
	}
	else
	{
		$out=$n;
	}
	
	return $out;
}

function checkserverportstatus($srvr,$port,$timeout)
{
	// Tests Server Port Status
	$out	=array();
	
	if (isset($srvr) && isset($port) && isset($timeout))
	{
		$errno	='599';
		$errstr	=$srvr.'DB Unavailable';
		
		$sr 	= @fsockopen($srvr, $port, $errno, $errstr, $timeout);
		
		if ($sr)
		{
			$srvup	="<img src=\"images/srvup.gif\" height=\"15px\" width=\"15px\" title=\"Accounting System Operational\nContracts, Jobs, Addendums and MAS Imports may be processed.\">\n";
			$out=array($srvup,'<b>Accounting System Operational</b>',true);
			
			fclose($sr);
		}
		else
		{
			$srvdwn	="<img src=\"images/srvdown.gif\" height=\"15px\" width=\"15px\" title=\"Accounting System OFFLINE\nContracts, Jobs, Addendums and MAS Imports cannot be processed. Contact Management if this condition persists.\">\n";
			$out=array($srvdwn,'Accounting System OFFLINE',false);
		}
	}
	else
	{
		$srvunk	="<img src=\"images/srvunk.gif\" height=\"15px\" width=\"15px\" title=\"Accounting System Status Unknown\nContracts, Jobs, Addendums may NOT function correctly.\">\n";
		$out=array($srvunk,'Accounting System Status Unknown',false);
	}
	
	return $out;
}

function checkserverportstatus_OLD($srvr,$port,$timeout)
{
	// Tests Server Port Status
	$out	=array();
	$srvup	="<img src=\"images\srvup.gif\" height=\"15px\" width=\"15px\">\n";
	$srvdwn	="<img src=\"images\srvdown.gif\" height=\"15px\" width=\"15px\">\n";
	$srvunk	="<img src=\"images\srvunk.gif\" height=\"15px\" width=\"15px\">\n";
	
	if (isset($srvr) && isset($port) && isset($timeout))
	{
		$errno	='599';
		$errstr	=$srvr.'DB Unavailable';
		
		$sr 	= @fsockopen($srvr, $port, $errno, $errstr, $timeout);
		
		if ($sr)
		{
			$out=array($srvup,'Server Up',true);
			
			//echo "Server Up";
			fclose($sr);
		}
		else
		{
			//echo "<font color=\"red\">Server Down</font>";
			$out=array($srvdwn,'Server Down',false);
		}
	}
	else
	{
		//echo "Server UNK<br />";
		$out=array($srvunk,'Server Config',false);
	}
	
	return $out;
}

function system_announce()
{
	$qryA  = "SELECT * FROM systemwidemessage WHERE active='1' and officeid='0' ORDER BY added DESC;";
	$resA  = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);
	
	if ($nrowA > 0)
	{
		echo "			<table width=100% border=\"0\">\n";
		//echo "				<tr>\n";
		//echo "					<td class=\"ltgray_und\" colspan=\"2\" align=\"center\" valign=\"top\"><b>System Announcements</b></td>\n";
		//echo "				</tr>\n";
	
	   while ($rowA  = mssql_fetch_array($resA))
	   {
			echo "				<tr>\n";
			echo "					<th align=\"left\" valign=\"top\"><b>".$aid['subject']."</b></th>\n";
			echo "					<th align=\"right\" valign=\"top\">".date('m/d/Y g:i T',strtotime($rowA['added']))."</th>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td colspan=\"2\" align=\"left\" valign=\"top\">".$rowA['message']."</td>\n";
			echo "				</tr>\n";
	   }
   
	   echo "			</table>\n";
	}
}


function disp_cost_biditems($phsid,$jadd)
{
	error_reporting(E_ALL);
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;
	
	$MAS		=$_SESSION['pb_code'];
	$viewarray	=$_SESSION['viewarray'];
	$out		=0;

	if ($_SESSION['action']=="est")
	{
		$qryA = "SELECT * FROM bid_breakout WHERE officeid=".$_SESSION['officeid']." and estid=".$viewarray['estid']." and phsid=".$phsid.";";
		
		//echo $qryA.'<br>';
		$jid	= $viewarray['estid'];
	}
	elseif ($_SESSION['action']=="contract")
	{
		if ($jadd > 0)
		{
			$qryA = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' and jobid='".$viewarray['jobid']."' and jadd='".$jadd."';";
		}
		else
		{
			$qryA = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' and jobid='".$viewarray['jobid']."' and jadd='".$jadd."' and phsid='".$phsid."';";
		}
		$jid	= $viewarray['jobid'];
	}
	elseif ($_SESSION['action']=="job")
	{
		if ($jadd > 0)
		{
			$qryA = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' and njobid='".$viewarray['njobid']."' and jadd='".$jadd."';";
		}
		else
		{
			$qryA = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' and njobid='".$viewarray['njobid']."' and jadd='".$jadd."' and phsid='".$phsid."';";
		}
		$jid	= $viewarray['njobid'];
	}
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA." : ".$nrowA.'<br>';
	
	if ($nrowA!=0)
	{
		while ($rowA = mssql_fetch_array($resA))
		{
			if ($jadd > 0)
			{
				showbiditemaddnew($rowA['id'],$rowA['bprice'],$rowA['phsid'],$rowA['sdesc'],$rowA['comments'],$rowA['vendor'],$rowA['partno'],1,0,$rowA['rdbid'],$rowA['cdbid'],$jid,$viewarray['allowdel'],$rowA['jadd']);
				//showbiditemaddnew($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt);
			}
			else
			{
				//echo "ALLOW: ".$viewarray['allowdel']."<br>";
				showbiditemnew($rowA['id'],$rowA['bprice'],$rowA['phsid'],$rowA['sdesc'],$rowA['comments'],$rowA['vendor'],$rowA['partno'],1,0,$rowA['rdbid'],$rowA['cdbid'],$jid,$viewarray['allowdel'],$rowA['jadd']);
			}
			//showbiditem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt);
			$out=$out+round($rowA['bprice']);
		}
	}
	
	return $out;
}

function disp_mpa_cost($phsid,$jadd)
{
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;
	
	$MAS		=$_SESSION['pb_code'];
	$viewarray	=$_SESSION['viewarray'];
	$out		=0;
	
	//print_r($viewarray);

	if ($_SESSION['action']=="est")
	{
		$qryA = "SELECT * FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' and estid='".$viewarray['estid']."' and phsid='".$phsid."';";
		$jid	= $viewarray['estid'];
	}
	elseif ($_SESSION['action']=="contract")
	{
		if ($jadd > 0)
		{
			$qryA = "SELECT * FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' and jobid='".$viewarray['jobid']."' and jadd='".$jadd."';";
		}
		else
		{
			$qryA = "SELECT * FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' and jobid='".$viewarray['jobid']."' and jadd='".$jadd."' and phsid='".$phsid."';";
		}
		$jid	= $viewarray['jobid'];
	}
	elseif ($_SESSION['action']=="job")
	{
		if ($jadd > 0)
		{
			$qryA = "SELECT * FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' and njobid='".$viewarray['njobid']."' and jadd='".$jadd."';";
		}
		else
		{
			$qryA = "SELECT * FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' and njobid='".$viewarray['njobid']."' and jadd='".$jadd."' and phsid='".$phsid."';";
		}
		$jid	= $viewarray['njobid'];
	}
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA.'<br>';
	
	if ($nrowA!=0)
	{
		while ($rowA = mssql_fetch_array($resA))
		{
			if ($jadd > 0)
			{
				showmpaitemadd($rowA['id'],$rowA['bprice'],$rowA['phsid'],$rowA['sdesc'],$rowA['comments'],$rowA['vendor'],$rowA['partno'],1,0,$rowA['rdbid'],$rowA['cdbid'],$jid,$viewarray['allowdel'],$rowA['jadd']);
				//showbiditemaddnew($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt);
			}
			else
			{
				showmpaitem($rowA['id'],$rowA['bprice'],$rowA['phsid'],$rowA['sdesc'],$rowA['comments'],$rowA['vendor'],$rowA['partno'],1,0,$rowA['rdbid'],$rowA['cdbid'],$jid,$viewarray['allowdel'],$rowA['jadd']);
			}
			//showbiditem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt);
			$out=$out+round($rowA['bprice']);
		}
	}
	
	return $out;
}

function manphsadj_rollup_disp($oid,$cid,$jid,$jadd,$jc,$edit_disable)
{
	$MAS	=$_SESSION['pb_code'];
	$ric_ar	=array();
	$rid_ar	=array();
	$rin_ar	=array();
	$cl		=1;
	$retid	=0;
	$costid	=0;
	$pmasreq=0;
	
	if ($jadd > 0)
	{
		$joprtr=">=";
	}
	else
	{
		$joprtr="=";
	}
	
	if ($jadd > 0)
	{
		if ($_SESSION['action']=="contract")
		{
			$qry		= "SELECT pmasreq FROM jdetail WHERE officeid='".$oid."' and jobid='".$jid."' and jadd=".$jadd.";";
		}
		else
		{
			$qry		= "SELECT pmasreq FROM jdetail WHERE officeid='".$oid."' and njobid='".$jid."' and jadd=".$jadd.";";
		}
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		
		$pmasreq	=$row['pmasreq'];
	}
	
	//print_r($pmasreq);
	
	if ($_SESSION['action']=="est")
	{
		$jfield	= "estid";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$jfield	= "jobid";
	}
	else
	{
		$jfield	= "njobid";
	}
	
	if ($jadd==0)
	{
		$qryAa = "SELECT cid,mas_prep FROM cinfo WHERE officeid='".$oid."' and cid='".$cid."';";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		
		$masprep=$rowAa['mas_prep'];
	}
	else
	{
		$masprep=0;
	}
	
	$qryC = "SELECT count(id) as idcnt FROM man_phs_adj WHERE officeid='".$oid."' and ".$jfield."='".$jid."' and phsid!=0;";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$rin_ar[$retid]="Add New";
				
	if ($rowC['idcnt'] < 1)
	{
		$ric_ar[$retid]=0;
	}
	else
	{
		$ric_ar[$retid]=$rowC['idcnt'];
	}
	
	if (count($rin_ar) > 0)
	{
		echo "				<table class=\"outer\" width=\"100%\">\n";
		echo "					<tr>\n";
		echo "						<td class=\"ltgray_und\" align=\"center\"><b>Manual Phase Adjust</b></td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<td class=\"gray\" align=\"center\">\n";
		echo "							<table width=\"100%\">\n";
		echo "								<tr>\n";
		
		// Manual Phase Adjust Mechanism 
		foreach ($rin_ar as $n2 => $v2)
		{
			echo "									<td align=\"center\" valign=\"bottom\" class=\"gray\">\n";
			
			if ($jadd > 0)
			{
				if ($pmasreq >= 1)
				{
					echo "".$v2."";
					
					if ($_SESSION['action']!="est" && $rowC['idcnt'] > 0)
					{
						echo "										<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vmpa&oid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2].")";
					}
				}
				else
				{
					//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=mpaadd&officeid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&jadd=".$jadd."&pb_code=".$MAS."&rdbid=".$n2."&cdbid=".$costid."&costid=".$costid."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$v2."</a>\n";
					echo "<span>\n";
					echo "		<a class=\"OpenMPACostAddDialog\" href=\"#\">".$v2."</a>\n";
					echo "		<input type=\"hidden\" class=\"mpa_oid\" value=\"".$oid."\">\n";
					echo "		<input type=\"hidden\" class=\"mpa_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
					echo "		<input type=\"hidden\" class=\"mpa_act\" value=\"".$_SESSION['action']."\">\n";
					echo "		<input type=\"hidden\" class=\"mpa_cid\" value=\"".$cid."\">\n";
					echo "		<input type=\"hidden\" class=\"mpa_jid\" value=\"".$jid."\">\n";
					echo "		<input type=\"hidden\" class=\"mpa_jadd\" value=\"".$jadd."\">\n";
					echo "		<input type=\"hidden\" class=\"mpa_pbcid\" value=\"".$MAS."\">\n";
					echo "		<input type=\"hidden\" class=\"mpa_rdbid\" value=\"".$n2."\">\n";
					echo "		<input type=\"hidden\" class=\"mpa_cdbid\" value=\"".$costid."\">\n";
					echo "		<input type=\"hidden\" class=\"mpa_cstid\" value=\"".$costid."\">\n";
					echo "</span>\n";
					
					if ($_SESSION['action']!="est" && $rowC['idcnt'] > 0)
					{
						//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vmpa&oid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
						echo "<span>\n";
						echo "		<a class=\"OpenMPAViewDialog\" href=\"#\">(".$ric_ar[$n2].")</a>\n";
						echo "		<input type=\"hidden\" class=\"mpa_oid\" value=\"".$oid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_act\" value=\"".$_SESSION['action']."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_cid\" value=\"".$cid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_jid\" value=\"".$jid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_pbcid\" value=\"".$MAS."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_rdbid\" value=\"".$n2."\">\n";
						echo "</span>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2].")";
					}
				}
			}
			else
			{
				if ($jc >= 1 || $masprep > 0)
				{
					echo "".$v2."";
					
					if ($rowC['idcnt'] > 0)
					{
						//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vmpa&oid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
						echo "<span>\n";
						echo "		<a class=\"OpenMPAViewDialog\" href=\"#\">(".$ric_ar[$n2].")</a>\n";
						echo "		<input type=\"hidden\" class=\"mpa_oid\" value=\"".$oid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_act\" value=\"".$_SESSION['action']."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_cid\" value=\"".$cid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_jid\" value=\"".$jid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_pbcid\" value=\"".$MAS."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_rdbid\" value=\"".$n2."\">\n";
						echo "</span>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2].")";
					}
				}
				else
				{
					if ($edit_disable==0)
					{
						//echo "										<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=mpaadd&officeid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&jadd=".$jadd."&pb_code=".$MAS."&rdbid=".$n2."&cdbid=".$costid."&costid=".$costid."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$v2."</a>\n";
						echo "<span>\n";
						echo "		<a class=\"OpenMPACostAddDialog\" href=\"#\">".$v2."</a>\n";
						echo "		<input type=\"hidden\" class=\"mpa_oid\" value=\"".$oid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_act\" value=\"".$_SESSION['action']."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_cid\" value=\"".$cid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_jid\" value=\"".$jid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_jadd\" value=\"".$jadd."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_pbcid\" value=\"".$MAS."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_rdbid\" value=\"".$n2."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_cdbid\" value=\"".$costid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_cstid\" value=\"".$costid."\">\n";
						echo "</span>\n";
					}
					else
					{
						echo "".$v2."";
					}
					
					if ($rowC['idcnt'] > 0 and $edit_disable==0)
					{
						//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vmpa&oid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
						echo "<span>\n";
						echo "		<a class=\"OpenMPAViewDialog\" href=\"#\">(".$ric_ar[$n2].")</a>\n";
						echo "		<input type=\"hidden\" class=\"mpa_oid\" value=\"".$oid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_act\" value=\"".$_SESSION['action']."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_cid\" value=\"".$cid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_jid\" value=\"".$jid."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_pbcid\" value=\"".$MAS."\">\n";
						echo "		<input type=\"hidden\" class=\"mpa_rdbid\" value=\"".$n2."\">\n";
						echo "</span>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2].")";
					}
				}
			}
			
			echo "									</td>\n";
		}
	
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
	}
}

function costadj_rollup_disp($oid,$cid,$jid,$jadd,$jc)
{
	$MAS	=$_SESSION['pb_code'];
	$ric_ar	=array();
	$rid_ar	=array();
	$rin_ar	=array();
	$btype	=33;
	$cl		=1;
	$costid	=0;
	$pmasreq=0;
	$edit_disable=0;
	
	/*if ($_SESSION['securityid']==26)
	{
		echo $cid.'<br>';
	}*/
	
	if ($jadd > 0)
	{
		$joprtr=">=";
	}
	else
	{
		$joprtr="=";
	}
	
	if ($jadd > 0)
	{
		if ($_SESSION['action']=="contract")
		{
			$qry		= "SELECT pmasreq FROM jdetail WHERE officeid='".$oid."' and jobid='".$jid."' and jadd=".$jadd.";";
		}
		else
		{
			$qry		= "SELECT pmasreq FROM jdetail WHERE officeid='".$oid."' and njobid='".$jid."' and jadd=".$jadd.";";
		}
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		
		//echo $qry.'<br>';
		
		$pmasreq	=$row['pmasreq'];
	}
	
	//print_r($pmasreq);
	
	if ($_SESSION['action']=="est")
	{
		$qryZ = "SELECT jobid,njobid FROM cinfo WHERE officeid=".$oid." and cid=".$cid.";";
		$resZ = mssql_query($qryZ);
		$rowZ = mssql_fetch_array($resZ);
		
		if ($rowZ['jobid']!='0')
		{
			$edit_disable=1;
		}
		
		$jtype	= "bid_breakout";
		$jfield	= "estid";
		$ifield	= "rdbid";
		$cfield	= "id";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$jtype	= "jbids_breakout";
		$jfield	= "jobid";
		$ifield	= "rdbid";
		$cfield	= "id";
	}
	else
	{
		$jtype	= "jbids_breakout";
		$jfield	= "njobid";
		$ifield	= "rdbid";
		$cfield	= "id";
	}
	
	if ($_SESSION['action']=="est")
	{
		$qryA		= "SELECT * FROM est_acc_ext WHERE officeid='".$oid."' and estid='".$jid."';";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qryA		= "SELECT officeid,jobid,njobid,estdata FROM jdetail WHERE officeid='".$oid."' and jobid='".$jid."' and jadd".$joprtr.$jadd.";";
	}
	else
	{
		$qryA		= "SELECT officeid,jobid,njobid,estdata FROM jdetail WHERE officeid='".$oid."' and njobid='".$jid."' and jadd".$joprtr.$jadd.";";
	}
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if ($jadd==0)
	{
		//$qryAa = "SELECT cid,mas_prep FROM cinfo WHERE officeid='".$oid."' and ".$jfield."='".$jid."';";
		$qryAa = "SELECT cid,mas_prep FROM cinfo WHERE officeid='".$oid."' and cid='".$cid."';";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		
		$masprep=$rowAa['mas_prep'];
	}
	else
	{
		$masprep=0;
	}
	
	if ($_SESSION['securityid']==269999999999999999999999)
		{
			//echo $qryZ.'<br>';
			echo $edit_disable;
			echo '<br>';
			echo $jadd;
			echo '<br>';
			echo $masprep;
		}
	
	//echo $qryA.'<br>';
	$ri_ar=explode(",",$rowA['estdata']);
	
	foreach ($ri_ar as $n1 => $v1)
	{
		$rii_ar=explode(":",$v1);
		
		if (isset($rii_ar[0]))
		{
			$qryB = "SELECT id,aid,qtype,item FROM [".$MAS."acc] WHERE officeid='".$oid."' and id='".$rii_ar[0]."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			
			if ($rowB['qtype']==$btype && $rii_ar[0]==$rowB['id'])
			{
				$qryC = "SELECT count(".$cfield.") as idcnt FROM ".$jtype." WHERE officeid='".$oid."' and ".$jfield."='".$jid."' and ".$ifield."='".$rii_ar[0]."' and phsid!=0;";
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_array($resC);
				
				$rin_ar[$rii_ar[0]]=$rowB['item'];
				
				if ($rowC['idcnt'] < 1)
				{
					$ric_ar[$rii_ar[0]]=array(0);
				}
				else
				{
					$ric_ar[$rii_ar[0]]=array($rowC['idcnt']);
				}
			}
		}
	}
	
	echo "	<table class=\"objHidable\" width=\"100%\">\n";
	echo "		<tr>\n";
	echo "			<td width=\"125px\" align=\"left\" valign=\"top\">\n";

	manphsadj_rollup_disp($oid,$cid,$jid,$jadd,$jc,$edit_disable);
	
	echo "			</td>\n";
	echo "			<td align=\"left\">\n";
	echo "				<table class=\"outer\" width=\"100%\">\n";
	echo "					<tr>\n";
	echo "						<td class=\"ltgray_und\" align=\"left\"><b>Retail Bid Items on this Design</b> (Click on the Name to Add Cost)</td>\n";
	echo "					</tr>\n";
	
	if (count($rin_ar) > 0)
	{		
		echo "					<tr>\n";
		echo "						<td class=\"gray\" align=\"left\">\n";
		echo "							<table width=\"100%\">\n";
		echo "								<tr>\n";
		
		// Bid Cost Mechanism / Manual Phase Adjust
		$ij=0;
		foreach ($rin_ar as $n2 => $v2)
		{			
			if ($ij >= 5)
            {
                $ij=0;
            }
			
			echo "									<td align=\"left\" valign=\"bottom\" class=\"gray\">\n";
			
			if ($jadd > 0)
			{
				if ($pmasreq >= 1)
				{
					echo "".$v2."";
					
					if ($ric_ar[$n2][0] > 0 and $edit_disable==0)
					{
						//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vac&oid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2][0].")</a>\n";
						echo "<span>\n";
						echo "		<a class=\"OpenBidCostViewDialog\" href=\"#\">(".$ric_ar[$n2][0].")</a>\n";
						echo "		<input type=\"hidden\" class=\"bca_oid\" value=\"".$oid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_act\" value=\"".$_SESSION['action']."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_cid\" value=\"".$cid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_jid\" value=\"".$jid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_pbcid\" value=\"".$MAS."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_rdbid\" value=\"".$n2."\">\n";
						echo "</span>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2][0].")";
					}
				}
				else
				{
					//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=bidadd&officeid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&jadd=".$jadd."&pb_code=".$MAS."&rdbid=".$n2."&cdbid=".$costid."&costid=".$costid."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$v2."</a>\n";
					echo "<span>\n";
					echo "		<a class=\"OpenBidCostAddDialog\" href=\"#\">".$v2."</a>\n";
					echo "		<input type=\"hidden\" class=\"bca_oid\" value=\"".$oid."\">\n";
					echo "		<input type=\"hidden\" class=\"bca_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
					echo "		<input type=\"hidden\" class=\"bca_act\" value=\"".$_SESSION['action']."\">\n";
					echo "		<input type=\"hidden\" class=\"bca_cid\" value=\"".$cid."\">\n";
					echo "		<input type=\"hidden\" class=\"bca_jid\" value=\"".$jid."\">\n";
					echo "		<input type=\"hidden\" class=\"bca_jadd\" value=\"".$jadd."\">\n";
					echo "		<input type=\"hidden\" class=\"bca_pbcid\" value=\"".$MAS."\">\n";
					echo "		<input type=\"hidden\" class=\"bca_rdbid\" value=\"".$n2."\">\n";
					echo "		<input type=\"hidden\" class=\"bca_cdbid\" value=\"".$costid."\">\n";
					echo "		<input type=\"hidden\" class=\"bca_cstid\" value=\"".$costid."\">\n";
					echo "</span>\n";
					
					if ($ric_ar[$n2][0] > 0 and $edit_disable==0)
					{
						//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vac&oid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2][0].")</a>\n";
						echo "<span>\n";
						echo "		<a class=\"OpenBidCostViewDialog\" href=\"#\">(".$ric_ar[$n2][0].")</a>\n";
						echo "		<input type=\"hidden\" class=\"bca_oid\" value=\"".$oid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_act\" value=\"".$_SESSION['action']."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_cid\" value=\"".$cid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_jid\" value=\"".$jid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_pbcid\" value=\"".$MAS."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_rdbid\" value=\"".$n2."\">\n";
						echo "</span>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2][0].")";
					}
				}
			}
			else
			{
				if ($jc >= 1 || $masprep > 0)
				{
					echo "".$v2."";
					
					if ($ric_ar[$n2][0] > 0 and $edit_disable==0)
					{
						//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vac&oid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2][0].")</a>\n";
						echo "<span>\n";
						echo "		<a class=\"OpenBidCostViewDialog\" href=\"#\">(".$ric_ar[$n2][0].")</a>\n";
						echo "		<input type=\"hidden\" class=\"bca_oid\" value=\"".$oid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_act\" value=\"".$_SESSION['action']."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_cid\" value=\"".$cid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_jid\" value=\"".$jid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_pbcid\" value=\"".$MAS."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_rdbid\" value=\"".$n2."\">\n";
						echo "</span>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2][0].")";
					}
				}
				else
				{
					if ($edit_disable==0)
					{
						//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=bidadd&officeid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&jadd=".$jadd."&pb_code=".$MAS."&rdbid=".$n2."&cdbid=".$costid."&costid=".$costid."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$v2."</a>\n";
						echo "<span>\n";
						echo "		<a class=\"OpenBidCostAddDialog\" href=\"#\">".$v2."</a>\n";
						echo "		<input type=\"hidden\" class=\"bca_oid\" value=\"".$oid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_act\" value=\"".$_SESSION['action']."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_cid\" value=\"".$cid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_jid\" value=\"".$jid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_jadd\" value=\"0\">\n";
						echo "		<input type=\"hidden\" class=\"bca_pbcid\" value=\"".$MAS."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_rdbid\" value=\"".$n2."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_cdbid\" value=\"".$costid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_cstid\" value=\"".$costid."\">\n";
						echo "</span>\n";
						
					}
					else
					{
						echo "".$v2."";
					}
					
					if ($ric_ar[$n2][0] > 0 and $edit_disable==0)
					{
						//echo "<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vac&oid=".$oid."&cid=".$cid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2][0].")</a>\n";
						echo "<span>\n";
						echo "		<a class=\"OpenBidCostViewDialog\" href=\"#\">(".$ric_ar[$n2][0].")</a>\n";
						echo "		<input type=\"hidden\" class=\"bca_oid\" value=\"".$oid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_sid\" value=\"".md5($_SESSION['securityid'])."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_act\" value=\"".$_SESSION['action']."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_cid\" value=\"".$cid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_jid\" value=\"".$jid."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_pbcid\" value=\"".$MAS."\">\n";
						echo "		<input type=\"hidden\" class=\"bca_rdbid\" value=\"".$n2."\">\n";
						echo "</span>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2][0].")";
					}
				}
			}
			
			echo "									</td>\n";
			
			if ($ij == 4)
            {
                echo "</tr>\n";
                echo "<tr>\n";
            }
            
			$ij++;
		}
	
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</td>\n";
		echo "					</tr>\n";
	}
	else
	{
		echo "					<tr>\n";
		echo "						<td class=\"gray\" align=\"left\">".count($rin_ar)." Bid Items</td>\n";
		echo "					</tr>\n";
	}
	
	echo "				</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function pbvalidate_msg($pbvalidate,$bdate,$edate)
{
	if ($pbvalidate==1) {
		echo "		<tr>\n";
		echo "			<td align=\"center\">\n";
		echo "				<table>\n";
		echo "					<tr>\n";
		echo "						<td align=\"center\">\n";
		echo "							You have not Validated your Pricebook within the ".date("m/d/Y",strtotime($bdate))." to ".date("m/d/Y",strtotime($edate))." timeframe. <br> Click the Maintenance -> Pricebook -> PB Analyze buttons and Validate your Pricebook.\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
	}
}

function set_digdate() {
	//error_reporting(E_ALL);
	//ini_set('display_errors','On');
	
	if (empty($_REQUEST['digdate']) && isset($_REQUEST['chkdig']) && $_REQUEST['chkdig']==1) {
		$qry = "UPDATE jobs SET digdate=NULL,digsec='".$_SESSION['securityid']."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
		$res = mssql_query($qry);
		
		DeleteAllCommissions($_SESSION['officeid'],$_REQUEST['jobid']);
		chistory_list();
	}
	else
	{
		$err = "<font color=\"red\">ERROR</font> Date Incorrect or Validate isn't checked.";
		$isvaliddate	=valid_date($_REQUEST['digdate']);
		
		/*if (strlen($_REQUEST['digdate']) < 6 && isset($_REQUEST['chkdig']) && $_REQUEST['chkdig']==1)
		{
			$qry = "UPDATE jobs SET digdate=NULL,digsec='".$_SESSION['securityid']."' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
			$res = mssql_query($qry);
			
			DeleteAllCommissions($_SESSION['officeid'],$_REQUEST['jobid']);
	
			chistory_list();
		}
		else*/
		
		if ($isvaliddate==1 && isset($_REQUEST['chkdig']) && $_REQUEST['chkdig']==1)
		{
			$dd	=$_REQUEST['digdate']." 00:01";
			$ct	=strtotime($_REQUEST['cdate']);
			$dt	=strtotime($_REQUEST['digdate']);
	
			if ($dt >= $ct)
			{
				$qrypre0 = "SELECT estid,jobid,added FROM est WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
				$respre0 = mssql_query($qrypre0);
				$rowpre0 = mssql_fetch_array($respre0);
				
				$qrypreA = "SELECT jobid,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='0';";
				$respreA = mssql_query($qrypreA);
				$rowpreA = mssql_fetch_array($respreA);
				
				$qrypreB = "SELECT jobid,securityid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
				$respreB = mssql_query($qrypreB);
				$rowpreB = mssql_fetch_array($respreB);
				
				$qrypreC = "SELECT securityid,newcommdate FROM security WHERE securityid='".$rowpreB['securityid']."';";
				$respreC = mssql_query($qrypreC);
				$rowpreC = mssql_fetch_array($respreC);
				
				if (isset($_REQUEST['setupd']) && $_REQUEST['setupd']==1)
				{
					$qrypreD = "SELECT count(hid) as chid FROM jest..CommissionHistory WHERE oid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
					$respreD = mssql_query($qrypreD);
					$rowpreD = mssql_fetch_array($respreD);
					
					if ($rowpreD['chid'] == 0 && strtotime($rowpre0['added']) < strtotime($rowpreC['newcommdate']))
					{
						//if ($_SESSION['securityid']==26)
						//{
						//	echo 'DEBUG Info: Calling Rewrite...';
						//}
						
						PullandStoreSingleCommission($_SESSION['officeid'],$_REQUEST['jobid']);
					}
				}
				else
				{
					$qry = "UPDATE jobs SET digdate='".$dd."',digsec='".$_SESSION['securityid']."' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
					$res = mssql_query($qry);
				
					//echo 'Deleting...';
					DeleteAllCommissions($_SESSION['officeid'],$_REQUEST['jobid']);
					
					if (strtotime($rowpre0['added']) >= strtotime($rowpreC['newcommdate']))
					{
						//echo 'Calling New...';
						PullStoreCommissions($_SESSION['officeid'],$_REQUEST['jobid']);
					}
					else
					{
						//echo 'Calling Old...';
						PullandStoreSingleCommission($_SESSION['officeid'],$_REQUEST['jobid']);
					}
				}
				
				chistory_list();
			}
			else
			{
				//echo "ONE<BR>";
				echo $err;
			}
		}
		else
		{
			//echo E_ERROR;
			//echo "TWO<BR>";
			echo $err;
		}
	}
}

function set_clsdate()
{
	$err = "<font color=\"red\"><b>ERROR</b></font> Date Incorrect or Validate isn't checked.";
	$isvaliddate=valid_date($_REQUEST['clsdate']);
	if (isset($_REQUEST['clsdate']) && $isvaliddate==1 && isset($_REQUEST['chkcls']) && $_REQUEST['chkcls']==1)
	{
		$ct	=strtotime($_REQUEST['cdate']);
		$dt	=strtotime($_REQUEST['clsdate']);
		if ($dt >= $ct)
		{
			$qry = "UPDATE jobs SET closed='".$_REQUEST['clsdate']."',closesec='".$_SESSION['securityid']."' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
			$res = mssql_query($qry);

			chistory_list();
		}
		else
		{
			echo $err;
		}
	}
	else
	{
		//echo E_ERROR;
		echo $err;
	}
}

function set_condate()
{
	$err = "<font color=\"red\"><b>ERROR</b></font> Date Incorrect or Validate isn't checked.";
	$isvaliddate=valid_date($_REQUEST['condate']);
	if (isset($_REQUEST['condate']) && $isvaliddate==1 && isset($_REQUEST['chkcon']) && $_REQUEST['chkcon']==1)
	{
		$qry = "UPDATE jdetail SET contractdate='".$_REQUEST['condate']."' WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['jtid']."';";
		$res = mssql_query($qry);

		chistory_list();
	}
	else
	{
		//echo E_ERROR;
		echo $err;
	}
}

function chistory_add()
{
	//show_post_vars();
	//echo "<p>";
	
	if (empty($_REQUEST['tranid']) || $_REQUEST['tranid']==0)
	{
		echo "Transition Error. Exiting...";
		exit;
	}
	
	if (empty($_REQUEST['mtext']))
	{
		echo "Empty Comment Text<br>Click BACK and Enter fill out the Comments Box.";
		exit;
	}
	
	if (empty($_REQUEST['commentflag']))
	{
		$cmtflg_ar=array('C','0');
	}
	else
	{
		if ($_REQUEST['commentflag']=='0')
		{
			echo "Select an appropriate Comment Type.";
			exit;
		}
		else
		{
			$cmtflg_ar=explode(":",$_REQUEST['commentflag']);
		}
	}

	$qry = "SELECT * FROM chistory WHERE custid='".$_REQUEST['cid']."' and tranid='".$_REQUEST['tranid']."';";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($nrow > 0)
	{
		echo "<font color=\"red\">ERROR!</font> Duplicate Entry Found.";
		chistory_list();
	}
	else
	{
		$qryA = "SELECT officeid,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		if ($rowA['finan_off']==1)
		{
			//echo "OID POST<br>";
			$oid=$_REQUEST['officeid'];
			$action="fin";
		}
		else
		{
			//echo "OID SESS<br>";
			$oid=$_SESSION['officeid'];
			$action=$_REQUEST['action'];
		}
		
		if (is_array($cmtflg_ar))
		{
			if ($cmtflg_ar[0]=="C")
			{
				if ($cmtflg_ar[1]==1)
				{
					$inputtext="Complaint Created.\r".$_REQUEST['mtext'];
					$action="Complaint";
					$complaint=1;
					$cservice=0;
					$followup=0;
					$resolve=0;
					$relid=0;
				}
				else
				{
					$inputtext=$_REQUEST['mtext'];
					$complaint=0;
					$cservice=0;
					$followup=0;
					$resolve=0;
					$relid=0;
				}
			}
			elseif ($cmtflg_ar[0]=="S")
			{
				if ($cmtflg_ar[1]==1)
				{
					$inputtext="Service Request Created.\r".$_REQUEST['mtext'];
					$action="Service";
					$complaint=0;
					$cservice=1;
					$followup=0;
					$resolve=0;
					$relid=0;
				}
				else
				{
					$inputtext=$_REQUEST['mtext'];
					$complaint=0;
					$followup=0;
					$resolve=0;
					$relid=0;
				}
			}
			elseif ($cmtflg_ar[0]=="CF")
			{
				$inputtext="Complaint Followup.\r".$_REQUEST['mtext'];
				$action="Followup";
				$complaint=1;
				$cservice=0;
				$followup=1;
				$resolve=0;
				$relid=$cmtflg_ar[1];
			}
			elseif ($cmtflg_ar[0]=="CR")
			{
				$inputtext="Complaint Resolved.\r".$_REQUEST['mtext'];
				$action="Resolved";
				$complaint=1;
				$cservice=0;
				$followup=1;
				$resolve=1;
				$relid=$cmtflg_ar[1];
			}
			elseif ($cmtflg_ar[0]=="SF")
			{
				$inputtext="Service Followup.\r".$_REQUEST['mtext'];
				$action="Followup";
				$complaint=0;
				$cservice=1;
				$followup=1;
				$resolve=0;
				$relid=$cmtflg_ar[1];
			}
			elseif ($cmtflg_ar[0]=="SR")
			{
				$inputtext="Service Resolved.\r".$_REQUEST['mtext'];
				$action="Resolved";
				$complaint=0;
				$cservice=1;
				$followup=1;
				$resolve=1;
				$relid=$cmtflg_ar[1];
			}
		}
		
		$qry0  = "INSERT INTO chistory (officeid,secid,custid,act,tranid,mtext,complaint,followup,resolved,relatedcomplaint,cservice) ";
		$qry0 .= "VALUES ";
		$qry0 .= "('".$oid."','".$_SESSION['securityid']."','".$_REQUEST['cid']."','".$action."','".$_REQUEST['tranid']."','".htmlspecialchars($inputtext,ENT_QUOTES)."',".$complaint.",".$followup.",".$resolve.",".$relid.",".$cservice.");";
		$res0  = mssql_query($qry0);
		
		$qry1  = "UPDATE cinfo set updated=getdate() ";
		$qry1 .= "WHERE cid='".$_REQUEST['cid']."';";
		$res1  = mssql_query($qry1);

		if (!empty($_REQUEST['action']))
		{
			chistory_list();
		}
	}
}

function chistory_addOLD()
{
	if (empty($_REQUEST['tranid']) || $_REQUEST['tranid']==0)
	{
		echo "Transition Error. Exiting...";
		exit;
	}
	
	if (empty($_REQUEST['mtext']))
	{
		echo "Empty Comment Text<br>Click BACK and Enter fill out the Comments Box.";
		exit;
	}
	
	$qryA = "SELECT officeid,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if ($rowA['finan_off']==1)
	{
		//echo "OID POST<br>";
		$oid=$_REQUEST['officeid'];
		$action="fin";
	}
	else
	{
		//echo "OID SESS<br>";
		$oid=$_SESSION['officeid'];
		$action=$_REQUEST['action'];
	}

	$qry = "SELECT * FROM chistory WHERE officeid='".$oid."' AND custid='".$_REQUEST['cid']."' and tranid='".$_REQUEST['tranid']."';";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($nrow > 0)
	{
		echo "<font color=\"red\">ERROR!</font> Duplicate Entry Found.";
	}
	else
	{
		$qry0  = "INSERT INTO chistory (officeid,secid,custid,act,tranid,mtext) ";
		$qry0 .= "VALUES ";
		$qry0 .= "('".$oid."','".$_SESSION['securityid']."','".$_REQUEST['cid']."','".$action."','".$_REQUEST['tranid']."','".replacequote($_REQUEST['mtext'])."');";
		$res0  = mssql_query($qry0);
		
		$qry1  = "UPDATE cinfo set updated=getdate() WHERE officeid='".$oid."' and cid='".$_REQUEST['cid']."';";
		$res1  = mssql_query($qry1);

		if (!empty($_REQUEST['action']))
		{
			chistory_list();
		}
	}
}

function chistory_list()
{
	$tranid=time().".".$_REQUEST['cid'].".".$_SESSION['securityid'];
	$sdate = '';
	$udate = '';
	$fdate = '';
	$fudate= '';
	$fdadate='';
	
	$qry1 = "SELECT securityid,officeid FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qryA = "SELECT officeid,finan_off,finan_from,fsenable,fscustomer,enquickbooks,constructiondates FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if ($rowA['finan_off']==1)
	{
		$oid=$_REQUEST['officeid'];
	}
	else
	{
		$oid=$_SESSION['officeid'];
	}
	
	$qry = "SELECT C.* FROM cinfo AS C WHERE C.officeid='".$oid."' AND C.cid='".$_REQUEST['cid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qryB = "SELECT estid,officeid,cid,esttype,added,updated FROM est WHERE officeid=".$row['officeid']." and ccid='".$row['cid']."';";
	$resB = mssql_query($qryB);
	$nrowB= mssql_num_rows($resB);

	$qryC = "SELECT * FROM offices WHERE officeid='".$oid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$qryD = "SELECT mas_div,filestoreaccess FROM security WHERE securityid='".$row['securityid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);
	
	if ($row['estid']!=0)
	{
		$qryE = "SELECT estid,added,updated FROM est WHERE officeid='".$oid."' AND estid='".$row['estid']."';";
		$resE = mssql_query($qryE);
		$rowE = mssql_fetch_array($resE);
		
		$eadate= date("m/d/Y", strtotime($rowE['added']));
		$eudate= date("m/d/Y", strtotime($rowE['updated']));
	}
	else
	{
		$eadate="";
		$eudate="";
	}
	
	if ($row['jobid']!="0")
	{
		$qryF = "SELECT jobid,added,updated FROM jdetail WHERE officeid='".$oid."' AND jobid='".$row['jobid']."';";
		$resF = mssql_query($qryF);
		$rowF = mssql_fetch_array($resF);
		
		$cadate= date("m/d/Y", strtotime($rowF['added']));
		$cudate= date("m/d/Y", strtotime($rowF['updated']));
	}
	else
	{
		$cadate='';
		$cudate='';
	}
	
	if ($row['njobid']!="0")
	{
		$qryG = "SELECT J1.njobid,J1.added,J2.digdate FROM jdetail AS J1 inner join jobs as J2 on J1.jobid=J2.jobid WHERE J1.officeid='".$oid."' AND J1.njobid='".$row['njobid']."';";
		$resG = mssql_query($qryG);
		$rowG = mssql_fetch_array($resG);
		
		$cdate=date("m/d/Y", strtotime($rowG['added']));
		$ddate=date("m/d/Y", strtotime($rowG['digdate']));
		
	}
	else
	{
		$cdate='';
		$ddate='';
	}

	if (isset($row['added']))
	{
		$sdate = date("m/d/Y", strtotime($row['added']));
	}

	if (isset($row['updated']))
	{
		$udate = date("m/d/Y", strtotime($row['updated']));
	}

	$qryS = "SELECT securityid,filestoreaccess,constructdateaccess FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$resS = mssql_query($qryS);
	$rowS = mssql_fetch_array($resS);
	
	$qrySa = "select isnull(count(F.docid),0) as tfiles from jest..jestFileStore AS F inner join jest..jestFileStoreCategory AS C on F.fscid=C.fscid where F.cid=".$row['cid']." and F.active=1 and C.slevel <=".$rowS['filestoreaccess'].";";
	$resSa = mssql_query($qrySa);
	$rowSa = mssql_fetch_array($resSa);

	$brdr=0;
	echo "<script type=\"text/javascript\" src=\"js/jquery_onesheet.js?".time()."\"></script>\n";
	echo "<input type=\"hidden\" id=\"acct_OID\" value=\"".$oid."\">\n";
	echo "<input type=\"hidden\" id=\"usr_cid\" value=\"".$row['cid']."\">\n";
	echo "<table align=\"center\" width=\"950px\" border=\"".$brdr."\">\n";
	echo "   <tr>\n";
	echo "      <td align=\"left\" valign=\"bottom\" colspan=\"2\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\">\n";
	echo " 			  <tr>\n";
	echo "				<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Customer OneSheet</b></td>\n";
	echo "				<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>\n";
	
	?>
        
        <script type="text/javascript">
            setLocalTime();
        </script>
        
    <?php
	
	echo "				</b></td>\n";
	echo "			  </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td width=\"50%\">\n";

	cinfo_display_chistory($oid,$_REQUEST['cid'],$rowC['stax']);

	echo "		</td>\n";
	echo "		<td width=\"50%\">\n";
	echo "			<table class=\"outer\" width=\"100%\" height=\"250px\">\n";

	//if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332 or $_SESSION['securityid']==1732)
	if ($rowA['enquickbooks']==1 and $row1['officeid']==89)
	{
		echo "	   			<tr>\n";
		echo "      			<td class=\"wh\" align=\"left\" valign=\"top\">\n";
		echo "						<span id=\"CustomerLifeCycle\"></span>\n";
	}
	else
	{
		echo "	   			<tr>\n";
		echo "      			<td align=\"right\" valign=\"top\" class=\"gray\">\n";
		echo "						<table align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
		echo "	   						<tr>\n";
		echo "      						<td colspan=\"4\" class=\"ltgray_und\" align=\"left\"><b>Lifecycle Info</b></td>\n";
		echo "      						<td class=\"ltgray_und\" align=\"right\">\n";
		
		HelpNode('LifeCyclePanel',2);
		
		echo "								</td>\n";
		echo "   						</tr>\n";
	
		if ($_SESSION['llev']!=0 && $row['cid']!=0)
		{
			$uid	=md5(session_id().time().$row['custid']).".".$_SESSION['securityid'];
			
			echo "	   					<tr>\n";
			echo "      						<td colspan=\"2\" class=\"gray\" align=\"left\"></td>\n";
			echo "      						<td class=\"gray\" align=\"center\"><b>Added</b></td>\n";
			echo "      						<td class=\"gray\" align=\"center\"><b>Updated</b></td>\n";
			echo "      						<td class=\"gray\" align=\"center\"></td>\n";
			echo "   					</tr>\n";
			echo "	   					<tr>\n";
			echo "      						<td align=\"right\" class=\"gray\" width=\"90\"><b>Lead</b></td>\n";
			echo "      						<td align=\"left\" class=\"gray\" width=\"100\">".$row['cid']."</td>\n";
			echo "      						<td align=\"center\" class=\"gray\">".$sdate."</td>\n";
			echo "      						<td align=\"center\" class=\"gray\">".$udate."</td>\n";
			echo "      						<td align=\"left\" class=\"gray\">\n";
			
			if ($rowA['finan_off']==0)
			{
				echo "                        <form method=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
				echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
				echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
				echo "                        </form>\n";
			}
			
			echo "								</td>\n";
			echo "   						</tr>\n";
		}
	
		if ($_SESSION['elev']!=0 && $nrowB > 0)
		{
			while ($rowB = mssql_fetch_array($resB))
			{
				echo "	   					<tr>\n";
				echo "      						<td align=\"right\" class=\"gray\" width=\"90\"><b>\n";
				
				if ($rowB['esttype']=='E')
				{
					echo 'Estimate';
				}
				else
				{
					echo 'Quote';
				}
				
				echo "</b></td>\n";
				echo "      						<td align=\"left\" class=\"gray\">\n";
				echo $rowB['estid'];
				echo "								</td>\n";
				echo "      						<td align=\"center\" class=\"gray\">".date("m/d/Y", strtotime($rowB['added']))."</td>\n";
				echo "      						<td align=\"center\" class=\"gray\">\n";
				
				if (empty($rowB['updated']) || strtotime($rowB['updated']) < strtotime('1/1/2000'))
				{
					echo "<img src=\"images/pixel.gif\">\n";
				}
				else
				{
					echo date("m/d/Y", strtotime($rowB['updated']));
				}
				
				echo "								</td>\n";
				echo "      						<td align=\"left\" class=\"gray\">\n";
				echo "                        <form name=\"viewest\" method=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$rowB['estid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"esttype\" value=\"".$rowB['esttype']."\">\n";
				
				if ($rowA['finan_off']==0)
				{
					if ($rowB['esttype']=='E')
					{
						echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Estimate\">\n";
						//echo 'Estimate';
					}
					else
					{
						echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Quote\">\n";
						//echo 'Quote';
					}
				}
				
				echo "						</form>\n";
				echo "								</td>\n";
				echo "   						</tr>\n";
			}
		}
		
		if ($_SESSION['clev']!=0 && $row['jobid']!='0')
		{
			echo "	   					<tr>\n";
			echo "      						<td align=\"right\" class=\"gray\" width=\"90\"><b>Contract</b></td>\n";
			echo "      						<td align=\"left\" class=\"gray\" width=\"100\">".$row['jobid']."</td>\n";
			echo "      						<td align=\"center\" class=\"gray\">".$cadate."</td>\n";
			echo "      						<td align=\"center\" class=\"gray\">".$cudate."</td>\n";
			echo "      						<td align=\"left\" class=\"gray\">\n";
			
			if ($rowA['finan_off']==0)
			{
				echo "                        <form method=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				echo "                           <input type=\"hidden\" name=\"jobid\" id=\"usr_jobid\" value=\"".$row['jobid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Contract\">\n";
				//echo "                           <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View\" title=\"Click to View this Contract\">\n";
				echo "                        </form>\n";
			}
			
			echo "								</td>\n";
			echo "   						</tr>\n";
		}
	
		if ($_SESSION['jlev']!=0 && $row['njobid']!='0')
		{
			$destidret  =disp_mas_div_jobid($rowD['mas_div'],$row['njobid']);
			echo "	   					<tr>\n";
			echo "      						<td align=\"right\" class=\"gray\" width=\"90\"><b>Job</b></td>\n";
			echo "      						<td align=\"left\" class=\"gray\" width=\"100\">".$destidret[0]."</td>\n";
			echo "      						<td align=\"center\" class=\"gray\">".$cadate."</td>\n";
			echo "      						<td align=\"center\" class=\"gray\">".$cudate."</td>\n";
			echo "      						<td align=\"left\" class=\"gray\">\n";
			
			if ($rowA['finan_off']==0)
			{
				echo "                        <form method=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Job\">\n";
				echo "                        </form>\n";
			}
			
			echo "								</td>\n";
			echo "   						</tr>\n";
		}
		
		if ($_SESSION['jlev']!=0 && $row['njobid']!='0' && (isset($ddate) and valid_date($ddate) and strtotime($ddate) >= strtotime('1/1/2000')))
		{
			echo "	   						<tr>\n";
			echo "      						<td align=\"right\" class=\"gray\" width=\"90\"><b>Dig Date</b></td>\n";
			echo "      						<td align=\"left\" class=\"gray\" width=\"100\"><img src=\"images/pixel.gif\"></td>\n";
			echo "      						<td align=\"center\" class=\"gray\">".$ddate."</td>\n";
			echo "      						<td align=\"center\" class=\"gray\"><img src=\"images/pixel.gif\"></td>\n";
			echo "      						<td align=\"left\" class=\"gray\"><img src=\"images/pixel.gif\"></td>\n";
			echo "   						</tr>\n";
		}
		
		if ((isset($rowA['fscustomer']) and $rowA['fscustomer'] == 1) and (isset($rowS['filestoreaccess']) and $rowS['filestoreaccess'] >= 1))
		{
			echo "	   						<tr>\n";
			echo "      						<td align=\"right\" class=\"gray\" width=\"90\"><b>Files</b></td>\n";
			echo "      						<td align=\"left\" class=\"gray\" width=\"100\">".$rowSa['tfiles']."</td>\n";
			echo "      						<td align=\"center\" class=\"gray\"><img src=\"images/pixel.gif\"></td>\n";
			echo "      						<td align=\"center\" class=\"gray\"><img src=\"images/pixel.gif\"></td>\n";
			echo "      						<td align=\"left\" class=\"gray\">\n";
			echo "									<form method=\"POST\">\n";
			echo "										<input type=\"hidden\" name=\"action\" value=\"file\">\n";
			echo "										<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
			echo "										<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
			echo "										<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Files\">\n";
			echo "									</form>\n";
			echo "								</td>\n";
			echo "   						</tr>\n";
		}
	
		echo "						</table>\n";
	}
	
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "   </tr>\n";

	$o_ar=array(55,56,69,75,144,89,97,200);
	if (
		isset($row['njobid']) and $row['njobid']!='0' and
		//(in_array($_SESSION['officeid'],$o_ar)) and
		(isset($rowA['constructiondates']) and $rowA['constructiondates'] >=1) and
		(isset($rowG['added']) and (strtotime($rowG['added']) >= strtotime('4/1/2005'))) and
		$rowS['constructdateaccess'] >= 1
		)
	{
		unset($_SESSION['constr_cid']);
		$_SESSION['constr_cid']=$_REQUEST['cid'];
		$ccuid=md5(session_id().time().$_REQUEST['cid']).".".$_SESSION['securityid'];
		echo "   <tr>\n";
		echo "      <td colspan=\"2\" align=\"left\">\n";
		echo "			<table class=\"outer\" width=\"100%\" height=\"300\">\n";
		echo "	   			<tr>\n";
		echo "      			<td align=\"center\" valign=\"top\" class=\"gray\">\n";
		echo "						<table width=\"100%\">\n";
		echo "	   						<tr>\n";
		echo "      						<td class=\"ltgray_und\" align=\"left\"><b>Construction Dates</b></td>\n";
		echo "      						<td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "      						<td class=\"ltgray_und\" align=\"right\">\n";
		
		if (isset($rowS['constructdateaccess']) and $rowS['constructdateaccess'] >= 5)
		{
			echo "									<table>\n";
			echo "	   									<tr>\n";
			echo "      									<td>Job Progess Report</td>\n";
			echo "      									<td>\n";
			echo "												<form method=\"post\">\n";
			echo "													<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "													<input type=\"hidden\" name=\"call\" value=\"jobprogress\">\n";
			echo "													<input class=\"transnb\" type=\"image\" src=\"images/application_view_list.png\" alt=\"Job Progress\">\n";
			echo "												</form>\n";
			echo "											</td>\n";
			echo "   									</tr>\n";
			echo "   								</table>\n";
		}
		
		echo "								</td>\n";
		echo "   						</tr>\n";
		echo "	   						<tr>\n";
		echo "      						<td colspan=\"2\" class=\"gray\" align=\"left\" valign=\"top\">\n";
		
		constructiondates_display($_REQUEST['cid'],$row['jobid'],$rowS['constructdateaccess']);
		
		echo "								</td>\n";
		echo "      						<td class=\"gray\" align=\"left\" valign=\"top\">\n";
		echo "									<table width=\"100%\">\n";
		echo "	   									<tr>\n";
		echo "      									<td align=\"left\"><b>Construction/Receivable Comments</b></td>\n";
		echo "   									</tr>\n";
		
		if (isset($rowS['constructdateaccess']) and $rowS['constructdateaccess'] >= 5)
		{
			echo "	   									<tr>\n";
			echo "      									<td>\n";
			echo "												<form method=\"post\">\n";
			echo "													<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "													<input type=\"hidden\" name=\"call\" value=\"construction_comments_add\">\n";
			echo "													<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
			echo "													<input type=\"hidden\" name=\"ccuid\" value=\"".$ccuid."\">\n";
			echo "													<textarea name=\"mcomment\" id=\"mcomment\" cols=\"65\" rows=\"2\"></textarea>\n";
			echo "													<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Comment\" onClick=\"return EmptyFieldAlert('mcomment');\">\n";
			echo "												</form>\n";
			echo "											</td>\n";
			echo "   									</tr>\n";
		}
		
		echo "	   									<tr>\n";
		echo "      									<td>\n";
		echo "												<iframe src=\"http://jms.bhnmi.com/subs/comments_construction.php\" frameborder=\"0\" width=\"100%\" height=\"300px\" align=\"left\"></iframe>\n";
		echo "											</td>\n";
		echo "   									</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "   	</td>\n";
		echo "   </tr>\n";
	}
	elseif (!in_array($_SESSION['officeid'],$o_ar))
	{
		echo "   <tr>\n";
		echo "      <td colspan=\"2\" align=\"left\">\n";
		echo "			<table class=\"outer\" width=\"100%\" height=\"170\">\n";
		echo "	   			<tr>\n";
		echo "      			<td align=\"center\" valign=\"top\" class=\"gray\">\n";
		echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
		echo "	   					<tr>\n";
		echo "      						<td colspan=\"2\" class=\"ltgray_und\" align=\"left\"><b>Construction Dates</b></td>\n";
		echo "      						<td class=\"ltgray_und\" align=\"right\">\n";
	
		HelpNode('ConstructionDatesPanel',4);
	
		echo "							</td>\n";
		echo "   					</tr>\n";
		echo "	   					<tr>\n";
		echo "      						<td colspan=\"3\" class=\"gray\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "   					</tr>\n";
	
		$jddate = "";
		$jcdate = "";
		$jadate = "";
		$judate = "";
		$jtdate = "";
		
		$qry2 = "SELECT digdate,closed,added,updated FROM jobs WHERE officeid='".$oid."' AND njobid='".$row['njobid']."';";
		$res2 = mssql_query($qry2);
		$row2 = mssql_fetch_array($res2);
	
		$qry3 = "SELECT contractdate,id FROM jdetail WHERE officeid='".$oid."' AND jobid='".$row['jobid']."' AND jadd='0';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);
	
		if (isset($row2['added']))
		{
			$jadate = date("m/d/Y", strtotime($row2['added']));
		}
	
		if (isset($row2['updated']))
		{
			$judate = date("m/d/Y", strtotime($row2['updated']));
		}
	
		if ($row['jobid']!=0)
		{
			if (isset($row3['contractdate']))
			{
				$jtdate = date("m/d/Y", strtotime($row3['contractdate']));
			}
	
			echo "	   					<tr>\n";
			echo "      						<td class=\"gray\" align=\"right\" width=\"90\"><b>Contract Date:</b> </td>\n";
	
			if ($_SESSION['elev'] >=99 && $_SESSION['clev'] >=99 && $_SESSION['jlev'] >=99)
			{
				echo "									<form method=\"post\">\n";
				echo "										<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";
				echo "										<input type=\"hidden\" name=\"call\" value=\"set_condate\">\n";
				echo "										<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
				echo "										<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
				echo "										<input type=\"hidden\" name=\"jtid\" value=\"".$row3['id']."\">\n";
				echo "										<input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
				echo "										<input type=\"hidden\" name=\"jobid\" value=\"".$row['jobid']."\">\n";
				echo "										<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				echo "      						<td align=\"left\" class=\"gray\" width=\"100\">\n";
				echo "      							<input type=\"text\" size=\"15\" name=\"condate\" id=\"d1\" value=\"".$jtdate."\">\n";
				echo "								</td>\n";
				
				if ($rowA['finan_off']!=1)
				{
					echo "      						<td align=\"left\" class=\"gray\">\n";
					echo "                           		<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" title=\"Check the side Box and Click this Button to change the Contract Date (Admin Level Only)\">\n";
					echo "									<input class=\"transnb\" type=\"checkbox\" name=\"chkcon\" value=\"1\">\n";
					echo "								</td>\n";
				}
				else
				{
					echo "      						<td align=\"left\" class=\"gray\">\n";
					echo "								</td>\n";				
				}
				
				echo "									</form>\n";
			}
			else
			{
				echo "      						<td align=\"left\" class=\"gray\" width=\"100\">\n";
				echo "      							<input type=\"text\" size=\"15\" value=\"".$jtdate."\" DISABLED>\n";
				echo "								</td>\n";
				echo "      						<td align=\"left\" class=\"gray\">\n";
				echo "								</td>\n";
			}
			
			echo "   						</tr>\n";
		}
	
		if ($row['njobid']!=0)
		{
			$dis='';
			$dtitle='Check the side Box and Click this Button to set the Dig Date';
			if (isset($row2['digdate']))
			{
				$jddate = date("m/d/Y", strtotime($row2['digdate']));
				$prd_mo	= date("m", strtotime($row2['digdate']));;
				$prd_yr	= date("Y", strtotime($row2['digdate']));
	
				$qry4	= "SELECT id FROM digreport_main WHERE officeid='".$_SESSION['officeid']."' AND rept_mo='".$prd_mo."' AND rept_yr='".$prd_yr."';";
				$res4	= mssql_query($qry4);
				$nrow4	= mssql_num_rows($res4);
	
				//echo $qry4."<br>";
				if ($nrow4 >=1)
				{
					if ($_SESSION['clev'] >= 9 && $_SESSION['jlev'] >= 9)
					{
						$dis		='';
						$setupd		="<input type=\"hidden\" name=\"setupd\" value=\"1\">\n";
					}
					else
					{
						$dis		="DISABLED";
					}
					
					$dtitle	="Dig Report Created for this Period, unable to change";
				}
			}
	
			if (isset($row2['closed']))
			{
				$jcdate = date("m/d/Y", strtotime($row2['closed']));
			}
	
			if ($_SESSION['jlev'] < 5)
			{
				$dis	="DISABLED";
				$dtitle	="Your access Level does not permit you to change this value";
			}
	
			echo "	   					<tr>\n";
			echo "      						<td class=\"gray\" align=\"right\" width=\"90\"><b>Dig Date:</b> </td>\n";
			echo "									<form method=\"post\">\n";
			echo "										<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";
			echo "										<input type=\"hidden\" name=\"call\" value=\"set_digdate\">\n";
			echo "										<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
			echo "										<input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
			echo "										<input type=\"hidden\" name=\"jobid\" value=\"".$row['jobid']."\">\n";
			echo "										<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "										<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
			echo "										<input type=\"hidden\" name=\"cdate\" value=\"".$jtdate."\">\n";
			
			if (isset($setupd) && $_SESSION['clev']>=9 && $_SESSION['jlev']>=9)
			{
				echo $setupd;
			}
			
			echo "      						<td align=\"left\" class=\"gray\" width=\"100\">\n";
			echo "      							<input type=\"text\" size=\"15\" name=\"digdate\" id=\"d2\" value=\"".$jddate."\">\n";
			echo "								</td>\n";
			echo "      						<td align=\"left\" class=\"gray\">\n";
			echo "                           		<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" ".$dis." title=\"".$dtitle."\">\n";
			echo "									<input class=\"checkbox\" type=\"checkbox\" name=\"chkdig\" value=\"1\">\n";
			echo "								</td>\n";
			echo "									</form>\n";
			echo "   						</tr>\n";
			echo "	   					<tr>\n";
			echo "      						<td class=\"gray\" align=\"right\" width=\"90\"><b>Date Complete:</b> </td>\n";
			echo "									<form method=\"post\">\n";
			echo "										<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";
			echo "										<input type=\"hidden\" name=\"call\" value=\"set_clsdate\">\n";
			echo "										<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
			echo "										<input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
			echo "										<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "										<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
			echo "										<input type=\"hidden\" name=\"cdate\" value=\"".$jtdate."\">\n";
			echo "      						<td align=\"left\" class=\"gray\" width=\"100\">\n";
			echo "      							<input type=\"text\" size=\"15\" name=\"clsdate\" id=\"d3\" value=\"".$jcdate."\">\n";
			echo "								</td>\n";
			echo "      						<td align=\"left\" class=\"gray\">\n";
	
			if ($_SESSION['jlev'] >= 5)
			{
				echo "                           		<input class=\"transnb\" type=\"image\" src=\"images/save.gif\">\n";
			}
			else
			{
				echo "                           		<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" DISABLED>\n";
			}
	
			echo "									<input class=\"checkbox\" type=\"checkbox\" name=\"chkcls\" value=\"1\">\n";
			echo "								</td>\n";
			echo "									</form>\n";
			echo "   						</tr>\n";
		}
	
		echo "						</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "   	</td>\n";
		echo "   </tr>\n";
	}
	
	onesheet_comments_display($row['cid'],$tranid);

	echo "</table>\n";
}

function onesheet_comments_display($cid,$tranid)
{
	//echo 'In<br>';
	//$qry0 = "SELECT * FROM chistory WHERE officeid='".$oid."' AND custid='".$_REQUEST['cid']."' ORDER BY mdate DESC;";
	$srch_ar=array('/=C2=A0/','/=C2=B7/','/=C2/','/C2=/','/=A0/','/=0A/','/A0/','/0A/','/=20/','/= /','/ =/','/=/','/------Original Message------/','/----- Original message -----/');
	
	$qry = "
			SELECT
				C.cid,C.jobid,C.njobid
			FROM
				cinfo as C
			WHERE
				C.cid=".$_REQUEST['cid'].";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry0 = "
			SELECT
				C.*
				,(select lname from jest..security where securityid=C.secid) as lname
				,(select substring(fname,1,1) from jest..security where securityid=C.secid) as fname
			FROM
				chistory as C
			WHERE
				C.custid='".$_REQUEST['cid']."' ORDER BY C.mdate DESC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($_SESSION['securityid']==269999999999999999999)
	{
		echo $qry0.'<br>';
	}
	
	if ($nrow0 > 0)
	{
		$cfol_ar=array();
		$cres_ar=array();
		$ccls_ar=array();
		$sfol_ar=array();
		$sres_ar=array();
		$scls_ar=array();
		while ($row0 = mssql_fetch_array($res0))
		{
			$cstatus='';
			if ($row0['complaint']==1)
			{
				if ($row0['followup']==0 && $row0['resolved']==0)
				{
					$cstatus=$row0['id'];
					
					$cfol_ar[]=$row0['id'];
					$cres_ar[]=$row0['id'];
				}
				elseif ($row0['followup']==1 && $row0['resolved']==0)
				{
					$cstatus=$row0['relatedcomplaint'];
					
					if (!in_array($row0['relatedcomplaint'],$cfol_ar))
					{
						$cfol_ar[]=$row0['relatedcomplaint'];
					}
					
					if (!in_array($row0['relatedcomplaint'],$cres_ar))
					{
						$cres_ar[]=$row0['relatedcomplaint'];
					}
				}
				elseif ($row0['resolved']==1)
				{					
					$cstatus=$row0['relatedcomplaint'];
					$ccls_ar[]=$row0['relatedcomplaint'];
				}
			}
			
			if ($row0['cservice']==1)
			{
				if ($row0['followup']==0 && $row0['resolved']==0)
				{					
					$cstatus=$row0['id'];
					$sfol_ar[]=$row0['id'];
					$sres_ar[]=$row0['id'];
				}
				elseif ($row0['followup']==1 && $row0['resolved']==0)
				{					
					$cstatus=$row0['relatedcomplaint'];
					
					if (!in_array($row0['relatedcomplaint'],$sfol_ar))
					{
						$sfol_ar[]=$row0['relatedcomplaint'];
					}
					
					if (!in_array($row0['relatedcomplaint'],$sres_ar))
					{
						$sres_ar[]=$row0['relatedcomplaint'];
					}
				}
				elseif ($row0['resolved']==1)
				{					
					$cstatus=$row0['relatedcomplaint'];
					$scls_ar[]=$row0['relatedcomplaint'];
				}
			}

			if ($row0['act']=="leads")
			{
				$stage="Lead";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="reports")
			{
				$stage="Reports";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="est")
			{
				$stage="Estimate";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="contract")
			{
				$stage="Contract";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="job")
			{
				$stage="Job";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="mas")
			{
				$stage="MAS";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="fin")
			{
				$stage="Finance";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="Complaint")
			{
				$stage="Complaint";
				$cmt_tbg="ltred_und";
			}
			elseif ($row0['act']=="Service")
			{
				$stage="Service";
				$cmt_tbg="ltblue_und";
			}
			elseif ($row0['act']=="Followup")
			{
				$stage="Followup";
				
				if ($row0['complaint']==1)
				{
					$cmt_tbg="ltred_und";	
				}
				elseif ($row0['cservice']==1)
				{
					$cmt_tbg="ltblue_und";	
				}
				else
				{
					$cmt_tbg="wh_und";
				}
			}
			elseif ($row0['act']=="Resolved")
			{
				$stage="Resolved";
				$cmt_tbg="ltgrn_und";
			}
			elseif ($row0['act']=="cresp")
			{
				$stage="Email";
				$cmt_tbg="wh_und";
			}
			
			$cmnt_ar[]=array(
								'id'=>$row0['id'],
								'officeid'=>$row0['officeid'],
								'custid'=>$row0['custid'],
								'secid'=>$row0['secid'],
								'tranid'=>$row0['tranid'],
								'act'=>$row0['act'],
								'mtext'=>preg_replace($srch_ar,' ',$row0['mtext']),
								'mdate'=>$row0['mdate'],
								'complaint'=>$row0['complaint'],
								'source'=>$row0['source'],
								'result'=>$row0['result'],
								'apptdate'=>$row0['apptdate'],
								'relatedcomplaint'=>$row0['relatedcomplaint'],
								'resolved'=>$row0['resolved'],
								'followup'=>$row0['followup'],
								'cservice'=>$row0['cservice'],
								'lname'=>$row0['lname'],
								'fname'=>$row0['fname'],
								'stage'=>$stage,
								'cmt_tbg'=>$cmt_tbg,
								'cstatus'=>$cstatus
							 );
		}
	}
	
	echo "   <tr>\n";
	echo "      <td colspan=\"2\" align=\"left\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "   			<tr>\n";
	echo "      			<td class=\"ltgray_und\" colspan=\"2\" align=\"left\"><b>New Comment / Service Ticket</b></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "      			<td class=\"gray\" colspan=\"2\" align=\"left\">\n";
	
	/*
	echo "						<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"chistory_add\">\n";
	echo "						<input type=\"hidden\" name=\"tranid\" value=\"".$tranid."\">\n";
	echo "						<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "						<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";

	if ($_REQUEST['action']=="est" and isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)
	{
		echo "						<input type=\"hidden\" name=\"estid\" value=\"".$_REQUEST['estid']."\">\n";
	}
	
	if ($_REQUEST['action']=="contract" and isset($_REQUEST['jobid']) and $_REQUEST['jobid']!='0')
	{
		echo "						<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
		
		if (isset($_REQUEST['jadd']))
		{
			echo "						<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
		}
	}
	
	if (($_REQUEST['action']=="job" or $_REQUEST['action']=="mas") and isset($_REQUEST['njobid']) and $_REQUEST['njobid']!='0')
	{
		echo "						<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		
		if (isset($_REQUEST['jadd']))
		{
			echo "						<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
		}
	}
	*/

	echo "						<table align=\"center\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\">\n";
	echo "									<table>\n";
	echo "				   						<tr>\n";
	echo "				      						<td valign=\"top\" align=\"right\">Type</td>\n";
	echo "				      						<td valign=\"top\" align=\"left\">\n";
	echo "												<div id=\"OneSheetCmntSelector\"></div>\n";	
	echo "											</td>\n";
	echo "				   						</tr>\n";
	echo "				   						<tr>\n";
	echo "      									<td align=\"left\"></td>\n";
	echo "      									<td align=\"left\">\n";
	echo "												<textarea name=\"mtext\" id=\"mtext\" rows=\"2\" cols=\"80\"></textarea>\n";
	echo "												<input class=\"transnb\" id=\"saveLeadComment\" type=\"image\" src=\"images/save.gif\" alt=\"Save Comment\">\n";
	echo "											</td>\n";
	echo "			   							</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "			   				</tr>\n";
	echo "						</table>\n";
	//echo "						</form>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\" align=\"left\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "   			<tr>\n";
	echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Comment / Service History</b></td>\n";
	echo "      			<td class=\"ltgray_und\" align=\"right\">\n";
	echo "						<div class=\"noPrint\">\n";
	echo "							<img class=\"setpointer\" id=\"expandLeadComments\" src=\"images/arrow_out.png\" title=\"Expand Comment List\">\n";
	echo "							<img class=\"setpointer\" id=\"refreshLeadComments\" src=\"images/arrow_refresh.png\" title=\"Refresh Comment List\">\n";
	echo "						</div>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "	   			<tr>\n";
	echo "      			<td align=\"center\" valign=\"top\" colspan=\"2\" class=\"gray\">\n";
	echo "						<div id=\"LeadCommentList\"></div>\n";
	echo "      			</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function onesheet_cdates_display($cid,$jobid)
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	$qry0 = "SELECT
				cid
				,jobid
				,njobid
				,(select contractamt from jdetail where jobid=C.jobid and jadd=0) as contractamt
				,(select contractdate from jdetail where jobid=C.jobid and jadd=0) as contractdate
			FROM
				jest..cinfo AS C
			WHERE
				C.jobid='".$jobid."'
		";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);

	$qry = "SELECT
				p.*
				,(select cdate from constructiondates where cid=".$cid." and phsid=p.phsid and dtype=1) as act_sdate
				,(select cdate from constructiondates where cid=".$cid." and phsid=p.phsid and dtype=2) as act_edate
				,(select cdate from constructiondates where cid=".$cid." and phsid=p.phsid and dtype=3) as act_rdate
				,(select ramt from constructiondates where cid=".$cid." and phsid=p.phsid and dtype=3) as act_ramt
			FROM
				phasebase AS p
			WHERE
				p.condate=1
			ORDER BY
				p.seqnum ASC;
			";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	//echo $qry.'<br>';
	
	if ($nrow > 0)
	{
		setlocale(LC_MONETARY, 'en_US');
		echo "<form method=\"POST\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"constructiondates_process\">\n";
		echo "<input type=\"hidden\" name=\"jobid\" value=\"".$jobid."\">\n";
		echo "<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
		echo "<table>\n";
		echo "	<tr>\n";
		echo "		<td></td>\n";
		echo "		<td colspan=\"5\" align=\"center\"><b>Construction</b></td>\n";
		echo "		<td colspan=\"2\" align=\"center\"><b>Receivable</b></td>\n";
		echo "		<td align=\"center\"></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><b>Phase</b></td>\n";
		echo "		<td align=\"center\"><b>Code</b></td>\n";
		echo "		<td align=\"center\"><b>Start Date</b></td>\n";
		echo "		<td></td>\n";
		echo "		<td align=\"center\"><b>End Date</b></td>\n";
		echo "		<td></td>\n";
		echo "		<td align=\"center\"><b>Date</b></td>\n";
		echo "		<td align=\"center\"><b>Amount</b></td>\n";
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
				if (isset($row['act_sdate']) && strtotime($row['act_sdate']) >= strtotime('1/1/2005'))
				{
					echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][sdate]\" id=\"datep".$dcnt."\" value=\"".date('m/d/Y',strtotime($row['act_sdate']))."\" size=\"10\" maxlength=\"10\">\n";
				}
				else
				{
					echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][sdate]\" id=\"datep".$dcnt."\" size=\"10\" maxlength=\"10\">\n";
				}
			}
			
			echo "		</td>\n";
			echo "		<td></td>\n";
			echo "		<td align=\"center\">\n";
			
			if (isset($row['edate']) && $row['edate']==1 && !in_array($row['phsid'],$ex_phs))
			{
				$dcnt++;
				if (isset($row['act_edate']) && strtotime($row['act_edate']) >= strtotime('1/1/2005'))
				{
					echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][edate]\" id=\"datep".$dcnt."\" value=\"".date('m/d/Y',strtotime($row['act_edate']))."\" size=\"10\" maxlength=\"10\">\n";
				}
				else
				{
					echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][edate]\" id=\"datep".$dcnt."\" size=\"10\" maxlength=\"10\">\n";
				}
			}
			
			echo "		</td>\n";
			echo "		<td></td>\n";
			echo "		<td align=\"center\">\n";
			
			if (isset($row['rdate']) && $row['rdate']==1 && !in_array($row['phsid'],$ex_phs))
			{
				$dcnt++;
				if (isset($row['act_rdate']) && strtotime($row['act_rdate']) >= strtotime('1/1/2005'))
				{
					echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][rdate]\" id=\"datep".$dcnt."\" value=\"".date('m/d/Y',strtotime($row['act_rdate']))."\" size=\"10\" maxlength=\"10\">\n";
				}
				else
				{
					echo "		<input type=\"text\" class=\"bboxbc\" name=\"condates[".$row['phsid']."][rdate]\" id=\"datep".$dcnt."\" size=\"10\" maxlength=\"10\">\n";
				}
			}
			else
			{
				if ($row['phsid']==45)
				{
					if (valid_date(date('m/d/Y',strtotime($row0['contractdate']))))
					{
						echo date('m/d/Y',strtotime($row0['contractdate']));
					}
				}
			}
			
			echo "		</td>\n";
			echo "		<td align=\"right\">\n";
			
			if (isset($row['rdate']) && $row['rdate']==1  && !in_array($row['phsid'],$ex_phs))
			{
				if (isset($row['act_ramt']) && $row['act_ramt'] > 0)
				{
					echo "		<input type=\"text\" class=\"bboxbr\" name=\"condates[".$row['phsid']."][ramt]\" id=\"ramt".$row['phsid']."\" value=\"".number_format($row['act_ramt'],2,'.','')."\" size=\"10\" maxlength=\"10\">\n";
					$pramt=$pramt + $row['act_ramt'];
				}
				else
				{
					echo "		<input type=\"text\" class=\"bboxbr\" name=\"condates[".$row['phsid']."][ramt]\" id=\"ramt".$row['phsid']."\" value=\"0\" size=\"10\" maxlength=\"10\">\n";
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
			
			if (!in_array($row['phsid'],$ex_phs))
			{
				echo "			<div class=\"noPrint\"><input class=\"transnb\" type=\"checkbox\" name=\"condates[".$row['phsid']."][clear]\" value=\"1\" title=\"Check this box and Update to remove the entries for this Phase\"></div>\n";
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
				echo "		<td>Addendum</td>\n";
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
		echo "		<td colspan=\"9\"><hr width=\"100%\"></td>\n";
		echo "	</tr>\n";
		
		echo "	<tr>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td align=\"right\"><b>Total Received<b></td>\n";
		echo "		<td align=\"right\">".number_format($pramt,2,'.','')."</td>\n";
		echo "		<td></td>\n";
		echo "	</tr>\n";
		
		echo "	<tr>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td></td>\n";
		echo "		<td align=\"right\"><b>Total Due<b></td>\n";
		echo "		<td align=\"right\">".number_format(($cramt - $pramt),2,'.','')."</td>\n";
		echo "		<td align=\"center\"><div class=\"noPrint\"><input class=\"transnb\" type=\"image\" src=\"images/save.gif\" title=\"Update\"></div></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "</form>\n"; 
	}
	
	ini_set('display_errors','Off');
}

function onesheet_financing($oid,$cid)
{
	$qryA = "SELECT officeid,finan_off,finan_from FROM offices WHERE officeid=".$oid.";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
		
	echo "			<table width=\"100%\" height=\"".$wraptablehgt."\">\n";
	echo "	   			<tr>\n";
	echo "      			<td align=\"center\" valign=\"top\" class=\"gray\">\n";
	echo "						<table width=\"100%\">\n";

	if ($rowA['finan_off']==1 || $rowA['finan_from']!=0)
	{
		echo "	   					<tr>\n";
		echo "      						<td colspan=\"2\" class=\"ltgray_und\" align=\"left\"><b>Financing</b></td>\n";
		echo "      						<td class=\"ltgray_und\" align=\"right\">\n";

		HelpNode('FinancingPanel',3);

		echo "							</td>\n";
		echo "   						</tr>\n";
		
		if ($nrow1 > 0 && strtotime($row1['lupdate']) > strtotime('1/1/1980'))
		{
			echo "	   					<tr>\n";
			echo "      						<td align=\"right\" width=\"90\"><b>Source:</b></td>\n";
			
			if ($row['finan_src']==3)
			{
				echo "                     <td align=\"left\">Cash</td>\n";
			}
			elseif ($row['finan_src']==2)
			{
				echo "                     <td align=\"left\">Customer Finance</td>\n";
			}
			elseif ($row['finan_src']==1)
			{
				echo "                     <td align=\"left\">Winners Finance</td>\n";
			}
			else
			{
				echo "                     <td align=\"left\"></td>\n";
			}
			
			echo "      						<td class=\"gray\"></td>\n";
			echo "   						</tr>\n";
			echo "	   					<tr>\n";
			echo "      						<td align=\"right\" width=\"90\"><b>Date Received:</b></td>\n";
			echo "      						<td class=\"gray\">".$fdate."</td>\n";
			echo "      						<td class=\"gray\">\n";
			echo "      							\n";
			echo "								</td>\n";
			echo "   						</tr>\n";
			echo "	   					<tr>\n";
			echo "      						<td align=\"right\" width=\"90\"><b>Date Updated:</b></td>\n";
			echo "      						<td class=\"gray\">".$fudate."</td>\n";
			echo "      						<td class=\"gray\">\n";
			echo "								</td>\n";
			echo "   						</tr>\n";
			
			if ($row1['amtfinan'] > 0)
			{
				echo "	   					<tr>\n";
				echo "      						<td align=\"right\" width=\"90\"><b>Amt Financed:</b></td>\n";
				echo "      						<td class=\"gray\">".number_format($row1['amtfinan'], 2,'.',',')."</td>\n";
				echo "      						<td class=\"gray\">\n";
				echo "								</td>\n";
				echo "   						</tr>\n";
			}
		}
	}

	echo "						</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
}

function onesheet_ccomments($oid,$cid)
{
	$tranid=time().".".$cid.".".$_SESSION['securityid'];
	
	$qry0 = "SELECT * FROM chistory WHERE custid='".$cid."' ORDER BY mdate DESC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	$qry0ct = "SELECT id FROM chistory WHERE custid='".$cid."' and complaint=0;";
	$res0ct = mssql_query($qry0ct);
	$nrow0ct= mssql_num_rows($res0ct);
	
	$qry0co = "SELECT distinct(id) FROM chistory WHERE custid='".$cid."' and complaint=1 and followup=0 and resolved=0;";
	$res0co = mssql_query($qry0co);
	$nrow0co= mssql_num_rows($res0co);
	
	$qry0cr = "SELECT id FROM chistory WHERE custid='".$cid."' and complaint=1 and resolved=1;";
	$res0cr = mssql_query($qry0cr);
	$nrow0cr= mssql_num_rows($res0cr);
	
	$qry0ro = "SELECT distinct(id) FROM chistory WHERE custid='".$cid."' and cservice=1 and followup=0 and resolved=0;";
	$res0ro = mssql_query($qry0ro);
	$nrow0ro= mssql_num_rows($res0ro);
	
	$qry0rr = "SELECT id FROM chistory WHERE custid='".$cid."' and cservice=1 and resolved=1;";
	$res0rr = mssql_query($qry0rr);
	$nrow0rr= mssql_num_rows($res0rr);
	
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"chistory_add\">\n";
	echo "<input type=\"hidden\" name=\"tranid\" value=\"".$tranid."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";

	if ($_REQUEST['action']=="leads")
	{
		echo "<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	}
	elseif ($_REQUEST['action']=="Reports")
	{
		echo "<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	}
	elseif ($_REQUEST['action']=="est")
	{
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$_REQUEST['estid']."\">\n";
	}
	elseif ($_REQUEST['action']=="contract")
	{
		echo "<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	}
	elseif ($_REQUEST['action']=="job")
	{
		echo "<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	}
	elseif ($_REQUEST['action']=="mas")
	{
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";
		echo "<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	}
	else
	{
		echo "<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	}

	echo "<table>\n";
	echo "<tr>\n";
	echo "<td>\n";
	echo "<div id=\"comments_onesheet\">\n";	
	echo "	<h3><a href=\"#cComment\">Leave Comment</a></h3>\n";
	echo "	<div id=\"cComment\">\n";
	echo "		<p>\n";
	
	echo "<table width=\"450px\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"right\">Select \n";
	echo "			<select name=\"commentflag\">\n";
	echo "				<option value=\"0\">Select...</option>\n";
	
	if ($_SESSION['rlev'] >= 6 || $_SESSION['csrep'] >= 6)
	{		
		if (!empty($sfol_ar) && is_array($sfol_ar))
		{
			foreach (array_unique($sfol_ar) as $sfn => $sfv)
			{
				if (!in_array($sfv,$scls_ar))
				{
					echo "				<option value=\"SF:".$sfv."\">Service Followup: ".$sfv."</option>\n";
				}
			}
		}
		
		if (!empty($sres_ar) && is_array($sres_ar))
		{
			foreach (array_unique($sres_ar) as $srn => $srv)
			{
				if (!in_array($srv,$scls_ar))
				{
					echo "				<option value=\"SR:".$srv."\">Service Resolve: ".$srv."</option>\n";
				}
			}
		}
		
		if (!empty($cfol_ar) && is_array($cfol_ar))
		{
			foreach (array_unique($cfol_ar) as $fn => $fv)
			{
				if (!in_array($fv,$ccls_ar))
				{
					echo "				<option value=\"CF:".$fv."\">Complaint Followup: ".$fv."</option>\n";
				}
			}
		}
		
		if (!empty($cres_ar) && is_array($cres_ar))
		{
			foreach (array_unique($cres_ar) as $rn => $rv)
			{
				if (!in_array($rv,$ccls_ar))
				{
					echo "				<option value=\"CR:".$rv."\">Complaint Resolve: ".$rv."</option>\n";
				}
			}
		}
		
		echo "				<option value=\"S:1\">Add Service</option>\n";
		echo "				<option value=\"C:1\">Add Complaint</option>\n";
	}
	
	echo "				<option value=\"C:0\">Add Comment</option>\n";
	echo "			</select>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\" align=\"right\">\n";
	echo "			<textarea name=\"mtext\" rows=\"2\" cols=\"75\"></textarea>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\" align=\"right\">\n";
	echo "			<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" title=\"Save\">\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "		</p>\n";
	echo "	</div>\n";
	

	if ($nrow0 > 0)
	{
		echo "	<h3><a href=\"#cCommentHistory\">Comment History</a></h3>\n";
		echo "	<div id=\"cCommentHistory\">\n";
		echo "		<p>\n";
		echo "			<table width=\"450px\">\n";
		echo "	   			<tr>\n";
		echo "      			<td align=\"center\" valign=\"top\">\n";
		echo "					<table align=\"center\" width=\"100%\">\n";
		//echo "   					<tr class=\"tblhd\">\n";
		//echo "      					<td align=\"left\"><b>Date</b></td>\n";
		//echo "     	 				<td align=\"left\"><b>Name</b></td>\n";
		//echo "      					<td align=\"left\"><b>Stage</b></td>\n";
		//echo "      					<td align=\"left\"><b>Ticket</b></td>\n";
		//echo "   					</tr>\n";

		$cfol_ar=array();
		$cres_ar=array();
		$ccls_ar=array();
		$sfol_ar=array();
		$sres_ar=array();
		$scls_ar=array();
		while ($row0 = mssql_fetch_array($res0))
		{
			$qry1 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row0['secid']."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);

			if ($row0['act']=="leads")
			{
				$stage="Lead";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="reports")
			{
				$stage="Reports";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="est")
			{
				$stage="Estimate";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="contract")
			{
				$stage="Contract";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="job")
			{
				$stage="Job";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="mas")
			{
				$stage="MAS";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="fin")
			{
				$stage="Finance";
				$cmt_tbg="wh_und";
			}
			elseif ($row0['act']=="Complaint")
			{
				$stage="Complaint";
				$cmt_tbg="ltred_und";
			}
			elseif ($row0['act']=="Service")
			{
				$stage="Service";
				$cmt_tbg="ltblue_und";
			}
			elseif ($row0['act']=="Followup")
			{
				$stage="Followup";
				
				if ($row0['complaint']==1)
				{
					$cmt_tbg="ltred_und";	
				}
				elseif ($row0['cservice']==1)
				{
					$cmt_tbg="ltblue_und";	
				}
				else
				{
					$cmt_tbg="wh_und";
				}
			}
			elseif ($row0['act']=="Resolved")
			{
				$stage="Resolved";
				$cmt_tbg="ltgrn_und";
			}
			elseif ($row0['act']=="cresp")
			{
				$stage="Email";
				$cmt_tbg="wh_und";
			}

			echo "   						<tr>\n";
			echo "   							<td align=\"left\" valign=\"top\" class=\"gray\">".$row1['fname']." ".$row1['lname']."</td>\n";
			echo "								<td align=\"left\" valign=\"top\" class=\"gray\">".$stage."</td>\n";
			echo "								<td align=\"left\" valign=\"top\" class=\"gray\">\n";
			
			if ($row0['complaint']==1)
			{
				if ($row0['followup']==0 && $row0['resolved']==0)
				{
					echo $row0['id'];
					
					$cfol_ar[]=$row0['id'];
					$cres_ar[]=$row0['id'];
				}
				elseif ($row0['followup']==1 && $row0['resolved']==0)
				{
					echo $row0['relatedcomplaint'];
					
					if (!in_array($row0['relatedcomplaint'],$cfol_ar))
					{
						$cfol_ar[]=$row0['relatedcomplaint'];
					}
					
					if (!in_array($row0['relatedcomplaint'],$cres_ar))
					{
						$cres_ar[]=$row0['relatedcomplaint'];
					}
				}
				elseif ($row0['resolved']==1)
				{
					echo $row0['relatedcomplaint'];
					
					$ccls_ar[]=$row0['relatedcomplaint'];
				}
			}
			
			if ($row0['cservice']==1)
			{
				if ($row0['followup']==0 && $row0['resolved']==0)
				{
					echo $row0['id'];
					
					$sfol_ar[]=$row0['id'];
					$sres_ar[]=$row0['id'];
				}
				elseif ($row0['followup']==1 && $row0['resolved']==0)
				{
					echo $row0['relatedcomplaint'];
					
					if (!in_array($row0['relatedcomplaint'],$sfol_ar))
					{
						$sfol_ar[]=$row0['relatedcomplaint'];
					}
					
					if (!in_array($row0['relatedcomplaint'],$sres_ar))
					{
						$sres_ar[]=$row0['relatedcomplaint'];
					}
				}
				elseif ($row0['resolved']==1)
				{
					echo $row0['relatedcomplaint'];
					
					$scls_ar[]=$row0['relatedcomplaint'];
				}
			}
			
			echo "								</td>\n";
			echo "								<td align=\"right\" valign=\"top\" class=\"gray\">".date('m/d/y g:iA',strtotime($row0['mdate']))."</td>\n";
			echo "							</tr>\n";
			echo "							<tr>\n";
			echo "								<td colspan=\"4\" align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\">".htmlspecialchars_decode($row0['mtext'])."</td>\n";
			echo "   						</tr>\n";

		}

		echo "   					</table>\n";
		
		echo "		</p>\n";
		echo "	</div>\n";
	}

	echo "			</div>\n";
	echo "		</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function onesheet_image_tray($cid)
{
	$qry0	= "SELECT F.docid,filename FROM jest..jestFileStore AS F WHERE F.cid=".$cid." AND F.active=1 AND substring(F.filetype,1,5)='image';";
    $res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
	
	//echo $qry0.'<br>';
	if ($nrow0 > 0)
	{
		$cnt=1;
		while ($row0 = mssql_fetch_array($res0))
		{
			
			if ($cnt==6)
			{
				echo '<br>';
				$cnt=1;
			}
			
			echo "
				<a
					href=\"https://jms.bhnmi.com/subs/showimage.php?docid=".$row0['docid']."\"
					target=\"JMSShowImage\" onMouseOver=\"window.status='';return true;\"
					onMouseOut=\"window.status=''; return true;\"
					onclick=\"window.open('','JMSShowImage','HEIGHT=550,WIDTH=700,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"
				>
					<img src=\"export/fileout.php?storetype=file&docid=".$row0['docid']."\" height=\"75x\" width=\"75px\" title=\"".$row0['filename']."\">
				</a>\n";
			
			$cnt++;
		}
	}
	else
	{
		echo 'No Images in System';
	}
}

function onesheet_all_modules()
{
	//error_reporting(E_ALL);
	//show_post_vars();
	
	$tranid=time().".".$_REQUEST['cid'].".".$_SESSION['securityid'];
	$sdate = "";
	$udate = "";
	$fdate = "";
	$fudate= "";
	$fdadate="";
	
	$qryA = "SELECT officeid,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if ($rowA['finan_off']==1)
	{
		$oid=$_REQUEST['officeid'];
	}
	else
	{
		$oid=$_SESSION['officeid'];
	}
	
	$qry = "SELECT C.* FROM cinfo AS C WHERE C.officeid=".$oid." AND C.cid='".$_REQUEST['cid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qryB = "SELECT estid,officeid,cid,esttype,added,updated FROM est WHERE officeid=".$row['officeid']." and ccid='".$row['cid']."';";
	$resB = mssql_query($qryB);
	$nrowB= mssql_num_rows($resB);
	
	//echo $qryB.'<br>';

	$qryC = "SELECT * FROM offices WHERE officeid='".$oid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$qry0 = "SELECT * FROM chistory WHERE custid='".$_REQUEST['cid']."' ORDER BY mdate DESC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	$qryD = "SELECT mas_div,filestoreaccess FROM security WHERE securityid='".$row['securityid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);
	
	if ($row['estid']!=0)
	{
		$qryE = "SELECT estid,added,updated FROM est WHERE officeid='".$oid."' AND estid='".$row['estid']."';";
		$resE = mssql_query($qryE);
		$rowE = mssql_fetch_array($resE);
		
		$eadate= date("m/d/Y", strtotime($rowE['added']));
		$eudate= date("m/d/Y", strtotime($rowE['updated']));
	}
	else
	{
		$eadate="";
		$eudate="";
	}
	
	if ($row['jobid']!="0")
	{
		$qryF = "SELECT jobid,added,updated FROM jdetail WHERE officeid='".$oid."' AND jobid='".$row['jobid']."';";
		$resF = mssql_query($qryF);
		$rowF = mssql_fetch_array($resF);
		
		$cadate= date("m/d/Y", strtotime($rowF['added']));
		$cudate= date("m/d/Y", strtotime($rowF['updated']));
	}
	else
	{
		$cadate="";
		$cudate="";
	}
	
	if ($row['njobid']!="0")
	{
		$qryG = "SELECT njobid,added FROM jdetail WHERE officeid='".$oid."' AND njobid='".$row['njobid']."';";
		$resG = mssql_query($qryG);
		$rowG = mssql_fetch_array($resG);
		
		$cdate= date("m/d/Y", strtotime($rowG['added']));
	}
	else
	{
		$cdate="";
	}

	if (isset($row['added']))
	{
		$sdate = date("m/d/Y", strtotime($row['added']));
	}

	if (isset($row['updated']))
	{
		$udate = date("m/d/Y", strtotime($row['updated']));
	}
	
	if ($nrow1 > 0 && strtotime($row1['lupdate']) > strtotime('1/1/1980'))
	{
		$fdate	=date("m/d/Y", strtotime($row1['recdate']));
		$fudate	=date("m/d/Y", strtotime($row1['lupdate']));
	}

	$qryS = "SELECT securityid,filestoreaccess,constructdateaccess FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$resS = mssql_query($qryS);
	$rowS = mssql_fetch_array($resS);
	
	$qrySa = "select isnull(count(F.docid),0) as tfiles from jest..jestFileStore AS F inner join jest..jestFileStoreCategory AS C on F.fscid=C.fscid where F.cid=".$row['cid']." and F.active=1 and C.slevel <=".$rowS['filestoreaccess'].";";
	$resSa = mssql_query($qrySa);
	$rowSa = mssql_fetch_array($resSa);
	
	//echo $qrySa.'<br>';

	$brdr=0;
	$wraptablehgt='';
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_onesheet.js\"></script>\n";
	echo "<table align=\"center\" width=\"950px\">\n";
	echo "   <tr>\n";
	echo "      <td align=\"left\" valign=\"bottom\" colspan=\"2\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\">\n";
	echo " 			  <tr>\n";
	echo "				<td class=\"gray\" align=\"left\"><b>Customer OneSheet</b> ".$row['cfname']." ".$row['clname']."</td>\n";
	echo "			  </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td align=\"left\" valign=\"top\" width=\"50%\">\n";
	echo "<div id=\"customer_onesheet\">\n";
	echo "	<h3><a href=\"#cContact\">Contact Information</a></h3>\n";
	echo "	<div id=\"cContact\">\n";
	echo "		<p>\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\">\n";
	echo " 			  <tr>\n";
	echo "				<td class=\"gray\" align=\"left\">\n";
	
	onesheet_cinfo_display($oid,$row['cid'],$rowC['stax'],$wraptablehgt);
	
	echo "				</td>\n";
	echo "			  </tr>\n";
	echo "			</table>\n";
	echo "		</p>\n";
	echo "	</div>\n";
	
	echo "	<h3><a href=\"#cLifecycle\">Lifecycle</a></h3>\n";
	echo "	<div id=\"cLifecycle\">\n";
	echo "		<p>\n";
	
	echo "			<table>\n";
	echo "	   			<tr>\n";
	echo "      			<td align=\"center\" valign=\"top\">\n";
	echo "						<table align=\"center\">\n";
	echo "	   						<tr>\n";
	echo "      						<td colspan=\"5\" align=\"right\">\n";
	
	//HelpNode('LifeCyclePanel',2);
	
	echo "								</td>\n";
	echo "   						</tr>\n";

	if ($_SESSION['llev']!=0 && $row['cid']!=0)
	{
		$uid	=md5(session_id().time().$row['custid']).".".$_SESSION['securityid'];
		
		echo "	   					<tr>\n";
		echo "      						<td colspan=\"2\" align=\"left\"></td>\n";
		echo "      						<td align=\"center\"><b>Added</b></td>\n";
		echo "      						<td align=\"center\"><b>Updated</b></td>\n";
		echo "      						<td align=\"center\"></td>\n";
		echo "   					</tr>\n";
		echo "	   					<tr>\n";
		echo "      						<td align=\"right\" width=\"90\"><b>Lead</b></td>\n";
		echo "      						<td align=\"left\" width=\"100\">".$row['custid']."</td>\n";
		echo "      						<td align=\"center\">".$sdate."</td>\n";
		echo "      						<td align=\"center\">".$udate."</td>\n";
		echo "      						<td align=\"left\">\n";
		
		if ($rowA['finan_off']==0)
		{
			echo "                        <form method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
			echo "                        </form>\n";
		}
		
		echo "								</td>\n";
		echo "   						</tr>\n";
	}

	if ($_SESSION['elev']!=0 && $nrowB > 0)
	{
		while ($rowB = mssql_fetch_array($resB))
		{
			echo "                        <form name=\"viewest\" method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
			echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$rowB['estid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"esttype\" value=\"".$rowB['esttype']."\">\n";
			echo "	   					<tr>\n";
			echo "      						<td align=\"right\" width=\"90\"><b>\n";
			
			if ($rowB['esttype']=='E')
			{
				echo 'Estimate';
			}
			else
			{
				echo 'Quote';
			}
			
			echo "</b></td>\n";
			echo "      						<td align=\"left\">\n";
			echo $rowB['estid'];
			echo "								</td>\n";
			echo "      						<td align=\"center\">".date("m/d/Y", strtotime($rowB['added']))."</td>\n";
			echo "      						<td align=\"center\">\n";
			
			if (empty($rowB['updated']) || strtotime($rowB['updated']) < strtotime('1/1/2000'))
			{
				echo "<img src=\"images/pixel.gif\">\n";
			}
			else
			{
				echo date("m/d/Y", strtotime($rowB['updated']));
			}
			
			echo "								</td>\n";
			echo "      						<td align=\"left\">\n";
			
			if ($rowA['finan_off']==0)
			{
				if ($rowB['esttype']=='E')
				{
					echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Estimate\">\n";
					//echo 'Estimate';
				}
				else
				{
					echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Quote\">\n";
					//echo 'Quote';
				}
			}
			
			echo "								</td>\n";
			echo "   						</tr>\n";
			echo "						</form>\n";
		}
	}
	
	if ($_SESSION['clev']!=0 && $row['jobid']!=0)
	{
		echo "	   					<tr>\n";
		echo "      						<td align=\"right\" width=\"90\"><b>Contract</b></td>\n";
		echo "      						<td align=\"left\" width=\"100\">".$row['jobid']."</td>\n";
		echo "      						<td align=\"center\">".$cadate."</td>\n";
		echo "      						<td align=\"center\">".$cudate."</td>\n";
		echo "      						<td align=\"left\">\n";
		
		if ($rowA['finan_off']==0)
		{
			echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
			echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$row['jobid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Contract\">\n";
			//echo "                           <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View\" title=\"Click to View this Contract\">\n";
			echo "                        </form>\n";
		}
		
		echo "								</td>\n";
		echo "   						</tr>\n";
	}

	if ($_SESSION['jlev']!=0 && $row['njobid']!=0)
	{
		$destidret  =disp_mas_div_jobid($rowD['mas_div'],$row['njobid']);
		echo "	   					<tr>\n";
		echo "      						<td align=\"right\" width=\"90\"><b>Job</b></td>\n";
		echo "      						<td align=\"left\" width=\"100\">".$destidret[0]."</td>\n";
		echo "      						<td align=\"center\">".$cadate."</td>\n";
		echo "      						<td align=\"center\">".$cudate."</td>\n";
		echo "      						<td align=\"left\">\n";
		
		if ($rowA['finan_off']==0)
		{
			echo "                        <form method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
			echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			//echo "                           <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View\" title=\"Click to View this Job\">\n";
			echo "                           <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Job\">\n";
			echo "                        </form>\n";
		}
		
		echo "								</td>\n";
		echo "   						</tr>\n";
	}
	
	if (isset($rowS['filestoreaccess']) && $rowS['filestoreaccess'] >= 1)
	{
		echo "	   						<tr>\n";
		echo "      						<td align=\"right\" width=\"90\"><b>Files</b></td>\n";
		echo "      						<td align=\"left\" width=\"100\">".$rowSa['tfiles']."</td>\n";
		echo "      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "      						<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "      						<td align=\"left\">\n";
		echo "									<form method=\"POST\">\n";
		echo "										<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		echo "										<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
		echo "										<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
		echo "										<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Files\">\n";
		echo "									</form>\n";
		echo "								</td>\n";
		echo "   						</tr>\n";
	}

	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	
	echo "		</p>\n";
	echo "	</div>\n";
	
	/*
	echo "	<h3><a href=\"#cFiles\">Files</a></h3>\n";
	echo "	<div id=\"cFiles\">\n";
	echo "		<p>\n";

	list_file_CID();
	
	echo "		</p>\n";
	echo "	</div>\n";
	*/
	
	echo "	<h3><a href=\"#cconstruction\">Construction</a></h3>\n";
	echo "	<div id=\"cconstruction\">\n";
	echo "		<p>\n";
	
	if (isset($row['jobid']) and $row['jobid']!='0' and $rowS['constructdateaccess'] >= 5)
	{
		unset($_SESSION['constr_cid']);
		$_SESSION['constr_cid']=$_REQUEST['cid'];
		$ccuid=md5(session_id().time().$_REQUEST['cid']).".".$_SESSION['securityid'];

		echo "<div id=\"cconstruction_content\">\n";
		echo "	<ul>\n";
		echo "		<li><a href=\"#cconstruction_content_dates\">Dates</a></li>\n";
		echo "		<li><a href=\"#cconstruction_content_comments\">Comments</a></li>\n";
		echo "	</ul>\n";
		echo "	<div id=\"cconstruction_content_dates\">\n";
		echo "		<p>\n";

		echo "			<table width=\"100%\" height=\"".$wraptablehgt."\">\n";
		echo "	   			<tr>\n";
		echo "      			<td align=\"center\" valign=\"top\" class=\"gray\">\n";
		echo "						<table width=\"100%\">\n";
		echo "	   						<tr>\n";
		echo "      						<td class=\"ltgray_und\" align=\"left\"><b>Construction Dates</b> (BETA)</td>\n";
		echo "      						<td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "      						<td class=\"ltgray_und\" align=\"right\">\n";
		echo "									<table>\n";
		echo "	   									<tr>\n";
		echo "      									<td>Job Progess Report</td>\n";
		echo "      									<td>\n";
		echo "												<form method=\"post\">\n";
		echo "													<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "													<input type=\"hidden\" name=\"call\" value=\"jobprogress\">\n";
		echo "													<input class=\"transnb\" type=\"image\" src=\"images/application_view_list.png\" alt=\"Job Progress\">\n";
		echo "												</form>\n";
		echo "											</td>\n";
		echo "   									</tr>\n";
		echo "   								</table>\n";
		echo "								</td>\n";
		echo "   						</tr>\n";
		echo "	   						<tr>\n";
		echo "      						<td colspan=\"2\" align=\"left\">\n";
		
		//onesheet_cdates_display($_REQUEST['cid'],$row['jobid']);
		
		echo "								</td>\n";
		echo "   						</tr>\n";
		echo "   					</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "   		</table>\n";
		
		echo "		</p>\n";
		echo "	</div>\n";
	
		echo "	<div id=\"cconstruction_content_comments\">\n";
		echo "		<p>\n";
		echo "									<table width=\"100%\">\n";
		echo "	   									<tr>\n";
		echo "      									<td align=\"left\"><b>Construction/Receivable Comments</b></td>\n";
		echo "   									</tr>\n";
		echo "	   									<tr>\n";
		echo "      									<td>\n";
		echo "												<form method=\"post\">\n";
		echo "													<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "													<input type=\"hidden\" name=\"call\" value=\"construction_comments_add\">\n";
		echo "													<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
		echo "													<input type=\"hidden\" name=\"ccuid\" value=\"".$ccuid."\">\n";
		echo "													<textarea name=\"mcomment\" id=\"mcomment\" cols=\"60\" rows=\"2\"></textarea>\n";
		echo "													<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Comment\" onClick=\"return EmptyFieldAlert('mcomment');\">\n";
		echo "												</form>\n";
		echo "											</td>\n";
		echo "   									</tr>\n";
		echo "	   									<tr>\n";
		echo "      									<td>\n";
		echo "												<iframe src=\"subs/comments_construction.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"left\"></iframe>\n";
		echo "											</td>\n";
		echo "   									</tr>\n";
		echo "									</table>\n";
		
		echo "		</p>\n";
		echo "	</div>\n";
		
		echo "</div>\n";
	}
	else
	{
		echo 'No Contract in System';
	}
	
	echo "		</p>\n";
	echo "	</div>\n";
	
	echo "	<h3><a href=\"#cImages\">Images</a></h3>\n";
	echo "	<div id=\"cImages\">\n";
	echo "		<p>\n";
	
	echo "		<div class=\"noPrint\">\n";
		
	onesheet_image_tray($_REQUEST['cid']);
		
	echo "		</div>\n";
	
	echo "		</p>\n";
	echo "	</div>\n";
	
	echo "		</td>\n";
	echo "		<td valign=\"top\" width=\"50%\">\n";
	
	onesheet_ccomments($oid,$row['cid']);
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function show_postmas_add($jid,$jadd,$padd,$ptxt)
{
	if ($padd==1)
	{
		$tout  ="<table border=\"0\">\n";
		$tout .="	<tr><td><b>".$ptxt."</b></td><td>\n";
		$tout .="		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		$tout .="			<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		$tout .="			<input type=\"hidden\" name=\"call\" value=\"view_add_post_mas\">\n";
		$tout .="			<input type=\"hidden\" name=\"njobid\" value=\"".$jid."\">\n";
		$tout .="			<input type=\"hidden\" name=\"jadd\" value=\"".$jadd."\">\n";
		$tout .="			<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Addn Detail\">\n";
		$tout .="		</form>\n";
		$tout .="	</td></tr>\n";
		$tout .="</table>\n";
	}
	else
	{
		$tout=$ptxt;
	}
	//$tout= "TEST ($jid)($jadd)($padd)";
	return $tout;
}

function disp_mas_div_jobid($div,$id)
{
	$comp=0;
	if (strlen($div) > 2)
	{
		$ndiv=0;
		$comp++;
	}
	elseif (strlen($div)==1)
	{
		$ndiv=str_pad($div, 2, "0", STR_PAD_LEFT);
	}
	else
	{
		//$ndiv=$div."-";
		$ndiv=$div;
	}

	if ($id==0 || strlen($id) > 6)
	{
		//$nid=" INCOMP";
		$nid=$id;
		$comp++;
	}
	elseif (strlen($id) == 6)
	{
		if (strpos($id,1)==0)
		{
			$nid=substr($id, -5);
		}
		else
		{
			//$nid=" INCOMP";
			$nid=$id;
			$comp++;
		}
	}
	elseif (strlen($id) == 5)
	{
		$nid=$id;
	}
	else
	{
		$nid=str_pad($id, 5, "0", STR_PAD_LEFT);
	}

	$sjid=array($ndiv.$nid,$comp);
	return $sjid;
}

function maplink($a1,$c1,$s1,$z1)
{
	if (strlen($a1) < 2 or $a1=='')
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

		$aop	="<a target=\"_new\" HREF=";
		$a1p	=rtrim(preg_replace('/ /','+',$a1));
		$c1p	=rtrim(preg_replace('/ /','+',$c1));
		$s1p	=$s1;
		$z1p	=$z1;
		$cyp	="US";
		$cid	="&cid=lfmaplink";
		$acl	=">Mapquest <img src=\"images/map_go.png\"></a>";

		$link	=$aop.$base.$a1v.$a1p.$amp.$c1v.$c1p.$amp.$s1v.$s1p.$amp.$z1v.$z1p.$amp.$cyv.$cyp.$cid.$acl;
	}

	return $link;
}

function view_bid_job_mode()
{
	$MAS=$_SESSION['pb_code'];
	//global $viewarray;
	//print_r($_POST);
	$qry = "SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($_SESSION['action']=="contract")
	{
		$qryA = "SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryA = "SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
	}

	//echo $qryA."<br>";

	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$rowA['custid']."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	//echo $qryB."<br>";

	$qryC = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['costid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	if ($_SESSION['action']=="contract")
	{
		$qryD = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."' AND cdbid='".$_REQUEST['cdbid']."' AND rdbid='".$_REQUEST['rdbid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryD = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' AND cdbid='".$_REQUEST['cdbid']."' AND rdbid='".$_REQUEST['rdbid']."';";
	}
	$resD = mssql_query($qryD);
	$nrowD= mssql_num_rows($resD);

	//echo $qryD."<br>";

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<tr>\n";

	if ($_SESSION['action']=="contract")
	{
		echo "								<td align=\"left\" valign=\"bottom\"><b>Contract:</b> <input type=\"text\" class=\"bboxl\" value=\"".$_REQUEST['jobid']."\" DISABLED></td>\n";
	}
	elseif ($_SESSION['action']=="job")
	{
		echo "								<td align=\"left\" valign=\"bottom\"><b>Job:</b> <input type=\"text\" class=\"bboxl\" value=\"".$_REQUEST['njobid']."\" DISABLED></td>\n";
	}

	echo "								<td align=\"left\" valign=\"bottom\"><b>Customer:</b> <input type=\"text\" class=\"bboxl\" value=\"".$rowB['clname'].", ".$rowB['cfname']."\" DISABLED></td>\n";
	echo "								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "								<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "								<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "								<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";

	if ($_SESSION['action']=="contract")
	{
		echo "								<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	}
	elseif ($_SESSION['action']=="job")
	{
		echo "								<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	}

	echo "								<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
	echo "								<td align=\"right\" valign=\"bottom\">\n";
	//echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Return\">\n";
	echo "								</td>\n";
	echo "								</form>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"bottom\"><b>Bid Cost Breakdown for:</b></td>\n";
	echo "								<td align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" value=\"".$rowC['item']."\" DISABLED></td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Phase</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Cost Item</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Part #</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Vendor</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Name</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Description</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Price</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";

	if ($nrowD > 0)
	{
		while ($rowD = mssql_fetch_array($resD))
		{
			$qryDa = "SELECT * FROM phasebase WHERE phsid='".$rowC['phsid']."';";
			$resDa = mssql_query($qryDa);
			$rowDa = mssql_fetch_array($resDa);

			echo "				<tr>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>".$rowDa['phsname']."</b></td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>".$rowC['item']."</b></td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bboxl\" name=\"partno\" value=\"".$rowD['partno']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bboxl\" name=\"vendor\" value=\"".$rowD['vendor']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bboxl\" name=\"sdesc\" value=\"".$rowD['sdesc']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<textarea name=\"comments\" cols=\"30\" rows=\"2\">".$rowD['comments']."</textarea>\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bbox\" name=\"bprice\" value=\"".$rowD['bprice']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "					</td>\n";
			echo "				</tr>\n";
		}
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function edit_bid_job_mode()
{
	$MAS=$_SESSION['pb_code'];
	//global $viewarray;
	//print_r($_POST);
	$qry = "SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($_SESSION['action']=="contract")
	{
		$qryA = "SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryA = "SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
	}

	//echo $qryA."<br>";

	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$rowA['custid']."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	//echo $qryB."<br>";

	$qryC = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['costid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	if ($_SESSION['action']=="contract")
	{
		$qryD = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."' AND cdbid='".$_REQUEST['cdbid']."' AND rdbid='".$_REQUEST['rdbid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryD = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' AND cdbid='".$_REQUEST['cdbid']."' AND rdbid='".$_REQUEST['rdbid']."';";
	}
	$resD = mssql_query($qryD);
	$nrowD= mssql_num_rows($resD);

	//echo $qryD."<br>";

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<tr>\n";

	if ($_SESSION['action']=="contract")
	{
		echo "								<td align=\"left\" valign=\"bottom\"><b>Contract:</b> <input type=\"text\" class=\"bboxl\" value=\"".$_REQUEST['jobid']."\" DISABLED></td>\n";
	}
	elseif ($_SESSION['action']=="job")
	{
		echo "								<td align=\"left\" valign=\"bottom\"><b>Job:</b> <input type=\"text\" class=\"bboxl\" value=\"".$_REQUEST['njobid']."\" DISABLED></td>\n";
	}

	echo "								<td align=\"left\" valign=\"bottom\"><b>Customer:</b> <input type=\"text\" class=\"bboxl\" value=\"".$rowB['clname'].", ".$rowB['cfname']."\" DISABLED></td>\n";
	echo "								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "								<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "								<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
	//echo "								<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	echo "								<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";

	if ($_SESSION['action']=="contract")
	{
		echo "								<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	}
	elseif ($_SESSION['action']=="job")
	{
		echo "								<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	}

	echo "								<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
	echo "								<input type=\"hidden\" name=\"tcontract\" value=\"".$_REQUEST['tcontract']."\">\n";
	echo "								<input type=\"hidden\" name=\"tretail\" value=\"".$_REQUEST['tretail']."\">\n";
	echo "								<input type=\"hidden\" name=\"tcomm\" value=\"".$_REQUEST['tcomm']."\">\n";
	echo "								<input type=\"hidden\" name=\"acctotal\" value=\"".$_REQUEST['acctotal']."\">\n";
	echo "								<td align=\"right\" valign=\"bottom\">\n";
	echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Return\">\n";
	echo "								</td>\n";
	echo "								</form>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"bottom\"><b>Bid Cost Breakdown for:</b></td>\n";
	echo "								<td align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" value=\"".$rowC['item']."\" DISABLED></td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Phase</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Cost Item</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Part #</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Vendor</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Name</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Description</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Price</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";

	if ($nrowD > 0)
	{
		while ($rowD = mssql_fetch_array($resD))
		{
			$qryDa = "SELECT * FROM phasebase WHERE phsid='".$rowC['phsid']."';";
			$resDa = mssql_query($qryDa);
			$rowDa = mssql_fetch_array($resDa);

			echo "				<tr>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>".$rowDa['phsname']."</b></td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>".$rowC['item']."</b></td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bboxl\" name=\"partno\" value=\"".$rowD['partno']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bboxl\" name=\"vendor\" value=\"".$rowD['vendor']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bboxl\" name=\"sdesc\" value=\"".$rowD['sdesc']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<textarea name=\"comments\" cols=\"30\" rows=\"2\">".$rowD['comments']."</textarea>\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bbox\" name=\"bprice\" value=\"".$rowD['bprice']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "					</td>\n";
			echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
			echo "						<input type=\"hidden\" name=\"bbid\" value=\"".$rowD['id']."\">\n";
			echo "						<input type=\"hidden\" name=\"rdbid\" value=\"".$rowD['rdbid']."\">\n";
			echo "						<input type=\"hidden\" name=\"cdbid\" value=\"".$rowD['cdbid']."\">\n";
			echo "						<input type=\"hidden\" name=\"costid\" value=\"".$rowD['cdbid']."\">\n";

			if ($_SESSION['action']=="contract")
			{
				echo "								<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
				echo "								<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
				echo "								<input type=\"hidden\" name=\"call\" value=\"edit_bid_jobmode_delete\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			}
			elseif ($_SESSION['action']=="job")
			{
				echo "								<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
				echo "								<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
				echo "								<input type=\"hidden\" name=\"call\" value=\"edit_bid_jobmode_delete\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			}

			echo "						<input type=\"hidden\" name=\"tcomm\" value=\"".$_REQUEST['tcomm']."\">\n";
			echo "						<input type=\"hidden\" name=\"tcontract\" value=\"".$_REQUEST['tcontract']."\">\n";
			echo "						<input type=\"hidden\" name=\"tretail\" value=\"".$_REQUEST['tretail']."\">\n";
			echo "						<input type=\"hidden\" name=\"acctotal\" value=\"".$_REQUEST['acctotal']."\">\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete\">\n";
			echo "					</td>\n";
			echo "						</form>\n";;
			echo "				</tr>\n";
		}
	}

	echo "				<tr>\n";
	echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "						<input type=\"hidden\" name=\"rdbid\" value=\"".$_REQUEST['rdbid']."\">\n";
	echo "						<input type=\"hidden\" name=\"cdbid\" value=\"".$_REQUEST['costid']."\">\n";
	echo "						<input type=\"hidden\" name=\"costid\" value=\"".$_REQUEST['costid']."\">\n";

	if ($_SESSION['action']=="contract")
	{
		echo "							<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
		echo "							<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	}
	elseif ($_SESSION['action']=="job")
	{
		echo "							<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		echo "							<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	}

	echo "						<input type=\"hidden\" name=\"tcomm\" value=\"".$_REQUEST['tcomm']."\">\n";
	echo "						<input type=\"hidden\" name=\"tretail\" value=\"".$_REQUEST['tretail']."\">\n";
	echo "						<input type=\"hidden\" name=\"tcontract\" value=\"".$_REQUEST['tcontract']."\">\n";
	echo "						<input type=\"hidden\" name=\"acctotal\" value=\"".$_REQUEST['acctotal']."\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"edit_bid_jobmode_add\">\n";
	echo "					<td colspan=\"2\" class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>Add New Item:</b></td>\n";
	echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
	echo "						<input type=\"text\" class=\"bboxl\" name=\"partno\" size=\"20\">\n";
	echo "					</td>\n";
	echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
	echo "						<input type=\"text\" class=\"bboxl\" name=\"vendor\" size=\"20\">\n";
	echo "					</td>\n";
	echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
	echo "						<input type=\"text\" class=\"bboxl\" name=\"sdesc\" size=\"20\">\n";
	echo "					</td>\n";
	echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
	echo "						<textarea name=\"comments\" cols=\"30\" rows=\"2\"></textarea>\n";
	echo "					</td>\n";
	echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
	echo "						<input type=\"text\" class=\"bbox\" name=\"bprice\" size=\"20\">\n";
	echo "					</td>\n";
	echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add\">\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function form_element_ACC($id,$trig,$r_estdata,$type)
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	
	$tbg="ltgraynew";
	$til="";
	
	$qryA = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled,bullet FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$id."' ORDER BY seqn ASC";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);
	
	if (isset($rowA[18]) and $rowA[18] > 0)
	{
		$bullet="<img src=\"images/bullet_green.png\" title=\"SmartFeature (".$rowA[18].")\">";
	}
	else
	{
		$bullet='';
	}
	
	$qryB = "SELECT mid,abrv FROM mtypes WHERE mid='".$rowA[10]."'";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	if ($_SESSION['call']=='view_addnew'||$_SESSION['call']=='create_add'||$_SESSION['call']=='create_add_post_mas')
	{
		$jaddn=0;
		$qryCa = "SELECT status,jobid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$resCa = mssql_query($qryCa);
		$rowCa = mssql_fetch_row($resCa);

		if (strlen($r_estdata) < 2)
		{
			$db_id=0;
			$db_qn=0;
			$db_rp=0;
			$db_cd=0;
			$db_ct=0;
			$db_ca=0;
		}
		else
		{
			$edata=explode(",",$r_estdata);
			foreach($edata as $n1 => $v1)
			{
				$idata=explode(":",$v1);
				$rdata[]=$idata[0];
				$qdata[]=$idata[2];
				$pdata[]=$idata[3];
				$cdata[]=$idata[4];
				//$tdata[]=$idata[5];
				//$adata[]=$idata[6];
			}
			$arkey=array_search($id,$rdata);

			if ($id==$rdata[$arkey])
			{
				$db_id=$rdata[$arkey];
				$db_qn=$qdata[$arkey];
				$db_rp=$pdata[$arkey];
				$db_cd=$cdata[$arkey];
				//$db_ct=$tdata[$arkey];
				//$db_ca=$adata[$arkey];
			}
			else
			{
				$db_id=0;
				$db_qn=0;
				$db_rp=0;
				$db_cd=0;
				$db_ct=0;
				$db_ca=0;
			}
		}
	}

	$s0	=$rowA[0];
	$s1	="aaaa".$s0;                // Acc ID
	$s2	="bbba".$s0;                // Quantity
	$s3	="ccca".$s0;                // Spaitem (DEPRECATED)
	$s4	="ddda".$s0;                // Price
	$s5	="code".$s0;                // Material Code
	$s6	="eeea".$s0;                // Bid Item
	$s7	="fffa".$s0;                // Question Type Code
	$s8	="ggga".$s0;                // Comm Type Code
	$s9	="hhha".$s0;                // Comm Rate
	$s10="iiia".$s0;                // Quan Calc
	$bp	=number_format($rowA[7], 2, '.', '');						// BP from DB

	$cvar911=1; //For Collapsing SubHeaders

	//echo $rowA[3]."<br>";

	if ($rowA[17]==1)
	{
		//if ($db_id==$id && $cvar911==39)
		if ($db_id==$id)
		{
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s2\" value=\"".$db_qn."\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
		}
	}
	else
	{
		if ($rowA[5]==0)
		{
			echo "                     <tr>\n";
			echo "                        <td valign=\"bottom\" align=\"left\">";
			echo                            $rowA[3];
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td valign=\"bottom\" align=\"right\">\n";
			echo "                        </td>\n";
			echo "                             <td valign=\"bottom\" align=\"right\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                        </td>\n";
			echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">\n";
			echo                            $rowB[1];
			echo "                        </td>\n";
			echo "                             <td width=\"50px\" valign=\"bottom\" align=\"right\">\n";
			echo "                           <input type=\"hidden\" name=\"$s2\" value=\"1\">\n";
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif (
		$rowA[5]==2||
		$rowA[5]==39||
		$rowA[5]==55||
		$rowA[5]==58
		)
		{
			// Quantity - NoCharge (Quantity) - Package (Quantity)
			//echo "					<table>\n";
			echo "                     <tr>\n";
			echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">";
			
			showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
			
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"75px\" valign=\"bottom\" align=\"center\">$bullet</td>\n";
			echo "                        <td width=\"60px\" valign=\"bottom\" align=\"right\">$bp</td>\n";
			echo "                        <td width=\"25px\" valign=\"bottom\" align=\"center\">$rowB[1]</td>\n";
			echo "                        <td width=\"50px\" valign=\"bottom\" align=\"center\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"bboxbc\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxbc\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif ($rowA[5]==32)
		{
			// Sub Header (Display Only)
			if ($trig!=1)
			{
				echo "                </table>\n";
				echo "        </span>\n";
			}
			
			echo "		<div onclick=\"SwitchMenu('sub".$rowA[0]."')\">";
			echo "			<img src=\"images/plus.gif\"> <font color=\"blue\"><b>".ucwords(trim($rowA[3]))."</b></font>";	
			echo "		</div>\n";
			echo "		<span class=\"submenu\" id=\"sub".$rowA[0]."\">\n";
			echo "			<table class=\"inner_borders\" border=1 width=\"100%\">\n";
			echo "              <tr>\n";
			echo "					<td valign=\"bottom\" align=\"left\" colspan=\"5\">\n";
			echo "					<font color=\"blue\">\n";
			showdescrip_hdratribs($rowA[11],$rowA[12],$rowA[13]);
			echo "					</font>\n";
			echo "						<input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "						<input type=\"hidden\" name=\"$s2\" value=\"1\">\n";
			echo "						<input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "						<input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "						<input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "						<input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "						<input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "						<input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "					</td>\n";
			echo "				</tr>\n";
		}
		elseif  (
		$rowA[5]==1||
		$rowA[5]==3||
		$rowA[5]==4||
		$rowA[5]==5||
		$rowA[5]==6||
		$rowA[5]==7||
		$rowA[5]==8||
		$rowA[5]==9||
		$rowA[5]==10||
		$rowA[5]==11||
		$rowA[5]==12||
		$rowA[5]==13||
		$rowA[5]==14||
		$rowA[5]==15||
		$rowA[5]==16||
		$rowA[5]==17||
		$rowA[5]==34||
		$rowA[5]==35||
		$rowA[5]==36||
		$rowA[5]==37||
		$rowA[5]==38||
		$rowA[5]==41||
		$rowA[5]==42||
		$rowA[5]==43||
		$rowA[5]==45||
		$rowA[5]==46||
		$rowA[5]==47||
		$rowA[5]==69||
		$rowA[5]==70||
		$rowA[5]==72||
		$rowA[5]==77
		)
		{
			// PFT - SQFT - Fixed - Depth - Checkbox - Base+ (All) - Bracket (All)
			// Deck - NoCharge (PFT,SQFT,IA,Gals,Fixed and Base+ Variants)
			// IA (Div by CalcAmt) - IA (Mult by CalcAmt) - Package (Checkbox)
			
			echo "                     <tr>\n";
			echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">\n";
	
			showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
	
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"75px\" valign=\"bottom\" align=\"center\">$bullet</td>\n";
			echo "                        <td width=\"60px\" valign=\"bottom\" align=\"right\">$bp</td>\n";
			echo "                        <td width=\"25px\" valign=\"bottom\" align=\"center\">$rowB[1]</td>\n";
			echo "                        <td width=\"50px\" valign=\"bottom\" align=\"center\">\n";
	
			if ($_SESSION['call']=='view_addnew' && $db_id==$id)
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add' && $db_id==$id)
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			else
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif (
		$rowA[5]==18||
		$rowA[5]==19||
		$rowA[5]==21||
		$rowA[5]==22||
		$rowA[5]==40
		)
		{
			// Code (PFT - SQFT - IA - Gallons - No Charge)
			//echo "                     <tr>\n";
			//echo "                        <td colspan=\"5\">\n";
			//echo "                     <table class=\"inner_borders\" width=\"100%\" border=0>\n";
			echo "                     <tr>\n";
			echo "                        <td width=\"475\" valign=\"bottom\" align=\"left\">\n";
			showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                        <td width=\"60px\" valign=\"bottom\" align=\"left\">\n";
			echo "                        </td>\n";
			echo "								<td width=\"25px\" valign=\"bottom\" align=\"center\">\n";
			echo                            $rowB[1];
			echo "                        </td>\n";
			echo "								<td width=\"50px\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" SELECTED>\n";
			}
			else
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif ($rowA[5]==20)
		{
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				$qryCODE = "SELECT item,rp FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$db_cd."';";
				$resCODE = mssql_query($qryCODE);
				$rowCODE = mssql_fetch_array($resCODE);
			}
	
			// Code (Quantity)
			echo "                     <tr>\n";
			echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">\n";
			//echo "                           $rowA[3]\n";
			showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
	
			if (!empty($rowCODE['item']))
			{
				echo " (".$rowCODE['item'].")";
			}
	
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\">\n";
			}
	
			echo "                        </td>\n";
			echo "                        <td width=\"60px\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo                            $rowCODE['rp'];
			}
	
			echo "                        </td>\n";
			echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo                            $rowB[1];
			}
	
			echo "                        </td>\n";
			echo "                        <td width=\"50px\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo                            $rowA[4];
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		//elseif ($rowA[5]==23)
		//{
		//	// Code (Checkbox)
		//	echo "                     <tr>\n";
		//	echo "                        <td width=\"475\" valign=\"bottom\" align=\"left\">\n";
		//	showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
		//	echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[5]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
		//	echo "                        </td>\n";
		//	echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\">\n";
		//
		//	if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
		//	{
		//		echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
		//	}
		//	else
		//	{
		//		echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"0\">\n";
		//	}
		//
		//	echo "                        </td>\n";
		//	echo "                        <td width=\"60px\" valign=\"bottom\" align=\"left\">\n";
		//	echo "                        </td>\n";
		//	echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">\n";
		//	echo                            $rowB[1];
		//	echo "                        </td>\n";
		//	echo "                             <td width=\"50px\" valign=\"bottom\" align=\"right\">\n";
		//
		//	if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
		//	{
		//		echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" SELECTED>\n";
		//	}
		//	else
		//	{
		//		echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
		//	}
		//
		//	echo "                        </td>\n";
		//	echo "                     </tr>\n";
		//}
		//elseif (
		//$rowA[5]==24||
		//$rowA[5]==25||
		//$rowA[5]==27||
		//$rowA[5]==28||
		//$rowA[5]==29
		//)
		//{
		//	// Multiple Choice (PFT - SQFT - IA - Gallons - Checkbox)
		//	$qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM [".$MAS."accpbook] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND accid='".$accid."' ORDER BY accid";
		//	$resC = mssql_query($qryC);
		//
		//	echo "                     <tr>\n";
		//	echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">\n";
		//	echo "                           <select name=\"$s1\">\n";
		//
		//	while($rowC = mssql_fetch_row($resC))
		//	{
		//		echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
		//	}
		//
		//	echo "                           </select>\n";
		//	echo "                        </td>\n";
		//	echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\">\n";
		//	echo "                        </td>\n";
		//	echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\">\n";
		//	//echo "                           <input class=\"bbox\" type=\"text\" name=\"$s4\" value=\"$bp\" size=\"6\" maxlength=\"8\">\n";
		//	echo "                        </td>\n";
		//	echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">\n";
		//	echo                            $rowB[1];
		//	echo "                        </td>\n";
		//	echo "                        <td width=\"50px\" valign=\"bottom\" align=\"left\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
		//	echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
		//	echo "                        </td>\n";
		//	echo "                     </tr>\n";
		//}
		//elseif ($rowA[5]==26)
		//{
		//	// Multiple Choice (Quantity)
		//	$qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid AND accid=$accid ORDER BY accid";
		//	$resC = mssql_query($qryC);
		//
		//	echo "                     <tr>\n";
		//	echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">\n";
		//	echo "                           <select name=\"$s1\">\n";
		//
		//	while($rowC = mssql_fetch_row($resC))
		//	{
		//		echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
		//	}
		//
		//	echo "                           </select>\n";
		//	echo "                        </td>\n";
		//	echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\">\n";
		//	echo "                        </td>\n";
		//	echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\">\n";
		//	echo "                        </td>\n";
		//	echo "                             <td width=\"20px\" valign=\"bottom\" align=\"right\">\n";
		//	echo                            $rowB[1];
		//	echo "                        </td>\n";
		//	echo "                        <td width=\"50px\" valign=\"bottom\" align=\"left\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
		//	echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
		//	echo "                           <input class=\"bbox\" type=\"text\" name=\"$s2\" size=\"4\" maxlength=\"5\" value=\"0\"> $rowA[4]\n";
		//	echo "                        </td>\n";
		//	echo "                     </tr>\n";
		//}
		elseif  ($rowA[5]==33)
		{
			// Bid Items
			echo "                     <tr>\n";
			echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">\n";
			
			showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
			
			echo "<br>";
			echo "                           <textarea name=\"$s6\" rows=\"2\" cols=\"60\">";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				if (isset($_REQUEST['jobid']) && isset($_REQUEST['jadd']))
				{
					$qryC = "SELECT jobid,bidinfo,dbid FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."' AND dbid='".$rowA[0]."';";
				}
				elseif (isset($_REQUEST['njobid']) && isset($_REQUEST['jadd']))
				{
					$qryC = "SELECT jobid,bidinfo,dbid FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' AND dbid='".$rowA[0]."';";
				}
				else
				{
					$qryC = "SELECT estid,bidinfo,bidaccid FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$rowA[0]."';";
				}
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_array($resC);
				
				echo str_replace("\\", "", $rowC[1]);
			}
	
			echo "</textarea>\n";
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\"></td>\n";
	
			if ($_SESSION['call']=='view_addnew' && $db_id==$id)
			{
				echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\"><input class=\"bboxbr\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
				echo "                             <td width=\"50px\" valign=\"bottom\" align=\"center\">\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add' && $db_id==$id)
			{
				echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\"><input class=\"bboxbr\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
				echo "                             <td width=\"50px\" valign=\"bottom\" align=\"center\">\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\"><input class=\"bboxbr\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
				echo "                             <td width=\"50px\" valign=\"bottom\" align=\"center\">\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			else
			{
				echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\"><input class=\"bboxbr\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$rowA[7]\"></td>\n";
				echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
				echo "                             <td width=\"50px\" valign=\"bottom\" align=\"center\">\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif  ($rowA[5]==54)
		{
			// Referral
			echo "                     <tr>\n";
			echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">";
			showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);

			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\"></td>\n";
			echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\"></td>\n";
			echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
			echo "                        <td width=\"50px\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"bboxbr\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxbr\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
	}
	// Used to close Span element on last ACC before new category header
	if ($trig==2)
	{
		echo "</table>\n";
		echo "</span>\n";
	}
}

function form_element_ACC_NEW($id,$trig,$r_estdata,$type,$aid,$tbg)
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	
	$PFT_SQFT_Fixed_Depth_Checkbox=array(1,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,34,35,36,37,37,41,42,43,45,46,47,69,70,72,77);
	$Code_PFT_SQFT_GALS=array(18,19,21,22,40);
	
	if (isset($aid[18]) and $aid[18] > 0)
	{
		$bullet="<img src=\"images/bullet_green.png\" title=\"SmartFeature (".$aid[18].")\">";
	}
	else
	{
		$bullet='';
	}
	
	$qryB = "SELECT mid,abrv FROM mtypes WHERE mid='".$aid[10]."'";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	if ($_SESSION['call']=='view_addnew'||$_SESSION['call']=='create_add'||$_SESSION['call']=='create_add_post_mas')
	{
		$jaddn=0;
		$qryCa = "SELECT status,jobid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$resCa = mssql_query($qryCa);
		$rowCa = mssql_fetch_row($resCa);

		if (strlen($r_estdata) < 2)
		{
			$db_id=0;
			$db_qn=0;
			$db_rp=0;
			$db_cd=0;
			$db_ct=0;
			$db_ca=0;
		}
		else
		{
			$edata=explode(",",$r_estdata);
			foreach($edata as $n1 => $v1)
			{
				$idata=explode(":",$v1);
				$rdata[]=$idata[0];
				$qdata[]=$idata[2];
				$pdata[]=$idata[3];
				$cdata[]=$idata[4];
			}
			$arkey=array_search($id,$rdata);

			if ($id==$rdata[$arkey])
			{
				$db_id=$rdata[$arkey];
				$db_qn=$qdata[$arkey];
				$db_rp=$pdata[$arkey];
				$db_cd=$cdata[$arkey];
			}
			else
			{
				$db_id=0;
				$db_qn=0;
				$db_rp=0;
				$db_cd=0;
				$db_ct=0;
				$db_ca=0;
			}
		}
	}

	$s0	=$aid[0];
	$s1	="aaaa".$s0;                // Acc ID
	$s2	="bbba".$s0;                // Quantity
	$s3	="ccca".$s0;                // Spaitem (DEPRECATED)
	$s4	="ddda".$s0;                // Price
	$s5	="code".$s0;                // Material Code
	$s6	="eeea".$s0;                // Bid Item
	$s7	="fffa".$s0;                // Question Type Code
	$s8	="ggga".$s0;                // Comm Type Code
	$s9	="hhha".$s0;                // Comm Rate
	$s10="iiia".$s0;                // Quan Calc
	$bp	=number_format($aid[7], 2, '.', '');						// BP from DB

	$cvar911=1; //For Collapsing SubHeaders
	
	$sz1='475px';
	$sz2='40px';
	$sz3='55px';
	$sz4='20px';
	$sz5='45px';

	//echo $rowA[3]."<br>";

	if ($aid[17]==1)
	{
		//if ($db_id==$id && $cvar911==39)
		if ($db_id==$id)
		{
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$aid[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s2\" value=\"".$db_qn."\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$aid[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$aid[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$aid[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$aid[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$aid[14]\">\n";
		}
	}
	else
	{
		if (
		$aid[5]==2||
		$aid[5]==39||
		$aid[5]==55||
		$aid[5]==58
		)
		{
			// Quantity - NoCharge (Quantity) - Package (Quantity)
			//echo "					<table>\n";
			echo "                     <tr>\n";
			echo "                        <td width=\"".$sz1."\" valign=\"bottom\" align=\"left\">";
			
			showdescrip($aid[3],$aid[11],$aid[12],$aid[13]);
			
			//echo $bullet;
			
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$aid[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$aid[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$aid[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$aid[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$aid[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$aid[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"".$sz2."\" valign=\"bottom\" align=\"center\">".$bullet."</td>\n";
			echo "                        <td width=\"".$sz3."\" valign=\"bottom\" align=\"right\">$bp</td>\n";
			echo "                        <td width=\"".$sz4."\" valign=\"bottom\" align=\"center\">$rowB[1]</td>\n";
			echo "                        <td width=\"".$sz5."\" valign=\"bottom\" align=\"center\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"bboxbc\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxbc\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif ($aid[5]==32 and $_SESSION['securityid']!=26)
		{
			// Sub Header (Display Only)
			
			if (strlen($aid[11]) > 2)
			{
				echo "              <tr>\n";
				echo "					<td class=\"transnb\" valign=\"bottom\" align=\"left\" colspan=\"5\">\n";
				echo '<br/>';
				echo "<font color=\"blue\"><b>".ucwords(trim($aid[3]))."</b></font><br>";
		
				showdescrip_hdratribs($aid[11],$aid[12],$aid[13]);
		
				echo "					</td>\n";
				echo "				</tr>\n";
			}
		}
		elseif (in_array($aid[5],$PFT_SQFT_Fixed_Depth_Checkbox))
		{
			// PFT - SQFT - Fixed - Depth - Checkbox - Base+ (All) - Bracket (All)
			// Deck - NoCharge (PFT,SQFT,IA,Gals,Fixed and Base+ Variants)
			// IA (Div by CalcAmt) - IA (Mult by CalcAmt) - Package (Checkbox)
			
			echo "                     <tr>\n";
			echo "                        <td width=\"".$sz1."\" valign=\"bottom\" align=\"left\">\n";
	
			showdescrip($aid[3],$aid[11],$aid[12],$aid[13]);
			
			//echo $bullet;
	
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$aid[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$aid[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$aid[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$aid[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$aid[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$aid[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"".$sz2."\" valign=\"bottom\" align=\"center\">$bullet</td>\n";
			echo "                        <td width=\"".$sz3."\" valign=\"bottom\" align=\"right\">$bp</td>\n";
			echo "                        <td width=\"".$sz4."\" valign=\"bottom\" align=\"center\">$rowB[1]</td>\n";
			echo "                        <td width=\"".$sz5."\" valign=\"bottom\" align=\"center\">\n";
	
			if ($_SESSION['call']=='view_addnew' && $db_id==$id)
			{
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add' && $db_id==$id)
			{
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			else
			{
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif (in_array($aid[5],$Code_PFT_SQFT_GALS))
		{
			// Code (PFT - SQFT - IA - Gallons - No Charge)
			echo "                     <tr>\n";
			echo "                        <td width=\"".$sz1."\" valign=\"bottom\" align=\"left\">\n";
			
			showdescrip($aid[3],$aid[11],$aid[12],$aid[13]);
			
			//echo $bullet;
			
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$aid[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$aid[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$aid[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$aid[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$aid[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$aid[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"".$sz2."\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                        <td width=\"".$sz3."\" valign=\"bottom\" align=\"left\">\n";
			echo "                        </td>\n";
			echo "						  <td width=\"".$sz4."\" valign=\"bottom\" align=\"center\">\n";
			echo                            $rowB[1];
			echo "                        </td>\n";
			echo "						  <td width=\"".$sz5."\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" SELECTED>\n";
			}
			else
			{
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif  ($aid[5]==33)
		{
			// Bid Items
			echo "                     <tr>\n";
			echo "                        <td width=\"".$sz1."\" valign=\"bottom\" align=\"left\">\n";
			
			showdescrip($aid[3],$aid[11],$aid[12],$aid[13]);
			
			echo "<br>";
			echo "                           <textarea name=\"$s6\" rows=\"2\" cols=\"40\">";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				if (isset($_REQUEST['jobid']) && isset($_REQUEST['jadd']))
				{
					$qryC = "SELECT jobid,bidinfo,dbid FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."' AND dbid='".$aid[0]."';";
				}
				elseif (isset($_REQUEST['njobid']) && isset($_REQUEST['jadd']))
				{
					$qryC = "SELECT jobid,bidinfo,dbid FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' AND dbid='".$aid[0]."';";
				}
				else
				{
					$qryC = "SELECT estid,bidinfo,bidaccid FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$aid[0]."';";
				}
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_array($resC);
				
				echo str_replace("\\", "", htmlspecialchars_decode($rowC[1]));
			}
	
			echo "</textarea>\n";
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$aid[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$aid[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$aid[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$aid[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$aid[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$aid[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"".$sz2."\" valign=\"bottom\" align=\"center\">$bullet</td>\n";
	
			if ($_SESSION['call']=='view_addnew' && $db_id==$id)
			{
				echo "                             <td width=\"".$sz3."\" valign=\"bottom\" align=\"right\"><input class=\"bboxbr\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td width=\"".$sz4."\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
				echo "                             <td width=\"".$sz5."x\" valign=\"bottom\" align=\"center\">\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add' && $db_id==$id)
			{
				echo "                             <td width=\"".$sz3."\" valign=\"bottom\" align=\"right\"><input class=\"bboxbr\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td width=\"".$sz4."\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
				echo "                             <td width=\"".$sz5."\" valign=\"bottom\" align=\"center\">\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                             <td width=\"".$sz3."\" valign=\"bottom\" align=\"right\"><input class=\"bboxbr\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td width=\"".$sz4."\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
				echo "                             <td width=\"".$sz5."\" valign=\"bottom\" align=\"center\">\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			else
			{
				echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\"><input class=\"bboxbr\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$aid[7]\"></td>\n";
				echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
				echo "                             <td width=\"50px\" valign=\"bottom\" align=\"center\">\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif  ($aid[5]==54)
		{
			// Referral
			echo "                     <tr>\n";
			echo "                        <td width=\"".$sz1."\" valign=\"bottom\" align=\"left\">";
			
			showdescrip($aid[3],$aid[11],$aid[12],$aid[13]);
			
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$aid[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$aid[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$aid[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$aid[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$aid[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$aid[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"".$sz2."\" valign=\"bottom\" align=\"center\">$bullet</td>\n";
			echo "                        <td width=\"".$sz3."\" valign=\"bottom\" align=\"right\"></td>\n";
			echo "                        <td width=\"".$sz4."\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
			echo "                        <td width=\"".$sz5."\" valign=\"bottom\" align=\"right\">\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
	}
}

function pool_detail_display($estid)
{
	//global $viewarray;
	//print_r($viewarray);
	error_reporting(E_ALL);
	$viewarray=$_SESSION['viewarray'];
	
	$qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,comadj,sidm,buladj,applyov,applybu,refto,apft,renov FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$respreA = mssql_query($qrypreA);
	//$rowpreA = mssql_fetch_row($respreA);
	$rowpreA = mssql_fetch_array($respreA);
	
	//echo $qrypreA."<br>";

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowpreA['securityid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	//echo $qryD."<br>";

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);
	
	//echo $qryE."<br>";

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);
	
	//echo $qryF."<br>";

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT * FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resH = mssql_query($qryH);
	$rowH = mssql_fetch_array($resH);

	$bpset		=select_base_pool();
	$set_deck   =deckcalc($rowpreA['pft'],$rowpreA['sqft']);
	$incdeck    =round($set_deck[0]);
	$set_ia     =calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals   =calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);

	echo "<input type=\"hidden\" name=\"tzone\" value=\"0\">\n";
	echo "                  <table width=\"100%\" height=\"150\" border=0 class=\"transnb\">\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"top\" align=\"left\">\n";
	echo "                  <table width=\"100%\" border=0 class=\"transnb\">\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\"></td>\n";
	echo "                        <td class=\"gray\" colspan=\"3\" valign=\"bottom\" align=\"left\">\n";

	if ($rowpreA[29]==1)
	{
		echo "<b>Renovation</b>";
	}
	else
	{
		echo "";
	}
	
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\">\n";

	if ($bpset[6]=="pft")
	{
		echo "									<b>Perimeter</b>\n";
	}
	else
	{
		echo "									<b>Surface Area</b>\n";
	}

	echo "								</td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";

	if ($bpset[7] > 0)
	{
		if ($bpset[6]=="pft")
		{
			echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps1']."\">\n";
		}
		else
		{
			echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps2']."\">\n";
		}
	}
	else
	{
		if ($bpset[6]=="pft")
		{
			echo "                           <select name=\"ps1\">\n";
		}
		else
		{
			echo "                           <select name=\"ps2\">\n";
		}

		while($rowA = mssql_fetch_row($resA))
		{
			if ($rowA[1]==$bpset[5])
			{
				echo "                           <option value=\"$rowA[1]\" SELECTED>$rowA[1]</option>\n";
			}
			else
			{
				echo "                           <option value=\"$rowA[1]\">$rowA[1]</option>\n";
			}
		}

		echo "                           </select>\n";
	}
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\">\n";

	if ($bpset[6]=="pft")
	{
		echo "									<b>Surface Area</b>\n";
	}
	else
	{
		echo "									<b>Perimeter</b>\n";
	}

	echo "								</td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";

	if ($bpset[6]=="pft")
	{
		echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps2']."\">\n";
	}
	else
	{
		echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps1']."\">\n";
	}

	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Depths</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps5']."\" title=\"Shallow\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps6']."\" title=\"Middle\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps7']."\" title=\"Deep\">\n";
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Internal Area</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"center\">".$set_ia."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                     	<td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Total Deck</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"deck\" size=\"5\" maxlength=\"4\" value=\"".$viewarray['deck']."\"> \n";

	if ($rowH['deckinc']==1)
	{
		if ($bpset[5] > 0)
		{
			echo " (<b>$incdeck</b> sqft Deck Incl.)";
		}
	}

	echo "                        </td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Gallons</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"center\">".$set_gals."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Electrical Run</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"erun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['erun']."\">\n";
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Plumbing Run</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"prun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['prun']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Spa Per</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['spa2']."\">\n";
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Spa Surf Area</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['spa3']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Referral</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxb\" type=\"text\" name=\"refto\" size=\"15\" value=\"".$viewarray['refto']."\">\n";
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	//echo "                           <i>$rowF[2]</i>\n";
	echo "                         </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "                         </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function pool_detail_display_job($jobid,$jadd)
{
	//global $viewarray;
	$viewarray=$_SESSION['viewarray'];
	//print_r($viewarray);

	if ($jadd >= 1)
	{
		$ojadd=$jadd-1;
	}
	else
	{
		$ojadd=$jadd;
	}

	//echo "PRE: (".$jadd.")<br>";

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$ojadd."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);
	
	if ($_SESSION['action']=="contract")
	{
		$qrypreB = "SELECT renov FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	}
	else
	{
		$qrypreB = "SELECT renov FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$jobid."';";
	}
	//$qrypreB = "SELECT renov FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT * FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resH = mssql_query($qryH);
	$rowH = mssql_fetch_array($resH);

	//$qryIa = "SELECT * FROM masstatus WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$jobid."';";
	if ($_SESSION['action']=="contract")
	{
		$qryIa = "SELECT mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	}
	else
	{
		$qryIa = "SELECT mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$jobid."';";
	}
	$resIa = mssql_query($qryIa);
	$rowIa = mssql_fetch_array($resIa);

	//echo "PRE1: (".$jadd.")<br>";
	
	$masjinfo=getmasjobinfo($jobid);
	
	//echo "PST2: (".$jadd.")<br>";

	if ($rowIa['mas_prep'] > 1 || $masjinfo[1] >= 5)
	{
		$sta	= "Processed";
	}
	elseif ($rowIa['mas_prep'] == 1)
	{
		$sta	= "Review";
	}
	else
	{
		$sta	= "Unsubmitted";
	}

	if ($rowH['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$set_deck	=deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck		=round($set_deck[0]);
	$set_ia		=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);

	$oset_deck	=deckcalc($rowpreA['pft'],$rowpreA['deck']);
	$oincdeck	=round($oset_deck[0]);
	$oset_ia		=calc_internal_area($rowpreA['pft'],$rowpreA['sqft'],$rowpreA['shal'],$rowpreA['mid'],$rowpreA['deep']);
	$oset_gals	=calc_gallons($rowpreA['pft'],$rowpreA['sqft'],$rowpreA['shal'],$rowpreA['mid'],$rowpreA['deep']);

	echo "<input type=\"hidden\" name=\"tzone\" value=\"0\">\n";
	echo "                  <table width=\"100%\" height=\"140\" border=0 class=\"outer\">\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\" colspan=\"3\">\n";
	
	//echo "<b>Renovation</b>";
	if ($rowpreB['renov']==1)
	{
		echo "<b>Renovation</b>";
	}
	else
	{
		echo "";
	}
	
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\" colspan=\"4\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";

	if ($rowH['pft_sqft']=="p")
	{
		echo "                     <tr>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Perimeter:</b></td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['ps1']."</td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Surface Area:</b></td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['ps2']."";
		echo "								</td>\n";
		echo "                     </tr>\n";
	}
	else
	{
		echo "                     <tr>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Surface Area:</b></td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['ps2']."</td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Perimeter:</b></td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['ps1']."";
		echo "                        </td>\n";
		echo "                     </tr>\n";
	}

	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Depths:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['ps5']." x ".$viewarray['ps6']." x ".$viewarray['ps7']."</td>";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Internal Area:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$set_ia."</td>";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                     	<td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Total Deck:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['deck']."</td>";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Gallons:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$set_gals."</td>";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Electrical Run:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['erun']."</td>";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Plumbing Run:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['prun']."</td>";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Spa Perimeter:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['spa1']."</td>";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Spa Surface Area:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['spa2']."</td>";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Referral:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['refto']."</td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                         </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function cinfo_display($cid,$settax)
{
	$qryIa = "SELECT estid,officeid,ccid FROM est AS E WHERE E.officeid='".$_SESSION['officeid']."' AND E.ccid='".$cid."';";
	$resIa = mssql_query($qryIa);
	$rowIa = mssql_fetch_array($resIa);
	$nrowIa= mssql_num_rows($resIa);
	
	if ($nrowIa==0)
	{
		die('Customer Info not Found!');
	}
	
	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid,officeid,added,(select label_masoff_code from offices where officeid=C.officeid) as olabel FROM cinfo AS C WHERE C.officeid='".$_SESSION['officeid']."' AND C.cid='".$rowIa['ccid']."';";
	//$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);
	
	/*if ($_SESSION['securityid']==26)
	{
		echo $qryIa."<br>";
		echo $qryI."<br>";
	}*/

	$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
	$resK = mssql_query($qryK);

	echo "                  <table width=\"100%\" height=\"150\" class=\"transnb\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"top\" align=\"right\">\n";
	echo "                  <table width=\"100%\" class=\"transnb\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" width=\"80\"><b>First Name</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo str_replace('\\','',$rowI[1]);
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" width=\"80\"><b>Last Name</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo str_replace('\\','',$rowI[2]);
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Site Addr</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo $rowI[5];
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>City</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo $rowI[6];
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>State</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo $rowI[7];
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Zip</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo $rowI[8];
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Home Phone</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".format_phonenumber($rowI[3])."</td>\n";
	echo "                           \n";
	echo "                        \n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Cell Phone</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".format_phonenumber($rowI[9])."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>County</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";

	if ($settax==1)
	{
		echo "                           <select name=\"scounty\">\n";
		echo "                              <option value=\"0\">None</option>\n";

		while($rowK = mssql_fetch_row($resK))
		{
			if ($rowK[0]==$rowI[4])
			{
				echo "                           <option value=\"".$rowK[0]."\" SELECTED>".$rowK[1]."</option>\n";
			}
			else
			{
				echo "                           <option value=\"".$rowK[0]."\">".$rowK[1]."</option>\n";
			}
		}
		echo "                           </select>\n";
	}
	else
	{
		echo $rowI[4];
	}

	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function info_display_job($tbg,$offid,$jobid,$jadd,$sfname,$slname,$mfname,$mlname,$ver,$typ,$secid,$njobid,$tjobid)
{
	$brdr=0;
	
	error_reporting(E_ALL);
	
	global $viewarray;
	
	if ($_SESSION['securityid'] == 332 && $typ == "Job" || $_SESSION['securityid'] == 26 && $typ == "Job")
	{
		$sidm_ar=array();
		$sidm_ar[0]=array('fname'=>'None','lname'=>'Assigned','slev'=>0);
		
		$qry0 = "SELECT securityid,fname,lname,SUBSTRING(slevel,13,13) as slev FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY SUBSTRING(slevel,13,13) desc,lname ASC;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		$qry0a = "SELECT securityid,sidm FROM security WHERE securityid='".$secid."';";
		$res0a = mssql_query($qry0a);
		$row0a = mssql_fetch_array($res0a);
		
		$qry1 = "SELECT renov,digsec,sidm FROM jobs WHERE officeid='".$_SESSION['officeid']."' and njobid='".$njobid."';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		
		$qry2 = "SELECT securityid,fname,lname,SUBSTRING(slevel,13,13) as slev FROM security WHERE officeid='".$_SESSION['officeid']."' and securityid in (select securityid from security where sidm!=0) ORDER BY SUBSTRING(slevel,13,13) desc,lname ASC;";
		$res2 = mssql_query($qry2);
		$nrow2= mssql_num_rows($res2);
		
		if ($nrow2 > 0)
		{
			while ($row2 = mssql_fetch_array($res2))
			{
				$sidm_ar[$row2['securityid']]=array('fname'=>$row2['fname'],'lname'=>$row2['lname'],'slev'=>$row2['slev']);
			}
		}
		
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"updtsalesrep\">\n";
		echo "<input type=\"hidden\" name=\"njobid\" value=\"".$njobid."\">\n";
		echo "<input type=\"hidden\" id=\"usr_jobid\" name=\"tjobid\" value=\"".$tjobid."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "			<table class=\"outer\" width=\"100%\" border=".$brdr.">\n";
		echo "				<tr>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\" width=\"175\"><b>".$typ." ".$ver." Breakdown: </b></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\">".$offid."</td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\"></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\"></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\"><b>SalesRep:</b></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\">\n";
		echo "						<select name=\"secid\">\n";
	
		while ($row0 = mssql_fetch_array($res0))
		{
			//$slev=explode(",",$row0['slevel']);
			if ($row0['slev']==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}
			
			if ($row0['securityid'] == $secid)
			{
				echo "							<option class=\"".$ostyle."\" value=\"".$row0['securityid']."\" SELECTED>".$row0['lname'].", ".$row0['fname']."</option>\n";
			}
			else
			{
				echo "							<option class=\"".$ostyle."\" value=\"".$row0['securityid']."\">".$row0['lname'].", ".$row0['fname']."</option>\n";
			}
		}
		
		echo "					</select>\n";
		echo "					</td>";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\">";
		echo "                  	<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Change\">\n";
		echo "					</td>";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\" width=\"175\"><b>".$typ.": </b></td>";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\">".$jobid."\n";
	
		if ($ver=="Retail")
		{
			if ($jadd > 0)
			{
				if (isset($viewarray['add_type']) and $viewarray['add_type']==1)
				{
					echo "GM Adjust #".$jadd."\n";
				}
				elseif (isset($viewarray['add_type']) and $viewarray['add_type']==0)
				{
					echo "Customer Adden #".$jadd."\n";
				}
				else
				{
					echo "Adden #".$jadd."\n";
				}
			}
		}
	
		echo "					</td>";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\"></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\"></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\"><b>Sales Manager:</b></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\">\n";
		echo "						<select name=\"sidm\">\n";
		//echo "							<option class=\"fontblack\" value=\"0\">None Assigned</option>\n";
		
		foreach ($sidm_ar as $nSIDM=>$vSIDM)
		{
			if ($vSIDM['slev']==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}
			
			if ($nSIDM == $row1['sidm'])
			{
				echo "							<option class=\"".$ostyle."\" value=\"".$nSIDM."\" SELECTED>".$vSIDM['fname']." ".$vSIDM['lname']."</option>\n";
			}
			else
			{
				echo "							<option class=\"".$ostyle."\" value=\"".$nSIDM."\">".$vSIDM['fname']." ".$vSIDM['lname']."</option>\n";
			}
		}
		/*
		while ($row2 = mssql_fetch_array($res2))
		{
			if ($row2['slev']==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}
			
			if ($row2['securityid'] == $row1['sidm'])
			{
				echo "							<option class=\"".$ostyle."\" value=\"".$row2['securityid']."\" SELECTED>".$row2['lname'].", ".$row2['fname']."</option>\n";
			}
			else
			{
				echo "							<option class=\"".$ostyle."\" value=\"".$row2['securityid']."\">".$row2['lname'].", ".$row2['fname']."</option>\n";
			}
		}
		*/
		
		echo "						</select>\n";
		echo "					</td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\">\n";
		echo "						<img src=\"images/pixel.gif\">\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "</form>\n"; 
	}
	else
	{
		echo "<input type=\"hidden\" id=\"usr_jobid\" value=\"".$tjobid."\">\n";
		echo "			<table class=\"outer\" width=\"100%\" height=\"30\" border=".$brdr.">\n";
		echo "				<tr>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\" width=\"175\"><b>".$typ." ".$ver." Breakdown: </b></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\">".$offid."</td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\"></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\"></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\"><b>SalesRep:</b></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\">".$sfname." ".$slname."</td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\" width=\"175\"><b>".$typ." #: </b></td>";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\">".$jobid."\n";
	
		if ($ver=="Retail")
		{
			if ($jadd > 0)
			{
				echo "Adden #".$jadd."\n";
			}
		}
	
		echo "					</td>";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\"></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\"></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\"><b>Sales Manager:</b></td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"left\">".$mfname." ".$mlname."</td>\n";
		echo "					<td class=\"".$tbg." JobStatusHeader\" align=\"right\">\n";
		echo "						<img src=\"images/pixel.gif\">\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
	}
}

function info_display_addn($tbg,$offid,$jobid,$jadd,$sfname,$slname,$mfname,$mlname,$ver,$typ,$secid,$njobid)
{
	$brdr=0;
	
	error_reporting(E_ALL);
	
	global $viewarray;
	
	$qry0 = "SELECT securityid,fname,lname,SUBSTRING(slevel,13,13) as slev FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY SUBSTRING(slevel,13,13) desc,lname ASC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	$qry0a = "SELECT securityid,sidm FROM security WHERE securityid='".$secid."';";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
	
	$qry1 = "SELECT renov,digsec,sidm FROM jobs WHERE officeid='".$_SESSION['officeid']."' and njobid='".$njobid."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2 = "SELECT securityid,fname,lname,SUBSTRING(slevel,13,13) as slev FROM security WHERE officeid='".$_SESSION['officeid']."' and securityid in (select securityid from security where sidm!=0) ORDER BY SUBSTRING(slevel,13,13) desc,lname ASC;";
	$res2 = mssql_query($qry2);
	$nrow2= mssql_num_rows($res2);
	
	echo "			<table class=\"outer\" width=\"100%\" height=\"30\" border=".$brdr.">\n";
	echo "				<tr>\n";
	echo "					<td class=\"".$tbg."\" align=\"right\" width=\"175\"><b>Addendum Breakdown: </b></td>\n";
	echo "					<td class=\"".$tbg."\" align=\"left\">".$offid."</td>\n";
	echo "					<td class=\"".$tbg."\" align=\"right\" width=\"175\"><b>".$typ." #: </b></td>";
	echo "					<td class=\"".$tbg."\" align=\"left\">".$jobid."\n";

	if ($jadd > 0)
	{
		if (isset($viewarray['add_type']) and $viewarray['add_type']==1)
		{
			echo "GM Adjust #".$jadd."\n";
		}
		elseif (isset($viewarray['add_type']) and $viewarray['add_type']==0)
		{
			echo "Customer Adden #".$jadd."\n";
		}
		else
		{
			echo "Adden #".$jadd."\n";
		}
	}

	echo "					</td>";
	echo "				</tr>\n";
	echo "			</table>\n";
}

function cinfo_display_chistory($oid,$cid,$settax)
{
	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,jobid,securityid,officeid,ListID,EditSequence FROM cinfo WHERE officeid='".$oid."' AND cid='".$cid."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);
	
	$qryIa = "SELECT officeid,name,enquickbooks FROM offices WHERE officeid=".$rowI[12].";";
	$resIa = mssql_query($qryIa);
	$rowIa = mssql_fetch_array($resIa);
	
	$qryIb = "SELECT securityid,officeid,lname,fname FROM security WHERE securityid=".$rowI[11].";";
	$resIb = mssql_query($qryIb);
	$rowIb = mssql_fetch_array($resIb);
	
	$qryIc = "SELECT contractamt FROM jdetail WHERE officeid=".$rowI[12]." AND jobid='".$rowI[10]."';";
	$resIc = mssql_query($qryIc);
	$rowIc = mssql_fetch_array($resIc);

	$wi1=70;
	$wi2=80;

	echo "<table width=\"100%\" height=\"250px\" class=\"outer\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td class=\"ltgray_und\" align=\"left\">\n";
	echo "			<table width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\"><b>Contact Info</b></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "			<table width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td width=\"50%\">\n";
	echo "						<table width=\"100%\" border=0>\n";
	echo "							<tr>\n";
	echo "								<td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Name:</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">".str_replace('\\','',$rowI[2]).", ".str_replace('\\','',$rowI[1])."</td>\n";
	echo "							</tr>\n";
	echo "                     		<tr>\n";
	echo "                        		<td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Site Addr:</b></td>\n";
	echo "                        		<td class=\"gray\" align=\"left\">".substr($rowI[5], 0, 24)."</td>\n";
	echo "                     		</tr>\n";
	echo "                     		<tr>\n";
	echo "                        		<td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>City:</b></td>\n";
	echo "                        		<td class=\"gray\" align=\"left\">".$rowI[6]."</td>\n";
	echo "                     		</tr>\n";
	echo "                     		<tr>\n";
	echo "                     		   <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>State:</b></td>\n";
	echo "                     		   <td class=\"gray\" align=\"left\">".$rowI[7]."</td>\n";
	echo "                     		</tr>\n";
	echo "                     		<tr>\n";
	echo "                     		   <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Zip:</b></td>\n";
	echo "                     		   <td class=\"gray\" align=\"left\">".$rowI[8]."</td>\n";
	echo "                     		</tr>\n";
	echo "                     		<tr>\n";
	echo "                     		   <td class=\"gray\" width=\"".$wi1."\" valign=\"top\" align=\"right\"><b>Home Ph:</b></td>\n";
	echo "                     		   <td class=\"gray\" align=\"left\">".format_phonenumber($rowI[3])."</td>\n";
	echo "                     		</tr>\n";
	echo "                     		<tr>\n";
	echo "                     		   <td class=\"gray\" width=\"".$wi1."\" valign=\"top\" align=\"right\"><b>Cell Ph:</b></td>\n";
	echo "                     		   <td class=\"gray\" align=\"left\">".format_phonenumber($rowI[9])."</td>\n";
	echo "                     		</tr>\n";
	echo "                     		<tr>\n";
	echo "                     		   <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>County:</b></td>\n";
	echo "                     		   <td class=\"gray\" align=\"left\">\n";

	if ($settax==1)
	{
		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$oid."' AND id='".$rowI[4]."';";
		$resK = mssql_query($qryK);
		$rowK = mssql_fetch_row($resK);

		echo $rowK[1];
	}
	else
	{
		echo $rowI[4];
	}

	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "								<td class=\"gray\" align=\"left\" valign=\"top\" width=\"50%\">\n";
	echo "									<table width=\"100%\" border=0>\n";
	echo "                     					<tr>\n";
	echo "                     					   <td class=\"gray\" width=\"".$wi2."\" align=\"right\"><b>Office:</b></td>\n";
	echo "                     			 		  <td class=\"gray\" align=\"left\">".$rowIa['name']."</td>\n";
	echo "                     					</tr>\n";
	echo "                     					<tr>\n";
	echo "                     			   			<td class=\"gray\" width=\"".$wi2."\" align=\"right\"><b>Sales Rep:</b></td>\n";
	echo "                     			   			<td class=\"gray\" align=\"left\">\n";
	echo "                  							<table>\n";
	echo "	   												<tr>\n";
	echo "      												<td align=\"left\">".$rowIb['lname'].", ".$rowIb['fname']."</td>\n";
	echo "	   													<td align=\"left\">\n";
	
	if ($_SESSION['securityid']==$rowI[11] || $_SESSION['elev'] >= 6 || $_SESSION['clev'] >= 6 || $_SESSION['jlev'] >= 6)
	{
		srep_page_link($rowIb['officeid'],$rowIb['securityid']);
	}
	
	echo "	   													</td>\n";
	echo "	   												</tr>\n";
	echo "		   										</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	
	if ($rowI[10]!="0")
	{
		echo "                     			<tr>\n";
		echo "                        			<td class=\"gray\" width=\"".$wi2."\" align=\"right\"><b>Contract Amt:</b></td>\n";
		echo "                        			<td class=\"gray\" align=\"left\">\n";
		echo number_format($rowIc['contractamt'],2,'.','');
		echo "											</td>\n";
		echo "   									</tr>\n";
	}
	
	echo "									</table>\n";
	echo "								</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function onesheet_cinfo_display($oid,$cid,$settax,$tsize)
{
	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,jobid,securityid,officeid FROM cinfo WHERE officeid='".$oid."' AND cid='".$cid."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);
	
	$qryIa = "SELECT officeid,name FROM offices WHERE officeid='".$rowI[12]."';";
	$resIa = mssql_query($qryIa);
	$rowIa = mssql_fetch_array($resIa);
	
	$qryIb = "SELECT securityid,officeid,lname,fname FROM security WHERE securityid='".$rowI[11]."';";
	$resIb = mssql_query($qryIb);
	$rowIb = mssql_fetch_array($resIb);
	
	$qryIc = "SELECT contractamt FROM jdetail WHERE officeid='".$rowI[12]."' AND jobid='".$rowI[10]."';";
	$resIc = mssql_query($qryIc);
	$rowIc = mssql_fetch_array($resIc);

	echo "                  		<table border=0>\n";
	echo "                     			<tr>\n";
	echo "                     			   <td align=\"right\"><b>Office</b></td>\n";
	echo "                     			   <td align=\"left\">".$rowIa['name']."</td>\n";
	echo "                     			   <td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                     			   <td align=\"right\"><b>Sales Rep</b></td>\n";
	echo "                     			   <td align=\"left\">".$rowIb['fname']." ".$rowIb['lname']."</td>\n";
	echo "                     			   <td align=\"left\">\n";
	
	if ($_SESSION['securityid']==$rowI[11] || $_SESSION['elev'] >= 6 || $_SESSION['clev'] >= 6 || $_SESSION['jlev'] >= 6)
	{
		srep_page_link($rowIb['officeid'],$rowIb['securityid']);
	}
	
	echo "	   								</td>\n";
	echo "	   							</tr>\n";
	echo "                 			    <tr>\n";
	echo "									<td align=\"right\"><b>Customer</b></td>\n";
	echo "									<td align=\"left\">".str_replace('\\','',$rowI[1])." ".str_replace('\\','',$rowI[2])."</td>\n";
	echo "                     			   <td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "								</tr>\n";
	echo "								<tr>\n";
	echo "                      			<td align=\"right\"><b>Address</b></td>\n";
	echo "                      			<td align=\"left\">".$rowI[5]." ".$rowI[6]." ".$rowI[7]." ".$rowI[8]."</td>\n";
	echo "                     			   <td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                     			    <td valign=\"top\" align=\"right\"><b>Phone</b></td>\n";
	echo "                       			 <td align=\"left\">".$rowI[3]."</td>\n";
	echo "                     			   <td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                        			<td valign=\"top\" align=\"right\"><b>Cell</b></td>\n";
	echo "                       			 <td align=\"left\">".$rowI[9]."</td>\n";
	echo "                     			   <td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "                    			</tr>\n";
	echo "	   						</table>\n";
}

function cinfo_display_job($oid,$cid,$settax)
{
	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid FROM cinfo WHERE officeid='".$oid."' AND cid='".$cid."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$wi=70;
	//echo $qryI."<br>";

	echo "                  <table width=\"100%\" height=\"140\" class=\"outer\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi."\" align=\"right\"><b>Name:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".str_replace('\\','',$rowI[2]).", ".str_replace('\\','',$rowI[1])."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi."\" align=\"right\"><b>Site Addr:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".substr($rowI[5], 0, 24)."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi."\" align=\"right\"><b>City:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[6]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi."\" align=\"right\"><b>State:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[7]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi."\" align=\"right\"><b>Zip:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[8]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi."\" valign=\"top\" align=\"right\"><b>Home Ph:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[3]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi."\" valign=\"top\" align=\"right\"><b>Cell Ph:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[9]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi."\" align=\"right\"><b>County:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">\n";

	if ($settax==1)
	{
		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$oid."' AND id='".$rowI[4]."';";
		$resK = mssql_query($qryK);
		$rowK = mssql_fetch_row($resK);

		echo $rowK[1];
	}
	else
	{
		echo $rowI[4];
	}

	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function dates_display_job($cid)
{
	$qry = "SELECT * FROM cinfo WHERE officeid=".$_SESSION['officeid']." AND cid=".$cid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qryA = "SELECT added,submitted,updated,digdate,closed,ListID,EditSequence,securityid,acc_status,sandc FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	$qryAa = "SELECT securityid,ListID,SR_ListID FROM security WHERE securityid=".$rowA['securityid'].";";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);
	
	$qryAb = "SELECT accountingsystem,enmas,enquickbooks FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$resAb = mssql_query($qryAb);
	$rowAb = mssql_fetch_array($resAb);

	$qryB = "SELECT contractdate,added,post_add,pmasreq FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."' AND jadd='0';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$sdate		=date("m/d/Y", strtotime($rowB['added']));
	$udate		=date("m/d/Y", strtotime($rowA['updated']));
	$cdate 		=date("m/d/Y", strtotime($rowB['contractdate']));
	$tdate 		=date("m/d/Y", time());
	
	$sandc		=(isset($rowA['sandc']) and $rowA['sandc']==1)?true:false;

	if (isset($rowA['digdate']))
	{
		$ddate 	=date("m/d/Y", strtotime($rowA['digdate']));
	}
	else
	{
		$ddate	="N/A";
	}

	if (isset($rowA['closed']))
	{
		$cldate 	=date("m/d/Y", strtotime($rowA['closed']));
	}
	else
	{
		$cldate	="N/A";
	}

	/*
	if ($row['mas_prep'] == 9)
	{
		$sta	= "Closed";
	}
	elseif ($row['mas_prep'] >= 2)
	{
		$sta	= "Processed";
	}
	elseif ($row['mas_prep'] == 1)
	{
		$sta	= "Review";
	}
	else
	{
		$sta	= "Unsubmitted";
	}
	
	if (isset($rowB['pmasreq']) and $rowB['pmasreq']==1)
	{
		$sta='Processed';
	}
	*/

	$wd1="100";
	echo "                  <table width=\"100%\" height=\"140\" class=\"outer\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"center\" valign=\"top\">\n";
	echo " 			               <table width=\"100%\" border=0>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray_und\" align=\"right\" width=\"".$wd1."\"><b>Today's Date: </b> </td>\n";
	echo "                        			<td class=\"gray_und\" align=\"left\">".$tdate."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray\" align=\"right\" width=\"".$wd1."\"><b>System Date: </b> </td>\n";
	echo "                        			<td class=\"gray\" align=\"left\">".$sdate."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray\" align=\"right\" width=\"".$wd1."\"><b>Contract Date: </b></td>\n";
	echo "                        			<td class=\"gray\" align=\"left\">".$cdate."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray\" align=\"right\" width=\"".$wd1."\"><b>Dig Date: </b></td>\n";
	echo "                        			<td class=\"gray\" align=\"left\">".$ddate."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray_und\" align=\"right\" width=\"".$wd1."\"><b>Closed Date: </b></td>\n";
	echo "                        			<td class=\"gray_und\" align=\"left\">".$cldate."</td>\n";
	echo "                     			</tr>\n";

	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332 or $_SESSION['securityid']==1950) {
		echo "                     			<tr>\n";
		echo "                        			<td class=\"gray\" align=\"right\" width=\"".$wd1."\"><b>S&C Report: </b></td>\n";
		echo "                        			<td class=\"gray\" align=\"left\">\n";
		
		if ($sandc) {
			echo "					<input id=\"sandcblock\" type=\"checkbox\" value=\"T\" autocomplete=\"off\" title=\"If checked this Job will appear on the Sales & Commission Report\" CHECKED>\n";
		}
		else {
			echo "					<input id=\"sandcblock\" type=\"checkbox\" value=\"T\" autocomplete=\"off\" title=\"If checked this Job will appear on the Sales & Commission Report\">\n";
		}
		
		echo "									</td>\n";
		echo "                     			</tr>\n";
	}
	
	echo "                 				</table>\n";	
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "                  </table>\n";
}

function dates_display_addn($cid,$jadd)
{
	$qry = "SELECT * FROM cinfo WHERE officeid=".$_SESSION['officeid']." AND cid=".$cid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	//echo $qry."<BR>";

	$qryA = "SELECT added,submitted,updated,digdate,closed FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	$qryB = "SELECT contractdate,added,post_add,pmasreq FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."' AND jadd=".$jadd.";";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	//echo $qryB."<BR>";

	$sdate		=date("m/d/Y", strtotime($rowB['added']));
	$udate		=date("m/d/Y", strtotime($rowA['updated']));
	$cdate 		=date("m/d/Y", strtotime($rowB['contractdate']));
	$tdate 		=date("m/d/Y", time());	

	if (isset($rowA['digdate']))
	{
		$ddate 	=date("m/d/Y", strtotime($rowA['digdate']));
	}
	else
	{
		$ddate	="N/A";
	}

	if (isset($rowA['closed']))
	{
		$cldate 	=date("m/d/Y", strtotime($rowA['closed']));
	}
	else
	{
		$cldate	="N/A";
	}
	
	if (isset($rowB['pmasreq']) and $rowB['pmasreq']==1)
	{
		$sta='Processed';
	}
	else
	{
		$sta='Unprocessed';
	}

	$wd1="100";
	echo "                  <table width=\"100%\" height=\"140\" class=\"outer\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"center\" valign=\"top\">\n";
	echo " 			               <table width=\"100%\" border=0>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray_und\" align=\"right\" width=\"".$wd1."\"><b>Today's Date: </b> </td>\n";
	echo "                        			<td class=\"gray_und\" align=\"left\">".$tdate."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray\" align=\"right\" width=\"".$wd1."\"><b>System Date: </b> </td>\n";
	echo "                        			<td class=\"gray\" align=\"left\">".$sdate."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray\" align=\"right\" width=\"".$wd1."\"><b>Contract Date: </b></td>\n";
	echo "                        			<td class=\"gray\" align=\"left\">".$cdate."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray\" align=\"right\" width=\"".$wd1."\"><b>Dig Date: </b></td>\n";
	echo "                        			<td class=\"gray\" align=\"left\">".$ddate."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray_und\" align=\"right\" width=\"".$wd1."\"><b>Closed Date: </b></td>\n";
	echo "                        			<td class=\"gray_und\" align=\"left\">".$cldate."</td>\n";
	echo "                     			</tr>\n";
	echo "								<tr>\n";
	echo "                  				<td class=\"gray\" align=\"right\" width=\"".$wd1."\"><b>Status: </b></td>\n";
	echo "                        			<td class=\"gray\" align=\"left\">".$sta."</td>\n";
	echo "                     			</tr>\n";
	echo "                 			</table>\n";
	echo "							</td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function showdescrip($i,$a1,$a2,$a3)
{
	if (strlen($i) > 1)
	{
		echo htmlspecialchars(ucwords($i))."<br>\n";
	}

	if (strlen($a1) > 1)
	{
		echo "- <font class=\"7pt\">".htmlspecialchars(ucwords($a1))."</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "<br>- <font class=\"7pt\">".htmlspecialchars(ucwords($a2))."</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "<br>- <font class=\"7pt\">".htmlspecialchars(ucwords($a3))."</font>\n";
	}
}

function showdescrip_hdr($i,$a1,$a2,$a3)
{
	if (strlen($i) > 1)
	{
		echo "                                                <font color=\"blue\"><b>".ucwords($i)."</b></font><br>\n";
	}

	if (strlen($a1) > 1)
	{
		echo "                                         - <font class=\"7pt\">$a1</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "                                         <br>- <font class=\"7pt\">$a2</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "                                         <br>- <font class=\"7pt\">$a3</font>\n";
	}
}

function showdescrip_subhdr($i)
{
	if (strlen($i) > 1)
	{
		echo "<img src=\"plus.gif\" style=\"border:white\" alt=\"Click to Expand\"><font color=\"blue\"><b>".ucwords($i)."</b></font>";
	}
}

function showdescrip_hdratribs($a1,$a2,$a3)
{
	if (strlen($a1) > 1)
	{
		echo "                                                - <font class=\"7pt\">".ucwords($a1)."</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "                                                <br>- <font class=\"7pt\">".ucwords($a2)."</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "                                                <br>- <font class=\"7pt\">".ucwords($a3)."</font>\n";
	}
}

function displayall($bc,$rc,$phsid,$phsitem,$adjamt)
{
	global $viewarray;

	//$adjamt="0.00";
	//if ($bc!=0)
	//{
	global $estidret;

	$qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phsid='".$phsid."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	//$bc=round($bc);

	$bc=number_format(round($bc), 2, '.', '');
	$rc=number_format(round($rc), 2, '.', '');

	if ($phsid==8 && $viewarray['royrel'] > 0)
	{
		$tdc="yel";
	}
	else
	{
		$tdc="wh";
	}

	echo "           <tr>\n";
	echo "              <td align=\"center\" class=\"$tdc\"><b>".$rowA['phscode']."</b></td>\n";
	echo "              <td align=\"left\" class=\"$tdc\"><b>".$rowA['extphsname']."</b></td>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td align=\"right\" class=\"$tdc\"></td>\n";
		echo "              <td align=\"right\" class=\"$tdc\"></td>\n";
		echo "              <td align=\"right\" class=\"$tdc\" width=\"70\"></td>\n";
	}
	else
	{
		echo "              <td colspan=\"3\" align=\"right\" class=\"$tdc\"></td>\n";
	}

	echo "              <td align=\"right\" class=\"$tdc\" width=\"70\"><b>\n";
	
	if ($_SESSION['call']!='view_wo')
	{
		echo $bc;
	}
	
	echo "</b></td>\n";

	if ($_SESSION['manphsadj']==1)
	{
		$adjtotal	=$bc+$adjamt;
		$fadjtotal	=number_format($adjtotal, 2, '.', '');

		echo "              	<td align=\"right\" class=\"$tdc\" width=\"65\">\n";
		echo "         			<input class=\"bbox\" type=\"text\" name=\"adjX".$phsid."\" value=\"".$adjamt."\" size=\"8\">\n";
		echo "					</td>\n";
		echo "              	<td align=\"right\" class=\"$tdc\" width=\"65\"><b>".$fadjtotal."</b></td>\n";
	}

	echo "           </tr>\n";
	//}
}

function displayMall($bc,$rc,$cc,$phsid,$phsitem,$adjamt)
{
	//if ($bc!=0)
	//{
	global $estidret;

	//$adjamt="0.00";

	$qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phsid='".$phsid."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	//$bc=round($bc);

	$bc=number_format($bc, 2, '.', '');
	$rc=number_format($rc, 2, '.', '');
	$cc=number_format($cc, 2, '.', '');
	$tdc="wh";

	echo "           <tr>\n";
	echo "              <td align=\"center\" class=\"$tdc\"><b>".$rowA['phscode']."</b></td>\n";
	echo "              <td align=\"left\" class=\"$tdc\"><b>".$rowA['extphsname']."</b></td>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td align=\"right\" class=\"$tdc\"></td>\n";
		echo "              <td align=\"right\" class=\"$tdc\"></td>\n";
		echo "              <td align=\"right\" class=\"$tdc\" width=\"65\"></td>\n";
	}
	else
	{
		echo "              <td colspan=\"3\" align=\"right\" class=\"$tdc\"></td>\n";
	}
	
	echo "              <td align=\"right\" class=\"$tdc\" width=\"70\"><b>\n";
	
	if ($_SESSION['call']!='view_wo')
	{
		echo $bc;
	}
	
	echo "</b></td>\n";

	if ($_SESSION['manphsadj']==1)
	{
		$adjtotal	=$bc+$adjamt;
		$fadjtotal	=number_format($adjtotal, 2, '.', '');

		echo "              	<td align=\"right\" class=\"$tdc\" width=\"65\">\n";
		echo "         			<input class=\"bbox\" type=\"text\" name=\"adjX".$phsid."\" value=\"".$adjamt."\" size=\"8\">\n";
		echo "					</td>\n";
		echo "              	<td align=\"right\" class=\"$tdc\" width=\"100\"><b>".$fadjtotal."</b></td>\n";
	}

	echo "           </tr>\n";
	//}
}

function showitem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc;
	$qry2 = "SELECT phsname as extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($rid!=0)
	{
		$qry3 = "SELECT item FROM [".$MAS."acc] WHERE id='".$rid."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);
		$nrow3= mssql_num_rows($res3);
	}
	else
	{
		$nrow3=0;
	}

	$quan	=round($quan,1);
	//$bc=round($bc);

	$bc=number_format($bc, 2, '.', '');
	$rc=number_format($rc, 2, '.', '');
	$tdc="lg";

	//if (isset($_REQUEST['showdetail'])||$_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print')
	//{
	echo "			<tr class=\"objHidable objExportable\">\n";
	echo "				<td valign=\"bottom\" align=\"center\" class=\"lg\">";

	if ($cr==0)
	{
		echo $row2['phscode'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['phscode']."</font>";
	}

	echo "				</td>\n";
	echo "					<td valign=\"bottom\" align=\"left\" class=\"lg\">";

	if ($cr==0)
	{
		echo $row2['extphsname'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']."</font>";
	}

	echo "</td>\n";
	echo "					<td valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "						<table width=\"100%\" border=0>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\" width=\"225\">";

	if (strlen($i) > 1)
	{
		if ($cr==0)
		{
			echo $i;
		}
		else
		{
			echo "<font color=\"blue\">$i (Credit)</font>";
		}
	}
	if (strlen($a1) > 1)
	{
		echo "<br>- $a1";
	}
	if (strlen($a2) > 1)
	{
		echo "<br>- $a2";
	}
	if (strlen($a3) > 1)
	{
		echo "<br>- $a3";
	}
	echo "</td>\n";
	echo "              			<td valign=\"top\" align=\"left\">";

	if ($nrow3 > 0)
	{
		if ($cr==0)
		{
			echo "(".$row3[0].")";
		}
		else
		{
			echo "<font color=\"blue\">(".$row3[0].")</font>";
			//echo "<font color=\"blue\">$i (Credit)</font>";
		}
	}
	else
	{
		if ($cr==0)
		{
			echo "(Base)";
		}
	}

	echo "</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

	if ($cr==0)
	{
		echo $quan;
	}
	else
	{
		echo "<font color=\"blue\">".$quan."</font>";
	}

	echo "</td>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";

		if ($_SESSION['call']!='view_wo')
		{
			if ($cr==0)
			{
				echo $bc;
			}
			else
			{
				echo "<font color=\"blue\">$bc</font>";
			}
		}
		
		echo "</td>\n";
	}

	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
	echo "           </tr>\n";
}

function showtaxitem()
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$viewarray;

	//print_r($viewarray);
	$qry0 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='41';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$sbc		= $viewarray['tax'];
	$rate	=number_format($viewarray['taxrate'], 3, '.', '');
	$were	= '';
	//$were	= $viewarray['were'];
	$sbc		=round($sbc);
	$bc		=number_format($sbc, 2, '.', '');

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "			<tr>\n";
		echo "				<td valign=\"bottom\" align=\"center\" class=\"lg\">".$row0['phscode']."</td>\n";
		echo "				<td valign=\"bottom\" align=\"left\" class=\"lg\">".$row0['extphsname']."</td>\n";
		echo "				<td valign=\"top\" align=\"left\" class=\"lg\">\n";
		echo "					<table width=\"100%\" border=0>\n";
		echo "						<tr>\n";
		echo "							<td valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">Sales Tax</td>";
		echo "              			<td valign=\"top\" align=\"left\" width=\"175\" class=\"lg\">".$were."</td>";
		echo "              		</tr>\n";
		echo "              	</table>\n";
		echo "				</td>\n";
		echo "				<td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">".$rate."</td>\n";

		if ($_SESSION['jlev'] >= 5)
		{
			echo "				<td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">".$bc."</td>\n";
		}

		echo "				<td align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
		echo "			</tr>\n";
	}

	return $bc;
}

function showadditem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$anum)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc;
	$qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($rid!=0)
	{
		$qry3 = "SELECT item FROM [".$MAS."acc] WHERE id='".$rid."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);
		$nrow3= mssql_num_rows($res3);
	}
	else
	{
		$nrow3=0;
	}

	$quan=round($quan);
	$bc=round($bc);

	$bc=number_format($bc, 2, '.', '');
	$rc=number_format($rc, 2, '.', '');
	$tdc="lg";

	//if (isset($_REQUEST['showdetail'])||$_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print')
	//{
	echo "			<tr class=\"objHidable\">\n";
	echo "				<td valign=\"bottom\" align=\"center\" class=\"lg\">\n";

	if ($cr==0)
	{
		echo "60".$anum."L";
	}
	else
	{
		echo "<font color=\"blue\">60".$anum."L</font>";
		//echo "<font color=\"blue\">".$row2['phscode']."</font>";
	}

	echo "				</td>\n";
	echo "					<td valign=\"bottom\" align=\"left\" class=\"lg\">";

	if ($cr==0)
	{
		echo $row2['extphsname']." (".$row2['phscode'].")";
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']." (".$row2['phscode'].")</font>";
	}

	echo "</td>\n";
	echo "					<td valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "						<table width=\"100%\" border=0>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";

	if (strlen($i) > 1)
	{
		if ($cr==0)
		{
			echo $i;
		}
		else
		{
			echo "<font color=\"blue\">$i (Credit)</font>";
		}
	}
	if (strlen($a1) > 1)
	{
		echo "<br>- $a1";
	}
	if (strlen($a2) > 1)
	{
		echo "<br>- $a2";
	}
	if (strlen($a3) > 1)
	{
		echo "<br>- $a3";
	}
	echo "              			</td>\n";
	echo "              			<td valign=\"top\" align=\"left\" class=\"lg\">";

	if ($nrow3 > 0)
	{
		if ($cr==0)
		{
			echo "(".$row3[0].")";
		}
		else
		{
			echo "<font color=\"blue\">(".$row3[0].")</font>";
			//echo "<font color=\"blue\">$i (Credit)</font>";
		}
	}
	else
	{
		if ($cr==0)
		{
			echo "(Base)";
		}
		else
		{
			echo "<font color=\"blue\">(Base)</font>";
			//echo "<font color=\"blue\">$i (Credit)</font>";
		}
	}

	echo "</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

	if ($cr==0)
	{
		echo $quan;
	}
	else
	{
		echo "<font color=\"blue\">".$quan."</font>";
	}

	echo "</td>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";

		if ($_SESSION['call']!='view_wo')
		{
			if ($cr==0)
			{
				echo $bc;
			}
			else
			{
				echo "<font color=\"blue\">$bc</font>";
			}
		}

		echo "</td>\n";
	}

	//echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\">".$phsbcrc[0]."</td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
	echo "           </tr>\n";
	//}
}

function showaddMitem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$iid,$anum)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc;
	$qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($iid!=0)
	{
		$qry3 = "SELECT item FROM [".$MAS."inventory] WHERE invid='".$iid."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);
		$nrow3= mssql_num_rows($res3);
	}
	else
	{
		$nrow3=0;
	}

	$quan=round($quan);
	$bc=round($bc);

	$bc=number_format($bc, 2, '.', '');
	$rc=number_format($rc, 2, '.', '');
	$tdc="lg";

	//if (isset($_REQUEST['showdetail'])||$_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print')
	//{
	echo "			<tr class=\"objHidable\">\n";
	echo "				<td valign=\"bottom\" align=\"center\" class=\"lg\">\n";

	if ($cr==0)
	{
		echo "60".$anum."L";
	}
	else
	{
		echo "<font color=\"blue\">60".$anum."L</font>";
		//echo "<font color=\"blue\">".$row2['phscode']."</font>";
	}

	echo "				</td>\n";
	echo "					<td valign=\"bottom\" align=\"left\" class=\"lg\">";

	if ($cr==0)
	{
		echo $row2['extphsname']." (".$row2['phscode'].")";
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']." (".$row2['phscode'].")</font>";
	}

	echo "</td>\n";
	echo "					<td valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "						<table width=\"100%\" border=0>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";

	if (strlen($i) > 1)
	{
		if ($cr==0)
		{
			echo $i;
		}
		else
		{
			echo "<font color=\"blue\">$i (Credit)</font>";
		}
	}
	if (strlen($a1) > 1)
	{
		echo "<br>- $a1";
	}
	if (strlen($a2) > 1)
	{
		echo "<br>- $a2";
	}
	if (strlen($a3) > 1)
	{
		echo "<br>- $a3";
	}
	echo "              			</td>\n";
	echo "              			<td valign=\"top\" align=\"left\" class=\"lg\">";

	if ($nrow3 > 0)
	{
		if ($cr==0)
		{
			echo "(".$row3[0].")";
		}
		else
		{
			echo "<font color=\"blue\">(".$row3[0].")</font>";
			//echo "<font color=\"blue\">$i (Credit)</font>";
		}
	}
	else
	{
		if ($cr==0)
		{
			echo "(Base)";
		}
		else
		{
			echo "<font color=\"blue\">(Base)</font>";
			//echo "<font color=\"blue\">$i (Credit)</font>";
		}
	}

	echo "</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

	if ($cr==0)
	{
		echo $quan;
	}
	else
	{
		if ($bc < 0)
		{
			echo "<font color=\"blue\">".($quan*-1)."</font>";
		}
		else
		{
			echo "<font color=\"blue\">".$quan."</font>";
		}
	}

	echo "</td>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";

		if ($_SESSION['call']!='view_wo')
		{
			if ($cr==0)
			{
				echo $bc;
			}
			else
			{
				echo "<font color=\"blue\">$bc</font>";
			}
		}

		echo "</td>\n";
	}

	//echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\">".$phsbcrc[0]."</td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
	echo "           </tr>\n";
	//}
}

function showbiditem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	//print_r($viewarray);
	$qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($rid!=0)
	{
		$qry3 = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);
	}

	$quan	=round($quan);
	$bc	=round($bc);

	$bc=number_format($bc, 2, '.', '');
	$rc=number_format($rc, 2, '.', '');
	$tdc="lg";

	echo "           <tr class=\"objHideable\">\n";
	echo "              <td valign=\"bottom\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
	echo "              <td valign=\"bottom\" align=\"left\" class=\"lg\">\n";

	if ($cr==0)
	{
		echo $row2['extphsname'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']."</font>";
	}

	echo "					</td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td valign=\"top\" align=\"left\" width=\"225\">";

	if (strlen($i) > 1)
	{
		if ($cr==0)
		{
			echo stripslashes($i);
			//echo " XX";
		}
		else
		{
			echo "<font color=\"blue\">".stripslashes($i)." (Credit)</font>\n";
		}
	}
	if (strlen($a1) > 1)
	{
		echo "- <font class=\"7pt\">".stripslashes($a1)."</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "<br>- <font class=\"7pt\">".stripslashes($a2)."</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "<br>- <font class=\"7pt\">".stripslashes($a3)."</font>\n";
	}
	echo "              			</td>\n";
	echo "              			<td valign=\"top\" align=\"left\" width=\"175\">\n";

	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

	if ($quan >= 0)
	{
		echo $quan;
	}
	elseif ($quan < 0)
	{
		echo "<font color=\"blue\">".$quan."</font>";
	}

	echo "</td>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";

		if ($_SESSION['call']!='view_wo')
		{
			if ($bc >= 0)
			{
				echo $bc;
			}
			else
			{
				echo "<font color=\"blue\">".$bc."</font>";
			}
		}
		echo "</td>\n";
	}
	
	if ($_REQUEST['action']=="est")
	{
		$stage="estid";
	}
	else
	{
		if ($bbcnt > 0)
		{
			if ($_REQUEST['action']=="contract")
			{
				$stage="jobid";
			}
			else
			{
				$stage="njobid";
			}
		}
	}
	
	echo "              	<td align=\"center\" valign=\"bottom\" class=\"lg\">\n";
	echo " 						<a href=\".\subs\drilldetail.php?sid=".session_id()."&call=bidadd&officeid=".$_SESSION['officeid']."&sid=".$_SESSION['securityid']."&action=".$_REQUEST['action']."&jid=".$ej_id."&jadd=0&pb_code=".$_SESSION['pb_code']."&rdbid=".$rid."&cdbid=".$costid."&costid=".$costid."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=700,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">Delete</a>\n";
	echo "					</td>\n";
	/*
	echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "						<input type=\"hidden\" name=\"rdbid\" value=\"".$rid."\">\n";
	echo "						<input type=\"hidden\" name=\"cdbid\" value=\"".$costid."\">\n";
	echo "						<input type=\"hidden\" name=\"costid\" value=\"".$costid."\">\n";
	echo "						<input type=\"hidden\" name=\"tcomm\" value=\"".$_REQUEST['tcomm']."\">\n";
	echo "						<input type=\"hidden\" name=\"tretail\" value=\"".$_REQUEST['tretail']."\">\n";
	echo "						<input type=\"hidden\" name=\"tcontract\" value=\"".$viewarray['camt']."\">\n";
	echo "						<input type=\"hidden\" name=\"acctotal\" value=\"".$_REQUEST['acctotal']."\">\n";

	echo "              	<td align=\"center\" valign=\"bottom\" class=\"lg\">\n";

	if ($_REQUEST['action']=="est")
	{
		echo "						<input type=\"hidden\" name=\"estid\" value=\"".$ej_id."\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"edit_bid\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "						<input class=\"buttondkgrypnl50\" type=\"submit\" value=\"Edit\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}
	}
	else
	{
		if ($bbcnt > 0)
		{
			if ($_REQUEST['action']=="contract")
			{
				echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
				echo "						<input type=\"hidden\" name=\"jobid\" value=\"".$ej_id."\">\n";
				echo "						<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			}
			else
			{
				echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
				echo "						<input type=\"hidden\" name=\"njobid\" value=\"".$ej_id."\">\n";
				echo "						<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			}
			echo "						<input type=\"hidden\" name=\"call\" value=\"view_bid_jobmode\">\n";

			if ($_SESSION['subq']=="print")
			{
				echo "<div class=\"noPrint\">\n";
			}

			echo "						<input class=\"buttondkgrypnl50\" type=\"submit\" value=\"View\">\n";

			if ($_SESSION['subq']=="print")
			{
				echo "</div>\n";
			}
		}
	}

	echo "					</td>\n";
	echo "						</form>\n";
	*/
	echo "           </tr>\n";
}

function showbiditemnew($iid,$bc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt,$jadd)
{
	global $phsbcrc;
	$MAS		=$_SESSION['pb_code'];
	$viewarray	=$_SESSION['viewarray'];
	$qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($rid!=0)
	{
		$qry3 = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);
	}
	
	if ($jadd==0)
	{
		if ($_SESSION['action']=="est")
		{
			$jfield="estid";
		}
		elseif ($_SESSION['action']=="contract")
		{
			$jfield="jobid";
		}
		else
		{
			$jfield="njobid";
		}
		
		$qryAa = "SELECT cid,mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' and ".$jfield."='".$ej_id."';";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		
		$masprep=$rowAa['mas_prep'];
	}
	else
	{
		$masprep=0;
	}

	$quan	=round($quan);
	$bc	=round($bc);
	$bc	=number_format($bc, 2, '.', '');
	$tdc="lg";

	echo "           <tr class=\"objHidable objExportable\">\n";
	echo "              <td valign=\"top\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";

	if ($cr==0)
	{
		echo $row2['extphsname'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']."</font>";
	}
	
	if ($jadd!=0)
	{
		echo "<br>Addn 60".$jadd."L";
	}

	echo "					</td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td valign=\"top\" align=\"left\" width=\"225\">";
	echo "								<table border=0>\n";

	if (strlen($i) > 1)
	{	
		if ($cr==0)
		{
			echo "              		<tr>\n";
			echo "              			<td align=\"right\"><b>BC:</b></td>";
			echo "              			<td align=\"left\">";
			echo stripslashes($i);
			echo "              			</td>\n";
			echo "              		</tr>\n";
			//echo " XX";
		}
		else
		{
			echo "              		<tr>\n";
			echo "              			<td align=\"right\"><b>BC:</b></td>";
			echo "              			<td align=\"left\">";
			echo "								<font color=\"blue\">".stripslashes($i)." (Credit)</font>";
			echo "              			</td>\n";
			echo "              		</tr>\n";
		}
	}
	
	if (strlen($a1) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Desc:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a1)."</font>";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a2) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Vendor:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a2)."</font>";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a3) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Part No:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a3)."</font>";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	echo "              				</table>\n";
	echo "              			</td>\n";
	echo "              			<td valign=\"top\" align=\"left\">";
	//print_r($phsbcrc);
	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

	if ($quan >= 0)
	{
		echo $quan;
	}
	elseif ($quan < 0)
	{
		echo "<font color=\"blue\">".$quan."</font>";
	}

	echo "</td>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";

		if ($_SESSION['call']!='view_wo')
		{
			if ($bc >= 0)
			{
				echo $bc;
			}
			else
			{
				echo "<font color=\"blue\">".$bc."</font>";
			}
		}
		echo "</td>\n";
	}
	
	if ($_SESSION['action']=="est")
	{
		$stage="estid";
	}
	else
	{
		if ($bbcnt > 0)
		{
			if ($_SESSION['action']=="contract")
			{
				$stage="jobid";
			}
			else
			{
				$stage="njobid";
			}
		}
	}
	
	echo "              	<td align=\"center\" valign=\"bottom\" class=\"lg\">\n";

	//echo "MJADD2 :".$viewarray['mjadd']."<br>";
	//echo "MJADD3 :".$jadd."<br>";
	if ($viewarray['mjadd']==$jadd && $viewarray['allowdel']==0 && $masprep == 0)
	{
		echo " 						<a target=\"JMSmain\" href=\"http://jms.bhnmi.com/index.php?sid=".md5($_SESSION['securityid'])."&action=".$_SESSION['action']."&call=biddel&officeid=".$_SESSION['officeid']."&bbid=".$iid."\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\">Delete</a>\n";
	}
	
	echo "					</td>\n";
	echo "           </tr>\n";
}

function showmpaitem($iid,$bc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt,$jadd)
{
	global $phsbcrc;
	$MAS		=$_SESSION['pb_code'];
	$viewarray	=$_SESSION['viewarray'];
	$qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($rid!=0)
	{
		$qry3 = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);
	}
	
	if ($jadd==0)
	{
		if ($_SESSION['action']=="est")
		{
			$jfield="estid";
		}
		elseif ($_SESSION['action']=="contract")
		{
			$jfield="jobid";
		}
		else
		{
			$jfield="njobid";
		}
		
		$qryAa = "SELECT cid,mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' and ".$jfield."='".$ej_id."';";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		
		$masprep=$rowAa['mas_prep'];
	}
	else
	{
		$masprep=0;
	}

	$quan	=round($quan);
	$bc	=round($bc);
	$bc	=number_format($bc, 2, '.', '');
	$tdc="lg";

	echo "           <tr class=\"objHidable objExportable\">\n";
	echo "              <td valign=\"top\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";

	if ($cr==0)
	{
		echo $row2['extphsname'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']."</font>";
	}
	
	if ($jadd!=0)
	{
		echo "<br>Addn 60".$jadd."L";
	}

	echo "					</td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td valign=\"top\" align=\"left\" width=\"225\">";
	echo "								<table border=0>\n";

	if (strlen($i) >= 1)
	{	
		if ($cr==0)
		{
			echo "              		<tr>\n";
			echo "              			<td align=\"right\"><b>MPA:</b></td>";
			echo "              			<td align=\"left\">";
			echo stripslashes($i);
			echo "              			</td>\n";
			echo "              		</tr>\n";
			//echo " XX";
		}
		else
		{
			echo "              		<tr>\n";
			echo "              			<td align=\"right\"><b>MPA:</b></td>";
			echo "              			<td align=\"left\">";
			echo "								<font color=\"blue\">".stripslashes($i)." (Credit)</font>\n";
			echo "              			</td>\n";
			echo "              		</tr>\n";
		}
	}
	
	if (strlen($a1) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Desc:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a1)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a2) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Vendor:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a2)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a3) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Part No:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a3)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	echo "              				</table>\n";
	echo "              			</td>\n";
	echo "              			<td valign=\"top\" align=\"left\">\n";
	//print_r($phsbcrc);
	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

	if ($quan >= 0)
	{
		echo $quan;
	}
	elseif ($quan < 0)
	{
		echo "<font color=\"blue\">".$quan."</font>";
	}

	echo "</td>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";

		if ($_SESSION['call']!='view_wo')
		{
			if ($bc >= 0)
			{
				echo $bc;
			}
			else
			{
				echo "<font color=\"blue\">".$bc."</font>";
			}
		}
		echo "</td>\n";
	}
	
	if ($_SESSION['action']=="est")
	{
		$stage="estid";
	}
	else
	{
		if ($bbcnt > 0)
		{
			if ($_SESSION['action']=="contract")
			{
				$stage="jobid";
			}
			else
			{
				$stage="njobid";
			}
		}
	}
	
	echo "              	<td align=\"center\" valign=\"bottom\" class=\"lg\">\n";

	if ($_SESSION['securityid']==26999999999999999999999999)
	{
		echo "MJADD2 :".$viewarray['mjadd']."<br>";
		echo "MJADD3 :".$jadd."<br>";
		echo "ALLDEL :".$viewarray['allowdel']."<br>";
		echo "MASPRE :".$masprep."<br>";
	}
	
	//dislay_array($viewarray);
	
	if ($viewarray['mjadd']==$jadd && $viewarray['allowdel']==0 && $masprep == 0)
	{
		echo " 						<a target=\"JMSmain\" href=\"http://jms.bhnmi.com/index.php?sid=".md5($_SESSION['securityid'])."&action=".$_SESSION['action']."&call=mpadel&officeid=".$_SESSION['officeid']."&bbid=".$iid."\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\">Delete</a>\n";
	}
	
	echo "					</td>\n";
	echo "           </tr>\n";
}

function showbiditemadd($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;

	//print_r($viewarray);
	$qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	//echo "PHS: ".$qry2."<br>";

	//echo "RID: ".$rid."<br>";

	if ($rid!=0)
	{
		$qry3 = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);

		//echo "RID: ".$qry3."<br>";
	}

	//echo "ITM: ".$i."<br>";
	$quan	=round($quan);
	$bc	=round($bc);

	$bc=number_format($bc, 2, '.', '');
	$rc=number_format($rc, 2, '.', '');
	$tdc="lg";

	//if (isset($_REQUEST['showdetail'])||$_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print')
	//{
	echo "           <tr>\n";
	echo "              <td valign=\"bottom\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
	echo "              <td valign=\"bottom\" align=\"left\" class=\"lg\">\n";

	if ($cr==0)
	{
		echo $row2['extphsname'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']." (".$row2['phscode'].")</font>";
	}

	echo "					</td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td valign=\"top\" align=\"left\" width=\"225\">";

	if (strlen($i) > 1)
	{
		if ($cr==0)
		{
			echo stripslashes($i);
		}
		else
		{
			echo "<font color=\"blue\">".stripslashes($i)." (Credit)</font>\n";
		}
	}
	if (strlen($a1) > 1)
	{
		echo "- <font class=\"7pt\">".stripslashes($a1)."</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "<br>- <font class=\"7pt\">".stripslashes($a2)."</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "<br>- <font class=\"7pt\">".stripslashes($a3)."</font>\n";
	}
	echo "              			</td>\n";
	echo "              			<td valign=\"top\" align=\"left\" width=\"175\">\n";

	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

	if ($quan >= 0)
	{
		echo $quan;
	}
	elseif ($quan < 0)
	{
		echo "<font color=\"blue\">".$quan."</font>";
	}

	echo "</td>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";

		if ($bc >= 0)
		{
			echo $bc;
		}
		else
		{
			echo "<font color=\"blue\">".$bc."</font>";
		}

		echo "</td>\n";
	}

	echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "						<input type=\"hidden\" name=\"rdbid\" value=\"".$rid."\">\n";
	echo "						<input type=\"hidden\" name=\"cdbid\" value=\"".$costid."\">\n";
	echo "						<input type=\"hidden\" name=\"costid\" value=\"".$costid."\">\n";
	echo "						<input type=\"hidden\" name=\"tcomm\" value=\"".$_REQUEST['tcomm']."\">\n";
	echo "						<input type=\"hidden\" name=\"tretail\" value=\"".$_REQUEST['tretail']."\">\n";
	echo "						<input type=\"hidden\" name=\"tcontract\" value=\"".$viewarray['camt']."\">\n";
	echo "						<input type=\"hidden\" name=\"acctotal\" value=\"".$_REQUEST['acctotal']."\">\n";
	// }

	echo "              	<td align=\"center\" valign=\"bottom\" class=\"lg\">\n";

	if ($bbcnt > 0)
	{
		if ($_REQUEST['action']=="contract")
		{
			echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			echo "						<input type=\"hidden\" name=\"jobid\" value=\"".$ej_id."\">\n";
			echo "						<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['phsjadd']."\">\n";
		}
		else
		{
			echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "						<input type=\"hidden\" name=\"njobid\" value=\"".$ej_id."\">\n";
			echo "						<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['phsjadd']."\">\n";
		}
		//echo "						<input type=\"hidden\" name=\"call\" value=\"view_bid_jobmode\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($viewarray['maxjadd']==$viewarray['phsjadd'] && $viewarray['mas_prep'] != 1)
		{
			echo "						<input type=\"hidden\" name=\"call\" value=\"edit_bid_jobmode\">\n";
			echo "						<input class=\"buttondkgrypnl50\" type=\"submit\" value=\"Edit\">\n";
			//echo "MP ".$viewarray['mas_prep'];
		}
		else
		{
			echo "						<input type=\"hidden\" name=\"call\" value=\"view_bid_jobmode\">\n";
			echo "						<input class=\"buttondkgrypnl50\" type=\"submit\" value=\"View\">\n";
		}

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}
	}
	else
	{
		if ($_REQUEST['action']=="contract")
		{
			echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			echo "						<input type=\"hidden\" name=\"jobid\" value=\"".$ej_id."\">\n";
			echo "						<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['maxjadd']."\">\n";
		}
		else
		{
			echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "						<input type=\"hidden\" name=\"njobid\" value=\"".$ej_id."\">\n";
			echo "						<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['maxjadd']."\">\n";
		}

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($viewarray['maxjadd']==$viewarray['phsjadd'] && $viewarray['mas_prep'] != 1)
		{
			echo "						<input type=\"hidden\" name=\"call\" value=\"edit_bid_jobmode\">\n";
			echo "						<input class=\"buttondkgrypnl50\" type=\"submit\" value=\"Edit\">\n";
		}

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}
	}

	echo "					</td>\n";
	echo "						</form>\n";
	echo "           </tr>\n";
	//}
}

function showbiditemaddnew($iid,$bc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc;
	$viewarray	=$_SESSION['viewarray'];
	
	//print_r($viewarray);
	
	if ($_SESSION['action'] == "contract")
	{
		$qry = "SELECT pmasreq FROM jdetail WHERE officeid='".$_SESSION['officeid']."' and jobid='".$ej_id."' and jadd='".$jadd."';";
	}
	else
	{
		$qry = "SELECT pmasreq FROM jdetail WHERE officeid='".$_SESSION['officeid']."' and njobid='".$ej_id."' and jadd='".$jadd."';";
	}
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($rid!=0)
	{
		$qry3 = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);
	}

	$quan	=round($quan);
	$bc	=round($bc);

	$bc=number_format($bc, 2, '.', '');
	//$rc=number_format($rc, 2, '.', '');
	$tdc="lg";

	echo "           <tr>\n";
	echo "              <td valign=\"top\" align=\"center\" class=\"lg\">\n";
	
	if ($jadd!=0)
	{
		echo "60".$jadd."L";
	}
	else
	{
		echo $row2['phscode'];
	}
	
	echo "				  </td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";
	
		if ($cr==0)
		{
			echo $row2['extphsname']." (".$row2['phscode'].")";
		}
		else
		{
			echo "<font color=\"blue\">".$row2['extphsname']." (".$row2['phscode'].")</font>";
		}

	echo "					</td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td valign=\"top\" align=\"left\" width=\"225\">";
	echo "								<table border=0>\n";

	if (strlen($i) > 1)
	{	
		if ($cr==0)
		{
			echo "              		<tr>\n";
			echo "              			<td align=\"right\"><b>BC:</b></td>";
			echo "              			<td align=\"left\">";
			echo stripslashes($i);
			echo "              			</td>\n";
			echo "              		</tr>\n";
			//echo " XX";
		}
		else
		{
			echo "              		<tr>\n";
			echo "              			<td align=\"right\"><b>BC:</b></td>";
			echo "              			<td align=\"left\">";
			echo "								<font color=\"blue\">".stripslashes($i)." (Credit)</font>\n";
			echo "              			</td>\n";
			echo "              		</tr>\n";
		}
	}
	
	if (strlen($a1) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Desc:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a1)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a2) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Vendor:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a2)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a3) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Part No:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a3)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	echo "              				</table>\n";
	echo "              			</td>\n";
	echo "              			<td valign=\"top\" align=\"left\">\n";
	//print_r($phsbcrc);
	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

	if ($quan >= 0)
	{
		echo $quan;
	}
	elseif ($quan < 0)
	{
		echo "<font color=\"blue\">".$quan."</font>";
	}

	echo "</td>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";

		if ($_SESSION['call']!='view_wo')
		{
			if ($bc >= 0)
			{
				echo $bc;
			}
			else
			{
				echo "<font color=\"blue\">".$bc."</font>";
			}
		}
		echo "</td>\n";
	}
	
	if ($_SESSION['action']=="est")
	{
		$stage="estid";
	}
	else
	{
		if ($bbcnt > 0)
		{
			if ($_SESSION['action']=="contract")
			{
				$stage="jobid";
			}
			else
			{
				$stage="njobid";
			}
		}
	}
	
	echo "              	<td align=\"center\" valign=\"bottom\" class=\"lg\">\n";

	//echo $viewarray['allowdel']."<br>";
	//echo "MJADD2 :".$viewarray['mjadd']."<br>";
	//echo "MJADD3 :".$jadd."<br>";
	if ($viewarray['mjadd']==$jadd && $viewarray['allowdel']==0)
	{
		echo " 						<a href=\".\index.php?sid=".md5($_SESSION['securityid'])."&action=".$_SESSION['action']."&call=biddel&officeid=".$_SESSION['officeid']."&bbid=".$iid."\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\">Delete</a>\n";
	}
	
	echo "					</td>\n";
	echo "           </tr>\n";
}

function showmpaitemadd($iid,$bc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid,$costid,$ej_id,$bbcnt,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc;
	$viewarray	=$_SESSION['viewarray'];
	
	//print_r($viewarray);
	
	if ($_SESSION['action'] == "contract")
	{
		$qry = "SELECT pmasreq FROM jdetail WHERE officeid='".$_SESSION['officeid']."' and jobid='".$ej_id."' and jadd='".$jadd."';";
	}
	else
	{
		$qry = "SELECT pmasreq FROM jdetail WHERE officeid='".$_SESSION['officeid']."' and njobid='".$ej_id."' and jadd='".$jadd."';";
	}
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($rid!=0)
	{
		$qry3 = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);
	}

	$quan	=round($quan);
	$bc	=round($bc);

	$bc=number_format($bc, 2, '.', '');
	//$rc=number_format($rc, 2, '.', '');
	$tdc="lg";

	echo "           <tr>\n";
	echo "              <td valign=\"top\" align=\"center\" class=\"lg\">\n";
	
	if ($jadd!=0)
	{
		echo "60".$jadd."L";
	}
	else
	{
		echo $row2['phscode'];
	}
	
	echo "				  </td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";
	
		if ($cr==0)
		{
			echo $row2['extphsname']." <br>(".$row2['phscode'].")";
		}
		else
		{
			echo "<font color=\"blue\">".$row2['extphsname']." <br>(".$row2['phscode'].")</font>";
		}

	echo "					</td>\n";
	echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td valign=\"top\" align=\"left\" width=\"225\">";
	echo "								<table border=0>\n";

	if (strlen($i) > 1)
	{	
		if ($cr==0)
		{
			echo "              		<tr>\n";
			echo "              			<td align=\"right\"><b>MPA:</b></td>";
			echo "              			<td align=\"left\">";
			echo stripslashes($i);
			echo "              			</td>\n";
			echo "              		</tr>\n";
			//echo " XX";
		}
		else
		{
			echo "              		<tr>\n";
			echo "              			<td align=\"right\"><b>MPA:</b></td>";
			echo "              			<td align=\"left\">";
			echo "								<font color=\"blue\">".stripslashes($i)." (Credit)</font>\n";
			echo "              			</td>\n";
			echo "              		</tr>\n";
		}
	}
	
	if (strlen($a1) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Desc:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a1)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a2) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Vendor:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a2)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a3) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td align=\"right\">Part No:</td>";
		echo "              			<td align=\"left\">";
		echo "								<font class=\"7pt\">".stripslashes($a3)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	echo "              				</table>\n";
	echo "              			</td>\n";
	echo "              			<td valign=\"top\" align=\"left\">\n";
	//print_r($phsbcrc);
	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

	if ($quan >= 0)
	{
		echo $quan;
	}
	elseif ($quan < 0)
	{
		echo "<font color=\"blue\">".$quan."</font>";
	}

	echo "</td>\n";

	if ($_SESSION['jlev'] >= 5)
	{
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";

		if ($_SESSION['call']!='view_wo')
		{
			if ($bc >= 0)
			{
				echo $bc;
			}
			else
			{
				echo "<font color=\"blue\">".$bc."</font>";
			}
		}
		echo "</td>\n";
	}
	
	if ($_SESSION['action']=="est")
	{
		$stage="estid";
	}
	else
	{
		if ($bbcnt > 0)
		{
			if ($_SESSION['action']=="contract")
			{
				$stage="jobid";
			}
			else
			{
				$stage="njobid";
			}
		}
	}
	
	echo "              	<td align=\"center\" valign=\"bottom\" class=\"lg\">\n";

	//echo $viewarray['allowdel']."<br>";
	//echo "MJADD2 :".$viewarray['mjadd']."<br>";
	//echo "MJADD3 :".$jadd."<br>";
	if ($viewarray['mjadd']==$jadd && $viewarray['allowdel']==0)
	{
		echo " 						<a target=\"JMSmain\" href=\"http://jms.bhnmi.com/index.php?sid=".md5($_SESSION['securityid'])."&action=".$_SESSION['action']."&call=mpadel&officeid=".$_SESSION['officeid']."&bbid=".$iid."\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\">Delete</a>\n";
	}
	
	echo "					</td>\n";
	echo "           </tr>\n";
}


function showMitem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$iid)
{
	error_reporting(E_ALL);
	$MAS=$_SESSION['pb_code'];
	$qry2 = "SELECT phsname as extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if (isset($id) && isset($bc))
	{
		if (isset($iid) && $iid!=0)
		{
			$qry3 = "SELECT item FROM [".$MAS."acc] WHERE id='".$iid."';";
			$res3 = mssql_query($qry3);
			$row3 = mssql_fetch_array($res3);
			$nrow3= mssql_num_rows($res3);
		}
		else
		{
			$nrow3=0;
		}
	
		$quan	=round($quan,1);
		//$bc	=round($bc);
	
		$bc 	=number_format($bc, 2, '.', '');
		$rc 	=number_format($rc, 2, '.', '');

		echo "           <tr class=\"objHidable objExportable\">\n";
		//echo "              <td valign=\"bottom\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
		echo "				<td valign=\"bottom\" align=\"center\" class=\"lg\">";

		if ($cr==0)
		{
			echo $row2['phscode'];
		}
		else
		{
			echo "<font color=\"blue\">".$row2['phscode']."</font>";
		}

		echo "				</td>\n";
		echo "              <td valign=\"bottom\" align=\"left\" class=\"lg\">";

		if ($cr==0)
		{
			echo $row2['extphsname'];
		}
		else
		{
			echo "<font color=\"blue\">".$row2['extphsname']."</font>";
		}

		echo "</td>\n";
		echo "              <td valign=\"top\" align=\"left\" class=\"lg\">\n";
		echo "					<table width=\"100%\" border=0>\n";
		echo "              		<tr>\n";
		echo "              			<td valign=\"top\" align=\"left\" width=\"225\">\n";

		if (strlen($i) > 1)
		{
			if ($cr==0)
			{
				echo "$i<br>";
			}
			else
			{
				//echo "<font class=\"sblue\">$i (Credit)</font><br>\n";
				echo "<font color=\"blue\">$i (Credit)</font><br>";
			}
		}
		if (strlen($a1) > 1)
		{
			echo "<br>- $a1\n";
		}
		if (strlen($a2) > 1)
		{
			echo "<br>- $a2\n";
		}
		if (strlen($a3) > 1)
		{
			echo "<br>- $a3\n";
		}
		echo "              			</td>\n";
		echo "              			<td valign=\"top\" align=\"left\">";

		if ($nrow3 > 0)
		{
			if ($cr==0)
			{
				echo "(".$row3[0].")";
			}
			else
			{
				echo "<font color=\"blue\">(".$row3[0].")</font>";
				//echo "<font color=\"blue\">$i (Credit)</font>";
			}
		}
		else
		{
			if ($cr==0)
			{
				echo "(Base)";
			}
		}

		echo "</td>\n";
		echo "              		</tr>\n";
		echo "              	</table>\n";
		echo "              </td>\n";
		echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"30\">";

		if ($quan!=0)
		{
			if ($cr == 0)
			{
				echo $quan;
			}
			else
			{
				if ($bc < 0)
				{
					echo "<font color=\"blue\">".($quan*-1)."</font>";
				}
				else
				{
					echo "<font color=\"blue\">".$quan."</font>";
				}
			}
			echo "<input type=\"hidden\" name=\"ddd".$id."\" value=\"".$quan."\">";
		}

		echo "</td>\n";
		if ($_SESSION['jlev'] >= 5)
		{
			echo "              <td valign=\"bottom\" align=\"right\" class=\"lg\" width=\"70\">";
			
			if ($_SESSION['call']!='view_wo')
			{
				if ($bc!=0)
				{
					if ($cr == 0)
					{
						echo $bc;
					}
					else
					{
						echo "<font color=\"blue\">$bc</font>";
					}
				}
			}
			echo "</td>\n";
		}
		echo "              <td align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
		echo "           </tr>\n";
	}
}

?>
