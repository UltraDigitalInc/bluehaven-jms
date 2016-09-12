<?php

function get_SCAdjustForm($sid)
{
	$out='';
	$ranhash1  =md5(session_id().time()).".".$_SESSION['securityid'];
	$out.="					<div id=\"adjustdialog\" title=\"Manual Adjust\">\n";
	$out.="						<form id=\"AdjustSRPage\" name=\"AdjustSRPage\" method=\"post\">\n";
	$out.="						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	$out.="						<input type=\"hidden\" name=\"call\" value=\"srpage\">\n";
	$out.="						<input type=\"hidden\" name=\"stg\" value=\"2\">\n";
	$out.="						<input type=\"hidden\" name=\"incsecondary\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"createmancommadj\" value=\"1\">\n"; 
	$out.="						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	$out.="						<input type=\"hidden\" name=\"sid\" value=\"".$sid."\">\n";
	$out.="						<input type=\"hidden\" name=\"ranhash\" value=\"".$ranhash1."\">\n";
	$out.="						<input type=\"hidden\" name=\"showcommission\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showadjust\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showbonus\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showloan\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showdraw\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showpipeline\" value=\"1\">\n";
	$out.="						<table width=\"275px\">\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Type</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\">\n";
	$out.="                                  <select name=\"htype\" id=\"htype\">\n";
	
	if ($_SESSION['clev'] >= 9)
	{
		$out.="                                      <option value=\"A\" style=\"background-color:lightblue\">Adjust</option>\n";
	}
	
	if ($_SESSION['clev'] >= 9)
	{
		$out.="                                      <option value=\"B\" style=\"background-color:#FFD700\">Bonus</option>\n";
	}
	
	if ($_SESSION['clev'] >= 9)
	{
		$out.="                                      <option value=\"D\" style=\"background-color:#99FF9A\">Draw</option>\n";
	}
	
	if ($_SESSION['clev'] >= 9)
	{
		$out.="                                      <option value=\"L\" style=\"background-color:#008B8B;color:#FFFFFF\">Loan</option>\n";
	}
	
	//if ($_SESSION['clev'] >= 9)
	//{
	//	$out.="                                      <option value=\"R\" style=\"background-color:#666666;color:#FFFFFF\">Processor Lock</option>\n";
	//}
	
	$out.="                                  </select>\n";
	$out.="                              </td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Date</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\"><input type=\"text\" name=\"trandate\" id=\"d9\" value=\"".date('m/d/Y')."\" size=\"20\" maxlength=\"10\"></td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Description</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\"><input type=\"text\" name=\"descrip\" size=\"20\" maxlength=\"32\"></td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Amount</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\"><input type=\"text\" id=\"adj_amt\" name=\"amt\" size=\"20\"></td>\n";
	$out.="							</tr>\n";
	$out.="						</table>\n";
	$out.="						</form>\n";
	$out.="					</div>\n";
	
	return $out;
}

function get_SCValidateForm($sid,$tbal)
{
	$out='';
	
	$qryA = "   SELECT
					*,
					(select name from jest..offices where officeid=S.officeid) as oname,
					(select label_masoff_code from offices where officeid=S.officeid) as olabel
				FROM
					security as S
				WHERE
					securityid=".(int) $sid.";";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	$nrowA= mssql_num_rows($resA);
	
	$ranhash  =md5(session_id().time()).".".$_SESSION['securityid'];
	$out.="					<div id=\"validatedialog\" title=\"Balance Validation\">\n";
	$out.="						<form id=\"ValidateSRPage\" name=\"ValidateSRPage\" method=\"post\">\n";
	$out.="						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	$out.="						<input type=\"hidden\" name=\"call\" value=\"srpage\">\n";
	$out.="						<input type=\"hidden\" name=\"stg\" value=\"2\">\n";
	$out.="						<input type=\"hidden\" name=\"incsecondary\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"createmancommadj\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	$out.="						<input type=\"hidden\" name=\"sid\" value=\"".$_REQUEST['sid']."\">\n";
	$out.="						<input type=\"hidden\" name=\"ranhash\" value=\"".$ranhash."\">\n";
	$out.="						<input type=\"hidden\" name=\"showcommission\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showadjust\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showbonus\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showloan\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showdraw\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showpipeline\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"htype\" value=\"R\">\n";
	$out.="						<table width=\"200px\">\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"100px\"><b>Sales Rep</b></td>\n";
	$out.="								<td lign=\"left\" valign=\"bottom\">".$rowA['lname'].", ".$rowA['fname']." (".$rowA['securityid'].")</td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"100px\"><b>Validator</b></td>\n";
	$out.="								<td lign=\"left\" valign=\"bottom\">".$_SESSION['lname'].", ".$_SESSION['fname']."</td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"100px\"><b>Date</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\"><input type=\"text\" name=\"trandate\" id=\"d10\" value=\"".date('m/d/Y')."\" size=\"20\" maxlength=\"10\"></td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"100px\"><b>Balance</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\">".number_format($tbal, 2, '.', '')."<input type=\"hidden\" name=\"amt\" size=\"20\" value=\"".number_format($tbal, 2, '.', '')."\"></td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"100px\"><b>Comment</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\"><input type=\"text\" name=\"descrip\" size=\"20\" maxlength=\"32\"></td>\n";
	$out.="							</tr>\n";
	$out.="						</table>\n";
	$out.="						</form>\n";
	$out.="					</div>\n";
	
	return $out;
}

