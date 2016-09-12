<?php

function mail_out($to,$sub,$mess)
{
	ini_set('SMTP','ZE_EMX01');
	ini_set('sendmail_from','jmsadmin@bluehaven.com');
	ini_set('sendmail_path','d:\tools\sendmail\sendmail.exe -t');

	$to		=	$to;
	$head	=	"From: JMS Mail System <jmsadmin@bluehaven.com>\r\n" .
	"Reply-To: jmsadmin@bluehaven.com\r\n" .
	"X-Mailer: PHP/" . phpversion();

	mail($to,$sub,$mess,$head);
}


function send_proc_notify($c)
{
	$to	= "thelton@bluehaven.com";
	$sub	= "JMS Process ($c)";
	$mess	= "Do Not Reply\r\n";
	
	mail_out($to,$sub,$mess);
}

send_proc_notify($_GET['status']);

?>