<?php

function srpage()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
    $acclist =explode(",",$_SESSION['aid']);

    if ($_SESSION['clev'] == 1)
    {
        //$qry0 = "SELECT S.securityid,S.officeid,S.lname,S.fname,S.slevel,(select name from jest..offices where officeid=S.officeid) as oname FROM security as S WHERE S.securityid='".$_SESSION['securityid']."' and S.srep=1 order by SUBSTRING(S.slevel,13,1) DESC,S.lname ASC;";
		$qry0 = "
		SELECT 
			S.securityid,S.officeid,S.lname,S.fname,S.slevel,
			(select name from jest..offices where officeid=S.officeid) as oname,
			(select count([id]) from secondaryids where secid=S.securityid) as scnt
		FROM 
			security as S 
		WHERE 
			S.securityid=".$_SESSION['securityid']." 
		order by SUBSTRING(S.slevel,13,1) DESC,S.lname ASC;
		";
        $res0 = mssql_query($qry0);
        $nrow0= mssql_num_rows($res0);
    }
    elseif ($_SESSION['clev'] >= 6)
    {
        //$qry0 = "SELECT S.securityid,S.officeid,S.lname,S.fname,S.slevel,(select name from jest..offices where officeid=S.officeid) as oname FROM security as S WHERE officeid='".$_SESSION['officeid']."' and srep=1 order by SUBSTRING(slevel,13,1) DESC,lname ASC;";
		$qry0 = "
		SELECT 
			S.securityid,S.officeid,S.lname,S.fname,S.slevel,
			(select name from jest..offices where officeid=S.officeid) as oname,
			(select count([id]) from secondaryids where secid=S.securityid) as scnt
		FROM 
			security as S 
		WHERE 
			S.officeid=".$_SESSION['officeid']." 
			and S.srep=1 
		order by SUBSTRING(S.slevel,13,1) DESC,S.lname ASC;
		";
        $res0 = mssql_query($qry0);
        $nrow0= mssql_num_rows($res0);
    }
    elseif ($_SESSION['clev'] < 6)
    {
        $qry0 = "SELECT S.securityid,S.officeid,S.lname,S.fname,S.slevel,(select name from jest..offices where officeid=S.officeid) as oname FROM security as S WHERE S.securityid='".$_SESSION['securityid']."' or S.sidm='".$_SESSION['securityid']."' and S.srep=1 order by SUBSTRING(S.slevel,13,1) DESC,S.lname ASC;";
        $res0 = mssql_query($qry0);
        $nrow0= mssql_num_rows($res0);
    }
    else
    {
        echo "<b>You do not have the required access to view this resource</b>";
        exit;
    }
    
    $qry0a = "SELECT psdate,pedate,brept_yr,active FROM [jest].[dbo].[bonus_schedule_config] order by brept_yr desc;";
    $res0a = mssql_query($qry0a);
    $nrow0a= mssql_num_rows($res0a);
    
    $qry0b = "SELECT securityid,officeid,tester,SCPageAdjust FROM [jest].[dbo].[security] where securityid=".$_SESSION['securityid'].";";
    $res0b = mssql_query($qry0b);
    $row0b = mssql_fetch_array($res0b);
    

	$totalcom_ar=array('C'=>0,'M'=>0,'B'=>0,'P'=>0,'L'=>0,'D'=>0,'A'=>0);
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_srpage.js?".time()."\"></script>\n";
	echo "<div id=\"masterdiv\">";
	echo "	<table class=\"transnb\" align=\"center\" width=\"950px\">\n";
	echo "		<tr>\n";
	echo "			<td colspan=\"2\" align=\"center\">\n";
	echo "				<div class=\"noPrint\">\n";
	echo "				<table class=\"outer\" width=\"100%\">\n";
	echo "					<tr>\n";
	echo "						<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "							<a id=\"openlegenddialog\" href=\"#\"><img src=\"images/help.png\" title=\"Sales & Commission Legend\"></a> <b>Legend</b>\n";
	echo"						</td>\n";
	echo "						<td class=\"gray\" align=\"right\">\n";
	echo "							<table class=\"transnb\">\n";

    if ($nrow0 > 0)
    {
		echo "							<tr>\n";
        echo "         		        			<form method=\"post\">\n";
        echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
        echo "									<input type=\"hidden\" name=\"call\" value=\"srpage\">\n";
        echo "									<input type=\"hidden\" name=\"stg\" value=\"2\">\n";
        echo "									<input type=\"hidden\" name=\"incsecondary\" value=\"1\">\n";
		echo "									<input type=\"hidden\" name=\"showcommission\" value=\"1\">\n";
		echo "									<input type=\"hidden\" name=\"showadjust\" value=\"1\">\n";
		echo "									<input type=\"hidden\" name=\"showbonus\" value=\"1\">\n";
		echo "									<input type=\"hidden\" name=\"showloan\" value=\"1\">\n";
		echo "									<input type=\"hidden\" name=\"showdraw\" value=\"1\">\n";
		echo "									<input type=\"hidden\" name=\"showpipeline\" value=\"1\">\n";
        echo "									<input type=\"hidden\" name=\"showprocessing\" value=\"0\">\n";
        echo "								<td class=\"gray\" align=\"left\"><b>Sales Rep</b></td>\n";
        echo "								<td class=\"gray\" align=\"right\">\n";
        echo "										<select id=\"srsid\" name=\"sid\" onChange=\"this.form.submit();\">\n";
        echo "  										<option value=\"0\">Select Sales Rep...</option>\n";

        while ($row0 = mssql_fetch_array($res0))
        {
            //if ($_SESSION['rlev'] >= 9 && $_SESSION['officeid']==89)
			//if ($row0['scnt']==0)
			//{
            if ($_SESSION['rlev'] >= 9)
            {
                $secl=explode(",",$row0['slevel']);
                if ($secl[6]!=0)
                {
                    $ostyle="fontblack";
                }
                else
                {
                    $ostyle="fontred";
                }

                if (isset($_REQUEST['sid']) && $_REQUEST['sid']==$row0['securityid'])
                {
                    echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row0['lname'].", ".$row0['fname']." (".$row0['oname'].") (".$row0['securityid'].")</option>\n";
                }
                else
                {
                    echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\">".$row0['lname'].", ".$row0['fname']." (".$row0['oname'].") (".$row0['securityid'].")</option>\n";
                }
            }
            else
            {
                if (in_array($row0['securityid'],$acclist))
                {
                    $secl=explode(",",$row0['slevel']);
                    if ($secl[6]!=0)
                    {
                        $ostyle="fontblack";
                    }
                    else
                    {
                        $ostyle="fontred";
                    }
    
                    if (isset($_REQUEST['sid']) && $_REQUEST['sid']==$row0['securityid'])
                    {
                        echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row0['lname'].", ".$row0['fname']." (".$row0['oname'].")</option>\n";
                    }
                    else
                    {
                        echo "										<option value=\"".$row0['securityid']."\" class=\"".$ostyle."\">".$row0['lname'].", ".$row0['fname']." (".$row0['oname'].")</option>\n";
                    }
                }
            }
			//}
        }

        echo "										</select>\n";
        echo "                                  </td>\n";
		echo "									<td class=\"gray\" align=\"right\">\n";
		echo "										<div onclick=\"SwitchMenu('optionselect')\"><img src=\"images/bullet_toggle_plus.png\" title=\"Report Options\"></div>";
		echo "									</td>\n";
        echo "                                  <td><input class=\"transnb_button\" type=\"image\" src=\"images/arrow_refresh_small.png\" title=\"Refresh\"></td>\n";
		echo "					            </tr>\n";
		echo "					            <tr>\n";
		echo "                                  <td align=\"right\"></td>\n";
		echo "                                  <td colspan=\"2\">\n";
		echo "        								<span class=\"submenu\" id=\"optionselect\">\n";
		echo "										<table class=\"transnb\" align=\"center\" width=\"100%\">\n";
		echo "											<tr>\n";
        echo "												<td class=\"gray\" align=\"right\"><b>Remove Adjusts</b></td>\n";
        
        if (isset($_REQUEST['showadjust']) && $_REQUEST['showadjust']==0)
        {        
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showadjust\" value=\"0\" CHECKED></td>\n";
        }
        else
        {
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showadjust\" value=\"0\"></td>\n";
        }
		
		echo "											</tr>\n";
		echo "											<tr>\n";
        echo "												<td class=\"gray\" align=\"right\"><b>Remove Bonus</b></td>\n";
        
        if (isset($_REQUEST['showbonus']) && $_REQUEST['showbonus']==0)
        {        
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showbonus\" value=\"0\" CHECKED></td>\n";
        }
        else
        {
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showbonus\" value=\"0\"></td>\n";
        }
		
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\"><b>Remove Commission</b></td>\n";
        
        if (isset($_REQUEST['showcommission']) && $_REQUEST['showcommission']==0)
        {        
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showcommission\" value=\"0\" CHECKED></td>\n";
        }
        else
        {
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showcommission\" value=\"0\"></td>\n";
        }
		
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\"><b>Remove Draws</b></td>\n";
        
        if (isset($_REQUEST['showdraw']) && $_REQUEST['showdraw']==0)
        {        
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showdraw\" value=\"0\" CHECKED></td>\n";
        }
        else
        {
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showdraw\" value=\"0\"></td>\n";
        }
		
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\"><b>Remove Loans</b></td>\n";
        
        if (isset($_REQUEST['showloan']) && $_REQUEST['showloan']==0)
        {        
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showloan\" value=\"0\" CHECKED></td>\n";
        }
        else
        {
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showloan\" value=\"0\"></td>\n";
        }
		
		echo "											</tr>\n";
		echo "											<tr>\n";
        echo "												<td class=\"gray\" align=\"right\"><b>Remove Pending</b></td>\n";
        
        if (isset($_REQUEST['showpipeline']) && $_REQUEST['showpipeline']==0)
        {        
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showpipeline\" value=\"0\" CHECKED></td>\n";
        }
        else
        {
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showpipeline\" value=\"0\"></td>\n";
        }
		
		echo "											</tr>\n";
        echo "											<tr>\n";
        echo "												<td class=\"gray\" align=\"right\"><b>Remove Processor</b></td>\n";
        
        if (isset($_REQUEST['showprocessing']) && $_REQUEST['showprocessing']==0)
        {        
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showprocessing\" value=\"0\" CHECKED></td>\n";
        }
        else
        {
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"showprocessing\" value=\"0\"></td>\n";
        }
		
		echo "											</tr>\n";
		echo "											<tr>\n";
        echo "												<td class=\"gray\" align=\"right\"><b>Calculate Pending</b></td>\n";
        
        if (isset($_REQUEST['calcpipeline']) && $_REQUEST['calcpipeline']==1)
        {        
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"calcpipeline\" value=\"1\" CHECKED></td>\n";
        }
        else
        {
            echo "												<td class=\"gray\" align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"calcpipeline\" value=\"1\"></td>\n";
        }
		
		echo "											</tr>\n";
		echo "										</table>\n";
		echo "										</span>\n";
		echo "									</td>\n";
		echo "					            </tr>\n";
        echo "                      </form>\n";
    }
    else
    {
        echo '									<td class=\"gray\" align=\"right\" valign=\"top\"><b>No Sales Reps in this Company</b></td>';
		echo "					            </tr>\n";
    }
    
	echo "				            </table>\n";
    echo "                      </td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";
	echo "				</div>\n";
	echo "			</td>\n";
	echo "		</tr>\n";
    
    if (isset($_REQUEST['stg']) && $_REQUEST['stg']==2 && isset($_REQUEST['sid']) && $_REQUEST['sid']!=0) {
		if (isset($_REQUEST['createmancommadj']) && $_REQUEST['createmancommadj']==1) {
			AddManualCommAdjust();
		}
		
		if (isset($_REQUEST['deletemancommadj']) && $_REQUEST['deletemancommadj']==1) {
			DeleteManualCommAdjust();
		}
		
        $MRG_ar =array();
        $tbal   =0;
        $begbal =0;
        $cnt    =0;
        
        $qryA = "   SELECT
                        *,
                        (select name from jest..offices where officeid=S.officeid) as oname,
                        (select label_masoff_code from offices where officeid=S.officeid) as olabel
                    FROM
                        security as S
                    WHERE
                        securityid='".$_REQUEST['sid']."';";
        $resA = mssql_query($qryA);
        $rowA = mssql_fetch_array($resA);
        $nrowA= mssql_num_rows($resA);
        
        // Get Commission/Sales Beginning Balance
        $qryB  = "SET ANSI_WARNINGS ON ";
        $qryB .= "exec jest..tlh_SRBeginBalance @sid=".$_REQUEST['sid'].";";
        $resB  = mssql_query($qryB);
        $rowB  = mssql_fetch_array($resB);
        $nrowB = mssql_num_rows($resB);
        
        // Get Commission/Sales Data
        $qry1  = "SET ANSI_WARNINGS ON ";
		$qry1 .= "exec jest..tlh_SalesRepPage ".$_REQUEST['sid'].";";
        $res1  = mssql_query($qry1);
        $nrow1 = mssql_num_rows($res1);
        $highestprocdate=0;

        echo "		<tr>\n";
        echo "			<td colspan=\"2\">\n";
        echo "				<table class=\"outer\" width=\"100%\" align=\"right\">\n";
        echo "					<tr>\n";
		echo "						<td class=\"gray\" align=\"left\">\n";
		echo "							<b>Sales & Commission Report</b>";
		
		if (isset($_REQUEST['calcpipeline']) && $_REQUEST['calcpipeline']==1)
		{
			echo " <font color=\"red\"><b>NOTE</b></font><b>: Displaying Pipeline/Pending Sales in Balance</b>";
		}
		
		echo "						</td>\n";
        echo "						<td class=\"gray\" align=\"right\"><b>Print Date</b> \n";
        
        ?>
        
        <script type="text/javascript">
            setLocalTime();
        </script>
        
        <?php

        echo "                      </td>\n";
        echo "					</tr>\n";
        echo "				</table>\n";
        echo "			</td>\n";
        echo "		</tr>\n";
        echo "		<tr>\n";
        echo "			<td colspan=\"2\">\n";
        echo "				<table class=\"outer\" width=\"100%\" align=\"right\">\n";
        echo "					<tr>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"55\"><div title=\"Contract/Transaction Date\"><b>Date</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"45\"><b>Job #</b></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"165\"><b>Description</b></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"55\"><div title=\"Dig/Transaction Date\"><b>Date</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"60\"><div title=\"Adjusted Book Price\"><b>Par</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"20\"><div title=\"Base Commission Percentage Rate\"><b>%</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"60\"><div title=\"Base Commission\"><b>Comm</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"60\"><div title=\"Job Over/Under\"><b>O/U</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"20\"><div title=\"Over/Under Commission Percentage Rate\"><b>%</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"60\"><div title=\"Over/Under Commission\"><b>Comm</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"60\"><div title=\"Adjustments\"><b>Adjust</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"60\"><div title=\"Line Total\"><b>Total</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"60\"><div title=\"Draws Against Page\"><b>Draw</b></div></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"60\"><div title=\"Running Balance carried forward from Previous Year\"><b>Balance</b></td>\n";
        echo "                      <td class=\"ltgray_sidesb\" align=\"center\" width=\"10\"><img src=\"images/pixel.gif\"></td>\n";
        echo "					</tr>\n";
        echo "					<tr>\n";
        echo "                      <td class=\"gray_und\"><img src=\"images/pixel.gif\"></td>\n";
        echo "                      <td class=\"gray_und\" colspan=\"4\" align=\"left\"><b>".$rowA['oname']."</b></td>\n";
        echo "						<td class=\"gray_und\" colspan=\"6\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "						<td class=\"gray_und\" colspan=\"3\" align=\"right\"><b>".$rowA['lname'].", ".$rowA['fname']."</b></td>\n";
        echo "						<td class=\"gray_und\" colspan=\"2\" align=\"center\">\n";
        echo "							<table class=\"transnb\">\n";
        echo "								<tr>\n";
        echo "									<td align=\"center\">\n";
        
		if ($row0b['SCPageAdjust'] >= 1)
        {
			echo "							<a href=\"#\" id=\"openadjustdialog\"><img src=\"images/page_edit.png\"></a>\n";
        }
		else
		{
			echo "<img src=\"images/pixel.gif\">\n";
		}
        
        echo "									</td>\n";
        echo "									<td align=\"center\">\n";
        
        if ($row0b['officeid']==89)
        {
            echo "							<form name=\"viewuser\" id=\"viewuser\" method=\"post\">\n";
            echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
            echo "							<input type=\"hidden\" name=\"call\" value=\"users\">\n";
            echo "							<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
            echo "							<input type=\"hidden\" name=\"userid\" value=\"".$rowA['securityid']."\">\n";
            echo "							<input type=\"hidden\" name=\"officeid\" value=\"".$rowA['officeid']."\">\n";
            echo "							<input class=\"transnb\" type=\"image\" src=\"images/user.png\" alt=\"View Security Account\">\n";
            echo "							</form>\n";
        }
        else
        {
            echo "<img src=\"images/pixel.gif\">\n";
        }
        
        echo "									</td>\n";
        echo "								</tr>\n";
        echo "							</table>\n";
        echo "						</td>\n";
        echo "					</tr>\n";

        if ($nrowB != 0)
        {
            $begbal =$rowB['bbamt'];
            $tbal   =$tbal+$begbal;
            $totalcom_ar['A']=$totalcom_ar['A']+$begbal;
            echo "					<tr>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"right\">". ($cnt + 1) .".</td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"left\">\n";
            echo "							<table class=\"transnb\" width=\"100%\">\n";
            echo "								<tr>\n";
            echo "									<td align=\"left\">Beginning Balance</td>\n";
            echo "								</tr>\n";
            echo "							</table>\n";
            echo "						</td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"center\">".date('m/d/y',strtotime($rowB['bbdate']))."</td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"right\">".number_format($begbal, 2, '.', '')."</td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"right\">".number_format($begbal, 2, '.', '')."</td>\n";
            echo "                      <td class=\"blu_undsidesb\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
            
            if ($tbal < 0)
            {
                echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
            }
            else
            {
                echo number_format($tbal, 2, '.', '');
            }
            
            echo "                      </td>\n";
            echo "                      <td class=\"wh_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
            echo "					</tr>\n";
            $cnt++;
        }        
        
        $tct_ar=array('SRC'=>'SalesRep Comm','SRO'=>'SalesRep O/U','SMC'=>'SalesManager Comm','SRM'=>'SalesRep Manual',);
		$main_ar=array();
		if ($nrow1 > 0) {
			$MARate		=0;
			$MAAmount	=0;
			$sandc		=1;
			while ($row1 = mssql_fetch_array($res1)) {
				if ($row1['Type']=='C' || $row1['Type']=='P' || $row1['Type']=='S' || $row1['Type']=='T') {
					if ($row1['Type']=='C') {
						$qryC = "
							SELECT J1.contractamt,J1.contractdate,(select isnull(overunder,0) from jobs where officeid=J1.officeid and jobid=J1.jobid) as overunder,
							(select isnull(adjbook,0) from jobs where officeid=J1.officeid and jobid=J1.jobid) as adjbook,
							(select isnull(sandc,1) from jobs where officeid=J1.officeid and jobid=J1.jobid) as sandc
							FROM jdetail as J1
							WHERE J1.officeid=".(int) $_SESSION['officeid']." AND J1.njobid='".$row1['JobNumber']."' AND J1.jadd=0;";
                        $resC = mssql_query($qryC);
                        $rowC = mssql_fetch_array($resC);
                        $nrowC= mssql_num_rows($resC);
						$sandc=($rowC['sandc']==1)?1:0;
					}
					else {
						$qryC = "
							SELECT J1.contractamt,J1.contractdate,(select isnull(overunder,0) from jobs where officeid=J1.officeid and jobid=J1.jobid) as overunder,
							(select isnull(adjbook,0) from jobs where officeid=J1.officeid and jobid=J1.jobid) as adjbook,
							(select isnull(sandc,1) from jobs where officeid=J1.officeid and jobid=J1.jobid) as sandc
							FROM jdetail as J1
							WHERE J1.officeid=".(int) $_SESSION['officeid']." AND J1.jobid='".$row1['JobNumber']."' AND J1.jadd=0;";
                        
                        $resC = mssql_query($qryC);
                        $rowC = mssql_fetch_array($resC);
                        $nrowC= mssql_num_rows($resC);
						$sandc=($rowC['sandc']==1)?1:0;
					}
					
					if ($nrowC > 0) {
                        if ($row1['Type']=='S') {
                            $Par	=number_format($rowC['contractamt'], 2, '.', '');
                        }
                        else {
                            $Par	=number_format($rowC['adjbook'], 2, '.', '');
                        }
                        
						$cd		=date('m/d/y',strtotime($rowC['contractdate']));
						$oujob	=number_format($rowC['overunder'], 2, '.', '');
					}
					else {
						$Par    =number_format(0, 2, '.', '');
						$cd		='';
						$oujob	=number_format(0, 2, '.', '');
					}
				}
				else {
					$Par    =number_format(0, 2, '.', '');
					$cd		='';
					$oujob	=number_format(0, 2, '.', '');
				}
				
				if ($_SESSION['securityid']==26999999999999999999999999999999999999) {
					$qryCa = "SELECT J1.njobid,J1.sandc FROM jobs as J1 WHERE J1.officeid=".(int) $_SESSION['officeid']." AND J1.njobid='".$row1['JobNumber']."' AND J1.jadd=0;";
					$resCa = mssql_query($qryCa);
                    $rowCa = mssql_fetch_array($resCa);
					echo $row1['JobNumber'].':'.$rowCa['njobid'].':'.$rowCa['sandc'].'<br>';
				}
				//if ($sandc==1) {
				$main_ar[]=array(
						'Division'=>		$row1['Division'],
						'SalespersonCode'=>	$row1['SalespersonCode'],
						'SequenceNumber'=>	$row1['SequenceNumber'],
						'Type'=>			$row1['Type'],
						'Date'=>			$row1['Date'],
						'ReferenceNumber'=>	$row1['ReferenceNumber'],
						'JournalSource'=>	$row1['JournalSource'],
						'Description'=>		$row1['Description'],
						'Amount'=>			$row1['Amount'],
						'JobNumber'=>		$row1['JobNumber'],
						'Rate'=>			$row1['Rate'],
						'uid'=>				$row1['uid'],
						'OURate'=>			0,
						'OUAmount'=>		0,
						'Par'=>				$Par,
						'CD'=>				$cd,
						'OUJob'=>			$oujob,
						'MARate'=>			$MARate,
						'MAAmount'=>		$MAAmount
					);
				//}
			}
		}
		
		$dbg=0;
        if ($dbg==1 && $_SESSION['securityid']==SYS_ADMIN) {
            echo '<pre>';
            print_r($main_ar);
            echo '</pre>';
        }
		
		//$bcrate_ar=array();
		//$bccomm_ar=array();
		$oucomm_ar=array();
		$ourate_ar=array();
		foreach ($main_ar as $m1 => $v1) {
			if ($v1['Type']=='O') //Over/Under
			{
				$ourate_ar[$v1['JobNumber']]=$v1['Rate'];
				$oucomm_ar[$v1['JobNumber']]=$v1['Amount'];
			}
			elseif ($v1['Type']=='M') //Manual Adjusts
			{
				$marate_ar[$v1['JobNumber']]=$v1['Rate'];
				$macomm_ar[$v1['JobNumber']]=$v1['Amount'];
			}
			elseif ($v1['Type']=='U') //Bullets
			{
				$urate_ar[$v1['JobNumber']]=$v1['Rate'];
				$ucomm_ar[$v1['JobNumber']]=$v1['Amount'];
			}
			elseif ($v1['Type']=='T') //Merit
			{
				$trate_ar[$v1['JobNumber']]=$v1['Rate'];
				$tcomm_ar[$v1['JobNumber']]=$v1['Amount'];
			}
			elseif ($v1['Type']=='V') // Min Comm Override
			{
				$vrate_ar[$v1['JobNumber']]=$v1['Rate'];
				$vcomm_ar[$v1['JobNumber']]=$v1['Amount'];
			}
		}
		
		foreach ($main_ar as $m2 => $v2)
		{
			if ($v2['Type']=='C')
			{
				@$main_ar[$m2]['OURate']	=@$main_ar[$m2]['OURate'] + $ourate_ar[$v2['JobNumber']];
				@$main_ar[$m2]['OUAmount']	=@$main_ar[$m2]['OUAmount'] + $oucomm_ar[$v2['JobNumber']];
			}
		}
		
		foreach ($main_ar as $m2a => $v2a) // Manual Adjusts Add to Commission Manual Adjust Column
		{
			if ($v2a['Type']=='C')
			{
				@$main_ar[$m2a]['MARate']	=@$main_ar[$m2a]['MARate'] + $marate_ar[$v2a['JobNumber']];
				@$main_ar[$m2a]['MAAmount']	=@$main_ar[$m2a]['MAAmount'] + $macomm_ar[$v2a['JobNumber']];
			}
		}
		
		foreach ($main_ar as $m2b => $v2b) // Commission Bullets: Add to Main Commission
		{
			if ($v2b['Type']=='C')
			{
				@$main_ar[$m2b]['Rate']		=@$main_ar[$m2b]['Rate']    +$urate_ar[$v2b['JobNumber']];
				@$main_ar[$m2b]['Amount']	=@$main_ar[$m2b]['Amount']  +$ucomm_ar[$v2b['JobNumber']];
			}
		}
		
		foreach ($main_ar as $m2t => $v2t) // Merit Comm Adds to Main Comm Line Manual Adjust Column
		{
			if ($v2t['Type']=='C')
			{
				@$main_ar[$m2t]['MARate']	=@$main_ar[$m2t]['MARate']    +$trate_ar[$v2t['JobNumber']];
				@$main_ar[$m2t]['MAAmount']	=@$main_ar[$m2t]['MAAmount']  +$tcomm_ar[$v2t['JobNumber']];
			}
		}
		
		foreach ($main_ar as $m2v => $v2v) // Min Comm Override Adds to Main Comm Line Manual Adjust Column
		{
			if ($v2v['Type']=='C')
			{
				@$main_ar[$m2v]['MARate']	=@$main_ar[$m2v]['MARate']    +$vrate_ar[$v2v['JobNumber']];
				@$main_ar[$m2v]['MAAmount']	=@$main_ar[$m2v]['MAAmount']   +$vcomm_ar[$v2v['JobNumber']];
			}
		}
		
		foreach ($main_ar as $m3 => $v3)
		{
			if ($v3['Type']=='P')
			{
				if ($v3['Division']==2)
				{
					$p2rate_ar[$v3['JobNumber']]=$v3['Rate'];
					$p2comm_ar[$v3['JobNumber']]=$v3['Amount'];
				}
			}
		}
		
		foreach ($main_ar as $m3a => $v3a)
		{
			if ($v3a['Type']=='P')
			{
				if ($v3a['Division']==0)
				{
					$p0rate_ar[$v3a['JobNumber']]=$v3a['Rate'];
					$p0comm_ar[$v3a['JobNumber']]=$v3a['Amount'];
				}
			}
		}
        
        foreach ($main_ar as $np6a => $vp6a) // Pending Bullets: Separate
		{
			if ($vp6a['Type']=='P')
			{
				if ($vp6a['Division']==6)
				{
					$p6rate_ar[$vp6a['JobNumber']]=$vp6a['Rate'];
					$p6comm_ar[$vp6a['JobNumber']]=$vp6a['Amount'];
				}
			}
		}
		
		foreach ($main_ar as $np8a => $vp8a) // Minimum Override
		{
			if ($vp8a['Type']=='P')
			{
				if ($vp8a['Division']==8)
				{
					//echo 'HITa<br>';
					$p8rate_ar[$vp8a['JobNumber']]=$vp8a['Rate'];
					$p8comm_ar[$vp8a['JobNumber']]=$vp8a['Amount'];
				}
			}
		}
		
		foreach ($main_ar as $np9a => $vp9a) // Merit Bonus
		{
			if ($vp9a['Type']=='P')
			{
				if ($vp9a['Division']==9)
				{
					//echo 'HITa<br>';
					$p9rate_ar[$vp9a['JobNumber']]=$vp9a['Rate'];
					$p9comm_ar[$vp9a['JobNumber']]=$vp9a['Amount'];
				}
			}
		}
		
		foreach ($main_ar as $m4 => $v4)
		{
			if ($v4['Type']=='P')
			{
				if ($v4['Division']==1)
				{
					@$main_ar[$m4]['OURate']	=$p2rate_ar[$v4['JobNumber']];
					@$main_ar[$m4]['OUAmount']	=$p2comm_ar[$v4['JobNumber']];
				}
			}
		}
		
		foreach ($main_ar as $m4a => $v4a)
		{
			if ($v4a['Type']=='P')
			{
				if ($v4a['Division']==1)
				{
					@$main_ar[$m4a]['MARate']	=$p0rate_ar[$v4a['JobNumber']];
					@$main_ar[$m4a]['MAAmount']	=$p0comm_ar[$v4a['JobNumber']];
				}
			}
		}
        
        foreach ($main_ar as $np6b => $vp6b) // Pending Bullets: Add to Main Commission Line Manual Adjust Column
		{
			if ($vp6b['Type']=='P')
			{
                if ($vp6b['Division']==1)
				{
					//echo "HIT6b1:".$main_ar[$np6b]['Amount']."<br>";
					//echo "HIT6b2:".$p6comm_ar[$vp6b['JobNumber']]."<br>";
                    @$main_ar[$np6b]['MARate']		=@$main_ar[$np6b]['MARate']    +$p6rate_ar[$vp6b['JobNumber']];
                    @$main_ar[$np6b]['MAAmount']	=@$main_ar[$np6b]['MAAmount']  +$p6comm_ar[$vp6b['JobNumber']];
                }
			}
		}
		
		foreach ($main_ar as $np8b => $vp8b) // Pending Minimum Override Add to Main Commission Line Manual Adjust Column
		{
			if ($vp8b['Type']=='P')
			{
                if ($vp8b['Division']==1)
				{
					//echo "HIT6b1:".$main_ar[$np6b]['Amount']."<br>";
					//echo "HIT6b2:".$p6comm_ar[$vp6b['JobNumber']]."<br>";
                    @$main_ar[$np8b]['MARate']		=@$main_ar[$np8b]['MARate']    +$p8rate_ar[$vp8b['JobNumber']];
                    @$main_ar[$np8b]['MAAmount']	=@$main_ar[$np8b]['MAAmount']  +$p8comm_ar[$vp8b['JobNumber']];
                }
			}
		}
		
		foreach ($main_ar as $np9b => $vp9b) // Pending Merit: Add to Main Commission Line Manual Adjust Column
		{
			if ($vp9b['Type']=='P')
			{
                if ($vp9b['Division']==1)
				{
					//echo "HIT9b1:".$main_ar[$np9b]['Amount']."<br>";
					//echo "HIT9b2:".$p9comm_ar[$vp9b['JobNumber']]."<br>";
                    @$main_ar[$np9b]['MARate']		=@$main_ar[$np9b]['MARate']    +$p9rate_ar[$vp9b['JobNumber']];
                    @$main_ar[$np9b]['MAAmount']	=@$main_ar[$np9b]['MAAmount']  +$p9comm_ar[$vp9b['JobNumber']];
                }
			}
		}

        if ($nrow1 > 0)
        {
			$dbg=0;
			if ($dbg==2 && $_SESSION['securityid']==SYS_ADMIN)
			{
				echo '<pre>';
				print_r($main_ar);
				echo '</pre>';
			}

			foreach ($main_ar as $mn => $mv)
            {
                if ($mv['Type']=='C'||$mv['Type']=='M'||$mv['Type']=='N'||$mv['Type']=='U' ||$mv['Type']=='S'||$mv['Type']=='T' ||$mv['Type']=='V')
                {
                    $tbc='wh_undsidesb';
                }
                elseif ($mv['Type']=='A')
                {
                    $tbc='blu_undsidesb';
                }
                elseif ($mv['Type']=='P')
                {
                    $tbc='tan_undsidesb';
                }
                elseif ($mv['Type']=='D')
                {
                    $tbc='ltgrn_undsidesb';
                }
				elseif ($mv['Type']=='L')
                {
                    $tbc='dkcy_undsidesb';
                }
				elseif ($mv['Type']=='B')
                {
                    $tbc='gold_undsidesb';
                }
                elseif ($mv['Type']=='R')
                {
                    $tbc='grybkgd_und';
                }
                
                if ($mv['Type']=='C' && (isset($_REQUEST['showcommission']) && $_REQUEST['showcommission']==1))
                {
					$vbal=$mv['Amount'] + $mv['OUAmount'];
                    $tbal=$tbal + $vbal;
					
                    
                    $qryZ = "select hid,descrip from jest..CommissionHistory where njobid='".$mv['JobNumber']."' and cbtype=0;";
                    $resZ  = mssql_query($qryZ);
                    $rowZ = mssql_fetch_array($resZ);
                    $nrowZ = mssql_num_rows($resZ);
                    
                    if ($nrowZ > 0)
                    {
                        $cnotes=$rowZ['descrip'];
                    }
                    else
                    {
                        $cnotes='';
                    }
                    
					$cnt++;
                    echo "					<tr>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['CD']."</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['JobNumber']."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"left\" title=\"".$mv['JournalSource']."\">\n";
					echo "							<table class=\"transnb\" width=\"100%\">\n";
					echo "								<tr>\n";
					echo "									<td align=\"left\">\n";
					
					if (preg_match('/SR Comm/i',trim($mv['Description'])))
					{
						$mv['Description']=preg_replace('/SR Comm/i','SRC',trim($mv['Description']));
					}
				
					echo ucwords(trim($mv['Description']));
					echo "									</td>\n";
					echo "                      			<td align=\"right\">";
					
					if ($_SESSION['jlev'] >= 9)
					{
						echo "										<form method=\"POST\">\n";
						echo "										    <input type=\"hidden\" name=\"action\" value=\"job\">\n";
						echo "										    <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
						echo "										    <input type=\"hidden\" name=\"njobid\" value=\"".$mv['JobNumber']."\">\n";
						echo "										    <input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
						echo "										    <input class=\"transnb\" type=\"image\" src=\"images/bullet_go.png\" height=\"8\" width=\"8\" alt=\"View Job\">\n";
						echo "										</form>\n";
					}
					
                    echo "                      			</td>\n"; 
					echo "								</tr>\n";
					echo "							</table>\n";
					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\">\n";
                    
                    if ($mv['Par'] != 0)
                    {
                        echo $mv['Par'];
                    }
                    
                    echo "                      </td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
					
					if ($mv['Rate']!=0)
					{
						echo $mv['Rate'];
					}
                    else
                    {
                        echo "<img src=\"images/pixel.gif\">\n";
                    }
					
					echo "						</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\">\n";
					
					if ($mv['Par'] != 0 && $mv['OUJob']!=0)
					{
						if ($mv['OUJob'] < 0)
						{
							echo "<font color=\"red\">".$mv['OUJob']."</font>";
						}
						else
						{
							echo $mv['OUJob'];
						}
					}
                    else
                    {
                        echo "<img src=\"images/pixel.gif\">\n";
                    }
					
					echo "						</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
					
					if ($mv['OURate']!=0)
					{
						echo $mv['OURate'];
					}
                    else
                    {
                        echo "<img src=\"images/pixel.gif\">\n";
                    }
					
					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\">\n";
					
					if ($mv['OUAmount']!=0)
					{
						echo number_format($mv['OUAmount'], 2, '.', '')."</td>\n";
					}
                    else
                    {
                        echo "<img src=\"images/pixel.gif\">\n";
                    }
					
					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\">\n";
					
					if ($mv['MAAmount']!=0)
					{
						$vbal=$vbal+$mv['MAAmount'];
						$tbal=$tbal+$mv['MAAmount'];
						echo number_format($mv['MAAmount'], 2, '.', '')."</td>\n";
					}
                    else
                    {
                        echo "                          <img src=\"images/pixel.gif\">\n";
                    }
					
					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\">\n";
                    
                    echo number_format($vbal, 2, '.', '');
                    
                    echo "                      </td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
                    
                    if ($tbal < 0)
                    {
                        echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
                    }
                    else
                    {
                        echo number_format($tbal, 2, '.', '');
                    }
                        
                    echo "                      </td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "					</tr>\n";
					$totalcom_ar['C']=$totalcom_ar['C']+$vbal;
                }
				elseif ($mv['Type']=='N')
                {
					if (isset($_REQUEST['showcommission']) && $_REQUEST['showcommission']==1)
					{
						if ($mv['Amount']!=0)
						{
							$tbal=$tbal+$mv['Amount'];
							
							$cnt++;
							echo "					<tr>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['CD']."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['JobNumber']."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"left\" title=\"".$mv['JournalSource']."\">".ucwords(trim($mv['Description']))."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
							
							if ($mv['Rate']!=0)
							{
								echo $mv['Rate'];
							}
                            else
                            {
                                echo "<img src=\"images/pixel.gif\">\n";
                            }
							
							echo "						</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
								
							if ($tbal < 0)
							{
								echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
							}
							else
							{
								echo number_format($tbal, 2, '.', '');
							}
							
							echo "                      </td>\n";
							echo "                      <td class=\"wh_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
							echo "					</tr>\n";
							$totalcom_ar['M']=$totalcom_ar['M']+$mv['Amount'];
						}
					}
                }
                elseif ($mv['Type']=='S')
                {
					if (isset($_REQUEST['showcommission']) && $_REQUEST['showcommission']==1)
					{
						if ($mv['Amount']!=0)
						{
							$tbal=$tbal+$mv['Amount'];                            
							$cnt++;
							echo "					<tr>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['CD']."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
                            
                            if ($mv['JobNumber'] <= 5)
                            {
                                echo $mv['JobNumber'];
                            }
                            else
                            {
                                $qryZa = "select njobid from jest..cinfo where jobid='".$mv['JobNumber']."';";
                                $resZa  = mssql_query($qryZa);
                                $rowZa = mssql_fetch_array($resZa);
                                
                                if ($rowZa['njobid']!=0)
                                {
                                    echo $rowZa['njobid'];
                                }
                            }
                            
                            echo "                      </td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"left\" title=\"".$mv['JournalSource']."\">\n";
                            echo "							<table class=\"transnb\" width=\"100%\">\n";
                            echo "								<tr>\n";
                            echo "									<td align=\"left\">\n";
                            
                            if (preg_match('/Mngr Comm/i',trim($mv['Description'])))
                            {
                                $mv['Description']=preg_replace('/Mngr Comm/i','SMC ',trim($mv['Description']));
                            }
                        
                            echo ucwords(trim($mv['Description']));
                            
                            echo "                      			</td>\n";
                            echo "                      			<td align=\"right\">";
					
                            if ($_SESSION['jlev'] >= 6)
                            {
                                if ($rowZa['njobid']!='0')
                                {
                                    echo "										<form method=\"POST\">\n";
                                    echo "										<input type=\"hidden\" name=\"action\" value=\"job\">\n";
                                    echo "										<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
                                    echo "										<input type=\"hidden\" name=\"njobid\" value=\"".$rowZa['njobid']."\">\n";
                                    echo "										<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
                                    echo "										<input class=\"transnb\" type=\"image\" src=\"images/bullet_go.png\" height=\"7\" width=\"7\" alt=\"View Job\">\n";
                                    echo "										</form>\n";
                                }
                                else
                                {
                                    echo "										<form method=\"POST\">\n";
                                    echo "										<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
                                    echo "										<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
                                    echo "										<input type=\"hidden\" name=\"jobid\" value=\"".$mv['JobNumber']."\">\n";
                                    echo "										<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
                                    echo "										<input class=\"transnb\" type=\"image\" src=\"images/bullet_go.png\" height=\"7\" width=\"7\" alt=\"View Contract\">\n";
                                    echo "										</form>\n";
                                }
                            }
                            else
                            {
                                echo "<img src=\"images/pixel.gif\">\n";
                            }
                            
                            echo "                      			</td>\n"; 
                            echo "								</tr>\n";
                            echo "							</table>\n";
                            echo "						</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\">".$mv['Par']."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
							
							if ($mv['Rate']!=0)
							{
								if ($mv['Rate'] < 0)
								{
									echo ($mv['Rate'] * 100);
								}
								else
								{
									echo $mv['Rate'];
								}
							}
							else
                            {
                                echo "<img src=\"images/pixel.gif\">\n";
                            }
							
							echo "						</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
							echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
							echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
								
							if ($tbal < 0)
							{
								echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
							}
							else
							{
								echo number_format($tbal, 2, '.', '');
							}
							
							echo "                      </td>\n";
							echo "                      <td class=\"wh_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
							echo "					</tr>\n";
							$totalcom_ar['C']=$totalcom_ar['C']+$mv['Amount'];
						}
					}
                }
                elseif ($mv['Type']=='A' && (isset($_REQUEST['showadjust']) && $_REQUEST['showadjust']==1))
                {
                    $tbal=$tbal+$mv['Amount'];
					
					if (isset($mv['uid']) && $mv['uid']!=0)
					{
						$qryZa = "select lname,fname from jest..security where securityid='".$mv['uid']."';";
						$resZa = mssql_query($qryZa);
						$rowZa = mssql_fetch_array($resZa);
						
						$dtxt="title=\"".$rowZa['lname'].', '.$rowZa['fname']."\"";
					}
					else
					{
						$dtxt='';
					}
					
					$cnt++;
                    echo "					<tr>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['CD']."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"left\" ".$dtxt.">\n";
					echo "							<table class=\"transnb\" width=\"100%\">\n";
					echo "								<tr>\n";
					echo "									<td align=\"left\">\n";
					echo ucwords(trim($mv['Description']));
					echo "									</td>\n";
					echo "                      			<td align=\"right\">";					
                    echo "                      			</td>\n";
					echo "								</tr>\n";
					echo "							</table>\n";
					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
                        
					if ($tbal < 0)
					{
						echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
					}
					else
					{
						echo number_format($tbal, 2, '.', '');
					}
					
					echo "                      </td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"center\">\n";
					
					if ($highestprocdate < strtotime($mv['Date']))
					{
						if ($_SESSION['clev'] >= 9 && $row0b['officeid']==89)
						{
							//echo "							<a href=\"subs/manadjcomm.php?action=mancommdel&secid=".$rowA['securityid']."&hid=".$mv['ReferenceNumber']."\" target=\"JMSchild\" title=\"Delete this Entry\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSchild','HEIGHT=150,WIDTH=350,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a>\n";
							//echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><a href=\"#\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
							echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><input type=\"hidden\" name=\"#".$mv['ReferenceNumber']."\"><a href=\"#".$mv['ReferenceNumber']."\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
						}
						else
						{
							echo "                      <img src=\"images/pixel.gif\">\n";
						}
					}
                    else
                    {
                        echo "                      <img src=\"images/pixel.gif\">\n";
                    }
                    
                    echo "                      </td>\n";
                    echo "					</tr>\n";
					$totalcom_ar['A']=$totalcom_ar['A']+$mv['Amount'];
                }
                elseif ($mv['Type']=='P' && isset($_REQUEST['showpipeline']) && $_REQUEST['showpipeline']==1)
                {
                    //$tbal=$tbal+$mv['Amount'];
					if (($mv['Division']==1 || $mv['Division']==4) && $mv['Amount']!=0)
					{
						$vbal=$mv['Amount'] + $mv['OUAmount'];
						
						if (isset($_REQUEST['calcpipeline']) && $_REQUEST['calcpipeline']==1)
						{
							$tbal=$tbal + $vbal;
						}
                        
                        $qryZ = "select csid,label,amt,notes from jest..CommissionSchedule where jobid='".$mv['JobNumber']."' and cbtype=0;";
                        $resZ  = mssql_query($qryZ);
                        $rowZ = mssql_fetch_array($resZ);
                        $nrowZ = mssql_num_rows($resZ);
                        
                        if ($nrowZ > 0)
                        {
                            $pnotes=$rowZ['notes'];
                        }
                        else
                        {
                            $pnotes='';
                        }
                        
                        $iidbg=0;
                        if ($iidbg==1 && $_SESSION['securityid']==SYS_ADMIN)
                        {
                            echo '<pre>';
                            
                            print_r($mv);
                            
                            echo '</pre>';
                        }
						
						$cnt++;
						echo "					<tr>\n";
						//echo "                      <td class=\"".$tbc."\" align=\"right\"><div onclick=\"SwitchMenu('".$mv['Type'].$cnt."')\">".$cnt.".</div></td>\n";
                        echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['CD']."</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
                        
                        $qryZa = "select njobid from jest..cinfo where jobid='".$mv['JobNumber']."';";
                        $resZa  = mssql_query($qryZa);
                        $rowZa = mssql_fetch_array($resZa);
                        
                        if ($rowZa['njobid']!=0)
                        {
                            echo $rowZa['njobid'];
                        }
                        
                        echo "                      </td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"left\" title=\"".$mv['JournalSource']."\">\n";
						echo "							<table class=\"transnb\" width=\"100%\">\n";
						echo "								<tr>\n";
						echo "									<td align=\"left\">\n";
						
						if (preg_match('/Base Comm/i',trim($mv['Description'])))
						{
							$mv['Description']=preg_replace('/Base Comm/i','PSRC ',trim($mv['Description']));
						}
						
						if (preg_match('/Manual Comm Adjust/i',trim($mv['Description'])))
						{
							$mv['Description']=preg_replace('/Manual Comm Adjust/i','PSRM ',trim($mv['Description']));
						}
						
						if (preg_match('/Mngr Comm/i',trim($mv['Description'])))
						{
							$mv['Description']=preg_replace('/Mngr Comm/i','PSMC ',trim($mv['Description']));
						}
						
						echo ucwords(trim($mv['Description']));
						
						echo "									</td>\n";
						echo "                      			<td align=\"right\">";
						
						if ($_SESSION['clev'] >= 1 && ($mv['Division']==1 || $mv['Division']==4))
						{
                            if ($rowZa['njobid']!=0)
                            {
                                echo "										<form method=\"POST\">\n";
                                echo "										<input type=\"hidden\" name=\"action\" value=\"job\">\n";
                                echo "										<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
                                echo "										<input type=\"hidden\" name=\"njobid\" value=\"".$rowZa['njobid']."\">\n";
                                echo "										<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
                                echo "										<input class=\"transnb\" type=\"image\" src=\"images/bullet_go.png\" height=\"7\" width=\"7\" alt=\"View Job\">\n";
                                echo "										</form>\n";
                            }
                            else
                            {
                                echo "										<form method=\"POST\">\n";
                                echo "										<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
                                echo "										<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
                                echo "										<input type=\"hidden\" name=\"jobid\" value=\"".$mv['JobNumber']."\">\n";
                                echo "										<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
                                echo "										<input class=\"transnb\" type=\"image\" src=\"images/bullet_go.png\" height=\"7\" width=\"7\" alt=\"View Contract\">\n";
                                echo "										</form>\n";
                            }
						}
						
						echo "                      			</td>\n"; 
						echo "								</tr>\n";
						echo "							</table>\n";
						echo "						</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"right\">\n";
                        
                        echo $mv['Par'];
                        
                        echo "                      </td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
						
						if ($mv['Rate']!=0)
						{
							echo $mv['Rate'];
						}
						
						echo "						</td>\n";
                        echo "                      <td class=\"".$tbc."\" align=\"right\">\n";
                        
                        if (isset($p6rate_ar) && count($p6rate_ar) > 0)
                        {
                            echo "<div class=\"infobox\">\n";
                            echo "  <div>".number_format($mv['Amount'], 2, '.', '')."</div>\n";
                            echo "  <div class=\"more\">".number_format($mv['Amount'], 2, '.', '')."</div>\n";
                            echo "</div>\n";
                        }
                        else
                        {
                            echo number_format($mv['Amount'], 2, '.', '');
                        }
                        
                        echo "						</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"right\">\n";
						
						if ($mv['OUJob']!=0)
						{
							if ($mv['OUJob'] < 0)
							{
								echo "<font color=\"#CC0000\">".$mv['OUJob']."</font>";
							}
							else
							{
								echo $mv['OUJob'];
							}
						}
						
						echo "						</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"center\">\n";
						
						if ($mv['OURate']!=0)
						{
							echo $mv['OURate'];
						}
						
						echo "						</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"right\">\n";
						
						if ($mv['OUAmount']!=0)
						{
							echo number_format($mv['OUAmount'], 2, '.', '')."</td>\n";
						}
						
						echo "						</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"right\" title=\"".$pnotes."\">\n";
					
						if ($mv['MAAmount']!=0)
						{
							$vbal=$vbal+$mv['MAAmount'];
							
							if (isset($_REQUEST['calcpipeline']) && $_REQUEST['calcpipeline']==1)
							{
								$tbal=$tbal+$mv['MAAmount'];
							}
							
							echo number_format($mv['MAAmount'], 2, '.', '')."</td>\n";
						}
                        else
                        {
                            echo "<img src=\"images/pixel.gif\">\n";
                        }
						
						echo "						</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"right\" title=\"Amount does not apply to balance until pool is Dug\">".number_format($vbal, 2, '.', '')."</td>\n";
						echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
							
						if ($tbal < 0)
						{
							echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
						}
						else
						{
							echo number_format($tbal, 2, '.', '');
						}
							
						echo "                      </td>\n";
                        echo "                      <td class=\"wh_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						echo "					</tr>\n";
						$totalcom_ar['P']=$totalcom_ar['P'] + ($mv['Amount'] + $mv['OUAmount'] + $mv['MAAmount']);
					}
                }
                elseif ($mv['Type']=='D' && isset($_REQUEST['showdraw']) && $_REQUEST['showdraw']==1)
                {
                    $tbal=$tbal + ($mv['Amount'] * -1);
					$tbaltxt='';
					$cnt++;
                    echo "					<tr>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">\n";					
					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"left\">\n";
					echo "							<table class=\"transnb\" width=\"100%\">\n";
					echo "								<tr>\n";
					echo "									<td align=\"left\">\n";
					
					if (preg_match('/Payroll Check/i',trim($mv['Description'])))
					{
						$mv['Description']=preg_replace('/Payroll Check/i','PRC',trim($mv['Description']));
					}
					
					echo ucwords(trim($mv['Description']));
					echo "									</td>\n";
					echo "                      			<td align=\"right\">";
					
					if ($row0b['officeid']==89)
					{
						if (isset($mv['SequenceNumber']) && strlen($mv['SequenceNumber']) > 15)
						{
							//echo "							nnnn<a href=\".\subs\manadjcomm.php?action=mancommdel&secid=".$rowA['securityid']."&hid=".$mv['ReferenceNumber']."\" target=\"JMSchild\" title=\"Delete this Entry\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSchild','HEIGHT=150,WIDTH=350,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"><img hieght=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a>\n";
							//echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><a href=\"#\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
							echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><input type=\"hidden\" name=\"#".$mv['ReferenceNumber']."\"><a href=\"#".$mv['ReferenceNumber']."\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
						}
					}
					
                    echo "                      			</td>\n"; 
					echo "								</tr>\n";
					echo "							</table>\n";
					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\" title=\"".$tbaltxt."\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
 
					if ($tbal >= -.49 && $tbal <= 0) // Corrects Negative Rounding Display
					{
                        echo number_format(($tbal * -1), 2, '.', '');
					}
                    elseif ($tbal > 0)
                    {
                        echo number_format($tbal, 2, '.', '');
                    }
					else
					{
						echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
					}
					
					echo "                      </td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "					</tr>\n";
					$totalcom_ar['D']=$totalcom_ar['D'] + ($mv['Amount'] * -1);
                }
				elseif ($mv['Type']=='L' && isset($_REQUEST['showloan']) && $_REQUEST['showloan']==1)
                {
                    $tbal=$tbal + ($mv['Amount'] * -1);
					
					if (isset($mv['uid']) && $mv['uid']!=0)
					{
						$qryZa = "select lname,fname from jest..security where securityid='".$mv['uid']."';";
						$resZa = mssql_query($qryZa);
						$rowZa = mssql_fetch_array($resZa);
						
						$dtxt="title=\"".$rowZa['lname'].', '.$rowZa['fname']."\"";
					}
					else
					{
						$dtxt='';
					}
					
					$cnt++;
                    echo "					<tr>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['CD']."</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";					
					echo "                      <td class=\"".$tbc."\" align=\"left\" ".$dtxt.">\n";
					echo "							<table class=\"transnb\" width=\"100%\">\n";
					echo "								<tr>\n";
					echo "									<td class=\"transnb\" align=\"left\">\n";
					echo "										<font color=\"white\">\n";
					echo ucwords(trim($mv['Description']));
					echo "										</font>\n";
					echo "									</td>\n";
					echo "                      			<td align=\"right\">";
					
					if ($row0b['officeid']==89)
					{
						if (isset($mv['SequenceNumber']) && strlen($mv['SequenceNumber']) > 15)
						{
							//echo "							ccc<a href=\".\subs\manadjcomm.php?action=mancommdel&secid=".$rowA['securityid']."&hid=".$mv['ReferenceNumber']."\" target=\"JMSchild\" title=\"Delete this Entry\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSchild','HEIGHT=150,WIDTH=350,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"><img height=\"7\" width=\"7\" src=\"images/action_delete.gif\"></a>\n";
							echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><input type=\"hidden\" name=\"#".$mv['ReferenceNumber']."\"><a href=\"#".$mv['ReferenceNumber']."\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
						}
					}
					
                    echo "                      			</td>\n"; 
					echo "								</tr>\n";
					echo "							</table>\n";
					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
                        
					if ($tbal < 0)
					{
						echo "<font color=\"#CC0000\">".number_format($tbal, 2, '.', '')."</font>\n";
					}
					else
					{
						echo number_format($tbal, 2, '.', '');
					}
					
					echo "                      </td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"center\">\n";
                    
                    if ($highestprocdate < strtotime($mv['Date']))
					{
						if ($_SESSION['clev'] >= 9 && $row0b['officeid']==89)
						{
							///echo "							xxx<a href=\".\subs\manadjcomm.php?action=mancommdel&secid=".$rowA['securityid']."&hid=".$mv['ReferenceNumber']."\" target=\"JMSchild\" title=\"Delete this Entry\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSchild','HEIGHT=150,WIDTH=350,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a>\n";
							//echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><a href=\"#\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
							echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><input type=\"hidden\" name=\"#".$mv['ReferenceNumber']."\"><a href=\"#".$mv['ReferenceNumber']."\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
						}
						else
						{
							echo "                      <img src=\"images/pixel.gif\">\n";
						}
					}
                    else
                    {
                        echo "                      <img src=\"images/pixel.gif\">\n";
                    }
                    
                    echo "                      </td>\n";
                    echo "					</tr>\n";
					$totalcom_ar['L']=$totalcom_ar['L'] + ($mv['Amount'] * -1);
                }
				elseif ($mv['Type']=='B' && isset($_REQUEST['showbonus']) && $_REQUEST['showbonus']==1)
                {
                    //$tbal=$tbal;
					$vbal=$mv['Amount'];
                    $tbal=$tbal + $vbal;
					
					if (isset($mv['uid']) && $mv['uid']!=0)
					{
						$qryZa = "select lname,fname from jest..security where securityid='".$mv['uid']."';";
						$resZa = mssql_query($qryZa);
						$rowZa = mssql_fetch_array($resZa);
						
						$dtxt="title=\"".$rowZa['lname'].', '.$rowZa['fname']."\"";
					}
					else
					{
						$dtxt='';
					}
					
					$cnt++;
                    echo "					<tr>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['CD']."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"left\" ".$dtxt.">\n";

					echo ucwords(trim($mv['Description']));
                    
					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($mv['Amount'], 2, '.', '')."</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\">".number_format($vbal, 2, '.', '')."</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
                        
					if ($tbal < 0)
					{
						echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
					}
					else
					{
						echo number_format($tbal, 2, '.', '');
					}
					
					echo "                      </td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"center\">\n";
                    
                    if ($highestprocdate < strtotime($mv['Date']))
					{
						if ($_SESSION['clev'] >= 9 && $row0b['officeid']==89)
						{
							//echo "							yyy<a href=\".\subs\manadjcomm.php?action=mancommdel&secid=".$rowA['securityid']."&hid=".$mv['ReferenceNumber']."\" target=\"JMSchild\" title=\"Delete this Entry\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','JMSchild','HEIGHT=150,WIDTH=350,titlebar=no,copyhistory=no,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a>\n";
							//echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><a href=\"#\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
							echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><input type=\"hidden\" name=\"#".$mv['ReferenceNumber']."\"><a href=\"#".$mv['ReferenceNumber']."\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
						}
						else
						{
							echo "                      <img src=\"images/pixel.gif\">\n";
						}
					}
                    else
                    {
                        echo "                      <img src=\"images/pixel.gif\">\n";
                    }
                    
                    echo "                      </td>\n";
                    echo "					</tr>\n";
					$totalcom_ar['B']=$totalcom_ar['B'] + $vbal;
                }
                elseif ($mv['Type']=='R' && isset($_REQUEST['showbonus']) && $_REQUEST['showbonus']==1)
                {
					$validatedamt=$mv['Amount'];
					
					if (isset($mv['uid']) && $mv['uid']!=0)
					{
						$qryZa = "select lname,fname from jest..security where securityid='".$mv['uid']."';";
						$resZa = mssql_query($qryZa);
						$rowZa = mssql_fetch_array($resZa);
						
						$dtxt=number_format($validatedamt, 2, '.', '')." validated by ".$rowZa['lname'];
					}
					else
					{
						$dtxt='';
					}
					
					$cnt++;
                    echo "					<tr title=\"Balance Validated\">\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\">".$cnt.".</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\">".$mv['CD']."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"left\">\n";

					echo $dtxt;

					echo "						</td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\">".date('m/d/y',strtotime($mv['Date']))."</td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
					echo "                      <td class=\"".$tbc."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"right\">";
                        
					if ($tbal < 0)
					{
						echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
					}
					else
					{
						echo number_format($tbal, 2, '.', '');
					}
					
					echo "                      </td>\n";
                    echo "                      <td class=\"wh_undsidesb\" align=\"center\">\n";
                    
                    if ($row0b['officeid']==89 && $_SESSION['clev'] >= 9)
					//if ($_SESSION['securityid']==26)
					{
						echo "<div><input class=\"thishid\" type=\"hidden\" value=\"".$mv['ReferenceNumber']."\"><input type=\"hidden\" name=\"#".$mv['ReferenceNumber']."\"><a href=\"#".$mv['ReferenceNumber']."\" class=\"SCDeleteItem\"><img height=\"10\" width=\"10\" src=\"images/action_delete.gif\"></a></div>";
					}
                    else
                    {
                        echo "                          <img src=\"images/pixel.gif\">\n";
                    }
                    
                    echo "                      </td>\n";
                    echo "					</tr>\n";
                }
                //$cnt++;
            }
            
            $tbc='wh_undsidesb';
            echo "					<tr>\n";
			
			echo "						<td colspan=\"14\" class=\"".$tbc."\" align=\"right\">\n";
	
			if (isset($_REQUEST['calcpipeline']) && $_REQUEST['calcpipeline']==1)
			{
				echo "<font color=\"red\"><b>NOTE</b></font><b>: Displaying Pipeline/Pending Sales in Balance</b>";
			}
			else
			{
				echo "<b>End Balance</b>";
			}
			
			echo "						</td>\n";
			
            //echo "                      <td class=\"".$tbc."\" align=\"right\" colspan=\"2\"><b>End Balance</b></td>\n";
            echo "                      <td class=\"".$tbc."\" align=\"right\">";
			
            if ($tbal < 0)
            {
                echo "<font color=\"red\">".number_format($tbal, 2, '.', '')."</font>\n";
            }
            else
            {
                echo number_format($tbal, 2, '.', '');
            }
            
            echo "                      </td>\n";
            echo "                      <td class=\"wh_undsidesb\" align=\"center\">\n";
			
			if ((isset($_REQUEST['sid']) and $_REQUEST['sid']!=0) and $_SESSION['rlev'] >= 9)
			{
				echo "						<div class=\"noPrint\">\n";
				echo "							<input id=\"thistbal\" type=\"hidden\" name=\"#bal_btm\" value=\"".$tbal."\">\n";
				echo "							<a id=\"openvalidatedialog\" href=\"#bal_btm\"><img height=\"10\" width=\"10\" src=\"images/accept.png\" title=\"Click to Validate this Page\"></a>\n";
				echo "						</div>\n";
			}
			else
			{
				echo "							<img src=\"images/pixel.gif\">\n";
			}
			
			echo "						</td>\n";
            echo "					</tr>\n";
        }
        else
        {
            echo "					<tr>\n";
            echo "                      <td colspan=\"16\" class=\"wh_und\" align=\"center\"><b>No Records Found</b></td>\n";
            echo "					</tr>\n";
        }
        
        echo "				</table>\n";
        echo "			</td>\n";
        echo "		</tr>\n";
    }
	
    echo "	</table>\n";
	echo "</div>";
}