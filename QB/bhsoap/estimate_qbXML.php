<?php

header('Content-type: application/xml');

ini_set('display_errors','On');
error_reporting(E_ALL);


$UUIDTYPE = '';
$MACROTYPE = '';
$IDTYPE = '';
$STRTYPE = '';
$DATETYPE = '';
$BOOLTYPE = '';
$FLOATTYPE = '';
$QUANTYPE = '';
$PERCENTTYPE = '';
$PRICETYPE = '';
$GUIDTYPE = '';
$AMTTYPE = '';

$xmlout='
<?qbxml version="2.0"?>
  <QBXML>
    <QBXMLMsgsRq onError="stopOnError">
      <EstimateAddRq requestID="'. $UUIDTYPE .'">
        <EstimateAdd defMacro="'. $MACROTYPE .'">
          <CustomerRef>
            <ListID>'. $IDTYPE .'</ListID>
            <FullName>'. $STRTYPE .'</FullName>
          </CustomerRef>
          <ClassRef>
            <ListID>'. $IDTYPE .'</ListID>
            <FullName>'. $STRTYPE .'</FullName>
          </ClassRef>
          <TemplateRef>
            <ListID>'. $IDTYPE .'</ListID>
            <FullName>'. $STRTYPE .'</FullName>
          </TemplateRef>
          <TxnDate>'. $DATETYPE .'</TxnDate>
          <RefNumber>'. $STRTYPE .'</RefNumber>
          <BillAddress>
            <Addr1>'. $STRTYPE .'</Addr1> 
            <Addr2>'. $STRTYPE .'</Addr2> 
            <Addr3>'. $STRTYPE .'</Addr3> 
            <Addr4>'. $STRTYPE .'</Addr4> 
            <Addr5>'. $STRTYPE .'</Addr5>
            <City>'. $STRTYPE .'</City>
            <State>'. $STRTYPE .'</State>
            <PostalCode>'. $STRTYPE .'</PostalCode>
            <Country>'. $STRTYPE .'</Country>
            <Note>'. $STRTYPE .'</Note>
          </BillAddress>
          <ShipAddress>
            <Addr1>'. $STRTYPE .'</Addr1>
            <Addr2>'. $STRTYPE .'</Addr2>
            <Addr3>'. $STRTYPE .'</Addr3>
            <Addr4>'. $STRTYPE .'</Addr4>
            <Addr5>'. $STRTYPE .'</Addr5>
            <City>'. $STRTYPE .'</City>
            <State>'. $STRTYPE .'</State>
            <PostalCode>'. $STRTYPE .'</PostalCode>
            <Country>'. $STRTYPE .'</Country>
            <Note>'. $STRTYPE .'</Note>
          </ShipAddress>
          <IsActive>'. $BOOLTYPE .'</IsActive>
          <PONumber>'. $STRTYPE .'</PONumber>
          <TermsRef>
            <ListID>'. $IDTYPE .'</ListID>
            <FullName>'. $STRTYPE .'</FullName>
          </TermsRef>
          <DueDate>'. $DATETYPE .'</DueDate>
          <SalesRepRef>
            <ListID>'. $IDTYPE .'</ListID>
            <FullName>'. $STRTYPE .'</FullName>
          </SalesRepRef>
          <FOB>'. $STRTYPE .'</FOB>
          <ItemSalesTaxRef>
            <ListID>'. $IDTYPE .'</ListID>
            <FullName>'. $STRTYPE .'</FullName>
          </ItemSalesTaxRef>
          <Memo>'. $STRTYPE .'</Memo>
          <CustomerMsgRef>
            <ListID>'. $IDTYPE .'</ListID> 
            <FullName>'. $STRTYPE .'</FullName> 
          </CustomerMsgRef>
          <IsToBeEmailed>'. $BOOLTYPE .'</IsToBeEmailed>
          <IsTaxIncluded>'. $BOOLTYPE .'</IsTaxIncluded>
          <CustomerSalesTaxCodeRef>
            <ListID>'. $IDTYPE .'</ListID> 
            <FullName>'. $STRTYPE .'</FullName> 
          </CustomerSalesTaxCodeRef>
          <Other>'. $STRTYPE .'</Other> 
          <ExchangeRate>'. $FLOATTYPE .'</ExchangeRate> 
      <!- BEGIN OR: You may have 1 or more EstimateLineAdd OR EstimateLineGroupAdd  --> 
          <EstimateLineAdd>
            <ItemRef>
              <ListID>'. $IDTYPE .'</ListID> 
              <FullName>'. $STRTYPE .'</FullName> 
            </ItemRef>
            <Desc>'. $STRTYPE .'</Desc> 
            <Quantity>'. $QUANTYPE .'</Quantity> 
            <UnitOfMeasure>'. $STRTYPE .'</UnitOfMeasure> 
      <!- BEGIN OR: You may optionally have Rate OR RatePercent --> 
            <Rate>'. $PRICETYPE .'</Rate>
            <RatePercent>'. $PERCENTTYPE .'</RatePercent>
            <ClassRef>
              <ListID>'. $IDTYPE .'</ListID> 
              <FullName>'. $STRTYPE .'</FullName> 
            </ClassRef>
            <Amount>'. $AMTTYPE .'</Amount> 
            <TaxAmount>'. $AMTTYPE .'</TaxAmount>
            <SalesTaxCodeRef>
              <ListID>'. $IDTYPE .'</ListID> 
              <FullName>'. $STRTYPE .'</FullName>
            </SalesTaxCodeRef>
      <!- BEGIN OR: You may optionally have MarkupRate OR MarkupRatePercent OR PriceLevelRef -->
            <MarkupRate>'. $PRICETYPE .'</MarkupRate>
            <MarkupRatePercent>'. $PERCENTTYPE .'</MarkupRatePercent>
            <PriceLevelRef>
              <ListID>'. $IDTYPE .'</ListID>
              <FullName>'. $STRTYPE .'</FullName>
            </PriceLevelRef>
      <!- END OR --> 
            <OverrideItemAccountRef>
              <ListID>'. $IDTYPE .'</ListID>
              <FullName>'. $STRTYPE .'</FullName> 
            </OverrideItemAccountRef>
            <Other1>'. $STRTYPE .'</Other1>
            <Other2>'. $STRTYPE .'</Other2>
            <DataExt>
              <OwnerID>'. $GUIDTYPE .'</OwnerID> 
              <DataExtName>'. $STRTYPE .'</DataExtName> 
              <DataExtValue>'. $STRTYPE .'</DataExtValue> 
            </DataExt>
          </EstimateLineAdd>
      <!- OR  --> 
          <EstimateLineGroupAdd>
            <ItemGroupRef>
              <ListID>'. $IDTYPE .'</ListID>
              <FullName>'. $STRTYPE .'</FullName>
            </ItemGroupRef>
            <Desc>'. $STRTYPE .'</Desc>
            <Quantity>'. $QUANTYPE .'</Quantity>
            <UnitOfMeasure>'. $STRTYPE .'</UnitOfMeasure>
            <DataExt>
              <OwnerID>'. $GUIDTYPE .'</OwnerID>
              <DataExtName>'. $STRTYPE .'</DataExtName>
              <DataExtValue>'. $STRTYPE .'</DataExtValue> 
            </DataExt>
          </EstimateLineGroupAdd>
      <!- END OR --> 
        </EstimateAdd>
        <IncludeRetElement>'. $STRTYPE .'</IncludeRetElement>
      </EstimateAddRq>
    </QBXMLMsgsRq>
  </QBXML>
  ';
  
  print $xmlout;