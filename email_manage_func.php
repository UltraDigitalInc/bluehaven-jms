<?php

/*
if ($_SESSION['securityid']==26) {
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
}
*/

function BaseMatrix()
{
    DefaultLoader();
    
    if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='listEmailTemplates') {
        ListTemplates();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='viewEmailTemplate') {
        ViewTemplate();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='addEmailTemplate') {
        AddTemplate();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='editEmailTemplate') {
        EditTemplate();
        ViewTemplate();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='saveEmailTemplate') {
        SaveTemplate();
        ListTemplates();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='listReceivedEmail') {
        ListReceivedEmail(0);
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='deleteReceivedEmail') {
        deleteReceivedEmail(0);
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='removelink') {
        RemoveLink();
        ViewTemplate();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='createlink') {
        CreateLink();
        ViewTemplate();
    }
	elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='listKillFile') {
        listKillFile();
    }
}

function DefaultLoader() {
    echo "<script type=\"text/javascript\" src=\"js/jquery_emailloader_func.js\"></script>\n";
	echo '<input type="hidden" id="active_office" value="'.$_SESSION['officeid'].'">';
    echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
    echo "<table width=\"950px\">\n";
    echo "  <tr>\n";
    echo "      <td><b>Email Functions</b></td>\n";
    echo "      <td align=\"right\">\n";
    echo "          <table>\n";
    echo "   			<tr>\n";
	
	if (isset($_SESSION['emailtemplates']) and $_SESSION['emailtemplates'] >= 9) {
		echo "      			<td>\n";
		echo "         				<form method=\"post\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "							<input type=\"hidden\" name=\"call\" value=\"email\">\n";
		echo "							<input type=\"hidden\" name=\"subq\" value=\"listKillFile\">\n";
		echo "                          <button class=\"btnsysmenu\" id=\"listKillFile\">Email<br>Kill File</button>\n";
		echo "         				</form>\n";
		echo "                  </td>\n";
	}
	
	if (isset($_SESSION['emailtemplates']) and $_SESSION['emailtemplates'] >= 9) {
		echo "      			<td>\n";
		echo "         				<form method=\"post\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "							<input type=\"hidden\" name=\"call\" value=\"email\">\n";
		echo "							<input type=\"hidden\" name=\"subq\" value=\"listReceivedEmail\">\n";
		echo "                          <button class=\"btnsysmenu\" id=\"listReceivedEmail\">Email<br>Files</button>\n";
		echo "         				</form>\n";
		echo "                  </td>\n";
	}
	
	if (isset($_SESSION['emailtemplates']) and $_SESSION['emailtemplates'] >= 6) {
		echo "      			<td>\n";
		echo "         				<form method=\"post\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "							<input type=\"hidden\" name=\"call\" value=\"email\">\n";
		echo "							<input type=\"hidden\" name=\"subq\" value=\"listEmailTemplates\">\n";
		echo "                          <button class=\"btnsysmenu\" id=\"listEmailTemplates\">Email Templates</button>\n";
		echo "         				</form>\n";
		echo "                  </td>\n";
	}
	
    echo "         		</tr>\n";
    echo "          </table>\n";
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "</div>\n";
}

function addKillFile() {
}

