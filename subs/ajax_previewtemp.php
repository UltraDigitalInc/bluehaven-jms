<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');

include ('..\connect_db.php');
include ('ajax_common_func.php');
//echo var_dump(getLogState($_SESSION['securityid']));

if (
    (isset($_SESSION['securityid']) and getLogState($_SESSION['securityid'])) and
    (isset($_REQUEST['etid']) and $_REQUEST['etid']!=0) and
    (isset($_REQUEST['sysCID']) and $_REQUEST['sysCID']>=0)
    )
{
    $cid    =(isset($_REQUEST['sysCID']) and $_REQUEST['sysCID']!=0)?$_REQUEST['sysCID']:0;
    $tid    =(isset($_REQUEST['etid']) and $_REQUEST['etid']!=0)?$_REQUEST['etid']:0;
    $bme    =(isset($_REQUEST['sbme']) and !empty($_REQUEST['sbme']))?trim($_REQUEST['sbme']):null;
    $efile  =(isset($_REQUEST['efile']) and !empty($_REQUEST['efile']))?trim($_REQUEST['efile']):null;
    $etuid  =(isset($_REQUEST['etuid']) and !empty($_REQUEST['etuid']))?$_REQUEST['etuid']:0;
    
    include ('..\common_func.php');
    include ('..\doc_func.php');
    
    if (isset($_REQUEST['call']) and isset($_REQUEST['call'])=='processEmailTemplate')
    {
        processEmailTemplate($cid,$tid,$bme,$efile);
    }
    else
    {
        displayEmailTemplateForm($cid,$tid);
    }
}
else
{
    echo 'Security Error ('.__LINE__.')';
}

