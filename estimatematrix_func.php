<?php


function CreateContractwTAX()
{
	error_reporting(E_ALL);
	//echo __FUNCTION__.'<br>';
	
	$qrypre1	= "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
	$respre1	= mssql_query($qrypre1);
	$rowpre1	= mssql_fetch_array($respre1);
	
	if ($rowpre1['contractamt'] < 1)
	{
		echo 'Contract Amount must be greater than 0.00<br>';
		exit;
	}

	$qrypre2	= "SELECT psched,psched_perc,code,stax,finan_from,com_rate,over_split FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2	= mssql_query($qrypre2);
	$rowpre2	= mssql_fetch_array($respre2);
	
	$qrypre3	= "SELECT cid,clname,cfname,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rowpre1['ccid']."' ;";
	$respre3	= mssql_query($qrypre3);
	$rowpre3	= mssql_fetch_array($respre3);
	
	$qrypre4	= "SELECT securityid,sidm,com_rate,over_split FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowpre1['securityid']."';";
	$respre4	= mssql_query($qrypre4);
	$rowpre4	= mssql_fetch_array($respre4);
	
	if ($rowpre3['jobid']!='0')
	{
		echo "<b>Contract ".$rowpre3['jobid']." already exists for this Estimate.</b>";
		exit;
	}
	
	if ($rowpre4['com_rate']==0)
	{
		$base_rate=$rowpre2['com_rate'];
	}
	else
	{
		$base_rate=$rowpre4['com_rate'];
	}
	
	if ($rowpre4['over_split']==0)
	{
		$over_split=$rowpre2['over_split'];
	}
	else
	{
		$over_split=$rowpre4['over_split'];
	}
	
	if (isset($_REQUEST['oubook']) && $_REQUEST['oubook'] != 0)
	{
		$oubook=$_REQUEST['oubook'];
	}
	else
	{
		$oubook=0;
	}
	
	if (isset($_REQUEST['adjbook']) && $_REQUEST['adjbook'] != 0)
	{
		$adjbook=$_REQUEST['adjbook'];
	}
	else
	{
		$adjbook=0;
	}
	
	$comm_ar=array(
						'fctramt'=>$rowpre1['contractamt'],
						'estid'=>$rowpre1['estid'],
						'base_rate'=>$base_rate,
						'over_split'=>$over_split,
						'oubook'=>$oubook,
						'adjbook'=>$adjbook,
						'sidm'=>$rowpre1['sidm']
					);

	if ($rowpre2['stax']==1)
	{
		if ($rowpre1['tax']=="0.00")
		{
			$contractamt	=$rowpre1['contractamt'];
			$salestx		=0;
			$camt			=$contractamt+$salestx;

		}
		else
		{
			$contractamt	=$rowpre1['contractamt'];
			$salestx		=$rowpre1['tax'];
			$camt			=$contractamt+$salestx;
		}
	}
	else
	{
		$camt			=$rowpre1['contractamt'];
	}

	$fcamt	=number_format($camt, 2, '.', '');
	$fouamt	=number_format($comm_ar['oubook'], 2, '.', '');

	$tdate	=date("m/d/Y", time());
	$sdate	=date("m/d/Y", time());
	$cdate	=date("mdy", time());

	$contractcode=$rowpre1['estid'].".".$rowpre2['code'].".".$cdate;

	echo "<script type=\"text/javascript\" src=\"js/jquery_contract_create_revwTAX.js\"></script>\n";
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td>";
	
	if ($rowpre1['renov']==1)
	{
		echo "<b>Create New Renovation</b>";
	}
	else
	{
		echo "<b>Create New Contract</b>";
	}
	
	echo "		</td>\n";
	echo "		<td align=\"right\">\n";
	echo "			<form name=\"viewest\" method=\"POST\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "				<input type=\"hidden\" name=\"estid\" value=\"".$rowpre1['estid']."\">\n";
	echo "				<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "				<button title=\"Return to Estimate\">Return to Estimate</button>\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<p>\n";
	
	echo "<form id=\"submitContract\" name=\"createcontract\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"post_create_job\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowpre1['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowpre1['sidm']."\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$rowpre1['estid']."\">\n";
	echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowpre1['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$rowpre3['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"jobid\" value=\"".$contractcode."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"camt\" id=\"camt\" value=\"".$fcamt."\">\n";
	echo "<input type=\"hidden\" name=\"renov\" value=\"".$rowpre1['renov']."\">\n";
	echo "<input type=\"hidden\" name=\"overunder\" value=\"".$comm_ar['oubook']."\">\n";
	echo "<input type=\"hidden\" name=\"adjbook\" value=\"".$comm_ar['adjbook']."\">\n";

	if ($rowpre2['stax']==1)
	{
		echo "<input type=\"hidden\" name=\"salestx\" value=\"".$salestx."\">\n";
	}
	
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\"><b>Contract Detail</b></td>\n";
	echo "		<td align=\"left\"><b>Commission Schedule</b></td>\n";
	echo "		<td align=\"left\"><b>Payment Schedule</b></td>\n";
	echo "	</tr>\n";
	
	echo "	<tr>\n";
	echo "		<td align=\"center\" valign=\"top\">\n";
	
	ContractDetail($rowpre1['estid']);
	
	echo "		</td>\n";
	echo "		<td align=\"center\" valign=\"top\">\n";

	CommissionScheduleRW_NEW($comm_ar);
	CommissionScheduleRO_GMSM($comm_ar);
	
	echo "		</td>\n";
	echo "		<td align=\"center\" valign=\"top\">\n";
	
	PaymentScheduleRWwTAX($rowpre3['cid'],$rowpre1['contractamt'],$rowpre2[0],$rowpre2[1]);
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" colspan=\"3\"><button id=\"AcceptSubmit\" title=\"Save Contract\" onClick=\"return CreateContractAlerts()\">Create Contract</button></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	/*
	echo "	<td>\n";

	// Errors
	$keys=array_search(0,$rowpre1);
	$errinp=0;

	if ($rowpre1['pft']==0)
	{
		$errinp++;
		echo 'Error: Perimeter<br>';
		
	}

	if ($rowpre1['sqft']==0)
	{
		$errinp++;
		echo 'Error: Surface Area<br>';
	}

	if ($rowpre1['erun']==0 && $rowpre1['renov']!=1)
	{
		echo 'Error: Electrical Run<br>';
		$errinp++;
	}

	if ($rowpre1['prun']==0 && $rowpre1['renov']!=1)
	{
		echo 'Error: Plumbing Run<br>';
		$errinp++;
	}

	if ($rowpre1['contractamt']==0)
	{
		echo 'Error: Contract Amount<br>';
		$errinp++;
	}

	echo "</form>\n";
	echo "			<table>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\"><b>Save Contract</b></td>\n";
	echo "					<td align=\"right\" width=\"20\">\n";

	if ($errinp > 0)
	{
		echo "                  <input class=\"transnb\" type=\"image\" id=\"AcceptSubmit\" src=\"images/save.gif\" value=\"Apply\" title=\"Save Contract\" DISABLED>\n";
	}
	else
	{
		echo "                  <input class=\"transnb\" type=\"image\" id=\"AcceptSubmit\" src=\"images/save.gif\" value=\"Apply\" title=\"Save Contract\" onClick=\"return CreateContractAlerts()\">\n";
	}

	echo "					</td>\n";
	echo " 		  		</tr>\n";
	echo "			</table>\n";
	
	echo "					</td>\n";
	*/
}

function CreateContract()
{
	if ($_SESSION['securityid']==269999999999) {
		ini_set('display_errors','On');
		error_reporting(E_ALL);
		
		echo "<pre>";
		print_r($_REQUEST);
		echo "</pre>";
	}
	
	$qrypre1	= "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
	$respre1	= mssql_query($qrypre1);
	$rowpre1	= mssql_fetch_array($respre1);
	
	if ($rowpre1['contractamt'] < 1)
	{
		echo 'Contract Amount must be greater than 0.00<br>';
		exit;
	}

	$qrypre2	= "SELECT psched,psched_perc,code,stax,finan_from,com_rate,over_split FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2	= mssql_query($qrypre2);
	$rowpre2	= mssql_fetch_array($respre2);
	
	$qrypre3	= "SELECT cid,clname,cfname,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rowpre1['ccid']."' ;";
	$respre3	= mssql_query($qrypre3);
	$rowpre3	= mssql_fetch_array($respre3);
	
	$qrypre4	= "SELECT securityid,sidm,com_rate,over_split FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowpre1['securityid']."';";
	$respre4	= mssql_query($qrypre4);
	$rowpre4	= mssql_fetch_array($respre4);
	
	if ($rowpre3['jobid']!='0')
	{
		echo "<b>Contract ".$rowpre3['jobid']." already exists for this Estimate.</b>";
		exit;
	}
	
	if ($rowpre4['com_rate']==0)
	{
		$base_rate=$rowpre2['com_rate'];
	}
	else
	{
		$base_rate=$rowpre4['com_rate'];
	}
	
	if ($rowpre4['over_split']==0)
	{
		$over_split=$rowpre2['over_split'];
	}
	else
	{
		$over_split=$rowpre4['over_split'];
	}
	
	if (isset($_REQUEST['oubook']) && $_REQUEST['oubook'] != 0)
	{
		$oubook=$_REQUEST['oubook'];
	}
	else
	{
		$oubook=0;
	}
	
	if (isset($_REQUEST['adjbook']) && $_REQUEST['adjbook'] != 0)
	{
		$adjbook=$_REQUEST['adjbook'];
	}
	else
	{
		$adjbook=0;
	}
	
	$comm_ar=array(
						'fctramt'=>$rowpre1['contractamt'],
						'estid'=>$rowpre1['estid'],
						'base_rate'=>$base_rate,
						'over_split'=>$over_split,
						'oubook'=>$oubook,
						'adjbook'=>$adjbook,
						'sidm'=>$rowpre1['sidm']
					);
	
	
	if ($_SESSION['securityid']==26)
	{
		//echo $qrypre3.'<br>';
	}

	if ($rowpre2['stax']==1)
	{
		if ($rowpre1['tax']=="0.00")
		{
			$contractamt	=$rowpre1['contractamt'];
			$salestx		=0;
			$camt			=$contractamt+$salestx;

		}
		else
		{
			$contractamt	=$rowpre1['contractamt'];
			$salestx		=$rowpre1['tax'];
			$camt			=$contractamt+$salestx;
		}
	}
	else
	{
		$camt			=$rowpre1['contractamt'];
	}

	$fcamt	=number_format($camt, 2, '.', '');
	$fouamt	=number_format($comm_ar['oubook'], 2, '.', '');

	$tdate	=date("m/d/Y", time());
	$sdate	=date("m/d/Y", time());
	$cdate	=date("mdy", time());

	$contractcode=$rowpre1['estid'].".".$rowpre2['code'].".".$cdate;

	if ($_SESSION['securityid']==269999999999999)
	{
		echo 'Test<br>';
		echo "		<script type=\"text/javascript\" src=\"js/jquery_contract_create_rev_TEST.js\"></script>\n";
	}
	else
	{
		echo "		<script type=\"text/javascript\" src=\"js/jquery_contract_create_rev.js\"></script>\n";
	}
	
	echo "			<table class=\"transnb\" align=\"center\" border=0>\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"left\">\n";
	echo "						<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "   						<tr>\n";
	echo "      						<td class=\"gray\">\n";
	
	if ($rowpre1['renov']==1)
	{
		echo "									<b>Create New Renovation</b>";
	}
	else
	{
		echo "									<b>Create New Contract</b>";
	}
	
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\">\n";
	echo "									<form name=\"viewest\" method=\"POST\">\n";
	echo "									<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "									<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "									<input type=\"hidden\" name=\"estid\" value=\"".$rowpre1['estid']."\">\n";
	echo "									<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "									<input class=\"transnb\" type=\"image\" src=\"images/arrow_left.png\" value=\"Estimate\" title=\"Return to Estimate\">\n";
	echo "									</form>\n";
	echo "								</td>\n";
	echo "   						</tr>\n";
	echo "   					</table>\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "      			<td>\n";
	
	echo "<div id=\"createcontract\">\n";
    echo "	<ul>\n";
    echo "		<li><a href=\"#Detail\">Contract Detail</a></li>\n";
	echo "		<li><a href=\"#Commission\">Commission Schedule</a></li>\n";
	echo "		<li><a href=\"#Payment\">Payment Schedule</a></li>\n";
	echo "		<li><a href=\"#Accept\">Accept</a></li>\n";
    echo "	</ul>\n";
    echo "	<div id=\"Detail\">\n";
	echo "		<p>\n";
	
	echo "<form id=\"submitContract\" name=\"createcontract\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"post_create_job\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowpre1['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowpre1['sidm']."\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$rowpre1['estid']."\">\n";
	echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowpre1['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$rowpre3['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"jobid\" value=\"".$contractcode."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
	echo "<input type=\"hidden\" id=\"camt\" name=\"camt\" value=\"".$fcamt."\">\n";
	echo "<input type=\"hidden\" name=\"renov\" value=\"".$rowpre1['renov']."\">\n";
	echo "<input type=\"hidden\" name=\"overunder\" value=\"".$comm_ar['oubook']."\">\n";
	echo "<input type=\"hidden\" name=\"adjbook\" value=\"".$comm_ar['adjbook']."\">\n";

	if ($rowpre2['stax']==1)
	{
		echo "<input type=\"hidden\" name=\"salestx\" value=\"".$salestx."\">\n";
	}
	
	echo "			<table class=\"transnb\" align=\"center\">\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\" width=\"125\"><b>Customer</b></td>\n";
	echo "      			<td align=\"left\" width=\"70\">".$rowpre3['clname']."</td>\n";
	echo "      			<td align=\"right\" width=\"40\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      			<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\" width=\"125\"><b>Estimate</b></td>\n";
	echo "      			<td align=\"left\" width=\"70\">\n";
	
	echo $rowpre1['estid'];
	
	echo "      			</td>\n";
	echo "      			<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      			<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\" width=\"125\"><b>Contract</b></td>\n";
	echo "      			<td align=\"left\" width=\"70\">\n";
	
	echo $contractcode;
	
	echo "      			</td>\n";
	echo "      			<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      			<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "      			<td align=\"right\" width=\"125\"><b>Contract Date</b></td>\n";
	echo "      			<td align=\"left\" width=\"70\">\n";
	
	if (isset($rowpre1['contdate']) && strtotime($rowpre1['contdate']) > strtotime('1/1/2002'))
	{
		echo "							<input class=\"bboxbc\" type=\"text\" name=\"cdate\" id=\"cdate\" size=\"10\" maxlength=\"15\" value=\"".date('m/d/Y',strtotime($rowpre1['contdate']))."\">\n";
	}
	else
	{
		echo "							<input class=\"bboxbc\" type=\"text\" name=\"cdate\" id=\"cdate\" size=\"10\" maxlength=\"15\">\n";
	}
	
	echo "      			</td>\n";
	echo "      			<td align=\"left\">\n";
	echo "					</td>\n";
	echo "      			<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "   			</tr>\n";
	
	if ($rowpre2['finan_from']!=0)
	{
		echo "   <tr>\n";
		echo "      <td align=\"right\" width=\"125\"><b>Finance Type</b></td>\n";
		echo "		<td align=\"left\" colspan=\"2\">\n";
		echo "			<select id=\"finan\" name=\"finan\">\n";
		echo "				<option value=\"0\">Select...</option>\n";
		
		if ($rowpre2['finan_from']!=9999)
		{
			echo "				<option value=\"4\">BlueHaven</option>\n";
		}
		
		echo "				<option value=\"2\">Customer</option>\n";
		echo "				<option value=\"3\">Cash</option>\n";
		echo "			</select>\n";
		echo "		</td>\n";
		echo "      <td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "   </tr>\n";
	}
	
	echo "		</table>\n";
	echo "		</p>\n";
	echo "	</div>\n";
	echo "	<div id=\"Commission\">\n";
	echo "		<p>\n";

	CommissionScheduleRW_NEW($comm_ar);
	CommissionScheduleRO_GMSM($comm_ar);
	
	echo "		</p>\n";
	echo "	</div>\n";
	echo "	<div id=\"Payment\">\n";
	echo "		<p>\n";

	if ($_SESSION['securityid'] == 26)
	{
		PaymentScheduleRWwTAX($rowpre3['cid'],$rowpre1['contractamt'],$rowpre2[0],$rowpre2[1]);
	}
	else
	{
		PaymentScheduleRW($rowpre1['contractamt'],$rowpre2[0],$rowpre2[1]);
	}
	
	echo "		</p>\n";
	echo "	</div>\n";
	echo "	<div id=\"Accept\">\n";
	echo "		<p>\n";

	// Errors
	$keys=array_search(0,$rowpre1);
	$errinp=0;

	if ($rowpre1['pft']==0)
	{
		$errinp++;
		echo 'Error: Perimeter<br>';
		
	}

	if ($rowpre1['sqft']==0)
	{
		$errinp++;
		echo 'Error: Surface Area<br>';
	}

	if ($rowpre1['erun']==0 && $rowpre1['renov']!=1)
	{
		echo 'Error: Electrical Run<br>';
		$errinp++;
	}

	if ($rowpre1['prun']==0 && $rowpre1['renov']!=1)
	{
		echo 'Error: Plumbing Run<br>';
		$errinp++;
	}

	if ($rowpre1['contractamt']==0)
	{
		echo 'Error: Contract Amount<br>';
		$errinp++;
	}

	echo "</form>\n";
	echo "			<table>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\"><b>Save Contract</b></td>\n";
	echo "					<td align=\"right\" width=\"20\">\n";

	if ($errinp > 0)
	{
		echo "                  <input class=\"transnb\" type=\"image\" id=\"AcceptSubmit\" src=\"images/save.gif\" value=\"Apply\" title=\"Save Contract\" DISABLED>\n";
	}
	else
	{
		echo "                  <input class=\"transnb\" type=\"image\" id=\"AcceptSubmit\" src=\"images/save.gif\" value=\"Apply\" title=\"Save Contract\" onClick=\"return CreateContractAlerts()\">\n";
	}

	echo "					</td>\n";
	echo " 		  		</tr>\n";
	echo "			</table>\n";
	
	echo "		</p>\n";
	echo "	</div>\n";
	echo "</div>\n";	
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
}

function cform_view()
{
	$acclist=explode(",",$_SESSION['aid']);

	//print_r($_POST);

	if (empty($_REQUEST['uid']))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error occured.\n";
		exit;
	}

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

	if (empty($cid))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> You must provide a Valid Lead ID number.\n";
		exit;
	}

	$dates	=dateformat();

	if ($_SESSION['llev'] >= 5)
	{
		$qryA = "SELECT officeid,name,stax FROM offices WHERE active=1 ORDER BY name ASC;";
	}
	else
	{
		$qryA = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	}
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,sidm,slevel,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
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

	$qryF = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$cid."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	//echo $qryF;

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER by name ASC;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER by name ASC;";
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

	$adate = date("m-d-Y (g:i A)", strtotime($rowF['added']));

	//$curryr=$rowK['minyr'];
	//$futyr =$rowK['minyr']+2;

	$curryr=date("Y");
	$futyr =$curryr+2;

	//echo "CYR: ".$curryr."<br>";
	//echo "FYR: ".$futyr;

	if ($_REQUEST['uid']=="XXX")
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

	echo "<table width=\"85%\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "		<table class=\"outer\" width=\"100%\" align=\"center\" border=0>\n";
	echo "   	<tr>\n";
	echo "      <td>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"edit\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
	echo "         <table border=\"0\" width=\"100%\">\n";
	echo "         	<tr>\n";
	echo "            	<td bgcolor=\"#d3d3d3\">\n";
	echo "               	<table border=\"0\" width=\"100%\">\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"left\">\n";
	echo "               				<table border=\"0\" width=\"100%\">\n";
	echo "                     			<tr>\n";
	echo "                        			<td align=\"left\"><b>Customer Detailed Information:</font></b></td>\n";
	echo "                                 <td align=\"right\">\n";
	echo "								         </td>\n";
	echo "                                 <td valign=\"bottom\" align=\"right\">&nbsp\n";
	echo "                                    <input type=\"hidden\" name=\"dupe\" value=\"".$rowF['dupe']."\">\n";
	echo "											</td>\n";
	echo "                    				</tr>\n";
	echo "                    			</table>\n";
	echo "								</td>\n";
	echo "                    	</tr>\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"right\" valign=\"bottom\">\n";
	echo "                           <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                           	<tr>\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Date:</b>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">".$adate."</td>\n";
	echo "                                 <td align=\"right\" valign=\"bottom\"><b>Office: </b></td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\">\n";

	if ($_SESSION['llev'] >= 6)
	{
		echo "                                 	<select name=\"site\">\n";
		while ($rowA = mssql_fetch_row($resA))
		{
			if ($_SESSION['officeid']==$rowA[0])
			{
				echo "                                 		<option value=\"".$rowA[0]."\" SELECTED>".$rowA[1]."</option>\n";
			}
			else
			{
				echo "                                 		<option value=\"".$rowA[0]."\">".$rowA[1]."</option>\n";
			}
		}
		echo "                                 	</select>\n";
	}
	elseif ($_SESSION['llev'] == 5)
	{
		echo "                                 	<select name=\"site\">\n";
		while ($rowA = mssql_fetch_row($resA))
		{
			if ($_SESSION['officeid']==$rowA[0])
			{
				echo "                                 		<option value=\"".$rowA[0]."\" SELECTED>".$rowA[1]."</option>\n";
			}
			elseif ($rowA[0]==89)
			{
				echo "                                 		<option value=\"".$rowA[0]."\">".$rowA[1]."</option>\n";
			}
		}
		echo "                                 	</select>\n";
	}
	else
	{
		$rowA = mssql_fetch_row($resA);
		echo "                                 	".$rowA[1]."<input type=\"hidden\" name=\"site\" value=\"".$rowA[0]."\">\n";
	}

	echo "                                 </td>\n";
	echo "                                 <td align=\"right\" valign=\"bottom\"><b>SalesRep:</b>\n";

	if ($_SESSION['llev'] == 4) // Sales Manager List
	{
		echo "                                 	<select name=\"estorig\">\n";

		while ($rowB = mssql_fetch_row($resB))
		{
			if (in_array($rowB[0],$acclist))
			//if ($rowB[3]==$_SESSION['securityid']||$rowB[0]==$_SESSION['securityid']||$rowJ[2]==$_SESSION['securityid'])
			{
				$slev=explode(",",$rowB[4]);
				if ($slev[4]!=0)
				{
					if ($rowF['securityid']==$rowB[0])
					{
						echo "                                 	<option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
					}
					else
					{
						echo "                                 	<option value=\"".$rowB[0]."\">".$rowB[1]." ".$rowB[2]."</option>\n";
					}
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
			if ($slev[4]!=0)
			{
				if ($rowF['securityid']==$rowB[0])
				{
					echo "                                 	<option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
				}
				else
				{
					echo "                                 	<option value=\"".$rowB[0]."\">".$rowB[1]." ".$rowB[2]."</option>\n";
				}
			}
		}

		echo "                                 	</select>\n";

	}
	else
	{
		//echo "                                 ".$_SESSION['fname']." ".$_SESSION['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$_SESSION['securityid']."\">\n";
		echo "                                 ".$rowI['fname']." ".$rowI['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
	}

	//echo $rowI['securityid'];
	echo "                                 </td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "										<tr>\n";
	echo "											<td colspan=\"2\" valign=\"bottom\"><b>Customer:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">First Name</td>\n";
	echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"cfname\" value=\"".$rowF['cfname']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Last Name</td>\n";
	echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"clname\" value=\"".$rowF['clname']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Home Phone</td>\n";
	echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\" value=\"".$rowF['chome']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Work Phone</td>\n";
	echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\" value=\"".$rowF['cwork']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Cell Phone</td>\n";
	echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\" value=\"".$rowF['ccell']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Fax</td>\n";
	echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" value=\"".$rowF['cfax']."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Best Phone</td>\n";
	echo "											<td align=\"left\">\n";
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
	echo "											<td align=\"right\">Email</td>\n";
	echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"cemail\" size=\"30\" value=\"".$rowF['cemail']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Contact Time</td>\n";
	echo "											<td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"ccontime\" value=\"".$rowF['ccontime']."\"></td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"225\">\n";
	echo "										<tr>\n";
	echo "											<td colspan=\"2\" valign=\"top\"><b>Current Address:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Street</td>\n";
	echo "											<td><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"caddr1\" value=\"".$rowF['caddr1']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">City</td>\n";
	echo "											<td><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"ccity\" value=\"".$rowF['ccity']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" value=\"".$rowF['cstate']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Zip</td>\n";
	echo "											<td><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".$rowF['czip1']."\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\" value=\"".$rowF['czip2']."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">Cnty/Twnshp</td>\n";
	echo "											<td>\n";

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
	echo "											<tr>\n";

	if ($rowF['ssame']==1)
	{
		echo "												<td colspan=\"2\" valign=\"top\"><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
	}
	else
	{
		echo "												<td colspan=\"2\" valign=\"top\"><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\"> Same as above</td>\n";
	}

	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\">Street:</td>\n";
	echo "												<td><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\" value=\"".$rowF['saddr1']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\">City:</td>\n";
	echo "												<td><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\">Zip:</td>\n";
	echo "												<td><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td align=\"right\">Cnty/Twnshp:</td>\n";
	echo "												<td>\n";

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
	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"170\">\n";
	echo "										<tr>\n";
	echo "											<td align=\"left\" valign=\"top\"><b>Appointment/Source/Result:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"center\" valign=\"top\">\n";
	echo "												<table>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\" valign=\"bottom\"><b>Date</b></td>\n";
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

	for ($yr=$curryr; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['appt_yr'])
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
	echo "														<td align=\"right\" valign=\"bottom\"><b>Lead Source</b></td>\n";

	if ($rowF['source']==0)
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">Internet</td>\n";
		echo "         											<input type=\"hidden\" name=\"source\" value=\"0\">\n";
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
	echo "														<td align=\"right\" valign=\"bottom\"><b>Lead Result</b></td>\n";
	echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"stage\">\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		//if ($_SESSION['llev'] >= $rowG['access'])
		//{
		if ($rowG['statusid']==$rowF['stage'])
		{
			echo "                                             <option value=\"".$rowG['statusid']."\" SELECTED>".$rowG['name']."</option>\n";
		}
		else
		{
			echo "                                             <option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
		}
		//}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "                                 </tr>\n";

	// Call Back Selects
	echo "                                 <tr>\n";
	echo "                        			<td valign=\"bottom\" align=\"right\"><b>Call Back</b></td>\n";
	echo "                        			<td valign=\"bottom\" align=\"left\">\n";

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

	for ($yr=$curryr; $yr<=$futyr; $yr++)
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

	/*
	if ($rowF['hold_yr']==date("Y"))
	{
	echo "																<option value=\"".date("Y")."\" SELECTED>".date("Y")."</option>\n";
	}
	else
	{
	echo "																<option value=\"".date("Y")."\">".date("Y")."</option>\n";
	}
	*/
	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "												</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td align=\"right\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"170\">\n";
	echo "										<tr>\n";
	echo "											<td valign=\"top\"><b>Comments/Directions:</b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td valign=\"top\" align=\"center\"><textarea name=\"comments\" cols=\"60\" rows=\"10\">".$rowF['comments']."</textarea></td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";

	if (!empty($rowF['mrktproc']))
	{
		echo "							<tr>\n";
		echo "								<td align=\"right\" valign=\"top\" colspan=\"2\">\n";
		echo "									<table class=\"outer\" width=\"100%\" height=\"75\">\n";
		echo "										<tr>\n";
		echo "											<td valign=\"top\"><b>Marketing Data:</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr valign=\"top\">\n";
		echo "											<td><textarea name=\"mrkproc\" cols=\"90\" rows=\"25\" DISABLED>".$rowF['mrktproc']."</textarea></td>\n";
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
	echo "				   <td class=\"gray\">\n";


	if (!empty($_REQUEST['subq']) && $_REQUEST['subq']=="history")
	{
		$qryZ = "SELECT * FROM leadhistory WHERE cinfo_id='".$_REQUEST['cid']."' ORDER BY udate DESC;";
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
					echo "         <td class=\"wh_und\" align=\"left\">Internet</td>\n";
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
	echo "			</tr>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"view\">\n";
	echo "         <input type=\"hidden\" name=\"subq\" value=\"history\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['llev'] >= 4 && $_REQUEST['uid']!="XXX")
	{
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"History\"><br>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";
	echo "      	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"matrix0\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['custid']."\">\n";
	echo "         <input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";
	echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['jlev'] >= 1)
	{
		if ($rowF['hold']==0 && $rowF['dupe']==0 && $rowF['estid']==0)
		{
			echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Estimate\"><br>\n";
			//echo $rowF['hold']."<br>";
			//echo $rowF['dupe']."<br>";
		}
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";

}

function delete_est($estid=null)
{
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}
	
	if ($_REQUEST['call']=="delete_est1")
	{
		$qryA = "SELECT * FROM est WHERE officeid=".(int) $_SESSION['officeid']." AND estid=".(int) $estid.";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		$qryB = "SELECT * FROM cinfo WHERE officeid=".(int) $_SESSION['officeid']." AND cid=".(int) $rowA['ccid'].";";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);

		$acclist=explode(",",$_SESSION['aid']);
		
		/*if (!in_array($rowA['securityid'],$acclist))
		{
			echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to view this Estimate</b>";
			exit;
		}*/

		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"delete_est2\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowA['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowA['sidm']."\">\n";
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$rowA['estid']."\">\n";
		echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowA['cid']."\">\n";
		echo "<input type=\"hidden\" name=\"cid\" value=\"".$rowB['cid']."\">\n";
		echo "<input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";

		echo "<table class=\"outer\" align=\"center\" width=\"300px\" border=0>\n";
		echo "   <tr>\n";
		echo "      <th class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"left\">Confirm Estimate Delete</th>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Estimate Id:</b></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
		echo "         <input class=\"bboxl\" type=\"text\" name=\"estid\" value=\"".$rowA['estid']."\" size=\"25\" DISABLED>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Customer:</b></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
		echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowB['clname'].", ".$rowB['cfname']."\" size=\"25\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\">\n";
		echo "         <button type=\"submit\">Delete Estimate</button>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		echo "</form>\n";

	}
	elseif ($_REQUEST['call']=="delete_est2")
	{
		$qry = "SELECT * FROM est WHERE officeid=".(int) $_SESSION['officeid']." AND estid=".(int) $estid.";";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);
		$nrow	= mssql_num_rows($res);

		$qryA = "SELECT * FROM cinfo WHERE officeid=".(int) $_SESSION['officeid']." AND cid=".(int) $row['ccid'].";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		if ($nrow > 0)
		{
			$uid  =md5(session_id().time().$rowA['cid']).".".$_SESSION['securityid'];

			$qryB	= "exec dbo.sp_deleteestimate @officeid='".$_SESSION['officeid']."',@custid='".$row['cid']."',@cid='".$rowA['cid']."',@estid='".$row['estid']."',@securityid='".$_SESSION['securityid']."',@tranid='".$uid."';";
			$resB	= mssql_query($qryB);

			echo "<form method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "<input type=\"hidden\" name=\"cid\" value=\"".$rowA['cid']."\">\n";
			echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			echo "<table class=\"outer\" align=\"center\" width=\"300px\" border=0>\n";
			echo "   <tr>\n";
			echo "      <th class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"left\">Estimate Deleted!</th>\n";
			echo "   </tr>\n";
			echo "   <tr>\n";
			echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Customer:</b></td>\n";
			echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
			echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowA['clname'].", ".$rowA['cfname']."\" size=\"25\">\n";
			echo "      </td>\n";
			echo "   </tr>\n";
			echo "   <tr>\n";
			echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"center\">Has been reverted to Lead Status.</td>\n";
			echo "   </tr>\n";
			echo "   <tr>\n";
			echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\">\n";
			echo "         <button type=\"submit\">View Lead</button>\n";
			echo "      </td>\n";
			echo "   </tr>\n";
			echo "</table>\n";
			echo "</form>\n";
		}
		else
		{
			echo "Error Occured!";
		}
	}
}

function base_inclusion()
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;

	$qry = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND qtype BETWEEN '48' AND '52';";
	$res = mssql_query($qry);

	while ($row = mssql_fetch_array($res))
	{
		$amt=form_element_calc_ACC($row['id'],$row['quan_calc'],0,0);
		$qryA = "SELECT abrv FROM mtypes WHERE mid='".$row['mtype']."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		echo "           <tr>\n";
		echo "              <td class=\"lg\" valign=\"bottom\" align=\"left\">Base</td>\n";
		echo "              <td class=\"lg\" valign=\"top\" align=\"left\">".$row['item']."</td>\n";
		echo "              <td class=\"lg\" valign=\"bottom\" align=\"right\">".$amt[2]."</td>\n";
		echo "              <td class=\"lg\" valign=\"bottom\" align=\"right\">".$rowA['abrv']."</td>\n";
		echo "              <td class=\"lg\" valign=\"bottom\" align=\"right\">Incl.</td>\n";
		echo "              <td class=\"lg\" valign=\"bottom\" align=\"right\"></td>\n";
		echo "              <td class=\"lg\" valign=\"bottom\" align=\"center\"></td>\n";
		echo "           </tr>\n";
	}
}

function update_contract_amt($estid=null)
{
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}

	if (preg_match('/^[0-9]+\.[0-9]{2}/i',trim($_REQUEST['c_amt'])))
	{
		$qry = "UPDATE est SET contractamt='".trim($_REQUEST['c_amt'])."',updateby='".$_SESSION['securityid']."',updated=GETDATE() WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
		$res = mssql_query($qry);
		
		//echo $qry."<br>";
	
		viewest_retail($estid,0);
	}
	elseif (preg_match('/-/i',trim($_REQUEST['c_amt'])))
	{
		echo "<b>Contract Amount cannot be negative!</b>";
		exit;
	}
	else
	{
		echo "<b>Contract Amount not properly formatted! Must be numerical and in the following format: 00000.00</b>";
		exit;
	}
}

function addadj_init($estid=null)
{
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}
	
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"adjins\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "<table class=\"outer\" align=\"center\" width=\"700px\" border=0>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\">\n";
	echo "         <table align=\"center\">\n";
	echo "            <tr>\n";
	echo "               <th colspan=\"2\" align=\"left\">Add Retail Adjustment for Estimate ID: ".$estid."</th>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" valign=\"top\" align=\"right\"><b>Description:</b></td>\n";
	echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><textarea name=\"descrip\" rows=\"5\" cols=\"50\"></textarea></td>\n";
	echo "            <tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><b>Discount Amount:</b></td>\n";
	echo "               <td class=\"gray\" valign=\"top\" align=\"right\">\n";
	echo "                  <input class=\"bbox\" type=\"text\" name=\"adjamt\" value=\"0.00\">\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" colspan=\"2\" valign=\"top\" align=\"right\">\n";
	echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Add Discount\">\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      <td>\n";
	echo "   <tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function addadj_ins($estid=null)
{
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}
	
	if ($_REQUEST['adjamt'] == 0)
	{
		echo "<font color=\"red\"><b>Error!</b></font><br><font color=\"black\">Adjusts must be greater or Less than Zero. Adjust the Amount.</font>";
		exit;
	}
	
	if ($_REQUEST['adjamt']=="0.00" || $_REQUEST['adjamt']==0)
	{
		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"adjins\">\n";
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "<table class=\"outer\" align=\"center\" width=\"700px\" border=0>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\">\n";
		echo "         <table align=\"center\">\n";
		echo "            <tr>\n";
		echo "               <th colspan=\"2\" align=\"left\">Add Retail Adjustment for Estimate ID: ".$estid."</th>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td></td>\n";
		echo "               <td align=\"left\"><font color=\"red\">Amount must +/- 0.00</font></td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><b>Discount Amount:</b></td>\n";
		echo "               <td class=\"gray\" valign=\"top\" align=\"left\">\n";
		echo "                  <input class=\"bboxl\" type=\"text\" name=\"adjamt\" value=\"0.00\">\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" valign=\"top\" align=\"right\"><b>Description:</b></td>\n";
		echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><textarea name=\"descrip\" rows=\"5\" cols=\"50\">".$_REQUEST['descrip']."</textarea></td>\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" colspan=\"2\" valign=\"top\" align=\"right\">\n";
		echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Add Discount\">\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      <td>\n";
		echo "   <tr>\n";
		echo "</table>\n";
		echo "</form>\n";
	}
	else
	{
		if (isset($_REQUEST['tranid']) and strlen($_REQUEST['tranid']) > 1)
		{
			$qry0  = "select id from est_discounts where officeid=".$_SESSION['officeid']." and estid=".$_SESSION['estid']." and tranid='".$_REQUEST['tranid']."'";
			$res0  = mssql_query($qry0);
			$nrow0 = mssql_num_rows($res0);
			
			if ($nrow0==0)
			{
				$qryA  = "INSERT INTO est_discounts ";
				$qryA .= "(estid,officeid,descrip,discount,tranid) ";
				$qryA .= "VALUES ";
				$qryA .= "('".$_SESSION['estid']."','".$_SESSION['officeid']."','".$_REQUEST['descrip']."','".$_REQUEST['adjamt']."','".$_REQUEST['tranid']."');";
				$resA  = mssql_query($qryA);
		
				$qryB = "UPDATE est SET updateby='".$_SESSION['securityid']."',updated=GETDATE() WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
				$resB = mssql_query($qryB);
			}
		}
		else
		{
			$qryA  = "INSERT INTO est_discounts ";
			$qryA .= "(estid,officeid,descrip,discount) ";
			$qryA .= "VALUES ";
			$qryA .= "('".$_SESSION['estid']."','".$_SESSION['officeid']."','".$_REQUEST['descrip']."','".$_REQUEST['adjamt']."');";
			$resA  = mssql_query($qryA);
	
			$qryB = "UPDATE est SET updateby='".$_SESSION['securityid']."',updated=GETDATE() WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
			$resB = mssql_query($qryB);
		}

		viewest_retail($_SESSION['estid'],0);
	}
}

function remove_acc()
{
	$i=0;
	$a=0;
	$b=0;
	$qryA  = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$resA  = mssql_query($qryA);
	$rowA  = mssql_fetch_array($resA);

	//print_r($_POST);

	foreach ($_POST as $n=>$v)
	{
		if (substr($n,0,3)=="xxx")
		{
			$idata=substr($n,3);
			$postarray[]=$idata;
			$i++;
		}
		elseif (substr($n,0,3)=="aaa")
		{
			$adata=substr($n,3);
			$apostarray[]=$adata;
			$a++;
		}
		elseif (substr($n,0,3)=="bbb")
		{
			$bdata=substr($n,3);
			$bpostarray[]=$bdata;
			$b++;
		}
	}

	if ($i > 0)
	{
		foreach ($postarray as $n=>$v)
		{
			$dbarray=explode(",",$rowA[0]);
			foreach ($dbarray as $n1 => $v1)
			{
				$itemdata=explode(":",$v1);
				if ($itemdata[0]==$v)
				{
					// Removes Bid Items from est_bids
					$qryB  = "DELETE FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$v."';";
					$resB  = mssql_query($qryB);

					$diffarray[]=$v1;
				}
			}
		}

		$rarray=array_diff($dbarray,$diffarray);
		$racnt=count($rarray);
		$outdata="";

		foreach ($rarray as $n => $v)
		{
			if (!isset($outdata))
			{
				$outdata="";
			}

			if ($racnt!=1)
			{
				$outdata=$outdata.$v.",";
			}
			else
			{
				$outdata=$outdata.$v;
			}
			$racnt--;
		}

		$qryB  = "UPDATE est_acc_ext SET estdata='".$outdata."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
		$resB = mssql_query($qryB);
	}

	if ($a > 0)
	{
		foreach ($apostarray AS $na => $va)
		{
			$qryC  = "DELETE FROM est_discounts WHERE id='".$va."' AND officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
			$resC = mssql_query($qryC);
		}
	}

	if ($b > 0)
	{
		foreach ($bpostarray AS $nb => $vb)
		{
			$qryD  = "DELETE FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$vb."';";
			$resD = mssql_query($qryD);
		}
	}

	$qryE = "UPDATE est SET updateby='".$_SESSION['securityid']."',updated=GETDATE() WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$resE = mssql_query($qryE);

	viewest_retail($_SESSION['estid'],0);
}

function remove_est_adj_item()
{
	if (isset($_REQUEST['iid']) and $_REQUEST['iid']!=0)
	{
		$qryC	= "DELETE FROM est_discounts WHERE id=".$_REQUEST['iid']." AND officeid=".$_SESSION['officeid']." AND estid=".$_SESSION['estid'].";";
		$resC	= mssql_query($qryC);
	}
	
	viewest_retail($_SESSION['estid'],0);
}

function setretailitemlist($data)
{
	$celldelim=",";
	$contdelim=":";
	$data1=explode($celldelim,$data);
	foreach ($data1 as $n1=>$v1)
	{
		$itemar[]=array(0=>0);
	}
	return $itemar;
}

function setcostitemlist($data,$searchval)
{
	$MAS=$_SESSION['pb_code'];
	//echo $data;
	if ($searchval=="L")
	{
		$tb="[".$MAS."rclinks_l]";
	}
	elseif ($searchval=="M")
	{
		$tb="[".$MAS."rclinks_m]";
	}
	//This function takes a multidimension Array ($data) with cell/content delimiters and returns a match based
	$celldelim=",";
	$contdelim=":";
	$data1=explode($celldelim,$data);
	foreach ($data1 as $n1=>$v1)
	{
		$v1array=explode($contdelim,$v1);

		//echo "<pre>";
		//print_r($v1array);
		//echo "</pre>";

		$qry  = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$v1array[0]."';";
		$res  = mssql_query($qry);
		$row  = mssql_fetch_row($res);

		$qryA  = "SELECT cid FROM ".$tb." WHERE officeid='".$_SESSION['officeid']."' AND rid='".$v1array[0]."';";
		$resA  = mssql_query($qryA);
		$nrowA  = mssql_num_rows($resA);

		if ($nrowA > 0)
		{
			while ($rowA  = mssql_fetch_row($resA))
			{
				// breakout (0=Cost Item ID,1=Quantity,2=,3=Retail Item ID)
				$itemar[]=array(0=>$rowA[0],1=>$v1array[2],2=>$v1array[4],3=>$v1array[0]);
			}
		}

		if ($row[1]==55||$row[1]==72)
		{
			$qry0  = "SELECT iid,rid,adjamt,adjquan,adjtype FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$v1array[0]."';";
			$res0  = mssql_query($qry0);
			$nrow0  = mssql_num_rows($res0);

			if ($nrow0 > 0)
			{
				while ($row0  = mssql_fetch_array($res0))
				{
					$qryB  = "SELECT cid FROM ".$tb." WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row0['iid']."';";
					$resB  = mssql_query($qryB);
					$nrowB  = mssql_num_rows($resB);

					// Quantity Adjusts
					if ($row0['adjquan']!=0)
					{
						$quan=$row0['adjquan'];
						//echo $quan." ADJ<br>";
					}
					else
					{
						$quan=$v1array[2];
						//echo $quan." NONADJ<br>";
					}

					if ($nrowB > 0)
					{
						while ($rowB  = mssql_fetch_row($resB))
						{
							$itemar[]=array(0=>$rowB[0],1=>$quan,2=>$v1array[4],3=>$v1array[0]);
						}
					}
				}
			}
		}
	}

	if (empty($itemar[0]))
	{
		$itemar=array(0=>0);
	}
	//echo "<pre>";
	//print_r($itemar);
	//echo "</pre>";
	return $itemar;
}

function saveest0()
{
	error_reporting(E_ALL);
	global $viewarray,$tchrg,$estid;
	$estAdata_init =estAdata_init();
	//$finanset=0;

	if (empty($_REQUEST['uid']))
	{
		echo "<b>Transition Error Occured!</b>";
		exit;
	}

	if ($_REQUEST['ps1']==0||$_REQUEST['ps2']==0||$_REQUEST['ps5']==0||$_REQUEST['ps6']==0||$_REQUEST['ps7']==0||$_REQUEST['erun']==0||$_REQUEST['prun']==0)
	{
		echo "<h4><font color=\"red\">Error -  Data missing or incorrect format:</font><br></h4>\n";

		if ($_REQUEST['ps1']==0)
		{
			echo "Perimeter<br>";
		}

		if ($_REQUEST['ps2']==0)
		{
			echo "Surface Area<br>";
		}

		if ($_REQUEST['ps5']==0)
		{
			echo "Shallow Measurement<br>";
		}

		if ($_REQUEST['ps6']==0)
		{
			echo "Middle Measurement<br>";
		}

		if ($_REQUEST['ps7']==0)
		{
			echo "Deep Measurement<br>";
		}

		if ($_REQUEST['erun']==0)
		{
			echo "Electrical Run<br>";
		}

		if ($_REQUEST['prun']==0)
		{
			echo "Plumbing Run<br>";
		}

		echo "Click the BACK button and correct.<br>";

		exit;
	}
	
	/*
	$qry0  = "SELECT finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0  = mssql_query($qry0);
	$row0  = mssql_fetch_array($res0);
	
	if ($row0['finan_from']!=0)
	{
		if (empty($_REQUEST['finan'])||$_REQUEST['finan']==0)
		{
			echo "<font color=\"red\"><b>ERROR</b></font><br>Financing not indicated!<br>Click BACK and SELECT the appropriate Financing.\n";
			exit;
		}
		else
		{
			$finanset=1;
		}
	}
	*/
	
	$qry  = "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND unique_id='".$_REQUEST['uid']."'; ";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);
	$nrow = mssql_num_rows($res);

	//echo "ROW: ".$nrow;

	if (!isset($_REQUEST['phone']))
	{
		$phone="";
	}
	else
	{
		$phone=$_REQUEST['phone'];
	}
	if (!isset($_REQUEST['refto']))
	{
		$refto="";
	}
	else
	{
		$refto=$_REQUEST['refto'];
	}
	
	if (!isset($_REQUEST['renov']))
	{
		$renov=0;
	}
	else
	{
		$renov=$_REQUEST['renov'];
	}
	
	if ($nrow==0)
	{
		$qryA   = "exec sp_insertest ";
		$qryA  .= "@officeid='".$_SESSION['officeid']."', ";
		$qryA  .= "@securityid='".$_REQUEST['securityid']."', ";
		$qryA  .= "@sidm='".$_REQUEST['sidm']."', ";
		$qryA  .= "@status='0', ";
		$qryA  .= "@pft='".replacequote($_REQUEST['ps1'])."', ";
		$qryA  .= "@sqft='".replacequote($_REQUEST['ps2'])."', ";
		$qryA  .= "@apft='0', ";
		$qryA  .= "@shal='".replacequote($_REQUEST['ps5'])."', ";
		$qryA  .= "@mid='".replacequote($_REQUEST['ps6'])."', ";
		$qryA  .= "@deep='".replacequote($_REQUEST['ps7'])."', ";
		$qryA  .= "@deck='".replacequote($_REQUEST['deck'])."', ";
		$qryA  .= "@spa_pft='".replacequote($_REQUEST['spa2'])."', ";
		$qryA  .= "@spa_sqft='".replacequote($_REQUEST['spa3'])."', ";
		$qryA  .= "@spatype='".replacequote($_REQUEST['spa1'])."', ";
		$qryA  .= "@tzone='".replacequote($_REQUEST['tzone'])."', ";
		$qryA  .= "@erun='".replacequote($_REQUEST['erun'])."', ";
		$qryA  .= "@prun='".replacequote($_REQUEST['prun'])."', ";
		$qryA  .= "@renov='".$renov."', ";
		$qryA  .= "@btchrg='".$tchrg[0]."', ";
		$qryA  .= "@rtchrg='".$tchrg[1]."', ";
		$qryA  .= "@contractamt='".replacequote($_REQUEST['contractamt'])."', ";
		$qryA  .= "@refto='".replacequote($refto)."', ";
		$qryA  .= "@est_cost='0', ";
		$qryA  .= "@cid='".$_REQUEST['cid']."', ";
		$qryA  .= "@unique_id='".$_REQUEST['uid']."', ";
		$qryA  .= "@estAdata='".$estAdata_init."';";
		$resA   = mssql_query($qryA);
		$rowA   = mssql_fetch_row($resA);

		$estid	=$rowA[0];

		//$qryAb  = "UPDATE cinfo SET estid='".$_SESSION['estid']."' WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['cid']."';";
		//$resAb  = mssql_query($qryAb);
		//$rowAb  = mssql_fetch_row($resAb);

		// Writing Bid Items
		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,4)=="bbba")
			{
				$asid=substr($n,4);
				if ($_REQUEST['bbba'.$asid] > 0)
				{
					if (array_key_exists("eeea".$asid,$_POST))
					{
						$qryB  = "INSERT INTO est_bids (officeid,estid,bidinfo,bidaccid) VALUES ('".$_SESSION['officeid']."','".$estid."','".replacequote($_REQUEST['eeea'.$asid])."','$asid');";
						$resB  = mssql_query($qryB);
					}
				}
			}
		}
		
		//echo "FIN: ".$finanset."<br>";
		//echo "FOF: ".$row0['finan_from']."<br>";
		//echo "UID: ".$_REQUEST['uid']."<br>";
		
		/*
		if ($finanset==1 && $row0['finan_from']!=0 && !empty($_REQUEST['uid']))
		{
			//echo "SET FINAN<br>";
			add_finan_cust($row0['finan_from'],$_SESSION['officeid'],$_REQUEST['tcid'],$_REQUEST['securityid'],$_REQUEST['uid']);
		}
		*/

		viewest_retail($estid);
	}
	else
	{
		echo "<b>This estimate has already been submitted. Please create a New Lead from the Lead Menu or select a Lead from your Lead List.</b><br>";
		exit;
	}
}

