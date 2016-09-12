<?php

function button_matrix() {
	$_SESSION['admin_offs'] =array(89,138);
	$off_ar=array(55,60,89,75,99,144);
	$qry1  = "SELECT enest,encon,enjob,enmas,enquickbooks,finan_off,fsenable,fscustomer,fsshared,fsoffice,purchaseorder,accountingsystem,otype FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,tester,networkaccess,filestoreaccess,PurchaseOrder,accountingsystem FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=$_SESSION['securityid'];
	
	?>
	
	<script type="text/javascript">
		$.cookie("new_msg_ack",0);
	</script>
	
	<?php
	
	echo "<div style=\"width:950px;height:15px;\">\n";

	common_menu_panel();

	echo "</div><br>\n";
	echo "<div style=\"width:950px\">\n";
	echo "   <div id=\"jmsnav\">\n";
	echo "   	<ul>\n";
	echo "   		<li><a href=\"#quick\">Quick Search</a>\n";
	echo "   	</ul>\n";
	echo "   	<ul>\n";
	echo "   		<li><a href=\"#leads\">Leads</a>\n";
	echo "   			<ul>\n";
	echo "   				<li><a href=\"#leads.search\">Search</a></li>\n";
	echo "   				<li><a href=\"#leads.new\">New</a></li>\n";
	echo "   				<li><a href=\"#leads.calendar\">Calendar</a></li>\n";
	echo "   			</ul>\n";
	echo "   		</li>\n";
	echo "   	</ul>\n";
	echo "   	<ul>\n";
	echo "   		<li><a href=\"#estimates\">Estimates</a>\n";
	echo "   			<ul>\n";
	echo "   				<li><a href=\"#estimates.search\">Search</a></li>\n";
	echo "   				<li><a href=\"#estimates.new\">New</a></li>\n";
	echo "   			</ul>\n";
	echo "   		</li>\n";
	echo "   	</ul>\n";
	echo "   	<ul>\n";
	echo "   		<li><a href=\"#contracts\">Contracts</a>\n";
	echo "   			<ul>\n";
	echo "   				<li><a href=\"#contracts.search\">Search</a></li>\n";
	echo "   			</ul>\n";
	echo "   		</li>\n";
	echo "   	</ul>\n";
	echo "   	<ul>\n";
	echo "   		<li><a href=\"#jobs\">Jobs</a>\n";
	echo "   			<ul>\n";
	echo "   				<li><a href=\"#jobs.search\">Search</a></li>\n";
	echo "   			</ul>\n";
	echo "   		</li>\n";
	echo "   	</ul>\n";
	echo "   	<ul>\n";
	echo "   		<li><a href=\"#reports\">Reports</a>\n";
	echo "   			<ul>\n";
	echo "   				<li><a href=\"#reports.search\">Search</a></li>\n";
	echo "   			</ul>\n";
	echo "   		</li>\n";
	echo "   	</ul>\n";
	echo "   	<ul>\n";
	echo "   		<li><a href=\"#maint\">Maintenance</a>\n";
	echo "   			<ul>\n";
	echo "   				<li><a href=\"#reports.search\">Search</a></li>\n";
	echo "   			</ul>\n";
	echo "   		</li>\n";
	echo "   	</ul>\n";
	echo "   </div>\n";
	echo "</div>\n";
}

