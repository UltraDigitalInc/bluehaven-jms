<?php

function CommissionScheduleRO($cinar,$col_struct)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    $tsecid=26;
	$dbg=1;
	if (isset($dbg) && $dbg==1 && $_SESSION['securityid']==$tsecid)
	{
		echo "<pre>";
		//print_r($commarray);
		echo 'MOD:'.$_SESSION['modcomm'].'<br>';
		echo "</pre>";
	}

	$tcomm=0;
	$commcat_ar=array();
	$comar=array();
	$grpcomar=array();
	
	$errbiditems=bid_item_cost_test($_SESSION['officeid'],$cinar['estidret']);
	
	$qry0  = "select * from jest..CommissionBuilderCategory where access <= ".$_SESSION['clev']." order by descrip;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
	
	$qry0a  = "select officeid,gm,sm,am from jest..offices where officeid = ".$_SESSION['officeid'].";";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
    $nrow0a= mssql_num_rows($res0a);
	
	if ($nrow0 > 0)
	{
		$commcat_ar[$row0['catid']]=array('label'=>$row0['label'],'descrip'=>$row0['descrip']);
	}
	
	//Grab Category 1 SR Specific Comm
	if ($cinar['renov']==1) // Base Comms
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);
    $nrow1a= mssql_num_rows($res1a);
	
	if ($nrow1a==1)
	{
		$c1cmid		=$row1a['cmid'];
		$c1secid	=$row1a['secid'];
		$c1catid	=$row1a['ctgry'];
		$c1rate		=$row1a['rwdrate'];
		$c1ctype	=$row1a['ctype'];
		$c1amt		=$row1a['rwdamt'];
		$c1d1		=strtotime($row1a['d1']);
		$c1d2		=strtotime($row1a['d2']);
		$c1thresh	=$row1a['trgwght'];
		$c1label	=$row1a['name'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=0;";
		}
		
		$res1b = mssql_query($qry1b);
		$row1b = mssql_fetch_array($res1b);
		$nrow1b= mssql_num_rows($res1b);
		
		$c1cmid		=$row1b['cmid'];
		$c1secid	=$row1b['secid'];
		$c1catid	=$row1b['ctgry'];
		$c1rate		=$row1b['rwdrate'];
		$c1ctype	=$row1b['ctype'];
		$c1amt		=$row1b['rwdamt'];
		$c1d1		=strtotime($row1b['d1']);
		$c1d2		=strtotime($row1b['d2']);
		$c1thresh	=$row1b['trgwght'];
		$c1label	=$row1b['name'];
	}

	if ($cinar['renov']==1) //Grab Category 2 SR OU Specific Comm
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
	$nrow2a= mssql_num_rows($res2a);
	
	if ($nrow2a==1)
	{
		$c2cmid		=$row2a['cmid'];
		$c2secid	=$row2a['secid'];
		$c2catid	=$row2a['ctgry'];
		$c2rate		=$row2a['rwdrate'];
		$c2ctype	=$row2a['ctype'];
		$c2amt		=$row2a['rwdamt'];
		$c2d1		=strtotime($row2a['d1']);
		$c2d2		=strtotime($row2a['d2']);
		$c2thresh	=$row2a['trgwght'];
		$c2label	=$row2a['name'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=0;";
		}

		$res2b = mssql_query($qry2b);
		$row2b = mssql_fetch_array($res2b);
		$nrow2b= mssql_num_rows($res2b);
		
		$c2cmid		=$row2b['cmid'];
		$c2secid	=$row2b['secid'];
		$c2catid	=$row2b['ctgry'];
		$c2rate		=$row2b['rwdrate'];
		$c2ctype	=$row2b['ctype'];
		$c2amt		=$row2b['rwdamt'];
		$c2d1		=strtotime($row2b['d1']);
		$c2d2		=strtotime($row2b['d2']);
		$c2thresh	=$row2b['trgwght'];
		$c2label	=$row2b['name'];
	}

	if (isset($cinar['sidm']))
	{
		//Grab Category 4 Comms (Sales Manager)
		$qry3a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=".$cinar['sidm'].";";
		$res3a = mssql_query($qry3a);
		$row3a = mssql_fetch_array($res3a);
		$nrow3a= mssql_num_rows($res3a);
		
		if ($nrow3a > 0)
		{
			$c3cmid		=$row3a['cmid'];
			$c3secid	=$row3a['secid'];
			$c3catid	=$row3a['ctgry'];
			$c3rate		=$row3a['rwdrate'];
			$c3ctype	=$row3a['ctype'];
			$c3amt		=$row3a['rwdamt'];
			$c3d1		=strtotime($row3a['d1']);
			$c3d2		=strtotime($row3a['d2']);
			$c3thresh	=$row3a['trgwght'];
			$c3label	=$row3a['name'];
		}
		else
		{
			$qry3b  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=0;";
			$res3b = mssql_query($qry3b);
			$row3b = mssql_fetch_array($res3b);
			$nrow3b= mssql_num_rows($res3b);
			
			$c3cmid		=$row3b['cmid'];
			$c3secid	=$row3b['secid'];
			$c3catid	=$row3b['ctgry'];
			$c3rate		=$row3b['rwdrate'];
			$c3ctype	=$row3b['ctype'];
			$c3amt		=$row3b['rwdamt'];
			$c3d1		=strtotime($row3b['d1']);
			$c3d2		=strtotime($row3b['d2']);
			$c3thresh	=$row3b['trgwght'];
			$c3label	=$row3b['name'];
		}
	}
	
	//Grab Category 6 Comms (Bullet Commissions)
	$qry4a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=6 and active=1 order by trgwght desc,ctgry asc;";
	$res4a = mssql_query($qry4a);
	$nrow4a= mssql_num_rows($res4a);
	
	if ($nrow4a > 0)
	{
		while ($row4a = mssql_fetch_array($res4a))
		{
				$grpcomar[$row4a['linkid']][]=array(
						'cmid'=>	$row4a['cmid'],
						'secid'=>	$row4a['secid'],
						'catid'=>	$row4a['ctgry'],
						'ctype'=>	$row4a['ctype'],
						'rwdrate'=>	$row4a['rwdrate'],
						'trgwght'=>	$row4a['trgwght'],
						'd1'=>		strtotime($row4a['d1']),
						'd2'=>		strtotime(date('m/d/y',strtotime($row4a['d2'])). ' 23:59:59'),
						'active'=>	$row4a['active'],
						'label'=>	$row4a['name'],
						'rwdamt'=>	$row4a['rwdamt'],
						'linkid'=>	$row4a['linkid'],
						'trgsrc'=>	$row4a['trgsrc'],
						'trgsrcval'=>$row4a['trgsrcval']
					);
		}
	}
	
	//Grab Category 7 Comms (General Manager)
	$qry7a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=7 and active=1 and secid=".$row0a['gm'].";";
	$res7a = mssql_query($qry7a);
	$row7a = mssql_fetch_array($res7a);
	$nrow7a= mssql_num_rows($res7a);
	
	if ($nrow7a == 1)
	{
		$c7cmid		=$row7a['cmid'];
		$c7secid	=$row7a['secid'];
		$c7catid	=$row7a['ctgry'];
		$c7rate		=$row7a['rwdrate'];
		$c7ctype	=$row7a['ctype'];
		$c7amt		=$row7a['rwdamt'];
		$c7d1		=strtotime($row7a['d1']);
		$c7d2		=strtotime($row7a['d2']);
		$c7thresh	=$row7a['trgwght'];
		$c7label	=$row7a['name'];
	}	
	
	if ($cinar['renov']==1) //Grab Category 8 SR Override Comm
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res8a = mssql_query($qry8a);
	$row8a = mssql_fetch_array($res8a);
	$nrow8a= mssql_num_rows($res8a);
	
	if ($nrow8a == 1)
	{
		$c8cmid		=$row8a['cmid'];
		$c8secid	=$row8a['secid'];
		$c8catid	=$row8a['ctgry'];
		$c8rate		=$row8a['rwdrate'];
		$c8ctype	=$row8a['ctype'];
		$c8amt		=$row8a['rwdamt'];
		$c8d1		=strtotime($row8a['d1']);
		$c8d2		=strtotime($row8a['d2']);
		$c8thresh	=$row8a['trgwght'];
		$c8label	=$row8a['name'];
		$c8trgsrc	=$row8a['trgsrc'];
		$c8trgsrcval=$row8a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=0;";
		}

		$res8b = mssql_query($qry8b);
		$row8b = mssql_fetch_array($res8b);
		$nrow8b= mssql_num_rows($res8b);
		
		$c8cmid		=$row8b['cmid'];
		$c8secid	=$row8b['secid'];
		$c8catid	=$row8b['ctgry'];
		$c8rate		=$row8b['rwdrate'];
		$c8ctype	=$row8b['ctype'];
		$c8amt		=$row8b['rwdamt'];
		$c8d1		=strtotime($row8b['d1']);
		$c8d2		=strtotime($row8b['d2']);
		$c8thresh	=$row8b['trgwght'];
		$c8label	=$row8b['name'];
		$c8trgsrc	=$row8b['trgsrc'];
		$c8trgsrcval=$row8b['trgsrcval'];
	}

	if (isset($cinar['contdate']))
	{
		$drange=$cinar['contdate'];
	}
	else
	{
		$drange=$cinar['sysdate'];
	}
	
	$dbg=1;
	if ($dbg==1 && $_SESSION['securityid']==SYS_ADMIN)
	{		
		echo "           <tr>\n";
		echo "              <td colspan=\"7\">\n";
		
		echo "<pre>";
		echo 'VARIABLES<br>';
		print_r($cinar);
		echo '<br><br>';
		echo 'STANDARD COMMISSIONS<br>';
		print_r($comar);
		echo '<br><br>';
		echo 'GROUP COMMISSIONS<br>';
		print_r($grpcomar);
		echo '<br><br>';
		echo 'TIMESTAMP<br>';
		echo time();
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "                        <form method=\"post\">\n";
	echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$cinar['estidret']."\">\n";
	echo "							 <input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "                           <input type=\"hidden\" name=\"call\" value=\"CreateContract\">\n";
	echo "                           <input type=\"hidden\" name=\"adjbook\" value=\"".number_format($cinar['fadjbookamt'], 2, '.', '')."\">\n";
	echo "                           <input type=\"hidden\" name=\"oubook\" value=\"".number_format(($cinar['fctramt'] - $cinar['fadjbookamt']), 2, '.', '')."\">\n";

	if ($cinar['taxtrig']==1)
	{
		echo "                           <input type=\"hidden\" name=\"salestax\" value=\"".$cinar['frtax']."\">\n";
	}
	
	//echo "			<table background=\"white\" bordercolor=\"gray\" width=\"".array_sum($col_struct)."px\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."\"><b>Commissions</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[1]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[3]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	
	if ($nrow1a == 1 || $nrow1b == 1) // Base Entries
	{
		if ($c1ctype==1)
		{
			$fc1ctype='fx';
		}
		elseif ($c1ctype==2)
		{
			$fc1ctype='%';
		}
		else
		{
			$fc1ctype='';
		}
		
		$c1amt=$cinar['fadjbookamt'] * $c1rate;
		$tcomm=$tcomm + $c1amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Base Commission</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $c1rate * 100;
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc1ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($c1amt < 0)
		{
			echo "              <font color=\"red\">".number_format($c1amt, 2, '.', '')."</font>\n";
		}
		else
		{
			echo number_format($c1amt, 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][cmid]\" value=\"".$c1cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdamt]\" value=\"".number_format($c1amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][secid]\" value=\"".$c1secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][catid]\" value=\"".$c1catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][ctype]\" value=\"".$c1ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdrate]\" value=\"".$c1rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][trgwght]\" value=\"".$c1thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d1]\" value=\"".$c1d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d2]\" value=\"".$c1d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][label]\" value=\"".$c1label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	if (isset($cinar['tbullets']) && $cinar['tbullets'] > 0 && $nrow4a > 0 && ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
	{
		foreach ($grpcomar as $gn1 => $gv1)
		{
			$tsval=0;
			foreach ($gv1 as $gn2 => $gv2)
			{
				if ($cinar['tbullets']==$gv2['trgwght'] && time() >= $gv2['d1'] && time() < $gv2['d2'])
				{
					if ($gv2['trgsrc']==6)
					{
						$tbamt=0;
						if ($gv2['ctype']==1) //Fixed
						{
							$rate	=0;
							$tbamt 	=$gv2['rwdamt'];
						}
						elseif ($gv2['ctype']==2)
						{
							if ($gv2['trgsrcval']==1) //Contract Amt
							{
								$tbamt=($cinar['fadjbookamt'] * $gv2['rwdrate']);
							}
							elseif ($gv2['trgsrcval']==2) //
							{
								$tbamt=(($cinar['fctramt']-$cinar['fadjbookamt']) * ($gv2['rwdrate'] * .01));
							}
						}
						
						$tcomm=$tcomm+$tbamt;
						echo "           <tr>\n";
						
						if ($gv2['label']=='SRU')
						{
							echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$cinar['tbullets']." SmartFeature</b></td>\n";
						}
						else
						{
							echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$cinar['tbullets']." ".$gv2['label']."</b></td>\n";
						}
						
						echo "              <td class=\"wh\" align=\"center\">".($gv2['rwdrate'] * 100)."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						
						if ($gv2['ctype']==2)
						{
							echo '%';
						}
						
						echo "				</td>\n";
						echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($tbamt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][cmid]\" value=\"".$gv2['cmid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdamt]\" value=\"".number_format($tbamt, 2, '.', '')."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][secid]\" value=\"".$gv2['secid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][catid]\" value=\"".$gv2['catid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][ctype]\" value=\"".$gv2['ctype']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdrate]\" value=\"".$gv2['rwdrate']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][trgwght]\" value=\"".$gv2['trgwght']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d1]\" value=\"".$gv2['d1']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d2]\" value=\"".$gv2['d2']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][label]\" value=\"".($cinar['tbullets']." ".$gv2['label'])."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][tbullets]\" value=\"".$cinar['tbullets']."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}
	
	if ($nrow2a == 1 || $nrow2b == 1) //OU Entries
	{
		if ($c2ctype==1)
		{
			$fc2ctype='fx';
		}
		elseif ($c2ctype==2)
		{
			$fc2ctype='%';
		}
		else
		{
			$fc2ctype='';
		}
		
		$c2amt=($cinar['fctramt'] - $cinar['fadjbookamt'])  * $c2rate;
		$tcomm=$tcomm + $c2amt;
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Over/<font color=\"red\">Under</font></b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $c2rate * 100;
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc2ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($c2amt < 0)
		{
			echo "              <font color=\"red\">".number_format($c2amt, 2, '.', '')."</font>\n";
		}
		else
		{
			echo number_format($c2amt, 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][cmid]\" value=\"".$c2cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdamt]\" value=\"".number_format($c2amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][secid]\" value=\"".$c2secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][catid]\" value=\"".$c2catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][ctype]\" value=\"".$c2ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdrate]\" value=\"".$c2rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][trgwght]\" value=\"".$c2thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d1]\" value=\"".$c2d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d2]\" value=\"".$c2d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][label]\" value=\"".$c2label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	if (isset($cinar['sidm']) && $cinar['fctramt'] > 0) //SalesManager
	{
		if ($nrow3a > 0 || $nrow3b > 0)
		{
			$c3amt=($cinar['fctramt'])  * $c3rate;
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][cmid]\" value=\"".$c3cmid."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][rwdamt]\" value=\"".number_format($c3amt, 2, '.', '')."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][secid]\" value=\"".$c3secid."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][catid]\" value=\"".$c3catid."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][ctype]\" value=\"".$c3ctype."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][rwdrate]\" value=\"".$c3rate."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][trgwght]\" value=\"".$c3thresh."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][d1]\" value=\"".$c3d1."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][d2]\" value=\"".$c3d2."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][label]\" value=\"".$c3label."\">\n";
		}
	}
	
	/*
	if ($nrow7a > 0)
	{
		$c7amt=($cinar['fctramt'])  * $c7rate;
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][cmid]\" value=\"".$c7cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][rwdamt]\" value=\"".number_format($c7amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][secid]\" value=\"".$c7secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][catid]\" value=\"".$c7catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][ctype]\" value=\"".$c7ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][rwdrate]\" value=\"".$c7rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][trgwght]\" value=\"".$c7thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][d1]\" value=\"".$c7d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][d2]\" value=\"".$c7d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][label]\" value=\"".$c7label."\">\n";
	}
	*/
	
	/*if (is_array($cinar) && $nrow3a > 0)
	{
		foreach ($comar as $cn => $cv)
		{
			if ($drange >= $cv['d1'] && $drange < $cv['d2'])
			{
				if ($cv['ctype']==1)
				{
					$ctype='fx';
				}
				elseif ($cv['ctype']==2)
				{
					$ctype='%';
				}
				else
				{
					$ctype='';
				}
				
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][cmid]\" value=\"".$cv['cmid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][secid]\" value=\"".$cv['secid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\" value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\" value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".$cv['rate']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][trgwght]\" value=\"".$cv['thresh']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][d1]\" value=\"".$cv['d1']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][d2]\" value=\"".$cv['d2']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\" value=\"".$cv['label']."\">\n";
				
				if ($cv['catid'] == 3)
				{
					
					if ($cv['secid']==0 || $cinar['estsecid']==$cv['secid'])
					{
						if ($ctype==1)
						{
							$amt=($cinar['fadjbookamt'] * $cv['rwdrate']);
						}
						else
						{
							$amt=$cv['rwdamt'];
						}
						
						$tcomm=$tcomm+$amt;
						echo "           <tr>\n";
						echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$cv['label']."</b></td>\n";
						echo "              <td class=\"wh\" align=\"center\"></td>\n";
						echo "              <td class=\"wh\" align=\"center\">".$ctype."</td>\n";
						echo "              <td class=\"wh\" align=\"right\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($amt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($amt, 2, '.', '')."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}

				}
				elseif ($cv['catid'] == 4)
				{

					if ($cv['secid']==0 || $cinar['estsecid']==$cv['secid'])
					{
						if ($ctype==1)
						{
							$amt=($cinar['fadjbookamt'] * $cv['rwdrate']);
						}
						else
						{
							$amt=$cv['rwdamt'];
						}
						
						$tcomm=$tcomm+$amt;
						echo "           <tr>\n";
						echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$cv['label']."</b></td>\n";
						echo "              <td class=\"wh\" align=\"center\"></td>\n";
						echo "              <td class=\"wh\" align=\"center\">".$ctype."</td>\n";
						echo "              <td class=\"wh\" align=\"right\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($amt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($amt, 2, '.', '')."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}*/
	
	if (($nrow8a == 1 || $nrow8b == 1) && $tcomm < $c8amt) //Forced Override Entries (Always LAST!)
	{
		if ($c8ctype==1)
		{
			$fc8ctype='fx';
		}
		elseif ($c8ctype==2)
		{
			$fc8ctype='%';
		}
		else
		{
			$fcctype='';
		}
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Total</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($tcomm < 0)
		{
			echo "					<font color=\"red\">".number_format($tcomm, 2, '.', '')."</font>";
		}
		else
		{
			echo number_format($tcomm, 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><img src=\"images/pixel.gif\" height=\"12px\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		
		$c8amt=$c8amt + ($tcomm * -1);
		$tcomm=$tcomm + $c8amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\" title=\"Adjustment required for Minimum Commission\"><b>Override</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc8ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		echo number_format($c8amt, 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][cmid]\" value=\"".$c8cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdamt]\" value=\"".number_format($c8amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][secid]\" value=\"".$c8secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][catid]\" value=\"".$c8catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][ctype]\" value=\"".$c8ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdrate]\" value=\"".$c8rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][trgwght]\" value=\"".$c8thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d1]\" value=\"".$c8d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d2]\" value=\"".$c8d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][label]\" value=\"".$c8label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Total</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\">".number_format($tcomm, 2, '.', '')."</font>";
	}
	else
	{
		echo number_format($tcomm, 2, '.', '');
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "					<div class=\"noPrint\">\n";
	
	if ($_SESSION['elev'] >= 5)
	{
		if ($cinar['jobid']=='0')
		{
			if ($_SESSION['elev'] >= 9)
			{
				echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract Override: Your security level allows you to override control protocols\">\n";
			}
			else
			{
				if (isset($errbiditems['no_ret']) and $errbiditems['no_ret'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Retail Price on one or more Bid Items\">\n";
				}
				elseif (isset($errbiditems['no_cst']) and $errbiditems['no_cst'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Cost on one or more Bid Items\">\n";
				}
				elseif (isset($errbiditems['th_cst']) and $errbiditems['th_cst'] > 0)
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Warning: Bid Item Cost too high on one or more Bid Items\">\n";
				}
				else
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract\">\n";
				}
			}
		}
		else
		{
			echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Contract exists for this Estimate\" DISABLED>\n";
		}
	}
	
	echo "					</div>\n";
	echo "				</td>\n";
	echo "           </tr>\n";
	echo "			</form>\n";
	
	return number_format($tcomm, 2, '.', '');
}

function CommissionScheduleRO_GMSM($cinar)
{
	//display_array($cinar);
	
	$qry0a  = "select officeid,gm,sm,am from jest..offices where officeid = ".$_SESSION['officeid'].";";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
    $nrow0a= mssql_num_rows($res0a);
	
	if (isset($cinar['sidm']) && $cinar['fctramt'] > 0)
	{
		//Grab Category 4 Comms (Sales Manager)
		$qry3a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=".$cinar['sidm'].";";
		$res3a = mssql_query($qry3a);
		$row3a = mssql_fetch_array($res3a);
		$nrow3a= mssql_num_rows($res3a);
		
		if ($nrow3a > 0)
		{
			$c3cmid		=$row3a['cmid'];
			$c3secid	=$row3a['secid'];
			$c3catid	=$row3a['ctgry'];
			$c3rate		=$row3a['rwdrate'];
			$c3ctype	=$row3a['ctype'];
			$c3amt		=$row3a['rwdamt'];
			$c3d1		=strtotime($row3a['d1']);
			$c3d2		=strtotime($row3a['d2']);
			$c3thresh	=$row3a['trgwght'];
			$c3label	=$row3a['name'];
		}
		else
		{
			$qry3b  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=0;";
			$res3b = mssql_query($qry3b);
			$row3b = mssql_fetch_array($res3b);
			$nrow3b= mssql_num_rows($res3b);
			
			$c3cmid		=$row3b['cmid'];
			$c3secid	=$row3b['secid'];
			$c3catid	=$row3b['ctgry'];
			$c3rate		=$row3b['rwdrate'];
			$c3ctype	=$row3b['ctype'];
			$c3amt		=$row3b['rwdamt'];
			$c3d1		=strtotime($row3b['d1']);
			$c3d2		=strtotime($row3b['d2']);
			$c3thresh	=$row3b['trgwght'];
			$c3label	=$row3b['name'];
		}
		
		if ($nrow3a > 0 || $nrow3b > 0)
		{
			$c3amt=($cinar['fctramt'])  * $c3rate;
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][cmid]\" value=\"".$c3cmid."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][rwdamt]\" value=\"".number_format($c3amt, 2, '.', '')."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][secid]\" value=\"".$c3secid."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][catid]\" value=\"".$c3catid."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][ctype]\" value=\"".$c3ctype."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][rwdrate]\" value=\"".$c3rate."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][trgwght]\" value=\"".$c3thresh."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][d1]\" value=\"".$c3d1."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][d2]\" value=\"".$c3d2."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][label]\" value=\"".$c3label."\">\n";
			echo "					<input type=\"hidden\" name=\"csched[".$c3cmid."][uid]\" value=\"".md5(session_id().time().$cinar['estid']).".".$_SESSION['securityid']."\">\n";
		}
	}

	//Grab Category 7 Comms (General Manager)
	$qry7a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=7 and active=1 and secid=".$row0a['gm'].";";
	$res7a = mssql_query($qry7a);
	$row7a = mssql_fetch_array($res7a);
	$nrow7a= mssql_num_rows($res7a);
	
	if ($nrow7a == 1)
	{
		$c7cmid		=$row7a['cmid'];
		$c7secid	=$row7a['secid'];
		$c7catid	=$row7a['ctgry'];
		$c7rate		=$row7a['rwdrate'];
		$c7ctype	=$row7a['ctype'];
		$c7amt		=$row7a['rwdamt'];
		$c7d1		=strtotime($row7a['d1']);
		$c7d2		=strtotime($row7a['d2']);
		$c7thresh	=$row7a['trgwght'];
		$c7label	=$row7a['name'];
		
		$c7amt=($cinar['fctramt'])  * $c7rate;
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][cmid]\" value=\"".$c7cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][rwdamt]\" value=\"".number_format($c7amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][secid]\" value=\"".$c7secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][catid]\" value=\"".$c7catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][ctype]\" value=\"".$c7ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][rwdrate]\" value=\"".$c7rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][trgwght]\" value=\"".$c7thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][d1]\" value=\"".$c7d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][d2]\" value=\"".$c7d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][label]\" value=\"".$c7label."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c7cmid."][uid]\" value=\"".md5(session_id().time().$cinar['estid']).".".$_SESSION['securityid']."\">\n";
	}
}

