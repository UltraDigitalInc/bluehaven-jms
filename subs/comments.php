<?php

session_start();

if (!isset($_SESSION['ifcid']) || !is_numeric($_SESSION['ifcid']))
{
	exit;
}
	
function listcomments()
{
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	//print_r($_SESSION);
	
	include('../connect_db.php');

	$qryL = "SELECT * FROM chistory WHERE custid='".$_SESSION['ifcid']."' ORDER BY mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);
	
	echo "<html>\n";
	echo "<head>\n";
	?>
	
	<link rel="stylesheet" type="text/css" href="../yui/build/reset-fonts-grids/reset-fonts-grids.css">
			
	<?php
	echo "   <link rel=\"stylesheet\" type=\"text/css\" href=\"../bh_embed.css\" />\n";
	echo "</head>\n";
	echo "   <body bgcolor=\"#B9D3EE\">\n";
	
	?>
	
	<script type="text/javascript">

	function ConfirmDeleteComment()
	{
		var agree = confirm('You are are attempting to Delete this Comment\n\nClick OK to continue or CANCEL stop the Delete process');
	
		if (agree)
		{
			return true;
		}
		
		return false;
	}
	
	</script>
	
	<?php

    if ($nrowL > 0)
    {
        echo "<table align=\"center\" width=\"100%\">\n";
        echo "   <tr>\n";
        echo "      <td align=\"left\" class=\"gray\" width=\"40\"><b>Date</b></td>\n";
        echo "      <td align=\"left\" class=\"gray\" width=\"60\"><b>Name</b></td>\n";
        echo "      <td align=\"center\" class=\"gray\" width=\"25\"><b>Stage</b></td>\n";
        echo "      <td align=\"center\" class=\"gray\" width=\"25\"><b>Ticket</b></td>\n";
        echo "      <td align=\"left\" class=\"gray\" width=\"300\"><b>Comments</b></td>\n";
        echo "      <td align=\"center\" class=\"gray\" width=\"20\"><img src=\"../images/pixel.gif\"></td>\n";
        echo "   </tr>\n";
    
        $cmntcnt=0;
        while ($rowL = mssql_fetch_array($resL))
        {
            $cmntcnt++;
            $qryLa = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowL['secid']."';";
            $resLa = mssql_query($qryLa);
            $rowLa = mssql_fetch_array($resLa);
            
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
                $cmt_tbg="ltred_und";
            }
            elseif ($rowL['act']=="Followup")
            {
                $stage="<div title=\"Followup\">FL</div>";
                $cmt_tbg="ltred_und";
            }
            elseif ($rowL['act']=="Resolved")
            {
                $stage="<div title=\"Resolved\">RS</div>";
                $cmt_tbg="ltgrn_und";
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
    
            echo "   <tr>\n";
            echo "      <td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\" NOWRAP>".date('m/d/y g:ia',strtotime($rowL['mdate']))."</td>\n";
            echo "      <td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\" NOWRAP>".substr($rowLa['fname'],0,1)." ".substr($rowLa['lname'],0,6)."</td>\n";
            echo "      <td align=\"center\" valign=\"top\" class=\"".$cmt_tbg."\" NOWRAP>".$stage."</td>\n";
            echo "      <td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\">\n";
        
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
    
            echo "		</td>\n";
            echo "      <td align=\"left\" width=\"300px\" class=\"".$cmt_tbg."\">\n";
            
            echo htmlspecialchars_decode(preg_replace('/=A0/','',$rowL['mtext']));
    
            echo "		</td>\n";
            echo "      <td align=\"center\" valign=\"top\" width=\"20px\" class=\"".$cmt_tbg."\">\n";
            
            if (isset($_SESSION['securityid']) && ($_SESSION['securityid']==26 ||$_SESSION['securityid']==332))
            {
                if ($rowL['complaint']!=1 && $rowL['followup']!=1 && $rowL['resolved']!=1)
                {
                    echo "		<form method=\"post\">\n";
                    echo "		<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
                    echo "		<input type=\"hidden\" name=\"call\" value=\"deletecmnt\">\n";
                    echo "		<input type=\"hidden\" name=\"chid\" value=\"".$rowL['id']."\">\n";
                    echo "		<input class=\"transnb\" type=\"image\" src=\"../images/action_delete.gif\" alt=\"Delete Comment\" onClick=\"return ConfirmDeleteComment();\">\n";
                    echo "		</form>\n";
                }
            }
            else
            {
                echo "<img src=\"../images/pixel.gif\">";
            }
            
            echo "		</td>\n";
            echo "   </tr>\n";
        }
    
        echo "</table>\n";
    }
    
	echo "   </body>\n";
	echo "</html>\n";
}

