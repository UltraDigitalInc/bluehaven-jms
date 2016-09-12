<?php

session_start();

if (empty($_GET['sid']))
{
	die('Error: Malformed Request (Type 0)');
}

if (empty($_SESSION['securityid']))
{
	die('Error: Malformed Request (Type 1)');
}

if (strip_tags($_GET['sid']) != md5($_SESSION['securityid']))
{
	die('Error: Malformed Request (Type 2)');
}

/*
$hostname = "192.168.1.22";
$username = "MAS_REPORTS";
$password = "reports";
$dbname   = "ZE_Stats";

mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
mssql_select_db($dbname) or die("Table unavailable");
*/

	include ("./ajax_drilldetail_func.php");

	if (empty($_GET['call']))
	{
		die('Error: Malformed Request (Type 3)');
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="ov")
	{
		drill_ov_ind();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="pd")
	{
		//echo "Drill Detail Start Parse<br>";
		drill_pd_ind();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="cb")
	{
		drill_cb_ind();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="hp")
	{
		drill_hp_ind();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="IVR")
	{
		drill_ivr_range();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="IVRd")
	{
		drill_ivr_detail();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="bidadd")
	{
		// Add Bid Item Popup
		drill_bid_add();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="vac")
	{
		// View Total Cost Bid Items Popup
		drill_view_bid_cost();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="mpaadd")
	{
		// Add MPA Item Popup
		drill_mpa_add();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="vmpa")
	{
		// View Total MPA Items Popup
		drill_view_mpa_cost();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="dsdvsr")
	{
		// View Total MPA Items Popup
		drill_view_ds_salesrep();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="dsdvof")
	{
		// View Total MPA Items Popup
		drill_view_ds_office();
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="vroet")
	{
		view_EmailTemplate_RO();
	}

?>