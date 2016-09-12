<?php

ini_set('display_errors','On');
error_reporting(E_ALL);

function setOfficeCreateOpts()
{
	$opts			=array();
	
	$opts['oidnew']	= 0;
	$opts['otypecd']= (isset($_REQUEST['otypecd']) and !empty($_REQUEST['otypecd']))? trim($_REQUEST['otypecd']):0;
	$opts['OLabel']	= (isset($_REQUEST['olabel']) and !empty($_REQUEST['olabel']))? trim($_REQUEST['olabel']):'';
	$opts['Name']	= (isset($_REQUEST['oname']) and !empty($_REQUEST['oname']))? trim($_REQUEST['oname']):'';
	$opts['Addr1']	= (isset($_REQUEST['oaddr1']) and !empty($_REQUEST['oaddr1']))? trim($_REQUEST['oaddr1']):'';
	$opts['Addr2']	= (isset($_REQUEST['oaddr2']) and !empty($_REQUEST['oaddr2']))? trim($_REQUEST['oaddr2']):'';
	$opts['City']	= (isset($_REQUEST['ocity']) and !empty($_REQUEST['ocity']))? trim($_REQUEST['ocity']):'';
	$opts['State']	= (isset($_REQUEST['ostate']) and !empty($_REQUEST['ostate']))? trim($_REQUEST['ostate']):'';
	$opts['Zip']	= (isset($_REQUEST['ozip']) and !empty($_REQUEST['ozip']))? trim($_REQUEST['ozip']):'';
	$opts['Phone']	= (isset($_REQUEST['ophone']) and !empty($_REQUEST['ophone']))? trim($_REQUEST['ophone']):'';
	
	$opts['gmsid']	= 0;
	$opts['gmlogid']= (isset($_REQUEST['gmlogid']) and !empty($_REQUEST['gmlogid']))? trim($_REQUEST['gmlogid']):'';
	$opts['gmpass']	= (isset($_REQUEST['gmpass']) and !empty($_REQUEST['gmpass']))? trim($_REQUEST['gmpass']):'';
	$opts['gmfirst']= (isset($_REQUEST['gmfirst']) and !empty($_REQUEST['gmfirst']))?trim($_REQUEST['gmfirst']):'';
	$opts['gmlast']	= (isset($_REQUEST['gmlast']) and !empty($_REQUEST['gmlast']))? trim($_REQUEST['gmlast']):'';
	
	$opts['oidsrc']	= (isset($_REQUEST['srcoffice']) and $_REQUEST['srcoffice']!=0)? trim($_REQUEST['srcoffice']):0;
	$opts['mvleads']= (isset($_REQUEST['mvleads']) and $_REQUEST['mvleads']==1)? true:false;
	$opts['mvzips']	= (isset($_REQUEST['mvzips']) and $_REQUEST['mvzips']==1)? true:false;
	$opts['cpyretail']=(isset($_REQUEST['cpyretail']) and $_REQUEST['cpyretail']==1)? true:false;
	$opts['cpycost']= (isset($_REQUEST['cpycost']) and $_REQUEST['cpycost']==1)? true:false;
	$opts['cpycomm']= (isset($_REQUEST['cpycomm']) and $_REQUEST['cpycomm']==1)? true:false;
	$opts['tleads']	= 0;
	$opts['tzips']	= 0;
	$opts['tretail']= 0;
	$opts['tcost']	= 0;
	$opts['tcomms']	= 0;
	
	return $opts;
}

function proc_AddOffice()
{
	ini_set('max_execution_time', 600);
	
	$out	= array();
	$opts	= setOfficeCreateOpts();
	$oiderr	= checkOfficeInfo($opts['Name'],$opts['Zip']);
	$otext	= '';
	
	if ($oiderr['name_err'] == 0 && $oiderr['zip_err'] == 0)
	{		
		$qry2  = "INSERT INTO offices (active,grouping,code,otype_code,label_masoff_code,name";
		$qry2 .= ",addr1,addr2,city,state,zip,phone,ringto,fsenable,fslimit,fscustomer,fsshared,fsoffice) values (";
		$qry2 .= "1,";
		$qry2 .= "0,";
		$qry2 .= "convert(int,'".$opts['Zip']."'),";
		$qry2 .= "'".$opts['otypecd']."',";
		$qry2 .= "'".$opts['OLabel']."',";
		$qry2 .= "'".$opts['Name']."',";
		$qry2 .= "'".$opts['Addr1']."',";
		$qry2 .= "'".$opts['Addr2']."',";
		$qry2 .= "'".$opts['City']."',";
		$qry2 .= "'".$opts['State']."',";
		$qry2 .= "'".$opts['Zip']."',";
		$qry2 .= "'".$opts['Phone']."',";
		$qry2 .= "'".$opts['Phone']."',";
		$qry2 .= "1,500,1,1,1";
		$qry2 .= "); ";
		$qry2 .= "SELECT @@IDENTITY;";
		$res2  = mssql_query($qry2);
		$row2  = mssql_fetch_row($res2);
		$opts['oidnew']= $row2[0];
		$out['oidnew']=$opts['oidnew'];
		
		if ($opts['oidnew'] > 0)
		{
			$otext.=$opts['Name'].' Office Created<br>';
		}
		
		if (isset($opts['oidnew']) and $opts['oidnew']!=0)
		{
			$opts['gmsid']=addUserNew($opts);
			
			if ($opts['gmsid'][0] > 0)
			{
				$otext.=$opts['gmsid'][1].'<br>';
			}
		}
		
		if (
			(isset($opts['oidnew']) and $opts['oidnew']!=0)
			and (isset($opts['oidsrc']) and $opts['oidsrc']!=0)
			and ($opts['mvleads'] or $opts['mvzips'] or $opts['cpyretail'] or $opts['cpycost'] or $opts['cpycomm'])
			)
		{			
			if ($opts['mvleads'] and $opts['gmsid'][0]!=0)
			{
				$opts['tleads']=moveLeads($opts['oidsrc'],$opts['oidnew'],$opts['gmsid'][0],$_SESSION['securityid']);
				$otext.=$opts['tleads'][3].'<br>';
			}
			
			if ($opts['mvzips'])
			{
				$opts['tzips']=moveZipMatrix($opts['oidsrc'],$opts['oidnew']);
				$otext.=$opts['tzips'][2].'<br>';
			}
			
			if ($opts['cpycomm'])
			{
				$opts['tcomm']=copycommissions($opts['oidsrc'],$opts['oidnew'],$_SESSION['securityid']);
				$otext.=$opts['tcomm']['otext'].'<br>';
			}
			
			if ($opts['cpyretail'])
			{
				$opts['tretail']=copyPricebookRetail($opts['oidsrc'],$opts['oidnew'],$_SESSION['securityid']);
				$otext.=$opts['tretail']['otext'].'<br>';
			}
			
			if ($opts['cpycost'])
			{
				$opts['tcost']=copyPriceBookCost($opts['oidsrc'],$opts['oidnew'],$_SESSION['securityid']);
				$otext.=$opts['tcost']['otext'].'<br>';
			}
		}
	}
	else
	{
		if ($oiderr['name_err']!=0)
		{
			$otext.="<br><font color=\"red\"><b>Error!</b></font> The Office Name <b>".trim($opts['Name'])."</b> already exists.";
		}
		
		if ($oiderr['zip_err']!=0)
		{
			$otext.="<br><font color=\"red\"><b>Error!</b></font> The Office Zip Code: <b>".trim($opts['Zip'])."</b> already exists.";
		}
	}
	
	$out['otext']=$otext;
	return $out;
}

function sendOfficeCreateMailer($opts)
{
	$to	 	 = "sschirmer@corp.bluehaven.com,thelton@corp.bluehaven.com";
	$sub	 = "New Office ".$opts['Name']."";
	$mess	 = "Name  : ".$opts['Name']."\r\n";
	$mess	 = "Zip   : ".$opts['Zip']."\r\n\r\n";
	
	if (isset($opts['mvleads']) and $opts['mvleads']!=0)
	{
		$mess	 = "      : ".$opts['mvleads']." Leads were moved\r\n\r\n";
	}
	
	if (isset($opts['mvzipmatrix']) and $opts['mvzipmatrix']!=0)
	{
		$mess	 = "      : ".$opts['mvleads']." Zip Codes were moved in the Matrix\r\n\r\n";
	}
	
	if (isset($opts['cppbretail']) and $opts['cppbretail']!=0)
	{
		$mess	 = "      : ".$opts['cppbretail']." Retail Pricebook entries were copied\r\n\r\n";
	}
	
	if (isset($opts['cppbcost']) and $opts['cppbcost']!=0)
	{
		$mess	 = "      : ".$opts['cppcost']." Cost Pricebook entries were copied\r\n\r\n";
	}
	
	if (isset($opts['cpcomms']) and $opts['cpcomms']!=0)
	{
		$mess	 = "      : ".$opts['cpcomms']." Commission Profiles were copied\r\n\r\n";
	}
	
	$mess	.= "----------------------\r\n";
	$mess	.= "Admin : ".$_SESSION['fname']." ".$_SESSION['lname']."\r\n";
	$mess	.= "LHost : ".$_SERVER['SERVER_NAME']."\r\n";
	$mess	.= "RHost : ".getenv('REMOTE_ADDR')."\r\n";
	$mess	.= "----------------------\r\n";
	
	echo $to.'<br>';
	echo $from.'<br>';
	echo $mess.'<br>';
	//office_mail_out($to,$sub,$mess);
}

function office_mail_out($to,$sub,$mess)
{
	ini_set('SMTP','192.168.1.17');
	ini_set('sendmail_from','jmsadmin@bluehaven.com');
	ini_set('sendmail_path','d:\tools\sendmail\sendmail.exe -t');

	$qry	= "SELECT ADMIN_ADDR FROM [jest]..[jest_config];";
	$res	= mssql_query($qry);
	$row 	= mssql_fetch_array($res);

	$to		=	$to;
	$head	=	"From: JMS Mail System <".$row['ADMIN_ADDR'].">\r\n" .
	"Reply-To: ".$row['ADMIN_ADDR']."\r\n" .
	"X-Mailer: PHP/" . phpversion();

	mail($to,$sub,$mess,$head);
}

function checkOfficeInfo($name,$zip)
{
	$out=array('name_err'=>0,'zip_err'=>0);
	$name_err	=0;
	$zip_err	=0;
	$eoffname_ar=array();
	$eoffzip_ar	=array();
	
	$qry1 = "SELECT name,zip FROM offices order by name;";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	if ($nrow1 > 0)
	{
		while ($row1 = mssql_fetch_array($res1))
		{
			$eoffname_ar[]	=trim($row1['name']);
			$eoffzip_ar[]	=trim($row1['zip']);
		}
	}
	
    if (isset($name) && !empty($name))
    {
        foreach ($eoffname_ar as $n1 => $v1)
        {
            if (trim($name)===$v1)
            {
                $out['name_err']++;
            }
        }
    }
	
    if (isset($zip) && !empty($zip))
    {
        foreach ($eoffzip_ar as $n2 => $v2)
        {
            if (trim($zip)===$v2)
            {
                $out['zip_err']++;
            }
        }
    }
	
	return $out;
}

function gen_randstring($l = 8)
{
    $c = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";    
    $rs = '';
    for ($i = 0; $i < $l; $i++)
    {
        $rs .= $c[rand(0, strlen($c) - 1)];
    }
    return $rs;
}

function addUserNew($opts)
{
	$out=array(0,'');
    if (isset($opts['oidnew']) and $opts['oidnew']!=0)
    {
        if ((isset($opts['gmlogid']) and !empty($opts['gmlogid'])) and (isset($opts['gmpass']) and !empty($opts['gmpass'])))
        {
            $gmlogid= $opts['gmlogid'];
            $gmpass	= md5($opts['gmpass']);
            $gmfirst= (isset($opts['gmfirst']) and !empty($opts['gmfirst']))? $opts['gmfirst']:'';
            $gmlast = (isset($opts['gmlast']) and !empty($opts['gmlast']))? $opts['gmlast']:'';
            $alevel	= '6,6,6,6,6,6,1';
        }
        else
        {
            do {
                $rs	=gen_randstring();
                $qry= "select login from jest..security where login='".$rs."';";
                $res= mssql_query($qry);
                $row= mssql_num_rows($res);
            }	
            while ($row > 0);
            
            $gmlogid= $rs;
            $gmpass	= md5($rs);
            $gmfirst= 'Temp';
            $gmlast = 'User';
            $alevel	= '0,0,0,1,0,0,1';
        }
        
        $qry  = "INSERT INTO jest..security ";
        $qry .= "(officeid,fname,lname,login,pswd,slevel,srep,hdate,email,newcommdate,stitle) ";
        $qry .= "VALUES ";
        $qry .= "(".$opts['oidnew'].",'".$gmfirst."','".$gmlast."','".$gmlogid."','".$gmpass."','".$alevel."',0,getdate(),'None',getdate(),'GM');";
        $qry .= "SELECT @@IDENTITY;";
        $res  = mssql_query($qry);
        $row  = mssql_fetch_row($res);
        $out[0]  = $row[0];
		
		if ($out[0]!=0)
		{
			$out[1]= $gmfirst.' '.$gmlast.' ('.$gmlogid.') Created';
			$qry  = "update jest..offices set gm=".$out[0].",am=".$out[0]." where officeid=".$opts['oidnew'].";";
			$res  = mssql_query($qry);	
		}
    }

	return $out;
}

function moveLeads($from,$to,$tsid,$psid)
{
    $out=array(false,0,0,'Lead Move Error: Nothing to do');
    
    if (isset($from) and $from!=0)
    {
        $cids=getcids($from);
        if (count($cids) > 0 and (isset($to) and $to!=0))
        {
            $cnt=0;
            foreach ($cids as $n => $v)
            {
                $qry = "UPDATE cinfo SET officeid=".(int) $to.",securityid=".(int) $tsid.",custid=cid WHERE officeid=".(int) $from." and cid=".(int) $v." ";
                $res = mssql_query($qry);
				$cnt++;
            }
            
            $out=array(true,count($cids),$cnt,$cnt.' Leads moved');
        }
        else
        {
            $out[3]='Lead Move Error: No Customers found';
        }
    }
    
    return $out;	
}

