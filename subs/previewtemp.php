<?php

    session_start();
    
    error_reporting(E_ALL);
    ini_set('display_errors','On');

    if (isset($_SESSION['securityid']))
    {
        include ('..\connect_db.php');
        include ('..\common_func.php');
        include ('..\doc_func.php');
    
        function showmenu()
        {
            unset($_SESSION['et_uid']);
            $qryA = "SELECT * FROM jest..EmailTemplate WHERE etid = ".$_REQUEST['etid'].";";
            $resA = mssql_query($qryA);
            $rowA = mssql_fetch_array($resA);
            $nrowA = mssql_num_rows($resA);
            
            $qryB = "SELECT * FROM jest..EmailTracking WHERE tid = ".$_REQUEST['etid']." AND cid = ".$_REQUEST['cid'].";";
            $resB = mssql_query($qryB);
            $rowB = mssql_fetch_array($resB);
            $nrowB = mssql_num_rows($resB);
            
            echo "<table align=\"right\">\n";
            echo "  <tr>\n";
            
            if ($_SESSION['emailtemplates'] < 5 && ($rowA['active']==0 || $nrowB >= $rowA['sendallowance']))
            {
                echo "      <td valign=\"top\">Send Count (<span title=\"The total number of emails already sent\"><font color=\"red\">".$nrowB."</font></span> / <span title=\"The total number of emails allowed to send\"><font color=\"red\">".$rowA['sendallowance']."</font></span>)</td>\n";
            }
            else
            {
                echo "      <td valign=\"top\">Send Count (<span title=\"The total number of emails already sent\">".$nrowB."</span> / <span title=\"The total number of emails allowed to send\">".$rowA['sendallowance']."</span>)</td>\n";
                echo "      <td valign=\"top\" align=\"center\" width=\"20px\">\n";
                echo "          <form action=\"../index.php\" method=\"post\" target=\"JMSmain\" onSubmit=\"JavaScript: window.close()\">\n";
                echo "              <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
                echo "              <input type=\"hidden\" name=\"call\" value=\"sendetemp_fromPreview\">\n";
                echo "              <input type=\"hidden\" name=\"etid\" value=\"".$_REQUEST['etid']."\">\n";
                echo "              <input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
                echo "              <input type=\"hidden\" name=\"etcid[]\" value=\"".$_REQUEST['cid']."\">\n";
                echo "              <input type=\"hidden\" name=\"uid\" value=\"".md5($_REQUEST['cid'])."\">\n";
                echo "              <input type=\"hidden\" name=\"et_uid\" value=\"".md5($_REQUEST['cid'])."\">\n";
                echo "              <input type=\"hidden\" name=\"chistory\" value=\"1\">\n";
                echo "              <input class=\"transnb\" type=\"image\" src=\"../images/email_go.png\" alt=\"Send Email\">\n";
                echo "          </form>\n";
                echo "      </td>\n";
            }
            
            echo "      <td valign=\"top\" align=\"center\" width=\"20px\"><img src=\"../images/deletesm.gif\" onClick=\"window.close();\" title=\"Close\"></td>\n";
            echo "  </tr>\n";
            echo "</table>\n";
        }
        
        function showUserFileAttach() {
            echo "<div class=\"emailpreview\"><b>Select a File to send to the Recipient:</b><br>";
            echo "  <select>\n";
            echo "      <option value=\"0\">None</option>\n";            
            echo "      <option>Customer File 1</option>\n";
            echo "      <option>Customer File 2</option>\n";
            echo "      <option>Customer File 3</option>\n";
            echo "      <option>Customer File 4</option>\n";
            echo "  </select>\n";
            echo "</div>";            
        }
        
        $qryA = "SELECT * FROM jest..EmailTemplate WHERE etid = ".$_REQUEST['etid'].";";
        $resA = mssql_query($qryA);
        $rowA = mssql_fetch_array($resA);
        $nrowA = mssql_num_rows($resA);
        
        $qryAa = "SELECT * FROM jest..EmailProfile WHERE pid = ".$rowA['epid'].";";
        $resAa = mssql_query($qryAa);
        $rowAa = mssql_fetch_array($resAa);
        $nrowAa = mssql_num_rows($resAa);
        //$from=htmlspecialchars('"Blue Haven Pools & Spas" <bhcustomercare@bluehaven.com>');
        
        $from=htmlspecialchars('"' . $rowAa['ename'] . "\"" . " <" . $rowAa['elogin']. ">");
        
        if (isset($rowA['fileattach']) && strlen($rowA['fileattach']) > 2)
        {
            //$file=trim($rowA['fileattach']);
            $efile="<img src=\"../images/attach.png\" alt=\"".basename($rowA['fileattach'])."\">";
        }
        else
        {
            $efile='';
        }
        
        if (isset($_REQUEST['cid']) && $_REQUEST['cid']!=0) {
            $qryB = "SELECT cid,officeid,cfname,clname,cemail,securityid,apptmnt,appt_mo FROM jest..cinfo WHERE cid = ".$_REQUEST['cid'].";";
            $resB = mssql_query($qryB);
            $rowB = mssql_fetch_array($resB);
            
            $cfname=htmlspecialchars_decode($rowB['cfname']);
            $clname=htmlspecialchars_decode($rowB['clname']);
            $cemail=$rowB['cemail'];
            $apptmnt=date('l F jS Y',strtotime($rowB['apptmnt'])).' at '.date('h:i A',strtotime($rowB['apptmnt']));
            $cname=htmlspecialchars($cfname." ".$clname." <".$cemail.">");
        }
        else {
            $cfname='John';
            $clname='Customer';
            $cemail='bhcustomer@anywhere.com';
            $apptmnt='1/1/1970 12:00 AM';
            $cname=htmlspecialchars($cfname." ".$clname." <".$cemail.">");
        }
        
        if (isset($rowB['officeid']) && $rowB['officeid']!=0) {
            //echo 'From Office<br>';
            $qryC = "SELECT O.phone,O.gm,O.am,(select fname from jest..security where securityid=O.gm) as ogmfn,(select lname from jest..security where securityid=O.gm) as ogmln FROM jest..offices as O WHERE O.officeid = ".$rowB['officeid'].";";
        }
        else {
            //echo 'From Corporate<br>';
            $qryC = "SELECT O.phone,O.gm,O.am,(select fname from jest..security where securityid=O.gm) as ogmfn,(select lname from jest..security where securityid=O.gm) as ogmln FROM jest..offices as O WHERE O.officeid = ".$_SESSION['officeid'].";";
        }
        
        $resC = mssql_query($qryC);
        $rowC = mssql_fetch_array($resC);
            
        $ophone =trim($rowC['phone']);
        $ogmfull=trim($rowC['ogmfn']).' '.trim($rowC['ogmln']);
        
        //echo $ogmfull.'<br>';
        
        if (isset($rowB['securityid']) && $rowB['securityid']!=0)
        {
            $qryD = "SELECT fname,lname,phone,ext FROM jest..security WHERE securityid = ".$rowB['securityid'].";";
            $resD = mssql_query($qryD);
            $rowD = mssql_fetch_array($resD);
            
            $esender=$rowD['fname']." ".$rowD['lname'];
            
            if (isset($rowD['phone']) && (strlen(trim($rowD['phone'])) == 10 || strlen(trim($rowD['phone'])) == 12))
            {
                $ephone=trim($rowD['phone']) . " " . trim($rowD['ext']);
            }
            elseif (isset($rowC['phone']) && (strlen(trim($rowC['phone'])) == 10 || strlen(trim($rowC['phone'])) == 12))
            {
                $ephone=trim($rowC['phone']);
            }
            else
            {
                $ephone='';
            }
        }
        else
        {
            $esender='William T. SalesRep';
            $ephone='123-456-7890';
        }
        
        $corpname='Blue Haven Pools & Spas';
        
        $srch_ar=array(
                       0=>'/CUSTOMERFULLNAME/',
                       1=>'/CUSTOMERFIRSTNAME/',
                       2=>'/CUSTOMERLASTNAME/',
                       3=>'/CUSTOMEREMAILADDRESS/',
                       4=>'/OFFICEPHONENUMBER/',
                       5=>'/GMFULLNAME/',
                       6=>'/SALESREPFULLNAME/',
                       7=>'/CORPORATEFULLNAME/',
                       8=>'/APPOINTMENTDATETIME/',
                       9=>'/SALESREPPHONENUMBER/'
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
                       8=>$apptmnt,
                       9=>$ephone
                       );

        $esubj=preg_replace($srch_ar,$res_ar,trim($rowA['esubject']));
        $ebody=preg_replace($srch_ar,$res_ar,$rowA['ebody']);

        ?>
        
        <html>
            <head>
                <title>Email Preview</title>
                <link rel="stylesheet" type="text/css" href="../bh_main.css" media="screen">
            </head>
            <body style="background: #FFFFFF;">                
            <table>
                <tr>
                    <td>
                        <div class="emailpreview">
                            <table>
                                <tr><td align="right">From:</td><td><?php printf($from); ?></td></tr>
                                <tr><td align="right">To:</td><td><?php printf($cname); ?></td></tr>
                                <tr><td align="right">Subj:</td><td><?php printf($esubj . ' ' . $efile); ?></td></tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="emailpreview">
                        <?php echo nl2br($ebody); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        if (isset($rowA['allowattach']) and $rowA['allowattach']==1) {
                            showUserFileAttach();
                        }
                        ?>
                    </td>
                </tr>
            </table>
            </body>
        </html>
        <?php
    }
?>