<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);

include ('../connect_db.php');
include ('./ajax_common_func.php');
if (isLoggedIn() and getLogState($_SESSION['securityid']))
{	
	if (isset($_REQUEST['call']) and $_REQUEST['call']=="list")
	{
		listmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="listsent")
	{
		listsentmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="view")
	{
		viewmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="new")
	{
		newmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="reply")
	{
		newmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="add")
	{
		addmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="edit")
	{
		editmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="delete")
	{
		delmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="new_feedback")
	{
		fdbkmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="add_feedback")
	{
		addfdbkmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="viewsent")
	{
		viewsntmsg();
	}
	elseif (isset($_REQUEST['call']) and $_REQUEST['call']=="markmsgRead")
	{
		markmsgRead();
	}
	else
	{
		echo 'Error: '.__LINE__;
		
	}
}
else
{
	echo 'Error: '.__LINE__;
}

function markmsgRead()
{
	$mid=$_REQUEST['msgid'];
	
	if ($mid!=0)
	{
		$qryA = "SELECT mid,securityid,sendto,viewed FROM jest..messages WHERE mid=".(int) $mid .";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		$nrowA = mssql_num_rows($resA);
		
		if ($nrowA > 0 and $rowA['viewed']==0 and $rowA['sendto']==$_SESSION['securityid'])
		{
			$qryB = "UPDATE jest..messages SET viewed=1 WHERE mid=".(int) $mid .";";
			$resB = mssql_query($qryB);
		}
	}
	else
	{
		$qryA = "UPDATE jest..messages SET viewed=1 WHERE sendto=".(int) $_SESSION['securityid'] ." and viewed!=1;";
		$resA = mssql_query($qryA);
	}
	
}

