<?php

require_once 'XML/RPC.php';

$sourceURI = 'http://www.example.com/'; // doesn't matter here
$targetURI = $sourceURI;

$client = new XML_RPC_Client('/hubspot_lead.php', $_SERVER['HTTP_HOST']);
$client->setDebug(1);
$msg = new XML_RPC_Message('pingback.ping',
array(new XML_RPC_Value($sourceURI, 'string'),
new XML_RPC_Value($targetURI, 'string')));

$response = $client->send($msg, 0, 'http');

?>