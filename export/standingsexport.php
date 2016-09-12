<?php
session_start();

if (!isset($_GET['byr']) || !isset($_GET['sid']) || $_GET['sid']!=md5($_SESSION['securityid']))
{
	echo "Invalid Parameters!";
   exit;
}

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=dsexport_".date("m-d-Y").".xls");
header("Pragma: no-cache"); 
header("Expires: 0");


include('../connect_db.php');
//$hostname = "192.168.1.59";
////$hostname = "192.168.1.38";
//$username = "jestadmin";
//$password = "into99black";
//$dbname   = "jest";
//
//mssql_connect($hostname,$username,$password) or die("DATABASE FAILED TO RESPOND.");
//mssql_select_db($dbname) or die("database unavailable");


function getdaterange($brept_yr,$periodar,$prevperiod)
{
	$par=explode(":",$periodar);
	$pyr=$brept_yr-1;

	$begofper=date("m/d/Y",strtotime($par[0]."/01/".$pyr));
	$endofper=date("m/t/Y",strtotime($par[$prevperiod-1]."/01/".$brept_yr));

	$out=array($begofper,$endofper);

	//print_r($out);
	return $out;
}

function getactiveperiods($par)
{
	$out=array(12);

	for ($i=1; $i<$par; $i++)
	{
		$out[]=$i;
	}

	return $out;
}

function array_combine_emulated($keys, $vals,$secid)
{
	/*
	print_r($keys);
	echo "<br>";
	print_r($vals);
	echo "<br>".$secid."<br>---<br>";
	*/

	if (count($keys)!=count($vals))
	{
		echo "<b>DSGF Array Error!</b><br>Contact Management (619) 233-3522.<br>";
		exit;
	}

	$keys	= array_values((array)$keys);
	$vals	= array_values((array)$vals);
	$n		= max(count($keys), count($vals));
	$r		= array();
	for( $i=0; $i<$n; $i++ )
	{
		$r[$keys[$i]] = $vals[$i];
	}
	return $r;
}

function getgfarray($brept_yr)
{
	$out=array();
	$qry 	= "SELECT securityid,dsgfperiod,dsgfarray FROM security WHERE dsgfperiod='".$brept_yr."';";
	$res 	= mssql_query($qry);
	$nrow= mssql_num_rows($res);

	if ($nrow > 0)
	{
		//echo "DSGF: ".$row['dsgfarray']."<br>";
		while ($row = mssql_fetch_array($res))
		{
			$in1=explode(",",$row['dsgfarray']);
			foreach ($in1 AS $n => $v)
			{
				if ($v!=0)
				{
					for ($c=1; $c<=$v; $c++)
					{
						$out[]=$row['securityid'];
					}
				}
			}
		}
	}

	//print_r($out);

	return $out;
}

