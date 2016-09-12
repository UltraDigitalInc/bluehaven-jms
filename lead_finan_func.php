<?php
//error_reporting(E_ALL);

function basematrix()
{
	if ($_SESSION['call']=="list")
	{
		listleads();
	}
	elseif ($_SESSION['call']=="appts")
	{
		apptleads_mo();
	}
	elseif ($_SESSION['call']=="new")
	{
		cform();
	}
	elseif ($_SESSION['call']=="add")
	{
		cform_add();
	}
	elseif ($_SESSION['call']=="view")
	{
		cform_view();
	}
	elseif ($_SESSION['call']=="edit")
	{
		cform_edit();
	}
	elseif ($_SESSION['call']=="delete")
	{
		cform_delete();
	}
	elseif ($_SESSION['call']=="search")
	{
		//echo $_SESSION['call']."<br>";
		lead_search();
	}
	elseif ($_SESSION['call']=="sales_search")
	{
		//echo $_SESSION['call']."<br>";
		sales_search();
	}
	elseif ($_SESSION['call']=="search_results")
	{
		listleads();
	}
	elseif ($_SESSION['call']=="showcalendar")
	{
		showMonth_full();
	}
	elseif ($_SESSION['call']=="showday_expanded")
	{
		showMonth_full();
		//showDay_expanded();
	}
	elseif ($_SESSION['call']=="chistory_add")
	{
		chistory_add();
	}
	elseif ($_SESSION['call']=="chistory")
	{
		//echo "HISTORY";
		chistory_list();
	}
	elseif ($_SESSION['call']=="set_digdate")
	{
		set_digdate();
	}
	elseif ($_SESSION['call']=="set_clsdate")
	{
		set_clsdate();
	}
	elseif ($_SESSION['call']=="set_condate")
	{
		set_condate();
	}
	elseif ($_SESSION['call']=="add_fin_detail")
	{
		finan_form_add();
	}
	elseif ($_SESSION['call']=="add_fin_detail2")
	{
		finan_form_add2();
	}
	elseif ($_SESSION['call']=="view_fin_detail")
	{
		finan_form_view();
	}
	elseif ($_SESSION['call']=="updt_fin_detail")
	{
		finan_form_updt();
	}
	elseif ($_SESSION['call']=="finan_status_update")
	{
		finan_status_update();
	}
	elseif ($_SESSION['call']=="exports")
	{
		lead_export();
	}
}

function add_finan_cust($oid,$orig_oid,$cid,$sid,$uid,$closer)
{
	//echo "Adding WinFin<br>";
	error_reporting(E_ALL);
	$nsecid		=0;
	
	if (isset($_POST['finansrc']) && $_POST['finansrc']!=1)
	{
		$finan_src	=$_POST['finansrc']; // Submitted
	}
	else
	{
		$finan_src	=4; // BH Finance	
	}
	
	$qry  	= "SELECT cid FROM cinfo WHERE cid='".$cid."';";
	$res  	= mssql_query($qry);
	$row  	= mssql_fetch_array($res);
	$nrow 	= mssql_num_rows($res);
	
	$qry0a  	= "SELECT cid FROM tfinan_detail WHERE cid='".$cid."';";
	$res0a  	= mssql_query($qry0a);
	$nrow0a 	= mssql_num_rows($res0a);
	//echo $qry."<br>";
	
	if ($nrow==1 && $nrow0a==0)
	{
		$qry0  	= "SELECT name,gm,am FROM offices WHERE officeid='".$orig_oid."';";
		$res0  	= mssql_query($qry0);
		$row0  	= mssql_fetch_array($res0);
		
		$ctext  = "System Message - Finance Office Assigned: ".$row0['name'];		

		if ($row0['gm']!=0)
		{
			$nsecid=$row0['gm'];
		}
		else
		{
			$nsecid=$row0['am'];
		}
		
		$qry1   = "UPDATE cinfo SET finan_from='".$orig_oid."',finan_sec='".$nsecid."',finan_src='".$finan_src."',finan_date=getdate() WHERE cid=".$cid.";";
		$res1   = mssql_query($qry1);
		//echo $qry1."<br>---<br>";
		$qry1a  = "INSERT INTO tfinan_detail (cid,officeid,finan_from,financlose,recdate,uid,assigned) VALUES ('".$cid."','".$oid."','".$orig_oid."',0,getdate(),'".$uid."','".$closer."');";
		$res1a  = mssql_query($qry1a);
		//echo $qry1a."<br>---<br>";

		$qry1b   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
		$qry1b  .= "VALUES ";
		$qry1b  .= "('".$cid."','".$orig_oid."','".$_SESSION['securityid']."','leads','".$ctext."','".$uid."')";
		$res1b  = mssql_query($qry1b);
		//echo $qry1b."<br>---<br>";
	}
}

