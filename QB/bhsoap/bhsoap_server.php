<?php
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

/*
if (!isset($_GET['oid']) or empty($_GET['oid']))
{
	exit;
}
*/

// Require the framework
require_once 'QuickBooks.php';

// set this to log into QBWC
$user = 'bhsoap';
$pass = 'bhsoap';

// Map QuickBooks actions to handler functions
$map = array(
	 QUICKBOOKS_ADD_CUSTOMER => 		array('_customer_add_request', '_customer_add_response')
	,QUICKBOOKS_MOD_CUSTOMER => 		array('_customer_mod_request', '_customer_mod_response')
	,QUICKBOOKS_ADD_EMPLOYEE => 		array('_employee_add_request', '_employee_add_response')
	,QUICKBOOKS_MOD_EMPLOYEE => 		array('_employee_mod_request', '_employee_mod_response')
	,QUICKBOOKS_QUERY_EMPLOYEE => 		array('_employee_qry_request', '_query_response')
    ,QUICKBOOKS_ADD_SALESREP => 		array('_salesrep_add_request', '_salesrep_add_response')
	,QUICKBOOKS_MOD_SALESREP => 		array('_salesrep_mod_request', '_salesrep_mod_response')
	,QUICKBOOKS_QUERY_ITEM =>			array('_itemquery_request', '_query_response')
	,QUICKBOOKS_ADD_SERVICEITEM =>		array('_service_add_request', '_service_add_response')
	,QUICKBOOKS_MOD_SERVICEITEM =>		array('_service_mod_request', '_service_mod_response')
	,QUICKBOOKS_ADD_INVENTORYITEM =>	array('_inventory_add_request', '_inventory_add_response')
	,QUICKBOOKS_MOD_INVENTORYITEM =>	array('_inventory_mod_request', '_inventory_mod_response')
    ,QUICKBOOKS_ADD_NONINVENTORYITEM =>	array('_noninventory_add_request', '_noninventory_add_response')
	,QUICKBOOKS_MOD_NONINVENTORYITEM =>	array('_noninventory_mod_request', '_noninventory_mod_response')
    ,QUICKBOOKS_ADD_DEPOSIT =>  		array('_deposit_add_request', '_deposit_add_response')
	,QUICKBOOKS_MOD_DEPOSIT =>  		array('_deposit_mod_request', '_deposit_mod_response')
	,QUICKBOOKS_ADD_ESTIMATE => 		array('_estimate_add_request', '_estimate_add_response')
	,QUICKBOOKS_MOD_ESTIMATE => 		array('_estimate_mod_request', '_estimate_mod_response')
	,QUICKBOOKS_QUERY_ESTIMATE => 		array('_estimate_qry_request', '_query_response')
    ,QUICKBOOKS_ADD_INVOICE =>   		array('_invoice_add_request', '_invoice_add_response')
	,QUICKBOOKS_MOD_INVOICE =>  		array('_invoice_mod_request', '_invoice_mod_response')
	);

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

$handler_options = array();

$driver_options = array();

$callback_options = array();

// Set this to log into SOAP DB
$dsn = 'mssql://sa:date1995@CORP-DB01/BH_SOAP'; //Connect to MS SQL Server database

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

