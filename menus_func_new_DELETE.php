<?php


function main_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT encon,enjob,enmas,endigreport,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,admindigreport,tester,srep FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=$_SESSION['securityid'];

	echo "<table class=\"transnb\" width=\"900\">\n";
	echo "   <tr>\n";
	echo "   	<td>\n";
	echo "			<table class=\"transnb\" align=\"right\">\n";
	echo "   			<tr>\n";

	if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1)
	{
		echo "   				<td align=\"center\">\n";
		echo "						<a href=\"./index.php?action=message&call=list\"><img src=\"images/email_error.png\" title=\"New Messages\"></a>\n";
		echo "   				</td>\n";
	}

	
	if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
	{
		echo "   				<td align=\"center\">\n";
		echo "   					<a href=\"./index.php?action=reports&call=office&subq=commentlist&oid=".$_SESSION['officeid']."\"><img src=\"images/comments.png\" title=\"Office Comments\"></a>\n";
		echo "   				</td>\n";
	}
	
	
	echo "      			<td align=\"right\" valign=\"bottom\">\n";

	display_user_menubar_info();
	
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td>\n";
	
	echo "<div id=\"menubar\" class=\"yuimenubar yuimenubarnav\">\n";
	echo "	<div class=\"bd\">\n";
	echo "		<ul class=\"first-of-type first-of-type\">\n";
	echo "			<li class=\"yuimenubaritem\">\n";
	echo "				<a class=\"yuimenuitemlabel\" href=\"./index.php?action=main\"> Home</a>\n";
	echo "			</li>\n";
	echo "			<li class=\"yuimenubaritem\"><a class=\"yuimenubaritemlabel\" href=\"#\"> Search</a>\n";
	echo "				<div id=\"search\" class=\"yuimenu\">\n";
	echo "					<div class=\"bd\">\n";
	echo "						<ul class=\"first-of-type\">\n";
	
	if ($_SESSION['llev'] >= 5)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=csearch\"> Quick Search</a>\n";
		echo "							</li>\n";
	}
	
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=leads&call=search\"> Lead Search</a>\n";
	echo "							</li>\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=leads&call=appts\"> Appointments</a>\n";
	echo "							</li>\n";
	/*echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=leads&call=callbacks\"> Callbacks</a>\n";
	echo "							</li>\n";*/
	echo "						</ul>\n";
	echo "						<ul>\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=leads&call=new\"> New Lead</a>\n";
	echo "							</li>\n";
	echo "						</ul>\n";
	echo "						<ul>\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"#\"> Open</a>\n";
	echo "								<div id=\"open\" class=\"yuimenu\">\n";
	echo "									<div class=\"bd\">\n";
	echo "										<ul class=\"first-of-type\">\n";
	echo "											<li class=\"yuimenuitem\">\n";
	echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=leads&call=search\"> Leads</a>\n";
	echo "											</li>\n";
	
	if ($_SESSION['elev'] >= 1 && $row1['enest']==1)
	{
		echo "											<li class=\"yuimenuitem\">\n";
		echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=est&call=search\"> Estimates</a>\n";
		echo "											</li>\n";
	}
	
	if ($_SESSION['clev'] >= 1 && $row1['encon']==1)
	{
		echo "											<li class=\"yuimenuitem\">\n";
		echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=contract&call=search\"> Contracts</a>\n";
		echo "											</li>\n";
	}
	
	if ($_SESSION['jlev'] >= 1 && $row1['enjob']==1)
	{
		echo "											<li class=\"yuimenuitem\">\n";
		echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=job&call=search\"> Jobs</a>\n";
		echo "											</li>\n";
	}
	
	if ($_SESSION['jlev'] >= 7 && $row1['enmas']==1)
	{
		echo "											<li class=\"yuimenuitem\">\n";
		echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=mas&call=MAS_search\"> MAS</a>\n";
		echo "											</li>\n";
	}
	
	echo "										</ul>\n";           
	echo "									</div>\n";
	echo "								</div>\n";
	echo "							</li>\n";
	echo "						</ul>\n";
	echo "					</div>\n";
	echo "				</div>\n";
	echo "			</li>\n";
	
	if ($_SESSION['ilev'] >= 5 || $_SESSION['securityid']==26 || $_SESSION['securityid']==332)
	{
		echo "			<li class=\"yuimenubaritem\">\n";
		echo "				<a class=\"yuimenuitemlabel\" href=\"#\"> Inventory</a>\n";
		echo "				<div id=\"search\" class=\"yuimenu\">\n";
		echo "					<div class=\"bd\">\n";
		echo "						<ul class=\"first-of-type\">\n";
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=inventory&call=search\"> Search Inventory</a>\n";
		echo "							</li>\n";
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=inventory&call=pohistory\"> Purchase Order History</a>\n";
		echo "							</li>\n";
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=inventory&call=ponew\"> Create Purchase Order</a>\n";
		echo "							</li>\n";
		echo "						</ul>\n";
		echo "					</div>\n";
		echo "				</div>\n";
		echo "			</li>\n";
	}
	
	echo "			<li class=\"yuimenubaritem\"><a class=\"yuimenubaritemlabel\" href=\"#reports\"> Reports</a>\n";
	echo "				<div id=\"reports\" class=\"yuimenu\">\n";
	echo "					<div class=\"bd\">\n";
	echo "						<ul class=\"first-of-type\">\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=contactlist\"> Contact List</a>\n";
	echo "							</li>\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=standings\"> Dig Standings</a>\n";
	echo "							</li>\n";
	
	if ($_SESSION['rlev'] >= 5 && $row2['tester'] >= 1)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=srpage\"> Sales & Commission</a>\n";
		echo "							</li>\n";
	}
	
	if (isset($row2['admindigreport']) && $row2['admindigreport'] >= 1)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=digreports&subq=admingen_preview&d_moyr=".date('Y').":".date('m')."&access=".$row2['admindigreport']."\"> Admin Digs</a>\n";
		echo "							</li>\n";
	}
	
	if ($_SESSION['rlev'] >= 5 && $row1['finan_off']==0)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=zipreports\"> Zip Code Report</a>\n";
		echo "							</li>\n";
	}
	
	if ($_SESSION['rlev'] >= 1)
	{
		if ($row1['finan_off']==0 && $row1['finan_from']!=0 || $row1['finan_off']==1)
		{
			echo "							<li class=\"yuimenuitem\">\n";
			echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=srsearch\"> Fin Status</a>\n";
			echo "							</li>\n";
		}
	}
	
	if ($_SESSION['rlev'] >= 6)
	{
		if ($row1['finan_off']==1)
		{
			echo "							<li class=\"yuimenuitem\">\n";
			echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=sfinleads\"> Fin Source</a>\n";
			echo "							</li>\n";
		}
	}
	
	if ($_SESSION['rlev'] >= 6)
	{
		if ($row1['finan_off']==1)
		{
			echo "							<li class=\"yuimenuitem\">\n";
			echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=fnexport\"> Fin Summary</a>\n";
			echo "							</li>\n";
		}
	}
	
	if ($_SESSION['rlev'] >= 5)
	{
		if ($row1['finan_off']==0)
		{
			echo "							<li class=\"yuimenuitem\">\n";
			echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=sleads\"> Lead Source</a>\n";
			echo "							</li>\n";
			echo "							<li class=\"yuimenuitem\">\n";
			echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=rleads\"> Lead Result</a>\n";
			echo "							</li>\n";
		}
	}
	
	if ($_SESSION['rlev'] >= 5 && $row1['finan_off']==0)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=salesman_gen\"> Sales Rep</a>\n";
		echo "							</li>\n";
	}
	
	if ($_SESSION['rlev'] >= 9 && $row2['officeid']==89)
	{
		if ($_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==MTRX_ADMIN)
		{
			echo "							<li class=\"yuimenuitem\">\n";
			echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=employeeinfo_401k\"> 401k Data</a>\n";
			echo "							</li>\n";
		}
	}
	
	if ($_SESSION['rlev'] >= 6 && $row1['finan_off']==0)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=IVRreport&subq=stage1\"> 800 Report</a>\n";
		echo "							</li>\n";
	}
	
	if ($_SESSION['rlev'] >= 6 && $row1['finan_off']==0)
	{
		if ($_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==MTRX_ADMIN)
		{
			echo "							<li class=\"yuimenuitem\">\n";
			echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=activity_job_full\"> LCD Report</a>\n";
			echo "							</li>\n";
		}
	}
	
	if ($_SESSION['rlev'] >= 8 && $row1['finan_off']==0)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=loggedin\"> Logged Users</a>\n";
		echo "							</li>\n";
	}
	
	if ($_SESSION['rlev'] >= 6 || $_SESSION['csrep'] >= 6)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=complaints\"> CS Reports</a>\n";
		echo "							</li>\n";
	}
	
	if ($_SESSION['rlev'] >= 6 && $row1['encon'] == 1 && $row1['finan_off']==0)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=showretpb&subq=list_ret\"> Gen Ret PB</a>\n";
		echo "							</li>\n";
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=showcstpb&subq=list_ret\"> Gen Lab CS</a>\n";
		echo "							</li>\n";
	}
	
	if ($_SESSION['rlev'] >= 6 && $row1['endigreport'] == 1 && $row1['finan_off']==0 || $_SESSION['officeid']==89)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=digreports\"> Dig Reports</a>\n";
		echo "							</li>\n";
	}
	
	if ($row1['finan_off']==0 && $_SESSION['securityid'] == 26 || $_SESSION['securityid'] == 332 || $_SESSION['securityid']==50 || $_SESSION['securityid']==1053 || $_SESSION['securityid']==1154)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=offfeesched&subq=list\"> Fee Schedule</a>\n";
		echo "							</li>\n";
	}
	
	
	echo "						</ul>\n";
	
	if ($_SESSION['rlev'] >= 6 && $row1['finan_off']==0)
	{
		echo "						<ul>\n";
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=operating&subq=clist\"> Operating</a>\n";
		echo "							</li>\n";
		/*echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"#\"> Operating</a>\n";
		echo "								<div id=\"operating\" class=\"yuimenu\">\n";
		echo "									<div class=\"bd\">\n";
		echo "										<ul class=\"first-of-type\">\n";
		echo "											<li class=\"yuimenuitem\">\n";
		echo "												<a class=\"yuimenuitemlabel\" href=\"#admin\"> Admin</a>\n";
		echo "											</li>\n";
		echo "											<li class=\"yuimenuitem\">\n";
		echo "												<a class=\"yuimenuitemlabel\" href=\"#cib\"> Cash in Bank</a>\n";
		echo "											</li>\n";
		echo "											<li class=\"yuimenuitem\">\n";
		echo "												<a class=\"yuimenuitemlabel\" href=\"#110p\"> 110%</a>\n";
		echo "											</li>\n";
		echo "											<li class=\"yuimenuitem\">\n";
		echo "												<a class=\"yuimenuitemlabel\" href=\"#arec\"> AR</a>\n";
		echo "											</li>\n";
		echo "										</ul>\n";            
		echo "									</div>\n";
		echo "								</div> \n";
		echo "							</li>\n";*/
		echo "						</ul>\n";
	}
	
	echo "					</div>\n";
	echo "				</div>\n";
	echo "			</li>\n";
	echo "			<li class=\"yuimenubaritem\"><a class=\"yuimenubaritemlabel\" href=\"#messages\">Messages</a>\n";
	echo "				<div id=\"messages\" class=\"yuimenu\">\n";
	echo "					<div class=\"bd\">\n";
	echo "						<ul class=\"first-of-type\">\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=message&call=new\"> Compose</a>\n";
	echo "							</li>\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=message&call=list&subq=unread\"> View Unread</a>\n";
	echo "							</li>\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=message&call=list\"> View Saved</a>\n";
	echo "							</li>\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\./index.php?action=message&call=listsent\"> View Sent</a>\n";
	echo "							</li>\n";
	echo "						</ul>\n";							
	echo "					</div>\n";
	echo "				</div>\n";
	echo "			</li>\n";
	echo "			<li class=\"yuimenubaritem\">\n";
	echo "				<a class=\"yuimenubaritemlabel\" href=\"#system\">System</a>\n";
	echo "				<div id=\"system\" class=\"yuimenu\">\n";
	echo "					<div class=\"bd\">\n";
	echo "						<ul class=\"first-of-type\">\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=maint&call=users&subq=rp&userid=".$_SESSION['securityid']."\"> Change Password</a>\n";
	echo "							</li>\n";
	
	if ($row2['srep'] == 1)
	{
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=maint&call=users&subq=pp&userid=".$_SESSION['securityid']."\"> PriceBook Adjust</a>\n";
		echo "							</li>\n";
	}
	
	echo "						</ul>\n";
	
	if ($_SESSION['tlev'] >= 7)
	{
		office_chng_list();
	}
	
	if (isset($_SESSION['m_plev']) || isset($_SESSION['m_llev']) || isset($_SESSION['m_ulev']))
	{
		echo "						<ul>\n";
		echo "							<li class=\"yuimenuitem\">\n";
		echo "								<a class=\"yuimenuitemlabel\" href=\"#\"> Maintenance</a>\n";
		echo "								<div id=\"Maintenance\" class=\"yuimenu\">\n";
		echo "									<div class=\"bd\">\n";
		echo "										<ul class=\"first-of-type\">\n";
		
		if ($_SESSION['tlev'] >= 9 && $_SESSION['m_plev'] >=1)
		{
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=pbconfig&call=bpool\"> Base Pool</a>\n";
			echo "											</li>\n";
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=pbconfig&call=cat\"> Categories</a>\n";
			echo "											</li>\n";
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=pbconfig&call=acc\"> Retail</a>\n";
			echo "											</li>\n";
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=pbconfig&call=cost\"> Labor Cost</a>\n";
			echo "											</li>\n";
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=pbconfig&call=inv\"> Material Cost</a>\n";
			echo "											</li>\n";
		}
		
		if ($_SESSION['tlev'] >= 9 && $row1['finan_off']==0)
		{
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=maint&call=commbuilder\"> Commission Builder</a>\n";
			echo "											</li>\n";
		}
		
		if ($_SESSION['rlev'] >= 9 && $row1['finan_off']==0)
		{
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=reports&call=standings_config\"> Dig Standings Config</a>\n";
			echo "											</li>\n";
		}
		
		if ($_SESSION['tlev'] >= 1 && $_SESSION['m_llev'] >= 1)
		{
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=maint&call=leads\"> Leads</a>\n";
			echo "											</li>\n";
		}
		
		if ($_SESSION['tlev'] >= 1 && $_SESSION['m_ulev'] >= 1)
		{
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=maint&call=users\"> Users</a>\n";
			echo "											</li>\n";
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=maint&call=off\"> Offices</a>\n";
			echo "											</li>\n";
		}
		
		if ($_SESSION['mlev'] >= 9 && $_SESSION['m_llev'] >= 9 && $_SESSION['officeid']==89)
		{
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=maint&call=IVR&subq=list\"> IVR/800</a>\n";
			echo "											</li>\n";
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=maint&call=IVR&subq=zip_maint\"> Matrix</a>\n";
			echo "											</li>\n";
		}
		
		if ($_SESSION['securityid'] == 26 || $_SESSION['securityid']==332)
		{
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"http://jms/schedmanualleadproc.php\" target=\"_new\">Get Leads</a>\n";
			echo "											</li>\n";
			
			echo "											<li class=\"yuimenuitem\">\n";
			echo "												<a class=\"yuimenuitemlabel\" href=\"./index.php?action=sourceconfig\"> Source Codes</a>\n";
			echo "											</li>\n";
		}
		
		echo "										</ul>\n";           
		echo "									</div>\n";
		echo "								</div>\n";
		echo "							</li>\n";
		echo "						</ul>\n";
	}
	
	echo "						<ul>\n";
	echo "							<li class=\"yuimenuitem\">\n";
	echo "								<a class=\"yuimenuitemlabel\" href=\"./index.php?action=logoff\"> Logout</a>\n";
	echo "							</li>\n";
	echo "						</ul>\n";
	echo "					</div>\n";
	echo "				</div>\n";
	echo "			</li>\n";
	echo "		</ul>\n";
	echo "	</div>\n";
	echo "</div>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}



