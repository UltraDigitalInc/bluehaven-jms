<?php

function CommProfileTemplateCopy()
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	if (isset($_REQUEST['toid']) && $_REQUEST['toid']!=0)
	{
		$randu= md5(session_id().time(). "." . $_SESSION['securityid']);
		$qryA = "exec tlh_CopyCommProfiles @toid=".$_REQUEST['toid'].",@fmid=".$_SESSION['officeid'].",@nsid=".$_SESSION['securityid'].",@randt='".$randu."';";
		$resA = mssql_query($qryA);
		
		//echo $qryA;
	}
}

function CommSingleProfileTemplateCopy()
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	//echo '<pre>';
	//echo var_dump($_REQUEST);
	//echo '</pre>';

	if ((isset($_REQUEST['cmid']) && $_REQUEST['cmid']!=0) and (isset($_REQUEST['stoid']) && $_REQUEST['stoid']!=0))
	{
		$randu= md5(session_id().time(). "." . $_SESSION['securityid']);
		$qryA = "exec tlh_CopySingleCommProfile @toid=".$_REQUEST['stoid'].",@fmid=".$_SESSION['officeid'].",@cmid=".$_REQUEST['cmid'].",@nsid=".$_SESSION['securityid'].",@randt='".$randu."';";
		$resA = mssql_query($qryA);
		
		//echo $qryA;
	}
}

function CommProfileList()
{	
	echo "<script type=\"text/javascript\" src=\"js/jquery_comm_builder_func.js\"></script>\n";
	echo "<div id=\"CommProfileWorkSpace\"></div>\n";
}

