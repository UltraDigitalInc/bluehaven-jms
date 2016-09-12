<?php

function get_Item_qb_status($oid,$qbid,$a,$qbs_db)
{
	/*
    $out='Unreleased';
    mssql_connect($qbs_db['hostname'],$qbs_db['username'],$qbs_db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($qbs_db['dbname']) or die("Table unavailable");
    
    $qry	= "SELECT * FROM quickbooks_queue WHERE ident='".$qbid."' and qb_action='".$a."';";
	$res	= mssql_query($qry);
    $row    = mssql_fetch_array($res);
	$nrow	= mssql_num_rows($res);
	
	//echo $qry;
    
    if ($nrow==1)
    {
		if ($row['qb_status']=='q')
		{
			$out='Queued';
		}
		elseif ($row['qb_status']=='e')
		{
			$out='Error';
		}
		elseif ($row['qb_status']=='i')
		{
			$out='Incomplete';
		}
		elseif ($row['qb_status']=='s')
		{
			$out='Processed';
		}
    }
	else
	{
		include ('QB/bhsoap/QB_Support.php');
		$amap	=action_map($a);
		$qry1	= "SELECT * FROM quickbooks_ident WHERE qb_object='".$amap."' and unique_id='".$qbid."';";
		$res1	= mssql_query($qry1);
		$row1	= mssql_fetch_array($res1);
		$nrow1	= mssql_num_rows($res1);
		
		if ($nrow1!=0)
		{
			$out='Processed';
		}
	}
	
	mssql_connect('CORP-DB02','sa','date1995') or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");
	
	return $out;
	*/
}

function activematlist()
{
	$MAS=$_SESSION['pb_code'];
	$brdr=0;
	$i=0;

	$qry0  = "select  ";
	$qry0 .= "		DISTINCT(b.vpnum),a.item as aitem,b.item as bitem, ";
	$qry0 .= "		(select phsname from phasebase where phsid=a.phsid) as phs, ";
	$qry0 .= "		(select abrev from material_grp_codes where masgrp=b.masgrp) as abrev ";
	$qry0 .= "from [".$MAS."inventory] as a ";
	$qry0 .= "inner join material_master as b  ";
	$qry0 .= "		on a.matid=b.id  ";
	$qry0 .= "		where a.matid!=0 ";
	$qry0 .= "		and a.officeid='".$_SESSION['officeid']."' ";
	$qry0 .= "		and b.masgrp!='0' ";
	$qry0 .= "		and (select active from material_grp_codes where masgrp=b.masgrp)!='0' ";
	$qry0 .= "order by a.item;";

	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		echo "<table width=\"60%\" border=\"".$brdr."\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\" align=\"left\" valign=\"bottom\" colspan=\"6\"><b>Active Material List for ".$_SESSION['offname']."</b></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" ><b>&nbsp</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" ><b>Inventory Item</b>&nbsp&nbsp&nbsp&nbspLinked to --></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" ><b>Material Master Item</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" ><b>Part No</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" ><b>Phase</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" ><b>Mat Code</b></td>\n";
		//echo "		<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" ><b>&nbsp</b></td>\n";
		echo "	</tr>\n";


		while ($row0 = mssql_fetch_array($res0))
		{
			$i++;
			echo "	<tr>\n";
			echo "		<td class=\"wh_und\" align=\"right\" valign=\"bottom\" ><b>".$i.".</b></td>\n";
			echo "		<td class=\"wh_und\" align=\"left\" valign=\"bottom\" >&nbsp".$row0['aitem']."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\" valign=\"bottom\" >&nbsp".$row0['bitem']."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\" valign=\"bottom\" >&nbsp".$row0['vpnum']."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\" valign=\"bottom\" >&nbsp".$row0['phs']."</td>\n";
			echo "		<td class=\"wh_und\" align=\"center\" valign=\"bottom\" >&nbsp".$row0['abrev']."</td>\n";
			echo "	</tr>\n";
			//echo "HIT<BR>";
		}

		echo "</table>\n";
	}
	else
	{
		echo "<b>No Active Materials</b>";
	}
}

function bld_pubstorage()
{

}

function pbpub()
{
	if (isset($_REQUEST['pubnow']) && $_REQUEST['pubnow']==1)
	{
		echo "Pub Now!";

		$pchngdata=bld_pubstorage();
	}
	else
	{
		if (!isset($_REQUEST['pubdate']))
		{
			echo "<font color=\"red\"><b>Error!</b></font> No Publish Date Detected. Click Back and enter a valid Publish Date.";
			exit;
		}

		$pdate=split('/',$_REQUEST['pubdate'] );
		if (!checkdate($pdate[0],$pdate[1],$pdate[2]))
		{
			echo "<font color=\"red\"><b>Error!</b></font> Publish Date Out of Range. Click Back and enter a valid Publish Date.";
			exit;
		}

		if (strtotime($_REQUEST['pubdate']) < strtotime(date('m/d/Y')))
		{
			echo "<font color=\"red\"><b>Error!</b></font> Invalid date. Click Back and enter a valid Publish Date.";
			exit;
		}


		$pchngdata=bld_pubstorage();
	}

	show_post_vars();
}

function addspecaccpbook()
{
	//echo "TEST add2<br>";
	if ($_REQUEST['lrange']==0 && $_REQUEST['hrange']==0)
	{
		echo "<b>Invalid Range, click Back and correct.</b><br>";
	}
	else
	{
		$qry0 = "SELECT * FROM specaccpbook WHERE officeid='".$_SESSION['officeid']."' AND lrange='".$_REQUEST['lrange']."' and hrange='".$_REQUEST['hrange']."' and linkid='".$_REQUEST['linkid']."';";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		if ($nrow0 > 0)
		{
			echo "<b>Item, Range, and Price already exists, click Back and correct.</b><br>";
		}
		else
		{
			$qry1 = "INSERT INTO specaccpbook (linkid,officeid,lrange,hrange,bprice) VALUES ('".$_REQUEST['linkid']."','".$_SESSION['officeid']."','".$_REQUEST['lrange']."','".$_REQUEST['hrange']."','".number_format($_REQUEST['bprice'], 2, '.', '')."');";
			$res1 = mssql_query($qry1);
		}
	}

	acced($_REQUEST['id'],$_REQUEST['phsid']);
}

function editspecaccpbook()
{
	$qry0 = "SELECT id FROM specaccpbook WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['itemid']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		$qry1 = "UPDATE specaccpbook SET lrange='".$_REQUEST['lrange']."',hrange='".$_REQUEST['hrange']."',bprice='".number_format($_REQUEST['bprice'], 2, '.', '')."' WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['itemid']."';";
		$res1 = mssql_query($qry1);
	}

	acced($_REQUEST['id'],$_REQUEST['phsid']);
}

function deletespecaccpbook()
{
	$qry0 = "SELECT id FROM specaccpbook WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['itemid']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		$qry1 = "DELETE FROM specaccpbook WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['itemid']."';";
		$res1 = mssql_query($qry1);
	}

	acced($_REQUEST['id'],$_REQUEST['phsid']);
}

function copy_operation()
{
	$MAS=$_SESSION['pb_code'];
	$hcnt=0;
	if (empty($_REQUEST['offid']) || $_REQUEST['offid']==0)
	{
		echo "<font color=\"red\"><b>ERROR!</b></font>: Click Back and Select an Office to copy to<br>";
	}
	else
	{
		if (empty($_REQUEST['stage']) || $_REQUEST['stage']==0)
		{
			if (empty($_REQUEST['tcatid']))
			{
				echo "Category not set";
				exit;
			}

			if (is_array($_POST))
			{
				foreach ($_POST as $n=>$v)
				{
					if (substr($n,0,4)=="cpy_")
					{
						$hcnt++;
					}
				}
			}
			
			//echo "BOP";
			$qry0 = "SELECT officeid,name,pb_code FROM offices WHERE officeid='".$_REQUEST['offid']."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$qry1 = "SELECT catid,name,active FROM AC_cats WHERE officeid='".$_REQUEST['offid']."' AND catid='".$_REQUEST['tcatid']."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);
			$nrow1= mssql_num_rows($res1);

			//echo $qry1."<br>";

			$qry2 = "SELECT catid,name,active FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' and catid='".$_REQUEST['fcatid']."';";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);

			//echo $nrow1;
			if ($hcnt==0)
			{
				echo "<table class=\"outer\" align=\"center\" border=0>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\"><font color=\"red\"><b>ERROR!</b></font></td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\">No Items Selected for Copy!</td>\n";
				echo "	</tr>\n";
				echo "</table>\n";
				exit;
			}
			else
			{
				if (is_array($_POST))
				{
					$idar		=array();
					$idcnt	=0;
					foreach ($_POST as $n=>$v)
					{
						if (substr($n,0,4)=="cpy_")
						{
							//echo $n."<br>";
							$asid		=substr($n,4);
							$idar[]	=$asid;
							$idcnt++;
						}
					}
				}

				echo "<table class=\"outer\" align=\"center\" border=0>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\">Category match in: ".$row0['name']."</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\">Looking for: ".$row2['name']."</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\">Found: ".$row1['name']."</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\">\n";
				echo "			<table align=\"center\" width=\"100%\" border=0>\n";
				echo "				<tr>\n";
				echo "   				<td class=\"gray\" align=\"left\">\n";

				$cpcnt	=0;
				$flcnt	=0;
				$cscnt	=0;
				$llcnt	=0;
				$cllcnt	=0;
				$lmcnt	=0;
				$clmcnt	=0;
				foreach ($idar as $ni => $vi)
				{
					$qry3 = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' and aid='".$vi."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);

					$qry3a = "SELECT * FROM [".$row0['pb_code']."acc] WHERE officeid='".$_REQUEST['offid']."' and aid='".$row3['aid']."';";
					$res3a = mssql_query($qry3a);
					$row3a = mssql_fetch_array($res3a);
					$nrow3a= mssql_num_rows($res3a);

					//echo $qry3."<br>";
					//echo $qry3a."<br>";

					if ($nrow3a==0)
					{
						//echo "Inserting Top Level Retail Item<br>";
						$qryINS  = "INSERT INTO [".$row0['pb_code']."acc] (";
						$qryINS .= "[aid],";
						$qryINS .= "[officeid],";
						$qryINS .= "[phsid],";
						$qryINS .= "[catid],";
						$qryINS .= "[matid],";
						$qryINS .= "[subid],";
						$qryINS .= "[item],";
						$qryINS .= "[atrib1],";
						$qryINS .= "[atrib2],";
						$qryINS .= "[atrib3],";
						$qryINS .= "[accpbook],";
						$qryINS .= "[bp],";
						$qryINS .= "[rp],";
						$qryINS .= "[commtype],";
						$qryINS .= "[crate],";
						$qryINS .= "[qtype],";
						$qryINS .= "[spaitem],";
						$qryINS .= "[quan_calc],";
						$qryINS .= "[mtype],";
						$qryINS .= "[lrange],";
						$qryINS .= "[hrange],";
						$qryINS .= "[seqn],";
						$qryINS .= "[supplier],";
						$qryINS .= "[bullet],";
						$qryINS .= "[def_quan]";
						$qryINS .= ") VALUES (";
						$qryINS .= "'".$row3['aid']."',";
						$qryINS .= "'".$row0['officeid']."',";
						$qryINS .= "'".$row3['phsid']."',";
						$qryINS .= "'".$_REQUEST['tcatid']."',";
						$qryINS .= "'".$row3['matid']."',";
						$qryINS .= "'".$row3['subid']."',";
						$qryINS .= "'".$row3['item']."',";
						$qryINS .= "'".$row3['atrib1']."',";
						$qryINS .= "'".$row3['atrib2']."',";
						$qryINS .= "'".$row3['atrib3']."',";
						$qryINS .= "'".$row3['accpbook']."',";
						$qryINS .= "convert(money,'".$row3['bp']."'),";
						$qryINS .= "convert(money,'".$row3['rp']."'),";
						$qryINS .= "'".$row3['commtype']."',";
						$qryINS .= "'".$row3['crate']."',";
						$qryINS .= "'".$row3['qtype']."',";
						$qryINS .= "'".$row3['spaitem']."',";
						$qryINS .= "'".$row3['quan_calc']."',";
						$qryINS .= "'".$row3['mtype']."',";
						$qryINS .= "'".$row3['lrange']."',";
						$qryINS .= "'".$row3['hrange']."',";
						$qryINS .= "'".$row3['seqn']."',";
						$qryINS .= "'".$row3['supplier']."',";
						$qryINS .= "'".$row3['bullet']."',";
						$qryINS .= "'".$row3['def_quan']."'";
						$qryINS .= ")";
						$resINS  = mssql_query($qryINS);

						$qrySEL1 = "SELECT id FROM [".$row0['pb_code']."acc] WHERE officeid='".$row0['officeid']."' and aid='".$row3['aid']."';";
						$resSEL1 = mssql_query($qrySEL1);
						$rowSEL1 = mssql_fetch_array($resSEL1);

						//echo "SEL1: ".$rowSEL1['id']."<br>";

						$cpcnt++; //Retail Item Counter

						//Direct Cost Item Copy Loop (Labor)
						$qryA = "SELECT * FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' and rid='".$row3['id']."';";
						$resA = mssql_query($qryA);
						$nrowA= mssql_num_rows($resA);
						//echo "QRYA: ".$qryA."<br>";
						//echo "NROA: ".$nrowA."<br>";
						if ($nrowA > 0)
						{
							while ($rowA = mssql_fetch_array($resA))
							{
								$qryFNDAa = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' and id='".$rowA['cid']."';";
								$resFNDAa = mssql_query($qryFNDAa);
								$rowFNDAa = mssql_fetch_array($resFNDAa);

								$qryFNDAb = "SELECT id,accid FROM [".$row0['pb_code']."accpbook] WHERE officeid='".$row0['officeid']."' and accid='".$rowFNDAa['accid']."';";
								$resFNDAb = mssql_query($qryFNDAb);
								$rowFNDAb = mssql_fetch_array($resFNDAb);
								$nrowFNDAb= mssql_num_rows($resFNDAb);

								if ($nrowFNDAb==0)
								{
									$qryINSA  = "INSERT INTO [".$row0['pb_code']."accpbook] (";
									$qryINSA .= "[officeid],";
									$qryINSA .= "[accid],";
									$qryINSA .= "[phsid],";
									$qryINSA .= "[matid],";
									$qryINSA .= "[seqnum],";
									$qryINSA .= "[item],";
									$qryINSA .= "[atrib1],";
									$qryINSA .= "[atrib2],";
									$qryINSA .= "[atrib3],";
									$qryINSA .= "[mtype],";
									$qryINSA .= "[lrange],";
									$qryINSA .= "[hrange],";
									$qryINSA .= "[bprice],";
									$qryINSA .= "[rprice],";
									$qryINSA .= "[rebate],";
									$qryINSA .= "[rpbid],";
									$qryINSA .= "[baseitem],";
									$qryINSA .= "[quantity],";
									$qryINSA .= "[qtype],";
									$qryINSA .= "[raccid],";
									$qryINSA .= "[rinvid],";
									$qryINSA .= "[spaitem],";
									$qryINSA .= "[zcharge],";
									$qryINSA .= "[supplier],";
									$qryINSA .= "[supercedes],";
									$qryINSA .= "[code]";
									$qryINSA .= ") VALUES (";
									$qryINSA .= "'".$row0['officeid']."',";
									$qryINSA .= "'".$rowFNDAa['accid']."',";
									$qryINSA .= "'".$rowFNDAa['phsid']."',";
									$qryINSA .= "'".$rowFNDAa['matid']."',";
									$qryINSA .= "'".$rowFNDAa['seqnum']."',";
									$qryINSA .= "'".$rowFNDAa['item']."',";
									$qryINSA .= "'".$rowFNDAa['atrib1']."',";
									$qryINSA .= "'".$rowFNDAa['atrib2']."',";
									$qryINSA .= "'".$rowFNDAa['atrib3']."',";
									$qryINSA .= "'".$rowFNDAa['mtype']."',";
									$qryINSA .= "'".$rowFNDAa['lrange']."',";
									$qryINSA .= "'".$rowFNDAa['hrange']."',";
									$qryINSA .= "convert(money,'".$rowFNDAa['bprice']."'),";
									$qryINSA .= "convert(money,'".$rowFNDAa['rprice']."'),";
									$qryINSA .= "'".$rowFNDAa['rebate']."',";
									$qryINSA .= "'".$rowFNDAa['rpbid']."',";
									$qryINSA .= "'".$rowFNDAa['baseitem']."',";
									$qryINSA .= "'".$rowFNDAa['quantity']."',";
									$qryINSA .= "'".$rowFNDAa['qtype']."',";
									$qryINSA .= "'".$rowFNDAa['raccid']."',";
									$qryINSA .= "'".$rowFNDAa['rinvid']."',";
									$qryINSA .= "'".$rowFNDAa['spaitem']."',";
									$qryINSA .= "'".$rowFNDAa['zcharge']."',";
									$qryINSA .= "'".$rowFNDAa['supplier']."',";
									$qryINSA .= "'".$rowFNDAa['supercedes']."',";
									$qryINSA .= "'".$rowFNDAa['code']."'";
									$qryINSA .= ")";
									$resINSA = mssql_query($qryINSA);
									//echo "INSA: ".$qryINSA."<br>";
									//echo "Direct Labor Cost Item Copied<br>";
									$llcnt++;

									$qrySELA = "SELECT * FROM [".$row0['pb_code']."accpbook] WHERE officeid='".$row0['officeid']."' and accid='".$rowFNDAa['accid']."';";
									$resSELA = mssql_query($qrySELA);
									$rowSELA = mssql_fetch_array($resSELA);

									//echo "qrySELA: ".$qrySELA."<br>";
									//echo "SELA: ".$rowSELA['id']."<br>";

									$qryTSTA = "SELECT * FROM [".$row0['pb_code']."rclinks_l] WHERE officeid='".$row0['officeid']."' AND rid='".$rowSEL1['id']."' AND cid='".$rowSELA['id']."';";
									$resTSTA = mssql_query($qryTSTA);
									$nrowTSTA= mssql_num_rows($resTSTA);

									if ($nrowTSTA==0)
									{
										$qryINSAa  = "INSERT INTO [".$row0['pb_code']."rclinks_l] (";
										$qryINSAa .= "[officeid],";
										$qryINSAa .= "[rid],";
										$qryINSAa .= "[cid]";
										$qryINSAa .= ") VALUES (";
										$qryINSAa .= "'".$row0['officeid']."',";
										$qryINSAa .= "'".$rowSEL1['id']."',";
										$qryINSAa .= "'".$rowSELA['id']."'";
										$qryINSAa .= ")";
										$resINSAa = mssql_query($qryINSAa);

										//echo "Direct Labor Cost Tie Copied<br>";
										$cllcnt++;

										$qrySELAa = "SELECT id FROM [".$row0['pb_code']."rclinks_l] WHERE officeid='".$row0['officeid']."' AND rid='".$rowSEL1['id']."' AND cid='".$rowSELA['accid']."';";
										$resSELAa = mssql_query($qrySELAa);
										$rowSELAa = mssql_fetch_array($resSELAa);
										//echo "SELAa: ".$rowSELAa['id']."<br>";
									}
								}
							}
						}

						//Direct Cost Item Copy Loop (Inventory)
						$qryB = "SELECT * FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' and rid='".$row3['id']."';";
						$resB = mssql_query($qryB);
						$nrowB= mssql_num_rows($resB);
						//echo "QRYB: ".$qryB."<br>";
						//echo "NROB: ".$nrowB."<br>";
						if ($nrowB > 0)
						{
							while ($rowB = mssql_fetch_array($resB))
							{
								$qryFNDBa = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' and invid='".$rowB['cid']."';";
								$resFNDBa = mssql_query($qryFNDBa);
								$rowFNDBa = mssql_fetch_array($resFNDBa);

								$qryFNDBb = "SELECT invid,accid FROM [".$row0['pb_code']."inventory] WHERE officeid='".$row0['officeid']."' and accid='".$rowFNDBa['accid']."';";
								$resFNDBb = mssql_query($qryFNDBb);
								$rowFNDBb = mssql_fetch_array($resFNDBb);
								$nrowFNDBb= mssql_num_rows($resFNDBb);

								if ($nrowFNDBb==0)
								{
									$qryINSB  = "INSERT INTO [".$row0['pb_code']."inventory] (";
									$qryINSB .= "[officeid],";
									$qryINSB .= "[accid],";
									$qryINSB .= "[phsid],";
									$qryINSB .= "[raccid],";
									$qryINSB .= "[rinvid],";
									$qryINSB .= "[vid],";
									$qryINSB .= "[matid],";
									$qryINSB .= "[vendor],";
									$qryINSB .= "[vpno],";
									$qryINSB .= "[item],";
									$qryINSB .= "[atrib1],";
									$qryINSB .= "[atrib2],";
									$qryINSB .= "[atrib3],";
									$qryINSB .= "[mtype],";
									$qryINSB .= "[bprice],";
									$qryINSB .= "[rprice],";
									$qryINSB .= "[quan_calc],";
									$qryINSB .= "[commtype],";
									$qryINSB .= "[crate],";
									$qryINSB .= "[seqnum],";
									$qryINSB .= "[baseitem],";
									$qryINSB .= "[spaitem],";
									$qryINSB .= "[qtype],";
									$qryINSB .= "[active]";
									$qryINSB .= ") VALUES (";
									$qryINSB .= "'".$row0['officeid']."',";
									$qryINSB .= "'".$rowFNDBa['accid']."',";
									$qryINSB .= "'".$rowFNDBa['phsid']."',";
									$qryINSB .= "'".$rowFNDBa['raccid']."',";
									$qryINSB .= "'".$rowFNDBa['rinvid']."',";
									$qryINSB .= "'".$rowFNDBa['vid']."',";
									$qryINSB .= "'".$rowFNDBa['matid']."',";
									$qryINSB .= "'".$rowFNDBa['vendor']."',";
									$qryINSB .= "'".$rowFNDBa['vpno']."',";
									$qryINSB .= "'".$rowFNDBa['item']."',";
									$qryINSB .= "'".$rowFNDBa['atrib1']."',";
									$qryINSB .= "'".$rowFNDBa['atrib2']."',";
									$qryINSB .= "'".$rowFNDBa['atrib3']."',";
									$qryINSB .= "'".$rowFNDBa['mtype']."',";
									$qryINSB .= "convert(money,'".$rowFNDBa['bprice']."'),";
									$qryINSB .= "convert(money,'".$rowFNDBa['rprice']."'),";
									$qryINSB .= "'".$rowFNDBa['quan_calc']."',";
									$qryINSB .= "'".$rowFNDBa['commtype']."',";
									$qryINSB .= "'".$rowFNDBa['crate']."',";
									$qryINSB .= "'".$rowFNDBa['seqnum']."',";
									$qryINSB .= "'".$rowFNDBa['baseitem']."',";
									$qryINSB .= "'".$rowFNDBa['spaitem']."',";
									$qryINSB .= "'".$rowFNDBa['qtype']."',";
									$qryINSB .= "'".$rowFNDBa['active']."'";
									$qryINSB .= ")";
									$resINSB = mssql_query($qryINSB);

									//echo "Direct Inventory Cost Item Copied<br>";
									$lmcnt++;

									$qrySELB = "SELECT * FROM [".$row0['pb_code']."inventory] WHERE officeid='".$row0['officeid']."' and accid='".$rowFNDBa['accid']."';";
									$resSELB = mssql_query($qrySELB);
									$rowSELB = mssql_fetch_array($resSELB);

									//echo "qrySELB: ".$qrySELB."<br>";
									//echo "SELB: ".$rowSELB['invid']."<br>";

									$qryTSTB = "SELECT * FROM [".$row0['pb_code']."rclinks_m] WHERE officeid='".$row0['officeid']."' AND rid='".$rowSEL1['id']."' AND cid='".$rowSELB['invid']."';";
									$resTSTB = mssql_query($qryTSTB);
									$nrowTSTB= mssql_num_rows($resTSTB);

									if ($nrowTSTB==0)
									{
										$qryINSBa  = "INSERT INTO [".$row0['pb_code']."rclinks_m] (";
										$qryINSBa .= "[officeid],";
										$qryINSBa .= "[rid],";
										$qryINSBa .= "[cid]";
										$qryINSBa .= ") VALUES (";
										$qryINSBa .= "'".$row0['officeid']."',";
										$qryINSBa .= "'".$rowSEL1['id']."',";
										$qryINSBa .= "'".$rowSELB['invid']."'";
										$qryINSBa .= ")";
										$resINSBa = mssql_query($qryINSBa);

										//echo "Direct Inventory Cost Tie Copied<br>";
										$clmcnt++;

										$qrySELBa = "SELECT id FROM [".$row0['pb_code']."rclinks_m] WHERE officeid='".$row0['officeid']."' AND rid='".$rowSEL1['id']."' AND cid='".$rowSELB['invid']."';";
										$resSELBa = mssql_query($qrySELBa);
										$rowSELBa = mssql_fetch_array($resSELBa);
										//echo "SELAa: ".$rowSELAa['id']."<br>";
									}
								}
							}
						}


						// Package Retail Item Copy Loop
						if ($row3['qtype']==55||$row3['qtype']==72)
						{
							//echo "is Main Package Object<br>";

							// Retail Package Filter
							$qry4 = "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' and rid='".$row3['id']."';";
							$res4 = mssql_query($qry4);

							$fidar=array();
							while ($row4 = mssql_fetch_array($res4))
							{
								$fidar[]=$row4['id'];
								//echo "&nbsp&nbspFilter Link<br>";

								//Filter Linked Retail Items
								$qry5 = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' and id='".$row4['iid']."';";
								$res5 = mssql_query($qry5);

								$lidar=array();
								while ($row5 = mssql_fetch_array($res5))
								{
									//print_r($row5);
									$lidar[]=$row5['aid'];
									//echo "&nbsp&nbsp&nbsp&nbspFilter Retail Object<br>";

									$qry6 = "SELECT * FROM [".$row0['pb_code']."acc] WHERE officeid='".$row0['officeid']."' and id='".$row5['aid']."';";
									$res6 = mssql_query($qry6);
									$nrow6= mssql_num_rows($res6);

									if ($nrow6==0)
									{
										//Find Copy To Cat id
										$qryFND1 = "SELECT catid,name,active FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row5['catid']."';";
										$resFND1 = mssql_query($qryFND1);
										$rowFND1 = mssql_fetch_array($resFND1);

										$qryFND2 = "SELECT catid,name,active FROM AC_cats WHERE officeid='".$row0['officeid']."' AND catid='".$rowFND1['catid']."';";
										$resFND2 = mssql_query($qryFND2);
										$rowFND2 = mssql_fetch_array($resFND2);
										$nrowFND2= mssql_num_rows($resFND2);

										if ($nrowFND2==1)
										{
											if ($rowFND1['name']==$rowFND2['name'])
											{
												$fltcatid=$rowFND2['catid'];
											}
											else
											{
												$fltcatid=0;
											}
										}
										else
										{
											$fltcatid=0;
										}

										//echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspNot Exist<br>";
										// Insert Filter Linked Item
										$qryINS2  = "INSERT INTO [".$row0['pb_code']."acc] (";
										$qryINS2 .= "[aid],";
										$qryINS2 .= "[officeid],";
										$qryINS2 .= "[phsid],";
										$qryINS2 .= "[catid],";
										$qryINS2 .= "[matid],";
										$qryINS2 .= "[subid],";
										$qryINS2 .= "[item],";
										$qryINS2 .= "[atrib1],";
										$qryINS2 .= "[atrib2],";
										$qryINS2 .= "[atrib3],";
										$qryINS2 .= "[accpbook],";
										$qryINS2 .= "[bp],";
										$qryINS2 .= "[rp],";
										$qryINS2 .= "[commtype],";
										$qryINS2 .= "[crate],";
										$qryINS2 .= "[qtype],";
										$qryINS2 .= "[spaitem],";
										$qryINS2 .= "[quan_calc],";
										$qryINS2 .= "[mtype],";
										$qryINS2 .= "[lrange],";
										$qryINS2 .= "[hrange],";
										$qryINS2 .= "[seqn],";
										$qryINS2 .= "[supplier],";
										$qryINS2 .= "[bullet],";
										$qryINS2 .= "[def_quan]";
										$qryINS2 .= ") VALUES (";
										$qryINS2 .= "'".$row5['aid']."',";
										$qryINS2 .= "'".$row0['officeid']."',";
										$qryINS2 .= "'".$row5['phsid']."',";
										$qryINS2 .= "'".$fltcatid."',";
										$qryINS2 .= "'".$row5['matid']."',";
										$qryINS2 .= "'".$row5['subid']."',";
										$qryINS2 .= "'".$row5['item']."',";
										$qryINS2 .= "'".$row5['atrib1']."',";
										$qryINS2 .= "'".$row5['atrib2']."',";
										$qryINS2 .= "'".$row5['atrib3']."',";
										$qryINS2 .= "'".$row5['accpbook']."',";
										$qryINS2 .= "convert(money,'".$row5['bp']."'),";
										$qryINS2 .= "convert(money,'".$row5['rp']."'),";
										$qryINS2 .= "'".$row5['commtype']."',";
										$qryINS2 .= "'".$row5['crate']."',";
										$qryINS2 .= "'".$row5['qtype']."',";
										$qryINS2 .= "'".$row5['spaitem']."',";
										$qryINS2 .= "'".$row5['quan_calc']."',";
										$qryINS2 .= "'".$row5['mtype']."',";
										$qryINS2 .= "'".$row5['lrange']."',";
										$qryINS2 .= "'".$row5['hrange']."',";
										$qryINS2 .= "'".$row5['seqn']."',";
										$qryINS2 .= "'".$row5['supplier']."',";
										$qryINS2 .= "'".$row5['bullet']."',";
										$qryINS2 .= "'".$row5['def_quan']."'";
										$qryINS2 .= ")";
										$resINS2 = mssql_query($qryINS2);
										//echo "QRY: ".$qryINS."<br>";

										$qrySEL2 = "SELECT id FROM [".$row0['pb_code']."acc] WHERE officeid='".$row0['officeid']."' and aid='".$row5['aid']."';";
										$resSEL2 = mssql_query($qrySEL2);
										$rowSEL2 = mssql_fetch_array($resSEL2);
										//echo "SEL: ".$qrySEL."<br>";
										//echo "SEL2: ".$rowSEL2['id']."<br>";

										$cpcnt++;

										$qry7 = "SELECT * FROM [".$row0['pb_code']."plinks] WHERE officeid='".$row0['officeid']."' AND rid='".$row3['id']."' AND iid='".$rowSEL2['id']."';";
										$res7 = mssql_query($qry7);
										$nrow7= mssql_num_rows($res7);

										if ($nrow7==0)
										{
											//Insert New Filter Link
											$qryINS3  = "INSERT INTO [".$row0['pb_code']."plinks] (";
											$qryINS3 .= "[officeid],";
											$qryINS3 .= "[rid],";
											$qryINS3 .= "[iid],";
											$qryINS3 .= "[adjtype],";
											$qryINS3 .= "[adjamt],";
											$qryINS3 .= "[adjquan],";
											$qryINS3 .= "[seqn]";
											$qryINS3 .= ") VALUES (";
											$qryINS3 .= "'".$row0['officeid']."',";
											$qryINS3 .= "'".$rowSEL1['id']."',";
											$qryINS3 .= "'".$rowSEL2['id']."',";
											$qryINS3 .= "'".$row4['adjtype']."',";
											$qryINS3 .= "'".$row4['adjamt']."',";
											$qryINS3 .= "'".$row4['adjquan']."',";
											$qryINS3 .= "'".$row4['seqn']."'";
											$qryINS3 .= ")";
											$resINS3 = mssql_query($qryINS3);

											$flcnt++;

											$qrySEL3 = "SELECT id FROM [".$row0['pb_code']."plinks] WHERE officeid='".$row0['officeid']."' AND rid='".$rowSEL1['id']."' AND iid='".$rowSEL2['id']."';";
											$resSEL3 = mssql_query($qrySEL3);
											$rowSEL3 = mssql_fetch_array($resSEL3);
											//echo "SEL3: ".$rowSEL3['id']."<br>";
										}

										//Package Link Cost Item Copy Loop (Labor)
										$qryAsub = "SELECT * FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' and rid='".$row5['id']."';";
										$resAsub = mssql_query($qryAsub);
										$nrowAsub= mssql_num_rows($resAsub);
										//echo "QRYAsub: ".$qryAsub."<br>";
										//echo "NROAsub: ".$nrowAsub."<br>";
										if ($nrowAsub > 0)
										{
											while ($rowAsub = mssql_fetch_array($resAsub))
											{
												$qryFNDAasub = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' and id='".$rowAsub['cid']."';";
												$resFNDAasub = mssql_query($qryFNDAasub);
												$rowFNDAasub = mssql_fetch_array($resFNDAasub);

												$qryFNDAbsub = "SELECT id,accid FROM [".$row0['pb_code']."accpbook] WHERE officeid='".$row0['officeid']."' and accid='".$rowFNDAasub['accid']."';";
												$resFNDAbsub = mssql_query($qryFNDAbsub);
												$rowFNDAbsub = mssql_fetch_array($resFNDAbsub);
												$nrowFNDAbsub= mssql_num_rows($resFNDAbsub);

												if ($nrowFNDAbsub==0)
												{
													$qryINSAsub  = "INSERT INTO [".$row0['pb_code']."accpbook] (";
													$qryINSAsub .= "[officeid],";
													$qryINSAsub .= "[accid],";
													$qryINSAsub .= "[phsid],";
													$qryINSAsub .= "[matid],";
													$qryINSAsub .= "[seqnum],";
													$qryINSAsub .= "[item],";
													$qryINSAsub .= "[atrib1],";
													$qryINSAsub .= "[atrib2],";
													$qryINSAsub .= "[atrib3],";
													$qryINSAsub .= "[mtype],";
													$qryINSAsub .= "[lrange],";
													$qryINSAsub .= "[hrange],";
													$qryINSAsub .= "[bprice],";
													$qryINSAsub .= "[rprice],";
													$qryINSAsub .= "[rebate],";
													$qryINSAsub .= "[rpbid],";
													$qryINSAsub .= "[baseitem],";
													$qryINSAsub .= "[quantity],";
													$qryINSAsub .= "[qtype],";
													$qryINSAsub .= "[raccid],";
													$qryINSAsub .= "[rinvid],";
													$qryINSAsub .= "[spaitem],";
													$qryINSAsub .= "[zcharge],";
													$qryINSAsub .= "[supplier],";
													$qryINSAsub .= "[supercedes],";
													$qryINSAsub .= "[code]";
													$qryINSAsub .= ") VALUES (";
													$qryINSAsub .= "'".$row0['officeid']."',";
													$qryINSAsub .= "'".$rowFNDAasub['accid']."',";
													$qryINSAsub .= "'".$rowFNDAasub['phsid']."',";
													$qryINSAsub .= "'".$rowFNDAasub['matid']."',";
													$qryINSAsub .= "'".$rowFNDAasub['seqnum']."',";
													$qryINSAsub .= "'".$rowFNDAasub['item']."',";
													$qryINSAsub .= "'".$rowFNDAasub['atrib1']."',";
													$qryINSAsub .= "'".$rowFNDAasub['atrib2']."',";
													$qryINSAsub .= "'".$rowFNDAasub['atrib3']."',";
													$qryINSAsub .= "'".$rowFNDAasub['mtype']."',";
													$qryINSAsub .= "'".$rowFNDAasub['lrange']."',";
													$qryINSAsub .= "'".$rowFNDAasub['hrange']."',";
													$qryINSAsub .= "convert(money,'".$rowFNDAasub['bprice']."'),";
													$qryINSAsub .= "convert(money,'".$rowFNDAasub['rprice']."'),";
													$qryINSAsub .= "'".$rowFNDAasub['rebate']."',";
													$qryINSAsub .= "'".$rowFNDAasub['rpbid']."',";
													$qryINSAsub .= "'".$rowFNDAasub['baseitem']."',";
													$qryINSAsub .= "'".$rowFNDAasub['quantity']."',";
													$qryINSAsub .= "'".$rowFNDAasub['qtype']."',";
													$qryINSAsub .= "'".$rowFNDAasub['raccid']."',";
													$qryINSAsub .= "'".$rowFNDAasub['rinvid']."',";
													$qryINSAsub .= "'".$rowFNDAasub['spaitem']."',";
													$qryINSAsub .= "'".$rowFNDAasub['zcharge']."',";
													$qryINSAsub .= "'".$rowFNDAasub['supplier']."',";
													$qryINSAsub .= "'".$rowFNDAasub['supercedes']."',";
													$qryINSAsub .= "'".$rowFNDAasub['code']."'";
													$qryINSAsub .= ")";
													$resINSAsub  = mssql_query($qryINSAsub);
													//echo "INSA: ".$qryINSA."<br>";
													//echo "Direct Labor Cost Item Copied<br>";
													$llcnt++;

													$qrySELAsub = "SELECT * FROM [".$row0['pb_code']."accpbook] WHERE officeid='".$row0['officeid']."' and accid='".$rowFNDAasub['accid']."';";
													$resSELAsub = mssql_query($qrySELAsub);
													$rowSELAsub = mssql_fetch_array($resSELAsub);

													//echo "qrySELA: ".$qrySELA."<br>";
													//echo "SELA: ".$rowSELA['id']."<br>";

													$qryTSTAsub = "SELECT * FROM [".$row0['pb_code']."rclinks_l] WHERE officeid='".$row0['officeid']."' AND rid='".$rowSEL2['id']."' AND cid='".$rowSELAsub['id']."';";
													$resTSTAsub = mssql_query($qryTSTAsub);
													$nrowTSTAsub= mssql_num_rows($resTSTAsub);

													if ($nrowTSTAsub==0)
													{
														$qryINSAasub  = "INSERT INTO [".$row0['pb_code']."rclinks_l] (";
														$qryINSAasub .= "[officeid],";
														$qryINSAasub .= "[rid],";
														$qryINSAasub .= "[cid]";
														$qryINSAasub .= ") VALUES (";
														$qryINSAasub .= "'".$row0['officeid']."',";
														$qryINSAasub .= "'".$rowSEL2['id']."',";
														$qryINSAasub .= "'".$rowSELAsub['id']."'";
														$qryINSAasub .= ")";
														$resINSAasub = mssql_query($qryINSAasub);

														//echo "Package Filter Labor Cost Tie Copied<br>";
														$cllcnt++;

														$qrySELAasub = "SELECT id FROM [".$row0['pb_code']."rclinks_l] WHERE officeid='".$row0['officeid']."' AND rid='".$rowSEL2['id']."' AND cid='".$rowSELAsub['accid']."';";
														$resSELAasub = mssql_query($qrySELAasub);
														$rowSELAasub = mssql_fetch_array($resSELAasub);
														//echo "SELAa: ".$rowSELAa['id']."<br>";
													}
												}
											}
										}

										//Package Link Cost Item Copy Loop (Inventory)
										$qryBsub = "SELECT * FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' and rid='".$row5['id']."';";
										$resBsub = mssql_query($qryBsub);
										$nrowBsub= mssql_num_rows($resBsub);
										//echo "QRYB: ".$qryB."<br>";
										//echo "NROB: ".$nrowB."<br>";
										if ($nrowBsub > 0)
										{
											while ($rowBsub = mssql_fetch_array($resBsub))
											{
												$qryFNDBasub = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' and invid='".$rowBsub['cid']."';";
												$resFNDBasub = mssql_query($qryFNDBasub);
												$rowFNDBasub = mssql_fetch_array($resFNDBasub);

												$qryFNDBbsub = "SELECT invid,accid FROM [".$row0['pb_code']."inventory] WHERE officeid='".$row0['officeid']."' and accid='".$rowFNDBasub['accid']."';";
												$resFNDBbsub = mssql_query($qryFNDBbsub);
												$rowFNDBbsub = mssql_fetch_array($resFNDBbsub);
												$nrowFNDBbsub= mssql_num_rows($resFNDBbsub);

												if ($nrowFNDBbsub==0)
												{
													$qryINSBsub  = "INSERT INTO [".$row0['pb_code']."inventory] (";
													$qryINSBsub .= "[officeid],";
													$qryINSBsub .= "[accid],";
													$qryINSBsub .= "[phsid],";
													$qryINSBsub .= "[raccid],";
													$qryINSBsub .= "[rinvid],";
													$qryINSBsub .= "[vid],";
													$qryINSBsub .= "[matid],";
													$qryINSBsub .= "[vendor],";
													$qryINSBsub .= "[vpno],";
													$qryINSBsub .= "[item],";
													$qryINSBsub .= "[atrib1],";
													$qryINSBsub .= "[atrib2],";
													$qryINSBsub .= "[atrib3],";
													$qryINSBsub .= "[mtype],";
													$qryINSBsub .= "[bprice],";
													$qryINSBsub .= "[rprice],";
													$qryINSBsub .= "[quan_calc],";
													$qryINSBsub .= "[commtype],";
													$qryINSBsub .= "[crate],";
													$qryINSBsub .= "[seqnum],";
													$qryINSBsub .= "[baseitem],";
													$qryINSBsub .= "[spaitem],";
													$qryINSBsub .= "[qtype],";
													$qryINSBsub .= "[active]";
													$qryINSBsub .= ") VALUES (";
													$qryINSBsub .= "'".$row0['officeid']."',";
													$qryINSBsub .= "'".$rowFNDBasub['accid']."',";
													$qryINSBsub .= "'".$rowFNDBasub['phsid']."',";
													$qryINSBsub .= "'".$rowFNDBasub['raccid']."',";
													$qryINSBsub .= "'".$rowFNDBasub['rinvid']."',";
													$qryINSBsub .= "'".$rowFNDBasub['vid']."',";
													$qryINSBsub .= "'".$rowFNDBasub['matid']."',";
													$qryINSBsub .= "'".$rowFNDBasub['vendor']."',";
													$qryINSBsub .= "'".$rowFNDBasub['vpno']."',";
													$qryINSBsub .= "'".$rowFNDBasub['item']."',";
													$qryINSBsub .= "'".$rowFNDBasub['atrib1']."',";
													$qryINSBsub .= "'".$rowFNDBasub['atrib2']."',";
													$qryINSBsub .= "'".$rowFNDBasub['atrib3']."',";
													$qryINSBsub .= "'".$rowFNDBasub['mtype']."',";
													$qryINSBsub .= "convert(money,'".$rowFNDBasub['bprice']."'),";
													$qryINSBsub .= "convert(money,'".$rowFNDBasub['rprice']."'),";
													$qryINSBsub .= "'".$rowFNDBasub['quan_calc']."',";
													$qryINSBsub .= "'".$rowFNDBasub['commtype']."',";
													$qryINSBsub .= "'".$rowFNDBasub['crate']."',";
													$qryINSBsub .= "'".$rowFNDBasub['seqnum']."',";
													$qryINSBsub .= "'".$rowFNDBasub['baseitem']."',";
													$qryINSBsub .= "'".$rowFNDBasub['spaitem']."',";
													$qryINSBsub .= "'".$rowFNDBasub['qtype']."',";
													$qryINSBsub .= "'".$rowFNDBasub['active']."'";
													$qryINSBsub .= ")";
													$resINSBsub = mssql_query($qryINSBsub);

													//echo "Direct Inventory Cost Item Copied<br>";
													$lmcnt++;

													$qrySELBsub = "SELECT * FROM [".$row0['pb_code']."inventory] WHERE officeid='".$row0['officeid']."' and accid='".$rowFNDBasub['accid']."';";
													$resSELBsub = mssql_query($qrySELBsub);
													$rowSELBsub = mssql_fetch_array($resSELBsub);

													//echo "qrySELB: ".$qrySELB."<br>";
													//echo "SELB: ".$rowSELB['invid']."<br>";

													$qryTSTBsub = "SELECT * FROM [".$row0['pb_code']."rclinks_m] WHERE officeid='".$row0['officeid']."' AND rid='".$rowSEL2['id']."' AND cid='".$rowSELBsub['invid']."';";
													$resTSTBsub = mssql_query($qryTSTBsub);
													$nrowTSTBsub= mssql_num_rows($resTSTBsub);

													if ($nrowTSTBsub==0)
													{
														$qryINSBasub  = "INSERT INTO [".$row0['pb_code']."rclinks_m] (";
														$qryINSBasub .= "[officeid],";
														$qryINSBasub .= "[rid],";
														$qryINSBasub .= "[cid]";
														$qryINSBasub .= ") VALUES (";
														$qryINSBasub .= "'".$row0['officeid']."',";
														$qryINSBasub .= "'".$rowSEL2['id']."',";
														$qryINSBasub .= "'".$rowSELBsub['invid']."'";
														$qryINSBasub .= ")";
														$resINSBasub = mssql_query($qryINSBasub);

														//echo "Package Filter Inventory Cost Tie Copied<br>";
														$clmcnt++;

														$qrySELBasub = "SELECT id FROM [".$row0['pb_code']."rclinks_m] WHERE officeid='".$row0['officeid']."' AND rid='".$rowSEL2['id']."' AND cid='".$rowSELBsub['invid']."';";
														$resSELBasub = mssql_query($qrySELBasub);
														$rowSELBasub = mssql_fetch_array($resSELBasub);
														//echo "SELAa: ".$rowSELAa['id']."<br>";
													}
												}
											}
										}
									}
									//echo "<br>";
								}
							}
						}
					}
					else
					{
						echo "Item Code Exists: ".$vi."<br>";
					}
				}

				echo "					</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
				echo "		</td>\n";
				echo "	</tr>\n";

				$cscnt=$llcnt+$lmcnt;
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\"><b>".$cpcnt."</b> Total Retail Items Copied</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\"><b>".$flcnt."</b> Total Package Filters Copied</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\"><b>".$llcnt."</b> Labor Cost Items Copied</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\"><b>".$cllcnt."</b> Labor Cost Ties Copied</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\"><b>".$lmcnt."</b> Inventory Cost Items Copied</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\"><b>".$clmcnt."</b> Inventory Cost Ties Copied</td>\n";
				echo "	</tr>\n";
				echo "	<tr>\n";
				echo "   	<td class=\"gray\" align=\"left\"><b>".$cscnt."</b> Total Cost Items Copied</td>\n";
				echo "	</tr>\n";
				echo "</table>\n";
			}
		}
	}
}