function _quickbooks_error_stringtoolong($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
{
	//mail('your-email@your-domain.com', 
	//	'QuickBooks error occured!', 
	//	'QuickBooks thinks that ' . $action . ': ' . $ID . ' has a value which will not fit in a QuickBooks field...');
}

function _quickbooks_generic_error($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
{
	$fperr = fopen('QB_Generic_Error.log', 'a+');
	fwrite($fperr, $errmsg);
	fclose($fperr);
}

function _deposit_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row = mssql_fetch_array(mssql_query('SELECT securityid,fname,lname,phone,email,hdate,srep FROM security WHERE securityid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<DepositAddRq requestID="' . $requestID . '">
					<DepositAdd defMacro="MACROTYPE">
                        <DepositToAccountRef>
                            <FullName>DEFERRED REVENUE</FullName>
                        </DepositToAccountRef>
                        <Memo></Memo>
					</DepositAdd>
				</DepositAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

function _deposit_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	mssql_query("UPDATE security SET ListID='".trim($idents['ListID'])."',EditSequence='".trim($idents['EditSequence'])."' WHERE securityid=".$ID.";");
}

function _invoice_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
    
	$row = mssql_fetch_array(mssql_query('SELECT cid,officeid,jobid,ListID FROM cinfo WHERE cid=' . (int) $ID));
    
    $qry1 = "
            SELECT P.*,(select phscode from phasebase where phsid=P.phsid) as phscode
            FROM payment_schedule AS P
            WHERE P.oid=" . $row['officeid'] . " AND P.cid=" . $row['cid'] . " AND psid=" . $extra . ";";
    $res1 = mssql_query($qry1);
    
    $pay_ar=array();
    $ptype='';
    while ($row1 = mssql_fetch_array($res1))
    {
        if (isset($phsid) and ($phsid=1 or $phsid=48))
        {
            $ptype='Deposit';
        }
        else
        {
            $ptype='Payment';
        }
        
        $pay_ar[]=array('jobid'=>$row1['jobid'],'phscode'=>$row1['phscode'],'amount'=>number_format($row1['amount'],2,'.',''),'ptype'=>$ptype);
    }
    
    if (count($pay_ar) > 0)
    {
        // Create and return a qbXML request
        $xml = '<?xml version="1.0" encoding="utf-8"?>
            <?qbxml version="8.0"?>
            <QBXML>
                <QBXMLMsgsRq onError="stopOnError">
                    <InvoiceAddRq>
                        <InvoiceAdd>
                            <CustomerRef>
                                <ListID>' . trim($row['ListID']) . '</ListID>
                            </CustomerRef>
                            <Memo>Job '.$row['jobid'].'</Memo>';
                            
        foreach ($pay_ar as $np => $v)
        {
            $xml = "
                            <InvoiceLineAdd>
                                <Desc>Scheduled ".$v['phscode']." ".$v['ptype']."</Desc>
                                <Rate>".$v['amount']."</Rate>
                            </InvoiceLineAdd>";
        }
                            
        $xml .= '       </InvoiceAdd>
                    </InvoiceAddRq>
                </QBXMLMsgsRq>
            </QBXML>';
    }
    else
    {
        $xml='';
    }
    
	return $xml;
}

function _invoice_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	mssql_query("UPDATE payment_schedule SET ListID='".trim($idents['ListID'])."',EditSequence='".trim($idents['EditSequence'])."' WHERE psid=".$ID.";");
}

function get_inventoryitem_data_pkg($oid,$iid)
{
    $out=array('alive'=>false);
    
    mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');

    $qry0 ="select officeid,pb_code from jest..offices where officeid=" . (int) $oid . ";";
    $res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 0)
    {
        $qry1 = "
                SELECT
                    A.[invid],A.[accid],A.[phsid],A.[item],A.[bprice],
                    (SELECT ListID FROM jest..phasebase WHERE phsid=A.phsid) as ListID,
                    (SELECT ParentID FROM jest..phasebase WHERE phsid=A.phsid) as ParentName,
                    (SELECT AccountName FROM jest..phasebase WHERE phsid=A.phsid) as AccountName
                FROM
                    jest..[".trim($row0['pb_code'])."inventory] as A
                WHERE A.[invid] =" . (int) $iid .";
                ";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);
        $nrow1= mssql_num_rows($res1);
        
        if ($nrow1 == 1)
        {
            $out = array(
                'alive'=>       true,
                'iid'=>         $row1['invid'],
                'accid'=>       $row1['accid'],
                'itemname'=>    $row1['item'],
                'baseprice'=>   $row1['bprice'],
                'ListID'=>      $row1['ListID'],
                'ParentName'=>  $row1['ParentName'],
                'AccountName'=> $row1['AccountName']
            );
        }
    }
    
    return $out;
}

function get_serviceitem_data_pkg($oid,$iid)
{
    $out=array('alive'=>false);
    
    mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');

    $qry0 ="select officeid,pb_code from jest..offices where officeid=" . (int) $oid . ";";
    $res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 0)
    {
        $qry1 = "
                SELECT
                    A.id,A.accid,A.phsid,A.item,A.bprice,
                    (SELECT ListID FROM jest..phasebase WHERE phsid=A.phsid) as ListID,
                    (SELECT ParentID FROM jest..phasebase WHERE phsid=A.phsid) as ParentName,
                    (SELECT AccountName FROM jest..phasebase WHERE phsid=A.phsid) as AccountName
                FROM
                    jest..[".trim($row0['pb_code'])."accpbook] as A
                WHERE A.[id] =" . (int) $iid .";
                ";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);
        $nrow1= mssql_num_rows($res1);
        
        if ($nrow1 == 122)
        {
            $out = array(
                'alive'=>       true,
                'accid'=>       $row1['accid'],
                'itemname'=>    $row1['item'],
                'baseprice'=>   $row1['bprice'],
                'ListID'=>      $row1['ListID'],
                'ParentName'=>  $row1['ParentName'],
                'AccountName'=> $row1['AccountName']
            );
        }
    }
    
    $out = array(
                'alive'=>       true,
                'accid'=>       $oid,
                'itemname'=>    $iid
            );
    return $out;
}

