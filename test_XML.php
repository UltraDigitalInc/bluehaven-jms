<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
date_default_timezone_set('America/Los_Angeles');

function parse_email_XML($c)
{
	$p = xml_parser_create();
	xml_parse_into_struct($p,trim($c),$v,$i);
	xml_parser_free($p);
	return array($v,$i);
}

function formed_dbase_query($email)
{
	$out  = '';
	$out .= "INSERT INTO lead_inc (";
	$out .= 'submitted,';
	$out .= 'zip,';
	$out .= (!empty($email['PHONE'])) ? 'phone,':'';
	$out .= (!empty($email['EMAIL'])) ? 'email,':'';
	$out .= (!empty($email['FIRST'])) ? 'fname,':'';
	$out .= 'lname,';
	$out .= (!empty($email['STREET'])) ? 'addr,':'';	
	$out .= (!empty($email['CITY'])) ? 'city,':'';
	$out .= (!empty($email['STATE'])) ? 'state,':'';	
	$out .= (!empty($email['XTRA'])) ? 'comments,':'';	
	$out .= "opt1,";
	$out .= "opt2,";
	$out .= "opt3,";
	$out .= "opt4,";
	$out .= "source";
	$out .= ") VALUES (";
	$out .= (!empty($email['SUBMITTED'])) ? "'".$email['SUBMITTED']."',": "'".date('m/d/Y H:i:s',time())."',";
	$out .= (!empty($email['ZIP'])) ? "'".$email['ZIP']."',":"'00000',";
	$out .= (!empty($email['PHONE'])) ? "'".$email['PHONE']."',":'';
	$out .= (!empty($email['EMAIL'])) ? "'".$email['EMAIL']."',":'';
	$out .= (!empty($email['FIRST'])) ? "'".$email['FIRST']."',":'';
	$out .= (!empty($email['LAST'])) ? "'".$email['LAST']."',":"'Not Provided',";
	$out .= (!empty($email['STREET'])) ? "'".$email['STREET']."',":'';
	$out .= (!empty($email['CITY'])) ? "'".$email['CITY']."',":'';
	$out .= (!empty($email['STATE'])) ? "'".$email['STATE']."',":'';
	$out .= (!empty($email['XTRA'])) ? "'".$email['XTRA']."',":'';
	$out .= "'0',";
	$out .= "'0',";
	$out .= "'0',";
	$out .= "'0',";
	$out .= "'0');";
	$out .= " SELECT @@IDENTITY;";
	
	return $out;
}

function create_proc_array_from_xml($src)
{
	$out	=array('XTRA'=>'');
	$ntags	=array('LEAD','NAME','ADDRESS','LIKES');
	$ttags	=array('FIRST','LAST');
	$utags	=array('SUBMITTED','FIRST','LAST','PHONE','EMAIL','STREET','CITY','STATE','ZIP'); //usable tags
	$xtags	=array('LIKE1','LIKE2','LIKE3','LIKE4','COMMENTS','HOW'); //xtra tags
	
	$xml_ar=parse_email_XML($src);
	$x= (array_key_exists('FIRST',$xml_ar[1])||array_key_exists('LAST',$xml_ar[1])) ? true:false;

	foreach($xml_ar[0] as $n=>$v)
	{
		if (!in_array($v['tag'],$ntags))
		{
			$tval = (!empty($v['value']))? $v['value'] : '';
			
			if (in_array($v['tag'],$xtags))
			{
				$out['XTRA']=$out['XTRA'].$v['tag'].': '.htmlspecialchars(trim($tval)).'\n\r';
				
			}
			else
			{
				$out[$v['tag']]=htmlspecialchars(trim($tval));
			}
		}
	}
	
	if (!$x)
	{
		foreach($xml_ar[0] as $n1=>$v1)
		{
			if ($v1['tag']=='NAME')
			{
				$out['LAST']=$v1['value'];
			}
		}
	}
	
	if (empty($out['XTRA']))
	{
		unset($out['XTRA']);
	}
	
	return $out;
}

$xml1='<?xml version="1.0" encoding="ISO-8859-1"?>
<lead>
  <name>
     <first></first>
     <last></last>
  </name>
  <phone>123456</phone>
  <address>
     <street></street>
     <city></city>
     <state>aaa</state>
     <zip></zip>
  </address>
  <likes>
     <like1>in-home</like1>
     <like2>telephone pool estimate</like2>
     <like3>xxxx</like3>
     <like4></like4>
  </likes>
  <comments>qwerty</comments>
  <how>other</how>
</lead>';

$xml2='<?xml version="1.0" encoding="ISO-8859-1"?>
<lead>
  <name>
     <first>abc</first>
     <last>xyz</last>
  </name>
  <phone>123456</phone>
  <email>abc@gmail.com</email>
  <address>
     <street>1 xyz st.</street>
     <city>New York</city>
     <state>aaa</state>
     <zip>111</zip>
  </address>
  <likes>
     <like1>in-home</like1>
     <like2>telephone pool estimate</like2>
     <like3>xxxx</like3>
     <like4></like4>
  </likes>
  <comments>qwerty</comments>
  <how>other</how>
  <submitted>08 Nov 2004 06:47:10</submitted>
</lead>';

$xml3='<?xml version="1.0" encoding="ISO-8859-1"?>
<lead>
  <name>asd zxc</name>
  <phone>123456</phone>
  <email>abc@gmail.com</email>
  <address>qaz wsx</address>
  <zip>111222</zip>
  <submitted>08 Nov 2004 06:47:10</submitted>
</lead>';

$cp=create_proc_array_from_xml($xml3);
//echo '<pre>';
echo formed_dbase_query($cp);
//print_r(create_proc_array($xml));
//echo '</pre>';