function moveZipMatrix($from,$to)
{
	$out=array(false,0,'');
	
	if ((isset($from) and $from!=0) and (isset($to) and $to!=0))
    {
		$qry0 = "select zip from jest..offices where officeid=".(int) $from.";";
        $res0 = mssql_query($qry0);
		$row0= mssql_fetch_array($res0);
		$nrow0= mssql_num_rows($res0);
		
		$qry1 = "select zip from jest..offices where officeid=".(int) $to.";";
        $res1 = mssql_query($qry1);
		$row1= mssql_fetch_array($res1);
		$nrow1= mssql_num_rows($res1);
		
		if (($nrow0!=0 and $nrow1!=0) and $row0['zip']!=$row1['zip'])
		{			
			$qry2  = "update jest..zip_to_zip set ozip='".$row1['zip']."' where ozip='".$row0['zip']."';";
			$res2 = mssql_query($qry2);
			//$nrow2= mssql_num_rows($res2);
			
			$qry3 = "select count(id) as idcnt from jest..zip_to_zip where ozip='".$row1['zip']."';";
			$res3 = mssql_query($qry3);
			$row3 = mssql_fetch_array($res3);
			
			//echo $qry2.'<br>';
			
			$out[0]=true;
			$out[1]=$row3['idcnt'];
			$out[2]=$row3['idcnt'].' Zips Moved';
		}
		else
		{
			$out[2]='Zip Move Error occurred '.__LINE__;
		}
    }
	
	return $out;
}

function get_AddOfficeForm()
{
	$eoff_ar=array(0=>'Select...');
	$eoffname_ar=array();
	$eoffzip_ar	=array();
	
	$qry0 = "SELECT abrev,state FROM states ORDER BY abrev ASC;";
	$res0 = mssql_query($qry0);
	
	$qry1 = "SELECT officeid,name FROM offices where active=1 ORDER BY name ASC;";
	$res1 = mssql_query($qry1);
	
	while ($row1 = mssql_fetch_array($res1))
	{
		$eoff_ar[$row1['officeid']]=$row1['name'];
	}
	
	echo "<form id=\"addNewOfficeForm\" method=\"post\">\n";
	echo "<input class=\"ffldsstat\" type=\"hidden\" id=\"action\" name=\"action\" value=\"maint\">\n";
	echo "<input class=\"ffldsstat\" type=\"hidden\" id=\"call\" name=\"call\" value=\"office\">\n";
	echo "<input class=\"ffldsstat\" type=\"hidden\" id=\"subq\" name=\"subq\" value=\"proc_addOffice\">\n";
	echo "<input class=\"ffldsstat\" type=\"hidden\" id=\"optype\" name=\"otype\" value=\"json\">\n";
	echo "<input class=\"ffldsstat\" type=\"hidden\" id=\"ogrouping\" name=\"grouping\" value=\"0\">\n";
	echo "<table width=\"400px\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td valign=\"top\" align=\"left\" width=\"100%\">\n";
	echo "			<table width=\"100%\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"3\"><b>Office Information</b></td>\n";
	echo "				</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\">Type</td>\n";
	echo "               	<td>\n";
	echo "						<select class=\"JMStooltip ffldsstat\" id=\"otypecd\" name=\"otype_code\" title=\"Select an Office Type\">\n";
	echo "							<option value=\"0\">Select...</option>\n";
	echo "							<option value=\"1\">P&A</option>\n";
	echo "							<option value=\"2\">Franchise</option>\n";
	echo "							<option value=\"3\">Franchise in Training</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
	echo "					<td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\">Label</td>\n";
	echo "					<td><input class=\"JMStooltip ffldsstat\" type=\"text\" id=\"olabel\" name=\"olabel\" size=\"25\" title=\"Office Label\"></td>\n";
	echo "					<td align=\"left\"></td>\n";
	echo "            	</tr>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\">Name</td>\n";
	echo "					<td><input class=\"JMStooltip officeffval ffldsstat\" type=\"text\" id=\"oname\" name=\"Name\" size=\"25\" title=\"Office Name is required\"></td>\n";
	echo "					<td align=\"left\"></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\">Address</td>\n";
	echo "               	<td><input class=\"JMStooltip ffldsstat\" type=\"text\" id=\"oaddr1\" name=\"Addr1\" size=\"25\" title=\"Office Address 1 is required\"></td>\n";
	echo "					<td align=\"left\"></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\"></td>\n";
	echo "               	<td><input class=\"ffldsstat\" type=\"text\" id=\"oaddr2\" name=\"Addr2\" size=\"25\"></td>\n";
	echo "					<td align=\"left\"></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\">City</td>\n";
	echo "               	<td><input class=\"JMStooltip ffldsstat\" type=\"text\" id=\"ocity\" name=\"City\" size=\"25\" title=\"City is required\"></td>\n";
	echo "					<td align=\"left\"></td>\n";
	echo "            	</tr>\n";
	echo "            	<tr>\n";
	echo "               	<td align=\"right\">State</td>\n";
	echo "               	<td>\n";
	echo "						<select class=\"JMStooltip ffldsstat\" id=\"ostate\" name=\"State\" title=\"Select a State\">\n";
	echo "							<option value=\"0\">Select...</option>\n";
	
	while ($row0 = mssql_fetch_array($res0))
	{
		echo "							<option value=\"".$row0['abrev']."\">".$row0['abrev']."</option>\n";
	}
	
	echo "						</select>\n";
	echo "					</td>\n";
	echo "					<td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               	<td align=\"right\">Zip</td>\n";
	echo "               	<td><input class=\"JMStooltip officeffval ffldsstat\" type=\"text\" id=\"ozip\" name=\"Zip\" size=\"25\" title=\"Office Zip Code is required and must not be used by another office\"></td>\n";
	echo "					<td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               	<td align=\"right\">Phone</td>\n";
	echo "               	<td><input class=\"JMStooltip officeffval ffldsstat\" type=\"text\" id=\"ophone\" name=\"Phone\" size=\"25\" title=\"Office Phone Number is required and must not be used by another office\"></td>\n";
	echo "					<td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td colspan=\"3\"><img src=\"images/pixel.gif\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td colspan=\"3\" align=\"left\"><b>Copy / Move Operations</b></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td align=\"right\">Source Office</td>\n";
	echo "              <td>\n";
	echo "					<select class=\"ffldsstat\" id=\"srcoffice\" name=\"srcoffice\">\n";
	
	foreach ($eoff_ar as $n0=>$v0)
	{
		echo "					<option value=\"".$n0."\">".$v0." (".$n0.")</option>";
	}
	
	echo "					</select>\n";
	echo "				</td>\n";
	echo "				<td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\">Move Leads</td>\n";
	echo "				<td colspan=\"2\">\n";
	echo "					<table border=0>\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "								<input class=\"JMStooltip ffldsstat\" type=\"checkbox\" id=\"mvleads\" name=\"mvleads\" value=\"1\" title=\"Check this box to move leads from the office selected below\">\n";
	echo "							</td>\n";
	echo "							<td><div id=\"mvleads_res\"></div></td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\">Move Zip Matrix</td>\n";
	echo "				<td colspan=\"2\">\n";
	echo "					<table border=0>\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "								<input class=\"JMStooltip ffldsstat\" type=\"checkbox\" id=\"mvzips\" name=\"mvzips\" value=\"1\" title=\"Check this box to move leads from the office selected below\">\n";
	echo "							</td>\n";
	echo "							<td><div id=\"mvzips_res\"></div></td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td align=\"right\">Copy Pricebook Retail</td>\n";
	echo "				<td colspan=\"2\">\n";
	echo "					<table border=0>\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "              				<input class=\"JMStooltip ffldsstat\" type=\"checkbox\" id=\"cpyretail\" name=\"cpyretail\" value=\"1\" title=\"Check this box to copy the active Pricebook from the office selected below\">\n";
	echo "							</td>\n";
	echo "							<td><div id=\"cpyretail_res\"></div></td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\">Copy Pricebook Cost</td>\n";
	echo "				<td colspan=\"2\">\n";
	echo "					<table border=0>\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "              				<input class=\"JMStooltip ffldsstat\" type=\"checkbox\" id=\"cpycost\" name=\"cpycost\" value=\"1\" title=\"Check this box to copy active Cost Items (Labor & Material) from the office selected below\">\n";
	echo "							</td>\n";
	echo "							<td><div id=\"cpycost_res\"></div></td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\">Copy Commissions</td>\n";
	echo "				<td colspan=\"2\">\n";
	echo "					<table border=0>\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "              				<input class=\"JMStooltip ffldsstat\" type=\"checkbox\" id=\"cpycomm\" name=\"cpycomm\" value=\"1\" title=\"Check this box to copy active Commission Profiles from the office selected below\">\n";
	echo "							</td>\n";
	echo "							<td><div id=\"cpycomm_res\"></div></td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td ><img src=\"images/pixel.gif\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td colspan=\"3\" align=\"left\"><b>Owner / General Manager Information</b></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\">Login ID</td>\n";
	echo "				<td><input class=\"JMStooltip userffval ffldsstat\" type=\"text\" id=\"gmlogid\" name=\"gmlogid\" size=\"15\" title=\"Login ID is required and must be between 4 and 8 characters in length\" autocomplete=\"off\"></td>\n";
	echo "				<td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\">Password</td>\n";
	echo "				<td><input class=\"JMStooltip ffldsstat\" type=\"text\" id=\"gmpass\" name=\"gmpass\" size=\"15\" title=\"Password is required and must be between 4 and 16 characters in length\" autocomplete=\"off\"></td>\n";
	echo "				<td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\">First Name</td>\n";
	echo "				<td><input class=\"JMStooltip ffldsstat\" type=\"text\" id=\"gmfirst\" name=\"gmfirst\" size=\"15\" title=\"First Name is required and must be more than 2 characters\"></td>\n";
	echo "				<td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "				<td align=\"right\">Last Name</td>\n";
	echo "				<td><input class=\"JMStooltip ffldsstat\" type=\"text\" id=\"gmlast\" name=\"gmlast\" size=\"15\" title=\"Last Name is required and must be more than 2 characters\"></td>\n";
	echo "				<td align=\"left\"></td>\n";
	echo "            </tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function get_OfficeInfo($ffld,$vval)
{
	$out=array('result'=>false,'oid'=>0,'otext'=>'');
	
	if ($ffld=='oname' || $ffld=='ozip' || $ffld=='ophone')
	{
		if ($ffld=='oname')
		{
			$tfld='name';
		}
		elseif ($ffld=='ozip')
		{
			$tfld='zip';
		}
		elseif ($ffld=='ophone')
		{
			$tfld='phone';
		}
		
		$qry = "SELECT o.officeid AS oid,name as oname FROM offices AS o WHERE o.".$tfld."='".trim($vval)."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		$nrow = mssql_num_rows($res);
		
		if ($nrow > 0)
		{
			$out['result']=true;
			$out['oid']=$row['oid'];
			$out['otext']=$row['oname'];
		}
	}
	elseif ($ffld=='mvleads')
	{
		if ($vval!=0)
		{
			$cids=count(getcids($vval));
			$out['result']=true;
			$out['oid']=$vval;
			$out['otext']=$cids;
		}
	}
	
	return $out;
}

function get_ZipMatrix($from)
{
    // Attempts to retrieve a list of Zip Code Matrix entries from an Office ($from)
    
    $zms_out=array('zcnt'=>0);
    
    if (isset($from) and $from != 0)
    {
        $qry  = "select count(id) as zcnt from zip_to_zip where ozip=(select zip from offices where officeid=".(int) $from.");";
        $res = mssql_query($qry);
        $row = mssql_fetch_array($res);        
        
        $zms_out=array('zcnt'=>$row['zcnt']);
    }
    
    return $zms_out;
}

function get_CommsCnt($from)
{
    // Attempts to retrieve a list of Commission Profiles from an Office ($from)
    
    $cbs_out=array('ccnt'=>0);
    
    if (isset($from) and $from != 0)
    {
        $qry  = "select count(cmid) as ccnt from CommissionBuilder where oid=".(int) $from.";";
        $res = mssql_query($qry);
        $row = mssql_fetch_array($res);        
        
        $cbs_out=array('ccnt'=>$row['ccnt']);
    }
    
    return $cbs_out;
}

function getcommsold($from)
{
    // Attempts to retrieve a list of Commission Profiles from an Office ($from)
    
    $cbs_out=array('ccnt'=>0);
    
    if (isset($from) and $from != 0)
    {
        $qry = "select * from jest..CommissionBuilder where oid=".(int) $from." and active=1 and secid=0;";
        $res = mssql_query($qry);
        $nrow= mssql_num_rows($res);
        
        if ($nrow > 0)
        {
			$ccnt=0;
            while($row = mssql_fetch_array($res))
            {
                $cbs_out['comms'][$row['cmid']]=$row;
				$ccnt++;
            }
			
			$cbs_out['ccnt']=$ccnt;
        }
    }
    
    return $cbs_out;
}

function getcomms($from)
{
    // Attempts to retrieve a list of Commission Profiles from an Office ($from)
    $cbs_out=array('ccnt'=>0);
    $qry = "select
            [cmid],[oid],[sid],[secid],[ctgry],[ctype],[rwdrate],[rwdamt],[bcnt],[trgwght],[d1],[d2],[active],
            [uid],[comment],[name],[trgsrc],[linkid],[trgsrcval],[stype],[renov],isnull([cat_override],0) as cat_override
             from jest..CommissionBuilder where oid=".(int) $from." and active=1 and secid=0;";
    $res = mssql_query($qry);
    $nrow= mssql_num_rows($res);
    
    if ($nrow > 0)
    {
        $ccnt=0;
        while($row = mssql_fetch_array($res))
        {
            $cbs_out['comms'][$row['cmid']]=$row;
            $ccnt++;
        }
        
        $cbs_out['ccnt']=$ccnt;
    }
    
    return $cbs_out;
}

function copycommissions($from,$to,$psid)
{
    $out=array('pcnt'=>0,'ocmid'=>array(),'ncmid'=>array(),'otext'=>'');
    
    if ((isset($from) and $from != 0) and (isset($to) and $from != $to))
    {
        $comms=getcomms($from);
        
        if ($comms['ccnt'] > 0)
        {
            $qry = "select * from jest..CommissionBuilder where oid=".(int) $to.";";
            $res = mssql_query($qry);
            $nrow= mssql_num_rows($res);
            
            if ($nrow==0)
            {
                $oset=0;
                $nset=0;
                $pcnt=0;
                foreach ($comms['comms'] as $n=>$v)
                {
                    $uid=md5($v['cmid']).'.'.$psid;
                    
                    $qry = "insert into jest..CommissionBuilder (
                    [oid],[sid],[secid],[ctgry],[ctype],[rwdrate],[rwdamt],[bcnt],[trgwght],[d1],[d2],[active],
                    [uid],[comment],[name],[trgsrc],[linkid],[trgsrcval],[stype],[renov],[cat_override],dupeproc
                    ) values (
                    ".$to.",".$psid.",".$v['secid'].",".$v['ctgry'].",".$v['ctype'].",'".$v['rwdrate']."','".$v['rwdamt']."',
                    ".$v['bcnt'].",".$v['trgwght'].",'".$v['d1']."','".$v['d2']."',1,'".$psid."','".$v['comment']."','".$v['name']."',
                    ".$v['trgsrc'].",".$v['linkid'].",".$v['trgsrcval'].",".$v['stype'].",".$v['renov'].",".$v['cat_override'].",'".$uid."');
                    select @@identity;";
                    $res = mssql_query($qry);
                    $row = mssql_fetch_array($res);
                    $out['ncmid'][]=$row[0];
                    $out['ocmid'][]=$v['cmid'];
                    
                    if ($v['cmid']==$v['linkid'])
                    {
                        $oset=$v['linkid'];
                        $nset=$row[0];
                    }
                    
                    //echo $qry.'<br>';
                    $pcnt++;
                }
                
                if (isset($nset) and $nset!=0 and $nset!=$oset)
                {
                    $qry = "update jest..CommissionBuilder set linkid=".$nset." where oid=".$to." and linkid=".$oset.";";
                    $res = mssql_query($qry);
                }
                
                $out['pcnt']=$pcnt;
                $out['oset']=$oset;
                $out['nset']=$nset;
                $out['otext']=$pcnt.' Commision Profiles copied';
            }
        }
    }
    
    return $out;
}

