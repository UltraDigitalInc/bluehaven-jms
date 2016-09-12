<?php

/**
 * Example of building qbXML requests using the QuickBooks_Object_* classes
 * 
 * 
 * 
 * @author Keith Palmer <keith@consolibyte.com>
 *
 * @package QuickBooks
 * @subpackage Documentation
 */ 

// include path
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../');

// error reporting
ini_set('display_errors', 1);
error_reporting(E_STRICT);

// QuickBooks framework classes
require_once 'QuickBooks.php';

	$Customer = new QuickBooks_Object_Customer(); 
	$Customer->setName('Keith Palmer'); 
	$Customer->setPhone('860-634-1602');
	
	
	$qbxml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">';
			
	$qbxml .= $Customer->asQBXML(QUICKBOOKS_ADD_CUSTOMER);
	
	$qbxml .= '</QBXMLMsgsRq></QBXML>';
	
print($qbxml);
 
// Prints the following XML: 
/*
<InvoiceAddRq>
	<InvoiceAdd>
		<CustomerRef>
			<FullName>The Company Name Here</FullName>
		</CustomerRef>
		<RefNumber>A-123</RefNumber>
		<Memo>This invoice was created using the QuickBooks PHP API!</Memo>
		<InvoiceLineAdd>
			<ItemRef>
				<FullName>Item Type 1</FullName>
			</ItemRef>
			<Quantity>3</Quantity>
			<Rate>10</Rate>
		</InvoiceLineAdd>
		<InvoiceLineAdd>
			<ItemRef>
				<FullName>Item Type 2</FullName>
			</ItemRef>
			<Quantity>5</Quantity>
			<Amount>225.00</Amount>
		</InvoiceLineAdd>
	</InvoiceAdd>
</InvoiceAddRq>
*/ 