function CommissionScheduleRO_NEW($cinar,$col_struct)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	$tsecid=26;
	$dbg=1;
	if (isset($dbg) && $dbg==0 && $_SESSION['securityid']==$tsecid)
	{
        echo __FUNCTION__.'<br>';
		echo "<pre>";
		print_r($cinar);
		//echo 'MOD:'.$_SESSION['modcomm'].'<br>';
		echo "</pre>";
	}

	$tcomm=0;
	$commcat_ar=array();
	$comar=array();
	$grpcomar=array();
	$tiercomar=array();
	
	//display_array($cinar);
	
	$errbiditems=bid_item_cost_test($_SESSION['officeid'],$cinar['estidret']);
	
	if (isset($cinar['tbullets']) and $cinar['tbullets'] > 0)
	{
		$bullets=$cinar['tbullets'];
	}
	else
	{
		$bullets=0;
	}
	
	//echo $bullets.'<br>';
	$qry0  = "select * from jest..CommissionBuilderCategory where access <= ".$_SESSION['clev']." order by descrip;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
	
	$qry0a  = "select officeid,gm,sm,am from jest..offices where officeid = ".$_SESSION['officeid'].";";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
    $nrow0a= mssql_num_rows($res0a);
	
	if ($nrow0 > 0)
	{
		$commcat_ar[$row0['catid']]=array('label'=>$row0['label'],'descrip'=>$row0['descrip']);
	}
	
	//Grab Category 1 SR Specific Comm
	if ($cinar['renov']==1) // Base Comms
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);
    $nrow1a= mssql_num_rows($res1a);
	
	if ($nrow1a==1)
	{
		$c1cmid		=$row1a['cmid'];
		$c1secid	=$row1a['secid'];
		$c1catid	=$row1a['ctgry'];
		$c1rate		=$row1a['rwdrate'];
		$c1ctype	=$row1a['ctype'];
		$c1amt		=$row1a['rwdamt'];
		$c1d1		=strtotime($row1a['d1']);
		$c1d2		=strtotime($row1a['d2']);
		$c1thresh	=$row1a['trgwght'];
		$c1label	=$row1a['name'];
        $c1trgcsrc  =$row1a['trgsrc'];
        $c1trgcsrcval=$row1a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=0;";
		}
		
		$res1b = mssql_query($qry1b);
		$row1b = mssql_fetch_array($res1b);
		$nrow1b= mssql_num_rows($res1b);
		
		$c1cmid		=$row1b['cmid'];
		$c1secid	=$row1b['secid'];
		$c1catid	=$row1b['ctgry'];
		$c1rate		=$row1b['rwdrate'];
		$c1ctype	=$row1b['ctype'];
		$c1amt		=$row1b['rwdamt'];
		$c1d1		=strtotime($row1b['d1']);
		$c1d2		=strtotime($row1b['d2']);
		$c1thresh	=$row1b['trgwght'];
		$c1label	=$row1b['name'];
        $c1trgcsrc  =$row1b['trgsrc'];
        $c1trgcsrcval=$row1b['trgsrcval'];
	}

	if ($cinar['renov']==1) //Grab Category 2 SR OU Specific Comm
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
	$nrow2a= mssql_num_rows($res2a);
	
	if ($nrow2a==1)
	{
		$c2cmid		=$row2a['cmid'];
		$c2secid	=$row2a['secid'];
		$c2catid	=$row2a['ctgry'];
		$c2rate		=$row2a['rwdrate'];
		$c2ctype	=$row2a['ctype'];
		$c2amt		=$row2a['rwdamt'];
		$c2d1		=strtotime($row2a['d1']);
		$c2d2		=strtotime($row2a['d2']);
		$c2thresh	=$row2a['trgwght'];
		$c2label	=$row2a['name'];
        $c2trgcsrc  =$row2a['trgsrc'];
        $c2trgcsrcval=$row2a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=0;";
		}

		$res2b = mssql_query($qry2b);
		$row2b = mssql_fetch_array($res2b);
		$nrow2b= mssql_num_rows($res2b);
		
		//echo $nrow2b.'<br>';
		
		$c2cmid		=$row2b['cmid'];
		$c2secid	=$row2b['secid'];
		$c2catid	=$row2b['ctgry'];
		$c2rate		=$row2b['rwdrate'];
		$c2ctype	=$row2b['ctype'];
		$c2amt		=$row2b['rwdamt'];
		$c2d1		=strtotime($row2b['d1']);
		$c2d2		=strtotime($row2b['d2']);
		$c2thresh	=$row2b['trgwght'];
		$c2label	=$row2b['name'];
        $c2trgcsrc  =$row2b['trgsrc'];
        $c2trgcsrcval=$row2b['trgsrcval'];
	}

	/* MOVED TO CommissionScheduleRO_GMSM
	if (isset($cinar['sidm']))
	{
		//Grab Category 4 Comms (Sales Manager)
		$qry3a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=".$cinar['sidm'].";";
		$res3a = mssql_query($qry3a);
		$row3a = mssql_fetch_array($res3a);
		$nrow3a= mssql_num_rows($res3a);
		
		if ($nrow3a > 0)
		{
			$c3cmid		=$row3a['cmid'];
			$c3secid	=$row3a['secid'];
			$c3catid	=$row3a['ctgry'];
			$c3rate		=$row3a['rwdrate'];
			$c3ctype	=$row3a['ctype'];
			$c3amt		=$row3a['rwdamt'];
			$c3d1		=strtotime($row3a['d1']);
			$c3d2		=strtotime($row3a['d2']);
			$c3thresh	=$row3a['trgwght'];
			$c3label	=$row3a['name'];
		}
		else
		{
			$qry3b  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=0;";
			$res3b = mssql_query($qry3b);
			$row3b = mssql_fetch_array($res3b);
			$nrow3b= mssql_num_rows($res3b);
			
			$c3cmid		=$row3b['cmid'];
			$c3secid	=$row3b['secid'];
			$c3catid	=$row3b['ctgry'];
			$c3rate		=$row3b['rwdrate'];
			$c3ctype	=$row3b['ctype'];
			$c3amt		=$row3b['rwdamt'];
			$c3d1		=strtotime($row3b['d1']);
			$c3d2		=strtotime($row3b['d2']);
			$c3thresh	=$row3b['trgwght'];
			$c3label	=$row3b['name'];
		}
	}
	*/
	
	//Grab Category 6 Comms (Bullets/SmartFeatures)
	if ($bullets > 0 and $cinar['estsecid']!=1952)
	{
		$qry4a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=6 and active=1 order by trgwght desc,ctgry asc;";
		$res4a = mssql_query($qry4a);
		$nrow4a= mssql_num_rows($res4a);
		
		if ($nrow4a > 0)
		{
			while ($row4a = mssql_fetch_array($res4a))
			{
					$grpcomar[$row4a['linkid']][]=array(
							'cmid'=>	$row4a['cmid'],
							'secid'=>	$row4a['secid'],
							'catid'=>	$row4a['ctgry'],
							'ctype'=>	$row4a['ctype'],
							'rwdrate'=>	$row4a['rwdrate'],
							'trgwght'=>	$row4a['trgwght'],
							'd1'=>		strtotime($row4a['d1']),
							'd2'=>		strtotime(date('m/d/y',strtotime($row4a['d2'])). ' 23:59:59'),
							'active'=>	$row4a['active'],
							'label'=>	$row4a['name'],
							'rwdamt'=>	$row4a['rwdamt'],
							'linkid'=>	$row4a['linkid'],
							'trgsrc'=>	$row4a['trgsrc'],
							'trgsrcval'=>$row4a['trgsrcval']
						);
			}
		}
	}
	
	//Grab Category 9 Comms (Tiered Commissions)
	$qry9a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=9 and active=1 order by trgwght desc,ctgry asc;";
	$res9a = mssql_query($qry9a);
	$nrow9a= mssql_num_rows($res9a);
	
	if ($nrow9a > 0)
	{
		while ($row9a = mssql_fetch_array($res9a))
		{
				$tiercomar[$row9a['linkid']][]=array(
						'cmid'=>	$row9a['cmid'],
						'secid'=>	$row9a['secid'],
						'catid'=>	$row9a['ctgry'],
						'ctype'=>	$row9a['ctype'],
						'rwdrate'=>	$row9a['rwdrate'],
						'trgwght'=>	$row9a['trgwght'],
						'd1'=>		strtotime($row9a['d1']),
						'd2'=>		strtotime(date('m/d/y',strtotime($row9a['d2'])). ' 23:59:59'),
						'active'=>	$row9a['active'],
						'label'=>	$row9a['name'],
						'rwdamt'=>	$row9a['rwdamt'],
						'linkid'=>	$row9a['linkid'],
						'trgsrc'=>	$row9a['trgsrc'],
						'trgsrcval'=>$row9a['trgsrcval']
					);
		}
	}
	
	/* MOVED TO CommissionScheduleRO_GMSM
	//Grab Category 7 Comms (General Manager)
	$qry7a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=7 and active=1 and secid=".$row0a['gm'].";";
	$res7a = mssql_query($qry7a);
	$row7a = mssql_fetch_array($res7a);
	$nrow7a= mssql_num_rows($res7a);
	
	if ($nrow7a == 1)
	{
		$c7cmid		=$row7a['cmid'];
		$c7secid	=$row7a['secid'];
		$c7catid	=$row7a['ctgry'];
		$c7rate		=$row7a['rwdrate'];
		$c7ctype	=$row7a['ctype'];
		$c7amt		=$row7a['rwdamt'];
		$c7d1		=strtotime($row7a['d1']);
		$c7d2		=strtotime($row7a['d2']);
		$c7thresh	=$row7a['trgwght'];
		$c7label	=$row7a['name'];
	}
	*/
	
	if ($cinar['renov']==1) //Grab Category 8 SR Override Comm
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res8a = mssql_query($qry8a);
	$row8a = mssql_fetch_array($res8a);
	$nrow8a= mssql_num_rows($res8a);
	
	if ($nrow8a == 1)
	{
		$c8cmid		=$row8a['cmid'];
		$c8secid	=$row8a['secid'];
		$c8catid	=$row8a['ctgry'];
		$c8rate		=$row8a['rwdrate'];
		$c8ctype	=$row8a['ctype'];
		$c8amt		=$row8a['rwdamt'];
		$c8d1		=strtotime($row8a['d1']);
		$c8d2		=strtotime($row8a['d2']);
		$c8thresh	=$row8a['trgwght'];
		$c8label	=$row8a['name'];
		$c8trgsrc	=$row8a['trgsrc'];
		$c8trgsrcval=$row8a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=0;";
		}

		$res8b = mssql_query($qry8b);
		$row8b = mssql_fetch_array($res8b);
		$nrow8b= mssql_num_rows($res8b);
		
		$c8cmid		=$row8b['cmid'];
		$c8secid	=$row8b['secid'];
		$c8catid	=$row8b['ctgry'];
		$c8rate		=$row8b['rwdrate'];
		$c8ctype	=$row8b['ctype'];
		$c8amt		=$row8b['rwdamt'];
		$c8d1		=strtotime($row8b['d1']);
		$c8d2		=strtotime($row8b['d2']);
		$c8thresh	=$row8b['trgwght'];
		$c8label	=$row8b['name'];
		$c8trgsrc	=$row8b['trgsrc'];
		$c8trgsrcval=$row8b['trgsrcval'];
	}

	if (isset($cinar['contdate']))
	{
		$drange=$cinar['contdate'];
	}
	else
	{
		$drange=$cinar['sysdate'];
	}
	
	$dbg=0;
	if ($dbg==1 && $_SESSION['securityid']==SYS_ADMIN)
	{		
		echo "           <tr>\n";
		echo "              <td colspan=\"7\">\n";
		
		echo "<pre>";
		echo 'VARIABLES<br>';
		print_r($cinar);
		echo '<br><br>';
		echo 'STANDARD COMMISSIONS<br>';
		print_r($comar);
		echo '<br><br>';
		echo 'GROUP COMMISSIONS<br>';
		print_r($grpcomar);
		echo '<br><br>';
		echo 'TIMESTAMP<br>';
		echo time();
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "                        <form method=\"post\">\n";
	echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$cinar['estidret']."\">\n";
	echo "							 <input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "                           <input type=\"hidden\" name=\"call\" value=\"CreateContract\">\n";
	echo "                           <input type=\"hidden\" name=\"adjbook\" value=\"".number_format($cinar['fadjbookamt'], 2, '.', '')."\">\n";
	echo "                           <input type=\"hidden\" name=\"oubook\" value=\"".number_format(($cinar['fctramt'] - $cinar['fadjbookamt']), 2, '.', '')."\">\n";

	if ($cinar['taxtrig']==1)
	{
		echo "                           <input type=\"hidden\" name=\"salestax\" value=\"".$cinar['frtax']."\">\n";
	}
	
	//echo "			<table class=\"outer\" background=\"white\" bordercolor=\"gray\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."\"><b>Commissions</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[1]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[3]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	
	if ($nrow1a == 1 || $nrow1b == 1) // Base Entries
	{
		if ($c1ctype==1)
		{
			$fc1ctype='fx';
		}
		elseif ($c1ctype==2)
		{
			$fc1ctype='%';
		}
		else
		{
			$fc1ctype='';
		}
		
		if ($c1trgcsrcval == 1)
		{
			$c1amt=$cinar['fctramt'] * $c1rate;
            //echo '1!';
		}
		elseif ($c1trgcsrcval == 3) // Adjusted Price per Book
		{
			$c1amt=$cinar['fadjbookamt'] * $c1rate;
		}
		else
		{
			$c1amt=$cinar['fctramt'] * $c1rate;
		}
		
		$tcomm=$tcomm + $c1amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Base Commission</b></td>\n";		
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $c1rate * 100;
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc1ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($c1amt < 0)
		{
			echo "              <font color=\"red\">".number_format($c1amt, 2, '.', '')."</font>\n";
		}
		else
		{
			echo number_format($c1amt, 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//if ($bullets >= 3)
		//{
			echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][contrsrc]\" value=\"book\">\n";
			echo "<div class=\"noPrint\"><img id=\"basefrombook\" src=\"images/information.png\" width=\"12px\" height=\"12px\" title=\"Base Comm from Price per Book\"></div>\n";
        /*
		}
		else
		{
			echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][contrsrc]\" value=\"contract\">\n";
			echo "<div class=\"noPrint\"><img id=\"basefromcontract\" src=\"images/information.png\" width=\"12px\" height=\"12px\" title=\"Base Comm from Contract Amt\"></div>\n";
			
		}
        */
		
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][cmid]\" value=\"".$c1cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdamt]\" value=\"".number_format($c1amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][secid]\" value=\"".$c1secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][catid]\" value=\"".$c1catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][ctype]\" value=\"".$c1ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdrate]\" value=\"".$c1rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][trgwght]\" value=\"".$c1thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d1]\" value=\"".$c1d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d2]\" value=\"".$c1d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][label]\" value=\"".$c1label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	if ($nrow2a == 1 || $nrow2b == 1) //OU Entries
	{
		if ($cinar['renov']==1)
		{
			if ($cinar['fctramt'] - $cinar['fadjbookamt'] != 0)
			{
				$c2amt=($cinar['fctramt'] - $cinar['fadjbookamt']) * $c2rate;
				$tcomm=$tcomm + $c2amt;
				
				if ($c2ctype==1)
				{
					$fc2ctype='fx';
				}
				elseif ($c2ctype==2)
				{
					$fc2ctype='%';
				}
				else
				{
					$fc2ctype='';
				}
				
				echo "           <tr>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><b>Over/<font color=\"red\">Under</font></b></td>\n";		
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".($c2rate * 100)."</font>";
				}
				else
				{
					echo $c2rate * 100;
				}
			
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".$fc2ctype."</font>";
				}
				else
				{
					echo $fc2ctype;
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\">\n";
			
				if ($c2amt < 0)
				{
					echo "              <font color=\"red\">".number_format($c2amt, 2, '.', '')."</font>\n";
				}
				else
				{
					echo number_format($c2amt, 2, '.', '');
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][cmid]\" value=\"".$c2cmid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdamt]\" value=\"".number_format($c2amt, 2, '.', '')."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][secid]\" value=\"".$c2secid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][catid]\" value=\"".$c2catid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][ctype]\" value=\"".$c2ctype."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdrate]\" value=\"".$c2rate."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][trgwght]\" value=\"".$c2thresh."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d1]\" value=\"".$c2d1."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d2]\" value=\"".$c2d2."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][label]\" value=\"".$c2label."\">\n";
				echo "				</td>\n";
				echo "           </tr>\n";
			}
		}
		else
		{
			//if ($bullets >= 3 or ($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
            if (($cinar['fctramt'] - $cinar['fadjbookamt']) != 0)
			{
				$c2amt=($cinar['fctramt'] - $cinar['fadjbookamt']) * $c2rate;
				$tcomm=$tcomm + $c2amt;
				
				if ($c2ctype==1)
				{
					$fc2ctype='fx';
				}
				elseif ($c2ctype==2)
				{
					$fc2ctype='%';
				}
				else
				{
					$fc2ctype='';
				}
				
				echo "           <tr>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><b>Over/<font color=\"red\">Under</font></b></td>\n";		
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".($c2rate * 100)."</font>";
				}
				else
				{
					echo $c2rate * 100;
				}
			
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".$fc2ctype."</font>";
				}
				else
				{
					echo $fc2ctype;
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\">\n";
			
				if ($c2amt < 0)
				{
					echo "              <font color=\"red\">".number_format($c2amt, 2, '.', '')."</font>\n";
				}
				else
				{
					echo number_format($c2amt, 2, '.', '');
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][cmid]\" value=\"".$c2cmid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdamt]\" value=\"".number_format($c2amt, 2, '.', '')."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][secid]\" value=\"".$c2secid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][catid]\" value=\"".$c2catid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][ctype]\" value=\"".$c2ctype."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdrate]\" value=\"".$c2rate."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][trgwght]\" value=\"".$c2thresh."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d1]\" value=\"".$c2d1."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d2]\" value=\"".$c2d2."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][label]\" value=\"".$c2label."\">\n";
				echo "				</td>\n";
				echo "           </tr>\n";
			}
		}
	}
	
	if ((isset($nrow4a) and $nrow4a > 0) and (isset($cinar['tbullets']) and $cinar['tbullets'] > 0) and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
	{
		foreach ($grpcomar as $gn1 => $gv1)
		{
			$tsval=0;
			foreach ($gv1 as $gn2 => $gv2)
			{
				if ($cinar['tbullets']==$gv2['trgwght'] && time() >= $gv2['d1'] && time() < $gv2['d2'])
				{
					if ($gv2['trgsrc']==6)
					{
						$tbamt=0;
						if ($gv2['ctype']==1) //Fixed
						{
							$rate	=0;
							$tbamt 	=$gv2['rwdamt'];
						}
						elseif ($gv2['ctype']==2)
						{
							if ($gv2['trgsrcval']==1) //Contract Amt
							{
								$tbamt=($cinar['fadjbookamt'] * $gv2['rwdrate']);
							}
							elseif ($gv2['trgsrcval']==2) //
							{
								$tbamt=(($cinar['fctramt']-$cinar['fadjbookamt']) * ($gv2['rwdrate'] * .01));
							}
						}
						
						$tcomm=$tcomm+$tbamt;
						echo "           <tr>\n";
						echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						
						if ($gv2['label']=='SRU')
						{
							echo "              <td class=\"wh\" align=\"right\"><b>".$cinar['tbullets']." SmartFeatures</b></td>\n";
						}
						else
						{
							echo "              <td class=\"wh\" align=\"right\"><b>".$cinar['tbullets']." ".$gv2['label']."</b></td>\n";
						}
						
						echo "              <td class=\"wh\" align=\"center\">".($gv2['rwdrate'] * 100)."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						
						if ($gv2['ctype']==2)
						{
							echo '%';
						}
						
						echo "				</td>\n";
						echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($tbamt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][cmid]\" value=\"".$gv2['cmid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdamt]\" value=\"".number_format($tbamt, 2, '.', '')."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][secid]\" value=\"".$gv2['secid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][catid]\" value=\"".$gv2['catid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][ctype]\" value=\"".$gv2['ctype']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdrate]\" value=\"".$gv2['rwdrate']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][trgwght]\" value=\"".$gv2['trgwght']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d1]\" value=\"".$gv2['d1']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d2]\" value=\"".$gv2['d2']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][label]\" value=\"".$gv2['label']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][tbullets]\" value=\"".$cinar['tbullets']."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}
	//else
	//{
	//	echo "           <tr>\n";
	//	echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$bullets." SmartFeatures</b></td>\n";
	//	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	//	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	//	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	//	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	//	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	//	echo "           </tr>\n";
	//}
	
	if ($nrow9a > 0 and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
	{
		//echo 'Cat 9.0<br>';
		$tblock=false;
		foreach ($tiercomar as $tn1 => $tv1)
		{
			$tsvalt=0;
			foreach ($tv1 as $tn2 => $tv2)
			{
				if ($tv2['trgsrc'] == 7)
				{
					if (!$tblock and $cinar['fctramt'] >= $tv2['trgwght'] and (time() >= $tv2['d1'] and time() < $tv2['d2']))
					{
						$tbamtt=0;
						if ($tv2['ctype']==1) //Fixed
						{
							$tbamtt =$tv2['rwdamt'];
						}
						elseif ($tv2['ctype']==2) // Percent
						{
							if ($tv2['trgsrcval']==7) //Contract Amt
							{
								$tbamtt=($cinar['fctramt'] * $tv2['rwdrate']);
								$tblock=true;
							}
							else
							{
								$tbamtt=0;
							}
						}
						
						$tcomm=$tcomm+$tbamtt;
						
						echo "           <tr>\n";
						echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\"><b>Merit Bonus</b></td>\n";
						echo "              <td class=\"wh\" align=\"center\">".($tv2['rwdrate'] * 100)."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						
						if ($tv2['ctype']==2)
						{
							echo '%';
						}
						
						echo "				</td>\n";
						echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($tbamtt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][cmid]\" value=\"".$tv2['cmid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][rwdamt]\" value=\"".number_format($tbamtt, 2, '.', '')."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][secid]\" value=\"".$tv2['secid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][catid]\" value=\"".$tv2['catid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][ctype]\" value=\"".$tv2['ctype']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][rwdrate]\" value=\"".$tv2['rwdrate']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][trgwght]\" value=\"".$tv2['trgwght']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][d1]\" value=\"".$tv2['d1']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][d2]\" value=\"".$tv2['d2']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][label]\" value=\"".$tv2['label']."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}
	
	if (($nrow8a == 1 || $nrow8b == 1) && $tcomm < $c8amt) //Forced Override Entries (Always LAST!)
	{
		//echo 'HIT<BR>';
		if ($c8ctype==1)
		{
			$fc8ctype='fx';
		}
		elseif ($c8ctype==2)
		{
			$fc8ctype='%';
		}
		else
		{
			$fcctype='';
		}
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>sub-Total</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($tcomm < 0)
		{
			echo "					<font color=\"red\">".number_format($tcomm, 2, '.', '')."</font>";
		}
		else
		{
			echo number_format($tcomm, 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\" height=\"12px\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		
		$uc8amt=$c8amt + ($tcomm * -1);
		$tcomm=$tcomm + $uc8amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\" title=\"Adjustment required for Minimum Commission\"><b>Override</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc8ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		echo number_format($uc8amt, 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<div class=\"noPrint\"><img id=\"minimumoverride\" src=\"images/information.png\" width=\"11px\" height=\"11px\" title=\"Commission Minimum Override enabled\"></div>\n";
		
		jquery_notify_popup(
							'overridetext',
							'<b>Commission Automatic Override enabled!</b><br><br>
							Commission sub-Total is below the Minimum Commission of $'.number_format($c8amt, 2, '.', '').'.<br><br>
							Commission Total has been adjusted by $'.number_format($uc8amt, 2, '.', '').' to meet the minimum.'
							);
		
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][cmid]\" value=\"".$c8cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdamt]\" value=\"".number_format($uc8amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][secid]\" value=\"".$c8secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][catid]\" value=\"".$c8catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][ctype]\" value=\"".$c8ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdrate]\" value=\"".$c8rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][trgwght]\" value=\"".$c8thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d1]\" value=\"".$c8d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d2]\" value=\"".$c8d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][label]\" value=\"".$c8label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Total</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\">".number_format($tcomm, 2, '.', '')."</font>";
	}
	else
	{
		echo number_format($tcomm, 2, '.', '');
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	
	if ($_SESSION['elev'] >= 5)
	{
		echo "					<div class=\"noPrint\">\n";
		
		if ($cinar['jobid']=='0')
		{
			if ($_SESSION['elev'] >= 9)
			{
				echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract Override: Your security level allows you to override control protocols\">\n";
			}
			else
			{
				if (isset($errbiditems['no_ret']) and $errbiditems['no_ret'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Retail Price on one or more Bid Items\">\n";
					
					jquery_notify_popup(
								'overridetext',
								'<b>Create Contract Disabled!</b><br><br>
								Missing Bid Item Retail Price on one or more Bid Items'
								);
					
				}
				elseif (isset($errbiditems['no_cst']) and $errbiditems['no_cst'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Cost on one or more Bid Items\">\n";
					
					jquery_notify_popup(
								'overridetext',
								'<b>Create Contract Disabled!</b><br><br>
								Missing Bid Item Cost on one or more Bid Items'
								);
				}
				elseif (isset($errbiditems['th_cst']) and $errbiditems['th_cst'] > 0)
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Warning: Bid Item Cost too high on one or more Bid Items\">\n";
					jquery_notify_popup(
								'overridetext',
								'Bid Item Cost too high on one or more Bid Items'
								);
				}
				else
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract\">\n";
				}
			}
		}
		else
		{
			echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Contract exists for this Estimate\" DISABLED>\n";
		}
		
		echo "					</div>\n";
	}
	
	echo "				</td>\n";
	echo "           </tr>\n";
	echo "			</form>\n";
	
	return number_format($tcomm, 2, '.', '');
}

function CommissionScheduleRO_NEW_EDIT($cinar,$col_struct)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    $tsecid=26;
	$dbg=1;
	if (isset($dbg) && $dbg==0 && $_SESSION['securityid']==$tsecid)
	{
        echo __FUNCTION__.'<br>';
		echo "<pre>";
		print_r($cinar);
		//echo 'MOD:'.$_SESSION['modcomm'].'<br>';
		echo "</pre>";
	}
	
	$tcomm=0;
	$commcat_ar=array();
	$comar=array();
	$grpcomar=array();
	$tiercomar=array();
	
	//display_array($cinar);
	
	$errbiditems=bid_item_cost_test($_SESSION['officeid'],$cinar['estidret']);
	
	if (isset($cinar['tbullets']) and $cinar['tbullets'] > 0)
	{
		$bullets=$cinar['tbullets'];
	}
	else
	{
		$bullets=0;
	}
	
	//echo $bullets.'<br>';
	$qry0  = "select * from jest..CommissionBuilderCategory where access <= ".$_SESSION['clev']." order by descrip;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
	
	$qry0a  = "select officeid,gm,sm,am from jest..offices where officeid = ".$_SESSION['officeid'].";";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
    $nrow0a= mssql_num_rows($res0a);
	
	if ($nrow0 > 0)
	{
		$commcat_ar[$row0['catid']]=array('label'=>$row0['label'],'descrip'=>$row0['descrip']);
	}
	
	//Grab Category 1 SR Specific Comm
	if ($cinar['renov']==1) // Base Comms
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);
    $nrow1a= mssql_num_rows($res1a);
	
	if ($nrow1a==1)
	{
		$c1cmid		=$row1a['cmid'];
		$c1secid	=$row1a['secid'];
		$c1catid	=$row1a['ctgry'];
		$c1rate		=$row1a['rwdrate'];
		$c1ctype	=$row1a['ctype'];
		$c1amt		=$row1a['rwdamt'];
		$c1d1		=strtotime($row1a['d1']);
		$c1d2		=strtotime($row1a['d2']);
		$c1thresh	=$row1a['trgwght'];
		$c1label	=$row1a['name'];
        $c1trgcsrc  =$row1a['trgsrc'];
        $c1trgcsrcval=$row1a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=0;";
		}
		
		$res1b = mssql_query($qry1b);
		$row1b = mssql_fetch_array($res1b);
		$nrow1b= mssql_num_rows($res1b);
		
		$c1cmid		=$row1b['cmid'];
		$c1secid	=$row1b['secid'];
		$c1catid	=$row1b['ctgry'];
		$c1rate		=$row1b['rwdrate'];
		$c1ctype	=$row1b['ctype'];
		$c1amt		=$row1b['rwdamt'];
		$c1d1		=strtotime($row1b['d1']);
		$c1d2		=strtotime($row1b['d2']);
		$c1thresh	=$row1b['trgwght'];
		$c1label	=$row1b['name'];
        $c1trgcsrc  =$row1b['trgsrc'];
        $c1trgcsrcval=$row1b['trgsrcval'];
	}

	if ($cinar['renov']==1) //Grab Category 2 SR OU Specific Comm
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
	$nrow2a= mssql_num_rows($res2a);
	
	if ($nrow2a==1)
	{
		$c2cmid		=$row2a['cmid'];
		$c2secid	=$row2a['secid'];
		$c2catid	=$row2a['ctgry'];
		$c2rate		=$row2a['rwdrate'];
		$c2ctype	=$row2a['ctype'];
		$c2amt		=$row2a['rwdamt'];
		$c2d1		=strtotime($row2a['d1']);
		$c2d2		=strtotime($row2a['d2']);
		$c2thresh	=$row2a['trgwght'];
		$c2label	=$row2a['name'];
        $c2trgcsrc  =$row2a['trgsrc'];
        $c2trgcsrcval=$row2a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=0;";
		}

		$res2b = mssql_query($qry2b);
		$row2b = mssql_fetch_array($res2b);
		$nrow2b= mssql_num_rows($res2b);
		
		//echo $nrow2b.'<br>';
		
		$c2cmid		=$row2b['cmid'];
		$c2secid	=$row2b['secid'];
		$c2catid	=$row2b['ctgry'];
		$c2rate		=$row2b['rwdrate'];
		$c2ctype	=$row2b['ctype'];
		$c2amt		=$row2b['rwdamt'];
		$c2d1		=strtotime($row2b['d1']);
		$c2d2		=strtotime($row2b['d2']);
		$c2thresh	=$row2b['trgwght'];
		$c2label	=$row2b['name'];
        $c2trgsrc  =$row2b['trgsrc'];
        $c2trgcsrcval=$row2b['trgsrcval'];
	}

	/* MOVED TO CommissionScheduleRO_GMSM
	if (isset($cinar['sidm']))
	{
		//Grab Category 4 Comms (Sales Manager)
		$qry3a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=".$cinar['sidm'].";";
		$res3a = mssql_query($qry3a);
		$row3a = mssql_fetch_array($res3a);
		$nrow3a= mssql_num_rows($res3a);
		
		if ($nrow3a > 0)
		{
			$c3cmid		=$row3a['cmid'];
			$c3secid	=$row3a['secid'];
			$c3catid	=$row3a['ctgry'];
			$c3rate		=$row3a['rwdrate'];
			$c3ctype	=$row3a['ctype'];
			$c3amt		=$row3a['rwdamt'];
			$c3d1		=strtotime($row3a['d1']);
			$c3d2		=strtotime($row3a['d2']);
			$c3thresh	=$row3a['trgwght'];
			$c3label	=$row3a['name'];
		}
		else
		{
			$qry3b  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=0;";
			$res3b = mssql_query($qry3b);
			$row3b = mssql_fetch_array($res3b);
			$nrow3b= mssql_num_rows($res3b);
			
			$c3cmid		=$row3b['cmid'];
			$c3secid	=$row3b['secid'];
			$c3catid	=$row3b['ctgry'];
			$c3rate		=$row3b['rwdrate'];
			$c3ctype	=$row3b['ctype'];
			$c3amt		=$row3b['rwdamt'];
			$c3d1		=strtotime($row3b['d1']);
			$c3d2		=strtotime($row3b['d2']);
			$c3thresh	=$row3b['trgwght'];
			$c3label	=$row3b['name'];
		}
	}
	*/
	
	//Grab Category 6 Comms (Bullets/SmartFeatures)
	if ($bullets > 0 and $cinar['estsecid']!=1952)
	{
		$qry4a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=6 and active=1 order by trgwght desc,ctgry asc;";
		$res4a = mssql_query($qry4a);
		$nrow4a= mssql_num_rows($res4a);
		
		if ($nrow4a > 0)
		{
			while ($row4a = mssql_fetch_array($res4a))
			{
					$grpcomar[$row4a['linkid']][]=array(
							'cmid'=>	$row4a['cmid'],
							'secid'=>	$row4a['secid'],
							'catid'=>	$row4a['ctgry'],
							'ctype'=>	$row4a['ctype'],
							'rwdrate'=>	$row4a['rwdrate'],
							'trgwght'=>	$row4a['trgwght'],
							'd1'=>		strtotime($row4a['d1']),
							'd2'=>		strtotime(date('m/d/y',strtotime($row4a['d2'])). ' 23:59:59'),
							'active'=>	$row4a['active'],
							'label'=>	$row4a['name'],
							'rwdamt'=>	$row4a['rwdamt'],
							'linkid'=>	$row4a['linkid'],
							'trgsrc'=>	$row4a['trgsrc'],
							'trgsrcval'=>$row4a['trgsrcval']
						);
			}
		}
	}
	
	//Grab Category 9 Comms (Tiered Commissions)
	$qry9a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=9 and active=1 order by trgwght desc,ctgry asc;";
	$res9a = mssql_query($qry9a);
	$nrow9a= mssql_num_rows($res9a);
	
	if ($nrow9a > 0)
	{
		while ($row9a = mssql_fetch_array($res9a))
		{
				$tiercomar[$row9a['linkid']][]=array(
						'cmid'=>	$row9a['cmid'],
						'secid'=>	$row9a['secid'],
						'catid'=>	$row9a['ctgry'],
						'ctype'=>	$row9a['ctype'],
						'rwdrate'=>	$row9a['rwdrate'],
						'trgwght'=>	$row9a['trgwght'],
						'd1'=>		strtotime($row9a['d1']),
						'd2'=>		strtotime(date('m/d/y',strtotime($row9a['d2'])). ' 23:59:59'),
						'active'=>	$row9a['active'],
						'label'=>	$row9a['name'],
						'rwdamt'=>	$row9a['rwdamt'],
						'linkid'=>	$row9a['linkid'],
						'trgsrc'=>	$row9a['trgsrc'],
						'trgsrcval'=>$row9a['trgsrcval']
					);
		}
	}
	
	/* MOVED TO CommissionScheduleRO_GMSM
	//Grab Category 7 Comms (General Manager)
	$qry7a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=7 and active=1 and secid=".$row0a['gm'].";";
	$res7a = mssql_query($qry7a);
	$row7a = mssql_fetch_array($res7a);
	$nrow7a= mssql_num_rows($res7a);
	
	if ($nrow7a == 1)
	{
		$c7cmid		=$row7a['cmid'];
		$c7secid	=$row7a['secid'];
		$c7catid	=$row7a['ctgry'];
		$c7rate		=$row7a['rwdrate'];
		$c7ctype	=$row7a['ctype'];
		$c7amt		=$row7a['rwdamt'];
		$c7d1		=strtotime($row7a['d1']);
		$c7d2		=strtotime($row7a['d2']);
		$c7thresh	=$row7a['trgwght'];
		$c7label	=$row7a['name'];
	}
	*/
	
	if ($cinar['renov']==1) //Grab Category 8 SR Override Comm
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res8a = mssql_query($qry8a);
	$row8a = mssql_fetch_array($res8a);
	$nrow8a= mssql_num_rows($res8a);
	
	if ($nrow8a == 1)
	{
		$c8cmid		=$row8a['cmid'];
		$c8secid	=$row8a['secid'];
		$c8catid	=$row8a['ctgry'];
		$c8rate		=$row8a['rwdrate'];
		$c8ctype	=$row8a['ctype'];
		$c8amt		=$row8a['rwdamt'];
		$c8d1		=strtotime($row8a['d1']);
		$c8d2		=strtotime($row8a['d2']);
		$c8thresh	=$row8a['trgwght'];
		$c8label	=$row8a['name'];
		$c8trgsrc	=$row8a['trgsrc'];
		$c8trgsrcval=$row8a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=0;";
		}

		$res8b = mssql_query($qry8b);
		$row8b = mssql_fetch_array($res8b);
		$nrow8b= mssql_num_rows($res8b);
		
		$c8cmid		=$row8b['cmid'];
		$c8secid	=$row8b['secid'];
		$c8catid	=$row8b['ctgry'];
		$c8rate		=$row8b['rwdrate'];
		$c8ctype	=$row8b['ctype'];
		$c8amt		=$row8b['rwdamt'];
		$c8d1		=strtotime($row8b['d1']);
		$c8d2		=strtotime($row8b['d2']);
		$c8thresh	=$row8b['trgwght'];
		$c8label	=$row8b['name'];
		$c8trgsrc	=$row8b['trgsrc'];
		$c8trgsrcval=$row8b['trgsrcval'];
	}

	if (isset($cinar['contdate']))
	{
		$drange=$cinar['contdate'];
	}
	else
	{
		$drange=$cinar['sysdate'];
	}
	
	$dbg=0;
	if ($dbg==1 && $_SESSION['securityid']==SYS_ADMIN)
	{		
		echo "           <tr>\n";
		echo "              <td colspan=\"7\">\n";
		
		echo "<pre>";
		echo 'VARIABLES<br>';
		print_r($cinar);
		echo '<br><br>';
		echo 'STANDARD COMMISSIONS<br>';
		print_r($comar);
		echo '<br><br>';
		echo 'GROUP COMMISSIONS<br>';
		print_r($grpcomar);
		echo '<br><br>';
		echo 'TIMESTAMP<br>';
		echo time();
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "                        <form method=\"post\">\n";
	echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$cinar['estidret']."\">\n";
	echo "							 <input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "                           <input type=\"hidden\" name=\"call\" value=\"CreateContract\">\n";
	echo "                           <input type=\"hidden\" name=\"adjbook\" value=\"".number_format($cinar['fadjbookamt'], 2, '.', '')."\">\n";
	echo "                           <input type=\"hidden\" name=\"oubook\" value=\"".number_format(($cinar['fctramt'] - $cinar['fadjbookamt']), 2, '.', '')."\">\n";

	if ($cinar['taxtrig']==1)
	{
		echo "                           <input type=\"hidden\" name=\"salestax\" value=\"".$cinar['frtax']."\">\n";
	}
	
	//echo "			<table class=\"outer\" background=\"white\" bordercolor=\"gray\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."\"><b>Commissions</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[1]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[3]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	
	if ($nrow1a == 1 || $nrow1b == 1) // Base Entries
	{
		if ($c1ctype==1)
		{
			$fc1ctype='fx';
		}
		elseif ($c1ctype==2)
		{
			$fc1ctype='%';
		}
		else
		{
			$fc1ctype='';
		}
		
        if ($bullets >= 3)
		{
			$c1amt=$cinar['fadjbookamt'] * $c1rate;
		}
		else
		{
			$c1amt=$cinar['fctramt'] * $c1rate;
		}
		
		$tcomm=$tcomm + $c1amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Base</b></td>\n";	
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo "<span id=\"BCratedisplay\">".($c1rate * 100)."</span>";
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc1ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($c1amt < 0)
		{
			echo "              <font color=\"red\"><span id=\"BCamtdisplay\">".number_format($c1amt, 2, '.', '')."</span></font>\n";
		}
		else
		{
			echo "<span id=\"BCamtdisplay\">".number_format($c1amt, 2, '.', '')."</span>";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//if ($bullets >= 3)
		//{
			echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][contrsrc]\" value=\"book\">\n";
			//echo "<div class=\"noPrint\"><img id=\"basefrombook\" src=\"images/information.png\" width=\"12px\" height=\"12px\" title=\"Base Comm from Price per Book\"></div>\n";
            /*
		}
		else
		{
			echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][contrsrc]\" value=\"contract\">\n";
			//echo "<div class=\"noPrint\"><img id=\"basefromcontract\" src=\"images/information.png\" width=\"12px\" height=\"12px\" title=\"Base Comm from Contract Amt\"></div>\n";
			
		}
            */
		
		echo "				<span class=\"JMStooltip\" id=\"OpenBaseCommAdjustDialog\" title=\"Adjust Base Commission\"><a href=\"#\"><b>+/-</b></a></span>\n";
		echo "				<span id=\"OrigBCAmt\">".number_format($c1amt, 2, '.', '')."</span>";
		
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][cmid]\" value=\"".$c1cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdamt]\" value=\"".number_format($c1amt, 2, '.', '')."\" id=\"BCrwdamt\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][secid]\" value=\"".$c1secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][catid]\" value=\"".$c1catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][ctype]\" value=\"".$c1ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdrate]\" value=\"".$c1rate."\" id=\"BCrwdrate\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdrateorig]\" value=\"".$c1rate."\" id=\"BCrwdrateorig\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][trgwght]\" value=\"".$c1thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d1]\" value=\"".$c1d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d2]\" value=\"".$c1d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][label]\" value=\"".$c1label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"BCrwdamt\">\n";
	}
	
	
	if ($nrow2a == 1 || $nrow2b == 1) //OU Entries
	{
		if ($cinar['renov']==1)
		{
			if ($_SESSION['securityid']==269999999999999999999)
			{
				echo ($cinar['fctramt'] - $cinar['fadjbookamt']).'<br>';
			}
			
			if ($cinar['fctramt'] - $cinar['fadjbookamt'] > 0)
			{
				$c2amt=($cinar['fctramt'] - $cinar['fadjbookamt']) * $c2rate;
				$tcomm=$tcomm + $c2amt;
				
				if ($c2ctype==1)
				{
					$fc2ctype='fx';
				}
				elseif ($c2ctype==2)
				{
					$fc2ctype='%';
				}
				else
				{
					$fc2ctype='';
				}
				
				echo "           <tr>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><b>Over/<font color=\"red\">Under</font></b></td>\n";		
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".($c2rate * 100)."</font>";
				}
				else
				{
					echo $c2rate * 100;
				}
			
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".$fc2ctype."</font>";
				}
				else
				{
					echo $fc2ctype;
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\">\n";
			
				if ($c2amt < 0)
				{
					echo "              <font color=\"red\"><span id=\"OUamtdisplay\">".number_format($c2amt, 2, '.', '')."</span></font>\n";
				}
				else
				{
					echo "<span id=\"OUamtdisplay\">".number_format($c2amt, 2, '.', '')."</span>";
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][cmid]\" value=\"".$c2cmid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdamt]\" id=\"OUrwdamt\" value=\"".number_format($c2amt, 2, '.', '')."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][secid]\" value=\"".$c2secid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][catid]\" value=\"".$c2catid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][ctype]\" value=\"".$c2ctype."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdrate]\" id=\"OUrwdrate\" value=\"".$c2rate."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][trgwght]\" value=\"".$c2thresh."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d1]\" value=\"".$c2d1."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d2]\" value=\"".$c2d2."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][label]\" value=\"".$c2label."\">\n";
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			else
			{
				echo "<input type=\"hidden\" value=\"0\" id=\"OUrwdamt\">\n";
			}
		}
		else
		{
			//if ($bullets >= 3 or ($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
            if (($cinar['fctramt'] - $cinar['fadjbookamt']) != 0)
			{
				$c2amt=($cinar['fctramt'] - $cinar['fadjbookamt']) * $c2rate;
				$tcomm=$tcomm + $c2amt;
				
				if ($c2ctype==1)
				{
					$fc2ctype='fx';
				}
				elseif ($c2ctype==2)
				{
					$fc2ctype='%';
				}
				else
				{
					$fc2ctype='';
				}
				
				echo "           <tr>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><b>Over/<font color=\"red\">Under</font></b></td>\n";		
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".($c2rate * 100)."</font>";
				}
				else
				{
					echo $c2rate * 100;
				}
			
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".$fc2ctype."</font>";
				}
				else
				{
					echo $fc2ctype;
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\">\n";
			
				if ($c2amt < 0)
				{
					echo "              <font color=\"red\">".number_format($c2amt, 2, '.', '')."</font>\n";
				}
				else
				{
					echo number_format($c2amt, 2, '.', '');
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][cmid]\" value=\"".$c2cmid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdamt]\" id=\"OUrwdamt\" value=\"".number_format($c2amt, 2, '.', '')."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][secid]\" value=\"".$c2secid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][catid]\" value=\"".$c2catid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][ctype]\" value=\"".$c2ctype."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdrate]\" id=\"OUrwdrate\" value=\"".$c2rate."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][trgwght]\" value=\"".$c2thresh."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d1]\" value=\"".$c2d1."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d2]\" value=\"".$c2d2."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][label]\" value=\"".$c2label."\">\n";
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			else
			{
				echo "<input type=\"hidden\" value=\"0\" id=\"OUrwdamt\">\n";
			}
		}
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"OUrwdamt\">\n";
	}
	
	if ((isset($nrow4a) and $nrow4a > 0) and (isset($cinar['tbullets']) and $cinar['tbullets'] > 0) and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
	{
		foreach ($grpcomar as $gn1 => $gv1)
		{
			$tsval=0;
			foreach ($gv1 as $gn2 => $gv2)
			{
				if ($cinar['tbullets']==$gv2['trgwght'] && time() >= $gv2['d1'] && time() < $gv2['d2'])
				{
					if ($gv2['trgsrc']==6)
					{
						$tbamt=0;
						if ($gv2['ctype']==1) //Fixed
						{
							$rate	=0;
							$tbamt 	=$gv2['rwdamt'];
						}
						elseif ($gv2['ctype']==2)
						{
							if ($gv2['trgsrcval']==1) //Contract Amt
							{
								$tbamt=($cinar['fadjbookamt'] * $gv2['rwdrate']);
							}
							elseif ($gv2['trgsrcval']==2) //
							{
								$tbamt=(($cinar['fctramt']-$cinar['fadjbookamt']) * ($gv2['rwdrate'] * .01));
							}
						}
						
						$tcomm=$tcomm+$tbamt;
						echo "           <tr>\n";
						echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						
						if ($gv2['label']=='SRU')
						{
							echo "              <td class=\"wh\" align=\"right\"><b>".$cinar['tbullets']." SmartFeatures</b></td>\n";
						}
						else
						{
							echo "              <td class=\"wh\" align=\"right\"><b>".$cinar['tbullets']." ".$gv2['label']."</b></td>\n";
						}
						
						echo "              <td class=\"wh\" align=\"center\">".($gv2['rwdrate'] * 100)."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						
						if ($gv2['ctype']==2)
						{
							echo '%';
						}
						
						echo "				</td>\n";
						echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($tbamt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][cmid]\" value=\"".$gv2['cmid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdamt]\" id=\"BUrwdamt\" value=\"".number_format($tbamt, 2, '.', '')."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][secid]\" value=\"".$gv2['secid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][catid]\" value=\"".$gv2['catid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][ctype]\" value=\"".$gv2['ctype']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdrate]\" id=\"BUrwdrate\" value=\"".$gv2['rwdrate']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][trgwght]\" value=\"".$gv2['trgwght']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d1]\" value=\"".$gv2['d1']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d2]\" value=\"".$gv2['d2']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][label]\" value=\"".$gv2['label']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][tbullets]\" value=\"".$cinar['tbullets']."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"BUrwdamt\">\n";
	}
	
	if ($nrow9a > 0 and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
	{
		//echo 'Cat 9.0<br>';
		$tblock=false;
		foreach ($tiercomar as $tn1 => $tv1)
		{
			$tsvalt=0;
			foreach ($tv1 as $tn2 => $tv2)
			{
				if ($tv2['trgsrc'] == 7)
				{
					if (!$tblock and $cinar['fctramt'] >= $tv2['trgwght'] and (time() >= $tv2['d1'] and time() < $tv2['d2']))
					{
						$tbamtt=0;
						if ($tv2['ctype']==1) //Fixed
						{
							$tbamtt =$tv2['rwdamt'];
						}
						elseif ($tv2['ctype']==2) // Percent
						{
							if ($tv2['trgsrcval']==7) //Contract Amt
							{
								$tbamtt=($cinar['fctramt'] * $tv2['rwdrate']);
								$tblock=true;
							}
							else
							{
								$tbamtt=0;
							}
						}
						
						$tcomm=$tcomm+$tbamtt;
						
						echo "           <tr>\n";
						echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\"><b>Merit Bonus</b></td>\n";
						echo "              <td class=\"wh\" align=\"center\">".($tv2['rwdrate'] * 100)."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						
						if ($tv2['ctype']==2)
						{
							echo '%';
						}
						
						echo "				</td>\n";
						echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($tbamtt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][cmid]\" value=\"".$tv2['cmid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][rwdamt]\" id=\"MBrwdamt\" value=\"".number_format($tbamtt, 2, '.', '')."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][secid]\" value=\"".$tv2['secid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][catid]\" value=\"".$tv2['catid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][ctype]\" value=\"".$tv2['ctype']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][rwdrate]\" id=\"MBrwdrate\" value=\"".$tv2['rwdrate']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][trgwght]\" value=\"".$tv2['trgwght']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][d1]\" value=\"".$tv2['d1']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][d2]\" value=\"".$tv2['d2']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][label]\" value=\"".$tv2['label']."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"MBrwdamt\">\n";
	}
	
	if (($nrow8a == 1 || $nrow8b == 1) && $tcomm < $c8amt) //Forced Override Entries (Always LAST!)
	{
		if ($c8ctype==1)
		{
			$fc8ctype='fx';
		}
		elseif ($c8ctype==2)
		{
			$fc8ctype='%';
		}
		else
		{
			$fcctype='';
		}
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>sub-Total</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($tcomm < 0)
		{
			echo "					<font color=\"red\">".number_format($tcomm, 2, '.', '')."</font>";
		}
		else
		{
			echo number_format($tcomm, 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\" height=\"12px\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		
		$uc8amt=$c8amt + ($tcomm * -1);
		$tcomm=$tcomm + $uc8amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\" title=\"Adjustment required for Minimum Commission\"><b>Override</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc8ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		echo number_format($uc8amt, 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<div class=\"noPrint\"><img id=\"minimumoverride\" src=\"images/information.png\" width=\"11px\" height=\"11px\" title=\"Commission Minimum Override enabled\"></div>\n";
		
		jquery_notify_popup(
							'overridetext',
							'<b>Commission Automatic Override enabled!</b><br><br>
							Commission sub-Total is below the Minimum Commission of $'.number_format($c8amt, 2, '.', '').'.<br><br>
							Commission Total has been adjusted by $'.number_format($uc8amt, 2, '.', '').' to meet the minimum.'
							);
		
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][cmid]\" value=\"".$c8cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdamt]\" id=\"OVrwdamt\" value=\"".number_format($uc8amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][secid]\" value=\"".$c8secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][catid]\" value=\"".$c8catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][ctype]\" value=\"".$c8ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdrate]\" id=\"OVrwdrate\" value=\"".$c8rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][trgwght]\" value=\"".$c8thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d1]\" value=\"".$c8d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d2]\" value=\"".$c8d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][label]\" value=\"".$c8label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"OVrwdamt\">\n";
	}
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Total</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\"><span id=\"TotalAmtDisplay\">".number_format($tcomm, 2, '.', '')."</span></font>";
	}
	else
	{
		echo "<span id=\"TotalAmtDisplay\">".number_format($tcomm, 2, '.', '')."</span>";
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "				<span id=\"OrigTotalAmt\">".number_format($tcomm, 2, '.', '')."</span>";
	
	if ($_SESSION['clev'] >= 5)
	{
		echo "					<div class=\"noPrint\">\n";
		
		if ($cinar['jobid']=='0')
		{
			if ($_SESSION['clev'] >= 9)
			{
				echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract Override: Your security level allows you to override control protocols\">\n";
			}
			else
			{
				if (isset($errbiditems['no_ret']) and $errbiditems['no_ret'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Retail Price on one or more Bid Items\">\n";
					
					jquery_notify_popup(
								'overridetext',
								'<b>Create Contract Disabled!</b><br><br>
								Missing Bid Item Retail Price on one or more Bid Items'
								);
					
				}
				elseif (isset($errbiditems['no_cst']) and $errbiditems['no_cst'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Cost on one or more Bid Items\">\n";
					
					jquery_notify_popup(
								'overridetext',
								'<b>Create Contract Disabled!</b><br><br>
								Missing Bid Item Cost on one or more Bid Items'
								);
				}
				elseif (isset($errbiditems['th_cst']) and $errbiditems['th_cst'] > 0)
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Warning: Bid Item Cost too high on one or more Bid Items\">\n";
					jquery_notify_popup(
								'overridetext',
								'Bid Item Cost too high on one or more Bid Items'
								);
				}
				else
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract\">\n";
				}
			}
		}
		else
		{
			echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Contract exists for this Estimate\" DISABLED>\n";
		}
		
		echo "					</div>\n";
	}
	
	echo "				</td>\n";
	echo "           </tr>\n";
	echo "			</form>\n";
	
	return number_format($tcomm, 2, '.', '');
}

function CommissionScheduleRO_After_Contract_Est($cinar,$col_struct)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    $tsecid=26;
	$dbg=0;
	if (isset($dbg) && $dbg==1 && $_SESSION['securityid']==26)
	{
        echo __FUNCTION__.'<br>';
		echo "<pre>";
		print_r($cinar);
		//echo 'MOD:'.$_SESSION['modcomm'].'<br>';
		echo "</pre>";
	}
	
	$tcomm=0;
	$commcat_ar=array();
	$comar=array();
	$grpcomar=array();
	$tiercomar=array();
	
	//display_array($cinar);
	
	$errbiditems=bid_item_cost_test($_SESSION['officeid'],$cinar['estidret']);
	
	if (isset($cinar['tbullets']) and $cinar['tbullets'] > 0)
	{
		$bullets=$cinar['tbullets'];
	}
	else
	{
		$bullets=0;
	}
	
	//echo $bullets.'<br>';
	$qry0  = "select * from jest..CommissionBuilderCategory where access <= ".$_SESSION['clev']." order by descrip;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
	
	$qry0a  = "select officeid,gm,sm,am from jest..offices where officeid = ".$_SESSION['officeid'].";";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
    $nrow0a= mssql_num_rows($res0a);
	
	if ($nrow0 > 0)
	{
		$commcat_ar[$row0['catid']]=array('label'=>$row0['label'],'descrip'=>$row0['descrip']);
	}
	
	//Grab Category 1 SR Specific Comm
	if ($cinar['renov']==1) // Base Comms
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);
    $nrow1a= mssql_num_rows($res1a);
	
	if ($nrow1a==1)
	{
		$c1cmid		=$row1a['cmid'];
		$c1secid	=$row1a['secid'];
		$c1catid	=$row1a['ctgry'];
		$c1rate		=$row1a['rwdrate'];
		$c1ctype	=$row1a['ctype'];
		$c1amt		=$row1a['rwdamt'];
		$c1d1		=strtotime($row1a['d1']);
		$c1d2		=strtotime($row1a['d2']);
		$c1thresh	=$row1a['trgwght'];
		$c1label	=$row1a['name'];
        $c1trgcsrc  =$row1a['trgsrc'];
        $c1trgcsrcval=$row1a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=0;";
		}
		
		$res1b = mssql_query($qry1b);
		$row1b = mssql_fetch_array($res1b);
		$nrow1b= mssql_num_rows($res1b);
		
		$c1cmid		=$row1b['cmid'];
		$c1secid	=$row1b['secid'];
		$c1catid	=$row1b['ctgry'];
		$c1rate		=$row1b['rwdrate'];
		$c1ctype	=$row1b['ctype'];
		$c1amt		=$row1b['rwdamt'];
		$c1d1		=strtotime($row1b['d1']);
		$c1d2		=strtotime($row1b['d2']);
		$c1thresh	=$row1b['trgwght'];
		$c1label	=$row1b['name'];
        $c1trgcsrc  =$row1b['trgsrc'];
        $c1trgcsrcval=$row1b['trgsrcval'];
	}

	if ($cinar['renov']==1) //Grab Category 2 SR OU Specific Comm
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
	$nrow2a= mssql_num_rows($res2a);
	
	if ($nrow2a==1)
	{
		$c2cmid		=$row2a['cmid'];
		$c2secid	=$row2a['secid'];
		$c2catid	=$row2a['ctgry'];
		$c2rate		=$row2a['rwdrate'];
		$c2ctype	=$row2a['ctype'];
		$c2amt		=$row2a['rwdamt'];
		$c2d1		=strtotime($row2a['d1']);
		$c2d2		=strtotime($row2a['d2']);
		$c2thresh	=$row2a['trgwght'];
		$c2label	=$row2a['name'];
        $c2trgcsrc  =$row2a['trgsrc'];
        $c2trgcsrcval=$row2a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=0;";
		}

		$res2b = mssql_query($qry2b);
		$row2b = mssql_fetch_array($res2b);
		$nrow2b= mssql_num_rows($res2b);
		
		//echo $nrow2b.'<br>';
		
		$c2cmid		=$row2b['cmid'];
		$c2secid	=$row2b['secid'];
		$c2catid	=$row2b['ctgry'];
		$c2rate		=$row2b['rwdrate'];
		$c2ctype	=$row2b['ctype'];
		$c2amt		=$row2b['rwdamt'];
		$c2d1		=strtotime($row2b['d1']);
		$c2d2		=strtotime($row2b['d2']);
		$c2thresh	=$row2b['trgwght'];
		$c2label	=$row2b['name'];
        $c2trgsrc  =$row2b['trgsrc'];
        $c2trgcsrcval=$row2b['trgsrcval'];
	}

	/* MOVED TO CommissionScheduleRO_GMSM
	if (isset($cinar['sidm']))
	{
		//Grab Category 4 Comms (Sales Manager)
		$qry3a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=".$cinar['sidm'].";";
		$res3a = mssql_query($qry3a);
		$row3a = mssql_fetch_array($res3a);
		$nrow3a= mssql_num_rows($res3a);
		
		if ($nrow3a > 0)
		{
			$c3cmid		=$row3a['cmid'];
			$c3secid	=$row3a['secid'];
			$c3catid	=$row3a['ctgry'];
			$c3rate		=$row3a['rwdrate'];
			$c3ctype	=$row3a['ctype'];
			$c3amt		=$row3a['rwdamt'];
			$c3d1		=strtotime($row3a['d1']);
			$c3d2		=strtotime($row3a['d2']);
			$c3thresh	=$row3a['trgwght'];
			$c3label	=$row3a['name'];
		}
		else
		{
			$qry3b  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=0;";
			$res3b = mssql_query($qry3b);
			$row3b = mssql_fetch_array($res3b);
			$nrow3b= mssql_num_rows($res3b);
			
			$c3cmid		=$row3b['cmid'];
			$c3secid	=$row3b['secid'];
			$c3catid	=$row3b['ctgry'];
			$c3rate		=$row3b['rwdrate'];
			$c3ctype	=$row3b['ctype'];
			$c3amt		=$row3b['rwdamt'];
			$c3d1		=strtotime($row3b['d1']);
			$c3d2		=strtotime($row3b['d2']);
			$c3thresh	=$row3b['trgwght'];
			$c3label	=$row3b['name'];
		}
	}
	*/
	
	//Grab Category 6 Comms (Bullets/SmartFeatures)
	if ($bullets > 0 and $cinar['estsecid']!=1952)
	{
		$qry4a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=6 and active=1 order by trgwght desc,ctgry asc;";
		$res4a = mssql_query($qry4a);
		$nrow4a= mssql_num_rows($res4a);
		
		if ($nrow4a > 0)
		{
			while ($row4a = mssql_fetch_array($res4a))
			{
					$grpcomar[$row4a['linkid']][]=array(
							'cmid'=>	$row4a['cmid'],
							'secid'=>	$row4a['secid'],
							'catid'=>	$row4a['ctgry'],
							'ctype'=>	$row4a['ctype'],
							'rwdrate'=>	$row4a['rwdrate'],
							'trgwght'=>	$row4a['trgwght'],
							'd1'=>		strtotime($row4a['d1']),
							'd2'=>		strtotime(date('m/d/y',strtotime($row4a['d2'])). ' 23:59:59'),
							'active'=>	$row4a['active'],
							'label'=>	$row4a['name'],
							'rwdamt'=>	$row4a['rwdamt'],
							'linkid'=>	$row4a['linkid'],
							'trgsrc'=>	$row4a['trgsrc'],
							'trgsrcval'=>$row4a['trgsrcval']
						);
			}
		}
	}
	
	//Grab Category 9 Comms (Tiered Commissions)
	$qry9a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=9 and active=1 order by trgwght desc,ctgry asc;";
	$res9a = mssql_query($qry9a);
	$nrow9a= mssql_num_rows($res9a);
	
	if ($nrow9a > 0)
	{
		while ($row9a = mssql_fetch_array($res9a))
		{
				$tiercomar[$row9a['linkid']][]=array(
						'cmid'=>	$row9a['cmid'],
						'secid'=>	$row9a['secid'],
						'catid'=>	$row9a['ctgry'],
						'ctype'=>	$row9a['ctype'],
						'rwdrate'=>	$row9a['rwdrate'],
						'trgwght'=>	$row9a['trgwght'],
						'd1'=>		strtotime($row9a['d1']),
						'd2'=>		strtotime(date('m/d/y',strtotime($row9a['d2'])). ' 23:59:59'),
						'active'=>	$row9a['active'],
						'label'=>	$row9a['name'],
						'rwdamt'=>	$row9a['rwdamt'],
						'linkid'=>	$row9a['linkid'],
						'trgsrc'=>	$row9a['trgsrc'],
						'trgsrcval'=>$row9a['trgsrcval']
					);
		}
	}
	
	/* MOVED TO CommissionScheduleRO_GMSM
	//Grab Category 7 Comms (General Manager)
	$qry7a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=7 and active=1 and secid=".$row0a['gm'].";";
	$res7a = mssql_query($qry7a);
	$row7a = mssql_fetch_array($res7a);
	$nrow7a= mssql_num_rows($res7a);
	
	if ($nrow7a == 1)
	{
		$c7cmid		=$row7a['cmid'];
		$c7secid	=$row7a['secid'];
		$c7catid	=$row7a['ctgry'];
		$c7rate		=$row7a['rwdrate'];
		$c7ctype	=$row7a['ctype'];
		$c7amt		=$row7a['rwdamt'];
		$c7d1		=strtotime($row7a['d1']);
		$c7d2		=strtotime($row7a['d2']);
		$c7thresh	=$row7a['trgwght'];
		$c7label	=$row7a['name'];
	}
	*/
	
	if ($cinar['renov']==1) //Grab Category 8 SR Override Comm
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res8a = mssql_query($qry8a);
	$row8a = mssql_fetch_array($res8a);
	$nrow8a= mssql_num_rows($res8a);
	
	if ($nrow8a == 1)
	{
		$c8cmid		=$row8a['cmid'];
		$c8secid	=$row8a['secid'];
		$c8catid	=$row8a['ctgry'];
		$c8rate		=$row8a['rwdrate'];
		$c8ctype	=$row8a['ctype'];
		$c8amt		=$row8a['rwdamt'];
		$c8d1		=strtotime($row8a['d1']);
		$c8d2		=strtotime($row8a['d2']);
		$c8thresh	=$row8a['trgwght'];
		$c8label	=$row8a['name'];
		$c8trgsrc	=$row8a['trgsrc'];
		$c8trgsrcval=$row8a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=0;";
		}

		$res8b = mssql_query($qry8b);
		$row8b = mssql_fetch_array($res8b);
		$nrow8b= mssql_num_rows($res8b);
		
		$c8cmid		=$row8b['cmid'];
		$c8secid	=$row8b['secid'];
		$c8catid	=$row8b['ctgry'];
		$c8rate		=$row8b['rwdrate'];
		$c8ctype	=$row8b['ctype'];
		$c8amt		=$row8b['rwdamt'];
		$c8d1		=strtotime($row8b['d1']);
		$c8d2		=strtotime($row8b['d2']);
		$c8thresh	=$row8b['trgwght'];
		$c8label	=$row8b['name'];
		$c8trgsrc	=$row8b['trgsrc'];
		$c8trgsrcval=$row8b['trgsrcval'];
	}

	if (isset($cinar['contdate']))
	{
		$drange=$cinar['contdate'];
	}
	else
	{
		$drange=$cinar['sysdate'];
	}
	
	$dbg=2;
	if ($dbg==1 && $_SESSION['securityid']==26)
	{		
		echo "           <tr>\n";
		echo "              <td colspan=\"7\">\n";
		
		echo "<pre>";
		echo 'VARIABLES<br>';
		print_r($cinar);
		echo '<br><br>';
		echo 'STANDARD COMMISSIONS<br>';
		print_r($comar);
		echo '<br><br>';
		echo 'GROUP COMMISSIONS<br>';
		print_r($grpcomar);
		echo '<br><br>';
		echo 'TIMESTAMP<br>';
		echo time();
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "                        <form method=\"post\">\n";
	echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$cinar['estidret']."\">\n";
	echo "							 <input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "                           <input type=\"hidden\" name=\"call\" value=\"CreateContract\">\n";
	echo "                           <input type=\"hidden\" name=\"adjbook\" value=\"".number_format($cinar['fadjbookamt'], 2, '.', '')."\">\n";
	echo "                           <input type=\"hidden\" name=\"oubook\" value=\"".number_format(($cinar['fctramt'] - $cinar['fadjbookamt']), 2, '.', '')."\">\n";

	if ($cinar['taxtrig']==1)
	{
		echo "                           <input type=\"hidden\" name=\"salestax\" value=\"".$cinar['frtax']."\">\n";
	}
	
	//echo "			<table class=\"outer\" background=\"white\" bordercolor=\"gray\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."\"><b>Commissions</b></td>\n";
	echo "              <td class=\"wh\" align=\"left\" width=\"".$col_struct[1]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[3]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."\"></td>\n";
	echo "              <td class=\"wh\" align=\"left\" colspan=\"6\" width=\"".$col_struct[1]."\"><font color=\"red\"><b>*NOTE*</b></font>: <span>A Contract has been created from this Estimate.<br>Pricing and Commissions in this estimate reflect the current Pricebook and may not match the Contract.</span></td>\n";
	echo "           </tr>\n";
	if ($nrow1a == 1 || $nrow1b == 1) // Base Entries
	{
		if ($c1ctype==1)
		{
			$fc1ctype='fx';
		}
		elseif ($c1ctype==2)
		{
			$fc1ctype='%';
		}
		else
		{
			$fc1ctype='';
		}
		
        if ($c1trgcsrcval == 1)
		{
			$c1amt=$cinar['fctramt'] * $c1rate;
		}
		elseif ($c1trgcsrcval == 3) // Adjusted Price per Book
		{
			$c1amt=$cinar['fadjbookamt'] * $c1rate;
		}
		else
		{
			$c1amt=$cinar['fctramt'] * $c1rate;
		}
		
		$tcomm=$tcomm + $c1amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Base</b></td>\n";	
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo "<span id=\"BCratedisplay\">".($c1rate * 100)."</span>";
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc1ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($c1amt < 0)
		{
			echo "              <font color=\"red\"><span id=\"BCamtdisplay\">".number_format($c1amt, 2, '.', '')."</span></font>\n";
		}
		else
		{
			echo "<span id=\"BCamtdisplay\">".number_format($c1amt, 2, '.', '')."</span>";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//if ($bullets >= 3)
		//{
			echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][contrsrc]\" value=\"book\">\n";
			//echo "<div class=\"noPrint\"><img id=\"basefrombook\" src=\"images/information.png\" width=\"12px\" height=\"12px\" title=\"Base Comm from Price per Book\"></div>\n";
        /*
		}
		else
		{
			echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][contrsrc]\" value=\"contract\">\n";
			//echo "<div class=\"noPrint\"><img id=\"basefromcontract\" src=\"images/information.png\" width=\"12px\" height=\"12px\" title=\"Base Comm from Contract Amt\"></div>\n";
			
		}
        */
		
		//echo "				<span class=\"JMStooltip\" id=\"OpenBaseCommAdjustDialog\" title=\"Adjust Base Commission\"><a href=\"#\"><b>+/-</b></a></span>\n";
		echo "				<span id=\"OrigBCAmt\">".number_format($c1amt, 2, '.', '')."</span>";
		
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][cmid]\" value=\"".$c1cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdamt]\" value=\"".number_format($c1amt, 2, '.', '')."\" id=\"BCrwdamt\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][secid]\" value=\"".$c1secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][catid]\" value=\"".$c1catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][ctype]\" value=\"".$c1ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdrate]\" value=\"".$c1rate."\" id=\"BCrwdrate\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdrateorig]\" value=\"".$c1rate."\" id=\"BCrwdrateorig\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][trgwght]\" value=\"".$c1thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d1]\" value=\"".$c1d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d2]\" value=\"".$c1d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][label]\" value=\"".$c1label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"BCrwdamt\">\n";
	}
	
	if ($nrow2a == 1 || $nrow2b == 1) //OU Entries
	{
		if ($cinar['renov']==1)
		{
			if ($_SESSION['securityid']==269999999999999999999)
			{
				echo ($cinar['fctramt'] - $cinar['fadjbookamt']).'<br>';
			}
			
			if ($cinar['fctramt'] - $cinar['fadjbookamt'] > 0)
			{
				$c2amt=($cinar['fctramt'] - $cinar['fadjbookamt']) * $c2rate;
				$tcomm=$tcomm + $c2amt;
				
				if ($c2ctype==1)
				{
					$fc2ctype='fx';
				}
				elseif ($c2ctype==2)
				{
					$fc2ctype='%';
				}
				else
				{
					$fc2ctype='';
				}
				
				echo "           <tr>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><b>Over/<font color=\"red\">Under</font></b></td>\n";		
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".($c2rate * 100)."</font>";
				}
				else
				{
					echo $c2rate * 100;
				}
			
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".$fc2ctype."</font>";
				}
				else
				{
					echo $fc2ctype;
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\">\n";
			
				if ($c2amt < 0)
				{
					echo "              <font color=\"red\"><span id=\"OUamtdisplay\">".number_format($c2amt, 2, '.', '')."</span></font>\n";
				}
				else
				{
					echo "<span id=\"OUamtdisplay\">".number_format($c2amt, 2, '.', '')."</span>";
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][cmid]\" value=\"".$c2cmid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdamt]\" id=\"OUrwdamt\" value=\"".number_format($c2amt, 2, '.', '')."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][secid]\" value=\"".$c2secid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][catid]\" value=\"".$c2catid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][ctype]\" value=\"".$c2ctype."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdrate]\" id=\"OUrwdrate\" value=\"".$c2rate."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][trgwght]\" value=\"".$c2thresh."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d1]\" value=\"".$c2d1."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d2]\" value=\"".$c2d2."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][label]\" value=\"".$c2label."\">\n";
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			else
			{
				echo "<input type=\"hidden\" value=\"0\" id=\"OUrwdamt\">\n";
			}
		}
		else
		{
			//if ($bullets >= 3 or ($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
            if (($cinar['fctramt'] - $cinar['fadjbookamt']) != 0)
			{
				$c2amt=($cinar['fctramt'] - $cinar['fadjbookamt']) * $c2rate;
				$tcomm=$tcomm + $c2amt;
				
				if ($c2ctype==1)
				{
					$fc2ctype='fx';
				}
				elseif ($c2ctype==2)
				{
					$fc2ctype='%';
				}
				else
				{
					$fc2ctype='';
				}
				
				echo "           <tr>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><b>Over/<font color=\"red\">Under</font></b></td>\n";		
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".($c2rate * 100)."</font>";
				}
				else
				{
					echo $c2rate * 100;
				}
			
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".$fc2ctype."</font>";
				}
				else
				{
					echo $fc2ctype;
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\">\n";
			
				if ($c2amt < 0)
				{
					echo "              <font color=\"red\">".number_format($c2amt, 2, '.', '')."</font>\n";
				}
				else
				{
					echo number_format($c2amt, 2, '.', '');
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][cmid]\" value=\"".$c2cmid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdamt]\" id=\"OUrwdamt\" value=\"".number_format($c2amt, 2, '.', '')."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][secid]\" value=\"".$c2secid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][catid]\" value=\"".$c2catid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][ctype]\" value=\"".$c2ctype."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdrate]\" id=\"OUrwdrate\" value=\"".$c2rate."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][trgwght]\" value=\"".$c2thresh."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d1]\" value=\"".$c2d1."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d2]\" value=\"".$c2d2."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][label]\" value=\"".$c2label."\">\n";
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			else
			{
				echo "<input type=\"hidden\" value=\"0\" id=\"OUrwdamt\">\n";
			}
		}
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"OUrwdamt\">\n";
	}
	
	if ((isset($nrow4a) and $nrow4a > 0) and (isset($cinar['tbullets']) and $cinar['tbullets'] > 0) and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
	{
		foreach ($grpcomar as $gn1 => $gv1)
		{
			$tsval=0;
			foreach ($gv1 as $gn2 => $gv2)
			{
				if ($cinar['tbullets']==$gv2['trgwght'] && time() >= $gv2['d1'] && time() < $gv2['d2'])
				{
					if ($gv2['trgsrc']==6)
					{
						$tbamt=0;
						if ($gv2['ctype']==1) //Fixed
						{
							$rate	=0;
							$tbamt 	=$gv2['rwdamt'];
						}
						elseif ($gv2['ctype']==2)
						{
							if ($gv2['trgsrcval']==1) //Contract Amt
							{
								$tbamt=($cinar['fadjbookamt'] * $gv2['rwdrate']);
							}
							elseif ($gv2['trgsrcval']==2) //
							{
								$tbamt=(($cinar['fctramt']-$cinar['fadjbookamt']) * ($gv2['rwdrate'] * .01));
							}
						}
						
						$tcomm=$tcomm+$tbamt;
						echo "           <tr>\n";
						echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						
						if ($gv2['label']=='SRU')
						{
							echo "              <td class=\"wh\" align=\"right\"><b>".$cinar['tbullets']." SmartFeatures</b></td>\n";
						}
						else
						{
							echo "              <td class=\"wh\" align=\"right\"><b>".$cinar['tbullets']." ".$gv2['label']."</b></td>\n";
						}
						
						echo "              <td class=\"wh\" align=\"center\">".($gv2['rwdrate'] * 100)."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						
						if ($gv2['ctype']==2)
						{
							echo '%';
						}
						
						echo "				</td>\n";
						echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($tbamt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][cmid]\" value=\"".$gv2['cmid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdamt]\" id=\"BUrwdamt\" value=\"".number_format($tbamt, 2, '.', '')."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][secid]\" value=\"".$gv2['secid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][catid]\" value=\"".$gv2['catid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][ctype]\" value=\"".$gv2['ctype']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdrate]\" id=\"BUrwdrate\" value=\"".$gv2['rwdrate']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][trgwght]\" value=\"".$gv2['trgwght']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d1]\" value=\"".$gv2['d1']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d2]\" value=\"".$gv2['d2']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][label]\" value=\"".$gv2['label']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][tbullets]\" value=\"".$cinar['tbullets']."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"BUrwdamt\">\n";
	}
	
	if ($nrow9a > 0 and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
	{
		//echo 'Cat 9.0<br>';
		$tblock=false;
		foreach ($tiercomar as $tn1 => $tv1)
		{
			$tsvalt=0;
			foreach ($tv1 as $tn2 => $tv2)
			{
				if ($tv2['trgsrc'] == 7)
				{
					if (!$tblock and $cinar['fctramt'] >= $tv2['trgwght'] and (time() >= $tv2['d1'] and time() < $tv2['d2']))
					{
						$tbamtt=0;
						if ($tv2['ctype']==1) //Fixed
						{
							$tbamtt =$tv2['rwdamt'];
						}
						elseif ($tv2['ctype']==2) // Percent
						{
							if ($tv2['trgsrcval']==7) //Contract Amt
							{
								$tbamtt=($cinar['fctramt'] * $tv2['rwdrate']);
								$tblock=true;
							}
							else
							{
								$tbamtt=0;
							}
						}
						
						$tcomm=$tcomm+$tbamtt;
						
						echo "           <tr>\n";
						echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\"><b>Merit Bonus</b></td>\n";
						echo "              <td class=\"wh\" align=\"center\">".($tv2['rwdrate'] * 100)."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						
						if ($tv2['ctype']==2)
						{
							echo '%';
						}
						
						echo "				</td>\n";
						echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($tbamtt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][cmid]\" value=\"".$tv2['cmid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][rwdamt]\" id=\"MBrwdamt\" value=\"".number_format($tbamtt, 2, '.', '')."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][secid]\" value=\"".$tv2['secid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][catid]\" value=\"".$tv2['catid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][ctype]\" value=\"".$tv2['ctype']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][rwdrate]\" id=\"MBrwdrate\" value=\"".$tv2['rwdrate']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][trgwght]\" value=\"".$tv2['trgwght']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][d1]\" value=\"".$tv2['d1']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][d2]\" value=\"".$tv2['d2']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][label]\" value=\"".$tv2['label']."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"MBrwdamt\">\n";
	}
	
	if (($nrow8a == 1 || $nrow8b == 1) && $tcomm < $c8amt) //Forced Override Entries (Always LAST!)
	{
		if ($c8ctype==1)
		{
			$fc8ctype='fx';
		}
		elseif ($c8ctype==2)
		{
			$fc8ctype='%';
		}
		else
		{
			$fcctype='';
		}
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>sub-Total</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($tcomm < 0)
		{
			echo "					<font color=\"red\">".number_format($tcomm, 2, '.', '')."</font>";
		}
		else
		{
			echo number_format($tcomm, 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\" height=\"12px\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		
		$uc8amt=$c8amt + ($tcomm * -1);
		$tcomm=$tcomm + $uc8amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\" title=\"Adjustment required for Minimum Commission\"><b>Override</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc8ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		echo number_format($uc8amt, 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<div class=\"noPrint\"><img id=\"minimumoverride\" src=\"images/information.png\" width=\"11px\" height=\"11px\" title=\"Commission Minimum Override enabled\"></div>\n";
		
		jquery_notify_popup(
							'overridetext',
							'<b>Commission Automatic Override enabled!</b><br><br>
							Commission sub-Total is below the Minimum Commission of $'.number_format($c8amt, 2, '.', '').'.<br><br>
							Commission Total has been adjusted by $'.number_format($uc8amt, 2, '.', '').' to meet the minimum.'
							);
		
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][cmid]\" value=\"".$c8cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdamt]\" id=\"OVrwdamt\" value=\"".number_format($uc8amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][secid]\" value=\"".$c8secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][catid]\" value=\"".$c8catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][ctype]\" value=\"".$c8ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdrate]\" id=\"OVrwdrate\" value=\"".$c8rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][trgwght]\" value=\"".$c8thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d1]\" value=\"".$c8d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d2]\" value=\"".$c8d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][label]\" value=\"".$c8label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"OVrwdamt\">\n";
	}
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Total</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\"><span id=\"TotalAmtDisplay\">".number_format($tcomm, 2, '.', '')."</span></font>";
	}
	else
	{
		echo "<span id=\"TotalAmtDisplay\">".number_format($tcomm, 2, '.', '')."</span>";
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "              <form method=\"POST\">\n";
    echo "              	<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
    echo "              	<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
    echo "              	<input type=\"hidden\" name=\"jobid\" value=\"".$cinar['jobid']."\">\n";
    echo "              	<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
    echo "              	<input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_link.png\" title=\"Contract Created, Click to View Contract\">\n";
    echo "              </form>\n";
	
	echo "				</td>\n";
	echo "           </tr>\n";
	echo "			</form>\n";
	
	return number_format($tcomm, 2, '.', '');
}

function CommissionScheduleRO_NEW_EDIT_TED($cinar,$col_struct)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    $tsecid=26;
	$dbg=0;
	if (isset($dbg) && $dbg==1 && $_SESSION['securityid']==26)
	{
        echo __FUNCTION__.'<br>';
		echo "<pre>";
		print_r($cinar);
		//echo 'MOD:'.$_SESSION['modcomm'].'<br>';
		echo "</pre>";
	}
	
	$tcomm=0;
	$commcat_ar=array();
	$comar=array();
	$grpcomar=array();
	$tiercomar=array();
	
	//display_array($cinar);
	
	$errbiditems=bid_item_cost_test($_SESSION['officeid'],$cinar['estidret']);
	
	if (isset($cinar['tbullets']) and $cinar['tbullets'] > 0)
	{
		$bullets=$cinar['tbullets'];
	}
	else
	{
		$bullets=0;
	}
	
	//echo $bullets.'<br>';
	$qry0  = "select * from jest..CommissionBuilderCategory where access <= ".$_SESSION['clev']." order by descrip;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
	
	$qry0a  = "select officeid,gm,sm,am from jest..offices where officeid = ".$_SESSION['officeid'].";";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
    $nrow0a= mssql_num_rows($res0a);
	
	if ($nrow0 > 0)
	{
		$commcat_ar[$row0['catid']]=array('label'=>$row0['label'],'descrip'=>$row0['descrip']);
	}
	
	//Grab Category 1 SR Specific Comm
	if ($cinar['renov']==1) // Base Comms
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry1a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);
    $nrow1a= mssql_num_rows($res1a);
	
	if ($nrow1a==1)
	{
		$c1cmid		=$row1a['cmid'];
		$c1secid	=$row1a['secid'];
		$c1catid	=$row1a['ctgry'];
		$c1rate		=$row1a['rwdrate'];
		$c1ctype	=$row1a['ctype'];
		$c1amt		=$row1a['rwdamt'];
		$c1d1		=strtotime($row1a['d1']);
		$c1d2		=strtotime($row1a['d2']);
		$c1thresh	=$row1a['trgwght'];
		$c1label	=$row1a['name'];
        $c1trgcsrc  =$row1a['trgsrc'];
        $c1trgcsrcval=$row1a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry1b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=1 and active=1 and renov=0 and secid=0;";
		}
		
		$res1b = mssql_query($qry1b);
		$row1b = mssql_fetch_array($res1b);
		$nrow1b= mssql_num_rows($res1b);
		
		$c1cmid		=$row1b['cmid'];
		$c1secid	=$row1b['secid'];
		$c1catid	=$row1b['ctgry'];
		$c1rate		=$row1b['rwdrate'];
		$c1ctype	=$row1b['ctype'];
		$c1amt		=$row1b['rwdamt'];
		$c1d1		=strtotime($row1b['d1']);
		$c1d2		=strtotime($row1b['d2']);
		$c1thresh	=$row1b['trgwght'];
		$c1label	=$row1b['name'];
        $c1trgcsrc  =$row1b['trgsrc'];
        $c1trgcsrcval=$row1b['trgsrcval'];
	}

	if ($cinar['renov']==1) //Grab Category 2 SR OU Specific Comm
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry2a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
	$nrow2a= mssql_num_rows($res2a);
	
	if ($nrow2a==1)
	{
		$c2cmid		=$row2a['cmid'];
		$c2secid	=$row2a['secid'];
		$c2catid	=$row2a['ctgry'];
		$c2rate		=$row2a['rwdrate'];
		$c2ctype	=$row2a['ctype'];
		$c2amt		=$row2a['rwdamt'];
		$c2d1		=strtotime($row2a['d1']);
		$c2d2		=strtotime($row2a['d2']);
		$c2thresh	=$row2a['trgwght'];
		$c2label	=$row2a['name'];
        $c2trgcsrc  =$row2a['trgsrc'];
        $c2trgcsrcval=$row2a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry2b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=2 and active=1 and renov=0 and secid=0;";
		}

		$res2b = mssql_query($qry2b);
		$row2b = mssql_fetch_array($res2b);
		$nrow2b= mssql_num_rows($res2b);
		
		//echo $nrow2b.'<br>';
		
		$c2cmid		=$row2b['cmid'];
		$c2secid	=$row2b['secid'];
		$c2catid	=$row2b['ctgry'];
		$c2rate		=$row2b['rwdrate'];
		$c2ctype	=$row2b['ctype'];
		$c2amt		=$row2b['rwdamt'];
		$c2d1		=strtotime($row2b['d1']);
		$c2d2		=strtotime($row2b['d2']);
		$c2thresh	=$row2b['trgwght'];
		$c2label	=$row2b['name'];
        $c2trgsrc  =$row2b['trgsrc'];
        $c2trgcsrcval=$row2b['trgsrcval'];
	}

	/* MOVED TO CommissionScheduleRO_GMSM
	if (isset($cinar['sidm']))
	{
		//Grab Category 4 Comms (Sales Manager)
		$qry3a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=".$cinar['sidm'].";";
		$res3a = mssql_query($qry3a);
		$row3a = mssql_fetch_array($res3a);
		$nrow3a= mssql_num_rows($res3a);
		
		if ($nrow3a > 0)
		{
			$c3cmid		=$row3a['cmid'];
			$c3secid	=$row3a['secid'];
			$c3catid	=$row3a['ctgry'];
			$c3rate		=$row3a['rwdrate'];
			$c3ctype	=$row3a['ctype'];
			$c3amt		=$row3a['rwdamt'];
			$c3d1		=strtotime($row3a['d1']);
			$c3d2		=strtotime($row3a['d2']);
			$c3thresh	=$row3a['trgwght'];
			$c3label	=$row3a['name'];
		}
		else
		{
			$qry3b  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=4 and active=1 and secid=0;";
			$res3b = mssql_query($qry3b);
			$row3b = mssql_fetch_array($res3b);
			$nrow3b= mssql_num_rows($res3b);
			
			$c3cmid		=$row3b['cmid'];
			$c3secid	=$row3b['secid'];
			$c3catid	=$row3b['ctgry'];
			$c3rate		=$row3b['rwdrate'];
			$c3ctype	=$row3b['ctype'];
			$c3amt		=$row3b['rwdamt'];
			$c3d1		=strtotime($row3b['d1']);
			$c3d2		=strtotime($row3b['d2']);
			$c3thresh	=$row3b['trgwght'];
			$c3label	=$row3b['name'];
		}
	}
	*/
	
	//Grab Category 6 Comms (Bullets/SmartFeatures)
	if ($bullets > 0 and $cinar['estsecid']!=1952)
	{
		$qry4a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=6 and active=1 order by trgwght desc,ctgry asc;";
		$res4a = mssql_query($qry4a);
		$nrow4a= mssql_num_rows($res4a);
		
		if ($nrow4a > 0)
		{
			while ($row4a = mssql_fetch_array($res4a))
			{
					$grpcomar[$row4a['linkid']][]=array(
							'cmid'=>	$row4a['cmid'],
							'secid'=>	$row4a['secid'],
							'catid'=>	$row4a['ctgry'],
							'ctype'=>	$row4a['ctype'],
							'rwdrate'=>	$row4a['rwdrate'],
							'trgwght'=>	$row4a['trgwght'],
							'd1'=>		strtotime($row4a['d1']),
							'd2'=>		strtotime(date('m/d/y',strtotime($row4a['d2'])). ' 23:59:59'),
							'active'=>	$row4a['active'],
							'label'=>	$row4a['name'],
							'rwdamt'=>	$row4a['rwdamt'],
							'linkid'=>	$row4a['linkid'],
							'trgsrc'=>	$row4a['trgsrc'],
							'trgsrcval'=>$row4a['trgsrcval']
						);
			}
		}
	}
	
	//Grab Category 9 Comms (Tiered Commissions)
	$qry9a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=9 and active=1 order by trgwght desc,ctgry asc;";
	$res9a = mssql_query($qry9a);
	$nrow9a= mssql_num_rows($res9a);
	
	if ($nrow9a > 0)
	{
        //echo 'XXX';
		while ($row9a = mssql_fetch_array($res9a))
		{
            if ($row9a['rwdrate']!=0)
            {
				$tiercomar[$row9a['linkid']][]=array(
						'cmid'=>	$row9a['cmid'],
						'secid'=>	$row9a['secid'],
						'catid'=>	$row9a['ctgry'],
						'ctype'=>	$row9a['ctype'],
						'rwdrate'=>	$row9a['rwdrate'],
						'trgwght'=>	$row9a['trgwght'],
						'd1'=>		strtotime($row9a['d1']),
						'd2'=>		strtotime(date('m/d/y',strtotime($row9a['d2'])). ' 23:59:59'),
						'active'=>	$row9a['active'],
						'label'=>	$row9a['name'],
						'rwdamt'=>	$row9a['rwdamt'],
						'linkid'=>	$row9a['linkid'],
						'trgsrc'=>	$row9a['trgsrc'],
						'trgsrcval'=>$row9a['trgsrcval']
					);
            }
		}
        
        //echo '<pre>';
        //print_r($tiercomar);
        //echo '</pre>';
	}
	
	/* MOVED TO CommissionScheduleRO_GMSM
	//Grab Category 7 Comms (General Manager)
	$qry7a  = "select top 1 * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=7 and active=1 and secid=".$row0a['gm'].";";
	$res7a = mssql_query($qry7a);
	$row7a = mssql_fetch_array($res7a);
	$nrow7a= mssql_num_rows($res7a);
	
	if ($nrow7a == 1)
	{
		$c7cmid		=$row7a['cmid'];
		$c7secid	=$row7a['secid'];
		$c7catid	=$row7a['ctgry'];
		$c7rate		=$row7a['rwdrate'];
		$c7ctype	=$row7a['ctype'];
		$c7amt		=$row7a['rwdamt'];
		$c7d1		=strtotime($row7a['d1']);
		$c7d2		=strtotime($row7a['d2']);
		$c7thresh	=$row7a['trgwght'];
		$c7label	=$row7a['name'];
	}
	*/
	
	if ($cinar['renov']==1) //Grab Category 8 SR Override Comm
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=".$cinar['estsecid'].";";
	}
	else
	{
		$qry8a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=".$cinar['estsecid'].";";
	}
	
	$res8a = mssql_query($qry8a);
	$row8a = mssql_fetch_array($res8a);
	$nrow8a= mssql_num_rows($res8a);
	
	if ($nrow8a == 1)
	{
		$c8cmid		=$row8a['cmid'];
		$c8secid	=$row8a['secid'];
		$c8catid	=$row8a['ctgry'];
		$c8rate		=$row8a['rwdrate'];
		$c8ctype	=$row8a['ctype'];
		$c8amt		=$row8a['rwdamt'];
		$c8d1		=strtotime($row8a['d1']);
		$c8d2		=strtotime($row8a['d2']);
		$c8thresh	=$row8a['trgwght'];
		$c8label	=$row8a['name'];
		$c8trgsrc	=$row8a['trgsrc'];
		$c8trgsrcval=$row8a['trgsrcval'];
	}
	else
	{
		if ($cinar['renov']==1)
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=1 and secid=0;";
		}
		else
		{
			$qry8b  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry=8 and active=1 and renov=0 and secid=0;";
		}

		$res8b = mssql_query($qry8b);
		$row8b = mssql_fetch_array($res8b);
		$nrow8b= mssql_num_rows($res8b);
		
		$c8cmid		=$row8b['cmid'];
		$c8secid	=$row8b['secid'];
		$c8catid	=$row8b['ctgry'];
		$c8rate		=$row8b['rwdrate'];
		$c8ctype	=$row8b['ctype'];
		$c8amt		=$row8b['rwdamt'];
		$c8d1		=strtotime($row8b['d1']);
		$c8d2		=strtotime($row8b['d2']);
		$c8thresh	=$row8b['trgwght'];
		$c8label	=$row8b['name'];
		$c8trgsrc	=$row8b['trgsrc'];
		$c8trgsrcval=$row8b['trgsrcval'];
	}

	if (isset($cinar['contdate']))
	{
		$drange=$cinar['contdate'];
	}
	else
	{
		$drange=$cinar['sysdate'];
	}
	
	$dbg=0;
	if ($dbg==1 && $_SESSION['securityid']==26)
	{		
		echo "           <tr>\n";
		echo "              <td colspan=\"7\">\n";
		
		echo "<pre>";
		echo 'VARIABLES<br>';
		print_r($cinar);
		echo '<br><br>';
		echo 'STANDARD COMMISSIONS<br>';
		print_r($comar);
		echo '<br><br>';
		echo 'GROUP COMMISSIONS<br>';
		print_r($grpcomar);
		echo '<br><br>';
		echo 'TIMESTAMP<br>';
		echo time();
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "                        <form method=\"post\">\n";
	echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$cinar['estidret']."\">\n";
	echo "							 <input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "                           <input type=\"hidden\" name=\"call\" value=\"CreateContract\">\n";
	echo "                           <input type=\"hidden\" name=\"adjbook\" value=\"".number_format($cinar['fadjbookamt'], 2, '.', '')."\">\n";
	echo "                           <input type=\"hidden\" name=\"oubook\" value=\"".number_format(($cinar['fctramt'] - $cinar['fadjbookamt']), 2, '.', '')."\">\n";

	if ($cinar['taxtrig']==1)
	{
		echo "                           <input type=\"hidden\" name=\"salestax\" value=\"".$cinar['frtax']."\">\n";
	}
	
	//echo "			<table class=\"outer\" background=\"white\" bordercolor=\"gray\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."\"><b>Commissions</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[1]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[3]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	
	if ($nrow1a == 1 || $nrow1b == 1) // Base Entries
	{
		if ($c1ctype==1)
		{
			$fc1ctype='fx';
		}
		elseif ($c1ctype==2)
		{
			$fc1ctype='%';
		}
		else
		{
			$fc1ctype='';
		}
		
        if ($c1trgcsrcval == 1)
		{
			$c1amt=$cinar['fctramt'] * $c1rate;
            //echo '1!';
		}
		elseif ($c1trgcsrcval == 3) // Adjusted Price per Book
		{
			$c1amt=$cinar['fadjbookamt'] * $c1rate;
		}
		else
		{
			$c1amt=$cinar['fctramt'] * $c1rate;
		}
		
		$tcomm=$tcomm + $c1amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Base</b></td>\n";	
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo "<span id=\"BCratedisplay\">".($c1rate * 100)."</span>";
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc1ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($c1amt < 0)
		{
			echo "              <font color=\"red\"><span id=\"BCamtdisplay\">".number_format($c1amt, 2, '.', '')."</span></font>\n";
		}
		else
		{
			echo "<span id=\"BCamtdisplay\">".number_format($c1amt, 2, '.', '')."</span>";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//if ($bullets >= 3)
		//{
			echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][contrsrc]\" value=\"book\">\n";
			//echo "<div class=\"noPrint\"><img id=\"basefrombook\" src=\"images/information.png\" width=\"12px\" height=\"12px\" title=\"Base Comm from Price per Book\"></div>\n";
        /*
		}
		else
		{
			echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][contrsrc]\" value=\"contract\">\n";
			//echo "<div class=\"noPrint\"><img id=\"basefromcontract\" src=\"images/information.png\" width=\"12px\" height=\"12px\" title=\"Base Comm from Contract Amt\"></div>\n";
			
		}
        */
		
		echo "				<span class=\"JMStooltip\" id=\"OpenBaseCommAdjustDialog\" title=\"Adjust Base Commission\"><a href=\"#\"><b>+/-</b></a></span>\n";
		echo "				<span id=\"OrigBCAmt\">".number_format($c1amt, 2, '.', '')."</span>";
		
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][cmid]\" value=\"".$c1cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdamt]\" value=\"".number_format($c1amt, 2, '.', '')."\" id=\"BCrwdamt\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][secid]\" value=\"".$c1secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][catid]\" value=\"".$c1catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][ctype]\" value=\"".$c1ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdrate]\" value=\"".$c1rate."\" id=\"BCrwdrate\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][rwdrateorig]\" value=\"".$c1rate."\" id=\"BCrwdrateorig\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][trgwght]\" value=\"".$c1thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d1]\" value=\"".$c1d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][d2]\" value=\"".$c1d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c1cmid."][label]\" value=\"".$c1label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"BCrwdamt\">\n";
	}
	
	
	if ($nrow2a == 1 || $nrow2b == 1) //OU Entries
	{
		if ($cinar['renov']==1)
		{			
			if ($cinar['fctramt'] - $cinar['fadjbookamt'] != 0)
			{
				$c2amt=($cinar['fctramt'] - $cinar['fadjbookamt']) * $c2rate;
				$tcomm=$tcomm + $c2amt;
				
				if ($c2ctype==1)
				{
					$fc2ctype='fx';
				}
				elseif ($c2ctype==2)
				{
					$fc2ctype='%';
				}
				else
				{
					$fc2ctype='';
				}
				
				echo "           <tr>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><b>Over/<font color=\"red\">Under</font></b></td>\n";		
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".($c2rate * 100)."</font>";
				}
				else
				{
					echo $c2rate * 100;
				}
			
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".$fc2ctype."</font>";
				}
				else
				{
					echo $fc2ctype;
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\">\n";
			
				if ($c2amt < 0)
				{
					echo "              <font color=\"red\"><span id=\"OUamtdisplay\">".number_format($c2amt, 2, '.', '')."</span></font>\n";
				}
				else
				{
					echo "<span id=\"OUamtdisplay\">".number_format($c2amt, 2, '.', '')."</span>";
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][cmid]\" value=\"".$c2cmid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdamt]\" id=\"OUrwdamt\" value=\"".number_format($c2amt, 2, '.', '')."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][secid]\" value=\"".$c2secid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][catid]\" value=\"".$c2catid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][ctype]\" value=\"".$c2ctype."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdrate]\" id=\"OUrwdrate\" value=\"".$c2rate."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][trgwght]\" value=\"".$c2thresh."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d1]\" value=\"".$c2d1."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d2]\" value=\"".$c2d2."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][label]\" value=\"".$c2label."\">\n";
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			else
			{
				echo "<input type=\"hidden\" value=\"0\" id=\"OUrwdamt\">\n";
			}
		}
		else
		{
			//if ($bullets >= 3 or ($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
            if (($cinar['fctramt'] - $cinar['fadjbookamt']) != 0)
			{
				$c2amt=($cinar['fctramt'] - $cinar['fadjbookamt']) * $c2rate;
				$tcomm=$tcomm + $c2amt;
				
				if ($c2ctype==1)
				{
					$fc2ctype='fx';
				}
				elseif ($c2ctype==2)
				{
					$fc2ctype='%';
				}
				else
				{
					$fc2ctype='';
				}
				
				echo "           <tr>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\"><b>Over/<font color=\"red\">Under</font></b></td>\n";		
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".($c2rate * 100)."</font>";
				}
				else
				{
					echo $c2rate * 100;
				}
			
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				
				if (($cinar['fctramt'] - $cinar['fadjbookamt']) < 0)
				{
					echo "<font color=\"red\">".$fc2ctype."</font>";
				}
				else
				{
					echo $fc2ctype;
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "              <td class=\"wh\" align=\"right\">\n";
			
				if ($c2amt < 0)
				{
					echo "              <font color=\"red\">".number_format($c2amt, 2, '.', '')."</font>\n";
				}
				else
				{
					echo number_format($c2amt, 2, '.', '');
				}
				
				echo "				</td>\n";
				echo "              <td class=\"wh\" align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][cmid]\" value=\"".$c2cmid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdamt]\" id=\"OUrwdamt\" value=\"".number_format($c2amt, 2, '.', '')."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][secid]\" value=\"".$c2secid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][catid]\" value=\"".$c2catid."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][ctype]\" value=\"".$c2ctype."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][rwdrate]\" id=\"OUrwdrate\" value=\"".$c2rate."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][trgwght]\" value=\"".$c2thresh."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d1]\" value=\"".$c2d1."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][d2]\" value=\"".$c2d2."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$c2cmid."][label]\" value=\"".$c2label."\">\n";
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			else
			{
				echo "<input type=\"hidden\" value=\"0\" id=\"OUrwdamt\">\n";
			}
		}
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"OUrwdamt\">\n";
	}
	
	if ((isset($nrow4a) and $nrow4a > 0) and (isset($cinar['tbullets']) and $cinar['tbullets'] > 0) and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
	{
		foreach ($grpcomar as $gn1 => $gv1)
		{
			$tsval=0;
			foreach ($gv1 as $gn2 => $gv2)
			{
				if ($cinar['tbullets']==$gv2['trgwght'] && time() >= $gv2['d1'] && time() < $gv2['d2'])
				{
					if ($gv2['trgsrc']==6)
					{
						$tbamt=0;
						if ($gv2['ctype']==1) //Fixed
						{
							$rate	=0;
							$tbamt 	=$gv2['rwdamt'];
						}
						elseif ($gv2['ctype']==2)
						{
							if ($gv2['trgsrcval']==1) //Contract Amt
							{
								$tbamt=($cinar['fadjbookamt'] * $gv2['rwdrate']);
							}
							elseif ($gv2['trgsrcval']==2) //
							{
								$tbamt=(($cinar['fctramt']-$cinar['fadjbookamt']) * ($gv2['rwdrate'] * .01));
							}
						}
						
						$tcomm=$tcomm+$tbamt;
						echo "           <tr>\n";
						echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						
						if ($gv2['label']=='SRU')
						{
							echo "              <td class=\"wh\" align=\"right\"><b>".$cinar['tbullets']." SmartFeatures</b></td>\n";
						}
						else
						{
							echo "              <td class=\"wh\" align=\"right\"><b>".$cinar['tbullets']." ".$gv2['label']."</b></td>\n";
						}
						
						echo "              <td class=\"wh\" align=\"center\">".($gv2['rwdrate'] * 100)."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						
						if ($gv2['ctype']==2)
						{
							echo '%';
						}
						
						echo "				</td>\n";
						echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($tbamt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][cmid]\" value=\"".$gv2['cmid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdamt]\" id=\"BUrwdamt\" value=\"".number_format($tbamt, 2, '.', '')."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][secid]\" value=\"".$gv2['secid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][catid]\" value=\"".$gv2['catid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][ctype]\" value=\"".$gv2['ctype']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][rwdrate]\" id=\"BUrwdrate\" value=\"".$gv2['rwdrate']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][trgwght]\" value=\"".$gv2['trgwght']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d1]\" value=\"".$gv2['d1']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][d2]\" value=\"".$gv2['d2']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][label]\" value=\"".$gv2['label']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$gv2['cmid']."][tbullets]\" value=\"".$cinar['tbullets']."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"BUrwdamt\">\n";
	}
	
	if ($nrow9a > 0 and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
	{
		//echo 'Cat 9.0<br>';
		$tblock=false;
		foreach ($tiercomar as $tn1 => $tv1)
		{
			$tsvalt=0;
			foreach ($tv1 as $tn2 => $tv2)
			{
				if ($tv2['trgsrc'] == 7)
				{
					if (!$tblock and $cinar['fctramt'] >= $tv2['trgwght'] and (time() >= $tv2['d1'] and time() < $tv2['d2']))
					{
						$tbamtt=0;
						if ($tv2['ctype']==1) //Fixed
						{
							$tbamtt =$tv2['rwdamt'];
						}
						elseif ($tv2['ctype']==2) // Percent
						{
							if ($tv2['trgsrcval']==7) //Contract Amt
							{
								$tbamtt=($cinar['fctramt'] * $tv2['rwdrate']);
								$tblock=true;
							}
							else
							{
								$tbamtt=0;
							}
						}
						
						$tcomm=$tcomm+$tbamtt;
						
						echo "           <tr>\n";
						echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\"><b>Merit Bonus</b></td>\n";
						echo "              <td class=\"wh\" align=\"center\">".($tv2['rwdrate'] * 100)."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						
						if ($tv2['ctype']==2)
						{
							echo '%';
						}
						
						echo "				</td>\n";
						echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
						echo "              <td class=\"wh\" align=\"right\">".number_format($tbamtt, 2, '.', '')."</td>\n";
						echo "              <td class=\"wh\" align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][cmid]\" value=\"".$tv2['cmid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][rwdamt]\" id=\"MBrwdamt\" value=\"".number_format($tbamtt, 2, '.', '')."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][secid]\" value=\"".$tv2['secid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][catid]\" value=\"".$tv2['catid']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][ctype]\" value=\"".$tv2['ctype']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][rwdrate]\" id=\"MBrwdrate\" value=\"".$tv2['rwdrate']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][trgwght]\" value=\"".$tv2['trgwght']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][d1]\" value=\"".$tv2['d1']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][d2]\" value=\"".$tv2['d2']."\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$tv2['cmid']."][label]\" value=\"".$tv2['label']."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}
				}
			}
		}
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"MBrwdamt\">\n";
	}
	
	if (($nrow8a == 1 || $nrow8b == 1) && $tcomm < $c8amt) //Forced Override Entries (Always LAST!)
	{
		if ($c8ctype==1)
		{
			$fc8ctype='fx';
		}
		elseif ($c8ctype==2)
		{
			$fc8ctype='%';
		}
		else
		{
			$fcctype='';
		}
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>sub-Total</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($tcomm < 0)
		{
			echo "					<font color=\"red\">".number_format($tcomm, 2, '.', '')."</font>";
		}
		else
		{
			echo number_format($tcomm, 2, '.', '');
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\" height=\"12px\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "           </tr>\n";
		
		$uc8amt=$c8amt + ($tcomm * -1);
		$tcomm=$tcomm + $uc8amt;
		
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\" title=\"Adjustment required for Minimum Commission\"><b>Override</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"center\">".$fc8ctype."</td>\n";
		echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		echo number_format($uc8amt, 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<div class=\"noPrint\"><img id=\"minimumoverride\" src=\"images/information.png\" width=\"11px\" height=\"11px\" title=\"Commission Minimum Override enabled\"></div>\n";
		
		jquery_notify_popup(
							'overridetext',
							'<b>Commission Automatic Override enabled!</b><br><br>
							Commission sub-Total is below the Minimum Commission of $'.number_format($c8amt, 2, '.', '').'.<br><br>
							Commission Total has been adjusted by $'.number_format($uc8amt, 2, '.', '').' to meet the minimum.'
							);
		
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][cmid]\" value=\"".$c8cmid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdamt]\" id=\"OVrwdamt\" value=\"".number_format($uc8amt, 2, '.', '')."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][secid]\" value=\"".$c8secid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][catid]\" value=\"".$c8catid."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][ctype]\" value=\"".$c8ctype."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][rwdrate]\" id=\"OVrwdrate\" value=\"".$c8rate."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][trgwght]\" value=\"".$c8thresh."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d1]\" value=\"".$c8d1."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][d2]\" value=\"".$c8d2."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$c8cmid."][label]\" value=\"".$c8label."\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	else
	{
		echo "<input type=\"hidden\" value=\"0\" id=\"OVrwdamt\">\n";
	}
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Total</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\"><span id=\"TotalAmtDisplay\">".number_format($tcomm, 2, '.', '')."</span></font>";
	}
	else
	{
		echo "<span id=\"TotalAmtDisplay\">".number_format($tcomm, 2, '.', '')."</span>";
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "				<span id=\"OrigTotalAmt\">".number_format($tcomm, 2, '.', '')."</span>";
	
	if ($_SESSION['clev'] >= 5)
	{
		echo "					<div class=\"noPrint\">\n";
		
		if ($cinar['jobid']=='0')
		{
			if ($_SESSION['clev'] >= 9)
			{
				echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract Override: Your security level allows you to override control protocols\">\n";
			}
			else
			{
				if (isset($errbiditems['no_ret']) and $errbiditems['no_ret'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Retail Price on one or more Bid Items\">\n";
					
					jquery_notify_popup(
								'overridetext',
								'<b>Create Contract Disabled!</b><br><br>
								Missing Bid Item Retail Price on one or more Bid Items'
								);
					
				}
				elseif (isset($errbiditems['no_cst']) and $errbiditems['no_cst'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Cost on one or more Bid Items\">\n";
					
					jquery_notify_popup(
								'overridetext',
								'<b>Create Contract Disabled!</b><br><br>
								Missing Bid Item Cost on one or more Bid Items'
								);
				}
				elseif (isset($errbiditems['th_cst']) and $errbiditems['th_cst'] > 0)
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Warning: Bid Item Cost too high on one or more Bid Items\">\n";
					jquery_notify_popup(
								'overridetext',
								'Bid Item Cost too high on one or more Bid Items'
								);
				}
				else
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract\">\n";
				}
			}
		}
		else
		{
			echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Contract exists for this Estimate\" DISABLED>\n";
		}
		
		echo "					</div>\n";
	}
	
	echo "				</td>\n";
	echo "           </tr>\n";
	echo "			</form>\n";
	
	return number_format($tcomm, 2, '.', '');
}

