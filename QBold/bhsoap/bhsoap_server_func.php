<?php


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


function _quickbooks_customer_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// You'd probably do some database access here to pull the record with 
	//	ID = $ID from your database and build a request to add that particular 
	//	customer to QuickBooks. 
	//	
	// So, when you implement this for your business, you'd probably do 
	//	something like this...:
	
	// Fetch your customer record from your database
	mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");
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

/**
 * Receive a response from QuickBooks 
 * 
 * @param string $requestID					The requestID you passed to QuickBooks previously
 * @param string $action					The action that was performed (CustomerAdd in this case)
 * @param mixed $ID							The unique identifier of the record
 * @param array $extra			
 * @param string $err						An error message, assign a valid to $err if you want to report an error
 * @param integer $last_action_time			A unix timestamp (seconds) indicating when the last action of this type was dequeued (i.e.: for CustomerAdd, the last time a customer was added, for CustomerQuery, the last time a CustomerQuery ran, etc.)
 * @param integer $last_actionident_time	A unix timestamp (seconds) indicating when the combination of this action and ident was dequeued (i.e.: when the last time a CustomerQuery with ident of get-new-customers was dequeued)
 * @param string $xml						The complete qbXML response
 * @param array $idents						An array of identifiers that are contained in the qbXML response
 * @return void
 */
function _quickbooks_customer_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	// Great, customer $ID has been added to QuickBooks with a QuickBooks 
	//	ListID value of: $idents['ListID']
	// 
	// We probably want to store that ListID in our database, so we can use it 
	//	later. (You'll need to refer to the customer by either ListID or Name 
	//	in other requests, say, to update the customer or to add an invoice for 
	//	the customer. 
	
	/*
	mysql_query("UPDATE your_customer_table SET quickbooks_listid = '" . mysql_escape_string($idents['ListID']) . "' WHERE your_customer_ID_field = " . (int) $ID);
	*/
	
	$link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
	mssql_query("INSERT INTO [jest_ext]..[jms_qb_ident_customer_map] (cid,ListID,EditSequence) VALUES (". (int) $ID .",'".trim($idents['ListID'])."','".trim($idents['EditSequence'])."');");
	
	mssql_close($link);
}

function _quickbooks_customer_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");
	$row = mssql_fetch_array(mssql_query('SELECT * FROM cinfo WHERE cid=' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="2.0"?>
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

function _quickbooks_customer_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	$link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
	mssql_query("UPDATE jest_ext..jms_qb_ident_customer_map SET EditSequence='".trim($idents['EditSequence'])."' WHERE ListID='".trim($idents['ListID'])."';");
}

/** 
 * 
 * @param string $requestID					You should include this in your qbXML request (it helps with debugging later)
 * @param string $action					The QuickBooks action being performed (CustomerAdd in this case)
 * @param mixed $ID							The unique identifier for the record (maybe a customer ID number in your database or something)
 * @param array $extra						Any extra data you included with the queued item when you queued it up
 * @param string $err						An error message, assign a value to $err if you want to report an error
 * @param integer $last_action_time			A unix timestamp (seconds) indicating when the last action of this type was dequeued (i.e.: for CustomerAdd, the last time a customer was added, for CustomerQuery, the last time a CustomerQuery ran, etc.)
 * @param integer $last_actionident_time	A unix timestamp (seconds) indicating when the combination of this action and ident was dequeued (i.e.: when the last time a CustomerQuery with ident of get-new-customers was dequeued)
 * @param float $version					The max qbXML version your QuickBooks version supports
 * @param string $locale					
 * @return string							A valid qbXML request
 */
function _quickbooks_salesreceipt_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	/*
		<CustomerRef>
			<ListID>80003579-1231522938</ListID>
		</CustomerRef>	
	*/
	
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="2.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<SalesReceiptAddRq requestID="' . $requestID . '">
					<SalesReceiptAdd>
						<CustomerRef>
							<FullName>Keith Palmer Jr.</FullName>
						</CustomerRef>
						<TxnDate>2009-01-09</TxnDate>
						<RefNumber>16466</RefNumber>
						<BillAddress>
							<Addr1>Keith Palmer Jr.</Addr1>
							<Addr3>134 Stonemill Road</Addr3>
							<City>Storrs-Mansfield</City>
							<State>CT</State>
							<PostalCode>06268</PostalCode>
							<Country>United States</Country>
						</BillAddres>
						<SalesReceiptLineAdd>
							<ItemRef>
								<FullName>Gift Certificate</FullName>
							</ItemRef>
							<Desc>$25.00 gift certificate</Desc>
							<Quantity>1</Quantity>
							<Rate>25.00</Rate>
							<SalesTaxCodeRef>
								<FullName>NON</FullName>
							</SalesTaxCodeRef>
						</SalesReceiptLineAdd>
						<SalesReceiptLineAdd>
							<ItemRef>
								<FullName>Book</FullName>
							</ItemRef>
							<Desc>The Hitchhiker\'s Guide to the Galaxy</Desc>
							<Amount>19.95</Amount>
							<SalesTaxCodeRef>
								<FullName>TAX</FullName>
							</SalesTaxCodeRef>
						</SalesReceiptLineAdd>
					</SalesReceiptAdd>
				</SalesReceiptAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
	
	return $xml;
}