function processEmailTemplate($cid,$tid,$bme,$docid)
{
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    $errors=false;
    $errtext='';
    $fileattach='';
    $filecopied=false;
    
	//Process Email Template
	if (isset($tid) and $tid != 0) {
        $chistory=true;
	
		if (!isset($_SESSION['et_uid'])) {
			if (isset($cid) and $cid != 0) {
				//echo 'IN2<br>';
				$qry = "SELECT * FROM jest..EmailTemplate WHERE etid=".(int) $tid.";";
				$res = mssql_query($qry);
				$row = mssql_fetch_array($res);
				$nrow= mssql_num_rows($res);
				
				$qry1 = "SELECT cid,officeid,cfname,clname,cemail,stage,apptmnt,callback,securityid,opt1,opt2,cid as c2id FROM jest..cinfo WHERE cid=".(int) $cid.";";				
				$res1 = mssql_query($qry1);
				$row1 = mssql_fetch_array($res1);
				$nrow1= mssql_num_rows($res1);
				
				$qry1a = "SELECT * FROM jest..EmailProfile WHERE pid=".$row['epid'].";";
				$res1a = mssql_query($qry1a);
				$row1a = mssql_fetch_array($res1a);
				$nrow1a= mssql_num_rows($res1a);
				
				$emcnt=1;
				
				if ($nrow1 > 0 && $nrow1a > 0) {					
					if (valid_email_addr(trim($row1['cemail']))) {		
						$qry2 = "SELECT esid,sdate FROM jest..EmailTracking WHERE cid=".$row1['c2id']." and tid=".(int) $tid." and active=1;";
						$res2 = mssql_query($qry2);
						$row2 = mssql_fetch_array($res2);
                        
                        if (isset($docid) and !is_null($docid) and $docid!=0) {
                            $qryF = "SELECT * FROM jest..jestFileStore WHERE cid=".(int) $row1['c2id']." and docid=".(int) $docid.";";
                            $resF = mssql_query($qryF);
                            $rowF = mssql_fetch_array($resF);
                            $nrowF= mssql_num_rows($resF);
                            
                            if ($nrowF!=0) {
                                $fsoid      = $rowF['filestore'];
                                $fsfile     = $rowF['fsfilename'];
                                $dirPath	= 'F:\\FileStore'.$fsoid.'\\';
                                $permPath   = $dirPath.$fsfile;
                                
                                if (file_exists($permPath)) {
                                    $filename=trim($rowF['filename']);
                                    $tmpPath=$dirPath.'\\tempFiles';
                                    $outFile=$tmpPath.'\\'.$filename;
                                    
                                    if (!is_dir($tmpPath)) {
                                        mkdir($tmpPath);
                                    }
                                    
                                    if (copy($permPath,$outFile)) {
                                        $fileattach=$outFile;
                                        $logdocid=$docid;
                                        $filecopied=true;
                                    }
                                }
                            }
                        }
                        else {
                            $logdocid=0;
                        }
						
						if ($_SESSION['emailtemplates'] >= 5) {
							$sendauth=true;
						}
						else {
							if (mssql_num_rows($res2) <= $row['sendallowance']) {
								$sendauth=true;
							}
							else {
								$sendauth=false;
							}
						}
						
						if ($nrow1 > 0 && $sendauth) {
							$erecp		=trim($row1['cemail']);
							$efile		=trim($fileattach);
							$efrom		=trim($row1a['elogin']);
							$ereply		=trim($row1a['ereply']);
							$epswd		=trim($row1a['epswd']);
							$ename		=trim($row1a['ename']);
							$ehost		=trim($row1a['ehost']);
							$eport		=$row1a['eport'];
							$SMTPdebug	=1;
							$corpname	='Blue Haven Pools & Spas';
							
							if (isset($row1['cid']) && $row1['cid']!=0)
							{						
								$cfname=$row1['cfname'];
								$clname=$row1['clname'];
								$cemail=$row1['cemail'];
								$apptmnt=date('l F jS Y',strtotime($row1['apptmnt'])).' at '.date('h:i A',strtotime($row1['apptmnt']));
								$cname=$cfname." ".$clname." <".$cemail.">";
							}
							else
							{
								$cfname='John';
								$clname='Customer';
								$cemail='customer@example.com';
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
								$qryD = "SELECT fname,lname,phone,ext FROM jest..security WHERE securityid = ".$row1['securityid'].";";
								$resD = mssql_query($qryD);
								$rowD = mssql_fetch_array($resD);
									
								$esender=$rowD['fname']." ".$rowD['lname'];
								
								if (isset($rowD['phone']) && (strlen(trim($rowD['phone'])) == 10 || strlen(trim($rowD['phone'])) == 12))
								{
									$ephone=trim($rowD['phone']) . " " . trim($rowD['ext']);
								}
								elseif (isset($rowC['phone']) && (strlen(trim($rowC['phone'])) == 10 || strlen(trim($rowC['phone'])) == 12))
								{
									$ephone=$ophone;
								}
								else
								{
									$ephone='';
								}
							}
							else
							{
								$esender='';
								$ephone='';
							}
			
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
									9=>'/SALESREPPHONENUMBER/',
                                    10=>'/BLANKMESSAGEENTRY/'
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
									9=>$ephone,
                                    10=>$bme
									);
					
							$esubj=preg_replace($srch_ar,$res_ar,trim($row['esubject']));
							$ebody=preg_replace($srch_ar,$res_ar,trim($row['ebody']));
							
                            //echo $bme;
                            
                            $ishtml=(isset($row['ishtml']) and $row['ishtml']==1)?true:false;
                            $isbme=(isset($row['bme']) and $row['bme']==1)?true:false;
                            
							$emc_ar=array(
										'to'=>		$erecp,
										'from'=>	$efrom,
										'efrom'=>	$efrom,
										'replyto'=>	$ereply,
										'fromname'=>$ename,
										'esubject'=>trim($esubj),
										'ebody'=>	trim($ebody),
										'oid'=> 	$row1['officeid'],
										'lid'=> 	$row1['stage'],
										'tid'=> 	$row['etid'],
										'cid'=> 	$row1['cid'],
										'uid'=> 	$_SESSION['securityid'],
										'appt'=> 	'',
										'callb'=> 	'',
										'ename'=>	$row['name'],
										'ehost'=>	$ehost,
										'epswd'=>	$epswd,
										'eport'=>	$eport,
										'efile'=>	$efile,
										'chistory'=>$chistory,
                                        'docid'=>   $logdocid,
										'SMTPdbg'=>	$SMTPdebug,
                                        'ishtml'=>  $ishtml,
                                        'isbme'=>   $isbme,
                                        'bme'=>     ($ishtml)?htmlspecialchars($bme):$bme
									);
							
							$mresult=ExtEmailSendSSL($emc_ar);
							
							if (!$mresult)
							{
								$errors=true;
								$errtext=$errtext.' Mail Server Send Error<br>';	
							}
                            else
                            {
                                if ($filecopied and is_file($outFile)) {
                                    unlink($outFile);
                                }
                                
                                ajaxEventProc(0);
                            }
						}
						else
						{
							$errors=true;
							$errtext=$errtext.' No Send Authority<br>';
						}
					}
					else
					{
						$errors=true;
						$errtext=$errtext.' Invalid Email: '.$erecp.'<br>';
					}
				}
				else
				{
					$errors=true;
					$errtext=$errtext.' CID not Found<br>';
				}
				
				//$_SESSION['et_uid']=$_REQUEST['et_uid'];
			}
			else
			{
				$errors=true;
				$errtext=$errtext.' No Assigned CID<br>';
			}
		}
		else
		{
			$errors=true;
			$errtext=$errtext.' This Email has already been sent!<br>';
		}
	}
	else
	{
		$errors=true;
		$errtext=$errtext.' Template Not Set<br>';
	}
    
    echo "<table align=\"center\">\n";
    
    if ($errors)
    {
        echo "	<tr>\n";
        echo "		<td><b>Send Email Failed for the following reason(s):</b></td>\n";
        echo "	</tr>\n";
        echo "	<tr>\n";
        echo "		<td>".$errtext."</td>\n";
        echo "	</tr>\n";
    }
    else
    {
        echo "	<tr>\n";
        echo "		<td align=\"center\"><h3><b>Email Sent!</b></h3></td>\n";
        echo "	</tr>\n";
    }
    
    echo "	<tr>\n";
    echo "		<td>\n";
    echo "          <button id=\"closeEmailSendDialog\">Close this Dialog</button>\n";
    echo "		</td>\n";
    echo "	<tr>\n";
    echo "</table>";
}

