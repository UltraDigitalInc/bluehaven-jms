<?php

function move_leads()
{
	error_reporting(E_ALL);
	// This function will move leads, by salesrep id between office or interoffice.
	//	show_post_vars();
	if ($_SESSION['securityid']==MTRX_ADMIN || $_SESSION['securityid']==SYS_ADMIN)
	{
		echo "<table width=\"40%\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"2\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\"><b>Move Leads</b></td>\n";
		
		if ($_SESSION['subq']=="move1")
		{
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\"><b>Step 1</b></td>\n";
		}
		elseif ($_SESSION['subq']=="move2")
		{
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\"><b>Step 2</b></td>\n";
		}
		elseif ($_SESSION['subq']=="move3")
		{
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\"><b>Step 3</b></td>\n";
		}
		elseif ($_SESSION['subq']=="move4")
		{
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\"><b>Step 4</b></td>\n";
		}
		
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		
		if ($_POST['subq']=="move1")
		{
			echo "<input type=\"hidden\" name=\"subq\" value=\"move2\">\n";
		}
		elseif ($_POST['subq']=="move2")
		{
			echo "<input type=\"hidden\" name=\"subq\" value=\"move3\">\n";	
		}
		elseif ($_POST['subq']=="move3")
		{
			echo "<input type=\"hidden\" name=\"subq\" value=\"move4\">\n";	
		}
		
		echo "	<tr>\n";
		echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>From Office:</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
		
		if ($_POST['subq']=="move1")
		{
			$qryF  	= "SELECT officeid as foid,name as fname FROM offices WHERE active=1 ORDER by grouping,name ASC;";
			$resF  	= mssql_query($qryF);
			
			echo "						<select name=\"foid\">\n";
			
			while ($rowF  	= mssql_fetch_array($resF))
			{
				if (!empty($_POST['foid']) && $_POST['foid']==$rowF['foid'])
				{
					echo "<option value=\"".$rowF['foid']."\" SELECTED>".$rowF['fname']."</option>\n";
				}
				else
				{
					echo "<option value=\"".$rowF['foid']."\">".$rowF['fname']."</option>\n";
				}
			}
			
			echo "						</select>\n";
		}
		elseif ($_POST['subq']=="move2" || $_POST['subq']=="move3" || $_POST['subq']=="move4")
		{
			$qryF  	= "SELECT officeid as foid,name as fname FROM offices WHERE officeid='".$_POST['foid']."';";
			$resF  	= mssql_query($qryF);
			$rowF  	= mssql_fetch_array($resF);
			
			echo "<b><font color=\"blue\">".$rowF['fname']."</font></b>\n";
			echo "<input type=\"hidden\" name=\"foid\" value=\"".$rowF['foid']."\">\n";
		}
		
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>To Office:</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
		
		if ($_POST['subq']=="move1")
		{
			$qryT  	= "SELECT officeid as toid,name as tname FROM offices WHERE active=1 ORDER by grouping,name ASC;";
			$resT  	= mssql_query($qryT);
			
			echo "						<select name=\"toid\">\n";
			
			while ($rowT  	= mssql_fetch_array($resT))
			{
				if (!empty($_POST['toid']) && $_POST['toid']==$rowT['toid'])
				{
					echo "<option value=\"".$rowT['toid']."\" SELECTED>".$rowT['tname']."</option>\n";
				}
				else
				{
					echo "<option value=\"".$rowT['toid']."\">".$rowT['tname']."</option>\n";
				}
			}
			
			echo "						</select>\n";
		}
		elseif ($_POST['subq']=="move2" || $_POST['subq']=="move3" || $_POST['subq']=="move4")
		{
			$qryT  	= "SELECT officeid as toid,name as tname FROM offices WHERE officeid='".$_POST['toid']."';";
			$resT  	= mssql_query($qryT);
			$rowT  	= mssql_fetch_array($resT);
			
			echo "<b><font color=\"blue\">".$rowT['tname']."</font></b>\n";
			echo "<input type=\"hidden\" name=\"toid\" value=\"".$rowT['toid']."\">\n";
		}
		
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	
		if ($_POST['subq']=="move2" || $_POST['subq']=="move3" || $_POST['subq']=="move4")
		{
			$qryFa  	= "SELECT securityid as fsid,fname as ffname,lname as flname FROM security WHERE officeid='".$_POST['foid']."' ORDER by flname ASC;";
			$resFa  	= mssql_query($qryFa);
			
			$qryTa  	= "SELECT securityid as tsid,fname as tfname,lname as tlname FROM security WHERE officeid='".$_POST['toid']."' and substring(slevel,13,13)=1 ORDER by tlname ASC;";
			$resTa  	= mssql_query($qryTa);
			
			echo "	<tr>\n";
			echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>From SalesRep:</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
			
			if ($_POST['subq']=="move2")
			{
				echo "						<select name=\"fsid\">\n";
				
				while ($rowFa  = mssql_fetch_array($resFa))
				{
					if (!empty($_POST['fsid']) && $_POST['fsid']==$rowFa['fsid'])
					{
						echo "<option value=\"".$rowFa['fsid']."\" SELECTED>".$rowFa['flname'].", ".$rowFa['ffname']."</option>\n";
					}
					else
					{
						echo "<option value=\"".$rowFa['fsid']."\">".$rowFa['flname'].", ".$rowFa['ffname']."</option>\n";
					}
				}
				
				echo "						</select>\n";
			}
			elseif ($_POST['subq']=="move3" || $_POST['subq']=="move4")
			{
				$qryFa  	= "SELECT securityid as fsid,fname as ffname,lname as flname FROM security WHERE officeid='".$_POST['foid']."' and securityid='".$_POST['fsid']."';";
				$resFa  	= mssql_query($qryFa);
				$rowFa  = mssql_fetch_array($resFa);
				
				//echo $qryFa."<br>";
				
				echo "<b><font color=\"blue\">".$rowFa['flname'].", ".$rowFa['ffname']."</font></b>\n";
				echo "<input type=\"hidden\" name=\"fsid\" value=\"".$rowFa['fsid']."\">\n";
			}
			
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>To SalesRep:</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
			
			if ($_POST['subq']=="move2")
			{
				echo "						<select name=\"tsid\">\n";
				
				while ($rowTa 	= mssql_fetch_array($resTa))
				{
					if (!empty($_POST['tsid']) && $_POST['tsid']==$rowTa['tsid'])
					{
						echo "<option value=\"".$rowTa['tsid']."\" SELECTED>".$rowTa['tlname'].", ".$rowTa['tfname']."</option>\n";
					}
					else
					{
						echo "<option value=\"".$rowTa['tsid']."\">".$rowTa['tlname'].", ".$rowTa['tfname']."</option>\n";
					}
				}
				
				echo "						</select>\n";
			}
			elseif ($_POST['subq']=="move3" || $_POST['subq']=="move4")
			{
				$qryTa  	= "SELECT securityid as tsid,fname as tfname,lname as tlname FROM security WHERE officeid='".$_POST['toid']."' and securityid='".$_POST['tsid']."';";
				$resTa  	= mssql_query($qryTa);
				$rowTa 	= mssql_fetch_array($resTa);
				
				//echo $qryTa."<br>";
				
				echo "<b><font color=\"blue\">".$rowTa['tlname'].", ".$rowTa['tfname']."</font></b>\n";
				echo "<input type=\"hidden\" name=\"tsid\" value=\"".$rowTa['tsid']."\">\n";
			}
			
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>Date Range:</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
			
			if ($_POST['subq']=="move2")
			{
				echo "						<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
				echo "						<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
			}
			elseif ($_POST['subq']=="move3" || $_POST['subq']=="move4")
			{
				echo date("m/d/y",strtotime($_POST['d1']))." - ".date("m/d/y",strtotime($_POST['d2']));
				echo "						<input type=\"hidden\" name=\"d1\" value=\"".date("m/d/y",strtotime($_POST['d1']))."\">\n";
				echo "						<input type=\"hidden\" name=\"d2\" value=\"".date("m/d/y",strtotime($_POST['d2']))."\">\n";
			}
			
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			
			if ($_POST['subq']=="move3" || $_POST['subq']=="move4")
			{
				//echo $qryL."<br>";
				echo "	<tr>\n";
				echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
				echo "			<table class=\"outer\" width=\"100%\">\n";
				echo "				<tr>\n";
				echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>Leads Found:</b></td>\n";
				echo "				</tr>\n";
				echo "				<tr>\n";
				echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
				
				if ($_POST['subq']=="move3")
				{
					$qryL  	= "SELECT cid FROM cinfo WHERE officeid=".$_POST['foid']." ";
					$qryL   .= "and securityid=".$_POST['fsid']." and estid=0 and jobid='0' and njobid='0' ";
					$qryL   .= "and added >= '".$_POST['d1']."' and added <= '".$_POST['d2']." 11:59:59';";
					$resL  	= mssql_query($qryL);
					$nrowL 	= mssql_num_rows($resL);
					
					//echo $qryL."<br>";
					
					echo $nrowL;
					echo "<input type=\"hidden\" name=\"lcnt\" value=\"".$nrowL."\">\n";
					
					while($rowL 	= mssql_fetch_array($resL))
					{
						echo "<input type=\"hidden\" name=\"cids[]\" value=\"".$rowL['cid']."\">\n";
						//$cids_ar[]=$rowL['cid'];
					}					
				}
				elseif ($_POST['subq']=="move4")
				{
					echo $_POST['lcnt'];
					//echo "<input type=\"hidden\" name=\"lcnt\" value=\"".$_POST['lcnt']."\">\n";
				}
				
				echo "					</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
				echo "		</td>\n";
				echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
				
				if ($_POST['subq']=="move4")
				{					
					echo "			<table class=\"outer\" width=\"100%\">\n";
					echo "				<tr>\n";
					echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>Leads Moved:</b></td>\n";
					echo "				</tr>\n";
					echo "				<tr>\n";
					echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
					
					$err=0;
					if (is_array($_POST['cids']))
					{
						$lcnt=0;
						foreach ($_POST['cids'] as $cn => $cv)
						{
							
							$uid	 =	md5($cv).$_SESSION['securityid'];
							$qryU  = "DECLARE @tmid int ";
							$qryU .= "DECLARE @oname char(20) ";
							$qryU .= "SET @tmid=((SELECT MAX(custid) FROM cinfo WHERE officeid=".$_POST['toid'].") + 1) ";
							$qryU .= "SET @oname=(SELECT name FROM offices WHERE officeid=".$_POST['foid'].") ";
							$qryU .= "BEGIN TRAN ";
							$qryU .= "UPDATE cinfo SET officeid='".$_POST['toid']."',securityid='".$_POST['tsid']."',custid=@tmid WHERE officeid='".$_POST['foid']."' and cid='".$cv."' ";
							$qryU .= "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
							$qryU .= "VALUES ";
							$qryU .= "('".$cv."','".$_POST['foid']."','".$_SESSION['securityid']."','leads','Lead Moved from ' + @oname,'".$uid."')";
							$qryU .= "COMMIT; ";
							$resU = mssql_query($qryU);
							//echo $qryU."<br>";
							
							$lcnt++;
						}

						echo $lcnt;	
					}
					else
					{
						$err++;
						echo "None. An Error Occured.";
					}

					echo "					</td>\n";
					echo "				</tr>\n";
					echo "			</table>\n";
				}
				echo "		</td>\n";
				echo "	</tr>\n";
			}
		}
		
		
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\" colspan=\"2\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"center\" valign=\"top\">\n";
		
		if ($_SESSION['subq']=="move1")
		{
			echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Step 2\">\n";
		}
		elseif ($_SESSION['subq']=="move2")
		{
			echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Step 3\">\n";
		}
		elseif ($_SESSION['subq']=="move3")
		{
			echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Move\">\n";
		}
		elseif ($_SESSION['subq']=="move4")
		{
			if ($err == 0)
			{
				echo "<font color=\"blue\"><b>Move Process Complete!</b></font>";
			}
		}
		
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "</form>\n";
	}
	else
	{
		die('You do not have appropriate access Rights to View this Resource');
	}
}

function add_finan_cust($oid,$orig_oid,$cid,$sid,$uid)
{
	//echo "Adding WinFin<br>";
	error_reporting(E_ALL);
	$nsecid		=0;
	
	if (isset($_POST['finansrc']) && $_POST['finansrc']!=1)
	{
		$finan_src	=$_POST['finansrc']; // Submitted
	}
	else
	{
		$finan_src	=1; // Winners	
	}
	
	$qry  	= "SELECT cid FROM cinfo WHERE cid='".$cid."';";
	$res  	= mssql_query($qry);
	$row  	= mssql_fetch_array($res);
	$nrow 	= mssql_num_rows($res);
	
	$qry0a  	= "SELECT cid FROM tfinan_detail WHERE cid='".$cid."';";
	$res0a  	= mssql_query($qry0a);
	$nrow0a 	= mssql_num_rows($res0a);
	
	//echo $qry."<br>";
	
	if ($nrow==1 && $nrow0a==0)
	{
		$qry0  	= "SELECT name,gm,am FROM offices WHERE officeid='".$orig_oid."';";
		$res0  	= mssql_query($qry0);
		$row0  	= mssql_fetch_array($res0);
		
		//$qry0a  	= "SELECT cid FROM tfinan_detail WHERE cid='".$cid."';";
		//$res0a  	= mssql_query($qry0a);
		//$nrow0a 	= mssql_num_rows($res0a);
		
		$ctext  = "System Message - Finance Office Assigned: ".$row0['name'];		

		if ($row0['gm']!=0)
		{
			$nsecid=$row0['gm'];
		}
		else
		{
			$nsecid=$row0['am'];
		}

		$qry1   = "UPDATE cinfo SET finan_from='".$orig_oid."',finan_sec='".$nsecid."',finan_src='".$finan_src."',finan_date=getdate() WHERE officeid=".$oid." AND cid=".$cid.";";
		$res1   = mssql_query($qry1);
		//echo $qry1."<br>";

		if ($nrow0a==0)
		{
			$qry1a  = "INSERT INTO tfinan_detail (cid,officeid,finan_from,financlose,recdate,uid) VALUES ('".$cid."','".$oid."','".$orig_oid."',0,getdate(),'".$uid."');";
			$res1a  = mssql_query($qry1a);
		}

		$qry2   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
		$qry2  .= "VALUES ";
		$qry2  .= "('".$cid."','".$oid."','".$_SESSION['securityid']."','leads','".$ctext."','".$uid."')";
		$res2  = mssql_query($qry2);
	}
}

function add_zip_movedtoIVRmaint()
{
	//show_post_vars();
	if (strlen(trim($_POST['czip']))!=5 || !is_numeric(trim($_POST['czip'])))
	{
		echo "<font color=\"red\"><b>Error!</b></font><br>";
		echo "<b>Customer ZIP Code (".$_POST['czip']."): Invalid Format</b><br>";
	}
	else
	{
		$qry = "SELECT czip FROM zip_to_zip WHERE czip='".$_POST['czip']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		$nrow= mssql_num_rows($res);
		
		//echo $nrow."<br>";
		
		if ($nrow == 0 && !empty($_POST['czip']) && strlen(trim($_POST['czip']))==5)
		{
			//echo "ADDED!<BR>";
			if (!empty($_POST['q']) && $_POST['q']==1)
			{
				$pq=1;
			}
			else
			{
				$pq=0;
			}
			
			$qry1 = "INSERT INTO zip_to_zip (ozip,czip,careacode,ccity,ccounty,cstate,q,updtby,updated,u) values ('".$_POST['ozip']."','".$_POST['czip']."','".$_POST['careacode']."','".$_POST['ccity']."','".$_POST['ccounty']."','".$_POST['cstate']."','".$pq."','".$_SESSION['securityid']."',getdate(),'0');";
			$res1 = mssql_query($qry1);
		}
		else
		{
			$qry1 = "SELECT o.name,o.zip FROM zip_to_zip as z inner join offices as o on z.ozip=o.zip WHERE z.czip='".$_POST['czip']."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);
			$nrow1= mssql_num_rows($res1);
	
			//echo $qry1."<br>";
			//echo $nrow1."<br>";
			
			if ($nrow1 > 0)
			{
				echo "<font color=\"red\"><b>Error!</b></font><br>";
				echo "<b>Customer ZIP Code (".$_POST['czip']."): Already routing to ".$row1['name']." (".$row1['zip'].")</b><br>";
			}
		}
	}
	zip_search();
}

function add_zipold()
{
	if (strlen(trim($_POST['czip']))!=5 || !is_numeric(trim($_POST['czip'])))
	{
		echo "<font color=\"red\"><b>Error!</b></font><br>";
		echo "<b>Customer ZIP Code (".$_POST['czip']."): Invalid Format</b><br>";
	}
	else
	{
		$qry = "SELECT czip FROM zip_to_zip WHERE czip='".$_POST['czip']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		$nrow= mssql_num_rows($res);
		
		//echo $nrow."<br>";
		
		if ($nrow == 0 && !empty($_POST['czip']) && strlen(trim($_POST['czip']))==5)
		{
			//echo "ADDED!<BR>";
			$qry1 = "INSERT INTO zip_to_zip (ozip,czip) values ('".$_POST['ozip']."','".$_POST['czip']."');";
			$res1 = mssql_query($qry1);
		}
		else
		{
			$qry1 = "SELECT o.name,o.zip FROM zip_to_zip as z inner join offices as o on z.ozip=o.zip WHERE z.czip='".$_POST['czip']."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);
			$nrow1= mssql_num_rows($res1);
	
			//echo $qry1."<br>";
			//echo $nrow1."<br>";
			
			if ($nrow1 > 0)
			{
				echo "<font color=\"red\"><b>Error!</b></font><br>";
				echo "<b>Customer ZIP Code (".$_POST['czip']."): Already routing to ".$row1['name']." (".$row1['zip'].")</b><br>";
			}
		}
	}
	zip_search();
}

