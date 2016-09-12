<?php

function isValidUser($esid)
{
	// This function will validate the integrity of the User when processing requests via JMS Ajax
	$dbg=0;

	if (isset($_SESSION['securityid']) and $_SESSION['securityid']!=0)
	{
		$sessUserVar=md5($_SESSION['securityid'].'.'.substr($_SESSION['lname'],0,2));
		if (isset($_SESSION['SessHash']) and trim($_SESSION['SessHash'])===trim($sessUserVar))
		{
			if (isset($esid) and strlen($esid) > 5)
			{
				//include ('../connect_db.php');
				$qry0 = "SELECT securityid,officeid,login,slevel FROM security WHERE securityid=".$_SESSION['securityid'].";";
				$res0 = mssql_query($qry0);
				$nrow0= mssql_num_rows($res0);
				
				//echo '<br>'.$qry0.'<br>';
				
				if ($nrow0 == 1)
				{
					$row0 = mssql_fetch_array($res0);
					$slevs=explode(',',$row0['slevel']);
					
					if ($slevs[6] > 0)
					{
						if ($dbg==1){echo "<br>Authorized User (" . __LINE__ . ")";}
						return true;
					}
					else
					{
						if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
						return false;	
					}
				}
				else
				{
					if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
					return false;
				}
			}
			else
			{
				if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
				return false;
			}
		}
		else
		{
			if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
			return false;
		}
	}
	else
	{
		if ($dbg==1){echo "<br>Unauthorized User (" . __LINE__ . ")";}
		return false;
	}
}

function BlockIllegalChar($in)
{
	//$ichr=array('\'','\"',';',':','%','^');
	$ichr='/\'/i';
	
	if (preg_match($ichr,$in))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function CheckFunctionalAccess($s,$a)
{
	$r=false;
	$o=array();
	$out=array();
	$qry0 = "SELECT * FROM SecurityLevel WHERE sid=".$s." AND sgKeyword='".$a."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 == 1)
	{
		$row0 = mssql_fetch_array($res0);
		
		if ($row0['sgRead'] > 0)
		{
			$o=array('C'=>$row0['sgCreate'],'R'=>$row0['sgRead'],'U'=>$row0['sgUpdate'],'D'=>$row0['sgDelete']);
			$r=true;
		}
	}
	
	$out=array($r,$o);
	
	return $out;
}

function isValidUserExt($elog,$etkn,$efnc=0)
{
	// This function will validate the integrity of the User when processing requests via External Request
	$t='';
	$r=false;

	if (isset($elog) and (strlen($elog) >= 4 and strlen($elog) < 17))
	{
		if (isset($etkn) and strlen($etkn) > 5)
		{
			$qry0 = "SELECT securityid,officeid,login,pswd,slevel,passcnt FROM security WHERE login='".trim($elog)."';";
			$res0 = mssql_query($qry0);
			$nrow0= mssql_num_rows($res0);
			
			if ($nrow0 == 1)
			{
				$row0 = mssql_fetch_array($res0);
				$slevs=explode(',',$row0['slevel']);
				
				if ($slevs[6] != 0)
				{
					if ($row0['passcnt'] < 5)
					{
						if (trim($etkn) === md5($row0['securityid']))
						{
							$qry1 = " UPDATE security set passcnt=0 WHERE securityid=".$row0['securityid'].";";
							$res1 = mssql_query($qry1);
							
							$ecfc=CheckFunctionalAccess($row0['securityid'],$efnc);
							
							if ($ecfc[0])
							{
								$r=true;
								$t="Authorized (" . __LINE__ . ") (Create:".$ecfc[1]['C']." Read:".$ecfc[1]['R']." Update:".$ecfc[1]['U']." Delete:".$ecfc[1]['D'].")";
							}
							else
							{
								$t="No Function Access (" . __LINE__ . ") (".$row0['passcnt'].")";
							}
						}
						else
						{
							$qry1 = " UPDATE security set passcnt=(passcnt + 1) WHERE securityid=".$row0['securityid'].";";
							$res1 = mssql_query($qry1);
							
							$t="Password Incorrect (" . __LINE__ . ") (".$row0['passcnt'].")";
						}
					}
					else
					{
						$t="Account Locked Out (" . __LINE__ . ") (".$row0['passcnt'].")";
					}
				}
				else
				{
					$t="Account Deactivated (" . __LINE__ . ")";
				}
			}
			elseif ($nrow0 > 1)
			{
				$t="Account Error (" . __LINE__ . ")";
			}
			else
			{
				$t="Account Not Found (" . __LINE__ . ")";
			}
		}
		else
		{
			$t="Invalid Password (" . __LINE__ . ")";
		}
	}
	else
	{
		$t="Invalid Login (" . __LINE__ . ")";
	}
	
	$out=array($r,trim($t));
	
	return $out;
}

function isUserLoggedIn($esid)
{
	
}

?>