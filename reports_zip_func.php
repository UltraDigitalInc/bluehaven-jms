<?php

function zip_query()
{
	$qry = "SELECT officeid,name FROM offices WHERE active=1 order by name ASC;";
	$res = mssql_query($qry);

	echo "<table width=\"100%\">\n";
	echo "       <form name=\"f1\" method=\"post\">\n";
	echo "	<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "	<input type=\"hidden\" name=\"call\" value=\"zipreports\">\n";
	echo "	<input type=\"hidden\" name=\"subq\" value=\"result\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\" class=\"gray_und\" colspan=\"5\"><b>Zip Code Report</b></td>\n";
	echo "		<td align=\"center\" class=\"gray_und\"><img src=\"images/help.png\" title=\"This report shows the number of Leads or Jobs associated with a Zip Code. Leave Date Range blank to show all\"></td>\n";
	echo "	</tr>";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">&nbsp;</td>\n";
	echo "		<td class=\"gray\">&nbsp;</td>\n";
	echo "		<td class=\"gray\" align=\"left\"><b>Date Range:</b> (Optional)</td>\n";
	echo "		<td class=\"gray\" align=\"left\"></td>\n";
	echo "		<td class=\"gray\">&nbsp;</td>\n";
	echo "	</tr>";
	echo "	<tr>\n";
	echo "		<td class=\"gray_und\">&nbsp;</td>\n";
	echo "		<td class=\"gray_und\" align=\"center\">&nbsp;</td>\n";
	echo "		<td class=\"gray_und\" align=\"center\">\n";

	if (isset($_POST['d1']) && isset($_POST['d2']))
	{
		echo "					<input class=\"bboxl\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\" value=\"".$_POST['d1']."\">\n";
		echo "					<input class=\"bboxl\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\" value=\"".$_POST['d2']."\">\n";
	}
	else
	{
		echo "					<input class=\"bboxl\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
		echo "					<input class=\"bboxl\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
	}

	echo "		</td>\n";
	echo "		<td class=\"gray_und\" align=\"center\">\n";

	if (!empty($_POST['jobsonly']) && $_POST['jobsonly']==1)
	{
		echo "			<select name=\"jobsonly\" title=\"Show Lead or Job counts\">\n";
		echo "				<option value=\"0\">Leads</option>\n";
		echo "				<option value=\"1\" SELECTED>Jobs</option>\n";
		echo "			</select>\n";
		//echo "			<input class=\"checkbox\" type=\"checkbox\" name=\"jobsonly\" value=\"1\" title=\"Check this box to show only those Zip Codes that resulted in a Job\" CHECKED>\n";
	}
	else
	{
		echo "			<select name=\"jobsonly\" title=\"Show Lead or Job counts\">\n";
		echo "				<option value=\"0\" SELECTED>Leads</option>\n";
		echo "				<option value=\"1\">Jobs</option>\n";
		echo "			</select>\n";
		//echo "			<input class=\"checkbox\" type=\"checkbox\" name=\"jobsonly\" value=\"1\" title=\"Check this box to show only those Zip Codes that resulted in a Job\">\n";
	}

	if ($_SESSION['officeid']==89)
	{
		echo "			<select name=\"oid\" title=\"Set the Office\">\n";
		echo "				<option value=\"0\">All Offices</option>\n";
		while ($row = mssql_fetch_array($res))
		{
			if (isset($_POST['oid']) && $_POST['oid']==$row['officeid'])
			{
				echo "				<option value=\"".$row['officeid']."\" SELECTED>".$row['name']."</option>\n";
			}
			else
			{
				echo "				<option value=\"".$row['officeid']."\">".$row['name']."</option>\n";
			}
		}

		echo "			</select>\n";
	}

	echo "		</td>\n";
	echo "		<td class=\"gray_und\" align=\"center\">\n";
	echo "			<select name=\"order\" title=\"Sort Order\">\n";

	if (isset($_POST['order']) && $_POST['order']=="name")
	{
		//echo "				<option value=\"name\" SELECTED>Office</option>\n";
		echo "				<option value=\"count\">Count</option>\n";
	}
	elseif (isset($_POST['order']) && $_POST['order']=="count")
	{
		//echo "				<option value=\"name\">Office</option>\n";
		echo "				<option value=\"count\" SELECTED>Count</option>\n";
	}
	else
	{
		//echo "				<option value=\"name\">Office</option>\n";
		echo "				<option value=\"count\" SELECTED>Count</option>\n";
	}

	echo "			</select>\n";
	echo "		</td>\n";
	echo "		<td class=\"gray_und\" align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Submit\"></td>\n";
	echo "	</tr>";
	echo "</table>\n";
	echo "       </form>\n";
}

