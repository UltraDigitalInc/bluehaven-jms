<?php

function basematrix()
{
	include ('lead_support_func.php');
	
	if ($_SESSION['call']=="list")
	{
		if ($_SESSION['otype']==2)
        {
            listleads_VENDOR();    
        }
		elseif ($_SESSION['otype']==3)
        {
            listleads_TRACK();
        }
        else
        {
            listleads();
        }
	}
	elseif ($_SESSION['call']=="appts")
	{
		apptleads_mo();
	}
	elseif ($_SESSION['call']=="new")
	{
        if ($_SESSION['otype']==2)
        {
            cform_VENDOR();
        }
		elseif ($_SESSION['otype']==3)
        {
            cform_TRACK();
        }
        else
        {
            cform();
        }
	}
	elseif ($_SESSION['call']=="add")
	{
		if ($_SESSION['otype']==2)
        {
            cform_add_VENDOR();
        }
        elseif ($_SESSION['otype']==3)
        {
            cform_add_TRACK();
        }
        else
        {
            cform_add();
        }
	}
	elseif ($_SESSION['call']=="view")
	{
		//cform_view();
        if ($_SESSION['otype']==2)
        {
            cform_view_VENDOR();
        }
		elseif ($_SESSION['otype']==3)
        {
            cform_view_TRACK();
        }
        else
        {
			@cform_view();
        }
	}
	elseif ($_SESSION['call']=="edit")
	{
        if ($_SESSION['otype']==2)
        {
            cform_edit_VENDOR();
        }
		elseif ($_SESSION['otype']==3)
        {
            cform_edit_TRACK();
        }
        else
        {
			//echo 'Edit';
            cform_edit();
        }
	}
	elseif ($_SESSION['call']=="delete")
	{
		cform_delete();
	}
	elseif ($_SESSION['call']=="search")
	{
		//echo $_SESSION['call']."<br>";
		lead_search();
	}
	elseif ($_SESSION['call']=="sales_search")
	{
		//echo $_SESSION['call']."<br>";
		sales_search();
	}
	elseif ($_SESSION['call']=="search_results")
	{
		if ($_SESSION['otype']==2)
        {
            listleads_VENDOR();
        }
        elseif ($_SESSION['otype']==3)
        {
            listleads_TRACK();    
        }
        else
        {
            listleads();
        }
	}
	elseif ($_SESSION['call']=="showcalendar")
	{
		showMonth_full();
	}
	elseif ($_SESSION['call']=="showday_expanded")
	{
		showMonth_full();
		//showDay_expanded();
	}
	elseif ($_SESSION['call']=="chistory_add")
	{
		chistory_add();
	}
	elseif ($_SESSION['call']=="chistory")
	{
		//echo "HISTORY";
		chistory_list();
	}
	elseif ($_SESSION['call']=="set_digdate")
	{
		set_digdate();
	}
	elseif ($_SESSION['call']=="set_clsdate")
	{
		set_clsdate();
	}
	elseif ($_SESSION['call']=="set_condate")
	{
		set_condate();
	}
	elseif ($_SESSION['call']=="add_fin_detail")
	{
		finan_form_add();
	}
	elseif ($_SESSION['call']=="add_fin_detail2")
	{
		finan_form_add2();
	}
	elseif ($_SESSION['call']=="view_fin_detail")
	{
		finan_form_view();
	}
	elseif ($_SESSION['call']=="updt_fin_detail")
	{
		finan_form_updt();
	}
	elseif ($_SESSION['call']=="finan_status_update")
	{
		finan_status_update();
	}
	elseif ($_SESSION['call']=="procemaillist")
	{
		procemaillist();
	}
	elseif ($_SESSION['call']=="exports")
	{
		lead_export();
	}
    elseif ($_SESSION['call']=="sendetemp_fromPreview")
    {
        process_template_email();
	}
}

