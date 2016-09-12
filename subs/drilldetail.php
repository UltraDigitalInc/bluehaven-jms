<?php

if (empty($_GET['sid']))
{
	die('Error: Malformed Request (Type 0)');
}

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//print_r($_SESSION);

if (empty($_SESSION['securityid']))
{
	die('Error: Malformed Request (Type 1)');
}

if (strip_tags($_GET['sid']) != md5($_SESSION['securityid']))
{
	die('Error: Malformed Request (Type 2)');
}

	include ("./drilldetail_func.php");

	echo "<html>\n";
	echo "<head>\n";
	
	if (!empty($_GET['call']) && $_GET['call']=="hp")
	{	
		echo "   <title>BHNMI: JMS Help System</title>\n";
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="bidadd")
	{	
		echo "   <title>BHNMI: Add Cost Bid Item</title>\n";
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="vac")
	{	
		echo "   <title>BHNMI: Bid Cost Breakout</title>\n";
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="mpaadd")
	{	
		echo "   <title>BHNMI: Add Manual Phase Adjust Item</title>\n";
	}
	elseif (!empty($_GET['call']) && $_GET['call']=="vmpa")
	{	
		echo "   <title>BHNMI: Manual Phase Adjust Breakout</title>\n";
	}
	else
	{
		echo "   <title>BHNMI Reports: Account Detail</title>\n";
	}
	
	echo "   <link rel=\"stylesheet\" type=\"text/css\" href=\"../yui/build/reset-fonts/reset-fonts.css\" />\n";
	echo "   <link rel=\"stylesheet\" type=\"text/css\" href=\"../bh_main.css\" media=\"screen\" />\n";
	echo "   <link rel=\"stylesheet\" type=\"text/css\" href=\"../bh_main_print.css\" media=\"print\" />\n";
	?>
	
	<script type="text/javascript">
		window.opener='JMSmain';
	</script>
	
	<?php
	echo "</head>\n";
	echo "   <body>\n";
	echo "         <table width=\"100%\">\n";
	echo "		<tr>\n";
	echo "			<td align=\"left\">\n";
	
	if (!empty($_GET['call']))
	{
		if ($_GET['call']!="bidadd" && $_GET['call']!="mpaadd")
		{
			echo "<div class=\"noPrint\">\n";
			echo "<input class=\"buttondkgrypnl60\" type=\"submit\" onClick=\"JavaScript: window.print()\" value=\"Print\">\n";
			echo "</div>\n";
		}
	}
	
	echo "			</td>\n";
	echo "			<td align=\"right\"><div class=\"noPrint\"><form onSubmit=\"JavaScript: window.close()\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Close\"></form></div></td>\n";
	echo "		</tr>\n";
	echo "		<tr><td colspan=\"2\" align=\"left\">\n";

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

	echo "                  </table>\n";
	echo "            </td></tr>\n";
	echo "         </table>\n";

//print_r($_SESSION);
//echo "<BR>";
//$browser = get_browser(null, true);
//print_r($browser);

	echo "   </body>\n";
	echo "</html>\n";

?>