function main_button_menu_OLD() {
	//include ('help_nodes.php');
	$dis	="";
	$_SESSION['admin_offs'] =array(89,138);
	$off_ar=array(55,60,89,75,99,144);
	$qry1  = "SELECT enest,encon,enjob,enmas,enquickbooks,finan_off,fsenable,fscustomer,fsshared,fsoffice,purchaseorder,accountingsystem,otype FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,tester,networkaccess,filestoreaccess,PurchaseOrder,accountingsystem FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=$_SESSION['securityid'];
	
	?>
	
	<script type="text/javascript">
		$.cookie("new_msg_ack",0);
	</script>
	
	<?php
	
	echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
	echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\"><b>Main</b></td>\n";
	echo "   	<td align=\"right\">\n";

	common_menu_panel();

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	if ($row1['officeid']!=199)
	{
		if ($_SESSION['llev'] >= 5 && ($row1['otype']==0 || $row1['otype']==1))
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"csearch\">\n";
			//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Quick Search\" title=\"Quick Search Menu\">\n";
			echo "							<button class=\"btnsysmenu\">Search</button>\n";
			echo "         				</form>\n";
			echo "					</td>\n";
		}
	}

	if ($_SESSION['llev'] >= 1)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
		
		if ($row1['officeid']!=199)
		{
			if ($row1['otype']==0 || $row1['otype']==1)
			{
				echo "							<button class=\"btnsysmenu\">Leads</button>\n";
				//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Leads\" title=\"Lead Tracking & Information\">\n";
			}
			elseif ($row1['otype']==2)
			{
				echo "							<button class=\"btnsysmenu\">Vendors</button>\n";
				//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Vendors\" title=\"Vendor Menu\">\n";
			}
			elseif ($row1['otype']==3)
			{
				echo "							<button class=\"btnsysmenu\">Contacts</button>\n";
				//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Contacts\" title=\"Contacts Menu\">\n";
			}
			elseif ($row1['otype']==4)
			{
				echo "							<button class=\"btnsysmenu\">Contacts</button>\n";
				//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Contacts\" title=\"Contacts Menu\">\n";
			}
			else
			{
				echo "							<button class=\"btnsysmenu\">Leads</button>\n";
				//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Leads\" title=\"Vendor Tracking\">\n";
			}
		}
		else
		{
			echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Vendors\" title=\"Vendor Tracking\">\n";
		}
		
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	if ((isset($row2['networkaccess']) and $row2['networkaccess'] >= 1) and $row1['officeid']!=199)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"network\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search_net\">\n";
		//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Network Leads\" title=\"Network Lead Tracking & Information\">\n";
		echo "							<button class=\"btnsysmenu\">Network</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	if ($_SESSION['elev'] >= 1 && $row1['enest']==1 && ($row1['otype']==0 || $row1['otype']==1))
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
		//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Estimates\" title=\"Search Estimates\">\n";
		echo "							<button class=\"btnsysmenu\">Estimates</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	if ($_SESSION['clev'] >= 1 && $row1['encon']==1 && ($row1['otype']==0 || $row1['otype']==1))
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
		//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Contracts\" title=\"Search Contracts\">\n";
		echo "							<button class=\"btnsysmenu\">Contracts</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	if ($_SESSION['jlev'] >= 1 && $row1['enjob']==1 && ($row1['otype']==0 || $row1['otype']==1))
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
		//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Jobs\" title=\"Search Jobs\">\n";
		echo "							<button class=\"btnsysmenu\">Jobs</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	/*
	if ($_SESSION['securityid'] == 26 || $_SESSION['securityid'] == 332)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"sales\">\n";
		echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Sales\" title=\"Create a New Shopping Cart or Search Sales\">\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	*/

	if ($_SESSION['jlev'] >= 7 && $row1['enmas']==1 && ($row1['otype']==0 || $row1['otype']==1))
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"mas\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"MAS_search\">\n";
		//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"MAS\" title=\"Search MAS\">\n";
		echo "							<button class=\"btnsysmenu\">Accounting</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	/*
	if ($_SESSION['jlev'] >= 6 and (isset($row1['enquickbooks']) and $row1['enquickbooks'] == 1) and $row2['officeid']==89)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"accountingsystem\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list_Queues\">\n";
		echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Quickbooks\" title=\"Quickbooks Accounting System\">\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	*/

	if ($_SESSION['rlev'] >= 1)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";
		//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Reports\" title=\"Report Menu\">\n";
		echo "							<button class=\"btnsysmenu\">Reports</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	if (
			(
				(isset($row1['fsshared']) and $row1['fsshared'] == 1)
				or (isset($row1['fsoffice']) and $row1['fsoffice'] == 1)
			)
			and (isset($row2['filestoreaccess']) and $row2['filestoreaccess'] >= 5)
		)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"File Cabinet\" title=\"File Cabinet\">\n";
		echo "							<button class=\"btnsysmenu\">Files</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";
	//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Maintenance\" title=\"Password Change & System Maintenance\">\n";
	echo "							<button class=\"btnsysmenu\">Maintenance</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	//echo "							<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Main Menu\" title=\"Main Menu\">\n";
	echo "							<button class=\"btnsysmenu\">Main Menu</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "						<img class=\"getHelpNode\" id=\"MainMenu\" src=\"images/help.png\" title=\"Main Menu Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function est_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,tester FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['elev'],"e");
	
	echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
	echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\">\n";
	echo "			<b>Estimates\n";
	echo "   	</td>\n";
	echo "   	<td align=\"right\">\n";
	
	common_menu_panel();
	
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "							<button class=\"btnsysmenu\">New Search</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "							<button class=\"btnsysmenu\">Main Menu</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "						<img class=\"getHelpNode\" id=\"EstimateMenu\" src=\"images/help.png\" title=\"Estimate Menu Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function doc_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,admindigreport,tester FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);
	
	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	$_SESSION['aid']	=aidbuilder($_SESSION['rlev'],"r");
	
	$brdr=0;
	
	//if ($_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==FDBK_ADMIN)
	if ($row2['officeid']==89)
	{
		echo "<table class=\"outer\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "   <tr>\n";
		echo "   	<td class=\"lg\" colspan=\"5\" align=\"right\">\n";
		echo "			<table cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "   			<tr>\n";
		
		if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1)
		{
			echo "   	<td class=\"lg\" width=\"20\">\n";
			echo "			<form method=\"post\">\n";
			echo "				<input type=\"hidden\" name=\"action\" value=\"message\">\n";
			echo "				<input type=\"hidden\" name=\"call\" value=\"list\">\n";
			echo "				<input class=\"transnb\" type=\"image\" src=\"images/email.png\" alt=\"New Messages\">\n";
			echo "			</form>\n";
			echo "   	</td>\n";
		}
		
		if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
		{
			echo "	<td class=\"lg\" align=\"center\">\n";
			echo "	<form method=\"post\">\n";
			echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
			echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
			echo "		<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "		<input class=\"transnb\" type=\"image\" src=\"images/comments.png\" alt=\"Office Comments\">\n";
			echo "	</form>\n";
			echo "	</td>\n";
		}
	
		if ($_SESSION['tlev'] >= 7)
		{
			echo "      			<td align=\"right\">\n";
	
			set_office();
	
			echo "					</td>\n";
		}
		else
		{
			echo "      			<td align=\"right\"><b>[ </b>".$_SESSION['offname']."<b> ]</b></td>\n";
		}
		
		echo "   				<td>\n";
		echo "					<b>[ </b>".$_SESSION['fname']." ".$_SESSION['lname']."<b> ]</b> <a class=\"transnb\" href=\"./index.php?action=logoff\"><img src=\"images/action_delete.gif\" title=\"Logoff\"></a>\n";
		echo "   				</td>\n";
		echo "   			</tr>\n";
		echo "   		</table>\n";
		echo "   	</td>\n";
		echo "   <tr>\n";
	}
	else
	{
		echo "<table class=\"outer\" width=\"100%\">\n";
	}
	
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" colspan=\"4\" align=\"right\">\n";
	echo "			<table cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   			<tr>\n";
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"docs\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"List Docs\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"docs\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"create\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"List Docs\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "		   	</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}
}

function contract_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,tester FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);
	
	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	$_SESSION['aid']	=aidbuilder($_SESSION['clev'],"c");
	
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\">\n";
	echo "			<b>Contracts\n";
	echo "   	</td>\n";
	echo "   	<td align=\"right\">\n";
	
	common_menu_panel();
	
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Search\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";	
	echo "      			<td align=\"right\">\n";
	echo "						<img class=\"getHelpNode\" id=\"ContractMenuHelp\" src=\"images/help.png\" title=\"Contract Menu Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}
}

function job_button_menu()
{
	$dis='';
	
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,tester FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['clev'],"c");
	
	$brdr=0;
	
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\">\n";
	echo "			<b>Job Menu\n";
	echo "   	</td>\n";
	echo "   	<td align=\"right\">\n";
	
	common_menu_panel();
	
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\">\n";
	echo "			<table align=\"right\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Search\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "						<img class=\"getHelpNode\" id=\"JobMenuHelp\" src=\"images/help.png\" title=\"Job Menu Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}
}