function getcids($from)
{
    // Attempts to retrieve a list of Customers from an Office ($from)
    // Will only return cids for Customers that are not marked hidden, duplicate, and have no Est
    
    $cids_out=array();
    
    if (isset($from) and $from != 0)
    {
        $qry  = "
                declare @oid int;
                set @oid=".(int) $from.";
                
                select
                    f.cid, f.clname
                from
                    jest..cinfo as f
                where 
                    f.officeid=@oid
                    and f.jobid='0'
                    and f.dupe!=1
                    and f.hidden!=1
                order by f.cid;
        ";
        $res  = mssql_query($qry);
        $nrow = mssql_num_rows($res);
        
        if ($nrow > 0)
        {
            while ($row = mssql_fetch_array($res))
            {
                $cids_out[]=$row['cid'];
            }
        }
    }
    
    return $cids_out;
}

function getretailpb($from)
{
    // Attempts to retrieve a list of Pricebook Retail entries from an Office ($from)
    // Will only return ids for entries that are not marked disabled
    
    $aids_out=array(
					'result'=>false,
					'category'=>0,
					'retail'=>0,
					'offinfo'=>array(),
					'bprices'=>array(),
					'otext'=>'Nothing to do');
    
    if (isset($from) and $from != 0)
    {
        //Pre: Pull Staging Info
        $qry0 = "select
					officeid,pb_code,pft_sqft,def_per,def_sqft,def_s,def_m,def_d,psched,psched_perc,stax 
				from offices where officeid=".(int) $from.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        $pbc  = ($row0['pb_code']=='0')? '': trim($row0['pb_code']);
		$aids_out['offinfo']=$row0;
		
		$qry1a = "select quan,quan1,price,comm from jest..rbpricep where officeid=".(int) $from.";";		
        $res1a = mssql_query($qry1a);
        $nrow1a= mssql_num_rows($res1a);
        
        if ($nrow1a > 0)
        {
			$pbs_out=array();
            while ($row1a = mssql_fetch_array($res1a))
            {
                $pbs_out[]=array(
                                  'quan'=>$row1a['quan'],
                                  'quan1'=>$row1a['quan1'],
                                  'price'=>$row1a['price'],
                                  'comm'=>$row1a['comm']);
            }
            
            $aids_out['result']=true;
            $aids_out['bprices']=$pbs_out;
		}
        
        //Stage 2: Pull Pricebook Categories
        $qry1  = "select catid,name,active,seqn,privcat,irequired,salestype from AC_cats where officeid=".(int) $from.";";
        $res1  = mssql_query($qry1);
        $nrow1 = mssql_num_rows($res1);
        
        if ($nrow1 > 0)
        {
            $cats_out=array();
            while ($row1 = mssql_fetch_array($res1))
            {
                $cats_out[]=array(
                                  'catid'=>$row1['catid'],
                                  'name'=>$row1['name'],
                                  'active'=>$row1['active'],
                                  'seqn'=>$row1['seqn'],
                                  'privcat'=>$row1['privcat'],
                                  'irequired'=>$row1['irequired'],
                                  'salestype'=>$row1['salestype']);
            }
            
            $aids_out['result']=true;
            $aids_out['cats']=$cats_out;
            $aids_out['otext']='';
           
            //Stage 3: Setup Retail Pricebook Items
            $qry2  = "
                        select
                            id,aid,officeid,phsid,catid,matid,subid,item,atrib1,atrib2,atrib3,
                            rp,commtype,crate,qtype,spaitem,quan_calc,mtype,lrange,hrange,seqn,
                            supplier,bullet,def_quan,pdetect,royrelease,isnull(poolcalc,0) as poolcalc 
                        from [".$pbc."acc] where officeid=".$row0['officeid']." and disabled!=1";
            $res2  = mssql_query($qry2);
            $nrow2 = mssql_num_rows($res2);
			//echo $qry2.'<br>';
            
            if ($nrow2 > 0)
            {
                $itms_out=array();
                while ($row2 = mssql_fetch_array($res2))
                {
                    $itms_out[$row2['id']]=array(
                        'id'=>$row2['id'],'aid'=>$row2['aid'],'officeid'=>$row2['officeid'],
                        'phsid'=>$row2['phsid'],'catid'=>$row2['catid'],
                        'matid'=>$row2['matid'],'subid'=>$row2['subid'],
                        'item'=>trim($row2['item']),'atrib1'=>trim($row2['atrib1']),'atrib2'=>trim($row2['atrib2']),'atrib3'=>trim($row2['atrib3']),
                        'rp'=>number_format($row2['rp'], 2, '.', ','),'commtype'=>$row2['commtype'],'crate'=>$row2['crate'],
                        'qtype'=>$row2['qtype'],'spaitem'=>$row2['spaitem'],'quan_calc'=>$row2['quan_calc'],'mtype'=>$row2['mtype'],
                        'lrange'=>$row2['lrange'],'hrange'=>$row2['hrange'],'seqn'=>$row2['seqn'],
                        'supplier'=>$row2['supplier'],'bullet'=>$row2['bullet'],'def_quan'=>$row2['def_quan'],
                        'pdetect'=>$row2['pdetect'],'royrelease'=>$row2['royrelease'],'poolcalc'=>$row2['poolcalc']
                    );
                }
                
                $aids_out['result']=true;
                $aids_out['retail']=$itms_out;
                $aids_out['otext']='';
            }
        }
    }
    
    return $aids_out;
}

function copyPriceBookCost($from,$to,$psid)
{
	$out=array('result'=>0,'otext'=>'');
	if ((isset($from) and $from!=0) and (isset($to) and $to!=0))
	{
		$cst=getcostcnt($from);
		//Copy Labor Items
		if (count($cst['cost']['labor']) > 0)
		{
			$qryL1 = "select id from jest..accpbook where officeid=".$to.";";
			$resL1 = mssql_query($qryL1);
			$nrowL1= mssql_num_rows($resL1);
			
			if ($nrowL1==0)
			{
				$lcnt=0;
				foreach ($cst['cost']['labor'] as $nl=>$vl)
				{
					$qryL2  = "insert into jest..accpbook ";
					$qryL2 .= "(officeid,accid,phsid,matid,seqnum,item,atrib1,atrib2,atrib3,mtype,lrange,hrange,bprice,rprice,";
					$qryL2 .= "rebate,rpbid,baseitem,quantity,qtype,raccid,rinvid,spaitem,zcharge,supplier,supercedes,code,";
					$qryL2 .= "royrelease,usecid) values ";
					$qryL2 .= "(".$to.",".$vl['accid'].",".$vl['phsid'].",".$vl['matid'].",".$vl['seqnum'].",";
					$qryL2 .= "'".$vl['item']."','".$vl['atrib1']."','".$vl['atrib2']."','".$vl['atrib3']."',";
					$qryL2 .= "".$vl['mtype'].",".$vl['lrange'].",".$vl['hrange'].",cast('".$vl['bprice']."' as money),cast('".$vl['rprice']."' as money),";
					$qryL2 .= "".$vl['rebate'].",".$vl['rpbid'].",".$vl['baseitem'].",'".$vl['quantity']."',".$vl['qtype'].",";
					$qryL2 .= "'".$vl['raccid']."','".$vl['rinvid']."',".$vl['spaitem'].",".$vl['zcharge'].",".$vl['supplier'].",".$vl['supercedes'].",";
					$qryL2 .= "'".$vl['code']."',".$vl['royrelease'].",".$psid.");";
					$resL2 = mssql_query($qryL2);
					$lcnt++;
				}
				
				$out['result']=$lcnt;
				$out['otext'].=$lcnt.' Labor Cost Items Copied <br>';
			}
		}
		
		//Copy Material Items
		if (count($cst['cost']['material']) > 0)
		{
			$qryM1 = "select invid from jest..inventory where officeid=".$to.";";
			$resM1 = mssql_query($qryM1);
			$nrowM1= mssql_num_rows($resM1);
			
			if ($nrowM1==0)
			{
				$mcnt=0;
				foreach ($cst['cost']['material'] as $nm=>$vm)
				{
					$qryM2  = "insert into jest..inventory ";
					$qryM2 .= "(officeid,accid,raccid,rinvid,vid,phsid,matid,vendor,vpno,item,atrib1,atrib2,atrib3,mtype,bprice,rprice,";
					$qryM2 .= "quan_calc,commtype,crate,seqnum,baseitem,spaitem,qtype,active,royrelease,usecid) values ";
					$qryM2 .= "(".$to.",".$vm['accid'].",".$vm['raccid'].",".$vm['rinvid'].",".$vm['vid'].",".$vm['phsid'].",".$vm['matid'].",";
					$qryM2 .= "'".$vm['vendor']."','".$vm['vpno']."','".$vm['item']."','".$vm['atrib1']."','".$vm['atrib2']."','".$vm['atrib3']."',";
					$qryM2 .= "".$vm['mtype'].",cast('".$vm['bprice']."' as money),cast('".$vm['rprice']."' as money),'".$vm['quan_calc']."',";
					$qryM2 .= "'".$vm['commtype']."','".$vm['crate']."',".$vm['seqnum'].",".$vm['baseitem'].",".$vm['spaitem'].",";
					$qryM2 .= "".$vm['qtype'].",1,".$vm['royrelease'].",".$psid.");";
					$resM2 = mssql_query($qryM2);
					$mcnt++;
				}
				
				$out['result']=$out['result']+$mcnt;
				$out['otext'].=$mcnt.' Material Cost Items Copied';
			}
		}
	}
	
	return $out;
}