function listKillFile() {
	$qry1 = "SELECT officeid FROM security WHERE securityid=".(int) $_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	if ($row1['officeid']==89 and $_SESSION['emailtemplates'] >= 9) {
		$oid=0;
	}
	else {
		$oid=(isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)?$_REQUEST['oid']:$_SESSION['officeid'];
	}
	
	$qry0 = "SELECT k.*,isnull((select name from offices where officeid=k.oid),'Global') as oname FROM EmailKillList AS k WHERE k.oid=".(int) $oid." ORDER BY k.oid,k.emailhost ASC,k.emailaddr ASC;";
    $res0 = mssql_query($qry0);
	
	$kf_ar=array();
    while ($row0 = mssql_fetch_array($res0))
    {
		$kf_ar[$row0['kfid']]=array('oid'=>$row0['oid'],'emailaddr'=>$row0['emailaddr'],'emailhost'=>$row0['emailhost'],'secid'=>$row0['secid'],'adate'=>$row0['adate'],'oname'=>$row0['oname']);
	}
	
	echo '<div class="outerrnd" style="width:650px">';
    echo '<table width="600px">';
	echo '<tr><td colspan="3" align="left"><b>Email Kill File</b></td></tr>';
    //echo '<tr><td></td><td align="center"><b>Email Address</b></td><td></td></tr>';
	echo '<tr><td align="right"><b>Email Address</b></td><td align="right"><input id="addemailaddr" name="emailaddr" size="25" placeholder="Username"></td><td align="center" width="25px">@</td><td align="left"><input id="addemailhost" name="emailhost" size="40" placeholder="Host"></form></td><td align="right" width="50px"><button id="addEmailKillEntry">Add</button></td></tr>';
	echo '</table>';
	echo '</div>';
	
	//print_r($kf_ar);
	
	if (count($kf_ar) > 0) {
		echo '<div class="outerrnd" style="width:650px">';
		echo '<table width="650px">';
		echo '<tr><td></td><td><b>Email (Sender)</b></td><td></td></tr>';
		
		$kfl=1;
		foreach ($kf_ar as $n=>$v) {
			$tbg=($kfl%2)?'even':'odd';
			echo '<tr class="'.$tbg.'"><td width="25px">'.$n.'</td><td width="200px" align="right">'.$v['emailaddr'].'</td><td align="center" width="25px">@</td><td align="left">'.$v['emailhost'].'</td><td width="60px">'.date('m/d/y',strtotime($v['adate'])).'</td><td align="right" width="50px"><button>Delete</button></td></tr>';
			$kfl++;
		}
		
		echo '</table>';
		echo '</div>';
	}
}

function ListReceivedEmail($oid=null) {
    if (!is_null($oid)) {
        $fdir="F:\\FileStore\\".$oid."\\CustomerNotFoundEmailFiles";
        if (is_dir($fdir)) {
            $flist=scandir($fdir);
                
            echo "<div class=\"outerrnd\" style=\"width:400px\">\n";
            echo '<table width="400px">';
            echo '<tr><td></td><td><b>Filename (Sender)</b></td><td><b>Added</b></td><td align="center"><b>Open</b></td><td align="center"><b>Remove</b></td></tr>';
                
            if (count($flist) > 0) {                
                $lcnt=0;
                foreach ($flist as $nf=>$vf) {
                    $tbg=($lcnt%2)?'even':'odd';
                    if ($vf!='.' and $vf!='..') {
                        $lcnt++;
                        $cadd=filectime($fdir.'\\'.$vf);
                        echo '<tr class='.$tbg.'><td>'.$lcnt.'.</td><td>'.$vf.'</td><td>'.date('m/d/y',$cadd).'</td>';
                        echo '<td align="center">';
                        
                        echo "					<form action=\"http://jms.bhnmi.com/export/fileout.php\" target=\"_new\" method=\"post\">\n";
						echo "					<input type=\"hidden\" name=\"storetype\" value=\"file_ex\">\n";
                        echo "					<input type=\"hidden\" name=\"oid\" value=\"".$oid."\">\n";
						echo "					<input type=\"hidden\" name=\"filename\" value=\"".$vf."\">\n";
						echo "					<input class=\"transnb\" type=\"image\" src=\"images/download.gif\" title=\"Download File\">\n";
						echo "					</form>\n";
                        
                        echo '</td>';
                        echo '<td align="center">';
                        
                        echo "					<form class=\"deleteEmailFile\" method=\"post\">\n";
						echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
                        echo "						<input type=\"hidden\" name=\"call\" value=\"email\">\n";
                        echo "						<input type=\"hidden\" name=\"subq\" value=\"deleteReceivedEmail\">\n";
						echo "					    <input type=\"hidden\" name=\"filename\" class=\"REFile\" value=\"".$vf."\">\n";
						echo "					    <input class=\"transnb\" type=\"image\" src=\"images/action_delete.gif\" title=\"Delete File\">\n";
						echo "					</form>\n";
                        echo '</td>';
                        echo '</tr>';
                    }
                }
            }
            else {
                echo '<tr><td colspan="4">No Files Found</td>';
            }
            
            echo '</table>';
            echo "</div>\n";
            
        }
        else {
            echo 'Directory not Found';
        }
    }
    else {
        echo 'Missing Office designation';
    }
}

function deleteReceivedEmail($oid=null) {
    $ff=FILESTORE."\\".$oid."\\CustomerNotFoundEmailFiles\\".$_REQUEST['filename'];
	$fe=(file_exists($ff))?true:false;
    
    if ($fe) {
        unlink($ff);
    }
    
    ListReceivedEmail(0);
}