function catlist()
{
	if ($_SESSION['jlev'] < 5)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}
	else
	{
		if (!empty($_GET['order']))
		{
			$order=$_GET['order'];
		}
		else
		{
			$order="seqn";
		}

		$qry = "SELECT * FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' ORDER BY $order ASC";
		$res = mssql_query($qry);
		$nrow= mssql_num_rows($res);
		
		
		//Add Form
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"cat\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"add\">\n";
		echo "<table class=\"outer\" width=\"400px\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray_und\" colspan=\"2\"><b>Add Category</b></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "			<td align=\"right\"><b>Category Name</b></td>\n";
		echo "			<td>\n";
		echo "				<input type=\"text\" name=\"name\" size=\"40\" maxlength=\"50\">\n";
		echo "			</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "			<td align=\"right\"><b>QB ParentItem</b></td>\n";
		echo "			<td>\n";
		echo "				<input type=\"text\" name=\"ParentID\" size=\"40\" maxlength=\"50\">\n";
		echo "			</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "			<td align=\"right\"><b>QB Account</b></td>\n";
		echo "			<td>\n";
		echo "				<input type=\"text\" name=\"AccountName\" size=\"40\" maxlength=\"50\">\n";
		echo "			</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "			<td align=\"right\"><b>Active</b></td>\n";
		echo "			<td>\n";
		echo "      		<select name=\"active\">\n";
		echo "         		<option value=\"1\" SELECTED>Yes</option>\n";
		echo "         		<option value=\"0\">No</option>\n";
		echo "      		</select>\n";
		echo "			</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "			<td align=\"right\"><b>Private</b></td>\n";
		echo "			<td>\n";
		echo "      		<select name=\"privcat\">\n";
		echo "         		<option value=\"1\">Yes</option>\n";
		echo "         		<option value=\"0\" SELECTED>No</option>\n";
		echo "      		</select>\n";
		echo "			</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "			<td align=\"right\"><b>Sales Type</b></td>\n";
		echo "			<td>\n";
		echo "      		<select name=\"salestype\">\n";
		echo "         		<option value=\"0\" SELECTED>Pool</option>\n";
		echo "         		<option value=\"1\">Other</option>\n";
		echo "      		</select>\n";
		echo "			</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "			<td align=\"right\"><b>Required</b></td>\n";
		echo "			<td>\n";
		echo "      		<select name=\"irequired\">\n";
		echo "         		<option value=\"1\">Yes</option>\n";
		echo "         		<option value=\"0\" SELECTED>No</option>\n";
		echo "      		</select>\n";
		echo "			</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "			<td></td>\n";
		echo "			<td align=\"right\"><input class=\"transnb_button\" type=\"image\" src=\"images/save.gif\" title=\"Save\"></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
		echo '<br>';
		
		// List
		echo "<table class=\"outer\" width=\"400px\">\n";
		echo "<tr>\n";
		echo "   <td class=\"gray_und\"><b>Category List</b></td>\n";
		echo "</tr>\n";

		$ccnt=0;
		while ($row = mssql_fetch_array($res))
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbg = 'wh';
			}
			else
			{
				$tbg = 'gray';
			}
			
			if (isset($row['usecid']) ||$row['usecid'] !=0)
			{
				$qryO = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['usecid']."';";
				$resO = mssql_query($qryO);
				$rowO  = mssql_fetch_array($resO);

				$ufname	=$rowO['fname'];
				$ulname	=$rowO['lname'];
				$udate	=date("m/d/Y",strtotime($row['updt']));
			}
			else
			{
				$ufname	="";
				$ulname	="";
				$udate	="";
			}
			
			echo "<form method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"cat\">\n";
			echo "<input type=\"hidden\" name=\"subq\" value=\"update\">\n";
			echo "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">\n";
			echo "<tr class=\"".$tbg."\">\n";
			echo "	<td>";
			echo "		<table width=\"100%\">";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>Category ID</b></td>\n";
			echo "				<td>".$row['catid']." ";
			
			if ($row['active']==0)
			{
				echo "<font color=\"red\"><b>(INACTIVE)</b></font>";
			}
			
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "			<tr>";
			echo "				<td align=\"right\"><b>Category Name</b></td>\n";
			echo "				<td><input class=\"bboxc\" type=\"text\" name=\"name\" value=\"".$row['name']."\" size=\"40\" maxlength=\"50\"></td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>QB ParentItem</b></td>\n";
			echo "				<td><input class=\"bboxc\" type=\"text\" name=\"ParentID\" value=\"".$row['ParentID']."\" size=\"40\" maxlength=\"40\"></td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>QB Account</b></td>\n";
			echo "				<td><input class=\"bboxc\" type=\"text\" name=\"AccountName\" value=\"".$row['AccountName']."\" size=\"40\" maxlength=\"40\"></td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>Active</b></td>\n";
			echo "				<td>\n";
			echo "					<select name=\"active\">\n";

			if ($row['active']==1)
			{
				echo "         <option value=\"1\" SELECTED>Yes</option>\n";
				echo "         <option value=\"0\">No</option>\n";
			}
			else
			{
				echo "         <option value=\"1\">Yes</option>\n";
				echo "         <option value=\"0\" SELECTED>No</option>\n";
			}

			echo "					</select>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>Private</b></td>\n";
			echo "				<td>\n";
			echo "					<select name=\"privcat\">\n";

			if ($row['privcat']==1)
			{
				echo "         <option value=\"1\" SELECTED>Yes</option>\n";
				echo "         <option value=\"0\">No</option>\n";
			}
			else
			{
				echo "         <option value=\"1\">Yes</option>\n";
				echo "         <option value=\"0\" SELECTED>No</option>\n";
			}

			echo "					</select>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "	<tr>\n";
			echo "			<td align=\"right\"><b>Sales Type</b></td>\n";
			echo "			<td>\n";
			echo "      		<select name=\"salestype\">\n";
			
			if ($row['salestype']==1)
			{
				echo "         <option value=\"1\" SELECTED>Other</option>\n";
				echo "         <option value=\"0\">Pool</option>\n";
			}
			else
			{
				echo "         		<option value=\"0\" SELECTED>Pool</option>\n";
				echo "         		<option value=\"1\">Other</option>\n";
			}
			
			echo "      		</select>\n";
			echo "			</td>\n";
			echo "	</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>Required</b></td>\n";
			echo "				<td> \n";
			echo "				<select name=\"irequired\">\n";

			if ($row['irequired']==1)
			{
				echo "         <option value=\"1\" SELECTED>Yes</option>\n";
				echo "         <option value=\"0\">No</option>\n";
			}
			else
			{
				echo "         <option value=\"1\">Yes</option>\n";
				echo "         <option value=\"0\" SELECTED>No</option>\n";
			}

			echo "					</select>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>Sequence</b></td>\n";
			echo "				<td><input class=\"bboxc\" type=\"text\" name=\"seqn\" value=\"".$row['seqn']."\" size=\"5\" maxlength=\"3\"></td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"></td>\n";
			echo "   			<td align=\"right\">Updated: ".$ufname." ".$ulname." on ".$udate." <input class=\"transnb_button\" type=\"image\" src=\"images/save.gif\" title=\"Save\"></td>\n";
			echo "			</tr>\n";
			echo "		</table>\n";
			echo "		</form>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			
			/*
			echo "<form method=\"post\">\n";
			echo "<tr>\n";
			echo "   	<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "   	<input type=\"hidden\" name=\"call\" value=\"cat\">\n";
			echo "   	<input type=\"hidden\" name=\"subq\" value=\"delete\">\n";
			echo "   	<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">\n";
			echo "   <td class=\"".$tbg."\" align=\"right\"><input class=\"transnb_button\" type=\"image\" src=\"images/delete.png\" title=\"Delete\"></td>\n";
			echo "</tr>\n";
			echo "</form>\n";
			*/
		}
		
		echo "</table>\n";
	}
}

function updatecat()
{
	$qry = "UPDATE AC_Cats SET name='".trim($_REQUEST['name'])."',AccountName='".trim($_REQUEST['AccountName'])."',ParentID='".trim($_REQUEST['ParentID'])."',active='".$_REQUEST['active']."',privcat='".$_REQUEST['privcat']."',salestype='".$_REQUEST['salestype']."',irequired='".$_REQUEST['irequired']."',seqn='".$_REQUEST['seqn']."',usecid='".$_SESSION['securityid']."',updt='".date("m/d/Y",time())."' WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
	$res = mssql_query($qry);
	//$row= mssql_num_rows($res);

	catlist();
}

function addcat()
{
	$qryA = "SELECT MAX(seqn) FROM AC_Cats WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);

	$seqn=$rowA[0]+1;

	$qryB = "SELECT MAX(catid) FROM AC_Cats WHERE officeid='".$_SESSION['officeid']."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$catid=$rowB[0]+1;

	$qryC  = "INSERT INTO AC_Cats (officeid,name,AccountName,ParentID,active,seqn,privcat,salestype,irequired,catid,usecid) VALUES ";
	$qryC .= "('".$_SESSION['officeid']."','".$_REQUEST['name']."','".$_REQUEST['AccountName']."','".$_REQUEST['ParentID']."','0','".$seqn."','".$_REQUEST['privcat']."','".$_REQUEST['salestype']."','".$_REQUEST['irequired']."','".$catid."','".$_SESSION['securityid']."');";
	$resC = mssql_query($qryC);

	catlist();
}

function add_package_item()
{
	$MAS=$_SESSION['pb_code'];
	$qry0 = "SELECT rid FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$_REQUEST['rid']."' AND iid='".$_REQUEST['iid']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		echo "Duplicate Package SubItem Found.";
	}
	else
	{
		$qry = "INSERT INTO [".$MAS."plinks] (officeid,rid,iid) VALUES ('".$_SESSION['officeid']."','".$_REQUEST['rid']."','".$_REQUEST['iid']."');";
		$res = mssql_query($qry);
	}

	accessory_edit($_REQUEST['retailid']);
}

function adjust_package_item()
{
	$MAS=$_SESSION['pb_code'];
	$qry = "UPDATE [".$MAS."plinks] SET adjtype='".$_REQUEST['adjtype']."',adjamt='".$_REQUEST['adjamt']."',adjquan='".$_REQUEST['adjquan']."' WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
	$res = mssql_query($qry);

	accessory_edit($_REQUEST['retailid']);
}

function del_package_item()
{
	$MAS=$_SESSION['pb_code'];
	$qry0 = "SELECT rid FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 < 1)
	{
		echo "<b>Package SubItem Not Found!</b>";
	}
	else
	{
		$qry = "DELETE FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
		$res = mssql_query($qry);
	}

	accessory_edit($_REQUEST['retailid']);
}

function add_labor_cost_item($rid,$cid)
{
	$MAS=$_SESSION['pb_code'];
	$qry0 = "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rid."' AND cid='".$cid."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		echo "The selected cost item is already a member of the current retail item.";
	}
	else
	{
		$qry1 = "INSERT INTO [".$MAS."rclinks_l] (officeid,rid,cid) VALUES ('".$_SESSION['officeid']."','".$rid."','".$cid."');";
		$res1 = mssql_query($qry1);

		accessory_edit($rid);
	}
}

function rem_labor_cost_item($rid,$cid)
{
	$MAS=$_SESSION['pb_code'];
	$qry1 = "DELETE FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rid."' AND cid='".$cid."';";
	$res1 = mssql_query($qry1);

	accessory_edit($rid);
}

function show_labor_cost_selects($phsid,$rid)
{
	$MAS=$_SESSION['pb_code'];
	$qry0 = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND baseitem!=1 ORDER by item ASC;";
	$res0 = mssql_query($qry0);

	//echo $qry0;
	$qry1 = "SELECT phsname FROM phasebase WHERE phsid='".$phsid."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	echo "<table class=\"outer\" border=0 align=\"center\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "	   <td class=\"ltgray_und\" align=\"left\"><b>Unrelated ".$row1['phsname']." Cost Items</b></td>\n";
	echo "	   <td class=\"ltgray_und\" align=\"right\">Base Cost</td>\n";
	echo "	   <td class=\"ltgray_und\" align=\"right\">&nbsp</td>\n";
	echo "	   <td class=\"ltgray_und\" align=\"right\">&nbsp</td>\n";
	echo "	</tr>\n";

	while ($row0 = mssql_fetch_array($res0))
	{
		$fbp=number_format($row0['bprice'], 2, '.', '');
		echo "   <tr>\n";
		echo "	   <td align=\"left\" class=\"wh_und\">".$row0['item']."</td>\n";
		echo "	   <td align=\"right\" class=\"wh_und\">".$fbp."</td>\n";
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"add_labor_cost_item\">\n";
		echo "<input type=\"hidden\" name=\"cid\" value=\"".$row0['id']."\">\n";
		echo "<input type=\"hidden\" name=\"rid\" value=\"".$rid."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
		
		if (isset($_REQUEST['catid']))
		{
			echo "<input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
		}
		
		echo "	   <td class=\"wh_und\" align=\"right\" width=\"25\">\n";
		//echo "         <button type=\"submit\">Select</button>\n";
		echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/add.png\" alt=\"Select\">\n";
		echo "      </td>\n";
		echo "</form>\n";
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
		echo "<input type=\"hidden\" name=\"id\" value=\"".$row0['id']."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
		
		if (isset($_REQUEST['catid']) && $_REQUEST['catid']!=0)
		{
			echo "<input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
		}
		
		echo "	   <td class=\"wh_und\" align=\"right\" width=\"25\">\n";
		//echo "         <button type=\"submit\">View</button>\n";
		echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
		echo "      </td>\n";
		echo "</form>\n";
		echo "	</tr>\n";
	}

	echo "</table>\n";
}

function show_package_selects($catid,$rid)
{
	$MAS=$_SESSION['pb_code'];
	$qry0 = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$catid."' ORDER by seqn ASC;";
	$res0 = mssql_query($qry0);

	/*$qry1 = "SELECT name FROM AC_cats WHERE catid='".$catid."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);*/

	echo "<table class=\"inner_borders\" width=\"100%\">\n";
	/*echo "   <tr>\n";
	echo "	   <td align=\"left\"><b>Unrelated ".$row1['name']." Package Items</b></td>\n";
	echo "	   <td align=\"right\"><b>Retail</b></td>\n";
	echo "	   <td align=\"right\">&nbsp</td>\n";
	echo "	   <td align=\"right\">&nbsp</td>\n";
	echo "	</tr>\n";*/

	while ($row0 = mssql_fetch_array($res0))
	{
		echo "   <tr>\n";

		if ($row0['qtype']==32)
		{
			echo "	   <td align=\"left\" class=\"wh_und\"><b>".$row0['item']."</b></td>\n";
			echo "	   <td align=\"right\" class=\"wh_und\"></td>\n";
			echo "	   <td align=\"right\" class=\"wh_und\"></td>\n";
		}
		else
		{
			echo "	   <td align=\"left\" class=\"wh_und\">".$row0['item']."</td>\n";
			echo "	   <td align=\"right\" class=\"wh_und\">".$row0['rp']."</td>\n";
			echo "<form method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
			echo "<input type=\"hidden\" name=\"subq\" value=\"add_package_item\">\n";
			echo "<input type=\"hidden\" name=\"iid\" value=\"".$row0['id']."\">\n";
			echo "<input type=\"hidden\" name=\"rid\" value=\"".$rid."\">\n";
			echo "<input type=\"hidden\" name=\"retailid\" value=\"".$rid."\">\n";
			echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "<input type=\"hidden\" name=\"catid\" value=\"".$catid."\">\n";
			echo "	   <td class=\"wh_und\" align=\"right\" width=\"25\">\n";
			//echo "         <button type=\"submit\">Select</button>\n";
			echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/add.png\" alt=\"Select\">\n";
			echo "      </td>\n";
			echo "</form>\n";
		}

		echo "	   <td class=\"wh_und\" align=\"right\" width=\"25\">\n";
		
		if ($row0['qtype']!=32)
		{
			echo "<form method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
			echo "<input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
			echo "<input type=\"hidden\" name=\"id\" value=\"".$row0['id']."\">\n";
			echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "<input type=\"hidden\" name=\"catsid\" value=\"".$catid."\">\n";
			echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
			echo "</form>\n";
		}
		
		echo "      </td>\n";
		echo "	</tr>\n";
	}

	echo "</table>\n";
}

function add_mat_cost_item($rid,$cid)
{
	$MAS=$_SESSION['pb_code'];
	$qry0 = "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rid."' AND cid='".$cid."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		echo "The selected cost item is already a member of the current retail item.";
	}
	else
	{
		$qry1 = "INSERT INTO [".$MAS."rclinks_m] (officeid,rid,cid) VALUES ('".$_SESSION['officeid']."','".$rid."','".$cid."');";
		$res1 = mssql_query($qry1);

		accessory_edit($rid);
	}
}

function rem_mat_cost_item($rid,$cid)
{
	$MAS=$_SESSION['pb_code'];
	$qry1 = "DELETE FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rid."' AND cid='".$cid."';";
	$res1 = mssql_query($qry1);

	accessory_edit($rid);
}

function show_mat_cost_selects($phsid,$rid)
{
	$MAS=$_SESSION['pb_code'];
	//ECHO "TEST";
	$qry0 = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND baseitem!=1 ORDER by item ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT phsname FROM phasebase WHERE phsid='".$phsid."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	echo "<table class=\"outer\" border=0 align=\"center\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "	   <td class=\"ltgray_und\" align=\"left\"><b>Unrelated ".$row1['phsname']." Cost Items</b></td>\n";
	echo "	   <td class=\"ltgray_und\" align=\"right\"><b>Base Cost</b></td>\n";
	echo "	   <td class=\"ltgray_und\" align=\"right\">&nbsp</td>\n";
	echo "	   <td class=\"ltgray_und\" align=\"right\">&nbsp</td>\n";
	echo "	</tr>\n";

	while ($row0 = mssql_fetch_array($res0))
	{
		if ($row0['matid']!=0)
		{
			$qry2 = "SELECT bp FROM material_master WHERE id='".$row0['matid']."';";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);

			$bp=$row2['bp'];
		}
		else
		{
			$bp=$row0['bprice'];
		}

		$fbp=number_format($bp, 2, '.', '');
		echo "   <tr>\n";
		echo "	   <td align=\"left\" class=\"wh_und\">".$row0['item']."</td>\n";
		echo "	   <td align=\"right\" class=\"wh_und\">".$fbp."</td>\n";
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"add_mat_cost_item\">\n";
		echo "<input type=\"hidden\" name=\"cid\" value=\"".$row0['invid']."\">\n";
		echo "<input type=\"hidden\" name=\"rid\" value=\"".$rid."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
		
		if (isset($_REQUEST['catid']))
		{
			echo "<input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
		}
		
		echo "	   <td class=\"wh_und\" align=\"right\" width=\"25\">\n";
		//echo "         <button type=\"submit\">Select</button>\n";
		echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/add.png\" alt=\"Select\">\n";
		echo "      </td>\n";
		echo "</form>\n";
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
		echo "<input type=\"hidden\" name=\"invid\" value=\"".$row0['invid']."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
		
		if (isset($_REQUEST['catid']))
		{
			echo "<input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
		}
		
		echo "	   <td class=\"wh_und\" align=\"right\" width=\"25\">\n";
		//echo "         <button type=\"submit\">View</button>\n";
		echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
		echo "      </td>\n";
		echo "</form>\n";
		echo "	</tr>\n";
	}

	echo "</table>\n";
}

function retail_cost_tie_display($retailid)
{
	//echo "XX";
	$MAS=$_SESSION['pb_code'];
	$qry0 = "SELECT phsid,rphsid,phsname FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY phsname ASC";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT phsid,rphsid,phsname FROM phasebase WHERE phstype='M' AND costing=1 ORDER BY phsname ASC";
	$res1 = mssql_query($qry1);

	$qry2  = "SELECT id,officeid,rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$retailid."' AND cid!='0' ORDER BY cid ASC;";
	$res2  = mssql_query($qry2);
	$nrow2 = mssql_num_rows($res2);

	//echo $qry2;
	if ($nrow2 > 0)
	{
		while ($row2 = mssql_fetch_array($res2))
		{
			$cidarr_l[]=$row2['cid'];
		}
	}

	$qry4  = "SELECT id,officeid,rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$retailid."' AND cid!='0' ORDER BY cid ASC;";
	$res4  = mssql_query($qry4);
	$nrow4 = mssql_num_rows($res4);

	if ($nrow4 > 0)
	{
		while ($row4 = mssql_fetch_array($res4))
		{
			$cidarr_m[]=$row4['cid'];
		}
	}

	echo "<table class=\"outer\" width=\"100%\">\n";

	if ($nrow2 > 0 || $nrow4 > 0)
	{
		echo "<tr>\n";
		echo "   <td valign=\"top\" >\n";
		echo "      <table width=\"100%\" border=0>\n";
		echo "      <tr>\n";
		echo "	      <td class=\"ltgray_und\" align=\"left\"><b>Related Cost Items</b></td>\n";
		echo "	      <td class=\"ltgray_und\" align=\"left\"><b>Phase</b></td>\n";
		echo "	      <td class=\"ltgray_und\" align=\"right\"><b>Base Cost</b></td>\n";
		echo "	      <td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "	      <td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "	   </tr>\n";

		//show_array_vars($cidarr_l);
		//show_array_vars($cidarr_m);

		if ($nrow2 > 0)
		{
			foreach ($cidarr_l as $n1 => $v1)
			{
				$qry3 = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$v1."'";
				$res3 = mssql_query($qry3);
				$row3 = mssql_fetch_array($res3);

				//echo $qry3."<br>";

				$qry6 = "SELECT phsname FROM phasebase WHERE phsid='".$row3['phsid']."'";
				$res6 = mssql_query($qry6);
				$row6 = mssql_fetch_array($res6);

				$fbp=number_format($row3['bprice'], 2, '.', '');

				echo "      <tr>\n";
				echo "	      <td class=\"wh_und\" align=\"left\">".$row3['item']."</td>\n";
				echo "	      <td class=\"wh_und\" align=\"left\">".$row6['phsname']."</td>\n";
				echo "	      <td class=\"wh_und\" align=\"right\">".$fbp."</td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
					echo "<input type=\"hidden\" name=\"subq\" value=\"rem_labor_cost_item\">\n";
					echo "<input type=\"hidden\" name=\"rid\" value=\"".$retailid."\">\n";
					echo "<input type=\"hidden\" name=\"cid\" value=\"".$row3['id']."\">\n";
					echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "<input type=\"hidden\" name=\"phsid\" value=\"".$row3['phsid']."\">\n";
					
					if (isset($_REQUEST['catid']))
					{
						echo "<input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
					}
				}

				echo "	   <td class=\"wh_und\" align=\"center\" width=\"25\">\n";

				if ($_SESSION['m_plev'] >=8)
				{
					//echo "         <button type=\"submit\">Delete</button>\n";
					echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/action_delete.gif\" alt=\"Delete\">\n";
				}

				echo "      </td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "</form>\n";
					echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
					echo "<input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
					echo "<input type=\"hidden\" name=\"id\" value=\"".$row3['id']."\">\n";
					echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "<input type=\"hidden\" name=\"phsid\" value=\"".$row3['phsid']."\">\n";
					
					if (isset($_REQUEST['catid']) && $_REQUEST['catid']!=0)
					{
						echo "<input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
					}
				}

				echo "	      <td class=\"wh_und\" align=\"center\" width=\"25\">\n";

				if ($_SESSION['m_plev'] >=8)
				{
					//echo "            <button type=\"submit\">View</button>\n";
					echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
				}

				echo "         </td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "</form>\n";
				}

				echo "	   </tr>\n";
				$sumarr_l[]=$row3['bprice'];
			}
		}
		else
		{
			$sumarr_l=array(0=>0);
		}

		if ($nrow4 > 0)
		{
			foreach ($cidarr_m as $n2 => $v2)
			{
				$qry5 = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$v2."'";
				$res5 = mssql_query($qry5);
				$row5 = mssql_fetch_array($res5);
				//echo $qry5."<BR>";

				if ($row5['matid']!=0)
				{
					$qry6 = "SELECT bp FROM material_master WHERE id='".$row5['matid']."'";
					$res6 = mssql_query($qry6);
					$row6 = mssql_fetch_array($res6);

					$bp=$row6['bp'];
				}
				else
				{
					$bp=$row5['bprice'];
				}

				$fbp=number_format($bp, 2, '.', '');

				$qry7 = "SELECT phsname FROM phasebase WHERE phsid='".$row5['phsid']."'";
				$res7 = mssql_query($qry7);
				$row7 = mssql_fetch_array($res7);

				echo "      <tr>\n";
				echo "	      <td class=\"wh_und\" align=\"left\">".$row5['item']."</td>\n";
				echo "	      <td class=\"wh_und\" align=\"left\">".$row7['phsname']."</td>\n";
				echo "	      <td class=\"wh_und\" align=\"right\">".$fbp."</td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
					echo "<input type=\"hidden\" name=\"subq\" value=\"rem_mat_cost_item\">\n";
					echo "<input type=\"hidden\" name=\"rid\" value=\"".$retailid."\">\n";
					echo "<input type=\"hidden\" name=\"cid\" value=\"".$row5['invid']."\">\n";
					echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "<input type=\"hidden\" name=\"phsid\" value=\"".$row5['phsid']."\">\n";
				}
				echo "	   <td class=\"wh_und\" align=\"center\" width=\"25\">\n";

				if ($_SESSION['m_plev'] >=8)
				{
					//echo "         <button type=\"submit\">Delete</button>\n";
					echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/action_delete.gif\" alt=\"Delete\">\n";
				}

				echo "      </td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "</form>\n";
					echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "<input type=\"hidden\" name=\"call\" value=\"inv\">\n";
					echo "<input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
					echo "<input type=\"hidden\" name=\"invid\" value=\"".$row5['invid']."\">\n";
					echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "<input type=\"hidden\" name=\"phsid\" value=\"".$row5['phsid']."\">\n";
				}
				echo "	      <td class=\"wh_und\" align=\"center\" width=\"25\">\n";

				if ($_SESSION['m_plev'] >=8)
				{
					//echo "            <button type=\"submit\">View</button>\n";
					echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
				}

				echo "         </td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "</form>\n";
				}

				echo "	   </tr>\n";
				$sumarr_m[]=$row5['bprice'];
			}
		}
		else
		{
			$sumarr_m=array(0=>0);
		}

		if ($sumarr_l!=0||$sumarr_m!=0)
		{
			$costsum=array_sum($sumarr_l)+array_sum($sumarr_m);
			$fcostsum =number_format($costsum, 2, '.', '');
		}

		echo "      </table>\n";
		echo "   </td>\n";
		echo "</tr>\n";
	}

	if ($_SESSION['m_plev'] >=8)
	{
		echo "<tr>\n";
		echo "   <td colspan=\"3\" valign=\"top\" align=\"right\">\n";
		echo "      <table width=\"100%\" border=0>\n";
		echo "         <tr>\n";
		echo "            <td bgcolor=\"yellow\" colspan=\"2\" valign=\"top\" align=\"left\"><b>Show Unrelated Cost Items</b></td>\n";
		echo "         </tr>\n";
		echo "         <tr>\n";
		echo "         <form method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"list_labor_cost\">\n";
		echo "         <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "         <input type=\"hidden\" name=\"id\" value=\"".$retailid."\">\n";
		echo "         <input type=\"hidden\" name=\"retid\" value=\"".$retailid."\">\n";
		
		if (isset($_REQUEST['catid']))
		{
			echo "<input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
		}
		
		echo "            <td bgcolor=\"yellow\" valign=\"top\" align=\"left\">\n";
		echo "               <select name=\"lmphsid\" onChange=\"this.form.submit();\">\n";
		echo "                  <option value=\"0\">Select Labor...</option>\n";
		echo "                  <option value=\"0\">None</option>\n";

		while($row0 = mssql_fetch_array($res0))
		{
			if (isset($_REQUEST['lmphsid']) && $_REQUEST['lmphsid']==$row0['phsid'])
			{
				echo "                  <option value=\"".$row0['phsid']."\" SELECTED>".$row0['phsname']."</option>\n";
			}
			else
			{
				echo "                  <option value=\"".$row0['phsid']."\">".$row0['phsname']."</option>\n";
			}
		}

		echo "               </select>\n";
		//echo "               <button type=\"submit\">Labor</button>\n";
		echo "            </td>\n";
		echo "          </form>\n";
		echo "         <form method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"list_mat_cost\">\n";
		echo "         <input type=\"hidden\" name=\"invid\" value=\"".$retailid."\">\n";
		echo "         <input type=\"hidden\" name=\"retid\" value=\"".$retailid."\">\n";
		echo "         <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		
		if (isset($_REQUEST['catid']))
		{
			echo "<input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
		}
		
		echo "            <td bgcolor=\"yellow\" valign=\"top\" align=\"right\">\n";
		echo "               <select name=\"lmphsid\" onChange=\"this.form.submit();\">\n";
		echo "                  <option value=\"0\">Select Material...</option>\n";
		echo "                  <option value=\"0\">None</option>\n";

		while($row1 = mssql_fetch_array($res1))
		{
			if (isset($_REQUEST['lmphsid']) && $_REQUEST['lmphsid']==$row1['phsid'])
			{
				echo "                  <option value=\"".$row1['phsid']."\" SELECTED>".$row1['phsname']."</option>\n";
			}
			else
			{
				echo "                  <option value=\"".$row1['phsid']."\">".$row1['phsname']."</option>\n";
			}
			//echo "                  <option value=\"".$row1['phsid']."\">".$row1['phsname']."</option>\n";
		}

		echo "               </select>\n";
		//echo "               <button type=\"submit\">Material</button>\n";
		echo "            </td>\n";
		echo "         </form>\n";
		echo "         </tr>\n";
		echo "      </table>\n";
		echo "   </td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";

	if (!empty($_REQUEST['subq']))
	{
		$subq=$_REQUEST['subq'];
	}
	else
	{
		$subq="";
	}

	if ($subq=="list_labor_cost")
	{
		show_labor_cost_selects($_REQUEST['lmphsid'],$retailid);
		//echo "TEST";
	}
	elseif ($subq=="list_mat_cost")
	{
		show_mat_cost_selects($_REQUEST['lmphsid'],$retailid);
		//echo "TEST";
	}
}

