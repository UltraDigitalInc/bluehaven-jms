<?php

function updt_standings_config()
{
	 ini_set('display_errors','On');
	
	error_reporting(E_ALL);
	$byr=$_POST['brept_yr'];
	//$byr	="2006";
	$qry 	 = "UPDATE bonus_schedule_config SET ";
	$qry 	.= " active='".$_POST['setactive']."',smo='".$_POST['smo']."',syr='".$_POST['syr']."',emo='".$_POST['emo']."',eyr='".$_POST['eyr']."', ";
	$qry 	.= " tfactor='".$_POST['tfactor']."',tbonuslev='".$_POST['tbonuslev']."',xbonuslev='".$_POST['xbonuslev']."', ";
	$qry 	.= " xbonustext='".$_POST['xbonustext']."',gfbonuslev='".$_POST['gfbonuslev']."',gfbonusamt='".$_POST['gfbonusamt']."', ";
	$qry 	.= " brept_yr='".$_POST['brept_yr']."',period_ar='".$_POST['period_ar']."',periodptr='".$_POST['periodptr']."', psdate='".$_POST['psdate']."',pedate='".$_POST['pedate']."',";
	$qry 	.= " periodcomp='".$_POST['periodcomp']."',pervolamt=cast('".$_POST['pervolamt']."' as money),cfgupdt=getdate(),updtby='".$_SESSION['securityid']."',contact='".$_POST['contact']."' ";
	$qry 	.= " WHERE brept_yr='".$byr."';";
	$res 	= mssql_query($qry);

	//echo $qry."<br>";

	sales_standings_config_view();
}

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
	//echo $par.'<br>';
	//$out=array(12);

	//for ($i=1; $i<$par; $i++)
	//{
	//	$out[]=$i;
	//}
	$out=explode(':',$par);

	//print_r( $out );

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
		$prarray	=array(12,1,2,3);
	}
	else
	{
		$prarray	=array(0,0,0,0);
	}
	
	//if (count($incperiod) > 12)
	//{
		//echo count($incperiod).'<br>';
	//}
	
	$psecidarray=array();
	$pervol_ar	=array();

	$qrypre 	= "SELECT rept_mo,rept_yr,brept_yr,jtext,officeid,jtext FROM digreport_main WHERE brept_yr='".$brept_yr."' and no_digs!=0 order by rept_yr,rept_mo;";
	$respre 	= mssql_query($qrypre);
	$nrowpre 	= mssql_num_rows($respre);

	if ($_SESSION['securityid']==269999999999999999999999999999)
	{
		echo $qrypre."<br>";
	}
	
	if ($_SESSION['securityid']==269999999999999999999999999999) {
		echo '<pre>';
		print_r($currperiod);
		echo '</pre>';
	}

	//print_r($incperiod);

	if ($nrowpre > 0)
	{
		 $cc=1;
		$h1=0;
		$h2=0;
		$dbgcnt=0;
		while ($rowpre = mssql_fetch_array($respre))
		{
			//if (in_array($rowpre['rept_mo'],$incperiod))
			//{
				$subid=explode(",", $rowpre['jtext']);
				if($_SESSION['securityid']==269999999999999999999999999)
				  {
						   
						   echo $cc++.'<br>';
				  }
				
				
				foreach ($subid as $n => $v)
				{
					$isubid=explode(":",$v);
					if($_SESSION['securityid']==269999999999999999999999)
						   {
									
									echo $cc++.'<br>';
						   }
					
					if (isset($isubid[20]) && $isubid[20] > 0)
					{
						//echo $isubid[20]."<br>";
						if($_SESSION['securityid']==26999999999999999999999999999)
						   {
									echo $isubid[0].':'.$isubid[20]."<br>";		
									//echo $cc++.'<br>';
						   }
					}
					else
					{
						//echo $v."<br>";
						
						//echo $isubid[0]."<br>";
						//echo "-----<br>";
						if (isset($isubid[8]) && is_numeric($isubid[8]))
						{
							$dbg++;
							if($_SESSION['securityid']==26999999999999999999999999999999)
						   {
									
									echo $cc++.'<br>';
						   }
							//echo $v."<br>";
							//$sid=$isubid[8];
							//$secidarray[]=$sid;
							//echo $dbg.':'.$isubid[8].'<br>';
							
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
								$dsgfprc=array_combine_emulated($prarray,$dsgfpr,$row['securityid']);
	
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
			//}
		}
	}
	else
	{
		//echo "Standings Report encountered an Error. Please contact Management.";
		echo ' No Digs Reported for the '.$brept_yr.' Year.';
		exit;
	}

	//echo "H1: ".$h1."<br>";
	//echo "H2: ".$h2."<br>";

	$gfar=getgfarray($brept_yr);
	$psecidarray=array_merge($secidarray,$gfar);
	$out	=array($psecidarray,$pervol_ar);
	
	//$psecidarray=$secidarray;
	
	if($_SESSION['securityid']==2699999999999999)
	{
		 echo '<pre>';
		print_r($out[0]);
		echo '</pre>';
	}

	//echo count($gfar);
	//echo "<br>";
	//echo count($secidarray);
	//echo "<br>";
	//echo count($psecidarray);
	return $out;
}

function getlatestarters()
{
	$h			=0;
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
				elseif (strtotime($rowpre0['hdate']) >= strtotime($date1))
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

function sales_standings_config()
{
	$qry	= "SELECT * FROM bonus_schedule_config ORDER BY brept_yr DESC;";
	$res	= mssql_query($qry);
	//$rowpre1	= mssql_fetch_array($respre1);
	
	echo "<table>\n";
	echo " 	<tr>\n";
	echo "		<td align=\"center\" valign=\"top\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"5\" class=\"gray\" align=\"center\"><b>Bonus Schedule Config Year Select</b></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray_und\" align=\"center\"><b>Active</b></td>\n";
	echo "					<td class=\"gray_und\" align=\"center\"><b>Year</b></td>\n";
	echo "					<td class=\"gray_und\" align=\"center\"><b>Start</b></td>\n";
	echo "					<td class=\"gray_und\" align=\"center\"><b>End</b></td>\n";
	echo "					<td class=\"gray_und\" align=\"center\"></td>\n";
	echo "				</tr>\n";

	$firstInd = true;
	$cyr = date("Y");
	while ($row	= mssql_fetch_array($res))
	{
		if ($firstInd==true)
		{
			while ($cyr > $row['brept_yr']) {
				
				echo "         	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "					<input type=\"hidden\" name=\"call\" value=\"standings_config_create\">\n";
				echo "					<input type=\"hidden\" name=\"brept_yr\" value=\"".$cyr."\">\n";
				echo "				<tr>\n";
				echo "					<td class=\"wh_und\" align=\"center\">\n";

				echo "					</td>\n";
				echo "					<td class=\"wh_und\" align=\"center\">".$cyr."</td>\n";
				echo "					<td class=\"wh_und\" align=\"center\">".""."</td>\n";
				echo "					<td class=\"wh_und\" align=\"center\">".""."</td>\n";
				echo "					<td class=\"wh_und\" align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Create Year\"></td>\n";
				echo "				</tr>\n";
				echo "         				</form>\n";

				$cyr = $cyr - 1;
			}
			$firstInd = false;
		}

		echo "         	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "					<input type=\"hidden\" name=\"call\" value=\"standings_config_view\">\n";
		echo "					<input type=\"hidden\" name=\"brept_yr\" value=\"".$row['brept_yr']."\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh_und\" align=\"center\">\n";
		
		if ($row['active']==1)
		{
			echo "<b>*</b>";
		}
		
		echo "					</td>\n";
		echo "					<td class=\"wh_und\" align=\"center\">".$row['brept_yr']."</td>\n";
		echo "					<td class=\"wh_und\" align=\"center\">".date("m/d/Y",strtotime($row['psdate']))."</td>\n";
		echo "					<td class=\"wh_und\" align=\"center\">".date("m/d/Y",strtotime($row['pedate']))."</td>\n";
		echo "					<td class=\"wh_und\" align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Select\"></td>\n";
		echo "				</tr>\n";
		echo "         				</form>\n";
	}

	echo "			</table>\n";
	echo "		</td>\n";
}

function sales_standings_config_create() 
{
	$byr=$_POST['brept_yr'];
	$priorYr = $byr - 1;
	$tfactor = "";
	$tbonuslev = "";
	$xbonuslev = "";
	$xbonustext = "";

	$qrypre0	= "SELECT * FROM bonus_schedule WHERE yr='".$byr."' order by dlev DESC;";
	$respre0	= mssql_query($qrypre0);
	$nrowpre0= mssql_num_rows($respre0);

	$qrypre1	= "SELECT * FROM bonus_schedule_config WHERE brept_yr='".$byr."' ;";
	$respre1	= mssql_query($qrypre1);
	$rowpre1	= mssql_fetch_array($respre1);

	if ($rowpre1 == 0)
	{
		$insertQuery2 = "INSERT INTO bonus_schedule_config (smo,syr,emo,eyr,tfactor,tbonuslev,xbonuslev,xbonustext, gfbonuslev, brept_yr, period_ar, periodptr, periodcomp) VALUES ('12', $priorYr, '11', $byr, '10', '42', '70','$500 Gift Certificate', '35', $byr, '12:1:2:3:4:5:6:7:8:9:10:11', '1', '0');";
		$insertResult = mssql_query($insertQuery2);
	}

	sales_standings_config_view();

}

function sales_standings_config_view()
{
	$byr=$_POST['brept_yr'];
	//$byr="2006";
	$qrypre0	= "SELECT * FROM bonus_schedule WHERE yr='".$byr."' order by dlev DESC;";
	$respre0	= mssql_query($qrypre0);
	$nrowpre0= mssql_num_rows($respre0);

	$qrypre1	= "SELECT * FROM bonus_schedule_config WHERE brept_yr='".$byr."' ;";
	$respre1	= mssql_query($qrypre1);
	$rowpre1	= mssql_fetch_array($respre1);
	
	$ldate=$rowpre1['cfgupdt'];
	
	if ($rowpre1['updtby']!=0)
	{
		$qrypre2	= "SELECT lname,fname FROM security WHERE securityid='".$rowpre1['updtby']."';";
		$respre2	= mssql_query($qrypre2);
		$rowpre2	= mssql_fetch_array($respre2);
		
		$lupdtby=$rowpre2['lname'].", ".$rowpre2['fname'];
	}
	else
	{
		$lupdtby='';
	}

	echo "<table width=\"50%\">\n";
	echo " 	<tr>\n";
	echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"4\" class=\"gray\" align=\"center\"><b>".$rowpre1['brept_yr']." Bonus Schedule</b></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray_und\" align=\"center\"></td>\n";
	echo "					<td class=\"gray_und\" align=\"center\"><b>Dig Level</b></td>\n";
	echo "					<td class=\"gray_und\" align=\"center\"><b>Bonus</b></td>\n";
	echo "					<td class=\"gray_und\" align=\"center\"></td>\n";
	echo "				</tr>\n";

	while ($rowpre0	= mssql_fetch_array($respre0))
	{
		echo "         				<form method=\"post\">\n";
		echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "					<input type=\"hidden\" name=\"call\" value=\"standings_config_update_sched\">\n";
		echo "					<input type=\"hidden\" name=\"schedid\" value=\"".$rowpre0['id']."\">\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"center\"></td>\n";
		echo "					<td class=\"wh\" align=\"center\"><input class=\"bbox\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"dlev\" value=\"".$rowpre0['dlev']."\"></td>\n";
		echo "					<td align=\"right\"><input class=\"bbox\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"bonus\" value=\"".$rowpre0['bonus']."\"></td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Update\"></td>\n";
		echo "				</tr>\n";
		echo "         				</form>\n";
	}

	echo "         				<form method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"standings_config_update_sched_lates\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"wh\" align=\"center\" title=\"Late Starter Minimum Cap\"><b>*</b></td>\n";
	echo "					<td class=\"wh\" align=\"center\"><input class=\"bbox\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"gfbonuslev\" value=\"".$rowpre1['gfbonuslev']."\"></td>\n";
	echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"gfbonusamt\" value=\"".$rowpre1['gfbonusamt']."\"></td>\n";
	echo "					<td class=\"wh\" align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Update\"></td>\n";
	echo "				</tr>\n";
	echo "         				</form>\n";
	echo "			</table>\n";
	echo "		</td>\n";

	if ($_SESSION['rlev'] >= 9)
	{
		echo "		<td align=\"center\" valign=\"top\" width=\"50%\">\n";
		echo "         			<form method=\"post\">\n";
		echo "				<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
		echo "				<input type=\"hidden\" name=\"call\" value=\"updt_standings_config\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" class=\"gray\" align=\"center\">&nbsp;</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td colspan=\"2\" class=\"gray_und\" align=\"center\"><b>Bonus Schedule Settings</b></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Last Update:</td>\n";
		echo "					<td class=\"wh\" align=\"right\">".$ldate."</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Last Update by:</td>\n";
		echo "					<td class=\"wh\" align=\"right\">".$lupdtby."</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Active:</td>\n";
		echo "					<td class=\"wh\" align=\"right\">\n";
		echo "						<select name=\"setactive\">\n";
		
		if ($rowpre1['active'] == 1)
		{
			echo "<option value=\"1\" SELECTED>Yes</option>";
			echo "<option value=\"0\">No</option>";
		}
		else
		{
			echo "<option value=\"1\">Yes</option>";
			echo "<option value=\"0\" SELECTED>No</option>";
		}
		
		echo "						</select>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Bonus Year:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"12\" name=\"brept_yr\" value=\"".$rowpre1['brept_yr']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Bonus Year Start:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"4\" maxlength=\"2\" name=\"smo\" value=\"".$rowpre1['smo']."\">/<input class=\"bbox\" type=\"text\" size=\"4\" maxlength=\"4\" name=\"syr\" value=\"".$rowpre1['syr']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Bonus Year End:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"4\" maxlength=\"2\" name=\"emo\" value=\"".$rowpre1['emo']."\">/<input class=\"bbox\" type=\"text\" size=\"4\" maxlength=\"4\" name=\"eyr\" value=\"".$rowpre1['eyr']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Displayed Period Start:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"10\" maxlength=\"10\" name=\"psdate\" value=\"".date("m/d/Y",strtotime($rowpre1['psdate']))."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Displayed Period End:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"10\" maxlength=\"10\" name=\"pedate\" value=\"".date("m/d/Y",strtotime($rowpre1['pedate']))."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Period Sequence:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"30\" name=\"period_ar\" value=\"".$rowpre1['period_ar']."\"></td>\n";
		echo "				</tr>\n";

		//$ptr_ar=explode(":",$rowpre1['period_ar']);

		$ptr_ar=array(1,2,3,4,5,6,7,8,9,10,11,12);
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Standings End Period:</td>\n";
		echo "					<td class=\"wh\" align=\"right\">\n";
		echo "						<select name=\"periodptr\">\n";

		foreach ($ptr_ar as $np1 => $vp1)
		{
			if ($rowpre1['periodptr'] == $vp1)
			{
				echo "							<option value=\"".$vp1."\" SELECTED>".$vp1."</option>\n";
			}
			else
			{
				echo "							<option value=\"".$vp1."\">".$vp1."</option>\n";
			}
		}

		echo "						</select>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Trip Bonus Level:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"12\" name=\"tbonuslev\" value=\"".$rowpre1['tbonuslev']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Bonus Calc Factor:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"12\" name=\"tfactor\" value=\"".$rowpre1['tfactor']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Extra Bonus Level:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"12\" name=\"xbonuslev\" value=\"".$rowpre1['xbonuslev']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Extra Bonus Item:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"19\" name=\"xbonustext\" value=\"".$rowpre1['xbonustext']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Late Starter Level:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"12\" name=\"gfbonuslev\" value=\"".$rowpre1['gfbonuslev']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Late Starter Amount:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"12\" name=\"gfbonusamt\" value=\"".$rowpre1['gfbonusamt']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Per Volume Trigger:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"12\" name=\"pervolamt\" value=\"".$rowpre1['pervolamt']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Period Complete:</td>\n";
		echo "					<td class=\"wh\" align=\"right\">\n";
		echo "						<select name=\"periodcomp\">\n";
		
		if ($rowpre1['periodcomp'])
		{
			echo "<option value=\"1\" SELECTED>Yes</option>\n";
			echo "<option value=\"0\">No</option>\n";
		}
		else
		{
			echo "<option value=\"1\">Yes</option>\n";
			echo "<option value=\"0\" SELECTED>No</option>\n";
		}
		
		echo "						</select>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\">Contact:</td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" size=\"25\" maxlength=\"50\" name=\"contact\" value=\"".$rowpre1['contact']."\"></td>\n";
		echo "				</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"wh\" align=\"right\"></td>\n";
		echo "					<td class=\"wh\" align=\"right\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Update\"></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "         			</form>\n";
		echo "		</td>\n";
	}


	echo "	</tr>\n";
	echo "</table>\n";
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

function sales_standings()
{
	$qrypreA 	= "SELECT securityid,digstandingrpt FROM security WHERE securityid=".$_SESSION['securityid'].";";
	$respreA 	= mssql_query($qrypreA);
	$rowpreA 	= mssql_fetch_array($respreA);
	
	$digstandingrpt	=$rowpreA['digstandingrpt'];
	
	if ($digstandingrpt < 1)
	{
	   die('You are not authorized to view this resource');
	   exit;
	}
	
	$qrypre0 	= "SELECT * FROM bonus_schedule_config WHERE active=1;";
	$respre0 	= mssql_query($qrypre0);
	$rowpre0 	= mssql_fetch_array($respre0);
	
	$activeyr	= $rowpre0['brept_yr'];
	$qrypre 	= "SELECT MAX(dlev) as mdlev FROM bonus_schedule WHERE yr='".$activeyr."';";
	$respre 	= mssql_query($qrypre);
	$rowpre		= mssql_fetch_array($respre);
	
	$dar		=array();
	$dar2		=array();
	$tdigs		=0;
	$scnt		=0;
	$titletxt	="";
	$lstarttxt	="";
	$lstrigger	=0;
	$latestart	=35;
	$tlevel		=40;
	$rank		=0;
	$ptdigs		=0;
	$tdate		=getdate();
	$smo		=$rowpre0['smo'];
	$emo		=$rowpre0['emo'];
	$syr		=$rowpre0['syr'];
	$eyr		=$rowpre0['eyr'];
	$tripbonus	=$rowpre0['tbonuslev'];
	$targetadj	=$rowpre0['tfactor'];
	$gflev		=$rowpre0['gfbonuslev'];
	$xbonuslev	=$rowpre0['xbonuslev'];
	$xbonustxt	=$rowpre0['xbonustext'];
	$brept_yr	=$activeyr;
	$pervolamt	=$rowpre0['pervolamt'];
	
	$currperiod	=getperiod($rowpre0['period_ar'],$tdate['mon']);
	$periodptr	=$currperiod-$rowpre0['periodptr'];
	$activeperiod	=getactiveperiods($rowpre0['period_ar']);
	$lstarters	=getlatestarters();
	$secidarray	=getsecids($activeyr,$activeperiod);
	
	if ($digstandingrpt <= 1) {
		$maxrankshown=20;
	}
	else {
		$maxrankshown=65535;
	}
	
	$qrypre1 		= "SELECT * FROM bonus_schedule WHERE yr='".$activeyr."' ORDER BY dlev DESC;";
	$respre1		= mssql_query($qrypre1);
	$nrowpre1		= mssql_num_rows($respre1);
	
	if ($_SESSION['securityid']==269999999999999999999999999) {
		echo '<pre>';
		print_r($currperiod);
		echo '</pre>';
	}
	
	if ($nrowpre1 > 0) {
		while ($rowpre1 = mssql_fetch_array($respre1)) {
			$qrypre2 = "SELECT * FROM bonus_schedule WHERE dlev < '".$rowpre1['dlev']."' AND yr='".$activeyr."' ORDER BY dlev DESC;";
			$respre2 = mssql_query($qrypre2);
			$rowpre2 = mssql_fetch_array($respre2);
			
			//if ($_SESSION['securityid']==26) {
			//   echo $qrypre2.'<br>';
			//}
	
			$dar[]=$rowpre1['dlev'];
			$dar2[]=$rowpre2['dlev']+1;
		}
	}
	
	$acount=array_count_values($secidarray[0]);
	arsort($acount);
	
	if ($digstandingrpt < 9)
	{
		echo "<div class=\"noPrint\">\n";
	}
	
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"11\" class=\"gray\" align=\"center\"><b><font class=\"super\">National Sales Bonus Program ".$activeyr."</font></b></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"11\" class=\"gray\" align=\"center\"><b>Year End Bonus and Leader Board</b></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"11\" class=\"gray\" align=\"center\"><b>Unofficial Standings as of ".$tdate['mon']."/".$tdate['mday']."/".$tdate['year']."</b></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	//echo "   	<td colspan=\"11\" class=\"gray\" align=\"center\"><b>Reported Digs: ".$periodrange[0]." through ".$periodrange[1]."</b></td>\n";
	echo "   	<td colspan=\"11\" class=\"gray\" align=\"center\"><b>Reported Digs: ".date('m/d/Y',strtotime($rowpre0['psdate']))." through ".date('m/d/Y',strtotime($rowpre0['pedate']))."</b></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"11\" class=\"gray_und\" align=\"center\"><b>Report discrepancies to ".$rowpre0['contact']." @ 619-233-3522</b></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td colspan=\"5\" align=\"center\" class=\"ltgray_none\">&nbsp;</td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_none\">&nbsp;</td>\n";
	echo "   	<td colspan=\"1\" align=\"center\" class=\"ltgray_sides\" title=\"Total Digs/Dollar Volume\"></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_sides\"><b>Projected</b></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_sides\"><b>Target</b></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_sides\"><b>Digs to make</b></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_sides\"><b>Target</b></td>\n";
	
	if ($digstandingrpt >= 9)
	{
		echo "   	<td align=\"center\" class=\"ltgray_sides\"><b>Dollar</b></td>\n";
	}
	
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "   	<td align=\"left\" class=\"ltgray_und\"></td>\n";
	echo "   	<td align=\"left\" class=\"ltgray_und\"><b>Rank</b></td>\n";
	echo "   	<td colspan=\"2\" align=\"left\" class=\"ltgray_und\"><b>Name</b></td>\n";
	echo "   	<td align=\"left\" class=\"ltgray_und\"><b>Office</b></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_und\" title=\"Totals from Stored Dig Reports\"><b>Total Digs</b></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Total Digs/Dollar Volume\"><b>Digs/Dollar Volume</b></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Projected Digs based upon current Monthly Dig Average\"><b>Digs for Year</b></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Increasing Scale Projected Target level as determined by Management\"><b>Bonus Level</b></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Digs required to hit the Target Bonus Level\"><b>Bonus</b></td>\n";
	echo "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Projected Bonus based upon Target Bonus Level\"><b>Bonus</b></td>\n";
	
	if ($digstandingrpt >= 9)
	{
		echo "   	<td align=\"center\" class=\"ltgray_sidesb\" title=\"Total Amount of Contracts\"><b>Volume</b></td>\n";
	}
	
	echo "   </tr>\n";
	
	$seccnt=count($acount);
	
	//echo $seccnt."<br>";
	
	foreach ($acount as $pn1 => $pv1)
	{
		if (array_key_exists($pn1,$secidarray[1]))
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
		$tardigs		=(ceil($yrprojdigs/5)*5)+$targetadj;
	
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
		$fcbonus	=number_format($cbonus[0], 2, '.', ',');
		$rnktext	="";
	
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
	
		if ($tardigs >= $tlevel and $rank <= $maxrankshown)
		{
			$tdigs=$tdigs+$v1;
			echo "   <tr>\n";
			echo "   	<td align=\"right\" class=\"wh_und\">\n";
			
			if ($_SESSION['securityid']==26)
			{
				echo $scnt;
			}
			
			echo "		</td>\n";
			echo "   	<td align=\"center\" class=\"wh_und\" title=\"".$rnktext."\">".$rank.".</td>\n";
			echo "   	<td align=\"left\" class=\"wh_und\" title=\"".$rowA['securityid']."\">\n";
			
			//echo $rowpre['digstandingrpt'];
			
			if ($digstandingrpt >= 9)
			{
				echo "			<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=dsdvsr&ssid=".$rowA['securityid']."&oid=".$rowA['officeid']."&byr=".$activeyr."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=600,WIDTH=950,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".ucwords($rowA['lname'])."</a>\n";
			}
			else
			{
				echo ucwords($rowA['lname']);	
			}
			
			echo "		</td>\n";
			echo "   	<td align=\"left\" class=\"wh_und\" title=\"".$rowA['securityid']."\">".ucwords($rowA['fname'])."</td>\n";
			echo "   	<td align=\"left\" class=\"wh_und\" NOWRAP>".ucwords($rowB['name'])."</td>\n";
			echo "   	<td align=\"center\" class=\"wh_und\">".$v1."</td>\n";
			echo "   	<td align=\"center\" class=\"wh_und\">".$fsumtripbonus."</td>\n";
			echo "   	<td align=\"center\" class=\"wh_und\">".$yrprojdigs."</td>\n";
			echo "   	<td align=\"center\" class=\"wh_und\"><b>".$tardigs."</b></td>\n";
			echo "   	<td align=\"center\" class=\"wh_und\">".$digscashbonus."</td>\n";
			echo "   	<td align=\"right\" class=\"wh_und\">".$fcbonus."</td>\n";
			
			if ($digstandingrpt >= 9)
			{
				echo "   	<td align=\"right\" class=\"yel_und\">".number_format(round($pvol))."</td>\n";
			}
			
			echo "   </tr>\n";
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
		
				if ($tardigs >= $tlevel and $rank <= $maxrankshown)
				{
					$tdigs=$tdigs+$v1;
					echo "   <tr>\n";
					echo "   	<td align=\"right\" class=\"wh_und\">\n";
			
					if ($_SESSION['securityid']==26)
					{
						echo $scnt;
					}
					
					echo "		</td>\n";
					echo "   	<td align=\"center\" class=\"wh_und\" title=\"".$rnktext."\">".$rank.".</td>\n";
					echo "   	<td align=\"left\" class=\"wh_und\" title=\"".$rowA['securityid']."\">\n";
				
				   if ($rowpreA['digstandingrpt'] >= 9)
				   {
						echo "			<a href=\"http://jms.bhnmi.com/subs/drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=dsdvsr&ssid=".$rowA['securityid']."&oid=".$rowA['officeid']."&byr=".$activeyr."\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=600,WIDTH=950,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">".ucwords($rowA['lname'])."</a>\n";
				   }
				   else
				   {
					   echo ucwords($rowA['lname']);
				   }
					
					echo "		</td>\n";
					echo "   	<td align=\"left\" class=\"wh_und\" title=\"".$rowA['securityid']."\">".ucwords($rowA['fname'])."</td>\n";
					echo "   	<td align=\"left\" class=\"wh_und\" NOWRAP>".ucwords($rowB['name'])."</td>\n";
					echo "   	<td align=\"center\" class=\"wh_und\">".$v1."</td>\n";
					echo "   	<td align=\"center\" class=\"wh_und\">".$fsumtripbonus."</td>\n";
					echo "   	<td align=\"center\" class=\"wh_und\">".$yrprojdigs."</td>\n";
					echo "   	<td align=\"center\" class=\"wh_und\"><b>".$tardigs."</b></td>\n";
					echo "   	<td align=\"center\" class=\"wh_und\">".$digscashbonus."</td>\n";
					echo "   	<td align=\"right\" class=\"wh_und\">".$fcbonus."</td>\n";
					
				   if ($rowpreA['digstandingrpt'] >= 9)
				   {
						echo "   	<td align=\"right\" class=\"yel_und\">".number_format(round($pvol))."</td>\n";
				   }
						
					echo "   </tr>\n";
		
				}
				$lstrigger=0;
				$latestarttxt="";
				$ptdigs=$v1;
		}
	}
	
	if ($digstandingrpt >= 9)
	{
		echo "   <tr>\n";
		echo "   	<td align=\"right\" colspan=\"5\" class=\"gray\"><b>Total Digs</b></td>\n";
		echo "   	<td align=\"center\" class=\"gray\"><b>".$tdigs."</b></td>\n";
		echo "   	<td align=\"right\" colspan=\"6\" class=\"gray\"></td>\n";
		echo "   </tr>\n";
	}
	
	echo "</table>\n";
	
	if ($digstandingrpt < 9)
	{
		echo "</div>\n";
	}
	
	//echo "End Standings<br>";
}

?>