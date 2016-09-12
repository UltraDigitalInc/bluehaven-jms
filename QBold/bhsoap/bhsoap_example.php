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
$user = 'jms_soap';
$pass = 'jms_soap';

// Set this to log into SOAP DB
$db_user = 'sa';
$db_pass = 'date1995';
$db_host = 'CORP-DB01';
$db_catl = 'BH_SOAP_Example'; // <----

$dsn = 'mssql://'. $db_user .':'. $db_pass .'@'. $db_host .'/'.$db_catl; //Connect to MS SQL Server database