function zip_matrix()
{
	echo "<table class=\"outer\" >\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\">\n";

	zip_query();

	if (isset($_POST['subq']) && $_POST['subq']=="result")
	{
		zip_report();
	}
	elseif (isset($_POST['subq']) && $_POST['subq']=="listc")
	{
		listc();
	}

	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function listc()
{
	$z=0;
	$t=0;

	$qry0  = "select ";
	$qry0 .= "		cid,";
	$qry0 .= "		custid,";
	$qry0 .= "		clname,";
	$qry0 .= "		szip1 ";
	$qry0 .= "from cinfo";
	$qry0 .= "		where dupe!='1' ";
	$qry0 .= "		and szip1='".$_POST['ozip']."' ";

	if ($_SESSION['officeid']!=89)
	{
		$qry0 .= "		and officeid='".$_SESSION['officeid']."' ";
	}
	else
	{
		if (isset($_POST['oid']) && $_POST['oid']!=0)
		{
			$qry0 .= "		and officeid='".$_POST['oid']."' ";
		}
	}

	if (isset($_POST['d1']) && isset($_POST['d2']) && valid_date($_POST['d1']) && valid_date($_POST['d2']))
	{
		$qry0 .= "		and added between '".$_POST['d1']."' and '".$_POST['d2']."' ";
	}

	if (isset($_POST['jobsonly']) && $_POST['jobsonly']==1)
	{
		$qry0 .= "		and njobid!='0'  ";
	}

	$qry0 .= "		order by clname ASC; ";

	$res0  = mssql_query($qry0);
	$nrow0 = mssql_num_rows($res0);

	//echo $qry0."<br>";

	if ($nrow0 > 0)
	{
		//echo "Total CNT: ".$nrow0."<br>";
		echo "<table width=\"100%\">\n";
		echo "<tr>\n";
		echo "	<td class=\"ltgray_und\">&nbsp;</td>\n";
		echo "	<td class=\"ltgray_und\" align=\"center\"><b>Customer</b></td>\n";
		echo "	<td class=\"ltgray_und\" align=\"center\"><b>Zip Code</b></td>\n";
		echo "	<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "	<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "</tr>";

		while ($row0 = mssql_fetch_array($res0))
		{
			if (is_numeric($row0['szip1']) && strlen($row0['szip1'])==5 && $row0['szip1']!="00000" && $row0['szip1']!="99999")
			{
				$z++;
				echo "<tr>\n";
				echo "	<td class=\"wh_und\" align=\"center\">".$z.".</td>\n";
				echo "	<td class=\"wh_und\" align=\"left\">".$row0['clname']."</td>\n";
				echo "	<td class=\"wh_und\" align=\"center\">".$row0['szip1']."</td>\n";
				echo "	<td class=\"wh_und\" align=\"center\"></td>\n";
				echo "       <form name=\"f1result\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "	<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "	<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
				echo "	<input type=\"hidden\" name=\"cid\" value=\"".$row0['cid']."\">\n";
				echo "	<input type=\"hidden\" name=\"uid\" value=\"".$row0['cid']."\">\n";
				echo "	<td class=\"wh_und\" align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View\"></td>\n";
				echo "       </form>\n";
				echo "</tr>";
				//$t=$t+$row0['scnt'];
			}
		}

		echo "</table>\n";
		//echo "End CNT: ".$z."<br>";
	}

}

function zip_report()
{
	$z=0;
	$t=0;

	$qry0  = "select ";
	$qry0 .= "		DISTINCT(a.szip1) as szip, ";
	$qry0 .= "		(select count(*) from cinfo where dupe!='1' and szip1=a.szip1 and officeid=b.officeid ";

	if (isset($_POST['d1']) && isset($_POST['d2']) && valid_date($_POST['d1']) && valid_date($_POST['d2']))
	{
		//$qry0 .= "		and a.added between '".$d1."' and '".$d2."' ";
		$qry0 .= "	and added between '".$_POST['d1']."' and '".$_POST['d2']."' ";
	}

	if (isset($_POST['jobsonly']) && $_POST['jobsonly']==1)
	{
		$qry0 .= "		and njobid!='0'  ";
	}

	$qry0 .= "	) as scnt, ";
	$qry0 .= "		b.name as ona ";
	$qry0 .= "from cinfo AS a ";
	$qry0 .= "		inner join offices as b ";
	$qry0 .= "		on a.officeid=b.officeid ";
	$qry0 .= "		where a.dupe!='1' ";

	if ($_SESSION['officeid']!=89)
	{
		$qry0 .= "		and a.officeid=".$_SESSION['officeid']." ";
	}
	else
	{
		if (isset($_POST['oid']) && $_POST['oid']!=0)
		{
			$qry0 .= "		and a.officeid=".$_POST['oid']." ";
		}
	}

	if (isset($_POST['ozip']) && $_POST['ozip']!=0)
	{
		$qry0 .= "		and b.zip='".$_POST['ozip']."' ";
	}

	if (isset($_POST['d1']) && isset($_POST['d2']) && valid_date($_POST['d1']) && valid_date($_POST['d2']))
	{
		$qry0 .= "		and a.added between '".$_POST['d1']."' and '".$_POST['d2']."' ";
	}

	if (isset($_POST['jobsonly']) && $_POST['jobsonly']==1)
	{
		$qry0 .= "		and a.njobid!='0'  ";
	}

	if (isset($_POST['order']) && $_POST['order']=="name")
	{
		$qry0 .= "		order by b.name ASC; ";
	}
	elseif (isset($_POST['order']) && $_POST['order']=="count")
	{
		$qry0 .= "		order by scnt DESC; ";
	}
	else
	{
		$qry0 .= "		order by a.szip1 ASC; ";
	}

	$res0  = mssql_query($qry0);
	$nrow0 = mssql_num_rows($res0);

	/*
	if ($_SESSION['securityid']==26)
	{
		//echo '<pre>';
		//print_r($_REQUEST);
		//echo '</pre>';
		echo $qry0."<br>";
	}
	//echo $qry0."<br>";
	*/
	
	if ($nrow0 > 0)
	{

		//echo "Total CNT: ".$nrow0."<br>";

		echo "<table width=\"100%\">\n";
		echo "<tr>\n";
		echo "	<td class=\"ltgray_und\">&nbsp;</td>\n";
		echo "	<td class=\"ltgray_und\" align=\"center\"><b>Office</b></td>\n";
		echo "	<td class=\"ltgray_und\" align=\"center\"><b>Site Zip Code</b></td>\n";
		
		if (!empty($_POST['jobsonly']) && $_POST['jobsonly']==1)
		{
			echo "	<td class=\"ltgray_und\" align=\"center\"><b>Jobs</b></td>\n";
		}
		else
		{
			echo "	<td class=\"ltgray_und\" align=\"center\"><b>Lead Count</b></td>\n";
		}
		
		echo "	<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "</tr>";

		while ($row0 = mssql_fetch_array($res0))
		{
			if (is_numeric($row0['szip']) && strlen($row0['szip'])==5 && $row0['szip']!="00000" && $row0['szip']!="99999")
			{
				$z++;
				echo "<tr>\n";
				echo "	<td class=\"wh_und\" align=\"center\">".$z.".</td>\n";
				echo "	<td class=\"wh_und\" align=\"left\">".$row0['ona']."</td>\n";
				echo "	<td class=\"wh_und\" align=\"center\">".$row0['szip']."</td>\n";
				echo "	<td class=\"wh_und\" align=\"center\">".$row0['scnt']."</td>\n";
				echo "  <form name=\"f1result\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "	<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "	<input type=\"hidden\" name=\"call\" value=\"zipreports\">\n";
				echo "	<input type=\"hidden\" name=\"subq\" value=\"listc\">\n";

				if (isset($_POST['jobsonly']) && $_POST['jobsonly']==1)
				{
					echo "	<input type=\"hidden\" name=\"jobsonly\" value=\"1\">\n";
				}

				echo "	<input type=\"hidden\" name=\"ozip\" value=\"".$row0['szip']."\">\n";
				echo "	<input type=\"hidden\" name=\"d1\" value=\"".$_POST['d1']."\">\n";
				echo "	<input type=\"hidden\" name=\"d2\" value=\"".$_POST['d2']."\">\n";
				echo "	<td class=\"wh_und\" align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View List\"></td>\n";
				echo "       </form>\n";
				echo "</tr>";
				$t=$t+$row0['scnt'];
			}
		}

		echo "<tr>\n";
		echo "	<td class=\"wh_und\" align=\"right\"></td>\n";
		echo "	<td class=\"wh_und\" align=\"right\"></td>\n";
		echo "	<td class=\"wh_und\" align=\"right\"></td>\n";
		echo "	<td class=\"wh_und\" align=\"center\">".$t."</td>\n";
		echo "	<td class=\"wh_und\" align=\"right\"></td>\n";
		echo "</tr>";
		echo "</table>\n";
		//echo "End CNT: ".$z."<br>";
	}

}

?>