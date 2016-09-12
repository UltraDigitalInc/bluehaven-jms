<?php

ini_set('display_errors','On');

/**
 * Example QuickBooks SOAP Server / Web Service
 * 
 * This is an example Web Service which adds customers to QuickBooks desktop 
 * editions via the QuickBooks Web Connector. 
 * 
 * You should copy this file and use this file as a reference for when you are 
 * creating your own Web Service to add, modify, query, or delete data from 
 * desktop versions of QuickBooks software. 
 * 
 * The basic idea behind this method of integration with QuickBooks desktop 
 * editions is to host this web service on your server and have the QuickBooks 
 * Web Connector connect to it and pass messages to QuickBooks. So, every time 
 * that an action occurs on your website which you wish to communicate to 
 * QuickBooks, you'll queue up a request (shown below, using the 
 * QuickBooks_Queue class). 
 * 
 * You'll write request handlers which generate qbXML requests for each type of 
 * action you queue up. Those qbXML requests will be passed by the Web 
 * Connector to QuickBooks, which will then process the requests and send back 
 * the responses. Your response handler will then process the response (you'll 
 * probably want to at least store the returned ListID or TxnID of anything you 
 * create within QuickBooks) and this pattern will continue until there are no 
 * more requests in the queue for QuickBooks to process. 
 * 
 * @author Keith Palmer <keith@consolibyte.com>
 * 
 * @package QuickBooks
 * @subpackage Documentation
 */

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
error_reporting(E_STRICT);

// Require the framework
require_once 'QuickBooks.php';
require_once 'bhsoap_server_func.php';

// set this to log into QBWC
$user = 'bhsoap';
$pass = 'bhsoap';

// Map QuickBooks actions to handler functions
$map = array(
	 QUICKBOOKS_ADD_CUSTOMER => 		array('_quickbooks_customer_add_request', '_quickbooks_customer_add_response')
	,QUICKBOOKS_MOD_CUSTOMER => 		array('_quickbooks_customer_mod_request', '_quickbooks_customer_mod_response')
	,QUICKBOOKS_ADD_SERVICEITEM =>		array('_quickbooks_service_add_request', '_quickbooks_service_add_response')
	,QUICKBOOKS_MOD_SERVICEITEM =>		array('_quickbooks_service_mod_request', '_quickbooks_service_mod_response')
	,QUICKBOOKS_ADD_INVENTORYITEM =>	array('_quickbooks_inventory_add_request', '_quickbooks_inventory_add_response')
	,QUICKBOOKS_MOD_INVENTORYITEM =>	array('_quickbooks_inventory_mod_request', '_quickbooks_inventory_mod_response')
	,QUICKBOOKS_ADD_ESTIMATE => 		array('_quickbooks_estimate_add_request', '_quickbooks_estimate_add_response')
	,QUICKBOOKS_MOD_ESTIMATE => 		array('_quickbooks_estimate_mod_request', '_quickbooks_estimate_mod_response')
	,QUICKBOOKS_ADD_NONINVENTORYITEM =>	array('_quickbooks_noninventory_add_request', '_quickbooks_noninventory_add_response')
	);

// This is entirely optional, use it to trigger actions when an error is returned by QuickBooks
$errmap = array(
	3070 => '_quickbooks_error_stringtoolong',				// Whenever a string is too long to fit in a field, call this function: _quickbooks_error_stringtolong()
	// 'CustomerAdd' => '_quickbooks_error_customeradd', 	// Whenever an error occurs while trying to perform an 'AddCustomer' action, call this function: _quickbooks_error_customeradd()
	// '*' => '_quickbooks_error_catchall', 				// Using a key value of '*' will catch any errors which were not caught by another error handler
	// ... more error handlers here ...
	);

// An array of callback hooks
$hooks = array(
	// There are many hooks defined which allow you to run your own functions/methods when certain events happen within the framework
	// QUICKBOOKS_HANDLERS_HOOK_LOGINSUCCESS => '_quickbooks_hook_loginsuccess', 	// Run this function whenever a successful login occurs
	);

// Logging level
//$log_level = QUICKBOOKS_LOG_NORMAL;
//$log_level = QUICKBOOKS_LOG_VERBOSE;
//$log_level = QUICKBOOKS_LOG_DEBUG;				
$log_level = QUICKBOOKS_LOG_DEVELOP;		// Use this level until you're sure everything works!!!

// What SOAP server you're using 
$soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;		// A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)

$soap_options = array(		// See http://www.php.net/soap
	);

$handler_options = array(
	//'authenticate_dsn' => ' *** YOU DO NOT NEED TO PROVIDE THIS CONFIGURATION VARIABLE TO USE THE DEFAULT AUTHENTICATION METHOD FOR THE DRIVER YOU'RE USING (I.E.: MYSQL) *** '
	//'authenticate_dsn' => 'ldapv3://ldap.example.com:389/ou=People,dc=example,dc=com',
	//'authenticate_dsn' => 'mysql://user:pass@localhost/database?quickbooks_user',  
	//'authenticate_dsn' => 'postgresql://user:pass@localhost/database?quickbooks_user', 
	//'authenticate_dsn' => 'function://your_function_name_here', 
	);		// See the comments in the QuickBooks/Server/Handlers.php file

$driver_options = array(		// See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )
	//'max_log_history' => 1024,	// Limit the number of quickbooks_log entries to 1024
	//'max_queue_history' => 64, 	// Limit the number of *successfully processed* quickbooks_queue entries to 64
	);

$callback_options = array(
	);

// Set this to log into SOAP DB
$db_user = 'sa';
$db_pass = 'date1995';
$db_host = 'CORP-DB01';
$db_catl = 'BH_SOAP'; // <----

$dsn = 'mssql://'. $db_user .':'. $db_pass .'@'. $db_host .'/'.$db_catl; //Connect to MS SQL Server database

if (!QuickBooks_Utilities::initialized($dsn))
{
	// Initialize creates the neccessary database schema for queueing up requests and logging
	QuickBooks_Utilities::initialize($dsn);
	
	// This creates a username and password which is used by the Web Connector to authenticate
	QuickBooks_Utilities::createUser($dsn, $user, $pass);
}

// Create a new server and tell it to handle the requests
// __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
$Server = new QuickBooks_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
$response = $Server->handle(true, true);

// If you wanted, you could do something with $response here for debugging

$fp = fopen(__FILE__.'_process.log', 'a+');
fwrite($fp, $response);
fclose($fp);
