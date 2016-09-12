<?php

function BaseMatrix()
{
	if (!isset($_SESSION['call'])||$_SESSION['call']=="None")
	{
		echo "<font color=\"red\">Error!</font>\$call not set.";
	}
	elseif ($_SESSION['call']=="list")
	{
		listmsg();
	}
	elseif ($_SESSION['call']=="listsent")
	{
		listsentmsg();
	}
	elseif ($_SESSION['call']=="view")
	{
		viewmsg();
	}
	elseif ($_SESSION['call']=="new")
	{
		newmsg();
	}
	elseif ($_SESSION['call']=="reply")
	{
		newmsg();
	}
	elseif ($_SESSION['call']=="add")
	{
		addmsg();
	}
	elseif ($_SESSION['call']=="edit")
	{
		editmsg();
	}
	elseif ($_SESSION['call']=="delete")
	{
		delmsg();
	}
	elseif ($_SESSION['call']=="new_feedback")
	{
		fdbkmsg();
	}
	elseif ($_SESSION['call']=="add_feedback")
	{
		addfdbkmsg();
	}
	elseif ($_SESSION['call']=="viewsent")
	{
		viewsntmsg();
	}
}

function listmsg()
{
	$qryA = "SELECT
				m.mid,m.datesent,m.securityid,m.msubject,m.mbody,m.viewed,m.hidden,
				(SELECT (fname +' '+lname) FROM security WHERE securityid=m.securityid) as sender
			FROM messages AS m WHERE m.sendto=".$_SESSION['securityid']." AND m.datesent BETWEEN (getdate() - 365) AND getdate() AND m.hidden=0 ORDER BY m.viewed,m.datesent DESC;";
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	if ($nrowsA==0)
	{
		echo "No Messages";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\">\n";
		echo "			<table align=\"center\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" align=\"leftt\"><b>Your Messages</b></td>\n";
		echo "					<td colspan=\"2\" align=\"right\"><b>You have <font color=\"red\">".$nrowsA."</font> message(s)</b></td>\n";
		echo "					<td align=\"right\" valign=\"top\" width=\"20px\">\n";
		echo "						<a href=\"#\" class=\"msgViewAll\"><img src=\"images/folder_table.png\" title=\"Show All Messages\"></a>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "				<tr class=\"tblhd\">\n";
		echo "					<td align=\"center\"><b>Msg ID</b></td>\n";
		echo "					<td align=\"center\"><b>Date Sent</b></td>\n";
		echo "					<td align=\"left\"><b>Sender</b></td>\n";
		echo "					<td align=\"left\"><b>Subject</b></td>\n";
		echo "					<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "				</tr>\n";

		$altc=1;
		while($rowA = mssql_fetch_array($resA))
		{
			$tdc = ($altc%2) ? "even":"odd";
			echo "				<tr class=\"".$tdc."\">\n";
			
			/*
			if ($rowA['viewed']==0)
			{
				echo "					<td align=\"center\" valign=\"top\"><b>".$rowA['mid']."</b></td>\n";
				echo "					<td align=\"center\" valign=\"top\"><b>".date('m/d/Y h:iA',strtotime($rowA['datesent']))."</b></td>\n";
				echo "					<td align=\"left\" valign=\"top\"><b>".$rowA['sender']."</b></td>\n";
				echo "					<td align=\"left\" valign=\"top\" width=\"350px\"><b>".$rowA['msubject']."</b></td>\n";
			}
			else
			{
			*/
				echo "					<td class=\"msgID\" align=\"center\" valign=\"top\">".$rowA['mid']."</td>\n";
				echo "					<td align=\"center\" valign=\"top\">" . date('m/d/Y h:iA',strtotime($rowA['datesent'])) . "</td>\n";
				echo "					<td align=\"left\" valign=\"top\">".$rowA['sender']."</td>\n";
				echo "					<td align=\"left\" class=\"msgText\" valign=\"top\" width=\"400px\">\n";
				echo $rowA['msubject'];
				echo "						<div class=\"msgHidden\" style=\"display: none\">\n";
				echo removequote($rowA['mbody']);
				echo "   						<table>\n";
				echo "   							<tr>\n";
				echo "									<td align=\"center\">\n";
				echo "										<form method=\"post\">\n";
				echo "											<input type=\"hidden\" name=\"action\" value=\"message\">\n";
				echo "											<input type=\"hidden\" name=\"call\" value=\"reply\">\n";
				echo "											<input type=\"hidden\" name=\"rid\" value=\"".$rowA['securityid']."\">\n";
				echo "   										<input type=\"hidden\" name=\"msubject\" value=\"".$rowA['msubject']."\">\n";
				echo "											<input class=\"transnb_button\" type=\"image\" src=\"images/email_go.png\" title=\"Reply\">\n";
				echo "										</form>\n";
				echo "   								</td>\n";
				echo "   								<td align=\"center\">\n";
				echo "										<form method=\"post\">\n";
				echo "											<input type=\"hidden\" name=\"action\" value=\"message\">\n";
				echo "											<input type=\"hidden\" name=\"call\" value=\"delete\">\n";
				echo "											<input type=\"hidden\" name=\"mid\" value=\"".$rowA['mid']."\">\n";
				echo "											<input class=\"transnb_button\" type=\"image\" src=\"images/email_delete.png\" title=\"Archive Message\">\n";
				echo "										</form>\n";
				echo "   								</td>\n";
				echo "   							</tr>\n";
				echo "   						</table>\n";
				echo "							<div class=\"msgReply\" style=\"display: none\"></div>\n";
				echo "						</div>";
				echo "					</td>\n";
			//}

			echo "					<td align=\"right\" valign=\"top\" width=\"20px\">\n";
			echo "						<a href=\"#\" class=\"msgView\"><img class=\"msgImg\" src=\"images/email.png\" title=\"View\"></a>\n";
			echo "					</td>\n";
			echo "				</tr>";
			$altc++;
		}

		echo "			</table>\n";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>\n";
	}
}