function listmsg()
{
	$qryA = "SELECT mid,datesent,securityid,msubject,viewed,hidden FROM messages WHERE sendto='".$_SESSION['securityid']."' AND datesent BETWEEN (getdate() - 365) AND getdate() AND hidden=0 ORDER BY viewed,datesent DESC;";
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	if ($nrowsA==0)
	{
		echo "No Messages";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"750px\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\">\n";
		echo "			<table align=\"center\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"4\" align=\"right\"><b>You have <font color=\"red\">".$nrowsA."</font> message(s)</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr class=\"tblhd\">\n";
		echo "					<td align=\"center\"><b>Msg ID</b></td>\n";
		echo "					<td align=\"center\"><b>Date Sent</b></td>\n";
		echo "					<td align=\"left\"><b>Sender</b></td>\n";
		echo "					<td align=\"left\"><b>Subject</b></td>\n";
		echo "					<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				</tr>\n";

		$altc="1";
		while($rowA = mssql_fetch_array($resA))
		{
			$qryB = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowA['securityid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			if ($altc%2)
			{
				$tdc = "white";
			}
			else
			{
				$tdc = "ltgray";
			}

			echo "				<tr class=\"".$tdc."\">\n";
			
			if ($rowA['viewed']==0)
			{
				echo "					<td align=\"center\"><b>" . date('m/d/Y g:iA',strtotime($rowA['datesent'])) . "</b></td>\n";
				echo "					<td align=\"left\"><b>" . $rowB['fname'] . " " . $rowB['lname'] . "</b></td>\n";
				echo "					<td align=\"left\"><b>" . $rowA['msubject'] . "</b></td>\n";
			}
			else
			{
				echo "					<td NOWRAP align=\"center\">" . date('m/d/Y g:iA',strtotime($rowA['datesent'])) . "</td>\n";
				echo "					<td align=\"left\">" . $rowB['fname'] . " " . $rowB['lname'] . "</td>\n";
				echo "					<td align=\"left\">" . $rowA['msubject'] . "</td>\n";
			}

			echo "					<td align=\"right\">\n";
			//echo "						<form method=\"post\">\n";
			//echo "							<input type=\"hidden\" name=\"action\" value=\"message\">\n";
			//echo "							<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			//echo "							<input type=\"hidden\" name=\"mid\" id=\"recid\" value=\"".$rowA['mid']."\">\n";
			echo "							<input class=\"transnb_button\" type=\"image\" src=\"images/folder.gif\" title=\"View\">\n";
			//echo "						</form>\n";
			echo "					</td>\n";
			echo "				</tr>";
		}

		echo "			</table>\n";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>\n";
	}
}

function listsentmsg()
{
	//$qryA = "SELECT mid,datesent,securityid,msubject,viewed,hidden,sendto FROM messages WHERE securityid='".$_SESSION['securityid']."' AND hidden=0 ORDER BY viewed,datesent DESC;";
	$qryA = "SELECT mid,datesent,securityid,msubject,viewed,hidden,sendto FROM messages WHERE securityid='".$_SESSION['securityid']."' ORDER BY viewed,datesent DESC;";
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	//echo $qryA."<br>";
	if ($nrowsA==0)
	{
		echo "No Messages";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"50%\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\">\n";
		echo "			<table align=\"center\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"6\" align=\"right\"><b>You have <font color=\"red\">".$nrowsA."</font> Sent message(s)</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Date Sent</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Sender</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Recipient</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Subject</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Read?</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "				</tr>\n";

		$altc="1";
		while($rowA = mssql_fetch_row($resA))
		{
			$qryB = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowA[2]."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);

			$qryC = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowA[6]."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_row($resC);

			if ($altc%2)
			{
				$tdc = "wh";
				$chkbx= "checkboxwh";
				$altc = "2";
			}
			else
			{
				$tdc = "lg";
				$chkbx= "checkbox";
				$altc = "1";
			}

			if ($rowA[4]==0)
			{
				$b1="<b>";
				$b2="</b>";
			}
			else
			{
				$b1="";
				$b2="";
			}

			echo "				<tr>\n";
			echo "					<td class=\"".$tdc."\" align=\"right\">".$b1.$rowA[1].$b2."</td>\n";
			echo "					<td class=\"".$tdc."\" align=\"left\">".$b1.$rowB[1]." ".$rowB[2].$b2."</td>\n";
			echo "					<td class=\"".$tdc."\" align=\"left\">".$b1.$rowC[1]." ".$rowC[2].$b2."</td>\n";
			echo "					<td class=\"".$tdc."\" align=\"left\">".$b1.$rowA[3].$b2."</td>\n";
			echo "					<td class=\"".$tdc."\" align=\"center\">\n";

			if ($rowA[4]==0)
			{
				echo "<b>No</b>\n";
			}
			else
			{
				echo "Yes\n";
			}

			echo "					</td>\n";
			echo "					<td class=\"".$tdc."\" align=\"right\">\n";
			echo "						<form method=\"post\">\n";
			echo "							<input type=\"hidden\" name=\"action\" value=\"message\">\n";
			echo "							<input type=\"hidden\" name=\"call\" value=\"viewsent\">\n";
			echo "							<input type=\"hidden\" name=\"mid\" value=\"".$rowA[0]."\">\n";
			echo "							<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View\">\n";
			echo "						</form>\n";
			echo "					</td>\n";
			echo "				</tr>";
		}

		echo "			</table>\n";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>\n";
	}
}

function viewmsg()
{
	$qryPRE = "UPDATE messages SET viewed=1 WHERE mid='".$_REQUEST['mid']."';";
	$resPRE = mssql_query($qryPRE);

	$qryA = "SELECT mid,officeid,securityid,type,fid,sendto,msubject,mbody,datesent,dateread,viewed,hidden FROM messages WHERE mid='".$_REQUEST['mid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);

	$qryB = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowA[5]."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_row($resB);

	$qryC = "SELECT securityid,fname,lname,officeid FROM security WHERE securityid='".$rowA[2]."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT officeid,name FROM offices WHERE officeid='".$rowC[3]."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	echo "<table class=\"outer\" align=\"center\" width=\"450px\">\n";
	echo "   <tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table align=\"center\" width=\"100%\">\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"left\"><b>Viewing Message</b></td>\n";
	echo "   				<td align=\"right\">\n";
	echo "   					<table>\n";
	echo "   						<tr>\n";
	echo "								<td align=\"center\">\n";
	echo "									<form method=\"post\">\n";
	echo "										<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "										<input type=\"hidden\" name=\"call\" value=\"reply\">\n";
	echo "										<input type=\"hidden\" name=\"rid\" value=\"".$rowA[2]."\">\n";
	echo "   									<input type=\"hidden\" name=\"msubject\" value=\"".$rowA[6]."\">\n";
	echo "										<input class=\"transnb_button\" type=\"image\" src=\"images/email_go.png\" title=\"Reply\">\n";
	echo "									</form>\n";
	echo "   							</td>\n";
	echo "   							<td align=\"center\">\n";
	echo "									<form method=\"post\">\n";
	echo "										<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "										<input type=\"hidden\" name=\"call\" value=\"delete\">\n";
	echo "										<input type=\"hidden\" name=\"mid\" value=\"".$rowA[0]."\">\n";
	echo "										<input class=\"transnb_button\" type=\"image\" src=\"images/email_delete.png\" title=\"Archive Message\">\n";
	echo "									</form>\n";
	echo "   							</td>\n";
	echo "   						</tr>\n";
	echo "   					</table>\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>Date Sent:</b></td>\n";
	echo "					<td align=\"left\">".$rowA[8]."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>To:</b></td>\n";
	echo "					<td align=\"left\">".$rowB[2].", ".$rowB[1]."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>From:</b></td>\n";
	echo "					<td align=\"left\">".$rowC[2].", ".$rowC[1]." (".$rowD['name'].")</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Subject:</b></td>\n";
	echo "   				<td align=\"left\">\n";
	echo "   					".$rowA[6]."";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Message:</b></td>\n";
	echo "   				<td align=\"left\" width=\"250px\">\n";
	echo "   					".$rowA[7]."\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function newmsg()
{
	$qryA = "SELECT securityid,fname,lname,officeid,slevel FROM security WHERE securityid!='".$_SESSION['securityid']."' and excmess!=1 ORDER BY lname;";
	$resA = mssql_query($qryA);

	if ($_REQUEST['call']=="reply")
	{
		$qryB = "SELECT securityid,fname,lname,officeid FROM security WHERE securityid='".$_REQUEST['rid']."';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_row($resB);
	}

	$qryC = "SELECT securityid,fname,lname,officeid,altoffices FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='89' AND securityid!='".$_SESSION['securityid']."' ORDER BY lname;";
	$resD = mssql_query($qryD);

	$qryE = "SELECT officeid,name FROM offices WHERE officeid='".$rowC[3]."';";
	$resE = mssql_query($qryE);
	$rowE = mssql_fetch_row($resE);

	echo "<table class=\"outer\" align=\"center\" width=\"400px\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\">\n";
	echo "			<form method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"add\">\n";
	echo "			<table align=\"center\" width=\"100%\">\n";
	echo "   			<tr>\n";

	if ($_REQUEST['call']=="reply")
	{
		echo "   				<td colspan=\"2\" align=\"left\"><b>Reply Message</b></td>\n";
	}
	else
	{
		echo "   				<td colspan=\"2\" align=\"left\"><b>Compose Message</b></td>\n";
	}

	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>To:</b></td>\n";
	echo "   				<td align=\"left\">\n";

	if ($_REQUEST['call']=="reply")
	{
		echo $rowB[2].", ".$rowB[1];
		echo "	<input type=\"hidden\" name=\"sendto\" value=\"".$rowB[0]."\">\n";
	}
	else
	{
		echo "						<select tabindex=\"1\" name=\"sendto\">\n";
		echo "							<option value=\"0\"></option>\n";

		while($rowA = mssql_fetch_row($resA))
		{
			$qryF = "SELECT officeid,name FROM offices WHERE officeid='".$rowA[3]."';";
			$resF = mssql_query($qryF);
			$rowF = mssql_fetch_row($resF);

			$slev=explode(",",$rowA[4]);
			if ($slev[6]!=0)
			{
				if ($_SESSION['mlev'] <= 5)
				{
					if ($_SESSION['officeid']==$rowA[3])
					{
						echo "							<option value=\"".$rowA[0]."\">".$rowA[2].", ".$rowA[1]." - ".$rowF[1]."</option>\n";
					}
				}
				elseif ($_SESSION['mlev'] == 6)
				{
					if ($rowC[4]!=0)
					{
						$aoff=explode(",",$rowC[4]);
						if (in_array($rowA[3],$aoff))
						{
							echo "							<option value=\"".$rowA[0]."\">".$rowA[2].", ".$rowA[1]." - ".$rowF[1]."</option>\n";
						}
					}
					else
					{
						if ($_SESSION['officeid']==$rowA[3])
						{
							echo "							<option value=\"".$rowA[0]."\">".$rowA[2].", ".$rowA[1]." - ".$rowF[1]."</option>\n";
						}
					}
				}
				else
				{
					echo "							<option value=\"".$rowA[0]."\">".$rowA[2].", ".$rowA[1]." - ".$rowF[1]."</option>\n";
				}
			}
		}

		if ($_SESSION['mlev'] >= 5 && $_SESSION['officeid']!=89)
		{
			while($rowD = mssql_fetch_row($resD))
			{
				$mslev=explode(",",$rowD[3]);
				if ($mslev[6]!=0)
				{
					echo "							<option value=\"".$rowD[0]."\">".$rowD[2].", ".$rowD[1]." - Management</option>\n";
				}
			}
		}

		echo "						</select>\n";
	}
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>From:</b></td>\n";
	echo "					<td align=\"left\">".$rowC[2].", ".$rowC[1]." - ".$rowE[1]."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Subject:</b></td>\n";
	echo "   				<td align=\"left\">\n";

	if ($_REQUEST['call']=="reply")
	{
		echo "   					<input tabindex=\"2\" type=\"text\" name=\"msubject\" size=\"70\" maxlength=\"64\" value=\"RE: ".$_REQUEST['msubject']."\">\n";
	}
	else
	{
		echo "   					<input tabindex=\"2\" type=\"text\" name=\"msubject\" size=\"70\" maxlength=\"64\">\n";
	}

	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Message:</b></td>\n";
	echo "   				<td align=\"left\">\n";
	echo "   					<textarea tabindex=\"3\" name=\"mbody\" rows=\"10\" cols=\"70\"></textarea>\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td colspan=\"2\" align=\"right\">\n";
	echo "                    <input tabindex=\"4\" class=\"buttondkgrypnl60\" type=\"submit\" value=\"Send\">\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function addmsg()
{
	if (!isset($_REQUEST['sendto'])||empty($_REQUEST['sendto'])||empty($_REQUEST['msubject']))
	{
		newmsg();
		exit;
	}
	else
	{
		$qryA  = "sp_insertmessage ";
		$qryA .= "@officeid='".$_SESSION['officeid']."',";
		$qryA .= "@securityid='".$_SESSION['securityid']."',";
		$qryA .= "@type='prv',";
		$qryA .= "@fid='0',";
		$qryA .= "@sendto='".$_REQUEST['sendto']."',";
		$qryA .= "@msubject='".replacequote($_REQUEST['msubject'])."',";
		$qryA .= "@mbody='".replacequote($_REQUEST['mbody'])."';";
		$resA  = mssql_query($qryA);
		$rowA  = mssql_fetch_row($resA);

		echo "<b>Message Sent!</b><br>";
	}
}

function delmsg()
{
	$qryA = "UPDATE messages SET hidden=1 WHERE mid='".$_REQUEST['mid']."';";
	$resA = mssql_query($qryA);

	listmsg();
}

function fdbkmsg()
{
	$qryA = "SELECT securityid,fname,lname,officeid,slevel FROM security WHERE securityid!='".$_SESSION['securityid']."' ORDER BY lname;";
	$resA = mssql_query($qryA);

	$qryC = "SELECT securityid,fname,lname,officeid,altoffices FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid=89 AND securityid!='".$_SESSION['securityid']."' ORDER BY lname;";
	$resD = mssql_query($qryD);

	$qryE = "SELECT officeid,name FROM offices WHERE officeid='".$rowC[3]."';";
	$resE = mssql_query($qryE);
	$rowE = mssql_fetch_row($resE);

	//$sysadminid=$rowF['FDBK_ADMIN'];
	$sysadminid=SYS_ADMIN;

	echo "			<table align=\"center\" width=\"350px\">\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
	echo "   				<td align=\"left\"><p><b>We welcome Comments and Suggestions on ways to improve this system. Please be constructive and to the point.</b></p><br/> <p><b>Thank You</b></p></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td colspan=\"2\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>To:</b></td>\n";
	echo "   				<td align=\"left\">System Administrator @ BHNM:Active</td>\n";
	echo "				</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>From:</b></td>\n";
	echo "					<td align=\"left\">".$rowC[2].", ".$rowC[1]." @ ".$rowE[1]."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Subject:</b></td>\n";
	echo "   				<td align=\"left\">JMS Feedback</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Message:</b></td>\n";
	echo "   				<td>\n";
	echo "   					<textarea tabindex=\"3\" id=\"ajx_mbody\" rows=\"10\" cols=\"70\"></textarea>\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
}

function addfdbkmsg()
{
	$data=0;
	if (empty($_REQUEST['ajx_mbody']))
	{
		echo 'An error occured.';
	}
	else
	{
		$qry = "SELECT securityid as sid,email FROM security WHERE fdbkmsgs=1;";
		$res = mssql_query($qry);
		
		while($row = mssql_fetch_array($res))
		{
			$rcpt_ar[]=$row['sid'];
		}
		
		foreach ($rcpt_ar as $n => $v)
		{
			$qryA  = "sp_insertmessage ";
			$qryA .= "@officeid='".$_SESSION['officeid']."',";
			$qryA .= "@securityid='".$_SESSION['securityid']."',";
			$qryA .= "@type='prv',";
			$qryA .= "@fid='0',";
			$qryA .= "@sendto='".$v."',";
			$qryA .= "@msubject='".htmlspecialchars($_REQUEST['ajx_msubject'])."',";
			$qryA .= "@mbody='".htmlspecialchars($_REQUEST['ajx_mbody'])."';";
			$resA  = mssql_query($qryA);
		}

		echo "Message Sent<br />Thank You for taking time to provide us with Feedback concerning this System.";
	}
}

function viewsntmsg()
{
	$qryA = "SELECT mid,officeid,securityid,type,fid,sendto,msubject,mbody,datesent,dateread,viewed,hidden FROM messages WHERE mid='".$_REQUEST['mid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	$qryB = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowA['sendto']."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryC = "SELECT securityid,fname,lname,officeid FROM security WHERE securityid='".$rowA['securityid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$qryD = "SELECT officeid,name FROM offices WHERE officeid='".$rowC['officeid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	echo "<table class=\"outer\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table align=\"center\" width=\"100%\">\n";
	echo "   			<tr>\n";
	echo "   				<td colspan=\"2\" align=\"left\"><b>Viewing Sent Message</b></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>Date Sent:</b></td>\n";
	echo "					<td align=\"left\">".$rowA['datesent']."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>To:</b></td>\n";
	echo "					<td align=\"left\">".$rowB['lname'].", ".$rowB['fname']."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>From:</b></td>\n";
	echo "					<td align=\"left\">".$rowC['lname'].", ".$rowC['fname']." (".$rowD['name'].")</td>\n";
	echo "					<td align=\"left\" valign=\"bottom\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Subject:</b></td>\n";
	echo "   				<td align=\"left\">\n";
	echo "   					<input tabindex=\"3\" type=\"text\" size=\"60\" maxlength=\"64\" value=\"".$rowA['msubject']."\">\n";
	echo "   				</td>\n";
	echo "   				<td align=\"left\" valign=\"top\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Message:</b></td>\n";
	echo "   				<td align=\"left\">\n";
	echo "   					<textarea tabindex=\"4\" rows=\"10\" cols=\"65\">".$rowA['mbody']."</textarea>\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}


1;
?>