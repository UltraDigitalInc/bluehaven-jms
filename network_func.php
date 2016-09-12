<?php

function basematrix()
{
	if ($_SESSION['call']=="search_net")
	{
        search_panel_NET();
	}
	elseif ($_SESSION['call']=="search_results_net")
	{
        list_NET();
	}
	elseif ($_SESSION['call']=="add_net")
	{
		cform_NET();
	}
	elseif ($_SESSION['call']=="save_net")
	{
		cform_save_NET();
	}
	elseif ($_SESSION['call']=="view_net")
	{
		cform_view_NET();
	}
	elseif ($_SESSION['call']=="edit_net")
	{
		cform_edit_NET();
	}
	elseif ($_SESSION['call']=="addcomment_net")
	{
		addcomment_NET();
	}
    elseif ($_SESSION['call']=="sendetemp_fromPreview")
    {
        process_template_email();
	}
	else
	{
		search_panel_NET();
	}
	
	//display_array($_REQUEST);
	
	//echo "Original Time: ". date("h:i:s")."<br>\n";
	//putenv("TZ=US/Eastern");
	//echo "New Time: ". date("h:i:s")."<br>\n";
}

function tickle_NET()
{
	
	if ($_SESSION['securityid']==2699999999999999)
	{
		error_reporting(E_ALL);
	    ini_set('display_errors','On');
	}
	
	$qryA = "SELECT securityid,emailtemplateaccess,sidm,networkaccess,substring(slevel,13,1) as sslevel FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	$qryCB  = "
		select 
			cnid,oid,sid,lname,fname,mid,cpname,cfullname,caddr1,ccity,cstate,czip1,adate,udate,calldate,cwork,cemail
		from 
			list_cinfo_net 
		where
			oid=".$_SESSION['officeid']." ";
	
	if ($rowA['networkaccess'] > 5)
	{
		$qryCB  .= " AND sid=".$_REQUEST['secid']." ";
	}
	elseif ($rowA['networkaccess'] == 5)
	{
		if ($_REQUEST['secid']!=$_SESSION['securityid'])
		{
			$qryCB  .= "	AND sid IN (select sid from security where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
		}
		else
		{
			$qryCB  .= " AND sid=".$_SESSION['securityid']." ";
		}
	}
	else
	{
		$qry  .= " AND sid=".$_SESSION['securityid']." ";
	}
			
	$qryCB .= "
			and calldate BETWEEN (getdate() - 2) and (getdate()+14)
			and active!=1
		order by
			calldate desc
	";
	$resCB = mssql_query($qryCB);
	$nrowCB= mssql_num_rows($resCB);
	
	//echo $qryCB.'<br>';
	
	if ($nrowCB > 0)
	{
		echo "<table width=\"1200px\" align=\"center\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<div id=\"tickletabs\" class=\"tickletabs\">\n";
		echo "				<ul>\n";
		echo "			    	<li><a href=\"#cb\">Callbacks</a></li>\n";
		echo "				</ul>\n";
		echo "			</div>\n";
		echo "			<div id=\"cb\">\n";
		echo "			<table id=\"TickleTable\" class=\"tablesorter\" cellpadding=\"1\">\n";
		echo "				<thead>\n";
		echo "				<tr>\n";
		echo "					<th width=\"20\"><img src=\"images/pixel.gif\"></th>\n";
        echo "					<th width=\"200\"><b>Company Name</b></th>\n";
		echo "                  <th width=\"75\"><b>Phone</b></th>\n";
		echo "                  <th width=\"150\"><b>Address</b></th>\n";
		echo "                  <th width=\"100\"><b>City</b></th>\n";
		echo "                  <th width=\"40\"><b>St</b></th>\n";
		echo "                  <th width=\"40\"><b>Zip</b></th>\n";
		echo "					<th width=\"100\"><b>Primary Contact</b></th>\n";
		echo "					<th width=\"150\"><b>Sales Rep</b></th>\n";
		echo "					<th width=\"90\"><b>Callback</b></th>\n";
		echo "            	    <th width=\"75\" align=\"right\">".$nrowCB." Result(s)</th>\n";
		echo "				</tr>\n";
		echo "				</thead>\n";
		echo "				<tbody>\n";
		
		$rcntCB=0;
		while ($rowCB= mssql_fetch_array($resCB))
		{
			$rcntCB++;
			if ($rcntCB%2)
			{
				$tbgCB = 'white';
			}
			else
			{
				$tbgCB = 'ltgray';
			}
			
			$tranidCB  =md5(session_id().time().$rowCB['cnid']).".".$_SESSION['securityid'];
			echo "				<tr>\n";
			echo "					<td align=\"right\" class=\"".$tbgCB."\">".$rcntCB++.".</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['cpname']."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgCB."\">";
			
			if (isset($rowCB['cwork']) && strlen($rowCB['cwork']) > 2)
			{
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowCB['cwork'])));
			}
			
			echo "					</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['caddr1']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['ccity']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['cstate']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['czip1']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['cfullname']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['lname'].", ".$rowCB['fname']."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgCB."\">".$rowCB['calldate']."</td>\n";
			echo "					<td class=\"".$tbgCB."\" align=\"right\">\n";
			echo "						<div class=\"noPrint\">\n";
			echo "						<form method=\"POST\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"network\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"view_net\">\n";
			echo "						<input type=\"hidden\" name=\"cnid\" value=\"".$rowCB['cnid']."\">\n";
			echo "						<input type=\"hidden\" name=\"tranid\" value=\"".$tranidCB."\">\n";
			echo "							<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
			echo "						</form>\n";
			echo "						</div>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "				</tbody>\n";
			echo "			</table>\n";
		}
	
		echo "				    </div>\n";	
		echo "				</div>\n";
		echo "			</div>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	/*
	else
	{
		echo "			<table>\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\"><b>No Network Leads</b></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
	}
	*/
}

function addcomment_NET()
{
	//echo 'Adding Comment<br>';
	$qry0  	= "SELECT chid FROM jest..chistory_net WHERE cnid=".$_REQUEST['cnid']." and tranid='".$_REQUEST['tranid']."';";
	$res0  	= mssql_query($qry0);
	$nrow0 	= mssql_num_rows($res0);
	
	if ($nrow0==0)
	{
		$qry1  	=	"INSERT INTO jest..chistory_net (cnid,oid,sid,tranid,act,mtext) VALUES (
					".$_REQUEST['cnid'].",
					".$_SESSION['officeid'].",
					".$_SESSION['securityid'].",
					'".$_REQUEST['tranid']."',
					'Network',
					'".htmlspecialchars(trim($_REQUEST['ccomments']))."'
						);";
		$res1  	= mssql_query($qry1);	
	}
	
	cform_view_NET();
}

