<?php
ini_set('display_errors','Off');
//error_reporting(E_ALL);
	  /* 
	   Last Edited By: Corey Rosamond
	   Edit Date:9/21/2014
	   Purpose: This file is in charge of routing phone calls as they come in
	   asdf
	  */
	// Define DB constantsa
	define("DB_USERNAME","matrix_ro");
	define("DB_PASSWORD","matrix_ro");
	define("DB_HOST","192.168.100.45");
	define("DB_NAME","jest");
	
	// Debug mode being on will cause alot of IO so please only have it on when absolutly needed
	define("DEBUG_MODE",false);
	define("LOG_FILE","E:\www\htdocs\logs\matrix_req_log.txt");
	
	// Header defs
	header("Content-type: text/xml");
	header("Content-Disposition: inline");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	// Mssql connection information
	$link = mssql_connect( DB_HOST, DB_USERNAME , DB_PASSWORD);
	if(!$link){ file_put_contents(LOG_FILE,"Database connection Failed!\r\n",FILE_APPEND); }
	$db_select = mssql_select_db(DB_NAME,$link);
	if(!$db_select){ file_put_contents(LOG_FILE,"Could not select DB!\r\n",FILE_APPEND); }
	
	// Prepend DID if its 4-digits
	$did = $_REQUEST['did'];
	if(strlen($did) == 4) {
	  $did = "619450".$did;
	}

	// Build the query
	$qry0 = "exec jest..tlh_zipmatrix_SMB 
	@cani='".$_REQUEST['cani']."',
	@did='".$did."',
	@czip='".$_REQUEST['czip']."',
	@tod='".$_REQUEST['tod']."';";
	
	// Run the query
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);
	$nrow = mssql_num_rows($res0);
	if(DEBUG_MODE){
	  $res = print_r($row0,true);
	  file_put_contents(LOG_FILE,$res."\r\n",FILE_APPEND);
	  file_put_contents(LOG_FILE,$qry0."\r\n",FILE_APPEND);
	  file_put_contents(LOG_FILE,$nrow."\r\n",FILE_APPEND);
	 }
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	echo "<!DOCTYPE ivr_info SYSTEM \"http://www.switchvox.com/xml/ivr.dtd\">";
	echo "<ivr_info>";
	if ($nrow > 0) {
	  if(DEBUG_MODE){ file_put_contents(LOG_FILE,"Results!\r\n",FILE_APPEND); }
	  echo "<variable name=\"ring\">".trim($row0[0])."</variable>";
	  echo "<variable name=\"clrid\">".trim($row0[1])."</variable>";
	  echo "<variable name=\"status\">".trim($row0[2])."</variable>";
	 } else {
	  if(DEBUG_MODE){ file_put_contents(LOG_FILE,"No Results!\r\n",FILE_APPEND); }
	  echo "<variable name=\"ring\">0</variable>";
	  echo "<variable name=\"clrid\">0</variable>";
	  echo "<variable name=\"status\">0</variable>";
	 }
	echo "</ivr_info>";
	?>