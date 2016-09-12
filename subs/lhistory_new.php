<?php
session_start();

$cid=(isset($_SESSION['ifcid']) && is_numeric($_SESSION['ifcid']))?$_SESSION['ifcid']:$_REQUEST['cid'];

include ('../connect_db.php');

	$qryZ  = "SELECT  ";
	$qryZ .= "	 L.id as lhid";
	$qryZ .= "	,L.udate ";
	$qryZ .= "FROM  ";
	$qryZ .= "	leadhistory as L ";
	$qryZ .= "WHERE ";
	$qryZ .= "	cinfo_id=".(int) $cid." ";
	$qryZ .= "ORDER BY ";
	$qryZ .= "	L.udate DESC; ";
	$resZ = mssql_query($qryZ);
	$nrowZ= mssql_num_rows($resZ);

	if ($nrowZ > 0)
	{
		/*
		//echo $qryZ."<br>";
		$cnt=0;		
		//if (isset($_POST['lhid']) && $_POST['lhid']!=0)
		//{
			$qryZa  = "SELECT  ";
			$qryZa .= "	 L.id as lhid";
			$qryZa .= "	,L.officeid ";
			$qryZa .= "	,(select name from offices where officeid=L.officeid) as oname ";
			$qryZa .= "	,L.cinfo_id ";
			$qryZa .= "	,L.clname ";
			$qryZa .= "	,L.cfname ";
			$qryZa .= "	,L.caddr1 ";
			$qryZa .= "	,L.saddr1 ";
			$qryZa .= "	,L.czip1 ";
			$qryZa .= "	,L.szip1 ";
			$qryZa .= "	,L.chome ";
			$qryZa .= "	,L.ccell ";
			$qryZa .= "	,L.cwork ";
			$qryZa .= "	,L.source ";
			$qryZa .= "	,L.result ";
			$qryZa .= "	,(select name from leadstatuscodes where statusid=L.source) as srcname ";
			$qryZa .= "	,(select name from leadstatuscodes where statusid=L.result) as resname ";
			$qryZa .= "	,(select lname from security where securityid=L.uby) as ulname ";
			$qryZa .= "	,(select fname from security where securityid=L.uby) as ufname ";
			$qryZa .= "	,(select lname from security where securityid=L.owner) as slname ";
			$qryZa .= "	,(select fname from security where securityid=L.owner) as sfname ";
			$qryZa .= "	,L.appt ";
			$qryZa .= "	,L.udate ";
			$qryZa .= "FROM  ";
			$qryZa .= "	leadhistory as L ";
			$qryZa .= "WHERE ";
			
			if (!isset($_POST['lhid']))
			{
				$qryZa .= "	L.id=(SELECT TOP 1 id FROM leadhistory WHERE cinfo_id=".$_SESSION['ifcid']." ORDER by udate DESC);";
			}
			else
			{
				$qryZa .= "	L.id='".$_POST['lhid']."' ;";
			}
			
			$resZa  = mssql_query($qryZa);
			$rowZa  = mssql_fetch_array($resZa);
			$nrowZa = mssql_num_rows($resZa);
			
			//echo $qryZa."<br>";
			if ($nrowZa > 0)
			{
				echo "			<table width=\"100%\">\n";
				echo "				<tr>\n";
				echo "            		<td class=\"gray\" width=\"300px\">\n";
				echo "						<table align=\"left\" width=\"100%\">\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Lead Updated:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". date("m/d/Y h:i A",strtotime($rowZa['udate'])) ." <b>by</b> ". $rowZa['ulname'] .", ". $rowZa['ufname'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>First Name:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['cfname'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Last Name:</td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['clname'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Home Ph:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['chome'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Cell Ph:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['ccell'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Work Ph:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['cwork'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Appnt:</b></td>\n";
				
				if (isset($rowZa['appt']) && strtotime($rowZa['appt']) > strtotime('1/1/2002'))
				{
					echo "          			  		<td class=\"gray\" align=\"left\">". date("m/d/Y h:i A",strtotime($rowZa['appt'])) ."</td>\n";
				}
				else
				{
					echo "          			  		<td class=\"gray\" align=\"left\"></td>\n";
				}
				
				echo "							</tr>\n";
				echo "						</table>\n";
				echo "					</td>\n";
				echo "            		<td class=\"gray\" width=\"250px\">\n";
				echo "						<table align=\"left\" width=\"100%\">\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>SalesRep:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['slname'] .",". $rowZa['sfname'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Cust Addr:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['caddr1'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Cust Zip:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['czip1'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Site Addr:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['saddr1'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Site Zip:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['szip1'] ."</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Source:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">\n";
				
				if ($rowZa['source']==0)
				{
					echo "bluehaven.com";
				}
				else
				{
					echo $rowZa['srcname'];
				}
				
				echo "								</td>\n";
				echo "							</tr>\n";
				echo "							<tr>\n";
				echo "          			  		<td class=\"gray\" align=\"right\"><b>Result:</b></td>\n";
				echo "          			  		<td class=\"gray\" align=\"left\">". $rowZa['resname'] ."</td>\n";
				echo "							</tr>\n";
				echo "						</table>\n";
				echo "					</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
			}
		//}
			
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "      	</table>\n";
		echo "		</td>\n";
		echo "		<td class=\"gray\" width=\"115px\" valign=\"top\">\n";
		echo "			<table width=\"100%\">\n";
		echo "				<tr>\n";
		echo "            		<td class=\"ltgray_und\" align=\"center\" title=\"Click on a Date to view the Lead Data prior to update\"><b>Date Updated</b></td>\n";
		echo "				</tr>\n";
		*/
		
		while ($rowZ = mssql_fetch_array($resZ))
		{
			echo "	<tr>\n";
			echo "	<form name=\"lhistory_embedded\" action=\"./lhistory.php\" method=\"post\">";
			echo "	<input type=\"hidden\" name=\"lhid\" value=\"".$rowZ['lhid']."\">\n";
			echo "		<td align=\"center\">\n";
			
			if (isset($_POST['lhid']) && $_POST['lhid']==$rowZ['lhid'])
			{
				echo "			<input class=\"btndkgry100\" type=\"submit\" value=\"". date("m/d/y h:i A",strtotime($rowZ['udate'])) ."\">\n";
			}
			elseif (!isset($_POST['lhid']) && $cnt==0)
			{
				echo "			<input class=\"btndkgry100\" type=\"submit\" value=\"". date("m/d/y h:i A",strtotime($rowZ['udate'])) ."\">\n";
			}
			else
			{
				
				echo "			<input class=\"btnwhtnb100\" type=\"submit\" value=\"". date("m/d/y h:i A",strtotime($rowZ['udate'])) ."\">\n";
			}
			
			echo "		</td>\n";
			echo "	</form>";
			echo "	</tr>\n";
			$cnt++;
		}
		
		/*
		echo "      </table>\n";
		echo "   </td></tr>\n";
		echo "</table>\n";
		*/
	}

	/*
	echo "		         </td>\n";
	echo "	         </tr>\n";
	echo "			</table>\n";
	*/

?>