<?php

//echo "Office Comment File entry<br>";
function base_matrix()
{
	
    if (!isset($_REQUEST['subq']))
    {
        //echo "Listing 1<br>";
        listcomment();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='commentlist')
    {
        //echo "Listing 2<br>";
        listcomment();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='addcomment')
    {
        //echo "Adding<br>";
        addcomment();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='deletecomment')
    {
        deletecomment();
    }
	elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='restorecomment')
    {
        restorecomment();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='editcomment')
    {
        editcomment();
    }
	elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='changeoffice')
    {
        changeoffice();
    }
}

function changeoffice()
{
	listcomment();
}

function listcomment()
{
    $qry	= "SELECT officeid,admstaff FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
	
	/*if ($_SESSION['securityid']==26)
	{
		echo 'TEST: ';
		echo var_dump($_SESSION['admin_offs']);
		echo 'ROFF: '.$row['officeid'];
		//print_r($_SESSION['admin_offs']);
	}*/
    
    if (!in_array($row['officeid'],$_SESSION['admin_offs']) && $row['admstaff'] > 0)
    {
        echo "You do not have the appropriate security access to view this resource<br>";
    }
    else
    {
		$sec_ar=array(3=>'High',2=>'Medium',1=>'Low');
		
        if (isset($_REQUEST['showall']) && $_REQUEST['showall']==1)
        {
            $qry0	= "SELECT O.*,(select lname from jest..security where securityid=O.sid) as lname FROM office_comments AS O WHERE oid='".$_REQUEST['oid']."' and seclevel <= ".$row['admstaff']." order by adate desc;";
        }
        else
        {
            $qry0	= "SELECT O.*,(select lname from jest..security where securityid=O.sid) as lname  FROM office_comments AS O WHERE oid='".$_REQUEST['oid']."' and seclevel <= ".$row['admstaff']." and hidden!=1 order by adate desc;";
        }
        
        $res0	= mssql_query($qry0);
        $nrow0	= mssql_num_rows($res0);
        
		if (isset($_REQUEST['oid']) || $_SESSION['officeid']==89)
		{
			$oid=$_REQUEST['oid'];
		}
		else
		{
			$oid=$_SESSION['officeid'];
		}
		
        $qry1	= "SELECT officeid,name FROM jest..offices WHERE officeid='".$oid."';";
        $res1	= mssql_query($qry1);
        $row1	= mssql_fetch_array($res1);
        
        $uid  =md5(session_id().time()).".".$_SESSION['securityid'];
		
		echo "<div id=\"masterdiv\">";
        echo "<table class=\"outer\" width=\"750\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\">\n";
		echo "			<table align=\"center\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"6\" align=\"left\">\n";
		echo "                      <form name=\"changeoffice\" method=\"post\">\n";
        echo "                      <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "                      <input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "                      <input type=\"hidden\" name=\"subq\" value=\"changeoffice\">\n";
		echo "			            <table align=\"center\" width=\"100%\">\n";
        echo "			            	<tr>\n";
        echo "					            <td align=\"left\" colspan=\"2\">\n";
		echo "									<b>Office Comment System</b> ";
		echo "					            </td>\n";
		echo "					            <td align=\"right\">\n";
		
		if (in_array($row['officeid'],$_SESSION['admin_offs']))
		{
			$qry2	= "SELECT officeid,name FROM jest..offices WHERE active=1 order by grouping,name;";
			$res2	= mssql_query($qry2);
			
			echo "					    Select Office: \n";
			echo "						<select name=\"oid\" onChange=\"this.form.submit();\">\n";
			
			while ($row2 = mssql_fetch_array($res2))
			{
				if (isset($_REQUEST['oid']) && $_REQUEST['oid']==$row2['officeid'])
				{
					echo "							<option value=\"".$row2['officeid']."\" SELECTED>".$row2['name']."</option>\n";
				}
				else
				{
					echo "							<option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
				}
			}
			
			echo "						</select>\n";
		}

		echo "			        			</td>\n";
		echo "                              <td width=\"20\" align=\"center\"><input class=\"transnb\" type=\"image\" src=\"images/arrow_refresh_small.png\" alt=\"Refresh\"></td>\n";
		echo "			        		</tr>\n";
		echo "			            	<tr>\n";
        //echo "					            <td></td>\n";
		echo "											<td align=\"right\">\n";
		
		if ($nrow0 > 0)
		{
			echo "												<div onclick=\"SwitchMenu('addcomment'); document.commentlist.addctext.focus();\"><img src=\"images/bullet_toggle_plus.png\" title=\"Add Comment\"></div>";
		}
		
		echo "											</td>";
		echo "											<td align=\"left\"><b>Add Comment</b></td>\n";
		
		if (isset($_REQUEST['showall']) && $_REQUEST['showall']==1)
        {
			echo "                              <td align=\"right\">Show Archived <input class=\"transnb\" type=\"checkbox\" name=\"showall\" value=\"1\" alt=\"Show Archived\" CHECKED></td>\n";
		}
		else
		{
			echo "                              <td align=\"right\">Show Archived <input class=\"transnb\" type=\"checkbox\" name=\"showall\" value=\"1\" alt=\"Show Archived\"></td>\n";
		}
		
		echo "                              <td></td>\n";
		echo "			        		</tr>\n";
		echo "			            </table>\n";
		echo "                      </form>\n";
        echo "			        </td>\n";
		echo "				</tr>\n";
        echo "				<tr>\n";
		echo "					<td colspan=\"6\" align=\"left\">\n";
        //echo "			            <table align=\"center\" width=\"100%\">\n";
		echo "                      <form name=\"commentlist\" method=\"post\">\n";
        echo "                      <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "                      <input type=\"hidden\" name=\"call\" value=\"office\">\n";
		echo "                      <input type=\"hidden\" name=\"subq\" value=\"addcomment\">\n";
		echo "         			    <input type=\"hidden\" name=\"oid\" value=\"".$row1['officeid']."\">\n";
        echo "                      <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
		echo "			            <table align=\"center\" width=\"100%\">\n";
        echo "							<tr>\n";
		echo "								<td align=\"right\"></td>\n";
		echo "								<td align=\"left\">\n";
		echo "									<table>\n";
		echo "										<tr>\n";
		/*echo "											<td align=\"left\"><b>Add Comment</b></td>\n";
		echo "											<td align=\"left\">\n";
		
		if ($nrow0 > 0)
		{
			echo "												<div onclick=\"SwitchMenu('addcomment'); document.commentlist.addctext.focus();\"><img src=\"images/bullet_toggle_plus.png\" title=\"Add Comment\"></div>";
		}
		
		echo "											</td>";*/
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>";
		echo "							</tr>\n";
        echo "			            	<tr>\n";
        echo "					            <td align=\"left\" colspan=\"2\">\n";
		
		if ($nrow0 > 0)
		{
			echo "        							<span class=\"submenu\" id=\"addcomment\">\n";
		}
		
		echo "									<table width=\"100\">\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\" valign=\"top\"></td>\n";
		echo "											<td align=\"left\" colspan=\"2\">\n";
        echo "                                  			<textarea id=\"commentttxt\" name=\"addctext\" cols=\"100\" rows=\"5\"></textarea>\n";
		echo "											</td>";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\"><b>Security</b></td>\n";
		echo "											<td align=\"left\">\n";
		
		if ($row['admstaff'] > 1)
		{
			echo "						<select name=\"seclevel\">\n";
			
			if ($row['admstaff']==3)
			{
				echo "      									<option value=\"3\" SELECTED>High</option>\n";
				echo "      									<option value=\"2\">Medium</option>\n";
				echo "      									<option value=\"1\">Low</option>\n";
			}
			elseif ($row['admstaff']==2)
			{
				echo "      									<option value=\"3\">High</option>\n";
				echo "      									<option value=\"2\" SELECTED>Medium</option>\n";
				echo "      									<option value=\"1\">Low</option>\n";
			}
			elseif ($row['admstaff']==1)
			{
				echo "      									<option value=\"3\">High</option>\n";
				echo "      									<option value=\"2\">Medium</option>\n";
				echo "      									<option value=\"1\" SELECTED>Low</option>\n";
			}
			
			echo "						</select>\n";
		}
		else
		{
			echo "                      <input type=\"hidden\" name=\"seclevel\" value=\"".$row['seclevel']."\">\n";
		}
		
		echo "											</td>";
		echo "					        			    <td align=\"right\">\n";
        echo "		                    			        <input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Office Comment\">\n";
        echo "                          			    </td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		
		if ($nrow0 > 0)
		{
			echo "									</span>\n";
		}
		
        echo "                              </td>\n";
		echo "				            </tr>\n";
        echo "			            </table>\n";
        echo "                      </form>\n";
		
		
        echo "			        </td>\n";
		echo "				</tr>\n";
		
		if ($nrow0 > 0)
		{
			echo "				<tr>\n";
			echo "					<td colspan=\"4\" align=\"left\"><b>Comment History</td>\n";
			echo "					<td colspan=\"2\" align=\"right\"><b><font color=\"blue\">".$nrow0."</font></b> comment(s)</td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td class=\"ltgray_und\" align=\"right\" width=\"25px\"></td>\n";
			echo "					<td class=\"ltgray_und\" align=\"left\" width=\"90px\"><b>Date</b></td>\n";
			echo "					<td class=\"ltgray_und\" align=\"left\" width=\"85px\"><b>Posted by</b></td>\n";
			echo "					<td class=\"ltgray_und\" align=\"center\" width=\"75px\"><b>Level</b></td>\n";
			echo "					<td class=\"ltgray_und\" align=\"left\"><b>Comment</b></td>\n";
			echo "					<td class=\"ltgray_und\" align=\"right\"></td>\n";
			echo "				</tr>\n";
			
			$ccnt=1;
			while($row0 = mssql_fetch_array($res0))
			{
				if ($row0['hidden']==1)
				{
					$tbg = 'lightcoral';
				}
				else
				{
					if ($ccnt%2)
					{
						$tbg = 'white';
					}
					else
					{
						$tbg = 'ltgray_none';
					}
				}
				
				echo "				<tr>\n";
				echo "					<td class=\"".$tbg."\" align=\"right\" valign=\"top\" width=\"20px\">".$ccnt++.".</td>\n";
				echo "					<td class=\"".$tbg."\" align=\"left\" valign=\"top\" width=\"90px\">".date('m/d/y h:iA',strtotime($row0['adate']))."</td>\n";
				echo "					<td class=\"".$tbg."\" align=\"left\" valign=\"top\" width=\"85px\">".$row0['lname']."</td>\n";
				echo "					<td class=\"".$tbg."\" align=\"center\" valign=\"top\" width=\"75px\">\n";
				
				if ($row['admstaff'] >= 2)
				{
					echo "	                    <form name=\"editcomment\" method=\"post\">\n";
					echo "	                	    <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
					echo "	                    	<input type=\"hidden\" name=\"call\" value=\"office\">\n";
					echo "	                    	<input type=\"hidden\" name=\"subq\" value=\"editcomment\">\n";
					echo "                          <input type=\"hidden\" name=\"oid\" value=\"".$row0['oid']."\">\n";
					echo "	                    	<input type=\"hidden\" name=\"offcntid\" value=\"".$row0['offcntid']."\">\n";
					echo "							<select name=\"setseclev\" onChange=\"this.form.submit();\">\n";
						
					foreach ($sec_ar as $n => $v)
					{
						if ($row0['seclevel']==$n)
						{
							echo "<option value=\"".$n."\" SELECTED>".$v."</option>\n";
						}
						else
						{
							echo "<option value=\"".$n."\">".$v."</option>\n";
						}
					}
					
					echo "							</select>\n";
					echo "	                    </form>\n";
				}
				else
				{
					if ($row0['seclevel']==0)
					{
						echo "None\n";
					}
					elseif ($row0['seclevel']==1)
					{
						echo "Low\n";
					}
					elseif ($row0['seclevel']==2)
					{
						echo "Med\n";
					}
					elseif ($row0['seclevel']==3)
					{
						echo "High\n";
					}
				}
	
				echo "					</td>\n";
				echo "					<td class=\"".$tbg."\" align=\"left\" valign=\"top\" width=\"300px\">".unserialize($row0['ctext'])."</td>\n";
				echo "					<td class=\"".$tbg."\" align=\"right\" valign=\"top\" width=\"10px\">\n";
				
				if ($_SESSION['mlev'] >= 9 || $_SESSION['securityid']==$row0['sid'])
				{
					if ($row0['hidden']==1)
					{
						echo "	                    <form id=\"restorecomment\" name=\"restorecomment\" method=\"post\" onSubmit=\"return ConfirmRestore();\">\n";
						echo "	                	    <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
						echo "	                    	<input type=\"hidden\" name=\"call\" value=\"office\">\n";
						echo "	                    	<input type=\"hidden\" name=\"subq\" value=\"restorecomment\">\n";
						
						if (isset($_REQUEST['showall']) && $_REQUEST['showall']==1)
						{
							echo "	                    	<input type=\"hidden\" name=\"showall\" value=\"1\">\n";
						}
						
						echo "                          <input type=\"hidden\" name=\"oid\" value=\"".$row0['oid']."\">\n";
						echo "	                    	<input type=\"hidden\" name=\"offcntid\" value=\"".$row0['offcntid']."\">\n";
						echo "		                    <input class=\"transnb\" type=\"image\" src=\"images/comment_add.png\" alt=\"Restore Comment\">\n";
						echo "	                    </form>\n";	
					}
					else
					{
						echo "	                    <form id=\"deletecomment\" name=\"deletecomment\" method=\"post\" onSubmit=\"return ConfirmDelete();\">\n";
						echo "	                	    <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
						echo "	                    	<input type=\"hidden\" name=\"call\" value=\"office\">\n";
						echo "	                    	<input type=\"hidden\" name=\"subq\" value=\"deletecomment\">\n";
						
						if (isset($_REQUEST['showall']) && $_REQUEST['showall']==1)
						{
							echo "	                    	<input type=\"hidden\" name=\"showall\" value=\"1\">\n";
						}
						
						echo "                          <input type=\"hidden\" name=\"oid\" value=\"".$row0['oid']."\">\n";
						echo "	                    	<input type=\"hidden\" name=\"offcntid\" value=\"".$row0['offcntid']."\">\n";
						echo "		                    <input class=\"transnb\" type=\"image\" src=\"images/comment_delete.png\" alt=\"Archive Comment\">\n";
						echo "	                    </form>\n";	
					}
				}
				
				echo "                  </td>\n";
				echo "				</tr>\n";    
			}		
			
			$qryZ	= "INSERT INTO jest_stats..office_comment_views (oid,sid,vdate) VALUES (".$oid.",".$_SESSION['securityid'].",getdate());";
			$resZ	= mssql_query($qryZ);
		}
		
		echo "			</table>\n";
		echo "      </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "</div>\n";
    }
}