function mas_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,tester FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['rlev'],"r");
	
	$brdr=0;
	
	echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
	echo "<table width=\"950px\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\"><b>Accounting</b></td>\n";
	echo "   	<td align=\"right\">\n";

	common_menu_panel();

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"mas\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"MAS_search\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Search\">\n";
	echo "							<button class=\"btnsysmenu\">New Search</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "							<button class=\"btnsysmenu\">Main Menu</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "					<td align=\"right\">\n";
	echo "						<img class=\"getHelpNode\" id=\"AccountingMenuHelp\" src=\"images/help.png\" title=\"Accounting Menu Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function leads_button_menu()
{
	$off_ar=array(55,60,89,75,99,144);
	
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry1  = "SELECT finan_off,ldexport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,gmreports,tester FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['llev'],"l");
	
	echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
	echo "<table width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\">\n";
	
	if ($_SESSION['otype']==0 || $_SESSION['otype']==1)
	{
		echo "			<b>Leads\n";
	}
	elseif ($_SESSION['otype']==2)
	{
		echo "			<b>Vendors\n";
	}
	elseif ($_SESSION['otype']==3)
	{
		echo "			<b>Contacts\n";
	}
	else
	{
		echo "			<b>Leads\n";
	}
	
	echo "   	</td>\n";
	echo "   	<td align=\"right\">\n";

	common_menu_panel();

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\">\n";
	echo "         			<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	
	if ($row1['finan_off']==0)
	{
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Search\">\n";
		echo "							<button class=\"btnsysmenu\">New Search</button>\n";
	}
	else
	{
		echo "							<button class=\"btnsysmenu\">Cont Search</button>\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Cont Search\">\n";
	}
	
	echo "         			</form>\n";
	echo "					</td>\n";
	
	if ($_SESSION['officeid']!=199)
	{
		if ($row1['finan_off']==1)
		{
			echo "      			<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"sales_search\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Sales Search\">\n";
			echo "							<button class=\"btnsysmenu\">Sales Search</button>\n";
			echo "         			</form>\n";
			echo "					</td>\n";
		}
	
		if ($row1['finan_off']==0)
		{
			echo "      			<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"showcalendar\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Calendar\">\n";
			echo "							<button class=\"btnsysmenu\">Calendar</button>\n";
			echo "         			</form>\n";
			echo "					</td>\n";
			echo "      			<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"appts\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Appointments\">\n";
			echo "							<button class=\"btnsysmenu\">Appointments</button>\n";
			echo "         			</form>\n";
			echo "					</td>\n";
		}
		
		echo "      			<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"new\">\n";
		
		if ($row1['finan_off']==0)
		{
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Lead\">\n";
			echo "							<button class=\"btnsysmenu\">New Lead</button>\n";
		}
		else
		{
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Contact\">\n";
			echo "							<button class=\"btnsysmenu\">New Contact</button>\n";
		}
		
		echo "         			</form>\n";
		echo "					</td>\n";
		
		if (($row2['gmreports']==1 and $row1['ldexport']==1) or $row2['officeid']==89)
		{
			echo "      			<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"exports\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Lead Export\">\n";
			echo "							<button class=\"btnsysmenu\">Export</button>\n";
			echo "         			</form>\n";
			echo "					</td>\n";
		}
	}
	else
	{
		echo "      			<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"new\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Vendor\">\n";
		echo "							<button class=\"btnsysmenu\">New Vendor</button>\n";
		echo "         			</form>\n";
		echo "					</td>\n";
	}
	
	echo "      			<td align=\"right\">\n";
	echo "         			<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "							<button class=\"btnsysmenu\">Main Menu</button>\n";
	echo "         			</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "						<img class=\"getHelpNode\" id=\"LeadsMenu\" src=\"images/help.png\" title=\"Leads Menu Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function network_button_menu()
{
	$_SESSION['aid']	=aidbuilder($_SESSION['llev'],"l");
	
	$off_ar=array(55,60,89,75,99,144);
	
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry1  = "SELECT finan_off,ldexport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,substring(slevel,13,1) as sslevel,admstaff,gmreports,tester FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);
	
	if ($row2['sslevel'] == 0)
	{
		echo 'Access Locked. Contact Management.';
		exit;
	}

	echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
	echo "<table width=\"950px\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\">\n";
	echo "			<b>Network Lead\n";
	echo "   	</td>\n";
	echo "   	<td align=\"right\">\n";
		
	common_menu_panel();
		
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";	
	echo "   	<td colspan=\"2\">\n";
	
	// Menu Table
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"network\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search_net\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Search\" title=\"Perform a Search of existing Network Leads\">\n";
	echo "							<button class=\"btnsysmenu\">Search</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"network\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search_net\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Callbacks\" title=\"Perform a Search of near date Callbacks\">\n";
	echo "							<button class=\"btnsysmenu\">Callbacks</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"network\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"add_net\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New\" title=\"Add a new Network Lead\">\n";
	echo "							<button class=\"btnsysmenu\">New</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\" title=\"Return to Main Menu\">\n";
	echo "							<button class=\"btnsysmenu\">Main Menu</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	
	echo "      			<td align=\"right\">\n";

	HelpNode('NetworkMenu',0);

	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function common_menu_panel()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry1  = "SELECT officeid,finan_off,ldexport,otype FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,gmreports,tester FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);
	
	$_SESSION['aid']	=aidbuilder($_SESSION['llev'],"l");
	$_SESSION['tester']	=$row2['tester'];
	
	$testaccess=(isset($_SESSION['tester']) and $_SESSION['tester']==1)?'<span title="You have access to Test Functions" style="color:red;"><b>Test Access</b></span>':'';
	$fullname = (isset($_SESSION['fullname']) and strlen($_SESSION['fullname'])!=0)?$_SESSION['fullname']:$_SESSION['fname'].' '.$_SESSION['lname'];

	echo "			<script type=\"text/javascript\" src=\"js/jquery_commonpanel_func.js\"></script>\n";
	echo "			<table style=\"float:right;\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"center\">\n";
	
	echo $testaccess;
	
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	
	if (isset($_REQUEST['sval']) && strlen($_REQUEST['sval']) >= 1)
	{
		$sval=$_REQUEST['sval'];
	}
	else
	{
		if ($row1['officeid']==199 or ($row1['otype'] == 2 || $row1['otype'] == 3))
		{
			$sval='Search Company Name...';
		}
		else
		{
			$sval='Search Customer Last Name...';
		}
	}
	
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"csearch_results_mainmenu\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"clname\">\n";
	echo "						<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
	
	if ($row1['officeid']==199 or ($row1['otype'] == 2 || $row1['otype'] == 3))
	{
		echo "						<input type=\"hidden\" name=\"spar\" value=\"cpname\">\n";
		echo "						<input type=\"hidden\" name=\"group\" value=\"c.cpname\">\n";
		echo "						<input type=\"hidden\" name=\"order\" value=\"c.cpname\">\n";
	}
	else
	{
		echo "						<input type=\"hidden\" name=\"spar\" value=\"clname\">\n";
		echo "						<input type=\"hidden\" name=\"group\" value=\"c.clname\">\n";
		echo "						<input type=\"hidden\" name=\"order\" value=\"c.clname\">\n";
	}
	
	echo "						<input type=\"hidden\" name=\"ascdesc1\" value=\"asc\">\n";
	echo "						<input type=\"hidden\" name=\"ascdesc2\" value=\"asc\">\n";
	echo "						<input class=\"JMStooltip\" type=\"text\" size=\"25\" name=\"sval\" id=\"sval\" value=\"".$sval."\" onFocus=\"ClearField('sval');\" title=\"Enter Customer Last Name (Full or Partial) and Click the Search Icon\">\n";
	echo "						<input class=\"transnb_button\" type=\"image\" src=\"images/search.gif\" title=\"Click to Search\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "   				<td width=\"20\">\n";
	
	if ($_SESSION['mlev'] >= 1)
	{
		echo "<a href=\"#\" id=\"subfdbkmsg\"><img src=\"images/server_edit.png\" title=\"Submit Feedback\"></a>";
	}
	
	echo "   				</td>\n";
	echo "   				<td width=\"20\">\n";
	echo "						<form id=\"show_jms_messages\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";

	if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1)
	{
		echo "						<input id=\"msgStatus\" class=\"transnb_button\" type=\"image\" src=\"images/email_new.png\" title=\"You have a New Message from another JMS User!\">\n";
	}
	else
	{
		echo "						<input id=\"msgStatus\" class=\"transnb_button\" type=\"image\" src=\"images/email.png\" title=\"Send or View Messages to other JMS Users\">\n";
	}
	
	echo "						</form>\n";
	echo "   				</td>\n";
	echo "	<td align=\"center\">\n";
	
	if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
	{
		echo "	<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
		echo "		<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "		<input class=\"transnb_button\" type=\"image\" src=\"images/comments.png\" title=\"Post Office Comments. Admin Function Only\">\n";
		echo "	</form>\n";
	}
	
	echo "	</td>\n";

	if ($_SESSION['tlev'] >= 7)
	{
		echo "      			<td align=\"right\">\n";

		if ($_SESSION['securityid']==26 or $_SESSION['securityid']==2191)
		{
			set_office_NEW();
		}
		else
		{
			set_office();
		}

		echo "					</td>\n";
	}
	else
	{
		echo "      			<td align=\"right\"><b>[ </b>".$_SESSION['offname']."<b> ]</b></td>\n";
	}
	
	echo "   				<td>\n";
	echo "						<img class=\"getHelpNode\" id=\"JMSInfoDlg\" src=\"images/information.png\" title=\"JMS Information\">";
	echo "   				</td>\n";
	echo "   				<td>\n";
	echo "					[ <span class=\"JMStooltip\" title=\"It's You!\">".$fullname."</span> ]</td><td> [<span class=\"JMStooltip\" title=\"Click here to log off the JMS\"><a class=\"transnb\" href=\"./index.php?action=logoff\"> Sign out </a></span>]\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "   		</table>\n";
	
	if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1 && $_SESSION['securityid']==26)
	{
		echo "<span id=\"new_msg_notify\"></span>";
	}
}

