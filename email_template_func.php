<?php

function BaseMatrix()
{
    if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='list')
    {
        //echo 'sdd';
        ListTemplates();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='view')
    {
        ViewTemplate();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='add')
    {
        AddTemplate();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='edit')
    {
        EditTemplate();
        ViewTemplate();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='save')
    {
        SaveTemplate();
        ListTemplates();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='removelink')
    {
        RemoveLink();
        ViewTemplate();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='createlink')
    {
        CreateLink();
        ViewTemplate();
    }
    else
    {
        echo 'xx';
        ListTemplates();
    }
}

function ListTemplates()
{
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    
    $emtemp_ar=array();
    
    $qry0 = "
            select
                E.*,
                (select lname from security where securityid=E.aid) as aidlname,
                (select lname from security where securityid=E.uid) as uidlname,
                (select count(etid) from leadstatuscodes where etid=E.etid) as lidcnt
            from
                EmailTemplate as E
            where
                E.oid=0
                or E.oid=".$_SESSION['officeid']."
            order by E.active desc,E.name;
            ";
    $res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
    
    while ($row0 = mssql_fetch_array($res0))
    {
        $emtemp_ar[]=array(
                           'etid'=>$row0['etid'],
                           'oid'=>$row0['oid'],
                           'lidcnt'=>$row0['lidcnt'],
                           'aidlname'=>$row0['aidlname'],
                           'uidlname'=>$row0['uidlname'],
                           'aid'=>$row0['aid'],
                           'uid'=>$row0['uid'],
                           'adate'=>$row0['adate'],
                           'udate'=>$row0['udate'],
                           'senddelay'=>$row0['senddelay'],
                           'name'=>$row0['name'],
                           'active'=>$row0['active']
                           );
    }
    
    echo "<table width=\"900px\">\n";
    echo "  <tr>\n";
    echo "      <td>\n";
    echo "          <table class=\"outer\" width=\"100%\" align=\"right\">\n";
    echo "   			<tr>\n";
    echo "      			<td align=\"left\"><b>Email Templates</b></td>\n";
    echo "      			<td align=\"center\" width=\"20\">\n";
    
    HelpNode('emailtemplist',1);

    echo "					</td>\n";
    echo "      			<td align=\"center\" width=\"20\">\n";
    echo "                      <form method=\"post\">\n";
    echo "						    <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
    echo "						    <input type=\"hidden\" name=\"call\" value=\"emailtemplate\">\n";
    echo "						    <input type=\"hidden\" name=\"subq\" value=\"add\">\n";
    echo "                          <input class=\"transnb\" type=\"image\" src=\"images/application_add.png\" title=\"Add New Template\">\n";
    echo "                      </form>\n";
    echo "				</td>\n";
    echo "         		</tr>\n";
    echo "          </table>\n";
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "      <td>\n";
    
    if ($nrow0 > 0)
    {
        echo "          	<table class=\"outer\" width=\"100%\" align=\"right\">\n";
        echo "   				<tr class=\"tblhd\">\n";
        echo "      				<td><img src=\"images/pixel.gif\"></td>\n";
        echo "      				<td align=\"left\"><b>Name</b></td>\n";
		echo "		      			<td align=\"center\"><b>Security</b></td>\n";
        echo "		      			<td align=\"center\"><b>Added</b></td>\n";
        echo "		      			<td align=\"left\"><b>by</b></td>\n";
        echo "		      			<td align=\"center\"><b>Updated</b></td>\n";
        echo "		      			<td align=\"left\"><b>by</b></td>\n";
		echo "		      			<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
        echo "		      			<td align=\"right\">\n";
		echo "		                    <form method=\"post\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "							<input type=\"hidden\" name=\"call\" value=\"emailtemplate\">\n";
		echo "							<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
		echo "							<input class=\"transnb\" type=\"image\" src=\"images/arrow_refresh_small.png\" title=\"Refresh List\">\n";
		echo "							</form>\n";
		echo "						</td>\n";
        echo "					</tr>\n";
    
        $srcnt=1;
        foreach ($emtemp_ar as $n1 => $v1)
        {
            if ($v1['active']==0)
            {
                $tbg='ltred';
            }
            else
            {
                if ($srcnt%2)
                {
                    $tbg='white';
                }
                else
                {
                    $tbg='gray';
                }
            }
            
            echo "   			<tr>\n";
            echo "      			<td class=\"".$tbg."\" align=\"right\">".$srcnt++.".</td>\n";
            echo "      			<td class=\"".$tbg."\" align=\"left\">".$v1['name']."</td>\n";
            echo "      			<td class=\"".$tbg."\" align=\"center\">".$v1['active']."</td>\n";
            echo "      			<td class=\"".$tbg."\" align=\"center\">".date('m-d-Y',strtotime($v1['adate']))."</td>\n";
            echo "      			<td class=\"".$tbg."\" align=\"left\">".$v1['aidlname']."</td>\n";
            echo "      			<td class=\"".$tbg."\" align=\"center\">".date('m-d-Y',strtotime($v1['udate']))."</td>\n";
            echo "      			<td class=\"".$tbg."\" align=\"left\">".$v1['uidlname']."</td>\n";
	    echo "      			<td class=\"".$tbg."\" align=\"right\">\n";
	    //echo "					<img id=\"empreview\" src=\"images/email_open.png\" onClick=\"displayPopup('etid','".$_SESSION['officeid']."','".$_SESSION['securityid']."','0');\" title=\"Select an Email Template then click to Preview\">\n";
	    echo "                  		</td>\n";
            echo "      			<td class=\"".$tbg."\" align=\"right\">\n";
            echo "                      		<form name=\"viewemailtemplate\" method=\"post\">\n";
            echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
            echo "						<input type=\"hidden\" name=\"call\" value=\"emailtemplate\">\n";
            echo "						<input type=\"hidden\" name=\"subq\" value=\"view\">\n";
            echo "						<input type=\"hidden\" id=\"etid\" name=\"etid\" value=\"".$v1['etid']."\">\n";
            echo "                          			<input class=\"transnb\" type=\"image\" src=\"images/application_form_edit.png\" title=\"View Email Template\">\n";
            echo "                      		</form>\n";
            echo "                  		</td>\n";
            echo "   			</tr>\n";
        }
        
        echo "          </table>\n";
    }
    
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
}

