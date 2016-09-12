<?php

function removequote($data)
{
	$out=preg_replace("/'/","",$data);
	return $out;
}

function splitonspace($data)
{
	$u_data=preg_split("/ +/",$data);
	$lname=array_pop($u_data);

	$fn="";
	foreach ($u_data as $n => $v)
	{
		$fn=$v." ";
	}

	$dout=array(0=>$fn,1=>$lname);
	return $dout;
}

function MailSend($emc) // Secure Email broken until SSL is enabled on JMS Web Server. Send Clear Text until then
{
	include('connect_db.php');
    require_once('phpmail/class.phpmailer.php');
    include('phpmail/class.smtp.php'); // optional, gets called from within class.phpmailer.php if not already loaded
    
    $mail             = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 465;                   // set the SMTP port for the GMAIL server
    $mail->Username   = "bhcustcare@bluehaven.com";  // GMAIL username
    $mail->Password   = "nuvo2029";            // GMAIL password
    
    $mail->SetFrom('bhcustcare@bluehaven.com', 'Blue Haven Customer Care');
    
    //$mail->AddReplyTo('tedh@bhnmi.com', 'Test Test');
    
    $mail->Subject    = $emc['esubject'];
    
    //$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
    
    $mail->MsgHTML($emc['ebody']);
    
    //$address = "thelton@bluehaven.com";
    $mail->AddAddress($emc['to']);
    
    if(!$mail->Send())
	{
		echo "Mailer Error: " . $mail->ErrorInfo;
    }
	else
	{
		$qry	 = "insert into jest..EmailTracking (oid,lid,tid,cid,uid,sdate,emailaddr) values ";
		$qry	.= "(".$emc['oid'].",".$emc['lid'].",".$emc['tid'].",".$emc['cid'].",".$emc['uid'].",getdate(),'".$emc['to']."');";
		//$res	 = mssql_query($qry);
		
		echo $qry;
    }
}

function autosort()
{
	$recdate	=time();
	$cdate		=time();
    $secid      =1797; // User ID to process leads
    $inscnt	    =0;
    
	$qry		= "SELECT * FROM lead_inc WHERE sorted!='1';";
	$res		= mssql_query($qry);
	$nrow		= mssql_num_rows($res);

	//echo "N1: ".$nrow."<br>";

	$qry0	= "SELECT * FROM offices WHERE active=1 AND am!='0';";
	$res0	= mssql_query($qry0);
	$nrow0  = mssql_num_rows($res0);

	$ap=0;
	if ($nrow0 > 0)
	{
		$sarray=array(0=>0);
		while($row0=mssql_fetch_array($res0))
		{
			$sarray[$row0['officeid']]=0;
		}
	}
	else
	{
		echo "<font color=\"red\"><b>ERROR!</b> no active Offices!</font>\n";
	}

	if ($nrow > 0)
	{
		while($row=mssql_fetch_array($res))
		{
			$trzip	=trim($row['zip']);
			if (strlen($trzip) == 5)
			{
				$qryA	= "SELECT top 1 * FROM jest..zip_to_zip WHERE czip='".$trzip."';";
				$resA	= mssql_query($qryA);
                $rowA   = mssql_fetch_array($resA);
				$nrowA	= mssql_num_rows($resA);
                
                if ($nrowA > 0)
				{
                    $qryB	= "SELECT officeid,am,name,active,leadforward FROM offices WHERE zip='".$rowA['ozip']."';";
                    $resB	= mssql_query($qryB);
                    $rowB	= mssql_fetch_array($resB);
                }
                else
                {
                    $qryB	= "SELECT officeid,am,name,active,leadforward FROM offices WHERE officeid=89;";
                    $resB	= mssql_query($qryB);
                    $rowB	= mssql_fetch_array($resB);
                }

                if ($rowB['leadforward']==0)
                {
                    if ($rowB['am']!=0 && $rowB['active']==1)
                    {
                        $ndata=splitonspace($row['lname']);

                        $qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowB['officeid']."';";
                        $resCa = mssql_query($qryCa);
                        $rowCa = mssql_fetch_row($resCa);
                        $nrowCa= mssql_num_rows($resCa);

                        if ($nrowCa==0)
                        {
                            $ncid=1;
                        }
                        else
                        {
                            $ncid=$rowCa[0]+1;
                        }

                        $qryC	= "INSERT INTO cinfo ";
                        $qryC .= "(added,updated,officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
                        $qryC .= "VALUES (";
                        $qryC .= "'".$row['submitted']."',getdate(),'".$rowB['officeid']."','".$rowB['am']."','".$ndata[0]."','".removequote($ndata[1])."','".removequote($row['addr'])."',";
                        $qryC .= "'".removequote($row['city'])."','".removequote($row['state'])."','".$row['zip']."','".$row['bphone']."',";

                        if ($row['bphone']=="wk")
                        {
                            $qryC .= "'','".$row['phone']."',";
                        }
                        else
                        {
                            $qryC .= "'".$row['phone']."','',";
                        }

                        $qryC .= "'".removequote($row['email'])."','".removequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
                        $qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."');";
                        $resC	= mssql_query($qryC);

                        $qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowB['officeid']."',secid=".$secid." WHERE lid='".$row['lid']."';";
                        $resD	= mssql_query($qryD);

                        $oid=$rowB['officeid'];
                        if (is_array($sarray))
                        {
                            $sarray[$oid]=$sarray[$oid]+1;
                        }
                        else
                        {
                            $sarray=array($oid=>1);
                        }
                        $inscnt++;
                    }
                }
                else
                {
                    $qryBa	= "SELECT officeid,am,name,active FROM offices WHERE officeid='".$rowB['leadforward']."';";
                    $resBa	= mssql_query($qryBa);
                    $rowBa	= mssql_fetch_array($resBa);

                    if ($rowBa['am']!=0 && $rowBa['active']==1)
                    {
                        $ndata=splitonspace($row['lname']);

                        $qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowBa['officeid']."';";
                        $resCa = mssql_query($qryCa);
                        $rowCa = mssql_fetch_row($resCa);
                        $nrowCa= mssql_num_rows($resCa);

                        if ($nrowCa==0)
                        {
                            $ncid=1;
                        }
                        else
                        {
                            $ncid=$rowCa[0]+1;
                        }

                        $qryC	= "INSERT INTO cinfo ";
                        $qryC .= "(added,updated,officeid,securityid,cfname,clname,saddr1,scity,sstate,szip1,cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
                        $qryC .= "VALUES (";
                        $qryC .= "'".$row['submitted']."',getdate(),'".$rowBa['officeid']."','".$rowBa['am']."','".$ndata[0]."','".removequote($ndata[1])."','".removequote($row['addr'])."',";
                        $qryC .= "'".removequote($row['city'])."','".removequote($row['state'])."','".$row['zip']."','".$row['bphone']."',";

                        if ($row['bphone']=="wk")
                        {
                            $qryC .= "'','".$row['phone']."',";
                        }
                        else
                        {
                            $qryC .= "'".$row['phone']."','',";
                        }

                        $qryC .= "'".removequote($row['email'])."','".removequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
                        $qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."');";
                        $resC	= mssql_query($qryC);

                        $qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowBa['officeid']."',secid=".$secid." WHERE lid='".$row['lid']."';";
                        $resD	= mssql_query($qryD);

                        $oid=$rowBa['officeid'];

                        if (is_array($sarray))
                        {
                            $sarray[$oid]=$sarray[$oid]+1;
                        }
                        else
                        {
                            $sarray=array($oid=>1);
                        }
                        $inscnt++;
                    }
                }
			}
		}
	}
    
    echo '<br>Total Leads: '.$nrow;
    echo '<br>Leads Sorted: '. $inscnt;
}