/**
 * Receive a response from QuickBooks 
 * 
 * @param string $requestID					The requestID you passed to QuickBooks previously
 * @param string $action					The action that was performed (CustomerAdd in this case)
 * @param mixed $ID							The unique identifier of the record
 * @param array $extra			
 * @param string $err						An error message, assign a valid to $err if you want to report an error
 * @param integer $last_action_time			A unix timestamp (seconds) indicating when the last action of this type was dequeued (i.e.: for CustomerAdd, the last time a customer was added, for CustomerQuery, the last time a CustomerQuery ran, etc.)
 * @param integer $last_actionident_time	A unix timestamp (seconds) indicating when the combination of this action and ident was dequeued (i.e.: when the last time a CustomerQuery with ident of get-new-customers was dequeued)
 * @param string $xml						The complete qbXML response
 * @param array $idents						An array of identifiers that are contained in the qbXML response
 * @return void
 */
function _quickbooks_salesreceipt_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	// Great, sales receipt $ID has been added to QuickBooks with a QuickBooks 
	//	TxnID value of: $idents['TxnID']
	//
	// The QuickBooks EditSequence is: $idents['EditSequence']
	// 
	// We probably want to store that TxnID in our database, so we can use it 
	//	later. You might also want to store the EditSequence. If you wanted to 
	//	issue a SalesReceiptMod to modify the sales receipt somewhere down the 
	//	road, you'd need to refer to the sales receipt using the TxnID and 
	//	EditSequence 
}

/**
 * Catch and handle a "that string is too long for that field" error (err no. 3070) from QuickBooks
 * 
 * @param string $requestID			
 * @param string $action
 * @param mixed $ID
 * @param mixed $extra
 * @param string $err
 * @param string $xml
 * @param mixed $errnum
 * @param string $errmsg
 * @return void
 */
function _quickbooks_error_stringtoolong($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg)
{
	mail('your-email@your-domain.com', 
		'QuickBooks error occured!', 
		'QuickBooks thinks that ' . $action . ': ' . $ID . ' has a value which will not fit in a QuickBooks field...');
}

function _quickbooks_estimate_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	// You'd probably do some database access here to pull the record with 
	//	ID = $ID from your database and build a request to add that particular 
	//	customer to QuickBooks. 
	//	
	// So, when you implement this for your business, you'd probably do 
	//	something like this...:
	
	// Fetch your customer record from your database
	//mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	//mssql_select_db('jest') or die("Table unavailable");
	//$row = mssql_fetch_array(mssql_query('SELECT * FROM cinfo WHERE cid =' . (int) $ID));
	
	/*
	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="2.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerAddRq requestID="' . $requestID . '">
					<CustomerAdd>
						<Name>'. ucwords(strtolower(trim($row['cfname']))) .' '. ucwords(strtolower(trim($row['clname']))) .' ('. $ID .')</Name>
						<CompanyName>'. ucwords(strtolower(trim($row['cfname']))) .' '. ucwords(strtolower(trim($row['clname']))) .'</CompanyName>
						<FirstName>'. ucwords(strtolower(trim($row['cfname']))) .'</FirstName>
						<LastName>'. ucwords(strtolower(trim($row['clname']))) .'</LastName>
						<BillAddress>
							<Addr1>'. ucwords(strtolower(trim($row['caddr1']))) .'</Addr1>
							<City>'. ucwords(strtolower(trim($row['ccity']))) .'</City>
							<State>'. trim($row['cstate']) .'</State>
							<PostalCode>'. trim($row['czip1']) .'</PostalCode>
							<Country>United States</Country>
						</BillAddress>
						<Phone>'. trim($row['chome']) .'</Phone>
						<Email>'. trim($row['cemail']) .'</Email>
						<Contact>'. ucwords(strtolower(trim($row['cfname']))) .' '. ucwords(strtolower(trim($row['clname']))) .'</Contact>
					</CustomerAdd>
				</CustomerAddRq>
			</QBXMLMsgsRq>
		</QBXML>';
		
	return $xml;
	*/
	
