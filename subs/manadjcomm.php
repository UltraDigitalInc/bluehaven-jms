<?php

session_start();

function showlegend()
{
    include('../connect_db.php');

    $qry0 = "SELECT * FROM jest..CommissionHistoryCodes WHERE active=1 and show=1 order by label asc;";
    $res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
    
    echo "<html>\n";
    echo "	<head>\n";
    echo "		<meta name=\"ROBOTS\" content=\"NOINDEX, NOFOLLOW\">\n";
    echo "		<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\">\n";
    echo "		<title>Sales & Commission Legend</title>\n";
    echo "		<link href=\"../yui/build/fonts/fonts-min.css\" type=\"text/css\" rel=\"stylesheet\">\n";
    echo "		<link href=\"../bh_main.css\" type=\"text/css\" rel=\"stylesheet\">\n";
    echo "	</head>\n";
    echo "	<body>\n";
    echo "<table width=\"100%\">\n";
    echo "	<tr>\n";
    echo "		<td>\n";
    echo "			<table class=\"outer\" width=\"100%\">\n";
    echo "				<tr>\n";
    echo "					<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Sales & Commission Legend</b></td>\n";
    echo "					<td class=\"gray\" align=\"right\" valign=\"bottom\"><div onClick=\"window.close();\" title=\"Close Window\"><b>[X]</b></div></td>\n";
    echo "				</tr>\n";
    echo "			</table>\n";
    echo "		</td>\n";
    echo "	</tr>\n";
    echo "	<tr>\n";
    echo "		<td>\n";
    echo "			<table class=\"outer\" width=\"100%\">\n";
    echo "				<tr>\n";
    echo "					<td align=\"left\">\n";
    echo "						<table class=\"gray\" width=\"100%\">\n";
    echo "							<tr>\n";
    echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"100px\"><b>Type</b></td>\n";
    echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Description</b></td>\n";
    echo "							</tr>\n";

    if ($nrow0 > 0)
    {
        while ($row0 = mssql_fetch_array($res0))
        {
            echo "							<tr>\n";
            echo "								<td class=\"".$row0['ischeme']."\" align=\"right\" valign=\"top\" width=\"100px\">".$row0['label']."</td>\n";
            echo "								<td class=\"".$row0['ischeme']."\" align=\"left\" valign=\"top\">".$row0['descrip']."</td>\n";
            echo "							</tr>\n";
        }
    }
    
    echo "						</table>\n";
    echo "					</td>\n";
    echo "				</tr>\n";
    echo "			</table>\n";
    echo "		</td>\n";
    echo "	</tr>\n";
    echo "</table>\n";
    echo "   </body>\n";
    echo "</html>\n";       
}


