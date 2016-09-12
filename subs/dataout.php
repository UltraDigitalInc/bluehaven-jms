<?php

session_start();
//error_reporting(E_ALL);
//ini_set('display_errors','On');

if (isset($_SESSION['officeid']) && isset($_REQUEST['njobid']) && strlen($_REQUEST['njobid']) >= 4)
{
    header ("Content-Type:text/xml");
    //header ("Content-Disposition:attachment;filename=dataout.xml");
    header ("Content-Disposition:inline");
    
    include ("..\connect_db.php");
    include ("..\common_func.php");
    include ("..\calc_func.php");
    include ("..\xml_func.php");
	$xcont=XML_content($_SESSION['officeid'],$_REQUEST['njobid']);
    
    echo "<?xml version=\"1.0\"?> ";
    echo $xcont;
}

?>