function lead_search()
{
	error_reporting(E_ALL);
	unset($_SESSION['tqry']);
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
	
	//echo "SUB";
	
	$srch_hlp="<b>Financing Contact Search</b><br><br>";
	$srch_hlp.="<ul><b>Field Descriptons</b><br>";
	$srch_hlp.="<li><b>Search By:</b><br>Select the data field you wish to search via the Drop down box, then type in the parameters in the next Box.</li>";
	$srch_hlp.="<li><b>Offices:</b><br>Selecting an Office will restrict the search to that office.</li>";
	$srch_hlp.="<li><b>Sort By:</b><br>Selects the order which the Results will be returned.</li>";
	$srch_hlp.="<li><b>Date Range:</b><br>The Date Range fields allow the search to be narrowed to the Date Range input. This is an optional parameter.</li>";
	$srch_hlp.="<li><b>*NOTE*:</b><br>The <b>Search by</b> field is optional if the Date Range fields are filled in.</li>";
	$srch_hlp.="</ul>";
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
	
	$qry2 = "SELECT * FROM offices WHERE finan_from='".$_SESSION['officeid']."' order by name ASC;";
	$res2 = mssql_query($qry2);
	$nrow2= mssql_num_rows($res2);
	
	//echo $qry2."<br>";
	
	$qry3 = "SELECT officeid FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
	
	$qry4 = "SELECT * FROM alt_security_levels WHERE sid='".$_SESSION['securityid']."';";
	$res4 = mssql_query($qry4);
	$nrow4= mssql_num_rows($res4);
	
	if ($nrow4 > 0)
	{
		while ($row4 = mssql_fetch_array($res4))
		{
			$altoidacc[$row4['oid']]=explode(",",$row4['slevel']);
		}
	}
	
	$qry5  = "select ";
	$qry5 .= "	s.officeid, ";
	$qry5 .= "	s.securityid, ";
	$qry5 .= "	s.lname, ";
	$qry5 .= "	s.fname, ";
	$qry5 .= "	s.slevel, ";
	$qry5 .= "	o.officeid, ";
	$qry5 .= "	o.name ";
	$qry5 .= "from ";
	$qry5 .= "	offices as o ";
	$qry5 .= "inner join ";
	$qry5 .= "	security as s ";
	$qry5 .= "on  ";
	$qry5 .= "	o.officeid=s.officeid ";
	$qry5 .= "where ";
	$qry5 .= "	o.finan_off=1 ";
	$qry5 .= "	and substring(s.slevel,13,13) > 0 ";
	$qry5 .= "	and o.officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] < 6)
	{
		$qry5 .= "	and s.securityid='".$_SESSION['securityid']."' ";
	}
	
	$qry5 .= "order by ";
	$qry5 .= "	o.name asc,substring(s.slevel,13,13) desc,s.lname asc;";
	$res5 = mssql_query($qry5);
	$nrow5= mssql_num_rows($res5);

	$acclist		=explode(",",$_SESSION['aid']);

	//show_array_vars($altoidacc);

	echo "<table align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td class=\"ltgray_und\" align=\"center\"><b>Financing Contact Search</b> <a href=\"#\" class=\"JMStooltip\" title=\"".$srch_hlp."\"><img src=\"images/help.png\"></a></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "         								<form name=\"tsearch1\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"left\"><b>Search By</b></td><td></td></tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"top\">\n";
	echo "                                    <select name=\"field\" tabindex=\"1\" title=\"Select the Data Field to Search. This field can perform a partial match starting with the first character input\">\n";
	echo "                                    	<option value=\"clname\" SELECTED>Last Name</option>\n";
	echo "                                    	<option value=\"saddr1\">Site Addr</option>\n";
	//echo "                                    	<option value=\"caddr1\">Customer Addr</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\"><input tabindex=\"2\" class=\"bboxl\" type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>Office</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "												<select name=\"oid\" tabindex=\"3\" title=\"Select an Office to narrow the search\">\n";
	echo "													<option value=\"0\">All Offices</option>\n";
	echo "                                 					<option value=\"0\">----------------------</option>\n";
	
	while ($row2=mssql_fetch_array($res2))
	{
		if ($row2['officeid']==$row3['officeid'])
		{
			echo "													<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
		}
		else
		{
			echo "													<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
		}
	}
	
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              			<td align=\"right\"><b>Assigned</b></td>\n";
	echo "                              			<td align=\"left\">\n";
	
	if ($_SESSION['llev'] >=5)
	{
		echo "												<select name=\"assigned\" tabindex=\"3\" title=\"Select an Office to narrow the search\">\n";
		echo "													<option value=\"0\">All Fin Reps</option>\n";
		echo "                                 					<option value=\"0\">----------------------</option>\n";
		
		$x=0;
		while ($row5=mssql_fetch_array($res5))
		{
			if ($x==0 || $x!=$row5['officeid'])
			{
				echo "      							<optgroup class=\"plain\" label=\"".$row5['name']."\">\n";
			}
			
			if ($_SESSION['officeid']==$row5['officeid'])
			{
				echo "													<option class=\"fontblue\" value=\"".$row5['securityid']."\">".$row5['fname'].", ".$row5['lname']."</option>\n";
			}
			else
			{
				echo "													<option value=\"".$row5['securityid']."\">".$row5['lname'].", ".$row5['fname']."</option>\n";
			}
			$x=$row5['officeid'];
		}
		
		echo "												</select>\n";
	}
	else
	{
		$row5=mssql_fetch_array($res5);
		echo $row5['lname'] .", " . $row5['fname'] . " - " . $row5['name'];
		echo "<input type=\"hidden\" name=\"assigned\" value=\"".$row5['securityid']."\">\n";
	}
	
	echo "											</td>\n";
	echo "										</tr>\n";
	
	//if ($row3['officeid']==89)
	//{
		echo "										<tr>\n";
		echo "                              	<td align=\"right\"><b>Finance Source</b></td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"finansrc\" tabindex=\"4\" title=\"Set the Finance Source to narrow the search\">\n";
		echo "                                    	<option value=\"0\" SELECTED>Any</option>\n";
		echo "                                    	<option value=\"1\">Winners</option>\n";
		echo "                                    	<option value=\"2\">Cust Finan</option>\n";
		echo "                                    	<option value=\"3\">Cash</option>\n";
		echo "                                    	<option value=\"4\">BH Finance</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
	/*}
	else
	{
		echo "											<input type=\"hidden\" name=\"finansrc\" value=\"1\">\n";
	}*/
	
	echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>Date Field</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"dfield\" tabindex=\"5\" title=\"Set the Date Field to be Searched\">\n";
	echo "                                    	<option value=\"f.recdate\">Finance Assigned</option>\n";
	echo "                                    	<option value=\"f.frecdate\" SELECTED>Finance Received</option>\n";
	echo "                                    	<option value=\"f.cbddate\">Call Back</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>Sort by</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"order\" tabindex=\"5\" title=\"Set the Sort Order of the Search\">\n";
	echo "                                    	<option value=\"f.recdate\">Finance Assigned</option>\n";
	echo "                                    	<option value=\"f.frecdate\" SELECTED>Finance Received</option>\n";
	echo "                                    	<option value=\"f.cbddate\">Call Back</option>\n";
	//echo "                                    	<option value=\"f.finan_date\">Finance Date</option>\n";
	echo "                                    	<option value=\"c.clname\">Last Name</option>\n";
	echo "                                    	<option value=\"c.szip1\">Site Zip Code</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>Order</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"ascdesc\" tabindex=\"5\" title=\"Set the Sort Order of the Search\">\n";
	echo "                                    	<option value=\"asc\">Ascending</option>\n";
	echo "                                    	<option value=\"desc\" selected>Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"top\"><b>Date Range:</b></td>\n";
	echo "                                 <td align=\"left\">\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\" tabindex=\"6\" title=\"Begin Date\"><br>\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\" tabindex=\"8\" title=\"End Date\">\n";
	echo "											</td>";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>Show Closed</b></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"showclosed\" tabindex=\"10\" title=\"Determines if Closed Deals are shown\">\n";
	echo "                                    	<option value=\"0\">Yes</option>\n";
	echo "                                    	<option value=\"1\" selected>No</option>\n";
	echo "                                    	<option value=\"2\">Only</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"center\" valign=\"bottom\"></td>\n";
	echo "                                 <td><input class=\"buttondkgrypnl80\" tabindex=\"11\" type=\"submit\" value=\"Search\" title=\"Click Here to Submit the Search Request\"></td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function sales_search()
{
	error_reporting(E_ALL);
	unset($_SESSION['tqry']);
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
	
	$qry2 = "SELECT * FROM offices WHERE finan_from='".$_SESSION['officeid']."' and finan_off!=1 order by name ASC;";
	$res2 = mssql_query($qry2);
	$nrow2= mssql_num_rows($res2);
	
	//echo $qry2."<br>";
	
	$qry3 = "SELECT officeid FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
	
	$qry4 = "SELECT * FROM alt_security_levels WHERE sid='".$_SESSION['securityid']."';";
	$res4 = mssql_query($qry4);
	$nrow4= mssql_num_rows($res4);
	
	if ($nrow4 > 0)
	{
		while ($row4 = mssql_fetch_array($res4))
		{
			$altoidacc[$row4['oid']]=explode(",",$row4['slevel']);
		}
	}
	
	$qry5  = "select ";
	$qry5 .= "	s.officeid, ";
	$qry5 .= "	s.securityid, ";
	$qry5 .= "	s.lname, ";
	$qry5 .= "	s.fname, ";
	$qry5 .= "	s.slevel, ";
	$qry5 .= "	o.officeid, ";
	$qry5 .= "	o.name ";
	$qry5 .= "from ";
	$qry5 .= "	offices as o ";
	$qry5 .= "inner join ";
	$qry5 .= "	security as s ";
	$qry5 .= "on  ";
	$qry5 .= "	o.officeid=s.officeid ";
	$qry5 .= "where ";
	$qry5 .= "	o.finan_off=1 ";
	$qry5 .= "	and substring(s.slevel,13,13) > 0 ";
	$qry5 .= "	and o.officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] < 6)
	{
		$qry5 .= "	and s.securityid='".$_SESSION['securityid']."' ";
	}
	
	$qry5 .= "order by ";
	$qry5 .= "	o.name asc,substring(s.slevel,13,13) desc,s.lname asc;";
	$res5 = mssql_query($qry5);
	$nrow5= mssql_num_rows($res5);

	$acclist		=explode(",",$_SESSION['aid']);

	//show_array_vars($altoidacc);

	echo "<table align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td colspan=\"2\" class=\"ltgray_und\" align=\"center\"><b>Sales Office Customer Search</b> </td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "         								<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "											<input type=\"hidden\" name=\"unrel\" value=\"1\">\n";
	echo "								<td colspan=\"2\" valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                              			<td align=\"right\"><b>Office:</b></td>\n";
	echo "                              			<td align=\"left\">\n";
	echo "												<select name=\"oid\" tabindex=\"3\" title=\"Select an Office to narrow the search\">\n";
	//echo "													<option value=\"0\">All Offices</option>\n";
	//echo "                                 					<option value=\"0\">----------------------</option>\n";
	
	while ($row2=mssql_fetch_array($res2))
	{
		if ($row2['officeid']==$row3['officeid'])
		{
			echo "													<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
		}
		else
		{
			echo "													<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
		}
	}
	
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              			<td align=\"right\" valign=\"top\">\n";
	echo "                               		    	<select name=\"field\" tabindex=\"1\" title=\"Select the Data Field to Search. This field can perform a partial match starting with the first character input\">\n";
	echo "                                    				<option value=\"clname\" SELECTED>Last Name</option>\n";
	echo "                                    				<option value=\"saddr1\">Site Addr</option>\n";
	echo "                                    			</select>\n";
	echo "											</td>\n";
	echo "                              			<td align=\"left\"><input tabindex=\"2\" class=\"bboxl\" type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              			<td align=\"right\"><b>Sort by:</b></td>\n";
	echo "                              			<td align=\"left\">\n";
	echo "                                    			<select name=\"order\" tabindex=\"5\" title=\"Set the Sort Order of the Search\">\n";
	echo "                                    				<option value=\"f.recdate\">Finance Assigned</option>\n";
	echo "                                   			 	<option value=\"f.frecdate\">Finance Received</option>\n";
	echo "                               			     	<option value=\"c.clname\" SELECTED>Last Name</option>\n";
	echo "                                 				   	<option value=\"c.szip1\">Site Zip Code</option>\n";
	echo "                                 				 </select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "			                              	<td align=\"center\" valign=\"bottom\"></td>\n";
	echo "											<td><input class=\"buttondkgrypnl80\" tabindex=\"11\" type=\"submit\" value=\"Search\" title=\"Click Here to Submit the Search Request\"></td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	
	/*echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch1'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch1'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";*/
}

function finan_form_addold()
{
	error_reporting(E_ALL);
	$oid		=$_POST['oid'];
	$foid		=$_POST['foid'];
	$cid		=$_POST['cid'];
	$uid		=$_POST['uid'];
	$dates	=dateformat();
	$acclist	=explode(",",$_SESSION['aid']);
	$curryr	=date("Y");
	$futyr 	=$curryr+1;
	$settax	=0;
	
	$qryApre = "SELECT officeid,name,stax FROM offices WHERE finan_from='".$_SESSION['officeid']."' order by name ASC;";
	$resApre = mssql_query($qryApre);
	$rowApre = mssql_fetch_array($resApre);
	
	$qry0 = "SELECT * FROM tfinan_detail WHERE cid='".$cid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT * FROM cinfo WHERE officeid='".$oid."' AND cid='".$cid."';";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	if ($nrow1!=0)
	{
		$row1 	= mssql_fetch_array($res1);
		$recdate	= date("m/d/Y",strtotime($row1['finan_date']));
	}
	else
	{
		$recdate	= date();
	}
	
	
	$settax=$rowApre['stax'];
	
	//echo "<input type=\"hidden\" name=\"recdate\" value=\"".$recdate."\">\n";
	echo "<table width=\"40%\" align=\"center\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td width=\"100%\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\" align=\"center\">\n";
	echo "         	<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "				<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\"width=\"100%\"><b>Add Finance Detail</b></td>\n";
	echo "					<td class=\"gray\" align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search Results\"><br>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				</form>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "<form name=\"finan_add\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	//echo "<input type=\"hidden\" name=\"call\" value=\"add_fin_detail2\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"updt_fin_detail\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "<input type=\"hidden\" name=\"oid\" value=\"".$oid."\">\n";
	echo "<input type=\"hidden\" name=\"foid\" value=\"".$foid."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"100%\" valign=\"top\">\n";
	
	cinfo_display_chistory($oid,$cid,$settax);
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"100%\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\" align=\"center\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\">\n";
	echo "						<table width=\"100%\" align=\"center\">\n";
	echo "							<tr>\n";
	echo "								<td class=\"ltgray_und\" colspan=\"2\" align=\"left\"><b>Financing Detail</b></td>\n";
	echo "							</tr>\n";
	
	
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Lender</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "                                    <select name=\"finansrc\" title=\"Update the Finance Source\">\n";
	
	if (!isset($row1['finan_src']) || $row1['finan_src']==0)
	{
		echo "                                    	<option value=\"0\">N/A</option>\n";
		echo "                                    	<option value=\"1\">BH Finance</option>\n";
		echo "                                    	<option value=\"2\">Cust Finan</option>\n";
		echo "                                    	<option value=\"3\">Cash</option>\n";
	}
	elseif ($row1['finan_src']==1)
	{
		echo "                                    	<option value=\"1\" selected>BH Finance</option>\n";
		echo "                                    	<option value=\"2\">Cust Finan</option>\n";
		echo "                                    	<option value=\"3\">Cash</option>\n";	
	}
	elseif ($row1['finan_src']==2)
	{
		echo "                                    	<option value=\"1\">BH Finance</option>\n";
		echo "                                    	<option value=\"2\" selected>Cust Finan</option>\n";
		echo "                                    	<option value=\"3\">Cash</option>\n";
	}
	elseif ($row1['finan_src']==3)
	{
		echo "                                    	<option value=\"1\">BH Finance</option>\n";
		echo "                                    	<option value=\"2\">Cust Finan</option>\n";
		echo "                                    	<option value=\"3\" selected>Cash</option>\n";
	}
	
	echo "                                    </select>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Status Report</b></td>\n";
	echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"inclstareport\" value=\"1\" title=\"Check this box to include this Contact in the Status Report\"></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Rec'd Date</b></td>\n";
	echo "								<td align=\"left\">".$recdate." <input type=\"hidden\" name=\"recdate\" value=\"".$recdate."\"></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Call Back Date</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"15\" title=\"Call Back Date\">\n";
	echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Call Back Date\"></a><br>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Lender</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "      							<select name=\"lender\">\n";
	echo "      							<option value=\"0\">Select a Lender...</option>\n";
			
			$qryF = "SELECT * FROM tlender WHERE lstatus=1 ORDER BY lendername ASC;";
			$resF = mssql_query($qryF);
			while ($rowF = mssql_fetch_array($resF))
			{
				if ($row['lender']==$rowF['lid'])
				{
					echo "      							<option value=\"".$rowF['lid']."\" SELECTED>".$rowF['lendername']."</option>\n";
				}
				else
				{
					echo "      							<option value=\"".$rowF['lid']."\">".$rowF['lendername']."</option>\n";
				}
			}
			
	echo "      							</select\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Amount Financed</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"finanamt\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Credit Score</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"crscore\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Rate</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"rate\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>GM Approval</b></td>\n";
	echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"gmapprove\" value=\"1\" title=\"Check this box for GM Approval\"></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Approval Date</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"15\" title=\"Approval Date\">\n";
	echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Approval Date\"></a><br>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Closed</b></td>\n";
	echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"financlosed\" value=\"1\" title=\"Check this box to Close this Contact\"></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Closer</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "      							<select name=\"closer\">\n";
	echo "      							<option value=\"0\">Select a Closer...</option>\n";
			
			$qryG = "SELECT securityid,lname,fname FROM security WHERE SUBSTRING(slevel,13,13)='1' AND officeid='".$oid."' OR officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
			$resG = mssql_query($qryG);
			while ($rowG= mssql_fetch_array($resG))
			{
				if ($row['closer']==$rowG['securityid'])
				{
					echo "      							<option value=\"".$rowG['securityid']."\" SELECTED>".$rowG['lname'].", ".$rowG['fname']."</option>\n";
				}
				else
				{
					echo "      							<option value=\"".$rowG['securityid']."\">".$rowG['lname'].", ".$rowG['fname']."</option>\n";
				}
			}
			
	echo "      							</select\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Reason Not Closed</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "      							<select name=\"finan_status\">\n";
			
			$qryE = "SELECT * FROM tfinanresultcodes ORDER BY rcode ASC;";
			$resE = mssql_query($qryE);
			while ($rowE= mssql_fetch_array($resE))
			{
				if ($row['finan_status']==$rowE['rid'])
				{
					echo "      							<option value=\"".$rowE['rid']."\" SELECTED>".$rowE['rcode']." - ".$rowE['descrip']."</option>\n";
				}
				else
				{
					echo "      							<option value=\"".$rowE['rid']."\">".$rowE['rcode']." - ".$rowE['descrip']."</option>\n";
				}
			}
			
	echo "      							</select\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP><b>Closing Date</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"d3\" size=\"15\" title=\"Closing Date\">\n";
	echo "									<a href=\"javascript:cal3.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Closing Date\"></a><br>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"top\" NOWRAP><b>Internal Comments</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "									<textarea name=\"comments\" rows=\"5\" cols=\"35\"></textarea>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td colspan=\"2\" align=\"right\">\n";
	echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['finan_add'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['finan_add'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						var cal3 = new calendar2(document.forms['finan_add'].elements['d3']);\n";
	echo "         						cal3.year_scroll = false;\n";
	echo "         						cal3.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";
	
}

function finan_form_add2old()
{
	error_reporting(E_ALL);
	
	//show_post_vars();
	
	if (empty($_POST['oid']) || empty($_POST['foid']) || $_POST['oid']==0 || $_POST['foid']==0)
	{
		echo "<font color=\"red\">Error Code 1</font><br>Incorrect OID or FOID Paramater.<br> Contact BHNM IT Support if this error persists: 619-233-3522 ext. 10180";
		exit;
	}
	
	$qry0 = "SELECT cid FROM tfinan_detail WHERE cid='".$_POST['cid']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if (isset($_POST['inclstareport'])  && $_POST['inclstareport']==1)
	{
		$inclstatreport=$_POST['inclstareport'];
	}
	else
	{
		$inclstatreport=0;
	}
	
	if (isset($_POST['gmapprove'])  && $_POST['gmapprove']==1)
	{
		$gmapprove=$_POST['gmapprove'];
	}
	else
	{
		$gmapprove=0;
	}
	
	if (isset($_POST['financlose'])  && $_POST['financlose']==1)
	{
		$financlose=$_POST['financlose'];
	}
	else
	{
		$financlose=0;
	}
	
	if (isset($_POST['lientype'])  && $_POST['lientype'] >= 1)
	{
		$lientype=$_POST['lientype'];
	}
	else
	{
		$lientype=0;
	}
	
	if ($nrow0 != 0)
	{
		$row0 = mssql_fetch_array($res0);
		// UPDATE Routine
		$qry1  = "UPDATE tfinan_detail SET ";
		$qry1 .= "inclstatreport='".$inclstatreport."',";
		$qry1 .= "cbddate='".$_POST['d1']."',";
		$qry1 .= "gmapprove='".$gmapprove."',";
		$qry1 .= "lender='".$_POST['lender']."',";
		$qry1 .= "lientype='".$lientype."',";
		$qry1 .= "amtfinan=CONVERT(money,'".$_POST['finanamt']."'),";
		$qry1 .= "credscore='".$_POST['crscore']."',";
		$qry1 .= "finanrate='".$_POST['rate'].",";
		$qry1 .= "dateapprove='".$_POST['d2']."',";
		$qry1 .= "financlose='".$financlose."',";
		$qry1 .= "closer='".$_POST['closer'].",";
		$qry1 .= "reasnotclosed='".$_POST['finan_status']."',";
		$qry1 .= "closedate='".$_POST['d3']."' ";
		$qry1 .= "lupdate=getdate(), ";
		$qry1 .= "lupdateid='".$_SESSION['securityid']."' ";
		$qry1 .= "WHERE cid='".$_POST['cid']."';";
		$res1 = mssql_query($qry1);
		
		//echo $qry1."<br>";
		finan_form_view();
	}
	else
	{
		//INSERT Routine
		$qry1  = "INSERT INTO tfinan_detail (";
		$qry1 .= "cid,";
		$qry1 .= "officeid,";
		$qry1 .= "finan_from,";
		$qry1 .= "uid,";
		$qry1 .= "inclstatreport,";
		$qry1 .= "recdate,";
		$qry1 .= "cbddate,";
		$qry1 .= "gmapprove,";
		$qry1 .= "lender,";
		$qry1 .= "lientype,";
		$qry1 .= "amtfinan,";
		$qry1 .= "credscore,";
		$qry1 .= "finanrate,";
		$qry1 .= "dateapprove,";
		$qry1 .= "financlose,";
		$qry1 .= "closer,";
		$qry1 .= "reasnotclosed,";
		$qry1 .= "closedate,";
		$qry1 .= "lupdate,";
		$qry1 .= "lupdateid";
		$qry1 .= ") VALUES (";
		$qry1 .= "'".$_POST['cid']."',";
		$qry1 .= "'".$_POST['oid']."',";
		$qry1 .= "'".$_POST['foid']."',";
		$qry1 .= "'".$_POST['uid']."',";
		$qry1 .= "'".$inclstatreport."',";
		$qry1 .= "'".$_POST['recdate']."',";
		$qry1 .= "'".$_POST['d1']."',";
		$qry1 .= "'".$gmapprove."',";
		$qry1 .= "'".$_POST['lender']."',";
		$qry1 .= "'".$lientype."',";
		$qry1 .= "CONVERT(money,'".$_POST['finanamt']."'),";
		$qry1 .= "'".$_POST['crscore']."',";
		$qry1 .= "'".$_POST['rate']."',";
		$qry1 .= "'".$_POST['d2']."',";
		$qry1 .= "'".$financlose."',";
		$qry1 .= "'".$_POST['closer']."',";
		$qry1 .= "'".$_POST['finan_status']."',";
		$qry1 .= "'".$_POST['d3']."',";
		$qry1 .= "getdate(),";
		$qry1 .= "'".$_SESSION['securityid']."'";
		$qry1 .= ");";
		$res1 = mssql_query($qry1);
		
		//echo $qry1."<br>";
		finan_form_view();
	}
}

function finan_form_updt_TEST()
{
	show_post_vars();
}

function finan_form_updt()
{
	error_reporting(E_ALL);
	
	if (isset($_POST['foid']) && $_POST['foid']!=0)
	{
		$foid = $_POST['foid'];
	}
	else
	{
		echo "<font color=\"red\"><b>ERROR</b></font><br> Finance Office variable error<br>";
		exit;
	}
	
	$qry0 = "SELECT cid,finan_from FROM tfinan_detail WHERE cid='".$_POST['cid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	$qry0a = "SELECT officeid,name,am FROM offices WHERE officeid='".$foid."';";
	$res0a = mssql_query($qry0a);
	$row0a= mssql_fetch_array($res0a);
	$nrow0a= mssql_num_rows($res0a);
	
	if (isset($_POST['assigned']) && $_POST['assigned']!=0)
	{
		$assigned=$_POST['assigned'];
	}
	else
	{
		$assigned=	$row0a['am'];
	}
	
	if (isset($_POST['inclstatreport'])  && $_POST['inclstatreport']==1)
	{
		$inclstatreport=$_POST['inclstatreport'];
	}
	else
	{
		$inclstatreport=0;
	}
	
	if (isset($_POST['gmapprove'])  && $_POST['gmapprove']==1)
	{
		$gmapprove=$_POST['gmapprove'];
	}
	else
	{
		$gmapprove=0;
	}
	
	if (isset($_POST['financlose'])  && $_POST['financlose']==1)
	{
		$financlose=$_POST['financlose'];
	}
	else
	{
		$financlose=0;
	}
	
	if (isset($_POST['bfeever'])  && $_POST['bfeever']==1)
	{
		$bfeever=$_POST['bfeever'];
	}
	else
	{
		$bfeever=0;
	}
	
	if (isset($_POST['cfeever'])  && $_POST['cfeever']==1)
	{
		$cfeever=$_POST['cfeever'];
	}
	else
	{
		$cfeever=0;
	}
	
	if (isset($_POST['p1feever'])  && $_POST['p1feever']==1)
	{
		$p1feever=$_POST['p1feever'];
	}
	else
	{
		$p1feever=0;
	}
	
	if (isset($_POST['p2feever'])  && $_POST['p2feever']==1)
	{
		$p2feever=$_POST['p2feever'];
	}
	else
	{
		$p2feever=0;
	}
	
	if (isset($_POST['ofeever'])  && $_POST['ofeever']==1)
	{
		$ofeever=$_POST['ofeever'];
	}
	else
	{
		$ofeever=0;
	}
	
	if (isset($_POST['r1'])  && valid_date($_POST['r1']))
	{
		$r1=$_POST['r1'];
	}
	else
	{
		$r1="";
	}
	
	if (isset($_POST['d1'])  && valid_date($_POST['d1']))
	{
		$d1=$_POST['d1'];
	}
	else
	{
		$d1="";
	}
	
	if (isset($_POST['d2'])  && valid_date($_POST['d2']))
	{
		$d2=$_POST['d2'];
	}
	else
	{
		$d2="";
	}
	
	if (isset($_POST['d3'])  && valid_date($_POST['d3']))
	{
		$d3=$_POST['d3'];
	}
	else
	{
		$d3="";
	}
	
	if (isset($_POST['d4'])  && valid_date($_POST['d4']))
	{
		$d4=$_POST['d4'];
	}
	else
	{
		$d4="";
	}
	
	if (isset($_POST['d5'])  && valid_date($_POST['d5']))
	{
		$d5=$_POST['d5'];
	}
	else
	{
		$d5="";
	}
	
	if (isset($_POST['lientype'])  && $_POST['lientype'] >= 1)
	{
		$lientype=$_POST['lientype'];
	}
	else
	{
		$lientype=0;
	}
	
	if ($nrow0 != 0)
	{
		$qry0  = "UPDATE tfinan_detail SET ";
		$qry0 .= "finan_from='".$foid."',";
		$qry0 .= "assigned='".$assigned."',";
		$qry0 .= "inclstatreport='".$inclstatreport."',";
		$qry0 .= "cbddate='".$d1."',";
		$qry0 .= "gmapprove='".$gmapprove."',";
		$qry0 .= "lender='".$_POST['lender']."',";
		$qry0 .= "lientype='".$lientype."',";
		$qry0 .= "amtfinan=CONVERT(money,'".$_POST['amtfinan']."'),";
		$qry0 .= "credscore='".$_POST['credscore']."',";
		$qry0 .= "finanrate='".$_POST['finanrate']."',";
		$qry0 .= "frecdate='".$r1."',";
		$qry0 .= "dateapprove='".$d2."',";
		$qry0 .= "financlose='".$financlose."',";
		$qry0 .= "closer='".$_POST['closer']."',";
		$qry0 .= "reasnotclosed='".$_POST['finan_status']."',";
		$qry0 .= "closedate='".$d3."',";
		$qry0 .= "datefeesent='".$d4."',";
		$qry0 .= "disclosedate='".$d5."',";
		$qry0 .= "bfee=CONVERT(money,'".$_POST['bfee']."'),";
		$qry0 .= "cfee=CONVERT(money,'".$_POST['cfee']."'),";
		$qry0 .= "p1fee=CONVERT(money,'".$_POST['p1fee']."'),";
		$qry0 .= "p2fee=CONVERT(money,'".$_POST['p2fee']."'),";
		$qry0 .= "ofee=CONVERT(money,'".$_POST['ofee']."'),";
		$qry0 .= "bfeever='".$bfeever."',";
		$qry0 .= "cfeever='".$cfeever."',";
		$qry0 .= "p1feever='".$p1feever."',";
		$qry0 .= "p2feever='".$p2feever."',";
		$qry0 .= "ofeever='".$ofeever."',";
		$qry0 .= "lupdate=getdate(),";
		$qry0 .= "fcomment='".strip_tags(replacequote(replacecomma(trim($_POST['fcomment']))))."',";
		$qry0 .= "lupdateid='".$_SESSION['securityid']."' ";
		$qry0 .= "WHERE cid='".$_POST['cid']."';";
		$res0 = mssql_query($qry0);
		
		//echo $qry0."<br>";
		if (isset($_POST['finansrc']) && $_POST['finansrc']!=0)
		{
			$qry0a  = "UPDATE cinfo SET ";
			$qry0a .= "finan_src='".$_POST['finansrc']."' ";
			$qry0a .= "WHERE cid='".$_POST['cid']."';";
			$res0a = mssql_query($qry0a);
			
			//echo $qry0a."<br>";
		}
		
		if ($foid!=$row0['finan_from'] && $foid!=0)
		{
			$qry0c  	= "SELECT name FROM offices WHERE officeid='".$foid."';";
			$res0c  	= mssql_query($qry0c);
			$row0c  	= mssql_fetch_array($res0c);
			
			$ctext  = "System Message - Finance Office Updated: ".$row0c['name'];		
	
			$qry0b  = "UPDATE cinfo SET finan_from='".$foid."' WHERE cid='".$_POST['cid']."';";
			$res0b = mssql_query($qry0b);
			
			$qry0b   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
			$qry0b  .= "VALUES ";
			$qry0b  .= "('".$_POST['cid']."','".$foid."','".$_SESSION['securityid']."','fin','".$ctext."','".$_POST['uid']."')";
			$res0b  = mssql_query($qry0b);
			//echo $qry0b."<br>";
		}
	}
	else
	{
		//INSERT Routine
		$qry1  = "INSERT INTO tfinan_detail (";
		$qry1 .= "cid,";
		$qry1 .= "officeid,";
		$qry1 .= "finan_from,";
		$qry1 .= "assigned,";
		$qry1 .= "uid,";
		$qry1 .= "inclstatreport,";
		$qry1 .= "recdate,";
		$qry1 .= "frecdate,";
		$qry1 .= "cbddate,";
		$qry1 .= "gmapprove,";
		$qry1 .= "lender,";
		$qry1 .= "lientype,";
		$qry1 .= "amtfinan,";
		$qry1 .= "credscore,";
		$qry1 .= "finanrate,";
		$qry1 .= "dateapprove,";
		$qry1 .= "financlose,";
		$qry1 .= "closer,";
		$qry1 .= "reasnotclosed,";
		$qry1 .= "closedate,";
		$qry1 .= "datefeesent,";
		$qry1 .= "disclosedate,";
		$qry1 .= "bfee,";
		$qry1 .= "cfee,";
		$qry1 .= "p1fee,";
		$qry1 .= "p2fee,";
		$qry1 .= "ofee,";
		$qry1 .= "bfeever,";
		$qry1 .= "cfeever,";
		$qry1 .= "p1feever,";
		$qry1 .= "p2feever,";
		$qry1 .= "ofeever,";
		$qry1 .= "fcomment,";
		$qry1 .= "lupdate,";
		$qry1 .= "lupdateid";
		$qry1 .= ") VALUES (";
		$qry1 .= "'".$_POST['cid']."',";
		$qry1 .= "'".$_POST['oid']."',";
		$qry1 .= "'".$foid."',";
		$qry1 .= "'".$assigned."',";
		$qry1 .= "'".$_POST['uid']."',";
		$qry1 .= "'".$inclstatreport."',";
		$qry1 .= "getdate(),";
		$qry1 .= "'".$r1."',";
		$qry1 .= "'".$d1."',";
		$qry1 .= "'".$gmapprove."',";
		$qry1 .= "'".$_POST['lender']."',";
		$qry1 .= "'".$lientype."',";
		$qry1 .= "CONVERT(money,'".$_POST['amtfinan']."'),";
		$qry1 .= "'".$_POST['credscore']."',";
		$qry1 .= "'".$_POST['finanrate']."',";
		$qry1 .= "'".$d2."',";
		$qry1 .= "'".$financlose."',";
		$qry1 .= "'".$_POST['closer']."',";
		$qry1 .= "'".$_POST['finan_status']."',";
		$qry1 .= "'".$d3."',";
		$qry1 .= "'".$d4."',";
		$qry1 .= "'".$d5."',";
		$qry1 .= "CONVERT(money,'".$_POST['bfee']."'),";
		$qry1 .= "CONVERT(money,'".$_POST['cfee']."'),";
		$qry1 .= "CONVERT(money,'".$_POST['p1fee']."'),";
		$qry1 .= "CONVERT(money,'".$_POST['p2fee']."'),";
		$qry1 .= "CONVERT(money,'".$_POST['ofee']."'),";
		$qry1 .= "'".$bfeever."',";
		$qry1 .= "'".$cfeever."',";
		$qry1 .= "'".$p1feever."',";
		$qry1 .= "'".$p2feever."',";
		$qry1 .= "'".$ofeever."',";
		$qry1 .= "'".strip_tags(replacequote(replacecomma(trim($_POST['fcomment']))))."',";
		$qry1 .= "getdate(),";
		$qry1 .= "'".$_SESSION['securityid']."'";
		$qry1 .= ");";
		$res1 = mssql_query($qry1);
		//echo $qry1."<br>";
		
		if (isset($_POST['cid']) && $_POST['cid']!=0 && isset($foid) && $foid!=0)
		{
			$qry1a  = "UPDATE cinfo SET ";
			$qry1a .= "finan_src='".$_POST['finansrc']."',finan_from='".$foid."',finan_date=getdate() ";
			$qry1a .= "WHERE cid='".$_POST['cid']."';";
			$res1a = mssql_query($qry1a);
		}
	}
	
	if (!empty($_POST['ecomment']) && strlen($_POST['ecomment']) > 3)
	{
		ecomments_add();
	}
	
	if (!empty($_POST['icomment']) && strlen($_POST['icomment']) > 3)
	{
		icomments_add();
	}
	
	if ($foid!=$row0['finan_from'] && $foid!=0)
	{		
		echo "Financial record moved to <b>".$row0a['name']."</b>.<br>";
	}
	else
	{
		finan_form_view();
	}
	
	//echo "<br>";
	//show_post_vars();
	//finan_form_view();
}

function finan_form_view()
{
	$oid		=$_POST['oid'];
	$foid		=$_POST['foid'];
	$cid		=$_POST['cid'];
	$uid		=$_POST['uid'];
	
	//show_post_vars();
	
	$dates	=dateformat();
	$acclist=explode(",",$_SESSION['aid']);
	$curryr	=date("Y");
	$futyr 	=$curryr+1;
	$settax	=0;
	
	$qryApre = "SELECT officeid,name,stax FROM offices WHERE finan_from='".$_SESSION['officeid']."' order by name ASC;";
	$resApre = mssql_query($qryApre);
	$rowApre = mssql_fetch_array($resApre);
	
	$qryBpre = "SELECT cid,finan_date,finan_src FROM cinfo WHERE cid='".$cid."';";
	$resBpre = mssql_query($qryBpre);
	$rowBpre = mssql_fetch_array($resBpre);
	
	$qryCpre = "SELECT officeid,securityid,lname,fname FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resCpre = mssql_query($qryCpre);
	$rowCpre = mssql_fetch_array($resCpre);
	
	$qryDpre = "SELECT officeid,name FROM offices WHERE officeid='".$rowCpre['officeid']."';";
	$resDpre = mssql_query($qryDpre);
	$rowDpre = mssql_fetch_array($resDpre);
	
	$qry0 = "SELECT * FROM tfinan_detail WHERE cid='".$cid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	$qry1 = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row0['lupdateid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	
	$qry1a = "SELECT officeid,securityid,lname,fname,(select name from offices where officeid=s.officeid) as oname FROM security as s WHERE securityid='".$row0['assigned']."';";
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);
	$nrow1a= mssql_num_rows($res1a);
	
	$settax=$rowApre['stax'];
	
	if ($nrow0==0)
	{
		$recdate=date("m/d/Y",time()); // Prefill if tfinan_detail record doesn't exist
	}
	else
	{
		if (!empty($row0['recdate']) && valid_date(date("m/d/Y",strtotime($row0['recdate']))))
		{
			$recdate=date("m/d/Y",strtotime($row0['recdate']));
		}
		else
		{
			$recdate="";
		}
	}
	
	if (!empty($row0['frecdate']) && strtotime($row0['frecdate']) >= strtotime('12/1/06'))
	{
		$frecdate=date("m/d/Y",strtotime($row0['frecdate']));
	}
	else
	{
		$frecdate="";
	}
	
	if (!empty($row0['cbddate']) && strtotime($row0['cbddate']) >= strtotime('12/1/06'))
	{
		$cbddate=date("m/d/Y",strtotime($row0['cbddate']));
	}
	else
	{
		$cbddate="";
	}
	
	if (!empty($row0['dateapprove']) && strtotime($row0['dateapprove']) >= strtotime('12/1/06'))
	{
		$dateapprove=date("m/d/Y",strtotime($row0['dateapprove']));
	}
	else
	{
		$dateapprove="";
	}
	
	if (!empty($row0['closedate']) && strtotime($row0['closedate']) >= strtotime('12/1/06'))
	{
		$dateclosed=date("m/d/Y",strtotime($row0['closedate']));
	}
	else
	{
		$dateclosed="";
	}
	
	if (!empty($row0['disclosedate']) && strtotime($row0['disclosedate']) >= strtotime('12/1/06'))
	{
		$disclosedate=date("m/d/Y",strtotime($row0['disclosedate']));
	}
	else
	{
		$disclosedate="";
	}
	
	if (!empty($row0['datefeesent']) && strtotime($row0['datefeesent']) >= strtotime('12/1/06'))
	{
		$datefeesent=date("m/d/Y",strtotime($row0['datefeesent']));
	}
	else
	{
		$datefeesent="";
	}
	
	if (!empty($row0['lupdate']))
	{
		$lastupdate=date("m/d/Y",strtotime($row0['lupdate']));
	}
	else
	{
		$lastupdate="";
	}
	
	if ($nrow1!=0)
	{
		$lastupdateby=$row1['lname'].", ".$row1['fname'];
	}
	else
	{
		$lastupdateby="";
	}
	
	/*$qry2  = "select ";
	$qry2 .= "	s.officeid, ";
	$qry2 .= "	s.securityid, ";
	$qry2 .= "	s.lname, ";
	$qry2 .= "	s.fname, ";
	$qry2 .= "	s.slevel, ";
	$qry2 .= "	o.officeid, ";
	$qry2 .= "	o.name ";
	$qry2 .= "from ";
	$qry2 .= "	offices as o ";
	$qry2 .= "inner join ";
	$qry2 .= "	security as s ";
	$qry2 .= "on  ";
	$qry2 .= "	o.officeid=s.officeid ";
	$qry2 .= "where ";
	$qry2 .= "	o.finan_off=1 ";
	$qry2 .= "	and substring(s.slevel,13,13) > 0 ";
	$qry2 .= "	and o.officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] < 6)
	{
		$qry2 .= "	and s.securityid='".$row1a['securityid']."' ";
	}
	
	$qry2 .= "order by ";
	$qry2 .= "	o.name asc,substring(s.slevel,13,13) desc,s.lname asc;";*/
	if ($rowCpre['officeid']==89) // For Admin Staff Only
	{
		$qry2  = "select  o.officeid, ";
		$qry2 .= "		(select name from jest..offices where officeid=o.officeid) as name, ";
		$qry2 .= "		s.lname, ";
		$qry2 .= "		s.securityid, ";
		$qry2 .= "		s.officeid, ";
		$qry2 .= "        s.fname, ";
		$qry2 .= "        s.slevel ";
		$qry2 .= "from    offices as o ";
		$qry2 .= "inner join		security as s ";
		$qry2 .= "on		o.officeid=s.officeid ";
		$qry2 .= "where   o.finan_off = 1 ";
		$qry2 .= "        and substring(s.slevel, 13, 1) > 0 ";
		$qry2 .= "		and substring(s.slevel, 7, 1) > 0 ";
		$qry2 .= "order by name asc, ";
		$qry2 .= "        substring(s.slevel, 13, 1) desc, ";
		$qry2 .= "        s.lname asc; ";
	}
	else
	{
		if ($_SESSION['llev'] >= 6)
		{
			$qry2  = "select	a.oid as officeid, ";
			$qry2 .= "			(select name from jest..offices where officeid=a.oid) as name, ";
			$qry2 .= "			s.lname, ";
			$qry2 .= "			s.securityid, ";
			$qry2 .= "			s.officeid, ";
			$qry2 .= "			s.fname, ";
			$qry2 .= "			s.slevel ";
			$qry2 .= "from		alt_security_levels as a ";
			$qry2 .= "inner join	security as s ";
			$qry2 .= "on			s.officeid=a.oid ";
			$qry2 .= "where		substring(s.slevel, 13, 1) > 0 ";
			$qry2 .= "			and substring(a.slevel, 13, 1) > 0 ";
			$qry2 .= "			and sid = ".$_SESSION['securityid']." ";
			$qry2 .= "order by ";
			$qry2 .= "			name asc, ";
			$qry2 .= "			substring(s.slevel, 13, 13) desc, ";
			$qry2 .= "			s.lname asc; ";
		}
		else
		{
			$qry2  = "select	o.officeid, ";
			$qry2 .= "			o.name, ";
			$qry2 .= "			s.lname, ";
			$qry2 .= "			s.securityid, ";
			$qry2 .= "			s.officeid, ";
			$qry2 .= "			s.fname, ";
			$qry2 .= "			s.slevel ";
			$qry2 .= "from		offices as o ";
			$qry2 .= "inner join	security as s ";
			$qry2 .= "on			s.officeid=o.officeid ";
			$qry2 .= "where		s.securityid = ".$_SESSION['securityid']." ";
		}
	}
	
	$res2 = mssql_query($qry2);
	$nrow2= mssql_num_rows($res2);
	
	//echo $qry2."<br>";
	
	$qry3  = "select ";
	$qry3 .= "	s.officeid, ";
	$qry3 .= "	s.securityid, ";
	$qry3 .= "	s.lname, ";
	$qry3 .= "	s.fname, ";
	$qry3 .= "	s.slevel, ";
	$qry3 .= "	o.officeid, ";
	$qry3 .= "	o.name ";
	$qry3 .= "from ";
	$qry3 .= "	offices as o ";
	$qry3 .= "inner join ";
	$qry3 .= "	security as s ";
	$qry3 .= "on  ";
	$qry3 .= "	o.officeid=s.officeid ";
	$qry3 .= "where ";
	$qry3 .= "	o.finan_off=1 ";
	$qry3 .= "	and substring(s.slevel,13,13) > 0 ";
	$qry3 .= "	and o.officeid=".$_SESSION['officeid']." ";
	
	/*if ($_SESSION['llev'] < 6)
	{
		$qry3 .= "	and s.securityid='".$row1a['securityid']."' ";
		//$qry2 .= "	and s.securityid='".$closer[1]."' ";
	}*/
	
	$qry3 .= "order by ";
	$qry3 .= "	o.name asc,substring(s.slevel,13,13) desc,s.lname asc;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);
	
	//echo $qry3."<br>";
	
	//echo "Lien Type: ".$row0['lientype']."<br>";
	
	//echo $_SESSION['llev']."<br>";
	
	echo "<table width=\"950px\" align=\"center\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td width=\"100%\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\" align=\"center\">\n";
	echo "         	<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "				<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\"><b>View Finance Detail</b></td>\n";
	echo "					<td class=\"gray\" align=\"right\">\n";
	
	$dtx="";
	$dis="";
	
	if (isset($_POST['csearch']) && $_POST['csearch']==1 || !isset($_SESSION['tqry']))
	{
		$dtx="Disabled. This feature currently only works in Contact Search.";
		$dis="DISABLED";	
	}
	
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search Results\" ".$dis." title=\"".$dtx."\"><br>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				</form>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "<form name=\"finan_add\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"updt_fin_detail\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "<input type=\"hidden\" name=\"oid\" value=\"".$oid."\">\n";
	echo "<input type=\"hidden\" name=\"foid\" value=\"".$foid."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"100%\" valign=\"top\">\n";
	
	cinfo_display_chistory($oid,$cid,$settax);
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"100%\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\" align=\"center\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\">\n";
	
	if ($_SESSION['securityid']==$row0['assigned'] || $_SESSION['llev'] >= 1)
	{
		echo "						<table width=\"100%\" align=\"center\">\n";
		echo "							<tr>\n";
		//echo "								<td class=\"ltgray_und\" align=\"left\"><b>Financing Detail</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\"><b>Financing Detail</b> (".$rowBpre['cid'].")</td>\n";
		
		if ($nrow0==0)
		{
			echo "								<td class=\"ltgray_und\" align=\"right\"><marquee><font color=\"red\"><b>Finance Detail record does not exist for this Customer.  Fill in the appropriate Information below and click Save</b></font></marquee></td>\n";	
		}
		else
		{
			if ($nrow1!=0)
			{
				echo "								<td class=\"ltgray_und\" align=\"right\">Last Update: ".$lastupdate." (".$lastupdateby.")</td>\n";
			}
			else
			{
				echo "								<td class=\"ltgray_und\" align=\"right\"></td>\n";
			}
		}
		
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Assigned:</b></td>\n";
		echo "								<td align=\"left\">\n";
		
		if ($_SESSION['llev'] >= 5)
		{
			if ($nrow2 > 0)
			{
				echo "      							<select name=\"assigned\">\n";
				
				if ($_SESSION['llev'] >= 6)
				{
					echo "      							<option value=\"0\">Unassigned</option>\n";
				}
				
				$x=0;
				while ($row2= mssql_fetch_array($res2))
				{
					if ($x==0 || $x!=$row2['officeid'])
					{
						echo "      							<optgroup class=\"plain\" label=\"".$row2['name']."\">\n";
					}
					
					if ($row0['assigned']==$row2['securityid'])
					{
						echo "      							<option class=\"fontblue\" value=\"".$row2['securityid']."\" SELECTED>".$row2['lname'].", ".$row2['fname']." - ".$row2['name']."</option>\n";
					}
					else
					{
						echo "      							<option value=\"".$row2['securityid']."\">".$row2['lname'].", ".$row2['fname']." - ".$row2['name']."</option>\n";
					}
					$x=$row2['officeid'];
				}
				
				echo "      							</select\">\n";
			}
		}
		else
		{
			if ($row0['assigned']!=0)
			{
				echo $row1a['lname'] .", " . $row1a['fname'] . " - " . $row1a['oname'];
				echo "<input type=\"hidden\" name=\"assigned\" value=\"".$row0['assigned']."\">\n";
			}
			elseif ($row0['assigned']==0)
			{
				echo "<select name=\"assigned\">\n";
				echo "	<option value=\"0\">Unassigned</option>\n";
				echo "	<option value=\"".$rowCpre['securityid']."\">".$rowCpre['lname'].", ".$rowCpre['fname']." - ".$rowDpre['name']."</option>\n";
				echo "</select>\n";
				//echo $_SESSION['lname'] .", " . $_SESSION['fname'] . " - " . $_SESSION['oname'];
				//echo "<input type=\"hidden\" name=\"assigned\" value=\"".$_SESSION['assigned']."\">\n";
			}
		}
		
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Status Report:</b></td>\n";
		
		if ($row0['inclstatreport']==1)
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"inclstatreport\" value=\"1\" title=\"Check this box to include this Contact in the Status Report\" CHECKED></td>\n";
		}
		else
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"inclstatreport\" value=\"1\" title=\"Check this box to include this Contact in the Status Report\"></td>\n";	
		}
		
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Finance Assigned Date:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo $recdate;
		//echo "									<input class=\"bboxl\" type=\"text\" value=\"".$recdate."\" size=\"15\" DISABLED>\n";
		echo "									<input type=\"hidden\" name=\"recdate\" value=\"".$recdate."\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Finance Received Date:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" name=\"r1\" value=\"".$frecdate."\" size=\"15\" title=\"Finance Rec'd Date\">\n";
		//echo "									<a href=\"javascript:cal0.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to the Finance Rec'd Date\"></a><br>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Finance Source</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "                                    <select name=\"finansrc\" title=\"Update the Finance Source\">\n";
		
		if (!isset($rowBpre['finan_src']) || $rowBpre['finan_src']==0)
		{
			echo "                                    	<option value=\"0\">Select...</option>\n";
			//echo "                                    	<option value=\"1\">Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowBpre['finan_src']==1)
		{
			echo "                                    	<option value=\"1\" selected>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
			
		}
		elseif ($rowBpre['finan_src']==2)
		{
			//echo "                                    	<option value=\"1\">Winners</option>\n";
			echo "                                    	<option value=\"2\" selected>Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowBpre['finan_src']==3)
		{
			//echo "                                    	<option value=\"1\">Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\" selected>Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowBpre['finan_src']==4)
		{
			//echo "                                    	<option value=\"1\">Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\" selected>BH Finance</option>\n";
		}
		
		echo "                                    </select>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Call Back Date:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" value=\"".$cbddate."\" size=\"15\" title=\"Call Back Date\">\n";
		//echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Call Back Date\"></a><br>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Lender:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "      							<select name=\"lender\">\n";
		echo "      								<option value=\"0\">Select a Lender...</option>\n";
		
		$qryF = "SELECT * FROM tlender ORDER BY lendername ASC;";
		$resF = mssql_query($qryF);
		while ($rowF= mssql_fetch_array($resF))
		{
			if ($row0['lender']==$rowF['lid'])
			{
				echo "      							<option value=\"".$rowF['lid']."\" SELECTED>".$rowF['lendername']."</option>\n";
			}
			else
			{
				echo "      							<option value=\"".$rowF['lid']."\">".$rowF['lendername']."</option>\n";
			}
		}
		
		echo "      							</select\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Lien Type:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<table>\n";
		echo "										<tr>\n";
		echo "											<td align=\"center\" title=\"1st Lien\">1st</td>\n";
		echo "											<td align=\"center\" title=\"2nd Lien\">2nd</td>\n";
		echo "											<td align=\"center\" title=\"3rd Lien\">3rd</td>\n";
		echo "											<td align=\"center\" title=\"Unspecified\">Uns</td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		
		if ($row0['lientype']==4)
		{	
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"1\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"2\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"3\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"4\" CHECKED></td>\n";
		}
		elseif ($row0['lientype']==3)
		{	
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"1\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"2\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"3\" CHECKED></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"4\"></td>\n";
		}
		elseif ($row0['lientype']==2)
		{	
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"1\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"2\" CHECKED></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"3\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"4\"></td>\n";
		}
		elseif ($row0['lientype']==1)
		{	
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"1\" CHECKED></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"2\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"3\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"4\"></td>\n";
		}
		else
		{	
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"1\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"2\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"3\"></td>\n";
			echo "									<td align=\"center\"><input class=\"radiogry\" type=\"radio\" name=\"lientype\" value=\"4\"></td>\n";
		}
		
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Amount Financed:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"amtfinan\" value=\"".number_format($row0['amtfinan'],2,'.','')."\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Credit Score:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"credscore\" value=\"".$row0['credscore']."\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Rate:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"finanrate\" value=\"".$row0['finanrate']."\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>GM Approval:</b></td>\n";
		
		if ($row0['gmapprove']==1)
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"gmapprove\" value=\"1\" title=\"Check this box to unset GM Approval\" CHECKED></td>\n";
		}
		else
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"gmapprove\" value=\"1\" title=\"Check this box to unset GM Approval\"></td>\n";	
		}
		
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Approval Date:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" value=\"".$dateapprove."\" size=\"15\" title=\"Approval Date\">\n";
		//echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Approval Date\"></a><br>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Closed:</b></td>\n";
		
		if ($row0['financlose']==1)
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"financlose\" value=\"1\" title=\"Check this box to Close\" CHECKED></td>\n";
		}
		else
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"financlose\" value=\"1\" title=\"Check this box to Close\"></td>\n";	
		}
		
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Closer:</b></td>\n";
		echo "								<td align=\"left\">\n";
		
		if ($nrow3 > 0)
		{
			echo "      							<select name=\"closer\">\n";
			
			if ($_SESSION['llev'] >= 6)
			{
				echo "      							<option value=\"0\">Unassigned</option>\n";
			}
			
			$y=0;
			while ($row3= mssql_fetch_array($res3))
			{
				if ($y==0 || $y!=$row3['officeid'])
				{
					echo "      							<optgroup class=\"plain\" label=\"".$row3['name']."\">\n";
				}
				
				if ($row0['closer']==$row3['securityid'])
				{
					echo "      							<option class=\"fontblue\" value=\"".$row3['securityid']."\" SELECTED>".$row3['lname'].", ".$row3['fname']." - ".$row3['name']."</option>\n";
				}
				else
				{
					echo "      							<option value=\"".$row3['securityid']."\">".$row3['lname'].", ".$row3['fname']." - ".$row3['name']."</option>\n";
				}
				$y=$row3['officeid'];
			}
			
			echo "      							</select\">\n";
		}
		
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Reason Not Closed:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "      							<select name=\"finan_status\">\n";
				
		$qryE = "SELECT * FROM tfinanresultcodes ORDER BY rcode ASC;";
		$resE = mssql_query($qryE);
		while ($rowE= mssql_fetch_array($resE))
		{
			if ($row0['reasnotclosed']==$rowE['rid'])
			{
				echo "      							<option value=\"".$rowE['rid']."\" SELECTED>".$rowE['rcode']." - ".$rowE['descrip']."</option>\n";
			}
			else
			{
				echo "      							<option value=\"".$rowE['rid']."\">".$rowE['rcode']." - ".$rowE['descrip']."</option>\n";
			}
		}
				
		echo "      							</select\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Closing Date:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d3\" value=\"".$dateclosed."\" size=\"15\" title=\"Call Back Date\">\n";
		//echo "									<a href=\"javascript:cal3.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Closing Date\"></a><br>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Broker Fee:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"bfee\" value=\"".number_format($row0['bfee'],2,'.','')."\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Broker Fee Verified:</b></td>\n";
		
		if ($row0['bfeever']==1)
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"bfeever\" value=\"1\" title=\"Check this box to Verify Broker Free\" CHECKED></td>\n";
		}
		else
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"bfeever\" value=\"1\" title=\"Check this box to Verify Broker Free\"></td>\n";	
		}
		
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Closing Fee:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"cfee\" value=\"".number_format($row0['cfee'],2,'.','')."\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Closing Fee Verified:</b></td>\n";
		
		if ($row0['cfeever']==1)
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"cfeever\" value=\"1\" title=\"Check this box to Verify Closing Free\" CHECKED></td>\n";
		}
		else
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"cfeever\" value=\"1\" title=\"Check this box to Verify Closing Free\"></td>\n";	
		}
		
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Property Fee:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"p1fee\" value=\"".number_format($row0['p1fee'],2,'.','')."\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Property Fee Verified:</b></td>\n";
		
		if ($row0['p1feever']==1)
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"p1feever\" value=\"1\" title=\"Check this box to Verify Property Free\" CHECKED></td>\n";
		}
		else
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"p1feever\" value=\"1\" title=\"Check this box to Verify Property Free\"></td>\n";	
		}
		
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Processing Fee:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"p2fee\" value=\"".number_format($row0['p2fee'],2,'.','')."\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Processing Fee Verified:</b></td>\n";
		
		if ($row0['p2feever']==1)
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"p2feever\" value=\"1\" title=\"Check this box to Verify Processing Free\" CHECKED></td>\n";
		}
		else
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"p2feever\" value=\"1\" title=\"Check this box to Verify Processing Free\"></td>\n";	
		}
		
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Other Fee:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" size=\"15\" name=\"ofee\" value=\"".number_format($row0['ofee'],2,'.','')."\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Other Fee Verified:</b></td>\n";
		
		if ($row0['ofeever']==1)
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"ofeever\" value=\"1\" title=\"Check this box to Verify Other Free\" CHECKED></td>\n";
		}
		else
		{
			echo "								<td align=\"left\"><input class=\"checkbox\" type=\"checkbox\" name=\"ofeever\" value=\"1\" title=\"Check this box to Verify Other Free\"></td>\n";	
		}
		
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Date Fee Sent:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d4\" value=\"".$datefeesent."\" size=\"15\" title=\"Date Fee Sent\">\n";
		//echo "									<a href=\"javascript:cal4.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Fee Sent Date\"></a><br>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" NOWRAP><b>Disclosure Date:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d5\" value=\"".$disclosedate."\" size=\"15\" title=\"Disclosure Date\">\n";
		//echo "									<a href=\"javascript:cal5.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Disclosure Date\"></a><br>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" valign=\"top\" NOWRAP><b>Fee Comment:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<textarea name=\"fcomment\" rows=\"3\" cols=\"35\">".stripslashes(removequote(trim($row0['fcomment'])))."</textarea>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" valign=\"top\" NOWRAP><b>External Comment:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<textarea name=\"ecomment\" rows=\"3\" cols=\"35\"></textarea>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"right\" valign=\"top\" NOWRAP><b>Internal Comment:</b></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<textarea name=\"icomment\" rows=\"3\" cols=\"35\"></textarea>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td colspan=\"2\" align=\"center\">\n";
		
		if ($nrow0==0)
		{
			echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Save\">\n";
		}
		else
		{
			echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\">\n";
		}
		
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		
		$qid	 = md5(session_id().".".time().".".$_SESSION['securityid']);
		$qryZa = "SELECT * FROM chistory WHERE custid='".$_POST['cid']."' ORDER BY mdate DESC;";
		$resZa = mssql_query($qryZa);
		$nrwZa = mssql_num_rows($resZa);
		
		$qryZb = "SELECT * FROM tfinanicomments WHERE cid='".$_POST['cid']."' ORDER BY adate DESC;";
		$resZb = mssql_query($qryZb);
		$nrwZb = mssql_num_rows($resZb);
		
		echo "				<input type=\"hidden\" name=\"qid\" value=\"".$qid."\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\">\n";
		echo "						<table width=\"100%\" align=\"center\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"wh_und\" align=\"center\"><b>External Comments</b></td>\n";
		echo "								<td class=\"wh_und\" align=\"center\"><b>Internal Comments</b></td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"center\" valign=\"top\" width=\"50%\">\n";
		
		if ($nrwZa > 0)
		{
			//echo $qryZa."<br>";
			echo "						<table width=\"100%\" align=\"center\">\n";
			echo "							<tr>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\"><b>Date</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\"><b>Name</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\"><b>Stage</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\"><b>Comments</b></td>\n";
			echo "							</tr>\n";
			
			while ($rowZa= mssql_fetch_array($resZa))
			{
				$qryZa1 = "SELECT lname FROM security WHERE securityid='".$rowZa['secid']."';";
				$resZa1 = mssql_query($qryZa1);
				$rowZa1 = mssql_fetch_array($resZa1);
				
				$elname	=	$rowZa1['lname'];
				
				if ($rowZa['act']=="leads")
				{
					$stage="Lead";
				}
				elseif ($rowZa['act']=="reports")
				{
					$stage="Reports";
				}
				elseif ($rowZa['act']=="est")
				{
					$stage="Estimate";
				}
				elseif ($rowZa['act']=="contract")
				{
					$stage="Contract";
				}
				elseif ($rowZa['act']=="job")
				{
					$stage="Job";
				}
				elseif ($rowZa['act']=="mas")
				{
					$stage="MAS";
				}
				elseif ($rowZa['act']=="fin")
				{
					$stage="Finance";
				}
				else
				{
					$stage="";
				}
				
				echo "							<tr>\n";
				echo "								<td class=\"wh_und\" align=\"center\" valign=\"top\">".date("m/d/Y",strtotime($rowZa['mdate']))."</td>\n";
				echo "								<td class=\"wh_und\" align=\"left\" valign=\"top\">".$elname."</td>\n";
				echo "								<td class=\"wh_und\" align=\"center\" valign=\"top\">".$stage."</td>\n";
				echo "								<td class=\"wh_und\" align=\"left\" valign=\"top\">".$rowZa['mtext']."</td>\n";
				echo "							</tr>\n";
			}
			
			echo "						</table>\n";
		}
	
		echo "								</td>\n";
		echo "								<td class=\"gray_lside\" align=\"center\" valign=\"top\" width=\"50%\">\n";
		
		if ($nrwZb > 0)
		{	
			echo "						<table width=\"100%\" align=\"center\">\n";
			echo "							<tr>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\"><b>Date</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"left\"><b>Name</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"left\"><b>Comments</b></td>\n";
			echo "							</tr>\n";
			
			while ($rowZb= mssql_fetch_array($resZb))
			{
				$qryZb1 = "SELECT lname FROM security WHERE securityid='".$rowZb['secid']."';";
				$resZb1 = mssql_query($qryZb1);
				$rowZb1 = mssql_fetch_array($resZb1);
				
				$ilname	=	$rowZb1['lname'];
	
				echo "							<tr>\n";
				echo "								<td class=\"wh_und\" align=\"center\" valign=\"top\">".date("m/d/Y",strtotime($rowZb['adate']))."</td>\n";
				echo "								<td class=\"wh_und\" align=\"left\" valign=\"top\">".$ilname."</td>\n";
				echo "								<td class=\"wh_und\" align=\"left\" valign=\"top\">".$rowZb['mbody']."</td>\n";
				echo "							</tr>\n";
			}
			
			echo "						</table>\n";
		}
	
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "</form>\n";
	
		/*echo "         						<script language=\"JavaScript\">\n";
		echo "         						var cal0 = new calendar2(document.forms['finan_add'].elements['r1']);\n";
		echo "         						cal0.year_scroll = false;\n";
		echo "         						cal0.time_comp = false;\n";
		echo "         						var cal1 = new calendar2(document.forms['finan_add'].elements['d1']);\n";
		echo "         						cal1.year_scroll = false;\n";
		echo "         						cal1.time_comp = false;\n";
		echo "         						var cal2 = new calendar2(document.forms['finan_add'].elements['d2']);\n";
		echo "         						cal2.year_scroll = false;\n";
		echo "         						cal2.time_comp = false;\n";
		echo "         						var cal3 = new calendar2(document.forms['finan_add'].elements['d3']);\n";
		echo "         						cal3.year_scroll = false;\n";
		echo "         						cal3.time_comp = false;\n";
		echo "         						var cal4 = new calendar2(document.forms['finan_add'].elements['d4']);\n";
		echo "         						cal4.year_scroll = false;\n";
		echo "         						cal4.time_comp = false;\n";
		echo "         						var cal5 = new calendar2(document.forms['finan_add'].elements['d5']);\n";
		echo "         						cal5.year_scroll = false;\n";
		echo "         						cal5.time_comp = false;\n";
		echo "         						//-->\n";
		echo "         						</script>\n";*/
	}
	else
	{
		echo "						<table width=\"100%\" align=\"center\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\">You do not have appropriate rights to view the Financial Detail.</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
	}
	
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function ecomments_add()
{
	$qry  = "SELECT * FROM chistory WHERE tranid='".$_POST['qid']."';";
	$res  = mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	if ($nrow == 0)
	{
		$qry0  = "INSERT INTO chistory (officeid,secid,custid,act,tranid,mtext) ";
		$qry0 .= "VALUES ";
		$qry0 .= "('".$_SESSION['officeid']."','".$_SESSION['securityid']."','".$_POST['cid']."','fin','".$_POST['qid']."','".replacequote($_POST['ecomment'])."');";
		$res0  = mssql_query($qry0);
		
		$qry1  = "UPDATE tfinan_detail set lupdate=getdate() WHERE cid='".$_POST['cid']."';";
		$res1  = mssql_query($qry1);
	}
}