function mancommadj()
{
    try
    {
        if (isset($_SESSION['securityid']) && $_SESSION['clev'] >= 6)
        {
            include('../connect_db.php');
            include('../common_func.php');
            
            $qry0 = "SELECT * FROM jest..security WHERE securityid=".$_SESSION['securityid'].";";
            $res0 = mssql_query($qry0);
            $row0 = mssql_fetch_array($res0);
            $nrow0= mssql_num_rows($res0);
            
            if ($nrow0 > 0)
            {
                $qry1 = "SELECT * FROM jest..security WHERE securityid=".$_REQUEST['secid'].";";
                $res1 = mssql_query($qry1);
                $row1 = mssql_fetch_array($res1);
                $nrow1= mssql_num_rows($res1);
                
                $ranhash  =md5(session_id().time().$row1['securityid']).".".$_SESSION['securityid'];
                
                echo "<html>\n";
                echo "	<head>\n";
                echo "		<meta name=\"ROBOTS\" content=\"NOINDEX, NOFOLLOW\">\n";
                echo "		<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\">\n";
                echo "		<title>Sales & Commission Entry</title>\n";
                echo "		<link href=\"../yui/build/fonts/fonts-min.css\" type=\"text/css\" rel=\"stylesheet\">\n";
                echo "		<link href=\"../bh.css\" type=\"text/css\" rel=\"stylesheet\">\n";
                echo "  <body onLoad=\"window.name = 'JMSchild';document.adjustform.descrip.focus();\">\n";
                echo "<table width=\"100%\">\n";
                echo "	<tr>\n";
                echo "		<td>\n";
                echo "			<table class=\"outer\" width=\"100%\">\n";
                echo "				<tr>\n";
                echo "					<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Sales & Commission Entry</b></td>\n";
                echo "					<td class=\"gray\" align=\"right\" valign=\"bottom\"><div onClick=\"window.close();\" title=\"Close Window\"><b>[X]</b></div></td>\n";
                echo "				</tr>\n";
                echo "			</table>\n";
                echo "		</td>\n";
                echo "	</tr>\n";
                echo "	<tr>\n";
                echo "		<td>\n";
                echo "			<table class=\"outer\" width=\"100%\">\n";
                echo "				<tr>\n";
                echo "					<td align=\"left\">\n";
                echo "						<table class=\"gray\" width=\"100%\">\n";
                echo "						<form action=\"../index.php\" name=\"adjustform\" method=\"post\" target=\"JMSmain\" onSubmit=\"JavaScript: window.close()\">\n";
                echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
                echo "						<input type=\"hidden\" name=\"call\" value=\"srpage\">\n";
                echo "						<input type=\"hidden\" name=\"stg\" value=\"2\">\n";
                echo "						<input type=\"hidden\" name=\"incsecondary\" value=\"1\">\n";
                echo "						<input type=\"hidden\" name=\"createmancommadj\" value=\"1\">\n"; 
                echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$row1['officeid']."\">\n";
                echo "						<input type=\"hidden\" name=\"sid\" value=\"".$row1['securityid']."\">\n";
                echo "						<input type=\"hidden\" name=\"ranhash\" value=\"".$ranhash."\">\n";
                echo "						<input type=\"hidden\" name=\"showcommission\" value=\"1\">\n";
                echo "						<input type=\"hidden\" name=\"showadjust\" value=\"1\">\n";
                echo "						<input type=\"hidden\" name=\"showbonus\" value=\"1\">\n";
                echo "						<input type=\"hidden\" name=\"showloan\" value=\"1\">\n";
                echo "						<input type=\"hidden\" name=\"showdraw\" value=\"1\">\n";
                echo "						<input type=\"hidden\" name=\"showpipeline\" value=\"1\">\n";
                //echo "						<input type=\"hidden\" name=\"htype\" value=\"A\">\n";
                echo "							<tr>\n";
                echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Sales Rep:</b></td>\n";
                echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$row1['lname'].", ".$row1['fname']." (".$row1['securityid'].")</td>\n";
                echo "							</tr>\n";
                echo "							<tr>\n";
                echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Date:</b></td>\n";
                echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" name=\"trandate\" value=\"".date('m/d/Y')."\" size=\"20\" maxlength=\"10\"></td>\n";
                echo "							</tr>\n";
                echo "							<tr>\n";
                echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Type:</b></td>\n";
                echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
                echo "                                  <select name=\"htype\" id=\"htype\">\n";
                
                if ($_SESSION['clev'] >= 9)
                {
                    echo "                                      <option value=\"A\" style=\"background-color:lightblue\">Adjust</option>\n";
                }
                
                if ($_SESSION['clev'] >= 9)
                {
                    echo "                                      <option value=\"B\" style=\"background-color:#FFD700\">Bonus</option>\n";
                }
                
                if ($_SESSION['clev'] >= 9)
                {
                    echo "                                      <option value=\"D\" style=\"background-color:#99FF9A\">Draw</option>\n";
                }
                
                if ($_SESSION['clev'] >= 9)
                {
                    echo "                                      <option value=\"L\" style=\"background-color:#008B8B;color:#FFFFFF\">Loan</option>\n";
                }
                
                if ($_SESSION['clev'] >= 9)
                {
                    echo "                                      <option value=\"R\" style=\"background-color:#666666;color:#FFFFFF\">Processor Lock</option>\n";
                }
                
                echo "                                  </select>\n";
                echo "                              </td>\n";
                echo "							</tr>\n";
                echo "							<tr>\n";
                echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Description:</b></td>\n";
                echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" name=\"descrip\" size=\"20\" maxlength=\"32\"></td>\n";
                echo "							</tr>\n";
                echo "							<tr>\n";
                echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Amount:</b></td>\n";
                echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" name=\"amt\" size=\"20\"></td>\n";
                echo "							</tr>\n";
                echo "							<tr>\n";
                echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\" colspan=\"2\"><input class=\"checkboxgry\" type=\"image\" src=\"../images/save.gif\" value=\"Save Commission Adjust\"></td>\n";
                echo "							</tr>\n";
                echo "						</table>\n";
                echo "						</form>\n";
                echo "					</td>\n";
                echo "				</tr>\n";
                echo "			</table>\n";
                echo "		</td>\n";
                echo "	</tr>\n";
                echo "</table>\n";
                echo "   </body>\n";
                echo "</html>\n";
            }
            else
            {
                throw new Exception('ID Failure');
            }
        }
        else
        {
            throw new Exception('Session Failure');
        }
    }
    catch (Exception $e)
    {
        echo 'Error: ' . $e->getMessage();
    }
}

