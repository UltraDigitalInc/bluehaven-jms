<?php

//ini_set('display_errors','On');
// We need to make sure the correct timezone is set, or some PHP installations will complain
if (function_exists('date_default_timezone_set'))
{
	// * MAKE SURE YOU SET THIS TO THE CORRECT TIMEZONE! *
	// List of valid timezones is here: http://us3.php.net/manual/en/timezones.php
	date_default_timezone_set('America/Tijuana');
}

// Include path for the QuickBooks library
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../');

// I always program in E_STRICT error mode... 
error_reporting(E_ALL|E_STRICT);


// Require the framework
require_once 'QuickBooks.php';
require_once 'bhsoap_server_func.php';

// set this to log into QBWC & SQL
$user = 'bhsoap55';
$pass = 'bhsoap55';
$qfile  = 'N:\\NewOrleans.QBW';

// This is entirely optional, use it to trigger actions when an error is returned by QuickBooks
$errmap = array('*' => '_quickbooks_generic_error');

// An array of callback hooks
$hooks = array();

// Logging level
//$log_level = QUICKBOOKS_LOG_NORMAL;
//$log_level = QUICKBOOKS_LOG_VERBOSE;
//$log_level = QUICKBOOKS_LOG_DEBUG;				
$log_level = QUICKBOOKS_LOG_DEVELOP;

// What SOAP server you're using 
$soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;

$soap_options = array();

$handler_options = array('qb_company_file'=>$qfile);

$driver_options = array();

$callback_options = array();

// Set this to log into SOAP DB
$dsn = 'mssql://'.$user.':'.$pass.'@CORP-DB01/BH_SOAP_NEWORLEANS'; //Connect to MS SQL Server database

if (!QuickBooks_Utilities::initialized($dsn))
{
	// Initialize creates the neccessary database schema for queueing up requests and logging
	QuickBooks_Utilities::initialize($dsn);
	
	// This creates a username and password which is used by the Web Connector to authenticate
	QuickBooks_Utilities::createUser($dsn, $user, $pass);
}

// Create a new server and tell it to handle the requests
$Server = new QuickBooks_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
$response = $Server->handle(true, true);

// If you wanted, you could do something with $response here for debugging
$fp = fopen(__FILE__.'_process.log', 'a+');
fwrite($fp, $response);
fclose($fp);