function getsecids($brept_yr,$incperiod)
{
	//error_reporting(E_ALL);
	//$incperiod	=array(12,1,2,3,4);
	//$incperiod	=array(12,1,2,3);
	
	if ($brept_yr < 2007)
	{
		$prarray			=array(12,1,2,3);
	}
	else
	{
		$prarray			=array(0,0,0,0);
	}
	
	$psecidarray	=array();
	$pervol_ar		=array();

	$qrypre 		= "SELECT rept_mo,rept_yr,jtext,officeid,jtext FROM digreport_main WHERE brept_yr='".$brept_yr."' and no_digs!=0;";
	$respre 		= mssql_query($qrypre);
	$nrowpre 	= mssql_num_rows($respre);

	//echo $qrypre."<br>";

	//print_r($incperiod);

	if ($nrowpre > 0)
	{
		$h1=0;
		$h2=0;
		while ($rowpre = mssql_fetch_array($respre))
		{
			/*
			if ($rowpre['officeid']==51)
			{
			echo $rowpre['jtext'].")<br>";
			}
			*/
			if (in_array($rowpre['rept_mo'],$incperiod))
			{
				$subid=explode(",", $rowpre['jtext']);
				foreach ($subid as $n => $v)
				{
					$isubid=explode(":",$v);
					if (isset($isubid[20]) && $isubid[20]==1)
					{
					}
					else
					{
						//echo $v."<br>";
						//$sid=$isubid[8];
						//$secidarray[]=$sid;
						//echo "-----<br>";
	
						//echo $v."<br>";
						//echo $isubid[0]."<br>";
						//echo "-----<br>";
						if (isset($isubid[8]) && is_numeric($isubid[8]))
						{
							$qry = "SELECT securityid,dsgfperiod,dsgfarray,altid,lname,fname FROM security WHERE securityid='".$isubid[8]."';";
							$res = mssql_query($qry);
							$row = mssql_fetch_array($res);
							$nrow= mssql_num_rows($res);
							
							$qry1 = "SELECT securityid,secid FROM secondaryids WHERE secid='".$isubid[8]."';";
							$res1 = mssql_query($qry1);
							$row1 = mssql_fetch_array($res1);
							$nrow1= mssql_num_rows($res1);
	
							if ($nrow1==1 && $row1['secid']==$isubid[8])
							{
								$sid=$row1['securityid'];
								//$sid=$row['altid'];
								//$sid=$isubid[8];
								//echo "HIT1<br>";
	
								//echo $row['lname'].", ".$row['fname']."<br>";
								//$h1++;
							}
							else
							{
								//echo "HIT2<br>";
								$sid=$isubid[8];
								//$h2++;
							}
	
							if ($nrow > 0 && $row['dsgfperiod']!=0)
							{
								$dsgfpr	=explode(",",$row['dsgfarray']);
								$dsgfprc	=array_combine_emulated($prarray,$dsgfpr,$row['securityid']);
	
								//$dsgfprc	=array_combine($prarray,$dsgfpr);
	
								if (!array_key_exists($rowpre['rept_mo'],$dsgfprc))
								{
									$secidarray[]=$sid;
									$h1++;
								}
							}
							else
							{
								$secidarray[]=$sid;
								$h2++;
							}
						}
	
						if (isset($pervol_ar[$sid]))
						{
							$pervol_ar[$sid]=$pervol_ar[$sid]+$isubid[2];
						}
						else
						{
							$pervol_ar[$sid]=$isubid[2];
						}
					}
				}
			}
		}
	}
	else
	{
		echo "Standings Report encountered an Error. Please contact Management.";
		exit;
	}

	//echo "H1: ".$h1."<br>";
	//echo "H2: ".$h2."<br>";

	$gfar=getgfarray($brept_yr);
	$psecidarray=array_merge($secidarray,$gfar);
	$out	=array($psecidarray,$pervol_ar);
	
	//$psecidarray=$secidarray;
	
	//print_r($pervol_ar);

	//echo count($gfar);
	//echo "<br>";
	//echo count($secidarray);
	//echo "<br>";
	//echo count($psecidarray);
	return $out;
}

function getlatestarters()
{
	$h		=0;
	$h1		=0;
	$h2		=0;
	$h3		=0;
	$d1_ar	=array();
	$d2_ar	=array();
	$d3_ar	=array();

	$date1	="1/1/06";
	$date2	="2/1/06";
	$date3	="3/1/06";

	$qrypre0	= "SELECT securityid,mas_office,mas_div,masid,hdate FROM security where masid!=0 ORDER BY mas_div;";
	$respre0	= mssql_query($qrypre0);
	$nrowpre0= mssql_num_rows($respre0);

	if ($nrowpre0 > 0)
	{
		//echo "Start Records: ".$nrowpre0."<br>";
		while($rowpre0	= mssql_fetch_array($respre0))
		{
			if (isset($rowpre0['hdate']))
			{
				$h++;
				if (strtotime($rowpre0['hdate']) >= strtotime($date3))
				{
					$h3++;
					$d3_ar[]=$rowpre0['securityid'];
				}
				elseif (strtotime($rowpre0['hdate']) >= strtotime($date2))
				{
					$h2++;
					$d2_ar[]=$rowpre0['securityid'];
				}
				elseif (strtotime($rowpre0['hdate'])>= strtotime($date1))
				{
					$h1++;
					$d1_ar[]=$rowpre0['securityid'];
				}
				//echo "(".$rowpre0['securityid'].") (".$rowpre0['mas_office'].") (".$rowpre0['mas_div'].") (".$rowpre0['masid'].") (".strtotime($rowpre0['hdate']).")<br>";
			}
		}
	}

	$out=array($d1_ar,$d2_ar,$d3_ar,$date1,$date2,$date3);
	return $out;
}