function search_dialog_NET()
{
	$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	$qryB = "SELECT securityid,emailtemplateaccess,networkaccess,substring(slevel,13,1) as sslevel FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	if ($rowB['networkaccess'] == 5)
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE securityid=".$_SESSION['securityid']." OR sidm=".$_SESSION['securityid']." ORDER BY substring(slevel,13,1) desc,lname ASC;";
	}
	elseif ($rowB['networkaccess'] == 6)
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE officeid=".$_SESSION['officeid']." AND substring(slevel,13,1) > 0 ORDER BY substring(slevel,13,1) desc,lname ASC;";
	}
	elseif ($rowB['networkaccess'] > 6)
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE officeid=".$_SESSION['officeid']." ORDER BY substring(slevel,13,1) desc,lname ASC;";
	}
	else
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE securityid=".$_SESSION['securityid'].";";
	}
	
	$resBa = mssql_query($qryBa);
	$nrowsBa = mssql_num_rows($resBa);
	
	$fr_ar=array('cpname'=>'Company','caddr1'=>'Address','cemail'=>'Email','cfullname'=>'Primary Contact','contactdate'=>'Last Contact');
	$at_ar=array(0=>'Active',1=>'Inactive',2=>'Both');

	//echo "				<div class=\"searchpanelDIM\">\n";
	echo "			<table><tr><td>\n";
	echo "					<form id=\"netsearch\" name=\"netsearch\" method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"network\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"search_net\">\n";
	echo "					<input type=\"hidden\" name=\"subq1\" value=\"search_results_net\">\n";
	echo "					<input type=\"hidden\" name=\"subq2\" value=\"sstring\">\n";
	echo "<p>\n";
	echo "					<label class=\"JMStooltip\" for=\"field\" title=\"Select a Field to Search\">Search Field</label><br>\n";
	echo "					<select name=\"field\" id=\"field\">\n";
	
	foreach ($fr_ar as $fn => $fv)
	{
		if (isset($_REQUEST['field']) && $_REQUEST['field']==$fn)
		{
			echo "						<option value=\"".$fn."\" SELECTED>".$fv."</option>\n";
		}
		else
		{
			echo "						<option value=\"".$fn."\">".$fv."</option>\n";
		}
	}
	
	echo "					</select>\n";
	echo "</p>\n";
	echo "<p>\n";
	echo "					<label class=\"JMStooltip\" for=\"ssearch\" title=\"Enter Full or Partial Text then click the button to Search\">Search Text</label><br>\n";
	if (isset($_REQUEST['ssearch']) && strlen($_REQUEST['ssearch']) > 0)
	{
		echo "					<input type=\"text\" name=\"ssearch\" id=\"ssearch\" value=\"".trim($_REQUEST['ssearch'])."\" size=\"20\" maxlength=\"40\"><br>\n";
	}
	else
	{
		echo "					<input type=\"text\" name=\"ssearch\" id=\"ssearch\" size=\"20\" maxlength=\"40\"><br>\n";	
	}
	echo "</p>";
	echo "<p>";
	echo "					<label class=\"JMStooltip\" for=\"secid\" title=\"Narrow your search to a single Sales Representative\">Assigned</label><br>\n";
	echo "					<select name=\"secid\" id=\"secid\">\n";
	
	if (isset($_REQUEST['secid']))
	{
		$ssid=$_REQUEST['secid'];
	}
	else
	{
		$ssid=$rowB['securityid'];
	}
	
	if ($rowB['networkaccess'] >= 6)
	{
		echo "						<option class=\"fontblack\" value=\"NA\">All</option>\n";
	}
	
	while ($rowBa = mssql_fetch_array($resBa))
	{
		if ($ssid==$rowBa['securityid'])
		{
			if ($rowBa['sslevel'] > 0)
			{
				echo "						<option class=\"fontblack\" value=\"".$rowBa['securityid']."\" SELECTED>".$rowBa['lname'].", ".$rowBa['fname']."</option>\n";
			}
			else
			{
				echo "						<option class=\"fontred\" value=\"".$rowBa['securityid']."\" SELECTED>".$rowBa['lname'].", ".$rowBa['fname']."</option>\n";
			}
		}
		else
		{
			if ($rowBa['sslevel'] > 0)
			{
				echo "						<option class=\"fontblack\" value=\"".$rowBa['securityid']."\">".$rowBa['lname'].", ".$rowBa['fname']."</option>\n";
			}
			else
			{
				echo "						<option class=\"fontred\" value=\"".$rowBa['securityid']."\">".$rowBa['lname'].", ".$rowBa['fname']."</option>\n";
			}
		}
	}
	
	echo "					</select>\n";
	echo "</p>";
	echo "<p>";
	echo "					<label class=\"JMStooltip\" for=\"active\" title=\"Search Active and/or Inactive Leads\">Status</label><br>\n";
	echo "					<select name=\"active\" id=\"active\">\n";
	
	foreach ($at_ar as $an => $av)
	{
		if (isset($_REQUEST['active']) && $_REQUEST['active']==$an)
		{
			echo "						<option value=\"".$an."\" SELECTED>".$av."</option>\n";	
		}
		else
		{
			echo "						<option value=\"".$an."\">".$av."</option>\n";
		}
	}

	echo "					</select>\n";
	echo "</p>";
	echo "<p>";
	echo "					<button type=\"submit\"><img src=\"images/search.gif\"></button>\n";
	echo "</p>";
	echo "					</form>\n";
	echo "</td></tr></table>\n";
	//echo "				</div>\n";
	
	//if ($_SESSION['securityid']==26)
	//{
	//	display_array($_REQUEST);
	//}
}

function search_panel_NET()
{
	unset($_SESSION['tqry']);
	unset($_SESSION['et_uid']);
	
	$hlpnd=1;
	
	echo "<div class=\"noPrint\">\n";
	echo "<div class=\"searchaccordion\" id=\"searchaccordion\">\n";
	echo "	<h3><a href=\"#\">Network Search</a></h3>\n";
	echo "	<div>\n";
	echo "		<p>\n";

	search_dialog_NET();
	
	echo "		</p>\n";
	echo "	</div>\n";
	echo "</div>\n";
	echo "</div>\n";
	
	if (isset($_REQUEST['subq1']) && $_REQUEST['subq1']=='search_results_net')
	{
		list_NET();
	}
	else
	{
		tickle_NET();
	}
}