function CommissionScheduleRO_After_Contract($v,$col_struct)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
    
    $tsecid=26;
	$dbg=1;
	if (isset($dbg) && $dbg==0 && $_SESSION['securityid']==$tsecid)
	{
        echo __FUNCTION__.'<br>';
		echo "<pre>";
		print_r($v);
		//echo 'MOD:'.$_SESSION['modcomm'].'<br>';
		echo "</pre>";
	}
	
	$dbg=0;
	$tcomm=0;
	
	//echo "			<table background=\"white\" bordercolor=\"gray\" width=\"".array_sum($col_struct)."px\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."\"><b>Commissions</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[1]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[3]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	
	if ($dbg==1)
	{
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"7\">\n";
		
		echo "<pre>";
		print_r($v);
		echo '<br><br>';
		//print_r($comar);
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 1 SR Specific Comm
	$qry1a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=1;";
	$res1a = mssql_query($qry1a);
	$row1a = mssql_fetch_array($res1a);
    $nrow1a= mssql_num_rows($res1a);
	
	if ($nrow1a==1)
	{			
		$tcomm=$tcomm + $row1a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Base Comm</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row1a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row1a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row1a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo1\">".number_format($row1a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo1\">".number_format($row1a['amt'], 2, '.', '')."</div>";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 2 SR OU Specific Comm
	$qry2a  = "select * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=2;";
	$res2a = mssql_query($qry2a);
	$row2a = mssql_fetch_array($res2a);
    $nrow2a= mssql_num_rows($res2a);
	
	if ($nrow2a==1)
	{		
		$tcomm=$tcomm + $row2a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Over/Under Comm</font></b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row2a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
			
		if ($row2a['type'] == 2)
		{
			echo '%';
		}
			
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
	
		if ($row2a['amt'] < 0)
		{
			echo "              <font color=\"red\"><div id=\"ouo2\">".number_format($row2a['amt'], 2, '.', '')."</div></font>\n";
		}
		else
		{
			echo "				<div id=\"ouo2\">".number_format($row2a['amt'], 2, '.', '')."</div>\n";
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "              	<img src=\"images/pixel.gif\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 6 Comms
	$qry6a  = "select top 1 * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=6 order by adate desc;";
	$res6a = mssql_query($qry6a);
	$row6a = mssql_fetch_array($res6a);
    $nrow6a= mssql_num_rows($res6a);
	
	if ($nrow6a > 0)
	{		
		$tcomm=$tcomm + $row6a['amt'];
		echo "           <tr>\n";
		//echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$row6a['label']."</b></td>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>SmartFeature Bonus</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row6a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row6a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row6a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";		
		echo "				</td>\n";
		echo "           </tr>\n";
	}

	//Grab Category 9 Comms
	$qry9a  = "select top 1 CS.*,(select fullname from CommissionBuilderCategory where catid=CS.cbtype) as fullname from jest..CommissionSchedule as CS where CS.oid='".$_SESSION['officeid']."' and CS.jobid='".$v['jobid']."' and CS.cbtype=9 order by CS.adate desc;";
	$res9a = mssql_query($qry9a);
	$row9a = mssql_fetch_array($res9a);
    $nrow9a= mssql_num_rows($res9a);
	
	if ($nrow9a > 0)
	{		
		$tcomm=$tcomm + $row9a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$row9a['fullname']."</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		echo $row9a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row9a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row9a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 8 Comms
	$qry8a  = "select top 1 * from jest..CommissionSchedule where oid=".$_SESSION['officeid']." and jobid='".$v['jobid']."' and cbtype=8 order by adate desc;";
	$res8a = mssql_query($qry8a);
	$row8a = mssql_fetch_array($res8a);
    $nrow8a= mssql_num_rows($res8a);
	
	if ($nrow8a > 0)
	{
		$tcomm=$tcomm + $row8a['amt'];
		echo "           <tr>\n";
		//echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>".$row8a['label']."</b></td>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Commission Override</b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//echo $row8a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//if ($row8a['type'] == 2)
		//{
		//	echo '%';
		//}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row8a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "					<div class=\"noPrint JMStooltip\" title=\"Minimum Commission Override enabled\"><img src=\"images/information.png\" width=\"11px\" height=\"11px\"></div>\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	//Grab Category 0 Manual Adjust Comm
	$qry3a  = "select top 1 * from jest..CommissionSchedule where oid='".$_SESSION['officeid']."' and jobid='".$v['jobid']."' and cbtype=0 order by adate desc;";
	$res3a = mssql_query($qry3a);
	$row3a = mssql_fetch_array($res3a);
    $nrow3a= mssql_num_rows($res3a);
	
	if ($nrow3a > 0)
	{		
		$tcomm=$tcomm + $row3a['amt'];
		echo "           <tr>\n";
		echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b><div title=\"".$row3a['notes']."\">Manual Adjust</div></b></td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		//echo $row3a['rate'];
	
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		
		if ($row3a['type'] == 2)
		{
			echo '%';
		}
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\">\n";
		
		echo number_format($row3a['amt'], 2, '.', '');
		
		echo "				</td>\n";
		echo "              <td class=\"wh\" align=\"center\">\n";
		echo "				</td>\n";
		echo "           </tr>\n";
	}

	//Grab Category 4+ Comms
	/*$qry3a  = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and ctgry >=4 and active=1 order by secid asc,ctgry asc;";
	$res3a = mssql_query($qry3a);
    $nrow3a= mssql_num_rows($res3a);
	
	if ($nrow3a > 0)
	{
		while ($row3a = mssql_fetch_array($res3a))
        {
            $comar[]=array(
						'cmid'=>$row3a['cmid'],
						'secid'=>$row3a['secid'],
						'catid'=>$row3a['ctgry'],
						'ctype'=>$row3a['ctype'],
						'rate'=>$row3a['rate'],
						'thresh'=>$row3a['thresh'],
						'd1'=>strtotime($row3a['d1']),
						'd2'=>strtotime($row3a['d2']),
						'active'=>$row3a['active'],
						'label'=>$row3a['name'],
						'amt'=>$row3a['amt']
					);
        }
	}
	
	if ($nrow3a > 0)
	{
		foreach ($comar as $cn => $cv)
		{
			if ($drange >= $cv['d1'] && $drange < $cv['d2'])
			{
				if ($cv['ctype']==1)
				{
					$ctype='fx';
				}
				elseif ($cv['ctype']==2)
				{
					$ctype='%';
				}
				else
				{
					$ctype='';
				}
				
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][cmid]\" value=\"".$cv['cmid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][secid]\" value=\"".$cv['secid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\" value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\" value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rate]\" value=\"".$cv['rate']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][thresh]\" value=\"".$cv['thresh']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][d1]\" value=\"".$cv['d1']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][d2]\" value=\"".$cv['d2']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\" value=\"".$cv['label']."\">\n";
				
				if ($cv['catid'] == 3)
				{
					
					if ($cv['secid']==0 || $cinar['estsecid']==$cv['secid'])
					{
						if ($ctype==1)
						{
							$amt=($cinar['fctramt'] * $cv['rate']);
						}
						else
						{
							$amt=$cv['amt'];
						}
						
						$tcomm=$tcomm+$amt;
						echo "           <tr>\n";
						echo "              <td colspan=\"2\" align=\"right\"><b>".$cv['label']."</b></td>\n";
						echo "              <td align=\"center\"></td>\n";
						echo "              <td align=\"center\">".$ctype."</td>\n";
						echo "              <td align=\"right\"></td>\n";
						echo "              <td align=\"right\">".number_format($amt, 2, '.', '')."</td>\n";
						echo "              <td align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][amt]\" value=\"".number_format($amt, 2, '.', '')."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}

				}
				elseif ($cv['catid'] == 4)
				{

					if ($cv['secid']==0 || $cinar['estsecid']==$cv['secid'])
					{
						if ($ctype==1)
						{
							$amt=($cinar['fctramt'] * $cv['rate']);
						}
						else
						{
							$amt=$cv['amt'];
						}
						
						$tcomm=$tcomm+$amt;
						echo "           <tr>\n";
						echo "              <td colspan=\"2\" align=\"right\"><b>".$cv['label']."</b></td>\n";
						echo "              <td align=\"center\"></td>\n";
						echo "              <td align=\"center\">".$ctype."</td>\n";
						echo "              <td align=\"right\"></td>\n";
						echo "              <td align=\"right\">".number_format($amt, 2, '.', '')."</td>\n";
						echo "              <td align=\"center\">\n";
						echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][amt]\" value=\"".number_format($amt, 2, '.', '')."\">\n";
						echo "				</td>\n";
						echo "           </tr>\n";
					}

				}
			}
		}
	}*/
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" colspan=\"2\" align=\"right\"><b>Total Comm</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\"><div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div></font>";
	}
	else
	{
		echo "					<div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div>";
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "              <form method=\"POST\">\n";
    echo "              	<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
    echo "              	<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
    echo "              	<input type=\"hidden\" name=\"jobid\" value=\"".$v['jobid']."\">\n";
    echo "              	<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
    echo "              	<input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_link.png\" title=\"Contract Created, Click to View Contract\">\n";
    echo "              </form>\n";
	echo "				</td>\n";
	echo "           </tr>\n";
	
	return number_format($tcomm, 2, '.', '');
}

function est_search()
{
	//ini_set('display_errors','On');
	$acclist=explode(",",$_SESSION['aid']);
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$res1 = mssql_query($qry1);

	echo "<table width=\"950px\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "         <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td bgcolor=\"#d3d3d3\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\"><b>Estimate Search Tool</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"bottom\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "                                  <td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Data Field</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Input Parameter</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Type</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Renov Only</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Sort</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Order</b></td>\n";
	echo "                              	<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b></b></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "         								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	//echo "											<input type=\"hidden\" name=\"subq\" value=\"estnum\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\">\n";
	echo "												<select name=\"subq\">\n";
	echo "                                 		<option value=\"last_name\">Customer Last Name</option>\n";
	//echo "                                 		<option value=\"enum\">Estimate #</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo " 	                                <td align=\"left\" valign=\"bottom\"><input class=\"bboxl\" type=\"text\" name=\"sval\" size=\"20\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo " 	                                <td align=\"left\" valign=\"bottom\">\n";
	echo "										<select name=\"etype\">\n";
	echo "											<option value=\"E\">Estimate</option>\n";
	
	if ($_SESSION['securityid']==543||$_SESSION['securityid']==1550||$_SESSION['securityid']==26||$_SESSION['securityid']==332)
	{
		echo "											<option value=\"Q\">Quote</option>\n";
	}
	
	echo "										</select>\n";
	echo "									</td>\n";
	echo "                              	<td align=\"center\" valign=\"bottom\"><input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this Box to Show Renovations Only\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"a.estid\" SELECTED>Estimate #</option>\n";
	echo "                                 		<option value=\"a.added\">Insert Date</option>\n";
	echo "                                 		<option value=\"b.clname\">Last Name</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                 		<option value=\"DESC\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "         								</form>\n";
	echo "										</tr>\n";

	//if ($_SESSION['llev'] >= 5)
	//{
	echo "										<tr>\n";
	echo "         								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
	echo "                              	<td align=\"right\" valign=\"bottom\"><b>Salesman:</b></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"assigned\">\n";

	while ($row1 = mssql_fetch_array($res1))
	{
		if (in_array($row1['securityid'],$acclist))
		{
			$secl=explode(",",$row1['slevel']);
			if ($secl[6]==0)
			{
				$ostyle="fontred";
			}
			else
			{
				$ostyle="fontblack";
			}

			echo "                                    	<option value=\"".$row1['securityid']."\" class=\"".$ostyle."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
		}
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	echo " 	                                <td align=\"left\" valign=\"bottom\">\n";
	echo "										<select name=\"etype\">\n";
	echo "											<option value=\"E\">Estimate</option>\n";
	
	if ($_SESSION['securityid']==543||$_SESSION['securityid']==1550||$_SESSION['securityid']==26||$_SESSION['securityid']==332)
	{
		echo "											<option value=\"Q\">Quote</option>\n";
	}
	
	echo "										</select>\n";
	echo "									</td>\n";
	echo "                              	<td align=\"center\" valign=\"bottom\"><input class=\"checkboxgry\" type=\"checkbox\" name=\"renov\" value=\"1\" title=\"Check this Box to Show Renovations Only\"></td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "                                 		<option value=\"a.estid\" SELECTED>Estimate #</option>\n";
	echo "                                 		<option value=\"a.added\">Insert Date</option>\n";
	echo "                                 		<option value=\"b.clname\">Last Name</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                              	<td align=\"left\" valign=\"bottom\">\n";
	echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>Ascending</option>\n";
	echo "                                 		<option value=\"DESC\">Descending</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\" valign=\"bottom\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "         								</form>\n";
	echo "										</tr>\n";
	//}

	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function CommissionScheduleRW($c)
{
	$dbg=0;
	$tcomm=0;
	$sec_ar=array();
	$sec_ar1=array();
	
	$qrypre1	= "SELECT securityid FROM security WHERE modcomm=1;";
	$respre1	= mssql_query($qrypre1);
	
	while ($rowpre1	= mssql_fetch_array($respre1))
	{
		$sec_ar[]=$rowpre1['securityid'];
	}
	
	echo "	<div id=\"masterdiv\">\n";
	echo "		<table class=\"transnb\" align=\"center\">\n";
	
	if ($dbg==1 && $_SESSION['securityid']==SYS_ADMIN)
	{
		echo "			<tr>\n";
		echo "				<td colspan=\"4\">\n";
		
		echo "<pre>";
		print_r($c);
		echo "</pre>";
		echo '<br><br>';
		echo "<pre>";
		print_r($_REQUEST['csched']);
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "			<tr>\n";
	echo "				<td align=\"right\" width=\"125\"><b>Contract Amount</b></td>\n";
	echo "				<td align=\"right\" width=\"70\">\n";
	
	echo $c['fctramt'];

	echo "				</td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" width=\"125\"><b>Adjusted Book Price</b></td>\n";
	echo "				<td align=\"right\" width=\"70\">\n";
	
	echo $c['adjbook'];

	echo "				</td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" width=\"125\"><b>Over/Under Amt</b></td>\n";
	echo "				<td align=\"right\" width=\"70\">\n";
	
	if ($c['oubook'] < 0)
	{
		echo "<font color=\"red\">".$c['oubook']."</font>";
	}
	else
	{
		echo $c['oubook'];
	}

	echo "				</td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "			</tr>\n";

	if (isset($_REQUEST['csched']) && is_array($_REQUEST['csched']))
	{
		$btrap=1;
		$btrap2=1;
		foreach ($_REQUEST['csched'] as $cn => $cv)
		{
			if (isset($cv['catid']) && $cv['catid']==1)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				echo "			<tr>\n";
				echo "              <td align=\"right\"><b>Base Comm</b></td>\n";
				echo "              <td align=\"right\">\n";				
				echo "              	<input DISABLED class=\"brdrtxtrght\" id=\"ouod1\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['adjbook'] * $cv['rwdrate']), 2, '.', '')."\" size=\"7\">\n";
				echo "              	<input type=\"hidden\" id=\"ouo1\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['adjbook'] * $cv['rwdrate']), 2, '.', '')."\" onChange=\"updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\">\n";
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				echo "					<input type=\"hidden\" id=\"c_amt\" name=\"c_amt\" value=\"".$c['adjbook']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estidret']).".".$_SESSION['securityid']."\">\n";
				
				if (in_array($_SESSION['securityid'],$sec_ar))
				{
					echo "              	<input class=\"bboxbc\" id=\"ouperc1\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\" size=\"2\" onChange=\"updPerc('ouperc1','c_amt','ouo1'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\">%\n";
				}
				else
				{
					echo ($cv['rwdrate'] * 100).' %';
					echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\"  value=\"".($cv['rwdrate'] * 100)."\">\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				
				if (in_array($_SESSION['securityid'],$sec_ar))
				{
					echo "					<table cellspacing=0 cellpadding=0>\n";
					echo "						<tr>\n";
					echo "							<td align=\"center\">\n";
					echo "								<img id=\"oucinc\" src=\"images/arrow_top.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('ouperc1','c_amt','ouo1','inc'); DispPercRes('ouperc1','c_amt','ouod1'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\" title=\"Adjust Up\">\n";
					echo "							</td>\n";
					echo "							<td align=\"center\">\n";
					echo "								<img id=\"oucdec\" src=\"images/arrow_down.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('ouperc1','c_amt','ouo1','dec'); DispPercRes('ouperc1','c_amt','ouod1'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\" title=\"Adjust Down\">\n";
					echo "							</td>\n";
					echo "						</tr>\n";
					echo "					</table>\n";
				}
				
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			
			if (isset($cv['catid']) && $cv['catid']==2)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>Over/<font color=\"red\">Under</font> Comm</b></td>\n";
				echo "              <td align=\"right\">\n";
				echo "              	<input DISABLED class=\"brdrtxtrght\" id=\"ouod2\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['oubook'] * $cv['rwdrate']), 2, '.', '')."\" size=\"7\">\n";
				echo "              	<input type=\"hidden\" id=\"ouo2\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['oubook'] * $cv['rwdrate']), 2, '.', '')."\" onChange=\"updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\">\n";
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				echo "					<input type=\"hidden\" id=\"oubook\" name=\"oubook\"  value=\"".$c['oubook']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estidret']).".".$_SESSION['securityid']."\">\n";
				
				if (in_array($_SESSION['securityid'],$sec_ar))
				{
					echo "              	<input class=\"bboxbc\" id=\"ouperc2\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\" size=\"2\" onChange=\"updPerc('ouperc2','oubook','ouo2'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\">%\n";
				}
				else
				{
					echo ($cv['rwdrate'] * 100).' %';
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				
				if (in_array($_SESSION['securityid'],$sec_ar))
				{
					echo "					<table cellspacing=0 cellpadding=0>\n";
					echo "						<tr>\n";
					echo "							<td align=\"center\">\n";
					echo "								<img id=\"oucomminc\" src=\"images/arrow_top.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('ouperc2','oubook','ouo2','inc'); DispPercRes('ouperc2','oubook','ouod2'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\" title=\"Adjust Up\">\n";
					echo "							</td>\n";
					echo "							<td align=\"center\">\n";
					echo "								<img id=\"oucommdec\" src=\"images/arrow_down.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('ouperc2','oubook','ouo2','dec'); DispPercRes('ouperc2','oubook','ouod2'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\" title=\"Adjust Down\">\n";
					echo "							</td>\n";
					echo "						</tr>\n";
					echo "					</table>\n";
				}
				
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			
			if (isset($cv['catid']) && $cv['catid']==4) // Sales Manager Comm
			{
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estidret']).".".$_SESSION['securityid']."\">\n";
				
				if (isset($c['oubook']) && $c['oubook'] > 0)
				{
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['fctramt'] * $cv['rwdrate']), 2, '.', '')."\">\n";
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				}
				else
				{
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['fctramt'] * ($cv['rwdrate']/2)), 2, '.', '')."\">\n";
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".(($cv['rwdrate']/2) * 100)."\">\n";
				}
			}
			
			if (isset($cv['catid']) && $cv['catid'] == 6)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				
				if (isset($cv['tbullets']) and $cv['tbullets'] > 0)
				{
					$tbullets=$cv['tbullets'];
				}
				else
				{
					$tbullets='';
				}
				
				echo "           <tr>\n";
				//echo "              <td align=\"right\"><b>".$tbullets." ".$cv['label']."</b></td>\n";
				echo "              <td align=\"right\"><b>".$tbullets." SmartFeature Bonus</b></td>\n";
				echo "              <td align=\"right\">\n";
				
				if (($cv['rwdamt']) < 0)
				{
					echo "					<font color=\"red\"><div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div></font>\n";
				}
				else
				{
					echo "					<div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div>\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estidret']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($cv['rwdamt'], 2, '.', '')."\">\n";
				
				if ($cv['rwdrate'] < 0)
				{
					echo "              <font color=\"red\">".($cv['rwdrate'] * 100)."%</font>\n";
				}
				else
				{
					echo "              ".($cv['rwdrate'] * 100)."%\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\"></td>\n";
				echo "           </tr>\n";
				$btrap=0;
			}
			
			if (isset($cv['catid']) && $cv['catid'] == 9)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>Merit Bonus</b></td>\n";
				echo "              <td align=\"right\">\n";
				
				if (($cv['rwdamt']) < 0)
				{
					echo "					<font color=\"red\"><div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div></font>\n";
				}
				else
				{
					echo "					<div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div>\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estidret']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($cv['rwdamt'], 2, '.', '')."\">\n";
				
				if ($cv['rwdrate'] < 0)
				{
					echo "              <font color=\"red\">".($cv['rwdrate'] * 100)."%</font>\n";
				}
				else
				{
					echo "              ".($cv['rwdrate'] * 100)."%\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\"></td>\n";
				echo "           </tr>\n";
			}
			
			if (isset($cv['catid']) && $cv['catid']==7) // General Manager Comm
			{
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estidret']).".".$_SESSION['securityid']."\">\n";
				
				if (isset($c['oubook']) && $c['oubook'] > 0)
				{
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['fctramt'] * $cv['rwdrate']), 2, '.', '')."\">\n";
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				}
				else
				{
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['fctramt'] * ($cv['rwdrate']/2)), 2, '.', '')."\">\n";
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".(($cv['rwdrate']/2) * 100)."\">\n";
				}
			}
			
			if (isset($cv['catid']) && $cv['catid']==8)// Override
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>Override</b></td>\n";
				echo "              <td align=\"right\">\n";
				echo "					<div id=\"ouo8\">".number_format($cv['rwdamt'], 2, '.', '')."</div>\n";
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estidret']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".$cv['rwdamt']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($cv['rwdamt'], 2, '.', '')."\">\n";
				echo "				</td>\n";
				echo "              <td align=\"left\"></td>\n";
				echo "           </tr>\n";
				$btrap2=0;
			}
		}
		
		if ($btrap==1)
		{
			echo "           <tr>\n";
			echo "              <td>\n";
			echo "					<div style=\"visibility:hidden;\" id=\"ouo6\">0</div>\n";
			echo "				</td>\n";
			echo "           </tr>\n";
		}
		
		if ($btrap2==1)
		{
			echo "           <tr>\n";
			echo "              <td>\n";
			echo "					<div style=\"visibility:hidden;\" id=\"ouo8\">0</div>\n";
			echo "				</td>\n";
			echo "           </tr>\n";
		}
	}
	
	if ($_SESSION['clev'] >= 4)
	{
		echo "			<tr>\n";
		echo "              <td align=\"right\">\n";
		echo "					<b>Manual Adjust</b>";
		echo "				</td>\n";
		echo "              <td align=\"right\" valign=\"top\">\n";
		echo "              	<input class=\"brdrtxtrght\" id=\"ouo0\" type=\"text\" name=\"csched[0][rwdamt]\" value=\"".number_format($c['fadjcomm'], 2, '.', '')."\" size=\"7\" onChange=\"updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\">\n";
		echo "				</td>\n";	
		echo "              <td align=\"center\" valign=\"top\" colspan=\"3\" rowspan=\"2\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][label]\"	value=\"SRM\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][catid]\"	value=\"0\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][ctype]\"	value=\"1\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][rwdrate]\"	value=\"0\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][uid]\"		value=\"".md5(session_id().time().$c['estidret']).".".$_SESSION['securityid']."\">\n";
		echo "						<textarea id=\"manadjnote\" name=\"csched[0][notes]\" cols=\"18\" rows=\"3\"></textarea>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}
	
	echo "           <tr>\n";
	echo "              <td align=\"right\" valign=\"top\"><b>Total Comm</b></td>\n";
	echo "              <td align=\"right\" valign=\"top\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\"><div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div></font>\n";
	}
	else
	{
		echo "					<div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div>\n";
	}
	
	echo "				</td>\n";
	echo "              <td colspan=\"2\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	echo "			<tr>\n";
	echo "              <td align=\"center\" colspan=\"4\"><img src=\"images/pixel.gif\"></td>\n";	
	echo "			</tr>\n";
	echo "			</table>\n";
	echo "		</div>\n";
}

function CommissionScheduleRW_NEW_COMM_EDIT($c)
{
	
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$dbg=0;
	$tcomm=0;
	$sec_ar=array();
	$sec_ar1=array();
	
	$qrypre1	= "SELECT securityid FROM security WHERE modcomm=1;";
	$respre1	= mssql_query($qrypre1);
	
	while ($rowpre1	= mssql_fetch_array($respre1))
	{
		$sec_ar[]=$rowpre1['securityid'];
	}
	
	echo "				<table class=\"transnb\" align=\"center\" height=\"300px\">\n";
	echo "					<tr>\n";
	echo "						<td align=\"center\" valign=\"top\">\n";
	echo "		<table class=\"transnb\">\n";
	
	if ($dbg==1 && $_SESSION['securityid']==SYS_ADMIN)
	{
		echo "			<tr>\n";
		echo "				<td colspan=\"4\">\n";
		
		echo "<pre>";
		print_r($c);
		echo "</pre>";
		echo '<br><br>';
		echo "<pre>";
		print_r($_REQUEST['csched']);
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "			<tr>\n";
	echo "				<td align=\"right\" width=\"125\"><b>Contract Amount</b></td>\n";
	echo "				<td align=\"right\" width=\"70\">\n";
	
	echo $c['fctramt'];

	echo "				</td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" width=\"125\"><b>Adjusted Book Price</b></td>\n";
	echo "				<td align=\"right\" width=\"70\">\n";
	
	echo $c['adjbook'];

	echo "				</td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" width=\"125\"><b>Over/Under Amt</b></td>\n";
	echo "				<td align=\"right\" width=\"70\">\n";
	
	if ($c['oubook'] < 0)
	{
		echo "<font color=\"red\">".$c['oubook']."</font>";
	}
	else
	{
		echo $c['oubook'];
	}

	echo "				</td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "			</tr>\n";

	if (isset($_REQUEST['csched']) && is_array($_REQUEST['csched']))
	{
		$btrap=0;
		$btrap2=0;
		foreach ($_REQUEST['csched'] as $cn => $cv)
		{
			if (isset($cv['catid']) && $cv['catid']==1)
			{
				if (isset($cv['contrsrc']) and $cv['contrsrc']=='book')
				{
					$srcamt=$c['adjbook'];
				}
				else
				{
					$srcamt=$c['fctramt'];
				}
				
				//$tcomm=$tcomm+$cv['rwdamt'];
				$tcomm=$tcomm + ($srcamt * $cv['rwdrate']);
				echo "			<tr>\n";
				echo "              <td align=\"right\"><b>Base Comm</b></td>\n";
				echo "              <td align=\"right\">\n";				
				echo "              	<input DISABLED class=\"brdrtxtrght\" id=\"ouod1\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($srcamt * $cv['rwdrate']), 2, '.', '')."\" size=\"7\">\n";
				echo "              	<input type=\"hidden\" id=\"ouo1\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($srcamt * $cv['rwdrate']), 2, '.', '')."\" onChange=\"updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\">\n";
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				echo "					<input type=\"hidden\" id=\"c_amt\" name=\"c_amt\" value=\"".$srcamt."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				
				if (in_array($_SESSION['securityid'],$sec_ar))
				{
					echo "              	<input class=\"bboxbc\" id=\"ouperc1\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\" size=\"2\" onChange=\"updPerc('ouperc1','c_amt','ouo1'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\"> %\n";
				}
				else
				{
					echo ($cv['rwdrate'] * 100).' %';
					echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\"  value=\"".($cv['rwdrate'] * 100)."\">\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				
				if (in_array($_SESSION['securityid'],$sec_ar))
				{
					echo "					<table cellspacing=0 cellpadding=0>\n";
					echo "						<tr>\n";
					echo "							<td align=\"center\">\n";
					echo "								<img id=\"oucinc\" src=\"images/arrow_top.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('ouperc1','c_amt','ouo1','inc'); DispPercRes('ouperc1','c_amt','ouod1'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\" title=\"Adjust Up\">\n";
					echo "							</td>\n";
					echo "							<td align=\"center\">\n";
					echo "								<img id=\"oucdec\" src=\"images/arrow_down.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('ouperc1','c_amt','ouo1','dec'); DispPercRes('ouperc1','c_amt','ouod1'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\" title=\"Adjust Down\">\n";
					echo "							</td>\n";
					echo "						</tr>\n";
					echo "					</table>\n";
				}
				
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			
			if (isset($cv['catid']) && $cv['catid']==2)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>Over/<font color=\"red\">Under</font> Comm</b></td>\n";
				echo "              <td align=\"right\">\n";
				echo "              	<input DISABLED class=\"brdrtxtrght\" id=\"ouod2\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['oubook'] * $cv['rwdrate']), 2, '.', '')."\" size=\"7\">\n";
				echo "              	<input type=\"hidden\" id=\"ouo2\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['oubook'] * $cv['rwdrate']), 2, '.', '')."\" onChange=\"updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\">\n";
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				echo "					<input type=\"hidden\" id=\"oubook\" name=\"oubook\"  value=\"".$c['oubook']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				
				if (in_array($_SESSION['securityid'],$sec_ar))
				{
					echo "              	<input class=\"bboxbc\" id=\"ouperc2\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\" size=\"2\" onChange=\"updPerc('ouperc2','oubook','ouo2'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\">%\n";
				}
				else
				{
					echo ($cv['rwdrate'] * 100).' %';
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				
				if (in_array($_SESSION['securityid'],$sec_ar))
				{
					echo "					<table cellspacing=0 cellpadding=0>\n";
					echo "						<tr>\n";
					echo "							<td align=\"center\">\n";
					echo "								<img id=\"oucomminc\" src=\"images/arrow_top.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('ouperc2','oubook','ouo2','inc'); DispPercRes('ouperc2','oubook','ouod2'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\" title=\"Adjust Up\">\n";
					echo "							</td>\n";
					echo "							<td align=\"center\">\n";
					echo "								<img id=\"oucommdec\" src=\"images/arrow_down.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('ouperc2','oubook','ouo2','dec'); DispPercRes('ouperc2','oubook','ouod2'); updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\" title=\"Adjust Down\">\n";
					echo "							</td>\n";
					echo "						</tr>\n";
					echo "					</table>\n";
				}
				
				echo "				</td>\n";
				echo "           </tr>\n";
			}
			
			/* Moved to CommissionScheduleRO_GMSM
			if (isset($cv['catid']) && $cv['catid']==4) // Sales Manager Comm
			{
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				
				if (isset($c['oubook']) && $c['oubook'] > 0)
				{
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['fctramt'] * $cv['rwdrate']), 2, '.', '')."\">\n";
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				}
				else
				{
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['fctramt'] * ($cv['rwdrate']/2)), 2, '.', '')."\">\n";
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".(($cv['rwdrate']/2) * 100)."\">\n";
				}
			}
			*/
			
			if (isset($cv['catid']) && $cv['catid'] == 6)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				
				if (isset($cv['tbullets']) and $cv['tbullets'] > 0)
				{
					$tbullets=$cv['tbullets'];
				}
				else
				{
					$tbullets='';
				}
				
				echo "           <tr>\n";
				//echo "              <td align=\"right\"><b>".$tbullets." ".$cv['label']."</b></td>\n";
				echo "              <td align=\"right\"><b>".$tbullets." SmartFeature Bonus</b></td>\n";
				echo "              <td align=\"right\">\n";
				
				if (($cv['rwdamt']) < 0)
				{
					echo "					<font color=\"red\"><div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div></font>\n";
				}
				else
				{
					echo "					<div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div>\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($cv['rwdamt'], 2, '.', '')."\">\n";
				
				if ($cv['rwdrate'] < 0)
				{
					echo "              <font color=\"red\">".($cv['rwdrate'] * 100)."%</font>\n";
				}
				else
				{
					echo "              ".($cv['rwdrate'] * 100)."%\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\"></td>\n";
				echo "           </tr>\n";
				$btrap++;
			}
			
			if (isset($cv['catid']) && $cv['catid'] == 9)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>Merit Bonus</b></td>\n";
				echo "              <td align=\"right\">\n";
				
				if (($cv['rwdamt']) < 0)
				{
					echo "					<font color=\"red\"><div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div></font>\n";
				}
				else
				{
					echo "					<div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div>\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($cv['rwdamt'], 2, '.', '')."\">\n";
				
				if ($cv['rwdrate'] < 0)
				{
					echo "              <font color=\"red\">".($cv['rwdrate'] * 100)."%</font>\n";
				}
				else
				{
					echo "              ".($cv['rwdrate'] * 100)."%\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\"></td>\n";
				echo "           </tr>\n";
			}
			
			if (isset($cv['catid']) && $cv['catid']==8)// Override
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>Override</b></td>\n";
				echo "              <td align=\"right\">\n";
				echo "					<div id=\"ouo8\">".number_format($cv['rwdamt'], 2, '.', '')."</div>\n";
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".$cv['rwdamt']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($cv['rwdamt'], 2, '.', '')."\">\n";
				echo "				</td>\n";
				echo "              <td align=\"left\"></td>\n";
				echo "           </tr>\n";
				$btrap2++;
			}
		}
	}
	
	if ($_SESSION['clev'] >= 99)
	{
		echo "			<tr>\n";
		echo "              <td align=\"right\">\n";
		echo "					<b>Manual Adjust</b>";
		echo "				</td>\n";
		echo "              <td align=\"right\" valign=\"top\">\n";
		echo "              	<input class=\"brdrtxtrght\" id=\"ouo0\" type=\"text\" name=\"csched[0][rwdamt]\" value=\"0.00\" size=\"7\" onChange=\"updTotalComm('ouo0','ouo1','ouo2','ouo6','ouo8','tcommamt');\">\n";
		echo "				</td>\n";	
		echo "              <td align=\"center\" valign=\"top\" colspan=\"3\" rowspan=\"2\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][label]\"	value=\"SRM\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][catid]\"	value=\"0\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][ctype]\"	value=\"1\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][rwdrate]\"	value=\"0\">\n";
		echo "					<input type=\"hidden\" name=\"csched[0][uid]\"		value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
		echo "						<textarea id=\"manadjnote\" name=\"csched[0][notes]\" cols=\"18\" rows=\"3\"></textarea>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}
	
	echo "			<tr>\n";
	echo "              <td align=\"center\" colspan=\"4\"><hr width=\"90%\"></td>\n";	
	echo "			</tr>\n";
	echo "           <tr>\n";
	echo "              <td align=\"right\"><b>Total Comm</b></td>\n";
	echo "              <td align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\"><div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div></font>\n";
	}
	else
	{
		echo "					<div id=\"tcommamt\">".number_format($tcomm, 2, '.', '')."</div>\n";
	}
	
	echo "				</td>\n";
	echo "              <td colspan=\"2\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	echo "			</table>\n";
	echo "								</td>\n";	
	echo "   						</tr>\n";
	echo "   					</table>\n";
	
	if ($btrap > 0)
	{
		echo "					<div style=\"visibility:hidden;\" id=\"ouo6\">0</div>\n";
	}
	
	if ($btrap2 > 0)
	{
		echo "					<div style=\"visibility:hidden;\" id=\"ouo8\">0</div>\n";
	}
}

function CommissionScheduleRW_NEW($c)
{
	
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$dbg=1;
	$tcomm=0;
	$sec_ar=array();
	$sec_ar1=array();
	
	$qrypre1	= "SELECT securityid FROM security WHERE modcomm=1;";
	$respre1	= mssql_query($qrypre1);
	
	while ($rowpre1	= mssql_fetch_array($respre1))
	{
		$sec_ar[]=$rowpre1['securityid'];
	}
	
	//echo "				<table class=\"transnb\" align=\"center\" height=\"300px\">\n";
	//echo "					<tr>\n";
	//echo "						<td align=\"center\" valign=\"top\">\n";
	echo "		<table class=\"transnb\">\n";
	
	if ($dbg==1 && $_SESSION['securityid']==26)
	{
		echo "			<tr>\n";
		echo "				<td colspan=\"4\">\n";
		
		echo "<pre>";
		print_r($c);
		echo "</pre>";
		echo '<br><br>';
		echo "<pre>";
		print_r($_REQUEST['csched']);
		echo "</pre>";
		
		echo "				</td>\n";
		echo "           </tr>\n";
	}
	
	echo "			<tr>\n";
	echo "				<td align=\"right\" width=\"125\"><b>Contract Amount</b></td>\n";
	echo "				<td align=\"right\" width=\"70\">\n";
	
	echo $c['fctramt'];

	echo "				</td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" width=\"125\"><b>Adjusted Book Price</b></td>\n";
	echo "				<td align=\"right\" width=\"70\">\n";
	
	echo $c['adjbook'];

	echo "				</td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td align=\"right\" width=\"125\"><b>Over/Under Amt</b></td>\n";
	echo "				<td align=\"right\" width=\"70\">\n";
	
	if ($c['oubook'] < 0)
	{
		echo "<font color=\"red\">".$c['oubook']."</font>";
	}
	else
	{
		echo $c['oubook'];
	}

	echo "				</td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "      		<td align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "			</tr>\n";

	if (isset($_REQUEST['csched']) && is_array($_REQUEST['csched']))
	{
		$btrap=0;
		$btrap2=0;
		foreach ($_REQUEST['csched'] as $cn => $cv)
		{
			if (isset($cv['catid']) && $cv['catid']==1)
			{
				if (isset($cv['contrsrc']) and $cv['contrsrc']=='book')
				{
					$srcamt=$c['adjbook'];
				}
				else
				{
					$srcamt=$c['fctramt'];
				}
				
				//$tcomm=$tcomm+$cv['rwdamt'];
				$tcomm=$tcomm + ($srcamt * $cv['rwdrate']);
				echo "			<tr>\n";
				echo "              <td align=\"right\"><b>Base Comm</b></td>\n";
				echo "              <td align=\"right\">\n";
				
				echo number_format(($srcamt * $cv['rwdrate']), 2, '.', '');
				
				echo "              	<input type=\"hidden\" id=\"ouo1\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($srcamt * $cv['rwdrate']), 2, '.', '')."\">\n";
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				echo "					<input type=\"hidden\" id=\"c_amt\" name=\"c_amt\" value=\"".$srcamt."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\"  value=\"".($cv['rwdrate'] * 100)."\">\n";
				
				echo ($cv['rwdrate'] * 100).' %';
				
				echo "				</td>\n";
				echo "      		<td><img src=\"images/pixel.gif\"></td>\n";
				echo "           </tr>\n";
			}
			
			if (isset($cv['catid']) && $cv['catid']==2)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>Over/<font color=\"red\">Under</font> Comm</b></td>\n";
				echo "              <td align=\"right\">\n";
				
				echo number_format(($c['oubook'] * $cv['rwdrate']), 2, '.', '');
				
				echo "              	<input type=\"hidden\" id=\"ouo2\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['oubook'] * $cv['rwdrate']), 2, '.', '')."\">\n";
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				echo "					<input type=\"hidden\" id=\"oubook\" name=\"oubook\"  value=\"".$c['oubook']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";			
				
				echo ($cv['rwdrate'] * 100).' %';
				
				echo "				</td>\n";
				echo "      		<td><img src=\"images/pixel.gif\"></td>\n";
				echo "           </tr>\n";
			}
			
			/* Moved to CommissionScheduleRO_GMSM
			if (isset($cv['catid']) && $cv['catid']==4) // Sales Manager Comm
			{
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				
				if (isset($c['oubook']) && $c['oubook'] > 0)
				{
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['fctramt'] * $cv['rwdrate']), 2, '.', '')."\">\n";
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				}
				else
				{
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format(($c['fctramt'] * ($cv['rwdrate']/2)), 2, '.', '')."\">\n";
					echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".(($cv['rwdrate']/2) * 100)."\">\n";
				}
			}
			*/
			
			if (isset($cv['catid']) && $cv['catid'] == 6)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				
				if (isset($cv['tbullets']) and $cv['tbullets'] > 0)
				{
					$tbullets=$cv['tbullets'];
				}
				else
				{
					$tbullets='';
				}
				
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>".$tbullets." SmartFeature Bonus</b></td>\n";
				echo "              <td align=\"right\">\n";
				
				if (($cv['rwdamt']) < 0)
				{
					echo "					<font color=\"red\"><div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div></font>\n";
				}
				else
				{
					echo "					<div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div>\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($cv['rwdamt'], 2, '.', '')."\">\n";
				
				if ($cv['rwdrate'] < 0)
				{
					echo "              <font color=\"red\">".($cv['rwdrate'] * 100)."%</font>\n";
				}
				else
				{
					echo "              ".($cv['rwdrate'] * 100)."%\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\"></td>\n";
				echo "           </tr>\n";
				$btrap++;
			}
			
			if (isset($cv['catid']) && $cv['catid'] == 9)
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>Merit Bonus</b></td>\n";
				echo "              <td align=\"right\">\n";
				
				if (($cv['rwdamt']) < 0)
				{
					echo "					<font color=\"red\"><div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div></font>\n";
				}
				else
				{
					echo "					<div id=\"ouo6\">".number_format($cv['rwdamt'], 2, '.', '')."</div>\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".($cv['rwdrate'] * 100)."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($cv['rwdamt'], 2, '.', '')."\">\n";
				
				if ($cv['rwdrate'] < 0)
				{
					echo "              <font color=\"red\">".($cv['rwdrate'] * 100)."%</font>\n";
				}
				else
				{
					echo "              ".($cv['rwdrate'] * 100)."%\n";
				}
				
				echo "				</td>\n";
				echo "              <td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "           </tr>\n";
			}
			
			if (isset($cv['catid']) && $cv['catid']==8)// Override
			{
				$tcomm=$tcomm+$cv['rwdamt'];
				echo "           <tr>\n";
				echo "              <td align=\"right\"><b>Override</b></td>\n";
				echo "              <td align=\"right\">\n";
				echo number_format($cv['rwdamt'], 2, '.', '');
				echo "				</td>\n";
				echo "              <td align=\"left\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][label]\"  value=\"".$cv['label']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][notes]\"	value=\"\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][catid]\"  value=\"".$cv['catid']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][ctype]\"  value=\"".$cv['ctype']."\">\n";
				echo "					<input type=\"hidden\" name=\"csched[".$cv['cmid']."][uid]\" 	value=\"".md5(session_id().time().$c['estid']).".".$_SESSION['securityid']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdrate]\" value=\"".$cv['rwdamt']."\">\n";
				echo "              	<input type=\"hidden\" name=\"csched[".$cv['cmid']."][rwdamt]\" value=\"".number_format($cv['rwdamt'], 2, '.', '')."\">\n";
				echo "				</td>\n";
				echo "              <td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
				echo "           </tr>\n";
				$btrap2++;
			}
		}
	}
	
	echo "			<tr>\n";
	echo "              <td align=\"center\" colspan=\"4\"><hr width=\"90%\"></td>\n";	
	echo "			</tr>\n";
	echo "           <tr>\n";
	echo "              <td align=\"right\"><b>Total Comm</b></td>\n";
	echo "              <td align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\">".number_format($tcomm, 2, '.', '')."</font>\n";
	}
	else
	{
		echo "					".number_format($tcomm, 2, '.', '')."\n";
	}
	
	echo "				</td>\n";
	echo "              <td colspan=\"2\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	echo "			</table>\n";
	//echo "								</td>\n";	
	//echo "   						</tr>\n";
	//echo "   					</table>\n";
}

function PaymentScheduleRW($camt,$ps1,$ps2)
{
	echo "				<table align=\"center\">\n";
	echo "					<tr>\n";
	echo "						<td align=\"right\" width=\"125\"><b>Contract Amount</b></td>\n";
	echo "						<td align=\"right\"><div id=\"psContractAmt\">".number_format($camt, 2, '.', '')."</div></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "					</tr>\n";
	echo "					<tr>\n";
	echo "						<td colspan=\"6\" align=\"center\"><hr width=\"90%\"></td>\n";
	echo "					</tr>\n";

	if ($ps1!="0" && $ps2!="0")
	{
		echo "					<tr>\n";
		echo "						<td align=\"right\" width=\"125\"><b>Down Payment</b></td>\n";
		echo "						<td align=\"right\">\n";
		echo "							<input class=\"brdrtxtrght\" id=\"amt_501L\" name=\"payschedule[501L][amt]\" type=\"text\" value=\"0.00\" size=\"7\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "							<input id=\"id_501L\" name=\"payschedule[501L][phsid]\" type=\"hidden\" value=\"1\">\n";
		echo "							<input id=\"amt_501Lorig\" type=\"hidden\" value=\"0.00\">\n";
		echo "							<input id=\"ps_calc\" type=\"hidden\" value=\"1\">\n";
		echo "						</td>\n";
		echo "						<td align=\"right\">\n";
		echo "							<input class=\"bboxbc\" id=\"per_s1\" name=\"payschedule[501L][perc]\" type=\"text\" value=\"0\" size=\"1\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "							<input id=\"per_s1orig\" type=\"hidden\" value=\"0\">\n";
		echo "						</td>\n";
		echo "						<td>%</td>\n";
		echo "						<td>\n";
		echo "							<table cellsacing=0 cellpadding=0>\n";
		echo "								<tr>\n";
		echo "									<td align=\"center\">\n";
		echo "										<img id=\"PSDWNinc\" src=\"images/arrow_top.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('per_s1','camt','amt_501L','inc'); ElemValueChange('ps_calc',0);\" title=\"Adjust Up\">\n";
		echo "									</td>\n";
		echo "									<td align=\"center\">\n";
		echo "										<img id=\"PSDWNdec\" src=\"images/arrow_down.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('per_s1','camt','amt_501L','dec'); ElemValueChange('ps_calc',0);\" title=\"Adjust Down\">\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</td>\n";
		echo "						<td>\n";
		echo "							<div title=\"Calculate\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"
														calcAmtToPercPS('amt_501L','camt','per_s1','V');
														calcPercToAmtPS('per_p1','camt','amt_501L','amt_531L','per_a1','V');
														calcPercToAmtPS('per_p2','camt','amt_501L','amt_531L','per_a2','V');
														calcPercToAmtPS('per_p3','camt','amt_501L','amt_531L','per_a3','V');
														calcPercToAmtPS('per_p4','camt','amt_501L','amt_531L','per_a4','V');
														calcAmtToPercPS('amt_531L','camt','per_s2','V');
														ElemValueChange('ps_calc',1);
													\">
											<a href=\"#\"><img id=\"psCalculate\" class=\"JMStooltip\" src=\"images/calculator.png\" title=\"Click to Calculate Estimated Pay Schedule\"></a>
										</div>\n";
		
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<td align=\"right\" width=\"125\"><b>Secondary Payee</b></td>\n";
		echo "						<td align=\"right\">\n";
		echo "							<input class=\"brdrtxtrght\" id=\"amt_531L\" name=\"payschedule[531L][amt]\" type=\"text\" value=\"0.00\" size=\"7\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "							<input id=\"id_531L\" name=\"payschedule[531L][phsid]\" type=\"hidden\" value=\"58\">\n";
		echo "						</td>\n";
		echo "						<td align=\"right\">\n";
		echo "							<input class=\"bboxbc\" id=\"per_s2\" name=\"payschedule[531L][perc]\" type=\"text\" value=\"0\" size=\"1\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "						</td>\n";
		echo "						<td>%</td>\n";
		echo "						<td>\n";
		echo "							<table cellsacing=0 cellpadding=0>\n";
		echo "								<tr>\n";
		echo "									<td align=\"center\">\n";
		echo "										<img id=\"PSSECinc\" src=\"images/arrow_top.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('per_s2','camt','amt_531L','inc'); ElemValueChange('ps_calc',0);\" title=\"Adjust Up\">\n";
		echo "									</td>\n";
		echo "									<td align=\"center\">\n";
		echo "										<img id=\"PSSECdec\" src=\"images/arrow_down.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('per_s2','camt','amt_531L','dec'); ElemValueChange('ps_calc',0);\" title=\"Adjust Down\">\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</td>\n";
		echo "						<td><span id=\"psCalcNotice\">Calculated</span></td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<td colspan=\"6\" align=\"center\"><hr width=\"90%\"></td>\n";
		echo "					</tr>\n";
		
		$phsar=explode(",",$ps1);
		$perar=explode(",",$ps2);

		if (count($phsar)==count($perar))
		{
			$pperp=1;
			foreach ($phsar as $an => $pc)
			{
				$qryZ = "SELECT phscode,phsname,extphsname,phsid FROM phasebase WHERE phscode='".$pc."';";
				$resZ = mssql_query($qryZ);
				$rowZ = mssql_fetch_array($resZ);

				$paymnt	=($camt - 0) * $perar[$an];
				$fpaymnt=number_format($paymnt, 2, '.', '');
				$fperc	=$perar[$an]*100;
				
				echo "					<tr>\n";
				echo "						<td align=\"right\" width=\"125\"><b>".$rowZ['phsname']."</b></td>\n";
				echo "						<td align=\"right\">\n";
				echo "							<input class=\"brdrtxtrght\" id=\"per_a".$pperp."\" name=\"payschedule[".$rowZ['phscode']."][amt]\" type=\"text\" value=\"".$fpaymnt."\" size=\"7\">\n";
				echo "						</td>\n";
				echo "						<td align=\"right\">".$fperc."</td>\n";
				echo "						<td>%</td>\n";
				echo "							<input id=\"per_p".$pperp."\" name=\"payschedule[".$rowZ['phscode']."][perc]\" type=\"hidden\" value=\"".$fperc."\">\n";
				echo "							<input id=\"per_i".$pperp."\" name=\"payschedule[".$rowZ['phscode']."][phsid]\" type=\"hidden\" value=\"".$rowZ['phsid']."\">\n";
				echo "						<td><img src=\"images/pixel.gif\"></td>\n";
				echo "						<td><img src=\"images/pixel.gif\"></td>\n";
				echo "					</tr>\n";
				$pperp++;
			}
		}
	}
	
	echo "				</table>\n";	
}

function PaymentScheduleRW_TED($camt,$ps1,$ps2)
{
	$phsar=explode(",",$ps1);
	$perar=explode(",",$ps2);
	
	echo "				<table align=\"center\">\n";
	echo "					<tr>\n";
	echo "						<td align=\"right\" width=\"125\"><b>Contract Amount</b></td>\n";
	echo "						<td align=\"right\"><div id=\"psContractAmt\">".number_format($camt, 2, '.', '')."</div></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "					</tr>\n";
	echo "					<tr>\n";
	echo "						<td colspan=\"6\" align=\"center\"><hr width=\"90%\"></td>\n";
	echo "					</tr>\n";

	if ($ps1!="0" && $ps2!="0")
	{
		echo "					<tr>\n";
		echo "						<td align=\"right\" width=\"125\"><b>Down Payment</b></td>\n";
		echo "						<td align=\"right\">\n";
		echo "							<input class=\"brdrtxtrght PSItemAmount\" id=\"amt_501L\" name=\"payschedule[501L][amt]\" type=\"text\" value=\"0.00\" size=\"7\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "							<input id=\"amt_501Lorig\" type=\"hidden\" value=\"0.00\">\n";
		echo "							<input id=\"ps_calc\" type=\"hidden\" value=\"1\">\n";
		echo "						</td>\n";
		echo "						<td align=\"right\">\n";
		echo "							<input class=\"bboxbc\" id=\"per_s1\" name=\"payschedule[501L][perc]\" type=\"text\" value=\"0\" size=\"1\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "							<input id=\"per_s1orig\" type=\"hidden\" value=\"0\">\n";
		echo "						</td>\n";
		echo "						<td>%</td>\n";
		echo "						<td>\n";
		echo "							<table cellsacing=0 cellpadding=0>\n";
		echo "								<tr>\n";
		echo "									<td align=\"center\">\n";
		echo "										<img id=\"PSDWNinc\" src=\"images/arrow_top.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('per_s1','camt','amt_501L','inc'); ElemValueChange('ps_calc',0);\" title=\"Adjust Up\">\n";
		echo "									</td>\n";
		echo "									<td align=\"center\">\n";
		echo "										<img id=\"PSDWNdec\" src=\"images/arrow_down.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('per_s1','camt','amt_501L','dec'); ElemValueChange('ps_calc',0);\" title=\"Adjust Down\">\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</td>\n";
		echo "						<td>\n";
		echo "							<div title=\"Calculate\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"
														calcAmtToPercPS('amt_501L','camt','per_s1','V');
														calcPercToAmtPS('per_p1','camt','amt_501L','amt_531L','per_a1','V');
														calcPercToAmtPS('per_p2','camt','amt_501L','amt_531L','per_a2','V');
														calcPercToAmtPS('per_p3','camt','amt_501L','amt_531L','per_a3','V');
														calcPercToAmtPS('per_p4','camt','amt_501L','amt_531L','per_a4','V');
														calcAmtToPercPS('amt_531L','camt','per_s2','V');
														ElemValueChange('ps_calc',1);
													\">
											<a href=\"#\"><img id=\"psCalculate\" class=\"JMStooltip\" src=\"images/calculator.png\" title=\"Click to Calculate Estimated Pay Schedule\"></a>
										</div>\n";
		
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<td align=\"right\" width=\"125\"><b>Secondary Payee</b></td>\n";
		echo "						<td align=\"right\">\n";
		echo "							<input class=\"brdrtxtrght PSItemAmount\" id=\"amt_531L\" name=\"payschedule[531L][amt]\" type=\"text\" value=\"0.00\" size=\"7\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "						</td>\n";
		echo "						<td align=\"right\">\n";
		echo "							<input class=\"bboxbc\" id=\"per_s2\" name=\"payschedule[531L][perc]\" type=\"text\" value=\"0\" size=\"1\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "						</td>\n";
		echo "						<td>%</td>\n";
		echo "						<td>\n";
		echo "							<table cellsacing=0 cellpadding=0>\n";
		echo "								<tr>\n";
		echo "									<td align=\"center\">\n";
		echo "										<img id=\"PSSECinc\" src=\"images/arrow_top.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('per_s2','camt','amt_531L','inc'); ElemValueChange('ps_calc',0);\" title=\"Adjust Up\">\n";
		echo "									</td>\n";
		echo "									<td align=\"center\">\n";
		echo "										<img id=\"PSSECdec\" src=\"images/arrow_down.gif\" onMouseOver=\"this.style.cursor='pointer'\" onClick=\"incPerc('per_s2','camt','amt_531L','dec'); ElemValueChange('ps_calc',0);\" title=\"Adjust Down\">\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</td>\n";
		echo "						<td></td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "						<td colspan=\"6\" align=\"center\"><hr width=\"90%\"></td>\n";
		echo "					</tr>\n";

		if (count($phsar)==count($perar))
		{
			$pperp=1;
			foreach ($phsar as $an => $pc)
			{
				$qryZ = "SELECT phscode,phsname,extphsname FROM phasebase WHERE phscode='".$pc."';";
				$resZ = mssql_query($qryZ);
				$rowZ = mssql_fetch_array($resZ);

				$paymnt	=($camt - 0) * $perar[$an];
				$fpaymnt=number_format($paymnt, 2, '.', '');
				$fperc	=$perar[$an]*100;
				
				echo "					<tr class=\"PSItemTableRow\">\n";
				echo "						<td align=\"right\" width=\"125\"><b>".$rowZ['phsname']."</b></td>\n";
				echo "						<td align=\"right\">\n";
				echo "							<input class=\"brdrtxtrght PSItemAmount\" id=\"per_a".$pperp."\" name=\"payschedule[".$rowZ['phscode']."][amt]\" type=\"text\" value=\"".$fpaymnt."\" size=\"7\">\n";
				echo "						</td>\n";
				echo "						<td align=\"right\">".$fperc."</td>\n";
				echo "						<td>%</td>\n";
				echo "							<input id=\"per_p".$pperp."\" name=\"payschedule[".$rowZ['phscode']."][perc]\" type=\"hidden\" value=\"".$fperc."\">\n";
				echo "						<td><a href=\"#\"><img class=\"removePSitem\" src=\"images/delete.png\"></a></td>\n";
				echo "						<td><img src=\"images/pixel.gif\"></td>\n";
				echo "					</tr>\n";
				$pperp++;
			}
		}
	}
	
	echo "					<tr>\n";
	echo "						<td colspan=\"6\" align=\"center\"><hr width=\"90%\"></td>\n";
	echo "					</tr>\n";
	echo "					<tr>\n";
	echo "						<td align=\"right\" width=\"125\"><b>Total</b></td>\n";
	echo "						<td align=\"right\">\n";
	echo "							<input class=\"brdrtxtrght\" id=\"psTOTAL\" type=\"text\" value=\"".number_format($camt, 2, '.', '')."\" size=\"7\">\n";
	echo "						</td>\n";
	echo "						<td colspan=\"3\" align=\"center\"><span id=\"psCalcNotice\">Calculated!</span></td>\n";
	echo "						<td><img src=\"images/pixel.gif\"></td>\n";
	echo "					</tr>\n";
	echo "				</table>\n";	
}

function PaymentScheduleRW_NEW($camt,$ps1,$ps2)
{
	//echo 'NEW';
	
	$qryA = "SELECT phsid,phscode,phsname,extphsname,seqnum,payschedselect FROM phasebase WHERE phstype='V' and payschedselect=1;";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	echo "						<label class=\"pay_sched\"><b>Contract Amount</b></label>\n";
	echo "						<div class=\"pay_sched\">\n";
	echo "							<div id=\"ctramt\">".number_format($camt, 2, '.', '')."</div>\n";
	echo "						</div>\n";
	echo "						<hr width=\"100%\">\n";

	if ($ps1!="0" && $ps2!="0")
	{
		echo "						<label class=\"pay_sched\"><b>Down Payment</b></label>\n";
		echo "						<div class=\"pay_sched\">\n";
		echo "							<input class=\"bboxbr\" id=\"amt_501L\" name=\"payschedule[501L][amt]\" type=\"text\" value=\"0.00\" size=\"7\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "							<input id=\"amt_501Lorig\" type=\"hidden\" value=\"0.00\">\n";
		echo "							<input id=\"ps_calc\" type=\"hidden\" value=\"0\">\n";
		echo "							<input class=\"bboxbc\" id=\"per_s1\" name=\"payschedule[501L][perc]\" type=\"text\" value=\"0\" size=\"1\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "							<input id=\"per_s1orig\" type=\"hidden\" value=\"0\">\n";										
		echo "							<img id=\"CalcPS\" src=\"images/calculator.png\" title=\"Calculate\">\n";
		echo "						</div>\n";
		echo "						<label class=\"pay_sched\"><b>Secondary Payee</b></label>\n";
		echo "						<div class=\"pay_sched\">\n";
		echo "							<input class=\"bboxbr\" id=\"amt_531L\" name=\"payschedule[531L][amt]\" type=\"text\" value=\"0.00\" size=\"7\" onChange=\"ElemValueChange('ps_calc',0);\">\n";
		echo "							<input class=\"bboxbc\" id=\"per_s2\" name=\"payschedule[531L][perc]\" type=\"text\" value=\"0\" size=\"1\" onChange=\"ElemValueChange('ps_calc',0);\"> %\n";
		echo "						</div>\n";
		echo "						<hr width=\"100%\">\n";
		
		if ($nrowA > 0)
		{
			echo "						<div class=\"pay_sched\">\n";
			echo "			<select id=\"phssel\">\n";
			echo "				<option value=\"0\">Add Payment...</option>\n";
			
			while ($rowA = mssql_fetch_array($resA))
			{
				echo "<option value=\"".$rowA['phscode']."\">".$rowA['phsname']."</option>\n";
			}
			
			echo "			</select>\n";
			echo "			<img id=\"phsadd\"src=\"images/add.png\">\n";
			echo "						</div>\n";
		}
		
		echo "								<span class=\"full_sched\">\n";
		
		$phsar=explode(",",$ps1);
		$perar=explode(",",$ps2);

		if (count($phsar)==count($perar))
		{
			$pperp=1;
			foreach ($phsar as $an => $pc)
			{
				$qryZ = "SELECT phscode,phsname,extphsname FROM phasebase WHERE phscode='".$pc."';";
				$resZ = mssql_query($qryZ);
				$rowZ = mssql_fetch_array($resZ);

				$paymnt	=($camt - 0) * $perar[$an];
				$fpaymnt=number_format($paymnt, 2, '.', '');
				$fperc	=$perar[$an]*100;
				
				echo "<span id=\"".$rowZ['phscode']."\">\n";
				echo "	<label class=\"pay_sched\"><b>".$rowZ['phsname']."</b></label>\n";
				echo "	<div class=\"pay_sched\">\n";
				echo "		<input class=\"ps_phs_amt\" id=\"per_a\" name=\"payschedule[".$rowZ['phscode']."][amt]\" type=\"text\" value=\"".$fpaymnt."\" size=\"7\"> ".$fperc."%\n";
				echo "		<input class=\"ps_phs_per\" id=\"per_p\" name=\"payschedule[".$rowZ['phscode']."][perc]\" type=\"hidden\" value=\"".$fperc."\">\n";
				echo "		<img class=\"del_item\" src=\"images/delete.png\">\n";
				echo "	</div>\n";
				echo "</span>\n";
				
				$pperp++;
			}
		}
	}
	
	echo "										</span><br>\n";

	echo "										<label class=\"pay_sched\"><b>Total Pay Schedule</b></label>\n";
	echo "										<div class=\"pay_sched\"><span class=\"total_PS\"></span></div>\n";
	echo "									</span>\n";
	//echo "								<label class=\"pay_sched\">Clear Schedule</label><div class=\"pay_sched\"><img id=\"delete_sched\" src=\"images/delete.png\"></div><br />\n";
}

function listest()
{
	//echo "TEST<BR>";
	$officeid=$_SESSION['officeid'];
	$securityid=$_SESSION['securityid'];
	$acclist=explode(",",$_SESSION['aid']);

	if (isset($_REQUEST['order']))
	{
		$order=$_REQUEST['order'];
	}
	else
	{
		$order="estid";
	}

	if (isset($_REQUEST['ascdesc']))
	{
		$dir=$_REQUEST['ascdesc'];
	}
	else
	{
		$dir="ASC";
	}
	
	if (isset($_REQUEST['etype']) && $_REQUEST['etype']=='E')
	{
		$etype='Estimates';
	}
	else
	{
		$etype='Quotes';
	}

	if ($_REQUEST['call']=="search_results")
	{
		if ($_REQUEST['subq']=="salesman")
		{
			$qry   = "SELECT ";
			$qry   .= "a.estid AS aestid, ";
			$qry   .= "b.securityid AS asec,";
			$qry   .= "a.cid AS acid,";
			$qry   .= "a.contractamt AS acontr,";
			$qry   .= "a.added AS aadd,";
			$qry   .= "a.updated AS aup,";
			$qry   .= "a.submitted AS asub, ";
			$qry   .= "b.cfname AS bcfname, ";
			$qry   .= "b.clname AS bclname, ";
			$qry   .= "b.chome AS bchome, ";
			$qry   .= "b.custid AS bcustid, ";
			$qry   .= "b.estid AS bestid, ";
			$qry   .= "a.renov AS renov, ";
			$qry   .= "a.esttype AS esttype ";
			$qry  .= "FROM [est] AS a ";
			$qry  .= "INNER JOIN [cinfo] AS b ";
			$qry  .= "ON a.estid=b.estid ";
			$qry  .= "AND a.officeid=b.officeid ";
			$qry  .= "WHERE b.officeid='".$_SESSION['officeid']."' ";
			$qry  .= "AND b.jobid='0' ";
			$qry  .= "AND b.njobid='0' ";
			$qry  .= "AND b.securityid='".$_REQUEST['assigned']."' ";
			$qry   .="AND a.esttype = '".$_REQUEST['etype']."'  ";
			
			if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
			{
				$qry   .="AND a.renov = '".$_REQUEST['renov']."'  ";
			}

			$qry  .= "ORDER BY ".$order." ".$dir.";";
		}
		elseif ($_REQUEST['subq']=="last_name")
		{
			if (empty($_REQUEST['sval']))
			{
				echo "<b><font color=\"red\">Error!</font> Search String required.</b>";
				exit;
			}

			$qry   = "SELECT ";
			$qry   .= "a.estid AS aestid, ";
			$qry   .= "b.securityid AS asec,";
			$qry   .= "a.cid AS acid,";
			$qry   .= "a.contractamt AS acontr,";
			$qry   .= "a.added AS aadd,";
			$qry   .= "a.updated AS aup,";
			$qry   .= "a.submitted AS asub, ";
			$qry   .= "b.cfname AS bcfname, ";
			$qry   .= "b.clname AS bclname, ";
			$qry   .= "b.chome AS bchome, ";
			$qry   .= "b.custid AS bcustid, ";
			$qry   .= "b.estid AS bestid, ";
			$qry   .= "a.renov AS renov, ";
			$qry   .= "a.esttype AS esttype ";
			$qry  .= "FROM [est] AS a ";
			$qry  .= "INNER JOIN [cinfo] AS b ";
			$qry  .= "ON a.estid=b.estid ";
			$qry  .= "AND a.officeid=b.officeid ";
			$qry  .= "WHERE b.officeid='".$_SESSION['officeid']."' ";
			$qry  .= "AND b.jobid='0' ";
			$qry  .= "AND b.njobid='0' ";
			$qry  .= "AND b.clname LIKE '".$_REQUEST['sval']."%' ";
			$qry   .="AND a.esttype = '".$_REQUEST['etype']."'  ";
			
			if (!empty($_REQUEST['renov']) && $_REQUEST['renov']==1)
			{
				$qry   .="AND a.renov = '".$_REQUEST['renov']."'  ";
			}
			
			$qry  .= "ORDER BY ".$order." ".$dir.";";
		}
	}

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);
	
	/*if ($_SESSION['securityid']==26)
	{
		echo $qry."<br>";
	}*/
	
	if ($nrows < 1)
	{
		echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "   <tr>\n";
		echo "   <form method=\"post\">\n";
		echo "   <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "   <input type=\"hidden\" name=\"call\" value=\"new\">\n";
		echo "      <td class=\"gray\" align=\"center\">\n";

		if ($_REQUEST['call']=="search_results")
		{
			echo "         <h4><b>".$etype." Search did not produce any Results!</h4>";
		}
		else
		{
			echo "         <h4>No ".$etype."  on File!</h4>";
		}

		echo "      </td>\n";
		echo "   </form>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		//print_r($acclist);
		echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\">\n";
		echo "         <table width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\">\n";
		echo "                     <tr>\n";
		echo "                        <td align=\"left\" class=\"gray\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "                     </tr>\n";
		echo "                   </table>\n";
		echo "                </td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "                  <table width=\"100%\" bgcolor=\"white\">\n";
		echo "                  <tr>\n";
		echo "                     <td class=\"gray_und\" align=\"center\"><b></b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"center\"><b>#</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"center\"><b>Renov</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"left\"><b>Customer</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"center\"><b>Phone</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"center\"><b>Contract Amt</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"left\"><b>SalesRep</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"center\"><b>Created</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"center\"><b>Updated</b></td>\n";
		echo "                     <td class=\"gray_und\" align=\"left\"></td>\n";
		echo "                     <td class=\"gray_und\" align=\"center\"><b>#</b></td>\n";
		echo "                  </tr>\n";

		$tcon=0;
		$xi=0;
		while($row=mssql_fetch_array($res))
		{
			$xi++;
			
			if ($xi%2)
			{
				$tbg = "ltgray_und";
			}
			else
			{
				$tbg = "wh_und";
			}
			//$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row['acid']."'";
			//$resB = mssql_query($qryB);
			//$rowB = mssql_fetch_array($resB);
			//echo $qryB."<br>";

			$qryC = "SELECT fname,lname,securityid,sidm,slevel FROM security WHERE securityid='".$row['asec']."'";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			$secl=explode(",",$rowC['slevel']);

			if ($secl[6]==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}

			if (in_array($row['asec'],$acclist)||$_SESSION['jlev'] >= 6)
			{
				$tcon			=$tcon+$row['acontr'];
				$fconamt		=number_format($row['acontr'], 2, '.', ',');

				if (isset($row['aadd']))
				{
					$odate = date("m-d-Y", strtotime($row['aadd']));
				}
				else
				{
					$odate = "";
				}

				if (isset($row['aup']))
				{
					$udate = date("m-d-Y", strtotime($row['aup']));
				}
				else
				{
					$udate = "";
				}

				if (isset($row['asub']))
				{
					$sdate = date("m-d-Y", strtotime($row['asub']));
				}
				else
				{
					$sdate = "";
				}
				
				if ($row['renov']==1)
				{
					$renov="R";
				}
				else
				{
					$renov="";
				}

				echo "                  <tr>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi.".</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$row['aestid']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\"><b>".$renov."</b></td>\n";	
				echo "                     <td class=\"".$tbg."\" align=\"left\"><b>".$row['bclname']."</b>, ".$row['bcfname']."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\">";
                //echo $row['bchome'];
                
                echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($row['bchome'])));
                echo "                      </td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$fconamt."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"left\"><font class=\"".$fstyle."\">".$rowC['lname'].", ".$rowC['fname']."</font></td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">".$odate."</td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">".$udate."</td>\n";
				/*echo "                        <form method=\"POST\">\n";
				echo "                     <td class=\"".$tbg."\" align=\"center\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"cview\">\n";
				echo "                           <input type=\"hidden\" name=\"cid\" value=\"".$row['bcustid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";
				echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Customer Info\">\n";
				echo "                     </td>\n";
				echo "                        </form>\n";*/
				echo "                     <td class=\"".$tbg."\" align=\"center\">\n";
				echo "                        <form amethod=\"POST\">\n";
				echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$row['aestid']."\">\n";
				echo "                           <input type=\"hidden\" name=\"esttype\" value=\"".$row['esttype']."\">\n";
				
				if ($row['esttype']=='E')
				{
                    echo "                          <input class=\"transnb JMStooltip\" type=\"image\" src=\"images/application_form_magnify.png\" title=\"Open Estimate\">\n";
					//echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Estimate\">\n";
				}
				else
				{
                    echo "                          <input class=\"transnb JMStooltip\" type=\"image\" src=\"images/application_form_magnify.png\" title=\"Open Quote\">\n";
					echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Quote\">\n";
				}
				
				echo "                        </form>\n";
				echo "                     </td>\n";
				echo "                     <td class=\"".$tbg."\" align=\"right\">".$xi."</td>\n";
				echo "                  </tr>\n";
			}
		}

		$ftcon        =number_format($tcon, 2, '.', ',');

		echo "                  <tr>\n";
		echo "                     <td class=\"gray\" align=\"right\" colspan=\"5\"><b>Total Estimates</b></td>\n";
		echo "                     <td class=\"gray\" align=\"right\"><b>".$ftcon."</b></td>\n";
		echo "                     <td class=\"gray\" align=\"left\" colspan=\"5\"></td>\n";
		echo "                  </tr>\n";
		echo "                  </table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

?>