function retail_package_tie_display($retailid)
{
	$MAS=$_SESSION['pb_code'];
	$qry0 = "SELECT id,officeid,catid,name FROM AC_Cats WHERE officeid='".$_SESSION['officeid']."' AND active='1' ORDER BY seqn ASC";
	$res0 = mssql_query($qry0);

	$qry2  = "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$retailid."' AND iid!='0' ORDER BY iid ASC;";
	$res2  = mssql_query($qry2);
	//echo $qry2;
	$nrow2 = mssql_num_rows($res2);

	echo "<table class=\"outer\" border=0 align=\"center\" width=\"100%\">\n";

	if ($nrow2 > 0)
	{
		echo "<tr>\n";
		echo "   <td >\n";
		echo "      <table width=\"100%\" border=0>\n";
		echo "      <tr>\n";
		echo "	      <td class=\"gray\" align=\"left\" colspan=\"8\"><b>Related Package Item</b></td>\n";
		echo "      </tr>\n";
		echo "      <tr>\n";
		echo "	      <td class=\"ltgray_und\" align=\"center\">Category</td>\n";
		echo "	      <td class=\"ltgray_und\" align=\"center\">Std Price</td>\n";
		echo "	      <td class=\"ltgray_und\" align=\"center\">Adjust Type</td>\n";
		echo "	      <td class=\"ltgray_und\" align=\"center\">Price Adjust</td>\n";
		echo "	      <td class=\"ltgray_und\" align=\"center\">Quan Adjust</td>\n";
		echo "	      <td class=\"ltgray_und\" align=\"right\" colspan=\"3\"></td>\n";
		echo "	   </tr>\n";

		if ($nrow2 > 0)
		{
			while ($row2 = mssql_fetch_array($res2))
			{
				$qry3 = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row2['iid']."'";
				$res3 = mssql_query($qry3);
				$row3 = mssql_fetch_array($res3);

				$qry6 = "SELECT name FROM AC_Cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row3['catid']."'";
				$res6 = mssql_query($qry6);
				$row6 = mssql_fetch_array($res6);

				$qry7 = "SELECT id,adjid,name FROM atypes WHERE active=1 ORDER BY adjid ASC;";
				$res7 = mssql_query($qry7);

				echo "      <tr>\n";
				echo "	      <td colspan=\"8\" bgcolor=\"white\" valign=\"bottom\"><b>".$row3['item']."</b></td>\n";
				echo "      </tr>\n";
				echo "      <tr>\n";
				echo "	      <td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$row6['name']."</td>\n";
				echo "	      <td class=\"wh_und\" align=\"right\" valign=\"bottom\">".$row3['rp']."</td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				}

				echo "	      <td class=\"wh_und\" align=\"right\" valign=\"bottom\">\n";
				echo "            <select name=\"adjtype\">\n";

				while ($row7 = mssql_fetch_array($res7))
				{
					if ($row7['adjid']==$row2['adjtype'])
					{
						echo "               <option value=\"".$row7['adjid']."\" SELECTED>".$row7['name']."</option>\n";
					}
					else
					{
						echo "               <option value=\"".$row7['adjid']."\">".$row7['name']."</option>\n";
					}
				}

				echo "            </select>\n";
				echo "         </td>\n";
				echo "	      <td class=\"wh_und\" align=\"right\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" name=\"adjamt\" value=\"".$row2['adjamt']."\" size=\"5\"></td>\n";
				echo "	      <td class=\"wh_und\" align=\"right\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" name=\"adjquan\" value=\"".$row2['adjquan']."\" size=\"3\"></td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
					echo "<input type=\"hidden\" name=\"subq\" value=\"adj_package\">\n";
					echo "<input type=\"hidden\" name=\"id\" value=\"".$row2['id']."\">\n";
					echo "<input type=\"hidden\" name=\"retailid\" value=\"".$retailid."\">\n";
					echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
				}

				echo "	   <td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"25\">\n";

				if ($_SESSION['m_plev'] >=8)
				{
					//echo "         <button type=\"submit\">Update</button>\n";
					echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/save.gif\" alt=\"Update\">\n";
				}

				echo "      </td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "</form>\n";
					echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
					echo "<input type=\"hidden\" name=\"subq\" value=\"del_package_item\">\n";
					echo "<input type=\"hidden\" name=\"id\" value=\"".$row2['id']."\">\n";
					echo "<input type=\"hidden\" name=\"retailid\" value=\"".$retailid."\">\n";
					echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
				}

				echo "	   <td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"25\">\n";

				if ($_SESSION['m_plev'] >=8)
				{
					//echo "         <button type=\"submit\">Delete</button>\n";
					echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/action_delete.gif\" alt=\"Delete\">\n";
				}

				echo "      </td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "</form>\n";
					echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
					echo "<input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
					echo "<input type=\"hidden\" name=\"id\" value=\"".$row3['id']."\">\n";
					echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "<input type=\"hidden\" name=\"catid\" value=\"".$row3['catid']."\">\n";
				}

				echo "	      <td class=\"wh_und\" align=\"right\" valign=\"bottom\" width=\"25\">\n";

				if ($_SESSION['m_plev'] >=8)
				{
					//echo "            <button type=\"submit\">View</button>\n";
					echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
				}

				echo "         </td>\n";

				if ($_SESSION['m_plev'] >=8)
				{
					echo "</form>\n";
				}

				echo "	   </tr>\n";
			}
		}
	}

	if ($_SESSION['m_plev'] >=8)
	{
		echo "<tr>\n";
		echo "   <td class=\"gray\" colspan=\"9\" valign=\"top\" align=\"right\">\n";
		echo "      <table width=\"100%\" border=0>\n";
		echo "         <tr>\n";
		echo "         <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"list_package_selects\">\n";
		echo "         <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "         <input type=\"hidden\" name=\"id\" value=\"".$retailid."\">\n";
		echo "         <input type=\"hidden\" name=\"retid\" value=\"".$retailid."\">\n";
		
		if (isset($_REQUEST['catid']))
		{
			echo "<input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
		}
		
		echo "            <td bgcolor=\"yellow\" align=\"left\"><b>Unrelated Package Items:</b></td>\n";
		echo "            <td bgcolor=\"yellow\" align=\"right\"> \n";
		echo "               <select name=\"uncatid\" onChange=\"this.form.submit();\">\n";
		echo "                  <option value=\"0\" SELECTED>None</option>\n";

		while($row0 = mssql_fetch_array($res0))
		{
			if (isset($_REQUEST['uncatid']) && $_REQUEST['uncatid']==$row0['catid'])
			{
				echo "                  <option value=\"".$row0['catid']."\" SELECTED>".$row0['name']."</option>\n";
			}
			else
			{
				echo "                  <option value=\"".$row0['catid']."\">".$row0['name']."</option>\n";
			}
			
		}

		echo "               </select>\n";
		//echo "               <button type=\"submit\">List</button>\n";
		echo "            </td>\n";
		echo "          </form>\n";
		echo "         </tr>\n";
		echo "      </table>\n";
		echo "   </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "   <td class=\"gray\" colspan=\"9\" align=\"right\">\n";
		//echo "      <table width=\"100%\" border=0>\n";
		//echo "         <tr>\n";
		//echo "            <td>\n";

		if (!empty($_REQUEST['subq']))
		{
			$subq=$_REQUEST['subq'];
		}
		else
		{
			$subq="";
		}

		if ($subq=="list_package_selects")
		{
			show_package_selects($_REQUEST['uncatid'],$retailid);
		}

		//echo "            </td>\n";
		//echo "         </tr>\n";
		//echo "      </table>\n";
		echo "   </td>\n";
		echo "</tr>\n";
	}

	echo "</table>\n";
}

function costing_maint_submenu()
{
	//echo "PASSTHRU";
}

function costing_trav_maint_subsys()
{
	$officeid=$_SESSION['officeid'];

	if (isset($_REQUEST['phsid']))
	{
		$phsid=$_REQUEST['phsid'];
	}
	else
	{
		$phsid=$_GET['phsid'];
	}

	if (isset($_REQUEST['id']))
	{
		$id=$_REQUEST['id'];
	}
	elseif (isset($_GET['id']))
	{
		$id=$_GET['id'];
	}
	else
	{
		$id="";
	}

	if (isset($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	elseif (isset($_GET['order']))
	{
		$order=$_GET['order'];
	}
	else
	{
		$order="invid";
	}

	$qry = "SELECT name FROM offices WHERE officeid='$officeid';";
	$res = mssql_query($qryA);
	$row = mssql_fetch_row($resA);

	$qryB = "SELECT phsid,phscode,phsname FROM phasebase WHERE phsid='$phsid';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryC = "SELECT SUM(bprice) FROM zcharge WHERE officeid='$officeid' AND phsid='$phsid' AND baseitem='T';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD   = "SELECT * FROM zcharge WHERE officeid='$officeid' AND phsid='$phsid' ORDER BY accid;";
	$resD   = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);
	/*
	$qry    = "SELECT phsid,phsname,phstype,phscode FROM phasebase WHERE phstype='M' ORDER BY phsname;";
	$res    = mssql_query($qry);

	$qryA   = "SELECT name FROM offices WHERE officeid=$officeid;";
	$resA   = mssql_query($qryA);
	$rowA   = mssql_fetch_row($resA);

	$qryB   = "SELECT phsid,phscode,phsname FROM phasebase WHERE phsid=$phsid;";
	$resB   = mssql_query($qryB);
	$rowB   = mssql_fetch_row($resB);

	$qryD   = "SELECT invid FROM inventory WHERE officeid=$officeid AND phsid=$phsid ORDER BY invid;";
	$resD   = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);
	*/
	echo "<table align=\"center\" width=\"700px\">\n";
	echo "<tr>\n";
	echo "   <th align=\"left\">Inventory Code Maintenance for $rowA[0]</th>\n";
	echo "            <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "            <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "            <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
	echo "            <input type=\"hidden\" name=\"subq\" value=\"add\">\n";
	echo "            <input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
	echo "            <input type=\"hidden\" name=\"phsid\" value=\"$phsid\">\n";
	echo "   <th align=\"right\">\n";
	echo "   	<button type=\"submit\">Add New Item</button>\n";
	//<a href=\"".$_SERVER['PHP_SELF']."?action=pbconfig&call=inv&subq=add&officeid=$officeid&phsid=$phsid\" method=\"post\" class=\"link\"><b>Add New Code</b></a>
	echo "	</th>\n";
	echo "				</form>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td  colspan=\"2\">\n";
	echo "      <table width=\"100%\">\n";
	echo "         <tr>\n";
	echo "            <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "            <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "            <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
	echo "            <input type=\"hidden\" name=\"subq\" value=\"inv\">\n";
	echo "            <td colspan=\"2\" ><b>Description: </b>\n";
	echo "               <select name=\"phsid\" OnChange=\"this.form.submit();\">\n";

	while($row = mssql_fetch_row($res))
	{
		if ($rowB[0]==$row[0])
		{
			echo "                  <option value=\"$rowB[0]\" SELECTED>$rowB[1] - $rowB[2]</option>\n";
		}
		else
		{
			echo "                  <option value=\"$row[0]\">$row[3] - $row[1]</option>\n";
		}
	}
	echo "               </select>\n";
	echo "             </form>\n";
	echo "             </td>\n";
	echo "         </tr>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"2\" >\n";

	if (isset($_SESSION['subq'])) {
		echo "   <table>\n";
		echo "      <tr>\n";
		echo "         <td >\n";

		if ($_SESSION['subq']=='add')
		{
			zcadd($phsid);
		}
		elseif ($_SESSION['subq']=='ins')
		{
			echo "<b>Adding...</b>";
			zcins();
		}
		elseif ($_SESSION['subq']=='del')
		{
			zcdel($invid,$phsid);
		}
		elseif ($_SESSION['subq']=='del2')
		{
			echo "<b>Deleting...</b>";
			zcdel2($invid,$phsid);
		}
		elseif ($_SESSION['subq']=='ed')
		{
			zced($invid,$phsid);
		}
		elseif ($_SESSION['subq']=='ed2')
		{
			echo "<b>Updating...</b>";
			zcupd();
		}
		elseif ($_SESSION['subq']=='reseq')
		{
			echo "<b>Sequencing Codes...</b>";
			renumseqzc($phsid);
		}
		else {
		}

		echo "         </td>\n";
		echo "      </tr>\n";
		echo "   </table>\n";
	}
	echo "   </td>\n";
	echo "</tr>\n";

	if ($nrowsD > 0)
	{
		echo "<tr>\n";
		echo "   <td  colspan=\"2\">\n";
		echo "      <table class=\"inner_borders\" width=\"100%\">\n";

		invlist($phsid,$order);

		echo "      </table>\n";
		echo "   </td>\n";
		echo "</tr>\n";
	}
}

function costing_inv_maint_subsys()
{
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	
	$officeid=$_SESSION['officeid'];
	$MAS=$_SESSION['pb_code'];
	$eqpphs=39;

	if (isset($_REQUEST['phsid']))
	{
		$phsid=$_REQUEST['phsid'];
	}
	else
	{
		$phsid=$_GET['phsid'];
	}

	if (isset($_REQUEST['invid']))
	{
		$invid=$_REQUEST['invid'];
	}
	elseif (isset($_GET['invid']))
	{
		$invid=$_GET['invid'];
	}
	else
	{
		$invid="";
	}

	if (isset($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	elseif (isset($_GET['order']))
	{
		$order=$_GET['order'];
	}
	else
	{
		$order="invid";
	}

	$qry    = "SELECT phsid,phsname,phstype,phscode FROM phasebase WHERE phstype='M' ORDER BY phscode;";
	$res    = mssql_query($qry);

	$qryA   = "SELECT name,accountingsystem,enmas,enquickbooks FROM offices WHERE officeid=".$officeid.";";
	$resA   = mssql_query($qryA);
	$rowA   = mssql_fetch_row($resA);

	$qryB   = "SELECT phsid,phscode,phsname,rphsid FROM phasebase WHERE phsid='".$phsid."';";
	$resB   = mssql_query($qryB);
	$rowB   = mssql_fetch_row($resB);

	$qryC   = "SELECT phsid,phscode,phsname,rphsid FROM phasebase WHERE phsid='".$rowB[3]."';";
	$resC   = mssql_query($qryC);
	$rowC   = mssql_fetch_row($resC);

	$qryD   = "SELECT invid FROM [".$MAS."inventory] WHERE officeid='".$officeid."' AND phsid='".$phsid."' ORDER BY invid;";
	$resD   = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);
	
	if (isset($rowA[3]) and $rowA[3] == 1)
	{
		// NonInventoryItem/Material
		$qryF1   = "
			SELECT
				I.invid
			FROM
				[".$MAS."inventory] AS I
			inner join
				phasebase as P
			on
				I.phsid=P.phsid
			WHERE
				I.officeid=".$officeid."
				and P.qb_inventory_phs=0
				and I.ListID='0';";
		$resF1   = mssql_query($qryF1);
		$nrowsF1 = mssql_num_rows($resF1);
		
		//$nrowsF1=0;
		
		//$qryF2   = "SELECT I.invid FROM [".$MAS."inventory] AS I inner join phasebase as P on I.phsid=P.phsid WHERE I.officeid=".$officeid." and P.qb_inventory_phs=1 and I.matid!=0 and I.ListID = '0';";
		//$qryF2   = "SELECT invid FROM [".$MAS."inventory] WHERE officeid=".$officeid." and matid!=0 and ListID = '0';";
		
		
		// InventoryItem/Inventory
		$qryF2 = "
			SELECT 
				I.invid
			FROM 
				[".$MAS."inventory] AS I 
			inner join 
				phasebase as P 
			on 
				I.phsid=P.phsid 
			WHERE 
				I.officeid=".$officeid." 
				and P.qb_inventory_phs=1 
				and I.ListID='0';
		";
		
		$resF2   = mssql_query($qryF2);
		$nrowsF2 = mssql_num_rows($resF2);
		
		if ($_SESSION['securityid']==269999999999999999999999999999999999999999999)
		{
			echo $qryF1.'<br>';
			echo $qryF2.'<br>';
		}
	}

	echo "<script type=\"text/javascript\" src=\"js/jquery_costing_maint_func.js\"></script>\n";
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\" align=\"left\"><b>".$rowA[0]." Inventory Cost Item</b></td>\n";
	echo "		<td class=\"gray\" align=\"right\"><b>Phase</b></td>\n";
	echo "		<td class=\"gray\" align=\"left\">\n";
	echo "			<form method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"inv\">\n";
	echo "			<input type=\"hidden\" name=\"subq\" value=\"inv\">\n";
	echo "			<select name=\"phsid\" onChange=\"this.form.submit();\">\n";

	while($row = mssql_fetch_row($res))
	{
		if ($rowB[0]==$row[0])
		{
			echo "                  <option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]." - ".$rowB[2]."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$row[0]."\">".$row[3]." - ".$row[1]."</option>\n";
		}
	}
	
	echo "			</select>\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "		<td align=\"right\" class=\"gray\">\n";
	echo "      <form method=\"post\">\n";
	echo "      <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "      <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
	echo "      <input type=\"hidden\" name=\"officeid\" id=\"usr_oid\" value=\"".$officeid."\">\n";
	echo "      <input type=\"hidden\" name=\"phsid\" id=\"phsid\" value=\"".$phsid."\">\n";
	echo "      <input type=\"hidden\" name=\"retid\" value=\"0\">\n";
	echo "      <input type=\"hidden\" name=\"qtype\" value=\"0\">\n";
	echo "      <input type=\"hidden\" name=\"mtype\" value=\"0\">\n";
	echo "      <select name=\"subq\" onChange=\"this.form.submit();\">\n";
	echo "         <option value=\"add_mm1\">Add New from...</option>\n";
	echo "         <option value=\"add_mm1\">Material List</option>\n";
	echo "         <option value=\"add\">Blank Input</option>\n";
	echo "      </select>\n";
	echo "      </form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	
	if ((isset($nrowsF1) and $nrowsF1 > 0) or (isset($nrowsF2) and $nrowsF2 > 0))
	{
		echo "<br>\n";
		echo "	<table class=\"outer\" width=\"950px\">\n";
		
		if (isset($nrowsF1) and $nrowsF1 > 0)
		{
			echo "		<tr>\n";
			echo "			<td align=\"left\">\n";
			echo "<input type=\"hidden\" id=\"usr_pactionM\" value=\"ItemNonInventoryAdd\">\n";
			echo "	<table width=\"500px\">\n";
			echo "		<tr>\n";
			echo "			<td align=\"left\"><b><font color=\"red\">NOTICE!</font> There are ".$nrowsF1." Material Cost Item(s) in the Pricebook that have not been synchronized with Quickbooks.<br>Until these items are synchronized, Quickbooks may not be able to properly accept Estimate/Job data from the JMS.</b></td>\n";
			echo "			<td valign=\"top\"><button id=\"SyncMaterialItems\">Synchronize <img src=\"images/arrow_refresh.png\"></button></td>\n";
			echo "		</tr>\n";
			echo "	</table>\n";
			echo "			</td>\n";
			echo "			<td align=\"left\" rowspan=\"2\" valign=\"top\"><div id=\"textbox_CostConfigStatus\"></div></td>\n";
			echo "		</tr>\n";
		}
		
		if (isset($nrowsF2) and $nrowsF2 > 0)
		{
			echo "		<tr>\n";
			echo "			<td align=\"left\">\n";
			echo "<input type=\"hidden\" id=\"usr_pactionI\" value=\"ItemInventoryAdd\">\n";
			echo "	<table width=\"500px\">\n";
			echo "		<tr>\n";
			echo "			<td align=\"left\"><b><font color=\"red\">NOTICE!</font> There are ".$nrowsF2." Inventory Cost Item(s) in the Pricebook that have not been synchronized with Quickbooks.<br>Until these items are synchronized, Quickbooks may not be able to properly accept Estimate/Job data from the JMS.</b></td>\n";
			echo "			<td valign=\"top\"><button id=\"SyncInventoryItems\">Synchronize <img src=\"images/arrow_refresh.png\"></button></td>\n";
			echo "		</tr>\n";
			echo "	</table>\n";
			echo "			</td>\n";
			echo "		</tr>\n";
		}
		
		echo "	</table>\n";
	}

	if (isset($_SESSION['subq']))
	{
		echo "<br>\n";

		if ($_SESSION['subq']=='add')
		{
			invadd($phsid);
		}
		elseif ($_SESSION['subq']=='add_mm1')
		{
			//echo "<b>Adding...</b>";
			invadd_mm1();
		}
		elseif ($_SESSION['subq']=='add_mm2')
		{
			//echo "<b>Adding...</b>";
			invadd_mm2($_REQUEST['cat']);
		}
		elseif ($_SESSION['subq']=='ins')
		{
			//echo "<b>Adding...</b>";
			invins();
		}
		elseif ($_SESSION['subq']=='del')
		{
			invdel($invid,$phsid);
		}
		elseif ($_SESSION['subq']=='del2')
		{
			//echo "<b>Deleting...</b>";
			invdel2($invid,$phsid);
		}
		elseif ($_SESSION['subq']=='ed')
		{
			//echo "ED";
			inved($invid,$phsid);
		}
		elseif ($_SESSION['subq']=='ed2')
		{
			//echo "<b>Updating...</b>";
			invupd();
		}
		elseif ($_SESSION['subq']=='edbp')
		{
			//echo "<b>Updating...</b>";
			invupdbp();
		}
		elseif ($_SESSION['subq']=='reseq')
		{
			//echo "<b>Sequencing Codes...</b>";
			renumseqinv($phsid);
		}
	}

	if ($nrowsD > 0)
	{
		echo "<br>\n";

		invlist($phsid,$order);
	}
}

function costing_acc_maint_subsys()
{
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];

	if (isset($_REQUEST['phsid']))
	{
		$phsid=$_REQUEST['phsid'];
	}
	else
	{
		$phsid=$_GET['phsid'];
	}

	if (isset($_REQUEST['id']))
	{
		$id=$_REQUEST['id'];
	}
	elseif (isset($_GET['id']))
	{
		$id=$_GET['id'];
	}
	else
	{
		$id="";
	}

	if (isset($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	elseif (isset($_GET['order']))
	{
		$order=$_GET['order'];
	}
	else
	{
		$order="accid";
	}

	$qry    = "SELECT phsid,phsname,phstype,phscode FROM phasebase WHERE phstype!='M' ORDER BY phscode;";
	$res    = mssql_query($qry);

	$qryA   = "SELECT name,accountingsystem,enmas,enquickbooks FROM offices WHERE officeid=".$officeid.";";
	$resA   = mssql_query($qryA);
	$rowA   = mssql_fetch_row($resA);

	$qryB   = "SELECT phsid,phscode,phsname,rphsid FROM phasebase WHERE phsid='".$phsid."';";
	$resB   = mssql_query($qryB);
	$rowB   = mssql_fetch_row($resB);

	$qryC   = "SELECT phsid,phscode,phsname,rphsid FROM phasebase WHERE phsid='".$rowB[3]."';";
	$resC   = mssql_query($qryC);
	$rowC   = mssql_fetch_row($resC);

	$qryD   = "SELECT id FROM [".$MAS."accpbook] WHERE officeid='".$officeid."' AND phsid='".$phsid."' ORDER BY accid;";
	$resD   = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);
	
	if (isset($rowA[3]) and $rowA[3] == 1)
	{
		//echo 'HIT';
		$qryF   = "SELECT id FROM [".$MAS."accpbook] WHERE officeid=".$officeid." and ListID = '0';";
		$resF   = mssql_query($qryF);
		$nrowsF = mssql_num_rows($resF);
		
		//echo $qryF.'<br>';
	}

	echo "<script type=\"text/javascript\" src=\"js/jquery_costing_maint_func.js\"></script>\n";
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\" colspan=\"2\">\n";
	echo "      	<table width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\"><b>".$rowA[0]." Labor Cost Maintenance</b></td>\n";
	echo "            		<td class=\"gray\" align=\"right\"><b>Phase</b></td>\n";
	echo "            		<td class=\"gray\" align=\"left\">\n";
	echo "						<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"acc\">\n";
	echo "						<select name=\"phsid\" id=\"usr_phsid\" OnChange=\"this.form.submit();\">\n";

	while($row = mssql_fetch_row($res))
	{
		if ($rowB[0]==$row[0])
		{
			echo "							<option value=\"$rowB[0]\" SELECTED>$rowB[1] - $rowB[2]</option>\n";
		}
		else
		{
			echo "							<option value=\"$row[0]\">$row[3] - $row[1]</option>\n";
		}
	}
	echo "						</select>\n";
	echo "						</form>\n";
	echo "					</td>\n";
	echo "					<td class=\"gray\" align=\"right\">\n";
	echo "						<form method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"add\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" id=\"usr_oid\" value=\"".$officeid."\">\n";
	echo "						<input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
	echo "						<input class=\"transnb_button\" type=\"image\" src=\"images/add.png\" title=\"Add Cost Item\">\n";
	echo "						</form>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "<table>\n";

	if ((isset($nrowsF) and $nrowsF > 0))
	{
		echo "<br>\n";
		echo "<input type=\"hidden\" id=\"usr_paction\" value=\"ItemServiceAdd\">\n";
		echo "<table class=\"outer\" width=\"950px\">\n";
		echo "		<tr>\n";
		echo "			<td align=\"left\" colspan=\"2\"><b><font color=\"red\">NOTICE!</font> There are ".$nrowsF." Labor Cost Items in the Pricebook that have not been synchronized with Quickbooks.<br>Until these items are synchronized, Quickbooks will not be able to properly accept Estimate/Job data from the JMS.</b></td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td valign=\"top\"><button id=\"SyncServiceItems\">Synchronize <img src=\"images/arrow_refresh.png\"></button></td>\n";
		echo "			<td align=\"left\" valign=\"top\"><div id=\"textbox_CostConfigStatus\"></div></td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";
	}

	if (isset($_SESSION['subq']))
	{
		echo "<br>";

		if ($_SESSION['subq']=='add')
		{
			accadd($phsid);
		}
		elseif ($_SESSION['subq']=='ins')
		{
			//echo "<b>Adding...</b>";
			accins();
		}
		elseif ($_SESSION['subq']=='del')
		{
			accdel($id,$phsid);
		}
		elseif ($_SESSION['subq']=='del2')
		{
			//echo "<b>Deleting...</b>";
			accdel2($id,$phsid);
		}
		elseif ($_SESSION['subq']=='ed')
		{
			acced($id,$phsid);
		}
		elseif ($_SESSION['subq']=='ed2')
		{
			//echo "<b>Updating...</b>";
			accupd();
		}
		elseif ($_SESSION['subq']=='edbp')
		{
			accupdbp();
		}
		elseif ($_SESSION['subq']=='adj_package')
		{
			adjust_package_item();
		}
		elseif ($_SESSION['subq']=='reseq')
		{
			//echo "<b>Sequencing Codes...</b>";
			renumseqacc($phsid);
		}
		elseif ($_SESSION['subq']=='addspecaccpbook')
		{
			//echo "TEST add<br>";
			addspecaccpbook();
		}
		elseif ($_SESSION['subq']=='editspecaccpbook')
		{
			//echo "TEST add<br>";
			editspecaccpbook();
		}
		elseif ($_SESSION['subq']=='deletespecaccpbook')
		{
			//echo "TEST add<br>";
			deletespecaccpbook();
		}
	}

	if ($nrowsD > 0)
	{
		echo "<br>";

		acclist($phsid,$order);
	}
}

function zclist($officeid,$zcid,$phsid,$accid,$item,$milesl,$milesu,$bprice,$baseitem,$tdc)
{
	echo "   <tr><td align=\"center\" valign=\"center\"><form action=\"zone_maint.php\" method=\"post\" target=\"actioniframe\"><input type=\"hidden\" name=\"officeid\" value=\"$officeid\"><input type=\"hidden\" name=\"call\" value=\"del\"><input type=\"hidden\" name=\"zcid\" value=\"$zcid\"><input type=\"hidden\" name=\"phsid\" value=\"$phsid\"><button type=\"submit\" class=\"plain\"><img src=\"./images/redx.gif\" alt=\"Delete Accessory\" height=\"10\" width=\"14\"></button></form></td><td align=\"center\" valign=\"center\"><form action=\"zone_maint.php\" method=\"post\" target=\"actioniframe\"><input type=\"hidden\" name=\"call\" value=\"ed\"><input type=\"hidden\" name=\"zcid\" value=\"$zcid\"><input type=\"hidden\" name=\"officeid\" value=\"$officeid\"><input type=\"hidden\" name=\"phsid\" value=\"$phsid\"><button type=\"submit\" class=\"plain\"><img src=\"./images/closeup.gif\" alt=\"Edit Accessory\"></button></form></td><td align=\"right\" class=\"$tdc\" >$accid</td><td align=\"left\" class=\"$tdc\" >$item</td><td align=\"left\" class=\"$tdc\" >$milesl - $milesu</td><td align=\"right\" class=\"$tdc\" >\$$bprice</td><td align=\"left\" class=\"$tdc\" >";
	if ($baseitem=="T")
	{
		echo "<img src=\"./images/smchk_rd.gif\" alt=\"Delete Accessory\" height=\"10\" width=\"14\">";
	}
	echo "</td></tr>\n";
}

function zcadd($officeid,$phsid)
{
	echo "<form action=\"zone_maint.php\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"ins\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"$phsid\">\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Zone Chrg Code:</b></td><td><input class=\"critical\" type=\"text\" name=\"accid\" size=\"5\" maxlength=\"4\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Description:</b></td><td><input class=\"critical\" type=\"text\" name=\"item\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Cost:</b></td><td><input type=\"text\" name=\"bprice\" size=\"15\" value=\"0.00\"></td><td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Mile Range:</b></td><td><input type=\"text\" name=\"milesl\" size=\"5\"><input type=\"text\" name=\"milesu\" size=\"5\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Base Item?</b></td><td> Yes<input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"T\"> No<input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"F\" CHECKED></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"2\" align=\"right\"><button type=\"submit\">Add</button></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function zcins($officeid,$phsid,$accid,$item,$milesl,$milesu,$bprice,$baseitem)
{
	$qry  = "sp_insertzcharge ";
	$qry .= "@officeid='$officeid',@accid='$accid',@phsid='$phsid',@item='$item',@milesl='$milesl',@milesu='$milesu',@bprice='$bprice',@rprice='0.00',@baseitem='$baseitem';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=zone_maint.php?officeid=$officeid&phsid=$phsid\">";
}

function zcdel($zcid)
{
	$qry = "SELECT * FROM zcharge WHERE zcid='$zcid';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	echo "<form action=\"zone_maint.php\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"del2\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"$row[2]\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"$row[1]\">\n";
	echo "<input type=\"hidden\" name=\"zcid\" value=\"$row[0]\">\n";
	echo "<font color=\"red\"><b>Confirm Delete:</b></font> (Can be re-added)\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Accessory Code:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[3]\" size=\"5\" maxlength=\"4\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Description:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[4]\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Cost:</b></td><td><input type=\"text\" value=\"$row[7]\" size=\"15\"></td><td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b><b>Mile Range:</b></td><td><input type=\"text\" name=\"milesl\" value=\"$row[5]\" size=\"5\"><input type=\"text\" name=\"milesu\" value=\"$row[6]\" size=\"5\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	if ($row[9]=="T")
	{
		echo "	 <td align=\"right\"><b>Base Item?</b></td><td> Yes<input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"T\" CHECKED> No<input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"F\"></td>\n";
	}
	else
	{
		echo "	 <td align=\"right\"><b>Base Item?</b></td><td> Yes<input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"T\"> No<input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"F\" CHECKED></td>\n";
	}
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"2\" align=\"right\"><button type=\"submit\">Delete</button></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function zcdel2($zcid)
{
	$qry = "DELETE FROM zcharge WHERE zcid='$zcid';";
	$res = mssql_query($qry);
	//$row = mssql_fetch_row($res);
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=zone_maint.php?zcid=$zcid\">";

}

function zced($zcid)
{
	$qry = "SELECT * FROM zcharge WHERE zcid='$zcid';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	echo "<form action=\"zone_maint.php\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"ed2\">\n";
	echo "<input type=\"hidden\" name=\"zcid\" value=\"$row[0]\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"$row[1]\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"$row[2]\">\n";
	echo "<font color=\"red\"><b>Edit Accessory:</b></font> (Accessory Code cannot be changed)\n";
	echo "<table width=\"398px\">\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Accessory Code:</b></td><td>$row[3]</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Description:</b></td><td><input class=\"critical\" type=\"text\" name=\"item\" value=\"$row[4]\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Cost:</b></td><td><input type=\"text\" name=\"bprice\" value=\"$row[7]\" size=\"15\"></td><td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Mile Range:</b></td><td><input type=\"text\" name=\"milesl\" value=\"$row[5]\" size=\"5\"><input type=\"text\" name=\"milesu\" value=\"$row[6]\" size=\"5\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	if ($row[9]=="T")
	{
		echo "	 <td align=\"right\"><b>Base Item?</b></td><td>Yes <input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"T\" CHECKED> No<input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"F\"></td>\n";
	}
	else
	{
		echo "	 <td align=\"right\"><b>Base Item?</b></td><td> Yes<input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"T\"> No<input class=\"checkboxcrm\" type=\"radio\" name=\"baseitem\" value=\"F\" CHECKED></td>\n";
	}
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"2\" align=\"right\"><button type=\"submit\">Update</button></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

}

function zced2($zcid,$officeid,$phsid,$item,$milesl,$milesu,$bprice,$baseitem)
{
	$qry  = "sp_updatezcharge @zcid='$zcid',@item='$item',@milesl='$milesl',@milesu='$milesu',@bprice='$bprice',@baseitem='$baseitem';";
	$res = mssql_query($qry);
	//$row = mssql_fetch_row($res);
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=zone_maint.php?officeid=$officeid&phsid=$phsid\">";
}

function renumseqinv($phsid) // Sequences Accessory Codes
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	$qryB = "SELECT DISTINCT(accid) FROM [".$MAS."inventory] WHERE officeid=$officeid AND phsid=$phsid AND baseitem!=1";
	$resB = mssql_query($qryB);

	while($rowB=mssql_fetch_row($resB))
	{
		$v1=$rowB[0];
		if (array_key_exists($v1,$_POST))
		{
			$qry = "UPDATE [".$MAS."inventory] SET seqnum=".$_REQUEST[$v1]." WHERE officeid=$officeid AND phsid=$phsid AND accid=$v1;";
			$res = mssql_query($qry);
			$row = mssql_fetch_row($res);
			//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=pbconfig&call=inv&subq=list&phsid=$phsid\">";
		}
	}
}

function invlist($phsid,$order)
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	
	$qryp   = "SELECT * FROM offices WHERE officeid=".$officeid.";";
	$resp   = mssql_query($qryp);
	$rowp 	= mssql_fetch_array($resp);
	
	if ($rowp['accountingsystem'] >= 2)
	{
		$qryD   = "SELECT invid,officeid,phsid,accid,item,bprice,rprice,mtype,seqnum,baseitem,raccid,rinvid,matid,qtype,ListID,EditSequence FROM [".$MAS."inventory] WHERE officeid=$officeid AND phsid=$phsid ORDER BY ".$order.";";	
	}
	else
	{
		$qryD   = "SELECT invid,officeid,phsid,accid,item,bprice,rprice,mtype,seqnum,baseitem,raccid,rinvid,matid,qtype FROM [".$MAS."inventory] WHERE officeid=$officeid AND phsid=$phsid ORDER BY ".$order.";";
	}
	
	$resD   = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);

	//echo $qryD."<br>";

	$qryE   = "SELECT DISTINCT(accid) FROM [".$MAS."inventory] WHERE officeid=$officeid AND phsid=$phsid";
	$resE   = mssql_query($qryE);
	$rowE   = mssql_fetch_row($resE);
	$nrowsE = mssql_num_rows($resE);

	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	      <tr>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\"><b>Code</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"left\"><b>Description</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"left\"><b>Calc Type</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\"><b>Units</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\"><b>Ties</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"left\"><b>Vendor PN</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\"><b>Cost</b></td>\n";	
	echo "            <td class=\"ltgray_und\" align=\"left\" colspan=\"2\"></td>\n";
	echo "         </tr>\n";

	while($rowD = mssql_fetch_row($resD))
	{
		$qryF   = "SELECT invid,accid FROM [".$MAS."inventory] WHERE officeid=$officeid AND phsid=$phsid AND accid=$rowD[3];";
		$resF   = mssql_query($qryF);
		$rowF   = mssql_fetch_row($resF);

		$qryG   = "SELECT mid,abrv FROM mtypes WHERE mid=$rowD[7];";
		$resG   = mssql_query($qryG);
		$rowG   = mssql_fetch_row($resG);

		if ($rowD[12]!=0)
		{
			$qryH   = "SELECT bp,vpnum FROM material_master WHERE id='".$rowD[12]."';";
			$resH   = mssql_query($qryH);
			$rowH   = mssql_fetch_row($resH);
		}
		
		$qryI   = "SELECT COUNT(cid) as cnt FROM [".$MAS."rclinks_m] WHERE cid='".$rowD[0]."';";
		$resI   = mssql_query($qryI);
		$rowI   = mssql_fetch_array($resI);
		
		$qryJ   = "SELECT qtype FROM qtypes WHERE qid='".$rowD[13]."';";
		$resJ   = mssql_query($qryJ);
		$rowJ   = mssql_fetch_row($resJ);

		$tdc = "wh_und";

		echo "         <tr>\n";
		echo "            <td align=\"right\" class=\"$tdc\" >".$rowD[3]."</td>\n";
		echo "            <td align=\"left\" class=\"$tdc\" >".$rowD[4]."</td>\n";
		echo "            <td align=\"left\" class=\"$tdc\" >".$rowJ[0]."</td>\n";
		echo "            <td align=\"center\" class=\"$tdc\" >".$rowG[1]."</td>\n";
		echo "            <td align=\"center\" class=\"$tdc\" >".$rowI['cnt']."</td>\n";
		echo "            <td align=\"left\" class=\"$tdc\" >\n";

		if ($rowD[12]!=0)
		{
			echo $rowH[1];
		}

		echo "            </td>\n";
		echo "            <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "            <td align=\"right\" class=\"wh_und\" >\n";

		if ($rowD[12]!=0 && $rowD[13]!=56)
		{
			echo "					<input class=\"bbox\" type=\"text\" name=\"bp\" value=\"".$rowH[0]."\" size=\"10\" DISABLED>\n";
		}
		else
		{
			echo "					<input class=\"bbox\" type=\"text\" name=\"bp\" value=\"".$rowD[5]."\" size=\"10\">\n";
		}

		echo "				</td>\n";
		echo "               <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "               <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "               <input type=\"hidden\" name=\"subq\" value=\"edbp\">\n";
		echo "               <input type=\"hidden\" name=\"id\" value=\"".$rowD[0]."\">\n";
		echo "               <input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
		echo "               <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "            <td align=\"right\" class=\"wh_und\" width=\"25\">\n";

		if ($rowD[12]==0)
		{
			echo "							<input class=\"checkboxwh\" type=\"image\" src=\"images/save.gif\" alt=\"Update\">\n";
		}

		echo "            </td>\n";
		echo "            </form>\n";
		echo "            <td align=\"right\" class=\"wh_und\" width=\"25\">\n";
		echo "            <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "               <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "               <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "               <input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
		echo "               <input type=\"hidden\" name=\"invid\" value=\"".$rowD[0]."\">\n";
		echo "               <input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
		echo "               <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "				 <input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
		echo "            </form>\n";
		echo "            </td>\n";
		echo "         </tr>\n";
	}

	echo "</table>\n";
	echo "</form>\n";
}

function invadd($phsid)
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];

	$qryp1  = "SELECT rphsid FROM phasebase WHERE phsid=$phsid";
	$resp1  = mssql_query($qryp1);
	$rowp1  = mssql_fetch_row($resp1);

	$qry    = "SELECT MAX(accid) FROM [".$MAS."inventory] WHERE officeid=$officeid AND phsid=$phsid";
	$res    = mssql_query($qry);
	$row    = mssql_fetch_row($res);

	$qryA   = "SELECT qid,qtype FROM qtypes ORDER BY qtype";
	$resA   = mssql_query($qryA);

	$qryB   = "SELECT DISTINCT(accid)FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$rowp1[0]";
	$resB   = mssql_query($qryB);

	$qryC   = "SELECT DISTINCT(accid) FROM [".$MAS."inventory] WHERE officeid=$officeid AND phsid=$phsid";
	$resC   = mssql_query($qryC);

	if ($row[0]<=1)
	{
		$maccid=($phsid)*10000;
		$codewarning="  (Note:$maccid is for Base Items Only!)";
	}
	else
	{
		$maccid=$row[0]+1;
		$codewarning="";
	}

	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"inv\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"ins\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"$phsid\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
	echo "<input type=\"hidden\" name=\"accid\" value=\"$maccid\">\n";
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\"><b>Description:</b></td>\n";
	echo "		<td colspan=\"2\" align=\"left\"><input class=\"critical\" type=\"text\" name=\"item\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\"><b>Vendor Part #:</b></td>\n";
	echo "		<td align=\"left\"><input class=\"critical\" type=\"text\" name=\"vpno\" size=\"21\" maxlength=\"20\"></td>\n";
	echo "		<td rowspan=\"6\" valign=\"bottom\" align=\"right\">\n";
	echo "			<table>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"left\" colspan=\"2\"><b><i>Code Controls:</i></b></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><font color=\"red\">Active?:</font></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select name=\"active\">\n";
	echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
	echo "                  <option value=\"0\">No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><font color=\"red\">Base Item:</font></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select name=\"baseitem\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><font color=\"red\">Question Type:</font></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select name=\"qtype\">\n";
	echo "                  <option value=\"0\" SELECTED>None</option>\n";

	while($rowA = mssql_fetch_row($resA))
	{
		echo "                  <option value=\"$rowA[0]\">$rowA[1]</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\">Calc Minimum:</b></td>\n";
	echo "            <td align=\"left\"><input class=\"critical\" type=\"text\" name=\"quan_calc\" value=\"0\" size=\"4\" maxlength=\"4\"></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\">Spa Item:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select name=\"spaitem\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "      </table>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Cost:</b></td>\n";
	echo "   <td align=\"left\"><input class=\"critical\" type=\"text\" name=\"bprice\" size=\"15\" value=\"0.00\"><input type=\"hidden\" name=\"rprice\" value=\"0\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>% Comm:</b></td>\n";
	echo "    <td align=\"left\"><input class=\"critical\" type=\"text\" name=\"pcomm\" size=\"15\" value=\".00\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Flat Comm:</b></td>\n";
	echo "    <td align=\"left\"><input class=\"critical\" type=\"text\" name=\"comm\" size=\"15\" value=\"0.00\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Rebate:</b></td>\n";
	echo "    <td align=\"left\"><input class=\"critical\" type=\"text\" name=\"rebate\" size=\"15\" value=\"0.00\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Units:</b></td>\n";
	echo "   <td align=\"left\"><select name=\"uom\"><option value=\"0\" DEFAULT></option><option value=\"ft\">ft</option><option value=\"sqft\">sqft</option><option value=\"hr\">hr</option><option value=\"ea\">ea</option><option value=\"fixed\">fixed</option><option value=\"%\">%</option><option value=\"yrd\">yard</option></select></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Rel Labor Code:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select name=\"raccid\">\n";
	echo "            <option value=\"0\" SELECTED>None</option>\n";

	while($rowB = mssql_fetch_row($resB))
	{
		echo "         <option value=\"$rowB[0]\">$rowB[0]</option>\n";
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Rel Inv Item:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select name=\"rinvid\">\n";
	echo "            <option value=\"0\" SELECTED>None</option>\n";

	while($rowC = mssql_fetch_row($resC))
	{
		echo "         <option value=\"$rowC[0]\">$rowC[0]</option>\n";
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"3\" align=\"center\"><button type=\"submit\">&nbsp;&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;&nbsp;</button></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function invadd_mm1()
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$qryA   = "SELECT vid,name FROM vendors ORDER BY name ASC;";
	$resA   = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	//$qryA   = "SELECT catid,name FROM MM_cats ORDER BY name ASC;";
	//$resA   = mssql_query($qryA);
	//$nrowsA = mssql_num_rows($resA);

	echo "<table width=\"100%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <th align=\"left\" colspan=\"2\">Inventory Cost Item: Master Material Category Select</th>\n";
	echo "      <th align=\"right\"><font color=\"blue\">".$nrowsA."</font> Vendors</th>\n";
	echo "   </tr>\n";

	while($rowA=mssql_fetch_array($resA))
	{
		echo "   <tr>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">&nbsp</td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\"><b>".$rowA['name']."</b></td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"add_mm2\">\n";
		echo "         <input type=\"hidden\" name=\"phsid\" value=\"".$_REQUEST['phsid']."\">\n";
		echo "         <input type=\"hidden\" name=\"retid\" value=\"".$_REQUEST['retid']."\">\n";
		echo "         <input type=\"hidden\" name=\"qtype\" value=\"".$_REQUEST['qtype']."\">\n";
		echo "         <input type=\"hidden\" name=\"mtype\" value=\"".$_REQUEST['mtype']."\">\n";
		echo "         <input type=\"hidden\" name=\"vid\" value=\"".$rowA['vid']."\">\n";
		//echo "         <button type=\"submit\">Select</button>\n";
		echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/add.png\" alt=\"Select\">\n";
		echo "      </td>\n";
		echo "         </form>\n";
		echo "   </tr>\n";
	}
	echo "</table>\n";
}

function invadd_mm1old()
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$qryA   = "SELECT vid,name FROM vendors ORDER BY name ASC;";
	$resA   = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	echo "<table width=\"50%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <th align=\"left\" colspan=\"2\">Inventory Cost Item: Master Material Category Select</th>\n";
	echo "      <th align=\"right\"><font color=\"blue\">".$nrowsA."</font> Vendors</th>\n";
	echo "   </tr>\n";

	while($rowA=mssql_fetch_array($resA))
	{
		echo "   <tr>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">&nbsp</td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\"><b>".$rowA['name']."</b></td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"add_mm2\">\n";
		echo "         <input type=\"hidden\" name=\"phsid\" value=\"".$_REQUEST['phsid']."\">\n";
		echo "         <input type=\"hidden\" name=\"retid\" value=\"".$_REQUEST['retid']."\">\n";
		echo "         <input type=\"hidden\" name=\"qtype\" value=\"".$_REQUEST['qtype']."\">\n";
		echo "         <input type=\"hidden\" name=\"mtype\" value=\"".$_REQUEST['mtype']."\">\n";
		echo "         <input type=\"hidden\" name=\"catid\" value=\"".$rowA['catid']."\">\n";
		echo "         <button type=\"submit\">Select</button>\n";
		echo "      </td>\n";
		echo "         </form>\n";
		echo "   </tr>\n";
	}
	echo "</table>\n";
}

function invadd_mm2()
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$qryA   = "SELECT * FROM material_master WHERE vid='".$_REQUEST['vid']."' ORDER BY item;";
	$resA   = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	$qryB   = "SELECT vid,name FROM vendors  WHERE vid='".$_REQUEST['vid']."';";
	$resB   = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
	//$qryB   = "SELECT catid,name FROM MM_cats WHERE catid='".$_REQUEST['catid']."';";
	//$resB   = mssql_query($qryB);
	//$rowB   = mssql_fetch_array($resB);

	echo "<table width=\"50%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <th align=\"left\" colspan=\"2\">Add Material Cost Item: Select Material Item from ".$rowB['name']."</th>\n";
	echo "      <th align=\"right\"><font color=\"blue\">".$nrowsA."</font> ".$rowB['name']." Items</th>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <th align=\"left\">Part #</th>\n";
	echo "      <th align=\"left\" colspan=\"2\">Description</th>\n";
	echo "   </tr>\n";

	while($rowA=mssql_fetch_array($resA))
	{
		echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "      <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "      <input type=\"hidden\" name=\"subq\" value=\"add_mm3\">\n";
		echo "      <input type=\"hidden\" name=\"phsid\" value=\"".$_REQUEST['phsid']."\">\n";
		echo "      <input type=\"hidden\" name=\"vid\" value=\"".$_REQUEST['vid']."\">\n";
		echo "      <input type=\"hidden\" name=\"retid\" value=\"".$_REQUEST['retid']."\">\n";
		echo "      <input type=\"hidden\" name=\"qtype\" value=\"".$_REQUEST['qtype']."\">\n";
		echo "      <input type=\"hidden\" name=\"mtype\" value=\"".$_REQUEST['mtype']."\">\n";
		echo "      <input type=\"hidden\" name=\"catid\" value=\"".$rowA['cat']."\">\n";
		echo "      <input type=\"hidden\" name=\"matid\" value=\"".$rowA['id']."\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">".$rowA['vpnum']."</td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">".$rowA['item']." - ".$rowA['atrib1']."</td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		//echo "         <button type=\"submit\">Select Item</button>\n";
		echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/add.png\" alt=\"Select\">\n";
		echo "      </td>\n";
		echo "         </form>\n";
		echo "      </tr>\n";
	}
	echo "   </table>\n";
}

