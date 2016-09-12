<?php

function set_sdate()
{
	$rtime	=time();
	$pdate	=getdate();
	$dcnt	=86400;
	
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

function LeadReport()
{
	$qry	= "SELECT officeid,admstaff,lname FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
	
	$qry1	= "SELECT endigreport,gm,am,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1	= mssql_query($qry1);
	$row1	= mssql_fetch_array($res1);
	
	if (($_SESSION['officeid']==89 && $_SESSION['llev'] >= 5))
	{
	    @lead_report_daily_admin();
	}
	else
	{
		if (($_SESSION['securityid'] == $row1['gm'] or $_SESSION['securityid'] == $row1['am'] or $_SESSION['llev'] >= 9) and $_SESSION['llev'] >= 5  and $row1['finan_off'] != 1)
		{
			@lead_report_daily_office();
		}
	}
}

function CustServ()
{
	$oid=$_SESSION['officeid'];	
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
	
	echo "<table align=\"center\">\n";
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
	echo "						<td colspan=\"3\" align=\"center\"><b>";
	
	if ($oid==89)
	{
		echo "Systemwide";
	}
	
	echo " Activity</b></td>\n";
	echo "					</tr>\n";
	echo "					<tr class=\"white\">\n";
	echo "						<td align=\"right\">Open Service Requests</td>\n";
	echo "						<td align=\"center\" width=\"30px\">\n";
	echo "							<font color=\"black\"><b>".$nrow0."</b></font>\n";
	echo "						</td>\n";
	echo "						<td>\n";
	
	if ($nrow0 > 0)
	{
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
		echo "							<input class=\"transnb\" type=\"image\" value=\"".$nrow0."\" src=\"../images/search.gif\" alt=\"View Service Requests\">\n";
		echo "         				</form>\n";
	}
	
	echo "						</td>\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "					<tr class=\"ltgray\">\n";
	echo "						<td align=\"right\">Open Complaints</td>\n";
	echo "						<td align=\"center\" width=\"30px\">\n";
	echo "							<font color=\"black\"><b>".$nrow1."</b></font>\n";
	echo "						</td>\n";
	echo "						<td>\n";
	
	if ($nrow1 > 0)
	{
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
		echo "							<input class=\"transnb\" type=\"image\" value=\"".$nrow1."\" src=\"../images/search.gif\" alt=\"View Complaints\">\n";
		echo "         				</form>\n";
	}
	
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function ConList()
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
        
		echo "<table align=\"center\">\n";
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

function lead_report_daily_office()
{
	$sdate=set_sdate();
	
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
	
	echo "<table align=\"center\">\n";
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
		
		
		if ($_SESSION['securityid']==26999999999999999999999999999999)
		{
			
			echo $qry1.'<br>';
		}
		
		$qry2	= "select lid from lead_inc where sorted=0;";
		$res2	= mssql_query($qry2);
		$nrow2	= mssql_num_rows($res2);
		
		echo "<table align=\"center\" width=\"600px\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"3\" align=\"center\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\">\n";
		
		checklastleadimport();
		
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"3\" align=\"center\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>Enterprise Lead Activity</b></td>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>".$sdate[0]." - ".$sdate[1]."</b></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"center\" valign=\"top\" width=\"33%\">\n";
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
					
					$tbg=($acnt%2)? 'even': 'odd';
					echo "			<tr class=\"".$tbg."\">\n";
					echo "				<td align=\"right\">".$row0['name']."</td>\n";
					echo "				<td align=\"center\" width=\"30px\">".$row0['lcnt']."</td>\n";
					echo "				<td align=\"left\" width=\"20px\">\n";
					echo "         		<form method=\"post\">\n";
					echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
					echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
					echo "						<input type=\"hidden\" name=\"noffid\" value=\"".$row0['officeid']."\">\n";
					echo "						<input type=\"hidden\" name=\"shownm\" value=\"1\">\n";
					echo "						<input class=\"provided_lead_fwd\" type=\"image\" src=\"images/search.gif\" height=\"10\" width=\"10\" title=\"View Leads\">\n";
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
		echo "		<td align=\"center\" valign=\"top\" width=\"33%\">\n";
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
				$tbgb=($bcnt%2)? 'even': 'odd';				
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
		echo "		<td align=\"center\" valign=\"top\" width=\"33%\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr class=\"tblhd\">\n";
		echo "					<td align=\"center\" colspan=\"2\"><b>Unsorted Leads</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td align=\"right\" width=\"50%\">".$nrow2."</td>\n";
		echo "					<td align=\"left\" width=\"50%\">\n";
		echo "         				<form method=\"post\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "							<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "							<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "							<input class=\"provided_lead_fwd\"  type=\"image\" src=\"images/search.gif\" height=\"10\" width=\"10\" title=\"View Unsorted Leads\">\n";
		echo "         				</form>\n";	
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function SysAnn()
{
	$qryA  = "SELECT * FROM systemwidemessage WHERE active='1' and officeid='0' ORDER BY added DESC;";
	$resA  = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);
	
	if ($nrowA > 0)
	{
		echo "			<table align=\"center\">\n";
	
		while ($rowA  = mssql_fetch_array($resA))
		{
			echo "				<tr>\n";
			echo "					<th align=\"left\" valign=\"top\"><b>".$rowA['subject']."</b></th>\n";
			echo "					<th align=\"right\" valign=\"top\">".date('m/d/Y g:i T',strtotime($rowA['added']))."</th>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td colspan=\"2\" align=\"left\" valign=\"top\">".$rowA['message']."</td>\n";
			echo "				</tr>\n";
		}
   
	   echo "			</table>\n";
	}
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
