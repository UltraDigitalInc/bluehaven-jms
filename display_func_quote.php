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

function disp_cust_id($cid,$ocode,$yr)
{
	echo date("y",strtotime($yr))."-".$ocode."-".$cid;
}

function complaint_sysdetailDELETEME($cid)
{
	$qry0c 	= "SELECT count(c.complaint) as ccnt FROM chistory AS c WHERE custid='".$cid."' and complaint=1;";
	$res0c 	= mssql_query($qry0c);
	$row0c	= mssql_fetch_array($res0c);
	//$nrow0c	= mssql_num_rows($res0c);
	
	$qry0r 	= "SELECT count(c.complaint) as ccnt  FROM chistory AS c WHERE custid='".$cid."' and complaint=3;";
	$res0r 	= mssql_query($qry0r);
	$row0r	= mssql_fetch_array($res0r);
	//$nrow0r	= mssql_num_rows($res0r);
	
	echo "<table width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\" align=\"right\" width=\"75\"><b>Open Complaints</b></td>\n";
	echo "		<td class=\"gray\" align=\"center\">\n";
	
	if ($row0c['ccnt'] > 0)
	{
		echo $row0c['ccnt'];
	}
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\" align=\"right\" width=\"75\"><b>Resolved Complaints</b></td>\n";
	echo "		<td class=\"gray\" align=\"center\">\n";
	
	if ($row0r['ccnt'] > 0)
	{
		echo $row0r['ccnt'];
	}
	
	echo "		</td>\n";
	echo "	</tr>\n";	
	echo "</table>\n"; 
}

function checkserverportstatus($srvr,$port,$timeout)
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

function activity_job_DEFUNCT()
{
    error_reporting(E_ALL);
    
    if (isset($_REQUEST['d1']) && strtotime($_REQUEST['d1']) >= strtotime('1/1/2002'))
	{
        $sdate=array(date("m/d/Y",strtotime($_REQUEST['d1'])),date("m/d/Y",strtotime($_REQUEST['d2'])));
    }
    else
    {
        $sdate=array(date("m/d/Y",(time()-2592000)),date("m/d/Y",time()));
        //$sdate=array('12/1/07',date("m/d/Y",time()));
    }
    
	if (isset($_REQUEST['reno']) && $_REQUEST['reno']==1)
	{
		$reno=$_REQUEST['reno'];
	}
	else
	{
		$reno=0;
	}
	
	$qryA   ="select [coid] ";
	$qryA  .="      ,[j1oid] ";
    $qryA  .="      ,[oname] ";
    $qryA  .="      ,[cid] ";
    $qryA  .="      ,[clname] ";
    $qryA  .="      ,[cfname] ";
    $qryA  .="      ,[caddr1] ";
    $qryA  .="      ,[securityid] ";
    $qryA  .="      ,[masdiv] ";
    $qryA  .="      ,[sidm] ";
	$qryA  .="      ,[salesrep] ";
	$qryA  .="      ,[salesmanager] ";
	$qryA  .="      ,[cadded] ";
	$qryA  .="      ,[estid] ";
	$qryA  .="      ,[eadded] ";
	$qryA  .="      ,[cjobid] ";
	$qryA  .="      ,[j1jobid] ";
	$qryA  .="      ,[contrdate] ";
	$qryA  .="      ,[cnjobid] ";
	$qryA  .="      ,[j1njobid] ";
	$qryA  .="      ,[j1added] ";
	$qryA  .="      ,[digdate] ";
	$qryA  .="      ,[renov] ";
	$qryA  .="from [jest].[dbo].[job_disp] ";
	$qryA  .="where ";
        //$qryA  .="coid=23 and ";
		//$qryA  .="j1oid=23 and ";
	
	if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
	{
		$qryA  .="coid=".$_REQUEST['oid']." and ";
		$qryA  .="j1oid=".$_REQUEST['oid']." and ";
	}
    elseif ($_SESSION['officeid'] != 89)
    {
        $qryA  .="coid=".$_SESSION['officeid']." and ";
		$qryA  .="j1oid=".$_SESSION['officeid']." and ";
    }
	
	$qryA  .= "j1jobid!='0' and ";
	$qryA  .= "renov = ".$reno." and ";	
	$qryA  .= "contrdate >= '".$sdate[0]."' and ";
	$qryA  .= "contrdate < '".$sdate[1]."' ";
	
	$qryA  .= "order by ";
    
    if (isset($_REQUEST['order']) && strlen($_REQUEST['order']) > 3)
	{
    	$qryA  .= "oname,masdiv,".$_REQUEST['order']." ";
    }
    else
    {
        $qryA  .= "oname,masdiv,contrdate ";
    }
    
    if (isset($_REQUEST['dir']) && $_REQUEST['dir']=='desc')
	{
    	$qryA  .= " desc ";
    }
    else
    {
        $qryA  .= " asc ";
    }
	
	$qryA  .= ";";
	$resA  = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);
	
    //echo $qryA."<br>";
    
    echo "<div id=\"masterdiv\">\n";
	echo "<table align=\"center\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\" class=\"gray\"><b>Job Activity</b></td>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>".$sdate[0]." - ".$sdate[1]."</b></td>\n";
	echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"subq2\" value=\"activity_leads\">\n";
	echo "					<td align=\"right\" valign=\"top\" class=\"gray\">\n";
	echo "							<input class=\"buttondkgryh10\" type=\"submit\" value=\"Lead Activity\">\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"center\" valign=\"top\">\n";
    echo "			<table class=\"outer\" width=100% border=\"0\">\n";
	
	if ($nrowA > 0)
	{
        $oidt=0;
        $digs=0;
        $ccnt=0;
        while ($rowA = mssql_fetch_array($resA))
        {
            $uid  =md5(session_id().".".time().".".$rowA['cid']).".".$_SESSION['securityid'];
            if ($oidt!=$rowA['j1oid'])
            {
                $ccnt=0;
                // Table Close
                if ($oidt!=0)
                {
                    echo "			            </table>\n";
                    echo "                  </span>\n";
                    echo "                  </td>\n";
                    echo "				</tr>\n";
                }
                
                echo "				<tr>\n";
                echo "					<td class=\"gray\" align=\"left\">\n";
                echo "                      <table width=\"100%\">\n";
                echo "	            			<tr>\n";
                echo "	            				<td class=\"gray\" align=\"left\" width=\"200px\"><div onclick=\"SwitchMenu('sub".$rowA['j1oid']."')\"><img src=\".\plus.gif\" alt=\"Expand\"><font color=\"blue\"><b>".$rowA['oname']."</b></font></div></td>\n";
                echo "	            				<td class=\"gray\" align=\"right\">\n";
                echo "                                  <table>\n";
                echo "	            		            	<tr>\n";
                echo "	            		            		<td class=\"gray\" align=\"right\" width=\"75px\">Contracts:</td>\n";
                echo "	            		            		<td class=\"gray\" align=\"left\" width=\"75px\"><b>\n";
                
                $qryAa   = "select ";
                $qryAa  .= "	count(cid) as cnt ";
                $qryAa  .= "from  ";
                $qryAa  .= "	cinfo as c  ";
                $qryAa  .= "inner join  ";
                $qryAa  .= "	jdetail as j1 ";
                $qryAa  .= "on  ";
                $qryAa  .= "	c.jobid=j1.jobid  ";
                $qryAa  .= "where  ";
                $qryAa  .= "	c.officeid=".$rowA['j1oid']." and ";
                $qryAa  .= "	j1.officeid=".$rowA['j1oid']." and ";
                $qryAa  .= "	j1.jadd=0 and ";
                $qryAa  .= "	(select renov from jobs where officeid=j1.officeid and jobid=j1.jobid)=0 and ";
                $qryAa  .= "	j1.contractdate >= '".$sdate[0]."' and ";
                $qryAa  .= "	j1.contractdate < '".$sdate[1]."' ;";
                $resAa  = mssql_query($qryAa);
                $rowAa  = mssql_fetch_array($resAa);
                
                echo $rowAa['cnt'];
                
                echo "	            		            		</b></td>\n";
                echo "	            		            		<td class=\"gray\" align=\"right\" width=\"75px\">Jobs:</td>\n";
                echo "	            		            		<td class=\"gray\" align=\"left\" width=\"75px\"><b>\n";
                
                $qryAb   = "select ";
                $qryAb  .= "	count(cid) as cnt ";
                $qryAb  .= "from  ";
                $qryAb  .= "	cinfo as c  ";
                $qryAb  .= "inner join  ";
                $qryAb  .= "	jdetail as j  ";
                $qryAb  .= "on  ";
                $qryAb  .= "	c.jobid=j.jobid  ";
                $qryAb  .= "where  ";
                $qryAb  .= "	c.officeid=".$rowA['j1oid']." and ";
                $qryAb  .= "	j.officeid=".$rowA['j1oid']." and ";
                $qryAb  .= "	c.njobid!='0' and ";
                $qryAb  .= "	j.jadd=0 and ";
                $qryAb  .= "	(select renov from jobs where officeid=j.officeid and jobid=j.jobid)=0 and ";
                $qryAb  .= "	j.contractdate >= '".$sdate[0]."' and ";
                $qryAb  .= "	j.contractdate < '".$sdate[1]."' ;";
                $resAb  = mssql_query($qryAb);
                $rowAb  = mssql_fetch_array($resAb);
                
                echo $rowAb['cnt'];
                
                if ($rowAb['cnt']!=0)
                {
                    $jrat= round(($rowAb['cnt'] / $rowAa['cnt']) * 100);
                }
                else
                {
                    $jrat=0;
                }
                
                echo "	            		            		</b> (".$jrat."%)</td>\n";
                echo "	            		            		<td class=\"gray\" align=\"right\" width=\"75px\">Digs:</td>\n";
                echo "	            		            		<td class=\"gray\" align=\"left\" width=\"75px\"><b>\n";
                
                $qryAc   = "select ";
                $qryAc  .= "	count(cid) as cnt ";
                $qryAc  .= "from  ";
                $qryAc  .= "	cinfo as c  ";
                $qryAc  .= "inner join  ";
                $qryAc  .= "	jdetail as j  ";
                $qryAc  .= "on  ";
                $qryAc  .= "	c.jobid=j.jobid  ";
                $qryAc  .= "where  ";
                $qryAc  .= "	c.officeid=".$rowA['j1oid']." and ";
                $qryAc  .= "	j.officeid=".$rowA['j1oid']." and ";
                $qryAc  .= "	c.njobid!='0' and ";
                $qryAc  .= "	j.jadd=0 and ";
                $qryAc  .= "	(select renov from jobs where officeid=j.officeid and jobid=j.jobid)=0 and ";
                $qryAc  .= "	(select digdate from jobs where officeid=j.officeid and jobid=j.jobid) >=j.contractdate and ";
                $qryAc  .= "	j.contractdate >= '".$sdate[0]."' and ";
                $qryAc  .= "	j.contractdate < '".$sdate[1]."' ";
                $resAc  = mssql_query($qryAc);
                $rowAc  = mssql_fetch_array($resAc);
                
                echo $rowAc['cnt'];
                
                if ($rowAc['cnt']!=0)
                {
                    $drat= round(($rowAc['cnt'] / $rowAa['cnt']) * 100);
                }
                else
                {
                    $drat=0;
                }
                
                echo "                                    </b> (".$drat."%)</td>\n";
                echo "          				            </tr>\n";
                echo "                                </table>\n";
                echo "                              </td>\n";
                echo "				            </tr>\n";
                echo "                      </table>\n";
                echo "					</td>\n";
                echo "				</tr>\n";
                echo "				<tr>\n";
                echo "					<td class=\"gray_und\" align=\"left\">\n";
                echo "                  <span class=\"submenu\" id=\"sub".$rowA['j1oid']."\">\n";
                echo "			            <table class=\"outer\" width=100% border=\"0\">\n";
                echo "				            <tr>\n";
                echo "					            <td class=\"wh_und\" align=\"left\" width=\"25px\"></td>\n";
                echo "			            		<td class=\"wh_und\" align=\"left\" width=\"10px\"></td>\n";
                echo "				            	<td class=\"wh_und\" align=\"left\"><b>Customer</b></td>\n";
                echo "		            			<td class=\"wh_und\" align=\"left\"></td>\n";
                echo "			            		<td class=\"wh_und\" align=\"center\"><b>Contract Dt</b></td>\n";
                echo "			            		<td class=\"wh_und\" align=\"left\"><b>Job #</b></td>\n";
                echo "			            		<td class=\"wh_und\" align=\"center\"><b>Dig Date</b></td>";
                echo "				            </tr>\n";
            }
            
            $ccnt++;
            echo "				            <tr>\n";
            echo "			        		    <td class=\"wh_und\" align=\"right\" width=\"25px\">".$ccnt.".</td>\n";
            echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
			echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$rowA['cid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
            echo "		            			<td class=\"wh_und\" align=\"left\" width=\"10px\">\n";
            echo "                                <input class=\"checkboxwh\" type=\"image\" src=\".\plus.gif\">\n";
            echo "                              </td>\n";
            echo "                        </form>\n";
            echo "		            			<td class=\"wh_und\" align=\"left\">".$rowA['clname']."</td>\n";
            echo "		            			<td class=\"wh_und\" align=\"left\">".$rowA['cfname']."</td>\n";
            echo "		            			<td class=\"wh_und\" align=\"center\" width=\"125px\">".date("m/d/y",strtotime($rowA['contrdate']))."</td>\n";
            
            if (isset($rowA['j1njobid']) && $rowA['j1njobid']!='0')
            {
                echo "		            			<td class=\"wh_und\" align=\"left\" width=\"125px\">".str_pad($rowA['masdiv'],2,'0',STR_PAD_LEFT).str_pad($rowA['j1njobid'],5,'0',STR_PAD_LEFT)."</td>\n";
            }
            else
            {
                echo "		            			<td class=\"wh_und\" align=\"left\" width=\"125px\"></td>\n";
            }
            
            echo "		            			<td class=\"wh_und\" align=\"center\" width=\"125px\">";
            
            if (!empty($rowA['digdate']))
            {
                $digs++;
                echo date("m/d/y",strtotime($rowA['digdate']));
            }
            
            echo "</td>\n";
            echo "				            </tr>\n";
            $oidt=$rowA['j1oid'];
        }
	}
	else
	{
		echo "				<tr>\n";
		echo "					<td class=\"wh_und\" align=\"left\">None during this timeframe</td>\n";
		echo "				</tr>\n";
	}
	
	echo "			</table>\n";
    //echo "      </span>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
    echo "</div>\n";
	
}

function system_announce()
{
	 $qryA  = "SELECT * FROM systemwidemessage WHERE active='1' and officeid='0' ORDER BY added DESC;";
	 $resA  = mssql_query($qryA);
	 $nrowA = mssql_num_rows($resA);
	 
	 if ($nrowA > 0)
	 {
		  echo "			<table class=\"outer\" width=100% border=\"0\">\n";
		  echo "				<tr>\n";
		  echo "					<td class=\"ltgray_und\" colspan=\"2\" align=\"center\" valign=\"top\"><b>System Announcements</b></td>\n";
		  echo "				</tr>\n";
	 
		 while ($rowA  = mssql_fetch_array($resA))
		 {
			 echo "				<tr>\n";
			 echo "					<th align=\"left\" valign=\"top\"><b>".$rowA['subject']."</b></th>\n";
			 echo "					<th align=\"right\" valign=\"top\">".$rowA['added']."</th>\n";
			 echo "				</tr>\n";
			 echo "				<tr>\n";
			 echo "					<td class=\"wh_und\" colspan=\"2\" align=\"left\" valign=\"top\">".$rowA['message']."</td>\n";
			 echo "				</tr>\n";
		 }
	 
		 echo "			</table>\n";
	 }
}