function list_NET()
{
	$unxdt=time();
	
	$qry0 = "SELECT securityid,emailtemplateaccess,networkaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$qry   = "SELECT ";
	$qry  .= "		* ";
	$qry  .= "FROM ";
	$qry  .= "	list_cinfo_net ";
	$qry  .= "WHERE ";
	$qry  .= "	oid=".$_SESSION['officeid']." ";
	
	if (isset($_REQUEST['active']) && $_REQUEST['active']==2)
	{
	}
	elseif (isset($_REQUEST['active']) && $_REQUEST['active']==1)
	{
		$qry  .= "	AND active=1 ";
	}
	else
	{
		$qry  .= "	AND active=0 ";
	}
	
	if (isset($_REQUEST['d1']) && isset($_REQUEST['d2']) && !empty($_REQUEST['d1']) && !empty($_REQUEST['d2']))
	{
		$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' ";
	}
	
	if (
				isset($_REQUEST['subq1'])
			&& isset($_REQUEST['subq2'])
			&& isset($_REQUEST['ssearch'])
			&& $_REQUEST['subq1']=='search_results_net'
			&& $_REQUEST['subq2']=='sstring'
			&& strlen($_REQUEST['ssearch']) >= 1
		)
	{
		$qry  .= "	AND ".$_REQUEST['field']." LIKE '".htmlspecialchars_decode($_REQUEST['ssearch'])."%' ";
	}
	
	if (isset($_REQUEST['secid']) && $_REQUEST['secid'] != 'NA')
	{
		if ($row0['networkaccess'] > 5)
		{
			$qry  .= " AND sid=".$_REQUEST['secid']." ";
		}
		elseif ($row0['networkaccess'] == 5)
		{
			if ($_REQUEST['secid']!=$_SESSION['securityid'])
			{
				$qry  .= "	AND sid IN (select sid from security where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			}
			else
			{
				$qry  .= " AND sid=".$_SESSION['securityid']." ";
			}
		}
		else
		{
			$qry  .= " AND sid=".$_SESSION['securityid']." ";
		}
	}
	
	$qry  .= " ORDER BY ".$_REQUEST['field'].";";
	
	//if ($_SESSION['securityid']==26)
	//{
	//	echo $qry."<br>";
	//}
	
	if (isset($_SESSION['tqry']) && trim($_SESSION['tqry'])===trim($qry))
	{
		//echo "ZERO<br>";
		$qry	=$_SESSION['tqry'];
		echo "			<table class=\"outer\" align=\"center\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>NOTE:</b> These Search Results are based upon previously entered Search parameters. Click <b>New Search</b> to clear this condition.</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
	}
	
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	$_SESSION['tqry']=$qry;

	if ($nrows == 0)
	{
		echo "         <b>No Records Found</b>\n";
	}
	else
	{
		
		echo "<table align=\"center\" width=\"1200px\">\n";
		echo "	<tr>\n";
		echo "		<td>\n";
		echo "<div class=\"resulttabs\" id=\"resulttabs\">\n";
		echo "	<ul>\n";
		echo "		<li><a href=\"#Results\">Search Result</a></li>\n";
		echo "	</ul>\n";
		echo "	<div id=\"Searches\">\n";
		echo "			<table id=\"myTable\" class=\"tablesorter\" cellpadding=\"1\">\n";
		echo "				<thead>\n";
		echo "					<tr>\n";
		echo "						<th width=\"20\"><img src=\"images/pixel.gif\"></th>\n";
        echo "						<th width=\"200\"><b>Company Name</b></th>\n";
		echo "                     	<th width=\"75\"><b>Phone</b></th>\n";
		echo "                     	<th width=\"150\"><b>Address</b></th>\n";
		echo "                     	<th width=\"100\"><b>City</b></th>\n";
		echo "                     	<th width=\"40\"><b>St</b></th>\n";
		echo "                     	<th width=\"40\"><b>Zip</b></th>\n";
		echo "						<th width=\"100\"><b>Primary Contact</b></th>\n";
		echo "						<th width=\"150\"><b>SalesRep</b></th>\n";
		echo "						<th width=\"90\"><b>Last Contact</b></th>\n";
		echo "            	        <th width=\"75\" align=\"right\">".$nrows." Result(s)</th>\n";
		echo "                  </tr>\n";
		echo "				</thead>\n";
		echo "				<tbody>\n";

		$etemp_ar=array();
		$nph_ar= array('0000000000','none','N/A');
		$age30=2592000; //30 Days
		$age15=1296000; //15 Days
		$age07=604800; // 7 Days
		$age01=86400; // 7 Days
		$ts_tdate=getdate();
		$lcnt=0;
		$altdtext="";
		
		while ($row=mssql_fetch_array($res))
		{
			$uid	= md5(time().$row['cid']).".".$_SESSION['securityid'];
			$udate	= date("m/d/Y", strtotime($row['udate']));
			
			$lcnt++;

			echo "					<tr>\n";
			echo "						<td align=\"right\">".$lcnt.".</td>\n";
            echo "						<td align=\"left\" width=\"200\">".ucwords(htmlspecialchars_decode($row['cpname']))."</td>\n";
			echo "						<td align=\"left\" width=\"75\">\n";
			//echo "						<td align=\"left\" width=\"75\">".htmlspecialchars_decode($row['cwork'])."</td>\n";
			if (isset($row['cwork']) && strlen($row['cwork']) > 2)
			{
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($row['cwork'])));
			}
			
			echo "						</td>\n";
			echo "						<td align=\"left\" width=\"150\">".ucwords(htmlspecialchars_decode($row['caddr1']))."</td>\n";
			echo "						<td align=\"left\" width=\"100\">".htmlspecialchars_decode($row['ccity'])."</td>\n";
			echo "						<td align=\"center\" width=\"20\">".htmlspecialchars_decode($row['cstate'])."</td>\n";
			echo "						<td align=\"center\" width=\"40\">".htmlspecialchars_decode($row['czip1'])."</td>\n";
			echo "						<td align=\"left\" width=\"100\">".ucwords(htmlspecialchars_decode($row['cfullname']))."</td>\n";
			//echo "						<td align=\"left\" width=\"150\">".htmlspecialchars_decode($row['cemail'])."</td>\n";
			echo "						<td align=\"left\" >".$row['lname'].", ".$row['fname']."</td>\n";
			echo "						<td align=\"center\" width=\"75\">\n";
			
			echo date('m/d/Y',strtotime($row['contactdate']));
			
			echo "                     	</td>\n";
			echo "                     	<td align=\"right\">\n";
			echo "							<div class=\"noPrint\">\n";
			echo "                     		<form method=\"POST\">\n";
			echo "                     			<input type=\"hidden\" name=\"action\" value=\"network\">\n";
			echo "                     			<input type=\"hidden\" name=\"call\" value=\"view_net\">\n";
			echo "                     			<input type=\"hidden\" name=\"cnid\" value=\"".$row['cnid']."\">\n";
			echo "                     			<input type=\"hidden\" name=\"tranid\" value=\"".$uid."\">\n";
			echo "						        <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
			echo "                     		</form>\n";
			echo "							</div>\n";
			echo "                     	</td>\n";
			echo "					</tr>\n";
		}
		
		echo "				</tbody>\n";
		echo "			</table>\n";
		echo "				    </div>\n";	
		echo "				</div>\n";
		echo "			</div>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		
	}
}