function contact_button_menu()
{
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\" colspan=\"2\" align=\"right\">\n";
	
	common_menu_panel();
	
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\">\n";
	echo "			<b>Contacts\n";
	echo "   	</td>\n";
	echo "   	<td class=\"gray\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Search\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"showcalendar\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Calendar\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"appts\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Appointments\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"new\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";

	HelpNode('VendorMenu',0);

	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function vendor_button_menu()
{
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\" colspan=\"2\" align=\"right\">\n";
	
	common_menu_panel();
	
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\">\n";
	echo "			<b>Vendors\n";
	echo "   	</td>\n";
	echo "   	<td class=\"gray\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"center\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Search\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";	
	echo "      			<td align=\"center\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"new\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"center\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";

	HelpNode('VendorMenu',0);

	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function leads_maint_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['clev'],"c");
	
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
	echo "			<b>Lead Maintenance\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"right\">\n";
	
	common_menu_panel();
	
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\" class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	if ($_SESSION['tlev'] >= 7 && $_SESSION['llev'] >= 7)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Unsorted\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"access_report\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Access Report\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if (($_SESSION['tlev'] >= 9 && $_SESSION['m_llev'] >= 9) or $row2['officeid']==89)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"lead_source_list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Source Codes\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"lead_result_list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Result Codes\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"move1\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Move Leads\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"upfile1\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Lead Import\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		
	}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Maintenance Menu\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "      				<td align=\"right\">\n";

	HelpNode('LeadsMaintenanceMenu',0);

	echo "					</td>\n";
	echo "		   	</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function reports_button_menu()
{
	error_reporting(JMS_DEBUG);
	
	$off_ar	=array(55,60,89,75,99,144);
	$sc_ar	=array(55,56,59,69,75,144);
	$jp_ar	=array(55,56,59,75,144);
	
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off,endigreport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,admindigreport,tester,constructdateaccess,gmreports,conspiperpt,digstandingrpt,jobprogress,screport FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['rlev'],"r");
	
	echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
	echo "<table width=\"950px\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\"><b>Reports</b></td>\n";
	echo "   	<td align=\"right\">\n";

	common_menu_panel();

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	if (isset($row2['digstandingrpt'])  and $row2['digstandingrpt'] >= 1)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"standings\">\n";
		//echo "						<input type=\"hidden\" name=\"brept_yr\" value=\"2009\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Dig Standings\">\n";
		echo "							<button class=\"btnsysmenu\">Standings</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	if ($_SESSION['rlev'] >= 5 && $row1['finan_off']==0)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"zipreports\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Zip Report\">\n";
		echo "							<button class=\"btnsysmenu\">Zip</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	if ($_SESSION['rlev'] >= 5)
	{
		if ($row1['finan_off']==0 && $row1['finan_from']!=0 || $row1['finan_off']==1)
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"srsearch\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\" Fin Status\">\n";
			echo "							<button class=\"btnsysmenu\">Fin Status</button>\n";
			echo "         				</form>\n";
			echo "					</td>\n";
		}
	}		
	
	if ($_SESSION['rlev'] >= 6)
	{
		if ($row1['finan_off']==1)
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"sfinleads\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Fin Source\">\n";
			echo "							<button class=\"btnsysmenu\">Fin Source</button>\n";
			echo "         				</form>\n";
			echo "					</td>\n";
		}
	}
	
	if ($_SESSION['rlev'] >= 6)
	{
		if ($row1['finan_off']==1)
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"fnexport\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Fin Summary\">\n";
			echo "							<button class=\"btnsysmenu\">Fin Summ</button>\n";
			echo "         				</form>\n";
			echo "					</td>\n";
		}
	}

	if ($_SESSION['rlev'] >= 5 && $row1['finan_off']==0)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"salesman_gen\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Sales Rep\">\n";
		echo "							<button class=\"btnsysmenu\">Sales Rep</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	if ($_SESSION['rlev'] >= 6 && $row1['finan_off']==0)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"IVRreport\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"stage1\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"800 Report\" title=\"800 Call Matrix Report\">\n";
		echo "							<button class=\"btnsysmenu\">800</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	if ($_SESSION['rlev'] >= 6 || $_SESSION['csrep'] >= 6)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"complaints\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"CSR\">\n";
		echo "							<button class=\"btnsysmenu\">CSR</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	if (($_SESSION['rlev'] >= 6 && $row1['endigreport'] == 1 && $row1['finan_off']==0) || $_SESSION['officeid']==89)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Dig Reports\">\n";
		echo "							<button class=\"btnsysmenu\">Digs</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}

	if ($_SESSION['rlev'] >= 99 && $row2['gmreports'] > 0 && $row1['finan_off']==0)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"clist\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Operating\">\n";
		echo "							<button class=\"btnsysmenu\">Operating</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	if (isset($row2['conspiperpt']) and $row2['conspiperpt'] >= 6)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"officepipeline\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Pipeline\" title=\"Pipeline Report\">\n";
		echo "							<button class=\"btnsysmenu\">Pipeline</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	if ($_SESSION['rlev'] >= 1 and $row2['screport'] >= 1)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"srpage\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"S & C\" title=\"Sales & Commission Report\">\n";
		echo "							<button class=\"btnsysmenu\">S & C</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	if ($row2['constructdateaccess'] >= 5 and $row2['jobprogress'] >= 1)
	{
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"jobprogress\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Job Progress\">\n";
		echo "							<button class=\"btnsysmenu\">Job Progress</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "							<button class=\"btnsysmenu\">Main Menu</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	//echo "      			<td align=\"center\">\n";
	//echo "						<img class=\"getHelpNode\" id=\"ReportsMenuHelp\" src=\"images/help.png\" title=\"Reports Menu Help\">\n";
	//echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "   			<tr>\n";
	
	if ($_SESSION['rlev'] >= 5)
	{
		if ($row1['finan_off']==0)
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"sleads\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Lead Source\">\n";
			echo "							<button class=\"btnsysmenu\">Source</button>\n";
			echo "         				</form>\n";
			echo "					</td>\n";

			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"rleads\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Lead Result\">\n";
			echo "							<button class=\"btnsysmenu\">Result</button>\n";
			echo "         				</form>\n";
			echo "					</td>\n";
		}
	}
		
	if ($_SESSION['rlev'] >= 9) //Admin Menu Items
	{
		echo "      			<td align=\"right\"><b>Admin Only</b></td>\n";
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"standings_config\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"DS Config\">\n";
		echo "							<button class=\"btnsysmenu\">DS Config</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"Conversion\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Conversion\">\n";
		echo "							<button class=\"btnsysmenu\">Conversion</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "         				<form id=\"rt_digs\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"admingen_preview\">\n";
		echo "						<input type=\"hidden\" name=\"d_moyr\" value=\"".date('Y').":".date('m')."\">\n";
		echo "						<input type=\"hidden\" name=\"access\" value=\"".$row2['admindigreport']."\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Admin Digs\">\n";
		echo "							<button class=\"btnsysmenu\">Admin Digs</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"activity_job_full\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"LCD Report\" title=\"Lead - Contract - Dig Report\">\n";
		echo "							<button class=\"btnsysmenu\">LCD</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"ShowEmailLog\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Email Log\">\n";
		echo "							<button class=\"btnsysmenu\">Email Log</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"loggedin\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Logged Users\">\n";
		echo "							<button class=\"btnsysmenu\">Logged In</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"offfeesched\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Fee Schedule\">\n";
		echo "							<button class=\"btnsysmenu\">Fees</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function mess_button_menu()
{
	$off_ar=array(55,60,89,75,99,144);
	
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,tester FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['rlev'],"r");

	echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
	echo "<table width=\"950px\">\n";
	echo "   <tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<b>Messages</b>\n";
	echo "		</td>\n";
	echo "		<td align=\"right\">\n";
		
	common_menu_panel();
		
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "   	<td colspan=\"2\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"list\">\n";
	echo "							<button class=\"btnsysmenu\">Received</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"listsent\">\n";
	echo "							<button class=\"btnsysmenu\">Sent</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"new\">\n";
	echo "							<button class=\"btnsysmenu\">Compose</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "							<button class=\"btnsysmenu\">Main Menu</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      				<td align=\"right\">\n";
	echo "						<img class=\"getHelpNode\" id=\"MessagingHelp\" src=\"images/help.png\" title=\"Messaging Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function maint_button_menu($valdis)
{
	$dis = '';
	$brdr= 0;
	$qry = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1= "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1= mssql_query($qry1);
	$row1= mssql_fetch_array($res1);
	
	$qry2= "SELECT officeid,admstaff,admindigreport,tester FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2= mssql_query($qry2);
	$row2= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['rlev'],"r");
	echo "<div class=\"outerrnd noPrint\" style=\"width:950px\">\n";
	echo "<table width=\"950px\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\"><b>Maintenance</b></td>\n";
	echo "   	<td align=\"right\">\n";

	common_menu_panel();

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	if ($_SESSION['tlev'] >=1 && $_SESSION['m_plev'] >= 1)
	{
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		//echo "						<input type=\"hidden\" name=\"call\" value=\"pbconfig\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Price Book\">\n";
		echo "							<button class=\"btnsysmenu\">Pricebook</button>\n";
		echo "         			</form>\n";
		echo "				</td>\n";
	}

	if ($_SESSION['tlev'] >= 1 && $_SESSION['m_llev'] >= 1)
	{
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Leads\" $dis>\n";
		echo "							<button class=\"btnsysmenu\">Leads</button>\n";
		echo "         			</form>\n";
		echo "				</td>\n";
	}

	if ($_SESSION['tlev'] >= 1 && $_SESSION['m_ulev'] >= 1)
	{
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Users\" $dis>\n";
		echo "							<button class=\"btnsysmenu\">Users</button>\n";
		echo "         			</form>\n";
		echo "				</td>\n";
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"off\">\n";
		//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Offices\" $dis>\n";
		echo "							<button class=\"btnsysmenu\">Offices</button>\n";
		echo "         			</form>\n";
		echo "				</td>\n";
	}
	
	echo "      		<td align=\"right\">\n";
	echo "         			<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"users\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"pp\">\n";
	echo "						<input type=\"hidden\" name=\"userid\" value=\"".$_SESSION['securityid']."\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"PB Adjusts\">\n";
	echo "							<button class=\"btnsysmenu\">PB Adjusts</button>\n";
	echo "         			</form>\n";
	echo "				</td>\n";
	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"users\">\n";
	echo "							<input type=\"hidden\" name=\"subq\" value=\"rp\">\n";
	echo "							<input type=\"hidden\" name=\"userid\" value=\"".$_SESSION['securityid']."\">\n";
	//echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Options\">\n";
	echo "							<button class=\"btnsysmenu\">Options</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	//echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "							<button class=\"btnsysmenu\">Main Menu</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	//echo "      			<td align=\"right\">\n";
	//echo "						<img class=\"getHelpNode\" id=\"MaintMenu\" src=\"images/help.png\" title=\"Maintenance Menu Help\">\n";
	//echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	
	if ($_SESSION['mlev'] >= 9) {
		echo "   <tr>\n";
		echo "   	<td colspan=\"2\">\n";
		echo "			<table align=\"right\">\n";
		echo "   			<tr>\n";
		echo "      			<td align=\"right\"><b>Admin Only</b></td>\n";
		
		if ($_SESSION['mlev'] >= 9 && $_SESSION['m_llev'] >= 1)
		{
			echo "      		<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"IVR/800\" $dis>\n";
			echo "							<button class=\"btnsysmenu\">IVR</button>\n";
			echo "         			</form>\n";
			echo "				</td>\n";
			echo "      		<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"zip_maint\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Matrix\">\n";
			echo "							<button class=\"btnsysmenu\">Matrix</button>\n";
			echo "         			</form>\n";
			echo "				</td>\n";
		}
		
		if ($_SESSION['mlev'] >= 9 && $_SESSION['m_llev'] >= 1)
		{
			echo "      		<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"commbuilder\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Comm Bldr\">\n";
			echo "							<button class=\"btnsysmenu\">Comm Bldr</button>\n";
			echo "         			</form>\n";
			echo "				</td>\n";
		}
		
		if ($_SESSION['mlev'] >= 9 && $_SESSION['m_llev'] >= 1 && $_SESSION['officeid']==89)
		{
			echo "      		<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"srcrescodes\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"S/R Codes\">\n";
			echo "							<button class=\"btnsysmenu\">S/R Codes</button>\n";
			echo "         			</form>\n";
			echo "				</td>\n";
		}
		
		if ($_SESSION['emailtemplates'] >= 9)
		{
			echo "      		<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"email\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"EMail Temps\">\n";
			echo "							<button class=\"btnsysmenu\">Email</button>\n";
			echo "         			</form>\n";
			echo "				</td>\n";
		}
		
		if ($_SESSION['mlev'] >= 9 && $_SESSION['m_mlev'] >= 9)
		{
			echo "   			<td align=\"right\">\n";
			echo "   				<form method=\"post\">\n";
			echo "   					<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "   					<input type=\"hidden\" name=\"call\" value=\"off\">\n";
			echo "					   	<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
			echo "   					<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Edit Office\">\n";
			echo "							<button class=\"btnsysmenu\">Edit Office</button>\n";
			echo "   				</form>\n";
			echo "   			</td>\n";
		}
		
		if ($_SESSION['securityid'] == 26 || $_SESSION['securityid'] == 332 || $_SESSION['securityid'] == 1950)
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"changelog\">\n";
			//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Change Log\">\n";
			echo "							<button class=\"btnsysmenu\">Change Log</button>\n";
			echo "         				</form>\n";
			echo "					</td>\n";
		}
		
		echo "		   		</tr>\n";
		echo "			</table>\n";
		echo "   	</td>\n";
		echo "   </tr>\n";
	}
	
	echo "</table>\n";
	echo "</div>\n";
}