function main_button_menuOLD($valdis)
{
	$dis	="";
	//$admin_off_ar=array(89,138);
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT enest,encon,enjob,enmas,finan_off FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=$_SESSION['securityid'];

	if ($valdis==1)
	{
		$dis="DISABLED";
	}

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100\">\n";
	echo "			<b>&nbsp;Main Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" width=\"150\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" width=\"100\">\n";

	if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1)
	{
		echo "	<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"message\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"list\">\n";
		echo "		<input class=\"checkboxgry\" type=\"image\" src=\"images/email.png\" alt=\"New Messages\">\n";
		echo "	</form>\n";
	}

	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\">\n";

	if (empty($_SESSION['action']) || $_SESSION['action']=="None" || $_SESSION['action']=="main")
	{
		set_assistant();
	}
	
	echo "					</td>\n";
	
	if ($_SESSION['tlev'] >= 7)
	{
		echo "      			<td align=\"right\">\n";

		if (empty($_SESSION['action'])||$_SESSION['action']=="None"||$_SESSION['action']=="main"||$_SESSION['action']=="update_off")
		{
			set_office();
		}

		echo "					</td>\n";
	}

	if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
	{
		echo "	<td class=\"lg\" align=\"center\">\n";
		echo "	<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
		echo "		<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "		<input class=\"checkboxgry\" type=\"image\" src=\"images/comments.png\" alt=\"Office Comments\">\n";
		echo "	</form>\n";
		echo "	</td>\n";
	}

	//if ($_SESSION['llev'] >= 5 && $row1['finan_off']==0)
	if ($_SESSION['llev'] >= 5)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"csearch\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Cust Search\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['llev'] >= 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
		echo "      			<td align=\"right\">\n";
		
		if ($row1['finan_off']==1)
		{
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Contacts\" $dis>\n";
		}
		else
		{
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Leads\" $dis>\n";
		}
		
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['elev'] >= 1 && $row1['enest']==1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Estimates\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['clev'] >= 1 && $row1['encon']==1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Contracts\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['jlev'] >= 1 && $row1['enjob']==1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Jobs\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['jlev'] >= 7 && $row1['enmas']==1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"mas\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"MAS_search\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"MAS\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['rlev'] >= 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Reports\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['mlev'] >= 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"message\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Messages\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['mlev'] >= 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"message\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"new_feedback\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Feedback\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Maintenance\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"logoff\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Logoff\">\n";
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
}