function cform_NET()
{
	$tranid		=md5(session_id().time()).".".$_SESSION['securityid'];
	
	$qryA = "SELECT officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
    $rowA = mssql_fetch_array($resA);
	$nrowsA = mssql_num_rows($resA);

	$qryB = "SELECT securityid,officeid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
	
	if ($rowB['networkaccess'] == 1)
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE securityid=".$_SESSION['securityid'].";";
	}
	elseif ($rowB['networkaccess'] <= 5)
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE securityid=".$_SESSION['securityid']." OR sidm=".$_SESSION['securityid']." ORDER BY substring(slevel,13,1) desc,lname ASC;";
	}
	else
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE officeid=".$_SESSION['officeid']." AND substring(slevel,13,1) > 0 ORDER BY substring(slevel,13,1) desc,lname ASC;";
	}
	
	$resBa = mssql_query($qryBa);
	$nrowsBa = mssql_num_rows($resBa);

	$qryC = "SELECT * FROM jest..states ORDER BY abrev ASC;";
	$resC = mssql_query($qryC);
	$nrowsC = mssql_num_rows($resC);
	
	$qryD = "SELECT * FROM jest..nettypes ORDER BY netname ASC;";
	$resD = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);

	$hlpnd=1;

	echo "<script type=\"text/javascript\" src=\"js/validate_form.js\"></script>\n";
	echo "<table width=\"500px\" align=\"center\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table width=\"100%\" align=\"center\">\n";
	echo "				<tr>\n";
	echo "					<td valign=\"top\">\n";
	echo "                  	<form method=\"post\" onSubmit=\"return AddNetworkFormValidate();\">\n";
	echo "                  	<input type=\"hidden\" name=\"action\" value=\"network\">\n";
	echo "                  	<input type=\"hidden\" name=\"call\" value=\"save_net\">\n";
	echo "                  	<input type=\"hidden\" name=\"tranid\" value=\"".$tranid."\">\n";
	echo "						<fieldset class=\"leadform\">\n";
    echo "						<legend>Company</legend>\n";
	echo "							<label>Type</label><br>\n";
	echo "							<select tabindex=\"1\" name=\"cptype\">\n";
	echo "								<option value=\"0\">Select...</option>\n";
	
	while ($rowD=mssql_fetch_array($resD))
	{
		echo "								<option value=\"".$rowD['ntid']."\">".$rowD['netname']."</option>\n";
	}
	
	echo "							</select><br>\n";
	echo "							<label>Name</label><br>\n";
    echo "							<input tabindex=\"2\" type=\"text\" size=\"50\" name=\"cpname\" id=\"cpname\"><br>\n";
	echo "							<label>Address</label><br>\n";
	echo "							<input tabindex=\"3\" type=\"text\" size=\"50\" name=\"caddr1\"><br>\n";
	echo "							<label>City</label><br>\n";
	echo "							<input tabindex=\"4\" type=\"text\" size=\"20\" name=\"ccity\"><br>\n";	
	echo "							<label>State</label><br>\n";
	echo "							<select tabindex=\"5\" name=\"cstate\">\n";
	
	while ($rowC=mssql_fetch_array($resC)) {
		echo "								<option value=\"".$rowC['abrev']."\">".$rowC['abrev']."</option>\n";
	}
	
	echo "							</select><br>\n";
	echo "							<label>Zip</label><br>\n";
	echo "							<input tabindex=\"6\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\"><br>\n";	
	echo "						</fieldset>\n";
	
	echo "						<fieldset class=\"leadform\">\n";
    echo "						<legend><b>Contact</b></legend>\n";
	echo "							<label>Primary Contact</label><br>\n";
	echo "							<input tabindex=\"7\" type=\"text\" size=\"40\" name=\"cfullname\"><br>\n";
	echo "							<label>E-Mail</label><br>\n";
	echo "							<input tabindex=\"8\" type=\"text\" name=\"cemail\" id=\"cemail\" size=\"40\"><br>\n";	
	echo "							<label>Phone</label><br>\n";
	echo "							<input tabindex=\"9\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\"><br>\n";	
	echo "							<label>Cell</label><br>\n";
	echo "							<input tabindex=\"10\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\"><br>\n";	
	echo "							<label>Fax</label><br>\n";
	echo "							<input tabindex=\"11\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\"><br>\n";	
	echo "						</fieldset>\n";
	echo "						<fieldset class=\"leadform\">\n";
    echo "						<legend><b>Comment</b></legend>\n";
    echo "							<textarea tabindex=\"15\" name=\"ccomments\" rows=\"5\" cols=\"60\"></textarea><br>\n";
	echo "						</fieldset>\n";
    echo "  				</td>\n";
	echo "                  <td valign=\"top\">\n";
	echo "						<fieldset class=\"leadform\">\n";
    echo "						<legend><b>Dates</b></legend>\n";	
	echo "							<label>Last Contact</label><br>\n";
	echo "							<input tabindex=\"12\" type=\"text\" size=\"16\" name=\"contactdate\" id=\"d1\"><br>\n";	
	echo "							<label>Callback</label><br>\n";
	echo "							<input tabindex=\"13\" type=\"text\" size=\"16\" name=\"calldate\" id=\"dZ2\"><br>\n";	
	echo "						</fieldset>\n";
	
	echo "						<fieldset class=\"leadform\">\n";
    echo "						<legend><b>System</b></legend>\n";
	echo "							<label>Assigned</label><br>\n";
	echo "							<select tabindex=\"14\" name=\"sid\">\n";

	if ($rowB['networkaccess'] >= 5)
	{
		echo "								<option class=\"fontblack\" value=\"0\">Select...</option>\n";
	}
	
	while ($rowBa = mssql_fetch_array($resBa))
	{
		
		if ($rowBa['securityid']==$_SESSION['securityid'] && $rowB['officeid']==$_SESSION['officeid'])
		{
			echo "							<option value=\"".$rowBa['securityid']."\" SELECTED>".$rowBa['lname'].", ".$rowBa['fname']."</option>\n";
		}
		else
		{
			echo "							<option value=\"".$rowBa['securityid']."\">".$rowBa['lname'].", ".$rowBa['fname']."</option>\n";
		}
	}
	
	echo "							</select><br>\n";
	
	echo "						</fieldset>\n";
	echo "  					<button tabindex=\"16\" style=\"float:right;margin-top:5px;\" type=\"submit\"><img src=\"images/save.gif\"></button>\n";
	echo "                  	</form>\n";
    echo "  				</td>\n";
	echo "  			</tr>\n";
	echo "  		</table>\n";
	echo "      </td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
}