//$UUIDTYPE = '';
$MACROTYPE = 'MACROTYPE';
$IDTYPE = '80000019-1286994954';
$STRTYPE = 'Test Entry';
$DATETYPE = '10/13/2010';
$BOOLTYPE = 1;
$FLOATTYPE = '';
$QUANTYPE = '';
$PERCENTTYPE = '';
$PRICETYPE = '';
$GUIDTYPE = '';
$AMTTYPE = '';

$xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="2.0"?>
  <QBXML>
    <QBXMLMsgsRq onError="stopOnError">
      <EstimateAddRq>
        <EstimateAdd>
          <CustomerRef>
            <ListID>'. $IDTYPE .'</ListID>
          </CustomerRef>
          <TxnDate>'. $DATETYPE .'</TxnDate>
          <RefNumber>'. $STRTYPE .'</RefNumber>
          <IsActive>'. $BOOLTYPE .'</IsActive>
          <PONumber>'. $STRTYPE .'</PONumber>
          <TermsRef>
            <FullName>'. $STRTYPE .'</FullName>
          </TermsRef>
          <DueDate>'. $DATETYPE .'</DueDate>
          <SalesRepRef>
            <FullName>'. $STRTYPE .'</FullName>
          </SalesRepRef>
          <FOB>'. $STRTYPE .'</FOB>
          <ItemSalesTaxRef>
            <FullName>'. $STRTYPE .'</FullName>
          </ItemSalesTaxRef>
          <Memo>'. $STRTYPE .'</Memo>
          <CustomerMsgRef>
            <FullName>'. $STRTYPE .'</FullName> 
          </CustomerMsgRef>
          <IsToBeEmailed>'. $BOOLTYPE .'</IsToBeEmailed>
          <IsTaxIncluded>'. $BOOLTYPE .'</IsTaxIncluded>
          <CustomerSalesTaxCodeRef>
            <FullName>'. $STRTYPE .'</FullName> 
          </CustomerSalesTaxCodeRef>
		  <EstimateLineAdd>
            <ItemRef>
              <FullName>Test Item line 1</FullName>
            </ItemRef>
            <Quantity>2</Quantity>
          </EstimateLineAdd>
          <Other>'. $STRTYPE .'</Other> 
        </EstimateAdd>
      </EstimateAddRq>
    </QBXMLMsgsRq>
  </QBXML>
  ';
  
  print $xmlout;
}

function _quickbooks_estimate_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	$link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
	//mssql_query("UPDATE jest..jms_qb_ident_map SET ListID='".trim($idents['ListID'])."' WHERE cid=". (int) $ID.";");
}

function _quickbooks_inventory_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");
	
	$row1 = mssql_fetch_array(mssql_query("select pb_code from jest..offices where officeid=" . (int) $extra));
	$row9 = mssql_fetch_array(mssql_query("select item,vpno,bprice from [".trim($row1['pb_code'])."inventory] where [invid]=" . (int) $ID));
	
	$xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
		
		<ItemInventoryAddRq requestID="'. $requestID .'">
			<ItemInventoryAdd>
				<Name>'.htmlspecialchars(substr(trim($row9['item']),0,30)).'</Name>
				<ManufacturerPartNumber>'.htmlspecialchars(substr(trim($row9['vpno']),0,30)).'</ManufacturerPartNumber>
				<SalesPrice>'. number_format($row9['bprice'],2,'.','') .'</SalesPrice>
				<IncomeAccountRef>
					<FullName>Sales</FullName>
				</IncomeAccountRef>
				<COGSAccountRef>
					<FullName>Sales</FullName>
				</COGSAccountRef>
				<AssetAccountRef>
					<FullName>Sales</FullName>
				</AssetAccountRef>
				<ReorderPoint>1</ReorderPoint>
				<QuantityOnHand>1</QuantityOnHand>
				<TotalValue>1.00</TotalValue>
			</ItemInventoryAdd>
		</ItemInventoryAddRq>
	
	</QBXMLMsgsRq>
</QBXML>';
	
	return $xmlout;
}

function _quickbooks_inventory_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	// Great, customer $ID has been added to QuickBooks with a QuickBooks 
	//	ListID value of: $idents['ListID']
	// 
	// We probably want to store that ListID in our database, so we can use it 
	//	later. (You'll need to refer to the customer by either ListID or Name 
	//	in other requests, say, to update the customer or to add an invoice for 
	//	the customer.
	
	$link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
	
	//mssql_query("INSERT INTO jest_ext..jms_qb_ident_inventory_map SET ListID='".trim($idents['ListID'])."' WHERE lid=". (int) $ID.";");
	mssql_query("INSERT INTO [jest_ext]..[jms_qb_ident_inventory_map] (invid,oid,ListID,EditSequence) VALUES (". (int) $ID .",".$extra.",'".trim($idents['ListID'])."','".trim($idents['EditSequence'])."');");
}