function est_button_menu()
{
	$qry2	= "SELECT officeid,admstaff FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);
	
	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	$_SESSION['aid']	=aidbuilder($_SESSION['elev'],"e");
	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>&nbsp;Estimate Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	//if ($_SESSION['elev'] > 5)
	//{
	
	if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
	{
		echo "	<td class=\"lg\" align=\"center\">\n";
		echo "	<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
		echo "		<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "		<input class=\"checkboxgry\" type=\"image\" src=\"images/comments.png\" alt=\"Office Comments\">\n";
		echo "	</form>\n";
		echo "	</td>\n";
	}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Search\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	//}

	/*
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"List\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	*/
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
	$qry2	= "SELECT officeid,admstaff FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);
	
	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	$_SESSION['aid']	=aidbuilder($_SESSION['clev'],"c");
	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>&nbsp;Contract Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	
	if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
	{
		echo "	<td class=\"lg\" align=\"center\">\n";
		echo "	<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
		echo "		<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "		<input class=\"checkboxgry\" type=\"image\" src=\"images/comments.png\" alt=\"Office Comments\">\n";
		echo "	</form>\n";
		echo "	</td>\n";
	}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Search\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	/*
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"List\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	*/
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

function job_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry2	= "SELECT officeid,admstaff FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['jlev'],"j");

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	$brdr=0;
	echo "<table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>&nbsp;Job Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
	
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" width=\"100\">\n";

	if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1)
	{
		echo "<b><font color=\"red\">".$row['mcnt']."</font> New Message";

		if ($row['mcnt'] > 1)
		{
			echo "s";
		}
	}

	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	
	if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
	{
		echo "	<td class=\"lg\" align=\"center\">\n";
		echo "	<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
		echo "		<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "		<input class=\"checkboxgry\" type=\"image\" src=\"images/comments.png\" alt=\"Office Comments\">\n";
		echo "	</form>\n";
		echo "	</td>\n";
	}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Search\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	/*
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"List\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	*/
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

