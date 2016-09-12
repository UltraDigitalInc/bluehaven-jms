<?php

function role_display($slev)
{
	echo $slev;
}

function pad_update()
{
	//echo 'Saving';
	error_reporting(E_ALL);
	$err=0;
	$qryA = "SELECT securityid,officeid,fname,lname,login,startpage,slevel,srep FROM jest..security WHERE securityid='".$_REQUEST['userid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	$qryB = "SELECT * FROM jest..base_price_pad WHERE sid='".$rowA['securityid']."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
	$nrowB= mssql_num_rows($resB);
	
	if (isset($_REQUEST['bpadjust']))
	{
		if ($nrowB > 0)
		{
			$qryBa = "DELETE FROM jest..base_price_pad WHERE sid='".$rowA['securityid']."';";
			$resBa = mssql_query($qryBa);
		}
		
		$qryC = "INSERT INTO jest..base_price_pad (oid,sid,adj_price,udateby) VALUES (".$_SESSION['officeid'].",".$rowA['securityid'].",cast('".number_format($_REQUEST['bpadjust'], 2, '.', '')."' as money),".$_SESSION['securityid'].");";
		$resC = mssql_query($qryC);
	}
	else
	{
		echo 'Error. No Base Price Adjust Amount!<br>';
	}
	
	pad_display();
}

function pad_item_update()
{
	$qryC  = "update jest..[".$_SESSION['pb_code']."acc] set ";
	$qryC .= "atrib2='".$_REQUEST['iauto']."', ";
	$qryC .= "disabled=".$_REQUEST['iactive']." ";
	$qryC .= "WHERE id=".$_REQUEST['iid'].";";
	//echo $qryC.'<br>';
	$resC = mssql_query($qryC);
	
	pad_display();
}

function pad_item_add()
{
	error_reporting(E_ALL);
	$err=0;
	$qryA = "SELECT securityid,officeid,fname,lname,login,startpage,slevel,srep FROM jest..security WHERE securityid='".$_REQUEST['userid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	$qryB = "SELECT * FROM jest..[".$_SESSION['pb_code']."acc] WHERE phsid='".$rowA['securityid']."' and atrib1='".trim($_REQUEST['uid'])."';";
	$resB = mssql_query($qryB);
	$nrowB= mssql_num_rows($resB);
	
	//echo $qryB.'<br>';
	
	if ($nrowB==0 && isset($_REQUEST['iamount']) && $_REQUEST['iamount']!=0)
	{
		//echo 'Saving<br>';
		$qryC = "INSERT INTO jest..[".$_SESSION['pb_code']."acc] (officeid,phsid,catid,qtype,atrib1,atrib2,disabled,item,rp,commtype,crate,usecid) VALUES ";
		$qryC .= "(".$_SESSION['officeid'].",".$rowA['securityid'].",".$_REQUEST['catid'].",1,'".$_REQUEST['uid']."','".$_REQUEST['iauto']."',".$_REQUEST['iactive'].", ";
		$qryC .= " '".trim($_REQUEST['iitem'])."',cast('".number_format($_REQUEST['iamount'], 2, '.', '')."' as money), ";
		$qryC .= " ".$_REQUEST['ctype'].", ".$_REQUEST['crate'].", ".$_SESSION['securityid']."); ";
		$resC = mssql_query($qryC);
		//echo $qryC.'<br>';
	}
	
	pad_display();
}

function pad_display()
{
	//ini_set('display_errors','On');
	error_reporting(E_ALL);
	
	//include('calc_func_quote.php');
	load_pricebook_data();
	$err=0;
	$qryA = "SELECT securityid,officeid,fname,lname,login,startpage,slevel,srep FROM jest..security WHERE securityid='".$_REQUEST['userid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if ($_SESSION['tlev'] < 9 && $rowA['srep'] != 1)
	{
		echo 'Configuration Error.<br> Contact Management to be enabled for Price Pad.<br>';
		exit;
	}
	else
	{
		$qryB = "SELECT * FROM jest..base_price_pad WHERE sid='".$rowA['securityid']."';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);
		$nrowB= mssql_num_rows($resB);
		
		if ($nrowB > 1)
		{
			$tempadjamt='Error';
			$err++;
		}
		elseif ($nrowB == 1)
		{
			$tempadjamt=$rowB['adj_price'];
		}
		else
		{
			$tempadjamt=0;
		}
		
		$qryC = "SELECT * FROM jest..[".$_SESSION['pb_code']."acc] WHERE phsid='".$rowA['securityid']."';";
		$resC = mssql_query($qryC);
		$nrowC= mssql_num_rows($resC);
		
		$qryD = "SELECT id,catid FROM jest..AC_Cats WHERE officeid='".$_SESSION['officeid']."' and active=1 and privcat=1;";
		$resD = mssql_query($qryD);
		$rowD = mssql_fetch_array($resD);
		$nrowD= mssql_num_rows($resD);
		
		//echo "<div name=\"pbadj1\"><b>Pricebook Adjustments</b></div>\n";
		echo "	<script language=\"Javascript\" type=\"text/javascript\" src=\"js/PB_adj_qtips.js\"></script>\n";
		echo "	<table align=\"center\" width=\"750\">\n";
		echo "		<tr>\n";
		echo "			<td colspan=\"2\">\n";
		echo "				<table class=\"outer\" width=\"100%\" align=\"right\">\n";
		echo "					<tr>\n";
		echo "                      <td class=\"gray\" align=\"left\"><b>Blue Haven Pricebook Adjustments</b></td>\n";
		echo "                      <td class=\"gray\" align=\"center\"></td>\n";
		echo "                      <td class=\"gray\" align=\"right\"><b>".$rowA['fname']." ".$rowA['lname']."</b></td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td valign=\"top\" width=\"20%\">\n";
		echo "				<form name=\"setpad\" method=\"post\">\n";
		echo "				<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "				<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "				<input type=\"hidden\" name=\"subq\" value=\"ppu\">\n";
		echo "				<input type=\"hidden\" name=\"userid\" value=\"".$rowA['securityid']."\">\n";
		echo "				<table class=\"outer\" width=\"100%\" align=\"right\">\n";
		echo "					<tr>\n";
		echo "                      <td class=\"ltgray_und\" align=\"left\"><b>Base Price Adjust</b></td>\n";
		echo "                      <td class=\"ltgray_und\" align=\"right\" width=\"20\"><div id=\"bpadj1\"><img src=\"images/help.png\"></div></td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "                      <td class=\"gray\" colspan=\"2\">\n";
		echo "							<table width=\"100%\" align=\"right\">\n";
		echo "								<tr>\n";
		echo "                      			<td class=\"gray\" align=\"right\">\n";
		echo "										<input class=\"bbox\" type=\"text\" size=\"10\" name=\"bpadjust\" value=\"".number_format($tempadjamt, 2, '.', '')."\">\n";
		echo "									</td>\n";
		echo "                      			<td class=\"gray\" align=\"right\" valin=\"top\">\n";
		
		if ($err > 0)
		{
			echo "										<input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Save Pad Info\" DISABLED>\n";
		}
		else
		{
			echo "										<input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Save Pad Info\">\n";
		}
		
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		
		echo "</form>\n";
		
		echo "			</td>\n";
		echo "			<td valign=\"top\" width=\"80%\">\n";
		echo "				<table class=\"outer\" border=1 cellspacing=0 width=\"100%\">\n";
		echo "					<tr>\n";
		echo "						<td class=\"ltgray_und\"><b>Pricebook Adjustments</b></td>\n";
		echo "                      <td class=\"ltgray_und\" align=\"right\"><div id=\"pbadj1\"><img src=\"images/help.png\"></div></td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<td colspan=\"2\" class=\"gray\">\n";
		echo "							<iframe name=\"PBPadSelect\" id=\"frmPBPadSelect\" src=\"subs/pb_adj_select.php\" frameborder=\"0\" width=\"100%\" height=\"600\" align=\"center\"></iframe>\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		
		if ($nrowD == 0)
		{
			echo "		<tr>\n";
			echo "			<td colspan=\"2\" valign=\"top\" width=\"100%\">\n";
		
		//echo $qryD.'<br>';
		/*if ($nrowD == 0)
		{
			echo "				<table class=\"outer\" width=\"100%\" align=\"right\">\n";
			echo "					<tr>\n";
			echo "                      <td class=\"gray\" align=\"left\">No Category Configured. Contact Management for Assistance</td>\n";
			echo "					</tr>\n";
			echo "				</table>\n";
			
			die();
		}
		else
		{*/
			echo "				<form name=\"addadj\" method=\"post\">\n";
			echo "				<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "				<input type=\"hidden\" name=\"call\" value=\"users\">\n";
			echo "				<input type=\"hidden\" name=\"subq\" value=\"ppia\">\n";
			echo "				<input type=\"hidden\" name=\"catid\" value=\"".$rowD['catid']."\">\n";
			echo "				<input type=\"hidden\" name=\"userid\" value=\"".$rowA['securityid']."\">\n";
			echo "				<input type=\"hidden\" name=\"qtype\" value=\"1\">\n"; // Defaults to Checkbox Item
			echo "				<input type=\"hidden\" name=\"ctype\" value=\"1\">\n";
			echo "				<input type=\"hidden\" name=\"crate\" value=\".05\">\n";
			echo "				<input type=\"hidden\" name=\"uid\" value=\"". md5(session_id().time().".".$_SESSION['securityid']) ."\">\n";
			echo "				<table class=\"outer\" width=\"750\">\n";
			echo "					<tr>\n";
			echo "                      <td class=\"ltgray_und\" colspan=\"4\" align=\"left\"><b>Personal PriceBook Items</b></td>\n";
			echo "                      <td class=\"ltgray_und\" align=\"right\" width=\"20\"><div id=\"ppadj1\"><img src=\"images/help.png\"></div></td>\n";
			echo "					</tr>\n";
			echo "					<tr>\n";
			echo "                      <td class=\"ltgray_und\" align=\"left\"><b>Description</b></td>\n";
			echo "                      <td class=\"ltgray_und\" align=\"center\"><b>Auto Add</b></td>\n";
			echo "                      <td class=\"ltgray_und\" align=\"left\"><b>Active</b></td>\n";
			echo "                      <td class=\"ltgray_und\" align=\"center\"><b>Amount</b></td>\n";
			echo "                      <td class=\"ltgray_und\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
			echo "					</tr>\n";
			echo "					<tr>\n";
			echo "                      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
			echo "							<textarea name=\"iitem\" cols=\"35\" rows=\"2\"></textarea>\n";
			echo "						</td>\n";
			echo "                      <td class=\"gray\" align=\"center\" valign=\"bottom\">\n";
			echo "							<select name=\"iauto\">\n";
			echo "								<option value=\"0\">Off</option>\n";
			echo "								<option value=\"1\">On</option>\n";
			echo "							</select>\n";
			echo "						</td>\n";
			echo "                      <td class=\"gray\" align=\"center\" valign=\"bottom\">\n";
			echo "							<select name=\"iactive\">\n";
			echo "								<option value=\"0\">Yes</option>\n";
			echo "								<option value=\"1\">No</option>\n";
			echo "							</select>\n";
			echo "						</td>\n";
			echo "                      <td class=\"gray\" align=\"right\" valign=\"bottom\">\n";
			echo "							<input class=\"bbox\" type=\"text\" name=\"iamount\" size=\"10\" value=\"0.00\">\n";
			echo "						</td>\n";
			echo "                      <td class=\"gray\" align=\"right\" valign=\"bottom\">\n";
			echo "							<input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Save Item\">\n";
			echo "						</td>\n";
			echo "					</tr>\n";
			echo "				</table>\n";
			echo "</form>\n";	
			echo "			</td>\n";
			echo "		</tr>\n";
			
			if ($nrowC > 0)
			{
				echo "		<tr>\n";
				echo "			<td colspan=\"2\" valign=\"top\" width=\"100%\">\n";
				echo "				<table class=\"outer\" width=\"100%\">\n";
				echo "					<tr>\n";
				echo "                      <td class=\"ltgray_und\" align=\"left\"><b>Date</b></td>\n";
				echo "                      <td class=\"ltgray_und\" align=\"left\"><b>Description</b></td>\n";
				echo "                      <td class=\"ltgray_und\" align=\"center\"><b>Auto Add</b></td>\n";
				echo "                      <td class=\"ltgray_und\" align=\"center\"><b>Active</b></td>\n";
				echo "                      <td class=\"ltgray_und\" align=\"center\"><b>Price</b></td>\n";
				echo "                      <td class=\"ltgray_und\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
				echo "					</tr>\n";
				
				while ($rowC = mssql_fetch_array($resC))
				{
					echo "<form name=\"addadj\" method=\"post\">\n";
					echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
					echo "<input type=\"hidden\" name=\"call\" value=\"users\">\n";
					echo "<input type=\"hidden\" name=\"subq\" value=\"ppiu\">\n";
					echo "<input type=\"hidden\" name=\"iid\" value=\"".$rowC['id']."\">\n";
					echo "<input type=\"hidden\" name=\"catid\" value=\"".$rowC['catid']."\">\n";
					echo "<input type=\"hidden\" name=\"userid\" value=\"".$rowA['securityid']."\">\n";
					echo "					<tr>\n";
					echo "                      <td class=\"wh_und\" align=\"left\">\n";
					
					echo date('m/d/y',strtotime($rowC['added']));
					
					echo "						</td>\n";
					echo "                      <td class=\"wh_und\" align=\"left\">\n";
					echo trim($rowC['item']);
					echo "						</td>\n";
					echo "                      <td class=\"wh_und\" align=\"center\">\n";
					echo "							<select name=\"iauto\">\n";
					
					if ($rowC['atrib2']=='0')
					{
						echo "								<option value=\"0\" SELECTED>Off</option>\n";
						echo "								<option value=\"1\">On</option>\n";
					}
					else
					{
						echo "								<option value=\"0\">Off</option>\n";
						echo "								<option value=\"1\" SELECTED>On</option>\n";
					}
					
					echo "							</select>\n";					
					echo "						</td>\n";
					echo "                      <td class=\"wh_und\" align=\"center\">\n";
					echo "							<select name=\"iactive\">\n";
					
					if ($rowC['disabled']==1)
					{
						echo "								<option value=\"0\">Yes</option>\n";
						echo "								<option value=\"1\" SELECTED>No</option>\n";
					}
					else
					{
						echo "								<option value=\"0\" SELECTED>Yes</option>\n";
						echo "								<option value=\"1\">No</option>\n";
					}
					
					echo "							</select>\n";					
					echo "						</td>\n";
					echo "                      <td class=\"wh_und\" align=\"center\">\n";
					echo "							<input class=\"bbox\" type=\"text\" name=\"iamount\" size=\"10\" value=\"".number_format($rowC['rp'], 2, '.', '')."\">\n";
					echo "						</td>\n";
					echo "                      <td class=\"wh_und\" align=\"right\">\n";
					echo "							<input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Save Item\">\n";
					echo "						</td>\n";
					echo "					</tr>\n";
					echo "</form>\n";
				}
				
				echo "				</table>\n";
			}
		
			echo "			</td>\n";
			echo "		</tr>\n";
		
		}
		
		echo "	</table>\n";
	}
}

function maintsecelements($array)
{
	if (is_array($array))
	{
		$elemcnt=9;

		echo "<table width=\"100%\">\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_plev\">\n";

		$e=0;
		while ($e <= $elemcnt)
		{

			if ($e==$array[0])
			{
				echo "         <option value=\"$e\" SELECTED>$e</option>\n";
			}
			else
			{
				echo "         <option value=\"$e\">$e</option>\n";
			}
			$e++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">Pricebook</td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_llev\">\n";

		$l=0;
		while ($l <= $elemcnt)
		{

			if ($l==$array[1])
			{
				echo "         <option value=\"$l\" SELECTED>$l</option>\n";
			}
			else
			{
				echo "         <option value=\"$l\">$l</option>\n";
			}
			$l++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">Leads</td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_ulev\">\n";

		$r=0;
		while ($r <= $elemcnt)
		{

			if ($r==$array[2])
			{
				echo "         <option value=\"$r\" SELECTED>$r</option>\n";
			}
			else
			{
				echo "         <option value=\"$r\">$r</option>\n";
			}
			$r++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">User/Office</td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_mlev\">\n";

		$m=0;
		while ($m <= $elemcnt)
		{

			if ($m==$array[3])
			{
				echo "         <option value=\"$m\" SELECTED>$m</option>\n";
			}
			else
			{
				echo "         <option value=\"$m\">$m</option>\n";
			}
			$m++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">Messages</td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_tlev\">\n";

		$t=0;
		while ($t <= $elemcnt)
		{

			if ($t==$array[4])
			{
				echo "         <option value=\"$t\" SELECTED>$t</option>\n";
			}
			else
			{
				echo "         <option value=\"$t\">$t</option>\n";
			}
			$t++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">Reserved</td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function resetuserpword()
{
	if (!isset($_REQUEST['state'])||empty($_REQUEST['state']))
	{
		error_reporting(E_ALL);
		
		$userid=(isset($_REQUEST['userid']) && $_REQUEST['userid']!=0)?$_REQUEST['userid']:$_SESSION['securityid'];
		
		$qryA = "SELECT securityid,officeid,fname,lname,login,startpage,slevel,returntolist,searchlandingpage,tester,testerenable FROM jest..security WHERE securityid=".(int) $userid.";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		$qryB = "SELECT * FROM jest..tstartpage WHERE active=1 and slevel <= ".substr($rowA['slevel'],12,1)." order by sname asc;";
		$resB = mssql_query($qryB);

		//echo $qryB."<br>";
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"rp\">\n";
		echo "<input type=\"hidden\" name=\"state\" value=\"setopts\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"userid\" value=\"".$rowA['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$rowA['officeid']."\">\n";
		
		echo "<div class=\"outerrnd\" style=\"width:300px\">\n";
		echo "<table align=\"center\" width=\"300px\">\n";
		echo "<tr class=\"tblhd\">\n";
		echo "   <td colspan=\"2\" align=\"left\"><b>Account Options</b></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td align=\"right\"><b>Login ID</b></td>\n";
		echo "   <td align=\"left\"><b>".$rowA['login']."</b></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td align=\"right\"><b>First Name</b></td>\n";
		echo "   <td align=\"left\">".$rowA['fname']."</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td align=\"right\"><b>Last Name</b></td>\n";
		echo "   <td align=\"left\">".$rowA['lname']."</td>\n";
		echo "</tr>\n";		
		echo "<tr>\n";
		echo "   <td align=\"right\"><b>Search Landing Page</b></td>\n";
		echo "   <td align=\"left\">\n";
		echo "		<select class=\"JMStooltip\" name=\"searchlandingpage\" title=\"Sets the default page to load when clicking on Customer in Search Results\">\n";
		
		if ($rowA['searchlandingpage']==1)
		{
			echo "			<option value=\"0\">Lead View</option>\n";
			echo "			<option value=\"1\" SELECTED>OneSheet</option>\n";
		}
		else
		{
			echo "			<option value=\"0\" SELECTED>Lead View</option>\n";
			echo "			<option value=\"1\">OneSheet</option>\n";
		}
		
		echo "		</select>\n"; 
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td align=\"right\"><b>Lead Update</b></td>\n";
		echo "   <td align=\"left\">\n";
		echo "		<select class=\"JMStooltip\" name=\"returntolist\" title=\"Sets the default action taken after Updating a Lead\">\n";
		
		if ($rowA['returntolist']==1)
		{
			echo "			<option value=\"0\">Return to Lead</option>\n";
			echo "			<option value=\"1\" SELECTED>Return to List</option>\n";
		}
		else
		{
			echo "			<option value=\"0\" SELECTED>Return to Lead</option>\n";
			echo "			<option value=\"1\">Return to List</option>\n";
		}
		
		echo "		</select>\n"; 
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td align=\"right\" colspan=\"2\"><button class=\"btnsysmenu\">Update</button></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "</form>\n";
		echo "<p>";
		
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"rp\">\n";
		echo "<input type=\"hidden\" name=\"state\" value=\"resetpwd\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"userid\" value=\"".$rowA['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$rowA['officeid']."\">\n";
		
		echo "<div class=\"outerrnd\" style=\"width:300px\">\n";
		echo "<table align=\"center\" width=\"300px\">\n";
		echo "<tr class=\"tblhd\">\n";
		echo "   <td colspan=\"2\" align=\"left\"><b>Change Password</b></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td align=\"right\"><b>New Password</b></td>\n";
		echo "   <td align=\"left\"><input class=\"bboxb\" type=\"password\" name=\"p1\" size=\"10\" maxlength=\"8\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td align=\"right\"><b>Verify New Password</b></td>\n";
		echo "   <td align=\"left\"><input class=\"bboxb\" type=\"password\" name=\"p2\" size=\"10\" maxlength=\"8\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td align=\"right\" colspan=\"2\"><button class=\"btnsysmenu\">Update</button></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "</form>\n";
		
		if (isset($rowA['testerenable']) and $rowA['testerenable']!=0) {
			echo "<form method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"users\">\n";
			echo "<input type=\"hidden\" name=\"subq\" value=\"rp\">\n";
			echo "<input type=\"hidden\" name=\"state\" value=\"testaccess\">\n";
			echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
			echo "<input type=\"hidden\" name=\"userid\" value=\"".$rowA['securityid']."\">\n";
			echo "<input type=\"hidden\" name=\"officeid\" value=\"".$rowA['officeid']."\">\n";
			echo "<p>";
			echo "<div class=\"outerrnd\" style=\"width:300px\">\n";
			echo "<table align=\"center\" width=\"300px\">\n";
			echo "	<tr class=\"tblhd\">\n";
			echo "		<td colspan=\"2\" align=\"left\"><b>Tester Access</b></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td align=\"right\"><b>Enabled</b></td>\n";
			echo "		<td align=\"left\">\n";
			echo "			<select name=\"tester\" title=\"Turns Tester Access On and Off\">\n";
			
			if (isset($rowA['tester']) and $rowA['tester']==1) {
				echo "			<option value=\"1\" SELECTED>Yes</option>\n";
				echo "			<option value=\"0\">No</option>\n";
			}
			else {
				echo "			<option value=\"1\">Yes</option>\n";
				echo "			<option value=\"0\" SELECTED>No</option>\n";
			}
			
			echo "			</select>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td align=\"right\" colspan=\"2\"><button class=\"btnsysmenu\">Update</button></td>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			echo "</div>\n";
			echo "</form>\n";
		}
	}
	elseif ($_REQUEST['state']=='resetpwd')
	{
		$userid=(isset($_REQUEST['userid']) && $_REQUEST['userid']!=0)?$_REQUEST['userid']:null;
		
		if (!is_null($userid)) {
			$qryA = "SELECT securityid,fname,lname,login,pswd,logstate,startpage,tester FROM security WHERE securityid=".(int) $userid.";";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
	
			if ($_REQUEST['p1']!=$_REQUEST['p2'])
			{
				die ("<b>Password Fields do not match. Click BACK and try Again.</b>");
			}
			elseif ($_REQUEST['p1']==$rowA[3]||$_REQUEST['p2']==$rowA[3])
			{
				die ("<b>Invalid Password (Cannot match Login ID). Click BACK and try Again.</b>");
			}
			elseif (strlen($_REQUEST['p1']) < 4||strlen($_REQUEST['p2'])<4)
			{
				die ("<b>Password is too Short. Password must be between 4 and 8 characters. Click BACK and try Again.</b>");
			}
			elseif (strlen($_REQUEST['p1']) > 8||strlen($_REQUEST['p2']) > 8)
			{
				die ("<b>Password is too Long. Password must be between 4 and 8 characters. Click BACK and try Again.</b>");
			}
			else
			{
				$npswd= md5($_REQUEST['p2']);
				if ($npswd==$rowA[4]||$npswd==$rowA[4])
				{
					die ("<b>Invalid Password (Cannot match Current Password). Click BACK and try Again.</b>");
				}
				else
				{
					$qryB = "UPDATE security SET pswd='".$npswd."',passchg=getdate(),passchgid='".$_SESSION['securityid']."' WHERE securityid='".$rowA[0]."'";
					$resB = mssql_query($qryB);
	
					echo "Password has been <font color=\"red\"><b>Changed</b></font> for ".$rowA[1]." ".$rowA[2].". <br> Please use the new password the next time you log into the JMS";
				}
			}
		}
		else {
			echo 'Transition Error';
		}
	}
	elseif ($_REQUEST['state']=='setopts')
	{
		$userid=(isset($_REQUEST['userid']) && $_REQUEST['userid']!=0)?$_REQUEST['userid']:null;
		
		if (!is_null($userid)) {
			$qryA = "SELECT securityid,fname,lname,login,pswd,logstate,startpage,tester FROM security WHERE securityid=".(int) $userid.";";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			$nrowA = mssql_num_rows($resA);
	
			if ($nrowA > 0)
			{
				$qryB = "UPDATE security SET returntolist=".$_REQUEST['returntolist'].",searchlandingpage='".$_REQUEST['searchlandingpage']."' WHERE securityid=".$rowA[0].";";
				$resB = mssql_query($qryB);
				
				if ($_SESSION['securityid']==269999999999999999999999)
				{
					echo $qryB.'<br>';	
				}
	
				echo "<b>Account Settings Updated</b><br>";
			}
		}
		else {
			echo 'Transition Error';
		}
	}
	elseif ($_REQUEST['state']=='testaccess')
	{
		$userid=(isset($_REQUEST['userid']) && $_REQUEST['userid']!=0)?$_REQUEST['userid']:null;
		
		if (!is_null($userid)) {
			$qryA = "SELECT securityid,fname,lname,login,pswd,logstate,startpage,tester FROM security WHERE securityid=".(int) $userid.";";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			$nrowA = mssql_num_rows($resA);
	
			if ($nrowA > 0){
				$qryB = "UPDATE security SET tester=".$_REQUEST['tester']." WHERE securityid=".$rowA[0].";";
				$resB = mssql_query($qryB);
	
				echo "<b>Test Access Updated</b><br>";
			}
		}
		else {
			echo 'Transition Error';
		}
	}
}

function updateuser()
{
	error_reporting(E_ALL);
	
	//show_post_vars();
	$qry0 = "SELECT * FROM security WHERE securityid='".$_REQUEST['userid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$oseclevel=explode(',',$row0['slevel']);

	if (!empty($_REQUEST['masid']))
	{
		$pmasid=explode(":",$_REQUEST['masid']);
	}
	else
	{
		$pmasid=array(0=>0,1=>0);
	}
	
	if (!empty($_REQUEST['rmasid']))
	{
		$rpmasid=explode(":",$_REQUEST['rmasid']);
	}
	else
	{
		$rpmasid=array(0=>0,1=>0);
	}
	
	if (!empty($_REQUEST['salesrep']) && $_REQUEST['salesrep']==1)
	{
		$sr=$_REQUEST['salesrep'];
	}
	else
	{
		$sr=0;
	}
	
	if (!empty($_REQUEST['emailchrg']) && $_REQUEST['emailchrg']==1)
	{
		$ec=$_REQUEST['emailchrg'];
	}
	else
	{
		$ec=0;
	}

	if (isset($_REQUEST['alevel']) && is_array($_REQUEST['alevel']) && $_REQUEST['alevel'][6]==0)
	{
		$slevel='0,0,0,0,0,0,0';
	}
	elseif (is_array($_REQUEST['alevel']))
	{
		$slevel='';
		foreach ($_REQUEST['alevel'] as $n => $v)
		{
			if ($n==(count($_REQUEST['alevel']) - 1))
			{
				$slevel=$slevel.$v;
			}
			else
			{
				$slevel=$slevel.$v.",";
			}
		}
	}
	else
	{
		$slevel='0,0,0,0,0,0,0';
	}

	//$slevel=$_REQUEST['updt_elev'].",".$_REQUEST['updt_clev'].",".$_REQUEST['updt_jlev'].",".$_REQUEST['updt_llev'].",".$_REQUEST['updt_rlev'].",".$_REQUEST['updt_mlev'].",".$_REQUEST['updt_tlev'];
	$mlevel=$_REQUEST['updt_m_plev'].",".$_REQUEST['updt_m_llev'].",".$_REQUEST['updt_m_ulev'].",".$_REQUEST['updt_m_mlev'].",".$_REQUEST['updt_m_tlev'];

	$qry1  = "UPDATE security SET officeid='".$_REQUEST['office']."',slevel='".$slevel."',";
	$qry1 .= "fname='".$_REQUEST['fname']."',lname='".$_REQUEST['lname']."',sidm='".$_REQUEST['sidm']."',";
	$qry1 .= "admstaff='".$_REQUEST['admstaff']."',assistant='".$_REQUEST['assistant']."',adminid='".$_REQUEST['adminid']."',admindate=getdate(),";
	$qry1 .= "mas_office='".$_REQUEST['mas_office']."',masid='".$pmasid[0]."',mas_div='".$pmasid[1]."',hdate='".$_REQUEST['hdate']."',mlevel='".$mlevel."',";
	$qry1 .= "rmasid='".$rpmasid[0]."',rmas_div='".$rpmasid[1]."',off_demo='".$_REQUEST['off_demo']."',dsgfperiod='".$_REQUEST['dsgfperiod']."',";
	$qry1 .= "dsgfarray='".$_REQUEST['dsgfarray']."', altid='".$_REQUEST['altid']."', devmode='".$_REQUEST['devmode']."', excmess='".$_REQUEST['excmess']."',";
	$qry1 .= "gmreports='".$_REQUEST['gmreports']."',email='".$_REQUEST['email']."',srep='".$sr."',csrep='".$_REQUEST['csrep']."',emailchrg='".$ec."',";
	$qry1 .= "mas_prid='".$_REQUEST['mas_prid']."',phone='".$_REQUEST['phone']."',ext='".$_REQUEST['extn']."',admindigreport='".$_REQUEST['admindigreport']."',";
	$qry1 .= "tester='".$_REQUEST['tester']."',newcommdate='".$_REQUEST['newcommdate']."',menutype='".$_REQUEST['menutype']."',modcomm='".$_REQUEST['modcomm']."', ";
	$qry1 .= "emailtemplateaccess='".$_REQUEST['emailtemplateaccess']."',contactlist='".$_REQUEST['contactlist']."',stitle='".htmlspecialchars($_REQUEST['stitle'])."', ";
	$qry1 .= "networkaccess='".$_REQUEST['networkaccess']."',filestoreaccess='".$_REQUEST['filestoreaccess']."',officelist='".$_REQUEST['officelist']."', ";
	$qry1 .= "constructdateaccess='".$_REQUEST['constructdateaccess']."',enotify='".$_REQUEST['enotify']."',JobCommEdit='".$_REQUEST['JobCommEdit']."', ";
	
	if (isset($_REQUEST['passcnt']) and $_REQUEST['passcnt']==0)
	{
		$qry1 .= "passcnt=0,";
	}
	
	$qry1 .= "PurchaseOrder='".$_REQUEST['PurchaseOrder']."',returntolist='".$_REQUEST['returntolist']."',acctngrelease='".$_REQUEST['acctngrelease']."',conspiperpt='".$_REQUEST['conspiperpt']."' ";
	$qry1 .= "WHERE securityid='".$_REQUEST['userid']."';";
	$res1  = mssql_query($qry1);

	//echo $qry1."<br>";

	if (!empty($_REQUEST['altid']) && $_REQUEST['altid']!=0)
	{
		$qry2  = "INSERT INTO secondaryids (securityid,secid,addby) values (".$row0['securityid'].",".$_REQUEST['altid'].",".$_SESSION['securityid'].")";
		$res2  = mssql_query($qry2);
	}
	
	if (!empty($_REQUEST['delalt']) && $_REQUEST['delalt']!=0)
	{
		//echo "DELETE!!!!!";
		$qry3  = "DELETE FROM secondaryids WHERE secid=".$_REQUEST['delalt'].";";
		$res3  = mssql_query($qry3);
	}

	if (EMAIL_OUT==true && $_REQUEST['alevel'][6]!=$oseclevel[6])
	{
		$to=array();
		$qry4 = "SELECT o.processor,s.email FROM offices as o inner join security on o.processor=s.securityid WHERE o.officeid='".$row0['officeid']."';";
		$res4 = mssql_query($qry4);
		$row4 = mssql_fetch_array($res4);
		
		if ($row4['processor']!=0)
		{
			$to[]=$row4['email'];
		}
		
		$to[]	= 'hhess@corp.bluehaven.com';
		$to[]	= 'sschirmer@corp.bluehaven.com';
		
		$mess	 = "Name  : ".$row0['fname']." ".$row0['lname']."\r\n";
		$mess	.= "Office: ".$_SESSION['offname']."\r\n";
		$mess	.= "----------------------\r\n";
		$mess	.= "Admin : ".$_SESSION['fname']." ".$_SESSION['lname']."\r\n";
		$mess	.= "RHost : ".getenv('REMOTE_ADDR')."\r\n";
		
		if ($_REQUEST['alevel'][6]==1 && $oseclevel[6]==0)
		{
			$sub	 = "JMS Account Reactivated - ".$_SESSION['offname']." - ".$row0['lname']." ".$row0['fname'];
			SendSystemEmail('jmsadmin@bhnmi.com',$to,$sub,$mess);
		}
		elseif ($_REQUEST['alevel'][6]==0 && $oseclevel[6]==1)
		{
			$sub	 = "JMS Account Deactivated - ".$_SESSION['offname']." - ".$row0['lname']." ".$row0['fname'];
			SendSystemEmail('jmsadmin@bhnmi.com',$to,$sub,$mess);
		}
	}
	
	viewuser();
}

function clearuserlogin()
{
	$qry = "SELECT securityid,logstate,lname FROM security WHERE securityid='".$_REQUEST['userid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	if ($row[1]==1)
	{
		$qryA = "UPDATE security SET logstate='0' WHERE securityid='".$_REQUEST['userid']."';";
		$resA = mssql_query($qryA);
		//$rowA = mssql_fetch_row($resA);

		//echo "<b>Clearing Login State for: ".$row[2]."</b>\n";
	}
	//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=maint&call=users&subq=view&userid=$row[0]\">";
	viewuser();
}

function slevelform($csl)
{
	if (!is_array($csl))
	{
		//$csl=array(0,0,0,0,0,0,0);
		$csl=array(1,1,1,1,1,1,1);
	}
	
	$modules=array('Estimates','Contracts','Jobs','Leads','Reports','Messages','System');
	
	echo "<table>\n";
	
	//for ($x=0;$x<=count($modules);$x++)
	for ($x=0;$x<=6;$x++)
	{
		echo "<tr>\n";
		echo "	<td align=\"right\">\n";
		echo "		<select name=\"alevel[]\">\n";
		
		for ($y=0;$y<=9;$y++)
		{
			if ($y==$csl[$x])
			{
				echo "				<option value=\"".$y."\" SELECTED>".$y."</option>\n";
			}
			else
			{
				echo "				<option value=\"".$y."\">".$y."</option>\n";
			}
			//echo "				<option value=\"".$y."\">".$y."</option>\n";
		}

		echo "		</select>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\">\n";
		echo $modules[$x];
		echo "	</td>\n";
		echo "</tr>\n";
	}
	
	echo "</table>\n";
}

function slevelformflat($csl,$oid)
{
	if (!is_array($csl))
	{
		$csl=array(0,0,0,0,0,0,0);
	}
	
	$modules=array('E','C','J','L','R','M','S');
	
	echo "<table>\n";
	echo "	<tr>\n";
	
	for ($z=0;$z<=6;$z++)
	{
		echo "	<td align=\"center\">\n";
		echo $modules[$z];
		echo "	</td>\n";
	}
	
	echo "	</tr>";
	echo "	<tr>\n";
	
	for ($x=0;$x<=6;$x++)
	{
		echo "	<td align=\"center\">\n";
		echo "		<select name=\"altlevel".$oid."[]\">\n";
		
		for ($y=0;$y<=9;$y++)
		{
			if ($y==$csl[$x])
			{
				echo "				<option value=\"".$y."\" SELECTED>".$y."</option>\n";
			}
			else
			{
				echo "				<option value=\"".$y."\">".$y."</option>\n";
			}
			//echo "				<option value=\"".$y."\">".$y."</option>\n";
		}

		echo "		</select>\n";
		echo "	</td>\n";
	}
	
	echo "	</tr>";
	echo "</table>";
}

function sleveldisplay($csl)
{
	if (!is_array($csl))
	{
		$csl=array(0,0,0,0,0,0,0);
	}
	
	$nulltxt='';
	
	$temptxt="
	Level 1 - Sales Rep<br>
	Level 5 - Sales Manager<br>
	Level 6 - General Manager<br>
	";
	
	$esttxt="
	<b>Estimates</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Create/Read/Update/Delete Estimates on Leads directly assigned<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Create/Read/Update/Delete Estimates on Leads directly Assigned and Leads for Sales Reps directly assigned<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- Create/Read/Update/Delete Estimates on Leads for entire Office<br>
	- View Cost
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Same as General Manager
	</p>
	";
	
	$contxt="
	<b>Contracts</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Read Contracts for directly assigned Leads/Customers<br>
	- Cannot Create Contracts<br>
	- Create/Read Addendums<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Create/Read/Update/Delete Contracts for directly assigned Leads/Customers and Leads/Customers for Sales Reps directly assigned<br>
	- Create/Read/Update/Delete Addendums for directly assigned Leads/Customers and Leads/Customers for Sales Reps directly assigned<br>
	- Cannot Create GM Adjusts<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- Create/Read/Update/Delete Contracts for entire Office<br>
	- Create/Read/Update/Delete Addendums for entire Office<br>
	- Create/Read/Update/Delete GM Adjust for entire Office<br>
	- View Cost
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Same as General Manager
	</p>
	";
	
	$jobtxt="
	<b>Jobs</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Read Jobs for directly assigned Leads/Customers<br>
	- Cannot Create Jobs<br>
	- Create/Read Addendums<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Create/Read/Update/Delete Jobs for directly assigned Leads/Customers and Leads/Customers for Sales Reps directly assigned<br>
	- Create/Read/Update/Delete Addendums for directly assigned Leads/Customers and Leads/Customers for Sales Reps directly assigned<br>
	- Cannot Create GM Adjusts<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- Create/Read/Update/Delete Jobs for entire Office<br>
	- Create/Read/Update/Delete Addendums for entire Office<br>
	- Create/Read/Update/Delete GM Adjust for entire Office<br>
	- MAS Ready/Not Ready Jobs<br>
	- View Cost</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Same as General Manager
	</p>
	";
	
	$ldstxt="
	<b>Leads</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Create/Read/Update directly assigned Leads
	</p><br>
	<p>
	Level 4 - Sales Manager<br>
	- Create/Read/Update directly assigned Leads and Leads for directly assigned Sales Reps<br>
	- Move Leads between directly assigned Sales Reps or Staff
	</p><br>
	<p>
	Level 5 - General Manager<br>
	- Create/Read/Update Leads for entire Office<br>
	- Move Leads to any Staff within Office<br>
	- Return BHNM Provided Leads to Management or other Offices (see System Security)
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Create/Read/Update Leads for entire System<br>
	- Move Leads to any Office/Staff within System<br>
	</p>
	";
	
	$rpttxt="
	<b>Reports</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Dig Standings<br>
	- Sales & Commission (Self Only)
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Dig Standings<br>
	- Lead Source (Default Office Only)<br>
	- Zip Report<br>
	- Sales & Commission (Self & assigned Sales Reps)
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- All Level 5 Reports<br>
	- CSR Report (requires Funcational Access setup)<br>
	- Dig Reports<br>
	- Job Progress<br>
	- Operating Reports (enable GM/Operating Reports under Functional Access)<br>
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- All Reports (some may require Functional Access setting)
	</p>
	";
	
	$msgtxt="
	<b>Messages</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Send and Receive Message from others in their Office<br>
	- Receive from BHNM
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Send to and Receive from others in their Office<br>
	- Send to and Receive from BHNM
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- Send to and Receive from others in their Office<br>
	- Send to and Receive from BHNM
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Send to and Receive from all Users and Resource Accounts
	</p>
	";
	
	$systxt="
	<b>System</b><br>
	<p>
	Level 1 - Standard Access<br>
	- Allows login to Default Office
	</p><br>
	<p>
	Level 7 - Alternate Office Access<br>
	- Access to Offices other than the Default Office (Alternate Office Access List Setup Required)<br>
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Unrestricted Access to all Offices
	</p>
	";
	
	
	$modules=array(
					array('Estimates',$esttxt),
					array('Contracts',$contxt),
					array('Jobs',$jobtxt),
					array('Leads',$ldstxt),
					array('Reports',$rpttxt),
					array('Messages',$msgtxt),
					array('System',$systxt)
					);
	
	echo "<table>";
	
	for ($x=0;$x<=6;$x++)
	{
		echo "<tr>";
		echo "	<td align=\"right\">";
		echo $csl[$x];
		echo "	</td>";
		echo "	<td align=\"left\">";
		echo '<b>'.$modules[$x][0].'</b> ';		
		echo "	</td>";
		echo "	<td align=\"left\">";
		
		if (!empty($modules[$x][1]))
		{
			echo "<span class=\"JMStooltip\" title=\"".$modules[$x][1]."\"><img src=\"images/info.gif\"></span>";
		}
		
		echo "	</td>";
		echo "</tr>";
	}
	
	echo "</table>";
}

function adduser()
{
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$currdate	=date("m/d/Y",time());

	if (!isset($_REQUEST['state'])||empty($_REQUEST['state']))
	{
		$qryA = "SELECT officeid,code,name FROM offices WHERE active=1 ORDER BY name ASC";
		$resA = mssql_query($qryA);

		$qryB = "SELECT id,alevel,name,tlev FROM seclevels ORDER BY tlev ASC";
		$resB = mssql_query($qryB);

		$qryC = "SELECT code FROM offices WHERE officeid='".$_SESSION['officeid']."';";
		$resC = mssql_query($qryC);
		$rowC = mssql_fetch_array($resC);

		$tidx=1;
		echo "<script type=\"text/javascript\" src=\"http://dev.jquery.com/view/trunk/plugins/validate/jquery.validate.js\"></script>\n";
		echo "<script type=\"text/javascript\" src=\"js/jquery_user_add.js\"></script>\n";
		echo "<table width=\"950px\">\n";
		echo "	<tr>\n";
		echo "   	<td align=\"left\" valign=\"top\" width=\"500px\">\n";
		echo "						<form id=adduser name=adduser method=\"post\" onSubmit=\"return BasicFormCheck('adduser','ErrorText')\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"add\">\n";
		echo "						<input type=\"hidden\" name=\"state\" value=\"ins\">\n";
		echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "						<table class=\"outer\" width=\"100%\">\n";
		echo "							<tr class=\"tblhd\">\n";
		echo "								<td colspan=\"2\"><b>Add New User</b></td>\n";
		echo "							</tr>\n";

		if ($_SESSION['tlev'] >= 8)
		{
			echo "<tr>\n";
			echo "   <td class=\"gray\" align=\"right\"><b>Office</b></td>\n";
			echo "   <td class=\"gray\">\n";
			echo "      <select tabindex=\"".$tidx++."\" name=\"officeid\">\n";

			while ($rowA = mssql_fetch_row($resA))
			{
				if ($rowA[0]==$officeid)
				{
					echo "         <option value=\"$rowA[0]\" SELECTED>$rowA[2]</option>\n";
				}
				elseif (!empty($_REQUEST['officeid']))
				{
					if ($rowA[0]==$_REQUEST['officeid'])
					{
						echo "         <option value=\"$rowA[0]\" SELECTED>$rowA[2]</option>\n";
					}
					else
					{
						echo "         <option value=\"$rowA[0]\">$rowA[2]</option>\n";
					}
				}
				else
				{
					echo "         <option value=\"$rowA[0]\">$rowA[2]</option>\n";
				}
			}

			echo "      </select>\n";
			echo "   </td>\n";
			echo "</tr>\n";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		}
		
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\"><b>Login ID</b></td>\n";
		echo "								<td class=\"gray\"><input tabindex=\"".$tidx++."\" class=\"bboxb\" type=\"text\" name=\"login\" id=\"loginid\" size=\"25\" maxlength=\"8\"></td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\"><b>Password</b></td>\n";
		echo "								<td class=\"gray\"><input tabindex=\"".$tidx++."\" class=\"bboxb\" type=\"password\" name=\"p1\" size=\"25\" maxlength=\"8\"></td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\"><b>First Name</b></td>\n";
		echo "								<td class=\"gray\"><input tabindex=\"".$tidx++."\" class=\"bboxb\" type=\"text\" name=\"fname\" id=\"fname\" size=\"25\" maxlength=\"20\"></td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\"><b>Last Name</b></td>\n";
		echo "								<td class=\"gray\"><input tabindex=\"".$tidx++."\" class=\"bboxb\" type=\"text\" name=\"lname\" id=\"lname\" size=\"25\" maxlength=\"20\"></td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\"><b>Title/Role</b></td>\n";
		echo "								<td class=\"gray\"><input tabindex=\"".$tidx++."\" class=\"bboxb\" type=\"text\" name=\"stitle\" size=\"25\" maxlength=\"32\"></td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\"><b>Hire Date</b></td>\n";
		echo "								<td class=\"gray\">\n";
		echo "									<input tabindex=\"".$tidx++."\" class=\"bboxb\" type=\"text\" id=\"hdate\" name=\"hdate\" value=\"".$currdate."\" size=\"25\" maxlength=\"11\">\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\"><b>Email</b></td>\n";
		echo "								<td class=\"gray\"><input tabindex=\"".$tidx++."\" class=\"bboxb\" type=\"text\" name=\"email\" id=\"email\" size=\"25\" maxlength=\"32\"></td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\"><b>Sales Rep</b></td>\n";
		echo "								<td class=\"gray\"><input tabindex=\"".$tidx++."\" class=\"transnb\" type=\"checkbox\" name=\"salesrep\" value=\"1\"></td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\" valign=\"top\"><b>Security Levels</b></td>\n";
		echo "								<td class=\"gray\" valign=\"top\">\n";
		
		slevelform();
		
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td class=\"gray\" align=\"right\" colspan=\"2\">\n";
		echo "									<table width=\"100%\">\n";
		echo "										<tr>\n";
		echo "   										<td align=\"right\">\n";
		echo "												<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Add User\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "						</form>\n";
		echo "		</td>\n";
		echo "		<td align=\"left\" valign=\"top\" width=\"550px\"><p><span id=\"ajxcontent\"></span></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	elseif ($_REQUEST['state']=='ins')
	{
		//show_post_vars();
		//exit;
		
		$qry0 = "SELECT cmid FROM CommissionBuilder WHERE oid=".$_SESSION['officeid']." and active=1;";
		$res0 = mssql_query($qry0);
		$nrow0 = mssql_num_rows($res0);
		
		$qry = "SELECT securityid FROM security WHERE login='".$_REQUEST['login']."'";
		$res = mssql_query($qry);
		$nrow = mssql_num_rows($res);

		if (empty($_REQUEST['login']))
		{
			echo "<b>Login ID is Blank.... Click BACK and try again.</b>\n";
		}
		elseif (strlen($_REQUEST['login']) < 4)
		{
			echo "<b>Login ID is too Short. Must be between 4 and 8 characters.... Click BACK and try Again.</b>";
		}
		elseif (strlen($_REQUEST['login']) > 8)
		{
			echo "<b>Login ID is too Long. Must be between 4 and 8 characters.... Click BACK and try Again.</b>";
		}
		elseif (empty($_REQUEST['p1']))
		{
			echo "<b>Password Field is Blank.... Click BACK and try again.</b>\n";
		}
		elseif (strlen($_REQUEST['p1']) < 4)
		{
			echo "<b>Password is too Short. Password must be between 4 and 8 characters.... Click BACK and try Again.</b>";
		}
		elseif (strlen($_REQUEST['p1']) > 8)
		{
			echo "<b>Password is too Long. Password must be between 4 and 8 characters.... Click BACK and try Again.</b>";
		}
		elseif ($nrow > 0)
		{
			echo "<b>User Login: <font color=\"red\">".$_REQUEST['login']."</font> already exists.... Click BACK and try Again.</b>";
		}
		elseif (strlen($_REQUEST['hdate']) < 6 || !valid_date($_REQUEST['hdate']))
		{
			echo "<b>Hire Date: Not Filled in or Invalid Format.... Click BACK and try Again.</b>";
		}
		else
		{
			$npswd= md5($_REQUEST['p1']);
			
			if (isset($_REQUEST['salesrep']) && $_REQUEST['salesrep']==1)
			{
				$sr=1;
			}
			else
			{
				$sr=0;
			}
			
			if (isset($_REQUEST['alevel']) && is_array($_REQUEST['alevel']) && $_REQUEST['alevel'][6]==0)
			{
				$alevel='0,0,0,0,0,0,0';
			}
			elseif (isset($_REQUEST['alevel']) && is_array($_REQUEST['alevel']))
			{
				foreach ($_REQUEST['alevel'] as $n => $v)
				{
					if ($n==(count($_REQUEST['alevel']) - 1))
					{
						$alevel=$alevel.$v;
					}
					else
					{
						$alevel=$alevel.$v.",";
					}
				}
			}
			else
			{
				$alevel='0,0,0,0,0,0,0';
			}
			
			//if ($nrow0 > 0)
			//{
			//	$ncdate=date('m/d/Y',time());
			//}
			//else
			//{
			//	$ncdate='12/31/2025';
			//}

			$qryB  = "INSERT INTO jest..security ";
			$qryB .= "(officeid,fname,lname,login,pswd,slevel,srep,hdate,email,newcommdate,stitle) ";
			$qryB .= "VALUES ";
			$qryB .= "('".$_SESSION['officeid']."','".$_REQUEST['fname']."','".$_REQUEST['lname']."','".$_REQUEST['login']."','".$npswd."','".$alevel."','".$sr."','".$_REQUEST['hdate']."','".$_REQUEST['email']."','".$_REQUEST['hdate']."','".$_REQUEST['stitle']."');";
			$qryB .= "SELECT @@IDENTITY;";
			$resB  = mssql_query($qryB);
			$rowB  = mssql_fetch_row($resB);
			
			///echo $qryB.'<br>';
			///exit;
			if (is_numeric($rowB[0]) && $rowB[0] > 0)
			{
				if (EMAIL_OUT==true && $_REQUEST['alevel'][6]==1)
				{
					$to=array();
					$qry4 = "SELECT isnull(o.processor,0),s.email FROM offices as o inner join security on o.processor=s.securityid WHERE o.officeid='".$_SESSION['officeid']."';";
					$res4 = mssql_query($qry4);
					$row4 = mssql_fetch_array($res4);
					
					if ($row4['processor']!=0)
					{
						$to[]=$row4['email'];
					}
					
					$to[]	= 'thelton@corp.bluehaven.com';
					
					$sub	 = "JMS User Activated - ".$_SESSION['offname']." - ".$_REQUEST['lname']." ".$_REQUEST['fname'];
					$mess	 = "Office: ".$_SESSION['offname']."\r\n";
					$mess	.= "Name  : ".$_REQUEST['lname']." ".$_REQUEST['fname']."\r\n";
					$mess	.= "----------------------\r\n";
					$mess	.= "Admin : ".$_SESSION['lname']." ".$_SESSION['fname']."\r\n";
					$mess	.= "RHost : ".getenv('REMOTE_ADDR')."\r\n";
		
					SendSystemEmail('jmsadmin@bhnmi.com',$to,$sub,$mess);
				}
	
				listusers();
			}
			else
			{
				echo "<b>User Not Added (DB Return: Error -> secid)</b>";
			}
		}
	}
}

function listusers()
{
	if ($_SESSION['tlev'] < 9)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}

	if (empty($_REQUEST['officeid']))
	{
		$officeid=$_SESSION['officeid'];
	}
	else
	{
		$officeid=$_REQUEST['officeid'];
	}


	if ($_SESSION['tlev'] >= 8)
	{
		$tenv= new JMSSYS_ENV();
		
		$altlname="";
		$altfname="";
		$qry0 = "SELECT officeid,code,name,gm,sm FROM offices WHERE officeid='".$officeid."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);

		$qry1 = "SELECT officeid,code,name FROM offices WHERE active=1 ORDER BY name";
		$res1 = mssql_query($qry1);
		//$row1 = mssql_fetch_array($res1);

		if ($officeid==0)
		{
			$qry = "SELECT * FROM security ORDER BY lname ASC";
		}
		else
		{
			$qry = "SELECT * FROM security WHERE officeid='".$officeid."' ORDER BY lname ASC";
		}

		$res = mssql_query($qry);

		echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "<tr>\n";
		echo "   <td class=\"gray\" valign=\"bottom\" colspan=\"4\"><b>Security List </b> \n";
		echo "		<b>".$row0['name']."</b>";		
		echo "	</td>\n";
		echo "   <td class=\"gray\" align=\"right\" colspan=\"10\">\n";
		echo "		<table class=\"selectrow\">\n";
		echo "			<tr>\n";
		echo "				<td>Viewing</td>\n";
		echo "				<td>\n";
		echo "   	<form method=\"post\">\n";
		echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "   	<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		//echo "   	<input type=\"hidden\" name=\"showall\" value=\"1\">\n";
		echo "   	<input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
		echo "		<select name=\"showall\" onChange=\"this.form.submit();\">\n";
		
		if (isset($_REQUEST['showall']) && $_REQUEST['showall']==1)
		{
			echo "			<option value=\"0\">Active Users</option>\n";
			echo "			<option value=\"1\" SELECTED>All Users</option>\n";
		}
		else
		{
			echo "			<option value=\"0\" SELECTED>Active Users</option>\n";
			echo "			<option value=\"1\">All Users</option>\n";
		}
		
		echo "		</select>\n";
		echo "   	</form>\n";
		echo "				</td>\n";
		echo "				<td><img src=\"images/pixel.gif\"></td>\n";
		echo "				<td>Add User</td>\n";
		echo "				<td class=\"gray\" align=\"right\">\n";
		echo "		<form method=\"post\">\n";
		echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "   	<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "   	<input type=\"hidden\" name=\"subq\" value=\"add\">\n";
		echo "   	<input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
		echo "		<input class=\"transnb\" type=\"image\" src=\"images/add.png\" alt=\"Add User\">\n";
		echo "		</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr class=\"tblhd\">\n";
		echo "   <td></td>\n";
		echo "   <td></td>\n";
		echo "   <td align=\"center\"><b>Name</b></td>\n";
		echo "   <td align=\"center\"><b>Email</b></td>\n";
		echo "   <td align=\"center\"><b>MAS ID</b></td>\n";
		echo "   <td align=\"center\"><b>Sec ID</b></td>\n";
		echo "   <td align=\"center\" title=\"Number of Alternate User IDs\"><b>Alt Cnt</b></td>\n";
		echo "   <td align=\"center\"><b>E,C,J,L,R,M,S</b></td>\n";
		echo "   <td align=\"center\"><b>CS Rep</b></td>\n";
		echo "   <td align=\"center\"><b>Login ID</b></td>\n";
		echo "   <td align=\"center\"><b>Logged In?</b></td>\n";
		echo "   <td align=\"center\"><b>Last Login</b></td>\n";
		echo "   <td align=\"center\"><b>Last Pass Chg</b></td>\n";
		echo "	 <td align=\"right\"></td>\n";
		echo "	 <td align=\"right\">\n";
	
		HelpNode('MaintUserList',1);
	
		echo "	 </td>\n";
		echo "</tr>\n";
		
		$altcnt="";
		$ucnt=1;
		$lcnt=1;
		while ($row = mssql_fetch_array($res))
		{
			$secl=explode(",",$row['slevel']);
			if ($secl[6]!=0||!empty($_REQUEST['showall']))
			{
				$lcnt++;
				
				if ($lcnt%2)
				{
					$tbg='white';
				}
				else
				{
					$tbg='ltgray';
				}
				
				if ($secl[6]==0)
				{
					$fstyle="red";
				}
				else
				{
					$fstyle="black";
				}

				$qry1 = "SELECT * FROM logstate WHERE securityid='".$row['securityid']."';";
				$res1 = mssql_query($qry1);
				$row1 = mssql_fetch_array($res1);
				$nrow1= mssql_num_rows($res1);
				
				$qry2 = "SELECT COUNT(id) as altcnt FROM secondaryids WHERE securityid='".$row['securityid']."';";
				$res2 = mssql_query($qry2);
				$row2 = mssql_fetch_array($res2);

				if ($row2['altcnt'] > 0)
				{
					$altcnt=$row2['altcnt'];
				}
				else
				{
					$altcnt="";
				}

				echo "<tr class=\"".$tbg."\">\n";
				echo "   <td align=\"right\"><font color=\"".$fstyle."\">".$ucnt++.".</td>\n";
				echo "   <td align=\"center\">\n";
				
				if (isset($row['srep']) && $row['srep']==1)
				{					
					srep_page_link($row['officeid'],$row['securityid']);
				}
				
				echo "	 </td>\n";
				echo "   <td align=\"left\"><font color=\"".$fstyle."\"><b>".$row['lname']."</b>, ".$row['fname']."</font></td>\n";
				echo "   <td align=\"left\"><font color=\"".$fstyle."\">".$row['email']."</td>\n";
				echo "   <td align=\"center\"><font color=\"".$fstyle."\">".$row['masid']."</td>\n";
				echo "   <td align=\"center\"><font color=\"".$fstyle."\">".$row['securityid']."</td>\n";
				echo "   <td align=\"center\"><font color=\"".$fstyle."\">".$altcnt."</td>\n";
				echo "   <td align=\"center\">\n";

				role_display($row['slevel']);

				echo "	 </td>\n";
				echo "   <td align=\"center\">".$row['csrep']."</td>\n";
				echo "   <td>".$row['login']."</td>\n";

				if ($nrow1 > 0)
				{
					echo "   <td align=\"center\"><b>Yes</b></td>\n";
				}
				else
				{
					echo "   <td align=\"center\">No</td>\n";
				}

				echo "	<td align=\"center\">";
	
				if (strtotime($row['curr_login']) < strtotime('1/1/2004'))
				{
					echo "<font color=\"red\">Never</font>";
				}
				else
				{
					echo date('m/d/y g:iA',strtotime($row['curr_login']));
				}
				
				echo "</td>\n";
				//echo "   <td>".$row['curr_login']."</td>\n";
				echo "	<td align=\"center\">";
	
				if (strtotime($row['passchg']) < strtotime('1/1/2004'))
				{
					echo "<font color=\"red\">Never</font>";
				}
				else
				{
					echo date('m/d/y g:iA',strtotime($row['passchg']));
				}
				
				echo "	</td>\n";
				echo "	<td align=\"center\">\n";
				echo "   <form method=\"post\">\n";
				echo "   	<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "   	<input type=\"hidden\" name=\"call\" value=\"user_activity_detail\">\n";
				echo "   	<input type=\"hidden\" name=\"userid\" value=\"".$row['securityid']."\">\n";
				echo "   	<input class=\"transnb_button\" type=\"image\" src=\"images/search.gif\" alt=\"Detail\">\n";
				echo "   </form>\n";
				echo "	</td>\n";
				echo "	<td align=\"center\">\n";
				echo "   <form class=\"ViewRecord\" method=\"post\">\n";
				echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
				echo "   	<input type=\"hidden\" name=\"call\" value=\"users\">\n";
				echo "   	<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
				echo "   	<input type=\"hidden\" name=\"userid\" id=\"recid\" value=\"".$row['securityid']."\">\n";
				echo "   	<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
				echo "		<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View User\">\n";
				echo "   </form>\n";
				echo "	</td>\n";
				echo "</tr>\n";
			}
		}
		
		echo "</table>\n";
	}
}

function viewuser()
{
	error_reporting(E_ALL);
	//ini_set('display_errors','On');
	
	/*
	if ($_SESSION['securityid']!=26)
	{
		if ($_SESSION['securityid']!=332)
		{
			if ($_SESSION['securityid']!=1732)
			{
				echo 'User admin offline for maintenance.';
				exit;
			}
		}
	}
	*/
	
	if ($_SESSION['tlev'] < 8)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Resource</b>";
		exit;
	}
	
	$altid_ar=array();

	$qry0 = "SELECT officeid,code,name,gm,sm,accountingsystem,enquickbooks FROM offices WHERE officeid=".$_REQUEST['officeid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid='".$_REQUEST['officeid']."';";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt, ";
	$qry .= "ListID, ";
	$qry .= "EditSequence, ";
	$qry .= "SR_ListID, ";
	$qry .= "SR_EditSequence ";
	$qry .= " FROM security WHERE securityid='".$_REQUEST['userid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid='".$row[0]."'";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!='".$row[0]."' and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid='".$row[0]."'";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row[0]."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	//echo $qry9."<br>";

	$sarray=explode(",",$row[5]);
	$marray=explode(",",$row[15]);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}

	$brdr=0;
	$hlpnd=1;
	echo "<script type=\"text/javascript\" src=\"js/jquery_users_func.js\"></script>\n";
	echo "<table align=\"center\" width=\"950px\" border=\"".$brdr."\">\n";
	echo "<tr>\n";
	echo "   <td valign=\"top\">\n";
	echo "		<table align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "			<tr>\n";
	echo "   			<td valign=\"top\">\n";
	echo "					<table align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "						<tr>\n";
	echo "   						<td colspan=\"2\">\n";
	echo "								<table class=\"outer\" align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "									<tr>\n";
	echo "   									<td class=\"gray\" valign=\"center\">\n";
	echo "											<table><tr><td><h2><b>Edit User: <i>".$row[2]." ".$row[3]."</i></b></h2></td><td></td></tr></table>\n";
	echo "										</td>\n";
	echo "   									<td class=\"gray\" valign=\"center\">\n";
	//echo "											<button id=\"query_Quickbooks_Employees\">Query QB</button>\n";
	echo "										</td>\n";
	echo "   									<td class=\"gray\" align=\"right\">User List</td>\n";
	echo "   									<td class=\"gray\" align=\"right\" width=\"20px\">\n";
	echo "         									<form method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"users\">\n";
	echo "											<input class=\"transnb\" type=\"image\" src=\"images/application_view_list.png\" title=\"User List\">\n";
	echo "         									</form>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "   						<td valign=\"top\" align=\"left\">\n";
	echo "			<input type=\"hidden\" id=\"UserSID\" value=\"".$row[0]."\">\n";
	echo "			<input type=\"hidden\" id=\"UserOID\" value=\"".$row[1]."\">\n";
	echo "			<div id=\"ViewUserInfo\">\n";
	?>
	
					<ul>
						<li><a href="#tab0"><em>JMS User Info</em></a></li>
						<li><a href="#tab1"><em>Security Levels</em></a></li>
						<li><a href="#tab2"><em>Functional Access</em></a></li>
						<li><a href="#tab3"><em>Profiles</em></a></li>
						<li><a href="#tab4"><em>Sales Rep</em></a></li>
						<li><a href="#tab5"><em>MAS</em></a></li>
						<li><a href="#tab6"><em>QBS</em></a></li>
						<li><a href="#tab7"><em>Office Access</em></a></li>
						<li><a href="#tab8"><em>System Information</em></a></li>
					</ul>
	
	<?php
	echo "				<div id=\"tab0\">\n";
	echo "					<div id=\"panel_JMSUserInfo\"></div>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab1\">\n";
	echo "					<div id=\"panel_JMSSecurityInfo\"></div>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab2\">\n";
	echo "					<div id=\"panel_JMSFunctionalInfo\"></div>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab3\">\n";
	echo "					<div id=\"panel_JMSProfilesInfo\"></div>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab4\">\n";
	echo "					<div id=\"panel_JMSSalesRepInfo\"></div>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab5\">\n";	
	echo "					<div id=\"panel_MASAccountingInfo\"></div>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab6\">\n";	
	echo "					<div id=\"panel_QBSAccountingInfo\"></div>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab7\">\n";
	echo "					<div id=\"panel_JMSAltOfficeAccessInfo\"></div>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab8\">\n";	
	echo "					<div id=\"panel_JMSUserSysInfo\"></div>\n";
	echo "				</div>\n";
	echo "			</div>\n";
	
	echo "							</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	//echo "</form>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\">\n";
	echo "			<table align=\"left\" border=$brdr>\n";

	if ($_SESSION['tlev'] >= 9)
	{
		echo "<tr>\n";
		echo "   <td align=\"right\">\n";
		echo "		<table border=0>\n";
		echo "			<tr>\n";
		echo "   			<td>\n";
		//echo "					<input class=\"buttondkgrypnl80\" id=\"submituserupdate\" type=\"submit\" value=\"Update\">\n";
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "		<form method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "		<input type=\"hidden\" name=\"subq\" value=\"rp\">\n";
		echo "		<input type=\"hidden\" name=\"userid\" value=\"".$row[0]."\">\n";
		echo "		<input type=\"hidden\" name=\"officeid\" value=\"".$row[1]."\">\n";
		echo "   			<td>\n";
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Reset Pswrd\">\n";
		echo "				</td>\n";
		echo "		</form>\n";
		echo "			</tr>\n";

		if ($row[7]==1)
		{
			echo "			<tr>\n";
			echo "		<form method=\"post\">\n";
			echo "		<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "		<input type=\"hidden\" name=\"call\" value=\"users\">\n";
			echo "		<input type=\"hidden\" name=\"subq\" value=\"cl\">\n";
			echo "		<input type=\"hidden\" name=\"userid\" value=\"$row[0]\">\n";
			echo "		<input type=\"hidden\" name=\"officeid\" value=\"$row[1]\">\n";
			echo "   			<td>\n";
			echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Clear Login\">\n";
			echo "		</form>\n";
		}

		echo "				</td>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
	}

	if (isset($row[32]) && $row[32]==1)
	{
		echo "<tr>\n";
		echo "   <td align=\"center\">\n";
		
		srep_page_link($row[1],$row[0]);
		
		echo "	</td>\n";
		echo "</tr>\n";
	}
	

	if ($nrow3 > 0)
	{
		echo "												<tr>\n";
		echo "													<td align=\"left\"><b>Manager of</b></td>\n";
		echo "												</tr>\n";

		while ($row3 = mssql_fetch_row($res3))
		{
			echo "												<tr>\n";
			echo "													<td align=\"right\">\n";
				
			$slev=explode(",",$row3[2]);
			if ($slev[6]==0)
			{
				echo "													<font color=\"red\">".$row3[1]." ".$row3[0]."</font>";
			}
			else
			{
				echo $row3[1]." ".$row3[0]."";
			}
			
			echo "													</td>\n";
			echo "												</tr>\n";
		}
	}
		
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";

	/*
	if ($_SESSION['tlev'] > 4)
	{
	echo "<p>\n";
	listusers();
	}
	*/
}

function user_jmsinfo_panel($sid)
{
	$altid_ar=array();

	$qry0 = "SELECT officeid,code,name,gm,sm,accountingsystem FROM offices WHERE officeid=".$_REQUEST['officeid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid='".$_REQUEST['officeid']."';";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt, ";
	$qry .= "ListID, ";
	$qry .= "EditSequence, ";
	$qry .= "SR_ListID, ";
	$qry .= "SR_EditSequence ";
	$qry .= " FROM security WHERE securityid='".$_REQUEST['userid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	echo "								<table>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Login ID</b></td>\n";
	echo "										<td align=\"left\"><font color=\"blue\"><b>".$row[6]."</b></font></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Security ID</b></td>\n";
	echo "										<td align=\"left\"><font color=\"blue\"><b>".$row[0]."</b></font></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Auth Token</b></td>\n";
	echo "										<td align=\"left\"><font color=\"blue\"><b>".md5($row[0])."</b></font></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>First Name</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"fname\" value=\"".trim($row[2])."\" size=\"30\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Last Name</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"lname\" value=\"".trim($row[3])."\" size=\"30\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Title/Role</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"stitle\" value=\"".trim($row[45])."\" size=\"30\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Hire Date</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<input class=\"bboxb\" type=\"text\" id=\"d9\" name=\"hdate\" value=\"".trim($hdate)."\" size=\"15\">\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\" valign=\"top\"><b>Manager</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<select name=\"sidm\">\n";

	while ($row1 = mssql_fetch_row($res1))
	{
		$secl=explode(",",$row1[4]);
		if ($secl[3] >= 4 || $secl[4] >= 4)
		{
			if ($row1[0]==$row[11])
			{
				echo "											<option value=\"".$row1[0]."\" SELECTED>".$row1[2].", ".$row1[1]."</option>\n";
			}
			else
			{
				echo "											<option value=\"".$row1[0]."\">".$row1[2].", ".$row1[1]."</option>\n";
			}
		}
	}

	echo "											</select>\n";
	echo "										</td>\n";
	echo "									</tr>\n";

	$seclA=explode(",",$row[5]);
	if ($seclA[0] >= 4||$seclA[1] >= 4||$seclA[2] >= 4||$seclA[3] >= 4)
	{
		echo "									<tr>\n";
		echo "										<td align=\"right\" valign=\"top\"><b>Assitant</b></td>\n";
		echo "										<td align=\"left\">\n";
		echo "											<select name=\"assistant\">\n";
		echo "												<option value=\"0\">None</option>\n";

		while ($row4 = mssql_fetch_row($res4))
		{
			$seclAsub=explode(",",$row4[3]);
			if ($seclAsub[0] <= $seclA[0]||$seclAsub[1] <= $seclA[1]||$seclAsub[2] <= $seclA[2]||$seclAsub[3] <= $seclA[3])
			{
				if ($row4[0]==$row[13])
				{
					echo "												<option value=\"".$row4[0]."\" SELECTED>".$row4[2].", ".$row4[1]."</option>\n";
				}
				else
				{
					echo "												<option value=\"".$row4[0]."\">".$row4[2].", ".$row4[1]."</option>\n";
				}
			}
		}

		echo "											</select>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	else
	{
		echo "								<input type=\"hidden\" name=\"assistant\" value=\"0\">\n";
	}
	
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Phone</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"phone\" value=\"".trim($row[36])."\" size=\"15\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Extension</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"extn\" value=\"".trim($row[37])."\" size=\"15\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Email</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"email\" value=\"".trim($row[31])."\" size=\"40\"></td>\n";
	echo "									</tr>\n";	
	echo "								</table>\n";
}

function user_profiles_panel($sid)
{
	
	$altid_ar=array();

	$qry0 = "SELECT officeid,code,name,gm,sm,accountingsystem FROM offices WHERE officeid=".$_REQUEST['officeid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid='".$_REQUEST['officeid']."';";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt, ";
	$qry .= "ListID, ";
	$qry .= "EditSequence, ";
	$qry .= "SR_ListID, ";
	$qry .= "SR_EditSequence ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid='".$row[0]."'";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!='".$row[0]."' and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid='".$row[0]."'";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	echo "								<table>\n";
	
	if ($nrow9 > 0)
	{
		echo "									<tr>\n";
		echo "										<td align=\"right\"><b>Tied as Alt to:</b></td>\n";
		echo "										<td align=\"left\">\n";
		echo "      									<table>\n";
		
		$qry9a = "SELECT s.securityid,s.officeid,s.fname,s.lname,(SELECT name from offices where officeid=s.officeid) as oname FROM security as s WHERE securityid='".$row9['securityid']."'";
		$res9a = mssql_query($qry9a);
		$row9a = mssql_fetch_array($res9a);
		
		echo "      										<tr>\n";
		echo "													<td align=\"left\">".$row9a['lname'].", ".$row9a['fname']."</td>\n";
		echo "													<td align=\"center\">(".$row9a['oname'].")</td>\n";
		echo "													<td align=\"right\">(".$row9a['securityid'].")</td>";
		echo "      										</tr>\n";
		echo "      									</table>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	else
	{
		echo "									<tr>\n";
		echo "										<td align=\"right\"><b>Select Alternate</b></td>\n";
		echo "										<td align=\"left\">\n";	
		echo "											<select name=\"altid\">\n";
		echo "											<option value=\"0\" SELECTED>None</option>\n";
	
		while ($row7 = mssql_fetch_row($res7))
		{
			if (!in_array($row7[0],$altid_ar))
			{
				if ($row7[4]>=1)
				{
					echo "											<option class=\"fontblack\" value=\"".$row7[0]."\">".$row7[1].", ".$row7[2]." (".$row7[3]	.")(".$row7[0].")</option>\n";
				}
				else
				{
					echo "											<option class=\"fontred\" value=\"".$row7[0]."\">".$row7[1].", ".$row7[2]." (".$row7[3]	.")(".$row7[0].")</option>\n";
				}
			}
		}
		
		echo "											</select>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		
		if (count($altid_ar) > 0)
		{
			echo "									<tr>\n";
			echo "										<td align=\"right\" valign=\"top\" title=\"Existing Alternate IDs\"><b>Alternate Accounts Tied</b></td>\n";
			echo "										<td align=\"left\">\n";
			echo "      									<table>\n";
			
			foreach ($altid_ar as $n => $v)
			{
				$qry8a = "SELECT s.securityid,s.officeid,s.fname,s.lname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname FROM security as s WHERE securityid='".$v."'";
				$res8a = mssql_query($qry8a);
				$row8a = mssql_fetch_array($res8a);
				
				echo "      									<tr>\n";
				echo "												<td align=\"left\">".$row8a['lname'].", ".$row8a['fname']."</td>\n";
				echo "												<td align=\"center\">(".$row8a['oname'].")</td>\n";
				echo "												<td align=\"right\">(".$row8a['securityid'].")</td>";
				echo "												<td align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"delalt\" value=\"".$row8a['securityid']."\" title=\"Check this box to delete this entry.\"></td>";
				echo "      									</tr>\n";
			}
			
			echo "      									</table>\n";
		}
		
		echo "											</td>\n";
		echo "										</tr>\n";
	}
	
	echo "								</table>\n";
}

function user_account_panel($sid)
{
	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "assistant, ";
	$qry .= "adminid, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "stitle, ";
	$qry .= "JobCommEdit ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry0 = "SELECT officeid,code,name,gm,sm FROM offices WHERE officeid=".$row['officeid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,substring(slevel,13,1) as sactive FROM security WHERE officeid=".$row['officeid']." order by substring(slevel,13,1) desc,lname asc;";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry4 = "SELECT securityid,fname,lname,slevel,substring(slevel,13,1) as sactive FROM security WHERE officeid=".$row['officeid']." order by substring(slevel,13,1) desc,lname asc;";
	$res4 = mssql_query($qry4);
	
	if (isset($row['hdate']) && strlen($row['hdate']) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row['hdate']));
	}
	else
	{
		$hdate="";
	}
	
	
	//echo "XX<br>XX<br>XX<br>XX<br>XX<br>XX<br>XX<br>XX<br>XX<br>XX<br>XX<br>XX<br>";
	
	echo "								<table>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Login ID</b></td>\n";
	echo "										<td align=\"left\"><font color=\"blue\"><b>".$row['login']."</b></font></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Security ID</b></td>\n";
	echo "										<td align=\"left\"><font color=\"blue\"><b>".$row['securityid']."</b></font></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Auth Token</b></td>\n";
	echo "										<td align=\"left\"><font color=\"blue\"><b>".md5($row['securityid'])."</b></font></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>First Name</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"fname\" value=\"".trim($row['fname'])."\" size=\"30\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Last Name</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"lname\" value=\"".trim($row['lname'])."\" size=\"30\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Title/Role</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"stitle\" value=\"".trim($row['stitle'])."\" size=\"30\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Hire Date</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<input class=\"bboxb\" type=\"text\" id=\"d9\" name=\"hdate\" value=\"".$hdate."\" size=\"15\">\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\" valign=\"top\"><b>Manager</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<select name=\"sidm\">\n";

	while ($row1 = mssql_fetch_array($res1))
	{
		
		if ($row1['sactive'] > 0)
		{
			$otM='fontblack';
		}
		else
		{
			$otM='fontred';
		}
		
		if ($row1['securityid']==$row['sidm'])
		{
			echo "											<option class=\"".$otM."\" value=\"".$row1['securityid']."\" SELECTED>".$row1['lname'].", ".$row1['fname']."</option>\n";
		}
		else
		{
			echo "											<option class=\"".$otM."\" value=\"".$row1['securityid']."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
		}
	}

	echo "											</select>\n";
	echo "										</td>\n";
	echo "									</tr>\n";

	$seclA=explode(",",$row['slevel']);
	if ($seclA[0] >= 4||$seclA[1] >= 4||$seclA[2] >= 4||$seclA[3] >= 4)
	{
		echo "									<tr>\n";
		echo "										<td align=\"right\" valign=\"top\"><b>Assitant</b></td>\n";
		echo "										<td align=\"left\">\n";
		echo "											<select name=\"assistant\">\n";
		echo "												<option value=\"0\">None</option>\n";

		while ($row4 = mssql_fetch_array($res4))
		{
			$seclAsub=explode(",",$row4['slevel']);
			if ($seclAsub[0] <= $seclA[0]||$seclAsub[1] <= $seclA[1]||$seclAsub[2] <= $seclA[2]||$seclAsub[3] <= $seclA[3])
			{
				if ($row4['sactive'] > 0)
				{
					$otA='fontblack';
				}
				else
				{
					$otA='fontred';
				}
				
				if ($row4['securityid']==$row['assistant'])
				{
					echo "												<option class=\"".$otA."\" value=\"".$row4['securityid']."\" SELECTED>".$row4['lname'].", ".$row4['fname']."</option>\n";
				}
				else
				{
					echo "												<option class=\"".$otA."\" value=\"".$row4['securityid']."\">".$row4['lname'].", ".$row4['fname']."</option>\n";
				}
			}
		}

		echo "											</select>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	else
	{
		echo "								<input type=\"hidden\" name=\"assistant\" value=\"0\">\n";
	}
	
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Phone</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"tel\" name=\"phone\" value=\"".trim($row['phone'])."\" size=\"15\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Extension</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"extn\" value=\"".trim($row['ext'])."\" size=\"15\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Email</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"email\" value=\"".trim($row['email'])."\" size=\"40\"></td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
}

function user_security_panel()
{
	$altid_ar=array();

	$qry0 = "SELECT officeid,code,name,gm,sm FROM offices WHERE officeid='".$_REQUEST['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid='".$_REQUEST['officeid']."';";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt ";
	$qry .= " FROM security WHERE securityid='".$_REQUEST['userid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid='".$row[0]."'";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!='".$row[0]."' and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid='".$row[0]."'";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row[0]."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	//echo $qry9."<br>";

	$sarray=explode(",",$row[5]);
	$marray=explode(",",$row[15]);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}
	
	echo "								<table>\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\">\n";
	echo "											<table width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td align=\"left\"><b>Current</b> <a href=\"#\"><span class=\"JMStooltip\" title=\"Current Security Levels\"><img src=\"images/info.gif\"></span></a></td>";
	echo "													<td align=\"left\"><b>Set</b> <a href=\"#\"><span class=\"JMStooltip\" title=\"These settings will not be applied until the next time the User logs into the JMS\"><img src=\"images/info.gif\"></span></a></td>";
	echo "													<td align=\"left\">\n";
	
	if ($seclA[4] >= 2)
	{
		echo "<b>Maintenance</b>";
	}
	
	echo "													</td>";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\">";

	sleveldisplay(explode(",",$row[5]));

	echo "													</td>\n";
	echo "													<td valign=\"top\">";

	slevelform(explode(",",$row[5]));

	echo "													</td>\n";
	echo "													<td valign=\"top\">";

	if ($seclA[4] >= 2)
	{
		maintsecelements($marray);
	}
	else
	{
		echo "											<input type=\"hidden\" name=\"updt_m_plev\" value=\"0\"></td>\n";
		echo "											<input type=\"hidden\" name=\"updt_m_llev\" value=\"0\"></td>\n";
		echo "											<input type=\"hidden\" name=\"updt_m_ulev\" value=\"0\"></td>\n";
		echo "											<input type=\"hidden\" name=\"updt_m_mlev\" value=\"0\"></td>\n";
		echo "											<input type=\"hidden\" name=\"updt_m_tlev\" value=\"0\"></td>\n";
	}

	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
}

function user_function_panel($sid)
{
	$altid_ar=array();
	
	if (!isset($sid) or $sid==0)
	{
		echo 'ERROR Loading User Funtion Config';
		exit;
	}

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);
	
	$qry0 = "SELECT officeid,code,name,gm,sm FROM offices WHERE officeid=".$row[1].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid=".$row[1].";";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid='".$row[0]."'";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!='".$row[0]."' and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid='".$row[0]."'";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row[0]."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	//echo $qry9."<br>";

	$sarray=explode(",",$row[5]);
	$marray=explode(",",$row[15]);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}

	$brdr=0;
	$hlpnd=1;
	
	echo "								<table>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Account Lockout</b></td>\n";
	echo "													<td align=\"left\">\n";
	
	if ($row[56] >= 5)
	{
		echo "<font color=\"red\"><b>Yes</b></font>";
	}
	else
	{
		echo 'No';
	}

	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Account Lockout Clear</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<input type=\"checkbox\" class=\"transnb JMStooltip\" name=\"passcnt\" value=\"0\" title=\"Check this box to clear Account Lockout\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";	
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Accounting Release</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<select class=\"JMStooltip\" name=\"acctngrelease\" title=\"Enables the ability to approve Jobs for release to Accounting (MAS Ready / MAS Not Ready)\">\n";

	if ($row[54]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "											</select>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Developer Access</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<select class=\"JMStooltip\" name=\"devmode\" title=\"Grants Developer access to the JMS\">\n";

	if ($row[27]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Development Tester</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"tester\" title=\"Grants access to features under Development for Testing purposes\">\n";

	if ($row[39]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "									<tr>\n";
	echo "   									<td align=\"right\"><b>Admin Comment Level</b></td>\n";
	echo "   									<td align=\"left\">\n";
	echo "      									<select class=\"JMStooltip\" name=\"admstaff\" title=\"Grants access to Office Comments\">\n";

	if ($row[10]==0)
	{
		echo "      									<option value=\"0\" SELECTED>None</option>\n";
		echo "      									<option value=\"1\">Low</option>\n";
		echo "      									<option value=\"2\">Medium</option>\n";
		echo "      									<option value=\"3\">High</option>\n";
		
	}
	elseif ($row[10]==1)
	{
		echo "      									<option value=\"0\">None</option>\n";
		echo "      									<option value=\"1\" SELECTED>Low</option>\n";
		echo "      									<option value=\"2\">Medium</option>\n";
		echo "      									<option value=\"3\">High</option>\n";
	}
	elseif ($row[10]==2)
	{
		echo "      									<option value=\"0\">None</option>\n";
		echo "      									<option value=\"1\">Low</option>\n";
		echo "      									<option value=\"2\" SELECTED>Medium</option>\n";
		echo "      									<option value=\"3\">High</option>\n";
	}
	elseif ($row[10]==3)
	{
		echo "      									<option value=\"0\">None</option>\n";
		echo "      									<option value=\"1\">Low</option>\n";
		echo "      									<option value=\"2\">Medium</option>\n";
		echo "      									<option value=\"3\" SELECTED>High</option>\n";
	}

	echo "      									</select>\n";
	echo "      								</td>\n";
	echo "									</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Modify Price/Commissions</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"modcomm\" title=\"Allows User to Adjust Price per Book and Base Commission on Estimate Retail Breakdown\">\n";

	if ($row[42]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Excl Messaging</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"excmess\" title=\"Excludes User from System Messages (Defunct)\">\n";

	if ($row[26]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>GM/Operating Reports</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"gmreports\" title=\"Grants access to General Manager Operating reports\">\n";

	if ($row[28]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Admin Dig Report</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"admindigreport\" title=\"Grants access to the Administrative Dig Report\">\n";

	if ($row[38]==2)
	{
		echo "												<option value=\"2\" SELECTED>Create</option>\n";
		echo "												<option value=\"1\">View</option>\n";
		echo "												<option value=\"0\">No Access</option>\n";
	}
	elseif ($row[38]==1)
	{
		echo "												<option value=\"2\">Create</option>\n";
		echo "												<option value=\"1\" SELECTED>View</option>\n";
		echo "												<option value=\"0\">No Access</option>\n";
	}
	elseif ($row[38]==0)
	{
		echo "												<option value=\"2\">Create</option>\n";
		echo "												<option value=\"1\">View</option>\n";
		echo "												<option value=\"0\" SELECTED>No Access</option>\n";
	}
	else
	{
		echo "												<option value=\"0\">No Access</option>\n";
		echo "												<option value=\"1\">View</option>\n";
		echo "												<option value=\"2\">Create</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Contact List</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select name=\"contactlist\">\n";

	if ($row[44]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "   												<td align=\"right\"><b>After Lead Update</b></td>\n";
	echo "   												<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"returntolist\" title=\"Sets the default action taken after Updating a Lead\">\n";
	
	if ($row[52]==1)
	{
		echo "			<option value=\"0\">Return to Lead</option>\n";
		echo "			<option value=\"1\" SELECTED>Return to List</option>\n";
	}
	else
	{
		echo "			<option value=\"0\" SELECTED>Return to Lead</option>\n";
		echo "			<option value=\"1\">Return to List</option>\n";
	}
	
	echo "														</select>\n"; 
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Office List</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"officelist\" title=\"Set the sort order of the Office Listing for Users who have access to switch or edit Offices\">\n";

		if ($row[48]=='A')
		{
			echo "												<option value=\"A\" SELECTED>Alpha</option>\n";
			echo "												<option value=\"N\">Numeric</option>\n";
		}
		else
		{
			echo "												<option value=\"A\">Alpha</option>\n";
			echo "												<option value=\"N\" SELECTED>Numeric</option>\n";
		}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Email Notify</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"enotify\" title=\"Enables Email Notifications from the JMS to User (Requires valid Email Address)\">\n";

	if ($row[50]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>CS Rep Level</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"csrep\" title=\"Grants access to Customer Service Module\">\n";

	for ($cs=9;$cs>=0;$cs--)
	{
		if ($row[34]==$cs)
		{
			echo "												<option value=\"".$cs."\" SELECTED>".$cs."</option>\n";	
		}
		else
		{
			echo "												<option value=\"".$cs."\">".$cs."</option>\n";	
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Email Templates</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"emailtemplateaccess\" title=\"Grants access to Email Template Module\">\n";

	for ($et=9;$et>=0;$et--)
	{
		if ($row[43]==$et)
		{
			echo "												<option value=\"".$et."\" SELECTED>".$et."</option>\n";	
		}
		else
		{
			echo "												<option value=\"".$et."\">".$et."</option>\n";	
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Network Leads</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"networkaccess\" title=\"Grants access to Network Leads Module\">\n";

	for ($nwa=0;$nwa<=9;$nwa++)
	{
		if ($row[46]==$nwa)
		{
			echo "												<option value=\"".$nwa."\" SELECTED>".$nwa."</option>\n";
		}
		else
		{
			echo "												<option value=\"".$nwa."\">".$nwa."</option>\n";
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>File Cabinet</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"filestoreaccess\" title=\"Grants access to JMS File Cabinet (Office Configuration also required)\">\n";

	for ($fsa=0;$fsa<=9;$fsa++)
	{
		if ($row[47]==$fsa)
		{
			echo "												<option value=\"".$fsa."\" SELECTED>".$fsa."</option>\n";
		}
		else
		{
			echo "												<option value=\"".$fsa."\">".$fsa."</option>\n";
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Construction Dates</b></td>\n";
	echo "													<td align=\"left\">\n";		
	echo "														<select class=\"JMStooltip\" name=\"constructdateaccess\" title=\"Grants access to the Construction Dates Module on the Customer OneSheet\">\n";

	for ($cda=0;$cda<=9;$cda++)
	{
		if ($row[49]==$cda)
		{
			echo "												<option value=\"".$cda."\" SELECTED>".$cda."</option>\n";
		}
		else
		{
			echo "												<option value=\"".$cda."\">".$cda."</option>\n";
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Purchase Order</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"PurchaseOrder\" title=\"Grants access to Purchasing Module (in Development)\">\n";

	for ($po=9;$po>=0;$po--)
	{
		if ($row[53]==$po)
		{
			echo "												<option value=\"".$po."\" SELECTED>".$po."</option>\n";	
		}
		else
		{
			echo "												<option value=\"".$po."\">".$po."</option>\n";	
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Pipeline Report</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"conspiperpt\" title=\"Grants access to Office Pipeline Report<br>NOTE currently only activates report for viewing at level 6 or above\">\n";

	for ($opr=9;$opr>=0;$opr--)
	{
		if ($row[55]==$opr)
		{
			echo "												<option value=\"".$opr."\" SELECTED>".$opr."</option>\n";
		}
		else
		{
			echo "												<option value=\"".$opr."\">".$opr."</option>\n";
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "								</table>\n";
}

function user_altaccess_panel()
{
	$altid_ar=array();

	$qry0 = "SELECT officeid,code,name,gm,sm FROM offices WHERE officeid='".$_REQUEST['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid='".$_REQUEST['officeid']."';";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt ";
	$qry .= " FROM security WHERE securityid='".$_REQUEST['userid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid='".$row[0]."'";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!='".$row[0]."' and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid='".$row[0]."'";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row[0]."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	//echo $qry9."<br>";

	$sarray=explode(",",$row[5]);
	$marray=explode(",",$row[15]);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}

	$brdr=0;
	$hlpnd=1;
	
	echo "								<table>\n";
	
	if ($nrow9 > 0)
	{
		echo "									<tr>\n";
		echo "										<td align=\"right\"><b>Tied as Alt to:</b></td>\n";
		echo "										<td align=\"left\">\n";
		echo "      									<table>\n";
		
		$qry9a = "SELECT s.securityid,s.officeid,s.fname,s.lname,(SELECT name from offices where officeid=s.officeid) as oname FROM security as s WHERE securityid='".$row9['securityid']."'";
		$res9a = mssql_query($qry9a);
		$row9a = mssql_fetch_array($res9a);
		
		echo "      										<tr>\n";
		echo "													<td align=\"left\">".$row9a['lname'].", ".$row9a['fname']."</td>\n";
		echo "													<td align=\"center\">(".$row9a['oname'].")</td>\n";
		echo "													<td align=\"right\">(".$row9a['securityid'].")</td>";
		echo "      										</tr>\n";
		echo "      									</table>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	else
	{
		echo "									<tr>\n";
		echo "										<td align=\"right\"><b>Select Alternate</b></td>\n";
		echo "										<td align=\"left\">\n";	
		echo "											<select name=\"altid\">\n";
		echo "											<option value=\"0\" SELECTED>None</option>\n";
	
		while ($row7 = mssql_fetch_row($res7))
		{
			if (!in_array($row7[0],$altid_ar))
			{
				if ($row7[4]>=1)
				{
					echo "											<option class=\"fontblack\" value=\"".$row7[0]."\">".$row7[1].", ".$row7[2]." (".$row7[3]	.")(".$row7[0].")</option>\n";
				}
				else
				{
					echo "											<option class=\"fontred\" value=\"".$row7[0]."\">".$row7[1].", ".$row7[2]." (".$row7[3]	.")(".$row7[0].")</option>\n";
				}
			}
		}
		
		echo "											</select>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		
		if (count($altid_ar) > 0)
		{
			echo "									<tr>\n";
			echo "										<td align=\"right\" valign=\"top\" title=\"Existing Alternate IDs\"><b>Alternate Accounts Tied</b></td>\n";
			echo "										<td align=\"left\">\n";
			echo "      									<table>\n";
			
			foreach ($altid_ar as $n => $v)
			{
				$qry8a = "SELECT s.securityid,s.officeid,s.fname,s.lname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname FROM security as s WHERE securityid='".$v."'";
				$res8a = mssql_query($qry8a);
				$row8a = mssql_fetch_array($res8a);
				
				echo "      									<tr>\n";
				echo "												<td align=\"left\">".$row8a['lname'].", ".$row8a['fname']."</td>\n";
				echo "												<td align=\"center\">(".$row8a['oname'].")</td>\n";
				echo "												<td align=\"right\">(".$row8a['securityid'].")</td>";
				echo "												<td align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"delalt\" value=\"".$row8a['securityid']."\" title=\"Check this box to delete this entry.\"></td>";
				echo "      									</tr>\n";
			}
			
			echo "      									</table>\n";
		}
		
		echo "											</td>\n";
		echo "										</tr>\n";
	}
	
	echo "								</table>\n";
}

function user_srep_panel($sid)
{
	$altid_ar=array();

	$qry  = "SELECT ";
	$qry .= "securityid, ";
	$qry .= "hdate, ";
	$qry .= "srep, ";
	$qry .= "newcommdate, ";
	$qry .= "SR_ListID, ";
	$qry .= "SR_EditSequence ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$sarray=explode(",",$row['slevel']);
	$marray=explode(",",$row['mlevel']);

	if (isset($row['hdate']) && strlen($row['hdate']) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row['hdate']));
	}
	else
	{
		$hdate="";
	}
	
	echo "								<table>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\" valign=\"top\">\n";
	echo "											<table>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Sales Rep</b></td>\n";
	
	if ($row['srep']==1)
	{
		echo "													<td align=\"left\"><input class=\"transnb_button\" type=\"checkbox\" name=\"salesrep\" value=\"1\" CHECKED></td>\n";
	}
	else
	{
		echo "													<td align=\"left\"><input class=\"transnb_button\" type=\"checkbox\" name=\"salesrep\" value=\"1\"></td>\n";
	}
	
	echo "												</tr>\n";
	echo "      										<tr>\n";
	echo "   												<td align=\"right\" title=\"Set Date to Activate New Commission Tracking\"><b>New Commissions</b></td>\n";
	echo "   												<td align=\"left\">\n";
	echo "   													<input class=\"bboxb\" type=\"text\" name=\"newcommdate\" id=\"d10\" value=\"".trim(date('m/d/Y',strtotime($row['newcommdate'])))."\" size=\"15\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "      										<tr>\n";
	echo "   												<td align=\"right\"><b>Quickbooks SR ID</b></td>\n";
	echo "   												<td align=\"left\">".$row['SR_ListID']."</td>\n";
	echo "   												<td align=\"left\">\n";
	echo "   													<span id=\"SRLinkControls\">\n";
	echo "															<a href=\"#\" id=\"sendSRLinkConfig\"><img src=\"images/page_go.png\"></a>\n";
	echo "														</span>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "      										<tr>\n";
	echo "   												<td align=\"right\"><b>Quickbooks SR ES</b></td>\n";
	echo "   												<td align=\"left\">".$row['SR_EditSequence']."</td>\n";
	echo "												</tr>\n";
	
	if (isset($row['srep']) && $row['srep']==1)
	{
		echo "									<tr>\n";
		echo "										<td colspan=\"3\"><b>Sales Rep Beginning Balance</b></td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "										<td valign=\"top\" align=\"center\"  colspan=\"3\">\n";
		echo "											<iframe src=\"subs/srepbeginbalance.php?a=list&bbsid=".$row['securityid']."\" frameborder=\"0\" scrolling=\"auto\" width=\"100%\" height=\"100%\" align=\"right\"></iframe>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	else
	{
		echo "									<tr>\n";
		echo "										<td valign=\"top\" align=\"center\"  colspan=\"3\">\n";
		echo 'This Account is not flagged as a Sales Rep';
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	
	echo "											</table>\n";
	echo "										</td>\n";
	echo "										<td valign=\"top\">\n";
	echo "											<table>\n";
	echo "												<tr>\n";
	echo "													<td align=\"left\"><b>Update Status:</b></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"left\"><div id=\"status_srep\"></div></td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
}

function user_accounting_mas_panel($sid)
{
	$altid_ar=array();

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);
	
	//echo $qry;
	$qry0 = "SELECT officeid,code,name,gm,sm,accountingsystem FROM offices WHERE officeid=".$row[1].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid=".$row[1].";";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid=".$row[1]." ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid=".$row[0].";";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!=".$row[0]." and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid=".$row[0].";";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row[0]."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	//echo $qry9."<br>";

	$sarray=explode(",",$row[5]);
	$marray=explode(",",$row[15]);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}

	$brdr=0;
	$hlpnd=1;
	
	echo "								<table>\n";
	
	if ($row0[5]==1)
	{
		echo "      							<tr>\n";
		echo "   									<td align=\"right\"><b>Accounting Office</b></td>\n";
		echo "   									<td align=\"left\">\n";
		echo "   										<input class=\"bboxb\" type=\"text\" name=\"mas_office\" value=\"".trim($row[17])."\" size=\"5\">\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "      								<tr>\n";
		echo "   									<td align=\"right\"><b>Accounting Payroll ID</b></td>\n";
		echo "   									<td align=\"left\">\n";
		echo "   										<input class=\"bboxb\" type=\"text\" name=\"mas_prid\" value=\"".trim($row[35])."\" size=\"7\">\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "   										<input type=\"hidden\" name=\"mas_div\" value=\"".trim($row[18])."\">\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>New Construction Link</b></td>\n";
		echo "   									<td align=\"left\">\n";
	
		if ($row[17]==0)
		{
			echo "   										<input class=\"bboxb\" type=\"text\" name=\"masid\" value=\"".trim($row[16])."\" size=\"5\" maxlength=\"5\" DISABLED>\n";
			echo "   										<input type=\"hidden\" name=\"masid\" value=\"".trim($row[16])."\">\n";
		}
		else
		{
			$odbc_conn1	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","");
			$odbc_qry1	=	"SELECT SalespersonNumber,Name,Division FROM MAS_".$row[17].".dbo.ARD_SalespersonMasterFile WHERE Name LIKE '%".substr($row[3],0,3)."%';";
			$odbc_res1	=	odbc_exec($odbc_conn1, $odbc_qry1);
	
			//echo $odbc_qry1."<br>";
	
			echo "   										<select name=\"masid\">\n";
			echo "   											<option value=\"0\">None</option>\n";
	
			while(odbc_fetch_row($odbc_res1))
			{
				$odbc_ret11 = odbc_result($odbc_res1, 1);
				$odbc_ret12 = odbc_result($odbc_res1, 2);
				$odbc_ret13 = odbc_result($odbc_res1, 3);
	
				if ($odbc_ret13==$row[18] && $odbc_ret11==$row[16])
				{
					echo "   											<option value=\"".$odbc_ret11.":".$odbc_ret13."\" SELECTED>(".$odbc_ret13.") (".$odbc_ret11.") ".$odbc_ret12."</option>\n";
				}
				else
				{
					echo "   											<option value=\"".$odbc_ret11.":".$odbc_ret13."\">(".$odbc_ret13.") (".$odbc_ret11.") ".$odbc_ret12."</option>\n";
				}
			}
	
			odbc_free_result($odbc_res1);
			odbc_close($odbc_conn1);
	
			echo "   										</select>\n";
		}
	
		//odbc_close($odbc_conn);
	
		echo "   									</td>\n";
		echo "   								</tr>\n";
		echo "   									<input type=\"hidden\" name=\"rmas_div\" value=\"".$row[30]."\">\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>Renovation Link</b></td>\n";
		echo "   									<td align=\"left\">\n";
		
		if ($row[17]==0)
		{
			echo "   										<input class=\"bboxb\" type=\"text\" name=\"masid\" value=\"".trim($row[29])."\" size=\"5\" maxlength=\"5\" DISABLED>\n";
			echo "   										<input type=\"hidden\" name=\"rmasid\" value=\"".trim($row[29])."\">\n";
		}
		else
		{
			//echo $row[17]."<br>";
			//echo $row[29]."<br>";
			//echo $row[30]."<br>";
			
			$odbc_conn2	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","");
			//$odbc_qry1	=	"SELECT SalespersonNumber,Name,Division FROM MAS_".$row[17].".dbo.ARD_SalespersonMasterFile WHERE Division='".$row[18]."';";
			$odbc_qry2	=	"SELECT SalespersonNumber,Name,Division FROM MAS_".$row[17].".dbo.ARD_SalespersonMasterFile WHERE Name LIKE '%".substr($row[3],0,3)."%';";
			$odbc_res2	=	odbc_exec($odbc_conn2, $odbc_qry2);
	
			//echo $odbc_qry1."<br>";
			
			echo "   										<select name=\"rmasid\">\n";
			echo "   											<option value=\"0\">None</option>\n";
	
			while(odbc_fetch_row($odbc_res2))
			{
				$odbc_ret21 = odbc_result($odbc_res2, 1);
				$odbc_ret22 = odbc_result($odbc_res2, 2);
				$odbc_ret23 = odbc_result($odbc_res2, 3);
	
				if ($odbc_ret23==$row[30] && $odbc_ret21==$row[29])
				{
					echo "   											<option value=\"".$odbc_ret21.":".$odbc_ret23."\" SELECTED>(".$odbc_ret23.") (".$odbc_ret21.") ".$odbc_ret22."</option>\n";
				}
				else
				{
					echo "   											<option value=\"".$odbc_ret21.":".$odbc_ret23."\">(".$odbc_ret23.") (".$odbc_ret21.") ".$odbc_ret22."</option>\n";
				}
			}
	
			odbc_free_result($odbc_res2);
			odbc_close($odbc_conn2);
	
			echo "   										</select>\n";
		}
	
		//odbc_close($odbc_conn);
	
		echo "   									</td>\n";
		echo "   								</tr>\n";
	}
	else
	{
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>QB Employee ID</b></td>\n";
		echo "   									<td align=\"left\">".$row[57]."</td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>QB Employee ES</b></td>\n";
		echo "   									<td align=\"left\">".$row[58]."</td>\n";
		echo "									</tr>\n";
	}
	echo "								</table>\n";
}

function user_accounting_qb_panel($sid)
{
	$altid_ar=array();

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);
	
	//echo $qry;
	$qry0 = "SELECT officeid,code,name,gm,sm,accountingsystem FROM offices WHERE officeid=".$row[1].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid=".$row[1].";";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid=".$row[1]." ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid=".$row[0].";";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!=".$row[0]." and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid=".$row[0].";";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row[0]."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	//echo $qry9."<br>";

	$sarray=explode(",",$row[5]);
	$marray=explode(",",$row[15]);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}

	$brdr=0;
	$hlpnd=1;
	
	echo "								<table>\n";
	
	if ($row0[5]==1)
	{
		echo "      							<tr>\n";
		echo "   									<td align=\"right\"><b>Accounting Office</b></td>\n";
		echo "   									<td align=\"left\">\n";
		echo "   										<input class=\"bboxb\" type=\"text\" name=\"mas_office\" value=\"".trim($row[17])."\" size=\"5\">\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "      								<tr>\n";
		echo "   									<td align=\"right\"><b>Accounting Payroll ID</b></td>\n";
		echo "   									<td align=\"left\">\n";
		echo "   										<input class=\"bboxb\" type=\"text\" name=\"mas_prid\" value=\"".trim($row[35])."\" size=\"7\">\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "   										<input type=\"hidden\" name=\"mas_div\" value=\"".trim($row[18])."\">\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>New Construction Link</b></td>\n";
		echo "   									<td align=\"left\">\n";
	
		if ($row[17]==0)
		{
			echo "   										<input class=\"bboxb\" type=\"text\" name=\"masid\" value=\"".trim($row[16])."\" size=\"5\" maxlength=\"5\" DISABLED>\n";
			echo "   										<input type=\"hidden\" name=\"masid\" value=\"".trim($row[16])."\">\n";
		}
		else
		{
			$odbc_conn1	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","");
			$odbc_qry1	=	"SELECT SalespersonNumber,Name,Division FROM MAS_".$row[17].".dbo.ARD_SalespersonMasterFile WHERE Name LIKE '%".substr($row[3],0,3)."%';";
			$odbc_res1	=	odbc_exec($odbc_conn1, $odbc_qry1);
	
			//echo $odbc_qry1."<br>";
	
			echo "   										<select name=\"masid\">\n";
			echo "   											<option value=\"0\">None</option>\n";
	
			while(odbc_fetch_row($odbc_res1))
			{
				$odbc_ret11 = odbc_result($odbc_res1, 1);
				$odbc_ret12 = odbc_result($odbc_res1, 2);
				$odbc_ret13 = odbc_result($odbc_res1, 3);
	
				if ($odbc_ret13==$row[18] && $odbc_ret11==$row[16])
				{
					echo "   											<option value=\"".$odbc_ret11.":".$odbc_ret13."\" SELECTED>(".$odbc_ret13.") (".$odbc_ret11.") ".$odbc_ret12."</option>\n";
				}
				else
				{
					echo "   											<option value=\"".$odbc_ret11.":".$odbc_ret13."\">(".$odbc_ret13.") (".$odbc_ret11.") ".$odbc_ret12."</option>\n";
				}
			}
	
			odbc_free_result($odbc_res1);
			odbc_close($odbc_conn1);
	
			echo "   										</select>\n";
		}
	
		//odbc_close($odbc_conn);
	
		echo "   									</td>\n";
		echo "   								</tr>\n";
		echo "   									<input type=\"hidden\" name=\"rmas_div\" value=\"".$row[30]."\">\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>Renovation Link</b></td>\n";
		echo "   									<td align=\"left\">\n";
		
		if ($row[17]==0)
		{
			echo "   										<input class=\"bboxb\" type=\"text\" name=\"masid\" value=\"".trim($row[29])."\" size=\"5\" maxlength=\"5\" DISABLED>\n";
			echo "   										<input type=\"hidden\" name=\"rmasid\" value=\"".trim($row[29])."\">\n";
		}
		else
		{
			//echo $row[17]."<br>";
			//echo $row[29]."<br>";
			//echo $row[30]."<br>";
			
			$odbc_conn2	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","");
			//$odbc_qry1	=	"SELECT SalespersonNumber,Name,Division FROM MAS_".$row[17].".dbo.ARD_SalespersonMasterFile WHERE Division='".$row[18]."';";
			$odbc_qry2	=	"SELECT SalespersonNumber,Name,Division FROM MAS_".$row[17].".dbo.ARD_SalespersonMasterFile WHERE Name LIKE '%".substr($row[3],0,3)."%';";
			$odbc_res2	=	odbc_exec($odbc_conn2, $odbc_qry2);
	
			//echo $odbc_qry1."<br>";
			
			echo "   										<select name=\"rmasid\">\n";
			echo "   											<option value=\"0\">None</option>\n";
	
			while(odbc_fetch_row($odbc_res2))
			{
				$odbc_ret21 = odbc_result($odbc_res2, 1);
				$odbc_ret22 = odbc_result($odbc_res2, 2);
				$odbc_ret23 = odbc_result($odbc_res2, 3);
	
				if ($odbc_ret23==$row[30] && $odbc_ret21==$row[29])
				{
					echo "   											<option value=\"".$odbc_ret21.":".$odbc_ret23."\" SELECTED>(".$odbc_ret23.") (".$odbc_ret21.") ".$odbc_ret22."</option>\n";
				}
				else
				{
					echo "   											<option value=\"".$odbc_ret21.":".$odbc_ret23."\">(".$odbc_ret23.") (".$odbc_ret21.") ".$odbc_ret22."</option>\n";
				}
			}
	
			odbc_free_result($odbc_res2);
			odbc_close($odbc_conn2);
	
			echo "   										</select>\n";
		}
	
		//odbc_close($odbc_conn);
	
		echo "   									</td>\n";
		echo "   								</tr>\n";
	}
	else
	{
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>QB Employee ID</b></td>\n";
		echo "   									<td align=\"left\">".$row[57]."</td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>QB Employee ES</b></td>\n";
		echo "   									<td align=\"left\">".$row[58]."</td>\n";
		echo "									</tr>\n";
	}
	echo "								</table>\n";
}

function user_sysinfo_panel($sid)
{
	$qry  = "SELECT ";
	$qry .= "securityid, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admindate ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry6 = "SELECT securityid FROM logstate WHERE securityid=".$row['securityid'].";";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row['securityid']."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	echo "								<table>\n";
	echo "									<tr>\n";
	echo "   									<td align=\"right\"><b>Logged In</b></td>\n";
	echo "										<td align=\"left\">\n";

	if ($nrow6 >= 1)
	{
		echo "<font color=\"red\">Yes</font>";
	}
	else
	{
		echo "No";
	}

	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Last Login</b></td>\n";
	echo "										<td align=\"left\">";
	
	if (strtotime($row['curr_login']) < strtotime('1/1/2004'))
	{
		echo "<font color=\"red\">Never</font>";
	}
	else
	{
		echo date('m/d/y g:iA',strtotime($row['curr_login']));
	}
	
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Date Added</b></td>\n";
	echo "										<td align=\"left\">";
	
	echo date('m/d/y g:iA',strtotime($row['added']));
		
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Date Updated</b></td>\n";
	echo "										<td align=\"left\">";
	
	if (strtotime($row['admindate']) < strtotime('1/1/2004'))
	{
		echo "<font color=\"red\">Never</font>";
	}
	else
	{
		echo date('m/d/y g:iA',strtotime($row['admindate']));
	}
	
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Updated By</b></td>\n";
	echo "										<td align=\"left\">";
	
	if (strtotime($row['admindate']) >= strtotime('1/1/2004'))
	{
		echo "".$row5['fname']." ".$row5['lname']."";
	}
	
	echo "										</td>\n";
	//echo "										<td align=\"left\">".$row5['fname']." ".$row5['lname']."</td>\n";
	
	if ($row10['Logons'] > 0)
	{
		$userrate=round(($row10['Logoffs']/$row10['Logons']) * 100);
		//$userrate=round((($row10['Logoffs'] + $row10['Events'])/($row10['Logons'] + $row10['Events'])) * 100);
	}
	else
	{
		$userrate='NA';
	}
	
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>User Rating</b></td>\n";
	echo "										<td align=\"left\">".$userrate."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Logons</b></td>\n";
	echo "										<td align=\"left\">".$row10['Logons']."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Logoffs</b></td>\n";
	echo "										<td align=\"left\">".$row10['Logoffs']."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Events</b></td>\n";
	echo "										<td align=\"left\">".$row10['Events']."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td><img src=\"images/pixel.gif\"></td>\n";
	echo "										<td><img src=\"images/pixel.gif\"></td>\n";
	echo "									</tr>\n";
	echo "							</table>\n";
}

function user_officeaccess_panel()
{
	$altid_ar=array();

	$qry0 = "SELECT officeid,code,name,gm,sm FROM offices WHERE officeid='".$_REQUEST['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid='".$_REQUEST['officeid']."';";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt ";
	$qry .= " FROM security WHERE securityid='".$_REQUEST['userid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!='".$row[0]."' and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid='".$row[0]."'";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);

	$sarray=explode(",",$row[5]);
	$marray=explode(",",$row[15]);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}

	$brdr=0;
	$hlpnd=1;
	
	if ($sarray[6]==7)
	{
		$qry4 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
		$res4 = mssql_query($qry4);
		$nrow4= mssql_num_rows($res4);

		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"set_offlist\">\n";
		echo "<input type=\"hidden\" name=\"userid\" value=\"".$row[0]."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$row[1]."\">\n";
		echo "<input type=\"hidden\" name=\"alevel\" value=\"".$row[5]."\">\n";
		
		echo "<table>\n";
		echo "<tr>\n";
		echo "   <td>\n";
		echo "		<table width=\"100%\">\n";
		echo "			<tr>\n";
		echo "				<td class=\"ltgray_und\" align=\"left\"><b>Office Access List</b></td>\n";
		echo "				<td class=\"ltgray_und\" align=\"right\">Replicate?</td>\n";
		echo "				<td class=\"ltgray_und\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"replicate\" value=\"1\" title=\"Check this box to replicate security levels\"></td>\n";
		echo "				<td class=\"ltgray_und\" align=\"right\"><input class=\"buttondkgry\" type=\"submit\" value=\"Set Access\"></td>\n";
		echo "			</tr>\n";

		$altoffs=explode(",",$row[12]);
		while ($row4 = mssql_fetch_array($res4))
		{
			echo "				<tr>\n";

			if (is_array($altoffs) && in_array($row4['officeid'],$altoffs))
			{
				$qry5 = "SELECT * FROM jest..alt_security_levels WHERE oid='".$row4['officeid']."' and sid='".$row[0]."';";
				$res5 = mssql_query($qry5);
				$nrow5= mssql_num_rows($res5);
				
				echo "				<td class=\"blu_und\" colspan=\"2\" align=\"left\">".$row4['name']."</td>\n";
				echo "				<td class=\"blu_und\" align=\"center\">\n";
				echo "					<input class=\"transnb\" type=\"checkbox\" name=\"chk".$row4['officeid']."\" value=\"".$row4['officeid']."\" CHECKED>\n";
				echo "				</td>\n";
				echo "				<td class=\"blu_und\" align=\"center\">\n";
				
				if ($nrow5 > 1)
				{
					echo "Security Error Occured";
				}
				elseif ($nrow5 == 1)
				{
					$row5 = mssql_fetch_array($res5);
					slevelformflat(explode(",",$row5['slevel']),$row5['oid']);
				}
				else
				{
					slevelformflat('',$row4['officeid']);
				}
				
				echo "				</td>\n";
			}
			else
			{
				echo "				<td class=\"wh_und\" colspan=\"2\" align=\"left\">".$row4['name']."</td>\n";
				echo "				<td class=\"wh_und\" align=\"center\">\n";
				echo "					<input class=\"checkboxwh\" type=\"checkbox\" name=\"chk".$row4['officeid']."\" value=\"".$row4['officeid']."\">\n";
				echo "				</td>\n";
				echo "				<td class=\"wh_und\" align=\"left\"></td>\n";
			}

			echo "				</tr>\n";
		}

		echo "			</table>\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function viewuser_TED()
{
	error_reporting(E_ALL);
	//ini_set('display_errors','On');
	
	if ($_SESSION['tlev'] < 8)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Resource</b>";
		exit;
	}
	
	$altid_ar=array();

	$qry0 = "SELECT officeid,code,name,gm,sm FROM offices WHERE officeid='".$_REQUEST['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid='".$_REQUEST['officeid']."';";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt ";
	$qry .= " FROM security WHERE securityid='".$_REQUEST['userid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row['securityid']."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$brdr=0;
	$hlpnd=1;
	echo "<script type=\"text/javascript\" src=\"js/jquery_users_func.js\"></script>\n";
	echo "<table align=\"center\" width=\"950px\" border=\"".$brdr."\">\n";
	echo "<tr>\n";
	echo "   <td valign=\"top\">\n";
	echo "		<table align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "			<tr>\n";
	echo "   			<td valign=\"top\">\n";
	echo "					<table align=\"center\" width=\"100%\" border=\"".$brdr."\"\n";
	echo "						<tr>\n";
	echo "   						<td colspan=\"2\">\n";
	echo "								<table class=\"outer\" align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "									<tr>\n";
	echo "   									<td class=\"gray\" valign=\"center\">\n";
	echo "											<table><tr><td><h2><b>Edit User: <i>".$row['fname']." ".$row['lname']."</i></b></h2></td><td></td></tr></table>\n";
	echo "										</td>\n";
	echo "   									<td class=\"gray\" valign=\"right\" width=\"20px\">\n";
	echo "         									<form method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"users\">\n";
	echo "											<input class=\"transnb\" type=\"image\" src=\"images/application_view_list.png\" title=\"User List\">\n";
	echo "         									</form>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "   						<td valign=\"top\" align=\"left\">\n";
	
	echo "			<div id=\"ViewUserInfo\">\n";	
	echo "				<ul>\n";
	echo "					<li><a href=\"#tab0\"><em>Account Info</em></a></li>\n";
	echo "					<li><a href=\"#tab1\"><em>Security Levels</em></a></li>\n";
	echo "					<li><a href=\"#tab2\"><em>Functional Access</em></a></li>\n";
	echo "					<li><a href=\"#tab3\"><em>Profiles</em></a></li>\n";
	echo "					<li><a href=\"#tab4\"><em>Sales Rep</em></a></li>\n";
	echo "					<li><a href=\"#tab5\"><em>Accounting</em></a></li>\n";
	echo "					<li><a href=\"#tab6\"><em>Office Access</em></a></li>\n";
	echo "					<li><a href=\"#tab7\"><em>System Information</em></a></li>\n";
	echo "				</ul>\n";
	
	/*
	echo "<form id=\"UpdateUserForm\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"users\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"update\">\n";
	echo "<input type=\"hidden\" name=\"userid\" value=\"".$row['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"office\" value=\"".$row['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"off_demo\" value=\"".$row['off_demo']."\">\n";
	echo "<input type=\"hidden\" name=\"adminid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"dsgfperiod\" value=\"".trim($row['dsgfperiod'])."\">\n";
	echo "<input type=\"hidden\" name=\"dsgfarray\" value=\"".trim($row['dsgfarray'])."\">\n";
	echo "<input type=\"hidden\" name=\"menutype\" value=\"O\">\n";
	echo "<input type=\"hidden\" name=\"emailchrg\" value=\"".$row['emailchrg']."\">\n";
	echo "<input type=\"hidden\" name=\"JobCommEdit\" value=\"".$row['JobCommEdit']."\">\n";
	*/
	echo "				<div id=\"tab0\">\n";
	echo "					<p>\n";
	
	user_account_panel($_REQUEST['userid']);
	
	echo "					</p>\n";
	echo "				</div>\n";
	
	echo "				<div id=\"tab1\">\n";
	echo "					<p>\n";
	
	user_security_panel($_REQUEST['userid']);

	echo "					</p>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab2\">\n";
	echo "					<p>\n";
	
	user_function_panel($_REQUEST['userid']);
	
	echo "					</p>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab3\">\n";
	echo "					<p>\n";
	
	user_altaccess_panel($_REQUEST['userid']);
	
	echo "					</p>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab4\">\n";
	echo "					<p>\n";

	user_srep_panel($_REQUEST['userid']);
	
	echo "					</p>\n";
	echo "				</div>\n";
	echo "				<div id=\"tab5\">\n";
	
	user_accounting_panel($_REQUEST['userid']);
	
	echo "				</div>\n";
	//echo "</form>\n";
	echo "				<div id=\"tab7\">\n";
	
	user_sysinfo_panel($_REQUEST['userid']);
	
	echo "				</div>\n";
	echo "				<div id=\"tab6\">\n";
	
	user_officeaccess_panel($_REQUEST['userid']);
	
	echo "				</div>\n";
	echo "			</div>\n";
	
	echo "							</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "		<td valign=\"top\">\n";
	echo "			<table align=\"left\" border=$brdr>\n";

	if ($_SESSION['tlev'] >= 9)
	{
		echo "<tr>\n";
		echo "   <td align=\"right\">\n";
		echo "		<table border=0>\n";
		echo "			<tr>\n";
		echo "   			<td>\n";
		echo "					<input class=\"buttondkgrypnl80\" id=\"submituserupdate\" type=\"submit\" value=\"Update\">\n";
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "   			<td>\n";
		echo "					<form method=\"post\">\n";
		echo "					<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "					<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		echo "					<input type=\"hidden\" name=\"subq\" value=\"rp\">\n";
		echo "					<input type=\"hidden\" name=\"userid\" value=\"".$row['securityid']."\">\n";
		echo "					<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Reset Pswrd\">\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";

		if ($row['logstate']==1)
		{
			echo "			<tr>\n";
			echo "   			<td>\n";
			echo "					<form method=\"post\">\n";
			echo "					<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "					<input type=\"hidden\" name=\"call\" value=\"users\">\n";
			echo "					<input type=\"hidden\" name=\"subq\" value=\"cl\">\n";
			echo "					<input type=\"hidden\" name=\"userid\" value=\"".$row['securityid']."\">\n";
			echo "					<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
			echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Clear Login\">\n";
			echo "					</form>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
		}

		echo "		</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
	}

	if (isset($row['srep']) && $row['srep']==1)
	{
		echo "<tr>\n";
		echo "   <td align=\"center\">\n";
		
		srep_page_link($row['officeid'],$row['securityid']);
		
		echo "	</td>\n";
		echo "</tr>\n";
	}
	

	if ($nrow3 > 0)
	{
		echo "												<tr>\n";
		echo "													<td align=\"left\"><b>Manager of</b></td>\n";
		echo "												</tr>\n";

		while ($row3 = mssql_fetch_row($res3))
		{
			echo "												<tr>\n";
			echo "													<td align=\"right\">\n";
				
			$slev=explode(",",$row3[2]);
			if ($slev[6]==0)
			{
				echo "													<font color=\"red\">".$row3[1]." ".$row3[0]."</font>";
			}
			else
			{
				echo $row3[1]." ".$row3[0]."";
			}
			
			echo "													</td>\n";
			echo "												</tr>\n";
		}
	}
		
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
}

function set_altoffice()
{
	error_reporting(E_ALL);
	//show_post_vars();
	$icount=0;
	$setoff='';
	if (is_array($_POST))
	{
		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,3)=="chk")
			{
				$icount++;
			}
		}

		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,3)=="chk")
			{
				$asid=substr($n,3);
				if (array_key_exists("chk".$asid,$_POST))
				{
					if ($icount==1)
					{
						$off=$_REQUEST['chk'.$asid];
					}
					else
					{
						$off=$_REQUEST['chk'.$asid].',';
					}
					
					if (array_key_exists("altlevel".$asid,$_POST))
					{
						$qryZa = "select id from alt_security_levels where oid='".$asid."' and sid='".$_REQUEST['userid']."';";
						$resZa = mssql_query($qryZa);
						$rowZa = mssql_fetch_array($resZa);
						$nrowZa= mssql_num_rows($resZa);
						
						if (isset($_REQUEST['replicate'])&& $_REQUEST['replicate']==1)
						{
							$lvls=$_REQUEST['alevel'];
						}
						else
						{
							$lvls=implode(",",$_REQUEST['altlevel'.$asid]);
						}
						
						if ($nrowZa > 0)
						{
							$qryZb = "UPDATE alt_security_levels SET slevel='".$lvls."' WHERE sid='".$_REQUEST['userid']."' and oid='".$asid."';";
							//echo "UPDATE<BR>";
						}
						else
						{
							$qryZb = "INSERT INTO alt_security_levels (sid,oid,slevel) VALUES ('".$_REQUEST['userid']."','".$asid."','".$lvls."');";
							//echo "INSERT<BR>";
						}
						$resZb = mssql_query($qryZb);
						//echo implode(",",$_REQUEST['altlevel'.$asid]);
						//echo "<br>";
					}
					
					$setoff=$setoff.$off;
					$icount--;
				}
			}
		}

		$qry = "UPDATE security SET altoffices='".$setoff."' WHERE securityid='".$_REQUEST['userid']."';";
		$res = mssql_query($qry);
		//echo "Offices=".$setoff;
	}
	viewuser();
}

?>