function ListTemplates() {
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    
    $emtemp_ar=array();
	
	$qry1 = "SELECT officeid FROM security WHERE securityid=".(int) $_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry0 = "
			select
				E.*,
				(select name from offices where officeid=E.oid) as oname,
				(select lname from security where securityid=E.aid) as aidlname,
				(select lname from security where securityid=E.uid) as uidlname,
				(select count(etid) from leadstatuscodes where etid=E.etid) as lidcnt
			from
				EmailTemplate as E
			where ";
			
	if ($row1['officeid']==89 and $_SESSION['emailtemplates']==9) {
		$qry0 .= " E.oid=0 or ";
	}
	
	$qry0 .= " oid=".$_SESSION['officeid']." order by E.oid,E.active desc,E.name;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	//echo $qry0.'<br>';
    
    while ($row0 = mssql_fetch_array($res0))
    {
			$emtemp_ar[]=array(
				'etid'=>$row0['etid'],
				'oid'=>$row0['oid'],
				'oname'=>$row0['oname'],
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
    
    echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
    echo "<table width=\"950px\">\n";
    echo "  <tr>\n";
    echo "      <td align=\"left\"><b>Templates</b></td>\n";
    echo "      <td align=\"right\">\n";
    echo "          <table>\n";
    echo "   			<tr>\n";
    echo "      			<td align=\"center\" width=\"20\">\n";
    
    //HelpNode('emailtemplist',1);

    echo "					</td>\n";
    echo "      			<td align=\"center\">\n";
    echo "                      <form method=\"post\">\n";
    echo "						    <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
    echo "						    <input type=\"hidden\" name=\"call\" value=\"email\">\n";
    echo "						    <input type=\"hidden\" name=\"subq\" value=\"addEmailTemplate\">\n";
    echo "                          <button class=\"btnsysmenu\">Add</button>\n";
    echo "                      </form>\n";
    echo "				</td>\n";
    echo "         		</tr>\n";
    echo "          </table>\n";
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "      <td colspan=\"2\">\n";
    
    if ($nrow0 > 0)
    {
        echo "          	<table width=\"100%\" align=\"right\">\n";
        echo "   				<tr class=\"tblhd\">\n";
        echo "      				<td><img src=\"images/pixel.gif\"></td>\n";
        echo "      				<td align=\"left\"><b>Name</b></td>\n";
		echo "      				<td align=\"left\"><b>Office</b></td>\n";
		echo "		      			<td align=\"center\"><b>Security</b></td>\n";
        echo "		      			<td align=\"center\"><b>Added</b></td>\n";
        echo "		      			<td align=\"left\"><b>by</b></td>\n";
        echo "		      			<td align=\"center\"><b>Updated</b></td>\n";
        echo "		      			<td align=\"left\"><b>by</b></td>\n";
		echo "		      			<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
        echo "		      			<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "					</tr>\n";
    
        $srcnt=1;
        foreach ($emtemp_ar as $n1 => $v1) {
			$oname=(isset($v1['oid']) and $v1['oid']!=0)?$v1['oname']:'Provided (General)';
			
            if ($v1['active']==0) {
                $tbg='ltred';
            }
            else {
                if ($srcnt%2) {
                    $tbg='even';
                }
                else {
                    $tbg='odd';
                }
            }
            
            echo "   			<tr class=\"".$tbg."\">\n";
            echo "      			<td align=\"right\">".$srcnt++.".</td>\n";
            echo "      			<td align=\"left\">".$v1['name']."</td>\n";
			echo "      			<td align=\"left\">".$oname."</td>\n";
            echo "      			<td align=\"center\">".$v1['active']."</td>\n";
            echo "      			<td align=\"center\">".date('m-d-Y',strtotime($v1['adate']))."</td>\n";
            echo "      			<td align=\"left\">".$v1['aidlname']."</td>\n";
            echo "      			<td align=\"center\">".date('m-d-Y',strtotime($v1['udate']))."</td>\n";
            echo "      			<td align=\"left\">".$v1['uidlname']."</td>\n";
            echo "      			<td align=\"right\"></td>\n";
            echo "      			<td align=\"right\">\n";
            echo "                      <form name=\"viewemailtemplate\" method=\"post\">\n";
            echo "						<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
            echo "						<input type=\"hidden\" name=\"call\" value=\"email\">\n";
            echo "						<input type=\"hidden\" name=\"subq\" value=\"viewEmailTemplate\">\n";
            echo "						<input type=\"hidden\" id=\"etid\" name=\"etid\" value=\"".$v1['etid']."\">\n";
            echo "                      <input class=\"transnb\" type=\"image\" src=\"images/application_form_edit.png\" title=\"View Email Template\">\n";
            echo "                      </form>\n";
            echo "                  </td>\n";
            echo "   			</tr>\n";
        }
        
        echo "          </table>\n";
    }
    
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "</div>\n";
}

function AddTemplate() {
    ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$qry	= "select officeid,emailtemplateaccess from jest..security where securityid=".(int) $_SESSION['securityid'].";";
    $res	= mssql_query($qry);
	$row 	= mssql_fetch_array($res);
	
	if ($row['officeid']==89 and $row['emailtemplateaccess']>=9) {
		$seclev=9;
		$sndall=99;
	}
	else {
		$seclev=($row['emailtemplateaccess'] > 6)?6:$row['emailtemplateaccess'];
		$sndall=10;
	}

	$ttid	= array();
	$qry0	= "select * from jest..EmailTemplateTypes where name ='Leads' order by name asc;";
    $res0	= mssql_query($qry0);
	
	while ($row0 = mssql_fetch_array($res0))
    {
		$ttid[$row0['ettid']]=array($row0['name']);
	}
	
	$qry1	 = "select * from jest..EmailProfile where seclev <= ".$seclev." order by elogin asc;";
    $res1	 = mssql_query($qry1);
	
	while ($row1 = mssql_fetch_array($res1))
    {
		$epid[$row1['pid']]=array($row1['elogin']);
	}

    $guid  =md5(session_id().time().$_SESSION['securityid']);
	
	$emkwds	=array('CUSTOMERFULLNAME','CUSTOMERFIRSTNAME','CUSTOMERLASTNAME','CUSTOMEREMAILADDRESS','APPOINTMENTDATETIME','GMFULLNAME','OFFICEPHONENUMBER','SALESREPFULLNAME','SALESREPPHONENUMBER','CORPORATEFULLNAME','BLANKMESSAGEENTRY');
	
    echo "<script type=\"text/javascript\" src=\"js/jquery_emailtemplate_func.js\"></script>\n";
    echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
    echo "<table width=\"950px\">\n";
    echo "  <tr>\n";
    echo "      <td colspan=\"2\">\n";
    echo "          <table width=\"100%\">\n";
    echo "              <tr>\n";
    echo "              	<td align=\"left\"><b>Email Template Creator</b></td>\n";
    echo "      		    <td align=\"center\" width=\"20\">\n";
	
    HelpNode('emailtempadd',1);

    echo "			        </td>\n";
    echo "      			<td align=\"center\" width=\"20\">\n";
    /*
    echo "         		        <form id=\"listtemplates\" method=\"post\">\n";
    echo "						    <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
    echo "						    <input type=\"hidden\" name=\"call\" value=\"emailtemplate\">\n";
    echo "						    <input class=\"transnb\" type=\"image\" src=\"images/application_view_list.png\" title=\"Return to Template List\">\n";
    echo "         		        </form>\n";
    */
    echo "				    </td>\n";
    echo "              </tr>\n";
    echo "          </table>\n";
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "      <td>\n";
    echo "          <form id=\"addemailtemplate\" name=\"addemailtemplate\" method=\"post\">\n";
    echo "          <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
    echo "          <input type=\"hidden\" name=\"call\" value=\"email\">\n";
    echo "          <input type=\"hidden\" name=\"subq\" value=\"saveEmailTemplate\">\n";
    echo "          <input type=\"hidden\" name=\"guid\" value=\"".$guid."\">\n";
    echo "          <input type=\"hidden\" id=\"FileAttach\" name=\"fileattach\">\n";
    echo "          <table width=\"100%\">\n";
	echo "              <tr>\n";
    echo "                  <td align=\"right\" valign=\"top\"><b>Office</b></td>\n";
    echo "                  <td align=\"left\">\n";
	echo "                  	<select id=\"templateoid\" name=\"oid\">\n";
	
	if ($row['officeid']==89 and $seclev >= 9) {
		echo "                  		<option value=\"0\" SELECTED>Provided (General)</option>\n";
	}
	
	echo "                  		<option value=\"".$_SESSION['officeid']."\">".$_SESSION['offname']."</option>\n";	
	echo "                  	</select>\n";
	echo "					</td>\n";
    echo "      	    	<td align=\"right\"></td>\n";
    echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td align=\"right\" valign=\"top\"><b>Name</b></td>\n";
    echo "                  <td align=\"left\"><input type=\"text\" id=\"TemplateName\" name=\"name\" size=\"30\" maxlength=\"32\"></td>\n";
    echo "      	    	<td align=\"right\"></td>\n";
    echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td align=\"right\" valign=\"top\"><b>Email Subject</b></td>\n";
    echo "                  <td colspan=\"2\" align=\"left\"><input type=\"text\" id=\"EmailSubject\" name=\"esubject\" size=\"100\" maxlength=\"64\"></td>\n";
    echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td align=\"right\" valign=\"top\"><b>Email Body</b></td>\n";
    echo "                  <td colspan=\"2\" align=\"left\">\n";
    echo "                      <textarea cols=\"100\" rows=\"25\" id=\"EmailBody\" name=\"ebody\" class=\"tinymce\"></textarea>\n";
    echo "                  </td>\n";
    echo "              </tr>\n";
	echo "              <tr>\n";
    echo "                  <td align=\"right\"><b>HTML</b></td>\n";
    echo "                  <td colspan=\"2\" align=\"left\">\n";
	echo "						<select name=\"ishtml\" id=\"isHTML\">\n";
	echo "							<option value=\"0\" SELECTED>No</option>\n";
	echo "							<option value=\"1\">Yes</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
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
    echo "                  <td colspan=\"2\" align=\"left\">\n";
	//echo "						<input type=\"text\" id=\"SendAllowance\" name=\"sendallowance\" value=\"0\" size=\"5\" maxlength=\"4\">\n";
	echo "						<select id=\"SendAllowance\" name=\"sendallowance\">\n";
	
	for ($x=0;$x <= $sndall;$x++) {
		if ($x==1) {
			echo "<option value=\"".$x."\" SELECTED>".$x."</option>\n";
		}
		else {
			echo "<option value=\"".$x."\">".$x."</option>\n";
		}
	}
	
	echo "						</select>\n";
	echo "					</td>\n";
    echo "              </tr>\n";
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
    echo "                  <td colspan=\"3\" align=\"right\"><button>Save Template</button></td>\n";
    echo "              </tr>\n";
    echo "          </table>\n";
    echo "          </form>\n";
    echo "      </td>\n";
    echo "      <td valign=\"top\">\n";
    echo "          <table width=\"100%\">\n";
    echo "              <tr>\n";
    echo "                  <td align=\"left\"><b>Keyword List<b></td>\n";
	echo "              </tr>\n";
    echo "              <tr>\n";
    echo "                  <td align=\"left\">\n";
	
	foreach ($emkwds as $ev) {
		echo "                      <button class=\"emailkeywords\">".$ev."</button><br/>\n";
	}

    echo "                  </td>\n";
	echo "              <tr>\n";
    echo "			</table>\n";
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "</div>\n";
}

function ViewTemplate() {
    ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$qry = "SELECT officeid,emailtemplateaccess FROM security WHERE securityid=".(int) $_SESSION['securityid'].";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	if ($row['officeid']==89 and $row['emailtemplateaccess'] >=9) {
		$seclev=9;
		$sndall=99;
	}
	else {
		$seclev=($row['emailtemplateaccess'] > 6)?6:$row['emailtemplateaccess'];
		$sndall=10;
	}
	
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
		
		$qry4	 = "select * from jest..EmailProfile where seclev <= ".$seclev." order by elogin asc;";
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
	
		$act_sel_ar=array(0=>'Inactive',1=>'Sales Representative',2=>'',3=>'',4=>'',5=>'Sales Manager',6=>'General Manager/Lead Admin',7=>'',8=>'',9=>'Admin');
		$emkwds	=array('CUSTOMERFULLNAME','CUSTOMERFIRSTNAME','CUSTOMERLASTNAME','CUSTOMEREMAILADDRESS','APPOINTMENTDATETIME','GMFULLNAME','OFFICEPHONENUMBER','SALESREPFULLNAME','SALESREPPHONENUMBER','CORPORATEFULLNAME','BLANKMESSAGEENTRY');
		
		if ($row0['ishtml']==1) {
		?>
		<script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
		<script type="text/javascript">
		tinyMCE.init({
				// General options
				mode : "textareas",
				theme : "advanced",
				plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		
				// Theme options
				theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : false,
		
				// Skin options
				skin : "o2k7",
				skin_variant : "silver",
		});
		</script>		
		<?php
		}
		
        echo "<script type=\"text/javascript\" src=\"js/jquery_emailtemplate_func.js\"></script>\n";
        echo "<div class=\"outerrnd\" style=\"width:950px\">\n";
		echo "<table width=\"950px\">\n";
		echo "  <tr>\n";
		echo "      <td colspan=\"2\">\n";
		echo "          <table width=\"100%\">\n";
		echo "              <tr>\n";
		echo "                  <td><b>Email Template Editor</b></td>\n";
		echo "      			<td align=\"center\" width=\"20\">\n";
		echo "					</td>\n";
		echo "      			<td align=\"right\" width=\"20px\">\n";
		
		HelpNode('emailtempview',1);
	
		echo "					</td>\n";
		echo "              </tr>\n";
		echo "          </table>\n";
		echo "      </td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "      <td width=\"750px\">\n";
		echo "          <form id=\"editemailtemplate\" name=\"editemailtemplate\" method=\"post\">\n";
		echo "          <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "          <input type=\"hidden\" name=\"call\" value=\"email\">\n";
		echo "          <input type=\"hidden\" name=\"subq\" value=\"editEmailTemplate\">\n";
		echo "          <input type=\"hidden\" name=\"etid\" value=\"".$row0['etid']."\">\n";
        echo "          <input type=\"hidden\" id=\"FileAttach\" name=\"fileattach\" value=\"".trim($row0['fileattach'])."\">\n";
		echo "          <table width=\"100%\">\n";
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
	
		for ($x=0;$x <= $seclev;$x++)
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
		echo "                      <textarea class=\"tdroppable\" cols=\"110\" rows=\"35\" id=\"EmailBody\" name=\"ebody\" class=\"tinymce\">".trim($row0['ebody'])."</textarea>\n";
		echo "                  </td>\n";
		echo "              </tr>\n";
		echo "              <tr>\n";
		echo "                  <td align=\"right\"><b>HTML</b></td>\n";
		echo "                  <td colspan=\"2\" align=\"left\">\n";
		echo "						<select name=\"ishtml\" id=\"isHTML\">\n";
		
		if ($row0['ishtml']==1) {
			echo "						<option value=\"0\">No</option>\n";
			echo "						<option value=\"1\" SELECTED>Yes</option>\n";
		}
		else {
			echo "						<option value=\"0\" SELECTED>No</option>\n";
			echo "						<option value=\"1\">Yes</option>\n";
		}
		
		echo "						</select>\n";
		echo "					</td>\n";
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
		echo "                  <td colspan=\"2\" align=\"left\">\n";
		//echo "					<input type=\"text\" id=\"SendAllowance\" name=\"sendallowance\" value=\"".trim($row0['sendallowance'])."\" size=\"5\" maxlength=\"4\">\n";
		echo "						<select id=\"SendAllowance\" name=\"sendallowance\">\n";
		for ($x=0;$x <= $sndall;$x++) {
			if ($row0['sendallowance']==$x) {
				echo "<option value=\"".$x."\" SELECTED>".$x."</option>\n";
			}
			else {
				echo "<option value=\"".$x."\">".$x."</option>\n";
			}
		}
		
		echo "						</select>\n";
		echo "					</td>\n";
		echo "              </tr>\n";
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
		echo "                  <td colspan=\"2\" align=\"right\"><button>Save Template</button></td>\n";
		echo "              </tr>\n";
		echo "          </table>\n";
		echo "          </form>\n";
		echo "      </td>\n";
		echo "      <td width=\"25%\" align=\"center\" valign=\"top\">\n";
		echo "          <table width=\"100%\">\n";
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
		
		foreach ($emkwds as $ev) {
			echo "                      <button class=\"emailkeywords\">".$ev."</button><br/>\n";
		}
		
		echo "              	</td>\n";
		echo "              </tr>\n";
		echo "      	</table>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
        echo "</div>\n";
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
	$qry0	.= "    ishtml=".$_REQUEST['ishtml'].",";
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
        $qry0	 = "INSERT INTO EmailTemplate (oid,aid,uid,name,esubject,ebody,senddelay,sendallowance,fileattach,allowattach,epid,guid,ttype";
        
        if (isset($_REQUEST['sendappt']) && $_REQUEST['sendappt']==1)
        {
            $qry0	.= ",sendappt ";
        }
        
        if (isset($_REQUEST['sendcallb']) && $_REQUEST['sendcallb']==1)
        {
            $qry0	.= ",sendcallb ";
        }
        
        $qry0	.= ") VALUES (";
		$qry0	.= "'".$_REQUEST['oid']."',";
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