<?php

	session_start();

	if (isset($_SESSION['securityid']) && $_SESSION['securityid'] == 26)
	{
		include ('../connect_db.php');
		
		$unxdt		=time();
		
		$qry0 = "SELECT securityid,emailtemplateaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
	
		$qry   = "SELECT ";
		$qry  .= "		* ";
		$qry  .= "FROM ";
		$qry  .= "	list_cinfo_net ";
		$qry  .= "WHERE ";
		$qry  .= "	oid=".$_SESSION['officeid']." ";
		
		if (isset($_REQUEST['active']) && $_REQUEST['active']==2)
		{
		}
		elseif (isset($_REQUEST['active']) && $_REQUEST['active']==1)
		{
			$qry  .= "	AND active=1 ";
		}
		else
		{
			$qry  .= "	AND active=0 ";
		}
		
		if (isset($_REQUEST['d1']) && !empty($_REQUEST['d1']) && isset($_REQUEST['d2']) && !empty($_REQUEST['d2']))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' ";
		}
	
		if ($_REQUEST['call']=="search_results_net" && $_REQUEST['subq']=="sstring" && strlen($_REQUEST['ssearch']) >= 1)
		{
			$qry  .= "	AND ".$_REQUEST['field']." LIKE '".htmlspecialchars_decode($_REQUEST['ssearch'])."%' ";
		}
		
		if (isset($_REQUEST['secid']) && $_REQUEST['secid']!='NA')
		{
			if ($_SESSION['llev'] > 4 && isset($_REQUEST['field']) && $_REQUEST['field']!="securityid")
			{
				$qry  .= " AND sid=".$_REQUEST['secid']." ";
			}
			elseif ($_SESSION['llev'] == 4 && isset($_REQUEST['field']) && $_REQUEST['field']!="securityid")
			{
				if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
				{
					$qry  .= "	AND sid IN (select sid from list_secid_sidm where sidm=".$_SESSION['asstto']." OR sid=".$_SESSION['asstto']." OR sid=".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid'].") ";
				}
				else
				{
					$qry  .= "	AND sid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
				}
			}
			elseif (isset($_REQUEST['field']) && $_REQUEST['field']!="securityid")
			{
				$qry  .= " AND sid=".$_SESSION['securityid']." ";
			}
		}
		
		$qry  .= ";";
		
		if ($_SESSION['securityid']==26)
		{
			echo $qry."<br>";
			//show_post_vars();
		}
		
		if (isset($_SESSION['tqry']) && trim($_SESSION['tqry'])===trim($qry))
		{
			//echo "ZERO<br>";
			$qry	=$_SESSION['tqry'];
			echo "			<table class=\"outer\" align=\"center\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td align=\"center\" class=\"gray\"><b>NOTE:</b> These Search Results are based upon previously entered Search parameters. Click <b>New Search</b> to clear this condition.</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
		}
		
		$res   = mssql_query($qry);
		$nrows = mssql_num_rows($res);
	
		$_SESSION['tqry']=$qry;
	
		if ($nrows == 0)
		{
			echo "<table class=\"outer\" align=\"center\" width=\"100%x\">\n";
			echo "   <tr>\n";
			echo "      <td align=\"center\" class=\"gray\">\n";
			echo "         <b>No Records Found</b>\n";
			echo "      </td>\n";
			echo "   </tr>\n";
			echo "</table>\n";
		}
		else
		{
			echo "<table align=\"center\" width=\"100%\">\n";
			echo "	<tr>\n";
			echo "		<td align=\"left\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td align=\"left\" class=\"gray\"><b>".$_SESSION['offname']." Network Search Result</b></td>\n";
			echo "					<td align=\"right\" class=\"gray\">".date('m/d/Y g:i A',time())."</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td>\n";
			echo "                  <table id=\"myTable\" class=\"tablesorter\" cellpadding=\"1\">\n";
			echo "						<thead>\n";
			echo "                  	<tr>\n";
			echo "							<th width=\"20\"><img src=\"images/pixel.gif\"></th>\n";
			echo "							<th width=\"200\"><b>Company Name</b></th>\n";
			echo "                     		<th width=\"75\"><b>Phone</b></th>\n";
			echo "                     		<th width=\"150\"><b>Address</b></th>\n";
			echo "                     		<th width=\"100\"><b>City</b></th>\n";
			echo "                     		<th width=\"40\"><b>St</b></th>\n";
			echo "                     		<th width=\"40\"><b>Zip</b></th>\n";
			echo "							<th width=\"100\"><b>Contact Name</b></th>\n";
			echo "							<th width=\"150\"><b>Email</b></th>\n";
			echo "							<th width=\"90\"><b>Last Contact</b></th>\n";
			echo "            	        	<th width=\"75\" align=\"right\">".$nrows." Result(s)</th>\n";
			echo "                  	</tr>\n";
			echo "						</thead>\n";
			echo "						<tbody>\n";
	
			$etemp_ar=array();
			$nph_ar= array('0000000000','none','N/A');
			$age30=2592000; //30 Days
			$age15=1296000; //15 Days
			$age07=604800; // 7 Days
			$age01=86400; // 7 Days
			$ts_tdate=getdate();
			$lcnt=0;
			$altdtext="";
			
			while ($row=mssql_fetch_array($res))
			{
				$uid	= md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];
				$udate	= date("m/d/Y", strtotime($row['udate']));
				
				$lcnt++;
	
				echo "					<tr>\n";
				echo "						<td align=\"right\">".$lcnt.".</td>\n";
				echo "						<td align=\"left\" width=\"200\">".ucwords(htmlspecialchars_decode($row['cpname']))."</td>\n";
				echo "						<td align=\"left\" width=\"75\">".htmlspecialchars_decode($row['cwork'])."</td>\n";
				echo "						<td align=\"left\" width=\"150\">".ucwords(htmlspecialchars_decode($row['caddr1']))."</td>\n";
				echo "						<td align=\"left\" width=\"100\">".htmlspecialchars_decode($row['ccity'])."</td>\n";
				echo "						<td align=\"center\" width=\"20\">".htmlspecialchars_decode($row['cstate'])."</td>\n";
				echo "						<td align=\"center\" width=\"40\">".htmlspecialchars_decode($row['czip1'])."</td>\n";
				echo "						<td align=\"left\" width=\"100\">".ucwords(htmlspecialchars_decode($row['cfname1']))." ".ucwords(htmlspecialchars_decode($row['clname1']))."</td>\n";
				echo "						<td align=\"left\" width=\"150\">".htmlspecialchars_decode($row['cemail'])."</td>\n";
				echo "						<td align=\"center\" width=\"75\"></td>\n";
				echo "                     	<td align=\"right\">\n";
				echo "							<div class=\"noPrint\">\n";
				echo "                     		<form method=\"POST\">\n";
				echo "                     			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
				echo "                     			<input type=\"hidden\" name=\"call\" value=\"view_net\">\n";
				echo "                     			<input type=\"hidden\" name=\"cnid\" value=\"".$row['cnid']."\">\n";
				echo "                     			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
				echo "						        <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
				echo "                     		</form>\n";
				echo "							</div>\n";
				echo "                     	</td>\n";
				echo "					</tr>\n";
			}
			
			echo "						</tbody>\n";
			echo "                  </table>\n";
			echo "      </td>\n";
			echo "   </tr>\n";
			echo "</table>\n";
		}
	}
?>