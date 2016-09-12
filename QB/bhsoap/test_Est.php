<?php

ini_set('display_errors','On');
error_reporting(E_ALL);

//require('../Quickbooks.php');

echo 'START '.time().'<BR>';
$requestID='';
$extra=200;
$ID=2;

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
                'baseprice'=>   number_format($row1['bprice'],2,'.',''),
                'ListID'=>      $row1['ListID'],
                'ParentName'=>  $row1['ParentName'],
                'AccountName'=> $row1['AccountName']
            );
        }
        
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
					<Price>'. $idata['baseprice'] .'</Price>
					<AccountRef>
						<FullName>'. trim($idata['AccountName']) .'</FullName>
					</AccountRef>
				</SalesOrPurchase>
			</ItemServiceAdd>
		</ItemServiceAddRq>
	
	</QBXMLMsgsRq>
</QBXML>';
    }
    
echo '<pre>';
//print_r($xmlout);
//print_r($idata);
echo $xmlout;
echo '</pre><br>';
echo 'END '.time().'<BR>';

?>