function copyPriceBookRetail($from,$to,$psid)
{
	$out=array();
	
	if ((isset($from) and $from!=0) and (isset($to) and $to!=0))
	{
		$rpb=getretailpb($from);
	
		//Copy Categories
		if ($rpb['result'] and count($rpb['cats']) > 0)
		{
			$qryC1 = "select * from jest..AC_cats where officeid=".$to.";";
			$resC1 = mssql_query($qryC1);
			$nrowC1= mssql_num_rows($resC1);
			
			if ($nrowC1==0)
			{
				$ccnt=0;
				foreach ($rpb['cats'] as $nc=>$vc)
				{
					$qryC2 = "insert into jest..AC_cats (officeid,catid,name,active,seqn,privcat,irequired,salestype) values ";
					$qryC2 .= "(".$to.",".$vc['catid'].",'".$vc['name']."',".$vc['active'].",".$vc['seqn'].",".$vc['privcat'].",'".$vc['irequired']."',".$vc['salestype'].");";
					$resC2 = mssql_query($qryC2);
					$ccnt++;
				}
				
				$out['catcnt']=$ccnt;
			}
			
			//Copy Items
			if ($ccnt > 0 and count($rpb['retail']) > 0)
			{
				$qryR1 = "select * from jest..acc where officeid=".$to.";";
				$resR1 = mssql_query($qryR1);
				$nrowR1= mssql_num_rows($resR1);
				
				if ($nrowR1==0)
				{
					$rcnt=0;
					foreach ($rpb['retail'] as $nr=>$vr)
					{
						$qryR2  = "insert into jest..acc ";
						$qryR2 .= "(aid,officeid,phsid,catid,matid,subid,item,atrib1,atrib2,atrib3,rp,commtype,crate,";
						$qryR2 .= "qtype,spaitem,quan_calc,mtype,lrange,hrange,seqn,supplier,bullet,def_quan,pdetect,royrelease,poolcalc) values ";
						$qryR2 .= "('".$vr['aid']."',".$to.",".$vr['phsid'].",".$vr['catid'].",".$vr['matid'].",".$vr['subid'].",";
						$qryR2 .= "'".$vr['item']."','".$vr['atrib1']."','".$vr['atrib2']."','".$vr['atrib3']."','".$vr['rp']."',".$vr['commtype'].",'".$vr['crate']."',";
						$qryR2 .= "".$vr['qtype'].",".$vr['spaitem'].",'".$vr['quan_calc']."',".$vr['mtype'].",".$vr['lrange'].",".$vr['hrange'].",".$vr['seqn'].",";
						$qryR2 .= "".$vr['supplier'].",".$vr['bullet'].",".$vr['def_quan'].",".$vr['pdetect'].",".$vr['royrelease'].",".$vr['poolcalc'].");";
						$resR2 = mssql_query($qryR2);
						//echo $qryR2.'<br>';
						$rcnt++;
					}
					
					$out['retcnt']=$rcnt;
					$out['otext']=$rcnt.' Retail Items Copied';
				}
				
				$qry01 = "update jest..offices set
							enest=1,encost=1,encon=1,enjob=1,enexp=1,
							pft_sqft='".$rpb['offinfo']['pft_sqft']."',
							psched='".$rpb['offinfo']['psched']."',
							psched_perc='".$rpb['offinfo']['psched_perc']."',
							stax='".$rpb['offinfo']['stax']."'
							where officeid=".$to.";";
				$res01 = mssql_query($qry01);
			}
			
			if (isset($rpb['bprices']) and count($rpb['bprices']) > 0)
			{
				$qryB1 = "select id from jest..rbpricep where officeid=".(int) $to.";";
				$resB1 = mssql_query($qryB1);
				$nrowB1= mssql_num_rows($resB1);
				
				if ($nrowB1==0)
				{
					foreach ($rpb['bprices'] as $nb=>$vb)
					{
						$qryB2 = "insert into jest..rbpricep (officeid,quan,quan1,price,comm) values (".$to.",'".$vb['quan']."','".$vb['quan1']."','".$vb['price']."','".$vb['comm']."');";
						$resB2 = mssql_query($qryB2);						
					}
				}
			}
		}
	}
	
	return $out;
}

function getretailpbcnt($from)
{
    // Attempts to retrieve a list of Pricebook Retail entries from an Office ($from)
    // Will only return ids for entries that are not marked disabled
    
    $aids_out=array('cats'=>0,'retail'=>0);
    
    if (isset($from) and $from != 0)
    {
        //Pre: Pull Staging Info
        $qry0  = "select officeid,pb_code from offices where officeid=".(int) $from.";";
        $res0  = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        
        $pbc   = ($row0['pb_code']=='0')? '': trim($row0['pb_code']);
        
        //Stage 1: Pull Pricebook Categories
        $qry1  = "select catid,name,active,seqn,privcat,irequired,salestype from AC_cats where officeid=".(int) $from.";";
        $res1  = mssql_query($qry1);
        $nrow1 = mssql_num_rows($res1);
        
        if ($nrow1 > 0)
        {
            $aids_out['cats']=$nrow1;
           
            //Stage 2: Setup Retail Pricebook Items
            $qry2  = "select id from [".$pbc."acc] where officeid=".$row0['officeid']." and disabled!=1";
            $res2  = mssql_query($qry2);
            $nrow2 = mssql_num_rows($res2);
            
            if ($nrow2 > 0)
            {
                $aids_out['retail']=$nrow2;
            }
        }
    }
    
    return $aids_out;
}

function getcostcnt($from)
{
    // Attempts to retrieve a list of Pricebook Retail entries from an Office ($from)
    // Will only return ids for entries that are not marked disabled
    
    $cst_out=array('ccnt'=>0,'cost'=>array('labor','material'));
    
    if (isset($from) and $from != 0)
    {
        //Pre: Pull Staging Info
        $qry0 = "select officeid,pb_code from offices where officeid=".(int) $from.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        $pbc  = ($row0['pb_code']=='0')? '': trim($row0['pb_code']);
           
        //Stage 1: Setup Retail Pricebook Items
        $qry1  = "select * from [".$pbc."accpbook] where officeid=".$row0['officeid'].";";
        $res1  = mssql_query($qry1);
        $nrow1 = mssql_num_rows($res1);
            
        if ($nrow1 > 0)
        {
            $cst_out['ccnt']=$nrow1;
			
			while($row1 = mssql_fetch_array($res1))
			{
				$cst_out['cost']['labor'][]=$row1;
			}
        }
		
		$qry2  = "select * from [".$pbc."inventory] where officeid=".$row0['officeid'].";";
        $res2  = mssql_query($qry2);
        $nrow2 = mssql_num_rows($res2);
            
        if ($nrow2 > 0)
        {
            $cst_out['ccnt']=($cst_out['ccnt']+$nrow2);
			
			while($row2 = mssql_fetch_array($res2))
			{
				$cst_out['cost']['material'][]=$row2;
			}
        }
    }
    
    return $cst_out;
}

function get_UserInfo($vval)
{
	$out=array('result'=>false,'sid'=>0,'stext'=>'');
	
	$qry = "SELECT
				s.securityid AS sid,
				s.login as logid,
				(s.fname+' '+s.lname) as sname,
				(select name from offices where officeid=s.officeid) as oname
			FROM security AS s WHERE s.login='".htmlspecialchars(trim($vval))."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow = mssql_num_rows($res);
	
	if ($nrow > 0)
	{
		$out['result']=true;
		$out['sid']=$row['sid'];
		$out['stext']=$row['logid'].' already in use by '.$row['sname'].' in '.$row['oname'];
	}
	
	return $out;
}

function update_PaymentScheduleConfig($oid)
{
	$psar=explode(',',$_REQUEST['ps_phsCode']);
	$ppar=explode(',',$_REQUEST['ps_phsAmt']);
	
	if (count($psar)==count($ppar))
	{
		$qry  = "UPDATE offices SET ";
		$qry .= "psched='".$_REQUEST['ps_phsCode']."',";
		$qry .= "psched_perc='".$_REQUEST['ps_phsAmt']."', ";
		$qry .= "lupdate='".$_SESSION['securityid']."', ";
		$qry .= "lupdtime=getdate() ";
		$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode";
		$res  = mssql_query($qry);
		$row  = mssql_fetch_array($res);
		
		if ($row['ErrorCode']!=0)
		{
			echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
		}
	}
	else
	{
		echo 'Error: Invalid Array Count';
	}
}

function get_LastOfficeUpdate($oid)
{
	$qry = "SELECT O.lupdate,O.lupdtime,(select fname from security where securityid=O.lupdate) as lfname,(select lname from security where securityid=O.lupdate) as llname FROM offices as O WHERE O.officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	echo date('m/d/Y g:iA',strtotime($row['lupdtime'])) . ' by ' . $row['lfname'] . ' ' . $row['llname'];
}

function get_FinanceConfig($oid)
{
	$qryFIN		="SELECT officeid,name FROM offices WHERE finan_off!=0 order by grouping,name;";
	$resFIN		= mssql_query($qryFIN);
	//$rowFIN		= mssql_fetch_array($resFIN);
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Financing Office:</b></td>\n";
	echo "               <td>\n";
	echo "               	<select name=\"finan_from\" id=\"gc_finan_from\">\n";
	echo "                     <option value=\"0\">None</option>\n";
		
		while ($rowFIN=mssql_fetch_array($resFIN))
		{
			if ($rowFIN['officeid']==$row['finan_from'])
			{
				echo "                     <option value=\"".$rowFIN['officeid']."\" SELECTED>".$rowFIN['name']."</option>\n";
			}
			else
			{
				echo "                     <option value=\"".$rowFIN['officeid']."\">".$rowFIN['name']."</option>\n";
			}
		}
		
	echo "               	</select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	
	$qryFREP = "SELECT securityid,lname,fname,substring(slevel,13,1) FROM security WHERE officeid='".$row['finan_from']."' AND substring(slevel,13,1) >= 1 ORDER BY lname;";
	$resFREP = mssql_query($qryFREP);
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Def Finance Rep:</b></td>\n";
	echo "               <td>\n";
	echo "               	<select name=\"finan_rep\" id=\"gc_finan_rep\">\n";
	echo "                     <option value=\"0\">None</option>\n";
		
	while ($rowFREP=mssql_fetch_array($resFREP))
	{
		if ($rowFREP['securityid']==$row['finan_rep'])
		{
			echo "                     <option value=\"".$rowFREP['securityid']."\" SELECTED>".$rowFREP['lname'].", ".$rowFREP['fname']."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowFREP['securityid']."\">".$rowFREP['lname'].", ".$rowFREP['fname']."</option>\n";
		}
	}
		
	echo "               	</select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	
	$qryZz = "SELECT officeid,name FROM offices WHERE finan_from=".$oid." ORDER BY name ASC;";
	$resZz = mssql_query($qryZz);
		
	echo "         <table>\n";
	echo "            <tr>\n";
	echo "               <td align=\"left\"><b>Financing For:</b></td>\n";
	echo "               <td align=\"left\"></td>\n";
	echo "            </tr>\n";

	while ($rowZz = mssql_fetch_array($resZz))
	{
		echo "            <tr>\n";
		echo "               <td align=\"left\"></td>\n";
		echo "               <td align=\"left\">".$rowZz['name']."</td>\n";
		echo "            </tr>\n";
	}
	
	echo "         </table>\n";
}

function update_RoutingMatrixConfig($oid)
{
	$qry0 = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry  = "UPDATE offices SET ";	
	$qry .= "zip='".$_REQUEST['rm_nozip']."',";
	$qry .= "ringto='".$_REQUEST['rm_noringto']."',";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate() ";
	$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	//echo  $qry.'<br>';
	
	if ($row['ErrorCode']!=0)
	{
		echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
	}
}

function update_SalesTaxBaseConfig($oid)
{
	$qry0 = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry  = "UPDATE offices SET ";	
	$qry .= "stax='".$_REQUEST['st_stax']."',";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate() ";
	$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	//echo  $qry.'<br>';
	
	if ($row['ErrorCode']!=0)
	{
		echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
	}
}

function get_RoutingMatrixConfig($oid)
{
	$qry = "SELECT O.*,isnull((select count(ozip) from zip_to_zip where ozip=O.zip),0) as zcnt FROM offices AS O WHERE O.officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry1 = "SELECT COUNT(id) as mcnt FROM zip_to_zip WHERE ozip='".$row['zip']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	if ($row['zcnt']!=0 && strlen($row['ringto'])!=10)
	{
		$zmerrcnt=1;
		$zmerrtxt="<font color=\"red\"><b>Matrix Config Invalid!</b></font><br>Office has Matrix Ties but improper Ringto. Calls will not be processed properly";
	}
	else
	{
		$zmerrcnt=0;
		$zmerrtxt='';
	}
	
	echo "<form id=\"frm_UpdateRoutingMatrixConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"rm_oid\" name=\"officeid\" value=\"".$oid."\">\n";
	echo "<input type=\"hidden\" id=\"rm_mties\" name=\"rm_mties\" value=\"".$row1['mcnt']."\">\n";
	echo "         <table width=\"350px\">\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\" colspan=\"3\"><a id=\"submit_UpdateRoutingMatrixConfig\" href=\"#\">Save <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save Routing Matrix Configuration\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "            <tr>\n";
	echo "					<td align=\"right\"><b>Matrix Ties</b></td>\n";
	echo "					<td align=\"left\">".$row1['mcnt']."</td>\n";
	echo "					<td align=\"left\">".$zmerrtxt."</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "					<td align=\"right\"><b>Zip Code</b></td>\n";
	echo "               	<td align=\"left\" colspan=\"2\"><input class=\"JMStooltip\" type=\"text\" id=\"rm_nozip\" name=\"nozip\" value=\"".$row['zip']."\" size=\"20\" title=\"This should match the Office Zip Code\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "					<td align=\"right\"><b>RingTo</b></td>\n";
	echo "               	<td align=\"left\" colspan=\"2\"><input class=\"JMStooltip\" type=\"text\" id=\"rm_noringto\" name=\"noringto\" value=\"".$row['ringto']."\" size=\"20\" title=\"Calls will be routed to this number\"></td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "</form\n";
}

function get_FeeScheduleConfig($oid)
{
	$qry = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	echo "<form id=\"frm_UpdateFeeScheduleConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"es_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "         <table width=\"350px\">\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\"></td>\n";
		echo "               <td align=\"right\"><a id=\"submit_UpdateFeeScheduleConfig\" href=\"#\">Save <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save Fee Schedule Configuration\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Base Consulting Fee</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"es_consfee\" name=\"consfee\" value=\"".number_format($row['consfee'], 2, '.', '')."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Base Accounting Fee</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"es_acctfee\" name=\"acctfee\" value=\"".number_format($row['acctfee'], 2, '.', '')."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Per Pool Accounting Fee</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"es_pacctfee\" name=\"pacctfee\" value=\"".number_format($row['pacctfee'], 2, '.', '')."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "</form>\n";
}

function update_FeeScheduleConfig($oid)
{
	$qry0 = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry  = "UPDATE offices SET ";
	$qry .= "consfee='".$_REQUEST['es_consfee']."', ";
	$qry .= "acctfee='".$_REQUEST['es_acctfee']."', ";
	$qry .= "pacctfee='".$_REQUEST['es_pacctfee']."', ";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate() ";
	$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	//echo  $qry.'<br>';
	
	if ($row['ErrorCode']!=0)
	{
		echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
	}
}