function getperiod($par,$cmo)
{
	$out	=0;
	$ar	=explode(":",$par);

	if (in_array($cmo,$ar))
	{
		$out=array_keys($ar, $cmo);
		$out=$out[0]+1;
	}

	return $out;
}

function getbonus($targetlev,$yr,$dar,$dar2,$gftrig)
{
	$out		=array(0,0);
	//$targetlev=125;

	$qrypre	= "SELECT MAX(dlev) AS mdlev FROM bonus_schedule WHERE yr='".$yr."';";
	$respre	= mssql_query($qrypre);
	$rowpre	= mssql_fetch_array($respre);

	$qrypre1	= "SELECT MIN(dlev) AS mdlev FROM bonus_schedule WHERE yr='".$yr."';";
	$respre1	= mssql_query($qrypre1);
	$rowpre1	= mssql_fetch_array($respre1);

	$qrypre2	= "SELECT gfbonuslev,gfbonusamt FROM bonus_schedule_config WHERE brept_yr='".$yr."';";
	$respre2	= mssql_query($qrypre2);
	$rowpre2	= mssql_fetch_array($respre2);

	$maxdlev	=$rowpre['mdlev'];
	//$gflev	=$rowpre2['gfbonuslev'];
	$gfamt	=$rowpre2['gfbonusamt'];

	if ($gftrig==1)
	{
		$mindlev	=$rowpre2['gfbonuslev'];
	}
	else
	{
		$mindlev	=$rowpre1['mdlev'];
	}

	if ($targetlev >= $maxdlev)
	{
		//echo "MAX ($targetlev)<br>";
		$qry	= "SELECT bonus FROM bonus_schedule WHERE dlev='".$maxdlev."';";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);

		$out=array($row['bonus'],$maxdlev);
	}
	elseif ($targetlev == $mindlev)
	{
		//echo "MIN ($targetlev)<br>";
		if ($gftrig!=1)
		{
			$qry	= "SELECT bonus FROM bonus_schedule WHERE dlev='".$mindlev."';";
			$res	= mssql_query($qry);
			$row	= mssql_fetch_array($res);

			//$out=array($row['bonus'],$targetlev);
			$out=array($row['bonus'],$mindlev);
		}
		else
		{
			$out=array($gfamt,$rowpre2['gfbonuslev']);
		}
	}
	elseif ($targetlev < $mindlev)
	{
		//echo "BELOW ($targetlev)<br>";
		$out=array(0,$targetlev);
	}
	else
	{
		if (in_array($targetlev,$dar))
		{
			$qry	= "SELECT dlev,bonus FROM bonus_schedule WHERE dlev ='".$targetlev."';";
			$res	= mssql_query($qry);
			$row	= mssql_fetch_array($res);

			$out=array($row['bonus'],$row['dlev']);
		}
		else
		{
			foreach ($dar as $n1 => $v1)
			{
				//$out=array(99,$targetlev);

				if ($dar[$n1]==$rowpre['mdlev'])
				{
					$dar[$n1]=$rowpre['mdlev']-1;
				}

				if ($targetlev <=$dar[$n1] && $targetlev >= $dar2[$n1])
				{
					$llev=$dar2[$n1]-1;
					//$qry	= "SELECT dlev,bonus FROM bonus_schedule WHERE dlev ='".$dar[$n1]."';";
					$qry	= "SELECT dlev,bonus FROM bonus_schedule WHERE dlev ='".$llev."';";
					$res	= mssql_query($qry);
					$row	= mssql_fetch_array($res);

					/*
					if ($targetlev >= 100)
					{
						//print_r($dar2);
						echo "<br>";
						echo "MID ($targetlev)<br>";
						echo "DAR1: ".$dar[$n1]."<br>";
						echo "DAR2: ".$dar2[$n1]."<br>";
						echo "BONE: ".$row['bonus']."<br>";
						echo "--------------------<br>";
					}
					*/
					$out=array($row['bonus'],$row['dlev']);
				}
			}
		}
	}

	return $out;
}