function get_SCDeleteItemForm($sid,$hid)
{
	$out='';
	
	$qry0 = "SELECT * FROM jest..security WHERE securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	$qry1 = "SELECT * FROM jest..security WHERE securityid=".(int) $sid.";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	
	$qry2 = "SELECT * FROM jest..CommissionHistory WHERE hid=".(int) $hid.";";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
	$nrow2= mssql_num_rows($res2);
	
	$out.="						<form id=\"SCDeleteItemForm\" method=\"post\">\n";
	$out.="						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	$out.="						<input type=\"hidden\" name=\"call\" value=\"srpage\">\n";
	$out.="						<input type=\"hidden\" name=\"stg\" value=\"2\">\n";
	$out.="						<input type=\"hidden\" name=\"incsecondary\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"deletemancommadj\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"officeid\" value=\"".$row1['officeid']."\">\n";
	$out.="						<input type=\"hidden\" name=\"sid\" value=\"".$row1['securityid']."\">\n";
	$out.="						<input type=\"hidden\" name=\"hid\" value=\"".$row2['hid']."\">\n";
	$out.="						<input type=\"hidden\" name=\"showcommission\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showadjust\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showbonus\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showloan\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showdraw\" value=\"1\">\n";
	$out.="						<input type=\"hidden\" name=\"showpipeline\" value=\"1\">\n";
	$out.="						<table width=\"100%\">\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Sales Rep:</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\">".$row1['lname'].", ".$row1['fname']." (".$row1['securityid'].")</td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Date:</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\">".date('m/d/Y',strtotime($row2['trandate']))."</td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Description:</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\">".$row2['descrip']."</td>\n";
	$out.="							</tr>\n";
	$out.="							<tr>\n";
	$out.="								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Amount:</b></td>\n";
	$out.="								<td align=\"left\" valign=\"bottom\">".number_format($row2['amt'], 2, '.', '')."</td>\n";
	$out.="							</tr>\n";
	$out.="						</table>\n";
	$out.="						</form>\n";
	
	return $out;
}

function get_SCLegend()
{
	$out="
	<table width=\"100%\">
				<tr>
					<td>
						<table width=\"100%\">
							<tr>
								<td align=\"right\" valign=\"bottom\" width=\"100px\"><b>Type</b></td>
								<td align=\"left\" valign=\"bottom\"><b>Description</b></td>
							</tr>
							<tr>
								<td class=\"lightblue\" align=\"right\" valign=\"top\" width=\"100px\">Adjusts</td>
								<td class=\"lightblue\" align=\"left\" valign=\"top\">Begin Balance or Manual Adjustments to the S&C balance</td>
							</tr>
							<tr>
								<td class=\"gold\" align=\"right\" valign=\"top\" width=\"100px\">Bonus</td>
								<td class=\"gold\" align=\"left\" valign=\"top\"></td>
							</tr>
							<tr>
								<td class=\"white\" align=\"right\" valign=\"top\" width=\"100px\">Commissions</td>
								<td class=\"white\" align=\"left\" valign=\"top\">SRC = Sales Rep Comm, SRO = Sales Rep Over/Under Comm, SRM = Sales Rep Manual Adjust Comm, SMC = Sales Manager Comm</td>
							</tr>
							<tr>
								<td class=\"lightgreen\" align=\"right\" valign=\"top\" width=\"100px\">Draws</td>
								<td class=\"lightgreen\" align=\"left\" valign=\"top\">PRC = Payroll Check</td>
							</tr>
							<tr>
								<td class=\"darkcyan\" align=\"right\" valign=\"top\" width=\"100px\">Loans</td>
								<td class=\"darkcyan\" align=\"left\" valign=\"top\"></td>
							</tr>
							<tr>
								<td class=\"tan\" align=\"right\" valign=\"top\" width=\"100px\">Pending</td>
								<td class=\"tan\" align=\"left\" valign=\"top\">PSRC = Pending Sales Rep Comm, PSMC = Pending Sales Manager Comm</td>
							</tr>
							<tr>
								<td class=\"grayback\" align=\"right\" valign=\"top\" width=\"100px\">Validated</td>
								<td class=\"grayback\" align=\"left\" valign=\"top\">Amount in Total column is the Validated Amount. Equals Running total on the same line.</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
	";
	
	return $out;
}

?>