function mancommdel()
{
    try
    {
        if (isset($_SESSION['securityid']))
        {
            include('../connect_db.php');
            include('../common_func.php');
            
            $qry0 = "SELECT * FROM jest..security WHERE securityid=".$_SESSION['securityid'].";";
            $res0 = mssql_query($qry0);
            $row0 = mssql_fetch_array($res0);
            $nrow0= mssql_num_rows($res0);
            
            $qry1 = "SELECT * FROM jest..security WHERE securityid=".$_REQUEST['secid'].";";
            $res1 = mssql_query($qry1);
            $row1 = mssql_fetch_array($res1);
            $nrow1= mssql_num_rows($res1);
            
            $qry2 = "SELECT * FROM jest..CommissionHistory WHERE hid=".$_REQUEST['hid'].";";
            $res2 = mssql_query($qry2);
            $row2 = mssql_fetch_array($res2);
            $nrow2= mssql_num_rows($res2);
            
            //if ($nrow0 > 0 && $row0['officeid']==89)
            //if ($nrow0 > 0 && $_SESSION['clev'] >= 6 && $row2['TranLock']==0)
            if ($nrow0 > 0 && $_SESSION['clev'] >= 6)
            {
                $ranhash  =md5(session_id().time().$row1['securityid']).".".$_SESSION['securityid'];
                
                if ($nrow2==1)
                {
                    echo "<html>\n";
                    echo "	<head>\n";
                    echo "		<meta name=\"ROBOTS\" content=\"NOINDEX, NOFOLLOW\">\n";
                    echo "		<meta http-equiv=\"content-type\" content=\"text/html;charset=utf-8\">\n";
                    echo "		<title>Manual Commission Adjust</title>\n";
                    echo "		<link href=\"../yui/build/fonts/fonts-min.css\" type=\"text/css\" rel=\"stylesheet\">\n";
                    echo "		<link href=\"../bh.css\" type=\"text/css\" rel=\"stylesheet\">\n";
                    echo "   <body>\n";
                    echo "<table width=\"100%\">\n";
                    echo "	<tr>\n";
                    echo "		<td>\n";
                    echo "			<table class=\"outer\" width=\"100%\">\n";
                    echo "				<tr>\n";
                    echo "					<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Delete Commission Adjust</b></td>\n";
                    echo "					<td class=\"gray\" align=\"right\" valign=\"bottom\"><div onClick=\"window.close();\" title=\"Close Window\"><b>[X]</b></div></td>\n";
                    echo "				</tr>\n";
                    echo "			</table>\n";
                    echo "		</td>\n";
                    echo "	</tr>\n";
                    echo "	<tr>\n";
                    echo "		<td>\n";
                    echo "			<table class=\"outer\" width=\"100%\">\n";
                    echo "				<tr>\n";
                    echo "					<td align=\"left\">\n";
                    echo "						<table class=\"gray\" width=\"100%\">\n";
                    echo "						<form action=\"../index.php\" name=\"deleteform\" method=\"post\" target=\"JMSmain\" onSubmit=\"JavaScript: window.close()\">\n";
                    echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
                    echo "						<input type=\"hidden\" name=\"call\" value=\"srpage\">\n";
                    echo "						<input type=\"hidden\" name=\"stg\" value=\"2\">\n";
                    echo "						<input type=\"hidden\" name=\"incsecondary\" value=\"1\">\n";
                    echo "						<input type=\"hidden\" name=\"deletemancommadj\" value=\"1\">\n";
                    echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$row1['officeid']."\">\n";
                    echo "						<input type=\"hidden\" name=\"sid\" value=\"".$row1['securityid']."\">\n";
                    echo "						<input type=\"hidden\" name=\"hid\" value=\"".$row2['hid']."\">\n";
                    echo "						<input type=\"hidden\" name=\"showcommission\" value=\"1\">\n";
                    echo "						<input type=\"hidden\" name=\"showadjust\" value=\"1\">\n";
                    echo "						<input type=\"hidden\" name=\"showbonus\" value=\"1\">\n";
                    echo "						<input type=\"hidden\" name=\"showloan\" value=\"1\">\n";
                    echo "						<input type=\"hidden\" name=\"showdraw\" value=\"1\">\n";
                    echo "						<input type=\"hidden\" name=\"showpipeline\" value=\"1\">\n";
                    echo "							<tr>\n";
                    echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Sales Rep:</b></td>\n";
                    echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$row1['lname'].", ".$row1['fname']." (".$row1['securityid'].")</td>\n";
                    echo "							</tr>\n";
                    echo "							<tr>\n";
                    echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Date:</b></td>\n";
                    echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".date('m/d/Y',strtotime($row2['trandate']))."</td>\n";
                    echo "							</tr>\n";
                    echo "							<tr>\n";
                    echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Description:</b></td>\n";
                    echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".$row2['descrip']."</td>\n";
                    echo "							</tr>\n";
                    echo "							<tr>\n";
                    echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Amount:</b></td>\n";
                    echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\">".number_format($row2['amt'], 2, '.', '')."</td>\n";
                    echo "							</tr>\n";
                    echo "							<tr>\n";
                    echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\" colspan=\"2\"><input class=\"transnb\" type=\"image\" src=\"../images/action_delete.gif\" value=\"Delete\" title=\"Delete\"></td>\n";
                    echo "							</tr>\n";
                    echo "						</table>\n";
                    echo "						</form>\n";
                    echo "					</td>\n";
                    echo "				</tr>\n";
                    echo "			</table>\n";
                    echo "		</td>\n";
                    echo "	</tr>\n";
                    echo "</table>\n";
                    echo "   </body>\n";
                    echo "</html>\n";
                }
                elseif ($nrow2 > 1)
                {
                   throw new Exception('More than One Commission Adjust Found');
                }
                elseif ($nrow2 == 0)
                {
                   throw new Exception('No Commission Adjust Found');
                }
            }
            else
            {
                throw new Exception('You are not authorized to view this resource');
            }
        }
        else
        {
            throw new Exception('Session Failure');
        }
    }
    catch (Exception $e)
    {
        echo 'Error: ' . $e->getMessage();
    }
}

if (isset($_REQUEST['action']))
{
    if ($_REQUEST['action']=='mancommadj')
    {
        mancommadj();
    }
    elseif ($_REQUEST['action']=='mancommdel')
    {
        mancommdel();
    }
    elseif ($_REQUEST['action']=='showlegend')
    {
        showlegend();
    }
}

?>