function _quickbooks_inventory_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	//include ('inventoryadd_qbXML.php');
	
	mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");
	
	$row0 = mssql_fetch_array(mssql_query("select oid,invid,ListID,EditSequence from jest_ext..jms_qb_ident_inventory_map where invid=" . (int) $ID));
	$row1 = mssql_fetch_array(mssql_query("select pb_code from jest..offices where officeid=" . (int) $row0['oid']));
	$row9 = mssql_fetch_array(mssql_query("SELECT item,bprice FROM [".trim($row1['pb_code'])."inventory] WHERE invid =" . (int) $row0['invid']));
	
	$xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="2.0"?>
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

function _quickbooks_inventory_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	// Great, customer $ID has been added to QuickBooks with a QuickBooks 
	//	ListID value of: $idents['ListID']
	// 
	// We probably want to store that ListID in our database, so we can use it 
	//	later. (You'll need to refer to the customer by either ListID or Name 
	//	in other requests, say, to update the customer or to add an invoice for 
	//	the customer.
	
	$link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
	
	mssql_query("UPDATE jest_ext..jms_qb_ident_inventory_map SET EditSequence='".trim($idents['EditSequence'])."' WHERE ListID='".trim($idents['ListID'])."';");
}

function _quickbooks_service_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	//include ('inventoryadd_qbXML.php');
	
	mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");

	$row1 = mssql_fetch_array(mssql_query("select pb_code from jest..offices where officeid=" . (int) $extra));
	$row9 = mssql_fetch_array(mssql_query("SELECT accid,item,bprice FROM jest..[".trim($row1['pb_code'])."accpbook] WHERE [id] =" . (int) $ID));
	
	$xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
	
		<ItemServiceAddRq requestID="' . $requestID . '">
			<ItemServiceAdd>
				<Name>'. htmlspecialchars(substr(trim($row9['item']),0,30)) .'</Name>
				<SalesOrPurchase>
					<Desc>'. trim($row9['accid']) .'</Desc>
					<Price>'. number_format($row9['bprice'],2,'.','') .'</Price>
					<AccountRef>
						<FullName>Sales</FullName>
					</AccountRef>
				</SalesOrPurchase>
			</ItemServiceAdd>
		</ItemServiceAddRq>
	
	</QBXMLMsgsRq>
</QBXML>';
	
	return $xmlout;
}

function _quickbooks_service_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	
	$link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }

	mssql_query("INSERT INTO [jest_ext]..[jms_qb_ident_service_map] (srvid,oid,ListID,EditSequence) VALUES (". (int) $ID .",".$extra.",'".trim($idents['ListID'])."','".trim($idents['EditSequence'])."');");
}

function _quickbooks_service_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	//include ('inventoryadd_qbXML.php');
	
	mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");

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

function _quickbooks_service_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{
	
	$link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }

	mssql_query("UPDATE jest_ext..jms_qb_ident_service_map SET EditSequence='".trim($idents['EditSequence'])."' WHERE ListID='".trim($idents['ListID'])."';");
}

function _quickbooks_noninventory_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale)
{
	//include ('inventoryadd_qbXML.php');
	
	$xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
<QBXML>
	<QBXMLMsgsRq onError="stopOnError">
		<ItemNonInventoryAddRq>
			<ItemNonInventoryAdd>
				<Name>TEST NoNINVENTORY ITEM (nonTEST)</Name>
			</ItemNonInventoryAdd>
		</ItemNonInventoryAddRq>
	</QBXMLMsgsRq>
</QBXML>';
	return $xmlout;
}

function _quickbooks_noninventory_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents)
{	
	// Great, customer $ID has been added to QuickBooks with a QuickBooks 
	//	ListID value of: $idents['ListID']
	// 
	// We probably want to store that ListID in our database, so we can use it 
	//	later. (You'll need to refer to the customer by either ListID or Name 
	//	in other requests, say, to update the customer or to add an invoice for 
	//	the customer.
	
	$link = mssql_connect('CORP-DB02', 'sa', 'date1995');

    if (!$link)
    {
        die('Error! Unable to connect to database! ('. __LINE__ .')');
    }
    
    $dselect= mssql_select_db('jest', $link);
    
    if (!$dselect)
    {
        die('Error! Unable to select database! ('. __LINE__ .')');
    }
    
	//mssql_query("UPDATE jest_ext..jms_qb_ident_inventory_map SET ListID='".trim($idents['ListID'])."' WHERE invid=". (int) $ID.";");
	mssql_query("UPDATE jest_ext..jms_qb_ident_noninventory_map SET ListID='".trim($idents['ListID'])."' WHERE qid=". (int) $ID.";");
}

?>