function mas_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry2	= "SELECT officeid,admstaff FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['jlev'],"j");

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>&nbsp;MAS Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" width=\"100\">\n";

	if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1)
	{
		echo "<b><font color=\"red\">".$row['mcnt']."</font> New Message";

		if ($row['mcnt'] > 1)
		{
			echo "s";
		}
	}

	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	
	if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
	{
		echo "	<td class=\"lg\" align=\"center\">\n";
		echo "	<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
		echo "		<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "		<input class=\"checkboxgry\" type=\"image\" src=\"images/comments.png\" alt=\"Office Comments\">\n";
		echo "	</form>\n";
		echo "	</td>\n";
	}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"mas\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"MAS_search\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Search\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	/*
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"mas\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"MAS_list\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"List\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	*/
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

function leads_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry1  = "SELECT finan_off,ldexport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,gmreports FROM security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['llev'],"l");

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	
	if ($row1['finan_off']==0)
	{
		echo "			<b>&nbsp;Leads Menu\n";
	}
	else
	{
		echo "			<b>&nbsp;Contacts Menu\n";
	}
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	
	if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0)
	{
		display_assistant_to();
	}
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" width=\"100\">\n";

	if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1)
	{
		echo "<b><font color=\"red\">".$row['mcnt']."</font> New Message";

		if ($row['mcnt'] > 1)
		{
			echo "s";
		}
	}

	echo "   	</td>\n";	
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	
	if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
	{
		echo "	<td class=\"lg\" align=\"center\">\n";
		echo "	<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
		echo "		<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "		<input class=\"checkboxgry\" type=\"image\" src=\"images/comments.png\" alt=\"Office Comments\">\n";
		echo "	</form>\n";
		echo "	</td>\n";
	}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"search\">\n";
	echo "      			<td align=\"right\">\n";
	
	if ($row1['finan_off']==0)
	{
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Search\">\n";
	}
	else
	{
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Cont Search\">\n";
	}
	
	echo "					</td>\n";
	echo "         		</form>\n";
	
	if ($row1['finan_off']==1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"sales_search\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Sales Search\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"showcalendar\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Calendar\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"appts\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Appointments\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"new\">\n";
	echo "      			<td align=\"right\">\n";
	
	if ($row1['finan_off']==0)
	{
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Lead\">\n";
	}
	else
	{
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Contact\">\n";
	}
	
	echo "					</td>\n";
	echo "         		</form>\n";
	
	if ($row2['gmreports']==1 && $row1['ldexport']==1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"exports\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Export\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
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
}

function leads_maint_button_menu()
{
	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>&nbsp;Leads Maintenance Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
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
		/*echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Sorted\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";*/
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"access_report\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Access Report\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['tlev'] >= 9 && $_SESSION['m_llev'] >= 9)
	{
		$qry0 = "SELECT SYS_ADMIN,MTRX_ADMIN FROM master..bhest_config;";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		
		if ($_SESSION['securityid']==$row0['SYS_ADMIN'] || $_SESSION['securityid']==$row0['MTRX_ADMIN'])
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
		}
		
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"upfile1\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Lead Import\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		
		/*echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"capps\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Cred App\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";*/
	}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Maintenance Menu\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "		   	</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function reports_button_menu()
{
	error_reporting(JMS_DEBUG);
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1  = "SELECT encon,enjob,enmas,endigreport,entripinfo,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2	= "SELECT officeid,admstaff,admindigreport,tester FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res2	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$_SESSION['aid']	=aidbuilder($_SESSION['rlev'],"r");

	if (isset($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>&nbsp;Report Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	
	if (in_array($row2['officeid'],$_SESSION['admin_offs']) && $row2['admstaff'] > 0)
	{
		echo "	<td class=\"lg\" align=\"center\">\n";
		echo "	<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"commentlist\">\n";
		echo "		<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "		<input class=\"checkboxgry\" type=\"image\" src=\"images/comments.png\" alt=\"Office Comments\">\n";
		echo "	</form>\n";
		echo "	</td>\n";
	}

	if ($_SESSION['llev'] >=5 && $_SESSION['rlev'] >=5)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"csearch\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Cust Search\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	/*if ($_SESSION['rlev'] >= 9)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"orgchart\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Org Chart\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}*/

	if ($_SESSION['rlev'] >= 1 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"standings\">\n";
		echo "						<input type=\"hidden\" name=\"brept_yr\" value=\"2009\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Dig Standings\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['rlev'] >= 9 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"standings_config\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"DS Config\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	if (isset($row2['admindigreport']) && $row2['admindigreport'] >= 1)
	{
		echo "         		<form id=\"rt_digs\" method=\"post\">\n";
		echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "					<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
		echo "					<input type=\"hidden\" name=\"subq\" value=\"admingen_preview\">\n";
		echo "					<input type=\"hidden\" name=\"d_moyr\" value=\"".date('Y').":".date('m')."\">\n";
		echo "					<input type=\"hidden\" name=\"access\" value=\"".$row2['admindigreport']."\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Admin Digs\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['rlev'] >= 5 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"zipreports\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Zip Report\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['rlev'] >= 1)
	{
		if ($row1['finan_off']==0 && $row1['finan_from']!=0 || $row1['finan_off']==1)
		{
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"srsearch\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\" Fin Status\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
		}
	}		
	
	if ($_SESSION['rlev'] >= 6)
	{
		if ($row1['finan_off']==1)
		{
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"sfinleads\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Fin Source\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
			/*
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"finleads\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Finan Result\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
			*/
		}
	}
	
	if ($_SESSION['rlev'] >= 6)
	{
		if ($row1['finan_off']==1)
		{
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"fnexport\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Fin Summary\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
		}
	}
	
	if ($_SESSION['rlev'] >= 5)
	{
		if ($row1['finan_off']==0)
		{
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"sleads\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Lead Source\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
			
			if ($_SESSION['securityid']==26)
			{
				echo "         		<form method=\"post\">\n";
				echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "						<input type=\"hidden\" name=\"call\" value=\"LeadSource\">\n";
				echo "      			<td align=\"right\">\n";
				echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"New Source\">\n";
				echo "					</td>\n";
				echo "         		</form>\n";
			}
			
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"rleads\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Lead Result\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
		}
	}

	/*
	if ($_SESSION['rlev'] >= 7)
	{
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"tinternet\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Internet Total\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	}
	*/

	if ($_SESSION['rlev'] >= 5 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"salesman_gen\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Sales Rep\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['rlev'] >= 5 && $row2['tester'] >= 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"srpage\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"S & C\" title=\"Sales & Commission Report\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	/*if ($_SESSION['rlev'] >= 9 && $row2['officeid']==89)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"srpage_MAS\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"S & C MAS\" title=\"Sales & Commission MAS RAW\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}*/
	
	/*if ($_SESSION['rlev'] >= 9 && $row2['officeid']==89)
	{
		if ($_SESSION['securityid'] == 26 || $_SESSION['securityid'] == 332)
		{
			echo "         		<form action=\"./export/masempfin.php\" method=\"post\" target=\"_new\">\n";
			//echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			//echo "						<input type=\"hidden\" name=\"call\" value=\"masempfinconsolidated\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"401k Data\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
		}
	}*/

	if ($_SESSION['rlev'] >= 6 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"IVRreport\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"stage1\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"800 Report\" title=\"800 Call Matrix Report\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	if ($_SESSION['rlev'] >= 6 && $row1['finan_off']==0)
	{
		if ($_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==MTRX_ADMIN)
		{
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"activity_job_full\">\n";
			//echo "						<input type=\"hidden\" name=\"subq\" value=\"stage1\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"LCD Report\" title=\"Lead - Contract - Dig Report\">\n";
			echo "					</td>\n";
			echo "         		</form>\n";
		}
	}

	if ($_SESSION['rlev'] >= 8 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"loggedin\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Logged Users\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	if ($_SESSION['rlev'] >= 6 || $_SESSION['csrep'] >= 6)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"complaints\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"CSR\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['rlev'] >= 6 && $row1['encon'] == 1 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"showretpb\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list_ret\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Gen Ret PB\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"showcstpb\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list_cst\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Gen Lab CS\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		/*
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"exportpb\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list_mcst\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Gen Mat CS\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		*/
	}

	/*
	if ($_SESSION['rlev'] >= 9 && $_SESSION['securityid']==26)
	{
	echo "         		<form method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"cursorlist\">\n";
	echo "      			<td align=\"right\">\n";
	echo "				<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Cursor List\">\n";
	echo "			</td>\n";
	echo "         		</form>\n";

	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"off_total\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Totals\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	}
	*/

	if ($_SESSION['rlev'] >= 6 && $row1['endigreport'] == 1 && $row1['finan_off']==0 || $_SESSION['officeid']==89)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"digreports\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Dig Reports\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	/*
	if ($row1['finan_off']==0 && $_SESSION['rlev'] >= 6 && $row1['endigreport'] == 1 || $_SESSION['officeid']==89)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"renovreports\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Renovations\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	*/

	if ($_SESSION['rlev'] >= 6 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
		//echo "						<input type=\"hidden\" name=\"subq\" value=\"preopstate\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"clist\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Operating\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	/*
	if ($_SESSION['rlev'] >= 9 && $_SESSION['tlev'] >= 9 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"distinct_matlist\">\n";
		//echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"VCP Mat List\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	*/

	/*	
	if ($_SESSION['rlev'] >= 9 && $_SESSION['off_demo']==1 && $row1['finan_off']==0)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"officerep\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Office Demo\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	*/
	
	if ($row1['finan_off']==0 && $_SESSION['securityid'] == 26 || $_SESSION['securityid'] == 332 || $_SESSION['securityid']==50 || $_SESSION['securityid']==1053 || $_SESSION['securityid']==1154)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"offfeesched\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Fee Schedule\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	/*if ($_SESSION['securityid'] == SYS_ADMIN)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"evlogreport\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Event Log\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}*/
	
	/*
	if ($_SESSION['rlev'] >= 9 && $row1['finan_off']==0 && $_SESSION['securityid']==26)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"contrval\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"LtC Value\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	*/

	/*
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"job\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Job\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	*/
	
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

	if (isset($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "</div>\n";
	}
}

function mess_button_menu()
{
	$qry  = "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res  = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$_SESSION['aid']	=aidbuilder($_SESSION['mlev'],"m");
	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>&nbsp;Message Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" width=\"100\">\n";

	if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1)
	{
		echo "<b><font color=\"red\">".$row['mcnt']."</font> New Message";

		if ($row['mcnt'] > 1)
		{
			echo "s";
		}
	}

	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"list\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Received\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"listsent\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Sent\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"new\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Compose\">\n";
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
}

function maint_button_menu($valdis)
{
	$dis	="";
	$qry	= "SELECT COUNT(mid) as mcnt FROM messages WHERE sendto='".$_SESSION['securityid']."' AND viewed='0';";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);

	if ($valdis==1)
	{
		$dis="DISABLED";
	}

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>&nbsp;Maint Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" width=\"100\">\n";

	if ($row['mcnt'] > 0 && $_SESSION['mlev'] >= 1)
	{
		echo "<b><font color=\"red\">".$row['mcnt']."</font> New Message";

		if ($row['mcnt'] > 1)
		{
			echo "s";
		}
	}

	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"conv_slevels\">\n";
	echo "      			<td align=\"right\">\n";
	//echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Convert SL\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";

	if ($_SESSION['tlev'] >=1 && $_SESSION['m_plev'] >= 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		//echo "						<input type=\"hidden\" name=\"call\" value=\"pbconfig\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Price Book\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['tlev'] >= 1 && $_SESSION['m_llev'] >= 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Leads\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['tlev'] >= 1 && $_SESSION['m_ulev'] >= 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Users\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"off\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Offices\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	if ($_SESSION['mlev'] >= 9 && $_SESSION['m_llev'] >= 9 && $_SESSION['officeid']==89)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"IVR/800\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"zip_maint\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Matrix\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	if ($_SESSION['mlev'] >= 9 && $_SESSION['m_llev'] >= 9 && $_SESSION['officeid']==89)
	{
		echo "         		<form method=\"post\">\n";
		//echo "         		<form action=\"https://jms-dev\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"capps\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Cred Apps\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['mlev'] >= 9 && $_SESSION['m_mlev'] >= 9 && $_SESSION['securityid']==26)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"sysmess\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Sys Messages\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}
	
	if ($_SESSION['securityid'] == 26 || $_SESSION['securityid']==332)
	{
		echo "      			<td align=\"right\">\n";
		echo "						<a href=\"http://jms/schedmanualleadproc.php\" target=\"_new\">[ Get Leads ]</a>\n";
		echo "					</td>\n";
	}

	/*
	http://jms/schedmanualleadproc.php
	if ($_SESSION['tlev'] >= 8 && $_SESSION['m_tlev'] >= 1)
	{
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"events\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Event Log\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	}
	*/
	/*
	if ($_SESSION['tlev'] >= 9 && $_SESSION['m_tlev'] >= 9)
	{
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"mail_send\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Mail Test\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	}
	*/
	
	//if ($_SESSION['securityid']==26 || $_SESSION['securityid']==1550 || $_SESSION['securityid']==543)
	//{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"pp\">\n";
		echo "						<input type=\"hidden\" name=\"userid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"PB Adjusts\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	//}
	
	echo "         		<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"users\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"rp\">\n";
	echo "						<input type=\"hidden\" name=\"userid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "      			<td align=\"right\">\n";
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Options\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	
	if ($_SESSION['securityid'] == 26)
	{
		echo "      			<td align=\"right\">\n";
		echo "						<a href=\"./index.php?action=maint&call=users&subq=rp&userid=".$_SESSION['securityid']."\">[ Options ]</a>\n";
		echo "					</td>\n";
	}
	
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
	$qry1  = "SELECT encon,enjob,enmas,endigreport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1  = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	if ($valdis==1)
	{
		$dis="DISABLED";
	}

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>Price Book Menu\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\" align=\"left\">\n";
		
	display_user_menubar_info();
	
	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
	echo "			<table align=\"right\">\n";
	echo "   			<tr>\n";

	if ($_SESSION['tlev'] >=1 && $_SESSION['m_plev'] >= 8)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Base Pool\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"cat\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Categories\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['tlev'] >=1 && $_SESSION['m_plev'] >= 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "						<input type=\"hidden\" name=\"catid\" value=\"0\">\n";
		echo "						<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Retail\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['tlev'] >=1 && $_SESSION['m_plev'] >= 9)
	{
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
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Inv Cost\">\n";
		echo "					</td>\n";
		echo "					</form>\n";
	}

	if ($_SESSION['tlev'] >=1 && $_SESSION['m_plev'] >= 8)
	{
		if ($_SESSION['securityid']==26 ||$_SESSION['securityid']==50||$_SESSION['securityid']==58||$_SESSION['securityid']==332||$_SESSION['securityid']==1190)
		{
			echo "         		<form method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"copy_list\">\n";
			echo "						<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
			echo "      			<td align=\"right\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Copy\" $dis>\n";
			echo "					</td>\n";
			echo "         		</form>\n";
		}
	}

	if ($_SESSION['tlev'] >=1 && $_SESSION['m_plev'] >= 8)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"base_vendor_list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Material Master\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"activematlist\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\" Active Mats\" $dis>\n";
		echo "					</td>\n";
		echo "         		</form>\n";
	}

	if ($_SESSION['tlev'] >= 1 && $_SESSION['m_plev'] >= 1 && $row1['encon'] == 1)
	{
		echo "         		<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"pbanalyze\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"PB Analyze\">\n";
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
	echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Maint. Menu\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "		   	</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function sys_button_menu()
{
	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"lg\" width=\"100px\">\n";
	echo "			<b>&nbsp;System Menu</b>\n";
	echo "   	</td>\n";
	echo "   	<td class=\"lg\">\n";
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



function menu_system()
{
	main_menu();
	
	/*
	if (!isset($_REQUEST['action'])||$_REQUEST['action']==""||$_REQUEST['action']=="main")
	{
		main_button_menu();
	}
	elseif ($_REQUEST['action']=="leads")
	{
		leads_button_menu($valdis);
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
	elseif ($_REQUEST['action']=="maint")
	{
		if (isset($_REQUEST['call']) && $_REQUEST['call']=="leads")
		{
			leads_maint_button_menu($valdis);
		}
		else
		{
			maint_button_menu($valdis);
		}
	}
	
	*/
}

function button_matrixOLD()
{
	if (!isset($_REQUEST['action'])||$_REQUEST['action']==""||$_REQUEST['action']=="main")
	{
		main_button_menu();
	}
	elseif ($_REQUEST['action']=="leads")
	{
		leads_button_menu($valdis);
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
	elseif ($_REQUEST['action']=="maint")
	{
		if (isset($_REQUEST['call']) && $_REQUEST['call']=="leads")
		{
			leads_maint_button_menu($valdis);
		}
		else
		{
			maint_button_menu($valdis);
		}
	}
}

?>