/*
function add_finan_cust($oid,$orig_oid,$cid,$sid,$uid)
{
	//echo "Adding WinFin<br>";
	error_reporting(E_ALL);
	$nsecid	=0;
	$qry  	= "SELECT cid FROM cinfo WHERE officeid='".$orig_oid."' AND cid='".$cid."';";
	$res  	= mssql_query($qry);
	$row  	= mssql_fetch_array($res);
	$nrow 	= mssql_num_rows($res);
	
	//echo $qry."<br>";
	
	if ($nrow==1)
	{
		$qry0  	= "SELECT name,gm,am FROM offices WHERE officeid='".$oid."';";
		$res0  	= mssql_query($qry0);
		$row0  	= mssql_fetch_array($res0);
		
		$ctext  = "System Message - Finance Office Assigned: ".$row0['name'];		

		if ($row0['gm']!=0)
		{
			$nsecid=$row0['gm'];
		}
		else
		{
			$nsecid=$row0['am'];
		}

		$qry1   = "UPDATE cinfo SET finan_from='".$oid."',finan_sec='".$nsecid."',finan_src='".$_REQUEST['finan']."',finan_date=getdate() WHERE officeid=".$orig_oid." AND cid=".$cid.";";
		$res1   = mssql_query($qry1);
		//echo $qry1."<br>";

		$qry2   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
		$qry2  .= "VALUES ";
		$qry2  .= "('".$cid."','".$orig_oid."','".$_SESSION['securityid']."','est','".$ctext."','".$uid."')";
		$res2  = mssql_query($qry2);
	}
}
*/