function invadd_mm2old()
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$qryA   = "SELECT * FROM material_master WHERE cat='".$_REQUEST['catid']."' ORDER BY item;";
	$resA   = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	$qryB   = "SELECT catid,name FROM MM_cats WHERE catid='".$_REQUEST['catid']."';";
	$resB   = mssql_query($qryB);
	$rowB   = mssql_fetch_array($resB);

	echo "<table width=\"50%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <th align=\"left\" colspan=\"2\">Add Material Cost Item: Select Material Item from ".$rowB['name']."</th>\n";
	echo "      <th align=\"right\"><font color=\"blue\">".$nrowsA."</font> ".$rowB['name']." Items</th>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <th align=\"left\">Part #</th>\n";
	echo "      <th align=\"left\" colspan=\"2\">Description</th>\n";
	echo "   </tr>\n";

	while($rowA=mssql_fetch_array($resA))
	{
		echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "      <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "      <input type=\"hidden\" name=\"subq\" value=\"add_mm3\">\n";
		echo "      <input type=\"hidden\" name=\"phsid\" value=\"".$_REQUEST['phsid']."\">\n";
		echo "      <input type=\"hidden\" name=\"catid\" value=\"".$_REQUEST['catid']."\">\n";
		echo "      <input type=\"hidden\" name=\"retid\" value=\"".$_REQUEST['retid']."\">\n";
		echo "      <input type=\"hidden\" name=\"qtype\" value=\"".$_REQUEST['qtype']."\">\n";
		echo "      <input type=\"hidden\" name=\"mtype\" value=\"".$_REQUEST['mtype']."\">\n";
		echo "      <input type=\"hidden\" name=\"matid\" value=\"".$rowA['id']."\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">".$rowA['vpnum']."</td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">".$rowA['item']." - ".$rowA['atrib1']."</td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <button type=\"submit\">Select Item</button>\n";
		echo "      </td>\n";
		echo "         </form>\n";
		echo "      </tr>\n";
	}
	echo "   </table>\n";
}