function getleadmail($lc,$process,$test)
{
	//error_reporting(E_ALL);
	//ini_set('display_errors','On');
	//$dbg = array('process'=>$process,'printstatus'=>$test,'printcheck'=>$test,'printstruct'=>$test,'printhead'=>$test,'printbody'=>$test);
    
    if ($lc==112)
    {
        $MAIL_HOST			= "pop.gmail.com";
        $MAIL_HOST_CONNECT	= "{".$MAIL_HOST.":995/pop3/ssl}INBOX";
        $MAIL_USER_NAME		= "freepoolquotes@bluehaven.com";
        $MAIL_USER_PASS		= "FPQ4321";
        
        //$header_from_mbox   = 'thelton';
        //$header_from_host   = 'bluehaven.com';
    }
    else
    {
        echo "{success:false,results: {'emailProcessed':'0','reason':'InvalidLeadCode'}}";
        exit;
    }
    
    if ($process)
    {
        $mbox	= imap_open($MAIL_HOST_CONNECT,$MAIL_USER_NAME,$MAIL_USER_PASS) or die("ERROR: ".imap_last_error());
        $mcheck = imap_check($mbox);
            
        if ($test)
        {
            echo "<h1>Checking Mailbox</h1>\n";
            
            echo "<pre>";
            var_dump($mcheck);
            echo "</pre>";
            
            $mstatus = imap_status($mbox, $MAIL_HOST_CONNECT, SA_ALL);
            echo "<h1>Mailbox Status</h1>\n";
            
            echo "<pre>";
            var_dump($mstatus);
            echo "</pre>";
        }
        
		if ($mcheck->Nmsgs > 0)
		{
			$headers = imap_headers($mbox);
			
			if ($headers == false)
			{
				echo "No Messages <br />\n";
			}
			else
			{
				$x=1;
				$y=0;
				foreach ($headers as $val)
				{
					$header=array();
					$header=imap_headerinfo($mbox,$x) or die('Header '.$x.' not retrieved');
					
                    
                    
					if ($test)
					{
						echo '<b>Header</b><br>';
						
						echo "<pre>";
						var_dump($header);
						echo "</pre>";
                        
						$structure=imap_fetchstructure($mbox,$x) or die('Structure '.$x.' not retrieved');
						echo '<b>Structure</b><br>';
						
						echo "<pre>";
						print_r($structure);
						echo "</pre>";
					}
					
                    if (isset($header->subject))
                    //if (trim($header->from->mailbox) == trim($header_from_mbox))
					{
						$body=imap_fetchbody($mbox,$x,'1',2) or die('Body '.$x.' not retrieved');
                        
                        if ($test)
                        {
                            echo '<b>Body</b><br>';
                            
                            echo "<pre>";
                            var_dump($body);
                            echo "</pre>";
                        }
                        
                        procleademail($header,$body,$lc,$process,$test);
						$y++;
					}
					
					$x++;
				}
			}
			
			if ($y > 0)
			{
				echo "{success:true,results: {'emailProcessed':'".$y."','reason':'ProcessedNoErrors'}}";
			}
			else
			{
				echo "{success:false,results: {'emailProcessed':'".$y."','reason':'ProcessedNonCritical'}}";
			}
		}
		else
		{
			echo "{success:true,results: {'emailProcessed':'0','reason':'mailboxEmpty'}}";
		}
        
        imap_close($mbox);
	}
	else
	{
		echo "{success:false,results: {'emailProcessed':'0','reason':'processingDisabled'}}";
	}
}

?>