function addcomment()
{
    error_reporting(E_ALL);
    $qry	= "SELECT officeid,admstaff FROM jest..security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
    
    $qryA	= "SELECT uid FROM jest..office_comments WHERE uid='".$_REQUEST['uid']."';";
	$resA	= mssql_query($qryA);
	$nrowA	= mssql_num_rows($resA);
    
    if (!in_array($row['officeid'],$_SESSION['admin_offs']) && $row['admstaff'] > 0)
    {
        echo "<b>You do not have the appropriate security access to use this resource</b><br>";
    }
    elseif ($nrowA > 0)
    {
        echo "<b>This message has already been added</b><br>";
    }
	elseif (strlen($_REQUEST['addctext']) < 3)
    {
        echo "<b>This message has already been added</b><br>";
    }
    else
    {
        $qry0	 = "INSERT INTO jest..office_comments (uid,oid,sid,ctext,seclevel) VALUES ";
        $qry0	.= "('".$_REQUEST['uid']."',".$_REQUEST['oid'].",".$_SESSION['securityid'].",'".replacequote(serialize($_REQUEST['addctext']))."',".$_REQUEST['seclevel'].")";
        $res0	 = mssql_query($qry0);
		
		$qry0a	= "SELECT securityid as sid,officeid,email FROM jest..security WHERE admstaff >= ".$_REQUEST['seclevel']." and substring(slevel,13,1) >= 1 order by admstaff desc,lname asc;";
		$res0a	= mssql_query($qry0a);
		$nrow0a	= mssql_num_rows($res0a);
		
		//echo $qry0a.'<br>';
		if ($nrow0a > 0 && $_SESSION['securityid']==26)
		{
			$qry0b	= "SELECT officeid,name FROM jest..offices WHERE officeid = ".$_REQUEST['oid'].";";
			$res0b	= mssql_query($qry0b);
			$row0b = mssql_fetch_array($res0b);
			
			$qry0c	= "SELECT securityid,fname,lname FROM jest..security WHERE securityid = ".$_SESSION['securityid'].";";
			$res0c	= mssql_query($qry0c);
			$row0c = mssql_fetch_array($res0c);
			
			//$adm_ar	= array();
			
			$adm_ar[]	= array(26,89,'thelton@bluehaven.com');
			/*
			while ($row0a = mssql_fetch_array($res0a))
			{
				$adm_ar[]=array($row0a['sid'],$row0a['oid'],$row0a['email']);
			}
			*/
			
			foreach ($adm_ar as $na => $nv)
			{
				$to	 	  = $nv[2];
				$sub	  = "JMS Office Comment Created - ".$row0b['name']." - Security Level ".$_REQUEST['seclevel'];
				$mess	  = "This is an automated message notifying you a comment was left in the JMS Office Commenting System. \r\n";
				$mess	 .= "Do Not Reply to this message.\r\n";

				mail_out($to,$sub,$mess);
				
				//echo $to."<br>";
				//echo $sub."<br>";
				//echo $mess."<br>";
			}
		}
		
		//EMail out
		/*
		if (isset($_REQUEST['notify']) && $_REQUEST['notify'] >= 1)
		{
			echo $qry0."<br>";
		}
		*/
    }

    listcomment();
}

