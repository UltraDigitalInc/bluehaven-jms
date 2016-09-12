<?php

error_reporting(E_ALL);
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['securityid']))
{
	echo 'Unauthorized ('.__LINE__.')';
	exit;
}
else
{
	include('../connect_db.php');
	bbmatrix();
}

function bbmatrix()
{
	if (isset($_REQUEST['a']) && $_REQUEST['a'] == 'bblist')
	{
		bblist();	
	}
	elseif (isset($_REQUEST['a']) && $_REQUEST['a'] == 'bbadd')
	{
		bbadd();	
	}
	elseif (isset($_REQUEST['a']) && $_REQUEST['a'] == 'bbupdate')
	{
		bbupdate();
	}
	elseif (isset($_REQUEST['a']) && $_REQUEST['a'] == 'bbdelete')
	{
		bbdelete();
	}
	else
	{
		bblist();
	}
}

function bblist()
{
	error_reporting(E_ALL);
	
	//$qryZ  = "SELECT  * FROM jest..SalesRepBeginBalance WHERE bbsid=".$_REQUEST['bbsid']." or bbsid in (select secid from jest..secondaryids where securityid=".$_REQUEST['bbsid'].") order by hidden asc,bbdate desc;";
	$qryZ  = "SELECT  * FROM jest..SalesRepBeginBalance WHERE bbsid=".$_REQUEST['bbsid']." order by hidden asc,bbdate desc;";
	$resZ  = mssql_query($qryZ);
	$nrowZ = mssql_num_rows($resZ);

	$uid  =md5(session_id().time().$_REQUEST['bbsid']).".".$_SESSION['securityid'];
	
	echo "<html>\n";
	echo "	<head>\n";
	echo "		<link rel=\"stylesheet\" type=\"text/css\" href=\"../bh_embed.css\" />\n";
	echo "	</head>\n";
	echo "	<body>\n";
	echo "		<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	echo "					<table align=\"left\" width=\"100%\">\n";
	/*echo "						<tr>\n";
	echo "		            		<td class=\"gray_und\" align=\"left\"><b>Sales Rep Beginning Balances</b></td>\n";
	echo "						</tr>\n";*/
	echo "						<tr>\n";
	echo "							<td valign=\"top\" align=\"left\">\n";
	echo "								<form name=\"bbadd\" id=\"bbadd\">\n";
	echo "		            			<input type=\"hidden\" name=\"a\" value=\"bbadd\">\n";
	echo "		            			<input type=\"hidden\" name=\"bbsid\" value=\"".$_REQUEST['bbsid']."\">\n";
	echo "		            			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "								<table align=\"left\" width=\"100%\">\n";
	echo "									<tr>\n";
	echo "		            					<td align=\"left\"><b>Add Entry</b></td>\n";
	echo "		            					<td align=\"right\">Date</td>\n";
	echo "		            					<td align=\"left\"><input type=\"text\" name=\"effdate\" size=\"5\" title=\"Effective Date\"></td>\n";
	echo "		            					<td align=\"right\">Amt</td>\n";
	echo "		            					<td align=\"left\"><input type=\"text\" name=\"effamt\" size=\"5\" title=\"Amount\"></td>\n";
	echo "		            					<td align=\"center\"><input class=\"transnb\" type=\"image\" src=\"../images/save.gif\" alt=\"Save\"></td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
	echo "								</form>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td valign=\"top\" align=\"left\">\n";
	echo "								<table align=\"left\" width=\"100%\">\n";
	
	if ($nrowZ > 0)
	{
		echo "						<tr>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\">Date</td>\n";
		echo "							<td class=\"ltgray_und\" align=\"right\">Amount</td>\n";
		echo "							<td class=\"ltgray_und\" width=\"20\" align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
		echo "						</tr>\n";
		
		while ($rowZ = mssql_fetch_array($resZ))
		{
			if ($rowZ['hidden']==0)
			{
				$rclass='wh_und';
			}
			else
			{
				$rclass='ltred_und';
			}
			
			echo "						<tr>\n";
			echo "							<td class=\"".$rclass."\" align=\"left\">".date('m/d/Y',strtotime($rowZ['bbdate']))."</td>\n";
			echo "							<td class=\"".$rclass."\" align=\"right\">".number_format($rowZ['bbamt'], 2, '.', '')."</td>\n";
			echo "							<td class=\"".$rclass."\" width=\"20\" align=\"center\">\n";
			
			if ($rowZ['hidden']==0)
			{
				echo "								<form name=\"bbdelete\" id=\"bbdelete\">\n";
				echo "		            			<input type=\"hidden\" name=\"a\" value=\"bbdelete\">\n";
				echo "		            			<input type=\"hidden\" name=\"bbid\" value=\"".$rowZ['bbid']."\">\n";
				echo "		            			<input type=\"hidden\" name=\"bbsid\" value=\"".$_REQUEST['bbsid']."\">\n";
				echo "								<input class=\"transnb\" type=\"image\" src=\"../images/action_delete.gif\" alt=\"Delete\">\n";
				echo "								</form>\n";
			}
			else
			{
				echo "<img src=\"../images/pixel.gif\">\n";	
			}
			
			echo "							</td>\n";
			echo "						</tr>\n";
		}
		
	}

	echo "								</table>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";

	//echo "<pre>";
	//print_r($_REQUEST);
	//echo "</pre>";

	echo "	</body>\n";
	echo "</html>\n";
}

function bbadd()
{
	$qryY  = "select bbid from jest..SalesRepBeginBalance where uid='".$_REQUEST['uid']."';";
	$resY = mssql_query($qryY);
	$nrowY= mssql_num_rows($resY);
	
	//echo $nrowY.'<br>';
	
	if ($nrowY==0)
	{
		$qryZ  = "INSERT INTO jest..SalesRepBeginBalance (bbsid,bbdate,bbamt,addby,updateid,uid) VALUES (".$_REQUEST['bbsid'].",'".$_REQUEST['effdate']."',cast('".$_REQUEST['effamt']."' as money),'".$_SESSION['securityid']."','".$_SESSION['securityid']."','".$_REQUEST['uid']."');";
		$resZ = mssql_query($qryZ);
	}
	
	bblist();
}

function bbdelete()
{
	$qryY  = "update jest..SalesRepBeginBalance set hidden=1 where bbid=".$_REQUEST['bbid'].";";
	$resY = mssql_query($qryY);

	//echo 'Entry '.$qryY.'<br>';

	bblist();	
}

?>