function icomments_add()
{
	$qry  = "SELECT * FROM tfinanicomments WHERE tranid='".$_POST['qid']."';";
	$res  = mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	if ($nrow == 0)
	{
		$qry0  = "INSERT INTO tfinanicomments (oid,secid,cid,tranid,mbody) ";
		$qry0 .= "VALUES ";
		$qry0 .= "('".$_SESSION['officeid']."','".$_SESSION['securityid']."','".$_POST['cid']."','".$_POST['qid']."','".replacequote($_POST['icomment'])."');";
		$res0  = mssql_query($qry0);
		
		$qry1  = "UPDATE tfinan_detail set lupdate=getdate() WHERE cid='".$_POST['cid']."';";
		$res1  = mssql_query($qry1);
	}
}

function listleads()
{
	error_reporting(E_ALL);
	$officeid	=$_SESSION['officeid'];
	$securityid	=$_SESSION['securityid'];
	$acclist	=explode(",",$_SESSION['aid']);
	$unxdt		=time();
	
	//echo "<br>";
	
	//print_r($acclist);
	
	//echo "<br>";
	
	if (isset($_POST['order']))
	{
		$order=$_POST['order'];
	}
	else
	{
		$order="f.frecdate";
	}
	
	if (isset($_POST['ascdesc']) && $_POST['ascdesc']=="desc")
	{
		$ascdesc=$_POST['ascdesc'];
	}
	else
	{
		$ascdesc="asc";
	}

	if (isset($_POST['showdupe']) && $_POST['showdupe']==1)
	{
		$dupe="";
	}
	else
	{
		$dupe=" AND c.dupe!=1 ";
	}

	if (isset($_POST['showhold']) && $_POST['showhold']==1)
	{
		$hold=" AND c.hold=1 ";
	}
	else
	{
		$hold="";
	}
	
	if (isset($_POST['oid']) && $_POST['oid']!=0)
	{
		$ooid=" AND c.officeid='".$_POST['oid']."' ";
	}
	else
	{
		$ooid="";
	}

	if (isset($_POST['finansrc']) && $_POST['finansrc']!=0)
	{
		$finansrc=" AND c.finan_src='".$_POST['finansrc']."' ";
	}
	else
	{
		$finansrc="";
	}
	
	if (isset($_POST['ssearch']) && strlen($_POST['ssearch']) >= 1)
	{
		$ssearch=" AND c.".$_POST['field']." LIKE '".$_POST['ssearch']."%' ";
	}
	else
	{
		$ssearch="";
	}

	if (isset($_SESSION['tqry']))
	{
		//echo "ZERO<br>";
		$qry	=$_SESSION['tqry'];
		//$qtext="<b>NOTE:</b> This dataset is based upon previously entered Search parameters. Select <b>New Search</b> to clear this condition.";
		echo "<table align=\"center\" width=\"950px\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>NOTE:</b> This dataset is based upon previously entered Search parameters. Select <b>New Search</b> to clear this condition.</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	else
	{
		if (isset($_POST['unrel']) && $_POST['unrel']==1 && $_POST['oid']!=0)
		{
			//$qry   = "DECLARE @na varchar(3)";
			//$qry  .= "SET @na='N/A'";
			if (isset($_POST['oid']) && $_POST['oid']!=0)
			{
				$qry  = "SELECT ";
				$qry  .= "		c.*,  ";
				$qry  .= "		(select 'N/A') as frecdate,  ";
				$qry  .= "		(select 'N/A') as disclosedate,  ";
				$qry  .= "		(select 'N/A') as finan_date,  ";
				$qry  .= "		(select 'N/A') as recdate,  ";
				$qry  .= "		(select 'N/A') as cbddate,  ";
				$qry  .= "		(select 'N/A') as financlose,  ";
				$qry  .= "		(select 'N/A') as aslname,  ";
				$qry  .= "		(select 'N/A') as asfname,  ";
				$qry  .= "		(select '0') as assigned,  ";
				$qry  .= "		(select substring(slevel,13,13) from security where securityid=c.securityid) as secenable,  ";
				$qry  .= "		(select lname from security where securityid=c.securityid) as srlname,  ";
				$qry  .= "		(select fname from security where securityid=c.securityid) as srfname  ";
				$qry  .= "FROM ";
				$qry  .= "		cinfo as c ";
				$qry  .= "WHERE ";
				$qry  .= "		c.officeid='".$_POST['oid']."' ".$ssearch." ";
				$qry  .= "		and c.dupe!=1 ";
				$qry  .= "		and c.finan_from = 0 ";
				$qry  .= "		ORDER BY ".$order." ".$ascdesc.";";
			}
			else
			{
				exit;
			}
		}
		else
		{
			$qry   = "SELECT ";
			$qry  .= "		c.*,  ";
			$qry  .= "		f.*,  ";
			$qry  .= "		(select lname from security where securityid=f.assigned) as aslname,  ";
			$qry  .= "		(select fname from security where securityid=f.assigned) as asfname,  ";
			$qry  .= "		(select substring(slevel,13,13) from security where securityid=c.securityid) as secenable,  ";
			$qry  .= "		(select lname from security where securityid=c.securityid) as srlname,  ";
			$qry  .= "		(select fname from security where securityid=c.securityid) as srfname  ";
			$qry  .= "FROM ";
			$qry  .= "		cinfo as c ";
			$qry  .= "INNER JOIN ";
			$qry  .= "		tfinan_detail as f ";
			$qry  .= "ON ";
			$qry  .= "		c.cid=f.cid ";
			$qry  .= "WHERE ";
			$qry  .= "		c.finan_from='".$_SESSION['officeid']."' ".$ooid." ".$finansrc." ".$ssearch."";
			$qry  .= "		AND c.dupe!=1 ";
			
			if (valid_date($_POST['d1']) && valid_date($_POST['d2']))
			{
				if (strtotime($_POST['d1']) < strtotime($_POST['d2']))
				{
					if (isset($_POST['dfield']) && $_POST['dfield']=="f.frecdate")
					{
						$qry  .= "		AND f.frecdate BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']." 23:59' ";
					}
					elseif (isset($_POST['dfield']) && $_POST['dfield']=="f.cbddate")
					{
						$qry  .= "		AND f.cbddate BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']." 23:59' ";
					}
					else
					{
						$qry  .= "		AND f.recdate BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']." 23:59' ";
					}
				}
			}
			
			if (isset($_POST['showclosed']) && $_POST['showclosed']==1) //Show No Closed
			{
				$qry  .= "		AND f.financlose='0' ";
			}
			elseif (isset($_POST['showclosed']) && $_POST['showclosed']==2) //Show Only Closed
			{
				$qry  .= "		AND f.financlose='1' ";
			}
			
			if ($_SESSION['llev'] < 6) //Restrict Result Returned is Lead Level is less than 6
			{
				$qry  .= "		AND f.assigned='".$_SESSION['securityid']."' ";
			}
			else
			{
				if (isset($_POST['assigned']) && $_POST['assigned'])
				{
					$qry  .= "		AND f.assigned='".$_POST['assigned']."' ";	
				}
			}
			
			$qry  .= "		ORDER BY ".$order." ".$ascdesc.";";
		}
		//$qtext="";
	}


	if ($_SESSION['securityid']==2699999999999)
	{
		echo $qry."<br>";
	}
	
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//echo "BEFORE: ".$_SESSION['tqry']."<br>";

	$_SESSION['tqry']=$qry;

	//echo "AFTER: ".$_SESSION['tqry']."<br>";

	//echo $nrows."<br>";
	if ($nrows < 1)
	{
		echo "<table align=\"center\" width=\"950px\">\n";
		
		//echo $qtext;
		
		echo "   <tr>\n";
		//echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      <td class=\"gray\">\n";
		echo "         <b>Your search did not return any results.</b>\n";
		echo "      </td>\n";
		//echo "   </form>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		/*echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\">".$qtext."</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";*/
		echo "	</tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                     <tr>\n";
		echo "               			<td align=\"left\" class=\"ltgray_und\">\n";
		echo "								</td>\n";
		echo "                        <td align=\"left\" class=\"ltgray_und\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "                        <td align=\"right\" class=\"ltgray_und\"><b>Contact</b> Color Codes:</td>\n";
		echo "                        <td align=\"center\" class=\"wh_und\" width=\"100\"><b>Attended</b></td>\n";
		echo "                        <td align=\"center\" class=\"tan_und\" width=\"100\"><b>Lead/Estimate</b></td>\n";
		echo "                        <td align=\"center\" class=\"magenta_und\" width=\"100\"><b>Call Back</b></td>\n";
		echo "                        <td align=\"center\" class=\"grn_und\" width=\"100\"><b>Closed</b></td>\n";
		echo "                        <td align=\"center\" class=\"blu_und\" width=\"100\"><b>Dig</b></td>\n";
		//echo "                        <td align=\"center\" class=\"pnk_und\" width=\"100\"><b>Reason NC</b></td>\n";
		echo "                        <td align=\"center\" class=\"yel_und\" width=\"100\"><b>Unattended 7+</b></td>\n";
		echo "                     </tr>\n";
		echo "                   </table>\n";
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\">\n";
		echo "         <table width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                  	<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Last Name</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>First Name</b></td>\n";
		//echo "                     	<td class=\"ltgray_und\" align=\"left\"><b>Phone</b></td>\n";
		echo "                     		<td class=\"ltgray_und\" align=\"left\"><b>City</b></td>\n";
		echo "                     		<td class=\"ltgray_und\" align=\"left\"><b>Zip</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>SalesRep</b></td>\n";
		echo "                  	   <td class=\"ltgray_und\" align=\"left\"><b>Contact Off</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Stage</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Contract Dt</b></td>\n";
		//echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Finance Dt</b></td>\n";
		
		if (isset($_POST['dfield']) && $_POST['dfield']=="f.frecdate")
		{
			echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Fin Rec'd</b></td>\n";
		}
		else
		{
			echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Fin Ass'd</b></td>\n";
		}
		
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Fin Rep</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Fin Src</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Fin Stat</b></td>\n";
		//echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Contact Dt</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Call Back</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Disclose Dt</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Status Dt</b></td>\n";
		echo "            	         <td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "            	         <td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "            	         <td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "            	         <td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "                  	</tr>\n";

		$age30=2592000; 	//30 Days
		$age15=1296000; 	//15 Days
		$age07=604800; 	// 7 Days
		$age01=86400; 		// 1 Day
		$ts_tdate=getdate();
		$lcnt=0;
		while($row=mssql_fetch_array($res))
		{
			$tbg="wh_und";
			$nrowsA	=0;
			$nrowH	=0;

			$qryB = "SELECT jobid,njobid,digdate FROM jobs WHERE officeid='".$row['officeid']."' and njobid='".$row['njobid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			$nrowB= mssql_num_rows($resB);
			
			/*
			$qryC = "SELECT fname,lname,securityid,sidm,slevel FROM security WHERE securityid='".$row['securityid']."'";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			$secl=explode(",",$rowC['slevel']);
			*/
			
			if ($row['secenable']==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}

			$qryG = "SELECT officeid,name FROM offices WHERE officeid='".$row['officeid']."';";
			$resG = mssql_query($qryG);
			$rowG = mssql_fetch_array($resG);
			
			$qryI = "SELECT contractamt,contractdate FROM jdetail WHERE officeid='".$row['officeid']."' and jobid='".$row['jobid']."' and jadd=0;";
			$resI = mssql_query($qryI);
			$rowI = mssql_fetch_array($resI);
			$nrowI = mssql_num_rows($resI);
			
			if ($nrowI > 0)
			{
				$cdate	= date("m-d-Y", strtotime($rowI['contractdate']));	
			}
			else
			{
				$cdate	= "";
			}

			$uid  =md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];
			//if (in_array($row['securityid'],$acclist)||$_SESSION['llev'] >= 5)
			//{
					//$fdate = date("m/d/Y", strtotime($row['finan_date']));
					
					if (!empty($row['frecdate']) && strtotime($row['frecdate']) > strtotime('12/1/06'))
					{
						$frdate = date("m/d/Y", strtotime($row['frecdate']));
					}
					else
					{
						$frdate = "";
					}
					
					if (!empty($row['disclosedate']) && strtotime($row['disclosedate']) > strtotime('12/1/06'))
					{
						$ddate = date("m/d/Y", strtotime($row['disclosedate']));
					}
					else
					{
						$ddate = "";
					}
					
					if (!empty($row['finan_date'])||$row['finan_date']!="")
					{
						$ts_odate=strtotime($row['finan_date']);
						$odate = date("m-d-Y", strtotime($row['finan_date']));
					}
					else
					{
						$ts_odate=0;
						$odate = "";
					}
					
					if (!empty($row['recdate']) && strtotime($row['recdate']) > strtotime('12/1/06'))
					{
						$ts_rdate=strtotime($row['recdate']);
						$rdate = date("m-d-Y", strtotime($row['recdate']));
					}
					else
					{
						$ts_rdate=0;
						$rdate = "";
					}

					if (isset($row['lupdate']) && strtotime($row['lupdate']) >= strtotime('1/1/2000'))
					{
						$ts_udate=strtotime($row['lupdate']);
						$sdate = date("m/d/Y", strtotime($row['lupdate']));
					}
					else
					{
						$ts_udate=0;
						$sdate = "";
					}

					$udiff_date=$ts_tdate[0]-$ts_udate;
					$odiff_date=$ts_tdate[0]-$ts_odate;
					//$rdiff_date=$ts_tdate[0]-$ts_rdate;
					//$frdiff_date=$ts_tdate[0]-$ts_frdate;

					if (!empty($row['cbddate']) && strtotime($row['cbddate']) >= strtotime('1/1/2000'))
					{
						$cb_date= strtotime($row['cbddate']) - time();
						$cbdate = date("m-d-Y", strtotime($row['cbddate']));
					}
					else
					{
						$cb_date=0;
						$cbdate = "";
					}

					$hdate = str_pad($row['hold_mo'],2,"0",STR_PAD_LEFT)."/".str_pad($row['hold_da'],2,"0",STR_PAD_LEFT)."/".$row['hold_yr'];
					$ts_hdate=strtotime($hdate);
					$hdiff_date=$ts_hdate-$ts_tdate[0];
					
					/*if (isset($_POST['dfield']) && $_POST['dfield']=="f.frecdate")
					{
						$vdiff_date=$frdiff_date;
					}
					else
					{
						$vdiff_date=$rdiff_date;
					}*/

					if ($row['dupe']==1)
					{
						$tbg="red_und";
					}
					elseif (isset($rowB['digdate']) && strtotime($rowB['digdate']) >= strtotime('1/1/2000'))
					{
						$tbg="blu_und";
					}
					elseif (isset($row['financlose']) && $row['financlose']==1)
					{
						$tbg="grn_und";
					}
					elseif ($cb_date > 0 && $cb_date <= $age07)
					{
						$tbg="magenta_und";
					}
					elseif ($row['jobid'] === "0" && $row['njobid'] === "0")
					{
						$tbg="tan_und";
					}
					else
					{
						if ($ts_udate == 0)
						{
							if ($odiff_date > $age07)
							{
								$tbg="yel_und";
							}
							else
							{
								$tbg="wh_und";
							}
						}
						elseif ($udiff_date > $age07)
						{
							$tbg="yel_und";
						}
						else
						{
							if ($row['appt_mo']==date("n") && $row['appt_da']==date("j") && $row['appt_yr']==date("Y"))
							{
								//$tbg="grn_und";
								$tbg="wh_und";
							}
							else
							{
								$tbg="wh_und";
							}
						}
					}

					/*
					if ($nrowH!=0 && $rowH['reasnotclosed']!=0)
					{
						$tbg="pnk_und";
					}
					*/

					if ($row['cconph']=="hm")
					{
						$cphone=$row['chome'];
					}
					elseif ($row['cconph']=="wk")
					{
						$cphone=$row['cwork'];
					}
					elseif ($row['cconph']=="ce")
					{
						$cphone=$row['ccell'];
					}
					else
					{
						$cphone="";
					}

					// Display Lead Trigger
					if (!empty($_POST['d1']) && !empty($_POST['d2']))
					{
						// If a Date Range search is used
						$show=1;
					}
					elseif (!empty($_SESSION['d1']) && !empty($_SESSION['d2']))
					{
						// If a Date Range search is used
						$show=1;
					}
					/*elseif ($row['hold']==1)
					{
						// For any CallBacks
						if ($hdiff_date < $age15 && $ts_hdate >= ($ts_tdate[0]-$age01))
						{
							$show=1;
						}
						elseif (isset($_POST['showhold']) && $_POST['showhold']==1)
						{
							$show=1;
						}
						else
						{
							$show=0;
						}
					}*/
					elseif ($hdiff_date > $age30 && $udiff_date > $age30 && !isset($_POST['showaged']))
					{
						$show=1;
					}
					else
					{
						$show=1;
					}

					//$show=1;
					if ($show!=0)
					{
						$lcnt++;
						echo "                  <tr>\n";
						echo "                     <td class=\"".$tbg."\" align=\"center\">".$lcnt."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$row['clname']."</b></td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$row['cfname']."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$row['scity']."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$row['szip1']."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\"><font class=\"".$fstyle."\">".$row['srlname'].", ".substr($row['srfname'],0,1)."</font></td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$rowG['name']."</td>\n";
						
						if (isset($rowB['digdate']) && strtotime($rowB['digdate']) >= strtotime('1/1/2000'))
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\">Dig</td>\n";
						}
						else
						{
							if ($row['njobid']!="0")
							{
								echo "                     <td class=\"".$tbg."\" align=\"center\">Job</td>\n";
							}
							elseif ($row['jobid']!="0")
							{
								echo "                     <td class=\"".$tbg."\" align=\"center\">Contract</td>\n";
							}
							/*
							elseif ($row['estid']!=0)
							{
								echo "                     <td class=\"".$tbg."\" align=\"center\">Estimate</td>\n";
							}
							*/
							else
							{
								echo "                     <td class=\"".$tbg."\" align=\"center\">Lead/Est</td>\n";
							}
						}
						echo "                     <td class=\"".$tbg."\" align=\"center\">".$cdate."</td>\n";
						
						if (isset($_POST['dfield']) && $_POST['dfield']=="f.frecdate")
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\">".$frdate."</td>\n";
						}
						else
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\">".$rdate."</td>\n";
						}
						
						if ($row['assigned']!=0)
						{
							echo "                     <td class=\"".$tbg."\" align=\left\">".$row['aslname'].", ".$row['asfname']."</td>\n";
						}
						else
						{
							echo "                     <td class=\"".$tbg."\" align=\left\"></td>\n";
						}

						if ($row['finan_src']==4)
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\">BH Finance</td>\n";
						}
						elseif ($row['finan_src']==3)
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\">Cash</td>\n";
						}
						elseif ($row['finan_src']==2)
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\">Cust Finan</td>\n";
						}
						elseif ($row['finan_src']==1)
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\">Winners</td>\n";
						}
						else
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\">&nbsp</td>\n";
						}

						if ($nrowH!=0)
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\" title=\"".$rowHa['descrip']."\">".$rowHa['rcode']."</td>\n";
						}
						else
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\"></td>\n";
						}
						
						echo "                     	<td class=\"".$tbg."\" align=\"center\">".$cbdate."</td>\n";
						echo "								<td class=\"".$tbg."\" align=\"center\">".$ddate."</td>\n";
						echo "								<td class=\"".$tbg."\" align=\"center\">".$sdate."</td>\n";
						//echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
						echo "							<td class=\"".$tbg."\" align=\"center\">\n";
						echo "                        <form method=\"POST\">\n";
						echo "									<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
						
						if ($nrowH == 0)
						{
							echo "									<input type=\"hidden\" name=\"call\" value=\"view_fin_detail\">\n";
							echo "									<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
							echo "									<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
							echo "									<input type=\"hidden\" name=\"oid\" value=\"".$row['officeid']."\">\n";
							echo "									<input type=\"hidden\" name=\"foid\" value=\"".$_SESSION['officeid']."\">\n";
							//echo "                           		<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Finance Detail\">\n";
							echo "									<input class=\"transnb_button\" type=\"image\" src=\"images/money.png\" title=\"Finance Detail\">\n";
						}
						else
						{
							echo "									<input type=\"hidden\" name=\"call\" value=\"view_fin_detail\">\n";
							echo "									<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
							echo "									<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
							echo "									<input type=\"hidden\" name=\"oid\" value=\"".$row['officeid']."\">\n";
							echo "									<input type=\"hidden\" name=\"foid\" value=\"".$_SESSION['officeid']."\">\n";
							echo "									<input class=\"transnb_button\" type=\"image\" src=\"images/money.png\" title=\"Finance Detail\">\n";
						}
						
						echo "                        </form>\n";
						echo "                     </td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"center\">\n";
						echo "                        <form method=\"POST\">\n";
						echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
						echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
						echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
						echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
						echo "							 <input type=\"hidden\" name=\"oid\" value=\"".$row['officeid']."\">\n";
						echo "							 <input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
						echo "							 <input type=\"hidden\" name=\"foid\" value=\"".$_SESSION['officeid']."\">\n";
						echo "							 <input type=\"hidden\" name=\"salesrep\" value=\"".$row['officeid'].":".$row['securityid']."\">\n";
						echo "									<input class=\"transnb_button\" type=\"image\" src=\"images/user_suit.png\" title=\"Contact Information\">\n";
						echo "                        </form>\n";
						echo "                     </td>\n";
						echo "							<td class=\"".$tbg."\" align=\"center\">\n";
						echo "                        <form method=\"POST\">\n";
						echo "									<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
						echo "									<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
						echo "									<input type=\"hidden\" name=\"rcall\" value=\"".$_POST['call']."\">\n";
						echo "									<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
						echo "									<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
						echo "									<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
						echo "									<input type=\"hidden\" name=\"fofficeid\" value=\"".$_SESSION['officeid']."\">\n";
						echo "									<input class=\"transnb_button\" type=\"image\" src=\"images/comments.png\" title=\"Comments\">\n";
						echo "                        </form>\n";
						echo "                     </td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"center\">".$lcnt."</td>\n";
						echo "                  </tr>\n";
					}
			//}
		}

		echo "                  </table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function cform()
{
	error_reporting(E_ALL);
	$officeid =$_SESSION['officeid'];
	$dates	=dateformat();
	$uid	=md5(session_id().time()).".".$_SESSION['securityid'];
	$acclist=explode(",",$_SESSION['aid']);
	$curryr	=date("Y");
	$futyr 	=$curryr+1;

	$qryA = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);
	$nrowsA = mssql_num_rows($resA);
	
	//echo $qryA."<br>";

	if ($_SESSION['llev'] >= 5)
	{
		$qryB = "SELECT securityid,officeid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}
	elseif ($_SESSION['llev'] < 5)
	{
		$qryB = "SELECT securityid,officeid,fname,lname,slevel FROM security WHERE securityid='".$_SESSION['securityid']."';";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[0]!=0)
	{
		$qryD = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resD = mssql_query($qryD);

		$qryE = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resE = mssql_query($qryE);
	}

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=2 AND statusid!=0 ORDER by name ASC;";
	$resG = mssql_query($qryG);
	
	$qryH = "SELECT officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resH = mssql_query($qryH);
	$rowH = mssql_fetch_array($resH);
	$nrowH = mssql_num_rows($resH);
	
	$qryI  = "select ";
	$qryI .= "	s.officeid, ";
	$qryI .= "	s.securityid, ";
	$qryI .= "	s.lname, ";
	$qryI .= "	s.fname, ";
	$qryI .= "	o.officeid, ";
	$qryI .= "	o.name, ";
	$qryI .= "	(select 'Lead Admin') as title ";
	$qryI .= "from ";
	$qryI .= "	offices as o ";
	$qryI .= "inner join ";
	$qryI .= "	security as s ";
	$qryI .= "on  ";
	$qryI .= "	o.officeid=s.officeid ";
	$qryI .= "where ";
	$qryI .= "	o.finan_from='".$_SESSION['officeid']."' ";
	$qryI .= "	and o.am=s.securityid ";
	$qryI .= "	and substring(slevel,13,13)!=0 ";
	$qryI .= "order by ";
	$qryI .= "	o.name,s.lname ";
	$qryI .= "asc ";
	$resI = mssql_query($qryI);
	$nrowI= mssql_num_rows($resI);
	
	//echo $qryI."<br>";
	
	$qryJ  = "select ";
	$qryJ .= "	s.officeid, ";
	$qryJ .= "	s.securityid, ";
	$qryJ .= "	s.lname, ";
	$qryJ .= "	s.fname, ";
	$qryJ .= "	o.officeid, ";
	$qryJ .= "	o.name ";
	$qryJ .= "from ";
	$qryJ .= "	offices as o ";
	$qryJ .= "inner join ";
	$qryJ .= "	security as s ";
	$qryJ .= "on  ";
	$qryJ .= "	o.officeid=s.officeid ";
	$qryJ .= "where ";
	$qryJ .= "	 o.officeid=".$_SESSION['officeid']." ";
	$qryJ .= "	 and substring(s.slevel,13,13)!=0 ";
	$qryJ .= "	 and o.finan_off=1 ";
	$qryJ .= "order by ";
	$qryJ .= "	o.name,s.lname ";
	$qryJ .= "asc ";
	$resJ = mssql_query($qryJ);
	$nrowJ= mssql_num_rows($resJ);

	echo "<table width=\"85%\" align=\"center\" border=0>\n";
	echo "	<tr>\n";
	echo "	<td>\n";
	echo "		<table width=\"100%\" align=\"center\">\n";
	echo "			<tr>\n";
	echo "				<td>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"add\">\n";
	//echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "         <input type=\"hidden\" name=\"recdate\" value=\"".$dates[1]."\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "         <input type=\"hidden\" name=\"comments\" value=\"\">\n";
	//echo "         <input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
	//echo "          <input type=\"hidden\" name=\"officeid\" value=\"".$rowA[0]."\">\n";
	echo "					<table border=\"0\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "								<table border=\"0\" width=\"100%\">\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Contact Entry:</b><font color=\"blue\"> * Required Entry</font></td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\" valign=\"bottom\" align=\"right\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\"><b>Date:</b>\n";
	echo "													<td class=\"gray\" align=\"left\">".$dates[0]."</td>\n";
	echo "													<td class=\"gray\" align=\"right\"><b>Office Rep: </b></td>\n";
	echo "													<td class=\"gray\" align=\"left\">\n";
	
	if ($nrowI > 0)
	{
		echo "														<select name=\"salesrep\">\n";
		
		//if ($_SESSION['llev'] >= 6)
		//{
			echo "													<option value=\"0:0\" >Select Office Rep...</option>\n";
		//}
		
		$x=0;
		while ($rowI = mssql_fetch_array($resI))
		{
			if ($x==0 || $x!=$rowI['officeid'])
			{
				echo "													<optgroup class=\"plain\" label=\"".$rowI['name']."\">\n";
			}
			
			if ($_SESSION['llev'] >= 6)
			{
				if ($_SESSION['securityid']==$rowI['securityid'])
				{
					echo "														<option value=\"".$rowI['officeid'].":".$rowI['securityid']."\" SELECTED>".ucfirst($rowI['lname']).", ".ucfirst($rowI['fname'])."</option>\n";
				}
				else
				{
					echo "														<option value=\"".$rowI['officeid'].":".$rowI['securityid']."\" >".ucfirst($rowI['lname']).", ".ucfirst($rowI['fname'])."</option>\n";
				}
			}
			else
			{
				if ($_SESSION['securityid']==$rowI['securityid'])
				{
					echo "														<option value=\"".$rowH['officeid'].":".$rowI['securityid']."\" SELECTED>".ucfirst($rowI['lname']).", ".ucfirst($rowI['fname'])."</option>\n";
				}
				else
				{
					echo "														<option value=\"".$rowH['officeid'].":".$rowI['securityid']."\" >".ucfirst($rowI['lname']).", ".ucfirst($rowI['fname'])."</option>\n";
				}
			}
			
			$x=$rowI['officeid'];
		}
		
		echo "														</select>\n";
	}
	
	echo "													</td>\n";
	echo "													<td class=\"gray\" align=\"right\"><b>Assigned: </b></td>\n";
	echo "													<td class=\"gray\" align=\"left\">\n";
	
	if ($nrowJ > 0)
	{
		if ($_SESSION['llev'] >= 5)
		{
			echo "														<select name=\"assigned\">\n";
			echo "															<option value=\"0\" >Unassigned</option>\n";
			
			$y=0;
			while ($rowJ = mssql_fetch_array($resJ))
			{
				if ($y==0 || $y!=$rowJ['officeid'])
				{
					echo "													<optgroup class=\"plain\" label=\"".$rowJ['name']."\">\n";
				}
				
				if ($_SESSION['securityid']==$rowJ['securityid'])
				{
					echo "														<option value=\"".$rowJ['officeid'].":".$rowJ['securityid']."\" SELECTED>".ucfirst($rowJ['lname']).", ".ucfirst($rowJ['fname'])."</option>\n";
				}
				else
				{
					echo "														<option value=\"".$rowJ['officeid'].":".$rowJ['securityid']."\" >".ucfirst($rowJ['lname']).", ".ucfirst($rowJ['fname'])."</option>\n";
				}
				
				$y=$rowJ['officeid'];
			}
			
			echo "														</select>\n";
		}
		elseif ($_SESSION['llev'] < 5)
		{
			$rowB = mssql_fetch_array($resB);
			echo "         <input type=\"hidden\" name=\"assigned\" value=\"".$rowB['officeid'].":".$rowB['securityid']."\">\n";
			echo $rowB['lname'].", ".$rowB['fname'];
		}
	}
	
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" colspan=\"2\" valign=\"bottom\" NOWRAP><b>Customer:</b></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP><font color=\"blue\">* First Name</font></td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"cfname\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP><font color=\"blue\">* Last Name</font></td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"clname\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP><font color=\"blue\">* Home Phone</font></td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>Work Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>Cell Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>Fax</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>Best Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP>\n";
	echo "														<select name=\"cconph\">\n";
	echo "															<option value=\"hm\">Home</option>\n";
	echo "															<option value=\"wk\">Work</option>\n";
	echo "															<option value=\"ce\">Cell</option>\n";
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP><font color=\"blue\">* E-Mail</font></td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" name=\"cemail\" size=\"30\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>Contact Time</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"ccontime\"></td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Current Address:</b></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP><font color=\"blue\">* Street</font></td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"caddr1\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP><font color=\"blue\">* City</font></td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"ccity\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP><font color=\"blue\">* Zip</font></td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>Cnty/Twnshp</td>\n";
	echo "													<td class=\"gray\" NOWRAP>\n";

	if ($rowC[0]==0)
	{
		echo "												<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"ccounty\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "												<select name=\"ccounty\">\n";
		while ($rowD = mssql_fetch_row($resD))
		{
			echo "												<option value=\"".$rowD[0]."\">".$rowD[2]."</option>\n";
		}
		echo "												</select>\n";
	}
	else
	{
		echo "											<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"ccounty\">\n";
	}

	echo "												Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"cmap\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>Street:</td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>City:</td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>Zip:</td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" NOWRAP>Cnty/Twnshp:</td>\n";
	echo "													<td class=\"gray\" NOWRAP>\n";

	if ($rowC[0]==0)
	{
		echo "													<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "													<select name=\"scounty\">\n";
		while ($rowE = mssql_fetch_row($resE))
		{
			echo "														<option value=\"".$rowE[0]."\">".$rowE[2]."</option>\n";
		}
		echo "														</select>\n";
	}
	else
	{
		echo "													<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\">\n";
	}

	echo "											Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"smap\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"left\" valign=\"top\">\n";
	echo "											<table border=0 class=\"outer\" width=\"100%\" height=\"100\">\n";
	echo "                           			<tr>\n";
	echo "													<td class=\"gray\" align=\"left\" valign=\"top\"><b>Source</b></td>\n";
	echo "                           			</tr>\n";
	echo "                     					<tr>\n";
	echo "                        					<td class=\"gray\" valign=\"top\">\n";
	echo "                           					<table border=\"0\" width=\"100%\">\n";
	echo "                                             <input type=\"hidden\" name=\"appt_mo\" value=\"00\">\n";
	echo "                                             <input type=\"hidden\" name=\"appt_da\" value=\"00\">\n";
	echo "                                             <input type=\"hidden\" name=\"appt_yr\" value=\"0000\">\n";
	echo "                                             <input type=\"hidden\" name=\"appt_hr\" value=\"00\">\n";
	echo "                                             <input type=\"hidden\" name=\"appt_mn\" value=\"00\">\n";
	echo "                                             <input type=\"hidden\" name=\"appt_pa\" value=\"1\">\n";
	echo "                                             	<tr>\n";
	echo "                                             		<td align=\"right\"><font color=\"blue\">* Contact Source</font></td>\n";
	echo "                                             		<td align=\"left\">\n";
	echo "                                             			<select name=\"source\">\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		echo "                                             													<option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
	}

	echo "                                             			</select>\n";
	echo "                                            			</td>\n";
	echo "                                            		</tr>\n";
	echo "                                             	<tr>\n";
	echo "                                             		<td align=\"right\"><font color=\"blue\">* Finance Source</font></td>\n";
	echo "                                             		<td align=\"left\">\n";
	echo "                                             			<select name=\"finansrc\">\n";
	//echo "						                                    	<option value=\"1\">Winners</option>\n";
	echo "						                                    	<option value=\"2\">Cust Finan</option>\n";
	echo "						                                    	<option value=\"3\">Cash</option>\n";
	echo "						                                    	<option value=\"4\">BH Finance</option>\n";
	echo "                                             			</select>\n";
	echo "                                            			</td>\n";
	echo "                                            		</tr>\n";
	echo "                                            	</table>\n";
	echo "                                 			</td>\n";
	echo "                                 		</tr>\n";
	echo "                                 	</table>\n";
	echo "                                 </td>\n";
	echo "                                 <td colspan=\"2\" align=\"right\" valign=\"top\">\n";
	echo "                                             							<table border=\"0\" class=\"outer\" width=\"100%\" height=\"100\">\n";
	echo "                                             								<tr>\n";
	echo "                                             									<td class=\"gray\" valign=\"top\"><b>Comments/Directions:</b></td>\n";
	echo "                                             								</tr>\n";
	echo "                                             								<tr valign=\"top\">\n";
	echo "                                             									<td class=\"gray\" align=\"center\">&nbsp;\n";
	echo "																									<textarea name=\"comments\" rows=\"5\" cols=\"50\"></textarea>\n";
	echo "																								</td>\n";
	echo "                                             								</tr>\n";
	echo "                                             							</table>\n";
	echo "                                             						</td>\n";
	echo "                                             					</tr>\n";
	echo "                                             				</table>\n";
	echo "                                             			</td>\n";
	echo "                                             		</tr>\n";
	//echo "                                             	</table>\n";
	echo "									<tr>\n";
	echo "										<td align=\"left\" valign=\"top\">\n";
	echo "											<table border=0 class=\"outer\" width=\"100%\">\n";
	echo "                           			<tr>\n";
	echo "													<td class=\"gray\" colspan=\"2\" align=\"left\" valign=\"top\"><b>Privacy Policy</b></td>\n";
	echo "                           			</tr>\n";
	echo "                           			<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"center\">\n";
	echo "														<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\">\n";
	echo "														<input type=\"hidden\" name=\"opt2\" value=\"0\">\n";
	echo "														<input type=\"hidden\" name=\"opt3\" value=\"0\">\n";
	echo "														<input type=\"hidden\" name=\"opt4\" value=\"0\">\n";
	echo "													</td>\n";
	echo "													<td class=\"gray\" align=\"left\">Customer does not wish to receive any future information about updates, special offers, or other communications regarding Blue Haven-related products, supplies, or services.</td>\n";	
	echo "                           			</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "                           </tr>\n";
	echo "								</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	<td valign=\"top\" align=\"left\">\n";
	echo "		<table border=0>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add Lead\">\n";
	echo "				</td>\n";
	echo "			</form>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function cform_view($cid)
{
	error_reporting(E_ALL);
	//show_post_vars();
	
	unset($_SESSION['ifcid']); // Security Setting for embedded frames and AJAX
	
	//echo $tcid."<br>";
	$acclist=explode(",",$_SESSION['aid']);
	$assto	=explode(":",$_POST['salesrep']);
	$oid=$assto[0];
	
	if (!isset($cid))
	{
		$cid=$_POST['cid'];
	}
	
	if (empty($_POST['uid']))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error occured.\n";
		exit;
	}

	if (empty($cid))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> You must provide a Valid Customer ID number.\n";
		exit;
	}

	$dates	=dateformat();

	$qryA = "SELECT officeid,name,stax,enest,finan_off FROM offices WHERE officeid='".$oid."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	$nrowsA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	//$qryAa = "SELECT officeid,name,stax,enest FROM offices WHERE officeid='".$oid."';";
	//$resAa = mssql_query($qryAa);
	//$nrowsAa = mssql_num_rows($resAa);

	if ($_SESSION['llev'] >= 5)
	{
		$qryB = "SELECT securityid,fname,lname,sidm,slevel,assistant FROM security WHERE officeid='".$oid."' ORDER BY lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}
	elseif ($_SESSION['llev'] < 5)
	{
		$qryB = "SELECT securityid,fname,lname,slevel FROM security WHERE securityid='".$_SESSION['securityid']."';";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT stax,enest,finan_off,finan_from FROM offices WHERE officeid='".$oid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[0]!=0)
	{
		$qryD = "SELECT * FROM taxrate WHERE officeid='".$oid."' ORDER BY city ASC;";
		$resD = mssql_query($qryD);

		$qryE = "SELECT * FROM taxrate WHERE officeid='".$oid."' ORDER BY city ASC;";
		$resE = mssql_query($qryE);
	}

	$qryF = "SELECT * FROM cinfo WHERE officeid='".$oid."' AND cid='".$cid."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);
	
	//echo $qryF."<br>";

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=1 AND access!=0 ORDER by name ASC;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT * FROM leadstatuscodes WHERE statusid > 1 and active=2 ORDER by name ASC;";
	$resH = mssql_query($qryH);

	//$qryI = "SELECT securityid,fname,lname,sidm FROM security WHERE officeid='".$oid."' AND securityid='".$rowF['securityid']."';";
	$qryI = "SELECT s.securityid,s.fname,s.lname,s.sidm,(select name from offices where officeid=s.officeid) as oname FROM security as s WHERE s.securityid='".$rowF['securityid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryJ = "SELECT securityid,sidm,assistant FROM security WHERE officeid='".$oid."' AND securityid='".$rowF['sidm']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);

	$qryL = "SELECT * FROM chistory WHERE officeid='".$oid."' AND custid='".$cid."' ORDER BY mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);
	
	$qryM = "SELECT officeid,name,finan_off FROM offices WHERE officeid='".$oid."';";
	$resM = mssql_query($qryM);
	$rowM = mssql_fetch_array($resM);
	
	$qryN  = "select ";
	$qryN .= "	s.officeid, ";
	$qryN .= "	s.securityid, ";
	$qryN .= "	s.lname, ";
	$qryN .= "	s.fname, ";
	$qryN .= "	s.slevel, ";
	$qryN .= "	o.officeid, ";
	$qryN .= "	o.name ";
	$qryN .= "from ";
	$qryN .= "	offices as o ";
	$qryN .= "inner join ";
	$qryN .= "	security as s ";
	$qryN .= "on  ";
	$qryN .= "	o.officeid=s.officeid ";
	$qryN .= "where ";
	$qryN .= "	o.finan_off=1 ";
	$qryN .= "	or o.finan_from=".$_SESSION['officeid']." ";
	$qryN .= "	and o.am=s.securityid ";
	$qryN .= "order by ";
	$qryN .= "	o.name asc,substring(s.slevel,13,13) desc,s.lname asc;";
	$resN = mssql_query($qryN);
	$nrowN= mssql_num_rows($resN);
	
	$qryO  = "select ";
	$qryO .= "	s.officeid, ";
	$qryO .= "	s.securityid, ";
	$qryO .= "	s.lname, ";
	$qryO .= "	s.fname, ";
	$qryO .= "	s.slevel, ";
	$qryO .= "	o.officeid, ";
	$qryO .= "	o.name as oname ";
	$qryO .= "from ";
	$qryO .= "	offices as o ";
	$qryO .= "inner join ";
	$qryO .= "	security as s ";
	$qryO .= "on  ";
	$qryO .= "	o.officeid=s.officeid ";
	$qryO .= "where ";
	$qryO .= "	s.securityid=(select assigned from tfinan_detail where cid=".$cid."); ";
	$resO = mssql_query($qryO);
	$rowO = mssql_fetch_array($resO);
	$nrowO= mssql_num_rows($resO);
	
	//echo $qryN."<br>";
	
	$adate = date("m-d-Y (g:i A)", strtotime($rowF['added']));
	$curryr=date("Y");
	$futyr =$curryr+2;

	if ($_SESSION['officeid']!=$oid)
	{
		$dis="DISABLED";
	}
	else
	{
		$dis="";
	}
	
	//if ($rowM['finan_off']!=1 && $rowC[3]!=$_SESSION['officeid'])
	//if ($rowM['finan_off']!=1)
	//{
	//	echo "<font color=\"red\"><b>ERROR!</b></font><br>You do not have appropriate Access to view this Information.\n";
	//	exit;
	//}

	$_SESSION['ifcid']=$rowF['cid'];
	$cmaplink=maplink($rowF['caddr1'],$rowF['ccity'],$rowF['cstate'],$rowF['czip1']);
	$smaplink=maplink($rowF['saddr1'],$rowF['scity'],$rowF['sstate'],$rowF['szip1']);
	echo "<table width=\"950px\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "		<table width=\"100%\" align=\"center\" border=0>\n";
	echo "   	<tr>\n";
	echo "      <td>\n";
	echo "         <table border=\"0\" width=\"100%\">\n";
	echo "         	<tr>\n";
	echo "            	<td>\n";
	echo "               	<table border=\"0\" width=\"100%\">\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"left\">\n";
	echo "               				<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                     			<tr>\n";
	//echo "                        			<td class=\"gray\" align=\"left\"><b>Contact Information</font></b> </td>\n";
	echo "                        			<td class=\"gray\" align=\"left\"><b>Contact Information</font></b> (".$rowF['cid'].")</td>\n";
	echo "                                 <td class=\"gray\" align=\"right\"></td>\n";
	echo "         	<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "				<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "                        			<td class=\"gray\" valign=\"bottom\" align=\"right\">&nbsp\n";
	
	$dtx="";
	$dis="";
	
	if (isset($_POST['csearch']) && $_POST['csearch']==1 || !isset($_SESSION['tqry']))
	{
		$dtx="Disabled. This feature currently only works in Contact Search.";
		$dis="DISABLED";	
	}
	
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search Results\" ".$dis." title=\"".$dtx."\"><br>\n";
	echo "					</td>\n";
	echo "				</form>\n";
	echo "                    				</tr>\n";
	echo "                    			</table>\n";
	echo "								</td>\n";
	echo "                    	</tr>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "          <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "          <input type=\"hidden\" name=\"call\" value=\"edit\">\n";
	echo "        	<input type=\"hidden\" name=\"uid\" value=\"".$_POST['uid']."\">\n";
	echo "         	<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "         	<input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
	echo "			<input type=\"hidden\" name=\"appt_mo\" value=\"".$rowF['appt_mo']."\">\n";
	echo "			<input type=\"hidden\" name=\"appt_da\" value=\"".$rowF['appt_da']."\">\n";
	echo "			<input type=\"hidden\" name=\"appt_yr\" value=\"".$rowF['appt_yr']."\">\n";
	echo "			<input type=\"hidden\" name=\"appt_hr\" value=\"".$rowF['appt_hr']."\">\n";
	echo "			<input type=\"hidden\" name=\"appt_mn\" value=\"".$rowF['appt_mn']."\">\n";
	echo "			<input type=\"hidden\" name=\"appt_pa\" value=\"".$rowF['appt_pa']."\">\n";
	echo "			<input type=\"hidden\" name=\"hold_mo\" value=\"".$rowF['hold_mo']."\">\n";
	echo "			<input type=\"hidden\" name=\"hold_da\" value=\"".$rowF['hold_da']."\">\n";
	echo "			<input type=\"hidden\" name=\"hold_yr\" value=\"".$rowF['hold_yr']."\">\n";
	echo "			<input type=\"hidden\" name=\"stage\" value=\"".$rowF['stage']."\">\n";
	echo "         	<input type=\"hidden\" name=\"dupe\" value=\"0\">\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"right\" valign=\"bottom\">\n";
	echo "                           <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                           	<tr>\n";
	echo "                              	<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Date:</b>\n";
	echo "                                 <td class=\"gray\" align=\"left\" valign=\"bottom\">".$adate."</td>\n";
	echo "                                 <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Office Rep:</b>\n";

	if ($rowA['finan_off']==1)
	{
		if ($_SESSION['llev'] == 4) // Sales Manager List
		{
			echo "                                 	<select name=\"salesrep\">\n";
			
			$x=0;
			while ($rowN = mssql_fetch_array($resN))
			{
				if (in_array($rowN['securityid'],$acclist))
				{
					$slev=explode(",",$rowN['slevel']);
					if ($slev[6]==0)
					{
						$ostyle="fontred";
					}
					else
					{
						$ostyle="fontblack";
					}
					
					if ($x==0 || $x!=$rowN['officeid'])
					{
						echo "<optgroup class=\"plain\" label=\"".$rowN['name']."\">\n";
					}
					
					if ($rowF['securityid']==$rowN['securityid'])
					{
						echo "                                 	<option value=\"".$rowN['officeid'].":".$rowN['securityid']."\" class=\"".$ostyle."\" SELECTED>".$rowN['fname']." ".$rowN['lname']."</option>\n";
					}
					else
					{
						echo "                                 	<option value=\"".$rowN['officeid'].":".$rowN['securityid']."\" class=\"".$ostyle."\">".$rowN['fname']." ".$rowN['lname']."</option>\n";
					}
					$x=$rowN['officeid'];
				}
			}
			echo "                                 	</select>\n";
		}
		elseif ($_SESSION['llev'] >= 5) // General Manager List
		{
			echo "                                 	<select name=\"assignedto\">\n";
			
			$x=0;
			while ($rowN = mssql_fetch_array($resN))
			{
				$slev=explode(",",$rowN['slevel']);
				if ($slev[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}
	
				if ($x==0 || $x!=$rowN['officeid'])
				{
					echo "<optgroup class=\"plain\" label=\"".$rowN['name']."\">\n";
				}
	
				if ($rowF['securityid']==$rowN['securityid'])
				{
					echo "                                 	<option value=\"".$rowN['officeid'].":".$rowN['securityid']."\" class=\"".$ostyle."\" SELECTED>".$rowN['fname']." ".$rowN['lname']."</option>\n";
				}
				else
				{
					echo "                                 	<option value=\"".$rowN['officeid'].":".$rowN['securityid']."\" class=\"".$ostyle."\">".$rowN['fname']." ".$rowN['lname']."</option>\n";
				}
				$x=$rowN['officeid'];
			}
	
			echo "                                 	</select>\n";
		}
		else
		{
			echo "                                 ".$rowI['fname']." ".$rowI['lname']." - ".$rowI['oname']."<input type=\"hidden\" name=\"salesrep\" value=\"".$rowI['securityid']."\">\n";
		}
	}
	else
	{
		echo "                                 ".$rowI['fname']." ".$rowI['lname']." - ".$rowI['oname']."<input type=\"hidden\" name=\"salesrep\" value=\"".$rowI['securityid']."\">\n";
	}

	echo "                                 			</td>\n";
	echo "                                 			<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Assigned:</b>\n";
	
	if ($nrowO > 0)
	{
		echo "                                 ".$rowO['fname']." ".$rowO['lname']." - ".$rowO['oname']."<input type=\"hidden\" name=\"salesrep\" value=\"".$rowO['securityid']."\">\n";
	}
	else
	{
		echo "                                 None <input type=\"hidden\" name=\"salesrep\" value=\"0:0\">\n";
	}
	
	
	echo "                                 			</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" colspan=\"2\" valign=\"bottom\" NOWRAP><b>Customer:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>First Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"cfname\" value=\"".$rowF['cfname']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Last Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"clname\" value=\"".$rowF['clname']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Home Phone</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\" value=\"".$rowF['chome']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Work Phone</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\" value=\"".$rowF['cwork']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Cell Phone</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\" value=\"".$rowF['ccell']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Fax</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" value=\"".$rowF['cfax']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Best Phone</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP>\n";
	echo "												<select name=\"cconph\">\n";

	if ($rowF['cconph']=="hm")
	{
		echo "													<option value=\"hm\" SELECTED>Home</option>\n";
		echo "													<option value=\"wk\">Work</option>\n";
		echo "													<option value=\"ce\">Cell</option>\n";
	}
	elseif ($rowF['cconph']=="wk")
	{
		echo "													<option value=\"hm\">Home</option>\n";
		echo "													<option value=\"wk\" SELECTED>Work</option>\n";
		echo "													<option value=\"ce\">Cell</option>\n";
	}
	elseif ($rowF['cconph']=="ce")
	{
		echo "													<option value=\"hm\">Home</option>\n";
		echo "													<option value=\"wk\">Work</option>\n";
		echo "													<option value=\"ce\" SELECTED>Cell</option>\n";
	}

	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Email</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" name=\"cemail\" size=\"30\" value=\"".$rowF['cemail']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Contact Time</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"ccontime\" value=\"".$rowF['ccontime']."\"></td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Current Address:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Street</td>\n";

	if ($rowF['caddr1']==0)
	{
		echo "											<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"caddr1\"></td>\n";
	}
	else
	{
		echo "											<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"caddr1\" value=\"".$rowF['caddr1']."\"></td>\n";
	}

	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>City</td>\n";
	echo "											<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"ccity\" value=\"".$rowF['ccity']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" value=\"".$rowF['cstate']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Zip</td>\n";
	echo "											<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".$rowF['czip1']."\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\" value=\"".$rowF['czip2']."\"> ".$cmaplink."</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Cnty/Twnshp</td>\n";
	echo "											<td class=\"gray\" NOWRAP>\n";

	if ($rowC[0]==0)
	{
		echo "												<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"ccounty\" value=\"".$rowF['ccounty']."\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "												<select name=\"ccounty\">\n";
		while ($rowD = mssql_fetch_row($resD))
		{
			if ($rowD[0]==$rowF['ccounty'])
			{
				echo "												<option value=\"".$rowD[0]."\" SELECTED>".$rowD[2]."</option>\n";
			}
			else
			{
				echo "												<option value=\"".$rowD[0]."\">".$rowD[2]."</option>\n";
			}
		}
		echo "												</select>\n";
	}

	echo "												Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"cmap\" value=\"".$rowF['cmap']."\">\n";
	echo "												</td>\n";
	echo "											</tr>\n";

	if ($rowF['estid']==0)
	{
		echo "											<tr>\n";

		if ($rowF['ssame']==1)
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
		}
		else
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\"> Same as above</td>\n";
		}

		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Street:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\" value=\"".$rowF['saddr1']."\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>City:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Zip:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\"> ".$smaplink."</td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Cnty/Twnshp:</td>\n";
		echo "												<td class=\"gray\" NOWRAP>\n";

		if ($rowC[0]==0)
		{
			echo "													<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\" value=\"".$rowF['scounty']."\">\n";
		}
		elseif ($rowC[0]==1)
		{
			echo "													<select name=\"scounty\">\n";
			while ($rowE = mssql_fetch_row($resE))
			{
				if ($rowE[0]==$rowF['scounty'])
				{
					echo "												<option value=\"".$rowE[0]."\" SELECTED>".$rowE[2]."</option>\n";
				}
				else
				{
					echo "												<option value=\"".$rowE[0]."\">".$rowE[2]."</option>\n";
				}
			}
			echo "														</select>\n";
		}

		echo "											Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"smap\" value=\"".$rowF['smap']."\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
	}
	else
	{
		echo "											<tr>\n";

		if ($rowF['ssame']==1)
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED DISABLED> Same as above</td>\n";
			echo "<input type=\"hidden\" name=\"ssame\" value=\"1\">\n";
		}
		else
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" DISABLED> Same as above</td>\n";
			echo "<input type=\"hidden\" name=\"ssame\" value=\"0\">\n";
		}

		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Street:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\" value=\"".$rowF['saddr1']."\" DISABLED></td>\n";
		echo "<input type=\"hidden\" name=\"saddr1\" value=\"".$rowF['saddr1']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>City:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\" DISABLED> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\" DISABLED></td>\n";
		echo "<input type=\"hidden\" name=\"scity\" value=\"".$rowF['scity']."\"><input type=\"hidden\" name=\"sstate\" value=\"".$rowF['sstate']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Zip:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\" DISABLED>-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\" DISABLED> ".$smaplink."</td>\n";
		echo "<input type=\"hidden\" name=\"szip1\" value=\"".$rowF['szip1']."\"><input type=\"hidden\" name=\"szip2\" value=\"".$rowF['szip2']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Cnty/Twnshp:</td>\n";
		echo "												<td class=\"gray\" NOWRAP>\n";

		if ($rowC[0]==0)
		{
			echo "													<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\" value=\"".$rowF['scounty']."\" DISABLED>\n";
			echo "<input type=\"hidden\" name=\"scounty\" value=\"".$rowF['scounty']."\">\n";
		}
		elseif ($rowC[0]==1)
		{
			echo "													<select name=\"scounty\" DISABLED>\n";
			while ($rowE = mssql_fetch_row($resE))
			{
				if ($rowE[0]==$rowF['scounty'])
				{
					echo "												<option value=\"".$rowE[0]."\" SELECTED>".$rowE[2]."</option>\n";
				}
				else
				{
					echo "												<option value=\"".$rowE[0]."\">".$rowE[2]."</option>\n";
				}
			}
			echo "														</select>\n";
			echo "<input type=\"hidden\" name=\"scounty\" value=\"".$rowF['scounty']."\">\n";
		}

		echo "											Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"smap\" value=\"".$rowF['smap']."\" DISABLED>\n";
		echo "<input type=\"hidden\" name=\"smap\" value=\"".$rowF['smap']."\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
	}

	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"100\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" valign=\"top\"><b>Source</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"center\" valign=\"top\">\n";
	echo "												<table>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\" valign=\"top\"><b>Contact Source</b></td>\n";

	if ($rowF['source']==0)
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">bluehaven.com</td>\n";
		echo "         											<input type=\"hidden\" name=\"source\" value=\"0\">\n";
	}
	elseif ($rowF['source'] >= 1)
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";
		echo "                                             <select name=\"source\">\n";

		while ($rowH = mssql_fetch_array($resH))
		{
				if ($rowH['statusid']==$rowF['source'])
				{
					echo "                                             <option value=\"".$rowH['statusid']."\" SELECTED>".$rowH['name']."</option>\n";
				}
				else
				{
					echo "                                             <option value=\"".$rowH['statusid']."\">".$rowH['name']."</option>\n";
				}
		}

		echo "                                             </select>\n";
		echo "														</td>\n";
	}

	echo "													</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" valign=\"top\"><b>Finance Source</b></td>\n";
	echo "								<td align=\"left\">\n";
	echo "                                    <select name=\"finansrc\" title=\"Update the Finance Source\">\n";
	
	if (!isset($rowF['finan_src']) || $rowF['finan_src']==0)
	{
		echo "                                    	<option value=\"0\">Select...</option>\n";
		//echo "                                    	<option value=\"1\">Winners</option>\n";
		echo "                                    	<option value=\"2\">Cust Finan</option>\n";
		echo "                                    	<option value=\"3\">Cash</option>\n";
		echo "						           		<option value=\"4\">BH Finance</option>\n";
	}
	elseif ($rowF['finan_src']==1)
	{
		echo "                                    	<option value=\"1\" selected>Winners</option>\n";
		echo "                                    	<option value=\"2\">Cust Finan</option>\n";
		echo "                                    	<option value=\"3\">Cash</option>\n";
		echo "						           		<option value=\"4\">BH Finance</option>\n";
	}
	elseif ($rowF['finan_src']==2)
	{
		//echo "                                    	<option value=\"1\">Winners</option>\n";
		echo "                                    	<option value=\"2\" selected>Cust Finan</option>\n";
		echo "                                    	<option value=\"3\">Cash</option>\n";
		echo "						           		<option value=\"4\">BH Finance</option>\n";
	}
	elseif ($rowF['finan_src']==3)
	{
		//echo "                                    	<option value=\"1\">Winners</option>\n";
		echo "                                    	<option value=\"2\">Cust Finan</option>\n";
		echo "                                    	<option value=\"3\" selected>Cash</option>\n";
		echo "						           		<option value=\"4\">BH Finance</option>\n";
	}
	elseif ($rowF['finan_src']==4)
	{
		//echo "                                    	<option value=\"1\">Winners</option>\n";
		echo "                                    	<option value=\"2\">Cust Finan</option>\n";
		echo "                                    	<option value=\"3\">Cash</option>\n";
		echo "						           		<option value=\"4\" selected>BH Finance</option>\n";
	}
	
	echo "                                    </select>\n";
	echo "								</td>\n";
	
	echo "										</tr>\n";
	echo "												</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td align=\"right\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"100\">\n";
	echo "										<tr>\n";
	echo "											<td height=\"20px\" class=\"gray\" valign=\"top\"><b>Comments/Directions:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" valign=\"top\" align=\"center\">\n";
	echo "												<iframe src=\"subs/comments.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"right\"></iframe>\n";
	//echo "												<input type=\"hidden\" name=\"comments\" value=\"".$rowF['comments']."\">\n";
	echo "											</td>\n";
	
	/*
	echo "											<td class=\"gray\" valign=\"top\" align=\"center\">\n";

	if ($nrowL > 0)
	{
		// Comments Display Table
		echo "<input type=\"hidden\" name=\"comments\" value=\"".$rowF['comments']."\">\n";
		echo "<table align=\"center\" width=\"90%\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" class=\"ltgray_und\" width=\"20%\"><b>Date</b></td>\n";
		echo "      <td align=\"left\" class=\"ltgray_und\" width=\"20%\">&nbsp&nbsp<b>Name</b></td>\n";
		//echo "      <td align=\"left\" class=\"ltgray_und\" width=\"10%\"><b>Stage</b></td>\n";
		echo "      <td align=\"left\" class=\"ltgray_und\" width=\"60%\"><b>Comments</b></td>\n";
		echo "   </tr>\n";

		$mc=0;
		while ($rowL = mssql_fetch_array($resL))
		{
			if ($mc <= 3)
			{
				$qryLa = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowL['secid']."';";
				$resLa = mssql_query($qryLa);
				$rowLa = mssql_fetch_array($resLa);

				if ($rowL['act']=="leads")
				{
					$stage="Lead";
				}
				elseif ($rowL['act']=="est")
				{
					$stage="Estimate";
				}
				elseif ($rowL['act']=="contract")
				{
					$stage="Contract";
				}
				elseif ($rowL['act']=="jobs")
				{
					$stage="Job";
				}
				elseif ($rowL['act']=="mas")
				{
					$stage="MAS";
				}
				else
				{
					$stage="";
				}

				//$strarr=str_split($rowL['mtext'],30);

				echo "   <tr>\n";
				echo "      <td align=\"left\" valign=\"top\" class=\"wh_und\" NOWRAP>".$rowL['mdate']."</td>\n";
				echo "      <td align=\"left\" valign=\"top\" class=\"wh_und\" NOWRAP>&nbsp&nbsp".$rowLa['fname']." ".$rowLa['lname']."</td>\n";
				//echo "      <td align=\"left\" valign=\"top\" class=\"wh_und\">".$stage."</td>\n";
				echo "      <td align=\"left\" width=\"300px\" class=\"wh_und\">\n";

				//echo $strarr[0];
				echo $rowL['mtext'];

				echo "		</td>\n";
				echo "   </tr>\n";
			}
			elseif ($mc == 4)
			{
				echo "   <tr>\n";
				echo "      <td colspan=\"3\" align=\"right\" valign=\"bottom\" class=\"gray\" NOWRAP><b>More....</b> (Use Add Comments Button)</td>\n";
				echo "   </tr>\n";
			}
			$mc++;

		}

		echo "</table>\n";
	}

	echo "											</td>\n";
	*/
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";

	if (!empty($rowF['mrktproc']))
	{
		echo "							<tr>\n";
		echo "								<td align=\"right\" valign=\"top\" colspan=\"2\">\n";
		echo "									<table class=\"outer\" width=\"100%\" height=\"75\">\n";
		echo "										<tr>\n";
		echo "											<td class=\"gray\" valign=\"top\"><b>Marketing Data:</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr valign=\"top\">\n";
		echo "											<td class=\"gray\"><textarea name=\"mrkproc\" cols=\"90\" rows=\"25\">".$rowF['mrktproc']."</textarea></td>\n";
		//echo "											<td width=\"75%\" WRAP><pre>".$rowF['mrktproc']."</pre></td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
	}

	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "				   <td>\n";

	if (!empty($_POST['subq']) && $_POST['subq']=="history")
	{
		$qryZ = "SELECT * FROM leadhistory WHERE cinfo_id='".$_POST['cid']."' ORDER BY udate DESC;";
		$resZ = mssql_query($qryZ);
		$nrowZ= mssql_num_rows($resZ);

		if ($nrowZ > 0)
		{
			echo "<table class=\"outer\" align=\"center\" width=\"100%\">\n";
			echo "   <tr><td class=\"gray\" align=\"left\"><b>Lead Update History</b></td></tr>\n";
			echo "   <tr><td class=\"gray\">\n";
			echo "      <table align=\"left\" width=\"100%\">\n";
			echo "         <tr>\n";
			echo "            <td class=\"ltgray_und\" align=\"left\"><b>Date</b></td>\n";
			echo "            <td class=\"ltgray_und\" align=\"left\"><b>Office</b></td>\n";
			echo "            <td class=\"ltgray_und\" align=\"left\"><b>Owner</b></td>\n";
			echo "            <td class=\"ltgray_und\" align=\"left\"><b>Source</b></td>\n";
			echo "            <td class=\"ltgray_und\" align=\"left\"><b>Result</b></td>\n";
			echo "            <td class=\"ltgray_und\" align=\"left\"><b>Last Update</b></td>\n";
			echo "         </tr>\n";

			while ($rowZ = mssql_fetch_array($resZ))
			{
				$qryZa = "SELECT name FROM offices WHERE officeid='".$rowZ['officeid']."';";
				$resZa = mssql_query($qryZa);
				$rowZa = mssql_fetch_array($resZa);

				$qryZb = "SELECT lname,fname FROM security WHERE securityid='".$rowZ['owner']."';";
				$resZb = mssql_query($qryZb);
				$rowZb = mssql_fetch_array($resZb);

				$qryZc = "SELECT name FROM leadstatuscodes WHERE statusid='".$rowZ['source']."';";
				$resZc = mssql_query($qryZc);
				$rowZc = mssql_fetch_array($resZc);

				$qryZd = "SELECT name FROM leadstatuscodes WHERE statusid='".$rowZ['result']."';";
				$resZd = mssql_query($qryZd);
				$rowZd = mssql_fetch_array($resZd);

				$qryZe = "SELECT lname,fname FROM security WHERE securityid='".$rowZ['uby']."';";
				$resZe = mssql_query($qryZe);
				$rowZe = mssql_fetch_array($resZe);

				echo "   <tr>\n";
				echo "         <td class=\"wh_und\" align=\"left\">".$rowZ['udate']."</td>\n";
				echo "         <td class=\"wh_und\" align=\"left\">".$rowZa['name']."</td>\n";
				echo "         <td class=\"wh_und\" align=\"left\">".$rowZb['lname'].", ".$rowZb['fname']."</td>\n";

				if ($rowZ['source']==0)
				{
					//echo "         <td class=\"wh_und\" align=\"left\">Internet</td>\n";
					echo "         <td class=\"wh_und\" align=\"left\">bluehaven.com</td>\n";
				}
				else
				{
					echo "         <td class=\"wh_und\" align=\"left\">".$rowZc['name']."</td>\n";
				}

				echo "         <td class=\"wh_und\" align=\"left\">".$rowZd['name']."</td>\n";
				echo "         <td class=\"wh_und\" align=\"left\">".$rowZe['lname'].", ".$rowZe['fname']."</td>\n";
				echo "   </tr>\n";
			}
			echo "      </table>\n";
			echo "   </td></tr>\n";
			echo "</table>\n";
		}
	}

	echo "		         </td>\n";
	echo "	         </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "	</td>\n";
	echo "	<td align=\"left\" valign=\"top\">\n";
	echo "		<table border=0>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update Contact\" ".$dis.">\n";
	echo "				</td>\n";
	echo "			</form>\n";
	echo "			</tr>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"view_fin_detail\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_POST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	echo "         <input type=\"hidden\" name=\"oid\" value=\"".$oid."\">\n";
	echo "			<input type=\"hidden\" name=\"foid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_POST['uid']!="XXX")
	{
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Finance Detail\"><br>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";
	echo "         <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	echo "				<input type=\"hidden\" name=\"uid\" value=\"".$_POST['uid']."\">\n";
	echo "				<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	echo "				<input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";
	echo "				<input type=\"hidden\" name=\"fofficeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "            <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Comments\">\n";
	echo "            </td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	
	$qryXX = "UPDATE cinfo SET viewed=getdate(), viewedby=".$_SESSION['securityid']." WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resXX = mssql_query($qryXX);

}

