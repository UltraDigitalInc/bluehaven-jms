<?php

function show_array_vars($array)
{
	if (is_array($array))
	{
		echo "<table>\n";
		foreach ($array as $n=>$v)
		{
			echo "<tr>\n";
			
			echo "	<td valign=\"top\">array: $n=$v</td>";
			if (is_array($v))
			{
				echo "<td>\n";
				echo "	<table>\n";
				foreach ($v as $subn=>$subv)
				{
					echo "		<tr>\n";
					echo "			<td valign=\"top\">$subn = $subv</td>\n";
					if (is_array($subv))
					{
						echo "			<td>\n";
						echo "				<table>\n";
						
						foreach ($subv as $ssubn=>$ssubv)
						{
							echo "			<tr>\n";
							echo "				<td valign=\"top\">$ssubn = $ssubv</td>\n";
                            
                            if (is_array($ssubv))
                            {
                                echo "			<td>\n";
                                echo "				<table>\n";
                                
                                foreach ($ssubv as $sssubn=>$sssubv)
                                {
                                    echo "			<tr>\n";
                                    echo "				<td valign=\"top\">$sssubn = $sssubv</td>\n";
                                    echo "			<tr>\n";
                                }
                                
                                echo "			</table>\n";
                                echo "		</td>\n";
                            }
                            
							echo "			<tr>\n";
						}
						
						echo "			</table>\n";
						echo "		</td>\n";
					}
					echo "		</tr>\n";
				}
				echo "	</table>\n";
				echo "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}

function multidimensional_array_diff($a1,$a2) 
{
    $r = array();
    foreach ($a2 as $key => $second)
    {
        foreach ($a1 as $key => $first)
        {
            if (isset($a2[$key])) 
            { 
                foreach ($first as $first_value) 
                { 
                    foreach ($second as $second_value) 
                    { 
                        if ($first_value == $second_value) 
                        { 
                            $true = true; 
                            break;    
                        } 
                    } 
                    if (!isset($true)) 
                    { 
                        
                        $r[$key][] = $first_value; 
                    } 
                    unset($true); 
                } 
            } 
            else 
            { 
                $r[$key] = $first; 
            } 
        } 
    } 
    return $r;
} 

function proc_prior_jobcost($oid,$jobid,$jadd,$reset)
{
    $data_out=array();
    $v_ar=array();
    
    $qry0 = "SELECT officeid,pft_sqft,stax,finan_from,pb_code,enquickbooks FROM offices WHERE officeid=".$oid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    
    $qry1 = "SELECT officeid,estid,custid,jobid FROM jobs WHERE officeid=".$oid." AND jobid='".$jobid."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
    
    $qry2 = "
        SELECT 
            E1.*
            ,E2.estdata
        FROM 
            est AS E1
        INNER JOIN
            est_acc_ext as E2
        ON	
            E1.officeid=E2.officeid 
            and E1.estid=E2.estid 
        WHERE 
            E1.officeid=".$oid." 
            AND E1.estid=".$row1['estid']."
            AND E2.officeid=".$oid." 
            AND E2.estid=".$row1['estid']." 
    ";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
    
    $qry4 = "SELECT * FROM jdetail WHERE officeid=".$oid." AND jobid='".$row1['jobid']."' AND jadd=".$jadd.";";
    $res4 = mssql_query($qry4);
    $row4 = mssql_fetch_array($res4);
    
    $v_ar=array(
	'ps1'=>		$row4['pft'],
	'ps2'=>		$row4['sqft'],
	'spa1'=>	$row4['spa_pft'],
	'spa2'=>	$row4['spa_sqft'],
	'spa3'=>	$row4['spa_type'],
	'ps5'=>		$row4['shal'],
	'ps6'=>		$row4['mid'],
	'ps7'=>		$row4['deep']);
    
    if ($row0['pb_code']!='0')
    {
        $v_ar['MAS']   =$row0['pb_code'];
    }
    else
    {
        $v_ar['MAS']   ='';
    }
    
    $v_ar['oid']   =$oid;
    $v_ar['cid']   =$row1['custid'];
    $v_ar['estid'] =$row1['estid'];
    $v_ar['jobid'] =$row1['jobid'];
    $v_ar['jadd']  =$row4['jadd'];
    $v_ar['enqb']  =$row0['enquickbooks'];
    
    $vpr    =proc_align_pricing($v_ar,$row2['estdata']);
    
    $srv_ar =proc_lab_cost_items($v_ar,$vpr);
    $mat_ar =proc_mat_cost_items($v_ar,$vpr);
    $bid_ar =proc_bid_items($v_ar);
    $adj_ar =proc_mpa_items($v_ar);
    
    $v_ar['jc_ar']['service']          =$srv_ar['service'];
    $v_ar['jc_ar']['material']         =$mat_ar['material'];
    $v_ar['jc_ar']['inventory']        =$mat_ar['inventory'];
    $v_ar['jc_ar']['bids']             =$bid_ar['bids'];
    $v_ar['jc_ar']['adjusts']          =$adj_ar['adjusts'];
    
    $v_ar['jc_ar']['service_errors']   =$srv_ar['service_errors'];
    $v_ar['jc_ar']['material_errors']  =$mat_ar['material_errors'];
    $v_ar['jc_ar']['inventory_errors'] =$mat_ar['inventory_errors'];
    $v_ar['jc_ar']['bids_errors']      =$bid_ar['bids_errors'];
    $v_ar['jc_ar']['adjusts_errors']   =$adj_ar['adjusts_errors'];
    
    
    //echo '<pre>';
    //print_r($v_ar);
    //echo '</pre>';
    //exit;    
    
    if (
        (count($v_ar['jc_ar']['service_errors']) > 0) or
        (count($v_ar['jc_ar']['material_errors']) > 0) or
        (count($v_ar['jc_ar']['inventory_errors']) > 0) or
        (count($v_ar['jc_ar']['bids_errors']) > 0) or
        (count($v_ar['jc_ar']['adjusts_errors']) > 0)
        )
    {
        if (count($v_ar['jc_ar']['service_errors']) > 0)
        {
            echo 'Service Item Errors<br>';        
            echo '<pre>';
            print_r($v_ar['jc_ar']['service_errors']);
            echo '</pre>';
            echo '<br>';
        }
        
        if (count($v_ar['jc_ar']['material_errors']) > 0)
        {
            echo 'Material Item Errors<br>';
            echo '<pre>';
            print_r($viewarray['jc_ar']['material_errors']);
            echo '</pre>';
            echo '<br>';
        }
        
        if (count($v_ar['jc_ar']['inventory_errors']) > 0)
        {
            echo 'Inventory Item Errors<br>';
            echo '<pre>';
            print_r($viewarray['jc_ar']['inventory_errors']);
            echo '</pre>';
            echo '<br>';
        }
        
        if (count($v_ar['jc_ar']['bids_errors']) > 0)
        {
            echo 'Bid Item Errors<br>';
            echo '<pre>';
            print_r($v_ar['jc_ar']['bids_errors']);
            echo '</pre>';
            echo '<br>';
        }
        
        if (count($v_ar['jc_ar']['adjusts_errors']) > 0)
        {
            echo 'Adjust Item Errors<br>';
            echo '<pre>';
            print_r($v_ar['jc_ar']['adjusts_errors']);
            echo '</pre>';
            echo '<br>';
        }
    }
    else
    {
        //echo 'No Errors<br>';
        $data_out=proc_quickbooks_datastore($v_ar,false);
    }
    
    //echo '<pre>';
    //print_r($data_out);
    //echo '</pre>';
    return $data_out;
}

function replacequote($data)
{
	$out=preg_replace("/'/","''",$data);
	return $out;
}

function proc_getspecaccpbook($v_ar,$code,$q1,$q2)
{
    //global $viewarray;
    
	$qryA  = "SELECT bprice,lrange,hrange FROM specaccpbook WHERE officeid=".$v_ar['oid']." AND linkid='".$code."' ORDER BY hrange ASC;";
	$resA  = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);

	if ($nrowA > 0)
	{
		while ($rowA=mssql_fetch_array($resA))
		{
			if ($q1 >= $rowA['lrange'] && $q1 <= $rowA['hrange'])
			{
				$bcsub =$rowA['bprice'];
				$lrange=$rowA['lrange'];
				$hrange=$rowA['hrange'];
			}
			elseif ($q1 > $rowA['hrange'])
			{
				$bcsub =$rowA['bprice']+(($q1-$rowA['hrange'])*$q2);
				$lrange=$rowA['lrange'];
				$hrange=$rowA['hrange'];
			}
		}
		$codedet=array(0=>$bcsub,1=>$lrange,2=>$hrange);
	}
	else
	{
		$codedet=array(0=>0,1=>0,2=>0);
	}

	return $codedet;
}

function proc_countcostitems($v_ar,$data,$type)
{
	$ecnt=0;

	if (!empty($data))
	{
		$edata=explode(",",$data);
		foreach ($edata as $en1 => $ev1)
		{
			$idata=explode(":",$ev1);
			$qry = "SELECT id FROM [".$v_ar['MAS']."rclinks_".$type."] WHERE officeid=".$v_ar['oid']." AND rid='".$idata[0]."';";
			$res = mssql_query($qry);
			$nrow= mssql_num_rows($res);
			$ecnt=$ecnt+$nrow;
		}
	}
	return $ecnt;
}

function proc_align_pricing($v_ar,$estdata)
{
	$dout='';
	$est_in=explode(",",$estdata);
    
	foreach ($est_in as $in_n => $in_v)
	{
		$p      =explode(":",$in_v);
		$qry0 	="SELECT id,rp,qtype,crate,commtype FROM [".$v_ar['MAS']."acc] WHERE officeid=".$v_ar['oid']." AND id='".$p[0]."';";
		$res0 	=mssql_query($qry0);
		$row0 	=mssql_fetch_array($res0);

		if ($p[3]!=$row0['rp'] || $p[6]!=$row0['crate'])
		{
			if ($row0['qtype']==33)
			{
				$rp=$p[3];
			}
			else
			{
				$rp=$row0['rp'];
			}
			$cm=$row0['crate'];
		}
		else
		{
			$rp=$p[3];
			$cm=$p[6];
		}

		$cr=$row0['commtype'];
		$frp=number_format($rp, 2, '.', '');
		$dout=$dout.$p[0].":".$p[1].":".$p[2].":".$frp.":".$p[4].":".$cr.":".$cm.",";
	}

	$dout=preg_replace("/,\Z/","",$dout);
	return $dout;
}

function proc_lab_cost_items($v_ar,$estdata)
{
	//global $viewarray;
	$p_arout='';
	$data_out=array();
    $data_out['service']=array();
    $data_out['service_errors']=array();
	$edata=explode(",",$estdata);
	$ecnt=proc_countcostitems($v_ar,$estdata,"l");

	//print_r($viewarray);

	foreach ($edata as $en1 => $ev1)
	{
		$idata=explode(":",$ev1);

		$qry0 = "SELECT id,rid,cid FROM [".$v_ar['MAS']."rclinks_l] WHERE officeid=".$v_ar['oid']." AND rid='".$idata[0]."';";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		if ($nrow0 > 0)
		{
			while ($row0 = mssql_fetch_array($res0))
			{
				$qry1 = "SELECT * FROM [".$v_ar['MAS']."accpbook] WHERE officeid=".$v_ar['oid']." AND id='".$row0['cid']."';";
				$res1 = mssql_query($qry1);
				$row1 = mssql_fetch_array($res1);

				//echo $qry1."<br>";

				if ($row1['qtype'] >= 9 && $row1['qtype'] <= 12)
				{
					//echo $qry1."<br>";
					if ($row1['qtype'] == 9)
					{
						$cdquan=$v_ar['ps1'];
					}
					elseif ($row1['qtype'] == 10)
					{
						$cdquan=$v_ar['ps2'];
					}
					elseif ($row1['qtype'] == 11)
					{
						$cdquan=$v_ar['ia'];
					}
					elseif ($row1['qtype'] == 12)
					{
						$cdquan=$v_ar['gals'];
					}

					$code		=$row1['accid'];
					$specout	=proc_getspecaccpbook($v_ar,$code,$cdquan,$row1['quantity']);

					if ($specout[0]==0)
					{
						$fbprice=0;
						$quan	=0;
					}
					else
					{
						$fbprice=$specout[0];
					}

					$lrange	=$specout[1];
					$hrange	=$specout[2];
					$quantity  =$row1['quantity'];
				}
				elseif ($row1['qtype'] == 53) // Permit Type
				{
					$qry2  = "SELECT 		a.permit ";
					$qry2 .= "FROM 		taxrate as a ";
					$qry2 .= "INNER JOIN 	cinfo as b ";
					$qry2 .= "ON 			b.scounty=a.id ";
					$qry2 .= "WHERE 		b.officeid='".$v_ar['oid']."' ";
					$qry2 .= "AND	 		b.estid='".$v_ar['estid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					//print_r($row2)."<br>";

					$lrange	=0;
					$hrange	=0;
					$fbprice	=number_format($row2['permit'], 2, '.', '');
					//echo $qry2."<br>";
					//echo $row2['permit']."<br>";
					//echo $fbprice."<br>";
				}
				else
				{
					$lrange	=$row1['lrange'];
					$hrange	=$row1['hrange'];
					$fbprice=number_format($row1['bprice'], 2, '.', '');
				}

                if ($row1['qtype']!=33) // Process Service Items except Bids
                {
                    $data_out['service'][]=array(
                                                           'srvid'=>$row1['id'],
                                                           'oid'=>$v_ar['oid'],
                                                           'jobid'=>$v_ar['jobid'],
                                                           'jadd'=>$v_ar['jadd'],
                                                           'phsid'=>$row1['phsid'],
                                                           'code'=>$row1['accid'],
                                                           'ListID'=>$row1['ListID'],
                                                           'EditSequence'=>$row1['EditSequence'],
                                                           'itemname'=>htmlspecialchars(trim($row1['item'])),
                                                           'itemattrib1'=>htmlspecialchars(trim($row1['atrib1'])),
                                                           'itemattrib2'=>htmlspecialchars(trim($row1['atrib2'])),
                                                           'unitprice'=>$fbprice,
                                                           'totalprice'=>$fbprice,
                                                           'tquantity'=>$idata[2],
                                                           'unkparam'=>$idata[4]
                                                           );
                    
                    if (trim($row1['ListID'])==='0')
                    {
                        $data_out['service_errors'][]=array(
                                                           'srvid'=>$row1['id'],
                                                           'oid'=>$v_ar['oid'],
                                                           'jobid'=>$v_ar['jobid'],
                                                           'jadd'=>$v_ar['jadd'],
                                                           'phsid'=>$row1['phsid'],
                                                           'code'=>$row1['accid'],
                                                           'ListID'=>$row1['ListID'],
                                                           'EditSequence'=>$row1['EditSequence'],
                                                           'itemname'=>htmlspecialchars(trim($row1['item'])),
                                                           'itemattrib1'=>htmlspecialchars(trim($row1['atrib1'])),
                                                           'itemattrib2'=>htmlspecialchars(trim($row1['atrib2'])),
                                                           'unitprice'=>$fbprice,
                                                           'totalprice'=>$fbprice,
                                                           'tquantity'=>$idata[2],
                                                           'unkparam'=>$idata[4]
                                                           );
                    }
                }
			}
		}
	}
    
    if (count($data_out['service_errors']) > 0)
    {
        print_r($data_out['service_errors']);
    }
    
    return $data_out;
}

function proc_mat_cost_items($v_ar,$estdata)
{
	$qry = "SELECT estdata FROM jdetail WHERE officeid=".$v_ar['oid']." AND jobid='".$v_ar['jobid']."' AND jadd=".$v_ar['jadd'].";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$p_arout='';
	$data_out=array();
	$data_out['material']=array();
    $data_out['material_errors']=array();
    $data_out['inventory']=array();
    $data_out['inventory_errors']=array();
	$edata=explode(",",$estdata);
	$ecnt=proc_countcostitems($v_ar,$estdata,"m");
	foreach ($edata as $en1 => $ev1)
	{
		$idata=explode(":",$ev1);

		$qry0 = "SELECT id,rid,cid FROM [".$v_ar['MAS']."rclinks_m] WHERE officeid=".$v_ar['oid']." AND rid='".$idata[0]."';";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		if ($nrow0 > 0)
		{
			while ($row0 = mssql_fetch_array($res0))
			{
				$qry1 = "SELECT * FROM [".$v_ar['MAS']."inventory] WHERE officeid=".$v_ar['oid']." AND invid='".$row0['cid']."';";
				$res1 = mssql_query($qry1);
				$row1 = mssql_fetch_array($res1);

				if ($row1['matid']!=0)
				{
					$qry1a = "SELECT bp FROM material_master WHERE id='".$row1['matid']."';";
					$res1a = mssql_query($qry1a);
					$row1a = mssql_fetch_array($res1a);

					if ($row1['qtype']==56)
					{
						$bp=$row1['bprice'];
					}
					else
					{
						$bp=$row1a['bp'];
					}
				}
				else
				{
					$bp=$row1['bprice'];
				}

				$fbprice=number_format($bp, 2, '.', '');
                
				if (isset($v_ar['enqb']) and $v_ar['enqb']==1)
				{
					if ($row1['qtype']!=33)
					{
                        if ($row1['matid']==0)
                        {
                            $data_out['material'][]=array(
                                                                   'invid'=>$row1['invid'],
                                                                   'oid'=>$v_ar['oid'],
                                                                   'jobid'=>$v_ar['jobid'],
                                                                   'jadd'=>$v_ar['jadd'],
                                                                   'phsid'=>$row1['phsid'],
                                                                   'matid'=>$row1['matid'],
                                                                   'code'=>$row1['accid'],
                                                                   'vpno'=>$row1['vpno'],
                                                                   'ListID'=>$row1['ListID'],
                                                                   'EditSequence'=>$row1['EditSequence'],
                                                                   'itemname'=>htmlspecialchars(trim($row1['item'])),
                                                                   'itemattrib1'=>htmlspecialchars(trim($row1['atrib1'])),
                                                                   'itemattrib2'=>htmlspecialchars(trim($row1['atrib2'])),
                                                                   'unitprice'=>$fbprice,
                                                                   'totalprice'=>$fbprice,
                                                                   'tquantity'=>$idata[2],
                                                                   'unkparam'=>$idata[4]
                                                                   );
                        
                            if (trim($row1['ListID'])==='0')
                            {
                                $data_out['material_errors'][]=array(
                                                                       'invid'=>$row1['invid'],
                                                                       'oid'=>$v_ar['oid'],
                                                                       'jobid'=>$v_ar['jobid'],
                                                                       'jadd'=>$v_ar['jadd'],
                                                                       'phsid'=>$row1['phsid'],
                                                                       'matid'=>$row1['matid'],
                                                                       'code'=>$row1['accid'],
                                                                       'vpno'=>$row1['vpno'],
                                                                       'ListID'=>$row1['ListID'],
                                                                       'EditSequence'=>$row1['EditSequence'],
                                                                       'itemname'=>htmlspecialchars(trim($row1['item'])),
                                                                       'itemattrib1'=>htmlspecialchars(trim($row1['atrib1'])),
                                                                       'itemattrib2'=>htmlspecialchars(trim($row1['atrib2'])),
                                                                       'unitprice'=>$fbprice,
                                                                       'totalprice'=>$fbprice,
                                                                       'tquantity'=>$idata[2],
                                                                       'unkparam'=>$idata[4]
                                                                   );
                            }
                        }
                        else
                        {
                            $data_out['inventory'][]=array(
                                                                   'invid'=>$row1['invid'],
                                                                   'oid'=>$v_ar['oid'],
                                                                   'jobid'=>$v_ar['jobid'],
                                                                   'jadd'=>$v_ar['jadd'],
                                                                   'phsid'=>$row1['phsid'],
                                                                   'matid'=>$row1['matid'],
                                                                   'code'=>$row1['accid'],
                                                                   'vpno'=>$row1['vpno'],
                                                                   'ListID'=>$row1['ListID'],
                                                                   'EditSequence'=>$row1['EditSequence'],
                                                                   'itemname'=>htmlspecialchars(trim($row1['item'])),
                                                                   'itemattrib1'=>htmlspecialchars(trim($row1['atrib1'])),
                                                                   'itemattrib2'=>htmlspecialchars(trim($row1['atrib2'])),
                                                                   'unitprice'=>$fbprice,
                                                                   'totalprice'=>$fbprice,
                                                                   'tquantity'=>$idata[2],
                                                                   'unkparam'=>$idata[4]
                                                                   );
                        
                            if (trim($row1['ListID'])==='0')
                            {
                                $data_out['inventory_errors'][]=array(
                                                                       'invid'=>$row1['invid'],
                                                                       'oid'=>$v_ar['oid'],
                                                                       'jobid'=>$v_ar['jobid'],
                                                                       'jadd'=>$v_ar['jadd'],
                                                                       'phsid'=>$row1['phsid'],
                                                                       'matid'=>$row1['matid'],
                                                                       'code'=>$row1['accid'],
                                                                       'vpno'=>$row1['vpno'],
                                                                       'ListID'=>$row1['ListID'],
                                                                       'EditSequence'=>$row1['EditSequence'],
                                                                       'itemname'=>htmlspecialchars(trim($row1['item'])),
                                                                       'itemattrib1'=>htmlspecialchars(trim($row1['atrib1'])),
                                                                       'itemattrib2'=>htmlspecialchars(trim($row1['atrib2'])),
                                                                       'unitprice'=>$fbprice,
                                                                       'totalprice'=>$fbprice,
                                                                       'tquantity'=>$idata[2],
                                                                       'unkparam'=>$idata[4]
                                                                   );
                            }
                        }
                    }
				}
			}
		}
	}
    
    return $data_out;
}

function proc_bid_items($v_ar)
{
    $data_out=array();
    $data_out['bids']=array();
    $data_out['bids_errors']=array();
    
	$MAS=$v_ar['MAS'];
	//echo " (Internal) ";
    
    if ($MAS=='0')
    {
        $pb_code='';
    }
    else
    {
        $pb_code=$MAS;
    }

    if (isset($v_ar['enqb']) and $v_ar['enqb']==1)
    {
        $qry = "select phsid,phscode,phstype from phasebase order by phsid;";
        $res = mssql_query($qry);
        
        while ($row = mssql_fetch_array($res))
        {
            if ($row['phstype']=='M')
            {
                $bidsrctable='inventory';
            }
            else
            {
                $bidsrctable='accpbook';
            }

            $qry3	= "
            SELECT
                J1.*
                ,(select item from [".$pb_code."acc] where officeid=J1.officeid and id=J1.rdbid) AS idesc
                ,(select top 1 ListID from [".$pb_code.$bidsrctable."] where officeid=J1.officeid and phsid=J1.phsid) AS iListID
                ,(select top 1 EditSequence from [".$pb_code.$bidsrctable."] where officeid=J1.officeid and phsid=J1.phsid) AS iEditSequence
            FROM
                jbids_breakout AS J1
            WHERE
                J1.officeid=".$v_ar['oid']."
                AND J1.jobid='".$v_ar['jobid']."'
                AND J1.phsid=".$row['phsid']."
            ;";
            $res3	= mssql_query($qry3);
            $nrow3  = mssql_num_rows($res3);
        
            //echo $qry3;
            if ($nrow3 > 0)
            {
                while ($row3 = mssql_fetch_array($res3))
                {
                    // Stores Bid Cost Breakouts, if any
                    if ((isset($row3['iListID']) and $row3['iListID']!=='0') and (isset($row3['iEditSequence']) and $row3['iEditSequence']!=='0'))
                    {
                        $data_out['bids'][]=array(
                                                       'bid'=>$row3['id'],
                                                       'oid'=>$row3['officeid'],
                                                       'jobid'=>$v_ar['jobid'],
                                                       'jadd'=>$v_ar['jadd'],
                                                       'phsid'=>$row3['phsid'],
                                                       'code'=>'BIDCOST',
                                                       'ListID'=>$row3['iListID'],
                                                       'EditSequence'=>$row3['iEditSequence'],
                                                       'itemname'=>htmlspecialchars(trim($row3['idesc'])),
                                                       'itemattrib1'=>htmlspecialchars(trim($row3['sdesc'])),
                                                       'itemattrib2'=>'',
                                                       'unitprice'=>number_format($row3['bprice'], 2, '.', ''),
                                                       'totalprice'=>number_format($row3['bprice'], 2, '.', ''),
                                                       'tquantity'=>1,
                                                       'unkparam'=>''
                                                       );
                    }
                    else
                    {
                        $data_out['bids_errors'][]=array(
                                                       'bid'=>$row3['id'],
                                                       'oid'=>$row3['officeid'],
                                                       'jobid'=>$row3['jobid'],
                                                       'jadd'=>$v_ar['jadd'],
                                                       'phsid'=>$row3['phsid'],
                                                       'code'=>'BIDCOST',
                                                       'ListID'=>$row3['iListID'],
                                                       'EditSequence'=>$row3['iEditSequence'],
                                                       'itemname'=>htmlspecialchars(trim($row3['idesc'])),
                                                       'itemattrib1'=>htmlspecialchars(trim($row3['sdesc'])),
                                                       'itemattrib2'=>'',
                                                       'unitprice'=>number_format($row3['bprice'], 2, '.', ''),
                                                       'totalprice'=>number_format($row3['bprice'], 2, '.', ''),
                                                       'tquantity'=>1,
                                                       'unkparam'=>''
                                                       );
                    }
                }
            }
        }
    }
    
    return $data_out;
}

function proc_mpa_items($v_ar)
{
    $data_out=array();
    $data_out['adjusts']=array();
    $data_out['adjusts_errors']=array();
    
	if (isset($v_ar['enqb']) and $v_ar['enqb']==1)
	{
		$qry3	= "SELECT M.* FROM man_phs_adj AS M WHERE M.officeid=".$v_ar['oid']." AND M.jobid='".$v_ar['jobid']."' and jadd=".$v_ar['jadd'].";";
		$res3	= mssql_query($qry3);
		$nrow3  = mssql_num_rows($res3);
	
		if ($nrow3 > 0)
		{
			while ($row3 = mssql_fetch_array($res3))
			{
				// Stores Bid Cost Breakouts, if any
				$data_out['adjusts'][]=array(
											   'aid'=>$row3['id'],
											   'oid'=>$row3['officeid'],
											   'jobid'=>$v_ar['jobid'],
                                               'jadd'=>$v_ar['jadd'],
											   'phsid'=>$row3['phsid'],
											   'code'=>'MPACST',
											   'ListID'=>'',
											   'EditSequence'=>'',
											   'itemname'=>htmlspecialchars(trim($row3['sdesc'])),
											   'itemattrib1'=>htmlspecialchars(trim($row3['smisc'])),
											   'itemattrib2'=>'',
											   'unitprice'=>number_format($row3['bprice'], 2, '.', ''),
											   'totalprice'=>number_format($row3['bprice'], 2, '.', ''),
											   'tquantity'=>1,
											   'unkparam'=>''
											   );
			}
        }
	}
    
    return $data_out;
}

function read_payment_sched($oid,$jobid,$jadd)
{
    $out=array();
    
    $qry = "SELECT psched,psched_perc from jdetail where officeid=".$oid." and jobid='".$jobid."' and jadd=".$jadd.";";
	$res = mssql_query($qry);
    $row = mssql_fetch_array($res);
    $nrow= mssql_num_rows($res);
    
    if ($nrow == 1)
	{
        $phs=explode(',',$row['psched']);
        $sch=explode(',',$row['psched_perc']);
        
        if (count($phs) == count($sch))
        {
            foreach ($phs as $pn=>$pv)
            {
                $qry1 = "select phsid from phasebase where phscode='".trim($pv)."';";
                $res1 = mssql_query($qry1);
                $row1 = mssql_fetch_array($res1);
                
                $out[]=array('phsid'=>$row1['phsid'],'amt'=>$sch[$pn]);
            }
        }
    }
    
    return $out;
}

function proc_payment_sched($va,$s)
{
	//print_r($s).'<br>';
    
    $phs_ar=array();
    
    $qry0	= "select * from payment_schedule where cid=".$va['cid']." and psTxnID!='0';";
	$res0 	= mssql_query($qry0);
    $nrow0  = mssql_num_rows($res0);
    
    if ($nrow0 > 0)
    {
        while ($row0  = mssql_num_rows($res0))
        {
            $phs_ar[]=$row0['phsid'];
        }
    }
	
	foreach ($s as $nn => $vv)
	{
		if ($vv['amt'] > 0 and in_array($vv['phsid'],$phs_ar))
		{
			$qry1	= "INSERT INTO payment_schedule (oid,cid,phsid,amount,sid) VALUES (".$va['oid'].",".$va['cid'].",'".$vv['phsid']."',cast('".$vv['amt']."' as money),".$_SESSION['securityid'].");";
			$res1 	= mssql_query($qry1);
		}
	}
	
    return true;
}

function proc_quickbooks_datastore($inc_data,$forcereset)
{
    
    /*
      This function tests JMS job data against each QB data conditioning tables and
      builds item lists for inclusion/exclusion to the QB Conditioning Tables.
      
      Parameters:
      $inc_data (array): Job Detail including Cost Items compiled from Job Data Storage in JMS.
      $forcereset (bool): Forces reset of existing data in QB Conditioning Tables. Use with caution.
      
    */
    
    $fn_debug=array('all'=>false,'service'=>false,'material'=>false,'inventory'=>false,'bids'=>false,'adjusts'=>false);
    $forcereset=false;
	$out=array();
    $wrk_data=array(
                    'service'=>array('curr'=>array(),'del'=>array(),'add'=>array(),'prc'=>array()),
                    'material'=>array('curr'=>array(),'del'=>array(),'add'=>array(),'prc'=>array()),
                    'inventory'=>array('curr'=>array(),'del'=>array(),'add'=>array(),'prc'=>array()),
                    'bids'=>array('curr'=>array(),'del'=>array(),'add'=>array(),'prc'=>array()),
                    'adjusts'=>array('curr'=>array(),'del'=>array(),'add'=>array(),'prc'=>array())
                    );
    
    // Clear previous entries
    if ($forcereset)
    {
        mssql_query("delete from jest..JobCost_Service where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";");
        mssql_query("delete from jest..JobCost_Material where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";");
        mssql_query("delete from jest..JobCost_Inventory where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";");
        mssql_query("delete from jest..JobCost_BidCost where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";");
        mssql_query("delete from jest..JobCost_Adjusts where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";");
    }
	
	// Select Previous Data
    $qryS   ="select * from jest..JobCost_Service where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resS   =mssql_query($qryS);
	$nrowS  =mssql_num_rows($resS);
    
    $qryM   ="select * from jest..JobCost_Material where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resM   =mssql_query($qryM);
	$nrowM  =mssql_num_rows($resM);
    
    $qryI   ="select * from jest..JobCost_Inventory where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resI   =mssql_query($qryI);
	$nrowI  =mssql_num_rows($resI);
    
    $qryB   ="select * from jest..JobCost_BidCost where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resB   =mssql_query($qryB);
    $nrowB  =mssql_num_rows($resB);
    
    $qryA   ="select * from jest..JobCost_Adjusts where oid=".$inc_data['oid']." and jobid='".trim($inc_data['jobid'])."' and jadd=".trim($inc_data['jadd']).";";
    $resA   =mssql_query($qryA);
	$nrowA  =mssql_num_rows($resA);
	
    // Build Current Stored array
	if ($nrowS > 0)
	{
        while($rowS = mssql_fetch_array($resS))
        {
            $wrk_data['service']['curr'][]=$rowS;
        }
        
        $wrk_data['service']['add']=array_diff($inc_data['jc_ar']['service'],$wrk_data['service']['curr']);
	}
    else
    {
        $wrk_data['service']['add']=$inc_data['jc_ar']['service'];
    }
    
    if ($fn_debug['all'] or $fn_debug['service'])
    {
        echo '<pre>';
        echo 'Existing Service: ('.$nrowS.')<br>';
        echo "Incoming Service: (".count($inc_data['jc_ar']['service']).")<br>";
        echo "Service Add: <br>";
        print_r($wrk_data['service']['add']);
        echo '</pre>';
    }
    
	if ($nrowM > 0)
	{
        while($rowM = mssql_fetch_array($resM))
        {
            $wrk_data['material']['curr'][]=$rowM;
        }
        
        $wrk_data['material']['add']=array_diff($inc_data['jc_ar']['material'],$wrk_data['material']['curr']);
	}
    else
    {
        $wrk_data['material']['add']=$inc_data['jc_ar']['material'];
    }
    
    if ($fn_debug['all'] or $fn_debug['material'])
    {
        echo '<pre>';
        echo 'Existing Material: ('.$nrowM.')<br>';
        echo "Incoming Material: (".count($inc_data['jc_ar']['material']).")<br>";
        echo 'Material Add: <br>';
        print_r($wrk_data['material']['add']);
        echo '</pre>';
    }
	
	if ($nrowI > 0)
	{
        while($rowI = mssql_fetch_array($resI))
        {
            $wrk_data['inventory']['curr'][]=$rowI;
        }
        
        $wrk_data['inventory']['add']=array_diff($inc_data['jc_ar']['inventory'],$wrk_data['inventory']['curr']);
	}
    else
    {
        $wrk_data['inventory']['add']=$inc_data['jc_ar']['inventory'];
    }
    
    if ($fn_debug['all'] or $fn_debug['inventory'])
    {
        echo '<pre>';
        echo 'Existing Inventory: ('.$nrowI.')<br>';
        echo "Incoming Inventory: (".count($inc_data['jc_ar']['inventory']).")<br>";
        echo 'Inventory Add: <br>';
        print_r($wrk_data['inventory']['add']);
        echo '</pre>';
    }
    
    if ($nrowB > 0)
	{
        while($rowB = mssql_fetch_array($resB))
        {
            $wrk_data['bids']['curr'][]=$rowB;
        }
        
        $wrk_data['bids']['add']=array_diff($inc_data['jc_ar']['bids'],$wrk_data['bids']['curr']);
	}
    else
    {
        $wrk_data['bids']['add']=$inc_data['jc_ar']['bids'];
    }
    
    if ($fn_debug['all'] or $fn_debug['bids'])
    {
        echo '<pre>';
        echo 'Existing Bids: ('.$nrowB.')<br>';
        echo "Incoming Bids: (".count($inc_data['jc_ar']['bids']).")<br>";
        echo 'Bid Adds: <br>';
        print_r($wrk_data['bids']['add']);
        echo '</pre>';
    }
	
	if ($nrowA > 0)
	{
        while($rowA = mssql_fetch_array($resA))
        {
            $wrk_data['adjusts']['curr'][]=$rowA;
        }
        
        $wrk_data['adjusts']['add']=array_diff($inc_data['jc_ar']['adjusts'],$wrk_data['adjusts']['curr']);
	}
    else
    {
        $wrk_data['adjusts']['add']=$inc_data['jc_ar']['adjusts'];
    }
	
    if ($fn_debug['all'] or $fn_debug['adjusts'])
    {
        echo '<pre>';
        echo 'Existing Adjusts: ('.$nrowA.')<br>';
        echo "Incoming Adjusts: (".count($inc_data['jc_ar']['adjusts']).")<br>";
        echo 'Adjust Adds: <br>';
        print_r($wrk_data['adjusts']['add']);
        echo '</pre>';
    }
    
    exit;
	//echo '<pre>';
	$preqry='BEGIN TRANSACTION';
	$pstqry='COMMIT TRANSACTION';
    
    $srvcnt=0;
    if (count($wrk_data['service']['add']) > 0)
	{
        $srvqry='';
        foreach ($wrk_data['service']['add'] as $ns=>$vs)
        {
            $srvqry=$srvqry."
            INSERT INTO [jest]..[JobCost_Service] (
            [srvid], [oid], [jobid], [jadd], [phsid],
            [ListID], [EditSequence], [code],
            [itemname], [itemattrib1], [itemattrib2],
            [unitprice], [totalprice], [tquantity]
            ) VALUES (
            ".$vs['srvid'].", ".$vs['oid'].", '".$vs['jobid']."', ".$vs['jadd'].", ".$vs['phsid'].",
            '".$vs['ListID']."', '".$vs['EditSequence']."', '".$vs['code']."',
            '".substr(trim($vs['itemname']),0,30)."', '".substr(trim($vs['itemattrib1']),0,30)."','".substr(trim($vs['itemattrib2']),0,30)."',
            cast('".$vs['unitprice']."' as money), cast('".$vs['totalprice']."' as money), ".$vs['tquantity'].");
            ";
            $srvcnt++;
        }
        
        if ($srvcnt > 0)
        {
            mssql_query($preqry.$srvqry.$pstqry);
            //echo $preqry.$srvqry.$pstqry;
        }
	}
	
    $matcnt=0;
    if (count($wrk_data['material']['add']) > 0)
	{
        $matqry='';
        foreach ($wrk_data['material']['add'] as $nm=>$vm)
        {
            $matqry=$matqry."
            INSERT INTO [jest]..[JobCost_Material] (
            [invid], [oid], [jobid], [jadd], [phsid],
            [matid], [vpno], [ListID], [EditSequence], [code],
            [itemname], [itemattrib1], [itemattrib2],
            [unitprice], [totalprice], [tquantity]
            ) VALUES (
            ".$vm['invid'].", ".$vm['oid'].", '".$vm['jobid']."', ".$vs['jadd'].", ".$vm['phsid'].",
            ".$vm['matid'].", '".$vm['vpno']."','".$vm['ListID']."', '".$vm['EditSequence']."', '".$vm['code']."',
            '".substr(trim($vm['itemname']),0,30)."', '".substr(trim($vm['itemattrib1']),0,30)."','".substr(trim($vm['itemattrib2']),0,30)."',
            cast('".$vm['unitprice']."' as money), cast('".$vm['totalprice']."' as money), ".$vm['tquantity'].");
            ";
            $matcnt++;
        }
        
        if ($matcnt > 0)
        {
            mssql_query($preqry.$matqry.$pstqry);
            //echo $preqry.$matqry.$pstqry;
        }
	}
	
    $invcnt=0;
    if (count($wrk_data['inventory']['add']) > 0)
	{
        $invqry='';
        foreach ($wrk_data['inventory']['add'] as $ni=>$vi)
        {
            $invqry=$invqry."
            INSERT INTO [jest]..[JobCost_Inventory] (
            [invid], [oid], [jobid], [jadd], [phsid],
            [matid], [vpno], [ListID], [EditSequence], [code],
            [itemname], [itemattrib1], [itemattrib2],
            [unitprice], [totalprice], [tquantity]
            ) VALUES (
            ".$vi['invid'].", ".$vi['oid'].", '".$vi['jobid']."', ".$vs['jadd'].", ".$vi['phsid'].",
            ".$vi['matid'].", '".$vi['vpno']."','".$vi['ListID']."', '".$vi['EditSequence']."', '".$vi['code']."',
            '".substr(trim($vi['itemname']),0,30)."', '".substr(trim($vi['itemattrib1']),0,30)."','".substr(trim($vi['itemattrib2']),0,30)."',
            cast('".$vi['unitprice']."' as money), cast('".$vi['totalprice']."' as money), ".$vi['tquantity'].");
            ";
            $invcnt++;
        }
        
        if ($invcnt > 0)
        {
            mssql_query($preqry.$invqry.$pstqry);
            //echo $preqry.$invqry.$pstqry;
        }
	}
    
    $bidcnt=0;
    if (count($wrk_data['bids']['add']) > 0)
	{
        $bidqry='';
        foreach ($wrk_data['bids']['add'] as $nb=>$vb)
        {
            $bidqry=$bidqry."
            INSERT INTO [jest]..[JobCost_BidCost] (
            [bid], [oid], [jobid], [jadd], [phsid],
            [ListID], [EditSequence], [code],
            [itemname], [itemattrib1], [itemattrib2],
            [unitprice], [totalprice], [tquantity]
            ) VALUES (
            ".$vb['bid'].", ".$vb['oid'].", '".$vb['jobid']."', ".$vb['jadd'].", ".$vb['phsid'].",
            '".$vb['ListID']."', '".$vb['EditSequence']."', '".$vb['code']."',
            '".substr(trim($vb['itemname']),0,30)."', '".substr(trim($vb['itemattrib1']),0,30)."','".substr(trim($vb['itemattrib2']),0,30)."',
            cast('".$vb['unitprice']."' as money), cast('".$vb['totalprice']."' as money), ".$vb['tquantity'].");
            ";
            $bidcnt++;
        }
        
        if ($bidcnt > 0)
        {
            mssql_query($preqry.$bidqry.$pstqry);
            //echo 'Bid Items: '.$bidcnt.'<br>';
            //echo $preqry.$invqry.$pstqry;
        }
	}
	
    $adjcnt=0;
	if (count($wrk_data['adjusts']['add']) > 0)
	{
        $adjcnt=0;
        $adjqry='';
        foreach ($wrk_data['adjusts']['add'] as $na=>$va)
        {
            $adjqry=$adjqry."
            INSERT INTO [jest].[dbo].[JobCost_Adjusts] (
            [aid], [oid], [jobid], [jadd], [phsid],
            [ListID], [EditSequence], [code],
            [itemname], [itemattrib1], [itemattrib2],
            [unitprice], [totalprice], [tquantity]
            ) VALUES (
            ".$va['aid'].", ".$va['oid'].", '".$va['jobid']."', ".$vs['jadd'].", ".$va['phsid'].",
            '".$va['ListID']."', '".$va['EditSequence']."', '".$va['code']."',
            '".substr(trim($va['itemname']),0,30)."', '".substr(trim($va['itemattrib1']),0,30)."','".substr(trim($va['itemattrib2']),0,30)."',
            cast('".$va['unitprice']."' as money), cast('".$va['totalprice']."' as money), ".$va['tquantity'].");
            ";
            $adjcnt++;
        }
        
        if ($adjcnt > 0)
        {
            mssql_query($preqry.$adjqry.$pstqry);
            //echo 'Adjust Items: '.$adjcnt.'<br>';
            //echo $preqry.$invqry.$pstqry;
        }
	}
    
    //$pmtcnt=proc_payment_sched($v_ar,read_payment_sched($v_ar['oid'],$v_ar['jobid'],0));
    $pmtcnt=0;
	
	//echo '</pre>';
    
    /*
    $out=array(
               'tprc'=>($srvcnt+$matcnt+$invcnt+$bidcnt+$adjcnt+$pmtcnt),
               'srv'=>$srvcnt,'mat'=>$matcnt,
               'inv'=>$invcnt,'bid'=>$bidcnt,
               'adj'=>$adjcnt,'pmt'=>$pmtcnt,
               'srverr'=>print_r($inc_data['jc_ar']['service_errors']),'materr'=>print_r($inc_data['jc_ar']['material_errors']),
               'inverr'=>print_r($inc_data['jc_ar']['inventory_errors']),'biderr'=>print_r($inc_data['jc_ar']['bids_errors']),
               'adjerr'=>print_r($inc_data['jc_ar']['adjusts_errors'])
               );
    */
    
    //$out=$inc_data['jc_ar']['service_errors'];
	//return $out;
}

?>