function move_leads()
{
	error_reporting(E_ALL);
	// This function will move leads, by salesrep id between office or interoffice.
	// show_post_vars();
	//if ($_SESSION['securityid']==MTRX_ADMIN || $_SESSION['securityid']==SYS_ADMIN)
	//{
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
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		
		if ($_REQUEST['subq']=="move1")
		{
			echo "<input type=\"hidden\" name=\"subq\" value=\"move2\">\n";
		}
		elseif ($_REQUEST['subq']=="move2")
		{
			echo "<input type=\"hidden\" name=\"subq\" value=\"move3\">\n";	
		}
		elseif ($_REQUEST['subq']=="move3")
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
		
		if ($_REQUEST['subq']=="move1")
		{
			$qryF  	= "SELECT officeid as foid,name as fname FROM offices WHERE active=1 ORDER by grouping,name ASC;";
			$resF  	= mssql_query($qryF);
			
			echo "						<select name=\"foid\">\n";
			
			while ($rowF  	= mssql_fetch_array($resF))
			{
				if (!empty($_REQUEST['foid']) && $_REQUEST['foid']==$rowF['foid'])
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
		elseif ($_REQUEST['subq']=="move2" || $_REQUEST['subq']=="move3" || $_REQUEST['subq']=="move4")
		{
			$qryF  	= "SELECT officeid as foid,name as fname FROM offices WHERE officeid='".$_REQUEST['foid']."';";
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
		
		if ($_REQUEST['subq']=="move1")
		{
			$qryT  	= "SELECT officeid as toid,name as tname FROM offices WHERE active=1 ORDER by grouping,name ASC;";
			$resT  	= mssql_query($qryT);
			
			echo "						<select name=\"toid\">\n";
			
			while ($rowT  	= mssql_fetch_array($resT))
			{
				if (!empty($_REQUEST['toid']) && $_REQUEST['toid']==$rowT['toid'])
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
		elseif ($_REQUEST['subq']=="move2" || $_REQUEST['subq']=="move3" || $_REQUEST['subq']=="move4")
		{
			$qryT  	= "SELECT officeid as toid,name as tname FROM offices WHERE officeid='".$_REQUEST['toid']."';";
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
	
		if ($_REQUEST['subq']=="move2" || $_REQUEST['subq']=="move3" || $_REQUEST['subq']=="move4")
		{
			$qryFa  	= "SELECT securityid as fsid,fname as ffname,lname as flname,substring(slevel,13,1) as slevel FROM security WHERE officeid='".$_REQUEST['foid']."' ORDER by substring(slevel,13,1) desc,flname ASC;";
			$resFa  	= mssql_query($qryFa);
			
			$qryTa  	= "SELECT securityid as tsid,fname as tfname,lname as tlname FROM security WHERE officeid='".$_REQUEST['toid']."' and substring(slevel,13,1)=1 ORDER by tlname ASC;";
			$resTa  	= mssql_query($qryTa);
			
			echo "	<tr>\n";
			echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>From SalesRep:</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
			
			if ($_REQUEST['subq']=="move2")
			{
				echo "						<select name=\"fsid\">\n";
				
				while ($rowFa  = mssql_fetch_array($resFa))
				{
					if ($rowFa['slevel'] >= 1)
					{
						$otype1='fontblack';
					}
					else
					{
						$otype1='fontred';
					}
					
					if (!empty($_REQUEST['fsid']) && $_REQUEST['fsid']==$rowFa['fsid'])
					{
						echo "<option class=\"".$otype1."\" value=\"".$rowFa['fsid']."\" SELECTED>".$rowFa['flname'].", ".$rowFa['ffname']."</option>\n";
					}
					else
					{
						echo "<option class=\"".$otype1."\" value=\"".$rowFa['fsid']."\">".$rowFa['flname'].", ".$rowFa['ffname']."</option>\n";
					}
				}
				
				echo "						</select>\n";
			}
			elseif ($_REQUEST['subq']=="move3" || $_REQUEST['subq']=="move4")
			{
				$qryFa  = "SELECT securityid as fsid,fname as ffname,lname as flname FROM security WHERE officeid='".$_REQUEST['foid']."' and securityid='".$_REQUEST['fsid']."';";
				$resFa  = mssql_query($qryFa);
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
			
			if ($_REQUEST['subq']=="move2")
			{
				echo "						<select name=\"tsid\">\n";
				
				while ($rowTa 	= mssql_fetch_array($resTa))
				{
					if (!empty($_REQUEST['tsid']) && $_REQUEST['tsid']==$rowTa['tsid'])
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
			elseif ($_REQUEST['subq']=="move3" || $_REQUEST['subq']=="move4")
			{
				$qryTa  	= "SELECT securityid as tsid,fname as tfname,lname as tlname FROM security WHERE officeid='".$_REQUEST['toid']."' and securityid='".$_REQUEST['tsid']."';";
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
			
			if ($_REQUEST['subq']=="move2")
			{
				echo "						<input class=\"bboxb\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
				echo "						<input class=\"bboxb\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
			}
			elseif ($_REQUEST['subq']=="move3" || $_REQUEST['subq']=="move4")
			{
				echo date("m/d/y",strtotime($_REQUEST['d1']))." - ".date("m/d/y",strtotime($_REQUEST['d2']));
				echo "						<input type=\"hidden\" name=\"d1\" value=\"".date("m/d/y",strtotime($_REQUEST['d1']))."\">\n";
				echo "						<input type=\"hidden\" name=\"d2\" value=\"".date("m/d/y",strtotime($_REQUEST['d2']))."\">\n";
			}
			
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			
			if ($_REQUEST['subq']=="move3" || $_REQUEST['subq']=="move4")
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
				
				if ($_REQUEST['subq']=="move3")
				{
					$qryL  	= "SELECT cid FROM cinfo WHERE officeid=".$_REQUEST['foid']." ";
					$qryL   .= "and securityid=".$_REQUEST['fsid']." and estid=0 and jobid='0' and njobid='0' ";
					$qryL   .= "and added >= '".$_REQUEST['d1']."' and added <= '".$_REQUEST['d2']." 11:59:59';";
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
				elseif ($_REQUEST['subq']=="move4")
				{
					echo $_REQUEST['lcnt'];
					//echo "<input type=\"hidden\" name=\"lcnt\" value=\"".$_REQUEST['lcnt']."\">\n";
				}
				
				echo "					</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
				echo "		</td>\n";
				echo "		<td align=\"left\" width=\"50%\" valign=\"top\">\n";
				
				if ($_REQUEST['subq']=="move4")
				{					
					echo "			<table class=\"outer\" width=\"100%\">\n";
					echo "				<tr>\n";
					echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>Leads Moved:</b></td>\n";
					echo "				</tr>\n";
					echo "				<tr>\n";
					echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
					
					$err=0;
					if (is_array($_REQUEST['cids']))
					{
						$lcnt=0;
						foreach ($_REQUEST['cids'] as $cn => $cv)
						{
							$uid   = md5($cv).$_SESSION['securityid'];
							$qryU  = "DECLARE @tmid int ";
							$qryU .= "DECLARE @oname char(20) ";
							$qryU .= "SET @tmid=((SELECT MAX(custid) FROM cinfo WHERE officeid=".$_REQUEST['toid'].") + 1) ";
							$qryU .= "SET @oname=(SELECT name FROM offices WHERE officeid=".$_REQUEST['foid'].") ";
							$qryU .= "BEGIN TRAN ";
							$qryU .= "UPDATE cinfo SET officeid='".$_REQUEST['toid']."',securityid='".$_REQUEST['tsid']."',custid=@tmid WHERE officeid='".$_REQUEST['foid']."' and cid='".$cv."' ";
							$qryU .= "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
							$qryU .= "VALUES ";
							$qryU .= "('".$cv."','".$_REQUEST['foid']."','".$_SESSION['securityid']."','leads','Lead Moved from ' + @oname,'".$uid."')";
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
		elseif ($_SESSION['subq']=="move3" and (isset($nrowL)) and $nrowL > 0)
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
	//}
	//else
	//{
	//	die('You do not have appropriate access Rights to View this Resource');
	//}
}

function add_finan_cust($oid,$orig_oid,$cid,$sid,$uid)
{
	//echo "Adding WinFin<br>";
	error_reporting(E_ALL);
	$nsecid		=0;
	$fsecid		=0;
	
	if (isset($_REQUEST['finansrc']) && $_REQUEST['finansrc']!=1)
	{
		$finan_src	=$_REQUEST['finansrc']; // Submitted
	}
	else
	{
		$finan_src	=4; // BH Finance	
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
		$qry0  	= "SELECT name,gm,am,finan_from,finan_rep as fsecid FROM offices WHERE officeid='".$orig_oid."';";
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

		if (isset($row0['fsecid']) && $row0['fsecid']!=0)
		{
			$fsecid=$row0['fsecid'];
		}
		else
		{
			$fsecid=0;
		}

		$qry1   = "UPDATE cinfo SET finan_from='".$orig_oid."',finan_sec='".$nsecid."',finan_src='".$finan_src."',finan_date=getdate() WHERE officeid=".$oid." AND cid=".$cid.";";
		$res1   = mssql_query($qry1);
		//echo $qry1."<br>";

		if ($nrow0a==0)
		{
			$qry1a  = "INSERT INTO tfinan_detail (cid,officeid,finan_from,financlose,recdate,uid,assigned) VALUES ('".$cid."','".$oid."','".$orig_oid."',0,getdate(),'".$uid."','".$fsecid."');";
			$res1a  = mssql_query($qry1a);
		}

		$qry2   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
		$qry2  .= "VALUES ";
		$qry2  .= "('".$cid."','".$oid."','".$_SESSION['securityid']."','leads','".$ctext."','".$uid."')";
		$res2  = mssql_query($qry2);
	}
}

function upd_zap()
{
	$qry = "SELECT id FROM zip_link WHERE id='".$_REQUEST['coid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	echo $nrow."<br>";
	
	if ($nrow > 0)
	{
		$qry1 = "UPDATE zip_link SET zip='".$_REQUEST['ozip']."',area='".$_REQUEST['area']."',pre='".$_REQUEST['pre']."' WHERE id='".$_REQUEST['coid']."';";
		$res1 = mssql_query($qry1);
	}
	
	zip_search();
}

function upd_ringto()
{
	$qry = "SELECT officeid FROM offices WHERE officeid='".$_REQUEST['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	//echo $nrow."<br>";
	
	if ($nrow > 0 && $_REQUEST['oringto']!=$_REQUEST['nringto'])
	{	
		$qry2 = "UPDATE offices SET ringto='".$_REQUEST['nringto']."' WHERE officeid='".$_REQUEST['officeid']."';";
		$res2 = mssql_query($qry2);
	}
	
	zip_search();
}

function upfile1()
{
	// Clear previous uploads
	unset($_SESSION['imp_stage']);
	unset($_SESSION['imp_results']);
	unset($_SESSION['imp_errors']);
	
	$uid  = md5(session_id().time()).".".$_SESSION['securityid'];
	$_SESSION['puid'] = $uid;
	
	$qry0 = "SELECT * FROM leadstatuscodes WHERE statusid > 1 and active = 2 and ivr!=1 order by name asc;";
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
	//$uid	=$_REQUEST['uid'];
	$source	=$_REQUEST['source'];
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
							'Comments',
							'Comments2',
							'Comments3'
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
		$uploaddir = 'E:\\uploadtemp\\';
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
			echo "<input type=\"hidden\" name=\"source\" value=\"".$_REQUEST['source']."\">\n";
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
			echo "					<td class=\"gray\" align=\"left\">\n";
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
				echo "					<td class=\"gray\" align=\"left\"><br>Contains <b>".$fl."</b> Lines</td>\n";
				echo "				</tr>\n";	
			}
			else
			{
				echo "				<tr>\n";
				echo "					<td class=\"gray\" align=\"left\"><font color=\"red\">Format Invalid or not CSV file!</font></td>\n";
				echo "				</tr>\n";
				$err++;
			}
			
			if ($err > 0)
			{
				$dis="DISABLED";
			}

			if ($_SESSION['securityid']==26)
			{
				echo "				<tr>\n";
				echo "					<td class=\"gray\" align=\"left\">\n";
				echo "						Enable Debug: <input class=\"checkboxgry\" type=\"checkbox\" name=\"en_debug\" value=\"1\">\n";
				echo "					</td>\n";
				echo "				</tr>\n";
			}
			
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"center\">\n";			
			echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Verify\" ".$dis.">\n";
			echo "					</td>\n";
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
	$sh_err			=0;
	
	if (isset($_REQUEST['en_debug']) && $_REQUEST['en_debug']==1)
	{
		$en_temp_content=1;
	}
	else
	{
		$en_temp_content=0;
	}
	
	if (!isset($_SESSION['puid']))
	{
		die('Process Transition Error!');
	}
	
	if (empty($_REQUEST['Date']))
	{
		die('Date Header not set!');
	}
	
	if (empty($_REQUEST['FirstName']))
	{
		die('FirstName Header not set!');
	}
	
	if (empty($_REQUEST['LastName']))
	{
		die('LastName Header not set!');
	}
	
	if (empty($_REQUEST['Address1']))
	{
		die('Address1 Header not set!');
	}
	
	if (empty($_REQUEST['Address2']))
	{
		die('Address2 Header not set!');
	}
	
	if (empty($_REQUEST['City']))
	{
		die('City Header not set!');
	}
	
	if (empty($_REQUEST['State']))
	{
		die('State Header not set!');
	}
	
	if (empty($_REQUEST['Zip']))
	{
		die('Zip Header not set!');
	}
	
	if (empty($_REQUEST['Phone']))
	{
		die('Phone Header not set!');
	}
	
	if (empty($_REQUEST['Email']))
	{
		die('Email Header not set!');
	}
	
	if (empty($_REQUEST['Comments']))
	{
		die('Comments Header not set!');
	}
	
	if (empty($_REQUEST['source']))
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
	
	$uploaddir = 'E:\\uploadtemp\\';
	
	if (!isset($_REQUEST['impfile']) && !file_exists($uploaddir.$_REQUEST['impfile']))
	{
		echo "<font color=\"\">Error</font>: File not retained.";
		exit;
	}
	else
	{
		$qry0 = "SELECT statusid,name FROM leadstatuscodes WHERE statusid = '".$_REQUEST['source']."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		$src  = array($row0['statusid'],$row0['name']);
		
		$uploaddir = 'E:\\uploadtemp\\';
		$uploadfile = $uploaddir . basename($_REQUEST['impfile']);

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
					
					//if (!empty($data[$_REQUEST['Date'][1]]) && valid_date($data[$_REQUEST['Date'][1]])) // Tests & Cleans Date Data
					if (!empty($data[$_REQUEST['Date'][1]]) && strtotime($data[$_REQUEST['Date'][1]]) > strtotime('1/1/2000')) // Tests & Cleans Date Data
					{
						if ($sh_err==1)
						{
							echo "Date: (".$fl.") ".$data[$_REQUEST['Date'][1]]."<br>";
						}
						$cond_data[$fl]['Date']=$data[$_REQUEST['Date'][1]];
					}
					else
					{
						if ($sh_err==1)
						{
							echo "Date: (".$fl.") ".date("m/d/y",time())."<br>";
						}
						$cond_data[$fl]['Date']=date("m/d/y",time());
					}
					
					if (!empty($data[$_REQUEST['FirstName'][1]]) && is_numeric($_REQUEST['FirstName'][1]))	// Tests FirstName Data
					{
						if (strlen($data[$_REQUEST['FirstName'][1]]) >= 1)
						{
							if ($sh_err==1)
							{
								echo "FirstName: (".$fl.") ".ucfirst(filter_var($data[$_REQUEST['FirstName'][1]]))."<br>";
							}
							$cond_data[$fl]['FirstName']=ucfirst(filter_var($data[$_REQUEST['FirstName'][1]]));
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
				
					if (is_numeric($_REQUEST['LastName'][1]) && !empty($data[$_REQUEST['LastName'][1]])) // Tests LastName Data
					{
						if (!empty($data[$_REQUEST['LastName'][1]]) && !is_numeric($data[$_REQUEST['LastName'][1]]))
						{
							if ($sh_err==1)
							{
								echo "LastName: (".$fl.") ".ucfirst(filter_var($data[$_REQUEST['LastName'][1]]))."<br>";
							}
							$cond_data[$fl]['LastName']=ucfirst(filter_var($data[$_REQUEST['LastName'][1]]));
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
				
					if (is_numeric($_REQUEST['Address1'][1]) && !empty($data[$_REQUEST['Address1'][1]])) // Tests Address1 Data
					{
						if (!is_numeric($data[$_REQUEST['Address1'][1]]))
						{
							if ($sh_err==1)
							{
								echo "Address1: (".$fl.") ".$data[$_REQUEST['Address1'][1]]."<br>";
							}
							$cond_data[$fl]['Address1']=filter_var($data[$_REQUEST['Address1'][1]]);
						}
					}
				
					if (is_numeric($_REQUEST['Address2'][1]) && !empty($data[$_REQUEST['Address2'][1]]) && strlen($data[$_REQUEST['Address2'][1]]) > 2) // Tests Address2 Data
					{
						if ($sh_err==1)
						{
							echo "Address2: (".$fl.") ".$data[$_REQUEST['Address2'][1]]."<br>";
						}
						$cond_data[$fl]['Address2']=filter_var($data[$_REQUEST['Address2'][1]]);
					}
				
					if (is_numeric($_REQUEST['City'][1]) && !empty($data[$_REQUEST['City'][1]]) && strlen($data[$_REQUEST['City'][1]]) >= 2) // Tests City Data
					{
						if ($sh_err==1)
						{
							echo "City: (".$fl.") ".$data[$_REQUEST['City'][1]]."<br>";
						}
						$cond_data[$fl]['City']=filter_var($data[$_REQUEST['City'][1]]);
					}

					if (is_numeric($_REQUEST['State'][1]) && !empty($data[$_REQUEST['State'][1]]) && strlen($data[$_REQUEST['State'][1]]) >= 2) // Tests State Data
					{
						if ($sh_err==1)
						{
							echo "State: (".$fl.") ".$data[$_REQUEST['State'][1]]."<br>";
						}
						$cond_data[$fl]['State']=substr(filter_var($data[$_REQUEST['State'][1]]),0,2);
					}

					if (is_numeric($_REQUEST['Zip'][1]) && !empty($data[$_REQUEST['Zip'][1]])) // Tests & Cleans Zip Code Data
					{
						if (is_numeric($data[$_REQUEST['Zip'][1]]) && strlen($data[$_REQUEST['Zip'][1]])==5)
						{
							if ($sh_err==1)
							{
								echo "Zip: (".$fl.") ".filter_var($data[$_REQUEST['Zip'][1]])."<br>";
							}
							$cond_data[$fl]['Zip']=filter_var($data[$_REQUEST['Zip'][1]]);
						}
						elseif (is_numeric($data[$_REQUEST['Zip'][1]]) && strlen($data[$_REQUEST['Zip'][1]]) < 5)
						{
							if ($sh_err==1)
							{
								echo "Zip Padded: (".$fl.") ".str_pad($data[$_REQUEST['Zip'][1]], 5, "0", STR_PAD_LEFT)."<br>";
							}
							$cond_data[$fl]['Zip']=str_pad($data[$_REQUEST['Zip'][1]], 5, "0", STR_PAD_LEFT);
						}
						elseif (strlen($data[$_REQUEST['Zip'][1]]) > 5 && preg_match('/-/',$data[$_REQUEST['Zip'][1]]))
						{
							$fzip=split("-",$data[$_REQUEST['Zip'][1]]);
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
						else //if (!is_numeric($data[$_REQUEST['Zip'][1]]) && strlen($data[$_REQUEST['Zip'][1]]) >= 1)
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
							echo "Zip Padded: (".$fl.") ".str_pad($data[$_REQUEST['Zip'][1]], 5, "0", STR_PAD_LEFT)."<br>";
						}
						$cond_data[$fl]['Zip']=str_pad($data[$_REQUEST['Zip'][1]], 5, "0", STR_PAD_LEFT);
					}
				
					if (!empty($data[$_REQUEST['Phone'][1]]) && is_numeric($_REQUEST['Phone'][1]) && strlen($data[$_REQUEST['Phone'][1]]) >= 1) // Tests Phone Data
					{
						if (strlen($data[$_REQUEST['Phone'][1]]) <= 10)
						{
							if ($sh_err==1)
							{
								echo "Phone: (".$fl.") ".str_pad(filter_var($data[$_REQUEST['Phone'][1]]), 10, "0", STR_PAD_LEFT)."<br>";
							}
							$cond_data[$fl]['Phone']=str_pad(filter_var($data[$_REQUEST['Phone'][1]]), 10, "0", STR_PAD_LEFT);
						}
						elseif (strlen($data[$_REQUEST['Phone'][1]]) > 10)
						{
							if ($sh_err==1)
							{
								echo "Phone: (".$fl.") ".preg_replace('/[-() ]+/','',filter_var($data[$_REQUEST['Phone'][1]]))."<br>";
							}
							$cond_data[$fl]['Phone']=preg_replace('/[-() ]+/','',filter_var($data[$_REQUEST['Phone'][1]]));
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
							echo "Phone: (".$fl.") ".str_pad(filter_var($data[$_REQUEST['Phone'][1]]), 10, "0", STR_PAD_LEFT)."<br>";
						}
						$cond_data[$fl]['Phone']=str_pad(filter_var($data[$_REQUEST['Phone'][1]]), 10, "0", STR_PAD_LEFT);
					}
				
					if	(
							is_numeric($_REQUEST['Email'][1])
							&& !empty($data[$_REQUEST['Email'][1]])
							&& preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i',$data[$_REQUEST['Email'][1]])
						) // Tests Email Data
					{
						if ($sh_err==1)
						{
							echo "Email: (".$fl.") ".filter_var($data[$_REQUEST['Email'][1]])."<br>";
						}
						$cond_data[$fl]['Email']=$data[$_REQUEST['Email'][1]];
					}
				
					// Additional Data Tags based upon Source Code
					
					if ($cond_data[$fl]['SourceID']==44) //poolsearch.com
					{
						$cond_data[$fl]['Comments']  = "From poolsearch.com website (free lead arranged by BHNM).";
					}
					else
					{
						$cond_data[$fl]['Comments']  = "";
					}
				
					if (is_numeric($_REQUEST['Comments'][1]) && !empty($data[$_REQUEST['Comments'][1]])) // Tests & Cleans Comments Data
					{
						if ($sh_err==1)
						{
							echo "Comments: (".$fl.") ".filter_var($data[$_REQUEST['Comments'][1]])."<br>";
						}
						$cond_data[$fl]['Comments']=$cond_data[$fl]['Comments'] ." ". filter_var($data[$_REQUEST['Comments'][1]]);
						
						if (is_numeric($_REQUEST['Comments2'][1]) && !empty($data[$_REQUEST['Comments2'][1]])) // Tests & Cleans Comments Data
						{
							if ($sh_err==1)
							{
								echo "Comments2: (".$fl.") ".filter_var($data[$_REQUEST['Comments2'][1]])."<br>";
							}
							$cond_data[$fl]['Comments']=$cond_data[$fl]['Comments'] ." ". filter_var($data[$_REQUEST['Comments2'][1]]);
						}
						
						if (is_numeric($_REQUEST['Comments3'][1]) && !empty($data[$_REQUEST['Comments3'][1]])) // Tests & Cleans Comments Data
						{
							if ($sh_err==1)
							{
								echo "Comments3: (".$fl.") ".filter_var($data[$_REQUEST['Comments3'][1]])."<br>";
							}
							$cond_data[$fl]['Comments']=$cond_data[$fl]['Comments'] ." ". filter_var($data[$_REQUEST['Comments3'][1]]);
						}
						//$cond_data[$fl]['Comments']=filter_var($data[$_REQUEST['Comments'][1]]);
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
					$qry0 .= " added"; //0
					$qry0 .= ",submitted"; //1
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
					$qry0 .= " '".date("m/d/Y",time())."'"; //1
					$qry0 .= ",'".date("m/d/Y",strtotime($tv['Date']))."'"; //1
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
			if ($en_temp_content == 1)
			{
				if (is_array($cond_data) && count($cond_data) > 0)
				{
					$tx=1;
					echo "<pre>\n";
					foreach ($cond_data as $tn=>$tv)
					{
						//$qry0 = $tn.": ";
							$qry0  = "INSERT INTO lead_inc ";
							$qry0 .= "( ";
							$qry0 .= " added"; //0
							$qry0 .= ",submitted"; //0
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
							$qry0 .= " '".date("m/d/Y",time())."'"; //0
							$qry0 .= ",'".date("m/d/Y",strtotime($tv['Date']))."'"; //0
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
					echo "</pre>\n";
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
		
		/*echo "<table class=\"outer\" align=\"center\" width=\"35%\">\n";
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
		echo "</table>\n";*/
		
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
		/*$qry0  = "DECLARE @m varchar(2) ";
		$qry0 .= "DECLARE @y varchar(4) ";
		$qry0 .= "DECLARE @d varchar(10) ";
		$qry0 .= "SET @m = (SELECT smo FROM bonus_schedule_config WHERE brept_yr=(SELECT MAX(brept_yr) FROM bonus_schedule_config)) ";
		$qry0 .= "SET @y = (SELECT syr FROM bonus_schedule_config WHERE brept_yr=(SELECT MAX(brept_yr) FROM bonus_schedule_config)) ";
		$qry0 .= "SET @d = (@m + '/01/' + @y) ";*/
		$qry0  = "DECLARE @b varchar(4) ";
		$qry0 .= "DECLARE @m varchar(2) ";
		$qry0 .= "DECLARE @y varchar(4) ";
		$qry0 .= "DECLARE @d varchar(10) ";
		$qry0 .= "SET @b = (SELECT MAX(brept_yr) FROM bonus_schedule_config) ";
		$qry0 .= "IF (@b = '2008') ";
		$qry0 .= "	BEGIN ";
		$qry0 .= "		SET @d = '06/01/08' ";
		$qry0 .= "	END ";
		$qry0 .= "ELSE ";
		$qry0 .= "	BEGIN ";
		$qry0 .= "		SET @m = (SELECT smo FROM bonus_schedule_config WHERE brept_yr=@b) ";
		$qry0 .= "		SET @y = (SELECT syr FROM bonus_schedule_config WHERE brept_yr=@b) ";
		$qry0 .= "		SET @d = (@m + '/01/' + @y) ";
		$qry0 .= "	END	";
		$qry0 .= "SELECT @d as sdate,getdate() as edate ";
		$res0  = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);

		/*$qry  = "DECLARE @b varchar(4) ";
		$qry .= "DECLARE @m varchar(2) ";
		$qry .= "DECLARE @y varchar(4) ";
		$qry .= "DECLARE @d varchar(10) ";
		$qry .= "SET @m = (SELECT smo FROM bonus_schedule_config WHERE brept_yr=(SELECT MAX(brept_yr) FROM bonus_schedule_config)) ";
		$qry .= "SET @y = (SELECT syr FROM bonus_schedule_config WHERE brept_yr=(SELECT MAX(brept_yr) FROM bonus_schedule_config)) ";
		$qry .= "SET @d = (@m + '/01/' + @y) ";*/
		
		$qry  = "DECLARE @b varchar(4) ";
		$qry .= "DECLARE @m varchar(2) ";
		$qry .= "DECLARE @y varchar(4) ";
		$qry .= "DECLARE @d varchar(10) ";
		$qry .= "SET @b = (SELECT MAX(brept_yr) FROM bonus_schedule_config) ";
		$qry .= "IF (@b = '2008') ";
		$qry .= "	BEGIN ";
		$qry .= "		SET @d = '06/01/08' ";
		$qry .= "	END ";
		$qry .= "ELSE ";
		$qry .= "	BEGIN ";
		$qry .= "		SET @m = (SELECT smo FROM bonus_schedule_config WHERE brept_yr=@b) ";
		$qry .= "		SET @y = (SELECT syr FROM bonus_schedule_config WHERE brept_yr=@b) ";
		$qry .= "		SET @d = (@m + '/01/' + @y) ";
		$qry .= "	END	";
		$qry .= "SELECT ";
		$qry .= "	officeid,name,city,phone,am,zip ";
		$qry .= "	,(SELECT count(id) FROM zip_to_zip WHERE ozip=o.zip) as mcnt ";
		$qry .= "	,(SELECT lname FROM security WHERE securityid=o.am) as lname ";
		$qry .= "	,(SELECT fname FROM security WHERE securityid=o.am) as fname ";
		$qry .= "	,(SELECT substring(slevel,13,1) FROM security WHERE securityid=o.am) as lactive ";
		$qry .= "	,(SELECT curr_login FROM security WHERE securityid=o.am) as curr_login ";
		//$qry .= "	,(SELECT laccess FROM security WHERE securityid=o.am) as laccess, ";
		$qry .= "	,(SELECT count(cid) FROM cinfo WHERE officeid=o.officeid AND dupe!=1 AND added BETWEEN @d AND getdate()) as tlcnt ";
		//$qry .= "	,(SELECT count(cid) FROM cinfo WHERE officeid=o.officeid AND dupe!=1 and viewedby!=0 AND added BETWEEN @d AND getdate() AND viewedby IN (SELECT securityid FROM security WHERE officeid=o.officeid)) as tvcnt ";
		//$qry .= "	,(SELECT count(cid) FROM cinfo WHERE officeid=o.officeid AND dupe!=1 and ccontact!=0 AND added BETWEEN @d AND getdate() AND ccontactby IN (SELECT securityid FROM security WHERE officeid=o.officeid)) as tccnt ";
		//$qry .= "	,(SELECT count(cid) FROM cinfo WHERE officeid=o.officeid AND dupe!=1 and viewedby !=0 AND added BETWEEN @d AND getdate()) as tvcnt ";
		$qry .= "	,(SELECT count(cid) FROM cinfo WHERE officeid=o.officeid AND dupe!=1 and viewedby not in (select securityid from jest..security where officeid=89 and securityid!=1950) AND added BETWEEN @d AND getdate()) as tvcnt ";
		$qry .= "	,(SELECT count(cid) FROM cinfo WHERE officeid=o.officeid AND dupe!=1 and ccontact!=0 AND added BETWEEN @d AND getdate()) as tccnt ";
		//$qry .= "	,(SELECT count(custid) FROM chistory WHERE custid=o.officeid AND dupe!=1 and ccontact!=0 AND added BETWEEN @d AND getdate()) as tccnt ";
		/*$qry .= "	,( ";
		$qry .= "		(SELECT count(cid) FROM cinfo WHERE officeid=o.officeid AND dupe!=1 AND added BETWEEN @d AND getdate()) -  ";
		$qry .= "		(SELECT count(cid) FROM cinfo WHERE officeid=o.officeid AND dupe!=1 AND added BETWEEN @d AND getdate() and viewedby=0) ";
		$qry .= "	) as luperc ";*/
		$qry .= "FROM ";
		$qry .= "	offices AS o ";
		$qry .= "WHERE ";
		$qry .= "	active=1 AND grouping=0 ORDER BY grouping,name ASC";
		$res  = mssql_query($qry);

		//echo $qry."<br>";
		echo "<table class=\"outer\" align=\"center\" border=0 width=\"750\">\n";
		echo "	<tr>\n";
		echo "   	<td class=\"gray\">\n";
		echo "			<table align=\"center\" width=\"100%\" border=0 cellpadding=2>\n";
		echo "				<tr>\n";
		echo "   				<td class=\"gray\" colspan=\"5\"><b>Lead Information Report</b></td>\n";
		echo "   				<td class=\"gray_lside1px\" colspan=\"6\" align=\"center\"><b>".date("m/d/y",strtotime($row0['sdate']))." - ".date("m/d/y",strtotime($row0['edate']))."</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Office</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Phone</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Lead Adm</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Last Login</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Matrix</b></td>\n";
		echo "					<td class=\"ltgray_undlside1px\" align=\"center\"><b>Total Leads</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\" title=\"Total number of leads added in the above timeframe minus duplicates\"><font color=\"blue\">?</font></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Viewed</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\" title=\"Total number of leads added in the above timeframe that have been Viewed by a representative from that Office\"><font color=\"blue\">?</font></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Contacted</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\" title=\"Total number of leads added in the above timeframe that have been Contacted by a representative from that Office\"><font color=\"blue\">?</font></td>\n";
		echo "				</tr>\n";

		while ($row = mssql_fetch_array($res))
		{
			if ($row['lactive'] > 0)
			{
				$lfnt="black";
			}
			else
			{
				$lfnt="red";
			}
			
			if ($row['tlcnt'] != 0)
			{
				$tlfnt="black";
			}
			else
			{
				$tlfnt="red";
			}
			
			if ($row['tvcnt'] == $row['tlcnt'])
			{
				$tvfnt="black";
			}
			else
			{
				$tvfnt="red";
			}
			
			if ($row['tccnt'] == $row['tlcnt'])
			{
				$tcfnt="black";
			}
			else
			{
				$tcfnt="red";
			}
			
			echo "				<tr>\n";
			echo "					<td class=\"wh_und\">".$row['name']."</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$row['phone']."</td>\n";
			echo "					<td class=\"wh_und\"><font color=\"".$lfnt."\">".$row['fname']." ".$row['lname']."</font></td>\n";
			echo "					<td class=\"wh_und\" align=\"center\"><font color=\"".$lfnt."\">".date("m/d/y h:m a",strtotime($row['curr_login']))."</font></td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">".$row['mcnt']."</td>\n";
			echo "					<td colspan=\"2\" class=\"wh_undsidesl\" align=\"center\"><font color=\"".$tlfnt."\">".$row['tlcnt']."</font></td>\n";
			echo "					<td colspan=\"2\" class=\"wh_undsidesl\" align=\"center\"><font color=\"".$tvfnt."\">".$row['tvcnt']."</font></td>\n";
			echo "					<td colspan=\"2\" class=\"wh_undsidesl\" align=\"center\"><font color=\"".$tcfnt."\">".$row['tccnt']."</font></td>\n";
			echo "				</tr>\n";
		}

		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function lead_export()
{
	$qry1  = "SELECT  ";
	$qry1 .= "	 s.securityid ";
	$qry1 .= "	,s.lname ";
	$qry1 .= "	,s.fname ";
	$qry1 .= "	,s.slevel ";
	$qry1 .= "	,(SELECT count(cid) FROM cinfo WHERE dupe=0 AND jobid='0' AND securityid=S.securityid) as lcnt ";
	$qry1 .= "FROM  ";
	$qry1 .= "	security AS s ";
	$qry1 .= "WHERE  ";
	$qry1 .= "	s.officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=$_SESSION['securityid'] && $_SESSION['asstto']!=0)
		{
			$qry1 .= "	and s.sidm=".$_SESSION['asstto']." OR s.securityid=".$_SESSION['securityid']." ";
		}
		else
		{
			$qry1 .= "	and s.sidm=".$_SESSION['securityid']." OR s.securityid=".$_SESSION['securityid']." ";
		}
	}
	elseif ($_SESSION['llev'] <= 3)
	{
		$qry1 .= "	and s.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry1 .= "ORDER BY ";
	$qry1 .= "	SUBSTRING(s.slevel,13,1) DESC, ";
	$qry1 .= "	s.lname ASC; ";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	$qry2 = "SELECT ldexport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
	
	$qry3 = "SELECT securityid,officeid,lname,fname,slevel,gmreports FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
	
	if ($_SESSION['officeid']!=89)
	{
		$qryOF = "SELECT officeid,name FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	}
	else
	{
		$qryOF = "SELECT officeid,name FROM offices WHERE grouping!=3 ORDER BY grouping,name ASC;";	
	}
	
	$resOF = mssql_query($qryOF);
	
	if ($_SESSION['officeid']!=89)
	{
		$qryLS = "SELECT * FROM leadstatuscodes WHERE active=2 AND ivr!=1 and oid=0 or oid=".$_SESSION['officeid']." ORDER BY name ASC;";
	}
	else
	{
		$qryLS = "SELECT * FROM leadstatuscodes WHERE active=2 AND ivr!=1 ORDER BY name ASC;";
	}
	
	$resLS = mssql_query($qryLS);
	
	$qryLR = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$resLR = mssql_query($qryLR);

	echo "<table width=\"400\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\">\n";
	echo "         				<form name=\"ldexport1\" action=\"export/ldexport.php\" method=\"post\" target=\"_new\">\n";
	echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td class=\"ltgray_und\" align=\"left\"><b>Lead Export</b></td>\n";
	echo "                              <td class=\"ltgray_und\" align=\"right\"><img class=\"JMStooltip\" src=\"images/help.png\" title=\"Use this Export Tool to Extract Lead Records for Mailing Lists or Email Campaigns\"></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "                          	<td align=\"right\"><b>Office</b></td>\n";
	echo "                          	<td align=\"left\">\n";
	echo "                          		<select name=\"oid\">\n";
	
	if ($_SESSION['officeid']==89)
	{
		echo "										<option value=\"0\">All</option>\n";
		echo "										<option value=\"0\">------</option>\n";
	}
	
	while ($rowOF = mssql_fetch_array($resOF))
	{
		echo "                                    		<option value=\"".$rowOF['officeid']."\">".$rowOF['name']."</option>\n";
	}
	
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "                          	<td align=\"right\">\n";
	echo "                          		<select name=\"dtype\">\n";
	echo "                          			<option value=\"added\" SELECTED>Date Added</option>\n";
	echo "                          			<option value=\"updated\">Last Update</option>\n";
	echo "                          		</select>\n";
	echo "								</td>\n";
	echo "                          	<td align=\"left\">\n";
	echo "									<input class=\"bboxb\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
	echo "									<input class=\"bboxb\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";	
	echo "							<tr>\n";
	echo "                          	<td align=\"right\">\n";
	echo "									Only Source Code\n";
	echo "								</td>\n";
	echo "                          	<td align=\"left\">\n";
	echo "                          		<select name=\"srccode\">\n";
	echo "										<option value=\"A\">All</option>\n";
	echo "										<option value=\"A\">------</option>\n";

	while ($rowLS = mssql_fetch_array($resLS))
	{
		if ($rowLS['statusid']==0)
		{
			echo "                                    		<option value=\"".$rowLS['statusid']."\">bluehaven.com</option>\n";
		}
		elseif ($rowLS['statusid']!=1)
		{
			echo "                                    		<option value=\"".$rowLS['statusid']."\">".$rowLS['name']."</option>\n";
		}
	}

	echo "									</select>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "                          	<td align=\"right\">\n";
	echo "									Only Result Code\n";
	echo "								</td>\n";
	echo "                          	<td align=\"left\">\n";
	echo "                          		<select name=\"rescode\">\n";
	echo "										<option value=\"A\">All</option>\n";
	echo "										<option value=\"A\">------</option>\n";

	while ($rowLR = mssql_fetch_array($resLR))
	{
		echo "                                    		<option value=\"".$rowLR['statusid']."\">".$rowLR['name']."</option>\n";
	}

	echo "									</select>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	/*echo "							<tr>\n";
	echo "                          	<td align=\"right\">\n";
	echo "									Only Sales Rep\n";
	echo "								</td>\n";
	echo "                              <td align=\"left\">\n";
	echo "                              	<select name=\"srep\">\n";
	echo "										<option value=\"A\">All</option>\n";
	echo "										<option value=\"A\">------</option>\n";
	
	while ($row1 = mssql_fetch_array($res1))
	{
		$secl=explode(",",$row1['slevel']);
		if ($secl[6]==0)
		{
			echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontred\">".$row1['lname'].", ".$row1['fname']." ".$dis."</option>\n";
		}
		else
		{
			echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontblack\">".$row1['lname'].", ".$row1['fname']."".$dis."</option>\n";					
		}
	}
	
	echo "									</select>\n";
	echo "								</td>\n";
	echo "							</tr>\n";*/
	
	echo "							<tr>\n";
	echo "                          	<td align=\"right\">\n";
	echo "									Only Valid Email\n";
	echo "								</td>\n";
	echo "                          	<td align=\"left\">\n";
	echo "									<input class=\"transnb\" type=\"checkbox\" name=\"validemail\" value=\"1\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	
	if (isset($row3['officeid']) and $row3['officeid']==89)
	{
		echo "							<tr>\n";
		echo "                          	<td align=\"right\">\n";
		echo "									Privacy Release\n";
		echo "								</td>\n";
		echo "                          	<td align=\"left\">\n";
		echo "									<input class=\"transnb JMStooltip\" type=\"checkbox\" name=\"privrelease\" value=\"1\" title=\"Check this box to include Customer Information for those who selected to Opt out of ANY future Contact (Email, etc)\"> (available to BHNM Only)\n";
		echo "								</td>\n";
		echo "							</tr>\n";
	}
	
	echo "							<tr>\n";
	echo "								<td colspan=\"2\" align=\"right\">\n";
	echo "         							<table width=\"100%\">\n";
	echo "										<tr>\n";
	echo "											<td align=\"left\" valign=\"top\"><b>Release/Certify</b><br>\n";
	echo "												By checking this box you certify the exported data will be used for the sole interest of Blue Haven Pools & Spas and no other.";
	echo "											</td>\n";
	echo "											<td align=\"center\" valign=\"top\">\n";
	echo "												<input class=\"transnb\" type=\"checkbox\" name=\"certify\" value=\"1\" title=\"By checking this box you certify that the exported information will be used for the sole interest of Blue Haven Pools & Spas and no other\">\n";
	echo "											</td>\n";
	echo "											<td valign=\"top\">\n";
	echo "													<input class=\"transnb\" type=\"image\" src=\"images/page_excel.png\" title=\"This button will create a comma de-limited text file with Customer Information originated in the JMS within Date Range indicated.\">\n";
	//echo "												<input class=\"buttondkgrypnl80\" type=\"submit\" name=\"export\" value=\"Export\" title=\"This button will create a comma de-limited text file with Customer Information originated in the JMS within Date Range indicated.\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "					</form>\n";
	
	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['ldexport1'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['ldexport1'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";
	
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function selectemailtemplate($oid,$sid,$cid,$ttid)
{
	$qryET = "SELECT * FROM EmailTemplate WHERE active <= ".$_SESSION['emailtemplates']." AND active >= 1 and ttype=".$ttid." ORDER BY name ASC;";	
	$resET = mssql_query($qryET);
	$nrowET= mssql_num_rows($resET);

	if ($nrowET > 0)
	{
		echo "								<table>\n";
		echo "                                 					<tr>\n";
		echo "                                 						<td align=\"left\">\n";
		echo "                                 							<select id=\"etid\" name=\"etid\" title=\"Selecting an Email Template will send an Email to the Customer upon update.\">\n";
		echo "                                 								<option value=\"0\">None</option>\n";
		
		while ($rowET = mssql_fetch_array($resET))
		{
			if ($rowET['active']==0)
			{
				echo "                                 								<option class=\"fontred\"value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
			else
			{
				echo "                                 								<option value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
		}
		
		echo "                                 							</select>\n";
		echo "											<img id=\"empreview\" src=\"images/email_open.png\" onClick=\"displayPopup('etid','".$oid."','".$sid."','".$cid."');\" title=\"Select an Email Template then click to Preview\">\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "								</table>\n";
	}
}

function selectemailtemplate_NEW($oid,$sid,$cid,$ttid)
{
	$qryET = "SELECT * FROM EmailTemplate WHERE active <= ".$_SESSION['emailtemplates']." AND active >= 1 and ttype=".$ttid." ORDER BY name ASC;";	
	$resET = mssql_query($qryET);
	$nrowET= mssql_num_rows($resET);

	if ($nrowET > 0)
	{
		echo "								<table>\n";
		echo "                                 					<tr>\n";
		echo "                                 						<td align=\"left\">\n";
		echo "                                 							<select id=\"etid\" name=\"etid\" title=\"Selecting an Email Template will send an Email to the Customer upon update.\">\n";
		echo "                                 								<option value=\"0\">None</option>\n";
		
		while ($rowET = mssql_fetch_array($resET))
		{
			if ($rowET['active']==0)
			{
				echo "                                 								<option class=\"fontred\"value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
			else
			{
				echo "                                 								<option value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
		}
		
		echo "                                 							</select>\n";
		//echo "											<img id=\"empreview\" src=\"images/email_open.png\" onClick=\"displayPopup('etid','".$oid."','".$sid."','".$cid."');\" title=\"Select an Email Template then click to Preview\">\n";
		echo "											<img id=\"empreviewNEW\" src=\"images/email_open.png\" title=\"Select an Email Template then click to Preview\">\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "								</table>\n";
	}
}


function selectemaillisttemplate()
{
	if ($_SESSION['emailtemplates'] >= 6 && $_SESSION['llev'] >= 6)
	{
		$qryET = "SELECT  * FROM EmailTemplate ORDER BY name ASC;";
		$resET = mssql_query($qryET);
		$nrowET= mssql_num_rows($resET);
		
		if ($nrowET > 0)
		{
			echo "												<table>\n";
			echo "                                 					<tr>\n";
			echo "														<td><b>Send Email</b> =></td>\n";
			echo "                                 						<td align=\"left\">\n";
			echo "                                 							<select name=\"etid\" title=\"Selecting an Email Template will create an Email List of qualified Customers\">\n";
			echo "                                 								<option value=\"0\">None</option>\n";
			//echo "                                 								<option value=\"AU\">Auto</option>\n";
			
			while ($rowET = mssql_fetch_array($resET))
			{
				echo "                                 								<option value=\"".$rowET['etid']."\">".$rowET['name']."</option>\n";
			}
			
			echo "                                 							</select>\n";
			echo "														</td>\n";
			echo "													</tr>\n";
			echo "												</table>\n";
		}
	}
}

function search_net_string()
{
	$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	$qry1  = "SELECT  ";
	$qry1 .= "	 s.securityid ";
	$qry1 .= "	,s.lname ";
	$qry1 .= "	,s.fname ";
	$qry1 .= "	,substring(s.slevel,13,1) as pslevel ";
	$qry1 .= "	,(SELECT count(cid) FROM cinfo WHERE dupe=0 AND jobid='0' AND securityid=S.securityid) as lcnt ";
	$qry1 .= "FROM  ";
	$qry1 .= "	security AS s ";
	$qry1 .= "WHERE  ";
	$qry1 .= "	s.officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=$_SESSION['securityid'] && $_SESSION['asstto']!=0)
		{
			$qry1 .= "	and s.sidm=".$_SESSION['asstto']." OR s.securityid=".$_SESSION['securityid']." ";
		}
		else
		{
			$qry1 .= "	and s.sidm=".$_SESSION['securityid']." OR s.securityid=".$_SESSION['securityid']." ";
		}
	}
	elseif ($_SESSION['llev'] <= 3)
	{
		$qry1 .= "	and s.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry1 .= "ORDER BY ";
	$qry1 .= "	SUBSTRING(s.slevel,13,1) DESC, ";
	$qry1 .= "	s.lname ASC; ";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);

	echo "				<div class=\"searchpanelDIM\">\n";
	echo "					<form id=\"netsearch\" name=\"netsearch\" method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"search_results_net\">\n";
	echo "					<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	
	//echo "				<fieldset>\n";
	//echo "				<legend><b>Network Search</b></legend>\n";
	
	echo "					<label for=\"field\">Search Field</label>\n";
	echo "					<select class=\"jform\" name=\"field\" id=\"field\">\n";
	echo "						<option value=\"caddr1\">Address</option>\n";
	echo "						<option value=\"cpname\" SELECTED>Company Name</option>\n";
    echo "						<option value=\"cemail\">Email</option>\n";
	echo "						<option value=\"cfname1\">First Name</option>\n";
	echo "						<option value=\"clname1\">Last Name</option>\n";
	echo "					</select><br>\n";
	
	echo "					<label for=\"ssearch\">Search Text</label>\n";
	echo "					<input class=\"jform\" type=\"text\" name=\"ssearch\" id=\"ssearch\" size=\"20\" maxlength=\"40\"><br>\n";

	if ($nrow1 > 0)
	{
		echo "					<label for=\"secid\">Sales Rep</label>\n";
		echo "					<select class=\"jform\" name=\"secid\" id=\"secid\">\n";
		
		if ($_SESSION['llev'] > 1)
		{
			echo "						<option class=\"fontblack\" value=\"NA\"></option>\n";
		}
		
		if ($_SESSION['llev'] >= 5)
		{
			echo "						<option class=\"fontblack\" value=\"0\">Unassigned</option>\n";
		}
		
		while ($row1 = mssql_fetch_array($res1))
		{
			if ($row1['pslevel'] > 0)
			{
				echo "						<option class=\"fontblack\" value=\"".$row1['securityid']."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
			}
			else
			{
				echo "						<option class=\"fontred\" value=\"".$row1['securityid']."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
			}
		}
		
		echo "					</select><br>\n";
	}
	
	if ($_SESSION['llev'] >= 5)
	{
		echo "					<label for=\"active\">Status</label>\n";
		echo "					<select class=\"jform\" name=\"active\" id=\"active\">\n";
		echo "						<option value=\"0\">Active</option>\n";
		echo "						<option value=\"1\">Inactive</option>\n";
		echo "						<option value=\"2\">Both</option>\n";
		echo "					</select><br>\n";
	}
	
	echo "					<button class=\"jform\" type=\"submit\"><img src=\"images/search.gif\"></button>\n";
	//echo "				</fieldset>\n";
	echo "					</form>\n";
	echo "				</div>\n";

}

function search_leads_string()
{
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
	unset($_SESSION['et_uid']);
	
	$et_uid1  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	echo "				<div class=\"searchpanelDIM\">\n";
	echo "         			<form name=\"stringsearch\" method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "					<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "					<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid1."\">\n";
	
	//echo "				<fieldset>\n";
	//echo "				<legend><b>String Search</b></legend>\n";
	echo "					<label>Data Field</label>\n";
	echo "					<select class=\"jform\" name=\"field\">\n";
	echo "						<option value=\"clname\" SELECTED>Last Name</option>\n";
    echo "						<option value=\"cemail\">Email</option>\n";
	echo "						<option value=\"chome\">Home Phone</option>\n";
	echo "						<option value=\"cwork\">Work Phone</option>\n";
	echo "						<option value=\"ccell\">Cell Phone</option>\n";
	echo "						<option value=\"caddr1\">Customer Addr</option>\n";
	echo "						<option value=\"czip1\">Customer Zip</option>\n";
	echo "						<option value=\"saddr1\">Site Addr</option>\n";
	echo "						<option value=\"szip1\">Site Zip</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Data String</label>\n";
	echo "					<input class=\"jform\" type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Information in this Field\"><br>\n";
	echo "					<label>Date (Opt)</label>\n";
	echo "					<select class=\"jform\" name=\"dtype\">\n";
	echo "						<option value=\"added\">Date Added</option>\n";
	echo "						<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Date Range (Opt)</label>\n";
	echo "					<input class=\"jform\" type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
	echo "					<input class=\"jformALT\" type=\"text\" name=\"d2\" id=\"d2\" size=\"11\"><br>\n";
	echo "					<label>Sort Field</label>\n";
	echo "					<select class=\"jform\" name=\"order\">\n";
	echo "						<option value=\"clname\">Last Name</option>\n";
	echo "						<option value=\"custid\">Lead ID</option>\n";
	echo "						<option value=\"szip1\">Site Zip Code</option>\n";
	echo "						<option value=\"added\">Date Added</option>\n";
	echo "						<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Sort Order</label>\n";
	echo "					<select class=\"jform\" name=\"dir\">\n";
	echo "						<option value=\"asc\">Ascending</option>\n";
	echo "						<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "					</select><br>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "					<label for=\"showdupe\">Status</label>\n";
		echo "					<select class=\"jform\" name=\"showdupe\">\n";
		echo "						<option value=\"0\" SELECTED>Active</option>\n";
		echo "						<option value=\"1\">Inactive</option>\n";
		echo "						<option value=\"2\">Both</option>\n";
		echo "					</select><br>\n";
	}

	echo "					<label>Address</label>\n";
	echo "					<select class=\"jform\" name=\"incaddr\">\n";
	echo "						<option value=\"0\" SELECTED>No</option>\n";
	echo "						<option value=\"1\">Yes</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Comments</label>\n";
	echo "					<select class=\"jform\" name=\"cmtcnt\">\n";
	
	for ($ia=0;$ia <=5;$ia++)
	{
		echo "						<option value=\"".$ia."\">".$ia."</option>\n";
	}
	
	echo "					</select><br>\n";
	echo "					<button class=\"jform\" type=\"submit\"><img src=\"images/search.gif\"></button><br>\n";
	//echo "				</fieldset>\n";
	
	echo "         		</form>\n";
	echo "         		</div>\n";
}

function search_leads_source()
{
	unset($_SESSION['d3']);
	unset($_SESSION['d4']);
	
	$et_uid2  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 AND ivr!=1 AND (oid=0 OR oid=".$_SESSION['officeid'].") ORDER BY name ASC;";
	$res = mssql_query($qry);
	
	echo "				<div class=\"searchpanelDIM\">\n";
	echo "					<form name=\"sourcesearch\" method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "					<input type=\"hidden\" name=\"subq\" value=\"srcstatus\">\n";
	echo "					<input type=\"hidden\" name=\"field\" value=\"source\">\n";
	echo "					<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid2."\">\n";
	
	//echo "				<fieldset>\n";
	//echo "				<legend><b>Source Code Search</b></legend>\n";
	echo "					<label>Source Code</label>\n";
	echo "					<select class=\"jform\" name=\"ssearch\">\n";

	while ($row = mssql_fetch_array($res))
	{
		if ($row['statusid']==0)
		{
			echo "					<option value=\"".$row['statusid']."\">bluehaven.com</option>\n";
		}
		elseif ($row['statusid']==1)
		{
			echo "					<option value=\"".$row['statusid']."\">Manual</option>\n";
		}
		else
		{
			if ($row['oid']==0 || $row['oid']==$_SESSION['officeid'])
			{
				echo "						<option value=\"".$row['statusid']."\">".$row['name']."</option>\n";
			}
		}
	}

	echo "					</select><br>\n";
	echo "					<label>Date (Opt)</label>\n";
	echo "					<select class=\"jform\" name=\"dtype\">\n";
	echo "						<option value=\"added\">Date Added</option>\n";
	echo "						<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Date Range (Opt)</label>\n";
	echo "					<input class=\"jform\" type=\"text\" name=\"d3\" id=\"d3\" size=\"11\">\n";
	echo "					<input class=\"jformALT\" type=\"text\" name=\"d4\" id=\"d4\" size=\"11\"><br>\n";
	echo "					<label>Sort Field</label>\n";
	echo "					<select class=\"jform\" name=\"order\">\n";
	echo "						<option value=\"clname\">Last Name</option>\n";
	echo "						<option value=\"custid\">Lead ID</option>\n";
	echo "						<option value=\"szip1\">Site Zip Code</option>\n";
	echo "						<option value=\"added\">Date Added</option>\n";
	echo "						<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Sort Order</label>\n";
	echo "					<select class=\"jform\" name=\"dir\">\n";
	echo "						<option value=\"asc\">Ascending</option>\n";
	echo "						<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Aged +30</label>\n";
	echo "					<select class=\"jform\" name=\"showaged\">\n";
	echo "						<option value=\"0\">No</option>\n";
	echo "						<option value=\"1\">Yes</option>\n";
	echo "					</select><br>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "					<label>Status</label>\n";
		echo "					<select class=\"jform\" name=\"showdupe\">\n";
		echo "						<option value=\"0\">Active</option>\n";
		echo "						<option value=\"1\">Inactive</option>\n";
		echo "						<option value=\"2\">Both</option>\n";
		echo "					</select><br>\n";
	}

	echo "					<label>Address</label>\n";
	echo "					<select class=\"jform\" name=\"incaddr\">\n";
	echo "						<option value=\"0\">No</option>\n";
	echo "						<option value=\"1\">Yes</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Comments</label>\n";
	echo "					<select class=\"jform\" name=\"cmtcnt\">\n";
	
	for ($ia=0;$ia <=5;$ia++)
	{
		echo "						<option value=\"".$ia."\">".$ia."</option>\n";
	}

	echo "					</select><br>\n";
	echo "					<button class=\"jform\" type=\"submit\"><img src=\"images/search.gif\"></button>\n";
	//echo "				</fieldset>\n";
	
	echo "					</form>\n";
	echo "				</div>\n";
}

function search_leads_result()
{
	unset($_SESSION['d5']);
	unset($_SESSION['d6']);
	
	$et_uid3  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=1 AND (oid=0 OR oid=".$_SESSION['officeid'].") ORDER BY name ASC;";
	$res = mssql_query($qry);
	
	echo "				<div class=\"searchpanelDIM\">\n";
	echo "					<form name=\"resultsearch\" method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "					<input type=\"hidden\" name=\"subq\" value=\"srcstatus\">\n";
	echo "					<input type=\"hidden\" name=\"field\" value=\"source\">\n";
	echo "					<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid3."\">\n";
	
	//echo "				<fieldset>\n";
	//echo "				<legend><b>Result Code Search</b></legend>\n";
	echo "					<label>Result Code</label>\n";
	echo "					<select class=\"jform\" name=\"ssearch\">\n";

	while ($row = mssql_fetch_array($res))
	{
		echo "						<option value=\"".$row['statusid']."\">".$row['name']."</option>\n";
	}

	echo "					</select><br>\n";
	echo "					<label>Date (Opt)</label>\n";
	echo "					<select class=\"jform\" name=\"dtype\">\n";
	echo "						<option value=\"added\">Date Added</option>\n";
	echo "						<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Date Range (Opt)</label>\n";
	echo "					<input class=\"jform\" type=\"text\" name=\"d5\" id=\"d5\" size=\"11\">\n";
	echo "					<input type=\"text\" name=\"d6\" id=\"d6\" size=\"11\"><br>\n";
	echo "					<label>Sort Field</label>\n";
	echo "					<select class=\"jform\" name=\"order\">\n";
	echo "						<option value=\"clname\">Last Name</option>\n";
	echo "						<option value=\"custid\">Lead ID</option>\n";
	echo "						<option value=\"szip1\">Site Zip Code</option>\n";
	echo "						<option value=\"added\">Date Added</option>\n";
	echo "						<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Sort Order</label>\n";
	echo "					<select class=\"jform\" name=\"dir\">\n";
	echo "						<option value=\"asc\">Ascending</option>\n";
	echo "						<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Aged +30</label>\n";
	echo "					<select class=\"jform\" name=\"showaged\">\n";
	echo "						<option value=\"0\">No</option>\n";
	echo "						<option value=\"1\">Yes</option>\n";
	echo "					</select><br>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "					<label>Status</label>\n";
		echo "					<select class=\"jform\" name=\"showdupe\">\n";
		echo "						<option value=\"0\">Active</option>\n";
		echo "						<option value=\"1\">Inactive</option>\n";
		echo "						<option value=\"2\">Both</option>\n";
		echo "					</select><br>\n";
	}

	echo "					<label>Address</label>\n";
	echo "					<select class=\"jform\" name=\"incaddr\">\n";
	echo "						<option value=\"0\">No</option>\n";
	echo "						<option value=\"1\">Yes</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Show Comments</label>\n";
	echo "					<select class=\"jform\" name=\"cmtcnt\">\n";
	
	for ($ia=0;$ia <=5;$ia++)
	{
		echo "						<option value=\"".$ia."\">".$ia."</option>\n";
	}

	echo "					</select><br>\n";
	echo "					<button class=\"jform\" type=\"submit\"><img src=\"images/search.gif\"></button>\n";
	//echo "				</fieldset>\n";
	
	echo "					</form>\n";
	echo "				</div>\n";
}

function search_leads_srep()
{
	unset($_SESSION['d7']);
	unset($_SESSION['d8']);
	
	$et_uid4  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	$qry1  = "SELECT  ";
	$qry1 .= "	 s.securityid ";
	$qry1 .= "	,s.lname ";
	$qry1 .= "	,s.fname ";
	$qry1 .= "	,substring(s.slevel,13,1) as sslevel ";
	$qry1 .= "	,(SELECT count(cid) FROM cinfo WHERE dupe=0 AND jobid='0' AND securityid=S.securityid) as lcnt ";
	$qry1 .= "FROM  ";
	$qry1 .= "	security AS s ";
	$qry1 .= "WHERE  ";
	$qry1 .= "	s.officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=$_SESSION['securityid'] && $_SESSION['asstto']!=0)
		{
			$qry1 .= "	and s.sidm=".$_SESSION['asstto']." OR s.securityid=".$_SESSION['securityid']." ";
		}
		else
		{
			$qry1 .= "	and s.sidm=".$_SESSION['securityid']." OR s.securityid=".$_SESSION['securityid']." ";
		}
	}
	elseif ($_SESSION['llev'] <= 3)
	{
		$qry1 .= "	and s.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry1 .= "ORDER BY ";
	$qry1 .= "	SUBSTRING(s.slevel,13,1) DESC, ";
	$qry1 .= "	s.lname ASC; ";
	$res1  = mssql_query($qry1);
	$nrow1 = mssql_num_rows($res1);

	echo "				<div class=\"searchpanelDIM\">\n";
	echo "					<form name=\"srepsearch\" method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "					<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
	echo "					<input type=\"hidden\" name=\"field\" value=\"securityid\">\n";
	echo "					<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid4."\">\n";
	
	//echo "				<fieldset>\n";
	//echo "				<legend><b>Sales Rep Search</b></legend>\n";
	echo "					<label>Sales Rep</label>\n";
	echo "					<select class=\"jform\" name=\"ssearch\">\n";

	while ($row1 = mssql_fetch_array($res1))
	{
		if ($row1['sslevel']==0)
		{
			echo "				<option value=\"".$row1['securityid']."\" class=\"fontred\">".$row1['lname'].", ".$row1['fname']." ".$dis."</option>\n";
		}
		else
		{
			echo "				<option value=\"".$row1['securityid']."\" class=\"fontblack\">".$row1['lname'].", ".$row1['fname']."".$dis."</option>\n";					
		}
	}

	echo "					</select><br>\n";
	echo "					<label>Date (Opt)</label>\n";
	echo "					<select class=\"jform\" name=\"dtype\">\n";
	echo "						<option value=\"added\">Date Added</option>\n";
	echo "						<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Date Range (Opt)</label>\n";
	echo "					<input class=\"jform\" type=\"text\" name=\"d7\" id=\"d7\" size=\"11\">\n";
	echo "					<input class=\"jformALT\" type=\"text\" name=\"d8\" id=\"d8\" size=\"11\"><br>\n";
	echo "					<label>Sort Field</label>\n";
	echo "					<select class=\"jform\" name=\"order\">\n";
	echo "						<option value=\"clname\">Last Name</option>\n";
	echo "						<option value=\"custid\">Lead ID</option>\n";
	echo "						<option value=\"szip1\">Site Zip Code</option>\n";
	echo "						<option value=\"added\">Date Added</option>\n";
	echo "						<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Sort Order</label>\n";
	echo "					<select class=\"jform\" name=\"dir\">\n";
	echo "						<option value=\"asc\">Ascending</option>\n";
	echo "						<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Aged 30+</label>\n";
	echo "					<select class=\"jform\" name=\"showaged\">\n";
	echo "						<option value=\"0\">No</option>\n";
	echo "						<option value=\"1\">Yes</option>\n";
	echo "					</select><br>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "					<label>Status</label>\n";
		echo "					<select class=\"jform\" name=\"showdupe\">\n";
		echo "						<option value=\"0\">Active</option>\n";
		echo "						<option value=\"1\">Inactive</option>\n";
		echo "						<option value=\"2\">Both</option>\n";
		echo "					</select><br>\n";
	}

	echo "					<label>Address</label>\n";
	echo "					<select class=\"jform\" name=\"incaddr\">\n";
	echo "						<option value=\"0\">No</option>\n";
	echo "						<option value=\"1\">Yes</option>\n";
	echo "					</select><br>\n";
	echo "					<label>Comments</label>\n";
	echo "					<select class=\"jform\" name=\"cmtcnt\">\n";

	for ($ia=0;$ia <=5;$ia++)
	{
		echo "						<option value=\"".$ia."\">".$ia."</option>\n";
	}

	echo "					</select><br>\n";
	echo "					<button class=\"jform\" type=\"submit\"><img src=\"images/search.gif\"></button>\n";
	//echo "				</fieldset>\n";
	echo "					</form>\n";
	echo "				</div>\n";
}

function search_panel()
{
	$dev_ar= array(SYS_ADMIN);
	
	unset($_SESSION['tqry']);
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
	unset($_SESSION['d3']);
	unset($_SESSION['d4']);
	unset($_SESSION['d5']);
	unset($_SESSION['d6']);
	unset($_SESSION['d7']);
	unset($_SESSION['d8']);
	unset($_SESSION['et_uid']);
	
	$cr_ar=array();
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 AND ivr!=1 ORDER BY name ASC;";
	$res = mssql_query($qry);
	
	while ($row = mssql_fetch_array($res))
	{
		$lsrc_ar[$row['statusid']]=array('oid'=>$row['oid'],'name'=>$row['name']);
	}

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 AND (oid=0 OR oid=".$_SESSION['officeid'].") ORDER BY name ASC;";
	$res0 = mssql_query($qry0);
	
	while ($row0 = mssql_fetch_array($res0))
	{
		$lres_ar[$row0['statusid']]=array('oid'=>$row0['oid'],'name'=>$row0['name']);;
	}

	//$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$qry1  = "SELECT  ";
	$qry1 .= "	 s.securityid ";
	$qry1 .= "	,s.lname ";
	$qry1 .= "	,s.fname ";
	//$qry1 .= "	,s.slevel ";
	$qry1 .= "	,SUBSTRING(s.slevel,13,1) as slevel ";
	$qry1 .= "	,(SELECT count(cid) FROM cinfo WHERE dupe=0 AND jobid='0' AND securityid=S.securityid) as lcnt ";
	$qry1 .= "FROM  ";
	$qry1 .= "	security AS s ";
	$qry1 .= "WHERE  ";
	$qry1 .= "	s.officeid=".$_SESSION['officeid']." ";
	//$qry1 .= "	and s.srep=1 ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=$_SESSION['securityid'] && $_SESSION['asstto']!=0)
		{
			$qry1 .= "	and s.sidm=".$_SESSION['asstto']." OR s.securityid=".$_SESSION['securityid']." ";
		}
		else
		{
			$qry1 .= "	and s.sidm=".$_SESSION['securityid']." OR s.securityid=".$_SESSION['securityid']." ";
		}
	}
	elseif ($_SESSION['llev'] <= 3)
	{
		$qry1 .= "	and s.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry1 .= "ORDER BY ";
	$qry1 .= "	SUBSTRING(s.slevel,13,1) DESC, ";
	$qry1 .= "	s.lname ASC; ";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	$qry2 = "SELECT ldexport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
	
	$qry3 = "SELECT securityid,lname,fname,slevel,gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
	
	if ($_SESSION['officeid']==193)
	{
		$mar_ar=array();
		$qryM = "SELECT DISTINCT(market) as markets FROM cinfo WHERE officeid=".$_SESSION['officeid']." and dupe!=1;";
		$resM = mssql_query($qryM);
		$nrowM= mssql_num_rows($resM);
		
		if ($nrowM > 0)
		{
			while ($rowM = mssql_fetch_array($resM))
			{
				$mar_ar[]=$rowM['markets'];
			}
		}
	}
	
	//$acclist=explode(",",$_SESSION['aid']);
	
	if (in_array($_SESSION['securityid'],$dev_ar))
	{
		$tbgS='transnb';
	}
	else
	{
		$tbgS='gray';
	}

	$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	$hlpnd=1;

	echo "<script type=\"text/javascript\" src=\"js/jquery_extend.js\"></script>\n";
	echo "<table width=\"950px\" align=\"center\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	//echo "					<td class=\"".$tbgS."\">\n";
	echo "					<td class=\"gray\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	
	if ($_SESSION['officeid']==199)
	{
		echo "								<td align=\"left\"><b>Vendor Search</b></td>\n";
	}
	else
	{
		echo "								<td align=\"left\"><b>Lead Search</b> <img class=\"getHelpNode\" id=\"LeadSearchPanel\" src=\"images/help.png\" title=\"Lead Search Help\"></td>\n";
	}
	
	echo "								<td align=\"right\">\n";
	//echo "									<img class=\"getHelpNode\" id=\"LeadSearchPanel\" src=\"images/help.png\" title=\"Leads Search Panel Help\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td colspan=\"2\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" colspan=\"2\"><b>Search Type</b></td>\n";
	echo "											<td class=\"gray\" align=\"left\"><strong>Data Field</strong></td>\n";
	echo "											<td class=\"gray\" align=\"left\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{		
		echo "<b>Market</b>\n";
	}
	
	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><b>Sort by</b></td>\n";
	echo "											<td class=\"gray\" align=\"left\"><b>Direction</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">Aged 30+</td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "											<td class=\"gray\" align=\"center\">Inactive</td>\n";
	}
	
	echo "											<td class=\"gray\" align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">Address</td>\n";
	echo "											<td class=\"gray\" align=\"center\" title=\"Select the number of comments to display\">Comments</td>\n";
	echo "											<td class=\"gray\" align=\"left\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	
	// String Search
	echo "         		<form name=\"tsearch1\" method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "				<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "											<td align=\"right\">\n";
	echo "												<img class=\"getHelpNode\" id=\"LeadSearchPanelText\" src=\"images/help.png\" title=\"Lead Search Help\">\n";
	echo "											</td>\n";
	echo "											<td align=\"right\">\n";
	echo "												<select name=\"field\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
		echo "													<option value=\"clname\">Last Name</option>\n";
	}
	else
	{
		echo "													<option value=\"clname\" SELECTED>Last Name</option>\n";
	}
	
	echo "													<option value=\"cfname\">First Name</option>\n";
    echo "                                    				<option value=\"cemail\">Email</option>\n";
	echo "                                    				<option value=\"chome\">Home Phone</option>\n";
	echo "                                    				<option value=\"cwork\">Work Phone</option>\n";
	echo "                                    				<option value=\"ccell\">Cell Phone</option>\n";
	echo "                                    				<option value=\"custid\">Lead ID</option>\n";
	echo "                                    				<option value=\"caddr1\">Customer Addr</option>\n";
	echo "                                    				<option value=\"ccity\">Customer City</option>\n";
	echo "                                    				<option value=\"czip1\">Customer Zip</option>\n";
	echo "                                    				<option value=\"saddr1\">Site Addr</option>\n";
	echo "                                    				<option value=\"scity\">Site City</option>\n";
	echo "                                    				<option value=\"szip1\">Site Zip</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\"><input type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Information in this Field\"></td>\n";
	echo "											<td align=\"left\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "												<select name=\"market\">\n";
		
		foreach ($mar_ar as $nM => $vM)
		{
			echo "												<option value=\"".$vM."\">".$vM."</option>\n";
		}
		
		echo "												</select>\n";
	}
	
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<select name=\"order\">\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "													<option value=\"updated\">Last Update</option>\n";
		echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
		echo "													<option value=\"clname\">Last Name</option>\n";
	}
	else
	{
		echo "													<option value=\"clname\" SELECTED>Last Name</option>\n";
		echo "													<option value=\"updated\">Last Update</option>\n";
	}
	
	echo "													<option value=\"cfname\">First Name</option>\n";
	echo "													<option value=\"custid\">Lead ID</option>\n";
	echo "													<option value=\"scity\">Site City</option>\n";
	echo "													<option value=\"szip1\">Site Zip Code</option>\n";
	echo "													<option value=\"added\">Date Added</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<select name=\"dir\">\n";
	echo "													<option value=\"asc\">Ascending</option>\n";
	echo "													<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "												</select>\n";
	echo "											</td>";
	echo "											<td align=\"center\"></td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "											<td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
		echo "												<select name=\"showdupe\">\n";
		echo "													<option value=\"0\">No</option>\n";
		echo "													<option value=\"1\">Yes</option>\n";
		echo "												</select>\n";
		echo "											</td>\n";
	}

	echo "											<td align=\"center\" title=\"Check this box to include the Address and Email in the displayed search results\">\n";
	echo "												<select name=\"incaddr\">\n";
	echo "													<option value=\"0\">No</option>\n";
	echo "													<option value=\"1\">Yes</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"center\">\n";
	echo "												<select name=\"cmtcnt\">\n";
	echo "													<option value=\"0\">0</option>\n";
	echo "													<option value=\"1\">1</option>\n";
	echo "													<option value=\"2\">2</option>\n";
	echo "													<option value=\"3\">3</option>\n";
	echo "													<option value=\"4\">4</option>\n";
	echo "													<option value=\"5\">5</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "											<td align=\"right\">\n";
	echo "												<select name=\"dtype\">\n";
	echo "													<option value=\"added\">Date Added</option>\n";
	echo "													<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<input type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
	echo "												<input type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
	echo "											</td>\n";
	echo "											<td align=\"left\" colspan=\"7\">(Date Optional)</td>\n";
	echo "										</tr>\n";
	echo "         			</form>\n";
	
	if ($_SESSION['officeid']!=199)
	{
		echo "										<tr>\n";
		echo "                              		<td align=\"center\" colspan=\"10\"><hr width=\"100%\"></td>\n";
		echo "										</tr>\n";
		
		// Lead Source
		echo "										<tr>\n";
		echo "											<td align=\"right\">\n";
		echo "												<img class=\"getHelpNode\" id=\"LeadSearchPanelSource\" src=\"images/help.png\" title=\"Lead Search Help\">\n";
		echo "											</td>\n";
		echo "         								<form name=\"tsearch2\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"srcstatus\">\n";
		echo "											<input type=\"hidden\" name=\"field\" value=\"source\">\n";
		echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid."\">\n";
		echo "                              	<td align=\"right\"><b>Source Code</b>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"ssearch\">\n";
	
		foreach ($lsrc_ar as $ns=>$vs)
		{
			if ($ns==0)
			{
				echo "                                    	<option value=\"".$ns."\">bluehaven.com</option>\n";
			}
			elseif ($ns==1)
			{
				echo "                                    	<option value=\"".$ns."\">Manual</option>\n";
			}
			else
			{
				if ($vs['oid']==0 || $vs['oid']==$_SESSION['officeid'])
				{
					echo "                                    	<option value=\"".$ns."\">".$vs['name']."</option>\n";
				}
			}
		}
	
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "											<td align=\"left\">\n";
	
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "												<select name=\"market\">\n";
			
			foreach ($mar_ar as $nM => $vM)
			{
				echo "												<option value=\"".$vM."\">".$vM."</option>\n";
			}
			
			echo "												</select>\n";
		}
		
		echo "											</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "                                    	<option value=\"clname\">Last Name</option>\n";
		echo "										<option value=\"cfname\">First Name</option>\n";
		echo "                                    	<option value=\"custid\">Lead ID</option>\n";
		echo "										<option value=\"scity\">Site City</option>\n";
		echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "                                    <select name=\"dir\">\n";
		echo "                                    	<option value=\"asc\">Ascending</option>\n";
		echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "                                    </select>\n";
		echo "									</td>";
		echo "                                 <td align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">\n";
		echo "										<select name=\"showaged\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	
		if ($_SESSION['llev'] >= 5)
		{
			echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
			echo "										<select name=\"showdupe\">\n";
			echo "											<option value=\"0\">No</option>\n";
			echo "											<option value=\"1\">Yes</option>\n";
			echo "										</select>\n";
			echo "								   </td>\n";
		}
	
		echo "                                 <td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">\n";
		echo "										<select name=\"incaddr\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"center\">\n";
		echo "                                    <select name=\"cmtcnt\">\n";
		echo "                                    	<option value=\"0\">0</option>\n";
		echo "                                    	<option value=\"1\">1</option>\n";
		echo "                                    	<option value=\"2\">2</option>\n";
		echo "                                    	<option value=\"3\">3</option>\n";
		echo "                                    	<option value=\"4\">4</option>\n";
		echo "                                    	<option value=\"5\">5</option>\n";
		echo "                                    </select>\n";
		echo "								   </td>\n";
		echo "                                	<td align=\"left\">\n";
		echo "										<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
		echo "									</td>\n";
		echo "								<tr>\n";
		echo "											<td align=\"right\">\n";
	
		echo "											</td>\n";
		echo "                              	<td align=\"right\">\n";
		echo "                                   	<select name=\"dtype\">\n";
		echo "                                    		<option value=\"added\">Date Added</option>\n";
		echo "                                    		<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    	</select>\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "										<input type=\"text\" name=\"d3\" id=\"d3\" size=\"11\">\n";
		echo "										<input type=\"text\" name=\"d4\" id=\"d4\" size=\"11\">\n";
		echo "									</td>\n";
		echo "                                 	<td align=\"left\" colspan=\"2\">(Date Optional)</td>\n";
		echo "                                 	<td colspan=\"4\">\n";
		
		//selectemaillisttemplate();
		
		echo "									</td>\n";
		echo "                                 	<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "								</tr>\n";
		echo "         						</form>\n";
		echo "										<tr>\n";
		echo "                              			<td align=\"center\" colspan=\"10\"><hr width=\"100%\"></td>\n";
		echo "										</tr>\n";
		
		// Lead Result
		echo "										<tr>\n";
		echo "											<td align=\"right\">\n";
		echo "												<img class=\"getHelpNode\" id=\"LeadSearchPanelResult\" src=\"images/help.png\" title=\"Lead Search Help\">\n";
		echo "											</td>\n";
		echo "         								<form name=\"tsearch3\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"resstatus\">\n";
		echo "											<input type=\"hidden\" name=\"field\" value=\"stage\">\n";
		echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid."\">\n";
		echo "                              			<td align=\"right\"><b>Result Code</b>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"ssearch\">\n";
	
		foreach ($lres_ar as $nr => $vr)
		{
			echo "                                    	<option value=\"".$nr."\">".$vr['name']."</option>\n";
		}
	
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "											<td align=\"left\">\n";
	
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "												<select name=\"market\">\n";
			
			foreach ($mar_ar as $nM => $vM)
			{
				echo "												<option value=\"".$vM."\">".$vM."</option>\n";
			}
			
			echo "												</select>\n";
		}
		
		echo "											</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "                                    	<option value=\"clname\">Last Name</option>\n";
		echo "										<option value=\"cfname\">First Name</option>\n";
		echo "                                    	<option value=\"custid\">Lead ID</option>\n";
		echo "										<option value=\"scity\">Site City</option>\n";
		echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "                                    <select name=\"dir\">\n";
		echo "                                    	<option value=\"asc\">Ascending</option>\n";
		echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "                                    </select>\n";
		echo "								   </td>";
		echo "                                 <td align=\"center\" title=\"Check this box to include Leads that have not been updated within the last 30 days\">\n";
		echo "										<select name=\"showaged\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	
		if ($_SESSION['llev'] >= 5)
		{
			echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
			echo "										<select name=\"showdupe\">\n";
			echo "											<option value=\"0\">No</option>\n";
			echo "											<option value=\"1\">Yes</option>\n";
			echo "										</select>\n";
			echo "								   </td>\n";
		}
	
		echo "                                 <td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">\n";
		echo "										<select name=\"incaddr\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"center\">\n";
		echo "                                    <select name=\"cmtcnt\">\n";
		echo "                                    	<option value=\"0\">0</option>\n";
		echo "                                    	<option value=\"1\">1</option>\n";
		echo "                                    	<option value=\"2\">2</option>\n";
		echo "                                    	<option value=\"3\">3</option>\n";
		echo "                                    	<option value=\"4\">4</option>\n";
		echo "                                    	<option value=\"5\">5</option>\n";
		echo "                                    </select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
		echo "									</td>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\">\n";
		
		echo "											</td>\n";
		echo "                              	<td align=\"right\">\n";
		echo "                                    <select name=\"dtype\">\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "										<input type=\"text\" name=\"d5\" id=\"d5\" size=\"11\">\n";
		echo "										<input type=\"text\" name=\"d6\" id=\"d6\" size=\"11\">\n";
		echo "									</td>\n";
		echo "                                 	<td align=\"left\" colspan=\"2\">(Date Optional)</td>\n";
		echo "                                 	<td colspan=\"4\">\n";
		
		//selectemaillisttemplate();
		
		echo "											</td>\n";
		echo "                                 			<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "										</tr>\n";
		echo "         								</form>\n";
	}
	
	if ($nrow1 > 0)
	{
		// SalesRep
		echo "										<tr>\n";
		echo "                              			<td align=\"center\" colspan=\"10\"><hr width=\"100%\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\">\n";
		echo "												<img class=\"getHelpNode\" id=\"LeadSearchPanelSalesRep\" src=\"images/help.png\" title=\"Lead Search Help\">\n";
		echo "											</td>\n";
		echo "         								<form name=\"tsearch4\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
		echo "											<input type=\"hidden\" name=\"field\" value=\"securityid\">\n";
		
		if ($_SESSION['officeid']==193 and $_SESSION['officeid']==199)
		{
			echo "                              	<td align=\"right\"><b>Manager</b></td>\n";
		}
		else
		{
			echo "                              	<td align=\"right\" title=\"JMS recognized Sales Reps. The number in parenthesis represents the total number of leads allocated to that Sales Rep. This number does not include Leads that have gone to contract. If this list is empty or a name is missing please contact BHNM IT Support \"><b>Sales Rep</b></td>\n";
		}
		
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"ssearch\">\n";
	
		while ($row1 = mssql_fetch_array($res1))
		{
			if ($_SESSION['securityid']==$row1['securityid'])
			{
				if ($row1['slevel']==0)
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontred\" SELECTED>".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
				else
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontblack\" SELECTED>".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
			}
			else
			{
				if ($row1['slevel']==0)
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontred\">".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
				else
				{
					echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontblack\">".$row1['lname'].", ".$row1['fname']."</option>\n";
				}
			}
		}
	
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "											<td align=\"left\">\n";
	
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "												<select name=\"market\">\n";
			
			foreach ($mar_ar as $nM => $vM)
			{
				echo "												<option value=\"".$vM."\">".$vM."</option>\n";
			}
			
			echo "												</select>\n";
		}
		
		echo "											</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"order\">\n";
		
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
			echo "													<option value=\"clname\">Last Name</option>\n";
		}
		else
		{
			echo "													<option value=\"clname\" SELECTED>Last Name</option>\n";
		}
		//echo "                                    	<option value=\"clname\">Last Name</option>\n";
		echo "										<option value=\"cfname\">First Name</option>\n";
		echo "                                    	<option value=\"custid\">Lead ID</option>\n";
		echo "										<option value=\"scity\">Site City</option>\n";
		echo "                                    	<option value=\"szip1\">Site Zip Code</option>\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "                                    <select name=\"dir\">\n";
		echo "                                    	<option value=\"asc\">Ascending</option>\n";
		echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "                                    </select>\n";
		echo "									</td>";
		echo "                                 <td align=\"center\" title=\"Check this box to include Leads that have not been updated within the last 30 days\">\n";
		echo "										<select name=\"showaged\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	
		if ($_SESSION['llev'] >= 5)
		{
			echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
			echo "										<select name=\"showdupe\">\n";
			echo "											<option value=\"0\">No</option>\n";
			echo "											<option value=\"1\">Yes</option>\n";
			echo "										</select>\n";
			echo "								   </td>\n";
		}
	
		echo "                                 <td align=\"center\" title=\"Check this box to include the Address and Email in the displayed search results\">\n";
		echo "										<select name=\"incaddr\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"center\">\n";
		echo "                                    <select name=\"cmtcnt\">\n";
		echo "                                    	<option value=\"0\">0</option>\n";
		echo "                                    	<option value=\"1\">1</option>\n";
		echo "                                    	<option value=\"2\">2</option>\n";
		echo "                                    	<option value=\"3\">3</option>\n";
		echo "                                    	<option value=\"4\">4</option>\n";
		echo "                                    	<option value=\"5\">5</option>\n";
		echo "                                    </select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "											<td align=\"right\">\n";

		echo "											</td>\n";
		echo "                              	<td align=\"right\">\n";
		echo "                                    <select name=\"dtype\">\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "									<input type=\"text\" name=\"d7\" id=\"d7\" size=\"11\">\n";
		echo "									<input type=\"text\" name=\"d8\" id=\"d8\" size=\"11\">\n";
		echo "									</td>\n";
		echo "                                 	<td align=\"left\" colspan=\"2\">(Date Optional)</td>\n";
		echo "                              	<td align=\"left\" colspan=\"2\"><b>Source</b>\n";
		echo "                                    <select name=\"lsource\">\n";
		echo "                                    		<option value=\"NA\">All</option>\n";

		foreach ($lsrc_ar as $ns => $vs)
		{
			if ($ns==0)
			{
				echo "                                    	<option value=\"".$ns."\">bluehaven.com</option>\n";
			}
			elseif ($ns==1)
			{
				echo "                                    	<option value=\"".$ns."\">Manual</option>\n";
			}
			else
			{
				if ($vs['oid']==0 || $vs['oid']==$_SESSION['officeid'])
				{
					echo "                                    	<option value=\"".$ns."\">".$vs['name']."</option>\n";
				}
			}
		}

		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                              	<td align=\"left\" colspan=\"2\"><b>Result</b>\n";
		echo "                                    <select name=\"lresult\">\n";
		echo "                                    		<option value=\"NA\">All</option>\n";

		foreach ($lres_ar as $nr => $vr)
		{
			echo "                                    	<option value=\"".$nr."\">".$vr['name']."</option>\n";
		}

		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "										</tr>\n";
		echo "         								</form>\n";
	}
	
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

function lead_search()
{
	$dev_ar= array(26,289,332,419,443,641,1950,1984,2139);
	
	/*
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==2191)
	{
		echo $_SESSION['otype'].'<br>';
		echo $_SESSION['sotype_code'];
	}
	*/
	
	if ($_SESSION['otype']==2)
	{
		search_panel_VENDOR();
	}
	elseif ($_SESSION['otype']==3)
	{
		search_panel_TRACK();
	}
	else
	{
		if ($_SESSION['securityid']==269999999999999999999999999)
		{
			//search_panel_NEW();
			search_panel();
		}
		else
		{
			search_panel();
		}
	}
	
		if ($_SESSION['otype']==2)
		{
			//display_CB_AP_TRACK();
		}
		elseif ($_SESSION['otype']==3)
		{
			display_CB_AP_TRACK();
		}
		else
		{
				display_Lead_Search_Ajax();
		}
}

function lead_search_results()
{
	$officeid=$_SESSION['officeid'];
	$securityid=$_SESSION['securityid'];

	if (isset($_REQUEST['order']))
	{
		if (isset($_REQUEST['dir']))
		{
			$order=$_REQUEST['order'];
			$dir=$_REQUEST['dir'];
		}
		else
		{
			$order=$_REQUEST['order'];
			$dir="ASC";
		}
	}
	else
	{
		$order="custid";
		$dir="ASC";
	}

	if (isset($_REQUEST['showdupe']) && $_REQUEST['showdupe']==1)
	{
		$dupe="";
	}
	else
	{
		$dupe="AND dupe!=1 ";
	}

	if (isset($_REQUEST['showhold']) && $_REQUEST['showhold']==1)
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

	if (empty($_REQUEST['order']))
	{
		$order="lid";
	}
	else
	{
		$order=$_REQUEST['order'];
	}

	$qryA	 ="SELECT L.* ";
	$qryA	.=",(select count(czip) from zip_to_zip where czip=L.zip) as zcnt ";
	$qryA	.=",(select name from leadstatuscodes where statusid=L.source) as srcname ";
	$qryA	.="FROM lead_inc as L WHERE L.sorted!=1 ORDER BY L.".$order.";";
	$resA	 = mssql_query($qryA);
	$nrowA	 = mssql_num_rows($resA);

	$qryB	="SELECT * FROM offices WHERE am!=0 AND active=1 ORDER BY name;";
	$resB	= mssql_query($qryB);

	echo "<table width=\"950px\">\n";
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
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Name\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"phone\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Phone\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"addr\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Address\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"city\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"City\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"state\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"State\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"zip\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Zip Code\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"added\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Date Added\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewunproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"submitted\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Date Submitted\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">Source</td>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">Zip Match</td>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						</tr>\n";

		$lcnt=0;
		while($rowA = mssql_fetch_array($resA))
		{
			if ($lcnt%2)
			{
				$tr='even';
			}
			else
			{
				$tr='odd';
			}
			
			echo "						<tr class=\"".$tr."\">\n";
			//echo "							<td class=\"wh_und\" align=\"left\"><a href=\"".$_SERVER['PHP_SELF']."?action=maint&call=leads&subq=view_lform&type=proc&lid=".$rowA['lid']."\"><b>".$rowA['lname']."</b></a></td>\n";
			echo "							<td align=\"left\"><b>".$rowA['lname']."</b></td>\n";
			echo "							<td align=\"center\"><b>".$rowA['phone']."</b></td>\n";
			echo "							<td align=\"left\">".$rowA['addr']."</td>\n";
			echo "							<td align=\"left\">".$rowA['city']."</td>\n";
			echo "							<td align=\"center\">".$rowA['state']."</td>\n";
			echo "							<td align=\"center\">".$rowA['zip']."</td>\n";
			echo "							<td align=\"center\">".$rowA['added']."</td>\n";
			echo "							<td align=\"center\">".$rowA['submitted']."</td>\n";
			echo "							<td align=\"left\">\n";
			
			echo $rowA['srcname'];
			
			echo "							</td>\n";
			echo "							<td align=\"center\">\n";
			
			if ($rowA['zcnt'] > 0)
			{
				echo "Yes";
			}
			else
			{
				echo "<font color=\"red\">No</font>\n";
			}
			
			echo "							</td>\n";
			echo "							<td align=\"center\">\n";
			echo "                        		<input class=\"checkboxwh\" type=\"checkbox\" name=\"xzx".$rowA['lid']."\" value=\"xzx".$rowA['lid']."\">\n";
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "						<tr class=\"".$tr."\">\n";
			echo "							<td align=\"right\"><b>Comment:</b></td>\n";
			echo "							<td colspan=\"10\" align=\"left\">".$rowA['syscomment']."</td>\n";
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
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" width=\"90px\">\n";
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
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" width=\"90px\">\n";
			echo "                        <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Manual Process\">\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "                        </form>\n";
			echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
			echo "								<input type=\"hidden\" name=\"subq\" value=\"autosort\">\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" width=\"90px\">\n";
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
				echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" width=\"90px\">\n";
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

	if (empty($_REQUEST['order']))
	{
		$order="submitted";
	}
	else
	{
		$order=$_REQUEST['order'];
	}

	$qryA	="SELECT * FROM lead_inc WHERE sorted!=0 ORDER BY ".$order.";";
	$resA	= mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);

	//$qryB	="SELECT * FROM offices WHERE am!=0 AND active=1 ORDER BY name;";
	//$resB	= mssql_query($qryB);

	echo "<table width=\"950px\">\n";
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
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Name\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"phone\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Phone\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"addr\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Address\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"city\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"City\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"state\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"State\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"zip\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Zip Code\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"submitted\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Submit Date\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"leads\">\n";
		echo "								<input type=\"hidden\" name=\"subq\" value=\"viewproclist\">\n";
		echo "								<input type=\"hidden\" name=\"order\" value=\"added\">\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add Date\">\n";
		echo "							</td>\n";
		echo "         					</form>\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "							<td class=\"ltgray_und\" align=\"left\"></td>\n";
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						</tr>\n";

		while($rowA = mssql_fetch_array($resA))
		{
			$qryB	="SELECT name FROM offices WHERE officeid='".$rowA['tooffice']."';";
			$resB	= mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			echo "						<tr>\n";
			echo "							<td class=\"wh_und\" align=\"left\"><b>".$rowA['lname']."</a></td>\n";
			echo "							<td class=\"wh_und\" align=\"center\"><b>".$rowA['phone']."</b></td>\n";
			echo "							<td class=\"wh_und\" align=\"left\">".$rowA['addr']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\">".$rowA['city']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\">".$rowA['state']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\">".$rowA['zip']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\">".$rowA['submitted']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\">".$rowA['added']."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\"><b>".$rowB['name']."</b></td>\n";
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

function mansort()
{
	$recdate=time();
	if (empty($_REQUEST['toofficeid'])||$_REQUEST['toofficeid']==0)
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> you must select an Office to Process Leads!";
		exit;
	}
	else
	{
		$qry	= "SELECT officeid,active,am,name FROM offices WHERE officeid='".$_REQUEST['toofficeid']."';";
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
				$qryC .= "(added,updated,officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cemail,cconph,chome,cwork,mrktproc,recdate,custid,opt1,opt2,opt3,opt4,source) ";
				$qryC .= "VALUES (";
				$qryC .= "'".$rowA['submitted']."',getdate(),'".$row['officeid']."','".$row['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($rowA['addr'])."',";
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
				$qryC .= "'".$rowA['opt1']."','".$rowA['opt2']."','".$rowA['opt3']."','".$rowA['opt4']."','".$rowA['source']."');";
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
	$cdate		=time();
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
										$qryC .= "(added,updated,officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
										$qryC .= "VALUES (";
										$qryC .= "'".$row['submitted']."',getdate(),'".$rowB['officeid']."','".$rowB['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
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
										$qryC .= "(added,updated,officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
										$qryC .= "VALUES (";
										$qryC .= "'".$row['submitted']."',getdate(),'".$rowBa['officeid']."','".$rowBa['am']."','".$ndata[0]."','".replacequote($ndata[1])."','".replacequote($row['addr'])."',";
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
	//error_reporting(E_ALL);
	//$officeid	=$_SESSION['officeid'];
	//$securityid	=$_SESSION['securityid'];
	//$acclist	=explode(",",$_SESSION['aid']);
	$unxdt		=time();
	
	$qry0 = "SELECT securityid,emailtemplateaccess,searchlandingpage FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	if (isset($_SESSION['tqry']))
	{
		//echo "ZERO<br>";
		$qry	=$_SESSION['tqry'];
		echo "<table align=\"center\" width=\"950px\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>NOTE:</b> These Search Results are based upon previously entered Search parameters. Click <b>New Search</b> to clear this condition.</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
	else
	{
		$qry   = "DECLARE @pdate varchar(10) ";
		$qry  .= "SET @pdate = (CAST(DATEPART(m,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(d,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(yy,(getdate() - 30)) AS varchar)) ";
		$qry  .= "SELECT ";
		$qry  .= "		* ";
		$qry  .= "FROM ";
		$qry  .= "	list_cinfo ";
		$qry  .= "WHERE ";
		$qry  .= "	officeid='".$_SESSION['officeid']."' ";
		
		if (isset($_REQUEST['showdupe']) && $_REQUEST['showdupe']==1)
		{
			$qry  .= "	AND dupe=1 ";
		}
		else
		{
			$qry  .= "	AND dupe=0 ";
		}
		
		if (isset($_REQUEST['d1']) && !empty($_REQUEST['d1']) && isset($_REQUEST['d2']) && !empty($_REQUEST['d2']))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' ";
		}
		elseif (isset($_REQUEST['d3']) && !empty($_REQUEST['d3']) && isset($_REQUEST['d4']) && !empty($_REQUEST['d4']))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d3']."' AND '".$_REQUEST['d4']." 23:59:59' ";
		}
		elseif (isset($_REQUEST['d5']) && !empty($_REQUEST['d5']) && isset($_REQUEST['d6']) && !empty($_REQUEST['d6']))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d5']."' AND '".$_REQUEST['d6']." 23:59:59' ";
		}
		elseif (isset($_REQUEST['d7']) && !empty($_REQUEST['d7']) && isset($_REQUEST['d8']) && !empty($_REQUEST['d8']))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d7']."' AND '".$_REQUEST['d8']." 23:59:59' ";
		}
		elseif (isset($_REQUEST['d9']) && !empty($_REQUEST['d9']) && isset($_REQUEST['d10']) && !empty($_REQUEST['d10']))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d9']."' AND '".$_REQUEST['d10']." 23:59:59' ";
		}
		elseif (isset($_REQUEST['d11']) && !empty($_REQUEST['d11']) && isset($_REQUEST['d12']) && !empty($_REQUEST['d12']))
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d11']."' AND '".$_REQUEST['d12']." 23:59:59' ";
		}
		else
		{
			if (isset($_REQUEST['showaged']) && $_REQUEST['showaged']==0)
			{
				$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN @pdate AND getdate() ";
			}
		}
		
		if ($_REQUEST['call']=="search_results" && $_REQUEST['subq']=="sstring")
		{
			$qry  .= "	AND ".$_REQUEST['field']." LIKE '".htmlspecialchars_decode($_REQUEST['ssearch'])."%' ";
			
			if ($_SESSION['llev'] == 4)
			{
				if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
				{
					$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
				}
				else
				{
					$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
				}
			}
			elseif ($_SESSION['llev'] < 4)
			{
				$qry  .= "	AND securityid='".$_SESSION['securityid']."' ";
			}
		}
		else
		{
			$qry  .= "	AND ".$_REQUEST['field']."='".$_REQUEST['ssearch']."' ";
			
			if (isset($_REQUEST['lsource']) and $_REQUEST['lsource']!='NA')
			{
				$qry  .= "	AND source='".$_REQUEST['lsource']."' ";
			}
			
			if (isset($_REQUEST['lresult']) and $_REQUEST['lresult']!='NA')
			{
				$qry  .= "	AND stage='".$_REQUEST['lresult']."' ";
			}
			
			if ($_SESSION['llev'] == 4 && isset($_REQUEST['field']) && $_REQUEST['field']!="securityid")
			{
				if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
				{
					$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
				}
				else
				{
					$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
				}
			}
			elseif ($_SESSION['llev'] < 4 && isset($_REQUEST['field']) && $_REQUEST['field']!="securityid")
			{
				$qry  .= " AND securityid='".$_SESSION['securityid']."' ";
			}
		}
		
		if (isset($_REQUEST['market']) and strlen(trim($_REQUEST['market'])) >= 2)
		{
			$qry  .= "	AND market='".$_REQUEST['market']."' ";
		}
		
		$qry  .= "ORDER BY ";
		$qry  .= "	".$_REQUEST['order']." ".$_REQUEST['dir'].";";
	}
	
	/*
	if ($_SESSION['securityid']==26)
	{
		echo $qry."<br>";
		//show_post_vars();
	}
	*/
	
	//echo $qry."<br>";
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//echo "BEFORE: ".$_SESSION['tqry']."<br>";

	$_SESSION['tqry']=$qry;

	//echo "AFTER: ".$_SESSION['tqry']."<br>";
	
	//echo $nrows."<br>";
	//exit;
	if ($nrows == 0)
	{
		echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\" class=\"gray\">\n";
		echo "         <b>Your search returned ".$nrows." results.</b>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "<table align=\"center\" width=\"950px\">\n";
		}
		else
		{
			echo "<table align=\"center\">\n";
		}
		
		echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"left\" class=\"gray_und\"></td>\n";
		echo "					<td align=\"left\" class=\"gray_und\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "					<td align=\"right\" class=\"gray_und\"><b>Lead</b> Color Codes:</td>\n";
		echo "					<td align=\"center\" class=\"wh_und\" width=\"75\"><b>Normal</b></td>\n";
		echo "					<td align=\"center\" class=\"grn_und\" width=\"75\"><b>Appt Today</b></td>\n";
		echo "					<td align=\"center\" class=\"magenta_und\" width=\"75\"><b>Call Back</b></td>\n";
		echo "					<td align=\"center\" class=\"yel_und\" width=\"75\"><b>Aged 7 Days</b></td>\n";

		if ($_SESSION['llev'] >= 5)
		{
			echo "					<td align=\"center\" class=\"red_und\" width=\"75\"><b>Duplicate</b></td>\n";
		}

		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>\n";
		echo "         <table class=\"outer\" width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                  	<tr class=\"tblhd\">\n";
		echo "							<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		
		if ($_SESSION['officeid']!=193)
		{
			echo "							<td align=\"center\"><b>Lead ID</b></td>\n";
		}
		
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "							<td align=\"left\" width=\"150\"><b>Company</b></td>\n";
			echo "							<td align=\"left\"><b>Market</b></td>\n";
		}
		else
		{
			echo "							<td align=\"left\" width=\"100\"><b>Last Name</b></td>\n";
			echo "							<td align=\"left\">First Name</td>\n";
		}
		
		echo "                     		<td align=\"left\"><b>Phone</b></td>\n";
		
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "                     		<td align=\"left\" title=\"City where Construction will take place\"><b>Site City</b></td>\n";
		}
		else
		{
			echo "                     		<td align=\"left\" title=\"City where Construction will take place\"><b>Site City</b></td>\n";
			echo "                     		<td align=\"center\" title=\"Zip Code where Construction will take place\"><b>Site Zip</b></td>\n";
		}
		
		echo "							<td align=\"left\"><b>Rep</b></td>\n";
		echo "							<td align=\"center\"><b>Date Added</b></td>\n";
		echo "							<td align=\"center\"><b>Last Update</b></td>\n";
		echo "                  	    <td align=\"center\" width=\"110\"><b>Appnt</b></td>\n";
		echo "							<td align=\"left\"><b>Source</b></td>\n";
		echo "							<td align=\"left\"><b>Result</b></td>\n";
		
		if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
		{
			echo "							<td align=\"center\" title=\"Company Type\"><b>Type</b></td>\n";
		}
		else
		{
			echo "							<td align=\"center\" title=\"JMS LifeCycle\"><b>Life</b></td>\n";
		}

		echo "							<td align=\"center\" title=\"Total Comments for this Lead\"><b>Cmnts</b></td>\n";
		
		//echo "            	         	<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "            	        	<td colspan=\"2\" align=\"right\">".$nrows." Result(s)</td>\n";
		echo "                  	</tr>\n";

		$etemp_ar=array();
		$nph_ar= array('0000000000','none','N/A');
		$age30=2592000; //30 Days
		$age15=1296000; //15 Days
		$age07=604800; // 7 Days
		$age01=86400; // 7 Days
		$ts_tdate=getdate();
		$lcnt=0;
		$altdtext="";
		while($row=mssql_fetch_array($res))
		{
			if ($row['estid']!=0)
			{
				$qryU   = "update jest..est set ccid=".$row['cid']." where officeid=".$row['officeid']." and estid=".$row['estid'].";";
				$resU   = mssql_query($qryU);
			}
			
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

			$secl=explode(",",$row['slevel']);

			if ($secl[6]==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}

			$uid  =md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];

			if (!empty($row['added']))
			{
				$ts_odate=strtotime($row['added']);
				$odate = date("m/d/Y", strtotime($row['added']));
			}
			else
			{
				$ts_odate=0;
				$odate = "";
			}

			if (!empty($row['updated'])||$row['updated']!="")
			{
				$ts_udate=strtotime($row['updated']);
				
				if ($row['updated']!=$row['added'])
				{
					$udate = date("m/d/Y", strtotime($row['updated']));
				}
				else
				{
					$udate = "";
				}
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
				
				$adate = "<table width=\"100%\"><tr><td align=\"left\">".str_pad($row['appt_mo'],2,"0",STR_PAD_LEFT)."/".str_pad($row['appt_da'],2,"0",STR_PAD_LEFT)."/".$row['appt_yr']."</td><td align=\"right\">".$row['appt_hr'].":".str_pad($row['appt_mn'],2,"0",STR_PAD_LEFT)." ".$pa."</td</tr></table>";
				//$adate = str_pad($row['appt_mo'],2,"0",STR_PAD_LEFT)."/".str_pad($row['appt_da'],2,"0",STR_PAD_LEFT)."/".$row['appt_yr']." (".$row['appt_hr'].":".str_pad($row['appt_mn'],2,"0",STR_PAD_LEFT)." ".$pa.")";
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
			elseif ($row['hold']==1 && $row['hold_mo']!='0' && $row['hold_da']!='0' && $row['hold_yr']!='0000' && $ts_hdate > getdate())
			{
				//echo "CALLB HOOK<br>";
				$tbg="magenta_und";
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
			
			//if ($_SESSION['securityid']==)

			if (isset($row['chome']) && !in_array($row['chome'],$nph_ar) && strlen($row['chome']) > 2)
			{
				$cphone	=preg_replace('/\.|-|\s/i','$1$2$3',trim($row['chome']));
				$bstph	="hm";
			}
			elseif (isset($row['ccell']) && !in_array($row['ccell'],$nph_ar) && strlen($row['ccell']) > 2)
			{
				$cphone	=preg_replace('/\.|-|\s/i','$1$2$3',trim($row['ccell']));
				$bstph	="ce";
			}
			elseif (isset($row['cwork']) && !in_array($row['cwork'],$nph_ar) && strlen($row['cwork']) > 2)
			{
				$cphone	=preg_replace('/\.|-|\s/i','$1$2$3',trim($row['cwork']));
				$bstph	="wk";
			}
			else
			{
				$cphone	="";
				$bstph	="";
			}
			
			if (strlen(trim($cphone)) == 7)
			{
				$cphone=substr($cphone,0,3)."-".substr($cphone,3,4);
			}
			elseif (strlen(trim($cphone)) == 10)
			{
				$cphone=substr($cphone,0,3)."-".substr($cphone,3,3)."-".substr($cphone,6,4);
			}			

			$lcnt++;
			echo "                  <tr class=\"".$tbg."\">\n";
			echo "                     <td class=\"pullrec\" align=\"center\">".$lcnt."</td>\n";
			
			if ($_SESSION['officeid']!=193)
			{
				echo "                     <td class=\"pullrec\" align=\"center\"><b>".$row['custid']."</b></td>\n";
			}
			
			if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
			{
				echo "                     <td class=\"pullrec\" align=\"left\" width=\"150\"><b>".htmlspecialchars_decode($row['cpname'])."</b></td>\n";
				echo "                     <td class=\"pullrec\" align=\"left\"><b>".htmlspecialchars_decode($row['market'])."</b></td>\n";
			}
			else
			{
				echo "                     <td class=\"pullrec\" align=\"left\" width=\"100\"><b>".htmlspecialchars_decode($row['clname'])."</b></td>\n";
				echo "                     <td class=\"pullrec\" align=\"left\">".htmlspecialchars_decode($row['cfname'])."</td>\n";
			}
			
			echo "                     <td class=\"pullrec\" align=\"left\"><b>".$cphone."</b></td>\n";
			
			if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
			{
				echo "                     <td class=\"pullrec\" align=\"left\">".$row['scity']."</td>\n";
			}
			else
			{
				echo "                     <td class=\"pullrec\" align=\"left\">".$row['scity']."</td>\n";
				echo "                     <td class=\"pullrec\" align=\"center\">".$row['szip1']."</td>\n";
			}
			
			echo "                     <td class=\"pullrec\" align=\"left\"><font class=\"".$fstyle."\">".substr($row['fname'],0,2).substr($row['lname'],0,3)."</font></td>\n";
			echo "                     <td class=\"pullrec\" align=\"center\">".$odate."</td>\n";
			echo "                     <td class=\"pullrec\" align=\"center\">".$udate."</td>\n";
			echo "                     <td class=\"pullrec\" align=\"center\" width=\"110\">".$adate."</td>\n";

			if ($row['source']==0)
			{
				echo "                     <td class=\"pullrec\" align=\"left\">bluehaven.com</b></td>\n";
			}
			elseif ($row['source'] >= 1)
			{
				echo "                     <td class=\"pullrec\" align=\"left\">".$row['srcname']."</td>\n";
			}

			if ($row['stage']==6)
			{
				echo "                     <td class=\"pullrec\" align=\"left\"><b>".$row['resname']."</b></td>\n";
			}
			else
			{
				echo "                     <td class=\"pullrec\" align=\"left\">".$row['resname']."</td>\n";
			}
			
			echo "                     <td class=\"pullrec\" align=\"center\">\n";
			
			if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
			{
				echo $row['cptype'];
			}
			else
			{
				if ($row['mas_prep'] != 0)
				{
					echo "<b title=\"MAS Ready\">M</b>";
				}
				elseif ($row['njobid'] != '0')
				{
					echo "<b title=\"Job\">J</b>";
				}
				elseif ($row['jobid'] != '0')
				{
					echo "<b title=\"Contract\">C</b>";
				}
				elseif ($row['estid'] != '0' || $row['estcnt'] > 0)
				{
					echo "<b title=\"Quote/Estimate\">Q/E</b>";
				}
				else
				{
					echo "<b title=\"Lead\">L</b>";
				}
			}
			
			echo "					   </td>\n";
			echo "                     <td class=\"pullrec\" align=\"center\">".$row['lcmtcnt']."</td>\n";			
			echo "						<td class=\"pullrec\" align=\"center\">\n";
			
			/*
			echo "                     		<form method=\"POST\">\n";
			echo "                     			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "                     			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "                     			<input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$row['cid']."\">\n";
			echo "                     			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			echo "								<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
			echo "                     		</form>\n";
			*/
			
			if ($_SESSION['llev'] >=4 and (isset($row0['searchlandingpage']) and $row0['searchlandingpage']==1))
			{
				echo "                        <form method=\"POST\">\n";
				echo "							<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "							<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
				echo "							<input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$row['cid']."\">\n";
				echo "							<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
				echo "							<input class=\"transnb_button\" type=\"image\" src=\"images/application_view_list.png\" title=\"Open OneSheet\">\n";
				echo "						</form>\n";
			}
			else
			{
				echo "                     		<form method=\"POST\">\n";
				echo "                     			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
				echo "                     			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
				echo "                     			<input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$row['cid']."\">\n";
				echo "                     			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
				echo "								<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
				echo "							</form>\n";
			}
			
			echo "                     	</td>\n";
			echo "                     <td align=\"center\">\n";
			
			if ($row['estcnt'] > 0 && $row['jobid']=='0')
			{
				echo "<span class=\"lifemenu\">\n";
				$qryE= "select estid,officeid,esttype from jest..est where officeid=".$row['officeid']." and ccid=".$row['cid'].";";
				$resE= mssql_query($qryE);
				
				echo "							<form id=\"estselect\" name=\"est_".$row['cid']."\" method=\"POST\">\n";
				echo "							<input type=\"hidden\" name=\"action\" value=\"est\">\n";
				echo "							<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				echo "							<select class=\"transnb\" id=\"estid\" name=\"estid\" onChange=\"this.form.submit();\">\n";
				echo "								<option value=\"0\">View...</option>\n";
				
				while ($rowE= mssql_fetch_array($resE))
				{
					echo "								<option value=\"".$rowE['estid']."\">".$rowE['esttype']."".$rowE['estid']."</option>\n";	
				}
				
				echo "							</select>\n";
				echo "                        </form>\n";
				echo "</span>\n";
			}
			
			echo "						</td>\n";
			//echo "                     	<td align=\"center\">".$lcnt."</td>\n";
			echo "                  </tr>\n";
			
			if (isset($_REQUEST['cmtcnt']) && $_REQUEST['cmtcnt'] > 0)
			{
				$qryCMT  = "SELECT TOP ".$_REQUEST['cmtcnt']." * ";
				$qryCMT .= ",(SELECT lname FROM security WHERE securityid=ch.secid) AS lsname ";
				$qryCMT .= ",(SELECT fname FROM security WHERE securityid=ch.secid) AS fsname ";
				$qryCMT .= "FROM chistory AS ch WHERE custid='".$row['cid']."' ORDER by mdate DESC;";
				$resCMT  = mssql_query($qryCMT);
				$nrowCMT = mssql_num_rows($resCMT);
			}
			
			if (isset($_REQUEST['incaddr']) && $_REQUEST['incaddr']==1)
			{
				echo "                  <tr>\n";
				echo "                     <td class=\"gray\" align=\"right\" colspan=\"11\"><b>Address:</b></td>\n";
				echo "                     <td class=\"wh_undsidesl\"  align=\"left\"></td>\n";
				echo "                     <td class=\"wh_und\"  align=\"left\" colspan=\"5\">".$altdtext."</td>\n";
				echo "					</tr>\n";
				echo "                  <tr>\n";
				
				if (isset($_REQUEST['cmtcnt']) && $_REQUEST['cmtcnt'] > 0 && $nrowCMT > 0)
				{	
					echo "                     <td class=\"gray\" align=\"right\" colspan=\"11\"><b>Email:</b></td>\n";
				}
				else
				{
					echo "                     <td class=\"gray_und\" align=\"right\" colspan=\"11\"><b>Email:</b></td>\n";
				}
				
				echo "                     <td class=\"wh_undsidesl\"  align=\"left\"></td>\n";
				echo "                     <td class=\"wh_und\"  align=\"left\" colspan=\"5\">".$row['cemail']."</td>\n";
				echo "					</tr>\n";
			}
			
			if (isset($_REQUEST['cmtcnt']) && $_REQUEST['cmtcnt'] > 0 && $nrowCMT > 0)
			{
				if ($nrowCMT > 0)
				{
					$snt=1;
					while ($rowCMT = mssql_fetch_array($resCMT))
					{
						$cmtxt="";
						if ($snt==1)
						{
							//$cmtxt=$row['custid']." ".$row['clname'].", ".$row['cfname']." Comment(s)";
							$cmtxt='';
						}
						
						echo "                  </tr>\n";
						
						if ($snt==$nrowCMT)
						{
							echo "                     <td class=\"gray_und\" align=\"right\" valign=\"top\" colspan=\"11\"><b>".$cmtxt."</b></td>\n";
						}
						else
						{
							echo "                     <td class=\"gray\" align=\"right\" valign=\"top\" colspan=\"11\"><b>".$cmtxt."</b></td>\n";
						}
						
						echo "                     <td class=\"wh_undsidesl\" align=\"left\" valign=\"top\"><table width=\"100%\"><tr><td align=\"left\">".date("m/d/Y",strtotime($rowCMT['mdate']))."</td</tr></table></td>\n";
						echo "                     <td class=\"wh_und\" align=\"left\" valign=\"top\">".$rowCMT['lsname'].", ".$rowCMT['fsname']."</td>\n";
						echo "                     <td class=\"wh_undsidesr\" align=\"left\" valign=\"top\" colspan=\"4\" width=\"200px\">".htmlspecialchars_decode($rowCMT['mtext'])."</td>\n";
						echo "                  </tr>\n";
						$snt++;
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
		
		if ($_SESSION['securityid']==26 && $_REQUEST['call']=='search_results' && $row0['emailtemplateaccess'] >= 1 && isset($_REQUEST['etid']) && $_REQUEST['etid']!=0 && count($etemp_ar) > 0)
		{
			//$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
			$qryET1 = "select etid,name from jest..EmailTemplate where etid=".$_REQUEST['etid'].";";
			$resET1 = mssql_query($qryET1);
			$rowET1 = mssql_fetch_array($resET1);
			
			echo "	<tr>\n";
			echo "		<td align=\"left\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td align=\"right\">\n";
			echo "						<form method=\"POST\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"procemaillist\">\n";
			echo "						<input type=\"hidden\" name=\"etid\" value=\"".$_REQUEST['etid']."\">\n";
			echo "						<input type=\"hidden\" name=\"et_uid\" value=\"".$_REQUEST['et_uid']."\">\n";
			echo "						<input type=\"hidden\" name=\"chistory\" value=\"1\">\n";
			echo "						<input type=\"hidden\" name=\"etest\" value=\"1\">\n";
			
			foreach ($etemp_ar as $nET => $vET)
			{
				echo "						<input type=\"hidden\" name=\"etcid[]\" value=\"".$vET."\">\n";
			}
			
			echo "							<table width=\"100%\">\n";
			echo "								<tr>\n";
			echo "									<td align=\"left\" class=\"gray\"><b>Email List Processing</b></td>\n";
			echo "									<td align=\"center\" class=\"gray\"><b>".count($etemp_ar)."</b> recipient(s) will receive the <b>".$rowET1['name']."</b> Email.</td>\n";
			echo "									<td align=\"right\" class=\"gray\">Process Email List? <input class=\"transnb\" type=\"checkbox\" id=\"confirmemaillist\" name=\"confirmemaillist\" value=\"1\" title=\"Confirm\"><input class=\"transnb\" type=\"image\" src=\"images/table_go.png\" alt=\"Process Email List\" onClick=\"return ConfirmChecked('confirmemaillist');\"></td>\n";
			echo "								</tr>\n";
			echo "							</table>\n";
			echo "						</form>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		
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

    if (empty($_REQUEST['appt_mo']))
    {
            $mdate=$curr_date['mon'];
            $ndate=$curr_date['month'];
    }
    else
    {
            $mdate=$_REQUEST['appt_mo'];
            $ndate=date("F", mktime(0, 0, 0, $_REQUEST['appt_mo'], 1, $curr_date['year']));
    }

    if (empty($_REQUEST['appt_da']))
    {
            $ddate=$curr_date['mday'];
    }
    else
    {
            $ddate=$_REQUEST['appt_da'];
    }

    if (empty($_REQUEST['appt_yr']))
    {
            $ydate=$curr_date['year'];
    }
    else
    {
            $ydate=$_REQUEST['appt_yr'];
    }

    $pstyr=2004;
    $futyr=$curr_date['year']+1;
    //$qry   = "select * from cinfo where officeid='".$_SESSION['officeid']."' and appt_mo='".$mdate."' and appt_da!='0' and appt_yr='".$ydate."' and dupe!=1 and hold!=1 order by appt_da DESC;";
	$qry   = "select * from cinfo where officeid='".$_SESSION['officeid']."' and appt_mo='".$mdate."' and appt_da!='0' and appt_yr='".$ydate."' and dupe!=1 order by appt_da DESC,appt_pa DESC,appt_hr DESC,appt_mn DESC;";
    $res   = mssql_query($qry);
    $nrows = mssql_num_rows($res);

    /*if ($_SESSION['securityid']==26)
    {
            echo $qry;
    }*/

    if ($nrows < 1)
    {
        echo "<table align=\"center\" width=\"400px\">\n";
        echo "   <tr>\n";
        echo "      <td>\n";
        echo "         <b>No Appointments for ".$ndate.", ".$curr_date['year']."</b>\n";
        echo "      </td>\n";
        echo "   </tr>\n";
        echo "</table>\n";
    }
    else
    {
        echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
        echo "  <tr>\n";
        echo "      <td align=\"left\" class=\"gray\">\n";
        echo "          <form method=\"post\">\n";
        echo "          <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
        echo "          <input type=\"hidden\" name=\"call\" value=\"appts\">\n";
        echo "          <table width=\"100%\">\n";
        echo "              <tr>\n";
        echo "                  <td align=\"left\" class=\"gray\"><b>Appointments</b> \n";
        echo "                      <select name=\"appt_mo\">\n";

        for ($x = 1; $x <= 12; $x++)
        {
            $m_name=date("F", mktime(0, 0, 0, $x, 1, $curr_date['year']));
            if ($x == $mdate)
            {
                echo "                          <option value=\"".$x."\" SELECTED>".$m_name."</option>\n";
            }
            else
            {
                echo "                          <option value=\"".$x."\">".$m_name."</option>\n";
            }
        }

        echo "                      </select>\n";
        echo "                      <select name=\"appt_yr\">\n";

        for ($x = $pstyr; $x <= $futyr; $x++)
        {
            if ($x == $ydate)
            {
                echo "                          <option value=\"".$x."\" SELECTED>".$x."</option>\n";
            }
            else
            {
                echo "                          <option value=\"".$x."\">".$x."</option>\n";
            }
        }

        echo "                      </select>\n";
        echo "                      <input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
        echo "                  </td>\n";
        echo "                  <td align=\"left\" class=\"gray\"><b>".$_SESSION['offname']."</b></td>\n";
        echo "                  <td align=\"right\" class=\"gray\"><b>Lead</b> Color Codes:</td>\n";
        echo "                  <td align=\"center\" class=\"white\" width=\"75px\"><b>Normal</b></td>\n";
        echo "                  <td align=\"center\" class=\"lightgreen\" width=\"75px\"><b>Appt Today</b></td>\n";
        echo "                  <td align=\"center\" class=\"yellow\" width=\"75px\"><b>Aged 7 Days</b></td>\n";
		echo "                  <td align=\"center\" class=\"magenta\" width=\"75px\"><b>Callback Set</b></td>\n";
        echo "                  <td align=\"center\" class=\"gray\" width=\"20px\">\n";
        
        HelpNode('LeadApptListing',1);
        
        echo "                  </td>\n";
        echo "              </tr>\n";
        echo "          </table>\n";
        echo "          </form>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "      <td class=\"gray\">\n";
        echo "         <table width=\"100%\">\n";
        echo "            <tr>\n";
        echo "               <td align=\"left\">\n";
        echo "                  <table width=\"100%\">\n";
        echo "                      <tr class=\"tblhd\">\n";
        echo "						    <td align=\"center\"><b>Lead ID</b></td>\n";
        echo "							<td align=\"left\"><b>Last Name</b></td>\n";
        echo "							<td align=\"left\"><b>First Name</b></td>\n";
        echo "                     	    <td align=\"left\"><b>Phone</b></td>\n";
        echo "							<td align=\"left\"><b>Assigned</b></td>\n";
        echo "							<td align=\"left\"><b>Added</b></td>\n";
        echo "							<td align=\"left\"><b>Updated</b></td>\n";
        echo "                     	    <td align=\"left\"><b>Apptmnt</b></td>\n";
        echo "							<td align=\"left\"><b>Source</b></td>\n";
        echo "							<td align=\"left\"><b>Status</b></td>\n";
        echo "                     	    <td align=\"right\">\n";
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
								/*
                                elseif ($row['hold']==1)
                                {
                                        $tbg="magenta_und";
                                }
								*/
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
												elseif ($row['hold']==1)
												{
													$tbg="magenta_und";
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

                                echo "                  <tr class=\"".$tbg."\">\n";
                                echo "                     <td align=\"center\"><b>".$row['custid']."</b></td>\n";
                                echo "                     <td align=\"left\"><b>".$row['clname']."</b></td>\n";
                                echo "                     <td align=\"left\">".$row['cfname']."</td>\n";
                                echo "                     <td align=\"left\"><b>".$cphone."</b></td>\n";
                                echo "                     <td align=\"left\">".$rowC['lname'].", ".$rowC['fname']."</td>\n";
                                echo "                     <td align=\"left\">".$odate."</td>\n";
                                echo "                     <td align=\"left\">".$udate."</td>\n";
                                echo "                     <td align=\"left\">".$adate."</td>\n";

                                if ($row['source']==0)
                                {
                                        echo "                     <td align=\"left\">bluehaven.com</b></td>\n";
                                }
                                elseif ($row['source'] >= 1)
                                {
                                        echo "                     <td align=\"left\">".$rowF['name']."</td>\n";
                                }

                                if ($rowE['statusid']==6)
                                {
                                        echo "                     <td align=\"left\"><b>".$rowE['name']."</b></td>\n";
                                }
                                else
                                {
                                        echo "                     <td align=\"left\">".$rowE['name']."</td>\n";
                                }

                                echo "                     <td align=\"right\">\n";
                                echo "                        <form method=\"POST\">\n";
                                echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
                                echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
                                echo "                           <input type=\"hidden\" name=\"cid\" id=\"recid\" value=\"".$row['cid']."\">\n";
                                echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
                                echo "				             <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
                                echo "                        </form>\n";
                                echo "                     </td>\n";
                                echo "                  </tr>\n";
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
	if ($_REQUEST['type']=="proc"||$_GET['type']=="proc")
	{
		$qryF = "SELECT * FROM lead_inc WHERE lid='".$_REQUEST['lid']."';";
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
		echo "                        <td align=\"right\"></td>\n";
		echo "                    	</tr>\n";
		echo "                     <tr>\n";
		echo "                        <td colspan=\"2\">\n";
		echo "                           <table class=\"outer\" border=\"0\" width=\"100%\">\n";
		echo "                           	<tr>\n";
		echo "                              	<td align=\"right\"><b>Date Submitted:</b>\n";
		echo "                                 <td align=\"left\">".$rowF['submitted']."</td>\n";
		echo "                                 <td align=\"right\"><b>Date Received: </b></td>\n";
		echo "                                 <td align=\"left\">".$rowF['added']."</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td valign=\"top\" align=\"left\">\n";
		echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"125\">\n";
		echo "										<tr>\n";
		echo "											<td colspan=\"2\"><b>Customer:</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\">Name</td>\n";
		echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"clname\" value=\"".$rowF['lname']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\">Phone</td>\n";
		echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\" value=\"".$rowF['phone']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\">Best Phone</td>\n";
		echo "											<td align=\"left\">\n";
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
		echo "											<td align=\"right\">Email</td>\n";
		echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"cemail\" size=\"30\" value=\"".$rowF['email']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\">Contact Time</td>\n";
		echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"ccontime\" value=\"".$rowF['contime']."\"></td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "								<td valign=\"top\" align=\"left\">\n";
		echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"125\">\n";
		echo "										<tr>\n";
		echo "											<td colspan=\"2\" valign=\"top\"><b>Site Address</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "												<td align=\"right\">Street:</td>\n";
		echo "												<td><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\" value=\"".$rowF['addr']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "												<td align=\"right\">City:</td>\n";
		echo "												<td><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['city']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['state']."\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "												<td align=\"right\">Zip:</td>\n";
		echo "												<td><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['zip']."\"></td>\n";
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
	elseif ($_REQUEST['type']=="unproc"||$_GET['type']=="unproc")
	{
		$qryF = "SELECT * FROM lead_inc_bucket WHERE id='".$_REQUEST['lid']."';";
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
		echo "                        <td align=\"right\"></td>\n";
		echo "                    	</tr>\n";
		echo "                     <tr>\n";
		echo "                        <td colspan=\"2\">\n";
		echo "                           <table class=\"outer\" border=\"0\" width=\"100%\">\n";
		echo "                           	<tr>\n";
		echo "                                 <td align=\"left\"><b>Date Received: </b></td>\n";
		echo "                                 <td align=\"left\">".$rowF['added']."</td>\n";
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
	$dates		=dateformat();
	$uid		=md5(session_id().time()).".".$_SESSION['securityid'];
	$acclist	=explode(",",$_SESSION['aid']);
	$curryr		=date("Y");
	$futyr 		=$curryr+1;

	//if ($_SESSION['llev'] >= 7)
	//{
	//	$qryA = "SELECT officeid,name,stax FROM offices ORDER BY name ASC;";
	//}
	//else
	//{
		$qryA = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	//}
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT stax,intro_etid as ietid FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[0]!=0)
	{
		$qryD = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resD = mssql_query($qryD);

		$qryE = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resE = mssql_query($qryE);
	}

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=2 AND statusid!=0 AND access!=9 AND provided=0 and (oid=0 or oid=".$_SESSION['officeid'].") ORDER by name ASC;";
	$resG = mssql_query($qryG);

	$hlpnd=1;

	echo "<script type=\"text/javascript\" src=\"js/jquery_leads_new.js\"></script>\n";
	
	if ($_SESSION['securityid']==269999999999999999999999)
	{
		echo "<script type=\"text/javascript\" src=\"js/jquery_leads_TED.js\"></script>\n";
	}
	
	echo "<table width=\"950px\" align=\"center\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "		<table width=\"100%\" align=\"center\">\n";
	echo "			<tr>\n";
	echo "				<td>\n";
	echo "				<form id=\"newlead\" method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"add\">\n";
	echo "				<input type=\"hidden\" name=\"recdate\" value=\"".$dates[1]."\">\n";
	echo "				<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "				<input type=\"hidden\" name=\"comments\" value=\"\">\n";
	
	if (isset($rowC[1]) and $rowC[1]!=0)
	{
		echo "				<input type=\"hidden\" name=\"intro_email\" id=\"intro_email\" value=\"1\">\n";
	}
	
	echo "					<table border=\"0\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "								<table border=\"0\" width=\"100%\">\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"left\"><b><b>Lead Entry</b></td>\n";
	echo "													<td class=\"gray\" align=\"right\"><font color=\"blue\"> Required Fields</font>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"right\"><b>Date</b>\n";
	echo "													<td class=\"gray\" align=\"left\">".$dates[0]."</td>\n";
	echo "													<td class=\"gray\" align=\"right\"><b>Office </b></td>\n";
	echo "													<td class=\"gray\" align=\"left\">\n";

	/*
	if ($_SESSION['llev'] >= 7)
	{
		echo "													<select name=\"site\" id=\"soid\">\n";
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
	*/
		$rowA = mssql_fetch_row($resA);
		//print_r($rowA)."<BR>";
		echo "                                 	".$rowA[1]."<input type=\"hidden\" name=\"officeid\" id=\"soid\" value=\"".$rowA[0]."\">\n";
	//}

	echo "													</td>\n";
	echo "													<td class=\"gray\" align=\"right\"></td>\n";
	echo "													<td class=\"gray\" align=\"right\"><b>Salesrep</b> \n";

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
	echo "													<td class=\"gray\" width=\"20px\" align=\"right\">\n";

	HelpNode('cformadddatepanel',$hlpnd++);

	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" valign=\"top\">\n";
	echo "											<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" valign=\"top\"><b>Customer</b></td>\n";
	echo "													<td class=\"gray\" align=\"right\">\n";

	HelpNode('cformaddcustomerpanel',$hlpnd++);

	echo "													</td>\n";
	echo "												</tr>\n";
	
	if ($_SESSION['officeid']==193 or $_SESSION['officeid']==199)
	{
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Company Name</td>\n";
		echo "												<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"30\" name=\"cpname\" id=\"cpname\"></td>\n";
		echo "											</tr>\n";
		echo "												<tr>\n";
		echo "													<td class=\"gray\" width=\"100px\" align=\"right\"></td>\n";
		echo "													<td class=\"gray\" align=\"left\"><div id=\"CompanyNameData\"></div></td>\n";
		echo "												</tr>\n";
	}
	
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\"><font color=\"blue\">First Name</font></td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"30\" id=\"cfname\" name=\"cfname\"></td>\n";
	echo "												</tr>\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "												<tr>\n";
		echo "													<td class=\"gray\" width=\"100px\" align=\"right\"><font color=\"blue\">Last Name</font></td>\n";
		echo "													<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"30\" name=\"clname\" id=\"clname\"></td>\n";
		echo "												</tr>\n";
		echo "												<tr>\n";
		echo "													<td class=\"gray\" width=\"100px\" align=\"right\"></td>\n";
		echo "													<td class=\"gray\" align=\"left\"><div id=\"LeadNameData\"></div></td>\n";
		echo "												</tr>\n";
	}
	else
	{
		echo "												<tr>\n";
		echo "													<td class=\"gray\" width=\"100px\" align=\"right\"><font color=\"blue\">Last Name</font></td>\n";
		echo "													<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"30\" name=\"clname\" id=\"clname\"></td>\n";
		echo "												</tr>\n";
	}
	
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\"><span class=\"JMStooltip\" title=\"Input the Customer Home Phone without dashes or dots. e.g. 123456789\"><font color=\"blue\">Home Phone</font></span></td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\" id=\"chome\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Work Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\" id=\"cwork\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Cell Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\" id=\"ccell\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Fax</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" id=\"cfax\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Best Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\">\n";
	echo "														<select name=\"cconph\" id=\"cconph\">\n";
	echo "															<option value=\"hm\">Home</option>\n";
	echo "															<option value=\"wk\">Work</option>\n";
	echo "															<option value=\"ce\">Cell</option>\n";
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Contact Time</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" size=\"30\" name=\"ccontime\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\"><span class=\"JMStooltip\" title=\"Input <strong><b>NA</b></strong> in this field if you do not have, or the Customer will not provide, a valid email\"><font color=\"blue\">E-Mail</font></span></td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"cemail\" size=\"30\" id=\"cemail\"></td>\n";
	echo "												</tr>\n";
	
	//if (($_SESSION['securityid']==26 or $_SESSION['securityid']==1950) && $rowC[1]!=0)
	if (isset($rowC[1]) and $rowC[1]!=0)
	{
		echo "												<tr>\n";
		echo "													<td align=\"right\"><input class=\"transnb JMStooltip\" type=\"checkbox\" name=\"introbypass\" value=\"1\" title=\"Check the box to prevent an Introduction Letter from being sent when adding the Lead\"></td>\n";
		echo "													<td align=\"left\"><div class=\"JMStooltip\" title=\"Check the box to prevent an Introduction Letter from being sent when adding the Lead\">Bypass Introduction Letter</td>\n";
		echo "												</tr>\n";
	}
	
	echo "												</table>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" valign=\"top\">\n";
	echo "											<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" valign=\"top\"><b>Current Address</b></td>\n";
	echo "													<td class=\"gray\" align=\"right\">\n";

	HelpNode('cformaddaddresspanel',$hlpnd++);

	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\"><font color=\"blue\">Street</font></td>\n";
	echo "													<td class=\"gray\"><input class=\"bboxb\" type=\"text\" size=\"50\" name=\"caddr1\" id=\"caddr1\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\"><font color=\"blue\">City</font></td>\n";
	echo "													<td class=\"gray\"><input class=\"bboxb\" type=\"text\" size=\"20\" name=\"ccity\" id=\"ccity\"> State <input class=\"bboxb\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" id=\"cstate\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\"><font color=\"blue\">Zip</font></td>\n";
	echo "													<td class=\"gray\"><input class=\"bboxb\" type=\"text\" size=\"6\" maxlength=\"5\" id=\"czip1\" name=\"czip1\">-<input class=\"bboxb\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\" id=\"czip2\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Cnty/Twnshp</td>\n";
	echo "													<td class=\"gray\">\n";

	if ($rowC[0]==0)
	{
		echo "												<input class=\"bboxb\" type=\"text\" size=\"18\" name=\"ccounty\" id=\"ccounty\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "												<select name=\"ccounty\" id=\"ccounty\">\n";
		while ($rowD = mssql_fetch_row($resD))
		{
			echo "												<option value=\"".$rowD[0]."\">".$rowD[2]."</option>\n";
		}
		echo "												</select>\n";
	}
	else
	{
		echo "											<input class=\"bboxb\" type=\"text\" size=\"18\" name=\"ccounty\" id=\"ccounty\">\n";
	}

	echo "												Map <input class=\"bboxb\" type=\"text\" size=\"10\" name=\"cmap\" id=\"cmap\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Pool Site Address</b> <input class=\"transnb\" type=\"checkbox\" name=\"ssame\" id=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Street</td>\n";
	echo "													<td class=\"gray\"><input class=\"bboxb\" type=\"text\" size=\"50\" name=\"saddr1\" id=\"saddr1\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">City</td>\n";
	echo "													<td class=\"gray\"><input class=\"bboxb\" type=\"text\" size=\"20\" name=\"scity\" id=\"scity\"> State <input class=\"bboxb\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" id=\"sstate\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Zip</td>\n";
	echo "													<td class=\"gray\"><input class=\"bboxb\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" id=\"szip1\">-<input class=\"bboxb\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" id=\"szip2\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Cnty/Twnshp</td>\n";
	echo "													<td class=\"gray\">\n";

	if ($rowC[0]==0)
	{
		echo "													<input class=\"bboxb\" type=\"text\" size=\"18\" name=\"scounty\" id=\"scounty\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "													<select name=\"scounty\" id=\"scounty\">\n";
		while ($rowE = mssql_fetch_row($resE))
		{
			echo "														<option value=\"".$rowE[0]."\">".$rowE[2]."</option>\n";
		}
		echo "														</select>\n";
	}
	else
	{
		echo "													<input class=\"bboxb\" type=\"text\" size=\"18\" name=\"scounty\" id=\"scounty\">\n";
	}

	echo "											Map <input class=\"bboxb\" type=\"text\" size=\"10\" name=\"smap\" id=\"smap\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"left\" valign=\"top\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\" height=\"100\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" valign=\"top\">\n";
	echo "											<table class=\"transnb\" width=\"100%\">\n";
	echo "                           								<tr>\n";
	echo "													<td class=\"gray\" align=\"left\" valign=\"top\"><b>Appointment / Source</b></td>\n";
	echo "													<td class=\"gray\" align=\"right\">\n";

	HelpNode('cformaddappointmentpanel',$hlpnd++);

	echo "													</td>\n";
	echo "                           								</tr>\n";
	echo "                     									<tr>\n";
	echo "                        										<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
	echo "                           										<table border=\"0\" width=\"100%\">\n";
	echo "															<tr>\n";
	echo "																<td align=\"right\"><b>Date</b></td>\n";
	echo "																<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "																	<table border=0>\n";
	echo "																		<tr>\n";
	echo "																			<td valign=\"top\">\n";
	echo "                                             																<select name=\"appt_mo\" id=\"appt_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		echo "                                             																	<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "																			</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_da\" id=\"appt_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		echo "                                             																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "																			</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_yr\" id=\"appt_yr\">\n";
	echo "                                             																	<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr; $yr<=$futyr; $yr++)
	{
		echo "                                             																	<option value=\"".$yr."\">".$yr."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "																			</td>\n";
	echo "																		</tr>\n";
	echo "																	</table>\n";
	echo "                           														</td>\n";
	echo "                           													</tr>\n";
	echo "                           													<tr>\n";
	echo "																<td align=\"right\"><b>Time</b></td>\n";
	echo "																<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "																	<table border=0>\n";
	echo "																		<tr>\n";
	echo "																			<td align=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_hr\" id=\"appt_hr\">\n";

	for ($hr=0; $hr<=12; $hr++)
	{
		echo "                                             																<option value=\"".$hr."\">".$hr."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "                                             															</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_mn\" id=\"appt_mn\">\n";

	for ($mn=0; $mn<=60; $mn++)
	{
		echo "                                             																<option value=\"".$mn."\">".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             																</select>\n";
	echo "                                             															</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "                                             															<td valign=\"left\" valign=\"top\">\n";
	echo "                                             																<select name=\"appt_pa\" id=\"appt_pa\">\n";
	echo "                                             																	<option value=\"1\">AM</option>\n";
	echo "                                             																	<option value=\"2\">PM</option>\n";
	echo "                                             																</select>\n";
	echo "                                             															</td>\n";
	echo "                                             														</tr>\n";
	echo "                                             													</table>\n";
	echo "                                             												</td>\n";
	echo "                                             											</tr>\n";
	echo "                                             											<tr>\n";
	echo "                                             												<td align=\"right\"><font color=\"blue\">Lead Source</font></td>\n";
	echo "                                             												<td align=\"left\">\n";
	echo "                                             													<select name=\"source\" id=\"source\">\n";

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
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "                                             						</td>\n";
	echo "                                             						<td colspan=\"2\" align=\"right\" valign=\"top\">\n";
	echo "											<table class=\"outer\" width=\"100%\" height=\"100\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" valign=\"top\">\n";
	echo "                                             							<table class=\"transnb\" width=\"100%\">\n";
	echo "                                             								<tr>\n";
	echo "                                             									<td class=\"gray\" align=\"left\" valign=\"top\"><b>Comments/Directions</b></td>\n";
	echo "															<td class=\"gray\" align=\"right\">\n";

	HelpNode('cformaddcommentpanel',$hlpnd++);

	echo "															</td>\n";
	echo "                                             								</tr>\n";
	echo "                                             								<tr>\n";
	echo "                                             									<td colspan=\"2\" class=\"gray\" valign=\"top\" align=\"left\">\n";
	echo "																<textarea name=\"comments\" rows=\"3\" cols=\"100\"></textarea>\n";
	echo "															</td>\n";
	echo "                                             								</tr>\n";
	echo "                                             							</table>\n";
	echo "                                             						</td>\n";
	echo "                                             					</tr>\n";
	echo "                                             				</table>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "                                             			</td>\n";
	echo "                                             		</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"left\" valign=\"top\">\n";
	echo "											<table class=\"transnb\" width=\"100%\" height=\"50\">\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\">\n";
	echo "											<table class=\"outer\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" valign=\"top\">\n";
	echo "											<table class=\"transnb\" width=\"100%\">\n";
	echo "                           								<tr>\n";
	echo "																<td class=\"gray\" colspan=\"2\" align=\"left\" valign=\"top\"><b>Privacy Policy</b></td>\n";
	echo "                           								</tr>\n";
	echo "                           								<tr>\n";
	echo "													<td class=\"gray\" width=\"20px\" align=\"right\" valign=\"top\">\n";
	echo "														<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\">\n";
	echo "														<input type=\"hidden\" name=\"opt2\" value=\"0\">\n";
	echo "														<input type=\"hidden\" name=\"opt3\" value=\"0\">\n";
	echo "														<input type=\"hidden\" name=\"opt4\" value=\"0\">\n";
	echo "													</td>\n";
	echo "													<td class=\"gray\" align=\"left\">Customer does not wish to receive any future information about updates, special offers, or other communications regarding Blue Haven-related products, supplies, or services.</td>\n";	
	echo "                           								</tr>\n";
	echo "											</table>\n";
	echo "                                             						</td>\n";
	echo "                                             					</tr>\n";
	echo "                                             				</table>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
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
	echo "					<div class=\"noPrint\">\n";
	echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add Lead\" onClick=\"return VerifyLeadForm();\" title=\"Click this button to Add the Lead\">\n";
	echo "					</div>\n";
	echo "				</td>\n";
	echo "			</form>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

//function cform_view($tcid)
function cform_view($tcid)
{
	unset($_SESSION['ifcid']); // Security Setting for embedded frames and AJAX
	
	//echo 'NEW LEAD VIEW<br>';
	$src_ex=array();
	$acclist=explode(",",$_SESSION['aid']);

	if (empty($_REQUEST['uid']))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error occured.\n";
		exit;
	}

	if (isset($tcid) && $tcid!=0)
	{
		$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid=".$tcid.";";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);

		$cid=$row0['cid'];
	}
	else
	{
		if (isset($_REQUEST['subq']) && $_REQUEST['subq']=="custid")
		{
			$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['custid']."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$cid=$row0['cid'];
		}
		else
		{
			$cid=$_REQUEST['cid'];
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

	$qryC = "SELECT stax,enest,encon,finan_off,finan_from,otype_code FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[0]!=0)
	{
		$qryD = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resD = mssql_query($qryD);

		$qryE = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resE = mssql_query($qryE);
	}

	$qryF = "SELECT * FROM cinfo WHERE officeid=".$_SESSION['officeid']." AND cid=".$cid.";";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=1 AND access!=0 ORDER by name ASC;";
	$resG = mssql_query($qryG);
	
	//$qryGa = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$qryGa = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$resGa = mssql_query($qryGa);
	
	while ($rowGa = mssql_fetch_array($resGa))
	{
		$src_ex[]=$rowGa['statusid'];
	}

	$qryH = "SELECT * FROM leadstatuscodes WHERE active=2 AND access!=0  AND access!=9 and (oid=0 or oid=".$_SESSION['officeid'].") ORDER by name ASC;";
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

	$qryL = "SELECT C1.*,(SELECT lname FROM security WHERE securityid=C1.secid) as slname,(SELECT fname FROM security WHERE securityid=C1.secid) as sfname FROM chistory AS C1 WHERE C1.custid='".$cid."' ORDER BY C1.mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);

	$qryM = "SELECT securityid,emailtemplateaccess,filestoreaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resM = mssql_query($qryM);
	$rowM = mssql_fetch_array($resM);

	$adate = date("m-d-Y (g:i A)", strtotime($rowF['added']));
	//$sdate = date("m-d-Y (g:i A)", strtotime($rowF['submitted']));
	$curryr=date("Y");
	$futyr =$curryr+2;

	if ($_REQUEST['uid']=="XXX")
	{
		$dis="DISABLED";
	}
	else
	{
		$dis="";
	}

	if ($_SESSION['llev'] < 9 && !in_array($rowI['securityid'],$acclist))
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
	$tranid=time().".".$cid.".".$_SESSION['securityid'];
	
	$hlpnd=1;
	
	$lwidth='340px';
	$rwidth='530px';
	
	if ($rowC[5] == 2) // Active/Inactive Selector
	{
		$aiupdate=5;
	}
	else
	{
		$aiupdate=6;
	}
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_lead_view.js?".time()."\"></script>\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table width=\"850px\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "		<table width=\"100%\" align=\"center\" border=0>\n";
	echo "   		<tr>\n";
	echo "      	<td>\n";
	echo "      	<form name=\"cview1\" id=\"UpdateLeadForm\" method=\"post\" ".$dis.">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"edit\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" id=\"sysCID\" value=\"".$rowF['cid']."\">\n";
	echo "         <input type=\"hidden\" name=\"tranid\" value=\"".$tranid."\">\n";
	echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
	echo "         	<table border=\"0\" width=\"100%\">\n";
	echo "         		<tr>\n";
	echo "            		<td>\n";
	echo "               	<table border=\"0\" width=\"100%\">\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"left\">\n";
	echo "               			<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                     			<tr>\n";
	echo "                        			<td class=\"gray\" align=\"left\"><b>Lead # <font color=\"blue\">".$rowF['cid']."</font></b></td>\n";
	echo "                        			<td class=\"gray\" align=\"right\">\n";

	if ($_SESSION['llev'] >= $aiupdate)
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
	echo "									</td>\n";
	echo "                                 	<td class=\"gray\" align=\"right\" width=\"20px\">\n";
	echo "										<img class=\"getHelpNode\" id=\"CformViewHeadPanel\" src=\"images/help.png\" title=\"Lead Help\">\n";
	echo "									</td>\n";
	echo "                    			</tr>\n";
	echo "                    		</table>\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "					<tr>\n";
	echo "						<td colspan=\"2\" align=\"right\">\n";
	echo "							<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "								<tr>\n";
	echo "									<td class=\"gray\" align=\"right\"><b>Date:</b>\n";
	echo "									<td class=\"gray\" align=\"left\">".$adate."</td>\n";
	echo "									<td class=\"gray\" align=\"right\"><b>Office: </b></td>\n";
	echo "									<td class=\"gray\" align=\"left\">\n";

	if ($rowF['estid']!=0)
	{
		$rowAa = mssql_fetch_array($resAa);
		echo "                                 	".$rowAa['name']."<input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
	}
	else
	{
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
			$rowAa = mssql_fetch_array($resAa);
			echo "                                 	".$rowAa['name']."<input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
		}
	}

	echo "									</td>\n";
	echo "									<td class=\"gray\" align=\"right\"><b>Sales Rep:</b>\n";

	if ($_SESSION['llev'] == 4) // Sales Manager List
	{
		if ($rowF['estid']==0)
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
						echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\" SELECTED>".ucwords(strtolower($rowB[1]))." ".ucwords(strtolower($rowB[2]))."</option>\n";
					}
					else
					{
						echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\">".ucwords(strtolower($rowB[1]))." ".ucwords(strtolower($rowB[2]))."</option>\n";
					}
				}
			}
			echo "                                 	</select>\n";
		}
		else
		{
			/*echo "                                 	<select name=\"estorig\" DISABLED>\n";
			echo "<input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";*/
			echo "                                 ".$rowI['fname']." ".$rowI['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
		}
	}
	elseif ($_SESSION['llev'] >= 5) // General Manager List
	{
		//echo "                                 	<select name=\"estorig\">\n";
		if ($rowF['estid']==0)
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
					echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\" SELECTED>".ucwords(strtolower($rowB[1]))." ".ucwords(strtolower($rowB[2]))."</option>\n";
				}
				else
				{
					echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\">".ucwords(strtolower($rowB[1]))." ".ucwords(strtolower($rowB[2]))."</option>\n";
				}
			}
	
			echo "                                 	</select>\n";
		}
		else
		{
			/*echo "                                 	<select name=\"estorig\" DISABLED>\n";
			echo "<input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";*/
			echo "                                 ".$rowI['fname']." ".$rowI['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
		}
	}
	else
	{
		echo "                                 ".$rowI['fname']." ".$rowI['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
	}

	echo "									</td>\n";
	echo "									<td class=\"gray\" align=\"right\" width=\"20px\">\n";
	echo "										<img class=\"getHelpNode\" id=\"CformViewDatePanel\" src=\"images/help.png\" title=\"Lead Help\">\n";
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "							</table>\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "					<tr>\n";
	echo "						<td valign=\"top\" align=\"left\">\n";
	echo "							<table class=\"outer\" border=\"0\" width=\"".$lwidth."\" height=\"200\">\n";
	echo "								<tr>\n";
	echo "									<td class=\"gray\" valign=\"top\">\n";
	
	// Customer Table Start
	echo "										<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" valign=\"top\"><b>Customer</b></td>\n";
	echo "												<td class=\"gray\" align=\"right\">\n";
	echo "													<img class=\"getHelpNode\" id=\"CformViewCustomerPanel\" src=\"images/help.png\" title=\"Lead Help: Customer Info\">\n";
	echo "												</td>\n";
	echo "											</tr>\n";
	
	if ($rowF['officeid']==193 or $rowF['officeid']==199)
	{
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Company Name</td>\n";
		echo "												<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"30\" name=\"cpname\" value=\"".trim($rowF['cpname'])."\" ".$dis."></td>\n";
		echo "											</tr>\n";
	}
	
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">First Name</td>\n";
	echo "												<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"30\" name=\"cfname\" value=\"".trim($rowF['cfname'])."\" ".$dis."></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Last Name</td>\n";
	echo "												<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"30\" name=\"clname\" value=\"".trim($rowF['clname'])."\" ".$dis."></td>\n";
	echo "											</tr>\n";
    echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Home Phone</td>\n";
	
	if (isset($rowF['chome']) && strlen($rowF['chome']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"chome\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['chome'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['chome'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['chome'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"chome\" ".$dis."></td>\n";
	}
	
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Work Phone</td>\n";
	
	if (isset($rowF['cwork']) && strlen($rowF['cwork']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" ".$dis."></td>\n";
	}
	
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Cell Phone</td>\n";
	
	if (isset($rowF['ccell']) && strlen($rowF['ccell']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" ".$dis."></td>\n";
	}
	
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Fax</td>\n";
	echo "												<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" value=\"".$rowF['cfax']."\" ".$dis."></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Best Phone</td>\n";
	echo "												<td class=\"gray\" align=\"left\">\n";
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
	else
	{
		echo "													<option value=\"hm\" SELECTED>Home</option>\n";
		echo "													<option value=\"wk\">Work</option>\n";
		echo "													<option value=\"ce\">Cell</option>\n";
	}

	echo "												</select>\n";
	echo "												</td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Email</td>\n";
	echo "												<td class=\"gray\" align=\"left\"><input type=\"text\" name=\"cemail\" size=\"30\" value=\"".$rowF['cemail']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Contact Time</td>\n";
	echo "												<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"30\" name=\"ccontime\" value=\"".$rowF['ccontime']."\"></td>\n";
	echo "											</tr>\n";
	echo "										</table>\n";
	// Customer Table End
	
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "							</table>\n";
	echo "						</td>\n";
	echo "						<td valign=\"top\" align=\"left\">\n";
	echo "							<table class=\"outer\" border=\"0\" width=\"".$rwidth."\" height=\"200\">\n";
	echo "								<tr>\n";
	echo "									<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	
	// Address Table Start
	echo "										<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" valign=\"top\"><b>Current Address</b></td>\n";
	echo "												<td class=\"gray\" align=\"right\">\n";
	echo "													<img class=\"getHelpNode\" id=\"CformViewAddressPanel\" src=\"images/help.png\" title=\"Lead Help: Address Panel\">\n";
	echo "												</td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Street</td>\n";
	echo "												<td class=\"gray\"><input type=\"text\" size=\"39\" name=\"caddr1\" value=\"".$rowF['caddr1']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">City</td>\n";
	echo "												<td class=\"gray\"><input type=\"text\" size=\"20\" name=\"ccity\" value=\"".$rowF['ccity']."\"> State: <input type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" value=\"".$rowF['cstate']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Zip</td>\n";
	echo "												<td class=\"gray\"><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".$rowF['czip1']."\">-<input type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\" value=\"".$rowF['czip2']."\"> ".$cmaplink."</td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Cnty/Twnshp</td>\n";
	echo "												<td class=\"gray\">\n";

	if ($rowC[0]==0)
	{
		echo "												<input type=\"text\" size=\"18\" name=\"ccounty\" value=\"".$rowF['ccounty']."\">\n";
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

	echo "												Map <input type=\"text\" size=\"10\" name=\"cmap\" value=\"".$rowF['cmap']."\">\n";
	echo "												</td>\n";
	echo "											</tr>\n";

	if ($rowF['jobid']=='0' || $_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==FDBK_ADMIN)
	{
		echo "											<tr>\n";

		if ($rowF['ssame']==1)
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
		}
		else
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\"> Same as above</td>\n";
		}

		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Street</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"39\" name=\"saddr1\" value=\"".$rowF['saddr1']."\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">City</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\"> State <input type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Zip</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\">-<input type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\"> ".$smaplink."</td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Cnty/Twnshp</td>\n";
		echo "												<td class=\"gray\">\n";

		if ($rowC[0]==0)
		{
			echo "													<input type=\"text\" size=\"18\" name=\"scounty\" value=\"".$rowF['scounty']."\">\n";
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

		echo "											Map <input type=\"text\" size=\"10\" name=\"smap\" value=\"".htmlspecialchars_decode($rowF['smap'])."\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
	}
	else
	{
		echo "											<tr>\n";

		if ($rowF['ssame']==1)
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Pool Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED DISABLED> Same as above</td>\n";
			echo "<input type=\"hidden\" name=\"ssame\" value=\"1\">\n";
		}
		else
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Pool Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" DISABLED> Same as above</td>\n";
			echo "<input type=\"hidden\" name=\"ssame\" value=\"0\">\n";
		}

		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Street:</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"39\" name=\"saddr1\" value=\"".$rowF['saddr1']."\" DISABLED></td>\n";
		echo "<input type=\"hidden\" name=\"saddr1\" value=\"".$rowF['saddr1']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">City:</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\" DISABLED> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\" DISABLED></td>\n";
		echo "<input type=\"hidden\" name=\"scity\" value=\"".$rowF['scity']."\"><input type=\"hidden\" name=\"sstate\" value=\"".$rowF['sstate']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Zip:</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\" DISABLED>-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\" DISABLED> ".$smaplink."</td>\n";
		echo "<input type=\"hidden\" name=\"szip1\" value=\"".$rowF['szip1']."\"><input type=\"hidden\" name=\"szip2\" value=\"".$rowF['szip2']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Cnty/Twnshp:</td>\n";
		echo "												<td class=\"gray\">\n";

		if ($rowC[0]==0)
		{
			echo "													<input type=\"text\" size=\"18\" name=\"scounty\" value=\"".$rowF['scounty']."\" DISABLED>\n";
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

		echo "											Map: <input type=\"text\" size=\"10\" name=\"smap\" value=\"".$rowF['smap']."\" DISABLED>\n";
		echo "<input type=\"hidden\" name=\"smap\" value=\"".$rowF['smap']."\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
	}
	
	// Address Table End
	
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "							</table>\n";
	echo "						</td>\n";
	echo "					</tr>\n";	
	echo "					<tr>\n";
	echo "						<td align=\"left\" valign=\"top\">\n";
	echo "							<table class=\"outer\" width=\"".$lwidth."\" height=\"220px\">\n";
	echo "								<tr>\n";
	echo "									<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	
	// Appt/Source Table Start
	echo "									<table width=\"100%\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" valign=\"top\"><b>Appointment/Source/Result</b></td>\n";
	echo "											<td class=\"gray\" align=\"right\">\n";
	echo "												<img class=\"getHelpNode\" id=\"CformViewApptPanel\" src=\"images/help.png\" title=\"Lead Help: Contact Panel\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" colspan=\"2\" align=\"center\" valign=\"top\">\n";
	echo "												<table border=0>\n";
	echo "													<tr>\n";
	echo "                        			<td align=\"right\"><b>Lead Contacted</b></td>\n";
	echo "                        			<td align=\"left\" colspan=\"5\">\n";

	if ($rowF['ccontact']==1)
	{
		if (!empty($rowF['ccontactby']) && $rowF['ccontactby']!=0)
		{
			$qryFz = "SELECT securityid,lname,fname,slevel FROM security WHERE securityid='".$rowF['ccontactby']."';";
			$resFz = mssql_query($qryFz);
			$rowFz = mssql_fetch_array($resFz);
			
			$scon	= explode(",",$rowFz['slevel']);
			
			//print_r($scon);
			
			if ($scon[6]==0)
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
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"ccontact\" value=\"1\" title=\"Check this box to indicate the Customer has been contacted.\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "											<td align=\"right\"><b> Appt. Date</b></td>\n";
	echo "														<td valign=\"top\" align=\"left\">\n";
	echo "                                             <select name=\"appt_mo\" id=\"appt_mo\">\n";

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
	echo "														<td align=\"left\" valign=\"top\">/</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_da\" id=\"appt_da\">\n";

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
	echo "														<td align=\"left\" valign=\"top\">/</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_yr\" id=\"appt_yr\">\n";
	echo "																<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr-2; $yr<=$futyr; $yr++)
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
	echo "														<td align=\"right\"><b>Appt. Time</b></td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_hr\" id=\"appt_hr\">\n";

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
	echo "														<td align=\"left\" valign=\"top\">:</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_mn\">\n";

	for ($mn=0; $mn<=59; $mn++)
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
	echo "														<td align=\"left\" valign=\"top\">:</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
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

	if (in_array($rowF['source'],$src_ex))
	{
		if ($rowF['source']==0)
		{
			echo "													<td colspan=\"5\" align=\"left\" valign=\"top\">bluehaven.com</td>\n";
			echo "         											<input type=\"hidden\" name=\"source\" value=\"0\">\n";
		}
		else
		{
			$qryGaa = "SELECT statusid,name FROM leadstatuscodes WHERE statusid=".$rowF['source'].";";
			$resGaa = mssql_query($qryGaa);
			$rowGaa = mssql_fetch_array($resGaa);
			echo "													<td colspan=\"5\" align=\"left\" valign=\"top\">".$rowGaa['name']."</td>\n";
			echo "         											<input type=\"hidden\" name=\"source\" value=\"".$rowGaa['statusid']."\">\n";
		}
	}
	else
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";
		echo "                                             				<select name=\"source\">\n";
		
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

	if ($rowF['jobid']=='0')
	{
		echo "                                             <select name=\"stage\">\n";
	}
	else
	{
		echo "         										<input type=\"hidden\" name=\"stage\" value=\"".$rowF['stage']."\">\n";
		echo "												<select name=\"stage\" DISABLED>\n";
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
	
	if ($_SESSION['emailtemplates'] >= 1 && valid_email_addr(trim($rowF['cemail'])))
	{
		if ($_SESSION['securityid'] == 26)
		{			
			echo "                                 <tr>\n";
			echo "                        				<td align=\"right\"><b>Send Email</b></td>\n";
			echo "                        				<td align=\"left\" colspan=\"5\">\n";
			
			unset($_SESSION['et_uid']);
			$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
			
			echo "											<input type=\"hidden\" name=\"etcid[]\" value=\"".$cid."\">\n";
			echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid ."\">\n";
			echo "											<input type=\"hidden\" name=\"chistory\" value=\"1\">\n";
			echo "											<input type=\"hidden\" name=\"etest\" value=\"0\">\n";
			
			selectemailtemplate_NEW($rowF['officeid'],$rowF['securityid'],$rowF['cid'],1);
			
			echo "                        				</td>\n";
			echo "                        			</tr>\n";
		}
		else
		{
			echo "                                 <tr>\n";
			echo "                        				<td align=\"right\"><b>Send Email</b></td>\n";
			echo "                        				<td align=\"left\" colspan=\"5\">\n";
			
			unset($_SESSION['et_uid']);
			$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
			
			echo "											<input type=\"hidden\" name=\"etcid[]\" value=\"".$cid."\">\n";
			echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid ."\">\n";
			echo "											<input type=\"hidden\" name=\"chistory\" value=\"1\">\n";
			echo "											<input type=\"hidden\" name=\"etest\" value=\"0\">\n";
			
			selectemailtemplate($rowF['officeid'],$rowF['securityid'],$rowF['cid'],1);
			
			echo "                        				</td>\n";
			echo "                        			</tr>\n";
		}
	}

	// Call Back Selects
	echo "                                 <tr>\n";
	echo "                        			<td align=\"right\"><b>Call Back</b></td>\n";
	echo "                        			<td align=\"left\" colspan=\"5\">\n";

	if ($rowF['hold']==1)
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" id=\"hold\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" id=\"hold\" value=\"1\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "                        			<td align=\"right\"><b>on</b></td>\n";
	echo "									<td valign=\"top\" align=\"left\">\n";
	
	echo "                                             <select name=\"hold_mo\" id=\"hold_mo\">\n";

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
	echo "									</td>\n";
	echo "									<td align=\"left\" valign=\"top\">/</td>\n";
	echo "									<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_da\" id=\"hold_da\">\n";

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
	echo "														<td align=\"left\" valign=\"top\">/</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_yr\" id=\"hold_yr\">\n";
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
		echo "                        						<td align=\"left\" colspan=\"5\">\n";
		echo "                                    			<select name=\"finansrc\" ".$disfr." title=\"Set the Finance Source\">\n";
		
		if (!isset($rowF['finan_src']) || $rowF['finan_src']==0)
		{
			echo "                                    	<option value=\"0\">Select...</option>\n";
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==1)
		{
			echo "                                    	<option value=\"1\" SELECTED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==2)
		{
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\" selected>Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==3)
		{
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\" selected>Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==4)
		{
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\" SELECTED>BH Finance</option>\n";
		}
		
		echo "                                    			</select>\n";
		
		if (isset($rowF['finan_src']) && $rowF['finan_src'] > 0)
		{
			echo "												<input type=\"hidden\" name=\"finansrc\" value=\"".$rowF['finan_src']."\">\n";
		}
		
		echo "                        						</td>\n";
		echo "                        					</tr>\n";	
	}
	
	echo "											</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	
	// Appt/Source Table End
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "							</table>\n";
	echo "						</td>\n";
	echo "						<td align=\"left\" valign=\"top\" rowspan=\"2\">\n";
	echo "							<table class=\"outer\" width=\"".$rwidth."\" height=\"525px\">\n";
	echo "								<tr>\n";
	echo "									<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "										<table align=\"left\" width=\"100%\">\n";
		echo "											<tr>\n";
		echo "												<td align=\"left\"><b>Comments/Directions</b></td>\n";
		echo "												<td align=\"right\">\n";
		echo "													<div class=\"noPrint\">\n";
		echo "														<img class=\"setpointer\" id=\"refreshLeadComments\" src=\"images/arrow_refresh_small.png\" title=\"Refresh Comment List\">\n";
		echo "													</div>\n";
		echo "												</td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td align=\"left\">\n";
		echo "													<div class=\"noPrint\">\n";
		echo "														<textarea name=\"addcomment\" id=\"addcomment\" cols=\"75\" rows=\"2\"></textarea>\n";
		echo "													</div>\n";
		echo "												</td>\n";
		echo "												<td align=\"right\">\n";
		echo "													<div class=\"noPrint\">\n";
		echo "														<img class=\"setpointer\" id=\"saveLeadComment\" src=\"images/save.gif\" title=\"Save New Comment\">\n";
		echo "													</div>\n";
		echo "												</td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td valign=\"top\" align=\"left\" colspan=\"3\">\n";
		echo "													<table align=\"left\" width=\"100%\">\n";
		echo "														<tr>\n";
		echo "				              								<td align=\"left\" valign=\"top\">\n";
		echo "																<div id=\"LeadCommentList\"></div>\n";
		echo "															</td>\n";
		echo "														</tr>\n";
		echo "													</table>\n";
		echo "												</td>\n";
		echo "											</tr>\n";
		echo "										</table>\n";
	}
	else
	{
		// Comment Table Start
		echo "										<table align=\"left\" width=\"100%\">\n";
		echo "											<tr>\n";
		echo "												<td height=\"20px\" class=\"gray\" valign=\"top\" align=\"left\"><b>Comments/Directions</b></td>\n";
		echo "												<td height=\"20px\" width=\"20px\" class=\"gray\" valign=\"top\" align=\"right\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" valign=\"top\" align=\"left\" colspan=\"2\">\n";
		echo "													<table align=\"left\" width=\"100%\">\n";
		echo "														<tr>\n";
		echo "				              								<td class=\"gray\" align=\"left\" colspan=\"5\" valign=\"top\">\n";
		echo "																<div class=\"noPrint\">\n";
		echo "																	<textarea name=\"addcomment\" id=\"addcomment\" cols=\"75\" rows=\"2\"></textarea>\n";
		echo "																</div>\n";
		echo "															</td>\n";
		echo "														</tr>\n";
		
		if ($nrowL > 0)
		{
			echo "   												<tr>\n";
			echo "      												<td align=\"left\" class=\"gray\" width=\"90px\"><b>Date</b></td>\n";
			echo "      												<td align=\"left\" class=\"gray\" width=\"30px\"><b>Name</b></td>\n";
			echo "      												<td align=\"center\" class=\"gray\" width=\"30px\"><b>Stage</b></td>\n";
			echo "      												<td align=\"center\" class=\"gray\" width=\"30px\"><b>Ticket</b></td>\n";
			echo "      												<td align=\"left\" class=\"gray\"><b>Comments</b></td>\n";
			echo "   												</tr>\n";
		
			$cmntcnt=0;
			while ($rowL = mssql_fetch_array($resL))
			{
				$cmntcnt++;				
				$stage='';
				
				if ($rowL['act']=="leads")
				{
					$stage="<div title=\"Lead\">L</div>";
				}
				elseif ($rowL['act']=="est")
				{
					$stage="<div title=\"Estimate\">E</div>";
				}
				elseif ($rowL['act']=="contract")
				{
					$stage="<div title=\"Contract\">C</div>";
				}
				elseif ($rowL['act']=="jobs")
				{
					$stage="<div title=\"Job\">J</div>";
				}
				elseif ($rowL['act']=="mas")
				{
					$stage="<div title=\"MAS\">M</div>";
				}
				elseif ($rowL['act']=="reports")
				{
					$stage="<div title=\"Reports\">R</div>";
				}
				elseif ($rowL['act']=="fin")
				{
					$stage="<div title=\"Finance\">F</div>";
				}
				elseif ($rowL['act']=="Complaint")
				{
					$stage="<div title=\"Complaint\">CP</div>";
				}
				elseif ($rowL['act']=="Followup")
				{
					$stage="<div title=\"Followup\">FL</div>";
				}
				elseif ($rowL['act']=="Resolved")
				{
					$stage="<div title=\"Resolved\">RS</div>";
				}
				elseif ($rowL['act']=="cresp")
				{
					$stage="<div title=\"Email Response\">ER</div>";
				}
				
				if ($rowL['act']=="Complaint")
				{
					$stage="<div title=\"Complaint\">CP</div>";
					$cmt_tbg="ltred";
				}
				elseif ($rowL['act']=="Followup")
				{
					$stage="<div title=\"Followup\">FL</div>";
					$cmt_tbg="ltred";
				}
				elseif ($rowL['act']=="Resolved")
				{
					$stage="<div title=\"Resolved\">RS</div>";
					$cmt_tbg="ltgrn";
				}
				else
				{
					if ($cmntcnt%2)
					{
						$cmt_tbg="white";
					}
					else
					{
						$cmt_tbg="ltblue";
					}
				}
		
				echo "   												<tr>\n";
				echo "   													<td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\" NOWRAP><table width=\"100%\"><tr><td align=\"left\">".date('m/d/y',strtotime($rowL['mdate']))."</td><td align=\"right\">".date('g:ia',strtotime($rowL['mdate']))."</td></tr></table></td>\n";
				echo "   													<td align=\"center\" valign=\"top\" class=\"".$cmt_tbg."\" title=\"".trim($rowL['sfname'])." ".trim($rowL['slname'])."\" NOWRAP>".substr($rowL['sfname'],0,2)." ".substr($rowL['slname'],0,6)."</td>\n";
				echo "   													<td align=\"center\" valign=\"top\" class=\"".$cmt_tbg."\" NOWRAP>".$stage."</td>\n";
				echo "   													<td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\">\n";
			
				if ($rowL['complaint']==1 && $rowL['followup']==0 && $rowL['resolved']==0)
				{
					echo $rowL['id'];
				}
				elseif ($rowL['complaint']==1 && $rowL['followup']==1 && $rowL['resolved']==0)
				{
					echo $rowL['relatedcomplaint'];
				}
				elseif ($rowL['complaint']==1 && $rowL['followup']==1 && $rowL['resolved']==1)
				{
					echo $rowL['relatedcomplaint'];
				}
		
				echo "   													</td>\n";
				echo "   													<td align=\"left\" class=\"".$cmt_tbg."\">\n";
		
				//$detect_ar=array('/=2C/','/=20/','/=A0/','/=0A/','/=OA/','/= /','/=/');
				$detect_ar=array(
								  '/=C2=A0/',
								  '/=C2=B7/',
								  '/=C2/',
								  '/C2=/',
								  '/=A0/',
								  '/=0A/',
								  '/A0/',
								  '/0A/',
								  '/=20/',
								  '/= /',
								  '/ =/',
								  '/=/',
								  '/------Original Message------/',
								  '/----- Original message -----/');
				
				$replace_ar=array('','','','','','','','','',' ',' ',' ',' ','');
				
				if ($rowL['act']=='cresp')
				{
					if ($rowL['custid']==274405 or is_base64_encoded($rowL['mtext']))
					{
						echo htmlspecialchars_decode(preg_replace($detect_ar,$resplace_ar,base64_decode($rowL['mtext'])));
					}
					else
					{
						echo substr(htmlspecialchars_decode(preg_replace($detect_ar,$resplace_ar,$rowL['mtext'])),0,512);
					}
				}
				else
				{
					echo htmlspecialchars_decode(preg_replace($detect_ar,$resplace_ar,$rowL['mtext']));
				}
		
				echo "   													</td>\n";
				echo "   												</tr>\n";
			}
		}
		
		echo "   												</table>\n";
		echo "												</td>\n";
		echo "											</tr>\n";
		echo "										</table>\n";
		// Comment Table End
	}
	
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "							</table>\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "					<tr>\n";
	echo "						<td align=\"left\" valign=\"top\">\n";
	echo "							<table class=\"outer\" width=\"".$lwidth."\" height=\"303px\">\n";
	echo "								<tr>\n";
	echo "									<td class=\"gray\" valign=\"top\" align=\"left\"><b>Marketing Data</b></td>\n";
	echo "									<td class=\"gray\" valign=\"top\" align=\"right\">\n";
	echo "										<div class=\"noPrint\">\n";
	echo "											<button class=\"buttondkgrypnl70\" id=\"showMarketingData\">Show</button>\n";
	echo "										</div>\n";
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "                           	<tr>\n";
	echo "									<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
	
	if (isset($rowF['mrktproc']) and strlen($rowF['mrktproc']) > 2)
	{
		// Marketing Table Start
		echo "										<table width=\"100%\" id=\"MarketingDataTable\">\n";
		echo "											<tr>\n";
		echo "												<td colspan=\"2\" class=\"gray\" valign=\"top\" align=\"left\">\n";
		echo "													<pre>".wordwrap(preg_replace('/-----------------/','---',$rowF['mrktproc'],45))."</pre>\n";
		//echo "												<pre>".wordwrap($rowF['mrktproc'],45)."</pre>\n";	
		echo "												</td>\n";
		echo "											</tr>\n";
		echo "										</table>\n";
		// Marketing Table End
	}

	echo "									</td>\n";
	echo "                          	</tr>\n";
	echo "                           	<tr>\n";
	echo "									<td class=\"gray\" valign=\"top\">\n";
	
	//Priv Policy Start
	echo "									<table width=\"100%\">\n";
	echo "                           			<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" valign=\"top\"><b>Privacy</b></td>\n";
	echo "											<td class=\"gray\" valign=\"top\" align=\"right\">\n";
	echo "												<img class=\"getHelpNode\" id=\"CformViewPrivacyPanel\" src=\"images/help.png\" title=\"Lead Help: Marketing\">\n";
	echo "											</td>\n";
	echo "                           			</tr>\n";
	echo "                           			<tr>\n";
	echo "											<td class=\"gray\" width=\"35px\" valign=\"top\" align=\"right\">\n";
	
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
	echo "                           			</tr>\n";
	echo "									</table>\n";
	//Priv Policy End

	echo "									</td>\n";
	echo "                          	</tr>\n";
	echo "							</table>\n";
	echo "						</td>\n";
	echo "						<td align=\"left\" valign=\"top\">\n";
	echo "						</td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "					<input type=\"hidden\" name=\"comments\" value=\"".$rowF['comments']."\">\n";
	echo "					</form>\n";
	echo "			</td>\n";
	echo "		</tr>\n";

	if (!empty($_REQUEST['subq']) && $_REQUEST['subq']=="history")
	{
		echo "		<tr>\n";
		echo "			<td>\n";
		echo "				<table class=\"outer\" width=\"100%\" height=\"200\">\n";
		echo "					<tr>\n";
		echo "						<td height=\"20px\" class=\"gray\" valign=\"top\"><b>Lead Update History</b></td>\n";
		echo "						<td class=\"gray\" valign=\"top\" align=\"right\">\n";
		echo "							<img class=\"getHelpNode\" id=\"CformViewLeadHistoryPanel\" src=\"images/help.png\" title=\"Lead Help: History\">\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<td colspan=\"2\" class=\"gray\" valign=\"top\" align=\"center\">\n";
		echo "							<iframe src=\"subs/lhistory.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"left\"></iframe>\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
	}
	
	echo "	</table>\n";
	echo "</td>\n";
	echo "<td align=\"left\" valign=\"top\">\n";
	echo "		<div class=\"noPrint\">\n";
	echo "		<table border=0>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	echo "					<input class=\"buttondkgrypnl70\" id=\"submitleadupdate\" type=\"submit\" value=\"Update\">\n";
	echo "				</td>\n";	
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_REQUEST['uid']!="XXX")
	{
		echo "      		<form method=\"post\">\n";
		echo "         			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "         			<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
		echo "         			<input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
		echo "         			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
		echo "         			<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
		echo "         			<input type=\"hidden\" name=\"custid\" value=\"".$rowF['custid']."\">\n";
		echo "					<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"OneSheet\"><br>\n";
		echo "				</form>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['llev'] >= 4 && $_REQUEST['uid']!="XXX")
	{
		echo "      		<form method=\"post\">\n";
		echo "         			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "         			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
		echo "         			<input type=\"hidden\" name=\"subq\" value=\"history\">\n";
		echo "         			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
		echo "         			<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
		echo "					<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"History\"><br>\n";
		echo "				</form>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";

	if ($_SESSION['elev'] >= 1 && $rowC[1]==1)
	{
		if ($rowF['dupe']==0 && $rowF['jobid']=='0')
		{
			echo "			<tr>\n";
			echo "				<td valign=\"top\">\n";
			echo "      			<form method=\"post\">\n";
			echo "         				<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "         				<input type=\"hidden\" name=\"call\" value=\"new\">\n";
			echo "         				<input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
			echo "         				<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
			echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Quote\"><br>\n";
			echo "					</form>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
		}
		
		if ($rowF['dupe']==0 && $rowF['jobid']=='0')
		{
			echo "			<tr>\n";
			echo "				<td valign=\"top\">\n";
			echo "      			<form method=\"post\">\n";
			echo "         				<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "         				<input type=\"hidden\" name=\"call\" value=\"matrix0\">\n";
			echo "         				<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
			echo "         				<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
			echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Estimate\"><br>\n";
			echo "					</form>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
		}
	}
	
	if ($_SESSION['securityid'] == 26 || $_SESSION['securityid'] == 332)
	{
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "					<form method=\"POST\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"sales\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"new_cart\">\n";
		echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
		echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"New Cart\"><br>\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}
	
	if (isset($rowM['filestoreaccess']) && $rowM['filestoreaccess'] >= 99)
	{
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "					<form method=\"POST\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
		echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
		echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Files\"><br>\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}

	if (isset($_SESSION['tqry']))
	{
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "         			<form name=\"tsearch1\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
		echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Search Results\" title=\"Click here to Return to the Last Search Results\">\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}

	echo "		<tr>\n";
	echo "		<td valign=\"top\" align=\"center\">\n";
	echo "			<img class=\"getHelpNode\" id=\"CformViewLeadButtonsPanel\" src=\"images/help.png\" title=\"Lead Help: Context Menu\">\n";
	echo "		</td>\n";
	echo "		</table>\n";
	echo "		</div>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	
	$qryXX	= "UPDATE jest..cinfo SET viewed=getdate(), viewedby=".$_SESSION['securityid']." WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resXX	= mssql_query($qryXX);
	
	/*$qryZ	= "INSERT INTO jest_stats..cinfo_views (oid,sid,cid,vdate) VALUES (".$_SESSION['officeid'].",".$_SESSION['securityid'].",".$cid.",getdate());";
	$resZ	= mssql_query($qryZ);*/
}

function cform_add()
{
	error_reporting(E_ALL);
	//include ('./email_notify.php');

	if (
				empty($_REQUEST['cfname'])
			 || empty($_REQUEST['clname'])
			 || empty($_REQUEST['chome'])
			 || empty($_REQUEST['caddr1'])
			 || empty($_REQUEST['ccity'])
			 || empty($_REQUEST['czip1'])
			 || empty($_REQUEST['cemail'])
			 || !is_numeric($_REQUEST['czip1'])
			 || strlen($_REQUEST['czip1']) != 5
			 || $_REQUEST['source']==1
			 || preg_match("/,/",$_REQUEST['clname'])
			 //|| preg_match("/'/",$_REQUEST['clname'])
			 || preg_match("/,/",$_REQUEST['cfname'])
			 //|| preg_match("/'/",$_REQUEST['cfname'])
			 || preg_match("/,/",$_REQUEST['caddr1'])
			 //|| preg_match("/'/",$_REQUEST['caddr1'])
			 || empty($_REQUEST['chome'])
			 || strlen($_REQUEST['chome']) != 10
			 || preg_match("/-/",$_REQUEST['chome'])
		)
	{

		echo "<table>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><font color=\"red\"><b>ERROR!</b></font></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td></td><td><b>Required Information is Missing or is Improperly Formatted, click the BACK button and correct:</b></td>";
		echo "	</tr>\n";

		if (empty($_REQUEST['cfname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- First Name</b></td>";
			echo "	</tr>\n";
		}

		/*if (preg_match("/,/",$_REQUEST['cfname']) || preg_match("/'/",$_REQUEST['cfname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- First Name cannot contain Commas or Apostrophes</b></td>";
			echo "	</tr>\n";
		}*/

		if (empty($_REQUEST['clname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Last Name</b></td>";
			echo "	</tr>\n";
		}

		/*if (preg_match("/,/",$_REQUEST['clname']) || preg_match("/'/",$_REQUEST['clname']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Last Name cannot contain Commas or Apostrophes</b></td>";
			echo "	</tr>\n";
		}*/

		if (empty($_REQUEST['chome']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Home Phone</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_REQUEST['caddr1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Address</b></td>";
			echo "	</tr>\n";
		}

		/*if (preg_match("/,/",$_REQUEST['caddr1']) || preg_match("/'/",$_REQUEST['caddr1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Address cannot contain Commas or Apostrophes</b></td>";
			echo "	</tr>\n";
		}*/

		if (empty($_REQUEST['ccity']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- City</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_REQUEST['czip1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Zip Code Blank</b></td>";
			echo "	</tr>\n";
		}

		if (!is_numeric($_REQUEST['czip1']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Zip Code not Numeric</b></td>";
			echo "	</tr>\n";
		}

		if (strlen($_REQUEST['czip1']) != 5)
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Zip Code Length not valid</b></td>";
			echo "	</tr>\n";
		}

		if (empty($_REQUEST['cemail']) || preg_match("/,/",$_REQUEST['cemail']) || preg_match("/'/",$_REQUEST['cemail']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- E-Mail Address issing or Illegal character</b></td>";
			echo "	</tr>\n";
		}

		if ($_REQUEST['source']==1)
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Lead Source</b> error</td>";
			echo "	</tr>\n";
		}
		
		if (empty($_REQUEST['chome']) || strlen($_REQUEST['chome']) != 10 || preg_match("/-/",$_REQUEST['chome']))
		{
			echo "	<tr>\n";
			echo "		<td></td><td><b>- Home Phone</b> is not filled in or properly formatted. Full 10 digit phone number required and should not contain dashes or dots (eg: 4095551212)</td>";
			echo "	</tr>\n";
		}

		echo "</table>\n";
		exit;
	}

	$qryA  = "SELECT c.cid,c.officeid,c.clname,c.cfname,c.caddr1,c.czip1,c.chome,c.cwork,c.ccell,c.securityid as sid,c.sidm ";
	$qryA .= ",(select name from jest..offices where officeid=c.officeid) as oname ";
	$qryA .= ",(select lname from jest..security where securityid=c.securityid) as slname ";
	$qryA .= ",(select fname from jest..security where securityid=c.securityid) as sfname ";
	$qryA .= "FROM cinfo as c WHERE officeid='".$_SESSION['officeid']."' AND c.clname='".$_REQUEST['clname']."' AND c.caddr1='".$_REQUEST['caddr1']."' AND c.czip1='".$_REQUEST['czip1']."' ";	
	$qryA .= "ORDER BY c.clname;";
	$resA  = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);

	//echo $qryA."<br>";
	
	if ($nrowA > 25)
	{
		echo "         <table class=\"outer\" width=\"75%\">\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" align=\"center\">\n";
		echo "					<font color=\"red\"><b>Lead Entry Error!</b></font> The Customer information entered already exists in the JMS and returned ".$nrowA." leads with the entered information.";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "			</table>\n";
		exit;
	}
	elseif ($nrowA > 0)
	{
		echo "         <table class=\"outer\" width=\"75%\">\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" align=\"center\">\n";
		echo "					<font color=\"red\"><b>Lead Entry Error!</b></font> The Customer information entered already exists in the JMS as listed below.";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                  	<tr>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\"><b>Office</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\"><b>Last Name</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\">First Name</td>\n";
		echo "                     		<td class=\"ltgray_und\" align=\"center\"><b>Home Ph</b></td>\n";
		echo "                     		<td class=\"ltgray_und\" align=\"center\"><b>Customer Addr</b></td>\n";
		echo "                     		<td class=\"ltgray_und\" align=\"center\"><b>Customer Zip</b></td>\n";
		echo "							<td class=\"ltgray_und\" align=\"center\"><b>SalesRep</b></td>\n";
		echo "            	         	<td class=\"ltgray_und\" align=\"right\">Records Found: ".$nrowA."</td>\n";
		echo "                  	</tr>\n";
		
		$cnA=0;
		while ($rowA = mssql_fetch_array($resA))
		{
			$cnA++;
			$uid  =md5(session_id().time().$rowA['cid']).".".$_SESSION['securityid'];
			echo "                  	<tr>\n";
			echo "							<td class=\"wh_und\" align=\"center\">". 	$cnA ."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\">". 		$rowA['oname'] ."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\">". 		$rowA['clname'] ."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\">". 		$rowA['cfname'] ."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\">". 	$rowA['chome'] ."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\">". 		$rowA['caddr1'] ."</td>\n";
			echo "							<td class=\"wh_und\" align=\"center\">". 	$rowA['czip1'] ."</td>\n";
			echo "							<td class=\"wh_und\" align=\"left\">". 		$rowA['slname'] .", ". $rowA['sfname'] ."</td>\n";
			
			if ($_SESSION['llev'] >= 5 || $rowA['sid']==$_SESSION['securityid'] || $rowA['sidm']==$_SESSION['securityid'])
			{
				echo "                        <form method=\"POST\">\n";
				echo "            	         	<td class=\"wh_und\" align=\"right\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view\">\n";
				echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$rowA['cid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
				echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Lead\">\n";
				echo "							</td>\n";
				echo "                        </form>\n";
			}
			else
			{
				echo "            	         	<td class=\"wh_und\" align=\"center\">\n";
				echo "							</td>\n";
			}
			
			echo "                  	</tr>\n";
		}
		
		echo "</table>\n";
		exit;
	}
	
	if (!empty($_REQUEST['opt1']) && $_REQUEST['opt1']==1)
	{
		$opt1=1;
	}
	else
	{
		$opt1=0;
	}

	if (!empty($_REQUEST['opt2']) && $_REQUEST['opt2']==1)
	{
		$opt2=1;
	}
	else
	{
		$opt2=0;
	}
	
	if (!empty($_REQUEST['opt3']) && $_REQUEST['opt3']==1)
	{
		$opt3=1;
	}
	else
	{
		$opt3=0;
	}

	if (!empty($_REQUEST['opt4']) && $_REQUEST['opt4']==1)
	{
		$opt4=1;
	}
	else
	{
		$opt4=0;
	}
	
	/*
	if (isset($_REQUEST['setappt']) and valid_date($_REQUEST['setappt']) and strtotime($_REQUEST['setappt']) >= strtotime('1/1/2000'))
	{
		$setappt=1;
		$appt_mo=date('n',strtotime($_REQUEST['setappt']));
		$appt_da=date('d',strtotime($_REQUEST['setappt']));
		$appt_yr=date('Y',strtotime($_REQUEST['setappt']));
		$appt_hr=date('G',strtotime($_REQUEST['setappt']));
		$appt_mn=date('i',strtotime($_REQUEST['setappt']));
		$appt_pa=date('A',strtotime($_REQUEST['setappt']));
		$apptmnt=date('m/d/Y G:iA',strtotime($_REQUEST['setappt']));
	}
	else
	{
		$setappt=0;
		$appt_mo=$_REQUEST['appt_mo'];
		$appt_da=$_REQUEST['appt_da'];
		$appt_yr=$_REQUEST['appt_yr'];
		$appt_hr=$_REQUEST['appt_hr'];
		$appt_mn=$_REQUEST['appt_mn'];
		$appt_pa=$_REQUEST['appt_pa'];
		$apptmnt=old_date_disp($_REQUEST['appt_mo'],$_REQUEST['appt_da'],$_REQUEST['appt_yr'],$_REQUEST['appt_hr'],$_REQUEST['appt_mn'],$_REQUEST['appt_pa']);
	}
	*/
		
		$qryC   = "exec sp_insert_cinfo ";
		$qryC  .= "@securityid='".$_REQUEST['estorig']."', ";
		$qryC  .= "@officeid='".$_SESSION['officeid']."', ";
		$qryC  .= "@srcoffice='".$_SESSION['officeid']."', ";
		$qryC  .= "@recdate='".$_REQUEST['recdate']."', ";
		$qryC  .= "@cfname='".htmlspecialchars((ucwords(trim($_REQUEST['cfname']))),ENT_QUOTES)."', ";
		$qryC  .= "@clname='".htmlspecialchars(ucwords(trim($_REQUEST['clname'])),ENT_QUOTES)."', ";
		$qryC  .= "@caddr1='".htmlspecialchars(trim($_REQUEST['caddr1']),ENT_QUOTES)."', ";
		$qryC  .= "@ccity='".htmlspecialchars($_REQUEST['ccity'],ENT_QUOTES)."', ";
		$qryC  .= "@cstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
		$qryC  .= "@czip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
		$qryC  .= "@czip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
		$qryC  .= "@ccounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
		$qryC  .= "@cmap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";

		if (empty($_REQUEST['ssame']))
		{
			$qryC  .= "@ssame='0', ";
			$qryC  .= "@saddr1='".htmlspecialchars($_REQUEST['saddr1'],ENT_QUOTES)."', ";
			$qryC  .= "@scity='".htmlspecialchars($_REQUEST['scity'],ENT_QUOTES)."', ";
			$qryC  .= "@sstate='".htmlspecialchars($_REQUEST['sstate'],ENT_QUOTES)."', ";
			$qryC  .= "@szip1='".htmlspecialchars($_REQUEST['szip1'],ENT_QUOTES)."', ";
			$qryC  .= "@szip2='".htmlspecialchars($_REQUEST['szip2'],ENT_QUOTES)."', ";
			$qryC  .= "@scounty='".htmlspecialchars($_REQUEST['scounty'],ENT_QUOTES)."', ";
			$qryC  .= "@smap='".htmlspecialchars($_REQUEST['smap'],ENT_QUOTES)."', ";
		}
		else
		{
			$qryC  .= "@ssame='".$_REQUEST['ssame']."', ";
			$qryC  .= "@saddr1='".htmlspecialchars($_REQUEST['caddr1'],ENT_QUOTES)."', ";
			$qryC  .= "@scity='".htmlspecialchars($_REQUEST['ccity'],ENT_QUOTES)."', ";
			$qryC  .= "@sstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
			$qryC  .= "@szip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
			$qryC  .= "@szip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
			$qryC  .= "@scounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
			$qryC  .= "@smap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
		}

		$qryC  .= "@chome='".htmlspecialchars($_REQUEST['chome'],ENT_QUOTES)."', ";
		$qryC  .= "@cwork='".htmlspecialchars($_REQUEST['cwork'],ENT_QUOTES)."', ";
		$qryC  .= "@ccell='".htmlspecialchars($_REQUEST['ccell'],ENT_QUOTES)."', ";
		$qryC  .= "@cfax='".htmlspecialchars($_REQUEST['cfax'],ENT_QUOTES)."', ";
		$qryC  .= "@source='".$_REQUEST['source']."', ";
		$qryC  .= "@cemail='".replacequote($_REQUEST['cemail'])."', ";
		$qryC  .= "@cconph='".$_REQUEST['cconph']."', ";
		$qryC  .= "@ccontime='".htmlspecialchars($_REQUEST['ccontime'],ENT_QUOTES)."', ";
		$qryC  .= "@appt_mo='".$_REQUEST['appt_mo']."', ";
		$qryC  .= "@appt_da='".$_REQUEST['appt_da']."', ";
		$qryC  .= "@appt_yr='".$_REQUEST['appt_yr']."', ";
		$qryC  .= "@appt_hr='".$_REQUEST['appt_hr']."', ";
		$qryC  .= "@appt_mn='".$_REQUEST['appt_mn']."', ";
		$qryC  .= "@appt_pa='".$_REQUEST['appt_pa']."', ";
		$qryC  .= "@opt1='".$opt1."', ";
		$qryC  .= "@opt2='".$opt2."', ";
		$qryC  .= "@opt3='".$opt3."', ";
		$qryC  .= "@opt4='".$opt4."', ";
		$qryC  .= "@comments=''; ";

		$resC   = mssql_query($qryC);
		$rowC   = mssql_fetch_row($resC);
		
		if (isset($rowC[0]) && $rowC[0] != 0)
		{
			include ('./email_notify.php');
			
            if (($_SESSION['officeid']==193 or $_SESSION['officeid']==199) and (isset($_REQUEST['cpname']) and !empty($_REQUEST['cpname'])))
            {
                $qryCa  = "UPDATE jest..cinfo SET cpname='".htmlspecialchars($_REQUEST['cpname'],ENT_QUOTES)."' WHERE cid=".$rowC[0].";";
                $resCa  = mssql_query($qryCa);
            }
            
			//if (!empty($_REQUEST['comments']) && strlen($_REQUEST['comments']) >= 2 && $rowA['ccnt'] == 0)
			if (!empty($_REQUEST['comments']) && strlen($_REQUEST['comments']) >= 2)
			{
				$qryB   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
				$qryB  .= "VALUES ";
				$qryB  .= "('".$rowC[0]."','".$_SESSION['officeid']."','".$_SESSION['securityid']."','leads','".htmlspecialchars($_REQUEST['comments'],ENT_QUOTES)."','".$_REQUEST['uid']."')";
				$resB  = mssql_query($qryB);
			}
			
			if (
				(isset($_REQUEST['appt_yr']) and $_REQUEST['appt_yr']!='0000') and
				(isset($_REQUEST['appt_mo']) and $_REQUEST['appt_mo']!=0) and
				(isset($_REQUEST['appt_da']) and $_REQUEST['appt_da']!=0)
				)
			{
				$setappt=old_date_disp($_REQUEST['appt_mo'],$_REQUEST['appt_da'],$_REQUEST['appt_yr'],$_REQUEST['appt_hr'],$_REQUEST['appt_mn'],$_REQUEST['appt_pa']);
				$qryCc  = "UPDATE jest..cinfo SET apptmnt='".$setappt."' WHERE cid=".$rowC[0].";";
                $resCc  = mssql_query($qryCc);
			}
			
			process_email_intro($rowC[0]);			
			cform_view($rowC[0]);
			//echo $qryB."<BR>";
		}
		else
		{
			echo "<b><font color=\"red\">Error!</font> <br><br>The Lead information you attempted to submit did not save. Click back, check your entry and resubmit</b>";
			exit;
		}
	//}
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
		$ap="am";
	}
	else
	{
		$ap="pm";
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
	return trim($dtxt);
}

function cform_edit_new()
{

	//show_post_vars();
	/*
	if (!valid_date($_REQUEST['appt_dt']))
	{
		echo $_REQUEST['appt_dt']." is Not Valid<br>";
	}

	if (!valid_date($_REQUEST['callb_dt']))
	{
		echo $_REQUEST['callb_dt']." is Not Valid<br>";
	}
	*/
	
	old_date_store($_REQUEST['appt_dt']);
	
	echo "<br>";
	
	old_date_disp(04,15,2005,1,25,0);
}

function cform_edit()
{	
	if ($_SESSION['securityid']==26)
	{
	    ini_set('display_errors','On');
		error_reporting(E_ALL);
	}
	
	$acclist=explode(",",$_SESSION['aid']);
	$ex_status_codes=array();

	$qry = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid=".$_REQUEST['cid'].";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1 = "SELECT am,finan_from,leadmail FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	$qry2 = "SELECT am,leadmail FROM offices WHERE officeid='89';"; //BHNM:Active
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	$qry3 = "SELECT am,name,leadmail FROM offices WHERE officeid='".$_REQUEST['site']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);

	$qry4 = "SELECT securityid,sidm FROM security WHERE securityid='".$row['securityid']."';";
	$res4 = mssql_query($qry4);
	$row4 = mssql_fetch_array($res4);
	
	$qry5 = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$res5 = mssql_query($qry5);
	
	while ($row5 = mssql_fetch_array($res5))
	{
		$ex_status_codes[]=$row5['statusid'];
	}
	
	$qry6 = "SELECT securityid,sidm,returntolist FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res6 = mssql_query($qry6);
	$row6 = mssql_fetch_array($res6);

	if ($_SESSION['llev'] < 9 && !in_array($row['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to Update this Lead</b>";
		exit;
	}
	else
	{
		if (isset($_REQUEST['hold']) && $_REQUEST['hold']==1)
		{
			$hold=1;
		}
		else
		{
			$hold=0;
		}
		
		if (!empty($_REQUEST['opt1']) && $_REQUEST['opt1']==1)
		{
			$opt1=1;
		}
		else
		{
			$opt1=0;
		}

		if (!empty($_REQUEST['opt2']) && $_REQUEST['opt2']==1)
		{
			$opt2=1;
		}
		else
		{
			$opt2=0;
		}
		
		if (!empty($_REQUEST['opt3']) && $_REQUEST['opt3']==1)
		{
			$opt3=1;
		}
		else
		{
			$opt3=0;
		}

		if (!empty($_REQUEST['opt4']) && $_REQUEST['opt4']==1)
		{
			$opt4=1;
		}
		else
		{
			$opt4=0;
		}
		
		$qryA  = "UPDATE cinfo SET ";

		if ($_SESSION['llev'] >= 5)
		{
			$qry4 = "SELECT custid FROM cinfo WHERE officeid='".$_REQUEST['site']."' AND custid='".$row['custid']."';";
			$res4 = mssql_query($qry4);
			$row4 = mssql_fetch_array($res4);
			$nrow4= mssql_num_rows($res4);

			if ($nrow4 > 0 && $_SESSION['officeid']!=$_REQUEST['site'])
			{
				$qry5 = "SELECT MAX(custid) as mcustid FROM cinfo WHERE officeid='".$_REQUEST['site']."';";
				$res5 = mssql_query($qry5);
				$row5 = mssql_fetch_array($res5);

				$ncustid=$row5['mcustid']+1;
				$qryA  .= "custid='".$ncustid."', ";
			}

			if ($_SESSION['officeid']!=$_REQUEST['site'])
			{
				$qryA  .= "securityid='".$row3['am']."', ";
				$qryA  .= "officeid='".$_REQUEST['site']."', ";
				//$qryA  .= "officeid='89', ";
				$udate_id=$row3['am'];
			}
			else
			{
				$qryA  .= "securityid='".$_REQUEST['estorig']."', ";
				$qryA  .= "officeid='".$_REQUEST['site']."', ";
				$udate_id=$_REQUEST['estorig'];
			}
		}
		else
		{
			$qryA  .= "securityid='".$_REQUEST['estorig']."', ";
			$udate_id=$_REQUEST['estorig'];
		}

		if (isset($_REQUEST['cpname']) and !empty($_REQUEST['cpname']))
		{
			$qryA  .= "cpname='".htmlspecialchars(ucwords($_REQUEST['cpname']),ENT_QUOTES)."', ";
		}

		$qryA  .= "cfname='".htmlspecialchars(ucwords($_REQUEST['cfname']),ENT_QUOTES)."', ";
		$qryA  .= "clname='".htmlspecialchars(ucwords($_REQUEST['clname']),ENT_QUOTES)."', ";
		$qryA  .= "caddr1='".htmlspecialchars(ucwords($_REQUEST['caddr1']),ENT_QUOTES)."', ";
		$qryA  .= "ccity='".htmlspecialchars(ucwords($_REQUEST['ccity']),ENT_QUOTES)."', ";
		$qryA  .= "cstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
		$qryA  .= "czip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
		$qryA  .= "czip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
		$qryA  .= "ccounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
		$qryA  .= "cmap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
		$qryA  .= "chome='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['chome']),ENT_QUOTES)."', ";
		$qryA  .= "cwork='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['cwork']),ENT_QUOTES)."', ";
		$qryA  .= "ccell='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['ccell']),ENT_QUOTES)."', ";
		$qryA  .= "cfax='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['cfax']),ENT_QUOTES)."', ";
		$qryA  .= "cemail='".replacequote($_REQUEST['cemail'])."', ";
		$qryA  .= "cconph='".htmlspecialchars($_REQUEST['cconph'],ENT_QUOTES)."', ";
		$qryA  .= "ccontime='".htmlspecialchars($_REQUEST['ccontime'],ENT_QUOTES)."', ";
		$qryA  .= "appt_mo='".$_REQUEST['appt_mo']."', ";
		$qryA  .= "appt_da='".$_REQUEST['appt_da']."', ";
		$qryA  .= "appt_yr='".$_REQUEST['appt_yr']."', ";
		$qryA  .= "appt_hr='".$_REQUEST['appt_hr']."', ";
		$qryA  .= "appt_mn='".$_REQUEST['appt_mn']."', ";
		$qryA  .= "appt_pa='".$_REQUEST['appt_pa']."', ";
		$qryA  .= "hold='".$hold."', ";
		
		if ($hold==1)
		{
			$qryA  .= "hold_mo='".$_REQUEST['hold_mo']."', ";
			$qryA  .= "hold_da='".$_REQUEST['hold_da']."', ";
			$qryA  .= "hold_yr='".$_REQUEST['hold_yr']."', ";
			$qryA  .= "callback='". old_date_disp($_REQUEST['hold_mo'],$_REQUEST['hold_da'],$_REQUEST['hold_yr'],'00','00','1') ."',";
		}

		if ($row['mas_prep']==0 || $_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==SYS_ADMIN)
		{
			if ($row['jobid']=='0')
			{
				if (empty($_REQUEST['ssame']))
				{
					$qryA  .= "ssame='0', ";
					$qryA  .= "saddr1='".htmlspecialchars(ucwords($_REQUEST['saddr1']),ENT_QUOTES)."', ";
					$qryA  .= "scity='".htmlspecialchars(ucwords($_REQUEST['scity']),ENT_QUOTES)."', ";
					$qryA  .= "sstate='".htmlspecialchars($_REQUEST['sstate'],ENT_QUOTES)."', ";
					$qryA  .= "szip1='".htmlspecialchars($_REQUEST['szip1'],ENT_QUOTES)."', ";
					$qryA  .= "szip2='".htmlspecialchars($_REQUEST['szip2'],ENT_QUOTES)."', ";
					$qryA  .= "scounty='".htmlspecialchars($_REQUEST['scounty'],ENT_QUOTES)."', ";
					$qryA  .= "smap='".htmlspecialchars($_REQUEST['smap'],ENT_QUOTES)."', ";
				}
				else
				{
					$qryA  .= "ssame='".$_REQUEST['ssame']."', ";
					$qryA  .= "saddr1='".htmlspecialchars(ucwords($_REQUEST['caddr1']),ENT_QUOTES)."', ";
					$qryA  .= "scity='".htmlspecialchars(ucwords($_REQUEST['ccity']),ENT_QUOTES)."', ";
					$qryA  .= "sstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
					$qryA  .= "szip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
					$qryA  .= "szip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
					$qryA  .= "scounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
					$qryA  .= "smap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
				}
			}
			
			$qryA  .= "stage='".$_REQUEST['stage']."', ";
			$qryA  .= "dupe='".$_REQUEST['dupe']."', ";
		}
		
		if (!in_array($row['source'],$ex_status_codes))
		{
			$qryA  .= "source='".$_REQUEST['source']."', ";
			$qryA  .= "opt1='".$opt1."', ";
			$qryA  .= "opt2='".$opt2."', ";
			$qryA  .= "opt3='".$opt3."', ";
			$qryA  .= "opt4='".$opt4."', ";
		}
		
		if ($row['ccontact']==0 && !empty($_REQUEST['ccontact']) && $_REQUEST['ccontact']==1)
		{
			$qryA  .= "ccontact='".$_REQUEST['ccontact']."', ";
			$qryA  .= "ccontactdate=getdate(), ";
			$qryA  .= "ccontactby='".$_SESSION['securityid']."', ";
		}
		
		if (
				isset($_REQUEST['appt_yr']) && isset($_REQUEST['appt_mo']) && isset($_REQUEST['appt_da']) &&
				$_REQUEST['appt_mo']!='0' && $_REQUEST['appt_da']!='0' && $_REQUEST['appt_yr']!='0000'
			)
		{
			//echo "OK!!<br>";
			$qryA  .= "apptmnt='". old_date_disp($_REQUEST['appt_mo'],$_REQUEST['appt_da'],$_REQUEST['appt_yr'],$_REQUEST['appt_hr'],$_REQUEST['appt_mn'],$_REQUEST['appt_pa']) ."',";
		}
		
		if (isset($_REQUEST['market']) and strlen($_REQUEST['market']) >= 2)
		{
			$qryA  .= "market='".htmlspecialchars(trim($_REQUEST['market']),ENT_QUOTES)."', ";
		}
		
		if (isset($_REQUEST['cptype']) and strlen($_REQUEST['cptype']) >= 2)
		{
			$qryA  .= "cptype='".htmlspecialchars(trim($_REQUEST['cptype']),ENT_QUOTES)."', ";
		}
		
		$qryA  .= "updated=getdate() ";
		$qryA  .= "WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
		$resA  = mssql_query($qryA);
		
		// Adds Comment
		if (isset($_REQUEST['addcomment']) && strlen($_REQUEST['addcomment']) >= 2)
		{
			$qryA1 = "SELECT id FROM jest..chistory WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['cid']."' and tranid='".$_REQUEST['tranid']."';";
			$resA1 = mssql_query($qryA1);
			$nrowA1 = mssql_num_rows($resA1);
			
			if ($nrowA1 == 0)
			{
				$inputtext=removequote($_REQUEST['addcomment']);
				$complaint=0;
				$cservice=0;
				$followup=0;
				$resolve=0;
				$relid=0;
				
				$qryA2  = "INSERT INTO jest..chistory (officeid,secid,custid,act,tranid,mtext,complaint,followup,resolved,relatedcomplaint,cservice) ";
				$qryA2 .= "VALUES ";
				$qryA2 .= "('".$_SESSION['officeid']."','".$_SESSION['securityid']."','".$_REQUEST['cid']."','leads','".$_REQUEST['tranid']."','".htmlspecialchars(removequote($inputtext),ENT_QUOTES)."',".$complaint.",".$followup.",".$resolve.",".$relid.",".$cservice.");";
				$resA2  = mssql_query($qryA2);
			}
		}
		
		// Adds Inactive Comment
		if (isset($_REQUEST['dupe']) and $_REQUEST['dupe']==1 and $_REQUEST['dupe']!=$row['dupe'])
		{
			$qryA3  = "INSERT INTO jest..chistory (officeid,secid,custid,act,tranid,mtext) ";
			$qryA3 .= "VALUES ";
			$qryA3 .= "('".$_SESSION['officeid']."','".$_SESSION['securityid']."','".$_REQUEST['cid']."','leads','".$_REQUEST['tranid']."','Lead set Inactive');";
			$resA3  = mssql_query($qryA3);
		}
		
		//Create Entry in Lead History table
		$qryB   = "INSERT INTO jest..leadhistory (cinfo_id,officeid,owner,uby,source,result,clname,cfname,caddr1,czip1,saddr1,szip1,chome,ccell,cwork,appt) ";
		$qryB  .= "VALUES ";
		$qryB  .= "('".$row['cid']."','".$row['officeid']."','".$udate_id."','".$_SESSION['securityid']."','".$row['source']."','".$row['stage']."','".$row['clname']."','".$row['cfname']."','".$row['caddr1']."','".$row['czip1']."','".$row['saddr1']."','".$row['szip1']."','".$row['chome']."','".$row['ccell']."','".$row['cwork']."','".$row['apptmnt']."')";
		$resB  = mssql_query($qryB);

		//Update chistory table for inter-office moves
		//if ($_SESSION['officeid']!=$_REQUEST['site'])
		//{
		//	$qryC	= "UPDATE chistory SET officeid='".$_REQUEST['site']."' WHERE custid='".$_REQUEST['cid']."';";
		//	$resC	= mssql_query($qryC);
		//}
		
		// Create Finance Record
		if (isset($_REQUEST['finansrc']) && $_REQUEST['finansrc'] > 0)
		{
			add_finan_cust($_SESSION['officeid'],$row1['finan_from'],$_REQUEST['cid'],$_SESSION['securityid'],$_REQUEST['uid']);
		}
		
		if ($_SESSION['llev'] >= 5)
		{
			if ($_SESSION['officeid']!=$_REQUEST['site'])
			{
				echo "<b>Lead forwarded to ".$row3['name']."</b>";
			}
			else
			{
				if (!empty($_SESSION['tqry']) and strlen($_SESSION['tqry']) > 5 and $row6['returntolist']==1)
				{
					listleads();
				}
				else
				{
					@cform_view();
				}
			}
		}
		else
		{
			if (!empty($_SESSION['tqry']) and strlen($_SESSION['tqry']) > 5 and $row6['returntolist']==1)
			{
				listleads();
			}
			else
			{
				@cform_view();
			}
		}
	}
}

function AutoSendLeadResultEmail()
{
	if (isset($_REQUEST['stage']) && $_REQUEST['stage'] != 0)
	{
		$qryZ1 = "SELECT etid FROM jest..leadstatuscodes WHERE statusid='".$_REQUEST['stage']."';";
		$resZ1 = mssql_query($qryZ1);
		$rowZ1 = mssql_fetch_array($resZ1);
		
		if (isset($rowZ1['etid']) && $rowZ1['etid']!=0)
		{							
			$qryZ3 = "SELECT * FROM jest..EmailTemplate WHERE etid=".$rowZ1['etid'].";";
			$resZ3 = mssql_query($qryZ3);
			$rowZ3 = mssql_fetch_array($resZ3);
			$nrowZ3= mssql_num_rows($resZ3);
			
			if ($nrowZ3 > 0)
			{
				$blocksend=false;
				$bodyexptxt='';
				$appt='';
				$callb='';
				$qryZ2 = "SELECT top 1 * FROM jest..EmailTracking WHERE cid=".$_REQUEST['cid']." and lid=".$_REQUEST['stage']." order by sdate desc;";
				$resZ2 = mssql_query($qryZ2);
				$rowZ2 = mssql_fetch_array($resZ2);
				$nrowZ2= mssql_num_rows($resZ2);
				
				//Appointment Rules
				if ($rowZ3['sendappt'] == 1)
				{
					if ($_REQUEST['appt_mo']!=0 && $_REQUEST['appt_da']!=0 && $_REQUEST['appt_yr']!=0)
					{
						//echo '1-AM<br>';
						$qryZZ = "SELECT cid,apptmnt FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
						$resZZ = mssql_query($qryZZ);
						$rowZZ = mssql_fetch_array($resZZ);
						//echo $nrowZ2.'<br>';
						
						//echo strtotime($rowZZ['apptmnt']).'<br>';
						//echo strtotime($rowZ2['apptmnt']).'<br>';
						
						if ($nrowZ2 > 0 && strtotime($rowZZ['apptmnt']) != strtotime($rowZ2['apptmnt']))
						{
							//echo '2-AM<br>';
							$appt		=date('m/d/Y g:i a',strtotime($rowZZ['apptmnt']));
							$bodyexptxt	=date('m/d/Y g:i a',strtotime($rowZZ['apptmnt']));
						}
						else
						{
							//echo '3-AM<br>';
							if (
								isset($row['apptmnt']) && strtotime($row['apptmnt'])
								!= strtotime(old_date_disp($_REQUEST['appt_mo'],$_REQUEST['appt_da'],$_REQUEST['appt_yr'],$_REQUEST['appt_hr'],$_REQUEST['appt_mn'],$_REQUEST['appt_pa']))
								)
							{
								$appt		=date('m/d/Y g:i a',strtotime($rowZZ['apptmnt']));
								$bodyexptxt	=date('m/d/Y g:i a',strtotime($rowZZ['apptmnt']));
							}
							else
							{
								$blocksend=true;
							}
						}
					}
				}

				//Callback Rules
				if ($rowZ3['sendcallb'] == 1)
				{
					if (isset($_REQUEST['hold']) && $_REQUEST['hold_mo']!=0 && $_REQUEST['hold_da']!=0 && $_REQUEST['hold_yr']!=0)
					{
						echo 'Callback<br>';
						$qryZZ = "SELECT cid,callback FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
						$resZZ = mssql_query($qryZZ);
						$rowZZ = mssql_fetch_array($resZZ);

						//echo 'CI: '.strtotime($rowZZ['callback']).'<br>';
						//echo 'RQ: '.strtotime($_REQUEST['hold_mo'].'/'.$_REQUEST['hold_da'].'/'.$_REQUEST['hold_yr']).'<br>';
						//echo 'OL: '.old_date_disp($_REQUEST['hold_mo'],$_REQUEST['hold_da'],$_REQUEST['hold_yr'],'00','00','1').'<br>';
						
						if ($nrowZ2 > 0 && strtotime($rowZZ['callback']) != strtotime($rowZ2['callback']))
						{
							//echo '1CB<br>';
							$callb		=date('m/d/Y h:i a',strtotime($rowZZ['callback']));
							$bodyexptxt	=date('m/d/Y h:i a',strtotime($rowZZ['callback']));
						}
						else
						{
							if (
								isset($row['callback']) && strtotime($row['callback'])
								!= strtotime($_REQUEST['hold_mo'].'/'.$_REQUEST['hold_da'].'/'.$_REQUEST['hold_yr'])
								)
							{
								//echo '2CB<br>';
								$callb		=date('m/d/Y h:i a',strtotime($rowZZ['callback']));
								$bodyexptxt	=date('m/d/Y h:i a',strtotime($rowZZ['callback']));
							}
							else
							{
								//echo '3CB<br>';
								$blocksend=true;
							}
						}
					}
				}
				
				if (isset($rowZ3['senddelay']) && $rowZ3['senddelay'] == -1 && $nrowZ2 > 0)
				{
					//echo 'EMAIL BLOCK Rule Send Restrict = Single Send<br>';
					$blocksend=true;
				}
				
				if ((isset($_REQUEST['opt1']) && $_REQUEST['opt1']==1))
				{
					//echo 'EMAIL BLOCK Rule Opt Out<br>';
					$blocksend=true;
				}
				
				//echo 'BLK: '.$blocksend.'<br>';
				//echo 'MOD: '.$bodyexptxt.'<br>';
				
				if (!$blocksend)
				{
					//echo 'Email Sent!';
					$emc_ar=array(
									//'to'=>		replacequote($_REQUEST['cemail']),
									'to'=>		'thelton@corp.bluehaven.com',
									'from'=>	'bhcustcare@bluehaven.com',
									'fromname'=>'Blue Haven Customer Care',
									'esubject'=>$rowZ3['esubject'],
									'ebody'=>	$rowZ3['ebody'].$bodyexptxt,
									'oid'=> 	$_SESSION['officeid'],
									'lid'=> 	$_REQUEST['stage'],
									'tid'=> 	$rowZ1['etid'],
									'cid'=> 	$_REQUEST['cid'],
									'uid'=> 	$_SESSION['securityid'],
									'appt'=> 	$appt,
									'callb'=> 	$callb
								);
					
					ExtEmailSendPlain($emc_ar);
				}
			}
		}
	}
}

function cform_delete()
{
	$qry = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
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
		$qryA = "DELETE FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
		$resA = mssql_query($qryA);

		//listleads();
	}
}

?>