function AddTemplate() {
    ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$ttid=array();
	$qry0	 = "select * from jest..EmailTemplateTypes order by name asc;";
    $res0	 = mssql_query($qry0);
	
	while ($row0 = mssql_fetch_array($res0))
    {
		$ttid[$row0['ettid']]=array($row0['name']);
	}
	
	$qry1	 = "select * from jest..EmailProfile order by elogin asc;";
    $res1	 = mssql_query($qry1);
	
	while ($row1 = mssql_fetch_array($res1))
    {
		$epid[$row1['pid']]=array($row1['elogin']);
	}

    $guid  =md5(session_id().time().$_SESSION['securityid']);
    
    echo "<script type=\"text/javascript\" src=\"js/jquery_emailtemplate_func.js\"></script>\n";
    echo "<table width=\"900px\">\n";
    echo "  <tr>\n";
    echo "      <td colspan=\"2\">\n";
    echo "          <table class=\"outer\" width=\"100%\">\n";
    echo "              <tr>\n";
    echo "              	<td align=\"left\"><b>Email Template Creator</b></td>\n";
    echo "      		    <td align=\"center\" width=\"20\">\n";
	
    HelpNode('emailtempadd',1);

    echo "			        </td>\n";
    echo "      			<td align=\"center\" width=\"20\">\n";
    echo "         		        <form id=\"listtemplates\" method=\"post\">\n";
    echo "						    <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
    echo "						    <input type=\"hidden\" name=\"call\" value=\"emailtemplate\">\n";
    echo "						    <input class=\"transnb\" type=\"image\" src=\"images/application_view_list.png\" title=\"Return to Template List\">\n";
    echo "         		        </form>\n";
    echo "				    </td>\n";
    echo "              </tr>\n";
    echo "          </table>\n";
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "      <td>\n";
    echo "          <form id=\"addemailtemplate\" name=\"addemailtemplate\" method=\"post\" onSubmit=\"return FormCheckValues('addemailtemplate','errtext');\">\n";
    echo "          <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
    echo "          <input type=\"hidden\" name=\"call\" value=\"emailtemplate\">\n";
    echo "          <input type=\"hidden\" name=\"subq\" value=\"save\">\n";
    echo "          <input type=\"hidden\" name=\"guid\" value=\"".$guid."\">\n";
    echo "          <input type=\"hidden\" id=\"FileAttach\" name=\"fileattach\">\n";
    echo "          <table class=\"outer\" width=\"100%\">\n";
    echo "              <tr>\n";
    echo "                  <td align=\"right\" valign=\"top\"><b>Name</b></td>\n";
    echo "                  <td align=\"left\"><input type=\"text\" id=\"TemplateName\" name=\"name\" size=\"30\" maxlength=\"32\"></td>\n";
    echo "      	    <td align=\"right\"></td>\n";
    echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td align=\"right\" valign=\"top\"><b>Email Subject</b></td>\n";
    echo "                  <td colspan=\"2\" align=\"left\"><input type=\"text\" id=\"EmailSubject\" name=\"esubject\" size=\"100\" maxlength=\"64\"></td>\n";
    echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td align=\"right\" valign=\"top\"><b>Email Body</b></td>\n";
    echo "                  <td colspan=\"2\" align=\"left\">\n";
    echo "                      <textarea cols=\"100\" rows=\"25\" id=\"EmailBody\" name=\"ebody\"></textarea>\n";
    echo "                  </td>\n";
    echo "              </tr>\n";
	echo "              <tr>\n";
    echo "                  <td align=\"right\"><b>Type</b></td>\n";
    echo "                  <td colspan=\"2\" align=\"left\">\n";
	echo "						<select name=\"ttype\">\n";
	
	foreach ($ttid as $n => $v)
	{
		echo "						<option value=\"".$n."\">".$v[0]."</option>\n";
	}
	
	echo "						</select>\n";
	echo "					</td>\n";
    echo "              </tr>\n";
	echo "              <tr>\n";
    echo "                  <td align=\"right\"><b>Email Profile</b></td>\n";
    echo "                  <td colspan=\"2\" align=\"left\">\n";
	echo "						<select name=\"epid\">\n";
	echo "							<option value=\"0\">None</option>\n";
	
	foreach ($epid as $n1 => $v1)
	{
		echo "							<option value=\"".$n1."\">".$v1[0]."</option>\n";
	}
	
	echo "						</select>\n";
	echo "					</td>\n";
    echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td align=\"right\"><b>Send Allowance</b></td>\n";
    echo "                  <td colspan=\"2\" align=\"left\"><input type=\"text\" id=\"SendAllowance\" name=\"sendallowance\" value=\"0\" size=\"5\" maxlength=\"4\"></td>\n";
    echo "              </tr>\n";
	//echo "              <tr>\n";
    //echo "                  <td align=\"right\"><b>Static Attachment</b></td>\n";
    //echo "                  <td colspan=\"2\" align=\"left\"><input type=\"text\" id=\"FileAttach\" name=\"fileattach\" size=\"25\" maxlength=\"32\"></td>\n";
    //echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td align=\"right\"><b>Allow File Attachment</b></td>\n";
    echo "                  <td colspan=\"2\" align=\"left\">\n";
    echo "                      <select id=\"UserFileAttach\" name=\"allowattach\">\n";
     
    if (isset($row0['allowattach']) and $row0['allowattach']==1) {
        echo "<option value=\"0\">No</option><option value=\"1\" SELECTED>Yes</option>\n";
    }
    else {
        echo "<option value=\"0\" SELECTED>No</option><option value=\"1\">Yes</option>\n";
    }
     
    echo "                      </select>\n";
    echo "                  </td>\n";
    echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td colspan=\"3\" align=\"center\"><div id=\"errtext\"></div></td>\n";
    echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td colspan=\"3\" align=\"right\"><input class=\"transnb\" type=\"image\" src=\"images/save.gif\" title=\"Save Template\"></td>\n";
    echo "              </tr>\n";
    echo "          </table>\n";
    echo "          </form>\n";
    echo "      </td>\n";
    echo "      <td valign=\"top\">\n";
    echo "          <table class=\"outer\" width=\"100%\">\n";
    echo "              <tr>\n";
    echo "                  <td colspan=\"2\" align=\"left\"><b>Keyword List<b></td>\n";
    echo "              <tr>\n";
    echo "                  <td colspan=\"2\" align=\"left\">\n";
    echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">CUSTOMERFULLNAME</span></span></span><br/>\n";
    echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">CUSTOMERFIRSTNAME</span></span><br/>\n";
	echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">CUSTOMERLASTNAME</span></span><br/>\n";
	echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">CUSTOMEREMAILADDRESS</span></span><br/>\n";
	echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">APPOINTMENTDATETIME</span></span><br/>\n";
	echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">GMFULLNAME</span></span><br/>\n";
	echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">OFFICEPHONENUMBER</span></span><br/>\n";
	echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">SALESREPFULLNAME</span></span><br/>\n";
    echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">SALESREPPHONENUMBER</span></span><br/>\n";
	echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">CORPORATEFULLNAME</span></span><br/>\n";
	echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">BLANKMESSAGEENTRY</span></span><br/>\n";
    echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">PHASEOFCONSTRUCTION</span></span><br/>\n";
    echo "                      <span><img class=\"CopytoTextArea setpointer\" src=\"images/page_white_add.png\" title=\"Click to Add this Keyword to the EmailBody\"> <span class=\"CopytoTextAreaContent\">PHASEBEGINDATE</span></span><br/>\n";
    echo "                  </td>\n";
    echo "			   </table>\n";
    /*
    echo "          <table class=\"outer\" width=\"100%\">\n";
    echo "              <tr>\n";
    echo "                  <td colspan=\"2\" align=\"left\"><b>Keyword List<b></td>\n";
    echo "              <tr>\n";
    echo "                  <td colspan=\"2\" align=\"left\">\n";
    echo "                      <table width=\"100%\">";
    echo "                          <tr>\n";
	echo "                              <td><span id=\"CUSTOMERFULLNAME\" onClick=\"CopytoClipBoard('CUSTOMERFULLNAME','holdtext');\"><img src=\"images/page_white_add.png\"> CUSTOMERFULLNAME</span></td>\n";
	echo "                              <td align=\"center\"><textarea id=\"holdtext\" style=\"display:none;\"></textarea></td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td><span id=\"CUSTOMERFIRSTNAME\" onClick=\"CopytoClipBoard('CUSTOMERFIRSTNAME','holdtext');\"><img src=\"images/page_white_add.png\"> CUSTOMERFIRSTNAME</span></td>\n";
	echo "                              <td align=\"center\"></td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td><span id=\"CUSTOMERLASTNAME\" onClick=\"CopytoClipBoard('CUSTOMERLASTNAME','holdtext');\"><img src=\"images/page_white_add.png\"> CUSTOMERLASTNAME</span></td>\n";
	echo "                              <td align=\"center\"></td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td><span id=\"CUSTOMEREMAILADDRESS\" onClick=\"CopytoClipBoard('CUSTOMEREMAILADDRESS','holdtext');\"><img src=\"images/page_white_add.png\"> CUSTOMEREMAILADDRESS</span></td>\n";
	echo "                              <td align=\"center\"></td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td><span id=\"APPOINTMENTDATETIME\" onClick=\"CopytoClipBoard('APPOINTMENTDATETIME','holdtext');\"><img src=\"images/page_white_add.png\"> APPOINTMENTDATETIME</span></td>\n";
	echo "                              <td align=\"center\"></td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td><span id=\"GMFULLNAME\" onClick=\"CopytoClipBoard('GMFULLNAME','holdtext');\"><img src=\"images/page_white_add.png\"> GMFULLNAME</span></td>\n";
	echo "                              <td align=\"center\"></td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td><span id=\"OFFICEPHONENUMBER\" onClick=\"CopytoClipBoard('OFFICEPHONENUMBER','holdtext');\"><img src=\"images/page_white_add.png\"> OFFICEPHONENUMBER</span></td>\n";
	echo "                              <td align=\"center\"></td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td><span id=\"SALESREPFULLNAME\" onClick=\"CopytoClipBoard('SALESREPFULLNAME','holdtext');\"><img src=\"images/page_white_add.png\"> SALESREPFULLNAME</span></td>\n";
	echo "                              <td align=\"center\"></td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
    echo "                              <td><span id=\"SALESREPPHONENUMBER\" onClick=\"CopytoClipBoard('SALESREPPHONENUMBER','holdtext');\"><img src=\"images/page_white_add.png\"> SALESREPPHONENUMBER</span></td>\n";
    echo "                              <td align=\"center\"></td>\n";
    echo "                              <td></td>\n";
    echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td><span id=\"CORPORATEFULLNAME\" onClick=\"CopytoClipBoard('CORPORATEFULLNAME','holdtext');\"><img src=\"images/page_white_add.png\"> CORPORATEFULLNAME</span></td>\n";
	echo "                              <td align=\"center\"></td>\n";
	echo "                          </tr>\n";
    echo "			   </table>\n";
    */
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
}