function deletecmnt()
{
	include('../connect_db.php');
	//ini_set('display_errors','On');
	//error_reporting(E_ALL);
	$qry = "DELETE FROM chistory WHERE id='".$_REQUEST['chid']."';";
	$res = mssql_query($qry);
}

function procemail()
{
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	
	//echo 'IN';
	include('../connect_db.php');
	include('../common_func.php');
	
	if (isset($_REQUEST['chistory']) && $_REQUEST['chistory']==1)
	{
		$chistory=true;
	}
	else
	{
		$chistory=false;
	}
 
	if (!isset($_SESSION['et_uid']) && isset($_REQUEST['et_uid']))
	{
		if (isset($_REQUEST['etcid']) && isset($_REQUEST['etid']) && count($_REQUEST['etcid']) > 0 && $_REQUEST['etid'] != 0)
		{
			$qry = "SELECT * FROM jest..EmailTemplate WHERE etid='".$_REQUEST['etid']."';";
			$res = mssql_query($qry);
			$row = mssql_fetch_array($res);
			$nrow= mssql_num_rows($res);
			
			$emcnt=1;
			
			if (!$chistory)
			{
				echo "<table class=\"outer\" width=\"600\">\n";
				echo "<tr><td class=\"gray_und\" colspan=\"2\"><b>Email Results</b></td></tr>\n";
			}
			
			foreach ($_REQUEST['etcid'] as $n1 => $v1)
			{
				$qry1 = "SELECT cid,officeid,cfname,clname,cemail,stage,apptmnt,callback,securityid,opt1,opt2 FROM jest..cinfo WHERE cid='".$v1."';";
				$res1 = mssql_query($qry1);
				$row1 = mssql_fetch_array($res1);
				$nrow1= mssql_num_rows($res1);

				$qry2 = "SELECT esid,sdate FROM jest..EmailTracking WHERE cid='".$v1."' and tid=".$_REQUEST['etid']." and active=1;";
				$res2 = mssql_query($qry2);
				$row2 = mssql_fetch_array($res2);
				
				if ($_SESSION['emailtemplates'] >= 6)
				{
					$sendauth=true;
				}
				else
				{
					if (mssql_num_rows($res2) <= $row['sendallow'])
					{
					    $sendauth=true;
					}
					else
					{
					    $sendauth=false;
					}
					//$sendauth= mssql_num_rows($res2);
				}
				
				if ($nrow1 > 0 && $row1['opt1']==0 && $sendauth)
				{
					$erecp		=trim($row1['cemail']);
					//$erecp		='thelton@corp.bluehaven.com';
					$SMTPdebug	=1;
					$corpname	='Blue Haven Pools & Spas';
					
					if (isset($v1) && $v1!=0)
					{						
						$cfname=$row1['cfname'];
						$clname=$row1['clname'];
						$cemail=$row1['cemail'];
						$apptmnt=$rowB['apptmnt'];
						$cname=$cfname." ".$clname." <".$cemail.">";
					}
					else
					{
						$cfname='John';
						$clname='Customer';
						$cemail='customer@anywhere.com';
						$apptmnt='1/1/1970 12:00 AM';
						$cname=htmlspecialchars($cfname." ".$clname." <".$cemail.">");
					}
					
					if (isset($rowB['officeid']) && $rowB['officeid']!=0)
					{
					    //echo 'From Office<br>';
					    $qryC = "SELECT O.phone,O.gm,O.am,(select fname from jest..security where securityid=O.gm) as ogmfn,(select lname from jest..security where securityid=O.gm) as ogmln FROM jest..offices as O WHERE O.officeid = ".$rowB['officeid'].";";
					}
					else
					{
					    //echo 'From Corporate<br>';
					    $qryC = "SELECT O.phone,O.gm,O.am,(select fname from jest..security where securityid=O.gm) as ogmfn,(select lname from jest..security where securityid=O.gm) as ogmln FROM jest..offices as O WHERE O.officeid = ".$_SESSION['officeid'].";";
					}
					
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);
					    
					$ophone =trim($rowC['phone']);
					$ogmfull=trim($rowC['ogmfn']).' '.trim($rowC['ogmln']);
					
					if (isset($row1['securityid']) && $row1['securityid']!=0)
					{
						$qryD = "SELECT fname,lname FROM jest..security WHERE securityid = ".$row1['securityid'].";";
						$resD = mssql_query($qryD);
						$rowD = mssql_fetch_array($resD);
						
						$esender=$rowD['fname']." ".$rowD['lname'];
					}
					else
					{
						$esender='';
					}
					
					//$srch_ar=array(0=>'/CUSTOMERFULLNAME/',1=>'/CUSTOMERFIRSTNAME/',2=>'/CUSTOMERLASTNAME/',3=>'/CUSTOMEREMAILADDRESS/',4=>'/OFFICEPHONENUMBER/',5=>'/GMFULLNAME/',6=>'/SALESREPFULLNAME/',7=>'/CORPORATEFULLNAME/');
					//$res_ar =array(0=>$cname,1=>$cfname,2=>$clname,3=>$cemail,4=>$ophone,5=>$ogmfull,6=>$esender,7=>$corpname);
					$srch_ar=array(
							0=>'/CUSTOMERFULLNAME/',
							1=>'/CUSTOMERFIRSTNAME/',
							2=>'/CUSTOMERLASTNAME/',
							3=>'/CUSTOMEREMAILADDRESS/',
							4=>'/OFFICEPHONENUMBER/',
							5=>'/GMFULLNAME/',
							6=>'/SALESREPFULLNAME/',
							7=>'/CORPORATEFULLNAME/',
							8=>'/APPOINTMENTDATETIME/'
							);
					 
					 $res_ar =array(
							0=>$cname,
							1=>$cfname,
							2=>$clname,
							3=>$cemail,
							4=>$ophone,
							5=>$ogmfull,
							6=>$esender,
							7=>$corpname,
							8=>$apptmnt
							);
			
					$esubj=preg_replace($srch_ar,$res_ar,trim($row['esubject']));
					$ebody=preg_replace($srch_ar,$res_ar,trim($row['ebody']));
					
					$emc_ar=array(
								'to'=>		$erecp,
								'from'=>	'bhcustcare@bluehaven.com',
								'fromname'=>	'Blue Haven Customer Care',
								'esubject'=>	trim($esubj),
								'ebody'=>	trim($ebody),
								'oid'=> 	$row1['officeid'],
								'lid'=> 	$row1['stage'],
								'tid'=> 	$row['etid'],
								'cid'=> 	$row1['cid'],
								'uid'=> 	$_SESSION['securityid'],
								'appt'=> 	'',
								'callb'=> 	'',
								'ename'=>	$row['name'],
								'chistory'=>	$chistory,
								'SMTPdbg'=>	$SMTPdebug
							);
					
					//ExtEmailSendPlain($emc_ar);
					ExtEmailSendSSL($emc_ar);
					
					//if ($_SESSION['securityid']==26)
					//{
					//	echo ':'.$erecp.':'.trim($esubj);
					//}
					
					if (!$chistory)
					{
						echo "<tr><td class=\"gray\" align=\"right\">".$emcnt++.".</td><td class=\"gray\"><b>".$row['name']."</b> Email sent to ".$row1['cemail']."</td></tr>\n";
					}
				}
				else
				{
					if (!$chistory)
					{
						echo "<tr><td class=\"gray\"><b>".$row['name']."</b> Email Not Sent:</td><td class=\"gray\">".$row1['cemail']." already received this Email on ".date('m/d/Y h:i a',strtotime($row2['sdate']))."</td></tr>\n";
					}
				}
			}
			
			if (!$chistory)
			{
				if ($emcnt > 1)
				{
					echo "<tr><td class=\"gray\" colspan=\"2\"><img src=\"images/pixel.gif\"></td></tr>\n";
					
					if (count($_REQUEST['etcid']) > 1)
					{
						echo "<tr><td class=\"gray\"><img src=\"images/pixel.gif\"></td><td class=\"gray\">Your Email List was processed without Errors.</td></tr>\n";
					}
					else
					{
						echo "<tr><td class=\"gray\"><img src=\"images/pixel.gif\"></td><td class=\"gray\">Your Email was processed without Errors.</td></tr>\n";
					}
				}
				
				echo "</table>\n";
			}
			
			$_SESSION['et_uid']=$_REQUEST['et_uid'];
		}
	}
	else
	{
		if (!$chistory)
		{
			echo "<table class=\"outer\" width=\"400\">\n";
			echo "<tr><td class=\"gray\">This Email List has already been processed. Click <b>New Search</b> to create a new Email List.</td></tr>\n";
			echo "</table>\n";
		}
	}
}

//Main
if (isset($_REQUEST['call']) && $_REQUEST['call']=='deletecmnt')
{
	deletecmnt();
	listcomments();
}
elseif (isset($_REQUEST['call']) && $_REQUEST['call']=='sendemail')
{
	//deletecmnt();
	procemail();
	listcomments();
}
else
{
	listcomments();
}

?>