function deletecomment()
{
    $qry	= "SELECT officeid,admstaff FROM jest..security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
    
    if (!in_array($row['officeid'],$_SESSION['admin_offs']) && $row['admstaff'] > 0)
    {
        echo "You do not have the appropriate security access to use this resource<br>";
    }
    else
    {
        $qry0	 = "UPDATE jest..office_comments SET hidden=1,hdate=getdate(),hiddenby=".$_SESSION['securityid']." WHERE offcntid=".$_REQUEST['offcntid'].";";
        $res0	= mssql_query($qry0);
    }
    
    listcomment();
    
}

function restorecomment()
{
    $qry	= "SELECT officeid,admstaff FROM jest..security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
    
    if (!in_array($row['officeid'],$_SESSION['admin_offs']) && $row['admstaff'] > 0)
    {
        echo "You do not have the appropriate security access to use this resource<br>";
    }
    else
    {
        $qry0	 = "UPDATE jest..office_comments SET hidden=0,hdate=getdate(),hiddenby=".$_SESSION['securityid']." WHERE offcntid=".$_REQUEST['offcntid'].";";
        $res0	= mssql_query($qry0);
    }
    
    listcomment();
    
}

function editcomment()
{
    $qry	= "SELECT officeid,admstaff FROM jest..security WHERE securityid='".$_SESSION['securityid']."' and substring(slevel,13,1) >= 1;";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
    
    if (!in_array($row['officeid'],$_SESSION['admin_offs']) && $row['admstaff'] > 0)
    {
        echo "You do not have the appropriate security access to view this resource<br>";
    }
	else
    {
        $qry0	 = "UPDATE jest..office_comments SET seclevel=".$_REQUEST['setseclev']." WHERE offcntid=".$_REQUEST['offcntid'].";";
        $res0	= mssql_query($qry0);
    }
    
	listcomment();
}

?>