function CommProfileListOLD()
{
    //echo 'List<br>';
    
    $qryA = "SELECT catid,label FROM jest..CommissionBuilderCategory WHERE catid!=0 ORDER BY catid ASC;";
	$resA = mssql_query($qryA);
    $nrowA= mssql_num_rows($resA);
    
    while ($rowA = mssql_fetch_array($resA))
    {
        $catar[]=array('catid'=>$rowA['catid'],'label'=>$rowA['label']);
    }
    
    /*echo "<pre>";
	print_r($catar);
	echo "</pre>";*/
    
    $qry0  = "select CB.*, ";
    $qry0 .= "(select lname from jest..security where securityid=CB.sid) as cr, ";
    $qry0 .= "(select lname from jest..security where securityid=CB.secid) as sr, ";
    $qry0 .= "(select label from jest..CommissionBuilderCategory where catid=CB.ctgry) as cat ";
    $qry0 .= "from jest..CommissionBuilder as CB where oid='".$_SESSION['officeid']."' order by active desc,ctgry asc,secid asc;";
	$res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
    
    $qry1 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$res1 = mssql_query($qry1);
    $nrow1= mssql_num_rows($res1);
	
	$qry2 = "SELECT distinct(C.oid),(select name from offices where officeid=C.oid) as oname FROM CommissionBuilder as C WHERE oid != ".$_SESSION['officeid']." ORDER BY oname ASC;";
	$res2 = mssql_query($qry2);
    $nrow2= mssql_num_rows($res2);
    
	//echo "<script type=\"text/javascript\" src=\"js/jquery_help_func.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"js/jquery_comm_builder_func.js\"></script>\n";
	echo "<div id=\"CommProfileWorkSpace\"></div>\n";
    echo "<table class=\"transnb\" width=\"950px\">\n";
	echo "  <tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" width=\"100%\" align=\"left\">\n";
	echo "   			<tr>\n";
	echo "   			    <td class=\"gray\"><b>Commission Builder</b><img src=\"images/pixel.gif\">\n";

	//HelpNode('CommBuilderList',2);

	echo "				    </td>\n";
	
	if ($nrow2 > 0)
	{
		echo "   			    <td class=\"gray\" align=\"right\"><b>Copy Active Commission Profiles from this list to</b></td>\n";
		echo "   			    <td align=\"right\" class=\"gray\" width=\"100\">\n";
		echo "						<form id=\"add\" name=\"add\" method=\"POST\" onSubmit=\"return NoSelectAlert('toids','Office Profile');\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "							<input type=\"hidden\" name=\"call\" value=\"commbuilder\">\n";
		echo "							<input type=\"hidden\" name=\"subq\" value=\"copy1\">\n";
		echo "							<input type=\"hidden\" name=\"noCommProfs\" value=\"".$nrow0."\">\n";
		echo "                          	<select id=\"toids\" name=\"toid\">\n";
		echo "									<option value=\"0\">Select Target...</option>\n";
		
		while ($row2 = mssql_fetch_array($res2))
		{
			echo "									<option value=\"".$row2['oid']."\">".$row2['oname']."</option>\n";
		}
		
		echo "                          	</select>\n";
		echo "							<td align=\"right\" class=\"gray\" width=\"20\"><div onClick=\"return CopyWarning('noCommProfs','Commission Profiles');\"><input class=\"transnb\" type=\"image\" src=\"images/application_go.png\" title=\"Copy Active Commission Profiles\"></div></td>\n";
		echo "                  </td>\n";
		echo "						</form>\n";
	}
	
	echo "   			    <td class=\"gray\" align=\"right\"><b>Add New Profile</b></td>\n";
	
	if ($_SESSION['securityid']==26)
	{
		echo "   			    <td align=\"right\" class=\"gray\" width=\"20\">\n";
		echo "						<a href=\"#\" id=\"addNewCommProfile\"><img src=\"images/add.png\" title=\"Add New Profile\"></a>\n";
		echo "                  </td>\n";
	}
	
	echo "   			    <td align=\"right\" class=\"gray\" width=\"20\">\n";
	echo "						<form id=\"add\" name=\"add\" method=\"POST\">\n";
    echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "							<input type=\"hidden\" name=\"call\" value=\"commbuilder\">\n";
	echo "							<input type=\"hidden\" name=\"subq\" value=\"add1\">\n";
	echo "							<input class=\"transnb\" type=\"image\" src=\"images/add.png\" title=\"Add New Profile\">\n";
	echo "						</form>\n";
    echo "                  </td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
    echo "          <table class=\"outer\" width=\"100%\">\n";
    
    if (isset($_REQUEST['call']) && $_REQUEST['call']=='edit')
    {
        
    }
    else
    {
        if ($nrow0 > 0)
        {
            //echo "                              <td class=\"ltgray_und\" align=\"left\"></td>\n";
            //echo "                              <td class=\"ltgray_und\" align=\"center\"><b>Date</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"center\"><b>CMid</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"left\"><b>Name</b></td>\n";
			echo "                              <td class=\"ltgray_und\" align=\"center\"><b>Reno</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"left\"><b>Creator</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"left\"><b>SalesRep</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"left\"><b>Bns Grp</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"center\"><b>Bns Cat</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"center\"><b>Bns Type</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"center\"><b>Begin</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"center\"><b>End</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"center\"><b>Bns %</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"center\"><b>Trg Wgt</b></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"center\"><b>Bns Amt</b></td>\n";
			echo "                              <td class=\"ltgray_und\" align=\"left\"></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"left\"></td>\n";
            echo "                              <td class=\"ltgray_und\" align=\"left\"></td>\n";
            
            $cnt=1;
            while ($row0 = mssql_fetch_array($res0))
            {
                if ($row0['active']==1)
                {
                    $rclass='wh_und';
                }
                else
                {
                    $rclass='ltred_und';
                }
                
                echo "                          <tr>\n";
                //echo "                              <td class=\"".$rclass."\" align=\"right\">".$cnt++.".</td>\n";
                //echo "                              <td class=\"".$rclass."\" align=\"center\">".date('m/d/Y',strtotime($row0['adate']))."</td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\" title=\"".$row0['comment']."\">".$row0['cmid']."</td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"left\">".$row0['name']."</td>\n";
				echo "                              <td class=\"".$rclass."\" align=\"center\">\n";
                
                if ($row0['renov']==1)
                {
                    echo "Y";
                }
                else
                {
                    echo "N";
                }
                
                echo "                              </td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"left\">\n";
                
                echo $row0['cr'];
                
                echo "                              </td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"left\">\n";
                
                if ($row0['secid']==0)
                {
                    echo 'ALL';
                }
                else
                {
                    echo $row0['sr'];   
                }
                
                echo "                              </td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\">".$row0['linkid']."</td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\">".$row0['cat']."</td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\">\n";
                
                if ($row0['ctype']==1)
                {
                    echo 'Fixed';
                }
                else
                {
                    echo '%';
                }
                
                echo "                              </td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\">".date('m/d/Y',strtotime($row0['d1']))."</td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\">".date('m/d/Y',strtotime($row0['d2']))."</td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\">".($row0['rwdrate'] * 100)."</td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\">".$row0['trgwght']."</td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"right\">".number_format($row0['rwdamt'], 2, '.', ',')."</td>\n";
				echo "                              <td class=\"".$rclass."\" align=\"center\" width=\"20\">\n";
				
				if ($row0['active']==1)
				{
					echo "									<form class=\"frmcopySingle\" method=\"POST\">\n";
					echo "										<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
					echo "										<input type=\"hidden\" name=\"call\" value=\"commbuilder\">\n";
					echo "										<input type=\"hidden\" name=\"subq\" value=\"copySingle\">\n";
					echo "										<input type=\"hidden\" class=\"thiscmid\" name=\"cmid\" value=\"".$row0['cmid']."\">\n";
					echo "                                      <img src=\"images/application_go.png\" title=\"Copy Commission Profile\">\n";
					echo "                                  </form\n";
				}
				
                echo "                              </td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\" width=\"20\">\n";
                echo "                                  <form id=\"add\" name=\"add\" method=\"POST\">\n";
                echo "                                      <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
				echo "                                      <input type=\"hidden\" name=\"call\" value=\"commbuilder\">\n";
                echo "                                      <input type=\"hidden\" name=\"cmid\" value=\"".$row0['cmid']."\">\n";
                
                if ($row0['active']==1)
                {
                    echo "                                      <input type=\"hidden\" name=\"subq\" value=\"deactivate\">\n";
                    echo "                                      <input class=\"transnb\" type=\"image\" src=\"images/cross.png\" value=\"Deactivate\" title=\"Deactivate Commission Profile\">\n";
                }
                else
                {
                    echo "                                      <input type=\"hidden\" name=\"subq\" value=\"activate\">\n";
                    echo "                                      <input class=\"transnb\" type=\"image\" src=\"images/accept.png\" value=\"Activate\" title=\"Activate Commission Profile\">\n";
                }
                
                echo "                                  </form\n";
                echo "                              </td>\n";
                echo "                              <td class=\"".$rclass."\" align=\"center\" width=\"20\">\n";
                echo "                                  <form id=\"add\" name=\"add\" method=\"POST\">\n";
                echo "                                      <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
				echo "                                      <input type=\"hidden\" name=\"call\" value=\"commbuilder\">\n";
                echo "                                      <input type=\"hidden\" name=\"subq\" value=\"edit\">\n";
                echo "                                      <input type=\"hidden\" name=\"cmid\" value=\"".$row0['cmid']."\">\n";
                echo "                                      <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" value=\"Edit\" title=\"Edit Commission Profile\">\n";
                echo "                                  </form\n";
                echo "                              </td>\n";
                echo "                          </tr>\n";
            }
        }
        else
        {
            echo "                          <tr>\n";
            echo "                              <td class=\"wh\" align=\"center\"><b>No Commission Builder Entries Found</b></td>\n";
            echo "                          </tr>\n";
        }
    }
    
    echo "                      </table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function CommProfileAdd1()
{
    $qryA = "SELECT catid,label,descrip FROM jest..CommissionBuilderCategory WHERE catid != 0 and access <= 6 ORDER BY catid ASC;";
	$resA = mssql_query($qryA);
    $nrowA= mssql_num_rows($resA);
    
    while ($rowA = mssql_fetch_array($resA))
    {
        $catar[]=array('catid'=>$rowA['catid'],'descrip'=>$rowA['descrip']);
    }
    
    $qry0  = "select CB.*, ";
    $qry0 .= "(select lname from jest..security where securityid=CB.sid) as cr, ";
    $qry0 .= "(select lname from jest..security where securityid=CB.secid) as sr, ";
    $qry0 .= "(select label from jest..CommissionBuilderCategory where catid=CB.ctgry) as cat ";
    $qry0 .= "from jest..CommissionBuilder as CB where oid='".$_SESSION['officeid']."' order by active desc,ctgry asc,secid asc;";
	$res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
    
    $qry1 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$res1 = mssql_query($qry1);
    $nrow1= mssql_num_rows($res1);
    
    $dp  =md5(session_id().time().$rowA['catid']).".".$_SESSION['securityid'];
    echo "                      <form id=\"add\" name=\"add\" method=\"POST\">\n";
    echo "                      <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "                      <input type=\"hidden\" name=\"call\" value=\"commbuilder\">\n";
	echo "                      <input type=\"hidden\" name=\"subq\" value=\"add2\">\n";
    echo "                      <input type=\"hidden\" name=\"dp\" value=\"".$dp."\">\n";
    echo "                      <table class=\"outer\">\n";
    echo "                          <tr>\n";
	echo "                              <td align=\"right\">\n";
    echo "                      <table>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"ltgray_und\" align=\"right\"><b>Add Commission Profile</b></td>\n";
	echo "                              <td class=\"ltgray_und\" align=\"right\" colspan=\"2\">\n";
    echo "								    <img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Active</b></td>\n";
	echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"active\">\n";
    echo "                                      <option value=\"1\">Yes</option>\n";
    echo "                                      <option value=\"0\">No</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <img src=\"images/pixel.gif\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Sales Rep</b></td>\n";
	echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <select name=\"secid\">\n";
    echo "                                      <option value=\"0\">All</option>\n";
    
    if ($nrow1 > 0)
    {
        while ($row1 = mssql_fetch_array($res1))
        {
            if (substr(trim($row1['slevel']),-1) >= 1)
            {
                echo "                                      <option class=\"fontblack\" value=\"".$row1['securityid']."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
            }
            else
            {
                echo "                                      <option class=\"fontred\" value=\"".$row1['securityid']."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
            }
        }
    }
    
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"sidhlp\" title=\"Select ALL for this Profile to apply to All SalesReps\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Category</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"ctgry\">\n";
    echo "                                      <option value=\"0\">Select...</option>\n";
    
    foreach ($catar as $cn => $cv)
    {
        echo "                                      <option value=\"".$cv['catid']."\">".$cv['descrip']."</option>\n";
    }
    
    
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"ctgryhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Dates Active</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"d1\" id=\"d1\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <img src=\"images/pixel.gif\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"d2\" id=\"d2\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <img src=\"images/pixel.gif\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Bonus Type</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"ctype\">\n";
    echo "                                          <option value=\"0\">Select...</option>\n";
    echo "                                          <option value=\"1\">Fixed</option>\n";
    echo "                                          <option value=\"2\">Percent</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"ctypehlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Bonus % Rate</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"rwdrate\" value=\"0\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"ratehlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Bonus Fixed Amt</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtrght\" type=\"text\" name=\"rwdamt\" value=\"0.00\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"amthlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Source Value</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"trgsrcval\">\n";
    echo "                                          <option value=\"0\">None</option>\n";
    echo "                                          <option value=\"1\">Contract Amt</option>\n";
	echo "                                          <option value=\"2\">O/U Comm</option>\n";
	echo "                                          <option value=\"3\">Adj Book Value</option>\n";
	echo "                                          <option value=\"7\">Total Commission</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Trigger Source</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"trgsrc\">\n";
    echo "                                          <option value=\"0\">None</option>\n";
    echo "                                          <option value=\"1\">Base Comm</option>\n";
    echo "                                          <option value=\"2\">O/U Comm</option>\n";
	echo "                                          <option value=\"7\">Total Commission</option>\n";
	echo "                                          <option value=\"4\">Contract Amt</option>\n";
    echo "                                          <option value=\"6\">Bullet Weight</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Trigger Weight</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"trgwght\" value=\"0\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Trigger Amt</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"trgamt\" value=\"0\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Group with</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"linkid\">\n";
    echo "                                          <option value=\"0\">Select...</option>\n";
    
    if ($nrow0 > 0)
    {
        while ($row0 = mssql_fetch_array($res0))
        {
            if ($row0['active']==1)
            {
                echo "                                      <option class=\"fontblack\" value=\"".$row0['cmid']."\">".$row0['cmid']." ".$row0['name']."</option>\n";
            }
            else
            {
                echo "                                      <option class=\"fontred\" value=\"".$row0['cmid']."\">".$row0['cmid']." ".$row0['name']."</option>\n";
            }
        }
    }
    
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Renovation</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"trenov\">\n";
    echo "                                          <option value=\"0\">No</option>\n";
    echo "                                          <option value=\"1\">Yes</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"trenovhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
    echo "                              <td class=\"gray\" colspan=\"3\" align=\"right\">\n";
    echo "                                  <input class=\"transnb\" type=\"image\" src=\"images/save.gif\" value=\"Save\" title=\"Save Commission Profile\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                      </table>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                      </table>\n";
    echo "                      </form>\n";
}

function CommProfileView()
{
    $qryA = "SELECT catid,label,descrip FROM jest..CommissionBuilderCategory WHERE catid != 0 and access <= 6 ORDER BY catid ASC;";
	$resA = mssql_query($qryA);
    $nrowA= mssql_num_rows($resA);
    
    while ($rowA = mssql_fetch_array($resA))
    {
        $catar[]=array('catid'=>$rowA['catid'],'descrip'=>$rowA['descrip']);
    }
    
    $qry0  = "select CB.*, ";
    $qry0 .= "(select lname from jest..security where securityid=CB.sid) as cr, ";
    $qry0 .= "(select lname from jest..security where securityid=CB.secid) as sr, ";
    $qry0 .= "(select label from jest..CommissionBuilderCategory where catid=CB.ctgry) as cat ";
    $qry0 .= "from jest..CommissionBuilder as CB where oid='".$_SESSION['officeid']."' order by active desc,ctgry asc,secid asc;";
	$res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
    
    $qry1 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$res1 = mssql_query($qry1);
    $nrow1= mssql_num_rows($res1);
    
    $dp  =md5(session_id().time().$rowA['catid']).".".$_SESSION['securityid'];
    echo "                      <form id=\"add\" name=\"add\" method=\"POST\">\n";
    echo "                      <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "                      <input type=\"hidden\" name=\"call\" value=\"commbuilder\">\n";
	echo "                      <input type=\"hidden\" name=\"subq\" value=\"add2\">\n";
    echo "                      <input type=\"hidden\" name=\"dp\" value=\"".$dp."\">\n";
    echo "                      <table class=\"outer\">\n";
    echo "                          <tr>\n";
	echo "                              <td align=\"right\">\n";
    echo "                      <table>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"ltgray_und\" align=\"right\"><b>Add Commission Profile</b></td>\n";
	echo "                              <td class=\"ltgray_und\" align=\"right\" colspan=\"2\">\n";
    echo "								    <img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Active</b></td>\n";
	echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"active\">\n";
    echo "                                      <option value=\"1\">Yes</option>\n";
    echo "                                      <option value=\"0\">No</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <img src=\"images/pixel.gif\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Sales Rep</b></td>\n";
	echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <select name=\"secid\">\n";
    echo "                                      <option value=\"0\">All</option>\n";
    
    if ($nrow1 > 0)
    {
        while ($row1 = mssql_fetch_array($res1))
        {
            if (substr(trim($row1['slevel']),-1) >= 1)
            {
                echo "                                      <option class=\"fontblack\" value=\"".$row1['securityid']."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
            }
            else
            {
                echo "                                      <option class=\"fontred\" value=\"".$row1['securityid']."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
            }
        }
    }
    
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"sidhlp\" title=\"Select ALL for this Profile to apply to All SalesReps\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Category</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"ctgry\">\n";
    echo "                                      <option value=\"0\">Select...</option>\n";
    
    foreach ($catar as $cn => $cv)
    {
        echo "                                      <option value=\"".$cv['catid']."\">".$cv['descrip']."</option>\n";
    }
    
    
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"ctgryhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Dates Active</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"d1\" id=\"d1\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <img src=\"images/pixel.gif\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"d2\" id=\"d2\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <img src=\"images/pixel.gif\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Bonus Type</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"ctype\">\n";
    echo "                                          <option value=\"0\">Select...</option>\n";
    echo "                                          <option value=\"1\">Fixed</option>\n";
    echo "                                          <option value=\"2\">Percent</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"ctypehlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Bonus % Rate</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"rwdrate\" value=\"0\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"ratehlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Bonus Fixed Amt</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtrght\" type=\"text\" name=\"rwdamt\" value=\"0.00\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"amthlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Source Value</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"trgsrcval\">\n";
    echo "                                          <option value=\"0\">None</option>\n";
    echo "                                          <option value=\"1\">Contract Amt</option>\n";
	echo "                                          <option value=\"2\">O/U Comm</option>\n";
	echo "                                          <option value=\"3\">Adj Book Value</option>\n";
	echo "                                          <option value=\"7\">Total Commission</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Trigger Source</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"trgsrc\">\n";
    echo "                                          <option value=\"0\">None</option>\n";
    echo "                                          <option value=\"1\">Base Comm</option>\n";
    echo "                                          <option value=\"2\">O/U Comm</option>\n";
	echo "                                          <option value=\"7\">Total Commission</option>\n";
	echo "                                          <option value=\"4\">Contract Amt</option>\n";
    echo "                                          <option value=\"6\">Bullet Weight</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Trigger Weight</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"trgwght\" value=\"0\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Trigger Amt</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <input class=\"brdrtxtcntr\" type=\"text\" name=\"trgamt\" value=\"0\" size=\"7\">\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Group with</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"linkid\">\n";
    echo "                                          <option value=\"0\">Select...</option>\n";
    
    if ($nrow0 > 0)
    {
        while ($row0 = mssql_fetch_array($res0))
        {
            if ($row0['active']==1)
            {
                echo "                                      <option class=\"fontblack\" value=\"".$row0['cmid']."\">".$row0['cmid']." ".$row0['name']."</option>\n";
            }
            else
            {
                echo "                                      <option class=\"fontred\" value=\"".$row0['cmid']."\">".$row0['cmid']." ".$row0['name']."</option>\n";
            }
        }
    }
    
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"threshhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td class=\"gray\" align=\"right\"><b>Renovation</b></td>\n";
    echo "                              <td class=\"gray\">\n";
    echo "                                  <select name=\"trenov\">\n";
    echo "                                          <option value=\"0\">No</option>\n";
    echo "                                          <option value=\"1\">Yes</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                              <td class=\"gray\" align=\"left\">\n";
    echo "                                  <div id=\"trenovhlp\"><img src=\"images/pixel.gif\"></div>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                          <tr>\n";
    echo "                              <td class=\"gray\" colspan=\"3\" align=\"right\">\n";
    echo "                                  <input class=\"transnb\" type=\"image\" src=\"images/save.gif\" value=\"Save\" title=\"Save Commission Profile\">\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                      </table>\n";
    echo "                              </td>\n";
	echo "                          </tr>\n";
    echo "                      </table>\n";
    echo "                      </form>\n";
}

function CommProfileAdd2()
{
    error_reporting(E_ALL);
    //echo 'Add<br>';
    $qryA = "SELECT cmid FROM jest..CommissionBuilder WHERE dupeproc='".$_REQUEST['dp']."';";
	$resA = mssql_query($qryA);
    $nrowA= mssql_num_rows($resA);
    
    if ($nrowA==0)
    {
        $qryB = "SELECT * FROM jest..CommissionBuilderCategory WHERE catid='".$_REQUEST['ctgry']."';";
        $resB = mssql_query($qryB);
        $rowB = mssql_fetch_array($resB);
        $nrowB= mssql_num_rows($resB);
        
        if ($nrowB==1)
        {
            $label=$rowB['descrip'];
        }
        else
        {
            $label='';
        }
        
        $qry0  = "INSERT INTO jest..CommissionBuilder ";
        $qry0 .= "(oid,sid,secid,ctgry,ctype,name,rwdrate,rwdamt,trgwght,trgsrc,trgsrcval,linkid,d1,d2,active,uid,dupeproc,renov)";
        $qry0 .= " VALUES ";
        $qry0 .= "(";
        $qry0 .= "".$_SESSION['officeid'].",";
        $qry0 .= "".$_SESSION['securityid'].",";
        $qry0 .= "".$_REQUEST['secid'].",";
        $qry0 .= "".$_REQUEST['ctgry'].",";
        $qry0 .= "".$_REQUEST['ctype'].",";
        $qry0 .= "'".$label."',";
        $qry0 .= "cast('".($_REQUEST['rwdrate'] * .01)."' as float),";
        $qry0 .= "cast('".$_REQUEST['rwdamt']."' as money),";
        $qry0 .= "".$_REQUEST['trgwght'].",";
        $qry0 .= "".$_REQUEST['trgsrc'].",";
        $qry0 .= "".$_REQUEST['trgsrcval'].",";
        $qry0 .= "".$_REQUEST['linkid'].",";
        $qry0 .= "'".$_REQUEST['d1']." 00:00:00',";
        $qry0 .= "'".$_REQUEST['d2']." 23:59:59',";
        $qry0 .= "".$_REQUEST['active'].",";
        $qry0 .= "".$_SESSION['securityid'].",";
        $qry0 .= "'".$_REQUEST['dp']."',";
		$qry0 .= "'".$_REQUEST['trenov']."'";
        $qry0 .= ");";
        $res0  = mssql_query($qry0);
        
        //echo $qry0.'<br>';
    }
    
    CommProfileList();
}

function CommProfileDeactivate()
{
    //echo 'Deactivate<br>';
    $qry0  = "update jest..CommissionBuilder set active=0 where cmid=".$_REQUEST['cmid'].";";
	$res0  = mssql_query($qry0);
    
    CommProfileList();
}

function CommProfileActivate()
{
    //echo 'Deactivate<br>';
    $qry0  = "update jest..CommissionBuilder set active=1 where cmid=".$_REQUEST['cmid'].";";
	$res0  = mssql_query($qry0);
    
    CommProfileList();
}

function CommProfileDelete()
{
    //echo 'Delete<br>';
    $qry0  = "delete from jest..CommissionBuilder where cmid=".$_REQUEST['cmid'].";";
	$res0  = mssql_query($qry0);
    
    CommProfileList();
}

function basematrix()
{
    //echo 'Future Commission Builder Tool<br>';
	
	if ($_SESSION['securityid']==26)
	{
    if (!isset($_REQUEST['subq']))
    {
        CommProfileList();
    }
    else
    {
        if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='add1')
        {
            CommProfileAdd1();
        }
        elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='add2')
        {
            CommProfileAdd2();
        }
        elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='activate')
        {
            CommProfileActivate();
        }
        elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='deactivate')
        {
            CommProfileDeactivate();
        }
		elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='view')
        {
            CommProfileView();
        }
		elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='save')
        {
            CommProfileSave();
        }
        elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='add')
        {
            CommProfileDelete();
        }
        elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='list')
        {
            CommProfileList();
        }
		elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='copy1')
        {
            CommProfileTemplateCopy();
			CommProfileList();
        }
		elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='copySingle')
        {
            CommSingleProfileTemplateCopy();
			CommProfileList();
        }
    }
	}
	else
	{
		echo 'Commission Builder Offline for Maintenance';	
	}
}

?>