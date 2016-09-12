<?php

ini_set('display_errors','On');
error_reporting(E_ALL);

$ID=183971;
$requestID=1;


	mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
	mssql_select_db('jest') or die("Table unavailable");
	$row = mssql_fetch_array(mssql_query('SELECT * FROM cinfo WHERE cid =' . (int) $ID));

	// Create and return a qbXML request
	$xml = '<?xml version="1.0" encoding="utf-8"?>
		<?qbxml version="8.0"?>
		<QBXML>
			<QBXMLMsgsRq onError="stopOnError">
				<CustomerAddRq requestID="' . $requestID . '">
					<CustomerAdd>
						<Name>'. ucwords(strtolower(trim($row['clname']))) .' '. ucwords(strtolower(trim($row['cfname']))) .' ('. $ID .')</Name>
						<CompanyName>'. ucwords(strtolower(trim($row['clname']))) .' '. ucwords(strtolower(trim($row['cfname']))) .'</CompanyName>
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

echo $xml;

?>