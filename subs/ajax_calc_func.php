<?php

function start_addendum_calc($oid,$jid,$jd)
{
	$out_ar=array();
	$qryV = "SELECT * FROM jdetail WHERE officeid=".$oid." AND jobid='".$jid."' AND jadd!=0;";
	$resV = mssql_query($qryV);
	$nrowV= mssql_num_rows($resV);

	if ($nrowV > 0)
	{
		while ($rowV= mssql_fetch_array($resV))
		{
			$pld=explode(',',$rowV['pcostlabdiff']);
			$pmd=explode(',',$rowV['pcostmatdiff']);
			$cld=explode(',',$rowV['costlabdiff']);
			$cmd=explode(',',$rowV['costmatdiff']);
			
			if (is_array($pld))
			{
				$aflc=add_filter_labor_cost($oid,$jid,$jd,$pld,$rowV['jadd']);
				if (count($aflc) > 0)
				{
					foreach($aflc as $naflc => $vaflc)
					{
						$out_ar[$rowV['jadd']][]=$vaflc;
					}
				}
			}

			if (is_array($pld))
			{
				$afmc=add_filter_mat_cost($oid,$jid,$jd,$pmd,$rowV['jadd']);
				if (count($afmc) > 0)
				{
					foreach($afmc as $nafmc => $vafmc)
					{
						$out_ar[$rowV['jadd']][]=$vafmc;
					}
				}
			}

			if (is_array($cld))
			{
				$alc=add_labor_cost($oid,$jid,$jd,$cld,$rowV['jadd']);
				if (count($alc) > 0)
				{
					foreach($alc as $nalc => $valc)
					{
						$out_ar[$rowV['jadd']][]=$valc;
					}
				}
			}

			if (is_array($cmd))
			{
				$amc=add_mat_cost($oid,$jid,$jd,$cmd,$rowV['jadd']);
				if (count($amc) > 0)
				{
					foreach($amc as $namc => $vamc)
					{
						$out_ar[$rowV['jadd']][]=$vamc;
					}
				}
			}
			
			$cbi=bid_cost($oid,$jid,$rowV['jadd'],$jd,0,'');
			if (count($cbi) > 0)
			{
				foreach($cbi as $ncbi => $vcbi)
				{
					$out_ar[$rowV['jadd']][]=$vcbi;
				}
			}
			
			$mpa=mpa_cost($oid,$jid,$rowV['jadd'],$jd,0,'');
			if (count($mpa) > 0)
			{
				foreach($mpa as $nmpa => $vmpa)
				{
					$subsi[$rowV['jadd']][]=$vmpa;
				}
			}
			
			$ccr=calc_commission($oid,$jid,$rowV['jadd'],$jd,3,'503L');
			if (count($ccr) > 0)
			{
				foreach($ccr as $nccr => $vccr)
				{
					$out_ar[$rowV['jadd']][]=$vccr;
					//$out_ar[$rowV['jadd']][3]=$vccr;
				}
			}
			
			$ccm=calc_commission($oid,$jid,$rowV['jadd'],$jd,4,'504L');
			if (count($ccm) > 0)
			{
				foreach($ccm as $nccm => $vccm)
				{
					$out_ar[$rowV['jadd']][]=$vccm;
					//$out_ar[$rowV['jadd']][4]=$vccm;
				}
			}
			
			//echo 'JADD: '.$rowV['jadd'].'<br>';
			//echo '<pre>';
			//print_r($out_ar);
			//echo '</pre>';
		}
	}
	
	return $out_ar;
}