function cform_add()
{
	error_reporting(E_ALL);
	//$err=0;

	if (	  empty($_POST['cfname'])
			||empty($_POST['clname'])
			||empty($_POST['chome'])
			||empty($_POST['caddr1'])
			||empty($_POST['ccity'])
			||empty($_POST['czip1'])
			||empty($_POST['cemail'])
			||empty($_POST['salesrep'])
			||empty($_POST['assigned'])
			||!is_numeric($_POST['czip1'])
			||strlen($_POST['czip1'])!= 5
			||$_POST['source']==1
			|| preg_match("/,/",$_POST['clname'])
			|| preg_match("/'/",$_POST['clname'])
			|| preg_match("/,/",$_POST['cfname'])
			|| preg_match("/'/",$_POST['cfname'])
			|| preg_match("/,/",$_POST['caddr1'])
			|| preg_match("/'/",$_POST['caddr1'])
			||!isset($_POST['finansrc'])
		 )
	{
		echo "<table>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><font color=\"red\"><b>ERROR!</b></font></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><b>Required Information is Missing or is Improperly Formatted, click the BACK button and correct:</b></td>";
		echo "	</tr>\n";

		if (empty($_POST['cfname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- First Name</b></td>";
			echo "	</tr>\n";
		}

		if (preg_match("/,/",$_POST['cfname']) || preg_match("/'/",$_POST['cfname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- First Name cannot contain Commas or Apostrophes</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['clname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Last Name</b></td>";
			echo "	</tr>\n";
		}

		if (preg_match("/,/",$_POST['clname']) || preg_match("/'/",$_POST['clname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Last Name cannot contain Commas or Apostrophes</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['chome']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Home Phone</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['caddr1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Address</b></td>";
			echo "	</tr>\n";
		}

		if (preg_match("/,/",$_POST['caddr1']) || preg_match("/'/",$_POST['caddr1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Address cannot contain Commas or Apostrophes</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['ccity']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- City</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['czip1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Zip Code Blank</b></td>";
			echo "	</tr>\n";
		}

		if (!is_numeric($_POST['czip1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Zip Code not Numeric</b></td>";
			echo "	</tr>\n";
		}

		if (strlen($_POST['czip1']) != 5)
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Zip Code Length not valid</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['cemail']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- E-Mail Address</b></td>";
			echo "	</tr>\n";
		}

		if ($_POST['source']==1)
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Lead Source</b></td>";
			echo "	</tr>\n";
		}
		
		if (!isset($_POST['finansrc']) || $_POST['finansrc'] < 1)
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Finance Source</b></td>";
			echo "	</tr>\n";
		}
		
		if (!isset($_POST['salesrep']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Office Rep</b></td>";
			echo "	</tr>\n";
		}
		
		if (!isset($_POST['assigned']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Finance Rep</b></td>";
			echo "	</tr>\n";
		}

		echo "</table>\n";
		exit;
	}
	
	//$assto=explode(":",$_POST['assignedto']);
	$srep	=explode(":",$_POST['salesrep']);
	$clsr	=explode(":",$_POST['assigned']);
	
	if ($srep[0]==0)
	{
		echo "<table>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><font color=\"red\"><b>ERROR!</b></font></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><b>You must Select an Office Rep</b></td>";
		echo "	</tr>\n";
		exit;
	}
	
	if ($clsr[0]==0)
	{
		echo "<table>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><font color=\"red\"><b>ERROR!</b></font></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><b>You must Assignt a Finance Rep</b></td>";
		echo "	</tr>\n";
		exit;
	}

	$qryA = "SELECT * FROM cinfo WHERE officeid='".$srep[0]."' AND clname='".$_POST['clname']."' AND caddr1='".$_POST['caddr1']."' AND czip1='".$_POST['czip1']."';";
	$resA = mssql_query($qryA);
	$rowA= mssql_fetch_array($resA);
	$nrowA=mssql_num_rows($resA);

	//echo $qryA."<br>";

	if ($nrowA > 0)
	{
		echo "<b><font color=\"red\">Error!</font> <br><br>The Lead information you entered already exists in the Lead System. Check your entry and resubmit</b>";
		exit;
	}
	else
	{
		if (!empty($_POST['opt1']) && $_POST['opt1']==1)
		{
			$opt1=1;
		}
		else
		{
			$opt1=0;
		}

		if (!empty($_POST['opt2']) && $_POST['opt2']==1)
		{
			$opt2=1;
		}
		else
		{
			$opt2=0;
		}
		
		if (!empty($_POST['opt3']) && $_POST['opt3']==1)
		{
			$opt3=1;
		}
		else
		{
			$opt3=0;
		}

		if (!empty($_POST['opt4']) && $_POST['opt4']==1)
		{
			$opt4=1;
		}
		else
		{
			$opt4=0;
		}
		
		/*if ($assto[0]!=$_SESSION['officeid'])
		{
			$qryB = "SELECT officeid,name,am FROM offices WHERE officeid='".$assto[0]."';";
			$resB = mssql_query($qryB);
			$rowB	= mssql_fetch_array($resB);
			$sid	=$rowB['am'];
		}
		else
		{
			$sid	=$assto[1];
		}*/
		
		$qryC   = "exec sp_insert_cinfo ";
		$qryC  .= "@securityid='".$srep[1]."', ";
		$qryC  .= "@officeid='".$srep[0]."', ";
		$qryC  .= "@srcoffice='".$_SESSION['officeid']."', ";
		$qryC  .= "@recdate='".$_POST['recdate']."', ";
		$qryC  .= "@cfname='".replacequote(ucwords(trim($_POST['cfname'])))."', ";
		$qryC  .= "@clname='".replacequote(ucwords(trim($_POST['clname'])))."', ";
		$qryC  .= "@caddr1='".replacequote(trim($_POST['caddr1']))."', ";
		$qryC  .= "@ccity='".replacequote($_POST['ccity'])."', ";
		$qryC  .= "@cstate='".$_POST['cstate']."', ";
		$qryC  .= "@czip1='".$_POST['czip1']."', ";
		$qryC  .= "@czip2='".$_POST['czip2']."', ";
		$qryC  .= "@ccounty='".$_POST['ccounty']."', ";
		$qryC  .= "@cmap='".replacequote($_POST['cmap'])."', ";

		if (empty($_POST['ssame']))
		{
			$qryC  .= "@ssame='0', ";
			$qryC  .= "@saddr1='".replacequote($_POST['saddr1'])."', ";
			$qryC  .= "@scity='".replacequote($_POST['scity'])."', ";
			$qryC  .= "@sstate='".$_POST['sstate']."', ";
			$qryC  .= "@szip1='".$_POST['szip1']."', ";
			$qryC  .= "@szip2='".$_POST['szip2']."', ";
			$qryC  .= "@scounty='".$_POST['scounty']."', ";
			$qryC  .= "@smap='".replacequote($_POST['smap'])."', ";
		}
		else
		{
			$qryC  .= "@ssame='".$_POST['ssame']."', ";
			$qryC  .= "@saddr1='".replacequote($_POST['caddr1'])."', ";
			$qryC  .= "@scity='".replacequote($_POST['ccity'])."', ";
			$qryC  .= "@sstate='".$_POST['cstate']."', ";
			$qryC  .= "@szip1='".$_POST['czip1']."', ";
			$qryC  .= "@szip2='".$_POST['czip2']."', ";
			$qryC  .= "@scounty='".$_POST['ccounty']."', ";
			$qryC  .= "@smap='".replacequote($_POST['cmap'])."', ";
		}

		$qryC  .= "@chome='".$_POST['chome']."', ";
		$qryC  .= "@cwork='".$_POST['cwork']."', ";
		$qryC  .= "@ccell='".$_POST['ccell']."', ";
		$qryC  .= "@cfax='".$_POST['cfax']."', ";
		$qryC  .= "@source='".$_POST['source']."', ";
		$qryC  .= "@cemail='".replacequote($_POST['cemail'])."', ";
		$qryC  .= "@cconph='".$_POST['cconph']."', ";
		$qryC  .= "@ccontime='".$_POST['ccontime']."', ";
		$qryC  .= "@appt_mo='".$_POST['appt_mo']."', ";
		$qryC  .= "@appt_da='".$_POST['appt_da']."', ";
		$qryC  .= "@appt_yr='".$_POST['appt_yr']."', ";
		$qryC  .= "@appt_hr='".$_POST['appt_hr']."', ";
		$qryC  .= "@appt_mn='".$_POST['appt_mn']."', ";
		$qryC  .= "@appt_pa='".$_POST['appt_pa']."', ";
		$qryC  .= "@opt1='".$opt1."', ";
		$qryC  .= "@opt2='".$opt2."', ";
		$qryC  .= "@opt3='".$opt3."', ";
		$qryC  .= "@opt4='".$opt4."', ";
		$qryC  .= "@comments=''; ";

		$resC   = mssql_query($qryC);
		$rowC   = mssql_fetch_row($resC);
		
		//echo $qryC."<br>";

		if (isset($rowC[0]) && $rowC[0] != 0)
		{
			//if ($_POST['officeid']==$_SESSION['officeid'])
			//if ($srep[0]==$_SESSION['officeid'])
			//{
			if (!empty($_POST['comments']) && strlen($_POST['comments']) >= 2 && $rowA['ccnt'] == 0)
			{
				$qryD   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
				$qryD  .= "VALUES ";
				$qryD  .= "('".$rowC[0]."','".$clsr[0]."','".$_SESSION['securityid']."','leads','".replacequote($_POST['comments'])."','".$_POST['uid']."')";
				$resD  = mssql_query($qryD);
			}
			
			add_finan_cust($srep[0],$clsr[0],$rowC[0],$_SESSION['securityid'],$_POST['uid'],$clsr[1]);
			
			cform_view($rowC[0]);
			/*
			}
			else
			{
				$qryEa = "SELECT officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
				$resEa = mssql_query($qryEa);
				$rowEa = mssql_fetch_array($resEa);
				
				$qryE   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
				$qryE  .= "VALUES ";
				$qryE  .= "('".$rowC[0]."','".$assto[0]."','".$_SESSION['securityid']."','leads','Lead Forwarded by ".$rowEa['name']."','".$_POST['uid']."')";
				$resE  = mssql_query($qryE);
				
				if (!empty($_POST['comments']) && strlen($_POST['comments']) >= 2 && $rowA['ccnt'] == 0)
				{
					$qryD   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
					$qryD  .= "VALUES ";
					$qryD  .= "('".$rowC[0]."','".$srep[0]."','".$_SESSION['securityid']."','leads','".replacequote($_POST['comments'])."','".$_POST['uid']."')";
					$resD  = mssql_query($qryD);
				}
				
				add_finan_cust($assto[0],$_SESSION['officeid'],$rowC[0],$_SESSION['securityid'],$_POST['uid'],$clsr[1]);
				
				cform_view($rowC[0]);
				
				//echo "<b>The Contact Information you Submitted was added to: ".$rowB['name']."</b>";
				//exit;		
			}
				*/
		}
		else
		{
			echo "<b><font color=\"red\">Error!</font> <br><br>The Lead information you attempted to submit did not save. Click back, check your entry and resubmit</b>";
			exit;
		}
	}
}

function cform_editTEST()
{
	show_post_vars();
}

function cform_edit()
{
	$acclist=explode(",",$_SESSION['aid']);
	
	$assto=explode(":",$_POST['assignedto']);
	
	$qry = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_POST['cid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1 = "SELECT am FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	$qry2 = "SELECT am FROM offices WHERE officeid='89';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	//$qry3 = "SELECT am,name FROM offices WHERE officeid='".$_POST['site']."';";
	$qry3 = "SELECT am,name FROM offices WHERE officeid='".$assto[0]."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);

	$qry4 = "SELECT securityid,sidm FROM security WHERE securityid='".$row['securityid']."';";
	$res4 = mssql_query($qry4);
	$row4 = mssql_fetch_array($res4);

	if (!in_array($row['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to Update this Lead</b>";
		exit;
	}
	else
	{
		if (!isset($_POST['hold']))
		{
			$hold=0;
		}
		elseif ($_POST['stage']==6)
		{
			$hold=0;
		}
		else
		{
			$hold=$_POST['hold'];
		}

		$qryA  = "UPDATE cinfo SET ";

		if ($_SESSION['llev'] >= 5)
		{
			//$qry4 = "SELECT custid FROM cinfo WHERE officeid='".$_POST['site']."' AND custid='".$row['custid']."';";
			$qry4 = "SELECT custid FROM cinfo WHERE officeid='".$assto[0]."' AND custid='".$row['custid']."';";
			$res4 = mssql_query($qry4);
			$row4 = mssql_fetch_array($res4);
			$nrow4= mssql_num_rows($res4);

			//if ($nrow4 > 0 && $_SESSION['officeid']!=$_POST['site'])
			if ($nrow4 > 0 && $_SESSION['officeid']!=$assto[0])
			{
				//$qry5 = "SELECT MAX(custid) as mcustid FROM cinfo WHERE officeid='".$_POST['site']."';";
				$qry5 = "SELECT MAX(custid) as mcustid FROM cinfo WHERE officeid='".$assto[0]."';";
				$res5 = mssql_query($qry5);
				$row5 = mssql_fetch_array($res5);

				$ncustid=$row5['mcustid']+1;
				$qryA  .= "custid='".$ncustid."', ";
			}

			//if ($_SESSION['officeid']!=$_POST['site'])
			/*if ($_SESSION['officeid']!=$assto[0])
			{
				$qryA  .= "securityid='".$row3['am']."', ";
				//$qryA  .= "officeid='".$_POST['site']."', ";
				$qryA  .= "officeid='".$assto[0]."', ";
				//$qryA  .= "officeid='89', ";
				$udate_id=$row3['am'];
			}
			else
			{*/
				//$qryA  .= "securityid='".$_POST['estorig']."', ";
				//$qryA  .= "officeid='".$_POST['site']."', ";
				//$udate_id=$_POST['estorig'];
				$qryA  .= "securityid='".$assto[1]."', ";
				$qryA  .= "officeid='".$assto[0]."', ";
				$udate_id=$assto[1];
			//}
		}
		else
		{
			//$qryA  .= "securityid='".$_POST['estorig']."', ";
			//$udate_id=$_POST['estorig'];
			$qryA  .= "securityid='".$assto[0]."', ";
			$udate_id=$assto[0];
		}

		if ($_SESSION['llev'] >= 4)
		{
			$qryA  .= "cfname='".replacequote(ucwords($_POST['cfname']))."', ";
			$qryA  .= "clname='".replacequote(ucwords($_POST['clname']))."', ";
		}

		$qryA  .= "caddr1='".replacequote(ucwords($_POST['caddr1']))."', ";
		$qryA  .= "ccity='".replacequote(ucwords($_POST['ccity']))."', ";
		$qryA  .= "cstate='".$_POST['cstate']."', ";
		$qryA  .= "czip1='".$_POST['czip1']."', ";
		$qryA  .= "czip2='".$_POST['czip2']."', ";
		$qryA  .= "ccounty='".$_POST['ccounty']."', ";
		$qryA  .= "cmap='".replacequote($_POST['cmap'])."', ";

		if (empty($_POST['ssame']))
		{
			$qryA  .= "ssame='0', ";
			$qryA  .= "saddr1='".replacequote(ucwords($_POST['saddr1']))."', ";
			$qryA  .= "scity='".replacequote(ucwords($_POST['scity']))."', ";
			$qryA  .= "sstate='".$_POST['sstate']."', ";
			$qryA  .= "szip1='".$_POST['szip1']."', ";
			$qryA  .= "szip2='".$_POST['szip2']."', ";
			$qryA  .= "scounty='".$_POST['scounty']."', ";
			$qryA  .= "smap='".replacequote($_POST['smap'])."', ";
		}
		else
		{
			$qryA  .= "ssame='".$_POST['ssame']."', ";
			$qryA  .= "saddr1='".replacequote(ucwords($_POST['caddr1']))."', ";
			$qryA  .= "scity='".replacequote(ucwords($_POST['ccity']))."', ";
			$qryA  .= "sstate='".$_POST['cstate']."', ";
			$qryA  .= "szip1='".$_POST['czip1']."', ";
			$qryA  .= "szip2='".$_POST['czip2']."', ";
			$qryA  .= "scounty='".$_POST['ccounty']."', ";
			$qryA  .= "smap='".replacequote($_POST['cmap'])."', ";
		}

		if ($_SESSION['llev'] >= 4)
		{
			$qryA  .= "chome='".$_POST['chome']."', ";
			$qryA  .= "cwork='".$_POST['cwork']."', ";
			$qryA  .= "ccell='".$_POST['ccell']."', ";
			$qryA  .= "cfax='".$_POST['cfax']."', ";
		}

		$qryA  .= "cemail='".replacequote($_POST['cemail'])."', ";
		$qryA  .= "cconph='".$_POST['cconph']."', ";
		$qryA  .= "ccontime='".$_POST['ccontime']."', ";
		$qryA  .= "appt_mo='".$_POST['appt_mo']."', ";
		$qryA  .= "appt_da='".$_POST['appt_da']."', ";
		$qryA  .= "appt_yr='".$_POST['appt_yr']."', ";
		$qryA  .= "appt_hr='".$_POST['appt_hr']."', ";
		$qryA  .= "appt_mn='".$_POST['appt_mn']."', ";
		$qryA  .= "appt_pa='".$_POST['appt_pa']."', ";
		$qryA  .= "hold_mo='".$_POST['hold_mo']."', ";
		$qryA  .= "hold_da='".$_POST['hold_da']."', ";
		$qryA  .= "hold_yr='".$_POST['hold_yr']."', ";
		$qryA  .= "source='".$_POST['source']."', ";
		$qryA  .= "finan_src='".$_POST['finansrc']."', ";
		$qryA  .= "stage='".$_POST['stage']."', ";
		$qryA  .= "hold='".$hold."', ";
		$qryA  .= "updated=getdate(), ";
		$qryA  .= "dupe='".$_POST['dupe']."' ";
		//$qryA  .= "comments='".replacequote($_POST['comments'])."' ";
		$qryA  .= "WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_POST['cid']."';";
		$resA  = mssql_query($qryA);
		//$rowA = mssql_fetch_array($resA);
		//echo $qryA;

		//Update history table
		$qryB   = "INSERT INTO leadhistory (cinfo_id,officeid,owner,uby,source,result) ";
		$qryB  .= "VALUES ";
		//$qryB  .= "('".$_POST['cid']."','".$_POST['site']."','".$udate_id."','".$_SESSION['securityid']."','".$_POST['source']."','".$_POST['stage']."')";
		$qryB  .= "('".$_POST['cid']."','".$assto[0]."','".$udate_id."','".$_SESSION['securityid']."','".$_POST['source']."','".$_POST['stage']."')";
		$resB  = mssql_query($qryB);

		//Update chistory table for inter-office moves
		//if ($_SESSION['officeid']!=$_POST['site'])
		if ($_SESSION['officeid']!=$assto[0])
		{
			//$qryC	= "UPDATE chistory SET officeid='".$_POST['site']."' WHERE custid='".$_POST['cid']."';";
			$qryC	= "UPDATE chistory SET officeid='".$assto[0]."' WHERE custid='".$_POST['cid']."';";
			$resC	= mssql_query($qryC);
		}

		if ($_SESSION['llev'] >= 5)
		{
			//if ($_SESSION['officeid']!=$_POST['site'])
			if ($_SESSION['officeid']!=$assto[0])
			{
				echo "<b>Lead forwarded to ".$row3['name']."</b>";
			}
			else
			{
				if (!empty($_SESSION['tqry']))
				{
					listleads();
				}
				else
				{
					cform_view();
				}
			}
		}
		else
		{
			if (!empty($_SESSION['tqry']))
			{
				listleads();
			}
			else
			{
				cform_view();
			}
		}
	}

}

function lead_matrix()
{
	//show_post_vars();
	error_reporting(E_ALL);
	//echo "lead_linan_func:lead_matrix<br>";
	include ("./calendar_func.php");
	if ($_SESSION['call']=="list")
	{
		listleads();
	}
	elseif ($_SESSION['call']=="appts")
	{
		apptleads_mo();
	}
	elseif ($_SESSION['call']=="new")
	{
		cform();
	}
	elseif ($_SESSION['call']=="add")
	{
		cform_add();
	}
	elseif ($_SESSION['call']=="view")
	{
		cform_view();
	}
	elseif ($_SESSION['call']=="edit")
	{
		cform_edit();
	}
	elseif ($_SESSION['call']=="delete")
	{
		cform_delete();
	}
	elseif ($_SESSION['call']=="search")
	{
		echo $_SESSION['call']."<br>";
		lead_search();
	}
	elseif ($_SESSION['call']=="sales_search")
	{
		echo $_SESSION['call']."<br>";
		sales_search();
	}
	elseif ($_SESSION['call']=="search_results")
	{
		listleads();
	}
	elseif ($_SESSION['call']=="showcalendar")
	{
		showMonth_full();
	}
	elseif ($_SESSION['call']=="showday_expanded")
	{
		showMonth_full();
		//showDay_expanded();
	}
	elseif ($_SESSION['call']=="chistory_add")
	{
		chistory_add();
	}
	elseif ($_SESSION['call']=="chistory")
	{
		//echo "HISTORY";
		chistory_list();
	}
	elseif ($_SESSION['call']=="set_digdate")
	{
		set_digdate();
	}
	elseif ($_SESSION['call']=="set_clsdate")
	{
		set_clsdate();
	}
	elseif ($_SESSION['call']=="set_condate")
	{
		set_condate();
	}
	elseif ($_SESSION['call']=="add_fin_detail")
	{
		finan_form_add();
	}
	elseif ($_SESSION['call']=="add_fin_detail2")
	{
		finan_form_add2();
	}
	elseif ($_SESSION['call']=="view_fin_detail")
	{
		finan_form_view();
	}
	elseif ($_SESSION['call']=="updt_fin_detail")
	{
		finan_form_updt();
	}
	elseif ($_SESSION['call']=="finan_status_update")
	{
		finan_status_update();
	}
}

1;
?>