function ViewTemplate() {
    ini_set('display_errors','On');
    error_reporting(E_ALL);

    $qry0 = "
            select
                E.*
                ,(select lname from security where securityid=E.aid) as aidlname
                ,(select fname from security where securityid=E.aid) as aidfname
                ,(select lname from security where securityid=E.uid) as uidlname
                ,(select fname from security where securityid=E.uid) as uidfname
            from
                EmailTemplate as E
            where
                E.etid=".$_REQUEST['etid'].";
            ";
    $res0 = mssql_query($qry0);
    $row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 0)
    {
        $qry1 = "
            select
                L.statusid,L.name,L.active
            from
                leadstatuscodes as L
            where
                L.active=1 and
                L.etid=".$_REQUEST['etid'].";
            ";
        $res1 = mssql_query($qry1);
        $nrow1= mssql_num_rows($res1);
        
        $qry2 = "
            select
                L.statusid,L.name as Lname,L.active as Lactive,S.srcid,S.name as Sname,S.active as Sactive
            from
                leadstatuscodes as L
            inner join
                leadsourcecodes as S
            on
                L.lsource=S.srcid
            where
                L.active = 1
            order by
            S.name,L.name;
            ";
        $res2 = mssql_query($qry2);
        $nrow2= mssql_num_rows($res2);
		
		$ttid=array();
		$qry3	 = "select * from jest..EmailTemplateTypes order by name asc;";
		$res3	 = mssql_query($qry3);
		
		while ($row3 = mssql_fetch_array($res3))
		{
			$ttid[$row3['ettid']]=array($row3['name']);
		}
		
		$qry4	 = "select * from jest..EmailProfile order by elogin asc;";
		$res4	 = mssql_query($qry4);
		
		while ($row4 = mssql_fetch_array($res4))
		{
			$epid[$row4['pid']]=array($row4['elogin']);
		}

        if (isset($row0['active']) && $row0['active']==0)
        {
            $hdrtbg='ltred';
        }
        else
        {
            $hdrtbg='gray';
        }
	
		$act_sel_ar=array(0=>'Inactive',1=>'Sales Rep',2=>'',3=>'',4=>'',5=>'Sales Man',6=>'Gen Man/Lead Admin',7=>'',8=>'',9=>'BHNM/Admin');
        
        echo "<script type=\"text/javascript\" src=\"js/jquery_emailtemplate_func.js\"></script>\n";
		echo "<table width=\"950px\">\n";
		echo "  <tr>\n";
		echo "      <td colspan=\"2\">\n";
		echo "          <table class=\"outer\" width=\"100%\">\n";
		echo "              <tr>\n";
		echo "                  <td class=\"ltgray\"><b>Email Template Editor</b></td>\n";
		echo "      			<td class=\"ltgray\" align=\"center\" width=\"20\">\n";
		echo "         		        <form id=\"listtemplates\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"emailtemplate\">\n";
		echo "						<input class=\"transnb\" type=\"image\" src=\"images/application_view_list.png\" title=\"Return to Template List\">\n";
		echo "         		        </form>\n";
		echo "					</td>\n";
		echo "      			<td class=\"ltgray\" align=\"right\" width=\"20px\">\n";
		
		HelpNode('emailtempview',1);
	
		echo "					</td>\n";
		echo "              </tr>\n";
		echo "          </table>\n";
		echo "      </td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "      <td width=\"750px\">\n";
		echo "          <form id=\"editemailtemplate\" name=\"editemailtemplate\" method=\"post\" onSubmit=\"return FormCheckValues('editemailtemplate','errtext');\">\n";
		echo "          <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "          <input type=\"hidden\" name=\"call\" value=\"emailtemplate\">\n";
		echo "          <input type=\"hidden\" name=\"subq\" value=\"edit\">\n";
		echo "          <input type=\"hidden\" name=\"etid\" value=\"".$row0['etid']."\">\n";
        echo "          <input type=\"hidden\" id=\"FileAttach\" name=\"fileattach\" value=\"".trim($row0['fileattach'])."\">\n";
		echo "          <table class=\"outer\" width=\"100%\">\n";
		echo "              <tr>\n";
		echo "                  <td align=\"right\"><b>Template Name</b></td>\n";
		echo "                  <td align=\"left\">\n";
		echo "          		<table width=\"100%\">\n";
		echo "					<tr>\n";
		echo "						<td align=\"left\">\n";
		echo "							<input type=\"text\" id=\"TemplateName\" name=\"name\" value=\"".trim($row0['name'])."\" size=\"30\" maxlength=\"32\">\n";
		echo "							<a href=\"subs/previewtemp.php?etid=".$row0['etid']."&cid=0&oid=".$_SESSION['officeid']."&sid=".$_SESSION['securityid']."\" target=\"subDocViewer\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','subDocViewer','HEIGHT=400,WIDTH=550,status=0,scrollbars=1,dependent=1,resizable=0,toolbar=0,menubar=0,location=0,directories=0'); window.status=''; return true;\"><img src=\"images/email_open.png\" title=\"Email Template Preview\"></a>\n";
		echo "						</td>\n";
		echo "						<td align=\"right\"><b>Security Level</b></td>\n";
		echo "						<td align=\"right\" width=\"145px\">\n";
		echo "							<select name=\"active\">\n";
	
		for ($x=0;$x <= 9;$x++)
		{
			if ($x==0 or $x==1 or $x==5 or$x==6 or $x==9) // Open up when more Security Levels are required
			{
				if ($row0['active']==$x)
				{
					echo "							<option value=\"".$x."\" SELECTED>".$x." - ".$act_sel_ar[$x]."</option>\n";
				}
				else
				{
					echo "							<option value=\"".$x."\">".$x." - ".$act_sel_ar[$x]."</option>\n";
				}
			}
		}
	
		echo "							</select>\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "			</td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"right\"><b>Email Subject</b></td>\n";
		echo "                  <td align=\"left\"><input type=\"text\" id=\"EmailSubject\" name=\"esubject\" value=\"".trim($row0['esubject'])."\" size=\"109\" maxlength=\"64\"></td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"right\" valign=\"top\"><b>Email Body</b></td>\n";
		echo "                  <td align=\"left\">\n";
		echo "                      <textarea class=\"tdroppable\" cols=\"110\" rows=\"35\" id=\"EmailBody\" name=\"ebody\">".trim($row0['ebody'])."</textarea>\n";
		echo "                  </td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"right\"><b>Type</b></td>\n";
		echo "                  <td colspan=\"2\" align=\"left\">\n";
		echo "						<select name=\"ttype\">\n";
		
		foreach ($ttid as $n => $v)
		{
			if ($n==$row0['ttype'])
			{
				echo "						<option value=\"".$n."\" SELECTED>".$v[0]."</option>\n";
			}
			else
			{
				echo "						<option value=\"".$n."\">".$v[0]."</option>\n";	
			}
		}
		
		echo "						</select>\n";
		echo "					</td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"right\"><b>Email Profile</b></td>\n";
		echo "                  <td colspan=\"2\" align=\"left\">\n";
		echo "						<select name=\"epid\">\n";
		echo "							<option value=\"0\">None</option>\n";
		
		foreach ($epid as $n1 => $v1)
		{
			if ($n1==$row0['epid'])
			{
				echo "						<option value=\"".$n1."\" SELECTED>".$v1[0]."</option>\n";
			}
			else
			{
				echo "						<option value=\"".$n1."\">".$v1[0]."</option>\n";	
			}
		}
		
		echo "						</select>\n";
		echo "					</td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"right\"><b>Send Allowance</b></td>\n";
		echo "                  <td colspan=\"2\" align=\"left\"><input type=\"text\" id=\"SendAllowance\" name=\"sendallowance\" value=\"".trim($row0['sendallowance'])."\" size=\"5\" maxlength=\"4\"></td>\n";
		echo "              </tr>\n";
		//echo "              <tr>\n";
		//echo "                  <td align=\"right\"><b>Attachment</b></td>\n";
		//echo "                  <td colspan=\"2\" align=\"left\"><input type=\"text\" id=\"FileAttach\" name=\"fileattach\" value=\"".trim($row0['fileattach'])."\" size=\"25\" maxlength=\"32\"></td>\n";
		//echo "              </tr>\n";
        echo "              <tr>\n";
        echo "                  <td align=\"right\"><b>Allow File Attachment</b></td>\n";
        echo "                  <td colspan=\"2\" align=\"left\">\n";
        echo "                      <select id=\"UserFileAttach\" name=\"allowattach\">\n";
        
        if (isset($row0['allowattach']) and $row0['allowattach']==1) {
            echo "<option value=\"0\">No</option><option value=\"1\" SELECTED>Yes</option>\n";
        }
        else {
            echo "<option value=\"0\" SELECTED>No</option><option value=\"1\">Yes</option>\n";
        }
        
        echo "                      </select>\n";
        echo "                  </td>\n";
        echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td colspan=\"2\" align=\"center\"><div id=\"errtext\"></div></td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td colspan=\"2\" align=\"right\"><input class=\"transnb\" type=\"image\" src=\"images/save.gif\" title=\"Save Template\"></td>\n";
		echo "              </tr>\n";
		echo "          </table>\n";
		echo "          </form>\n";
		echo "      </td>\n";
		echo "      <td width=\"25%\" align=\"center\" valign=\"top\">\n";
		echo "          <table class=\"outer\" width=\"100%\">\n";
		echo "              <tr>\n";
		echo "                  <td colspan=\"2\" align=\"left\"><b>Created</b></td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"left\"><table width=\"100%\"><tr><td align=\"right\">".date('m-d-Y',strtotime($row0['adate']))."</td><td>".date('g:i a',strtotime($row0['adate']))."</td></tr></table></td>\n";
		echo "                  <td align=\"left\">".$row0['aidlname'].", ".$row0['aidfname']."</td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td colspan=\"2\" align=\"left\"><b>Last Update</b></td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"left\"><table width=\"100%\"><tr><td align=\"right\">".date('m-d-Y',strtotime($row0['udate']))."</td><td>".date('g:i a',strtotime($row0['udate']))."</td></tr></table></td>\n";
		echo "                  <td align=\"left\">".$row0['uidlname'].", ".$row0['uidfname']."</td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td colspan=\"2\" align=\"center\"><hr width=\"75%\"></td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"left\"><b>Email Keywords<b></td>\n";
		echo "      			<td align=\"right\">\n";

		HelpNode('emailtempkeyw',2);
		
		echo "					</td>\n";
		echo "              <tr>\n";
		echo "                  <td colspan=\"2\" align=\"center\">\n";
		echo "                      <table>";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"CUSTOMERFULLNAME\" onClick=\"CopytoClipBoard('CUSTOMERFULLNAME','holdtext');\"><img src=\"images/page_white_add.png\"> CUSTOMERFULLNAME</span></td>\n";
		echo "                              <td align=\"center\"><textarea id=\"holdtext\" style=\"display:none;\"></textarea></td>\n";
		echo "                          </tr>\n";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"CUSTOMERFIRSTNAME\" onClick=\"CopytoClipBoard('CUSTOMERFIRSTNAME','holdtext');\"><img src=\"images/page_white_add.png\"> <span class=\"tdraggable\">CUSTOMERFIRSTNAME</span></span></td>\n";
		echo "                              <td align=\"center\"></td>\n";
		echo "                          </tr>\n";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"CUSTOMERLASTNAME\" onClick=\"CopytoClipBoard('CUSTOMERLASTNAME','holdtext');\"><img src=\"images/page_white_add.png\"> CUSTOMERLASTNAME</span></td>\n";
		echo "                              <td align=\"center\"></td>\n";
		echo "                          </tr>\n";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"CUSTOMEREMAILADDRESS\" onClick=\"CopytoClipBoard('CUSTOMEREMAILADDRESS','holdtext');\"><img src=\"images/page_white_add.png\"> CUSTOMEREMAILADDRESS</span></td>\n";
		echo "                              <td align=\"center\"></td>\n";
		echo "                          </tr>\n";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"APPOINTMENTDATETIME\" onClick=\"CopytoClipBoard('APPOINTMENTDATETIME','holdtext');\"><img src=\"images/page_white_add.png\"> APPOINTMENTDATETIME</span></td>\n";
		echo "                              <td align=\"center\"></td>\n";
		echo "                          </tr>\n";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"GMFULLNAME\" onClick=\"CopytoClipBoard('GMFULLNAME','holdtext');\"><img src=\"images/page_white_add.png\"> GMFULLNAME</span></td>\n";
		echo "                              <td align=\"center\"></td>\n";
		echo "                          </tr>\n";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"OFFICEPHONENUMBER\" onClick=\"CopytoClipBoard('OFFICEPHONENUMBER','holdtext');\"><img src=\"images/page_white_add.png\"> OFFICEPHONENUMBER</span></td>\n";
		echo "                              <td align=\"center\"></td>\n";
		echo "                          </tr>\n";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"SALESREPFULLNAME\" onClick=\"CopytoClipBoard('SALESREPFULLNAME','holdtext');\"><img src=\"images/page_white_add.png\"> SALESREPFULLNAME</span></td>\n";
		echo "                              <td align=\"center\"></td>\n";
		echo "                          </tr>\n";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"SALESREPPHONENUMBER\" onClick=\"CopytoClipBoard('SALESREPPHONENUMBER','holdtext');\"><img src=\"images/page_white_add.png\"> SALESREPPHONENUMBER</span></td>\n";
		echo "                              <td align=\"center\"></td>\n";
		echo "                              <td></td>\n";
		echo "                          </tr>\n";
		echo "                          <tr>\n";
		echo "                              <td align=\"left\"><span id=\"CORPORATEFULLNAME\" onClick=\"CopytoClipBoard('CORPORATEFULLNAME','holdtext');\"><img src=\"images/page_white_add.png\"> CORPORATEFULLNAME</span></td>\n";
		echo "                              <td align=\"center\"></td>\n";
		echo "                          </tr>\n";
        echo "          </table>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
    }
}