function cform_view_NET($tcid)
{
	unset($_SESSION['ifcid']); // Security Setting for embedded frames and AJAX
	$acclist=explode(",",$_SESSION['aid']);

	if (empty($_REQUEST['tranid']))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error occured.\n";
		exit;
	}

	if (isset($tcid) && $tcid!=0)
	{
		$qry0 = "SELECT cnid FROM cinfo_net WHERE oid='".$_SESSION['officeid']."' AND cnid='".$tcid."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);

		$cnid=$row0['cnid'];	
	}
	else
	{
		$cnid=$_REQUEST['cnid'];
	}

	if (empty($cnid))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> You must provide a Valid ID number.\n";
		exit;
	}
	
	$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	$qryAa = "SELECT officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
    $rowAa = mssql_fetch_array($resAa);
	$nrowsAa = mssql_num_rows($resAa);
	
	$qryB = "SELECT securityid,emailtemplateaccess,networkaccess FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	//$qryBa = "SELECT securityid,fname,lname,sidm,substring(slevel,13,1) as sslevel,networkaccess FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY substring(slevel,13,13) desc, lname ASC;";
	if ($rowB['networkaccess'] == 1)
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE securityid=".$_SESSION['securityid'].";";
	}
	elseif ($rowB['networkaccess'] <= 5)
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE securityid=".$_SESSION['securityid']." OR sidm=".$_SESSION['securityid']." ORDER BY substring(slevel,13,1) desc,lname ASC;";
	}
	else
	{
		$qryBa = "SELECT securityid,fname,lname,substring(slevel,13,1) as sslevel,networkaccess,sidm FROM security WHERE officeid=".$_SESSION['officeid']." AND substring(slevel,13,1) > 0 ORDER BY substring(slevel,13,1) desc,lname ASC;";
	}
	
	$resBa = mssql_query($qryBa);
	$nrowsBa = mssql_num_rows($resBa);

	$qryC = "SELECT stax,enest,encon FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);
	
	$qryCa = "SELECT * FROM jest..states ORDER BY abrev ASC;";
	$resCa = mssql_query($qryCa);
	$nrowsCa = mssql_num_rows($resCa);
	
	$qryD = "SELECT * FROM jest..nettypes ORDER BY netname ASC;";
	$resD = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);

	$qryF = "SELECT * FROM cinfo_net WHERE oid='".$_SESSION['officeid']."' AND cnid='".$cnid."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	$qryI = "SELECT securityid,fname,lname,sidm FROM security WHERE officeid=".$_SESSION['officeid']." AND securityid='".$rowF['sid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryJ = "SELECT securityid,fname,lname,sidm,networkaccess FROM security WHERE officeid=".$_SESSION['officeid']." AND securityid='".$rowF['mid']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);

	$qryL = "SELECT chid FROM chistory_net WHERE oid=".$_SESSION['officeid']." AND cnid=".$cnid." ORDER BY mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);

	$adate = date("m/d/Y g:i A", strtotime($rowF['adate']));
	
	if (isset($rowF['udate']) && strtotime($rowF['udate']) > strtotime('1/1/2000'))
	{
		$udate = date("m/d/Y g:i A", strtotime($rowF['udate']));
	}
	else
	{
		$udate='';
	}
	
	if ($rowB['networkaccess'] < 9 && !in_array($rowI['securityid'],$acclist))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font><br>You do not have appropriate Access to view this Information.\n";
		exit;
	}
	
	$_SESSION['ifcid']=$rowF['cnid'];
	$cmaplink	=maplink($rowF['caddr1'],$rowF['ccity'],$rowF['cstate'],$rowF['czip1']);
	$tranid		=md5(time().".".$cnid).".".$_SESSION['securityid'];
	$hlpnd		=1;
	
	echo "<table width=\"500px\" align=\"center\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table width=\"100%\" align=\"center\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td>\n";
	echo "      				<form name=\"cviewnet1\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"network\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"edit_net\">\n";
	echo "						<input type=\"hidden\" name=\"cnid\" value=\"".$rowF['cnid']."\">\n";
	echo "						<input type=\"hidden\" name=\"tranid\" value=\"".$tranid."\">\n";
	echo "						<input type=\"hidden\" name=\"sid\" value=\"".$rowF['sid']."\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	
	echo "									<fieldset class=\"leadform\">\n";
	echo "									<legend><b>Company</b></legend>\n";
	echo "										<label>Type</label><br>\n";
	echo "										<select tabindex=\"7\" name=\"cptype\">\n";
	echo "											<option value=\"0\">Select...</option>\n";
	
	while ($rowD=mssql_fetch_array($resD))
	{
		if ($rowD['ntid']==$rowF['cptype'])
		{
			echo "										<option value=\"".$rowD['ntid']."\" SELECTED>".$rowD['netname']."</option>\n";
		}
		else
		{
			echo "										<option value=\"".$rowD['ntid']."\">".$rowD['netname']."</option>\n";
		}
	}
	
	echo "										</select><br>\n";
    echo "										<label>Name</label><br>\n";
    echo "										<input tabindex=\"2\" type=\"text\" size=\"40\" name=\"cpname\" value=\"".trim($rowF['cpname'])."\"><br>\n";	
	echo "										<label>Address</label><br>\n";
	echo "										<input tabindex=\"3\" type=\"text\" size=\"40\" name=\"caddr1\" value=\"".trim($rowF['caddr1'])."\"><br>\n";	
	echo "										<label>City</label><br>\n";
	echo "										<input tabindex=\"4\" type=\"text\" size=\"40\" name=\"ccity\" value=\"".trim($rowF['ccity'])."\"><br>\n";	
	echo "										<label>State</label><br>\n";
	echo "										<select tabindex=\"3\" name=\"cstate\">\n";
	echo "										<option></option>\n";
	
	while ($rowCa=mssql_fetch_array($resCa))
	{
		if ($rowF['cstate']==$rowCa['abrev'])
		{
			echo "										<option value=\"".$rowCa['abrev']."\" SELECTED>".$rowCa['abrev']."</option>\n";
		}
		else
		{
			echo "										<option value=\"".$rowCa['abrev']."\">".$rowCa['abrev']."</option>\n";
		}
	}
	
	echo "										</select><br>\n";	
	echo "										<label>Zip</label><br>\n";
	echo "										<input tabindex=\"6\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".trim($rowF['czip1'])."\"> ".$cmaplink."<br>\n";	
	echo "									</fieldset>\n";
	
	echo "									<fieldset class=\"leadform\">\n";
	echo "									<legend><b>Contact Info</b></legend>\n";	
	echo "										<label>Primary Contact</label><br>\n";
	echo "										<input tabindex=\"8\" type=\"text\" size=\"40\" name=\"cfullname\" value=\"".trim($rowF['cfullname'])."\"><br>\n";
	echo "										<label>Email</label><br>\n";
	echo "										<input tabindex=\"12\" type=\"text\" name=\"cemail\" size=\"30\" value=\"".trim($rowF['cemail'])."\"><br>\n";
	
	if (isset($rowF['cwork']) && strlen($rowF['cwork']) > 3)
	{
		echo "										<label>Phone</label><br>\n";
		echo "										<input tabindex=\"9\" type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),6,4)."\"><br>\n";	
	}
	else
	{
		echo "										<label>Phone</label><br>\n";
		echo "										<input tabindex=\"9\" type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\"><br>\n";
	}
	
	if (isset($rowF['ccell']) && strlen($rowF['ccell']) > 3)
	{
		echo "										<label>Cell</label><br>\n";
		echo "										<input tabindex=\"10\" type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),6,4)."\"><br>\n";
	}
	else
	{
		echo "										<label>Cell</label><br>\n";
		echo "										<input tabindex=\"10\" type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\"><br>\n";
	}
	
	if (isset($rowF['cfax']) && strlen($rowF['cfax']) > 3)
	{
		echo "										<label>Fax</label><br>\n";
		echo "										<input tabindex=\"11\" type=\"text\" size=\"13\" maxlength=\"20\" name=\"cfax\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cfax'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cfax'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cfax'])),6,4)."\"><br>\n";	
	}
	else
	{
		echo "										<label>Fax</label><br>\n";
		echo "										<input tabindex=\"11\" type=\"text\" size=\"13\" maxlength=\"20\" name=\"cfax\"><br>\n";
	}
	
	echo "									</fieldset>\n";
	
	echo "									<fieldset class=\"leadform\">\n";
	echo "									<legend><b>New Comment</b></legend>\n";
	echo "										<textarea tabindex=\"1\" name=\"ccomments\" id=\"ccomments\" rows=\"2\" cols=\"60\"></textarea><br>\n";
	echo "									</fieldset>\n";
	
	echo "									<fieldset class=\"leadform\">\n";
	echo "									<legend><b>Comment History</b></legend>\n";
	echo "										<iframe src=\"subs/comments_net.php\" frameborder=\"0\" width=\"100%\"></iframe>\n";
	echo "									</fieldset>\n";

	echo "								</td>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	
	echo "									<fieldset class=\"leadform\">\n";
	echo "									<legend><b>Dates</b></legend>\n";
	echo "											<label>Added</label><br>\n";
	echo "											<div>".$adate."</div>\n";	
	echo "											<label>Updated</label><br>\n";
	echo "											<div>".$udate."</div>\n";
	
	if (isset($rowF['contactdate']) && valid_date(date('m/d/Y',strtotime($rowF['contactdate']))) && strtotime($rowF['contactdate']) >= strtotime('1/1/2005'))
	{
		echo "											<label>Last Contact</label><br>\n";
		echo "											<input tabindex=\"13\" type=\"text\" size=\"16\" name=\"contactdate\" id=\"d1\" value=\"".date('m/d/Y',strtotime($rowF['contactdate']))."\"><br>\n";
	}
	else
	{
		echo "											<label>Last Contact</label><br>\n";
		echo "											<input tabindex=\"13\" type=\"text\" size=\"16\" name=\"contactdate\" id=\"d1\"><br>\n";
	}
	
	if (isset($rowF['calldate']) && valid_date(date('m/d/Y',strtotime($rowF['calldate']))) && strtotime($rowF['calldate']) >= strtotime('1/1/2005'))
	{
		echo "											<label>Callback</label><br>\n";
		echo "											<input tabindex=\"14\" type=\"text\" size=\"16\" name=\"calldate\" id=\"dZ2\" value=\"".date('m/d/Y',strtotime($rowF['calldate']))."\"><br>\n";
	}
	else
	{
		echo "											<label>Callback</label><br>\n";
		echo "											<input tabindex=\"14\" type=\"text\" size=\"16\" name=\"calldate\" id=\"dZ2\"><br>\n";
	}
	
	echo "									</fieldset>\n";
	
	echo "									<fieldset class=\"leadform\">\n";
	echo "									<legend><b>System</b></legend>\n";
	
	echo "											<label>Status</label><br>\n";
	echo "											<select tabindex=\"16\" name=\"active\">\n";

	if ($rowF['active']==0)
	{
		echo "											<option class=\"fontblack\" value=\"0\" SELECTED>Active</option>\n";
		echo "											<option class=\"fontred\" value=\"1\">Inactive</option>\n";
	}
	else
	{
		echo "											<option class=\"fontblack\" value=\"0\">Active</option>\n";
		echo "											<option class=\"fontred\" value=\"1\" SELECTED>Inactive</option>\n";
	}
	
	echo "											</select><br>\n";
	
	echo "											<label>Assigned</label><br>\n";
	echo "											<select tabindex=\"17\" name=\"sid\">\n";
	
	while ($rowBa=mssql_fetch_array($resBa))
	{
		if ($rowBa['sslevel'] > 0)
		{
			$ostyle='fontblack';
		}
		else
		{
			$ostyle='fontred';
		}
		
		if ($rowBa['securityid']==$rowF['sid'])
		{
			echo "											<option class=\"".$ostyle."\" value=\"".$rowBa['securityid']."\" SELECTED>".$rowBa['lname'].", ".$rowBa['fname']."</option>\n";
		}
		else
		{
			echo "											<option class=\"".$ostyle."\" value=\"".$rowBa['securityid']."\">".$rowBa['lname'].", ".$rowBa['fname']."</option>\n";
		}
	}
	
	echo "											</select><br>\n";
	echo "									</fieldset>\n";
	echo "									<button tabindex=\"16\" style=\"float:right;margin-top:5px;\" type=\"submit\"><img src=\"images/save.gif\"></button>\n";
	echo "									</form>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	
	$qryXX	= "UPDATE jest..cinfo_net SET viewed=getdate(), viewedby=".$_SESSION['securityid']." WHERE cnid='".$cnid."';";
	$resXX	= mssql_query($qryXX);
}