function add_filter_labor_cost($oid,$jid,$jd,$cdata,$jadd)
{
	$si=array();
	
	foreach ($cdata as $pre_n=>$pre_iv)
	{
		$pre_v=explode(":",$pre_iv);
		if (isset($pre_v[0]) and $pre_v[0]!=0)
		{
			$qryB = "SELECT a.*,(select phscode from phasebase where phsid=a.phsid) as phscode FROM [".$jd['pb_code']."accpbook] as a WHERE a.officeid=".$oid." AND a.id='".$pre_v[5]."' AND a.baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			$nrowB= mssql_num_rows($resB);
	
			$rid    =$pre_v[0];
			$cid    =$pre_v[5];
			$quan	=$pre_v[4];
			$cost	=$pre_v[6];
			$qtype	=$rowB['qtype'];
			$code	=$pre_v[5];
			$lrange	=$rowB['lrange'];
			$hrange	=$rowB['hrange'];
			$iphsid =$rowB['phsid'];
			$rinvid =$rowB['rinvid'];
			$quancalc=$rowB['quantity'];
	
			if ($nrowB > 0)
			{
				$item	=$rowB['item'];
				$a1	=$rowB['atrib1'];
				$a2	=$rowB['atrib2'];
				$a3	=$rowB['atrib3'];
				
				if ($rowB['qtype']!=33)
				{
					$calc_out	=uni_calc_loop($jd,$qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,0,0,0,$a1,$a2,$a3,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					
					//$si[$iphsid][]=array('phsid'=>$rowB['phscode'],'item'=>$item,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
					$si[]=array('jobname'=>$jd['jobname'],'phase'=>$rowB['phscode'].':'.$jd['jid'],'item'=>$item,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
				}
			}
		}
	}

	return $si;
}

function add_filter_mat_cost($oid,$jid,$jd,$cdata,$jadd)
{
	$si=array();
	foreach ($cdata as $pre_n=>$pre_iv)
	{
		$pre_v=explode(":",$pre_iv);
		if (isset($pre_v[0]) and $pre_v[0]!=0)
		{
			$qryB = "SELECT i.*,(select phscode from phasebase where phsid=i.phsid) as phscode FROM [".$jd['pb_code']."inventory] as i WHERE i.officeid='".$oid."' AND i.invid='".$pre_v[5]."' AND i.baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			$nrowB= mssql_num_rows($resB);
	
			$rid    =$pre_v[0];
			$cid    =$pre_v[5];
			$quan	=$pre_v[4];
			$cost	=$pre_v[6];
			$qtype	=$rowB['qtype'];
			$code	=$pre_v[5];
			$lrange	=0;
			$hrange	=0;
			$iphsid =$rowB['phsid'];
			$rinvid =$rowB['rinvid'];
			$quancalc=$rowB['quan_calc'];
	
			if ($nrowB > 0)
			{
				$item=$rowB['item'];
				$a1	=$rowB['atrib1'];
				$a2	=$rowB['atrib2'];
				$a3	=$rowB['atrib3'];
				
				if ($rowB['qtype']!=33)
				{
					$calc_out	=uni_calc_loop($jd,$qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,0,0,0,$a1,$a2,$a3,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
	
					//$si[$iphsid][]=array('phsid'=>$rowB['phscode'],'item'=>$item,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
					$si[]=array('jobname'=>$jd['jobname'],'phase'=>$rowB['phscode'].':'.$jd['jid'],'item'=>$item,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
				}
			}
		}
	}
	
	return $si;
}

function add_labor_cost($oid,$jid,$jd,$cdata,$jadd)
{
	$si=array();
	foreach ($cdata as $pre_n=>$pre_iv)
	{
		//echo $pre_iv.'<br>';
		$pre_v=explode(":",$pre_iv);
		if (isset($pre_v[0]) and $pre_v[0]!=0)
		{
			//echo 'HIT<br>';
			$qryB = "SELECT a.*,(select phscode from phasebase where phsid=a.phsid) as phscode FROM [".$jd['pb_code']."accpbook] as a WHERE a.officeid=".$oid." AND a.id='".$pre_v[1]."' AND a.baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			$nrowB= mssql_num_rows($resB);
			
			$rid	=$pre_v[0];
			$cid	=$pre_v[1];
			$quan	=$pre_v[2];
			$cost	=$pre_v[3];
			$qtype	=$pre_v[4];
			$code	=$pre_v[5];
			$lrange	=$rowB['lrange'];
			$hrange	=$rowB['hrange'];
			$iphsid =$rowB['phsid'];
			$rinvid =$rowB['rinvid'];
			$quancalc=$rowB['quantity'];
			
			//echo $qryB.'<br>';
			
			// 3/7/07 Backup Compat
			if (empty($pre_v[6]))
			{
				$pre_v[6]=0;
			}
			
			if (empty($pre_v[7]))
			{
				$pre_v[7]=0;
			}
			
			if (empty($pre_v[8]))
			{
				$pre_v[8]=0;
			}
			
			if (empty($pre_v[9]))
			{
				$pre_v[9]=0;
			}
			// End Backward Compat
	
			if ($nrowB > 0)
			{
				$item	=$rowB['item'];
				$a1	=$rowB['atrib1'];
				$a2	=$rowB['atrib2'];
				$a3	=$rowB['atrib3'];
	
				if ($rowB['qtype']!=33)
				{
					$calc_out	=uni_calc_loop($jd,$qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,0,0,0,$a1,$a2,$a3,$pre_v[7],$pre_v[9]);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					
					//$si[$iphsid][]=array('phsid'=>$rowB['phscode'],'item'=>$item,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
					$si[]=array('jobname'=>$jd['jobname'],'phase'=>$rowB['phscode'].':'.$jd['jid'],'item'=>$item,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
				}
			}
		}
	}

	//echo '<pre>';
	//print_r($si);
	//echo '</pre>';

	return $si;
}

function add_mat_cost($oid,$jid,$jd,$cdata,$jadd)
{
	$si=array();
	foreach ($cdata as $pre_n=>$pre_iv)
	{
		$pre_v=explode(":",$pre_iv);
		if (isset($pre_v[0]) and $pre_v[0]!=0)
		{
			$qryB = "SELECT i.*,(select phscode from phasebase where phsid=i.phsid) as phscode FROM [".$jd['pb_code']."inventory] as i WHERE i.officeid=".$oid." AND i.invid='".$pre_v[1]."' AND i.baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
	
			$rid		=$pre_v[0];
			$cid		=$pre_v[1];
			$quan		=$pre_v[2];
			$subbprice  =$pre_v[3];
			$subqtype   =$pre_v[4];
			$code       =$pre_v[5];
			$subphsid	=$pre_v[6];
			$subitem	=$rowB['item'];
			$subquan_c  =$rowB['quan_calc'];
			$subatrib1	=$rowB['atrib1'];
			$subatrib2	=$rowB['atrib2'];
			$subatrib3	=$rowB['atrib3'];
			$subquan		=$quan;
	
			if ($rowB['qtype']!=33)
			{
				$calc_out	=uni_calc_loop($jd,$subqtype,$subbprice,0,0,0,$subquan,$subquan_c,0,0,$code,$subatrib1,$subatrib2,$subatrib3,0,0);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
	
				//$si[$subphsid][]=array('phsid'=>$rowB['phscode'],'item'=>$subitem,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
				$si[]=array('jobname'=>$jd['jobname'],'phase'=>$rowB['phscode'].':'.$jd['jid'],'item'=>$subitem,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
			}
		}
	}

	//echo '<pre>';
	//print_r($si);
	//echo '</pre>';

	return $si;
}

function start_labor_calc($oid,$jid,$jadd,$jd,$cdata,$bdata,$fdata)
{
	//echo __FUNCTION__.':'.$fdata.'<br>';
    $phs_ar=array();
	$out_ar=array();
    
	$qryA = "SELECT phsid,phscode,phsname,seqnum FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum ASC;";
	$resA = mssql_query($qryA);
	
	while($rowA = mssql_fetch_array($resA))
	{
		$phs_ar[$rowA['phsid']]=array('phsid'=>$rowA['phsid'],'phscode'=>$rowA['phscode'],'phsname'=>$rowA['phsname']);
	}

	/*
	$qryB = "SELECT stax FROM offices WHERE officeid=".$oid.";";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryC = "SELECT id,quan,price FROM rbpricep WHERE officeid=".$oid." AND quan='".$jd['pft']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$pbaseprice=$rowC['price']-$discount;
	*/

	foreach ($phs_ar as $np => $vp)
	{
		if ($np==3)
		{
			$ccr=calc_commission($oid,$jid,$jadd,$jd,$np,$vp['phscode']);
			if (count($ccr) > 0)
			{
				$out_ar[$np]=$ccr;
			}
		}
		elseif ($np==4)
		{
			$ccm=calc_commission($oid,$jid,$jadd,$jd,$np,$vp['phscode']);
			if (count($ccm) > 0)
			{
				$out_ar[$np]=$ccm;
			}
		}
		elseif ($np==5)
		{
			//$roy=calc_royalty($oid,$jid,$jd,$ci,$vp['phscode']);
            $out_ar[$np][]=array('jobname'=>$jd['jobname'],'phase'=>$vp['phscode'].':'.$jd['jid'],'item'=>$vp['phsname'],'quan'=>1,'amt'=>calc_royalty($oid,$jid,$jd,$cdata,$np,$vp['phscode']));
		}
		else
		{
			// Base Items *Defunct*
			$lbc=l_baseitems_calc($oid,$jid,$jadd,$jd,$np,$bdata,$vp['phscode']);
			if (count($lbc) > 0)
			{
				foreach($lbc as $nlbc => $vlbc)
				{
					$out_ar[$np][]=$vlbc;
				}
			}
			
			// Package Items
			$lpc=l_pkgitems_calc($oid,$jid,$jadd,$jd,$np,$fdata,$vp['phscode']);
			if (count($lpc) > 0)
			{
				foreach($lpc as $nlpc => $vlpc)
				{
					$out_ar[$np][]=$vlpc;
				}
			}
			
			// Main Items
			$adjamt=get_adj_amt($oid,$jid,$np);
			$lic=l_mainitems_calc($oid,$jid,$jadd,$np,$jd,$cdata,$bdata,$fdata,$adjamt,$vp['phscode']);			
			if (count($lic) > 0)
			{
				foreach($lic as $nlic => $vlic)
				{
					$out_ar[$np][]=$vlic;
				}
			}

			//Bid Cost
			$cbi=bid_cost($oid,$jid,$jadd,$jd,$np,$vp['phscode']);
			if (count($cbi) > 0)
			{
				foreach($cbi as $ncbi => $vcbi)
				{
					$out_ar[$np][]=$vcbi;
				}
			}
			
			//Manual Phase Adjust
			$mpa=mpa_cost($oid,$jid,$jadd,$jd,$np,$vp['phscode']);
			if (count($mpa) > 0)
			{
				foreach($mpa as $nmpa => $vmpa)
				{
					$out_ar[$np][]=$vmpa;
				}
			}
		}
	}
    
    return $out_ar;
}

function l_mainitems_calc($oid,$jid,$jadd,$phsid,$jd,$cdata,$bdata,$fdata,$adjamt,$phscode)
{
	//echo __FUNCTION__.':'.$fdata.'<br>';
    $si=array();

	if ($cdata > 0)
	{
		$edata=explode(",",$cdata);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			$pre_v=explode(":",$pre_iv);
			$iphsid =$pre_v[8];
			
			if ($phsid==$iphsid)
			{
				$rid    =$pre_v[0];
				$cid    =$pre_v[1];
				$quan   =$pre_v[2];
				$cost   =$pre_v[3];
				$qtype	=$pre_v[4];
				$code   =$pre_v[5];
				$lrange	=$pre_v[6];
				$hrange	=$pre_v[7];
				$rinvid =$pre_v[9];
				$quancalc=$pre_v[10];
	
				$qryB = "SELECT * FROM [".$jd['pb_code']."accpbook] WHERE officeid=".$oid." AND phsid='".$iphsid."' AND id='".$pre_v[1]."' AND baseitem!=1";
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_array($resB);
				$nrowB= mssql_num_rows($resB);
	
				if ($nrowB > 0)
				{
					$item=$rowB['item'];
					$a1	=$rowB['atrib1'];
					$a2	=$rowB['atrib2'];
					$a3	=$rowB['atrib3'];
				}
				else
				{
					$item	='Not Found';
					$a1	='';
					$a2	='';
					$a3	='';
				}
				
				if ($rinvid!=0)  // Credit Code Loop
				{
					$si[]		=lab_credititem($oid,$jid,$jadd,$jd,$rowB['rinvid'],$iphsid,$phscode,$quan);
				}

				if ($rowB['qtype']!=33)
				{
					$calc_out	=uni_calc_loop($jd,$qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,0,0,0,$a1,$a2,$a3,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
                    
					$si[]=array('jobname'=>$jd['jobname'],'phase'=>$phscode.':'.$jd['jid'],'item'=>$rowB['item'],'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
				}
			}
		}
	}

	return $si;
}

function start_material_calc($oid,$jid,$jadd,$jd,$cdata,$bdata,$fdata)
{
	//echo __FUNCTION__.':'.$fdata.'<br>';
	$phs_ar =array();
	$out_ar	=array();

	$qryA = "SELECT phsid,phscode,phsname,seqnum,phsname FROM phasebase WHERE phstype='M' ORDER BY seqnum ASC;";
	$resA = mssql_query($qryA);
	
	while($rowA = mssql_fetch_array($resA))
	{
		$phs_ar[$rowA['phsid']]=array('phsid'=>$rowA['phsid'],'phscode'=>$rowA['phscode'],'phsname'=>$rowA['phsname']);
	}

	foreach ($phs_ar as $np => $vp)
	{
		// Package Items
		$mpc=m_pkgitems_calc($oid,$jid,$jadd,$jd,$np,$fdata,$vp['phscode']);
		if (count($mpc) > 0)
		{
			foreach($mpc as $nmpc => $vmpc)
			{
				$out_ar[$np][]=$vmpc;
			}
		}
		
		// Main Items
		$adjamt=get_adj_amt($oid,$jid,$np);
		$mmc=m_mainitems_calc($oid,$jid,$jadd,$jd,$np,$cdata,$fdata,$adjamt,$vp['phscode']);
		if (count($mmc) > 0)
		{
			foreach($mmc as $nmmc => $vmmc)
			{
				$out_ar[$np][]=$vmmc;
			}
		}
	}
	
	return $out_ar;
}

function m_mainitems_calc($oid,$jid,$jadd,$jd,$phsid,$cdata,$fdata,$adjamt,$phscode)
{
	//echo __FUNCTION__.':'.$fdata.'<br>';
    $si=array();

	// Option Calcs
	if ($cdata > 0)
	{
		$edata=explode(",",$cdata);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			$pre_v=explode(":",$pre_iv);
			$subphsid	=$pre_v[6];

			if ($subphsid==$phsid)
			{
				//echo $pre_iv.'<br>';
				$qryB = "SELECT * FROM [".$jd['pb_code']."inventory] WHERE officeid=".$oid." AND phsid='".$phsid."' AND invid='".$pre_v[1]."' AND baseitem!=1";
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_array($resB);
	
				if ($rowB['matid']!=0)
				{
					$qryBa	="SELECT bp FROM material_master WHERE id='".$rowB['matid']."';";
					$resBa	=mssql_query($qryBa);
					$rowBa	=mssql_fetch_array($resBa);
	
					if ($rowB['qtype']==56)
					{
						$subatrib3	=$rowBa['bp'];
					}
					else
					{
						$subatrib3	=$rowB['atrib3'];
					}
				}
				else
				{
					$subatrib3	=$rowB['atrib3'];
				}
	
				$rid		=$pre_v[0];
				$quan		=$pre_v[2];
				$subbprice	=$pre_v[3];
				$subqtype	=$pre_v[4];
				$code		=$pre_v[5];
				$subitem	=$rowB['item'];
				$subquan_c  =$rowB['quan_calc'];
				$subatrib1	=$rowB['atrib1'];
				$subatrib2	=$rowB['atrib2'];
				$subatrib3	=$rowB['atrib3'];
				$subquan	=$quan;
				
				$subrp =0; // Deprecated, remove on code cleanup
				$rc    =0; // Deprecated, remove on code cleanup

				if ($rowB['rinvid']!=0)  // Credit Code Loop
				{
					$si[]		=mat_credititem($oid,$jid,$jadd,$jd,$rowB['rinvid'],$pre_v,$phscode);
				}

				if ($rowB['qtype']!=33)
				{
					//$calc_out	=uni_calc_loop($jd,$qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,0,0,0,$a1,$a2,$a3,0,0);
					$calc_out	=uni_calc_loop($jd,$subqtype,$subbprice,0,0,0,$subquan,$subquan_c,0,0,$code,$subatrib1,$subatrib2,$subatrib3,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];

					$si[]=array('jobname'=>$jd['jobname'],'phase'=>$phscode.':'.$jd['jid'],'item'=>$rowB['item'],'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
				}
			}
		}
	}
	
	$cbi=bid_cost($oid,$jid,$jadd,$jd,$phsid,$phscode);
    if (count($cbi) > 0)
    {
		foreach($cbi as $n2 => $v2)
		{
			$si[]=$v2;
		}
    }
    
    $mpa=mpa_cost($oid,$jid,$jadd,$jd,$phsid,$phscode);
    if (count($mpa) > 0)
    {
		foreach($mpa as $n3 => $v3)
		{
			$si[]=$v3;
		}
    }

	return $si;
}

function calc_commission($oid,$jid,$jadd,$jd,$phsid,$phscode)
{
	$scomm=array();
	$secid=0;
	$stype='';
	
	if ($phsid==3)
	{
		$secid=$jd['sid'];
		$stype=' and cbtype!=4';
	}
	elseif ($phsid==4)
	{
		$secid=$jd['sidm'];
		$stype=' and cbtype=4';
	}
	
	$qryA  = "SELECT cs.csid,cs.secid,cs.amt as commamt,cs.notes, ";
	$qryA .= "(select fullname from CommissionBuilderCategory WHERE catid=cs.cbtype) as catname, ";
	$qryA .= "(select fname from security WHERE securityid=cs.secid) as sfname, ";
	$qryA .= "(select lname from security WHERE securityid=cs.secid) as slname ";
	$qryA .= "FROM CommissionSchedule AS cs ";
	$qryA .= "WHERE cs.oid=".$oid." AND cs.jobid='".$jid."' and cs.jadd=".$jadd." and cs.secid=".$secid."".$stype.";";
	$resA  = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);
	
	//echo $qryA.'<br>';
	while ($rowA = mssql_fetch_array($resA))
	{
		
		/*
		if ($jadd!=0)
		{
			$scomm[$phsid][]=array(
						  'phase'=>$phscode,
						  //'cid'=>$rowA['csid'],
						  'item'=>$rowA['catname'].' Commission: '.trim($rowA['sfname']).' '.trim($rowA['slname']).' '.trim($rowA['notes']),
						  'quan'=>1,
						  'amt'=>number_format($rowA['commamt'], 2, '.', '')
						  );
		}
		else
		{
		*/
			if ($rowA['commamt']!=0)
			{
				$scomm[]=array(
							  'jobname'=>$jd['jobname'],
							  'phase'=>$phscode.':'.$jd['jid'],
							  //'cid'=>$rowA['csid'],
							  'item'=>$rowA['catname'].' Comm '.trim($rowA['sfname']).' '.trim($rowA['slname']).' '.trim($rowA['notes']),
							  'quan'=>1,
							  'amt'=>number_format($rowA['commamt'], 2, '.', '')
							  );
			}
		//}
	}

	return $scomm;
}

function lab_credititem($oid,$jid,$jadd,$jd,$id,$phsid,$phscode,$quan)
{
	$out=array();
	
	$qry 		= "SELECT * FROM [".$jd['pb_code']."accpbook] WHERE officeid='".$oid."' AND phsid=".$phsid." AND id='".$id."';";
	$res 		= mssql_query($qry);
	$row 		= mssql_fetch_array($res);

	$subbp      =$row['bprice'];
	$subrp      =0;
	$subphsid	=$row['phsid'];
	$subitem	=$row['item'];
	$subquan	=$quan;
	$lr			=$row['lrange'];
	$hr			=$row['hrange'];
	$cr			=1;
	$code		=0;

	$calc_out	=uni_calc_loop($jd,$row['qtype'],$subbp,0,0,0,$subquan,$row['quantity'],0,0,$code,0,0,0,0,0);
	$bp			=$calc_out[0]*-1;
	$quan_out	=$calc_out[2]*-1;
	
	$out=array('jobname'=>$jd['jobname'],'phase'=>$phscode.':'.$jd['jid'],'item'=>$row['item'],'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
	return $out;
}

function mat_credititem($oid,$jid,$jadd,$jd,$rinvid,$pre_v,$phscode)
{
	$out=array();
	
	$qry = "SELECT * FROM [".$jd['pb_code']."inventory] WHERE officeid=".$oid." AND phsid=".$pre_v[6]." AND invid=".$rinvid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1 = "SELECT rid FROM [".$jd['pb_code']."rclinks_m] WHERE officeid=".$oid." AND cid=".$rinvid.";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	//echo "<br>QRY : ".$qry."<br>";

	if ($row['matid']!=0)
	{
		$qrya = "SELECT bp FROM material_master WHERE id='".$row['matid']."';";
		$resa = mssql_query($qrya);
		$rowa = mssql_fetch_array($resa);

		$subbp	=$rowa['bp'];
	}
	else
	{
		$subbp	=$row['bprice'];
	}

	$subrp      =0;
	$subphsid   =$row['phsid'];
	$subitem    =$row['item'];
	$subquan    =$pre_v[2];
	$cr         =1;
	$code       =0;

	$calc_out	=uni_calc_loop($jd,$row['qtype'],$subbp,0,0,0,$subquan,$row['quan_calc'],0,0,$code,0,0,0,0,0);
	$bp			=$calc_out[0]*-1;
	$quan_out	=$calc_out[2]*-1;
	
	$out=array('jobname'=>$jd['jobname'],'phase'=>$phscode.':'.$jd['jid'],'item'=>$row['item'],'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
	return $out;
}

function calc_royalty($oid,$jid,$jd,$ci,$np,$phscode)
{
	$out=0;
	//calc_royalty($oid,$jid,$jd,$cdata,$np,$vp['phscode'])
	$subctr	=0;
	$subroyt=0;
	$subroyn=0;
	$phsid	=8;
	//$viewarray['royrel']=0;

	$ci=preg_replace("/,\Z/","",$ci);
	if ($ci > 0)
	{
		//echo "COSTS: ";
		//print_r($costitems);
		$edata=explode(",",$ci);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			//echo "IV: ".$pre_iv."<br>";
			$pre_v=explode(":",$pre_iv);
			//echo "<pre>";
			//print_r($pre_v);
			//echo "</pre>";

			$rid    =$pre_v[0];
			$cid    =$pre_v[1];
			$quan	=$pre_v[2];
			$cost	=$pre_v[3];
			$qtype	=$pre_v[4];
			$code	=$pre_v[5];
			$lrange	=$pre_v[6];
			$hrange	=$pre_v[7];
			$iphsid =$pre_v[8];
			$rinvid =$pre_v[9];
			$quancalc=$pre_v[10];

			$qryB = "SELECT * FROM [".$jd['pb_code']."accpbook] WHERE officeid=".$oid." AND phsid='".$iphsid."' AND id='".$pre_v[1]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			$nrowB= mssql_num_rows($resB);

			if ($nrowB > 0)
			{
				$item	=$rowB['item'];
				$a1	=$rowB['atrib1'];
				$a2	=$rowB['atrib2'];
				$a3	=$rowB['atrib3'];
			}
			else
			{
				$item	='Not Found';
				$a1	='';
				$a2	='';
				$a3	='';
			}

			if ($phsid==$iphsid)
			{
				if ($qtype==33 && $rowB['royrelease']==1) // Bid Item
				{
					//echo "BID ITEM<br>";
					$qryC = "SELECT rid FROM [".$jd['pb_code']."rclinks_l] WHERE officeid=".$oid." AND cid='".$pre_v[1]."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);
					$nrowC= mssql_num_rows($resC);

					if ($nrowC > 0)
					{
						$qryCa = "SELECT * FROM jbids_breakout WHERE officeid=".$oid." AND jobid='".$jid."' AND rdbid='".$pre_v[0]."';";
						$resCa = mssql_query($qryCa);
						$nrowCa= mssql_num_rows($resCa);

						if ($nrowCa > 0)
						{
							$subbp=0;
							while($rowCa = mssql_fetch_array($resCa))
							{
								$bp=$rowCa['bprice'];
								$subbp=$subbp+$bp;
							}
						}
						else
						{
							$qryD = "SELECT bidinfo FROM jbids WHERE officeid=".$oid." AND jobid='".$jid."' AND dbid='".$pre_v[0]."';";
							$resD = mssql_query($qryD);
							$rowD = mssql_fetch_array($resD);

							$qryE = "SELECT estdata FROM jdetail WHERE officeid=".$oid." AND jobid='".$jid."' AND jadd=0;";
							$resE = mssql_query($qryE);
							$rowE = mssql_fetch_array($resE);

							$Xarray=explode(",",$rowE['estdata']);
							foreach ($Xarray as $n=>$v)
							{
								$subXarray=explode(":",$v);
								if ($subXarray[0]==$pre_v[0])
								{
									$Xbp=$subXarray[3];
								}
							}

							$subbp=$Xbp;
							$subrp=0;
							//$bc=$bc+$subbp;
							//echo ":WITHOUT";
						}

						if ($rowB['royrelease']==1)
						{
							$jd['royrel']=$subbp;
							//echo "ROYR: ".$viewarray['royrel']."<BR>";
						}
					}
				}
			}
		}
	}

	//$royalty		=($viewarray['camt']-$viewarray['royrel'])*.03;
	
	if ($jd['camt'] != 0)
	{
		$preval=100000;
		if (($jd['camt']-$jd['royrel']) >= $preval)
		{
			$preroy = ($preval *.03);
			$pstroy = ((($jd['camt']-$jd['royrel']) - $preval) * .01);
			$out= $preroy + $pstval;
		}
		else
		{
			$out=($jd['camt']-$jd['royrel'])*.03;
		}
	}
	else
	{
		$out=0;
	}

	return number_format(round($out), 2, '.', '');
}

function get_adj_amt($oid,$jid,$phsid)
{
	$dout=0;

	$qry0 = "SELECT manphscostadj FROM jobs WHERE officeid=".$oid." AND jobid='".$jid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	if (strlen($row0['manphscostadj']) > 3)
	{
		$sub1=explode(",",$row0['manphscostadj']);
		foreach ($sub1 as $n1 => $v1)
		{
			$sub2=explode(":",$v1);
			if ($sub2[0]==$phsid)
			{
				$dout=$sub2[1];
			}
		}
	}
	
	return $dout;
}

function calc_internal_area($pft,$sqft,$shallow,$middle,$deep)
{
	$ia=((($shallow+$middle+$deep)/3)*$pft)+$sqft;

	if (is_float($ia))
	{
		$ia=round($ia);
	}
	return $ia;
}

function calc_gallons($pft,$sqft,$shallow,$middle,$deep)
{
	$gals=((($shallow+$middle+$deep)/3)*$sqft)*7.5;

	if (is_float($gals))
	{
		$gals=round($gals);
	}
	return $gals;
}

function l_baseitems_calc($oid,$jid,$jadd,$phsid,$bdata,$phscode)
{
	$si=array();

	if ($bdata > 0)
	{
		$edata=explode(",",$bdata);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			//echo 'LBI: '.$pre_iv.'<br>';
			$pre_v=explode(":",$pre_iv);
			
			//print_r($pre_v);
			
			if (is_array($pre_v) and isset($pre_v[11]) and $pre_v[11]!=0)
			{
				$rid	=0;
				$cid	=$pre_v[0];
				$accid	=$pre_v[1];
				$matid	=$pre_v[3];
				$quan	=$pre_v[11];
				$cost	=$pre_v[6];
				$qtype	=$pre_v[4];
				$code	=$pre_v[8];
				$lrange	=$pre_v[9];
				$hrange	=$pre_v[10];
				$iphsid  =$pre_v[2];
				//$rinvid  =$pre_v[9];
				$quancalc=$pre_v[13];
				$item	=$pre_v[7];
				$a1		='';
				$a2		='';
				$a3		='';
	
				// Fix to correct Base Item Phase ID Storage bug
				if ($qtype==53 && $matid==53)
				{
					$iphsid=1;
				}
	
				if ($cid=='' && $accid=='' && $qtype=='' && $matid=='')
				{
					$iphsid	=41;
					$item		="<b>Unlinked Entry</b>";
				}
	
				if ($phsid==$iphsid)
				{
					if ($qtype==53) // Permit qtype
					{
						if ($rowpre0[5]==1)
						{
							$qry1a ="SELECT custid FROM jobs WHERE officeid=".$oid." AND jobid='".$jid."';";
							$res1a =mssql_query($qry1a);
							$row1a =mssql_fetch_array($res1a);
	
							$qry1b ="SELECT scounty FROM cinfo WHERE officeid=".$oid." AND custid='".$row1a[0]."';";
							$res1b =mssql_query($qry1b);
							$row1b =mssql_fetch_array($res1b);
	
							$qry1 ="SELECT permit,city FROM taxrate WHERE officeid=".$oid." AND id='".$row1b[0]."';";
							$res1 =mssql_query($qry1);
							$row1 =mssql_fetch_array($res1);
	
							$bp 	=$row1['permit'];
							$item  ="Permit (".$row1['city'].")";
						}
						else
						{
							$bp	=$cost;
						}
	
						$quan_out	=$quan;
						
						$si[]=array('jobname'=>$jd['jobname'],'phase'=>$phscode.':'.$jd['jid'],'item'=>$item,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
					}
					elseif ($qtype!=33) // All other qtypes
					{
						$bp			=$cost;
						$quan_out	=$quan;
						$si[]=array('jobname'=>$jd['jobname'],'phase'=>$phscode.':'.$jd['jid'],'item'=>$item,'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
					}
				}
			}
		}
	}
	
	return $si;
}

function bid_cost($oid,$jid,$jadd,$jd,$phsid)
{
	$si	=array();

	if ($jadd!=0)
	{
		$qryA = "SELECT j.*,(select phscode from phasebase where phsid=j.phsid) as phscode FROM jbids_breakout as j WHERE j.officeid=".$oid." and j.jobid='".$jid."' and j.jadd=".$jadd.";";
	}
	else
	{
		$qryA = "SELECT j.*,(select phscode from phasebase where phsid=j.phsid) as phscode FROM jbids_breakout as j WHERE j.officeid=".$oid." and j.jobid='".$jid."' and j.jadd=".$jadd." and j.phsid=".$phsid.";";
	}
	
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA." : ".$nrowA.'<br>';
	
	if ($nrowA!=0)
	{
		while ($rowA = mssql_fetch_array($resA))
		{
			$si[]=array('jobname'=>$jd['jobname'],'phase'=>$rowA['phscode'].':'.$jd['jid'],'item'=>'BID Item: '.$rowA['sdesc'],'quan'=>1,'amt'=>number_format($rowA['bprice'], 2, '.', ''));
		}
	}
	
	//echo '<pre>';
	//print_r($si);
	//echo '</pre>';
	
	return $si;
}

function mpa_cost($oid,$jid,$jadd,$jd,$phsid,$phscode)
{
	$si		=array();
	
	$qryA = "SELECT m.*,(select phscode from phasebase where phsid=m.phsid) as phscode  FROM man_phs_adj as m WHERE m.officeid=".$oid." and m.jobid='".$jid."' and m.jadd=".$jadd." and m.phsid=".$phsid.";";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	if ($nrowA!=0)
	{
		while ($rowA = mssql_fetch_array($resA))
		{
			$si[]=array('jobname'=>$jd['jobname'],'phase'=>$rowA['phscode'].':'.$jd['jid'],'cid'=>$rowA['id'],'item'=>'MPA Item: '.$rowA['sdesc'],'quan'=>1,'amt'=>number_format($rowA['bprice'], 2, '.', ''));
		}
	}
	
	return $si;
}

function l_pkgitems_calc($oid,$jid,$jadd,$jd,$phsid,$fdata,$phscode)
{
	$si=array();
	
	if (!empty($fdata) && strlen($fdata) > 3)
	{
		$edata=explode(",",$fdata);
		foreach ($edata as $pre_en=>$pre_ev)
		{
			$idata=explode(":",$pre_ev);

			// Displayed Quantity is forced to 1 if stored quan is 0
			if ($idata[4]==0)
			{
				$quan=1;
			}
			else
			{
				$quan=$idata[4];
			}

			//echo "TEST: ".$idata[4]."<br>";
			$code=$idata[9];
			$subbp=$idata[8];
			$qtype=$idata[7];

			$qryB = "SELECT * FROM [".$jd['pb_code']."accpbook] WHERE officeid=".$oid." AND phsid=".$phsid." AND id='".$idata[5]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			if ($rowB['phsid']==$phsid)
			{
				if ($rowB['rinvid']!=0)  // Credit Code Loop
				{
					//		 lab_credititem($oid,$jid,$jadd,$jd,$id,$phsid,$phscode,$quan)
					$cr_out	=lab_credititem($rowB['rinvid'],$rowB['id'],$phsid,$quan,0);
					$bp		=$cr_out[0];
				}

				$calc_out	=uni_calc_loop($jd,$qtype,$subbp,0,$rowB['lrange'],$rowB['hrange'],$quan,$rowB['quantity'],0,0,$code,0,0,0,0,0);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];

				$si[]=array('jobname'=>$jd['jobname'],'phase'=>$phscode.':'.$jd['jid'],'item'=>$rowB['item'],'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
			}
		}
	}
	
	return $si;
}

function m_pkgitems_calc($oid,$jid,$jadd,$jd,$phsid,$fdata,$phscode)
{
	$si=array();

	if (strlen($fdata) > 1)
	{
		$edata=explode(",",$fdata);
		foreach ($edata as $pre_en=>$pre_ev)
		{
			$idata=explode(":",$pre_ev);

			// Displayed Quantity is forced to 1 if stored quan is 0
			if ($idata[4]==0)
			{
				$quan=1;
			}
			else
			{
				$quan=$idata[4];
			}

			$code	=$idata[9];
			$subbp	=$idata[8];

			$qryB = "SELECT * FROM [".$jd['pb_code']."inventory] WHERE officeid=".$oid." AND phsid='".$phsid."' AND invid='".$idata[5]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			if ($rowB['phsid']==$phsid)
			{
				if ($rowB['matid']!=0)
				{
					$qryBa		="SELECT bp FROM material_master WHERE id='".$rowB['matid']."';";
					$resBa		=mssql_query($qryBa);
					$rowBa	   =mssql_fetch_array($resBa);

					if ($rowB['qtype']==56)
					{
						//print_r($idata);
						$subatrib3	=$rowBa['bp'];
					}
					else
					{
						$subatrib3	=$rowB['atrib3'];
					}
				}
				else
				{
					$subatrib3	=$rowB['atrib3'];
				}

				//echo $qryB."<br>";
				if ($rowB['rinvid']!=0)  // Credit Code Loop
				{
					$cr_out	=mat_credititem($oid,$jid,$jadd,$jd,$rowB['rinvid'],$idata,$phscode);
					$bp		=$cr_out[0];
					$bc		=$bc+$bp;
				}

				$calc_out	=uni_calc_loop($jd,$rowB['qtype'],$subbp,0,0,0,$quan,$rowB['quan_calc'],0,0,$code,$rowB['atrib1'],$rowB['atrib2'],$subatrib3,0,0);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				
				$si[]=array('jobname'=>$jd['jobname'],'phase'=>$phscode.':'.$jd['jid'],'item'=>$rowB['item'],'quan'=>$quan_out,'amt'=>number_format($bp, 2, '.', ''));
			}
		}
	}

	return $si;
}

function uni_calc_loop($jd,$qtype,$bp,$rp,$lr,$hr,$quan,$def_quan,$spa_ia,$spa_gl,$code,$a1,$a2,$a3,$mquan,$chgproc)
{
	if ($qtype==1||$qtype==31||$qtype==33||$qtype==77) // Fixed - Quantity - Bid Item
	{
		//temp fix
		if ($quan < 0)
		{
			$quan_out	= -1;
			//$quan_out=$quan;
		}
		else
		{
			$quan_out	= 1;
		}
		//$quan_out=$quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
		//echo $qtype.":".$quan_out.":".$subbp."<br>";

	}
	elseif ($qtype==2) // Quantity
	{
		$quan_out=$quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==3) // PFT
	{
		$quan_out=$jd['pft'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==4) // SQFT
	{
		$quan_out=$jd['sqft'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==5) // Base+ (PFT)
	{
		$quan_out=$jd['pft'];
		
		if (isset($chgproc) && $chgproc==1)
		{
			if ($mquan > $hr)
			{	
				$subbp=$quan_out*$def_quan;
				$subrp=$quan_out*$def_quan;
			}
			else
			{
				$subbp=$bp;
				$subrp=$rp;
			}
		}
		else
		{
			if ($quan_out > $hr)
			{
				$subbp=$bp+(($quan_out-$hr)*$def_quan);
				$subrp=$rp+(($quan_out-$hr)*$def_quan);
			}
			else
			{
				$subbp=$bp;
				$subrp=$rp;
			}
		}
	}
	elseif ($qtype==6) // Base+ (SQFT)
	{
		$quan_out=$jd['sqft'];
		
		if (isset($chgproc) && $chgproc==1)
		{
			if ($mquan > $hr)
			{	
				$subbp=$quan_out*$def_quan;
				$subrp=$quan_out*$def_quan;
			}
			else
			{
				$subbp=0;
				$subrp=0;
			}
		}
		else
		{
			if ($quan_out > $hr)
			{
				//echo "HIGHER<BR>";
				$subbp=$bp+(($quan_out-$hr)*$def_quan);
				$subrp=$rp+(($quan_out-$hr)*$def_quan);
			}
			else
			{
				//echo "LOWER<BR>";
				$subbp=$bp;
				$subrp=$rp;
			}
		}
	}
	elseif ($qtype==7) // Base+ (IA)
	{
		$quan_out=$jd['iarea'];
		if (isset($chgproc) && $chgproc==1)
		{
			if ($mquan > $hr)
			{	
				$subbp=$quan_out*$def_quan;
				$subrp=$quan_out*$def_quan;
			}
			else
			{
				$subbp=0;
				$subrp=0;
			}
		}
		else
		{
			if ($quan_out > $hr)
			{
				//$tt="(ONE)";
				$subbp=$bp+(($quan_out-$hr)*$def_quan);
				$subrp=$rp+(($quan_out-$hr)*$def_quan);
			}
			else
			{
				//$tt="(TWO)";
				$subbp=$bp;
				$subrp=$rp;
			}
		}
		// $subbp." ".$tt." ($hr)<br>";
	}
	elseif ($qtype==8) // Base+ (Fixed)
	{
		$quan_out=$quan;
		if (isset($chgproc) && $chgproc==1)
		{
			if ($mquan > $hr)
			{	
				$subbp=$quan_out*$def_quan;
				$subrp=$quan_out*$def_quan;
			}
			else
			{
				$subbp=0;
				$subrp=0;
			}
		}
		else
		{
			if ($quan_out > $hr)
			{
				$subbp=$bp+(($quan_out-$hr)*$def_quan);
				$subrp=$rp+(($quan_out-$hr)*$def_quan);
			}
			else
			{
				$subbp=$bp;
				$subrp=$rp;
			}
		}
	}
	elseif ($qtype==9) // Bracket (PFT)
	{
		$quan_out=$jd['pft'];
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==10) // Bracket (SQFT)
	{
		$quan_out=$jd['sqft'];
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==11) // Bracket (IA)
	{
		$quan_out=$jd['iarea'];
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==12) // Bracket (Gallons)
	{
		$quan_out=$jd['gals'];
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==13) // Checkbox (PFT)
	{
		$quan_out=$jd['pft'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==14) // Checkbox (SQFT)
	{
		$quan_out=$jd['sqft'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==15) // Checkbox (Quantity)
	{
		$quan_out=$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==16) // Checkbox (IA)
	{
		$quan_out=$iarea;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==17) // Checkbox (Gallons)
	{
		$quan_out=$gals;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	/*
	elseif ($qtype==18) // Code (PFT)
	{
		$quan_out=$jd['pft'];
		$scode=getcodeitem($code);
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==19) // Code (SQFT)
	{
		$quan_out=$jd['sqft'];
		$scode=getcodeitem($code);
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==20) // Code (Quantity)
	{
		$quan_out=$quan;
		$scode=getcodeitem($code);
		$subbp=$scode[2]*$quan_out;
		$subrp=$scode[3]*$quan_out;
	}
	elseif ($qtype==21) // Code (IA)
	{
		$quan_out=$iarea;
		$scode=getcodeitem($code);
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==22) // Code (Gallons)
	{
		$quan_out=$gals;
		$scode=getcodeitem($code);
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==23) // Code (Checkbox)
	{
		$quan_out=1;
		$scode=getcodeitem($code);
		$sitem="<u>".$rowB['name']."</u><br>".$scode[1];
		$subbp=$scode[2];
		$subrp=$scode[3];
	}
	elseif ($qtype==31) // Cubic FT(Checkbox)
	{
	$quan_out=1;
	$scode=getcodeitem($code);
	$sitem="<u>".$rowB['name']."</u><br>".$scode[1];
	$subbp=$scode[2];
	$subrp=$scode[3];
	}
	*/
	elseif ($qtype==45) // Peri Deck Incl(Cost is Base+)
	{
		//$xps1=$viewarray['ps1']*2.16;
		//$quan_out=$viewarray['deck'];
		$quan_out=$jd['pft']*2.16;
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==46) // IA (Div by CalcAmt)
	{
		$quan_out=$jd['iarea']/$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==47) // IA (Mult by CalcAmt)
	{
		$quan_out=$jd['iarea']*$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==48) // Base Inclusion (Quantity)
	{
		$quan_out=$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==53) // Permit
	{
		if ($bp==0)
		{
			$qrypst0 ="SELECT stax FROM offices WHERE officeid=".$oid.";";
			$respst0 =mssql_query($qrypst0);
			$rowpst0 =mssql_fetch_array($respst0);

			if ($rowpst0['stax']==1)
			{
				$qry1a ="SELECT custid FROM jobs WHERE officeid=".$oid." AND jobid='".$jid."';";
				$res1a =mssql_query($qry1a);
				$row1a =mssql_fetch_array($res1a);

				$qry1b ="SELECT scounty FROM cinfo WHERE officeid=".$oid." AND custid='".$row1a[0]."';";
				$res1b =mssql_query($qry1b);
				$row1b =mssql_fetch_array($res1b);

				$qry1 ="SELECT permit,city FROM taxrate WHERE officeid=".$oid." AND id='".$row1b[0]."';";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$quan_out=$row1['permit'];
				$subbp=$quan_out;
				$subrp=$quan_out;
			}
			else
			{
				$quan_out=$quan;
				$subbp=$quan_out;
				$subrp=$quan_out;
			}
		}
		else
		{
			$quan_out=$bp;
			$subbp=$quan_out;
			$subrp=$quan_out;
		}
	}
	elseif ($qtype==54) // Referral
	{
		$quan_out=$quan;
		$subbp=$quan_out;
		$subrp=$quan_out;
	}
	elseif ($qtype==55 || $qtype==72) // Package (Quantity) - Package (Checkbox)
	{
		$quan_out=$quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==56) // IA (Div by CalcAmt) Base+
	{
		if ($jd['iarea'] > $a2)
		{
			$calc1=$a2/$def_quan;
			$calc2=($jd['iarea']-$a2)/$def_quan;
			$quan_out=$calc1+$calc2;
			$subbp=$bp+($calc2*$a3);
			$subrp=$rp+($calc2*$a3);			
		}
		else
		{
			$calc=$jd['iarea']/$def_quan;
			$quan_out=$calc;
			$subbp=$bp;
			$subrp=$rp;
			//echo "INSIDE ($subbp)<BR>";
		}
	}
	elseif ($qtype==57) // Gallons (Total)
	{
		$quan_out=$jd['gals'];
		$subbp=$quan_out;
		$subrp=$quan_out;
	}
	elseif ($qtype==58) // Base+ (Quantity)
	{
		$quan_out=$quan;
		if (isset($chgproc) && $chgproc==1)
		{
			if ($mquan > $hr)
			{
				$subbp=$quan_out*$def_quan;
				$subrp=$quan_out*$def_quan;
			}
			else
			{
				//$subbp=$bp;
				//$subrp=$rp;
				$subbp=0;
				$subrp=0;
			}
		}
		else
		{
			if ($quan_out > $hr)
			{
				$subbp=$bp+(($quan_out-$hr)*$def_quan);
				$subrp=$rp+(($quan_out-$hr)*$def_quan);
			}
			else
			{
				$subbp=$bp;
				$subrp=$rp;
			}
		}
	}
	elseif ($qtype==59) // Elec Run (Total)
	{
		$quan_out=$jd['erun'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==60) // Plumb Run (Total)
	{
		$quan_out=$jd['prun'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	/*
	elseif ($qtype==61) // SPA PFT
	{
		$quan_out=$spa2;
		$subbp=$bp*$quan_out;
		$subrp=$bp*$quan_out;
	}
	elseif ($qtype==62) // SPA SQFT
	{
		$quan_out=$spa3;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==63) // SPA IA
	{
		$quan_out=$spa_ia;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==64) // SPA Gallons
	{
		$subbp=$bp*$spa_gl;
		$subrp=$bp*$spa_gl;
	}
	elseif ($qtype==65) // SPA PFT Base+ (Quantity)
	{
		$quan_out=$spa2;
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==66) // SPA SQFT Base+ (Quantity)
	{
		$quan_out=$spa3;
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==67) // SPA IA Base+ (Quantity)
	{
		$quan_out=$spa_ia;
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==68) // SPA IA Base+ (Quantity)
	{
		$quan_out=$spa_gl;
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==69) // Base+ (Depth)
	{
		$quan_out=$viewarray['ps7'];
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==70) // Depth
	{
		$quan_out=$viewarray['ps7'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==71) // Deck (Total Base+)
	{
		$quan_out=$viewarray['deck'];
		if ($quan_out > $hr)
		{
			$subbp=$bp+(($quan_out-$hr)*$def_quan);
			$subrp=$rp+(($quan_out-$hr)*$def_quan);
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	*/
	elseif ($qtype==73) // Peri (Div by CalcAmt)
	{
		$quan_out=$jd['pft']/$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==74) // SA (Div by CalcAmt)
	{
		$quan_out=$jd['sqft']/$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==75) // Peri (Div by CalcAmt) Base +
	{
		if ($jd['pft'] > $a2)
		{
			$calc1=$a2/$def_quan;
			$calc2=($jd['pft']-$a2)/$def_quan;
			$quan_out=$calc1+$calc2;
			$subbp=$bp+($calc2*$a3);
			$subrp=$rp+($calc2*$a3);
		}
		else
		{
			$calc=$jd['pft']/$def_quan;
			$quan_out=$calc;
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==76) // SA (Div by CalcAmt) Base +
	{
		if ($jd['sqft'] > $a2)
		{
			$calc1=$a2/$def_quan;
			$calc2=($jd['sqft']-$a2)/$def_quan;
			$quan_out=$calc1+$calc2;
			$subbp=$bp+($calc2*$a3);
			$subrp=$rp+($calc2*$a3);
		}
		else
		{
			$calc=$jd['sqft']/$def_quan;
			$quan_out=$calc;
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==77) // Base+ (Quan Calc Fixed)
	{
		$quan_out=$quan;
		if ($quan_out > $hr)
		{
			$subbp=$def_quan;
			$subrp=$def_quan;
		}
		else
		{
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==78) // Contract Amt Multiplier
	{
		$quan_out=$jd['camt'] * $def_quan;
		$subbp=$quan_out;
		$subrp=$quan_out;
	}
	else // Catch Bucket
	{
		$quan_out	=0;
		$subbp		=0;
		$subrp		=0;
	}

	$ar_out=array(0=>round($subbp),1=>$subrp,2=>$quan_out);
	return $ar_out;
}