function RemoveLink()
{
    $qry0	 = "update leadstatuscodes set ";
    $qry0	.= " etid=0 ";
    $qry0	.= "where statusid=".$_REQUEST['statusid'].";";
    $res0	 = mssql_query($qry0);
    
    $qry1	 = "update EmailTemplate set ";
    $qry1	.= " udate=getdate(),uid=".$_SESSION['securityid']." ";
    $qry1	.= "where etid=".$_REQUEST['etid'].";";
    $res1	 = mssql_query($qry1);
}

function CreateLink()
{
    $qry0	 = "update leadstatuscodes set ";
    $qry0	.= "  etid=".$_REQUEST['etid']." ";
    $qry0	.= "where statusid=".$_REQUEST['statusid'].";";
    $res0	 = mssql_query($qry0);
    
    $qry1	 = "update EmailTemplate set ";
    $qry1	.= " udate=getdate(),uid=".$_SESSION['securityid']." ";
    $qry1	.= "where etid=".$_REQUEST['etid'].";";
    $res1	 = mssql_query($qry1);
}

function EditTemplate()
{
    $qry0	 = "update EmailTemplate set ";
    $qry0	.= "    name='".replacequote($_REQUEST['name'])."',";
    $qry0	.= "    esubject='".replacequote(trim($_REQUEST['esubject']))."',";
    $qry0	.= "    ebody='".replacequote(trim($_REQUEST['ebody']))."',";
    $qry0	.= "    senddelay='".$_REQUEST['senddelay']."',";
    $qry0	.= "    sendallowance='".$_REQUEST['sendallowance']."',";
	$qry0	.= "    epid='".$_REQUEST['epid']."',";
	$qry0	.= "    ttype='".$_REQUEST['ttype']."',";
	$qry0	.= "    fileattach='".replacequote(trim($_REQUEST['fileattach']))."',";
    $qry0	.= "    sendappt='".$_REQUEST['sendappt']."',";
    $qry0	.= "    sendcallb='".$_REQUEST['sendcallb']."',";
    $qry0	.= "    allowattach='".$_REQUEST['allowattach']."',";
    $qry0	.= "    udate=getdate(),";
    $qry0	.= "    active=".$_REQUEST['active'].",";
    $qry0	.= "    uid=".$_SESSION['securityid']." ";
    $qry0	.= "where etid=".$_REQUEST['etid'].";";
    $res0	 = mssql_query($qry0);
}