function getbonus_comp($targetlev,$yr)
{
	$out		=array(0,0);
	$dar		=array();
	//$targetlev=125;

	$qrypre	= "SELECT MAX(dlev) AS mdlev FROM bonus_schedule WHERE yr='".$yr."';";
	$respre	= mssql_query($qrypre);
	$rowpre	= mssql_fetch_array($respre);

	$qrypre1	= "SELECT MIN(dlev) AS mdlev FROM bonus_schedule WHERE yr='".$yr."';";
	$respre1	= mssql_query($qrypre1);
	$rowpre1	= mssql_fetch_array($respre1);

	$qrypre2	= "SELECT dlev FROM bonus_schedule WHERE yr='".$yr."' order by dlev asc;";
	$respre2	= mssql_query($qrypre2);
	$nrowpre2= mssql_num_rows($respre2);
	
	if ($nrowpre2 > 0)
	{
		while ($rowpre2	= mssql_fetch_array($respre2))
		{
			$dar[]=$rowpre2['dlev'];
		}
	}

	$maxdlev	=$rowpre['mdlev'];
	$mindlev	=$rowpre1['mdlev'];

	if ($targetlev >= $maxdlev)
	{
		//echo "MAX ($targetlev) ($maxdlev)<br>";
		$qry	= "SELECT bonus FROM bonus_schedule WHERE dlev='".$maxdlev."' and yr='".$yr."';";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);
		//echo $qry."<br>";
		$out=array($row['bonus'],$maxdlev);
	}
	elseif ($targetlev == $mindlev)
	{
		//echo "MIN ($targetlev)<br>";
		$qry	= "SELECT bonus FROM bonus_schedule WHERE dlev='".$mindlev."' and yr='".$yr."';";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);
		//echo $qry."<br>";
		$out=array($row['bonus'],$mindlev);
	}
	elseif ($targetlev < $mindlev)
	{
		//echo "BELOW ($targetlev)<br>";
		$out=array(0,$targetlev);
	}
	elseif (in_array($targetlev,$dar))
	{
		//echo "EXACT ($targetlev)<br>";
		$qry	= "SELECT bonus FROM bonus_schedule WHERE dlev='".$targetlev."' and yr='".$yr."';";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);
		//echo $qry."<br>";
		$out=array($row['bonus'],$targetlev);
	}
	elseif ($targetlev < $maxdlev && $targetlev > $mindlev)
	{
		$qry	= "SELECT dlev,bonus FROM bonus_schedule WHERE dlev < '".$targetlev."' and yr='".$yr."' order by dlev desc";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);

		//echo "INNER ($targetlev)(".$row['bonus'].")<br>";
		$out=array($row['bonus'],$row['dlev']);
	}

	return $out;
}

	$xyzbyr		=$_GET['byr'];
	$qrypre 		= "SELECT MAX(dlev) as mdlev FROM bonus_schedule WHERE yr='".$xyzbyr."';";
	$respre 		= mssql_query($qrypre);
	$rowpre		= mssql_fetch_array($respre);

	$qrypre0 	= "SELECT * FROM bonus_schedule_config WHERE brept_yr='".$xyzbyr."';";
	$respre0 	= mssql_query($qrypre0);
	$rowpre0 	= mssql_fetch_array($respre0);

	$dar			=array();
	$dar2			=array();
	$tdigs		=0;
	$scnt			=0;
	$titletxt	="";
	$lstarttxt	="";
	$lstrigger	=0;
	$latestart	=35;
	$tlevel		=40;
	$rank			=0;
	$ptdigs		=0;
	$tdate		=getdate();
	$smo			=$rowpre0['smo'];
	$emo			=$rowpre0['emo'];
	$syr			=$rowpre0['syr'];
	$eyr			=$rowpre0['eyr'];
	$tripbonus	=$rowpre0['tbonuslev'];
	$targetadj	=$rowpre0['tfactor'];
	$gflev		=$rowpre0['gfbonuslev'];
	$xbonuslev	=$rowpre0['xbonuslev'];
	$xbonustxt	=$rowpre0['xbonustext'];
	$brept_yr	=$rowpre0['brept_yr'];
	$pervolamt	=$rowpre0['pervolamt'];
	$acountTRIP	=array();
	//$pervolamt	=1500000;

	$currperiod	=getperiod($rowpre0['period_ar'],$tdate['mon']);
	$periodptr	=$currperiod-$rowpre0['periodptr'];

	$activeperiod	=getactiveperiods($currperiod-$periodptr);
	$lstarters		=getlatestarters();
	$secidarray		=getsecids($brept_yr,$activeperiod);
	$periodrange	=getdaterange($brept_yr,$rowpre0['period_ar'],$currperiod-$periodptr);

	$qrypre1 		= "SELECT * FROM bonus_schedule WHERE yr='".$xyzbyr."' ORDER BY dlev DESC;";
	$respre1			= mssql_query($qrypre1);
	$nrowpre1		= mssql_num_rows($respre1);

	if ($nrowpre1 > 0)
	{
		while ($rowpre1 = mssql_fetch_array($respre1))
		{
			$qrypre2 = "SELECT * FROM bonus_schedule WHERE dlev < '".$rowpre1['dlev']."' AND yr='".$xyzbyr."' ORDER BY dlev DESC;";
			$respre2	= mssql_query($qrypre2);
			$rowpre2 = mssql_fetch_array($respre2);
			//$nrowpre2	= mssql_num_rows($respre2);

			$dar[]=$rowpre1['dlev'];
			$dar2[]=$rowpre2['dlev']+1;
		}
	}

	$acount=array_count_values($secidarray[0]);

	arsort($acount);
	
	$csv_output   = "";
	$csv_output  .= "<table border=\"1\">\n";
	$csv_output  .= "   <tr>\n";
	$csv_output  .= "   	<td colspan=\"12\" class=\"gray\" align=\"center\"><b><font class=\"super\">National Sales Bonus Program ".$brept_yr."</font></b></td>\n";
	$csv_output  .= "   </tr>\n";
	$csv_output  .= "   <tr>\n";
	$csv_output  .= "   	<td colspan=\"12\" class=\"gray\" align=\"center\"><b>Year End Bonus and Trip Leader Board</b></td>\n";
	$csv_output  .= "   </tr>\n";
	$csv_output  .= "   <tr>\n";
	$csv_output  .= "   	<td colspan=\"12\" class=\"gray\" align=\"center\"><b>Unofficial Standings as of ".$tdate['mon']."/".$tdate['mday']."/".$tdate['year']."</b></td>\n";
	$csv_output  .= "   </tr>\n";
	$csv_output  .= "   <tr>\n";
	$csv_output  .= "   	<td colspan=\"12\" class=\"gray\" align=\"center\"><b>Reported Digs: ".$periodrange[0]." through ".$periodrange[1]."</b></td>\n";
	$csv_output  .= "   </tr>\n";
	$csv_output  .= "   <tr>\n";
	$csv_output  .= "   	<td colspan=\"12\" class=\"gray_und\" align=\"center\"><b>Report discrepancies to ".$rowpre0['contact']." @ 619-233-3522</b></td>\n";
	$csv_output  .= "   </tr>\n";
	$csv_output  .= "   <tr>\n";
	$csv_output  .= "   	<td colspan=\"5\" align=\"center\" class=\"ltgray_none\">&nbsp;</td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_none\">&nbsp;</td>\n";
	$csv_output  .= "   	<td colspan=\"1\" align=\"center\" class=\"ltgray_sides\" title=\"Total Digs/Dollar Volume to make Trip\"><b>Digs/Dollar Volume to make</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sides\"><b>Projected</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sides\"><b>Target</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sides\"><b>Digs to make</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sides\"><b>Target</b></td>\n";
	
	if ($_SESSION['officeid']==89)
	{
		$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sides\"><b>Dollar</b></td>\n";
	}
	
	$csv_output  .= "   </tr>\n";
	$csv_output  .= "   <tr>\n";
	$csv_output  .= "   	<td align=\"left\" class=\"ltgray_und\"></td>\n";
	$csv_output  .= "   	<td align=\"left\" class=\"ltgray_und\"><b>Rank</b></td>\n";
	$csv_output  .= "   	<td colspan=\"2\" align=\"left\" class=\"ltgray_und\"><b>Name</b></td>\n";
	$csv_output  .= "   	<td align=\"left\" class=\"ltgray_und\"><b>Office</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_und\" title=\"Totals from Stored Dig Reports\"><b>Total Digs</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Salesman Digs (".$tripbonus.") or Dollar Volume ($".$pervolamt.") required to make Trip\"><b>Trip: ".$tripbonus." or $".number_format($pervolamt)."</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Projected Digs based upon current Monthly Dig Average\"><b>Digs for Year</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Increasing Scale Projected Target level as determined by Management\"><b>Bonus Level</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Digs required to hit the Target Bonus Level\"><b>Bonus</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Projected Bonus based upon Target Bonus Level\"><b>Bonus</b></td>\n";
	
	if ($_SESSION['officeid']==89)
	{
		$csv_output  .= "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Total Amount of Contracts\"><b>Volume</b></td>\n";
	}
	
	$csv_output  .= "   </tr>\n";

	//print_r($acount);
	
	//echo "<br>---<br>";

	$seccnt=count($acount);
	
	foreach ($acount as $pn1 => $pv1)
	{
		if	(array_key_exists($pn1,$secidarray[1]))
		{
			if ($pv1 >= $tripbonus || $secidarray[1][$pn1] >= $pervolamt)
			{
				//echo $secidarray[1][$pn1]." : ".$pervolamt." : ".$pv1." : ".$tripbonus."<br>";
				$acountTRIP[$pn1]=$pv1;
			}
		}
	}
	
	foreach ($acountTRIP as $n1 => $v1)
	{
		$scnt++;
		$qryA = "SELECT securityid,officeid,fname,lname,hdate FROM security WHERE securityid='".$n1."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		$qryB = "SELECT officeid,name FROM offices WHERE officeid='".$rowA['officeid']."';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);

		$sumtripbonus	=$tripbonus-$v1;
		$currdigavg		=$v1/($currperiod-$periodptr);
		$yrprojdigs		=ceil($v1+((12-($currperiod-$periodptr))*$currdigavg));
		//$yrprojdigs	=$currdigavg+((12-($currperiod-1))*$currdigavg);
		$tardigs			=(ceil($yrprojdigs/5)*5)+$targetadj;

		if ($tardigs >= $rowpre['mdlev'])
		{
			$tardigs=$rowpre['mdlev'];
		}

		$digscashbonus	=$tardigs-$v1;

		if ($v1 != $ptdigs)
		{
			$rank++;
		}

		$cbonus		=getbonus($tardigs,$tdate['year'],$dar,$dar2,$lstrigger);
		$fcbonus		=number_format($cbonus[0], 2, '.', ',');
		$rnktext		="";

		if ($sumtripbonus <= 0)
		{
			$fsumtripbonus="<b>Trip (".$rowpre0['tbonuslev'].")</b>";
		}
		else
		{
			$fsumtripbonus="<b>Trip ($".number_format($pervolamt).")</b>";
		}

		$tlevel=0;		
		
		if (isset($secidarray[1][$n1]))
		{
			$pvol=$secidarray[1][$n1];
		}
		else
		{
			$pvol=0;
		}

		if ($tardigs >= $tlevel)
		{
			$tdigs=$tdigs+$v1;
			$csv_output  .= "   <tr>\n";
			$csv_output  .= "   	<td align=\"right\" class=\"wh_und\"></td>\n";
			$csv_output  .= "   	<td align=\"center\" class=\"wh_und\" title=\"".$rnktext."\">".$rank."</td>\n";
			$csv_output  .= "   	<td align=\"left\" class=\"wh_und\" title=\"".$titletxt."\">".ucwords($rowA['lname'])."</td>\n";
			$csv_output  .= "   	<td align=\"left\" class=\"wh_und\" title=\"".$rowA['securityid']."\">".ucwords($rowA['fname'])."</td>\n";
			$csv_output  .= "   	<td align=\"left\" class=\"wh_und\" NOWRAP>".ucwords($rowB['name'])."</td>\n";
			$csv_output  .= "   	<td align=\"center\" class=\"wh_und\">".$v1."</td>\n";
			$csv_output  .= "   	<td align=\"center\" class=\"wh_und\">".$fsumtripbonus."</td>\n";
			$csv_output  .= "   	<td align=\"center\" class=\"wh_und\">".$yrprojdigs."</td>\n";
			$csv_output  .= "   	<td align=\"center\" class=\"wh_und\"><b>".$tardigs."</b></td>\n";
			$csv_output  .= "   	<td align=\"center\" class=\"wh_und\">".$digscashbonus."</td>\n";
			$csv_output  .= "   	<td align=\"right\" class=\"wh_und\">".$fcbonus."</td>\n";
			
			if ($_SESSION['officeid']==89)
			{
				$csv_output  .= "   	<td align=\"right\" class=\"yel_und\">".number_format(round($pvol))."</td>\n";
			}
			
			$csv_output  .= "   </tr>\n";

		}
		$lstrigger=0;
		$latestarttxt="";
		$ptdigs=$v1;
	}
	
	foreach ($acount as $n1 => $v1)
	{
		if (!array_key_exists($n1,$acountTRIP))
		{
			$scnt++;
			$qryA = "SELECT securityid,officeid,fname,lname,hdate FROM security WHERE securityid='".$n1."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);
	
			$qryB = "SELECT officeid,name FROM offices WHERE officeid='".$rowA['officeid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
	
			$sumtripbonus	=$tripbonus-$v1;
			$currdigavg		=$v1/($currperiod-$periodptr);
			$yrprojdigs		=ceil($v1+((12-($currperiod-$periodptr))*$currdigavg));
			//$yrprojdigs	=$currdigavg+((12-($currperiod-1))*$currdigavg);
			$tardigs			=(ceil($yrprojdigs/5)*5)+$targetadj;
	
			if ($tardigs >= $rowpre['mdlev'])
			{
				$tardigs=$rowpre['mdlev'];
			}
	
			$digscashbonus	=$tardigs-$v1;
	
			if ($v1 != $ptdigs)
			{
				$rank++;
			}
	
			$cbonus		=getbonus($tardigs,$tdate['year'],$dar,$dar2,$lstrigger);
			$fcbonus		=number_format($cbonus[0], 2, '.', ',');
			$rnktext		="";
	
			if ($sumtripbonus <= 0)
			{
				$fsumtripbonus="<b>Trip (".$rowpre0['tbonuslev'].")</b>";
			}
			else
			{
				$fsumtripbonus=$sumtripbonus;
			}
	
			$tlevel=0;		
			
			if (isset($secidarray[1][$n1]))
			{
				$pvol=$secidarray[1][$n1];
			}
			else
			{
				$pvol=0;
			}
	
			if ($tardigs >= $tlevel)
			{
				$tdigs=$tdigs+$v1;
				$csv_output  .= "   <tr>\n";
				$csv_output  .= "   	<td align=\"right\" class=\"wh_und\"></td>\n";
				$csv_output  .= "   	<td align=\"center\" class=\"wh_und\" title=\"".$rnktext."\">".$rank."</td>\n";
				$csv_output  .= "   	<td align=\"left\" class=\"wh_und\" title=\"".$titletxt."\">".ucwords($rowA['lname'])."</td>\n";
				$csv_output  .= "   	<td align=\"left\" class=\"wh_und\" title=\"".$rowA['securityid']."\">".ucwords($rowA['fname'])."</td>\n";
				$csv_output  .= "   	<td align=\"left\" class=\"wh_und\" NOWRAP>".ucwords($rowB['name'])."</td>\n";
				$csv_output  .= "   	<td align=\"center\" class=\"wh_und\">".$v1."</td>\n";
				$csv_output  .= "   	<td align=\"center\" class=\"wh_und\">".$fsumtripbonus."</td>\n";
				$csv_output  .= "   	<td align=\"center\" class=\"wh_und\">".$yrprojdigs."</td>\n";
				$csv_output  .= "   	<td align=\"center\" class=\"wh_und\"><b>".$tardigs."</b></td>\n";
				$csv_output  .= "   	<td align=\"center\" class=\"wh_und\">".$digscashbonus."</td>\n";
				$csv_output  .= "   	<td align=\"right\" class=\"wh_und\">".$fcbonus."</td>\n";
				
				if ($_SESSION['officeid']==89)
				{	
					$csv_output  .= "   	<td align=\"right\" class=\"yel_und\">".number_format(round($pvol))."</td>\n";
				}
				
				$csv_output  .= "   </tr>\n";
	
			}
			$lstrigger=0;
			$latestarttxt="";
			$ptdigs=$v1;
		}
	}

	$csv_output  .= "   <tr>\n";
	$csv_output  .= "   	<td align=\"right\" colspan=\"5\" class=\"gray\"><b>Total Digs</b></td>\n";
	$csv_output  .= "   	<td align=\"center\" class=\"gray\"><b>".$tdigs."</b></td>\n";
	$csv_output  .= "   	<td align=\"right\" colspan=\"6\" class=\"gray\"></td>\n";
	$csv_output  .= "   </tr>\n";
	$csv_output  .= "
   </table>
   ";	
	print $csv_output;
	exit;

?>