<?php

header('Content-type: application/xml');

ini_set('display_errors','On');
error_reporting(E_ALL);

$MACROTYPE = '';
$IDTYPE = '';
$STRTYPE = '';
$DATETYPE = '';
$DATETIMETYPE = '';
$BOOLTYPE = '';
$FLOATTYPE = '';
$QUANTYPE = '';
$PERCENTTYPE = '';
$PRICETYPE = '';
$GUIDTYPE = '';
$AMTTYPE = '';

$xmlout='<?xml version="1.0" encoding="utf-8"?>
<?qbxml version="8.0"?>
  <QBXML>
    <QBXMLMsgsRq onError="stopOnError"> 
      <ItemInventoryAddRq> 
        <ItemInventoryAdd> <!-- required --> 
          <Name>STRTYPE</Name> <!-- required -->
          <IsActive>BOOLTYPE</IsActive> <!-- optional -->
          <ParentRef> <!-- optional --> 
            <ListID>IDTYPE</ListID> <!-- optional -->
            <FullName>STRTYPE</FullName> <!-- optional -->
          </ParentRef>
          <ManufacturerPartNumber>STRTYPE</ManufacturerPartNumber> <!-- optional -->
          <UnitOfMeasureSetRef> <!-- optional --> 
            <ListID>IDTYPE</ListID> <!-- optional -->
            <FullName>STRTYPE</FullName> <!-- optional -->
          </UnitOfMeasureSetRef>
          <SalesTaxCodeRef> <!-- optional --> 
            <ListID>IDTYPE</ListID> <!-- optional -->
            <FullName>STRTYPE</FullName> <!-- optional -->
          </SalesTaxCodeRef>
          <SalesDesc>STRTYPE</SalesDesc> <!-- optional -->
          <SalesPrice>PRICETYPE</SalesPrice> <!-- optional -->
          <IncomeAccountRef> <!-- optional --> 
            <ListID>IDTYPE</ListID> <!-- optional -->
            <FullName>STRTYPE</FullName> <!-- optional -->
          </IncomeAccountRef>
          <PurchaseDesc>STRTYPE</PurchaseDesc> <!-- optional -->
          <PurchaseCost>PRICETYPE</PurchaseCost> <!-- optional -->
          <COGSAccountRef> <!-- optional --> 
            <ListID>IDTYPE</ListID> <!-- optional -->
            <FullName>STRTYPE</FullName> <!-- optional -->
          </COGSAccountRef>
          <PrefVendorRef> <!-- optional --> 
            <ListID>IDTYPE</ListID> <!-- optional -->
            <FullName>STRTYPE</FullName> <!-- optional -->
          </PrefVendorRef>
          <AssetAccountRef> <!-- optional --> 
            <ListID>IDTYPE</ListID> <!-- optional -->
            <FullName>STRTYPE</FullName> <!-- optional -->
          </AssetAccountRef>
          <ReorderPoint>QUANTYPE</ReorderPoint> <!-- optional -->
          <QuantityOnHand>QUANTYPE</QuantityOnHand> <!-- optional -->
          <TotalValue>AMTTYPE</TotalValue> <!-- optional -->
          <InventoryDate>DATETYPE</InventoryDate> <!-- optional -->
          <ExternalGUID>GUIDTYPE</ExternalGUID> <!-- optional -->
        </ItemInventoryAdd>
        <IncludeRetElement >STRTYPE</IncludeRetElement> <!-- optional, may repeat -->
      </ItemInventoryAddRq>
    </QBXMLMsgsRq>';