function SaveTemplate() {
    //DisplayArray($_REQUEST);
    
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    
    $qry	 = "select etid from EmailTemplate where guid='".$_REQUEST['guid']."';";
    $res	 = mssql_query($qry);
    $nrow    = mssql_num_rows($res);
    
    if ($nrow == 0)
    {
        $qry0	 = "INSERT INTO EmailTemplate (aid,uid,name,esubject,ebody,senddelay,sendallowance,fileattach,allowattach,epid,guid,ttype";
        
        if (isset($_REQUEST['sendappt']) && $_REQUEST['sendappt']==1)
        {
            $qry0	.= ",sendappt ";
        }
        
        if (isset($_REQUEST['sendcallb']) && $_REQUEST['sendcallb']==1)
        {
            $qry0	.= ",sendcallb ";
        }
        
        $qry0	.= ") VALUES (";
        $qry0	.= "".$_SESSION['securityid'].",";
        $qry0	.= "".$_SESSION['securityid'].",";
        $qry0	.= "'".replacequote(trim($_REQUEST['name']))."',";
        $qry0	.= "'".replacequote(trim($_REQUEST['esubject']))."',";
        $qry0	.= "'".replacequote(trim($_REQUEST['ebody']))."',";
        $qry0	.= "0,";
		$qry0	.= "'".$_REQUEST['sendallowance']."',";
		$qry0	.= "'".replacequote(trim($_REQUEST['fileattach']))."',";
        $qry0	.= "'".$_REQUEST['allowattach']."',";
		$qry0	.= "'".$_REQUEST['epid']."',";
        $qry0	.= "'".$_REQUEST['guid']."',";
		$qry0	.= "'".$_REQUEST['ttype']."'";
        
        if (isset($_REQUEST['sendappt']) && $_REQUEST['sendappt']==1)
        {
            $qry0	.= ",".$_REQUEST['sendappt']."";
        }
        
        if (isset($_REQUEST['sendcallb']) && $_REQUEST['sendcallb']==1)
        {
            $qry0	.= ",".$_REQUEST['sendcallb']."";
        }
        
        $qry0	.= ")";
        $res0	 = mssql_query($qry0);
        
        //echo $qry0.'<br>';
    }
    else
    {
        echo 'This Template has already been saved<br>';
        //echo $_REQUEST['guid'];
    }
}

?>