function invadd_mm3()
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];

	$qryp1  = "SELECT rphsid FROM phasebase WHERE phsid='".$_REQUEST['phsid']."';";
	$resp1  = mssql_query($qryp1);
	$rowp1  = mssql_fetch_row($resp1);

	$qryp2  = "SELECT * FROM material_master WHERE id='".$_REQUEST['matid']."';";
	$resp2  = mssql_query($qryp2);
	$rowp2  = mssql_fetch_array($resp2);

	$qry    = "SELECT MAX(accid) FROM [".$MAS."inventory] WHERE officeid='".$officeid."' AND phsid='".$_REQUEST['phsid']."';";
	$res    = mssql_query($qry);
	$row    = mssql_fetch_row($res);

	$qryA   = "SELECT qid,qtype FROM qtypes ORDER BY qtype ASC";
	$resA   = mssql_query($qryA);

	$qryB   = "SELECT id,aid,item FROM [".$MAS."acc] WHERE officeid='".$officeid."';";
	$resB   = mssql_query($qryB);

	$qryC   = "SELECT DISTINCT(accid) FROM [".$MAS."inventory] WHERE officeid='".$officeid."' AND phsid='".$_REQUEST['phsid']."';";
	$resC   = mssql_query($qryC);

	$qryD   = "SELECT mid,abrv FROM mtypes;";
	$resD   = mssql_query($qryD);

	if ($row[0]<=1)
	{
		$maccid=($_REQUEST['phsid'])*10000;
	}
	else
	{
		$maccid=$row[0]+1;
	}

	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"inv\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"ins\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"".$_REQUEST['phsid']."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
	echo "<input type=\"hidden\" name=\"accid\" value=\"".$maccid."\">\n";
	echo "<input type=\"hidden\" name=\"matid\" value=\"".$_REQUEST['matid']."\">\n";
	echo "<input type=\"hidden\" name=\"rinvid\" value=\"0\">\n";
	echo "<table class=\"outer\" align=\"center\" border=0>\n";
	echo "<tr>\n";
	echo "	<td class=\"ltgray_und\" colspan=\"3\" align=\"left\"><b>Add Material Cost Item</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Vendor Part #:</b></td>\n";
	echo "   <td class=\"gray\"><input type=\"text\" name=\"vpno\" value=\"".$rowp2['vpnum']."\"size=\"21\" maxlength=\"20\"></td>\n";
	echo "   <td class=\"gray\" rowspan=\"6\" valign=\"top\" align=\"right\">\n";
	echo "      <table>\n";
	echo "         <tr>\n";
	echo "	         <td class=\"gray\" align=\"left\" colspan=\"2\"><b><i>Display & Calc Controls:</i></b></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td class=\"gray\" align=\"right\"><b>Active:</b></td>\n";
	echo "            <td class=\"gray\">\n";
	echo "               <select name=\"active\">\n";
	echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
	echo "                  <option value=\"0\">No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td class=\"gray\" align=\"right\"><b>Base Item:</b></td>\n";
	echo "            <td class=\"gray\">\n";
	echo "               <select name=\"baseitem\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td class=\"gray\" align=\"right\"><b>Ques/Calc Type:</b></td>\n";
	echo "            <td class=\"gray\">\n";
	echo "               <select name=\"qtype\">\n";
	echo "                  <option value=\"0\" SELECTED>None</option>\n";

	while($rowA = mssql_fetch_row($resA))
	{
		if ($_REQUEST['qtype']==$rowA[0])
		{
			echo "                  <option value=\"$rowA[0]\" SELECTED>$rowA[1]</option>\n";
		}
		else
		{
			echo "                  <option value=\"$rowA[0]\">$rowA[1]</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td class=\"gray\" align=\"right\"><b>Calc Amt:</b></td>\n";
	echo "            <td class=\"gray\"><input type=\"text\" name=\"quan_calc\" value=\"0\" size=\"4\" maxlength=\"4\"></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td class=\"gray\" align=\"right\"><b>Spa Item:</b></td>\n";
	echo "            <td class=\"gray\">\n";
	echo "               <select name=\"spaitem\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "      </table>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Description:</b></td>\n";
	echo "   <td class=\"gray\"><input type=\"text\" name=\"item\" value=\"".$rowp2['item']."\" size=\"64\" maxlength=\"64\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b></b></td>\n";
	echo "   <td class=\"gray\"><input type=\"text\" name=\"atrib1\" value=\"".$rowp2['atrib1']."\" size=\"64\" maxlength=\"64\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b></b></td>\n";
	echo "   <td class=\"gray\"><input type=\"text\" name=\"atrib2\" value=\"".$rowp2['atrib2']."\" size=\"64\" maxlength=\"64\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b></b></td>\n";
	echo "   <td class=\"gray\"><input type=\"text\" name=\"atrib3\" value=\"".$rowp2['atrib3']."\" size=\"64\" maxlength=\"64\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Cost:</b></td>\n";
	echo "   <td class=\"gray\"><input type=\"text\" name=\"bprice\" size=\"15\" value=\"".$rowp2['bp']."\"><input type=\"hidden\" name=\"rprice\" value=\"0\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td class=\"gray\" align=\"right\"><b>Comm Type:</b></td>\n";
	echo "    <td class=\"gray\">\n";
	echo "      <select name=\"commtype\">\n";
	echo "         <option value=\"0\" SELECTED>None</option>\n";
	echo "         <option value=\"1\">Fixed</option>\n";
	echo "         <option value=\"2\">Percentage</option>\n";
	echo "      </select>\n";
	echo "    </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td class=\"gray\" align=\"right\"><b>Comm Rate:</b></td>\n";
	echo "    <td class=\"gray\"><input type=\"text\" name=\"crate\" size=\"15\" value=\"0\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Units:</b></td>\n";
	echo "   <td class=\"gray\">\n";
	echo "      <select name=\"mtype\">\n";

	while($rowD = mssql_fetch_row($resD))
	{
		if ($_REQUEST['mtype']==$rowD[0])
		{
			echo "         <option value=\"$rowD[0]\" SELECTED>$rowD[1]</option>\n";
		}
		else
		{
			echo "         <option value=\"$rowD[0]\">$rowD[1]</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Member of:</b></td>\n";
	echo "   <td class=\"gray\">\n";
	echo "      <select name=\"raccid\">\n";
	echo "            <option value=\"0\" SELECTED>None</option>\n";

	while($rowB = mssql_fetch_row($resB))
	{
		if ($_REQUEST['retid']==$rowB[0])
		{
			echo "         <option value=\"$rowB[0]\" SELECTED>($rowB[1]) $rowB[2]</option>\n";
		}
		else
		{
			echo "         <option value=\"$rowB[0]\">($rowB[1]) $rowB[2]</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td class=\"gray\" colspan=\"3\" align=\"center\"><button type=\"submit\">&nbsp;&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;&nbsp;</button></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function invins()
{
	$phsid    =$_REQUEST['phsid'];
	$accid    =$_REQUEST['accid'];
	$matid    =$_REQUEST['matid'];
	$item     =replacecomma($_REQUEST['item']);
	$atrib1   =replacecomma($_REQUEST['atrib1']);
	$atrib2   =replacecomma($_REQUEST['atrib2']);
	$atrib3   =replacecomma($_REQUEST['atrib3']);
	$vpno     =$_REQUEST['vpno'];
	$bprice   =$_REQUEST['bprice'];
	$rprice   =$_REQUEST['rprice'];
	$commtype =$_REQUEST['commtype'];
	$crate    =$_REQUEST['crate'];
	$mtype    =$_REQUEST['mtype'];
	$baseitem =$_REQUEST['baseitem'];
	$qtype    =$_REQUEST['qtype'];
	$raccid   =$_REQUEST['raccid'];
	$rinvid   =$_REQUEST['rinvid'];
	$active   =$_REQUEST['active'];
	$spaitem  =$_REQUEST['spaitem'];
	$quan_calc=$_REQUEST['quan_calc'];
	$officeid =$_SESSION['officeid'];
	$MAS		=$_SESSION['pb_code'];

	/*
	$qry  = "sp_insertinv ";
	$qry .= "@officeid='$officeid',";
	$qry .= "@pb_code='$MAS', ";
	$qry .= "@phsid='$phsid',";
	$qry .= "@accid='$accid',";
	$qry .= "@matid='$matid',";
	$qry .= "@item='$item',";
	$qry .= "@atrib1='$atrib1',";
	$qry .= "@atrib2='$atrib2',";
	$qry .= "@atrib3='$atrib3',";
	$qry .= "@vpno='$vpno',";
	$qry .= "@mtype='$mtype',";
	$qry .= "@bprice='$bprice',";
	$qry .= "@rprice='$rprice',";
	$qry .= "@commtype='$commtype',";
	$qry .= "@crate='$crate',";
	$qry .= "@baseitem='$baseitem',";
	$qry .= "@qtype='$qtype',";
	$qry .= "@raccid='$raccid',";
	$qry .= "@rinvid='$rinvid',";
	$qry .= "@active='$active',";
	$qry .= "@quan_calc='$quan_calc',";
	$qry .= "@spaitem='$spaitem';";
	*/

	$qry  = "INSERT INTO [".$MAS."inventory] ";
	$qry .= "(";
	$qry .= "officeid,";
	$qry .= "phsid,";
	$qry .= "accid,";
	$qry .= "matid,";
	$qry .= "item,";
	$qry .= "atrib1,";
	$qry .= "atrib2,";
	$qry .= "atrib3,";
	$qry .= "vpno,";
	$qry .= "mtype,";
	$qry .= "bprice,";
	$qry .= "rprice,";
	$qry .= "commtype,";
	$qry .= "crate,";
	$qry .= "baseitem,";
	$qry .= "qtype,";
	$qry .= "raccid,";
	$qry .= "rinvid,";
	$qry .= "active,";
	$qry .= "quan_calc,";
	$qry .= "usecid,";
	$qry .= "updt,";
	$qry .= "spaitem";
	$qry .= ")";
	$qry .= " VALUES ";
	$qry .= "(";
	$qry .= "'$officeid',";
	$qry .= "'$phsid',";
	$qry .= "'$accid',";
	$qry .= "'$matid',";
	$qry .= "'$item',";
	$qry .= "'$atrib1',";
	$qry .= "'$atrib2',";
	$qry .= "'$atrib3',";
	$qry .= "'$vpno',";
	$qry .= "'$mtype',";
	$qry .= "(CONVERT(smallmoney,'$bprice')),";
	$qry .= "(CONVERT(smallmoney,'$rprice')),";
	$qry .= "'$commtype',";
	$qry .= "'$crate',";
	$qry .= "'$baseitem',";
	$qry .= "'$qtype',";
	$qry .= "'$raccid',";
	$qry .= "'$rinvid',";
	$qry .= "'$active',";
	$qry .= "'$quan_calc',";
	$qry .= "'".$_SESSION['securityid']."',";
	$qry .= "getdate(),";
	$qry .= "'$spaitem'";
	$qry .= ");";
	$res = mssql_query($qry);
	//$row = mssql_fetch_row($res);

	//echo $qry;

	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=pbconfig&call=inv&subq=list&phsid=$phsid\">";
}

function invdel($invid,$phsid)
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	$qry = "SELECT invid,accid,item,bprice,mtype,officeid,phsid,baseitem,rprice,raccid,spaitem FROM [".$MAS."inventory] WHERE invid='$invid';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"inv\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"del2\">\n";
	echo "<input type=\"hidden\" name=\"invid\" value=\"$row[0]\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"$row[5]\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"$row[6]\">\n";
	echo "<font color=\"red\"><b>Confirm Delete:</b></font> (Can be re-added)\n";
	echo "<table>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Accessory Code:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[1]\" size=\"5\" maxlength=\"4\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Description:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[2]\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Cost:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[3]\" size=\"15\"></td><td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Retail:</b></td><td><input class=\"critical\" type=\"text\" name=\"rprice\" size=\"15\" value=\"0.00\"></td><td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Unit:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[8]\" size=\"10\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Base Item:</b></td><td>\n";

	if ($row[7]==1)
	{
		echo "Yes";
	}
	else
	{
		echo "No</td>\n";
	}

	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Rel Option:</b></td><td>$row[9]</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Rel Inv:</b></td><td>$row[10]</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Spa Item:</b></td><td>\n";

	if ($row[10]==1)
	{
		echo "Yes";
	}
	else
	{
		echo "No</td>\n";
	}

	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"2\" align=\"right\"><button type=\"submit\">Delete</button></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function invdel2($invid,$phsid)
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	$qry = "DELETE FROM [".$MAS."inventory] WHERE invid='$invid';";
	$res = mssql_query($qry);
	//$row = mssql_fetch_row($res);
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=pbconfig&call=inv&subq=list&phsid=".$phsid."\">";

}

function inved($invid,$phsid)
{
	//ECHO "TESTINV:".$invid." ".$phsid;
	$MAS		=$_SESSION['pb_code'];
	$officeid	=$_SESSION['officeid'];
	$eqpphs		=39;
	
	$qryp  = "SELECT officeid,pb_code,accountingsystem,enmas,enquickbooks FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$resp  = mssql_query($qryp);
	$rowp  = mssql_fetch_array($resp);

	$qryp0  = "SELECT rphsid,phsid,phsname,phscode FROM phasebase WHERE costing='1' AND phstype='M';";
	$resp0  = mssql_query($qryp0);
	//$rowp0  = mssql_fetch_array($resp0);

	$qryp1  = "SELECT rphsid,phsid FROM phasebase WHERE phsid='".$phsid."';";
	$resp1  = mssql_query($qryp1);
	$rowp1  = mssql_fetch_array($resp1);

	$qry  = "SELECT * FROM [".$MAS."inventory] WHERE invid='".$invid."';";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);

	if ($row['matid']!=0)
	{
		$qryA = "SELECT id,vpnum,item,bp FROM material_master WHERE id='".$row['matid']."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
	}

	//echo "MAT ITEM".$rowA['item'];
	//print_r($rowA);

	$qryB = "SELECT qid,qtype FROM qtypes ORDER BY qtype ASC;";
	$resB = mssql_query($qryB);

	$qryC = "SELECT invid,item FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$rowp1['phsid']."' ORDER BY item ASC;";
	$resC = mssql_query($qryC);

	$qryD = "SELECT id,aid,item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."';";
	$resD = mssql_query($qryD);

	$qryE = "SELECT commid,commtype FROM commtypes ORDER BY commid;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT mid,abrv FROM mtypes ORDER BY abrv;";
	$resF = mssql_query($qryF);

	if (isset($row['usecid']) || $row['usecid'] !=0 || strtotime($row['updt']) < strtotime('1/1/1999'))
	{
		$qryG = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['usecid']."';";
		$resG = mssql_query($qryG);
		$rowG  = mssql_fetch_array($resG);

		$ufname	=$rowG['fname'];
		$ulname	=$rowG['lname'];
		$udate	=date("m/d/Y",strtotime($row['updt']));
		$updby   =$ulname.",".$ufname." on ".$udate;
	}
	else
	{
		$updby	="";
	}

	$qryH = "SELECT officeid,rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND cid='".$invid."';";
	$resH = mssql_query($qryH);
	$nrowH= mssql_num_rows($resH);
	$sizeH=$nrowH+1;
	
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"inv\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"ed2\">\n";
	echo "<input type=\"hidden\" name=\"invid\" id=\"iid\" value=\"".$row['invid']."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" id=\"oid\" value=\"".$row['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"".$row['phsid']."\">\n";
	echo "<input type=\"hidden\" name=\"accid\" value=\"".$row['accid']."\">\n";
	echo "<input type=\"hidden\" name=\"matid\" value=\"".$row['matid']."\">\n";
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "<tr>\n";
	echo "	<td align=\"right\">Last Update:</td>\n";
	echo "	<td>\n";
	
	echo $updby;
	
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Vendor Part #:</b></td>\n";

	if ($row['matid']!=0)
	{
		echo "   <td align=\"left\"><input type=\"text\" value=\"".$rowA['vpnum']."\" size=\"21\" maxlength=\"20\" DISABLED><input type=\"hidden\" name=\"vpno\" value=\"".$rowA['vpnum']."\"></td>\n";
	}
	else
	{
		echo "   <td align=\"left\"><input type=\"text\" name=\"vpno\" value=\"".$row['vpno']."\" size=\"21\" maxlength=\"20\"></td>\n";
	}

	echo "   <td rowspan=\"10\" valign=\"top\" align=\"right\" >\n";
	echo "      <table>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"left\" colspan=\"2\"><b><i>Display & Calc Controls:</i></b></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Phase:</b></td>\n";
	echo "	         <td align=\"left\">\n";
	echo "               <select name=\"nphsid\">\n";
	
	while ($rowp0  = mssql_fetch_array($resp0))
	{
		if ($rowp0['phsid']==$row['phsid'])
		{
			echo "                  <option value=\"".$rowp0['phsid']."\" SELECTED>".$rowp0['phscode']." - ".$rowp0['phsname']."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$rowp0['phsid']."\">".$rowp0['phscode']." - ".$rowp0['phsname']."</option>\n";
		}	
	}
	
	echo "               </select>\n";
	echo "			</td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Active:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select name=\"active\">\n";

	if ($row['active']==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Base Item:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select name=\"baseitem\">\n";

	if ($row['baseitem']==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Question Type:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select name=\"qtype\">\n";

	while($rowB = mssql_fetch_row($resB))
	{
		if ($row['qtype']==$rowB[0])
		{
			echo "                  <option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$rowB[0]."\">".$rowB[1]."</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Calc Default:</b></td>\n";
	echo "            <td align=\"left\"><input type=\"text\" name=\"quan_calc\" value=\"".$row['quan_calc']."\" size=\"4\" maxlength=\"4\"></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Spa Item:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select name=\"spaitem\">\n";

	if ($row['spaitem']==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	
	echo "         <tr>\n";
	echo "	         <td align=\"right\" valign=\"top\"><b>Related Retail</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "            <select size=\"".$sizeH."\">\n";

	while($rowH = mssql_fetch_array($resH))
	{
		$qryI = "SELECT id,item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id=".$rowH['rid'].";";
		$resI = mssql_query($qryI);
		$rowI = mssql_fetch_array($resI);

		echo "			<option>".$rowI['item']."</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	
	if ($rowp['enquickbooks']==1)
	{
		if (isset($row['ListID']) and strlen($row['ListID']) > 3)
		{
			echo "         <tr>\n";
			echo "	         <td align=\"right\" valign=\"top\"><b>JMS ID</b></td>\n";
			echo "            <td align=\"left\">".$row['invid']."</td>\n";
			echo "         </tr>\n";
			echo "         <tr>\n";
			echo "	         <td align=\"right\" valign=\"top\"><b>QB ID</b></td>\n";
			echo "            <td align=\"left\">".$row['ListID']."</td>\n";
			echo "         </tr>\n";
			echo "         <tr>\n";
			echo "	         <td align=\"right\" valign=\"top\"><b>QB ES</b></td>\n";
			echo "            <td align=\"left\">".$row['EditSequence']."</td>\n";
			echo "         </tr>\n";
		}
		else
		{
			echo "			<tr>\n";
			echo "				<td align=\"right\" valign=\"top\"><b>JMS ID</b></td>\n";
			echo "				<td align=\"left\">".$row['invid']."</td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>QB ID</b></td>\n";
			echo "				<td align=\"left\" valign=\"top\" rowspan=\"2\">\n";
			
			if ($row['matid']!=0)
			{
				echo "					<input type=\"hidden\" id=\"qaction\" value=\"ItemInventoryAdd\">\n";
			}
			else
			{
				echo "					<input type=\"hidden\" id=\"qaction\" value=\"ItemNonInventoryAdd\">\n";
			}
			
			echo "					<table class=\"outer\" width=\"100%\">\n";
			echo "						<tr>\n";
			echo "							<td align=\"left\"><b>Not synchronized</b></td>\n";
			
			if ($row['matid']!=0)
			{
				echo "							<td valign=\"top\"><div id=\"SyncInventoryItem\">Synchronize <img src=\"images/arrow_refresh.png\"></div></td>\n";
			}
			else
			{
				echo "							<td valign=\"top\"><div id=\"SyncMaterialItem\">Synchronize <img src=\"images/arrow_refresh.png\"></div></td>\n";
			}

			echo "						</tr>\n";
			echo "						<tr>\n";
			echo "							<td align=\"left\" valign=\"top\" colspan=\"2\"><div id=\"textbox_SingleInventoryStatus\"></div></td>\n";
			echo "						</tr>\n";
			echo "					</table>\n";
			
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>QB ES</b></td>\n";
			echo "			</tr>\n";
		}
	}
	
	echo "      </table>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Description:</b></td>\n";

	if ($row['matid']!=0)
	{
		echo "   <td align=\"left\"><input type=\"text\" value=\"".$rowA['item']."\" size=\"64\" maxlength=\"64\" DISABLED><input type=\"hidden\" name=\"item\" value=\"".$rowA['item']."\"></td>\n";
	}
	else
	{
		echo "   <td align=\"left\"><input type=\"text\" name=\"item\" value=\"".$row['item']."\" size=\"64\" maxlength=\"64\"></td>\n";
	}

	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td align=\"left\"><input type=\"text\" name=\"atrib1\" value=\"".$row['atrib1']."\" size=\"64\" maxlength=\"64\"></td>\n";
	echo "</tr>\n";

	if ($row['matid']!=0 && $row['qtype']==56)
	{
		echo "<tr>\n";
		echo "	<td align=\"right\">Base+ Setting:</td>\n";
		echo "   <td align=\"left\"><input type=\"text\" name=\"atrib2\" value=\"".$row['atrib2']."\" size=\"64\" maxlength=\"64\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\">Material Price:</td>\n";
		echo "   <td align=\"left\"><input type=\"text\" value=\"".$rowA['bp']."\" size=\"15\" DISABLED><input type=\"hidden\" name=\"bprice\" value=\"".$rowA['bp']."\"><input type=\"hidden\" name=\"rprice\" value=\"0\"></td>\n";
		echo "</tr>\n";
	}
	else
	{
		echo "<tr>\n";
		echo "	<td align=\"right\"><b></b></td>\n";
		echo "   <td align=\"left\"><input type=\"text\" name=\"atrib2\" value=\"".$row['atrib2']."\" size=\"64\" maxlength=\"64\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\"><b></b></td>\n";
		echo "   <td align=\"left\"><input type=\"text\" name=\"atrib3\" value=\"".$row['atrib3']."\" size=\"64\"><input type=\"hidden\" name=\"rprice\" value=\"0\"></td>\n";
		echo "</tr>\n";
	}

	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Cost:</b></td>\n";

	if ($row['matid']!=0 && $row['qtype']!=56)
	{
		echo "   <td align=\"left\"><input type=\"text\" value=\"".$rowA['bp']."\" size=\"15\" DISABLED><input type=\"hidden\" name=\"bprice\" value=\"".$rowA['bp']."\"><input type=\"hidden\" name=\"rprice\" value=\"0\"></td>\n";
	}
	else
	{
		echo "   <td align=\"left\"><input type=\"text\" name=\"bprice\" value=\"".$row['bprice']."\" size=\"15\"><input type=\"hidden\" name=\"rprice\" value=\"0\"></td>\n";
	}

	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Comm Type:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select name=\"commtype\">\n";

	while($rowE = mssql_fetch_row($resE))
	{
		if ($rowE[0]==$row['commtype'])
		{
			echo "         <option value=\"".$rowE[0]."\" SELECTED>".$rowE[1]."</option>\n";
		}
		else
		{
			echo "         <option value=\"".$rowE[0]."\">".$rowE[1]."</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Comm:</b></td>\n";
	echo "   <td align=\"left\"><input type=\"text\" name=\"crate\" value=\"".$row['crate']."\" size=\"15\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>UOM:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select name=\"mtype\">\n";

	while($rowF = mssql_fetch_row($resF))
	{
		if ($rowF[0]==$row['mtype'])
		{
			echo "         <option value=\"".$rowF[0]."\" SELECTED>".$rowF[1]."</option>\n";
		}
		else
		{
			echo "         <option value=\"".$rowF[0]."\">".$rowF[1]."</option>\n";
		}
	}

	echo "      </select>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Member of:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select name=\"raccid\">\n";
	echo "            <option value=\"0\">None</option>\n";

	while($rowD = mssql_fetch_row($resD))
	{
		if ($rowD[0]==$row['raccid'])
		{
			echo "         <option value=\"".$rowD[0]."\" SELECTED>(".$rowD[1].") ".$rowD[2]."</option>\n";
		}
		else
		{
			echo "         <option value=\"".$rowD[0]."\">(".$rowD[1].") ".$rowD[2]."</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Supercedes:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select name=\"rinvid\">\n";
	echo "            <option value=\"0\">None</option>\n";

	while($rowC = mssql_fetch_row($resC))
	{
		if ($rowC[0]==$row['rinvid'])
		{
			echo "         <option value=\"".$rowC[0]."\" SELECTED>".$rowC[1]."</option>\n";
		}
		else
		{
			echo "         <option value=\"".$rowC[0]."\">".$rowC[1]."</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"3\" align=\"right\"><input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Update\"></td>\n";
	echo "</tr>\n";
	echo "</form>\n";
	echo "</table>\n";
}

function invupd()
{
	$invid    =$_REQUEST['invid'];
	$phsid    =$_REQUEST['phsid'];
	$nphsid   =$_REQUEST['nphsid'];
	$accid    =$_REQUEST['accid'];
	$matid    =$_REQUEST['matid'];
	$item     =removecomma($_REQUEST['item']);
	$atrib1   =removecomma($_REQUEST['atrib1']);
	$atrib2   =removecomma($_REQUEST['atrib2']);
	$atrib3   =removecomma($_REQUEST['atrib3']);
	$vpno     =$_REQUEST['vpno'];
	$bprice   =$_REQUEST['bprice'];
	$rprice   =$_REQUEST['rprice'];
	$commtype =$_REQUEST['commtype'];
	$crate    =$_REQUEST['crate'];
	$mtype    =$_REQUEST['mtype'];
	$baseitem =$_REQUEST['baseitem'];
	$qtype    =$_REQUEST['qtype'];
	$raccid   =$_REQUEST['raccid'];
	$active   =$_REQUEST['active'];
	$spaitem  =$_REQUEST['spaitem'];
	$rinvid	 =$_REQUEST['rinvid'];
	$quan_calc=$_REQUEST['quan_calc'];
	$officeid =$_SESSION['officeid'];
	$MAS=$_SESSION['pb_code'];

	
	$qryA  = "UPDATE [".$MAS."inventory] SET ";
	$qryA .= "accid='$accid',";
	$qryA .= "phsid='$nphsid',";
	$qryA .= "matid='$matid',";
	$qryA .= "item='$item',";
	$qryA .= "atrib1='$atrib1',";
	$qryA .= "atrib2='$atrib2',";
	$qryA .= "atrib3='$atrib3',";
	$qryA .= "vpno='$vpno',";
	$qryA .= "mtype='$mtype',";
	$qryA .= "commtype='$commtype',";
	$qryA .= "crate='$crate',";
	$qryA .= "bprice=(CONVERT(smallmoney,'$bprice')),";
	$qryA .= "rprice=(CONVERT(smallmoney,'$rprice')),";
	$qryA .= "baseitem='$baseitem',";
	$qryA .= "qtype='$qtype',";
	$qryA .= "raccid='$raccid',";
	$qryA .= "rinvid='$rinvid',";
	$qryA .= "active='$active',";
	$qryA .= "quan_calc='$quan_calc',";
	$qryA .= "updt='".date("m/d/Y",time())."',";
	$qryA .= "usecid='".$_SESSION['securityid']."',";
	$qryA .= "spaitem='$spaitem' ";
	$qryA .= "WHERE invid='$invid';";
	$resA = mssql_query($qryA);

	$qryB = "UPDATE [".$MAS."inventory] set qtype=$qtype WHERE officeid=$officeid and phsid=$phsid AND accid=$accid";
	$resB = mssql_query($qryB);

	//echo $qryA;
	costing_maint_submenu();
	//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=pbconfig&call=inv&subq=list&phsid=$phsid\">";
}

function invupdbp()
{
	$qry = "UPDATE inventory set bprice=CONVERT(money,'".$_REQUEST['bp']."') WHERE officeid='".$_SESSION['officeid']."' and phsid='".$_REQUEST['phsid']."' AND invid='".$_REQUEST['id']."';";
	$res = mssql_query($qry);

	//echo $qry;
}

function renumseqacc($phsid) // Sequences Accessory Codes
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	$qryB = "SELECT DISTINCT(accid) FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid AND baseitem!=1";
	$resB = mssql_query($qryB);

	while($rowB=mssql_fetch_row($resB))
	{
		$v1=$rowB[0];
		if (array_key_exists($v1,$_POST))
		{
			$qry = "UPDATE [".$MAS."accpbook] SET seqnum=".$_REQUEST[$v1]." WHERE officeid=$officeid AND phsid=$phsid AND accid=$v1;";
			$res = mssql_query($qry);
			$row = mssql_fetch_row($res);
			echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=pbconfig&call=cost&subq=acc&phsid=$phsid\">";
		}
	}
}

function acclist($phsid,$order)
{
	//ini_set('display_errors','On');
	//error_reporting(E_ALL|E_STRICT);
	
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	
	$qryp   = "SELECT * FROM offices WHERE officeid=".$officeid.";";
	$resp   = mssql_query($qryp);
	$rowp 	= mssql_fetch_array($resp);
	
	if ($rowp['enquickbooks'] == 1)
	{
		$qryD   = "SELECT id,officeid,phsid,accid,item,bprice,rprice,mtype,seqnum,baseitem,raccid,rinvid,spaitem,zcharge,lrange,hrange,royrelease,qtype,quantity,ListID,EditSequence FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid ORDER BY ".$order.";";
	}
	else
	{
		$qryD   = "SELECT id,officeid,phsid,accid,item,bprice,rprice,mtype,seqnum,baseitem,raccid,rinvid,spaitem,zcharge,lrange,hrange,royrelease,qtype,quantity FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid ORDER BY ".$order.";";
	}
	$resD   = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);

	$qryE   = "SELECT DISTINCT(id) FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid";
	$resE   = mssql_query($qryE);
	$rowE   = mssql_fetch_row($resE);
	$nrowsE = mssql_num_rows($resE);

	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	      <tr>\n";
	echo "            <td class=\"ltgray_und\" align=\"left\"><b>Code</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"left\"><b>Description</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"left\"><b>Ques/Calc Type</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\"><b>Unit</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\"><b>Low</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\"><b>High</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\"><b>Ties</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\"><b>Def Calc</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"center\" valign=\"bottom\" ><b>Cost</b></td>\n";
	echo "            <td class=\"ltgray_und\" align=\"right\" colspan=\"2\"><font color=\"red\">".$nrowsE."</font> Items</b></td>\n";
	echo "         </tr>\n";

	$altc="1";
	while($rowD = mssql_fetch_row($resD))
	{
		$qryF   = "SELECT id,accid FROM [".$MAS."accpbook] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND accid='".$rowD[3]."';";
		$resF   = mssql_query($qryF);
		$rowF   = mssql_fetch_row($resF);

		$qryG   = "SELECT mid,abrv FROM mtypes WHERE mid='".$rowD[7]."';";
		$resG   = mssql_query($qryG);
		$rowG   = mssql_fetch_row($resG);
		
		$qryH   = "SELECT qtype FROM qtypes WHERE qid='".$rowD[17]."';";
		$resH   = mssql_query($qryH);
		$rowH   = mssql_fetch_row($resH);
		
		$qryI   = "SELECT COUNT(cid) as cnt FROM [".$MAS."rclinks_l] WHERE cid='".$rowD[0]."';";
		$resI   = mssql_query($qryI);
		$rowI   = mssql_fetch_array($resI);

		$tdc = "wh_und";
		echo "         <tr class=\"wh_und\">\n";
		echo "            <td align=\"left\" class=\"$tdc\" >".$rowD[3]."</td>\n";
		echo "            <td align=\"left\" class=\"$tdc\" >".$rowD[4]."</td>\n";
		echo "            <td align=\"left\" class=\"$tdc\" >".$rowH[0]."</td>\n";
		echo "            <td align=\"center\" class=\"$tdc\" >".$rowG[1]."</td>\n";
		echo "            <td align=\"center\" class=\"$tdc\" >".$rowD[14]."</td>\n";
		echo "            <td align=\"center\" class=\"$tdc\" >".$rowD[15]."</td>\n";
		echo "            <td align=\"center\" class=\"$tdc\" >".$rowI['cnt']."</td>\n";
		echo "            <td align=\"center\" class=\"$tdc\" >".$rowD[18]."</td>\n";
		echo "            <form method=\"post\">\n";
		echo "            <td align=\"center\" class=\"wh_und\" >\n";
		echo "					<input class=\"bbox\" type=\"text\" name=\"bp\" value=\"".$rowD[5]."\" size=\"10\">\n";
		echo "				</td>\n";
		echo "               <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "               <input type=\"hidden\" name=\"call\" value=\"cost\">\n";
		echo "               <input type=\"hidden\" name=\"subq\" value=\"edbp\">\n";
		echo "               <input type=\"hidden\" name=\"id\" value=\"".$rowD[0]."\">\n";
		echo "               <input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
		echo "               <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "            <td align=\"center\" class=\"wh_und\">\n";
		//echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\">\n";
		echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/save.gif\" alt=\"Update\">\n";
		echo "            </td>\n";
		echo "            </form>\n";
		echo "            <form method=\"post\">\n";
		echo "               <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "               <input type=\"hidden\" name=\"call\" value=\"cost\">\n";
		echo "               <input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
		echo "               <input type=\"hidden\" name=\"id\" value=\"".$rowD[0]."\">\n";
		echo "               <input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
		echo "               <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "            <td align=\"center\" class=\"wh_und\">\n";
		echo "				<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"Open\">\n";
		//echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Edit\">\n";
		echo "            </td>\n";
		echo "            </form>\n";
		echo "         </tr>\n";
	}
	echo "</table>\n";
	//echo "</form>\n";
}

function accadd($phsid)
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	
	$qryp1  = "SELECT rphsid FROM phasebase WHERE phsid=$phsid";
	$resp1  = mssql_query($qryp1);
	$rowp1  = mssql_fetch_row($resp1);

	$qry    = "SELECT MAX(accid) FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid";
	$res    = mssql_query($qry);
	$row    = mssql_fetch_row($res);

	$qryA   = "SELECT qid,qtype FROM qtypes WHERE active=1 ORDER BY qtype;";
	$resA   = mssql_query($qryA);

	$qryB   = "SELECT id,item FROM [".$MAS."acc] WHERE officeid=$officeid";
	$resB   = mssql_query($qryB);

	$qryC   = "SELECT DISTINCT(invid) FROM [".$MAS."inventory] WHERE officeid=$officeid AND phsid=$rowp1[0]";
	$resC   = mssql_query($qryC);

	$qryD   = "SELECT zid,name FROM zoneinfo WHERE officeid=$officeid ORDER BY zid ASC";
	$resD   = mssql_query($qryD);

	$qryE   = "SELECT mid,abrv FROM mtypes ORDER BY abrv;";
	$resE   = mssql_query($qryE);

	if ($row[0] < 1)
	{
		$maccid=$phsid*10000;
	}
	else
	{
		$maccid=$row[0]+1;
	}

	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"ins\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"$phsid\">\n";
	echo "<input type=\"hidden\" name=\"rprice\" value=\"0.00\">\n";
	echo "<input type=\"hidden\" name=\"zcharge\" value=\"0\">\n";
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Code:</b></td>\n";
	echo "   <td><input tabindex=\"1\" type=\"text\" name=\"accid\" size=\"10\" value=\"".$maccid."\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Description:</b></td>\n";
	echo "   <td><input tabindex=\"1\" type=\"text\" name=\"item\" size=\"64\" maxlength=\"64\"></td>\n";
	echo "   <td rowspan=\"10\" valign=\"top\" align=\"right\">\n";
	echo "      <table>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"left\" colspan=\"2\"><b><i>Code Controls:</i></b></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Base Item:</b></td>\n";
	echo "            <td>\n";
	echo "               <select tabindex=\"10\" name=\"baseitem\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Question Type:</b></td>\n";
	echo "            <td>\n";
	echo "               <select tabindex=\"11\" name=\"qtype\">\n";
	echo "                  <option value=\"0\" SELECTED>None</option>\n";

	while($rowA = mssql_fetch_row($resA))
	{
		echo "                  <option value=\"$rowA[0]\">$rowA[1]</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Calc Quantity:</b></td>\n";
	echo "            <td><input tabindex=\"12\" type=\"text\" name=\"quantity\" value=\"1\" size=\"4\" maxlength=\"4\"> (Overage, ext sqft, etc)</td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Spa Item:</b></td>\n";
	echo "            <td>\n";
	echo "               <select tabindex=\"13\" name=\"spaitem\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Zone Charge:</b></td>\n";
	echo "            <td>\n";
	echo "               <select tabindex=\"14\" name=\"zcharge\">\n";

	while($rowD = mssql_fetch_row($resD))
	{
		if ($rowD[0]==0)
		{
			echo "                  <option value=\"$rowD[0]\" SELECTED>No</option>\n";
		}
		else
		{
			echo "                  <option value=\"$rowD[0]\">$rowD[1]</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Royalty Release:</b></td>\n";
	echo "            <td>\n";
	echo "               <select tabindex=\"15\" name=\"royrelease\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "      </table>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td><input tabindex=\"2\" type=\"text\" name=\"atrib1\" size=\"64\" maxlength=\"64\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td><input tabindex=\"3\" type=\"text\" name=\"atrib2\" size=\"64\" maxlength=\"64\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td><input tabindex=\"4\" type=\"text\" name=\"atrib3\" size=\"64\" maxlength=\"64\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>LRange:</b></td>\n";
	echo "   <td><input tabindex=\"5\" type=\"text\" name=\"lrange\" size=\"21\" maxlength=\"20\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>HRange:</b></td>\n";
	echo "   <td><input tabindex=\"6\" type=\"text\" name=\"hrange\" size=\"21\" maxlength=\"20\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Cost:</b></td>\n";
	echo "   <td><input tabindex=\"7\" type=\"text\" name=\"bprice\" size=\"15\" value=\"0.00\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>UOM:</b></td>\n";
	echo "   <td>\n";
	echo "      <select tabindex=\"8\" name=\"mtype\">\n";

	while($rowE = mssql_fetch_row($resE))
	{
		echo "         <option value=\"$rowE[0]\">$rowE[1]</option>\n";
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Member of:</b></td>\n";
	echo "   <td>\n";
	echo "      <select tabindex=\"16\" name=\"raccid\">\n";
	echo "            <option value=\"0\" SELECTED>None</option>\n";

	while($rowB = mssql_fetch_row($resB))
	{
		echo "         <option value=\"$rowB[0]\">$rowB[1]</option>\n";
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Supercedes:</b></td>\n";
	echo "   <td>\n";
	echo "      <select tabindex=\"9\" name=\"rinvid\">\n";
	echo "            <option value=\"0\" SELECTED>None</option>\n";

	while($rowC = mssql_fetch_row($resC))
	{
		echo "         <option value=\"$rowC[0]\">$rowC[0]</option>\n";
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"3\" align=\"center\"><button tabindex=\"15\" type=\"submit\">&nbsp;&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;&nbsp;</button></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function accins()
{
	$MAS	=$_SESSION['pb_code'];
	$qry    = "SELECT MAX(accid) FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$_REQUEST['phsid']."';";
	$res    = mssql_query($qry);
	$row    = mssql_fetch_row($res);

	if ($row[0] < 1)
	{
		$maccid=$_REQUEST['phsid'] * 10000;
	}
	else
	{
		$maccid=$row[0]+1;
	}

	$phsid    	=$_REQUEST['phsid'];
	$accid    	=$maccid;
	$item     	=replacecomma($_REQUEST['item']);
	$atrib1  	=replacecomma($_REQUEST['atrib1']);
	$atrib2   	=replacecomma($_REQUEST['atrib2']);
	$atrib3   	=$_REQUEST['atrib3'];
	$lrange   	=$_REQUEST['lrange'];
	$hrange   	=$_REQUEST['hrange'];
	$bprice   	=$_REQUEST['bprice'];
	$rprice   	=$_REQUEST['rprice'];
	$mtype    	=$_REQUEST['mtype'];
	$baseitem	=$_REQUEST['baseitem'];
	$qtype    	=$_REQUEST['qtype'];
	$quantity 	=$_REQUEST['quantity'];
	$raccid   	=$_REQUEST['raccid'];
	$rinvid   	=$_REQUEST['rinvid'];
	$spaitem  	=$_REQUEST['spaitem'];
	$zcharge    =$_REQUEST['zcharge'];
	$royrelease =$_REQUEST['royrelease'];
	$officeid 	=$_SESSION['officeid'];

	$qry  = "INSERT INTO [".$MAS."accpbook] ";
	$qry .= "(";
	$qry .= "officeid,";
	$qry .= "accid,";
	$qry .= "phsid,";
	$qry .= "item,";
	$qry .= "atrib1,";
	$qry .= "atrib2,";
	$qry .= "atrib3,";
	$qry .= "lrange,";
	$qry .= "hrange,";
	$qry .= "mtype,";
	$qry .= "bprice,";
	$qry .= "rprice,";
	$qry .= "baseitem,";
	$qry .= "qtype,";
	$qry .= "quantity,";
	$qry .= "raccid,";
	$qry .= "royrelease,";
	$qry .= "usecid,";
	$qry .= "updt,";
	$qry .= "spaitem";
	$qry .= ")";
	$qry .= "VALUES";
	$qry .= "(";
	$qry .= "'$officeid',";
	$qry .= "'$accid',";
	$qry .= "'$phsid',";
	$qry .= "'$item',";
	$qry .= "'$atrib1',";
	$qry .= "'$atrib2',";
	$qry .= "'$atrib3',";
	$qry .= "'$lrange',";
	$qry .= "'$hrange',";
	$qry .= "'$mtype',";
	$qry .= "(CONVERT(smallmoney,'$bprice')),";
	$qry .= "(CONVERT(smallmoney,'$rprice')),";
	$qry .= "'$baseitem',";
	$qry .= "'$qtype',";
	$qry .= "'$quantity',";
	$qry .= "'$raccid',";
	$qry .= "'$royrelease',";
	$qry .= "'".$_SESSION['securityid']."',";
	$qry .= "getdate(),";
	$qry .= "'$spaitem'";
	$qry .= ");";

	$res = mssql_query($qry);
	
	//echo $qry;

	//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=pbconfig&call=cost&subq=acc&phsid=$phsid\">";
}

function accdel($id,$phsid)
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	$qry = "SELECT id,accid,item,bprice,mtype,officeid,phsid,baseitem,rprice,lrange,hrange,raccid,spaitem FROM [".$MAS."accpbook] WHERE id='$id';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);

	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"del2\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$row[0]\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"$row[5]\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"$row[6]\">\n";
	echo "<font color=\"red\"><b>Confirm Delete:</b></font> (Can be re-added)\n";
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Accessory Code:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[1]\" size=\"5\" maxlength=\"4\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Description:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[2]\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>LRange:</b></td><td><input class=\"critical\" type=\"text\" name=\"lrange\" value=\"$row[9]\" size=\"21\" maxlength=\"20\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>HRange:</b></td><td><input class=\"critical\" type=\"text\" name=\"hrange\" value=\"$row[10]\" size=\"21\" maxlength=\"20\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Cost:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[3]\" size=\"15\"></td><td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Retail:</b></td><td><input class=\"critical\" type=\"text\" name=\"rprice\" size=\"15\" value=\"0.00\"></td><td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Unit:</b></td><td><input class=\"critical\" type=\"text\" value=\"$row[8]\" size=\"10\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Base Item:</b></td>\n";

	if ($row[7]==1)
	{
		echo "<td>Yes</td>\n";
	}
	else
	{
		echo "<td>No</td>\n";
	}

	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Rel Option:</b></td><td>$row[11]</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	 <td align=\"right\"><b>Rel Inv:</b></td><td>$row[12]</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Spa Item:</b></td><td>\n";
	if ($row[12]==1)
	{
		echo "Yes";
	}
	else
	{
		echo "No</td>\n";
	}
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"2\" align=\"right\"><button type=\"submit\">Delete</button></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function accdel2($id,$phsid)
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	$qry = "DELETE FROM [".$MAS."accpbook] WHERE id='$id';";
	$res = mssql_query($qry);
	//$row = mssql_fetch_row($res);
	echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=pbconfig&call=cost&subq=acc&phsid=$phsid\">";

}

function acced($id,$phsid)
{
	$MAS=$_SESSION['pb_code'];
	$officeid =$_SESSION['officeid'];
	$pid=0;
	
	$qryp  = "SELECT officeid,pb_code,accountingsystem,enmas,enquickbooks FROM offices WHERE officeid=".(int) $_SESSION['officeid'].";";
	$resp  = mssql_query($qryp);
	$rowp  = mssql_fetch_array($resp);

	$qryp1  = "SELECT rphsid FROM phasebase WHERE phsid=$phsid";
	$resp1  = mssql_query($qryp1);
	$rowp1  = mssql_fetch_row($resp1);

	if ($rowp['enquickbooks'] == 1)
	{
		$qry  = "SELECT id,accid,item,bprice,rprice,mtype,officeid,phsid,baseitem,lrange,hrange,qtype,quantity,raccid,rinvid,spaitem,zcharge,atrib1,atrib2,atrib3,royrelease,updt,usecid,ListID,EditSequence FROM [".$MAS."accpbook] WHERE id='".$id."';";
	}
	else
	{
		$qry  = "SELECT id,accid,item,bprice,rprice,mtype,officeid,phsid,baseitem,lrange,hrange,qtype,quantity,raccid,rinvid,spaitem,zcharge,atrib1,atrib2,atrib3,royrelease,updt,usecid FROM [".$MAS."accpbook] WHERE id='".$id."';";
	}
	
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);
	
	//echo $qry.'<br>';

	$qryB = "SELECT qid,qtype FROM qtypes WHERE active=1 ORDER BY qtype;";
	$resB = mssql_query($qryB);

	$qryC = "SELECT officeid,rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND cid='".$id."';";
	$resC = mssql_query($qryC);
	$nrowC= mssql_num_rows($resC);
	$sizeC=$nrowC+1;

	$qryD = "SELECT id,accid,item FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid!='".$row[1]."';";
	$resD = mssql_query($qryD);

	$qryE = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC";
	$resE = mssql_query($qryE);

	$qryF = "SELECT mid,abrv FROM mtypes ORDER BY abrv;";
	$resF = mssql_query($qryF);
	//echo $row[21]."<br>";

	if (strtotime($row[21]) > strtotime('1/1/1999') && isset($row[22]) && $row[22] !=0)
	{
		$qryG	= "SELECT securityid,lname,fname FROM security WHERE securityid='".$row[22]."';";
		$resG	= mssql_query($qryG);
		$rowG	= mssql_fetch_array($resG);

		$ufname	=$rowG['fname'];
		$ulname	=$rowG['lname'];
		$udate	=date("m/d/Y",strtotime($row[21]));
		$updby	=$ulname.",".$ufname." on ".$udate;
	}
	else
	{
		$updby	="";
	}

	if ($_SESSION['m_plev'] >=8)
	{
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"ed2\">\n";
		echo "<input type=\"hidden\" name=\"id\" id=\"iid\" value=\"".$row[0]."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" id=\"oid\" value=\"".$row[6]."\">\n";
		echo "<input type=\"hidden\" name=\"phsid\" value=\"".$row[7]."\">\n";
		echo "<input type=\"hidden\" name=\"rprice\" value=\"0.00\">\n";
	}

	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "<tr>\n";
	echo "   <td align=\"right\">Last Update:</td>\n";
	echo "   <td>\n";
	
	echo $updby;
	
	echo "	</td>\n";
	echo "   <td rowspan=\"12\" valign=\"top\" align=\"right\">\n";
	echo "      <table>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"left\" colspan=\"2\"><b><i>Calc & Display Controls:</i></b></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Base Item:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"11\" name=\"baseitem\">\n";

	if ($row[8]==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Calc/Ques Type:<b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"12\" name=\"qtype\">\n";

	while($rowB = mssql_fetch_row($resB))
	{
		if ($row[11]==$rowB[0])
		{
			echo "                  <option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$rowB[0]."\">".$rowB[1]."</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Calc Quantity:</b></td>\n";
	echo "            <td align=\"left\"><input type=\"text\" name=\"quantity\" value=\"".$row[12]."\" size=\"4\" maxlength=\"4\"> (ex:Overage)</td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Spa Item:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"13\" name=\"spaitem\">\n";

	if ($row[15]==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Zone Charge:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"14\" name=\"zcharge\">\n";

	while($rowE = mssql_fetch_row($resE))
	{
		if ($rowE[0]==0)
		{
			echo "                  <option value=\"".$rowE[0]."\" SELECTED>No</option>\n";
		}
		elseif ($rowE[0]==$row[16])
		{
			echo "                  <option value=\"".$rowE[0]."\" SELECTED>".$rowE[1]."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$rowE[0]."\">".$rowE[1]."</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Royalty Release:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"15\" name=\"royrelease\">\n";

	if ($row[20]==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\" valign=\"top\"><b>Related Retail</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "            <select tabindex=\"16\" name=\"raccid\" size=\"$sizeC\">\n";

	while($rowC = mssql_fetch_array($resC))
	{
		$qryG = "SELECT id,item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rowC['rid']."';";
		$resG = mssql_query($qryG);
		$rowG = mssql_fetch_array($resG);

		echo "         <option value=\"".$rowG['id']."\">".$rowG['item']."</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	
	if ($rowp['enquickbooks']==1)
	{
		$istatus='';
		
		if (isset($row[23]) and strlen($row[23]) > 3)
		{
			echo "         <tr>\n";
			echo "	         <td align=\"right\" valign=\"top\"><b>JMS ID</b></td>\n";
			echo "            <td align=\"left\">".$row[0]."</td>\n";
			echo "         </tr>\n";
			echo "         <tr>\n";
			echo "	         <td align=\"right\" valign=\"top\"><b>QB ID</b></td>\n";
			echo "            <td align=\"left\">".$row[23]."</td>\n";
			echo "         </tr>\n";
			echo "         <tr>\n";
			echo "	         <td align=\"right\" valign=\"top\"><b>QB ES</b></td>\n";
			echo "            <td align=\"left\">".$row[24]."</td>\n";
			echo "         </tr>\n";
		}
		else
		{
			$qryqb = "SELECT * FROM qbwcConfig WHERE oid=".$_SESSION['officeid'].";";
			$resqb = mssql_query($qryqb);
			$rowqb = mssql_fetch_array($resqb);
			
			$qbs_db=array('hostname'=>$rowqb['qb_soap_host'],'username'=>$rowqb['qb_soap_user'],'password'=>$rowqb['qb_soap_pass'],'dbname'=>$rowqb['qb_soap_db']);
			
			$istatus=get_Item_qb_status($_SESSION['officeid'],$row[0],'ItemServiceAdd',$qbs_db);
			
			echo "			<tr>\n";
			echo "				<td align=\"right\" valign=\"top\"><b>JMS ID</b></td>\n";
			echo "				<td align=\"left\">".$row[0]."</td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><div id=\"SyncServiceItem\">Synchronize <img src=\"images/arrow_refresh.png\"> <b>QB ID</b></td>\n";
			echo "				<td align=\"left\" valign=\"top\" rowspan=\"2\">\n";
			
			echo "					<input type=\"hidden\" id=\"qaction\" value=\"ItemServiceAdd\">\n";
			echo "					<table width=\"100%\">\n";
			echo "						<tr>\n";
			echo "							<td align=\"left\"</td>\n";
			echo "							<td valign=\"top\"></div></td>\n";
			echo "						</tr>\n";
			echo "						<tr>\n";
			echo "							<td align=\"left\" valign=\"top\" colspan=\"2\"><div id=\"textbox_SingleCostConfigStatus\"><b>".$istatus."</b></div></td>\n";
			echo "						</tr>\n";
			echo "					</table>\n";
			
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"right\"><b>QB ES</b></td>\n";
			echo "			</tr>\n";
		}
	}
	
	echo "      </table>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Code:</b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"1\" type=\"text\" name=\"accid\" value=\"".$row[1]."\" size=\"10\" maxlength=\"10\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Description:</b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"2\" type=\"text\" name=\"item\" value=\"".$row[2]."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"3\" type=\"text\" name=\"atrib1\" value=\"".$row[17]."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"4\" type=\"text\" name=\"atrib2\" value=\"".$row[18]."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"5\" type=\"text\" name=\"atrib3\" value=\"".$row[19]."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>LRange:</b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"6\" type=\"text\" name=\"lrange\" value=\"".$row[9]."\" size=\"21\" maxlength=\"20\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>HRange:</b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"7\" type=\"text\" name=\"hrange\" value=\"".$row[10]."\" size=\"21\" maxlength=\"20\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Cost:</b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"8\" type=\"text\" name=\"bprice\" value=\"".$row[3]."\" size=\"15\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Units:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select tabindex=\"9\" name=\"mtype\">\n";

	while($rowF = mssql_fetch_array($resF))
	{
		if ($rowF['mid']==$row[5])
		{
			echo "         <option value=\"".$rowF['mid']."\" SELECTED>".$rowF['abrv']."</option>\n";
		}
		else
		{
			echo "         <option value=\"".$rowF['mid']."\">".$rowF['abrv']."</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Supercedes:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select tabindex=\"10\" name=\"rinvid\">\n";
	echo "            <option value=\"0\">None</option>\n";

	while($rowD = mssql_fetch_row($resD))
	{
		if ($rowD[0]==$row[14])
		{
			echo "         <option value=\"".$rowD[0]."\" SELECTED>(".$rowD[1].") - ".$rowD[2]."</option>\n";
		}
		else
		{
			echo "         <option value=\"".$rowD[0]."\">(".$rowD[1].") - ".$rowD[2]."</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";

	if ($_SESSION['m_plev'] >=8)
	{
		echo "<tr>\n";
		echo "   <td colspan=\"3\" align=\"center\"><button tabindex=\"17\" type=\"submit\">Update Labor Item</button></td>\n";
		echo "</tr>\n";
		echo "</form>\n";
	}

	echo "</table>\n";

	if ($row[8]==0)
	{
		if ($row[11]==9||$row[11]==10||$row[11]==11||$row[11]==12)
		{
			$qry0 = "SELECT id,linkid,officeid,lrange,hrange,bprice FROM [specaccpbook] WHERE officeid='".$_SESSION['officeid']."' AND linkid='".$row[1]."' ORDER BY hrange ASC;";
			$res0 = mssql_query($qry0);
			$nrow0= mssql_num_rows($res0);

			echo "<br>\n";
			//echo $qry0."<br>";
			echo "<table class=\"outer\" width=\"85%\">\n";
			echo "	<tr>\n";
			echo "		<td valign=\"top\" align=\"left\">\n";
			echo "<table width=\"100%\">\n";
			echo "	<tr>\n";
			echo "		<td colspan=\"5\" align=\"left\"><b>Special Display & Calc Method Detail</b></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"ltgray_und\" align=\"left\"><b>Code</b></td>\n";
			echo "		<td class=\"ltgray_und\" align=\"left\"><b>LRange</b></td>\n";
			echo "		<td class=\"ltgray_und\" align=\"left\"><b>HRange</b></td>\n";
			echo "		<td class=\"ltgray_und\" align=\"left\"><b>Price</b></td>\n";
			echo "		<td class=\"ltgray_und\" align=\"left\"></td>\n";
			echo "		<td class=\"ltgray_und\" align=\"left\"></td>\n";
			echo "	</tr>\n";

			if ($nrow0 > 0)
			{
				while ($row0=mssql_fetch_array($res0))
				{
					echo "	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "	<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "	<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
					echo "	<input type=\"hidden\" name=\"subq\" value=\"editspecaccpbook\">\n";
					echo "	<input type=\"hidden\" name=\"itemid\" value=\"".$row0['id']."\">\n";
					echo "	<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
					echo "	<input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
					echo "	<tr>\n";
					echo "		<td valign=\"top\" align=\"left\"><input class=\"bboxl\" type=\"text\" value=\"".$row0['linkid']."\" size=\"10\" DISABLED></td>\n";
					echo "		<td valign=\"top\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"lrange\" value=\"".$row0['lrange']."\" size=\"10\"></td>\n";
					echo "		<td valign=\"top\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"hrange\" value=\"".$row0['hrange']."\" size=\"10\"></td>\n";
					echo "		<td valign=\"top\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"bprice\" value=\"".$row0['bprice']."\" size=\"10\"></td>\n";
					echo "		<td valign=\"top\" align=\"left\"><button type=\"submit\">Edit</button></td>\n";
					echo "	</form>\n";
					echo "	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "	<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "	<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
					echo "	<input type=\"hidden\" name=\"subq\" value=\"deletespecaccpbook\">\n";
					echo "	<input type=\"hidden\" name=\"itemid\" value=\"".$row0['id']."\">\n";
					echo "	<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
					echo "	<input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
					echo "		<td valign=\"top\" align=\"left\"><button type=\"submit\">Delete</button></td>\n";
					echo "	</tr>\n";
					echo "	</form>\n";
				}
			}

			echo "	<tr>\n";
			echo "		<td colspan=\"6\" align=\"left\"><hr width=\"100%\"></td>\n";
			echo "	</tr>\n";
			echo "	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "	<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "	<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
			echo "	<input type=\"hidden\" name=\"subq\" value=\"addspecaccpbook\">\n";
			echo "	<input type=\"hidden\" name=\"linkid\" value=\"".$row[1]."\" size=\"10\">\n";
			echo "	<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
			echo "	<input type=\"hidden\" name=\"phsid\" value=\"".$phsid."\">\n";
			echo "	<tr>\n";
			echo "		<td align=\"left\"><input class=\"bboxl\" type=\"text\" value=\"".$row[1]."\" size=\"10\" DISABLED></td>\n";
			echo "		<td align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"lrange\" value=\"0\" size=\"10\"></td>\n";
			echo "		<td align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"hrange\" value=\"0\" size=\"10\"></td>\n";
			echo "		<td align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"bprice\" value=\"0\" size=\"10\"></td>\n";
			echo "		<td colspan=\"2\" align=\"left\"><button type=\"submit\">Add New Entry</button></td>\n";
			echo "	</tr>\n";
			echo "	</form>\n";
			echo "			</table>\n";
			echo "   	</td>\n";
			echo "	</tr>\n";
			echo "</table>\n";

		}
	}

}

function accupd()
{
	$MAS		=$_SESSION['pb_code'];
	$id			=$_REQUEST['id'];
	//$catid		=$_REQUEST['catid'];
	$phsid		=$_REQUEST['phsid'];
	$accid		=$_REQUEST['accid'];
	$item		=removecomma($_REQUEST['item']);
	$atrib1		=removecomma($_REQUEST['atrib1']);
	$atrib2		=removecomma($_REQUEST['atrib2']);
	$atrib3		=removecomma($_REQUEST['atrib3']);
	$lrange		=$_REQUEST['lrange'];
	$hrange		=$_REQUEST['hrange'];
	$bprice		=$_REQUEST['bprice'];
	$rprice		=$_REQUEST['rprice'];
	$mtype		=$_REQUEST['mtype'];
	$baseitem		=$_REQUEST['baseitem'];
	$qtype		=$_REQUEST['qtype'];
	$quantity		=$_REQUEST['quantity'];
	$rinvid		=$_REQUEST['rinvid'];
	$spaitem		=$_REQUEST['spaitem'];
	$royrelease	=$_REQUEST['royrelease'];
	$officeid		=$_SESSION['officeid'];

	$qryA  = "UPDATE [".$MAS."accpbook] SET ";
	$qryA .= "item='$item',";
	//$qryA .= "catid='$catid',";
	$qryA .= "accid='$accid',";
	$qryA .= "atrib1='$atrib1',";
	$qryA .= "atrib2='$atrib2',";
	$qryA .= "atrib3='$atrib3',";
	$qryA .= "lrange='$lrange',";
	$qryA .= "hrange='$hrange',";
	$qryA .= "bprice=(CONVERT(smallmoney,'$bprice')),";
	$qryA .= "rprice=(CONVERT(smallmoney,'$rprice')),";
	$qryA .= "mtype='$mtype',";
	$qryA .= "baseitem='$baseitem',";
	$qryA .= "qtype='$qtype',";
	$qryA .= "quantity='$quantity',";
	$qryA .= "rinvid='$rinvid',";
	$qryA .= "royrelease='$royrelease',";
	$qryA .= "usecid='".$_SESSION['securityid']."',";
	$qryA .= "updt='".date("m/d/Y",time())."',";
	$qryA .= "spaitem='$spaitem' ";
	$qryA .= "WHERE id='$id';";
	$resA = mssql_query($qryA);
	//$rowA = mssql_fetch_row($resA);

	$qryB = "SELECT id,accid from [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid AND id=$id;";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryC = "update [".$MAS."accpbook] set qtype=$qtype,quantity=$quantity where officeid=$officeid and phsid=$phsid AND accid=$rowB[1]";
	$resC = mssql_query($qryC);
	//$rowC = mssql_fetch_row($resC);
	//echo $qryC."<br>";

	costing_maint_submenu();
	//echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=pbconfig&call=cost&subq=acc&phsid=$phsid\">";
}

function accupdbp()
{
	$MAS=$_SESSION['pb_code'];
	$qry = "update [".$MAS."accpbook] set bprice=CONVERT(money,'".$_REQUEST['bp']."'),usecid='".$_SESSION['securityid']."',updt='".date("m/d/Y",time())."' where officeid='".$_SESSION['officeid']."' and phsid='".$_REQUEST['phsid']."' AND id='".$_REQUEST['id']."';";
	$res = mssql_query($qry);
}

function resequence_acc() // Sequences Accessory Codes
{
	$MAS=$_SESSION['pb_code'];
	$qryB = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['catid']."' ORDER BY seqn;";
	$resB = mssql_query($qryB);

	$iseq=1;
	while($rowB=mssql_fetch_row($resB))
	{
		$qry = "UPDATE [".$MAS."acc] SET seqn=".$iseq." WHERE officeid='".$_SESSION['officeid']."' AND id='".$rowB[0]."';";
		$res = mssql_query($qry);
		$iseq++;
	}
	//acc_code_list();
}

function resequence_acc_inc() // Sequences Accessory Codes
{
	//echo "RESYNCH<BR>";
	$MAS=$_SESSION['pb_code'];
	$pid=0;
	
	$qryA = "SELECT * FROM [".$MAS."acc] WHERE officeid=".(int) $_SESSION['officeid']." AND id=".(int) $_REQUEST['id'].";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if ($rowA['qtype']!=32) {
		$qryAa = "SELECT TOP 1 id FROM [".$MAS."acc] WHERE officeid=".(int) $_SESSION['officeid']." AND catid=".(int) $rowA['catid']." AND qtype=32 AND seqn < ".(int) $rowA['seqn']." ORDER BY seqn DESC;";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		$pid=$rowAa['id'];
	}

	$n1seq=$rowA['seqn']+1;

	$qryB = "SELECT * FROM [".$MAS."acc] WHERE officeid=".(int) $_SESSION['officeid']." AND catid=".(int) $rowA['catid']." AND seqn=".(int) $n1seq.";";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
	$nrowB= mssql_num_rows($resB);

	if ($nrowB==1) {
		$n2seq=$rowB['seqn']-1;

		$qryC = "UPDATE [".$MAS."acc] SET seqn=".$n1seq.",pid=".$pid." WHERE officeid=".(int) $_SESSION['officeid']." AND id=".(int) $_REQUEST['id'].";";
		$resC = mssql_query($qryC);

		$qryD = "UPDATE [".$MAS."acc] SET seqn=".$n2seq.",pid=".$pid." WHERE officeid=".(int) $_SESSION['officeid']." AND id=".(int) $rowB['id'].";";
		$resD = mssql_query($qryD);
	}
	elseif ($nrowB < 1 || $nrowB > 1) { //Resquences all items in a Category if a missing or duplicate sequence number is detected
		$qryC = "SELECT id,seqn FROM [".$MAS."acc] WHERE officeid=".(int) $_SESSION['officeid']." AND catid=".(int) $rowA['catid']." ORDER BY seqn;";
		$resC = mssql_query($qryC);

		while ($rowC = mssql_fetch_array($resC)) {
			$sarr[]=$rowC[0];
		}

		for ($i=1;$i<=count($sarr);$i++) {
			$arrkey=$i-1;
			$qryD = "UPDATE [".$MAS."acc] SET seqn=".$i.",pid=".$pid." WHERE officeid='".$_SESSION['officeid']."' AND id='".$sarr[$arrkey]."';";
			$resD = mssql_query($qryD);
		}

		// Following Code reprossesses original request
		$qryE = "SELECT id,seqn,catid FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
		$resE = mssql_query($qryE);
		$rowE = mssql_fetch_array($resE);

		$n3seq=$rowE['seqn']+1;

		$qryF = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowA['catid']."' AND seqn='".$n3seq."';";
		$resF = mssql_query($qryF);
		$rowF = mssql_fetch_array($resF);

		$n4seq=$rowF['seqn']-1;

		$qryG = "UPDATE [".$MAS."acc] SET seqn=".$n3seq.",pid=".$pid." WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
		$resG = mssql_query($qryG);

		$qryH = "UPDATE [".$MAS."acc] SET seqn=".$n4seq.",pid=".$pid." WHERE officeid='".$_SESSION['officeid']."' AND id='".$rowF['id']."';";
		$resH = mssql_query($qryH);
	}

	acc_code_list();
}

function resequence_acc_dec() {// Sequences Accessory Codes
	$MAS=$_SESSION['pb_code'];
	$pid=0;
	
	$qryA = "SELECT id,seqn,catid,qtype FROM [".$MAS."acc] WHERE officeid=".(int) $_SESSION['officeid']." AND id=".(int) $_REQUEST['id'].";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	if ($rowA['qtype']!=32) {
		$qryAa = "SELECT TOP 1 id FROM [".$MAS."acc] WHERE officeid=".(int) $_SESSION['officeid']." AND catid=".(int) $rowA['catid']." AND qtype=32 AND seqn < ".(int) $rowA['seqn']." ORDER BY seqn DESC;";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		$pid=$rowAa['id'];
	}

	$n1seq=$rowA['seqn']-1;

	$qryB = "SELECT * FROM [".$MAS."acc] WHERE officeid=".(int) $_SESSION['officeid']." AND catid=".(int) $rowA['catid']." AND seqn=".(int) $n1seq.";";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
	$nrowB= mssql_num_rows($resB);

	if ($nrowB==1)
	{
		$n2seq=$rowB['seqn']+1;

		$qryC = "UPDATE [".$MAS."acc] SET seqn=".$n1seq.",pid=".$pid." WHERE officeid=".(int) $_SESSION['officeid']." AND id=".(int) $_REQUEST['id'].";";
		$resC = mssql_query($qryC);

		$qryD = "UPDATE [".$MAS."acc] SET seqn=".$n2seq.",pid=".$pid." WHERE officeid=".(int) $_SESSION['officeid']." AND id=".(int) $rowB['id'].";";
		$resD = mssql_query($qryD);
	}
	elseif ($nrowB < 1 || $nrowB > 1) //Resquences all items in a Category if a missing or duplicate sequence number is detected
	{
		$qryC = "SELECT id,seqn FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowA['catid']."' ORDER BY seqn;";
		$resC = mssql_query($qryC);

		while ($rowC = mssql_fetch_array($resC))
		{
			$sarr[]=$rowC[0];
		}

		for ($i=1;$i<=count($sarr);$i++)
		{
			$arrkey=$i-1;
			$qryD = "UPDATE [".$MAS."acc] SET seqn=".$i.",pid=".$pid." WHERE officeid='".$_SESSION['officeid']."' AND id='".$sarr[$arrkey]."';";
			$resD = mssql_query($qryD);
		}

		// Following Code reprossesses original request
		$qryE = "SELECT id,seqn,catid FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
		$resE = mssql_query($qryE);
		$rowE = mssql_fetch_array($resE);

		$n3seq=$rowE['seqn']-1;

		$qryF = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowA['catid']."' AND seqn='".$n3seq."';";
		$resF = mssql_query($qryF);
		$rowF = mssql_fetch_array($resF);

		$n4seq=$rowF['seqn']+1;

		$qryG = "UPDATE [".$MAS."acc] SET seqn=".$n3seq.",pid=".$pid." WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
		$resG = mssql_query($qryG);

		$qryH = "UPDATE [".$MAS."acc] SET seqn=".$n4seq.",pid=".$pid." WHERE officeid='".$_SESSION['officeid']."' AND id='".$rowF['id']."';";
		$resH = mssql_query($qryH);
	}

	acc_code_list();
}

function acc_code_list()
{

	$MAS=$_SESSION['pb_code'];
	if ($_SESSION['tlev'] < 1 && $_SESSION['m_plev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	if (!isset($_GET['order'])||empty($_GET['order']))
	{
		$order="seqn";
	}
	else
	{
		$order=$_GET['order'];
	}

	$disabled="AND disabled!=1 ";
	if (isset($_REQUEST['disabled']) && $_REQUEST['disabled'] == 1)
	{
		$disabled="";
	}

	if ($_SESSION['m_plev'] < 8)
	{
		$disabled="AND disabled!=1 ";
	}

	$qryB		= "SELECT catid,name,officeid FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND active=1 AND catid!=0 ORDER BY seqn;";
	$resB		= mssql_query($qryB);
	$nrowB	= mssql_num_rows($resB);

	if ($nrowB < 1)
	{
		echo "<font color=\"red\"><b>Error!</b></font> You must have at least 1 Active Category!\n";
		exit;
	}

	$qryC   = "SELECT officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC   = mssql_query($qryC);
	$rowC   = mssql_fetch_array($resC);

	if (isset($_REQUEST['catid']) && !empty($_REQUEST['catid']))
	{
		$qryD   = "SELECT id,aid,officeid,accpbook,item,seqn,bp,rp,phsid,catid,qtype,subid,disabled,royrelease,bullet FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['catid']."' ".$disabled."ORDER BY $order;";

	}
	else
	{
		$qryD   = "SELECT id,aid,officeid,accpbook,item,seqn,bp,rp,phsid,catid,qtype,subid,disabled,royrelease,bullet FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='0' ".$disabled."ORDER BY $order;";
	}
	$resD   = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);

	echo "		<table width=\"950px\">\n";
	echo "			<tr>\n";
	echo "				<td>\n";
	echo "					<table class=\"outer\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"left\" colspan=\"2\"><b>Retail Pricing (Accessory) List for ".$rowC['name']."</b></td>\n";
	echo "            				<td class=\"gray\" align=\"right\">\n";

	if ($_SESSION['tlev'] >=1 && $_SESSION['m_plev'] >= 8)
	{
		echo "            					<form method=\"post\">\n";
		echo "            					<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "            					<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "            					<input type=\"hidden\" name=\"catid\" value=\"0\">\n";
		echo "            					<select name=\"subq\" onChange=\"this.form.submit();\">\n";
		echo "            						<option value=\"add\">Add Retail Item From...</option>\n";
		echo "            						<option value=\"add\">Blank Form</option>\n";
		echo "            						<option value=\"add_rmm1\">Material List</option>\n";
		echo "            					</select>\n";
		echo "            					</form>\n";
	}
	
	echo "            				</td>\n";
	echo "            				<td class=\"gray\"><img src=\"images/pixel.gif\"></td>\n";
	echo "         				</tr>\n";
	echo "	      				<tr>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Search</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\">\n";
	echo "								<form method=\"post\">\n";
	echo "								<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "								<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
	echo "								<input type=\"hidden\" name=\"subq\" value=\"search\">\n";
	echo "								<input type=\"text\" name=\"stext\" size=\"24\" maxlength=\"25\">\n";
	echo "								<input class=\"checkboxgry\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "            					</form>\n";
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\">\n";
	echo "      						<form method=\"post\">\n";
	echo "      						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "      						<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
	echo "      						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "      						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
	echo "      						<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
	
	if (isset($_REQUEST['disabled']) && $_REQUEST['disabled']==1)
	{
		echo "								Incl Disabled <input class=\"checkboxgry\" type=\"checkbox\" name=\"disabled\" value=\"1\" CHECKED title=\"Check to include Disabled Items\">\n";
	}
	else
	{
		echo "								Incl Disabled <input class=\"checkboxgry\" type=\"checkbox\" name=\"disabled\" value=\"1\" title=\"Check to include Disabled Items\">\n";
	}
	
	echo "              				<select name=\"catid\" onChange=\"this.form.submit();\">\n";
	echo "									<option value=\"0\">Select Retail Category...</option>\n";
	echo "									<option value=\"0\">-----------</option>\n";
	echo "									<option value=\"0\">None</option>\n";

	while ($rowB = mssql_fetch_row($resB))
	{
		if (isset($_REQUEST['catid']) && !empty($_REQUEST['catid']) && $_REQUEST['catid']==$rowB[0])
		{
			echo "										<option value=\"$rowB[0]\" SELECTED>$rowB[1]</option>\n";
		}
		else
		{
			echo "										<option value=\"$rowB[0]\">$rowB[1]</option>\n";
		}
	}

	echo "              				</select>\n";
	echo "								<td class=\"gray\" width=\"20px\" align=\"right\"><input class=\"checkboxgry\" type=\"image\" src=\"images/arrow_refresh_small.png\" alt=\"Refresh\"></td>\n";
	echo "								</form>\n";
	echo "            				</td>\n";
	echo "         				</tr>\n";
	echo "      			</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";

	if ($nrowsD > 0)
	{
		echo "			<tr>\n";
		echo "				<td>\n";
		echo "					<table class=\"outer\" width=\"100%\">\n";
		echo "						<tr>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"><b>Code</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"><b>Name</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\"><b>Bullet</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"><b>Category</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\"><b>Allocation</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" colspan=\"2\"></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\"><b>Sequence</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"right\"><b>Retail Price</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "						</tr>\n";

		$icnt=0;
		while($rowD = mssql_fetch_row($resD))
		{
			$icnt++;
			
			if ($rowD[12]==1)
			{
				$class="red_und";
			}
			else
			{
				$class="wh_und";
			}

			$qryE   = "SELECT COUNT(catid) FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowD[9]."';";
			$resE   = mssql_query($qryE);
			$rowE   = mssql_fetch_row($resE);

			$qryF   = "SELECT catid,id,aid FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND aid='".$rowD[1]."';";
			$resF   = mssql_query($qryF);
			$rowF   = mssql_fetch_row($resF);

			$qryG  = "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rowD[0]."';";
			$resG  = mssql_query($qryG);
			$nrowG = mssql_num_rows($resG);

			$qryH  = "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rowD[0]."';";
			$resH  = mssql_query($qryH);
			$nrowH = mssql_num_rows($resH);

			$qryI  = "SELECT phsid,rphsid,phsname FROM phasebase WHERE phsid='".$rowD[8]."';";
			$resI  = mssql_query($qryI);
			$rowI  = mssql_fetch_row($resI);

			$qryJ  = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowD[9]."';";
			$resJ  = mssql_query($qryJ);
			$rowJ  = mssql_fetch_row($resJ);

			echo "         <tr class=\"".$class."\">\n";
			echo "            <td align=\"left\">\n";
			
			if ($rowD[10]!=32)
			{
				echo $rowD[1];
			}
			
			echo "				</td>";
			echo "            <td align=\"left\">";

			if ($rowD[10]==32)
			{
				echo "<b>$rowD[4]</b>";
			}
			else
			{
				echo "&nbsp;&nbsp;&nbsp;$rowD[4]";
			}

			echo "</td>\n";
			echo "            <td align=\"center\">";

			if ($rowD[14] > 0)
			{
				echo $rowD[14];
			}

			echo "</td>\n";
			echo "            <td align=\"left\">";

			if ($rowJ[0]==0)
			{
				echo "None";
			}
			else
			{
				echo $rowJ[1];
				if ($rowD[11]!=0)
				{
					echo " *";
				}
			}

			echo "</td>\n";
			echo "            <td align=\"center\">\n";

			if ($_SESSION['m_plev'] >= 8)
			{
				if ($rowD[10]==55||$rowD[10]==72)
				{
					echo "<b>P</b>";
				}

				if ($nrowG >0||$nrowH >0)
				{
					echo "<b>C</b>";
				}

				if ($rowD[13]==1)
				{
					echo "<b>R</b>";
				}
			}

			echo "            </td>\n";
			echo "            <td align=\"right\">\n";

			if ($_SESSION['m_plev'] >= 8)
			{
				if ($rowD[5]!=1)
				{
					echo "            <form method=\"post\">\n";
					echo "               <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "               <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
					echo "               <input type=\"hidden\" name=\"subq\" value=\"reseqdec\">\n";
					echo "               <input type=\"hidden\" name=\"id\" value=\"".$rowD[0]."\">\n";
					echo "               <input type=\"hidden\" name=\"catid\" value=\"".$rowD[9]."\">\n";
					echo "               <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "					<input class=\"transnb\" type=\"image\" src=\"images/arrow_top.gif\" alt=\"Up\">\n";
					echo "            </form>\n";
				}
			}

			echo "            </td>\n";
			echo "            <td align=\"left\">\n";

			if ($_SESSION['m_plev'] >= 8)
			{
				//if ($rowD[5]!=$rowK['mseqn'])
				if ($nrowsD!=$icnt)
				{
					echo "            <form method=\"post\">\n";
					echo "               <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
					echo "               <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
					echo "               <input type=\"hidden\" name=\"subq\" value=\"reseqinc\">\n";
					echo "               <input type=\"hidden\" name=\"id\" value=\"".$rowD[0]."\">\n";
					echo "               <input type=\"hidden\" name=\"catid\" value=\"".$rowD[9]."\">\n";
					echo "               <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "				<input class=\"transnb\" type=\"image\" src=\"images/arrow_down.gif\" alt=\"Down\">\n";
					echo "            </form>\n";
				}
			}

			echo "            </td>\n";
			echo "            <td align=\"center\">\n";

			if ($_SESSION['m_plev'] >= 8)
			{
				echo "            <form method=\"post\">\n";
				echo "					<input class=\"bbox\" type=\"text\" name=\"nseqn\" value=\"".$rowD[5]."\" size=\"3\">\n";
			}

			echo "				</td>\n";
			echo "            <td align=\"right\">\n";

			if ($rowD[10]!=32)
			{
				echo "					<input class=\"bbox\" type=\"text\" name=\"rp\" value=\"".$rowD[7]."\" size=\"10\">\n";
			}

			echo "				</td>\n";

			// Analyze
			echo "            <td align=\"right\">\n";
			echo "			  </td>\n";
			echo "            <td align=\"right\">\n";

			if ($_SESSION['m_plev'] >= 8)
			{
				echo "               <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
				echo "               <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
				echo "               <input type=\"hidden\" name=\"subq\" value=\"edrp\">\n";
				echo "               <input type=\"hidden\" name=\"id\" value=\"".$rowD[0]."\">\n";
				echo "               <input type=\"hidden\" name=\"catid\" value=\"".$rowD[9]."\">\n";
				echo "               <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
				echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Update\">\n";
				echo "            </form>\n";
			}

			echo "            </td>\n";

			if ($_SESSION['m_plev'] >= 1)
			{
				echo "            <form method=\"post\">\n";
				echo "               <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
				echo "               <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
				echo "               <input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
				echo "               <input type=\"hidden\" name=\"id\" value=\"".$rowD[0]."\">\n";
				echo "               <input type=\"hidden\" name=\"catid\" value=\"".$rowD[9]."\">\n";
				echo "               <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			}

			echo "            <td align=\"right\">\n";

			if ($_SESSION['m_plev'] >= 1)
			{
				//echo "               <button type=\"submit\">View</button>\n";
				echo "					<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
			}

			echo "            </td>\n";

			if ($_SESSION['m_plev'] >= 1)
			{
				echo "            </form>\n";
			}

			echo "         </tr>\n";
		}
		
		echo "      		</table>\n";
		echo "      	</td>\n";
		echo "      </tr>\n";
	}
	
	echo "      </table>\n";
}

function pbpub_acc_code_list()
{

	$MAS=$_SESSION['pb_code'];
	if ($_SESSION['tlev'] < 1 && $_SESSION['m_plev'] < 1)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	if (!isset($_GET['order'])||empty($_GET['order']))
	{
		$order="seqn";
	}
	else
	{
		$order=$_GET['order'];
	}

	$disabled="AND disabled!=1 ";
	if (isset($_REQUEST['disabled']) && $_REQUEST['disabled'] == 1)
	{
		$disabled="";
	}

	if ($_SESSION['m_plev'] < 8)
	{
		$disabled="AND disabled!=1 ";
	}

	$qryB	= "SELECT catid,name,officeid FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND active=1 AND catid!=0 ORDER BY seqn;";
	$resB	= mssql_query($qryB);
	$nrowB	= mssql_num_rows($resB);

	if ($nrowB < 1)
	{
		echo "<font color=\"red\"><b>Error!</b></font> You must have at least 1 Active Category!\n";
		exit;
	}

	$qryC   = "SELECT officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC   = mssql_query($qryC);
	$rowC   = mssql_fetch_array($resC);

	if (isset($_REQUEST['catid']) && !empty($_REQUEST['catid']))
	{
		$qryD   = "SELECT id,aid,officeid,accpbook,item,seqn,bp,rp,phsid,catid,qtype,subid,disabled,royrelease FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['catid']."' ".$disabled."ORDER BY $order;";

	}
	else
	{
		$qryD   = "SELECT id,aid,officeid,accpbook,item,seqn,bp,rp,phsid,catid,qtype,subid,disabled,royrelease FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='0' ".$disabled."ORDER BY $order;";
	}
	$resD   = mssql_query($qryD);
	$nrowsD = mssql_num_rows($resD);

	if ($nrowsD < 1)
	{
		echo "	<table class=\"outer\" border=\"0\" width=\"50%\" align=\"center\">\n";
		echo "		<tr>\n";
		echo "			<th valign=\"bottom\" align=\"left\" >Retail Pricebook Publish Tool for ".$rowC['name']."</th>\n";
		echo "      				<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      				<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "      				<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "      				<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "      				<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      				<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
		echo "			<th align=\"right\" > Category: \n";
		echo "               		<select name=\"catid\">\n";
		echo "                  			<option value=\"0\">None</option>\n";

		while ($rowB = mssql_fetch_row($resB))
		{
			if (isset($_REQUEST['catid']) && !empty($_REQUEST['catid']) && $_REQUEST['catid']==$rowB[0])
			{
				echo "                  			<option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]."</option>\n";
			}
			else
			{
				echo "                  			<option value=\"".$rowB[0]."\">".$rowB[1]."</option>\n";
			}
		}

		echo "                  			</select>\n";
		//echo "      		 			<input class=\"checkboxgry\" type=\"checkbox\" name=\"disabled\" value=\"1\">\n";
		echo "               			<button type=\"submit\">Select</button>\n";
		echo "      				</form>\n";
		echo "			</th>\n";
		echo "		</tr>\n";
		echo "	</table>\n";
	}
	else
	{
		$qryK   = "SELECT MAX(seqn) as mseqn FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['catid']."';";
		$resK   = mssql_query($qryK);
		$rowK   = mssql_fetch_array($resK);

		$uid=rand();
		echo "	<table class=\"outer\" border=\"0\" width=\"50%\" align=\"center\">\n";
		echo "		<tr>\n";
		echo "			<td class=\"gray\">\n";
		echo "				<table width=\"100%\">\n";
		echo "            	<th colspan=\"3\" align=\"left\" >Retail Pricebook Publishing Tool for ".$rowC['name']."</th>\n";
		echo "      		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      			<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "     			<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "      			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "      			<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      			<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
		echo "            <th colspan=\"6\" align=\"right\" > Category: \n";
		echo "               <select name=\"catid\">\n";
		echo "                  <option value=\"0\">None</option>\n";

		while ($rowB = mssql_fetch_row($resB))
		{
			if (isset($_REQUEST['catid']) && !empty($_REQUEST['catid']) && $_REQUEST['catid']==$rowB[0])
			{
				echo "                  <option value=\"$rowB[0]\" SELECTED>$rowB[1]</option>\n";
			}
			else
			{
				echo "                  <option value=\"$rowB[0]\">$rowB[1]</option>\n";
			}
		}

		echo "               </select>\n";
		//echo "      		 <input class=\"checkboxgry\" type=\"checkbox\" name=\"disabled\" value=\"1\">\n";
		echo "               <button type=\"submit\">Select</button>\n";
		echo "      		</form>\n";
		echo "         </tr>\n";
		echo "	      <tr>\n";
		echo "            <th colspan=\"5\" align=\"left\">\n";
		echo "            </th>\n";
		echo "            <th colspan=\"6\" align=\"right\" ><b><font color=\"red\">".$nrowsD."</font> Accessorie(s)</b></th>\n";
		echo "         </tr>\n";
		echo "         </table>\n";
		echo "         </td>\n";
		echo "         </tr>\n";
		echo "	      <tr>\n";
		echo "			<td class=\"gray\">\n";
		echo "				<form name=\"pbpub\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      					<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "     					<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "     					<input type=\"hidden\" name=\"subq\" value=\"pbpub\">\n";
		echo "     					<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
		echo "				<table width=\"100%\">\n";
		echo "					<tr>\n";
		echo "						<th colspan=\"2\" align=\"right\" valign=\"bottom\" >Publish now: <input class=\"checkboxgry\" type=\"checkbox\" name=\"pubnow\" value=\"1\"></th>\n";
		echo "						<th colspan=\"2\" align=\"right\" valign=\"bottom\" >Set Publish Date: <input class=\"bboxl\" type=\"text\" name=\"pubdate\" size=\"11\" maxlength=\"10\"></th>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<th colspan=\"4\" align=\"center\" valign=\"bottom\" ><hr width=\"100%\"></th>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<th align=\"left\" valign=\"bottom\" ><b>Code</b></th>\n";
		echo "						<th align=\"left\" valign=\"bottom\" ><b>Name</b></th>\n";
		echo "						<th align=\"right\" valign=\"bottom\" ><b>Current Price</b></th>\n";
		echo "						<th align=\"right\" valign=\"bottom\" ><b>Update Price</b></th>\n";
		echo "					</tr>\n";

		while($rowD = mssql_fetch_row($resD))
		{
			if ($rowD[12]==1)
			{
				$class="red_und";
			}
			else
			{
				$class="wh_und";
			}

			$qryE   = "SELECT COUNT(catid) FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowD[9]."';";
			$resE   = mssql_query($qryE);
			$rowE   = mssql_fetch_row($resE);

			$qryF   = "SELECT catid,id,aid FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND aid='".$rowD[1]."';";
			$resF   = mssql_query($qryF);
			$rowF   = mssql_fetch_row($resF);

			$qryG  = "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rowD[0]."';";
			$resG  = mssql_query($qryG);
			$nrowG = mssql_num_rows($resG);

			$qryH  = "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rowD[0]."';";
			$resH  = mssql_query($qryH);
			$nrowH = mssql_num_rows($resH);

			$qryI  = "SELECT phsid,rphsid,phsname FROM phasebase WHERE phsid='".$rowD[8]."';";
			$resI  = mssql_query($qryI);
			$rowI  = mssql_fetch_row($resI);

			$qryJ  = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowD[9]."';";
			$resJ  = mssql_query($qryJ);
			$rowJ  = mssql_fetch_row($resJ);

			echo "					<tr>\n";
			echo "						<td align=\"left\" class=\"".$class."\" >\n";

			if ($rowD[10]!=32)
			{
				echo $rowD[1];
			}

			echo "						</td>";
			echo "						<td align=\"left\" class=\"".$class."\" >";

			if ($rowD[10]==32)
			{
				echo "<b>$rowD[4]</b>";
			}
			else
			{
				echo "&nbsp;&nbsp;&nbsp;$rowD[4]";
			}

			echo "						</td>\n";
			echo "						<td align=\"right\" class=\"".$class."\" ><b>\n";

			if ($rowD[10]!=32)
			{
				echo number_format($rowD[7], 2, '.', '');
			}

			echo "						</b></td>\n";
			echo "						<td align=\"right\" class=\"".$class."\" >\n";

			if ($rowD[10]!=32)
			{
				echo "							<input class=\"bbox\" type=\"text\" name=\"urp_".$rowD[0]."\" value=\"0\" size=\"10\">\n";
			}

			echo "						</td>\n";
			echo "					</tr>\n";
		}

		echo "					<tr>\n";
		echo "						<td colspan=\"4\" align=\"right\" valign=\"bottom\">\n";
		echo "               					<button type=\"submit\">Publish</button>\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "					</table>\n";
		echo "				</form>\n";
	}
}

function acc_copy_list()
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$MAS=$_SESSION['pb_code'];
	$qryB	= "SELECT catid,name,officeid FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND active=1 AND catid!=0 ORDER BY seqn;";
	$resB	= mssql_query($qryB);
	$nrowB	= mssql_num_rows($resB);

	if ($nrowB < 1)
	{
		echo "<font color=\"red\"><b>Error!</b></font> You must have at least 1 Active Category!\n";
		exit;
	}

	$qryC   = "SELECT officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC   = mssql_query($qryC);
	$rowC   = mssql_fetch_array($resC);

	if (!isset($_REQUEST['fcatid'])||$_REQUEST['fcatid']==0)
	{
		$nrowsD=0;
	}
	else
	{
		$qryD   = "SELECT id,aid,officeid,accpbook,item,seqn,bp,rp,phsid,catid,qtype,subid,disabled FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['fcatid']."' AND disabled!=1 ORDER BY seqn;";
		$resD   = mssql_query($qryD);
		$nrowsD = mssql_num_rows($resD);
	}
	//echo $qryD;

	//echo "TEST";
	//echo $qryD."<br>";

	$brdr=0;
	if ($nrowsD < 1)
	{
		//echo "TEST1";
		echo "      <table class=\"outer\" width=\"50%\" align=\"center\" border=\"".$brdr."\">\n";
		echo "	      <tr>\n";
		echo "            <th colspan=\"5\" align=\"left\" >Copy Operation</th>\n";
		echo "	      </tr>\n";
		echo "	      <tr>\n";
		echo "            <th align=\"left\" >Copy From:</th>\n";
		echo "            <th align=\"left\" >".$rowC['name']."</th>\n";
		echo "      		<form method=\"post\">\n";
		echo "      		<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "      		<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "      		<input type=\"hidden\" name=\"subq\" value=\"copy_list\">\n";
		echo "      		<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
		echo "      		<input type=\"hidden\" name=\"disabled\" value=\"1\">\n";
		echo "            <th align=\"right\" >Category:</th>\n";
		echo "            <th align=\"left\" >\n";
		echo "               <select name=\"fcatid\">\n";
		echo "                  <option value=\"0\"></option>\n";

		while ($rowB = mssql_fetch_row($resB))
		{
			if (isset($_REQUEST['catid']) && !empty($_REQUEST['catid']) && $_REQUEST['catid']==$rowB[0])
			{
				echo "                  <option value=\"$rowB[0]\" SELECTED>$rowB[1]</option>\n";
			}
			else
			{
				echo "                  <option value=\"$rowB[0]\">$rowB[1]</option>\n";
			}
		}

		echo "               </select>\n";
		echo "            </th>\n";
		echo "            <th align=\"right\" ></th>\n";
		echo "	      </tr>\n";
		echo "	      <tr>\n";
		echo "            <th align=\"left\" >Copy To:</th>\n";
		echo "            <th align=\"left\" >\n";

		if (!isset($_REQUEST['offid']))
		{
			echo "               <select name=\"offid\">\n";
			echo "                  <option value=\"0\"></option>\n";

			$qryCa   = "SELECT officeid,name,pb_code FROM offices WHERE active=1 ORDER BY name ASC;";
			$resCa   = mssql_query($qryCa);

			while ($rowCa = mssql_fetch_array($resCa))
			{
				$qryCb   = "SELECT TABLE_NAME AS tn FROM information_Schema.tables WHERE TABLE_NAME = '".$rowCa['pb_code']."acc';";
				$resCb   = mssql_query($qryCb);
				$nrowCb  = mssql_num_rows($resCb);

				if ($nrowCb > 0 && $rowCa['officeid']!=$_SESSION['officeid'])
				{
					echo "                  <option value=\"".$rowCa['officeid']."\">".$rowCa['name']."</option>\n";
				}
			}

			echo "               </select>\n";
		}
		else
		{
			$qryCa   = "SELECT officeid,name,pb_code FROM offices WHERE officeid='".$_REQUEST['offid']."';";
			$resCa   = mssql_query($qryCa);
			$rowCa = mssql_fetch_array($resCa);

			echo $rowCa['name'];
			echo "	<input type=\"hidden\" name=\"offid\" value=\"".$rowCa['officeid']."\" size=\"64\" maxlength=\"64\">\n";
		}

		echo "            </th>\n";
		echo "            <th align=\"right\" >&nbspCategory:</th>\n";
		echo "            <th align=\"left\" >&nbsp</th>\n";
		echo "            <th align=\"right\">\n";
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Submit\">\n";
		echo "            </th>\n";
		echo "      		</form>\n";
		echo "         </tr>\n";

		if ($nrowsD > 0)
		{
			echo "	      <tr>\n";
			echo "            <th colspan=\"3\" align=\"right\" ><b><font color=\"red\">".$nrowsD."</font> Accessorie(s)</b></th>\n";
			echo "         </tr>\n";
		}

		echo "      </table>\n";
	}
	else
	{
		//echo "test2";
		$qryK   = "SELECT MAX(seqn) as mseqn FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['fcatid']."';";
		$resK   = mssql_query($qryK);
		$rowK   = mssql_fetch_array($resK);

		$qryY	= "SELECT catid,name,officeid FROM [AC_cats] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['fcatid']."';";
		$resY	= mssql_query($qryY);
		$rowY	= mssql_fetch_array($resY);

		$qryZ   = "SELECT officeid,name,pb_code FROM [offices] WHERE officeid='".$_REQUEST['offid']."';";
		$resZ   = mssql_query($qryZ);
		$rowZ	  = mssql_fetch_array($resZ);

		$qryZa	= "SELECT catid,name,officeid FROM [AC_cats] WHERE officeid='".$rowZ['officeid']."' ORDER BY seqn ASC;";
		$resZa	= mssql_query($qryZa);

		echo "      <table align=\"center\"  width=\"75%\" border=\"".$brdr."\">\n";
		echo "	      <tr>\n";
		echo "            <td valign=\"top\" align=\"left\" width=\"75%\">\n";
		echo "      <table class=\"outer\" width=\"100%\" align=\"center\" border=\"".$brdr."\">\n";
		echo "	      <tr>\n";
		echo "            <td class=\"ltgray_und\" colspan=\"6\" align=\"left\" >&nbsp<b>Retail Copy Operation</b></td>\n";
		echo "	      </tr>\n";
		echo "	      <tr>\n";
		echo "            <td class=\"gray\" align=\"right\" >&nbspCopy From:</td>\n";
		echo "            <th align=\"left\" >&nbsp".$rowC['name']."</th>\n";
		echo "      		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      		<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "      		<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "            <input type=\"hidden\" name=\"subq\" value=\"copy_op\">\n";
		echo "      		<input type=\"hidden\" name=\"offid\" value=\"".$rowZ['officeid']."\">\n";
		echo "            <input type=\"hidden\" name=\"fcatid\" value=\"".$rowY['catid']."\">\n";
		echo "            <td class=\"gray\" align=\"right\" >Category:</td>\n";
		echo "            <th colspan=\"3\" align=\"left\" >".$rowY['name']."</th>\n";
		//echo "            <th align=\"right\" >&nbsp</th>\n";
		echo "	      </tr>\n";
		echo "	      <tr>\n";
		echo "            <td class=\"gray\" align=\"right\" >&nbspCopy To:</td>\n";
		echo "            <th align=\"left\" >&nbsp".$rowZ['name']."</th>\n";
		echo "            <td class=\"gray\" align=\"right\" >&nbspCategory:</td>\n";
		echo "            <th colspan=\"3\" align=\"left\" >\n";
		echo "               <select name=\"tcatid\">\n";

		while ($rowZa = mssql_fetch_array($resZa))
		{
			echo "                  <option value=\"".$rowZa['catid']."\">".$rowZa['name']."</option>\n";
		}

		echo "               </select>\n";
		echo "				</th>\n";
		echo "         </tr>\n";

		if ($nrowsD > 0)
		{
			echo "	      <tr>\n";
			echo "            <th colspan=\"6\" align=\"right\" ><b><font color=\"red\">".$nrowsD."</font> Accessorie(s)</b></th>\n";
			echo "         </tr>\n";
		}

		while($rowD = mssql_fetch_row($resD))
		{
			if ($rowD[12]==1)
			{
				$class="red_und";
			}
			else
			{
				$class="wh_und";
			}

			$qryE   = "SELECT COUNT(catid) FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowD[9]."';";
			$resE   = mssql_query($qryE);
			$rowE   = mssql_fetch_row($resE);

			$qryF   = "SELECT catid,id,aid FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND aid='".$rowD[1]."';";
			$resF   = mssql_query($qryF);
			$rowF   = mssql_fetch_row($resF);

			$qryG  = "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rowD[0]."';";
			$resG  = mssql_query($qryG);
			$nrowG = mssql_num_rows($resG);

			$qryH  = "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rowD[0]."';";
			$resH  = mssql_query($qryH);
			$nrowH = mssql_num_rows($resH);

			$qryI  = "SELECT phsid,rphsid,phsname FROM phasebase WHERE phsid='".$rowD[8]."';";
			$resI  = mssql_query($qryI);
			$rowI  = mssql_fetch_row($resI);

			$qryJ  = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowD[9]."';";
			$resJ  = mssql_query($qryJ);
			$rowJ  = mssql_fetch_row($resJ);

			echo "         <tr>\n";
			echo "            <td align=\"right\" class=\"".$class."\" >".$rowD[1]."&nbsp&nbsp</td>";
			echo "            <td align=\"left\" class=\"".$class."\" >";

			if ($rowD[10]==32)
			{
				echo "<b>$rowD[4]</b>";
			}
			else
			{
				echo "&nbsp;&nbsp;&nbsp;$rowD[4]";
			}

			echo "</td>\n";
			echo "            <td align=\"left\" class=\"".$class."\" >";
			if ($rowJ[0]==0)
			{
				echo "None";
			}
			else
			{
				echo $rowJ[1];
				if ($rowD[11]!=0)
				{
					echo " *";
				}
			}
			echo "</td>\n";
			//echo "            <td align=\"right\" class=\"".$class."\" ></td>\n";
			echo "            <td align=\"center\" class=\"".$class."\" >\n";

			if ($rowD[10]==55||$rowD[10]==72)
			{
				echo "<b>P</b>";
			}

			if ($nrowG >0||$nrowH >0)
			{
				echo "<b>C</b>";
			}

			$frprice=number_format($rowD[7],2);
			echo "            </td>\n";
			echo "            <td align=\"right\" class=\"".$class."\" >\n";

			if ($rowD[10]!=32)
			{
				echo $frprice;
			}

			echo " 				</td>\n";
			echo "            <td align=\"right\" class=\"".$class."\" >\n";
			//echo "               <input type=\"hidden\" name=\"aiy_".$rowD[1]."\" value=\"aiy_".$rowD[1]."\">\n";
			echo "               <input class=\"checkboxwh\" type=\"checkbox\" name=\"cpy_".$rowD[1]."\" value=\"cpy_".$rowD[1]."\">\n";
			echo "            </td>\n";
			echo "         </tr>\n";
		}

		echo "         </tr>\n";
		echo "            <td colspan=\"10\" align=\"right\" class=\"".$class."\" >\n";
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Submit\">\n";
		echo "            </td>\n";
		echo "         </tr>\n";
		echo "            </form>\n";
		echo "      </table>\n";
		echo "            </td>\n";
		echo "            <td valign=\"top\" width=\"25%\" WRAP>\n";
		echo "				<font color=\"red\"><b>Warning!</b></font><br>\n";
		echo "				This operation will attempt to copy Retail Items from one office to another. This includes Package Retail Objects, Standard Retail Objects, Retail Package Filters, Direct Cost Items, and Linked Cost Items. It will attempt to find the proper location for all items. In event that it does not find a proper Category or Phase for each Item it will drop the copied items into a General Location (Category ID 0 or Phase ID 0). From there Items can be moved into their appropriate Category or Phase.";
		echo "            </td>\n";
		echo "         </tr>\n";
		echo "      </table>\n";

		//echo "</form>\n";
	}
}

function retail_item_search()
{
	$MAS=$_SESSION['pb_code'];
	if ($_SESSION['tlev'] < 1 && $_SESSION['m_plev'] < 1)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	if (empty($_REQUEST['stext']))
	{
		echo "<b>Go back and enter a Search string.</b>";
		exit;
	}

	$disabled=' AND disabled!=1 ';
	if ($_SESSION['m_plev'] >=8)
	{
		$disabled='';
	}

	$qryA  = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND item LIKE '%".$_REQUEST['stext']."%' ".$disabled.";";
	$resA  = mssql_query($qryA);
	$nrows = mssql_num_rows($resA);

	//echo $qryA."<br>";

	if ($nrows < 1)
	{
		echo "<b>The item search for</b> <font color=\"red\">".$_REQUEST['stext']."</font> <b>did not produce any results.</b><br> Click the Back button and try again.";
	}

	if ($nrows >= 1)
	{
		echo "<table class=\"outer\" width=\"60%\" align=\"center\" border=0>\n";
		echo "   <tr>\n";
		echo "      <td>\n";
		echo "<table width=\"100%\" align=\"center\" border=0>\n";
		echo "   <tr>\n";
		echo "      <th align=\"left\" colspan=\"5\">Retail Item Search Results for <font color=\"red\">".$_REQUEST['stext']."</font></th>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <th align=\"center\">Code</th>\n";
		echo "      <th align=\"left\">Item</th>\n";
		echo "      <th align=\"center\">Category</th>\n";
		echo "      <th align=\"center\">Retail</th>\n";
		echo "      <th align=\"right\">Found <font color=\"blue\">".$nrows."</font> Item(s)</th>\n";
		echo "   </tr>\n";

		while ($rowA=mssql_fetch_array($resA))
		{
			//print_r($rowA);

			$qryB = "SELECT * FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$rowA['catid']."';";
			$resB = mssql_query($qryB);
			$rowB	= mssql_fetch_array($resB);

			echo "   <tr>\n";
			echo "      <td align=\"center\" class=\"wh_und\" valign=\"bottom\">".$rowA['aid']."</td>\n";
			echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">".$rowA['item']."</td>\n";
			echo "      <td align=\"center\" class=\"wh_und\" valign=\"bottom\">".$rowB['name']."</td>\n";
			echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">".$rowA['rp']."</td>\n";

			if ($_SESSION['m_plev'] >= 1)
			{
				echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
				echo "         <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
				echo "         <input type=\"hidden\" name=\"catid\" value=\"".$rowA['catid']."\">\n";
				echo "         <input type=\"hidden\" name=\"subq\" value=\"ed\">\n";
				echo "         <input type=\"hidden\" name=\"id\" value=\"".$rowA['id']."\">\n";
			}

			echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";

			if ($_SESSION['m_plev'] >= 8)
			{
				echo "         <button type=\"submit\">Edit</button>\n";
			}
			elseif ($_SESSION['m_plev'] >= 1)
			{
				echo "         <button type=\"submit\">View</button>\n";
			}

			echo "      </td>\n";

			if ($_SESSION['m_plev'] >= 1)
			{
				echo "      </form>\n";
			}

			echo "      </tr>\n";
		}
		echo "   		</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function accessory_add()
{
	$MAS=$_SESSION['pb_code'];
	$qtype=32;

	$qryA = "SELECT MAX(aid) FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);

	$qryB = "SELECT qid,qtype FROM qtypes WHERE qcat=1 AND active=1 ORDER BY qtype ASC;";
	$resB = mssql_query($qryB);

	$qryC = "SELECT commid,commtype FROM commtypes ORDER BY commid ASC;";
	$resC = mssql_query($qryC);

	$qryD = "SELECT MAX(seqn) FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT phsid,rphsid,phsname FROM phasebase WHERE costing=1 ORDER BY phsname ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT mid,abrv FROM mtypes WHERE active=1 ORDER BY abrv ASC;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND active='1' ORDER BY seqn ASC;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT id,item,seqn FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['catid']."' AND qtype='".$qtype."' ORDER BY seqn ASC;";
	$resH = mssql_query($qryH);

	$maid  =$rowA[0]+1;
	$mseqn =$rowD[0]+1;

	$fmaid=sprintf("%04d",$maid);

	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"ins\">\n";
	echo "<input type=\"hidden\" name=\"seqn\" value=\"".$mseqn."\">\n";
	echo "<input type=\"hidden\" name=\"matid\" value=\"0\">\n";
	echo "<table class=\"outer\" border=0 align=\"center\" width=\"950px\">\n";
	echo "<tr>\n";
	echo "   <th align=\"left\" colspan=\"6\"><b>Add Retail Pricing (Accessory):</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Code:</b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"1\" type=\"text\" name=\"aid\" value=\"$fmaid\" size=\"10\" maxlength=\"4\"></td>\n";
	echo "   <td rowspan=\"12\" valign=\"top\" align=\"right\">\n";
	echo "      <table border=0 width=\"100%\">\n";
	echo "         <tr>\n";
	echo "	         <td align=\"left\" colspan=\"2\"><b><i>Display & Calc Controls:</i></b></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Phase:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"13\" name=\"phsid\">\n";
	echo "                  <option value=\"0\" SELECTED>None</option>\n";

	while($rowE = mssql_fetch_row($resE))
	{
		echo "                  <option value=\"$rowE[0]\">$rowE[2]</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Category:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"14\" name=\"catid\">\n";

	while($rowG = mssql_fetch_row($resG))
	{
		if ($rowG[0]==$_REQUEST['catid'])
		{
			echo "                  <option value=\"$rowG[0]\" SELECTED>$rowG[1]</option>\n";
		}
		else
		{
			echo "                  <option value=\"$rowG[0]\">$rowG[1]</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>subHeader:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"15\" name=\"subid\">\n";
	echo "                  <option value=\"0\">None</option>\n";

	while($rowH = mssql_fetch_row($resH))
	{
		echo "                  <option value=\"$rowH[0]\">$rowH[1]</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Question Type:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"16\" name=\"qtype\">\n";

	while($rowB = mssql_fetch_row($resB))
	{
		echo "                  <option value=\"$rowB[0]\">$rowB[1]</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Spa Item:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"17\" name=\"spaitem\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Supplier:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"18\" name=\"supplier\">\n";
	echo "                  <option value=\"1\">Vendor</option>\n";
	echo "                  <option value=\"0\" SELECTED>Blue Haven</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Bullet Weight:</b></td>\n";
	echo "			   <td align=\"left\"><input tabindex=\"19\" type=\"text\" name=\"bullet\" size=\"5\" maxlength=\"2\"></td>\n";
	//echo "               <select tabindex=\"19\" name=\"bullet\">\n";
	//echo "                  <option value=\"1\">Yes</option>\n";
	//echo "                  <option value=\"0\" SELECTED>No</option>\n";
	//echo "               </select>\n";
	//echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Royalty Release:</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"20\" name=\"royrelease\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "      </table>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Description:</b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"2\" type=\"text\" name=\"item\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"3\" type=\"text\" name=\"atrib1\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"4\" type=\"text\" name=\"atrib2\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"5\" type=\"text\" name=\"atrib3\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Low Range:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <input tabindex=\"6\" type=\"text\" name=\"lrange\" value=\"0\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>High Range:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <input tabindex=\"7\" type=\"text\" name=\"hrange\" value=\"0\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Calculation:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <input tabindex=\"8\" type=\"text\" name=\"quan_calc\" value=\"0\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Measurement:</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select tabindex=\"9\" name=\"mtype\">\n";

	while($rowF = mssql_fetch_row($resF))
	{
		echo "         <option value=\"$rowF[0]\">$rowF[1]</option>\n";
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Retail Price:</b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"10\" type=\"text\" name=\"rprice\" size=\"15\" value=\"0.00\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Commission:</b></td>\n";
	echo "   <td align=\"left\"><input tabindex=\"11\" type=\"text\" name=\"crate\" size=\"15\" value=\"0\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Commission Type</b></td>\n";
	echo "   <td align=\"left\">\n";
	echo "      <select tabindex=\"12\" name=\"commtype\">\n";

	while ($rowC=mssql_fetch_row($resC))
	{
		echo "                  <option value=\"$rowC[0]\">$rowC[1]</option>\n";
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"3\" align=\"center\"><button tabindex=\"21\" type=\"submit\">Add Accessory</button></td>\n";
	echo "</tr>\n";
	echo "</form>\n";
	echo "</table>\n";
	echo "<p>\n";
	//acc_code_list();
}

function accessory_add_rmm1()
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$qryA   = "SELECT catid,name FROM MM_cats ORDER BY name ASC;";
	$resA   = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	echo "<table width=\"50%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <th align=\"left\" colspan=\"2\">Add Retail Item: Select Material Category</th>\n";
	echo "      <th align=\"right\"><font color=\"blue\">".$nrowsA."</font> Categories</th>\n";
	echo "   </tr>\n";

	while($rowA=mssql_fetch_array($resA))
	{
		echo "   <tr>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">&nbsp</td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\"><b>".$rowA['name']."</b></td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"add_rmm2\">\n";
		echo "         <input type=\"hidden\" name=\"catid\" value=\"".$rowA['catid']."\">\n";
		echo "         <button type=\"submit\">Select</button>\n";
		echo "      </td>\n";
		echo "         </form>\n";
		echo "   </tr>\n";
	}
	echo "</table>\n";
}

function accessory_add_rmm2($catid)
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$qryA   = "SELECT * FROM material_master WHERE cat='".$catid."' ORDER BY item;";
	$resA   = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	$qryB   = "SELECT catid,name FROM MM_cats WHERE catid='".$catid."';";
	$resB   = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	echo "<table width=\"50%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <th align=\"left\" colspan=\"2\">Add Retail Item: Select Material Item from ".$rowB['name']."</th>\n";
	echo "      <th align=\"right\"><font color=\"blue\">".$nrowsA."</font> ".$rowB['name']." Items</th>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <th align=\"left\">Part #</th>\n";
	echo "      <th align=\"left\" colspan=\"2\">Description</th>\n";
	echo "   </tr>\n";

	while($rowA=mssql_fetch_array($resA))
	{
		echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "      <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "      <input type=\"hidden\" name=\"subq\" value=\"add_rmm3\">\n";
		echo "      <input type=\"hidden\" name=\"catid\" value=\"".$catid."\">\n";
		echo "      <input type=\"hidden\" name=\"id\" value=\"".$rowA['id']."\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">".$rowA['vpnum']."</td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">".$rowA['item']." - ".$rowA['atrib1']."</td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <button type=\"submit\">Select Item</button>\n";
		echo "      </td>\n";
		echo "         </form>\n";
		echo "      </tr>\n";
	}
	echo "   </table>\n";
}

function accessory_add_rmm3($id)
{
	$MAS=$_SESSION['pb_code'];
	$qry0 = "SELECT * FROM material_master WHERE id='".$id."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$qryA = "SELECT MAX(aid) FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);

	$qryB = "SELECT qid,qtype FROM qtypes WHERE qcat=1 AND active=1 ORDER BY qid ASC;";
	$resB = mssql_query($qryB);

	$qryC = "SELECT commid,commtype FROM commtypes ORDER BY commid ASC;";
	$resC = mssql_query($qryC);

	$qryD = "SELECT MAX(seqn) FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT phsid,rphsid,phsname FROM phasebase WHERE costing=1 ORDER BY phsname ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT mid,abrv FROM mtypes WHERE active=1 ORDER BY abrv ASC;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' ORDER BY catid ASC;";
	$resG = mssql_query($qryG);

	$maid  =$rowA[0]+1;
	$mseqn =$rowD[0]+1;

	$fmaid=sprintf("%04d",$maid);

	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"ins\">\n";
	echo "<input type=\"hidden\" name=\"seqn\" value=\"".$mseqn."\">\n";
	echo "<input type=\"hidden\" name=\"matid\" value=\"$id\">\n";
	echo "<table class=\"outer\" border=0 align=\"center\" width=\"60%\">\n";
	echo "<tr>\n";
	echo "   <th align=\"left\" colspan=\"6\"><b>Add Retail Pricing (Accessory):</th>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Accessory Code:</b></td>\n";
	echo "   <td><input type=\"text\" name=\"aid\" value=\"$fmaid\" size=\"10\" maxlength=\"4\"></td>\n";
	echo "   <td rowspan=\"5\" valign=\"top\" align=\"right\">\n";
	echo "      <table border=0 width=\"100%\">\n";
	echo "         <tr>\n";
	echo "	         <td align=\"left\" colspan=\"2\"><b><i>Display & Calc Controls:</i></b></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Phase:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"phsid\">\n";
	echo "                  <option value=\"0\" SELECTED>None</option>\n";

	while($rowE = mssql_fetch_row($resE))
	{
		echo "                  <option value=\"$rowE[0]\">$rowE[2]</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Category:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"catid\">\n";

	while($rowG = mssql_fetch_row($resG))
	{
		echo "                  <option value=\"$rowG[0]\">$rowG[1]</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Question Type:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"qtype\">\n";

	while($rowB = mssql_fetch_row($resB))
	{
		echo "                  <option value=\"$rowB[0]\">$rowB[1]</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Spa Item:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"spaitem\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Supplier:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"supplier\">\n";
	echo "                  <option value=\"1\">Vendor</option>\n";
	echo "                  <option value=\"0\" SELECTED>Blue Haven</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Bullet:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"bullet\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "      </table>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Description:</b></td>\n";
	echo "   <td><input type=\"text\" name=\"item\" value=\"".$row0['item']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td><input type=\"text\" name=\"atrib1\" value=\"".$row0['atrib1']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td><input type=\"text\" name=\"atrib2\" value=\"".$row0['atrib2']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td><input type=\"text\" name=\"atrib3\" value=\"".$row0['atrib3']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Low Range:</b></td>\n";
	echo "   <td>\n";
	echo "      <input type=\"text\" name=\"lrange\" value=\"0\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>High Range:</b></td>\n";
	echo "   <td>\n";
	echo "      <input type=\"text\" name=\"hrange\" value=\"0\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Calculation:</b></td>\n";
	echo "   <td>\n";
	echo "      <input type=\"text\" name=\"quan_calc\" value=\"0\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Measurement:</b></td>\n";
	echo "   <td>\n";
	echo "      <select name=\"mtype\">\n";

	while($rowF = mssql_fetch_row($resF))
	{
		if ($rowF[0]==$row0['mtype'])
		{
			echo "         <option value=\"$rowF[0]\" SELECTED>$rowF[1]</option>\n";
		}
		else
		{
			echo "         <option value=\"$rowF[0]\">$rowF[1]</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Retail Price:</b></td>\n";
	echo "   <td><input type=\"text\" name=\"rprice\" size=\"15\" value=\"".$row0['rp']."\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Commission:</b></td>\n";
	echo "   <td><input type=\"text\" name=\"crate\" size=\"15\" value=\"0\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Commission Type</b></td>\n";
	echo "   <td>\n";
	echo "      <select name=\"commtype\">\n";

	while ($rowC=mssql_fetch_row($resC))
	{
		echo "                  <option value=\"$rowC[0]\">$rowC[1]</option>\n";
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"3\" align=\"center\"><button type=\"submit\">Add Accessory</button></td>\n";
	echo "</tr>\n";
	echo "</form>\n";
	echo "</table>\n";
	echo "<p>\n";
	//acc_code_list();
}

function accessory_insert()
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];
	$aid=$_REQUEST['aid'];
	$matid=$_REQUEST['matid'];
	$item=replacecomma($_REQUEST['item']);
	$atrib1=replacecomma($_REQUEST['atrib1']);
	$atrib2=replacecomma($_REQUEST['atrib2']);
	$atrib3=replacecomma($_REQUEST['atrib3']);
	$rp=$_REQUEST['rprice'];
	$catid=$_REQUEST['catid'];
	$subid=$_REQUEST['subid'];
	$commtype=$_REQUEST['commtype'];
	$crate=$_REQUEST['crate'];
	$qtype=$_REQUEST['qtype'];
	$spaitem=$_REQUEST['spaitem'];
	$phsid=$_REQUEST['phsid'];
	$quan_calc=$_REQUEST['quan_calc'];
	$lrange=$_REQUEST['lrange'];
	$hrange=$_REQUEST['hrange'];
	$mtype=$_REQUEST['mtype'];
	$supplier=$_REQUEST['supplier'];
	$bullet=$_REQUEST['bullet'];

	if ($_REQUEST['subid']!=0)
	{
		//$qry  = "SELECT MAX(seqn) AS mseqn FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['catid']."' AND id='".$_REQUEST['subid']."';";
		$qry  = "SELECT MAX(seqn) AS mseqn FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['catid']."';";
		$res  = mssql_query($qry);
		$row  = mssql_fetch_array($res);
		//echo $qry."<br>";
		$seqn=$row['mseqn']+1;
	}
	else
	{
		$seqn=$_REQUEST['seqn'];
	}

	$qryA  = "INSERT INTO [".$MAS."acc] (officeid,aid,matid,item,atrib1,atrib2,atrib3,catid,rp,commtype,crate,qtype,spaitem,seqn,phsid,quan_calc,lrange,hrange,mtype,supplier,bullet,subid,usecid,updt) ";
	$qryA .= "VALUES ('$officeid','$aid','$matid','$item','$atrib1','$atrib2','$atrib3','$catid',CONVERT(money,$rp),'$commtype','$crate','$qtype','$spaitem','$seqn','$phsid','$quan_calc','$lrange','$hrange','$mtype','$supplier','$bullet','$subid','".$_SESSION['securityid']."',getdate());";
	$resA  = mssql_query($qryA);
	//$rowA  = mssql_fetch_array($resA);

	if ($qtype==32)
	{
		$qryB  = "SELECT MAX(id) FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_REQUEST['catid']."';";
		$resB  = mssql_query($qryB);
		$rowB  = mssql_fetch_row($resB);

		$qryC  = "UPDATE [".$MAS."acc] SET subid='".$rowB[0]."' WHERE officeid='".$_SESSION['officeid']."' AND id='".$rowB[0]."';";
		$resC  = mssql_query($qryC);
		//$rowC  = mssql_fetch_array($resC);
	}

	resequence_acc();
	acc_code_list();
}

function accessory_edit($id)
{
	$MAS=$_SESSION['pb_code'];
	$officeid =$_SESSION['officeid'];
	$qtype=32;

	//echo "BOO";
	$qry  = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' and id='".$id."';";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	/*
	if ($row['qtype']!=32) {
		$qryAa = "SELECT TOP 1 id FROM [".$MAS."acc] WHERE officeid=".(int) $_SESSION['officeid']." AND catid=".(int) $rowA['catid']." AND qtype=32 AND seqn < ".(int) $rowA['seqn']." ORDER BY seqn DESC;";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		$pid=$rowAa['id'];
	}
	*/

	$qryA = "SELECT phsid,rphsid,phsname FROM phasebase WHERE costing=1 ORDER BY phsname ASC";
	$resA = mssql_query($qryA);

	$qryB = "SELECT qid,qtype FROM qtypes WHERE qcat=1 AND active=1 ORDER BY qtype ASC;";
	$resB = mssql_query($qryB);

	$qryE = "SELECT commid,commtype FROM commtypes ORDER BY commid ASC;";
	$resE = mssql_query($qryE);

	$qryC  = "SELECT id,officeid,accid,item,bprice,rprice,phsid,raccid FROM [".$MAS."accpbook] WHERE raccid='".$row['id']."' ORDER BY item ASC;";
	$resC  = mssql_query($qryC);
	$nrowC = mssql_num_rows($resC);

	$qryD   = "SELECT catid,name,officeid FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' ORDER BY name;";
	$resD   = mssql_query($qryD);

	$qryF  = "SELECT invid,officeid,item,bprice,rprice,phsid FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND raccid='".$row['id']."' ORDER BY item ASC;";
	$resF  = mssql_query($qryF);
	$nrowF = mssql_num_rows($resF);

	$qryG  = "SELECT phsid,rphsid,phsname FROM phasebase WHERE phstype!='M' ORDER BY phsname;";
	$resG  = mssql_query($qryG);

	$qryH  = "SELECT phsid,rphsid,phsname FROM phasebase WHERE rphsid='".$row['phsid']."';";
	$resH  = mssql_query($qryH);
	$rowH  = mssql_fetch_row($resH);

	$qryI  = "SELECT mid,abrv FROM mtypes WHERE active=1 ORDER BY abrv;";
	$resI  = mssql_query($qryI);

	$qryJ = "SELECT phsid,rphsid,phsname FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY phsname ASC";
	$resJ = mssql_query($qryJ);

	$qryK = "SELECT phsid,rphsid,phsname FROM phasebase WHERE phstype='M' AND costing=1 ORDER BY phsname ASC";
	$resK = mssql_query($qryK);

	$qryL = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND active='1' ORDER BY seqn ASC;";
	$resL = mssql_query($qryL);

	$qryN = "SELECT id,item,seqn FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row['catid']."' AND qtype='".$qtype."' ORDER BY seqn ASC;";
	$resN = mssql_query($qryN);

	if ($_SESSION['tlev'] >=2 && $_SESSION['m_plev'] >= 8)
	{
		$dis="";
	}
	else
	{
		$dis="";
	}

	if (strtotime($row['updt']) > strtotime('1/1/1999') && isset($row['usecid']) && $row['usecid'] !=0)
	{
		$qryO = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['usecid']."';";
		$resO = mssql_query($qryO);
		$rowO  = mssql_fetch_array($resO);

		$ufname	=$rowO['fname'];
		$ulname	=$rowO['lname'];
		$udate	=date("m/d/Y",strtotime($row['updt']));
		
		$updby   ="Last Update: ".$ulname.",".$ufname." on ".$udate;
	}
	else
	{
		$updby	="";
	}

	echo "<table class=\"outer\" width=\"950px\">\n";
	
	/*
	echo "			<tr>\n";
	echo "				<td colspan=\"3\">\n";
	echo "					<table width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"left\" colspan=\"2\"><b>Retail Pricing (Accessory) List for ".$rowC['name']."</b></td>\n";
	echo "            				<td class=\"gray\" align=\"right\">\n";

	if ($_SESSION['tlev'] >=1 && $_SESSION['m_plev'] >= 8)
	{
		echo "            					<form method=\"post\">\n";
		echo "            					<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "            					<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "            					<input type=\"hidden\" name=\"catid\" value=\"0\">\n";
		echo "            					<select name=\"subq\" onChange=\"this.form.submit();\">\n";
		echo "            						<option value=\"add\">Add Retail Item From...</option>\n";
		echo "            						<option value=\"add\">Blank Form</option>\n";
		echo "            						<option value=\"add_rmm1\">Material List</option>\n";
		echo "            					</select>\n";
		echo "            					</form>\n";
	}
	
	echo "            				</td>\n";
	echo "         				</tr>\n";
	echo "	      				<tr>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Search</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\">\n";
	echo "								<form method=\"post\">\n";
	echo "								<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "								<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
	echo "								<input type=\"hidden\" name=\"subq\" value=\"search\">\n";
	echo "								<input type=\"text\" name=\"stext\" size=\"24\" maxlength=\"25\">\n";
	echo "								<input class=\"checkboxgry\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "            					</form>\n";
	echo "							</td>\n";
	echo "							<td class=\"gray\" align=\"right\">\n";
	echo "      						<form method=\"post\">\n";
	echo "      						<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "      						<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
	echo "      						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "      						<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
	echo "      						<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
	echo "              				<select name=\"catid\" onChange=\"this.form.submit();\">\n";
	echo "									<option value=\"0\">Select Retail Category...</option>\n";
	echo "									<option value=\"0\">-----------</option>\n";
	echo "									<option value=\"0\">None</option>\n";

	while ($rowB = mssql_fetch_row($resB))
	{
		if (isset($_REQUEST['catid']) && !empty($_REQUEST['catid']) && $_REQUEST['catid']==$rowB[0])
		{
			echo "										<option value=\"$rowB[0]\" SELECTED>$rowB[1]</option>\n";
		}
		else
		{
			echo "										<option value=\"$rowB[0]\">$rowB[1]</option>\n";
		}
	}

	echo "              				</select>\n";
	//echo "								<input class=\"checkboxgry\" type=\"checkbox\" name=\"disabled\" value=\"1\">\n";
	echo "								</form>\n";
	echo "            				</td>\n";
	echo "         				</tr>\n";
	echo "      			</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	*/
	
	echo "<tr>\n";
	echo "	<td class=\"ltgray_und\" align=\"left\" colspan=\"2\"><b>".$_SESSION['offname']." Retail Item</b></td>\n";
	echo "	<td class=\"ltgray_und\" align=\"right\"><img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"left\" colspan=\"2\">\n";
	
	echo $updby;
	
	echo "   </td>\n";
	echo "	<td class=\"gray\" align=\"right\">\n";

	if ($_SESSION['m_plev'] >=8)
	{
		echo "      <form method=\"post\" ".$dis.">\n";
		echo "      <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "      <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "      <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "      <input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "      <input type=\"hidden\" name=\"order\" value=\"seqn\">\n";


		echo "      <select tabindex=\"1\" name=\"catid\" onChange=\"this.form.submit();\">\n";
		echo "			<option value=\"0\">None</option>\n";

		while ($rowD = mssql_fetch_array($resD))
		{
			if (isset($_REQUEST['catid']) && $_REQUEST['catid']==$rowD['catid'])
			{
				echo "			<option value=\"".$rowD['catid']."\" SELECTED>".$rowD['name']."</option>\n";
			}
			else
			{
				echo "			<option value=\"".$rowD['catid']."\">".$rowD['name']."</option>\n";
			}
		}

		echo "      </select>\n";
		echo "		<input class=\"checkboxgry\" type=\"image\" src=\"images/arrow_refresh_small.png\" alt=\"Refresh\">\n";
		echo "		</form>\n";
	}

	echo "   </td>\n";
	echo "</tr>\n";

	if ($_SESSION['m_plev'] >=8)
	{
		echo "<form method=\"post\" ".$dis.">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"acc\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"ed2\">\n";
		echo "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"matid\" value=\"".$row['matid']."\">\n";
	}

	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Code</b></td>\n";
	echo "	<td class=\"gray\" align=\"left\"><font color=\"red\"><b>".$row['aid']."</b></font></td>\n";
	echo "	<td class=\"gray\" rowspan=\"13\" valign=\"top\" align=\"right\">\n";
	echo "		<table border=0>\n";
	echo "			<tr>\n";
	echo "				<td align=\"left\" colspan=\"2\"><b><i>Code Controls</i></b></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";

	if ($row['disabled']==1)
	{
		echo "	         <td align=\"right\"><b><font color=\"red\">Disabled</font></b></td>\n";
	}
	else
	{
		echo "	         <td align=\"right\"><b>Disabled</b></td>\n";
	}

	echo "            <td align=\"left\">\n";
	echo "                  <select tabindex=\"13\" name=\"disabled\">\n";

	if ($row['disabled']==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "            </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Phase</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"14\" name=\"phsid\">\n";

	if ($row['phsid']==0)
	{
		echo "                  <option value=\"0\" SELECTED>None</option>\n";
	}
	else
	{
		echo "                  <option value=\"0\">None</option>\n";
	}

	while($rowA = mssql_fetch_array($resA))
	{
		if ($row['phsid']==$rowA['phsid'])
		{
			echo "                  <option value=\"".$rowA['phsid']."\" SELECTED>".$rowA['phsname']."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$rowA['phsid']."\">".$rowA['phsname']."</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Category</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"15\" name=\"catid\">\n";

	while($rowL = mssql_fetch_array($resL))
	{
		if ($rowL['catid']==$row['catid'])
		{
			echo "                  <option value=\"".$rowL['catid']."\" SELECTED>".$rowL['name']."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$rowL['catid']."\">".$rowL['name']."</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>subHeader</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"16\" name=\"subid\">\n";
	echo "                  <option value=\"0\">None</option>\n";

	while($rowN = mssql_fetch_array($resN))
	{
		if ($rowN['id']==$row['subid'])
		{
			echo "                  <option value=\"".$rowN['id']."\" SELECTED>".$rowN['item']."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$rowN['id']."\">".$rowN['item']."</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Ques Type</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"17\" name=\"qtype\">\n";

	while($rowB = mssql_fetch_array($resB))
	{
		if ($rowB['qid']==$row['qtype'])
		{
			echo "                  <option value=\"".$rowB['qid']."\" SELECTED>".$rowB['qtype']."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$rowB['qid']."\">".$rowB['qtype']."</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Spa Item</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"18\" name=\"spaitem\">\n";

	if ($row['spaitem']==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Supplier</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"19\" name=\"supplier\">\n";

	if ($row['supplier']==1)
	{
		echo "                  <option value=\"1\" SELECTED>Vendor</option>\n";
		echo "                  <option value=\"0\">Blue Haven</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Vendor</option>\n";
		echo "                  <option value=\"0\" SELECTED>Blue Haven</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Bullet Wt</b></td>\n";
	echo "			   <td align=\"left\"><input tabindex=\"20\" type=\"text\" name=\"bullet\" value=\"".$row['bullet']."\" size=\"5\" maxlength=\"2\"></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Royalty Rel</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"20\" name=\"royrelease\">\n";

	if ($row['royrelease']==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"  title=\"For Pool Packages: Adds Base Pool Value to Retail Price.\"><b>Pool Calc</b></td>\n";
	echo "            <td align=\"left\">\n";
	echo "               <select tabindex=\"21\" name=\"poolcalc\">\n";

	if ($row['poolcalc']==1)
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "      </table>\n";
	echo "   </td>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Name</b></td>\n";
	echo "   <td class=\"gray\"  align=\"left\"><input tabindex=\"2\" type=\"text\" name=\"item\" value=\"".$row['item']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b></b></td>\n";
	echo "   <td class=\"gray\" align=\"left\"><input tabindex=\"3\" type=\"text\" name=\"atrib1\" value=\"".$row['atrib1']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b></b></td>\n";
	echo "   <td class=\"gray\" align=\"left\"><input tabindex=\"4\" type=\"text\" name=\"atrib2\" value=\"".$row['atrib2']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b></b></td>\n";
	echo "   <td class=\"gray\" align=\"left\"><input tabindex=\"5\" type=\"text\" name=\"atrib3\" value=\"".$row['atrib3']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td class=\"gray\" align=\"right\"><b>Low Range</b></td>\n";
	echo "   <td class=\"gray\" align=\"left\">\n";
	echo "      <input tabindex=\"6\" type=\"text\" name=\"lrange\" value=\"".$row['lrange']."\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td class=\"gray\" align=\"right\"><b>High Range</b></td>\n";
	echo "   <td class=\"gray\" align=\"left\">\n";
	echo "      <input tabindex=\"7\" type=\"text\" name=\"hrange\" value=\"".$row['hrange']."\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td class=\"gray\" align=\"right\"><b>Calc Amt</b></td>\n";
	echo "   <td class=\"gray\" align=\"left\">\n";
	echo "      <input tabindex=\"8\" type=\"text\" name=\"quan_calc\" value=\"".$row['quan_calc']."\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td class=\"gray\" align=\"right\"><b>Meas</b></td>\n";
	echo "   <td class=\"gray\" align=\"left\">\n";
	echo "      <select tabindex=\"9\" name=\"mtype\">\n";

	while($rowI = mssql_fetch_array($resI))
	{
		if ($rowI['mid']==$row['mtype'])
		{
			echo "         <option value=\"".$rowI['mid']."\" SELECTED>".$rowI['abrv']."</option>\n";
		}
		else
		{
			echo "         <option value=\"".$rowI['mid']."\">".$rowI['abrv']."</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Retail Price</b></td>\n";
	echo "   <td class=\"gray\" align=\"left\"><i>".$row['rp']."</i></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Update Retail Price</b></td>\n";
	echo "   <td class=\"gray\" align=\"left\"><input tabindex=\"10\" type=\"text\" name=\"rprice\" value=\"".$row['rp']."\" size=\"15\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Comm</b></td>\n";
	echo "   <td class=\"gray\" align=\"left\"><input tabindex=\"11\" type=\"text\" name=\"crate\" size=\"15\" value=\"".$row['crate']."\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td class=\"gray\" align=\"right\"><b>Comm Calc</b></td>\n";
	echo "   <td class=\"gray\" align=\"left\">\n";
	echo "      <select tabindex=\"12\" name=\"commtype\">\n";

	while ($rowE=mssql_fetch_array($resE))
	{
		if ($rowE['commid']==$row['commtype'])
		{
			echo "                  <option value=\"".$rowE['commid']."\" SELECTED>".$rowE['commtype']."</option>\n";
		}
		else
		{
			echo "                  <option value=\"".$rowE['commid']."\">".$rowE['commtype']."</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";

	if ($_SESSION['m_plev'] >=8)
	{
		echo "<tr>\n";
		echo "   <td class=\"gray\" colspan=\"3\" align=\"right\"><input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Update\"></td>\n";
		echo "   </form>\n";
		echo "</tr>\n";
	}

	echo "</table>\n";
	//echo "<br>\n";
	echo "<table width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"left\" width=\"50%\">\n";

	retail_cost_tie_display($row['id']);

	echo "   	</td>\n";
	echo "		<td valign=\"top\" align=\"left\" width=\"50%\">\n";

	if ($row['qtype']==55||$row['qtype']==72)
	{
		retail_package_tie_display($row['id']);
	}
	else
	{
		echo "&nbsp";
	}

	echo "   	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function accessory_edit2() {
	$MAS		=$_SESSION['pb_code'];
	$officeid	=$_SESSION['officeid'];
	$id			=$_REQUEST['id'];
	$catid		=$_REQUEST['catid'];
	$subid		=$_REQUEST['subid'];
	//$item		=preg_replace("/,/","",$_REQUEST['item']);
	$item		=removecomma($_REQUEST['item']);
	$atrib1		=removecomma($_REQUEST['atrib1']);
	$atrib2		=removecomma($_REQUEST['atrib2']);
	$atrib3		=removecomma($_REQUEST['atrib3']);
	$commtype	=$_REQUEST['commtype'];
	$crate		=$_REQUEST['crate'];
	$rp			=$_REQUEST['rprice'];
	$qtype		=$_REQUEST['qtype'];
	$spaitem	=$_REQUEST['spaitem'];
	$phsid		=$_REQUEST['phsid'];
	$quan_calc	=$_REQUEST['quan_calc'];
	$lrange		=$_REQUEST['lrange'];
	$hrange		=$_REQUEST['hrange'];
	$mtype		=$_REQUEST['mtype'];
	$supplier	=$_REQUEST['supplier'];
	$bullet		=$_REQUEST['bullet'];
	$disabled	=$_REQUEST['disabled'];
	$royrelease	=$_REQUEST['royrelease'];
	$poolcalc	=$_REQUEST['poolcalc'];
	$pid		=0;
	
	if ($qtype!=32) {
		$qry0 = "SELECT id,seqn FROM [".$MAS."acc] WHERE officeid=".(int) $officeid." AND id=".(int) $id.";";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		
		$qry0a = "SELECT TOP 1 id FROM [".$MAS."acc] WHERE officeid=".(int) $officeid." AND catid=".(int) $catid." AND qtype=32 AND seqn < ".(int) $row0['seqn']." ORDER BY seqn DESC;";
		$res0a = mssql_query($qry0a);
		$row0a = mssql_fetch_array($res0a);
		$pid=$row0a['id'];
	}

	$qryA  = "UPDATE [".$MAS."acc] SET catid='".$catid."',subid='".$subid."',item='".$item."',atrib1='".$atrib1."',atrib2='".$atrib2."',atrib3='".$atrib3."',";
	$qryA .= "rp=CONVERT(money,'".$rp."'),commtype='".$commtype."',crate='".$crate."',qtype='".$qtype."',spaitem='".$spaitem."',phsid='".$phsid."',";
	$qryA .= "quan_calc='".$quan_calc."',lrange='".$lrange."',hrange='".$hrange."',mtype='".$mtype."',supplier='".$supplier."',bullet='".$bullet."',";
	$qryA .= "disabled='".$disabled."',royrelease='".$royrelease."',poolcalc='".$poolcalc."',usecid='".$_SESSION['securityid']."',updt=getdate(), ";
	$qryA .= "pid='".$pid."' ";
	$qryA .= " WHERE id='".$id."';";
	$resA  = mssql_query($qryA);

	//if ($_SESSION['securityid']==26) {
	//	echo $qryA;
	//}

	//echo "XYZ<br>";
	
	accessory_edit($id);
}

function procPids($oid) {
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	echo 'Processing.... ';
	$qry0 = "SELECT officeid as oid,pb_code FROM offices WHERE officeid=".(int) $oid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$pb_code=$row0['pb_code'];
	
	$qry1 = "select id,officeid as oid,catid,qtype,seqn from [".$pb_code."acc] where officeid=".$row0['oid']." and qtype!=32 and disabled!=1 order by catid,seqn";
	$res1 = mssql_query($qry1);
	
	$p=0;
	while($row1 = mssql_fetch_array($res1)) {
		if ($row1['qtype']!=32) {
			$qry2 = "SELECT TOP 1 id FROM [".$pb_code."acc] WHERE officeid=".(int)$row1['oid']." AND catid=".(int) $row1['catid']." AND qtype=32 AND seqn < ".(int) $row1['seqn']." ORDER BY seqn DESC;";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);
			$pid  = $row2['id'];
		}
		else {
			$pid=0;
		}
		
		$qry3 = "update [".$pb_code."acc] set pid=".$pid." where id=".$row1['id'].";";
		$res3 = mssql_query($qry3);
		$p++;
	}
	
	echo $p.' Items Processed<br>';
}

function accessory_editrp()
{
	$MAS=$_SESSION['pb_code'];
	if (!isset($_REQUEST['rp'])||empty($_REQUEST['rp']))
	{
		$rp="0.00";
	}
	else
	{
		$rp=$_REQUEST['rp'];
	}

	$qry = "SELECT seqn FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qryA = "UPDATE [".$MAS."acc] SET rp=CONVERT(money,'".$rp."'),seqn='".$_REQUEST['nseqn']."',usecid='".$_SESSION['securityid']."' WHERE officeid='".$_SESSION['officeid']."' AND id='".$_REQUEST['id']."';";
	$resA = mssql_query($qryA);

	if ($_REQUEST['nseqn']!=$row['seqn'])
	{
		resequence_acc();
	}

	acc_code_list();
}

function material_list()
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$MAS=$_SESSION['pb_code'];
	
	if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='base_vendor_list')
	{
		$qryA   = "SELECT vid,name FROM vendors ORDER BY name ASC;";
	}
	else
	{
		$qryA   = "SELECT distinct(masgrp) FROM material_master ORDER BY masgrp ASC;";
	}
	$resA   = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);
	
	$qryF   = "
				SELECT
					M.id
				FROM
					material_master as M
				INNER JOIN
					[".$MAS."inventory] as I
				ON
					M.id=I.matid
				WHERE
					len(vpnum) >= 1
					and id not in (select mmid from material_master_links where oid=".$_SESSION['officeid'].")
			";
	$resF   = mssql_query($qryF);
	$nrowsF = mssql_num_rows($resF);

	echo "<script type=\"text/javascript\" src=\"js/jquery_costing_maint_func.js\"></script>\n";
	
	if (isset($nrowsF) and $nrowsF > 99999999999999999999999999)
	{
		echo "<input type=\"hidden\" id=\"paction\" value=\"ItemInventoryAdd\">\n";
		echo "<input type=\"hidden\" id=\"oid\"  value=\"".$_SESSION['officeid']."\">\n";
		echo "	<table class=\"outer\" width=\"450px\">\n";
		echo "		<tr>\n";
		echo "			<td align=\"left\" colspan=\"2\"><b><font color=\"red\">NOTICE!</font> There are ".$nrowsF." Material Master Items for ".$_SESSION['offname']." that have not been synchronized with Quickbooks.</b></td>\n";
		echo "		</tr>\n";
		echo "		<tr>\n";
		echo "			<td valign=\"top\"><button id=\"SyncMaterialMasterItems\">Synchronize <img src=\"images/arrow_refresh.png\"></button></td>\n";
		echo "			<td align=\"left\" valign=\"top\"><div id=\"textbox_CostConfigStatus\"></div></td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";
		echo '<br>';
	}
	
	echo "<table class=\"outer\" width=\"950px\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\" width=\"25\"></td>\n";
	echo "   	<td class=\"gray\">\n";
	echo "  		<table width=\"100%\">\n";
	echo "   			<tr>\n";
	echo "      			<td class=\"gray\" align=\"left\" ><b>Material Master</b></td>\n";
	echo "      			<td class=\"gray\" align=\"right\">\n";
	echo "         				<form method=\"post\">\n";
	echo "         					<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "            				<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
	echo "            				<input type=\"hidden\" name=\"subq\" value=\"item_search\">\n";
	echo "            				<select name=\"field\">\n";
	echo "               				<option value=\"vpnum\" SELECTED>Part No</option>\n";
	echo "               				<option value=\"item\">Description</option>\n";
	echo "            				</select>\n";
	echo "            				<input type=\"text\" name=\"stext\">\n";
	echo "							<input class=\"checkboxgry\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "         				</form>\n";
	echo "      			</td>\n";
	echo "   			</tr>\n";
	echo "   		</table>\n";
	echo "   	</td>\n";
	echo "   	<td class=\"gray\" width=\"25\"></td>\n";
	echo "   	<td class=\"gray\" width=\"25\"></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"ltgray_und\" width=\"25\"></td>\n";
	echo "      <td align=\"left\" class=\"ltgray_und\">\n";
	echo "         	<form method=\"post\">\n";
	echo "         	<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "          <input type=\"hidden\" name=\"call\" value=\"mat\">\n";
	echo "          <select name=\"subq\" OnChange=\"this.form.submit();\">\n";
	
	if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='base_vendor_list')
	{
		echo "               <option value=\"base_cat_list\">by Prod Line</option>\n";
		echo "               <option value=\"base_vendor_list\" SELECTED>by Vendor</option>\n";
	}
	else
	{
		echo "               <option value=\"base_cat_list\" SELECTED>by Prod Line</option>\n";
		echo "               <option value=\"base_vendor_list\">by Vendor</option>\n";
	}
	
	echo "          </select>\n";
	echo "          </form>\n";
	echo "		</td>\n";
	echo "      <td align=\"right\" class=\"ltgray_und\"></td>\n";
	echo "      <td align=\"right\" class=\"ltgray_und\"></td>\n";
	echo "   </tr>\n";
	
	if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='base_vendor_list')
	{
		$cnt=1;
		while($rowA=mssql_fetch_array($resA))
		{
			echo "   <tr>\n";
			echo "      <td align=\"right\" class=\"wh_und\" width=\"25\">".$cnt++.".</td>\n";
			echo "      <td align=\"left\" class=\"wh_und\"><b>".$rowA['name']."</b></td>\n";
			echo "      <td align=\"right\" class=\"wh_und\" width=\"25\">\n";
			echo "         <form method=\"post\">\n";
			echo "         	<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "         	<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
			echo "         	<input type=\"hidden\" name=\"subq\" value=\"add_by_vendor\">\n";
			echo "         	<input type=\"hidden\" name=\"vid\" value=\"".$rowA['vid']."\">\n";
			echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/add.png\" alt=\"Add New\">\n";
			echo "         </form>\n";
			echo "      </td>\n";
			echo "      <td align=\"right\" class=\"wh_und\" width=\"25\">\n";
			echo "         <form method=\"post\">\n";
			echo "         	<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "         	<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
			echo "         	<input type=\"hidden\" name=\"subq\" value=\"vendor_list\">\n";
			echo "         	<input type=\"hidden\" name=\"vid\" value=\"".$rowA['vid']."\">\n";
			echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Item\">\n";
			echo "         </form>\n";
			echo "      </td>\n";
			echo "   </tr>\n";
		}
	}
	else
	{
		$cnt=1;
		while($rowA=mssql_fetch_array($resA))
		{
			echo "   <tr>\n";
			echo "      <td align=\"right\" class=\"wh_und\" width=\"25\">".$cnt++.".</td>\n";
			echo "      <td align=\"left\" class=\"wh_und\"><b>".$rowA['masgrp']."</b></td>\n";
			echo "      <td align=\"right\" class=\"wh_und\" width=\"25\">\n";
			echo "         <form method=\"post\">\n";
			echo "         	<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "         	<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
			echo "         	<input type=\"hidden\" name=\"subq\" value=\"add_by_cat\">\n";
			echo "         	<input type=\"hidden\" name=\"catid\" value=\"".$rowA['masgrp']."\">\n";
			echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/add.png\" alt=\"Add New\">\n";
			echo "         </form>\n";
			echo "      </td>\n";
			echo "      <td align=\"right\" class=\"wh_und\">\n";
			echo "			<form method=\"post\">\n";
			echo "      	<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "      	<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
			echo "      	<input type=\"hidden\" name=\"subq\" value=\"cat_list\">\n";
			echo "      	<input type=\"hidden\" name=\"catid\" value=\"".$rowA['masgrp']."\">\n";
			echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Item\">\n";
			echo "			</form>\n";
			echo "      </td>\n";
			echo "   </tr>\n";
		}
	}
	
	echo "</table>\n";
}

function material_list_by_cat($catid) // Items List by Category
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	if (empty($_GET['order']))
	{
		$order="id";
	}

	$qryA   = "SELECT * FROM material_master WHERE masgrp='".$catid."' ORDER BY ".$order.";";
	$resA   = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	$qryB   = "SELECT catid,name FROM MM_cats WHERE catid='".$catid."';";
	$resB   = mssql_query($qryB);
	$rowB   = mssql_fetch_array($resB);

	echo "<table class=\"outer\" width=\"750\" align=\"center\" border=0>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"left\" colspan=\"6\"><b>Material Master List by Product Line (".$catid.")</b></td>\n";
	echo "      <td class=\"gray\" align=\"right\"><img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\"></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Code</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Part #</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Description</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Cost</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Retail</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"right\" colspan=\"2\"><font color=\"blue\">".$nrowsA."</font> Items</td>\n";
	echo "   </tr>\n";

	while($rowA=mssql_fetch_array($resA))
	{
		echo "   <tr>\n";
		echo "      <form method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"list_edit\">\n";
		echo "         <input type=\"hidden\" name=\"type\" value=\"0\">\n";
		echo "         <input type=\"hidden\" name=\"catid\" value=\"".$catid."\">\n";
		echo "         <input type=\"hidden\" name=\"id\" value=\"".$rowA['id']."\">\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         ".$rowA['masgrp']."\n";
		echo "      </td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <input type=\"text\" name=\"vpnum\" size=\"20\" maxlength=\"25\" value=\"".$rowA['vpnum']."\">\n";
		echo "      </td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <input type=\"text\" name=\"item\" size=\"50\" maxlength=\"65\" value=\"".$rowA['item']."\">\n";
		echo "      </td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <input type=\"text\" name=\"bp\" size=\"10\" maxlength=\"10\" value=\"".number_format($rowA['bp'],2,'.','')."\">\n";
		echo "      </td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <input type=\"text\" name=\"rp\" size=\"10\" maxlength=\"10\" value=\"".number_format($rowA['rp'],2,'.','')."\">\n";
		echo "      </td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/save.gif\" alt=\"Save Item\">\n";
		echo "      </td>\n";
		echo "      </form>\n";
		echo "      <form method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"edit\">\n";
		echo "         <input type=\"hidden\" name=\"id\" value=\"".$rowA['id']."\">\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Item\">\n";
		echo "      </td>\n";
		echo "      </form>\n";
		echo "      </tr>\n";
	}
	echo "   </table>\n";
}

function material_list_by_vendor($vid) // Items List by Category
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	if (empty($_GET['order']))
	{
		$order="id";
	}

	$qryA   = "SELECT * FROM material_master WHERE vid='".$vid."' ORDER BY ".$order.";";
	$resA   = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	$qryB   = "SELECT vid,name FROM vendors WHERE vid='".$vid."';";
	$resB   = mssql_query($qryB);
	$rowB   = mssql_fetch_array($resB);

	echo "<table class=\"outer\" width=\"750\" align=\"center\" border=0>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"left\" colspan=\"6\"><b>Material Master List by Vendor (".$rowB['name'].")</b></td>\n";
	echo "      <td class=\"gray\" align=\"right\">\n";
	echo "			<img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Code</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Part #</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Description</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Cost</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"center\">Retail</td>\n";
	echo "      <td class=\"ltgray_und\" align=\"right\" colspan=\"2\"><font color=\"blue\">".$nrowsA."</font> Items</td>\n";
	echo "   </tr>\n";

	while($rowA=mssql_fetch_array($resA))
	{
		echo "   <tr>\n";
		echo "      <form method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"list_edit\">\n";
		echo "         <input type=\"hidden\" name=\"type\" value=\"1\">\n";
		echo "         <input type=\"hidden\" name=\"vid\" value=\"".$vid."\">\n";
		echo "         <input type=\"hidden\" name=\"id\" value=\"".$rowA['id']."\">\n";
		echo "      <td align=\"center\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         ".$rowA['masgrp']."\n";
		echo "      </td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <input type=\"text\" name=\"vpnum\" size=\"15\" maxlength=\"25\" value=\"".$rowA['vpnum']."\">\n";
		echo "      </td>\n";
		echo "      <td align=\"left\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <input type=\"text\" name=\"item\" size=\"65\" maxlength=\"65\" value=\"".$rowA['item']."\">\n";
		echo "      </td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <input type=\"text\" name=\"bp\" size=\"10\" maxlength=\"10\" value=\"".number_format($rowA['bp'],2,'.','')."\">\n";
		echo "      </td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "         <input type=\"text\" name=\"rp\" size=\"10\" maxlength=\"10\" value=\"".number_format($rowA['rp'],2,'.','')."\">\n";
		echo "      </td>\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/save.gif\" alt=\"Save Item\">\n";
		echo "      </td>\n";
		echo "      </form>\n";
		echo "      <form method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "         <input type=\"hidden\" name=\"subq\" value=\"edit\">\n";
		echo "         <input type=\"hidden\" name=\"id\" value=\"".$rowA['id']."\">\n";
		echo "      <td align=\"right\" class=\"wh_und\" valign=\"bottom\">\n";
		echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Item\">\n";
		echo "      </td>\n";
		echo "      </form>\n";
		echo "      </tr>\n";
	}
	echo "   </table>\n";
}

function material_add_by_cat($cat)
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$qryA   = "SELECT vid,name FROM vendors;";
	$resA   = mssql_query($qryA);

	$qryB   = "SELECT mid,abrv FROM mtypes;";
	$resB   = mssql_query($qryB);

	$qryC   = "SELECT DISTINCT(masgrp) FROM material_master WHERE masgrp!='0' ORDER BY masgrp ASC;";
	$resC   = mssql_query($qryC);

	echo "<table class=\"outer\" width=\"750\" align=\"center\">\n";
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"insert_by_cat\">\n";
	echo "<input type=\"hidden\" name=\"catid\" value=\"0\">\n";
	echo "   <tr>\n";
	echo "      <td class=\"ltgray_und\" align=\"left\"><b>Material Master List Maintenance (Add New)</b></td>\n";
	echo "      <td class=\"ltgray_und\" align=\"right\"><img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\"></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Product Line:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <select name=\"masgrp\">\n";

	while($rowC=mssql_fetch_array($resC))
	{
		if ($cat==$rowC['masgrp'])
		{
			echo "            <option value=\"".$rowC['masgrp']."\" SELECTED>".$rowC['masgrp']."</option>\n";
		}
		else
		{
			echo "            <option value=\"".$rowC['masgrp']."\">".$rowC['masgrp']."</option>\n";
		}
	}

	echo "         </select>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Vendor:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <select name=\"vid\">\n";

	while($rowA=mssql_fetch_array($resA))
	{
		echo "            <option value=\"".$rowA['vid']."\">".$rowA['name']."</option>\n";
	}

	echo "         </select>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Vendor Part#:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input type=\"text\" name=\"vpnum\" size=\"12\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Material Code:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input type=\"text\" name=\"code\" size=\"12\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Description:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input type=\"text\" name=\"item\" size=\"64\" maxlength=\"64\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b></b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input type=\"text\" name=\"atrib1\" size=\"64\" maxlength=\"64\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b></b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input type=\"text\" name=\"atrib2\" size=\"64\" maxlength=\"64\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b></b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input type=\"text\" name=\"atrib3\" size=\"64\" maxlength=\"64\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Supplier:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <select name=\"supplier\">\n";
	echo "            <option value=\"0\" SELECTED>BlueHaven</option>\n";
	echo "            <option value=\"1\">Contractor</option>\n";
	echo "         </select>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>UOM:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <select name=\"mtype\">\n";

	while($rowB=mssql_fetch_array($resB))
	{
		echo "            <option value=\"".$rowB['mid']."\">".$rowB['abrv']."</option>\n";
	}

	echo "         </select>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Cost:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input type=\"text\" name=\"bp\" value=\"0.00\" size=\"15\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Retail:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input type=\"text\" name=\"rp\" value=\"0.00\" size=\"15\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" colspan=\"2\"><input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Add Item\"></td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function material_add_by_vendor($vid)
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$qryA   = "SELECT vid,name FROM vendors ORDER BY name ASC;";
	$resA   = mssql_query($qryA);

	$qryB   = "SELECT mid,abrv FROM mtypes;";
	$resB   = mssql_query($qryB);

	$qryC   = "SELECT distinct(masgrp) FROM material_master WHERE masgrp!='0' ORDER BY masgrp ASC;";
	$resC   = mssql_query($qryC);

	$qryD	= "SELECT officeid,name FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resD = mssql_query($qryD);
	$rowD	= mssql_fetch_array($resD);

	echo "<table class=\"outer\" width=\"750\" align=\"center\">\n";
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"insert_by_vendor\">\n";
	echo "<input type=\"hidden\" name=\"catid\" value=\"0\">\n";
	echo "   <tr>\n";
	echo "      <td class=\"ltgray_und\" align=\"left\"><b>Material Master List Maintenance by Vendor (Add New)</b></td>\n";
	echo "      <td class=\"ltgray_und\" align=\"right\"><img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\"></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Product Line:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <select name=\"masgrp\">\n";
	echo "				<option value=\"0\">None</option>\n";

	while($rowC=mssql_fetch_array($resC))
	{
		echo "            <option value=\"".$rowC['masgrp']."\">".$rowC['masgrp']."</option>\n";
	}

	echo "         </select>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Vendor:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <select name=\"vid\">\n";

	while($rowA=mssql_fetch_array($resA))
	{
		if ($vid==$rowA['vid'])
		{
			echo "            <option value=\"".$rowA['vid']."\" SELECTED>".$rowA['name']."</option>\n";
		}
		else
		{
			echo "            <option value=\"".$rowA['vid']."\">".$rowA['name']."</option>\n";
		}
	}

	echo "         </select>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Vendor Part#:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <input type=\"text\" name=\"vpnum\" size=\"12\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Material Code:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <input type=\"text\" name=\"code\" size=\"12\" value=\"0\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Description:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <input type=\"text\" name=\"item\" size=\"64\" maxlength=\"64\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b></b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <input type=\"text\" name=\"atrib1\" size=\"64\" maxlength=\"64\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b></b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <input type=\"text\" name=\"atrib2\" size=\"64\" maxlength=\"64\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b></b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <input type=\"text\" name=\"atrib3\" size=\"64\" maxlength=\"64\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Supplier:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <select name=\"supplier\">\n";
	echo "            <option value=\"0\" SELECTED>BlueHaven</option>\n";
	echo "            <option value=\"1\">Contractor</option>\n";
	echo "         </select>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Unique to ".$rowD['name'].":</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <input class=\"checkboxgry\" type=\"checkbox\" name=\"oid\" value=\"".$rowD['officeid']."\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>UOM:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <select name=\"mtype\">\n";

	while($rowB=mssql_fetch_array($resB))
	{
		echo "            <option value=\"".$rowB['mid']."\">".$rowB['abrv']."</option>\n";
	}

	echo "         </select>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Cost:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <input type=\"text\" name=\"bp\" value=\"0.00\" size=\"15\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Retail:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "         <input type=\"text\" name=\"rp\" value=\"0.00\" size=\"15\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\" valign=\"bottom\" colspan=\"2\"><input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Add Item\"></td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function material_insert_by_vendor()
{
	$cat			=$_REQUEST['catid'];
	$vid			=$_REQUEST['vid'];
	$vpnum		=$_REQUEST['vpnum'];
	$code 		=$_REQUEST['code'];
	$item			=replacecomma($_REQUEST['item']);
	$atrib1		=replacecomma($_REQUEST['atrib1']);
	$atrib2		=replacecomma($_REQUEST['atrib2']);
	$atrib3		=replacecomma($_REQUEST['atrib3']);
	$supplier	=$_REQUEST['supplier'];
	$mtype		=$_REQUEST['mtype'];
	$bp			=$_REQUEST['bp'];
	$rp			=$_REQUEST['rp'];
	$masgrp		=$_REQUEST['masgrp'];

	$qry   = "SELECT id,code,vpnum FROM material_master WHERE vpnum='".$vpnum."';";
	$res   = mssql_query($qry);
	$row	 = mssql_fetch_array($res);
	$nrows = mssql_num_rows($res);

	//echo $qry."<br>";

	if ($row['vpnum']==$vpnum)
	{
		echo "<font color=\"red\">ERROR!</font>: VENDOR PART NUMBER <b>".$vpnum."</b> already exists. Please Click the back button and try again.";
		exit;
	}
	else
	{
		$qryB   = "SELECT id,code,vpnum FROM material_master WHERE code='".$code."';";
		$resB   = mssql_query($qryB);
		$rowB	 = mssql_fetch_array($resB);
		$nrowsB = mssql_num_rows($resB);

		if ($code!=0 && $rowB['code']==$code)
		{
			echo "<font color=\"red\">ERROR!</font>: CODE <b>".$code."</b> already exists. Please Click the back button and try again.";
			exit;
		}
		else
		{
			if (empty($_REQUEST['oid']))
			{
				$qryA = "INSERT INTO material_master (cat,vid,vpnum,code,item,atrib1,atrib2,atrib3,supplier,mtype,bp,rp,masgrp) VALUES ('".$cat."','".$vid."','".$vpnum."','".$code."','".$item."','".$atrib1."','".$atrib2."','".$atrib3."','".$supplier."','".$mtype."','".$bp."','".$rp."','".$masgrp."');";
				$resA = mssql_query($qryA);
			}
			else
			{
				$qryA = "INSERT INTO material_master (officeid,cat,vid,vpnum,code,item,atrib1,atrib2,atrib3,supplier,mtype,bp,rp,masgrp) VALUES ('".$_SESSION['officeid']."','".$cat."','".$vid."','".$vpnum."','".$code."','".$item."','".$atrib1."','".$atrib2."','".$atrib3."','".$supplier."','".$mtype."','".$bp."','".$rp."','".$masgrp."');";
				$resA = mssql_query($qryA);
			}

			material_list_by_vendor($_REQUEST['vid']);
		}
	}
}

function material_insert()
{
	$cat		=$_REQUEST['cat'];
	$vid		=$_REQUEST['vid'];
	$vpnum		=$_REQUEST['vpnum'];
	$code 		=$_REQUEST['code'];
	$item		=$_REQUEST['item'];
	$atrib1		=$_REQUEST['atrib1'];
	$atrib2		=$_REQUEST['atrib2'];
	$atrib3		=$_REQUEST['atrib3'];
	$supplier	=$_REQUEST['supplier'];
	$mtype		=$_REQUEST['mtype'];
	$bp			=$_REQUEST['bp'];
	$rp			=$_REQUEST['rp'];
	$masgrp		=$_REQUEST['masgrp'];

	$qry   = "SELECT vpnum FROM material_master WHERE vpnum='".$vpnum."';";
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//echo $qry."<br>";

	if ($nrows >= 1)
	{
		echo "That Part Number ".$vpnum." already exists. Please Click the back button and try again.";
		exit;
	}
	else
	{
		$qryA = "INSERT INTO material_master (cat,vid,vpnum,code,item,atrib1,atrib2,atrib3,supplier,mtype,bp,rp,masgrp) VALUES ('$cat','$vid','$vpnum','$code','$item','$atrib1','$atrib2','$atrib3','$supplier','$mtype','$bp','$rp','$masgrp');";
		$resA = mssql_query($qryA);

		//echo $qryA."<br>";
	}
}

function material_edit($id)
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	$qryA   = "SELECT * FROM material_master WHERE id='".$id."';";
	$resA   = mssql_query($qryA);
	$rowA   = mssql_fetch_array($resA);
	$nrowsA = mssql_num_rows($resA);

	$qryB   = "SELECT vid,name FROM vendors ORDER BY name ASC;";
	$resB   = mssql_query($qryB);

	$qryC   = "SELECT mid,abrv FROM mtypes ORDER BY abrv ASC;";
	$resC   = mssql_query($qryC);

	$qryD   = "SELECT catid,name FROM MM_cats ORDER BY name;";
	$resD   = mssql_query($qryD);

	//$qryE   = "SELECT id,masgrp,abrev,name FROM material_grp_codes WHERE active=1 ORDER BY abrev;";
	//$resE   = mssql_query($qryE);

	$qryE   = "SELECT DISTINCT(masgrp) FROM material_master WHERE masgrp!='0' ORDER BY masgrp;";
	$resE   = mssql_query($qryE);
	
	$qryF   = "SELECT * FROM vendor_program ORDER BY vprogram;";
	$resF   = mssql_query($qryF);

	if ($nrowsA < 1)
	{
		echo "Sorry, that Item does not exist in the Database";
	}
	elseif ($nrowsA > 1)
	{
		echo "a Problem exists with ".$rowA['item'].". Please contact a SysAdmin";
	}
	else
	{
		echo "<table class=\"outer\" width=\"750\" align=\"center\">\n";
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"mat\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"update\">\n";
		echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\">\n";
		echo "<input type=\"hidden\" name=\"cat\" value=\"0\">\n";
		echo "   <tr>\n";
		echo "      <td class=\"ltgray_und\" align=\"left\"><b>Material Master Maintenance (Edit Item)</b></td>\n";
		echo "      <td class=\"ltgray_und\" align=\"right\"><img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\"></td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Product Line:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <select name=\"masgrp\">\n";
		echo "            <option value=\"0\">None</option>\n";

		while($rowE=mssql_fetch_array($resE))
		{
			if ($rowA['masgrp']==$rowE['masgrp'])
			{
				echo "            <option value=\"".$rowE['masgrp']."\" SELECTED>".$rowE['masgrp']."</option>\n";
			}
			else
			{
				echo "            <option value=\"".$rowE['masgrp']."\">".$rowE['masgrp']."</option>\n";
			}
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		/*
		echo "   <tr>\n";
		echo "      <td align=\"right\" valign=\"bottom\"><b>Category:</b></td>\n";
		echo "      <td align=\"left\" valign=\"bottom\">\n";
		echo "         <select name=\"cat\">\n";

		while($rowD=mssql_fetch_array($resD))
		{
			if ($rowD['catid']==$rowA['cat'])
			{
				echo "            <option value=\"".$rowD['catid']."\" SELECTED>".$rowD['name']."</option>\n";
			}
			else
			{
				echo "            <option value=\"".$rowD['catid']."\">".$rowD['name']."</option>\n";
			}
		}
		echo "         </select>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		*/
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Vendor:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <select name=\"vid\">\n";

		while($rowB=mssql_fetch_array($resB))
		{
			if ($rowB['vid']==$rowA['vid'])
			{
				echo "            <option value=\"".$rowB['vid']."\" SELECTED>".$rowB['name']."</option>\n";
			}
			else
			{
				echo "            <option value=\"".$rowB['vid']."\">".$rowB['name']."</option>\n";
			}
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Vendor Program:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <select name=\"vprog\">\n";

		while($rowF=mssql_fetch_array($resF))
		{
			if ($rowA['vprog']==$rowF['vid'])
			{
				echo "            <option value=\"".$rowF['vid']."\" SELECTED>".$rowF['vprogram']."</option>\n";
			}
			else
			{
				echo "            <option value=\"".$rowF['vid']."\">".$rowF['vprogram']."</option>\n";
			}
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Vendor Part#:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"vpnum\" value=\"".$rowA['vpnum']."\" size=\"12\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Material Code:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"code\" value=\"".$rowA['code']."\" size=\"12\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Item:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"item\" value=\"".$rowA['item']."\" size=\"64\" maxlength=\"64\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b></b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"atrib1\" value=\"".$rowA['atrib1']."\" size=\"64\" maxlength=\"64\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b></b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"atrib2\" value=\"".$rowA['atrib2']."\" size=\"64\" maxlength=\"64\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b></b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"atrib3\" value=\"".$rowA['atrib3']."\" size=\"64\" maxlength=\"64\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Supplier:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <select name=\"supplier\">\n";

		if ($rowA['supplier']==0)
		{
			echo "            <option value=\"0\" SELECTED>BlueHaven</option>\n";
			echo "            <option value=\"1\">Contractor</option>\n";
		}
		else
		{
			echo "            <option value=\"0\">BlueHaven</option>\n";
			echo "            <option value=\"1\" SELECTED>Contractor</option>\n";
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>UOM:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <select name=\"mtype\">\n";

		while($rowC=mssql_fetch_array($resC))
		{
			if ($rowC['mid']==$rowA['mtype'])
			{
				echo "            <option value=\"".$rowC['mid']."\" SELECTED>".$rowC['abrv']."</option>\n";
			}
			else
			{
				echo "            <option value=\"".$rowC['mid']."\">".$rowC['abrv']."</option>\n";
			}
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Cost:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"bp\" value=\"".$rowA['bp']."\" size=\"15\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Retail:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "         <input type=\"text\" name=\"rp\" value=\"".$rowA['rp']."\" size=\"15\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\" colspan=\"2\"><input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Save\"></td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
}

function material_update($id)
{
	$id			=$_REQUEST['id'];
	$cat        =$_REQUEST['cat'];
	$vid		=$_REQUEST['vid'];
	$vprog		=$_REQUEST['vprog'];
	$vpnum		=$_REQUEST['vpnum'];
	$code 		=$_REQUEST['code'];
	$item		=replacecomma($_REQUEST['item']);
	$atrib1		=replacecomma($_REQUEST['atrib1']);
	$atrib2		=replacecomma($_REQUEST['atrib2']);
	$supplier	=$_REQUEST['supplier'];
	$mtype		=$_REQUEST['mtype'];
	$bp			=$_REQUEST['bp'];
	$rp			=$_REQUEST['rp'];
	$masgrp		=$_REQUEST['masgrp'];

	$qryA = "UPDATE material_master SET cat='$cat',vid='$vid',vpnum='$vpnum',vprog='$vprog',code='$code',item='$item',atrib1='$atrib1',atrib2='$atrib2',supplier='$supplier',mtype='$mtype',bp='$bp',rp='$rp',masgrp='$masgrp' WHERE id=$id;";
	$resA = mssql_query($qryA);

	material_edit($id);
	//material_cat_list();
}

function material_update_from_list()
{
	$qryA = "UPDATE material_master SET vpnum='".$_REQUEST['vpnum']."',item='".$_REQUEST['item']."',bp='".$_REQUEST['bp']."',rp='".$_REQUEST['rp']."' WHERE id='".$_REQUEST['id']."';";
	$resA = mssql_query($qryA);

	if ($_REQUEST['type']==1)
	{
		material_list_by_vendor($_REQUEST['vid']);
	}
	else
	{
		material_list_by_cat($_REQUEST['catid']);
	}
}

function material_item_search()
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}

	if (empty($_REQUEST['stext']))
	{
		echo "<b>Go back and enter a Search string.</b>";
		exit;
	}

	$qryA  = "SELECT * from material_master WHERE ".$_REQUEST['field']." LIKE '%".$_REQUEST['stext']."%';";
	$resA  = mssql_query($qryA);
	$nrows = mssql_num_rows($resA);

	if ($nrows < 1)
	{
		echo "<b>The item search for</b> <font color=\"red\">".$_REQUEST['stext']."</font> <b>did not produce any results.</b><br> Click the Back button and try again.";
	}
	if ($nrows > 1)
	{
		echo "<table width=\"60%\" align=\"center\" border=0>\n";
		echo "   <tr>\n";
		echo "      <th align=\"left\" colspan=\"7\">Search Results for <font color=\"red\">".$_REQUEST['stext']."</font></th>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <th align=\"center\">Code</th>\n";
		echo "      <th align=\"center\">Part #</th>\n";
		echo "      <th align=\"center\">Description</th>\n";
		echo "      <th align=\"center\">Cost</th>\n";
		echo "      <th align=\"center\">Retail</th>\n";
		echo "      <th align=\"right\" colspan=\"2\">Found <font color=\"blue\">".$nrows."</font> Item(s)</th>\n";
		echo "   </tr>\n";

		while ($rowA=mssql_fetch_array($resA))
		{
			echo "   <tr>\n";
			echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			//echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			//echo "         <input type=\"hidden\" name=\"call\" value=\"mat\">\n";
			//echo "         <input type=\"hidden\" name=\"subq\" value=\"list_edit\">\n";
			//echo "         <input type=\"hidden\" name=\"type\" value=\"0\">\n";
			//echo "         <input type=\"hidden\" name=\"catid\" value=\"".$rowA['cat'."\">\n";
			//echo "         <input type=\"hidden\" name=\"id\" value=\"".$rowA['id']."\">\n";
			echo "      <td align=\"right\" class=\"wh\" valign=\"bottom\">\n";
			echo "         ".$rowA['code']."\n";
			echo "      </td>\n";
			echo "      <td align=\"left\" class=\"wh\" valign=\"bottom\">\n";
			echo "         <input type=\"text\" class=\"bboxl\" name=\"vpnum\" size=\"20\" maxlength=\"25\" value=\"".$rowA['vpnum']."\">\n";
			echo "      </td>\n";
			echo "      <td align=\"left\" class=\"wh\" valign=\"bottom\">\n";
			echo "         <input type=\"text\" class=\"bboxl\" name=\"item\" size=\"50\" maxlength=\"65\" value=\"".$rowA['item']."\">\n";
			echo "      </td>\n";
			echo "      <td align=\"right\" class=\"wh\" valign=\"bottom\">\n";
			echo "         <input type=\"text\" class=\"bbox\" name=\"bp\" size=\"10\" maxlength=\"10\" value=\"".$rowA['bp']."\">\n";
			echo "      </td>\n";
			echo "      <td align=\"right\" class=\"wh\" valign=\"bottom\">\n";
			echo "         <input type=\"text\" class=\"bbox\" name=\"rp\" size=\"10\" maxlength=\"10\" value=\"".$rowA['rp']."\">\n";
			echo "      </td>\n";
			echo "      <td align=\"right\" class=\"wh\" valign=\"bottom\">\n";
			//echo "         <button type=\"submit\">Update Item</button>\n";
			echo "      </td>\n";
			echo "      </form>\n";
			echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "         <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "         <input type=\"hidden\" name=\"call\" value=\"mat\">\n";
			echo "         <input type=\"hidden\" name=\"subq\" value=\"edit\">\n";
			echo "         <input type=\"hidden\" name=\"id\" value=\"".$rowA['id']."\">\n";
			echo "      <td align=\"right\" class=\"wh\" valign=\"bottom\">\n";
			echo "         <button type=\"submit\">Expand Item</button>\n";
			echo "      </td>\n";
			echo "      </form>\n";
			echo "      </tr>\n";
		}
		echo "   </table>\n";
	}
	else
	{
		$rowA  = mssql_fetch_array($resA);
		material_edit($rowA['id']);
	}
}

function laboritemfromretail()
{
	$MAS=$_SESSION['pb_code'];
	$officeid=$_SESSION['officeid'];

	$qryp0  = "SELECT phsid,phsname FROM phasebase WHERE phstype!='M';";
	$resp0  = mssql_query($qryp0);
	//$rowp0  = mssql_fetch_row($resp0);

	if ($_REQUEST['phsid']!=0)
	{
		$qryp1  = "SELECT rphsid FROM phasebase WHERE phsid='".$_REQUEST['phsid']."';";
		$resp1  = mssql_query($qryp1);
		$rowp1  = mssql_fetch_row($resp1);

		$qry    = "SELECT MAX(accid) FROM [".$MAS."inventory] WHERE officeid='".$_REQUEST['officeid']."' AND phsid='".$_REQUEST['phsid']."';";
		$res    = mssql_query($qry);
		$row    = mssql_fetch_row($res);
	}

	$qryA   = "SELECT qid,qtype FROM qtypes ORDER BY id";
	$resA   = mssql_query($qryA);

	$qryB   = "SELECT id,aid,item FROM [".$MAS."acc] WHERE officeid='".$officeid."';";
	$resB   = mssql_query($qryB);

	$qryC   = "SELECT mid,abrv,name FROM mtypes ORDER BY mid;";
	$resC   = mssql_query($qryC);

	//$qryC   = "SELECT DISTINCT(accid) FROM inventory WHERE officeid='".$_REQUEST['officeid']."' AND phsid='".$_REQUEST['phsid']."';";
	//$resC   = mssql_query($qryC);

	if ($_REQUEST['phsid']!=0)
	{
		if ($row[0]<=1)
		{
			$maccid=($_REQUEST['phsid'])*10000;
		}
		else
		{
			$maccid=$row[0]+1;
		}
	}
	else
	{
		$maccid=0;
	}

	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"cost\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"ins\">\n";
	echo "<input type=\"hidden\" name=\"phsid\" value=\"".$_REQUEST['phsid']."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
	echo "<input type=\"hidden\" name=\"zcharge\" value=\"0\">\n";
	echo "<table class=\"outer\" border=0 align=\"center\" width=\"60%\">\n";
	echo "<tr>\n";
	echo "   <th colspan=\"3\" align=\"left\"><b>Add Labor Cost Item: (Auto Fill by Retail Item)</b></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Phase:</b></td>\n";
	echo "   <td>\n";
	echo "      <select name=\"phsid\">\n";

	if ($_REQUEST['phsid']==0)
	{
		echo "         <option value=\"0\" SELECTED>None</option>\n";
	}
	else
	{
		echo "         <option value=\"0\">None</option>\n";
	}

	while($rowp0 = mssql_fetch_row($resp0))
	{
		if ($_REQUEST['phsid']==$rowp0[0])
		{
			echo "         <option value=\"$rowp0[0]\" SELECTED>$rowp0[1]</option>\n";
		}
		else
		{
			echo "         <option value=\"$rowp0[0]\">$rowp0[1]</option>\n";
		}
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Code:</b></td>\n";
	echo "   <td><input type=\"text\" name=\"accid\" value=\"".$maccid."\" size=\"10\" maxlength=\"10\"></td>\n";
	echo "   <td rowspan=\"6\" valign=\"bottom\" align=\"right\">\n";
	echo "      <table border=0>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"left\" colspan=\"2\"><b><u>Control Options:</u></b></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Active:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"active\">\n";
	echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
	echo "                  <option value=\"0\">No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Base Item:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"baseitem\">\n";
	echo "                  <option value=\"1\">Yes</option>\n";
	echo "                  <option value=\"0\" SELECTED>No</option>\n";
	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Calc Method:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"qtype\">\n";
	echo "                  <option value=\"0\" SELECTED>None</option>\n";

	while($rowA = mssql_fetch_row($resA))
	{
		if ($rowA[0]==$_REQUEST['qtype'])
		{
			echo "                  <option value=\"$rowA[0]\" SELECTED>$rowA[1]</option>\n";
		}
		else
		{
			echo "                  <option value=\"$rowA[0]\">$rowA[1]</option>\n";
		}
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Calc Quantity:</b></td>\n";
	echo "            <td><input type=\"text\" name=\"quantity\" value=\"0\" size=\"4\" maxlength=\"4\"></td>\n";
	echo "         </tr>\n";
	echo "         <tr>\n";
	echo "	         <td align=\"right\"><b>Spa Item:</b></td>\n";
	echo "            <td>\n";
	echo "               <select name=\"spaitem\">\n";

	if ($_REQUEST['spaitem']==0)
	{
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
	}
	else
	{
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
	}

	echo "               </select>\n";
	echo "            </td>\n";
	echo "         </tr>\n";
	echo "      </table>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Description:</b></td>\n";
	echo "   <td><input type=\"text\" name=\"item\" value=\"".$_REQUEST['item']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td><input type=\"text\" name=\"atrib1\" value=\"".$_REQUEST['atrib1']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td><input type=\"text\" name=\"atrib2\" value=\"".$_REQUEST['atrib2']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b></b></td>\n";
	echo "   <td><input type=\"text\" name=\"atrib3\" value=\"".$_REQUEST['atrib3']."\" size=\"51\" maxlength=\"50\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>Low Range:</b></td>\n";
	echo "   <td>\n";
	echo "      <input type=\"text\" name=\"lrange\" value=\"".$_REQUEST['lrange']."\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td align=\"right\"><b>High Range:</b></td>\n";
	echo "   <td>\n";
	echo "      <input type=\"text\" name=\"hrange\" value=\"".$_REQUEST['hrange']."\" size=\"5\" maxlength=\"5\">\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Cost:</b></td>\n";
	echo "   <td><input type=\"text\" name=\"bprice\" size=\"15\" value=\"0.00\"><input type=\"hidden\" name=\"rprice\" value=\"0\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>UOM:</b></td>\n";
	echo "   <td>\n";
	echo "      <select name=\"mtype\">\n";

	while($rowC = mssql_fetch_row($resC))
	{
		if ($rowC[0]==$_REQUEST['mtype'])
		{
			echo "         <option value=\"$rowC[0]\" SELECTED>$rowC[1]</option>\n";
		}
		else
		{
			echo "         <option value=\"$rowC[0]\">$rowC[1]</option>\n";
		}
	}
	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Member of:</b></td>\n";
	echo "   <td>\n";
	echo "      <select name=\"raccid\">\n";
	echo "            <option value=\"0\" SELECTED>None</option>\n";

	while($rowB = mssql_fetch_row($resB))
	{
		if ($rowB[0]==$_REQUEST['retid'])
		{
			echo "         <option value=\"$rowB[0]\" SELECTED>($rowB[1]) $rowB[2]</option>\n";
		}
		else
		{
			echo "         <option value=\"$rowB[0]\">($rowB[1]) $rowB[2]</option>\n";
		}
	}

	echo "      </select> <b>Accessory</b>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td align=\"right\"><b>Related Item:</b></td>\n";
	echo "   <td>\n";
	echo "      <select name=\"rinvid\">\n";
	echo "            <option value=\"0\" SELECTED>None</option>\n";

	while($rowC = mssql_fetch_row($resC))
	{
		echo "         <option value=\"$rowC[0]\">$rowC[0]</option>\n";
	}

	echo "      </select>\n";
	echo "   </td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "   <td colspan=\"3\" align=\"center\"><button type=\"submit\">Insert Cost Item</button></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function materialitemfromretail()
{
	$MAS=$_SESSION['pb_code'];
	if ($_REQUEST['phsid']==0)
	{
		echo "<b>Material Items Require a Phase. Click the BACK button and select a Phase to place the Material item.";
		exit;
	}

	if ($_REQUEST['matid']==0)
	{
		invadd_mm1();
		//echo "Mat cat selection req";
	}
	else
	{

		$officeid=$_SESSION['officeid'];

		$qryp1  = "SELECT rphsid FROM phasebase WHERE phsid='".$_REQUEST['phsid']."';";
		$resp1  = mssql_query($qryp1);
		$rowp1  = mssql_fetch_row($resp1);

		$qryp2  = "SELECT * FROM material_master WHERE id='".$_REQUEST['matid']."';";
		$resp2  = mssql_query($qryp2);
		$rowp2  = mssql_fetch_array($resp2);

		$qry    = "SELECT MAX(accid) FROM [".$MAS."inventory] WHERE officeid='".$officeid."' AND phsid='".$_REQUEST['phsid']."';";
		$res    = mssql_query($qry);
		$row    = mssql_fetch_row($res);

		$qryA   = "SELECT qid,qtype FROM qtypes ORDER BY id";
		$resA   = mssql_query($qryA);

		$qryB   = "SELECT id,aid,item FROM [".$MAS."acc] WHERE officeid='".$officeid."';";
		$resB   = mssql_query($qryB);

		$qryC   = "SELECT DISTINCT(accid) FROM [".$MAS."inventory] WHERE officeid='".$officeid."' AND phsid='".$_REQUEST['phsid']."';";
		$resC   = mssql_query($qryC);

		$qryD   = "SELECT mid,abrv FROM mtypes;";
		$resD   = mssql_query($qryD);

		$qryE   = "SELECT commid,commtype FROM commtypes;";
		$resE   = mssql_query($qryE);

		if ($row[0]<=1)
		{
			$maccid=($_REQUEST['phsid'])*10000;
		}
		else
		{
			$maccid=$row[0]+1;
		}

		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"inv\">\n";
		echo "<input type=\"hidden\" name=\"subq\" value=\"ins\">\n";
		echo "<input type=\"hidden\" name=\"phsid\" value=\"".$_REQUEST['phsid']."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
		echo "<input type=\"hidden\" name=\"accid\" value=\"".$maccid."\">\n";
		echo "<input type=\"hidden\" name=\"matid\" value=\"".$_REQUEST['matid']."\">\n";
		echo "<input type=\"hidden\" name=\"rinvid\" value=\"0\">\n";
		echo "<table align=\"center\" border=0>\n";
		echo "<tr>\n";
		echo "	<th colspan=\"3\" align=\"left\"><b>Add Material Cost Item</b></th>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\"><b>Vendor Part #:</b></td>\n";
		echo "   <td><input type=\"text\" name=\"vpno\" value=\"".$rowp2['vpnum']."\"size=\"21\" maxlength=\"20\"></td>\n";
		echo "   <td rowspan=\"6\" valign=\"top\" align=\"right\">\n";
		echo "      <table>\n";
		echo "         <tr>\n";
		echo "	         <td align=\"left\" colspan=\"2\"><b><i>Display & Calc Controls:</i></b></td>\n";
		echo "         </tr>\n";
		echo "         <tr>\n";
		echo "	         <td align=\"right\"><b>Active:</b></td>\n";
		echo "            <td>\n";
		echo "               <select name=\"active\">\n";
		echo "                  <option value=\"1\" SELECTED>Yes</option>\n";
		echo "                  <option value=\"0\">No</option>\n";
		echo "               </select>\n";
		echo "            </td>\n";
		echo "         </tr>\n";
		echo "         <tr>\n";
		echo "	         <td align=\"right\"><b>Base Item:</b></td>\n";
		echo "            <td>\n";
		echo "               <select name=\"baseitem\">\n";
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
		echo "               </select>\n";
		echo "            </td>\n";
		echo "         </tr>\n";
		echo "         <tr>\n";
		echo "	         <td align=\"right\"><b>Ques/Calc Type:</b></td>\n";
		echo "            <td>\n";
		echo "               <select name=\"qtype\">\n";
		echo "                  <option value=\"0\" SELECTED>None</option>\n";

		while($rowA = mssql_fetch_row($resA))
		{
			if ($_REQUEST['qtype']==$rowA[0])
			{
				echo "                  <option value=\"$rowA[0]\" SELECTED>$rowA[1]</option>\n";
			}
			else
			{
				echo "                  <option value=\"$rowA[0]\">$rowA[1]</option>\n";
			}
		}

		echo "               </select>\n";
		echo "            </td>\n";
		echo "         </tr>\n";
		echo "         <tr>\n";
		echo "	         <td align=\"right\"><b>Calc Amt:</b></td>\n";
		echo "            <td><input type=\"text\" name=\"quantity\" value=\"".$_REQUEST['quan_calc']."\" size=\"4\" maxlength=\"4\"></td>\n";
		echo "         </tr>\n";
		echo "         <tr>\n";
		echo "	         <td align=\"right\"><b>Spa Item:</b></td>\n";
		echo "            <td>\n";
		echo "               <select name=\"spaitem\">\n";
		echo "                  <option value=\"1\">Yes</option>\n";
		echo "                  <option value=\"0\" SELECTED>No</option>\n";
		echo "               </select>\n";
		echo "            </td>\n";
		echo "         </tr>\n";
		echo "      </table>\n";
		echo "   </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\"><b>Description:</b></td>\n";
		echo "   <td><input type=\"text\" name=\"item\" value=\"".$rowp2['item']."\" size=\"64\" maxlength=\"64\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\"><b></b></td>\n";
		echo "   <td><input type=\"text\" name=\"atrib1\" value=\"".$rowp2['atrib1']."\" size=\"64\" maxlength=\"64\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\"><b></b></td>\n";
		echo "   <td><input type=\"text\" name=\"atrib2\" value=\"".$rowp2['atrib2']."\" size=\"64\" maxlength=\"64\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\"><b></b></td>\n";
		echo "   <td><input type=\"text\" name=\"atrib3\" value=\"".$rowp2['atrib3']."\" size=\"64\" maxlength=\"64\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\"><b>Cost:</b></td>\n";
		echo "   <td><input type=\"text\" name=\"bprice\" size=\"15\" value=\"".$rowp2['bp']."\"><input type=\"hidden\" name=\"rprice\" value=\"0\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	 <td align=\"right\"><b>Comm Type:</b></td>\n";
		echo "    <td>\n";
		echo "      <select name=\"commtype\">\n";

		while($rowE = mssql_fetch_row($resE))
		{
			echo "         <option value=\"$rowE[0]\">$rowE[1]</option>\n";
		}

		echo "      </select>\n";
		echo "    </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	 <td align=\"right\"><b>Comm Rate:</b></td>\n";
		echo "    <td><input type=\"text\" name=\"crate\" size=\"15\" value=\"0\"></td>\n";
		echo "</tr>\n";
		//echo "<tr>\n";
		//echo "	 <td align=\"right\"><b>Rebate:</b></td>\n";
		//echo "    <td><input type=\"text\" name=\"rebate\" size=\"15\" value=\"0\"></td>\n";
		//echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\"><b>Units:</b></td>\n";
		echo "   <td>\n";
		echo "      <select name=\"uom\">\n";

		while($rowD = mssql_fetch_row($resD))
		{
			if ($_REQUEST['mtype']==$rowD[0])
			{
				echo "         <option value=\"$rowD[0]\" SELECTED>$rowD[1]</option>\n";
			}
			else
			{
				echo "         <option value=\"$rowD[0]\">$rowD[1]</option>\n";
			}
		}

		echo "      </select>\n";
		echo "   </td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td align=\"right\"><b>Member of:</b></td>\n";
		echo "   <td>\n";
		echo "      <select name=\"raccid\">\n";
		echo "            <option value=\"0\" SELECTED>None</option>\n";

		while($rowB = mssql_fetch_row($resB))
		{
			if ($_REQUEST['id']==$rowB[0])
			{
				echo "         <option value=\"$rowB[0]\" SELECTED>($rowB[1]) $rowB[2]</option>\n";
			}
			else
			{
				echo "         <option value=\"$rowB[0]\">($rowB[1]) $rowB[2]</option>\n";
			}
		}

		echo "      </select>\n";
		echo "   </td>\n";
		echo "</tr>\n";
		/*
		echo "<tr>\n";
		echo "	<td align=\"right\"><b>Rel Inv Item:</b></td>\n";
		echo "   <td>\n";
		echo "      <select name=\"rinvid\">\n";
		echo "         <option value=\"0\" SELECTED>None</option>\n";

		while($rowC = mssql_fetch_row($resC))
		{
		echo "         <option value=\"$rowC[0]\">$rowC[0]</option>\n";
		}

		echo "      </select>\n";
		echo "   </td>\n";
		echo "</tr>\n";
		*/
		echo "<tr>\n";
		echo "   <td colspan=\"3\" align=\"center\"><button type=\"submit\">&nbsp;&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;&nbsp;</button></td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
}

?>