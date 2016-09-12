<?php

function OS_remod_div_select($c,$d) {
	/*$d=array('NA');
	$qryR = "SELECT Division FROM MAS_".$c."..ARB_DivisionMasterfile WHERE Division BETWEEN 97 and 99;";
	$resR = mssql_query($qryR);
	$nrowR= mssql_num_rows($resR);
	
	if ($nrowR > 0)
	{
		while ($rowR = mssql_fetch_array($resR))
		{
			$d[]=$rowR['Division'];
		}
	}
	
	if (is_array($d))
	{
		echo "<select name=\"remodeldiv\">\n";
		
		foreach ($d as $n => $v)
		{
			if (isset($_REQUEST['remodeldiv']) && $_REQUEST['remodeldiv']==$v)
			{
				echo "<option value=\"".$v."\" SELECTED>".$v."</option>";
			}
			else
			{
				echo "<option value=\"".$v."\">".$v."</option>";
			}
		}
		
		echo "</select>\n";
	}*/
	
	$qryR = "SELECT remodeldiv FROM ZE_Stats..divtocomp WHERE company='".$c."' and division='".$d."';";
	$resR = mssql_query($qryR);
	$rowR = mssql_fetch_array($resR);
	
	echo $rowR['remodeldiv'];
	echo "<input type=\"hidden\" name=\"remodeldiv\" value=\"".$rowR['remodeldiv']."\">\n";
	
}


function officeconfig_add()
{
	if ($_SESSION['officeid']!=89)
	{
		echo "You do not have appropriate permission to use this resource.";
		exit;
	}
	
	$hostname = "192.168.1.22";
	$username = "jc_rw";
	$password = "jc_rw";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
	$qry0 = "SELECT dcid FROM divtocomp WHERE company=".$_REQUEST['company']." AND division=".$_REQUEST['division'].";";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if (!is_numeric($_REQUEST['company']) && !is_numeric($_REQUEST['division']))
	{
		echo "Company: ".$_REQUEST['company']." and Division: ".$_REQUEST['division']." must be numeric.<br/>";
	}
	elseif ($nrow0 > 0)
	{
		echo "Company: ".$_REQUEST['company']." and Division: ".$_REQUEST['division']." already exists.<br/>";
	}
	else
	{
		$qry0a = "INSERT INTO divtocomp (company,division,enabled,type,opstate,openjobs,closedjobs,huntenperc,cib,remodeldiv) VALUES ('".$_REQUEST['company']."','".$_REQUEST['division']."',".$_REQUEST['enabled'].",".$_REQUEST['type'].",".$_REQUEST['opstate'].",".$_REQUEST['openjobs'].",".$_REQUEST['closedjobs'].",".$_REQUEST['huntenperc'].",".$_REQUEST['cib'].",'".$_REQUEST['remodeldiv']."');";
		$res0a = mssql_query($qry0a);
		
		//echo $qry0a."<br>";
	}
	
	officeconfig();
}

function officeconfig_del()
{
	error_reporting(E_ALL);
	if ($_SESSION['officeid']!=89)
	{
		echo "You do not have appropriate permission to use this resource.";
		exit;
	}
	
	$hostname = "192.168.1.22";
	$username = "jc_rw";
	$password = "jc_rw";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
	$qry0 = "SELECT dcid FROM divtocomp WHERE dcid=".$_REQUEST['dcid'].";";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 > 0 && isset($_REQUEST['confirmdelete']) && $_REQUEST['confirmdelete']==1)
	{
		$qry0a = "DELETE FROM divtocomp WHERE dcid=".$_REQUEST['dcid'].";";
		$res0a = mssql_query($qry0a);
		
		//echo $qry0a."<br>";
	}
	
	officeconfig();
}

function officeconfig_upd()
{
	if ($_SESSION['officeid']!=89)
	{
		echo "You do not have appropriate permission to use this resource.";
		exit;
	}
	
	$hostname = "192.168.1.22";
	$username = "jc_rw";
	$password = "jc_rw";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
	$qry0 = "SELECT dcid FROM divtocomp WHERE dcid=".$_REQUEST['dcid'].";";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		if ($_REQUEST['enabled']==1)
		{
			$os	= $_REQUEST['opstate'];
			$oj = $_REQUEST['openjobs'];
			$cj	= $_REQUEST['closedjobs'];
			$hp = $_REQUEST['huntenperc'];
			$cb = $_REQUEST['cib'];
			$rd = $_REQUEST['remodeldiv'];
		}
		else
		{
			$os	= 0;
			$oj = 0;
			$cj	= 0;
			$hp = 0;
			$cb = 0;
			$rd = 'NA';
		}
		
		$qry0a  = "UPDATE divtocomp SET ";
		$qry0a .= "type=".$_REQUEST['type'].",";
		$qry0a .= "enabled=".$_REQUEST['enabled'].",";
		$qry0a .= "opstate=".$os.",";
		$qry0a .= "openjobs=".$oj.",";
		$qry0a .= "closedjobs=".$cj.",";
		$qry0a .= "huntenperc=".$hp.",";
		$qry0a .= "cib=".$cb.", ";
		$qry0a .= "remodeldiv=".$rd." ";
		$qry0a .= "WHERE dcid=".$_REQUEST['dcid'].";";
		$res0a = mssql_query($qry0a);
		
		//echo $qry0a."<br>";
	}
	
	officeconfig();
}

function officeconfig()
{
	if ($_SESSION['officeid']!=89)
	{
		echo "You do not have appropriate permission to use this resource.";
		exit;
	}
	
	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
	if (isset($_REQUEST['osort']) && $_REQUEST['osort']=='rp')
	{
		$osort="type asc,company asc,division asc";
	}
	else
	{
		$osort="company asc,division asc";
	}
	
	$qry0 = "SELECT * FROM divtocomp ORDER by ".$osort.";";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	$qry1 = "SELECT distinct(type) FROM divtocomp ORDER by type asc;";
	$res1 = mssql_query($qry1);
	
	while ($row1 = mssql_fetch_array($res1))
	{
		$type_ar[]=$row1['type'];
	}
	
	$typetxt_ar=array(1=>'PA',2=>'FT',3=>'FR');
	$remodel_ar=array('NA',97,98,99);
	
	
	echo "<table align=\"center\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
	echo "         <table class=\"outer\" width=\"100%\" border=0>\n";
	echo "   			<tr>\n";
	echo "					<td class=\"gray\" align=\"left\"><b>Company/Division Config Matrix</b></td>\n";
	echo "					<form name=\"offsort\" action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
	echo "					<input type=\"hidden\" name=\"subq\" value=\"officeconfig\">\n";
	echo "					<td class=\"gray\" align=\"right\">Sort \n";
	echo "						<select name=\"osort\" onChange=\"this.form.submit();\">\n";

	if (isset($_REQUEST['osort']) && $_REQUEST['osort']=='rp')
	{
		echo "						<option value=\"cd\">Comp/Div</option>\n";
		echo "						<option value=\"rp\" SELECTED>Report</option>\n";
	}
	else
	{
		echo "						<option value=\"cd\" SELECTED>Comp/Div</option>\n";
		echo "						<option value=\"rp\">Report</option>\n";
	}
	
	echo "						</select>\n";
	echo "					</td>\n";
	echo "					</form>\n";
	echo "   			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "   <tr>\n";
	echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
	echo "         <table class=\"outer\" width=\"100%\" border=0>\n";
	echo "   			<tr>\n";
	echo "      			<td class=\"wh\" align=\"center\" colspan=\"6\"><b></b></td>\n";
	echo "      			<td class=\"wh_undsidesl\" align=\"center\" colspan=\"5\"><b>Reports</b></td>\n";
	echo "      			<td class=\"wh_undsidesl\" align=\"right\" colspan=\"3\"><b></b></td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b></b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b>Company</b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b>Division</b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b>Type</b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b>Enabled</b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b>Remodel</b></td>\n";
	echo "      			<td class=\"wh_undsidesl\" align=\"center\"><b>OpState</b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b>OJobs</b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b>CJobs</b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b>110%</b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b>CiB</b></td>\n";
	echo "      			<td class=\"wh_undsidesl\" align=\"center\"><b></b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b></b></td>\n";
	echo "      			<td class=\"wh_und\" align=\"center\"><b></b></td>\n";
	echo "   			</tr>\n";
	echo "				<form name=\"add\" target=\"_top\" method=\"post\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
	echo "				<input type=\"hidden\" name=\"subq\" value=\"officeconfig_add\">\n";
	echo "   			<tr>\n";
	echo "      			<td class=\"gray_und\" align=\"right\"><b>Add</b></td>\n";
	echo "      			<td class=\"gray_und\" align=\"right\"><input type=\"text\" name=\"company\" size=\"5\" maxlength=\"3\"></td>\n";
	echo "      			<td class=\"gray_und\" align=\"right\"><input type=\"text\" name=\"division\" size=\"5\" maxlength=\"2\"></td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\">\n";
	
	if (is_array($type_ar))
	{
		echo "<select name=\"type\">\n";
		echo "	<option value=\"0\"></option>\n";
		
		foreach ($type_ar as $nt => $vt)
		{			
			echo "	<option value=\"".$vt."\">".$typetxt_ar[$vt]."</option>\n";
		}
		
		echo "</select>\n";
	}
	
	echo "					</td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\">\n";
	echo "						<select name=\"enabled\">\n";
	echo "							<option value=\"1\">Yes</option>\n";
	echo "							<option value=\"0\">No</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\">\n";
	
	if (is_array($remodel_ar))
	{
		echo "<select name=\"remodeldiv\">\n";
		
		foreach ($remodel_ar as $nr => $vr)
		{			
			echo "	<option value=\"".$vr."\">".$vr."</option>\n";
		}
		
		echo "</select>\n";
	}
	
	echo "					</td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\">\n";
	echo "						<select name=\"opstate\">\n";
	echo "							<option value=\"1\">Yes</option>\n";
	echo "							<option value=\"0\">No</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\">\n";
	echo "						<select name=\"openjobs\">\n";
	echo "							<option value=\"1\">Yes</option>\n";
	echo "							<option value=\"0\">No</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\">\n";
	echo "						<select name=\"closedjobs\">\n";
	echo "							<option value=\"1\">Yes</option>\n";
	echo "							<option value=\"0\">No</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\">\n";
	echo "						<select name=\"huntenperc\">\n";
	echo "							<option value=\"1\">Yes</option>\n";
	echo "							<option value=\"0\">No</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\">\n";
	echo "						<select name=\"cib\">\n";
	echo "							<option value=\"1\">Yes</option>\n";
	echo "							<option value=\"0\">No</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\"><b></b></td>\n";
	echo "      			<td class=\"gray_und\" align=\"center\"><b></b></td>\n";
	echo "      			<td class=\"gray_und\" align=\"right\"><input class=\"checkboxgry\" type=\"image\" src=\"images/action_add.gif\" alt=\"Add Company\"></td>\n";
	echo "   			</tr>\n";
	echo "				</form>\n";
	
	$ccnt=1;
	while ($row0 = mssql_fetch_array($res0))
	{
		if ($row0['enabled']==0)
		{
			$tbg = 'lightcoral';
			$tbc = 'checkboxcrl';
		}
		else
		{
			if ($ccnt%2)
			{
				$tbg = 'white';
				$tbc = 'checkboxwh';
			}
			else
			{
				$tbg = 'gray';
				$tbc = 'checkboxgry';
			}
		}
		
		echo "				<form name=\"add\" target=\"_top\" method=\"post\">\n";
		echo "				<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "				<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
		echo "				<input type=\"hidden\" name=\"subq\" value=\"officeconfig_upd\">\n";
		echo "				<input type=\"hidden\" name=\"dcid\" value=\"".$row0['dcid']."\">\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"".$tbg."\" align=\"right\">".$ccnt++.".</td>\n";
		echo "      			<td class=\"".$tbg."\" align=\"center\">".$row0['company']."</td>\n";
		echo "      			<td class=\"".$tbg."\" align=\"center\">".$row0['division']."</td>\n";
		echo "      			<td class=\"".$tbg."\" align=\"center\">\n";
		
		if (is_array($type_ar))
		{
			echo "<select name=\"type\">\n";
			
			foreach ($type_ar as $nt => $vt)
			{				
				if ($row0['type']==$vt)
				{
					echo "	<option value=\"".$vt."\" SELECTED>".$typetxt_ar[$vt]."</option>\n";
				}
				else
				{
					echo "	<option value=\"".$vt."\">".$typetxt_ar[$vt]."</option>\n";
				}
			}
			
			echo "</select>\n";
		}
		
		echo "					</td>\n";
		echo "      			<td class=\"".$tbg."\" align=\"center\">\n";
		
		if ($row0['enabled']==1)
		{
			echo "<select name=\"enabled\">\n";
			echo "	<option value=\"1\" SELECTED>Yes</option>\n";
			echo "	<option value=\"0\">No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		else
		{
			echo "<select name=\"enabled\">\n";
			echo "	<option value=\"1\">Yes</option>\n";
			echo "	<option value=\"0\" SELECTED>No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		
		echo "      			<td class=\"".$tbg."\" align=\"center\">\n";
	
		if (is_array($remodel_ar))
		{
			echo "<select name=\"remodeldiv\">\n";
			
			foreach ($remodel_ar as $nr => $vr)
			{
				if ($vr==$row0['remodeldiv'])
				{
					echo "	<option value=\"".$vr."\" SELECTED>".$vr."</option>\n";
				}
				else
				{
					echo "	<option value=\"".$vr."\">".$vr."</option>\n";
				}
			}
			
			echo "</select>\n";
		}
		
		echo "					</td>\n";
		
		if ($row0['opstate']==1)
		{
			echo "      			<td class=\"".$tbg."\" align=\"center\">\n";
			echo "<select name=\"opstate\">\n";
			echo "	<option value=\"1\" SELECTED>Yes</option>\n";
			echo "	<option value=\"0\">No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		else
		{
			echo "      			<td class=\"lightcoral\" align=\"center\">\n";
			echo "<select name=\"opstate\">\n";
			echo "	<option value=\"1\">Yes</option>\n";
			echo "	<option value=\"0\" SELECTED>No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		
		
		if ($row0['openjobs']==1)
		{
			echo "      			<td class=\"".$tbg."\" align=\"center\">\n";
			echo "<select name=\"openjobs\">\n";
			echo "	<option value=\"1\" SELECTED>Yes</option>\n";
			echo "	<option value=\"0\">No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		else
		{
			echo "      			<td class=\"lightcoral\" align=\"center\">\n";
			echo "<select name=\"openjobs\">\n";
			echo "	<option value=\"1\">Yes</option>\n";
			echo "	<option value=\"0\" SELECTED>No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		
		if ($row0['closedjobs']==1)
		{
			echo "      			<td class=\"".$tbg."\" align=\"center\">\n";
			echo "<select name=\"closedjobs\">\n";
			echo "	<option value=\"1\" SELECTED>Yes</option>\n";
			echo "	<option value=\"0\">No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		else
		{
			echo "      			<td class=\"lightcoral\" align=\"center\">\n";
			echo "<select name=\"closedjobs\">\n";
			echo "	<option value=\"1\">Yes</option>\n";
			echo "	<option value=\"0\" SELECTED>No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		
		if ($row0['huntenperc']==1)
		{
			echo "      			<td class=\"".$tbg."\" align=\"center\">\n";
			echo "<select name=\"huntenperc\">\n";
			echo "	<option value=\"1\" SELECTED>Yes</option>\n";
			echo "	<option value=\"0\">No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		else
		{
			echo "      			<td class=\"lightcoral\" align=\"center\">\n";
			echo "<select name=\"huntenperc\">\n";
			echo "	<option value=\"1\">Yes</option>\n";
			echo "	<option value=\"0\" SELECTED>No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		
		if ($row0['cib']==1)
		{
			echo "      			<td class=\"".$tbg."\" align=\"center\">\n";
			echo "<select name=\"cib\">\n";
			echo "	<option value=\"1\" SELECTED>Yes</option>\n";
			echo "	<option value=\"0\">No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		else
		{
			echo "      			<td class=\"lightcoral\" align=\"center\">\n";
			echo "<select name=\"cib\">\n";
			echo "	<option value=\"1\">Yes</option>\n";
			echo "	<option value=\"0\" SELECTED>No</option>\n";
			echo "</select>\n";
			echo "					</td>\n";
		}
		
		echo "      			<td class=\"".$tbg."\" align=\"right\"><input class=\"".$tbc."\" type=\"image\" src=\"images/save.gif\" alt=\"Save Company\"></td>\n";
		echo "				</form>\n";
		echo "				<form name=\"add\" target=\"_top\" method=\"post\">\n";
		echo "				<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "				<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
		echo "				<input type=\"hidden\" name=\"subq\" value=\"officeconfig_del\">\n";
		echo "				<input type=\"hidden\" name=\"dcid\" value=\"".$row0['dcid']."\">\n";
		echo "      			<td class=\"".$tbg."\" align=\"right\"><input class=\"".$tbc."\" type=\"checkbox\" name=\"confirmdelete\" value=\"1\" title=\"Check Box to Delete Company\"></td>\n";
		echo "      			<td class=\"".$tbg."\" align=\"right\"><input class=\"".$tbc."\" type=\"image\" src=\"images/action_delete.gif\" alt=\"Delete Company\"></td>\n";
		echo "   			</tr>\n";
		echo "				</form>\n";
	}
	
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function OS_pools_dug($cpny,$div,$cpny2,$div2)
{
	error_reporting(E_ALL);
	global $dtarray,$pdarray,$ipdarray,$open_ar,$topar,$reno_divs;

	$drillen=0;	
	$cutdate=strtotime("10/1/06");
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=$_REQUEST['cpny'];
	$tdiv	=substr($retext,0,2);
	$tcolor	="black";
	$reno1	=0;
	$reno2	=0;
	
	$qtext=spec_code_qtext();
	
	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($dtarray as $na => $va)
		{
			$open_ar[]=1;
			$topar++;
		}
	}
	
	/*if (is_array($reno_divs) && in_array($div,$reno_divs))
	{
		//echo "RENO1!";
		
		$reno1	=1;
	}
	
	if ($cpny2!=0 && is_array($reno_divs) && in_array($div2,$reno_divs))
	{
		//echo "RENO2!";
		
		$reno2	=1;
	}*/
	
	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"jest"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username
	
	//$open_ar		=array(1,1,1,1,1,1,1,1,1,1,1,1);
	//$open_ar		=array(0,0,0,0,0,0,0,0,0,0,0,0);
	$odbc_conn	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$tmp=0;
	$opp=0;
	
	//echo "CNT: ".count($dtarray)."<br>";
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
		{
			$subdates=split(":",$subdtarray);
			$dtconst0=$subdates[0];
			$dtconst1=$subdates[1];
		}
		else
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
		}
		
		if ($open_ar[$opp] != 0)
		{
			//echo "Not Z: ".$open_ar[$opp]."<br>";
			if (strtotime($dtconst0) >= $cutdate)
			{
				if ($cpny2==0)
				{
					if ($mdiv==1)
					{
						$odbc_qryA    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
					}
					else
					{
						$odbc_qryA    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
					}
			
					//echo $odbc_qryA."<br>";
					$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
					$odbc_retA 	 = odbc_result($odbc_resA, 1);
					
					$odbc_ret	=$odbc_retA;
					
					if ($odbc_ret  > 0)
					{
						$drillen	= 1;
					}
				}
				else
				{
					if ($mdiv==1)
					{
						$odbc_qryA    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
					}
					else
					{
						$odbc_qryA    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
					}
			
					//echo "A: ".$odbc_qryA."<br>";
					$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
					$odbc_retA 	 = odbc_result($odbc_resA, 1);
					
					if ($mdiv==1)
					{
						$odbc_qryB    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND mdiv='".$div2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
					}
					else
					{
						$odbc_qryB    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
					}
			
					//echo "B: ".$odbc_qryB."<br>";
					$odbc_resB	 = odbc_exec($odbc_conn, $odbc_qryB);
					$odbc_retB 	 = odbc_result($odbc_resB, 1);
					
					$odbc_ret	=$odbc_retA+$odbc_retB;
					
					if ($odbc_ret  > 0)
					{
						$drillen	= 1;
					}
				}
				//echo $open_ar[$dtmonth]."<br>";
				//echo $open_ar[$dtmonth]."<br>";
			}
			else
			{
				$odbc_ret=0;
			}
				
			if ($odbc_ret==0)
			{
				if ($cpny2==0)
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
					else
					{
						$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
	
					//echo $qryA."<br>";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
				
					$tmp=$rowA[0];
				}
				else
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
					else
					{
						$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
	
					//echo $qryA."<br>";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					if ($mdiv==1)
					{
						$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '010".$div2."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
					else
					{
						$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '010%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
	
					//echo $qryA."<br>";
					$resB = mssql_query($qryB);
					$rowB = mssql_fetch_row($resB);
				
					$tmp=$rowA[0]+$rowB[0];
				}
				
				if ($_SESSION['officeid']==89 && $tmp!=0)
				{
					$tcolor="green";
				}
				else
				{
					$tcolor="black";
				}
			}
			else
			{
				$tmp=$odbc_ret;
				$tcolor="black";
			}
		}
		else
		{
			//echo "Z: ".$open_ar[$opp]."<br>";
			$tmp=0;
			if ($_SESSION['officeid']==89 && $tmp!=0)
			{
				$tcolor="red";
			}
			else
			{
				$tcolor="black";
			}
		}

		if (count($ipdarray) < $topar)
		{
			$amt=formatinteger($tmp);
			$ipdarray[]=$tmp;
		}
		else
		{
			$amt=0;
			$ipdarray[]=0;
			$drillen=0;
		}
		
		if (count($pdarray) < $topar)
		{
			$pdarray[]=$tmp;
		}
		else
		{
			$pdarray[]=0;
			$drillen=0;
		}
		
		if ($drillen==1)
		{
			echo "                                 <td width=\"60px\" align=\"right\"><a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=pd&a=".$div."&d0=".$dtconst0."&d1=".$dtconst1."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"><font size=\"1\">".$amt."</font></a></td>\n";
			$drillen=0;
		}
		else
		{
			echo "                                 <td width=\"60px\" align=\"right\"><font color=\"".$tcolor."\" size=\"1\">".$amt."</font></td>\n";
		}
		
		$opp++;
	}

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		if (count($ipdarray) == 2)
		{
			$spd=formatinteger(($ipdarray[1] - $ipdarray[0]));
		}
		else
		{
			$spd="Err. 3";
		}
		
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$spd."</font></td>\n";
	}
	else
	{
		$spd=formatinteger(array_sum($ipdarray));
		
		echo "                                 <td width=\"20px\" align=\"right\">&nbsp</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$spd."</font></td>\n";
	}
}

function prd_pools_dug()
{
	//global $dtarray,$pdarray;

	$dtarray	=setdatearray();
	$prdpd		=0;
	
	$qtext=spec_code_qtext();

	$cpny =$_REQUEST['cpny'];
	$mdiv =$_REQUEST['mdiv'];
	$div  =$_REQUEST['division'];
	$retext=substr($_REQUEST['cpny'],4);

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($mdiv==1)
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}

		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_row($resA);

		if ($rowA[0] > 0)
		{
			$prdpd++;
		}
	}
	
	return $prdpd;
}


function OS_total_other_income()
{
	global $darray,$otharray,$rbarray,$fdarray,$tmcarray,$advrbarray,$totharray,$euarray,$topar;

	if (is_array($darray))
	{
		foreach ($darray as $arraykey => $arrayvalue)
		{
			//$amt=$arrayvalue+$otharray[$arraykey]+$rbarray[$arraykey]+$fdarray[$arraykey]+$advrbarray[$arraykey]+$tmcarray[$arraykey];
			$amt=$arrayvalue+$otharray[$arraykey]+$fdarray[$arraykey]+$advrbarray[$arraykey]+$tmcarray[$arraykey];

			if (count($totharray) < $topar)
			{
				$tot_oth=formatinteger($amt);
				$totharray[]=$amt;
			}
			else
			{
				$tot_oth=0;
				$totharray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tot_oth</td>\n";
		}
	}

	$tcalc_oth  =formatinteger(array_sum($totharray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($totharray)/array_sum($euarray));
	}
	
	//$pavg_calc  =formatinteger(array_sum($totharray)/array_sum($pdarray));
	$moavg_calc =formatinteger(array_sum($totharray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_oth</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function OS_vend_rebate_income($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$advrbarray,$euarray,$topar;

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];
	
		if ($cpny2==0)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '413$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '413%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
	
			$int1=revsign($rowA[0]);
	
			$samt=$int1;
		}
		else
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '413$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '413%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '413$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '413%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			$int1=revsign($rowA[0])+revsign($rowB[0]);
	
			$samt=$int1;
		}

		if (count($advrbarray) < $topar)
		{
			$amt=formatinteger($samt);
			$advrbarray[]=$samt;
		}
		else
		{
			$amt=0;
			$advrbarray[]=0;
		}
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$svcs=formatinteger(array_sum($advrbarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=currency(array_sum($advrbarray)/array_sum($euarray));
	}
	
	//$savp=formatinteger(array_sum($advrbarray)/array_sum($pdarray));
	$mavg=formatinteger(array_sum($advrbarray)/$topar);
	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$svcs</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$savp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$mavg</td>\n";
}

function OS_rebate_income()
{
	global $dtarray,$rbarray,$euarray,$topar;

	$dtarray=setdatearray();

	$cpny =$_REQUEST['cpny'];
	$mdiv =$_REQUEST['mdiv'];
	$div  =$_REQUEST['division'];
	$retext=substr($_REQUEST['cpny'],4);
	
	$qtext=spec_code_qtext();

	$pd="";
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$d="";
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($mdiv==1)
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '412$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '412%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}

		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_row($resA);

		$int1=revsign($rowA[0]);

		$samt=$int1;
		if (count($rbarray) < $topar)
		{
			$amt=formatinteger($samt);
			$rbarray[]=$samt;
		}
		else
		{
			$amt=0;
			$rbarray[]=0;
		}

		if ($amt!=0)
		{
			$d="<font title=\"Not included in final Calc\">*</font>";
			$pd=$d;
		}
		
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$d $amt</td>\n";
	}

	$svcs=formatinteger(array_sum($rbarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=formatinteger(array_sum($rbarray)/array_sum($euarray));
	}
	
	$mavg=formatinteger(array_sum($rbarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pd $svcs</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pd $savp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pd $mavg</td>\n";
	//unset($rbarray);
}

function OS_other_income($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$otharray,$euarray,$topar;

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($cpny2==0)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '402$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '402%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '418$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '418%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			$int1=revsign($rowA[0])+revsign($rowB[0]);
		}
		else
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '402$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '402%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '402$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '402%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
			
			if ($mdiv==1)
			{
				$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '418$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '418%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_row($resC);
			
			if ($mdiv==1)
			{
				$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '418$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '418%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_row($resD);
	
			$int1=revsign($rowA[0])+revsign($rowB[0])+revsign($rowC[0])+revsign($rowD[0]);
		}
		
		if (count($otharray) < $topar)
		{
			$amt=formatinteger($int1);
			$otharray[]=$int1;
		}
		else
		{
			$amt=0;
			$otharray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$svcs=formatinteger(array_sum($otharray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=formatinteger(array_sum($otharray)/array_sum($euarray));
	}

	$mavg=formatinteger(array_sum($otharray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$svcs</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$savp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$mavg</td>\n";
}

function OS_discounts($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$darray,$euarray,$topar;

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];
		
		if ($cpny2==0)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '414$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '414%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
	
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '415$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '415%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			$int1=revsign($rowA[0]);
			$int2=revsign($rowB[0]);
	
			$samt=$int1+$int2;
		}
		else
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '414$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '414%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
	
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '415$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '415%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			if ($mdiv==1)
			{
				$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '414$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '414%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_row($resC);
	
			if ($mdiv==1)
			{
				$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '415$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '415%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_row($resD);
			
			$int1=revsign($rowA[0]);
			$int2=revsign($rowB[0]);
			$int3=revsign($rowC[0]);
			$int4=revsign($rowD[0]);
	
			$samt=$int1+$int2+$int3+$int4;
		}
		
		if (count($darray) < $topar)
		{
			$amt=formatinteger($samt);
			$darray[]=$samt;
		}
		else
		{
			$amt=0;
			$darray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$svcs=formatinteger(array_sum($darray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=formatinteger(array_sum($darray)/array_sum($euarray));
	}
	
	$mavg=formatinteger(array_sum($darray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$svcs</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$savp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$mavg</td>\n";
}

function OS_overhead($cpny,$div)
{
	global $dtarray,$ovarray,$euarray,$topar;

	$dtarray=setdatearray();
	$sdate	=current($dtarray);
	//print_r($sdate);
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	if (!empty($_REQUEST['specYY']) && $_REQUEST['specYY']==1)
	{
		$specYY=$_REQUEST['specYY'];
	}
	else
	{
		$specYY=0;
	}
	
	$qtext=spec_code_qtext();
	
	//echo "CO:".$cpny."<br>";
	//echo "DI:".$div."<br>";
	//echo "MI:".$mdiv."<br>";

	$exacct=array(729,741);

	if ($mdiv==1)
	{
		//$qry = "SELECT AccountNumber,AccountDescription FROM $cpny..GL1_Accounts WHERE AccountNumber LIKE '7%$div%' AND AccountNumber != '729300000' ORDER BY AccountNumber ASC;";
		$qry = "SELECT AccountNumber,AccountDescription FROM MAS_".$cpny."..GL1_Accounts WHERE AccountNumber LIKE '7%".$div."0%' OR AccountNumber LIKE '9[0-9][0-9]".$div."%' ORDER BY AccountNumber ASC;";
	}
	else
	{
		//$qry = "SELECT AccountNumber,AccountDescription FROM $cpny..GL1_Accounts WHERE AccountNumber LIKE '7%' AND AccountNumber != '729300000' ORDER BY AccountNumber ASC;";
		$qry = "SELECT AccountNumber,AccountDescription FROM MAS_".$cpny."..GL1_Accounts WHERE AccountNumber LIKE '7%' OR AccountNumber LIKE '9%' ORDER BY AccountNumber ASC;";
	}

	$res = mssql_query($qry);

	//echo $qry."<br><br>";

	$ccnt=0;
	while ($row = mssql_fetch_row($res))
	{
		unset($pre_ovarray);
		foreach ($dtarray as $dtmonth => $subdtarray)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];

			//$qryApre = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			$qryApre = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$sdate[0]."' AND '".$dtconst1."';";
			$resApre = mssql_query($qryApre);
			$rowApre = mssql_fetch_row($resApre);

			//echo $qryApre."<br>";
			$pre_ovarray[]=$rowApre[0];
		}
		$pre_sov=array_sum($pre_ovarray);
		//$pre_sov=1;

		if($pre_sov!=0)
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbg = "wh_und";
			}
			else
			{
				$tbg = "gray_und";
			}
			
			if (is_array($ovarray))
			{
				$ovarray=array();
			}
			//unset($ovarray);
			$d="";
			$pd="";
			echo "                              <tr>\n";
			echo "                                 <td class=\"".$tbg."\" width=\"160px\" align=\"left\" NOWRAP><font size=\"1\">".substr(ucwords(strtolower($row[1])),0,25)."</td>\n";
			//echo "                                 <td class=\"".$tbg."\" align=\"center\" NOWRAP><font size=\"1\">(".$cpny."-".substr($row[0],0,3).")</td>\n";
			echo "                                 <td class=\"".$tbg."\" align=\"center\" NOWRAP><font size=\"1\">(XXX-".substr($row[0],0,3).")</td>\n";

			foreach ($dtarray as $dtmonth => $subdtarray)
			{
				$dtconst0=$subdtarray[0];
				$dtconst1=$subdtarray[1];

				$tpsum2=0;
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);

				//if ($row[0]=="728250000")
				//{
				//	echo $qryA."<br>";
				//}

				$amt=formatinteger($rowA[0]);
				if (count($ovarray) < $topar)
				{
					$ovarray[]=$rowA[0];

					if (in_array(substr($row[0],0,3),$exacct))
					{
						$d="*";
						$pd="*";
					}
				}
				else
				{
					//$amt=0;
					$ovarray[]=0;
					//$d="";
				}

				if (isset($amt) && $amt!=0)
				{
					echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$d." <a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=ov&c=".$cpny."&a=".$row[0]."&d0=".$dtconst0."&d1=".$dtconst1."&spY=".$specYY."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$amt."</a></td>\n";
				}
				else 
				{
					echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$d." ".$amt."</td>\n";
				}
				//echo "                                 <td class=\"und\" width=\"60px\" align=\"right\"><font size=\"1\">".$d." ".$amt."</td>\n";
			}

			$sov=formatinteger(array_sum($ovarray));
			
			if (!is_array($euarray) || array_sum($euarray)==0)
			{
				$sav=0;
			}
			else
			{
				$sav=formatinteger(array_sum($ovarray)/array_sum($euarray));
			}
			
				
			$mag=formatinteger(array_sum($ovarray)/$topar);
			
			//getYTDrange
			$YTDr	=getYTDrange($dtarray,$topar);			
			
			echo "                                 <td class=\"".$tbg."\" width=\"20px\" align=\"center\">&nbsp</td>\n";
			
			if ($sov!=0)
			{
				echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$pd." <a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=ov&c=".$cpny."&a=".$row[0]."&d0=".$YTDr[0]."&d1=".$YTDr[1]."&spY=".$specYY."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$sov."</a></td>\n";
			}
			else 
			{
				echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$pd." ".$sov."</td>\n";
			}
			
			//echo "                                 <td class=\"und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
			//echo "                                 <td class=\"und\" width=\"60px\" align=\"right\"><font size=\"1\">".$pd." ".$sov."</td>\n";
			echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><font size=\"1\">".$pd." ".$sav."</td>\n";
			echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><font size=\"1\">".$pd." ".$mag."</td>\n";
			echo "                              </tr>\n";
		}
	}
}

function OS_total_overhead($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$tovarray,$euarray,$topar;
	
	//error_reporting(E_ALL);

	$dtarray=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	$qtext	=spec_code_qtext();

	echo "                              <tr>\n";
	echo "                                 <td width=\"160px\" align=\"right\"><font size=\"1\">Total Overhead</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($mdiv==1)
		{
			$qryAa = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '7[0-9][0-9]".$div."%' AND AccountNumber NOT LIKE '729%".$div."%' AND AccountNumber NOT LIKE '741%".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
		}
		else
		{
			$qryAa = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '7%' AND AccountNumber NOT LIKE '729%' AND AccountNumber NOT LIKE '741%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
		}

		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_row($resAa);
		
		if ($mdiv==1)
		{
			$qryAb = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '9[0-9][0-9]".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
		}
		else
		{
			$qryAb = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '9%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
		}

		$resAb = mssql_query($qryAb);
		$rowAb = mssql_fetch_row($resAb);
		
		//echo "Aa: ".$qryAa."<br>";
		//echo "Ab: ".$qryAb."<br>";
		
		$setA=$rowAa[0]+$rowAb[0];
		
		if ($cpny2!=0)
		{
			if ($mdiv==1)
			{
				$qryBa = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '7[0-9][0-9]%".$div2."%' AND AccountNumber NOT LIKE '729%".$div2."%' AND AccountNumber NOT LIKE '741%".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
			else
			{
				$qryBa = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '7%' AND AccountNumber NOT LIKE '729%' AND AccountNumber NOT LIKE '741%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
	
			$resBa = mssql_query($qryBa);
			$rowBa = mssql_fetch_row($resBa);
			
			if ($mdiv==1)
			{
				$qryBb = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '9[0-9][0-9]%".$div2."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
			else
			{
				$qryBb = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '9%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
	
			$resBb = mssql_query($qryBb);
			$rowBb = mssql_fetch_row($resBb);
			
			$setB	=$rowBa[0]+$rowBb[0];
		}
		else
		{
			$setB	=0;
		}		

		if (count($tovarray) < $topar)
		{
			$amt=formatinteger(($setA+$setB));
			$tovarray[]=$setA+$setB;
		}
		else
		{		
			$amt=0;
			$tovarray[]=0;
		}
		//echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt." (".count($tovarray) .") (".$topar.")</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
	}

	$tsov=formatinteger(array_sum($tovarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$tsav=0;
	}
	else
	{
		$tsav=formatinteger(array_sum($tovarray)/array_sum($euarray));
	}
	
	//$tsav=formatinteger(array_sum($tovarray)/array_sum($pdarray));
	$tmag=formatinteger(array_sum($tovarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tsov."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tsav."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tmag."</td>\n";
	echo "                              </tr>\n";
}

function OS_indirect_costs($cpny,$div)
{
	global $dtarray,$icarray,$ext_icarray,$euarray,$topar;
	$dtarray=setdatearray();
	$d="";
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);

	if (!empty($_REQUEST['specYY']) && $_REQUEST['specYY']==1)
	{
		$specYY=$_REQUEST['specYY'];
	}
	else
	{
		$specYY=0;
	}

	$qtext=spec_code_qtext();

	if ($mdiv==1)
	{
		$qry   = "SELECT AccountNumber,AccountDescription FROM MAS_".$cpny."..GL1_Accounts WHERE AccountNumber LIKE '6[0-9][0-9]".$div."%' ORDER BY AccountNumber ASC;";
	}
	else
	{
		$qry   = "SELECT AccountNumber,AccountDescription FROM MAS_".$cpny."..GL1_Accounts WHERE AccountNumber LIKE '6[0-9][0-9]%' ORDER BY AccountNumber ASC;";
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);
	
	//echo $qry."<br>";

	$ccnt=0;
	while ($row = mssql_fetch_row($res))
	{
		unset($pre_icarray);
		
		$pre_sic=0;
		foreach ($dtarray as $dtmonth => $subdtarray)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];

			$qryApre = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			$resApre = mssql_query($qryApre);
			$rowApre = mssql_fetch_row($resApre);
			
			//echo $qryApre."<br>";

			$pre_icarray[]=$rowApre[0];
		}
		
		foreach ($pre_icarray as $np => $nv)
		{
			if ($nv!=0)
			{
				$pre_sic++;
			}
		}
		
		//$pre_sic=array_sum($pre_icarray);

		if ($pre_sic!=0)
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbg = "wh_und";
			}
			else
			{
				$tbg = "gray_und";
			}	
			
			if (is_array($icarray))
			{
				$icarray=array();
			}
			echo "                              <tr>\n";
			echo "                                 <td class=\"".$tbg."\" width=\"160px\" align=\"left\" NOWRAP><font size=\"1\">".substr(ucwords(strtolower($row[1])),0,25)."</td>\n";
			//echo "                                 <td class=\"".$tbg."\" align=\"center\" NOWRAP><font size=\"1\">(".$cpny."-".substr($row[0],0,3).")</td>\n";
			echo "                                 <td class=\"".$tbg."\" align=\"center\" NOWRAP><font size=\"1\">(XXX-".substr($row[0],0,3).")</td>\n";

			foreach ($dtarray as $dtmonth => $subdtarray)
			{
				
				$dtconst0=$subdtarray[0];
				$dtconst1=$subdtarray[1];

				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);

				$amt=formatinteger($rowA[0]);
				if (count($icarray) < $topar)
				{
					//$amt=formatinteger($rowA[0]);
					$icarray[]=$rowA[0];
				}
				else
				{
					//$amt=0;
					$icarray[]=0;
				}
				
				if (isset($amt) && $amt!=0)
				{
					echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$d." <a href=\"/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=ov&c=".$cpny."&a=".$row[0]."&d0=".$dtconst0."&d1=".$dtconst1."&spY=".$specYY."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$amt."</a></td>\n";
				}
				else 
				{
					echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$d." ".$amt."</td>\n";
				}
			}
			$sic=formatinteger(array_sum($icarray));
			
			if (!is_array($euarray) || array_sum($euarray)==0)
			{
				$sav=0;
			}
			else
			{
				$sav=formatinteger(array_sum($icarray)/array_sum($euarray));
			}
			//$sav=formatinteger(array_sum($icarray)/array_sum($pdarray));
			$mag=formatinteger(array_sum($icarray)/$topar);

			//echo "                                 <td class=\"und\" width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
			//echo "                                 <td class=\"und\" width=\"60px\" align=\"right\"><font size=\"1\">".$sic."</td>\n";
			
			//getYTDrange
			$YTDr	=getYTDrange($dtarray,$topar);			
			
			echo "                                 <td class=\"".$tbg."\" width=\"20px\" align=\"center\">&nbsp</td>\n";
			
			if ($sic!=0)
			{
				echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><a href=\"/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=ov&c=".$cpny."&a=".$row[0]."&d0=".$YTDr[0]."&d1=".$YTDr[1]."&spY=".$specYY."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$sic."</a></td>\n";
			}
			else 
			{
				echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$sic."</td>\n";
			}
			
			echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><font size=\"1\">".$sav."</td>\n";
			echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><font size=\"1\">".$mag."</td>\n";
			echo "                              </tr>\n";
		}
	}
}

function OS_total_indirect_costs($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$ticarray,$euarray,$topar;

	$dtarray=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	$qtext=spec_code_qtext();

	echo "                              <tr>\n";
	echo "                                 <td width=\"160px\" align=\"right\"><font size=\"1\">Total Indirect Costs</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($mdiv==1)
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '6[0-9][0-9]%$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '6[0-9][0-9]%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}

		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_row($resA);
		
		// Added for Company Joins
		if ($cpny2!=0)
		{
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '6[0-9][0-9]%".$div2."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '6[0-9][0-9]%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
			
			$setB=$rowB[0];
		}
		else
		{
			$setB=0;
		}

		if (count($ticarray) < $topar)
		{
			$amt=formatinteger(($rowA[0]+$setB));
			$ticarray[]=$rowA[0]+$setB;
		}
		else
		{
			$amt=0;
			$ticarray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
	}

	$tsic=formatinteger(array_sum($ticarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$tsav=0;
	}
	else
	{
		$tsav=formatinteger(array_sum($ticarray)/array_sum($euarray));
	}
	
	//$tsav=formatinteger(array_sum($ticarray)/array_sum($pdarray));
	$tmag=formatinteger(array_sum($ticarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tsic."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tsav."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tmag."</td>\n";
	echo "                              </tr>\n";
}

function OS_indirect_costs_per_dig()
{
	global $dtarray,$ticarray,$ticpdarray,$euarray,$topar;

	if (is_array($ticarray))
	{
		foreach ($ticarray as $arraykey => $arrayvalue)
		{
			if ($euarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$ticarray[$arraykey] / $euarray[$arraykey];
			}
			if (count($ticpdarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$ticpdarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$ticpdarray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}	

	if (!is_array($ticpdarray) || array_sum($ticpdarray)==0)
	{
		$ticpd_gp=0;
	}
	else
	{
		$ticpd_gp=array_sum($ticpdarray);
	}

	$fticpd_gp   =formatinteger($ticpd_gp);

	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp=array_sum($ticpdarray)/array_sum($euarray);
	}

	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($euarray));
	}

	$moavg_calc =formatinteger($ticpd_gp/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$fticpd_gp."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tcalc_gp."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$moavg_calc."</td>\n";
}

function OS_overhead_costs_per_dig()
{
	global $dtarray,$tovarray,$tovpdarray,$euarray,$topar;

	if (is_array($tovarray))
	{
		foreach ($tovarray as $arraykey => $arrayvalue)
		{
			if ($euarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$tovarray[$arraykey] / $euarray[$arraykey];
			}
			if (count($tovpdarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$tovpdarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$tovpdarray[]=0;
			}
			echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}
	
	if (!is_array($tovpdarray) || array_sum($tovpdarray)==0)
	{
		$tovpd_gp=0;
	}
	else
	{
		$tovpd_gp =array_sum($tovpdarray);
	}
	
	$ftovpd_gp   =formatinteger($tovpd_gp);

	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp =array_sum($tovpdarray)/array_sum($euarray);
	}
	
	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($euarray));
	}
	
	$moavg_calc =formatinteger($tovpd_gp/$topar);
	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$ftovpd_gp."</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$tcalc_gp."</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$moavg_calc."</td>\n";
}

function OS_total_indirect_overhead()
{
	global $ticarray,$tovarray,$tioarray,$euarray,$topar;

	echo "                              <tr>\n";
	echo "                                 <td class=\"gray_und\" width=\"160px\" align=\"right\"><font size=\"1\">Total Ind & Over Costs</td>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">&nbsp</td>\n";

	if (is_array($ticarray))
	{
		foreach ($ticarray as $arraykey => $arrayvalue)
		{
			$sunfmt=$ticarray[$arraykey]+$tovarray[$arraykey];
			if (count($tioarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$tioarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$tioarray[]=0;
			}
			echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}

	$tcalc_gp   =formatinteger(array_sum($tioarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($tioarray)/array_sum($euarray));
	}
	
	//$pavg_calc  =formatinteger(array_sum($tioarray)/array_sum($pdarray));
	$moavg_calc =formatinteger(array_sum($tioarray)/$topar);
	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function OS_net_pos_poolsdug()
{
	global $gpcarray,$gpc_rarray,$tioarray,$nppdarray,$totharray,$euarray,$open_ar,$topar;

	//echo $totharray[10]."<br>";
	echo "                              <tr>\n";
	echo "                                 <td width=\"160px\" align=\"right\"><font size=\"1\">Net Position</td>\n";
	echo "                                 <td align=\"center\">(per Pools Dug)</td>\n";

	if (is_array($gpcarray))
	{
		$opp=0;
		foreach ($gpcarray as $arraykey => $arrayvalue)
		{
			if ($open_ar[$opp] != 0)
			{
				$sunfmt=($gpcarray[$arraykey]+$totharray[$arraykey]+$gpc_rarray[$arraykey])-$tioarray[$arraykey];
				//echo $totharray[$arraykey]."<br>";
			}
			else
			{
				$sunfmt=0;
			}
			if (count($nppdarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$nppdarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$nppdarray[]=0;
			}
			echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$calc_io."</td>\n";
			$opp++;
		}
	}

	$tcalc_gp   =formatinteger(array_sum($nppdarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($nppdarray)/array_sum($euarray));
	}
	
	//$pavg_calc  =formatinteger(array_sum($nppdarray)/array_sum($pdarray));
	$moavg_calc =formatinteger(array_sum($nppdarray)/$topar);
	echo "                                 <td class=\"dbl_und\" width=\"20px\" align=\"center\">&nbsp</td>\n";
	echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$tcalc_gp."</td>\n";
	echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$pavg_calc."</td>\n";
	echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$moavg_calc."</td>\n";
}

function OS_net_pos_perccomp()
{
	global $gparray,$gpc_rarray,$tioarray,$totharray,$nppcarray,$pdarray,$open_ar,$topar;
	//print_r($gparray);

	echo "                              <tr>\n";
	echo "                                 <td class=\"gray_und\" width=\"160px\" align=\"right\"><font size=\"1\">Net Position</td>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">(% Completion)</td>\n";

	if (is_array($gparray))
	{
		$opp=0;
		foreach ($gparray as $arraykey => $arrayvalue)
		{
			if ($open_ar[$opp] != 0)
			{
				$sunfmt=($gparray[$arraykey]+$totharray[$arraykey]+$gpc_rarray[$arraykey])-$tioarray[$arraykey];
			}
			else
			{
				$sunfmt=0;
			}
			
			if (count($nppcarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$nppcarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$nppcarray[]=0;
			}
			echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
			$opp++;
		}
	}

	$tcalc_gp   =formatinteger(array_sum($nppcarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($nppcarray)/array_sum($pdarray));
	}
	//$pavg_calc  =formatinteger(array_sum($nppcarray)/array_sum($pdarray));
	$moavg_calc =formatinteger(array_sum($nppcarray)/$topar);

	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function OS_avg_contract()
{
	global $dtarray,$vcsarray,$avcsarray,$pdarray,$euarray,$topar;

	if (is_array($vcsarray))
	{
		foreach ($vcsarray as $arraykey => $arrayvalue)
		{
			//if ($euarray[$arraykey]==0)
			if ($pdarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$vcsarray[$arraykey] / $pdarray[$arraykey];
			}

			if (count($avcsarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$avcsarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$avcsarray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$calc_io."</td>\n";
		}
	}
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp =array_sum($vcsarray)/array_sum($pdarray);
	}
	//$precalc_gp =array_sum($vcsarray)/array_sum($pdarray);
	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	}
		
	//$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	$moavg_calc =formatinteger($precalc_gp/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tcalc_gp."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$moavg_calc."</td>\n";
}

function OS_avg_dir_cost()
{
	global $dtarray,$dcarray,$adcarray,$pdarray,$euarray,$topar;

	if (is_array($dcarray))
	{
		foreach ($dcarray as $arraykey => $arrayvalue)
		{
			if ($pdarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$dcarray[$arraykey] / $pdarray[$arraykey];
			}
			if (count($adcarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$adcarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$adcarray[]=0;
			}
			//echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$calc_io." (".$dsarray[$arraykey].")</td>\n";
			echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$calc_io."</td>\n";
		}
	}
	
	if (!is_array($adcarray) || array_sum($adcarray)==0)
	{
		$tadc_gp=0;
	}
	else
	{
		$tadc_gp=array_sum($adcarray);
	}
	
	$ftadc_gp=formatinteger($tadc_gp);
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp =array_sum($adcarray)/array_sum($pdarray);
	}
	
	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	}
	
	$moavg_calc =formatinteger($tadc_gp/$topar);
	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$ftadc_gp."</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$tcalc_gp."</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$moavg_calc."</td>\n";
}

function OS_avg_gp()
{
	global $dtarray,$avcsarray,$adcarray,$avggparray,$gpcarray,$pdarray,$euarray,$topar;

	if (is_array($avcsarray))
	{
		foreach ($avcsarray as $arraykey => $arrayvalue)
		{
			if ($pdarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$avcsarray[$arraykey] - $adcarray[$arraykey];
			}
			if (count($avggparray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$avggparray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$avggparray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$calc_io."</td>\n";
		}
	}
	
	if (!is_array($avggparray) || array_sum($avggparray)==0)
	{
		$tavggp_gp=0;
	}
	else
	{
		$tavggp_gp =array_sum($avggparray);
	}
	
	$ftavggp_gp=formatinteger($tavggp_gp);
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp =array_sum($avggparray)/array_sum($euarray);
	}

	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($euarray));
	}
	
	$moavg_calc =formatinteger($tavggp_gp/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$ftavggp_gp."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tcalc_gp."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$moavg_calc."</td>\n";
}

function OS_avg_perc_gp()
{
	global $dtarray,$avcsarray,$rrarray,$adcarray,$avgpgparray,$avggparray,$gpcarray,$euarray,$topar;

	if (is_array($avcsarray))
	{
		foreach ($avcsarray as $arraykey => $arrayvalue)
		{
			if ($euarray[$arraykey]==0)
			{
				$subsunfmt=0;
				$sunfmt=$subsunfmt;
			}
			else
			{
				if ($avcsarray[$arraykey]==0)
				{
					$sunfmt=0;
				}
				else
				{
					$sunfmt=($avcsarray[$arraykey] - $adcarray[$arraykey]) / $avcsarray[$arraykey];
				}
			}
			if (count($avgpgparray) < $topar)
			{
				if ($_SESSION['securityid']==2666666666666666)
				{
					$calc_io=$sunfmt;
				}
				else
				{
					$calc_io=fixfloat(round($sunfmt,2))."%";
				}
				//$calc_io=fixfloat($sunfmt)."%";
				$avgpgparray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$avgpgparray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$calc_io."</td>\n";
		}
	}
	
	if (!is_array($avgpgparray) || array_sum($avgpgparray)==0)
	{
		$avgpgp_gp=0;
	}
	else
	{
		$avgpgp_gp =(array_sum($avcsarray) - array_sum($adcarray)) / array_sum($avcsarray);
	}
	
	if ($_SESSION['securityid']==266666666)
	{
		$favgpgp_gp   =$avgpgp_gp;
	}
	else
	{
		$favgpgp_gp   =fixfloat(round($avgpgp_gp,2))."%";
	}

	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc   =((array_sum($avcsarray) / array_sum($euarray)) - (array_sum($adcarray) / array_sum($euarray))) / (array_sum($avcsarray) / array_sum($euarray));
	}

	$ftpavg_calc =fixfloat(round($pavg_calc,2))."%";

	if (!is_array($rrarray) || array_sum($rrarray)==0 || $topar==0)
	{
		$moavg_calc=0;
	}
	else
	{
		$moavg_calc  =((array_sum($avcsarray) / $topar) - (array_sum($adcarray) / $topar)) / (array_sum($avcsarray) / $topar);
	}
	
	$ftmoavg_calc=fixfloat(round($moavg_calc,2))."%";
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$favgpgp_gp."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$ftpavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$ftmoavg_calc</td>\n";
}

function OS_calc_gp_misc_sales()
{
	global $msarray,$mcarray,$tmcarray,$euarray,$topar;

	if (is_array($msarray))
	{
		foreach ($msarray as $arraykey => $arrayvalue)
		{
			$amt=$arrayvalue-$mcarray[$arraykey];
			if (count($tmcarray) < $topar)
			{
				$calc_gp=formatinteger($amt);
				$tmcarray[]=$amt;
			}
			else
			{
				$calc_gp=0;
				$tmcarray[]=0;
			}
		}
	}
}

function OS_cost_misc($cpny,$div,$cpny2,$div2,$cm)
{
	global $dtarray,$mcarray,$euarray,$topar;

	$qtext=spec_code_qtext();

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];
		
		$subglar1=array();
		foreach ($cm as $gln => $glv)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv.$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			$subglar1[]=$rowA[0];
			
			if ($cpny2!=0)
			{
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv.$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
	
				$subglar1[]=$rowB[0];
			}
		}
		
		$samt=array_sum($subglar1);
		
		if (count($mcarray) < $topar)
		{
			$amt=formatinteger($samt);
			$mcarray[]=$samt;
		}
		else
		{
			$amt=0;
			$mcarray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$smc=formatinteger(array_sum($mcarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$amc=0;
	}
	else
	{
		$amc=formatinteger(array_sum($mcarray)/array_sum($euarray));
	}

	$mmc=formatinteger(array_sum($mcarray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$smc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$mmc</td>\n";
	echo "                              </tr>\n";
}

function OS_misc_sales($cpny,$div,$cpny2,$div2,$gl)
{
	global $dtarray,$msarray,$euarray,$topar;

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];
	
		$subglar1=array();
		foreach ($gl as $gln => $glv)
		{
			//echo $glv."<br>";
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv.$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
		
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			$subglar1[]=$rowA[0];
		}
		
		if ($cpny2!=0)
		{
			$subglar2=array();
			foreach ($gl as $gln2 => $glv2)
			{
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv.$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
			
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				$subglar2[]=$rowB[0];
			}
		}
		
		if ($div2==0)
		{
			$samt=revsign(array_sum($subglar1));
		}
		else
		{
			$samt=revsign(array_sum($subglar1)) + revsign(array_sum($subglar2));
		}

		if (count($msarray) < $topar)
		{
			$amt=formatinteger($samt);
			$msarray[]=$samt;
		}
		else
		{
			$amt=0;
			$msarray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$sms=formatinteger(array_sum($msarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$ams=0;
	}
	else
	{
		$ams=formatinteger(array_sum($msarray)/array_sum($euarray));
	}
	
	$mms=formatinteger(array_sum($msarray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$sms</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$ams</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$mms</td>\n";
	echo "                              </tr>\n";
}

function OS_dir_cost_sales($cpny,$div,$cpny2,$div2,$gl)
{
	global $dtarray,$dsarray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	$qtext=spec_code_qtext();

	$opp=0;
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '525$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '525%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '526$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '526%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				$samt	=$rowA[0]+$rowB[0];
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '525$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '525%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '526$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '526%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				if ($mdiv==1)
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '525$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '525%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_row($resC);
		
				if ($mdiv==1)
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '526$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '526%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resD = mssql_query($qryD);
				$rowD = mssql_fetch_row($resD);
				
				$samt	=$rowA[0]+$rowB[0]+$rowC[0]+$rowD[0];
			}
		}
		else
		{
			$samt=0;
		}

		if (count($dsarray) < $topar)
		{
			$amt=formatinteger($samt);
			$dsarray[]=$samt;
		}
		else
		{
			$amt=0;
			$dsarray[]=0;
		}

		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
		$opp++;
	}

	$sds=formatinteger(array_sum($dsarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$ads=0;
	}
	else
	{
		$ads=formatinteger(array_sum($dsarray)/number_format(array_sum($euarray)));
	}
	
	//$ads=formatinteger(array_sum($dsarray)/array_sum($euarray));
	$mds=formatinteger(array_sum($dsarray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$sds."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$ads."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$mds."</td>\n";
	echo "                              </tr>\n";
}

function OS_dir_cost_sales_reno($cpny,$div,$gl)
{
	global $dtarray,$ds_rarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();	
	$qtext		=spec_code_qtext();

	$opp		=0;
	foreach ($dtarray as $dtmo => $subdt)
	{
		if ($open_ar[$opp] != 0)
		{
			$samt=array(0);
			foreach ($div as $ndiv => $vdiv)
			{
				foreach ($gl as $ngl => $vgl)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$vgl.$vdiv."%' ".$qtext." AND TransactionDate BETWEEN '".$subdt[0]." 00:00:00' AND '".$subdt[1]." 23:59:59';";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					$samt[]	=$rowA[0];
					//echo $qryA."<br/>";
				}
			}
		}
		else
		{
			$samt=array(0);
		}

		if (count($ds_rarray) < $topar)
		{
			$amt=formatinteger(array_sum($samt));
			$ds_rarray[]=array_sum($samt);
		}
		else
		{
			$amt=0;
			$ds_rarray[]=0;
		}

		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
		$opp++;
	}

	$sds=formatinteger(array_sum($ds_rarray));
	$mds=formatinteger(array_sum($ds_rarray)/$topar);

	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$sds."</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\"></td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$mds."</td>\n";
	echo "                              </tr>\n";
}

function OS_dir_cost_sales_renoOLD($cpny,$div,$cpny2,$div2,$gl)
{
	global $dtarray,$ds_rarray,$euarray,$open_ar,$topar;

	$dtarray=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	$qtext=spec_code_qtext();

	$opp	=0;
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];

			$samt=array(0);
			foreach ($gl as $ngl => $vgl)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$vgl.$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				
				$samt[]	=$rowA[0];
				//echo $dtconst1.":".$vgl.$div.":".$rowA[0]."<br/>"; 
				//echo $qryA."<br/>";
			}
		}
		else
		{
			$samt=array(0);
		}

		if (count($ds_rarray) < $topar)
		{
			$amt=formatinteger(array_sum($samt));
			$ds_rarray[]=array_sum($samt);
		}
		else
		{
			$amt=0;
			$ds_rarray[]=0;
		}

		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
		$opp++;
	}

	$sds=formatinteger(array_sum($ds_rarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$ads=0;
	}
	else
	{
		$ads=formatinteger(array_sum($ds_rarray)/number_format(array_sum($euarray)));
	}
	
	$mds=formatinteger(array_sum($ds_rarray)/$topar);

	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$sds."</td>\n";
	//echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$ads."</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\"></td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$mds."</td>\n";
	echo "                              </tr>\n";
}

function OS_sales_tax_paid($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$sparray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	$qtext=spec_code_qtext();

	$opp=0;
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '550$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '550%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				$samt=$rowA[0];
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '550$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '550%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '550$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '550%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				$samt=$rowA[0]+$rowB[0];
			}
		}
		else
		{
			$samt=0;
		}

		if (count($sparray) < $topar)
		{
			$amt=formatinteger($samt);
			$sparray[]=$samt;
		}
		else
		{
			$amt=0;
			$sparray[]=0;
		}

		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
		$opp++;
	}

	$ssp=formatinteger(array_sum($sparray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$asp=0;
	}
	else
	{
		$asp=formatinteger(array_sum($sparray)/number_format(array_sum($euarray)));
	}
	
	//$asp=formatinteger(array_sum($sparray)/array_sum($euarray));
	$msp=formatinteger(array_sum($sparray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$ssp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$asp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$msp</td>\n";
	echo "                              </tr>\n";
}

function OS_cost_on_closed($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$ccarray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	$opp	=0;
	
	$qtext=spec_code_qtext();
	
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
				
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '590$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '590%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				$samt=$rowA[0];
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '590$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '590%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '590$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '590%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				$samt=$rowA[0]+$rowB[0];
			}
		}
		else
		{
			$samt=0;
		}

		if (count($ccarray) < $topar)
		{
			$amt=formatinteger($samt);
			$ccarray[]=$samt;
		}
		else
		{
			$amt=0;
			$ccarray[]=0;
		}
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
		$opp++;
	}

	$srr=formatinteger(array_sum($ccarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$rav=0;
	}
	else
	{
		$rav=formatinteger(array_sum($ccarray)/number_format(array_sum($euarray)));
	}
	
	//$rav=formatinteger(array_sum($ccarray)/array_sum($euarray));
	$rag=formatinteger(array_sum($ccarray)/$topar);
	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$srr</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$rav</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$rag</td>\n";
	echo "                              </tr>\n";
}

function OS_inv_adjust($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$iaarray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	$opp		=0;
	
	$qtext=spec_code_qtext();
	
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
				
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '551$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '551%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				$samt=$rowA[0];
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '551$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '551%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '551$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '551%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				$samt=$rowA[0]+$rowB[0];
			}
			//echo $qryA."<br>";
			//echo $qryB."<br>";
		}
		else
		{
			$samt=0;
		}

		if (count($iaarray) < $topar)
		{
			$amt=formatinteger($samt);
			$iaarray[]=$samt;
		}
		else
		{
			$amt=0;
			$iaarray[]=0;
		}

		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
		$opp++;
	}

	$srr=formatinteger(array_sum($iaarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$rav=0;
	}
	else
	{
		$rav=formatinteger(array_sum($iaarray)/number_format(array_sum($euarray)));
	}
	//$rav=formatinteger(array_sum($iaarray)/array_sum($euarray));
	$rag=formatinteger(array_sum($iaarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$srr</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$rav</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$rag</td>\n";
	echo "                              </tr>\n";
}

function OS_pre_reco_rev($cpny,$div,$cpny2,$div2,$gl) // for Equiv Units
{
	global $dtarray,$prrarray,$pdarray,$open_ar,$topar;

	$dtarray=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	$opp		=0;
	
	$qtext=spec_code_qtext();
	
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				//echo "P: ".$qryA."<br>";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				
				//echo "P: ".$qryB."<br>";
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
		
				$int1=revsign($rowA[0]);
				$int2=revsign($rowB[0]);
		
				$samt=$int1+$int2;
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				if ($mdiv==1)
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_row($resC);
		
				if ($mdiv==1)
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resD = mssql_query($qryD);
				$rowD = mssql_fetch_row($resD);
		
				$int1=revsign($rowA[0]);
				$int2=revsign($rowB[0]);
				$int3=revsign($rowC[0]);
				$int4=revsign($rowD[0]);
		
				$samt=$int1+$int2+$int3+$int4;
			}
		}
		else
		{
			$samt=0;
		}

		if (count($prrarray) < $topar)
		{
			$prrarray[]=$samt;
		}
		else
		{
			$prrarray[]=0;
		}
		$opp++;
	}
	return $prrarray;
}

function OS_forgive_debt($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$fdarray,$euarray,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($cpny2==0)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '411$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '411%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
	
			$samt=revsign($rowA[0]);
		}
		else
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '411$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '411%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '411$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '411%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			$samt=revsign($rowA[0])+revsign($rowB[0]);
		}

		if (count($fdarray) < $topar)
		{
			$amt=formatinteger($samt);
			$fdarray[]=$samt;
		}
		else
		{
			$amt=0;
			$fdarray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$svcs=formatinteger(array_sum($fdarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=formatinteger(array_sum($fdarray)/array_sum($euarray));
	}
	//$savp=formatinteger(array_sum($fdarray)/array_sum($pdarray));
	$mavg=formatinteger(array_sum($fdarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$svcs</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$savp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$mavg</td>\n";
}

function OS_reco_rev($cpny,$div,$cpny2,$div2,$gl)
{
	global $dtarray,$rrarray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 		=$_REQUEST['mdiv'];
	$opp		=0;
	
	$qtext		=spec_code_qtext();
	
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				//echo $qryA."<br>";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				//echo $qryB."<br>";
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
		
				$int1=revsign($rowA[0]);
				$int2=revsign($rowB[0]);
				$samt=$int1+$int2;
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				if ($mdiv==1)
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_row($resC);
		
				if ($mdiv==1)
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resD = mssql_query($qryD);
				$rowD = mssql_fetch_row($resD);
		
				$int1=revsign($rowA[0]);
				$int2=revsign($rowB[0]);
				$int3=revsign($rowC[0]);
				$int4=revsign($rowD[0]);
				$samt=$int1+$int2+$int3+$int4;
			}
		}
		else
		{
			$samt=0;
		}
		
		if (count($rrarray) < $topar)
		{
			$amt=formatinteger($samt);
			$rrarray[]=$samt;
		}
		else
		{
			$amt=0;
			$rrarray[]=0;
		}
		
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
		$opp++;
	}

	$srr=formatinteger(array_sum($rrarray));
	//$srr=formatinteger(preg_replace("/-/","",array_sum($rrarray)));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$rav=0;
	}
	else
	{
		$rav=formatinteger(array_sum($rrarray)/number_format(array_sum($euarray)));
	}
	
	//$rav=formatinteger(array_sum($rrarray)/array_sum($euarray));
	$rag=formatinteger(array_sum($rrarray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$srr</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$rav</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$rag</td>\n";
	echo "                              </tr>\n";
}

function OS_reco_rev_reno($cpny,$div,$gl)
{
	global $dtarray,$rr_rarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$qtext		=spec_code_qtext();
	$opp		=0;
	
	foreach ($dtarray as $dtmo => $subdt)
	{
		if ($open_ar[$opp] != 0)
		{
			$samt=array(0);
			foreach ($div as $ndiv => $vdiv)
			{
				foreach ($gl as $ngl => $vgl)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$vgl.$vdiv."%' ".$qtext." AND TransactionDate BETWEEN '".$subdt[0]." 00:00:00' AND '".$subdt[1]." 23:59:59';";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					$samt[]	=$rowA[0];
					//echo $qryA."<br/>";
				}
			}
		}
		else
		{
			$samt=array(0);
		}
		
		if (count($rr_rarray) < $topar)
		{
			$amt		=formatinteger((array_sum($samt) * -1));
			$rr_rarray[]=(array_sum($samt) * -1);
		}
		else
		{
			$amt		=0;
			$rr_rarray[]=0;
		}
		
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
		$opp++;
	}

	$srr=formatinteger(array_sum($rr_rarray));
	$rag=formatinteger(array_sum($rr_rarray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$srr."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$rag."</td>\n";
	echo "                              </tr>\n";
}

function OS_equiv_units($cpny,$div,$cpny2,$div2,$gl)
{
	global $euarray,$pdarray,$avg_per_pool,$topar;

	$prrarray=OS_pre_reco_rev($cpny,$div,$cpny2,$div2,$gl);
	if (is_array($prrarray))
	{
		foreach ($prrarray as $arraykey => $arrayvalue)
		{
			if (count($euarray) < $_REQUEST['prd'])
			{
				if ($avg_per_pool==0)
				{
					$calc_eu=0;
				}
				else
				{
					//$calc_eu=$arrayvalue/$avg_per_pool;
					$calc_eu=round($arrayvalue/$avg_per_pool);
				}
				$euarray[]=$calc_eu;
			}
			else
			{
				$calc_eu=0;
				$euarray[]=0;
			}
			$calc_eu=formatinteger($calc_eu);
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$calc_eu."</td>\n";
		}
	}

	$tcalc_gp   =formatinteger(array_sum($euarray));
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tcalc_gp."</td>\n";
}

function OS_calc_total_gprof()
{
	global $gparray,$gpc_rarray,$totharray,$gtparray,$euarray,$topar;

	if (is_array($gparray))
	{
		foreach ($gparray as $arraykey => $arrayvalue)
		{
			$sunfmt=$arrayvalue+$gpc_rarray[$arraykey]+$totharray[$arraykey];

			if (count($gtparray) < $topar)
			{
				$calc_gp=formatinteger($sunfmt);
				$gtparray[]=$sunfmt;
			}
			else
			{
				$calc_gp=0;
				$gtparray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><b>".$calc_gp."</b></td>\n";
		}
	}

	$tcalc_gp   =formatinteger(array_sum($gtparray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($gtparray)/number_format(array_sum($euarray)));
	}
		
	$moavg_calc =formatinteger(array_sum($gtparray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><b>".$tcalc_gp."</b></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><b>".$pavg_calc."</b></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><b>".$moavg_calc."</b></td>\n";
}

function OS_calc_total_gp()
{
	global $rrarray,$dsarray,$ccarray,$iaarray,$gparray,$sparray,$pdarray,$euarray,$topar;

	if (is_array($rrarray))
	{
		foreach ($dsarray as $arraykey => $arrayvalue)
		{
			$sunfmt=$rrarray[$arraykey]-($arrayvalue+$ccarray[$arraykey]+$iaarray[$arraykey]+$sparray[$arraykey]);

			if (count($gparray) < $topar)
			{
				$calc_gp=formatinteger($sunfmt);
				$gparray[]=$sunfmt;
			}
			else
			{
				$calc_gp=0;
				$gparray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_gp</td>\n";
		}
	}

	$tcalc_gp   =formatinteger(array_sum($gparray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($gparray)/number_format(array_sum($euarray)));
	}
		
	//$pavg_calc  =formatinteger(array_sum($gparray)/array_sum($euarray));
	$moavg_calc =formatinteger(array_sum($gparray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function OS_calc_gp_var()
{
	global $pdarray,$euarray,$gparray,$gpcarray,$gparray,$topar;

	//print_r($gparray);
	if (is_array($gpcarray))
	{
		foreach ($gpcarray as $arraykey => $arrayvalue)
		{
			echo "                                 <td width=\"60px\" align=\"right\">&nbsp</td>\n";
		}
	}

	if (!is_array($euarray) || array_sum($euarray)==0 || !is_array($pdarray) || array_sum($pdarray)==0)
	{
		$p1avg_calc	=0;
		$p2avg_calc	=0;
		$pavg_calc	=0;
	}
	else
	{
		$p1avg_calc = round(array_sum($gparray)) / array_sum($euarray);
		$p2avg_calc = round(array_sum($gpcarray)) / array_sum($pdarray);
		//$p1avg_calc = round(array_sum($gparray)) / round(array_sum($euarray));
		//$p2avg_calc = round(array_sum($gpcarray)) / round(array_sum($pdarray));*/
		$pavg_calc	= $p1avg_calc - $p2avg_calc;
	}
	
	//$pavg_calc	=(array_sum($gparray) / array_sum($euarray)) - (array_sum($gpcarray) / array_sum($pdarray));
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pc_calc=0;
	}
	else
	{
		$pc_calc	=($pavg_calc / $p2avg_calc) * 100;
	}
	
	//$pc_calc	=($pavg_calc / ((array_sum($gparray) / array_sum($euarray)))) * 100;
	echo "                                 <td width=\"20px\" align=\"center\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\">".formatinteger($pavg_calc)."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\">".formatinteger($pc_calc)."%</td>\n";
	//echo "                                 <td width=\"60px\" align=\"right\">".$pc_calc."%</td>\n";
}

function OS_calc_gp_contracts()
{
	global $vcsarray,$dcarray,$pdarray,$gpcarray,$topar;

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($vcsarray as $na => $va)
		{
			$topar++;
		}
	}
	
	if (is_array($vcsarray))
	{
		foreach ($vcsarray as $arraykey => $arrayvalue)
		{
			$pcalc_gp=$arrayvalue-$dcarray[$arraykey];

			if (count($gpcarray) < $topar)
			{
				$calc_gp=formatinteger($pcalc_gp);
				$gpcarray[]=$pcalc_gp;
			}
			else
			{
				$calc_gp=0;
				$gpcarray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_gp</td>\n";
		}
	}

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		$gpcdiff=formatinteger(($gpcarray[1] - $gpcarray[0]));
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$gpcdiff."</font></td>\n";
	}
	else
	{
		$tcalc_gp   =formatinteger(array_sum($gpcarray));
		
		if (!is_array($pdarray) || array_sum($pdarray)==0)
		{
			$pavg_calc=0;
		}
		else
		{
			$pavg_calc  =formatinteger(array_sum($gpcarray)/array_sum($pdarray));
		}
		
		//$pavg_calc  =formatinteger(array_sum($gpcarray)/array_sum($pdarray));
		$moavg_calc =formatinteger(array_sum($gpcarray)/$topar);
		echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
	}
}

function OS_calc_gp_reno()
{
	global $rr_rarray,$ds_rarray,$gpc_rarray,$euarray,$topar;

	if (is_array($rr_rarray))
	{
		foreach ($rr_rarray as $arraykey => $arrayvalue)
		{
			$pcalc_gp=$rr_rarray[$arraykey]-$ds_rarray[$arraykey];

			if (count($gpc_rarray) < $topar)
			{
				$calc_gp		=formatinteger($pcalc_gp);
				$gpc_rarray[]	=$pcalc_gp;
			}
			else
			{
				$calc_gp		=0;
				$gpc_rarray[]	=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\">".$calc_gp."</td>\n";
		}
	}

	$tcalc_gp   =formatinteger(array_sum($gpc_rarray));
	$moavg_calc =formatinteger((array_sum($gpc_rarray)/$topar));
	echo "                                 <td width=\"20px\" align=\"center\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\">".$tcalc_gp."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\">".$moavg_calc."</td>\n";
}

function OS_dir_cost_con($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$dcarray,$pdarray,$open_ar,$topar,$reno_divs;

	//$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$tcolor	="black";
	$reno1	=0;
	$reno2	=0;
	
	$qtext=spec_code_qtext();
	
	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($dtarray as $na => $va)
		{
			$open_ar[]=1;
			$topar++;
		}
	}
	
	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($dtarray as $na => $va)
		{
			$open_ar[]=1;
			$topar++;
		}
	}
	
	/*if (is_array($reno_divs) && in_array($div,$reno_divs))
	{
		//echo "RENO1!";
		
		$reno1	=1;
	}
	
	if ($cpny2!=0 && is_array($reno_divs) && in_array($div2,$reno_divs))
	{
		//echo "RENO2!";
		
		$reno2	=1;
	}*/

	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"jest"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username
			
	$odbc_conn	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);

	$tmp=0;
	$opp=0;
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
			{
				$subdates=split(":",$subdtarray);
				$dtconst0=$subdates[0];
				$dtconst1=$subdates[1];
			}
			else
			{
				$dtconst0=$subdtarray[0];
				$dtconst1=$subdtarray[1];
			}
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
				else
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
				$odbc_retA 	 = odbc_result($odbc_resA, 1);
				
				$odbc_ret	=$odbc_retA;
			}
			else
			{
				if ($mdiv==1)
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
				else
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
				$odbc_retA 	 = odbc_result($odbc_resA, 1);
				
				if ($mdiv==1)
				{
					$odbc_qryB    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND mdiv='".$div2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
				}
				else
				{
					$odbc_qryB    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resB	 = odbc_exec($odbc_conn, $odbc_qryB);
				$odbc_retB 	 = odbc_result($odbc_resB, 1);
				
				$odbc_ret	=$odbc_retA+$odbc_retB;
			}
			
			if (!isset($odbc_ret) || $odbc_ret==0)
			{
				if ($cpny2==0)
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '012$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '012%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					
					//echo $qryA."<br>";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					$tmp	=$rowA[0];
				}
				else
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '012$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '012%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					
					//echo $qryA."<br>";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					if ($mdiv==1)
					{
						$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '012$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '012%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					
					//echo $qryA."<br>";
					$resB = mssql_query($qryB);
					$rowB = mssql_fetch_row($resB);
					
					$tmp	=$rowA[0]+$rowB[0];
				}
				
				if ($_SESSION['officeid']==89 && $tmp!=0)
				{
					$tcolor="green";
				}
				else
				{
					$tcolor="black";
				}
			}
			else
			{
				//echo $odbc_qry."<br>";
				$tmp=$odbc_ret;
				$tcolor="black";
			}
		}
		else
		{
			$tmp=0;
			
			if ($_SESSION['officeid']==89 && $tmp!=0)
			{
				$tcolor="red";
			}
			else
			{
				$tcolor="black";
			}
		}
		
		if (count($dcarray) < $topar)
		{
			$amt=formatinteger($tmp);
			$dcarray[]=$tmp;
		}
		else
		{
			$amt=0;
			$dcarray[]=0;
		}	
		
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font color=\"".$tcolor."\" size=\"1\">".$amt."</td>\n";
		$opp++;
	}

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		$dccdiff=formatinteger(($dcarray[1] - $dcarray[0]));
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$dccdiff."</font></td>\n";
	}
	else
	{
		$sdc=formatinteger(array_sum($dcarray));
		
		if (!is_array($pdarray) || array_sum($pdarray)==0)
		{
			$sav=0;
		}
		else
		{
			$sav=formatinteger(array_sum($dcarray)/array_sum($pdarray));
		}
		
		$mag=formatinteger(array_sum($dcarray)/$topar);
	
		echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$sdc."</td>\n";
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$sav."</td>\n";
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$mag."</td>\n";
	}
}

function OS_val_con_start($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$vcsarray,$pdarray,$avg_per_pool,$open_ar,$topar,$reno_divs;

	//$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$mflg		=0;
	$tcolor	="black";
	$dtar		=0;
	$reno1	=0;
	$reno2	=0;
	
	$qtext=spec_code_qtext();
	
	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($dtarray as $na => $va)
		{
			$open_ar[]=1;
			$topar++;
		}
	}
	
	/*if (is_array($reno_divs) && in_array($div,$reno_divs))
	{
		//echo "RENO1!";
		
		$reno1	=1;
	}
	
	if ($cpny2!=0 && is_array($reno_divs) && in_array($div2,$reno_divs))
	{
		//echo "RENO2!";
		
		$reno2	=1;
	}*/

	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"jest"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username
			
	$odbc_conn	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);

	$tmp=0;
	$opp=0;
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
			{
				$subdates=split(":",$subdtarray);
				$dtconst0=$subdates[0];
				$dtconst1=$subdates[1];
			}
			else
			{
				$dtconst0=$subdtarray[0];
				$dtconst1=$subdtarray[1];
			}
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
				else
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
		
				//echo $odbc_qryA."<br>";
				$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
				$odbc_retA 	 = odbc_result($odbc_resA, 1);
				
				$odbc_ret	=$odbc_retA;
			}
			else
			{
				if ($mdiv==1)
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
				else
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
				$odbc_retA 	 = odbc_result($odbc_resA, 1);
				
				if ($mdiv==1)
				{
					$odbc_qryB    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND mdiv='".$div2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
				}
				else
				{
					$odbc_qryB    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resB	 = odbc_exec($odbc_conn, $odbc_qryB);
				$odbc_retB 	 = odbc_result($odbc_resB, 1);
				
				$odbc_ret	=$odbc_retA+$odbc_retB;
			}
			
			if (!isset($odbc_ret) || $odbc_ret == 0)
			{
				if ($cpny2==0)
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '011$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '011% ".$qtext."' AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
		
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					$tmp=$rowA[0];
				}
				else
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '011$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '011%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
		
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					if ($mdiv==1)
					{
						$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '011$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '011%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
		
					$resB = mssql_query($qryB);
					$rowB = mssql_fetch_row($resB);
					
					$tmp=$rowA[0]+$rowB[0];
				}
				
				if ($_SESSION['officeid']==89 && $tmp!=0)
				{
					$tcolor="green";
				}
				else
				{
					$tcolor="black";
				}
			}
			else
			{
				$tmp=$odbc_ret;
				$tcolor="black";
			}
		}
		else
		{
			$tmp=0;
			
			if ($_SESSION['officeid']==89 && $tmp!=0)
			{
				$tcolor="red";
			}
			else
			{
				$tcolor="black";
			}
		}

		if (count($vcsarray) < $topar)
		{
			$amt=formatinteger($tmp);
			$vcsarray[]=$tmp;
		}
		else
		{
			$amt=0;
			$vcsarray[]=0;
		}
		
		echo "                                 <td width=\"60px\" align=\"right\"><font color=\"".$tcolor."\" size=\"1\">".$amt."</font></td>\n";
		$opp++;
		$dtar++;
	}

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		$vcsdiff=formatinteger(($vcsarray[1] - $vcsarray[0]));
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$vcsdiff."</font></td>\n";
	}
	else
	{
		if (!is_array($pdarray) || array_sum($pdarray)==0)
		{
			$avg_per_pool=0;
		}
		else
		{
			$avg_per_pool=array_sum($vcsarray)/array_sum($pdarray);
		}
		
		$svcs=formatinteger(array_sum($vcsarray));
		
		if (!is_array($pdarray) || array_sum($pdarray)==0)
		{
			$savp=0;
		}
		else
		{
			$savp=formatinteger(array_sum($vcsarray)/array_sum($pdarray));
		}
		
		$mavg=formatinteger(array_sum($vcsarray)/$topar);
		echo "                                 <td width=\"20px\" align=\"center\">&nbsp</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$svcs."</font></td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$savp."</font></td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$mavg."</font></td>\n";
	}
}

function divlookup($div)
{
	$fout = "";
	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
	$qryB = "SELECT Description FROM MAS_".$row['company']."..ARB_DivisionMasterfile WHERE Division='".$div."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
	
	$fout = $rowB['Description'];

	return $fout;
}

function os_admin()
{
	
}

function opuselog()
{
	error_reporting(E_ALL);
	
	$dcnt=0;
	$qry0 = "SELECT * FROM offices WHERE active=1 ORDER by grouping,name;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT securityid,gmreports FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	if ($_SESSION['officeid']!=89 && $_SESSION['rlev'] < 9 && $row1['gmreports']==1)
	{
		die('You do not have appropriate Access Rights to view this Report');
	}
	else
	{
		$order="evdate";
		$asc	="desc";
		
		if (isset($_REQUEST['order']) && $_REQUEST['order']!="evdate")
		{
			$order=$_REQUEST['order'];
		}
		
		if (isset($_REQUEST['asc']) && $_REQUEST['asc']!="desc")
		{
			$asc=$_REQUEST['asc'];
		}
		
		if (isset($_REQUEST['d1']) && valid_date($_REQUEST['d1']) && isset($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
		{
			$d1=date("m/d/Y",strtotime($_REQUEST['d1']));
			$d2=date("m/d/Y",strtotime($_REQUEST['d2']));
		}
		else
		{
			$d1=date("m/d/Y",(time() - (84600 * 30)));
			$d2=date("m/d/Y",time());
		}
	
		echo "<table align=\"center\" width=\"700px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
		echo "         <table class=\"outer\" width=\"100%\" border=0>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP colspan=\"2\">&nbsp<b>Operating Reports Access Log</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Order</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Date Range</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Non Admin</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Archive</b></td>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp</td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>Office:</b></td>\n";
		echo "         			<form name=\"report1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"opuselog\">\n";
		echo "						<input type=\"hidden\" name=\"subq2\" value=\"results\">\n";
		echo "      			<td class=\"gray\" align=\"left\">\n";
		echo "						<select name=\"oid\">\n";
		
		if (isset($_REQUEST['oid']) && $_REQUEST['oid']==0)
		{
			echo "							<option value=\"0\" SELECTED>All</option>\n";
		}
		else
		{
			echo "							<option value=\"0\">All</option>\n";
		}
		
		while ($row0 = mssql_fetch_array($res0))
		{
			if ($_REQUEST['oid']==$row0['officeid'])
			{
				echo "							<option value=\"".$row0['officeid']."\" SELECTED>".$row0['name']."</option>\n";
			}
			else
			{
				echo "							<option value=\"".$row0['officeid']."\">".$row0['name']."</option>\n";
			}
		}
		
		echo "						</select>\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"left\">\n";
		echo "						<select name=\"asc\">\n";
		
		if ($_REQUEST['asc']=='asc')
		{
			echo "							<option value=\"asc\" SELECTED>ASC</option>\n";
			echo "							<option value=\"desc\">DESC</option>\n";
		}
		else
		{
			echo "							<option value=\"desc\" SELECTED>DESC</option>\n";
			echo "							<option value=\"asc\">ASC</option>\n";
		}

		echo "						</select>\n";
		echo "      			</td>\n";
		echo "      			<td class=\"gray\" align=\"left\">\n";
		echo "         			<table width=\"100%\">\n";
		echo "   						<tr>\n";
		echo "      						<td class=\"gray\" align=\"left\">\n";
	
		if (!empty($d1))
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"12\" value=\"".$d1."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"12\">\n";
		}
	
		echo "									<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	
		if (!empty($d2))
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"12\" maxlength=\"10\" value=\"".$d2."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"12\">\n";
		}
	
		echo "									<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
		echo "      						</td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "      						<td class=\"gray\" align=\"center\">\n";
		
		if (isset($_REQUEST['excladmin']) && $_REQUEST['excladmin']==1)
		{
			echo "<input type=\"checkbox\" class=\"checkboxgry\" name=\"excladmin\" value=\"1\" title=\"Check this box to exclude BHNM: Active\" CHECKED>\n";
		}
		else
		{
			echo "<input type=\"checkbox\" class=\"checkboxgry\" name=\"excladmin\" value=\"1\" title=\"Check this box to exclude BHNM: Active\">\n";
		}
		
		echo "      						</td>\n";
		echo "      						<td class=\"gray\" align=\"center\">\n";
		
		if (isset($_REQUEST['archive']) && $_REQUEST['archive']==1)
		{
			echo "<input type=\"checkbox\" class=\"checkboxgry\" name=\"archive\" value=\"1\" title=\"Check this box to exclude BHNM: Active\" CHECKED>\n";
		}
		else
		{
			echo "<input type=\"checkbox\" class=\"checkboxgry\" name=\"archive\" value=\"1\" title=\"Check this box to exclude BHNM: Active\">\n";
		}
		
		echo "      						</td>\n";
		echo "      						<td class=\"gray\" align=\"center\">\n";
		echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
		echo "      						</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "         				</form>\n";
	
		echo "         						<script language=\"JavaScript\">\n";
		echo "         						var cal1 = new calendar2(document.forms['report1'].elements['d1']);\n";
		echo "         						cal1.year_scroll = false;\n";
		echo "         						cal1.time_comp = false;\n";
		echo "         						var cal2 = new calendar2(document.forms['report1'].elements['d2']);\n";
		echo "         						cal2.year_scroll = false;\n";
		echo "         						cal2.time_comp = false;\n";
		echo "         						//-->\n";
		echo "         						</script>\n";
		
		if (isset($_REQUEST['subq2']) && $_REQUEST['subq2']=='results')
		{
			$s_ar=array();
			if (isset($_REQUEST['oid']) && $_REQUEST['oid']!=0)
			{
				$otxt=" oid='".$_REQUEST['oid']."' AND ";
			}
			else
			{
				$otxt="";
			}
			
			if (isset($_REQUEST['excladmin']) && $_REQUEST['excladmin']==1)
			{
				$qryA  = "SELECT securityid FROM security WHERE officeid=89;";
				$resA  = mssql_query($qryA);
				
				while ($rowA = mssql_fetch_array($resA))
				{
					$s_ar[]=$rowA['securityid'];	
				}
				
				$atxt=" oid!='89' AND ";
			}
			else
			{
				$atxt="";
			}
			
			if (isset($_REQUEST['archive']) && $_REQUEST['archive']==1)
			{
				$rtxt="jest..events";
			}
			else
			{
				$rtxt="jest_stats..events";
			}
			
			$qry2  = "SELECT ";
			$qry2 .= "*, ";
			$qry2 .= "(SELECT name FROM offices WHERE officeid=e.oid) as office, ";
			$qry2 .= "(SELECT lname FROM security WHERE securityid=e.sid) as lname, ";
			$qry2 .= "(SELECT fname FROM security WHERE securityid=e.sid) as fname ";
			$qry2 .= "FROM ".$rtxt." as e WHERE ".$otxt." ".$atxt." evdate >='".$d1."' AND evdate < '".$d2."' AND evdescrip like 'reports|operating%' ORDER BY ".$order." ".$asc.";";
			$res2  = mssql_query($qry2);
			$nrow2 = mssql_num_rows($res2);
			
			//echo $qry2."<br>";
			
			if ($nrow2 > 0)
			{
				echo "<table align=\"center\" width=\"700px\">\n";
				echo "   <tr>\n";
				echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
				echo "         <table class=\"outer\" width=\"100%\">\n";
				//echo "   			<tr>\n";
				//echo "      			<td class=\"gray\" align=\"right\" colspan=\"5\" NOWRAP><font color=\"red\">".$nrow2."</font> Record(s)</td>\n";
				//echo "   			</tr>\n";
				echo "   			<tr>\n";
				echo "      			<td class=\"ltgray_und\" align=\"left\" NOWRAP>&nbsp</td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"left\" NOWRAP>&nbsp<b>Office</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>User</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>String</b></td>\n";
				echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Date</b></td>\n";
				echo "   			</tr>\n";
				
				$ccnt=0;
				while($row2 = mssql_fetch_array($res2))
				{
					if (!in_array($row2['sid'],$s_ar))
					{
						$ccnt++;
						if ($ccnt%2)
						{
							$tbg = "white";
						}
						else
						{
							$tbg = "gray";
						}
						
						echo "   			<tr>\n";
						echo "      			<td class=\"".$tbg."\" align=\"right\" NOWRAP>".$ccnt.".</td>\n";
						echo "      			<td class=\"".$tbg."\" align=\"left\" NOWRAP>".$row2['office']."</td>\n";
						echo "      			<td class=\"".$tbg."\" align=\"left\" NOWRAP>".$row2['lname'].", ".$row2['fname']."</td>\n";
						echo "      			<td class=\"".$tbg."\" align=\"left\" NOWRAP>".$row2['evdescrip']."</td>\n";
						echo "      			<td class=\"".$tbg."\" align=\"center\" NOWRAP>".date("m/d/Y h:m",strtotime($row2['evdate']))."</td>\n";
						echo "   			</tr>\n";
					}
				}
				
				echo "			</td>\n";
				echo "   	</tr>\n";
				echo "	</table>\n";
			}
		}
	}
}

function jobclosings()
{
	error_reporting(E_ALL);
	
	//show_post_vars();
	
	$dcnt=0;
	$qry1 = "SELECT securityid,gmreports FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	//print_r($row1);

	if ($row1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to view this Report');
	}
	
	//gmreptjoin();

	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	$dtarray	=setdatearray();
	$cpny		=$_REQUEST['cpny'];
	$mdiv		=$_REQUEST['mdiv'];
	$division=$_REQUEST['division'];

	if ($mdiv!=1)
	{
		//$retext=substr($cpny,4);
		$retext=$cpny;
	}
	else
	{
		$retext=$division;
	}
	
	if (isset($_REQUEST['edit']) && $_REQUEST['edit']==1)
	{
		update_jcdata();
	}
	
	$order_ar=array(
							'jobnumber'=>array('Job #','SELECTED',''),
							'customername'=>array('Customer','SELECTED',''),
							'contracttotal'=>array('Contract','SELECTED',''),
							'actualcost'=>array('Act Cost','SELECTED',''),
							'vari'=>array('Variance','SELECTED',''),
							'salesman'=>array('SalesRep','SELECTED',''),
							'commission'=>array('Commission','SELECTED',''),
							'datecompleted'=>array('Closed','SELECTED',''),
							'datedug'=>array('Dug','SELECTED',''),
						);
	
	
	if (!empty($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	else
	{
		$order="jobnumber";
	}
	
	if (!empty($_REQUEST['asc']))
	{
		$asc=$_REQUEST['asc'];
	}
	else
	{
		$asc="desc";
	}

	$cdate=date("m/d/Y", time());

	$qryA = "SELECT CompanyName,CompanyCode FROM MAS_".$cpny."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$cpny."');";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);
	
	$qryAa = "SELECT Description FROM MAS_".$cpny."..ARB_DivisionMasterfile WHERE Division='".$division."';";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);
	
	//echo $qryA."<br>";

	if ($mdiv==1)
	{
		$qryB = "SELECT DeptNumber,DeptName FROM MAS_".$cpny."..GL7_Department WHERE DeptNumber LIKE '".$division."00%';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_row($resB);
	}

	echo "                  <table width=\"1000px\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"center\">\n";
	echo "                           <table class=\"outer\" width=\"100%\" border=0>\n";
	echo "                              <tr>\n";
	echo "					<td class=\"gray\" align=\"center\" colspan=\"10\"><b>Job Closing Report</b></td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray\" align=\"left\" NOWRAP><b>Company</b></td>\n";
	echo "					<td class=\"gray\" align=\"left\" NOWRAP><b>Division</b></td>\n";
	echo "					<td class=\"gray\" align=\"center\" NOWRAP><b>MAS</b></td>\n";
	echo "					<td class=\"gray\" align=\"center\" NOWRAP><b>Div</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"center\"><b>Report Date</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"left\"><b>From</td>\n";
	echo "                                 <td class=\"gray\" align=\"left\"><b>To</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"left\"><b>Sort</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"center\"><b>Edit</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"center\"></td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	/*
	echo "                                 <td class=\"gray\" align=\"left\" NOWRAP>XXXXXXXXXXXXXXXXXXXXXXXX</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP>XXXXXXXXXX</td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP>XXX</td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP>XX</b></td>\n";
	*/
	echo "                                 <td class=\"gray\" align=\"left\" NOWRAP>".$rowA[0]."</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP>".$rowAa['Description']."</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP>".$cpny."</td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP>".$division."</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"center\"><font size=\"1\">".$cdate."</font></td>\n";
	echo "                              <form name=\"cjreport\" action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
	echo "                                 <input type=\"hidden\" name=\"subq\" value=\"cjreport\">\n";
	echo "                                 <input type=\"hidden\" name=\"stg\" value=\"step2\">\n";
	echo "                                 <input type=\"hidden\" name=\"cpny\" value=\"".$cpny."\">\n";
	echo "                                 <input type=\"hidden\" name=\"mdiv\" value=\"".$mdiv."\">\n";
	echo "                                 <input type=\"hidden\" name=\"division\" value=\"".$division."\">\n";
	
	if (!empty($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "                                 <input type=\"hidden\" name=\"print\" value=\"1\">\n";
	}

	if ($mdiv=1)
	{
		echo "                                 <input type=\"hidden\" name=\"parent\" value=\"".$cpny."\">\n";
	}

	echo "                                 <td class=\"gray\" align=\"left\">\n";
	
	if (isset($_REQUEST['d1']) && valid_date($_REQUEST['d1']))
	{
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" value=\"".$_REQUEST['d1']."\" size=\"11\">\n";
		$dcnt++;
	}
	else
	{
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
	}
	
	echo "												<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "											</td>\n";
	echo "                                 <td class=\"gray\" align=\"left\">\n";

	if (isset($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
	{
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" value=\"".$_REQUEST['d2']."\" size=\"11\">\n";
		$dcnt++;
	}
	else
	{
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
	}

	echo "												<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "											</td>\n";
	echo "                                 <td class=\"gray\" align=\"left\">\n";
	echo "												<select name=\"order\">\n";
	
	foreach ($order_ar as $on => $ov)
	{
		if (isset($order) && $order==$on)
		{
			echo "													<option value=\"".$on."\" ".$ov[1].">".$ov[0]."</option>\n";
		}
		else
		{
			echo "													<option value=\"".$on."\" ".$ov[2].">".$ov[0]."</option>\n";
		}
	}
	
	echo "												</select>\n";
	echo "												<select name=\"asc\">\n";
	
	if (isset($_REQUEST['asc']) && $_REQUEST['asc']=="desc")
	{
		echo "													<option value=\"asc\">Asc</option>\n";
		echo "													<option value=\"desc\" SELECTED>Desc</option>\n";
	}
	else
	{
		echo "													<option value=\"asc\" SELECTED>Asc</option>\n";
		echo "													<option value=\"desc\">Desc</option>\n";
	}
	
	echo "												</select>\n";
	echo "											</td>\n";
	echo "                                 <td class=\"gray\" align=\"center\">\n";
	
	if ($_SESSION['officeid']==89 && $_SESSION['rlev'] >= 9)
	{
		echo "										<input class=\"checkboxgry\" type=\"checkbox\" name=\"edit\" value=\"1\" title=\"Check this box to edit Contract, Actual Cost, and Estimate Cost Amounts\">\n";
	}
	
	echo "									</td>\n";
	echo "                                 <td class=\"gray\" align=\"right\"><input class=\"buttondkgry\" type=\"submit\" value=\"View\"></td>\n";
	//echo "                              </form>\n";
	echo "                              </tr>\n";
	echo "                           </table>\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	
	if (isset($_REQUEST['stg']) && $_REQUEST['stg']=='step2' && $dcnt >= 2)
	{
		$qryA =
				"
					SELECT 
						a.jcid, a.jobnumber, a.customername, a.contracttotal, a.actualcost, a.estimatecost,
						(SELECT     [contracttotal] - [actualcost]) AS ActGP, a.estimatecost,
						(SELECT     [contracttotal] - [estimatecost]) AS EstGP,
						(SELECT     [contracttotal] - [actualcost]) -
						(SELECT     [contracttotal] - [estimatecost]) AS Vari,
						((SELECT    [contracttotal] - [actualcost]) -
						(SELECT     [contracttotal] - [estimatecost])) /
						(SELECT     [contracttotal] - [estimatecost]) AS VariPerc, a.salesman, a.commission, a.datecompleted, a.companycode, a.datedug
					FROM
						ZE_Stats..jobclosings as a
					WHERE
						a.companycode='".$cpny."' ";
						
						if (substr($cpny,0,2)!=$division)
						{
							$qryA .= " AND SUBSTRING(CAST(a.jobnumber as varchar),1,2)=CAST('".$division."' as varchar) ";
						}
						
		$qryA .=
				"
						AND a.datecompleted >= '".$_REQUEST['d1']." 00:00'
						AND a.datecompleted < '".$_REQUEST['d2']." 11:59:59'
					ORDER BY
						a.".$order." ".$asc.";
				";
		$resA = mssql_query($qryA);
		$nrowA = mssql_num_rows($resA);
		//a.vari ".$asc.";
		//echo $qryA."<br>";
		
		echo "                  <table width=\"1000px\" border=0>\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"center\">\n";
		echo "                           <table class=\"outer\" width=\"100%\" border=0>\n";
		echo "                              <tr>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Job</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Customer</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Contract</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Act Cost</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Act GP</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Est Cost</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Est GP</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Variance</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>%</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>SalesRep</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Commission</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Date Dug</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Closed</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "                              </tr>\n";
		
		$tct=0;
		$tac=0;
		$tec=0;
		$tag=0;
		$teg=0;
		$tvr=0;
		$tcc=0;
		$tdd=0;
		$dcnt=0;
		
		if ($nrowA > 0)
		{
			$rcnt=0;
			while ($rowA = mssql_fetch_array($resA))
			{
				$rcnt++;
				
				/*
				if ($rcnt==1)
				{
					show_array_vars($rowA);
				}
				*/
				
				if ($rcnt%2)
				{
					$tbg = "white";
					$itbg= "bboxnobr";
				}
				else
				{
					$tbg = "gray";
					$itbg= "bboxnobrg";
				}
				
				if ($rowA['Vari'] < 0)
				{
					$fcolor="red";
				}
				else
				{
					$fcolor="black";
				}
				
				echo "								<input type=\"hidden\" name=\"jcid[]\" value=\"".$rowA['jcid']."\">\n";
				echo "								<input type=\"hidden\" name=\"jcid_ct".$rowA['jcid']."[]\" value=\"".number_format($rowA['contracttotal'],2,'.','')."\">\n";
				echo "								<input type=\"hidden\" name=\"jcid_ac".$rowA['jcid']."[]\" value=\"".number_format($rowA['actualcost'],2,'.','')."\">\n";
				echo "								<input type=\"hidden\" name=\"jcid_ec".$rowA['jcid']."[]\" value=\"".number_format($rowA['estimatecost'],2,'.','')."\">\n";
				echo "								<input type=\"hidden\" name=\"jcid_cm".$rowA['jcid']."[]\" value=\"".number_format($rowA['commission'],2,'.','')."\">\n";
				echo "                              <tr>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\">".$rcnt.".</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"center\">".str_pad($rowA['jobnumber'],7,'0',STR_PAD_LEFT)."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"left\" NOWRAP>".$rowA['customername']."</td>\n";
				//echo "                                 <td class=\"".$tbg."\" align=\"left\" NOWRAP>Xxxxx Xxxxxxxx</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP>\n";
				echo "										<input class=\"".$itbg."\" type=\"text\" name=\"jcid_ct".$rowA['jcid']."[]\" value=\"".number_format($rowA['contracttotal'],2,'.','')."\" size=\"10\">\n";
				echo "								   </td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP>\n";
				echo "										<input class=\"".$itbg."\" type=\"text\" name=\"jcid_ac".$rowA['jcid']."[]\" value=\"".number_format($rowA['actualcost'],2,'.','')."\" size=\"10\">\n";
				echo "								   </td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP>".number_format($rowA['ActGP'],2,'.','')."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP>\n";
				echo "										<input class=\"".$itbg."\" type=\"text\" name=\"jcid_ec".$rowA['jcid']."[]\" value=\"".number_format($rowA['estimatecost'],2,'.','')."\" size=\"10\">\n";
				echo "								   </td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP>".number_format($rowA['EstGP'],2,'.','')."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP><font color=\"".$fcolor."\">".number_format($rowA['Vari'],2,'.',',')."</font></td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP><font color=\"".$fcolor."\">".round($rowA['VariPerc'] * 100)."%</font></td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"left\" NOWRAP>".$rowA['salesman']."</td>\n";				
				echo "                                 <td class=\"".$tbg."\" align=\"right\">\n";
				echo "										<input class=\"".$itbg."\" type=\"text\" name=\"jcid_cm".$rowA['jcid']."[]\" value=\"".number_format($rowA['commission'],2,'.','')."\" size=\"10\">\n";
				echo "      							</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"center\">\n";
				
				if (isset($rowA['datedug']) && strtotime($rowA['datedug']) > strtotime('1/1/2000') && valid_date(date("m/d/Y",strtotime($rowA['datedug']))))
				{
					echo date("m/d/Y",strtotime($rowA['datedug']));
					$dcnt++;
				}
				
				echo "											</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"center\">".date("m/d/Y",strtotime($rowA['datecompleted']))."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\">".$rcnt."</td>\n";
				echo "                              </tr>\n";
				
				$tct=$tct+$rowA['contracttotal'];
				$tac=$tac+$rowA['actualcost'];
				$tec=$tec+$rowA['estimatecost'];
				$tag=$tag+$rowA['ActGP'];
				$teg=$teg+$rowA['EstGP'];
				$tvr=$tvr+$rowA['Vari'];
				$tcc=$tcc+$rowA['commission'];
			}
			
			$tbge="ltgray_upperline";
			echo "                              <tr>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\" colspan=\"3\"><b>Totals</b></td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tct,2,'.','')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tac,2,'.','')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tag,2,'.','')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tec,2,'.','')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($teg,2,'.','')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tvr,2,'.','')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\"></td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"left\" NOWRAP></td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tcc,2,'.','')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"center\">\n";
			echo "											</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"center\">\n";
			echo "											</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"center\">\n";
			echo "											</td>\n";
			echo "                              </tr>\n";
			
		}
		
		echo "                           </table>\n";
		echo "</form>\n";	
		echo "                        </td>\n";
		echo "                     </tr>\n";
		echo "                  </table>\n";
	}
	
	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['cjreport'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['cjreport'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";
}

function jobclosings_old()
{
	error_reporting(E_ALL);
	
	//show_post_vars();
	
	$dcnt=0;
	$qry1 = "SELECT securityid,gmreports FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	//print_r($row1);

	if ($row1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to view this Report');
	}
	
	//gmreptjoin();

	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	$dtarray	=setdatearray();
	$cpny		=$_REQUEST['cpny'];
	$mdiv		=$_REQUEST['mdiv'];
	$division=$_REQUEST['division'];

	if ($mdiv!=1)
	{
		//$retext=substr($cpny,4);
		$retext=$cpny;
	}
	else
	{
		$retext=$division;
	}
	
	$order_ar=array(
							'jobnumber'=>array('Job #','SELECTED',''),
							'customername'=>array('Customer','SELECTED',''),
							'contracttotal'=>array('Contract','SELECTED',''),
							'actualcost'=>array('Act Cost','SELECTED',''),
							'vari'=>array('Variance','SELECTED',''),
							'salesman'=>array('SalesRep','SELECTED',''),
							'commission'=>array('Commission','SELECTED',''),
							'datecompleted'=>array('Closed','SELECTED',''),
							'datedug'=>array('Dug','SELECTED',''),
						);
	
	
	if (!empty($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	else
	{
		$order="jobnumber";
	}
	
	if (!empty($_REQUEST['asc']))
	{
		$asc=$_REQUEST['asc'];
	}
	else
	{
		$asc="desc";
	}

	$cdate=date("m/d/Y", time());

	$qryA = "SELECT CompanyName,CompanyCode FROM MAS_".$cpny."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$cpny."');";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);
	
	$qryAa = "SELECT Description FROM MAS_".$cpny."..ARB_DivisionMasterfile WHERE Division='".$division."';";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);
	
	//echo $qryA."<br>";

	if ($mdiv==1)
	{
		$qryB = "SELECT DeptNumber,DeptName FROM MAS_".$cpny."..GL7_Department WHERE DeptNumber LIKE '".$division."00%';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_row($resB);
		
		//echo $qryB."<br>";
		
	}

	echo "                  <table width=\"1000px\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"center\">\n";
	echo "                           <table class=\"outer\" width=\"100%\" border=0>\n";
	echo "                              <tr>\n";
	echo "											<td class=\"gray\" align=\"center\" colspan=\"10\"><b>Job Closing Report</b></td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray\" align=\"left\" NOWRAP><b>Company</b></td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP><b>Division</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP><b>MAS</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP><b>Div</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"center\"><b>Report Date</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"left\"><b>From</td>\n";
	echo "                                 <td class=\"gray\" align=\"left\"><b>To</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"left\"><b>Sort</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"center\"></td>\n";
	echo "                                 <td class=\"gray\" align=\"center\"></td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	/*
	echo "                                 <td class=\"gray\" align=\"left\" NOWRAP>XXXXXXXXXXXXXXXXXXXXXXXX</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP>XXXXXXXXXX</td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP>XXX</td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP>XX</b></td>\n";
	*/
	echo "                                 <td class=\"gray\" align=\"left\" NOWRAP>".$rowA[0]."</td>\n";
	echo "											<td class=\"gray\" align=\"left\" NOWRAP>".$rowAa['Description']."</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP>".$cpny."</td>\n";
	echo "											<td class=\"gray\" align=\"center\" NOWRAP>".$division."</b></td>\n";
	echo "                                 <td class=\"gray\" align=\"center\"><font size=\"1\">".$cdate."</font></td>\n";
	echo "                              <form name=\"cjreport\" action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
	echo "                                 <input type=\"hidden\" name=\"subq\" value=\"cjreport\">\n";
	echo "                                 <input type=\"hidden\" name=\"stg\" value=\"step2\">\n";
	echo "                                 <input type=\"hidden\" name=\"cpny\" value=\"".$cpny."\">\n";
	echo "                                 <input type=\"hidden\" name=\"mdiv\" value=\"".$mdiv."\">\n";
	echo "                                 <input type=\"hidden\" name=\"division\" value=\"".$division."\">\n";
	
	if (!empty($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "                                 <input type=\"hidden\" name=\"print\" value=\"1\">\n";
	}

	if ($mdiv=1)
	{
		echo "                                 <input type=\"hidden\" name=\"parent\" value=\"".$cpny."\">\n";
	}

	echo "                                 <td class=\"gray\" align=\"left\">\n";
	
	if (isset($_REQUEST['d1']) && valid_date($_REQUEST['d1']))
	{
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" value=\"".$_REQUEST['d1']."\" size=\"11\">\n";
		$dcnt++;
	}
	else
	{
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
	}
	
	echo "												<a href=\"javascript:cal1.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
	echo "											</td>\n";
	echo "                                 <td class=\"gray\" align=\"left\">\n";

	if (isset($_REQUEST['d2']) && valid_date($_REQUEST['d2']))
	{
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" value=\"".$_REQUEST['d2']."\" size=\"11\">\n";
		$dcnt++;
	}
	else
	{
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
	}

	echo "												<a href=\"javascript:cal2.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
	echo "											</td>\n";
	echo "                                 <td class=\"gray\" align=\"left\">\n";
	echo "												<select name=\"order\">\n";
	
	foreach ($order_ar as $on => $ov)
	{
		if (isset($order) && $order==$on)
		{
			echo "													<option value=\"".$on."\" ".$ov[1].">".$ov[0]."</option>\n";
		}
		else
		{
			echo "													<option value=\"".$on."\" ".$ov[2].">".$ov[0]."</option>\n";
		}
	}
	
	echo "												</select>\n";
	echo "												<select name=\"asc\">\n";
	
	if (isset($_REQUEST['asc']) && $_REQUEST['asc']=="desc")
	{
		echo "													<option value=\"asc\">Asc</option>\n";
		echo "													<option value=\"desc\" SELECTED>Desc</option>\n";
	}
	else
	{
		echo "													<option value=\"asc\" SELECTED>Asc</option>\n";
		echo "													<option value=\"desc\">Desc</option>\n";
	}
	
	echo "												</select>\n";
	echo "											</td>\n";
	echo "                                 <td class=\"gray\" align=\"center\" width=\"50px\">\n";
	echo "                                 	<input class=\"buttondkgry\" type=\"submit\" value=\"Select\">\n";
	echo "											</td>\n";
	echo "                                 <td class=\"gray\" align=\"center\"></td>\n";
	echo "                              </form>\n";
	echo "                              </tr>\n";
	echo "                           </table>\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	
	echo "         						<script language=\"JavaScript\">\n";
	echo "         						var cal1 = new calendar2(document.forms['cjreport'].elements['d1']);\n";
	echo "         						cal1.year_scroll = false;\n";
	echo "         						cal1.time_comp = false;\n";
	echo "         						var cal2 = new calendar2(document.forms['cjreport'].elements['d2']);\n";
	echo "         						cal2.year_scroll = false;\n";
	echo "         						cal2.time_comp = false;\n";
	echo "         						//-->\n";
	echo "         						</script>\n";
	
	if (isset($_REQUEST['stg']) && $_REQUEST['stg']=='step2' && $dcnt >= 2)
	{
		$qryA =
				"
					SELECT 
						a.jcid, a.jobnumber, a.customername, a.contracttotal, a.actualcost,
						(SELECT     [contracttotal] - [actualcost]) AS ActGP, a.estimatecost,
						(SELECT     [contracttotal] - [estimatecost]) AS EstGP,
						(SELECT     [contracttotal] - [actualcost]) -
						(SELECT     [contracttotal] - [estimatecost]) AS Vari,
						((SELECT     [contracttotal] - [actualcost]) -
						(SELECT     [contracttotal] - [estimatecost])) /
						(SELECT     [contracttotal] - [estimatecost]) AS VariPerc, a.salesman, a.commission, a.datecompleted, a.companycode, a.datedug
					FROM
						ZE_Stats..jobclosings as a
					WHERE
						a.companycode='".$cpny."' ";
						
						if (substr($cpny,0,2)!=$division)
						{
							$qryA .= " AND SUBSTRING(CAST(a.jobnumber as varchar),1,2)=CAST('".$division."' as varchar) ";
						}
						
		$qryA .=
				"
						AND a.datecompleted >= '".$_REQUEST['d1']." 00:00'
						AND a.datecompleted < '".$_REQUEST['d2']." 11:59:59'
					ORDER BY
						a.".$order." ".$asc.";
						
				";
		$resA = mssql_query($qryA);
		$nrowA = mssql_num_rows($resA);
		//a.vari ".$asc.";
		//echo $qryA."<br>";
		
		echo "                  <table width=\"1000px\" border=0>\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"center\">\n";
		echo "                           <table class=\"outer\" width=\"100%\" border=0>\n";
		echo "                              <tr>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Job</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Customer</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Contract</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Act Cost</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Act GP</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Est GP</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Variance</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>%</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>SalesRep</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Commission</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Date Dug</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"><b>Closed</b></td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "                              </tr>\n";
		
		$tct=0;
		$tac=0;
		$tec=0;
		$tag=0;
		$teg=0;
		$tvr=0;
		$tcc=0;
		$tdd=0;
		$dcnt=0;
		
		if ($nrowA > 0)
		{
			$rcnt=0;
			while ($rowA = mssql_fetch_array($resA))
			{
				$rcnt++;
				
				/*
				if ($rcnt==1)
				{
					show_array_vars($rowA);
				}
				*/
				
				if ($rcnt%2)
				{
					$tbg = "white";
				}
				else
				{
					$tbg = "gray";
				}
				
				if ($rowA['Vari'] < 0)
				{
					$fcolor="red";
				}
				else
				{
					$fcolor="black";
				}
				
				echo "                              <tr>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\">".$rcnt.".</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"center\">".str_pad($rowA['jobnumber'],7,'0',STR_PAD_LEFT)."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"left\" NOWRAP>".$rowA['customername']."</td>\n";
				//echo "                                 <td class=\"".$tbg."\" align=\"left\" NOWRAP>Xxxxx Xxxxxxxx</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP>".number_format($rowA['contracttotal'],2,'.',',')."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP>".number_format($rowA['actualcost'],2,'.',',')."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP>".number_format($rowA['ActGP'],2,'.',',')."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP>".number_format($rowA['EstGP'],2,'.',',')."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP><font color=\"".$fcolor."\">".number_format($rowA['Vari'],2,'.',',')."</font></td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\" NOWRAP><font color=\"".$fcolor."\">".round($rowA['VariPerc'] * 100)."%</font></td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"left\" NOWRAP>".$rowA['salesman']."</td>\n";				
				//echo "                                 <td class=\"".$tbg."\" align=\"left\" NOWRAP>Xxxxx Xxxxxxxx</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\">".number_format($rowA['commission'],2,'.',',')."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"center\">\n";
				
				if (isset($rowA['datedug']) && strtotime($rowA['datedug']) > strtotime('1/1/2000') && valid_date(date("m/d/Y",strtotime($rowA['datedug']))))
				{
					echo date("m/d/Y",strtotime($rowA['datedug']));
					$dcnt++;
				}
				
				echo "											</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"center\">".date("m/d/Y",strtotime($rowA['datecompleted']))."</td>\n";
				echo "                                 <td class=\"".$tbg."\" align=\"right\">".$rcnt."</td>\n";
				echo "                              </tr>\n";
				
				$tct=$tct+$rowA['contracttotal'];
				$tac=$tac+$rowA['actualcost'];
				$tec=$tec+$rowA['estimatecost'];
				$tag=$tag+$rowA['ActGP'];
				$teg=$teg+$rowA['EstGP'];
				$tvr=$tvr+$rowA['Vari'];
				$tcc=$tcc+$rowA['commission'];
			}
			
			
			
			$tbge="ltgray_upperline";
			echo "                              <tr>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\" colspan=\"3\"><b>Totals</b></td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tct,2,'.',',')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tac,2,'.',',')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tag,2,'.',',')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($teg,2,'.',',')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tvr,2,'.',',')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\"></td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"left\" NOWRAP></td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"right\">".number_format($tcc,2,'.',',')."</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"center\">\n";
			echo "											</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"center\">\n";
			echo "											</td>\n";
			echo "                                 <td class=\"".$tbge."\" align=\"center\">\n";
			echo "											</td>\n";
			echo "                              </tr>\n";
			
		}
		
		echo "                           </table>\n";
		echo "                        </td>\n";
		echo "                     </tr>\n";
		echo "                  </table>\n";	
	}
}

function arreport()
{
	$cpny     = $_REQUEST['cpny'];
	$mdiv     = $_REQUEST['mdiv'];
	$division = $_REQUEST['division'];

	$today = date("m/d/y");

	//$retext=substr($cpny,4);
	if ($mdiv==1)
	{
		$retext=$division;
	}
	else
	{
		$retext=substr($cpny,4);
	}
	
	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	$qryA    = "SELECT ";
	$qryA   .= "DISTINCT(JobNumber) ";
	$qryA   .= "FROM MAS_$cpny..BB_J2JobProfReportDetail ";
	if ($mdiv==1)
	{
		$qryA   .= "WHERE JobNumber LIKE '".$retext."%' ";
	}
	$qryA   .= "ORDER BY JobNumber;";
	$resA    = mssql_query($qryA);
	$nrowsA  = mssql_num_rows($resA);
	
	//echo $qryA.'<br>';

	$qryA1  = "SELECT CompanyName,CompanyCode ";
	$qryA1 .= "FROM MAS_".$cpny."..SY0_CompanyParameters ";
	$qryA1 .= "WHERE CompanyCode=convert(varchar,'$cpny');";
	$resA1  = mssql_query($qryA1);
	$rowA1  = mssql_fetch_row($resA1);

	/*if ($_SESSION['securityid']==26)
	{
		echo $qryA1.'<br>';
	}*/
	
	if ($mdiv==1)
	{
		$qryA2 = "SELECT DeptNumber,DeptName FROM MAS_".$cpny."..GL7_Department WHERE DeptNumber=".$retext."000000;";
		$resA2 = mssql_query($qryA2);
		$rowA2 = mssql_fetch_row($resA2);
		
		//echo $qryA2.'<br>';
	}

	while ($rowA=mssql_fetch_row($resA))
	{
		$qryB  = "SELECT ";
		$qryB .= "   DISTINCT(CostCode)";
		$qryB .= "FROM ";
		$qryB .= "   MAS_".$cpny."..BB_J2JobProfReportDetail ";
		$qryB .= "WHERE ";
		$qryB .= "   JobNumber='$rowA[0]' ";
		$qryB .= "AND ";
		$qryB .= "   Status ";
		$qryB .= "BETWEEN ";
		$qryB .= "   '1' ";
		$qryB .= "AND ";
		$qryB .= "   '3' ";
		$qryB .= "AND ";
		$qryB .= "   Invoice!='NULL' ";
		$qryB .= "OR ";
		$qryB .= "   CostCode LIKE '6%' ";
		$qryB .= "AND ";
		$qryB .= "   JobNumber='$rowA[0]' ";
		$qryB .= "AND ";
		$qryB .= "   Invoice!='NULL' ";
		$qryB .= "ORDER BY ";
		$qryB .= "   CostCode;";
		$resB  = mssql_query($qryB);
		
		//echo $qryB.'<br>';

		$tpsum=0;

		while ($rowB=mssql_fetch_row($resB))
		{
			$qryC  = "SELECT ";
			$qryC .= "   SUM(SchedPmtAmount) ";
			$qryC .= "FROM ";
			$qryC .= "   MAS_".$cpny."..BB_J2JobProfReportDetail ";
			$qryC .= "WHERE ";
			$qryC .= "   JobNumber='$rowA[0]' ";
			$qryC .= "AND ";
			$qryC .= "   CostCode='$rowB[0]';";
			$resC  = mssql_query($qryC);
			$rowC  = mssql_fetch_row($resC);
			
			//echo $qryC.'<br>';

			$qryD  = "SELECT ";
			$qryD .= "   SUM(PaymentAmount) ";
			$qryD .= "FROM ";
			$qryD .= "   MAS_".$cpny."..BB_J2JobProfReportDetail ";
			$qryD .= "WHERE ";
			$qryD .= "   JobNumber='$rowA[0]' ";
			$qryD .= "AND ";
			$qryD .= "   CostCode='$rowB[0]';";
			$resD  = mssql_query($qryD);
			$rowD  = mssql_fetch_row($resD);
			
			//echo $qryD.'<br>';

			$psum=$rowC[0]-$rowD[0];

			if ($psum != 0) // Change for Non Zero Display of Jobs
			{
				$tpsum=$tpsum+$psum;
			}
		}
		if ($tpsum != 0) // Change for Non Zero Display of Jobs
		{
			$tarray[]=array(0=>$rowA[0],1=>$tpsum);
		}
	}

	//echo "<pre>";
	//print_r($tarray);
	//echo "</pre>";
	echo "                  <table class=\"outer\" width=\"650\">\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"center\">\n";
	echo "                           <table width=\"100%\" border=0>\n";
	echo "                              <tr>\n";
	
	if ($mdiv==1)
	{
		//echo "                                 <td align=\"center\">$rowA1[0] ($cpny) ($mdiv) ($rowA2[1]) ($division)</td>\n";
		echo "                                 <td align=\"center\"><b>$rowA1[0]</b> ($cpny)</td>\n";
	}
	else
	{
		echo "                                 <td align=\"center\"><b>$rowA1[0]</b> ($cpny) ($mdiv)</td>\n";
	}
	echo "                                 <td class=\"gray\" align=\"right\">\n";
	echo "										<img src=\"images\pixel.gif\">\n";
	echo "                                 </td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray\" align=\"center\">Accounts Receivable as of $today</td>\n";
	echo "                              </tr>\n";
	echo "                           </table>\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";

	if ($nrowsA > 0)
	{
		echo "                     <tr>\n";
		echo "                        <td align=\"center\">\n";
		echo "                           <table width=\"100%\">\n";
		echo "                              <tr>\n";
		echo "                                 <td class=\"gray\" colspan=\"5\" align=\"right\"><img src=\"images\arrow_left.png\" onClick=\"history.back();\" title=\"Back\"></td>\n";
		echo "                              <tr>\n";
		echo "                              <tr>\n";
		echo "                                 <td class=\"ltgray_und\">Phase</td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"right\">Last Activity</td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"right\">Invoices</td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"right\">Payments</td>\n";
		echo "                                 <td class=\"ltgray_und\" align=\"right\">Phase Total</td>\n";
		echo "                              <tr>\n";

		if (is_array($tarray))
		{
			foreach ($tarray as $n=>$v)
			{
				if (is_array($v))
				{
					//foreach ($v as $subn=>$subv)
					//{
						$subv=$v[0];
						//echo $subv." X1<br>";
						//echo "<br>";
						$cnm="000".substr($subv,3,4);
						$div=substr($subv,0,2);
						//echo $div."<br>";
						//echo $cnm."<br>";

						$qryE  = "SELECT ";
						$qryE .= "   CustomerNumber, ";
						$qryE .= "   CustomerName, ";
						$qryE .= "   AddressLine1, ";
						$qryE .= "   PhoneNumber ";
						$qryE .= "FROM ";
						$qryE .= "   MAS_$cpny..AR1_CustomerMaster ";
						$qryE .= "WHERE ";
						
						if ($mdiv==1)
						{
							$qryE .= "   CustomerNumber='$cnm' ";
						}
						else
						{
							$qryE .= "   CustomerNumber='$cnm' ";
							//$qryE .= "   CustomerNumber='$subv' ";
						}
						
						if ($mdiv==1)
						{
							$qryE .= "AND Division='$div' ";
						}
						
						$resE  = mssql_query($qryE);
						
						/*if ($_SESSION['securityid']==26)
						{
							echo $qryE."<br>";
						}*/

						while ($rowE  = mssql_fetch_row($resE))
						{
							$fjn=$rowE[0];
							//echo $fjn." X2<br>";
							echo "   <tr>\n";
							echo "      <td class=\"wh\" colspan=\"5\">\n";
							echo "         <table class=\"outer\">\n";
							echo "            <tr>\n";
							echo "               <td class=\"gray\" width=\"150px\"><b>".$subv."</b></td>\n";
							echo "               <td class=\"gray\" width=\"250px\"><b>".$rowE[1]."</b></td>\n";
							echo "               <td class=\"gray\" width=\"150px\"><b>".$rowE[2]."</b></td>\n";
							echo "               <td class=\"gray\" width=\"100px\" align=\"right\"><b>".$rowE[3]."</b></td>\n";
							echo "            </tr>\n";
							echo "         </table>\n";
							echo "      </td>\n";
							echo "   </tr>\n";

							$qryF  = "SELECT ";
							$qryF .= "   DISTINCT(CostCode)";
							$qryF .= "FROM ";
							$qryF .= "   MAS_$cpny..BB_J2JobProfReportDetail ";
							$qryF .= "WHERE ";
							$qryF .= "   JobNumber='$subv' ";
							$qryF .= "AND ";
							$qryF .= "   Status ";
							$qryF .= "BETWEEN ";
							$qryF .= "   '1' ";
							$qryF .= "AND ";
							$qryF .= "   '3' ";
							$qryF .= "AND ";
							$qryF .= "   Invoice!='NULL' ";
							$qryF .= "OR ";
							$qryF .= "   CostCode LIKE '6%' ";
							$qryF .= "AND ";
							$qryF .= "   JobNumber='$subv' ";
							$qryF .= "AND ";
							$qryF .= "   Invoice!='NULL' ";
							$qryF .= "ORDER BY ";
							$qryF .= "   CostCode;";
							$resF  = mssql_query($qryF);

							$tspsum=0;

							while ($rowF=mssql_fetch_row($resF))
							{
								$qryG  = "SELECT ";
								$qryG .= "   SUM(SchedPmtAmount) ";
								$qryG .= "FROM ";
								$qryG .= "   MAS_$cpny..BB_J2JobProfReportDetail ";
								$qryG .= "WHERE ";
								$qryG .= "   JobNumber='$subv' ";
								$qryG .= "AND ";
								$qryG .= "   CostCode='$rowF[0]';";
								$resG  = mssql_query($qryG);
								$rowG  = mssql_fetch_row($resG);

								$qryH  = "SELECT ";
								$qryH .= "   SUM(PaymentAmount) ";
								$qryH .= "FROM ";
								$qryH .= "   MAS_$cpny..BB_J2JobProfReportDetail ";
								$qryH .= "WHERE ";
								$qryH .= "   JobNumber='$subv' ";
								$qryH .= "AND ";
								$qryH .= "   CostCode='$rowF[0]';";
								$resH  = mssql_query($qryH);
								$rowH  = mssql_fetch_row($resH);

								$qryI  = "SELECT ";
								$qryI .= "   DISTINCT(CostCodeDesc) ";
								$qryI .= "FROM ";
								$qryI .= "   MAS_$cpny..BB_J2JobProfReportDetail ";
								$qryI .= "WHERE ";
								$qryI .= "   CostCode='$rowF[0]';";
								$resI  = mssql_query($qryI);
								$rowI  = mssql_fetch_row($resI);

								$qryJ  = "SELECT ";
								$qryJ .= "   MAX(CostDate) ";
								$qryJ .= "FROM ";
								$qryJ .= "   MAS_$cpny..BB_J2JobProfReportDetail ";
								$qryJ .= "WHERE ";
								$qryJ .= "   JobNumber='$subv' ";
								$qryJ .= "AND ";
								$qryJ .= "   CostCode='$rowF[0]';";
								$resJ  = mssql_query($qryJ);
								$rowJ  = mssql_fetch_row($resJ);

								$qryK  = "SELECT DISTINCT(Invoice) FROM MAS_$cpny..BB_J2JobProfReportDetail WHERE JobNumber='$subv' AND CostCode='$rowF[0]';";
								$resK  = mssql_query($qryK);
								$rowK  = mssql_fetch_row($resK);

								$qryL  = "SELECT InvoiceDueDate FROM MAS_$cpny..AR4_OpenInvoice WHERE CustomerNumber='$subv' AND InvoiceNumber='$rowK[0]';";
								$resL  = mssql_query($qryL);
								$rowL  = mssql_fetch_row($resL);

								$spsum=$rowG[0]-$rowH[0];

								$f1=fmoney($rowG[0]);
								$f2=fmoney($rowH[0]);
								$f3=fmoney($spsum);
								
								if (strtotime($rowJ[0]) >= strtotime('1/1/2000'))
								{
									$fd=date('m/d/y',strtotime($rowJ[0]));
								}
								else
								{
									$fd='';
								}
								//$fd=dateformat($rowJ[0]);
								//$fd=date('m/d/y',strtotime($rowJ[0]));
								//$fd=$rowJ[0];

								if ($spsum != 0) // Change for Non Zero Display of Jobs
								{
									echo "   <tr>\n";
									echo "      <td class=\"wh_und\">$rowI[0]</td>\n";
									echo "      <td class=\"wh_und\" align=\"right\">$fd</td>\n";
									echo "      <td class=\"wh_und\" align=\"right\">$f1</td>\n";
									echo "      <td class=\"wh_und\" align=\"right\">$f2</td>\n";
									echo "      <td class=\"wh_und\" align=\"right\">$f3</td>\n";
									echo "   </tr>\n";
									$tspsum=$tspsum+$spsum;
								}
							}
							
							$f4=fmoney($tspsum);
							echo "   <tr>\n";
							echo "      <td class=\"wh_und\" colspan=\"4\" align=\"right\">Job Total Due</td>\n";
							echo "      <td class=\"wh_und\" align=\"right\"><b>$f4</b></td>\n";
							echo "   </tr>\n";
							$gtsum[]=$tspsum;
						}
					//}
				}
			}
		}
		
		$f5=fmoney(array_sum($gtsum));
		echo "   <tr>\n";
		echo "      <td class=\"wh_und\" colspan=\"4\" align=\"right\">Grand Total</td>\n";
		echo "      <td align=\"right\" class=\"wh_und\"><b>$f5</b></td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		
		//print_r($gtsum);
	}
	else
	{
		echo "   <tr><td align=\"center\"><font class=\"ltitle\">No Records found. Turbo Report Generation Required.</font></td></tr>\n";
		echo "</table>\n";
	}
}

function spec_code_YY()
{	
	if (!empty($_REQUEST['specYY']) && $_REQUEST['specYY']==1)
	{
		echo "<input type=\"checkbox\" class=\"checkbox\" name=\"specYY\" value=\"1\" title=\"Disables Source Journal YY entries from display or calculations\" CHECKED>\n";
	}
	else
	{
		echo "<input type=\"checkbox\" class=\"checkbox\" name=\"specYY\" value=\"1\" title=\"Disables Source Journal YY entries from display or calculations\">\n";
	}
}

function spec_code_qtext()
{
	$qtext	="";
	
	if (!empty($_REQUEST['specYY']) && $_REQUEST['specYY']==1)
	{
		$qtext=$qtext." AND SourceJournal!='YY' ";
	}
	
	return $qtext;
}

function nq_jpsum($ccode,$cdiv,$mdiv)
{
	$out		=0;

	/*
	$qryA  = "SELECT  ";
	$qryA .= "	SUM(IsNull(JC3.TransactionAmount,0)) AS tranamt ";
	$qryA .= "FROM  ";
	$qryA .= "	MAS_".$ccode."..JC2_JobCostDetail JC2, MAS_".$ccode."..JC_B3EstimateDetail JC3, ";
	$qryA .= "	MAS_".$ccode."..JC_A2PaymentSummaryFile JCA2, MAS_".$ccode."..JCC_CostCodeMaster JCC ";
	$qryA .= "WHERE  ";
	$qryA .= "	JC2.JobNumber = JC3.JobNumber and JC2.CostCode = JC3.CostCode and ";
	$qryA .= "	JC2.CostType = JC3.CostType and JC2.CostCode = JCC.CodeCost and  ";
	$qryA .= "	(JC2.JobNumber + JC2.CostCode) *= (JCA2.JobNumber + JCA2.CostCode) and ";
	$qryA .= "	JC3.RecordType = 2  and JCC.CostType = '';";
	*/
	//$qryA  = "SELECT SUM(RevisedEstimatedCost) AS tranamt FROM MAS_".$ccode."..JC2_JobCostDetail;";
	$qryA  = "SELECT SUM(ISNULL(RevisedEstimatedCost,0)) AS tranamt FROM MAS_".$ccode."..JC2_JobCostDetail WHERE SUBSTRING(JobNumber,1,2) = '".$cdiv."' OR SUBSTRING(JobNumber,1,2)='00';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	//echo $qryA."<br>";
	$out		=$rowA['tranamt'];
	return 	$out;
}

function nq_arjns($ccode,$cdiv)
{
	$out 	=array();
	$sum0	=0;
	$sum1	=0;
	$sum2	=0;

	$qryA  = "SELECT  ";
	//$qryA .= "	IsNull(j.JobNumber,''), ";
	$qryA .= "	( ";
	$qryA .= "		SELECT sum(OriginalEstimatedCost) ";
	$qryA .= "		From MAS_".$ccode."..JC2_JobCostDetail ";
	$qryA .= "		WHERE CostType <> ''  and JobNumber=j.JobNumber ";
	$qryA .= "	) as est, ";
	$qryA .= "	( ";
	$qryA .= "		SELECT sum(IsNull(PaymentAmount,0)) as payamt ";
	$qryA .= "		FROM MAS_".$ccode."..JC_A1PaymentHistoryDetailFile ";
	$qryA .= "		WHERE JobNumber=j.JobNumber ";
	$qryA .= "	) as pay, ";
	$qryA .= "	( ";
	$qryA .= "		SELECT sum(JCA2.ContractAmount)  ";
	$qryA .= "		FROM MAS_".$ccode."..JC_A2PaymentSummaryFile JCA2, ";
	$qryA .= "		MAS_".$ccode."..JC1_JobMaster JC1  ";
	$qryA .= "		WHERE JCA2.JobNumber = JC1.JobNumber and JC1.JobNumber=j.JobNumber ";
	$qryA .= "	) as ctr ";
	$qryA .= "FROM  ";
	$qryA .= "	MAS_".$ccode."..JC1_JobMaster as j ";
	$qryA .= "WHERE  ";
	$qryA .= "	j.JobNumber not in ";
	$qryA .= "	(select distinct JobNumber from MAS_".$ccode."..JC3_TransactionDetail where costcode='508L00000');";

	//echo $qryA."<br>";

	$resA = mssql_query($qryA);

	while ($rowA = mssql_fetch_array($resA))
	{
		$sum0	=$sum0+$rowA['ctr'];
		$sum1	=$sum1+$rowA['est'];
		$sum2	=$sum2+$rowA['pay'];
	}

	$out		=array($sum0,$sum1,$sum2);
	return $out;
}

function nq_arjns_div($ccode,$cdiv)
{
	$out 	=array();
	$sum0	=0;
	$sum1	=0;
	$sum2	=0;

	$qryA  = "SELECT  ";
	//$qryA .= "	IsNull(j.JobNumber,''), ";
	$qryA .= "	( ";
	$qryA .= "		SELECT sum(OriginalEstimatedCost) ";
	$qryA .= "		From MAS_".$ccode."..JC2_JobCostDetail ";
	$qryA .= "		WHERE CostType <> ''  and JobNumber=j.JobNumber and subtring(JobNumber,1,2)='".$cdiv."'";
	$qryA .= "	) as est, ";
	$qryA .= "	( ";
	$qryA .= "		SELECT sum(IsNull(PaymentAmount,0)) as payamt ";
	$qryA .= "		FROM MAS_".$ccode."..JC_A1PaymentHistoryDetailFile ";
	$qryA .= "		WHERE JobNumber=j.JobNumber and subtring(JobNumber,1,2)='".$cdiv."'";
	$qryA .= "	) as pay, ";
	$qryA .= "	( ";
	$qryA .= "		SELECT sum(JCA2.ContractAmount)  ";
	$qryA .= "		FROM MAS_".$ccode."..JC_A2PaymentSummaryFile JCA2, ";
	$qryA .= "		MAS_".$ccode."..JC1_JobMaster JC1  ";
	$qryA .= "		WHERE JCA2.JobNumber = JC1.JobNumber and JC1.JobNumber=j.JobNumber and subtring(JobNumber,1,2)='".$cdiv."";
	$qryA .= "	) as ctr ";
	$qryA .= "FROM  ";
	$qryA .= "	MAS_".$ccode."..JC1_JobMaster as j ";
	$qryA .= "WHERE  ";
	$qryA .= "	j.JobNumber not in ";
	$qryA .= "	(select distinct JobNumber from MAS_".$ccode."..JC3_TransactionDetail where costcode='508L00000';";

	//echo $qryA."<br>";

	$resA = mssql_query($qryA);

	while ($rowA = mssql_fetch_array($resA))
	{
		$sum0	=$sum0+$rowA['ctr'];
		$sum1	=$sum1+$rowA['est'];
		$sum2	=$sum2+$rowA['pay'];
	}

	$out		=array($sum0,$sum1,$sum2);
	return $out;
}

function netquick()
{
	error_reporting(E_ALL);
	//show_post_vars();
	//$ctime	=date("m/d/Y",time());
	$ctime	=date("m/d/Y h:i A",time());
	$c_exar	=array(282,311,540,801,870,880,890,900,910,920,931,940,950,990,999);
	$d_exar	=array(001,002,003,004,600);

	$nq1	=0;
	$nq2	=0;
	$nq3	=0;
	$nq5	=0;
	$nq6	=0;
	$nq7	=0;
	$nq8	=0;
	$nq9	=0;
	$nq10	=0;
	$nq11	=0;
	$nq12	=0;
	$nq13	=0;
	$nq14	=0;
	$nq15	=0;
	$nq16	=0;
	$nq17	=0;
	$nq18	=0;
	//$division = 36;
	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	//$qry   = "SELECT name FROM master..sysdatabases WHERE name LIKE '%MAS%' AND name!='master' AND name!='MAS_SYSTEM' ORDER BY name;";
	/*
	if ($_REQUEST['subq']==1)
	{
		$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE mdiv = ".$_REQUEST['division'].";";
	}
	else
	{
		$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE type <= 3 ORDER by type,company;";
	}
	*/
	
	$qrypre   = "SELECT company,division FROM ZE_Stats..divtocomp WHERE company= ".$_REQUEST['cpny']." and division = ".$_REQUEST['division'].";";
	$respre   = mssql_query($qrypre);
	$rowpre 	= mssql_fetch_array($respre);
	
	
	//echo $rowpre['company']."<br>";
	//echo $rowpre['division']."<br>";
	//echo $qrypre."<br>";
	
	$qry   = "SELECT company,division FROM ZE_Stats..divtocomp WHERE company= ".$_REQUEST['cpny']." and division = ".$_REQUEST['division'].";";
	$res   = mssql_query($qry);
	
	$qryA = "SELECT CompanyName,CompanyCode FROM MAS_".$rowpre['company']."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$rowpre['company']."');";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	//echo $qryA."<br>";

	echo "		<table class=\"outer\">\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"center\" colspan=\"3\"><font><b>Net Quick Position Calculation<b></font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"center\" colspan=\"3\"><font><b>".$rowA['CompanyName']."<b> (".$rowpre['company'].") (".$rowpre['division'].")</font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"center\" colspan=\"3\"><font><b>".$ctime."<b></font></td>\n";
	echo "			</tr>\n";

	$tnq1=0;
	//$ccnt=0;
	while ($row=mssql_fetch_row($res))
	{
		//if ($ccnt==0)
		//{
			//echo $rowpre['company']."<br>";
			//echo $rowpre['division']."<br>";
			$retext=$row[0];
			if (!in_array($retext,$c_exar))
			{
				$ng16=0;
				$ng17=0;
				$qryAo = "SELECT CompanyName,CompanyCode,GLFiscalYear FROM MAS_".$row[0]."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'$retext');";
				$resAo = mssql_query($qryAo);
				$rowAo = mssql_fetch_row($resAo);
				
				//echo $qryA."<br>---<br>";
					
				$cfyr	=$rowAo[2];
				
				$qryAa = "SELECT FiscalYr, Period1EndingDate, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$cfyr."';";
				$resAa = mssql_query($qryAa);
				$rowAa = mssql_fetch_array($resAa);
				
				//echo $qryAa."<br>---<br>";
			
				$pfyr	=$rowAa['FiscalYr']-1;
			
				$qryAb = "SELECT FiscalYr, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$pfyr."';";
				$resAb = mssql_query($qryAb);
				$rowAb = mssql_fetch_array($resAb);
				$nrowAb= mssql_num_rows($resAb);
				
				//echo $qryAb."<br>---<br>";
				
				$p2fyr =$rowAb['FiscalYr']-1;
				
				$qryAc = "SELECT FiscalYr, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$p2fyr."';";
				$resAc = mssql_query($qryAc);
				$rowAc = mssql_fetch_array($resAc);
				$nrowAc= mssql_num_rows($resAc);
				
				//echo $qryAc."<br>---<br>";
			
				$endd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
				$pendd=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
		
				if ($nrowAb == 1)
				{
					$begd	=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
				}
				else
				{
					$begd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
				}
	
				if ($nrowAc == 1)
				{
					$pbegd	=date("m/d/Y",strtotime($rowAc['Period12EndingDate']))." 11:59:59";
				}
				else
				{
					$pbegd	=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
				}
				
				$qryB = "SELECT MAX(FiscalYr) AS fsyr FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile;";
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_array($resB);
				
				$qryC = "SELECT loansfrom,pmntsto FROM prioryearloans WHERE company='".$rowpre['company']."' and division='".$rowpre['division']."' AND fiscalyear='".$pfyr."';";
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_array($resC);
				$nrowC = mssql_num_rows($resC);
				
				//echo "PriB: ".$pbegd."<br>";
				//echo "PriE: ".$pendd."<br>";
				//echo "qryC: ".$qryC."<br>";

				if ($rowpre['company']==600 && $rowpre['division'] == 62)
				{
					$gl101	=gl_pull_simple_cib($rowpre['company'],63,1,101,$begd,$endd,$rowAa['FiscalYr']);
					$gl102	=gl_pull_simple_cib($rowpre['company'],63,1,102,$begd,$endd,$rowAa['FiscalYr']);
					$gl103	=gl_pull_simple_cib($rowpre['company'],63,1,103,$begd,$endd,$rowAa['FiscalYr']);
					$gl104	=gl_pull_simple_cib($rowpre['company'],63,1,104,$begd,$endd,$rowAa['FiscalYr']);
				}
				else
				{
					$gl101	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,101,$begd,$endd,$rowAa['FiscalYr']);
					$gl102	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,102,$begd,$endd,$rowAa['FiscalYr']);
					$gl103	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,103,$begd,$endd,$rowAa['FiscalYr']);
					$gl104	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,104,$begd,$endd,$rowAa['FiscalYr']);
				}
					$gl107	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,107,$begd,$endd,$rowAa['FiscalYr']);
					$gl110	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,110,$begd,$endd,$rowAa['FiscalYr']);
					$gl190	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,190,$begd,$endd,$rowAa['FiscalYr']);
					$gl412c	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,412,$begd,$endd,$rowAa['FiscalYr']);
					//$gl412p	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,412,$pbegd,$pendd,$pfyr);
					$gl729c	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,729,$begd,$endd,$rowAa['FiscalYr']);
					//$gl729p	=gl_pull_simple_cib($rowpre['company'],$rowpre['division'],1,729,$pbegd,$pendd,$pfyr);
					$arjns	=nq_arjns($rowpre['company'],$rowpre['division']);
					$jpsum	=nq_jpsum($rowpre['company'],$rowpre['division'],0);
	
					$nq1		=$gl101 + $gl102 + $gl103 + $gl104;
					$nq2		=$gl110;
					$nq3		=$arjns[0] - $arjns[2];
					$nq4		=$gl110 - $nq3;
					$nq5		=$jpsum;
					$nq6		=$arjns[1];
					$nq7		=$nq5 - $nq6;
					$nq8		=$gl190;
					$nq9		=$nq7 - $gl190;
					$nq10		=$nq4 - $nq9;
					$nq11		=$gl107;
					$nq12		=$arjns[2];
					$nq13		=($nq1 + $nq10 + $gl107) - $nq12;
					$nq14		=$gl412c;
					$nq15		=$gl729c;
					
					if ($nrowC > 0)
					{
						if (!empty($rowC['loansfrom']))
						{
							$nq16=$rowC['loansfrom'];
						}
						
						if (!empty($rowC['pmntsto']))
						{
							$nq17=$rowC['pmntsto'];
						}
					}
					
					//$nq18		=$nq13 + (($nq14 + $nq15) + ($nq16 + $nq17));
					$nq18		=$nq13 - $nq14 + $nq15 - $nq16 + $nq17;
	
					$tnq1		=$tnq1+$nq1;
	
					echo "			<tr>\n";
					//echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$rowA[0]."</font></td>\n";
					//echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$retext."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">1.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">G/L General Cash</font></td><!-- 1 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq1)."</font></td><!-- 1 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">2.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Gross A/R in Job Prof</font></td><!-- 2 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq2)."</font></td><!-- 2 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">3.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">A/R on Jobs not Started</font></td><!-- 3 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq3)."</font></td><!-- 3 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">4.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Net A/R in Job Prof</font></td><!-- 4 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq4)."</font></td><!-- 4 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">5.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Gross Est Costs per Job Prof</font></td><!-- 5 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq5)."</font></td><!-- 5 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">6.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Est Cost Jobs not Started</font></td><!-- 6 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq6)."</font></td><!-- 6 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">7.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Net Est Costs per Job Prof</font></td><!-- 7 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq7)."</font></td><!-- 7 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">8.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Actual Costs per Job Prof</font></td><!-- 8 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq8)."</font></td><!-- 8 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">9.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Est Cost to Complete</font></td><!-- 9 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq9)."</font></td><!-- 9 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">10.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Position on Jobs</font></td><!-- 10 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq10)."</font></td><!-- 10 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">11.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Physical Inventory</font></td><!-- 11 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq11)."</font></td><!-- 11 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">12.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Cust Pmts Jobs not Started</font></td><!-- 12 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq12)."</font></td><!-- 12 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">13.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Net Quick Position (No NM)</font></td><!-- 13 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq13)."</font></td><!-- 13 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">14.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Loans from NM YTD</font></td><!-- 14 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq14)."</font></td><!-- 14 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">15.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Pmts to NM YTD</font></td><!-- 15 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq15)."</font></td><!-- 15 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">16.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Loans from NM Prior Yr</font></td><!-- 16 -->\n";
					//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl412p)."</font></td><!-- 16 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq16)."</font></td><!-- 16 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">17.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Pmts to NM Prior Yr</font></td><!-- 17 -->\n";
					//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl729p)."</font></td><!-- 17 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq17)."</font></td><!-- 16 -->\n";
					echo "			</tr>\n";
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">18.</font></td>\n";
					echo "				<td class=\"und\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Net Quick Position (with NM)</font></td><!-- 18 -->\n";
					echo "				<td class=\"wh_und\" align=\"right\" width=\"125\"><font size=\"1\">".number_format($nq18)."</font></td><!-- 18 -->\n";
					echo "			</tr>\n";
					//$ccnt++;
			}
		//}
	}
	
	echo "		</table>\n";
}

function netquick_admin()
{
	//echo "TEST";
	error_reporting(E_ALL);
	
	if ($_SESSION['officeid']!=89)
	{
		echo "You do not have appropriate permission to view this resource";
		exit;
	}
	
	$mssql_ser	= "192.168.1.22";
	$mssql_db	= "MAS_SYSTEM";
	$mssql_user	= "MAS_REPORTS";
	$mssql_pass	= "reports";

	mssql_connect($mssql_ser,$mssql_user,$mssql_pass) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($mssql_db) or die("Table unavailable");
	
	$c_exar	=array(270,282,311,340,399,540,570,580,620,630,660,801,810,820,830,840,850,860,870,880,890,900,910,920,931,940,950,990,999);
	$d_exar	=array(001,002,003,004,600,888);

	$nq1		=0;
	$nq2		=0;
	$nq3		=0;
	$nq4		=0;
	$nq5		=0;
	$nq6		=0;
	$nq7		=0;
	$nq8		=0;
	$nq9		=0;
	$nq10		=0;
	$nq11		=0;
	$nq12		=0;
	$nq13		=0;
	$nq14		=0;
	$nq15		=0;
	$nq16		=0;
	$nq17		=0;
	$nq18		=0;
	$tnq1		=0;
	$tnq2		=0;
	$tnq3		=0;
	$tnq4		=0;
	$tnq5		=0;
	$tnq6		=0;
	$tnq7		=0;
	$tnq8		=0;
	$tnq9		=0;
	$tnq10	=0;
	$tnq11	=0;
	$tnq12	=0;
	$tnq13	=0;
	$tnq14	=0;
	$tnq15	=0;
	$tnq16	=0;
	$tnq17	=0;
	$tnq18	=0;
	$tnql1	=0;
	$tnql2	=0;
	$tnql3	=0;
	$tnql4	=0;
	$tnql5	=0;
	$tnql6	=0;
	$tnql7	=0;
	$tnql8	=0;
	$tnql9	=0;
	$tnql10	=0;
	$tnql11	=0;
	$tnql12	=0;
	$tnql13	=0;
	$tnql14	=0;
	$tnql15	=0;
	$tnql16	=0;
	$tnql17	=0;
	$tnql18	=0;
	$tnqllt	=0;
	$ctype	="";
	$oldt		=1;
	$ccnt		=0;
	$fsize	=".5";
	
	$currdate	=date("m/d/Y h:i A",time());
	
	//$qry   = "SELECT company,division,type FROM ZE_Stats..divtocomp WHERE type <= 1 ORDER by type,company;";
	$qry   = "SELECT company,division,type FROM ZE_Stats..divtocomp WHERE type <= 3 AND substring(company,1,2)=division ORDER by type,company;";
	$res   = mssql_query($qry);
	$nrow	 = mssql_num_rows($res);

	$ps="90px";
	echo "		<table class=\"outer\">\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"left\" colspan=\"6\"><font><b>Net Quick Rollup<b></font></td>\n";
	echo "				<td class=\"gray\" align=\"right\" colspan=\"16\"><font><b>".$currdate."<b></font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"und\" align=\"left\" valign=\"bottom\"><font size=\"1\">Company</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\"><font size=\"1\">Code</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\"><font size=\"1\">Type</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\"><font size=\"1\">Curr<br>FY</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">G/L Gen<br>Cash</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Gross A/R<br>Job Prof</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">A/R <br>JnS</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Net A/R<br>Job Prof</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Gross Est<br>Cost<br>Job Prof</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Est Costs<br>JnS</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Net Est<br>Costs<br>Job Prof</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Act Costs<br>Job Prof</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Est Cost<br>Compl</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Pos<br>on<br>Jobs</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Physical<br>Inv</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Cust Pmts<br>JnS</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">NQ Pos<br>(No NM)</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Loans from<br>NM YTD</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Pmts to<br>NM YTD</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Loans<br>from NM<br>Pri YR</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">Pmts<br>to NM<br>Pri YR</font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"".$ps."\" valign=\"bottom\"><font size=\"1\">NQ Pos<br>(with NM)</font></td>\n";
	echo "			</tr>\n";

	while ($row=mssql_fetch_row($res))
	{
		$ccnt++;
		$nq16=0;
		$nq17=0;

		if ($row[2]==1)
		{
			$ctype="PA";
		}
		elseif ($row[2]==2)
		{
			$ctype="FIT";
		}
		elseif ($row[2]==3)
		{
			$ctype="FR";
		}

		if (!in_array($row[0],$c_exar))
		{
			$t="";
			$qryAo = "SELECT CompanyName,CompanyCode,GLFiscalYear FROM MAS_".$row[0]."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'$row[0]');";
			$resAo = mssql_query($qryAo);
			$rowAo = mssql_fetch_row($resAo);
				
			//echo $qryAo."<br>---<br>";
					
			$cfyr	=$rowAo[2];
				
			$qryAa = "SELECT FiscalYr, Period1EndingDate, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$cfyr."';";
			$resAa = mssql_query($qryAa);
			$rowAa = mssql_fetch_array($resAa);
				
			//echo $qryAa."<br>---<br>";
			
			$pfyr	=$rowAa['FiscalYr']-1;
			
			$qryAb = "SELECT FiscalYr, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$pfyr."';";
			$resAb = mssql_query($qryAb);
			$rowAb = mssql_fetch_array($resAb);
			$nrowAb= mssql_num_rows($resAb);
				
				//echo $qryAb."<br>---<br>";
				
			$p2fyr =$rowAb['FiscalYr']-1;
				
			$qryAc = "SELECT FiscalYr, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$p2fyr."';";
			$resAc = mssql_query($qryAc);
			$rowAc = mssql_fetch_array($resAc);
			$nrowAc= mssql_num_rows($resAc);
				
				//echo $qryAc."<br>---<br>";
			
			$endd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
			$pendd=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
		
			if ($nrowAb == 1)
			{
				$begd	=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
			}
			else
			{
					$begd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
			}
	
			if ($nrowAc == 1)
			{
				$pbegd	=date("m/d/Y",strtotime($rowAc['Period12EndingDate']))." 11:59:59";
			}
			else
			{
				$pbegd	=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
			}
				
			$qryB = "SELECT MAX(FiscalYr) AS fsyr FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile;";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
				
			$qryC = "SELECT loansfrom,pmntsto FROM ZE_Stats..prioryearloans WHERE company='".$row[0]."' and division='".$row[1]."' AND fiscalyear='".$pfyr."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);
			$nrowC = mssql_num_rows($resC);
				
			$gl101	=gl_pull_simple_cib($row[0],$row[1],1,101,$begd,$endd,$rowAa['FiscalYr']);
			$gl102	=gl_pull_simple_cib($row[0],$row[1],1,102,$begd,$endd,$rowAa['FiscalYr']);
			$gl103	=gl_pull_simple_cib($row[0],$row[1],1,103,$begd,$endd,$rowAa['FiscalYr']);
			$gl104	=gl_pull_simple_cib($row[0],$row[1],1,104,$begd,$endd,$rowAa['FiscalYr']);
			$gl107	=gl_pull_simple_cib($row[0],$row[1],1,107,$begd,$endd,$rowAa['FiscalYr']);
			$gl110	=gl_pull_simple_cib($row[0],$row[1],1,110,$begd,$endd,$rowAa['FiscalYr']);
			$gl190	=gl_pull_simple_cib($row[0],$row[1],1,190,$begd,$endd,$rowAa['FiscalYr']);
			$gl412c	=gl_pull_simple_cib($row[0],$row[1],1,412,$begd,$endd,$rowAa['FiscalYr']);
			$gl729c	=gl_pull_simple_cib($row[0],$row[1],1,729,$begd,$endd,$rowAa['FiscalYr']);
			$arjns	=nq_arjns($row[0],$row[1]);
			$jpsum	=nq_jpsum($row[0],$row[1],0);
	
			$nq1		=$gl101 + $gl102 + $gl103 + $gl104;
			$nq2		=$gl110;
			$nq3		=$arjns[0] - $arjns[2];
			$nq4		=$gl110 - $nq3;
			$nq5		=$jpsum;
			$nq6		=$arjns[1];
			$nq7		=$nq5 - $nq6;
			$nq8		=$gl190;
			$nq9		=$nq7 - $gl190;
			$nq10		=$nq4 - $nq9;
			$nq11		=$gl107;
			$nq12		=$arjns[2];
			$nq13		=($nq1 + $nq10 + $gl107) - $nq12;
			$nq14		=$gl412c;
			$nq15		=$gl729c;
					
			if ($nrowC > 0)
			{
				$t="(HIT)";					
				if (!empty($rowC['loansfrom']) && $rowC['loansfrom']!=0)
				{
					$nq16=$rowC['loansfrom'];
				}
				else
				{
					$ng16=0;	
				}
					
				if (!empty($rowC['pmntsto']) && $rowC['pmntsto']!=0)
				{
					$nq17=$rowC['pmntsto'];
				}
				else
				{
					$ng17=0;	
				}
			}
			else
			{
				$ng16=0;
				$ng17=0;
			}
				
			if ($nq14 < 0)
			{
				$nq14=$nq14 * -1;
			}
					
			$nq18		=$nq13 - $nq14 + $nq15 - $nq16 + $nq17;
			
			if ($oldt != $row[2])
			{
				echo "			<tr>\n";
				echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"><b>Sub Total</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"></td>\n";
				echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"></td>\n";
				echo "				<td class=\"wh_und\" align=\"center\"><font size=\"".$fsize."\"></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql1)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql2)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql3)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql4)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql5)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql6)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql7)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql8)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql9)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql10)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql11)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql12)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql13)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql14)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql15)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql16)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql17)."</b></font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql18)."</b></font></td>\n";
				echo "			</tr>\n";
				echo "			<tr>\n";
				echo "				<td class=\"wh_und\" colspan=\"22\" align=\"left\">&nbsp</td>\n";
				echo "			</tr>\n";
				$tnql1	=0;
				$tnql2	=0;
				$tnql3	=0;
				$tnql4	=0;
				$tnql5	=0;
				$tnql6	=0;
				$tnql7	=0;
				$tnql8	=0;
				$tnql9	=0;
				$tnql10	=0;
				$tnql11	=0;
				$tnql12	=0;
				$tnql13	=0;
				$tnql14	=0;
				$tnql15	=0;
				$tnql16	=0;
				$tnql17	=0;
				$tnql18	=0;
			}
			

			echo "			<tr>\n";
			echo "				<td class=\"wh_und\" align=\"left\" NOWRAP><font size=\"".$fsize."\">".substr($rowAo[0],0,13)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\">".$row[0]."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"center\"><font size=\"".$fsize."\">".$ctype."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"center\"><font size=\"".$fsize."\">".$cfyr."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq1)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq2)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq3)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq4)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq5)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq6)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq7)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq8)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq9)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq10)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq11)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq12)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq13)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq14)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq15)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq16)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq17)."</font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"".$fsize."\">".number_format($nq18)."</font></td>\n";
			echo "			</tr>\n";
				
			$tnq1	=$tnq1+$nq1;
			$tnq2	=$tnq2+$nq2;
			$tnq3	=$tnq3+$nq3;
			$tnq4	=$tnq4+$nq4;
			$tnq5	=$tnq5+$nq5;
			$tnq6	=$tnq6+$nq6;
			$tnq7	=$tnq7+$nq7;
			$tnq8	=$tnq8+$nq8;
			$tnq9	=$tnq9+$nq9;
			$tnq10	=$tnq10+$nq10;
			$tnq11	=$tnq11+$nq11;
			$tnq12	=$tnq12+$nq12;
			$tnq13	=$tnq13+$nq13;
			$tnq14	=$tnq14+$nq14;
			$tnq15	=$tnq15+$nq15;
			$tnq16	=$tnq16+$nq16;
			$tnq17	=$tnq17+$nq17;
			$tnq18	=$tnq18+$nq18;
			$tnql1	=$tnql1+$nq1;
			$tnql2	=$tnql2+$nq2;
			$tnql3	=$tnql3+$nq3;
			$tnql4	=$tnql4+$nq4;
			$tnql5	=$tnql5+$nq5;
			$tnql6	=$tnql6+$nq6;
			$tnql7	=$tnql7+$nq7;
			$tnql8	=$tnql8+$nq8;
			$tnql9	=$tnql9+$nq9;
			$tnql10	=$tnql10+$nq10;
			$tnql11	=$tnql11+$nq11;
			$tnql12	=$tnql12+$nq12;
			$tnql13	=$tnql13+$nq13;
			$tnql14	=$tnql14+$nq14;
			$tnql15	=$tnql15+$nq15;
			$tnql16	=$tnql16+$nq16;
			$tnql17	=$tnql17+$nq17;
			$tnql18	=$tnql18+$nq18;
			unset($nq16);
			unset($nq17);
		}
	
		$oldt		=$row[2];
	}
	
	if ($nrow == $ccnt)
	{
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"><b>Sub Total</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql1)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql2)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql3)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql4)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql5)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql6)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql7)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql8)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql9)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql10)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql11)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql12)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql13)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql14)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql15)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql16)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql17)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnql18)."</b></font></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" colspan=\"22\" align=\"left\">&nbsp</td>\n";
		echo "			</tr>\n";
		$tnql1	=0;
		$tnql2	=0;
		$tnql3	=0;
		$tnql4	=0;
		$tnql5	=0;
		$tnql6	=0;
		$tnql7	=0;
		$tnql8	=0;
		$tnql9	=0;
		$tnql10	=0;
		$tnql11	=0;
		$tnql12	=0;
		$tnql13	=0;
		$tnql14	=0;
		$tnql15	=0;
		$tnql16	=0;
		$tnql17	=0;
		$tnql18	=0;
	}
	
	echo "			<tr>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"><b>Grand Total</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"".$fsize."\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq1)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq2)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq3)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq4)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq5)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq6)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq7)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq8)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq9)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq10)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq11)."</b></font></td>\n";	
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq12)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq13)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq14)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq15)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq16)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq17)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\" width=\"".$ps."\"><font size=\"".$fsize."\"><b>".number_format($tnq18)."</b></font></td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
}

function netquick_adminold()
{
	//echo "TEST";
	error_reporting(E_ALL);
	
	if ($_SESSION['officeid']!=89)
	{
		echo "You do not have appropriate permission to view this resource";
		exit;
	}
	
	$mssql_ser	= "192.168.1.22";
	$mssql_db	= "MAS_SYSTEM";
	$mssql_user	= "MAS_REPORTS";
	$mssql_pass	= "reports";

	mssql_connect($mssql_ser,$mssql_user,$mssql_pass) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($mssql_db) or die("Table unavailable");
	
	$c_exar	=array(200,270,282,311,340,540,600,800,801,810,820,830,840,850,860,870,880,890,900,910,920,931,940,950,990,999);
	$d_exar	=array(001,002,003,004,600,888);

	$nq1		=0;
	$nq2		=0;
	$nq3		=0;
	$nq4		=0;
	$nq5		=0;
	$nq6		=0;
	$nq7		=0;
	$nq8		=0;
	$nq9		=0;
	$nq10		=0;
	$nq11		=0;
	$nq12		=0;
	$nq13		=0;
	$nq14		=0;
	$nq15		=0;
	$nq16		=0;
	$nq17		=0;
	$nq18		=0;
	$tnq1		=0;
	$tnq2		=0;
	$tnq3		=0;
	$tnq4		=0;
	$tnq5		=0;
	$tnq6		=0;
	$tnq7		=0;
	$tnq8		=0;
	$tnq9		=0;
	$tnq10	=0;
	$tnq11	=0;
	$tnq12	=0;
	$tnq13	=0;
	$tnq14	=0;
	$tnq15	=0;
	$tnq16	=0;
	$tnq17	=0;
	$tnq18	=0;
	$ctype	="";
	$oldt		=1;
	$ccnt		=0;
	
	$currdate	=date("m/d/Y h:i A",time());
	
	//$qry   = "SELECT company,division,type FROM ZE_Stats..divtocomp WHERE type <= 1 ORDER by type,company;";
	$qry   = "SELECT company,division,type FROM ZE_Stats..divtocomp WHERE type <= 3 AND substring(company,1,2)=division ORDER by type,company;";
	$res   = mssql_query($qry);
	$nrow	 = mssql_num_rows($res);

	echo "		<table class=\"outer\">\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"left\" colspan=\"5\"><font><b>Net Quick Rollup<b></font></td>\n";
	echo "				<td class=\"gray\" align=\"right\" colspan=\"16\"><font><b>".$currdate."<b></font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"und\" align=\"left\"><font size=\"1\"><b>Company<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\"><font size=\"1\"><b>Code<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\"><font size=\"1\"><b>Type<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ1<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ2<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ3<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ4<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ5<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ6<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ7<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ8<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ9<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ10<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ11<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ12<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ13<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ14<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ15<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ16<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ17<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>NQ18<b></font></td>\n";
	echo "			</tr>\n";

	while ($row=mssql_fetch_row($res))
	{
		$ccnt++;
		if ($row[2]==1)
		{
			$ctype="PA";
		}
		elseif ($row[2]==2)
		{
			$ctype="FIT";
		}
		elseif ($row[2]==3)
		{
			$ctype="FR";
		}

		if (!in_array($row[0],$c_exar))
		{
			$ng16=0;
			$ng17=0;
			$qryAo = "SELECT CompanyName,CompanyCode,GLFiscalYear FROM MAS_".$row[0]."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'$row[0]');";
			$resAo = mssql_query($qryAo);
			$rowAo = mssql_fetch_row($resAo);
				
			//echo $qryAo."<br>---<br>";
					
			$cfyr	=$rowAo[2];
				
			$qryAa = "SELECT FiscalYr, Period1EndingDate, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$cfyr."';";
			$resAa = mssql_query($qryAa);
			$rowAa = mssql_fetch_array($resAa);
				
			//echo $qryAa."<br>---<br>";
			
			$pfyr	=$rowAa['FiscalYr']-1;
			
			$qryAb = "SELECT FiscalYr, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$pfyr."';";
			$resAb = mssql_query($qryAb);
			$rowAb = mssql_fetch_array($resAb);
			$nrowAb= mssql_num_rows($resAb);
				
				//echo $qryAb."<br>---<br>";
				
			$p2fyr =$rowAb['FiscalYr']-1;
				
			$qryAc = "SELECT FiscalYr, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$p2fyr."';";
			$resAc = mssql_query($qryAc);
			$rowAc = mssql_fetch_array($resAc);
			$nrowAc= mssql_num_rows($resAc);
				
				//echo $qryAc."<br>---<br>";
			
			$endd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
			$pendd=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
		
			if ($nrowAb == 1)
			{
				$begd	=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
			}
			else
			{
					$begd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
			}
	
			if ($nrowAc == 1)
			{
				$pbegd	=date("m/d/Y",strtotime($rowAc['Period12EndingDate']))." 11:59:59";
			}
			else
			{
				$pbegd	=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
			}
				
			$qryB = "SELECT MAX(FiscalYr) AS fsyr FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile;";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
				
			$qryC = "SELECT loansfrom,pmntsto FROM prioryearloans WHERE company='".$rowpre['company']."' and division='".$rowpre['division']."' AND fiscalyear='".$pfyr."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);
			$nrowC = mssql_num_rows($resC);
				
				//echo "PriB: ".$pbegd."<br>";
				//echo "PriE: ".$pendd."<br>";
				//echo "qryC: ".$qryC."<br>";
				
				$gl101	=gl_pull_simple_cib($row[0],$row[1],1,101,$begd,$endd,$rowAa['FiscalYr']);
				$gl102	=gl_pull_simple_cib($row[0],$row[1],1,102,$begd,$endd,$rowAa['FiscalYr']);
				$gl103	=gl_pull_simple_cib($row[0],$row[1],1,103,$begd,$endd,$rowAa['FiscalYr']);
				$gl104	=gl_pull_simple_cib($row[0],$row[1],1,104,$begd,$endd,$rowAa['FiscalYr']);
				$gl107	=gl_pull_simple_cib($row[0],$row[1],1,107,$begd,$endd,$rowAa['FiscalYr']);
				$gl110	=gl_pull_simple_cib($row[0],$row[1],1,110,$begd,$endd,$rowAa['FiscalYr']);
				$gl190	=gl_pull_simple_cib($row[0],$row[1],1,190,$begd,$endd,$rowAa['FiscalYr']);
				$gl412c	=gl_pull_simple_cib($row[0],$row[1],1,412,$begd,$endd,$rowAa['FiscalYr']);
				$gl729c	=gl_pull_simple_cib($row[0],$row[1],1,729,$begd,$endd,$rowAa['FiscalYr']);
				$arjns	=nq_arjns($row[0],$row['division']);
				$jpsum	=nq_jpsum($row[0],$row['division'],0);
	
				$nq1		=$gl101 + $gl102 + $gl103 + $gl104;
				$nq2		=$gl110;
				$nq3		=$arjns[0] - $arjns[2];
				$nq4		=$gl110 - $nq3;
				$nq5		=$jpsum;
				$nq6		=$arjns[1];
				$nq7		=$nq5 - $nq6;
				$nq8		=$gl190;
				$nq9		=$nq7 - $gl190;
				$nq10		=$nq4 - $nq9;
				$nq11		=$gl107;
				$nq12		=$arjns[2];
				$nq13		=($nq1 + $nq10 + $gl107) - $nq12;
				$nq14		=$gl412c;
				$nq15		=$gl729c;
					
				if ($nrowC > 0)
				{
						if (!empty($rowC['loansfrom']))
						{
							$nq16=$rowC['loansfrom'];
						}
						
						if (!empty($rowC['pmntsto']))
						{
							$nq17=$rowC['pmntsto'];
						}
				}
					
				$nq18		=$nq13 - $nq14 + $nq15 - $nq16 + $nq17;
				$tnq1		=$tnq1+$nq1;

				echo "			<tr>\n";
				echo "				<td class=\"wh_und\" align=\"left\" NOWRAP><font size=\"1\">".$rowAo[0]."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$row[0]."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"center\"><font size=\"1\">".$ctype."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq1)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq2)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq3)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq4)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq5)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq6)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq7)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq8)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq9)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq10)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq11)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq12)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq13)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq14)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq15)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq16)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq17)."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq18)."</font></td>\n";
				echo "			</tr>\n";
				$tnq1		=$tnq1+$nq1;
				$tnq2		=$tnq2+$nq2;
				$tnq3		=$tnq3+$nq3;
				$tnq4		=$tnq4+$nq4;
				$tnq5		=$tnq5+$nq5;
				$tnq6		=$tnq6+$nq6;
				$tnq7		=$tnq7+$nq7;
				$tnq8		=$tnq8+$nq8;
				$tnq9		=$tnq9+$nq9;
				$tnq10	=$tnq10+$nq10;
				$tnq11	=$tnq11+$nq11;
				$tnq12	=$tnq12+$nq12;
				$tnq13	=$tnq13+$nq13;
				$tnq14	=$tnq14+$nq14;
				$tnq15	=$tnq15+$nq15;
				$tnq16	=$tnq16+$nq16;
				$tnq17	=$tnq17+$nq17;
				$tnq18	=$tnq18+$nq18;
				
		}
	
		//$oldt		=$row[1];
	}
	
	if ($nrow == $ccnt)
	{
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"><b>Sub Total</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq1)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq2)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq3)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq4)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq5)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq6)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq7)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq8)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq9)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq10)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq11)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq12)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq13)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq14)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq15)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq16)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq17)."</font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tnq18)."</font></td>\n";
		echo "			</tr>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" colspan=\"21\" align=\"left\">&nbsp</td>\n";
		echo "			</tr>\n";
		$tsgl101		=0;
		$tsgl102		=0;
		$tsgl103		=0;
		$tsgl104		=0;
		$tsgllntot	=0;
	}
	/*
	echo "			<tr>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"><b>Grand Total</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl101)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl102)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl103)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl104)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgllntot)."</b></font></td>\n";
	echo "			</tr>\n";
	*/
	echo "		</table>\n";
}

function netquickold()
{
	error_reporting(E_ALL);
	$ctime	=date("m/d/Y",time());
	$c_exar	=array(270,282,311,340,540,800,801,810,830,840,850,860,870,880,890,900,910,920,930,931,940,950,990,999);
	$d_exar	=array(001,002,003,004,600);

	$nq1		=0;
	$nq2		=0;
	$nq3		=0;
	$nq5		=0;
	$nq6		=0;
	$nq7		=0;
	$nq8		=0;
	$nq9		=0;
	$nq10	=0;
	$nq11	=0;
	$nq12	=0;
	$nq13	=0;
	$nq14	=0;
	$nq15	=0;
	$nq16	=0;
	$nq17	=0;
	$nq18	=0;

	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	//$qry   = "SELECT name FROM master..sysdatabases WHERE name LIKE '%MAS%' AND name!='master' AND name!='MAS_SYSTEM' ORDER BY name;";
	/*
	if ($_REQUEST['subq']==1)
	{
		$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE mdiv = ".$_REQUEST['division'].";";
	}
	else
	{
		$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE type <= 3 ORDER by type,company;";
	}
	*/
	$qry   = "SELECT company,division FROM ZE_Stats..divtocomp WHERE division = ".$_REQUEST['division'].";";
	$res   = mssql_query($qry);

	//echo $qry."<br>";
	//print_r($_POST);

	echo "		<table class=\"outer\">\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"center\" colspan=\"20\"><font><b>Blue Haven Pools<b></font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"center\" colspan=\"20\"><font><b>Net Quick Position Calculation<b></font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"center\" colspan=\"20\"><font><b>".$ctime."<b></font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"left\"></td>\n";
	echo "				<td class=\"gray\" align=\"left\"></td>\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">1</font></td><!-- 1 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">2</font></td><!-- 2 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">3</font></td><!-- 3 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">4</font></td><!-- 4 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">5</font></td><!-- 5 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">6</font></td><!-- 6 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">7</font></td><!-- 7 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">8</font></td><!-- 8 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">9</font></td><!-- 9 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">10</font></td><!-- 10 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">11</font></td><!-- 11 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">12</font></td><!-- 12 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">13</font></td><!-- 13 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">14</font></td><!-- 14 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">15</font></td><!-- 15 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">16</font></td><!-- 16 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">17</font></td><!-- 17 -->\n";
	echo "				<td class=\"gray\" align=\"center\" width=\"50\"><font size=\"1\">18</font></td><!-- 18 -->\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"und\" align=\"left\" valign=\"bottom\"><font size=\"1\"><b>Company<b></font></td>\n";
	echo "				<td class=\"und\" align=\"left\" valign=\"bottom\"><font size=\"1\"><b>Code<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">G/L<br>General<br>Cash</font></td><!-- 1 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Gross<br>A/R in<br>Job Prof</font></td><!-- 2 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">A/R on<br>Jobs not<br>Started</font></td><!-- 3 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Net<br>A/R in<br>Job Prof</font></td><!-- 4 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Gross Est<br>Costs per<br>Job Prof</font></td><!-- 5 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Est Cost<br>Jobs not<br>Started</font></td><!-- 6 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Net Est<br>Costs per<br>Job Prof</font></td><!-- 7 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Actual<br>Costs per<br>Job Prof</font></td><!-- 8 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Est<br>Cost to<br>Complete</font></td><!-- 9 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Position<br>on<br>Jobs</font></td><!-- 10 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Physical<br>Inventory</font></td><!-- 11 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Cust Pmts<br>Jobs not<br>Started</font></td><!-- 12 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Net Quick<br>Position<br>(No Z&E)</font></td><!-- 13 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Loans<br>from Z&E<br>YTD</font></td><!-- 14 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Pmts<br>to Z&E<br>YTD</font></td><!-- 15 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Loans<br>from Z&E<br>Prior Yr</font></td><!-- 16 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Pmts<br>to Z&E<br>Prior Yr</font></td><!-- 17 -->\n";
	echo "				<td class=\"und\" align=\"center\" valign=\"bottom\" width=\"50\"><font size=\"1\">Net Quick<br>Position<br>(with Z&E)</font></td><!-- 18 -->\n";
	echo "			</tr>\n";

	$tnq1=0;
	while ($row=mssql_fetch_row($res))
	{
		//$retext=substr($row[0],4);
		$retext=$row[0];
		if (!in_array($retext,$c_exar))
		{
			//$qryA = "SELECT CompanyName,CompanyCode FROM ".$row[0]."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'$retext');";
			$qryA = "SELECT CompanyName,CompanyCode FROM MAS_".$row[0]."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$retext."');";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			//echo $qryA;

			$qryB = "SELECT MAX(FiscalYr) AS fsyr FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile;";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			if (!ereg("[A-Z]",$retext))
			{
				$gl101	=gl_pull_simple($retext,0,0,101);
				$gl102	=gl_pull_simple($retext,0,0,102);
				$gl103	=gl_pull_simple($retext,0,0,103);
				$gl107	=gl_pull_simple($retext,0,0,107);
				$gl110	=gl_pull_simple($retext,0,0,110);
				$gl190	=gl_pull_simple($retext,0,0,190);
				$gl412c	=gl_pull_fyr($retext,0,0,412,$rowB['fsyr']);
				$gl412p	=gl_pull_priorfyr($retext,0,0,412,$rowB['fsyr']-1);
				$gl729c	=gl_pull_fyr($retext,0,0,729,$rowB['fsyr']);
				$gl729p	=gl_pull_priorfyr($retext,0,0,729,$rowB['fsyr']-1);
				$arjns	=nq_arjns($retext,0);
				$jpsum	=nq_jpsum($retext,0,0);

				$nq1		=$gl101 + $gl102 + $gl103;
				$nq4		=$gl110 - $arjns[0];
				$nq7		=$jpsum - $arjns[1];
				$nq9		=$nq7 - $gl190;
				$nq10	=$nq4 - $nq9;
				$nq13	=($nq1 + $nq10 + $gl107) - $arjns[2];
				$nq18	=(($nq1+$nq10+$gl107)-$arjns[2])-(($gl412c+$gl729c)-($gl412p+$gl729p));

				$tnq1	=$tnq1+$nq1;

				echo "			<tr>\n";
				echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$rowA[0]."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$retext."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq1)."</font></td><!-- 1 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl110)."</font></td><!-- 2 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($arjns[0])."</font></td><!-- 3 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq4)."</font></td><!-- 4 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($jpsum)."</font></td><!-- 5 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($arjns[1])."</font></td><!-- 6 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq7)."</font></td><!-- 7 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl190)."</font></td><!-- 8 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq9)."</font></td><!-- 9 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq10)."</font></td><!-- 10 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl107)."</font></td><!-- 11 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($arjns[2])."</font></td><!-- 12 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq13)."</font></td><!-- 13 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl412c)."</font></td><!-- 14 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl729c)."</font></td><!-- 15 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl412p)."</font></td><!-- 16 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl729p)."</font></td><!-- 17 -->\n";
				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($nq18)."</font></td><!-- 18 -->\n";
				echo "			</tr>\n";
				//}
			}
		}
	}
	/*
	echo "			<tr>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">Totals</font></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tgl101)."</font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tgl102)."</font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tgl103)."</font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($tgllntot)."</font></td>\n";
	echo "			</tr>\n";
	*/
	echo "		</table>\n";
}

function manreport()
{
	global $dtarray;

	$cpny	=$_REQUEST['cpny'];
	$mdiv	=$_REQUEST['mdiv'];
	$div		=$_REQUEST['division'];
	$scpny	= preg_replace('/^MAS_/', '', $cpny);
	$ccnt	=0;

	if (isset($_REQUEST['prdin']))
	{
		$prdin=$_REQUEST['prdin'];
	}
	else
	{
		$prdin=date("m");
	}

	$dtarray=setdatearray();

	$qry   = "SELECT name FROM master..sysdatabases WHERE name LIKE '%MAS_%' AND name!='master' AND name!='MAS_SYSTEM' ORDER BY name;";
	$res   = mssql_query($qry);

	echo "                  <table width=\"100%\">\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"left\"></td>\n";
	echo "                        <td align=\"left\">Company Name</td>\n";
	echo "                        <td align=\"center\" title=\"Company Code and Division\">Code</td>\n";
	echo "                        <td align=\"right\">1</td>\n";
	echo "                        <td align=\"right\">2</td>\n";
	echo "                        <td align=\"right\">3</td>\n";
	echo "                        <td align=\"right\">4</td>\n";
	echo "                        <td align=\"right\">5</td>\n";
	echo "                        <td align=\"right\">6</td>\n";
	echo "                        <td align=\"right\">7</td>\n";
	echo "                        <td align=\"right\">8</td>\n";
	echo "                        <td align=\"right\">9</td>\n";
	echo "                        <td align=\"right\">10</td>\n";
	echo "                        <td align=\"right\">11</td>\n";
	echo "                        <td align=\"right\">12</td>\n";
	echo "                        <td align=\"right\">13</td>\n";
	echo "                        <td align=\"right\">14</td>\n";
	echo "                        <td align=\"right\">15</td>\n";
	echo "                     </tr>\n";

	while ($row=mssql_fetch_row($res))
	{
		$retext=substr($row[0],4);

		$qryA = "SELECT CompanyName,CompanyCode FROM ".$row[0]."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$retext."');";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_row($resA);

		if (!ereg("[A-Z]",$retext))
		{
			$qryB = "SELECT DeptNumber FROM ".$row[0]."..GL7_Department WHERE DeptNumber!=000000000;";
			$resB = mssql_query($qryB);
			$nrowsB = mssql_num_rows($resB);

			//if ($nrowsB > 1)
			//{
			// Multidivision Companies
			$qryC = "SELECT DeptNumber,DeptName FROM ".$row[0]."..GL7_Department WHERE DeptNumber!=000000000 ORDER BY DeptNumber;";
			$resC = mssql_query($qryC);

			while ($rowC=mssql_fetch_row($resC))
			{
				$ccnt++;
				$retextdiv =substr($rowC[0],0,3);
				echo "                     <tr>\n";
				echo "                        <td align=\"right\">".$ccnt.".</td>\n";
				echo "                        <td class=\"und\" align=\"left\">".$rowA[0]." (".$rowC[1].")</td>\n";
				echo "                        <td class=\"und\" align=\"left\">".$retext." (".$retextdiv.")</td>\n";
				echo "                        <td></td>\n";
				echo "                        <td></td>\n";
				echo "                        <td></td>\n";
				echo "                        <td></td>\n";
				echo "                        <td></td>\n";
				echo "                        <td></td>\n";
				echo "                     </tr>\n";
			}
			//}
		}
	}
}

function gl_pull_simple_cib($ccode,$cdiv,$mdiv,$glacc,$beg,$end,$fsyr)
{
	$out	=0;
	$bb	=0;

	if ($mdiv==1)
	{
		//$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc.$cdiv."%';";
		$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc.$cdiv."%' AND FiscalYr = '".$fsyr."';";
	}
	else
	{
		//$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc."%';";
		$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc."%' AND FiscalYr  = '".$fsyr."';";
	}

	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow = mssql_num_rows($res);

	if ($nrow > 0)
	{
		$bb=$row['bbamt'];
	}

	if ($mdiv==1)
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$cdiv."%' AND TransactionDate BETWEEN '".$beg."' AND '".$end."';";
	}
	else
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc."%' AND TransactionDate BETWEEN '".$beg."' AND '".$end."';";
	}

	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	$out	=$rowA['pstamt']+$bb;
	
	if ($glacc==999 || $glacc==729)
	{
		//echo $out."<br>";
		//echo $qry."<br>";
		//echo $qryA."<br>---<br>";
	}
	
	return $out;
}

function gl_pull_simple($ccode,$cdiv,$mdiv,$glacc)
{
	$out	=0;
	$bb	=0;

	if ($mdiv==1)
	{
		//$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc.$cdiv."%';";
		$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc.$cdiv."%' AND FiscalYr < 2003";
	}
	else
	{
		//$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc."%';";
		$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc."%' AND FiscalYr < 2003";
	}

	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow = mssql_num_rows($res);

	if ($nrow > 0)
	{
		$bb=$row['bbamt'];
	}

	if ($mdiv==1)
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$cdiv."%';";
	}
	else
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc."%';";
	}

	/*
	if ($glacc==109)
	{
	echo $qryA."<br>";
	}
	*/
	//echo $qry."<br>";
	//echo $qryA."<br>";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	$out	=$rowA['pstamt']+$bb;
	return $out;
}


function gl_pull_fyr($ccode,$cdiv,$mdiv,$glacc,$fyr)
{
	$out	=0;

	$qry0 = "SELECT FiscalYr,NumberOfPeriods,Period1EndingDate AS p1,Period12EndingDate AS p12 FROM MAS_".trim($ccode)."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$fyr."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	//print_r($row0);

	if ($mdiv==1)
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$cdiv."%'  AND TransactionDate BETWEEN '".$row0['p1']."' AND '".$row0['p12']."';";
	}
	else
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc."%' AND TransactionDate BETWEEN '".$row0['p1']."' AND '".$row0['p12']."';";
	}

	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	//echo $qryA."<br>";

	$out	=$rowA['pstamt'];
	return $out;
}

function gl_pull_priorfyr($ccode,$cdiv,$mdiv,$glacc,$fyr)
{
	$out	=0;

	$qry0 = "SELECT FiscalYr,NumberOfPeriods,Period1EndingDate AS p1,Period12EndingDate AS p12 FROM MAS_".trim($ccode)."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$fyr."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$qry1 = "SELECT FiscalYr,NumberOfPeriods,Period1EndingDate AS p1,Period12EndingDate AS p12 FROM MAS_".trim($ccode)."..GLC_FiscalYrMasterfile WHERE FiscalYr='2002';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	//print_r($row0);

	if ($mdiv==1)
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$cdiv."%'  AND TransactionDate BETWEEN '".$row1['p1']."' AND '".$row0['p12']."';";
	}
	else
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc."%' AND TransactionDate BETWEEN '".$row1['p1']."' AND '".$row0['p12']."';";
	}

	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	//echo $qryA."<br>";

	$out	=$rowA['pstamt'];
	return $out;
}

function gl_pull_drange($ccode,$cdiv,$mdiv,$glacc,$d1,$d2)
{
	$out	=0;

	if ($mdiv==1)
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$cdiv."%' AND TransactionDate BETWEEN '".$d1."' AND '".$d2."';";
	}
	else
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc."%' AND TransactionDate BETWEEN '".$d1."' AND '".$d2."';";
	}

	/*
	if ($glacc==414)
	{
		echo $qryA."<br>";
	}
	*/

	//echo $qryA."<br>";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	$out	=$rowA['pstamt'];
	return $out;
}

function cashinbank_admin()
{
	//echo "TEST";
	error_reporting(0);
	
	if ($_SESSION['officeid']!=89)
	{
		echo "You do not have appropriate permission to view this resource";
		exit;
	}
	
	$mssql_ser	= "192.168.1.22";
	$mssql_db	= "MAS_SYSTEM";
	$mssql_user	= "MAS_REPORTS";
	$mssql_pass	= "reports";

	mssql_connect($mssql_ser,$mssql_user,$mssql_pass) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($mssql_db) or die("Table unavailable");
	
	$c_exar	=array(270,282,311,340,399,540,801,810,820,830,840,850,860,870,880,890,900,910,920,931,940,950,990,999);
	$d_exar	=array(00,01,02,03,60,88,97,98,99);

	$gl101	=0;
	$gl102	=0;
	$gl103	=0;
	$gl104	=0;
	$gllntot=0;
	$tgl101	=0;
	$tgl102	=0;
	$tgl103	=0;
	$tgl104	=0;
	$tgllntot=0;
	$tsgl101=0;
	$tsgl102=0;
	$tsgl103=0;
	$tsgl104=0;
	$tsgllntot=0;
	$ctype	="";
	$oldt	=1;
	$ccnt	=0;
	
	$currdate	=date("m/d/Y h:i A",time());
	
	$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE type <= 3 ORDER by type,company;";
	$res   = mssql_query($qry);
	$nrow	 = mssql_num_rows($res);

	echo "		<table class=\"outer\">\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"left\" colspan=\"3\"><font><b>Cash in Bank<b></font></td>\n";
	echo "				<td class=\"gray\" align=\"center\" colspan=\"3\">\n";
	
	echo "		<form name=\"cib\" target=\"_top\" method=\"post\">\n";
	echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "		<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
	echo "		<input type=\"hidden\" name=\"subq\" value=\"cib_admin\">\n";
	
	if (isset($_REQUEST['beyondyearend']) && $_REQUEST['beyondyearend']==1)
	{
		echo "		Include Transactions beyond Current FY <input class=\"checkboxgry\" type=\"checkbox\" name=\"beyondyearend\" value=\"1\" CHECKED onClick=\"this.form.submit();\" alt=\"Includes Transactions beyond the Current Fiscal Year\">\n";
	}
	else
	{
		echo "		Include Transactions beyond Current FY <input class=\"checkboxgry\" type=\"checkbox\" name=\"beyondyearend\" value=\"1\" onClick=\"this.form.submit();\" alt=\"Includes Transactions beyond the Current Fiscal Year\">\n";
	}
	
	echo "		</form>\n";
	echo "				</td>\n";
	echo "				<td class=\"gray\" align=\"right\" colspan=\"3\"><font><b>".$currdate."<b></font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"und\" align=\"left\"><font size=\"1\"><b>Company<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\"><font size=\"1\"><b>Code<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\"><font size=\"1\"><b>Type<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\"><font size=\"1\"><b>FY<b></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL101<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL102<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL103<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL104<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL Total<b></font></td>\n";
	echo "			</tr>\n";

	while ($row=mssql_fetch_row($res))
	{
		$ccnt++;
		if ($row[1]==1)
		{
			$ctype="PA";
		}
		elseif ($row[1]==2)
		{
			$ctype="FIT";
		}
		elseif ($row[1]==3)
		{
			$ctype="FR";
		}
		
		if ($oldt!=$row[1])
		{
			echo "			<tr>\n";
			echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"><b>Sub Total</b></font></td>\n";
			echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
			echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
			echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl101)."</b></font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl102)."</b></font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl103)."</b></font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl104)."</b></font></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgllntot)."</b></font></td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td class=\"wh_und\" colspan=\"9\" align=\"left\">&nbsp</td>\n";
			echo "			</tr>\n";
			$tsgl101		=0;
			$tsgl102		=0;
			$tsgl103		=0;
			$tsgl104		=0;
			$tsgllntot	=0;
		}

		if (!in_array($row[0],$c_exar))
		{
			$qryA = "SELECT CompanyName,CompanyCode,GLFiscalYear FROM MAS_".$row[0]."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$row[0]."');";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			$cfyr	=$rowA[2];
			
			$qryAa = "SELECT FiscalYr, Period1EndingDate, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$cfyr."';";
			$resAa = mssql_query($qryAa);
			$rowAa = mssql_fetch_array($resAa);
			
			$pfyr	=$rowAa['FiscalYr']-1;
			
			$qryAb = "SELECT FiscalYr, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$pfyr."';";
			$resAb = mssql_query($qryAb);
			$rowAb = mssql_fetch_array($resAb);
			$nrowAb= mssql_num_rows($resAb);

			if (!ereg("[A-Z]",$row[0]))
			{
				// Multidivision Companies
				$qryC = "SELECT DeptNumber,DeptName FROM MAS_".$row[0]."..GL7_Department WHERE DeptNumber!=000000000 ORDER BY DeptNumber;";
				$resC = mssql_query($qryC);
				$nrowsC = mssql_num_rows($resC);

				if ($nrowsC > 0)
				{
					while ($rowC=mssql_fetch_row($resC))
					{
						if (!in_array(substr($rowC[0],0,2),$d_exar))
						{							
							$gl101	=cib_office_no_disp($row[0],substr($rowC[0],0,2),1,101,$rowAa['FiscalYr']);
							$gl102	=cib_office_no_disp($row[0],substr($rowC[0],0,2),1,102,$rowAa['FiscalYr']);
							$gl103	=cib_office_no_disp($row[0],substr($rowC[0],0,2),1,103,$rowAa['FiscalYr']);
							$gl104	=cib_office_no_disp($row[0],substr($rowC[0],0,2),1,104,$rowAa['FiscalYr']);
							
							$gllntot	=$gl101 + $gl102 + $gl103 + $gl104;

							$tgl101		=$tgl101+$gl101;
							$tgl102		=$tgl102+$gl102;
							$tgl103		=$tgl103+$gl103;
							$tgl104		=$tgl104+$gl104;
							$tgllntot	=$tgllntot+$gllntot;
							$tsgl101	=$tsgl101+$gl101;
							$tsgl102	=$tsgl102+$gl102;
							$tsgl103	=$tsgl103+$gl103;
							$tsgl104	=$tsgl104+$gl104;
							$tsgllntot	=$tsgllntot+$gllntot;
							echo "			<tr>\n";
							echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$rowA[0]." ".($rowC[1])."</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$row[0]." (".substr($rowC[0],0,2).")</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"center\"><font size=\"1\">".$ctype."</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"center\"><font size=\"1\">".$cfyr."</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl101)."</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl102)."</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl103)."</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl104)."</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gllntot)."</font></td>\n";
							echo "			</tr>\n";
						}
					}
				}
				/*else
				{
					// Single Div Companies
					$gl101	=cib_office_no_disp($row[0],substr($rowC[0],0,2),1,101,$rowAa['FiscalYr']);
					$gl102	=cib_office_no_disp($row[0],substr($rowC[0],0,2),1,102,$rowAa['FiscalYr']);
					$gl103	=cib_office_no_disp($row[0],substr($rowC[0],0,2),1,103,$rowAa['FiscalYr']);
					$gl104	=cib_office_no_disp($row[0],substr($rowC[0],0,2),1,104,$rowAa['FiscalYr']);

					$gllntot	=$gl101 + $gl102 + $gl103 + $gl104;

					$tgl101	=$tgl101+$gl101;
					$tgl102	=$tgl102+$gl102;
					$tgl103	=$tgl103+$gl103;
					$tgl104	=$tgl104+$gl104;
					$tgllntot=$tgllntot+$gllntot;
					$tsgl101=$tsgl101+$gl101;
					$tsgl102=$tsgl102+$gl102;
					$tsgl103=$tsgl103+$gl103;
					$tsgl104=$tsgl103+$gl104;
					$tsgllntot=$tsgllntot+$gllntot;
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$rowA[0]."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$rowA[0]."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"center\"><font size=\"1\">".$ctype."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"center\"><font size=\"1\">".$cfyr."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl101)."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl102)."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl103)."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl104)."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gllntot)."</font></td>\n";
					echo "			</tr>\n";
				}*/
			}
		}
	
		$oldt		=$row[1];
	}
	
	if ($nrow == $ccnt)
	{
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"><b>Sub Total</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl101)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl102)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl103)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl104)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgllntot)."</b></font></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" colspan=\"9\" align=\"left\">&nbsp</td>\n";
		echo "			</tr>\n";
		$tsgl101		=0;
		$tsgl102		=0;
		$tsgl103		=0;
		$tsgl104		=0;
		$tsgllntot	=0;
	}
	
	echo "			<tr>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"><b>Grand Total</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl101)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl102)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl103)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl104)."</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgllntot)."</b></font></td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
}

function cib_sum_yr($cpy,$fyr,$gl,$div,$nop)
{
	$fout=0;
	$rs=0;
	
	$qryB = "SELECT * FROM MAS_".$cpy."..GL8_BudgetAndHistory WHERE FiscalYr='".$fyr."' and AccountNumber='".$gl.$div."0000';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
	$nrowB= mssql_num_rows($resB);
	
	/*if ($_SESSION['securityid']==26)
	{
		echo $qryB."<br>";
	}*/

	for ($x=1;$x<=$nop;$x++)
	{
		if ($x==1)
		{
			$bb	=$rowB['BegBalTypeActualOnly'];
		}
		else
		{
			$bb	=$rs;
		}
		
		$p1	=$rowB['ActualPeriod'.$x];	
		$rs	=$bb + $p1;
	}
		
	$fout=$rs;
	
	/*if ($_SESSION['securityid']==26)
	{
		//echo $cpy.':'.$fout."<br>";
	}*/
	
	return $fout;
}

function cashinbank_office()
{
	//echo "TEST";
	error_reporting(E_ALL);
	
	$qrypre1 = "SELECT mas_office,mas_div,gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$respre1 = mssql_query($qrypre1);
	$rowpre1 = mssql_fetch_array($respre1);
	
	//echo $qrypre1."<br>";
	
	if ($rowpre1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to this Area');
	}
	
	if (empty($_REQUEST['glacc']))
	{
		$glacc=101;
	}
	else
	{
		$glacc=$_REQUEST['glacc'];
	}
	
	if (isset($_REQUEST['division']) || $_REQUEST['division']!=0)
	{
		//$div=substr($_REQUEST['division'],0,2);
		$div=$_REQUEST['division'];
	}
	else
	{
		die('No Division');
	}
	
	if (isset($_REQUEST['cpny']) || $_REQUEST['cpny']!=0)
	{
		//$cpny=substr($_REQUEST['cpny'],4,7);
		$cpny=$_REQUEST['cpny'];
	}
	else
	{
		die('No Division');
	}
	
	//Redirect: Div 62 -> 63 
	if ($cpny==600 && $div == 62)
	{
		$cpny	=600;
		$div	=63;
	}
	
	$mssql_ser	= "192.168.1.22";
	$mssql_db	= "MAS_SYSTEM";
	$mssql_user	= "MAS_REPORTS";
	$mssql_pass	= "reports";

	mssql_connect($mssql_ser,$mssql_user,$mssql_pass) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($mssql_db) or die("Table unavailable");

	$gl_ar=array(101,102,103,104,105);

	$currdate=date("m/d/Y h:i A",time());
	
	$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE division='".$div."' AND company='".$cpny."';";
	$res   = mssql_query($qry);
	$row   = mssql_fetch_array($res);
	$nrow  = mssql_num_rows($res);
	
	$qryA = "SELECT CompanyName,CompanyCode,GLFiscalYear FROM MAS_".$row['company']."..SY0_CompanyParameters WHERE CompanyCode='".$row['company']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	$qryAa = "SELECT FiscalYr FROM MAS_".$row['company']."..GLC_FiscalYrMasterfile ORDER BY FiscalYr ASC;";
	$resAa = mssql_query($qryAa);
	$nrowAa= mssql_num_rows($resAa);
	
	$scdiv=md5($div);
	
	if (empty($_REQUEST['fiscalyr']))
	{
		$fyr=$rowA['GLFiscalYear'];
	}
	else
	{
		$fyr=$_REQUEST['fiscalyr'];
	}
	
	$qryAc  = "SELECT ";
	$qryAc .= "Period1EndingDate,";
	$qryAc .= "Period2EndingDate,";
	$qryAc .= "Period3EndingDate,";
	$qryAc .= "Period4EndingDate,";
	$qryAc .= "Period5EndingDate,";
	$qryAc .= "Period6EndingDate,";
	$qryAc .= "Period7EndingDate,";
	$qryAc .= "Period8EndingDate,";
	$qryAc .= "Period9EndingDate,";
	$qryAc .= "Period10EndingDate,";
	$qryAc .= "Period11EndingDate,";
	$qryAc .= "Period12EndingDate,";
	$qryAc .= "NumberOfPeriods,";
	$qryAc .= "FiscalYr ";
	$qryAc .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$fyr."';";
	$resAc  = mssql_query($qryAc);
	$rowAc  = mssql_fetch_array($resAc);
	
	$precurrfisyr=$rowAc['FiscalYr'] - 1;
	
	$qryAd  = "SELECT ";
	$qryAd .= "Period1EndingDate,";
	$qryAd .= "Period2EndingDate,";
	$qryAd .= "Period3EndingDate,";
	$qryAd .= "Period4EndingDate,";
	$qryAd .= "Period5EndingDate,";
	$qryAd .= "Period6EndingDate,";
	$qryAd .= "Period7EndingDate,";
	$qryAd .= "Period8EndingDate,";
	$qryAd .= "Period9EndingDate,";
	$qryAd .= "Period10EndingDate,";
	$qryAd .= "Period11EndingDate,";
	$qryAd .= "Period12EndingDate,";
	$qryAd .= "NumberOfPeriods,";
	$qryAd .= "FiscalYr ";
	$qryAd .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$precurrfisyr."';";
	$resAd  = mssql_query($qryAd);
	$rowAd  = mssql_fetch_array($resAd);
	
	echo "		<table width=\"600px\">\n";
	echo "			<tr>\n";
	echo "				<td align=\"center\">\n";
	echo "					<table class=\"outer\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"left\"><font><b>Cash in Bank:<b></font></td>\n";
	//echo "							<td class=\"gray\" align=\"left\"><font>XXXXXXXXXXXXXXXX XXX</font></td>\n";
	echo "							<td class=\"gray\" align=\"left\"><font>".$rowA['CompanyName']." ".$rowA['CompanyCode']."</font></td>\n";
	echo "							<td class=\"gray\" align=\"right\" colspan=\"3\"><font><b>".$currdate." PST<b></font></td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		<form action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
	echo "		<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "		<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
	echo "		<input type=\"hidden\" name=\"subq\" value=\"cib_office\">\n";
	echo "		<input type=\"hidden\" name=\"cpny\" value=\"".$cpny."\">\n";
	echo "		<input type=\"hidden\" name=\"division\" value=\"".$div."\">\n";
	echo "		<input type=\"hidden\" name=\"mdiv\" value=\"1\">\n";
	echo "			<tr>\n";
	echo "				<td align=\"center\">\n";
	echo "					<table class=\"outer\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\"><font size=\"1\"><b>Year:<b>\n";
	
	if ($nrowAa > 0)
	{
		echo "							<select name=\"fiscalyr\" OnChange=\"this.form.submit();\">\n";
		
		while ($rowAa = mssql_fetch_array($resAa))
		{
			if ($fyr==$rowAa['FiscalYr'])
			{
				echo "<option value=\"".$rowAa['FiscalYr']."\" SELECTED>".$rowAa['FiscalYr']."</option>\n";
			}
			else
			{
				echo "<option value=\"".$rowAa['FiscalYr']."\">".$rowAa['FiscalYr']."</option>\n";
			}
		}
		
		echo "							</select>\n";
	}
	
	echo "							</td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\"><font size=\"1\"><b>GL:<b>\n";
	echo "								<select name=\"glacc\" OnChange=\"this.form.submit();\">\n";
	
	foreach ($gl_ar as $gln => $glv)
	{
		if ($glacc==$glv)
		{
			echo "<option value=\"".$glv."\" SELECTED>".$glv."</option>\n";
		}
		else
		{
			echo "<option value=\"".$glv."\">".$glv."</option>\n";
		}
	}
	
	echo "								</select>\n";
	//echo "								<input class=\"buttondkgry\" type=\"submit\" value=\"Change\">\n";	
	echo "							</td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" width=\"80px\"><font size=\"1\"><b>Beg Balance<b></font></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" width=\"80px\"><font size=\"1\"><b>Debit<b></font></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" width=\"80px\"><font size=\"1\"><b>Credit<b></font></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" width=\"80px\"><font size=\"1\"><b>End Balance<b></font></td>\n";
	echo "						</tr>\n";
	echo "					</form >\n";

	$dayc=86400;

	if ($nrow > 0)
	{
		$num_ar=array(10,11,12);
		if (in_array($rowAd['NumberOfPeriods'],$num_ar))
		{
			$noppyr=$rowAd['NumberOfPeriods'];
		}
		else
		{
			$noppyr=$rowAd['NumberOfPeriods'][1];
		}
		
		if (in_array($rowAc['NumberOfPeriods'],$num_ar))
		//if ($rowAc['NumberofPeriods'] == 12)
		{
			$nopcyr=$rowAc['NumberOfPeriods'];
		}
		else
		{
			$nopcyr=$rowAc['NumberOfPeriods'][1];
		}
		
		$qryB = "SELECT * FROM MAS_".$row['company']."..GL8_BudgetAndHistory WHERE FiscalYr='".$fyr."' and AccountNumber='".$glacc.$div."0000';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);
		$nrowB= mssql_num_rows($resB);

		for ($x=1;$x<=$nopcyr;$x++)
		{
			if ($x%2)
			{
				$tbg = "wh_und";
			}
			else
			{
				$tbg = "gray_und";
			}
			
			if ($x==1)
			{
				$bb	=$rowB['BegBalTypeActualOnly'];
				
				if ($bb==0)
				{
					//$bb =cib_sum_yr($row['company'],($fyr - 1),$glacc,$div,$nopcyr);
					$bb =cib_sum_yr($row['company'],$rowAd['FiscalYr'],$glacc,$div,$noppyr);
				}
				
				$d1	=strtotime($rowAd['Period'.$noppyr.'EndingDate']) + $dayc;
				//$d2	=strtotime($rowAc['Period'.$nopcyr.'EndingDate']);
				//$d1	=strtotime($rowAd['Period12EndingDate']);
				$d2	=strtotime($rowAc['Period'.$x.'EndingDate']);
			}
			else
			{
				$bb	=$rs;
				$d1	=strtotime($rowAc['Period'.($x - 1).'EndingDate']) + $dayc;
				$d2	=strtotime($rowAc['Period'.$x.'EndingDate']);
			}
			
			$p1=$rowB['ActualPeriod'.$x];
			
			$qryBba= "SELECT isnull(SUM(PostingAmount),0) as PACred FROM MAS_".$row['company']."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND PostingAmount >= 0 AND TransactionDate >='".date("m/d/y",$d1)." 00:00:00' AND TransactionDate <='".date("m/d/y",$d2)." 23:59:59';";
			$resBba= mssql_query($qryBba);
			$rowBba= mssql_fetch_array($resBba);
			$nrowBba= mssql_num_rows($resBba);
			
			$qryBbb= "SELECT isnull(SUM(PostingAmount),0) as PADebt FROM MAS_".$row['company']."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND PostingAmount < 0 AND TransactionDate >='".date("m/d/y",$d1)." 00:00:00' AND TransactionDate <='".date("m/d/y",$d2)." 23:59:59';";
			$resBbb= mssql_query($qryBbb);
			$rowBbb= mssql_fetch_array($resBbb);
			$nrowBbb= mssql_num_rows($resBbb);
			
			/*if ($p1 >= 0)
			{
				$cr=0;
				$db=$p1;
			}
			else
			{
				$cr=$p1;
				$db=0;
			}*/
			
			//$rs=$bb + $p1;
			
			$cr=$rowBba['PACred'];
			$db=$rowBbb['PADebt'];
			
			$rs = $bb + ($cr + $db);
			
			//if ($_SESSION['securityid']==26)
			//{
				//echo $bb.':'.$p1.':'.$cr.':'.$db.':'.$rs.':';
				//echo $qryBbb.':';
			//}
			
			$qryBc = "SELECT TransactionDate,RefDescription,BatchNumber,PostingAmount FROM MAS_".$row['company']."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND TransactionDate >='".date("m/d/y",$d1)." 00:00:00' AND TransactionDate <='".date("m/d/y",$d2)." 23:59:59';";
			$resBc = mssql_query($qryBc);
			$nrowBc= mssql_num_rows($resBc);
			
			/*if ($_SESSION['securityid']==26)
			{
				echo $qryBc.'<br>';
			}*/
			
			
			echo "						<tr>\n";
			echo "							<td class=\"".$tbg."\" align=\"right\"><font size=\"1\">Period ".$x." </font></td>\n";
			echo "							<td class=\"".$tbg."\" align=\"center\">".date("m/d/y",$d1)." - ".date("m/d/y",$d2)."</td>\n";
			echo "							<td class=\"".$tbg."\" align=\"right\"><font size=\"1\">".number_format($bb,2,'.',',')."</font></td>\n";
			echo "							<td class=\"".$tbg."\" align=\"right\"><font size=\"1\">".number_format($db,2,'.',',')."</font></td>\n";
			echo "							<td class=\"".$tbg."\" align=\"right\"><font size=\"1\">".number_format($cr,2,'.',',')."</font></td>\n";
			//echo "							<td class=\"".$tbg."\" align=\"right\"><font size=\"1\">".number_format($rs,2,'.',',')."</font></td>\n"; // Remove and uncomment below when drilldown corrected
			
			if ($nrowBc==0)
			{
				echo "							<td class=\"".$tbg."\" align=\"right\"><font size=\"1\">".number_format($rs,2,'.',',')."</font></td>\n";	
			}
			else
			{
				echo "							<td class=\"".$tbg."\" align=\"right\"><font size=\"1\"><a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=cb&a=".$div."&c=".$cpny."&s=".$glacc."&x=".$scdiv."&b=".$bb."&d=".$d1.":".$d2."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=700,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".number_format($rs,2,'.',',')."</a></font></td>\n";
			}
			
			echo "						</tr>\n";
		}
	}
	
	echo "					</table>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
}	

function cib_office_no_disp($cpny,$div,$mul,$glacc,$fyr)
{
	//echo "TEST";
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	$fout=0;
	
	/*if ($cpny==600 && $div == 62)
	{
		$cpny	=600;
		$div	=63;
	}*/
	
	$mssql_ser	= "192.168.1.22";
	$mssql_db	= "MAS_SYSTEM";
	$mssql_user	= "MAS_REPORTS";
	$mssql_pass	= "reports";

	mssql_connect($mssql_ser,$mssql_user,$mssql_pass) or die("Could not connect to db server");
	mssql_select_db($mssql_db) or die("db unavailable");

	$gl_ar=array(101,102,103,104);

	//$currdate=date("m/d/Y h:i A",time());
	
	$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE division='".$div."' AND company='".$cpny."';";
	$res   = mssql_query($qry);
	$row   = mssql_fetch_array($res);
	$nrow  = mssql_num_rows($res);
	
	$qryA = "SELECT CompanyName,CompanyCode,GLFiscalYear FROM MAS_".$cpny."..SY0_CompanyParameters WHERE CompanyCode='".$cpny."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	$qryAa = "SELECT FiscalYr FROM MAS_".$cpny."..GLC_FiscalYrMasterfile ORDER BY FiscalYr ASC;";
	$resAa = mssql_query($qryAa);
	$nrowAa= mssql_num_rows($resAa);
	
	//$scdiv=md5($div);
	
	$qryAc  = "SELECT ";
	$qryAc .= "Period1EndingDate,";
	$qryAc .= "Period2EndingDate,";
	$qryAc .= "Period3EndingDate,";
	$qryAc .= "Period4EndingDate,";
	$qryAc .= "Period5EndingDate,";
	$qryAc .= "Period6EndingDate,";
	$qryAc .= "Period7EndingDate,";
	$qryAc .= "Period8EndingDate,";
	$qryAc .= "Period9EndingDate,";
	$qryAc .= "Period10EndingDate,";
	$qryAc .= "Period11EndingDate,";
	$qryAc .= "Period12EndingDate,";
	$qryAc .= "NumberOfPeriods,";
	$qryAc .= "FiscalYr ";
	$qryAc .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$fyr."';";
	$resAc  = mssql_query($qryAc);
	$rowAc  = mssql_fetch_array($resAc);
	$nrowAc = mssql_num_rows($resAc);
	
	$precurrfisyr=$rowAc['FiscalYr'] - 1;
	
	$qryAd  = "SELECT ";
	$qryAd .= "Period1EndingDate,";
	$qryAd .= "Period2EndingDate,";
	$qryAd .= "Period3EndingDate,";
	$qryAd .= "Period4EndingDate,";
	$qryAd .= "Period5EndingDate,";
	$qryAd .= "Period6EndingDate,";
	$qryAd .= "Period7EndingDate,";
	$qryAd .= "Period8EndingDate,";
	$qryAd .= "Period9EndingDate,";
	$qryAd .= "Period10EndingDate,";
	$qryAd .= "Period11EndingDate,";
	$qryAd .= "Period12EndingDate,";
	$qryAd .= "NumberOfPeriods,";
	$qryAd .= "FiscalYr ";
	$qryAd .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$precurrfisyr."';";
	$resAd  = mssql_query($qryAd);
	$rowAd  = mssql_fetch_array($resAd);
	$nrowAd = mssql_num_rows($resAd);

	if ($cpny=='070XX')
	{
		echo $cpny.'<br>';
		echo $div.'<br>';
		echo $mul.'<br>';
		echo $glacc.'<br>';
		echo $fyr.'<br>';
		echo $nrow.'<br>';
		echo '----<br>';
	}
	
	$dayc=86400;
	if ($nrow > 0 && $nrowAc > 0 && $nrowAd > 0)
	{
		$num_ar=array(10,11,12);
		if (in_array($rowAd['NumberOfPeriods'],$num_ar))
		{
			$noppyr=$rowAd['NumberOfPeriods'];
		}
		else
		{
			$noppyr=$rowAd['NumberOfPeriods'][1];
		}
		
		if (in_array($rowAc['NumberOfPeriods'],$num_ar))
		{
			$nopcyr=$rowAc['NumberOfPeriods'];
		}
		else
		{
			$nopcyr=$rowAc['NumberOfPeriods'][1];
		}
		
		$qryB = "SELECT * FROM MAS_".$cpny."..GL8_BudgetAndHistory WHERE FiscalYr='".$fyr."' and AccountNumber='".$glacc.$div."0000';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);
		$nrowB= mssql_num_rows($resB);
		
		$bb	=$rowB['BegBalTypeActualOnly'];

		if ($bb==0)
		{
			$bb =cib_sum_yr($cpny,$rowAd['FiscalYr'],$glacc,$div,$noppyr);
		}
		
		$d1	=strtotime($rowAd['Period'.$noppyr.'EndingDate']) + $dayc;
		$d2	=strtotime($rowAc['Period'.$nopcyr.'EndingDate']);
		
		if (isset($_REQUEST['beyondyearend']) && $_REQUEST['beyondyearend']==1)
		{
			$qryBba= "SELECT isnull(SUM(PostingAmount),0) as PACred FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND PostingAmount >= 0 AND TransactionDate >='".date("m/d/y",$d1)." 00:00:00';";
			$qryBbb= "SELECT isnull(SUM(PostingAmount),0) as PADebt FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND PostingAmount < 0 AND TransactionDate >='".date("m/d/y",$d1)." 00:00:00';";
		}
		else
		{
			$qryBba= "SELECT isnull(SUM(PostingAmount),0) as PACred FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND PostingAmount >= 0 AND TransactionDate >='".date("m/d/y",$d1)." 00:00:00' AND TransactionDate <='".date("m/d/y",$d2)." 23:59:59';";
			$qryBbb= "SELECT isnull(SUM(PostingAmount),0) as PADebt FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND PostingAmount < 0 AND TransactionDate >='".date("m/d/y",$d1)." 00:00:00' AND TransactionDate <='".date("m/d/y",$d2)." 23:59:59';";
		}
		
		$resBba= mssql_query($qryBba);
		$rowBba= mssql_fetch_array($resBba);
		
		$nrowBba= mssql_num_rows($resBba);
		
		$resBbb= mssql_query($qryBbb);
		$rowBbb= mssql_fetch_array($resBbb);
		$nrowBbb= mssql_num_rows($resBbb);
		
		if ($cpny=='070xxx')
		{
			echo $d1.'<br>';
			echo $d2.'<br>';
			echo $qryBba.'<br>';
			echo $qryBbb.'<br>';
		}	
		
		$cr=$rowBba['PACred'];
		$db=$rowBbb['PADebt'];
		
		$fout = $bb + ($cr + $db);
	}
	
	return $fout;

	ini_set('display_errors','Off');
}

function cib_office_no_dispOLD($cpny,$div,$mul,$glacc,$fyr)
{
	//echo "TEST";
	error_reporting(E_ALL);
	$scpny='370';
	$sglacc=1011111;
	$fout=0;
	
	//Redirect: Div 62 -> 63 
	if ($cpny==600 && $div == 62)
	{
		$cpny	=600;
		$div	=63;
	}
	
	$mssql_ser	= "192.168.1.22";
	$mssql_db	= "MAS_SYSTEM";
	$mssql_user	= "MAS_REPORTS";
	$mssql_pass	= "reports";

	mssql_connect($mssql_ser,$mssql_user,$mssql_pass) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($mssql_db) or die("Table unavailable");

	/*$gl_ar=array(101,102,103,104);

	$currdate=date("m/d/Y h:i A",time());*/
	
	$qryA = "SELECT CompanyName,CompanyCode,GLFiscalYear FROM MAS_".$cpny."..SY0_CompanyParameters WHERE CompanyCode='".$cpny."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA.'<br>-----<br>';
	
	//$qryAa = "SELECT FiscalYr FROM MAS_".$cpny."..GLC_FiscalYrMasterfile ORDER BY FiscalYr ASC;";
	//$resAa = mssql_query($qryAa);
	//$nrowAa= mssql_num_rows($resAa);
	
	$qryAc  = "SELECT ";
	$qryAc .= "Period1EndingDate,";
	$qryAc .= "Period2EndingDate,";
	$qryAc .= "Period3EndingDate,";
	$qryAc .= "Period4EndingDate,";
	$qryAc .= "Period5EndingDate,";
	$qryAc .= "Period6EndingDate,";
	$qryAc .= "Period7EndingDate,";
	$qryAc .= "Period8EndingDate,";
	$qryAc .= "Period9EndingDate,";
	$qryAc .= "Period10EndingDate,";
	$qryAc .= "Period11EndingDate,";
	$qryAc .= "Period12EndingDate,";
	$qryAc .= "NumberOfPeriods,";
	$qryAc .= "FiscalYr ";
	$qryAc .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$fyr."';";
	$resAc  = mssql_query($qryAc);
	$rowAc  = mssql_fetch_array($resAc);
	
	//echo $qryAc.'<br>-----<br>';
	
	$precurrfisyr=$rowAc['FiscalYr'] - 1;
	
	$qryAd  = "SELECT ";
	$qryAd .= "Period1EndingDate,";
	$qryAd .= "Period2EndingDate,";
	$qryAd .= "Period3EndingDate,";
	$qryAd .= "Period4EndingDate,";
	$qryAd .= "Period5EndingDate,";
	$qryAd .= "Period6EndingDate,";
	$qryAd .= "Period7EndingDate,";
	$qryAd .= "Period8EndingDate,";
	$qryAd .= "Period9EndingDate,";
	$qryAd .= "Period10EndingDate,";
	$qryAd .= "Period11EndingDate,";
	$qryAd .= "Period12EndingDate,";
	$qryAd .= "NumberOfPeriods,";
	$qryAd .= "FiscalYr ";
	$qryAd .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$precurrfisyr."';";
	$resAd  = mssql_query($qryAd);
	$rowAd  = mssql_fetch_array($resAd);
	
	//echo $qryAd.'<br>-----<br>';

	$dayc=86400;
	
	//$num_ar=array('10','11','12');
	$num_ar=array(10,11,12);

	if ($nrowA > 0)
	{
		if (in_array($rowAd['NumberOfPeriods'],$num_ar))
		{
			$noppyr=$rowAd['NumberOfPeriods'];
		}
		else
		{
			$noppyr=$rowAd['NumberOfPeriods'][1];
		}
		
		if (in_array($rowAc['NumberOfPeriods'],$num_ar))
		{
			$nopcyr=$rowAc['NumberOfPeriods'];
		}
		else
		{
			$nopcyr=$rowAc['NumberOfPeriods'][1];
		}
		
		$rs	  = 0;
		$qryB = "SELECT * FROM MAS_".$cpny."..GL8_BudgetAndHistory WHERE FiscalYr='".$fyr."' and AccountNumber LIKE '".$glacc.$div."%';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);
		$nrowB= mssql_num_rows($resB);
		
		/*if ($cpny==$scpny && $glacc==$sglacc)
		{
			echo $qryB.'<br>';
		}*/

		$bb	=$rowB['BegBalTypeActualOnly'];
		
		/*if ($_SESSION['securityid']==26)
		{
			echo 'BB ('.$cpny.': '.$bb.'<br>';
			//echo $qryBb.'<br>';
		}*/

		if ($bb==0)
		{
			$bb =cib_sum_yr($cpny,($fyr - 1),$glacc,$div);
		}
		
		$d1	=strtotime($rowAd['Period'.$noppyr.'EndingDate']);
		$d2	=strtotime($rowAc['Period'.$nopcyr.'EndingDate']);
		
		//echo $d1.'<br>';
		//echo $d2.'<br>---<br>';
		
		//$qryBb = "SELECT TransactionDate,RefDescription,BatchNumber,PostingAmount FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND TransactionDate >='".date("m/d/y",$d1)." 23:59:59' AND TransactionDate <='".date("m/d/y",$d2)." 23:59:59';";
		if (isset($_REQUEST['beyondyearend']) && $_REQUEST['beyondyearend']==1)
		{
			$qryBb = "SELECT isnull(sum(PostingAmount),0) as tamt FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND TransactionDate > '".date("m/d/y",$d1)." 23:59:59';";
		}
		else
		{
			$qryBb = "SELECT isnull(sum(PostingAmount),0) as tamt FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$div."%' AND TransactionDate > '".date("m/d/y",$d1)." 23:59:59' AND TransactionDate <='".date("m/d/y",$d2)." 23:59:59';";
		}
		
		$resBb = mssql_query($qryBb);
		$rowBb = mssql_fetch_array($resBb);
		//$nrowBb= mssql_num_rows($resBb);
		
		/*if ($cpny==$scpny && $glacc==$sglacc)
		{
			echo 'BB: '.$bb.'<br>';
			echo $qryBb.'<br>';
		}*/
		
		$fout=$bb+$rowBb['tamt'];
		
	}
	
	return $fout;
}

function cashinbank_officeold()
{
	//echo "TEST";
	error_reporting(E_ALL);
	
	$qrypre1 = "SELECT mas_office,mas_div,gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$respre1 = mssql_query($qrypre1);
	$rowpre1 = mssql_fetch_array($respre1);
	
	//echo $qrypre1."<br>";
	
	if ($rowpre1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to this Area');
	}
	
	if (isset($_REQUEST['division']) || $_REQUEST['division']!=0)
	{
		//$div=substr($_REQUEST['division'],0,2);
		$div=$_REQUEST['division'];
	}
	else
	{
		die('No Division');
	}
	
	if (isset($_REQUEST['cpny']) || $_REQUEST['cpny']!=0)
	{
		//$cpny=substr($_REQUEST['cpny'],4,7);
		$cpny=$_REQUEST['cpny'];
	}
	else
	{
		die('No Division');
	}
	
	//Redirect: Div 62 -> 63 
	if ($cpny==600 && $div == 62)
	{
		$cpny	=600;
		$div	=63;
	}
	
	$mssql_ser	= "192.168.1.22";
	$mssql_db	= "MAS_SYSTEM";
	$mssql_user	= "MAS_REPORTS";
	$mssql_pass	= "reports";

	mssql_connect($mssql_ser,$mssql_user,$mssql_pass) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($mssql_db) or die("Table unavailable");
	
	$c_exar	=array(270,282,311,340,540,801,810,820,830,840,850,860,870,880,890,900,910,920,930,931,940,950,990,999);
	$d_exar	=array(001,002,003,004,600);

	$gl101	=0;
	$gl102	=0;
	$gl103	=0;
	$gl104	=0;
	$gllntot	=0;
	$tgl101	=0;
	$tgl102	=0;
	$tgl103	=0;
	$tgl104	=0;
	$tgllntot=0;
	$tsgl101	=0;
	$tsgl102	=0;
	$tsgl103	=0;
	$tsgl104	=0;
	$tsgllntot=0;
	$ctype	="";
	$oldt		=1;
	$ccnt		=0;
	
	$currdate	=date("m/d/Y h:i A",time());
	
	//$qry   = "SELECT name FROM master..sysdatabases WHERE name LIKE '%MAS%' AND name!='master' AND name!='MAS_SYSTEM' ORDER BY name;";
	//$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE type <= 2 ORDER by type,company;";
	$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE division='".$div."' AND company='".$cpny."';";
	$res   = mssql_query($qry);
	$nrow	 = mssql_num_rows($res);
	
	//echo $qry;
	
	if ($nrow > 0)
	{
		//echo $qry;
	
		echo "		<table class=\"outer\">\n";
		echo "			<tr>\n";
		echo "				<td class=\"gray\" align=\"left\" colspan=\"5\"><font><b>Cash in Bank<b></font></td>\n";
		//echo "				<td class=\"gray\" align=\"left\" colspan=\"2\"><font><b>Requestor: ".."<b></font></td>\n";
		echo "				<td class=\"gray\" align=\"right\" colspan=\"3\"><font><b>".$currdate." PST<b></font></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td class=\"und\" align=\"left\"><font size=\"1\"><b>Company<b></font></td>\n";
		echo "				<td class=\"und\" align=\"center\"><font size=\"1\"><b>Code<b></font></td>\n";
		echo "				<td class=\"und\" align=\"center\"><font size=\"1\"><b>Type<b></font></td>\n";
		echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL101<b></font></td>\n";
		echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL102<b></font></td>\n";
		echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL103<b></font></td>\n";
		echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL104<b></font></td>\n";
		echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL Total<b></font></td>\n";
		echo "			</tr>\n";
	
		while ($row=mssql_fetch_row($res))
		{
			$ccnt++;
			$scdiv=md5($div);
			if ($row[1]==1)
			{
				$ctype="PA";
			}
			elseif ($row[1]==2)
			{
				$ctype="FIT";
			}
			elseif ($row[1]==3)
			{
				$ctype="FR";
			}
			
			$retext=$row[0];
	
			if (!in_array($retext,$c_exar))
			{
				$qryA = "SELECT CompanyName,CompanyCode,GLFiscalYear FROM MAS_".$row[0]."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'$retext');";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				
				$cfyr	=$rowA[2];
			
				$qryAa = "SELECT FiscalYr, Period1EndingDate, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$cfyr."';";
				$resAa = mssql_query($qryAa);
				$rowAa = mssql_fetch_array($resAa);
			
				$pfyr	=$rowAa['FiscalYr']-1;
			
				$qryAb = "SELECT FiscalYr, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$pfyr."';";
				$resAb = mssql_query($qryAb);
				$rowAb = mssql_fetch_array($resAb);
				$nrowAb= mssql_num_rows($resAb);
			
				$endd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
			
				if ($nrowAb == 1)
				{
					$begd	=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
				}
				else
				{
					$begd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
				}	
				
				$gl101	=gl_pull_simple_cib($retext,$div,1,101,$begd,$endd,$rowAa['FiscalYr']);
				$gl102	=gl_pull_simple_cib($retext,$div,1,102,$begd,$endd,$rowAa['FiscalYr']);
				$gl103	=gl_pull_simple_cib($retext,$div,1,103,$begd,$endd,$rowAa['FiscalYr']);
				$gl104	=gl_pull_simple_cib($retext,$div,1,104,$begd,$endd,$rowAa['FiscalYr']);
				$gllntot	=$gl101 + $gl102 + $gl103 + $gl104;
	
				$tgl101	=$tgl101+$gl101;
				$tgl102	=$tgl102+$gl102;
				$tgl103	=$tgl103+$gl103;
				$tgl104	=$tgl104+$gl104;
				$tgllntot=$tgllntot+$gllntot;
				echo "			<tr>\n";
				echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$rowA[0]."</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$retext." (".$div.")</font></td>\n";
				echo "				<td class=\"wh_und\" align=\"center\"><font size=\"1\">".$ctype."</font></td>\n";
				
				if ($gl101 != 0)
				{
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=cb&a=".$div."&c=".$retext."&s=101&x=".$scdiv."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".number_format($gl101)."</a></font></td>\n";
				}
				else
				{
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl101)."</font></td>\n";
				}
				
				if ($gl102 != 0)
				{
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=cb&a=".$div."&c=".$retext."&s=102&x=".$scdiv."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".number_format($gl102)."</a></font></td>\n";
				}
				else
				{
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl102)."</font></td>\n";
				}
				
				if ($gl103 != 0)
				{
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><a href=\"/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=cb&a=".$div."&c=".$retext."&s=103&x=".$scdiv."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".number_format($gl103)."</a></font></td>\n";
				}
				else
				{
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl103)."</font></td>\n";
				}
				
				if ($gl104 != 0)
				{
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=cb&a=".$div."&c=".$retext."&s=104&x=".$scdiv."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".number_format($gl104)."</a></font></td>\n";
				}
				else
				{
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl104)."</font></td>\n";
				}
				

				echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gllntot)."</font></td>\n";
				echo "			</tr>\n";
			}
		
			$oldt		=$row[1];
		}
		
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"><b>Grand Total</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl101)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl102)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl103)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl104)."</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgllntot)."</b></font></td>\n";
		echo "			</tr>\n";
		echo "		</table>\n";
	}
	else
	{
		echo "<b>Company file not Found</b>";
	}
}

function over110percent()
{
	//error_reporting(E_ALL);
	
	$phs_ar=array();
	
	$qry1 = "SELECT securityid,gmreports FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2 = "SELECT phscode,phsname FROM phasebase ORDER BY seqnum;";
	$res2 = mssql_query($qry2);
	
	while($row2 = mssql_fetch_array($res2))
	{
		$phs_ar[$row2['phscode']]=$row2['phsname'];
	}

	if ($row1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to view this Report');
	}

	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	$cpny	=$_REQUEST['cpny'];
	$mdiv	=$_REQUEST['mdiv'];
	$div	=$_REQUEST['division'];
	$scpny	= preg_replace('/^MAS_/', '', $cpny);

	$qry  	= "SELECT CompanyName,CompanyCode FROM MAS_SYSTEM..SY0_CompanyParameters WHERE CompanyCode='".$scpny."';";
	$res  	= mssql_query($qry);
	$row		= mssql_fetch_array($res);
	$cdate	= time();

	if ($mdiv==1)
	{
		$qryA  = "SELECT ";
		$qryA .= "	JobNumber, ";
		$qryA .= "		CostCode, ";
		$qryA .= "		( ";
		$qryA .= "			SELECT  ";
		$qryA .= "				sum(IsNull(TransactionAmount,0))  ";
		$qryA .= "			FROM MAS_".$scpny."..JC3_TransactionDetail ";
		$qryA .= "			WHERE JobNumber=JC3.JobNumber and CostCode=JC3.CostCode and CostType <> '' and RecordType = 2 and SourceCode <> 'PY' ";
		$qryA .= "		) as act, ";
		$qryA .= "		( ";
		$qryA .= "			Select sum(OriginalEstimatedCost) ";
		$qryA .= "			FROM MAS_".$scpny."..JC2_JobCostDetail ";
		$qryA .= "			WHERE JobNumber=JC3.JobNumber and CostCode=JC3.CostCode and CostType <> '' ";
		$qryA .= "		) as est ";
		$qryA .= "	FROM  ";
		$qryA .= "		MAS_".$scpny."..JC3_TransactionDetail JC3 ";
		$qryA .= "	WHERE  ";
		$qryA .= "		JobNumber like '".$div."%' and CostCode IS NOT NULL AND CostType <> '' AND JC3.RecordType = 2 AND JC3.SourceCode <> 'PY' ";
		$qryA .= "	GROUP BY  ";
		$qryA .= "		JC3.JobNumber,JC3.CostCode; ";
	}
	else
	{
		$qryA  = "SELECT ";
		$qryA .= "	JobNumber, ";
		$qryA .= "		CostCode, ";
		$qryA .= "		( ";
		$qryA .= "			SELECT  ";
		$qryA .= "				sum(IsNull(TransactionAmount,0))  ";
		$qryA .= "			FROM MAS_".$scpny."..JC3_TransactionDetail ";
		$qryA .= "			WHERE JobNumber=JC3.JobNumber and CostCode=JC3.CostCode and CostType <> '' and RecordType = 2 and SourceCode <> 'PY' ";
		$qryA .= "		) as act, ";
		$qryA .= "		( ";
		$qryA .= "			Select sum(OriginalEstimatedCost) ";
		$qryA .= "			FROM MAS_".$scpny."..JC2_JobCostDetail ";
		$qryA .= "			WHERE JobNumber=JC3.JobNumber and CostCode=JC3.CostCode and CostType <> '' ";
		$qryA .= "		) as est ";
		$qryA .= "	FROM  ";
		$qryA .= "		MAS_".$scpny."..JC3_TransactionDetail JC3 ";
		$qryA .= "	WHERE  ";
		$qryA .= "		CostCode IS NOT NULL AND CostType <> '' AND JC3.RecordType = 2 AND JC3.SourceCode <> 'PY' ";
		$qryA .= "	GROUP BY  ";
		$qryA .= "		JC3.JobNumber,JC3.CostCode; ";
	}
	$resA  = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	if ($nrowA > 0)
	{
		echo "                  <table border=0>\n";
		echo "                     <tr>\n";
		echo "								<td valign=\"top\">\n";
		echo "                  <table border=0 width=\"100%\" class=\"outer\">\n";
		echo "                     <tr>\n";
		echo "								<td colspan=\"6\" align=\"left\" class=\"gray\" NOWRAP><b>110% Report</b> Note - Does not include Costcodes with Zero Estimated Cost</td>\n";
		echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "								<td colspan=\"3\" align=\"left\" class=\"gray\">".$row['CompanyName']." (MAS_".$row['CompanyCode'].") ($div)</td>\n";
		//echo "								<td colspan=\"3\" align=\"left\" class=\"gray\">XXXXXXXXXXXXXXXXXXXXXXXX (MAS_XXX) (XX)</td>\n";
		echo "								<td colspan=\"3\" align=\"right\" class=\"gray\">".date("m/d/Y",$cdate)."</td>\n";
		echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"left\" class=\"ltgray_und\" NOWRAP><b>Job #</b></td>\n";
		echo "                        <td align=\"left\" class=\"ltgray_und\" NOWRAP><b>Customer</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\" NOWRAP><b>CostCode</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\" NOWRAP><b>Act Cost</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\" NOWRAP><b>Est Cost</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\" NOWRAP><b>Over</b></td>\n";
		echo "                     </tr>\n";
		$trana=0;
		$trane=0;
		$ttrana=0;
		$ttrane=0;
		$o=0;
		$j=0;
		$jov='';
		$tper='';
		
		$ccar=array();
		$a_ar=array();
		$e_ar=array();
		while ($rowA=mssql_fetch_array($resA))
		{
			if ($rowA['est']!=0)
			{
				if ($rowA['est']==0)
				{
					//exit;
					$pervar =  ($rowA['act'] / 1);
				}
				else
				{
					$pervar =  ($rowA['act'] / $rowA['est']) * 100;
				}
				
				if ($pervar >= 110 || $rowA['est']==0)
				{
					if ($jov!=$rowA['JobNumber'])
					{
						$j=0;
						if ($o!=0)
						{
							if ($trane!=0)
							{
								$trant = ($trana / $trane) * 100;
							}
							else
							{
								$trant = $trana / 1;
							}
							
							echo "                     <tr>\n";
							echo "                        <td align=\"right\" colspan=\"2\" class=\"gray_und\"><b>Total</b></td>\n";
							echo "                        <td align=\"right\" class=\"gray_und\"></td>\n";
							echo "                        <td align=\"right\" class=\"gray_und\">&nbsp;".number_format($trana)."</td>\n";
							echo "                        <td align=\"right\" class=\"gray_und\">&nbsp;".number_format($trane)."</td>\n";
							echo "                        <td align=\"right\" class=\"gray_und\">&nbsp;".number_format($trant)."%</td>\n";
							echo "                     </tr>\n";
							echo "                     <tr>\n";
							echo "                        <td align=\"right\" colspan=\"6\" class=\"wh_und\">&nbsp</td>\n";
							echo "                     </tr>\n";
							$trana=0;
							$trane=0;
							$trant=0;
						}
					}
					
					if ($j <= 0)
					{
						$qryB = "SELECT JobDescription FROM MAS_".$scpny."..JC1_JobMaster WHERE JobNumber='".$rowA['JobNumber']."';";
						$resB = mssql_query($qryB);
						$rowB = mssql_fetch_array($resB);
						
						//echo $qryB;
						
						$jname=$rowB['JobDescription'];	
						$jnum=$rowA['JobNumber'];
						$jov=$rowA['JobNumber'];
					}
					else
					{
						$jname='';		
						$jnum='';
					}
					
					echo "                     <tr>\n";
					echo "                        <td align=\"left\" class=\"wh_und\">".$jnum."</td>\n";
					echo "                        <td align=\"left\" class=\"wh_und\" NOWRAP>".$jname."</td>\n";
					//echo "                        <td align=\"left\" class=\"wh_und\" NOWRAP>Xxxxx Xxxxxxxx</td>\n";
					echo "                        <td align=\"center\" class=\"wh_und\">".substr($rowA['CostCode'],0,4)."</td>\n";
					echo "                        <td align=\"right\" class=\"wh_und\">".number_format($rowA['act'])."</td>\n";
					echo "                        <td align=\"right\" class=\"wh_und\">".number_format($rowA['est'])."</td>\n";
					echo "                        <td align=\"right\" class=\"wh_und\">".number_format($pervar)."% ".$tper."</td>\n";
					echo "                     </tr>\n";
					$trana=$trana+$rowA['act'];
					$trane=$trane+$rowA['est'];
					$ttrana=$ttrana+$rowA['act'];
					$ttrane=$ttrane+$rowA['est'];
					
					if (array_key_exists($rowA['CostCode'],$ccar))
					{
						$ccar[$rowA['CostCode']]=array($ccar[$rowA['CostCode']][0]+$rowA['act'],$ccar[$rowA['CostCode']][1]+$rowA['est']);
					}
					else
					{
						$ccar[$rowA['CostCode']]=array($rowA['act'],$rowA['est']);
					}
					
					$j++;
					$o++;
					
					if ($o==$nrowA)
					{
							echo "                     <tr>\n";
							echo "                        <td align=\"right\" colspan=\"2\" class=\"wh_und\"><b>Total</b></td>\n";
							echo "                        <td align=\"right\" class=\"wh_und\">&nbsp</td>\n";
							echo "                        <td align=\"right\" class=\"wh_und\">&nbsp</td>\n";
							echo "                        <td align=\"right\" class=\"wh_und\">&nbsp</td>\n";
							echo "                        <td align=\"right\" class=\"wh_und\">&nbsp</td>\n";
							echo "                     </tr>\n";
							echo "                     <tr>\n";
							echo "                        <td align=\"right\" colspan=\"5\" class=\"wh_und\">&nbsp</td>\n";
							echo "                     </tr>\n";
					}
					
				}
			}
			//$trant=$trant+$rowA['AmtDue'];
		}
		
		if ($ttrane==0)
		{
			//$ttrant = ($ttrana / 1);
			$ttrant = 100;
		}
		else
		{
			$ttrant = ($ttrana / $ttrane) * 100;
		}
		
		echo "                     <tr>\n";
		echo "                        <td align=\"right\" colspan=\"6\" class=\"wh_und\"></td>\n";
		echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"right\" colspan=\"2\" class=\"gray_und\"><b>Grand Total</b></td>\n";
		echo "                        <td align=\"right\" class=\"gray_und\"></td>\n";
		echo "                        <td align=\"right\" class=\"gray_und\">".number_format($ttrana)."</td>\n";
		echo "                        <td align=\"right\" class=\"gray_und\">".number_format($ttrane)."</td>\n";
		echo "                        <td align=\"right\" class=\"gray_und\">".number_format($ttrant)."%</td>\n";
		echo "                     </tr>\n";
		echo "                  </table>\n";
		echo "		</td>\n";
		echo "		<td valign=\"top\">\n";
		
		//print_r($phs_ar);
		
		echo "                  <table border=0 width=\"100%\" class=\"outer\">\n";
		echo "                     <tr>\n";
		echo "								<td colspan=\"4\" align=\"left\" class=\"gray\" NOWRAP><b>110% Report Phase Analysis<b></td>\n";
		echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "								<td colspan=\"4\" align=\"left\" class=\"gray\" NOWRAP>&nbsp</td>\n";
		echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\" NOWRAP><b>CostCode</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\" NOWRAP><b>Act Cost</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\" NOWRAP><b>Est Cost</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\" NOWRAP><b>% Over</b></td>\n";
		echo "                     </tr>\n";
		
		
		$perc_ar=array();
		foreach ($ccar as $n => $v)
		{
			if ($ccar[$n][1]==0)
			{
				$percs		=($ccar[$n][0] / 1);
			}
			else
			{
				$percs		=($ccar[$n][0] / $ccar[$n][1]) * 100;
			}
			
			if ($percs!=0)
			{
				$perc_ar[$n]	=round($percs);
			}
			
		}
		
		arsort($perc_ar);
		
		foreach ($perc_ar as $n2 => $v2)
		{
			$join_ar[]=array($n2,$v2,$ccar[$n2][0],$ccar[$n2][1]);
		}
		
		$altt="";
		foreach ($join_ar as $n3 => $v3)
		{
			if (array_key_exists(substr($v3[0],0,4),$phs_ar))
			{
				$altt=$phs_ar[substr($v3[0],0,4)];
			}
			
			echo "                     <tr>\n";
			echo "                        <td align=\"center\"  class=\"ltgray_und\" NOWRAP><font title=\"".$altt."\">".substr($v3[0],0,4)."</font></td>\n";
			echo "                        <td align=\"right\" class=\"ltgray_und\" NOWRAP>".number_format($v3[2])."</td>\n";
			echo "                        <td align=\"right\" class=\"ltgray_und\" NOWRAP>".number_format($v3[3])."</td>\n";
			echo "                        <td align=\"right\" class=\"ltgray_und\" NOWRAP>".number_format($v3[1])."%</td>\n";
			echo "                     </tr>\n";	
		}
		
		echo "						</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		
		//show_array_vars($ccar);
		//show_array_vars($perc_ar);
		//show_array_vars($join_ar);
	}
	else
	{
		echo "<b>No Jobs found over 110%</b>";
	}
}

function openjobs()
{
	$qry1 = "SELECT securityid,gmreports FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	if ($row1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to view this Report');
	}

	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	$cpny	=$_REQUEST['cpny'];
	$mdiv	=$_REQUEST['mdiv'];
	$div	=$_REQUEST['division'];
	$scpny= preg_replace('/^MAS_/', '', $cpny);

	$qry  = "SELECT CompanyName,CompanyCode FROM MAS_SYSTEM..SY0_CompanyParameters WHERE CompanyCode='".$scpny."';";
	$res  = mssql_query($qry);
	$row	= mssql_fetch_array($res);
	
	$nodays		=5184000;
	//$nodays			=25920000;
	
	$cdate		=time();
	
	$prevdate	=$cdate - $nodays;
	
	//echo $cdate."<br>";
	//echo $div."<br>";

	if ($mdiv==1)
	{
		$qryA  = "select 			a.JobNumber,b.JobDescription,a.Status,b.RevisedContract,b.OriginalEstimate,b.JTDActualCosts,b.JTDInvoiceBilled, ";
		$qryA .= "					(SELECT SUM(PaymentAmount) FROM MAS_".$scpny."..JC_A2PaymentSummaryFile WHERE JobNumber=a.JobNumber) as TranAmt, ";
		$qryA .= "					(b.RevisedContract - (SELECT SUM(PaymentAmount) FROM MAS_".$scpny."..JC_A2PaymentSummaryFile WHERE JobNumber=a.JobNumber)) as AmtDue, ";
		$qryA .= "					b.LastPaymentDate,  ";
		$qryA .= "					a.ReportedDate  ";
		$qryA .= "		from 		MAS_".$scpny."..JC2_JobCostDetail as a ";
		$qryA .= "		inner join	MAS_".$scpny."..JC1_JobMaster as b ";
		$qryA .= "		on			a.JobNumber=b.JobNumber ";
		$qryA .= "		where 		a.CostCode='524L00000'  ";
		$qryA .= "		and 		a.Status > 1  ";
		$qryA .= "		and 		a.ReportedDate <='".date("m/d/Y",$prevdate)."' ";
		$qryA .= "		and			b.JobStatus='O' ";
		$qryA .= "		and			b.JobNumber LIKE '".$div."%' ";
		$qryA .= "		order by 	b.JobNumber;";
	}
	else
	{
		$qryA  = "select 			a.JobNumber,b.JobDescription,a.Status,b.RevisedContract,b.OriginalEstimate,b.JTDActualCosts,b.JTDInvoiceBilled, ";
		$qryA .= "					(SELECT SUM(PaymentAmount) FROM MAS_".$scpny."..JC_A2PaymentSummaryFile WHERE JobNumber=a.JobNumber) as TranAmt, ";
		$qryA .= "					(b.RevisedContract - (SELECT SUM(PaymentAmount) FROM MAS_".$scpny."..JC_A2PaymentSummaryFile WHERE JobNumber=a.JobNumber)) as AmtDue, ";
		$qryA .= "					b.LastPaymentDate,  ";
		$qryA .= "					a.ReportedDate  ";
		$qryA .= "		from 		MAS_".$scpny."..JC2_JobCostDetail as a ";
		$qryA .= "		inner join	MAS_".$scpny."..JC1_JobMaster as b ";
		$qryA .= "		on			a.JobNumber=b.JobNumber ";
		$qryA .= "		where 		a.CostCode='524L00000'  ";
		$qryA .= "		and 		a.Status > 1  ";
		$qryA .= "		and 		a.ReportedDate <='".date("m/d/Y",$prevdate)."' ";
		$qryA .= "		and			b.JobStatus='O' ";
		$qryA .= "		order by 	b.JobNumber;";
	}
	$resA  = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	if ($nrowA > 0)
	{
		echo "                  <table border=0 class=\"outer\">\n";
		echo "                     <tr>\n";
		echo "								<td colspan=\"3\" align=\"left\" class=\"gray\"><b>Open Job Report</b></td>\n";
		echo "								<td colspan=\"4\" align=\"right\" class=\"gray\">No transactions Last 60 Days</td>\n";
		echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "								<td colspan=\"4\" align=\"left\" class=\"gray\">".$row['CompanyName']." (MAS_".$row['CompanyCode'].")</td>\n";
		//echo "								<td colspan=\"4\" align=\"left\" class=\"gray\">XXXXXXXXXXXXXX (MAS_XXX) (XX)</td>\n";
		echo "								<td colspan=\"3\" align=\"right\" class=\"gray\">".date("m/d/Y",$prevdate)." to Present</td>\n";
		echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>Job #</b></td>\n";
		echo "                        <td align=\"left\" class=\"ltgray_und\"><b>Customer</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>Estimate Cost</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>Actual Cost</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>Contract Amt</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>Payments</b></td>\n";
		echo "                        <td align=\"center\" class=\"ltgray_und\"><b>Amt Due</b></td>\n";
		echo "                     </tr>\n";
		$trant=0;
		$ccnt=0;
		while ($rowA=mssql_fetch_array($resA))
		{
			$ccnt++;
			
			if (isset($_REQUEST['print']) && $_REQUEST['print']==1)
			{
				if ($ccnt%2)
				{
					$tbg = "wh_und";
				}
				else
				{
					$tbg = "gray_und";
				}	
			}
			else
			{
				if ($ccnt%2)
				{
					$tbg = "white";
				}
				else
				{
					$tbg = "gray";
				}
			}
			
			//$bdate	=date("m/d/Y",strtotime($rowA['BirthDate']));
			$bdate	=$rowA['BirthDate'];
			$hdate	=date("m/d/Y",strtotime($rowA['HireDate']));

			if (!empty($rowA['TerminationDate']))
			{
				$tdate	=date("m/d/Y",strtotime($rowA['TerminationDate']));
			}
			else
			{
				$tdate="";
			}
			
			if ($rowA['AmtDue']==0)
			{
				$fcolor='red';
			}
			else
			{
				$fcolor='black';
			}

			echo "                     <tr>\n";
			echo "                        <td align=\"left\" class=\"".$tbg."\">&nbsp;<font color=\"".$fcolor."\">".$rowA['JobNumber']."</font>&nbsp;</td>\n";
			echo "                        <td align=\"left\" class=\"".$tbg."\">&nbsp;<font color=\"".$fcolor."\">".$rowA['JobDescription']."</font>&nbsp;</td>\n";
			//echo "                        <td align=\"left\" class=\"".$tbg."\">&nbsp;<font color=\"".$fcolor."\">Xxxxx Xxxxxxx</font>&nbsp;</td>\n";
			echo "                        <td align=\"right\" class=\"".$tbg."\">&nbsp;<font color=\"".$fcolor."\">".number_format($rowA['OriginalEstimate'], 2, '.', '')."</font>&nbsp;</td>\n";
			echo "                        <td align=\"right\" class=\"".$tbg."\">&nbsp;<font color=\"".$fcolor."\">".number_format($rowA['JTDActualCosts'], 2, '.', '')."</font>&nbsp;</td>\n";
			echo "                        <td align=\"right\" class=\"".$tbg."\">&nbsp;<font color=\"".$fcolor."\">".number_format($rowA['RevisedContract'], 2, '.', '')."</font>&nbsp;</td>\n";
			echo "                        <td align=\"right\" class=\"".$tbg."\">&nbsp;<font color=\"".$fcolor."\">".number_format($rowA['TranAmt'], 2, '.', '')."</font>&nbsp;</td>\n";
			echo "                        <td align=\"right\" class=\"".$tbg."\">&nbsp;<font color=\"".$fcolor."\">".number_format($rowA['AmtDue'], 2, '.', '')."</font>&nbsp;</td>\n";
			echo "                     </tr>\n";
			$trant=$trant+$rowA['AmtDue'];
		}
		
		echo "                     <tr>\n";
		echo "                        <td align=\"right\" colspan=\"6\" class=\"ltgray_upperline\"><b>Total</b>&nbsp;</td>\n";
		echo "                        <td align=\"right\" class=\"ltgray_upperline\">&nbsp;".number_format($trant, 2, '.', '')."&nbsp;</td>\n";
		echo "                     </tr>\n";
		echo "                  </table>\n";
	}
}

function glquerypre()
{
	error_reporting(E_ALL);
	
	if ($_SESSION['officeid']!=89)
	{
		echo "You do not have appropriate permission to view this resource";
		exit;
	}
	$currdate	=date("m/d/Y h:i A",time());
	
	echo "		<table class=\"outer\">\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"left\"><font><b>GL Query<b></font></td>\n";
	echo "				<td class=\"gray\" align=\"right\"><font><b>".$currdate."<b></font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" colspan=\"2\">\n";
	echo "							<form name=\"glquerypre\" action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
	echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "								<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
	echo "								<input type=\"hidden\" name=\"subq\" value=\"glquery\">\n";
	echo "					<table>\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"right\"><font>GL Account:</font></td>\n";
	echo "							<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"glacc\" maxlength=\"6\"></td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"right\"><font>Start Date:</font></td>\n";
	echo "							<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"d1\" maxlength=\"8\"></td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"right\"><font>End Date:</font></td>\n";
	echo "							<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"d2\" maxlength=\"8\"></td>\n";
	echo "						</tr>\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" align=\"right\"></td>\n";
	echo "							<td class=\"gray\" align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"GL Query\"></td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "					</form>\n";
	echo "				</td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
}

function glquery()
{
	//echo "TEST";
	error_reporting(0);
	
	if ($_SESSION['officeid']!=89)
	{
		echo "You do not have appropriate permission to view this resource";
		exit;
	}
	
	$mssql_ser	= "192.168.1.22";
	$mssql_db	= "MAS_SYSTEM";
	$mssql_user	= "MAS_REPORTS";
	$mssql_pass	= "reports";

	mssql_connect($mssql_ser,$mssql_user,$mssql_pass) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($mssql_db) or die("Table unavailable");
	
	$c_exar	=array(270,282,311,340,540,800,801,810,820,830,840,850,860,870,880,890,900,910,920,930,931,940,950,990,999);
	$d_exar	=array(001,002,003,004,600,888);

	$gl101	=0;
	$gl102	=0;
	$gl103	=0;
	$gllntot	=0;
	$tgl101	=0;
	$tgl102	=0;
	$tgl103	=0;
	$tgllntot=0;
	$tsgl101	=0;
	$tsgl102	=0;
	$tsgl103	=0;
	$tsgllntot=0;
	$ctype	="";
	$oldt		=1;
	$ccnt		=0;
	
	$currdate	=date("m/d/Y h:i A",time());
	
	//$qry   = "SELECT name FROM master..sysdatabases WHERE name LIKE '%MAS%' AND name!='master' AND name!='MAS_SYSTEM' ORDER BY name;";
	$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE type <= 3 ORDER by type,company;";
	//$qry   = "SELECT DISTINCT(company),type FROM ZE_Stats..divtocomp WHERE division='07';";
	$res   = mssql_query($qry);
	$nrow	 = mssql_num_rows($res);

	echo "		<table class=\"outer\">\n";
	echo "			<tr>\n";
	echo "				<td class=\"gray\" align=\"left\"><font><b>GL Query<b></font></td>\n";
	echo "				<td class=\"gray\" align=\"right\" colspan=\"3\"><font>".$_REQUEST['d1']." - ".$_REQUEST['d2']."</font></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td class=\"und\" align=\"left\"><font size=\"1\"><b>Company<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\"><font size=\"1\"><b>Code<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\"><font size=\"1\"><b>Type<b></font></td>\n";
	echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL".$_REQUEST['glacc']."<b></font></td>\n";
	//echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL102<b></font></td>\n";
	//echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL103<b></font></td>\n";
	//echo "				<td class=\"und\" align=\"center\" width=\"70px\"><font size=\"1\"><b>GL Total<b></font></td>\n";
	echo "			</tr>\n";

	while ($row=mssql_fetch_row($res))
	{
		$ccnt++;
		if ($row[1]==1)
		{
			$ctype="PA";
		}
		elseif ($row[1]==2)
		{
			$ctype="FIT";
		}
		elseif ($row[1]==3)
		{
			$ctype="FR";
		}
		
		if ($oldt!=$row[1])
		{
			echo "			<tr>\n";
			echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"><b>Sub Total</b></font></td>\n";
			echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
			echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
			echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl101)."</b></font></td>\n";
			//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl102)."</b></font></td>\n";
			//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl103)."</b></font></td>\n";
			//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgllntot)."</b></font></td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td class=\"wh_und\" colspan=\"7\" align=\"left\">&nbsp</td>\n";
			echo "			</tr>\n";
			$tsgl101		=0;
			$tsgl102		=0;
			$tsgl103		=0;
			$tsgllntot	=0;
		}
		
		//$retext=substr($row[0],4);
		$retext=$row[0];

		if (!in_array($retext,$c_exar))
		{
			$qryA = "SELECT CompanyName,CompanyCode,GLFiscalYear FROM MAS_".$row[0]."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'$retext');";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			$cfyr	=$rowA[2];
			
			$qryAa = "SELECT FiscalYr, Period1EndingDate, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$cfyr."';";
			$resAa = mssql_query($qryAa);
			$rowAa = mssql_fetch_array($resAa);
			
			$pfyr	=$rowAa['FiscalYr']-1;
			
			$qryAb = "SELECT FiscalYr, Period12EndingDate FROM MAS_".$row[0]."..GLC_FiscalYrMasterfile WHERE FiscalYr='".$pfyr."';";
			$resAb = mssql_query($qryAb);
			$rowAb = mssql_fetch_array($resAb);
			$nrowAb= mssql_num_rows($resAb);
			
			$endd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
			
			if ($nrowAb == 1)
			{
				$begd	=date("m/d/Y",strtotime($rowAb['Period12EndingDate']))." 11:59:59";
			}
			else
			{
				$begd	=date("m/d/Y",strtotime($rowAa['Period12EndingDate']))." 11:59:59";
			}
			
			//echo $begd."<br>";
			//echo $endd."<br>";

			if (!ereg("[A-Z]",$retext))
			{
				$qryB = "SELECT DeptNumber FROM MAS_".$row[0]."..GL7_Department WHERE DeptNumber!=000000000;";
				$resB = mssql_query($qryB);
				$nrowsB = mssql_num_rows($resB);

				if ($nrowsB > 1)
				{
					// Multidivision Companies
					$qryC = "SELECT DeptNumber,DeptName FROM MAS_".$row[0]."..GL7_Department WHERE DeptNumber!=000000000 ORDER BY DeptNumber;";
					$resC = mssql_query($qryC);

					while ($rowC=mssql_fetch_row($resC))
					{
						$retextdiv =substr($rowC[0],0,3);
						if (!in_array($retextdiv,$d_exar))
						{
							$gl101	=gl_pull_drange($retext,$retextdiv,1,$_REQUEST['glacc'],$_REQUEST['d1'],$_REQUEST['d2']);
							//$gl101	=gl_pull_simple_cib($retext,$retextdiv,1,101,$begd,$endd,$rowAa['FiscalYr']);
							//$gl102	=gl_pull_simple_cib($retext,$retextdiv,1,102,$begd,$endd,$rowAa['FiscalYr']);
							//$gl103	=gl_pull_simple_cib($retext,$retextdiv,1,103,$begd,$endd,$rowAa['FiscalYr']);
							//$gllntot	=$gl101 + $gl102 + $gl103;

							$tgl101	=$tgl101+$gl101;
							//$tgl102	=$tgl102+$gl102;
							//$tgl103	=$tgl103+$gl103;
							//$tgllntot=$tgllntot+$gllntot;
							$tsgl101	=$tsgl101+$gl101;
							//$tsgl102	=$tsgl102+$gl102;
							//$tsgl103	=$tsgl103+$gl103;
							//$tsgllntot=$tsgllntot+$gllntot;
							echo "			<tr>\n";
							echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$rowA[0]." ".($rowC[1])."</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$retext." (".$retextdiv.")</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"center\"><font size=\"1\">".$ctype."</font></td>\n";
							echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl101)."</font></td>\n";
							//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl102)."</font></td>\n";
							//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl103)."</font></td>\n";
							//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gllntot)."</font></td>\n";
							echo "			</tr>\n";
						}
					}
				}
				else
				{
					// Single Div Companies
					$gl101	=gl_pull_drange($retext,0,0,$_REQUEST['glacc'],$_REQUEST['d1'],$_REQUEST['d2']);
					//$gl101	=gl_pull_simple_cib($retext,0,0,101,$begd,$endd,$rowAa['FiscalYr']);
					//$gl102	=gl_pull_simple_cib($retext,0,0,102,$begd,$endd,$rowAa['FiscalYr']);
					//$gl103	=gl_pull_simple_cib($retext,0,0,103,$begd,$endd,$rowAa['FiscalYr']);
					//$gl101	=gl_pull_simple($retext,0,0,101);
					//$gl102	=gl_pull_simple($retext,0,0,102);
					//$gl103	=gl_pull_simple($retext,0,0,103);
					//$gllntot	=$gl101 + $gl102 + $gl103;

					$tgl101	=$tgl101+$gl101;
					//$tgl102	=$tgl102+$gl102;
					//$tgl103	=$tgl103+$gl103;
					//$tgllntot=$tgllntot+$gllntot;
					$tsgl101	=$tsgl101+$gl101;
					//$tsgl102	=$tsgl102+$gl102;
					//$tsgl103	=$tsgl103+$gl103;
					//$tsgllntot=$tsgllntot+$gllntot;
					echo "			<tr>\n";
					echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$rowA[0]."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\">".$retext."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"center\"><font size=\"1\">".$ctype."</font></td>\n";
					echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl101)."</font></td>\n";
					//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl102)."</font></td>\n";
					//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gl103)."</font></td>\n";
					//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\">".number_format($gllntot)."</font></td>\n";
					echo "			</tr>\n";
				}
			}
		}
	
		$oldt		=$row[1];
	}
	
	if ($nrow == $ccnt)
	{
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"><b>Sub Total</b></font></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
		echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl101)."</b></font></td>\n";
		//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl102)."</b></font></td>\n";
		//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgl103)."</b></font></td>\n";
		//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tsgllntot)."</b></font></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td class=\"wh_und\" colspan=\"7\" align=\"left\">&nbsp</td>\n";
		echo "			</tr>\n";
		$tsgl101		=0;
		$tsgl102		=0;
		$tsgl103		=0;
		$tsgllntot	=0;
	}
	
	echo "			<tr>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"><b>Grand Total</b></font></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"left\"><font size=\"1\"></td>\n";
	echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl101)."</b></font></td>\n";
	//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl102)."</b></font></td>\n";
	//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgl103)."</b></font></td>\n";
	//echo "				<td class=\"wh_und\" align=\"right\"><font size=\"1\"><b>".number_format($tgllntot)."</b></font></td>\n";
	echo "			</tr>\n";
	echo "		</table>\n";
}

function getYTDrange($dtar,$prd)
{
	$out	=array();
	$tprd	= $prd-1;
	$ark	=0;
	$tmpvar	='';
	//$tedar	=array_keys($dtar);
	
	foreach (array_keys($dtar) as $n => $v)
	{
		//echo $ark."|".$tprd."<br>";
		if ($ark==$tprd)
		{
			//echo $ark."|".$tprd."<br>";
			$tmpvar=$v;
		}
		$ark++;
	}

	$out		=array($dtar[array_shift(array_keys($dtar))][0],$dtar[$tmpvar][1]);
	return $out;
}

function drill_ov_ind()
{
	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";
	//$dbname   = "MAS_SYSTEM";

	//mssql_connect($hostname,$username,$password) or die("DATABASE FAILED TO RESPOND.");
	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	$tpost=0;
	$qryA = "SELECT * FROM ".$_GET['c']."..GL5_DetailPosting WHERE AccountNumber='".$_GET['a']."' AND TransactionDate BETWEEN '".$_GET['d0']."' AND '".$_GET['d1']."' ORDER by TransactionDate ASC;";
	$resA = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	if ($nrowA > 0)
	{
		$qryAa = "SELECT * FROM ".$_GET['c']."..GL1_Accounts WHERE AccountNumber='".$_GET['a']."';";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);

		$qryAb = "SELECT CompanyName FROM ".$_GET['c']."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".substr($_GET['c'],4)."');";
		$resAb = mssql_query($qryAb);
		$rowAb = mssql_fetch_array($resAb);

		echo "<table class=\"outer\" width=\"40%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"5\" class=\"gray\">\n";
		echo "			<table width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\" align=\"left\"><b>Company:</b></td><td class=\"gray\" align=\"left\">".$rowAb['CompanyName']." (".$_GET['c'].")</td>\n";
		echo "					<td class=\"gray\" align=\"right\"><b>Dates:</b></td><td class=\"gray\" align=\"left\">".date("m/d/Y",strtotime($_GET['d0']))." - ".date("m/d/Y",strtotime($_GET['d1']))."</td>\n";
		echo "         			</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\" align=\"left\"><b>Account:</b></td><td class=\"gray\" align=\"left\">".$rowAa['AccountDescription']."</td>\n";
		echo "					<td class=\"gray\" align=\"right\"><b>Acct #:</b></td><td class=\"gray\" align=\"left\">".$_GET['a']."</td>\n";
		echo "         			</tr>\n";
		echo "         		</table>\n";
		echo "		</td>\n";
		echo "         </tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Date</b></td>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Source</b></td>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Description</b></td>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Vendor Ref</b></td>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Posting Amount</b></td>\n";
		echo "         </tr>\n";

		$vendorname="";
		while ($rowA = mssql_fetch_array($resA))
		{
			if ($rowA['SourceJournal']=="AP" || $rowA['SourceJournal']=="PR")
			{
				$srd	=explode(" ",substr($rowA['RefDescription'],2));
				$qryB = "SELECT VendorName FROM ".$_GET['c']."..AP1_VendorMaster WHERE VendorNumber='".$srd[0]."';";
				$resB = mssql_query($qryB);
				$nrowB = mssql_num_rows($resB);

				//echo $qryB."<br>";
				if ($nrowB > 0)
				{
					$rowB = mssql_fetch_array($resB);

					$vendorname=$rowB['VendorName'];
				}
			}

			echo "	<tr>\n";
			echo "		<td class=\"wh_und\" align=\"right\">".date("m/d/Y",strtotime($rowA['TransactionDate']))."</td>\n";
			echo "		<td class=\"wh_und\" align=\"center\">".$rowA['SourceJournal']."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$rowA['RefDescription']."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$vendorname."</td>\n";
			echo "		<td class=\"wh_und\" align=\"right\">".number_format($rowA['PostingAmount'], 2, '.', '')."</td>\n";
			echo "         </tr>\n";
			$vendorname="";
			$tpost=$tpost+$rowA['PostingAmount'];
		}

		echo "	<tr>\n";
		echo "		<td class=\"wh_und\" align=\"right\"></td>\n";
		echo "		<td class=\"wh_und\" align=\"center\"></td>\n";
		echo "		<td class=\"wh_und\" align=\"center\"></td>\n";
		echo "		<td class=\"wh_und\" align=\"right\"><b>Total</b></td>\n";
		echo "		<td class=\"wh_und\" align=\"right\">".number_format($tpost, 2, '.', '')."</td>\n";
		echo "         </tr>\n";
		echo "         </table>\n";
	}


}

function randomwordgen($w_ar,$c)
{
	if (is_array($w_ar))
	{
		for ($x = 1; $c >= $x; $x++)
		{
			$out=$out.'A';	
		}
	}
	else
	{
		$out='faulty';
	}
	
	return $out;
}

function op_matrix_wordtest()
{
	if (!isset($_REQUEST['tword']) || empty($_REQUEST['tword']))
	{
		srand((float) microtime() * 10000000);
		$war			=array('beach','waves','surfs','shell','stars','magic','aware','truth',' trust','first','trips','trees','sends','creep','daily');
		//$war 			=array('A','B','C','D','E','F','G','H','I','J','K','L','0','1','2','3','4','5','6','7','8','9');
		//$wars			='beach:waves:surfs:shell:stars:magic:aware:truth:trust:first:trips:brown:sends:creep:daily';
		//$_SESSION['wars']	=$wars;
		//$randwar				=$war[array_rand($war,1)];
		//$_SESSION['war']	=randomwordgen($war,5);
		$_SESSION['war']	=$war[array_rand($war,1)];

		echo "	<form name=\"wordtest1\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "	<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "	<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
		echo "<table border=0 class=\"outer\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"center\" class=\"gray\">Enter the following word<br>in the box below and click Validate</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"center\" class=\"gray\"><iframe src=\"subs/wordtest.php\" frameborder=\"0\" width=\"135px\" height=\"60px\" scrolling=\"no\"></iframe></td>\n";
		echo "	</tr>\n";
		/*
		echo "	<tr>\n";
		echo "		<td align=\"center\" colspan=\"2\" class=\"gray\">Enter the following word<br>in the box below and click Validate: <br><b>".$randwar."</b></td>\n";
		echo "	</tr>\n";
		*/
		echo "	<tr>\n";
		echo "		<td align=\"center\" class=\"gray\"><input type=\"text\" name=\"tword\" size=\"20\" maxlength=\"5\"><br><input class=\"buttondkgry\" type=\"submit\" value=\"Validate\"></td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
		echo "	</form>\n";
		exit;
	}
	else
	{
		if (isset($_SESSION['war']) && !empty($_SESSION['war']))
		{
			if ($_SESSION['war']==$_REQUEST['tword'])
			{
				$_SESSION['wtest'] = 1;
				companylist();
			}
			else
			{
				unset($_SESSION['war']);
				unset($_REQUEST['tword']);
				op_matrix_wordtest();
				exit;
			}
		}
		else
		{
			unset($_SESSION['war']);
			unset($_REQUEST['tword']);
			op_matrix_wordtest();
			exit;
		}
	}
}

function op_matrix()
{
	error_reporting(E_ALL);
	
	$qrypre1 = "SELECT gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$respre1 = mssql_query($qrypre1);
	$rowpre1 = mssql_fetch_array($respre1);
	
	if ($rowpre1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to this Area');
	}
	
	//events();
	
	//echo "         <table width=\"900px\">\n";
	echo "         <table width=\"100%\">\n";
	echo "            <tr><td align=\"center\">\n";

	if (!isset($_SESSION['wtest']) || $_SESSION['wtest'] != 1)
	{
		//echo "WTWTWT<br>\n";
		op_matrix_wordtest();
	}

	if ($_SESSION['subq']=="clist")
	{
		//echo "XXXY<br>\n";
		companylist();
	}
	elseif ($_SESSION['subq']=='opuselog')
	{
		opuselog();
	}
	elseif ($_SESSION['subq']=='vlist')
	{
		vendorlist();
	}
	elseif ($_SESSION['subq']=='preopstate')
	{
		preopstate();
	}
	elseif ($_SESSION['subq']=='opstate')
	{
		opstate();
	}
	elseif ($_SESSION['subq']=='preopstate_compare')
	{
		preopstate_compare();
	}
	elseif ($_SESSION['subq']=='opstate_compare')
	{
		opstate_compare();
	}
	elseif ($_SESSION['subq']=='digreport')
	{
		digreport();
	}
	elseif ($_SESSION['subq']=='dgsave')
	{
		digreportsave();
	}
	elseif ($_SESSION['subq']=='dghistory')
	{
		digreporthistory();
	}
	elseif ($_SESSION['subq']=='arreport')
	{
		arreport();
	}
	elseif ($_SESSION['subq']=='ecreport')
	{
		employeecensus();
	}
	elseif ($_SESSION['subq']=='date')
	{
		setdatearray();
	}
	elseif ($_SESSION['subq']=="oidrill")
	{	
		drill_ov_ind();
	}
	elseif ($_SESSION['subq']=="ojreport")
	{	
		openjobs();
	}
	elseif ($_SESSION['subq']=="110report")
	{	
		over110percent();
	}
	elseif ($_SESSION['subq']=="openable")
	{	
		op_enable();
	}
	elseif ($_SESSION['subq']=="openableset")
	{	
		op_enable_set();
	}
	elseif ($_SESSION['subq']=="cib_admin")
	{	
		cashinbank_admin();
	}
	elseif ($_SESSION['subq']=="cib_office")
	{	
		cashinbank_office();
	}
	elseif ($_SESSION['subq']=="glquerypre")
	{	
		glquerypre();
	}
	elseif ($_SESSION['subq']=="glquery")
	{	
		glquery();
	}
	elseif ($_SESSION['subq']=="nq")
	{	
		netquick();
	}
	elseif ($_SESSION['subq']=="nq_admin")
	{	
		netquick_admin();
	}
	elseif ($_SESSION['subq']=="ops_print")
	{	
		ops_print();
	}
	elseif ($_SESSION['subq']=="ops_print")
	{	
		ops_print();
	}
	elseif ($_SESSION['subq']=="cjreport")
	{	
		jobclosings();
	}
	elseif ($_SESSION['subq']=="officeconfig")
	{	
		officeconfig();
	}
	elseif ($_SESSION['subq']=="officeconfig_add")
	{	
		officeconfig_add();
	}
	elseif ($_SESSION['subq']=="officeconfig_del")
	{	
		officeconfig_del();
	}
	elseif ($_SESSION['subq']=="officeconfig_upd")
	{	
		officeconfig_upd();
	}
	elseif ($_SESSION['subq']=="os_admin")
	{	
		os_admin();
	}
	
	echo "                  </table>\n";
	echo "            </td></tr>\n";
	echo "         </table>\n";
}

function op_enable_os($oa)
{
	global $dtarray,$otharray,$pdarray,$open_ar;
	$dtarray=setdatearray();

	//$cpny =$_REQUEST['cpny'];
	//$mdiv =$_REQUEST['mdiv'];
	//$div  =$_REQUEST['division'];

	//foreach ($open_ar as $n => $v)
	foreach ($oa as $n => $v)
	{
		if ($v==1)
		{
			echo "                                 <td width=\"60px\" align=\"right\"><input class=\"checkboxgry\" type=\"checkbox\" name=\"p".$n."\" value=\"1\" CHECKED title=\"Check this box to enable this period\"></td>\n";
		}
		else
		{
			echo "                                 <td width=\"60px\" align=\"right\"><input class=\"checkboxgry\" type=\"checkbox\" name=\"p".$n."\" value=\"1\" title=\"Check this box to enable this period\"></td>\n";
		}
	}
	
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">\n";
	
	if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332 || $_SESSION['securityid']==1137)
	{
		echo "												<input class=\"buttondkgry\" type=\"submit\" value=\"Set\">\n";
	}
	
	echo "											</td>\n";
	
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">&nbsp</td>\n";
}

function op_enable()
{
	//echo "Op Statement Enable System<br>\n";
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	
	$qrypre0 = "SELECT * FROM op_enable WHERE fiscyr='".$_REQUEST['fiscyr']."' ORDER BY masdiv;";
	$respre0 = mssql_query($qrypre0);
	$nrowpre0= mssql_num_rows($respre0);
	
	if ($nrowpre0 > 0 && $_REQUEST['set'] == 1)
	{
		echo "                  <table class=\"outer\">\n";
		echo "                     <tr>\n";
		echo "                        <td class=\"gray\" colspan=\"15\" align=\"left\"><b>Op Statement Enable System</b></td>\n";
		echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>Code</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>Div</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>1</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>2</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>3</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>4</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>5</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>6</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>7</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>8</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>9</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>10</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>11</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\"><b>12</b></td>\n";
		echo "                        <td class=\"ltgray_und\" align=\"center\">&nbsp</td>\n";
		echo "                     </tr>\n";
	
		//$chktxt="CHECKED";
		while ($rowpre0 = mssql_fetch_array($respre0))
		{
			echo "							<form name=\"enopstateset".$rowpre0['opid']."\" action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "								<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
			echo "								<input type=\"hidden\" name=\"subq\" value=\"openableset\">\n";
			echo "								<input type=\"hidden\" name=\"opid\" value=\"".$rowpre0['opid']."\">\n";
			echo "								<input type=\"hidden\" name=\"fiscyr\" value=\"2006\">\n";
			echo "								<input type=\"hidden\" name=\"set\" value=\"1\">\n";
			echo "                     <tr>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">".str_pad($rowpre0['masid'],3,0,STR_PAD_LEFT)."</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">".str_pad($rowpre0['masdiv'],2,0,STR_PAD_LEFT)."</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p0'],'p0');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p1'],'p1');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p2'],'p2');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p3'],'p3');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p4'],'p4');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p5'],'p5');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p6'],'p6');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p7'],'p7');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p8'],'p8');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p9'],'p9');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p10'],'p10');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			
			op_enable_inchk($rowpre0['p11'],'p11');
			
			echo "								</td>\n";
			echo "                        <td class=\"wh_und\" align=\"center\">\n";
			echo "									<input class=\"buttondkgry\" type=\"submit\" value=\"Set\">\n";
			echo "								</td>\n";
			echo "                     </tr>\n";
			echo "					</form>\n";
		}
		
		echo "                  </table>\n";
	}
}

function op_enable_set()
{
	//print_r($_POST);
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	
	$qry0 = "SELECT p0 FROM op_enable WHERE masid='".$_REQUEST['cpny']."' AND masdiv='".$_REQUEST['division']."' AND fiscyr='".$_REQUEST['fisyr']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	$p0	=0;
	$p1	=0;
	$p2	=0;
	$p3	=0;
	$p4	=0;
	$p5	=0;
	$p6	=0;
	$p7	=0;
	$p8	=0;
	$p9	=0;
	$p10	=0;
	$p11	=0;
	
	if (!empty($_REQUEST['p0']) && $_REQUEST['p0']!=0)
	{
		$p0=1;
	}
	
	if (!empty($_REQUEST['p1']) && $_REQUEST['p1']!=0)
	{
		$p1=1;
	}
	
	if (!empty($_REQUEST['p2']) && $_REQUEST['p2']!=0)
	{
		$p2=1;
	}
	
	if (!empty($_REQUEST['p3']) && $_REQUEST['p3']!=0)
	{
		$p3=1;
	}
	
	if (!empty($_REQUEST['p4']) && $_REQUEST['p4']!=0)
	{
		$p4=1;
	}
	
	if (!empty($_REQUEST['p5']) && $_REQUEST['p5']!=0)
	{
		$p5=1;
	}
	
	if (!empty($_REQUEST['p6']) && $_REQUEST['p6']!=0)
	{
		$p6=1;
	}
	
	if (!empty($_REQUEST['p7']) && $_REQUEST['p7']!=0)
	{
		$p7=1;
	}
	
	if (!empty($_REQUEST['p8']) && $_REQUEST['p8']!=0)
	{
		$p8=1;
	}
	
	if (!empty($_REQUEST['p9']) && $_REQUEST['p9']!=0)
	{
		$p9=1;
	}
	
	if (!empty($_REQUEST['p10']) && $_REQUEST['p10']!=0)
	{
		$p10=1;
	}
	
	if (!empty($_REQUEST['p11']) && $_REQUEST['p11']!=0)
	{
		$p11=1;
	}
	
	//echo $qry0."<br>";
	if ($nrow0 == 0)
	{
		//echo "INSERT<br>";
		$qry  = "INSERT INTO op_enable (";
		$qry .= "masid,masdiv,fiscyr,updtby, ";
		$qry .= "p0,p1,p2,p3, ";
		$qry .= "p4,p5,p6,p7, ";
		$qry .= "p8,p9,p10,p11 ";
		$qry .= ") VALUES ( ";
		$qry .= "'".$_REQUEST['cpny']."','".$_REQUEST['division']."','".$_REQUEST['fisyr']."','".$_SESSION['securityid']."', ";
		$qry .= "'".$p0."','".$p1."','".$p2."','".$p3."', ";
		$qry .= "'".$p4."','".$p5."','".$p6."','".$p7."', ";
		$qry .= "'".$p8."','".$p9."','".$p10."','".$p11."'); ";
		$res = mssql_query($qry);
		//$row = mssql_fetch_array($res);
		
		//echo $qry."<br>";
	}
	else
	{
		//echo "UPDATE<br>";
		$qry  = "UPDATE op_enable SET ";
		$qry .= "p0='".$p0."',p1='".$p1."',p2='".$p2."',p3='".$p3."', ";
		$qry .= "p4='".$p4."',p5='".$p5."',p6='".$p6."',p7='".$p7."', ";
		$qry .= "p8='".$p8."',p9='".$p9."',p10='".$p10."',p11='".$p11."', ";
		$qry .= "updtby='".$_SESSION['securityid']."',lstupdt=getdate() ";
		$qry .= "WHERE masid='".$_REQUEST['cpny']."' AND masdiv='".$_REQUEST['division']."' AND fiscyr='".$_REQUEST['fisyr']."';";
		$res = mssql_query($qry);
		//$row = mssql_fetch_array($res);
		
		//echo $qry."<br>";
	}
	
	opstate();
}

function op_enable_inchk($n,$p)
{
	$inchk="";
	
	if ($n == 1)
	{
		$inchk="CHECKED";
	}
			
	echo "									<input class=\"checkboxwh\" type=\"checkbox\" name=\"".$p."\" value=\"1\" ".$inchk.">\n";
}

function getaltoffices()
{
	$out  =array();
	$qryB = "SELECT altoffices FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
			
	$out	=explode(",",$rowB['altoffices']);
	
	return $out;
}

function user_alt_offices()
{
	$out  = array();
	//$qryB = "SELECT A.oid,B.officeid,B.parentmcode FROM [jest]..[alt_security_levels] as A INNER JOIN [jest]..[offices] as B ON A.oid=B.officeid WHERE A.sid=".$_SESSION['securityid'].";";
	$qryB = "SELECT A.oid,B.officeid,B.pb_code FROM [jest]..[alt_security_levels] as A INNER JOIN [jest]..[offices] as B ON A.oid=B.officeid WHERE A.sid=1129 and B.pb_code!='0' order by B.pb_code;";
	$resB = mssql_query($qryB);
	$nrowB = mssql_num_rows($resB);
	
	/*if ($_SESSION['securityid']==26)
	{
		echo $qryB.'<br />';
	}*/
	
	if ($nrowB > 0)
	{
		while ($rowB = mssql_fetch_array($resB))
		{
			$out[]=$rowB['pb_code'];
		}
	}

	return $out;
}

function companylist()
{
	error_reporting(E_ALL);
	$D_BUG=1;
	$mas_div=0;
	
	$qrypre0 = "SELECT code,pb_code FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 = mssql_query($qrypre0);
	$rowpre0 = mssql_fetch_array($respre0);
	
	$qrypre1 = "SELECT mas_office,mas_div,gmreports,officeid FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$respre1 = mssql_query($qrypre1);
	$rowpre1 = mssql_fetch_array($respre1);
	
	//$altoff_ar=user_alt_offices();
	
	// Logic to account for Job Renumber from 310 to 390
	if ($rowpre1['mas_div']==31 && $_SESSION['securityid']==491)
	{
		$masdivision=39;
	}
	else
	{
		$masdivision=$rowpre1['mas_div'];
	}
	
	if ($rowpre1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to this Area');
	}
	else
	{
		$mas_div=$masdivision;
	}

	$code=str_pad($rowpre0['code'], 3, "0", STR_PAD_LEFT);

	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	$qry  = "SELECT * FROM ZE_Stats..divtocomp WHERE enabled=1 and type <= 3 ORDER by company,division;";
	$res	= mssql_query($qry);
	$nrow	= mssql_num_rows($res);

	if ($_SESSION['securityid']==26)
	{
		//echo $qry."<br>";
		print_r($altoff_ar);
	}
	
	if ($nrow==0)
	{
		echo "Company ID not found. Contact Management. ($code) (".$rowpre1['mas_office'].")";
		exit;
	}
	
	echo "                  <table class=\"outer\">\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray\" align=\"center\" colspan=\"8\"><b>Operating Reports</b></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td class=\"gray_und\" align=\"left\"><b>Company Name</b></td>\n";
	echo "                        <td class=\"gray_und\" align=\"left\"><b>Division Name</b></td>\n";
	echo "                        <td class=\"gray_und\" align=\"center\"><b>MAS</b></td>\n";
	echo "                        <td class=\"gray_und\" align=\"center\"><b>Div</b></td>\n";
	echo "                        <td class=\"gray_und\" align=\"center\"><b>Report</b></td>\n";
	echo "                        <td class=\"gray_und\" align=\"center\"><b>Print</b></td>\n";	
	echo "                        <td class=\"gray_und\" align=\"center\"></td>\n";
	
	if ($_SESSION['officeid'] == 89 && $_SESSION['rlev'] == 9)
	{
		echo "                        <td class=\"gray_und\" align=\"center\"></td>\n";	
	}
	
	echo "                     </tr>\n";
	
	if ($_SESSION['officeid'] == 89 && $_SESSION['rlev'] == 9)
	{
		echo "                     <tr>\n";
		echo "                        <td class=\"white\" align=\"left\" colspan=\"4\">BHNMI Corporate</td>\n";
		echo "							<form name=\"cib\" target=\"_top\" method=\"post\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "							<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
		echo "							<input type=\"hidden\" name=\"beyondyearend\" value=\"1\">\n";
		echo "                        <td class=\"white\" align=\"right\">\n";
		echo "								<select name=\"subq\" onChange=\"this.form.submit();\">\n";
		echo "									<option value=\"NA\">Select...</option>\n";
		
		//if ($_SESSION['securityid']==SYS_ADMIN)
		//{
			echo "									<option value=\"cib_admin\">Cash in Bank</option>\n";
		//}
		
		echo "									<option value=\"nq_admin\">Net Quick</option>\n";
		
		if ($_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==MTRX_ADMIN)
		{
			echo "										<option value=\"NA\">-------------</option>\n";
			echo "										<option value=\"officeconfig\">Company/Div Config</option>\n";
			echo "										<option value=\"os_admin\">OpState Admin</option>\n";
			echo "										<option value=\"opuselog\">OpReport Use Log</option>\n";
		}
		
		echo "								</select>\n";
		echo "							</td>\n";
		echo "                        <td class=\"white\" align=\"center\">\n";
		echo "									<input class=\"checkboxwh\" type=\"checkbox\" name=\"print\" value=\"1\">\n";
		echo "								</td>\n";
		echo "                        <td class=\"white\" align=\"right\">\n";
		echo "									<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View\">\n";
		echo "								</td>\n";
		echo "                        <td class=\"white\" align=\"right\">\n";
		echo "								</td>\n";
		echo "							</form>\n";
		echo "                     </tr>\n";
		echo "                     <tr>\n";
		echo "                        <td class=\"gray\" align=\"left\" colspan=\"8\">&nbsp</td>\n";
		echo "                     </tr>\n";
	}

	$ccnt=0;
	while ($row=mssql_fetch_array($res))
	{
		//if (!in_array($row['company'],$c_exar) && !in_array($row['division'],$d_exar))
		//{
			$qryA = "SELECT CompanyName,CompanyCode FROM MAS_SYSTEM..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$row['company']."');";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);
			$nrowA = mssql_num_rows($resA);
			
			/*if ($_SESSION['securityid']==26)
			{
				echo $qryA."<br>";
			}*/
			
			$qryB = "SELECT Description,Division FROM MAS_".$row['company']."..ARB_DivisionMasterfile WHERE Division='".$row['division']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			
			//if ($nrowA == 1)
			//{
				//if ($row[1] == $masdivision || $_SESSION['officeid']==89)
				if ($row[1] == $masdivision	|| in_array($rowpre1['officeid'],$_SESSION['admin_offs']))
				{
					$ccnt++;	
					if ($ccnt%2)
					{
						$tbg = "white";
						$tbgc= "checkboxwh";
					}
					else
					{
						$tbg = "gray";
						$tbgc= "checkboxgry";
					}
					
					echo "                     <tr>\n";
					echo "                        <td class=\"".$tbg."\" align=\"left\">".$rowA['CompanyName']."</td>\n";
					echo "                        <td class=\"".$tbg."\" align=\"left\">".$rowB['Description']."</td>\n";
					echo "                        <td class=\"".$tbg."\" align=\"center\">".$row['company']."</td>\n";
					echo "                        <td class=\"".$tbg."\" align=\"center\">".$row['division']."</td>\n";
					
					if ($_SESSION['rlev'] >=6)
					{
						echo "                       <form name=\"opstate\" action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
						echo "						 <input type=\"hidden\" name=\"action\" value=\"reports\">\n";
						echo "						 <input type=\"hidden\" name=\"call\" value=\"operating\">\n";
						echo "                       <input type=\"hidden\" name=\"cpny\" value=\"".$row['company']."\">\n";
						echo "                       <input type=\"hidden\" name=\"mdiv\" value=\"1\">\n";
						echo "                       <input type=\"hidden\" name=\"division\" value=\"".$row['division']."\">\n";
						echo "                      <td class=\"".$tbg."\">\n";
						echo "							<select name=\"subq\" onChange=\"this.form.submit();\">\n";
						
						if ($row['opstate']==1)
						{
							echo "										<option value=\"preopstate\">Operating Statement</option>\n";
						}
						
						if ($row['openjobs']==1)
						{
							echo "										<option value=\"ojreport\">Open Jobs</option>\n";
						}
						
						if ($row['closedjobs']==1)
						{
							echo "										<option value=\"cjreport\">Closed Jobs</option>\n";
						}
						
						if ($row['huntenperc']==1)
						{
							echo "										<option value=\"110report\">110% Report</option>\n";
						}
						
						if ($row['cib']==1)
						{
							echo "										<option value=\"cib_office\">Cash in Bank</option>\n";
						}
						
						if ($_SESSION['officeid']==89)
						{
							echo "										<option value=\"nq\">Net Quick *</option>\n";
						}
						
						//if ($_SESSION['officeid']==89)
						//{
							echo "										<option value=\"arreport\">AR Report</option>\n";
						//}
						
						echo "								</select>\n";
						echo "							</td>\n";
						echo "                      	<td class=\"".$tbg."\" align=\"center\">\n";
						echo "								<input class=\"".$tbgc."\" type=\"checkbox\" name=\"print\" value=\"1\">\n";
						echo "							</td>\n";
						echo "                      	<td class=\"".$tbg."\">\n";
						echo "								<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View\">\n";
						echo "							</td>\n";
						echo "                       </form>\n";
						
						if ($_SESSION['rlev'] >=9 && $_SESSION['officeid']==89)
						{
							echo "         					<form name=\"wc_rep\" action=\"export/wcexport.php\" method=\"post\" target=\"_new\">\n";
							//echo "                       <form name=\"wc_rep\" action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
							echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
							echo "								<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
							echo "                       	<input type=\"hidden\" name=\"subq\" value=\"wc_report\">\n";
							echo "                       	<input type=\"hidden\" name=\"cpny\" value=\"".$row['company']."\">\n";
							echo "                       	<input type=\"hidden\" name=\"mdiv\" value=\"1\">\n";
							echo "                      	<input type=\"hidden\" name=\"division\" value=\"".$row['division']."\">\n";
							echo "                        <td class=\"wh_und\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"WC Audit\" title=\"Reflects what has been posted to MAS, not a current bank balance\"></td>\n";
							echo "                       </form>\n";
						}
					}
					echo "                     </tr>\n";
				}
			//}
		//}
	}
	
	$hostname = "192.168.100.45";
	$username = "jestadmin";
	$password = "into99black";
	$dbname = "jest";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
}

function revsign($num)
{
	$fnum=$num*-1;
	return $fnum;
}

function fixfloat($num)
{
	//$fnum=sprintf("%01.2u",$num*100);
	//$fnum=sprintf("%u",$num*100);
	$fnum=$num*100;
	return $fnum;
}

function getcpnytype()
{
	$cpny=$_REQUEST['cpny'];

	$retext=substr($row[0],4);

	$qry     = "SELECT CompanyName,CompanyCode FROM MAS_".$cpny."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'$retext');";
	$res     = mssql_query($qry);
	$row     = mssql_fetch_row($res);
	$numrows = mssql_num_rows($row);

	if ($numrows > 1)
	{
		// Multidivision Companies
		preopstate();
	}
	else
	{
		// Single Division Companies
		opstate();
	}
}

function vendorlist()
{
	$cpny=$_REQUEST['cpny'];
	$mdiv=$_REQUEST['mdiv'];
	$v_ar=array();

	$retext=substr($_REQUEST['cpny'],4);

	if ($mdiv==1)
	{
		$retextdiv=substr($_REQUEST['division'],0,2);
	}

	$qryPRE   = "SELECT  ";
	$qryPRE  .= "	a.VendorNumber,b.ML_UDF_APV_WC_END_DATE,b.ML_UDF_APV_GEN_END_DATE ";
	$qryPRE  .= "from  ";
	$qryPRE  .= "	$cpny..AP1_VendorMaster AS a  ";
	$qryPRE  .= "INNER JOIN  ";
	$qryPRE  .= "	$cpny..AP_90_UDF_AP_Vendor AS b  ";
	$qryPRE  .= "ON  ";
	$qryPRE  .= "	a.VendorNumber=b.VendorNumber  ";

	if ($mdiv==1)
	{
		$qryPRE  .= "WHERE  ";
		$qryPRE  .= "	a.Division=convert(varchar,'".$retextdiv."')   ";
	}

	$qryPRE  .= " ORDER BY a.VendorNumber;  ";

	$resPRE   = mssql_query($qryPRE);
	$nrowsPRE = mssql_num_rows($resPRE);

	//echo $qryPRE."<br>";

	if ($nrowsPRE > 0)
	{
		$vtext="";
		while ($rowPRE=mssql_fetch_array($resPRE))
		{
			$wdatePRE	=dateformat($rowPRE['ML_UDF_APV_WC_END_DATE']);
			$gdatePRE	=dateformat($rowPRE['ML_UDF_APV_GEN_END_DATE']);
			$tadjPRE		=time()+5184000; // 60 Day Forward from Current

			if ($wdatePRE)
			{
				$wtsPRE	=strtotime($rowPRE['ML_UDF_APV_WC_END_DATE']);
				if ($wtsPRE > time() && $wtsPRE < $tadjPRE)
				{
					//echo $rowPRE['VendorNumber']."<br>";
					$v_ar[]=$rowPRE['VendorNumber'];
				}
			}

			if ($gdatePRE)
			{
				$gtsPRE=strtotime($rowPRE['ML_UDF_APV_GEN_END_DATE']);
				if ($gtsPRE > time() && $gtsPRE < $tadjPRE)
				{
					//echo $rowPRE['VendorNumber']."<br>";
					$v_ar[]=$rowPRE['VendorNumber'];
				}
			}
		}

		//print_r($v_ar);
		$uv_ar=array_unique($v_ar);
		//print_r($uv_ar)."<br>";

		foreach ($uv_ar as $n=>$v)
		{
			$vtext=$vtext." <a href=\"#".$v."\">".$v."</a>";
			//$vtext=$vtext." ".$v." ";
		}

		//echo $vtext."<br>";
	}

	$qry   = "SELECT  ";
	$qry  .= "	a.Division,a.VendorNumber,a.VendorName,a.AddressLine1,a.AddressLine2,a.City,a.State,a.ZipCode, ";
	$qry  .= "	a.TemporaryVendor,a.HoldFlag,a.MasterFileComment,a._1099VendorType,a.LastPaymentDate,a.LastCheckAmount, ";
	$qry  .= "	a.VendorRef,a.Default1099Box,a.AddressLine3,b.ML_UDF_APV_WC_END_DATE,b.ML_UDF_APV_GEN_END_DATE, ";
	$qry  .= "	b.ML_UDF_APV_WC_POLICY_NUM,b.ML_UDF_APV_GEN_POLICY_NUM ";
	$qry  .= "from  ";
	$qry  .= "	MAS_".$cpny."..AP1_VendorMaster AS a  ";
	$qry  .= "INNER JOIN  ";
	$qry  .= "	MAS_".$cpny."..AP_90_UDF_AP_Vendor AS b  ";
	$qry  .= "ON  ";
	$qry  .= "	a.VendorNumber=b.VendorNumber  ";

	if ($mdiv==1)
	{
		$qry  .= "WHERE  ";
		$qry  .= "	a.Division=convert(varchar,'".$retextdiv."')   ";
	}

	$qry  .= " ORDER BY a.VendorNumber;  ";

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	$qryA = "SELECT CompanyName,CompanyCode FROM MAS_".$cpny."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'$retext');";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);

	$nrcnt=0;

	if ($nrows > 0)
	{
		echo "                  <table>\n";
		echo "                     <tr>\n";

		if ($mdiv==1)
		{
			echo "                        <td align=\"left\" colspan=\"2\"><b>$rowA[0] ($retextdiv) (Vendor Report)</b></td>\n";
		}
		else
		{
			echo "                        <td align=\"left\" colspan=\"2\"><b>$rowA[0] (Vendor Report)</b></td>\n";
		}

		/*
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
		echo "                        <td align=\"right\"><input type=\"hidden\" name=\"#Top\"><input class=\"buttondkgry\" type=\"submit\" value=\"Reporting Main Page\"></td>\n";
		echo "                        </form>\n";
		*/
		echo "                        <td align=\"right\"></td>\n";
		echo "                     </tr>\n";
		//echo "                        <form>\n";

		if ($nrowsPRE > 0)
		{
			echo "			<tr>\n";
			echo "				<td align=\"left\" colspan=\"3\">Vendor Policy Within 60 Day Window: </td>\n";
			echo "			</tr>\n";
			echo "			<tr>\n";
			echo "				<td align=\"left\" colspan=\"3\">\n";
			echo 					$vtext;
			echo "				</td>\n";
			echo "			</tr>\n";
		}

		while ($row=mssql_fetch_row($res))
		{
			$getext='';
			$wetext='';
			if ($row[9]!='N')
			{
				$tvendor="<font color=\"red\"><b>**HOLD**</b></font>";
			}
			else
			{
				$tvendor="&nbsp";
			}

			if ($row[11]=='N')
			{
				$vtype="None";
			}
			elseif ($row[11]=='B')
			{
				$vtype="Business";
			}
			elseif ($row[11]=='I')
			{
				$vtype="Individual";
			}
			else
			{
				$vtype="$row[11]";
			}

			if ($row[8]=='N')
			{
				$fpmtamt	=number_format($row[13], 2, '.', '');
				$fdate	=dateformat($row[12]);
				$wdate	=dateformat($row[17]);
				$gdate	=dateformat($row[18]);
				$tadj		=time()+5184000; // 60 Day Forward from Current

				if ($wdate)
				{
					$wts		=strtotime($row[17]);
					if ($wts > time() && $wts < $tadj)
					{
						$wetext='<b>Within 60 days</b>';
					}
				}

				if ($gdate)
				{
					$gts=strtotime($row[18]);
					if ($gts > time() && $gts < $tadj)
					{
						$getext='<b>Within 60 days</b>';
					}
				}

				echo "                     <tr>\n";
				echo "                        <td align=\"left\" colspan=\"3\"><hr width=\"100%\"></td>\n";
				echo "                     </tr>\n";
				//echo "			 <input type=\"hidden\" name=\"#".$row[1]."\">\n";
				//echo "			 <input type=\"hidden\" name=\"#1\">\n";
				echo "                     <tr>\n";
				echo "                        <td align=\"left\"><input class=\"bbox\" type=\"text\" value=\"$row[0]-$row[1]\" size=\"12\"><input type=\"hidden\" name=\"#".$row[1]."\"></td>\n";
				echo "                        <td align=\"left\"><input class=\"bbox\" type=\"text\" value=\"$row[2]\" size=\"35\"></td>\n";
				echo "                        <td align=\"right\">Comment: <input class=\"bbox\" type=\"text\" value=\"$row[10]\" size=\"20\"></td>\n";
				echo "                     </tr>\n";
				echo "                     <tr>\n";
				echo "                        <td align=\"left\">$tvendor</td>\n";
				echo "                        <td align=\"left\"><input class=\"bbox\" type=\"text\" value=\"$row[3]\" size=\"35\"></td>\n";
				echo "                        <td align=\"right\">1099 Box #: <input class=\"bbox\" type=\"text\" value=\"$row[15]\" size=\"1\"></td>\n";
				echo "                     </tr>\n";
				echo "                     <tr>\n";
				echo "                        <td align=\"left\">ACCT#:</td>\n";
				echo "                        <td align=\"left\"><input class=\"bbox\" type=\"text\" value=\"$row[4]\" size=\"35\"></td>\n";
				echo "                        <td align=\"right\">1099 Vendor Box: <input class=\"bbox\" type=\"text\" value=\"$vtype\" size=\"10\"></td>\n";
				echo "                     </tr>\n";
				echo "                     <tr>\n";
				echo "                        <td align=\"left\"><input class=\"bbox\" type=\"text\" value=\"$row[14]\" size=\"12\"></td>\n";
				echo "                        <td align=\"left\"><input class=\"bbox\" type=\"text\" value=\"$row[16]\" size=\"35\"></td>\n";
				echo "                        <td align=\"right\">Last Pmt Dt: <input class=\"bbox\" type=\"text\" value=\"$fdate\" size=\"15\"></td>\n";
				echo "                     </tr>\n";
				echo "                     <tr>\n";
				echo "                        <td align=\"left\">&nbsp</td>\n";
				echo "                        <td align=\"left\"><input class=\"bbox\" type=\"text\" value=\"$row[5] $row[6] $row[7]\" size=\"35\"></td>\n";
				echo "                        <td align=\"right\">Last Pmt Amt: <input class=\"bbox\" type=\"text\" value=\"\$$fpmtamt\" size=\"15\"></td>\n";
				echo "                     </tr>\n";
				echo "                     <tr>\n";
				echo "                        <td align=\"right\">".$getext."</td>\n";
				echo "                        <td align=\"right\">Gen Policy #: <input class=\"bbox\" type=\"text\" value=\"$row[20]\" size=\"15\"></td>\n";
				echo "                        <td align=\"right\">Gen End Date: <input class=\"bbox\" type=\"text\" value=\"$gdate\" size=\"15\"></td>\n";
				echo "                     </tr>\n";
				echo "                     <tr>\n";
				echo "                        <td align=\"right\">".$wetext."</td>\n";
				//echo "                        <td align=\"right\">(".time().") ($tadj) ($wts) </td>\n";
				echo "                        <td align=\"right\">WC Policy #: <input class=\"bbox\" type=\"text\" value=\"$row[19]\" size=\"15\"></td>\n";
				echo "                        <td align=\"right\">WC Ins End Date: <input class=\"bbox\" type=\"text\" value=\"$wdate\" size=\"15\"></td>\n";
				echo "                     </tr>\n";
				echo "                     <tr>\n";
				echo "                        <td align=\"right\" colspan=\"3\"><a href=\"#Top\">Top</a></td>\n";
				echo "                     </tr>\n";
				//echo "                     </form>\n";
			}
			$nrcnt++;
		}
		echo "                     <tr>\n";
		//echo "                     </form>\n";
		if ($mdiv==1)
		{
			echo "                        <td align=\"left\" colspan=\"2\"><b>$rowA[0] ($retextdiv) (Vendor Report)</b></td>\n";
		}
		else
		{
			echo "                        <td align=\"left\" colspan=\"2\"><b>$rowA[0] (Vendor Report)</b></td>\n";
		}

		/*
		echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
		echo "                        <td align=\"right\"><input class=\"buttondkgry\" type=\"submit\" value=\"Reporting Main Page\"></td>\n";
		echo "                        </form>\n";
		*/
		echo "                        <td align=\"right\"></td>\n";
		echo "                     </tr>\n";
	}
	else
	{
		//echo "                     <form action=\"".$_SERVER['PHP_SELF']."\" target=\"_top\" method=\"post\">\n";
		echo "                     <tr>\n";
		if ($mdiv==1)
		{
			echo "                        <td align=\"left\"><b>No Vendor Records for $rowA[0] ($retextdiv) Company/Division</b></td>\n";
		}
		else
		{
			echo "                        <td align=\"left\"><b>No Vendor Records for $rowA[0] Company</b></td>\n";
		}
		//echo "                        <td align=\"right\"><input class=\"buttondkgry\" type=\"submit\" value=\"Reporting Main Page\"></td>\n";
		echo "                        <td align=\"right\"></td>\n";
		//echo "                     </form>\n";
		echo "                     </tr>\n";
	}
	echo "                     <tr>\n";
	echo "                        <td align=\"left\">&nbsp</td>\n";
	echo "                        <td align=\"left\"></td>\n";
	echo "                        <td align=\"center\">$nrcnt Vendors</td>\n";
	echo "                     </tr>\n";
}

function getcurrfisyr()
{
	$cpny=$_REQUEST['cpny'];

	$qry  = "SELECT ";
	$qry .= "CurrentJCFiscalYr";
	$qry .= " FROM MAS_".$cpny."..JC0_Parameters WHERE CurrentJCFiscalYr IS NOT NULL;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);

	//echo $qry."<br>";

	if (!isset($_REQUEST['fisyr']))
	{
		$curryr=$row[0];
	}
	else
	{
		$curryr=$_REQUEST['fisyr'];
	}

	//$curryr=$row[0];
	return $curryr;
}

function getcurrmonth()
{
	$cpny=$_REQUEST['cpny'];

	$qry  = "SELECT ";
	$qry .= "CurrentPeriod";
	$qry .= " FROM MAS_".$cpny."..JC0_Parameters WHERE CurrentJCFiscalYr IS NOT NULL;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);

	if (!isset($_REQUEST['prd']))
	{
		$currmonth=$row[0];
	}
	else
	{
		$currmonth=$_REQUEST['prd'];
	}
	return $currmonth;
}

function monthselect()
{
	//$currfisyr	=getcurrfisyr();
	$cpny=$_REQUEST['cpny'];
	
	$qry  = "SELECT ";
	$qry .= "CurrentJCFiscalYr";
	$qry .= " FROM MAS_".$cpny."..JC0_Parameters WHERE CurrentJCFiscalYr IS NOT NULL;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);
	
	/*
	//echo getcurrfisyr();
	if (!empty($_REQUEST['fisyr']) && $row[0] > $_REQUEST['fisyr'])
	{
		$x				=12;
	}
	else
	{
		$x				=prd_pools_dug();
	}
	*/
	//echo $ppd;
	$x				=12;
	echo "<select name=\"prd\">\n";

	//$x=0;
	while ($x >= 1)
	{
		if ($x == $_REQUEST['prd'])
		{
			echo "   <option value=\"".$x."\" SELECTED>".$x."</option>\n";
		}
		else
		{
			echo "   <option value=\"".$x."\" DISABLED>".$x."</option>\n";
		}
		$x--;
	}
	
	echo "</select>\n";
}

function yearselect()
{
	$cpny=$_REQUEST['cpny'];
	$currfisyr=getcurrfisyr();
	
	$qry  = "SELECT ";
	$qry .= "FiscalYr";
	$qry .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterfile WHERE FiscalYr IS NOT NULL;";
	$res  = mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	if ($nrow > 0)
	{
		//echo "<b>Fiscal Year:</b> ";
		echo "<select name=\"fisyr\">\n";
		
		while ($row  = mssql_fetch_array($res))
		{
			if ($row['FiscalYr'] >= 2003)
			{
				if ($currfisyr==$row['FiscalYr'])
				{
					echo "   <option value=\"".$row['FiscalYr']."\" SELECTED>".$row['FiscalYr']."</option>\n";
				}
				else
				{
					echo "   <option value=\"".$row['FiscalYr']."\">".$row['FiscalYr']."</option>\n";
				}
			}
		}
				
		echo "</select>\n";
		echo "<input type=\"hidden\" name=\"prd\" value=\"12\">\n";
	}
}

function yearselect_compare()
{
	$cpny=$_REQUEST['cpny'];
	$currfisyr=(getcurrfisyr()-1);
	
	$qry  = "SELECT ";
	$qry .= "FiscalYr";
	$qry .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterfile WHERE FiscalYr IS NOT NULL;";
	$res  = mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	if ($nrow > 0)
	{
		//echo "<b>Fiscal Year:</b> ";
		echo "<select name=\"fisyr\">\n";
		
		while ($row  = mssql_fetch_array($res))
		{
			if ($row['FiscalYr'] >= 2003)
			{
				if ($currfisyr==$row['FiscalYr'])
				{
					echo "   <option value=\"".$row['FiscalYr']."\" SELECTED>".$row['FiscalYr']."</option>\n";
				}
				else
				{
					echo "   <option value=\"".$row['FiscalYr']."\">".$row['FiscalYr']."</option>\n";
				}
			}
		}
				
		echo "</select>\n";
		echo "<input type=\"hidden\" name=\"prd\" value=\"12\">\n";
	}
}

function monthyearselect()
{
	$cpny=$_REQUEST['cpny'];
	$currfisyr=getcurrfisyr();
	$preyr=$currfisyr-1;
	$postyr=$currfisyr+1;
	$yrarr=array(0=>$preyr,1=>$currfisyr,2=>$postyr);
	sort($yrarr);
	
	$qry  = "SELECT ";
	$qry .= "CurrentJCFiscalYr";
	$qry .= " FROM MAS_".$cpny."..JC0_Parameters WHERE CurrentJCFiscalYr IS NOT NULL;";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);
	
	/*
	if (!empty($_REQUEST['fisyr']) && $row[0] > $_REQUEST['fisyr'])
	{
		$x				=12;
	}
	else
	{
		$x				=prd_pools_dug();
	}
	*/
	$x				=12;
	echo "<b>Fiscal Year:</b>";
	echo "<select name=\"fisyr\">\n";

	foreach ($yrarr as $n=>$v)
	{
		if ($v==$currfisyr)
		{
			echo "   <option value=\"$v\" SELECTED>$v</option>\n";
		}
		else
		{
			echo "   <option value=\"$v\">$v</option>\n";
		}
	}
	echo "</select>\n";
	echo "<input type=\"hidden\" name=\"prd\" value=\"12\">\n";
}

function setdatearray()
{
	$cpny=$_REQUEST['cpny'];
	$currfisyr=getcurrfisyr();
	$precurrfisyr=$currfisyr-1;

	$dtarray	=array();	
	$tdtarray	=array();

	$qry  = "SELECT ";
	$qry .= "Period1EndingDate,";
	$qry .= "Period2EndingDate,";
	$qry .= "Period3EndingDate,";
	$qry .= "Period4EndingDate,";
	$qry .= "Period5EndingDate,";
	$qry .= "Period6EndingDate,";
	$qry .= "Period7EndingDate,";
	$qry .= "Period8EndingDate,";
	$qry .= "Period9EndingDate,";
	$qry .= "Period10EndingDate,";
	$qry .= "Period11EndingDate,";
	$qry .= "Period12EndingDate";
	$qry .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$currfisyr."';";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);
	$nrows = mssql_num_rows($res);

	$qryB  = "SELECT ";
	$qryB .= "Period1EndingDate,";
	$qryB .= "Period2EndingDate,";
	$qryB .= "Period3EndingDate,";
	$qryB .= "Period4EndingDate,";
	$qryB .= "Period5EndingDate,";
	$qryB .= "Period6EndingDate,";
	$qryB .= "Period7EndingDate,";
	$qryB .= "Period8EndingDate,";
	$qryB .= "Period9EndingDate,";
	$qryB .= "Period10EndingDate,";
	$qryB .= "Period11EndingDate,";
	$qryB .= "Period12EndingDate";
	$qryB .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$precurrfisyr."';";
	$resB  = mssql_query($qryB);
	$rowB  = mssql_fetch_row($resB);
	
	for ($p=11;$p > 0;$p--)
	{
		if (strtotime($rowB[$p]) > strtotime('1/1/2000'))
		{
			$pprefdat[]=$rowB[$p];
		}
	}

	if ($nrows != 1)
	{
		echo "                        <table>\n";
		echo "                           <tr>\n";
		echo "                              <td align=\"left\"><b>No Fiscal Year for ".$currfisyr.". Check Fiscal Year Maintenance in MAS</b></td>\n";
		echo "                              <td align=\"right\"></td>\n";
		echo "                           </tr>\n";
		echo "                        </table>\n";
		exit;
	}
	else
	{
		$prefdat=date("m/d/y",strtotime($pprefdat[0]) + 87400);
		$r=0;
		foreach ($row as $n => $v)
		{
			if (!empty($v))
			{
				if ($r==0)
				{
					$tdtarray[date("M",strtotime($v))]=array($prefdat,date("m/d/y",strtotime($v)));
					$r++;
				}
				else
				{
					$tdtarray[date("M",strtotime($v))]=array(date("m/d/y",(strtotime($row[($r-1)]) + 87400)),date("m/d/y",strtotime($v)));
					$r++;
				}
			}
		}
	}

	if ($cpny=="MAS_420" && $precurrfisyr==2005)
	{
		$dtarray['Aug'][0]="07/31/2005";
	}

	$dtarray=$tdtarray;
	return $dtarray;
}


function setdatearrayold()
{
	//global $dtarray;

	$cpny=$_REQUEST['cpny'];
	$currfisyr=getcurrfisyr();
	$precurrfisyr=$currfisyr-1;

	$qry  = "SELECT ";
	$qry .= "Period1EndingDate,";
	$qry .= "Period2EndingDate,";
	$qry .= "Period3EndingDate,";
	$qry .= "Period4EndingDate,";
	$qry .= "Period5EndingDate,";
	$qry .= "Period6EndingDate,";
	$qry .= "Period7EndingDate,";
	$qry .= "Period8EndingDate,";
	$qry .= "Period9EndingDate,";
	$qry .= "Period10EndingDate,";
	$qry .= "Period11EndingDate,";
	$qry .= "Period12EndingDate";
	$qry .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$currfisyr."';";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);
	$nrows = mssql_num_rows($res);

	//echo $qry."<br>";

	//$ar_idx =12;
	$qryB  = "SELECT ";
	$qryB .= "Period1EndingDate,";
	$qryB .= "Period2EndingDate,";
	$qryB .= "Period3EndingDate,";
	$qryB .= "Period4EndingDate,";
	$qryB .= "Period5EndingDate,";
	$qryB .= "Period6EndingDate,";
	$qryB .= "Period7EndingDate,";
	$qryB .= "Period8EndingDate,";
	$qryB .= "Period9EndingDate,";
	$qryB .= "Period10EndingDate,";
	$qryB .= "Period11EndingDate,";
	$qryB .= "Period12EndingDate";
	$qryB .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$precurrfisyr."';";
	$resB  = mssql_query($qryB);
	$rowB  = mssql_fetch_row($resB);
	
	// Reduction Code to detect abnormal Fiscal Year Config
	$ar_idx =count($rowB);
	for ($i=1;$ar_idx >= $i;$ar_idx--)
	{
		if (!empty($rowB[$ar_idx]))
		{
			$rowB_ar[]=$rowB[$ar_idx];
		}
	}
	
	$rowBval  = $rowB_ar[0];
	// End Reduction Code
	//echo $qryB."<br>";

	if ($nrows < 1)
	{
		echo "                        <table>\n";
		echo "                           <tr>\n";
		echo "                              <td align=\"left\"><b>No Fiscal Year for ".$currfisyr.". Check Fiscal Year Maintenance</b></td>\n";
		echo "                              <td align=\"right\"></td>\n";
		echo "                           </tr>\n";
		echo "                        </table>\n";
		exit;
	}

	//$preyrdm=substr($rowB[11],0,3);
	//$preyrdd=substr($rowB[11],4,2);
	//$preyrdy=substr($rowB[11],7,4);
	
	/*
	$preyrmT=date("M",strtotime($rowB[11]));
	$preyrdm=date("m",strtotime($rowB[11]));
	$preyrdd=date("d",strtotime($rowB[11]));
	$preyrdy=date("Y",strtotime($rowB[11]));
	$prefdat=date("m/d/Y",strtotime($rowB[11]) + 86400);
	*/
	$preyrmT=date("M",strtotime($rowBval));
	$preyrdm=date("m",strtotime($rowBval));
	$preyrdd=date("d",strtotime($rowBval));
	$preyrdy=date("Y",strtotime($rowBval));
	$prefdat=date("m/d/Y",strtotime($rowBval) + 86400);
	//echo "PRE: ".date("m/d/Y",strtotime($rowB[11]) + 86400)."<br>";

	foreach ($row as $n => $v)
	{
		$premT	=date("M",strtotime($v));
		$dm		=date("m",strtotime($v));
		$predd	=date("d",strtotime($v));
		$predy	=date("Y",strtotime($v));
		//echo $dm."<br>";
		//echo "PST: ".date("m/d/Y",strtotime($v))."<br>";
		
		if (!isset($postdm))
		{
			if ($dm=='01')
			{
				//if ($predd=='31')
				if ($preyrdd=='31')
				{
					$postdm=$dm;
					$postdd='01';
					$postdy=$predy;
					$numt=1;
				}
				else
				{
					$postdm='12';
					$postdd=$preyrdd+1;
					$postdy=$preyrdy;
					$numt=2;
				}
			}
			elseif ($dm=='08')
			{
				if ($predd=='31')
				{
					$postdm=$dm+1;
					$postdd='01';
					$postdy=$predy;
					$numt=3;
					//echo "NOT HIT<br>";
				}
				else
				{
					$postdm=$dm-1;
					$postdd=$preyrdd+1;
					$postdy=$preyrdy;
					$numt=4;
					//echo "HIT<br>";
				}
				//echo $numt."<br>";
			}
			elseif ($dm=='09')
			{
				if ($predd=='30')
				{
					$postdm=$dm;
					$postdd='01';
					$postdy=$predy;
					$numt=5;
				}
				else
				{
					$postdm=$dm-1;
					$postdd=$preyrdd+1;
					$postdy=$preyrdy;
					$numt=6;
				}
			}
		}

		if(!isset($dtarray))
		{
			//echo "HIT<br>";
			$dtarray=array("$premT" => array (0=>$prefdat, 1=>"$dm/$predd/$predy"));
			//$dtarray=array("$premT" => array (0=>"$postdm/$postdd/$postdy", 1=>"$dm/$predd/$predy"));
		}
		else
		{
			$dtarray[$premT]=array (0=>"$postdm/$postdd/$postdy", 1=>"$dm/$predd/$predy");
			//$dtarray[$premT]=array (0=>"$postdm/$postdd/$postdy", 1=>"$dm/$predd/$predy");
		}

		$postdm=$dm;
		$postdd=$predd;
		$postdy=$predy;

		if ($postdm=='12'&&$postdd=='31')
		{
			$postdm='01';
			$postdd='01';
			$postdy=$predy+1;
			$numt=5;
		}
		elseif ($postdm=='02'&&$postdd=='28')
		{
			$postdm=$dm+1;
			$postdd='01';
			$numt=6;
		}
		elseif ($postdm=='02'&&$postdd=='29')
		{
			$postdm=$dm+1;
			$postdd='01';
			$numt=7;
		}
		elseif ($postdm=='04'&&$postdd=='30')
		{
			$postdm=$dm+1;
			$postdd='01';
			$numt=8;
		}
		elseif ($postdm=='06'&&$postdd=='30')
		{
			$postdm=$dm+1;
			$postdd='01';
			$numt=9;
		}
		elseif ($postdm=='08'&&$postdd=='31')
		{
			$postdm=$dm+1;
			$postdd='01';
			$numt=101;
			//echo "HIT 8<br>";
		}
		elseif ($postdm=='09'&&$postdd=='30')
		{
			$postdm=$dm+1;
			$postdd='01';
			$numt=10;
		}
		elseif ($postdm=='11'&&$postdd=='30')
		{
			$postdm=$dm+1;
			$postdd='01';
			$numt=11;
		}
		elseif ($postdd=='31')
		{
			$postdm=$dm+1;
			$postdd='01';
			$numt=12;
		}
		else
		{
			$postdd=$postdd+1;
			$numt=13;
			//echo "HIT Aug<br>";
		}
	}

	//echo $cpny."<br>";
	//echo $dtarray['Aug'][0]."<br>";
	//echo $precurrfisyr."<br>";
	//show_array_vars($dtarray);

	if ($cpny=="MAS_420" && $precurrfisyr==2005)
	{
		$dtarray['Aug'][0]="07/31/2005";
	}

	//echo $dtarray['Aug'][0]."<br>";
	//show_array_vars($dtarray);

	return $dtarray;
}

function selectdatedisplay_compare($ymod)
{
	global $dterror;

	$cpny=$_REQUEST['cpny'];
	
	if (empty($ymod))
	{
		$ymod=0;
	}
	
	//$currfisyr=(getcurrfisyr() - $ymod);

	$qry  = "SELECT ";
	$qry .= " FiscalYr ";
	$qry .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr >= '2004';";
	$res  = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//echo $qry."<br>";
	if ($nrows == 0)
	{
		$dterror=1;
		exit;
	}
	else
	{
		//echo "<select name=\"drange".$ymod."\">\n";
		echo "<select name=\"drange".$ymod."\">\n";
		
		while($row  = mssql_fetch_array($res))
		{
			$qryA  = "SELECT ";
			$qryA .= "Period1EndingDate,";
			$qryA .= "Period2EndingDate,";
			$qryA .= "Period3EndingDate,";
			$qryA .= "Period4EndingDate,";
			$qryA .= "Period5EndingDate,";
			$qryA .= "Period6EndingDate,";
			$qryA .= "Period7EndingDate,";
			$qryA .= "Period8EndingDate,";
			$qryA .= "Period9EndingDate,";
			$qryA .= "Period10EndingDate,";
			$qryA .= "Period11EndingDate,";
			$qryA .= "Period12EndingDate,";
			$qryA .= "FiscalYr ";
			$qryA .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$row['FiscalYr']."';";
			$resA  = mssql_query($qryA);
			$rowA  = mssql_fetch_array($resA);
			
			$precurrfisyr=$rowA['FiscalYr']-1;
			
			$qryB  = "SELECT ";
			$qryB .= "Period12EndingDate";
			$qryB .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$precurrfisyr."';";
			$resB  = mssql_query($qryB);
			$rowB  = mssql_fetch_array($resB);
	
			//echo $qryB."<br>";
			//echo "<option>".$rowB['Period12EndingDate'].":".date("m/d/Y",strtotime($rowA['Period1EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowB['Period12EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period1EndingDate']))."\">".date("M",strtotime($rowA['Period1EndingDate']))." ".date("y",strtotime($rowA['Period1EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period1EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period2EndingDate']))."\">".date("M",strtotime($rowA['Period2EndingDate']))." ".date("y",strtotime($rowA['Period2EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period2EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period3EndingDate']))."\">".date("M",strtotime($rowA['Period3EndingDate']))." ".date("y",strtotime($rowA['Period3EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period3EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period4EndingDate']))."\">".date("M",strtotime($rowA['Period4EndingDate']))." ".date("y",strtotime($rowA['Period4EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period4EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period5EndingDate']))."\">".date("M",strtotime($rowA['Period5EndingDate']))." ".date("y",strtotime($rowA['Period5EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period5EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period6EndingDate']))."\">".date("M",strtotime($rowA['Period6EndingDate']))." ".date("y",strtotime($rowA['Period6EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period6EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period7EndingDate']))."\">".date("M",strtotime($rowA['Period7EndingDate']))." ".date("y",strtotime($rowA['Period7EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period7EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period8EndingDate']))."\">".date("M",strtotime($rowA['Period8EndingDate']))." ".date("y",strtotime($rowA['Period8EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period8EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period9EndingDate']))."\">".date("M",strtotime($rowA['Period9EndingDate']))." ".date("y",strtotime($rowA['Period9EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period9EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period10EndingDate']))."\">".date("M",strtotime($rowA['Period10EndingDate']))." ".date("y",strtotime($rowA['Period10EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period10EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period11EndingDate']))."\">".date("M",strtotime($rowA['Period11EndingDate']))." ".date("y",strtotime($rowA['Period11EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowA['Period11EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period12EndingDate']))."\">".date("M",strtotime($rowA['Period12EndingDate']))." ".date("y",strtotime($rowA['Period12EndingDate']))."</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowB['Period12EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period12EndingDate']))."\">------</option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowB['Period12EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period12EndingDate']))."\"><b>FY ".date("y",strtotime($rowA['Period12EndingDate']))."</b></option>\n";
			echo "	<option value=\"".date("m/d/Y",(strtotime($rowB['Period12EndingDate']) + 86401)).":".date("m/d/Y",strtotime($rowA['Period12EndingDate']))."\">------</option>\n";
	
			/*
			if (isset($_REQUEST['drange'.$ymod]))
			{
				$bdt=split(":",$_REQUEST['drange'.$ymod]);
			}
			else
			{
				$bdt=array(0,0);
			}
			
			foreach ($dtarray as $n1 => $v1)
			{
				if (isset($_REQUEST['drange'.$ymod]) && $bdt[0]==$v1[0])
				{
					echo "<option value=\"".$v1[0].":".$v1[1]."\" SELECTED>".$n1." ".date("Y",strtotime($v1[0]))."</option>\n";
				}
				else
				{
					echo "<option value=\"".$v1[0].":".$v1[1]."\">".$n1." ".date("Y",strtotime($v1[0]))."</option\n>";	
				}
			}
			*/
		}
		
		echo "</select>\n";
	}
}

function selectdatedisplay_compareold($fyr,$ymod)
{
	global $dterror;

	$cpny=$_REQUEST['cpny'];
	
	if (empty($ymod))
	{
		$ymod=0;
	}
	
	if (empty($fyr))
	{
		$currfisyr=(getcurrfisyr() - $ymod);
	}
	else
	{
		$currfisyr=($fyr - $ymod);
	}
	
	$precurrfisyr=$currfisyr-1;

	$qry  = "SELECT ";
	$qry .= "Period1EndingDate,";
	$qry .= "Period2EndingDate,";
	$qry .= "Period3EndingDate,";
	$qry .= "Period4EndingDate,";
	$qry .= "Period5EndingDate,";
	$qry .= "Period6EndingDate,";
	$qry .= "Period7EndingDate,";
	$qry .= "Period8EndingDate,";
	$qry .= "Period9EndingDate,";
	$qry .= "Period10EndingDate,";
	$qry .= "Period11EndingDate,";
	$qry .= "Period12EndingDate";
	$qry .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$currfisyr."';";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_row($res);
	$nrows = mssql_num_rows($res);

	//echo $qry."<br>";

	$qryB  = "SELECT ";
	$qryB .= "Period1EndingDate,";
	$qryB .= "Period2EndingDate,";
	$qryB .= "Period3EndingDate,";
	$qryB .= "Period4EndingDate,";
	$qryB .= "Period5EndingDate,";
	$qryB .= "Period6EndingDate,";
	$qryB .= "Period7EndingDate,";
	$qryB .= "Period8EndingDate,";
	$qryB .= "Period9EndingDate,";
	$qryB .= "Period10EndingDate,";
	$qryB .= "Period11EndingDate,";
	$qryB .= "Period12EndingDate";
	$qryB .= " FROM MAS_".$cpny."..GLC_FiscalYrMasterFile WHERE FiscalYr='".$precurrfisyr."';";
	$resB  = mssql_query($qryB);
	$rowB  = mssql_fetch_row($resB);

	//echo $qryB."<br>";

	if ($nrows < 1)
	{
		$dterror=1;
		exit;
	}
	else
	{
		$preyrmT=date("M",strtotime($rowB[11]));
		$preyrdm=date("m",strtotime($rowB[11]));
		$preyrdd=date("d",strtotime($rowB[11]));
		$preyrdy=date("Y",strtotime($rowB[11]));
		$prefdat=date("m/d/Y",strtotime($rowB[11]) + 86400);
	
		foreach ($row as $n => $v)
		{
			$premT	=date("M",strtotime($v));
			$dm		=date("m",strtotime($v));
			$predd	=date("d",strtotime($v));
			$predy	=date("Y",strtotime($v));
			//echo $dm."<br>";
			//echo "PST: ".date("m/d/Y",strtotime($v))."<br>";
			
			if (!isset($postdm))
			{
				if ($dm=='01')
				{
					//if ($predd=='31')
					if ($preyrdd=='31')
					{
						$postdm=$dm;
						$postdd='01';
						$postdy=$predy;
						$numt=1;
					}
					else
					{
						$postdm='12';
						$postdd=$preyrdd+1;
						$postdy=$preyrdy;
						$numt=2;
					}
				}
				elseif ($dm=='08')
				{
					if ($predd=='31')
					{
						$postdm=$dm+1;
						$postdd='01';
						$postdy=$predy;
						$numt=3;
						//echo "NOT HIT<br>";
					}
					else
					{
						$postdm=$dm-1;
						$postdd=$preyrdd+1;
						$postdy=$preyrdy;
						$numt=4;
						//echo "HIT<br>";
					}
					//echo $numt."<br>";
				}
				elseif ($dm=='09')
				{
					if ($predd=='30')
					{
						$postdm=$dm;
						$postdd='01';
						$postdy=$predy;
						$numt=5;
					}
					else
					{
						$postdm=$dm-1;
						$postdd=$preyrdd+1;
						$postdy=$preyrdy;
						$numt=6;
					}
				}
			}
	
			if(!isset($dtarray))
			{
				//echo "HIT<br>";
				$dtarray=array("$premT" => array (0=>$prefdat, 1=>"$dm/$predd/$predy"));
				//$dtarray=array("$premT" => array (0=>"$postdm/$postdd/$postdy", 1=>"$dm/$predd/$predy"));
			}
			else
			{
				$dtarray[$premT]=array (0=>"$postdm/$postdd/$postdy", 1=>"$dm/$predd/$predy");
				//$dtarray[$premT]=array (0=>"$postdm/$postdd/$postdy", 1=>"$dm/$predd/$predy");
			}
	
			$postdm=$dm;
			$postdd=$predd;
			$postdy=$predy;
	
			if ($postdm=='12'&&$postdd=='31')
			{
				$postdm='01';
				$postdd='01';
				$postdy=$predy+1;
				$numt=5;
			}
			elseif ($postdm=='02'&&$postdd=='28')
			{
				$postdm=$dm+1;
				$postdd='01';
				$numt=6;
			}
			elseif ($postdm=='02'&&$postdd=='29')
			{
				$postdm=$dm+1;
				$postdd='01';
				$numt=7;
			}
			elseif ($postdm=='04'&&$postdd=='30')
			{
				$postdm=$dm+1;
				$postdd='01';
				$numt=8;
			}
			elseif ($postdm=='06'&&$postdd=='30')
			{
				$postdm=$dm+1;
				$postdd='01';
				$numt=9;
			}
			elseif ($postdm=='08'&&$postdd=='31')
			{
				$postdm=$dm+1;
				$postdd='01';
				$numt=101;
				//echo "HIT 8<br>";
			}
			elseif ($postdm=='09'&&$postdd=='30')
			{
				$postdm=$dm+1;
				$postdd='01';
				$numt=10;
			}
			elseif ($postdm=='11'&&$postdd=='30')
			{
				$postdm=$dm+1;
				$postdd='01';
				$numt=11;
			}
			elseif ($postdd=='31')
			{
				$postdm=$dm+1;
				$postdd='01';
				$numt=12;
			}
			else
			{
				$postdd=$postdd+1;
				$numt=13;
				//echo "HIT Aug<br>";
			}
		}
	
		//echo $cpny."<br>";
		//echo $dtarray['Aug'][0]."<br>";
		//echo $precurrfisyr."<br>";
		//show_array_vars($dtarray);
	
		if ($cpny=="MAS_420" && $precurrfisyr==2005)
		{
			$dtarray['Aug'][0]="07/31/2005";
		}
		
		if (isset($_REQUEST['drange'.$ymod]))
		{
			$bdt=split(":",$_REQUEST['drange'.$ymod]);
		}
		else
		{
			$bdt=array(0,0);
		}
	
		echo "<select name=\"drange".$ymod."\">\n";
		
		foreach ($dtarray as $n1 => $v1)
		{
			if (isset($_REQUEST['drange'.$ymod]) && $bdt[0]==$v1[0])
			{
				echo "<option value=\"".$v1[0].":".$v1[1]."\" SELECTED>".$n1." ".date("Y",strtotime($v1[0]))."</option>";
			}
			else
			{
				echo "<option value=\"".$v1[0].":".$v1[1]."\">".$n1." ".date("Y",strtotime($v1[0]))."</option>";	
			}
		}
		
		echo "</select>\n";
	
		//echo $dtarray['Aug'][0]."<br>";
		//show_array_vars($dtarray);
	
		//return $dtarray;
	}
}

function arrayaddpermo($arr)
{
	$currmonth=getcurrmonth();

	if ($currmonth==1)
	{
		$arsum=$arr[0];
	}
	elseif ($currmonth==2)
	{
		$arsum=$arr[0]+$arr[1];
	}
	elseif ($currmonth==3)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2];
	}
	elseif ($currmonth==4)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2]+$arr[3];
	}
	elseif ($currmonth==5)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2]+$arr[3]+$arr[4];
	}
	elseif ($currmonth==6)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2]+$arr[3]+$arr[4]+$arr[5];
	}
	elseif ($currmonth==7)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2]+$arr[3]+$arr[4]+$arr[5]+$arr[6];
	}
	elseif ($currmonth==8)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2]+$arr[3]+$arr[4]+$arr[5]+$arr[6]+$arr[7];
	}
	elseif ($currmonth==9)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2]+$arr[3]+$arr[4]+$arr[5]+$arr[6]+$arr[7]+$arr[8];
	}
	elseif ($currmonth==10)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2]+$arr[3]+$arr[4]+$arr[5]+$arr[6]+$arr[7]+$arr[8]+$arr[9];
	}
	elseif ($currmonth==11)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2]+$arr[3]+$arr[4]+$arr[5]+$arr[6]+$arr[7]+$arr[8]+$arr[9]+$arr[10];
	}
	elseif ($currmonth==12)
	{
		$arsum=$arr[0]+$arr[1]+$arr[2]+$arr[3]+$arr[4]+$arr[5]+$arr[6]+$arr[7]+$arr[8]+$arr[9]+$arr[10]+$arr[11];
	}
	/*
	echo "Curr MO:";
	echo $currmonth;
	echo "<br>";
	print_r($arr);
	echo "<br>";
	*/
	return $arsum;
}

function dateheaders()
{
	global $dtarray;

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		$title	="";
	
		//print_r($dtarray);
	
		foreach ($dtarray as $dtprd => $dtdate)
		{
			$sdtdate=split(":",$dtdate);
			$myr	= date("M",strtotime($sdtdate[1]));
			$eyr	= date("y",strtotime($sdtdate[1]));
			$title="($sdtdate[0] - $sdtdate[1])";
			//echo "                                 <td width=\"60px\" class=\"und\" align=\"center\" valign=\"bottom\"><b>$dtmonth ($submonth[0]-$submonth[1])</b></td>\n";
			echo "                                 <td width=\"60px\" class=\"gray_und\" align=\"center\" valign=\"bottom\" title=\"".$title."\"><b>".$myr." '".$eyr."</b></td>\n";
		}
		
		//echo "                                 <td width=\"20px\" class=\"gray_und\" align=\"center\"><b>&nbsp</b></td>\n";
		echo "                                 <td width=\"60px\" class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Variance</b></td>\n";
	}
	else
	{
		$dtarray	=setdatearray();
		$title	="";
	
		foreach ($dtarray as $dtmonth => $submonth)
		{
			$eyr	= date("y",strtotime($submonth[1]));
			$title="($submonth[0] - $submonth[1])";
			//echo "                                 <td width=\"60px\" class=\"und\" align=\"center\" valign=\"bottom\"><b>$dtmonth ($submonth[0]-$submonth[1])</b></td>\n";
			echo "                                 <td width=\"60px\" class=\"gray_und\" align=\"center\" valign=\"bottom\" title=\"".$title."\"><b>".$dtmonth." '".$eyr."</b></td>\n";
		}
		
		echo "                                 <td width=\"20px\" class=\"gray_und\" align=\"center\"><b>&nbsp</b></td>\n";
		echo "                                 <td width=\"60px\" class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>YTD Total</b></td>\n";
		echo "                                 <td width=\"60px\" class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Avg. Per Pool</b></td>\n";
		echo "                                 <td width=\"60px\" class=\"gray_und\" align=\"center\" valign=\"bottom\"><b>Mo. Avg.</b></td>\n";
		
	}
}

function dateselect()
{
	global $dtarray;
	$dtarray=setdatearray();

	if (is_array($dtarray))
	{
		foreach ($dtarray as $postname => $postvalue)
		{
			//echo "$postname:<br> ";
			if (is_array($postvalue))
			{
				foreach ($postvalue as $name => $value)
				//echo "$name = $value<br>";
				echo "<option value=\"$value\">$postname</option>\n";
			}
		}
	}
}

function formatinteger($num)
{
	//$num = number_format($num,0,'.',',');
	$num = number_format($num);
	return $num;
}

function currency($num)
{
	$num = "\$".number_format($num,0,'.',',');
	return $num;
}

function fmoney($num)
{
	$num = number_format($num, 2, '.', ',');
	return $num;
}

function calc_gp_misc_sales()
{
	global $msarray,$mcarray,$tmcarray,$pdarray,$topar;

	if (is_array($msarray))
	{
		foreach ($msarray as $arraykey => $arrayvalue)
		{
			$amt=$arrayvalue-$mcarray[$arraykey];
			if (count($tmcarray) < $topar)
			{
				$calc_gp=formatinteger($amt);
				$tmcarray[]=$amt;
			}
			else
			{
				$calc_gp=0;
				$tmcarray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_gp</td>\n";
		}
	}

	$tcalc_gp   =formatinteger(array_sum($msarray) - array_sum($mcarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger((array_sum($msarray)/array_sum($pdarray)) - (array_sum($mcarray)/array_sum($pdarray)));
	}
	
	//$pavg_calc  =formatinteger((array_sum($msarray)/array_sum($pdarray)) - (array_sum($mcarray)/array_sum($pdarray)));
	$moavg_calc =formatinteger((array_sum($msarray)/$topar) - (array_sum($mcarray)/$topar));
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function cost_misc($cpny,$div,$cpny2,$div2,$cm)
{
	global $dtarray,$mcarray,$pdarray,$topar;

	$qtext=spec_code_qtext();

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];
		
		$subglar1=array();
		foreach ($cm as $gln => $glv)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv.$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			$subglar1[]=$rowA[0];
			
			if ($cpny2!=0)
			{
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv.$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
	
				$subglar1[]=$rowB[0];
			}
		}
		
		$samt=array_sum($subglar1);
		
		if (count($mcarray) < $topar)
		{
			$amt=formatinteger($samt);
			$mcarray[]=$samt;
		}
		else
		{
			$amt=0;
			$mcarray[]=0;
		}
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$smc=formatinteger(array_sum($mcarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$amc=0;
	}
	else
	{
		$amc=formatinteger(array_sum($mcarray)/array_sum($pdarray));
	}
	//$amc=formatinteger(array_sum($mcarray)/array_sum($pdarray));
	$mmc=formatinteger(array_sum($mcarray)/$topar);

	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$smc</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$amc</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$mmc</td>\n";
	echo "                              </tr>\n";
}

function misc_sales($cpny,$div,$cpny2,$div2,$gl)
{
	global $dtarray,$msarray,$pdarray,$topar;

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];
	
		$subglar1=array();
		foreach ($gl as $gln => $glv)
		{
			//echo $glv."<br>";
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv.$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
		
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			$subglar1[]=$rowA[0];
		}
		/*
		if ($mdiv==1)
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '401$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '401%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
	
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_row($resA);
			
		if ($mdiv==1)
		{
			$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '416$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '416%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
	
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_row($resB);
	
		if ($mdiv==1)
		{
			$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '430$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '430%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
	
		$resC = mssql_query($qryC);
		$rowC = mssql_fetch_row($resC);
			
		if ($mdiv==1)
		{
			$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '440$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '440%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
	
		$resD = mssql_query($qryD);
		$rowD = mssql_fetch_row($resD);
			
		if ($mdiv==1)
		{
			$qryE = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '450$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryE = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '450%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
	
		$resE = mssql_query($qryE);
		$rowE = mssql_fetch_row($resE);
			
		if ($mdiv==1)
		{
			$qryF = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '460$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryF = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '460%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
	
		$resF = mssql_query($qryF);
		$rowF = mssql_fetch_row($resF);
			
		if ($mdiv==1)
		{
			$qryG = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '461$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryG = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '461%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
	
		$resG = mssql_query($qryG);
		$rowG = mssql_fetch_row($resG);
		
		if ($mdiv==1)
		{
			$qryH = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '465$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryH = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '465%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		
		$resH = mssql_query($qryH);
		$rowH = mssql_fetch_row($resH);
		*/
		
		if ($cpny2!=0)
		{
			$subglar2=array();
			foreach ($gl as $gln2 => $glv2)
			{
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv.$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glv."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
			
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				$subglar2[]=$rowB[0];
			}
			/*
			if ($mdiv==1)
			{
				$qryI = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '401$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryI = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '401%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resI = mssql_query($qryI);
			$rowI = mssql_fetch_row($resI);
			
			if ($mdiv==1)
			{
				$qryJ = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '416$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryJ = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '416%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resJ = mssql_query($qryJ);
			$rowJ = mssql_fetch_row($resJ);
	
			if ($mdiv==1)
			{
				$qryK = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '430$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryK = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '430%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resK = mssql_query($qryK);
			$rowK = mssql_fetch_row($resK);
			
			if ($mdiv==1)
			{
				$qryL = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '440$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryL = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '440%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resL = mssql_query($qryL);
			$rowL = mssql_fetch_row($resL);
			
			if ($mdiv==1)
			{
				$qryM = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '450$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryM = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '450%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resM = mssql_query($qryM);
			$rowM = mssql_fetch_row($resM);
			
			if ($mdiv==1)
			{
				$qryN = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '460$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryN = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '460%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resN = mssql_query($qryN);
			$rowN = mssql_fetch_row($resN);
			
			if ($mdiv==1)
			{
				$qryO = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '461$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryO = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '461%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resO = mssql_query($qryO);
			$rowO = mssql_fetch_row($resO);
			
			if ($mdiv==1)
			{
				$qryP = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '465$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryP = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '465%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resP = mssql_query($qryP);
			$rowP = mssql_fetch_row($resP);
			*/
		}
		
		if ($div2==0)
		{
			/*
			$int1=revsign($rowA[0]);
			$int2=revsign($rowB[0]);
			$int3=revsign($rowC[0]);
			$int4=revsign($rowD[0]);
			$int5=revsign($rowE[0]);
			$int6=revsign($rowF[0]);
			$int7=revsign($rowG[0]);
			$int8=revsign($rowH[0]);
			
			$samt=$int1+$int2+$int3+$int4+$int5+$int6+$int7+$int8;
			*/
			
			$samt=revsign(array_sum($subglar1));
		}
		else
		{
			/*
			$int1=revsign($rowA[0]);
			$int2=revsign($rowB[0]);
			$int3=revsign($rowC[0]);
			$int4=revsign($rowD[0]);
			$int5=revsign($rowE[0]);
			$int6=revsign($rowF[0]);
			$int7=revsign($rowG[0]);
			$int8=revsign($rowH[0]);
			$int9=revsign($rowI[0]);
			$int10=revsign($rowJ[0]);
			$int11=revsign($rowK[0]);
			$int12=revsign($rowL[0]);
			$int13=revsign($rowM[0]);
			$int14=revsign($rowN[0]);
			$int15=revsign($rowO[0]);
			$int16=revsign($rowP[0]);
			
			$samt=$int1+$int2+$int3+$int4+$int5+$int6+$int7+$int8+$int9+$int10+$int12+$int13+$int14+$int15+$int16;
			*/
			
			$samt=revsign(array_sum($subglar1)) + revsign(array_sum($subglar2));
		}

		if (count($msarray) < $topar)
		{
			$amt=formatinteger($samt);
			$msarray[]=$samt;
		}
		else
		{
			$amt=0;
			$msarray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$sms=formatinteger(array_sum($msarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$ams=0;
	}
	else
	{
		$ams=formatinteger(array_sum($msarray)/array_sum($pdarray));
	}
	//$ams=formatinteger(array_sum($msarray)/array_sum($pdarray));
	$mms=formatinteger(array_sum($msarray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$sms</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$ams</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$mms</td>\n";
	echo "                              </tr>\n";
}

function dir_cost_sales($cpny,$div,$cpny2,$div2,$gl)
{
	global $dtarray,$dsarray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	$qtext=spec_code_qtext();

	$opp=0;
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '525$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '525%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '526$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '526%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				$samt	=$rowA[0]+$rowB[0];
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '525$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '525%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '526$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '526%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				if ($mdiv==1)
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '525$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '525%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_row($resC);
		
				if ($mdiv==1)
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '526$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '526%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resD = mssql_query($qryD);
				$rowD = mssql_fetch_row($resD);
				
				$samt	=$rowA[0]+$rowB[0]+$rowC[0]+$rowD[0];
			}
		}
		else
		{
			$samt=0;
		}

		if (count($dsarray) < $topar)
		{
			$amt=formatinteger($samt);
			$dsarray[]=$samt;
		}
		else
		{
			$amt=0;
			$dsarray[]=0;
		}

		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
		$opp++;
	}

	$sds=formatinteger(array_sum($dsarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$ads=0;
	}
	else
	{
		$ads=formatinteger(array_sum($dsarray)/number_format(array_sum($euarray)));
	}
	
	//$ads=formatinteger(array_sum($dsarray)/array_sum($euarray));
	$mds=formatinteger(array_sum($dsarray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$sds."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$ads."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$mds."</td>\n";
	echo "                              </tr>\n";
}

function sales_tax_paid($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$sparray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	$qtext=spec_code_qtext();

	$opp=0;
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '550$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '550%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				$samt=$rowA[0];
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '550$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '550%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '550$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '550%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				$samt=$rowA[0]+$rowB[0];
			}
		}
		else
		{
			$samt=0;
		}

		if (count($sparray) < $topar)
		{
			$amt=formatinteger($samt);
			$sparray[]=$samt;
		}
		else
		{
			$amt=0;
			$sparray[]=0;
		}

		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
		$opp++;
	}

	$ssp=formatinteger(array_sum($sparray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$asp=0;
	}
	else
	{
		$asp=formatinteger(array_sum($sparray)/number_format(array_sum($euarray)));
	}
	
	//$asp=formatinteger(array_sum($sparray)/array_sum($euarray));
	$msp=formatinteger(array_sum($sparray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$ssp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$asp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$msp</td>\n";
	echo "                              </tr>\n";
}

function cost_on_closed($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$ccarray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	$opp		=0;
	
	$qtext=spec_code_qtext();
	
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
				
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '590$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '590%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				$samt=$rowA[0];
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '590$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '590%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '590$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '590%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				$samt=$rowA[0]+$rowB[0];
			}
		}
		else
		{
			$samt=0;
		}

		if (count($ccarray) < $topar)
		{
			$amt=formatinteger($samt);
			$ccarray[]=$samt;
		}
		else
		{
			$amt=0;
			$ccarray[]=0;
		}
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
		$opp++;
	}

	$srr=formatinteger(array_sum($ccarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$rav=0;
	}
	else
	{
		$rav=formatinteger(array_sum($ccarray)/number_format(array_sum($euarray)));
	}
	
	//$rav=formatinteger(array_sum($ccarray)/array_sum($euarray));
	$rag=formatinteger(array_sum($ccarray)/$topar);
	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$srr</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$rav</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$rag</td>\n";
	echo "                              </tr>\n";
}

function inv_adjust($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$iaarray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	$opp		=0;
	
	$qtext=spec_code_qtext();
	
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
				
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '551$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '551%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				$samt=$rowA[0];
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '551$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '551%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
				
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '551$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '551%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				$samt=$rowA[0]+$rowB[0];
			}
			//echo $qryA."<br>";
			//echo $qryB."<br>";
		}
		else
		{
			$samt=0;
		}

		if (count($iaarray) < $topar)
		{
			$amt=formatinteger($samt);
			$iaarray[]=$samt;
		}
		else
		{
			$amt=0;
			$iaarray[]=0;
		}

		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
		$opp++;
	}

	$srr=formatinteger(array_sum($iaarray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$rav=0;
	}
	else
	{
		$rav=formatinteger(array_sum($iaarray)/number_format(array_sum($euarray)));
	}
	//$rav=formatinteger(array_sum($iaarray)/array_sum($euarray));
	$rag=formatinteger(array_sum($iaarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$srr</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$rav</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$rag</td>\n";
	echo "                              </tr>\n";
}

function pre_reco_rev($cpny,$div,$cpny2,$div2,$gl) // for Equiv Units
{
	global $dtarray,$prrarray,$pdarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	$opp		=0;
	
	$qtext=spec_code_qtext();
	
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				//echo "P: ".$qryA."<br>";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				
				//echo "P: ".$qryB."<br>";
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
		
				$int1=revsign($rowA[0]);
				$int2=revsign($rowB[0]);
		
				$samt=$int1+$int2;
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				if ($mdiv==1)
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_row($resC);
		
				if ($mdiv==1)
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resD = mssql_query($qryD);
				$rowD = mssql_fetch_row($resD);
		
				$int1=revsign($rowA[0]);
				$int2=revsign($rowB[0]);
				$int3=revsign($rowC[0]);
				$int4=revsign($rowD[0]);
		
				$samt=$int1+$int2+$int3+$int4;
			}
		}
		else
		{
			$samt=0;
		}

		if (count($prrarray) < $topar)
		{
			$prrarray[]=$samt;
		}
		else
		{
			$prrarray[]=0;
		}
		$opp++;
	}
	return $prrarray;
}

function forgive_debt($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$fdarray,$pdarray,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($cpny2==0)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '411$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '411%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
	
			$samt=revsign($rowA[0]);
		}
		else
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '411$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '411%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '411$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '411%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			$samt=revsign($rowA[0])+revsign($rowB[0]);
		}

		if (count($fdarray) < $topar)
		{
			$amt=formatinteger($samt);
			$fdarray[]=$samt;
		}
		else
		{
			$amt=0;
			$fdarray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$svcs=formatinteger(array_sum($fdarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=formatinteger(array_sum($fdarray)/array_sum($pdarray));
	}
	//$savp=formatinteger(array_sum($fdarray)/array_sum($pdarray));
	$mavg=formatinteger(array_sum($fdarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$svcs</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$savp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$mavg</td>\n";
}

function reco_rev($cpny,$div,$cpny2,$div2,$gl)
{
	global $dtarray,$rrarray,$pdarray,$euarray,$open_ar,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$opp		=0;
	
	$qtext=spec_code_qtext();
	
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				//echo $qryA."<br>";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				//echo $qryB."<br>";
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
		
				$int1=revsign($rowA[0]);
				$int2=revsign($rowB[0]);
				$samt=$int1+$int2;
			}
			else
			{
				if ($mdiv==1)
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);
		
				if ($mdiv==1)
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_row($resB);
				
				if ($mdiv==1)
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0].$div2."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[0]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_row($resC);
		
				if ($mdiv==1)
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1].$div."2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
				else
				{
					$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '".$gl[1]."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
				}
		
				$resD = mssql_query($qryD);
				$rowD = mssql_fetch_row($resD);
		
				$int1=revsign($rowA[0]);
				$int2=revsign($rowB[0]);
				$int3=revsign($rowC[0]);
				$int4=revsign($rowD[0]);
				$samt=$int1+$int2+$int3+$int4;
			}
		}
		else
		{
			$samt=0;
		}
		
		if (count($rrarray) < $topar)
		{
			$amt=formatinteger($samt);
			$rrarray[]=$samt;
		}
		else
		{
			$amt=0;
			$rrarray[]=0;
		}
		
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
		$opp++;
	}

	$srr=formatinteger(array_sum($rrarray));
	//$srr=formatinteger(preg_replace("/-/","",array_sum($rrarray)));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$rav=0;
	}
	else
	{
		$rav=formatinteger(array_sum($rrarray)/number_format(array_sum($euarray)));
	}
	
	//$rav=formatinteger(array_sum($rrarray)/array_sum($euarray));
	$rag=formatinteger(array_sum($rrarray)/$topar);

	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$srr</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$rav</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$rag</td>\n";
	echo "                              </tr>\n";
}

function equiv_units($cpny,$div,$cpny2,$div2,$gl)
{
	global $euarray,$pdarray,$avg_per_pool,$topar;

	$prrarray=pre_reco_rev($cpny,$div,$cpny2,$div2,$gl);

	//print_r($prrarray);
	//echo $avg_per_pool;
	//echo "<br>";
	if (is_array($prrarray))
	{
		foreach ($prrarray as $arraykey => $arrayvalue)
		{
			if (count($euarray) < $_REQUEST['prd'])
			{
				if ($avg_per_pool==0)
				{
					$calc_eu=0;
				}
				else
				{
					$calc_eu=$arrayvalue/$avg_per_pool;
				}
				$euarray[]=$calc_eu;
			}
			else
			{
				$calc_eu=0;
				$euarray[]=0;
			}
			$calc_eu=formatinteger($calc_eu);
			//$calc_eu=$calc_eu;
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$calc_eu."</td>\n";
		}
	}

	//print_r($euarray);
	//echo "<br>";

	$tcalc_gp   =formatinteger(array_sum($euarray));
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tcalc_gp."</td>\n";
}

function calc_total_gp()
{
	global $rrarray,$dsarray,$ccarray,$iaarray,$gparray,$sparray,$pdarray,$euarray,$topar;

	if (is_array($rrarray))
	{
		foreach ($dsarray as $arraykey => $arrayvalue)
		{
			$sunfmt=$rrarray[$arraykey]-($arrayvalue+$ccarray[$arraykey]+$iaarray[$arraykey]+$sparray[$arraykey]);

			if (count($gparray) < $topar)
			{
				$calc_gp=formatinteger($sunfmt);
				$gparray[]=$sunfmt;
			}
			else
			{
				$calc_gp=0;
				$gparray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_gp</td>\n";
		}
	}

	$tcalc_gp   =formatinteger(array_sum($gparray));
	
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($gparray)/number_format(array_sum($euarray)));
	}
		
	//$pavg_calc  =formatinteger(array_sum($gparray)/array_sum($euarray));
	$moavg_calc =formatinteger(array_sum($gparray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function calc_gp_var()
{
	global $pdarray,$euarray,$gparray,$gpcarray,$gparray,$topar;

	//print_r($gparray);
	if (is_array($gpcarray))
	{
		foreach ($gpcarray as $arraykey => $arrayvalue)
		{
			echo "                                 <td width=\"60px\" align=\"right\">&nbsp</td>\n";
		}
	}

	if (!is_array($euarray) || array_sum($euarray)==0 || !is_array($pdarray) || array_sum($pdarray)==0)
	{
		$p1avg_calc	=0;
		$p2avg_calc	=0;
		$pavg_calc	=0;
	}
	else
	{
		$p1avg_calc = round(array_sum($gparray)) / round(array_sum($euarray));
		$p2avg_calc = round(array_sum($gpcarray)) / round(array_sum($pdarray));
		$pavg_calc	= $p1avg_calc - $p2avg_calc;
	}
	
	//$pavg_calc	=(array_sum($gparray) / array_sum($euarray)) - (array_sum($gpcarray) / array_sum($pdarray));
	if (!is_array($euarray) || array_sum($euarray)==0)
	{
		$pc_calc=0;
	}
	else
	{
		$pc_calc	=($pavg_calc / $p1avg_calc) * 100;
	}
	
	//$pc_calc	=($pavg_calc / ((array_sum($gparray) / array_sum($euarray)))) * 100;
	echo "                                 <td width=\"20px\" align=\"center\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\">".formatinteger($pavg_calc)."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\">".formatinteger($pc_calc)."%</td>\n";
}

function calc_gp_contracts()
{
	global $vcsarray,$dcarray,$pdarray,$gpcarray,$topar;

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($vcsarray as $na => $va)
		{
			$topar++;
		}
	}
	
	if (is_array($vcsarray))
	{
		foreach ($vcsarray as $arraykey => $arrayvalue)
		{
			$pcalc_gp=$arrayvalue-$dcarray[$arraykey];

			if (count($gpcarray) < $topar)
			{
				$calc_gp=formatinteger($pcalc_gp);
				$gpcarray[]=$pcalc_gp;
			}
			else
			{
				$calc_gp=0;
				$gpcarray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_gp</td>\n";
		}
	}

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		$gpcdiff=formatinteger(($gpcarray[1] - $gpcarray[0]));
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$gpcdiff."</font></td>\n";
	}
	else
	{
		$tcalc_gp   =formatinteger(array_sum($gpcarray));
		
		if (!is_array($pdarray) || array_sum($pdarray)==0)
		{
			$pavg_calc=0;
		}
		else
		{
			$pavg_calc  =formatinteger(array_sum($gpcarray)/array_sum($pdarray));
		}
		
		//$pavg_calc  =formatinteger(array_sum($gpcarray)/array_sum($pdarray));
		$moavg_calc =formatinteger(array_sum($gpcarray)/$topar);
		echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
	}
}

function dir_cost_con($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$dcarray,$pdarray,$open_ar,$topar,$reno_divs;

	//$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$tcolor	="black";
	$reno1	=0;
	$reno2	=0;
	
	$qtext=spec_code_qtext();
	
	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($dtarray as $na => $va)
		{
			$open_ar[]=1;
			$topar++;
		}
	}
	
	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($dtarray as $na => $va)
		{
			$open_ar[]=1;
			$topar++;
		}
	}
	
	/*if (is_array($reno_divs) && in_array($div,$reno_divs))
	{
		//echo "RENO1!";
		
		$reno1	=1;
	}
	
	if ($cpny2!=0 && is_array($reno_divs) && in_array($div2,$reno_divs))
	{
		//echo "RENO2!";
		
		$reno2	=1;
	}*/

	/*$odbc_ser	=	JMS_TST_DB;
	$odbc_add	=	JMS_TST_DB;
	$odbc_db		=	JMS_TST_CAT; #the name of the database
	$odbc_user	=	JMS_RO_ID; #a valid username
	$odbc_pass	=	JMS_RO_PS; #a password for the username*/
	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"jest"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username
			
	$odbc_conn	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);

	$tmp=0;
	$opp=0;
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
			{
				$subdates=split(":",$subdtarray);
				$dtconst0=$subdates[0];
				$dtconst1=$subdates[1];
			}
			else
			{
				$dtconst0=$subdtarray[0];
				$dtconst1=$subdtarray[1];
			}
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
				else
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
				$odbc_retA 	 = odbc_result($odbc_resA, 1);
				
				$odbc_ret	=$odbc_retA;
			}
			else
			{
				if ($mdiv==1)
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
				else
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
				$odbc_retA 	 = odbc_result($odbc_resA, 1);
				
				if ($mdiv==1)
				{
					$odbc_qryB    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND mdiv='".$div2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
				}
				else
				{
					$odbc_qryB    = "SELECT ISNULL(SUM(CAST(dcc as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resB	 = odbc_exec($odbc_conn, $odbc_qryB);
				$odbc_retB 	 = odbc_result($odbc_resB, 1);
				
				$odbc_ret	=$odbc_retA+$odbc_retB;
			}
			
			if (!isset($odbc_ret) || $odbc_ret==0)
			{
				if ($cpny2==0)
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '012$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '012%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					
					//echo $qryA."<br>";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					$tmp	=$rowA[0];
				}
				else
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '012$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '012%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					
					//echo $qryA."<br>";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					if ($mdiv==1)
					{
						$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '012$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '012%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					
					//echo $qryA."<br>";
					$resB = mssql_query($qryB);
					$rowB = mssql_fetch_row($resB);
					
					$tmp	=$rowA[0]+$rowB[0];
				}
				
				if ($_SESSION['officeid']==89 && $tmp!=0)
				{
					$tcolor="green";
				}
				else
				{
					$tcolor="black";
				}
			}
			else
			{
				//echo $odbc_qry."<br>";
				$tmp=$odbc_ret;
				$tcolor="black";
			}
		}
		else
		{
			$tmp=0;
			
			if ($_SESSION['officeid']==89 && $tmp!=0)
			{
				$tcolor="red";
			}
			else
			{
				$tcolor="black";
			}
		}
		
		if (count($dcarray) < $topar)
		{
			$amt=formatinteger($tmp);
			$dcarray[]=$tmp;
		}
		else
		{
			$amt=0;
			$dcarray[]=0;
		}	
		
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font color=\"".$tcolor."\" size=\"1\">".$amt."</td>\n";
		$opp++;
	}

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		$dccdiff=formatinteger(($dcarray[1] - $dcarray[0]));
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$dccdiff."</font></td>\n";
	}
	else
	{
		$sdc=formatinteger(array_sum($dcarray));
		
		if (!is_array($pdarray) || array_sum($pdarray)==0)
		{
			$sav=0;
		}
		else
		{
			$sav=formatinteger(array_sum($dcarray)/array_sum($pdarray));
		}
		
		$mag=formatinteger(array_sum($dcarray)/$topar);
	
		echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$sdc."</td>\n";
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$sav."</td>\n";
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$mag."</td>\n";
	}
}

function val_con_start($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$vcsarray,$pdarray,$avg_per_pool,$open_ar,$topar,$reno_divs;

	//$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$mflg		=0;
	$tcolor	="black";
	$dtar		=0;
	$reno1	=0;
	$reno2	=0;
	
	$qtext=spec_code_qtext();
	
	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($dtarray as $na => $va)
		{
			$open_ar[]=1;
			$topar++;
		}
	}
	
	/*if (is_array($reno_divs) && in_array($div,$reno_divs))
	{
		//echo "RENO1!";
		
		$reno1	=1;
	}
	
	if ($cpny2!=0 && is_array($reno_divs) && in_array($div2,$reno_divs))
	{
		//echo "RENO2!";
		
		$reno2	=1;
	}*/

	/*$odbc_ser	=	JMS_TST_DB;
	$odbc_add	=	JMS_TST_DB;
	$odbc_db		=	JMS_TST_CAT; #the name of the database
	$odbc_user	=	JMS_RO_ID; #a valid username
	$odbc_pass	=	JMS_RO_PS; #a password for the username*/
	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"jest"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username
			
	$odbc_conn	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);

	$tmp=0;
	$opp=0;
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if ($open_ar[$opp] != 0)
		{
			if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
			{
				$subdates=split(":",$subdtarray);
				$dtconst0=$subdates[0];
				$dtconst1=$subdates[1];
			}
			else
			{
				$dtconst0=$subdtarray[0];
				$dtconst1=$subdtarray[1];
			}
	
			if ($cpny2==0)
			{
				if ($mdiv==1)
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
				else
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
		
				//echo $odbc_qryA."<br>";
				$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
				$odbc_retA 	 = odbc_result($odbc_resA, 1);
				
				$odbc_ret	=$odbc_retA;
			}
			else
			{
				if ($mdiv==1)
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
				else
				{
					$odbc_qryA    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
				$odbc_retA 	 = odbc_result($odbc_resA, 1);
				
				if ($mdiv==1)
				{
					$odbc_qryB    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND mdiv='".$div2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
				}
				else
				{
					$odbc_qryB    = "SELECT ISNULL(SUM(CAST(vcs as decimal)),0) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
				}
		
				//echo $odbc_qry."<br>";
				$odbc_resB	 = odbc_exec($odbc_conn, $odbc_qryB);
				$odbc_retB 	 = odbc_result($odbc_resB, 1);
				
				$odbc_ret	=$odbc_retA+$odbc_retB;
			}
			
			if (!isset($odbc_ret) || $odbc_ret == 0)
			{
				if ($cpny2==0)
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '011$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '011% ".$qtext."' AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
		
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					$tmp=$rowA[0];
				}
				else
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '011$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryA = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '011%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
		
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					if ($mdiv==1)
					{
						$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '011$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
					else
					{
						$qryB = "SELECT ISNULL(SUM(PostingAmount),0) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '011%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
					}
		
					$resB = mssql_query($qryB);
					$rowB = mssql_fetch_row($resB);
					
					$tmp=$rowA[0]+$rowB[0];
				}
				
				if ($_SESSION['officeid']==89 && $tmp!=0)
				{
					$tcolor="green";
				}
				else
				{
					$tcolor="black";
				}
			}
			else
			{
				$tmp=$odbc_ret;
				$tcolor="black";
			}
		}
		else
		{
			$tmp=0;
			
			if ($_SESSION['officeid']==89 && $tmp!=0)
			{
				$tcolor="red";
			}
			else
			{
				$tcolor="black";
			}
		}

		if (count($vcsarray) < $topar)
		{
			$amt=formatinteger($tmp);
			$vcsarray[]=$tmp;
		}
		else
		{
			$amt=0;
			$vcsarray[]=0;
		}
		
		echo "                                 <td width=\"60px\" align=\"right\"><font color=\"".$tcolor."\" size=\"1\">".$amt."</font></td>\n";
		$opp++;
		$dtar++;
	}

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		$vcsdiff=formatinteger(($vcsarray[1] - $vcsarray[0]));
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$vcsdiff."</font></td>\n";
	}
	else
	{
		if (!is_array($pdarray) || array_sum($pdarray)==0)
		{
			$avg_per_pool=0;
		}
		else
		{
			$avg_per_pool=array_sum($vcsarray)/array_sum($pdarray);
		}
		
		$svcs=formatinteger(array_sum($vcsarray));
		
		if (!is_array($pdarray) || array_sum($pdarray)==0)
		{
			$savp=0;
		}
		else
		{
			$savp=formatinteger(array_sum($vcsarray)/array_sum($pdarray));
		}
		
		$mavg=formatinteger(array_sum($vcsarray)/$topar);
		echo "                                 <td width=\"20px\" align=\"center\">&nbsp</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$svcs."</font></td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$savp."</font></td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$mavg."</font></td>\n";
	}
}

function setenablearray($c,$d,$y,$dtar)
{
	error_reporting(E_ALL);
	/*
	$odbc_ser	=	"ZE_TST01"; #the name of the SQL Server
	$odbc_add	=	"192.168.1.30";
	$odbc_db		=	"jest"; #the name of the database
	$odbc_user	=	"jest_ro"; #a valid username
	$odbc_pass	=	"date1995"; #a password for the username
	*/
	
	//$odbc_ser	=	"BHEST01a.bluehaven.local"; #the name of the SQL Server
	$odbc_ser	=	"192.168.100.45";
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"jest"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username

	$odbc_conn0		= odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry0		= "SELECT p0,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11 FROM op_enable WHERE masid='".$c."' AND masdiv='".$d."' AND fiscyr='".$y."';";
	$odbc_res0		= odbc_exec($odbc_conn0, $odbc_qry0);
	
	$odbc_ret01		= odbc_result($odbc_res0, 1);
	$odbc_ret02		= odbc_result($odbc_res0, 2);
	$odbc_ret03		= odbc_result($odbc_res0, 3);
	$odbc_ret04		= odbc_result($odbc_res0, 4);
	$odbc_ret05		= odbc_result($odbc_res0, 5);
	$odbc_ret06		= odbc_result($odbc_res0, 6);
	$odbc_ret07		= odbc_result($odbc_res0, 7);
	$odbc_ret08		= odbc_result($odbc_res0, 8);
	$odbc_ret09		= odbc_result($odbc_res0, 9);
	$odbc_ret010	= odbc_result($odbc_res0, 10);
	$odbc_ret011	= odbc_result($odbc_res0, 11);
	$odbc_ret012	= odbc_result($odbc_res0, 12);
	
	//$open_ar		=array($odbc_ret01,$odbc_ret02,$odbc_ret03,$odbc_ret04,$odbc_ret05,$odbc_ret06,$odbc_ret07,$odbc_ret08,$odbc_ret09,$odbc_ret010,$odbc_ret011,$odbc_ret012);

	for ($r=1;$r <= count($dtar);$r++)
	{
		$open_ar[]=odbc_result($odbc_res0, $r);
	}

	//print_r($open_ar);
	//echo $odbc_qry0;
	//echo "<br>";
	return $open_ar;
}

function setenablearrayold($c,$d,$y)
{
	error_reporting(E_ALL);
	//echo "setenablear entry<br>";
	/*
	$odbc_ser	=	"ZE_TST01"; #the name of the SQL Server
	$odbc_add	=	"192.168.1.30";
	$odbc_db		=	"jest"; #the name of the database
	$odbc_user	=	"jest_ro"; #a valid username
	$odbc_pass	=	"date1995"; #a password for the username
	*/
	
	//$odbc_ser	=	"BHEST01a.bluehaven.local"; #the name of the SQL Server
	$odbc_ser	=	"192.168.100.45"; #the name of the SQL Server
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"jest"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username

	//echo $odbc_pass."<br>";
	$odbc_conn0		= odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$odbc_qry0		= "SELECT p0,p1,p2,p3,p4,p5,p6,p7,p8,p9,p10,p11 FROM jest..op_enable WHERE masid='".$c."' AND masdiv='".$d."' AND fiscyr='".$y."';";
	$odbc_res0		= odbc_exec($odbc_conn0, $odbc_qry0);
	
	//echo $odbc_qry0."<br>";
	
	$odbc_ret01		= odbc_result($odbc_res0, 1);
	$odbc_ret02		= odbc_result($odbc_res0, 2);
	$odbc_ret03		= odbc_result($odbc_res0, 3);
	$odbc_ret04		= odbc_result($odbc_res0, 4);
	$odbc_ret05		= odbc_result($odbc_res0, 5);
	$odbc_ret06		= odbc_result($odbc_res0, 6);
	$odbc_ret07		= odbc_result($odbc_res0, 7);
	$odbc_ret08		= odbc_result($odbc_res0, 8);
	$odbc_ret09		= odbc_result($odbc_res0, 9);
	$odbc_ret010	= odbc_result($odbc_res0, 10);
	$odbc_ret011	= odbc_result($odbc_res0, 11);
	$odbc_ret012	= odbc_result($odbc_res0, 12);
	
	//echo $odbc_ret01."<br>";
	
	$open_ar		=array($odbc_ret01,$odbc_ret02,$odbc_ret03,$odbc_ret04,$odbc_ret05,$odbc_ret06,$odbc_ret07,$odbc_ret08,$odbc_ret09,$odbc_ret010,$odbc_ret011,$odbc_ret012);

	//print_r($open_ar);
	//echo $odbc_qry0;
	//echo "<br>";
	return $open_ar;
}

function pools_dug($cpny,$div,$cpny2,$div2)
{
	error_reporting(E_ALL);
	global $dtarray,$pdarray,$ipdarray,$open_ar,$topar,$reno_divs;

	//$dtarray	=setdatearray();
	$drillen	=0;	
	$cutdate	=strtotime("10/1/06");
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=$_REQUEST['cpny'];
	$tdiv		=substr($retext,0,2);
	$tcolor	="black";
	$reno1	=0;
	$reno2	=0;
	
	$qtext=spec_code_qtext();
	
	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		foreach($dtarray as $na => $va)
		{
			$open_ar[]=1;
			$topar++;
		}
	}
	
	/*if (is_array($reno_divs) && in_array($div,$reno_divs))
	{
		//echo "RENO1!";
		
		$reno1	=1;
	}
	
	if ($cpny2!=0 && is_array($reno_divs) && in_array($div2,$reno_divs))
	{
		//echo "RENO2!";
		
		$reno2	=1;
	}*/
	
	/*$odbc_ser	=	JMS_TST_DB;
	$odbc_add	=	JMS_TST_DB;
	$odbc_db		=	JMS_TST_CAT; #the name of the database
	$odbc_user	=	JMS_RO_ID; #a valid username
	$odbc_pass	=	JMS_RO_PS; #a password for the username*/
	$odbc_ser	=	"192.168.100.45";
	$odbc_add	=	"192.168.100.45";
	$odbc_db	=	"jest"; #the name of the database
	$odbc_user	=	"jestadmin"; #a valid username
	$odbc_pass	=	"into99black"; #a password for the username
	
	//$open_ar		=array(1,1,1,1,1,1,1,1,1,1,1,1);
	//$open_ar		=array(0,0,0,0,0,0,0,0,0,0,0,0);
	$odbc_conn	 =	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","") or trigger_error("Could not connect to ODBC database", E_USER_ERROR);
	$tmp=0;
	$opp=0;
	
	//echo "CNT: ".count($dtarray)."<br>";
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
		{
			$subdates=split(":",$subdtarray);
			$dtconst0=$subdates[0];
			$dtconst1=$subdates[1];
		}
		else
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];
		}
		
		if ($open_ar[$opp] != 0)
		{
			//echo "Not Z: ".$open_ar[$opp]."<br>";
			if (strtotime($dtconst0) >= $cutdate)
			{
				if ($cpny2==0)
				{
					if ($mdiv==1)
					{
						$odbc_qryA    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
					}
					else
					{
						$odbc_qryA    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
					}
			
					//echo $odbc_qryA."<br>";
					$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
					$odbc_retA 	 = odbc_result($odbc_resA, 1);
					
					$odbc_ret	=$odbc_retA;
					
					if ($odbc_ret  > 0)
					{
						$drillen	= 1;
					}
				}
				else
				{
					if ($mdiv==1)
					{
						$odbc_qryA    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny."' AND mdiv='".$div."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
					}
					else
					{
						$odbc_qryA    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno1.";";
					}
			
					//echo $odbc_qry."<br>";
					$odbc_resA	 = odbc_exec($odbc_conn, $odbc_qryA);
					$odbc_retA 	 = odbc_result($odbc_resA, 1);
					
					if ($mdiv==1)
					{
						$odbc_qryB    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND mdiv='".$div2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
					}
					else
					{
						$odbc_qryB    = "SELECT COUNT(oroid) FROM jest..recognized_digs WHERE moid='".$cpny2."' AND trandate BETWEEN '".$dtconst0."' AND '".$dtconst1."' AND reno=".$reno2.";";
					}
			
					//echo $odbc_qry."<br>";
					$odbc_resB	 = odbc_exec($odbc_conn, $odbc_qryB);
					$odbc_retB 	 = odbc_result($odbc_resB, 1);
					
					$odbc_ret	=$odbc_retA+$odbc_retB;
					
					if ($odbc_ret  > 0)
					{
						$drillen	= 1;
					}
				}
				//echo $open_ar[$dtmonth]."<br>";
				//echo $open_ar[$dtmonth]."<br>";
			}
			else
			{
				$odbc_ret=0;
			}
				
			if ($odbc_ret==0)
			{
				if ($cpny2==0)
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
					else
					{
						$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
	
					//echo $qryA."<br>";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
				
					$tmp=$rowA[0];
				}
				else
				{
					if ($mdiv==1)
					{
						$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
					else
					{
						$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '010%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
	
					//echo $qryA."<br>";
					$resA = mssql_query($qryA);
					$rowA = mssql_fetch_row($resA);
					
					if ($mdiv==1)
					{
						$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '010".$div2."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
					else
					{
						$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '010%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
					}
	
					//echo $qryA."<br>";
					$resB = mssql_query($qryB);
					$rowB = mssql_fetch_row($resB);
				
					$tmp=$rowA[0]+$rowB[0];
				}
				
				if ($_SESSION['officeid']==89 && $tmp!=0)
				{
					$tcolor="green";
				}
				else
				{
					$tcolor="black";
				}
			}
			else
			{
				$tmp=$odbc_ret;
				$tcolor="black";
			}
		}
		else
		{
			//echo "Z: ".$open_ar[$opp]."<br>";
			$tmp=0;
			if ($_SESSION['officeid']==89 && $tmp!=0)
			{
				$tcolor="red";
			}
			else
			{
				$tcolor="black";
			}
		}

		if (count($ipdarray) < $topar)
		{
			$amt=formatinteger($tmp);
			$ipdarray[]=$tmp;
		}
		else
		{
			$amt=0;
			$ipdarray[]=0;
			$drillen=0;
		}
		
		if (count($pdarray) < $topar)
		{
			$pdarray[]=$tmp;
		}
		else
		{
			$pdarray[]=0;
			$drillen=0;
		}
		
		if ($drillen==1)
		{
			echo "                                 <td width=\"60px\" align=\"right\"><a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=pd&a=".$div."&d0=".$dtconst0."&d1=".$dtconst1."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\"><font size=\"1\">".$amt."</font></a></td>\n";
			$drillen=0;
		}
		else
		{
			echo "                                 <td width=\"60px\" align=\"right\"><font color=\"".$tcolor."\" size=\"1\">".$amt."</font></td>\n";
		}
		
		$opp++;
	}

	if (isset($_REQUEST['compare']) && $_REQUEST['compare']==1)
	{
		if (count($ipdarray) == 2)
		{
			$spd=formatinteger(($ipdarray[1] - $ipdarray[0]));
		}
		else
		{
			$spd="Err. 3";
		}
		
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$spd."</font></td>\n";
	}
	else
	{
		$spd=formatinteger(array_sum($ipdarray));
		
		echo "                                 <td width=\"20px\" align=\"right\">&nbsp</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$spd."</font></td>\n";
	}
}


function total_other_income()
{
	global $darray,$otharray,$rbarray,$fdarray,$tmcarray,$advrbarray,$totharray,$pdarray,$topar;

	if (is_array($darray))
	{
		foreach ($darray as $arraykey => $arrayvalue)
		{
			//$amt=$arrayvalue+$otharray[$arraykey]+$rbarray[$arraykey]+$fdarray[$arraykey]+$advrbarray[$arraykey]+$tmcarray[$arraykey];
			$amt=$arrayvalue+$otharray[$arraykey]+$fdarray[$arraykey]+$advrbarray[$arraykey]+$tmcarray[$arraykey];

			if (count($totharray) < $topar)
			{
				$tot_oth=formatinteger($amt);
				$totharray[]=$amt;
			}
			else
			{
				$tot_oth=0;
				$totharray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tot_oth</td>\n";
		}
	}

	$tcalc_oth  =formatinteger(array_sum($totharray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($totharray)/array_sum($pdarray));
	}
	
	//$pavg_calc  =formatinteger(array_sum($totharray)/array_sum($pdarray));
	$moavg_calc =formatinteger(array_sum($totharray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_oth</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function vend_rebate_income($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$advrbarray,$pdarray,$topar;

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];
	
		if ($cpny2==0)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '413$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '413%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
	
			$int1=revsign($rowA[0]);
	
			$samt=$int1;
		}
		else
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '413$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '413%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '413$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '413%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			$int1=revsign($rowA[0])+revsign($rowB[0]);
	
			$samt=$int1;
		}

		if (count($advrbarray) < $topar)
		{
			$amt=formatinteger($samt);
			$advrbarray[]=$samt;
		}
		else
		{
			$amt=0;
			$advrbarray[]=0;
		}
		echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$svcs=formatinteger(array_sum($advrbarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=currency(array_sum($advrbarray)/array_sum($pdarray));
	}
	
	//$savp=formatinteger(array_sum($advrbarray)/array_sum($pdarray));
	$mavg=formatinteger(array_sum($advrbarray)/$topar);
	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$svcs</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$savp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$mavg</td>\n";
}

function rebate_income()
{
	global $dtarray,$rbarray,$pdarray,$topar;

	$dtarray=setdatearray();

	$cpny =$_REQUEST['cpny'];
	$mdiv =$_REQUEST['mdiv'];
	$div  =$_REQUEST['division'];
	$retext=substr($_REQUEST['cpny'],4);
	
	$qtext=spec_code_qtext();

	$pd="";
	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$d="";
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($mdiv==1)
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '412$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '412%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}

		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_row($resA);

		$int1=revsign($rowA[0]);

		$samt=$int1;
		if (count($rbarray) < $topar)
		{
			$amt=formatinteger($samt);
			$rbarray[]=$samt;
		}
		else
		{
			$amt=0;
			$rbarray[]=0;
		}

		if ($amt!=0)
		{
			$d="<font title=\"Not included in final Calc\">*</font>";
			$pd=$d;
		}
		
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$d $amt</td>\n";
	}

	$svcs=formatinteger(array_sum($rbarray));
	
	/*
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($totharray)/array_sum($pdarray));
	}
	*/
	
	//$pavg_calc  =formatinteger(array_sum($totharray)/array_sum($pdarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=formatinteger(array_sum($rbarray)/array_sum($pdarray));
	}
	//$savp=formatinteger(array_sum($rbarray)/array_sum($pdarray));
	$mavg=formatinteger(array_sum($rbarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pd $svcs</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pd $savp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pd $mavg</td>\n";
	//unset($rbarray);
}

function other_income($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$otharray,$pdarray,$topar;

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($cpny2==0)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '402$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '402%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '418$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '418%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			$int1=revsign($rowA[0])+revsign($rowB[0]);
		}
		else
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '402$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '402%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
			
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '402$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '402%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
			
			if ($mdiv==1)
			{
				$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '418$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '418%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_row($resC);
			
			if ($mdiv==1)
			{
				$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '418$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '418%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_row($resD);
	
			$int1=revsign($rowA[0])+revsign($rowB[0])+revsign($rowC[0])+revsign($rowD[0]);
		}
		
		if (count($otharray) < $topar)
		{
			$amt=formatinteger($int1);
			$otharray[]=$int1;
		}
		else
		{
			$amt=0;
			$otharray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$svcs=formatinteger(array_sum($otharray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=formatinteger(array_sum($otharray)/array_sum($pdarray));
	}
	//$savp=formatinteger(array_sum($otharray)/array_sum($pdarray));
	$mavg=formatinteger(array_sum($otharray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$svcs</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$savp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$mavg</td>\n";
}

function discounts($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$darray,$pdarray,$topar;

	$dtarray=setdatearray();
	$mdiv =$_REQUEST['mdiv'];
	
	$qtext=spec_code_qtext();

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];
		
		if ($cpny2==0)
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '414$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '414%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
	
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '415$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '415%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			$int1=revsign($rowA[0]);
			$int2=revsign($rowB[0]);
	
			$samt=$int1+$int2;
		}
		else
		{
			if ($mdiv==1)
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '414$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '414%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_row($resA);
	
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '415$div%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '415%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
	
			if ($mdiv==1)
			{
				$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '414$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryC = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '414%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_row($resC);
	
			if ($mdiv==1)
			{
				$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '415$div2%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
			else
			{
				$qryD = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '415%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
			}
	
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_row($resD);
			
			$int1=revsign($rowA[0]);
			$int2=revsign($rowB[0]);
			$int3=revsign($rowC[0]);
			$int4=revsign($rowD[0]);
	
			$samt=$int1+$int2+$int3+$int4;
		}
		
		if (count($darray) < $topar)
		{
			$amt=formatinteger($samt);
			$darray[]=$samt;
		}
		else
		{
			$amt=0;
			$darray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$amt</td>\n";
	}

	$svcs=formatinteger(array_sum($darray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$savp=0;
	}
	else
	{
		$savp=formatinteger(array_sum($darray)/array_sum($pdarray));
	}
	
	//$savp=formatinteger(array_sum($darray)/array_sum($pdarray));
	$mavg=formatinteger(array_sum($darray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$svcs</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$savp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$mavg</td>\n";
}

function overhead($cpny,$div)
{
	global $dtarray,$ovarray,$pdarray,$topar;

	$dtarray=setdatearray();
	$sdate	=current($dtarray);
	//print_r($sdate);
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	if (!empty($_REQUEST['specYY']) && $_REQUEST['specYY']==1)
	{
		$specYY=$_REQUEST['specYY'];
	}
	else
	{
		$specYY=0;
	}
	
	$qtext=spec_code_qtext();
	
	//echo "CO:".$cpny."<br>";
	//echo "DI:".$div."<br>";
	//echo "MI:".$mdiv."<br>";

	$exacct=array(729,741);

	if ($mdiv==1)
	{
		//$qry = "SELECT AccountNumber,AccountDescription FROM $cpny..GL1_Accounts WHERE AccountNumber LIKE '7%$div%' AND AccountNumber != '729300000' ORDER BY AccountNumber ASC;";
		$qry = "SELECT AccountNumber,AccountDescription FROM MAS_".$cpny."..GL1_Accounts WHERE AccountNumber LIKE '7%".$div."0%' OR AccountNumber LIKE '9[0-9][0-9]".$div."%' ORDER BY AccountNumber ASC;";
	}
	else
	{
		//$qry = "SELECT AccountNumber,AccountDescription FROM $cpny..GL1_Accounts WHERE AccountNumber LIKE '7%' AND AccountNumber != '729300000' ORDER BY AccountNumber ASC;";
		$qry = "SELECT AccountNumber,AccountDescription FROM MAS_".$cpny."..GL1_Accounts WHERE AccountNumber LIKE '7%' OR AccountNumber LIKE '9%' ORDER BY AccountNumber ASC;";
	}

	$res = mssql_query($qry);

	//echo $qry."<br><br>";

	$ccnt=0;
	while ($row = mssql_fetch_row($res))
	{
		unset($pre_ovarray);
		foreach ($dtarray as $dtmonth => $subdtarray)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];

			//$qryApre = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			$qryApre = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$sdate[0]."' AND '".$dtconst1."';";
			$resApre = mssql_query($qryApre);
			$rowApre = mssql_fetch_row($resApre);

			//echo $qryApre."<br>";
			$pre_ovarray[]=$rowApre[0];
		}
		$pre_sov=array_sum($pre_ovarray);
		//$pre_sov=1;

		if($pre_sov!=0)
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbg = "wh_und";
			}
			else
			{
				$tbg = "gray_und";
			}
			
			if (is_array($ovarray))
			{
				$ovarray=array();
			}
			//unset($ovarray);
			$d="";
			$pd="";
			echo "                              <tr>\n";
			echo "                                 <td class=\"".$tbg."\" width=\"160px\" align=\"left\" NOWRAP><font size=\"1\">".substr(ucwords(strtolower($row[1])),0,25)."</td>\n";
			echo "                                 <td class=\"".$tbg."\" align=\"center\" NOWRAP><font size=\"1\">(".$cpny."-".substr($row[0],0,3).")</td>\n";
			//echo "                                 <td class=\"".$tbg."\" align=\"center\" NOWRAP><font size=\"1\">(XXX-".substr($row[0],0,3).")</td>\n";

			foreach ($dtarray as $dtmonth => $subdtarray)
			{
				$dtconst0=$subdtarray[0];
				$dtconst1=$subdtarray[1];

				$tpsum2=0;
				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);

				//if ($row[0]=="728250000")
				//{
				//	echo $qryA."<br>";
				//}

				$amt=formatinteger($rowA[0]);
				if (count($ovarray) < $topar)
				{
					$ovarray[]=$rowA[0];

					if (in_array(substr($row[0],0,3),$exacct))
					{
						$d="*";
						$pd="*";
					}
				}
				else
				{
					//$amt=0;
					$ovarray[]=0;
					//$d="";
				}

				if (isset($amt) && $amt!=0)
				{
					echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$d." <a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=ov&c=".$cpny."&a=".$row[0]."&d0=".$dtconst0."&d1=".$dtconst1."&spY=".$specYY."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$amt."</a></td>\n";
				}
				else 
				{
					echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$d." ".$amt."</td>\n";
				}
				//echo "                                 <td class=\"und\" width=\"60px\" align=\"right\"><font size=\"1\">".$d." ".$amt."</td>\n";
			}

			$sov=formatinteger(array_sum($ovarray));
			
			if (!is_array($pdarray) || array_sum($pdarray)==0)
			{
				$sav=0;
			}
			else
			{
				$sav=formatinteger(array_sum($ovarray)/array_sum($pdarray));
			}
			
				
			$mag=formatinteger(array_sum($ovarray)/$topar);
			
			//getYTDrange
			$YTDr	=getYTDrange($dtarray,$topar);			
			
			echo "                                 <td class=\"".$tbg."\" width=\"20px\" align=\"center\">&nbsp</td>\n";
			
			if ($sov!=0)
			{
				echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$pd." <a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=ov&c=".$cpny."&a=".$row[0]."&d0=".$YTDr[0]."&d1=".$YTDr[1]."&spY=".$specYY."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$sov."</a></td>\n";
			}
			else 
			{
				echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$pd." ".$sov."</td>\n";
			}
			
			echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><font size=\"1\">".$pd." ".$sav."</td>\n";
			echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><font size=\"1\">".$pd." ".$mag."</td>\n";
			echo "                              </tr>\n";
		}
	}
}

function total_overhead($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$tovarray,$pdarray,$topar;
	
	//error_reporting(E_ALL);

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	$qtext	=spec_code_qtext();
	//$qtext="";

	echo "                              <tr>\n";
	echo "                                 <td width=\"160px\" align=\"right\"><font size=\"1\">Total Overhead</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($mdiv==1)
		{
			$qryAa = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '7[0-9][0-9]".$div."%' AND AccountNumber NOT LIKE '729%".$div."%' AND AccountNumber NOT LIKE '741%".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
		}
		else
		{
			$qryAa = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '7%' AND AccountNumber NOT LIKE '729%' AND AccountNumber NOT LIKE '741%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
		}

		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_row($resAa);
		
		if ($mdiv==1)
		{
			$qryAb = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '9[0-9][0-9]".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
		}
		else
		{
			$qryAb = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '9%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
		}

		$resAb = mssql_query($qryAb);
		$rowAb = mssql_fetch_row($resAb);
		
		//echo "Aa: ".$qryAa."<br>";
		//echo "Ab: ".$qryAb."<br>";
		
		$setA=$rowAa[0]+$rowAb[0];
		
		if ($cpny2!=0)
		{
			if ($mdiv==1)
			{
				$qryBa = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '7[0-9][0-9]%".$div2."%' AND AccountNumber NOT LIKE '729%".$div2."%' AND AccountNumber NOT LIKE '741%".$div."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
			else
			{
				$qryBa = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '7%' AND AccountNumber NOT LIKE '729%' AND AccountNumber NOT LIKE '741%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
	
			$resBa = mssql_query($qryBa);
			$rowBa = mssql_fetch_row($resBa);
			
			if ($mdiv==1)
			{
				$qryBb = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '9[0-9][0-9]%".$div2."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
			else
			{
				$qryBb = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '9%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
	
			$resBb = mssql_query($qryBb);
			$rowBb = mssql_fetch_row($resBb);
			
			$setB	=$rowBa[0]+$rowBb[0];
		}
		else
		{
			$setB	=0;
		}		

		if (count($tovarray) < $topar)
		{
			$amt=formatinteger(($setA+$setB));
			$tovarray[]=$setA+$setB;
		}
		else
		{		
			$amt=0;
			$tovarray[]=0;
		}
		//echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt." (".count($tovarray) .") (".$topar.")</td>\n";
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
	}

	$tsov=formatinteger(array_sum($tovarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$tsav=0;
	}
	else
	{
		$tsav=formatinteger(array_sum($tovarray)/array_sum($pdarray));
	}
	
	//$tsav=formatinteger(array_sum($tovarray)/array_sum($pdarray));
	$tmag=formatinteger(array_sum($tovarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tsov."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tsav."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tmag."</td>\n";
	echo "                              </tr>\n";
}

function indirect_costs($cpny,$div)
{
	global $dtarray,$icarray,$ext_icarray,$pdarray,$topar;
	$dtarray	=setdatearray();
	$d="";
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);

	if (!empty($_REQUEST['specYY']) && $_REQUEST['specYY']==1)
	{
		$specYY=$_REQUEST['specYY'];
	}
	else
	{
		$specYY=0;
	}

	$qtext=spec_code_qtext();

	if ($mdiv==1)
	{
		$qry   = "SELECT AccountNumber,AccountDescription FROM MAS_".$cpny."..GL1_Accounts WHERE AccountNumber LIKE '6[0-9][0-9]$div%' ORDER BY AccountNumber ASC;";
	}
	else
	{
		$qry   = "SELECT AccountNumber,AccountDescription FROM MAS_".$cpny."..GL1_Accounts WHERE AccountNumber LIKE '6[0-9][0-9]%' ORDER BY AccountNumber ASC;";
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	$ccnt=0;
	while ($row = mssql_fetch_row($res))
	{
		unset($pre_icarray);
		$pre_sic=0;
		foreach ($dtarray as $dtmonth => $subdtarray)
		{
			$dtconst0=$subdtarray[0];
			$dtconst1=$subdtarray[1];

			$qryApre = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			$resApre = mssql_query($qryApre);
			$rowApre = mssql_fetch_row($resApre);

			$pre_icarray[]=$rowApre[0];
		}
		
		foreach ($pre_icarray as $np => $nv)
		{
			if ($nv!=0)
			{
				$pre_sic++;
			}
		}
		//$pre_sic=array_sum($pre_icarray);

		if ($pre_sic!=0)
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbg = "wh_und";
			}
			else
			{
				$tbg = "gray_und";
			}	
			
			if (is_array($icarray))
			{
				$icarray=array();
			}
			echo "                              <tr>\n";
			echo "                                 <td class=\"".$tbg."\" width=\"160px\" align=\"left\" NOWRAP><font size=\"1\">".substr(ucwords(strtolower($row[1])),0,25)."</td>\n";
			echo "                                 <td class=\"".$tbg."\" align=\"center\" NOWRAP><font size=\"1\">(".$cpny."-".substr($row[0],0,3).")</td>\n";
			//echo "                                 <td class=\"".$tbg."\" align=\"center\" NOWRAP><font size=\"1\">(XXX-".substr($row[0],0,3).")</td>\n";

			foreach ($dtarray as $dtmonth => $subdtarray)
			{
				
				$dtconst0=$subdtarray[0];
				$dtconst1=$subdtarray[1];

				$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber='".$row[0]."' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
				$resA = mssql_query($qryA);
				$rowA = mssql_fetch_row($resA);

				$amt=formatinteger($rowA[0]);
				if (count($icarray) < $topar)
				{
					//$amt=formatinteger($rowA[0]);
					$icarray[]=$rowA[0];
				}
				else
				{
					//$amt=0;
					$icarray[]=0;
				}
				
				if (isset($amt) && $amt!=0)
				{
					echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$d." <a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=ov&c=".$cpny."&a=".$row[0]."&d0=".$dtconst0."&d1=".$dtconst1."&spY=".$specYY."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$amt."</a></td>\n";
				}
				else 
				{
					echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$d." ".$amt."</td>\n";
				}
			}
			$sic=formatinteger(array_sum($icarray));
			
			if (!is_array($pdarray) || array_sum($pdarray)==0)
			{
				$sav=0;
			}
			else
			{
				$sav=formatinteger(array_sum($icarray)/array_sum($pdarray));
			}
			//$sav=formatinteger(array_sum($icarray)/array_sum($pdarray));
			$mag=formatinteger(array_sum($icarray)/$topar);

			//echo "                                 <td class=\"und\" width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
			//echo "                                 <td class=\"und\" width=\"60px\" align=\"right\"><font size=\"1\">".$sic."</td>\n";
			
			//getYTDrange
			$YTDr	=getYTDrange($dtarray,$topar);			
			
			echo "                                 <td class=\"".$tbg."\" width=\"20px\" align=\"center\">&nbsp</td>\n";
			
			if ($sic!=0)
			{
				echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><a href=\"subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=ov&c=".$cpny."&a=".$row[0]."&d0=".$YTDr[0]."&d1=".$YTDr[1]."&spY=".$specYY."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".$sic."</a></td>\n";
			}
			else 
			{
				echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\">".$sic."</td>\n";
			}
			
			echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><font size=\"1\">".$sav."</td>\n";
			echo "                                 <td class=\"".$tbg."\" width=\"60px\" align=\"right\"><font size=\"1\">".$mag."</td>\n";
			echo "                              </tr>\n";
		}
	}
}

function total_indirect_costs($cpny,$div,$cpny2,$div2)
{
	global $dtarray,$ticarray,$pdarray,$topar;

	$dtarray	=setdatearray();
	$mdiv 	=$_REQUEST['mdiv'];
	$retext	=substr($_REQUEST['cpny'],4);
	
	$qtext=spec_code_qtext();

	echo "                              <tr>\n";
	echo "                                 <td width=\"160px\" align=\"right\"><font size=\"1\">Total Indirect Costs</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	foreach ($dtarray as $dtmonth => $subdtarray)
	{
		$dtconst0=$subdtarray[0];
		$dtconst1=$subdtarray[1];

		if ($mdiv==1)
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '6[0-9][0-9]".$div."%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}
		else
		{
			$qryA = "SELECT SUM(PostingAmount) FROM MAS_".$cpny."..GL5_DetailPosting WHERE AccountNumber LIKE '6[0-9][0-9]%' ".$qtext." AND TransactionDate BETWEEN '$dtconst0' AND '$dtconst1';";
		}

		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_row($resA);
		
		// Added for Company Joins
		if ($cpny2!=0)
		{
			if ($mdiv==1)
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '6[0-9][0-9]".$div2."%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
			else
			{
				$qryB = "SELECT SUM(PostingAmount) FROM MAS_".$cpny2."..GL5_DetailPosting WHERE AccountNumber LIKE '6[0-9][0-9]%' ".$qtext." AND TransactionDate BETWEEN '".$dtconst0."' AND '".$dtconst1."';";
			}
	
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_row($resB);
			
			$setB=$rowB[0];
		}
		else
		{
			$setB=0;
		}

		if (count($ticarray) < $topar)
		{
			$amt=formatinteger(($rowA[0]+$setB));
			$ticarray[]=$rowA[0]+$setB;
		}
		else
		{
			$amt=0;
			$ticarray[]=0;
		}
		echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$amt."</td>\n";
	}

	$tsic=formatinteger(array_sum($ticarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$tsav=0;
	}
	else
	{
		$tsav=formatinteger(array_sum($ticarray)/array_sum($pdarray));
	}
	
	//$tsav=formatinteger(array_sum($ticarray)/array_sum($pdarray));
	$tmag=formatinteger(array_sum($ticarray)/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\"></td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tsic."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tsav."</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">".$tmag."</td>\n";
	echo "                              </tr>\n";
}

function indirect_costs_per_dig()
{
	global $dtarray,$ticarray,$ticpdarray,$pdarray,$topar;

	if (is_array($ticarray))
	{
		foreach ($ticarray as $arraykey => $arrayvalue)
		{
			if ($pdarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$ticarray[$arraykey] / $pdarray[$arraykey];
			}
			if (count($ticpdarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$ticpdarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$ticpdarray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}

	//$tcalc_gp   =formatinteger(array_sum($ticpdarray)/arrayavg_non_0($ticpdarray));
	//$pavg_calc  =formatinteger(array_sum($ticpdarray)/array_sum($pdarray));
	//$moavg_calc =formatinteger(array_sum($ticpdarray)/$_REQUEST['prd']);
	

	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp=array_sum($ticarray)/array_sum($pdarray);
	}
	//$precalc_gp =array_sum($ticarray)/array_sum($pdarray);
	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	}
	//$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	$moavg_calc =formatinteger($precalc_gp/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	//echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function overhead_costs_per_dig()
{
	global $dtarray,$tovarray,$tovpdarray,$pdarray,$topar;

	if (is_array($tovarray))
	{
		foreach ($tovarray as $arraykey => $arrayvalue)
		{
			if ($pdarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$tovarray[$arraykey] / $pdarray[$arraykey];
			}
			if (count($tovpdarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$tovpdarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$tovpdarray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}

	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp =array_sum($tovarray)/array_sum($pdarray);
	}
	//$precalc_gp =array_sum($tovarray)/array_sum($pdarray);
	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	}
	//$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	$moavg_calc =formatinteger($precalc_gp/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	//echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function total_indirect_overhead()
{
	global $ticarray,$tovarray,$tioarray,$pdarray,$topar;

	echo "                              <tr>\n";
	echo "                                 <td width=\"160px\" align=\"right\"><font size=\"1\">Total Ind & Over Costs</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	if (is_array($ticarray))
	{
		foreach ($ticarray as $arraykey => $arrayvalue)
		{
			$sunfmt=$ticarray[$arraykey]+$tovarray[$arraykey];
			if (count($tioarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$tioarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$tioarray[]=0;
			}
			echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}

	$tcalc_gp   =formatinteger(array_sum($tioarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($tioarray)/array_sum($pdarray));
	}
	
	//$pavg_calc  =formatinteger(array_sum($tioarray)/array_sum($pdarray));
	$moavg_calc =formatinteger(array_sum($tioarray)/$topar);
	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function net_pos_poolsdug()
{
	global $gpcarray,$tioarray,$nppdarray,$totharray,$pdarray,$open_ar,$topar;

	//echo $totharray[10]."<br>";
	echo "                              <tr>\n";
	echo "                                 <td width=\"160px\" align=\"right\"><font size=\"1\">Net Position (Pools Dug)</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	if (is_array($gpcarray))
	{
		$opp=0;
		foreach ($gpcarray as $arraykey => $arrayvalue)
		{
			if ($open_ar[$opp] != 0)
			{
				$sunfmt=($gpcarray[$arraykey]+$totharray[$arraykey])-$tioarray[$arraykey];
				//echo $totharray[$arraykey]."<br>";
			}
			else
			{
				$sunfmt=0;
			}
			if (count($nppdarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$nppdarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$nppdarray[]=0;
			}
			echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$calc_io."</td>\n";
			$opp++;
		}
	}

	$tcalc_gp   =formatinteger(array_sum($nppdarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($nppdarray)/array_sum($pdarray));
	}
	
	//$pavg_calc  =formatinteger(array_sum($nppdarray)/array_sum($pdarray));
	$moavg_calc =formatinteger(array_sum($nppdarray)/$topar);
	echo "                                 <td class=\"dbl_und\" width=\"20px\" align=\"center\">&nbsp</td>\n";
	echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$tcalc_gp."</td>\n";
	echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$pavg_calc."</td>\n";
	echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">".$moavg_calc."</td>\n";
}

function net_pos_perccomp()
{
	global $gparray,$tioarray,$totharray,$nppcarray,$pdarray,$open_ar,$topar;

	echo "                              <tr>\n";
	echo "                                 <td width=\"160px\" align=\"right\"><font size=\"1\">Net Position (% Comp)</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	if (is_array($gparray))
	{
		$opp=0;
		foreach ($gparray as $arraykey => $arrayvalue)
		{
			if ($open_ar[$opp] != 0)
			{
				$sunfmt=($gparray[$arraykey]+$totharray[$arraykey])-$tioarray[$arraykey];
			}
			else
			{
				$sunfmt=0;
			}
			
			if (count($nppcarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$nppcarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$nppcarray[]=0;
			}
			echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
			$opp++;
		}
	}

	$tcalc_gp   =formatinteger(array_sum($nppcarray));
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger(array_sum($nppcarray)/array_sum($pdarray));
	}
	//$pavg_calc  =formatinteger(array_sum($nppcarray)/array_sum($pdarray));
	$moavg_calc =formatinteger(array_sum($nppcarray)/$topar);

	echo "                                 <td class=\"dbl_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td class=\"dbl_und\" width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function avg_contract()
{
	global $dtarray,$vcsarray,$avcsarray,$pdarray,$topar;

	if (is_array($vcsarray))
	{
		foreach ($vcsarray as $arraykey => $arrayvalue)
		{
			if ($pdarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$vcsarray[$arraykey] / $pdarray[$arraykey];
			}

			if (count($avcsarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$avcsarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$avcsarray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp =array_sum($vcsarray)/array_sum($pdarray);
	}
	//$precalc_gp =array_sum($vcsarray)/array_sum($pdarray);
	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	}
		
	//$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	$moavg_calc =formatinteger($precalc_gp/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	//echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function avg_dir_cost()
{
	global $dtarray,$dcarray,$adcarray,$pdarray,$topar;

	if (is_array($dcarray))
	{
		foreach ($dcarray as $arraykey => $arrayvalue)
		{
			if ($pdarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$dcarray[$arraykey] / $pdarray[$arraykey];
			}
			if (count($adcarray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$adcarray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$adcarray[]=0;
			}
			echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp =array_sum($dcarray)/array_sum($pdarray);
	}
	
	//$precalc_gp =array_sum($dcarray)/array_sum($pdarray);
	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	}
	//$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	$moavg_calc =formatinteger($precalc_gp/$topar);
	echo "                                 <td class=\"gray_und\" width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	//echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td class=\"gray_und\" width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function avg_gp()
{
	global $dtarray,$avcsarray,$adcarray,$avggparray,$gpcarray,$pdarray,$topar;

	if (is_array($avcsarray))
	{
		foreach ($avcsarray as $arraykey => $arrayvalue)
		{
			if ($pdarray[$arraykey]==0)
			{
				$sunfmt=0;
			}
			else
			{
				$sunfmt=$avcsarray[$arraykey] - $adcarray[$arraykey];
			}
			if (count($avggparray) < $topar)
			{
				$calc_io=formatinteger($sunfmt);
				$avggparray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$avggparray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp =array_sum($gpcarray)/array_sum($pdarray);
	}
	//$precalc_gp =array_sum($gpcarray)/array_sum($pdarray);
	$tcalc_gp   =formatinteger($precalc_gp);
	
	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	}
	
	//$pavg_calc  =formatinteger($precalc_gp/array_sum($pdarray));
	$moavg_calc =formatinteger($precalc_gp/$topar);
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$tcalc_gp</td>\n";
	//echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$pavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$moavg_calc</td>\n";
}

function avg_perc_gp()
{
	global $dtarray,$avcsarray,$vcsarray,$adcarray,$avgpgparray,$avggparray,$gpcarray,$pdarray,$topar;

	if (is_array($avcsarray))
	{
		foreach ($avcsarray as $arraykey => $arrayvalue)
		{
			if ($pdarray[$arraykey]==0)
			{
				$subsunfmt=0;
				$sunfmt=$subsunfmt;
			}
			else
			{
				if ($avcsarray[$arraykey]==0)
				{
					$sunfmt=0;
				}
				else
				{
					$sunfmt=($avcsarray[$arraykey] - $adcarray[$arraykey]) / $avcsarray[$arraykey];
				}
			}
			if (count($avgpgparray) < $topar)
			{
				$calc_io=fixfloat($sunfmt)."%";
				$avgpgparray[]=$sunfmt;
			}
			else
			{
				$calc_io=0;
				$avgpgparray[]=0;
			}
			echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$calc_io</td>\n";
		}
	}
	/*
	$stcalc_gp    =(array_sum($avggparray)/arrayavg_non_0($avggparray)) / (array_sum($avcsarray)/arrayavg_non_0($avcsarray));
	$ftcalc_gp    =fixfloat($stcalc_gp)."%";
	$pavg_calc    =((array_sum($avggparray)/arrayavg_non_0($avggparray))/array_sum($pdarray)) / ((array_sum($avcsarray)/arrayavg_non_0($avcsarray))/array_sum($pdarray));
	$ftpavg_calc  =fixfloat($pavg_calc)."%";
	*/
	
	if (!is_array($vcsarray) || array_sum($vcsarray)==0)
	{
		$precalc_gp=0;
	}
	else
	{
		$precalc_gp =array_sum($gpcarray)/array_sum($vcsarray);
	}
	//$precalc_gp =array_sum($gpcarray)/array_sum($vcsarray);
	$ftcalc_gp   =fixfloat(round($precalc_gp,2))."%";

	if (!is_array($pdarray) || array_sum($pdarray)==0)
	{
		$pavg_calc=0;
	}
	else
	{
		$pavg_calc   =(array_sum($gpcarray)/array_sum($pdarray))/(array_sum($vcsarray)/array_sum($pdarray));
	}

	//$pavg_calc   =(array_sum($gpcarray)/array_sum($pdarray))/(array_sum($vcsarray)/array_sum($pdarray));
	$ftpavg_calc =fixfloat(round($pavg_calc,2))."%";

	if (!is_array($vcsarray) || array_sum($vcsarray)==0)
	{
		$moavg_calc=0;
	}
	else
	{
		$moavg_calc  =(array_sum($gpcarray)/$topar)/(array_sum($vcsarray)/$topar);
	}
	//$moavg_calc  =(array_sum($gpcarray)/$_REQUEST['prd'])/(array_sum($vcsarray)/$_REQUEST['prd']);
	$ftmoavg_calc=fixfloat(round($moavg_calc,2))."%";
	echo "                                 <td width=\"20px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"center\"><font size=\"1\">&nbsp</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$ftcalc_gp</td>\n";
	//echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$ftpavg_calc</td>\n";
	echo "                                 <td width=\"60px\" align=\"right\"><font size=\"1\">$ftmoavg_calc</td>\n";
}

function arrayavg_non_0($numberArray)
{
	$sum = 0;
	if (is_array($numberArray))
	{
		foreach ($numberArray as $n => $v)
		{
			if ($v!=0)
			{
				$sum++;
			}
		}
	}
	return $sum;
}

function gmreptjoin()
{
	// Create Side Connect to JEST System
	$hostname = "192.168.100.45";
	$username = "jest_ro";
	$password = "date1995";
	$dbname   = "jest";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
	$qry1 = "SELECT gmrjoin FROM offices WHERE parentmcode=".$_REQUEST['cpny'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	
	//echo $qry1 ."<br>";
	//echo $row1['gmrjoin'];
	//echo $nrow1;
	
	if ($row1['gmrjoin']!='0' && $nrow1 > 0)
	{
		echo "<b>Join:</b> ";
		
		if (preg_match("/,/i",$row1['gmrjoin']))
		{
			$sel_ar=explode(",",$row1['gmrjoin']);
			//echo "Array";
		}
		else
		{
			$sel_ar=array($row1['gmrjoin']);
			//echo "Not Array";
		}
		
		echo "<select name=\"gmrjoin\">\n";
		echo "<option value=\"0\">None</option>\n";
		
		/*foreach ($sel_ar as $n => $v)
		{
			$ev=explode(":",$v);
			if (isset($_REQUEST['gmrjoin']) && $_REQUEST['gmrjoin']==$v)
			{
				echo "<option value=\"".$v."\" SELECTED>Comp:".$ev[0]." Div:".$ev[1]."</option>\n";
			}
			else
			{
				echo "<option value=\"".$v."\">Comp:".$ev[0]." Div:".$ev[1]."</option>\n";
			}
		}*/
		
		echo "</select>\n";
		
		//echo $row1['gmrjoin'];
	}
	
	
	// Return Connect to MAS System
	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
}

function preopstate()
{
	error_reporting(E_ALL);
	$qry1 = "SELECT securityid,gmreports FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	//print_r($row1);

	if ($row1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to view this Report');
	}
	
	//gmreptjoin();

	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	$dtarray	=setdatearray();
	$cpny		=$_REQUEST['cpny'];
	$mdiv		=$_REQUEST['mdiv'];
	$division=$_REQUEST['division'];

	if ($mdiv!=1)
	{
		//$retext=substr($cpny,4);
		$retext=$cpny;
	}
	else
	{
		$retext=$division;
	}

	$cdate=date("m/d/Y", time());

	$qryA = "SELECT CompanyName,CompanyCode FROM MAS_".$cpny."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$cpny."');";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);
	
	$qryAa = "SELECT Description FROM MAS_".$cpny."..ARB_DivisionMasterfile WHERE Division='".$division."';";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);
	
	//echo $qryA."<br>";

	if ($mdiv==1)
	{
		$qryB = "SELECT DeptNumber,DeptName FROM MAS_".$cpny."..GL7_Department WHERE DeptNumber LIKE '".$division."00%';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_row($resB);
	}

	echo "                  <table class=\"outer\" width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"center\">\n";
	echo "                           <table bgcolor=\"#d3d3d3\" width=\"100%\" border=0>\n";
	echo "                              <tr>\n";
	echo " 									<td align=\"left\" NOWRAP><b>Company</b></td>\n";
	echo "									<td align=\"left\" NOWRAP><b>Division</b></td>\n";
	echo "									<td align=\"center\" NOWRAP><b>MAS</b></td>\n";
	echo "									<td align=\"center\" NOWRAP><b>Div</b></td>\n";
	echo "									<td align=\"center\"><font size=\"1\"><b>Remodel Div</b></font></td>\n";
	echo "									<td align=\"center\"><font size=\"1\"><b>Operating Statement</b></font></td>\n";
	echo "									<td align=\"center\"><font size=\"1\"></td>\n";
	echo "									<td align=\"center\"><font size=\"1\"><b>Fiscal Year</b></font></td>\n";
	echo "									<td align=\"center\"><font size=\"1\"><b>Print</b></font></td>\n";
	echo "									<td align=\"center\"></td>\n";
	echo "									<td align=\"center\"></td>\n";
	echo "                              </tr>\n";
	
	echo "                              	<form target=\"_top\" method=\"post\">\n";
	echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "									<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
	echo "									<input type=\"hidden\" name=\"subq\" value=\"opstate\">\n";
	echo "									<input type=\"hidden\" name=\"cpny\" value=\"".$cpny."\">\n";
	echo "									<input type=\"hidden\" name=\"mdiv\" value=\"".$mdiv."\">\n";
	echo "									<input type=\"hidden\" name=\"prd\" value=\"12\">\n";
	echo "									<input type=\"hidden\" name=\"division\" value=\"".$division."\">\n";

	if ($mdiv=1)
	{
		echo "                                 <input type=\"hidden\" name=\"parent\" value=\"".$cpny."\">\n";
	}
	
	echo "                              <tr>\n";
	echo "									<td align=\"left\" NOWRAP>".$rowA[0]."</td>\n";
	echo "									<td align=\"left\" NOWRAP>".$rowAa['Description']."</b></td>\n";
	echo "									<td align=\"center\" NOWRAP>".$cpny."</td>\n";
	echo "									<td align=\"center\" NOWRAP>".$division."</td>\n";
	echo "									<td align=\"center\">\n";

	OS_remod_div_select($cpny,$division);

	echo "									</td>\n";
	echo "									<td align=\"center\"><font size=\"1\">".$cdate."</font></td>\n";
	echo "									<td align=\"center\">\n";
	
	//spec_code_YY();

	echo "									</td>\n";
	echo "									<td align=\"center\">\n";
	
	yearselect();

	echo "									</td>\n";
	echo "									<td align=\"center\">\n";
	
	//gmreptjoin();
	if (isset($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "<input type=\"checkbox\" class=\"checkboxgry\" name=\"print\" value=\"1\" title=\"Check this box to show the OpStatement in print mode\" CHECKED>\n";
	}
	else
	{
		echo "<input type=\"checkbox\" class=\"checkboxgry\" name=\"print\" value=\"1\" title=\"Check this box to show the OpStatement in print mode\">\n";
	}
	
	echo "									</td>\n";
	echo "									<td align=\"center\">\n";
	
	
	
	echo "									</td>\n";
	echo "									<td align=\"center\" width=\"50px\">\n";
	echo "                                 		<input class=\"buttondkgry\" type=\"submit\" value=\"Select\">\n";
	echo "									</td>\n";
	echo "                              </form>\n";
	echo "                              </tr>\n";
	echo "                           </table>\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";

}

function setopenperiods($open_ar)
{
	$topar=0;
	foreach ($open_ar as $on => $ov)
	{
		if ($ov==1)
		{
			$topar++;	
		}
	}
	
	return $topar;
}

function opstate()
{
	error_reporting(E_ALL);
	//error_reporting(0);
	global $dtarray,$pdarray,$vcsarray,$dcarray,$icarray,$gparray,$ticarray,$tovarray,$tioarray,$nppdarray,$prrarray,$avg_per_pool,$open_ar,$topar,$reno_divs;

	$qry1 = "SELECT securityid,gmreports FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	if ($row1['gmreports'] != 1)
	{
		die('You do not have appropriate Access Rights to view this Report');
	}
	
	$hostname = "192.168.1.22";
	$username = "MAS_REPORTS";
	$password = "reports";
	$dbname   = "ZE_Stats";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");

	$dtarray	=@setdatearray();
	$currmonth	=@getcurrmonth();
	$currfisyr	=@getcurrfisyr(0);

	$cpny     =$_REQUEST['cpny'];
	$mdiv     =$_REQUEST['mdiv'];
	$division =$_REQUEST['division'];
	$div_ar[] =$_REQUEST['division'];
	
	if (isset($_REQUEST['remodeldiv']) && $_REQUEST['remodeldiv']!='N/A')
	{
		$rdivision =$_REQUEST['remodeldiv'];
		$div_ar[]	=$_REQUEST['remodeldiv'];
	}
	else
	{
		$rdivision ='XX';
	}

	//$retext=substr($cpny,4);
	if ($mdiv!=1)
	{
		$retext=substr($cpny,4);
	}
	else
	{
		$retext=$division;
	}

	// For Joining two Opstatements
	if (!empty($_REQUEST['gmrjoin']) && $_REQUEST['gmrjoin']!=0)
	{
		$pgmr	=explode(":",$_REQUEST['gmrjoin']);
		$gmrjoin=$pgmr[0];
		$gmrdiv	=$pgmr[1];
		
		if (isset($pgmr) && is_array($pgmr) && $pgmr[0]==$cpny)
		{			
			$qry1a = "SELECT * FROM ZE_Stats..divtocomp WHERE company=".$pgmr[0]." AND division='".$pgmr[1]."';";
			$res1a = mssql_query($qry1a);
			$row1a = mssql_fetch_array($res1a);
			
			$div_ar[]	=$pgmr[1];
			
			if ($row1a['remodeldiv']!='NA')
			{
				$div_ar[]	=$row1a['remodeldiv'];
			}
		}
	}
	else
	{
		$gmrjoin=0;
		$gmrdiv	=0;
	}
	
	$rr=array('425','426');
	$dc=array('525','526');
	$rr_r=array('430','431');
	$dc_r=array('530','531');
	$ms=array('401','416','429','440','450','460','461','465');
	$cm=array('575','560','516');
	
	$cdate=date("m/d/Y", time());

	$qryA = "SELECT CompanyName,CompanyCode FROM MAS_".$cpny."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$cpny."');";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);
	
	$qryAa = "SELECT Description FROM MAS_".$cpny."..ARB_DivisionMasterfile WHERE Division='".$division."';";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);

	if ($mdiv==1)
	{
		$qryB = "SELECT DeptNumber,DeptName FROM MAS_".$cpny."..GL7_Department WHERE DeptNumber=".$division."0000000;";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_row($resB);
	}
	
	$open_ar	=@setenablearray($cpny,$division,$_REQUEST['fisyr'],$dtarray);
	$topar 		=@setopenperiods($open_ar);
	$nspan		=18;
	$nwidth		="160px";
	//echo "                  <table width=\"100%\" border=\"0\">\n";
	echo "                  <table width=\"950px\" border=\"0\">\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"center\">\n";
	echo "                           <table class=\"outer\" bgcolor=\"#d3d3d3\" width=\"100%\">\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" NOWRAP><b>Company</b></td>\n";
	echo "									<td align=\"left\" NOWRAP><b>Division</b></td>\n";
	echo "									<td align=\"center\" NOWRAP><b>MAS</b></td>\n";
	echo "									<td align=\"center\" NOWRAP><b>Div</b></td>\n";
	echo "                                 <td align=\"center\"><b>Remodel Div</b></td>\n";
	echo "                                 <td align=\"center\"><b>Operating Statement</td>\n";
	echo "                                 <td align=\"center\"></td>\n";
	echo "                                 <td align=\"center\"><b>Fiscal Year</b></td>\n";
	echo "                                 <td align=\"center\"><b>Print</b></td>\n";
	echo "                                 <td align=\"center\"></td>\n";
	echo "                              </tr>\n";
	
	echo "                              <form action=\"index.php\" target=\"_top\" method=\"post\">\n";
	echo "								<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "								<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
	echo "                              <input type=\"hidden\" name=\"subq\" value=\"opstate\">\n";
	echo "                              <input type=\"hidden\" name=\"cpny\" value=\"".$cpny."\">\n";
	echo "                              <input type=\"hidden\" name=\"mdiv\" value=\"".$mdiv."\">\n";
	echo "                              <input type=\"hidden\" name=\"division\" value=\"".$division."\">\n";
	echo "                              <input type=\"hidden\" name=\"increbateinc\" value=\"1\">\n";
	echo "								<input type=\"hidden\" name=\"prd\" value=\"12\">\n";
	
	echo "                              <tr>\n";
	echo "                                  <td align=\"left\" NOWRAP>".$rowA[0]."</td>\n";
	echo "									<td align=\"left\" NOWRAP>".$rowAa['Description']."</b></td>\n";
	echo "									<td align=\"center\" NOWRAP>".$cpny."</td>\n";
	echo "									<td align=\"center\" NOWRAP>".$division."</b></td>\n";
	echo "                                  <td align=\"center\"><font size=\"1\">\n";

	@OS_remod_div_select($cpny,$division);

	echo "									</td>\n";
	echo "                                 <td align=\"center\"><font size=\"1\">".$cdate."</font></td>\n";
	echo "                                 <td align=\"center\">\n";
	
	//spec_code_YY();

	echo "									</td>\n";
	
	echo "                                 <td align=\"center\"><font size=\"1\">\n";

	yearselect();

	echo "									</td>\n";
	echo "                                 <td align=\"center\">\n";
	
	//gmreptjoin();
	if (isset($_REQUEST['print']) && $_REQUEST['print']==1)
	{
		echo "<input type=\"checkbox\" class=\"checkboxgry\" name=\"print\" value=\"1\" title=\"Check this box to show the OpStatement in print mode\" CHECKED>\n";
	}
	else
	{
		echo "<input type=\"checkbox\" class=\"checkboxgry\" name=\"print\" value=\"1\" title=\"Check this box to show the OpStatement in print mode\">\n";
	}
	
	echo "									</td>\n";
	echo "                                 <td align=\"center\">\n";	
	echo "									</td>\n";
	echo "                                 <td align=\"right\" width=\"50\">\n";
	echo "                                 	<input class=\"buttondkgry\" type=\"submit\" value=\"Select\">\n";
	echo "									</td>\n";
	echo "                              </form>\n";
	echo "                              </tr>\n";
	echo "                           </table>\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <table class=\"outer\" bgcolor=\"#d3d3d3\" width=\"100%\" border=0>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">&nbsp</td>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">&nbsp</td>\n";

	dateheaders();

	echo "                              </tr>\n";
	
	if ($_SESSION['officeid']==89)
	{
		echo "									<form action=\"index.php\" target=\"_top\" method=\"post\">\n";
		echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "									<input type=\"hidden\" name=\"call\" value=\"operating\">\n";
		echo "                                 <input type=\"hidden\" name=\"subq\" value=\"openableset\">\n";
		echo "                                 <input type=\"hidden\" name=\"cpny\" value=\"".$cpny."\">\n";
		echo "                                 <input type=\"hidden\" name=\"mdiv\" value=\"".$mdiv."\">\n";
		echo "                                 <input type=\"hidden\" name=\"division\" value=\"".$division."\">\n";
		echo "                                 <input type=\"hidden\" name=\"fisyr\" value=\"".$_REQUEST['fisyr']."\">\n";
		
		if ($gmrjoin!=0)
		{
			echo "                                 <input type=\"hidden\" name=\"gmrjoin\" value=\"".$gmrjoin."\">\n";
		}
		
		echo "                                 <input type=\"hidden\" name=\"prd\" value=\"".$_REQUEST['prd']."\">\n";
		echo "                              <tr>\n";
		echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\"></td>\n";
		echo "                                 <td align=\"center\">&nbsp</td>\n";
		
		op_enable_os($open_ar);

		echo "                              </tr>\n";
		echo "</form>";
	}
	
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" bgcolor=\"DarkGray\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP><font color=\"black\"><b>NEW BUILD</b></font></td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Pools Dug</td>\n";
	echo "                                 <td align=\"center\">&nbsp(Recog)</td>\n";

	OS_pools_dug($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Value of Cont Started</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	OS_val_con_start($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray_und\" width=\"".$nwidth."\" align=\"left\" valign=\"bottom\" NOWRAP><font size=\"1\">Direct Cost of Const</td>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">&nbsp</td>\n";

	OS_dir_cost_con($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Gr Prof on Contr Started</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	OS_calc_gp_contracts();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Equivalent Units</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	OS_equiv_units($cpny,$division,$gmrjoin,$gmrdiv,$rr);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Recognized Revenue</td>\n";
	echo "                                 <td align=\"center\">&nbsp(".$rr[0].",".$rr[1].")</td>\n";

	OS_reco_rev($cpny,$division,$gmrjoin,$gmrdiv,$rr);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Direct Cost of Sales</td>\n";
	echo "                                 <td align=\"center\">&nbsp(".$dc[0].",".$dc[1].")</td>\n";

	OS_dir_cost_sales($cpny,$division,$gmrjoin,$gmrdiv,$dc);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Sales Tax Paid</td>\n";
	echo "                                 <td align=\"center\">&nbsp(550)</td>\n";

	OS_sales_tax_paid($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Inv Adjustment</td>\n";
	echo "                                 <td align=\"center\">&nbsp(551)</td>\n";

	OS_inv_adjust($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray_und\" align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Cost on Closed Jobs</td>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">&nbsp(590)</td>\n";

	OS_cost_on_closed($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Gr Prof</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	OS_calc_total_gp();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP>GP Variance</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	OS_calc_gp_var();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td  align=\"left\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" bgcolor=\"DarkGray\" valign=\"bottom\" colspan=\"1\" NOWRAP><font color=\"black\"><b>REMODELS</b></font></td>\n";
	echo "                                 <td align=\"center\" bgcolor=\"DarkGray\" valign=\"bottom\" colspan=\"1\" NOWRAP><b>\n";
	
	//echo $_REQUEST['remodeldiv'];
	
	echo "								   </b></td>\n";
	echo "                                 <td align=\"left\" bgcolor=\"DarkGray\" valign=\"bottom\" colspan=\"".($nspan - 2)."\" NOWRAP></td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Recog Revenue - Remodels</td>\n";
	echo "                                 <td align=\"center\">&nbsp(".$rr_r[0].",".$rr_r[1].")</td>\n";

	OS_reco_rev_reno($cpny,$div_ar,$rr_r);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray_und\" align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Direct Cost Const - Remodels</td>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">&nbsp(".$dc_r[0].",".$dc_r[1].")</td>\n";

	OS_dir_cost_sales_reno($cpny,$div_ar,$dc_r);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Gr Prof on Remodels</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	OS_calc_gp_reno();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td  align=\"left\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" bgcolor=\"DarkGray\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP><font color=\"black\"><b>MISCELLANEOUS</b></font></td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Misc. Sales</td>\n";
	echo "                                 <td align=\"center\" title=\"";
	
	$mscnt=0;
	foreach ($ms as $msn => $msv)
	{
		if ($mscnt > 0)
		{
			echo ",";
		}
		
		echo $ms[$mscnt++];
	}
	
	echo "\" NOWRAP>(Mult-GL's)</td>\n";

	OS_misc_sales($cpny,$division,$gmrjoin,$gmrdiv,$ms);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Cost on Misc. Sales</td>\n";
	echo "                                 <td align=\"center\" title=\"";
	
	$cmcnt=0;
	foreach ($cm as $cmn => $cmv)
	{
		if ($cmcnt > 0)
		{
			echo ",";
		}
		
		echo $cm[$cmcnt++];
	}
	
	echo "\" NOWRAP>(Mult-GL's)</td>\n";

	OS_cost_misc($cpny,$division,$gmrjoin,$gmrdiv,$cm);

	/*echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Gr Prof on Misc Sales</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";*/

	OS_calc_gp_misc_sales();

	//echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Discounts</td>\n";
	echo "                                 <td align=\"center\">&nbsp(414,415)</td>\n";

	OS_discounts($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Other Income</td>\n";
	echo "                                 <td align=\"center\">&nbsp(402,418)</td>\n";

	OS_other_income($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Rebate Income</td>\n";
	echo "                                 <td align=\"center\">&nbsp(412)</td>\n";

	OS_rebate_income($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Forgiveness of Debt</td>\n";
	echo "                                 <td align=\"center\">&nbsp(411)</td>\n";

	OS_forgive_debt($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray_und\" align=\"left\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Vendor Rebate Inc</td>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">&nbsp(413)</td>\n";

	OS_vend_rebate_income($cpny,$division,$gmrjoin,$gmrdiv);

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP>Gr Prof on Misc Income</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	OS_total_other_income();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><b>Total Gross Profit</td>\n";
	echo "                                 <td align=\"center\">&nbsp</td>\n";

	OS_calc_total_gprof();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td bgcolor=\"DarkGray\" align=\"left\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP><font color=\"black\"><b>INDIRECT JOB COSTS</b></font></td>\n";
	echo "                              </tr>\n";

	OS_indirect_costs($_REQUEST['cpny'],$_REQUEST['division']);
	
	if ($gmrjoin!=0)
	{
		OS_indirect_costs($gmrjoin,$gmrdiv);
	}
	
	OS_total_indirect_costs($_REQUEST['cpny'],$_REQUEST['division'],$gmrjoin,$gmrdiv);

	echo "                              <tr>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td bgcolor=\"DarkGray\" align=\"left\" valign=\"bottom\" colspan=\"".$nspan."\" NOWRAP><font color=\"black\"><b>OVERHEAD</b></font></td>\n";
	echo "                              </tr>\n";

	OS_overhead($_REQUEST['cpny'],$_REQUEST['division']);
	
	if ($gmrjoin!=0)
	{
		OS_overhead($gmrjoin,$gmrdiv);
	}
	
	OS_total_overhead($_REQUEST['cpny'],$_REQUEST['division'],$gmrjoin,$gmrdiv);

	echo "                              <tr>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\" colspan=\"".$nspan."\"  NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";

	OS_total_indirect_overhead();

	echo "                              <tr>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\" colspan=\"".$nspan."\"  NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";

	OS_net_pos_poolsdug();

	//echo "                              <tr>\n";
	//echo "                                 <td align=\"center\" valign=\"bottom\" colspan=\"".$nspan."\"  NOWRAP>&nbsp</td>\n";
	//echo "                              </tr>\n";

	OS_net_pos_perccomp();

	echo "                              <tr>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\" colspan=\"".$nspan."\"  NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Ind Costs</td>\n";
	echo "                                 <td align=\"center\">(per Equiv Unit)</td>\n";

	OS_indirect_costs_per_dig();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray_und\" align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Overhead</td>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">(per Equiv Unit)</td>\n";

	OS_overhead_costs_per_dig();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"center\" valign=\"bottom\" colspan=\"".$nspan."\"  NOWRAP>&nbsp</td>\n";
	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Average Contract</td>\n";
	echo "                                 <td align=\"center\">(per Pools Dug)</td>\n";

	OS_avg_contract();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td class=\"gray_und\" align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Average Cost</td>\n";
	echo "                                 <td class=\"gray_und\" align=\"center\">(per Pools Dug)</td>\n";

	OS_avg_dir_cost();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Average Gross Profit</td>\n";
	echo "                                 <td align=\"center\">(per Pools Dug)</td>\n";

	OS_avg_gp();

	echo "                              </tr>\n";
	echo "                              <tr>\n";
	echo "                                 <td align=\"right\" width=\"".$nwidth."\" valign=\"bottom\" NOWRAP><font size=\"1\">Gross Profit %</td>\n";
	echo "                                 <td align=\"center\">(per Pools Dug)</td>\n";

	OS_avg_perc_gp();

	echo "                              </tr>\n";
	echo "                           </table>\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
}

function update_jcdata()
{
	if (isset($_REQUEST['jcid']))
	{
		foreach ($_REQUEST['jcid'] as $n => $v)
		{
			if (isset($_REQUEST['jcid_ct'.$v][0]) && isset($_REQUEST['jcid_ct'.$v][1]) && $_REQUEST['jcid_ct'.$v][0]!=$_REQUEST['jcid_ct'.$v][1])
			{
				//$qry0 .= "UPDATE [".$sqlsvr."].[ZE_Stats].[dbo].[JobClosings] SET contracttotal=CONVERT(money,'".$_REQUEST['jcid_ct'.$v][1]."') WHERE jcid=".$v.";";
				$qry0  = "UPDATE [ZE_Stats].[dbo].[JobClosings] SET contracttotal=CONVERT(money,'".$_REQUEST['jcid_ct'.$v][1]."'),updated=getdate(),updateby=".$_SESSION['securityid']." WHERE jcid=".$v.";";
				$res0  = mssql_query($qry0);
			}
			
			if (isset($_REQUEST['jcid_ac'.$v][0]) && isset($_REQUEST['jcid_ac'.$v][1]) && $_REQUEST['jcid_ac'.$v][0]!=$_REQUEST['jcid_ac'.$v][1])
			{
				$qry1  = "UPDATE [ZE_Stats].[dbo].[JobClosings] SET actualcost=CONVERT(money,'".$_REQUEST['jcid_ac'.$v][1]."'),updated=getdate(),updateby=".$_SESSION['securityid']." WHERE jcid=".$v.";";
				$res1  = mssql_query($qry1);
			}
			
			if (isset($_REQUEST['jcid_ec'.$v][0]) && isset($_REQUEST['jcid_ec'.$v][1]) && $_REQUEST['jcid_ec'.$v][0]!=$_REQUEST['jcid_ec'.$v][1])
			{
				$qry2  = "UPDATE [ZE_Stats].[dbo].[JobClosings] SET estimatecost=CONVERT(money,'".$_REQUEST['jcid_ec'.$v][1]."'),updated=getdate(),updateby=".$_SESSION['securityid']." WHERE jcid=".$v.";";
				$res2  = mssql_query($qry2);
			}
			
			if (isset($_REQUEST['jcid_cm'.$v][0]) && isset($_REQUEST['jcid_cm'.$v][1]) && $_REQUEST['jcid_cm'.$v][0]!=$_REQUEST['jcid_cm'.$v][1])
			{
				$qry3  = "UPDATE [ZE_Stats].[dbo].[JobClosings] SET commission=CONVERT(money,'".$_REQUEST['jcid_cm'.$v][1]."'),updated=getdate(),updateby=".$_SESSION['securityid']." WHERE jcid=".$v.";";
				$res3  = mssql_query($qry3);
			}
		}
	}
}

?>