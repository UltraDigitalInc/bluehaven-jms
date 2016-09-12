<?php

function decode_quoprint($str) {
	$str = preg_replace("/\=([A-F][A-F0-9])/","",$str);
    return $str;
}

function m_decode($code,$data)
{
	if ($code==1)
	{
		return imap_utf8($data);
	}
	elseif ($code==3)
	{
		return imap_base64($data);
	}
	elseif ($code==4)
	{
		return imap_qprint($data);
	}
	else
	{
		return (decode_quoprint($data));
	}
}

function getemail()
{
	error_reporting(E_ALL);
	ini_set('display_errors','On');
    
	$process	=true;
	$testonly	=false;
	$cid_ar		=array();
	$fcid_ar	=array();
	
    $MAIL_HOST			= "imap.gmail.com";
    $MAIL_HOST_CONNECT	= "{".$MAIL_HOST.":993/imap/ssl}INBOX";
    $MAIL_USER_NAME		= "bhnmtest@bluehaven.com";
    $MAIL_USER_PASS		= "bhnm1234";
	//$MAIL_USER_NAME		= "bhcustcare@bluehaven.com";
    //$MAIL_USER_PASS		= "nuvo2029";

    
    if ($process)
    {
        $mbox	= imap_open($MAIL_HOST_CONNECT,$MAIL_USER_NAME,$MAIL_USER_PASS) or die("ERROR: ".imap_last_error());
        $mcheck = imap_check($mbox);
		//$mcheck = imap_status($mbox, "{".$MAIL_HOST.":995/pop3/ssl}INBOX", SA_MESSAGES);  
		
		$errors=imap_errors();
		if (is_array($errors))
		{
			echo "Status: " . $errors[0];
			$e=$errors[0];
			$y=0;
			$total=0;
			exit;
		}
		
        if ($testonly)
        {
            echo "<h1>Mailbox Status</h1>\n";
            
            echo "<pre>";
            var_dump($mcheck);
            echo "</pre>";
			imap_close($mbox);
        }
		else
		{
			if ($mcheck->Nmsgs > 0)
			{
				$x=1;
				$result = imap_fetch_overview($mbox,"1:{$mcheck->Nmsgs}",0);
				
				//echo "<pre>";
				//print_r($result);
				//echo "</pre>";
				
				foreach ($result as $overview)
				{
					$strct	=imap_fetchstructure($mbox, $overview->msgno) or die("STRUCTURE {$overview->msgno} not retrieved");
					$head	=imap_headerinfo($mbox,$overview->msgno) or die("HEAD {$overview->msgno} not retrieved");
					$body	=imap_fetchbody($mbox,$overview->msgno,'1',2) or die("BODY {$overview->msgno} not retrieved");
					$from=$head->from[0];
					$msgd=$head->message_id;
					
					echo "<pre>";
					
					/*
					if ($strct->encoding == 3)
					{
						$tfbody=base64_decode($body);
					}
					elseif ($strct->encoding == 4)
					{
						$tfbody=imap_qprint($body);
					}
					else
					{
						print_r($strct);
					}
					*/
					
					echo m_decode($strct->encoding,$body);
					
					//print_r($strct);
					//print_r($head);
					//print_r($body);
					echo "</pre>";
					
					
				}
			}
			else
			{
				echo 'No Msgs';
			}
			
			imap_close($mbox);
		}
	}
}

getemail();

?>