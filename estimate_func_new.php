<?php
//echo $_SERVER["SCRIPT_NAME"].'<br>'; 


echo "Development Version<br>";

function quote_matrix()
{
	//load_pricebook_data();
	
	/*$qryA = "SELECT pb_code FROM jest..offices WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);*/
	//echo 'INTO ESTMATRIX<br>';
	
	if (!isset($_SESSION['pricebookdata']) || $_SESSION['pbupdate'] > 25)
	{
		load_pricebook_data();
	}
	elseif (isset($_REQUEST['pricebookdatareset']) && $_REQUEST['pricebookdatareset']==1)
	{
		load_pricebook_data();
	}
	
	if (!isset($_REQUEST['call'])||$_REQUEST['call']=="None")
	{
		//echo "<font color=\"red\">Error!</font>\$call not set.";
	}
	elseif ($_REQUEST['call']=="new")
	{
		if (isset($_REQUEST['esttype']) && $_REQUEST['esttype']=='Q')
		{
			//echo 'NEW QUOTE<BR>';
			new_quote();
		}
		else
		{
			matrix0();
		}
	}
	elseif ($_REQUEST['call']=="saveest")
	{
		saveest();
	}
	elseif ($_REQUEST['call']=="view_retail")
	{
		viewest_retail();
	}
	elseif ($_REQUEST['call']=="view_retail_print")
	{
		viewest_retail_print($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="view_cost")
	{
		viewest_cost();
	}
	elseif ($_REQUEST['call']=="view_cost_print")
	{
		viewest_cost_print($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="view_addnew")
	{
		viewest_addnew($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="update_contract_amt")
	{
		update_contract_amt($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="add_acc_items")
	{
		add_acc_items($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="acc_adds_addendum")
	{
		add_acc_items_add($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="addadj")
	{
		addadj_init($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="adjins")
	{
		addadj_ins($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="edit_bid")
	{
		edit_bid();
	}
	elseif ($_REQUEST['call']=="edit_bid_jobmode_add")
	{
		edit_bid_jobmode_add();
	}
	elseif ($_REQUEST['call']=="edit_bid_jobmode_delete")
	{
		//echo "Contract VBJM<br>";
		edit_bid_jobmode_delete();
	}
	elseif ($_REQUEST['call']=="edit_mpa_jobmode_add")
	{
		edit_mpa_jobmode_add();
	}
	elseif ($_REQUEST['call']=="edit_mpa_jobmode_delete")
	{
		//echo "Contract VBJM<br>";
		edit_mpa_jobmode_delete();
	}
	elseif ($_REQUEST['call']=="edit_bid_update")
	{
		edit_bid_update();
	}
	elseif ($_REQUEST['call']=="edit_bid_delete")
	{
		edit_bid_delete();
	}
	elseif ($_REQUEST['call']=="bidins")
	{
		bid_addins($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="update")
	{
		updateest($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="insertest_add") // Inserts Addendum Header Variables
	{
		insertest_add($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="update_acc")
	{
		//echo 'UPDATING...<br>';
		update_acc($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="pop_update")
	{
		pop_updateest($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="applyou")
	{
		apply_overage($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="deleteou")
	{
		delete_overage($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="applybu")
	{
		apply_bullet($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="deletebu")
	{
		delete_bullet($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="delete_est1")
	{
		delete_est();
	}
	elseif ($_REQUEST['call']=="delete_est2")
	{
		delete_est($_REQUEST['estid']);
	}
	elseif ($_REQUEST['call']=="cview")
	{
		//echo "TESTC";
		cform_view();
	}
	elseif ($_REQUEST['call']=="search")
	{
		est_search();
	}
	elseif ($_REQUEST['call']=="search_results")
	{
		listest();
	}
	elseif ($_REQUEST['call']=="list")
	{
		listest();
	}
	elseif ($_REQUEST['call']=="chistory_add")
	{
		chistory_add();
	}
	elseif ($_REQUEST['call']=="chistory")
	{
		//echo "HISTORY";
		chistory_list();
	}
	elseif ($_REQUEST['call']=="set_digdate")
	{
		set_digdate();
	}
	elseif ($_REQUEST['call']=="set_clsdate")
	{
		set_clsdate();
	}
	elseif ($_REQUEST['call']=="set_condate")
	{
		set_condate();
	}
	elseif ($_REQUEST['call']=="biddel")
	{
		edit_bid_jobmode_delete();
	}
	elseif ($_REQUEST['call']=="mpadel")
	{
		edit_mpa_jobmode_delete();
	}
	elseif ($_REQUEST['call']=="setdemomode")
	{
		setdemomode();
	}
}

function est_search()
{
	//ini_set('display_errors','On');
	$acclist=explode(",",$_SESSION['aid']);
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);

	echo "<table width=\"50%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\"><b>Estimate Search Tool</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                  <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Data Field</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Input Parameter</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Type</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Renov Only</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Sort</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Order</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b></b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	//echo "											<input type=\"hidden\" name=\"subq\" value=\"estnum\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\">\n";
	echo "												<select name=\"subq\">\n";
	echo "                                 		<option value=\"last_name\">Customer Last Name</option>\n";
	//echo "                                 		<option value=\"enum\">Estimate #</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo " 	                                <td align=\"left\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" name=\"sval\" size=\"20\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo " 	                                <td align=\"left\" valign=\"bottom\">\n";
	echo "										<select name=\"etype\">\n";
	echo "											<option value=\"E\">Estimate</option>\n";
	
	if ($_SESSION['securityid']==543||$_SESSION['securityid']==1550||$_SESSION['securityid']==26||$_SESSION['securityid']==332)
	{
		echo "											<option value=\"Q\">Quote</option>\n";
	}
	
	echo "										</select>\n";
	echo "									</td>\n";
	echo "                              	<td align=\"center\" valign=\"bottom\"><input class=\"transnb\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this Box to Show Renovations Only\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"a.estid\" SELECTED>Estimate #</option>\n";
	echo "                                 		<option value=\"a.added\">Insert Date</option>\n";
	echo "                                 		<option value=\"b.clname\">Last Name</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                 		<option value=\"DESC\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "         								</form>\n";
	echo "										</tr>\n";

	//if ($_SESSION['llev'] >= 5)
	//{
	echo "										<tr>\n";
	echo "         								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Salesman:</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"assigned\">\n";

	while ($row1 = mssql_fetch_array($res1))
	{
		if (in_array($row1['securityid'],$acclist))
		{
			$secl=explode(",",$row1['slevel']);
			if ($secl[6]==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}

			echo "                                    	<option value=\"".$row1['securityid']."\" class=\"".$ostyle."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
		}
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	echo " 	                                <td align=\"left\" valign=\"bottom\">\n";
	echo "										<select name=\"etype\">\n";
	echo "											<option value=\"E\">Estimate</option>\n";
	
	if ($_SESSION['securityid']==543||$_SESSION['securityid']==1550||$_SESSION['securityid']==26||$_SESSION['securityid']==332)
	{
		echo "											<option value=\"Q\">Quote</option>\n";
	}
	
	echo "										</select>\n";
	echo "									</td>\n";
	echo "                              	<td align=\"center\" valign=\"bottom\"><input class=\"transnb\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this Box to Show Renovations Only\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"a.estid\" SELECTED>Estimate #</option>\n";
	echo "                                 		<option value=\"a.added\">Insert Date</option>\n";
	echo "                                 		<option value=\"b.clname\">Last Name</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                 		<option value=\"DESC\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "         								</form>\n";
	echo "										</tr>\n";
	//}

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

function est_searchOLD()
{
	$acclist=explode(",",$_SESSION['aid']);
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,1) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
	
	/*echo "<pre>";
	print_r($acclist);
	echo "</pre>";*/

	echo "<table width=\"50%\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\"><b>Estimate Search Tool</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                 <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Data Field</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Input Parameter</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Renov Only</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Sort</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Order</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b></b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	//echo "											<input type=\"hidden\" name=\"subq\" value=\"estnum\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\">\n";
	echo "												<select name=\"subq\">\n";
	echo "                                 		<option value=\"last_name\">Customer Last Name</option>\n";
	//echo "                                 		<option value=\"enum\">Estimate #</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" name=\"sval\" size=\"20\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo "                              	<td align=\"center\" valign=\"bottom\"><input class=\"transnb\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this Box to Show Renovations Only\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"a.estid\" SELECTED>Estimate #</option>\n";
	echo "                                 		<option value=\"a.added\">Insert Date</option>\n";
	echo "                                 		<option value=\"b.clname\">Last Name</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                 		<option value=\"DESC\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "         								</form>\n";
	echo "										</tr>\n";

	//if ($_SESSION['llev'] >= 5)
	//{
	echo "										<tr>\n";
	echo "         								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Sales Rep</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"assigned\">\n";

	while ($row1 = mssql_fetch_array($res1))
	{
		if (in_array($row1['securityid'],$acclist) || $_SESSION['elev'] >= 9)
		{
			$secl=explode(",",$row1['slevel']);
			
			if ($secl[6]==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}

			echo "                                    	<option value=\"".$row1['securityid']."\" class=\"".$ostyle."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
		}
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	//echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                              	<td align=\"center\" valign=\"bottom\"><input class=\"transnb\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this Box to Show Renovations Only\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"a.estid\" SELECTED>Estimate #</option>\n";
	echo "                                 		<option value=\"a.added\">Insert Date</option>\n";
	echo "                                 		<option value=\"b.clname\">Last Name</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                 		<option value=\"DESC\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "         								</form>\n";
	echo "										</tr>\n";
	//}

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

function cform_view()
{
	$acclist=explode(",",$_SESSION['aid']);

	//print_r($_POST);

	if (empty($_REQUEST['uid']))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error occured.\n";
		exit;
	}

	if (isset($_REQUEST['subq']) && $_REQUEST['subq']=="custid")
	{
		$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['custid']."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);

		$cid=$row0['cid'];
	}
	else
	{
		$cid=$_REQUEST['cid'];
	}

	if (empty($cid))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> You must provide a Valid Lead ID number.\n";
		exit;
	}

	$dates	=dateformat();

	if ($_SESSION['llev'] >= 5)
	{
		$qryA = "SELECT officeid,name,stax FROM offices WHERE active=1 ORDER BY name ASC;";
	}
	else
	{
		$qryA = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	}
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,sidm,slevel,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
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

	$qryF = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$cid."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	//echo $qryF;

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER by name ASC;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER by name ASC;";
	$resH = mssql_query($qryH);

	$qryI = "SELECT securityid,fname,lname,sidm FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowF['securityid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryJ = "SELECT securityid,sidm,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowF['sidm']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);

	$qryK = "SELECT MIN(appt_yr) as minyr FROM cinfo WHERE officeid='".$_SESSION['officeid']."';";
	$resK = mssql_query($qryK);
	$rowK = mssql_fetch_array($resK);

	$adate = date("m-d-Y (g:i A)", strtotime($rowF['added']));

	//$curryr=$rowK['minyr'];
	//$futyr =$rowK['minyr']+2;

	$curryr=date("Y");
	$futyr =$curryr+2;

	//echo "CYR: ".$curryr."<br>";
	//echo "FYR: ".$futyr;

	if ($_REQUEST['uid']=="XXX")
	{
		$dis="DISABLED";
	}
	else
	{
		$dis="";
	}

	if (!in_array($rowI['securityid'],$acclist))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font><br>You do not have appropriate Access to view this Information.\n";
		exit;
	}

	echo "<table width=\"85%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "		<table class=\"outer\" width=\"100%\" align=\"center\" border=0>\n";
	echo "   	<tr>\n";
	echo "      <td>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"edit\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
	echo "         <table border=\"0\" width=\"100%\">\n";
	echo "         	<tr>\n";
	echo "            	<td bgcolor=\"#d3d3d3\">\n";
	echo "               	<table border=\"0\" width=\"100%\">\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"left\">\n";
	echo "               				<table border=\"0\" width=\"100%\">\n";
	echo "                     			<tr>\n";
	echo "                        			<td align=\"left\"><b>Customer Detailed Information:</font></b></td>\n";
	echo "                                 <td align=\"right\">\n";
	echo "								         </td>\n";
	echo "                                 <td valign=\"bottom\" align=\"right\">&nbsp\n";
	echo "                                    <input type=\"hidden\" name=\"dupe\" value=\"".$rowF['dupe']."\">\n";
	echo "											</td>\n";
	echo "                    				</tr>\n";
	echo "                    			</table>\n";
	echo "								</td>\n";
	echo "                    	</tr>\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"right\" valign=\"bottom\">\n";
	echo "                           <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                           	<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Date:</b>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">".$adate."</td>\n";
	echo "                                 <td align=\"right\" valign=\"bottom\"><b>Office: </b></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";

	if ($_SESSION['llev'] >= 6)
	{
		echo "                                 	<select name=\"site\">\n";
		while ($rowA = mssql_fetch_row($resA))
		{
			if ($_SESSION['officeid']==$rowA[0])
			{
				echo "                                 		<option value=\"".$rowA[0]."\" SELECTED>".$rowA[1]."</option>\n";
			}
			else
			{
				echo "                                 		<option value=\"".$rowA[0]."\">".$rowA[1]."</option>\n";
			}
		}
		echo "                                 	</select>\n";
	}
	elseif ($_SESSION['llev'] == 5)
	{
		echo "                                 	<select name=\"site\">\n";
		while ($rowA = mssql_fetch_row($resA))
		{
			if ($_SESSION['officeid']==$rowA[0])
			{
				echo "                                 		<option value=\"".$rowA[0]."\" SELECTED>".$rowA[1]."</option>\n";
			}
			elseif ($rowA[0]==89)
			{
				echo "                                 		<option value=\"".$rowA[0]."\">".$rowA[1]."</option>\n";
			}
		}
		echo "                                 	</select>\n";
	}
	else
	{
		$rowA = mssql_fetch_row($resA);
		echo "                                 	".$rowA[1]."<input type=\"hidden\" name=\"site\" value=\"".$rowA[0]."\">\n";
	}

	echo "                                 </td>\n";
	echo "                                 <td align=\"right\" valign=\"bottom\"><b>SalesRep:</b>\n";

	if ($_SESSION['llev'] == 4) // Sales Manager List
	{
		echo "                                 	<select name=\"estorig\">\n";

		while ($rowB = mssql_fetch_row($resB))
		{
			if (in_array($rowB[0],$acclist))
			//if ($rowB[3]==$_SESSION['securityid']||$rowB[0]==$_SESSION['securityid']||$rowJ[2]==$_SESSION['securityid'])
			{
				$slev=explode(",",$rowB[4]);
				if ($slev[4]!=0)
				{
					if ($rowF['securityid']==$rowB[0])
					{
						echo "                                 	<option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
					}
					else
					{
						echo "                                 	<option value=\"".$rowB[0]."\">".$rowB[1]." ".$rowB[2]."</option>\n";
					}
				}
			}
		}
		echo "                                 	</select>\n";

	}
	elseif ($_SESSION['llev'] >= 5) // General Manager List
	{
		echo "                                 	<select name=\"estorig\">\n";

		while ($rowB = mssql_fetch_row($resB))
		{
			$slev=explode(",",$rowB[4]);
			if ($slev[4]!=0)
			{
				if ($rowF['securityid']==$rowB[0])
				{
					echo "                                 	<option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
				}
				else
				{
					echo "                                 	<option value=\"".$rowB[0]."\">".$rowB[1]." ".$rowB[2]."</option>\n";
				}
			}
		}

		echo "                                 	</select>\n";

	}
	else
	{
		//echo "                                 ".$_SESSION['fname']." ".$_SESSION['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$_SESSION['securityid']."\">\n";
		echo "                                 ".$rowI['fname']." ".$rowI['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
	}

	//echo $rowI['securityid'];
	echo "                                 </td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "										<tr>\n";
	echo "											<td colspan=\"2\" valign=\"bottom\" NOWRAP><b>Customer:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>First Name</td>\n";
	echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"cfname\" value=\"".$rowF['cfname']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Last Name</td>\n";
	echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"clname\" value=\"".$rowF['clname']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Home Phone</td>\n";
	echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\" value=\"".$rowF['chome']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Work Phone</td>\n";
	echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\" value=\"".$rowF['cwork']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Cell Phone</td>\n";
	echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\" value=\"".$rowF['ccell']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Fax</td>\n";
	echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" value=\"".$rowF['cfax']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Best Phone</td>\n";
	echo "											<td align=\"left\" NOWRAP>\n";
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
	echo "											<td align=\"right\" NOWRAP>Email</td>\n";
	echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" name=\"cemail\" size=\"30\" value=\"".$rowF['cemail']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Contact Time</td>\n";
	echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"ccontime\" value=\"".$rowF['ccontime']."\"></td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "										<tr>\n";
	echo "											<td colspan=\"2\" valign=\"top\" NOWRAP><b>Current Address:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Street</td>\n";
	echo "											<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"caddr1\" value=\"".$rowF['caddr1']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>City</td>\n";
	echo "											<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"ccity\" value=\"".$rowF['ccity']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" value=\"".$rowF['cstate']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Zip</td>\n";
	echo "											<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".$rowF['czip1']."\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\" value=\"".$rowF['czip2']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\" NOWRAP>Cnty/Twnshp</td>\n";
	echo "											<td NOWRAP>\n";

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
	echo "											<tr>\n";

	if ($rowF['ssame']==1)
	{
		echo "												<td colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
	}
	else
	{
		echo "												<td colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\"> Same as above</td>\n";
	}

	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\" NOWRAP>Street:</td>\n";
	echo "												<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\" value=\"".$rowF['saddr1']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\" NOWRAP>City:</td>\n";
	echo "												<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\" NOWRAP>Zip:</td>\n";
	echo "												<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\" NOWRAP>Cnty/Twnshp:</td>\n";
	echo "												<td NOWRAP>\n";

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
	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"170\">\n";
	echo "										<tr>\n";
	echo "											<td align=\"left\" valign=\"top\"><b>Appointment/Source/Result:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"center\" valign=\"top\">\n";
	echo "												<table>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\" valign=\"bottom\"><b>Date</b></td>\n";
	echo "														<td valign=\"top\">\n";
	echo "                                             <select name=\"appt_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		if ($rowF['appt_mo']==$mo)
		{
			echo "																<option value=\"".$mo."\" SELECTED>".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		if ($rowF['appt_da']==$da)
		{
			echo "																<option value=\"".$da."\" SELECTED>".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_yr\">\n";
	echo "																<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['appt_yr'])
		{
			echo "																<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$yr."\">".$yr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\" valign=\"bottom\"><b>Time</b></td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_hr\">\n";

	for ($hr=0; $hr<=12; $hr++)
	{
		if ($rowF['appt_hr']==$hr)
		{
			echo "																<option value=\"".$hr."\" SELECTED>".$hr."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$hr."\">".$hr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_mn\">\n";

	for ($mn=0; $mn<=60; $mn++)
	{
		if ($rowF['appt_mn']==$mn)
		{
			echo "																<option value=\"".$mn."\" SELECTED>".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mn."\">".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_pa\">\n";

	if ($rowF['appt_pa']==1)
	{
		echo "																<option value=\"1\" SELECTED>AM</option>\n";
		echo "																<option value=\"2\">PM</option>\n";
	}
	else
	{
		echo "																<option value=\"1\">AM</option>\n";
		echo "																<option value=\"2\" SELECTED>PM</option>\n";
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\" valign=\"bottom\"><b>Lead Source</b></td>\n";

	if ($rowF['source']==0)
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">Internet</td>\n";
		echo "         											<input type=\"hidden\" name=\"source\" value=\"0\">\n";
	}
	elseif ($rowF['source'] >= 1)
	{
		//echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">Manual</td>\n";
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";
		echo "                                             <select name=\"source\">\n";

		while ($rowH = mssql_fetch_array($resH))
		{
			if ($_SESSION['llev'] >= $rowH['access'])
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
		}

		echo "                                             </select>\n";
		echo "														</td>\n";
	}

	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\" valign=\"bottom\"><b>Lead Result</b></td>\n";
	echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"stage\">\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		//if ($_SESSION['llev'] >= $rowG['access'])
		//{
		if ($rowG['statusid']==$rowF['stage'])
		{
			echo "                                             <option value=\"".$rowG['statusid']."\" SELECTED>".$rowG['name']."</option>\n";
		}
		else
		{
			echo "                                             <option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
		}
		//}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "                                 </tr>\n";

	// Call Back Selects
	echo "                                 <tr>\n";
	echo "                        			<td valign=\"bottom\" align=\"right\"><b>Call Back</b></td>\n";
	echo "                        			<td valign=\"bottom\" align=\"left\">\n";

	if ($rowF['hold']==1)
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" value=\"1\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "                        			<td valign=\"bottom\" align=\"right\"><b>on</b></td>\n";
	echo "														<td valign=\"top\">\n";
	echo "                                             <select name=\"hold_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		if ($rowF['hold_mo']==$mo)
		{
			echo "																<option value=\"".$mo."\" SELECTED>".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		if ($rowF['hold_da']==$da)
		{
			echo "																<option value=\"".$da."\" SELECTED>".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_yr\">\n";
	echo "																<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['hold_yr'])
		{
			echo "																<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$yr."\">".$yr."</option>\n";
		}
	}

	/*
	if ($rowF['hold_yr']==date("Y"))
	{
	echo "																<option value=\"".date("Y")."\" SELECTED>".date("Y")."</option>\n";
	}
	else
	{
	echo "																<option value=\"".date("Y")."\">".date("Y")."</option>\n";
	}
	*/
	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "												</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td align=\"right\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"170\">\n";
	echo "										<tr>\n";
	echo "											<td valign=\"top\"><b>Comments/Directions:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td valign=\"top\" align=\"center\"><textarea name=\"comments\" cols=\"60\" rows=\"10\">".$rowF['comments']."</textarea></td>\n";
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
		echo "											<td valign=\"top\"><b>Marketing Data:</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr valign=\"top\">\n";
		echo "											<td><textarea name=\"mrkproc\" cols=\"90\" rows=\"25\" DISABLED>".$rowF['mrktproc']."</textarea></td>\n";
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
	echo "				   <td class=\"gray\">\n";


	if (!empty($_REQUEST['subq']) && $_REQUEST['subq']=="history")
	{
		$qryZ = "SELECT * FROM leadhistory WHERE cinfo_id='".$_REQUEST['cid']."' ORDER BY udate DESC;";
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
					echo "         <td class=\"wh_und\" align=\"left\">Internet</td>\n";
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
	echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update Lead\" ".$dis.">\n";
	echo "				</td>\n";
	echo "			</form>\n";
	echo "			</tr>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"view\">\n";
	echo "         <input type=\"hidden\" name=\"subq\" value=\"history\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['llev'] >= 4 && $_REQUEST['uid']!="XXX")
	{
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"History\"><br>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"matrix0\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['custid']."\">\n";
	echo "         <input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";
	echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['jlev'] >= 1)
	{
		if ($rowF['hold']==0 && $rowF['dupe']==0 && $rowF['estid']==0)
		{
			echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Estimate\"><br>\n";
			//echo $rowF['hold']."<br>";
			//echo $rowF['dupe']."<br>";
		}
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";

}

function delete_est()
{
	if (!isset($_REQUEST['estid']) || $_REQUEST['estid']==0)
	{
		die('Estimate ID not set!');
	}
	
	if ($_REQUEST['call']=="delete_est1")
	{
		$qryA = "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		//$qryA = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rowA['ccid']."';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);

		$acclist=explode(",",$_SESSION['aid']);
		if ($_SESSION['tlev'] < 9 && !in_array($rowA['securityid'],$acclist))
		{
			echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Resource</b>";
			exit;
		}

		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"delete_est2\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowA['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowA['sidm']."\">\n";
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$rowA['estid']."\">\n";
		echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowA['cid']."\">\n";
		echo "<input type=\"hidden\" name=\"cid\" value=\"".$rowB['cid']."\">\n";
		echo "<input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";

		echo "<table class=\"outer\" align=\"center\" width=\"300px\" border=0>\n";
		echo "   <tr>\n";
		echo "      <th class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"left\">Confirm Estimate Delete</th>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Estimate Id:</b></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
		echo "         <input class=\"bboxl\" type=\"text\" name=\"estid\" value=\"".$rowA['estid']."\" size=\"25\" DISABLED>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Customer:</b></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
		echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowB['clname'].", ".$rowB['cfname']."\" size=\"25\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\">\n";
		echo "         <button type=\"submit\">Delete Estimate</button>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
	elseif ($_REQUEST['call']=="delete_est2")
	{
		$qry	= "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		//$qry	= "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);
		$nrow	= mssql_num_rows($res);

		$qryA = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$row['cid']."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		if ($nrow > 0)
		{
			$uid  =md5(session_id().time().$rowA['cid']).".".$_SESSION['securityid'];
			$qryB	= "exec dbo.sp_deleteestimate @officeid='".$_SESSION['officeid']."',@custid='".$row['cid']."',@cid='".$rowA['cid']."',@estid='".$row['estid']."',@securityid='".$_SESSION['securityid']."',@tranid='".$uid."';";
			$resB	= mssql_query($qryB);

			echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "<input type=\"hidden\" name=\"cid\" value=\"".$rowA['cid']."\">\n";
			echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			echo "<table class=\"outer\" align=\"center\" width=\"300px\" border=0>\n";
			echo "   <tr>\n";
			echo "      <th class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"left\">Estimate Deleted!</th>\n";
			echo "   </tr>\n";
			echo "   <tr>\n";
			echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Customer:</b></td>\n";
			echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
			echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowA['clname'].", ".$rowA['cfname']."\" size=\"25\">\n";
			echo "      </td>\n";
			echo "   </tr>\n";
			echo "   <tr>\n";
			echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"center\">Has been reverted to Lead Status.</td>\n";
			echo "   </tr>\n";
			echo "   <tr>\n";
			echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\">\n";
			echo "         <button type=\"submit\">View Lead</button>\n";
			echo "      </td>\n";
			echo "   </tr>\n";
			echo "</table>\n";
			echo "</form>\n";
		}
		else
		{
			echo "Error Occured!";
		}
	}
}

function base_inclusion()
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;

	$qry = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND qtype BETWEEN '48' AND '52';";
	$res = mssql_query($qry);

	while ($row = mssql_fetch_array($res))
	{
		$amt=form_element_calc_ACC($row['id'],$row['quan_calc'],0,0);
		$qryA = "SELECT abrv FROM mtypes WHERE mid='".$row['mtype']."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\">Base</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\">".$row['item']."</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$amt[2]."</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$rowA['abrv']."</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">Incl.</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"center\"></td>\n";
		echo "           </tr>\n";
	}
}

function update_contract_amt($estid)
{
	//error_reporting(E_ALL);

	if (preg_match('/^[0-9]+\.[0-9]{2}/i',trim($_REQUEST['c_amt'])))
	{
		$qry = "UPDATE est SET contractamt='".trim($_REQUEST['c_amt'])."',updateby='".$_SESSION['securityid']."',updated=GETDATE() WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
		$res = mssql_query($qry);
		
		//echo $qry."<br>";
	
		viewest_retail($estid,0);
	}
	elseif (preg_match('/-/i',trim($_REQUEST['c_amt'])))
	{
		echo "<b>Contract Amount cannot be negative!</b>";
		exit;
	}
	else
	{
		echo "<b>Contract Amount not properly formatted! Must be numerical and in the following format: 00000.00</b>";
		exit;
	}
}

function addadj_init($estid)
{
	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"adjins\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "<table class=\"outer\" align=\"center\" width=\"700px\" border=0>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\">\n";
	echo "         <table align=\"center\">\n";
	echo "            <tr>\n";
	echo "               <th colspan=\"2\" align=\"left\">Add Retail Adjustment for Estimate ID: ".$estid."</th>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" valign=\"top\" align=\"right\"><b>Description:</b></td>\n";
	echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><textarea name=\"descrip\" rows=\"5\" cols=\"50\"></textarea></td>\n";
	echo "            <tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><b>Discount Amount:</b></td>\n";
	echo "               <td class=\"gray\" valign=\"top\" align=\"right\">\n";
	echo "                  <input class=\"bbox\" type=\"text\" name=\"adjamt\" value=\"0.00\">\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "               <td class=\"gray\" colspan=\"2\" valign=\"top\" align=\"right\">\n";
	echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Add Discount\">\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      <td>\n";
	echo "   <tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function addadj_ins($estid)
{
	/*if ($_REQUEST['adjamt'] >= 0)
	{
		echo "<font color=\"red\"><b>Error!</b></font><br><font color=\"black\">Discounts must be negative. Click Back and Adjust the Amount.</font>";
		exit;
	}*/
	
	if (!isset($_REQUEST['adjamt']) || $_REQUEST['adjamt']=="0.00" || $_REQUEST['adjamt'] < 1)
	{
		echo "<font color=\"red\"><b>Error!</b></font><br><font color=\"black\">Adjustments must contain a Description and Amount. Click Back and Enter the appropriate Info.</font>";
		exit;
		/*echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"adjins\">\n";
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "<table class=\"outer\" align=\"center\" width=\"700px\" border=0>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\">\n";
		echo "         <table align=\"center\">\n";
		echo "            <tr>\n";
		echo "               <th colspan=\"2\" align=\"left\">Add Retail Adjustment for Estimate ID: ".$estid."</th>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td></td>\n";
		echo "               <td align=\"left\"><font color=\"red\">Amount must +/- 0.00</font></td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><b>Discount Amount:</b></td>\n";
		echo "               <td class=\"gray\" valign=\"top\" align=\"left\">\n";
		echo "                  <input class=\"bboxl\" type=\"text\" name=\"adjamt\" value=\"0.00\">\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" valign=\"top\" align=\"right\"><b>Description:</b></td>\n";
		echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><textarea name=\"descrip\" rows=\"5\" cols=\"50\">".$_REQUEST['descrip']."</textarea></td>\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" colspan=\"2\" valign=\"top\" align=\"right\">\n";
		echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Add Adjust\">\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      <td>\n";
		echo "   <tr>\n";
		echo "</table>\n";
		echo "</form>\n";*/
	}
	else
	{
		$qryA  = "INSERT INTO est_discounts ";
		$qryA .= "(estid,officeid,descrip,discount) ";
		$qryA .= "VALUES ";
		$qryA .= "('".$_SESSION['estid']."','".$_SESSION['officeid']."','".$_REQUEST['descrip']."','".$_REQUEST['adjamt']."');";
		$resA  = mssql_query($qryA);

		$qryB = "UPDATE est SET updateby='".$_SESSION['securityid']."',updated=GETDATE() WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
		$resB = mssql_query($qryB);

		viewest_retail($_SESSION['estid'],0);
	}
}

function update_acc()
{
	/*echo "<pre>";
	print_r($_REQUEST);
	echo "<br>";
	echo "</pre>";*/
	
	$i=0;
	$a=0;
	$b=0;
	$qryA  = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$resA  = mssql_query($qryA);
	$rowA  = mssql_fetch_array($resA);

	// Process Deletes
	foreach ($_REQUEST as $n=>$v)
	{
		if (substr($n,0,3)=="xxx")
		{
			$idata=substr($n,3);
			$postarray[]=$idata;
			$i++;
		}
		elseif (substr($n,0,3)=="aaa")
		{
			$adata=substr($n,3);
			$apostarray[]=$adata;
			$a++;
		}
		elseif (substr($n,0,3)=="bbb")
		{
			$bdata=substr($n,3);
			$bpostarray[]=$bdata;
			$b++;
		}
	}

	if ($i > 0)
	{
		foreach ($postarray as $n=>$v)
		{
			$dbarray=explode(",",$rowA[0]);
			foreach ($dbarray as $n1 => $v1)
			{
				$itemdata=explode(":",$v1);
				if ($itemdata[0]==$v)
				{
					// Removes Bid Items from est_bids
					$qryB  = "DELETE FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$v."';";
					$resB  = mssql_query($qryB);

					$diffarray[]=$v1;
				}
			}
		}

		$rarray=array_diff($dbarray,$diffarray);
		$racnt=count($rarray);
		$outdata="";

		foreach ($rarray as $n => $v)
		{
			if (!isset($outdata))
			{
				$outdata="";
			}

			if ($racnt!=1)
			{
				$outdata=$outdata.$v.",";
			}
			else
			{
				$outdata=$outdata.$v;
			}
			$racnt--;
		}

		$qryB  = "UPDATE est_acc_ext SET estdata='".$outdata."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
		$resB = mssql_query($qryB);
	}

	if ($a > 0)
	{
		foreach ($apostarray AS $na => $va)
		{
			$qryC  = "DELETE FROM est_discounts WHERE id='".$va."' AND officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
			$resC = mssql_query($qryC);
		}
	}

	if ($b > 0)
	{
		foreach ($bpostarray AS $nb => $vb)
		{
			$qryD  = "DELETE FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$vb."';";
			$resD = mssql_query($qryD);
		}
	}
	
	// Process Adjusts
	proc_price_adjusts();

	$qryE = "UPDATE est SET updateby='".$_SESSION['securityid']."',updated=GETDATE() WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$resE = mssql_query($qryE);

	viewest_retail($_SESSION['estid'],0);
}

function setretailitemlist($data)
{
	$celldelim=",";
	$contdelim=":";
	$data1=explode($celldelim,$data);
	foreach ($data1 as $n1=>$v1)
	{
		$itemar[]=array(0=>0);
	}
	return $itemar;
}

function setcostitemlist($data,$searchval)
{
	$MAS=$_SESSION['pb_code'];
	//echo $data;
	if ($searchval=="L")
	{
		$tb="[".$MAS."rclinks_l]";
	}
	elseif ($searchval=="M")
	{
		$tb="[".$MAS."rclinks_m]";
	}
	//This function takes a multidimension Array ($data) with cell/content delimiters and returns a match based
	$celldelim=",";
	$contdelim=":";
	$data1=explode($celldelim,$data);
	foreach ($data1 as $n1=>$v1)
	{
		$v1array=explode($contdelim,$v1);

		//echo "<pre>";
		//print_r($v1array);
		//echo "</pre>";

		$qry  = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$v1array[0]."';";
		$res  = mssql_query($qry);
		$row  = mssql_fetch_row($res);

		$qryA  = "SELECT cid FROM ".$tb." WHERE officeid='".$_SESSION['officeid']."' AND rid='".$v1array[0]."';";
		$resA  = mssql_query($qryA);
		$nrowA  = mssql_num_rows($resA);

		if ($nrowA > 0)
		{
			while ($rowA  = mssql_fetch_row($resA))
			{
				// breakout (0=Cost Item ID,1=Quantity,2=,3=Retail Item ID)
				$itemar[]=array(0=>$rowA[0],1=>$v1array[2],2=>$v1array[4],3=>$v1array[0]);
			}
		}

		if ($row[1]==55||$row[1]==72)
		{
			$qry0  = "SELECT iid,rid,adjamt,adjquan,adjtype FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$v1array[0]."';";
			$res0  = mssql_query($qry0);
			$nrow0  = mssql_num_rows($res0);

			if ($nrow0 > 0)
			{
				while ($row0  = mssql_fetch_array($res0))
				{
					$qryB  = "SELECT cid FROM ".$tb." WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row0['iid']."';";
					$resB  = mssql_query($qryB);
					$nrowB  = mssql_num_rows($resB);

					// Quantity Adjusts
					if ($row0['adjquan']!=0)
					{
						$quan=$row0['adjquan'];
						//echo $quan." ADJ<br>";
					}
					else
					{
						$quan=$v1array[2];
						//echo $quan." NONADJ<br>";
					}

					if ($nrowB > 0)
					{
						while ($rowB  = mssql_fetch_row($resB))
						{
							$itemar[]=array(0=>$rowB[0],1=>$quan,2=>$v1array[4],3=>$v1array[0]);
						}
					}
				}
			}
		}
	}

	if (empty($itemar[0]))
	{
		$itemar=array(0=>0);
	}
	//echo "<pre>";
	//print_r($itemar);
	//echo "</pre>";
	return $itemar;
}

function autoadditems()
{
	if (isset($_REQUEST['esttype']) && $_REQUEST['esttype']=='Q')
	{
		$qryB = "SELECT id,catid,name FROM AC_Cats WHERE officeid='".$_SESSION['officeid']."' and active=1 and privcat=1;";
		$resB = mssql_query($qryB);
		$nrowB= mssql_num_rows($resB);
		
		if ($nrowB > 0)
		{
			$estout='';
			while($rowB = mssql_fetch_array($resB))
			{
				$qryC  = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc, ";
				$qryC .= "commtype,crate,disabled FROM [".$_SESSION['pb_code']."acc] WHERE officeid='".$_SESSION['officeid']."' ";
				$qryC .= "AND catid=".$rowB['catid']." AND phsid=".$_SESSION['securityid']." AND atrib2='1' AND disabled=0 ORDER BY seqn;";
				$resC = mssql_query($qryC);
				$nrowC= mssql_num_rows($resC);
				
				if ($nrowC > 0)
				{
					$d_ar[$rowB['catid']]=   array(
											0=>	$rowB['catid'],
											1=>	$rowB['name']
											);
						
					while($rowC = mssql_fetch_array($resC))
					{
						$d_ar[$rowB['catid']][2][]=   array(
														'id'=>		$rowC['id'],
														'aid'=>		$rowC['aid'],
														'officeid'=>$rowC['officeid'],
														'item'=>	$rowC['item'],
														'accpbook'=>$rowC['accpbook'],
														'qtype'=>	$rowC['qtype'],
														'seqn'=>	$rowC['seqn'],
														'rp'=>		$rowC['rp'],
														'bp'=>		$rowC['bp'],
														'spaitem'=>	$rowC['spaitem'],
														'mtype'=>	$rowC['mtype'],
														'atrib1'=>	$rowC['atrib1'],
														'atrib2'=>	$rowC['atrib2'],
														'atrib3'=>	$rowC['atrib3'],
														'quan_calc'=>$rowC['quan_calc'],
														'commtype'=>$rowC['commtype'],
														'crate'=>$rowC['crate'],
														'disabled'=>$rowC['disabled']
													);
					}
				}
			}
			
			/*echo "<pre>";
			print_r($d_ar);
			echo "</pre>";*/
			
			foreach ($d_ar as $n1 => $v1)
			{
				$icount=count($v1[2]);
				foreach ($v1[2] as $n2 => $v2)
				{
					/*echo '<pre>';
					echo print_r($v2);
					echo '</pre>';*/
					//$v3=explode(":",$v2);
					if ($icount==1)
					{
						$estd=$v2['id'].'::1:'.$v2['rp'].'::'.$v2['commtype'].':'.$v2['crate'].':'.$v2['quan_calc'];
					}
					else
					{
						$estd=$v2['id'].'::1:'.$v2['rp'].'::'.$v2['commtype'].':'.$v2['crate'].':'.$v2['quan_calc'].',';
					}
					//echo $estd."<br>";
					$estout=$estout.$estd;
					$icount--;
				}
			}
			
			return $estout;
		}
	}
}

function saveest()
{
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	global $viewarray,$tchrg,$estid;
	//$finanset=0;

	if (empty($_REQUEST['uid']))
	{
		echo "<b>Transition Error Occured!</b>";
		exit;
	}

	if ($_REQUEST['ps1']==0||$_REQUEST['ps2']==0||$_REQUEST['ps5']==0||$_REQUEST['ps6']==0||$_REQUEST['ps7']==0||$_REQUEST['erun']==0||$_REQUEST['prun']==0)
	{
		echo "<h4><font color=\"red\">Error -  Data missing or incorrect format:</font><br></h4>\n";

		if ($_REQUEST['ps1']==0)
		{
			echo "Perimeter<br>";
		}

		if ($_REQUEST['ps2']==0)
		{
			echo "Surface Area<br>";
		}

		if ($_REQUEST['ps5']==0)
		{
			echo "Shallow Measurement<br>";
		}

		if ($_REQUEST['ps6']==0)
		{
			echo "Middle Measurement<br>";
		}

		if ($_REQUEST['ps7']==0)
		{
			echo "Deep Measurement<br>";
		}

		if ($_REQUEST['erun']==0)
		{
			echo "Electrical Run<br>";
		}

		if ($_REQUEST['prun']==0)
		{
			echo "Plumbing Run<br>";
		}

		echo "Click the BACK button and correct.<br>";

		exit;
	}
	
	$qry  = "SELECT estid FROM est WHERE officeid='".$_SESSION['officeid']."' AND unique_id='".$_REQUEST['uid']."'; ";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	$nrow = mssql_num_rows($res);

	//echo "ROW: ".$nrow;

	if (!isset($_REQUEST['phone']))
	{
		$phone="";
	}
	else
	{
		$phone=$_REQUEST['phone'];
	}
	if (!isset($_REQUEST['refto']))
	{
		$refto="";
	}
	else
	{
		$refto=$_REQUEST['refto'];
	}
	
	if (!isset($_REQUEST['renov']))
	{
		$renov=0;
	}
	else
	{
		$renov=$_REQUEST['renov'];
	}
	
	if ($nrow==0)
	{
		if ($_REQUEST['esttype']=='Q')
		{
			$estAdata =estAdata_quote();
		}
		else
		{
			$estAdata =estAdata_init();
		}
		
		//exit;
		
		$qryA   = "exec sp_insertquote ";
		$qryA  .= "@officeid='".$_SESSION['officeid']."', ";
		$qryA  .= "@securityid='".$_REQUEST['securityid']."', ";
		$qryA  .= "@sidm='".$_REQUEST['sidm']."', ";
		$qryA  .= "@status='0', ";
		$qryA  .= "@pft='".replacequote($_REQUEST['ps1'])."', ";
		$qryA  .= "@sqft='".replacequote($_REQUEST['ps2'])."', ";
		$qryA  .= "@apft='0', ";
		$qryA  .= "@shal='".replacequote($_REQUEST['ps5'])."', ";
		$qryA  .= "@mid='".replacequote($_REQUEST['ps6'])."', ";
		$qryA  .= "@deep='".replacequote($_REQUEST['ps7'])."', ";
		$qryA  .= "@deck='".replacequote($_REQUEST['deck'])."', ";
		$qryA  .= "@spa_pft='".replacequote($_REQUEST['spa2'])."', ";
		$qryA  .= "@spa_sqft='".replacequote($_REQUEST['spa3'])."', ";
		$qryA  .= "@spatype='".replacequote($_REQUEST['spa1'])."', ";
		$qryA  .= "@tzone='".replacequote($_REQUEST['tzone'])."', ";
		$qryA  .= "@erun='".replacequote($_REQUEST['erun'])."', ";
		$qryA  .= "@prun='".replacequote($_REQUEST['prun'])."', ";
		$qryA  .= "@renov='".$renov."', ";
		$qryA  .= "@btchrg='".$tchrg[0]."', ";
		$qryA  .= "@rtchrg='".$tchrg[1]."', ";
		$qryA  .= "@contractamt='0.00', ";
		$qryA  .= "@refto='".replacequote($refto)."', ";
		$qryA  .= "@est_cost='0', ";
		$qryA  .= "@cid='".$_REQUEST['cid']."', ";
		$qryA  .= "@unique_id='".$_REQUEST['uid']."', ";
		$qryA  .= "@esttype='".$_REQUEST['esttype']."', ";
		$qryA  .= "@estAdata='".$estAdata."';";
		$resA   = mssql_query($qryA);
		$rowA   = mssql_fetch_row($resA);
		
		//echo $qryA.'<br>';
		//exit;

		$_SESSION['estid']=$rowA[0];

		//$qryAb  = "UPDATE cinfo SET estid='".$_SESSION['estid']."' WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['cid']."';";
		//$resAb  = mssql_query($qryAb);
		//$rowAb  = mssql_fetch_row($resAb);

		//Writing Bid Items
		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,4)=="bbba")
			{
				$asid=substr($n,4);
				if ($_REQUEST['bbba'.$asid] > 0)
				{
					if (array_key_exists("eeea".$asid,$_POST))
					{
						$qryB  = "INSERT INTO est_bids (officeid,estid,bidinfo,bidaccid) VALUES ('".$_SESSION['officeid']."','".$_SESSION['estid']."','".replacequote($_REQUEST['eeea'.$asid])."','$asid');";
						$resB  = mssql_query($qryB);
					}
				}
			}
		}

		viewest_retail();
	}
	else
	{
		$_SESSION['estid']=$row['estid'];
		viewest_retail();
		//echo "<b>This estimate has already been submitted. Please create a New Lead from the Lead Menu or select a Lead from your Lead List.</b><br>";
		//exit;
	}
}

/*
function add_finan_cust($oid,$orig_oid,$cid,$sid,$uid)
{
	//echo "Adding WinFin<br>";
	error_reporting(E_ALL);
	$nsecid	=0;
	$qry  	= "SELECT cid FROM cinfo WHERE officeid='".$orig_oid."' AND cid='".$cid."';";
	$res  	= mssql_query($qry);
	$row  	= mssql_fetch_array($res);
	$nrow 	= mssql_num_rows($res);
	
	//echo $qry."<br>";
	
	if ($nrow==1)
	{
		$qry0  	= "SELECT name,gm,am FROM offices WHERE officeid='".$oid."';";
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

		$qry1   = "UPDATE cinfo SET finan_from='".$oid."',finan_sec='".$nsecid."',finan_src='".$_REQUEST['finan']."',finan_date=getdate() WHERE officeid=".$orig_oid." AND cid=".$cid.";";
		$res1   = mssql_query($qry1);
		//echo $qry1."<br>";

		$qry2   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
		$qry2  .= "VALUES ";
		$qry2  .= "('".$cid."','".$orig_oid."','".$_SESSION['securityid']."','est','".$ctext."','".$uid."')";
		$res2  = mssql_query($qry2);
	}
}
*/

function proc_price_adjusts()
{
	error_reporting(E_ALL);
	$MAS	= $_SESSION['pb_code'];
	//$estid	= $_SESSION['estid'];
	
	if (isset($_REQUEST['base_pl_src']) || isset($_REQUEST['acc_pb_src']))
	{
		$qryA  = "SELECT estid,securityid,esttype FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
		$resA  = mssql_query($qryA);
		$rowA  = mssql_fetch_array($resA);
	}
	
	//echo "TEST<BR>";
	//Base Price Adjust
	if (isset($_REQUEST['base_pl_src']))
	{
		if ($rowA['esttype']=='E') // Updates Estimates
		{
			$qry0  = "select * from [jest].[dbo].[base_price_adjusts] where oid=".$_SESSION['officeid']." and estid=".$_SESSION['estid'].";";
			$res0  = mssql_query($qry0);
			$row0  = mssql_fetch_array($res0);
			$nrow0 = mssql_num_rows($res0);
			
			if ($nrow0 > 0)
			{
				if (number_format($_REQUEST['base_pl_src'][1], 2, '.', '')!=number_format($row0['var_price'], 2, '.', ''))
				{
					echo "TEST RETAIL ADJ 1<BR>";
					$adj_price=($_REQUEST['base_pl_src'][0]+$_REQUEST['base_pl_src'][1]);
					$qry0a   = "update [jest].[dbo].[base_price_adjusts] set ";
					$qry0a  .= "pft=0,sqft=0,ppb_price=cast('".$_REQUEST['base_pl_src'][0]."' as money), ";
					$qry0a  .= "adj_price=cast('".$adj_price."' as money), ";
					$qry0a  .= "var_price=cast('".$_REQUEST['base_pl_src'][1]."' as money), ";
					$qry0a  .= "udate=getdate(),udateby=".$_SESSION['securityid']." ";
					$qry0a  .= "where aid=".$row0['aid'].";";
					$res0a   = mssql_query($qry0a);
				}
			}
			elseif ($nrow0==0 && $_REQUEST['base_pl_src'][1]!=0)
			{
					echo "TEST RETAIL ADJ 2<BR>";
					$qry0a   = "INSERT INTO [jest].[dbo].[base_price_adjusts] (";
					$qry0a  .= " [oid] ";
					$qry0a  .= ",[sid] ";
					$qry0a  .= ",[estid] ";
					$qry0a  .= ",[stage] ";
					$qry0a  .= ",[pft] ";
					$qry0a  .= ",[sqft] ";
					$qry0a  .= ",[ppb_price] ";
					$qry0a  .= ",[adj_price] ";
					$qry0a  .= ",[var_price] ";
					$qry0a  .= ",[udateby] ";
					$qry0a  .= ") VALUES (";
					$qry0a  .= " ".$_SESSION['officeid']." ";
					$qry0a  .= ",".$rowA['securityid']." ";
					$qry0a  .= ",".$_SESSION['estid']." ";
					$qry0a  .= ",'e' ";
					$qry0a  .= ",0 ";
					$qry0a  .= ",0 ";
					$qry0a  .= ",cast('".$_REQUEST['base_pl_src'][0]."' as money) ";
					$qry0a  .= ",cast('".($_REQUEST['base_pl_src'][0] + $_REQUEST['base_pl_src'][1])."' as money) ";
					$qry0a  .= ",cast('".$_REQUEST['base_pl_src'][1]."' as money) ";
					$qry0a  .= ",".$_SESSION['securityid']." ";
					$qry0a  .= ");";
					$res0a   = mssql_query($qry0a);
			}
		}
		elseif ($rowA['esttype']=='Q') // Updates Quotes
		{
			if ($_REQUEST['base_pl_src'][0]!=$_REQUEST['base_pl_src'][1] && $_REQUEST['base_pl_src'][0]!='0.00' && $_REQUEST['base_pl_src'][1]!='0.00')
			{
				/*echo "<pre>";
				print_r($_REQUEST['base_pl_src']);
				echo "<br>";
				echo "</pre>";*/
					
				$var_diff=number_format($_REQUEST['base_pl_src'][1] - $_REQUEST['base_pl_src'][0], 2, '.', '');
				//echo 'DIFFXX: '. $var_diff .'<br>';
				
				$qry0  = "select * from [jest].[dbo].[base_price_adjusts] where oid=".$_SESSION['officeid']." and estid=".$_SESSION['estid'].";";
				$res0  = mssql_query($qry0);
				$row0  = mssql_fetch_array($res0);
				$nrow0 = mssql_num_rows($res0);
				
				if ($nrow0 > 0)
				{
					$qry0a   = "update [jest].[dbo].[base_price_adjusts] set ";
					$qry0a  .= "pft=0,sqft=0,ppb_price=cast('".$_REQUEST['base_pl_src'][0]."' as money), ";
					$qry0a  .= "adj_price=cast('".$_REQUEST['base_pl_src'][1]."' as money), ";
					$qry0a  .= "var_price=cast('".$var_diff."' as money), ";
					$qry0a  .= "udate=getdate(),udateby=".$_SESSION['securityid']." ";
					$qry0a  .= "where aid=".$row0['aid'].";";
					$res0a   = mssql_query($qry0a);
					//$row0a  = mssql_fetch_array($res0a);
				}
				elseif ($var_diff!=0)
				{
					$qry0a   = "INSERT INTO [jest].[dbo].[base_price_adjusts] (";
					$qry0a  .= " [oid] ";
					$qry0a  .= ",[sid] ";
					$qry0a  .= ",[estid] ";
					$qry0a  .= ",[stage] ";
					$qry0a  .= ",[pft] ";
					$qry0a  .= ",[sqft] ";
					$qry0a  .= ",[ppb_price] ";
					$qry0a  .= ",[adj_price] ";
					$qry0a  .= ",[var_price] ";
					$qry0a  .= ",[udateby] ";
					$qry0a  .= ") VALUES (";
					$qry0a  .= " ".$_SESSION['officeid']." ";
					$qry0a  .= ",".$rowA['securityid']." ";
					$qry0a  .= ",".$_SESSION['estid']." ";
					$qry0a  .= ",'e' ";
					$qry0a  .= ",0 ";
					$qry0a  .= ",0 ";
					$qry0a  .= ",cast('".$_REQUEST['base_pl_src'][0]."' as money) ";
					$qry0a  .= ",cast('".$_REQUEST['base_pl_src'][1]."' as money) ";
					$qry0a  .= ",cast('".$var_diff."' as money) ";
					$qry0a  .= ",".$_SESSION['securityid']." ";
					$qry0a  .= ");";
					$res0a   = mssql_query($qry0a);
				}
			}
		}
	}
	
	//Acc Price Adjust
	if (isset($_REQUEST['acc_pb_src']))
	{
		$acc_ar=array();
		$acc_pr=array();
		$qry1  = "select aid,accid,ppb_price,adj_price,var_price from [jest].[dbo].[acc_price_adjusts] where oid=".$_SESSION['officeid']." and estid=".$_SESSION['estid'].";";
		$res1  = mssql_query($qry1);
		$nrow1 = mssql_num_rows($res1);
		
		if ($nrow1 > 0)
		{
			while($row1  = mssql_fetch_array($res1))
			{
				//$acc_ar[]=$row1['accid'];
				$acc_ar[$row1['accid']]=array($row1['aid'],$row1['ppb_price'],$row1['adj_price'],$row1['var_price']);
			}
			
			/*echo "<pre>";
			print_r($acc_ar);
			echo "<br>";
			echo "</pre>";*/
		}
		
		foreach ($_REQUEST['acc_pb_src'] as $n => $v)
		{
			if ($_REQUEST['acc_pb_src'][$n][0]!=$_REQUEST['acc_pb_src'][$n][1])
			{
				/*echo "<pre>";
				print_r($_REQUEST['acc_pb_src']);
				echo "<br>";
				echo "</pre>";*/
				
				$var_diff=number_format($_REQUEST['acc_pb_src'][$n][1] - $_REQUEST['acc_pb_src'][$n][0], 2, '.', '');
				
				//echo 'DIFF['. $n .']: '. $var_diff .'<br>';

				//echo 'INPUT: '.$n.'<br>';
				//echo 'OUTER: '.$acc_ar[$n][0].'<br>';
				//echo 'TB: '.$_REQUEST['acc_pb_src'][$n][0].'<br>';
				//if (is_array($acc_ar) && in_array($n,$acc_ar) && $_REQUEST['acc_pb_src'][$n]==$n)
				if (is_array($acc_ar) && array_key_exists($n,$acc_ar) && $var_diff!=0)
				{
					$qry0a   = "update [jest].[dbo].[acc_price_adjusts] set ";
					$qry0a  .= "pft=0,sqft=0,ppb_price=cast('".$_REQUEST['acc_pb_src'][$n][0]."' as money), ";
					$qry0a  .= "adj_price=cast('".$_REQUEST['acc_pb_src'][$n][1]."' as money), ";
					$qry0a  .= "var_price=cast('".$var_diff."' as money), ";
					$qry0a  .= "udate=getdate(),udateby=".$_SESSION['securityid']." ";
					$qry0a  .= "where oid=".$_SESSION['officeid']." and estid=".$_SESSION['estid']." and aid=".$acc_ar[$n][0].";";
					$res0a   = mssql_query($qry0a);
					
					//echo $qry0a.'<br>';
				}
				elseif ($var_diff!=0)
				{
					$qry0a   = "INSERT INTO [jest].[dbo].[acc_price_adjusts] (";
					$qry0a  .= " [oid] ";
					$qry0a  .= ",[sid] ";
					$qry0a  .= ",[estid] ";
					$qry0a  .= ",[stage] ";
					$qry0a  .= ",[pft] ";
					$qry0a  .= ",[sqft] ";
					$qry0a  .= ",[accid] ";
					$qry0a  .= ",[ppb_price] ";
					$qry0a  .= ",[adj_price] ";
					$qry0a  .= ",[var_price] ";
					$qry0a  .= ",[udateby] ";
					$qry0a  .= ") VALUES (";
					$qry0a  .= " ".$_SESSION['officeid']." ";
					$qry0a  .= ",".$rowA['securityid']." ";
					$qry0a  .= ",".$_SESSION['estid']." ";
					$qry0a  .= ",'e' ";
					$qry0a  .= ",0 ";
					$qry0a  .= ",0 ";
					$qry0a  .= ",".$n." ";
					$qry0a  .= ",cast('".$_REQUEST['acc_pb_src'][$n][0]."' as money) ";
					$qry0a  .= ",cast('".$_REQUEST['acc_pb_src'][$n][1]."' as money) ";
					$qry0a  .= ",cast('".$var_diff."' as money) ";
					$qry0a  .= ",".$_SESSION['securityid']." ";
					$qry0a  .= ");";
					$res0a   = mssql_query($qry0a);
				}
			}
		}
	}
}

function updateest()
{
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	$MAS	= $_SESSION['pb_code'];
	$estid	= $_SESSION['estid'];
	
	$qry	= "SELECT bprice,rprice,zcharge FROM [".$MAS."accpbook] WHERE officeid=".$_SESSION['officeid']." AND phsid=40 AND zcharge=".$_REQUEST['tzone'].";";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_row($res);
	
	$qry0	= "SELECT securityid,sidm FROM jest..security WHERE securityid=".$_REQUEST['securityid'].";";
	$res0	= mssql_query($qry0);
	$row0	= mssql_fetch_array($res0);
	
	$qry1	= "SELECT officeid,gm FROM jest..offices WHERE officeid=".$_SESSION['officeid'].";";
	$res1	= mssql_query($qry1);
	$row1	= mssql_fetch_array($res1);
	
	if ($row0['sidm']!=0)
	{
		$sidm=$row0['sidm'];
	}
	else
	{
		$sidm=$row1['gm'];
	}
	
	$tchrg	= array(0=>$row[0],1=>$row[1]);

	if (!isset($_REQUEST['refto']))
	{
		$refto='';
	}
	else
	{
		$refto=$_REQUEST['refto'];
	}

	if (!isset($_REQUEST['est_cost']))
	{
		$est_cost=0;
	}
	else
	{
		$est_cost=$_REQUEST['est_cost'];
	}
	
	$qryA   = "exec sp_updateest ";
	$qryA  .= "@estid='".$estid."', ";
	$qryA  .= "@custid='".$_REQUEST['custid']."', ";
	$qryA  .= "@cid='".$_REQUEST['cid']."', ";
	$qryA  .= "@officeid='".$_SESSION['officeid']."', ";
	$qryA  .= "@securityid='".$_REQUEST['securityid']."', ";
	$qryA  .= "@sidm='".$sidm."', ";
	$qryA  .= "@status='".replacequote($_REQUEST['status'])."', ";
	$qryA  .= "@pft='".replacequote($_REQUEST['ps1'])."', ";
	$qryA  .= "@apft='".replacequote($_REQUEST['ps1'])."', ";
	$qryA  .= "@sqft='".replacequote($_REQUEST['ps2'])."', ";
	$qryA  .= "@shal='".replacequote($_REQUEST['ps5'])."', ";
	$qryA  .= "@mid='".replacequote($_REQUEST['ps6'])."', ";
	$qryA  .= "@deep='".replacequote($_REQUEST['ps7'])."', ";
	$qryA  .= "@deck='".replacequote($_REQUEST['deck'])."', ";
	$qryA  .= "@spa_pft='".replacequote($_REQUEST['spa2'])."', ";
	$qryA  .= "@spa_sqft='".replacequote($_REQUEST['spa3'])."', ";
	$qryA  .= "@spatype='".replacequote($_REQUEST['spa1'])."', ";
	$qryA  .= "@tzone='".replacequote($_REQUEST['tzone'])."', ";
	$qryA  .= "@erun='".replacequote($_REQUEST['erun'])."', ";
	$qryA  .= "@prun='".replacequote($_REQUEST['prun'])."', ";
	$qryA  .= "@btchrg='".$tchrg[0]."', ";
	$qryA  .= "@rtchrg='".$tchrg[1]."', ";
	$qryA  .= "@contractamt='".replacequote($_REQUEST['contractamt'])."', ";
	$qryA  .= "@refto='".replacequote($refto)."', ";
	$qryA  .= "@est_cost='".replacequote($est_cost)."', ";
	$qryA  .= "@updateby='".$_SESSION['securityid']."';";
	$resA   = mssql_query($qryA);
	
	viewest_retail();
}

function add_acc_items($estid)
{
	if (!isset($estid) || $estid==0)
	{
		echo 'Estimate Info missing.<br>Exiting...';
		exit;
	}
	
	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";*/
	
	$estdata=estAdata_init();
	
	//echo $estdata.'<br>';

	$qryA  = "sp_updateest_ext @estid='".$estid."',@officeid='".$_SESSION['officeid']."',@estdata='".$estdata."';";
	$resA   = mssql_query($qryA);
	
	if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
	{
		$renov=1;
	}
	else
	{
		$renov=0;
	}

	foreach ($_POST as $n=>$v)
	{
		if (substr($n,0,4)=="bbba")
		{
			$asid=substr($n,4);
			if ($_REQUEST['bbba'.$asid] > 0)
			{
				if (array_key_exists("eeea".$asid,$_POST))
				{
					$qryB  = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
					$resB  = mssql_query($qryB);
					$rowB  = mssql_fetch_array($resB);
					$nrowB = mssql_num_rows($resB);

					if ($nrowB < 1)
					{
						$qryC  = "INSERT INTO est_bids (officeid,estid,bidinfo,bidaccid) VALUES ('".$_SESSION['officeid']."','".$_SESSION['estid']."','".replacequote($_REQUEST['eeea'.$asid])."','".$asid."');";
						$resC  = mssql_query($qryC);
					}
					elseif ($_REQUEST['eeea'.$asid]!=$rowB['bidinfo'])
					{
						$qryC  = "UPDATE est_bids SET bidinfo='".replacequote($_REQUEST['eeea'.$asid])."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
						$resC  = mssql_query($qryC);
					}
				}
			}
		}
	}

	$qryD = "UPDATE est SET updateby='".$_SESSION['securityid']."',renov='".$renov."',updated=GETDATE() WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$resD = mssql_query($qryD);

	viewest_retail($estid,0);
}

function add_acc_items_add($estid)
{
	$estdata=estAdata_init();

	$qryA  = "sp_updateest_ext_add @estid='".$estid."',@officeid='".$_SESSION['officeid']."',@estdata='".$estdata."';";
	$resA   = mssql_query($qryA);

	foreach ($_POST as $n=>$v)
	{
		if (substr($n,0,4)=="bbba")
		{
			$asid=substr($n,4);
			if ($_REQUEST['bbba'.$asid] > 0)
			{
				if (array_key_exists("eeea".$asid,$_POST))
				{
					$qryB  = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
					$resB  = mssql_query($qryB);
					$rowB  = mssql_fetch_array($resB);
					$nrowB = mssql_num_rows($resB);

					if ($nrowB < 1)
					{
						$qryC  = "INSERT INTO est_bids (officeid,estid,bidinfo,bidaccid) VALUES ('".$_SESSION['officeid']."','".$_SESSION['estid']."','".replacequote($_REQUEST['eeea'.$asid])."','".$asid."');";
						$resC  = mssql_query($qryC);
					}
					elseif ($_REQUEST['eeea'.$asid]!=$rowB['bidinfo'])
					{
						$qryC  = "UPDATE est_bids SET bidinfo='".replacequote($_REQUEST['eeea'.$asid])."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
						$resC  = mssql_query($qryC);
					}
				}
			}
		}
	}
	viewest_retail($estid,1);

	//echo $qryA;
}

function listest()
{
	//echo "TEST<BR>";
	$officeid=$_SESSION['officeid'];
	$securityid=$_SESSION['securityid'];
	$acclist=explode(",",$_SESSION['aid']);

	if (isset($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	else
	{
		$order="estid";
	}

	if (isset($_REQUEST['ascdesc']))
	{
		$dir=$_REQUEST['ascdesc'];
	}
	else
	{
		$dir="ASC";
	}
	
	if (isset($_REQUEST['etype']) && $_REQUEST['etype']=='E')
	{
		$etype='Estimates';
	}
	else
	{
		$etype='Quotes';
	}

	if ($_REQUEST['call']=="search_results")
	{
		if ($_REQUEST['subq']=="salesman")
		{
			$qry   = "SELECT ";

			$qry   .= "a.estid AS aestid, ";
			$qry   .= "b.securityid AS asec,";
			$qry   .= "a.cid AS acid,";
			$qry   .= "a.contractamt AS acontr,";
			$qry   .= "a.added AS aadd,";
			$qry   .= "a.updated AS aup,";
			$qry   .= "a.submitted AS asub, ";
			$qry   .= "b.cfname AS bcfname, ";
			$qry   .= "b.clname AS bclname, ";
			$qry   .= "b.chome AS bchome, ";
			$qry   .= "b.custid AS bcustid, ";
			$qry   .= "b.estid AS bestid, ";
			$qry   .= "a.renov AS renov, ";
			$qry   .= "a.esttype AS esttype ";
			$qry  .= "FROM [est] AS a ";
			$qry  .= "INNER JOIN [cinfo] AS b ";
			$qry  .= "ON a.estid=b.estid ";
			$qry  .= "AND a.officeid=b.officeid ";
			$qry  .= "WHERE b.officeid='".$_SESSION['officeid']."' ";
			$qry  .= "AND b.jobid='0' ";
			$qry  .= "AND b.njobid='0' ";
			$qry  .= "AND b.securityid='".$_REQUEST['assigned']."' ";
			$qry   .="AND a.esttype = '".$_REQUEST['etype']."'  ";
			
			if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
			{
				$qry   .="AND a.renov = '".$_REQUEST['renov']."'  ";
			}

			$qry  .= "ORDER BY ".$order." ".$dir.";";
		}
		elseif ($_REQUEST['subq']=="last_name")
		{
			if (empty($_REQUEST['sval']))
			{
				echo "<b><font color=\"red\">Error!</font> Search String required.</b>";
				exit;
			}

			$qry   = "SELECT ";
			$qry   .= "a.estid AS aestid, ";
			$qry   .= "b.securityid AS asec,";
			$qry   .= "a.cid AS acid,";
			$qry   .= "a.contractamt AS acontr,";
			$qry   .= "a.added AS aadd,";
			$qry   .= "a.updated AS aup,";
			$qry   .= "a.submitted AS asub, ";
			$qry   .= "b.cfname AS bcfname, ";
			$qry   .= "b.clname AS bclname, ";
			$qry   .= "b.chome AS bchome, ";
			$qry   .= "b.custid AS bcustid, ";
			$qry   .= "b.estid AS bestid, ";
			$qry   .= "a.renov AS renov, ";
			$qry   .= "a.esttype AS esttype ";
			$qry  .= "FROM [est] AS a ";
			$qry  .= "INNER JOIN [cinfo] AS b ";
			$qry  .= "ON a.estid=b.estid ";
			$qry  .= "AND a.officeid=b.officeid ";
			$qry  .= "WHERE b.officeid='".$_SESSION['officeid']."' ";
			$qry  .= "AND b.jobid='0' ";
			$qry  .= "AND b.njobid='0' ";
			$qry  .= "AND b.clname LIKE '".$_REQUEST['sval']."%' ";
			$qry   .="AND a.esttype = '".$_REQUEST['etype']."'  ";
			
			if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
			{
				$qry   .="AND a.renov = '".$_REQUEST['renov']."'  ";
			}
			
			$qry  .= "ORDER BY ".$order." ".$dir.";";
		}
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);
	
	if ($_SESSION['securityid']==99999999999999)
	{
		echo $qry."<br>";
	}
	
	if ($nrows < 1)
	{
		echo "<table class=\"outer\" align=\"center\" width=\"90%\">\n";
		echo "   <tr>\n";
		echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "   <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "   <input type=\"hidden\" name=\"call\" value=\"new\">\n";
		echo "      <td class=\"gray\" align=\"center\">\n";

		if ($_REQUEST['call']=="search_results")
		{
			echo "         <h4><b>".$etype." Search did not produce any Results!</h4>";
		}
		else
		{
			echo "         <h4>No ".$etype."  on File!</h4>";
		}

		echo "      </td>\n";
		echo "   </form>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		//print_r($acclist);
		echo "<table class=\"outer\" align=\"center\" width=\"90%\">\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\">\n";
		echo "         <table width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "                     </tr>\n";
		echo "                   </table>\n";
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\" bgcolor=\"white\">\n";
		echo "                  <tr>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b></b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>#</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Renov</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>Customer</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Phone</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Amount</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>SalesRep</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>Insert Date</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>Date Updated</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>#</b></td>\n";
		echo "                  </tr>\n";

		$tcon=0;
		$xi=0;
		while($row=mssql_fetch_array($res))
		{
			$xi++;
			$tbg = "wh_und";
			//$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row['acid']."'";
			//$resB = mssql_query($qryB);
			//$rowB = mssql_fetch_array($resB);
			//echo $qryB."<br>";

			$qryC = "SELECT fname,lname,securityid,sidm,slevel FROM security WHERE securityid='".$row['asec']."'";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			$secl=explode(",",$rowC['slevel']);

			if ($secl[6]==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}

			if (in_array($row['asec'],$acclist)||$_SESSION['jlev'] >= 6)
			{
				$tcon			=$tcon+$row['acontr'];
				$fconamt		=number_format($row['acontr'], 2, '.', ',');

				if (isset($row['aadd']))
				{
					$odate = date("m-d-Y", strtotime($row['aadd']));
				}
				else
				{
					$odate = "";
				}

				if (isset($row['aup']))
				{
					$udate = date("m-d-Y", strtotime($row['aup']));
				}
				else
				{
					$udate = "";
				}

				if (isset($row['asub']))
				{
					$sdate = date("m-d-Y", strtotime($row['asub']));
				}
				else
				{
					$sdate = "";
				}
				
				if ($row['renov']==1)
				{
					$renov="R";
				}
				else
				{
					$renov="";
				}

				echo "                  <tr>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['aestid']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\"><b>".$renov."</b></td>\n";	
				echo "                     <td class=\"".$tbg."\" align=\"left\">&nbsp<b>".$row['bclname']."</b>, ".$row['bcfname']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\">&nbsp".$row['bchome']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">&nbsp".$fconamt."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\"><font class=\"".$fstyle."\">".$rowC['lname'].", ".$rowC['fname']."</font></td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\">&nbsp".$odate."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\">&nbsp".$udate."</td>\n";
				echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"cview\">\n";
				echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['bcustid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";
				echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Customer Info\">\n";
				echo "                     </td>\n";
				echo "                        </form>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">\n";
				echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$row['aestid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"esttype\" value=\"".$row['esttype']."\">\n";
				
				if ($row['esttype']=='E')
				{
					echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Estimate\">\n";
				}
				else
				{
					echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Quote\">\n";
				}
				
				echo "                        </form>\n";
				echo "                     </td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi."</td>\n";
				echo "                  </tr>\n";
			}
		}

		$ftcon        =number_format($tcon, 2, '.', ',');

		echo "                  <tr>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\" colspan=\"5\"><b>Total Estimates</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\"><b>".$ftcon."</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                  </tr>\n";
		echo "                  </table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function listestOLD()
{
	//echo "TEST<BR>";
	$officeid=$_SESSION['officeid'];
	$securityid=$_SESSION['securityid'];
	$acclist=explode(",",$_SESSION['aid']);

	if (isset($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	else
	{
		$order="estid";
	}

	if (isset($_REQUEST['ascdesc']))
	{
		$dir=$_REQUEST['ascdesc'];
	}
	else
	{
		$dir="ASC";
	}

	if ($_REQUEST['call']=="search_results")
	{
		if ($_REQUEST['subq']=="salesman")
		{
			$qry   = "SELECT ";

			$qry   .= "a.estid AS aestid, ";
			$qry   .= "b.securityid AS asec,";
			$qry   .= "a.cid AS acid,";
			$qry   .= "a.contractamt AS acontr,";
			$qry   .= "a.added AS aadd,";
			$qry   .= "a.updated AS aup,";
			$qry   .= "a.submitted AS asub, ";
			$qry   .= "b.cfname AS bcfname, ";
			$qry   .= "b.clname AS bclname, ";
			$qry   .= "b.chome AS bchome, ";
			$qry   .= "b.custid AS bcustid, ";
			$qry   .= "b.estid AS bestid, ";
			$qry   .= "a.renov AS renov ";

			$qry  .= "FROM [est] AS a ";
			$qry  .= "INNER JOIN [cinfo] AS b ";
			$qry  .= "ON a.estid=b.estid ";
			$qry  .= "AND a.officeid=b.officeid ";
			$qry  .= "WHERE b.officeid='".$_SESSION['officeid']."' ";
			$qry  .= "AND b.jobid='0' ";
			$qry  .= "AND b.njobid='0' ";
			$qry  .= "AND b.securityid='".$_REQUEST['assigned']."' ";
			
			if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
			{
				$qry   .="AND a.renov = '".$_REQUEST['renov']."'  ";
			}
			
			$qry  .= "ORDER BY ".$order." ".$dir.";";
		}
		elseif ($_REQUEST['subq']=="last_name")
		{
			if (empty($_REQUEST['sval']))
			{
				echo "<b><font color=\"red\">Error!</font> Search String required.</b>";
				exit;
			}

			$qry   = "SELECT ";
			$qry   .= "a.estid AS aestid, ";
			$qry   .= "b.securityid AS asec,";
			$qry   .= "a.cid AS acid,";
			$qry   .= "a.contractamt AS acontr,";
			$qry   .= "a.added AS aadd,";
			$qry   .= "a.updated AS aup,";
			$qry   .= "a.submitted AS asub, ";
			$qry   .= "b.cfname AS bcfname, ";
			$qry   .= "b.clname AS bclname, ";
			$qry   .= "b.chome AS bchome, ";
			$qry   .= "b.custid AS bcustid, ";
			$qry   .= "b.estid AS bestid, ";
			$qry   .= "a.renov AS renov ";

			$qry  .= "FROM [est] AS a ";
			$qry  .= "INNER JOIN [cinfo] AS b ";
			$qry  .= "ON a.estid=b.estid ";
			$qry  .= "AND a.officeid=b.officeid ";
			$qry  .= "WHERE b.officeid='".$_SESSION['officeid']."' ";
			$qry  .= "AND b.jobid='0' ";
			$qry  .= "AND b.njobid='0' ";
			$qry  .= "AND b.clname LIKE '".$_REQUEST['sval']."%' ";
			
			if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
			{
				$qry   .="AND a.renov = '".$_REQUEST['renov']."'  ";
			}
			
			$qry  .= "ORDER BY ".$order." ".$dir.";";
		}
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);
	//echo $qry."<br>";

	if ($nrows < 1)
	{
		echo "<table class=\"outer\" align=\"center\" width=\"90%\">\n";
		echo "   <tr>\n";
		echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "   <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "   <input type=\"hidden\" name=\"call\" value=\"new\">\n";
		echo "      <td class=\"gray\" align=\"center\">\n";

		if ($_REQUEST['call']=="search_results")
		{
			echo "         <h4><b>Estimate Search did not produce any Results!</h4>";
		}
		else
		{
			echo "         <h4>No Estimates on File!</h4><br>Click <input type=\"submit\" class=\"buttondkgry\" value=\"Here\"> to Create a Lead, then an Estimate.\n";
		}

		echo "      </td>\n";
		echo "   </form>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		//print_r($acclist);
		echo "<table class=\"outer\" align=\"center\" width=\"90%\">\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\">\n";
		echo "         <table width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "                     </tr>\n";
		echo "                   </table>\n";
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\" bgcolor=\"white\">\n";
		echo "                  <tr>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b></b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Est #</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Renov</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>Customer</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Phone</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Estimate Amt</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>SalesRep</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>Insert Date</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>Date Updated</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Est #</b></td>\n";
		echo "                  </tr>\n";

		$tcon=0;
		$xi=0;
		while($row=mssql_fetch_array($res))
		{
			$xi++;
			$tbg = "wh_und";
			//$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row['acid']."'";
			//$resB = mssql_query($qryB);
			//$rowB = mssql_fetch_array($resB);
			//echo $qryB."<br>";

			$qryC = "SELECT fname,lname,securityid,sidm,slevel FROM security WHERE securityid='".$row['asec']."'";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			$secl=explode(",",$rowC['slevel']);

			if ($secl[6]==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}

			if (in_array($row['asec'],$acclist)||$_SESSION['jlev'] >= 6)
			{
				$tcon			=$tcon+$row['acontr'];
				$fconamt		=number_format($row['acontr'], 2, '.', ',');

				if (isset($row['aadd']))
				{
					$odate = date("m-d-Y", strtotime($row['aadd']));
				}
				else
				{
					$odate = "";
				}

				if (isset($row['aup']))
				{
					$udate = date("m-d-Y", strtotime($row['aup']));
				}
				else
				{
					$udate = "";
				}

				if (isset($row['asub']))
				{
					$sdate = date("m-d-Y", strtotime($row['asub']));
				}
				else
				{
					$sdate = "";
				}
				
				if ($row['renov']==1)
				{
					$renov="R";
				}
				else
				{
					$renov="";
				}

				echo "                  <tr>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['aestid']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\"><b>".$renov."</b></td>\n";	
				echo "                     <td class=\"".$tbg."\" align=\"left\">&nbsp<b>".$row['bclname']."</b>, ".$row['bcfname']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\">&nbsp".$row['bchome']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">&nbsp".$fconamt."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\"><font class=\"".$fstyle."\">".$rowC['lname'].", ".$rowC['fname']."</font></td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\">&nbsp".$odate."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\">&nbsp".$udate."</td>\n";
				echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"cview\">\n";
				echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['bcustid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";
				echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Customer Info\">\n";
				echo "                     </td>\n";
				echo "                        </form>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">\n";
				echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$row['aestid']."\">\n";
				echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Estimate\">\n";
				echo "                        </form>\n";
				echo "                     </td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi."</td>\n";
				echo "                  </tr>\n";
			}
		}

		$ftcon        =number_format($tcon, 2, '.', ',');

		echo "                  <tr>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\" colspan=\"5\"><b>Total Estimates</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"right\"><b>".$ftcon."</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                  </tr>\n";
		echo "                  </table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function pb_elements($id,$aid,$officeid,$item,$accpbook,$qtype,$seqn,$rp,$bp,$spaitem,$mtype,$atrib1,$atrib2,$atrib3,$quan_calc,$commtype,$crate,$disabled,$r_estdata,$tbg,$sid)
{
	error_reporting(E_ALL);
	//$tbg="gray_whtbrdr";
	//$tbg="wh";

	if (isset($mtype) && $mtype!=0)
	{
		$qryB = "SELECT mid,abrv FROM mtypes WHERE mid='".$mtype."'";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);
		
		$fmtype=$rowB['abrv'];
	}
	else
	{
		$fmtype='';
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

	$s0	=$id;
	$s1	="aaaa".$s0;                // Acc ID
	$s2	="bbba".$s0;                // Quantity
	$s3	="ccca".$s0;                // Orig Price
	$s4	="ddda".$s0;                // Price
	$s5	="code".$s0;                // Material Code
	$s6	="eeea".$s0;                // Bid Item
	$s7	="fffa".$s0;                // Question Type Code
	$s8	="ggga".$s0;                // Comm Type Code
	$s9	="hhha".$s0;                // Comm Rate
	$s10="iiia".$s0;                // Quan Calc
	
	
	if (isset($db_rp) && $db_rp!=0)
	{
		$rp	=number_format($db_rp, 2, '.', ''); // BP from Quote
	}
	else
	{
		$qryC = "SELECT * FROM acc_price_pad WHERE oid=".$officeid." and sid=".$sid." and iid=".$id.";";
		$resC = mssql_query($qryC);
		$nrowC= mssql_num_rows($resC);
		
		if ($nrowC != 0)
		{
			$rowC 		= mssql_fetch_array($resC);
			$pid		=$rowC['pid'];
			$adj_price	=number_format($rowC['adj_price'], 2, '.', '');
		}
		else
		{
			$pid		=0;
			$adj_price	=$rp;
		}
		
		$rp	=number_format($adj_price, 2, '.', ''); // BP from DB
	}

	if ($disabled==1)
	{
		if ($db_id==$id)
		{
			echo "                           <input type=\"hidden\" name=\"".$s1."\" value=\"".$s0."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s2."\" value=\"".$db_qn."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s3."\" value=\"".$spaitem."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s4."\" value=\"".$rp."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s7."\" value=\"".$qtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s8."\" value=\"".$commtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s9."\" value=\"".$crate."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s10."\" value=\"".$quan_calc."\">\n";
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
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";			
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s2\" value=\"1\">\n";
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
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			//echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			//echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\">\n";
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s4\" value=\"$rp\" size=\"6\" maxlength=\"15\">\n";
			//$rp
			echo "						  </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\">\n";
	
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
	
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
	
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			//echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\">\n";
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s4\" value=\"$rp\" size=\"6\" maxlength=\"15\">\n";
			echo "						  </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
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
		elseif (
		$qtype==18||
		$qtype==19||
		$qtype==21||
		$qtype==22||
		$qtype==40
		)
		{
			// Code (PFT - SQFT - IA - Gallons - No Charge)
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			//echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"0\">\n";
			}
	
			//echo "                        </td>\n";
			echo "                      <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "						<td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "						<td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
			if ($db_id==$id)
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
		elseif ($qtype==20)
		{
			if ($db_id==$id)
			{
				$qryCODE = "SELECT item,rp FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$db_cd."';";
				$resCODE = mssql_query($qryCODE);
				$rowCODE = mssql_fetch_array($resCODE);
			}
	
			// Code (Quantity)
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";

			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
	
			if (!empty($rowCODE['item']))
			{
				echo " (".$rowCODE['item'].")";
			}
	
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			//echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\">\n";
			}
	
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
			{
				echo                            $rowCODE['rp'];
			}
	
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
			if ($db_id==$id)
			{
				echo                            $fmtype;
			}
	
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo                            $accpbook;
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif ($qtype==23)
		{
			// Code (Checkbox)
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			//echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"left\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
			if ($db_id==$id)
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
		elseif (
		$qtype==24||
		$qtype==25||
		$qtype==27||
		$qtype==28||
		$qtype==29
		)
		{
			// Multiple Choice (PFT - SQFT - IA - Gallons - Checkbox)
			$qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM [".$MAS."accpbook] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND accid='".$accid."' ORDER BY accid";
			$resC = mssql_query($qryC);
	
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			echo "                           <select name=\"$s1\">\n";
	
			while($rowC = mssql_fetch_row($resC))
			{
				echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
			}
	
			echo "                           </select>\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><img src=\"../images/pixel.gif\"></td>\n";;
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo                            $fmtype;
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"left\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			//echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif ($qtype==26)
		{
			// Multiple Choice (Quantity)
			$qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid AND accid=$accid ORDER BY accid";
			$resC = mssql_query($qryC);
	
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			echo "                           <select name=\"$s1\">\n";
	
			while($rowC = mssql_fetch_row($resC))
			{
				echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
			}
	
			echo "                           </select>\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"20\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo                            $fmtype;
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"left\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			//echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s2\" size=\"4\" maxlength=\"5\" value=\"0\"> $accpbook\n";
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
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "									</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>\n";
			echo "                           			<textarea name=\"$s6\" rows=\"2\" cols=\"35\">";
	
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
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			//echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
	
			if ($db_id==$id)
			{
				echo "                             <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\" NOWRAP>n/a</td>\n";
				echo "                             <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
				echo "                        		</td>\n";
			}
			else
			{
				echo "                             <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$rp\"></td>\n";
				echo "                             <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\" NOWRAP>n/a</td>\n";
				echo "                             <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
				echo "                           <input class=\"transnb\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
				echo "                        		</td>\n";
			}
	
			echo "                     </tr>\n";
		}
		elseif  ($qtype==54)
		{
			// Referral
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">";
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			//echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\">\n";
	
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

function showdescrip_quote($i,$a1,$a2,$a3)
{
	echo "                        <table align=\"left\" width=\"100%\" border=0>\n";
	
	if (strlen($i) > 1)
	{
		echo "                           <tr>\n";
		echo "                              <td colspan=\"2\" align=\"left\">\n";
		
		if (isset($id) && $id!=0)
		{
			echo "									<a href=\"#PBi_".$id."\">".trim($i)."</a>\n";
		}
		else
		{
			echo "									".trim($i)."\n";
		}
		
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

function pbmatrix()
{
	error_reporting(E_ALL);
	//$MAS	=$_SESSION['pb_code'];
	$_SESSION['pbupdate']++;

	$qry0 = "SELECT estid,esttype,securityid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$qryA = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	echo "<form name=\"updateest\" action=\"../index.php\" method=\"post\" target=\"_parent\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"esttype\" value=\"".$row0['esttype']."\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"add_acc_items\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['viewarray']['estsecid']."\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$_SESSION['estid']."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$_SESSION['viewarray']['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$_SESSION['viewarray']['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"contractamt\" value=\"0.00\">\n";
	echo "<input type=\"hidden\" name=\"showdetail\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"tzone\" value=\"0\">\n";
	
	if (isset($_REQUEST['esttype']))
	{
		echo "<input type=\"hidden\" name=\"esttype\" value=\"".$_REQUEST['esttype']."\">\n";
	}
	
	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "	<div id=\"masterdiv\">\n";
	echo "		<table width=\"100%\" border=0>\n";
	
	if (isset($_SESSION['pricebookdata']))
	{
		$pbdata_ar=json_decode($_SESSION['pricebookdata'],true);
		
		if ($pbdata_ar[array_rand($pbdata_ar)][2][0][2]!=$_SESSION['officeid'])
		{
			load_pricebook_data();
			$pbdata_ar=json_decode($_SESSION['pricebookdata'],true);
		}

		if (is_array($pbdata_ar))
		{
			$qryB = "SELECT id,catid,name FROM AC_Cats WHERE officeid='".$_SESSION['officeid']."' and active=1 and privcat=1;";
			$resB = mssql_query($qryB);
			$nrowB= mssql_num_rows($resB);
			
			if ($nrowB > 0)
			{
				while($rowB = mssql_fetch_array($resB))
				{
					$qryC = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled FROM [".$_SESSION['pb_code']."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid=".$rowB['catid']." AND phsid=".$_SESSION['securityid']." AND disabled=0 ORDER BY seqn;";
					$resC = mssql_query($qryC);
					$nrowC= mssql_num_rows($resC);
					
					if ($nrowC > 0)
					{
						//echo 'LOADing Pers PB <br>';
						$pbdata_ar[$rowB['catid']]=   array(
											0=>	$rowB['catid'],
											1=>	$rowB['name']
											);
							
						while($rowC = mssql_fetch_array($resC))
						{
							$pbdata_ar[$rowB['catid']][2][]=   array(
														$rowC['id'],
														$rowC['aid'],
														$rowC['officeid'],
														$rowC['item'],
														$rowC['accpbook'],
														$rowC['qtype'],
														$rowC['seqn'],
														$rowC['rp'],
														$rowC['bp'],
														$rowC['spaitem'],
														$rowC['mtype'],
														$rowC['atrib1'],
														$rowC['atrib2'],
														$rowC['atrib3'],
														$rowC['quan_calc'],
														$rowC['commtype'],
														$rowC['crate'],
														$rowC['disabled']
														);
						}
					}
				}
			}
			
			$ecnt=1;
			
			echo "	<tr>\n";
			echo "		<td valign=\"top\">\n";
			echo "			<table width=\"100%\">\n";
			
			foreach ($pbdata_ar as $no => $vo)
			{
				if ($vo[0]!=0)
				{
					echo "				<tr>\n";
					echo "					<td align=\"left\">\n";
					echo "                  	<div onclick=\"SwitchMenu('sub".trim($vo[0])."ax')\">\n";
					echo "						<table class=\"outer\" width=\"100%\">\n";
					echo "							<tr>\n";
					echo "								<td class=\"drkgray\" align=\"left\">\n";
					echo "                  				<b>".trim($vo[1])."</b>\n";
					echo "								</td>\n";
					echo "								<td class=\"drkgray\" align=\"right\">\n";
					echo "									(Click to Expand)\n";
					echo "								</td>\n";
					echo "							</tr>\n";
					echo "						</table>\n";
					echo "						</div>\n";
					echo "					</td>\n";
					echo "				</tr>\n";
					echo "				<tr>\n";
					echo "					<td>\n";
					echo "                  	<span class=\"submenu\" id=\"sub".$vo[0]."ax\">\n";
					echo "						<table width=\"100%\">\n";
	
					$tbgcnt=1;
					foreach($vo[2] as $pn => $pv)
					{
						if ($tbgcnt%2)
						{
							$tbg='whlist';
						}
						else
						{
							$tbg='ltgraylist';
						}
						$tbgcnt++;
						
						if ($pv[5]==32)
						{
							echo "				<tr>\n";
							echo "					<td class=\"gray\" colspan=\"3\">\n";
							echo "						<b>".trim($pv[3])."</b>\n";
							echo "					</td>\n";
							echo "					<td class=\"gray\" align=\"center\">\n";
							echo "						<input class=\"transnb\" type=\"image\" src=\"../images/save.gif\" alt=\"Save Selections\">\n";
							echo "					</td>\n";
							echo "					<td class=\"gray\" align=\"right\"><a href=\"#Top\"><img class=\"transnb\" src=\"../images/scrollup.gif\" alt=\"to Top\"></a></td>\n";
							echo "				</tr>\n";
							echo "              <tr>\n";
							echo "					<td class=\"gray\" valign=\"top\" align=\"left\" colspan=\"5\">\n";
		
							showdescrip_hdratribs(trim($pv[11]),trim($pv[12]),trim($pv[13]));
	
							echo "					</td>\n";
							echo "				</tr>\n";
						}

						
						pb_elements(trim($pv[0]),trim($pv[1]),trim($pv[2]),
										 trim($pv[3]),trim($pv[4]),trim($pv[5]),
										 trim($pv[6]),trim($pv[7]),trim($pv[8]),
										 trim($pv[9]),trim($pv[10]),trim($pv[11]),
										 trim($pv[12]),trim($pv[13]),trim($pv[14]),
										 trim($pv[15]),trim($pv[16]),trim($pv[17]),
										 trim($rowA['estdata']),$tbg,$row0['securityid']);
						
					}
					
					echo "  					</table>\n";
					echo "						</span>\n";
					echo "					</td>\n";
					echo "				</tr>\n";
				}
			}
		
			echo "  		</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
	}
	
	echo "		</table>\n";
	echo "	</div>\n";
	echo "</form>\n";
}

function new_quote()
{
	$MAS		=$_SESSION['pb_code'];
	$officeid	=$_SESSION['officeid'];
	$secid		=$_SESSION['securityid'];

	if (empty($_REQUEST['cid']))
	{
		//echo "<b>Error Occured! Click <a href=\"".$_SERVER['PHP_SELF']."?action=est&call=new\">HERE</a> to enter a Customer.</b>\n";
		echo "<b>Transition Error Occured!</b>\n";
		exit;
	}
	
	//autoadditems();

	$qrypre2 = "SELECT officeid,name,def_per,def_sqft,def_s,def_m,def_d,pft_sqft,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2 = mssql_query($qrypre2);
	$rowpre2 = mssql_fetch_array($respre2);

	$qryA = "SELECT quan FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA.'<br>';

	$qryAa = "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);

	$qryD = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC";
	$resD = mssql_query($qryD);

	$qryM  = "SELECT securityid,fname,lname,sidm,rmasid FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_REQUEST['estorig']."';";
	$resM  = mssql_query($qryM);
	$rowM  = mssql_fetch_array($resM);

	//$qryN  = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['cid']."';";
	$qryN  = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
	$resN  = mssql_query($qryN);
	$rowN  = mssql_fetch_array($resN);
	
	$uid  =md5(session_id().time().$rowN['cid']).".".$_SESSION['securityid'];

	if ($rowpre2['pft_sqft']=="p")
	{
		$defmeas=$rowpre2['def_per'];
	}
	else
	{
		$defmeas=$rowpre2['def_sqft'];
	}

	echo "<form name=\"saveest\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"saveest\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowM['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowM['sidm']."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$rowN['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"showdetail\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"tzone\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
	
	echo "<div id=\"masterdiv\">\n";
	echo "<table align=\"center\" width=\"750\" border=0>\n";
	echo "   <tr>\n";
	echo "      <td colspan=\"3\" align=\"left\">\n";
	echo "         <table border=\"0\" width=\"100%\">\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"3\" align=\"left\">\n";
	echo "               	<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "               		<tr>\n";
	echo "							<td class=\"gray\" align=\"left\" NOWRAP><b>Blue Haven Pools & Spas Quote</b></td>\n";
	
	if ($rowM[4]!=0)
	{
		if ($rowN['stage']==17)
		{
			echo "                     	<td class=\"gray\" align=\"right\"><b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\" CHECKED></td>\n";
		}
		else
		{
			echo "                     	<td class=\"gray\" align=\"right\"><b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\"></td>\n";
		}
	}
	else
	{
		echo "<td class=\"gray\" align=\"right\"><img src=\"images/pixel.gif\"></td>";
	}
	
	echo "							<td class=\"gray\" align=\"right\">\n";
	echo "								<input class=\"checkbox\" type=\"image\" src=\"images/save.gif\" alt=\"Create Quote\">\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "               		<tr>\n";
	echo "							<td class=\"gray\" align=\"left\"><b>Customer</b> ".stripslashes($rowN['cfname'])." ".stripslashes($rowN['clname'])."</td>\n";
	echo "							<td class=\"gray\" colspan=\"2\" align=\"right\"><b>SalesRep</b> ".$rowM['fname']." ".$rowM['lname']."</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td colspan=\"3\" align=\"left\">\n";
	echo "					<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "                     		<td class=\"gray\" colspan=\"12\" align=\"left\"><b>Pool Dimensions</b></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "							<td class=\"gray\" align=\"right\">\n";

	//echo $_SESSION['defmeas'];

	if ($rowpre2['pft_sqft']=="p")
	{
		echo "									<b>Perimeter</b>\n";
	}
	else
	{
		echo "									<b>Surface Area</b>\n";
	}

	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";

	//if ($rowAa['quan1t'] > 0)
	if ($nrowA==0)
	{
		if ($rowpre2['pft_sqft']=="p")
		{
			echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$rowpre2['def_per']."\"></td>\n";
		}
		else
		{
			echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$rowpre2['def_sqft']."\"></td>\n";
		}
	}
	else
	{
		if ($rowpre2['pft_sqft']=="p")
		{
			echo "                        	<select name=\"ps1\">\n";
		}
		else
		{
			echo "                        	<select name=\"ps2\">\n";
		}

		while($rowA = mssql_fetch_array($resA))
		{
			if ($rowA['quan']==$defmeas)
			{
				echo "                        		<option value=\"".$rowA['quan']."\" SELECTED>".$rowA['quan']."</option>\n";
			}
			else
			{
				echo "                        		<option value=\"".$rowA['quan']."\">".$rowA['quan']."</option>\n";
			}
		}

		echo "                                          </select>\n";
	}
	
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\">\n";

	if ($rowpre2['pft_sqft']=="p")
	{
		echo "									<b>Surface Area</b>\n";
	}
	else
	{
		echo "									<b>Perimeter</b>\n";
	}

	echo "															</td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";

	if ($rowpre2['pft_sqft']=="p")
	{
		echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$rowpre2['def_sqft']."\"></td>\n";
	}
	else
	{
		echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$rowpre2['def_per']."\"></td>\n";
	}

	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Depths</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";
	echo "                                          <input class=\"bboxbc\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$rowpre2['def_s']."\">\n";
	echo "                                          <input class=\"bboxbc\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"".$rowpre2['def_m']."\">\n";
	echo "                                          <input class=\"bboxbc\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"".$rowpre2['def_d']."\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Electrical Run</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"bboxbc\" type=\"text\" name=\"erun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Plumbing Run</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"bboxbc\" type=\"text\" name=\"prun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Total Deck</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"bboxbc\" type=\"text\" name=\"deck\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td>\n";
	echo "						<table class=\"outer\" border=\"0\" width=\"100%\" height=\"40px\">\n";
	echo "							<tr>\n";
	echo "								<td class=\"gray\" colspan=\"5\" align=\"left\"><b>Spa Dimensions</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "							<td class=\"gray\" align=\"left\">\n";
	echo "								<select name=\"spa1\">\n";

	while($rowD = mssql_fetch_array($resD))
	{
		echo "                                    <option value=\"".$rowD['typeid']."\">".$rowD['name']."</option>\n\n";
	}

	echo "								</select>\n";
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Spa Perimeter</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\">\n";
	echo "								<input class=\"bboxbc\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Spa Surface Area</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\">\n";
	echo "								<input class=\"bboxbc\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "				<td>\n";
	echo "					<table class=\"outer\" border=\"0\" width=\"100%\" height=\"40px\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Referral</b></td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"center\">\n";
	echo "								<input class=\"bboxbl\" type=\"text\" name=\"refto\" size=\"50\">\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "   <tr>\n";
	echo "      <td colspan=\"3\" align=\"left\">\n";
	echo "         <table border=\"0\" width=\"100%\">\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"3\" align=\"left\">\n";
	echo "               	<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "               		<tr>\n";
	echo "							<td class=\"gray\">\n";
	echo "		<table class=\"outer\" width=\"100%\" border=0>\n";
	
	if (isset($_SESSION['pricebookdata']))
	{
		$pbdata_ar=json_decode($_SESSION['pricebookdata'],true);
		
		if ($pbdata_ar[array_rand($pbdata_ar)][2][0][2]!=$_SESSION['officeid'])
		{
			load_pricebook_data();
			$pbdata_ar=json_decode($_SESSION['pricebookdata'],true);
		}

		echo "	<tr>\n";
		echo "		<td>\n";
		echo "			<table cellspacing=0 border=0 width=\"100%\">\n";

		if (is_array($pbdata_ar))
		{
			$qryB = "SELECT id,catid,name FROM AC_Cats WHERE officeid='".$_SESSION['officeid']."' and active=1 and privcat=1;";
			$resB = mssql_query($qryB);
			$nrowB= mssql_num_rows($resB);
			
			if ($nrowB > 0)
			{
				while($rowB = mssql_fetch_array($resB))
				{
					$qryC = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled FROM [".$_SESSION['pb_code']."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid=".$rowB['catid']." AND phsid=".$_SESSION['securityid']." AND disabled=0 ORDER BY seqn;";
					$resC = mssql_query($qryC);
					$nrowC= mssql_num_rows($resC);
					
					if ($nrowC > 0)
					{
						//echo 'LOADing Pers PB <br>';
						$pbdata_ar[$rowB['catid']]=   array(
											0=>	$rowB['catid'],
											1=>	$rowB['name']
											);
							
						while($rowC = mssql_fetch_array($resC))
						{
							$pbdata_ar[$rowB['catid']][2][]=   array(
														$rowC['id'],
														$rowC['aid'],
														$rowC['officeid'],
														$rowC['item'],
														$rowC['accpbook'],
														$rowC['qtype'],
														$rowC['seqn'],
														$rowC['rp'],
														$rowC['bp'],
														$rowC['spaitem'],
														$rowC['mtype'],
														$rowC['atrib1'],
														$rowC['atrib2'],
														$rowC['atrib3'],
														$rowC['quan_calc'],
														$rowC['commtype'],
														$rowC['crate'],
														$rowC['disabled']
														);
						}
					}
				}
				//echo "<pre>";
				//print_r($pbdata_ar);
				//echo "</pre>";
			}
			
			$ecnt=1;
			echo "				<tr>\n";
			echo "					<td class=\"gray\" valign=\"top\">\n";
			//echo 'IN<br>';	
			foreach ($pbdata_ar as $n=>$v)
			{
				if ($v[0]!=0)
				{
					if ($ecnt==count($pbdata_ar))
					{
						echo "<a href=\"#".trim($v[0])."\">".trim($v[1])."</a>";
					}
					else
					{
						echo "<a href=\"#".trim($v[0])."\">".trim($v[1])."</a> - ";
					}
					$ecnt++;
				}
			}
			
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "  		</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td valign=\"top\">\n";
			echo "			<table cellspacing=0 border=0 width=\"100%\">\n";
			
			foreach ($pbdata_ar as $no => $vo)
			{
				if ($vo[0]!=0)
				{
					echo "				<tr>\n";
					echo "					<td class=\"wh_und\" align=\"left\" colspan=\"4\" valign=\"top\">\n";
					echo "						<input type=\"hidden\" name=\"#".trim($vo[0])."\"><b>".trim($vo[1])."</b>\n";
					echo "					</td>\n";
					echo "					<td class=\"wh_und\" align=\"right\"><a href=\"#Top\"><img style=\"border:white\" src=\"../images/scrollup.gif\" alt=\"to Top\"></a></td>\n";
					echo "				</tr>\n";
	
					foreach($vo[2] as $pn => $pv)
					{
						if ($pv[5]==32)
						{
							echo "				<tr>\n";
							echo "					<td class=\"drkgray\" colspan=\"5\">\n";
							echo "						<font color=\"white\"><b>".trim($pv[3])."</b></font>\n";
							echo "					</td>\n";
							echo "				</tr>\n";
							echo "              <tr>\n";
							echo "					<td class=\"ltgray_und\" valign=\"top\" align=\"left\" colspan=\"5\">\n";
		
							showdescrip_hdratribs(trim($pv[11]),trim($pv[12]),trim($pv[13]));
	
							echo "					</td>\n";
							echo "				</tr>\n";
						}

						form_element_ACC_quote(trim($pv[0]),trim($pv[1]),trim($pv[2]),
										 trim($pv[3]),trim($pv[4]),trim($pv[5]),
										 trim($pv[6]),trim($pv[7]),trim($pv[8]),
										 trim($pv[9]),trim($pv[10]),trim($pv[11]),
										 trim($pv[12]),trim($pv[13]),trim($pv[14]),
										 trim($pv[15]),trim($pv[16]),trim($pv[17]),
										 trim($rowA['estdata']));
					}
				}
			}
	
			echo "  		</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
	}
	
	echo "		</table>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	echo "</form>\n";
}

function new_estimate()
{
	$MAS		=$_SESSION['pb_code'];
	$officeid	=$_SESSION['officeid'];
	$secid		=$_SESSION['securityid'];

	if (empty($_REQUEST['cid']))
	{
		//echo "<b>Error Occured! Click <a href=\"".$_SERVER['PHP_SELF']."?action=est&call=new\">HERE</a> to enter a Customer.</b>\n";
		echo "<b>Transition Error Occured!</b>\n";
		exit;
	}
	
	//autoadditems();

	$qrypre2 = "SELECT officeid,name,def_per,def_sqft,def_s,def_m,def_d,pft_sqft,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2 = mssql_query($qrypre2);
	$rowpre2 = mssql_fetch_array($respre2);

	$qryA = "SELECT quan FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA.'<br>';

	$qryAa = "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);

	$qryD = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC";
	$resD = mssql_query($qryD);

	$qryM  = "SELECT securityid,fname,lname,sidm,rmasid FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_REQUEST['estorig']."';";
	$resM  = mssql_query($qryM);
	$rowM  = mssql_fetch_array($resM);

	//$qryN  = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['cid']."';";
	$qryN  = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
	$resN  = mssql_query($qryN);
	$rowN  = mssql_fetch_array($resN);
	
	$uid  =md5(session_id().time().$rowN['cid']).".".$_SESSION['securityid'];

	if ($rowpre2['pft_sqft']=="p")
	{
		$defmeas=$rowpre2['def_per'];
	}
	else
	{
		$defmeas=$rowpre2['def_sqft'];
	}

	echo "<form name=\"saveest\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"saveest\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowM['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowM['sidm']."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$rowN['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"showdetail\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"tzone\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_quote_func.js\"></script>\n";

	echo "<div id=\"masterdiv\">\n";
	echo "<table align=\"center\" width=\"750px\" border=0>\n";
	echo "   <tr>\n";
	echo "      <td colspan=\"3\" align=\"left\">\n";
	echo "         <table border=\"0\" width=\"100%\">\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"3\" align=\"left\">\n";
	echo "               	<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "               		<tr>\n";
	echo "							<td class=\"gray\" align=\"left\" NOWRAP><b>Blue Haven Pools & Spas Estimate</b></td>\n";
	
	if ($rowM[4]!=0)
	{
		if ($rowN['stage']==17)
		{
			echo "                     	<td class=\"gray\" align=\"right\"><b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\" CHECKED></td>\n";
		}
		else
		{
			echo "                     	<td class=\"gray\" align=\"right\"><b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\"></td>\n";
		}
	}
	else
	{
		echo "<td class=\"gray\" align=\"right\"><img src=\"images/pixel.gif\"></td>";
	}
	
	echo "							<td class=\"gray\" align=\"right\">\n";
	echo "								<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Create Estimate\">\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "               		<tr>\n";
	echo "							<td class=\"gray\" align=\"left\"><b>Customer</b> ".stripslashes($rowN['cfname'])." ".stripslashes($rowN['clname'])."</td>\n";
	echo "							<td class=\"gray\" colspan=\"2\" align=\"right\"><b>SalesRep</b> ".$rowM['fname']." ".$rowM['lname']."</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td colspan=\"3\" align=\"left\">\n";
	echo "					<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "                     		<td class=\"gray\" colspan=\"12\" align=\"left\"><b>Pool Dimensions</b></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "							<td class=\"gray\" align=\"right\">\n";

	//echo $_SESSION['defmeas'];

	if ($rowpre2['pft_sqft']=="p")
	{
		echo "									<b>Perimeter</b>\n";
	}
	else
	{
		echo "									<b>Surface Area</b>\n";
	}

	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";

	//if ($rowAa['quan1t'] > 0)
	if ($nrowA==0)
	{
		if ($rowpre2['pft_sqft']=="p")
		{
			echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$rowpre2['def_per']."\"></td>\n";
		}
		else
		{
			echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$rowpre2['def_sqft']."\"></td>\n";
		}
	}
	else
	{
		if ($rowpre2['pft_sqft']=="p")
		{
			echo "                        	<select name=\"ps1\">\n";
		}
		else
		{
			echo "                        	<select name=\"ps2\">\n";
		}

		while($rowA = mssql_fetch_array($resA))
		{
			if ($rowA['quan']==$defmeas)
			{
				echo "                        		<option value=\"".$rowA['quan']."\" SELECTED>".$rowA['quan']."</option>\n";
			}
			else
			{
				echo "                        		<option value=\"".$rowA['quan']."\">".$rowA['quan']."</option>\n";
			}
		}

		echo "                                          </select>\n";
	}
	
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\">\n";

	if ($rowpre2['pft_sqft']=="p")
	{
		echo "									<b>Surface Area</b>\n";
	}
	else
	{
		echo "									<b>Perimeter</b>\n";
	}

	echo "															</td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";

	if ($rowpre2['pft_sqft']=="p")
	{
		echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$rowpre2['def_sqft']."\"></td>\n";
	}
	else
	{
		echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$rowpre2['def_per']."\"></td>\n";
	}

	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Depths</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";
	echo "                                          <input class=\"bboxbc\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$rowpre2['def_s']."\">\n";
	echo "                                          <input class=\"bboxbc\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"".$rowpre2['def_m']."\">\n";
	echo "                                          <input class=\"bboxbc\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"".$rowpre2['def_d']."\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Electrical Run</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"bboxbc\" type=\"text\" name=\"erun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Plumbing Run</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"bboxbc\" type=\"text\" name=\"prun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Total Deck</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"bboxbc\" type=\"text\" name=\"deck\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td>\n";
	echo "						<table class=\"outer\" border=\"0\" width=\"100%\" height=\"40px\">\n";
	echo "							<tr>\n";
	echo "								<td class=\"gray\" colspan=\"5\" align=\"left\"><b>Spa Dimensions</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "							<td class=\"gray\" align=\"left\">\n";
	echo "								<select name=\"spa1\">\n";

	while($rowD = mssql_fetch_array($resD))
	{
		echo "                                    <option value=\"".$rowD['typeid']."\">".$rowD['name']."</option>\n\n";
	}

	echo "								</select>\n";
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Spa Perimeter</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\">\n";
	echo "								<input class=\"bboxbc\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Spa Surface Area</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\">\n";
	echo "								<input class=\"bboxbc\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "				<td>\n";
	echo "					<table class=\"outer\" border=\"0\" width=\"100%\" height=\"40px\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Referral</b></td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"center\">\n";
	echo "								<input class=\"bboxbl\" type=\"text\" name=\"refto\" size=\"50\">\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";	
	echo "</table>\n";
	echo "</div>\n";
	echo "</form>\n";
}

function matrix0()
{
	$MAS		=$_SESSION['pb_code'];
	$officeid	=$_SESSION['officeid'];
	$secid		=$_SESSION['securityid'];

	if (empty($_REQUEST['uid'])||empty($_REQUEST['cid']))
	{
		//echo "<b>Error Occured! Click <a href=\"".$_SERVER['PHP_SELF']."?action=est&call=new\">HERE</a> to enter a Customer.</b>\n";
		echo "<b>Transition Error Occured!</b>\n";
		exit;
	}

	$qrypre2 = "SELECT officeid,name,def_per,def_sqft,def_s,def_m,def_d,pft_sqft,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2 = mssql_query($qrypre2);
	$rowpre2 = mssql_fetch_row($respre2);

	// Builds a list of exisiting categories in the retail accessory table by office
	$qrypre3  = "SELECT DISTINCT a.catid,a.seqn ";
	$qrypre3 .= "FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
	$qrypre3 .= "ON a.catid=b.catid ";
	$qrypre3 .= "AND a.officeid='".$officeid."' ";
	$qrypre3 .= "AND a.active=1 ";
	$qrypre3 .= "ORDER BY a.seqn ASC;";
	$respre3 = mssql_query($qrypre3);

	//echo $qrypre3."<br>";

	while ($rowpre3 = mssql_fetch_row($respre3))
	{
		$catarray[]=$rowpre3[0];
	}

	$qryA = "SELECT quan FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC";
	$resA = mssql_query($qryA);

	$qryAa = "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);

	$qryB = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum";
	$resB = mssql_query($qryB);

	$qryC = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype='M' AND costing=1 ORDER BY seqnum";
	$resC = mssql_query($qryC);

	$qryD = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC";
	$resD = mssql_query($qryD);

	//$qryE = "SELECT zid,name FROM zoneinfo ORDER BY zid ASC";
	//$resE = mssql_query($qryE);

	$qryF  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!=1 AND spaitem!=1";
	$resF  = mssql_query($qryF);
	$nrowF = mssql_num_rows($resF);

	$qryH  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!=1 AND spaitem!=1 AND phsid=0 ORDER BY seqn ASC";
	$resH  = mssql_query($qryH);
	$nrowH = mssql_num_rows($resH);

	$qryI  = "SELECT id,phsid FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!=1 AND spaitem!=1 AND phsid!=0 ORDER BY seqn ASC";
	$resI  = mssql_query($qryI);
	$nrowI = mssql_num_rows($resI);

	$qryG  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND spaitem=1";
	$resG  = mssql_query($qryG);
	$nrowG = mssql_num_rows($resG);

	$qryK  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND spaitem=1 AND phsid=0 ORDER BY seqn ASC";
	$resK  = mssql_query($qryK);
	$nrowK = mssql_num_rows($resK);

	$qryL  = "SELECT id,phsid FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND spaitem=1 AND phsid!=0 ORDER BY seqn ASC";
	$resL  = mssql_query($qryL);
	$nrowL = mssql_num_rows($resL);

	$qryM  = "SELECT securityid,fname,lname,sidm,rmasid FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_REQUEST['estorig']."';";
	$resM  = mssql_query($qryM);
	$rowM  = mssql_fetch_row($resM);

	//$qryN  = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['cid']."';";
	$qryN  = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
	$resN  = mssql_query($qryN);
	$rowN  = mssql_fetch_array($resN);

	if ($rowpre2[7]=="p")
	{
		$defmeas=$rowpre2[2];
	}
	else
	{
		$defmeas=$rowpre2[3];
	}

	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"matrix1\">\n";
	//echo "<input type=\"hidden\" name=\"call\" value=\"saveest\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowM[0]."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowM[3]."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$rowN['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"contractamt\" value=\"0.00\">\n";
	echo "<input type=\"hidden\" name=\"showdetail\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"tzone\" value=\"0\">\n";

	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table align=\"center\" class=\"outer\" width=\"800px\" border=0>\n";
	echo "   <tr>\n";
	echo "      <td colspan=\"3\" align=\"left\" class=\"gray\">\n";
	echo "         <table border=\"0\" width=\"100%\">\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"3\" align=\"left\">\n";
	echo "               	<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "               		<tr>\n";
	echo "                  		<td valign=\"bottom\" align=\"left\"><b>Retail Estimate for ".$rowpre2[1]." Office</b></td>\n";
	echo "                     	<td valign=\"bottom\" align=\"left\"><b>Customer</b> ".stripslashes($rowN['cfname'])." ".stripslashes($rowN['clname'])."</b></td>\n";
	echo "                     	<td NOWRAP valign=\"bottom\" align=\"right\"><b>SalesRep</b> ".$rowM[1]." ".$rowM[2]."</td>\n";
	
	if ($rowM[4]!=0)
	{
		if ($rowN['stage']==17)
		{
			echo "                     	<td valign=\"bottom\" align=\"right\"><b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\" CHECKED></td>\n";
		}
		else
		{
			echo "                     	<td valign=\"bottom\" align=\"right\"><b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\"></td>\n";
		}
	}
	else
	{
		echo "<td valign=\"bottom\" align=\"right\">&nbsp</td>";
	}
	
	if (isset($_SESSION['demomode']) && $_SESSION['demomode']==1)
	{	
		echo "                     	<td valign=\"bottom\" align=\"right\"><b>Demo Mode</b> <input type=\"checkbox\" class=\"transnb\" name=\"demomode\" value=\"1\" alt=\"Check this box to prevent the commission display on the Retail Breakdown\" CHECKED></td>\n";
	}
	else
	{
		echo "                     	<td valign=\"bottom\" align=\"right\"><b>Demo Mode</b> <input type=\"checkbox\" class=\"transnb\" name=\"demomode\" value=\"1\" alt=\"Check this box to prevent the commission display on the Retail Breakdown\"></td>\n";
	}
	
	echo "                     	<td NOWRAP valign=\"bottom\" align=\"right\">\n";
	echo "                     		<input class=\"buttondkgry\" type=\"submit\" value=\"Estimate\">\n";
	echo "                     	</td>\n";
	echo "                  	</tr>\n";
	echo "               	</table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"3\" align=\"left\">\n";
	echo "               	<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                  	<tr>\n";
	echo "                     	<td colspan=\"4\" align=\"left\" valign=\"bottom\"><b>POOL DIMENSIONS</b></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                     	<td align=\"right\" valign=\"bottom\">\n";

	//echo $_SESSION['defmeas'];

	if ($rowpre2[7]=="p")
	{
		echo "									<b>Perimeter</b>\n";
	}
	else
	{
		echo "									<b>Surface Area</b>\n";
	}

	echo "								</td>\n";
	echo "                     	<td align=\"left\" valign=\"bottom\">\n";

	if ($rowAa['quan1t'] > 0)
	{
		if ($rowpre2[7]=="p")
		{
			echo "                                            <input class=\"bboxl\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[2]\"></td>\n";
		}
		else
		{
			echo "                                            <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[3]\"></td>\n";
		}
	}
	else
	{
		if ($rowpre2[7]=="p")
		{
			echo "                        	<select name=\"ps1\">\n";
		}
		else
		{
			echo "                        	<select name=\"ps2\">\n";
		}

		while($rowA = mssql_fetch_row($resA))
		{
			if ($rowA[0]==$defmeas)
			{
				echo "                        		<option value=\"$rowA[0]\" SELECTED>$rowA[0]</option>\n";
			}
			else
			{
				echo "                        		<option value=\"$rowA[0]\">$rowA[0]</option>\n";
			}
		}

		echo "                                          </select>\n";
	}
	echo "                                       </td>\n";
	echo "                                            <td align=\"right\" valign=\"bottom\">\n";

	if ($rowpre2[7]=="p")
	{
		echo "									<b>Surface Area</b>\n";
	}
	else
	{
		echo "									<b>Perimeter</b>\n";
	}

	echo "															</td>\n";
	echo "                                            <td align=\"left\" valign=\"bottom\">\n";

	if ($rowpre2[7]=="p")
	{
		echo "                                            <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[3]\"></td>\n";
	}
	else
	{
		echo "                                            <input class=\"bboxl\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[2]\"></td>\n";
	}

	echo "                                       </td>\n";
	echo "                                            <td align=\"right\" valign=\"bottom\"><b>Depth</b></td>\n";
	echo "                                            <td align=\"left\" valign=\"bottom\">\n";
	echo "                                            <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[4]\">\n";
	echo "                                          <input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[5]\">\n";
	echo "                                          <input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[6]\">\n";
	echo "                                       </td>\n";
	echo "                                            <td align=\"right\" valign=\"bottom\"><b>Electrical Run</b></td>\n";
	echo "                                            <td align=\"left\" valign=\"bottom\">\n";
	echo "                                          <input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "                                       </td>\n";
	echo "                                            <td align=\"right\" valign=\"bottom\"><b>Plumbing Run</b></td>\n";
	echo "                                            <td align=\"left\" valign=\"bottom\">\n";
	echo "                                          <input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "                                       </td>\n";
	echo "                                            <td align=\"right\" valign=\"bottom\"><b>Total Deck</b></td>\n";
	echo "                                            <td align=\"left\" valign=\"bottom\">\n";
	echo "                                          <input class=\"bboxl\" type=\"text\" name=\"deck\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "                                       </td>\n";
	echo "                          </tr>\n";
	echo "                       </table>\n";
	echo "                    </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\">\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\" height=\"40px\">\n";
	echo "                      <tr>\n";
	echo "                    <td colspan=\"5\" align=\"left\" valign=\"bottom\"><b>SPA DIMENSIONS</b></td>\n";
	echo "                 </tr>\n";
	echo "            <tr>\n";
	echo "                         <td align=\"left\" valign=\"bottom\">\n";
	echo "                            <select name=\"spa1\">\n";

	while($rowD = mssql_fetch_row($resD))
	{
		echo "                                    <option value=\"".$rowD[0]."\">".$rowD[1]."</option>\n\n";
	}

	echo "                            </select>\n";
	echo "                    </td>\n";
	echo "                    <td align=\"right\" valign=\"bottom\"><b>Spa Perimeter</b></td>\n";
	echo "                    <td align=\"left\" valign=\"bottom\">\n";
	echo "                         <input class=\"bboxl\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "                    </td>\n";
	echo "                    <td align=\"right\" valign=\"bottom\"><b>Spa Surface Area</b></td>\n";
	echo "                    <td align=\"left\" valign=\"bottom\">\n";
	echo "                  <input class=\"bboxl\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td class=\"gray\">\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\" height=\"40px\">\n";
	echo "         	<tr>\n";
	echo "            	<td align=\"left\" valign=\"bottom\"><b>REFERRAL</b></td>\n";
	echo "           	</tr>\n";
	echo "            <tr>\n";
	echo "            	<td align=\"right\" valign=\"bottom\">To:</td>\n";
	echo "            	<td align=\"left\" valign=\"bottom\">\n";
	echo "                  <input class=\"bboxl\" type=\"text\" name=\"refto\" size=\"25\">\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td class=\"gray\">\n";
	
	/*
	if ($rowpre2[8]!=0)
	{
		echo "         <table class=\"outer\" border=\"0\" width=\"100%\" height=\"40px\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"left\" valign=\"bottom\"><b>FINANCING</b></td>\n";
		echo "            </tr>\n";
		echo "				<tr>\n";
		echo "					<td align=\"right\" valign=\"bottom\">\n";
		echo "                  <select name=\"finan\">\n";
		echo "                     <option value=\"0\"></option>\n";
		echo "                     <option value=\"1\">Winners</option>\n";
		echo "                     <option value=\"2\">Cust Finan</option>\n";
		echo "                     <option value=\"3\">Cash</option>\n";
		echo "                  </select>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
	}
	*/
	echo "      </td>\n";
	echo "   </tr>\n";

	if ($nrowF > 0||$nrowG > 0)
	{
		echo "   <tr>\n";
		echo "      <td colspan=\"3\" class=\"gray\" align=\"left\" valign=\"top\" width=\"100%\">\n";
		//echo "<pre>";
		//print_r($catarray);
		//echo "</pre>";
		echo "         <table border=0 class=\"inner_borders\" width=\"100%\">\n";
		echo "         <tr>\n";
		echo "            <td class=\"wh\" colspan=\"5\" valign=\"top\">\n";

		$ecnt=1;
		foreach ($catarray as $n=>$v)
		{
			$qryJ = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."';";
			$resJ = mssql_query($qryJ);
			$rowJ = mssql_fetch_row($resJ);

			if ($rowJ[0]!=0)
			{
				if ($ecnt==count($catarray))
				{
					echo "<a href=\"#$rowJ[0]\">$rowJ[1]</a>";
				}
				else
				{
					echo "<a href=\"#$rowJ[0]\">$rowJ[1]</a> - ";
				}
				$ecnt++;
			}
		}

		echo "            </td>\n";
		echo "         </tr>\n";

		// POOL RETAIL ACC ITEM Loop
		foreach ($catarray as $n=>$v)
		{
			$qryJ = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."';";
			$resJ = mssql_query($qryJ);
			$rowJ = mssql_fetch_row($resJ);

			if ($v!=0)
			{
				echo "         <tr>\n";
				echo "            <td class=\"wh\" align=\"left\" valign=\"top\">\n";
				echo "                                        <input type=\"hidden\" name=\"#$rowJ[0]\"><b>$rowJ[1]</b>\n";
				echo "                                </td>\n";
				echo "            <td class=\"wh\" align=\"right\" valign=\"top\">&nbsp<a href=\"#Top\"><img style=\"border:white\" src=\"images/scrollup.gif\" alt=\"to Top\"></a></td>\n";
				echo "         </tr>\n";
				//echo "         <tr>\n";
				//echo "            <td class=\"gray\" colspan=\"5\" valign=\"top\">\n";

				$qryM  = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."' AND disabled!='1' ORDER BY seqn;";
				$resM  = mssql_query($qryM);
				$nrowM = mssql_num_rows($resM);

				$qcnt=0;

				while ($rowM=mssql_fetch_row($resM))
				{
					$qcnt++;

					echo "         <tr>\n";
					echo "            <td class=\"gray\" colspan=\"5\" valign=\"top\">\n";
					
					if ($qcnt==1)
					{
						form_element_ACC($rowM[0],1,0,0);
					}
					elseif ($qcnt==$nrowM)
					{
						form_element_ACC($rowM[0],2,0,0);
					}
					else
					{
						form_element_ACC($rowM[0],0,0,0);
					}
					
					echo "                 </td>\n";
					echo "         </tr>\n";
				}

				//echo "                 </td>\n";
				//echo "         </tr>\n";
			}
		}

		echo "                           <tr>\n";
		echo "                                        <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\">\n";
		echo "                                           <input class=\"buttondkgry\" type=\"submit\" value=\"Estimate\">\n";
		echo "                                   </td>\n";
		echo "                           </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
	}
	echo "</table>\n";
	echo "</div>\n";
	echo "</form>\n";
}

function viewest_retail()
{
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	unset($viewarray);
	unset($_SESSION['estbidretail']);
	unset($_SESSION['demomode']);
	
	$MAS		=$_SESSION['pb_code'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	$_SESSION['aid']=aidbuilder($_SESSION['jlev'],"j");
	$acclist	=explode(",",$_SESSION['aid']);
	
	//
	if (isset($_REQUEST['estid']) && $_REQUEST['estid']!=0)
	{
		$estid		=$_REQUEST['estid'];
	}
	else
	{
		$estid		=$_SESSION['estid'];
	}
	
	$jobid		=0;

	if (!isset($estid) || $estid==0 || $estid=='')
	{
		die("Fatal Error: Estimate ID (".$estid.") not set!");
	}

	$qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,comadj,sidm,buladj,applyov,applybu,refto,apft,added,updated,updateby,comm,renov,esttype,ccid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_row($respreA);
	
	//echo $qrypreA.'<br>';
	
	$qrypreAa = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND estid='".$rowpreA[0]."';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreB = "SELECT securityid,sidm FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowpreA[17]."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_row($respreB);
	
	/*if (!in_array($_SESSION['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>You do not have approriate Access to view this Estimate</b>";
		exit;
	}*/

	$viewarray=array(
	'estid'=>	$rowpreA[0],
	'jobid'=>	'0',
	'njobid'=>	'0',
	'ps1'=>		$rowpreA[1],
	'ps2'=>		$rowpreA[2],
	'spa1'=>	$rowpreA[3],
	'spa2'=>	$rowpreA[4],
	'spa3'=>	$rowpreA[5],
	'tzone'=>	$rowpreA[6],
	'camt'=>	$rowpreA[7],
	'comt'=>	0,
	'cfname'=>	$rowpreAa['cfname'],
	'clname'=>	$rowpreAa['clname'],
	'phone'=>	$rowpreA[10],
	'status'=>	$rowpreA[11],
	'ps5'=>		$rowpreA[13],
	'ps6'=>		$rowpreA[14],
	'ps7'=>		$rowpreA[15],
	'cid'=>		$rowpreA[35],
	'estsecid'=>$rowpreA[17],
	'deck'=>	$rowpreA[18],
	'erun'=>	$rowpreA[19],
	'prun'=>	$rowpreA[20],
	'jobid'=>	$rowpreA[21],
	'comadj'=>	$rowpreA[22],
	'dbocomm'=>	$rowpreA[32],
	'sidm'=>	$rowpreA[23],
	'buladj'=>	$rowpreA[24],
	'applyov'=>	$rowpreA[25],
	'applybu'=>	$rowpreA[26],
	'refto'=>	$rowpreA[27],
	'ps1a'=>	$rowpreA[28],
	'jadd'=>	0,
	'mjadd'=>	0,
	'custallow'=>0,
	'renov'=>	$rowpreA[33],
	'esttype'=>	$rowpreA[34],
	'discount'=>0,
	'royrel'=>	0,
	'allowdel'=>0,
	'tcomm'=>	0,
	'comsched'=>array()
	);

	$qrypreD = "SELECT * FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);

	$r_estdata = $rowpreD['estdata'];

	$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft,encost,enest FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[10]!=1)
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>Estimating has been disabled in ".$rowC[1]."</b>";
		exit;
	}

	if ($rowC[8]=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

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

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$viewarray['cid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_row($resL);

	if ($rowpreA[31]!=0)
	{
		$qryM = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowpreA[31]."';";
		$resM = mssql_query($qryM);
		$rowM = mssql_fetch_array($resM);

		$lupdatestr=$rowM['fname']." ".$rowM['lname'];
	}
	else
	{
		$lupdatestr="";
	}

	$qryN = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND srep=1 ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$resN = mssql_query($qryN);

	$qryP = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$resP = mssql_query($qryP);

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}
	
	$qryO = "SELECT * FROM comm_adj_sched WHERE oid='".$_SESSION['officeid']."';";
	$resO = mssql_query($qryO);
	$nrowO= mssql_num_rows($resO);
	
	if ($nrowO > 0)
	{
		$comsched=array();
		while($rowO = mssql_fetch_array($resO))
		{
			$comsched[]=array('oid'=>$rowO['oid'],'type'=>$rowO['type'],'rate'=>$rowO['rate']);
		}
		
		$viewarray['comsched']=$comsched;
	}

	$_SESSION['viewarray']=$viewarray;
	$tbullets   	=0;
	$poolcomm_adj	=detect_package($r_estdata);
	$set_deck   	=deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    	=round($set_deck[0]);
	$set_ia     	=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals   	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$estidret   	=$rowpreA[0];
	$vdiscnt    	=$viewarray['camt'];	
	$bpset			=select_base_pool();
	$pbaseprice 	=$bpset[3];

	if ($poolcomm_adj >= 1)
	{
		$bcomm      =0;
	}
	else
	{
		$bcomm      =$bpset[4];
	}

	$uid			=md5(session_id().time().$rowI[10]).".".$_SESSION['securityid'];

	if (!empty($rowpreA[29]))
	{
		$atime=date("m/d/Y", strtotime($rowpreA[29]));
	}
	else
	{
		$atime="";
	}

	if (!empty($rowpreA[30]))
	{
		$utime=date("m/d/Y", strtotime($rowpreA[30]));
	}
	else
	{
		$utime="";
	}
	
	if (isset($viewarray['esttype']) && $viewarray['esttype']=='Q')
	{
		$etype='Quote';
	}
	else
	{
		$etype='Estimate';
	}

	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	$fbcomm		=number_format($bcomm, 2, '.', '');
	$ctramt		=$viewarray['camt'];
	$fctramt	=number_format($ctramt, 2, '.', '');

	echo "<script type=\"text/javascript\" src=\"js/jquery_quote_func_new.js\"></script>\n";
	echo "<table width=\"750px\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"left\">\n";
	echo "			<table cellspacing=0 align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"3\" valign=\"top\" align=\"left\" width=\"100%\">\n";
	echo "                  	<table width=\"100%\" class=\"outer\" border=0>\n";
	echo "                  	   <tr>\n";
	echo "								<td class=\"gray\" align=\"left\" NOWRAP><b>Blue Haven Pools & Spas ";
	
	echo $etype;
	
	echo "</b></td>\n";
	echo "								<td class=\"gray\" align=\"right\">\n";
	
	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}
	
	//Control Box Code
	echo "									<table border=0>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	echo "												<img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\">\n";
	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	echo "			<form name=\"CustInfo\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	echo "			<input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
	echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$rowI[10]."\">\n";
	echo "			<input class=\"checkbox JMStooltip\" type=\"image\" src=\"images/comments.png\" title=\"Customer OneSheet\">\n";
	echo "			</form>\n";

	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	
	if (isset($viewarray['esttype']) && $viewarray['esttype']=='E')
	{
		echo "			<form name=\"createcontract\" method=\"post\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"create_job\">\n";
		
		/*if ($rowC[2]==1)
		{
			echo "			<input type=\"hidden\" name=\"salestax\" value=\"".$frtax."\">\n";
		}*/
	
		if ($rowI[11]!=0 || $rowI[12]!=0)
		{
			echo "			<input class=\"checkbox JMStooltip\" type=\"image\" src=\"images/layout_add.png\" title=\"Create Contract\" DISABLED>\n";
		}
		else
		{
			echo "			<input class=\"checkbox JMStooltip\" type=\"image\" src=\"images/layout_add.png\" title=\"Create Contract\">\n";
		}
	
		echo "				</form>\n";
	}
	
	echo "											</td>\n";
	/*echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	
	if ($_SESSION['elev'] >= 6 || $_SESSION['clev'] >= 6 || $_SESSION['jlev'] >= 9999999999)
	{	
		echo "			<form name=\"viewcost\" method=\"post\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "         	<input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
		echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "			<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";

		if ($rowC[9]==1)
		{
			echo "			<input class=\"checkbox\" type=\"image\" src=\"images/database_table.png\" alt=\"View Cost\">\n";
		}
		
		echo "			</form>\n";
	}
	
	echo "											</td>\n";*/
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	
	if ($_SESSION['elev'] >= 1)
	{	
		echo "			<form name=\"deleteest\" method=\"POST\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"delete_est1\">\n";
		echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "			<input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";

		if ($rowI[11]!=0 || $rowI[12]!=0)
		{
			echo "			<input class=\"checkbox JMStooltip\" type=\"image\" src=\"images/layout_delete.png\" title=\"Delete $etype\" DISABLED>\n";
		}
		else
		{
			echo "			<input class=\"checkbox JMStooltip\" type=\"image\" src=\"images/layout_delete.png\" title=\"Delete $etype\">\n";
		}
		
		echo "			</form>\n";
	}
	
	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	
	echo "			<form name=\"mainupdate\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "         	<input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
	echo "			<input type=\"hidden\" name=\"securityid\" id=\"sid1\" value=\"".$viewarray['estsecid']."\">\n";
	echo "			<input type=\"hidden\" name=\"custid\" value=\"".$rowI[0]."\">\n";
	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$rowI[10]."\">\n";
	echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "			<input type=\"hidden\" name=\"discount\" value=\"".$vdiscnt."\">\n";
	echo "			<input type=\"hidden\" name=\"contractamt\" value=\"".$fctramt."\">\n";
	echo "			<input type=\"hidden\" name=\"spa1\" value=\"".$viewarray['spa1']."\">\n";
	echo "			<input type=\"hidden\" name=\"spa2\" value=\"".$viewarray['spa2']."\">\n";
	echo "			<input type=\"hidden\" name=\"spa3\" value=\"".$viewarray['spa3']."\">\n";
	echo "			<input type=\"hidden\" name=\"status\" value=\"".$viewarray['status']."\">\n";
	echo "			<input type=\"hidden\" name=\"qecnt\" id=\"ecnt\" value=\"1\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"update\">\n";
	echo "			<input class=\"transnb JMStooltip\" type=\"image\" src=\"images/save.gif\" title=\"Save Quote\" onClick=\"return SRChangeAlert('sid1','sid2','ecnt');\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	
	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}
	
	//End Control Box Code
	
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td valign=\"top\" align=\"left\" width=\"32%\">\n";

	// Begin Customer Display Info
	cinfo_display($viewarray['cid'],$rowC[2]);
	// End Customer Display

	echo "					</td>\n";
	echo "					<td valign=\"top\" align=\"right\" width=\"32%\">\n";
	
	// Begin Pool Detail Display
	pool_detail_display($viewarray['estid']);
	// End Pool Detail Display

	echo "					<td valign=\"top\" align=\"right\" width=\"32%\">\n";

	// Set System/Est Info Display
	echo "						<table width=\"100%\" class=\"outer\" height=\"150\" border=0>\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" valign=\"top\">\n";
	echo "								<table width=\"100%\" border=0>\n";
	echo "									<tr>\n";
	echo "										<td class=\"gray\" align=\"right\" width=\"50%\"><b>".$etype."</b></td>\n";
	echo "										<td class=\"gray\" align=\"left\">".$estidret."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td class=\"gray\" align=\"right\" width=\"50%\"><b>Office</b></td>\n";
	echo "										<td class=\"gray\" align=\"left\">".$rowC[1]."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td class=\"gray\" align=\"right\" width=\"50%\"><b>Sales Rep</b></td>\n";
	echo "										<td class=\"gray\" align=\"left\">\n";

	if ($_SESSION['jlev'] >= 4)
	{
		echo "                                               <select id=\"sid2\" name=\"securityid\">\n";
		while($rowN = mssql_fetch_row($resN))
		{
			if (in_array($rowN[0],$acclist))
			{
				$secl=explode(",",$rowN[3]);
				if ($secl[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}

				if ($viewarray['estsecid']==$rowN[0])
				{
					echo "                                               	<option value=\"".$rowN[0]."\" class=\"".$ostyle."\" SELECTED>".$rowN[1]." ".$rowN[2]."</option>";
				}
				else
				{
					echo "                                               	<option value=\"".$rowN[0]."\" class=\"".$ostyle."\">".$rowN[1]." ".$rowN[2]."</option>";
				}
			}
		}
		echo "                                               </select>\n";
	}
	else
	{
		echo $rowD[1]." ".$rowD[2]."\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$viewarray['estsecid']."\">\n";
	}

	echo "							</td>\n";
	echo "						</tr>\n";
	
	if ($viewarray['esttype']=='E')
	{
		echo "						<tr>\n";
		echo "							<td class=\"gray\" align=\"right\" width=\"50%\"><b>Manager</b></td>\n";
		echo "							<td class=\"gray\" align=\"left\">\n";
	
		if ($_SESSION['jlev'] >= 6)
		{
			echo "								<select name=\"sidm\">\n";
	
			while($rowP = mssql_fetch_row($resP))
			{
				$secl=explode(",",$rowP[3]);
				if ($secl[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}
	
				if ($viewarray['sidm']==$rowP[0])
				{
					echo "								<option value=\"".$rowP[0]."\" class=\"".$ostyle."\" SELECTED>".$rowP[1]." ".$rowP[2]."</option>\n";
				}
				else
				{
					echo "								<option value=\"".$rowP[0]."\" class=\"".$ostyle."\">".$rowP[1]." ".$rowP[2]."</option>\n";
				}
			}
			
			echo "								</select>\n";
		}
		else
		{
			echo $rowL[1]." ".$rowL[2]."\n";
			echo "<input type=\"hidden\" name=\"sidm\" value=\"".$viewarray['sidm']."\">\n";
		}
	
		echo "								</td>\n";
		echo "						</tr>\n";
		echo "						<tr>\n";
	
		/*if ($_SESSION['subq']=="print")
		{
			echo "<form method=\"post\">\n";
			echo "							<td class=\"gray\" align=\"right\">\n";
			echo "								<input class=\"buttondkgrypnl80\" type=\"button\" name=\"buttonPrint\" value=\"Print\" onClick=\"window.print()\">\n";
			echo "                     		</td>\n";
			echo "</form>\n";
		}*/
	
		echo "                     				</tr>\n";
		echo "                     				<tr>\n";
		echo "										<td class=\"gray\" align=\"right\"><b>Date Added</b></td>\n";
		echo "										<td class=\"gray\" align=\"left\">".$atime."</td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "										<td class=\"gray\" align=\"right\"><b>Date Updated</b></td>\n";
		echo "										<td class=\"gray\" align=\"left\">".$utime."</td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "										<td class=\"gray\" align=\"right\"><b>by</b></td>\n";
		echo "										<td class=\"gray\" align=\"left\">".$lupdatestr."</td>\n";
		/*echo "      <td valign=\"top\" align=\"left\">\n";
		echo "			<div onclick=\"SwitchMenu('ItemSelect')\"><img src=\"images/add.png\"></div>";
		echo "		</td>\n";*/
		echo "									</tr>\n";
	}
	else
	{
		echo "<input type=\"hidden\" name=\"sidm\" value=\"".$viewarray['sidm']."\">\n";
	}
	
	echo "								</table>\n";
	echo "							</td>\n";
	echo "						</table>\n";
	
	// End System/Est Info Display
	
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";	
	echo "	</tr>\n";
	echo "</form>\n";

	echo "	<tr>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	echo "         <table width=\"100%\">\n";
	echo "           <tr>\n";
	echo "              <td align=\"left\">\n";
	
	/*
	echo "			<div id=\"EstRetail\" class=\"yui-navset\">\n";
    echo "				<ul class=\"yui-nav\">\n";
	echo "					<li class=\"selected\"><a href=\"#rt\"><em>Quote Detail</em></a></li>\n";
	echo "			    	<li><a href=\"#pb\"><em>Pricebook</em></a></li>\n";
	echo "				</ul>\n";
	echo "				<div class=\"yui-content\">\n";
	*/
	
	echo "			<div id=\"EstRetail\">\n";
    echo "				<ul>\n";
	echo "					<li><a href=\"#rt\">Quote Detail</a></li>\n";
	echo "			    	<li><a href=\"#pb\">Pricebook</a></li>\n";
	echo "				</ul>\n";
    echo "				    <div id=\"rt\">\n";
	echo "						<p>\n";
	
	echo "           <form name=\"updateestitems\" method=\"post\">\n";
	echo "           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "         	 <input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
	echo "           <input type=\"hidden\" name=\"call\" value=\"update_acc\">\n";
	echo "           <input type=\"hidden\" name=\"estid\" value=\"".$_SESSION['estid']."\">\n";
	echo "         			<table cellspacing=0 width=\"100%\" border=0>\n";
	echo "           			<tr>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"left\" width=\"90\"><b>Category</b></td>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"left\"><b>Item</b></td>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"center\" width=\"30\"><b>Quan</b></td>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"center\" width=\"30\"><b>Units</b></td>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"center\" width=\"65\"><b>Price</b></td>\n";
	
	if ($viewarray['esttype']=='E')
	{
		echo "              			<td NOWRAP class=\"gray\" align=\"center\" width=\"55\"><b>Adjusts</b></td>\n";
	}
	
	if ($viewarray['esttype']=='Q')
	{	
		echo "              <td NOWRAP class=\"gray\" align=\"center\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
	}
	else
	{
		echo "              <td NOWRAP class=\"gray\" align=\"center\" width=\"55\"><div id=\"comm\"><b>Comm</b></div></td>\n";
	}
	
	echo "              <td NOWRAP class=\"gray\" valign=\"bottom\" align=\"center\" width=\"25\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	if ($viewarray['status'] >= 2)
	{
		echo "							 <input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Update Items\" DISABLED>\n";
	}
	else
	{
		echo "							 <input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Update Items\">\n";
	}

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "					</td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"ltgraylist\" align=\"left\" width=\"90\">Base</td>\n";
	echo "              <td NOWRAP class=\"ltgraylist\" align=\"left\"><b>Base Pool</b></td>\n";
	echo "              <td NOWRAP class=\"ltgraylist\" align=\"center\" width=\"30\">".$bpset[5]."</td>\n";
	echo "              <td NOWRAP class=\"ltgraylist\" align=\"center\" width=\"30\">".$bpset[6]."</td>\n";

	$qryBP = "SELECT * FROM base_price_adjusts WHERE oid=".$_SESSION['officeid']." AND estid=".$viewarray['estid'].";";
	$resBP = mssql_query($qryBP);
	$rowBP = mssql_fetch_array($resBP);
	$nrowBP= mssql_num_rows($resBP);
	
	$qryPP = "SELECT * FROM base_price_pad WHERE sid=".$viewarray['estsecid'].";";
	$resPP = mssql_query($qryPP);
	$rowPP = mssql_fetch_array($resPP);
	$nrowPP= mssql_num_rows($resPP);

	
	if (isset($viewarray['esttype']) && $viewarray['esttype']=='Q')
	{
		echo "              <td NOWRAP class=\"ltgraylist\" align=\"right\" width=\"65\">\n";
		
		if ($nrowBP != 0)
		{
			//echo '1<br>';
			$adj_bprice=$rowBP['adj_price'];
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$rowBP['ppb_price']."\">\n";
			echo "                  <input class=\"bboxnobrb\" type=\"text\" name=\"base_pl_src[1]\" value=\"".number_format($rowBP['adj_price'], 2, '.', '')."\" size=\"7\" title=\"This is an Adjusted Amount\">\n";
		}
		elseif ($nrowPP != 0 && $nrowBP == 0)
		{
			//echo '2<br>';
			$adj_bprice=$fpbaseprice+$rowPP['adj_price'];
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobrb\" type=\"text\" name=\"base_pl_src[1]\" value=\"".number_format(($fpbaseprice+$rowPP['adj_price']), 2, '.', '')."\" size=\"7\" title=\"This is an Adjusted Amount\">\n";
		}
		else
		{
			//echo '3<br>';
			$adj_bprice=$fpbaseprice;
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobr\" type=\"text\" name=\"base_pl_src[1]\" value=\"".$fpbaseprice."\" size=\"7\">\n";
		}
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"ltgraylist\" align=\"right\" width=\"55\"><div id=\"comm\"><img src=\"images/pixel.gif\"></div></td>\n";
	}
	elseif (isset($viewarray['esttype']) && $viewarray['esttype']=='E')
	{
		echo "              <td NOWRAP class=\"ltgraylist\" align=\"right\" width=\"65\">\n";
		
		if ($nrowBP != 0)
		{
			echo number_format($rowBP['ppb_price'], 2, '.', '');
		}
		else
		{
			echo $fpbaseprice;
		}
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"ltgraylist\" align=\"right\" width=\"55\">\n";
		echo "					<div id=\"adjts\">\n";
		
		if ($nrowBP != 0)
		{
			//echo '4<br>';
			$adj_bprice=$rowBP['ppb_price'] + $rowBP['var_price'];
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobrb\" type=\"text\" name=\"base_pl_src[1]\" value=\"".number_format($rowBP['var_price'], 2, '.', '')."\" size=\"7\" title=\"This is an Adjusted Amount\">\n";
		}
		elseif ($nrowPP != 0)
		{
			//echo '4<br>';
			$adj_bprice=$fpbaseprice + $rowPP['adj_price'];
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobrb\" type=\"text\" name=\"base_pl_src[1]\" value=\"".number_format($rowPP['adj_price'], 2, '.', '')."\" size=\"7\" title=\"This is an Adjusted Amount\">\n";
		}
		else
		{
			//echo '5<br>';
			$adj_bprice=$fpbaseprice;
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobr\" type=\"text\" name=\"base_pl_src[1]\" value=\"0.00\" size=\"7\">\n";
		}
		
		echo "					</div>\n";
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"ltgraylist\" align=\"right\" width=\"55\"><div id=\"comm\">".$fbcomm."</div></td>\n";
	}
	else
	{
		echo "              <td NOWRAP class=\"ltgraylist\" align=\"right\" width=\"65\">\n";
		
		//echo '4<br>';
		$adj_bprice=$fpbaseprice;
		echo $fpbaseprice;
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"ltgraylist\" align=\"right\" width=\"55\"><div id=\"comm\">".$fbcomm."</div></td>\n";
	}
	
	echo "              <td NOWRAP class=\"ltgraylist\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";

	//echo $r_estdata."<br>";
	calcbyacc($r_estdata,0);

	// Totals Table Calcs
	$bccost  =$bctotal;
	$rccost  =$rctotal;
	$cccost  =$cctotal;
	$bmcost  =$bmtotal;
	$rmcost  =$rmtotal;
	$trccost =$rccost+$rmcost;
	$cmcost  =$cmtotal;
	$tbcost  =$bccost+$bmcost;
	$trcost  =$adj_bprice+$trccost+$tbid;
	$tccost  =$cccost+$cmcost;
	$trcomm  =$bcomm+$tccost;
	$prof    =($trcost-$tbcost)-$trcomm;
	
	if ($prof!=0)
	{
		$perprof =$prof/$trcost;
	}
	else
	{
		$perprof =0;
	}

	if ($rowC[2]==1)
	{
		$rtax    =$ctramt*$taxrate[1];
		$grtcost =$ctramt+$rtax;
		$frtax   =number_format($rtax, 2, '.', '');
		$fgrtcost=number_format($grtcost, 2, '.', '');
	}

	$fbccost		=number_format($bccost, 2, '.', '');
	$fbmcost		=number_format($bmcost, 2, '.', '');
	$fcccost		=number_format($cccost, 2, '.', '');
	$frccost		=number_format($rccost, 2, '.', '');
	$frmcost		=number_format($rmcost, 2, '.', '');
	$fcmcost		=number_format($cmcost, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftrcost		=number_format($trcost, 2, '.', ',');
	$ftccost		=number_format($tccost, 2, '.', '');
	$ftrcomm		=number_format($trcomm, 2, '.', '');

	echo "           <tr>\n";
	
	if ($viewarray['esttype']=='Q')
	{
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Total Price</b></td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Price per Book</b></td>\n";
	}
	
	echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"65\"><strong>".$ftrcost."</strong></td>\n";
	
	if ($viewarray['esttype']=='Q')
	{	
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
	}
	else
	{
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"55\"><div id=\"comm\">".$ftrcomm."</div></td>\n";
	}
	
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	
	if (!isset($rowC[5])||!is_numeric($rowC[5]))
	{
		//echo "OBR: ". $rowC[5]."<br>";
		$bullet_rate=0;
	}
	else
	{
		$bullet_rate=$rowC[5];
	}

	$adjbookamt	=$trcost+$discount;
	$fadjbookamt=number_format($adjbookamt, 2, '.', '');

	if ($viewarray['renov']==1)
	{
		$adjctramt	=0;
	}
	else
	{
		$adjctramt	=$ctramt-$adjbookamt;
	}
	
	$fadjctramt	=number_format($adjctramt, 2, '.', '');

	$adjcomm		=0;

	$ou_out		=calc_ou($adjctramt,$adjcomm,$tbullets,$rowC[6],$viewarray['applyov'],$viewarray['comadj'],$bullet_rate,$rowC[7]);

	$foucomm	=number_format($ou_out[0], 2, '.', '');
	$fadjcomm	=number_format($ou_out[1], 2, '.', '');

	if ($viewarray['applyov']==1)
	{
		//$tadjcomm	=($trcomm + $t_sr_comadj_amt) + $fadjcomm;
		$tadjcomm	=$trcomm + $fadjcomm;
	}
	else
	{
		//$tadjcomm	=$trcomm + $t_sr_comadj_amt;
		$tadjcomm	=$trcomm;
	}

	// Set commission for global
	$viewarray['comt']	=$tadjcomm;
	$ftadjcomm			=number_format($tadjcomm, 2, '.', '');

	echo "</form>\n";
	
	if ($viewarray['esttype']=='E')
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Adjusted Book Price</b></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">".$fadjbookamt."</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		echo "<form name=\"setretail\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"update_contract_amt\">\n";
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Retail Contract Price</b></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">";
		echo "                 <input class=\"bboxnobr\" type=\"text\" name=\"c_amt\" size=\"6\" maxlength=\"10\" value=\"".$fctramt."\">";
		echo "              </td>";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\">";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}
	
		if ($viewarray['status'] >= 2)
		{
			echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Contract Price\" DISABLED>\n";
			//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
		}
		else
		{
			echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Contract Price\">\n";
			//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\">\n";
		}
	
		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "              </td>\n";
		echo "           </tr>\n";
		echo "</form>\n";
	}
	
	//Over/Under Contract Percentage
	$osplitperc=0;
	if ($viewarray['renov'] == 1)
	{
		$osplitperc=0;
	}
	else
	{
		if (isset($fctramt) && $fctramt!=0)
		{
			$osplitperc=round(($fadjctramt/$fctramt)*100);
		}
		else
		{
			$osplitperc=0;
		}
	}
	
	if ($viewarray['esttype']=='E')
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Overage/<font color=\"red\">Underage</font></b></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\">";
		
		if ($osplitperc < 0)
		{
			echo "<font color=\"red\">".$osplitperc."%</font>\n";
		}
		else
		{
			echo $osplitperc."%";
		}
		
		echo "</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\">".($rowC[7] * 100)."%</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">";
		
		if ($adjctramt < 0)
		{
			echo "<font color=\"red\">".$fadjctramt."</font>\n";
		}
		else
		{
			echo $fadjctramt;
		}
		
		echo "</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\">";
		
		if ($viewarray['applyov']==1)
		{
			echo "<img src=\"images/pixel.gif\">\n";
		}
		else
		{
			if ($foucomm < 0)
			{
				echo "<font color=\"red\">".$foucomm."</font>\n";
			}
			else
			{
				echo $foucomm;
			}
		}
		
		echo "</div></td>\n";
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><div id=\"commission\"><b>Manual Comm. Adjust</b></div></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\"><img src=\"images/pixel.gif\">\n";
	
		if ($tbullets > 0)
		{
			echo "$tbullets Bullets";
		}
	
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
	
		if ($_SESSION['elev'] >= 4)
		{
			echo "<form name=\"mancomadj\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
			echo "<input type=\"hidden\" name=\"comm\" value=\"".$ftrcomm."\">\n";
			echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	
			if ($viewarray['applyov']==1)
			{
				echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\"><input class=\"bboxnobr\" type=\"text\" name=\"comadj\" value=\"".$fadjcomm."\" size=\"7\"></div></td>\n";
			}
			else
			{
				echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\"><input class=\"bboxnobr\" type=\"text\" name=\"comadj\" value=\"".$foucomm."\" size=\"7\"></div></td>\n";
			}

			echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\">\n";
	
			if ($_SESSION['subq']=="print")
			{
				echo "<div class=\"noPrint\">\n";
			}
	
			if ($rowI[11]!=0 || $rowI[12]!=0)
			{
				if ($viewarray['applyov']==1)
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteou\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
					echo "					<input class=\"transnb\" type=\"image\" src=\"images/delete.png\" alt=\"Delete Comm Adjust\" DISABLED>\n";
					//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Delete\" DISABLED>\n";
				}
				else
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"applyou\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
					echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Comm Adjust\" DISABLED>\n";
					//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
				}
			}
			else
			{
				if ($viewarray['applyov']==1)
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteou\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
					echo "					<input class=\"transnb\" type=\"image\" src=\"images/delete.png\" alt=\"Delete Comm Adjust\">\n";
				}
				else
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"applyou\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
					echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Comm Adjust\">\n";
				}
			}
			
			echo "</form>\n";
	
			if ($_SESSION['subq']=="print")
			{
				echo "</div>\n";
			}
	
			echo "                                        </td>\n";
		}
		else
		{
			echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\"><div id=\"commission\">".$fadjcomm."</div></td>\n";
			echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
		}
	
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><div id=\"commission\"><b>Total Commission</b></div></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\">". round(($tadjcomm/$trcost), 2) * 100 ."%</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\">".$ftadjcomm."</div></td>\n";
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
	
		if ($rowC[2]==1)
		{
			echo "            <tr>\n";
			echo "               <td colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Tax (".$taxrate[1]."):</b></td>\n";
			echo "               <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td align=\"right\" class=\"wh_undsidesr\" width=\"65\">".$frtax."</td>\n";
			echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
			echo "               <td class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td class=\"gray_undsidesr\" align=\"right\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Total:</b></td>\n";
			echo "               <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td align=\"right\" class=\"wh_undsidesr\" width=\"65\">".$fgrtcost."</td>\n";
			echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
			echo "               <td class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td class=\"gray_undsidesr\" align=\"right\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
			echo "            </tr>\n";
		}
	}
	
	echo "         </table>\n";
	echo "		</p>\n";
	echo "		</div>\n";
	echo "		<div id=\"pb\">\n";
	echo "			<p>\n";
	//echo "				<iframe name=\"PBSelect\" id=\"frmPBSelect\" src=\"subs/pb_select.php\" frameborder=\"0\" width=\"100%\" height=\"600\" align=\"center\"></iframe>\n";
	pbmatrix();
	
	echo "			</p>\n";
	echo "		</div>\n";
	echo "	</div>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	
	/*
	echo "
	
	<script> 
	(function() {
		var tabView = new YAHOO.widget.TabView('EstRetail');
	})();
	</script>
	
	";
	*/
	
	$viewarray['tcomm']		=$tadjcomm;
	$viewarray['tretail']	=$adjbookamt;
	$viewarray['tcontract']	=$ctramt;
	$viewarray['acctotal']	=$trccost;
	$viewarray['discount']	=$vdiscnt;
	$viewarray['royrel']	=0;
	$viewarray['custallow']	=0;
	
	if ($viewarray['jobid']!='0' || $viewarray['njobid']!='0')
	{
		$viewarray['allowdel']	=1;
	}
	else
	{
		$viewarray['allowdel']	=0;
	}
	
	$_SESSION['viewarray']=$viewarray;
	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";*/
}

function viewest_retailOLD()
{
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	unset($viewarray);
	unset($_SESSION['estbidretail']);
	unset($_SESSION['demomode']);
	
	$MAS		=$_SESSION['pb_code'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	$_SESSION['aid']=aidbuilder($_SESSION['jlev'],"j");
	$acclist	=explode(",",$_SESSION['aid']);
	
	//
	if (isset($_REQUEST['estid']) && $_REQUEST['estid']!=0)
	{
		$estid		=$_REQUEST['estid'];
	}
	else
	{
		$estid		=$_SESSION['estid'];
	}
	
	$jobid		=0;

	if (!isset($estid) || $estid==0 || $estid=='')
	{
		die("Fatal Error: Estimate ID (".$estid.") not set!");
	}

	$qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,comadj,sidm,buladj,applyov,applybu,refto,apft,added,updated,updateby,comm,renov,esttype,ccid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_row($respreA);
	
	//echo $qrypreA.'<br>';
	
	$qrypreAa = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND estid='".$rowpreA[0]."';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreB = "SELECT securityid,sidm FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowpreA[17]."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_row($respreB);
	
	/*if (!in_array($_SESSION['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>You do not have approriate Access to view this Estimate</b>";
		exit;
	}*/

	$viewarray=array(
	'estid'=>	$rowpreA[0],
	'jobid'=>	'0',
	'njobid'=>	'0',
	'ps1'=>		$rowpreA[1],
	'ps2'=>		$rowpreA[2],
	'spa1'=>	$rowpreA[3],
	'spa2'=>	$rowpreA[4],
	'spa3'=>	$rowpreA[5],
	'tzone'=>	$rowpreA[6],
	'camt'=>	$rowpreA[7],
	'comt'=>	0,
	'cfname'=>	$rowpreAa['cfname'],
	'clname'=>	$rowpreAa['clname'],
	'phone'=>	$rowpreA[10],
	'status'=>	$rowpreA[11],
	'ps5'=>		$rowpreA[13],
	'ps6'=>		$rowpreA[14],
	'ps7'=>		$rowpreA[15],
	'cid'=>		$rowpreA[35],
	'estsecid'=>$rowpreA[17],
	'deck'=>	$rowpreA[18],
	'erun'=>	$rowpreA[19],
	'prun'=>	$rowpreA[20],
	'jobid'=>	$rowpreA[21],
	'comadj'=>	$rowpreA[22],
	'dbocomm'=>	$rowpreA[32],
	'sidm'=>	$rowpreA[23],
	'buladj'=>	$rowpreA[24],
	'applyov'=>	$rowpreA[25],
	'applybu'=>	$rowpreA[26],
	'refto'=>	$rowpreA[27],
	'ps1a'=>	$rowpreA[28],
	'jadd'=>	0,
	'mjadd'=>	0,
	'custallow'=>0,
	'renov'=>	$rowpreA[33],
	'esttype'=>	$rowpreA[34],
	'discount'=>0,
	'royrel'=>	0,
	'allowdel'=>0,
	'tcomm'=>	0,
	'comsched'=>array()
	);

	$qrypreD = "SELECT * FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);

	$r_estdata = $rowpreD['estdata'];

	$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft,encost,enest FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[10]!=1)
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>Estimating has been disabled in ".$rowC[1]."</b>";
		exit;
	}

	if ($rowC[8]=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

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

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$viewarray['cid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_row($resL);

	if ($rowpreA[31]!=0)
	{
		$qryM = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowpreA[31]."';";
		$resM = mssql_query($qryM);
		$rowM = mssql_fetch_array($resM);

		$lupdatestr=$rowM['fname']." ".$rowM['lname'];
	}
	else
	{
		$lupdatestr="";
	}

	$qryN = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND srep=1 ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$resN = mssql_query($qryN);

	$qryP = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$resP = mssql_query($qryP);

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}
	
	$qryO = "SELECT * FROM comm_adj_sched WHERE oid='".$_SESSION['officeid']."';";
	$resO = mssql_query($qryO);
	$nrowO= mssql_num_rows($resO);
	
	if ($nrowO > 0)
	{
		$comsched=array();
		while($rowO = mssql_fetch_array($resO))
		{
			$comsched[]=array('oid'=>$rowO['oid'],'type'=>$rowO['type'],'rate'=>$rowO['rate']);
		}
		
		$viewarray['comsched']=$comsched;
	}

	$_SESSION['viewarray']=$viewarray;
	$tbullets   	=0;
	$poolcomm_adj	=detect_package($r_estdata);
	$set_deck   	=deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    	=round($set_deck[0]);
	$set_ia     	=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals   	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$estidret   	=$rowpreA[0];
	$vdiscnt    	=$viewarray['camt'];	
	$bpset			=select_base_pool();
	$pbaseprice 	=$bpset[3];

	if ($poolcomm_adj >= 1)
	{
		$bcomm      =0;
	}
	else
	{
		$bcomm      =$bpset[4];
	}

	$uid			=md5(session_id().time().$rowI[10]).".".$_SESSION['securityid'];

	if (!empty($rowpreA[29]))
	{
		$atime=date("m/d/Y", strtotime($rowpreA[29]));
	}
	else
	{
		$atime="";
	}

	if (!empty($rowpreA[30]))
	{
		$utime=date("m/d/Y", strtotime($rowpreA[30]));
	}
	else
	{
		$utime="";
	}
	
	if (isset($viewarray['esttype']) && $viewarray['esttype']=='Q')
	{
		$etype='Quote';
	}
	else
	{
		$etype='Estimate';
	}

	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	$fbcomm		=number_format($bcomm, 2, '.', '');
	$ctramt		=$viewarray['camt'];
	$fctramt	=number_format($ctramt, 2, '.', '');

	//echo "<script language=\"javascript\" type=\"text/javascript\" src=\"js/est-drawer-panel.js\"></script>\n";
	//echo "<script language=\"javascript\" type=\"text/javascript\" src=\"js/view_estimate_qtips.js\"></script>\n";
	//echo "<div id=\"masterdiv\">\n";
	echo "<table width=\"750\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"left\">\n";
	echo "			<table cellspacing=0 align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"3\" valign=\"top\" align=\"left\" width=\"100%\">\n";
	echo "                  	<table width=\"100%\" class=\"outer\" border=0>\n";
	echo "                  	   <tr>\n";
	echo "								<td class=\"gray\" align=\"left\" NOWRAP><b>Blue Haven Pools & Spas ";
	
	echo $etype;
	
	echo "</b></td>\n";
	echo "								<td class=\"gray\" align=\"right\">\n";
	
	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}
	
	//Control Box Code
	echo "									<table border=0>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	echo "												<img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\">\n";
	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	echo "			<form name=\"CustInfo\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	echo "			<input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
	echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$rowI[10]."\">\n";
	echo "			<input class=\"checkbox\" type=\"image\" src=\"images/comments.png\" alt=\"Customer Information & Comments\">\n";
	echo "			</form>\n";

	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	
	if ($_SESSION['elev'] >= 1)
	{	
		echo "			<form name=\"deleteest\" method=\"POST\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"delete_est1\">\n";
		echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "			<input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";

		if ($rowI[11]!=0 || $rowI[12]!=0)
		{
			echo "			<input class=\"checkbox\" type=\"image\" src=\"images/layout_delete.png\" alt=\"Delete $etype\" DISABLED>\n";
		}
		else
		{
			echo "			<input class=\"checkbox\" type=\"image\" src=\"images/layout_delete.png\" alt=\"Delete $etype\">\n";
		}
		
		echo "			</form>\n";
	}
	
	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	
	if (isset($viewarray['esttype']) && $viewarray['esttype']=='E')
	{
		echo "			<form name=\"createcontract\" method=\"post\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"create_job\">\n";
		
		/*if ($rowC[2]==1)
		{
			echo "			<input type=\"hidden\" name=\"salestax\" value=\"".$frtax."\">\n";
		}*/
	
		if ($rowI[11]!=0 || $rowI[12]!=0)
		{
			echo "			<input class=\"checkbox\" type=\"image\" src=\"images/layout_add.png\" alt=\"Create Contract\" DISABLED>\n";
		}
		else
		{
			echo "			<input class=\"checkbox\" type=\"image\" src=\"images/layout_add.png\" alt=\"Create Contract\">\n";
		}
	
		echo "				</form>\n";
	}
	
	echo "											</td>\n";
	/*echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	
	if ($_SESSION['elev'] >= 6 || $_SESSION['clev'] >= 6 || $_SESSION['jlev'] >= 9999999999)
	{	
		echo "			<form name=\"viewcost\" method=\"post\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "         	<input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
		echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "			<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";

		if ($rowC[9]==1)
		{
			echo "			<input class=\"checkbox\" type=\"image\" src=\"images/database_table.png\" alt=\"View Cost\">\n";
		}
		
		echo "			</form>\n";
	}
	
	echo "											</td>\n";*/
	echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
	
	echo "			<form name=\"mainupdate\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "         	<input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
	echo "			<input type=\"hidden\" name=\"securityid\" value=\"".$viewarray['estsecid']."\">\n";
	echo "			<input type=\"hidden\" name=\"custid\" value=\"".$rowI[0]."\">\n";
	echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "			<input type=\"hidden\" name=\"discount\" value=\"".$vdiscnt."\">\n";
	echo "			<input type=\"hidden\" name=\"contractamt\" value=\"".$fctramt."\">\n";
	echo "			<input type=\"hidden\" name=\"spa1\" value=\"".$viewarray['spa1']."\">\n";
	echo "			<input type=\"hidden\" name=\"spa2\" value=\"".$viewarray['spa2']."\">\n";
	echo "			<input type=\"hidden\" name=\"spa3\" value=\"".$viewarray['spa3']."\">\n";
	echo "			<input type=\"hidden\" name=\"status\" value=\"".$viewarray['status']."\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"update\">\n";
	echo "			<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Info\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	
	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}
	
	//End Control Box Code
	
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td valign=\"top\" align=\"left\" width=\"32%\">\n";

	// Begin Customer Display Info
	cinfo_display($viewarray['cid'],$rowC[2]);
	// End Customer Display

	echo "					</td>\n";
	echo "					<td valign=\"top\" align=\"right\" width=\"32%\">\n";
	
	// Begin Pool Detail Display
	pool_detail_display($viewarray['estid']);
	// End Pool Detail Display

	echo "					<td valign=\"top\" align=\"right\" width=\"32%\">\n";

	// Set System/Est Info Display
	echo "						<table width=\"100%\" class=\"outer\" height=\"150\" border=0>\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" valign=\"top\">\n";
	echo "								<table width=\"100%\" border=0>\n";
	echo "									<tr>\n";
	echo "										<td class=\"gray\" align=\"right\" width=\"50%\"><b>".$etype."</b></td>\n";
	echo "										<td class=\"gray\" align=\"left\">".$estidret."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td class=\"gray\" align=\"right\" width=\"50%\"><b>Office</b></td>\n";
	echo "										<td class=\"gray\" align=\"left\">".$rowC[1]."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td class=\"gray\" align=\"right\" width=\"50%\"><b>Sales Rep</b></td>\n";
	echo "										<td class=\"gray\" align=\"left\">\n";

	if ($_SESSION['jlev'] >= 4)
	{
		echo "                                               <select name=\"securityid\">\n";
		while($rowN = mssql_fetch_row($resN))
		{
			if (in_array($rowN[0],$acclist))
			{
				$secl=explode(",",$rowN[3]);
				if ($secl[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}

				if ($viewarray['estsecid']==$rowN[0])
				{
					echo "                                               	<option value=\"".$rowN[0]."\" class=\"".$ostyle."\" SELECTED>".$rowN[1]." ".$rowN[2]."</option>";
				}
				else
				{
					echo "                                               	<option value=\"".$rowN[0]."\" class=\"".$ostyle."\">".$rowN[1]." ".$rowN[2]."</option>";
				}
			}
		}
		echo "                                               </select>\n";
	}
	else
	{
		echo $rowD[1]." ".$rowD[2]."\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$viewarray['estsecid']."\">\n";
	}

	echo "							</td>\n";
	echo "						</tr>\n";
	
	if ($viewarray['esttype']=='E')
	{
		echo "						<tr>\n";
		echo "							<td class=\"gray\" align=\"right\" width=\"50%\"><b>Manager</b></td>\n";
		echo "							<td class=\"gray\" align=\"left\">\n";
	
		if ($_SESSION['jlev'] >= 6)
		{
			echo "								<select name=\"sidm\">\n";
	
			while($rowP = mssql_fetch_row($resP))
			{
				$secl=explode(",",$rowP[3]);
				if ($secl[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}
	
				if ($viewarray['sidm']==$rowP[0])
				{
					echo "								<option value=\"".$rowP[0]."\" class=\"".$ostyle."\" SELECTED>".$rowP[1]." ".$rowP[2]."</option>\n";
				}
				else
				{
					echo "								<option value=\"".$rowP[0]."\" class=\"".$ostyle."\">".$rowP[1]." ".$rowP[2]."</option>\n";
				}
			}
			
			echo "								</select>\n";
		}
		else
		{
			echo $rowL[1]." ".$rowL[2]."\n";
			echo "<input type=\"hidden\" name=\"sidm\" value=\"".$viewarray['sidm']."\">\n";
		}
	
		echo "								</td>\n";
		echo "						</tr>\n";
		echo "						<tr>\n";
	
		/*if ($_SESSION['subq']=="print")
		{
			echo "<form method=\"post\">\n";
			echo "							<td class=\"gray\" align=\"right\">\n";
			echo "								<input class=\"buttondkgrypnl80\" type=\"button\" name=\"buttonPrint\" value=\"Print\" onClick=\"window.print()\">\n";
			echo "                     		</td>\n";
			echo "</form>\n";
		}*/
	
		echo "                     				</tr>\n";
		echo "                     				<tr>\n";
		echo "										<td class=\"gray\" align=\"right\"><b>Date Added</b></td>\n";
		echo "										<td class=\"gray\" align=\"left\">".$atime."</td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "										<td class=\"gray\" align=\"right\"><b>Date Updated</b></td>\n";
		echo "										<td class=\"gray\" align=\"left\">".$utime."</td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "										<td class=\"gray\" align=\"right\"><b>by</b></td>\n";
		echo "										<td class=\"gray\" align=\"left\">".$lupdatestr."</td>\n";
		/*echo "      <td valign=\"top\" align=\"left\">\n";
		echo "			<div onclick=\"SwitchMenu('ItemSelect')\"><img src=\"images/add.png\"></div>";
		echo "		</td>\n";*/
		echo "									</tr>\n";
	}
	else
	{
		echo "<input type=\"hidden\" name=\"sidm\" value=\"".$viewarray['sidm']."\">\n";
	}
	
	echo "								</table>\n";
	echo "							</td>\n";
	echo "						</table>\n";
	
	// End System/Est Info Display
	
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";	
	echo "	</tr>\n";
	echo "</form>\n";
	/*echo "	<tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	
	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}
	
	echo "				<table class=\"outer\" cellspacing=0 width=\"100%\">\n";
	echo "					<tr>\n";
	echo "						<td class=\"ltgray\"><div onclick=\"SwitchMenu('ItemSelect')\" title=\"Click to Open/Close\"><font color=\"blue\"><b>Pricebook</b></font></div></td>\n";
	echo "                      <td class=\"ltgray\" align=\"right\"><img src=\"images/help.png\"></td>\n";
	//echo "                      <td class=\"ltgray_und\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "					</tr>\n";
	echo "					<tr>\n";
	echo "						<td class=\"gray\" colspan=\"2\">\n";
	//echo "							<div id=\"container\"></div>\n";
	echo "							<span class=\"submenu\" id=\"ItemSelect\" style=\"display:none\">\n";
	echo "								<iframe name=\"PBSelect\" id=\"frmPBSelect\" src=\"subs/pb_select.php\" frameborder=\"0\" width=\"100%\" height=\"250\" align=\"center\"></iframe>\n";
	echo "							</span>\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	
	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}
	
	echo "		</td>\n";	
	echo "	</tr>\n";*/
	echo "	<tr>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	echo "         <table width=\"100%\">\n";
	echo "           <tr>\n";
	echo "              <td>\n";
	
	echo "			<div id=\"EstRetail\" class=\"yui-navset\">\n";
    echo "				<ul class=\"yui-nav\">\n";
	echo "					<li class=\"selected\"><a href=\"#rt\"><em>Breakdown</em></a></li>\n";
	echo "			    	<li><a href=\"#pb\"><em>Pricebook</em></a></li>\n";
	echo "				</ul>\n";
	echo "				<div class=\"yui-content\">\n";
    echo "				    <div id=\"rt\">\n";
	echo "						<p>\n";
	
	echo "           <form name=\"updateestitems\" method=\"post\">\n";
	echo "           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "         	 <input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
	echo "           <input type=\"hidden\" name=\"call\" value=\"update_acc\">\n";
	echo "           <input type=\"hidden\" name=\"estid\" value=\"".$_SESSION['estid']."\">\n";
	echo "         			<table cellspacing=0 width=\"100%\" border=0>\n";
	echo "           			<tr>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"left\" width=\"90\"><b>Category</b></td>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"left\"><b>Item</b></td>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"center\" width=\"30\"><b>Quan</b></td>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"center\" width=\"30\"><b>Units</b></td>\n";
	echo "              			<td NOWRAP class=\"gray\" align=\"center\" width=\"65\"><b>Price</b></td>\n";
	
	if ($viewarray['esttype']=='E')
	{
		echo "              			<td NOWRAP class=\"gray\" align=\"center\" width=\"55\"><b>Adjusts</b></td>\n";
	}
	
	/*echo "           <form name=\"updateestitems\" method=\"post\">\n";
	echo "           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "         	 <input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
	echo "           <input type=\"hidden\" name=\"call\" value=\"update_acc\">\n";
	echo "           <input type=\"hidden\" name=\"estid\" value=\"".$_SESSION['estid']."\">\n";*/
	
	if ($viewarray['esttype']=='Q')
	{	
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
	}
	else
	{
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"55\"><div id=\"comm\"><b>Comm</b></div></td>\n";
	}
	
	echo "              <td NOWRAP class=\"gray_undsidesr\" valign=\"bottom\" align=\"center\" width=\"25\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	if ($viewarray['status'] >= 2)
	{
		echo "							 <input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Update Items\" DISABLED>\n";
	}
	else
	{
		echo "							 <input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Update Items\">\n";
	}

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "					</td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"left\" width=\"90\">Base</td>\n";
	echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"left\"><b>Basic Pool</b></td>\n";
	echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"center\" width=\"30\">".$bpset[5]."</td>\n";
	echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"center\" width=\"30\">".$bpset[6]."</td>\n";

	$qryBP = "SELECT * FROM base_price_adjusts WHERE oid=".$_SESSION['officeid']." AND estid=".$viewarray['estid'].";";
	$resBP = mssql_query($qryBP);
	$rowBP = mssql_fetch_array($resBP);
	$nrowBP= mssql_num_rows($resBP);
	
	$qryPP = "SELECT * FROM base_price_pad WHERE sid=".$viewarray['estsecid'].";";
	$resPP = mssql_query($qryPP);
	$rowPP = mssql_fetch_array($resPP);
	$nrowPP= mssql_num_rows($resPP);

	
	if (isset($viewarray['esttype']) && $viewarray['esttype']=='Q')
	{
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">\n";
		
		if ($nrowBP != 0)
		{
			//echo '1<br>';
			$adj_bprice=$rowBP['adj_price'];
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$rowBP['ppb_price']."\">\n";
			echo "                  <input class=\"bboxnobrb\" type=\"text\" name=\"base_pl_src[1]\" value=\"".number_format($rowBP['adj_price'], 2, '.', '')."\" size=\"7\" title=\"This is an Adjusted Amount\">\n";
		}
		elseif ($nrowPP != 0 && $nrowBP == 0)
		{
			//echo '2<br>';
			$adj_bprice=$fpbaseprice+$rowPP['adj_price'];
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobrb\" type=\"text\" name=\"base_pl_src[1]\" value=\"".number_format(($fpbaseprice+$rowPP['adj_price']), 2, '.', '')."\" size=\"7\" title=\"This is an Adjusted Amount\">\n";
		}
		else
		{
			//echo '3<br>';
			$adj_bprice=$fpbaseprice;
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobr\" type=\"text\" name=\"base_pl_src[1]\" value=\"".$fpbaseprice."\" size=\"7\">\n";
		}
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\"><img src=\"images/pixel.gif\"></div></td>\n";
	}
	elseif (isset($viewarray['esttype']) && $viewarray['esttype']=='E')
	{
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">\n";
		
		if ($nrowBP != 0)
		{
			echo number_format($rowBP['ppb_price'], 2, '.', '');
		}
		else
		{
			echo $fpbaseprice;
		}
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\">\n";
		echo "					<div id=\"adjts\">\n";
		
		if ($nrowBP != 0)
		{
			//echo '4<br>';
			$adj_bprice=$rowBP['ppb_price'] + $rowBP['var_price'];
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobrb\" type=\"text\" name=\"base_pl_src[1]\" value=\"".number_format($rowBP['var_price'], 2, '.', '')."\" size=\"7\" title=\"This is an Adjusted Amount\">\n";
		}
		elseif ($nrowPP != 0)
		{
			//echo '4<br>';
			$adj_bprice=$fpbaseprice + $rowPP['adj_price'];
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobrb\" type=\"text\" name=\"base_pl_src[1]\" value=\"".number_format($rowPP['adj_price'], 2, '.', '')."\" size=\"7\" title=\"This is an Adjusted Amount\">\n";
		}
		else
		{
			//echo '5<br>';
			$adj_bprice=$fpbaseprice;
			echo "                  <input type=\"hidden\" name=\"base_pl_src[0]\" value=\"".$fpbaseprice."\">\n";
			echo "                  <input class=\"bboxnobr\" type=\"text\" name=\"base_pl_src[1]\" value=\"0.00\" size=\"7\">\n";
		}
		
		echo "					</div>\n";
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\">".$fbcomm."</div></td>\n";
	}
	else
	{
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">\n";
		
		//echo '4<br>';
		$adj_bprice=$fpbaseprice;
		echo $fpbaseprice;
		
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\">".$fbcomm."</div></td>\n";
	}
	
	echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";

	//echo $r_estdata."<br>";
	calcbyacc($r_estdata,0);

	// Totals Table Calcs
	$bccost  =$bctotal;
	$rccost  =$rctotal;
	$cccost  =$cctotal;
	$bmcost  =$bmtotal;
	$rmcost  =$rmtotal;
	$trccost =$rccost+$rmcost;
	$cmcost  =$cmtotal;
	$tbcost  =$bccost+$bmcost;
	$trcost  =$adj_bprice+$trccost+$tbid;
	$tccost  =$cccost+$cmcost;
	$trcomm  =$bcomm+$tccost;
	$prof    =($trcost-$tbcost)-$trcomm;
	
	if ($prof!=0)
	{
		$perprof =$prof/$trcost;
	}
	else
	{
		$perprof =0;
	}

	if ($rowC[2]==1)
	{
		$rtax    =$ctramt*$taxrate[1];
		$grtcost =$ctramt+$rtax;
		$frtax   =number_format($rtax, 2, '.', '');
		$fgrtcost=number_format($grtcost, 2, '.', '');
	}

	$fbccost		=number_format($bccost, 2, '.', '');
	$fbmcost		=number_format($bmcost, 2, '.', '');
	$fcccost		=number_format($cccost, 2, '.', '');
	$frccost		=number_format($rccost, 2, '.', '');
	$frmcost		=number_format($rmcost, 2, '.', '');
	$fcmcost		=number_format($cmcost, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftrcost		=number_format($trcost, 2, '.', '');
	$ftccost		=number_format($tccost, 2, '.', '');
	$ftrcomm		=number_format($trcomm, 2, '.', '');

	echo "           <tr>\n";
	
	if ($viewarray['esttype']=='Q')
	{
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Total Price</b></td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Price per Book</b></td>\n";
	}
	
	echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">".$ftrcost."</td>\n";
	
	if ($viewarray['esttype']=='Q')
	{	
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
	}
	else
	{
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\">".$ftrcomm."</div></td>\n";
	}
	
	echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	
	if (!isset($rowC[5])||!is_numeric($rowC[5]))
	{
		//echo "OBR: ". $rowC[5]."<br>";
		$bullet_rate=0;
	}
	else
	{
		$bullet_rate=$rowC[5];
	}

	$adjbookamt	=$trcost+$discount;
	$fadjbookamt=number_format($adjbookamt, 2, '.', '');

	if ($viewarray['renov']==1)
	{
		$adjctramt	=0;
	}
	else
	{
		$adjctramt	=$ctramt-$adjbookamt;
	}
	
	$fadjctramt	=number_format($adjctramt, 2, '.', '');

	$adjcomm		=0;

	$ou_out		=calc_ou($adjctramt,$adjcomm,$tbullets,$rowC[6],$viewarray['applyov'],$viewarray['comadj'],$bullet_rate,$rowC[7]);

	$foucomm	=number_format($ou_out[0], 2, '.', '');
	$fadjcomm	=number_format($ou_out[1], 2, '.', '');

	if ($viewarray['applyov']==1)
	{
		//$tadjcomm	=($trcomm + $t_sr_comadj_amt) + $fadjcomm;
		$tadjcomm	=$trcomm + $fadjcomm;
	}
	else
	{
		//$tadjcomm	=$trcomm + $t_sr_comadj_amt;
		$tadjcomm	=$trcomm;
	}

	// Set commission for global
	$viewarray['comt']	=$tadjcomm;
	$ftadjcomm			=number_format($tadjcomm, 2, '.', '');

	echo "</form>\n";
	
	if ($viewarray['esttype']=='E')
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Adjusted Book Price</b></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">".$fadjbookamt."</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		echo "<form name=\"setretail\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"update_contract_amt\">\n";
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Retail Contract Price</b></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">";
		echo "                 <input class=\"bboxnobr\" type=\"text\" name=\"c_amt\" size=\"6\" maxlength=\"10\" value=\"".$fctramt."\">";
		echo "              </td>";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\">";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}
	
		if ($viewarray['status'] >= 2)
		{
			echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Contract Price\" DISABLED>\n";
			//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
		}
		else
		{
			echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Contract Price\">\n";
			//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\">\n";
		}
	
		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "              </td>\n";
		echo "           </tr>\n";
		echo "</form>\n";
	}
	
	//Over/Under Contract Percentage
	$osplitperc=0;
	if ($viewarray['renov'] == 1)
	{
		$osplitperc=0;
	}
	else
	{
		if (isset($fctramt) && $fctramt!=0)
		{
			$osplitperc=round(($fadjctramt/$fctramt)*100);
		}
		else
		{
			$osplitperc=0;
		}
	}
	
	if ($viewarray['esttype']=='E')
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Overage/<font color=\"red\">Underage</font></b></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\">";
		
		if ($osplitperc < 0)
		{
			echo "<font color=\"red\">".$osplitperc."%</font>\n";
		}
		else
		{
			echo $osplitperc."%";
		}
		
		echo "</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\">".($rowC[7] * 100)."%</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\">";
		
		if ($adjctramt < 0)
		{
			echo "<font color=\"red\">".$fadjctramt."</font>\n";
		}
		else
		{
			echo $fadjctramt;
		}
		
		echo "</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\">";
		
		if ($viewarray['applyov']==1)
		{
			echo "<img src=\"images/pixel.gif\">\n";
		}
		else
		{
			if ($foucomm < 0)
			{
				echo "<font color=\"red\">".$foucomm."</font>\n";
			}
			else
			{
				echo $foucomm;
			}
		}
		
		echo "</div></td>\n";
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><div id=\"commission\"><b>Manual Comm. Adjust</b></div></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\"><img src=\"images/pixel.gif\">\n";
	
		if ($tbullets > 0)
		{
			echo "$tbullets Bullets";
		}
	
		echo "				</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
	
		if ($_SESSION['elev'] >= 4)
		{
			echo "<form name=\"mancomadj\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
			echo "<input type=\"hidden\" name=\"comm\" value=\"".$ftrcomm."\">\n";
			echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	
			if ($viewarray['applyov']==1)
			{
				echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\"><input class=\"bboxnobr\" type=\"text\" name=\"comadj\" value=\"".$fadjcomm."\" size=\"7\"></div></td>\n";
			}
			else
			{
				echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\"><input class=\"bboxnobr\" type=\"text\" name=\"comadj\" value=\"".$foucomm."\" size=\"7\"></div></td>\n";
			}

			echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\">\n";
	
			if ($_SESSION['subq']=="print")
			{
				echo "<div class=\"noPrint\">\n";
			}
	
			if ($rowI[11]!=0 || $rowI[12]!=0)
			{
				if ($viewarray['applyov']==1)
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteou\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
					echo "					<input class=\"transnb\" type=\"image\" src=\"images/delete.png\" alt=\"Delete Comm Adjust\" DISABLED>\n";
					//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Delete\" DISABLED>\n";
				}
				else
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"applyou\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
					echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Comm Adjust\" DISABLED>\n";
					//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
				}
			}
			else
			{
				if ($viewarray['applyov']==1)
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteou\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
					echo "					<input class=\"transnb\" type=\"image\" src=\"images/delete.png\" alt=\"Delete Comm Adjust\">\n";
				}
				else
				{
					echo "                  <input type=\"hidden\" name=\"call\" value=\"applyou\">\n";
					echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
					echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Comm Adjust\">\n";
				}
			}
			
			echo "</form>\n";
	
			if ($_SESSION['subq']=="print")
			{
				echo "</div>\n";
			}
	
			echo "                                        </td>\n";
		}
		else
		{
			echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\"><div id=\"commission\">".$fadjcomm."</div></td>\n";
			echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
		}
	
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><div id=\"commission\"><b>Total Commission</b></div></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\">". round(($tadjcomm/$trcost), 2) * 100 ."%</td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"65\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
		echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"comm\">".$ftadjcomm."</div></td>\n";
		echo "              <td NOWRAP class=\"gray_undsidesr\" align=\"center\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
	
		if ($rowC[2]==1)
		{
			echo "            <tr>\n";
			echo "               <td colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Tax (".$taxrate[1]."):</b></td>\n";
			echo "               <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td align=\"right\" class=\"wh_undsidesr\" width=\"65\">".$frtax."</td>\n";
			echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
			echo "               <td class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td class=\"gray_undsidesr\" align=\"right\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td colspan=\"2\" class=\"wh_undsidesr\" align=\"right\"><b>Total:</b></td>\n";
			echo "               <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"30\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td align=\"right\" class=\"wh_undsidesr\" width=\"65\">".$fgrtcost."</td>\n";
			echo "              <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
			echo "               <td class=\"wh_undsidesr\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
			echo "               <td class=\"gray_undsidesr\" align=\"right\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
			echo "            </tr>\n";
		}
	}
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "		</p>\n";
	echo "		</div>\n";
	echo "		<div id=\"pb\">\n";
	echo "			<p>\n";
	//echo "				<iframe name=\"PBSelect\" id=\"frmPBSelect\" src=\"subs/pb_select.php\" frameborder=\"0\" width=\"100%\" height=\"600\" align=\"center\"></iframe>\n";
	pbmatrix();
	
	echo "			</p>\n";
	echo "		</div>\n";
	echo "
	
	<script> 
	(function() {
		var tabView = new YAHOO.widget.TabView('EstRetail');
	})();
	</script>
	
	";
	
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	//echo "</div>\n";
	
	$viewarray['tcomm']		=$tadjcomm;
	$viewarray['tretail']	=$adjbookamt;
	$viewarray['tcontract']	=$ctramt;
	$viewarray['acctotal']	=$trccost;
	$viewarray['discount']	=$vdiscnt;
	$viewarray['royrel']	=0;
	$viewarray['custallow']	=0;
	
	if ($viewarray['jobid']!='0' || $viewarray['njobid']!='0')
	{
		$viewarray['allowdel']	=1;
	}
	else
	{
		$viewarray['allowdel']	=0;
	}
	
	$_SESSION['viewarray']=$viewarray;
	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";*/
}

function viewest_addnew()
{
	$MAS=$_SESSION['pb_code'];
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate;

	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	$estid      =$_SESSION['estid'];

	if (!isset($estid)||$estid==''||$estid==0)
	{
		echo "Fatal Error: \$$estid not set, or is Zero!";
		exit;
	}

	$qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,comadj,sidm,buladj,applyov,applybu,refto,apft,renov FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_row($respreA);

	$qrypreB = "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$officeid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_row($respreD);

	// Builds a list of exisiting categories in the retail accessory table by office
	$qrypreE  = "SELECT DISTINCT a.catid,a.seqn ";
	$qrypreE .= "FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
	$qrypreE .= "ON a.catid=b.catid ";
	$qrypreE .= "AND a.officeid='".$_SESSION['officeid']."' ";
	$qrypreE .= "AND a.active=1 ";
	$qrypreE .= "ORDER BY a.seqn ASC;";
	$respreE = mssql_query($qrypreE);

	while ($rowpreE = mssql_fetch_row($respreE))
	{
		$catarray[]=$rowpreE[0];
	}

	$ps1        =$rowpreA[1];
	$ps2        =$rowpreA[2];
	$spa1       =$rowpreA[3];
	$spa2       =$rowpreA[4];
	$spa3       =$rowpreA[5];
	$tzone      =$rowpreA[6];
	$discount   =$rowpreA[7];
	$cfname     =$rowpreA[8];
	$clname     =$rowpreA[9];
	$phone      =$rowpreA[10];
	$status     =$rowpreA[11];
	$ps5        =$rowpreA[13];
	$ps6        =$rowpreA[14];
	$ps7        =$rowpreA[15];

	$viewarray=array(
	'ps1'=>$rowpreA[1],
	'ps2'=>$rowpreA[2],
	'spa1'=>$rowpreA[3],
	'spa2'=>$rowpreA[4],
	'spa3'=>$rowpreA[5],
	'tzone'=>$rowpreA[6],
	'camt'=>$rowpreA[7],
	'cfname'=>$rowpreA[8],
	'clname'=>$rowpreA[9],
	'phone'=>$rowpreA[10],
	'status'=>$rowpreA[11],
	'ps5'=>$rowpreA[13],
	'ps6'=>$rowpreA[14],
	'ps7'=>$rowpreA[15],
	'custid'=>$rowpreA[16],
	'estsecid'=>$rowpreA[17],
	'deck'=>$rowpreA[18],
	'erun'=>$rowpreA[19],
	'prun'=>$rowpreA[20],
	'jobid'=>$rowpreA[21],
	'comadj'=>$rowpreA[22],
	'sidm'=>$rowpreA[23],
	'buladj'=>$rowpreA[24],
	'applyou'=>$rowpreA[25],
	'applybu'=>$rowpreA[26],
	'refto'=>$rowpreA[27],
	'ps1a'=>$rowpreA[28]
	);

	if ($rowpreB['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryC = "SELECT officeid,name,stax FROM offices WHERE officeid='".$officeid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname,rmasid FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT cid,cfname,clname,chome,scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$rowpreA[16]."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$type=0; //EST=0 JOB=1

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
	}

	$estidret   =$rowpreA[0];
	//$vdiscnt    =$viewarray['discount'];
	$vdiscnt    =0;
	//$pbaseprice =$rowB[2]-$discount;
	$pbaseprice =$rowB[2];
	$bcomm      =$rowB[3];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');

	//echo $rowpreA[0]."<br>";
	//echo $rowpreD[0];

	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";

	if ($viewarray['status']==3)
	{
		echo "<input type=\"hidden\" name=\"call\" value=\"acc_adds_addendum\">\n";
	}
	else
	{
		echo "<input type=\"hidden\" name=\"call\" value=\"acc_adds\">\n";
	}

	echo "<input type=\"hidden\" name=\"estid\" value=\"$estidret\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
	//echo "<input type=\"hidden\" name=\"discount\" value=\"$vdiscnt\">\n";
	echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table class=\"outer\" align=\"center\" width=\"700px\" border=0>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" colspan=\"5\" valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=0>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"5\" class=\"gray\" align=\"left\"><b>Retail Estimate for $rowC[1] Office</b> (EstID: <b>$estidret</b>)</td>\n";
	echo "               <td class=\"gray\" align=\"right\">\n";
	
	if ($rowD[3]!=0)
	{
		if ($rowpreA[29]==1)
		{
			echo "               <b>Renovation:</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\" CHECKED>\n";	
		}
		else
		{
			echo "               <b>Renovation:</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\">\n";	
		}
	}
	else
	{
		echo "&nbsp";
	}
	
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"3\" align=\"left\"><b>Customer:</b> $rowI[1] $rowI[2]</td>\n";
	echo "               <td align=\"left\"><b>Perimeter:</b> $ps1  <b>Surface:</b> $ps2  <b>Shallow:</b> $rowpreA[13]  <b>Middle:</b> $rowpreA[14]  <b>Deep:</b> $rowpreA[15]</td>\n";
	echo "               <td colspan=\"2\" class=\"gray\" valign=\"bottom\" align=\"right\">\n";
	echo "                  SalesRep: \n";
	echo "                  <b>$rowD[1] $rowD[2]</b>\n";
	echo "               </td>\n";
	//echo "               <td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td colspan=\"5\" class=\"gray\" align=\"right\" NOWRAP>\n";
	echo "         <input class=\"buttondkgry\" type=\"submit\" value=\"Update Items\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td colspan=\"5\" class=\"gray\"NOWRAP>\n";
	echo "         <table border=1 class=\"inner_borders\" width=\"100%\">\n";
	echo "         <tr>\n";
	echo "            <td class=\"wh\" colspan=\"5\" valign=\"top\">\n";

	$ecnt=1;
	foreach ($catarray as $n=>$v)
	{
		$qryJ = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		if ($rowJ[0]!=0)
		{
			if ($ecnt==count($catarray))
			{
				echo "<a href=\"#".$rowJ[0]."\">".$rowJ[1]."</a>";
			}
			else
			{
				echo "<a href=\"#".$rowJ[0]."\">".$rowJ[1]."</a> - ";
			}
			$ecnt++;
		}
	}

	echo "            </td>\n";
	echo "         </tr>\n";

	// POOL RETAIL ACC ITEM Loop
	foreach ($catarray as $n=>$v)
	{
		$qryJ = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		if ($v!=0)
		{
			echo "         <tr>\n";
			echo "            <td class=\"wh\" colspan=\"4\" align=\"left\" valign=\"top\"><input type=\"hidden\" name=\"#".$rowJ[0]."\"><b>".$rowJ[1]."</b></td>\n";
			echo "            <td class=\"wh\" align=\"right\" valign=\"top\">&nbsp<a href=\"#Top\">Up</a></td>\n";
			echo "         </tr>\n";
			echo "         <tr>\n";
			echo "            <td colspan=\"5\" class=\"gray\" valign=\"top\">\n";

			$qryM  = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."' AND disabled!=1 ORDER BY seqn;";
			$resM  = mssql_query($qryM);
			$nrowM = mssql_num_rows($resM);

			$qcnt=0;

			while ($rowM=mssql_fetch_row($resM))
			{
				$qcnt++;
				//form_element_ACC($rowM[0],$nrowM);

				if ($qcnt==1)
				{
					form_element_ACC($rowM[0],1,$rowpreD[0],$type);
				}
				elseif ($qcnt==$nrowM)
				{
					form_element_ACC($rowM[0],2,$rowpreD[0],$type);
				}
				else
				{
					form_element_ACC($rowM[0],0,$rowpreD[0],$type);
				}
			}

			echo "                 </td>\n";
			echo "         </tr>\n";
		}
	}

	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</form>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function viewest_cost()
{
	if (!isset($_SESSION['viewarray']))
	{
		echo "Fatal Error: Job Cost variables not set!";
		exit;
	}
	
	//print_r($_SESSION['viewarray'])."<br>";
	//print_r($_SESSION)."<br>";
	
	$MAS		=$_SESSION['pb_code'];
	global 		$bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate;

	$viewarray	=$_SESSION['viewarray'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	$estid		=$viewarray['estid'];

	if (!isset($estid)||$estid=='')
	{
		echo "Fatal Error: var estid not set!";
		exit;
	}

	//print_r($_SESSION['viewarray'])."<br>";
	$qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,comadj,sidm,buladj,applyov,applybu,refto,apft,added,updated,updateby FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_row($respreA);
	
	$qrypreAa = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND estid='".$rowpreA[0]."';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$jsecurityid =$rowpreA[17];

	$qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_row($respreD);

	$ps1        =$rowpreA[1];
	$ps2        =$rowpreA[2];
	$spa1       =$rowpreA[3];
	$spa2       =$rowpreA[4];
	$spa3       =$rowpreA[5];
	$tzone      =$rowpreA[6];
	$contractamt=$rowpreA[7];
	$cfname     =$rowpreA[8];
	$clname     =$rowpreA[9];
	$phone      =$rowpreA[10];
	$status     =$rowpreA[11];
	$ps5        =$rowpreA[13];
	$ps6        =$rowpreA[14];
	$ps7        =$rowpreA[15];
	
	if (isset($viewarray['acctotal'])||$viewarray['acctotal']!=0)
	{
		$acctotal=$viewarray['acctotal'];
	}
	else
	{
		$acctotal=0;
	}

	$qryC = "SELECT officeid,name,stax,sm,gm,psched,psched_perc,pft_sqft FROM offices WHERE officeid='".$officeid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[7]=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='$defmeas';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$jsecurityid."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$rowpreA[16]."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_row($resL);

	if ($rowpreA[31]!=0)
	{
		$qryM = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowpreA[31]."';";
		$resM = mssql_query($qryM);
		$rowM = mssql_fetch_array($resM);

		$lupdatestr=$rowM['fname']." ".$rowM['lname'];
	}
	else
	{
		$lupdatestr="";
	}

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ 	= "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ 	= mssql_query($qryJ);
		$rowJ 	= mssql_fetch_row($resJ);

		$taxrate	=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);

		$viewarray['taxrate']	=$taxrate[1];
		$viewarray['tax']			=$viewarray['camt']*$taxrate[1];
		$viewarray['were']		="from Dynamic";
	}

	if (!empty($rowpreA[29]))
	{
		$atime=date("m-d-Y", strtotime($rowpreA[29]));
	}
	else
	{
		$atime="";
	}

	if (!empty($rowpreA[30]))
	{
		$utime=date("m-d-Y", strtotime($rowpreA[30]));
	}
	else
	{
		$utime="";
	}

	$set_ia		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$set_gals	=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
	$estidret   =$rowpreA[0];
	$vdiscnt    =$viewarray['discount'];
	$pbaseprice =$rowB[2];
	$bcomm      =$rowB[3];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	$brdr=0;

	//print_r($viewarray);
	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"$estidret\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
	echo "<input type=\"hidden\" name=\"discount\" value=\"$vdiscnt\">\n";
	echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"2\" align=\"right\" >\n";
	echo "                  <table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
	/*
	echo "                     <tr>\n";
	echo "								<td class=\"gray\" align=\"right\"></td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Date Added:</b>&nbsp</td>\n";
	echo "								<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"".$atime."\"></td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Date Updated:</b>&nbsp</td>\n";
	echo "								<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"".$utime."\"></td>\n";
	echo "                     </tr>\n";
	*/
	echo "                     <tr>\n";
	echo "								<td colspan=\"3\" class=\"gray\" align=\"left\" valign=\"top\" NOWRAP>&nbsp<b>Cost Estimate #<font color=\"red\">".$estidret."</font> for ".$rowC[1]."</b></td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>SalesRep</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowD[1]." ".$rowD[2]."\">\n";
	echo "                        </td>\n";
	echo "                        <td class=\"gray\" align=\"right\"><b>Sales Manager</b></td>\n";
	echo "                        <td class=\"gray\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowL[1]." ".$rowL[2]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "								<td class=\"gray\" align=\"right\"></td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Date Added:</b>&nbsp</td>\n";
	echo "								<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"".$atime."\"></td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Date Updated:</b>&nbsp</td>\n";
	echo "								<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"".$utime."\"></td>\n";
	echo "								<td class=\"gray\" align=\"right\"><b>Last Update by:</b>&nbsp</td>\n";
	echo "								<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"".$lupdatestr."\"></td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"50%\">\n";

	//	Customer Display Start
	cinfo_display($viewarray['cid'],$rowC[2]);
	// Customer Display End

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"50%\">\n";

	// Pool Display Start
	pool_detail_display($estid);
	// Pool Display End

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"bottom\" align=\"left\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "         <input class=\"buttondkgrypnl\" type=\"submit\" value=\"View Retail\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</form>\n";
	
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"100%\">\n";

	//	Bids Rollup Display
	costadj_rollup_disp($_SESSION['officeid'],$estid,0,"e");
	
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	echo "         <table width=\"100%\" bordercolor=\"black\" border=1>\n";

	calcbyphsL($rowpreD[0],0,0,0);
	$bccost  =$bctotal;
	$fbccost =number_format(round($bccost), 2, '.', '');

	echo "           <tr>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"5\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
	}

	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fbccost."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	//echo "         <div class=\"pagebreak\">\n";
	//echo "         <hr width=\"100%\">\n";
	echo "         <br>\n";
	echo "         <table width=\"100%\" bordercolor=\"black\" border=1>\n";

	calcbyphsM($rowpreD[0],0,0);
	$bmcost  =$bmtotal;
	$fbmcost =number_format(round($bmcost), 2, '.', '');

	echo "           <tr>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"5\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
	}

	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fbmcost."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";

	echo "         <br>\n";

	// Total Table
	$custallow	=$viewarray['custallow'];
	$tcustallow	=$custallow*-1;
	$tcontract	=0;
	$tcontract	=$viewarray['camt'];
	$tbcost		=round($bccost+$bmcost);

	if ($rowC[2]==1)
	{
		$tax			=$tcontract*$taxrate[1];
		//$tax			=round($tax);
		$tcontract	=$tcontract+$tax;
	}

	if ($tcustallow != 0)
	{
		$tadjcontract	=$tcontract+$tcustallow;
	}
	else
	{
		$tadjcontract	=$tcontract;
	}

	if ($tcustallow != 0)
	{
		$tadjbcost		=round($tbcost+$tcustallow);
	}
	else
	{
		$tadjbcost		=round($tbcost);
	}

	//$tgross		=$tbcost;

	if ($tcustallow != 0)
	{
		$tprofit		=$tadjcontract-$tadjbcost;
	}
	else
	{
		$tprofit		=$tcontract-$tbcost;
	}

	if ($tcontract!=0)
	{
		if ($tcustallow != 0)
		{
			$netper  =$tprofit/$tadjcontract;
		}
		else
		{
			$netper  =$tprofit/$tcontract;
		}
	}
	else
	{
		$netper  =0;
	}

	$ftcustallow		=number_format($tcustallow, 2, '.', '');
	$ftcontract 		=number_format($tcontract, 2, '.', '');
	$ftadjcontract 	=number_format($tadjcontract, 2, '.', '');
	$ftbcost				=number_format($tbcost, 2, '.', '');
	$ftadjbcost			=number_format($tadjbcost, 2, '.', '');
	$ftprofit			=number_format($tprofit, 2, '.', '');
	$fnetper 			=round($netper, 2)*100;

	echo "         <table width=\"100%\" bordercolor=\"black\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"left\"><b>Totals</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Retail Contract Price</b></td>\n";
	echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftcontract."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Adjusted Contract Price</b></td>\n";
		echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftadjcontract."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Construction Total</b></td>\n";
	echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftbcost."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Adjusted Construction Total</b></td>\n";
		echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftadjbcost."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Net</b></td>\n";
	echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$ftprofit."</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Net %</b></td>\n";
	echo "              <td NOWRAP width=\"65\" class=\"wh\" align=\"right\"><b>".$fnetper."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\">\n";
	echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";
	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"print\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$securityid."\">\n";
	//echo "<input type=\"hidden\" name=\"tcomm\" value=\"".$viewarray['comt']."\">\n";
	echo "<input type=\"hidden\" name=\"discount\" value=\"$vdiscnt\">\n";
	echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	//echo "<input type=\"hidden\" name=\"acctotal\" value=\"".$_REQUEST['acctotal']."\">\n";
	echo "<input type=\"hidden\" name=\"showtotals\" value=\"1\">\n";
	echo "            <tr>\n";
	echo "               <td align=\"left\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "                  <input class=\"buttondkgrypnl\" type=\"submit\" value=\"View Totals\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "</form>\n";
	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"print\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$securityid."\">\n";
	//echo "<input type=\"hidden\" name=\"tcomm\" value=\"".$viewarray['comt']."\">\n";
	echo "<input type=\"hidden\" name=\"discount\" value=\"$vdiscnt\">\n";
	echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	//echo "<input type=\"hidden\" name=\"acctotal\" value=\"".$_REQUEST['acctotal']."\">\n";
	echo "            <tr>\n";
	echo "               <td align=\"left\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "                  <input class=\"buttondkgrypnl\" type=\"submit\" value=\"Print View\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "               </td>\n";
	echo "</form>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	
	$_SESSION['viewarray']=$viewarray;
}

1;
?>