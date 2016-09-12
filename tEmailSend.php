<?php

ini_set('display_errors','On');
error_reporting(E_ALL);

include ('./common_func.php');
//include ('./emailroutines_func.php');

$emc_ar=array(
                'to'=>'tedh19@yahoo.com',
                'from'=>'bhcustcare@bluehaven.com',
                'fromname'=>'BH Customer Care',
                'esubject'=>'Email Test',
                'ebody'=>'Tricky,But it works',
                'oid'=> 89,
                'lid'=> 44,
                'tid'=> 64,
                'cid'=> 234113,
                'uid'=> 26
);

//MailSend($emc_ar);
ExtEmailSendSSL($emc_ar);

?>