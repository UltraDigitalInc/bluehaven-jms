<?php

function get_defaultcomms()
{
	$out=array();
	
	$qryCB = "select * from jest..CommissionBuilder where oid='".$_SESSION['officeid']."' and active=1;";
	$resCB = mssql_query($qryCB);
    $nrowCB = mssql_num_rows($resCB);
    
    if ($nrowCB > 0)
    {
		while ($rowCB = mssql_fetch_array($resCB))
		{
			// Array structure (commcategory:buildtype:commdetails)
			$out['profiles'][$rowCB['ctgry']][$rowCB['renov']][$rowCB['cmid']]=$rowCB;
		}
    }

	return $out;
}

function cbcalc($cinar)
{
    error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	$tcomm=0;
    $out		=array();
	$commcat_ar	=array();
	$comar		=array();
	$grpcomar	=array();
	$tiercomar	=array();
	
	$cb = get_defaultcomms($_SESSION['officeid']);
	
	echo '<table><tr><td><pre>';
	print_r($cinar);
	//print_r($cb);
    //print_r($cb['profiles']);
    echo '</pre></td></tr></table>';
	
	//exit;
	
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

	if (isset($cb['profiles'][1][0]) and count($cb['profiles'][1][0]) > 0)
	{
		//echo 'Base';
	}

	exit;
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

	if ($_SESSION['securityid']==26)
	{
        echo __FUNCTION__.'<br>';
		echo "<pre>";
		print_r($cinar);
		echo "</pre>";
        echo '<br>';
        echo "<pre>";
		print_r($out);
		echo "</pre>";
	}
    
    return $out;
}


?>