function listsentmsg()
{
	$qryA = "SELECT
				M.mid,M.datesent,M.securityid,M.msubject,M.mbody,M.viewed,M.hidden,M.sendto,
				(SELECT (fname +' '+lname) FROM security WHERE securityid=M.securityid) as sender,
				(SELECT (fname +' '+lname) FROM security WHERE securityid=M.sendto) as recipient
			FROM messages as M WHERE M.securityid=".(int) $_SESSION['securityid']." ORDER BY M.viewed,M.datesent DESC;";
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	if ($nrowsA==0)
	{
		echo "No Messages";
	}
	else
	{
		echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\">\n";
		echo "			<table align=\"center\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"4\" align=\"leftt\"><b>Your Sent Messages</b></td>\n";
		echo "					<td colspan=\"2\" align=\"right\"><b>You have <font color=\"red\">".$nrowsA."</font> Sent message(s)</b></td>\n";
		echo "					<td align=\"right\" valign=\"top\" width=\"20px\">\n";
		echo "						<img src=\"images/pixel.gif\">\n";
		//echo "						<a href=\"#\" class=\"msgViewAll\"><img src=\"images/folder_table.png\" title=\"Show All Messages\"></a>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Msg ID</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Date Sent</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Sender</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Recipient</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"left\"><b>Subject</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"><b>Read?</b></td>\n";
		echo "					<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "				</tr>\n";

		$altc=1;
		while($rowA = mssql_fetch_array($resA))
		{
			$tdc=($altc%2)?"even":"odd";
			$b1=($rowA['viewed']==0)?'<b>':'';
			$b2=($rowA['viewed']==0)?'</b>':'';
			$vwd=($rowA['viewed']==0)?'No':'Yes';
			echo "				<tr class=\"".$tdc."\">\n";
			echo "					<td align=\"center\" valign=\"top\">".$b1.$rowA['mid'].$b2."</td>\n";
			echo "					<td align=\"center\" valign=\"top\">".$b1.date('m/d/Y h:iA',strtotime($rowA['datesent'])).$b2."</td>\n";
			echo "					<td align=\"left\" valign=\"top\">".$b1.$rowA['sender'].$b2."</td>\n";
			echo "					<td align=\"left\" valign=\"top\">".$b1.$rowA['recipient'].$b2."</td>\n";
			echo "					<td align=\"left\" class=\"msgText\" valign=\"top\" width=\"400px\">\n";
			echo $b1.$rowA['msubject'].$b2;
			echo "						<div class=\"msgHidden\" style=\"display: none\">\n";
			echo $rowA['mbody'];
			echo "						</div>";
			echo "					</td>\n";
			echo "					<td align=\"center\" valign=\"top\">".$vwd."</td>\n";
			echo "					<td align=\"right\" valign=\"top\" width=\"20px\">\n";
			echo "						<a href=\"#\" class=\"msgView\"><img class=\"msgImg\" src=\"images/email.png\" title=\"View\"></a>\n";
			echo "					</td>\n";
			echo "				</tr>";
			$altc++;
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

	echo "<table class=\"outer\" align=\"center\" width=\"350px\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\">\n";
	echo "			<form  method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"add_feedback\">\n";
	echo "			<table align=\"center\" width=\"100%\">\n";
	echo "   			<tr>\n";
	echo "   				<td colspan=\"2\" class=\"ltgray_und\" align=\"left\"><b>Feedback</b></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td colspan=\"2\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "   				<td align=\"left\"><p><b>We welcome Comments and Suggestions on ways to improve this system. Please be constructive and to the point.</b></p><br/> <p><b>Thank You</b></p></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td colspan=\"2\" align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>To:</b></td>\n";
	echo "   				<td align=\"left\">System Administrator @ BHNM:Active\n";
	echo "						<input type=\"hidden\" name=\"sendto\" value=\"".$sysadminid."\">\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\"><b>From:</b></td>\n";
	echo "					<td align=\"left\">".$rowC[2].", ".$rowC[1]." @ ".$rowE[1]."</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Subject:</b></td>\n";
	
	if (!empty($_REQUEST['subject']))
	{
		echo "   				<td align=\"left\">".trim($_REQUEST['subject'])."\n";
		echo "   					<input type=\"hidden\" name=\"msubject\" value=\"".trim($_REQUEST['subject'])."\">\n";
		echo "   				</td>\n";
	}
	else
	{
		echo "   				<td align=\"left\">JMS Feedback\n";
		echo "   					<input type=\"hidden\" name=\"msubject\" value=\"Job Management System Feedback\">\n";
		echo "   				</td>\n";
	}
	
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td align=\"right\" valign=\"top\"><b>Message:</b></td>\n";
	echo "   				<td>\n";
	echo "   					<textarea tabindex=\"3\" name=\"mbody\" rows=\"10\" cols=\"70\"></textarea>\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "   				<td colspan=\"2\" align=\"right\">\n";
	echo "                    <input tabindex=\"4\" class=\"buttondkgrypnl80\" type=\"submit\" value=\"Send\">\n";
	echo "   				</td>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function addfdbkmsg()
{
	if (!isset($_REQUEST['sendto'])||empty($_REQUEST['sendto'])||empty($_REQUEST['msubject']))
	{
		//newmsg();
		exit;
	}
	else
	{
		$rcpt_ar=array();
		$qry = "SELECT securityid as sid,email FROM security WHERE fdbkmsgs=1;";
		$res = mssql_query($qry);
		
		while($row = mssql_fetch_array($res))
		{
			$rcpt_ar[]=$row['sid'];
		}
		
		foreach ($rcpt_ar as $n=>$v)
		{
			$qryA  = "sp_insertmessage ";
			$qryA .= "@officeid='".$_SESSION['officeid']."',";
			$qryA .= "@securityid='".$_SESSION['securityid']."',";
			$qryA .= "@type='prv',";
			$qryA .= "@fid='0',";
			$qryA .= "@sendto='".$v."',";
			$qryA .= "@msubject='".replacequote($_REQUEST['msubject'])."',";
			$qryA .= "@mbody='".replacequote($_REQUEST['mbody'])."';";
			$resA  = mssql_query($qryA);
		}

		echo "<b>Thank You for taking time to provide us with Feedback concerning this System.</b><br>";
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