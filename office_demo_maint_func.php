<?php

function advrep_search()
{
	echo "Advanced Reporting Not implemented.";
	exit;
}

function advrep_result()
{
	echo "Not implemented.";
	exit;
}

function addoff()
{
	echo "New Office Adds Not implemented.";
	exit;
}

function listoff()
{
	if ($_SESSION['rlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}
	elseif ($_SESSION['rlev'] > 6)
	{
		if (!empty($_POST['subq']) && $_POST['subq']=="search" && !empty($_POST['sfield']) && !empty($_POST['sparam']))
		{
			$sparam=$_POST['sparam'];
			$qry = "SELECT * FROM offices WHERE ".$_POST['sfield']." LIKE '".$_POST['sparam']."%' ORDER BY name ASC";
			$res = mssql_query($qry);
			$nrow= mssql_num_rows($res);
		}
		else
		{
			$sparam="";
			//$qry = "SELECT * FROM offices WHERE d_active=1 ORDER BY name ASC";
			$qry = "SELECT * FROM offices ORDER BY name ASC";
			$res = mssql_query($qry);
			$nrow= mssql_num_rows($res);
		}

		echo "<table align=\"center\" width=\"60%\" border=0>\n";
		echo "<tr>\n";
		echo "   <td class=\"gray\">\n";
		echo "		<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
		echo "			<tr>\n";
		echo "   			<td class=\"gray\" colspan=\"7\">\n";
		echo "					<table width=\"100%\" border=0>\n";
		echo "						<tr>\n";
		echo "   						<td class=\"gray\">\n";
		echo "								<b>Office List</b>\n";
		echo "							</td>\n";
		echo "   							<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"officerep\">\n";
		echo "   							<input type=\"hidden\" name=\"subq\" value=\"search\">\n";
		echo "   						<td class=\"gray\" align=\"right\">\n";
		echo "								Search for: ";
		echo "               			<input class=\"bboxl\" type=\"text\" name=\"sparam\" size=\"10\" value=\"".$sparam."\">\n";
		echo "                  		<select name=\"sfield\">\n";

		if (!empty($_POST['subq']) && $_POST['subq']=="search" && !empty($_POST['sfield']) && !empty($_POST['sparam']))
		{
			if ($_POST['sfield']=="name")
			{
				echo "                     		<option value=\"name\" SELECTED>Office Name</option>\n";
				echo "                     		<option value=\"d_market\">Overall Market</option>\n";
			}
			else
			{
				echo "                     		<option value=\"name\">Office Name</option>\n";
				echo "                     		<option value=\"d_market\" SELECTED>Overall Market</option>\n";
			}
		}
		else
		{
			echo "                     		<option value=\"name\" SELECTED>Office Name</option>\n";
			echo "                     		<option value=\"d_market\">Overall Market</option>\n";
		}

		echo "                  		</select>\n";
		echo "							</td>\n";
		echo "   						<td class=\"gray\" align=\"right\" width=\"81px\">\n";
		echo "   							<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Quick Search\">\n";
		echo "							</td>\n";
		echo "   							</form>\n";
		/*
		echo "   							<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"officerep\">\n";
		echo "   							<input type=\"hidden\" name=\"subq\" value=\"search\">\n";
		echo "   						<td class=\"gray\" align=\"right\" width=\"81px\">\n";
		echo "   							<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Adv Search\">\n";
		echo "							</td>\n";
		echo "   							</form>\n";
		*/
		echo "   							<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"officerep\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"advrep\">\n";
		echo "   							<input type=\"hidden\" name=\"req\" value=\"1\">\n";
		echo "   						<td class=\"gray\" align=\"right\" width=\"81px\">\n";
		echo "   							<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Adv Reporting\">\n";
		echo "							</td>\n";
		echo "   							</form>\n";
		echo "   							<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"officerep\">\n";
		echo "   							<input type=\"hidden\" name=\"subq\" value=\"add\">\n";
		echo "   						<td class=\"gray\" align=\"right\" width=\"81px\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"New Office\" DISABLED>\n";
		echo "							</td>\n";
		echo "   							</form>\n";
		echo "						</tr>\n";
		echo "					</table>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td class=\"ltgray_und\"><b>Name</b></td>\n";
		echo "				<td class=\"ltgray_und\"><b>City</b></td>\n";
		echo "				<td class=\"ltgray_und\"><b>Phone</b></td>\n";
		echo "				<td class=\"ltgray_und\"><b>Zip</b></td>\n";
		echo "				<td class=\"ltgray_und\"><b>Matrix Ring</b></td>\n";
		echo "				<td class=\"ltgray_und\"><b>GM</b></td>\n";
		echo "				<td class=\"ltgray_und\" align=\"right\"><font color=\"red\">".$nrow."</font> Record(s)</td>\n";
		echo "			</tr>\n";

		while ($row = mssql_fetch_array($res))
		{
			echo "			<tr>\n";
			
			if ($row['active']==1)
			{
				echo "				<td class=\"wh_und\"><b>".$row['name']."</b></td>\n";
			}
			else 
			{
				echo "				<td class=\"wh_und\">".$row['name']."</td>\n";
			}
			
			echo "				<td class=\"wh_und\">".$row['city']."</td>\n";
			echo "				<td class=\"wh_und\">".$row['phone']."</td>\n";
			echo "				<td class=\"wh_und\">".$row['zip']."</td>\n";
			echo "				<td class=\"wh_und\">".$row['ringto']."</td>\n";
			echo "				<td class=\"wh_und\">".$row['d_gm']."</td>\n";
			echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "		<input type=\"hidden\" name=\"call\" value=\"officerep\">\n";
			echo "   	<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
			echo "   	<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
			echo "   <td class=\"wh_und\" align=\"right\">\n";
			echo "					<input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
			echo "				</td>\n";
			echo "   </form>\n";
			echo "			</tr>\n";
		}

		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function viewoff()
{
	if ($_SESSION['rlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}

	$oarr=array();

	$officeid		=$_POST['officeid'];
	$securityid	=$_SESSION['securityid'];

	$qry0 = "SELECT * FROM offices ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	while ($row0 = mssql_fetch_array($res0))
	{
		$oarr[]=$row0['officeid'];
	}

	//print_r($oarr);
	$coid=array_search($officeid,$oarr);
	$poid=$coid-1;
	$noid=$coid+1;

	//echo "COID: ".$coid."<br>";
	//echo "POID: ".$poid."<br>";
	//echo "NOID: ".$noid."<br>";

	$qry = "SELECT * FROM offices WHERE officeid='".$officeid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"officerep\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"update\">\n";
	echo "<input type=\"hidden\" name=\"secid\" value=\"".$securityid."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"zip\" value=\"".$row['zip']."\">\n";
	echo "<table align=\"center\" width=\"50%\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\">\n";
	echo "         <table class=\"outer\" width=\"100%\" align=\"left\">\n";
	echo "   			<tr>\n";
	echo "   				<td class=\"gray\" align=\"left\"><b>OFFICE INFORMATION</b></td>\n";
	echo "   				<td class=\"gray\" align=\"right\"><b>Last Update:</b> <input class=\"bboxl\" type=\"text\" value=\"".$row['d_update']."\" size=\"30\"></td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "   <tr>\n";
	echo "      <td width=\"50%\" valign=\"top\" align=\"left\">\n";
	echo "         <table class=\"outer\" width=\"100%\" height=\"370px\" border=0>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" width=\"125px\" NOWRAP><b>Office Name:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"name\" value=\"".$row['name']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Address:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"addr1\" value=\"".$row['addr1']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"addr2\" value=\"".$row['addr2']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>City:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"city\" value=\"".$row['city']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>State:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"state\" value=\"".$row['state']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Zip:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"zip\" value=\"".$row['zip']."\" size=\"30\" DISABLED title=\"This field cannot be changed from this screen\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Main Phone:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"phone\" value=\"".$row['phone']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Main Fax:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"fax\" value=\"".$row['fax']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Routing Matrix #:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"ringto\" value=\"".$row['ringto']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Main Email:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"d_email\" value=\"".$row['d_email']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>GM:</b></b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"d_gm\" value=\"".$row['d_gm']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>GM Mobile:</b></b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"d_gmph\" value=\"".$row['d_gmph']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Sales Manager:</b></b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"d_sm\" value=\"".$row['d_sm']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Overall Market:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"d_market\" value=\"".$row['d_market']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Corp. Name:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"d_corpname\" value=\"".$row['d_corpname']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>License:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"d_lic\" value=\"".$row['d_lic']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" NOWRAP><b>Lic. Holder:</b></td>\n";
	echo "               <td class=\"gray\"><input class=\"bboxl\" type=\"text\" name=\"d_licholder\" value=\"".$row['d_licholder']."\" size=\"30\"></td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\" width=\"50%\" align=\"left\">\n";
	echo "         <table class=\"outer\" width=\"100%\" height=\"370px\" border=0>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>Internal Only:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_i\">\n";

	if ($row['d_i']==0)
	{
		echo "                     <option value=\"0\" SELECTED>No</option>\n";
		echo "                     <option value=\"1\">Yes</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">No</option>\n";
		echo "                     <option value=\"1\" SELECTED>Yes</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>Office Type:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_type\">\n";

	if ($row['d_type']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Main</option>\n";
		echo "                     <option value=\"1\">Satellite</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Main</option>\n";
		echo "                     <option value=\"1\" SELECTED>Satellite</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>Ownership:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_o\">\n";

	if ($row['d_o']==0)
	{
		echo "                     <option value=\"0\" SELECTED>PA</option>\n";
		echo "                     <option value=\"1\">FIT</option>\n";
		echo "                     <option value=\"2\">FR</option>\n";
	}
	elseif ($row['d_o']==1)
	{
		echo "                     <option value=\"0\">PA</option>\n";
		echo "                     <option value=\"1\" SELECTED>FIT</option>\n";
		echo "                     <option value=\"2\">FR</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">PA</option>\n";
		echo "                     <option value=\"1\">FIT</option>\n";
		echo "                     <option value=\"2\" SELECTED>FR</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>Pool Type:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_pt\">\n";

	if ($row['d_pt']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Concrete</option>\n";
		echo "                     <option value=\"1\">Fiber</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Concrete</option>\n";
		echo "                     <option value=\"1\" SELECTED>Fiber</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>Remodel:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_remodel\">\n";

	if ($row['d_remodel']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
		echo "                     <option value=\"1\">Major</option>\n";
		echo "                     <option value=\"2\">Minor</option>\n";
	}
	elseif ($row['d_remodel']==1)
	{
		echo "                     <option value=\"0\">None</option>\n";
		echo "                     <option value=\"1\" SELECTED>Major</option>\n";
		echo "                     <option value=\"2\">Minor</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">None</option>\n";
		echo "                     <option value=\"1\">Major</option>\n";
		echo "                     <option value=\"2\" SELECTED>Minor</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>Display Pool:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_dp\">\n";

	if ($row['d_dp']==0)
	{
		echo "                     <option value=\"0\" SELECTED>0</option>\n";
		echo "                     <option value=\"1\">1</option>\n";
		echo "                     <option value=\"2\">2</option>\n";
	}
	elseif ($row['d_dp']==1)
	{
		echo "                     <option value=\"0\">0</option>\n";
		echo "                     <option value=\"1\" SELECTED>1</option>\n";
		echo "                     <option value=\"2\">2</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">0</option>\n";
		echo "                     <option value=\"1\" SELECTED>1</option>\n";
		echo "                     <option value=\"2\" SELECTED>2</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>DP Location:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_io\">\n";

	if ($row['d_io']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Indoor</option>\n";
		echo "                     <option value=\"1\">Outdoor</option>\n";
		echo "                     <option value=\"2\">Both</option>\n";
	}
	elseif ($row['d_io']==1)
	{
		echo "                     <option value=\"0\">Indoor</option>\n";
		echo "                     <option value=\"1\" SELECTED>Outdoor</option>\n";
		echo "                     <option value=\"2\">Both</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Indoor</option>\n";
		echo "                     <option value=\"1\">Outdoor</option>\n";
		echo "                     <option value=\"2\" SELECTED>Both</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>License in Ad:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_licinad\">\n";

	if ($row['d_licinad']==0)
	{
		echo "                     <option value=\"0\" SELECTED>No</option>\n";
		echo "                     <option value=\"1\">Yes</option>\n";
		echo "                     <option value=\"2\">Preference</option>\n";
	}
	elseif ($row['d_licinad']==1)
	{
		echo "                     <option value=\"0\">No</option>\n";
		echo "                     <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                     <option value=\"2\">Preference</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">No</option>\n";
		echo "                     <option value=\"1\">Yes</option>\n";
		echo "                     <option value=\"2\" SELECTED>Preference</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>Suite # in Ad:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_sia\">\n";

	if ($row['d_sia']==0)
	{
		echo "                     <option value=\"0\" SELECTED>No</option>\n";
		echo "                     <option value=\"1\">Yes</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">No</option>\n";
		echo "                     <option value=\"1\" SELECTED>Yes</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>Purification:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_pure\">\n";

	if ($row['d_pure']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Salt</option>\n";
		echo "                     <option value=\"1\">Ozone</option>\n";
		echo "                     <option value=\"2\">Both</option>\n";
	}
	elseif ($row['d_pure']==1)
	{
		echo "                     <option value=\"0\">Salt</option>\n";
		echo "                     <option value=\"1\" SELECTED>Ozone</option>\n";
		echo "                     <option value=\"2\">Both</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Salt</option>\n";
		echo "                     <option value=\"1\">Ozone</option>\n";
		echo "                     <option value=\"2\" SELECTED>Both</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>Concrete Type:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_conc\">\n";

	if ($row['d_conc']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Gunite</option>\n";
		echo "                     <option value=\"1\">Shotcrete</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Gunite</option>\n";
		echo "                     <option value=\"1\" SELECTED>Shotcrete</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>BBB:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_bbb\">\n";

	if ($row['d_bbb']==0)
	{
		echo "                     <option value=\"0\" SELECTED>No</option>\n";
		echo "                     <option value=\"1\">Regular</option>\n";
		echo "                     <option value=\"2\">Local</option>\n";
	}
	elseif ($row['d_bbb']==1)
	{
		echo "                     <option value=\"0\">No</option>\n";
		echo "                     <option value=\"1\" SELECTED>Regular</option>\n";
		echo "                     <option value=\"2\">Local</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">No</option>\n";
		echo "                     <option value=\"1\" SELECTED>Regular</option>\n";
		echo "                     <option value=\"2\">Local</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\"><b>APSP:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_asps\">\n";

	if ($row['d_asps']==0)
	{
		echo "                     <option value=\"0\" SELECTED>No</option>\n";
		echo "                     <option value=\"1\">Yes</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">No</option>\n";
		echo "                     <option value=\"1\" SELECTED>Yes</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" width=\"100px\"><b>Active:</b></td>\n";
	echo "               <td class=\"gray\">\n";
	echo "                  <select name=\"d_active\">\n";

	if ($row['d_active']==1)
	{
		echo "							<option value=\"1\" SELECTED>Yes</option>\n";
		echo "							<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "							<option value=\"1\">Yes</option>\n";
		echo "							<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"2\">\n";
	echo "         <table class=\"outer\" width=\"100%\" align=\"left\">\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" width=\"125px\"><b>Comment:</b></td>\n";
	echo "               <td class=\"gray\" width=\"500px\"><input class=\"bboxl\" type=\"text\" name=\"comment\" value=\"".$row['comment']."\" size=\"75\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"right\" colspan=\"2\"><input class=\"buttondkgry\" type=\"submit\" value=\"Update\"></td>\n";
	echo "            </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "            </form>\n";
	echo "</table>\n";
}

function updateoff()
{
	$officeid=$_POST['officeid'];
	$qry  = "UPDATE offices SET ";
	$qry .= "name='".replacequote($_POST['name'])."',";
	$qry .= "addr1='".replacequote($_POST['addr1'])."',";
	$qry .= "addr2='".replacequote($_POST['addr2'])."',";
	$qry .= "city='".replacequote($_POST['city'])."',";
	$qry .= "state='".replacequote($_POST['state'])."',";
	$qry .= "zip='".replacequote($_POST['zip'])."',";
	$qry .= "phone='".replacequote($_POST['phone'])."',";
	$qry .= "fax='".replacequote($_POST['fax'])."',";
	$qry .= "d_gm='".replacequote($_POST['d_gm'])."',";
	$qry .= "d_gmph='".replacequote($_POST['d_gmph'])."',";
	$qry .= "d_sm='".replacequote($_POST['d_sm'])."',";
	$qry .= "d_type='".$_POST['d_type']."',";
	$qry .= "d_market='".replacequote($_POST['d_market'])."',";
	$qry .= "d_corpname='".replacequote($_POST['d_corpname'])."',";
	$qry .= "d_lic='".replacequote($_POST['d_lic'])."',";
	$qry .= "d_licholder='".replacequote($_POST['d_licholder'])."',";
	$qry .= "d_i='".$_POST['d_i']."',";
	$qry .= "d_o='".$_POST['d_o']."',";
	$qry .= "d_pt='".$_POST['d_pt']."',";
	$qry .= "d_remodel='".$_POST['d_remodel']."',";
	$qry .= "d_dp='".$_POST['d_dp']."',";
	$qry .= "d_io='".$_POST['d_io']."',";
	$qry .= "d_licinad='".$_POST['d_licinad']."',";
	$qry .= "d_sia='".$_POST['d_sia']."',";
	$qry .= "d_pure='".$_POST['d_pure']."',";
	$qry .= "d_conc='".$_POST['d_conc']."',";
	$qry .= "d_bbb='".$_POST['d_bbb']."',";
	$qry .= "d_asps='".$_POST['d_asps']."',";
	$qry .= "d_email='".replacequote($_POST['d_email'])."',";
	$qry .= "comment='".replacequote($_POST['comment'])."',";
	$qry .= "ringto='".replacequote($_POST['ringto'])."',";
	$qry .= "d_active='".$_POST['d_active']."',";
	$qry .= "d_update=getdate()";
	$qry .= " WHERE officeid=".$officeid.";";
	$res  = mssql_query($qry);
	//$row  = mssql_fetch_row($res);

	//echo $qry;
	viewoff();
	//echo "<pre>";
	//print_r($_POST);
	//echo "</pre>";

}

?>