function update_PricebookConfig($oid)
{
	$qry0 = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry  = "UPDATE offices SET ";	
	$qry .= "stax='".$_REQUEST['pb_stax']."',";
	$qry .= "deckinc='".$_REQUEST['pb_deckinc']."',";
	$qry .= "bullet_rate='".$_REQUEST['pb_bullet_rate']."',";
	$qry .= "bullet_cnt='".$_REQUEST['pb_bullet_cnt']."',";
	$qry .= "over_split='".$_REQUEST['pb_over_split']."',";
	$qry .= "com_rate='".$_REQUEST['pb_com_rate']."', ";
	$qry .= "tgp='".$_REQUEST['pb_tgp']."', ";
	$qry .= "vgp='".$_REQUEST['pb_vgp']."', ";
	$qry .= "manphsadj='".$_REQUEST['pb_manphsadj']."', ";
	$qry .= "all_code='".$_REQUEST['pb_all_code']."', ";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate() ";
	$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	//echo  $qry.'<br>';
	
	if ($row['ErrorCode']!=0)
	{
		echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
	}
}

function get_PricebookConfig($oid)
{
	$qry = "SELECT * FROM offices WHERE officeid=".$oid." ORDER BY city ASC;";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	if ($row['pb_code']==0)
	{
		$MAS='';
	}
	else
	{
		$MAS=$row['pb_code'];
	}
	
	$qryH = "SELECT id,aid,item FROM [".$MAS."acc] WHERE officeid=".$row['officeid']." AND disabled!='1' AND qtype='33' ORDER BY item;";
	$resH = mssql_query($qryH);
	
	echo "<form id=\"frm_UpdatePricebookConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"pb_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "         <table width=\"350px\">\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\"></td>\n";
		echo "               <td align=\"right\"><a id=\"submit_UpdatePricebookConfig\" href=\"#\">Save <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save Pricebook Configuration\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Commission Rate</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"pb_com_rate\" name=\"com_rate\" value=\"".$row['com_rate']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Bullet Comm</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"pb_bullet_rate\" name=\"bullet_rate\" value=\"".$row['bullet_rate']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Bullet Count</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"pb_bullet_cnt\" name=\"bullet_cnt\" value=\"".$row['bullet_cnt']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Overage Split</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"pb_over_split\" name=\"over_split\" value=\"".$row['over_split']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Avg GP</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"pb_tgp\" name=\"tgp\" value=\"".$row['tgp']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Retail vs Cost Var</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"pb_vgp\" name=\"vgp\" value=\"".$row['vgp']."\" size=\"5\">%</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Deck Included</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"deckinc\" id=\"pb_deckinc\">\n";

	if ($row['deckinc']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Allowance Code</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"all_code\" id=\"pb_all_code\">\n";
	echo "                     <option value=\"0\">None</option>\n";

	while ($rowH=mssql_fetch_array($resH))
	{
		$item=substr($rowH['item'], 0,20);
		if ($rowH['id']==$row['all_code'])
		{
			echo "                     <option value=\"".$rowH['id']."\" SELECTED>(".$rowH['aid'].") ".$item."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowH['id']."\">(".$rowH['aid'].") ".$item."</option>\n";
		}
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Manual Phase Adjust</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"manphsadj\" id=\"pb_manphsadj\">\n";

	if ($row['manphsadj']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "</form>\n";
}

function get_SalesTaxConfig($oid)
{
	$qry = "SELECT O.* FROM offices AS O WHERE O.officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	echo "<form id=\"frm_UpdateSalesTaxConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"st_oid\" name=\"officeid\" value=\"".$oid."\">\n";
	
	echo "<table class=\"outer\" width=\"600px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\"><b>Office Sale Tax Enable/Disable</b></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<br/>";
	echo "<table width=\"600px\">\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "	<tr>\n";
		echo "		<td align=\"right\"><a id=\"submit_UpdateSalesTaxBaseConfig\" href=\"#\">Save <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save Sales Tax Configuration\"></a></td>\n";
		echo "	</tr>\n";
	}
	
	echo "	<tr>\n";
	echo "		<td align=\"right\">\n";
	echo "			<table>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\"><b>Sales Tax</b></td>\n";
	echo "					<td align=\"right\">\n";
	echo "						<select name=\"stax\" id=\"st_stax\">\n";

	if ($row['stax']==0)
	{
		echo "			<option value=\"0\" SELECTED>Disabled</option>\n";
		echo "			<option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "			<option value=\"0\">Disabled</option>\n";
		echo "			<option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "						</select>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "<table class=\"outer\" width=\"600px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"center\"><b>Sales Tax Edit</b></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<br/>";
	
	if ($row['stax']==1)
	{
		echo "<table width=\"600px\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"center\"><b>Sales Tax Edit</b></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=\"top\">\n";
		
		$qryA = "SELECT * FROM taxrate WHERE officeid=".$oid." ORDER BY active DESC,city ASC;";
		$resA = mssql_query($qryA);
		$nrowA =mssql_num_rows($resA);
	
		if ($nrowA > 0)
		{	
			echo "			<table>\n";
			echo "				<tr>\n";
			echo "					<td><b>Active Tax & Permit Table</b></td>\n";
			echo "				</tr>\n";
			
			$stcnt=0;
			while ($rowA = mssql_fetch_array($resA))
			{
				$stcnt++;
				
				if ($rowA['active']==0)
				{
					$tbg=($stcnt%2)?'disabled_even':'disabled_odd';
				}
				else
				{
					$tbg=($stcnt%2)?'even':'odd';
				}				
				
				$sta=($rowA['active']==1)?'Deactivate':'Activate';
				
				echo "				<tr class=\"".$tbg."\">\n";
				echo "					<td>\n";
				//echo "            			<form class=\"upd_TaxPermit\" method=\"post\">\n";
				echo "						<table width=\"300px\">\n";
				echo "               			<tr><td align=\"right\"><b>City</b></td><td align=\"left\">".$rowA['city']."</td></tr>\n";
				echo "               			<tr><td align=\"right\"><b>Permit</b></td><td align=\"left\">".$rowA['permit']."</td></tr>\n";
				echo "               			<tr><td align=\"right\"><b>/w Ryder</b</td><td align=\"left\">".$rowA['wryder']."</td></tr>\n";
				echo "               			<tr><td align=\"right\"><b>Tax Rate</b></td><td align=\"left\">".$rowA['taxrate']."</td></tr>\n";
				echo "               			<tr><td align=\"right\" colspan=\"2\"><div><input class=\"staxupdID\" type=\"hidden\" value=\"".$rowA['id']."\"><button class=\"upd_thisTaxRate\">".$sta."</button></div></td></tr>\n";
				echo "						</table>\n";
				echo "					</td>\n";
				echo "            	</tr>\n";
			}
		
			echo "			</table>\n";
		}
		
		echo "		</td>\n";
		echo "		<td valign=\"top\" width=\"275px\">\n";
		echo "		<form id=\"addTaxPermitForm\" method=\"post\">\n";
		echo "			<table>\n";
		echo "				<tr>\n";
		echo "					<td><b>Add Tax & Permit</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td align=\"right\"><b>City/Township</b></td>\n";
		echo "					<td><input id=\"addtpcity\" type=\"text\" name=\"addtpcity\" size=\"20\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td align=\"right\"><b>Base Permit</b></td>\n";
		echo "					<td><input id=\"addtppermit\" type=\"text\" name=\"addtppermit\" size=\"20\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td align=\"right\"><b>w/ Rider</b></td>\n";
		echo "					<td><input id=\"addtpwryder\" type=\"text\" name=\"addtpwryder\"  size=\"20\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td align=\"right\"><b>Tax Rate</b></td>\n";
		echo "					<td><input id=\"addtptaxrate\" type=\"text\" name=\"addtptaxrate\" value=\"0.00\" size=\"20\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td align=\"right\"></td>\n";
		echo "					<td><button id=\"addNewTaxPermit\">Add New</button></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}


function add_NewTaxPermit($oid,$city,$permit,$wryder,$taxrate)
{
	$qry1 = "INSERT INTO jest..taxrate (officeid,city,permit,wryder,taxrate) VALUES (".$oid.",'".$city."','".$permit."','".$wryder."','".$taxrate."');";
	$res1 = mssql_query($qry1);
}

function upd_thisTaxRate($oid,$id)
{
	$qry0 = "SELECT officeid,id,active FROM jest..taxrate WHERE officeid=".$oid." AND id=".$id.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	if ($row0['active'] == 0)
	{
		$qry1 = "UPDATE jest..taxrate SET active=1 WHERE officeid=".$oid." AND id=".$id.";";
		$res1 = mssql_query($qry1);
	}
	else
	{
		$qry1 = "UPDATE jest..taxrate SET active=0 WHERE officeid=".$oid." AND id=".$id.";";
		$res1 = mssql_query($qry1);
	}
}

function update_AccountingQBXMLConfig($oid)
{
	$qry0 = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res0 = mssql_query($qry0);
	//$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT * FROM qbwcConfig WHERE oid=".$oid.";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	
	//echo '<pre>';
	//print_r($_REQUEST);
	//echo '</pre>';
	
	//if (isset($_REQUEST['qb_reccnt']) and $_REQUEST['qb_reccnt']==1)
	if ($nrow1 >= 1)
	{
		$qry  = "UPDATE qbwcConfig SET ";
		$qry .= "AppDescription='".trim($_REQUEST['qb_AppDescription'])."',";
		$qry .= "AppDisplayName='".trim($_REQUEST['qb_AppDisplayName'])."',";
		$qry .= "AppID='".$_REQUEST['qb_AppID']."',";
		$qry .= "AppName='".$_REQUEST['qb_AppName']."',";
		$qry .= "AppSupport='".$_REQUEST['qb_AppSupport']."',";
		$qry .= "AppUniqueName='".$_REQUEST['qb_AppUniqueName']."',";
		$qry .= "AppURL='".$_REQUEST['qb_AppURL']."',";
		$qry .= "AuthFlags='".$_REQUEST['qb_AuthFlags']."',";
		$qry .= "FileID='".$_REQUEST['qb_FileID']."',";
		$qry .= "IsReadOnly='".$_REQUEST['qb_IsReadOnly']."',";
		$qry .= "Notify='".$_REQUEST['qb_Notify']."',";
		$qry .= "OwnerID='".$_REQUEST['qb_OwnerID']."',";
		$qry .= "PersonalDataPref='".$_REQUEST['qb_PersonalDataPref']."',";
		$qry .= "QBType='".$_REQUEST['qb_QBType']."',";
		$qry .= "Scheduler='".$_REQUEST['qb_Scheduler']."',";
		$qry .= "Style='".$_REQUEST['qb_Style']."',";
		$qry .= "UnattendedModePref='".$_REQUEST['qb_UnattendedModePref']."',";
		$qry .= "UserName='".$_REQUEST['qb_UserName']."', ";
		$qry .= "qb_soap_host='".trim($_REQUEST['qb_SoapHost'])."', ";
		$qry .= "qb_soap_db='".trim($_REQUEST['qb_SoapDB'])."', ";
		$qry .= "qb_soap_user='".trim($_REQUEST['qb_SoapUser'])."', ";
		$qry .= "qb_soap_pass='".trim($_REQUEST['qb_SoapPass'])."', ";
		$qry .= "uid=".$_SESSION['securityid'].", ";
		$qry .= "udate=getdate() ";
		$qry .= " WHERE id=".$row1['id']."; select @@ERROR as ErrorCode;";
		$res  = mssql_query($qry);
		$row  = mssql_fetch_array($res);
		
		//echo $qry.'<br>';
	
		if ($row['ErrorCode']!=0)
		{
			echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
		}
	}
	else
	{
		$qry  = "INSERT INTO qbwcConfig (";
		$qry .= "oid, ";
		$qry .= "AppDescription, ";
		$qry .= "AppDisplayName, ";
		$qry .= "AppID, ";
		$qry .= "AppName, ";
		$qry .= "AppSupport, ";
		$qry .= "AppUniqueName, ";
		$qry .= "AppURL, ";
		$qry .= "AuthFlags, ";
		$qry .= "FileID, ";
		$qry .= "IsReadOnly, ";
		$qry .= "Notify, ";
		$qry .= "OwnerID, ";
		$qry .= "PersonalDataPref, ";
		$qry .= "QBType, ";
		$qry .= "Scheduler, ";
		$qry .= "Style, ";
		$qry .= "UnattendedModePref, ";
		$qry .= "UserName";
		$qry .= ") VALUES (";
		$qry .= "".$_REQUEST['qb_oid'].",";
		$qry .= "'".$_REQUEST['qb_AppDescription']."',";
		$qry .= "'".$_REQUEST['qb_AppDisplayName']."',";
		$qry .= "'".$_REQUEST['qb_AppID']."',";
		$qry .= "'".$_REQUEST['qb_AppName']."',";
		$qry .= "'".$_REQUEST['qb_AppSupport']."',";
		$qry .= "'".$_REQUEST['qb_AppUniqueName']."',";
		$qry .= "'".$_REQUEST['qb_AppURL']."',";
		$qry .= "'".$_REQUEST['qb_AuthFlags']."',";
		$qry .= "'".$_REQUEST['qb_FileID']."',";
		$qry .= "".$_REQUEST['qb_IsReadOnly'].",";
		$qry .= "".$_REQUEST['qb_Notify'].",";
		$qry .= "'".$_REQUEST['qb_OwnerID']."',";
		$qry .= "'".$_REQUEST['qb_PersonalDataPref']."',";
		$qry .= "'".$_REQUEST['qb_QBType']."',";
		$qry .= "'".$_REQUEST['qb_Scheduler']."',";
		$qry .= "'".$_REQUEST['qb_Style']."',";
		$qry .= "'".$_REQUEST['qb_UnattendedModePref']."',";
		$qry .= "'".$_REQUEST['qb_UserName']."'); ";
		$qry .= "select @@ERROR as ErrorCode;";
		$res  = mssql_query($qry);
		$row  = mssql_fetch_array($res);
		
		//echo $qry.'<br>';
	
		if ($row['ErrorCode']!=0)
		{
			echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
		}
	}
}

function update_AccountingXMLConfig($oid)
{
	$qry0 = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry  = "UPDATE offices SET ";	
	$qry .= "enexp='".$_REQUEST['xl_enexp']."',";
	$qry .= "enmas='".$_REQUEST['xl_enmas']."',";
	$qry .= "masimport='".$_REQUEST['xl_masimport']."',";
	$qry .= "exportserver='".$_REQUEST['xl_exportserver']."',";
	$qry .= "exportlogin='".$_REQUEST['xl_exportlogin']."',";
	$qry .= "exportpass='".$_REQUEST['xl_exportpass']."',";
	$qry .= "exportcatalog='".$_REQUEST['xl_exportcatalog']."',";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate() ";
	$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	//echo  $qry.'<br>';
	
	if ($row['ErrorCode']!=0)
	{
		echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
	}
}

function update_AccountingTypeConfig($oid)
{
	$qry0 = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry  = "UPDATE offices SET ";	
	$qry .= "accountingsystem='".$_REQUEST['ac_accountingsystem']."',";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate() ";
	$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	//echo  $qry.'<br>';
	
	if ($row['ErrorCode']!=0)
	{
		echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
	}
}

function get_MASAccountingConfig($oid)
{
	$qry = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	/*
	
	echo "<form id=\"frm_UpdateAccountingConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"ac_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "<input type=\"hidden\" id=\"ac_curr\" name=\"ac_curr\" value=\"".$row['accountingsystem']."\">\n";
	echo "         <table width=\"350px\">\n";
	
	if ($_SESSION['securityid']==26)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\"></td>\n";
		echo "               <td align=\"right\"><a id=\"submit_UpdateAccountingTypeConfig\" href=\"#\">Save Accounting Type <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save Accounting Type Configuration\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "            <tr>\n";
	echo "               <td align=\"right\">Accounting System</td>\n";
	echo "               <td>\n";
	echo "					<select name=\"accountingsystem\" id=\"ac_accountingsystem\">\n";
	
	if (isset($row['accountingsystem']) and $row['accountingsystem']==1)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\" SELECTED>MAS (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
		//echo "					<option value=\"99\">All</option>\n";
	}
	elseif (isset($row['accountingsystem']) and $row['accountingsystem']==2)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\">MAS (XML)</option>\n";
		echo "					<option value=\"2\" SELECTED>Quickbooks (qbXML)</option>\n";
		//echo "					<option value=\"99\">All</option>\n";
	}
	elseif (isset($row['accountingsystem']) and $row['accountingsystem']==99)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\">MAS (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
		echo "					<option value=\"99\" SELECTED>All</option>\n";
	}
	else
	{
		echo "					<option value=\"0\" SELECTED>None</option>\n";
		echo "					<option value=\"1\">MAS (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
		//echo "					<option value=\"99\">All</option>\n";
	}
	
	echo "					</select>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "		</table>\n";
	echo "		</form>\n";
	*/
		
	if (strtotime($row['masimport']) < strtotime('1/1/2004'))
	{
		$mdate = '';
	}
	else
	{
		$mdate = date("m/d/Y", strtotime($row['masimport']));
	}
	
	echo "<form id=\"frm_UpdateAccountingXMLConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"xl_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "         <table width=\"350px\">\n";		
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>MAS Export</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"enexp\" id=\"xl_enexp\">\n";

	if ($row['enexp']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>MAS Import</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"enmas\" id=\"xl_enmas\">\n";

	if ($row['enmas']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>MAS Import Date</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"xl_masimport\" name=\"masimport\" id=\"d5\" value=\"".$mdate."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Job Export Server</b></td>\n";
	//echo "               <td><input class=\"bboxb\" type=\"text\" id=\"xl_exportserver\" name=\"exportserver\" value=\"".$row['exportserver']."\" size=\"15\"> ".$srvstat[0]." ".$srvstat[1]."</td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"xl_exportserver\" name=\"exportserver\" value=\"".$row['exportserver']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Job Export Login ID</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"xl_exportlogin\" name=\"exportlogin\" value=\"".$row['exportlogin']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Job Export Password</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"xl_exportpass\" name=\"exportpass\" value=\"".$row['exportpass']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Job Export Catalog</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"xl_exportcatalog\" name=\"exportcatalog\" value=\"".$row['exportcatalog']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	
	if ($_SESSION['securityid']==26)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\"></td>\n";
		echo "               <td align=\"right\"><a id=\"submit_UpdateAccountingXMLConfig\" href=\"#\">Save <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save Accounting XML Configuration\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "		</table>\n";
	echo "		</form>\n";
}

function get_QBAccountingConfig($oid)
{
	$qry = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	/*
	echo "<form id=\"frm_UpdateAccountingConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"ac_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "<input type=\"hidden\" id=\"ac_curr\" name=\"ac_curr\" value=\"".$row['accountingsystem']."\">\n";
	echo "         <table width=\"350px\">\n";
	
	if ($_SESSION['securityid']==26)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\"></td>\n";
		echo "               <td align=\"right\"><a id=\"submit_UpdateAccountingTypeConfig\" href=\"#\">Save Accounting Type <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save Accounting Type Configuration\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "            <tr>\n";
	echo "               <td align=\"right\">Accounting System</td>\n";
	echo "               <td>\n";
	echo "					<select name=\"accountingsystem\" id=\"ac_accountingsystem\">\n";
	
	if (isset($row['accountingsystem']) and $row['accountingsystem']==1)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\" SELECTED>MAS (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
		//echo "					<option value=\"99\">All</option>\n";
	}
	elseif (isset($row['accountingsystem']) and $row['accountingsystem']==2)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\">MAS (XML)</option>\n";
		echo "					<option value=\"2\" SELECTED>Quickbooks (qbXML)</option>\n";
		//echo "					<option value=\"99\">All</option>\n";
	}
	elseif (isset($row['accountingsystem']) and $row['accountingsystem']==99)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\">MAS (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
		echo "					<option value=\"99\" SELECTED>All</option>\n";
	}
	else
	{
		echo "					<option value=\"0\" SELECTED>None</option>\n";
		echo "					<option value=\"1\">MAS (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
		//echo "					<option value=\"99\">All</option>\n";
	}
	
	echo "					</select>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "		</table>\n";
	echo "		</form>\n";
	*/

	$qryACCSYS = "SELECT * FROM qbwcConfig WHERE oid=".$oid.";";
	$resACCSYS = mssql_query($qryACCSYS);
	$rowACCSYS = mssql_fetch_array($resACCSYS);
	$nrowACCSYS= mssql_num_rows($resACCSYS);
	
	if ($nrowACCSYS > 1)
	{
		echo 'Config Error: Multiple Offices. Exiting.';
		exit;
	}
	
	echo "<form id=\"frm_UpdateAccQBXMLConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"qb_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "<input type=\"hidden\" id=\"qb_reccnt\" name=\"reccnt\" value=\"".$nrowACCSYS."\">\n";
	echo "         <table width=\"350px\">\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>AppDescription</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_AppDescription\" name=\"AppDescription\" value=\"".$rowACCSYS['AppDescription']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>AppDisplayName</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_AppDisplayName\" name=\"AppDisplayName\" value=\"".$rowACCSYS['AppDisplayName']."\" size=\"60\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>AppID</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_AppID\" name=\"AppID\" value=\"".$rowACCSYS['AppID']."\" size=\"60\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>AppName</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_AppName\" name=\"AppName\" value=\"".$rowACCSYS['AppName']."\" size=\"60\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>AppSupport</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_AppSupport\" name=\"AppSupport\" value=\"".$rowACCSYS['AppSupport']."\" size=\"60\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\">AppUniqueName</td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_AppUniqueName\" name=\"AppUniqueName\" value=\"".$rowACCSYS['AppUniqueName']."\" size=\"60\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>AppURL</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_AppURL\"  name=\"AppURL\" value=\"".$rowACCSYS['AppURL']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\">AuthFlags</td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_AuthFlags\" name=\"AuthFlags\" value=\"".$rowACCSYS['AuthFlags']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\">IsReadOnly</td>\n";
	
	if (isset($rowACCSYS['IsReadOnly']) and $rowACCSYS['IsReadOnly']==1)
	{
		echo "               <td><select id=\"qb_IsReadOnly\" name=\"IsReadOnly\"><option value=\"1\" SELECTED>Yes</option><option value=\"0\">No</option></select></td>\n";
	}
	else
	{
		echo "               <td><select id=\"qb_IsReadOnly\" name=\"IsReadOnly\"><option value=\"1\">Yes</option><option value=\"0\" SELECTED>No</option></select></td>\n";
	}
	
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\">Notify</td>\n";
	
	if (isset($rowACCSYS['Notify']) and $rowACCSYS['Notify']==1)
	{
		echo "               <td><select id=\"qb_Notify\" name=\"Notify\"><option value=\"1\" SELECTED>Yes</option><option value=\"0\">No</option></select></td>\n";
	}
	else
	{
		echo "               <td><select id=\"qb_Notify\" name=\"Notify\"><option value=\"1\">Yes</option><option value=\"0\" SELECTED>No</option></select></td>\n";
	}
	
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>OwnerID</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_OwnerID\" name=\"OwnerID\" value=\"".$rowACCSYS['OwnerID']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>FileID</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_FileID\" name=\"FileID\" value=\"".$rowACCSYS['FileID']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\">PersonalDataPref</td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_PersonalDataPref\" name=\"PersonalDataPref\" value=\"".$rowACCSYS['PersonalDataPref']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>QBType</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_QBType\" name=\"QBType\" value=\"".$rowACCSYS['QBType']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\">Scheduler</td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_Scheduler\" name=\"Scheduler\" value=\"".$rowACCSYS['Scheduler']."\" size=\"5\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\">Style</td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_Style\" name=\"Style\" value=\"".$rowACCSYS['Style']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\">UnattendedModePref</td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_UnattendedModePref\" name=\"UnattendedModePref\" value=\"".$rowACCSYS['UnattendedModePref']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>UserName</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_UserName\" name=\"UserName\" value=\"".$rowACCSYS['UserName']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"center\" colspan=\"2\"><hr width=\"80%\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>QB SOAP Host</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_SoapHost\" name=\"qb_SoapHost\" value=\"".$rowACCSYS['qb_soap_host']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>QB SOAP DB</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_SoapDB\" name=\"qb_SoapDB\" value=\"".$rowACCSYS['qb_soap_db']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>QB SOAP User</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_SoapUser\" name=\"qb_SoapUser\" value=\"".$rowACCSYS['qb_soap_user']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><font color=\"blue\"><b>QB SOAP Pass</b></font></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"qb_SoapPass\" name=\"qb_SoapPass\" value=\"".$rowACCSYS['qb_soap_pass']."\" size=\"45\"></td>\n";
	echo "            </tr>\n";
	
	if ($_SESSION['securityid']==26)
	{
		echo "            <tr>\n";
		echo "               <td align=\"left\"><a id=\"generate_AccountingQBXMLConfig\" target=\"new\" href=\"subs/xml_req.php?call=xml&subq=get_qbwcXML_config&oid=".$rowACCSYS['oid']."\"><img class=\"JMStooltip\" src=\"images/money_add.png\" title=\"Generate qbwcXML Configuration\"> Generate qbwcXML</a></td>\n";
		echo "               <td align=\"right\"><a id=\"submit_UpdateAccountingQBXMLConfig\" href=\"#\">Save qbwcXML Configuration<img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save Accounting/qbXML Configuration\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "		</table>\n";
	echo "		</form>\n";
}

function update_GeneralOfficeConfig($oid)
{
	$qry0 = "SELECT * FROM offices WHERE officeid='".$oid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT COUNT(id) as mcnt FROM zip_to_zip WHERE ozip='".$row0['zip']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry  = "UPDATE offices SET ";
	$qry .= "enest='".$_REQUEST['gc_enest']."', ";
	$qry .= "encost='".$_REQUEST['gc_encost']."', ";
	$qry .= "encon='".$_REQUEST['gc_encon']."', ";
	$qry .= "enjob='".$_REQUEST['gc_enjob']."', ";
	$qry .= "enmas='".$_REQUEST['gc_enmas']."', ";
	$qry .= "enexp='".$_REQUEST['gc_enexp']."', ";
	$qry .= "endigreport='".$_REQUEST['gc_endigreport']."', ";
	$qry .= "masimport='".$_REQUEST['gc_masimport']."', ";
	$qry .= "logging='".$_REQUEST['gc_logging']."', ";
	$qry .= "constructiondates='".$_REQUEST['gc_constructiondates']."', ";
	$qry .= "gmrjoin='".$_REQUEST['gc_gmrjoin']."', ";
	$qry .= "leadmail='".$_REQUEST['gc_leadmail']."', ";
	$qry .= "PurchaseOrder='".$_REQUEST['gc_PurchaseOrder']."', ";
	$qry .= "ldexport='".$_REQUEST['gc_ldexport']."', ";
	$qry .= "intro_etid='".$_REQUEST['gc_intro_etid']."', ";
	$qry .= "accountingsystem='".$_REQUEST['gc_accountingsystem']."', ";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate() ";
	$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	//echo  $qry.'<br>';
	
	if ($row['ErrorCode']!=0)
	{
		echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
	}
}

function get_GeneralOfficeConfig($oid)
{
	if ($_SESSION['tlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}

	if (isset($oid) && $oid!=0)
	{
		$officeid=$oid;
	}
	else
	{
		$officeid=$_REQUEST['officeid'];
	}

	$securityid=$_SESSION['securityid'];

	$qry = "SELECT O.*,isnull((select count(ozip) from zip_to_zip where ozip=O.zip),0) as zcnt FROM offices AS O WHERE O.officeid='".$officeid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($row['pb_code']==0)
	{
		$MAS='';
	}
	else
	{
		$MAS=$row['pb_code'];
	}

	$qryA = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resA = mssql_query($qryA);

	$qryD = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resD = mssql_query($qryD);

	$qryE = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT officeid,name FROM offices WHERE active='1' ORDER BY name;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT officeid,name,code,altcode FROM offices WHERE active='1' ORDER BY name;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT id,aid,item FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!='1' AND qtype='33' ORDER BY item;";
	$resH = mssql_query($qryH);
	
	$qryI = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['lupdate']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);
	
	$qryJ = "SELECT COUNT(id) as mcnt FROM zip_to_zip WHERE ozip='".$row['zip']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);
	
	$qryK = "SELECT securityid,lname,fname,substring(slevel,13,13) as tlevel FROM security WHERE officeid=89 and substring(slevel,13,13)>='1' ORDER BY lname;";
	$resK = mssql_query($qryK);
	
	$qry0 = "SELECT abrev,state FROM states ORDER BY abrev ASC;";
	$res0 = mssql_query($qry0);
	
	$qryP = "SELECT * FROM officegroupcodes ORDER BY seqn,name ASC;";
	$resP = mssql_query($qryP);
	
	$qryQ = "SELECT * FROM otypes ORDER BY otname ASC;";
	$resQ = mssql_query($qryQ);
	
	$qryR = "SELECT * FROM OfficeTypes ORDER BY otype_name ASC;";
	$resR = mssql_query($qryR);

	$mdate = date("m/d/Y", strtotime($row['masimport']));
	
	if ($row['lupdate']!=0)
	{
		$lupdate = $rowI['lname']." ".$rowI['fname']." (".date("m/d/Y h:m", strtotime($row['lupdtime'])).")";
	}
	else 
	{
		$lupdate = '&nbsp;';
	}
	
	if ($row['zcnt']!=0 && strlen($row['ringto'])!=10)
	{
		$zmerrcnt=1;
		$zmerrtxt="<font color=\"red\"><b>Matrix Config Invalid!</b></font><br>Office has Matrix Ties but improper Ringto. Calls will not be processed properly";
	}
	else
	{
		$zmerrcnt=0;
		$zmerrtxt='';
	}
	
	echo "<form id=\"frm_UpdateGeneralOfficeConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"gc_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "         <table width=\"350px\">\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\"></td>\n";
		echo "               <td align=\"right\"><a id=\"submit_UpdateGeneralOfficeConfig\" href=\"#\">Save <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save System Configuration\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Estimates/Quotes</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"enest\" id=\"gc_enest\">\n";

	if ($row['enest']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Contracts</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"encon\" id=\"gc_encon\">\n";

	if ($row['encon']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Jobs</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"enjob\" id=\"gc_enjob\">\n";

	if ($row['enjob']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Costing</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"encost\" id=\"gc_encost\">\n";

	if ($row['encost']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Dig Report</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"endigreport\" id=\"gc_endigreport\">\n";

	if ($row['endigreport']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
		echo "                     <option value=\"2\">Exclude</option>\n";
	}
	elseif ($row['endigreport']==1)
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
		echo "                     <option value=\"2\">Exclude</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
		echo "                     <option value=\"2\" SELECTED>Exclude</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Lead Import/Export</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"ldexport\" id=\"gc_ldexport\">\n";

	if ($row['ldexport']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Lead CC Email</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"leadmail\" id=\"gc_leadmail\">\n";

	if ($row['leadmail']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Logging</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"logging\" id=\"gc_logging\">\n";

	if ($row['logging']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>OpState Join</b></td>\n";
	echo "               <td><input class=\"bboxb\" type=\"text\" id=\"gc_gmrjoin\" name=\"gmrjoin\" value=\"".$row['gmrjoin']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\"><b>Purchase Order</b></td>\n";
	echo "				<td align=\"left\">\n";
	echo "					<select name=\"PurchaseOrder\"  id=\"gc_PurchaseOrder\">\n";

	for ($po=9;$po>=0;$po--)
	{
		if ($row['PurchaseOrder']==$po)
		{
			echo "								<option value=\"".$po."\" SELECTED>".$po."</option>\n";	
		}
		else
		{
			echo "								<option value=\"".$po."\">".$po."</option>\n";	
		}
	}

	echo "					</select>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	
	//$qryETID = "SELECT etid,name FROM EmailTemplate WHERE (oid=".$row['officeid']." or oid=0) AND active = 6 ORDER BY name ASC;";
	$qryETID = "SELECT etid,name FROM EmailTemplate WHERE (oid=".$row['officeid']." or oid=0) and active!=0 ORDER BY name ASC;";
	$resETID = mssql_query($qryETID);
	
	echo "			<tr>\n";
	echo "				<td align=\"right\"><b>Introductory Email</b></td>\n";
	echo "				<td align=\"left\">\n";
	echo "					<select name=\"intro_etid\" id=\"gc_intro_etid\">\n";
	
	if ($row['intro_etid']==0)
	{
		echo "								<option value=\"0\" SELECTED>None</option>\n";	
	}
	else
	{
		echo "								<option value=\"0\">None</option>\n";	
	}

	while ($rowETID=mssql_fetch_array($resETID))
	{
		if ($row['intro_etid']==$rowETID['etid'])
		{
			echo "								<option value=\"".$rowETID['etid']."\" SELECTED>".$rowETID['name']."</option>\n";	
		}
		else
		{
			echo "								<option value=\"".$rowETID['etid']."\">".$rowETID['name']."</option>\n";	
		}
	}

	echo "					</select>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Time Shift</b></td>\n";
	echo "               <td><input class=\"bboxb JMStooltip\" type=\"text\" id=\"gc_timeshift\" name=\"timeshift\" value=\"".$row['timeshift']."\" size=\"15\" title=\"Sets the Time Difference between the JMS System and the Office in seconds\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Accounting System</b></td>\n";
	echo "               <td>\n";
	echo "					<select name=\"accountingsystem\" id=\"gc_accountingsystem\">\n";
	
	if (isset($row['accountingsystem']) and $row['accountingsystem']==1)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\" SELECTED>MAS (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
		//echo "					<option value=\"99\">All</option>\n";
	}
	elseif (isset($row['accountingsystem']) and $row['accountingsystem']==2)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\">MAS (XML)</option>\n";
		echo "					<option value=\"2\" SELECTED>Quickbooks (qbXML)</option>\n";
		//echo "					<option value=\"99\">All</option>\n";
	}
	elseif (isset($row['accountingsystem']) and $row['accountingsystem']==99)
	{
		echo "					<option value=\"0\">None</option>\n";
		echo "					<option value=\"1\">MAS (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
		echo "					<option value=\"99\" SELECTED>All</option>\n";
	}
	else
	{
		echo "					<option value=\"0\" SELECTED>None</option>\n";
		echo "					<option value=\"1\">MAS (XML)</option>\n";
		echo "					<option value=\"2\">Quickbooks (qbXML)</option>\n";
		//echo "					<option value=\"99\">All</option>\n";
	}
	
	echo "					</select>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Construction Dates</b></td>\n";
	echo "               <td>\n";
	echo "                  <select name=\"constructiondates\" id=\"gc_constructiondates\">\n";

	if ($row['constructiondates']==0)
	{
		echo "                     <option value=\"0\" SELECTED>Disabled</option>\n";
		echo "                     <option value=\"1\">Enabled</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">Disabled</option>\n";
		echo "                     <option value=\"1\" SELECTED>Enabled</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	
	
	echo "         </table>\n";
	echo "		</form>\n";
}

function update_FileStorageConfig($oid)
{
	$qry0 = "SELECT * FROM offices WHERE officeid='".$oid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry  = "UPDATE offices SET ";	
	$qry .= "fslimit='".$_REQUEST['fs_fslimit']."', ";
	$qry .= "fscustomer='".$_REQUEST['fs_fscustomer']."', ";
	$qry .= "fsshared='".$_REQUEST['fs_fsshared']."', ";
	$qry .= "fsoffice='".$_REQUEST['fs_fsoffice']."', ";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate() ";
	$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	//echo  $qry.'<br>';
	
	if ($row['ErrorCode']!=0)
	{
		echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
	}
}

function get_FileStorageConfig($oid)
{
	if ($_SESSION['tlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}

	$qry = "SELECT * FROM offices WHERE officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	echo "<form id=\"frm_UpdateFileStorageConfig\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"fs_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	echo "         <table width=\"350px\">\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\"></td>\n";
		echo "               <td align=\"right\"><a id=\"submit_UpdateFileStorageConfig\" href=\"#\">Save <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save File Storage Configuration\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "            <tr>\n";
	echo "               <td align=\"right\" valign=\"top\"><b>File Storage</b></td>\n";
	echo "               <td>\n";
	echo "					<table>\n";
	echo "            			<tr>\n";
	echo "							<td align=\"center\">Customer</td>\n";
	echo "							<td align=\"center\">Shared</td>\n";
	echo "							<td align=\"center\">Office</td>\n";
	echo "            			</tr>\n";
	echo "            			<tr>\n";
	echo "							<td align=\"center\">\n";
	echo "								<select name=\"fscustomer\" id=\"fs_fscustomer\">\n";
	
	if ($row['fscustomer']==0)
	{
		echo "									<option value=\"0\" SELECTED>No</option>\n";
		echo "									<option value=\"1\">Yes</option>\n";
	}
	else
	{
		echo "									<option value=\"0\">No</option>\n";
		echo "									<option value=\"1\" SELECTED>Yes</option>\n";
	}
	
	echo "								</select>\n";
	echo "							</td>\n";
	echo "							<td align=\"center\">\n";
	echo "								<select name=\"fsshared\" id=\"fs_fsshared\">\n";
	
	if ($row['fsshared']==0)
	{
		echo "									<option value=\"0\" SELECTED>No</option>\n";
		echo "									<option value=\"1\">Yes</option>\n";
	}
	else
	{
		echo "									<option value=\"0\">No</option>\n";
		echo "									<option value=\"1\" SELECTED>Yes</option>\n";
	}
	
	echo "								</select>\n";
	echo "							</td>\n";
	echo "							<td align=\"center\">\n";
	echo "								<select name=\"fsoffice\" id=\"fs_fsoffice\">\n";
	
	if ($row['fsoffice']==0)
	{
		echo "									<option value=\"0\" SELECTED>No</option>\n";
		echo "									<option value=\"1\">Yes</option>\n";
	}
	else
	{
		echo "									<option value=\"0\">No</option>\n";
		echo "									<option value=\"1\" SELECTED>Yes</option>\n";
	}
	
	echo "								</select>\n";
	echo "							</td>\n";
	echo "            			</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\" valign=\"top\"><b>File Storage Limit</b></td>\n";
	echo "               <td><input class=\"bboxbr\" type=\"text\" id=\"fs_fslimit\" name=\"fslimit\" value=\"".$row['fslimit']."\" size=\"15\"> Mb</td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "		</form>\n";
}

function update_RequestTST($oid)
{
	echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';
}

function update_GeneralOfficeInfo($oid)
{	
	$qry0 = "SELECT * FROM offices WHERE officeid='".$oid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry  = "UPDATE offices SET ";
	$qry .= "active='".$_REQUEST['gi_active']."',";
	$qry .= "name='".$_REQUEST['gi_name']."',";
	$qry .= "label_masoff_code='".$_REQUEST['gi_label_masoff_code']."',";
	$qry .= "addr1='".$_REQUEST['gi_addr1']."',";
	$qry .= "addr2='".$_REQUEST['gi_addr2']."',";
	$qry .= "city='".$_REQUEST['gi_city']."',";
	$qry .= "state='".$_REQUEST['gi_state']."',";
	$qry .= "phone='".$_REQUEST['gi_phone']."',";
	$qry .= "fax='".$_REQUEST['gi_fax']."',";
	$qry .= "gm='".$_REQUEST['gi_gm']."',";
	$qry .= "sm='".$_REQUEST['gi_sm']."',";
	$qry .= "am='".$_REQUEST['gi_am']."',";
	$qry .= "leadforward='".$_REQUEST['gi_leadforward']."',";
	$qry .= "processor='".$_REQUEST['gi_processor']."', ";
	$qry .= "otype='".$_REQUEST['gi_otype']."', ";
	$qry .= "otype_code='".$_REQUEST['gi_otype_code']."', ";
	$qry .= "conlicense='".$_REQUEST['gi_conlicense']."', ";
	$qry .= "grouping='".$_REQUEST['gi_grouping']."', ";
	$qry .= "csrep='".$_REQUEST['gi_csrep']."', ";
	$qry .= "finan_off='".$_REQUEST['gi_finan_off']."', ";
	$qry .= "lupdate='".$_SESSION['securityid']."', ";
	$qry .= "lupdtime=getdate() ";
	$qry .= " WHERE officeid=".$oid."; select @@ERROR as ErrorCode";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	if ($row['ErrorCode']!=0)
	{
		echo "SQL Query failed: " . mssql_get_last_message() . "<br />\n";
	}
}

function get_GeneralOfficeInfo($oid)
{
	if ($_SESSION['tlev'] < 7)
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Resource</b>";
		exit;
	}

	if (isset($oid) && $oid!=0)
	{
		$officeid=$oid;
	}
	else
	{
		$officeid=$_REQUEST['officeid'];
	}

	$securityid=$_SESSION['securityid'];

	$qry = "SELECT O.*,isnull((select count(ozip) from zip_to_zip where ozip=O.zip),0) as zcnt FROM offices AS O WHERE O.officeid='".$officeid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($row['pb_code']==0)
	{
		$MAS='';
	}
	else
	{
		$MAS=$row['pb_code'];
	}

	$qryA = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resA = mssql_query($qryA);

	$qryD = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resD = mssql_query($qryD);

	$qryE = "SELECT securityid,lname,fname,substring(slevel,13,1) as slev FROM security WHERE officeid='".$officeid."' ORDER BY substring(slevel,13,1) desc,lname asc;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT officeid,name FROM offices WHERE active='1' ORDER BY name;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT officeid,name,code,altcode FROM offices WHERE active='1' ORDER BY name;";
	$resG = mssql_query($qryG);

	$qryH = "SELECT id,aid,item FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND disabled!='1' AND qtype='33' ORDER BY item;";
	$resH = mssql_query($qryH);
	
	$qryI = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['lupdate']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);
	
	$qryJ = "SELECT COUNT(id) as mcnt FROM zip_to_zip WHERE ozip='".$row['zip']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);
	
	$qryK = "SELECT securityid,lname,fname,substring(slevel,13,13) as tlevel FROM security WHERE officeid=89 and substring(slevel,13,13)>='1' ORDER BY lname;";
	$resK = mssql_query($qryK);
	
	$qry0 = "SELECT abrev,state FROM states ORDER BY abrev ASC;";
	$res0 = mssql_query($qry0);
	
	$qryP = "SELECT * FROM officegroupcodes ORDER BY seqn,name ASC;";
	$resP = mssql_query($qryP);
	
	$qryQ = "SELECT * FROM otypes ORDER BY otname ASC;";
	$resQ = mssql_query($qryQ);
	
	$qryR = "SELECT * FROM OfficeTypes ORDER BY otype_name ASC;";
	$resR = mssql_query($qryR);

	$mdate = date("m/d/Y", strtotime($row['masimport']));
	
	if ($row['lupdate']!=0)
	{
		$lupdate = $rowI['lname']." ".$rowI['fname']." (".date("m/d/Y h:m", strtotime($row['lupdtime'])).")";
	}
	else 
	{
		$lupdate = '&nbsp;';
	}
	
	if ($row['zcnt']!=0 && strlen($row['ringto'])!=10)
	{
		$zmerrcnt=1;
		$zmerrtxt="<font color=\"red\"><b>Matrix Config Invalid!</b></font><br>Office has Matrix Ties but improper Ringto. Calls will not be processed properly";
	}
	else
	{
		$zmerrcnt=0;
		$zmerrtxt='';
	}
	
	echo "<form id=\"frm_UpdateGeneralOfficeInfo\" method=\"post\">\n";
	echo "<input type=\"hidden\" id=\"gi_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
	
	echo "         <table width=\"350px\">\n";
	
	if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
	{
		echo "            <tr>\n";
		echo "               <td align=\"right\"></td>\n";
		echo "               <td align=\"right\"><a id=\"submit_UpdateGeneralOfficeInfo\" href=\"#\">Save <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save General Information\"></a></td>\n";
		echo "            </tr>\n";
	}
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Office ID</b></td>\n";
	echo "               <td align=\"left\">".$row['officeid']."</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Name</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"gi_name\" name=\"name\" value=\"".trim($row['name'])."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Office Code (Label)</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"gi_label_masoff_code\" name=\"label_masoff_code\" value=\"".trim($row['label_masoff_code'])."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Address</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"gi_addr1\" name=\"addr1\" value=\"".$row['addr1']."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"gi_addr2\" name=\"addr2\" value=\"".$row['addr2']."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>City</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"gi_city\" name=\"city\" value=\"".$row['city']."\" size=\"25\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>State</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "					<select name=\"state\" id=\"gi_state\">\n";
	
	while ($row0 = mssql_fetch_array($res0))
	{
		if ($row0['abrev']==$row['state'])
		{
			echo "<option value=\"".$row0['abrev']."\" SELECTED>".$row0['abrev']."</option>\n";
		}
		else
		{
			echo "<option value=\"".$row0['abrev']."\">".$row0['abrev']."</option>\n";
		}
	}
	
	echo "					</select>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Active</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"active\" id=\"gi_active\">\n";

	if ($row['active']==1)
	{
		echo "							<option value=\"1\" SELECTED>Yes</option>\n";
		echo "							<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "							<option value=\"1\">Yes</option>\n";
		echo "							<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Admin Only</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"adminonly\" id=\"gi_adminonly\">\n";

	if ($row['adminonly']==1)
	{
		echo "							<option value=\"1\" SELECTED>Yes</option>\n";
		echo "							<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "							<option value=\"1\">Yes</option>\n";
		echo "							<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Finance Office</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"finan_off\" id=\"gi_finan_off\">\n";

	if ($row['finan_off']==1)
	{
		echo "							<option value=\"1\" SELECTED>Yes</option>\n";
		echo "							<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "							<option value=\"1\">Yes</option>\n";
		echo "							<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Grouping</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"grouping\" id=\"gi_grouping\">\n";

	while ($rowP=mssql_fetch_array($resP))
	{
		if ($row['grouping']==$rowP['code'])
		{
			echo "							<option value=\"".$rowP['code']."\" SELECTED>".$rowP['name']."</option>\n";
		}
		else
		{
			echo "							<option value=\"".$rowP['code']."\">".$rowP['name']."</option>\n";
		}
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Ownership</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"otype_code\" id=\"gi_otype_code\">\n";
	echo "							<option value=\"0\">NA</option>\n";

	while ($rowR=mssql_fetch_array($resR))
	{
		if ($row['otype_code']==$rowR['id'])
		{
			echo "							<option value=\"".$rowR['id']."\" SELECTED>".$rowR['otype_name']."</option>\n";
		}
		else
		{
			echo "							<option value=\"".$rowR['id']."\">".$rowR['otype_name']."</option>\n";
		}
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>System Type</b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"otype\" id=\"gi_otype\">\n";

	while ($rowQ=mssql_fetch_array($resQ))
	{
		if ($row['otype']==$rowQ['otid'])
		{
			echo "					<option value=\"".$rowQ['otid']."\" SELECTED>".$rowQ['otname']."</option>\n";
		}
		else
		{
			echo "					<option value=\"".$rowQ['otid']."\">".$rowQ['otname']."</option>\n";
		}
	}

	echo "                  </select>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Accounting Code</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"gi_code\" value=\"".$row['code']."\" size=\"20\" DISABLED></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Contractor Lic</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"gi_conlicense\" value=\"".trim($row['conlicense'])."\" name=\"conlicense\" size=\"20\"></td>\n";
	echo "            </tr>\n";	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Phone</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"gi_phone\" name=\"phone\" value=\"".$row['phone']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Fax</b></td>\n";
	echo "               <td align=\"left\"><input class=\"bboxb\" type=\"text\" id=\"gi_fax\" name=\"fax\" value=\"".$row['fax']."\" size=\"15\"></td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>GM</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"gm\" id=\"gi_gm\">\n";

	if ($row['gm']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
	}

	while ($rowA=mssql_fetch_array($resA))
	{
		if ($rowA['slev']==0)
		{
			$ostyle="fontred";
		}
		else
		{
			$ostyle="fontblack";
		}
		
		if ($rowA['securityid']==$row['gm'])
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowA['securityid']."\" SELECTED>".$rowA['lname']." ".$rowA['fname']."</option>\n";
		}
		else
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowA['securityid']."\">".$rowA['lname']." ".$rowA['fname']."</option>\n";
		}
	}
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>SM</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"sm\" id=\"gi_sm\">\n";

	if ($row['sm']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
	}

	while ($rowD=mssql_fetch_array($resD))
	{
		if ($rowD['slev']==0)
		{
			$ostyle="fontred";
		}
		else
		{
			$ostyle="fontblack";
		}
		
		if ($rowD['securityid']==$row['sm'])
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowD['securityid']."\" SELECTED>".$rowD['lname']." ".$rowD['fname']."</option>\n";
		}
		else
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowD['securityid']."\">".$rowD['lname']." ".$rowD['fname']."</option>\n";
		}
	}
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Lead Admin</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"am\" id=\"gi_am\">\n";

	if ($row['am']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
	}

	while ($rowE=mssql_fetch_array($resE))
	{
		if ($rowE['slev']==0)
		{
			$ostyle="fontred";
		}
		else
		{
			$ostyle="fontblack";
		}
		
		if ($rowE['securityid']==$row['am'])
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowE['securityid']."\" SELECTED>".$rowE['lname']." ".$rowE['fname']."</option>\n";
		}
		else
		{
			echo "                     <option class=\"".$ostyle."\" value=\"".$rowE['securityid']."\">".$rowE['lname']." ".$rowE['fname']."</option>\n";
		}
	}
	
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Processor</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"processor\" id=\"gi_processor\">\n";
	echo "                     <option value=\"0\">None</option>\n";

	while ($rowK=mssql_fetch_array($resK))
	{
		if ($rowK['securityid']==$row['processor'])
		{
			echo "                     <option value=\"".$rowK['securityid']."\" SELECTED>".$rowK['fname']." ".$rowK['lname']."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowK['securityid']."\">".$rowK['fname']." ".$rowK['lname']."</option>\n";
		}
	}
	
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	
	$qryCSREP = "SELECT securityid,lname,fname,substring(slevel,13,1) FROM security WHERE officeid='".$row['officeid']."' AND substring(slevel,13,1) >= 1 ORDER BY lname;";
	$resCSREP = mssql_query($qryCSREP);
	
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Cust Service Rep</b></td>\n";
	echo "               <td>\n";
	echo "               	<select name=\"csrep\" id=\"gi_csrep\">\n";
	echo "                     <option value=\"0\">None</option>\n";
		
	while ($rowCSREP=mssql_fetch_array($resCSREP))
	{
		if ($rowCSREP['securityid']==$row['csrep'])
		{
			echo "                     <option value=\"".$rowCSREP['securityid']."\" SELECTED>".$rowCSREP['lname'].", ".$rowCSREP['fname']."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowCSREP['securityid']."\">".$rowCSREP['lname'].", ".$rowCSREP['fname']."</option>\n";
		}
	}
		
	echo "               	</select>\n";
	echo "					</td>\n";
	echo "			</tr>\n";
	echo "            <tr>\n";
	echo "               <td align=\"right\"><b>Leads Forward to</b></b></td>\n";
	echo "               <td align=\"left\">\n";
	echo "                  <select name=\"leadforward\" id=\"gi_leadforward\">\n";

	if ($row['leadforward']==0)
	{
		echo "                     <option value=\"0\" SELECTED>None</option>\n";
	}
	else
	{
		echo "                     <option value=\"0\">None</option>\n";
	}

	while ($rowF=mssql_fetch_array($resF))
	{
		if ($rowF['officeid']==$row['leadforward'])
		{
			echo "                     <option value=\"".$rowF['officeid']."\" SELECTED>".$rowF['name']."</option>\n";
		}
		else
		{
			echo "                     <option value=\"".$rowF['officeid']."\">".$rowF['name']."</option>\n";
		}
	}
	echo "                  </select>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "			</table>\n";
	echo "</form>\n";
}

function get_PaymentScheduleConfig($oid)
{
	$qry = "SELECT O.* FROM offices AS O WHERE O.officeid=".$oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	echo "         <table width=\"350px\">\n";
	echo "            <tr>\n";
	echo "               <td>\n";
	//echo $row['psched'];

	$psched	=explode(",",$row['psched']);
	$pperc	=explode(",",$row['psched_perc']);
	$pcnt	=count($psched);

	if (is_array($psched) && $psched[0]!=0)
	{
		echo "		<form id=\"frm_DeletePayScheduleConfig\" method=\"post\">\n";
		echo "		<input type=\"hidden\" id=\"dps_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
		echo "<table width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\" colspan=\"3\"><b>Current Payment Schedule</b></td>\n";
		echo "            </tr>\n";

		foreach ($psched as $n1 => $v1)
		{
			//echo $v1."<br>";
			$qryZ		="SELECT phsname,phscode FROM phasebase WHERE phscode='".$v1."';";
			$resZ		= mssql_query($qryZ);
			$rowZ		= mssql_fetch_array($resZ);

			echo "<tr>\n";
			echo "	<td align=\"left\">".$rowZ['phscode']."</td>\n";
			echo "	<td align=\"left\">".$rowZ['phsname']."</td>\n";
			echo "	<td align=\"right\"><b>".$pperc[$n1]."</b></td>\n";
			echo "</tr>\n";
		}

		if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
		{
			echo "            <tr>\n";
			echo "               <td align=\"right\" colspan=\"3\"><hr width=\"100%\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\" colspan=\"3\"><a id=\"submit_DeletePaymentScheduleConfig\" href=\"#\">Delete <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to Delete Payment Schedule Configuration\"></a></td>\n";
			echo "            </tr>\n";
		}
		
		echo "</table>\n";
		echo "</form>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
	}
	else
	{
		$qryZa = "SELECT * FROM phasebase ORDER BY seqnum ASC;";
		$resZa = mssql_query($qryZa);

		echo "		<form id=\"frm_UpdatePayScheduleConfig\" method=\"post\">\n";
		echo "		<input type=\"hidden\" id=\"ps_oid\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
		echo "         <table width=\"100%\">\n";		
		echo "            <tr>\n";
		echo "               <td colspan=\"2\" align=\"left\"><font color=\"red\"><b>Payment Schedule has not been configured for this Office</b></font></td>\n";
		echo "            </tr>\n";
		echo "			<tr>\n";
		echo "				<td align=\"left\">Phase Selection</td>\n";
		echo "				<td align=\"right\">\n";
		echo "					<select id=\"sel_PhaseCode\">\n";
		
		while ($rowZa = mssql_fetch_array($resZa))
		{
			echo "<option value=\"".$rowZa['phscode']."\">".$rowZa['phscode']." ".$rowZa['phsname']."</option>\n";
		}
		
		echo "					</select>\n";
		echo "					<a id=\"add_PhaseCode\" href=\"#\"><img src=\"images/add.png\" title=\"Add Phase\"></a>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td align=\"left\" colspan=\"2\">Proposed Pay Schedule:</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td align=\"right\" colspan=\"2\">\n";
		echo "					<p>\n";
		echo "					<div id=\"PaySchedContainer\"></div>\n";
		echo "					</p>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
		
		if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332)
		{
			echo "            <tr>\n";
			echo "               <td align=\"right\" colspan=\"2\"><hr width=\"100%\"></td>\n";
			echo "            </tr>\n";
			echo "            <tr>\n";
			echo "               <td align=\"right\"></td>\n";
			echo "               <td align=\"right\"><a id=\"submit_UpdatePaymentScheduleConfig\" href=\"#\">Save <img class=\"JMStooltip\" src=\"images/save.gif\" title=\"Click to save Payment Schedule Configuration\"></a></td>\n";
			echo "            </tr>\n";
		}
		
		echo "         </table>\n";
		echo "		</form>\n";
	}
}

?>