<?php

function off_feesched()
{
	error_reporting(E_ALL);
	if ($_SESSION['tlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}
	elseif ($_SESSION['tlev'] > 6)
	{
		$saddr="192.168.1.30";

		$qry0 = "SELECT id FROM zip_link;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		if (!empty($_POST['req']))
		{
			$qry = "SELECT * FROM offices ORDER BY grouping,name ASC";
		}
		else
		{
			$qry = "SELECT * FROM offices WHERE active=1 ORDER BY grouping,name ASC";
		}
		//$qry = "SELECT * FROM offices ORDER BY name ASC";
		$res = mssql_query($qry);

		echo "<table align=\"center\" border=0>\n";
		echo "<tr>\n";
		echo "   <td class=\"gray\">\n";
		echo "<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
		echo "<tr>\n";
		echo "   <td class=\"gray\" colspan=\"3\"><b>Office Fee Schedule</b></td>\n";
		echo "   <td class=\"gray\" align=\"right\" colspan=\"5\"></td>\n";
		/*
		echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "   	<input type=\"hidden\" name=\"call\" value=\"off\">\n";
		echo "   	<input type=\"hidden\" name=\"subq\" value=\"add\">\n";
		echo "   <td class=\"gray\" align=\"right\"><input class=\"buttondkgry\" type=\"submit\" value=\"New Office\"></td>\n";
		echo "   </form>\n";
		echo "</tr>\n";
		*/
		echo "<tr>\n";
		echo"   <td class=\"ltgray_und\" align=\"center\"><b>OID</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Name</b></td>\n";
		echo"   <td class=\"ltgray_und\">&nbsp<b>Consult Fee</b></td>\n";
		echo"   <td class=\"ltgray_und\">&nbsp<b>Accting Fee</b></td>\n";
		echo"   <td class=\"ltgray_und\">&nbsp<b>Computer Fee</b></td>\n";
		echo"</tr>\n";

		while ($row = mssql_fetch_array($res))
		{
			echo "<tr>\n";
			echo"   <td class=\"wh_und\" align=\"right\">".$row['officeid']."&nbsp&nbsp</td>\n";

			if ($row['active']==1)
			{
				echo"   <td class=\"wh_und\"><b>".$row['name']."</b></td>\n";
			}
			else
			{
				echo"   <td class=\"wh_und\">".$row['name']."</td>\n";
			}

			echo"   <td class=\"wh_und\" align=\"right\">".number_format($row['consfee'])."</td>\n";
			echo"   <td class=\"wh_und\" align=\"right\">".number_format($row['acctfee'])."</td>\n";
			echo"   <td class=\"wh_und\" align=\"right\">".number_format($row['pacctfee'])."</td>\n";
			echo"</tr>\n";
		}

		echo "</table>\n";
		echo "</td>\n";
		echo"</tr>\n";
		echo "</table>\n";
	}
}

function off_total()
{
	$etot=0;
	$ecnt=0;

	$eqry = "select contractamt from est where officeid='".$_SESSION['officeid']."'";
	$eres = mssql_query($eqry);

	while($erow=mssql_fetch_array($eres))
	{
		$esubtot=$erow['contractamt'];
		$etot=$etot+$esubtot;
		$ecnt++;
	}

	$etot		=number_format($etot, 2, '.', ',');

	echo "<br>------------------------------------<br>";
	echo $eqry."<BR>";
	echo "<br>Retail Estimate Total ($ecnt) is: ".$etot;

	$ctot=0;
	$catot=0;
	$ccnt=0;
	$cacnt=0;

	$cqry = "select contractamt from jdetail where officeid='".$_SESSION['officeid']."' and jobid!='0' and njobid='0' and jadd=0;";
	$cres = mssql_query($cqry);

	while($crow=mssql_fetch_array($cres))
	{
		$csubtot=$crow['contractamt'];
		$ctot=$ctot+$csubtot;
		$ccnt++;
	}

	$cqry1 = "select raddnpr_man from jdetail where officeid='".$_SESSION['officeid']."' and jobid!='0' and njobid='0' and raddnpr_man!='0' and jadd!=0;";
	$cres1 = mssql_query($cqry1);

	while($crow1=mssql_fetch_array($cres1))
	{
		$csubtot1=$crow1['raddnpr_man'];
		$catot=$catot+$csubtot1;
		$cacnt++;
	}

	$catot	=number_format($catot, 2, '.', ',');
	$ctot		=number_format($ctot, 2, '.', ',');

	echo "<br>------------------------------------<br>";
	echo $cqry."<BR>";
	echo $cqry1."<BR>";
	echo "<br>Estimated Retail Contract Total ($ccnt) is: ".$ctot;
	echo "<br>Estimated Retail Contract Add Total ($cacnt) is: ".$catot;

	$jtot=0;
	$jatot=0;
	$jcnt=0;
	$jacnt=0;

	$jqry = "select contractamt from jdetail where officeid='".$_SESSION['officeid']."' and njobid!='0' and jadd=0;";
	$jres = mssql_query($jqry);

	while($jrow=mssql_fetch_array($jres))
	{
		$jsubtot=$jrow['contractamt'];
		$jtot=$jtot+$jsubtot;
		$jcnt++;
	}

	$jqry1 = "select raddnpr_man from jdetail where officeid='".$_SESSION['officeid']."' and njobid!='0' and jadd!=0;";
	$jres1 = mssql_query($jqry1);

	while($jrow1=mssql_fetch_array($jres1))
	{
		$jsubtot1=$jrow1['raddnpr_man'];
		$jatot=$jatot+$jsubtot1;
		$jacnt++;
	}

	$jatot	=number_format($jatot, 2, '.', ',');
	$jtot		=number_format($jtot, 2, '.', ',');

	echo "<br>------------------------------------<br>";
	echo $jqry."<BR>";
	echo $jqry1."<BR>";
	echo "<br>Estimated Retail Job Total ($jcnt) is: ".$jtot;
	echo "<br>Estimated Retail Job Add Total ($jacnt) is: ".$jatot;
}

function csearch()
{
	unset($_SESSION['tqry']);
	$acclist=explode(",",$_SESSION['aid']);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
	$res1 = mssql_query($qry1);

	//echo $_SESSION['tqry']."<br>";
	echo "<table width=\"425\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\"><b>Customer Search</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                 <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Search String:</b></td>\n";
	//echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Data Field</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Sort by</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Order by</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Inactive</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b></b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"csearch_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"clname\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"bboxl\" name=\"sval\" size=\"20\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	//echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	//echo "												<select name=\"subq\">\n";
	//echo "                                 		<option value=\"clname\">Last Name</option>\n";
	//echo "                                 		<option value=\"custid\">Lead #</option>\n";
	//echo "                                 		<option value=\"estid\">Estimate #</option>\n";
	//echo "                                 		<option value=\"jobid\">Contract #</option>\n";
	//echo "                                 		<option value=\"njobid\">Job #</option>\n";
	//echo "												</select>\n";
	//echo "											</td>\n";
	echo "                              	<td>\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"clname\" SELECTED>Last Name</option>\n";
	echo "                                 		<option value=\"added\">Date Added</option>\n";
	//echo "                                 		<option value=\"updated\">Last Update</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                 		<option value=\"DESC\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";

	if ($_SESSION['llev'] >=5)
	{
		echo "					<input class=\"checkbox\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this Box to Include Inactive Customer Records\">\n";
	}

	echo "				</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";

	if ($_SESSION['llev'] >=5)
	{
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
	}

	echo "				</td>\n";
	echo "         								</form>\n";
	echo "										</tr>\n";
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

function csearch_results()
{
	//echo $_SESSION['tqry']."<br>";

	$officeid		=$_SESSION['officeid'];
	$securityid		=$_SESSION['securityid'];
	$acclist			=explode(",",$_SESSION['aid']);
	$brdr				=0;

	$qrypre = "SELECT enmas,enexp,masimport,tgp FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre = mssql_query($qrypre);
	$rowpre = mssql_fetch_array($respre);

	if (isset($_POST['order']))
	{
		$order=$_POST['order'];
	}
	else
	{
		$order="jobid";
	}

	if (isset($_POST['ascdesc']))
	{
		$dir=$_POST['ascdesc'];
	}
	else
	{
		$dir="ASC";
	}

	if (isset($_POST['showdupe']) && $_POST['showdupe']==1)
	{
		$showdupe=" AND dupe=1 ";
	}
	else
	{
		$showdupe=" AND dupe=0 ";
	}

	if (empty($_POST['sval']))
	{
		echo "<b><font color=\"red\">Error!</font><br><br>Search String required</b>";
		exit;
	}


	//if (isset($_SESSION['tqry']))
	//{
	//echo "ZERO<br>";
	//	$qry=$_SESSION['tqry'];
	//}
	//else
	//{
	if ($_POST['call']=="csearch_results")
	{
		if ($_POST['subq']=="clname")
		{
			//echo "ONE<br>";
			$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND clname LIKE '".$_POST['sval']."%' ".$showdupe." ORDER BY ".$order." ".$dir.";";
		}
		elseif ($_POST['subq']=="salesman")
		{
			//echo "TWO<br>";
			$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['assigned']."' ".$showdupe." ORDER BY ".$order." ".$dir.";";
		}
	}
	else
	{
		//echo "THREE<br>";
		$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$showdupe." ORDER BY ".$order." ".$dir.";";
	}
	//}

	//echo $_POST['subq'];

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//$_SESSION['tqry']=$qry;

	//echo $qry."<br>";
	//echo $_POST['subq']."<br>";
	//echo $_SESSION['tqry']."<br>";

	if ($nrows < 1)
	{
		echo "<table class=\"outer\" align=\"center\" width=\"90%\" border=\"".$brdr."\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\" class=\"gray\">\n";
		echo "         <h4>Customer Search did not produce any results.</h4>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		echo "<table align=\"center\" width=\"45%\" border=\"".$brdr."\">\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\">\n";
		echo "         <table width=\"100%\" border=\"".$brdr."\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\" border=\"".$brdr."\">\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"left\" class=\"ltgray_und\">Customer Search Results for <b>".$_SESSION['offname']."</b></td>\n";
		echo "                     </tr>\n";
		echo "                   </table>\n";
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\" bgcolor=\"white\" border=\"".$brdr."\">\n";
		echo "                  <tr>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b></b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>Customer</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"left\"><b>Salesperson</b></td>\n";
		echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Add Date</b></td>\n";
		//echo "                     <td class=\"ltgray_und\" align=\"center\"><b>Updated</b></td>\n";
		//echo "                     <td class=\"ltgray_und\" align=\"center\"><b></b></td>\n";
		echo "                     <td colspan=\"2\" class=\"ltgray_und\" align=\"right\"><b>Record(s): <font color=\"red\">".$nrows."</font></b></td>\n";
		echo "                  </tr>\n";

		$xi 	= 0;
		$ls	= "<b>L</b>";
		$es	= "";
		$cs	= "";
		$js	= "";
		while($row=mssql_fetch_array($res))
		{
			$xi++;
			$tbg	= "wh_und";
			$mstat=	0;

			$uid  =md5(session_id().time().$row['custid']).".".$_SESSION['securityid'];

			$qryA = "SELECT jobid FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."' AND jadd!='0';";
			$resA = mssql_query($qryA);
			$nrowA = mssql_num_rows($resA);

			$qryB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$row['njobid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			$qryC = "SELECT fname,lname,slevel,mas_office,mas_div,masid FROM security WHERE securityid='".$row['securityid']."';";
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

			if (in_array($row['securityid'],$acclist)||$_SESSION['jlev'] >= 6)
			{
				//if ($row['custid']!=0)
				//{
				//	$ls="<b>L</b>";
				//}

				if ($row['estid']!=0)
				{
					$es="<b>E</b>";
				}

				if ($row['jobid']!=0)
				{
					$cs="<b>C</b>";
				}

				if ($row['njobid']!=0)
				{
					$js="<b>J</b>";
				}

				if (isset($row['added']))
				{
					$sdate = date("m-d-Y", strtotime($row['added']));
				}
				else
				{
					$sdate = "";
				}

				if (isset($row['updated']))
				{
					$udate = date("m-d-Y", strtotime($row['updated']));
				}
				else
				{
					$udate = "";
				}

				echo "                  <tr>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">&nbsp".$xi."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\" NOWRAP>&nbsp<b>".str_replace('\\','',$row['clname'])."</b>, ".$row['cfname']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\" NOWRAP>&nbsp<font class=\"".$fstyle."\">".$rowC['lname'].", ".$rowC['fname']."</font></td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">&nbsp".$sdate."</td>\n";
				//echo "                     <td class=\"".$tbg."\" align=\"center\">&nbsp".$udate."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\">&nbsp".$ls." ".$es." ".$cs." ".$js."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
				echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
				echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
				echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View\">\n";
				echo "                        </form>\n";
				echo "                     </td>\n";
				echo "                  </tr>\n";
				$es	= "";
				$cs	= "";
				$js	= "";
			}
		}

		echo "                  </table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function distinct_matlist()
{
	$matid_ar	= array();
	$companyid	= array(
	0=>'',
	1=>70,
	2=>210,
	3=>220,
	4=>230,
	5=>250,
	6=>260,
	7=>270,
	8=>280,
	9=>290,
	10=>300,
	11=>302,
	12=>310,
	13=>320,
	14=>350,
	15=>370,
	16=>380,
	17=>400,
	18=>420,
	19=>440,
	20=>450,
	21=>460,
	22=>470,
	23=>500,
	24=>510,
	25=>520,
	26=>540
	);

	foreach ($companyid as $n1 => $v1)
	{
		$qry0	= "select distinct(matid) as matid from [".$v1."inventory] where matid!=0;";
		$res0 = mssql_query($qry0);

		//echo $qry0."<br>";

		while($row0	= mssql_fetch_array($res0))
		{
			$qrya	= "select id,vid from [material_master] where id='".$row0['matid']."';";
			$resa = mssql_query($qrya);
			$rowa	= mssql_fetch_array($resa);

			if (!in_array($row0['matid'],$matid_ar) && $rowa['vid']!=12)
			{
				$matid_ar[]=$row0['matid'];
			}
		}
	}

	//print_r($matid_ar);

	sort($matid_ar);

	//print_r($matid_ar);

	if (is_array($matid_ar) && count($matid_ar) > 0)
	{
		echo "<table>\n";
		/*
		echo "	<tr>\n";
		echo "		<td><b>ID</b></td>\n";
		echo "		<td><b>Part Number</b></td>\n";
		echo "		<td><b>Item</b></td>\n";
		echo "		<td><b>Price</b></td>\n";
		echo "		<td><b>VID</b></td>\n";
		echo "		<td><b>Vendor</b></td>\n";
		echo "	</tr>\n";
		*/

		foreach ($matid_ar as $n2 => $v2)
		{
			$qry1	= "select id,vid,vpnum,item,bp from [material_master] where id='".$v2."';";
			$res1 = mssql_query($qry1);
			$row1	= mssql_fetch_array($res1);

			$qry2	= "select vid,name from [vendors] where vid='".$row1['vid']."';";
			$res2 = mssql_query($qry2);
			$row2	= mssql_fetch_array($res2);

			echo "	<tr>\n";
			echo "		<td>".$row1['id']."</td>\n";
			echo "		<td>".$row1['vpnum']."</td>\n";
			echo "		<td>".$row1['item']."</td>\n";
			echo "		<td>".$row1['bp']."</td>\n";
			echo "		<td>".$row2['vid']."</td>\n";
			echo "		<td>".$row2['name']."</td>\n";
			echo "	</tr>\n";
		}

		echo "<table>\n";
	}
}

function listevents()
{
	if ($_SESSION['tlev'] < 9)
	{
		echo "You do not have appropriate Access Rights to view this Resource";
		exit;
	}

	if (!isset($_POST['order'])||$_POST['order']=="")
	{
		$order="evdate";
	}
	else
	{
		$order=$_POST['order'];
	}

	if (!isset($_POST['sort'])||$_POST['sort']=="")
	{
		$sort="ASC";
	}
	else
	{
		$sort=$_POST['sort'];
	}

	if (!isset($_POST['oid'])||$_POST['oid']==""||$_POST['oid']==0)
	{
		$qry = "SELECT * FROM events ORDER BY ".$order." ".$sort.";";
	}
	else
	{
		$qry = "SELECT * FROM events WHERE oid='".$_POST['oid']."' ORDER BY ".$order." ".$sort.";";
	}

	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($nrow > 0)
	{
		$qry1 = "SELECT * FROM offices ORDER BY name ASC;";
		$res1 = mssql_query($qry1);

		//echo $qry;
		echo "<table class=\"outer\" width=\"60%\" align=\"center\">\n";
		echo "   <tr>\n";
		echo "      <td class=\"ltgray_und\" colspan=\"6\">\n";
		echo "			<table align=\"right\">\n";
		echo "   			<tr>\n";
		echo "         	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "					<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "					<input type=\"hidden\" name=\"call\" value=\"events\">\n";
		echo "      			<td align=\"right\">\n";
		echo "						<select name=\"oid\">\n";
		echo "							<option value=\"0\">All</option>\n";

		while ($row1=mssql_fetch_array($res1))
		{
			if (isset($_POST['oid']) && $_POST['oid']==$row1['officeid'])
			{
				echo "							<option value=\"".$row1['officeid']."\" SELECTED>".$row1['name']."</option>\n";
			}
			else
			{
				echo "							<option value=\"".$row1['officeid']."\">".$row1['name']."</option>\n";
			}
		}

		echo "						</select>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "						<select name=\"order\">\n";
		echo "							<option value=\"evdate\" SELECTED>Date</option>\n";
		echo "							<option value=\"evdescrip\">Description</option>\n";
		echo "							<option value=\"status\">Status</option>\n";
		echo "							<option value=\"sid\">User</option>\n";
		echo "							<option value=\"oid\">Office</option>\n";
		echo "							<option value=\"ip\">IP Address</option>\n";
		echo "						</select>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "						<select name=\"sort\">\n";
		echo "							<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "							<option value=\"asc\">Ascending</option>\n";
		echo "						</select>\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View\">\n";
		echo "					</td>\n";
		echo "         	</form>\n";
		echo "   			</tr>\n";
		echo "   		</table>\n";
		echo "      </td>\n";
		echo "   <tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>Date</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>Event</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>Status</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>User</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>Office</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\"><b>IP Addr</b></td>\n";
		echo "	<tr>\n";

		while ($row=mssql_fetch_array($res))
		{
			echo "	<tr>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[1]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[2]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[3]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[4]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[5]."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$row[6]."</td>\n";
			echo "	<tr>\n";
		}

		echo "</table>\n";
	}
	else
	{
		echo "No Records.";
	}
}

function loggedusers()
{
	if (isset($_POST['order']))
	{
		$order=$_POST['order'];
	}
	else
	{
		$order="acttime";
	}

	if (isset($_POST['ascdesc']))
	{
		$ascdesc=$_POST['ascdesc'];
	}
	else
	{
		$ascdesc="DESC";
	}

	$orderar		=array("acttime"=>"Last Activity","logtime"=>"Login Time","rem_addr"=>"Remote Address");
	$ascdescar	=array("ASC"=>"Ascending","DESC"=>"Descending");
	$sactar		=array();
	$ssact		="";

	$qryX = "SELECT id,officeid,securityid,logtime,acttime,sessionid,rem_addr,queries,sact FROM logstate ORDER BY ".$order." ".$ascdesc.";";
	$resX = mssql_query($qryX);
	$nrowX= mssql_num_rows($resX);

	//echo $qry;
	if ($nrowX > 0)
	{
		echo "<table class=\"outer\" align=\"center\" width=\"75%\">\n";
		echo "<tr>\n";
		echo "   <td class=\"ltgray_und\" colspan=\"9\">\n";
		echo "		<table align=\"right\">\n";
		echo "			<tr>\n";
		echo "   			<td>\n";
		echo "					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"loggedin\">\n";
		echo "   			<td>\n";
		echo "						<select name=\"order\">\n";

		foreach ($orderar AS $on=>$ov)
		{
			if ($on==$order)
			{
				echo "							<option value=\"".$on."\" SELECTED>".$ov."</option>\n";
			}
			else
			{
				echo "							<option value=\"".$on."\">".$ov."</option>\n";
			}
		}

		echo "						</select>\n";
		echo "					</td>\n";
		echo "					<td>\n";
		echo "						<select name=\"ascdesc\">\n";

		foreach ($ascdescar AS $adn=>$adv)
		{
			if ($adn==$ascdesc)
			{
				echo "							<option value=\"".$adn."\" SELECTED>".$adv."</option>\n";
			}
			else
			{
				echo "							<option value=\"".$adn."\">".$adv."</option>\n";
			}
		}

		echo "						</select>\n";
		echo "					</td>\n";
		echo "   				<td>\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Refresh\">\n";
		echo "					</td>\n";
		echo "         			</form>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td class=\"ltgray_und\" colspan=\"8\"><b>Users Logged On</b></td>\n";
		echo "   <td class=\"ltgray_und\" align=\"right\"><b>".$nrowX."</b> Logged On</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td class=\"ltgray_und\"><b>Name</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Security Levels</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Login ID</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Last Login</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Last Activity</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Remote Addr</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Office</b></td>\n";
		echo "   <td class=\"ltgray_und\"><b>Area</b></td>\n";
		echo "   <td class=\"ltgray_und\"></td>\n";
		echo "</tr>\n";

		while ($rowX = mssql_fetch_array($resX))
		{
			$qry0 = "SELECT securityid,officeid,fname,lname,slevel,login FROM security WHERE securityid='".$rowX['securityid']."'";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$qry1 = "SELECT name FROM offices WHERE officeid='".$rowX['officeid']."'";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);

			//print_r(array_keys($rowX));
			//print_r($rowX);

			if ($rowX['sact']=="est")
			{
				$ssact="Estimates";
			}
			elseif ($rowX['sact']=="contract")
			{
				$ssact="Contracts";
			}
			elseif ($rowX['sact']=="job")
			{
				$ssact="Jobs";
			}
			elseif ($rowX['sact']=="reports")
			{
				$ssact="Reports";
			}
			elseif ($rowX['sact']=="leads")
			{
				$ssact="Leads";
			}
			elseif ($rowX['sact']=="message")
			{
				$ssact="Messages";
			}
			elseif ($rowX['sact']=="main")
			{
				$ssact="Main Menu";
			}

			echo "<tr>\n";
			echo "   <td class=\"wh_und\"><b>".$row0['lname']."</b>, ".$row0['fname']."</td>\n";
			echo "   <td class=\"wh_und\">".$row0['slevel']."</td>\n";
			echo "   <td class=\"wh_und\">".$row0['login']."</td>\n";
			echo "   <td class=\"wh_und\">".$rowX['logtime']."</td>\n";
			echo "   <td class=\"wh_und\">".$rowX['acttime']."</td>\n";
			echo "   <td class=\"wh_und\">".$rowX['rem_addr']."</td>\n";
			echo "   <td class=\"wh_und\">".$row1['name']."</td>\n";
			echo "   <td class=\"wh_und\">".$ssact."</td>\n";
			echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "   	<input type=\"hidden\" name=\"call\" value=\"users\">\n";
			echo "   	<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
			echo "   	<input type=\"hidden\" name=\"userid\" value=\"".$row0['securityid']."\">\n";
			echo "   	<input type=\"hidden\" name=\"officeid\" value=\"".$row0['officeid']."\">\n";
			echo "   <td class=\"wh_und\" align=\"right\">\n";
			echo "   	<input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
			echo "   </td>\n";
			echo "   </form>\n";
			echo "</tr>\n";
			//$sactar[]=$rowX['sact'];
		}
		echo "</table>\n";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"75%\">\n";
		echo "	<tr>\n";
		echo "   	<td class=\"gray\" align=\"right\"><b>Users Logged On:</b></td>\n";
		echo "   	<td class=\"gray\" align=\"left\"><b>".$nrowX."</b> Logged On</td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
	}

}

function internet_total()
{
	if (!isset($_POST['d1']) || !isset($_POST['d2']))
	{
		echo "<table width=\"100%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"3\" align=\"right\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\" align=\"left\"><b>Total Internet Leads</b></td>\n";
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"tinternet\">\n";
		echo "      			<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";
		echo "						<input class=\"bboxl\" type=\"text\" id=\"data\" name=\"d1\" size=\"10\" maxlength=\"10\">\n";
		echo "						<input class=\"bboxl\" type=\"text\" id=\"data\" name=\"d2\" size=\"10\" maxlength=\"10\">\n";
		echo "						<input type=\"hidden\" name=\"full\" value=\"1\">\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Select\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	else
	{
		$qry = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND source='0' AND added BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		//$nrow= mssql_num_rows($res);

		//echo $qry;
		echo "<table width=\"100%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"3\" align=\"right\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\" align=\"left\"><b>Total Internet Leads from bluehaven.com: </b>&nbsp<input class=\"bboxl\" type=\"text\" name=\"inum\" size=\"10\" maxlength=\"10\" value=\"".$row['cnt']."\"></td>\n";
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"tinternet\">\n";
		echo "      			<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";
		echo "						<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"10\" maxlength=\"10\" value=\"".$_POST['d1']."\"> to \n";
		echo "						<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\" value=\"".$_POST['d2']."\">\n";
		echo "						<input type=\"hidden\" name=\"full\" value=\"1\">\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"right\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Select\">\n";
		echo "					</td>\n";
		echo "         		</form>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function salesman_generalold()
{
	$aid=explode(",",$_SESSION['aid']);

	if (isset($_POST['d1']))
	{
		$d1=$_POST['d1'];
	}
	else
	{
		$d1="";
	}

	if (isset($_POST['d2']))
	{
		$d2=$_POST['d2'];
	}
	else
	{
		$d2="";
	}

	if (isset($_POST['addupd']) && $_POST['addupd']=="added")
	{
		$addupd="added";
	}
	else
	{
		$addupd="updated";
	}

	$qry = "SELECT * FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	echo "<table width=\"75%\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"right\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "         		<form name=\"tsearch\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "					 <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"salesman_gen\">\n";
	echo "					<td class=\"gray\" align=\"left\"><b>Salesman Report</b></td>\n";
	echo "      					<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"20\" value=\"".$d1."\" title=\"Begin Date\"><a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "                                 	<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"20\" value=\"".$d2."\" title=\"End Date\"><a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to End Date\"></a>\n";

	if (isset($_POST['todate']) && $_POST['todate']==1)
	{
		$addupd="All";
	}
	else
	{
		echo "						<select name=\"addupd\">\n";

		if ($addupd=="added")
		{
			echo "							<option value=\"added\" SELECTED>Added</option>\n";
			echo "							<option value=\"updated\">Updated</option>\n";
		}
		else
		{
			echo "							<option value=\"added\">Added</option>\n";
			echo "							<option value=\"updated\" SELECTED>Updated</option>\n";
		}

		echo "						</select>\n";
	}

	echo "						<input type=\"hidden\" name=\"full\" value=\"1\">\n";
	echo "      			</td>\n";
	echo "					<td class=\"gray\" align=\"right\">\n";

	/*if (isset($_POST['todate']) && $_POST['todate']==1)
	{
	echo "To Date:";
	echo "						<input class=\"checkboxgry\" type=\"checkbox\" name=\"todate\" value=\"1\" CHECKED>\n";
	}
	else
	{
	echo "						<input class=\"checkboxgry\" type=\"checkbox\" name=\"todate\" value=\"1\">\n";
	}*/

	echo "						<select name=\"sid\">\n";

	while ($row = mssql_fetch_array($res))
	{
		if (in_array($row['securityid'],$aid))
		{
			$secl=explode(",",$row['slevel']);

			if ($secl[6]==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}

			if (!empty($_POST['sid']) && $_POST['sid']==$row['securityid'])
			{
				echo "						<option value=\"".$row['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row['lname'].", ".$row['fname']." ".$dis."</option>\n";
			}
			else
			{
				echo "						<option value=\"".$row['securityid']."\" class=\"".$ostyle."\">".$row['lname'].", ".$row['fname']." ".$dis."</option>\n";
			}
		}
	}

	echo "						</select>\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Select\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "				</tr>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";

	if (!empty($_POST['sid'])&&!empty($_POST['d1'])&&!empty($_POST['d2']) || !empty($_POST['todate']))
	{
		// Leads Info
		$qry1 = "SELECT * FROM leadstatuscodes WHERE active='2' ORDER BY name ASC";
		$res1 = mssql_query($qry1);

		$qry2 = "SELECT * FROM leadstatuscodes WHERE active='1' ORDER BY name ASC";
		$res2 = mssql_query($qry2);

		$br=0;
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"33%\">\n";
		echo "			<table class=\"outer\" width=\"100%\" border=$br>\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>".ucfirst($addupd)." Leads</b></td>\n";

		if (!empty($_POST['todate']) && $_POST['todate']==1)
		{
			$tdate=date("m/d/Y");
			echo "					<td class=\"ltgray_und\" align=\"right\">As of ".$tdate."</td>\n";
		}
		else
		{
			echo "					<td class=\"ltgray_und\" align=\"right\">".$_POST['d1']." to ".$_POST['d2']."</td>\n";
		}

		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" class=\"gray\" align=\"left\" valign=\"top\">\n";
		echo "						<table width=\"100%\" border=$br>\n";
		echo "						<tr>\n";
		echo "							<td colspan=\"3\" class=\"gray\" align=\"center\"><b>Source</b></td>\n";
		echo "						</tr>\n";

		if (!empty($_POST['todate']) && $_POST['todate']==1)
		{
			$qry1z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0';";
		}
		else
		{
			$qry1z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0' AND ".$addupd." BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
		}
		$res1z = mssql_query($qry1z);
		$row1z = mssql_fetch_array($res1z);

		echo $qry1z;

		$srccnt=0;
		$srctotal=$row1z['cnt'];
		while ($row1 = mssql_fetch_array($res1))
		{
			if (!empty($_POST['todate']) && $_POST['todate']==1)
			{
				$qry1a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0' AND source='".$row1['statusid']."';";
			}
			else
			{
				$qry1a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0' AND source='".$row1['statusid']."' AND ".$addupd." BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
			}
			$res1a = mssql_query($qry1a);
			$row1a = mssql_fetch_array($res1a);

			//echo $qry1a."<br>";

			$cnt=$row1a['cnt'];
			$srccnt=$srccnt+$cnt;

			if ($row1a['cnt']!=0)
			{
				$perc=number_format(($cnt/$srctotal)*100);
			}
			else
			{
				$perc=0;
			}

			echo "							<tr>\n";

			if ($row1['statusid'] == "0")
			{
				echo "								<td class=\"wh_und\" align=\"left\">Internet</td>\n";
			}
			elseif ($row1['statusid'] == 1)
			{
				echo "								<td class=\"wh_und\" align=\"left\">Manual (Source Unset)</td>\n";
			}
			else
			{
				echo "								<td class=\"wh_und\" align=\"left\">".$row1['name']."</td>\n";
			}

			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\">".$cnt."</td>\n";
			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\">".$perc."%</td>\n";
			echo "							</tr>\n";
		}


		if ($srccnt!=0)
		{
			$srcperc=number_format(($srccnt/$srctotal)*100);
		}
		else
		{
			$srcperc=0;
		}

		echo "							<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\">Total</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$srccnt."</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$srcperc."%</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" class=\"gray\" align=\"left\">\n";
		echo "						<table width=\"100%\" border=$br>\n";
		echo "						<tr>\n";
		echo "							<td colspan=\"3\" class=\"gray\" align=\"center\"><b>Result</b></td>\n";
		echo "						</tr>\n";

		if (!empty($_POST['todate']) && $_POST['todate']==1)
		{
			$qry2z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0';";
		}
		else
		{
			$qry2z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0' AND ".$addupd." BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
		}
		$res2z = mssql_query($qry2z);
		$row2z = mssql_fetch_array($res2z);

		//echo $qry2z;

		$stgcnt=0;
		$stgtotal=$row2z['cnt'];
		while ($row2 = mssql_fetch_array($res2))
		{
			if (!empty($_POST['todate']) && $_POST['todate']==1)
			{
				$qry2a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0' AND stage='".$row2['statusid']."';";
			}
			else
			{
				$qry2a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0' AND stage='".$row2['statusid']."' AND ".$addupd." BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
			}
			$res2a = mssql_query($qry2a);
			$row2a = mssql_fetch_array($res2a);

			//echo $qry2a;

			$cnt=$row2a['cnt'];
			$stgcnt=$stgcnt+$cnt;

			if ($row2a['cnt']!=0)
			{
				$perc=number_format(($cnt/$stgtotal)*100);
			}
			else
			{
				$perc=0;
			}

			echo "							<tr>\n";
			echo "								<td class=\"wh_und\" align=\"left\">".$row2['name']."</td>\n";
			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\">".$cnt."</td>\n";
			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\">".$perc."%</td>\n";
			echo "							</tr>\n";
		}

		if ($srccnt!=0)
		{
			$stgperc=number_format(($stgcnt/$srctotal)*100);
		}
		else
		{
			$stgperc=0;
		}

		echo "							<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\">Total</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$stgcnt."</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$stgperc."%</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"33%\">\n";
		/*
		// Estimates
		$qry3z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND estid!='0' AND jobid='0';";
		$res3z = mssql_query($qry3z);
		$row3z = mssql_fetch_array($res3z);

		$qry3a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND estid='0' AND jobid='0' AND dupe!='1' AND stage='6';";
		$res3a = mssql_query($qry3a);
		$row3a = mssql_fetch_array($res3a);

		$slcnt= $row3a['cnt'];
		$ecnt	= $row3z['cnt'];

		if ($slcnt!=0)
		{
		$slperc=number_format(($slcnt/$ecnt)*100);
		}
		else
		{
		$slperc=0;
		}

		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Active Estimates</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\">".$ecnt."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\">Sold Lead w/o Estimate</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\">".$slcnt."</td>\n";
		//echo "					<td class=\"ltgray_und\" align=\"left\">".$slperc."%</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		*/
		echo "		</td>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"33%\">\n";
		/*
		// Jobs
		$qry4z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND estid!='0' AND jobid!='0';";
		$res4z = mssql_query($qry4z);
		$row4z = mssql_fetch_array($res4z);

		$jcnt	= $row4z['cnt'];

		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Stored Jobs</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\">$jcnt</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		*/
		echo "		</td>\n";
		echo "	</tr>\n";
	}

	echo "</table>\n";
}

function salesman_general()
{
	error_reporting(0);
	$aid=explode(",",$_SESSION['aid']);

	if (isset($_POST['d1']))
	{
		$d1=$_POST['d1'];
	}
	else
	{
		$d1="";
	}

	if (isset($_POST['d2']))
	{
		$d2=$_POST['d2'];
	}
	else
	{
		$d2="";
	}

	if (isset($_POST['addupd']) && $_POST['addupd']=="added")
	{
		$addupd="added";
	}
	else
	{
		$addupd="updated";
	}

	//$qry = "SELECT * FROM security WHERE officeid='".$_SESSION['officeid']."' and SUBSTRING(slevel,13,13)='1' ORDER BY lname ASC";
	$qry = "SELECT * FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	echo "<table width=\"80%\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"right\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "         		<form name=\"tsearch\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "					 <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"salesman_gen\">\n";
	echo "					<td class=\"gray\" align=\"left\"><b>Sales Rep Report for ".$_SESSION['offname']."</b></td>\n";
	echo "      					<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"20\" value=\"".$d1."\" title=\"Begin Date\"><a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "                                 	<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"20\" value=\"".$d2."\" title=\"End Date\"><a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to End Date\"></a>\n";
	echo "					<input type=\"hidden\" name=\"addupd\" value=\"updated\">\n";
	/*if (isset($_POST['todate']) && $_POST['todate']==1)
	{
	$addupd="All";
	}
	else
	{
	echo "						<select name=\"addupd\">\n";

	if ($addupd=="added")
	{
	echo "							<option value=\"added\" SELECTED>Added</option>\n";
	echo "							<option value=\"updated\">Updated</option>\n";
	}
	else
	{
	echo "							<option value=\"added\">Added</option>\n";
	echo "							<option value=\"updated\" SELECTED>Updated</option>\n";
	}

	echo "						</select>\n";
	}*/

	echo "						<input type=\"hidden\" name=\"full\" value=\"1\">\n";
	echo "      			</td>\n";
	echo "					<td class=\"gray\" align=\"right\">\n";

	/*if (isset($_POST['todate']) && $_POST['todate']==1)
	{
	echo "To Date:";
	echo "						<input class=\"checkboxgry\" type=\"checkbox\" name=\"todate\" value=\"1\" CHECKED>\n";
	}
	else
	{
	echo "						<input class=\"checkboxgry\" type=\"checkbox\" name=\"todate\" value=\"1\">\n";
	}*/

	echo "						<select name=\"sid\">\n";
	echo "							<option value=\"***\" class=\"fontblack\">All Salesreps</option>\n";
	echo "							<option value=\"***\" class=\"fontblack\">----------------</option>\n";

	while ($row = mssql_fetch_array($res))
	{
		if (in_array($row['securityid'],$aid))
		{
			$secl=explode(",",$row['slevel']);

			if ($secl[6]==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}

			if (!empty($_POST['sid']) && $_POST['sid']==$row['securityid'])
			{
				echo "						<option value=\"".$row['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row['lname'].", ".$row['fname']." ".$dis."</option>\n";
			}
			else
			{
				echo "						<option value=\"".$row['securityid']."\" class=\"".$ostyle."\">".$row['lname'].", ".$row['fname']." ".$dis."</option>\n";
			}
		}
	}

	echo "						</select>\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Select\">\n";
	echo "					</td>\n";
	echo "         		</form>\n";
	echo "				</tr>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";

	if (!empty($_POST['sid'])&&!empty($_POST['d1'])&&!empty($_POST['d2']) || !empty($_POST['todate']))
	{
		// Leads Info
		$qry1 = "SELECT * FROM leadstatuscodes WHERE active='2' ORDER BY name ASC";
		$res1 = mssql_query($qry1);

		$qry2 = "SELECT * FROM leadstatuscodes WHERE active='1' ORDER BY name ASC";
		$res2 = mssql_query($qry2);

		$br=0;
		echo "	<tr>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"35%\">\n";
		echo "			<table class=\"outer\" width=\"100%\" border=$br>\n";
		echo "				<tr>\n";
		//echo "					<td class=\"ltgray_und\" align=\"left\"><b>".ucfirst($addupd)." Leads</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Lead Activity</b></td>\n";

		/*
		if (!empty($_POST['todate']) && $_POST['todate']==1)
		{
		$tdate=date("m/d/Y");
		echo "					<td class=\"ltgray_und\" align=\"right\">As of ".$tdate."</td>\n";
		}
		else
		{
		echo "					<td class=\"ltgray_und\" align=\"right\">".$_POST['d1']." to ".$_POST['d2']."</td>\n";
		}
		*/

		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" class=\"gray\" align=\"left\" valign=\"top\">\n";
		echo "						<table width=\"100%\" border=$br>\n";
		echo "						<tr>\n";
		echo "							<td colspan=\"3\" class=\"gray\" align=\"left\"><b>Source</b></td>\n";
		echo "						</tr>\n";

		//if (!empty($_POST['todate']) && $_POST['todate']==1)
		//{
		//	$qry1z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0';";
		//}
		//else
		//{
		$qry1z  = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ";

		if ($_POST['sid']!="***")
		{
			$qry1z .= "AND securityid='".$_POST['sid']."' ";
		}

		$qry1z .= "AND dupe='0' AND ".$addupd." BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
		//}
		$res1z = mssql_query($qry1z);
		$row1z = mssql_fetch_array($res1z);

		//echo $qry1z;

		$srccnt=0;
		$srctotal=$row1z['cnt'];
		while ($row1 = mssql_fetch_array($res1))
		{
			//if (!empty($_POST['todate']) && $_POST['todate']==1)
			//{
			//	$qry1a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0' AND source='".$row1['statusid']."';";
			//}
			//else
			//{
			$qry1a  = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ";

			if ($_POST['sid']!="***")
			{
				$qry1a .= "AND securityid='".$_POST['sid']."' ";
			}

			$qry1a .= "AND dupe='0' AND source='".$row1['statusid']."' AND ".$addupd." BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
			//}
			$res1a = mssql_query($qry1a);
			$row1a = mssql_fetch_array($res1a);

			//echo $qry1a."<br>";

			$cnt=$row1a['cnt'];
			$srccnt=$srccnt+$cnt;

			if ($row1a['cnt']!=0)
			{
				$perc=number_format(($cnt/$srctotal)*100);
			}
			else
			{
				$perc=0;
			}

			echo "							<tr>\n";

			if ($row1['statusid'] == "0")
			{
				echo "								<td class=\"wh_und\" align=\"left\">Internet</td>\n";
			}
			elseif ($row1['statusid'] == 1)
			{
				echo "								<td class=\"wh_und\" align=\"left\">Manual (Source Unset)</td>\n";
			}
			else
			{
				echo "								<td class=\"wh_und\" align=\"left\">".$row1['name']."</td>\n";
			}

			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\">".$cnt."</td>\n";
			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\" title=\"Percentage of Total Leads\">".$perc."%</td>\n";
			echo "							</tr>\n";
		}


		if ($srccnt!=0)
		{
			$srcperc=number_format(($srccnt/$srctotal)*100);
		}
		else
		{
			$srcperc=0;
		}

		echo "							<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\">Total</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$srccnt."</td>\n";
		//echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$srcperc."%</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\"></td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" class=\"gray\" align=\"left\">\n";
		echo "						<table width=\"100%\" border=$br>\n";
		echo "						<tr>\n";
		echo "							<td colspan=\"3\" class=\"gray\" align=\"left\"><b>Result</b></td>\n";
		echo "						</tr>\n";

		//if (!empty($_POST['todate']) && $_POST['todate']==1)
		//{
		//	$qry2z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0';";
		//}
		//else
		//{
		$qry2z = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ";

		if ($_POST['sid']!="***")
		{
			$qry2z .= "AND securityid='".$_POST['sid']."' ";
		}

		$qry2z .= "AND dupe='0' AND ".$addupd." BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
		//}
		$res2z = mssql_query($qry2z);
		$row2z = mssql_fetch_array($res2z);

		//echo $qry2z;

		$stgcnt=0;
		$stgtotal=$row2z['cnt'];
		while ($row2 = mssql_fetch_array($res2))
		{
			//if (!empty($_POST['todate']) && $_POST['todate']==1)
			//{
			//	$qry2a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_POST['sid']."' AND dupe='0' AND stage='".$row2['statusid']."';";
			//}
			//else
			//{
			$qry2a = "SELECT COUNT(*) as cnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ";

			if ($_POST['sid']!="***")
			{
				$qry2a .= "AND securityid='".$_POST['sid']."' ";
			}

			$qry2a .= "AND dupe='0' AND stage='".$row2['statusid']."' AND ".$addupd." BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
			//}
			$res2a = mssql_query($qry2a);
			$row2a = mssql_fetch_array($res2a);

			//echo $qry2a;

			$cnt=$row2a['cnt'];
			$stgcnt=$stgcnt+$cnt;

			if ($row2a['cnt']!=0)
			{
				$perc=number_format(($cnt/$stgtotal)*100);
			}
			else
			{
				$perc=0;
			}

			echo "							<tr>\n";
			echo "								<td class=\"wh_und\" align=\"left\">".$row2['name']."</td>\n";
			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\">".$cnt."</td>\n";
			echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\" title=\"Percentage of Total Leads\">".$perc."%</td>\n";
			//echo "								<td class=\"wh_und\" align=\"right\" width=\"25%\"></td>\n";
			echo "							</tr>\n";
		}

		if ($srccnt!=0)
		{
			$stgperc=number_format(($stgcnt/$srctotal)*100);
		}
		else
		{
			$stgperc=0;
		}

		echo "							<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\">Total</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$stgcnt."</td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" width=\"25%\">".$stgperc."%</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "		<td align=\"right\" valign=\"top\" width=\"65%\">\n";
		// Contracts

		$qry3z  = "select  ";
		$qry3z .= "		c.officeid as oid, ";
		$qry3z .= "		c.cid, ";
		$qry3z .= "		c.clname, ";
		$qry3z .= "		c.cfname, ";
		$qry3z .= "		c.added, ";
		$qry3z .= "		c.jobid, ";
		$qry3z .= "		j.contractdate, ";
		$qry3z .= "		j.contractamt, ";
		$qry3z .= "		DATEDIFF(day,c.added,j.contractdate) as cdays, ";
		$qry3z .= "		(SELECT digdate from jobs where officeid='".$_SESSION['officeid']."' and jobid=j.jobid) as ddate, ";
		$qry3z .= "		DATEDIFF(day,j.contractdate,(SELECT digdate from jobs where officeid='".$_SESSION['officeid']."' and jobid=j.jobid)) as ddays, ";
		$qry3z .= "		(SELECT lname from security where officeid='".$_SESSION['officeid']."' and securityid=c.securityid) as salesrep, ";
		$qry3z .= "		(SELECT comm from jobs where officeid='".$_SESSION['officeid']."' and jobid=j.jobid) as jcomm ";
		$qry3z .= "	from  ";
		$qry3z .= "		cinfo as c ";
		$qry3z .= "	inner join  ";
		$qry3z .= "		jdetail as j ";
		$qry3z .= "	on  ";
		$qry3z .= "		c.jobid=j.jobid ";
		$qry3z .= "	where  ";
		$qry3z .= "		c.officeid='".$_SESSION['officeid']."' and  ";
		$qry3z .= "		c.jobid!='0' and ";
		$qry3z .= "		j.jadd='0' and ";

		if (isset($_POST['sid']) && $_POST['sid']!="***")
		{
			$qry3z .= "		c.securityid='".$_POST['sid']."' and ";
		}

		$qry3z .= "		j.contractdate between ";
		$qry3z .= "		'".$_POST['d1']."' and ";
		$qry3z .= "		'".$_POST['d2']."' ";
		$qry3z .= "	order by ";
		$qry3z .= "		j.contractdate ";
		$qry3z .= "	asc;";

		//echo $qry3z."<br>";
		$res3z = mssql_query($qry3z);
		$nrow3z = mssql_num_rows($res3z);

		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\" colspan=\"7\"><b>Contract Activity</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\" colspan=\"2\"><b>".$nrow3z."</b> Record(s)</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Customer</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Added</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Contract Date</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Lead to Contr</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Dig Date</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Contr to Dig</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Sales Rep</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Contr Amt</b></td>\n";
		echo "					<td class=\"gray\" align=\"center\"><b>Comm</b></td>\n";
		echo "				</tr>\n";

		$tjcomm	=0;
		$tavgltc	=0;
		$tavgctd	=0;
		$ltc		=0;
		$ctd		=0;
		$trprc	=0;
		$tovc	=0;
		while ($row3z = mssql_fetch_array($res3z))
		{
			$qry3z1  = "select  ";
			$qry3z1 .= "		SUM(CONVERT(money,raddnpr)) as traddnpr";
			$qry3z1 .= "	from  ";
			$qry3z1 .= "		jdetail ";
			$qry3z1 .= "	where  ";
			$qry3z1 .= "		officeid='".$_SESSION['officeid']."' and  ";
			$qry3z1 .= "		jobid='".$row3z['jobid']."' and ";
			$qry3z1 .= "		jadd!='0';";
			$res3z1  = mssql_query($qry3z1);
			$row3z1 = mssql_fetch_array($res3z1);
			
			$trprc=$trprc+$row3z1['traddnpr'];
			
			$qry3z2  = "select  ";
			$qry3z2 .= "		SUM(CONVERT(money,ovcommission)) as ovcomm";
			$qry3z2 .= "	from  ";
			$qry3z2 .= "		jobs ";
			$qry3z2 .= "	where  ";
			$qry3z2 .= "		officeid='".$_SESSION['officeid']."' and  ";
			$qry3z2 .= "		jobid='".$row3z['jobid']."';";
			$res3z2  = mssql_query($qry3z2);
			$row3z2 = mssql_fetch_array($res3z2);
			
			$tovc=$tovc+$row3z2['ovcomm'];
			//echo $qry3z1."<br>";
			//if (checkdate(date("m",strtotime($row3z['ddate'])), date("d",strtotime($row3z['ddate'])), date("Y",strtotime($row3z['ddate']))) && date("m/d/Y",strtotime($row3z['ddate']))!="12/31/1969")
			if (strlen($row3z['ddate']) > 5)
			{
				$ddays=date("m/d/Y",strtotime($row3z['ddate']));
			}
			else
			{
				$ddays="";
			}

			$tjcomm	=$tjcomm+$row3z['jcomm'];

			if ($row3z['cdays'] <= 0)
			{
				//$preddays=0;
				$preddays=$row3z['cdays']*(2*-1);
				//$preddays=$row3z['cdays']*-1;
			}
			else
			{
				$preddays=$row3z['cdays'];
			}

			$tavgltc	=$tavgltc+$preddays;

			if ($row3z['ddays'] > 0)
			{
				$tavgctd	=$tavgctd+$row3z['ddays'];
				$ctd++;
			}

			echo "				<tr>\n";
			echo "					<td class=\"wh_und\" align=\"left\" width=\"100\">&nbsp".$row3z['clname']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".date("m/d/Y",strtotime($row3z['added']))."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".date("m/d/Y",strtotime($row3z['contractdate']))."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$row3z['cdays']."</td>\n";
			//echo "					<td class=\"wh_und\" align=\"center\">".$ddays." (".$row3z['ddate'].")</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$ddays."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$row3z['ddays']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\">".$row3z['salesrep']."</td>\n";
			//echo "					<td class=\"wh_und\" align=\"right\">".number_format($row3z['contractamt'], 2, '.', '')." (".$row3z1['traddnpr'].")</td>\n";
			//echo "					<td class=\"wh_und\" align=\"right\">".number_format($row3z['jcomm']+$row3z2['ovcomm'], 2, '.', '')."(".$row3z2['ovcomm'].")</td>\n";
			echo "					<td class=\"wh_und\" align=\"right\">".number_format($row3z['contractamt']+$row3z1['traddnpr'], 2, '.', '')."</td>\n";
			echo "					<td class=\"wh_und\" align=\"right\">".number_format($row3z['jcomm']+$row3z2['ovcomm'], 2, '.', '')."</td>\n";
			echo "				</tr>\n";
		}

		$qry3a  = "select  ";
		$qry3a .= "		SUM(CONVERT(money,j.contractamt)) as sumctr, ";
		$qry3a .= "		AVG(DATEDIFF(day,c.added,j.contractdate)) as avg_lcdays";
		$qry3a .= "	from  ";
		$qry3a .= "		cinfo as c ";
		$qry3a .= "	inner join  ";
		$qry3a .= "		jdetail as j ";
		$qry3a .= "	on  ";
		$qry3a .= "		c.jobid=j.jobid ";
		$qry3a .= "	where  ";
		$qry3a .= "		c.officeid='".$_SESSION['officeid']."' and  ";
		$qry3a .= "		c.jobid!='0' and ";
		$qry3a .= "		j.jadd='0' and ";

		if (isset($_POST['sid']) && $_POST['sid']!="***")
		{
			$qry3a .= "		c.securityid='".$_POST['sid']."' and ";
		}

		$qry3a .= "		j.contractdate between ";
		$qry3a .= "		'".$_POST['d1']."' and ";
		$qry3a .= "		'".$_POST['d2']."' ";
		$res3a = mssql_query($qry3a);
		$row3a = mssql_fetch_array($res3a);

		$ttavgltc=round($tavgltc/$nrow3z);
		$ttavgctd=round($tavgctd/$ctd);
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\" colspan=\"3\">&nbsp<b>Totals</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\" title=\"Total days Lead to Contr (Negative Entries are inverted and doubled)\">".$tavgltc."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\" title=\"Total days Contr to Dig (Calc only includes Contracts with a Dig ($ctd))\">".$tavgctd."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\">".number_format($row3a['sumctr']+$trprc, 2, '.', '')."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\">".number_format($tjcomm+$tovc, 2, '.', '')."</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\" colspan=\"3\">&nbsp<b>Averages</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\" title=\"Avg # days Lead to Contr (Negative Entries are inverted and doubled)\">".$ttavgltc."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\" title=\"Avg # days Contr to Dig (Calc only includes Contracts with a Dig ($ctd))\">".$ttavgctd."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\">".number_format(($row3a['sumctr']+$trprc)/$nrow3z, 2, '.', '')."</td>\n";
		echo "					<td class=\"ltgray_und\" align=\"right\">".number_format(($tjcomm+$tovc)/$nrow3z, 2, '.', '')."</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}

	echo "</table>\n";
}

function logins_general()
{
	if ($_SESSION['tlev'] < 9 && $_SESSION['rlev'] < 9)
	{
		echo "<font color=\"red\">Error</font> You do not have the appropriate Access Level to view this Resource.";
		exit;
	}

	if (empty($_POST['conf'])||$_POST['conf']!=1)
	{
		echo "<table width=\"25%\">\n";
		echo "		<td colspan=\"2\" align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">\n";
		echo "						<b>Date Range:</b>";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"logins\">\n";
		echo "						<input type=\"hidden\" name=\"conf\" value=\"1\">\n";
		echo "				<tr>\n";
		echo "      			<td bgcolor=\"#d3d3d3\" align=\"right\"><b></b></td>\n";
		echo "      			<td bgcolor=\"#d3d3d3\" align=\"right\">\n";
		echo "						<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"10\" maxlength=\"10\"> to \n";
		echo "						<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "         		</form>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	elseif ($_POST['conf']==1)
	{
		$qry = "SELECT * FROM security WHERE laccess BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."' ORDER BY lname ASC";
		$res = mssql_query($qry);
		$nrow= mssql_num_rows($res);

		//echo $qry."<br>";

		echo "<table class=\"outer\" align=\"center\" width=\"80%\">\n";
		echo "<tr>\n";
		echo"   <td class=\"ltgray_und\" colspan=\"4\"><b>User Activity between ".$_POST['d1']." and ".$_POST['d2']."</b></td>\n";
		echo"   <td class=\"ltgray_und\" align=\"right\" colspan=\"2\"><b>".$nrow."</b> Records Found</td>\n";
		echo"</tr>\n";
		echo "<tr>\n";
		echo"   <td class=\"ltgray_und\"><b>Name</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Login ID</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Last Login</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Last Activity</b></td>\n";
		echo"   <td class=\"ltgray_und\"><b>Office</b></td>\n";
		echo"   <td class=\"ltgray_und\"></td>\n";
		echo"</tr>\n";

		while ($row = mssql_fetch_array($res))
		{
			$qry1 = "SELECT name FROM offices WHERE officeid='".$row['officeid']."'";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);

			$qry2 = "SELECT MAX(evdate) as mdate FROM events WHERE sid='".$row['securityid']."'";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);

			echo "<tr>\n";
			echo "   <td class=\"wh_und\" NOWRAP><b>".$row['lname']."</b>, ".$row['fname']."</td>\n";
			echo "   <td class=\"wh_und\" NOWRAP>".$row['login']."</td>\n";
			echo "   <td class=\"wh_und\" NOWRAP>".$row['curr_login']."</td>\n";
			//echo "   <td class=\"wh_und\">".$row2['mdate']."</td>\n";
			echo "   <td class=\"wh_und\" NOWRAP>".$row['laccess']."</td>\n";
			echo "   <td class=\"wh_und\" NOWRAP><b>".$row1['name']."</b></td>\n";
			echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "   	<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "   	<input type=\"hidden\" name=\"call\" value=\"users\">\n";
			echo "   	<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
			echo "   	<input type=\"hidden\" name=\"userid\" value=\"".$row['securityid']."\">\n";
			echo "   	<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
			echo "   <td class=\"wh_und\" align=\"right\" NOWRAP>\n";
			echo "   	<input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
			echo "   </td>\n";
			echo "   </form>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}

function clist_general()
{
	if (isset($_POST['order']))
	{
		if (isset($_POST['dir']))
		{
			$order=$_POST['order'];
			$dir=$_POST['dir'];
		}
		else
		{
			$order=$_POST['order'];
			$dir="ASC";
		}
	}
	else
	{
		$order="clname";
		$dir="ASC";
	}

	if (isset($_POST['dir']) && $_POST['dir']=="ASC")
	{
		$sdir="DESC";
	}
	elseif (isset($_POST['dir']) && $_POST['dir']=="DESC")
	{
		$sdir="ASC";
	}
	else
	{
		$sdir="ASC";
	}

	$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND dupe!=1 ORDER BY ".$order." ".$dir.";";
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	if ($nrows < 1)
	{
		echo "<table align=\"center\" width=\"60%\">\n";
		echo "   <tr>\n";
		echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      <td class=\"gray\">\n";
		echo "         <b>Your search did not return any results.</b>\n";
		echo "      </td>\n";
		echo "   </form>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"70%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                     <tr>\n";
		//echo "                        <td align=\"left\" class=\"ltgray_und\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "                     </tr>\n";
		echo "                   </table>\n";
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\">\n";
		echo "         <table width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                  	<tr>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"clname\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Last Name\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">First Name</td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Phone</td>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"added\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Origin Date\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"custid\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Lead ID\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"estid\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Estimate ID\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"clist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"jobid\">\n";
		echo "								<input type=\"hidden\" name=\"dir\" value=\"".$sdir."\">\n";
		echo "							<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Job ID\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "							<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">\n";
		echo "							</td>\n";
		echo "                  	</tr>\n";

		while($row=mssql_fetch_array($res))
		{
			$tbg="wh_und";

			if (!empty($row['added']))
			{
				$ts_odate=strtotime($row['added']);
				$odate = date("m-d-Y", strtotime($row['added']));
			}
			else
			{
				$ts_odate=0;
				$odate = "";
			}

			$udiff_date=$ts_tdate[0]-$ts_udate;
			$odiff_date=$ts_tdate[0]-$ts_odate;

			if ($row['cconph']=="hm")
			{
				$cphone=$row['chome'];
			}
			elseif ($row['cconph']=="wk")
			{
				$cphone=$row['cwork'];
			}
			elseif ($row['cconph']=="ce")
			{
				$cphone=$row['ccell'];
			}
			else
			{
				$cphone="";
			}

			echo "                  <tr>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$row['clname']."</b></td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\">".$row['cfname']."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$cphone."</b></td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\">".$odate."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['custid']."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['estid']."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['jobid']."</td>\n";
			//echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
			//echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			//echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
			//echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
			//echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Contact\">\n";
			echo "                     </td>\n";
			//echo "                        </form>\n";
			echo "                  </tr>\n";
		}
	}

	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function events_general()
{
	if ($_SESSION['rlev'] < 9 && $_SESSION['tlev'] < 9)
	{
		echo "You do not have appropriate Access to view this Resource.";
		exit;
	}

	$qry1 = "SELECT COUNT(*) FROM events;";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	$qry2 = "SELECT COUNT(*) FROM events WHERE status=1;";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	$qry3 = "SELECT COUNT(*) FROM events WHERE status=2;";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);

	$qry4 = "SELECT COUNT(*) FROM events WHERE status=3;";
	$res4 = mssql_query($qry4);
	$row4 = mssql_fetch_array($res4);

	if ($row2[0] != 0)
	{
		echo $row2[0]. " Logons!<br>";
	}

	if ($row4[0] != 0)
	{
		echo $row4[0]. " Functions!<br>";
	}

	if ($row3[0] != 0)
	{
		echo $row3[0]. " Logoffs!<br>";
	}

	if ($row1[0] != 0)
	{
		echo $row1[0]. " Total Processes!<br>";
	}
}

function leads_general()
{
	global $retar;

	$qryALTo = "SELECT securityid,altoffices FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resALTo = mssql_query($qryALTo);
	$rowALTo = mssql_fetch_array($resALTo);

	if ($rowALTo['altoffices']!=0)
	{
		$alto=explode(",",$rowALTo['altoffices']);
	}

	if ($_SESSION['rlev'] >= 5)
	{
		if (empty($_POST['subq']))
		{
			//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1';";
			$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1';";
		}
		elseif ($_POST['subq']=="drange")
		{
			if (empty($_POST['d2']))
			{
				//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added='".$_POST['d1']."';";
				$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND finan_date='".$_POST['d1']."';";
			}
			else
			{
				//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
				$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND finan_date BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
			}
		}
	}
	else
	{
		echo "<b>You do not have appropriate Access to View this Resource.</b>";
		exit;
	}
	
	//echo $qry1."<br>";

	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);

	if ($_POST['call']=="rleads")
	{
		$qrypre1 = "SELECT rid,rcode,descrip FROM tfinanresultcodes ORDER BY rcode;"; //
		$respre1 = mssql_query($qrypre1);

		while ($rowpre1 = mssql_fetch_array($respre1))
		{
			$srccodes[$rowpre1['rid']]=$rowpre1['rcode'];
		}
		
		//echo $qrypre1."<BR>";
	}
	else
	{
		$srccodes=array(0=>'',1=>'Winners',2=>'Cust Finan',3=>'Cash');
	}

	$rdate = date("m-d-Y", time());
	
	//print_r($srccodes);
	
	if ($_POST['call']=="rleads")
	{
		echo "<table align=\"center\" width=\"100%\">\n";
	}
	else
	{
		echo "<table align=\"center\" width=\"50%\">\n";
	}
	
	echo "   <tr>\n";
	echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
	echo "         <table class=\"outer\" width=\"100%\">\n";
	echo "   			<tr>\n";

	if ($_POST['call']=="rleads")
	{
		echo "      			<td class=\"gray\" align=\"left\" valign=\"bottom\" NOWRAP>&nbsp<b>Finance Result Report</b></td>\n";
	}
	else
	{
		echo "      			<td class=\"gray\" align=\"left\" valign=\"bottom\" NOWRAP>&nbsp<b>Finance Source Report</b></td>\n";
	}

	echo "      			<td class=\"gray\" align=\"right\" valign=\"bottom\">\n";
	echo "         			<table width=\"100%\">\n";
	echo "         			<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";

	if ($_POST['call']=="rleads")
	{
		echo "						<input type=\"hidden\" name=\"call\" value=\"rleads\">\n";
	}
	else
	{
		echo "						<input type=\"hidden\" name=\"call\" value=\"sleads\">\n";
	}

	echo "						<input type=\"hidden\" name=\"subq\" value=\"drange\">\n";
	echo "   						<tr>\n";
	echo "      						<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";

	if (!empty($_POST['d1']))
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\" value=\"".$_POST['d1']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
	}

	echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";

	if (!empty($_POST['d2']))
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\" value=\"".$_POST['d2']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
	}

	echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";

	echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
	echo "      						</td>\n";
	echo "      						<td class=\"gray\" align=\"right\">Percent Display:</font>\n";

	if (isset($_POST['percs']) && $_POST['percs']==1)
	{
		echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"percs\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"percs\" value=\"1\">\n";
	}

	echo "      						</td>\n";
	echo "      						<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
	echo "      						</td>\n";
	echo "   						</tr>\n";
	echo "         				</form>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch1'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch1'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "						</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";

	//if (isset($_POST['full'])&&$_POST['full']==1)
	if (!empty($_POST['subq']) && $_POST['subq']=="drange")
	{
		if ($row1['cnt'] > 0)
		{
			echo "   			<tr>\n";
			echo "      			<td colspan=\"2\" class=\"gray\" align=\"left\" valign=\"top\">\n";
			echo "         			<table width=\"100%\">\n";

			if ($_SESSION['rlev'] >=5)
			{
				if ($_SESSION['rlev'] >=9)
				{
					$qry4 = "SELECT DISTINCT(finan_from),officeid,name FROM offices WHERE active=1 and finan_off=1 ORDER BY name;";
					$res4 = mssql_query($qry4);
				}
				else
				{
					$qry4 = "SELECT DISTINCT(finan_from),officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."' AND active=1 ORDER BY name;";
					$res4 = mssql_query($qry4);
				}

				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">&nbsp<b>Office</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">Total</td>\n";

				foreach($srccodes as $n1 => $v1)
				{
					if ($n1!=0)
					{
						echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">".$v1."</td>\n";
					}
				}

				echo "   			</tr>\n";

				$ocon	=0;
				$oicon=0;
				$omcon=0;
				$o_ar	=array();

				while ($row4 = mssql_fetch_array($res4))
				{
					$qry4a = "SELECT officeid,name FROM offices WHERE active=1 and finan_from=".$row4['officeid']." ORDER BY name;";
					$res4a = mssql_query($qry4a);
					
					//echo $qry4a."<br>";
					echo "   			<tr>\n";
					echo "      			<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>".$row4['name']."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp</td>\n";

					foreach($srccodes as $n1 => $v1)
					{
						if ($n1!=0)
						{
							echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">&nbsp</td>\n";
						}
					}

					echo "   			</tr>\n";
					
					while ($row4a = mssql_fetch_array($res4a))
					{
						if ($_SESSION['rlev'] >=8) // Anyone with Report Level 8+
						{
							$tt="1";
							leads_gen_sub($srccodes,$row4a['officeid'],$row4a['name']);
							$ocon=$ocon+$retar[0];
							$oicon=$oicon+$retar[1];
							$omcon=$omcon+$retar[2];
							
							foreach($srccodes as $nX => $vX)
							{
								if (is_array($retar[3]))
								{
									$o_ar[$nX]=$o_ar[$nX]+$retar[3][$nX];
								}
								else
								{
									$o_ar[$nX]=$o_ar[$nX]+0;
								}
							}	
						}
						elseif ($_SESSION['rlev'] >=7 && in_array($row4['officeid'],$alto)) // Anyone with Report Level 7+
						{
							$tt="2";
							leads_gen_sub($srccodes,$row4a['officeid'],$row4a['name']);
							$ocon=$ocon+$retar[0];
							$oicon=$oicon+$retar[1];
							$omcon=$omcon+$retar[2];
							
							foreach($srccodes as $nX => $vX)
							{
								if (is_array($retar[3]))
								{
									$o_ar[$nX]=$o_ar[$nX]+$retar[3][$nX];
								}
								else
								{
									$o_ar[$nX]=$o_ar[$nX]+0;
								}
							}	
						}
						elseif ($_SESSION['rlev'] >=6 && $row4['officeid']==$_SESSION['officeid']) // Anyone with Report Level 5+
						{
							$tt="3";
							leads_gen_sub($srccodes,$row4a['officeid'],$row4a['name']);
							$ocon=$ocon+$retar[0];
							$oicon=$oicon+$retar[1];
							$omcon=$omcon+$retar[2];
							
							foreach($srccodes as $nX => $vX)
							{
								if (is_array($retar[3]))
								{
									$o_ar[$nX]=$o_ar[$nX]+$retar[3][$nX];
								}
								else
								{
									$o_ar[$nX]=$o_ar[$nX]+0;
								}
							}	
						}
					}
					
					/*
					echo "   			<tr>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp<b>Test Total($tt)(".$row4['name'].")</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$ocon."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$oicon."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$omcon."</b></td>\n";
					echo "   			</tr>\n";
					*/
				}

				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp<b>Total</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$ocon."</b></td>\n";
				
				foreach($srccodes as $n1 => $v1)
				{
					if ($n1!=0)
					{
						echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>".$o_ar[$n1]."</b></td>\n";
					}
				}

				echo "   			</tr>\n";
			}

			echo "						</table>\n";
			echo "					</td>\n";
			echo "   			</tr>\n";
		}
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function leads_gen_sub($srccodes,$officeid,$oname)
{
	global $retar;
	$s_ar=array();

	if (empty($_POST['subq']))
	{
		//$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1';";
		$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1';";
	}
	elseif ($_POST['subq']=="drange")
	{
		//$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND added BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
		$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND finan_date BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";

	}
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	echo "   			<tr>\n";
	echo "      			<td class=\"wh_und\" align=\"left\" valign=\"bottom\" NOWRAP>&nbsp&nbsp".$oname."</td>\n";
	echo "      			<td class=\"wh_und\" align=\"right\" valign=\"bottom\"><b>".$row5['cnt']."</b></td>\n";

	if ($_POST['call']=="rleads")
	{
		$ffield="finan_status";
	}
	else
	{
		$ffield="finan_src";
	}

	foreach($srccodes as $n1 => $v1)
	{
		if ($n1!=0)
		{
			// Tabulates statusids from leadstatuscodes
			if (empty($_POST['subq']))
			{
				//$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND ".$ffield."='".$v1."';";
				$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND ".$ffield."='".$n1."';";
			}
			elseif ($_POST['subq']=="drange")
			{
				//$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND ".$ffield."='".$v1."' AND added BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
				$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND ".$ffield."='".$n1."' AND finan_date BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
	
			}
			$res5z = mssql_query($qry5z);
			$row5z = mssql_fetch_array($res5z);
			
			//echo $qry5z."<br>";
	
			if ($row5z['cnt'] < 1)
			{
				$s_cnt="";
			}
			else
			{
				if (isset($_POST['percs']) && $_POST['percs']==1)
				{
					$ps_cnt	=$row5z['cnt']/$row5['cnt'];
					$s_cnt	=number_format($ps_cnt, 4, '.', '');
				}
				else
				{
					$s_cnt=$row5z['cnt'];
					
					if (!is_numeric($s_cnt) || $s_cnt < 1)
					{
						$s_ar[$n1]=0;
					}
					else
					{
						$s_ar[$n1]=$s_cnt;
					}
				}
			}
	
			echo "      			<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$s_cnt."</b></td>\n";
		}
	}

	echo "   			</tr>\n";

	$retar=array(0=>$row5['cnt'],1=>$row5b['cnt'],2=>$row5a['cnt'],$s_ar);
	//return $retar;
	//}
}

function leads_generalold()
{
	global $retar;

	$qryALTo = "SELECT securityid,altoffices FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resALTo = mssql_query($qryALTo);
	$rowALTo = mssql_fetch_array($resALTo);

	if ($rowALTo['altoffices']!=0)
	{
		$alto=explode(",",$rowALTo['altoffices']);
	}

	if ($_SESSION['rlev'] >= 5)
	{
		if (empty($_POST['subq']))
		{
			//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1';";
			$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1';";
		}
		elseif ($_POST['subq']=="drange")
		{
			if (empty($_POST['d2']))
			{
				//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added='".$_POST['d1']."';";
				$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND finan_date='".$_POST['d1']."';";
			}
			else
			{
				//$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE estid='0' AND jobid='0' AND dupe!='1' AND added BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
				$qry1 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE dupe!='1' AND finan_date BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
			}
		}
	}
	else
	{
		echo "<b>You do not have appropriate Access to View this Resource.</b>";
		exit;
	}
	
	//echo $qry1."<br>";

	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);

	if ($_POST['call']=="rleads")
	{
		$qrypre1 = "SELECT rid,rcode,descrip FROM tfinanresultcodes ORDER BY rcode;"; //
		$respre1 = mssql_query($qrypre1);

		while ($rowpre1 = mssql_fetch_array($respre1))
		{
			$srccodes[$rowpre1['rid']]=$rowpre1['rcode'];
		}
		
		//echo $qrypre1."<BR>";
	}
	else
	{
		$srccodes=array(0=>'',1=>'Winners',2=>'Cust Finan',3=>'Cash');
	}

	$rdate = date("m-d-Y", time());
	
	//print_r($srccodes);
	
	if ($_POST['call']=="rleads")
	{
		echo "<table align=\"center\" width=\"100%\">\n";
	}
	else
	{
		echo "<table align=\"center\" width=\"50%\">\n";
	}
	
	echo "   <tr>\n";
	echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
	echo "         <table class=\"outer\" width=\"100%\">\n";
	echo "   			<tr>\n";

	if ($_POST['call']=="rleads")
	{
		echo "      			<td class=\"gray\" align=\"left\" valign=\"bottom\" NOWRAP>&nbsp<b>Finance Result Report</b></td>\n";
	}
	else
	{
		echo "      			<td class=\"gray\" align=\"left\" valign=\"bottom\" NOWRAP>&nbsp<b>Finance Source Report</b></td>\n";
	}

	echo "      			<td class=\"gray\" align=\"right\" valign=\"bottom\">\n";
	echo "         			<table width=\"100%\">\n";
	echo "         			<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";

	if ($_POST['call']=="rleads")
	{
		echo "						<input type=\"hidden\" name=\"call\" value=\"rleads\">\n";
	}
	else
	{
		echo "						<input type=\"hidden\" name=\"call\" value=\"sleads\">\n";
	}

	echo "						<input type=\"hidden\" name=\"subq\" value=\"drange\">\n";
	echo "   						<tr>\n";
	echo "      						<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";

	if (!empty($_POST['d1']))
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\" value=\"".$_POST['d1']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
	}

	echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";

	if (!empty($_POST['d2']))
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\" value=\"".$_POST['d2']."\">\n";
	}
	else
	{
		echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
	}

	echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";

	echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
	echo "      						</td>\n";
	echo "      						<td class=\"gray\" align=\"right\">Percent Display:</font>\n";

	if (isset($_POST['percs']) && $_POST['percs']==1)
	{
		echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"percs\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"percs\" value=\"1\">\n";
	}

	echo "      						</td>\n";
	echo "      						<td class=\"gray\" align=\"left\">\n";
	echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
	echo "      						</td>\n";
	echo "   						</tr>\n";
	echo "         				</form>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch1'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch1'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "						</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";

	//if (isset($_POST['full'])&&$_POST['full']==1)
	if (!empty($_POST['subq']) && $_POST['subq']=="drange")
	{
		if ($row1['cnt'] > 0)
		{
			echo "   			<tr>\n";
			echo "      			<td colspan=\"2\" class=\"gray\" align=\"left\" valign=\"top\">\n";
			echo "         			<table width=\"100%\">\n";

			if ($_SESSION['rlev'] >=5)
			{
				$qry4 = "SELECT DISTINCT(finan_from),officeid,name FROM offices WHERE active=1 and finan_off=1 ORDER BY name;";
				$res4 = mssql_query($qry4);

				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">&nbsp<b>Office</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">Total</td>\n";

				foreach($srccodes as $n1 => $v1)
				{
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">".$v1."</td>\n";
				}

				echo "   			</tr>\n";

				$ocon	=0;
				$oicon=0;
				$omcon=0;

				while ($row4 = mssql_fetch_array($res4))
				{
					$qry4a = "SELECT officeid,name FROM offices WHERE active=1 and finan_from=".$row4['officeid']." ORDER BY name;";
					$res4a = mssql_query($qry4a);
					
					//echo $qry4a."<br>";
					echo "   			<tr>\n";
					echo "      			<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>".$row4['name']."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp</td>\n";

					foreach($srccodes as $n1 => $v1)
					{
						echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">&nbsp</td>\n";
					}

					echo "   			</tr>\n";
					
					while ($row4a = mssql_fetch_array($res4a))
					{
						if ($_SESSION['rlev'] >=8) // Anyone with Report Level 8+
						{
							$tt="1";
							leads_gen_sub($srccodes,$row4a['officeid'],$row4a['name']);
							$ocon=$ocon+$retar[0];
							$oicon=$oicon+$retar[1];
							$omcon=$omcon+$retar[2];
						}
						elseif ($_SESSION['rlev'] >=7 && in_array($row4['officeid'],$alto)) // Anyone with Report Level 7+
						{
							$tt="2";
							leads_gen_sub($srccodes,$row4a['officeid'],$row4a['name']);
							$ocon=$ocon+$retar[0];
							$oicon=$oicon+$retar[1];
							$omcon=$omcon+$retar[2];
						}
						elseif ($_SESSION['rlev'] >=5 && $row4['officeid']==$_SESSION['officeid']) // Anyone with Report Level 5+
						{
							$tt="3";
							leads_gen_sub($srccodes,$row4a['officeid'],$row4a['name']);
							$ocon=$ocon+$retar[0];
							$oicon=$oicon+$retar[1];
							$omcon=$omcon+$retar[2];
						}
					}
					
					/*
					echo "   			<tr>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp<b>Test Total($tt)(".$row4['name'].")</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$ocon."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$oicon."</b></td>\n";
					echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$omcon."</b></td>\n";
					echo "   			</tr>\n";
					*/
				}

				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp<b>Total</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\"><b>".$ocon."</b></td>\n";
				
				foreach($srccodes as $n1 => $v1)
				{
					echo "      			<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">&nbsp</td>\n";
				}

				echo "   			</tr>\n";
			}

			echo "						</table>\n";
			echo "					</td>\n";
			echo "   			</tr>\n";
		}
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function leads_gen_subold($srccodes,$officeid,$oname)
{
	global $retar;

	if (empty($_POST['subq']))
	{
		//$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1';";
		$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1';";
	}
	elseif ($_POST['subq']=="drange")
	{
		//$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND added BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
		$qry5 = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND finan_date BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";

	}
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	echo "   			<tr>\n";
	echo "      			<td class=\"wh_und\" align=\"left\" valign=\"bottom\" NOWRAP>&nbsp&nbsp".$oname."</td>\n";
	echo "      			<td class=\"wh_und\" align=\"right\" valign=\"bottom\"><b>".$row5['cnt']."</b></td>\n";

	if ($_POST['call']=="rleads")
	{
		$ffield="finan_status";
	}
	else
	{
		$ffield="finan_src";
	}

	foreach($srccodes as $n1 => $v1)
	{
		// Tabulates statusids from leadstatuscodes
		if (empty($_POST['subq']))
		{
			//$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND ".$ffield."='".$v1."';";
			$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND ".$ffield."='".$n1."';";
		}
		elseif ($_POST['subq']=="drange")
		{
			//$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND estid='0' AND jobid='0' AND dupe!='1' AND ".$ffield."='".$v1."' AND added BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";
			$qry5z = "SELECT COUNT(*) AS cnt FROM cinfo WHERE officeid='".$officeid."' AND dupe!='1' AND ".$ffield."='".$n1."' AND finan_date BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']."';";

		}
		$res5z = mssql_query($qry5z);
		$row5z = mssql_fetch_array($res5z);
		
		//echo $qry5z."<br>";

		if ($row5z['cnt'] < 1)
		{
			$s_cnt="";
		}
		else
		{
			if (isset($_POST['percs']) && $_POST['percs']==1)
			{
				$ps_cnt	=$row5z['cnt']/$row5['cnt'];
				$s_cnt	=number_format($ps_cnt, 4, '.', '');
			}
			else
			{
				$s_cnt=$row5z['cnt'];
			}
		}

		echo "      			<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$s_cnt."</b></td>\n";
	}

	echo "   			</tr>\n";

	$retar=array(0=>$row5['cnt'],1=>$row5b['cnt'],2=>$row5a['cnt']);
	//return $retar;
	//}
}

1;
?>