function off_demo_button_menu()
{
	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"250\">\n";
	echo "			<b>&nbsp;Price Book Config Menu [ </b>".$_SESSION['fname']." ".$_SESSION['lname']."<b> ]</b>\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	if ($_SESSION['tlev'] >=8)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Base Pool\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"cat\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Categories\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "						<input type=\"hidden\" name=\"catid\" value=\"0\">\n";
		echo "						<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Retail\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "					<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "                  <input type=\"hidden\" name=\"call\" value=\"cost\">\n";
		echo "                  <input type=\"hidden\" name=\"subq\" value=\"acc\">\n";
		echo "                  <input type=\"hidden\" name=\"phsid\" value=\"1\">\n";
		echo "					<td NOWRAP>\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Labor Cost\">\n";
		echo "					</td>\n";
		echo "					</form>\n";
		echo "					<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "                  <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "                  <input type=\"hidden\" name=\"subq\" value=\"inv\">\n";
		echo "                  <input type=\"hidden\" name=\"phsid\" value=\"10\">\n";
		echo "					<td NOWRAP>\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Inventory Cost\">\n";
		echo "					</td>\n";
		echo "					</form>\n";

		if ($_SESSION['securityid']==26 ||$_SESSION['securityid']==50||$_SESSION['securityid']==58)
		{
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"copy_list\">\n";
			echo "						<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Copy\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
		}

		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"base_vendor_list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Material Master\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	/*
	if ($_SESSION['tlev'] >= 9)
	{
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"exportpb\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Generate PB\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	}
	*/

	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Maintenance Menu\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "		   	</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function pbconfig_button_menu($valdis)
{
	$dis	="";

	$off_ar=array(55,60,89,75,99,144);

	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,tester FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);
	
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<b>Price Book</b>\n";
	echo "		</td>\n";
	echo "		<td align=\"right\">\n";
		
	common_menu_panel();
		
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "   	<td colspan=\"2\" >\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	if (isset($_SESSION['m_plev']) && $_SESSION['m_plev'] >= 8)
	{
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Base Pool\" $dis>\n";
		echo "         			</form>\n";
		echo "				</td>\n";
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"cat\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Categories\" $dis>\n";
		echo "         			</form>\n";
		echo "				</td>\n";
	}

	if ((isset($_SESSION['m_plev']) and $_SESSION['m_plev'] >= 2) and $row1['encon'] == 1)
	{
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "						<input type=\"hidden\" name=\"catid\" value=\"0\">\n";
		echo "						<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Retail\" $dis>\n";
		echo "         			</form>\n";
		echo "				</td>\n";
	}

	if (isset($_SESSION['m_plev']) and $_SESSION['m_plev'] >= 9)
	{
		echo "				<td>\n";
		echo "					<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "              	    <input type=\"hidden\" name=\"call\" value=\"cost\">\n";
		echo "                  	<input type=\"hidden\" name=\"subq\" value=\"acc\">\n";
		echo "                  	<input type=\"hidden\" name=\"phsid\" value=\"1\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Labor Cost\">\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "				<td>\n";
		echo "					<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "              	    <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"inv\">\n";
		echo "						<input type=\"hidden\" name=\"phsid\" value=\"10\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Inv Cost\">\n";
		echo "					</form>\n";
		echo "				</td>\n";
	}

	if (isset($_SESSION['m_plev']) and $_SESSION['m_plev'] >= 8)
	{
		if ($_SESSION['securityid']==26||$_SESSION['securityid']==332||$_SESSION['securityid']==1991)
		{
			echo "      		<td align=\"right\">\n";
			echo "         			<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"copy_list\">\n";
			echo "						<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Copy\">\n";
			echo "         			</form>\n";
			echo "				</td>\n";
		}
	}

	if (isset($_SESSION['m_plev']) && $_SESSION['m_plev'] >= 8)
	{
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"base_vendor_list\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Material Master\" $dis>\n";
		echo "         			</form>\n";
		echo "				</td>\n";
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"activematlist\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\" Active Mats\" $dis>\n";
		echo "         			</form>\n";
		echo "				</td>\n";
	}
	
	if ($_SESSION['rlev'] >= 6 && $row1['encon'] == 1 && $row1['finan_off']==0)
	{
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"showretpb\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list_ret\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Gen Ret PB\">\n";
		echo "         			</form>\n";
		echo "				</td>\n";
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"showcstpb\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list_cst\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Gen Lab CS\">\n";
		echo "         			</form>\n";
		echo "				</td>\n";
	}

	if ((isset($_SESSION['m_plev']) and $_SESSION['m_plev'] >= 1) and $row1['encon'] == 1)
	{
		echo "      		<td align=\"right\">\n";
		echo "         			<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"pbanalyze\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"PB Analyze\">\n";
		echo "         			</form>\n";
		echo "				</td>\n";
	}

	if ($_SESSION['securityid']==26) {
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"procPids\">\n";
		echo "						<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Proc PIDS\">\n";
		echo "         				</form>\n";
		echo "					</td>\n";
	}
	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Maint. Menu\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "						<img class=\"getHelpNode\" id=\"PBConfigMenuHelp\" src=\"images/help.png\" title=\"Pricebook Configuration Menu Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function sys_button_menu()
{
	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td width=\"100px\">\n";
	echo "			<b>&nbsp;System Menu</b>\n";
	echo "   	</td>\n";
	echo "   	<td>\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"logoff\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"null\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Logoff\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Main Menu\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "		   	</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function file_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off,fsenable,fscustomer,fsshared,fsoffice FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,admindigreport,tester,filestoreaccess FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['rlev'],"r");
	
	echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
	echo "<table width=\"950px\">\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\"><b>File Cabinet</b></td>\n";
	echo "   	<td align=\"right\">\n";
		
	common_menu_panel();
		
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"2\" align=\"right\">\n";
	echo "			<table cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\">\n";
	
	//Shared Files
	if (
			(isset($row1['fsshared']) and ($row1['fsshared'] == 1))
			and (isset($row2['filestoreaccess']) and $row2['filestoreaccess'] >= 5)
		)
	{
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list_file_ENT\">\n";
		echo "							<button class=\"btnsysmenu\" id=\"SharedFilesENT\">Shared Files</button>\n";
		/*
		if ($_SESSION['officeid']==197)
		{
			echo "							<button class=\"btnsysmenu\" id=\"SharedFilesENT\">Main Menu</button>\n";
			echo "						<input class=\"buttondkgrypnl\" id=\"SharedFilesENT\" type=\"submit\" value=\"Shared Files\" title=\"Forms, Memos, Policies, and Vendor Pricing offered by Blue Haven Corporate\">\n";
		}
		else
		{
			echo "						<input class=\"buttondkgrypnl\" id=\"SharedFilesENT\" type=\"submit\" value=\"Shared Files\" title=\"Forms, Memos, Policies, and Vendor Pricing offered by Blue Haven Corporate\">\n";
		}
		*/
		
		echo "         				</form>\n";
	}
	
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	
	// Office Files
	if (
			(isset($row1['fsoffice']) and $row1['fsoffice'] == 1)
			and (isset($row2['filestoreaccess']) and $row2['filestoreaccess'] >= 5)
		)
	{
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list_file_OFF\">\n";
		
		if ($_SESSION['officeid']==197)
		{
			echo "							<button class=\"btnsysmenu\">Shared Files (Author)</button>\n";
			//echo "						<input class=\"buttondkgrypnl\" type=\"submit\" value=\"Shared Files (Author)\" title=\"Shared Files Author Mode\">\n";
		}
		else
		{
			echo "							<button class=\"btnsysmenu\">".$_SESSION['offname']." Files</button>\n";
			//echo "						<input class=\"buttondkgrypnl\" type=\"submit\" value=\"".$_SESSION['offname']." Files\" title=\"Your Office File Storage\">\n";
		}
		
		echo "         				</form>\n";
	}
	
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "							<button class=\"btnsysmenu\">Main Menu</button>\n";
	//echo "							<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Main Menu\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "						<img class=\"getHelpNode\" id=\"FileCabinetHelp\" src=\"images/help.png\" title=\"File Cabinet Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</div>\n";
}


function purchasing_button_menu()
{
	$qry0	= "SELECT sgid,sgAction,sgCall,sgCreate,sgRead FROM SecurityLevel WHERE sid=".$_SESSION['securityid']." and sgAction='Purchasing' and sgRead >= 1;";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
	
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" align=\"left\">Purchasing</td>\n";
	echo "   	<td class=\"lg\" align=\"right\">\n";

	common_menu_panel();

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\" colspan=\"2\" align=\"right\">\n";
	
	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			$sgCall_ar[$row0['sgid']]=$row0['sgCall'];
		}
		
		echo "			<table cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "   			<tr>\n";
		
		if (in_array('PurchaseOrderSearch',$sgCall_ar))
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"Purchasing\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"PurchaseOrderSearch\">\n";
			echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Search\" title=\"Search Purchase Orders\">\n";	
			echo "         				</form>\n";
			echo "					</td>\n";
		}
		
		if (in_array('PurchaseOrderPending',$sgCall_ar))
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"Purchasing\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"PurchaseOrderPending\">\n";
			echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Pending\" title=\"Purchase Orders awaiting Approval\">\n";		
			echo "         				</form>\n";
			echo "					</td>\n";
		}
		
		if (in_array('PurchaseOrderList',$sgCall_ar))
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"Purchasing\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"PurchaseOrderList\">\n";
			echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"New\" title=\"Create a New Purchase Order\">\n";
			echo "         				</form>\n";
			echo "					</td>\n";
		}
		
		if (in_array('CheckRequest',$sgCall_ar))
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"Purchasing\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"CheckRequest\">\n";
			//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"CheckRequest\" title=\"Check Request Module\">\n";
			echo "							<button>Check Request</button>\n";
			echo "         				</form>\n";
			echo "					</td>\n";
		}
		
		echo "      			<td align=\"right\">\n";
		echo "         				<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"main\">\n";
		//echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Main Menu\">\n";
		echo "							<button>Main Menu</button>\n";
		echo "         				</form>\n";
		echo "					</td>\n";
		echo "		   		</tr>\n";
		echo "			</table>\n";
	}
	else
	{
		echo 'You do not have approriate Security to Access Purchasing';
	}
	
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function _dynamic_menus($a,$l,$t,$m)
{
	$qry0	= "SELECT sgid,sgAction,sgCall,sgName,sgCreate,sgRead FROM SecurityLevel WHERE sid=".$_SESSION['securityid']." and sgAction='".$a."' and sgType='".$t."' and sgRead >= 1 ORDER BY sgSequence ASC;";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);
	
	//echo $qry0.'<br>';
	
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" align=\"left\"><b>".$l."</b></td>\n";
	echo "   	<td class=\"lg\" align=\"right\">\n";

	common_menu_panel();

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\" colspan=\"2\" align=\"right\">\n";
	echo "			<table cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   			<tr>\n";
	
	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			echo "      			<td align=\"right\">\n";
			echo "         				<form method=\"post\">\n";
			echo "							<input type=\"hidden\" name=\"action\" value=\"".$row0['sgAction']."\">\n";
			
			if (isset($row0['sgCall']) and !empty($row0['sgCall']))
			{
				echo "							<input type=\"hidden\" name=\"call\" value=\"".$row0['sgCall']."\">\n";
			}
			
			if (isset($row0['sgSubq']) and !empty($row0['sgSubq']))
			{
				echo "							<input type=\"hidden\" name=\"subq\" value=\"".$row0['sgSubq']."\">\n";
			}
			
			echo "							<button>".$row0['sgName']."</button>\n";
			echo "         				</form>\n";
			echo "					</td>\n";
		}
	}
	else
	{
		echo "      			<td align=\"right\">You have no access within this module</td>\n";
	}
	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "							<button>Main Menu</button>\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function accounting_menu()
{
	$qry0	= "SELECT * FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$res0	= mssql_query($qry0);
	$row0 	= mssql_fetch_array($res0);
	$nrow0	= mssql_num_rows($res0);
	
	$qry1	= "SELECT * FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1	= mssql_query($qry1);
	$row1 	= mssql_fetch_array($res1);
	$nrow1	= mssql_num_rows($res1);
	
	//echo $qry0.'<br>';
	//echo $qry1.'<br>';
	
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" align=\"left\"><b>Accounting</b></td>\n";
	echo "   	<td class=\"lg\" align=\"right\">\n";

	common_menu_panel();

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\" colspan=\"2\" align=\"right\">\n";
	echo "			<table cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"accountingsystem\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"list_Queues\">\n";
	echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"List\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function sales_button_menu()
{
	$qry0	= "SELECT * FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$res0	= mssql_query($qry0);
	$row0 	= mssql_fetch_array($res0);
	$nrow0	= mssql_num_rows($res0);
	
	$qry1	= "SELECT * FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1	= mssql_query($qry1);
	$row1 	= mssql_fetch_array($res1);
	$nrow1	= mssql_num_rows($res1);
	
	//echo $qry0.'<br>';
	//echo $qry1.'<br>';
	
	echo "<table class=\"outer\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" align=\"left\"><b>Sales</b></td>\n";
	echo "   	<td class=\"lg\" align=\"right\">\n";

	common_menu_panel();

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\" colspan=\"2\" align=\"right\">\n";
	echo "			<table cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "   			<tr>\n";
	
	/*
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"sales\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"New\">\n";
	echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Cart\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"sales\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Search\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	*/
	
	echo "      			<td align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "							<input type=\"hidden\" name=\"action\" value=\"main\">\n";
	echo "							<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Main Menu\">\n";
	echo "         				</form>\n";
	echo "					</td>\n";
	echo "      			<td align=\"center\">\n";
	echo "						<img class=\"getHelpNode\" id=\"SalesMenuHelp\" src=\"images/help.png\" title=\"Sales Menu Help\">\n";
	echo "					</td>\n";
	echo "		   		</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function button_matrix_OLD($valdis=null) {
	echo __FUNCTION__.'<br>';
	if (!isset($_REQUEST['action'])||$_REQUEST['action']==""||$_REQUEST['action']=="main")
	{
		main_button_menu();
	}
	elseif ($_REQUEST['action']=="leads")
	{
		if ($_SESSION['otype']==0 || $_SESSION['otype']==1)
		{
			leads_button_menu($valdis);
		}
		elseif ($_SESSION['otype']==2)
		{
			vendor_button_menu($valdis);
		}
		elseif ($_SESSION['otype']==3)
		{
			contact_button_menu($valdis);
		}
		elseif ($_SESSION['otype']==4)
		{
			leads_button_menu($valdis);
		}
		else
		{
			leads_button_menu($valdis);
		}
	}
	elseif ($_REQUEST['action']=="network")
	{
		network_button_menu();
	}
	elseif ($_REQUEST['action']=="sales")
	{
		sales_button_menu();
	}
	elseif ($_REQUEST['action']=="est")
	{
		est_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="contract")
	{
		contract_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="job")
	{
		job_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="mas")
	{
		mas_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="message")
	{
		mess_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="reports")
	{
		reports_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="update_off")
	{
		main_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="setoffice")
	{
		main_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="pbconfig")
	{
		pbconfig_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="docs")
	{
		doc_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="file")
	{
		file_button_menu($valdis);
	}
	elseif ($_REQUEST['action']=="Purchasing")
	{
		purchasing_button_menu();
	}
	elseif ($_REQUEST['action']=="accountingsystem")
	{
		//_dynamic_menus('accountingsystem','Accounting','Menu0','Buttons');
		//accounting_menu();
		main_button_menu();
	}
	elseif ($_REQUEST['action']=="maint")
	{
		if (isset($_REQUEST['call']) && $_REQUEST['call']=="leads")
		{
			leads_maint_button_menu($valdis);
		}
		//elseif (isset($_REQUEST['call']) && $_REQUEST['call']=="email")
		//{
		//	leads_maint_button_menu($valdis);
		//}
		else
		{
			maint_button_menu($valdis);
		}
	}
}

?>