function updateest($estid=null) {
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}
	
	$MAS		=$_SESSION['pb_code'];
	$estid		=$_SESSION['estid'];
	$qry		= "SELECT bprice,rprice,zcharge FROM [".$MAS."accpbook] WHERE officeid=".$_SESSION['officeid']." AND phsid=40 AND zcharge=".$_REQUEST['tzone'].";";
	$res		= mssql_query($qry);
	$row		= mssql_fetch_row($res);
	
	$qry1		= "SELECT cid,scounty FROM cinfo WHERE officeid=".$_SESSION['officeid']." AND cid=".$_REQUEST['cid'].";";
	$res1		= mssql_query($qry1);
	$row1		= mssql_fetch_array($res1);
	
	$tchrg	=array(0=>$row[0],1=>$row[1]);

	if (!isset($_REQUEST['cfname']))
	{
		$cfname="";
	}
	else
	{
		$cfname=$_REQUEST['cfname'];
	}

	if (!isset($_REQUEST['clname']))
	{
		$clname="";
	}
	else
	{
		$clname=$_REQUEST['clname'];
	}

	if (!isset($_REQUEST['phone']))
	{
		$phone="";
	}
	else
	{
		$phone=$_REQUEST['phone'];
	}

	if (!isset($_REQUEST['refto']))
	{
		$refto="";
	}
	else
	{
		$refto=$_REQUEST['refto'];
	}

	if (!isset($_REQUEST['est_cost']))
	{
		$est_cost=0;
	}
	else
	{
		$est_cost=$_REQUEST['est_cost'];
	}

	$qryA  = "exec sp_updateest ";
	$qryA  .= "@estid='".$estid."', ";
	$qryA  .= "@custid='".$_REQUEST['custid']."', ";
	$qryA  .= "@cid='".$_REQUEST['cid']."', ";
	$qryA  .= "@officeid='".$_SESSION['officeid']."', ";
	$qryA  .= "@securityid='".$_REQUEST['securityid']."', ";
	$qryA  .= "@sidm='".$_REQUEST['sidm']."', ";
	$qryA  .= "@status='".replacequote($_REQUEST['status'])."', ";
	$qryA  .= "@pft='".replacequote($_REQUEST['ps1'])."', ";
	$qryA  .= "@apft='".replacequote($_REQUEST['ps1'])."', ";
	$qryA  .= "@sqft='".replacequote($_REQUEST['ps2'])."', ";
	$qryA  .= "@shal='".replacequote($_REQUEST['ps5'])."', ";
	$qryA  .= "@mid='".replacequote($_REQUEST['ps6'])."', ";
	$qryA  .= "@deep='".replacequote($_REQUEST['ps7'])."', ";
	$qryA  .= "@deck='".replacequote($_REQUEST['deck'])."', ";
	$qryA  .= "@spa_pft='".replacequote($_REQUEST['spa2'])."', ";
	$qryA  .= "@spa_sqft='".replacequote($_REQUEST['spa3'])."', ";
	$qryA  .= "@spatype='".replacequote($_REQUEST['spa1'])."', ";
	$qryA  .= "@tzone='".replacequote($_REQUEST['tzone'])."', ";
	$qryA  .= "@erun='".replacequote($_REQUEST['erun'])."', ";
	$qryA  .= "@prun='".replacequote($_REQUEST['prun'])."', ";
	$qryA  .= "@btchrg='".$tchrg[0]."', ";
	$qryA  .= "@rtchrg='".$tchrg[1]."', ";
	$qryA  .= "@contractamt='".replacequote($_REQUEST['contractamt'])."', ";
	$qryA  .= "@refto='".replacequote($refto)."', ";
	$qryA  .= "@est_cost='".replacequote($est_cost)."', ";
	$qryA  .= "@updateby='".$_SESSION['securityid']."'; ";
	$resA   = mssql_query($qryA);
	
	if (isset($_REQUEST['cid']) && $row1['cid']==$_REQUEST['cid'] && isset($_REQUEST['scounty']) && trim($_REQUEST['scounty'])!=trim($row1['scounty']))
	{
		$qry2		= "update jest..cinfo set scounty='".$_REQUEST['scounty']."' WHERE officeid=".$_SESSION['officeid']." AND cid=".$_REQUEST['cid'].";";
		$res2		= mssql_query($qry2);
	}

	viewest_retail();
}

function add_acc_items($estid=null)
{
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}
	
	$estdata=estAdata_init();

	$qryA  = "sp_updateest_ext @estid='".$estid."',@officeid='".$_SESSION['officeid']."',@estdata='".$estdata."';";
	$resA   = mssql_query($qryA);
	
	if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
	{
		$renov=1;
	}
	else
	{
		$renov=0;
	}

	foreach ($_POST as $n=>$v)
	{
		if (substr($n,0,4)=="bbba")
		{
			$asid=substr($n,4);
			if ($_REQUEST['bbba'.$asid] > 0)
			{
				if (array_key_exists("eeea".$asid,$_POST))
				{
					$qryB  = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
					$resB  = mssql_query($qryB);
					$rowB  = mssql_fetch_array($resB);
					$nrowB = mssql_num_rows($resB);

					if ($nrowB < 1)
					{
						$qryC  = "INSERT INTO est_bids (officeid,estid,bidinfo,bidaccid) VALUES ('".$_SESSION['officeid']."','".$_SESSION['estid']."','".replacequote($_REQUEST['eeea'.$asid])."','".$asid."');";
						$resC  = mssql_query($qryC);
					}
					elseif ($_REQUEST['eeea'.$asid]!=$rowB['bidinfo'])
					{
						$qryC  = "UPDATE est_bids SET bidinfo='".replacequote($_REQUEST['eeea'.$asid])."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
						$resC  = mssql_query($qryC);
					}
				}
			}
		}
	}

	$qryD = "UPDATE est SET updateby='".$_SESSION['securityid']."',renov='".$renov."',updated=GETDATE() WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$resD = mssql_query($qryD);

	viewest_retail($estid,0);
}

function add_acc_items_TED($oid,$estid)
{
	$estdata=estAdata_init_TED();
	
	//echo 'EstData: '.$estdata;

	//exit;
	$qryA  = "sp_updateest_ext @estid=".(int) $estid.",@officeid=".(int) $oid.",@estdata='".$estdata."';";
	$resA   = mssql_query($qryA);
	
	if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
	{
		$renov=1;
	}
	else
	{
		$renov=0;
	}

	foreach ($_REQUEST as $n=>$v)
	{
		if (substr($n,0,4)=="bbba")
		{
			$asid=substr($n,4);
			if ($_REQUEST['bbba'.$asid] > 0)
			{
				if (array_key_exists("eeea".$asid,$_REQUEST))
				{
					$qryB  = "SELECT bidinfo FROM est_bids WHERE officeid=".(int) $oid." AND estid=".(int) $estid." AND bidaccid='".$asid."';";
					$resB  = mssql_query($qryB);
					$rowB  = mssql_fetch_array($resB);
					$nrowB = mssql_num_rows($resB);

					if ($nrowB < 1)
					{
						$qryC  = "INSERT INTO est_bids (officeid,estid,bidinfo,bidaccid) VALUES (".(int) $oid.",".(int) $estid.",'".replacequote($_REQUEST['eeea'.$asid])."','".$asid."');";
						$resC  = mssql_query($qryC);
					}
					elseif ($_REQUEST['eeea'.$asid]!=$rowB['bidinfo'])
					{
						$qryC  = "UPDATE est_bids SET bidinfo='".replacequote($_REQUEST['eeea'.$asid])."' WHERE officeid=".(int) $oid." AND estid=".(int) $estid." AND bidaccid='".$asid."';";
						$resC  = mssql_query($qryC);
					}
				}
			}
		}
	}

	$qryD = "UPDATE est SET updateby='".$_SESSION['securityid']."',renov='".$renov."',updated=GETDATE() WHERE officeid=".(int) $oid." AND estid=".(int) $estid.";";
	$resD = mssql_query($qryD);

	viewest_retail($estid,0);
}

function add_acc_items_add($estid=null)
{
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}
	
	$estdata=estAdata_init();

	$qryA  = "sp_updateest_ext_add @estid='".$estid."',@officeid='".$_SESSION['officeid']."',@estdata='".$estdata."';";
	$resA   = mssql_query($qryA);

	foreach ($_POST as $n=>$v)
	{
		if (substr($n,0,4)=="bbba")
		{
			$asid=substr($n,4);
			if ($_REQUEST['bbba'.$asid] > 0)
			{
				if (array_key_exists("eeea".$asid,$_POST))
				{
					$qryB  = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
					$resB  = mssql_query($qryB);
					$rowB  = mssql_fetch_array($resB);
					$nrowB = mssql_num_rows($resB);

					if ($nrowB < 1)
					{
						$qryC  = "INSERT INTO est_bids (officeid,estid,bidinfo,bidaccid) VALUES ('".$_SESSION['officeid']."','".$_SESSION['estid']."','".replacequote($_REQUEST['eeea'.$asid])."','".$asid."');";
						$resC  = mssql_query($qryC);
					}
					elseif ($_REQUEST['eeea'.$asid]!=$rowB['bidinfo'])
					{
						$qryC  = "UPDATE est_bids SET bidinfo='".replacequote($_REQUEST['eeea'.$asid])."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
						$resC  = mssql_query($qryC);
					}
				}
			}
		}
	}
	viewest_retail($estid,1);

	//echo $qryA;
}