function activity_ivr()
{
	//show_post_vars();
	$sdate=array(date("m/d/Y",(time() - (86400 * 90))),date("m/d/Y",time()));
	
	$qryAa  = "select ";
	$qryAa .= "	 distinct(I.czip) as czip ";
	$qryAa .= "	,(SELECT count(cid) FROM jest..cinfo WHERE czip1=I.czip and officeid=I.oid and source!=0 and added >= '01/22/2008' and added <= '04/21/2008 23:59:59') as Leads ";
	$qryAa .= "	,(SELECT count(id) FROM IVR_stats..tIVR_events WHERE czip=I.czip and oid=I.oid and indate >= '01/22/2008' and indate <= '04/21/2008 23:59:59') as Calls  ";
	$qryAa .= "from  ";
	$qryAa .= "	IVR_stats..tIVR_events as I  ";
	$qryAa .= "where  ";
	$qryAa .= "	I.indate between '".$sdate[0]."' and '".$sdate[1]." 23:59:59' and ";
	
	if ($_SESSION['officeid']!=89)
	{
		$qryAa .= "	I.oid = ".$_SESSION['officeid']." and ";
	}
	
	$qryAa .= "	datalength(I.czip) = 5 ";
	$qryAa .= "order by  ";
	
	if (isset($_REQUEST['order']) && $_REQUEST['order'] == "Leads")
	{
		$qryAa .= "	Leads  ";
	}
	else
	{
		$qryAa .= "	Calls  ";
	}
	
	$qryAa .= "DESC; ";
	
	$resAa = mssql_query($qryAa);
	$nrowAa= mssql_num_rows($resAa);

	echo "<table align=\"center\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"center\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\" class=\"gray\"><b>IVR/Lead Activity</b></td>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>".$sdate[0]." - ".$sdate[1]."</b></td>\n";
	echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"subq2\" value=\"activity_zip\">\n";
	echo "					<td align=\"right\" class=\"gray\">\n";
	echo "							<input class=\"buttondkgryh10\" type=\"submit\" value=\"Zip Activity\">\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "   <tr>\n";
	echo "      <td align=\"center\">\n";
	echo "			<table class=\"outer\" width=100% border=\"0\">\n";

	if ($nrowAa > 0)
	{
		$t=0;
		
		if (isset($_REQUEST['fulllist']) && $_REQUEST['fulllist']==1)
		{
			$tlimit=$nrowAa;
		}
		else
		{
			$tlimit=30;
		}
		
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Zip Code</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>IVR Calls</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Manual Leads</b></td>\n";
		echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "							<input type=\"hidden\" name=\"subq2\" value=\"ivr_activity\">\n";
		
		if (isset($_REQUEST['fulllist']) && $_REQUEST['fulllist']==1)
		{
			echo "							<input type=\"hidden\" name=\"fulllist\" value=\"1\">\n";
		}
		
		echo "					<td align=\"right\" class=\"ltgray_und\">Sort:";
		echo "   					<select class=\"small\" name=\"order\" OnChange=\"this.form.submit();\">\n";
		
		if (isset($_REQUEST['order']) && $_REQUEST['order'] == "Leads")
		{
			echo "<option value=\"Leads\" SELECTED>Leads</option>\n";
			echo "<option value=\"Calls\">Calls</option>\n";
		}
		else
		{
			echo "<option value=\"Leads\">Leads</option>\n";
			echo "<option value=\"Calls\" SELECTED>Calls</option>\n";			
		}
		
		echo "   					</select>\n";				
		echo "					</td>\n";
		echo "						</form>\n";
		echo "				</tr>\n";
		
		while ($rowAa  = mssql_fetch_array($resAa))
		{
			$t++;
			if ($t <= $tlimit)
			{
				echo "				<tr>\n";
				echo "					<td class=\"wh_und\" align=\"center\">".$rowAa['czip']."</td>\n";
				echo "					<td class=\"wh_und\" align=\"center\">".$rowAa['Calls']."</td>\n";
				echo "					<td class=\"wh_und\" align=\"center\">".$rowAa['Leads']."</td>\n";
				echo "					<td class=\"wh_und\" align=\"center\"></td>\n";
				echo "				</tr>\n";
			}
		}
		
		if ($nrowAa >= 30 && $tlimit == 30)
		{
			echo "				<tr>\n";
			echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "							<input type=\"hidden\" name=\"subq2\" value=\"ivr_activity\">\n";
			echo "							<input type=\"hidden\" name=\"fulllist\" value=\"1\">\n";
			
			if (isset($_REQUEST['order']) && $_REQUEST['order'] == "Leads")
			{
				echo "							<input type=\"hidden\" name=\"order\" value=\"Leads\">\n";
			}
			
			echo "					<td colspan=\"4\" class=\"wh_und\" align=\"right\">\n";
			echo "							<input class=\"buttondkgryh10\" type=\"submit\" value=\"Full List\">\n";
			echo "					</td>\n";
			echo "						</form>\n";
			echo "				</tr>\n";
		}
	}
	else
	{
		echo "				<tr>\n";
		echo "					<td class=\"wh_und\" align=\"left\">None during this timeframe</td>\n";
		echo "				</tr>\n";
	}
	
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function activity_zip()
{
	//show_post_vars();
	//$sdate=array(date("m/d/Y",(time()-7776000)),date("m/d/Y",time()));
    $sdate=array(date("m/d/Y",(time() - (86400 * 90))),date("m/d/Y",time()));
	
	$qryAa  = "select ";
	$qryAa .= "	o.officeid ";
	$qryAa .= "	,o.name ";
	$qryAa .= "	,o.zip ";
	$qryAa .= "	,z.ozip ";
	$qryAa .= "	,z.czip ";
	$qryAa .= "	,z.updated ";
	
	if ($_SESSION['officeid']!=89)
	{
		$qryAa .= "	,(select count(id) from IVR_stats..tIVR_events where oid=".$_SESSION['officeid']." and czip=z.czip and indate > '".$sdate[0]."' and indate <= '".$sdate[1]." 23:59:59') as calls ";
	}
	else
	{
		$qryAa .= "	,(select count(id) from IVR_stats..tIVR_events where czip=z.czip and indate > '".$sdate[0]."' and indate <= '".$sdate[1]." 23:59:59') as calls ";
	}
	
	$qryAa .= "	,(select lname from jest..security where securityid=z.updtby) as updater ";
	$qryAa .= "from ";
	$qryAa .= "	jest..offices as o ";
	$qryAa .= "inner join ";
	$qryAa .= "	jest..zip_to_zip as z ";
	$qryAa .= "on ";
	$qryAa .= "	o.zip=z.ozip ";
	$qryAa .= "where  ";
	
	if ($_SESSION['officeid']!=89)
	{
		$qryAa .= "	officeid=".$_SESSION['officeid']." and ";
	}
	
	$qryAa .= "	updated >  '".$sdate[0]."' and ";
	$qryAa .= "	updated <= '".$sdate[1]." 23:59:59' ";
	$qryAa .= "order by ";
	$qryAa .= "	z.updated DESC; ";
	
	$resAa = mssql_query($qryAa);
	$nrowAa= mssql_num_rows($resAa);
	//echo $nrowAa."<br>";
	//echo $qryAa."<br>";
	echo "<table align=\"center\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"center\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\" class=\"gray\"><b>Zip Code Update Activity</b></td>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>".$sdate[0]." - ".$sdate[1]."</b></td>\n";
	echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"subq2\" value=\"activity_ivr\">\n";
	echo "					<td align=\"right\" valign=\"top\" class=\"gray\">\n";
	echo "							<input class=\"buttondkgryh10\" type=\"submit\" value=\"IVR Activity\">\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"center\">\n";
	echo "			<table class=\"outer\" width=100% border=\"0\">\n";

	if ($nrowAa > 0)
	{
		$t=0;
		
		if (isset($_REQUEST['fulllist']) && $_REQUEST['fulllist']==1)
		{
			$tlimit=$nrowAa;
		}
		else
		{
			$tlimit=30;
		}        
        
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Office</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Zip</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Updated</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>by</b></td>\n";
		echo "				</tr>\n";
		
		while ($rowAa  = mssql_fetch_array($resAa))
		{
            if ((time() - strtotime($rowAa['updated'])) <= (86400 * 30))
            {
                $fstyle="red";
            }
            elseif ((time() - strtotime($rowAa['updated'])) <= (86400 * 60))
            {
                $fstyle="blue";
            }
            else
            {
                $fstyle="black";
            }
            
			$t++;
			if ($t <= $tlimit)
			{
				echo "				<tr>\n";
				//echo "					<td class=\"wh_und\" align=\"left\"><font color=\"".$fstyle."\">".$rowAa['name']."</font>(".date("m/d/y",strtotime($rowAa['updated'])).")(".date("m/d/y",(time() - 2592000)).")</td>\n";
                echo "					<td class=\"wh_und\" align=\"left\"><font color=\"".$fstyle."\">".$rowAa['name']."</font></td>\n";
				echo "					<td class=\"wh_und\" align=\"center\"><font color=\"".$fstyle."\">".$rowAa['czip']."</font></td>\n";
				echo "					<td class=\"wh_und\" align=\"center\"><font color=\"".$fstyle."\">".date("m/d/Y",strtotime($rowAa['updated']))."</font></td>\n";
				echo "					<td class=\"wh_und\" align=\"left\"><font color=\"".$fstyle."\">".$rowAa['updater']."</font></td>\n";
				echo "				</tr>\n";
			}
		}
		
		if ($nrowAa >= 30 && $tlimit == 30)
		{
			echo "				<tr>\n";
			echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "							<input type=\"hidden\" name=\"subq2\" value=\"activity_zip\">\n";
			echo "							<input type=\"hidden\" name=\"fulllist\" value=\"1\">\n";			
			echo "					<td colspan=\"4\" class=\"wh_und\" align=\"right\">\n";
			echo "							<input class=\"buttondkgryh10\" type=\"submit\" value=\"Full List\">\n";
			echo "					</td>\n";
			echo "						</form>\n";
			echo "				</tr>\n";
		}
	}
	else
	{
		echo "				<tr>\n";
		echo "					<td class=\"wh_und\" align=\"left\">None during this timeframe</td>\n";
		echo "				</tr>\n";
	}
	
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function activity_leads_admin()
{
	$sdate=set_sdate();
	$icnt	=0;
	$mcnt	=0;
	//$qry0	= "SELECT tooffice FROM lead_inc WHERE added >= '".$sdate[0]."';";
	$qry0  = "SELECT ";
	$qry0	.= "	DISTINCT(L.tooffice), ";
	$qry0	.= "	O.name, ";
	$qry0	.= "	(SELECT COUNT(lid) FROM lead_inc as L2 WHERE tooffice=O.officeid and L2.added >= '".$sdate[0]."') as lcnt ";
	$qry0	.= "FROM ";
	$qry0	.= "	lead_inc as L ";
	$qry0	.= "INNER JOIN ";
	$qry0	.= "	offices as O ";
	$qry0	.= "ON ";
	$qry0	.= "	L.tooffice=O.officeid ";
	$qry0	.= "WHERE ";
	$qry0	.= "	L.added >= '".$sdate[0]."' ";
	$qry0	.= "ORDER BY ";
	$qry0	.= "	O.name ASC;";
	$res0	= mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	$qry1	 = "SELECT ";
	$qry1	.= "	DISTINCT(c.officeid), ";
	$qry1	.= "	o.name, ";
	$qry1	.= "	(SELECT COUNT(cid) FROM cinfo WHERE officeid=c.officeid and added >= '".$sdate[0]."' and source!=0 and source!=44 and dupe!=1) as ccnt ";
	$qry1	.= "FROM ";
	$qry1	.= "	cinfo AS c ";
	$qry1	.= "INNER JOIN ";
	$qry1	.= "	offices AS o ";
	$qry1	.= "ON ";
	$qry1	.= "	c.officeid=o.officeid ";
	$qry1	.= "WHERE ";
	$qry1	.= "	c.added >= '".$sdate[0]."' and c.source!=0 and c.source!=44 ";
	$qry1	.= "ORDER BY ";
	$qry1	.= "	o.name ASC;";
	$res1	= mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	echo "<table align=\"center\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\" align=\"center\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>Lead Activity Report</b></td>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>".$sdate[0]." - ".$sdate[1]."</b></td>\n";
	echo "					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"subq2\" value=\"activity_job\">\n";
	echo "					<td align=\"right\" valign=\"top\" class=\"gray\">\n";
	//echo "							<input class=\"buttondkgryh10\" type=\"submit\" value=\"Job Activity\">\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\" colspan=\"2\"><b>BHNM Provided Leads</b></td>\n";
	echo "				</tr>\n";

	if ($nrow0 > 0)
	{	
		while ($row0= mssql_fetch_array($res0))
		{
			echo "			<tr>\n";
			echo "				<td class=\"wh_und\" align=\"right\" width=\"50%\">".$row0['name']."</td>\n";
			echo "				<td class=\"wh_und\" align=\"left\" width=\"50%\">".$row0['lcnt']."</td>\n";
			echo "			</tr>\n";
			$icnt=$icnt+$row0['lcnt'];
		}
		
		if ($icnt!=0)
		{
			echo "				<tr>\n";
			echo "					<td class=\"ltgray_und\" align=\"right\" width=\"50%\"><b>Total</b></td>\n";
			echo "					<td class=\"ltgray_und\" align=\"left\" width=\"50%\">".$icnt."</td>\n";
			echo "				</tr>\n";
		}
	}
	else
	{
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" align=\"center\" width=\"100%\" colspn=\"2\"><b>No BHNM Provided Leads for this Time Period</b></td>\n";
		echo "			</tr>\n";
	}
	
	echo "			</table>\n";	
	echo "		</td>\n";
	echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\" colspan=\"2\"><b>Manual Leads</b></td>\n";
	echo "				</tr>\n";
		
	if ($nrow1 > 0)
	{
		while ($row1= mssql_fetch_array($res1))
		{
			echo "			<tr>\n";
			echo "				<td class=\"wh_und\" align=\"right\" width=\"50%\">".$row1['name']."</td>\n";
			echo "				<td class=\"wh_und\" align=\"left\" width=\"50%\">".$row1['ccnt']."</td>\n";
			echo "			</tr>\n";
			$mcnt=$mcnt+$row1['ccnt'];
		}
		
		if ($mcnt!=0)
		{
			echo "				<tr>\n";
			echo "					<td class=\"ltgray_und\" align=\"right\" width=\"50%\"><b>Total</b></td>\n";
			echo "					<td class=\"ltgray_und\" align=\"left\" width=\"50%\">".$mcnt."</td>\n";
			echo "				</tr>\n";
		}
	}
	else
	{
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" align=\"center\" width=\"100%\"><b>No Lead Entries for this Time Period</b></td>\n";
		echo "			</tr>\n";
	}
	
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function activity_leads_office()
{
	$sdate=set_sdate();
	
	//$qry0	= "SELECT tooffice FROM lead_inc WHERE added >= '".$sdate[0]."';";
	$qry0  = "SELECT ";
	$qry0	.= "	DISTINCT(L.tooffice), ";
	$qry0	.= "	O.name, ";
	$qry0	.= "	(SELECT COUNT(lid) FROM lead_inc as L2 WHERE tooffice=O.officeid and L2.added >= '".$sdate[0]."') as lcnt ";
	$qry0	.= "FROM ";
	$qry0	.= "	lead_inc as L ";
	$qry0	.= "INNER JOIN ";
	$qry0	.= "	offices as O ";
	$qry0	.= "ON ";
	$qry0	.= "	L.tooffice=O.officeid ";
	$qry0	.= "WHERE ";
	$qry0	.= "	L.added >= '".$sdate[0]."' and ";
	$qry0	.= "	O.officeid = '".$_SESSION['officeid']."' ";
	$qry0	.= "ORDER BY ";
	$qry0	.= "	O.name ASC;";
	$res0	 = mssql_query($qry0);
	$nrow0 = mssql_num_rows($res0);
	
	//if ($_SESSION['securityid']==26)
	//{
	//	echo $qry0."<br>";
	//}
	
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
	$qry1	.= "	c.source!=0 and ";
	$qry1	.= "	c.source!=1 and ";
    $qry1	.= "	c.source!=44 ";
	$qry1	.= "ORDER BY  ";
	$qry1	.= "	l.name ASC;	 ";
	
	$res1	 = mssql_query($qry1);
	$nrow1 = mssql_num_rows($res1);
	
	echo "<table align=\"center\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\" align=\"center\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>Lead Activity Report</b></td>\n";
	echo "					<td align=\"center\" class=\"gray\"><b>".$sdate[0]." - ".$sdate[1]."</b></td>\n";
    echo "                      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"subq2\" value=\"activity_job\">\n";
	echo "					<td align=\"right\" valign=\"top\" class=\"gray\">\n";
	//echo "							<input class=\"buttondkgryh10\" type=\"submit\" value=\"Job Activity\">\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\" colspan=\"2\"><b>BHNM Provided Leads</b></td>\n";
	echo "				</tr>\n";

	if ($nrow0 > 0)
	{	
		while ($row0= mssql_fetch_array($res0))
		{
			echo "			<tr>\n";
			echo "				<td class=\"wh_und\" align=\"right\" width=\"50%\">".$row0['name']."</td>\n";
			echo "				<td class=\"wh_und\" align=\"left\" width=\"50%\">".$row0['lcnt']."</td>\n";
			echo "			</tr>\n";
		}
	}
	else
	{
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" align=\"center\" width=\"100%\"><b>None</b></td>\n";
		echo "			</tr>\n";
	}
	
	echo "			</table>\n";	
	echo "		</td>\n";
	echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\" colspan=\"2\"><b>Manual Leads</b></td>\n";
	echo "				</tr>\n";
	
	if ($nrow1 > 0)
	{	
		while ($row1= mssql_fetch_array($res1))
		{
			echo "			<tr>\n";
			echo "				<td class=\"wh_und\" align=\"right\" width=\"50%\">".$row1['name']."</td>\n";
			echo "				<td class=\"wh_und\" align=\"left\" width=\"50%\">".$row1['ccnt']."</td>\n";
			echo "			</tr>\n";
		}
	}
	else
	{
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" align=\"center\" width=\"100%\"><b>None</b></td>\n";
		echo "			</tr>\n";
	}
	
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function show_sys_messages()
{
    error_reporting(JMS_DEBUG);
    
    //echo "TEST1<br>";
	$qry1	= "SELECT endigreport,gm,am,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1	= mssql_query($qry1);
	$row1	= mssql_fetch_array($res1);
	
	//print_r($row1);
	
	if (empty($_SESSION['action'])||$_SESSION['action']=="None"||$_SESSION['action']=="main"||$_SESSION['action']=="update_off")
	{
		echo "		<tr>\n";
		echo "			<td valign=\"top\" align=\"center\">\n";
      
		systemwidemessage();
		
		echo "			</td>\n";
		echo "		</tr>\n";
		
		if ($_SESSION['officeid']==89)
		{
			if ($_SESSION['llev'] >= 9)
			{
				echo "				<tr>\n";
				echo "      			<td valign=\"top\" align=\"center\">\n";
				echo "						<table width=\"100%\">\n";
				echo "							<tr>\n";
				echo "      						<td align=\"center\" valign=\"top\" width=\"65%\">\n";
		
				activity_leads_admin();
			
				echo "      						</td>\n";
				echo "      						<td align=\"center\"  valign=\"top\" width=\"35%\">\n";
		
				if (isset($_REQUEST['subq2']) && $_REQUEST['subq2']=='activity_zip')
				{
					activity_zip();
				}
				else
				{
					activity_ivr();
				}
			
				echo "      						</td>\n";
				echo "							</tr>\n";
				echo "						</table>\n";
				echo "      			</td>\n";
				echo "				</tr>\n";
			}
		}
		else
		{
			//echo "0<BR>";
			//echo "Access: ".$_SESSION['securityid'].":".$row1['gm'].":".$row1['am'].":".$row1['finan_off']."<br>";
			/*
			if ($row0['startpage']!=0 && empty($_SESSION['action'])||$_SESSION['action']=="None"||$_SESSION['action']=="main"||$_SESSION['action']=="update_off")
			{
				echo "XX<BR>";
				echo "				<tr>\n";
				echo "      			<td align=\"center\">\n";
				//echo "						Custom Start Page";
				
				if ($row0['startpage']==1)
				{
					include_once(".\calendar_func.php");
					showCalendar_full();
					//showWeek_full($sday,$month,$year,$_SESSION['securityid']);
				}
				
				echo "      			</td>\n";
				echo "				</tr>\n";
			}
			else
			{
			*/
				//echo "1<BR>";
				if ($_SESSION['llev'] >= 9 || $_SESSION['securityid'] == $row1['gm'] || $_SESSION['securityid'] == $row1['am'])
				{
					//echo "2<BR>";
					if ($row1['finan_off'] != 1)
					{
						//echo "3<BR>";
						echo "				<tr>\n";
						echo "      			<td valign=\"top\" align=\"center\">\n";
						echo "						<table width=\"100%\">\n";
						echo "							<tr>\n";
						echo "      						<td align=\"center\" valign=\"top\" width=\"60%\">\n";
				
                        if (isset($_REQUEST['subq2']) && $_REQUEST['subq2']=='activity_job')
                        {
                            activity_job();
                        }
                        else
                        {
                            activity_leads_office();
                        }
						//lead_report_daily_office();
					
						echo "      						</td>\n";
						echo "      						<td align=\"center\"  valign=\"top\" width=\"40%\">\n";
					
						activity_csr_office();
					
						echo "      						</td>\n";
						echo "							</tr>\n";
						echo "						</table>\n";
						echo "      			</td>\n";
						echo "				</tr>\n";
					}
				}
			//}
		}
      
      echo "		<tr>\n";
      echo "			<td align=\"center\">\n";
      
      //show_pbperc_overage();
      
      echo "			</td>\n";
      echo "		</tr>\n";
	}
    
    //echo "TEST2<br>";
}

function show_pbperc_overage()
{
	if ($_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==FDBK_ADMIN)
	{
		$qryA = "
					SELECT *,
							(select name from offices where officeid=pb.oid) as name
					FROM
							pb_perc_overage as pb
					WHERE
							ackn=0
					order by
							name asc,adddate desc;
					";
	}
	elseif ($_SESSION['elev'] >= 6 && $_SESSION['clev'] >= 6)
	{
		$qryA = "SELECT *,(select name from offices where officeid=pb.oid) as name FROM pb_perc_overage as pb WHERE oid=".$_SESSION['officeid']." and ackn=0 order by adddate desc;";
	}
	
	$resA = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);
	
	if ($nrowA > 0)
	{
		echo "<table align=\"center\" width=\"60%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" align=\"center\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>Pricebook Analyzer Activity</b></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"center\" valign=\"top\" width=\"100%\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Office</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Item</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Profit %</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Alarm %</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Var %</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Ackn</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>View</b></td>\n";
		echo "				</tr>\n";
		
		$pbcnt=1;
		while ($rowA = mssql_fetch_array($resA))
		{
			//show_array_vars($rowA);
			echo "				<tr>\n";
			echo "					<td class=\"ltgray_und\" align=\"right\">".$pbcnt++.".</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\">".$rowA['name']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\">".$rowA['iname']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"right\">".$rowA['cprof']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"right\">".$rowA['aprof']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"right\">".$rowA['vprof']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$rowA['rid']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$rowA['rid']."</td>\n";
			echo "				</tr>\n";
		}
		
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" colspan=\"6\" align=\"center\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Ackn</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function disp_cost_biditems($phsid,$jadd)
{
	error_reporting(E_ALL);
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;
	
	$MAS			=$_SESSION['pb_code'];
	$viewarray	=$_SESSION['viewarray'];
	$out			=0;

	if ($_SESSION['action']=="est")
	{
		$qryA = "SELECT * FROM bid_breakout WHERE officeid='".$_SESSION['officeid']."' and estid='".$viewarray['estid']."' and phsid='".$phsid."';";
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
	
	//echo $qryA.'<br>';
	
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
	
	$MAS			=$_SESSION['pb_code'];
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

function manphsadj_rollup_disp($oid,$jid,$jadd,$jc)
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
	
	/*
	if ($_SESSION['action']=="contract")
	{
		$qryA		= "SELECT officeid,jobid,njobid,estdata FROM jdetail WHERE officeid='".$oid."' and jobid='".$jid."' and jadd".$joprtr.$jadd.";";
	}
	else
	{
		$qryA		= "SELECT officeid,jobid,njobid,estdata FROM jdetail WHERE officeid='".$oid."' and njobid='".$jid."' and jadd".$joprtr.$jadd.";";
	}
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	*/
	
	if ($jadd==0)
	{
		$qryAa = "SELECT cid,mas_prep FROM cinfo WHERE officeid='".$oid."' and ".$jfield."='".$jid."';";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		
		$masprep=$rowAa['mas_prep'];
	}
	else
	{
		$masprep=0;
	}
	
	//echo $qryA.'<br>';

	$qryC = "SELECT count(id) as idcnt FROM man_phs_adj WHERE officeid='".$oid."' and ".$jfield."='".$jid."' and phsid!=0;";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);
				
	//echo $qryC.'<br>';
	//echo "IDCNT: ".$rowC['idcnt'].'<br>';
				
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
			echo "									<td NOWRAP align=\"center\" valign=\"bottom\" class=\"gray\">\n";
			
			if ($jadd > 0)
			{
				if ($pmasreq >= 1)
				{
					echo "".$v2."";
					
					if ($_SESSION['action']!="est" && $rowC['idcnt'] > 0)
					{
						echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vmpa&oid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2].")";
					}
				}
				else
				{
					echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=mpaadd&officeid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&jadd=".$jadd."&pb_code=".$MAS."&rdbid=".$n2."&cdbid=".$costid."&costid=".$costid."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$v2."</a>\n";
					
					if ($_SESSION['action']!="est" && $rowC['idcnt'] > 0)
					{
						echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vmpa&oid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
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
						echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vmpa&oid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2].")";
					}
				}
				else
				{
					echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=mpaadd&officeid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&jadd=".$jadd."&pb_code=".$MAS."&rdbid=".$n2."&cdbid=".$costid."&costid=".$costid."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$v2."</a>\n";
					
					if ($rowC['idcnt'] > 0)
					{
						echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vmpa&oid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
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

function costadj_rollup_disp($oid,$jid,$jadd,$jc)
{
	$MAS	=$_SESSION['pb_code'];
	$ric_ar	=array();
	$rid_ar	=array();
	$rin_ar	=array();
	$btype	=33;
	$cl		=1;
	$costid	=0;
	$pmasreq	=0;
	
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
		$qryAa = "SELECT cid,mas_prep FROM cinfo WHERE officeid='".$oid."' and ".$jfield."='".$jid."';";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		
		$masprep=$rowAa['mas_prep'];
	}
	else
	{
		$masprep=0;
	}
	
	//echo $qryA.'<br>';
	$ri_ar=explode(",",$rowA['estdata']);
	
	foreach ($ri_ar as $n1 => $v1)
	{
		$rii_ar=explode(":",$v1);
		
		//if (isset($rii_ar[0]) && $rii_ar[0]!=0)
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
				
				//echo $qryC.'<br>';
				//echo $rowC['idcnt'].'<br>';
				
				$rin_ar[$rii_ar[0]]=$rowB['item'];
				
				if ($rowC['idcnt'] < 1)
				{
					$ric_ar[$rii_ar[0]]=0;
				}
				else
				{
					$ric_ar[$rii_ar[0]]=$rowC['idcnt'];
				}
			}
		}
	}
	
	echo "	<table width=\"100%\">\n";
	echo "		<tr>\n";
	echo "			<td width=\"125px\" align=\"left\" valign=\"top\">\n";

	manphsadj_rollup_disp($oid,$jid,$jadd,$jc);
	
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
            
			echo "									<td NOWRAP align=\"left\" valign=\"bottom\" class=\"gray\">\n";
            
			if ($jadd > 0)
			{
				if ($pmasreq >= 1)
				{
					echo "".$v2."";
					
					//if ($_SESSION['action']!="est" && $rowC['idcnt'] > 0)
					if ($ric_ar[$n2] > 0)
					{
						echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vac&oid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2].")";
					}
				}
				else
				{
					echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=bidadd&officeid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&jadd=".$jadd."&pb_code=".$MAS."&rdbid=".$n2."&cdbid=".$costid."&costid=".$costid."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$v2."</a>\n";
					
					//if ($_SESSION['action']!="est" && $rowC['idcnt'] > 0)
					if ($ric_ar[$n2] > 0)
					{
						echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vac&oid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
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
					
					if ($ric_ar[$n2] > 0)
					{
						echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vac&oid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2].")";
					}
				}
				else
				{
					echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=bidadd&officeid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&jadd=".$jadd."&pb_code=".$MAS."&rdbid=".$n2."&cdbid=".$costid."&costid=".$costid."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$v2."</a>\n";
					
					if ($ric_ar[$n2] > 0)
					{
						echo "										<a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=vac&oid=".$oid."&action=".$_SESSION['action']."&jid=".$jid."&pb_code=".$MAS."&rdbid=".$n2."\" target=\"JMSbidadd\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSbidadd','HEIGHT=300,WIDTH=400,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"> (".$ric_ar[$n2].")</a>\n";
					}
					else
					{
						echo " (".$ric_ar[$n2].")";
					}
				}
			}
            
			echo "									</td>\n";
            
            //echo "(".$ij.")";
            
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
	
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function pbvalidate_msg($pbvalidate,$bdate,$edate)
{
	if ($pbvalidate==1)
	{
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

function set_digdate()
{
	$err = "<font color=\"red\"><b>ERROR</b></font> Date Incorrect or Validate isn't checked.";
	$isvaliddate	=valid_date($_REQUEST['digdate']);

	if (strlen($_REQUEST['digdate']) < 6 && isset($_REQUEST['chkdig']) && $_REQUEST['chkdig']==1)
	{
		//$dd	='NULL';
		$qry = "UPDATE jobs SET digdate=NULL,digsec='".$_SESSION['securityid']."' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
		$res = mssql_query($qry);

		chistory_list();
	}
	elseif ($isvaliddate==1 && isset($_REQUEST['chkdig']) && $_REQUEST['chkdig']==1)
	{
		$dd	=$_REQUEST['digdate']." 00:01";
		$ct	=strtotime($_REQUEST['cdate']);
		$dt	=strtotime($_REQUEST['digdate']);
		//echo "C: ".$ct."<br>";
		//echo "D: ".$dt."<br>";
		if ($dt >= $ct)
		{
			//$qry = "UPDATE jobs SET digdate='".$_REQUEST['digdate']." 00:01',digsec='".$_SESSION['securityid']."' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
			$qry = "UPDATE jobs SET digdate='".$dd."',digsec='".$_SESSION['securityid']."' WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."';";
			$res = mssql_query($qry);

			chistory_list();
		}
		else
		{
			echo "ONE<BR>";
			echo $err;
		}
	}
	else
	{
		//echo E_ERROR;
		echo "TWO<BR>";
		echo $err;
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

function chistory_addTEST()
{
	if (empty($_REQUEST['tranid']) || $_REQUEST['tranid']==0)
	{
		echo "Tranisition Error. Exitting...";
		exit;
	}
	
	if (empty($_REQUEST['mtext']))
	{
		echo "Empty Comment Text<br>Click BACK and Enter fill out the Comments Box.";
		exit;
	}

	$qry = "SELECT * FROM chistory WHERE custid='".$_REQUEST['cid']."' and tranid='".$_REQUEST['tranid']."';";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($nrow > 0)
	{
		echo "<font color=\"red\">ERROR!</font> Duplicate Entry Found.";
	}
	else
	{
		show_post_vars();
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
		$cmtflg_ar=explode(":",$_REQUEST['commentflag']);
	}

	$qry = "SELECT * FROM chistory WHERE custid='".$_REQUEST['cid']."' and tranid='".$_REQUEST['tranid']."';";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($nrow > 0)
	{
		echo "<font color=\"red\">ERROR!</font> Duplicate Entry Found.";
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
		//echo $inputtext."<br>";
		
		$qry0  = "INSERT INTO chistory (officeid,secid,custid,act,tranid,mtext,complaint,followup,resolved,relatedcomplaint,cservice) ";
		$qry0 .= "VALUES ";
		$qry0 .= "('".$oid."','".$_SESSION['securityid']."','".$_REQUEST['cid']."','".$action."','".$_REQUEST['tranid']."','".$inputtext."',".$complaint.",".$followup.",".$resolve.",".$relid.",".$cservice.");";
		$res0  = mssql_query($qry0);
		//echo $qry0."<br>";
		
		$qry1  = "UPDATE cinfo set updated=getdate() ";
		$qry1 .= "WHERE cid='".$_REQUEST['cid']."';";
		$res1  = mssql_query($qry1);
		//echo $qry1."<br>";

		if (!empty($_REQUEST['action']))
		{
			chistory_list();
		}
	}
}

function remove_element($arr, $val)
{
	foreach ($arr as $key => $value)
	{
		if ($arr[$key] == $val)
		{
			unset($arr[$key]);
		}
	}
	return $arr = array_values($arr);
}


function chistory_list()
{
	//error_reporting(E_ALL);
	//show_post_vars();
	
	$tranid=time().".".$_REQUEST['cid'].".".$_SESSION['securityid'];
	$sdate = "";
	$udate = "";
	$fdate = "";
	$fudate = "";
	$fdadate="";
	$tcellwidth='900';
	//$dcellwidth='475';
	$dcellwidth=(($tcellwidth - 2) / 2);
	
	$qryA = "SELECT officeid,finan_off,finan_from,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if ($rowA['finan_off']==1)
	{
		//echo "OID POST<br>";
		$oid=$_REQUEST['officeid'];
	}
	else
	{
		//echo "OID SESS<br>";
		$oid=$_SESSION['officeid'];
	}
    
    $qry = "SELECT * FROM cinfo WHERE cid='".$_REQUEST['cid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
    $oid	=$row['officeid'];
	
	//$qryB = "SELECT estid,officeid,cid FROM est WHERE officeid=".$oid." and cid='".$_REQUEST['cid']."';";
	$qryB = "SELECT estid,officeid,cid,esttype,added,updated FROM est WHERE officeid=".$row['officeid']." and cid='".$row['cid']."';";
	$resB = mssql_query($qryB);
	$nrowB= mssql_num_rows($resB);
	
	//echo $qryB.'<br>';
	
	$qryC = "SELECT * FROM offices WHERE officeid='".$oid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);
    
    $qryCa = "SELECT * FROM offices WHERE officeid='".$rowA['finan_from']."';";
	$resCa = mssql_query($qryCa);
	$rowCa = mssql_fetch_array($resCa);

	$qry0 = "SELECT c.* FROM chistory AS c WHERE c.custid='".$_REQUEST['cid']."' ORDER BY c.mdate DESC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	$qry0ct = "SELECT id FROM chistory WHERE custid='".$_REQUEST['cid']."' and complaint=0 and cservice=0;";
	$res0ct = mssql_query($qry0ct);
	$nrow0ct= mssql_num_rows($res0ct);
	
	$qry0co = "SELECT distinct(id) FROM chistory WHERE custid='".$_REQUEST['cid']."' and complaint=1 and followup=0 and resolved=0;";
	$res0co = mssql_query($qry0co);
	$nrow0co= mssql_num_rows($res0co);
	
	$qry0cr = "SELECT id FROM chistory WHERE custid='".$_REQUEST['cid']."' and complaint=1 and resolved=1;";
	$res0cr = mssql_query($qry0cr);
	$nrow0cr= mssql_num_rows($res0cr);
	
	$qry0ro = "SELECT distinct(id) FROM chistory WHERE custid='".$_REQUEST['cid']."' and cservice=1 and followup=0 and resolved=0;";
	$res0ro = mssql_query($qry0ro);
	$nrow0ro= mssql_num_rows($res0ro);
	
	$qry0rr = "SELECT id FROM chistory WHERE custid='".$_REQUEST['cid']."' and cservice=1 and resolved=1;";
	$res0rr = mssql_query($qry0rr);
	$nrow0rr= mssql_num_rows($res0rr);
	
	//$qry1 = "SELECT cid,recdate,amtfinan,lupdate FROM tfinan_detail WHERE officeid='".$oid."' AND finan_from='".$_REQUEST['fofficeid']."' AND cid='".$row['cid']."';";
	$qry1 = "SELECT cid,recdate,amtfinan,lupdate FROM tfinan_detail WHERE cid='".$row['cid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	
	//echo $qry."<br>";
	
	if ($nrow1 > 0)
	{
		$fdadate=date("m/d/Y", strtotime($row1['recdate']));
	}

	$qryD = "SELECT mas_div FROM security WHERE securityid='".$row['securityid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);
	
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

	$brdr=0;

	//echo "UD:".$row['updated']."<br>";
	echo "<table width=\"".$tcellwidth."\" cellspacing=\"0\" cellpadding=\"1\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\" valign=\"bottom\">\n";
	echo "			<table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Customer LifeCycle Information</b></td>\n";
	echo "              	<td class=\"gray\" align=\"right\"></td>\n";
	echo "         				<form name=\"searchresults\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "              	<td class=\"gray\" valign=\"bottom\" align=\"right\">\n";
	
	$dtx="";
	$dis="";
	
	if (isset($_REQUEST['csearch']) && $_REQUEST['csearch']==1 || !isset($_SESSION['tqry']))
	{
		$dtx="Disabled. This feature currently only works in Contact Search.";
		$dis="DISABLED";	
	}
	
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search Results\" ".$dis." title=\"".$dtx."\">\n";
	echo "					</td>\n";
	echo "				</form>\n";
	echo "				<form method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";

	if ($_REQUEST['action']=="leads")
	{
		echo "			<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
	}
	elseif ($_REQUEST['action']=="est")
	{
		echo "			<input type=\"hidden\" name=\"estid\" value=\"".$_REQUEST['estid']."\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	}
	elseif ($_REQUEST['action']=="contract")
	{
		echo "			<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
		echo "			<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	}
	elseif ($_REQUEST['action']=="job")
	{
		echo "			<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
		echo "			<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	}
	elseif ($_REQUEST['action']=="mas")
	{
		echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "			<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"MAS_detail\">\n";
		echo "			<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	}

	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
	echo "			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "				<td class=\"gray\" align=\"right\" valign=\"bottom\">\n";

	if ($rowA['finan_off']!=1)
	{
		if ($_REQUEST['action']=="leads"||$_REQUEST['action']=="est"||$_REQUEST['action']=="contract"||$_REQUEST['action']=="job"||$_REQUEST['action']=="mas")
		{
			echo "			<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Return\">\n";
		}
	}
	else
	{
		echo "			<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Return\" DISABLE>\n";
	}

	echo "				</td>\n";
	echo "			  </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "		</form>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td width=\"".$dcellwidth."\" valign=\"top\">\n";
	
	//cinfo_display_new($oid,$_REQUEST['cid'],$rowC['stax']);
	cinfo_display_chistory($oid,$_REQUEST['cid'],$rowC['stax']);

	echo "		</td>\n";
	echo "      <td width=\"".$dcellwidth."\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\" height=\"170\">\n";
	echo "	   			<tr>\n";
	echo "      			<td align=\"right\" valign=\"top\" class=\"gray\">\n";
	echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "	   					<tr>\n";
	echo "      					<td colspan=\"5\" class=\"ltgray_und\" align=\"left\"><b>System Dates</b></td>\n";
	echo "   					</tr>\n";	

	if ($_SESSION['llev']!=0 && $row['cid']!=0)
	{
		$uid	=md5(session_id().time().$row['custid']).".".$_SESSION['securityid'];
		
		echo "	   					<tr>\n";
		echo "      						<td class=\"gray\" align=\"right\" width=\"75\"></td>\n";
		echo "      						<td class=\"gray\" align=\"left\"></td>\n";
		echo "      						<td class=\"gray\" align=\"center\"><b>Added</b></td>\n";
		echo "      						<td class=\"gray\" align=\"center\"><b>Updated</b></td>\n";
		echo "      						<td class=\"gray\" align=\"center\"></td>\n";
		echo "   					</tr>\n";
		echo "	   					<tr>\n";
		echo "      						<td align=\"right\" class=\"gray\" width=\"75\"><b>Lead:</b></td>\n";
		//echo "      						<td align=\"left\" class=\"gray\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row['custid']."\" DISABLED></td>\n";
		echo "      						<td align=\"left\" class=\"gray\">".$row['custid']."</td>\n";
		echo "      						<td align=\"center\" class=\"gray\">".$sdate."</td>\n";
		echo "      						<td align=\"center\" class=\"gray\">".$udate."</td>\n";
		echo "      						<td align=\"left\" class=\"gray\">\n";
		
		if ($rowA['finan_off']==0)
		{
			echo "                        <form name=\"viewlead\" method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			echo "                           <input class=\"checkboxgry\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
			echo "                        </form>\n";
		}
		
		echo "								</td>\n";
		echo "   						</tr>\n";
	}

//echo $nrowB.'<br>';

	if ($_SESSION['elev']!=0 && $nrowB > 0)
	{
		while ($rowB = mssql_fetch_array($resB))
		{
			echo "                        <form name=\"viewest\" method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
			echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$rowB['estid']."\">\n";
			echo "	   					<tr>\n";
			echo "      						<td align=\"right\" class=\"gray\" width=\"75\"><b>\n";
			
			if ($rowB['esttype']=='E')
			{
				echo 'Estimate:';
			}
			else
			{
				echo 'Quote:';
			}
			
			echo "</b></td>\n";
			echo "      						<td align=\"left\" class=\"gray\">\n";
			echo $rowB['estid'];
			/*echo "									<select name=\"estid\" onChange=\"this.form.submit();\">\n";
			echo "										<option value=\"0\">Select...</option>\n";
			echo "										<option value=\"".$rowB['estid']."\">".$rowB['estid']." ".$rowB['esttype']."</option>\n";
			//echo "<input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row['estid']."\" DISABLED>\n";
			echo "									</select>\n";*/
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
			
			if ($rowA['finan_off']==0)
			{
				if ($rowB['esttype']=='E')
				{
					echo "                           <input class=\"checkboxgry\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Estimate\">\n";
					//echo 'Estimate';
				}
				else
				{
					echo "                           <input class=\"checkboxgry\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Quote\">\n";
					//echo 'Quote';
				}
			}
			
			echo "								</td>\n";
			echo "   						</tr>\n";
			echo "						</form>\n";
		}
	}
	
	if ($_SESSION['clev']!=0 && $row['jobid']!='0')
	{
		echo "	   					<tr>\n";
		echo "      						<td align=\"right\" class=\"gray\" width=\"75\"><b>Contr:</b></td>\n";
		echo "      						<td align=\"left\" class=\"gray\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row['jobid']."\" DISABLED></td>\n";
		echo "      						<td align=\"center\" class=\"gray\">".$cadate."</td>\n";
		echo "      						<td align=\"center\" class=\"gray\">".$cudate."</td>\n";
		echo "      						<td align=\"left\" class=\"gray\">\n";
		
		if ($rowA['finan_off']==0)
		{
			echo "                        <form name=\"viewcon\" method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
			echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$row['jobid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "                           <input class=\"checkboxgry\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Contract\">\n";
			echo "                        </form>\n";
		}
		
		echo "								</td>\n";
		echo "   						</tr>\n";
	}

	if ($_SESSION['jlev']!=0 && $row['njobid']!=0)
	{
		$destidret  =disp_mas_div_jobid($rowD['mas_div'],$row['njobid']);
		echo "	   					<tr>\n";
		echo "      						<td align=\"right\" class=\"gray\" width=\"75\"><b>Job:</b></td>\n";
		echo "      						<td align=\"left\" class=\"gray\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$destidret[0]."\" DISABLED></td>\n";
		echo "      						<td align=\"center\" class=\"gray\">".$cadate."</td>\n";
		echo "      						<td align=\"center\" class=\"gray\">".$cudate."</td>\n";
		echo "      						<td align=\"left\" class=\"gray\">\n";
		
		if ($rowA['finan_off']==0)
		{
			echo "                        <form name=\"viewjob\" method=\"POST\">\n";
			echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
			echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
			echo "                           <input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
			echo "                           <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "                           <input class=\"checkboxgry\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Job\">\n";
			echo "                        </form>\n";
		}
		
		echo "								</td>\n";
		echo "   						</tr>\n";
	}

	echo "						</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"".$dcellwidth."\" height=\"120\">\n";
	echo "	   			<tr>\n";
	echo "      			<td valign=\"top\" class=\"gray\">\n";
	
	echo "						<table width=\"100%\">\n";

	if ($rowA['finan_off']==1 || $rowA['finan_from']!=0)
	{
		echo "	   					    <tr>\n";
		echo "      						<td colspan=\"2\" class=\"ltgray_und\" align=\"left\"><b>Financing</b></td>\n";
		echo "   						</tr>\n";
        echo "	   					    <tr>\n";
		echo "      						<td class=\"gray\" align=\"right\" width=\"80\"><b>Office:</b></td>\n";
        echo "      						<td class=\"gray\" align=\"left\">".$rowCa['name']."</td>\n";
		echo "   						</tr>\n";

		if ($nrow1 > 0 && strtotime($row1['lupdate']) > strtotime('1/1/1980'))
		{
			echo "	   					<tr>\n";
			echo "      						<td class=\"gray\" align=\"right\" width=\"80\"><b>Source:</b></td>\n";
			
			if ($row['finan_src']==3)
			{
				echo "                     <td class=\"gray\" align=\"left\">Cash</td>\n";
			}
			elseif ($row['finan_src']==2)
			{
				echo "                     <td class=\"gray\" align=\"left\">Customer Finance</td>\n";
			}
			elseif ($row['finan_src']==1)
			{
				echo "                     <td class=\"gray\" align=\"left\">Winners</td>\n";
			}
			else
			{
				echo "                     <td class=\"gray\" align=\"left\"></td>\n";
			}
			
			echo "   						</tr>\n";
			echo "	   					<tr>\n";
			echo "      						<td class=\"gray\" align=\"right\" width=\"80\"><b>Received:</b></td>\n";
			echo "      						<td class=\"gray\">".$fdate."</td>\n";
			echo "   						</tr>\n";
			echo "	   					<tr>\n";
			echo "      						<td class=\"gray\" align=\"right\" width=\"80\"><b>Updated:</b></td>\n";
			echo "      						<td class=\"gray\">".$fudate."</td>\n";
			echo "   						</tr>\n";
			
			if ($row1['amtfinan'] > 0)
			{
				echo "	   					<tr>\n";
				echo "      						<td class=\"gray\" align=\"right\" width=\"90\"><b>Amt Financed:</b></td>\n";
				echo "      						<td class=\"gray\">".number_format($row1['amtfinan'], 2,'.',',')."</td>\n";
				echo "   						</tr>\n";
			}
		}
	}

	echo "						</table>\n";
	
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	
	$jddate = "";
	$jcdate = "";
	$jadate = "";
	$judate = "";
	$jtdate = "";
	$qry2 = "SELECT digdate,closed,added,updated FROM jobs WHERE officeid='".$oid."' AND njobid='".$row['njobid']."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	$qry3 = "SELECT contractdate,id FROM jdetail WHERE officeid='".$oid."' AND njobid='".$row['njobid']."' AND jadd='0';";
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
	
	echo "      <td valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"".$dcellwidth."\" height=\"120\">\n";
	echo "	   			<tr>\n";
	echo "      			<td valign=\"top\" class=\"gray\">\n";
	echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "	   						<tr>\n";
	echo "      						<td colspan=\"3\" class=\"ltgray_und\" align=\"left\"><b>Job Dates</b></td>\n";
	echo "   						</tr>\n";

	if ($row['jobid']!=0)
	{
		if (isset($row3['contractdate']))
		{
			$jtdate = date("m/d/Y", strtotime($row3['contractdate']));
		}

		echo "	   					<tr>\n";
		echo "      						<td class=\"gray\" align=\"right\" width=\"80\"><b>Contract:</b> </td>\n";

		if ($_SESSION['elev'] >=9 && $_SESSION['clev'] >=9 && $_SESSION['jlev'] >=9)
		{
			echo "      						<td align=\"left\" class=\"gray\">\n";
			echo "									<form name=\"setcon\" method=\"post\">\n";
			echo "									<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";
			echo "									<input type=\"hidden\" name=\"call\" value=\"set_condate\">\n";
			echo "									<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
			echo "									<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
			echo "									<input type=\"hidden\" name=\"jtid\" value=\"".$row3['id']."\">\n";
			echo "									<input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
			echo "									<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "      							<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"condate\" value=\"".$jtdate."\">\n";
			echo "								</td>\n";
			
			if ($rowA['finan_off']!=1)
			{
				echo "      						<td align=\"left\" class=\"gray\">\n";
				echo "									<input class=\"checkbox\" type=\"checkbox\" name=\"chkcon\" value=\"1\">\n";
				echo "									<input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Set Date\">\n";
				echo "								</td>\n";
			}
			else
			{
				echo "      						<td align=\"left\" class=\"gray\">\n";
				echo "									<img src=\"images/login.gif\" alt=\"This Function is Locked\">\n";
				echo "								</td>\n";				
			}
			echo "									</form>\n";
		}
		else
		{
			echo "      						<td align=\"left\" class=\"gray\">\n";
			echo "      							<input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$jtdate."\" DISABLED>\n";
			echo "								</td>\n";
			echo "      						<td align=\"left\" class=\"gray\">\n";
			echo "									<img src=\"images/login.gif\" alt=\"This Function is Locked\">\n";
			echo "								</td>\n";
		}
		echo "   						</tr>\n";
	}

	if ($row['njobid']!=0)
	{
		$dis='';
		$digdis=0;
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
				//$dis		="DISABLED";
				$digdis		=1;
			}
		}

		if (isset($row2['closed']))
		{
			$jcdate = date("m/d/Y", strtotime($row2['closed']));
		}

		if ($_SESSION['jlev'] < 5)
		{
			//$dis		="DISABLED";
			$digdis		=1;
		}

		echo "	   					<tr>\n";
		echo "      						<td class=\"gray\" align=\"right\" width=\"80\"><b>Dig:</b> </td>\n";
		echo "      						<td align=\"left\" class=\"gray\">\n";
		echo "									<form name=\"setdig\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "									<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";
		echo "									<input type=\"hidden\" name=\"call\" value=\"set_digdate\">\n";
		echo "									<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
		echo "									<input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
		echo "									<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "									<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
		echo "									<input type=\"hidden\" name=\"cdate\" value=\"".$jtdate."\">\n";
		echo "      							<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"digdate\" value=\"".$jddate."\">\n";
		echo "								</td>\n";
		echo "      						<td align=\"left\" class=\"gray\">\n";
		echo "									<input class=\"checkbox\" type=\"checkbox\" name=\"chkdig\" value=\"1\">\n";
		
		if ($digdis != 1)
		{
			echo "									<input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Set Date\">\n";
		}
		else
		{
			echo "									<img src=\"images/login.gif\" alt=\"This Function is Locked\">\n";
		}
		
		echo "								</td>\n";
		echo "									</form>\n";
		echo "   						</tr>\n";
		echo "	   						<tr>\n";
		echo "      						<td class=\"gray\" align=\"right\" width=\"80\"><b>Complete:</b></td>\n";
		echo "      						<td align=\"left\" class=\"gray\">\n";
		echo "									<form name=\"setcom\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "									<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";
		echo "									<input type=\"hidden\" name=\"call\" value=\"set_clsdate\">\n";
		echo "									<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
		echo "									<input type=\"hidden\" name=\"njobid\" value=\"".$row['njobid']."\">\n";
		echo "									<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "									<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
		echo "									<input type=\"hidden\" name=\"cdate\" value=\"".$jtdate."\">\n";
		echo "      							<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"clsdate\" value=\"".$jcdate."\">\n";
		echo "								</td>\n";
		echo "      						<td align=\"left\" class=\"gray\">\n";
		echo "									<input class=\"checkbox\" type=\"checkbox\" name=\"chkcls\" value=\"1\">\n";
		
		if ($_SESSION['jlev'] >= 5)
		{
			echo "									<input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Set Date\">\n";
		}
		else
		{
			echo "									<img src=\"images/login.gif\" alt=\"Set Date\">\n";
		}
		
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
	
	echo "		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"".$_REQUEST['action']."\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"chistory_add\">\n";
	echo "			<input type=\"hidden\" name=\"tranid\" value=\"".$tranid."\">\n";
	echo "			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";

	if ($_REQUEST['action']=="leads")
	{
		echo "			<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
	}
	elseif ($_REQUEST['action']=="Reports")
	{
		echo "			<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
	}
	elseif ($_REQUEST['action']=="est")
	{
		echo "			<input type=\"hidden\" name=\"estid\" value=\"".$_REQUEST['estid']."\">\n";
	}
	elseif ($_REQUEST['action']=="contract")
	{
		echo "			<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
		echo "			<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	}
	elseif ($_REQUEST['action']=="job")
	{
		echo "			<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		echo "			<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	}
	elseif ($_REQUEST['action']=="mas")
	{
		echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";
		echo "			<input type=\"hidden\" name=\"njobid\" value=\"".$_REQUEST['njobid']."\">\n";
		echo "			<input type=\"hidden\" name=\"jadd\" value=\"".$_REQUEST['jadd']."\">\n";
	}

	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";	
	
	echo "   <tr>\n";
	echo "      <td colspan=\"2\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "	   			<tr>\n";
	echo "      			<td valign=\"top\" class=\"gray\">\n";
	echo "						<table width=\"100%\" cellpadding=\"1\">\n";
	echo " 			  				<tr>\n";
	echo "								<td colspan=\"5\" class=\"gray\" align=\"right\" valign=\"bottom\">\n";
	echo "									<table width=\"100%\">\n";
	echo " 			  							<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Comments:</b></td>\n";
	echo "											<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "												<font color=\"blue\"><b>".$nrow0ct."</b></font>";
	echo "	      									</td>\n";
	echo "											<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Open Requests:</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\" valign=\"bottom\">\n";
	echo "												<font color=\"black\"><b>".$nrow0ro."</b></font>";
	echo "	      									</td>\n";
	echo "											<td class=\"gray\" align=\"right\"><b>Resolved Requests:</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\">\n";
	echo "												<font color=\"green\"><b>".$nrow0rr."</b></font>";
	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Open Complaints:</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\" valign=\"bottom\">\n";
	echo "												<font color=\"red\"><b>".$nrow0co."</b></font>";
	echo "	      									</td>\n";
	echo "											<td class=\"gray\" align=\"right\"><b>Resolved Compaints:</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\">\n";
	echo "												<font color=\"green\"><b>".$nrow0cr."</b></font>";
	echo "											</td>\n";
	echo "	      								</tr>\n";
	echo "									</table>\n";
	echo "   							</td>\n";
	echo "   						</tr>\n";
		
	if ($nrow0!=0)
	{
		echo "   						<tr>\n";
		echo "      						<td align=\"left\" class=\"ltgray_und\" width=\"100\"><b>Date</b></td>\n";
		echo "     	 						<td align=\"left\" class=\"ltgray_und\" width=\"100\"><b>Name</b></td>\n";
		echo "      						<td align=\"left\" class=\"ltgray_und\" width=\"50\"><b>Stage</b></td>\n";
		echo "      						<td align=\"left\" class=\"ltgray_und\" width=\"50\"><b>Ticket</b></td>\n";
		echo "      						<td align=\"left\" class=\"ltgray_und\" width=\"660\"><b>Comments</b></td>\n";
		echo "   						</tr>\n";

		$cfol_ar=array();
		$cres_ar=array();
		$ccls_ar=array();
		
		$sfol_ar=array();
		$sres_ar=array();
		$scls_ar=array();
		while ($row0 = mssql_fetch_array($res0))
		{
			$stage="";
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

			echo "   						<tr>\n";
			echo "   							<td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\">".date("m/d/y h:iA",strtotime($row0['mdate']))."</td>\n";
			echo "								<td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\">".substr($row1['fname'],0,1)." ".$row1['lname']."</td>\n";
			echo "								<td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\">".$stage."</td>\n";
			echo "								<td align=\"left\" class=\"".$cmt_tbg."\">\n";
			
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
			echo "								<td align=\"left\" width=\"400\" class=\"".$cmt_tbg."\">".$row0['mtext']."</td>\n";
			echo "   						</tr>\n";
		}
	}
	
	echo "						</table>\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "   		</table>\n";
	echo "		</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td colspan=\"2\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"".$tcellwidth."\" height=\"150\">\n";
	echo "   			<tr>\n";
	echo "      			<td class=\"gray\" align=\"center\" valign=\"top\">\n";
	echo "						<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "							<tr>\n";
	echo "								<td class=\"ltgray_und\" align=\"left\"><b>Comments/Complaints</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\" class=\"gray\">\n";
	echo "									<table border=\"".$brdr."\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"left\">\n";
	echo "												<select name=\"commentflag\">\n";
	echo "													<option value=\"C:0\">Add Comment</option>\n";
	
	if ($_SESSION['rlev'] >= 6 || $_SESSION['csrep'] >= 6)
	{		
		if (!empty($sfol_ar) && is_array($sfol_ar))
		{
			foreach (array_unique($sfol_ar) as $sfn => $sfv)
			{
				if (!in_array($sfv,$scls_ar))
				{
					echo "<option value=\"SF:".$sfv."\">Service Followup: ".$sfv."</option>\n";
				}
			}
		}
		
		if (!empty($sres_ar) && is_array($sres_ar))
		{
			foreach (array_unique($sres_ar) as $srn => $srv)
			{
				if (!in_array($srv,$scls_ar))
				{
					echo "<option value=\"SR:".$srv."\">Service Resolve: ".$srv."</option>\n";
				}
			}
		}
		
		if (!empty($cfol_ar) && is_array($cfol_ar))
		{
			foreach (array_unique($cfol_ar) as $cfn => $cfv)
			{
				if (!in_array($cfv,$ccls_ar))
				{
					echo "<option value=\"CF:".$cfv."\">Complaint Followup: ".$cfv."</option>\n";
				}
			}
		}
		
		if (!empty($cres_ar) && is_array($cres_ar))
		{
			foreach (array_unique($cres_ar) as $crn => $crv)
			{
				if (!in_array($crv,$ccls_ar))
				{
					echo "<option value=\"CR:".$crv."\">Complaint Resolve: ".$crv."</option>\n";
				}
			}
		}
		
		echo "													<option value=\"S:1\">Add Service</option>\n";
		echo "													<option value=\"C:1\">Add Complaint</option>\n";
	}
	
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "			   				</tr>\n";
	echo "							<tr>\n";
	echo "								<td class=\"gray\" align=\"center\"><textarea name=\"mtext\" rows=\"5\" cols=\"120\"></textarea></td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "			   </tr>\n";
	echo "			   <tr>\n";
	echo "      			<td colspan=\"2\" align=\"right\" class=\"gray\">\n";	
	echo "						<input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Save Comment\">\n";
	echo "					</td>\n";
	echo "			   </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "		</form>\n";
	echo "   </tr>\n";

	if (!empty($row['comments']) && strlen($row['comments']) >= 2)
	{
		echo "   <tr>\n";
		echo "      <td colspan=\"2\" align=\"left\" class=\"gray\">\n";
		echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
		echo "   			<tr>\n";
		echo "      			<td colspan=\"2\" align=\"left\" class=\"gray\"><b>Archived Comments:</b></td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td colspan=\"2\" align=\"left\" class=\"gray\">".$row['comments']."</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "   </tr>\n";
	}

	echo "</table>\n";
	
	//show_session_info();
	/*print_r($fol_ar);
	echo "<br>";
	print_r($res_ar);
	echo "<br>";
	print_r($cls_ar);*/
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

		$aop	="<A TARGET=\"_new\" HREF=";
		$a1p	=rtrim(preg_replace('/ /','+',$a1));
		$c1p	=rtrim(preg_replace('/ /','+',$c1));
		$s1p	=$s1;
		$z1p	=$z1;
		$cyp	="US";
		$cid	="&cid=lfmaplink";
		$acl	=">Mapquest Link</A>";

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

function form_element_ACC_quote($id,$aid,$officeid,$item,$accpbook,$qtype,$seqn,$rp,$bp,$spaitem,$mtype,$atrib1,$atrib2,$atrib3,$quan_calc,$commtype,$crate,$disabled,$r_estdata)
{
	error_reporting(E_ALL);
	$tbg="graywhtbrdr";

	if (isset($mtype) && $mtype!=0)
	{
		$qryB = "SELECT mid,abrv FROM mtypes WHERE mid='".$mtype."'";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);
	
		$fmtype=$rowB['abrv'];
	}
	else
	{
		$fmtype="<img src=\"images/pixel.gif\">";
	}
	
	$qry0  = "select * from [jest]..[acc_price_pad] where oid=".$_SESSION['officeid']." and sid=".$_SESSION['securityid']." and iid=".$id." and active=1;";
	$res0  = mssql_query($qry0);
	$row0  = mssql_fetch_array($res0);
	$nrow0 = mssql_num_rows($res0);
	
	if ($nrow0==1)
	{
		$dbrp	=number_format($rp, 2, '.', '');
		$rp		=number_format(($rp + $row0['adj_price']), 2, '.', ''); // BP from DB + Adjust Pad
		//$frp	="<font color=\"blue\" title=\"SR Adjusted Price\">".$rp."</font>";
		$rpcls	='bboxbluetext';
	}
	else
	{
		$dbrp	=number_format($rp, 2, '.', '');
		$rp		=number_format($rp, 2, '.', ''); // BP from DB
		$rpcls	='bbox';
	}

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

	/*$s0	=$id;
	$s1	="aaaa".$s0;                // Acc ID
	$s2	="bbba".$s0;                // Quantity
	$s3	="ccca".$s0;                // Spaitem (DEPRECATED)
	$s4	="ddda".$s0;                // Price
	$s5	="code".$s0;                // Material Code
	$s6	="eeea".$s0;                // Bid Item
	$s7	="fffa".$s0;                // Question Type Code
	$s8	="ggga".$s0;                // Comm Type Code
	$s9	="hhha".$s0;                // Comm Rate
	$s10="iiia".$s0;                // Quan Calc*/

	if ($disabled==1)
	{
		if ($db_id==$id)
		{
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][id]\" value=\"".$id."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][quan]\" value=\"".$db_qn."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][spaitem]\" value=\"".$spaitem."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][dbrp]\" value=\"".$dbrp."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][rp]\" value=\"".$rp."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][qtype]\" value=\"".$qtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][commtype]\" value=\"".$commtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][crate]\" value=\"".$crate."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][quan_calc]\" value=\"".$quan_calc."\">\n";
		}
	}
	else
	{
		if ($qtype==0)
		{
			// Disabled
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			echo                            $item;
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][id]\" value=\"".$id."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][quan]\" value=\"".$db_qn."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][spaitem]\" value=\"".$spaitem."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][dbrp]\" value=\"".$dbrp."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][qtype]\" value=\"".$qtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][commtype]\" value=\"".$commtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][crate]\" value=\"".$crate."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][quan_calc]\" value=\"".$quan_calc."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][rp]\" value=\"".$rp."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\" NOWRAP>$fmtype</td>\n";			
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"center\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][quan]\" value=\"1\">\n";
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif (
		$qtype==2||
		$qtype==39||
		$qtype==55||
		$qtype==58
		)
		{
			// Quantity - NoCharge (Quantity) - Package (Quantity)
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			
			@showdescrip($item,$atrib1,$atrib2,$atrib3);
			
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][id]\" value=\"".$id."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][spaitem]\" value=\"".$spaitem."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][dbrp]\" value=\"".$dbrp."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][qtype]\" value=\"".$qtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][commtype]\" value=\"".$commtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][crate]\" value=\"".$crate."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][quan_calc]\" value=\"".$quan_calc."\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"".$tbg."\" width=\"60\" valign=\"bottom\" align=\"right\">\n";
			echo "                           <input class=\"".$rpcls."\" type=\"text\" name=\"estis[".$id."][rp]\" value=\"".$rp."\" size=\"6\" maxlength=\"15\">\n";
			echo "						  </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">$fmtype</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"center\">\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxbc\" type=\"text\" name=\"estis[".$id."][quan]\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxbc\" type=\"text\" name=\"estis[".$id."][quan]\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif  (
		$qtype==1||
		$qtype==3||
		$qtype==4||
		$qtype==5||
		$qtype==6||
		$qtype==7||
		$qtype==8||
		$qtype==9||
		$qtype==10||
		$qtype==11||
		$qtype==12||
		$qtype==13||
		$qtype==14||
		$qtype==15||
		$qtype==16||
		$qtype==17||
		$qtype==34||
		$qtype==35||
		$qtype==36||
		$qtype==37||
		$qtype==38||
		$qtype==41||
		$qtype==42||
		$qtype==43||
		$qtype==45||
		$qtype==46||
		$qtype==47||
		$qtype==69||
		$qtype==70||
		$qtype==72||
		$qtype==77
		)
		{
			// PFT - SQFT - Fixed - Depth - Checkbox - Base+ (All) - Bracket (All)
			// Deck - NoCharge (PFT,SQFT,IA,Gals,Fixed and Base+ Variants)
			// IA (Div by CalcAmt) - IA (Mult by CalcAmt) - Package (Checkbox)
			
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
	
			@showdescrip($item,$atrib1,$atrib2,$atrib3);
			
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][id]\" value=\"".$id."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][spaitem]\" value=\"".$spaitem."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][dbrp]\" value=\"".$dbrp."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][qtype]\" value=\"".$qtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][commtype]\" value=\"".$commtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][crate]\" value=\"".$crate."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][quan_calc]\" value=\"".$quan_calc."\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
			echo "                        <td class=\"".$tbg."\" width=\"60\" valign=\"bottom\" align=\"right\">\n";
			echo "                           <input class=\"".$rpcls."\" type=\"text\" name=\"estis[".$id."][rp]\" value=\"".$rp."\" size=\"6\" maxlength=\"15\">\n";
			echo "						  </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">$fmtype</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"center\">\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"estis[".$id."][quan]\" value=\"1\" CHECKED>\n";
			}
			else
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"estis[".$id."][quan]\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif  ($qtype==33)
		{
			// Bid Items
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			echo "							<table width=\"100%\">\n";
			echo "								<tr>\n";
			echo "									<td>\n";
			@showdescrip($item,$atrib1,$atrib2,$atrib3);
			echo "									</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>\n";
			echo "                           			<textarea name=\"estis[".$id."][bidid]\" rows=\"2\" cols=\"35\">";
	
			if ($db_id==$id)
			{
				if (isset($_REQUEST['jobid']) && isset($_REQUEST['jadd']))
				{
					$qryC = "SELECT jobid,bidinfo,dbid FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."' AND dbid='".$id."';";
				}
				elseif (isset($_REQUEST['njobid']) && isset($_REQUEST['jadd']))
				{
					$qryC = "SELECT jobid,bidinfo,dbid FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' AND dbid='".$id."';";
				}
				else
				{
					$qryC = "SELECT estid,bidinfo,bidaccid FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$id."';";
				}
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_array($resC);
				echo str_replace("\\", "", $rowC[1]);
			}
	
			echo "										</textarea>\n";
			echo "									</td>\n";
			echo "								</tr>\n";
			echo "							</table>\n";			
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][id]\" value=\"".$id."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][spaitem]\" value=\"".$spaitem."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][dbrp]\" value=\"".$db_rp."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][qtype]\" value=\"".$qtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][commtype]\" value=\"".$commtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][crate]\" value=\"".$crate."\">\n";
			echo "                           <input type=\"hidden\" name=\"estis[".$id."][quan_calc]\" value=\"".$quan_calc."\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	
			if ($db_id==$id)
			{
				echo "                             <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"estis[".$id."][rp]\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\" NOWRAP>n/a</td>\n";
				echo "                             <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"center\" NOWRAP>\n";
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"estis[".$id."][quan]\" value=\"1\" CHECKED>\n";
				echo "                        		</td>\n";
			}
			else
			{
				echo "                             <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"estis[".$id."][rp]\" size=\"6\" maxlength=\"20\" value=\"".$rp."\"></td>\n";
				echo "                             <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\" NOWRAP>$fmtype</td>\n";
				echo "                             <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"center\" NOWRAP>\n";
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"estis[".$id."][quan]\" value=\"1\">\n";
				echo "                        		</td>\n";
			}
	
			echo "                     </tr>\n";
		}
		elseif  ($qtype==54)
		{
			// Referral
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">";
			@showdescrip($item,$atrib1,$atrib2,$atrib3);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"".$rp."\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">$fmtype</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"center\">\n";
	
			if ($db_id==$id)
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

function form_element_ACC($id,$trig,$r_estdata,$type)
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	
	$tbg="gray";
	$til="";
	
	/*
	if (!empty($_REQUEST['njobid']) && $_REQUEST['njobid']!=0)
	{
		$masjinfo=getmasjobinfo($_REQUEST['njobid']);
		
		if ($masjinfo[1] >= 5)
		{
			$qryA = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$id."' ORDER BY seqn ASC";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			if ($rowA[17]==1)
			{
				$tbg="red";
				$til="This item is disabled. Included for backward compatibility. DO NOT CHANGE.";
			}
		}
		else
		{
			$qryA = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$id."' ORDER BY seqn ASC";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
		}
	}
	else
	{
		$qryA = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$id."' AND disabled!=1 ORDER BY seqn ASC";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_row($resA);
	}
	*/
	
	$qryA = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$id."' ORDER BY seqn ASC";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);
	
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
	$s10	="iiia".$s0;                // Quan Calc
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
			// Disabled
			//echo "                     <tr>\n";
			//echo "                        <td colspan=\"5\">\n";
			//echo "                     <table class=\"inner_borders\" width=\"100%\" border=0>\n";
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">";
			echo                            $rowA[3];
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"right\">\n";
			echo "                        </td>\n";
			echo "                             <td valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                        </td>\n";
			echo "                             <td class=\"$tbg\" width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo                            $rowB[1];
			echo "                        </td>\n";
			echo "                             <td class=\"$tbg\" width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
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
			//echo "                     <tr>\n";
			//echo "                        <td colspan=\"5\">\n";
			//echo "                     <table class=\"inner_borders\" width=\"100%\" border=0>\n";
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" width=\"475px\" valign=\"bottom\" align=\"left\">";
			@showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75px\" valign=\"bottom\" align=\"right\"></td>\n";
			echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\">$bp</td>\n";
			echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50px\" valign=\"bottom\" align=\"right\">\n";
	
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
		elseif ($rowA[5]==32)
		{
			// Sub Header (Display Only)
			if ($trig!=1)
			{
				echo "                </table>\n";
				//echo "                        </td>\n";
				//echo "                     </tr>\n";
				echo "        </span>\n";
			}
			echo "        <div onclick=\"SwitchMenu('sub".$rowA[0]."')\">";
	
			echo "<font color=\"blue\"><b>".$rowA[3]."</b></font>";
			//showdescrip_subhdr($rowA[3]);
	
			echo "</div>\n";
			echo "        <span class=\"submenu\" id=\"sub$rowA[0]\">\n";
			echo "                <table class=\"outer\" border=1 width=\"100%\">\n";
			echo "              <tr>\n";
			echo "                 <td class=\"$tbg\" valign=\"bottom\" align=\"left\" colspan=\"5\">\n";
	
			@showdescrip_hdratribs($rowA[11],$rowA[12],$rowA[13]);
	
			echo "               <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "               <input type=\"hidden\" name=\"$s2\" value=\"1\">\n";
			echo "               <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "               <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "               <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "               <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "               <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "               <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                                </td>\n";
			echo "                        </tr>\n";
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
			echo "                        <td colspan=\"5\">\n";
			echo "                     <table class=\"inner_borders\" width=\"100%\" border=0>\n";
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" width=\"475px\" valign=\"bottom\" align=\"left\">\n";
	
			@showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
	
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75px\" valign=\"bottom\" align=\"right\"></td>\n";
			echo "                             <td class=\"$tbg\" width=\"60px\" valign=\"bottom\" align=\"right\" NOWRAP>$bp</td>\n";
			echo "                             <td class=\"$tbg\" width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>$rowB[1]</td>\n";
			echo "                             <td class=\"$tbg\" width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
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
			echo "                     </table>\n";
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
			@showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
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
			echo "								<td width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo                            $rowB[1];
			echo "                        </td>\n";
			echo "								<td width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
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
			@showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
	
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
			echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
			if ($_SESSION['call']=='view_addnew' || $_SESSION['call']=='create_add' || $_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo                            $rowB[1];
			}
	
			echo "                        </td>\n";
			echo "                        <td width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
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
		elseif ($rowA[5]==23)
		{
			// Code (Checkbox)
			echo "                     <tr>\n";
			echo "                        <td width=\"475\" valign=\"bottom\" align=\"left\">\n";
			@showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
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
			echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo                            $rowB[1];
			echo "                        </td>\n";
			echo "                             <td width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
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
		elseif (
		$rowA[5]==24||
		$rowA[5]==25||
		$rowA[5]==27||
		$rowA[5]==28||
		$rowA[5]==29
		)
		{
			// Multiple Choice (PFT - SQFT - IA - Gallons - Checkbox)
			$qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM [".$MAS."accpbook] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND accid='".$accid."' ORDER BY accid";
			$resC = mssql_query($qryC);
	
			echo "                     <tr>\n";
			echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">\n";
			echo "                           <select name=\"$s1\">\n";
	
			while($rowC = mssql_fetch_row($resC))
			{
				echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
			}
	
			echo "                           </select>\n";
			echo "                        </td>\n";
			echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\">\n";
			echo "                        </td>\n";
			echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			//echo "                           <input class=\"bbox\" type=\"text\" name=\"$s4\" value=\"$bp\" size=\"6\" maxlength=\"8\">\n";
			echo "                        </td>\n";
			echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo                            $rowB[1];
			echo "                        </td>\n";
			echo "                        <td width=\"50px\" valign=\"bottom\" align=\"left\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif ($rowA[5]==26)
		{
			// Multiple Choice (Quantity)
			$qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid AND accid=$accid ORDER BY accid";
			$resC = mssql_query($qryC);
	
			echo "                     <tr>\n";
			echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">\n";
			echo "                           <select name=\"$s1\">\n";
	
			while($rowC = mssql_fetch_row($resC))
			{
				echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
			}
	
			echo "                           </select>\n";
			echo "                        </td>\n";
			echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\">\n";
			echo "                        </td>\n";
			echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo "                        </td>\n";
			echo "                             <td width=\"20px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo                            $rowB[1];
			echo "                        </td>\n";
			echo "                        <td width=\"50px\" valign=\"bottom\" align=\"left\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s2\" size=\"4\" maxlength=\"5\" value=\"0\"> $rowA[4]\n";
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif  ($rowA[5]==33)
		{
			// Bid Items
			//echo "                     <tr>\n";
			//echo "                        <td colspan=\"5\">\n";
			//echo "                     <table class=\"inner_borders\" width=\"100%\" border=0>\n";
			echo "                     <tr>\n";
			echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">\n";
			@showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
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
				//echo "TEST";
				//echo $qryC;
				echo str_replace("\\", "", $rowC[1]);
			}
	
			echo "</textarea>\n";
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$rowA[5]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$rowA[15]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$rowA[16]\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$rowA[14]\">\n";
			//echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$bp\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"75px\" valign=\"bottom\" align=\"right\"></td>\n";
	
			if ($_SESSION['call']=='view_addnew' && $db_id==$id)
			{
				echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>$rowB[1]</td>\n";
				echo "                             <td width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add' && $db_id==$id)
			{
				echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>$rowB[1]</td>\n";
				echo "                             <td width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			elseif ($_SESSION['call']=='create_add_post_mas' && $db_id==$id)
			{
				echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>$rowB[1]</td>\n";
				echo "                             <td width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			else
			{
				echo "                             <td width=\"60px\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$rowA[7]\"></td>\n";
				echo "                             <td width=\"25px\" valign=\"bottom\" align=\"right\" NOWRAP>$rowB[1]</td>\n";
				echo "                             <td width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif  ($rowA[5]==54)
		{
			// Referral
			//echo "                     <tr>\n";
			//echo "                        <td colspan=\"5\">\n";
			//echo "                     <table class=\"inner_borders\" width=\"100%\" border=0>\n";
			echo "                     <tr>\n";
			echo "                        <td width=\"475px\" valign=\"bottom\" align=\"left\">";
			@showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
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
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		else
		{
			echo "<!---                     <tr>\n";
			echo "                        <td colspan=\"2\" valign=\"bottom\" align=\"left\">** CODE NOT INCLUDED **</td>\n";
			echo "                     </tr> --->\n";
		}
	}
	// Used to close Span element on last ACC before new category header
	if ($trig==2)
	{
		echo "</table>\n";
		//echo "                        </td>\n";
		//echo "                     </tr>\n";
		echo "</span>\n";
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
	echo "                  <table width=\"100%\" height=\"150\" border=0 class=\"outer\">\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"top\">\n";
	echo "                  <table width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\" NOWRAP></td>\n";
	echo "                        <td class=\"gray\" colspan=\"3\" valign=\"bottom\" align=\"left\" NOWRAP>\n";
		
	//echo "<b>Renovation</b>";
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
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP>\n";

	if ($bpset[6]=="pft")
	{
		echo "									<b>Per</b>\n";
	}
	else
	{
		echo "									<b>SA</b>\n";
	}

	echo "								</td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";

	if ($bpset[7] > 0)
	{
		if ($bpset[6]=="pft")
		{
			echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"1\" maxlength=\"5\" value=\"".$viewarray['ps1']."\">\n";
		}
		else
		{
			echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"1\" maxlength=\"5\" value=\"".$viewarray['ps2']."\">\n";
		}
	}
	else
	{
		if ($bpset[6]=="pft")
		{
			echo "                           <select name=\"ps1\" onChange=\"this.form.submit();\">\n";
		}
		else
		{
			echo "                           <select name=\"ps2\" onChange=\"this.form.submit();\">\n";
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
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP>\n";

	if ($bpset[6]=="pft")
	{
		echo "									<b>SA</b>\n";
	}
	else
	{
		echo "									<b>Per</b>\n";
	}

	echo "								</td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";

	if ($bpset[6]=="pft")
	{
		echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"1\" maxlength=\"5\" value=\"".$viewarray['ps2']."\">\n";
	}
	else
	{
		echo "                           <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"1\" maxlength=\"5\" value=\"".$viewarray['ps1']."\">\n";
	}

	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"right\" NOWRAP><b>Depths</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">\n";
	echo "							 <table cellpadding=0 cellspacing=0 width=\"100%\">\n";
	echo "							 	<tr>\n";
	echo "							 		<td>\n";
	echo "                           			<input class=\"bboxbc\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps5']."\" title=\"Shallow\">\n";
	echo "							 		</td>\n";
	echo "							 		<td>\n";
	echo "                           			<input class=\"bboxbc\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps6']."\" title=\"Middle\">\n";
	echo "							 		</td>\n";
	echo "							 		<td>\n";
	echo "                           			<input class=\"bboxbc\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps7']."\" title=\"Deep\">\n";
	echo "							 		</td>\n";
	echo "							 	<tr>\n";
	echo "                        	</table>\n";
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" align=\"right\" NOWRAP><b>IA</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$set_ia."</td>\n";
	//echo "                           <input type=\"hidden\" size=\"5\" maxlength=\"5\" value=\"".$set_ia."\">\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                     	<td class=\"gray\" align=\"right\" NOWRAP><b>Total Deck</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"deck\" size=\"1\" maxlength=\"4\" value=\"".$viewarray['deck']."\"> \n";

	if ($rowH['deckinc']==1)
	{
		if ($bpset[5] > 0)
		{
			echo " (<b>$incdeck</b> sqft Deck Incl.)";
		}
	}

	echo "                        </td>\n";
	echo "                        <td class=\"gray\" align=\"right\" NOWRAP><b>Gallons</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$set_gals."</td>\n";
	//echo "                           <input class=\"bboxbc\" type=\"text\" size=\"1\" maxlength=\"5\" value=\"".$set_gals."\">\n";
	//echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"right\" NOWRAP><b>Elect Run</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"erun\" size=\"1\" maxlength=\"5\" value=\"".$viewarray['erun']."\">\n";
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" align=\"right\" NOWRAP><b>Plumb Run</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"prun\" size=\"1\" maxlength=\"5\" value=\"".$viewarray['prun']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"right\" NOWRAP><b>Spa Per</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"spa2\" size=\"1\" maxlength=\"5\" value=\"".$viewarray['spa2']."\">\n";
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" align=\"right\" NOWRAP><b>Spa SA</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">\n";
	echo "                           <input class=\"bboxbc\" type=\"text\" name=\"spa3\" size=\"1\" maxlength=\"5\" value=\"".$viewarray['spa3']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"right\" NOWRAP><b>Referral</b></td>\n";
	echo "                        <td colspan=\"3\" class=\"gray\" align=\"left\">\n";
	echo "                           <input type=\"text\" name=\"refto\" size=\"40\" value=\"".$viewarray['refto']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "        </table>\n";
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
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Perimeter:</b></td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['ps1']."</td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Surface Area:</b></td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['ps2']."";
		echo "								</td>\n";
		echo "                     </tr>\n";
	}
	else
	{
		echo "                     <tr>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Surface Area:</b></td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['ps2']."</td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Perimeter:</b></td>\n";
		echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['ps1']."";
		echo "                        </td>\n";
		echo "                     </tr>\n";
	}

	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Depths:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\" NOWRAP>".$viewarray['ps5']." x ".$viewarray['ps6']." x ".$viewarray['ps7']."</td>";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Internal Area:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$set_ia."</td>";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                     	<td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Total Deck:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['deck']."</td>";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Gallons:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$set_gals."</td>";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Electrical Run:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['erun']."</td>";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Plumbing Run:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['prun']."</td>";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Spa Perimeter:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['spa1']."</td>";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Spa Surface Area:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['spa2']."</td>";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP><b>Referral:</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">".$viewarray['refto']."</td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" NOWRAP></td>\n";
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
	
	//echo $qryIa.'<br>';
	
	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid,officeid,added,(select label_masoff_code from offices where officeid=C.officeid) as olabel FROM cinfo AS C WHERE C.officeid='".$_SESSION['officeid']."' AND C.cid='".$rowIa['ccid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
	$resK = mssql_query($qryK);

	echo "                  <table width=\"100%\" height=\"150\" class=\"outer\" border=0>\n";
	echo "	   					<tr>\n";
	echo "      					<td class=\"gray\" align=\"right\" valign=\"right\">\n";
	echo "                  <table width=\"100%\" border=0>\n";
	echo "	   					<tr>\n";
	echo "      					<td class=\"gray\" align=\"right\"><b>Contact Id</b></td>\n";
	echo "      					<td class=\"gray\" align=\"left\"><font class=\"theaderblue\">\n";
	
	disp_cust_id($rowI['cid'],$rowI['olabel'],$rowI['added']);
	
	echo "							</font></td>\n";
	echo "   					</tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" width=\"80\"><b>Name</b> </td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           ".str_replace('\\','',$rowI['clname']).",\n";
	echo "                           ".str_replace('\\','',$rowI['cfname'])."\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Site Addr</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           ".$rowI['saddr1']."\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>City</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           ".$rowI['scity']."\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>State</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           ".$rowI['sstate']."\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Zip</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           ".$rowI['szip1']."\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Phone</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           ".$rowI['chome']." (hm)\n";
	echo "                           ".$rowI['ccell']." (cl)\n";
	echo "                        </td>\n";
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
		echo "                           ".$rowI['scounty']."\n";
	}

	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "                  </td>\n";
	echo "                </tr>\n";
	echo "             </table>\n";
}

function cinfo_displayOLD($cid,$settax)
{
	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid,officeid,added FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
	$resK = mssql_query($qryK);

	echo "                  <table width=\"100%\" height=\"150\" class=\"outer\" border=0>\n";
	echo "	   					<tr>\n";
	echo "      					<td class=\"gray\" align=\"right\"><b>Customer ID</b></td>\n";
	echo "      					<td class=\"gray\" align=\"left\"><font class=\"theaderblue\">\n";
	
	disp_cust_id($rowI[10],$rowI[11],$rowI[12]);
	
	echo "							</font></td>\n";
	echo "   					</tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\" width=\"80\"><b>Name</b> </td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"clname\" size=\"29\" maxlength=\"42\" value=\"".str_replace('\\','',$rowI[2])."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"cfname\" size=\"15\" maxlength=\"20\" value=\"".str_replace('\\','',$rowI[1])."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Site Addr</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"saddr1\" size=\"50\" maxlength=\"42\" value=\"".$rowI[5]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>City</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"scity\" size=\"20\" maxlength=\"42\" value=\"".$rowI[6]."\">\n";
	echo "                            <b>State</b> <input class=\"bboxl\" type=\"text\" name=\"sstate\" size=\"5\" maxlength=\"42\" value=\"".$rowI[7]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Zip</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"szip1\" size=\"10\" maxlength=\"42\" value=\"".$rowI[8]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Phone</b></td>\n";
	echo "                        <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"chome\" size=\"15\" maxlength=\"42\" value=\"".$rowI[3]."\"> hm\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ccell\" size=\"15\" maxlength=\"42\" value=\"".$rowI[9]."\"> cl\n";
	echo "                        </td>\n";
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
		echo "                           <input class=\"bboxl\" type=\"text\" name=\"scounty\" size=\"25\" maxlength=\"30\" value=\"".$rowI[4]."\">\n";
	}

	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function info_display_job($tbg,$offid,$jobid,$jadd,$sfname,$slname,$mfname,$mlname,$ver,$typ,$secid,$njobid)
{
	$brdr=0;
	
	error_reporting(E_ALL);
	
	$qry0 = "SELECT securityid,fname,lname FROM security WHERE officeid='".$_SESSION['officeid']."' and SUBSTRING(slevel,13,13) >='1' ORDER BY lname ASC;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	//$row0 = mssql_fetch_array($res0);
	
	if ($_SESSION['securityid'] == 332 && $typ == "Job" || $_SESSION['securityid'] == 26 && $typ == "Job")
	{
		$qry1 = "SELECT renov,digsec FROM jobs WHERE officeid='".$_SESSION['officeid']."' and njobid='".$njobid."';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
	}
	
	//print_r($row0);
	
	//echo $qry1."<br>";
	echo "			<table class=\"outer\" width=\"100%\" height=\"30\" border=".$brdr.">\n";
	echo "				<tr>\n";
	echo "					<td class=\"".$tbg."\" align=\"right\" width=\"175\" NOWRAP><b>".$typ." ".$ver." Breakdown: </b></td>\n";
	echo "					<td class=\"".$tbg."\" align=\"left\">".$offid."</td>\n";
	echo "					<td class=\"".$tbg."\" align=\"right\"></td>\n";
	echo "					<td class=\"".$tbg."\" align=\"left\"></td>\n";
	echo "					<td class=\"".$tbg."\" align=\"right\"><b>SalesRep:</b></td>\n";
	
	if ($_SESSION['securityid'] == 332 && $typ == "Job" || $_SESSION['securityid'] == 26 && $typ == "Job")
	{
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"updtsalesrep\">\n";
		echo "<input type=\"hidden\" name=\"njobid\" value=\"".$njobid."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "					<td class=\"".$tbg."\" align=\"left\">\n";
		echo "						<select name=\"secid\">\n";
	
		while ($row0 = mssql_fetch_array($res0))
		{
			if ($row0['securityid'] == $secid)
			{
				echo "							<option value=\"".$row0['securityid']."\" SELECTED>".$row0['lname'].", ".$row0['fname']."</option>\n";
			}
			else
			{
				echo "							<option value=\"".$row0['securityid']."\">".$row0['lname'].", ".$row0['fname']."</option>\n";
			}
		}
		
		echo "					</select>\n";
		echo "					</td>";
		echo "					<td class=\"".$tbg."\" align=\"left\">";
		echo "                  <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Change\">\n";
		echo "					</td>";
		echo "</form>\n"; 
	}
	else
	{
		echo "					<td class=\"".$tbg."\" align=\"left\">".$sfname." ".$slname."</td>\n";
		echo "					<td class=\"".$tbg."\" align=\"right\"></td>\n";
	}
	
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"".$tbg."\" align=\"right\" width=\"175\"><b>".$typ." #: </b></td>";
	echo "					<td class=\"".$tbg."\" align=\"left\">".$jobid."\n";

	if ($ver=="Retail")
	{
		if ($jadd > 0)
		{
			echo "Adden #".$jadd."\n";
		}
	}

	echo "					</td>";
	echo "					<td class=\"".$tbg."\" align=\"right\"></td>\n";
	echo "					<td class=\"".$tbg."\" align=\"left\"></td>\n";
	echo "					<td class=\"".$tbg."\" align=\"right\"><b>Sales Manager:</b></td>\n";
	echo "					<td class=\"".$tbg."\" align=\"left\">".$mfname." ".$mlname."</td>\n";
	
	if ($_SESSION['securityid'] == 332 && $typ == "Job" || $_SESSION['securityid'] == 26 && $typ == "Job")
	{
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";	
		echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"sp_set_renov\">\n";
		echo "<input type=\"hidden\" name=\"njobid\" value=\"".$njobid."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "					<td class=\"".$tbg."\" align=\"left\">\n";
		
		if ($row1['renov']==1)
		{
			echo "<input type=\"hidden\" name=\"setrenov\" value=\"0\">";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"setrenov\" value=\"1\">";
		}
		
		echo "					<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Renov\">\n";
		echo "					</td>\n";
		echo "</form>\n"; 
	}
	else
	{
		echo "					<td class=\"".$tbg."\" align=\"right\"></td>\n";
	}
	echo "				</tr>\n";
	echo "			</table>\n";
}

function cinfo_display_chistory($oid,$cid,$settax)
{
	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,jobid,securityid,officeid,cid,added FROM cinfo WHERE cid='".$cid."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);
	
	$qryIa = "SELECT officeid,name FROM offices WHERE officeid='".$rowI[12]."';";
	$resIa = mssql_query($qryIa);
	$rowIa = mssql_fetch_array($resIa);
	
	$qryIb = "SELECT securityid,lname,fname FROM security WHERE securityid='".$rowI[11]."';";
	$resIb = mssql_query($qryIb);
	$rowIb = mssql_fetch_array($resIb);
	
	$qryIc = "SELECT contractamt FROM jdetail WHERE officeid='".$rowI[12]."' AND jobid='".$rowI[10]."';";
	$resIc = mssql_query($qryIc);
	$rowIc = mssql_fetch_array($resIc);

	$wi1=70;
	$wi2=70;
	//echo $qryI."<br>";

	echo "                  <table width=\"100%\" height=\"170\" class=\"outer\" border=0>\n";
	echo "	   					<tr>\n";
	echo "      						<td class=\"gray\" align=\"left\" valign=\"top\" width=\"50%\">\n";
	echo "                  <table width=\"100%\" border=0>\n";
	echo "	   					<tr>\n";
	echo "      						<td colspan=\"2\" class=\"ltgray_und\" align=\"left\"><b>Contact Info</b></td>\n";
	echo "   					</tr>\n";
	echo "	   					<tr>\n";
	echo "      					<td class=\"gray\" align=\"right\"><b>Customer ID:</b></td>\n";
	echo "      					<td class=\"gray\" align=\"left\"><font class=\"theaderblue\">\n";
	
	disp_cust_id($rowI[13],$rowI[12],$rowI[14]);
	
	echo "							</font></td>\n";
	echo "   					</tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Name:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".str_replace('\\','',$rowI[2]).", ".str_replace('\\','',$rowI[1])."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Site Addr:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".substr($rowI[5], 0, 24)."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>City:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[6]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>State:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[7]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Zip:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[8]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" valign=\"top\" align=\"right\"><b>Home Ph:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[3]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" valign=\"top\" align=\"right\"><b>Cell Ph:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[9]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>County:</b></td>\n";
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
	echo "                        </td>\n";
	echo "      						<td class=\"gray\" align=\"left\" valign=\"top\" width=\"50%\">\n";
	echo "                  			<table width=\"100%\" border=0>\n";
	echo "	   								<tr>\n";
	echo "      								<td colspan=\"2\" class=\"ltgray_und\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "   								</tr>\n";
	echo "                     			<tr>\n";
	echo "                     			   <td class=\"gray\" width=\"".$wi2."\" align=\"right\"><b>Office:</b></td>\n";
	echo "                     			   <td class=\"gray\" align=\"left\">".$rowIa['name']."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                     			   <td class=\"gray\" width=\"".$wi2."\" align=\"right\"><b>Sales Rep:</b></td>\n";
	echo "                     			   <td class=\"gray\" align=\"left\">".$rowIb['lname'].", ".$rowIb['fname']."</td>\n";
	echo "                     			</tr>\n";
	
	if ($rowI[10]!="0")
	{
		echo "                     			<tr>\n";
		echo "                        			<td class=\"gray\" width=\"".$wi2."\" align=\"right\" NOWRAP><b>Contract Amt:</b></td>\n";
		echo "                        			<td class=\"gray\" align=\"left\">\n";
		echo number_format($rowIc['contractamt']);
		echo "											</td>\n";
		echo "   									</tr>\n";
	}
	
	echo "									</table>\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function cinfo_display_new($oid,$cid,$settax)
{
	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,jobid,securityid,officeid,cid,added FROM cinfo WHERE cid='".$cid."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);
	
	$qryIa = "SELECT officeid,name FROM offices WHERE officeid='".$rowI[12]."';";
	$resIa = mssql_query($qryIa);
	$rowIa = mssql_fetch_array($resIa);
	
	$qryIb = "SELECT securityid,lname,fname FROM security WHERE securityid='".$rowI[11]."';";
	$resIb = mssql_query($qryIb);
	$rowIb = mssql_fetch_array($resIb);
	
	$qryIc = "SELECT contractamt FROM jdetail WHERE officeid='".$rowI[12]."' AND jobid='".$rowI[10]."';";
	$resIc = mssql_query($qryIc);
	$rowIc = mssql_fetch_array($resIc);

	$wi1=70;
	$wi2=70;

	echo "                  <table class=\"outer\" width=\"300\" height=\"170\">\n";
	echo "	   					<tr>\n";
	echo "      					<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "                  			<table width=\"100%\" border=0>\n";
	echo "	   								<tr>\n";
	echo "      								<td colspan=\"2\" class=\"ltgray_und\" align=\"left\"><b>Contact Info</b></td>\n";
	echo "   								</tr>\n";
	echo "	   								<tr>\n";
	echo "      								<td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Cust ID:</b></td>\n";
	echo "      								<td class=\"gray\" align=\"left\"><font class=\"theaderblue\">\n";
	
	disp_cust_id($rowI[13],$rowI[12],$rowI[14]);
	
	echo "										</font></td>\n";
	echo "   								</tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Name:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".str_replace('\\','',$rowI[2]).", ".str_replace('\\','',$rowI[1])."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Site Addr:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".substr($rowI[5], 0, 24)."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>City:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[6]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>State:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[7]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>Zip:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[8]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" valign=\"top\" align=\"right\"><b>Home Ph:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[3]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" valign=\"top\" align=\"right\"><b>Cell Ph:</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">".$rowI[9]."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" width=\"".$wi1."\" align=\"right\"><b>County:</b></td>\n";
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
	echo "                        </td>\n";
	/*
	echo "      						<td class=\"gray\" align=\"left\" valign=\"top\" width=\"50%\">\n";
	echo "                  			<table width=\"100%\" border=0>\n";
	echo "	   								<tr>\n";
	echo "      									<td colspan=\"2\" class=\"ltgray_und\" align=\"left\"></td>\n";
	echo "	   								</tr>\n";
	echo "                     			<tr>\n";
	echo "                     			   <td class=\"gray\" width=\"".$wi2."\" align=\"right\"><b>Office:</b></td>\n";
	echo "                     			   <td class=\"gray\" align=\"left\">".$rowIa['name']."</td>\n";
	echo "                     			</tr>\n";
	echo "                     			<tr>\n";
	echo "                     			   <td class=\"gray\" width=\"".$wi2."\" align=\"right\"><b>Sales Rep:</b></td>\n";
	echo "                     			   <td class=\"gray\" align=\"left\">".$rowIb['lname'].", ".$rowIb['fname']."</td>\n";
	echo "                     			</tr>\n";
	
	if ($rowI[10]!="0")
	{
		echo "                     			<tr>\n";
		echo "                        			<td class=\"gray\" width=\"".$wi2."\" align=\"right\" NOWRAP><b>Contract Amt:</b></td>\n";
		echo "                        			<td class=\"gray\" align=\"left\">\n";
		echo number_format($rowIc['contractamt']);
		echo "											</td>\n";
		echo "   									</tr>\n";
	}
	
	echo "									</table>\n";
	echo "                        </td>\n";
	*/
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function cinfo_display_job($oid,$cid,$settax)
{
	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid,officeid,added FROM cinfo WHERE officeid='".$oid."' AND cid='".$cid."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$wi=70;
	//echo $qryI."<br>";

	echo "                  <table width=\"100%\" height=\"140\" class=\"outer\" border=0>\n";
	echo "	   					<tr>\n";
	echo "      					<td class=\"gray\" align=\"right\"><b>Customer ID</b></td>\n";
	echo "      					<td class=\"gray\" align=\"left\"><font class=\"theaderblue\">\n";
	
	disp_cust_id($rowI[10],$rowI[11],$rowI[12]);
	
	echo "							</font></td>\n";
	echo "   					</tr>\n";
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
	$qry = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	//echo $qry."<BR>";

	if ($_SESSION['action']=="contract")
	{
		$qryA = "SELECT added,submitted,updated,digdate,closed FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."';";
	}
	else
	{
		$qryA = "SELECT added,submitted,updated,digdate,closed FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."';";
	}

	//echo $qryA."<BR>";

	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	if ($_SESSION['action']=="contract")
	{
		$qryB = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$row['jobid']."' AND jadd='0';";
	}
	else
	{
		$qryB = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd='0';";
	}
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

	$wd1="100";
	echo "                  <table width=\"100%\" height=\"140\" class=\"outer\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"center\" valign=\"top\">\n";
	echo " 			                  <table width=\"100%\" border=0>\n";
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
	echo "										<tr>\n";
	echo "                  					<td class=\"gray\" align=\"right\" width=\"".$wd1."\"><b>Status: </b></td>\n";
	echo "                        			<td class=\"gray\" align=\"left\">".$sta."</td>\n";
	echo "                     			</tr>\n";
	echo "                 				</table>\n";
	echo "								</td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function showdescrip($i,$a1,$a2,$a3,$id)
{
	echo "                        <table align=\"left\" width=\"100%\" border=0>\n";
	
	if (strlen($i) > 1)
	{
		echo "                           <tr>\n";
		echo "                              <td colspan=\"2\" align=\"left\">\n";
		
		/*if (isset($id) && $id!=0)
		{
			echo "									<a href=\"#PBi_".$id."\">".trim($i)."</a>\n";
		}
		else
		{
			echo "									".trim($i)."\n";
		}*/
		
		echo "									".trim($i)."\n";
		echo "								</td>\n";
		echo "                           </tr>\n";
	}

	if (strlen($a1) > 1)
	{
		echo "                           <tr>\n";
        echo "                              <td class=\"transbackfill\" align=\"right\" width=\"20\"></td>\n";
        echo "                              <td align=\"left\">".trim($a1)."</td>\n";
        echo "                           </tr>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "                           <tr>\n";
        echo "                              <td class=\"transbackfill\" align=\"right\" width=\"20\"></td>\n";
        echo "                              <td align=\"left\">".trim($a2)."</td>\n";
        echo "                           </tr>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "                           <tr>\n";
        echo "                              <td class=\"transbackfill\" align=\"right\" width=\"20\"></td>\n";
        echo "                              <td align=\"left\">".trim($a3)."</td>\n";
        echo "                           </tr>\n";
	}
	
	echo "                        </table>\n";
}

function showdescrip_hdr($i,$a1,$a2,$a3)
{
	if (strlen($i) > 1)
	{
		echo "                                                <font color=\"blue\"><b>$i</b></font><br>\n";
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
		echo "<img src=\"plus.gif\" style=\"border:white\" alt=\"Click to Expand\"><font color=\"blue\"><b>$i</b></font>";
	}
}

function showdescrip_hdratribs($a1,$a2,$a3)
{
	if (strlen($a1) > 1)
	{
		echo "                                                - <font class=\"7pt\">$a1</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "                                                <br>- <font class=\"7pt\">$a2</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "                                                <br>- <font class=\"7pt\">$a3</font>\n";
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
	echo "              <td NOWRAP align=\"center\" class=\"$tdc\"><b>".$rowA['phscode']."</b></td>\n";
	echo "              <td NOWRAP align=\"left\" class=\"$tdc\"><b>".$rowA['extphsname']."</b></td>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td NOWRAP align=\"right\" class=\"$tdc\"></td>\n";
		echo "              <td NOWRAP align=\"right\" class=\"$tdc\"></td>\n";
		echo "              <td NOWRAP align=\"right\" class=\"$tdc\" width=\"70\"></td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"3\" align=\"right\" class=\"$tdc\"></td>\n";
	}

	echo "              <td NOWRAP align=\"right\" class=\"$tdc\" width=\"70\"><b>\n";
	
	if ($_SESSION['call']!='view_wo')
	{
		echo $bc;
	}
	
	echo "</b></td>\n";

	if ($_SESSION['manphsadj']==1)
	{
		$adjtotal	=$bc+$adjamt;
		$fadjtotal	=number_format($adjtotal, 2, '.', '');

		echo "              	<td NOWRAP align=\"right\" class=\"$tdc\" width=\"65\">\n";
		echo "         			<input class=\"bbox\" type=\"text\" name=\"adjX".$phsid."\" value=\"".$adjamt."\" size=\"8\">\n";
		echo "					</td>\n";
		echo "              	<td NOWRAP align=\"right\" class=\"$tdc\" width=\"65\"><b>".$fadjtotal."</b></td>\n";
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
	echo "              <td NOWRAP align=\"center\" class=\"$tdc\"><b>".$rowA['phscode']."</b></td>\n";
	echo "              <td NOWRAP align=\"left\" class=\"$tdc\"><b>".$rowA['extphsname']."</b></td>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td NOWRAP align=\"right\" class=\"$tdc\"></td>\n";
		echo "              <td NOWRAP align=\"right\" class=\"$tdc\"></td>\n";
		echo "              <td NOWRAP align=\"right\" class=\"$tdc\" width=\"65\"></td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"3\" align=\"right\" class=\"$tdc\"></td>\n";
	}
	
	echo "              <td NOWRAP align=\"right\" class=\"$tdc\" width=\"70\"><b>\n";
	
	if ($_SESSION['call']!='view_wo')
	{
		echo $bc;
	}
	
	echo "</b></td>\n";

	if ($_SESSION['manphsadj']==1)
	{
		$adjtotal	=$bc+$adjamt;
		$fadjtotal	=number_format($adjtotal, 2, '.', '');

		echo "              	<td NOWRAP align=\"right\" class=\"$tdc\" width=\"65\">\n";
		echo "         			<input class=\"bbox\" type=\"text\" name=\"adjX".$phsid."\" value=\"".$adjamt."\" size=\"8\">\n";
		echo "					</td>\n";
		echo "              	<td NOWRAP align=\"right\" class=\"$tdc\" width=\"100\"><b>".$fadjtotal."</b></td>\n";
	}

	echo "           </tr>\n";
	//}
}

function showitem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$rid)
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

	$quan	=round($quan,1);
	//$bc=round($bc);

	$bc=number_format($bc, 2, '.', '');
	$rc=number_format($rc, 2, '.', '');
	$tdc="lg";

	//if (isset($_REQUEST['showdetail'])||$_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print')
	//{
	echo "			<tr>\n";
	echo "				<td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">";

	if ($cr==0)
	{
		echo $row2['phscode'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['phscode']."</font>";
	}

	echo "				</td>\n";
	echo "					<td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">";

	if ($cr==0)
	{
		echo $row2['extphsname'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']."</font>";
	}

	echo "</td>\n";
	echo "					<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "						<table width=\"100%\" border=0>\n";
	echo "							<tr>\n";
	echo "								<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";

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
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">";

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
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

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
		echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";

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

	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
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
		echo "				<td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">".$row0['phscode']."</td>\n";
		echo "				<td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">".$row0['extphsname']."</td>\n";
		echo "				<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
		echo "					<table width=\"100%\" border=0>\n";
		echo "						<tr>\n";
		echo "							<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">Sales Tax</td>";
		echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"175\" class=\"lg\">".$were."</td>";
		echo "              		</tr>\n";
		echo "              	</table>\n";
		echo "				</td>\n";
		echo "				<td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">".$rate."</td>\n";

		if ($_SESSION['jlev'] >= 5)
		{
			echo "				<td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">".$bc."</td>\n";
		}

		echo "				<td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
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
	echo "			<tr>\n";
	echo "				<td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">\n";

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
	echo "					<td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">";

	if ($cr==0)
	{
		echo $row2['extphsname']." (".$row2['phscode'].")";
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']." (".$row2['phscode'].")</font>";
	}

	echo "</td>\n";
	echo "					<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "						<table width=\"100%\" border=0>\n";
	echo "							<tr>\n";
	echo "								<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";

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
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">";

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
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

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
		echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";

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

	//echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\">".$phsbcrc[0]."</td>\n";
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
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
	echo "			<tr>\n";
	echo "				<td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">\n";

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
	echo "					<td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">";

	if ($cr==0)
	{
		echo $row2['extphsname']." (".$row2['phscode'].")";
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']." (".$row2['phscode'].")</font>";
	}

	echo "</td>\n";
	echo "					<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "						<table width=\"100%\" border=0>\n";
	echo "							<tr>\n";
	echo "								<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";

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
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">";

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
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

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
		echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";

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

	//echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\">".$phsbcrc[0]."</td>\n";
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
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

	echo "           <tr>\n";
	echo "              <td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
	echo "              <td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">\n";

	if ($cr==0)
	{
		echo $row2['extphsname'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']."</font>";
	}

	echo "					</td>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";

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
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"175\" class=\"lg\">\n";

	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

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
		echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";

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
	
	echo "              	<td NOWRAP align=\"center\" valign=\"bottom\" class=\"lg\">\n";
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

	echo "              	<td NOWRAP align=\"center\" valign=\"bottom\" class=\"lg\">\n";

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
	$MAS			=$_SESSION['pb_code'];
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

	echo "           <tr>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";

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
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";
	echo "								<table border=0>\n";

	if (strlen($i) > 1)
	{	
		if ($cr==0)
		{
			echo "              		<tr>\n";
			echo "              			<td NOWRAP align=\"right\" class=\"lg\"><b>BC:</b></td>";
			echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
			echo stripslashes($i);
			echo "              			</td>\n";
			echo "              		</tr>\n";
			//echo " XX";
		}
		else
		{
			echo "              		<tr>\n";
			echo "              			<td NOWRAP align=\"right\" class=\"lg\"><b>BC:</b></td>";
			echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
			echo "								<font color=\"blue\">".stripslashes($i)." (Credit)</font>\n";
			echo "              			</td>\n";
			echo "              		</tr>\n";
		}
	}
	
	if (strlen($a1) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Desc:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a1)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a2) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Vendor:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a2)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a3) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Part No:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a3)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	echo "              				</table>\n";
	echo "              			</td>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	//print_r($phsbcrc);
	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

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
		echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";

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
	
	echo "              	<td NOWRAP align=\"center\" valign=\"bottom\" class=\"lg\">\n";

	//echo "MJADD2 :".$viewarray['mjadd']."<br>";
	//echo "MJADD3 :".$jadd."<br>";
	if ($viewarray['mjadd']==$jadd && $viewarray['allowdel']==0 && $masprep == 0)
	{
		echo " 						<a href=\".\index.php?sid=".md5($_SESSION['securityid'])."&action=".$_SESSION['action']."&call=biddel&officeid=".$_SESSION['officeid']."&bbid=".$iid."\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\">Delete</a>\n";
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

	echo "           <tr>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";

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
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";
	echo "								<table border=0>\n";

	if (strlen($i) >= 1)
	{	
		if ($cr==0)
		{
			echo "              		<tr>\n";
			echo "              			<td NOWRAP align=\"right\" class=\"lg\"><b>MPA:</b></td>";
			echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
			echo stripslashes($i);
			echo "              			</td>\n";
			echo "              		</tr>\n";
			//echo " XX";
		}
		else
		{
			echo "              		<tr>\n";
			echo "              			<td NOWRAP align=\"right\" class=\"lg\"><b>MPA:</b></td>";
			echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
			echo "								<font color=\"blue\">".stripslashes($i)." (Credit)</font>\n";
			echo "              			</td>\n";
			echo "              		</tr>\n";
		}
	}
	
	if (strlen($a1) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Desc:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a1)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a2) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Vendor:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a2)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a3) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Part No:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a3)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	echo "              				</table>\n";
	echo "              			</td>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	//print_r($phsbcrc);
	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

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
		echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";

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
	
	echo "              	<td NOWRAP align=\"center\" valign=\"bottom\" class=\"lg\">\n";

	//echo "MJADD2 :".$viewarray['mjadd']."<br>";
	//echo "MJADD3 :".$jadd."<br>";
	if ($viewarray['mjadd']==$jadd && $viewarray['allowdel']==0 && $masprep == 0)
	{
		echo " 						<a href=\".\index.php?sid=".md5($_SESSION['securityid'])."&action=".$_SESSION['action']."&call=mpadel&officeid=".$_SESSION['officeid']."&bbid=".$iid."\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\">Delete</a>\n";
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
	echo "              <td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
	echo "              <td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">\n";

	if ($cr==0)
	{
		echo $row2['extphsname'];
	}
	else
	{
		echo "<font color=\"blue\">".$row2['extphsname']." (".$row2['phscode'].")</font>";
	}

	echo "					</td>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";

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
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"175\" class=\"lg\">\n";

	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

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
		echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";

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

	echo "              	<td NOWRAP align=\"center\" valign=\"bottom\" class=\"lg\">\n";

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
	echo "              <td NOWRAP valign=\"top\" align=\"center\" class=\"lg\">\n";
	
	if ($jadd!=0)
	{
		echo "60".$jadd."L";
	}
	else
	{
		echo $row2['phscode'];
	}
	
	echo "				  </td>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	
		if ($cr==0)
		{
			echo $row2['extphsname']." (".$row2['phscode'].")";
		}
		else
		{
			echo "<font color=\"blue\">".$row2['extphsname']." (".$row2['phscode'].")</font>";
		}

	echo "					</td>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";
	echo "								<table border=0>\n";

	if (strlen($i) > 1)
	{	
		if ($cr==0)
		{
			echo "              		<tr>\n";
			echo "              			<td NOWRAP align=\"right\" class=\"lg\"><b>BC:</b></td>";
			echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
			echo stripslashes($i);
			echo "              			</td>\n";
			echo "              		</tr>\n";
			//echo " XX";
		}
		else
		{
			echo "              		<tr>\n";
			echo "              			<td NOWRAP align=\"right\" class=\"lg\"><b>BC:</b></td>";
			echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
			echo "								<font color=\"blue\">".stripslashes($i)." (Credit)</font>\n";
			echo "              			</td>\n";
			echo "              		</tr>\n";
		}
	}
	
	if (strlen($a1) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Desc:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a1)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a2) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Vendor:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a2)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a3) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Part No:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a3)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	echo "              				</table>\n";
	echo "              			</td>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	//print_r($phsbcrc);
	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

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
		echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";

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
	
	echo "              	<td NOWRAP align=\"center\" valign=\"bottom\" class=\"lg\">\n";

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
	echo "              <td NOWRAP valign=\"top\" align=\"center\" class=\"lg\">\n";
	
	if ($jadd!=0)
	{
		echo "60".$jadd."L";
	}
	else
	{
		echo $row2['phscode'];
	}
	
	echo "				  </td>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	
		if ($cr==0)
		{
			echo $row2['extphsname']." <br>(".$row2['phscode'].")";
		}
		else
		{
			echo "<font color=\"blue\">".$row2['extphsname']." <br>(".$row2['phscode'].")</font>";
		}

	echo "					</td>\n";
	echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	echo "					<table width=\"100%\" border=0>\n";
	echo "              		<tr>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">";
	echo "								<table border=0>\n";

	if (strlen($i) > 1)
	{	
		if ($cr==0)
		{
			echo "              		<tr>\n";
			echo "              			<td NOWRAP align=\"right\" class=\"lg\"><b>MPA:</b></td>";
			echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
			echo stripslashes($i);
			echo "              			</td>\n";
			echo "              		</tr>\n";
			//echo " XX";
		}
		else
		{
			echo "              		<tr>\n";
			echo "              			<td NOWRAP align=\"right\" class=\"lg\"><b>MPA:</b></td>";
			echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
			echo "								<font color=\"blue\">".stripslashes($i)." (Credit)</font>\n";
			echo "              			</td>\n";
			echo "              		</tr>\n";
		}
	}
	
	if (strlen($a1) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Desc:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a1)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a2) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Vendor:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a2)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	if (strlen($a3) > 1)
	{
		echo "              		<tr>\n";
		echo "              			<td NOWRAP align=\"right\" class=\"lg\">Part No:</td>";
		echo "              			<td NOWRAP align=\"left\" class=\"lg\">";
		echo "								<font class=\"7pt\">".stripslashes($a3)."</font>\n";
		echo "              			</td>\n";
		echo "              		</tr>\n";
	}
	
	echo "              				</table>\n";
	echo "              			</td>\n";
	echo "              			<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
	//print_r($phsbcrc);
	if ($rid!=0)
	{
		echo "(".$row3['item'].")";
	}

	echo "              			</td>\n";
	echo "              		</tr>\n";
	echo "              	</table>\n";
	echo "              </td>\n";
	echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";

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
		echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";

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
	
	echo "              	<td NOWRAP align=\"center\" valign=\"bottom\" class=\"lg\">\n";

	//echo $viewarray['allowdel']."<br>";
	//echo "MJADD2 :".$viewarray['mjadd']."<br>";
	//echo "MJADD3 :".$jadd."<br>";
	if ($viewarray['mjadd']==$jadd && $viewarray['allowdel']==0)
	{
		echo " 						<a href=\".\index.php?sid=".md5($_SESSION['securityid'])."&action=".$_SESSION['action']."&call=mpadel&officeid=".$_SESSION['officeid']."&bbid=".$iid."\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\">Delete</a>\n";
	}
	
	echo "					</td>\n";
	echo "           </tr>\n";
}


function showMitem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr,$iid)
{
	error_reporting(E_ALL);
	$MAS=$_SESSION['pb_code'];
	$qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
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

		echo "           <tr>\n";
		//echo "              <td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
		echo "				<td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">";

		if ($cr==0)
		{
			echo $row2['phscode'];
		}
		else
		{
			echo "<font color=\"blue\">".$row2['phscode']."</font>";
		}

		echo "				</td>\n";
		echo "              <td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">";

		if ($cr==0)
		{
			echo $row2['extphsname'];
		}
		else
		{
			echo "<font color=\"blue\">".$row2['extphsname']."</font>";
		}

		echo "</td>\n";
		echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
		echo "					<table width=\"100%\" border=0>\n";
		echo "              		<tr>\n";
		echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">\n";

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
		echo "              			<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">";

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
		echo "              <td NOWRAP valign=\"bottom\" align=\"right\" class=\"lg\" width=\"30\">";

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
			echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";
			
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
		echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
		echo "           </tr>\n";
	}
}

?>