function cform_save_NET()
{
	error_reporting(E_ALL);
	
	if (!isset($_REQUEST['tranid']))
	{
		echo 'Transition Error Occurred';
		exit;
	}
	
	$qryA  = "SELECT cnid FROM jest..cinfo_net WHERE oid='".$_SESSION['officeid']."' and tranid='".trim($_REQUEST['tranid'])."';";
	$resA  = mssql_query($qryA);
	$rowA   = mssql_fetch_array($resA);
	$nrowA = mssql_num_rows($resA);
	
	if ($nrowA==0)
	{
		$qryC   = "INSERT INTO jest..cinfo_net (sid,oid,srcoid,cpname,cfullname,cptype,caddr1,ccity,cstate,czip1,cwork,ccell,cfax,cemail,tranid ";
		
		if (isset($_REQUEST['contactdate']) && valid_date(trim($_REQUEST['contactdate'])))
		{
			$qryC  .= ",contactdate ";
		}

		if (isset($_REQUEST['calldate']) && valid_date(trim($_REQUEST['calldate'])))
		{
			$qryC  .= ",calldate ";
		}
		
		$qryC  .= ") VALUES (";
		$qryC  .= "'".$_REQUEST['sid']."', ";
		$qryC  .= "'".$_SESSION['officeid']."', ";
		$qryC  .= "'".$_SESSION['officeid']."', ";
		$qryC  .= "'".htmlspecialchars(ucwords(trim($_REQUEST['cpname'])),ENT_QUOTES)."', ";
		$qryC  .= "'".htmlspecialchars(ucwords(trim($_REQUEST['cfullname'])),ENT_QUOTES)."', ";
		$qryC  .= "'".$_REQUEST['cptype']."', ";
		$qryC  .= "'".htmlspecialchars(trim($_REQUEST['caddr1']),ENT_QUOTES)."', ";
		$qryC  .= "'".htmlspecialchars($_REQUEST['ccity'],ENT_QUOTES)."', ";
		$qryC  .= "'".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
		$qryC  .= "'".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
		$qryC  .= "'".htmlspecialchars($_REQUEST['cwork'],ENT_QUOTES)."', ";
		$qryC  .= "'".htmlspecialchars($_REQUEST['ccell'],ENT_QUOTES)."', ";
		$qryC  .= "'".htmlspecialchars($_REQUEST['cfax'],ENT_QUOTES)."', ";
		$qryC  .= "'".replacequote($_REQUEST['cemail'])."', ";
		$qryC  .= "'".$_REQUEST['tranid']."' ";
		
		if (isset($_REQUEST['contactdate']) && valid_date(trim($_REQUEST['contactdate'])))
		{
			$qryC  .= ",'".trim($_REQUEST['contactdate'])."' ";
		}

		if (isset($_REQUEST['calldate']) && valid_date(trim($_REQUEST['calldate'])))
		{
			$qryC  .= ",'".trim($_REQUEST['calldate'])."' ";
		}
		
		$qryC  .= "); ";
		$qryC  .= "SELECT @@IDENTITY;";
		
		//echo $qryC.'<br>';
		//exit;
		
		$resC   = mssql_query($qryC);
		$rowC   = mssql_fetch_row($resC);
		
		if (isset($rowC[0]) && $rowC[0] != 0)
		{
			if (!empty($_REQUEST['ccomments']) && strlen($_REQUEST['ccomments']) >= 2)
			{
				$qryB   = "INSERT INTO jest..chistory_net (cnid,oid,sid,act,tranid,mtext ";
				
				if (isset($_REQUEST['contactdate']) && valid_date(trim($_REQUEST['contactdate'])))
				{
					$qryB  .= ",contactdate ";
				}

				if (isset($_REQUEST['calldate']) && valid_date(trim($_REQUEST['calldate'])))
				{
					$qryB  .= ",calldate ";
				}
				
				$qryB  .= ") VALUES (";
				
				$qryB  .= "'".$rowC[0]."','".$_SESSION['officeid']."','".$_SESSION['securityid']."','Network','".$_REQUEST['tranid']."' ";
				
				if (isset($_REQUEST['ccomments']) && strlen($_REQUEST['ccomments']) >= 2)
				{
					$qryB  .= ",'".htmlspecialchars($_REQUEST['ccomments'],ENT_QUOTES)."' ";
				}
				else
				{
					$qryB  .= ",'Network Info Added' ";
				}
				
				if (isset($_REQUEST['contactdate']) && valid_date(trim($_REQUEST['contactdate'])))
				{
					$qryB  .= ",'".trim($_REQUEST['contactdate'])."' ";
				}

				if (isset($_REQUEST['calldate']) && valid_date(trim($_REQUEST['calldate'])))
				{
					$qryB  .= ",'".trim($_REQUEST['calldate'])."' ";
				}
				
				$qryB  .= ")";
				$resB  = mssql_query($qryB);
			}
			
			cform_view_NET($rowC[0]);
		}
		else
		{
			echo "<b><font color=\"red\">Error!</font> <br><br>The information you attempted to submit did not save. Click back, check your entry and resubmit</b>";
			exit;
		}
	}
	else
	{
		echo "<center><b><font color=\"red\">Error!</font></b> The information has already been submittted</center><br>";
		cform_view_NET($rowA['cnid']);
	}
}