function _customer_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row = mssql_fetch_array(mssql_query('SELECT * FROM cinfo WHERE cid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerAddRq requestID="' . $requestID . '">
					<CustomerAdd>
						<Name>'. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .' '. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .' ('. $ID .')</Name>
						<CompanyName>'. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .' '. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .'</CompanyName>
						<FirstName>'. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .'</FirstName>
						<LastName>'. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .'</LastName>
						<BillAddress>
							<Addr1>'. ucwords(htmlspecialchars(strtolower(trim($row['caddr1'])))) .'</Addr1>
							<City>'. ucwords(htmlspecialchars(strtolower(trim($row['ccity'])))) .'</City>
							<State>'. trim($row['cstate']) .'</State>
							<PostalCode>'. trim($row['czip1']) .'</PostalCode>
							<Country>United States</Country>
						</BillAddress>
						<Phone>'. trim($row['chome']) .'</Phone>
						<Email>'. trim($row['cemail']) .'</Email>
						<Contact>'. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .' '. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .'</Contact>
					</CustomerAdd>
				</CustomerAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

function _customer_add_request_OLD($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row = mssql_fetch_array(mssql_query('SELECT * FROM cinfo WHERE cid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerAddRq requestID="' . $requestID . '">
					<CustomerAdd>
						<Name>'. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .' '. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .' ('. $ID .')</Name>
						<CompanyName>'. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .' '. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .'</CompanyName>
						<FirstName>'. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .'</FirstName>
						<LastName>'. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .'</LastName>
						<BillAddress>
							<Addr1>'. ucwords(htmlspecialchars(strtolower(trim($row['caddr1'])))) .'</Addr1>
							<City>'. ucwords(htmlspecialchars(strtolower(trim($row['ccity'])))) .'</City>
							<State>'. trim($row['cstate']) .'</State>
							<PostalCode>'. trim($row['czip1']) .'</PostalCode>
							<Country>United States</Country>
						</BillAddress>
						<Phone>'. trim($row['chome']) .'</Phone>
						<Email>'. trim($row['cemail']) .'</Email>
						<Contact>'. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .' '. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .'</Contact>
					</CustomerAdd>
				</CustomerAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

function _customer_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest_ext');
	//mssql_query("INSERT INTO [jms_qb_ident_customer_map] (cid,ListID,EditSequence) VALUES (". (int) $ID .",'".trim($idents['ListID'])."','".trim($idents['EditSequence'])."');");
    mssql_query("UPDATE jest..cinfo SET ListID='".trim($idents['ListID'])."',EditSequence='".trim($idents['EditSequence'])."' WHERE cid=". (int) $ID .";");
}

function _customer_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row = mssql_fetch_array(mssql_query('SELECT * FROM cinfo WHERE cid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerModRq requestID="' . $requestID . '">
					<CustomerNodd>
						<Name>'. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .' '. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .' ('. $ID .')</Name>
						<CompanyName>'. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .' '. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .'</CompanyName>
						<FirstName>'. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .'</FirstName>
						<LastName>'. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .'</LastName>
						<BillAddress>
							<Addr1>'. ucwords(htmlspecialchars(strtolower(trim($row['caddr1'])))) .'</Addr1>
							<City>'. ucwords(htmlspecialchars(strtolower(trim($row['ccity'])))) .'</City>
							<State>'. trim($row['cstate']) .'</State>
							<PostalCode>'. trim($row['czip1']) .'</PostalCode>
							<Country>United States</Country>
						</BillAddress>
						<Phone>'. trim($row['chome']) .'</Phone>
						<Email>'. trim($row['cemail']) .'</Email>
						<Contact>'. ucwords(htmlspecialchars(strtolower(trim($row['cfname'])))) .' '. ucwords(htmlspecialchars(strtolower(trim($row['clname'])))) .'</Contact>
					</CustomerMod>
				</CustomerModRq>
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

function _customer_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest_ext');
	mssql_query("UPDATE jms_qb_ident_customer_map SET EditSequence='".trim($idents['EditSequence'])."' WHERE ListID='".trim($idents['ListID'])."';");
}

function _employee_add_requestOLD($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row = mssql_fetch_array(mssql_query('SELECT * FROM security WHERE securityid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<EmployeeAddRq requestID="' . $requestID . '">
					<EmployeeAdd>
						<FirstName>'. ucwords(htmlspecialchars(strtolower(trim($row['fname'])))) .'</FirstName>
						<LastName>'. ucwords(htmlspecialchars(strtolower(trim($row['lname'])))) .'</LastName>
						<Phone>'. trim($row['phone']) .'</Phone>
						<Email>'. trim($row['email']) .'</Email>
						<HiredDate>'. date('Y-m-d',strtotime($row['hdate'])) .'</HiredDate>
					</EmployeeAdd>
				</EmployeeAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

function _employee_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row = mssql_fetch_array(mssql_query('SELECT securityid,fname,lname,phone,email,hdate,srep FROM security WHERE securityid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<EmployeeAddRq requestID="' . $requestID . '">
					<EmployeeAdd>
						<FirstName>'. ucwords(htmlspecialchars(strtolower(trim($row['fname'])))) .'</FirstName>
						<LastName>'. ucwords(htmlspecialchars(strtolower(trim($row['lname'])))) .'</LastName>
						<Phone>'. trim($row['phone']) .'</Phone>
						<Email>'. trim($row['email']) .'</Email>
						<HiredDate>'. date('Y-m-d',strtotime($row['hdate'])) .'</HiredDate>
					</EmployeeAdd>
				</EmployeeAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

function _employee_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	mssql_query("UPDATE security SET ListID='".trim($idents['ListID'])."',EditSequence='".trim($idents['EditSequence'])."' WHERE securityid=".$ID.";");
}

function _employee_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row = mssql_fetch_array(mssql_query('SELECT * FROM security WHERE securityid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<EmployeeModRq requestID="' . $requestID . '">
					<EmployeeMod>
						<ListID>'. trim($row['ListID']) .'</ListID>
						<EditSequence>'. trim($row['EditSequence']) .'</EditSequence>
						<FirstName>'. ucwords(htmlspecialchars(strtolower(trim($row['fname'])))) .'</FirstName>
						<LastName>'. ucwords(htmlspecialchars(strtolower(trim($row['lname'])))) .'</LastName>
						<Phone>'. trim($row['phone']) .'</Phone>
						<Email>'. trim($row['email']) .'</Email>
						<HiredDate>'. date('Y-m-d',strtotime($row['hdate'])) .'</HiredDate>
					</EmployeeMod>
				</EmployeeModRq>
				
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

function _employee_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	mssql_query("UPDATE security SET EditSequence='".trim($idents['EditSequence'])."' WHERE securityid=".$ID.";");
}

function _employee_qry_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row = mssql_fetch_array(mssql_query('SELECT * FROM security WHERE securityid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<EmployeeQueryRq requestID="' . $requestID . '">
					<EmployeeQuery>
						<ListID>'. trim($row['ListID']) .'</ListID>
					</EmployeeQuery>
				</EmployeeQueryRq>
				
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

function _employee_qry_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest_ext');
	mssql_query("INSERT INTO qb_query_response (oid,pid,qbxml_response) VALUES (".$extra.",".$ID.",'".trim($xml)."');");
}

function _salesrep_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row = mssql_fetch_array(mssql_query('SELECT fname,lname,ListID FROM security WHERE securityid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<SalesRepAddRq requestID="' . $requestID . '">
					<SalesRepAdd>
                        <Initial>'. strtoupper(substr(trim($row['fname']),0,1)) . strtoupper(substr(trim($row['lname']),0,1)). '</Initial>
                        <SalesRepEntityRef>
                            <ListID>'. trim($row['ListID']) .'</ListID>
                        </SalesRepEntityRef>
					</SalesRepAdd>
				</SalesRepAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
}

function _salesrep_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	mssql_query("UPDATE security SET SR_ListID='".trim($idents['ListID'])."',SR_EditSequence='".trim($idents['EditSequence'])."' WHERE securityid=".$ID.";");
}

function _estimate_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{    
	mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");
    
    $row  = mssql_fetch_array(mssql_query("SELECT C.cid,C.officeid,C.jobid,C.ListID as CID_ListID,(SELECT SR_ListID FROM jest..security WHERE securityid=C.securityid) as SR_ListID FROM cinfo AS C WHERE C.officeid=". (int) $extra ." and C.jobid='" . $ID . "';"));
    
    $res1 = mssql_query("SELECT ListID as IID_ListID,tquantity,totalprice FROM JobCost_Service WHERE oid=". $row['officeid'] ." and jobid='" . $row['jobid'] . "';");
    $nrow1= mssql_num_rows($res1);
    
    $res2 = mssql_query("SELECT ListID as IID_ListID,tquantity,totalprice FROM JobCost_Material WHERE oid=". $row['officeid'] ." and jobid='" . $row['jobid'] . "';");
    //$nrow2=mssql_num_rows($res2);
    $nrow2=0;
    
    /*
    $res3 = mssql_query("
                        select 
                            J.id,J.officeid,J.phsid,J.jobid,J.rdbid,J.sdesc,J.smisc,J.bprice,
                            (select ParentID from phasebase where phsid=J.phsid) as ParentID,
                            (select ListID from phasebase where phsid=J.phsid) as PListID
                        from jbids_breakout as J where J.officeid=". $row['officeid'] ." and jobid='" . $row['jobid'] . "';
    ");
    $nrow3=mssql_num_rows($res3);
    */
	
    $xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
  <QBXML>
    <QBXMLMsgsRq onError="stopOnError">
      <EstimateAddRq requestID="' . $requestID . '">
        <EstimateAdd>
            <CustomerRef>
                <ListID>'.trim($row['CID_ListID']).'</ListID>
            </CustomerRef>
            <TxnDate>'. date('Y-m-d',time()) .'</TxnDate>
            <IsActive>1</IsActive>
            <DueDate>'. date('Y-m-d',time()) .'</DueDate>
        ';

    if ($row['SR_ListID']!='NULL')
    {
        $xmlout.='
            <SalesRepRef> 
                <ListID>'.$row['SR_ListID'].'</ListID>
            </SalesRepRef>
        ';
    }

    if ($nrow1 > 0)
    {
        while ($row1 = mssql_fetch_array($res1))
        {
            $xmlout.='          
                <EstimateLineAdd>
                    <ItemRef>
                        <ListID>'. $row1['IID_ListID'] .'</ListID>
                    </ItemRef>
                    <Quantity>'. $row1['tquantity'] .'</Quantity>
                    <Rate>'. number_format($row1['totalprice'],2,'.','') .'</Rate>
                </EstimateLineAdd>
            ';
        }
    }

    if ($nrow2 > 0)
    {
        while ($row2 = mssql_fetch_array($res2))
        {
            $xmlout.='          
                <EstimateLineAdd>
                    <ItemRef>
                        <ListID>'. $row2['IID_ListID'] .'</ListID>
                    </ItemRef>
                    <Quantity>'. $row2['tquantity'] .'</Quantity>
                    <Rate>'. number_format($row2['totalprice'],2,'.','') .'</Rate>
                </EstimateLineAdd>
            ';
        }
    }
    
    /*
    if ($nrow3 > 999999999999999)
    {
        while ($row3 = mssql_fetch_array($res3))
        {
            $xmlout.='          
                <EstimateLineAdd>
                    <ItemRef>
                        <ListID>'. $row3['IID_ListID'] .'</ListID>
                    </ItemRef>
                    <Quantity>1</Quantity>
                    <Amount>'. $row3['totalprice'] .'</Amount>
                </EstimateLineAdd>
            ';
        }
    }
    */

    $xmlout.='          
        </EstimateAdd>
      </EstimateAddRq>
    </QBXMLMsgsRq>
  </QBXML>';

  return $xmlout;
}

function _estimate_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
    mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");
    mssql_fetch_array(mssql_query("UPDATE jobs SET ListID='".$idents['ListID']."',EditSequence='".$idents['EditSequence']."' WHERE officeid=". (int) $extra ." and jobid='" . $ID . "';"));
}

function _inventory_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	//mssql_connect('CORP-DB02','sa','date1995');
	//mssql_select_db('jest');
	//$row1 = mssql_fetch_array(mssql_query("select pb_code from jest..offices where officeid=" . (int) $extra));
	//$row9 = mssql_fetch_array(mssql_query("select item,vpno,bprice from [".trim($row1['pb_code'])."inventory] where [invid]=" . (int) $ID));
    
    mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');

    $qry0 ="select officeid,pb_code from jest..offices where officeid=" . (int) $extra . ";";
    $res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 0)
    {
        $qry1 = "
                SELECT
                    A.invid,A.accid,A.phsid,A.vpno,A.item,A.bprice,
                    (SELECT ListID FROM jest..phasebase WHERE phsid=A.phsid) as ListID,
                    (SELECT ParentID FROM jest..phasebase WHERE phsid=A.phsid) as ParentName,
                    (SELECT AccountName FROM jest..phasebase WHERE phsid=A.phsid) as AccountName,
                    (SELECT COGSAccountName FROM jest..phasebase WHERE phsid=A.phsid) as COGSAccountName
                FROM
                    jest..[".trim($row0['pb_code'])."inventory] as A
                WHERE A.[invid] =" . (int) $ID .";
                ";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);
        $nrow1= mssql_num_rows($res1);
        
        if ($nrow1 == 1)
        {
            $idata = array(
                'alive'=>       true,
                'iid'=>         $row1['invid'],
                'accid'=>       $row1['accid'],
                'vpno'=>        $row1['vpno'],
                'itemname'=>    $row1['item'],
                'baseprice'=>   $row1['bprice'],
                'ListID'=>      $row1['ListID'],
                'ParentName'=>  $row1['ParentName'],
                'AccountName'=> $row1['AccountName'],
                'COGSAN'=>      $row1['COGSAccountName']
            );
            
            $xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
		
		<ItemInventoryAddRq requestID="'. $requestID .'">
			<ItemInventoryAdd>
				<Name>'.htmlspecialchars(substr(trim($idata['itemname']),0,30)).'</Name>
                <ParentRef>
                    <FullName>'.trim($idata['ParentName']).'</FullName>
                </ParentRef>
				<ManufacturerPartNumber>'.htmlspecialchars(substr(trim($idata['vpno']),0,30)).'</ManufacturerPartNumber>
				<SalesPrice>'. number_format($idata['baseprice'],2,'.','') .'</SalesPrice>
				<IncomeAccountRef>
					<FullName>'.trim($idata['AccountName']).'</FullName>
				</IncomeAccountRef>
				<COGSAccountRef>
					<FullName>'.trim($idata['COGSAN']).'</FullName>
				</COGSAccountRef>
				<AssetAccountRef>
					<FullName>'.trim($idata['AccountName']).'</FullName>
				</AssetAccountRef>
				<ReorderPoint>1</ReorderPoint>
				<QuantityOnHand>1</QuantityOnHand>
				<TotalValue>0.00</TotalValue>
			</ItemInventoryAdd>
		</ItemInventoryAddRq>
	
	</QBXMLMsgsRq>
</QBXML>';

        }
    }
	
	return $xmlout;
}

function _inventory_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	
    $row1 = mssql_fetch_array(mssql_query("select officeid,pb_code from jest..offices where officeid=" . (int) $extra));
	mssql_query("UPDATE [jest]..[".trim($row1['pb_code'])."inventory] SET ListID='".trim($idents['ListID'])."',EditSequence='".trim($idents['EditSequence'])."' WHERE [officeid]=".$row1['officeid']." and [invid]=". (int) $ID .";");
}

function _inventory_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	//include ('inventoryadd_qbXML.php');
	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	
	$row0 = mssql_fetch_array(mssql_query("select oid,invid,ListID,EditSequence from jest_ext..jms_qb_ident_inventory_map where invid=" . (int) $ID));
	$row1 = mssql_fetch_array(mssql_query("select pb_code from jest..offices where officeid=" . (int) $row0['oid']));
	$row9 = mssql_fetch_array(mssql_query("SELECT item,bprice FROM [".trim($row1['pb_code'])."inventory] WHERE invid =" . (int) $row0['invid']));
	
	$xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
		
		<ItemInventoryModRq requestID="' . $requestID . '">
			<ItemInventoryMod>
				<ListID>'.trim($row0['ListID']).'</ListID>
				<EditSequence>'.trim($row0['EditSequence']).'</EditSequence>
				<Name>'.substr(trim($row9['item']),0,31).'</Name>
				<SalesPrice>'. number_format($row9['bprice'],2,'.','') .'</SalesPrice>
			</ItemInventoryMod>
		</ItemInventoryModRq>
	
	</QBXMLMsgsRq>
</QBXML>';
	
	return $xmlout;
}

function _inventory_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest_ext');
	mssql_query("UPDATE jms_qb_ident_inventory_map SET EditSequence='".trim($idents['EditSequence'])."' WHERE ListID='".trim($idents['ListID'])."';");
}

function _service_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	//$idata=get_serviceitem_data_pkg($extra,$ID);
	
    mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');

    $qry0 ="select officeid,pb_code from jest..offices where officeid=" . (int) $extra . ";";
    $res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 0)
    {
        $qry1 = "
                SELECT
                    A.id,A.accid,A.phsid,A.item,A.bprice,
                    (SELECT ListID FROM jest..phasebase WHERE phsid=A.phsid) as ListID,
                    (SELECT ParentID FROM jest..phasebase WHERE phsid=A.phsid) as ParentName,
                    (SELECT AccountName FROM jest..phasebase WHERE phsid=A.phsid) as AccountName
                FROM
                    jest..[".trim($row0['pb_code'])."accpbook] as A
                WHERE A.[id] =" . (int) $ID .";
                ";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);
        $nrow1= mssql_num_rows($res1);
        
        if ($nrow1 == 1)
        {
            $idata = array(
                'alive'=>       true,
                'iid'=>         $row1['id'],
                'accid'=>       $row1['accid'],
                'itemname'=>    $row1['item'],
                'baseprice'=>   $row1['bprice'],
                'ListID'=>      $row1['ListID'],
                'ParentName'=>  $row1['ParentName'],
                'AccountName'=> $row1['AccountName']
            );
        }
    }
    
    //if ($idata['alive'])
    //{
        $xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
<QBXML>
	<QBXMLMsgsRq onError="continueOnError">
	
		<ItemServiceAddRq requestID="' . $requestID . '">
			<ItemServiceAdd>
				<Name>'. htmlspecialchars(substr(trim($idata['itemname']),0,25)) .' '. $idata['iid'] .'</Name>
                <ParentRef>
                    <FullName>'. trim($idata['ParentName']) .'</FullName>
                </ParentRef>
				<SalesOrPurchase>
					<Desc>'. trim($idata['accid']) .'</Desc>
					<Price>'. number_format($idata['baseprice'],2,'.','') .'</Price>
					<AccountRef>
						<FullName>'. trim($idata['AccountName']) .'</FullName>
					</AccountRef>
				</SalesOrPurchase>
			</ItemServiceAdd>
		</ItemServiceAddRq>
	
	</QBXMLMsgsRq>
</QBXML>';
    //}
    
	return $xmlout;
}

function _service_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	$row1 = mssql_fetch_array(mssql_query("select officeid,pb_code from jest..offices where officeid=" . (int) $extra));
	mssql_query("UPDATE [jest]..[".trim($row1['pb_code'])."accpbook] SET ListID='".trim($idents['ListID'])."',EditSequence='".trim($idents['EditSequence'])."' WHERE [officeid]=".$row1['officeid']." and [id]=". (int) $ID .";");
	mssql_query("INSERT INTO [jest_ext]..[jms_qb_ident_service_map] (srvid,oid,ListID,EditSequence) VALUES (". (int) $ID .",".$extra.",'".trim($idents['ListID'])."','".trim($idents['EditSequence'])."');");
}

function _service_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');

	$row0 = mssql_fetch_array(mssql_query("select oid,srvid,ListID,EditSequence from jest_ext..jms_qb_ident_service_map where srvid=" . (int) $ID));
	$row1 = mssql_fetch_array(mssql_query("select pb_code from jest..offices where officeid=" . (int) $extra));
	$row9 = mssql_fetch_array(mssql_query("SELECT id,accid,item,bprice FROM jest..[".trim($row1['pb_code'])."accpbook] WHERE [id] =" . (int) $ID));
	
	$xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
	
		<ItemServiceModRq requestID="' . $requestID . '">
			<ItemServiceMod>
				<ListID>'. trim($row0['ListID']) .'</ListID>
				<EditSequence>'. trim($row0['EditSequence']) .'</EditSequence>
				<Name>'. htmlspecialchars(substr(trim($row9['item']),0,30)) .'</Name>
			</ItemServiceMod>
		</ItemServiceModRq>
	
	</QBXMLMsgsRq>
</QBXML>';
	
	return $xmlout;
}

function _service_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest_ext');
	mssql_query("UPDATE jms_qb_ident_service_map SET EditSequence='".trim($idents['EditSequence'])."' WHERE ListID='".trim($idents['ListID'])."';");
}