function upd_zap()
{
	$qry = "SELECT id FROM zip_link WHERE id='".$_POST['coid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	echo $nrow."<br>";
	
	if ($nrow > 0)
	{
		$qry1 = "UPDATE zip_link SET zip='".$_POST['ozip']."',area='".$_POST['area']."',pre='".$_POST['pre']."' WHERE id='".$_POST['coid']."';";
		$res1 = mssql_query($qry1);
	}
	
	zip_search();
}

function upd_ringto()
{
	$qry = "SELECT officeid FROM offices WHERE officeid='".$_POST['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	//echo $nrow."<br>";
	
	if ($nrow > 0 && $_POST['oringto']!=$_POST['nringto'])
	{	
		$qry2 = "UPDATE offices SET ringto='".$_POST['nringto']."' WHERE officeid='".$_POST['officeid']."';";
		$res2 = mssql_query($qry2);
	}
	
	zip_search();
}

function upd_zip_movedtoIVRmaint()
{
	//show_post_vars();
	$qry = "SELECT id FROM zip_to_zip WHERE id='".$_POST['coid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	if (!empty($_POST['q']) && $_POST['q']==1)
	{
		$pq=1;
	}
	else
	{
		$pq=0;
	}
	
	//echo $qry."<br>";
	
	if ($nrow > 0 && !empty($_POST['ozip']) && !empty($_POST['czip']))
	{
		$qry1 = "UPDATE
						zip_to_zip SET ozip='".$_POST['ozip']."',
						czip='".$_POST['czip']."',
						careacode='".$_POST['careacode']."',
						ccity='".$_POST['ccity']."',
						ccounty='".$_POST['ccounty']."',
						cstate='".$_POST['cstate']."',
						q='".$pq."',
						u='0',
						updtby='".$_SESSION['securityid']."',
						updated=getdate()
					WHERE id='".$_POST['coid']."';";
		$res1 = mssql_query($qry1);
		
		//echo $qry1."<br>";
	}
	
	zip_search();
}

function upd_zipold()
{
	$qry = "SELECT id FROM zip_to_zip WHERE id='".$_POST['coid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	//echo $qry."<br>";
	
	if ($nrow > 0 && !empty($_POST['ozip']) && !empty($_POST['czip']))
	{
		$qry1 = "UPDATE zip_to_zip SET ozip='".$_POST['ozip']."',czip='".$_POST['czip']."' WHERE id='".$_POST['coid']."';";
		$res1 = mssql_query($qry1);
		
		//echo $qry1."<br>";
	}
	
	zip_search();
}

function zip_maint_movedtoIVRmaint()
{
	//echo "TEST";
	error_reporting(E_ALL);
	
	$qry0 = "SELECT SYS_ADMIN,MTRX_ADMIN FROM master..bhest_config;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	//echo $qry0."<br>";
	
	if ($_SESSION['securityid']==$row0['SYS_ADMIN'] || $_SESSION['securityid']==$row0['MTRX_ADMIN'])
	{
		$qry1 = "SELECT officeid,name,zip,ringto,active FROM offices ORDER BY grouping,name ASC;";
		$res1 = mssql_query($qry1);
		$nrow1= mssql_num_rows($res1);
	
		echo "<table width=\"40%\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"center\" valign=\"top\"><b>Matrix Maintenance Tool</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"center\" valign=\"top\"><b><font color=\"red\">Warning!</font></b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"center\" valign=\"top\">Any modification to data in this search Tool has immediate effect<br> on both the Leads Routing Matrix and the 1-800 Call Routing Matrix.<br><font color=\"red\"><b>Use with Caution!</b></font></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"zip_search\">\n";
		echo "								<input type=\"hidden\" name=\"type\" value=\"off\">\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"right\"><b>Select Office:</b></td>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\">\n";
		echo "								<select name=\"sdata\">\n";
	
		while ($row1 = mssql_fetch_array($res1))
		{		
			$qry3 = "SELECT count(id) as cnt FROM zip_to_zip WHERE ozip='".$row1['zip']."';";
			$res3 = mssql_query($qry3);
			$row3 = mssql_fetch_array($res3);
			
			if ($row1['active']==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}
			
			echo "                           <option value=\"".$row1['zip']."\" class=\"".$ostyle."\">".$row1['name']." (".$row3['cnt'].")</option>\n";
		}
	
		echo "								</select>\n";
		echo "								<input type=\"hidden\" name=\"dsrc\" value=\"zip\">\n";
		echo "					</td>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"right\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
		echo "					</td>\n";
		echo "         					</form>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"3\" bgcolor=\"#d3d3d3\" align=\"center\"><hr width=\"80%\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"zip_search\">\n";
		echo "								<input type=\"hidden\" name=\"dsrc\" value=\"zip\">\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"right\"><b>ZIP Matrix:</b></td>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\">\n";
		echo "								<input class=\"bboxl\" type=\"text\" name=\"sdata\" size=\"20\" maxlength=\"10\">\n";
		echo "								<select name=\"type\">\n";
		echo "                           <option value=\"czip\">Client Zip</option>\n";
		echo "                           <option value=\"ozip\">Office Zip</option>\n";
		echo "                           <option value=\"ringto\">Ring to</option>\n";
		echo "								</select>\n";
		echo "					</td>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"right\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
		echo "					</td>\n";
		echo "         					</form>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function zip_search_movedtoIVRmaint()
{
	if (!empty($_POST['type']) && $_POST['type']=="off")
	{
		$qry = "SELECT id,czip,ozip as zip,ccity,ccounty,cstate,u,q,careacode FROM zip_to_zip WHERE ozip='".$_POST['sdata']."' order by czip ASC;";
	}
	else
	{
		$qry = "SELECT id,czip,ozip as zip,ccity,ccounty,cstate,u,q,careacode FROM zip_to_zip WHERE ".$_POST['type']."='".$_POST['sdata']."' order by czip ASC;";
	}

	$res	= mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	$res0 = mssql_query($qry);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	//$qry1 = "SELECT officeid,name,zip,ringto FROM offices WHERE zip='".$_POST['sdata']."';";
	$qry1 = "SELECT officeid,name,zip,ringto FROM offices WHERE zip='".$row0['zip']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	//echo $qry."<br>";

	if ($nrow > 0)
	{
		echo "<table width=\"50%\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"bottom\"><b>ZIP to ZIP Routing Matrix</b> Search Results</td>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"bottom\">\n";
		echo "						<font color=\"red\"><b>".$nrow."</font> Entries(s) Found</b>";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		
		if ($nrow1 > 0)
		{
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Office</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Office Zip</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Ring To</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "								<td class=\"gray\" align=\"left\">".$row1['name']."</td>\n";
			echo "								<td class=\"gray\" align=\"center\">".$row1['zip']."</td>\n";
			echo "								<td class=\"gray\" align=\"center\">".$row1['ringto']."</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
		}
		
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" valign=\"top\">\n";
		echo "						<table width=\"100%\" class=\"outer\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Office</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Office Zip</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Client Zip</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Area Code</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>City</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>County</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>State</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Qualified</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp</td>\n";
		echo "							</tr>\n";

		$zcnt=0;
		while ($row = mssql_fetch_array($res))
		{
			if (!empty($row['zip']))
			{
				$pozip=$row['zip'];
			}
			else
			{
				$pozip="";
			}
			
			if ($zcnt == 0 && !empty($pozip))
			{
				echo "							<tr>\n";
				echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "								<td class=\"wh_und\" align=\"left\">".$row1['name']."</td>\n";
				echo "								<td class=\"wh_und\" align=\"center\">".$row['zip']."</td>\n";
				echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" name=\"czip\"></td>\n";
				echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" name=\"careacode\"></td>\n";
				echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"15\" name=\"ccity\"></td>\n";
				echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"15\" name=\"ccounty\"></td>\n";
				echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"3\" name=\"cstate\" maxlength=\"2\"></td>\n";
				echo "								<td class=\"wh_und\" align=\"center\"><input class=\"checkboxwh\" type=\"checkbox\" value=\"1\" name=\"q\"></td>\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
				echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
				echo "								<input type=\"hidden\" name=\"subq\" value=\"add_zip\">\n";
				echo "								<input type=\"hidden\" name=\"ozip\" value=\"".$pozip."\">\n";
				echo "								<input type=\"hidden\" name=\"oringto\" value=\"".$row1['ringto']."\">\n";
				echo "								<input type=\"hidden\" name=\"officeid\" value=\"".$row1['officeid']."\">\n";
				echo "								<input type=\"hidden\" name=\"type\" value=\"".$_POST['type']."\">\n";
				echo "								<input type=\"hidden\" name=\"sdata\" value=\"".$_POST['sdata']."\">\n";
				echo "								<input type=\"hidden\" name=\"dsrc\" value=\"".$_POST['dsrc']."\">\n";
				echo "								<td class=\"wh_und\" align=\"right\">\n";
				echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add\">\n";				
				echo "								</td>\n";
				echo "         					</form>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "								<td class=\"gray\" colspan=\"9\" align=\"center\">&nbsp</td>\n";
				echo "							</tr>\n";
				$zcnt++;
			}			
			
			echo "							<tr>\n";
			echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "								<td class=\"wh_und\" align=\"left\">".$row1['name']."</td>\n";
			echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" value=\"".$pozip."\" name=\"ozip\"></td>\n";
			echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" value=\"".$row['czip']."\" name=\"czip\"></td>\n";
			echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" value=\"".$row['careacode']."\" name=\"careacode\"></td>\n";
			echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row['ccity']."\" name=\"ccity\"></td>\n";
			echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row['ccounty']."\" name=\"ccounty\"></td>\n";
			echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"3\" value=\"".$row['cstate']."\" name=\"cstate\" maxlength=\"2\"></td>\n";
			echo "								<td class=\"wh_und\" align=\"center\">\n";
				
			if (!empty($row['q']) && $row['q']==1)
			{
				echo "	<input class=\"checkboxwh\" type=\"checkbox\" value=\"1\" name=\"q\" CHECKED>\n";
			}
			else
			{
				echo "	<input class=\"checkboxwh\" type=\"checkbox\" value=\"1\" name=\"q\">\n";
			}
			
			echo "								</td>\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
			echo "								<input type=\"hidden\" name=\"subq\" value=\"upd_zip\">\n";
			echo "								<input type=\"hidden\" name=\"coid\" value=\"".$row['id']."\">\n";
			echo "								<input type=\"hidden\" name=\"oringto\" value=\"".$row1['ringto']."\">\n";
			echo "								<input type=\"hidden\" name=\"officeid\" value=\"".$row1['officeid']."\">\n";
			echo "								<input type=\"hidden\" name=\"type\" value=\"".$_POST['type']."\">\n";
			echo "								<input type=\"hidden\" name=\"sdata\" value=\"".$_POST['sdata']."\">\n";
			echo "								<input type=\"hidden\" name=\"dsrc\" value=\"".$_POST['dsrc']."\">\n";
			echo "								<td class=\"wh_und\" align=\"right\">\n";
			echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\">\n";				
			echo "								</td>\n";
			echo "         					</form>\n";
			echo "							</tr>\n";
		}

		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	else
	{
		echo "<table width=\"40%\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">No Entries(s) Found for <b>".$_POST['sdata']."</b></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function zip_searchxxx()
{
	if (!empty($_POST['type']) && $_POST['type']=="off")
	{
		$qry = "SELECT id,czip,ozip as zip FROM zip_to_zip WHERE ozip='".$_POST['sdata']."' order by czip ASC;";
	}
	else
	{
		$qry = "SELECT id,czip,ozip as zip FROM zip_to_zip WHERE ".$_POST['type']."='".$_POST['sdata']."' order by czip ASC;";
	}

	$res	= mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	$res0 = mssql_query($qry);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	//$qry1 = "SELECT officeid,name,zip,ringto FROM offices WHERE zip='".$_POST['sdata']."';";
	$qry1 = "SELECT officeid,name,zip,ringto FROM offices WHERE zip='".$row0['zip']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	//echo $qry."<br>";

	if ($nrow > 0)
	{
		echo "<table width=\"40%\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"bottom\"><b>ZIP to ZIP Routing Matrix</b> Search Results</td>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"bottom\">\n";
		echo "						<font color=\"red\"><b>".$nrow."</font> Entries(s) Found</b>";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		
		if ($nrow1 > 0)
		{
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Office</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Office Zip</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Ring To</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "								<td class=\"gray\" align=\"left\">".$row1['name']."</td>\n";
			echo "								<td class=\"gray\" align=\"center\">".$row1['zip']."</td>\n";
			echo "								<td class=\"gray\" align=\"center\">".$row1['ringto']."</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
		}
		
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" valign=\"top\">\n";
		echo "						<table class=\"outer\">\n";
		echo "							<tr>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Office Zip</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Client Zip</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp</td>\n";
		echo "							</tr>\n";

		$zcnt=0;
		while ($row = mssql_fetch_array($res))
		{
			if (!empty($row['zip']))
			{
				$pozip=$row['zip'];
			}
			else
			{
				$pozip="";
			}
			
			if ($zcnt == 0 && !empty($pozip))
			{
				echo "							<tr>\n";
				echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "								<td class=\"wh_und\" align=\"left\">".$row['zip']."</td>\n";
				echo "								<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" size=\"10\" name=\"czip\"></td>\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
				echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
				echo "								<input type=\"hidden\" name=\"subq\" value=\"add_zip\">\n";
				echo "								<input type=\"hidden\" name=\"ozip\" value=\"".$pozip."\">\n";
				echo "								<input type=\"hidden\" name=\"oringto\" value=\"".$row1['ringto']."\">\n";
				echo "								<input type=\"hidden\" name=\"officeid\" value=\"".$row1['officeid']."\">\n";
				echo "								<input type=\"hidden\" name=\"type\" value=\"".$_POST['type']."\">\n";
				echo "								<input type=\"hidden\" name=\"sdata\" value=\"".$_POST['sdata']."\">\n";
				echo "								<input type=\"hidden\" name=\"dsrc\" value=\"".$_POST['dsrc']."\">\n";
				echo "								<td class=\"wh_und\" align=\"right\">\n";
				echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add\">\n";				
				echo "								</td>\n";
				echo "         					</form>\n";
				echo "							</tr>\n";
				$zcnt++;
			}			
			
			echo "							<tr>\n";
			echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "								<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" size=\"10\" value=\"".$pozip."\" name=\"ozip\"></td>\n";
			echo "								<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" size=\"10\" value=\"".$row['czip']."\" name=\"czip\"></td>\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
			echo "								<input type=\"hidden\" name=\"subq\" value=\"upd_zip\">\n";
			echo "								<input type=\"hidden\" name=\"coid\" value=\"".$row['id']."\">\n";
			echo "								<input type=\"hidden\" name=\"oringto\" value=\"".$row1['ringto']."\">\n";
			echo "								<input type=\"hidden\" name=\"officeid\" value=\"".$row1['officeid']."\">\n";
			echo "								<input type=\"hidden\" name=\"type\" value=\"".$_POST['type']."\">\n";
			echo "								<input type=\"hidden\" name=\"sdata\" value=\"".$_POST['sdata']."\">\n";
			echo "								<input type=\"hidden\" name=\"dsrc\" value=\"".$_POST['dsrc']."\">\n";
			echo "								<td class=\"wh_und\" align=\"right\">\n";
			echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\">\n";				
			echo "								</td>\n";
			echo "         					</form>\n";
			echo "							</tr>\n";
		}

		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	else
	{
		echo "<table width=\"40%\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">No Entries(s) Found for <b>".$_POST['sdata']."</b></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function upfile1old()
{
	$uid		=md5(session_id().time()).".".$_SESSION['securityid'];

	$qry0 = "SELECT * FROM leadstatuscodes WHERE statusid > 1 and active = 2 order by name asc;";
	$res0 = mssql_query($qry0);
	//$row0 = mssql_fetch_array($res0);

	echo "<form enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"300000\" />\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"upfile2\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";

	echo "<table align=\"center\" class=\"outer\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"3\" class=\"gray\" align=\"center\"><b>Lead File Import</b></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"3\" class=\"gray\" align=\"left\">NOTE:<br>The file to be imported must be a Comma Delimited CSV file and contain the following column headers in the first data row:<br>Date, First Name, Last Name, Address 1, Address 2, City, State, Zip, Phone, Email, Project, Source, Notes<br></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\"><b>File:</b></td>\n";
	echo "					<td class=\"gray\" align=\"left\"><b>Source:</b></td>\n";
	echo "					<td class=\"gray\"></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"center\"><input type=\"file\" name=\"userfile\"></td>\n";
	echo "					<td class=\"gray\" align=\"center\">\n";
	echo "						<select name=\"source\">\n";

	while ($row0 = mssql_fetch_array($res0))
	{
		echo "<option value=\"".$row0['statusid']."\">".$row0['name']."</option>\n";
	}

	echo "						</select>\n";
	echo "					</td>\n";
	echo "					<td class=\"gray\" align=\"left\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Process\"></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function upfile2old()
{
	$uid		=$_POST['uid'];
	$source	=$_POST['source'];
	if (empty($uid))
	{
		die('Transition Error!');
	}

	//print_r($_FILES);
	//echo "<br>";

	if ($_FILES['userfile']['error'] == 0)
	{
		$uploaddir = 'D:\\PHP\\uploadtemp\\';
		$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
		{
			//echo "File is valid, and was successfully uploaded<br>";


			echo "<table width=\"85%\" class=\"outer\">\n";
			echo "	<tr>\n";
			echo "		<td>\n";
			echo "			<table width=\"100%\" align=\"center\">\n";

			$fl	=0;
			$fu	=0;
			$fo = fopen($uploadfile, "r");
			while (($data = fgetcsv($fo, 1000, ",")) !== FALSE)
			{
				$fl++;
				$num = count($data);
				//echo "<p> $num fields in line $fl: <br />\n";

				if ($fl==1)
				{
					if ($data[0]!="Date" || $data[1]!="First Name" || $data[2]!="Last Name")
					{
						echo "				<tr>\n";
						echo "					<td class=\"gray\" NOWRAP></td>";
						echo "					<td class=\"gray\" colspan=\"".$num."\"><b>\n";
						echo "Improper Format";
						echo "					</b></td>\n";
						echo "				</tr>\n";
						exit;
					}

					echo "				<tr>\n";
					echo "					<td class=\"gray\" NOWRAP></td>";
					echo "					<td class=\"gray\" colspan=\"".$num."\"><b>\n";
					echo "Lead Import Results";
					echo "					</b></td>\n";
					echo "				</tr>\n";
					echo "				<tr>\n";
					echo "					<td class=\"ltgray_und\" NOWRAP></td>";

					for ($c=0; $c < $num; $c++)
					{
						echo "		<td class=\"ltgray_und\" NOWRAP>";
						echo "			<b>";
						echo $data[$c];
						echo "			</b>";
						echo "		</td>\n";

					}
					echo "				</tr>\n";
				}

				if ($fl > 1)
				{
					if (count($data) == $num && valid_date($data[0]))
					{
						$fu++;

						$fphone	=preg_replace("/[\s()-]/","",$data[8]);

						$qry0  = "INSERT INTO lead_inc ";
						$qry0 .= "(submitted,"; //0
						$qry0 .= "lname,"; //2
						$qry0 .= "addr,"; //3
						$qry0 .= "city,"; //5
						$qry0 .= "state,"; //6
						$qry0 .= "zip,"; //7
						$qry0 .= "phone,"; //8
						$qry0 .= "bphone,";
						$qry0 .= "email,"; //9
						$qry0 .= "source,";
						$qry0 .= "comments) "; //12 (10)
						$qry0 .= "VALUES (";
						$qry0 .= "'".$data[0]."',"; //0
						$qry0 .= "'".replacequote($data[1])." ".replacequote($data[2])."',"; //1,2
						$qry0 .= "'".replacequote($data[3])."',"; //3
						$qry0 .= "'".replacequote($data[5])."',"; //5
						$qry0 .= "'".replacequote($data[6])."',"; //6
						$qry0 .= "'".replacequote($data[7])."',"; //7
						$qry0 .= "'".replacequote($fphone)."',"; //8
						$qry0 .= "'hm',"; //8
						$qry0 .= "'".replacequote($data[9])."',"; //9
						$qry0 .= "'".$source."',"; //source
						$qry0 .= "'".replacequote($data[10])."|".replacequote($data[12])."');"; //12 (10)
						$res0	= mssql_query($qry0);

						echo "				<tr>\n";
						echo "					<td class=\"gray\" NOWRAP></td>";
						echo "					<td class=\"gray\" colspan=\"".$num."\">\n";
						echo $qry0."<br>";
						echo "					</td>\n";
						echo "				</tr>\n";

						echo "				<tr>\n";
						echo "		<td  class=\"wh_und\" align=\"right\" NOWRAP>";
						echo $fu.".";
						echo "		</td>\n";

						for ($t=0; $t < $num; $t++)
						{
							echo "		<td  class=\"wh_und\" NOWRAP>";
							echo $data[$t];
							echo "		</td>\n";

						}
						//echo "					<td class=\"wh_und\" >".$data[0]."</td>\n"; //
						echo "				</tr>\n";
					}
				}

			}
			fclose($fo);

			echo "				<tr>\n";
			echo "					<td class=\"gray\" NOWRAP></td>";
			echo "					<td class=\"gray\" colspan=\"".$num."\"><b>\n";
			echo $fu." Leads Imported";
			echo "					</b></td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "</table>\n";

		}
		else
		{
			echo "Possible file upload attack!\n";
		}
	}
	else
	{
		if ($_FILES['userfile']['error'] == 1)
		{
			die('File Upload Error: File too Large (ini Directive)');
		}
		elseif ($_FILES['userfile']['error'] == 2)
		{
			die('File Upload Error: File too Large (Form Directive)');
		}
		elseif ($_FILES['userfile']['error'] == 3)
		{
			die('File Upload Error: Partial Upload');
		}
		elseif ($_FILES['userfile']['error'] == 4)
		{
			die('File Upload Error: No File');
		}
		elseif ($_FILES['userfile']['error'] == 6)
		{
			die('File Upload Error: TMP Dir Missing');
		}
		elseif ($_FILES['userfile']['error'] == 7)
		{
			die('File Upload Error: Failed Disk Write');
		}
	}

	//echo $_FILES['userfile']['impfile'];


	/*
	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"upfile2\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";

	echo "<table align=\"center\" class=\"outer\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" align=\"right\"><b>File to Upload:</b></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"center\"><input type=\"file\" name=\"impfile\"></td>\n";
	echo "					<td class=\"ltgray_und\" align=\"left\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Preview\"></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	*/
}

function upfile1()
{
	$uid  = md5(session_id().time()).".".$_SESSION['securityid'];
	$_SESSION['puid'] = $uid;
	$qry0 = "SELECT * FROM leadstatuscodes WHERE statusid > 1 and active = 2 order by name asc;";
	$res0 = mssql_query($qry0);
	$col_ar	= array(
						'Date',
						'<b>FirstName</b>',
						'<b>LastName</b>',
						'Address1',
						'Address2',
						'City',
						'State',
						'<b>Zip</b>',
						'<b>Phone</b>',
						'Email',
						'Comments'
					);
	
	//$row0 = mssql_fetch_array($res0);

	echo "<form enctype=\"multipart/form-data\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"3000000\" />\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"upfile2\">\n";
	//echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";

	echo "<br>\n";
	echo "<table align=\"center\" width=\"500px\" class=\"outer\">\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" class=\"ltgray_und\" align=\"center\"><b>Lead File Import:</b> Upload</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"75%\" valign=\"top\" class=\"gray\">\n";
	echo "			<table align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\"><b>NOTE:</b><br>The import file must be a <b>Comma Delimited</b> csv file.</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "		<td width=\"25%\" valign=\"top\" class=\"gray\">\n";
	echo "			<table align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\"><b>Source:</b></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"right\">\n";
	echo "						<select name=\"source\">\n";

	while ($row0 = mssql_fetch_array($res0))
	{
		echo "						<option value=\"".$row0['statusid']."\">".$row0['name']."</option>\n";
	}

	echo "						</select>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\"><b>File:</b></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"right\"><input type=\"file\" name=\"userfile\"></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"right\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Upload\"></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function upfile2()
{
	error_reporting(E_ALL);
	
	if (!isset($_SESSION['puid']))
	{
		die('Process Transition Error!');
	}

	if ($_FILES['userfile']['error'] == 1)
	{
		die('File Upload Error: File too Large (ini Directive)');
	}
	elseif ($_FILES['userfile']['error'] == 2)
	{
		die('File Upload Error: File too Large (Form Directive)');
	}
	elseif ($_FILES['userfile']['error'] == 3)
	{
		die('File Upload Error: Partial Upload');
	}
	elseif ($_FILES['userfile']['error'] == 4)
	{
		die('File Upload Error: No File');
	}
	elseif ($_FILES['userfile']['error'] == 6)
	{
		die('File Upload Error: TMP Dir Missing');
	}
	elseif ($_FILES['userfile']['error'] == 7)
	{
		die('File Upload Error: Failed Disk Write');
	}
	
	$err	=0;
	$dis	="";
	//$uid	=$_POST['uid'];
	$source	=$_POST['source'];
	$col_ar	= array(
							'Date',
							'FirstName',
							'LastName',
							'Address1',
							'Address2',
							'City',
							'State',
							'Zip',
							'Phone',
							'Email',
							'Comments'
						);
	
	$qry0 = "SELECT * FROM leadstatuscodes WHERE statusid > 1 and active = 2 order by name asc;";
	$res0 = mssql_query($qry0);
	
	/*show_array_vars($_FILES);
	echo "<br>";
	echo $_SERVER['CONTENT_LENGTH']."<br>";
	echo "---------<br>";*/
	//exit;
	if ($_FILES['userfile']['error'] == 0)
	{
		$uploaddir = 'D:\\uploadtemp\\';
		$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
		{
			$fl	=0;
			$fu	=0;
			$fo = fopen($uploadfile, "r");
			while (($data = fgetcsv($fo, 1000, ",")) !== FALSE)
			{
				$fl++;
				if ($fl==1)
				{
					$impheaders=$data;
				}
			}
			fclose($fo);
			
			echo "<form enctype=\"multipart/form-data\" method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
			echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"3000000\" />\n";
			echo "<input type=\"hidden\" name=\"subq\" value=\"upfile3\">\n";
			echo "<input type=\"hidden\" name=\"source\" value=\"".$_POST['source']."\">\n";
			echo "<input type=\"hidden\" name=\"impfile\" value=\"".basename($_FILES['userfile']['name'])."\">\n";
			echo "<br>\n";
			echo "<table align=\"center\" width=\"500px\" class=\"outer\">\n";
			echo "	<tr>\n";
			echo "		<td colspan=\"2\" class=\"ltgray_und\" align=\"center\"><b>Lead File Import</b>: Verify</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td width=\"75%\" valign=\"top\" class=\"gray\">\n";
			echo "			<table align=\"center\" width=\"100%\" border=0>\n";
			echo "				<tr>\n";
			echo "					<td colspan=\"4\" class=\"gray\" align=\"left\"><b>NOTE:</b><br>The file to be imported must be a <b>Comma Delimited CSV</b> file and match Database Fields to Import File Fields</td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"center\"><b>JMS Fields</b></td>\n";
			echo "					<td class=\"gray\" align=\"left\"></td>\n";
			echo "					<td class=\"gray\" align=\"center\" title=\"Data Map\"><b>Import File Columns</b></td>\n";
			/*echo "					<td class=\"gray\" align=\"left\"><b>Include</b></td>\n";*/
			echo "				</tr>\n";
			
			foreach ($col_ar as $n=>$v)
			{
				echo "							<input type=\"hidden\" name=\"".$v."[]\" value=\"".$n."\">\n";
				echo "				<tr>\n";
				
				if ($n==1 || $n==2 || $n==7)
				{
					echo "					<td class=\"gray\" align=\"right\" title=\"Mandatory Field\"><b>".$v."</b></td>\n";
				}
				else
				{
					echo "					<td class=\"gray\" align=\"right\">".$v."</td>\n";
				}
				
				echo "					<td class=\"gray\" align=\"right\"> <= </td>\n";
				echo "					<td class=\"gray\" align=\"center\">\n";
				echo "						<select name=\"".$v."[]\">\n";
				echo "							<option value=\"NA\"></option>\n";
	
				foreach($impheaders as $nd=>$vd)
				{
					echo "							<option value=\"".$nd."\">".$vd."</option>\n";
				}
				
				echo "						</select>\n";
				echo "					</td>\n";
				/*echo "					<td class=\"gray\" align=\"left\">\n";
				echo "						<input type=\"checkbox\" class=\"checkboxgry\" name=\"".$v."[]\" value=\"1\">\n";
				echo "					</td>\n";*/
				echo "				</tr>\n";
			}
			
			echo "			</table>\n";
			echo "		</td>\n";
			echo "		<td width=\"25%\" valign=\"top\" class=\"gray\">\n";
			echo "			<table align=\"center\" width=\"100%\" border=0>\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"left\">&nbsp</td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"left\"><b>Source:</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"right\">\n";
			echo "						<select name=\"source\">\n";
		
			while ($row0 = mssql_fetch_array($res0))
			{
				if (isset($source) && $source==$row0['statusid'])
				{
					echo "<option value=\"".$row0['statusid']."\" SELECTED>".$row0['name']."</option>\n";
				}
			}
		
			echo "						</select>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"left\"><b>File:</b></td>\n";
			echo "				</tr>\n";
			
			if (isset($uploadfile) && preg_match("/.csv/",$uploadfile))
			{
				echo "				<tr>\n";
				echo "					<td class=\"gray\" align=\"center\">Uploaded<br>Contains <b>".$fl."</b> Lines</td>\n";
				echo "				</tr>\n";	
			}
			else
			{
				echo "				<tr>\n";
				echo "					<td class=\"gray\" align=\"center\"><font color=\"red\">Format Invalid or not CSV file!</font></td>\n";
				echo "				</tr>\n";
				$err++;
			}
			
			if ($err > 0)
			{
				$dis="DISABLED";
			}

			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"right\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Verify\" ".$dis."></td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			echo "</form>\n";
		}
		else
		{
			echo "Possible file upload attack!\n";
		}
	}
}

function upfile3()
{
	error_reporting(E_ALL);
	$sh_err=0;
	$en_temp_content=1;
	$sess_write	=0;
	
	if (!isset($_SESSION['puid']))
	{
		die('Process Transition Error!');
	}
	
	if (empty($_POST['Date']))
	{
		die('Date Header not set!');
	}
	
	if (empty($_POST['FirstName']))
	{
		die('FirstName Header not set!');
	}
	
	if (empty($_POST['LastName']))
	{
		die('LastName Header not set!');
	}
	
	if (empty($_POST['Address1']))
	{
		die('Address1 Header not set!');
	}
	
	if (empty($_POST['Address2']))
	{
		die('Address2 Header not set!');
	}
	
	if (empty($_POST['City']))
	{
		die('City Header not set!');
	}
	
	if (empty($_POST['State']))
	{
		die('State Header not set!');
	}
	
	if (empty($_POST['Zip']))
	{
		die('Zip Header not set!');
	}
	
	if (empty($_POST['Phone']))
	{
		die('Phone Header not set!');
	}
	
	if (empty($_POST['Email']))
	{
		die('Email Header not set!');
	}
	
	if (empty($_POST['Comments']))
	{
		die('Comments Header not set!');
	}
	
	if (empty($_POST['source']))
	{
		die('Source Code not set!');
	}

	$col_ar	= array(
							'Date',
							'FirstName',
							'LastName',
							'Address1',
							'Address2',
							'City',
							'State',
							'Zip',
							'Phone',
							'Email',
							'Comments'
						);
	
	if ($sh_err==1)
	{
		show_array_vars($_POST);
		echo "<br>";
	}
	
	$uploaddir = 'D:\\uploadtemp\\';
	
	if (!isset($_POST['impfile']) && !file_exists($uploaddir.$_POST['impfile']))
	{
		echo "<font color=\"\">Error</font>: File not retained.";
		exit;
	}
	else
	{
		$qry0 = "SELECT statusid,name FROM leadstatuscodes WHERE statusid = '".$_POST['source']."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		$src  = array($row0['statusid'],$row0['name']);
		
		$uploaddir = 'D:\\uploadtemp\\';
		$uploadfile = $uploaddir . basename($_POST['impfile']);

		//if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
		
		//echo $uploaddir.basename($uploadfile).".tmp";
		//echo "<br>";
		//echo $uploadfile;
		//echo "<br>";
		//echo file_exists($uploadfile);
		//exit;
		
		//if (move_uploaded_file($uploaddir.basename($uploadfile).".tmp", $uploadfile))
		if (file_exists($uploadfile))
		{
			//echo "File is valid, and was successfully uploaded<br>";
			$ln1fldcnt	= 0;
			$fldcnt		= 0;
			$daterr		= 0;
			$fl			= 0;
			$fu			= 0;
			$fldcnterr	= array();
			$dataerr	= array();
			$bypasslns	= array();
			$cond_data	= array();
			$fo 		= fopen($uploadfile, "r");
			while (($data 	= fgetcsv($fo, 1000, ",")) !== FALSE)
			{
				$fl++;
				$fldcnt = count($data);
			
				if ($fl==1)
				{
					$ln1fldcnt=count($data);
				}
				
				if ($fldcnt!=$ln1fldcnt) // Detects per line Data Field Count Errors
				{
					$fldcnterr[$fl]=$fldcnt;
				}
				
				if ($fl!=1 && !array_key_exists($fl,$fldcnterr))	// Prevents Execution of Data Sanitize steps of Lines > 1
				{													// if Line has Data Field Count Error
					
					//$cond_data[$fl]['SourceID']=array('SourceID',$src[0]);
					$cond_data[$fl]['SourceID']=$src[0];
					
					if (!empty($data[$_POST['Date'][1]]) && is_numeric($_POST['Date'][1]) && valid_date($data[$_POST['Date'][1]])) // Tests & Cleans Date Data
					{
						if ($sh_err==1)
						{
							echo "Date: (".$fl.") ".$data[$_POST['Date'][1]]."<br>";
						}
						$cond_data[$fl]['Date']=$data[$_POST['Date'][1]];
					}
					else
					{
						if ($sh_err==1)
						{
							echo "Date: (".$fl.") ".date("m/d/y",time())."<br>";
						}
						$cond_data[$fl]['Date']=date("m/d/y",time());
					}
					
					if (!empty($data[$_POST['FirstName'][1]]) && is_numeric($_POST['FirstName'][1]))	// Tests FirstName Data
					{
						if (strlen($data[$_POST['FirstName'][1]]) >= 1)
						{
							if ($sh_err==1)
							{
								echo "FirstName: (".$fl.") ".ucfirst(filter_var($data[$_POST['FirstName'][1]]))."<br>";
							}
							$cond_data[$fl]['FirstName']=ucfirst(filter_var($data[$_POST['FirstName'][1]]));
						}
						else
						{
							if ($sh_err==1)
							{
								echo "Line: ".$fl." Data Map Error (FirstName). Line Excluded<br>";
							}
							$dataerr[$fl][]=array('FirstName','VL');
						}
					}
					else
					{
						if ($sh_err==1)
						{
							echo "Line: ".$fl." Data Map Error (FirstName). Line Excluded<br>";
						}
						$dataerr[$fl][]=array('FirstName','VE');
					}
				
					if (is_numeric($_POST['LastName'][1]) && !empty($data[$_POST['LastName'][1]])) // Tests LastName Data
					{
						if (!empty($data[$_POST['LastName'][1]]) && !is_numeric($data[$_POST['LastName'][1]]))
						{
							if ($sh_err==1)
							{
								echo "LastName: (".$fl.") ".ucfirst(filter_var($data[$_POST['LastName'][1]]))."<br>";
							}
							$cond_data[$fl]['LastName']=ucfirst(filter_var($data[$_POST['LastName'][1]]));
						}
						else
						{
							if ($sh_err==1)
							{
								echo "Line: ".$fl." Data Error (LastName). Line Excluded<br>";
							}
							$dataerr[$fl][]=array('LastName','VT');
						}
					}
					else
					{
						if ($sh_err==1)
						{
							echo "Line: ".$fl." Data Map Error (LastName). Line Excluded<br>";
						}
						$dataerr[$fl][]=array('LastName','VE');
					}
				
					if (is_numeric($_POST['Address1'][1]) && !empty($data[$_POST['Address1'][1]])) // Tests Address1 Data
					{
						if (!is_numeric($data[$_POST['Address1'][1]]))
						{
							if ($sh_err==1)
							{
								echo "Address1: (".$fl.") ".$data[$_POST['Address1'][1]]."<br>";
							}
							$cond_data[$fl]['Address1']=filter_var($data[$_POST['Address1'][1]]);
						}
					}
				
					if (is_numeric($_POST['Address2'][1]) && !empty($data[$_POST['Address2'][1]]) && strlen($data[$_POST['Address2'][1]]) > 2) // Tests Address2 Data
					{
						if ($sh_err==1)
						{
							echo "Address2: (".$fl.") ".$data[$_POST['Address2'][1]]."<br>";
						}
						$cond_data[$fl]['Address2']=filter_var($data[$_POST['Address2'][1]]);
					}
				
					if (is_numeric($_POST['City'][1]) && !empty($data[$_POST['City'][1]]) && strlen($data[$_POST['City'][1]]) > 2) // Tests City Data
					{
						if ($sh_err==1)
						{
							echo "City: (".$fl.") ".$data[$_POST['City'][1]]."<br>";
						}
						$cond_data[$fl]['City']=filter_var($data[$_POST['City'][1]]);
					}

					if (is_numeric($_POST['State'][1]) && !empty($data[$_POST['State'][1]]) && strlen($data[$_POST['State'][1]]) >= 2) // Tests State Data
					{
						if ($sh_err==1)
						{
							echo "State: (".$fl.") ".$data[$_POST['State'][1]]."<br>";
						}
						$cond_data[$fl]['State']=filter_var($data[$_POST['State'][1]]);
					}

					if (is_numeric($_POST['Zip'][1]) && !empty($data[$_POST['Zip'][1]])) // Tests & Cleans Zip Code Data
					{
						if (is_numeric($data[$_POST['Zip'][1]]) && strlen($data[$_POST['Zip'][1]])==5)
						{
							if ($sh_err==1)
							{
								echo "Zip: (".$fl.") ".filter_var($data[$_POST['Zip'][1]])."<br>";
							}
							$cond_data[$fl]['Zip']=filter_var($data[$_POST['Zip'][1]]);
						}
						elseif (is_numeric($data[$_POST['Zip'][1]]) && strlen($data[$_POST['Zip'][1]]) < 5)
						{
							if ($sh_err==1)
							{
								echo "Zip Padded: (".$fl.") ".str_pad($data[$_POST['Zip'][1]], 5, "0", STR_PAD_LEFT)."<br>";
							}
							$cond_data[$fl]['Zip']=str_pad($data[$_POST['Zip'][1]], 5, "0", STR_PAD_LEFT);
						}
						elseif (strlen($data[$_POST['Zip'][1]]) > 5 && preg_match('/-/',$data[$_POST['Zip'][1]]))
						{
							$fzip=split("-",$data[$_POST['Zip'][1]]);
							//echo $fl.":";
							//print_r($fzip);
							//echo "<br>";
							//echo ":".."<br>";
							if ($sh_err==1)
							{
								echo "Zip Corrected: (".$fl.") ".str_pad(filter_var($fzip[0]), 5, "0", STR_PAD_LEFT)."<br>";
							}
							$cond_data[$fl]['Zip']=str_pad(filter_var($fzip[0]), 5, "0", STR_PAD_LEFT);
						}
						else //if (!is_numeric($data[$_POST['Zip'][1]]) && strlen($data[$_POST['Zip'][1]]) >= 1)
						{
							if ($sh_err==1)
							{
								echo "Zip Padded: (".$fl.") ".str_pad(0, 5, "0", STR_PAD_LEFT)."<br>";
							}
							$cond_data[$fl]['Zip']=str_pad(0, 5, "0", STR_PAD_LEFT);
						}
						/*else
						{
							//die('<b>Error! Zip Code</b> Data Error Line '.$fl.'. Cannot continue.');
							if ($sh_err==1)
							{
								echo "Line: ".$fl." Data Error (Zip Code). Line Excluded<br>";
							}
							$dataerr[$fl][]=array('Zip','VL');
						}*/
					}
					else
					{
						//die('<b>Error!</b> Missing <b>Zip Code</b> Data Map or Data Line '.$fl.'. Cannot continue.');
						if ($sh_err==1)
						{
							echo "Zip Padded: (".$fl.") ".str_pad($data[$_POST['Zip'][1]], 5, "0", STR_PAD_LEFT)."<br>";
						}
						$cond_data[$fl]['Zip']=str_pad($data[$_POST['Zip'][1]], 5, "0", STR_PAD_LEFT);
					}
				
					if (is_numeric($_POST['Phone'][1]) && !empty($data[$_POST['Phone'][1]]) && strlen($data[$_POST['Phone'][1]]) >= 1) // Tests Phone Data
					{
						if (strlen($data[$_POST['Phone'][1]]) <= 10)
						{
							if ($sh_err==1)
							{
								echo "Phone: (".$fl.") ".str_pad(filter_var($data[$_POST['Phone'][1]]), 10, "0", STR_PAD_LEFT)."<br>";
							}
							$cond_data[$fl]['Phone']=str_pad(filter_var($data[$_POST['Phone'][1]]), 10, "0", STR_PAD_LEFT);
						}
						elseif (strlen($data[$_POST['Phone'][1]]) > 10)
						{
							if ($sh_err==1)
							{
								echo "Phone: (".$fl.") ".preg_replace('/[-() ]+/','',filter_var($data[$_POST['Phone'][1]]))."<br>";
							}
							$cond_data[$fl]['Phone']=preg_replace('/[-() ]+/','',filter_var($data[$_POST['Phone'][1]]));
						}
						else
						{
							if ($sh_err==1)
							{
								echo "Line: ".$fl." Data Length Error (Zip Code). Line Excluded<br>";
							}
							$dataerr[$fl][]=array('Phone','VL');
						}
					}
					else
					{
						if ($sh_err==1)
						{
							echo "Phone: (".$fl.") ".str_pad(filter_var($data[$_POST['Phone'][1]]), 10, "0", STR_PAD_LEFT)."<br>";
						}
						$cond_data[$fl]['Phone']=str_pad(filter_var($data[$_POST['Phone'][1]]), 10, "0", STR_PAD_LEFT);
					}
				
					if	(
							is_numeric($_POST['Comments'][1])
							&& !empty($data[$_POST['Email'][1]])
							&& preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i',$data[$_POST['Email'][1]])
						) // Tests Email Data
					{
						if ($sh_err==1)
						{
							echo "Email: (".$fl.") ".filter_var($data[$_POST['Email'][1]])."<br>";
						}
						$cond_data[$fl]['Email']=$data[$_POST['Email'][1]];
					}
				
					if (is_numeric($_POST['Comments'][1]) && !empty($data[$_POST['Comments'][1]])) // Tests & Cleans Comments Data
					{
						if ($sh_err==1)
						{
							echo "Comments: (".$fl.") ".filter_var($data[$_POST['Comments'][1]])."<br>";
						}
						$cond_data[$fl]['Comments']=filter_var($data[$_POST['Comments'][1]]);
					}
				}
				
				if ($sh_err==1)
				{
					echo "-------------------<br>";
				}
			}
			fclose($fo);
			
			echo "<div id=\"masterdiv\">\n";
			echo "<table width=\"35%\" class=\"outer\">\n";
			echo "	<tr>\n";
			echo "		<td colspan=\"2\" class=\"ltgray_und\" align=\"center\"><b>Lead File Import</b>: Process</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td class=\"gray\">\n";
			echo "			<table width=\"100%\" align=\"center\">\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"left\">\n";
			echo "						<table width=\"100%\" align=\"left\" border=0>\n";
			echo "							<tr>\n";
			echo "								<td class=\"gray\" align=\"left\"><font color=\"blue\"><b>Imports</b></font></td>\n";
			echo "							<tr>\n";
			echo "							<tr>\n";
			echo "								<td class=\"gray\" align=\"left\"><b>File</b>: ".basename($uploadfile)."</td>\n";
			echo "							<tr>\n";
			echo "							<tr>\n";
			echo "								<td class=\"gray\" align=\"left\"><b>Source</b>: ".$src[1]."</td>\n";
			echo "							<tr>\n";
			
			if (is_array($cond_data) && count($cond_data) > 0)
			{
				echo "							<tr>\n";
				echo "								<td class=\"gray\"><div onclick=\"SwitchMenu('subImports')\">&nbsp<img src=\".\plus.gif\" alt=\"Expand\"> <b>Lines:</b> ".count($cond_data)."</div></td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "								<td class=\"gray\">\n";
				
				echo "<span class=\"submenu\" id=\"subImports\">\n";
				echo "									<table>\n";
				echo "										<tr>\n";
				echo "											<td class=\"ltgray_und\"></td>\n";
				echo "											<td class=\"ltgray_und\"><b>Line</b></td>\n";
				echo "											<td class=\"ltgray_und\"><b>Data</b></td>\n";
				echo "										</tr>\n";
			
				$ix=1;
				foreach ($cond_data as $in=>$iv)
				{
					echo "										<tr>\n";
					echo "											<td class=\"wh_und\" valign=\"top\">".$ix++."</td>\n";
					echo "											<td class=\"wh_und\" valign=\"top\">".$in."</td>\n";
					echo "											<td class=\"wh_und\">\n";
					echo "												<table>\n";
					
					foreach ($iv as $iin => $iiv)
					{
						echo "													<tr>\n";
						echo "														<td valign=\"top\">".$iin.":</td><td valign=\"top\">".$iiv."</td>";
						echo "													</tr>\n";
					}
					
					echo "												</table>\n";
					echo "											</td>\n";
					echo "										</tr>\n";
				}
				
				echo "									</table>\n";
				echo "</span>";
				
				echo "								</td>\n";
				echo "							</tr>\n";
			}
			
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td>\n";
			echo "			<table width=\"100%\" align=\"center\">\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"left\">\n";
			echo "						<table width=\"100%\" align=\"left\" border=0>\n";
			echo "							<tr>\n";
			echo "								<td class=\"gray\" align=\"left\"><font color=\"red\"><b>Errors:</b></font> ".(count($fldcnterr)+count($dataerr))."</td>\n";
			echo "							<tr>\n";
			echo "							<tr>\n";
			echo "								<td class=\"gray\" align=\"left\">Correct and Resubmit or Click Process to Exclude Lines with Errors</td>\n";
			echo "							<tr>\n";
			
			if (is_array($fldcnterr) && count($fldcnterr) > 0)
			{
				echo "							<tr>\n";
				echo "								<td class=\"gray\"><div onclick=\"SwitchMenu('subFields')\">&nbsp<img src=\".\plus.gif\" alt=\"Expand\"> <b>Field Errors:</b> ".count($fldcnterr)."</div></td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "								<td class=\"gray\">\n";
				
				echo "<span class=\"submenu\" id=\"subFields\">\n";
				echo "									<table width=\"100%\">\n";
				echo "										<tr>\n";
				echo "											<td class=\"ltgray_und\"></td>\n";
				echo "											<td class=\"ltgray_und\"><b>Line</b></td>\n";
				echo "											<td class=\"ltgray_und\"><b>Field Count</b></td>\n";
				echo "										</tr>\n";
			
				$fx=1;
				foreach ($fldcnterr as $fn=>$fv)
				{
					echo "										<tr>\n";
					echo "											<td class=\"wh_und\">".$fx++."</td>\n";
					echo "											<td class=\"wh_und\">".$fn."</td>\n";
					echo "											<td class=\"wh_und\">".$fv."</td>\n";
					echo "										</tr>\n";
				}
				
				echo "									</table>\n";
				echo "</span>";
				
				echo "								</td>\n";
				echo "							</tr>\n";
			}
			/*else
			{
				echo "							<tr><td class=\"gray\">No Field Count Errors detected</td></tr>\n";
			}*/
			
			if (is_array($dataerr) && count($dataerr) > 0)
			{
				echo "							<tr>\n";
				echo "								<td class=\"gray\"><div onclick=\"SwitchMenu('subData')\">&nbsp<img src=\".\plus.gif\" alt=\"Expand\"> <b>Data Errors:</b> ".count($dataerr)."</div></td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "								<td class=\"gray\">\n";
				
				echo "<span class=\"submenu\" id=\"subData\">\n";
				echo "									<table width=\"100%\">\n";
				echo "										<tr>\n";
				echo "											<td class=\"ltgray_und\"></td>\n";
				echo "											<td class=\"ltgray_und\"><b>Line</b></td>\n";
				echo "											<td class=\"ltgray_und\"><b>Field</b></td>\n";
				echo "											<td class=\"ltgray_und\"><b>Error Type</b></td>\n";
				echo "										</tr>\n";
			
				$dx=1;
				foreach ($dataerr as $dn=>$dv)
				{
					echo "							<tr>\n";
					echo "								<td class=\"wh_und\">".$dx++."</td>\n";
					echo "								<td class=\"wh_und\">".$dn."</td>\n";
					echo "								<td class=\"wh_und\">".$dv[0][0]."</td>\n";
					echo "								<td class=\"wh_und\">".$dv[0][1]."</td>\n";
					echo "							</tr>\n";
				}
				
				echo "									</table>\n";
				echo "</span>";
				
				echo "								</td>\n";
				echo "							</tr>\n";
			}
			/*else
			{
				echo "							<tr><td class=\"gray\">No Data Errors were Detected</td></tr>\n";
			}*/
			
			echo "						</table>\n";
			
			$_SESSION['imp_stage']=array();
			if (is_array($cond_data) && count($cond_data) > 0)
			{
				$tx=1;
				foreach ($cond_data as $tn=>$tv)
				{
					$qry0  = "INSERT INTO lead_inc ";
					$qry0 .= "( ";
					$qry0 .= " submitted"; //0
					$qry0 .= ",lname"; //2
					$qry0 .= ",zip"; //7
					$qry0 .= ",phone"; //8
					$qry0 .= ",bphone";
					$qry0 .= ",source";
					
					if (!empty($tv['Address1']))
					{
						$qry0 .= ",addr"; //3
					}
					
					if (!empty($tv['City']))
					{
						$qry0 .= ",city"; //5
					}
					
					if (!empty($tv['State']))
					{
						$qry0 .= ",state"; //6
					}
					
					if (!empty($tv['Email']))
					{
						$qry0 .= ",email"; //9
					}
					
					if (!empty($tv['Comments']))
					{
						$qry0 .= ",comments "; //12 (10)
					}
					
					$qry0 .= " ) VALUES ( ";
					$qry0 .= " '".date("m/d/Y",strtotime($tv['Date']))."'"; //0
					$qry0 .= ",'".replacequote($tv['FirstName'])." ".replacequote($tv['LastName'])."'"; //1,2
					$qry0 .= ",'".replacequote($tv['Zip'])."'"; //7
					$qry0 .= ",'".replacequote($tv['Phone'])."'"; //8
					$qry0 .= ",'hm'";
					$qry0 .= ",'".$tv['SourceID']."'"; //source
					
					if (!empty($tv['Address1']))
					{
						$qry0 .= ",'".replacequote($tv['Address1'])."'"; //3
					}
					
					if (!empty($tv['City']))
					{
						$qry0 .= ",'".replacequote($tv['City'])."'"; //5
					}
					
					if (!empty($tv['State']))
					{
						$qry0 .= ",'".replacequote($tv['State'])."'"; //6
					}
					
					if (!empty($tv['Email']))
					{
						$qry0 .= ",'".replacequote($tv['Email'])."'"; //9
					}
					
					if (!empty($tv['Comments']))
					{
						$qry0 .= ",'".replacequote($tv['Comments'])."' ";
					}
					
					$qry0 .= ");"; //12 (10)
					
					$_SESSION['imp_stage'][$tn]=$qry0;
				}
			}
			
			echo "<form method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
			echo "<input type=\"hidden\" name=\"subq\" value=\"upfile4\">\n";
			echo "				<tr>\n";
			
			if (isset($_SESSION['imp_stage']) && is_array($_SESSION['imp_stage']) && count($_SESSION['imp_stage']) > 0)
			{
				echo "					<td class=\"gray\" align=\"right\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Process\"></td>\n";
			}
			else
			{
				echo "					<td class=\"gray\" align=\"right\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Process\" DISABLED></td>\n";
			}
			echo "				</tr>\n";
			echo "</form>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			echo "</div>\n";
			
			//show_array_vars($cond_data);
			
			// Temp Content Area
			if ($en_temp_content == 0)
			{
				if (is_array($cond_data) && count($cond_data) > 0)
				{
					$tx=1;
					foreach ($cond_data as $tn=>$tv)
					{
						//$qry0 = $tn.": ";
							$qry0  = "INSERT INTO lead_inc ";
							$qry0 .= "( ";
							$qry0 .= " submitted"; //0
							$qry0 .= ",lname"; //2
							$qry0 .= ",zip"; //7
							$qry0 .= ",phone"; //8
							$qry0 .= ",bphone";
							$qry0 .= ",source";
							
							if (!empty($tv['Address1']))
							{
								$qry0 .= ",addr"; //3
							}
							
							if (!empty($tv['City']))
							{
								$qry0 .= ",city"; //5
							}
							
							if (!empty($tv['State']))
							{
								$qry0 .= ",state"; //6
							}
							
							if (!empty($tv['Email']))
							{
								$qry0 .= ",email"; //9
							}
							
							if (!empty($tv['Comments']))
							{
								$qry0 .= ",comments "; //12 (10)
							}
							
							$qry0 .= " ) VALUES ( ";
							$qry0 .= " '".date("m/d/Y",strtotime($tv['Date']))."'"; //0
							$qry0 .= ",'".replacequote($tv['FirstName'])." ".replacequote($tv['LastName'])."'"; //1,2
							$qry0 .= ",'".replacequote($tv['Zip'])."'"; //7
							$qry0 .= ",'".replacequote($tv['Phone'])."'"; //8
							$qry0 .= ",'hm'";
							$qry0 .= ",'".$tv['SourceID']."'"; //source
							
							if (!empty($tv['Address1']))
							{
								$qry0 .= ",'".replacequote($tv['Address1'])."'"; //3
							}
							
							if (!empty($tv['City']))
							{
								$qry0 .= ",'".replacequote($tv['City'])."'"; //5
							}
							
							if (!empty($tv['State']))
							{
								$qry0 .= ",'".replacequote($tv['State'])."'"; //6
							}
							
							if (!empty($tv['Email']))
							{
								$qry0 .= ",'".replacequote($tv['Email'])."'"; //9
							}
							
							if (!empty($tv['Comments']))
							{
								$qry0 .= ",'".replacequote($tv['Comments'])."' ";
							}
							
							$qry0 .= ");"; //12 (10)
							echo $qry0."<br>";
					}
				}
			}
			
			if (file_exists($uploadfile))
			{
				unlink($uploadfile);
			}
		}
		else
		{
			echo "Possible file upload attack!\n";
		}
	}
}

function upfile4()
{
	error_reporting(E_ALL);
	$_SESSION['imp_errors']=array();
	
	if (!isset($_SESSION['puid']))
	{
		die('Transition Error. Exiting.');
	}
	
	if (!isset($_SESSION['imp_stage']) && count($_SESSION['imp_stage']) == 0)
	{
		die('Data Error. Exiting.');
	}
	else
	{
		$proc=0;
		$perr=0;
		
		foreach ($_SESSION['imp_stage'] as $n => $v)
		{
			//echo $v."<br>";
			$qry  = $v;
			$qry .= "SELECT @@IDENTITY as ins_id;";
			$res  = mssql_query($qry);
			$row  = mssql_fetch_array($res);
			
			if (isset($row['ins_id']) &&  $row['ins_id']!=0)
			{
				$proc++;
			}
			else
			{
				$_SESSION['imp_errors'][$n]=$v;
				$perr++;
			}
			//$perr++;
		}
		
		echo "<table class=\"outer\" align=\"center\" width=\"35%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" class=\"ltgray_und\" align=\"center\"><b>Lead File Import</b>: Results</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\"><b>Leads Input</b></td>\n";
		echo "		<td class=\"gray\">".count($_SESSION['imp_stage'])."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\"><b>Leads Processed</b></td>\n";
		echo "		<td class=\"gray\">".$proc."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\"><b>Errors</b></td>\n";
		echo "		<td class=\"gray\">".$perr."</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		
		unset($_SESSION['imp_stage']);
		
		autosort();
	}
}

function access_report()
{
	if ($_SESSION['tlev'] < 8 && $_SESSION['m_llev'] < 1)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}
	else
	{
		$qry0 = "SELECT id FROM zip_link;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		$qry = "SELECT * FROM offices WHERE active=1 ORDER BY grouping,name ASC";
		$res = mssql_query($qry);

		echo "<table align=\"center\" border=0 width=\"65%\">\n";
		echo "	<tr>\n";
		echo "   	<td class=\"gray\">\n";
		echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
		echo "				<tr>\n";
		echo "   				<td class=\"gray\" colspan=\"5\"><b>Lead Administrator Access List</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\"><b>Name</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>City</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>Phone</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>Matrix</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>Lead Adm</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>Last Login</b></td>\n";
		echo "				</tr>\n";

		while ($row = mssql_fetch_array($res))
		{
			$qryC = "SELECT securityid,lname,fname,curr_login,laccess FROM security WHERE securityid='".$row['am']."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);
			
			$qryD = "SELECT id FROM zip_to_zip WHERE ozip='".$row['zip']."';";
			$resD = mssql_query($qryD);
			$nrowD= mssql_num_rows($resD);

			echo "				<tr>\n";
			echo "					<td class=\"wh_und\">".$row['name']."</td>\n";
			echo "					<td class=\"wh_und\">".$row['city']."</td>\n";
			echo "					<td class=\"wh_und\">".$row['phone']."</td>\n";
			echo "					<td class=\"wh_und\">".$nrowD."</td>\n";
			echo "					<td class=\"wh_und\">".$rowC['fname']." ".$rowC['lname']."</td>\n";
			echo "					<td class=\"wh_und\">".$rowC['curr_login']."</td>\n";
			echo "				</tr>\n";
		}

		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function access_report()
{
	if ($_SESSION['tlev'] < 8 && $_SESSION['m_llev'] < 1)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}
	else
	{
		$qry0 = "SELECT id FROM zip_link;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		$qry = "SELECT * FROM offices WHERE active=1 ORDER BY grouping,name ASC";
		$res = mssql_query($qry);

		echo "<table align=\"center\" border=0 width=\"65%\">\n";
		echo "	<tr>\n";
		echo "   	<td class=\"gray\">\n";
		echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
		echo "				<tr>\n";
		echo "   				<td class=\"gray\" colspan=\"5\"><b>Lead Administrator Access List</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\"><b>Name</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>City</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>Phone</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>Matrix</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>Lead Adm</b></td>\n";
		echo "					<td class=\"ltgray_und\"><b>Last Login</b></td>\n";
		echo "				</tr>\n";

		while ($row = mssql_fetch_array($res))
		{
			$qryC = "SELECT securityid,lname,fname,curr_login,laccess FROM security WHERE securityid='".$row['am']."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);
			
			$qryD = "SELECT id FROM zip_to_zip WHERE ozip='".$row['zip']."';";
			$resD = mssql_query($qryD);
			$nrowD= mssql_num_rows($resD);

			echo "				<tr>\n";
			echo "					<td class=\"wh_und\">".$row['name']."</td>\n";
			echo "					<td class=\"wh_und\">".$row['city']."</td>\n";
			echo "					<td class=\"wh_und\">".$row['phone']."</td>\n";
			echo "					<td class=\"wh_und\">".$nrowD."</td>\n";
			echo "					<td class=\"wh_und\">".$rowC['fname']." ".$rowC['lname']."</td>\n";
			echo "					<td class=\"wh_und\">".$rowC['curr_login']."</td>\n";
			echo "				</tr>\n";
		}

		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function lead_search()
{
	unset($_SESSION['tqry']);
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
	unset($_SESSION['d3']);
	unset($_SESSION['d4']);
	unset($_SESSION['d5']);
	unset($_SESSION['d6']);
	unset($_SESSION['d7']);
	unset($_SESSION['d8']);
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);
	
	$qry2 = "SELECT ldexport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
	
	$qry3 = "SELECT securityid,lname,fname,slevel,gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
	
	$acclist		=explode(",",$_SESSION['aid']);

	echo "<table width=\"70%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"center\"><b>Lead Search Tool</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                 <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" colspan=\"2\"><b>Search by:</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b></b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Sort by:</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Direction:</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">Incl Addr</td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">Aged 30+</td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">Callbacks</td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Inactive</td>\n";
	}

	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\">\n";
	echo "                                    <select name=\"field\">\n";
	echo "                                    	<option value=\"clname\" SELECTED>Last Name</option>\n";
	echo "                                    	<option value=\"custid\">Lead ID</option>\n";
	echo "                                    	<option value=\"caddr1\">Customer Addr</option>\n";
	echo "                                    	<option value=\"czip1\">Customer Zip</option>\n";
	echo "                                    	<option value=\"saddr1\">Site Addr</option>\n";
	echo "                                    	<option value=\"szip1\">Site Zip</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                    	<option value=\"clname\">Last Name</option>\n";
	echo "                                    	<option value=\"custid\">Lead ID</option>\n";
	echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
	echo "                                    	<option value=\"added\" SELECTED>Date Added</option>\n";
	echo "                                    	<option value=\"updated\">Last Update</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"dir\">\n";
	echo "                                    	<option value=\"asc\">Ascending</option>\n";
	echo "                                    	<option value=\"desc\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>";
	echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"incaddr\" value=\"1\" title=\"Check this box to include the Customer Address\"></td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\" title=\"Check this box to include Leads that have not been updated within the last 30 days\"></td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\" title=\"Check this box to include Callbacks\"></td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this box to include Inactive Leads\"></td>\n";
	}

	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\">\n";
	echo "                                    <select name=\"dtype\">\n";
	echo "                                    	<option value=\"added\" SELECTED>Date Added</option>\n";
	echo "                                    	<option value=\"updated\">Last Update</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
	echo "												<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
	echo "												<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" colspan=\"7\">(Date Optional)</td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch1'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch1'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "										<tr>\n";
	echo "                              	<td align=\"center\" colspan=\"9\"><hr width=\"100%\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch2\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"srcstatus\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Lead Source:</b>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"statusid\">\n";

	while ($row = mssql_fetch_array($res))
	{
		if ($row['statusid']==0)
		{
			//echo "                                    	<option value=\"".$row['statusid']."\">Internet</option>\n";
			echo "                                    	<option value=\"".$row['statusid']."\">bluehaven.com</option>\n";
		}
		elseif ($row['statusid']==1)
		{
			echo "                                    	<option value=\"".$row['statusid']."\">Manual</option>\n";
		}
		else
		{
			echo "                                    	<option value=\"".$row['statusid']."\">".$row['name']."</option>\n";
		}
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                    	<option value=\"clname\">Last Name</option>\n";
	echo "                                    	<option value=\"custid\">Lead ID</option>\n";
	//echo "                                    	<option value=\"czip1\">Customer Zip</option>\n";
	echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
	echo "                                    	<option value=\"added\" SELECTED>Date Added</option>\n";
	echo "                                    	<option value=\"updated\">Last Update</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"dir\">\n";
	echo "                                    	<option value=\"asc\">Ascending</option>\n";
	echo "                                    	<option value=\"desc\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>";
	echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"incaddr\" value=\"1\" title=\"Check this box to include the Customer Address\"></td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\" title=\"Check this box to include Leads that have not been updated within the last 30 days\"></td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\" title=\"Check this box to include Callbacks\"></td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this box to include Inactive Leads\"></td>\n";
	}

	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\">\n";
	echo "                                    <select name=\"dtype\">\n";
	echo "                                    	<option value=\"added\" SELECTED>Date Added</option>\n";
	echo "                                    	<option value=\"updated\">Last Update</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d3\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal3.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d4\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal4.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" colspan=\"7\">(Date Optional)</td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal3 = new calendar2(document.forms['tsearch2'].elements['d3']);\n";
	echo "         						cal3.year_scroll = false;\n";
	echo "         						cal3.time_comp = false;\n";
	echo "         						var cal4 = new calendar2(document.forms['tsearch2'].elements['d4']);\n";
	echo "         						cal4.year_scroll = false;\n";
	echo "         						cal4.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "										<tr>\n";
	echo "                              	<td align=\"center\" colspan=\"9\"><hr width=\"100%\"></td>\n";
	echo "										</tr>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch3\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"resstatus\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Lead Results:</b>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"statusid\">\n";

	while ($row0 = mssql_fetch_array($res0))
	{
		echo "                                    	<option value=\"".$row0['statusid']."\">".$row0['name']."</option>\n";
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                    	<option value=\"clname\">Last Name</option>\n";
	echo "                                    	<option value=\"custid\">Lead ID</option>\n";
	echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
	echo "                                    	<option value=\"added\" SELECTED>Date Added</option>\n";
	echo "                                    	<option value=\"updated\">Last Update</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"dir\">\n";
	echo "                                    	<option value=\"asc\">Ascending</option>\n";
	echo "                                    	<option value=\"desc\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>";
	echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"incaddr\" value=\"1\" title=\"Check this box to include the Customer Address\"></td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\" title=\"Check this box to include Leads that have not been updated within the last 30 days\"></td>\n";
	//echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showreno\" value=\"1\" title=\"Check this box to include Renovation Leads\"></td>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\" title=\"Check this box to include Callbacks\"></td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this box to include Inactive Leads\"></td>\n";
	}

	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\">\n";
	echo "                                    <select name=\"dtype\">\n";
	echo "                                    	<option value=\"added\" SELECTED>Date Added</option>\n";
	echo "                                    	<option value=\"updated\">Last Update</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d5\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal5.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d6\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal6.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" colspan=\"7\">(Date Optional)</td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal5 = new calendar2(document.forms['tsearch3'].elements['d5']);\n";
	echo "         						cal5.year_scroll = false;\n";
	echo "         						cal5.time_comp = false;\n";
	echo "         						var cal6 = new calendar2(document.forms['tsearch3'].elements['d6']);\n";
	echo "         						cal6.year_scroll = false;\n";
	echo "         						cal6.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "										<tr>\n";
	echo "                              	<td align=\"center\" colspan=\"9\"><hr width=\"100%\"></td>\n";
	echo "										</tr>\n";

	if ($_SESSION['llev'] >= 4)
	{
		echo "										<tr>\n";
		echo "         								<form name=\"tsearch4\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
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
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontred\">".$row1['lname'].", ".$row1['fname']." ".$dis."</option>\n";
				}
				else
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontblack\">".$row1['lname'].", ".$row1['fname']." ".$dis."</option>\n";
				}
			}
		}

		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
		echo "                              	<td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "                                    	<option value=\"clname\">Last Name</option>\n";
		echo "                                    	<option value=\"custid\">Lead ID</option>\n";
		echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
		echo "                                    	<option value=\"added\" SELECTED>Date Added</option>\n";
		echo "                                    	<option value=\"updated\">Last Update</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\">\n";
		echo "                                    <select name=\"dir\">\n";
		echo "                                    	<option value=\"asc\">Ascending</option>\n";
		echo "                                    	<option value=\"desc\">Descending</option>\n";
		echo "                                    </select>\n";
		echo "											</td>";
		echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"incaddr\" value=\"1\" title=\"Check this box to include the Customer Address\"></td>\n";
		echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\"  title=\"Check this box to include Leads that have not bee updated within the last 30 days\"></td>\n";
		//echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showreno\" value=\"1\" title=\"Check this box to include Renovation Leads\"></td>\n";

		if ($_SESSION['llev'] >= 5)
		{
			echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\" title=\"Check this box to include Callbacks\"></td>\n";
			echo "                                 <td align=\"center\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this box to include Inactive Leads\"></td>\n";
		}
		else
		{
			echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
		}

		echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
		echo "										<tr>\n";
		echo "                              	<td align=\"right\">\n";
		echo "                                    <select name=\"dtype\">\n";
		echo "                                    	<option value=\"added\" SELECTED>Date Added</option>\n";
		echo "                                    	<option value=\"updated\">Last Update</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "					<input class=\"bboxl\" type=\"text\" name=\"d7\" size=\"11\">\n";
		echo "					<a href=\"javascript:cal7.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
		echo "					<input class=\"bboxl\" type=\"text\" name=\"d8\" size=\"11\">\n";
		echo "					<a href=\"javascript:cal8.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
		echo "											</td>\n";
		echo "                                 <td align=\"left\" colspan=\"7\">(Date Optional)</td>\n";
		echo "										</tr>\n";
		echo "         								</form>\n";

		echo "         						<script language=\"JavaScript\">\n";
		echo "         						var cal7 = new calendar2(document.forms['tsearch4'].elements['d7']);\n";
		echo "         						cal7.year_scroll = false;\n";
		echo "         						cal7.time_comp = false;\n";
		echo "         						var cal8 = new calendar2(document.forms['tsearch4'].elements['d8']);\n";
		echo "         						cal8.year_scroll = false;\n";
		echo "         						cal8.time_comp = false;\n";
		echo "         						//-->\n";
		echo "         						</script>\n";
	}
	
	if ($row3['gmreports']==1 && $row2['ldexport']==1)
	{
		echo "										<tr>\n";
		echo "                              	<td align=\"center\" colspan=\"9\"><hr width=\"100%\"></td>\n";
		echo "										</tr>\n";
		echo "         								<form name=\"tsearch5\" action=\"export/ldexport.php\" method=\"post\" target=\"_new\">\n";
		//echo "         								<form name=\"tsearch5\" action=\"subs\test.txt\" method=\"post\" target=\"_new\">\n";
		echo "											<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "											<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "										<tr>\n";
		echo "                              	<td align=\"right\"><b>Export by Date:</b></td>\n";
		echo "                              	<td align=\"left\" NOWRAP>\n";
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
		echo "												<a href=\"javascript:cal9.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
		echo "												<a href=\"javascript:cal10.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
		echo "											</td>\n";
		echo "                                 			<td align=\"left\"></td>\n";
		echo "                                 			<td align=\"left\" colspan=\"5\">\n";
		echo "&nbspBy checking this box you certify that the exported information will be used for the sole interest of Blue Haven Pools & Spas";
		/*
		echo "															<select name=\"expfields[]\" MULTIPLE size=\"1\" title=\"Hold down CTRL and left mouse click to select multiple fields\">\n";
		echo "																<option></option>\n";
		echo "																<option value=\"cfname\">First Name</option>\n";
		echo "																<option value=\"clname\">Last Name</option>\n";
		echo "																<option value=\"caddr1\">Cust Address</option>\n";
		echo "																<option value=\"saddr1\">Pool Address</option>\n";
		echo "																<option value=\"cstate\">Cust State</option>\n";
		echo "																<option value=\"sstate\">Pool State</option>\n";
		echo "															</select>\n";
		*/
		echo "														</td>\n";
		echo "                             			    	<td align=\"center\"><input class=\"checkbox\" type=\"checkbox\" name=\"certify\" value=\"1\" title=\"By checking this box you certify that the exported information will be used for the sole interest of Blue Haven Pools & Spas\"></td>\n";
		echo "                            			   	<td align=\"left\"><input class=\"buttondkgrypnl80\" type=\"submit\" name=\"export\" value=\"Export\" title=\"This button will create a comma de-limited text file with Customer Information originated in the JMS within Date Range indicated.\"></td>\n";
		echo "										</tr>\n";
		echo "       								</form>\n";
	
		echo "         						<script language=\"JavaScript\">\n";
		echo "         						var cal9 = new calendar2(document.forms['tsearch5'].elements['d1']);\n";
		echo "         						cal9.year_scroll = false;\n";
		echo "         						cal9.time_comp = false;\n";
		echo "         						var cal10 = new calendar2(document.forms['tsearch5'].elements['d2']);\n";
		echo "         						cal10.year_scroll = false;\n";
		echo "         						cal10.time_comp = false;\n";
		echo "         						//-->\n";
		echo "         						</script>\n";
	}
	
	/*
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Unique Zip Codes:</b>\n";
	echo "                                 <td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Aged 30+ <input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Holds <input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Duplicates <input class=\"checkbox\" type=\"checkbox\" name=\"showdupes\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Unique Last Names:</b>\n";
	echo "                                 <td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Aged 30+ <input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Holds <input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Duplicates <input class=\"checkbox\" type=\"checkbox\" name=\"showdupes\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										</tr>\n";
	*/
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

function lead_searchold()
{
	unset($_SESSION['tqry']);
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);

	$acclist		=explode(",",$_SESSION['aid']);

	echo "<table width=\"50%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"center\"><b>Lead Search Tool</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                 <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\" colspan=\"2\"><b>Search by:</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Field:</b></td>\n";
	//echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Overrides:</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Aged 30+ </td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Callbacks</td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">Inactive</td>\n";
	}

	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>String Search:</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"field\">\n";
	echo "                                    	<option value=\"clname\" SELECTED>Last Name</option>\n";
	echo "                                    	<option value=\"custid\">Lead ID</option>\n";
	echo "                                    	<option value=\"czip1\">Customer Zip</option>\n";
	echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	//echo "                                 <td align=\"left\" valign=\"bottom\"></td>";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\" title=\"Check this box to include Leads that have not been updated within the last 30 days\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\" title=\"Check this box to include Callbacks\"></td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this box to include Inactive Leads\"></td>\n";
	}

	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                              	<td align=\"left\" colspan=\"5\">\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "				</td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['tsearch1'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['tsearch1'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "										<tr>\n";
	echo "                              	<td align=\"center\" colspan=\"8\"><hr width=\"100%\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch2\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"srcstatus\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Lead Source:</b>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"statusid\">\n";

	while ($row = mssql_fetch_array($res))
	{
		if ($row['statusid']==0)
		{
			//echo "                                    	<option value=\"".$row['statusid']."\">Internet</option>\n";
			echo "                                    	<option value=\"".$row['statusid']."\">bluehaven.com</option>\n";
		}
		elseif ($row['statusid']==1)
		{
			echo "                                    	<option value=\"".$row['statusid']."\">Manual</option>\n";
		}
		else
		{
			echo "                                    	<option value=\"".$row['statusid']."\">".$row['name']."</option>\n";
		}
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"></td>\n";
	//echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\" title=\"Check this box to include Leads that have not been updated within the last 30 days\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\" title=\"Check this box to include Callbacks\"></td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this box to include Inactive Leads\"></td>\n";
	}

	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                              	<td align=\"left\" colspan=\"5\">\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d3\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal3.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d4\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal4.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "				</td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal3 = new calendar2(document.forms['tsearch2'].elements['d3']);\n";
	echo "         						cal3.year_scroll = false;\n";
	echo "         						cal3.time_comp = false;\n";
	echo "         						var cal4 = new calendar2(document.forms['tsearch2'].elements['d4']);\n";
	echo "         						cal4.year_scroll = false;\n";
	echo "         						cal4.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "										<tr>\n";
	echo "                              	<td align=\"center\" colspan=\"8\"><hr width=\"100%\"></td>\n";
	echo "										</tr>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form name=\"tsearch3\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"resstatus\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Lead Results:</b>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"statusid\">\n";

	while ($row0 = mssql_fetch_array($res0))
	{
		echo "                                    	<option value=\"".$row0['statusid']."\">".$row0['name']."</option>\n";
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"></td>\n";
	//echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\" title=\"Check this box to include Leads that have not been updated within the last 30 days\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\" title=\"Check this box to include Callbacks\"></td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this box to include Inactive Leads\"></td>\n";
	}

	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                              	<td align=\"left\" colspan=\"5\">\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d5\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal5.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"d6\" size=\"11\">\n";
	echo "					<a href=\"javascript:cal6.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "				</td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";

	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal5 = new calendar2(document.forms['tsearch3'].elements['d5']);\n";
	echo "         						cal5.year_scroll = false;\n";
	echo "         						cal5.time_comp = false;\n";
	echo "         						var cal6 = new calendar2(document.forms['tsearch3'].elements['d6']);\n";
	echo "         						cal6.year_scroll = false;\n";
	echo "         						cal6.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";

	echo "										<tr>\n";
	echo "                              	<td align=\"center\" colspan=\"8\"><hr width=\"100%\"></td>\n";
	echo "										</tr>\n";

	if ($_SESSION['llev'] >= 4)
	{
		echo "										<tr>\n";
		echo "         								<form name=\"tsearch4\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
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
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontred\">".$row1['lname'].", ".$row1['fname']." ".$dis."</option>\n";
				}
				else
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontblack\">".$row1['lname'].", ".$row1['fname']." ".$dis."</option>\n";
				}
			}
		}

		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\"></td>";
		echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\"  title=\"Check this box to include Leads that have not been updated within the last 30 days\"></td>\n";
		//echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";

		if ($_SESSION['llev'] >= 5)
		{
			echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\" title=\"Check this box to include Callbacks\"></td>\n";
			echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"checkbox\" type=\"checkbox\" name=\"showdupe\" value=\"1\" title=\"Check this box to include Inactive Leads\"></td>\n";
		}
		else
		{
			echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
		}

		echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
		echo "										<tr>\n";
		echo "                              	<td align=\"right\" valign=\"bottom\"></td>\n";
		echo "                              	<td align=\"left\" colspan=\"5\">\n";
		echo "					<input class=\"bboxl\" type=\"text\" name=\"d7\" size=\"11\">\n";
		echo "					<a href=\"javascript:cal7.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
		echo "					<input class=\"bboxl\" type=\"text\" name=\"d8\" size=\"11\">\n";
		echo "					<a href=\"javascript:cal8.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
		echo "				</td>\n";
		echo "										</tr>\n";
		echo "         								</form>\n";

		echo "         						<script language=\"JavaScript\">\n";
		echo "         						var cal7 = new calendar2(document.forms['tsearch4'].elements['d7']);\n";
		echo "         						cal7.year_scroll = false;\n";
		echo "         						cal7.time_comp = false;\n";
		echo "         						var cal8 = new calendar2(document.forms['tsearch4'].elements['d8']);\n";
		echo "         						cal8.year_scroll = false;\n";
		echo "         						cal8.time_comp = false;\n";
		echo "         						//-->\n";
		echo "         						</script>\n";

	}
	/*
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Unique Zip Codes:</b>\n";
	echo "                                 <td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Aged 30+ <input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Holds <input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Duplicates <input class=\"checkbox\" type=\"checkbox\" name=\"showdupes\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Unique Last Names:</b>\n";
	echo "                                 <td align=\"right\" valign=\"bottom\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Aged 30+ <input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Holds <input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">Duplicates <input class=\"checkbox\" type=\"checkbox\" name=\"showdupes\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										</tr>\n";
	*/
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

function lead_search_results()
{
	$officeid=$_SESSION['officeid'];
	$securityid=$_SESSION['securityid'];

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
		$order="custid";
		$dir="ASC";
	}

	if (isset($_POST['showdupe']) && $_POST['showdupe']==1)
	{
		$dupe="";
	}
	else
	{
		$dupe="AND dupe!=1 ";
	}

	if (isset($_POST['showhold']) && $_POST['showhold']==1)
	{
		$hold="";
	}
	else
	{
		$hold="AND hold!=1 ";
	}

	if ($_SESSION['llev'] >= 4)
	{
		$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold."ORDER BY ".$order." ".$dir.";";
	}
	//elseif ($_SESSION['llev'] == 4)
	//{
	//   $qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND sidm='".$_SESSION['securityid']."' ORDER BY ".$order." ".$dir.";";
	//}
	else
	{
		$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_SESSION['securityid']."' AND dupe!=1 ".$hold."ORDER BY ".$order." ".$dir.";";
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//echo"XXX";

}

function viewunproclist()
{
	if ($_SESSION['llev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Resource</b>";
		exit;
	}

	if (empty($_POST['order']))
	{
		$order="lid";
	}
	else
	{
		$order=$_POST['order'];
	}

	$qryA	="SELECT * FROM lead_inc WHERE sorted!=1 ORDER BY ".$order.";";
	$resA	= mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);

	$qryB	="SELECT * FROM offices WHERE am!=0 AND active=1 ORDER BY name;";
	$resB	= mssql_query($qryB);

	echo "<table width=\"85%\">\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"top\">\n";
	echo "						<b>".$nrowA." Unsorted Lead(s)</b>";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";

	if ($nrowA > 0)
	{
		echo "	<tr>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<table width=\"100%\">\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">\n";
		echo "					<table class=\"outer\" width=\"100%\">\n";
		echo "						<tr>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"lname\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Name\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"phone\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Phone\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"addr\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Address\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"city\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"City\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"state\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"State\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"zip\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Zip Code\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"submitted\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Date\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						</tr>\n";

		while($rowA = mssql_fetch_array($resA))
		{
			echo "						<tr>\n";
			//echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><a href=\"".$_SERVER['PHP_SELF']."?action=maint&call=leads&subq=view_lform&type=proc&lid=".$rowA['lid']."\"><b>".$rowA['lname']."</b></a></td>\n";
			echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>".$rowA['lname']."</b></td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$rowA['phone']."</b></td>\n";
			echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$rowA['addr']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$rowA['city']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowA['state']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowA['zip']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowA['submitted']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\">\n";
			echo "                        <input class=\"checkboxwh\" type=\"checkbox\" name=\"xzx".$rowA['lid']."\" value=\"xzx".$rowA['lid']."\">\n";
			echo "							</td>\n";
			echo "						</tr>\n";
		}

		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\">\n";

		if ($nrowA > 0)
		{
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
			echo "								<input type=\"hidden\" name=\"subq\" value=\"mansort\">\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"bottom\" width=\"90px\">\n";
			echo "						<select name=\"toofficeid\">\n";
			echo "                           <option value=\"0\"></option>\n";

			while($rowB = mssql_fetch_array($resB))
			{
				echo "                           <option value=\"".$rowB['officeid']."\">".$rowB['name']."</option>\n";
			}

			echo "						</select>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"bottom\" width=\"90px\">\n";
			echo "                        <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Manual Process\">\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "                        </form>\n";
			echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
			echo "								<input type=\"hidden\" name=\"subq\" value=\"autosort\">\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"bottom\" width=\"90px\">\n";
			echo "                        <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Auto Process\">\n";
			echo "					</td>\n";
			echo "                        </form>\n";
			echo "				</tr>\n";
			
			/*
			if ($_SESSION['securityid']==26)
			{
				echo "				<tr>\n";
				echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
				echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
				echo "								<input type=\"hidden\" name=\"subq\" value=\"autosort_zip\">\n";
				echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"bottom\" width=\"90px\">\n";
				echo "                        <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Auto (ZIP) \">\n";
				echo "					</td>\n";
				echo "                        </form>\n";
				echo "				</tr>\n";
			}
			*/
			
			echo "			</table>\n";
		}

		echo "		</td>\n";
		echo "	</tr>\n";
	}
	echo "</table>\n";
}

function viewproclist()
{
	if ($_SESSION['llev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Resource</b>";
		exit;
	}

	if (empty($_POST['order']))
	{
		$order="submitted";
	}
	else
	{
		$order=$_POST['order'];
	}

	$qryA	="SELECT * FROM lead_inc WHERE sorted!=0 ORDER BY ".$order.";";
	$resA	= mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);

	//$qryB	="SELECT * FROM offices WHERE am!=0 AND active=1 ORDER BY name;";
	//$resB	= mssql_query($qryB);

	echo "<table width=\"85%\">\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"top\">\n";
	echo "						<b>".$nrowA." Sorted Lead(s)</b>";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";

	if ($nrowA > 0)
	{
		echo "	<tr>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<table width=\"100%\">\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">\n";
		echo "					<table class=\"outer\" width=\"100%\">\n";
		echo "						<tr>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"lname\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Name\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"phone\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Phone\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"addr\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Address\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"city\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"City\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"state\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"State\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"zip\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Zip Code\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"submitted\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Submit Date\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"added\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add Date\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						</tr>\n";

		while($rowA = mssql_fetch_array($resA))
		{
			$qryB	="SELECT name FROM offices WHERE officeid='".$rowA['tooffice']."';";
			$resB	= mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			echo "						<tr>\n";
			echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>".$rowA['lname']."</a></td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\"><b>".$rowA['phone']."</b></td>\n";
			echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$rowA['addr']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$rowA['city']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowA['state']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowA['zip']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowA['submitted']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\" valign=\"bottom\">".$rowA['added']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>".$rowB['name']."</b></td>\n";
			echo "						</tr>\n";
		}

		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
	echo "</table>\n";
}

function viewunproclistold()
{
	if ($_SESSION['llev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Resource</b>";
		exit;
	}

	$qryA	="SELECT * FROM lead_inc_bucket ORDER BY added DESC;";
	$resA	= mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);

	echo "<table width=\"75%\">\n";
	echo "		<td align=\"left\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"top\">\n";
	echo "						<b>".$nrowA." Unprocessed Lead(s)</b>";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";

	if ($nrowA > 0)
	{
		echo "	<tr>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<table class=\"outer\" width=\"100%\">\n";
		echo "			<tr>\n";
		echo "				<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">\n";
		echo "					<table class=\"outer\" width=\"100%\">\n";
		echo "						<tr>\n";
		//echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">ID</td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Subject</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Date</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"></td>\n";
		echo "						<tr>\n";


		while($rowA = mssql_fetch_array($resA))
		{
			echo "						<tr>\n";
			//echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$rowA['id']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$rowA['subject']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$rowA['added']."</td>\n";
			echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
			echo "								<input type=\"hidden\" name=\"subq\" value=\"view_lform\">\n";
			echo "								<input type=\"hidden\" name=\"type\" value=\"unproc\">\n";
			echo "								<input type=\"hidden\" name=\"lid\" value=\"".$rowA['id']."\">\n";
			echo "							<td class=\"wh_und\" align=\"right\" valign=\"bottom\">\n";
			echo "                        <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View\">\n";
			echo "							</td>\n";
			echo "                        </form>\n";
			echo "						</tr>\n";
		}

		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
	echo "</table>\n";
}

function mailproc()
{
	if (empty($_POST['conf'])||$_POST['conf']!=1)
	{
		echo "<table width=\"25%\">\n";
		echo "		<td colspan=\"2\" align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">\n";
		echo "						<b>Confirm Get Leads Request:</b>";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" bgcolor=\"#d3d3d3\" align=\"center\" valign=\"top\">\n";
		echo "						<font color=\"red\">Warning!!</font> This process is not reversible!</font>";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "         		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"mailproc\">\n";
		echo "						<input type=\"hidden\" name=\"conf\" value=\"1\">\n";
		echo "				<tr>\n";
		echo "      			<td bgcolor=\"#d3d3d3\" align=\"right\"><b>Process?</b></td>\n";
		echo "      			<td bgcolor=\"#d3d3d3\" align=\"right\">\n";
		echo "						<input class=\"checkboxgry\" type=\"radio\" name=\"conf\" value=\"1\"> Yes\n";
		echo "						<input class=\"checkboxgry\" type=\"radio\" name=\"conf\" value=\"0\" CHECKED> No\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Confirm\">\n";
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
		$MAIL_HOST="mail.masterlink.com";
		$MAIL_HOST_CONNECT="{".$MAIL_HOST."/pop3:110}INBOX";
		//$MAIL_USER_NAME="leadproc@bluehaven.com";
		//$MAIL_USER_PASS="alti91";
		$MAIL_USER_NAME="bluehaven@bluehaven.com";
		$MAIL_USER_PASS="nuvo1991";

		//echo "Logging into ".$MAIL_USER_NAME.". Please be patient.<br>";

		$mbox	= imap_open($MAIL_HOST_CONNECT,$MAIL_USER_NAME,$MAIL_USER_PASS) or die("Error: Could not Connect");
		$total	= imap_num_msg($mbox);
		$s_sub1	= "Web Site Info Request";
		$s_sub2	= "Web Site Credit Application";

		$errors=imap_errors();
		if (is_array($errors))
		{
			echo $errors[0];
			exit;
		}

		if ($total > 0)
		{
			echo "<table width=\"75%\">\n";
			echo "		<td colspan=\"2\" align=\"left\" valign=\"top\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">\n";
			echo "						<b>Mail Processing Results:</b>";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "	<td align=\"left\" valign=\"top\">\n";
			echo "		<table class=\"outer\" width=\"100%\">\n";
			echo "			<tr>\n";
			echo "				<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">\n";
			echo "					<pre>";

			$w=0;
			$y=0;
			for($x=$total; $x > 0; $x--)
			{
				$z=0;
				$header 		= imap_header($mbox, $x);
				$body 		= imap_fetchbody($mbox, $x,1);

				if ($header->subject==$s_sub1)
				{
					// Match Incoming Email Info
					if (preg_match("/was submitted +[0-9]{1,}\/[0-9]{1,}\/[0-9]{1,} +[0-9]{1,}:[0-9]{1,}:[0-9]{1,} +[A-Z]{1,2}/",$body,$matches))
					{
						$u_sub=preg_split("/ +/",$matches[0]);
						$sub=$u_sub[2]." ".$u_sub[3]." ".$u_sub[4];
					}
					else
					{
						$sub="";
					}

					if (preg_match("/Name: +[\w+\s\'\-\&\.]+\n/",$body,$matches))
					{
						$u_name=preg_split("/ +/",$matches[0]);
						$u_name=array_slice($u_name,1);

						$name="";
						foreach($u_name as $n => $v)
						{
							$name=$name." ".$v;
						}
						$name=preg_replace('/^ /','',$name);
					}
					else
					{
						$name="";
						$z++;
						//echo "Prob<br>";
					}

					if (preg_match("/Address: +[\w+\s\'\.\#\@]+\n/",$body,$matches))
					{
						$u_addr=preg_split("/ +/",$matches[0]);
						$u_addr=array_slice($u_addr,1);

						$addr="";
						foreach($u_addr as $n => $v)
						{
							$addr=$addr." ".$v;
						}
						$addr=preg_replace('/^ /','',$addr);
					}
					else
					{
						$addr="";
					}

					if (preg_match("/City: +[\w+\s\'\.\#\@]+ State:/",$body,$matches))
					{
						$u_city=preg_split("/ +/",$matches[0]);
						$u_city=array_slice($u_city,1,-1);

						$city="";
						foreach($u_city as $n => $v)
						{
							$city=$city." ".$v;
						}
						$city=preg_replace('/^ /','',$city);
					}
					else
					{
						$city="";
					}

					if (preg_match("/State: +[a-zA-Z0-9]{1,2}/",$body,$matches))
					{
						$u_state=preg_split("/ +/",$matches[0]);
						$u_state=array_slice($u_state,1);
						$state=$u_state[0];
					}
					else
					{
						$state="";
					}

					if (preg_match("/Zip: +[0-9]{1,}/",$body,$matches))
					{
						$u_zip=preg_split("/ +/",$matches[0]);
						$u_zip=array_slice($u_zip,1);
						$zip=$u_zip[0];
					}
					else
					{
						$zip="";
						$z++;
					}

					if (preg_match("/E-mail: ([a-z][a-z0-9_.-\/]*@[^\s\"\)\?<>]+\.[a-z]{2,6})/i",$body,$matches))
					{
						$u_email=preg_split("/ +/",$matches[0]);
						$u_email=array_slice($u_email,1);
						$email=$u_email[0];
						//echo "<br>email: ".$email."<br>";
					}
					else
					{
						$email="";
						//echo "<br>NOT email: ".$email."<br>";
					}


					if (preg_match("/Phone Number: +\(?[0-9]{1,3}\)?(-|.|\/|\w)[0-9]{1,3}(-|.|\/|\w)[0-9]{1,4} +\([a-zA-Z0-9]{1,}\)/",$body,$matches))
					{
						$u_phone=preg_split("/ +/",$matches[0]);
						if (count($u_phone)==4)
						{
							$phone=$u_phone[2];
							$conph=$u_phone[3];
						}
						elseif (count($u_phone)==5)
						{
							$phone=$u_phone[2]." ".$u_phone[3];
							$conph=$u_phone[4];
						}
						elseif (count($u_phone)==6)
						{
							$phone=$u_phone[2]." ".$u_phone[3]." ".$u_phone[4];
							$conph=$u_phone[5];
						}
						else
						{
							$phone="";
							$conph="";
							$z++;
						}

						if ($conph=="(home)")
						{
							$conph="hm";
						}
						else
						{
							$conph="wk";
						}
					}
					else
					{
						$phone="";
						$conph="";
						$z++;
					}

					$pat='/\(?\)?\-?\.?\s?/';
					$rep='';
					$phone=preg_replace($pat,$rep,$phone);

					if (preg_match("/Contact Time: +[0-9]{1,}(\-?[0-9]{1,})? +[A-Z]{1,2}/",$body,$matches))
					{
						$u_time=preg_split("/ +/",$matches[0]);
						$time=$u_time[2]." ".$u_time[3];
					}
					else
					{
						$time="";
					}
					
					if (preg_match("/Opt1: +[0-1]/",$body,$matches))
					{
						$u_opt1=preg_split("/ +/",$matches[0]);
						
						if ($u_opt1[1]==1)
						{
							$opt1=$u_opt1[1];
						}
						else
						{
							$opt1=0;
						}
					}
					else
					{
						$opt1=0;
					}
					
					if (preg_match("/Opt2: +[0-1]/",$body,$matches))
					{
						$u_opt2=preg_split("/ +/",$matches[0]);
						
						if ($u_opt2[1]==1)
						{
							$opt2=$u_opt2[1];
						}
						else
						{
							$opt2=0;
						}
					}
					else
					{
						$opt2=0;
					}
					
					if (preg_match("/Opt3: +[0-1]/",$body,$matches))
					{
						$u_opt3=preg_split("/ +/",$matches[0]);
						
						if ($u_opt3[1]==1)
						{
							$opt3=$u_opt3[1];
						}
						else
						{
							$opt3=0;
						}
					}
					else
					{
						$opt3=0;
					}
					
					if (preg_match("/Opt4: +[0-1]/",$body,$matches))
					{
						$u_opt4=preg_split("/ +/",$matches[0]);
						
						if ($u_opt4[1]==1)
						{
							$opt4=$u_opt4[1];
						}
						else
						{
							$opt4=0;
						}
					}
					else
					{
						$opt4=0;
					}

					// Comments Code
					if (preg_match("/Requests\r[\-\s\S]+/",$body,$matches))
					{
						$comments=$matches[0];
					}
					else
					{
						$comments="";
					}

					if ($z==0)
					{
						//echo $header->subject."<br>";
						echo $name."<br>";
						echo $phone."<br>";
						echo $email."<br>";
						//echo $comments."<br>";
						//echo $body."<br>";

						$qry0	 = "INSERT INTO lead_inc ";
						$qry0 .= "(submitted,lname,addr,city,state,zip,phone,bphone,email,contime,comments,opt1,opt2,opt3,opt4) ";
						$qry0 .= "VALUES (";
						$qry0 .= "'".$sub."','".replacequote($name)."','".replacequote($addr)."',";
						$qry0 .= "'".replacequote($city)."','".$state."','".$zip."','".$phone."',";
						$qry0 .= "'".$conph."','".$email."','".$time."','".replacequote($comments)."'),";
						$qry0 .= "'".$opt1."','".$opt2."','".$opt3."','".$opt4."');";
						$res0	= mssql_query($qry0);
						//echo $qry0."<br>";
						$y++;
					}

					echo "----------------------<br>";
					echo $z." Error(s) this Recordset<br>";
					echo "<hr><br>";

					unset($u_sub);
					unset($u_name);
					unset($u_addr);
					unset($u_city);
					unset($u_state);
					unset($u_zip);
					unset($u_email);
					unset($u_phone);
					unset($u_time);

					//$check = imap_mailboxmsginfo($mbox);
					//print_r($check)." Check:".$x."<br>";
					imap_delete($mbox,$x);
				}
				else
				{
					imap_delete($mbox,$x);
					$w++;
				}
			}

			imap_expunge($mbox);
			imap_close($mbox);
		}

		echo "						</pre>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">\n";
		echo "						<b>Lead Mail Status:</b></br>";
		echo "						&nbsp<b>".$y."</b> Subject Match <br>";
		echo "						&nbsp<b>".$w."</b> No Match<br>";

		$q=$total-($y+$w);

		echo "						&nbsp<b>".$q."</b> Unattended<br>";
		echo "						&nbsp-------------------------------------<br>";
		echo "						&nbsp<b>".$total."</b> Total Messages<br>";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function mansort()
{
	$recdate=time();
	if (empty($_POST['toofficeid'])||$_POST['toofficeid']==0)
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> you must select an Office to Process Leads!";
		exit;
	}
	else
	{
		$qry	= "SELECT officeid,active,am,name FROM offices WHERE officeid='".$_POST['toofficeid']."';";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);

		if ($row['am']==0)
		{
			echo "<font color=\"red\"><b>ERROR!</b></font> Office must be Active or have an Admin Assigned to receive Leads!";
			exit;
		}
		else
		{
			$i=0;
			foreach ($_POST as $n=>$v)
			{
				if (substr($n,0,3)=="xzx")
				{
					$idata=substr($n,3);
					$parray[]=$idata;
					$i++;
				}
			}

			$tcnt=$i;
			foreach ($parray as $n1=>$v1)
			{
				$qryA	= "SELECT * FROM lead_inc WHERE lid='".$v1."';";
				$resA	= mssql_query($qryA);
				$rowA	= mssql_fetch_array($resA);

				$ndata=splitonspace($rowA['lname']);

				//echo $ndata[0]."<br>";
				//echo $ndata[1]."<br>";

				$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$row['officeid']."';";
				$resCa = mssql_query($qryCa);
				$rowCa = mssql_fetch_row($resCa);
				$nrowCa= mssql_num_rows($resCa);

				if ($nrowCa==0)
				{
					$ncid=1;
				}
				else
				{
					$ncid=$rowCa[0]+1;
				}

				$qryC	= "INSERT INTO cinfo ";
				$qryC .= "(officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cemail,cconph,chome,cwork,mrktproc,recdate,custid,opt1,opt2,opt3,opt4) ";
				$qryC .= "VALUES (";
				$qryC .= "'".$row['officeid']."','".$row['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($rowA['addr'])."',";
				$qryC .= "'".replacequote($rowA['city'])."','".replacequote($rowA['state'])."','".$rowA['zip']."','".replacequote($rowA['email'])."','".$rowA['bphone']."',";

				if ($rowA['bphone']=="wk")
				{
					$qryC .= "'','".$rowA['phone']."',";
				}
				else
				{
					$qryC .= "'".$rowA['phone']."','',";
				}

				$qryC .= "'".replacequote($rowA['comments'])."','".$recdate."','".$ncid."',";
				$qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."');";
				$resC	= mssql_query($qryC);
				//echo $qryC."<br>";

				$qryD	= "UPDATE lead_inc SET sorted=1,proctype=2,tooffice='".$row['officeid']."',secid='".$_SESSION['securityid']."' WHERE lid='".$v1."';";
				$resD	= mssql_query($qryD);
				//echo $qryD."<br>";
				//echo "<hr width=\"25%\">";
				$i--;
			}

			$mproc=$tcnt-$i;
			echo "<table class=\"outer\" align=\"center\" width=\"35%\">\n";
			echo "   <tr>\n";
			echo "      <td class=\"gray\">\n";

			if ($mproc > 0)
			{
				echo "			<table align=\"center\" width=\"100%\">\n";
				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\"><b>Office</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"center\"><b>Lead Count</b></td>\n";
				echo "   			</tr>\n";
				echo "   			<tr>\n";
				echo "      			<td class=\"wh_und\">".$row['name']."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\">".$mproc."</td>\n";
				echo "   			</tr>\n";
				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"right\"><b>Total Leads Posted:</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"center\">".$tcnt."</td>\n";
				echo "   			</tr>\n";
				echo "			</table>\n";
			}
			else
			{
				echo "<b>Nothing was Processed Manually or an Error Occurred</b>";
			}

			echo "      </td>\n";
			echo "   </tr>\n";
			echo "</table>\n";
		}
	}
}

function autosortZAP()
{
	$recdate	=time();
	$cdate	=time();
	$qry		= "SELECT * FROM lead_inc WHERE sorted!='1';";
	$res		= mssql_query($qry);
	$nrow	= mssql_num_rows($res);

	//echo "N1: ".$nrow."<br>";

	$qry0	= "SELECT * FROM offices WHERE active=1 AND am!='0';";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);

	$ap=0;
	if ($nrow0 > 0)
	{
		$sarray=array(0=>0);
		while($row0=mssql_fetch_array($res0))
		{
			$sarray[$row0['officeid']]=0;
		}
	}
	else
	{
		echo "<font color=\"red\"><b>ERROR!</b> no active Offices!</font>\n";
	}

	if ($nrow > 0)
	{
		while($row=mssql_fetch_array($res))
		{
			$inscnt	=0;
			if (strlen($row['phone']) >= 6)
			{
				$split=array(0=>substr($row['phone'],0,3),1=>substr($row['phone'],3,3));

				$qryA		= "SELECT * FROM zip_link WHERE area='".$split[0]."';";
				$resA		= mssql_query($qryA);
				$nrowA	= mssql_num_rows($resA);
				//echo "N2: ".$nrowA."<br>";
				//echo $row['phone']."(".$split[0].") ".$nrowA."<br>";

				if ($nrowA > 0)
				{
					//echo $row['phone']."(".$split[0].") ".$nrowA."<br>";
					while($rowA=mssql_fetch_array($resA))
					{
						if ($inscnt==0)
						{
							if ($rowA['pre']==$split[1])
							{
								//echo $row['phone']."(".$split[0].") SUB<br>";
								$qryB	= "SELECT officeid,am,name,active,leadforward FROM offices WHERE zip='".$rowA['zip']."';";
								$resB	= mssql_query($qryB);
								$rowB	= mssql_fetch_array($resB);

								if ($rowB['leadforward']==0)
								{
									if ($rowB['am']!=0 && $rowB['active']==1)
									{
										//echo $row['phone']."(".$split[0].") SUB<br>";
										$ndata=splitonspace($row['lname']);

										//echo $ndata[0]."<br>";
										//echo $ndata[1]."<br>";

										$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowB['officeid']."';";
										$resCa = mssql_query($qryCa);
										$rowCa = mssql_fetch_row($resCa);
										$nrowCa= mssql_num_rows($resCa);

										if ($nrowCa==0)
										{
											$ncid=1;
										}
										else
										{
											$ncid=$rowCa[0]+1;
										}

										$qryC	= "INSERT INTO cinfo ";
										$qryC .= "(officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source) ";
										$qryC .= "VALUES (";
										$qryC .= "'".$rowB['officeid']."','".$rowB['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
										$qryC .= "'".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."','".$row['bphone']."',";

										if ($row['bphone']=="wk")
										{
											$qryC .= "'','".$row['phone']."',";
										}
										else
										{
											$qryC .= "'".$row['phone']."','',";
										}

										$qryC .= "'".replacequote($row['email'])."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."');";
										$resC	= mssql_query($qryC);
										//echo "P1: ".$rowA['pre']."<br>";
										//echo "P1: ".$qryC."<br>";
										//echo $ndata[1]."<br>";

										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowB['officeid']."',secid='".$_SESSION['securityid']."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);

										$oid=$rowB['officeid'];
										if (is_array($sarray))
										{
											$sarray[$oid]=$sarray[$oid]+1;
										}
										else
										{
											$sarray=array($oid=>1);
										}
										$inscnt++;
									}
								}
								else
								{
									$qryBa	= "SELECT officeid,am,name,active FROM offices WHERE officeid='".$rowB['leadforward']."';";
									$resBa	= mssql_query($qryBa);
									$rowBa	= mssql_fetch_array($resBa);

									if ($rowBa['am']!=0 && $rowBa['active']==1)
									{
										$ndata=splitonspace($row['lname']);

										//echo $ndata[0]."<br>";
										//echo $ndata[1]."<br>";

										$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowBa['officeid']."';";
										$resCa = mssql_query($qryCa);
										$rowCa = mssql_fetch_row($resCa);
										$nrowCa= mssql_num_rows($resCa);

										if ($nrowCa==0)
										{
											$ncid=1;
										}
										else
										{
											$ncid=$rowCa[0]+1;
										}

										$qryC	= "INSERT INTO cinfo ";
										$qryC .= "(officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source) ";
										$qryC .= "VALUES (";
										$qryC .= "'".$rowBa['officeid']."','".$rowBa['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
										$qryC .= "'".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."','".$row['bphone']."',";

										if ($row['bphone']=="wk")
										{
											$qryC .= "'','".$row['phone']."',";
										}
										else
										{
											$qryC .= "'".$row['phone']."','',";
										}

										$qryC .= "'".replacequote($row['email'])."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."');";
										$resC	= mssql_query($qryC);
										//echo "P2: ".$qryC."<br>";
										//echo $ndata[1]."<br>";

										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowBa['officeid']."',secid='".$_SESSION['securityid']."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);

										$oid=$rowBa['officeid'];

										if (is_array($sarray))
										{
											$sarray[$oid]=$sarray[$oid]+1;
										}
										else
										{
											$sarray=array($oid=>1);
										}
										$inscnt++;
									}
								}
							}
							elseif ($rowA['pre']==0)
							{
								$qryB	= "SELECT officeid,am,name,active,leadforward FROM offices WHERE zip='".$rowA['zip']."';";
								$resB	= mssql_query($qryB);
								$rowB	= mssql_fetch_array($resB);

								if ($rowB['leadforward']==0)
								{
									if ($rowB['am']!=0 && $rowB['active']==1)
									{
										$ndata=splitonspace($row['lname']);

										//echo $ndata[0]."<br>";
										//echo $ndata[1]."<br>";

										$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowB['officeid']."';";
										$resCa = mssql_query($qryCa);
										$rowCa = mssql_fetch_row($resCa);
										$nrowCa= mssql_num_rows($resCa);

										if ($nrowCa==0)
										{
											$ncid=1;
										}
										else
										{
											$ncid=$rowCa[0]+1;
										}

										$qryC	= "INSERT INTO cinfo ";
										$qryC .= "(officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source) ";
										$qryC .= "VALUES (";
										$qryC .= "'".$rowB['officeid']."','".$rowB['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
										$qryC .= "'".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."','".$row['bphone']."',";

										if ($row['bphone']=="wk")
										{
											$qryC .= "'','".$row['phone']."',";
										}
										else
										{
											$qryC .= "'".$row['phone']."','',";
										}

										$qryC .= "'".replacequote($row['email'])."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."');";
										$resC	= mssql_query($qryC);
										//echo "P3: ".$rowA['pre']."<br>";
										//echo "P3: ".$qryC."<br>";

										//echo $ndata[1]."<br>";

										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowB['officeid']."',secid='".$_SESSION['securityid']."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);

										$oid=$rowB['officeid'];
										if (is_array($sarray))
										{
											$sarray[$oid]=$sarray[$oid]+1;
										}
										else
										{
											$sarray=array($oid=>1);
										}
										$inscnt++;
									}
								}
								else
								{
									$qryBa	= "SELECT officeid,am,name,active FROM offices WHERE officeid='".$rowB['leadforward']."';";
									$resBa	= mssql_query($qryBa);
									$rowBa	= mssql_fetch_array($resBa);

									if ($rowBa['am']!=0 && $rowBa['active']==1)
									{
										$ndata=splitonspace($row['lname']);

										//echo $ndata[0]."<br>";
										//echo $ndata[1]."<br>";

										$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowBa['officeid']."';";
										$resCa = mssql_query($qryCa);
										$rowCa = mssql_fetch_row($resCa);
										$nrowCa= mssql_num_rows($resCa);

										if ($nrowCa==0)
										{
											$ncid=1;
										}
										else
										{
											$ncid=$rowCa[0]+1;
										}

										$qryC	= "INSERT INTO cinfo ";
										$qryC .= "(officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source) ";
										$qryC .= "VALUES (";
										$qryC .= "'".$rowBa['officeid']."','".$rowBa['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
										$qryC .= "'".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."','".$row['bphone']."',";

										if ($row['bphone']=="wk")
										{
											$qryC .= "'','".$row['phone']."',";
										}
										else
										{
											$qryC .= "'".$row['phone']."','',";
										}

										$qryC .= "'".replacequote($row['email'])."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."');";
										$resC	= mssql_query($qryC);
										//echo "P4: ".$qryC."<br>";
										//echo $ndata[1]."<br>";

										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowBa['officeid']."',secid='".$_SESSION['securityid']."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);

										$oid=$rowBa['officeid'];
										if (is_array($sarray))
										{
											$sarray[$oid]=$sarray[$oid]+1;
										}
										else
										{
											$sarray=array($oid=>1);
										}
										$inscnt++;
									}
								}
							}
						}
					}
				}
				//else
				//{
				//   echo "No Area Code Match for: ".$split[0]."<br>";
				//}
			}
			//else
			//{
			//	echo "Wrong String Length for: ".$row['phone']." (".strlen($row['phone']).")<br>";
			//}
		}
	}

	$scnt=array_sum($sarray);

	$rdate = date("m-d-Y (g:i A)", time());

	$qryF	= "SELECT * FROM lead_inc WHERE sorted!='1';";
	$resF	= mssql_query($qryF);
	$nrowF= mssql_num_rows($resF);

	echo "<table class=\"outer\" align=\"center\" width=\"35%\">\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\">\n";

	if ($scnt > 0)
	{
		echo "			<table align=\"center\" width=\"100%\">\n";
		echo "   			<tr>\n";
		echo "      			<td colspan=\"2\" class=\"ltgray_und\" align=\"center\"><b>".$rdate."</b></td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"ltgray_und\"><b>Internet Leads by Office </b></td>\n";
		echo "      			<td class=\"ltgray_und\" align=\"center\"><b>Lead Count</b></td>\n";
		echo "   			</tr>\n";

		foreach ($sarray as $n => $v)
		{
			$qryZ	= "SELECT * FROM offices WHERE officeid='".$n."';";
			$resZ	= mssql_query($qryZ);
			$rowZ	= mssql_fetch_array($resZ);

			if ($v!=0)
			{
				echo "   			<tr>\n";
				echo "      			<td class=\"wh_und\">".$rowZ['name']."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\">".$v."</td>\n";
				echo "   			</tr>\n";
			}
		}

		$gcnt=$scnt+$nrowF;

		echo "   			<tr>\n";
		echo "      			<td class=\"ltgray_und\" align=\"right\"><b>Sorted Leads:</b></td>\n";
		echo "      			<td class=\"ltgray_und\" align=\"center\">".$scnt."</td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"ltgray_und\" align=\"right\"><b>Unsorted Leads:</b></td>\n";
		echo "      			<td class=\"ltgray_und\" align=\"center\">".$nrowF."</td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"ltgray_und\" align=\"right\"><b>Total Internet Leads:</b></td>\n";
		echo "      			<td class=\"ltgray_und\" align=\"center\">".$gcnt."</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
	}
	else
	{
		echo "<b>Nothing was Processed Automatically</b>";
	}

	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function autosort()
{
	$recdate	=time();
	$cdate	=time();
	$qry		= "SELECT * FROM lead_inc WHERE sorted!='1';";
	$res		= mssql_query($qry);
	$nrow		= mssql_num_rows($res);

	//echo "N1: ".$nrow."<br>";

	$qry0	= "SELECT * FROM offices WHERE active=1 AND am!='0';";
	$res0	= mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	$ap=0;
	if ($nrow0 > 0)
	{
		$sarray=array(0=>0);
		while($row0=mssql_fetch_array($res0))
		{
			$sarray[$row0['officeid']]=0;
		}
	}
	else
	{
		echo "<font color=\"red\"><b>ERROR!</b> no active Offices!</font>\n";
	}

	if ($nrow > 0)
	{
		while($row=mssql_fetch_array($res))
		{
			$inscnt	=0;
			$trzip	=trim($row['zip']);
			if (strlen($trzip) == 5)
			{
				//$split=array(0=>substr($row['phone'],0,3),1=>substr($row['phone'],3,3));

				$qryA		= "SELECT * FROM zip_to_zip WHERE czip='".$trzip."';";
				$resA		= mssql_query($qryA);
				$nrowA	= mssql_num_rows($resA);
				//echo "N2: ".$nrowA."<br>";
				//echo $row['phone']."(".$split[0].") ".$nrowA."<br>";

				if ($nrowA > 0)
				{
					//echo $row['phone']."(".$split[0].") ".$nrowA."<br>";
					while($rowA=mssql_fetch_array($resA))
					{
						if ($inscnt==0)
						{
							//if ($rowA['pre']==$split[1])
							//{
								//echo $row['phone']."(".$split[0].") SUB<br>";
								$qryB	= "SELECT officeid,am,name,active,leadforward FROM offices WHERE zip='".$rowA['ozip']."';";
								$resB	= mssql_query($qryB);
								$rowB	= mssql_fetch_array($resB);

								if ($rowB['leadforward']==0)
								{
									if ($rowB['am']!=0 && $rowB['active']==1)
									{
										//echo $row['phone']."(".$split[0].") SUB<br>";
										$ndata=splitonspace($row['lname']);

										//echo $ndata[0]."<br>";
										//echo $ndata[1]."<br>";

										$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowB['officeid']."';";
										$resCa = mssql_query($qryCa);
										$rowCa = mssql_fetch_row($resCa);
										$nrowCa= mssql_num_rows($resCa);

										if ($nrowCa==0)
										{
											$ncid=1;
										}
										else
										{
											$ncid=$rowCa[0]+1;
										}

										$qryC	= "INSERT INTO cinfo ";
										$qryC .= "(officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
										$qryC .= "VALUES (";
										$qryC .= "'".$rowB['officeid']."','".$rowB['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
										$qryC .= "'".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."','".$row['bphone']."',";

										if ($row['bphone']=="wk")
										{
											$qryC .= "'','".$row['phone']."',";
										}
										else
										{
											$qryC .= "'".$row['phone']."','',";
										}

										$qryC .= "'".replacequote($row['email'])."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
										$qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."');";
										$resC	= mssql_query($qryC);
										//echo "P1: ".$rowA['pre']."<br>";
										//echo "I1: ".$qryC."<br>";
										//echo $ndata[1]."<br>";

										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowB['officeid']."',secid='".$_SESSION['securityid']."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);
										//echo "U2: ".$qryD."<br>";
										//echo "-----------";
										$oid=$rowB['officeid'];
										if (is_array($sarray))
										{
											$sarray[$oid]=$sarray[$oid]+1;
										}
										else
										{
											$sarray=array($oid=>1);
										}
										$inscnt++;
									}
								}
								else
								{
									$qryBa	= "SELECT officeid,am,name,active FROM offices WHERE officeid='".$rowB['leadforward']."';";
									$resBa	= mssql_query($qryBa);
									$rowBa	= mssql_fetch_array($resBa);

									if ($rowBa['am']!=0 && $rowBa['active']==1)
									{
										$ndata=splitonspace($row['lname']);

										//echo $ndata[0]."<br>";
										//echo $ndata[1]."<br>";

										$qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowBa['officeid']."';";
										$resCa = mssql_query($qryCa);
										$rowCa = mssql_fetch_row($resCa);
										$nrowCa= mssql_num_rows($resCa);

										if ($nrowCa==0)
										{
											$ncid=1;
										}
										else
										{
											$ncid=$rowCa[0]+1;
										}

										$qryC	= "INSERT INTO cinfo ";
										$qryC .= "(officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
										$qryC .= "VALUES (";
										$qryC .= "'".$rowBa['officeid']."','".$rowBa['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
										$qryC .= "'".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."','".$row['bphone']."',";

										if ($row['bphone']=="wk")
										{
											$qryC .= "'','".$row['phone']."',";
										}
										else
										{
											$qryC .= "'".$row['phone']."','',";
										}

										$qryC .= "'".replacequote($row['email'])."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
										$qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."');";
										$resC	= mssql_query($qryC);
										//echo "I3: ".$qryC."<br>";
										//echo $ndata[1]."<br>";

										$qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowBa['officeid']."',secid='".$_SESSION['securityid']."' WHERE lid='".$row['lid']."';";
										$resD	= mssql_query($qryD);
										//echo "U3: ".$qryD."<br>";

										$oid=$rowBa['officeid'];

										if (is_array($sarray))
										{
											$sarray[$oid]=$sarray[$oid]+1;
										}
										else
										{
											$sarray=array($oid=>1);
										}
										$inscnt++;
									}
								}
							//}
						}
					}
				}
			}
		}
	}

	$scnt=array_sum($sarray);

	$rdate = date("m-d-Y (g:i A)", time());

	$qryF	= "SELECT * FROM lead_inc WHERE sorted!='1';";
	$resF	= mssql_query($qryF);
	$nrowF= mssql_num_rows($resF);

	echo "<table class=\"outer\" align=\"center\" width=\"35%\">\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\">\n";

	if ($scnt > 0)
	{
		echo "			<table align=\"center\" width=\"100%\">\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Lead Sort: Result</b></td>\n";
		echo "      			<td class=\"ltgray_und\" align=\"right\"><b>".$rdate."</b></td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"ltgray_und\"><b>Office </b></td>\n";
		echo "      			<td class=\"ltgray_und\" align=\"center\"><b>Lead Count</b></td>\n";
		echo "   			</tr>\n";

		foreach ($sarray as $n => $v)
		{
			$qryZ	= "SELECT * FROM offices WHERE officeid='".$n."';";
			$resZ	= mssql_query($qryZ);
			$rowZ	= mssql_fetch_array($resZ);

			if ($v!=0)
			{
				echo "   			<tr>\n";
				echo "      			<td class=\"wh_und\">".$rowZ['name']."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\">".$v."</td>\n";
				echo "   			</tr>\n";
			}
		}

		$gcnt=$scnt+$nrowF;

		echo "   			<tr>\n";
		echo "      			<td class=\"ltgray_und\" align=\"right\"><b>Sorted Leads:</b></td>\n";
		echo "      			<td class=\"ltgray_und\" align=\"center\">".$scnt."</td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"ltgray_und\" align=\"right\"><b>Unsorted Leads:</b></td>\n";
		echo "      			<td class=\"ltgray_und\" align=\"center\">".$nrowF."</td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"ltgray_und\" align=\"right\"><b>Total Internet Leads:</b></td>\n";
		echo "      			<td class=\"ltgray_und\" align=\"center\">".$gcnt."</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
	}
	else
	{
		echo "<b>Nothing was Processed Automatically</b>";
	}

	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

function cinfo_export()
{
	header("location:/pb_export/createcsv.php");
}

function listleads()
{
	error_reporting(E_ALL);
	$officeid	=$_SESSION['officeid'];
	$securityid	=$_SESSION['securityid'];
	$acclist	=explode(",",$_SESSION['aid']);
	$unxdt		=time();

	//echo "LISTLEADS<BR>";

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
		$order="custid";
		$dir="ASC";
	}
	
	if (isset($_POST['dtype']))
	{
		$dtype=$_POST['dtype'];
	}
	else
	{
		$dtype="added";
	}

	if (isset($_POST['showdupe']) && $_POST['showdupe']==1)
	{
		$dupe="";
	}
	else
	{
		$dupe="AND dupe!=1 ";
	}

	if (isset($_POST['showhold']) && $_POST['showhold']==1)
	{
		$hold="AND hold=1 ";
	}
	else
	{
		$hold="";
	}
	
	if (isset($_POST['showreno']) && $_POST['showreno']==1)
	{
		$renov="AND renov=1 ";
	}
	else
	{
		$renov="AND renov!=1 ";
	}

	if (isset($_SESSION['tqry']))
	{
		//echo "ZERO<br>";
		$qry=$_SESSION['tqry'];
	}
	else
	{
		if ($_POST['call']=="search_results" && $_POST['subq']=="sstring")
		{
			if (empty($_POST['d1']) && isset($_POST['d2']) )
			{
				if (empty($_POST['ssearch']))
				{
					echo "<b><font color=\"red\">Error!</font><br><br>Search String or Date Parameter required</b>";
					exit;
				}
			}

			//echo $_POST['subq']."<br>";
			// String Lead List Query
			if (isset($_POST['d1']) && !empty($_POST['d1']) && isset($_POST['d2']) && !empty($_POST['d2']))
			{
				$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold." AND ".$_POST['field']." LIKE '".$_POST['ssearch']."%' AND ".$dtype." BETWEEN '".$_POST['d1']."' AND '".$_POST['d2']." 23:59' ORDER BY ".$order." ".$dir.";";
			}
			else
			{
				$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold." AND ".$_POST['field']." LIKE '".$_POST['ssearch']."%' ORDER BY ".$order." ".$dir.";";
			}
		}
		elseif ($_POST['call']=="search_results" && $_POST['subq']=="resstatus")
		{
			//echo $_POST['subq']."<br>";
			// Lead List Result Query
			if (isset($_POST['d5']) && !empty($_POST['d5']) && isset($_POST['d6']) && !empty($_POST['d6']))
			{
				$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold." AND stage='".$_POST['statusid']."' AND ".$dtype." BETWEEN '".$_POST['d5']."' AND '".$_POST['d6']." 23:59' ORDER BY ".$order." ".$dir.";";
			}
			else
			{
				$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold." AND stage='".$_POST['statusid']."' ORDER BY ".$order." ".$dir.";";
			}
		}
		elseif ($_POST['call']=="search_results" && $_POST['subq']=="srcstatus")
		{
			//echo $_POST['subq']."<br>";
			// Lead List Source Query
			if (isset($_POST['d3']) && !empty($_POST['d3']) && isset($_POST['d4']) && !empty($_POST['d4']))
			{
				$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold." AND source='".$_POST['statusid']."' AND ".$dtype." BETWEEN '".$_POST['d3']."' AND '".$_POST['d4']." 23:59' ORDER BY ".$order." ".$dir.";";
			}
			else
			{
				$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold." AND source='".$_POST['statusid']."' ORDER BY ".$order." ".$dir.";";
			}
		}
		elseif ($_POST['call']=="search_results" && $_POST['subq']=="salesman")
		{
			//echo $_POST['subq']."<br>";
			// Status Lead List Query
			if (isset($_POST['d7']) && !empty($_POST['d7']) && isset($_POST['d8']) && !empty($_POST['d8']))
			{
				$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold." AND securityid='".$_POST['assigned']."' AND ".$dtype." BETWEEN '".$_POST['d7']."' AND '".$_POST['d8']." 23:59' ORDER BY ".$order." ".$dir.";";
			}
			else
			{
				$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold." AND securityid='".$_POST['assigned']."' ORDER BY ".$order." ".$dir.";";
			}
		}
		else
		{
			//echo $_POST['subq']."<br>";
			// Default Lead List Query
			$qry   = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' ".$dupe."".$hold." ORDER BY ".$order." ".$dir.";";
		}
	}
	
	/*
	if (isset($_SESSION['tqry']) && $qry==$_SESSION['tqry'])
	{
		//echo "ZERO<br>";
		$qry=$_SESSION['tqry'];
	}
	*/

	//echo $qry."<br>";
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//echo "BEFORE: ".$_SESSION['tqry']."<br>";

	$_SESSION['tqry']=$qry;

	//echo "AFTER: ".$_SESSION['tqry']."<br>";

	//echo $nrows."<br>";
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
		echo "<table class=\"outer\" align=\"center\" width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                     <tr>\n";
		echo "               			<td align=\"left\" class=\"ltgray_und\">\n";
		echo "								</td>\n";
		echo "                        <td align=\"left\" class=\"ltgray_und\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "                        <td align=\"right\" class=\"ltgray_und\"><b>Lead</b> Color Codes:</td>\n";
		echo "                        <td align=\"center\" class=\"wh_und\" width=\"75\"><b>Normal</b></td>\n";
		echo "                        <td align=\"center\" class=\"grn_und\" width=\"75\"><b>Appt Today</b></td>\n";
		echo "                        <td align=\"center\" class=\"magenta_und\" width=\"75\"><b>Call Back</b></td>\n";
		echo "                        <td align=\"center\" class=\"yel_und\" width=\"75\"><b>Aged 7 Days</b></td>\n";

		if ($_SESSION['llev'] >= 5)
		{
			echo "                        <td align=\"center\" class=\"red_und\" width=\"75\"><b>Duplicate</b></td>\n";
		}

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
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Lead ID</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Last Name</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\">First Name</td>\n";
		echo "                     	<td class=\"ltgray_und\" align=\"left\"><b>Phone</b></td>\n";
		//echo "                     	<td class=\"ltgray_und\" align=\"left\"><b>Email</b></td>\n";
		echo "                     	<td class=\"ltgray_und\" align=\"left\"><b>Site City</b></td>\n";
		echo "                     	<td class=\"ltgray_und\" align=\"left\"><b>Site Zip</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Assigned to</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Origin Date</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Last Update</b></td>\n";
		echo "                  	   <td class=\"ltgray_und\" align=\"left\"><b>Appointment</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Source</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Result</b></td>\n";
		echo "            	         <td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "            	         <td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "            	         <td class=\"ltgray_und\" align=\"right\"></td>\n";
		echo "                  	</tr>\n";

		$age30=2592000; //30 Days
		$age15=1296000; //15 Days
		$age07=604800; // 7 Days
		$age01=86400; // 7 Days
		$ts_tdate=getdate();
		$lcnt=0;
		$altdtext="";
		while($row=mssql_fetch_array($res))
		{
			$nrowsA =0;
			$adate ="";
			if (strlen($row['caddr1']) >= 3)
			{
				$altdtext=$row['caddr1'].", ".$row['ccity'].", ".$row['cstate'].", ".$row['czip1'];
			}
			elseif (strlen($row['saddr1']) >= 3)
			{
				$altdtext=$row['saddr1'].", ".$row['scity'].", ".$row['sstate'].", ".$row['szip1'];
			}

			$qryC = "SELECT fname,lname,securityid,sidm,slevel FROM security WHERE securityid='".$row['securityid']."'";
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

			$qryD = "SELECT estid,cid,renov FROM est WHERE officeid='".$_SESSION['officeid']."' AND cid='".$row['custid']."';";
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_array($resD);
			$nrowD= mssql_num_rows($resD);

			$qryE = "SELECT statusid,name FROM leadstatuscodes WHERE statusid='".$row['stage']."';";
			$resE = mssql_query($qryE);
			$rowE = mssql_fetch_array($resE);

			$qryF = "SELECT statusid,name FROM leadstatuscodes WHERE statusid='".$row['source']."';";
			$resF = mssql_query($qryF);
			$rowF = mssql_fetch_array($resF);

			$uid  =md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];

			if ($row['jobid']=="0" && $row['njobid']=="0")
			{
				if (in_array($row['securityid'],$acclist)||$_SESSION['llev'] >= 5)
				{

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

					if (!empty($row['updated'])||$row['updated']!="")
					{
						$ts_udate=strtotime($row['updated']);
						$udate = date("m-d-Y", strtotime($row['updated']));
					}
					else
					{
						$ts_udate=0;
						$udate = "";
					}

					if ($row['appt_mo']!=0)
					{
						if ($row['appt_pa']==1)
						{
							$pa="AM";
						}
						else
						{
							$pa="PM";
						}
						$adate = str_pad($row['appt_mo'],2,"0",STR_PAD_LEFT)."-".str_pad($row['appt_da'],2,"0",STR_PAD_LEFT)."-".$row['appt_yr']." (".$row['appt_hr'].":".str_pad($row['appt_mn'],2,"0",STR_PAD_LEFT)." ".$pa.")";
					}
					else
					{
					}

					$udiff_date=$ts_tdate[0]-$ts_udate;
					$odiff_date=$ts_tdate[0]-$ts_odate;

					$hdate = str_pad($row['hold_mo'],2,"0",STR_PAD_LEFT)."/".str_pad($row['hold_da'],2,"0",STR_PAD_LEFT)."/".$row['hold_yr'];
					$ts_hdate=strtotime($hdate);
					$hdiff_date=$ts_hdate-$ts_tdate[0];

					if ($row['dupe']==1)
					{
						$tbg="red_und";
					}
					elseif ($row['hold']==1)
					{
						if ($row['hold_mo']!=0 && $row['hold_da']!=0 && $row['hold_yr']!=0)
						{
							$tbg="magenta_und";
						}
						else
						{
							$tbg="wh_und";
						}
					}
					else
					{
						if ($ts_udate == 0)
						{
							if ($odiff_date > $age07)
							{
								$tbg="yel_und";
							}
							else
							{
								$tbg="wh_und";
							}
						}
						elseif ($udiff_date > $age07)
						{
							$tbg="yel_und";
						}
						else
						{
							if ($row['appt_mo']==date("n") && $row['appt_da']==date("j") && $row['appt_yr']==date("Y"))
							{
								$tbg="grn_und";
							}
							else
							{
								$tbg="wh_und";
							}
						}
					}

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

					// Display Lead Trigger
					if (!empty($_POST['d1']) && !empty($_POST['d2']))
					{
						// If a Date Range search is used
						$show=1;
					}
					elseif (!empty($_POST['d3']) && !empty($_POST['d4']))
					{
						// If a Date Range search is used
						$show=1;
					}
					elseif (!empty($_POST['d5']) && !empty($_POST['d6']))
					{
						// If a Date Range search is used
						$show=1;
					}
					elseif (!empty($_POST['d7']) && !empty($_POST['d8']))
					{
						// If a Date Range search is used
						$show=1;
					}
					elseif (!empty($_SESSION['d1']) && !empty($_SESSION['d2']))
					{
						// If a Date Range search is used
						$show=1;
					}
					elseif (!empty($_SESSION['d3']) && !empty($_SESSION['d4']))
					{
						// If a Date Range search is used
						$show=1;
					}
					elseif (!empty($_SESSION['d5']) && !empty($_SESSION['d6']))
					{
						// If a Date Range search is used
						$show=1;
					}
					elseif (!empty($_SESSION['d7']) && !empty($_SESSION['d8']))
					{
						// If a Date Range search is used
						$show=1;
					}
					elseif ($row['hold']==1)
					{
						// For any CallBacks
						if ($hdiff_date < $age15 && $ts_hdate >= ($ts_tdate[0]-$age01))
						{
							$show=1;
						}
						elseif (isset($_POST['showhold']) && $_POST['showhold']==1)
						{
							$show=1;
						}
						else
						{
							$show=0;
						}
					}
					elseif ($odiff_date > $age30 && $udiff_date > $age30 && !isset($_POST['showaged']))
					{
						$show=0;
					}
					elseif ($row['estid']!=0 && $row['jobid']=="0" && $row['njobid']=="0")
					{
						$show=1;
					}
					else
					{
						$show=1;
					}	

					//$show=1;
					if ($show!=0)
					{
						$lcnt++;
						echo "                  <tr>\n";
						echo "                     <td class=\"".$tbg."\" align=\"center\">".$lcnt."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"center\"><b>".$row['custid']."</b></td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$row['clname']."</b></td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$row['cfname']."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$cphone."</b></td>\n";
						//echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$row['cemail']."</b></td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$row['scity']."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$row['szip1']."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\"><font class=\"".$fstyle."\">".$rowC['lname'].", ".$rowC['fname']."</font></td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$odate."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$udate."</td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$adate."</td>\n";

						if ($row['source']==0)
						{
							//echo "                     <td class=\"".$tbg."\" align=\"left\">Internet</b></td>\n";
							echo "                     <td class=\"".$tbg."\" align=\"left\">bluehaven.com</b></td>\n";
						}
						elseif ($row['source'] >= 1)
						{
							echo "                     <td class=\"".$tbg."\" align=\"left\">".$rowF['name']."</td>\n";
						}

						if ($rowE['statusid']==6)
						{
							echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$rowE['name']."</b></td>\n";
						}
						else
						{
							echo "                     <td class=\"".$tbg."\" align=\"left\">".$rowE['name']."</td>\n";
						}
						
						if ($row['estid']!=0 && $row['jobid']=="0")
						{
							echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
							echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
							echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
							echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
							echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$row['estid']."\">\n";
							echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Estimate\">\n";
							echo "                     </td>\n";
							echo "                        </form>\n";
						}
						else
						{
							echo "                     <td class=\"".$tbg."\" align=\"center\"></td>\n";
						}

						echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
						echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
						echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
						echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
						echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
						echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
						echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Lead\">\n";
						echo "                     </td>\n";
						echo "                        </form>\n";
						echo "                     <td class=\"".$tbg."\" align=\"center\">".$lcnt."</td>\n";
						echo "                  </tr>\n";
						
						if (isset($_POST['incaddr']) && $_POST['incaddr']==1)
						{
							echo "                  </tr>\n";
							echo "                     <td class=\"".$tbg."\" align=\"right\" colspan=\"2\"><b>Address:</b></td>\n";
							echo "                     <td class=\"".$tbg."\" align=\"left\">".$altdtext."</td>\n";
							echo "                     <td class=\"".$tbg."\" align=\"left\" colspan=\"14\"><b>Email:</b> ".$row['cemail']."</td>\n";
							echo "                  </tr>\n";
						}
					}
				}
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

function apptleads_mo()
{
	$age30=2592000; //30 Days
	$age15=1296000; //15 Days
	$age07=604800; // 7 Days
	$curr_date=getdate();
	$acclist=explode(",",$_SESSION['aid']);

	if (empty($_POST['appt_mo']))
	{
		$mdate=$curr_date['mon'];
		$ndate=$curr_date['month'];
	}
	else
	{
		$mdate=$_POST['appt_mo'];
		$ndate=date("F", mktime(0, 0, 0, $_POST['appt_mo'], 1, $curr_date['year']));
	}

	if (empty($_POST['appt_da']))
	{
		$ddate=$curr_date['mday'];
	}
	else
	{
		$ddate=$_POST['appt_da'];
	}

	if (empty($_POST['appt_yr']))
	{
		$ydate=$curr_date['year'];
	}
	else
	{
		$ydate=$_POST['appt_yr'];
	}

	$pstyr=2004;
	$futyr=$curr_date['year']+1;

	//echo "<pre>";
	//print_r($curr_date)."<br>";
	//echo "</pre>";
	//echo $curr_date[0]."<br>";
	///echo $pstyr."<br>";
	//echo $futyr."<br>";

	$qry   = "select * from cinfo where officeid='".$_SESSION['officeid']."' and appt_mo='".$mdate."' and appt_da!='0' and appt_yr='".$ydate."' and dupe!=1 and hold!=1 and estid=0 order by appt_da DESC;";
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//echo $qry;

	if ($nrows < 1)
	{
		echo "<table align=\"center\" width=\"60%\">\n";
		echo "   <tr>\n";
		echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "      <td>\n";
		echo "         <b>No Appointments for ".$ndate.", ".$curr_date['year']."</b>\n";
		echo "      </td>\n";
		echo "   </form>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                     <tr>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"appts\">\n";
		echo "               			<td align=\"left\" class=\"ltgray_und\">All Appointments for \n";
		echo "               				<select name=\"appt_mo\">\n";

		for ($x = 1; $x <= 12; $x++)
		{
			$m_name=date("F", mktime(0, 0, 0, $x, 1, $curr_date['year']));
			if ($x == $mdate)
			{
				echo "               					<option value=\"".$x."\" SELECTED>".$m_name."</option>\n";
			}
			else
			{
				echo "               					<option value=\"".$x."\">".$m_name."</option>\n";
			}
		}

		echo "               				</select>\n";
		echo "               				<select name=\"appt_yr\">\n";

		for ($x = $pstyr; $x <= $futyr; $x++)
		{
			//$m_name=date("F", mktime(0, 0, 0, $x, 1, $curr_date['year']));
			if ($x == $ydate)
			{
				echo "               					<option value=\"".$x."\" SELECTED>".$x."</option>\n";
			}
			else
			{
				echo "               					<option value=\"".$x."\">".$x."</option>\n";
			}
		}

		echo "               				</select>\n";
		//echo $curr_date['year'];
		//echo $curr_date['year'];
		echo "									<input class=\"buttondkgry\" type=\"submit\" value=\"Search\">\n";
		echo "								</td>\n";
		echo "								</form>\n";
		echo "                        <td align=\"left\" class=\"ltgray_und\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "                        <td align=\"right\" class=\"ltgray_und\"><b>Lead</b> Color Codes:</td>\n";
		echo "                        <td align=\"center\" class=\"wh_und\" width=\"75\"><b>Normal</b></td>\n";
		echo "                        <td align=\"center\" class=\"grn_und\" width=\"75\"><b>Appt Today</b></td>\n";
		echo "                        <td align=\"center\" class=\"yel_und\" width=\"75\"><b>Aged 7 Days</b></td>\n";
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
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Lead ID</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Last Name</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>First Name</b></td>\n";
		echo "                     	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Phone</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Assigned</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Origin Date</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Last Update</b></td>\n";
		echo "                     	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Appointment</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Source</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Status</b></td>\n";
		echo "                     	<td class=\"ltgray_und\" align=\"right\">\n";
		echo "                     	</td>\n";
		echo "                  	</tr>\n";

		$ts_tdate=getdate();
		while($row=mssql_fetch_array($res))
		{
			$nrowsA =0;

			$qryC = "SELECT fname,lname,securityid,sidm FROM security WHERE securityid='".$row['securityid']."'";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			//$idarray=accessidlist(1,5,$rowC['sidm'],$rowC['securityid']);

			$qryD = "SELECT estid,cid FROM est WHERE officeid='".$_SESSION['officeid']."' AND cid='".$row['custid']."';";
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_array($resD);
			$nrowD= mssql_num_rows($resD);

			$qryE = "SELECT statusid,name FROM leadstatuscodes WHERE statusid='".$row['stage']."' and active=1;";
			$resE = mssql_query($qryE);
			$rowE = mssql_fetch_array($resE);
			
			$qryF = "SELECT statusid,name FROM leadstatuscodes WHERE statusid='".$row['source']."';";
			$resF = mssql_query($qryF);
			$rowF = mssql_fetch_array($resF);

			$uid  =md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];

			if ($nrowD==0)
			{
				if (in_array($row['securityid'],$acclist)||$_SESSION['llev'] >= 5)
				//if (in_array($_SESSION['securityid'],$idarray) || $_SESSION['llev'] >= 5)
				//if ($rowC['securityid']==$_SESSION['securityid'] || $rowC['sidm']==$_SESSION['securityid'] || $_SESSION['llev'] >=5)
				{
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

					if (!empty($row['updated'])||$row['updated']!="")
					{
						$ts_udate=strtotime($row['updated']);
						$udate = date("m-d-Y", strtotime($row['updated']));
					}
					else
					{
						$ts_udate=0;
						$udate = "";
					}

					if ($row['appt_mo']!=0)
					{
						if ($row['appt_pa']==1)
						{
							$pa="AM";
						}
						else
						{
							$pa="PM";
						}
						$adate = str_pad($row['appt_mo'],2,"0",STR_PAD_LEFT)."-".str_pad($row['appt_da'],2,"0",STR_PAD_LEFT)."-".$row['appt_yr']." (".$row['appt_hr'].":".str_pad($row['appt_mn'],2,"0",STR_PAD_LEFT)." ".$pa.")";
					}
					else
					{
						$adate = "";
					}

					$udiff_date=$ts_tdate[0]-$ts_udate;
					$odiff_date=$ts_tdate[0]-$ts_odate;

					if ($row['dupe']==1)
					{
						$tbg="red_und";
					}
					elseif ($row['hold']==1)
					{
						$tbg="oran_und";
					}
					else
					{
						if ($ts_udate == 0)
						{
							if ($odiff_date > $age07)
							{
								$tbg="yel_und";
							}
							else
							{
								$tbg="wh_und";
							}
						}
						elseif ($udiff_date > $age07)
						{
							$tbg="yel_und";
						}
						else
						{
							if ($row['appt_mo']==date("n") && $row['appt_da']==date("j") && $row['appt_yr']==date("Y"))
							{
								$tbg="grn_und";
							}
							else
							{
								$tbg="wh_und";
							}
						}
					}

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


					//if ($odiff_date > $age30 && $udiff_date > $age30 && !isset($_POST['showaged']))
					//if ($udiff_date > $age30 && !isset($_POST['showaged']))
					//{
					//$tbg="yel_und";
					//}
					//else
					//{
					echo "                  <tr>\n";
					//echo "                     <td class=\"".$tbg."\" align=\"left\">[".$row['cid']."] (".$ts_odate." / ".$ts_udate.") <b>".$row['clname']."</b></td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"center\"><b>".$row['custid']."</b></td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$row['clname']."</b></td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"left\">".$row['cfname']."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$cphone."</b></td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"left\">".$rowC['lname'].", ".$rowC['fname']."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"left\">".$odate."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"left\">".$udate."</td>\n";
					echo "                     <td class=\"".$tbg."\" align=\"left\">".$adate."</td>\n";

/*
					if ($row['source']==0)
					{
						//echo "                     <td class=\"".$tbg."\" align=\"left\">Internet</b></td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">bluehaven.com</b></td>\n";
					}
					elseif ($row['source']==1)
					{
						echo "                     <td class=\"".$tbg."\" align=\"left\">Manual</td>\n";
					}
					else
					{
						echo "                     <td class=\"".$tbg."\" align=\"left\"></td>\n";
					}
*/

					if ($row['source']==0)
					{
						//echo "                     <td class=\"".$tbg."\" align=\"left\">Internet</b></td>\n";
						echo "                     <td class=\"".$tbg."\" align=\"left\">bluehaven.com</b></td>\n";
					}
					elseif ($row['source'] >= 1)
					{
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$rowF['name']."</td>\n";
					}

					if ($rowE['statusid']==6)
					{
						echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$rowE['name']."</b></td>\n";
					}
					else
					{
						echo "                     <td class=\"".$tbg."\" align=\"left\">".$rowE['name']."</td>\n";
					}
					/*
					echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "                     <td class=\"".$tbg."\" align=\"center\">\n";

					if ($_SESSION['llev'] >=6)
					{
					echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
					echo "                           <input type=\"hidden\" name=\"call\" value=\"delete\">\n";
					echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
					echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete\">\n";
					}

					echo "                     </td>\n";
					echo "                        </form>\n";
					*/
					echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
					echo "                     <td class=\"".$tbg."\" align=\"right\">\n";
					echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
					echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
					echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
					echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
					echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Lead\">\n";
					echo "                     </td>\n";
					echo "                        </form>\n";
					echo "                  </tr>\n";
					//}
				}
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

function lform_view()
{
	if ($_POST['type']=="proc"||$_GET['type']=="proc")
	{
		$qryF = "SELECT * FROM lead_inc WHERE lid='".$_POST['lid']."';";
		$resF = mssql_query($qryF);
		$rowF = mssql_fetch_array($resF);

		echo "<table width=\"85%\" align=\"center\">\n";
		echo "   <tr>\n";
		echo "      <td>\n";
		echo "		<table class=\"outer\" width=\"100%\" align=\"center\" border=0>\n";
		echo "   	<tr>\n";
		echo "      <td>\n";
		echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"edit\">\n";
		echo "         <input type=\"hidden\" name=\"lid\" value=\"".$rowF['lid']."\">\n";
		echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "         <table border=\"0\" width=\"100%\">\n";
		echo "         	<tr>\n";
		echo "            	<td bgcolor=\"#d3d3d3\">\n";
		echo "               	<table border=\"0\" width=\"100%\">\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"left\"><b>Preproccessed Lead Information:</b><font color=\"blue\"></font></td>\n";
		echo "                        <td valign=\"bottom\" align=\"right\"></td>\n";
		echo "                    	</tr>\n";
		echo "                     <tr>\n";
		echo "                        <td colspan=\"2\" valign=\"bottom\">\n";
		echo "                           <table class=\"outer\" border=\"0\" width=\"100%\">\n";
		echo "                           	<tr>\n";
		echo "                              	<td align=\"right\" valign=\"bottom\"><b>Date Submitted:</b>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\">".$rowF['submitted']."</td>\n";
		echo "                                 <td align=\"right\" valign=\"bottom\"><b>Date Received: </b></td>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\">".$rowF['added']."</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td valign=\"top\" align=\"left\">\n";
		echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"125\">\n";
		echo "										<tr>\n";
		echo "											<td colspan=\"2\" valign=\"bottom\" NOWRAP><b>Customer:</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\" NOWRAP>Name</td>\n";
		echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"clname\" value=\"".$rowF['lname']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\" NOWRAP>Phone</td>\n";
		echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\" value=\"".$rowF['phone']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\" NOWRAP>Best Phone</td>\n";
		echo "											<td align=\"left\" NOWRAP>\n";
		echo "												<select name=\"cconph\">\n";
		if ($rowF['bphone']=="hm")
		{
			echo "													<option value=\"hm\" SELECTED>Home</option>\n";
			echo "													<option value=\"wk\">Work</option>\n";
			echo "													<option value=\"ce\">Cell</option>\n";
		}
		elseif ($rowF['bphone']=="wk")
		{
			echo "													<option value=\"hm\">Home</option>\n";
			echo "													<option value=\"wk\" SELECTED>Work</option>\n";
			echo "													<option value=\"ce\">Cell</option>\n";
		}

		echo "												</select>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\" NOWRAP>Email</td>\n";
		echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" name=\"cemail\" size=\"30\" value=\"".$rowF['email']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\" NOWRAP>Contact Time</td>\n";
		echo "											<td align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"ccontime\" value=\"".$rowF['contime']."\"></td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "								<td valign=\"top\" align=\"left\">\n";
		echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"125\">\n";
		echo "										<tr>\n";
		echo "											<td colspan=\"2\" valign=\"top\" NOWRAP><b>Site Address:</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "												<td align=\"right\" NOWRAP>Street:</td>\n";
		echo "												<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\" value=\"".$rowF['addr']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "												<td align=\"right\" NOWRAP>City:</td>\n";
		echo "												<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['city']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['state']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "												<td align=\"right\" NOWRAP>Zip:</td>\n";
		echo "												<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['zip']."\"></td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td colspan=\"2\" align=\"left\" valign=\"top\">\n";
		echo "									<table class=\"outer\" width=\"100%\" height=\"75\">\n";
		echo "										<tr>\n";
		echo "											<td valign=\"top\"><b>Comments/Directions:</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr valign=\"top\">\n";
		echo "											<td><textarea name=\"comments\" cols=\"75\" rows=\"10\">".$rowF['comments']."</textarea></td>\n";
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
		echo "	</table>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<table border=0>\n";
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Save Lead\" DISABLED>\n";
		echo "				</td>\n";
		echo "			</form>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
		echo "	</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	elseif ($_POST['type']=="unproc"||$_GET['type']=="unproc")
	{
		$qryF = "SELECT * FROM lead_inc_bucket WHERE id='".$_POST['lid']."';";
		$resF = mssql_query($qryF);
		$rowF = mssql_fetch_array($resF);

		echo "<table width=\"85%\" align=\"center\">\n";
		echo "   <tr>\n";
		echo "      <td>\n";
		echo "		<table class=\"outer\" width=\"100%\" align=\"center\" border=0>\n";
		echo "   	<tr>\n";
		echo "      <td>\n";
		echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"edit\">\n";
		echo "         <input type=\"hidden\" name=\"lid\" value=\"".$rowF['id']."\">\n";
		echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "         <table border=\"0\" width=\"100%\">\n";
		echo "         	<tr>\n";
		echo "            	<td bgcolor=\"#d3d3d3\">\n";
		echo "               	<table border=\"0\" width=\"100%\">\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"left\"><b>Unprocessed Lead Information:</b><font color=\"blue\"></font></td>\n";
		echo "                        <td valign=\"bottom\" align=\"right\"></td>\n";
		echo "                    	</tr>\n";
		echo "                     <tr>\n";
		echo "                        <td colspan=\"2\" valign=\"bottom\">\n";
		echo "                           <table class=\"outer\" border=\"0\" width=\"100%\">\n";
		echo "                           	<tr>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\"><b>Date Received: </b></td>\n";
		echo "                                 <td align=\"left\" valign=\"bottom\">".$rowF['added']."</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td colspan=\"2\" align=\"left\" valign=\"top\">\n";
		echo "									<table class=\"outer\" width=\"100%\" height=\"75\">\n";
		echo "										<tr>\n";
		echo "											<td valign=\"top\">".$rowF['subject']."</td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td width=\"400px\" valign=\"top\"><pre>".$rowF['body']."</pre></td>\n";
		//echo "											<td width=\"400px\" valign=\"top\">".$rowF['body']."</td>\n";
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
		echo "	</table>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\" valign=\"top\">\n";
		echo "		<table border=0>\n";
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Save Lead\" DISABLED>\n";
		echo "				</td>\n";
		echo "			</form>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
		echo "	</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function cform()
{
	$officeid 	=$_SESSION['officeid'];
	$dates	=dateformat();
	$uid		=md5(session_id().time()).".".$_SESSION['securityid'];
	$acclist	=explode(",",$_SESSION['aid']);
	$curryr	=date("Y");
	$futyr 	=$curryr+1;

	if ($_SESSION['llev'] >= 7)
	{
		$qryA = "SELECT officeid,name,stax FROM offices ORDER BY name ASC;";
	}
	else
	{
		$qryA = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	}
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
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

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=2 AND statusid!=0 AND access!=9 ORDER by name ASC;";
	$resG = mssql_query($qryG);

	echo "<table width=\"85%\" align=\"center\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "		<table width=\"100%\" align=\"center\">\n";
	echo "			<tr>\n";
	echo "				<td>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"add\">\n";
	//echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "         <input type=\"hidden\" name=\"recdate\" value=\"".$dates[1]."\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "         <input type=\"hidden\" name=\"comments\" value=\"\">\n";
	echo "					<table border=\"0\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "								<table border=\"0\" width=\"100%\">\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"left\" valign=\"bottom\"><b><b>Lead Entry:</b><font color=\"blue\"> * Required Entry</font></td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	
	//echo "										<td valign=\"bottom\" align=\"right\"></td>\n";
	//echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\" valign=\"bottom\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Date:</b>\n";
	echo "													<td class=\"gray\" align=\"left\" valign=\"bottom\">".$dates[0]."</td>\n";
	echo "													<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Office: </b></td>\n";
	echo "													<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";

	if ($_SESSION['llev'] >= 7)
	{
		echo "													<select name=\"site\">\n";
		while ($rowA = mssql_fetch_row($resA))
		{
			if ($_SESSION['officeid']==$rowA[0])
			{
				echo "												<option value=\"".$rowA[0]."\" SELECTED>".$rowA[1]."</option>\n";
			}
			else
			{
				echo "												<option value=\"".$rowA[0]."\">".$rowA[1]."</option>\n";
			}
		}
		echo "													</select>\n";
	}
	else
	{

		$rowA = mssql_fetch_row($resA);
		//print_r($rowA)."<BR>";
		echo "                                 	".$rowA[1]."<input type=\"hidden\" name=\"officeid\" value=\"".$rowA[0]."\">\n";
	}

	echo "													</td>\n";
	echo "													<td class=\"gray\" align=\"right\" valign=\"bottom\"></td>\n";
	echo "													<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Salesrep:</b> \n";

	if ($_SESSION['llev'] >= 4)
	{
		echo "														<select name=\"estorig\">\n";
		while ($rowB = mssql_fetch_row($resB))
		{
			if (in_array($rowB[0],$acclist))
			{
				$slev=explode(",",$rowB[3]);
				if ($slev[6]!=0)
				{
					if ($_SESSION['securityid']==$rowB[0])
					{
						echo "													<option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
					}
					else
					{
						echo "													<option value=\"".$rowB[0]."\">".$rowB[1]." ".$rowB[2]."</option>\n";
					}
				}
			}
		}
		echo "														</select>\n";

	}
	else
	{
		echo "                                 ".$_SESSION['fname']." ".$_SESSION['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$_SESSION['securityid']."\">\n";
	}

	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" colspan=\"2\" valign=\"bottom\" NOWRAP><b>Customer:</b></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP><font color=\"blue\">* First Name</font></td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"cfname\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP><font color=\"blue\">* Last Name</font></td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"clname\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP><font color=\"blue\">* Home Phone</font></td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>Work Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>Cell Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>Fax</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>Best Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP>\n";
	echo "														<select name=\"cconph\">\n";
	echo "															<option value=\"hm\">Home</option>\n";
	echo "															<option value=\"wk\">Work</option>\n";
	echo "															<option value=\"ce\">Cell</option>\n";
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP><font color=\"blue\">* E-Mail</font></td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" name=\"cemail\" size=\"30\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>Contact Time</td>\n";
	echo "													<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"ccontime\"></td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Current Address:</b></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP><font color=\"blue\">* Street</font></td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"caddr1\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP><font color=\"blue\">* City</font></td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"ccity\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP><font color=\"blue\">* Zip</font></td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>Cnty/Twnshp</td>\n";
	echo "													<td class=\"gray\" NOWRAP>\n";

	if ($rowC[0]==0)
	{
		echo "												<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"ccounty\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "												<select name=\"ccounty\">\n";
		while ($rowD = mssql_fetch_row($resD))
		{
			echo "												<option value=\"".$rowD[0]."\">".$rowD[2]."</option>\n";
		}
		echo "												</select>\n";
	}
	else
	{
		echo "											<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"ccounty\">\n";
	}

	echo "												Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"cmap\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>Street:</td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>City:</td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>Zip:</td>\n";
	echo "													<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\" NOWRAP>Cnty/Twnshp:</td>\n";
	echo "													<td class=\"gray\" NOWRAP>\n";

	if ($rowC[0]==0)
	{
		echo "													<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "													<select name=\"scounty\">\n";
		while ($rowE = mssql_fetch_row($resE))
		{
			echo "														<option value=\"".$rowE[0]."\">".$rowE[2]."</option>\n";
		}
		echo "														</select>\n";
	}
	else
	{
		echo "													<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\">\n";
	}

	echo "											Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"smap\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"left\" valign=\"top\">\n";
	echo "											<table border=0 class=\"outer\" width=\"100%\" height=\"100\">\n";
	echo "                           			<tr>\n";
	echo "													<td class=\"gray\" align=\"left\" valign=\"top\"><b>Appointment / Source</b></td>\n";
	echo "                           			</tr>\n";
	echo "                     					<tr>\n";
	echo "                        					<td class=\"gray\" valign=\"top\">\n";
	echo "                           					<table border=\"0\" width=\"100%\">\n";
	echo "															<tr>\n";
	echo "																<td align=\"right\" valign=\"bottom\"><b>Date:</b></td>\n";
	echo "																<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "																	<table border=0>\n";
	echo "																		<tr>\n";
	echo "																			<td valign=\"top\">\n";
	echo "                                             																<select name=\"appt_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		//if ($mo==date("m"))
		//{
		//	echo "                                             																	<option value=\"".$mo."\" SELECTED>".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		//}
		//else
		//{
			echo "                                             																	<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		//}
	}

	echo "                                             																</select>\n";
	echo "																			</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		//if ($da==date("d"))
		//{
		//	echo "                                             																<option value=\"".$da."\" SELECTED>".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		//}
		//else
		//{
			echo "                                             																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		//}
	}

	echo "                                             																</select>\n";
	echo "																			</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_yr\">\n";
	echo "                                             																	<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr; $yr<=$futyr; $yr++)
	{
		//if ($yr==date("Y"))
		//{
		//	echo "                                             																	<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		//}
		//else
		//{
			echo "                                             																	<option value=\"".$yr."\">".$yr."</option>\n";
		//}
	}

	echo "                                             																</select>\n";
	echo "																			</td>\n";
	echo "																		</tr>\n";
	echo "																	</table>\n";
	echo "                           														</td>\n";
	echo "                           													</tr>\n";
	echo "                           													<tr>\n";
	echo "																<td align=\"right\" valign=\"bottom\"><b>Time:</b></td>\n";
	echo "																<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "																	<table border=0>\n";
	echo "																		<tr>\n";
	echo "																			<td align=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_hr\">\n";

	for ($hr=0; $hr<=12; $hr++)
	{
		echo "                                             																<option value=\"".$hr."\">".$hr."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "                                             															</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_mn\">\n";

	for ($mn=0; $mn<=60; $mn++)
	{
		echo "                                             																<option value=\"".$mn."\">".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "                                             															</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_pa\">\n";
	echo "                                             																	<option value=\"1\">AM</option>\n";
	echo "                                             																	<option value=\"2\">PM</option>\n";
	echo "                                             																</select>\n";
	echo "                                             															</td>\n";
	echo "                                             														</tr>\n";
	echo "                                             													</table>\n";
	echo "                                             												</td>\n";
	echo "                                             											</tr>\n";
	echo "                                             											<tr>\n";
	echo "                                             												<td align=\"right\"><font color=\"blue\">* Lead Source</font></td>\n";
	echo "                                             												<td align=\"left\">\n";
	echo "                                             													<select name=\"source\">\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		echo "                                             													<option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
	}

	echo "                                             													</select>\n";
	echo "                                             												</td>\n";
	echo "                                             											</tr>\n";
	echo "                                             										</table>\n";
	echo "                                             									</td>\n";
	echo "                                             								</tr>\n";
	echo "                                             							</table>\n";
	echo "                                             						</td>\n";
	echo "                                             						<td colspan=\"2\" align=\"right\" valign=\"top\">\n";
	echo "                                             							<table border=\"0\" class=\"outer\" width=\"100%\" height=\"100\">\n";
	echo "                                             								<tr>\n";
	echo "                                             									<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Comments/Directions:</b></td>\n";
	echo "                                             								</tr>\n";
	echo "                                             								<tr valign=\"top\">\n";
	echo "                                             									<td class=\"gray\" width=\"100px\" align=\"left\">&nbsp</td>\n";
	echo "                                             									<td class=\"gray\" align=\"left\">\n";
	echo "																									<textarea name=\"comments\" rows=\"5\" cols=\"50\"></textarea>\n";
	echo "																								</td>\n";
	echo "                                             								</tr>\n";
	echo "                                             							</table>\n";
	echo "                                             						</td>\n";
	echo "                                             					</tr>\n";
	echo "                                             				</table>\n";
	echo "                                             			</td>\n";
	echo "                                             		</tr>\n";
	//echo "                                             	</table>\n";
	echo "									<tr>\n";
	echo "										<td align=\"left\" valign=\"top\">\n";
	echo "											<table border=0 class=\"outer\" width=\"100%\">\n";
	echo "                           			<tr>\n";
	echo "													<td class=\"gray\" colspan=\"2\" align=\"left\" valign=\"top\"><b>Privacy Policy</b></td>\n";
	echo "                           			</tr>\n";
	echo "                           			<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"center\">\n";
	echo "														<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\">\n";
	echo "														<input type=\"hidden\" name=\"opt2\" value=\"0\">\n";
	echo "														<input type=\"hidden\" name=\"opt3\" value=\"0\">\n";
	echo "														<input type=\"hidden\" name=\"opt4\" value=\"0\">\n";
	echo "													</td>\n";
	echo "													<td class=\"gray\" align=\"left\">Customer does not wish to receive any future information about updates, special offers, or other communications regarding Blue Haven-related products, supplies, or services.</td>\n";	
	echo "                           			</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "                           </tr>\n";
	echo "								</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	<td valign=\"top\" align=\"left\">\n";
	echo "		<table border=0>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add Lead\">\n";
	echo "				</td>\n";
	echo "			</form>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function cform_view($tcid)
{
	unset($_SESSION['ifcid']); // Security Setting for embedded frames and AJAX
	
	$acclist=explode(",",$_SESSION['aid']);
	if ($_SESSION['llev'] < 4)
	{
		$dis="DISABLED";
	}
	else
	{
		$dis="";
	}

	if (empty($_POST['uid']))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error occured.\n";
		exit;
	}

	if (isset($tcid) && $tcid!=0)
	{
		//if (isset($_POST['subq']) && $_POST['subq']=="custid")
		//{
			$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$tcid."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$cid=$row0['cid'];
		//}
		//echo $cid." PROC<br>";	
	}
	else
	{
		if (isset($_POST['subq']) && $_POST['subq']=="custid")
		{
			$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_POST['custid']."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$cid=$row0['cid'];
		}
		else
		{
			$cid=$_POST['cid'];
		}
	}

	if (empty($cid))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> You must provide a Valid Customer ID number.\n";
		exit;
	}

	$dates	=dateformat();

	if ($_SESSION['llev'] >= 5)
	{
		if ($_SESSION['officeid']==89)
		{
			//echo "Not Admin<br>";
			$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE active=1 ORDER BY grouping,name ASC;";
		}
		else
		{
			//echo "Admin<br>";
			$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE active=1 AND adminonly!=1 ORDER BY grouping,name ASC;";
		}
	}
	else
	{
		$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	}
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	$qryAa = "SELECT officeid,name,stax,enest,encon,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
	$nrowsAa = mssql_num_rows($resAa);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,sidm,slevel,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY substring(slevel,13,13) desc, lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT stax,enest,encon,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[0]!=0)
	{
		$qryD = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resD = mssql_query($qryD);

		$qryE = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resE = mssql_query($qryE);
	}

	$qryF = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=1 AND access!=0 ORDER by name ASC;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT * FROM leadstatuscodes WHERE active=2 AND access!=0 ORDER by name ASC;";
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

	$qryL = "SELECT * FROM chistory WHERE officeid='".$_SESSION['officeid']."' AND custid='".$cid."' ORDER BY mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);

	$adate = date("m-d-Y (g:i A)", strtotime($rowF['added']));
	$curryr=date("Y");
	$futyr =$curryr+2;

	if ($_POST['uid']=="XXX")
	{
		$dis="DISABLED";
	}
	elseif ($_SESSION['llev']==1 && $rowF['estid']!=0)
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

	$appt_dt	="";
	if ($rowF['appt_mo']!="00" && $rowF['appt_da']!="00" && $rowF['appt_yr']!="0000")
	{
		
		$appt_dt=old_date_disp($rowF['appt_mo'],$rowF['appt_da'],$rowF['appt_yr'],$rowF['appt_hr'],$rowF['appt_mn'],$rowF['appt_pa']);
	}

	$_SESSION['ifcid']=$rowF['cid'];
	$cmaplink=maplink($rowF['caddr1'],$rowF['ccity'],$rowF['cstate'],$rowF['czip1']);
	$smaplink=maplink($rowF['saddr1'],$rowF['scity'],$rowF['sstate'],$rowF['szip1']);
	echo "<table width=\"85%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "		<table width=\"100%\" align=\"center\" border=0>\n";
	echo "   	<tr>\n";
	echo "      <td>\n";
	echo "      	<form name=\"cview1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" ".$dis.">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"edit\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_POST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
	echo "         <table border=\"0\" width=\"100%\">\n";
	echo "         	<tr>\n";
	echo "            	<td>\n";
	echo "               	<table border=\"0\" width=\"100%\">\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"left\">\n";
	echo "               				<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray\" align=\"left\"><b>Lead Information: Lead #: <font color=\"blue\">".$rowF['custid']."</font></b></td>\n";
	echo "                                 <td class=\"gray\" align=\"right\"></td>\n";
	echo "                        			<td class=\"gray\" valign=\"bottom\" align=\"right\">&nbsp\n";

	if ($_SESSION['llev'] >= 5)
	{
		if ($rowF['estid']==0)
		{
			echo "<b>Status:</b> <select name=\"dupe\">\n";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"dupe\" value=\"".$rowF['dupe']."\">\n";
			echo "<b>Status:</b>       <select name=\"stage\" DISABLED>\n";
		}

		//echo "                        	<b>Status:</b> <select name=\"dupe\">\n";

		if ($rowF['dupe']==1)
		{
			echo "<option value=\"1\" SELECTED>Inactive</option>\n";
			echo "<option value=\"0\">Active</option>\n";
		}
		else
		{
			echo "<option value=\"1\">Inactive</option>\n";
			echo "<option value=\"0\" SELECTED>Active</option>\n";
		}
	}
	else
	{
		echo "         <input type=\"hidden\" name=\"dupe\" value=\"0\">\n";
	}

	echo "                        				</select>\n";
	echo "											</td>\n";
	echo "                    				</tr>\n";
	echo "                    			</table>\n";
	echo "								</td>\n";
	echo "                    	</tr>\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"right\" valign=\"bottom\">\n";
	echo "                           <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                           	<tr>\n";
	echo "                              	<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Date:</b>\n";
	echo "                                 <td class=\"gray\" align=\"left\" valign=\"bottom\">".$adate."</td>\n";
	echo "                                 <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Office: </b></td>\n";
	echo "                                 <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";

	if ($_SESSION['llev'] >= 6)
	{
		echo "                                 	<select name=\"site\">\n";
		while ($rowA = mssql_fetch_array($resA))
		{
			if ($_SESSION['officeid']==$rowA['officeid'])
			{
				echo "                                 		<option value=\"".$rowA['officeid']."\" SELECTED>".$rowA['name']."</option>\n";
			}
			else
			{
				echo "                                 		<option value=\"".$rowA['officeid']."\">".$rowA['name']."</option>\n";
			}
		}
		echo "                                 	</select>\n";
	}
	elseif ($_SESSION['llev'] == 5)
	{
		if ($_SESSION['officeid']==89 || $_SESSION['officeid']==138) // Z&E Active or Z&E: Supplies Direct
		{
			if ($rowF['stage']==29)
			{
				echo "                                 	<select name=\"site\">\n";
				while ($rowA = mssql_fetch_array($resA))
				{
					if ($_SESSION['officeid']==$rowA['officeid'])
					{
						echo "                                 		<option value=\"".$rowA['officeid']."\" SELECTED>".$rowA['name']."</option>\n";
					}
					elseif ($rowA['officeid']==89)
					{
						echo "                                 		<option value=\"".$rowA['officeid']."\">".$rowA['name']."</option>\n";
					}
				}
				echo "                                 	</select>\n";
			}
			else
			{
				$rowAa = mssql_fetch_array($resAa);
				echo "                                 	".$rowAa['name']."<input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
			}
		}
		else
		{
			if ($rowF['source']==0 && $rowF['stage']==29)
			{
				echo "                                 	<select name=\"site\">\n";
				while ($rowA = mssql_fetch_array($resA))
				{
					if ($_SESSION['officeid']==$rowA['officeid'])
					{
						echo "                                 		<option value=\"".$rowA['officeid']."\" SELECTED>".$rowA['name']."</option>\n";
					}
					elseif ($rowA['officeid']==89)
					{
						echo "                                 		<option value=\"".$rowA['officeid']."\">".$rowA['name']."</option>\n";
					}
				}
				echo "                                 	</select>\n";
			}
			else
			{
				$rowAa = mssql_fetch_array($resAa);
				echo "                                 	".$rowAa['name']."<input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
			}
		}
	}
	else
	{
		$rowA = mssql_fetch_array($resA);
		echo "                                 	".$rowA['name']."<input type=\"hidden\" name=\"site\" value=\"".$rowA['officeid']."\">\n";
	}

	echo "                                 </td>\n";
	echo "                                 <td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Sales Rep:</b>\n";

	if ($_SESSION['llev'] == 4) // Sales Manager List
	{
		echo "                                 	<select name=\"estorig\">\n";

		while ($rowB = mssql_fetch_row($resB))
		{
			if (in_array($rowB[0],$acclist))
			{
				$slev=explode(",",$rowB[4]);
				
				if ($slev[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}

				if ($rowF['securityid']==$rowB[0])
				{
					echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
				}
				else
				{
					echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\">".$rowB[1]." ".$rowB[2]."</option>\n";
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
			if ($slev[6]==0)
			{
				$ostyle="fontred";
				//$ostyle="style=\"background-color:red\"";
			}
			else
			{
				$ostyle="fontblack";
				//$ostyle="";
			}

			if ($rowF['securityid']==$rowB[0])
			{
				echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
			}
			else
			{
				echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\">".$rowB[1]." ".$rowB[2]."</option>\n";
			}
		}

		echo "                                 	</select>\n";
	}
	else
	{
		echo "                                 ".$rowI['fname']." ".$rowI['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
	}

	echo "                                 </td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" colspan=\"2\" valign=\"bottom\" NOWRAP><b>Customer:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>First Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"cfname\" value=\"".$rowF['cfname']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Last Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"clname\" value=\"".$rowF['clname']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Home Phone</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\" value=\"".$rowF['chome']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Work Phone</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\" value=\"".$rowF['cwork']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Cell Phone</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\" value=\"".$rowF['ccell']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Fax</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" value=\"".$rowF['cfax']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Best Phone</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP>\n";
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
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Email</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" name=\"cemail\" size=\"30\" value=\"".$rowF['cemail']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Contact Time</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"ccontime\" value=\"".$rowF['ccontime']."\"></td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Current Address:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Street</td>\n";

	if ($rowF['caddr1']==0)
	{
		echo "											<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"caddr1\"></td>\n";
	}
	else
	{
		echo "											<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"caddr1\" value=\"".$rowF['caddr1']."\"></td>\n";
	}

	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>City</td>\n";
	echo "											<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"ccity\" value=\"".$rowF['ccity']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" value=\"".$rowF['cstate']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Zip</td>\n";
	echo "											<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".$rowF['czip1']."\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\" value=\"".$rowF['czip2']."\"> ".$cmaplink."</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\" NOWRAP>Cnty/Twnshp</td>\n";
	echo "											<td class=\"gray\" NOWRAP>\n";

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

	if ($rowF['estid']==0)
	{
		echo "											<tr>\n";

		if ($rowF['ssame']==1)
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
		}
		else
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\"> Same as above</td>\n";
		}

		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Street:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\" value=\"".$rowF['saddr1']."\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>City:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Zip:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\"> ".$smaplink."</td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Cnty/Twnshp:</td>\n";
		echo "												<td class=\"gray\" NOWRAP>\n";

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
	}
	else
	{
		echo "											<tr>\n";

		if ($rowF['ssame']==1)
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED DISABLED> Same as above</td>\n";
			echo "<input type=\"hidden\" name=\"ssame\" value=\"1\">\n";
		}
		else
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" DISABLED> Same as above</td>\n";
			echo "<input type=\"hidden\" name=\"ssame\" value=\"0\">\n";
		}

		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Street:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\" value=\"".$rowF['saddr1']."\" DISABLED></td>\n";
		echo "<input type=\"hidden\" name=\"saddr1\" value=\"".$rowF['saddr1']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>City:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\" DISABLED> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\" DISABLED></td>\n";
		echo "<input type=\"hidden\" name=\"scity\" value=\"".$rowF['scity']."\"><input type=\"hidden\" name=\"sstate\" value=\"".$rowF['sstate']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Zip:</td>\n";
		echo "												<td class=\"gray\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\" DISABLED>-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\" DISABLED> ".$smaplink."</td>\n";
		echo "<input type=\"hidden\" name=\"szip1\" value=\"".$rowF['szip1']."\"><input type=\"hidden\" name=\"szip2\" value=\"".$rowF['szip2']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\" NOWRAP>Cnty/Twnshp:</td>\n";
		echo "												<td class=\"gray\" NOWRAP>\n";

		if ($rowC[0]==0)
		{
			echo "													<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\" value=\"".$rowF['scounty']."\" DISABLED>\n";
			echo "<input type=\"hidden\" name=\"scounty\" value=\"".$rowF['scounty']."\">\n";
		}
		elseif ($rowC[0]==1)
		{
			echo "													<select name=\"scounty\" DISABLED>\n";
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
			echo "<input type=\"hidden\" name=\"scounty\" value=\"".$rowF['scounty']."\">\n";
		}

		echo "											Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"smap\" value=\"".$rowF['smap']."\" DISABLED>\n";
		echo "<input type=\"hidden\" name=\"smap\" value=\"".$rowF['smap']."\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
	}

	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"200\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" valign=\"top\"><b>Appointment/Source/Result:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"center\" valign=\"top\">\n";
	echo "												<table border=0>\n";
	echo "													<tr>\n";
	echo "                                 <tr>\n";
	echo "                        			<td align=\"right\"><b>Lead Contacted</b></td>\n";
	echo "                        			<td valign=\"bottom\" align=\"left\" colspan=\"5\">\n";

	if ($rowF['ccontact']==1)
	{
		if (!empty($rowF['ccontactby']) && $rowF['ccontactby']!=0)
		{
			$qryFz = "SELECT securityid,lname,fname,slevel FROM security WHERE securityid='".$rowF['ccontactby']."';";
			$resFz = mssql_query($qryFz);
			$rowFz = mssql_fetch_array($resFz);
			
			$scon	= explode(",",$rowFz['slevel']);
			
			if ($scon[7]==0)
			{
				$cconby=" by <font color=\"red\">".$rowFz['lname'].", ".$rowFz['fname']."</font>";
			}
			else
			{
				$cconby=" by ".$rowFz['lname'].", ".$rowFz['fname'];
			}
		}
		else
		{
			$cconby="";
		}
		
		echo date("m/d/Y",strtotime($rowF['ccontactdate']))." ".$cconby;
		echo "<input type=\"hidden\" name=\"ccontact\" value=\"1\">\n";
	}
	else
	{
		echo "<input class=\"checkboxgry\" type=\"checkbox\" name=\"ccontact\" value=\"1\" title=\"Check this box to indicate the Customer has been contacted.\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "											<td align=\"right\"><b> Appt. Date & Time</b></td>\n";
	/*
	echo "											<td align=\"left\" valign=\"bottom\">\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"appt_dt\" value=\"".$appt_dt."\" size=\"15\">\n";
	echo "												<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Appointment Date & Time\"></a>\n";
	echo "											</td>\n";
	*/
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

	for ($yr=$curryr-1; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['appt_yr'])
		{
			echo "																<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		}
		else
		{
			//echo "																<option value=\"".$yr."\">".$yr." ($curryr ".$rowF['appt_yr'].")</option>\n";
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
	echo "														<td align=\"right\"><b>Lead Source</b></td>\n";

	if ($rowF['source']==0)
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">bluehaven.com</td>\n";
		echo "         											<input type=\"hidden\" name=\"source\" value=\"0\">\n";
	}
	elseif ($rowF['source']==44)
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">poolsearch.com</td>\n";
		echo "         											<input type=\"hidden\" name=\"source\" value=\"".$rowF['source']."\">\n";
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
	echo "														<td align=\"right\"><b>Lead Result</b></td>\n";
	echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";

	if ($rowF['estid']==0)
	{
		echo "                                             <select name=\"stage\">\n";
	}
	else
	{
		echo "         											<input type=\"hidden\" name=\"stage\" value=\"".$rowF['stage']."\">\n";
		echo "                                             <select name=\"stage\" DISABLED>\n";
	}

	echo "                                             	<option value=\"1\"></option>\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		if ($rowG['statusid']==$rowF['stage'])
		{
			echo "                                             <option value=\"".$rowG['statusid']."\" SELECTED>".$rowG['name']."</option>\n";
		}
		else
		{
			echo "                                             <option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "                                 </tr>\n";

	// Call Back Selects
	echo "                                 <tr>\n";
	echo "                        			<td align=\"right\"><b>Call Back</b></td>\n";
	echo "                        			<td valign=\"bottom\" align=\"left\" colspan=\"5\">\n";

	if ($rowF['hold']==1)
	{
		echo "<input class=\"checkboxgry\" type=\"checkbox\" name=\"hold\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "<input class=\"checkboxgry\" type=\"checkbox\" name=\"hold\" value=\"1\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "                        			<td valign=\"bottom\" align=\"right\"><b>on</b></td>\n";
	/*
	echo "											<td align=\"left\" valign=\"bottom\">\n";
	echo "												<input class=\"bboxl\" type=\"text\" name=\"callb_dt\" size=\"15\">\n";
	echo "												<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Call ack Date & Time\"></a>\n";
	echo "											</td>\n";
	*/
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

	for ($yr=$curryr-1; $yr<=$futyr; $yr++)
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

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	
//	echo "2:".$rowC[2]."<br>";
//	echo "3:".$rowC[3]."<br>";
//	echo "4:".$rowC[4]."<br>";
	
	if ($rowC[2]!=1 && $rowC[3]!=1 && $rowC[4]!=0)
	{	
		if (isset($rowF['finan_src']) && $rowF['finan_src'] > 0)
		{
			$disfr=" DISABLED ";
		}
		else
		{
			$disfr='';
		}
		
		echo "                             			   <tr>\n";
		echo "                        						<td align=\"right\"><b>Finance Release</b></td>\n";
		echo "                        						<td valign=\"bottom\" align=\"left\" colspan=\"5\">\n";
		echo "                                    			<select name=\"finansrc\" ".$disfr." title=\"Set the Finance Source\">\n";
		
		if (!isset($rowF['finan_src']) || $rowF['finan_src']==0)
		{
			echo "                                    	<option value=\"0\"></option>\n";
			echo "                                    	<option value=\"1\">Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
		}
		elseif ($rowF['finan_src']==1)
		{
			echo "                                    	<option value=\"0\"></option>\n";
			echo "                                    	<option value=\"1\" selected>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";	
		}
		elseif ($rowF['finan_src']==2)
		{
			echo "                                    	<option value=\"0\"></option>\n";
			echo "                                    	<option value=\"1\">Winners</option>\n";
			echo "                                    	<option value=\"2\" selected>Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
		}
		elseif ($rowF['finan_src']==3)
		{
			echo "                                    	<option value=\"0\"></option>\n";
			echo "                                    	<option value=\"1\">Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\" selected>Cash</option>\n";
		}
		
		echo "                                    			</select>\n";
		
		if (isset($rowF['finan_src']) && $rowF['finan_src'] > 0)
		{
			echo "												<input type=\"hidden\" name=\"finansrc\" value=\"".$rowF['finan_src']."\">\n";
		}
		
		echo "                        						</td>\n";
		echo "                        					</tr>\n";	
	}
	
	echo "												</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td align=\"right\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"200\">\n";
	echo "										<tr>\n";
	echo "											<td height=\"20px\" class=\"gray\" valign=\"top\"><b>Comments/Directions:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" valign=\"top\" align=\"center\">\n";
	echo "												<iframe src=\"subs/comments.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"right\"></iframe>\n";
	echo "												<input type=\"hidden\" name=\"comments\" value=\"".$rowF['comments']."\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\" colspan=\"2\" valign=\"top\">\n";
	echo "									<table border=0 class=\"outer\" width=\"100%\">\n";
	echo "                           	<tr>\n";
	echo "											<td class=\"gray\" colspan=\"2\" align=\"left\" valign=\"top\"><b>Privacy Policy</b></td>\n";
	echo "                           	</tr>\n";
	echo "                           	<tr>\n";
	echo "											<td class=\"gray\" width=\"100px\" align=\"center\">\n";
	
	if ($rowF['opt1']==1)
	{
		if ($rowF['source']==0)
		{
			echo "												<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\" title=\"Cannot be Modified. This Lead was sourced from bluehaven.com\" CHECKED DISABLED>\n";
			echo "												<input type=\"hidden\" name=\"opt1\" value=\"1\">\n";
		}
		else
		{
			echo "												<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\" CHECKED>\n";
		}
	}
	else
	{
		echo "												<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\">\n";
	}
		
	echo "												<input type=\"hidden\" name=\"opt2\" value=\"0\">\n";
	echo "												<input type=\"hidden\" name=\"opt3\" value=\"0\">\n";
	echo "												<input type=\"hidden\" name=\"opt4\" value=\"0\">\n";
	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"left\">Customer does not wish to receive any future information about updates, special offers, or other communications regarding Blue Haven-related products, supplies, or services.</td>\n";	
	echo "                           	</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	

	if (!empty($rowF['mrktproc']))
	{
		echo "							<tr>\n";
		echo "								<td align=\"right\" valign=\"top\" colspan=\"2\">\n";
		echo "									<table class=\"outer\" width=\"100%\" height=\"75\">\n";
		echo "										<tr>\n";
		echo "											<td class=\"gray\" valign=\"top\"><b>Marketing Data:</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr valign=\"top\">\n";
		echo "											<td class=\"gray\"><textarea name=\"mrkproc\" cols=\"90\" rows=\"25\">".$rowF['mrktproc']."</textarea></td>\n";
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
	echo "				   <td>\n";

	if (!empty($_POST['subq']) && $_POST['subq']=="history")
	{
		$qryZ = "SELECT * FROM leadhistory WHERE cinfo_id='".$_POST['cid']."' ORDER BY udate DESC;";
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
					//echo "         <td class=\"wh_und\" align=\"left\">Internet</td>\n";
					echo "         <td class=\"wh_und\" align=\"left\">bluehaven.com</td>\n";
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
	
	/*
	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['cview1'].elements['appt_dt']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = true;\n";
	echo "         						var cal2 = new calendar2(document.forms['cview1'].elements['callb_dt']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = true;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";
	*/
	
	echo "			</tr>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	echo "         <input type=\"hidden\" name=\"rcall\" value=\"".$_POST['call']."\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_POST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	echo "         <input type=\"hidden\" name=\"custid\" value=\"".$rowF['custid']."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_POST['uid']!="XXX")
	{
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Comments\"><br>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"view\">\n";
	echo "         <input type=\"hidden\" name=\"subq\" value=\"history\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_POST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['llev'] >= 4 && $_POST['uid']!="XXX")
	{
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"History\"><br>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";

	if ($_SESSION['elev'] >= 1 && $rowC[1]==1)
	{
		if ($rowF['hold']==0 && $rowF['dupe']==0 && $rowF['estid']==0)
		{
			echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "         <input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "         <input type=\"hidden\" name=\"call\" value=\"matrix0\">\n";
			echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_POST['uid']."\">\n";
			//echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['custid']."\">\n";
			echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
			echo "         <input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";
			echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
			echo "			<tr>\n";
			echo "				<td valign=\"top\">\n";
			echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Estimate\"><br>\n";
			//echo $rowF['hold']."<br>";
			//echo $rowF['dupe']."<br>";
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "			</form>\n";
		}
	}

	/*
	echo "			<tr>\n";
	echo "                     	<td align=\"center\">\n";
	echo "				<hr width=\"80%\">\n";
	echo "                     	</td>\n";
	echo "			</tr>\n";
	echo "      			<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "			<tr>\n";
	echo "                     	<td align=\"center\">\n";
	echo "         			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         			<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "         			<input type=\"hidden\" name=\"incerrs\" value=\"0\">\n";
	echo "				<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Return to List\"><br>\n";
	echo "                     	</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";
	*/

	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	
	$qryXX = "UPDATE cinfo SET viewed=getdate(), viewedby=".$_SESSION['securityid']." WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resXX = mssql_query($qryXX);
}

function cform_add()
{
	error_reporting(E_ALL);

	//$err=0;

	if (empty($_POST['cfname'])||empty($_POST['clname'])||empty($_POST['chome'])||empty($_POST['caddr1'])||empty($_POST['ccity'])||empty($_POST['czip1'])||empty($_POST['cemail'])||!is_numeric($_POST['czip1'])||strlen($_POST['czip1']) != 5||$_POST['source']==1|| preg_match("/,/",$_POST['clname']) || preg_match("/'/",$_POST['clname']) || preg_match("/,/",$_POST['cfname']) || preg_match("/'/",$_POST['cfname'])|| preg_match("/,/",$_POST['caddr1']) || preg_match("/'/",$_POST['caddr1']))
	{

		echo "<table>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><font color=\"red\"><b>ERROR!</b></font></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><b>Required Information is Missing or is Improperly Formatted, click the BACK button and correct:</b></td>";
		echo "	</tr>\n";

		if (empty($_POST['cfname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- First Name</b></td>";
			echo "	</tr>\n";
		}

		if (preg_match("/,/",$_POST['cfname']) || preg_match("/'/",$_POST['cfname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- First Name cannot contain Commas or Apostrophes</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['clname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Last Name</b></td>";
			echo "	</tr>\n";
		}

		if (preg_match("/,/",$_POST['clname']) || preg_match("/'/",$_POST['clname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Last Name cannot contain Commas or Apostrophes</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['chome']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Home Phone</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['caddr1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Address</b></td>";
			echo "	</tr>\n";
		}

		if (preg_match("/,/",$_POST['caddr1']) || preg_match("/'/",$_POST['caddr1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Address cannot contain Commas or Apostrophes</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['ccity']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- City</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['czip1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Zip Code Blank</b></td>";
			echo "	</tr>\n";
		}

		if (!is_numeric($_POST['czip1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Zip Code not Numeric</b></td>";
			echo "	</tr>\n";
		}

		if (strlen($_POST['czip1']) != 5)
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Zip Code Length not valid</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_POST['cemail']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- E-Mail Address</b></td>";
			echo "	</tr>\n";
		}

		if ($_POST['source']==1)
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Lead Source</b></td>";
			echo "	</tr>\n";
		}

		echo "</table>\n";
		exit;
	}

	$qryA = "SELECT COUNT(cid) AS ccnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND clname='".$_POST['clname']."' AND caddr1='".$_POST['caddr1']."' AND czip1='".$_POST['czip1']."';";
	$resA = mssql_query($qryA);
	$rowA= mssql_fetch_array($resA);

	//echo $qryA."<br>";

	if ($rowA['ccnt'] > 0)
	{
		echo "<b><font color=\"red\">Error!</font> <br><br>The Lead information you entered already exists in the Lead System. Check your entry and resubmit</b>";
		exit;
	}
	else
	{
		if (!empty($_POST['opt1']) && $_POST['opt1']==1)
		{
			$opt1=1;
		}
		else
		{
			$opt1=0;
		}

		if (!empty($_POST['opt2']) && $_POST['opt2']==1)
		{
			$opt2=1;
		}
		else
		{
			$opt2=0;
		}
		
		if (!empty($_POST['opt3']) && $_POST['opt3']==1)
		{
			$opt3=1;
		}
		else
		{
			$opt3=0;
		}

		if (!empty($_POST['opt4']) && $_POST['opt4']==1)
		{
			$opt4=1;
		}
		else
		{
			$opt4=0;
		}
		
		$qryC   = "exec sp_insert_cinfo ";
		$qryC  .= "@securityid='".$_POST['estorig']."', ";
		$qryC  .= "@officeid='".$_SESSION['officeid']."', ";
		$qryC  .= "@srcoffice='".$_SESSION['officeid']."', ";
		$qryC  .= "@recdate='".$_POST['recdate']."', ";
		$qryC  .= "@cfname='".replacequote(ucwords(trim($_POST['cfname'])))."', ";
		$qryC  .= "@clname='".replacequote(ucwords(trim($_POST['clname'])))."', ";
		$qryC  .= "@caddr1='".replacequote(trim($_POST['caddr1']))."', ";
		$qryC  .= "@ccity='".replacequote($_POST['ccity'])."', ";
		$qryC  .= "@cstate='".$_POST['cstate']."', ";
		$qryC  .= "@czip1='".$_POST['czip1']."', ";
		$qryC  .= "@czip2='".$_POST['czip2']."', ";
		$qryC  .= "@ccounty='".$_POST['ccounty']."', ";
		$qryC  .= "@cmap='".replacequote($_POST['cmap'])."', ";

		if (empty($_POST['ssame']))
		{
			$qryC  .= "@ssame='0', ";
			$qryC  .= "@saddr1='".replacequote($_POST['saddr1'])."', ";
			$qryC  .= "@scity='".replacequote($_POST['scity'])."', ";
			$qryC  .= "@sstate='".$_POST['sstate']."', ";
			$qryC  .= "@szip1='".$_POST['szip1']."', ";
			$qryC  .= "@szip2='".$_POST['szip2']."', ";
			$qryC  .= "@scounty='".$_POST['scounty']."', ";
			$qryC  .= "@smap='".replacequote($_POST['smap'])."', ";
		}
		else
		{
			$qryC  .= "@ssame='".$_POST['ssame']."', ";
			$qryC  .= "@saddr1='".replacequote($_POST['caddr1'])."', ";
			$qryC  .= "@scity='".replacequote($_POST['ccity'])."', ";
			$qryC  .= "@sstate='".$_POST['cstate']."', ";
			$qryC  .= "@szip1='".$_POST['czip1']."', ";
			$qryC  .= "@szip2='".$_POST['czip2']."', ";
			$qryC  .= "@scounty='".$_POST['ccounty']."', ";
			$qryC  .= "@smap='".replacequote($_POST['cmap'])."', ";
		}

		$qryC  .= "@chome='".$_POST['chome']."', ";
		$qryC  .= "@cwork='".$_POST['cwork']."', ";
		$qryC  .= "@ccell='".$_POST['ccell']."', ";
		$qryC  .= "@cfax='".$_POST['cfax']."', ";
		$qryC  .= "@source='".$_POST['source']."', ";
		$qryC  .= "@cemail='".replacequote($_POST['cemail'])."', ";
		$qryC  .= "@cconph='".$_POST['cconph']."', ";
		$qryC  .= "@ccontime='".$_POST['ccontime']."', ";
		$qryC  .= "@appt_mo='".$_POST['appt_mo']."', ";
		$qryC  .= "@appt_da='".$_POST['appt_da']."', ";
		$qryC  .= "@appt_yr='".$_POST['appt_yr']."', ";
		$qryC  .= "@appt_hr='".$_POST['appt_hr']."', ";
		$qryC  .= "@appt_mn='".$_POST['appt_mn']."', ";
		$qryC  .= "@appt_pa='".$_POST['appt_pa']."', ";
		$qryC  .= "@opt1='".$opt1."', ";
		$qryC  .= "@opt2='".$opt2."', ";
		$qryC  .= "@opt3='".$opt3."', ";
		$qryC  .= "@opt4='".$opt4."', ";
		$qryC  .= "@comments=''; ";

		$resC   = mssql_query($qryC);
		$rowC   = mssql_fetch_row($resC);
		
		//echo $rowC[0];
		//echo $qryC."<BR>";
		/*
		if (isset($rowC[0]) && $rowC[0] != 0 && !empty($_POST['comments']) && strlen($_POST['comments']) >= 2 && $rowA['ccnt'] == 0)
		{
			$qryB   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
			$qryB  .= "VALUES ";
			$qryB  .= "('".$rowC[0]."','".$_SESSION['officeid']."','".$_SESSION['securityid']."','leads','".replacequote($_POST['comments'])."','".$_POST['uid']."')";
			$resB  = mssql_query($qryB);
			
			//echo $qryB."<BR>";
		}
		
		cform_view($rowC[0]);
		*/
		
		if (isset($rowC[0]) && $rowC[0] != 0)
		{
			if ( !empty($_POST['comments']) && strlen($_POST['comments']) >= 2 && $rowA['ccnt'] == 0)
			{
				$qryB   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
				$qryB  .= "VALUES ";
				$qryB  .= "('".$rowC[0]."','".$_SESSION['officeid']."','".$_SESSION['securityid']."','leads','".replacequote($_POST['comments'])."','".$_POST['uid']."')";
				$resB  = mssql_query($qryB);
			}
			
			cform_view($rowC[0]);	
			//echo $qryB."<BR>";
		}
		else
		{
			echo "<b><font color=\"red\">Error!</font> <br><br>The Lead information you attempted to submit did not save. Click back, check your entry and resubmit</b>";
			exit;
		}
	}
}

function old_date_store($date)
{
	$date_ar=array();
	
	$m=date("m",strtotime($date));
	$d=date("d",strtotime($date));
	$y=date("Y",strtotime($date));
	$h=date("h",strtotime($date));
	$i=date("i",strtotime($date));
	$a=date("A",strtotime($date));

	//$dtext=$m."/".$d."/".$y." ".$h.":".$i." ".$a;
	$dtext=date("m/d/Y h:i A",strtotime($date));
	$date_ar=array($m,$d,$y,$h,$i,$a,$dtext);
	
	print_r($date_ar);
	return $date_ar;
}

function old_date_disp($m,$d,$y,$h,$i,$a)
{
	$dtxt	="";
	$ap	="";
	
	if ($a==1)
	{
		$ap="AM";
	}
	else
	{
		$ap="PM";
	}
	
	if ($i=="0")
	{
		$mn="00";
	}
	else
	{
		$mn=$i;
	}

	$dtxt=$m."/".$d."/".$y." ".$h.":".$mn." ".$ap;
	
	//echo $dtxt;
	return $dtxt;
}

function cform_edit_new()
{

	//show_post_vars();
	/*
	if (!valid_date($_POST['appt_dt']))
	{
		echo $_POST['appt_dt']." is Not Valid<br>";
	}

	if (!valid_date($_POST['callb_dt']))
	{
		echo $_POST['callb_dt']." is Not Valid<br>";
	}
	*/
	
	old_date_store($_POST['appt_dt']);
	
	echo "<br>";
	
	old_date_disp(04,15,2005,1,25,0);
	
}

function cform_edit()
{
	error_reporting(E_ALL);
	$acclist=explode(",",$_SESSION['aid']);

	$qry = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_POST['cid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1 = "SELECT am,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	$qry2 = "SELECT am FROM offices WHERE officeid='89';"; //BHNM:Active
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	$qry3 = "SELECT am,name FROM offices WHERE officeid='".$_POST['site']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);

	$qry4 = "SELECT securityid,sidm FROM security WHERE securityid='".$row['securityid']."';";
	$res4 = mssql_query($qry4);
	$row4 = mssql_fetch_array($res4);

	if (!in_array($row['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to Update this Lead</b>";
		exit;
	}
	else
	{
		// Lead Contact Tests
		//if ($row['ccontact']==0 && !empty($_POST['ccontact']) && $_POST['ccontact']==1)
		//{
		//	if 
			
		//}
		
		if (!isset($_POST['hold']))
		{
			$hold=0;
		}
		elseif ($_POST['stage']==6)
		{
			$hold=0;
		}
		else
		{
			$hold=$_POST['hold'];
		}
		
		if (!empty($_POST['opt1']) && $_POST['opt1']==1)
		{
			$opt1=1;
		}
		else
		{
			$opt1=0;
		}

		if (!empty($_POST['opt2']) && $_POST['opt2']==1)
		{
			$opt2=1;
		}
		else
		{
			$opt2=0;
		}
		
		if (!empty($_POST['opt3']) && $_POST['opt3']==1)
		{
			$opt3=1;
		}
		else
		{
			$opt3=0;
		}

		if (!empty($_POST['opt4']) && $_POST['opt4']==1)
		{
			$opt4=1;
		}
		else
		{
			$opt4=0;
		}
		
		/*
		if (!empty($_POST['appt_dt']))
		{
			$appt_set=old_date_store($_POST['appt_dt']);
			$appt_mo=$appt_set[0];
			$appt_da=$appt_set[1];
			$appt_yr=$appt_set[2];
			$appt_hr=$appt_set[3];
			$appt_mn=$appt_set[4];
			$appt_pa=$appt_set[5];
			$appt_dt=$appt_set[6];
		}
		else
		{
			$appt_mo="00";
			$appt_da="00";
			$appt_yr="0000";
			$appt_hr="00";
			$appt_mn="00";
			$appt_pa="0";
			$appt_dt="";
		}
		
		if (!empty($_POST['callb_dt']))
		{
			$hold_set=old_date_store($_POST['callb_dt']);
			$hold_mo=$hold_set[0];
			$hold_da=$hold_set[1];
			$hold_yr=$hold_set[2];
			$hold_dt=$hold_set[6];
		}
		else
		{
			$hold_mo="00";
			$hold_da="00";
			$hold_yr="0000";
			$hold_dt="";
		}
		*/
		
		$qryA  = "UPDATE cinfo SET ";

		if ($_SESSION['llev'] >= 5)
		{
			$qry4 = "SELECT custid FROM cinfo WHERE officeid='".$_POST['site']."' AND custid='".$row['custid']."';";
			$res4 = mssql_query($qry4);
			$row4 = mssql_fetch_array($res4);
			$nrow4= mssql_num_rows($res4);

			if ($nrow4 > 0 && $_SESSION['officeid']!=$_POST['site'])
			{
				$qry5 = "SELECT MAX(custid) as mcustid FROM cinfo WHERE officeid='".$_POST['site']."';";
				$res5 = mssql_query($qry5);
				$row5 = mssql_fetch_array($res5);

				$ncustid=$row5['mcustid']+1;
				$qryA  .= "custid='".$ncustid."', ";
			}

			if ($_SESSION['officeid']!=$_POST['site'])
			{
				$qryA  .= "securityid='".$row3['am']."', ";
				$qryA  .= "officeid='".$_POST['site']."', ";
				//$qryA  .= "officeid='89', ";
				$udate_id=$row3['am'];
			}
			else
			{
				$qryA  .= "securityid='".$_POST['estorig']."', ";
				$qryA  .= "officeid='".$_POST['site']."', ";
				$udate_id=$_POST['estorig'];
			}
		}
		else
		{
			$qryA  .= "securityid='".$_POST['estorig']."', ";
			$udate_id=$_POST['estorig'];
		}

		if ($_SESSION['llev'] >= 4)
		{
			$qryA  .= "cfname='".replacequote(ucwords($_POST['cfname']))."', ";
			$qryA  .= "clname='".replacequote(ucwords($_POST['clname']))."', ";
		}

		$qryA  .= "caddr1='".replacequote(ucwords($_POST['caddr1']))."', ";
		$qryA  .= "ccity='".replacequote(ucwords($_POST['ccity']))."', ";
		$qryA  .= "cstate='".$_POST['cstate']."', ";
		$qryA  .= "czip1='".$_POST['czip1']."', ";
		$qryA  .= "czip2='".$_POST['czip2']."', ";
		$qryA  .= "ccounty='".$_POST['ccounty']."', ";
		$qryA  .= "cmap='".replacequote($_POST['cmap'])."', ";

		if (empty($_POST['ssame']))
		{
			$qryA  .= "ssame='0', ";
			$qryA  .= "saddr1='".replacequote(ucwords($_POST['saddr1']))."', ";
			$qryA  .= "scity='".replacequote(ucwords($_POST['scity']))."', ";
			$qryA  .= "sstate='".$_POST['sstate']."', ";
			$qryA  .= "szip1='".$_POST['szip1']."', ";
			$qryA  .= "szip2='".$_POST['szip2']."', ";
			$qryA  .= "scounty='".$_POST['scounty']."', ";
			$qryA  .= "smap='".replacequote($_POST['smap'])."', ";
		}
		else
		{
			$qryA  .= "ssame='".$_POST['ssame']."', ";
			$qryA  .= "saddr1='".replacequote(ucwords($_POST['caddr1']))."', ";
			$qryA  .= "scity='".replacequote(ucwords($_POST['ccity']))."', ";
			$qryA  .= "sstate='".$_POST['cstate']."', ";
			$qryA  .= "szip1='".$_POST['czip1']."', ";
			$qryA  .= "szip2='".$_POST['czip2']."', ";
			$qryA  .= "scounty='".$_POST['ccounty']."', ";
			$qryA  .= "smap='".replacequote($_POST['cmap'])."', ";
		}

		if ($_SESSION['llev'] >= 4)
		{
			$qryA  .= "chome='".$_POST['chome']."', ";
			$qryA  .= "cwork='".$_POST['cwork']."', ";
			$qryA  .= "ccell='".$_POST['ccell']."', ";
			$qryA  .= "cfax='".$_POST['cfax']."', ";
		}

		$qryA  .= "cemail='".replacequote($_POST['cemail'])."', ";
		$qryA  .= "cconph='".$_POST['cconph']."', ";
		$qryA  .= "ccontime='".$_POST['ccontime']."', ";
		
		$qryA  .= "appt_mo='".$_POST['appt_mo']."', ";
		$qryA  .= "appt_da='".$_POST['appt_da']."', ";
		$qryA  .= "appt_yr='".$_POST['appt_yr']."', ";
		$qryA  .= "appt_hr='".$_POST['appt_hr']."', ";
		$qryA  .= "appt_mn='".$_POST['appt_mn']."', ";
		$qryA  .= "appt_pa='".$_POST['appt_pa']."', ";
		/*
		$qryA  .= "appt_mo='".$appt_mo."', ";
		$qryA  .= "appt_da='".$appt_da."', ";
		$qryA  .= "appt_yr='".$appt_yr."', ";
		$qryA  .= "appt_hr='".$appt_hr."', ";
		$qryA  .= "appt_mn='".$appt_mn."', ";
		$qryA  .= "appt_pa='".$appt_pa."', ";
		$qryA  .= "appt_dt='".$appt_dt."', ";
		*/
		$qryA  .= "hold_mo='".$_POST['hold_mo']."', ";
		$qryA  .= "hold_da='".$_POST['hold_da']."', ";
		$qryA  .= "hold_yr='".$_POST['hold_yr']."', ";
		
		/*
		$qryA  .= "hold_mo='".$hold_mo."', ";
		$qryA  .= "hold_da='".$hold_da."', ";
		$qryA  .= "hold_yr='".$hold_yr."', ";
		$qryA  .= "hold_dt='".$hold_dt."', ";
		*/
		$qryA  .= "source='".$_POST['source']."', ";
		$qryA  .= "stage='".$_POST['stage']."', ";
		$qryA  .= "hold='".$hold."', ";
		$qryA  .= "updated=getdate(), ";
		$qryA  .= "opt1='".$opt1."', ";
		$qryA  .= "opt2='".$opt2."', ";
		$qryA  .= "opt3='".$opt3."', ";
		$qryA  .= "opt4='".$opt4."', ";
		
		if ($row['ccontact']==0 && !empty($_POST['ccontact']) && $_POST['ccontact']==1)
		{
			$qryA  .= "ccontact='".$_POST['ccontact']."', ";
			$qryA  .= "ccontactdate=getdate(), ";
			$qryA  .= "ccontactby='".$_SESSION['securityid']."', ";
		}
		
		$qryA  .= "dupe='".$_POST['dupe']."' ";
		$qryA  .= "WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_POST['cid']."';";
		$resA  = mssql_query($qryA);
		//$rowA = mssql_fetch_array($resA);
		//echo $qryA;

		//Update history table
		$qryB   = "INSERT INTO leadhistory (cinfo_id,officeid,owner,uby,source,result) ";
		$qryB  .= "VALUES ";
		$qryB  .= "('".$_POST['cid']."','".$_POST['site']."','".$udate_id."','".$_SESSION['securityid']."','".$_POST['source']."','".$_POST['stage']."')";
		$resB  = mssql_query($qryB);

		//Update chistory table for inter-office moves
		if ($_SESSION['officeid']!=$_POST['site'])
		{
			$qryC	= "UPDATE chistory SET officeid='".$_POST['site']."' WHERE custid='".$_POST['cid']."';";
			$resC	= mssql_query($qryC);
		}
		
		// Create Finance Record
		if (isset($_POST['finansrc']) && $_POST['finansrc'] > 0)
		{
			add_finan_cust($_SESSION['officeid'],$row1['finan_from'],$_POST['cid'],$_SESSION['securityid'],$_POST['uid']);
		}

		if ($_SESSION['llev'] >= 5)
		{
			if ($_SESSION['officeid']!=$_POST['site'])
			{
				echo "<b>Lead forwarded to ".$row3['name']."</b>";
			}
			else
			{
				if (!empty($_SESSION['tqry']))
				{
					listleads();
				}
				else
				{
					cform_view();
				}
			}
		}
		else
		{
			if (!empty($_SESSION['tqry']))
			{
				listleads();
			}
			else
			{
				cform_view();
			}
		}
	}

}

function cform_delete()
{
	$qry = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_POST['cid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$idarray=accessidlist(1,6,$row['securityid']);

	if (!in_array($_SESSION['securityid'],$idarray))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to Delete this Lead</b>";
		exit;
	}
	else
	{
		$qryA = "DELETE FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_POST['cid']."';";
		$resA = mssql_query($qryA);

		//listleads();
	}
}

?>