function cform_edit_NET()
{
	
	//display_array($_REQUEST);
	//exit;
	
	if ($_SESSION['securityid']==26)
	{
		error_reporting(E_ALL);
	    ini_set('display_errors','On');
	}
	
	$acclist=explode(",",$_SESSION['aid']);

	$qry = "SELECT * FROM cinfo_net WHERE oid='".$_SESSION['officeid']."' AND cnid='".$_REQUEST['cnid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry0 = "SELECT securityid,sidm,networkaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$qry4 = "SELECT securityid,sidm,networkaccess FROM security WHERE securityid='".$row['sid']."';";
	$res4 = mssql_query($qry4);
	$row4 = mssql_fetch_array($res4);
	
	if (!in_array($row['sid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to Update this Lead</b>";
		exit;
	}
	else
	{		
		$qryA   = "UPDATE cinfo_net SET ";
		$qryA  .= "	cpname='".htmlspecialchars(ucwords($_REQUEST['cpname']),ENT_QUOTES)."', ";
		$qryA  .= "	cfullname='".htmlspecialchars(ucwords($_REQUEST['cfullname']),ENT_QUOTES)."', ";
		
		if ($row0['networkaccess'] > 1 && $row['sid']!=$_REQUEST['sid'])
		{
			$qryA  .= "	sid='".$_REQUEST['sid']."', ";
		}
		
		$qryA  .= "	cptype='".$_REQUEST['cptype']."', ";
		$qryA  .= "	caddr1='".htmlspecialchars(ucwords($_REQUEST['caddr1']),ENT_QUOTES)."', ";
		$qryA  .= "	ccity='".htmlspecialchars(ucwords($_REQUEST['ccity']),ENT_QUOTES)."', ";
		$qryA  .= "	cstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
		$qryA  .= "	czip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
		$qryA  .= "	cwork='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['cwork']),ENT_QUOTES)."', ";
		$qryA  .= "	ccell='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['ccell']),ENT_QUOTES)."', ";
		$qryA  .= "	cfax='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['cfax']),ENT_QUOTES)."', ";
		$qryA  .= "	active='".$_REQUEST['active']."', ";
		
		if (isset($_REQUEST['contactdate']) && valid_date(date('m/d/Y',strtotime($_REQUEST['contactdate']))))
		{
			$qryA  .= "	contactdate='".$_REQUEST['contactdate']."', ";
		}
		
		if (isset($_REQUEST['calldate']) && valid_date(date('m/d/Y',strtotime($_REQUEST['calldate']))))
		{
			$qryA  .= "	calldate='".$_REQUEST['calldate']."', ";
		}
		
		$qryA  .= "	cemail='".replacequote($_REQUEST['cemail'])."', ";
		$qryA  .= "	udate=getdate() ";
		$qryA  .= "WHERE oid='".$_SESSION['officeid']."' AND cnid='".$_REQUEST['cnid']."';";
		$resA  = mssql_query($qryA);
		
		// Adds Comment
		if (isset($_REQUEST['ccomments']) && strlen($_REQUEST['ccomments']) >= 2)
		{
			$qryA1 = "SELECT chid FROM jest..chistory_net WHERE cnid='".$_REQUEST['cnid']."' and tranid='".$_REQUEST['tranid']."';";
			$resA1 = mssql_query($qryA1);
			$nrowA1 = mssql_num_rows($resA1);
			
			if ($nrowA1 == 0)
			{
				$qryA2  = "INSERT INTO jest..chistory_net (oid,sid,cnid,tranid,mtext ";
				
				if (isset($_REQUEST['contactdate']) && valid_date(date('m/d/Y',strtotime($_REQUEST['contactdate']))))
				{
					$qryA2  .= ",contactdate ";
				}

				if (isset($_REQUEST['calldate']) && valid_date(date('m/d/Y',strtotime($_REQUEST['calldate']))))
				{
					$qryA2  .= ",calldate ";
				}
				
				$qryA2 .= ") VALUES (";
				$qryA2 .= "'".$_SESSION['officeid']."','".$_SESSION['securityid']."','".$_REQUEST['cnid']."','".$_REQUEST['tranid']."' ";
				
				
				$qryA2 .= ",'".htmlspecialchars(removequote($_REQUEST['ccomments']),ENT_QUOTES)."' ";
				
				if (isset($_REQUEST['contactdate']) && valid_date(date('m/d/Y',strtotime($_REQUEST['contactdate']))))
				{
					$qryA2  .= ",'".trim($_REQUEST['contactdate'])."' ";
				}

				if (isset($_REQUEST['calldate']) && valid_date(date('m/d/Y',strtotime($_REQUEST['calldate']))))
				{
					$qryA2  .= ",'".trim($_REQUEST['calldate'])."' ";
				}
				
				$qryA2 .= ");";
				$resA2  = mssql_query($qryA2);
				
				//echo $qryA2.'<br>';
			}
		}

		cform_view_NET($_REQUEST['cnid']);
	}
}

function selectemailtemplate_NET($oid,$sid,$cid,$ttid)
{
	$qryET = "SELECT * FROM EmailTemplate WHERE active <= ".$_SESSION['emailtemplates']." AND active >= 1 and ttype=".$ttid." ORDER BY name ASC;";	
	$resET = mssql_query($qryET);
	$nrowET= mssql_num_rows($resET);

	if ($nrowET > 0)
	{
		echo "			<label>Email Template</label>\n";
		echo "			<select class=\"jform\" name=\"etid\" title=\"Selecting an Email Template will send an Email to the Customer upon update.\">\n";
		echo "				<option value=\"0\">None</option>\n";
		
		while ($rowET = mssql_fetch_array($resET))
		{
			if ($rowET['active']==0)
			{
				echo "				<option class=\"fontred\"value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
			else
			{
				echo "				<option value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
		}
		
		echo "				</select>\n";
		echo "				<img id=\"empreview\" src=\"images/email_open.png\" onClick=\"displayPopup('etid','".$oid."','".$sid."','".$cid."');\" title=\"Select an Email Template then click to Preview\"><br>\n";

	}
}

?>