function _noninventory_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	//$idata=get_serviceitem_data_pkg($extra,$ID);
	
    mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');

    $qry0 ="select officeid,pb_code from jest..offices where officeid=" . (int) $extra . ";";
    $res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 0)
    {
        $qry1 = "
                SELECT
                    A.invid,A.accid,A.phsid,A.item,A.bprice,
                    (SELECT ListID FROM jest..phasebase WHERE phsid=A.phsid) as ListID,
                    (SELECT ParentID FROM jest..phasebase WHERE phsid=A.phsid) as ParentName,
                    (SELECT AccountName FROM jest..phasebase WHERE phsid=A.phsid) as AccountName
                FROM
                    jest..[".trim($row0['pb_code'])."inventory] as A
                WHERE A.[invid] =" . (int) $ID .";
                ";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);
        $nrow1= mssql_num_rows($res1);
        
        if ($nrow1 == 1)
        {
            $idata = array(
                'alive'=>       true,
                'iid'=>         $row1['invid'],
                'accid'=>       $row1['accid'],
                'itemname'=>    $row1['item'],
                'baseprice'=>   $row1['bprice'],
                'ListID'=>      $row1['ListID'],
                'ParentName'=>  $row1['ParentName'],
                'AccountName'=> $row1['AccountName']
            );
        }
    }
    
    //if ($idata['alive'])
    //{
        $xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
	
		<ItemNonInventoryAddRq requestID="' . $requestID . '">
			<ItemNonInventoryAdd>
				<Name>'. htmlspecialchars(substr(trim($idata['itemname']),0,30)) .'</Name>
                <ParentRef>
                    <FullName>'. trim($idata['ParentName']) .'</FullName>
                </ParentRef>
				<SalesOrPurchase>
					<Desc>'. trim($idata['accid']) .'</Desc>
					<Price>'. number_format($idata['baseprice'],2,'.','') .'</Price>
					<AccountRef>
						<FullName>'. trim($idata['AccountName']) .'</FullName>
					</AccountRef>
				</SalesOrPurchase>
			</ItemNonInventoryAdd>
		</ItemNonInventoryAddRq>
	
	</QBXMLMsgsRq>