function matrix0()
{
	$MAS		=$_SESSION['pb_code'];
	$officeid	=$_SESSION['officeid'];
	$secid		=$_SESSION['securityid'];

	if ($secid==2699999999999999999999999999) {
		print_r($_REQUEST);
	}
	
	//if (!isset($_REQUEST['uid']) || !isset($_REQUEST['cid']))
	if (!isset($_REQUEST['cid'])) {
		echo "<b>Transition Error Occured!</b>\n".__LINE__;
		exit;
	}

	$qrypre2 = "SELECT officeid,name,def_per,def_sqft,def_s,def_m,def_d,pft_sqft,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2 = mssql_query($qrypre2);
	$rowpre2 = mssql_fetch_row($respre2);

	// Builds a list of exisiting categories in the retail accessory table by office
	$qrypre3  = "SELECT DISTINCT a.catid,a.seqn ";
	$qrypre3 .= "FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
	$qrypre3 .= "ON a.catid=b.catid ";
	$qrypre3 .= "AND a.officeid='".$officeid."' ";
	$qrypre3 .= "AND a.active=1 ";
	$qrypre3 .= "AND a.privcat!=1 ";
	$qrypre3 .= "ORDER BY a.seqn ASC;";
	$respre3 = mssql_query($qrypre3);

	//echo $qrypre3."<br>";

	while ($rowpre3 = mssql_fetch_row($respre3))
	{
		$catarray[]=$rowpre3[0];
	}

	$qryA = "SELECT quan FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC";
	$resA = mssql_query($qryA);

	$qryAa = "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);

	$qryB = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum";
	$resB = mssql_query($qryB);

	$qryC = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype='M' AND costing=1 ORDER BY seqnum";
	$resC = mssql_query($qryC);

	$qryD = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC";
	$resD = mssql_query($qryD);

	//$qryE = "SELECT zid,name FROM zoneinfo ORDER BY zid ASC";
	//$resE = mssql_query($qryE);

	$qryF  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!=1 AND spaitem!=1";
	$resF  = mssql_query($qryF);
	$nrowF = mssql_num_rows($resF);

	$qryH  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!=1 AND spaitem!=1 AND phsid=0 ORDER BY seqn ASC";
	$resH  = mssql_query($qryH);
	$nrowH = mssql_num_rows($resH);

	$qryI  = "SELECT id,phsid FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!=1 AND spaitem!=1 AND phsid!=0 ORDER BY seqn ASC";
	$resI  = mssql_query($qryI);
	$nrowI = mssql_num_rows($resI);

	$qryG  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND spaitem=1";
	$resG  = mssql_query($qryG);
	$nrowG = mssql_num_rows($resG);

	$qryK  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND spaitem=1 AND phsid=0 ORDER BY seqn ASC";
	$resK  = mssql_query($qryK);
	$nrowK = mssql_num_rows($resK);

	$qryL  = "SELECT id,phsid FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND spaitem=1 AND phsid!=0 ORDER BY seqn ASC";
	$resL  = mssql_query($qryL);
	$nrowL = mssql_num_rows($resL);

	$qryM  = "SELECT securityid,fname,lname,sidm,rmasid FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_REQUEST['estorig']."';";
	$resM  = mssql_query($qryM);
	$rowM  = mssql_fetch_row($resM);

	$qryN  = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
	$resN  = mssql_query($qryN);
	$rowN  = mssql_fetch_array($resN);

	if ($rowpre2[7]=="p")
	{
		$defmeas=$rowpre2[2];
	}
	else
	{
		$defmeas=$rowpre2[3];
	}

	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"matrix1\">\n";
	echo "<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowM[0]."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowM[3]."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".md5($_REQUEST['cid'])."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$rowN['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"contractamt\" value=\"0.00\">\n";
	echo "<input type=\"hidden\" name=\"showdetail\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"tzone\" value=\"0\">\n";

	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table align=\"center\" width=\"800px\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"right\">\n";
	echo "			<div class=\"noPrint\">\n";
	echo "				<input class=\"buttondkgry\" type=\"submit\" value=\"Estimate\">\n";
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\">\n";
	echo "			<fieldset class=\"pbouter\">\n";
	echo "				<legend>RETAIL ESTIMATE</legend><br>\n";
	echo "               	<table border=\"0\" width=\"100%\">\n";
	echo "               		<tr>\n";
	echo "                  		<td align=\"left\"><b>".$rowpre2[1]."</b></td>\n";
	echo "                     		<td align=\"center\"><b>Customer</b> ".stripslashes($rowN['cfname'])." ".stripslashes($rowN['clname'])."</b></td>\n";
	echo "                     		<td align=\"right\"><b>SalesRep</b> ".$rowM[1]." ".$rowM[2]."</td>\n";
	echo "                     		<td valign=\"bottom\" align=\"right\">\n";
	
	if ($rowM[4]!=0)
	{
		if ($rowN['stage']==17)
		{
			echo "                     	<b>Renovation: </b> <input type=\"checkbox\" class=\"checkboxgry\" name=\"renov\" value=\"1\" CHECKED>\n";
		}
		else
		{
			echo "                     	<b>Renovation: </b> <input type=\"checkbox\" class=\"checkboxgry\" name=\"renov\" value=\"1\">\n";
		}
	}
	else
	{
		echo "<img src=\"images/pixel.gif\">\n";
	}
	
	echo "                     	</td>\n";
	echo "				</table>\n";
	echo "			</fieldset>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"left\">\n";
	echo "			<fieldset class=\"pbouter\">\n";
	echo "				<legend>POOL DIMENSIONS</legend><br>\n";
	
	if ($rowpre2[7]=="p")
	{
		echo "									Perimeter\n";
	}
	else
	{
		echo "									Surface Area\n";
	}
	
	if ($rowAa['quan1t'] > 0)
	{
		if ($rowpre2[7]=="p")
		{
			echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[2]\">\n";
		}
		else
		{
			echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[3]\">\n";
		}
	}
	else
	{
		if ($rowpre2[7]=="p")
		{
			echo "                        	<select name=\"ps1\">\n";
		}
		else
		{
			echo "                        	<select name=\"ps2\">\n";
		}

		while($rowA = mssql_fetch_row($resA))
		{
			if ($rowA[0]==$defmeas)
			{
				echo "                        		<option value=\"$rowA[0]\" SELECTED>$rowA[0]</option>\n";
			}
			else
			{
				echo "                        		<option value=\"$rowA[0]\">$rowA[0]</option>\n";
			}
		}

		echo "                                          </select>\n";
	}
	
	if ($rowpre2[7]=="p")
	{
		echo "									Surface Area\n";
	}
	else
	{
		echo "									Perimeter\n";
	}
	
	if ($rowpre2[7]=="p")
	{
		echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[3]\">\n";
	}
	else
	{
		echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[2]\">\n";
	}
	
	echo "			Depth	<input class=\"bboxbc\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[4]\">\n";
	echo "					<input class=\"bboxbc\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[5]\">\n";
	echo "					<input class=\"bboxbc\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[6]\">\n";
	echo "			Electrical Run	<input class=\"bboxbc\" type=\"text\" name=\"erun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "			Plumbing Run	<input class=\"bboxbc\" type=\"text\" name=\"prun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "			Total Deck	<input class=\"bboxbc\" type=\"text\" name=\"deck\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	
	echo "			</fieldset>\n";
	echo "      </td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"left\">\n";
	echo "			<fieldset class=\"pbouter\">\n";
	echo "				<legend>SPA DIMENSIONS</legend><br>\n";
	echo "				<select name=\"spa1\">\n";

	while($rowD = mssql_fetch_row($resD))
	{
		echo "							<option value=\"".$rowD[0]."\">".$rowD[1]."</option>\n\n";
	}

	echo "				</select>\n";
	
	echo "				Spa Perimeter	<input class=\"bboxbc\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "				Spa Surface Area	<input class=\"bboxbc\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "			</fieldset>\n";
	echo "      </td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"3\" align=\"left\">\n";
	echo "			<fieldset class=\"pbouter\">\n";
	echo "				<legend>REFERRAL</legend><br>\n";
	echo "				<input type=\"text\" name=\"refto\" size=\"25\">\n";	
	echo "			</fieldset>\n";
	echo "		</td>\n";
	echo "   </tr>\n";

	if ($nrowF > 0||$nrowG > 0)
	{
		if (count($catarray) > 0)
		{
			echo "	<tr>\n";
			echo "		<td align=\"left\">\n";
			echo "			<fieldset class=\"pbouter\">\n";
			echo "			<legend>PRICEBOOK</legend>\n";
			echo "			<table class=\"transnb\" width=\"100%\" colspan=\"3\">\n";
			
			// POOL RETAIL ACC ITEM Loop
			foreach ($catarray as $n=>$v)
			{
				$qryJ = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."';";
				$resJ = mssql_query($qryJ);
				$rowJ = mssql_fetch_row($resJ);
	
				if ($v!=0)
				{
					echo "			<tr>\n";
					echo "				<td class=\"wh\" align=\"left\" valign=\"top\">\n";
					echo "					<input type=\"hidden\" name=\"#".$rowJ[0]."\"><b>".$rowJ[1]."</b>\n";
					echo "				</td>\n";
					echo "				<td class=\"wh\" align=\"right\" valign=\"top\"><a href=\"#Top\"><img class=\"transnb\" src=\"images/scrollup.gif\" alt=\"to Top\"></a></td>\n";
					echo "			</tr>\n";
	
					$qryM  = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid=".$_SESSION['officeid']." AND catid='".$v."' AND disabled!='1' ORDER BY seqn;";
					$resM  = mssql_query($qryM);
					$nrowM = mssql_num_rows($resM);
	
					$qcnt=0;
					while ($rowM=mssql_fetch_row($resM))
					{
						$qcnt++;
						echo "		<tr>\n";
						echo "			<td align=\"left\" colspan=\"5\" valign=\"top\">\n";
						
						if ($qcnt==1)
						{
							form_element_ACC($rowM[0],1,0,0);
						}
						elseif ($qcnt==$nrowM)
						{
							form_element_ACC($rowM[0],2,0,0);
						}
						else
						{
							form_element_ACC($rowM[0],0,0,0);
						}
						
						echo "			</td>\n";
						echo "		</tr>\n";
					}
				}
			}
			
			echo "			</table>\n";
			echo "			</fieldset>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}

		echo "	<tr>\n";
		echo "		<td colspan=\"3\" align=\"right\">\n";
		echo "			<div class=\"noPrint\">\n";
		echo "				<input class=\"buttondkgry\" type=\"submit\" value=\"Estimate\">\n";
		echo "			</div>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
	
	echo "</table>\n";
	echo "</div>\n";
	echo "</form>\n";
}

function matrix0_NEW()
{
	$MAS		=$_SESSION['pb_code'];
	$officeid	=$_SESSION['officeid'];

	$secid		=$_SESSION['securityid'];

	if (empty($_REQUEST['uid'])||empty($_REQUEST['cid']))
	{
		echo "<b>Transition Error Occured!</b>\n";
		exit;
	}

	$qrypre2 = "SELECT officeid,name,def_per,def_sqft,def_s,def_m,def_d,pft_sqft,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2 = mssql_query($qrypre2);
	$rowpre2 = mssql_fetch_row($respre2);

	// Builds a list of exisiting categories in the retail accessory table by office
	$qrypre3  = "SELECT DISTINCT a.catid,a.seqn,a.name ";
	$qrypre3 .= "FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
	$qrypre3 .= "ON a.catid=b.catid ";
	$qrypre3 .= "AND a.officeid='".$officeid."' ";
	$qrypre3 .= "AND a.active=1 ";
	$qrypre3 .= "AND a.privcat!=1 ";
	$qrypre3 .= "ORDER BY a.seqn ASC;";
	$respre3 = mssql_query($qrypre3);

	//echo $qrypre3."<br>";
	$catarray=array();
	
	while ($rowpre3 = mssql_fetch_row($respre3))
	{		
		$qryM  = "
					SELECT
						--id,qtype
						id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled,bullet
					FROM
						[".$MAS."acc]
					WHERE
						officeid=".$_SESSION['officeid']."
						AND catid=".$rowpre3[0]."
						AND disabled!=1
					ORDER BY
						seqn;";
					$resM  = mssql_query($qryM);
					$nrowM = mssql_num_rows($resM);
	
					$qcnt=0;
		
		$itmarray=array();
		while ($rowM=mssql_fetch_row($resM))
		{
			$itmarray[$rowM[0]]=$rowM;
		}
		
		$catarray[$rowpre3[0]]=array($rowpre3[0],$rowpre3[2],$itmarray);
	}
	
	//display_array($itmarray);

	$qryA = "SELECT quan FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC";
	$resA = mssql_query($qryA);

	$qryAa = "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);

	$qryB = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum";
	$resB = mssql_query($qryB);

	$qryC = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype='M' AND costing=1 ORDER BY seqnum";
	$resC = mssql_query($qryC);

	$qryD = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC";
	$resD = mssql_query($qryD);

	$qryF  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!=1 AND spaitem!=1";
	$resF  = mssql_query($qryF);
	$nrowF = mssql_num_rows($resF);

	$qryH  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!=1 AND spaitem!=1 AND phsid=0 ORDER BY seqn ASC";
	$resH  = mssql_query($qryH);
	$nrowH = mssql_num_rows($resH);

	$qryI  = "SELECT id,phsid FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!=1 AND spaitem!=1 AND phsid!=0 ORDER BY seqn ASC";
	$resI  = mssql_query($qryI);
	$nrowI = mssql_num_rows($resI);

	$qryG  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND spaitem=1";
	$resG  = mssql_query($qryG);
	$nrowG = mssql_num_rows($resG);

	$qryK  = "SELECT id FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND spaitem=1 AND phsid=0 ORDER BY seqn ASC";
	$resK  = mssql_query($qryK);
	$nrowK = mssql_num_rows($resK);

	$qryL  = "SELECT id,phsid FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND spaitem=1 AND phsid!=0 ORDER BY seqn ASC";
	$resL  = mssql_query($qryL);
	$nrowL = mssql_num_rows($resL);

	$qryM  = "SELECT securityid,fname,lname,sidm,rmasid FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_REQUEST['estorig']."';";
	$resM  = mssql_query($qryM);
	$rowM  = mssql_fetch_row($resM);

	$qryN  = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
	$resN  = mssql_query($qryN);
	$rowN  = mssql_fetch_array($resN);

	if ($rowpre2[7]=="p")
	{
		$defmeas=$rowpre2[2];
	}
	else
	{
		$defmeas=$rowpre2[3];
	}

	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"matrix1\">\n";
	echo "<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowM[0]."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowM[3]."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$rowN['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"contractamt\" value=\"0.00\">\n";
	echo "<input type=\"hidden\" name=\"showdetail\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"tzone\" value=\"0\">\n";

	/*
	if ($_SESSION['securityid']==26)
	{
		echo 'NEW<br>';
	}
	*/
	
	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table align=\"center\" width=\"400px\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\">\n";
	echo "			<div class=\"noPrint\">\n";
	echo "				<input class=\"buttondkgry\" type=\"submit\" value=\"Estimate\">\n";
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<fieldset class=\"pbouter\">\n";
	echo "				<legend>RETAIL ESTIMATE</legend><br>\n";
	echo "               	<table border=\"0\" width=\"100%\">\n";
	echo "               		<tr>\n";
	echo "                  		<td align=\"left\"><b>".$rowpre2[1]."</b></td>\n";
	echo "                     		<td align=\"center\"><b>Customer</b> ".stripslashes($rowN['cfname'])." ".stripslashes($rowN['clname'])."</b></td>\n";
	echo "                     		<td align=\"right\"><b>SalesRep</b> ".$rowM[1]." ".$rowM[2]."</td>\n";
	echo "                     		<td valign=\"bottom\" align=\"right\">\n";
	
	if ($rowM[4]!=0)
	{
		if ($rowN['stage']==17)
		{
			echo "                     	<b>Renovation: </b> <input type=\"checkbox\" class=\"checkboxgry\" name=\"renov\" value=\"1\" CHECKED>\n";
		}
		else
		{
			echo "                     	<b>Renovation: </b> <input type=\"checkbox\" class=\"checkboxgry\" name=\"renov\" value=\"1\">\n";
		}
	}
	else
	{
		echo "<img src=\"images/pixel.gif\">\n";
	}
	
	echo "                     	</td>\n";
	echo "				</table>\n";
	echo "			</fieldset>\n";
	echo "		</td>\n";
	echo "	<tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<fieldset class=\"pbouter\">\n";
	echo "				<legend>POOL DIMENSIONS</legend><br>\n";
	
	if ($rowpre2[7]=="p")
	{
		echo "									Perimeter\n";
	}
	else
	{
		echo "									Surface Area\n";
	}
	
	if ($rowAa['quan1t'] > 0)
	{
		if ($rowpre2[7]=="p")
		{
			echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[2]\">\n";
		}
		else
		{
			echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[3]\">\n";
		}
	}
	else
	{
		if ($rowpre2[7]=="p")
		{
			echo "                        	<select name=\"ps1\">\n";
		}
		else
		{
			echo "                        	<select name=\"ps2\">\n";
		}

		while($rowA = mssql_fetch_row($resA))
		{
			if ($rowA[0]==$defmeas)
			{
				echo "                        		<option value=\"$rowA[0]\" SELECTED>$rowA[0]</option>\n";
			}
			else
			{
				echo "                        		<option value=\"$rowA[0]\">$rowA[0]</option>\n";
			}
		}

		echo "                                          </select>\n";
	}
	
	if ($rowpre2[7]=="p")
	{
		echo "									Surface Area\n";
	}
	else
	{
		echo "									Perimeter\n";
	}
	
	if ($rowpre2[7]=="p")
	{
		echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[3]\">\n";
	}
	else
	{
		echo "                                            <input class=\"bboxbc\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[2]\">\n";
	}
	
	echo "			Depth	<input class=\"bboxbc\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[4]\">\n";
	echo "					<input class=\"bboxbc\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[5]\">\n";
	echo "					<input class=\"bboxbc\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[6]\">\n";
	echo "			Electrical Run	<input class=\"bboxbc\" type=\"text\" name=\"erun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "			Plumbing Run	<input class=\"bboxbc\" type=\"text\" name=\"prun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "			Total Deck	<input class=\"bboxbc\" type=\"text\" name=\"deck\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	
	echo "			</fieldset>\n";
	echo "      </td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<fieldset class=\"pbouter\">\n";
	echo "				<legend>SPA DIMENSIONS</legend><br>\n";
	echo "				<select name=\"spa1\">\n";

	while($rowD = mssql_fetch_row($resD))
	{
		echo "							<option value=\"".$rowD[0]."\">".$rowD[1]."</option>\n\n";
	}

	echo "				</select>\n";
	
	echo "				Spa Perimeter	<input class=\"bboxbc\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "				Spa Surface Area	<input class=\"bboxbc\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "			</fieldset>\n";	
	echo "      </td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\">\n";
	echo "			<fieldset class=\"pbouter\">\n";
	echo "				<legend>REFERRAL</legend><br>\n";
	echo "				<input type=\"text\" name=\"refto\" size=\"25\">\n";
	echo "			</fieldset>\n";
	echo "		</td>\n";
	echo "   </tr>\n";

	if ($nrowF > 0||$nrowG > 0)
	{
		if (is_array($catarray) and count($catarray) > 0)
		{
			echo "	<tr>\n";
			echo "		<td>\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			
			// POOL RETAIL ACC ITEM Loop
			foreach ($catarray as $n=>$v)
			{
				if ($n!=0)
				{
					if (is_array($v[2]) and count($v[2]) > 0)
					{
						echo "				<tr>\n";
						echo "					<td class=\"wh\" colspan=\"4\" align=\"left\" valign=\"top\">\n";
						echo "						<input type=\"hidden\" name=\"#".$n."\">\n";
						echo "					<div onclick=\"SwitchMenu('sub".$n."')\">";
						echo "						<img src=\"images/plus.gif\">\n";
						echo "						<b>".$v[1]."</b>\n";
						echo "					</div>\n";
						echo "					</td>\n";
						echo "					<td class=\"wh\" align=\"right\" valign=\"top\"><a href=\"#Top\"><img class=\"transnb\" src=\"images/scrollup.gif\" alt=\"to Top\"></a></td>\n";
						echo "				</tr>\n";
						echo "				<tr>\n";
						echo "					<td class=\"gray\" colspan=\"5\" valign=\"top\" align=\"left\">\n";
						echo "						<span class=\"submenu\" id=\"sub".$n."\">\n";
						echo "							<fieldset class=\"pbouter\">\n";
						echo "							<table class=\"inner_borders\">\n";
		
						$qcnt=0;
						foreach ($v[2] as $in=>$iv)
						{
							$qcnt++;
							
							if ($qcnt%2)
							{
								$itbg='white';
							}
							else
							{
								$itbg='ltgray';
							}
							
							form_element_ACC_NEW($in,0,0,0,$iv,$itbg);
						}
						
						echo "							</table>\n";
						echo "							</fieldset>\n";
						echo "						</span>\n";
						echo "					</td>\n";
						echo "				</tr>\n";
					}
				}
			}
			
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}

		echo "	<tr>\n";
		echo "		<td align=\"right\">\n";
		echo "			<div class=\"noPrint\">\n";
		echo "				<input class=\"buttondkgry\" type=\"submit\" value=\"Estimate\">\n";
		echo "			</div>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
	
	echo "</table>\n";
	echo "</div>\n";
	echo "</form>\n";
}

function viewest_retail($estid=null)
{
	$off_arr=array(55,75,144);
	
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}
	
	$qry0 = "SELECT officeid,newcommdate FROM offices WHERE officeid=".(int) $_SESSION['officeid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT added,ccid,securityid FROM est WHERE officeid=".(int) $_SESSION['officeid']." AND estid=".(int) $estid.";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2 = "SELECT securityid,sidm,newcommdate FROM security WHERE securityid=".(int) $row1['securityid'].";";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if (strtotime($row1['added']) >= strtotime($row2['newcommdate']))
	{
		viewest_retail_NEW($estid);
	}
	else
	{
		viewest_retail_OLD($estid);
	}
}

function viewest_retail_OLD($estid=null)
{
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	unset($viewarray);
	unset($_SESSION['estbidretail']);
	
	if ($_SESSION['securityid']==26)
	{
		echo 'OLD<br>';
	}
	
	$MAS		=$_SESSION['pb_code'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	$_SESSION['aid']=aidbuilder($_SESSION['jlev'],"j");
	$acclist	=explode(",",$_SESSION['aid']);
	$jobid		=0;
	
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}

	$qrypreA  = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,";
	$qrypreA .= "phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,comadj,";
	$qrypreA .= "sidm,buladj,applyov,applybu,refto,apft,added,updated,updateby,comm,renov,esttype,ccid ";
	$qrypreA .= "FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_row($respreA);
	
	$qrypreAa = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rowpreA[35]."';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreB = "SELECT securityid,sidm FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowpreA[17]."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_row($respreB);
	
	$qrypreC = "SELECT securityid,filestoreaccess FROM security WHERE securityid='".$securityid."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_array($respreC);

	if (!in_array($_SESSION['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>You do not have approriate Access to view this Estimate</b>";
		exit;
	}

	$viewarray=array(
	'estid'=>	$rowpreA[0],
	'jobid'=>	'0',
	'njobid'=>	'0',
	'ps1'=>		$rowpreA[1],
	'ps2'=>		$rowpreA[2],
	'spa1'=>	$rowpreA[3],
	'spa2'=>	$rowpreA[4],
	'spa3'=>	$rowpreA[5],
	'tzone'=>	$rowpreA[6],
	'camt'=>	$rowpreA[7],
	'comt'=>	0,
	'cfname'=>	$rowpreAa['cfname'],
	'clname'=>	$rowpreAa['clname'],
	'phone'=>	$rowpreA[10],
	'status'=>	$rowpreA[11],
	'ps5'=>		$rowpreA[13],
	'ps6'=>		$rowpreA[14],
	'ps7'=>		$rowpreA[15],
	'cid'=>		$rowpreA[35],
	'estsecid'=>$rowpreA[17],
	'deck'=>	$rowpreA[18],
	'erun'=>	$rowpreA[19],
	'prun'=>	$rowpreA[20],
	'jobid'=>	$rowpreA[21],
	'comadj'=>	$rowpreA[22],
	'dbocomm'=>	$rowpreA[32],
	'sidm'=>	$rowpreA[23],
	'buladj'=>	$rowpreA[24],
	'applyov'=>	$rowpreA[25],
	'applybu'=>	$rowpreA[26],
	'refto'=>	$rowpreA[27],
	'ps1a'=>	$rowpreA[28],
	'jadd'=>	0,
	'mjadd'=>	0,
	'custallow'=>0,
	'renov'=>	$rowpreA[33],
	'esttype'=>	$rowpreA[34],
	'discount'=>0,
	'royrel'=>	0,
	'allowdel'=>0,
	'tcomm'=>	0,
	'added'=>	strtotime($rowpreA[29])
	);

	$qrypreD = "SELECT * FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);

	$r_estdata = $rowpreD['estdata'];

	$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft,encost,enest,vgp FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[10]!=1)
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>Estimating has been disabled in ".$rowC[1]."</b>";
		exit;
	}

	$viewarray['missing_bid_items']=bid_item_test($_SESSION['officeid'],$viewarray['estid'],$viewarray['camt'],$rowC[11]);

	if ($rowC[8]=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$viewarray['cid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_row($resL);

	if ($rowpreA[31]!=0)
	{
		$qryM = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowpreA[31]."';";
		$resM = mssql_query($qryM);
		$rowM = mssql_fetch_array($resM);

		$lupdatestr=$rowM['fname']." ".$rowM['lname'];
	}
	else
	{
		$lupdatestr="";
	}

	$qryN = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$resN = mssql_query($qryN);

	$qryP = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$resP = mssql_query($qryP);

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}

	$_SESSION['viewarray']=$viewarray;
	$tbullets   	=0;
	$poolcomm_adj	=detect_package($r_estdata);
	$set_deck   	=deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    	=round($set_deck[0]);
	$set_ia     	=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals   	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$estidret   	=$rowpreA[0];
	$vdiscnt    	=$viewarray['camt'];	
	$bpset			=select_base_pool();
	$pbaseprice 	=$bpset[3];

	if ($poolcomm_adj >= 1)
	{
		$bcomm      =0;
	}
	else
	{
		$bcomm      =$bpset[4];
	}

	$uid			=md5(session_id().time().$rowI[10]).".".$_SESSION['securityid'];

	if (!empty($rowpreA[29]))
	{
		$atime=date("m-d-Y", strtotime($rowpreA[29]));
	}
	else
	{
		$atime="";
	}

	if (!empty($rowpreA[30]))
	{
		$utime=date("m-d-Y", strtotime($rowpreA[30]));
	}
	else
	{
		$utime="";
	}

	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	$fbcomm		=number_format($bcomm, 2, '.', '');
	$ctramt		=$viewarray['camt'];
	$fctramt	=number_format($ctramt, 2, '.', '');
	
	if ($_SESSION['securityid']==99999999999999)
	{
		echo '<pre>';
		
		print_r($viewarray);
		
		echo '</pre>';
	}

	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" id=\"sid1\" value=\"".$viewarray['estsecid']."\">\n";
	echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowI[0]."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$rowI[10]."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"discount\" value=\"".$vdiscnt."\">\n";
	echo "<input type=\"hidden\" name=\"contractamt\" value=\"".$fctramt."\">\n";
	echo "<input type=\"hidden\" name=\"spa1\" value=\"".$viewarray['spa1']."\">\n";
	echo "<input type=\"hidden\" name=\"spa2\" value=\"".$viewarray['spa2']."\">\n";
	echo "<input type=\"hidden\" name=\"spa3\" value=\"".$viewarray['spa3']."\">\n";
	echo "<input type=\"hidden\" name=\"status\" value=\"".$viewarray['status']."\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"update\">\n";
	echo "<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "<input type=\"hidden\" name=\"qecnt\" id=\"ecnt\" value=\"1\">\n";
	echo "<table class=\"transnb\" width=\"900\">\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table class=\"transnb\" align=\"center\" width=\"100%\" border=0>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"2\" valign=\"top\" align=\"left\" width=\"500%\">\n";
	echo "                  <table width=\"100%\" class=\"outer\" border=0>\n";
	echo "                     <tr>\n";
	echo "                     		<td colspan=\"3\" class=\"gray\" align=\"left\"><b>Retail Estimate # <font color=\"red\">".$estidret."</font> for ".$rowC[1]."</b></td>\n";
	echo "                     		<td class=\"gray\" align=\"right\"><b>SalesRep</b></td>\n";
	echo "                     		<td class=\"gray\" align=\"left\">\n";

	if ($_SESSION['elev'] >= 4)
	{
		echo "                                               <select id=\"sid2\" name=\"securityid\">\n";
		//echo "                                               <select id=\"sid2\" name=\"securityid\" onChange=\"return SRChangeAlert('sid1','sid2','ecnt');\">\n";
		while($rowN = mssql_fetch_row($resN))
		{
			if (in_array($rowN[0],$acclist))
			{
				$secl=explode(",",$rowN[3]);
				if ($secl[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}

				if ($viewarray['estsecid']==$rowN[0])
				{
					echo "                                               	<option value=\"".$rowN[0]."\" class=\"".$ostyle."\" SELECTED>".$rowN[1]." ".$rowN[2]."</option>";
				}
				else
				{
					echo "                                               	<option value=\"".$rowN[0]."\" class=\"".$ostyle."\">".$rowN[1]." ".$rowN[2]."</option>";
				}
			}
		}
		echo "                                               </select>\n";
	}
	else
	{
		echo "                                                                        <input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowD[1]." ".$rowD[2]."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$viewarray['estsecid']."\">\n";
	}

	echo "                                                                </td>\n";
	echo "                                       <td class=\"gray\" align=\"right\"><b>Sales Manager:</b></td>\n";
	echo "                                       <td class=\"gray\" align=\"left\">\n";

	if ($_SESSION['elev'] >= 6)
	{
		echo "                                               <select name=\"sidm\">\n";

		while($rowP = mssql_fetch_row($resP))
		{
			$secl=explode(",",$rowP[3]);
			if ($secl[6]==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}

			if ($viewarray['sidm']==$rowP[0])
			{
				echo "                                               	<option value=\"".$rowP[0]."\" class=\"".$ostyle."\" SELECTED>".$rowP[1]." ".$rowP[2]."</option>\n";
			}
			else
			{
				echo "                                               	<option value=\"".$rowP[0]."\" class=\"".$ostyle."\">".$rowP[1]." ".$rowP[2]."</option>\n";
			}
		}
		echo "                                               </select>\n";
	}
	else
	{
		echo "<input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowL[1]." ".$rowL[2]."\">\n";
		echo "<input type=\"hidden\" name=\"sidm\" value=\"".$viewarray['sidm']."\">\n";
	}

	echo "								</td>\n";

	if ($_SESSION['subq']=="print")
	{
		echo "								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		//echo "									<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		//echo "									<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
		//echo "									<input type=\"hidden\" name=\"subq\" value=\"print\">\n";
		//echo "									<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		//echo "									<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		//echo "									<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "							<td class=\"gray\" align=\"right\">\n";
		echo "								<input class=\"buttondkgrypnl80\" type=\"button\" name=\"buttonPrint\" value=\"Print\" onClick=\"window.print()\">\n";
		echo "                     </td>\n";
		echo "</form>\n";
	}
	else
	{
		echo "											<td class=\"gray\" align=\"center\" width=\"20\">\n";
		echo "												<img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\">\n";
		echo "											</td>\n";
	}

	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "							<td class=\"gray\" align=\"right\"></td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Date Added:</b>&nbsp</td>\n";
	echo "							<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"".$atime."\"></td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Date Updated:</b>&nbsp</td>\n";
	echo "							<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"".$utime."\"></td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Last Update by:</b>&nbsp</td>\n";
	echo "							<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"".$lupdatestr."\"></td>\n";
	echo "							<td class=\"gray\" align=\"right\">&nbsp;</td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" width=\"50%\">\n";
	echo "                  <table width=\"100%\" height=\"200\" class=\"outer\" border=0>\n";
	echo "                     <tr>\n";
	echo "               			<td class=\"gray\" valign=\"top\">\n";

	// Customer Display Info
	cinfo_display($viewarray['cid'],$rowC[2]);
	// End Customer Display

	echo "							</td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "               <td valign=\"top\" width=\"50%\">\n";
	echo "                  <table width=\"100%\" height=\"200\" class=\"outer\" border=0>\n";
	echo "                     <tr>\n";
	echo "               			<td class=\"gray\" valign=\"top\">\n";
	
	//echo $viewarray['estid'];
	// Set Pool Detail Display
	pool_detail_display($viewarray['estid']);
	// End Pool Detail Display

	echo "							</td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";

	if ($viewarray['status'] >= 2)
	{
		echo "      <td valign=\"bottom\" align=\"left\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "			<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\" DISABLED>\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "		</td>\n";
	}
	else
	{
		echo "      <td valign=\"bottom\" align=\"left\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		//echo "			<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\">\n";
		echo "			<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\" onClick=\"return SRChangeAlert('sid1','sid2','ecnt');\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "		</td>\n";
	}

	echo "   </tr>\n";
	echo "</form>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" width=\"100%\" border=1>\n";
	echo "           <form method=\"post\">\n";
	echo "           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "           <input type=\"hidden\" name=\"call\" value=\"remove_acc\">\n";
	echo "           <input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
	echo "			 <input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"left\" width=\"100\"><b>Category</b></td>\n";
	echo "              <td class=\"wh\" align=\"left\"><b>Item</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"30\"><b>Quan.</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"30\"><b>Units</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"60\"><b>Retail</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"60\"><b>Comm</b></td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"60\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	if ($viewarray['status'] >= 2)
	{
		echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Delete\" DISABLED>\n";
	}
	else
	{
		echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Delete\">\n";
	}

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "					</td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"left\" width=\"100\">Base</td>\n";
	echo "              <td class=\"wh\" valign=\"top\" align=\"left\"><b>Basic Pool</b></td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"30\">".$bpset[5]."</td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"30\">".$bpset[6]."</td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"60\">".$fpbaseprice."</td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"60\">".$fbcomm."</td>\n";
	echo "              <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"60\">&nbsp</td>\n";
	echo "           </tr>\n";

	//echo $r_estdata."<br>";
	calcbyacc($r_estdata,0);

	// Totals Table Calcs
	$bccost  =$bctotal;
	$rccost  =$rctotal;
	$cccost  =$cctotal;
	$bmcost  =$bmtotal;
	$rmcost  =$rmtotal;
	$trccost =$rccost+$rmcost;
	$cmcost  =$cmtotal;
	$tbcost  =$bccost+$bmcost;
	$trcost  =$pbaseprice+$trccost+$tbid;
	$tccost  =$cccost+$cmcost;
	$trcomm  =$bcomm+$tccost;
	$prof    =($trcost-$tbcost)-$trcomm;
	
	if ($prof!=0)
	{
		$perprof =$prof/$trcost;
	}
	else
	{
		$perprof =0;
	}

	if ($rowC[2]==1)
	{
		$rtax    =$ctramt*$taxrate[1];
		$grtcost =$ctramt+$rtax;
		$frtax   =number_format($rtax, 2, '.', '');
		$fgrtcost=number_format($grtcost, 2, '.', '');
	}

	$fbccost		=number_format($bccost, 2, '.', '');
	$fbmcost		=number_format($bmcost, 2, '.', '');
	$fcccost		=number_format($cccost, 2, '.', '');
	$frccost		=number_format($rccost, 2, '.', '');
	$frmcost		=number_format($rmcost, 2, '.', '');
	$fcmcost		=number_format($cmcost, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftrcost		=number_format($trcost, 2, '.', '');
	$ftccost		=number_format($tccost, 2, '.', '');
	$ftrcomm		=number_format($trcomm, 2, '.', '');

	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b> Pool Price per Book:</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">".$ftrcost."</td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"60\">".$ftrcomm."</td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";

	calc_adjusts($rowpreA[0]);

	//$comadj		=$viewarray['comadj'];
	if (!isset($rowC[5])||!is_numeric($rowC[5]))
	{
		//echo "OBR: ". $rowC[5]."<br>";
		$bullet_rate=0;
	}
	else
	{
		$bullet_rate=$rowC[5];
	}

	$adjbookamt	=$trcost+$discount;
	$fadjbookamt=number_format($adjbookamt, 2, '.', '');

	if ($viewarray['renov']==1)
	{
		$adjctramt	=0;
	}
	else
	{
		$adjctramt	=$ctramt-$adjbookamt;
	}
	
	$fadjctramt	=number_format($adjctramt, 2, '.', '');

	$adjcomm		=0;

	$ou_out		=calc_ou($adjctramt,$adjcomm,$tbullets,$rowC[6],$viewarray['applyov'],$viewarray['comadj'],$bullet_rate,$rowC[7]);

	$foucomm	=number_format($ou_out[0], 2, '.', '');
	$fadjcomm	=number_format($ou_out[1], 2, '.', '');

	if ($viewarray['applyov']==1)
	{
		$tadjcomm	=$trcomm+$fadjcomm;
	}
	else
	{
		$tadjcomm	=$trcomm;
	}

	// Set commission for global
	$viewarray['comt']	=$tadjcomm;
	$ftadjcomm		=number_format($tadjcomm, 2, '.', '');

	//echo "RET: ".$foucomm."<br>";
	//echo "RET: ".$fadjcomm."<br>";
	//echo "RET: ". $viewarray['dbocomm']."<br>";
	//echo "RET: ".$viewarray['comadj']."<br>";
	//echo "RET: ".$viewarray['comt']."<br>";

	echo "           <tr>\n";
	echo "              <td colspan=\"2\" class=\"wh\" align=\"right\"><b>Adjusted Book Price:</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">".$fadjbookamt."</td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	echo "</form>\n";
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"update_contract_amt\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "			 <input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "           <tr>\n";
	echo "              <td colspan=\"2\" class=\"wh\" align=\"right\"><b>Retail Contract Price:</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	echo "                 <input class=\"bbox formatCurrency\" type=\"text\" name=\"c_amt\" size=\"6\" maxlength=\"10\" value=\"".$fctramt."\">\n";
	echo "              </td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	if ($viewarray['status'] >= 2)
	{
		echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
	}
	else
	{
		echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\">\n";
	}

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "              </td>\n";
	echo "           </tr>\n";
	echo "</form>\n";
	
	//Over/Under Split Percentage
	$osplitperc=0;
	if ($viewarray['renov'] == 1)
	{
		$osplitperc=0;
	}
	else
	{
		if (isset($fctramt) && $fctramt!=0)
		{
			$osplitperc=round(($fadjctramt/$fctramt)*100);
		}
		else
		{
			$osplitperc=0;
		}
	}
	echo "           <tr>\n";
	echo "              <td colspan=\"2\" class=\"wh\" align=\"right\"><b>Overage/<font color=\"red\">Underage</font>:</b></td>\n";
	//echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($osplitperc < 0)
	{
		echo "              <td class=\"wh\" align=\"right\"><font color=\"red\">".$osplitperc."%</font></td>\n";
	}
	else
	{
		echo "              <td class=\"wh\" align=\"right\">".$osplitperc."%</td>\n";
	}
	
	//echo $osplitperc;
	//echo "		</td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";

	if ($adjctramt < 0)
	{
		echo "              <td class=\"wh\" align=\"right\"><font color=\"red\">".$fadjctramt."</font></td>\n";
	}
	else
	{
		echo "              <td class=\"wh\" align=\"right\">".$fadjctramt."</td>\n";
	}

	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td colspan=\"2\" class=\"wh\" align=\"right\"><b>Commission Adjust:</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";

	if ($tbullets > 0)
	{
		echo "$tbullets Bullets";
	}

	echo "                                        </td>\n";

	if ($_SESSION['elev'] >= 4)
	{
		echo "<form  method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "<input type=\"hidden\" name=\"comm\" value=\"".$ftrcomm."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "			 <input type=\"hidden\" name=\"esttype\" value=\"E\">\n";

		if ($viewarray['applyov']==1)
		{
			echo "              <td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" name=\"comadj\" value=\"".$fadjcomm."\" size=\"7\"></td>\n";
		}
		else
		{
			echo "              <td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" name=\"comadj\" value=\"".$foucomm."\" size=\"7\"></td>\n";
		}

		echo "              <td class=\"wh\" align=\"center\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($rowI[11]!=0 || $rowI[12]!=0)
		{
			if ($viewarray['applyov']==1)
			{
				echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteou\">\n";
				echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
				echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Delete\" DISABLED>\n";
			}
			else
			{
				echo "                  <input type=\"hidden\" name=\"call\" value=\"applyou\">\n";
				echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
				echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
			}
		}
		else
		{
			if ($viewarray['applyov']==1)
			{
				echo "                  <input type=\"hidden\" name=\"call\" value=\"deleteou\">\n";
				echo "                  <input type=\"hidden\" name=\"applyov\" value=\"0\">\n";
				echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Delete\">\n";
			}
			else
			{
				echo "                  <input type=\"hidden\" name=\"call\" value=\"applyou\">\n";
				echo "                  <input type=\"hidden\" name=\"applyov\" value=\"1\">\n";
				echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Apply\">\n";
			}
		}

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "                                        </td>\n";
		echo "</form>\n";
	}
	else
	{
		echo "              <td class=\"wh\" align=\"right\">".$fadjcomm."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
	}

	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td colspan=\"2\" class=\"wh\" align=\"right\"><b>Total Commission:</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">".$ftadjcomm."</td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";

	if ($rowC[2]==1)
	{
		echo "            <tr>\n";
		echo "               <td colspan=\"2\" class=\"wh\" align=\"right\"><b>Tax (".$taxrate[1]."):</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td align=\"right\" class=\"wh\">".$frtax."</td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td colspan=\"2\" class=\"wh\" align=\"right\"><b>Total:</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td align=\"right\" class=\"wh\">".$fgrtcost."</td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "               <td class=\"wh\" align=\"right\"></td>\n";
		echo "            </tr>\n";
	}

	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table class=\"transnb\" cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";
	echo "            <tr>\n";
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"view_addnew\">\n";
	echo "<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "               <td align=\"left\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	if ($viewarray['status'] >= 2)
	{
		echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Edit Items\" DISABLED>\n";
	}
	else
	{
		echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Edit Items\">\n";
	}

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "               </td>\n";
	echo "</form>\n";
	echo "            </tr>\n";

	echo "      	<form method=\"post\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	echo "			<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "         <input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
	echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowI[10]."\">\n";
	//echo "         <input type=\"hidden\" name=\"custid\" value=\"".$viewarray['custid']."\">\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"OneSheet\"><br>\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			</form>\n";

	if ($_SESSION['elev'] >= 1)
	{
		echo "            <tr>\n";
		echo "               <td align=\"center\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "						<hr width=\"90%\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>\n";
		echo "            </tr>\n";

		echo "				<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		echo "					<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "					<input type=\"hidden\" name=\"call\" value=\"delete_est1\">\n";
		echo "					<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
		echo "					<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "         			<input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";
		//echo "        			 	<input type=\"hidden\" name=\"custid\" value=\"".$viewarray['custid']."\">\n";
		echo "            <tr>\n";
		echo "					<td align=\"left\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($rowI[11]!=0 || $rowI[12]!=0)
		{
			echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete Est\" DISABLED>\n";
		}
		else
		{
			echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete Est\">\n";
		}

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>\n";
		echo "            </tr>\n";
		echo "				</form>\n";
	}

	echo "            <tr>\n";
	echo "               <td align=\"center\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "						<hr width=\"90%\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "					</td>\n";
	echo "            </tr>\n";

	echo "                        <form method=\"post\">\n";
	echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "							 <input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "							 <input type=\"hidden\" name=\"overunder\" value=\"".$fadjctramt."\">\n";
	//echo "                           <input type=\"hidden\" name=\"custid\" value=\"".$viewarray['custid']."\">\n";
	echo "                           <input type=\"hidden\" name=\"call\" value=\"create_job\">\n";

	if ($rowC[2]==1)
	{
		echo "                           <input type=\"hidden\" name=\"salestax\" value=\"".$frtax."\">\n";
	}

	echo "            <tr>\n";
	echo "               <td align=\"center\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	if ($rowI[11]!=0 || $rowI[12]!=0)
	{
		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Create Contract\" DISABLED>\n";
	}
	else
	{
		echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Create Contract\">\n";
	}

	if ($_SESSION['subq']=="print")
	{
		echo "</div class=\"noPrint\">\n";
	}

	echo "					</td>\n";
	echo "            </tr>\n";
	echo "                        </form>\n";

	echo "            <tr>\n";
	echo "               <td align=\"center\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "						<hr width=\"90%\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "					</td>\n";
	echo "            </tr>\n";

	echo "            <tr>\n";
	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "<input type=\"hidden\" name=\"subq\" value=\"print\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "               <td align=\"left\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Print View\">\n";

	if ($_SESSION['subq']=="print")
	{
		echo "</div>\n";
	}

	echo "               </td>\n";
	echo "</form>\n";
	echo "            </tr>\n";

	$viewarray['tcomm']		=$tadjcomm;
	$viewarray['tretail']	=$adjbookamt;
	$viewarray['tcontract']	=$ctramt;
	$viewarray['acctotal']	=$trccost;
	$viewarray['discount']	=$vdiscnt;
	$viewarray['royrel']		=0;
	$viewarray['custallow']	=0;
	
	if ($viewarray['jobid']!='0' || $viewarray['njobid']!='0')
	{
		$viewarray['allowdel']	=1;
	}
	else
	{
		$viewarray['allowdel']	=0;
	}

	if ($_SESSION['elev'] >= 6||$_SESSION['clev'] >= 6||$_SESSION['jlev'] >= 6)
	{
		echo "            <tr>\n";
		echo "               <td align=\"center\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "						<hr width=\"90%\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>\n";
		echo "            </tr>\n";

		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
		echo "<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		//echo "<input type=\"hidden\" name=\"tcomm\" value=\"".$tadjcomm."\">\n";
		//echo "<input type=\"hidden\" name=\"tretail\" value=\"".$adjbookamt."\">\n";
		//echo "<input type=\"hidden\" name=\"tcontract\" value=\"".$ctramt."\">\n";
		//echo "<input type=\"hidden\" name=\"discount\" value=\"".$vdiscnt."\">\n";
		echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
		//echo "<input type=\"hidden\" name=\"acctotal\" value=\"".$trccost."\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		if ($rowC[9]==1)
		{
			echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Cost\">\n";
		}

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "               </td>\n";
		echo "</form>\n";
		echo "            </tr>\n";
	}

	if (isset($rowpreC['filestoreaccess']) && $rowpreC['filestoreaccess'] >= 5)
	{
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "					<form method=\"POST\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
		echo "						<input type=\"hidden\" name=\"cid\" value=\"".$viewarray['cid']."\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Files\"><br>\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}

	echo "         </table>\n";
	//echo "			<input type=\"hidden\" name=\"comments\" value=\"".$rowpreA[12]."\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";

	$_SESSION['viewarray']=$viewarray;
	//echo "<pre>";
	//print_r($_SESSION['estbidretail']);
	//echo "</pre>";
}

function bid_item_test($oid,$est,$ctramt,$vgp)
{
	$mbid_ar=array();
	
	if ($est!=0 and $ctramt > 0)
	{		
		$qryA = "SELECT officeid,estid,bidid,bidaccid FROM est_bids WHERE officeid=".$oid." AND estid=".$est.";";
		$resA = mssql_query($qryA);
		$nrowA= mssql_num_rows($resA);
		
		if ($nrowA > 0)
		{
			while($rowA = mssql_fetch_array($resA))
			{
				$qryB = "SELECT id FROM bid_breakout WHERE officeid=".$oid." AND estid=".$est." AND rdbid=".$rowA['bidaccid'].";";
				$resB = mssql_query($qryB);
				$nrowB= mssql_num_rows($resB);
				
				if ($nrowA==0)
				{
					$mbid_ar[$rowA['bidaccid']]=array($rowA['bidaccid']);
				}
			}
		}
	}

	return $mbid_ar;
}

function bid_item_cost_test($oid,$est)
{
	$mbid_ar=array();
	
	if ($est!=0 and isset($_SESSION['estbidretail']) and is_array($_SESSION['estbidretail']))
	{
		//if ($_SESSION['securityid']==26)
		//{
		$qryA = "SELECT officeid,vgp FROM offices WHERE officeid=".$oid.";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		if (isset($rowA['vgp']) and $rowA['vgp'] > 0)
		{
			$vgp=($rowA['vgp'] * .01);
		}
		else
		{
			$vgp=.3;
		}
		
		$no_ret=0; // Zero Retail
		$no_cst=0; // Zero Cost
		$th_cst=0; // Cost Threshold
		foreach ($_SESSION['estbidretail'] as $n => $v)
		{
			if ($v[0]!=0)
			{
				if ($v[1]==0)
				{
					$no_cst++;
					//$mbid_ar[]=array(0,1,0);
					//echo ($v[0] - ($v[0] * $vgp)).':'.$v[1].'<br>';
				}
				elseif ($v[1] > ($v[0] - ($v[0] * $vgp)))
				{
					$th_cst++;
					//echo ($v[0] - ($v[0] * $vgp)).':'.$v[1].'<br>';
					//$mbid_ar[]=array(0,0,1);
				}
			}
			else
			{
				$no_ret++;
				//$mbid_ar[]=array(1,0,0);
			}
		}
		
		if ($no_ret > 0 or $no_cst > 0 or $th_cst > 0)
		{
			$mbid_ar=array('no_ret'=>$no_ret,'no_cst'=>$no_cst,'th_cst'=>$th_cst);
		}
		//display_array($_SESSION['estbidretail']);
		//echo '<br>';
		//display_array($mbid_ar);
		//}
	}
	
	//display_array($mbid_ar);
	//echo '<br>-----<br>';
	return $mbid_ar;
}

function viewest_retail_NEW($estid=null)
{
	if ($_SESSION['securityid']==26) {
		error_reporting(E_ALL);
		ini_set('display_errors','On');
		echo __FUNCTION__.'<br>';
	}
	
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	unset($viewarray);
	unset($_SESSION['viewarray']);
	unset($_SESSION['estbidretail']);
	
	$dbg		=0;
	$tsecid		=1952;
	$MAS		=$_SESSION['pb_code'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	
	$_SESSION['aid']=aidbuilder($_SESSION['jlev'],"j");
	$acclist	=explode(",",$_SESSION['aid']);
	$jobid		=0;
	
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}

	$qrypreA  = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,";
	$qrypreA .= "phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,comadj,";
	$qrypreA .= "sidm,buladj,applyov,applybu,refto,apft,added,updated,updateby,comm,renov,esttype,";
	$qrypreA .= "ccid,com_rate,over_split,added ";
	$qrypreA .= "FROM est WHERE officeid=".(int) $_SESSION['officeid']." AND estid=".(int) $estid.";";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_row($respreA);
	
	if (isset($rowpreA[35]) && $rowpreA[35]!=0) {
		$qrypreAa = "SELECT * FROM cinfo WHERE officeid=".$_SESSION['officeid']." AND cid=".$rowpreA[35].";";
		$respreAa = mssql_query($qrypreAa);
		$rowpreAa = mssql_fetch_array($respreAa);
	}
	else {
		$qrypreAa = "SELECT * FROM cinfo WHERE officeid=".$_SESSION['officeid']." AND cid=".$rowpreA[16].";";
		$respreAa = mssql_query($qrypreAa);
		$rowpreAa = mssql_fetch_array($respreAa);
	}
	
	$qrypreAb = "SELECT count(estid) as qecnt FROM jest..est WHERE officeid=".$_SESSION['officeid']." AND ccid=".$rowpreAa['cid'].";";
	$respreAb = mssql_query($qrypreAb);
	$rowpreAb = mssql_fetch_array($respreAb);

	$qrypreB = "SELECT securityid,sidm,com_rate FROM security WHERE officeid=".$_SESSION['officeid']." AND securityid=".$rowpreA[17].";";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_row($respreB);
	
	$qrypreC = "SELECT securityid,filestoreaccess,modcomm FROM security WHERE securityid=".$securityid.";";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_array($respreC);
	
	$_SESSION['modcomm']=$rowpreC['modcomm'];

	if (!in_array($_SESSION['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>You do not have appropriate Access Rights to view this Resource</b>";
		exit;
	}

	$viewarray=array(
	'estid'=>	$rowpreA[0],
	'jobid'=>	$rowpreAa['jobid'],
	'njobid'=>	$rowpreAa['njobid'],
	'ps1'=>		$rowpreA[1],
	'ps2'=>		$rowpreA[2],
	'spa1'=>	$rowpreA[3],
	'spa2'=>	$rowpreA[4],
	'spa3'=>	$rowpreA[5],
	'tzone'=>	$rowpreA[6],
	'camt'=>	$rowpreA[7],
	'comt'=>	0,
	'com_rate'=>$rowpreA[36],
	'over_split'=>$rowpreA[37],
	'cfname'=>	$rowpreAa['cfname'],
	'clname'=>	$rowpreAa['clname'],
	'phone'=>	$rowpreA[10],
	'status'=>	$rowpreA[11],
	'ps5'=>		$rowpreA[13],
	'ps6'=>		$rowpreA[14],
	'ps7'=>		$rowpreA[15],
	'cid'=>		$rowpreA[35],
	'estsecid'=>$rowpreA[17],
	'deck'=>	$rowpreA[18],
	'erun'=>	$rowpreA[19],
	'prun'=>	$rowpreA[20],
	//'jobid'=>	$rowpreA[21],
	'comadj'=>	$rowpreA[22],
	'dbocomm'=>	$rowpreA[32],
	'sidm'=>	$rowpreA[23],
	'buladj'=>	$rowpreA[24],
	'applyov'=>	$rowpreA[25],
	'applybu'=>	$rowpreA[26],
	'refto'=>	$rowpreA[27],
	'ps1a'=>	$rowpreA[28],
	'jadd'=>	0,
	'mjadd'=>	0,
	'custallow'=>0,
	'renov'=>	$rowpreA[33],
	'esttype'=>	$rowpreA[34],
	'discount'=>0,
	'royrel'=>	0,
	'allowdel'=>0,
	'tcomm'=>	0,
	//'contdate'=>strtotime($rowpreA[38]),
	'added'=>	strtotime($rowpreA[38]),
	'updated'=>	strtotime($rowpreA[30]),
	'cdate'=>	strtotime($rowpreA[38])
	);

	$qrypreD = "SELECT * FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);

	$r_estdata = $rowpreD['estdata'];

	$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft,encost,enest,com_rate,newcommdate,vgp,otype_code FROM offices WHERE officeid=".$_SESSION['officeid'].";";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[10]!=1)
	{
		echo "<br><font color=\"red\"><b>ERROR!</b></font><br><b>Estimating has been disabled in ".$rowC[1]."</b>";
		exit;
	}

	if ($rowC[8]=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryD = "SELECT securityid,fname,lname,com_rate,over_split,newcommdate FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);
	
	$viewarray['ncommdate']=strtotime($rowD[5]);
	
	// Set Commission Perc
	if ($viewarray['applyov']==0)
	{
		if ($rowD[3]==0)
		{
			$com_rate=$rowC[11];
		}
		else
		{
			$com_rate=$rowD[3];
		}
	}
	else
	{
		$com_rate=$viewarray['com_rate'];
	}
	
	// Set O/U Commission Perc
	if ($viewarray['applyov']==0)
	{
		if ($rowD[4]==0)
		{
			$over_split=$rowC[7];
		}
		else
		{
			$over_split=$rowD[4];
			
		}
	}
	else
	{
		$over_split=$viewarray['over_split'];
	}
	
	$commarray=array(
						'jobid'=>$viewarray['jobid'],
						'njobid'=>$viewarray['njobid'],
						'applyov'=>$viewarray['applyov'],
						'com_rate'=>$com_rate,
						'over_split'=>$over_split,
						'estsecid'=>$viewarray['estsecid'],
						'sysdate'=>strtotime($rowpreA[29]),
						'renov'=>$viewarray['renov']
					);
	
	// Set Contract Date or System Date
	if (isset($viewarray['contdate']))
	{
		$commarray['contdate']=$viewarray['contdate'];
	}

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$viewarray['cid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_row($resL);

	if ($rowpreA[31]!=0)
	{
		$qryM = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowpreA[31]."';";
		$resM = mssql_query($qryM);
		$rowM = mssql_fetch_array($resM);

		$lupdatestr=$rowM['fname']." ".$rowM['lname'];
	}
	else
	{
		$lupdatestr="";
	}

	$qryN = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$resN = mssql_query($qryN);

	$qryP = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$resP = mssql_query($qryP);
	
	$qryO = "SELECT * FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND estid=".$viewarray['estid'].";";
	$resO = mssql_query($qryO);
	$nrowO= mssql_num_rows($resO);
	
	if ($nrowO > 0)
	{
		$commarray['commschedcnt']=$nrowO;
		
		while($rowO = mssql_fetch_array($resO))
		{
			if ($rowO['type']==1) // Base
			{
				$ctype='base';
			}
			elseif ($rowO['type']==2) // Manual Adjust
			{
				$ctype='man';
			}
			elseif ($rowO['type']==3)
			{
				$ctype='over';
			}
			elseif ($rowO['type']==3)
			{
				$ctype='build';
			}
			
			$commarray['commsched'][$ctype]=array(
											'csid'=>$rowO['csid'],
											'oid'=>$rowO['oid'],
											'estid'=>$rowO['estid'],
											'type'=>$rowO['type'],
											'rate'=>$rowO['rate'],
											'amt'=>$rowO['amt'],
											'secid'=>$rowO['secid'],
											'cbtype'=>$rowO['cbtype']
										);
		}
	}
	
	$tbullets=0;
	
	if ($viewarray['renov']==1) {
		$qryQ  = "select cmid,rwdrate,ctgry,ctype from jest..CommissionBuilder where oid=".$_SESSION['officeid']." and active=1 and ctgry=1 and renov=1;";
	}
	else {
		$qryQ  = "select cmid,rwdrate,ctgry,ctype from jest..CommissionBuilder where oid=".$_SESSION['officeid']." and active=1 and ctgry=1 and renov=0;";
	}
	
	$resQ = mssql_query($qryQ);
	$rowQ = mssql_fetch_array($resQ);
    $nrowQ= mssql_num_rows($resQ);
	
	$qryQa  = "select cmid,rwdrate,ctgry,ctype from jest..CommissionBuilder where oid=".$_SESSION['officeid']." and active=1 and secid=".$viewarray['estsecid']." and ctgry=1;";
	$resQa = mssql_query($qryQa);
	$rowQa = mssql_fetch_array($resQa);
    $nrowQa= mssql_num_rows($resQa);
	
	if ($nrowQa > 0) {
		$viewarray['com_base_type']=$rowQa['ctype'];
		
		if ($rowQa['ctype']==1) {
			$viewarray['com_base_rate']=0;
		}
		else {
			$viewarray['com_base_rate']=$rowQa['rwdrate'];
		}
	}
	else {
		$viewarray['com_base_type']=$rowQ['ctype'];
		
		if ($rowQ['ctype']==1) {
			$viewarray['com_base_rate']=0;
		}
		else {
			$viewarray['com_base_rate']=$rowQ['rwdrate'];
		}
	}
	
	//echo $viewarray['com_base_rate'].'<br>';

	// Sets Tax Rate
	if ($rowC[2]==1) {
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}
	
	$_SESSION['viewarray']=$viewarray;
	$poolcomm_adj	=detect_package($r_estdata);
	$set_deck   	=deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    	=round($set_deck[0]);
	$set_ia     	=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals   	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$estidret   	=$rowpreA[0];
	$vdiscnt    	=$viewarray['discount'];
	$bpset			=select_base_pool();
	$pbaseprice 	=$bpset[3];

	if ($poolcomm_adj >= 1) {
		$bcomm      =0;
	}
	else {
		if (isset($viewarray['com_base_type']) && $viewarray['com_base_type']==1) {
			$bcomm      =$bpset[4];
		}
		else {
			if (isset($viewarray['com_base_rate']) && $viewarray['com_base_rate']!=0) {
				$bcomm      =$bpset[3] * $viewarray['com_base_rate'];
			}
			else {
				$bcomm      =0;
			}
		}
	}

	$uid			=md5(session_id().time().$rowI[10]).".".$_SESSION['securityid'];

	if (!empty($rowpreA[29])) {
		$atime=date("m/d/Y", strtotime($rowpreA[29]));
	}
	else {
		$atime="";
	}

	if (!empty($rowpreA[30])) {
		$utime=date("m/d/Y", strtotime($rowpreA[30]));
	}
	else {
		$utime="";
	}

	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	$fbcomm		=number_format($bcomm, 2, '.', '');
	$ctramt		=$viewarray['camt'];
	$fctramt	=number_format($ctramt, 2, '.', '');
	$commarray['fctramt']=$fctramt;
	
	$tsecid=26;
	$dbg=0;
	if (isset($dbg) && $dbg==1 && $_SESSION['securityid']==$tsecid) {
		echo "<pre>";
		//print_r($commarray);
		echo 'MOD:'.$_SESSION['modcomm'].'<br>';
		echo "</pre>";
	}
	
	$col_struct=array(140,500,35,35,60,60,35);

	echo "<script type=\"text/javascript\" src=\"js/jquery_estimate_func.js\"></script>\n";
	echo "<div id=\"extEstInfo\"></div>\n";
	echo "<table class=\"transnb\" border=0 cellspacing=0 cellpadding=0 width=\"".(array_sum($col_struct) + 85)."px\">\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	
	echo "			<form id=\"updateest\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "			<input type=\"hidden\" name=\"estid\" id=\"qestid\" value=\"".$estidret."\">\n";
	echo "			<input type=\"hidden\" name=\"securityid\" id=\"sid1\" value=\"".$viewarray['estsecid']."\">\n";
	echo "			<input type=\"hidden\" name=\"custid\" value=\"".$rowI[0]."\">\n";
	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$rowI[10]."\">\n";
	echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "			<input type=\"hidden\" name=\"discount\" value=\"".$vdiscnt."\">\n";
	echo "			<input type=\"hidden\" name=\"contractamt\" value=\"".$fctramt."\">\n";
	echo "			<input type=\"hidden\" name=\"spa1\" value=\"".$viewarray['spa1']."\">\n";
	echo "			<input type=\"hidden\" name=\"spa2\" value=\"".$viewarray['spa2']."\">\n";
	echo "			<input type=\"hidden\" name=\"spa3\" value=\"".$viewarray['spa3']."\">\n";
	echo "			<input type=\"hidden\" name=\"status\" value=\"".$viewarray['status']."\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"update\">\n";
	echo "			<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "			<input type=\"hidden\" name=\"qecnt\" id=\"ecnt\" value=\"1\">\n";
	
	echo "			<table class=\"transnb\" border=0>\n";
	echo "				<tr>\n";
	echo "				<td valign=\"top\" align=\"center\" width=\"".array_sum($col_struct)."px\">\n";
	echo "					<table width=\"100%\" class=\"outer\" border=0>\n";
	echo "						<tr>\n";

	if ($viewarray['status']==1)
	{
		echo "							<td class=\"gray\" align=\"left\"><b>Retail Addendum Estimate ".$estidret." on Job: <font color=\"red\">".$viewarray['jobid']."</font> for ".$rowC[1]."</b></td>\n";
	}
	else
	{
		echo "							<td class=\"gray\" align=\"left\"><b>Retail Estimate</b></td>\n";
	}
	
	echo "							<td class=\"gray\" align=\"right\"><b>\n";
	?>
		
		<script type="text/javascript">
            setLocalTime();
        </script>
		
	<?php
	echo "							</b></td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\" align=\"left\" width=\"".array_sum($col_struct)."px\">\n";
	echo "					<table class=\"transnb\" align=\"center\" cellspacing=0 cellpadding=0 width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td valign=\"top\" align=\"left\" width=\"".(array_sum($col_struct) * .25)."px\">\n";
	echo "								<table class=\"outer\" width=\"100%\" height=\"170\"\n";
	echo "									<tr>\n";
	echo "				               			<td class=\"gray\" valign=\"top\">\n";

	// Customer Display Info
	cinfo_display($viewarray['cid'],$rowC[2]);
	// End Customer Display
	
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
	echo "							</td>\n";
	echo "							<td valign=\"top\" align=\"right\" width=\"".(array_sum($col_struct) * .45)."px\">\n";
	echo "								<table class=\"outer\" width=\"100%\" height=\"170\">\n";
	echo "									<tr>\n";
	echo "										<td class=\"gray\" valign=\"top\">\n";
	
	// Set Pool Detail Display
	pool_detail_display($viewarray['estid']);
	// End Pool Detail Display

	echo "             				  			</td>\n";
	echo "            				   		</tr>\n";
	echo "           			    	</table>\n";
	echo "           			    </td>\n";
	echo "              			 <td valign=\"top\" align=\"right\" width=\"".(array_sum($col_struct) * .30)."px\">\n";
	echo "              			    <table class=\"outer\" width=\"100%\" height=\"170\">\n";
	echo "                 				    <tr>\n";
	echo "               						<td class=\"gray\" valign=\"top\">\n";
	echo "                  						<table width=\"100%\" border=0>\n";
	echo "                     							<tr>\n";
	echo "													<td align=\"right\"><b>Estimate</b></td>\n";
	echo "													<td align=\"left\">".$viewarray['estid']."</td>\n";
	echo "                     							</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Sales Rep</b></td>\n";
	echo "													<td align=\"left\">\n";

	if ($_SESSION['elev'] >= 4 and $viewarray['jobid']=='0')
	{
		echo "                                               <select id=\"sid2\" name=\"securityid\">\n";
		
		while($rowN = mssql_fetch_row($resN))
		{
			if (in_array($rowN[0],$acclist))
			{
				$secl=explode(",",$rowN[3]);
				if ($secl[6]==0)
				{
					$ostyle="fontred";
				}
				else
				{
					$ostyle="fontblack";
				}

				if ($viewarray['estsecid']==$rowN[0])
				{
					echo "                                               	<option value=\"".$rowN[0]."\" class=\"".$ostyle."\" SELECTED>".$rowN[1]." ".$rowN[2]."</option>\n";
				}
				else
				{
					echo "                                               	<option value=\"".$rowN[0]."\" class=\"".$ostyle."\">".$rowN[1]." ".$rowN[2]."</option>\n";
				}
			}
		}
		echo "                                               </select>\n";
	}
	else
	{
		echo $rowD[1]." ".$rowD[2];
		echo "												<input type=\"hidden\" name=\"securityid\" value=\"".$viewarray['estsecid']."\">\n";
	}

	echo "                                  			     </td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Sales Manager</b></td>\n";
	echo "													<td align=\"left\">\n";
	
	if ($viewarray['sidm']!=0)
	{
		echo $rowL[1]." ".$rowL[2];
	}
	else
	{
		echo 'None Assigned';
	}
	
	echo "														<input type=\"hidden\" name=\"sidm\" value=\"".$viewarray['sidm']."\">\n";
	echo "													</td>\n";
	echo "                    			 				</tr>\n";
	echo "                    			 				<tr>\n";
	echo "													<td align=\"center\" colspan=\"2\"><hr width=\"90%\"><input type=\"hidden\" name=\"contdate\"></td>\n";
	echo "                    			 				</tr>\n";
	echo "                    			 				<tr>\n";
	echo "													<td align=\"right\"><b>Added</b></td>\n";
	echo "													<td align=\"left\">".$atime."</td>\n";
	echo "                    			 				</tr>\n";
	echo "                    			 				<tr>\n";	
	echo "													<td align=\"right\"><b>Updated</b></td>\n";
	echo "													<td align=\"left\">".$utime."</td>\n";
	echo "                    			 				</tr>\n";
	echo "                    			 				<tr>\n";
	echo "													<td align=\"right\"><b>Update by</b></td>\n";
	echo "													<td align=\"left\">".$lupdatestr."</td>\n";
	echo "                     							</tr>\n";
	echo "                  						</table>\n";
	echo "               						</td>\n";
	echo "               					</tr>\n";
	echo "               				</table>\n";
	echo "               			</td>\n";
	echo "               		</tr>\n";
	echo "               	</table>\n";
	echo "      		</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
	
	echo "		</form>\n";
	echo "	</td>\n";
	echo "				<td valign=\"bottom\" width=\"70px\" align=\"left\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "					<table class=\"transnb\" border=0>\n";
	echo "            			<tr>\n";
	echo "               			<td align=\"left\">\n";

	if ($viewarray['status'] >= 2)
	{
		echo "						<input class=\"LockedEst buttondkgrypnl60\" type=\"submit\" value=\"Update\">\n";
	}
	else
	{
		echo "						<input class=\"buttondkgrypnl60\" id=\"SubmitEstUpdate\" type=\"submit\" value=\"Update\" onClick=\"return SRChangeAlert('sid1','sid2','ecnt');\">\n";
	}

	echo "							</td>\n";
	echo "   					</tr>\n";
	echo "					</table>\n";
	echo "					</div>\n";
	echo "				</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "	<td valign=\"top\" align=\"left\">\n";
	echo "		<table class=\"transnb\">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"center\" width=\"100%\">\n";
	echo "					<table class=\"outer\" background=\"white\" bordercolor=\"gray\" border=1>\n";
	echo "						<tr>\n";
	echo "              			<td class=\"wh\" align=\"left\" width=\"".$col_struct[0]."px\"><b>Category</b></td>\n";
	echo "              			<td class=\"wh\" align=\"left\" width=\"".$col_struct[1]."px\"><b>Item</b></td>\n";
	echo "             		 		<td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."px\"><b>Quan</b></td>\n";
	echo "              			<td class=\"wh\" align=\"center\" width=\"".$col_struct[3]."px\"><b>Units</b></td>\n";
	echo "              			<td class=\"wh\" align=\"center\" width=\"".$col_struct[4]."px\"><b>Retail</b></td>\n";
	echo "              			<td class=\"wh\" align=\"center\" width=\"".$col_struct[5]."px\"><b>Comm</b></td>\n";
	echo "              			<td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."px\">\n";
	echo "								<div class=\"noPrint\">\n";

	/*
	if ($viewarray['status'] < 2)
	{
		echo "						<input class=\"transnb\" type=\"image\" src=\"images/cross.png\" value=\"Delete\" title=\"Delete Checked Items\">";
	}
	*/

	echo "								</div>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	
	if ($viewarray['renov']==0)
	{
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"left\">Base</td>\n";
		echo "					<td class=\"wh\" align=\"left\">Basic Pool</td>\n";
		echo "					<td class=\"wh\" align=\"center\">".$bpset[5]."</td>\n";
		echo "					<td class=\"wh\" align=\"center\">".$bpset[6]."</td>\n";
		echo "					<td class=\"wh\" align=\"right\">".$fpbaseprice."</td>\n";
		echo "					<td class=\"wh\" align=\"right\">".$fbcomm."</td>\n";
		echo "					<td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				</tr>\n";
	}

	calcbyacc($r_estdata,0);

	// Totals Table Calcs
	$bccost  =$bctotal;
	$rccost  =$rctotal;
	$cccost  =$cctotal;
	$bmcost  =$bmtotal;
	$rmcost  =$rmtotal;
	$trccost =$rccost+$rmcost;
	$cmcost  =$cmtotal;
	$tbcost  =$bccost+$bmcost;
	$trcost  =$pbaseprice+$trccost+$tbid;
	$tccost  =$cccost+$cmcost;
	$trcomm  =$bcomm+$tccost;
	$prof    =($trcost-$tbcost)-$trcomm;
	
	if ($prof!=0)
	{
		$perprof =$prof/$trcost;
	}
	else
	{
		$perprof =0;
	}

	if ($rowC[2]==1)
	{
		$rtax    =$ctramt*$taxrate[1];
		$grtcost =$ctramt+$rtax;
		$frtax   =number_format($rtax, 2, '.', '');
		$fgrtcost=number_format($grtcost, 2, '.', '');
	}

	$fbccost		=number_format($bccost, 2, '.', '');
	$fbmcost		=number_format($bmcost, 2, '.', '');
	$fcccost		=number_format($cccost, 2, '.', '');
	$frccost		=number_format($rccost, 2, '.', '');
	$frmcost		=number_format($rmcost, 2, '.', '');
	$fcmcost		=number_format($cmcost, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftrcost		=number_format($trcost, 2, '.', '');
	$ftccost		=number_format($tccost, 2, '.', '');
	$ftrcomm		=number_format($trcomm, 2, '.', '');
	$commarray['fpbcomm']	=$ftrcomm;

	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Price per Book</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><div id=\"ppbook\">".$ftrcost."</div></td>\n";
	echo "              <td class=\"wh\" align=\"right\">".$ftrcomm."</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";

	//echo 'C:'.$rowC[14];

	if ($rowC[14] == 2) // Adjust PpB Enable for Franchises / Blocked for P&A
	{
		$adjupdate=6;
	}
	else
	{
		$adjupdate=7;
	}
	
	if ((isset($_SESSION['modcomm']) and $_SESSION['modcomm'] >= 1) and (isset($rowpreAa['mas_prep']) and $rowpreAa['mas_prep'] < 1) and $viewarray['jobid']=='0')
	{
		echo "				<span class=\"JMStooltip noPrint\" id=\"OpenPBAdjustDialog\" title=\"Adjust Price per Book\"><a href=\"#\"><img src=\"../images/calculator_edit.png\"></a></span>\n";
	}
	else
	{
		echo "				<img src=\"images/pixel.gif\">\n";
	}
	
	echo "				</td>\n";
	echo "           </tr>\n";
	
	calc_adjusts_EXIST($viewarray['com_base_rate']);
	
	if (!isset($rowC[5])||!is_numeric($rowC[5]))
	{
		$bullet_rate=0;
	}
	else
	{
		$bullet_rate=$rowC[5];
	}

	$adjbookamt	=$trcost+$discount;
	$fadjbookamt=number_format($adjbookamt, 2, '.', '');
	$commarray['fadjbookamt']=$fadjbookamt;

	if ($viewarray['renov']==1)
	{
		$adjctramt	=0;
	}
	else
	{
		$adjctramt	=$ctramt-$adjbookamt;
	}
	
	$fadjctramt	=number_format($adjctramt, 2, '.', '');
	
	$adjcomm	=0;

	$ou_out		=calc_ou($adjctramt,$adjcomm,$tbullets,$rowC[6],$viewarray['applyov'],$viewarray['comadj'],$bullet_rate,$rowC[7]);

	$foucomm	=number_format($ou_out[0], 2, '.', '');
	$fadjcomm	=number_format($ou_out[1], 2, '.', '');
	$commarray['fadjcomm']=$fadjcomm;

	if ($viewarray['applyov']==1)
	{
		$tadjcomm	=$trcomm+$fadjcomm;
	}
	else
	{
		$tadjcomm	=$trcomm;
	}

	// Set commission for global
	$viewarray['comt']	=$tadjcomm;
	$ftadjcomm			=number_format($tadjcomm, 2, '.', '');

	//echo "			<table class=\"outer\" background=\"white\" bordercolor=\"gray\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."px\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[1]."px\"><b>Adjusted Book Price</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[2]."px\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[3]."px\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."px\"><div id=\"apbook\">".$fadjbookamt."</div></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."px\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."px\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Retail Contract Price</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" valign=\"bottom\">\n";
	
	if ($viewarray['status'] <= 1)
	{
		echo "			<form id=\"AdjRetailPrice\" method=\"post\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"update_contract_amt\">\n";
		echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
		echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "			<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "			<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
		echo "			<input class=\"transnbtextright formatCurrency\" type=\"text\" id=\"c_amt\" name=\"c_amt\" size=\"6\" maxlength=\"10\" value=\"".$fctramt."\">\n";
		echo "			</form>\n";
	}
	else
	{
		echo $fctramt;
	}
	
	echo "              </td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "					<div class=\"noPrint\">\n";
	
	if ($viewarray['status'] <= 1)
	{
		echo "                  <img class=\"transnb_button setpointer\" src=\"images/save.gif\" id=\"SubmitAdjRetailPrice\" title=\"Save Retail Amount\">\n";
	}

	echo "					</div>\n";
	echo "              </td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "				<td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."px\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[1]."px\"><b>Over/<font color=\"red\">Under</font> Book</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."px\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[3]."px\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."px\">\n";
	
	if (($commarray['fctramt'] - $commarray['fadjbookamt']) < 0)
	{
		echo "					<font color=\"red\"><div id=\"oubook\">".number_format(($commarray['fctramt'] - $commarray['fadjbookamt']), 2, '.', '')."</div></font>\n";
	}
	else
	{
		echo "					<div id=\"oubook\">".number_format(($commarray['fctramt'] - $commarray['fadjbookamt']), 2, '.', '')."</div>\n";
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	
	$viewarray['ou']=number_format(($commarray['fctramt'] - $commarray['fadjbookamt']), 2, '.', '');
	
	if ($rowC[2]==1)
	{
		echo "			<tr>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				<td class=\"wh\" align=\"right\"><b>Tax (".$taxrate[1]."):</b></td>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				<td class=\"wh\" align=\"right\">".$frtax."</td>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				<td class=\"wh\" align=\"right\"><b>Total</b></td>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				<td class=\"wh\" align=\"right\">".$fgrtcost."</td>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "			</tr>\n";
		$commarray['frtax']=$frtax;
	}

	$commarray['tbullets']			=$tbullets;
	$commarray['estidret']			=$estidret;
	$commarray['sidm']				=$viewarray['sidm'];
	$commarray['taxtrig']			=$rowC[2];
	$viewarray['missing_bid_items']	=array();
	
	if (isset($fctramt) and $fctramt > 0)
	{
		if ($nrowQ > 0) {
			if ($viewarray['jobid']!='0') {
				if ($_REQUEST['call']='est') {
					//$tadjcomm=retail_csched_ro($commarray,$col_struct);
					if (isset($_SESSION['modcomm']) and $_SESSION['modcomm'] >= 1) {
						//echo 'T';
						$tadjcomm=retail_csched_ro($commarray,$col_struct);
					}
					else {
						//echo 'O';
						$tadjcomm=CommissionScheduleRO_After_Contract_Est($commarray,$col_struct);
					}
					//echo '1';
				}
				else {
					$tadjcomm=CommissionScheduleRO_After_Contract($commarray,$col_struct);
					//echo '2';
				}
			}
			else {
				if (isset($_SESSION['modcomm']) and $_SESSION['modcomm'] >= 1) {//Commission Edit Ability
					$tadjcomm=retail_csched_ro($commarray,$col_struct);
					//echo '3';
				}
				else {
					$tadjcomm=CommissionScheduleRO_NEW($commarray,$col_struct);
					//echo '4';
				}
			}
		}
		else
		{
			//echo '5';
			echo "				<tr>\n";
			echo "					<td colspan=\"7\" align=\"center\"><b>Commission Schedules have not been created for this Office. Contact BH National Management.</b></td>\n";
			echo "				</tr>\n";
		}
	}
	
	echo "         			</table>\n";
	//echo 'X';
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "		<td valign=\"top\" align=\"left\">\n";
	echo "			<div class=\"noPrint\">\n";
	echo "			<table class=\"transnb\" width=\"70px\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";	
	
	if ($viewarray['status'] >= 2)
	{
		echo "                  	<input class=\"LockedEst buttondkgrypnl60\" value=\"Edit Items\">\n";
	}
	else
	{
		echo "						<form method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"view_addnew\">\n";
		echo "						<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
		echo "						<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
		echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "                  	<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Edit Items\">\n";
		echo "						</form>\n";
	}

	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "					<form method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
	echo "					<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "					<input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
	echo "					<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "					<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "					<input type=\"hidden\" name=\"cid\" value=\"".$rowI[10]."\">\n";
	echo "					<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"OneSheet\">\n";
	echo "					</form>\n";
	echo "					</td>\n";
	echo "				</tr>\n";

	if ($_SESSION['elev'] >= 1)
	{
		if ($rowI[11]=='0' and $rowI[12]=='0')
		{
			echo "            <tr>\n";
			echo "               <td align=\"left\">\n";
			echo "					<hr width=\"90%\">\n";
			echo "				</td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "					<td align=\"left\">\n";
			echo "				<form method=\"POST\">\n";
			echo "					<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "					<input type=\"hidden\" name=\"call\" value=\"delete_est1\">\n";
			echo "					<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
			echo "					<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
			echo "         			<input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";
			echo "					<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Delete Est\">\n";
			echo "				</form>\n";
			echo "					</td>\n";
			echo "            </tr>\n";
		}
	}
	
	if ($viewarray['jobid']!='0' || $viewarray['njobid']!='0')
	{
		$viewarray['allowdel']	=1;
	}
	else
	{
		$viewarray['allowdel']	=0;
	}

	if ($_SESSION['elev'] >= 6 || $_SESSION['clev'] >= 6 || $_SESSION['jlev'] >= 6)
	{
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "					<hr width=\"90%\">\n";
		echo "				</td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "						<form method=\"post\" id=\"ViewEstCost\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
		echo "						<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
		echo "						<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
		echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "						<input type=\"hidden\" name=\"refto\" value=\"none\">\n";

		if ($rowC[9]==1)
		{
			echo "                  <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View Cost\">\n";
		}

		echo "						</form>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
	}

	echo "			</table>\n";
	echo "			</div>\n";
	//echo "		</td>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	
	if ($_SESSION['modcomm'] >= 1)
	{
		echo "<span id=\"PBAdjustDialog\" title=\"Adjust Price per Book\">\n";
		echo "	<form id=\"SubmitPBAdjust\" method=\"post\">\n";
		echo "		<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "		<input type=\"hidden\" name=\"call\" value=\"adjins\">\n";
		echo "		<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
		echo "		<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "		<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "		<input type=\"hidden\" name=\"tranid\" value=\"".$uid."\">\n";
		echo "		Adjust Amount<br>";
		echo "		<input class=\"bboxbr\" name=\"adjamt\" id=\"PBadjamt\" value=\"0.00\" type=\"text\" size=\"6\" maxlength=\"9\"><br>\n";
		echo "		Comment<br>\n";
		echo "		<textarea name=\"descrip\" type=\"text\" cols=\"45\" rows=\"2\"></textarea>\n";
		echo "	</form>\n";
		echo "</span>\n";
	}
	
	if ($_SESSION['modcomm'] >= 1)
	{
		
		echo "<span id=\"BaseCommAdjustDialog\" title=\"Adjust Base Commission\">\n";
		echo "<br>\n";
		echo "<span id=\"origbaseamt\">".$commarray['fadjbookamt']."</span>\n";
		echo "<table align=\"center\">\n";
		//echo "<tr><td align=\"right\"></td><td align=\"right\">Reset</td><td align=\"center\"><img id=\"BaseCommAdjustReset\" src=\"images/arrow_refresh_small.png\"></td></tr>\n";
		
		if ($commarray['tbullets'] >= 3)
		{
			echo "<tr><td align=\"right\">Price per Book</td><td align=\"right\"><span id=\"baseamt\">".$commarray['fadjbookamt']."</span></td><td></td></tr>\n";
		}
		else
		{
			if (isset($commarray['fctramt']) and $commarray['fctramt']!=0)
			{
				echo "<tr><td align=\"right\">Contract Amount</td><td align=\"right\"><span id=\"baseamt\">".$commarray['fadjbookamt']."</span></td><td></td></tr>\n";
			}
			else
			{
				echo "<tr><td align=\"right\">Contract Amount</td><td align=\"right\"><span id=\"baseamt\">0</span></td><td></td></tr>\n";
			}
		}
		
		//echo "<tr><td align=\"right\">Rate</td><td align=\"right\"><input class=\"bboxbc\" type=\"text\" id=\"baserate\" value=\"0\" size=\"2\"></td><td><img id=\"baserateinc\" src=\"images/arrow_up.png\"><img id=\"baseratedec\" src=\"images/arrow_down.png\"></td></tr>\n";
		echo "<tr><td align=\"right\">Rate</td><td align=\"right\"><span id=\"baserate\">0</span></td><td>%</td></tr>\n";
		echo "<tr><td align=\"right\">Adj Base Comm</td><td align=\"right\"><span id=\"basecommadj\">0</span></td><td></td></tr>\n";
		echo "</table>\n";
		echo "</span>\n";
	}
	
	$viewarray['tcomm']		=$tadjcomm;
	$viewarray['tretail']	=$adjbookamt;
	$viewarray['tcontract']	=$ctramt;
	$viewarray['acctotal']	=$trccost;
	$viewarray['discount']	=$vdiscnt;
	$viewarray['royrel']	=0;
	$viewarray['custallow']	=0;

	$_SESSION['viewarray']=$viewarray;
}

function viewest_addnew($estid=null)
{
	$MAS=$_SESSION['pb_code'];
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate;

	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}

	$qrypreA = "SELECT
					estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,
					phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,
					comadj,sidm,buladj,applyov,applybu,refto,apft,renov
				FROM est WHERE officeid=".$_SESSION['officeid']." AND estid=".$estid.";";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_row($respreA);

	$qrypreB = "SELECT officeid,pft_sqft FROM offices WHERE officeid=".$officeid.";";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid=".$officeid." AND estid=".$rowpreA[0].";";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_row($respreD);

	// Builds a list of exisiting categories in the retail accessory table by office
	$qrypreE  = "SELECT DISTINCT a.catid,a.seqn,a.name ";
	$qrypreE .= "FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
	$qrypreE .= "ON a.catid=b.catid ";
	$qrypreE .= "AND a.officeid='".$_SESSION['officeid']."' ";
	$qrypreE .= "AND a.active=1 ";
	$qrypreE .= "ORDER BY a.seqn ASC;";
	$respreE = mssql_query($qrypreE);

	while ($rowpreE = mssql_fetch_row($respreE))
	{
		$catarray[$rowpreE[0]]=$rowpreE[2];
	}

	$ps1        =$rowpreA[1];
	$ps2        =$rowpreA[2];
	$spa1       =$rowpreA[3];
	$spa2       =$rowpreA[4];
	$spa3       =$rowpreA[5];
	$tzone      =$rowpreA[6];
	$discount   =$rowpreA[7];
	$cfname     =$rowpreA[8];
	$clname     =$rowpreA[9];
	$phone      =$rowpreA[10];
	$status     =$rowpreA[11];
	$ps5        =$rowpreA[13];
	$ps6        =$rowpreA[14];
	$ps7        =$rowpreA[15];

	$viewarray=array(
	'ps1'=>$rowpreA[1],
	'ps2'=>$rowpreA[2],
	'spa1'=>$rowpreA[3],
	'spa2'=>$rowpreA[4],
	'spa3'=>$rowpreA[5],
	'tzone'=>$rowpreA[6],
	'camt'=>$rowpreA[7],
	'cfname'=>$rowpreA[8],
	'clname'=>$rowpreA[9],
	'phone'=>$rowpreA[10],
	'status'=>$rowpreA[11],
	'ps5'=>$rowpreA[13],
	'ps6'=>$rowpreA[14],
	'ps7'=>$rowpreA[15],
	'custid'=>$rowpreA[16],
	'estsecid'=>$rowpreA[17],
	'deck'=>$rowpreA[18],
	'erun'=>$rowpreA[19],
	'prun'=>$rowpreA[20],
	'jobid'=>$rowpreA[21],
	'comadj'=>$rowpreA[22],
	'sidm'=>$rowpreA[23],
	'buladj'=>$rowpreA[24],
	'applyou'=>$rowpreA[25],
	'applybu'=>$rowpreA[26],
	'refto'=>$rowpreA[27],
	'ps1a'=>$rowpreA[28]
	);
	
	//if ($_SESSION['securityid']==26)
	//{
	//	display_array($viewarray);
	//}

	if ($rowpreB['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryC = "SELECT officeid,name,stax FROM offices WHERE officeid='".$officeid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname,rmasid FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT cid,cfname,clname,chome,scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$viewarray['custid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$type=0; //EST=0 JOB=1

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
	}

	$estidret   =$rowpreA[0];
	$vdiscnt    =0;
	$pbaseprice =$rowB[2];
	$bcomm      =$rowB[3];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');

	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";

	if ($viewarray['status']==3)
	{
		echo "<input type=\"hidden\" name=\"call\" value=\"acc_adds_addendum\">\n";
	}
	else
	{
		echo "<input type=\"hidden\" name=\"call\" value=\"acc_adds\">\n";
	}

	echo "<input type=\"hidden\" name=\"estid\" value=\"$estidret\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
	echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "XX<div id=\"masterdiv\">\n";
	echo "<table align=\"center\" width=\"700px\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"5\" valign=\"top\" align=\"left\">\n";
	echo "			<div class=\"noPrint\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\">\n";
	echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Update Items\">\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"5\" valign=\"top\" align=\"left\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"2\" align=\"left\"><b>Edit Retail Estimate</td>\n";
	echo "					<td align=\"center\">\n";
	
	if ($rowD[3]!=0)
	{
		if ($rowpreA[29]==1)
		{
			echo "               <b>Renovation</b> <input type=\"checkbox\" class=\"checkboxgry\" name=\"renov\" value=\"1\" CHECKED>\n";	
		}
		else
		{
			echo "               <b>Renovation</b> <input type=\"checkbox\" class=\"checkboxgry\" name=\"renov\" value=\"1\">\n";	
		}
	}
	
	echo "					</td>\n";
	echo "					<td colspan=\"2\" class=\"gray\" align=\"right\"><b>Estimate</b> $estidret</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"2\" align=\"left\"><b>Customer</b> ".$rowI[1]." ".$rowI[2]."</td>\n";
	echo "					<td align=\"left\"><b>Perimeter</b> $ps1  <b>Surface</b> $ps2  <b>Shallow</b> $rowpreA[13]  <b>Middle</b> $rowpreA[14]  <b>Deep</b> $rowpreA[15]</td>\n";
	echo "					<td colspan=\"2\" valign=\"bottom\" align=\"right\"><b>SalesRep</b> $rowD[1] $rowD[2]</td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td colspan=\"5\">\n";
	echo "         <table class=\"outer\" class=\"inner_borders\" width=\"100%\">\n";
	/*
	echo "         	<tr>\n";
	echo "            <td class=\"wh\" colspan=\"5\" valign=\"top\">\n";

	$ecnt=1;
	foreach ($catarray as $n=>$v)
	{
		if ($n!=0)
		{
			if ($ecnt==count($catarray))
			{
				echo "<a href=\"#".$n."\">".$v."</a>";
			}
			else
			{
				echo "<a href=\"#".$n."\">".$v."</a> - ";
			}
			$ecnt++;
		}
	}

	echo "            </td>\n";
	echo "         </tr>\n";
	*/

	// POOL RETAIL ACC ITEM Loop
	foreach ($catarray as $n=>$v)
	{
		$qryJ = "SELECT catid,name FROM AC_cats WHERE officeid=".$_SESSION['officeid']." AND catid=".$n.";";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		if ($n!=0)
		{
			echo "         <tr>\n";
			echo "            <td class=\"wh\" colspan=\"4\" align=\"left\" valign=\"top\"><div onclick=\"SwitchMenu('sub".$n."')\"><input type=\"hidden\" name=\"#".$n."\"><b>".$v."</b></div></td>\n";
			echo "            <td class=\"wh\" align=\"right\" valign=\"top\"><a href=\"#Top\">Up</a></td>\n";
			echo "         </tr>\n";
			echo "         <tr>\n";
			echo "            <td colspan=\"5\" class=\"gray\" valign=\"top\">\n";
			//echo "				<span class=\"submenu\" id=\"sub".$n."\">\n";
			//echo "                 	<table>\n";

			$qryM  = "SELECT
							id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled,bullet
						FROM [".$MAS."acc] WHERE officeid=".$_SESSION['officeid']." AND catid=".$n." AND disabled!=1 ORDER BY seqn;";
			$resM  = mssql_query($qryM);
			$nrowM = mssql_num_rows($resM);

			$qcnt=0;
			$tbg='inner_borders';
			while ($rowM=mssql_fetch_row($resM))
			{
				$qcnt++;

				if ($qcnt==1)
				{
					@form_element_ACC_NEW($rowM[0],1,$rowpreD[0],$type,$rowM,$tbg);
				}
				elseif ($qcnt==$nrowM)
				{
					@form_element_ACC_NEW($rowM[0],2,$rowpreD[0],$type,$rowM,$tbg);
				}
				else
				{
					@form_element_ACC_NEW($rowM[0],0,$rowpreD[0],$type,$rowM,$tbg);
				}
			}

			//echo "                 		</table>\n";
			//echo "                 	</span>\n";
			echo "                 </td>\n";
			echo "         </tr>\n";
		}
	}

	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"5\" valign=\"top\" align=\"left\">\n";
	echo "			<div class=\"noPrint\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"right\">\n";
	echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Update Items\">\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	echo "</form>\n";
}

function viewest_addnew_NEW()
{
	if ($_SESSION['securityid']==26)
	{
		echo __FUNCTION__;
	}
	
	$MAS=$_SESSION['pb_code'];
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate;

	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	//$estid      =$_SESSION['estid'];
	$estid 		=(isset($_REQUEST['estid']) && $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;

	if (!isset($estid)||$estid==''||$estid==0)
	{
		echo "Fatal Error: ".$estid." not set, or is Zero!";
		exit;
	}
	
	// Builds a list of exisiting categories in the retail accessory table by office
	$qrypre3  = "SELECT DISTINCT a.catid,a.seqn ";
	$qrypre3 .= "FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
	$qrypre3 .= "ON a.catid=b.catid ";
	$qrypre3 .= "AND a.officeid='".$officeid."' ";
	$qrypre3 .= "AND a.active=1 ";
	$qrypre3 .= "AND a.privcat!=1 ";
	$qrypre3 .= "ORDER BY a.seqn ASC;";
	$respre3 = mssql_query($qrypre3);

	while ($rowpre3 = mssql_fetch_row($respre3))
	{
		$catarray[]=$rowpre3[0];
	}

	$qrypreA = "SELECT
					estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,
					phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,
					comadj,sidm,buladj,applyov,applybu,refto,apft,renov
				FROM est WHERE officeid=".$_SESSION['officeid']." AND estid=".$estid.";";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_row($respreA);

	$qrypreB = "SELECT officeid,pft_sqft FROM offices WHERE officeid=".$officeid.";";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid=".$officeid." AND estid=".$rowpreA[0].";";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_row($respreD);

	$ps1        =$rowpreA[1];
	$ps2        =$rowpreA[2];
	$spa1       =$rowpreA[3];
	$spa2       =$rowpreA[4];
	$spa3       =$rowpreA[5];
	$tzone      =$rowpreA[6];
	$discount   =$rowpreA[7];
	$cfname     =$rowpreA[8];
	$clname     =$rowpreA[9];
	$phone      =$rowpreA[10];
	$status     =$rowpreA[11];
	$ps5        =$rowpreA[13];
	$ps6        =$rowpreA[14];
	$ps7        =$rowpreA[15];

	$viewarray=array(
	'ps1'=>$rowpreA[1],
	'ps2'=>$rowpreA[2],
	'spa1'=>$rowpreA[3],
	'spa2'=>$rowpreA[4],
	'spa3'=>$rowpreA[5],
	'tzone'=>$rowpreA[6],
	'camt'=>$rowpreA[7],
	'cfname'=>$rowpreA[8],
	'clname'=>$rowpreA[9],
	'phone'=>$rowpreA[10],
	'status'=>$rowpreA[11],
	'ps5'=>$rowpreA[13],
	'ps6'=>$rowpreA[14],
	'ps7'=>$rowpreA[15],
	'custid'=>$rowpreA[16],
	'estsecid'=>$rowpreA[17],
	'deck'=>$rowpreA[18],
	'erun'=>$rowpreA[19],
	'prun'=>$rowpreA[20],
	'jobid'=>$rowpreA[21],
	'comadj'=>$rowpreA[22],
	'sidm'=>$rowpreA[23],
	'buladj'=>$rowpreA[24],
	'applyou'=>$rowpreA[25],
	'applybu'=>$rowpreA[26],
	'refto'=>$rowpreA[27],
	'ps1a'=>$rowpreA[28]
	);

	if ($rowpreB['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryC = "SELECT officeid,name,stax FROM offices WHERE officeid='".$officeid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname,rmasid FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT cid,cfname,clname,chome,scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$viewarray['custid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_row($resI);

	$type=0; //EST=0 JOB=1

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
	}

	$estidret   =$rowpreA[0];
	$vdiscnt    =0;
	$pbaseprice =$rowB[2];
	$bcomm      =$rowB[3];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');

	echo "<script type=\"text/javascript\" src=\"js/jquery_estimate_func.js\"></script>\n";
	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";

	if ($viewarray['status']==3)
	{
		echo "<input type=\"hidden\" name=\"call\" value=\"acc_adds_addendum\">\n";
	}
	else
	{
		echo "<input type=\"hidden\" name=\"call\" value=\"acc_adds\">\n";
	}

	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$securityid."\">\n";
	echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table align=\"center\" width=\"950px\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"5\" valign=\"top\" align=\"left\">\n";
	echo "			<div class=\"noPrint\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\">\n";
	echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Update Items\">\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"5\" valign=\"top\" align=\"left\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"2\" align=\"left\"><b>Edit Retail Estimate</td>\n";
	echo "					<td align=\"center\">\n";
	
	if ($rowD[3]!=0)
	{
		if ($rowpreA[29]==1)
		{
			echo "               <b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\" CHECKED>\n";	
		}
		else
		{
			echo "               <b>Renovation</b> <input type=\"checkbox\" class=\"transnb\" name=\"renov\" value=\"1\">\n";	
		}
	}
	
	echo "					</td>\n";
	echo "					<td colspan=\"2\" class=\"gray\" align=\"right\"><b>Estimate</b> ".$estidret."</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"2\" align=\"left\"><b>Customer</b> ".$rowI[1]." ".$rowI[2]."</td>\n";
	echo "					<td align=\"left\"><b>Perimeter</b> ".$ps1."  <b>Surface</b> ".$ps2."  <b>Shallow</b> ".$rowpreA[13]."  <b>Middle</b> ".$rowpreA[14]."  <b>Deep</b> ".$rowpreA[15]."</td>\n";
	echo "					<td colspan=\"2\" valign=\"bottom\" align=\"right\"><b>SalesRep</b> $rowD[1] $rowD[2]</td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	
	if (count($catarray) > 0)
	{
		echo "<br>";
		echo "<fieldset class=\"pbouter\">\n";
		echo "<legend>PRICEBOOK</legend>\n";
		echo "<table align=\"center\" width=\"925px\" border=0>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<table class=\"transnb\" width=\"100%\">\n";

		foreach ($catarray as $n=>$v)
		{
			$qryJ = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."';";
			$resJ = mssql_query($qryJ);
			$rowJ = mssql_fetch_row($resJ);

			if ($v!=0)
			{
				echo "			<tr>\n";
				echo "				<td class=\"wh\" align=\"left\" valign=\"top\">\n";
				echo "					<input type=\"hidden\" name=\"#".$rowJ[0]."\"><b>".$rowJ[1]."</b>\n";
				echo "				</td>\n";
				echo "				<td class=\"wh\" align=\"right\" valign=\"top\"><a href=\"#Top\"><img class=\"transnb\" src=\"images/scrollup.gif\" alt=\"to Top\"></a></td>\n";
				echo "			</tr>\n";

				$qryM  = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid=".$_SESSION['officeid']." AND catid='".$v."' AND disabled!='1' ORDER BY seqn;";
				$resM  = mssql_query($qryM);
				$nrowM = mssql_num_rows($resM);

				$qcnt=0;
				while ($rowM=mssql_fetch_row($resM))
				{
					$qcnt++;
					echo "		<tr>\n";
					echo "			<td class=\"gray\" align=\"left\" colspan=\"5\" valign=\"top\">\n";
					
					if ($qcnt==1)
					{
						form_element_ACC($rowM[0],1,$rowpreD[0],0);
					}
					elseif ($qcnt==$nrowM)
					{
						form_element_ACC($rowM[0],2,$rowpreD[0],0);
					}
					else
					{
						form_element_ACC($rowM[0],0,$rowpreD[0],0);
					}

					echo "			</td>\n";
					echo "		</tr>\n";
				}
			}
		}
		
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
	
	echo "</table>\n";
	echo "</fieldset>\n";

	echo "<br>";
	echo "<table align=\"center\" width=\"950px\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"5\" valign=\"top\" align=\"left\">\n";
	echo "			<div class=\"noPrint\">\n";
	echo "			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"right\">\n";
	echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Update Items\">\n";
	echo "					</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "			</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function viewest_addnew_TED($oid,$estid)
{
	$qryA = "SELECT e.estid,e.cid,e.securityid,e.officeid FROM est AS e WHERE e.officeid=".(int) $oid." AND e.estid=".(int) $estid.";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA;
	
	if ($nrowA > 0)
	{
		echo "NEW<br>";
		echo "<script type=\"text/javascript\" src=\"js/jquery_estimate_EditItems.js\"></script>\n";
		echo "<fieldset class=\"pbouter\">\n";
		echo "<legend>PRICEBOOK</legend>\n";
		echo "<table align=\"center\" width=\"900px\" border=0>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\" align=\"left\">\n";
		echo "			<div id=\"dispCustomerInfo\"></div>\n";
		echo "      </td>\n";
		echo "		<td valign=\"top\" align=\"right\">\n";
		echo "			<div id=\"rtlbrkdwnrtnbtn\"></div>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\" align=\"right\">\n";
		echo "			<div id=\"updateitemsbtntop\"></div>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"3\" align=\"left\">\n";
		echo "			<form id=\"frmEstEditItems\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "			<input type=\"hidden\" name=\"call\" value=\"acc_adds\">\n";
		echo "			<input type=\"hidden\" id=\"active_estid\" name=\"estid\" value=\"".$rowA['estid']."\">\n";
		echo "			<input type=\"hidden\" id=\"active_oid\" name=\"officeid\" value=\"".$rowA['officeid']."\">\n";
		echo "			<input type=\"hidden\" id=\"active_sid\" name=\"securityid\" value=\"".$rowA['securityid']."\">\n";
		echo "				<div id=\"LoadStatus\"></div>\n";
		echo "				<div id=\"PBAccordionContainer\"></div>\n";
		echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"3\" valign=\"top\" align=\"right\">\n";
		echo "			<div id=\"updateitemsbtnbtm\"></div>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "</fieldset>\n";
	}
}

function viewest_cost($estid=null)
{
	if (!isset($_SESSION['viewarray']))
	{
		echo "Fatal Error: Job Cost variables not set!";
		exit;
	}
	
	//print_r($_SESSION['viewarray'])."<br>";
	//print_r($_SESSION)."<br>";
	
	$MAS		=$_SESSION['pb_code'];
	global 		$bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate;

	$viewarray	=$_SESSION['viewarray'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];
	
	if (isset($estid) and !is_null($estid)) {
		$estid=$estid;
	}
	else {
		$estid	=(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:0;
	}
	
	if ($estid==0) {
		echo "Fatal Error: Estimate ID (".$estid.") not set!";
		exit;
	}
	
	if ($_SESSION['securityid']==26999999999999999999999999999999999999999) {
		echo '<pre>';
		
		print_r($_REQUEST);
		
		echo '</pre>';
	}

	//display_array($_SESSION['viewarray'])."<br>";
	$qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun,jobid,comadj,sidm,buladj,applyov,applybu,refto,apft,added,updated,updateby,ccid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_row($respreA);
	
	/*$qrypreAa = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rowpreA[32]."';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);*/

	$jsecurityid =$rowpreA[17];

	$qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_row($respreD);

	$ps1        =$rowpreA[1];
	$ps2        =$rowpreA[2];
	$spa1       =$rowpreA[3];
	$spa2       =$rowpreA[4];
	$spa3       =$rowpreA[5];
	$tzone      =$rowpreA[6];
	$contractamt=$rowpreA[7];
	$cfname     =$rowpreA[8];
	$clname     =$rowpreA[9];
	$phone      =$rowpreA[10];
	$status     =$rowpreA[11];
	$ps5        =$rowpreA[13];
	$ps6        =$rowpreA[14];
	$ps7        =$rowpreA[15];
	
	if (isset($viewarray['acctotal']) and $viewarray['acctotal']!=0)
	{
		$acctotal=$viewarray['acctotal'];
	}
	else
	{
		$acctotal=0;
	}

	$qryC = "SELECT officeid,name,stax,sm,gm,psched,psched_perc,pft_sqft FROM offices WHERE officeid='".$officeid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[7]=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='$defmeas';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$jsecurityid."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$rowpreA[16]."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_row($resL);

	if ($rowpreA[31]!=0)
	{
		$qryM = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowpreA[31]."';";
		$resM = mssql_query($qryM);
		$rowM = mssql_fetch_array($resM);

		$lupdatestr=$rowM['fname']." ".$rowM['lname'];
	}
	else
	{
		$lupdatestr="";
	}

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ 	= "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ 	= mssql_query($qryJ);
		$rowJ 	= mssql_fetch_row($resJ);

		$taxrate	=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);

		$viewarray['taxrate']	=$taxrate[1];
		$viewarray['tax']		=$viewarray['camt']*$taxrate[1];
		$viewarray['were']		="from Dynamic";
	}

	if (!empty($rowpreA[29]))
	{
		$atime=date("m/d/Y", strtotime($rowpreA[29]));
	}
	else
	{
		$atime="";
	}

	if (!empty($rowpreA[30]))
	{
		$utime=date("m/d/Y", strtotime($rowpreA[30]));
	}
	else
	{
		$utime="";
	}

	$set_ia		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$set_gals	=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
	$estidret   =$rowpreA[0];
	$vdiscnt    =$viewarray['discount'];
	$pbaseprice =$rowB[2];
	$bcomm      =$rowB[3];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	$brdr=0;

	echo "<script type=\"text/javascript\" src=\"js/jquery_estimate_func.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"js/jquery_estimate_cost_func.js\"></script>\n";
	echo "<table width=\"950px\">\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"3\" align=\"right\" >\n";
	echo "                  <table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "                     <tr>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"top\"><b>Cost Estimate</b> ".$rowC[1]."</td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>\n";
	?>
		
		<script type="text/javascript">
            setLocalTime();
        </script>
		
	<?php
	echo "							</b></td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"25%\">\n";
	echo "                  <table class=\"outer\" width=\"100%\" height=\"200\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" valign=\"top\">\n";

	//	Customer Display Start
	cinfo_display($viewarray['cid'],$rowC[2]);
	// Customer Display End

	echo "							</td>\n";
	echo "						</tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"50%\">\n";
	echo "                  <table class=\"outer\" width=\"100%\" height=\"200\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" valign=\"top\">\n";

	// Pool Display Start
	pool_detail_display($estid);
	// Pool Display End

	echo "							</td>\n";
	echo "						</tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"25%\">\n";
	echo "                  <table class=\"outer\" width=\"100%\" height=\"200\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" valign=\"top\">\n";
	echo "								<table width=\"100%\">\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Estimate</b></td>\n";
	echo "										<td align=\"left\">".$estidret."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>SalesRep</b></td>\n";
	echo "										<td align=\"left\">".$rowD[1]." ".$rowD[2]."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "				                        <td align=\"right\"><b>Sales Manager</b></td>\n";
	
	if ($viewarray['sidm']!=0)
	{
		echo "				                        <td align=\"left\">".$rowL[1]." ".$rowL[2]."</td>\n";
	}
	else
	{
		echo '<td align=\"left\">None Assigned</td>';
	}
	
	echo "				                     </tr>\n";
	echo "									<tr>\n";
	echo "				                        <td colspan=\"2\" align=\"center\"><hr width=\"90%\"></td>\n";
	echo "				                     </tr>\n";
	echo "				                     <tr>\n";
	echo "										<td align=\"right\"><b>Added</b>&nbsp</td>\n";
	echo "										<td align=\"left\">".$atime."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Updated</b>&nbsp</td>\n";
	echo "										<td align=\"left\">".$utime."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Update by</b></td>\n";
	echo "										<td align=\"left\">".$lupdatestr."</td>\n";
	echo "									</tr>\n";
	echo "                  			</table>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"bottom\" align=\"left\">\n";
	echo "			<div class=\"noPrint\">\n";
	echo "			<form method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "			<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "			<input type=\"hidden\" name=\"estid\" value=\"$estid\">\n";
	echo "			<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
	echo "			<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
	echo "			<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "			<input class=\"buttondkgrypnl\" type=\"submit\" value=\"View Retail\">\n";
	echo "			</form>\n";
	echo "			</div>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"100%\">\n";

	//	Bids Rollup Display
	costadj_rollup_disp($_SESSION['officeid'],$viewarray['cid'],$estid,0,"e");
	
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "		<td></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"gray\" border=1>\n";

	calcbyphsL($rowpreD[0],0,0,0);
	$bccost  =$bctotal;
	$fbccost =number_format(round($bccost), 2, '.', '');

	echo "           <tr>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td colspan=\"3\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
	}
	else
	{
		echo "              <td colspan=\"5\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
	}

	echo "              <td class=\"wh\" align=\"right\"><b>".$fbccost."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	echo "         <br>\n";
	echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"gray\" border=1>\n";

	calcbyphsM($rowpreD[0],0,0);
	$bmcost  =$bmtotal;
	$fbmcost =number_format(round($bmcost), 2, '.', '');

	echo "           <tr>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td colspan=\"3\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
	}
	else
	{
		echo "              <td colspan=\"5\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
	}

	echo "              <td class=\"wh\" align=\"right\"><b>".$fbmcost."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";

	echo "         <br>\n";

	// Total Table
	$custallow	=$viewarray['custallow'];
	$tcustallow	=$custallow*-1;
	$tcontract	=0;
	$tcontract	=$viewarray['camt'];
	$tbcost		=round($bccost+$bmcost);

	if ($rowC[2]==1)
	{
		$tax			=$tcontract*$taxrate[1];
		//$tax			=round($tax);
		$tcontract	=$tcontract+$tax;
	}

	if ($tcustallow != 0)
	{
		$tadjcontract	=$tcontract+$tcustallow;
	}
	else
	{
		$tadjcontract	=$tcontract;
	}

	if ($tcustallow != 0)
	{
		$tadjbcost		=round($tbcost+$tcustallow);
	}
	else
	{
		$tadjbcost		=round($tbcost);
	}

	//$tgross		=$tbcost;

	if ($tcustallow != 0)
	{
		$tprofit		=$tadjcontract-$tadjbcost;
	}
	else
	{
		$tprofit		=$tcontract-$tbcost;
	}

	if ($tcontract!=0)
	{
		if ($tcustallow != 0)
		{
			$netper  =$tprofit/$tadjcontract;
		}
		else
		{
			$netper  =$tprofit/$tcontract;
		}
	}
	else
	{
		$netper  =0;
	}

	$ftcustallow	=number_format($tcustallow, 2, '.', '');
	$ftcontract 	=number_format($tcontract, 2, '.', '');
	$ftadjcontract 	=number_format($tadjcontract, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftadjbcost		=number_format($tadjbcost, 2, '.', '');
	$ftprofit		=number_format($tprofit, 2, '.', '');
	$fnetper 		=round($netper, 2)*100;

	echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"gray\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td colspan=\"2\" class=\"wh\" align=\"left\"><b>Totals</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Retail Contract Price</b></td>\n";
	echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftcontract."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td width=\"65\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Adjusted Contract Price</b></td>\n";
		echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftadjcontract."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Construction Total</b></td>\n";
	echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftbcost."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td width=\"65\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Adjusted Construction Total</b></td>\n";
		echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftadjbcost."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Net</b></td>\n";
	echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftprofit."</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Net %</b></td>\n";
	echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$fnetper."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	
	_show_hide_objects();
	
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	
	$_SESSION['viewarray']=$viewarray;
}

function estimate_matrix()
{
	if ($_SESSION['securityid']==26999999999999999999)
	{
		echo __FILE__.'<br>';
	}
	
	include ('estimatematrix_support_func.php');
	
	if (!isset($_SESSION['call'])||$_SESSION['call']=="None")
	{
	}
	elseif ($_SESSION['call']=="CreateContract")
	{
		CreateContractwTax();
		/*
		if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332)
		{
			CreateContractwTAX();
		}
		else
		{
		*/
		//CreateContract();
		//}
	}
	elseif ($_SESSION['call']=="new")
	{
		cform();
	}
	elseif ($_SESSION['call']=="matrix0")
	{
		matrix0();
	}
	elseif ($_SESSION['call']=="matrix0a")
	{
		//savecust();
		matrix0a();
	}
	elseif ($_SESSION['call']=="matrix1")
	{
		saveest0();
	}
	elseif ($_SESSION['call']=="view_retail")
	{
		viewest_retail();
	}
	elseif ($_SESSION['call']=="view_retail_print")
	{
		//viewest_retail_print($_SESSION['estid']);
	}
	elseif ($_SESSION['call']=="view_cost")
	{
		viewest_cost();
	}
	elseif ($_SESSION['call']=="view_cost_print")
	{
		viewest_cost_print();
	}
	elseif ($_SESSION['call']=="view_addnew")
	{
		viewest_addnew_NEW();
		/*
		if ($_SESSION['officeid']==69)
		{
			viewest_addnew_TED($_REQUEST['officeid'],$_REQUEST['estid']);
		}
		else
		{
			viewest_addnew_NEW();
		}
		*/
	}
	elseif ($_SESSION['call']=="acc_adds")
	{
		add_acc_items();
		/*
		if ($_SESSION['officeid']==69)
		{
			ajxEventProc(0);
			add_acc_items_TED($_REQUEST['officeid'],$_REQUEST['estid']);
		}
		else
		{
			add_acc_items($_SESSION['estid']);
		}
		*/
	}
	elseif ($_SESSION['call']=="update_contract_amt")
	{
		update_contract_amt();
	}
	elseif ($_SESSION['call']=="acc_adds_addendum")
	{
		add_acc_items_add();
	}
	elseif ($_SESSION['call']=="addadj")
	{
		addadj_init();
	}
	elseif ($_SESSION['call']=="adjins")
	{
		addadj_ins();
	}
	elseif ($_SESSION['call']=="edit_bid")
	{
		edit_bid();
	}
	elseif ($_SESSION['call']=="edit_bid_jobmode_add")
	{
		edit_bid_jobmode_add();
	}
	elseif ($_SESSION['call']=="edit_bid_jobmode_delete")
	{
		//echo "Contract VBJM<br>";
		edit_bid_jobmode_delete();
	}
	elseif ($_SESSION['call']=="edit_mpa_jobmode_add")
	{
		edit_mpa_jobmode_add();
	}
	elseif ($_SESSION['call']=="edit_mpa_jobmode_delete")
	{
		//echo "Contract VBJM<br>";
		edit_mpa_jobmode_delete();
	}
	elseif ($_SESSION['call']=="edit_bid_update")
	{
		edit_bid_update();
	}
	elseif ($_SESSION['call']=="edit_bid_delete")
	{
		edit_bid_delete();
	}
	elseif ($_SESSION['call']=="bidins")
	{
		bid_addins($_SESSION['estid']);
	}
	elseif ($_SESSION['call']=="update")
	{
		updateest();
	}
	elseif ($_SESSION['call']=="insertest_add") // Inserts Addendum Header Variables
	{
		insertest_add();
	}
	elseif ($_SESSION['call']=="remove_acc")
	{
		remove_acc();
	}
	elseif ($_SESSION['call']=="pop_update")
	{
		pop_updateest();
	}
	elseif ($_SESSION['call']=="applyou")
	{
		apply_overage();
	}
	elseif ($_SESSION['call']=="deleteou")
	{
		delete_overage();
	}
	elseif ($_SESSION['call']=="applybu")
	{
		apply_bullet();
	}
	elseif ($_SESSION['call']=="deletebu")
	{
		delete_bullet();
	}
	elseif ($_SESSION['call']=="delete_est1")
	{
		delete_est();
	}
	elseif ($_SESSION['call']=="delete_est2")
	{
		delete_est();
	}
	elseif ($_SESSION['call']=="cview")
	{
		//echo "TESTC";
		cform_view();
	}
	elseif ($_SESSION['call']=="search_results")
	{
		listest();
	}
	elseif ($_SESSION['call']=="list")
	{
		listest();
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
	elseif ($_SESSION['call']=="biddel")
	{
		edit_bid_jobmode_delete();
	}
	elseif ($_SESSION['call']=="mpadel")
	{
		edit_mpa_jobmode_delete();
	}
	elseif ($_SESSION['call']=="search")
	{
		est_search();
	}
	elseif ($_SESSION['call']=='remove_est_adj_item')
	{
		remove_est_adj_item();
	}
}

?>