function showmenu($cid,$tid,$err)
{
    unset($_SESSION['et_uid']);
    $qryA = "SELECT * FROM jest..EmailTemplate WHERE etid = ".(int) $tid.";";
    $resA = mssql_query($qryA);
    $rowA = mssql_fetch_array($resA);
    $nrowA = mssql_num_rows($resA);
    
    $qryB = "SELECT * FROM jest..EmailTracking WHERE tid = ".(int) $tid." AND cid = ".(int) $cid.";";
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
        echo "          <input type=\"hidden\" name=\"etid\" id=\"tmpetid\" value=\"".$tid."\">\n";
        echo "          <input type=\"hidden\" name=\"et_uid\" id=\"tmpet_uid\" value=\"".md5($cid)."\">\n";
        
        //echo "          <button id=\"processEmailTemplate\" title=\"Send Email\"><img src=\"../images/email_go.png\"></button>\n";
        if (!$err) {
            echo "          <button id=\"processEmailTemplate\" title=\"Send Email\"><img src=\"../images/email_go.png\"></button>\n";
        }
        else {
            echo "          <button id=\"processEmailTemplate\" title=\"Send Email\"><img src=\"../images/email_go.png\"></button>\n";
        }
        
        echo "      </td>\n";
    }
    
    echo "  </tr>\n";
    echo "</table>\n";
}

function showUserFileAttach($cid) {
    $qryA = "SELECT * FROM jest..jestFileStore WHERE cid = ".(int) $cid.";";
    $resA = mssql_query($qryA);
    $nrowA = mssql_num_rows($resA);
    
    if ($nrowA > 0) {
        echo "<div class=\"emailpreview\"><b>Select a File to send to the Recipient:</b><br>";
        echo "  <select id=\"bmeEFile\">\n";
        echo "      <option value=\"0\">None</option>\n";
        
        while ($rowA = mssql_fetch_array($resA)) {
            echo "      <option value=\"".$rowA['docid']."\">".$rowA['filename']."</option>\n";
        }
        
        echo "  </select>\n";
        echo "</div>";
    }
    else {
        echo "<div class=\"emailpreview\">";
        echo "  <b>No files exist for this Lead/Customer</b>";
        echo "</div>";
    }
    
}