</QBXML>';
    //}
    
    //$fp = fopen('serviceitemadd_process.log', 'a+');
    //fwrite($fp, print_r($idata));
    //fclose($fp);
    
	return $xmlout;
}

function _noninventory_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	mssql_connect('CORP-DB02','sa','date1995');
	mssql_select_db('jest');
	
    $row1 = mssql_fetch_array(mssql_query("select officeid,pb_code from jest..offices where officeid=" . (int) $extra));
	mssql_query("UPDATE [jest]..[".trim($row1['pb_code'])."inventory] SET ListID='".trim($idents['ListID'])."',EditSequence='".trim($idents['EditSequence'])."' WHERE [officeid]=".$row1['officeid']." and [invid]=". (int) $ID .";");
}

function _itemquery_responseOLD($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	//return $xml;
	
	/*
	if (count($idents) > 0)
	{
		foreach ($idents as $n => $v)
		{
			if ($n==='ListID')
			{
				mssql_query("INSERT INTO [qb_query_response] (oid,pid,qtext) VALUES (".$extra.",".$ID.",'".$v."');");
			}
		}
	}
	*/
	
	//mssql_query("INSERT INTO [qb_query_response] (oid,pid,qtext) VALUES (".$extra.",".$ID.",'".$v."');");
	
	$fperr = fopen('QB_itemquery_Response.log', 'a+');
	fwrite($fperr, var_dump($idents));
	fclose($fperr);
}

function _itemquery_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
		$xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
		
		<ItemQueryRq requestID="' . $requestID . '">
			<ActiveStatus>All</ActiveStatus>
		</ItemQueryRq>
	
	</QBXMLMsgsRq>
</QBXML>';
	
	return $xmlout;
}

function _query_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	mssql_connect('CORP-DB02', 'sa', 'date1995');
    mssql_select_db('jest_ext');
	mssql_query("INSERT INTO [qb_query_response] (oid,pid,qbxml_response) VALUES (".$extra.",".$ID.",'".$xml."');");
}