function displayEmailTemplateForm($cid,$tid) {
    $apptset=false;
    $qryA = "SELECT * FROM jest..EmailTemplate WHERE etid = ".(int) $tid.";";
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
    
    if (isset($cid) && $cid!=0)
    {
        $qryB = "SELECT cid,officeid,cfname,clname,cemail,securityid,apptmnt,appt_mo FROM jest..cinfo WHERE cid = ".(int) $cid.";";
        $resB = mssql_query($qryB);
        $rowB = mssql_fetch_array($resB);
        
        $cfname=htmlspecialchars_decode($rowB['cfname']);
        $clname=htmlspecialchars_decode($rowB['clname']);
        $cemail=$rowB['cemail'];
        $bappt=(isValidDate(date('m/d/Y',strtotime($rowB['apptmnt']))) and strtotime($rowB['apptmnt']) >= strtotime('1/1/2000'));
        //$apptmnt=date('l F jS Y',strtotime($rowB['apptmnt'])).' at '.date('h:i A',strtotime($rowB['apptmnt']));;
        //$cname=$cfname." ".$clname." ".$cemail."";
        $cname=htmlspecialchars($cfname." ".$clname." <".$cemail.">");
        
        if (isset($_SESSION['tester']) and $_SESSION['tester']==1)
        {
            if (isValidDate(date('m/d/Y',strtotime($rowB['apptmnt']))) and strtotime($rowB['apptmnt']) >= strtotime('1/1/2000')) {
                $apptmnt=date('l F jS Y',strtotime($rowB['apptmnt'])).' at '.date('h:i A',strtotime($rowB['apptmnt']));
                $apptset=true;

            }
            else {
                $apptmnt='<span style="color:red">Appointment not set, correct and try again.</span>';
                $apptset=false;
            }
        }
        else
        {
            if (isValidDate(date('m/d/Y',strtotime($rowB['apptmnt']))) and strtotime($rowB['apptmnt']) >= strtotime('1/1/2000')) {
                $apptmnt=date('l F jS Y',strtotime($rowB['apptmnt'])).' at '.date('h:i A',strtotime($rowB['apptmnt']));
                $apptset=true;
            }
            else {
                $apptmnt='<span style="color:red">Appointment not set, correct and try again.</span>';
                $apptset=false;
            }
            //$apptmnt=date('l F jS Y',strtotime($rowB['apptmnt'])).' at '.date('h:i A',strtotime($rowB['apptmnt']));
        }
    }
    else
    {
        $cfname='John';
        $clname='Customer';
        $cemail='bhcustomer@anywhere.com';
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
                   9=>'/SALESREPPHONENUMBER/',
                   10=>'/BLANKMESSAGEENTRY/'
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
                   9=>$ephone,
                   10=>"<p class=\"editarea\" id=\"bmeEmailBody\" contenteditable=\"true\"></p>"
                   );

    $esubj=preg_replace($srch_ar,$res_ar,trim($rowA['esubject']));
    $ebody=preg_replace($srch_ar,$res_ar,$rowA['ebody']);
    //preg_match($srch_ar[8],$rowA['ebody'],$tmatch);
    
    //$error=(count($tmatch) and !$apptset)?true;false;
    $ishtml=(isset($rowA['ishtml']) and $rowA['ishtml']==1)?true:false;
    $error=false;

    ?>
            <table width="620px">
                <tr>
                    <td>
    <?php
    
    if ($cid!=0)
    {
        showmenu($cid,$tid,$error);
    }

    ?>
                    </td>
                </tr>
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
                        <?php
                        
                        if ($ishtml) {
                            echo $ebody;
                        }
                        else {
                            echo nl2br($ebody);
                        }
                        
                        ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        if (
                            ($_SESSION['securityid']==26 or $_SESSION['securityid']==332 or $_SESSION['securityid']==1950) and
                            (isset($rowA['allowattach']) and $rowA['allowattach']==1)
                            ) {
                            showUserFileAttach($cid);
                        }
                        ?>
                    </td>
                </tr>
            </table>
    <?php
}