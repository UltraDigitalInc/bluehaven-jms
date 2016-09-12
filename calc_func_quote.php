<?php

function load_pricebook_data()
{
	error_reporting(E_ALL);
	//echo 'LOADING PRICEBOOK DATA<br>';
    
    //echo $_SESSION['pb_code'].'<br>';
    
    //if (isset($_SESSION['pb_code']) && strlen($_SESSION['pb_code']) > 1)
    //{
		$qryB  = "SELECT DISTINCT a.catid,a.name,a.seqn ";
		$qryB .= "FROM AC_cats AS a INNER JOIN [".$_SESSION['pb_code']."acc] AS b ";
		$qryB .= "ON a.catid=b.catid ";
		$qryB .= "AND a.officeid='".$_SESSION['officeid']."' ";
		$qryB .= "AND a.active=1 ";
        $qryB .= "AND a.privcat!=1 ";
		$qryB .= "ORDER BY a.seqn ASC;";
		$resB = mssql_query($qryB);
        
        //echo $qryB.'<br>';
		
		while($rowB = mssql_fetch_array($resB))
        {
            $acc_ar[$rowB['catid']]=   array(
                                        0=>	$rowB['catid'],
                                        1=>	$rowB['name']
                                        );
		
			$qryA = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled FROM [".$_SESSION['pb_code']."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid=".$rowB['catid']." AND disabled!=1 ORDER BY seqn;";
			$resA = mssql_query($qryA);
				
			while($rowA = mssql_fetch_array($resA))
			{
				$acc_ar[$rowB['catid']][2][]=   array(
											$rowA['id'],
											$rowA['aid'],
											$rowA['officeid'],
											$rowA['item'],
											$rowA['accpbook'],
											$rowA['qtype'],
											$rowA['seqn'],
											$rowA['rp'],
											$rowA['bp'],
											$rowA['spaitem'],
											$rowA['mtype'],
											$rowA['atrib1'],
											$rowA['atrib2'],
											$rowA['atrib3'],
											$rowA['quan_calc'],
											$rowA['commtype'],
											$rowA['crate'],
											$rowA['disabled']
											);
			}
		}
		
		$_SESSION['pricebookdata']  =json_encode($acc_ar);
        $_SESSION['pbupdate']       =1;
    //}
    
    //print_r($_SESSION['pricebookdata']);
    //echo 'LOADING PRICEBOOK DATA END<br>';
}

//function bid_cost_detect($oid,$jid)
function bid_cost_detect($oid,$jid,$jadd)
{
	$MAS	=$_SESSION['pb_code'];
	$rid_ar	=array();
	$rin_ar	=array();
	$btype  =33;
    $cl     =1;
    $costid =0;
    $cnt    =0;
	
	if ($_SESSION['action']=="est")
	{
		$qryA = "SELECT * FROM est_acc_ext WHERE officeid='".$oid."' and estid='".$jid."';";
		$jtype="bid_breakout";
		$jnum ="estid";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qryA = "SELECT officeid,jobid,njobid,estdata FROM jdetail WHERE officeid='".$oid."' and jobid='".$jid."' and jadd='".$jadd."';";
		$jtype="jbids_breakout";
		$jnum ="jobid";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryA = "SELECT officeid,jobid,njobid,estdata FROM jdetail WHERE officeid='".$oid."' and njobid='".$jid."' and jadd='".$jadd."';";
		$jtype="jbids_breakout";
		$jnum ="njobid";
	}
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	//echo $qryA.'<br>';
	
	$ri_ar=explode(",",$rowA['estdata']);
	
	foreach ($ri_ar as $n1 => $v1)
	{
		$rii_ar=explode(":",$v1);
		
		if (isset($rii_ar[0]) && $rii_ar[0]!=0)
		{
			$qryB = "SELECT id,aid,qtype,item FROM [".$MAS."acc] WHERE officeid='".$oid."' and id='".$rii_ar[0]."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			
			if ($rowB['qtype']==$btype && $rii_ar[0]==$rowB['id'])
			{
				$qryC = "SELECT count(id) as idcnt FROM ".$jtype." WHERE officeid='".$oid."' and ".$jnum."='".$jid."' and rdbid='".$rii_ar[0]."';";
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_array($resC);
				
				//echo $qryC.'<br>';
				
				$rin_ar[$rii_ar[0]]=$rowC['idcnt'];
			}
		}
	}

	foreach ($rin_ar as $n2 => $v2)
	{
		if ($v2==0)
		{
			$cnt++;
		}
	}
	
	//print_r($rin_ar);

	return $cnt;
}

function getbidinfo($dbid,$bidamt)
{
	//global $viewarray;
	$viewarray	=$_SESSION['viewarray'];
	$out=array();
	$bidvar=0;

	if ($_SESSION['action']=="est")
	{
		$qry2 = "SELECT estid,bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid=".$_SESSION['estid']." AND bidaccid='".$dbid."';";
		//$bidamt=0;
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qry2 = "SELECT bidinfo,bidamt FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd='".$viewarray['jadd']."' AND dbid='".$dbid."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qry2 = "SELECT bidinfo,bidamt FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd='".$viewarray['jadd']."' AND dbid='".$dbid."';";
	}

	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($viewarray['jadd'] >= 1)
	{
		$newjadd=$viewarray['jadd']-1;
		if ($_SESSION['action']=="contract")
		{
			$qry3 = "SELECT bidamt FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd='".$newjadd."' AND dbid='".$dbid."';";
		}
		elseif ($_SESSION['action']=="job")
		{
			$qry3 = "SELECT bidamt FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd='".$newjadd."' AND dbid='".$dbid."';";
		}

		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);

		if ($row2['bidamt']!=$row3['bidamt'])
		{
			if ($row3['bidamt'] <= 0)
			{
				$bidvar=$row3['bidamt']+$row2['bidamt'];
			}
			else
			{
				$bidvar=$row3['bidamt']-$row2['bidamt'];
			}
		}
	}

	//echo "\n".$row2['bidinfo']."\n";

	$out	=array($row2['bidinfo'],$bidamt,$bidvar);
	return $out;
}

function get_def_calc_amt($qtype,$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$a1,$a2,$a3)
{
	$quan_out=0;
	$out=array(0=>0,1=>$q1,2=>$q2,3=>$q3,4=>$q4,5=>$q5,6=>$q6);

	$iarea		=calc_internal_area($q1,$q2,$q3,$q4,$q5);
	$gallons		=calc_gallons($q1,$q2,$q3,$q4,$q5);

	//echo $iarea;

	if (!isset($quan))
	{
		if ($q8===0)
		{
			$quan=1;
		}
		else
		{
			$quan=$q8;
		}
	}

	if ($qtype==1 || $qtype==33 || $qtype==77) // Fixed - Quantity - Bid Item
	{
		$quan_out	=$quan;
	}
	elseif ($qtype==2) // Quantity
	{
		$quan_out	=$quan;
	}
	elseif ($qtype==3) // PFT
	{
		$quan_out	=$q1;
	}
	elseif ($qtype==4) // SQFT
	{
		$quan_out	=$q2;
	}
	elseif ($qtype==5) // Base+ (PFT)
	{
		$quan_out	=$q1;
	}
	elseif ($qtype==6) // Base+ (SQFT)
	{
		$quan_out	=$q2;
	}
	elseif ($qtype==7) // Base+ (IA)
	{
		$quan_out=$iarea;
	}
	elseif ($qtype==8) // Base+ (Fixed)
	{
		$quan_out=1;
	}
	elseif ($qtype==9) // Bracket (PFT)
	{
		$quan_out=$q1;
	}
	elseif ($qtype==10) // Bracket (SQFT)
	{
		$quan_out=$q2;
	}
	elseif ($qtype==11) // Bracket (IA)
	{
		$quan_out=$iarea;
	}
	elseif ($qtype==12) // Bracket (Gallons)
	{
		$quan_out=$gallons;
	}
	elseif ($qtype==13) // Checkbox (PFT)
	{
		$quan_out=$q1;
	}
	elseif ($qtype==14) // Checkbox (SQFT)
	{
		$quan_out=$q2;
	}
	elseif ($qtype==15) // Checkbox (Quantity)
	{
		$quan_out=1;
	}
	elseif ($qtype==16) // Checkbox (IA)
	{
		$quan_out=$iarea;
	}
	elseif ($qtype==17) // Checkbox (Gallons)
	{
		$quan_out=$gallons;
	}
	elseif ($qtype==18) // Code (PFT)
	{
		$quan_out=$q1;
	}
	elseif ($qtype==19) // Code (SQFT)
	{
		$quan_out=$q2;
	}
	elseif ($qtype==20) // Code (Quantity)
	{
		$quan_out=1;
	}
	elseif ($qtype==21) // Code (IA)
	{
		$quan_out=$iarea;
	}
	elseif ($qtype==22) // Code (Gallons)
	{
		$quan_out=$gallons;
	}
	elseif ($qtype==23) // Code (Checkbox)
	{
		$quan_out=1;
	}
	elseif ($qtype==45) // Peri Deck Incl(Cost is Base+)
	{
		$quan_out=$q1*2.16;
	}
	elseif ($qtype==46) // IA (Div by CalcAmt)
	{
		$quan_out=$iarea;
	}
	elseif ($qtype==47) // IA (Mult by CalcAmt)
	{
		$quan_out=$iarea;
	}
	elseif ($qtype==48) // Base Inclusion (Quantity)
	{
		$quan_out=1;
	}
	elseif ($qtype==53) // Permit
	{
		$quan_out=1;
	}
	elseif ($qtype==54) // Referral
	{
		$quan_out=1;
	}
	elseif ($qtype==55 || $qtype==72) // Package (Quantity) - Package (Checkbox)
	{
		$quan_out=1;
	}
	elseif ($qtype==56) // IA (Div by CalcAmt) Base+
	{
		if ($iarea > $a2)
		{
			$calc1=$a2/$q6;
			$calc2=($iarea-$a2)/$q6;
			$quan_out=$calc1+$calc2;
		}
		else
		{
			$calc=$iarea/$q6;
			$quan_out=$calc;
		}
	}
	elseif ($qtype==57) // Gallons (Total)
	{
		$quan_out=$gallons;
	}
	elseif ($qtype==58) // Base+ (Quantity)
	{
		$quan_out=$q8;
	}
	elseif ($qtype==59) // Elec Run (Total)
	{
		$quan_out=$erun;
	}
	elseif ($qtype==60) // Plumb Run (Total)
	{
		$quan_out=$prun;
	}
	elseif ($qtype==61) // SPA PFT
	{
		$quan_out=$spa_pft;
	}
	elseif ($qtype==62) // SPA SQFT
	{
		$quan_out=$spa_sqft;
	}
	elseif ($qtype==63) // SPA IA
	{
		$quan_out=$spa_iarea;
	}
	elseif ($qtype==64) // SPA Gallons
	{
		$quan_out=$spa_gallons;
	}
	elseif ($qtype==65) // SPA PFT Base+ (Quantity)
	{
		$quan_out=$spa_pft;
	}
	elseif ($qtype==66) // SPA SQFT Base+ (Quantity)
	{
		$quan_out=$spa_sqft;
	}
	elseif ($qtype==67) // SPA IA Base+ (Quantity)
	{
		$quan_out=$spa_iarea;
	}
	elseif ($qtype==68) // SPA IA Base+ (Quantity)
	{
		$quan_out=$spa_gallons;
	}
	elseif ($qtype==69) // Base+ (Depth)
	{
		$quan_out=$q5;
	}
	elseif ($qtype==70) // Depth
	{
		$quan_out=$q5;
	}
	elseif ($qtype==71) // Deck (Total Base+)
	{
		$quan_out=500;
	}
	elseif ($qtype==73) // Peri (Div by CalcAmt)
	{
		$quan_out=$q1/$q6;
	}
	elseif ($qtype==74) // SA (Div by CalcAmt)
	{
		$quan_out=$q2/$q6;
	}
	elseif ($qtype==75) // Peri (Div by CalcAmt) Base +
	{
		if ($q1 > $a2)
		{
			$calc1=$a2/$def_quan;
			$calc2=($q1-$a2)/$q6;
			$quan_out=$calc1+$calc2;
		}
		else
		{
			$calc=$q1/$def_quan;
			$quan_out=$calc;
		}
	}
	elseif ($qtype==76) // SA (Div by CalcAmt) Base +
	{
		if ($q2 > $a2)
		{
			$calc1=$a2/$q6;
			$calc2=($q2-$a2)/$q6;
			$quan_out=$calc1+$calc2;
		}
		else
		{
			$calc=$q2/$q6;
			$quan_out=$calc;
		}
	}

	
	$out=array(0=>$quan_out,1=>$q1,2=>$q2,3=>$q3,4=>$q4,5=>$q5,6=>$q6,7=>$iarea,8=>$gallons);
	
	//print_r($out);
	//echo "<br>";
	return $out;
}

function get_adj_amt($phsid)
{
	//global $viewarray;
	$viewarray	=$_SESSION['viewarray'];
	$dout=0;
	if ($_SESSION['action']=="contract")
	{
		$qry0	= "SELECT manphscostadj FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qry0	= "SELECT manphscostadj FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
	}
	$res0	= mssql_query($qry0);
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
				//echo "PHS:ADJ (".$phsid.":".$dout.")<br>";
			}
		}
	}
	//echo "ADJ: ".$dout."<br>";
	return $dout;
}

function calc_ou($adjctramt,$adjcomm,$tbullets,$offbullets,$applyou,$comadj,$bullet_rate,$over_split)
{
	// Over/Under Application *** Check for Number of Bullets then Overage Amt. ***
	/*
	echo "AJ: ".$adjctramt."<br>";
	echo "AC: ".$adjcomm."<br>";
	echo "TB: ".$tbullets."<br>";
	echo "OB: ".$offbullets."<br>";
	echo "AV: ".$applyou."<br>";
	echo "CA: ".$comadj."<br>";
	echo "BR: ".$bullet_rate."<br>";
	echo "OS: ".$over_split."<br>";
	*/
	
	if ($tbullets >= $offbullets) // Bullet Adjustment
	{
		if ($applyou==1)
		{
			if ($comadj=="0.00")
			{
				$oucomm		=$adjctramt*$over_split;
			}
			else
			{
				$oucomm		=$comadj;
				$adjcomm		=$adjcomm+$oucomm;
			}
		}
		else
		{
			if ($comadj=="0.00" && $_SESSION['clev'] < 4)
			{
				$oucomm		="0.00";
			}
			elseif ($comadj=="0.00" && $_SESSION['clev'] >= 4)
			{
				$oucomm		=$adjctramt*$over_split;
			}
			else
			{
				$oucomm		=$comadj;
				$adjcomm	=$adjcomm+$oucomm;
			}
		}
	}
	elseif ($adjctramt > $bullet_rate)
	{
		if ($applyou==1)
		{
			if ($comadj=="0.00")
			{
				$presplit	=$adjctramt-$bullet_rate;
				$oucomm		=$presplit*$over_split;
			}
			else
			{
				$oucomm		=$comadj;
				$adjcomm	=$adjcomm+$oucomm;
			}
		}
		else
		{
			if ($comadj=="0.00" && $_SESSION['clev'] < 4)
			{
				$oucomm		="0.00";
			}
			elseif ($comadj=="0.00" && $_SESSION['clev'] >= 4)
			{
				$presplit	=$adjctramt-$bullet_rate;
				$oucomm		=$presplit*$over_split;
			}
			else
			{
				$oucomm		=$comadj;
				$adjcomm	=$adjcomm+$oucomm;
			}
		}
	}
	elseif ($adjctramt < 0)
	{
		if ($applyou==1)
		{
			if ($comadj=="0.00")
			{
				$oucomm		=$adjctramt*$over_split;
			}
			else
			{
				$oucomm		=$comadj;
				$adjcomm		=$adjcomm+$oucomm;
			}
		}
		else
		{
			if ($comadj=="0.00" && $_SESSION['clev'] < 4)
			{
				$oucomm		="0.00";
			}
			elseif ($comadj=="0.00" && $_SESSION['clev'] >= 4)
			{
				$oucomm		=$adjctramt*$over_split;
			}
			else
			{
				$oucomm		=$comadj;
				$adjcomm	=$adjcomm+$oucomm;
			}
		}
	}
	else
	{
		if ($applyou==1)
		{
			if ($comadj=="0.00")
			{
				$oucomm	=0;
			}
			else
			{
				$oucomm		=$comadj;
				$adjcomm	=$adjcomm+$oucomm;
			}
		}
		else
		{
			$oucomm	=0;
		}
	}
	
	//echo "CALCOUT: ".$oucomm."<br>";
	//echo "CALCOUT: ".$adjcomm."<br>";
	//echo "-------------<br>";
	$ou=array(0=>$oucomm,1=>$adjcomm);
	return $ou;
}

function calc_royalty_est($costitems)
{
	$MAS=$_SESSION['pb_code'];
	//global $viewarray;
	$viewarray	=$_SESSION['viewarray'];

	$subctr	=0;
	$subroyt	=0;
	$subroyn	=0;
	$phsid	=8;

	/*
	if ($viewarray['jadd']!=0)
	{
	$qry0	= "SELECT contractamt,raddnpr_man,raddnroy_man FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd <= '".$viewarray['jadd']."';";
	$res0	= mssql_query($qry0);

	while ($row0 = mssql_fetch_array($res0))
	{
	if ($row0['raddnroy_man']!="1")
	{
	$subroyn	=$subroyn+$row0['raddnpr_man'];
	}
	}
	}
	*/
	//echo "JOBI: ".$viewarray['jobid']."<br>";
	//echo "JADD: ".$viewarray['jadd']."<br>";
	//echo "ROYA: ".$viewarray['royadj']."<br>";
	//echo "ADDT: ".$subroyt."<br>";
	//echo "ADDN: ".$subroyn."<br>";
	//echo "CTRT: ".$subctr."<br>";
	//echo "CTRA: ".$viewarray['camt']."<br>";

	// Pre Calc Royalty Release on Contr Allowance/Bid Item
	if ($costitems[0] > 0)
	{
		//echo "COSTS: ";
		foreach ($costitems as $pre_n=>$pre_v)
		{
			$quan	=$pre_v[1];
			$code	=0;
			$qryB = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND id='".$pre_v[0]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			//echo "QRY: ".$qryB."<BR>";
			//echo "PHS: ".$rowB['phsid']."<BR>";
			//echo "RRL: ".$rowB['royrelease']."<BR>";

			if ($rowB['phsid']==$phsid && $rowB['royrelease']==1)
			{
				//echo "PHS: ".$rowB['phsid']."<BR>";
				//echo "RRL: ".$rowB['royrelease']."<BR>";

				if ($rowB['qtype']==33) // Bid Item
				{
					//echo "<pre>";
					//print_r($pre_v);
					//echo "</pre>";
					$qryC = "SELECT rid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND cid='".$pre_v[0]."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);
					$nrowC= mssql_num_rows($resC);

					//echo "LINKS: ".$qryC." ($nrowC)<br>";

					if ($nrowC > 0)
					{
						//echo "EST TEST";
						if ($_SESSION['action']=="est")
						{
							$qryCa = "SELECT * FROM bid_breakout WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND rdbid='".$pre_v[3]."' AND cdbid='".$pre_v[0]."';";
						}
						elseif ($_SESSION['action']=="contract")
						{
							$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND rdbid='".$pre_v[0]."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND rdbid='".$pre_v[0]."';";
						}
						$resCa = mssql_query($qryCa);
						$nrowCa= mssql_num_rows($resCa);

						//echo "BRKS: ".$qryCa."<br>";

						if ($nrowCa > 0)
						{
							$subbp=0;

							$qryCb = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$pre_v[3]."';";
							$resCb = mssql_query($qryCb);
							$rowCb = mssql_fetch_array($resCb);

							while($rowCa = mssql_fetch_array($resCa))
							{
								//echo "BRKS: ".$qryCa."<br>";
								$bp=$rowCa['bprice'];
								$subbp=$subbp+$bp;
							}

						}
						else
						{
							//$qryD = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$rowC['rid']."';";
							$qryD = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$pre_v[3]."';";
							$resD = mssql_query($qryD);
							$rowD = mssql_fetch_array($resD);

							$qryE = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
							$resE = mssql_query($qryE);
							$rowE = mssql_fetch_array($resE);

							//echo "NBRKS: ".$qryD."<br>";

							$Xarray=explode(",",$rowE['estdata']);
							foreach ($Xarray as $n=>$v)
							{
								$subXarray=explode(":",$v);
								//if ($subXarray[0]==$rowC['rid'])
								if ($subXarray[0]==$pre_v[3])
								{
									$Xbp=$subXarray[3];
								}
							}

							$subbp=$Xbp;
						}

						if ($rowB['royrelease']==1)
						{
							$viewarray['royrel']=$subbp;
							//echo "ROYR: ".$viewarray['royrel']."<BR>";
						}
					}
				}
			}
		}
	}

	if ($viewarray['camt'] != 0)
	{
		$royalty		=($viewarray['camt']-$viewarray['royrel'])*.03;
	}
	else
	{
		$royalty		=0;
	}

	$royalty=round($royalty);
	return $royalty;
}

function calc_royalty_job($costitems)
{
	$MAS=$_SESSION['pb_code'];
	//global $viewarray;
	$viewarray	=$_SESSION['viewarray'];

	$subctr	=0;
	$subroyt	=0;
	$subroyn	=0;
	$phsid	=8;
	$viewarray['royrel']=0;

	/* Removed ... No Royalty Calc on Addendums per Ron 4/6/05
	if ($_SESSION['action']=="contract")
	{
	$qry0	= "SELECT contractamt,raddnpr_man,raddnroy_man FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd > '0';";
	}
	elseif ($_SESSION['action']=="job")
	{
	$qry0	= "SELECT contractamt,raddnpr_man,raddnroy_man FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd > '0';";
	}
	$res0	= mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
	while ($row0 = mssql_fetch_array($res0))
	{
	$subroyn	=$subroyn+$row0['raddnpr_man'];
	}
	}
	*/

	$costitems=preg_replace("/,\Z/","",$costitems);
	if ($costitems > 0)
	{
		//echo "COSTS: ";
		//print_r($costitems);
		$edata=explode(",",$costitems);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			//echo "IV: ".$pre_iv."<br>";
			$pre_v=explode(":",$pre_iv);
			//echo "<pre>";
			//print_r($pre_v);
			//echo "</pre>";

			$rid     =$pre_v[0];
			$cid     =$pre_v[1];
			$quan		=$pre_v[2];
			$cost		=$pre_v[3];
			$qtype	=$pre_v[4];
			$code		=$pre_v[5];
			$lrange	=$pre_v[6];
			$hrange	=$pre_v[7];
			$iphsid  =$pre_v[8];
			$rinvid  =$pre_v[9];
			$quancalc=$pre_v[10];

			$qryB = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$iphsid."' AND id='".$pre_v[1]."' AND baseitem!=1";
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
					$qryC = "SELECT rid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND cid='".$pre_v[1]."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);
					$nrowC= mssql_num_rows($resC);

					if ($nrowC > 0)
					{
						if ($_SESSION['action']=="contract")
						{
							$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND rdbid='".$pre_v[0]."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND rdbid='".$pre_v[0]."';";
						}
						$resCa = mssql_query($qryCa);
						$nrowCa= mssql_num_rows($resCa);

						//echo "JBIDS: ".$qryCa."<BR>";

						if ($nrowCa > 0)
						{
							$subbp=0;
							while($rowCa = mssql_fetch_array($resCa))
							{
								$bp=$rowCa['bprice'];
								$subbp=$subbp+$bp;
							}
							//echo ":WITH";
							//$bc=$bc+$subbp;
						}
						else
						{
							if ($_SESSION['action']=="contract")
							{
								$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND dbid='".$pre_v[0]."';";
							}
							elseif ($_SESSION['action']=="job")
							{
								$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND dbid='".$pre_v[0]."';";
							}

							$resD = mssql_query($qryD);
							$rowD = mssql_fetch_array($resD);

							if ($_SESSION['action']=="contract")
							{
								$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd='".$viewarray['jadd']."';";
							}
							elseif ($_SESSION['action']=="job")
							{
								$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd='".$viewarray['jadd']."';";
							}

							$resE = mssql_query($qryE);
							$rowE = mssql_fetch_array($resE);

							$Xarray=explode(",",$rowE['estdata']);
							foreach ($Xarray as $n=>$v)
							{
								$subXarray=explode(":",$v);
								//if ($subXarray[0]==$rowC['rid'])
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
							$viewarray['royrel']=$subbp;
							//echo "ROYR: ".$viewarray['royrel']."<BR>";
						}
					}
				}
			}
		}
	}

	//if ($nrow0!=0)
	//{
	//	echo "WITH JADD<br>";
	//	echo "SUB: ".$subroyn."<br>";
	//	$royalty		=(($viewarray['camt']+$subroyn)-$viewarray['royrel'])*.03;
	//}
	//else
	//{
	//	echo "WITHOUT JADD<br>";
	$royalty		=($viewarray['camt']-$viewarray['royrel'])*.03;
	//}

	$royalty=round($royalty);
	return $royalty;
}

function select_base_pool()
{
	$viewarray=$_SESSION['viewarray'];
	$qrypre	= "SELECT pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre	= mssql_query($qrypre);
	$rowpre	= mssql_fetch_array($respre);

	if ($rowpre['pft_sqft']=="p")
	{
		$psize=$viewarray['ps1'];
		$ptext="pft";
	}
	else
	{
		$psize=$viewarray['ps2'];
		$ptext="sqft";
	}

	if ($viewarray['renov']==1)
	{
		$rbtable="rbpricep_renov";
	}
	else
	{
		$rbtable="rbpricep";
	}

	$qry	= "SELECT SUM(quan1) as quan1t FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."';";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);

	if ($row['quan1t'] > 0)
	{
		$bi	=0;
		$bq	=0;
		$bq1	=0;
		$bp	=0;
		$bc	=0;

		$qry1	= "SELECT * FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
		$res1	= mssql_query($qry1);

		while ($row1 = mssql_fetch_array($res1))
		{
			if ($psize >= $row1['quan'] && $psize <= $row1['quan1'])
			{
				//echo "HIT";
				$bi	=$row1['id'];
				$bq	=$row1['quan'];
				$bq1	=$row1['quan1'];
				$bp	=$row1['price'];
				$bc	=$row1['comm'];
			}
		}
	}
	else
	{
		$qry1		= "SELECT * FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."' and quan='".$psize."';";
		$res1		= mssql_query($qry1);
		$row1 	= mssql_fetch_array($res1);
		$nrow1 	= mssql_num_rows($res1);

		if ($nrow1 > 0)
		{
			$bi	=$row1['id'];
			$bq	=$row1['quan'];
			$bq1	=$row1['quan1'];
			$bp	=$row1['price'];
			$bc	=$row1['comm'];
		}
		else
		{
			$bi	=0;
			$bq	=0;
			$bq1	=0;
			$bp	=0;
			$bc	=0;
		}
	}

	$bpar=array(0=>$bi,1=>$bq,2=>$bq1,3=>$bp,4=>$bc,5=>$psize,6=>$ptext,7=>$row['quan1t']);
	return $bpar;
}

function estAdata_init()
{
	//print_r($_POST);
	// aaaa = item id
	// bbba = quantity
	// ccca = Future Use
	// ddda = Retail Price
	// eeea = Bid Items (Not incuded in data store)
	// fffa = Qtype
	// ggga = Comm Type
	// hhha = Comm Rate
	// iiia = Quan calc

	$icount=0;
	if (is_array($_POST))
	{
		$estout='';
		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,4)=="bbba")
			{
				$asid=substr($n,4);
				if ($_POST['fffa'.$asid]!=32)
				{
					if ($_POST['bbba'.$asid] > 0)
					{
						$icount++;
					}
				}
			}
		}

		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,4)=="bbba")
			{
				$asid=substr($n,4);
				if ($_POST['fffa'.$asid]!=32)
				{
					if ($_POST['bbba'.$asid] > 0)
					{
						if (array_key_exists("aaaa".$asid,$_POST))
						{
							if (array_key_exists("ccca".$asid,$_POST))
							{
								if (array_key_exists("ddda".$asid,$_POST))
								{
									if (array_key_exists("ggga".$asid,$_POST))
									{
										if (array_key_exists("hhha".$asid,$_POST))
										{
											if (array_key_exists("iiia".$asid,$_POST))
											{
												if (array_key_exists("code".$asid,$_POST))
												{
													$code=$_POST['code'.$asid];
												}
												else
												{
													$code=0;
												}
	
												if ($icount==1)
												{
													//$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code;
													$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.':'.$_POST['ggga'.$asid].':'.$_POST['hhha'.$asid].':'.$_POST['iiia'.$asid];
												}
												else
												{
													//$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.',';
													$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.':'.$_POST['ggga'.$asid].':'.$_POST['hhha'.$asid].':'.$_POST['iiia'.$asid].',';
												}
												//echo $estd."<br>";
												$estout=$estout.$estd;
												$icount--;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
    
    /*if (isset($_REQUEST['esttype']) && $_REQUEST['esttype']=='Q')
	{
        $estout=','.autoadditems();
	}*/
    
	return $estout;
}

function estAdata_quote()
{
    $estout='';
    
	//print_r($_POST);
	// aaaa = item id
	// bbba = quantity
	// ccca = Future Use
	// ddda = Retail Price
	// eeea = Bid Items (Not incuded in data store)
	// fffa = Qtype
	// ggga = Comm Type
	// hhha = Comm Rate
	// iiia = Quan calc

    if (isset($_REQUEST['estis']))
    {
        //$cnt=count($_REQUEST['estis']);
        
        //echo $cnt.'<br>';
        //echo '<pre>';
        
        foreach ($_REQUEST['estis'] as $n => $v)
        {
            if (isset($v['quan']) && $v['quan']!=0)
            {
                //print_r($v);
                $estd=$v['id'].':'.$v['dbrp'].':'.$v['quan'].':'.$v['rp'].'::'.$v['commtype'].':'.$v['crate'].':'.$v['quan_calc'].',';
                //$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.':'.$_POST['ggga'.$asid].':'.$_POST['hhha'.$asid].':'.$_POST['iiia'.$asid];
                $estout=$estout.$estd;
            }
            //$cnt--;
        }
        
        //echo $cnt.'<br>';
        //echo '</pre>';
        $estout=preg_replace('/,$/i','',$estout);
        //echo $estout.'<br>';
    }

	/*$icount=0;
	if (is_array($_POST))
	{
		$estout='';
		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,4)=="bbba")
			{
				$asid=substr($n,4);
				if ($_POST['fffa'.$asid]!=32)
				{
					if ($_POST['bbba'.$asid] > 0)
					{
						$icount++;
					}
				}
			}
		}

		foreach ($_POST as $n=>$v)
		{
			if (substr($n,0,4)=="bbba")
			{
				$asid=substr($n,4);
				if ($_POST['fffa'.$asid]!=32)
				{
					if ($_POST['bbba'.$asid] > 0)
					{
						if (array_key_exists("aaaa".$asid,$_POST))
						{
							if (array_key_exists("ccca".$asid,$_POST))
							{
								if (array_key_exists("ddda".$asid,$_POST))
								{
									if (array_key_exists("ggga".$asid,$_POST))
									{
										if (array_key_exists("hhha".$asid,$_POST))
										{
											if (array_key_exists("iiia".$asid,$_POST))
											{
												if (array_key_exists("code".$asid,$_POST))
												{
													$code=$_POST['code'.$asid];
												}
												else
												{
													$code=0;
												}
	
												if ($icount==1)
												{
													//$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code;
													$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.':'.$_POST['ggga'.$asid].':'.$_POST['hhha'.$asid].':'.$_POST['iiia'.$asid];
												}
												else
												{
													//$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.',';
													$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.':'.$_POST['ggga'.$asid].':'.$_POST['hhha'.$asid].':'.$_POST['iiia'.$asid].',';
												}
												//echo $estd."<br>";
												$estout=$estout.$estd;
												$icount--;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}*/
    
    /*if (isset($_REQUEST['esttype']) && $_REQUEST['esttype']=='Q')
	{
        $estout=','.autoadditems();
	}*/
    
	return $estout;
}

function edit_bid_add()
{
	if ($_SESSION['action']=="est")
	{
		$qry 	= "INSERT INTO bid_breakout (officeid,estid,rdbid,cdbid,partno,vendor,sdesc,comments,bprice) ";
		$qry .= "VALUES ('".$_SESSION['officeid']."',";
		$qry .= "'".$_POST['estid']."','".$_POST['rdbid']."',";
		$qry .= "'".$_POST['cdbid']."','".replacequote($_POST['partno'])."',";
		$qry .= "'".replacequote($_POST['vendor'])."','".replacequote($_POST['sdesc'])."',";
		$qry .= "'".replacequote($_POST['comments'])."','".number_format($_POST['bprice'], 2, '.', '')."');";
		$res = mssql_query($qry);
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qry 	= "INSERT INTO jbids_breakout (officeid,jobid,jadd,rdbid,cdbid,partno,vendor,sdesc,comments,bprice) ";
		$qry .= "VALUES ('".$_SESSION['officeid']."',";
		$qry .= "'".$_POST['jobid']."','0','".$_POST['rdbid']."',";
		$qry .= "'".$_POST['cdbid']."','".replacequote($_POST['partno'])."',";
		$qry .= "'".replacequote($_POST['vendor'])."','".replacequote($_POST['sdesc'])."',";
		$qry .= "'".replacequote($_POST['comments'])."','".number_format($_POST['bprice'], 2, '.', '')."');";
		$res = mssql_query($qry);
	}
	//$row = mssql_fetch_array($res);
	//echo $qry;
	edit_bid();
}

function edit_bid_update()
{
	if ($_SESSION['action']=="est")
	{
		$qry  = "UPDATE bid_breakout SET partno='".replacequote($_POST['partno'])."',";
		$qry .= "vendor='".replacequote($_POST['vendor'])."',sdesc='".replacequote($_POST['sdesc'])."',";
		$qry .= "comments='".$_POST['comments']."',bprice='".number_format($_POST['bprice'], 2, '.', '')."'";
		$qry .= " WHERE officeid='".$_SESSION['officeid']."' and id='".$_POST['bbid']."';";
		$res  = mssql_query($qry);
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qry = "UPDATE jbids_breakout SET partno='".replacequote($_POST['partno'])."',";
		$qry .= "vendor='".replacequote($_POST['vendor'])."',sdesc='".replacequote($_POST['sdesc'])."',";
		$qry .= "comments='".replacequote($_POST['comments'])."',bprice='".number_format($_POST['bprice'], 2, '.', '')."'";
		$qry .= " WHERE officeid='".$_SESSION['officeid']."' and id='".$_POST['bbid']."';";
		$res = mssql_query($qry);
	}

	edit_bid();
}

function edit_bid_delete()
{
	if ($_SESSION['action']=="est")
	{
		$qry = "DELETE FROM bid_breakout WHERE officeid='".$_SESSION['officeid']."' AND id='".$_POST['bbid']."';";
		$res = mssql_query($qry);
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qry = "DELETE FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND id='".$_POST['bbid']."';";
		$res = mssql_query($qry);
	}

	edit_bid();
}

function edit_bid()
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$qry = "SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($_SESSION['action']=="est")
	{
		$qryA = "SELECT cid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_POST['estid']."';";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qryA = "SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_POST['jobid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryA = "SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_POST['njobid']."';";
	}
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_row($resA);

	$icid=$rowA[0];

	$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$icid."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryC = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_POST['costid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	if ($_SESSION['action']=="est")
	{
		$qryD = "SELECT * FROM bid_breakout WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_POST['estid']."' AND rdbid='".$_POST['rdbid']."' AND cdbid='".$_POST['cdbid']."';";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qryD = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_POST['jobid']."' AND rdbid='".$_POST['rdbid']."' AND cdbid='".$_POST['cdbid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryD = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_POST['njobid']."' AND rdbid='".$_POST['rdbid']."' AND cdbid='".$_POST['cdbid']."';";
	}
	$resD = mssql_query($qryD);
	$nrowD= mssql_num_rows($resD);

	if ($_SESSION['action']=="est")
	{
		$qryE = "SELECT * FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_POST['estid']."' AND bidaccid='".$_POST['rdbid']."';";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qryE = "SELECT * FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_POST['jobid']."' AND dbid='".$_POST['rdbid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryE = "SELECT * FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_POST['njobid']."' AND dbid='".$_POST['rdbid']."';";
	}
	$resE = mssql_query($qryE);
	$rowE = mssql_fetch_array($resE);

	if ($_SESSION['action']=="est")
	{
		$bidinfo=$rowE['bidinfo'];
	}
	elseif ($_SESSION['action']=="contract")
	{
		$bidinfo=$rowE['bidinfo'];
	}
	elseif ($_SESSION['action']=="job")
	{
		$bidinfo=$rowE['bidinfo'];
	}

	$qryF = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_POST['rdbid']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	//echo "<pre>";
	//echo $viewarray['camt'];
	//echo "</pre>";

	//echo "TEST";

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<tr>\n";

	if ($_SESSION['action']=="est")
	{
		echo "								<td align=\"left\" valign=\"bottom\"><b>Estimate:</b> <font color=\"red\"><b>".$_POST['estid']."</b></font></td>\n";
	}
	elseif ($_SESSION['action']=="contract")
	{
		echo "								<td align=\"left\" valign=\"bottom\"><b>Contract:</b> <font color=\"red\"><b>".$_POST['jobid']."</b></fony></td>\n";
	}
	elseif ($_SESSION['action']=="job")
	{
		echo "								<td align=\"left\" valign=\"bottom\"><b>Job:</b> <font color=\"red\"><b>".$_POST['njobid']."</b></font></td>\n";
	}

	echo "								<td align=\"left\" valign=\"bottom\"><b>Customer:</b> <input type=\"text\" class=\"bboxl\" value=\"".$rowB['clname'].", ".$rowB['cfname']."\" DISABLED></td>\n";
	echo "								<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "								<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "								<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";

	if ($_SESSION['action']=="est")
	{
		echo "								<input type=\"hidden\" name=\"estid\" value=\"".$_POST['estid']."\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	}
	elseif ($_SESSION['action']=="contract")
	{
		echo "								<input type=\"hidden\" name=\"jobid\" value=\"".$_POST['jobid']."\">\n";
		echo "								<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	}
	elseif ($_SESSION['action']=="job")
	{
		echo "								<input type=\"hidden\" name=\"njobid\" value=\"".$_POST['njobid']."\">\n";
		echo "								<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	}

	echo "								<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
	echo "								<input type=\"hidden\" name=\"tcontract\" value=\"".$_POST['tcontract']."\">\n";
	echo "								<input type=\"hidden\" name=\"tretail\" value=\"".$_POST['tretail']."\">\n";
	echo "								<input type=\"hidden\" name=\"tcomm\" value=\"".$_POST['tcomm']."\">\n";
	echo "								<input type=\"hidden\" name=\"acctotal\" value=\"".$_POST['acctotal']."\">\n";
	echo "								<td align=\"right\" valign=\"bottom\">\n";
	echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Return\">\n";
	echo "								</td>\n";
	echo "								</form>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"bottom\"><b>Bid Cost Breakdown:</b></td>\n";
	echo "								<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "								<td align=\"left\" valign=\"bottom\"></td>\n";
	echo "								<td align=\"left\" valign=\"bottom\"></td>\n";
	echo "								<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "								<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "								<td align=\"left\" valign=\"bottom\"><b>Cost Item</b></td>\n";
	echo "								<td align=\"left\" valign=\"bottom\"><b>Retail Association</b></td>\n";
	echo "								<td align=\"left\" valign=\"bottom\"><b>Bid Detail</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"bottom\"></td>\n";
	echo "								<td align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" value=\"".$rowC['item']."\" DISABLED></td>\n";
	echo "								<td align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" value=\"".$rowF['item']."\" DISABLED></td>\n";
	echo "								<td align=\"right\" valign=\"bottom\">&nbsp&nbsp&nbsp<b>".$bidinfo."</b></td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Phase</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Cost Item</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Part #</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Vendor</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Name</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Description</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Price</b></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
	echo "							<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";

	if ($nrowD > 0)
	{
		while ($rowD = mssql_fetch_array($resD))
		{
			$qryDa = "SELECT * FROM phasebase WHERE phsid='".$rowC['phsid']."';";
			$resDa = mssql_query($qryDa);
			$rowDa = mssql_fetch_array($resDa);

			echo "				<tr>\n";
			echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
			echo "						<input type=\"hidden\" name=\"tretail\" value=\"".$_POST['tretail']."\">\n";
			echo "						<input type=\"hidden\" name=\"bbid\" value=\"".$rowD['id']."\">\n";
			echo "						<input type=\"hidden\" name=\"rdbid\" value=\"".$rowD['rdbid']."\">\n";
			echo "						<input type=\"hidden\" name=\"cdbid\" value=\"".$rowD['cdbid']."\">\n";
			echo "						<input type=\"hidden\" name=\"costid\" value=\"".$rowD['cdbid']."\">\n";

			if ($_SESSION['action']=="est")
			{
				echo "								<input type=\"hidden\" name=\"estid\" value=\"".$_POST['estid']."\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			}
			elseif ($_SESSION['action']=="contract")
			{
				echo "								<input type=\"hidden\" name=\"jobid\" value=\"".$_POST['jobid']."\">\n";
				echo "								<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			}
			elseif ($_SESSION['action']=="job")
			{
				echo "								<input type=\"hidden\" name=\"njobid\" value=\"".$_POST['njobid']."\">\n";
				echo "								<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			}

			echo "						<input type=\"hidden\" name=\"call\" value=\"edit_bid_update\">\n";
			echo "						<input type=\"hidden\" name=\"tcomm\" value=\"".$_POST['tcomm']."\">\n";
			echo "						<input type=\"hidden\" name=\"tcontract\" value=\"".$_POST['tcontract']."\">\n";
			echo "						<input type=\"hidden\" name=\"acctotal\" value=\"".$_POST['acctotal']."\">\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>".$rowDa['phsname']."</b></td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>".$rowC['item']."</b></td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bboxl\" name=\"partno\" value=\"".$rowD['partno']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bboxl\" name=\"vendor\" value=\"".$rowD['vendor']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bboxl\" name=\"sdesc\" value=\"".$rowD['sdesc']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<textarea name=\"comments\" cols=\"30\" rows=\"2\">".$rowD['comments']."</textarea>\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input type=\"text\" class=\"bbox\" name=\"bprice\" value=\"".$rowD['bprice']."\" size=\"20\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";

			if ($_SESSION['action']!="job")
			{
				echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\">\n";
			}

			echo "					</td>\n";
			echo "						</form>\n";
			echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
			echo "						<input type=\"hidden\" name=\"bbid\" value=\"".$rowD['id']."\">\n";
			echo "						<input type=\"hidden\" name=\"rdbid\" value=\"".$rowD['rdbid']."\">\n";
			echo "						<input type=\"hidden\" name=\"cdbid\" value=\"".$rowD['cdbid']."\">\n";
			echo "						<input type=\"hidden\" name=\"costid\" value=\"".$rowD['cdbid']."\">\n";
			//echo "						<input type=\"hidden\" name=\"id\" value=\"".$_POST['id']."\">\n";
			//echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			if ($_SESSION['action']=="est")
			{
				echo "								<input type=\"hidden\" name=\"estid\" value=\"".$_POST['estid']."\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			}
			elseif ($_SESSION['action']=="contract")
			{
				echo "								<input type=\"hidden\" name=\"jobid\" value=\"".$_POST['jobid']."\">\n";
				echo "								<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
			}
			elseif ($_SESSION['action']=="job")
			{
				echo "								<input type=\"hidden\" name=\"njobid\" value=\"".$_POST['njobid']."\">\n";
				echo "								<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"job\">\n";
			}

			echo "						<input type=\"hidden\" name=\"tcomm\" value=\"".$_POST['tcomm']."\">\n";
			echo "						<input type=\"hidden\" name=\"tcontract\" value=\"".$_POST['tcontract']."\">\n";
			echo "						<input type=\"hidden\" name=\"tretail\" value=\"".$_POST['tretail']."\">\n";
			echo "						<input type=\"hidden\" name=\"acctotal\" value=\"".$_POST['acctotal']."\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"edit_bid_delete\">\n";
			echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
			echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete\">\n";
			echo "					</td>\n";
			echo "						</form>\n";
			echo "				</tr>\n";
		}
	}

	if ($_SESSION['action']!="job")
	{
		echo "				<tr>\n";
		echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "						<input type=\"hidden\" name=\"rdbid\" value=\"".$_POST['rdbid']."\">\n";
		echo "						<input type=\"hidden\" name=\"cdbid\" value=\"".$_POST['costid']."\">\n";
		echo "						<input type=\"hidden\" name=\"costid\" value=\"".$_POST['costid']."\">\n";
		//echo "						<input type=\"hidden\" name=\"id\" value=\"".$_POST['id']."\">\n";
		//echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		if ($_SESSION['action']=="est")
		{
			echo "								<input type=\"hidden\" name=\"estid\" value=\"".$_POST['estid']."\">\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		}
		elseif ($_SESSION['action']=="contract")
		{
			echo "								<input type=\"hidden\" name=\"jobid\" value=\"".$_POST['jobid']."\">\n";
			echo "								<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		}

		echo "						<input type=\"hidden\" name=\"tcomm\" value=\"".$_POST['tcomm']."\">\n";
		echo "						<input type=\"hidden\" name=\"tretail\" value=\"".$_POST['tretail']."\">\n";
		echo "						<input type=\"hidden\" name=\"tcontract\" value=\"".$_POST['tcontract']."\">\n";
		echo "						<input type=\"hidden\" name=\"acctotal\" value=\"".$_POST['acctotal']."\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"edit_bid_add\">\n";
		echo "					<td colspan=\"2\" class=\"wh_und\" align=\"left\" valign=\"bottom\"><b>Add New Item:</b></td>\n";
		echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
		echo "						<input type=\"text\" class=\"bboxl\" name=\"partno\" size=\"20\">\n";
		echo "					</td>\n";
		echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
		echo "						<input type=\"text\" class=\"bboxl\" name=\"vendor\" size=\"20\">\n";
		echo "					</td>\n";
		echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
		echo "						<input type=\"text\" class=\"bboxl\" name=\"sdesc\" size=\"20\">\n";
		echo "					</td>\n";
		echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
		echo "						<textarea name=\"comments\" cols=\"30\" rows=\"2\"></textarea>\n";
		echo "					</td>\n";
		echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
		echo "						<input type=\"text\" class=\"bbox\" name=\"bprice\" size=\"20\">\n";
		echo "					</td>\n";
		echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add\">\n";
		echo "					</td>\n";
		echo "						</form>\n";
		echo "					<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
		echo "					</td>\n";
		echo "				</tr>\n";
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function edit_mpa_jobmode_add()
{
	error_reporting(E_ALL);
	if ($_SESSION['action']=="est")
	{
		$jfield = "estid";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$jfield = "jobid";
	}
	elseif ($_SESSION['action']=="job")
	{
		$jfield = "njobid";
	}
	
	$jtable = "man_phs_adj";
	
	if ($_SESSION['action']=="est")
	{
		$fjadd = 0;
	}
	else
	{
		$fjadd = $_POST['jadd'];
	}
	
	$qry0 = "SELECT cid,estid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' and ".$jfield."='".$_POST['jid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	//echo $qry0.'<br>';
	
	// Prevents refresh from adding another entry
	$qry1 = "SELECT uid FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' and uid='".$_POST['uid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	
	//echo $qry1.'<br>';
	
	//show_post_vars();
	
	if ($nrow1 == 0)
	{
		if ($_SESSION['action']=="est")
		{
			$qry  = "INSERT INTO man_phs_adj (officeid,estid,jobid,jadd,phsid,rdbid,cdbid,partno,vendor,sdesc,comments,bprice,uid) ";
			$qry .= "VALUES ('".$_SESSION['officeid']."',";
			$qry .= "'".$row0['estid']."','".$row0['jobid']."','".$fjadd."','".$_POST['phsid']."','".$_POST['rdbid']."',";
			$qry .= "'".$_POST['cdbid']."','".replacequote($_POST['partno'])."',";
			$qry .= "'".replacequote($_POST['vendor'])."','".replacequote($_POST['sdesc'])."',";
			$qry .= "'".replacequote($_POST['comments'])."','".number_format($_POST['bprice'], 2, '.', '')."','".$_POST['uid']."');";
			$res  = mssql_query($qry);
			
			//echo $qry.'<br>';
		}
		elseif ($_SESSION['action']=="contract")
		{
			$qry  = "INSERT INTO man_phs_adj (officeid,estid,jobid,jadd,phsid,rdbid,cdbid,partno,vendor,sdesc,comments,bprice,uid) ";
			$qry .= "VALUES ('".$_SESSION['officeid']."',";
			$qry .= "'".$row0['estid']."','".$row0['jobid']."','".$fjadd."','".$_POST['phsid']."','".$_POST['rdbid']."',";
			$qry .= "'".$_POST['cdbid']."','".replacequote($_POST['partno'])."',";
			$qry .= "'".replacequote($_POST['vendor'])."','".replacequote($_POST['sdesc'])."',";
			$qry .= "'".replacequote($_POST['comments'])."','".number_format($_POST['bprice'], 2, '.', '')."','".$_POST['uid']."');";
			$res = mssql_query($qry);
		}
		elseif ($_SESSION['action']=="job")
		{
			$qry  = "INSERT INTO man_phs_adj (officeid,estid,jobid,njobid,jadd,phsid,rdbid,cdbid,partno,vendor,sdesc,comments,bprice,uid) ";
			$qry .= "VALUES ('".$_SESSION['officeid']."',";
			$qry .= "'".$row0['estid']."','".$row0['jobid']."','".$row0['njobid']."','".$fjadd."','".$_POST['phsid']."','".$_POST['rdbid']."',";
			$qry .= "'".$_POST['cdbid']."','".replacequote($_POST['partno'])."',";
			$qry .= "'".replacequote($_POST['vendor'])."','".replacequote($_POST['sdesc'])."',";
			$qry .= "'".replacequote($_POST['comments'])."','".number_format($_POST['bprice'], 2, '.', '')."','".$_POST['uid']."');";
			$res = mssql_query($qry);
		}
	}
	
	if ($_SESSION['action']=="est")
	{
		viewest_cost();
	}
	elseif ($_SESSION['action']=="contract")
	{
		view_job_cost();
	}
	elseif ($_SESSION['action']=="job")
	{
		view_job_cost();
	}
}

function edit_bid_jobmode_add()
{
	error_reporting(E_ALL);
	if ($_SESSION['action']=="est")
	{
		$jfield = "estid";
		$jtable = "bid_breakout";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$jfield = "jobid";
		$jtable = "jbids_breakout";
	}
	elseif ($_SESSION['action']=="job")
	{
		$jfield = "njobid";
		$jtable = "jbids_breakout";
	}
	
	$qry0 = "SELECT cid,estid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' and ".$jfield."='".$_POST['jid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	// Prevents refresh from adding another entry
	$qry1 = "SELECT uid FROM ".$jtable." WHERE officeid='".$_SESSION['officeid']."' and uid='".$_POST['uid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	
	//echo $qry1.'<br>';
	
	//show_post_vars();
	
	if ($nrow1 == 0)
	{
		if ($_SESSION['action']=="est")
		{
			$qry 	= "INSERT INTO bid_breakout (officeid,estid,jobid,jadd,phsid,rdbid,cdbid,partno,vendor,sdesc,comments,bprice,uid) ";
			$qry .= "VALUES ('".$_SESSION['officeid']."',";
			$qry .= "'".$row0['estid']."','".$row0['jobid']."','0','".$_POST['phsid']."','".$_POST['rdbid']."',";
			$qry .= "'".$_POST['cdbid']."','".replacequote($_POST['partno'])."',";
			$qry .= "'".replacequote($_POST['vendor'])."','".replacequote($_POST['sdesc'])."',";
			$qry .= "'".replacequote($_POST['comments'])."','".number_format($_POST['bprice'], 2, '.', '')."','".$_POST['uid']."');";
			$res  = mssql_query($qry);
			
			//echo $qry.'<br>';
		}
		elseif ($_SESSION['action']=="contract")
		{
			$qry 	= "INSERT INTO jbids_breakout (officeid,estid,jobid,jadd,phsid,rdbid,cdbid,partno,vendor,sdesc,comments,bprice,uid) ";
			$qry .= "VALUES ('".$_SESSION['officeid']."',";
			$qry .= "'".$row0['estid']."','".$row0['jobid']."','".$_POST['jadd']."','".$_POST['phsid']."','".$_POST['rdbid']."',";
			$qry .= "'".$_POST['cdbid']."','".replacequote($_POST['partno'])."',";
			$qry .= "'".replacequote($_POST['vendor'])."','".replacequote($_POST['sdesc'])."',";
			$qry .= "'".replacequote($_POST['comments'])."','".number_format($_POST['bprice'], 2, '.', '')."','".$_POST['uid']."');";
			$res = mssql_query($qry);
		}
		elseif ($_SESSION['action']=="job")
		{
			$qry 	= "INSERT INTO jbids_breakout (officeid,estid,jobid,njobid,jadd,phsid,rdbid,cdbid,partno,vendor,sdesc,comments,bprice,uid) ";
			$qry .= "VALUES ('".$_SESSION['officeid']."',";
			$qry .= "'".$row0['estid']."','".$row0['jobid']."','".$row0['njobid']."','".$_POST['jadd']."','".$_POST['phsid']."','".$_POST['rdbid']."',";
			$qry .= "'".$_POST['cdbid']."','".replacequote($_POST['partno'])."',";
			$qry .= "'".replacequote($_POST['vendor'])."','".replacequote($_POST['sdesc'])."',";
			$qry .= "'".replacequote($_POST['comments'])."','".number_format($_POST['bprice'], 2, '.', '')."','".$_POST['uid']."');";
			$res = mssql_query($qry);
		}
	}
	
	if ($_SESSION['action']=="est")
	{
		viewest_cost();
	}
	elseif ($_SESSION['action']=="contract")
	{
		view_job_cost();
	}
	elseif ($_SESSION['action']=="job")
	{
		view_job_cost();
	}
}

function create_old_bid_info($oid,$jid,$jadd)
{
	$MAS	=$_SESSION['pb_code'];
	$ric_ar	=array();
	$rid_ar	=array();
	$rin_ar	=array();
	$rii_ar	=array();
	$qtype	=33;
	
	$qrypreA = "SELECT MAX(jadd) as mjadd FROM jdetail WHERE officeid='".$oid."' and njobid='".$jid."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);
	
	if ($rowpreA['mjadd']==0)
	{
		//$qry = "SELECT oldbidflg,pmasreq,jadd FROM jdetail WHERE officeid='".$oid."' and njobid='".$jid."' and jadd<=".$rowpreA['mjadd']." order by jadd ASC;";
		$qry  = "select ";
		$qry .= "	c.cid,c.njobid,c.mas_prep,j.njobid,j.jadd,j.pmasreq ";
		$qry .= "from ";
		$qry .= "	cinfo as c ";
		$qry .= "inner join ";
		$qry .= "	jdetail as j ";
		$qry .= "on ";
		$qry .= "	c.officeid=j.officeid and ";
		$qry .= "	c.njobid=j.njobid ";
		$qry .= "where ";
		$qry .= "	c.officeid='".$oid."' and c.njobid='".$jid."' and jadd<=".$rowpreA['mjadd']." order by j.jadd ASC;";
		$res = mssql_query($qry);
		$nrow= mssql_num_rows($res);
		
		//echo $qry."<br>";
		//echo $nrow."<br>";
		
		while ($row = mssql_fetch_array($res))
		{		
			//echo "IN<br>";
			//echo $row['oldbidflg']."<br>";
			//echo $row['pmasreq']."<br>";
			if ($row['mas_prep'] >=1 || $row['pmasreq']==1)
			{
				//echo "INNER<br>";
				//$qryA	= "SELECT id,officeid,njobid,jadd,dbid,bidamt FROM jbids WHERE officeid='".$oid."' and njobid='".$jid."' order by jadd asc;";
				$qryA	= "SELECT id,officeid,njobid,jadd,dbid,bidamt FROM jbids WHERE officeid='".$oid."' and njobid='".$jid."' and jadd='".$row['jadd']."';";
				$resA = mssql_query($qryA);
				$nrowA= mssql_num_rows($resA);
				
				//echo $nrowA."<br>";
				//echo $qryA.'<br>';
				if ($nrowA > 0)
				{
					while ($rowA = mssql_fetch_array($resA))
					{
						if (isset($rowA['dbid']) && $rowA['dbid']!=0)
						{
							$qryB  = "select ";
							$qryB .= "	A.qtype as qtype, ";
							$qryB .= "	R.rid as rrid, ";
							$qryB .= "	R.cid as rcid, ";
							$qryB .= "	(select phsid from [".$MAS."accpbook] where officeid='".$oid."' and id=R.cid) as cphsid, ";
							$qryB .= "	A.item as citem ";
							$qryB .= "from  ";
							$qryB .= "	[".$MAS."rclinks_l] as R ";
							$qryB .= "inner join ";
							$qryB .= "	[".$MAS."acc] as A ";
							$qryB .= "on ";
							$qryB .= "	R.rid=A.id ";
							$qryB .= "where ";
							$qryB .= "	R.officeid=".$oid." and ";
							$qryB .= "	A.qtype=".$qtype." and ";
							$qryB .= "	R.rid=".$rowA['dbid'].";";
							$resB = mssql_query($qryB);
							$rowB = mssql_fetch_array($resB);
							$nrowB= mssql_num_rows($resB);
							
							//echo $qryB.'<br>';
							if ($nrowB!=0)
							{					
								//$trii_ar=$rii_ar[0];
								$qryC = "SELECT * FROM jbids_breakout WHERE officeid='".$oid."' and njobid='".$jid."' and rdbid='".$rowB['rrid']."';";
								$resC = mssql_query($qryC);
								$rowC = mssql_fetch_array($resC);
								$nrowC= mssql_num_rows($resC);
								
								//echo $qryC.'<br>';
								
								if ($nrowC==0 && !in_array($rowB['rrid'],$rii_ar))
								{
									//echo "Write Cost BID Item <br>";
									write_bid_cost($oid,$jid,0,$rowB['rrid'],$rowB['rcid'],$rowB['cphsid'],$rowB['citem'],$rowA['bidamt']);
									//$rii_ar[]=$rowB['rrid'];
								}
								elseif ($nrowC!=0 && $rowC['phsid']==0)
								{
									$qryCa = "UPDATE jbids_breakout SET phsid='".$rowB['cphsid']."' WHERE officeid='".$oid."' and id='".$rowC['id']."';";
									$resCa = mssql_query($qryCa);
								}
							}
						}
					}
				}
			}
		}
	}
}

function write_bid_cost($oid,$jid,$jadd,$rid,$cid,$phsid,$i,$bp)
{
	$qryA = "SELECT id FROM jbids_breakout WHERE officeid='".$oid."' and njobid='".$jid."' and jadd='".$jadd."' and rdbid='".$rid."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	$nrowA= mssql_num_rows($resA);
	
	if ($nrowA==0)
	{
		//echo "Write Cost BID Item ($oid) ($jid) ($jadd) ($rid) ($cid) ($phsid) ($i) ($nrowA)<br>";
		$qryB  = "INSERT INTO ";
		$qryB .= "jbids_breakout (officeid,njobid,jadd,rdbid,cdbid,sdesc,phsid,bprice) ";
		$qryB .= "VALUES ";
		$qryB .= "('".$oid."','".$jid."','".$jadd."','".$rid."','".$cid."','".$i."','".$phsid."','".$bp."');";
		$resB = mssql_query($qryB);
		//$rowB = mssql_fetch_array($resB);
		//echo $qryB."<br>";
	}
}

function edit_mpa_jobmode_delete()
{
	if ($_SESSION['action']=="est")
	{
		$qry = "DELETE FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' AND id='".$_GET['bbid']."';";
		$res = mssql_query($qry);
		
		//echo $qry.'<br>';
		viewest_cost();
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qry = "DELETE FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' AND id='".$_GET['bbid']."';";
		$res = mssql_query($qry);
		
		view_job_cost();
	}
	elseif ($_SESSION['action']=="job")
	{
		$qry = "DELETE FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' AND id='".$_GET['bbid']."';";
		$res = mssql_query($qry);
		
		view_job_cost();
	}
}

function edit_bid_jobmode_delete()
{
	if ($_SESSION['action']=="est")
	{
		$qry = "DELETE FROM bid_breakout WHERE officeid='".$_SESSION['officeid']."' AND id='".$_GET['bbid']."';";
		$res = mssql_query($qry);
		
		//echo $qry.'<br>';
		viewest_cost();
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qry = "DELETE FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND id='".$_GET['bbid']."';";
		$res = mssql_query($qry);
		
		view_job_cost();
	}
	elseif ($_SESSION['action']=="job")
	{
		$qry = "DELETE FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND id='".$_GET['bbid']."';";
		$res = mssql_query($qry);
		
		view_job_cost();
	}
}

function getcodeitem($code)
{
	$qryA  = "SELECT * FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$code."';";
	$resA  = mssql_query($qryA);
	$rowA  = mssql_fetch_array($resA);

	$nrowA = mssql_num_rows($resA);

	if ($nrowA < 1)
	{
		$codedet=array(0=>0,1=>'No Code!',2=>0,3=>0);
	}
	elseif ($nrowA > 1)
	{
		$codedet=array(0=>0,1=>'Duplicate Code!',2=>0,3=>0);
	}
	else
	{
		$iset=$rowA['item']." ".$rowA['atrib1']." ".$rowA['atrib2'];
		$codedet=array(0=>$rowA['code'],1=>$iset,2=>$rowA['bp'],3=>$rowA['rp']);
	}
	return $codedet;
}

function getspecaccpbook($code,$q1,$q2)
{
	$qryA  = "SELECT bprice,lrange,hrange FROM specaccpbook WHERE officeid='".$_SESSION['officeid']."' AND linkid='".$code."' ORDER BY hrange ASC;";
	$resA  = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	if ($nrowA > 0)
	{
		//echo "C: ".$code."<br>";
		//echo "1: ".$q1."<br>";
		//echo "2: ".$q2."<br>";
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

	//print_r($codedet);
	return $codedet;
}

function lab_credititem($id,$oid,$phsid,$quan,$rid)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$bc;

	$viewarray	=$_SESSION['viewarray'];
	$officeid		=$_SESSION['officeid'];
	$discount   	=$viewarray['discount'];
	$ps1        		=$viewarray['ps1'];
	$ps2        		=$viewarray['ps2'];
	$ps4        		=$viewarray['tzone'];
	$ps5        		=$viewarray['ps5'];
	$ps6        		=$viewarray['ps6'];
	$ps7        		=$viewarray['ps7'];
	$spa1       	=$viewarray['spa1'];
	$spa2       	=$viewarray['spa2'];
	$spa3       	=$viewarray['spa3'];

	$iarea		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals		=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	$qry 			= "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND id='".$id."';";
	$res 			= mssql_query($qry);
	$row 		= mssql_fetch_array($res);

	$subbp      	=$row['bprice'];

	//echo $subbp."<br>";
	$subrp      	=0;
	$subphsid		=$row['phsid'];
	$subitem		=$row['item'];
	$subatrib1	=$row['atrib1'];
	$subatrib2	=$row['atrib2'];
	$subatrib3	=$row['atrib3'];
	$subquan		=$quan;
	$lr			=$row['lrange'];
	$hr			=$row['hrange'];
	$cr			=1;
	$code			=0;

	$calc_out	=uni_calc_loop($row['qtype'],$subbp,0,$lr,$hr,$quan,$row['quantity'],$iarea,$gals,0,0,$code,0,0,0,0,0);
	$bp			=$calc_out[0]*-1;
	$quan_out	=$calc_out[2];

	if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
	{
		showitem($bp,$subrp,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$quan_out,1,$rid);
	}

	//echo "CRED QUAN: ".$quan."<br>";
	$phsbcrc=array(0=>$bp,0,0);
	return $phsbcrc;
}

function mat_credititem($id,$phsid,$quan)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$bc;

	$viewarray	=$_SESSION['viewarray'];
	$officeid	=$_SESSION['officeid'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];

	$iarea		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals			=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	$qry = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND invid='".$id."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	//echo "QRY : ".$qry."<br>";

	if ($row['matid']!=0)
	{
		$qrya = "SELECT bp FROM material_master WHERE id='".$row['matid']."';";
		$resa = mssql_query($qrya);
		$rowa = mssql_fetch_array($resa);
		//echo "QRYA: ".$qrya."<br>";

		$subbp   =$rowa['bp']*-1;
	}
	else
	{
		$subbp   =$row['bprice']*-1;
	}

	$subrp      =0;
	$subphsid   =$row['phsid'];
	$subitem    =$row['item'];
	$subatrib1  =$row['atrib1'];
	$subatrib2  =$row['atrib2'];
	$subatrib3  =$row['atrib3'];
	$subquan    =$quan;
	$lr			=0;
	$hr			=0;
	$cr         =1;
	$code       =0;
	//echo "CR ITEM: ".$subitem."<br>";
	//$calc_out	=uni_calc_loop($row['qtype'],$row['bprice'],0,0,0,$quan,$row['quan_calc'],$iarea,$gals,0,0,$code,0,0,0,0,0);
	$calc_out	=uni_calc_loop($row['qtype'],$subbp,0,$lr,$hr,$quan,$row['quan_calc'],$iarea,$gals,0,0,$code,0,0,0,0,0);
	$bp			=$calc_out[0];
	$quan_out	=$calc_out[2];
	//echo "SBP  OUT: ".$bp."<br>";
	//echo "QUAN OUT: ".$quan_out."<br>";

	if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
	{
		//showMitem($bp,0,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$quan_out,$cr,$id);
		showMitem($bp,0,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$quan_out,$cr,0);
	}
	$phsbcrc=array(0=>$bp,0,0);
	return $phsbcrc;
}

function mat_credititem_job($rinvid,$pre_v)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$bc;

	$viewarray	=$_SESSION['viewarray'];
	$officeid	=$_SESSION['officeid'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];

	$iarea		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals			=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	$qry = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$pre_v[6]."' AND invid='".$rinvid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1 = "SELECT rid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rinvid."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	//echo "QRY : ".$qry."<br>";

	if ($row['matid']!=0)
	{
		$qrya = "SELECT bp FROM material_master WHERE id='".$row['matid']."';";
		$resa = mssql_query($qrya);
		$rowa = mssql_fetch_array($resa);
		//echo "QRYA: ".$qrya."<br>";

		$subbp   =$rowa['bp']*-1;
	}
	else
	{
		$subbp   =$row['bprice']*-1;
	}

	//$rid			=$pre_v[0];
	//$quan			=$pre_v[2];
	//$subbprice  	=$pre_v[3];
	//$subqtype   	=$pre_v[4];
	//$code       	=$pre_v[5];
	//$subphsid		=$pre_v[6];
	$subrp      =0;
	$subphsid   =$row['phsid'];
	$subitem    =$row['item'];
	$subatrib1  =$row['atrib1'];
	$subatrib2  =$row['atrib2'];
	$subatrib3  =$row['atrib3'];
	//$subquan    =$quan;
	$subquan    =$pre_v[2];
	$cr         =1;
	$code       =0;

	$calc_out	=uni_calc_loop($row['qtype'],$subbp,0,0,0,$subquan,$row['quan_calc'],$iarea,$gals,0,0,$code,0,0,0,0,0);
	$bp			=$calc_out[0];
	$quan_out	=$calc_out[2];
	//echo "SBP  OUT: ".$bp."<br>";
	//echo "QUAN OUT: ".$quan_out."<br>";

	if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
	{
		//showMitem($bp,0,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$quan_out,$cr,$row1['rid']);
		showMitem($bp,0,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$quan_out,$cr,0);
	}
	$phsbcrc=array(0=>$bp,0,0);
	return $phsbcrc;
}

function uni_calc_loop($qtype,$bp,$rp,$lr,$hr,$quan,$def_quan,$iarea,$gals,$spa_ia,$spa_gl,$code,$a1,$a2,$a3,$mquan,$chgproc)
{
	//error_reporting(E_ALL);
	global $viewarray;
	//$viewarray	=$_SESSION['viewarray'];
	//echo "CHGP: ".$chgproc."<br>";
	//echo "IMQUAN: ".$mquan."<br>";
	//echo "QTYP: ".$qtype."<br>";
	//echo "QUAN: ".$quan."<br>";
	//echo "DUAN: ".$def_quan."<br>";
	//echo "BPRI: ".$bp."<br>";
	//echo "RPRI: ".$rp."<br>--<br>";
	
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
		
		//echo "<b>".$qtype.":".$quan.":".$quan_out.":".$subbp."</b><br>----<br>\n";
	}
	elseif ($qtype==3) // PFT
	{
		$quan_out=$viewarray['ps1'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
		//echo $qtype.":".$quan_out.":".$subbp."<br>--<br>";
		//print_r($_SESSION['viewarray']);
	}
	elseif ($qtype==4) // SQFT
	{
		$quan_out=$viewarray['ps2'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==5) // Base+ (PFT)
	{
		$quan_out=$viewarray['ps1'];
		
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
		$quan_out=$viewarray['ps2'];
		
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
		/*
		echo "*TOUT: ".$qtype."<BR>";
		echo "*BOUT: ".$bp."<BR>";
		echo "*ROUT: ".$rp."<BR>";
		echo "*QOUT: ".$quan_out."<BR>";
		echo "*DOUT: ".$def_quan."<BR>";
		echo "*HOUT: ".$hr."<BR>";
		echo "*SOUT: ".$subbp."<BR>";
		echo "XXXXXXXXXXXXXXXXXXXXXXX<BR>";
		*/
	}
	elseif ($qtype==7) // Base+ (IA)
	{
		$quan_out=$iarea;
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
		$quan_out=$viewarray['ps1'];
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==10) // Bracket (SQFT)
	{
		$quan_out=$viewarray['ps2'];
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==11) // Bracket (IA)
	{
		$quan_out=$iarea;
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==12) // Bracket (Gallons)
	{
		$quan_out=$gals;
		$subbp =$bp;
		$subrp =$rp;
	}
	elseif ($qtype==13) // Checkbox (PFT)
	{
		$quan_out=$viewarray['ps1'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==14) // Checkbox (SQFT)
	{
		$quan_out=$viewarray['ps2'];
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
	elseif ($qtype==18) // Code (PFT)
	{
		$quan_out=$viewarray['ps1'];
		$scode=getcodeitem($code);
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==19) // Code (SQFT)
	{
		$quan_out=$viewarray['ps2'];
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
	/*
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
		$quan_out=$viewarray['ps1']*2.16;
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
		$quan_out=$iarea/$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==47) // IA (Mult by CalcAmt)
	{
		$quan_out=$iarea*$def_quan;
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
			$qrypst0 ="SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
			$respst0 =mssql_query($qrypst0);
			$rowpst0 =mssql_fetch_array($respst0);

			if ($rowpst0['stax']==1)
			{
				if ($_SESSION['action']=="contract")
				{
					$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
				}
				elseif ($_SESSION['action']=="job")
				{
					$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
				}
				elseif ($_SESSION['action']=="est")
				{
					$qry1a ="SELECT cid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
				}
				else
				{
					$qry1a ="SELECT permit,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."';";
				}

				$res1a =mssql_query($qry1a);
				$row1a =mssql_fetch_array($res1a);

				$qry1b ="SELECT scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row1a[0]."';";
				$res1b =mssql_query($qry1b);
				$row1b =mssql_fetch_array($res1b);

				$qry1 ="SELECT permit,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1b[0]."';";
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
		if ($iarea > $a2)
		{
			$calc1=$a2/$def_quan;
			$calc2=($iarea-$a2)/$def_quan;
			$quan_out=$calc1+$calc2;
			$subbp=$bp+($calc2*$a3);
			$subrp=$rp+($calc2*$a3);

			
			/*echo "OUTSIDE ($subbp)<BR>";
			echo "BP: ".$bp."<BR>";
			echo "A2: ".$a2."<BR>";
			echo "A3: ".$a3."<BR>";
			echo "DQ: ".$def_quan."<BR>";
			echo "IA: ".$iarea."<BR>";
			echo "C1: ".$calc1."<BR>";
			echo "C2: ".$calc2."<BR>";
			echo "QO: ".$quan_out."<BR>";
			echo "SP: ".$subbp."<BR>";
			echo "--------------<BR>";*/
			
		}
		else
		{
			$calc=$iarea/$def_quan;
			$quan_out=$calc;
			$subbp=$bp;
			$subrp=$rp;
			//echo "INSIDE ($subbp)<BR>";
		}
	}
	elseif ($qtype==57) // Gallons (Total)
	{
		$quan_out=$gals;
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
		$quan_out=$viewarray['erun'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==60) // Plumb Run (Total)
	{
		$quan_out=$viewarray['prun'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
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
		//$xps1=$viewarray['ps1']*2.16;
		//$quan_out=$viewarray['deck'];
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
	elseif ($qtype==73) // Peri (Div by CalcAmt)
	{
		$quan_out=$viewarray['ps1']/$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==74) // SA (Div by CalcAmt)
	{
		$quan_out=$viewarray['ps2']/$def_quan;
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
	}
	elseif ($qtype==75) // Peri (Div by CalcAmt) Base +
	{
		if ($viewarray['ps1'] > $a2)
		{
			$calc1=$a2/$def_quan;
			$calc2=($viewarray['ps1']-$a2)/$def_quan;
			$quan_out=$calc1+$calc2;
			$subbp=$bp+($calc2*$a3);
			$subrp=$rp+($calc2*$a3);
		}
		else
		{
			$calc=$viewarray['ps1']/$def_quan;
			$quan_out=$calc;
			$subbp=$bp;
			$subrp=$rp;
		}
	}
	elseif ($qtype==76) // SA (Div by CalcAmt) Base +
	{
		if ($viewarray['ps2'] > $a2)
		{
			$calc1=$a2/$def_quan;
			$calc2=($viewarray['ps2']-$a2)/$def_quan;
			$quan_out=$calc1+$calc2;
			$subbp=$bp+($calc2*$a3);
			$subrp=$rp+($calc2*$a3);
		}
		else
		{
			$calc=$viewarray['ps2']/$def_quan;
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
	/*
	elseif ($qtype==77) // Constr Allowance - Psuedo Bid Item
	{
	//temp fix
	if ($quan < 0)
	{
	$quan_out=$quan;
	}
	else
	{
	$quan_out=1;
	}
	//$quan_out=$quan;
	$subbp=$bp*$quan_out;
	$subrp=$rp*$quan_out;
	}
	*/
	else // Catch Bucket
	{
		$quan_out	=0;
		$subbp		=0;
		$subrp		=0;
	}

	$ar_out=array(0=>round($subbp),1=>$subrp,2=>$quan_out);
	
	/*
	if ($qtype==58)
	{
	print_r($ar_out);
	}
	*/
	//print_r($ar_out);
	return $ar_out;
}

function labor_baseitems_calc($phsid,$jtag)
{
	$MAS=$_SESSION['pb_code'];
	//echo "Base CALC";
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;

	//print_r($viewarray);

	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	//Pulls Total List of Base Labor Items within a phase based upon DISTINCT accid's
	$qry0    ="SELECT DISTINCT(accid),qtype,seqnum FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND baseitem=1 ORDER BY seqnum;";
	$res0    =mssql_query($qry0);
	$nrow0   =mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		$bc=0;
		$rc=0;
		while($row0=mssql_fetch_row($res0))
		{
			if ($row0[1]==1) // Fixed
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice'];
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$row1['quantity'];
			}
			elseif ($row0[1]==2) // Quantity
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*$row1['quantity'];
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$row1['quantity'];
			}
			elseif ($row0[1]==3) // per PFT
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*($ps1*$row1['quantity']);
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$ps1;
			}
			elseif ($row0[1]==4) // per SQFT
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*$ps2;
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$ps2;
			}
			elseif ($row0[1]==5) // Base+ PFT (Fixed Base + amt per pft)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =($row1['bprice']*1)+(($ps1-$row1['hrange'])*$row1['quantity']);
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$ps1;
			}
			elseif ($row0[1]==6) // Base+ SQFT (Fixed Base + amt per sqft)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				if ($ps2<=$row1['lrange'])
				{
					$bcsub =$row1['bprice'];
				}
				elseif ($ps2 > $row1['lrange'])
				{
					$bcsub =($row1['bprice']*1)+(($ps2-$row1['hrange'])*$row1['quantity']);
				}
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$ps2;
			}
			elseif ($row0[1]==7) // Base+ IA
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']+(($row1['lrange'])*$row1['quantity']);
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$iarea;
			}
			elseif ($row0[1]==9) // Bracket PFT (ranges)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1 order by lrange ASC;";
				$res1  =mssql_query($qry1);

				while ($row1=mssql_fetch_array($res1))
				{
					if ($ps1 >= $row1['lrange'] && $ps1 <= $row1['hrange'])
					{
						$bcsub =$row1['bprice'];
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quan  =$ps1;
					}
					elseif ($ps1 > $row1['hrange'])
					{
						$bcsub =$row1['bprice']+(($ps1-$row1['hrange'])*$row1['quantity']);
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quan  =$ps1;
					}
				}
			}
			elseif ($row0[1]==10) // Bracket SQFT (ranges)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1 order by lrange ASC;";
				$res1  =mssql_query($qry1);

				//echo $qry1."<br>";

				//echo "PS2: ".$ps2."<br>";

				while ($row1=mssql_fetch_array($res1))
				{
					if ($ps2 >= $row1['lrange'] && $ps2 <= $row1['hrange'])
					{
						$bcsub =$row1['bprice'];
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quan  =$ps2;
						//echo "iLR: ".$row1['lrange']."<br>";
						//echo "iHR: ".$row1['hrange']."<br>";
					}
					elseif ($ps2 > $row1['hrange'])
					{
						$bcsub =$row1['bprice']+(($ps2-$row1['hrange'])*$row1['quantity']);
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quan  =$ps2;
						//echo "gLR: ".$row1['lrange']."<br>";
						//echo "gHR: ".$row1['hrange']."<br>";
					}
				}
			}
			elseif ($row0[1]==11) // Bracket IA
			{
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$respre1 = mssql_query($qrypre1);
				$rowpre1 = mssql_fetch_row($respre1);

				if ($iarea < $rowpre1[0])
				{
					$qry1  = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
					$res1  = mssql_query($qry1);
					$row1  = mssql_fetch_array($res1);

					$bcsub =$row1['bprice']*$row1['lrange'];
					$rcsub =0;
					$id    =$row1['phsid'];
					$item  =$row1['item'];
					$a1    =$row1['atrib1'];
					$a2    =$row1['atrib2'];
					$a3    =$row1['atrib3'];
					$quan  =$row1['lrange'];
				}
				elseif ($iarea > $rowpre1[3])
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND hrange='".$rowpre1[3]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);
					$row1  =mssql_fetch_array($res1);

					$bcsub =($row1['bprice']*$rowpre1[3])+(($iarea-$rowpre1[3])*$row1['quantity']);
					$rcsub =0;
					$id    =$row1['phsid'];
					$item  =$row1['item'];
					$a1    =$row1['atrib1'];
					$a2    =$row1['atrib2'];
					$a3    =$row1['atrib3'];
					$quan  =$iarea;
				}
				else
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);

					while ($row1  =mssql_fetch_array($res1))
					{
						if ($iarea >= $row1['lrange'] && $iarea <= $row1['hrange'])
						{
							$bcsub =$row1['bprice']*$iarea;
							$rcsub =0;
							$id    =$row1['phsid'];
							$item  =$row1['item'];
							$a1    =$row1['atrib1'];
							$a2    =$row1['atrib2'];
							$a3    =$row1['atrib3'];
							$quan  =$iarea;
						}
					}
				}
			}
			elseif ($row0[1]==30) // Fixed per PFT
			{
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$respre1 = mssql_query($qrypre1);
				$rowpre1 = mssql_fetch_row($respre1);

				if ($ps1 < $rowpre1[0])
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['bprice'];
					$rcsub =0;
					$id    =$row1['phsid'];
					$item  =$row1['item'];
					$a1    =$row1['atrib1'];
					$a2    =$row1['atrib2'];
					$a3    =$row1['atrib3'];
					$quan  =$ps1;
				}
				elseif ($ps1 > $rowpre1[1])
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[1]."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['bprice']+(($ps1-$rowpre1[1])*$row1['quantity']);
					$rcsub =0;
					$id    =$row1['phsid'];
					$item  =$row1['item'];
					$a1    =$row1['atrib1'];
					$a2    =$row1['atrib2'];
					$a3    =$row1['atrib3'];
					$quan  =$ps1;
				}
				else
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$ps1."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['bprice'];
					$rcsub =0;
					$id    =$row1['phsid'];
					$item  =$row1['item'];
					$a1    =$row1['atrib1'];
					$a2    =$row1['atrib2'];
					$a3    =$row1['atrib3'];
					$quan  =$ps1;
				}
			}
			elseif ($row0[1]==53) // Permit
			{
				if ($rowpre0[5]==1)
				{
					if ($jtag==1)
					{
						if ($_SESSION['action']=="contract")
						{
							$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
						}
						else
						{
							$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
						}
					}
					else
					{
						$qry1a ="SELECT cid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
					}
					$res1a =mssql_query($qry1a);
					$row1a =mssql_fetch_array($res1a);

					$qry1b ="SELECT scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row1a[0]."';";
					$res1b =mssql_query($qry1b);
					$row1b =mssql_fetch_array($res1b);

					$qry1 ="SELECT permit,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1b[0]."';";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['permit'];
					$rcsub =0;
					$id    =$phsid;
					$item  ="Permit (".$row1['city'].")";
					$a1    ="";
					$a2    ="";
					$a3    ="";
					$quan  =1;
				}
			}
			else
			{
			}

			if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
			{
				showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
			}
			$bc=$bc+$bcsub;
			$rc=$rc+$rcsub;
		}
		$cc=0;
	}
	else
	{
		$bc=0;
		$rc=0;
		$cc=0;
	}
}

function labor_baseitems_job_calc($phsid,$bdata,$jtag)
{
	$MAS=$_SESSION['pb_code'];
	//echo "Base Job CALC: <br>";
	//echo $bdata."<br>";
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;

	$bc=0;

	//print_r($viewarray);
	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	// *** ADD Accessory Cost Calcs Here ***
	$costitems=preg_replace("/,\Z/","",$bdata);
	if ($costitems > 0)
	{
		//echo "COSTS: ";
		//print_r($costitems);
		$edata=explode(",",$costitems);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			//echo "IV: ".$pre_iv."<br>";
			$pre_v=explode(":",$pre_iv);
			//echo "<pre>";
			//print_r($pre_v);
			//echo "</pre>";

			$rid		=0;
			$cid     =$pre_v[0];
			$accid	=$pre_v[1];
			$matid	=$pre_v[3];
			$quan		=$pre_v[11];
			$cost		=$pre_v[6];
			$qtype	=$pre_v[4];
			$code		=$pre_v[8];
			$lrange	=$pre_v[9];
			$hrange	=$pre_v[10];
			$iphsid  =$pre_v[2];
			//$rinvid  =$pre_v[9];
			$quancalc=$pre_v[13];
			$item		=$pre_v[7];
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
						if ($_SESSION['action']=="contract")
						{
							$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
						}

						$res1a =mssql_query($qry1a);
						$row1a =mssql_fetch_array($res1a);

						$qry1b ="SELECT scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row1a[0]."';";
						$res1b =mssql_query($qry1b);
						$row1b =mssql_fetch_array($res1b);

						$qry1 ="SELECT permit,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1b[0]."';";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bp 	=$row1['permit'];
						$item  ="Permit (".$row1['city'].")";
					}
					else
					{
						$bp	=$cost;
					}

					//echo "PERMIT ITEM: ".$item."<br>";
					$quan_out	=$quan;
					if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
					{
						showitem($bp,0,$iphsid,$item,$a1,$a2,$a3,$quan_out,0,$rid);
					}
					$bc=$bc+$bp;
					$rc=0;
				}
				elseif ($qtype!=33) // All other qtypes
				{
					//echo "ITEM: ".$item."<br>";
					$bp			=$cost;
					$quan_out	=$quan;
					if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
					{
						showitem($bp,0,$iphsid,$item,$a1,$a2,$a3,$quan_out,0,$rid);
					}
					$bc=$bc+$bp;
					$rc=0;
				}
			}
		}
	}
}

function labor_baseitems_calc_add($phsid,$jtag)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;

	$viewarray	=$_SESSION['viewarray'];
	$ojadd=$viewarray['jadd']-1;

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	if ($_SESSION['action']=="contract")
	{
		$qrypre0a	="SELECT pft,sqft,shal,mid,deep FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd='".$ojadd."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qrypre0a	="SELECT pft,sqft,shal,mid,deep FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd='".$ojadd."';";
	}

	$respre0a	=mssql_query($qrypre0a);
	$rowpre0a	=mssql_fetch_array($respre0a);

	if ($_SESSION['action']=="contract")
	{
		$qrypre0b	="SELECT pft,sqft,shal,mid,deep FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd='".$viewarray['jadd']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qrypre0b	="SELECT pft,sqft,shal,mid,deep FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd='".$viewarray['jadd']."';";
	}

	$respre0b	=mssql_query($qrypre0b);
	$rowpre0b	=mssql_fetch_array($respre0b);

	$pftdiff		=$viewarray['ps1']-$rowpre0a['pft'];
	$sqftdiff	=$viewarray['ps2']-$rowpre0a['sqft'];

	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$oiarea=calc_internal_area($rowpre0a['pft'],$rowpre0a['sqft'],$rowpre0a['shal'],$rowpre0a['mid'],$rowpre0a['deep']);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
	$ogals=calc_gallons($rowpre0a['pft'],$rowpre0a['sqft'],$rowpre0a['shal'],$rowpre0a['mid'],$rowpre0a['deep']);

	//Pulls Total List of Base Labor Items within a phase based upon DISTINCT accid's
	$qry0    ="SELECT DISTINCT(accid),qtype,seqnum FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND baseitem=1 ORDER BY seqnum;";
	$res0    =mssql_query($qry0);
	$nrow0   =mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		$bcsub=0;
		$rcsub=0;
		$bc=0;
		$rc=0;
		while($row0=mssql_fetch_row($res0))
		{
			//echo "qtype   : ".$row0[1]."<br>";
			if ($row0[1]==1) // Fixed
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice'];
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$row1['quantity'];

				//ECHO "FIXED: ".$item;
				//showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
			}
			elseif ($row0[1]==2) // Quantity
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*$row1['quantity'];
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$row1['quantity'];
				showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
			}
			elseif ($row0[1]==3) // per PFT
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*($ps1*$row1['quantity']);
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$ps1;
				showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
			}
			elseif ($row0[1]==4) // per SQFT
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*$ps2;
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$ps2;
				showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
			}
			elseif ($row0[1]==5) // Base+ PFT (Fixed Base + amt per pft)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =($row1['bprice']*1)+(($ps1-$row1['hrange'])*$row1['quantity']);
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$ps1;
				showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
			}
			elseif ($row0[1]==6) // Base+ SQFT (Fixed Base + amt per sqft)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				if ($ps2<=$row1['lrange'])
				{
					$bcsubn =$row1['bprice'];
				}
				elseif ($ps2 > $row1['lrange'])
				{
					$bcsubn =($row1['bprice']*1)+(($ps2-$row1['hrange'])*$row1['quantity']);
				}
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quann  =$ps2;

				$qry1a  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1a  =mssql_query($qry1a);
				$row1a  =mssql_fetch_array($res1a);

				if ($rowpre0a['sqft']<=$row1a['lrange'])
				{
					$bcsubo =$row1a['bprice'];
				}
				elseif ($rowpre0a['sqft'] > $row1a['lrange'])
				{
					$bcsubo =($row1a['bprice']*1)+(($rowpre0a['sqft']-$row1a['hrange'])*$row1a['quantity']);
				}
				$quano  =$rowpre0a['sqft'];

				$bcsub	=$bcsubo-$bcsubn;
				$quan		=$quano-$quann;

				if ($bcsub!=0)
				{
					showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
				}
			}
			elseif ($row0[1]==7) // Base+ IA
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']+(($row1['lrange'])*$row1['quantity']);
				$rcsub =0;
				$id    =$row1['phsid'];
				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
				$a2    =$row1['atrib2'];
				$a3    =$row1['atrib3'];
				$quan  =$iarea;
				showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
			}
			elseif ($row0[1]==9) // Bracket PFT (ranges)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1 order by lrange ASC;";
				$res1  =mssql_query($qry1);

				while ($row1=mssql_fetch_array($res1))
				{
					if ($ps1 >= $row1['lrange'] && $ps1 <= $row1['hrange'])
					{
						$bcsub =$row1['bprice'];
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quan  =$ps1;
					}
					elseif ($ps1 > $row1['hrange'])
					{
						$bcsub =$row1['bprice']+(($ps1-$row1['hrange'])*$row1['quantity']);
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quan  =$ps1;
					}
				}
				showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
			}
			elseif ($row0[1]==10) // Bracket SQFT (ranges)
			{
				if ($ps2!=$rowpre0a['sqft'])
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1 order by lrange ASC;";
					$res1  =mssql_query($qry1);

					while ($row1=mssql_fetch_array($res1))
					{
						if ($ps2 >= $row1['lrange'] && $ps2 <= $row1['hrange'])
						{
							$bcsubn =$row1['bprice'];
							$rcsub =0;
							$id    =$row1['phsid'];
							$item  =$row1['item'];
							$a1    =$row1['atrib1'];
							$a2    =$row1['atrib2'];
							$a3    =$row1['atrib3'];
							$quann  =$ps2;
						}
						elseif ($ps2 > $row1['hrange'])
						{
							$bcsubn =$row1['bprice']+(($ps2-$row1['hrange'])*$row1['quantity']);
							$rcsub =0;
							$id    =$row1['phsid'];
							$item  =$row1['item'];
							$a1    =$row1['atrib1'];
							$a2    =$row1['atrib2'];
							$a3    =$row1['atrib3'];
							$quann  =$ps2;
						}

						if ($rowpre0a['sqft'] >= $row1['lrange'] && $rowpre0a['sqft'] <= $row1['hrange'])
						{
							$bcsubo =$row1['bprice'];
							$rcsub =0;
							$id    =$row1['phsid'];
							$item  =$row1['item'];
							$a1    =$row1['atrib1'];
							$a2    =$row1['atrib2'];
							$a3    =$row1['atrib3'];
							$quano  =$rowpre0a['sqft'];
						}
						elseif ($ps2 > $row1['hrange'])
						{
							$bcsubo =$row1['bprice']+(($ps2-$row1['hrange'])*$row1['quantity']);
							$rcsub =0;
							$id    =$row1['phsid'];
							$item  =$row1['item'];
							$a1    =$row1['atrib1'];
							$a2    =$row1['atrib2'];
							$a3    =$row1['atrib3'];
							$quano  =$rowpre0a['sqft'];
						}
						$bcsub	=$bcsubn-$bcsubo;
						$quan		=$quann-$quano;
					}

					if ($bcsub!=0)
					{
						showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
					}
				}
			}
			elseif ($row0[1]==11) // Bracket IA
			{
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$respre1 = mssql_query($qrypre1);
				$rowpre1 = mssql_fetch_row($respre1);

				if ($iarea < $rowpre1[0])
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);
					$row1  =mssql_fetch_array($res1);

					$bcsub =$row1['bprice']*$row1['lrange'];
					$rcsub =0;
					$id    =$row1['phsid'];
					$item  =$row1['item'];
					$a1    =$row1['atrib1'];
					$a2    =$row1['atrib2'];
					$a3    =$row1['atrib3'];
					$quan  =$row1['lrange'];
				}
				elseif ($iarea > $rowpre1[3])
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND hrange='".$rowpre1[3]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);
					$row1  =mssql_fetch_array($res1);

					$bcsub =($row1['bprice']*$rowpre1[3])+(($iarea-$rowpre1[3])*$row1['quantity']);
					$rcsub =0;
					$id    =$row1['phsid'];
					$item  =$row1['item'];
					$a1    =$row1['atrib1'];
					$a2    =$row1['atrib2'];
					$a3    =$row1['atrib3'];
					$quan  =$iarea;
				}
				else
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);

					while ($row1  =mssql_fetch_array($res1))
					{
						if ($iarea >= $row1['lrange'] && $iarea <= $row1['hrange'])
						{
							$bcsub =$row1['bprice']*$iarea;
							$rcsub =0;
							$id    =$row1['phsid'];
							$item  =$row1['item'];
							$a1    =$row1['atrib1'];
							$a2    =$row1['atrib2'];
							$a3    =$row1['atrib3'];
							$quan  =$iarea;
						}
					}
				}
				showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
			}
			elseif ($row0[1]==30) // Fixed per PFT
			{
				if ($pftdiff!=0)
				{
					$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
					$respre1 = mssql_query($qrypre1);
					$rowpre1 = mssql_fetch_row($respre1);

					if ($ps1 < $rowpre1[0])
					{
						$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bcsubn =$row1['bprice'];
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quann  =$ps1;
					}
					elseif ($ps1 > $rowpre1[1])
					{
						$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[1]."' AND baseitem=1;";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bcsubn =$row1['bprice']+(($ps1-$rowpre1[1])*$row1['quantity']);
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quann  =$ps1;
					}
					else
					{
						$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$ps1."' AND baseitem=1;";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bcsubn =$row1['bprice'];
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quann  =$ps1;
					}


					if ($rowpre0a['pft'] < $rowpre1[0])
					{
						$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bcsub0 =$row1['bprice'];
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quano  =$rowpre0a['pft'];
					}
					elseif ($rowpre0a['pft'] > $rowpre1[1])
					{
						$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[1]."' AND baseitem=1;";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bcsubo =$row1['bprice']+(($ps1-$rowpre1[1])*$row1['quantity']);
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quano  =$rowpre0a['pft'];
					}
					else
					{
						$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre0a['pft']."' AND baseitem=1;";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bcsubo =$row1['bprice'];
						$rcsub =0;
						$id    =$row1['phsid'];
						$item  =$row1['item'];
						$a1    =$row1['atrib1'];
						$a2    =$row1['atrib2'];
						$a3    =$row1['atrib3'];
						$quano  =$rowpre0a['pft'];
					}

					$bcsub =$bcsubn-$bcsubo;
					$quan  =$quann-$quano;
					showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
				}
				else
				{
					//echo "NO COST Change";
				}
			}
			elseif ($row0[1]==53) // Permit
			{
				if ($rowpre0[5]==1)
				{
					if ($jtag==1)
					{
						$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
					}
					else
					{
						$qry1a ="SELECT cid FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
					}
					$res1a =mssql_query($qry1a);
					$row1a =mssql_fetch_array($res1a);

					$qry1b ="SELECT scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row1a[0]."';";
					$res1b =mssql_query($qry1b);
					$row1b =mssql_fetch_array($res1b);

					$qry1 ="SELECT permit,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1b[0]."';";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['permit'];
					$rcsub =0;
					$id    =$phsid;
					$item  ="Permit (".$row1['city'].")";
					$a1    ="";
					$a2    ="";
					$a3    ="";
					$quan  =1;
					//showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan,0,0);
				}
			}
			$bc=$bc+$bcsub;
			$rc=$rc+$rcsub;
		}
		$cc=0;
	}
	else
	{
		$bc=0;
		$rc=0;
		$cc=0;
	}
}

function store_labor_baseitemsold($jobid,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$viewarray,$tchrg,$taxrate,$bc;

	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	//Pulls Total List of Base Labor Items within a phase based upon DISTINCT accid's
	$qry0    ="SELECT DISTINCT(accid),qtype,seqnum,phsid FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND baseitem=1 ORDER BY seqnum;";
	$res0    =mssql_query($qry0);
	$nrow0   =mssql_num_rows($res0);

	$ecnt		=0;
	if ($nrow0 > 0)
	{
		//$ecnt		=$nrow0;
		//echo "BEFORE: ".$ecnt."<br>";
		$p_out='';
		$bc=0;
		$rc=0;

		while($row0=mssql_fetch_row($res0))
		{
			if ($row0[1]==1) // Fixed
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice'];
				$quan  =$row1['quantity'];
			}
			elseif ($row0[1]==2) // Quantity
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*$row1['quantity'];
				$quan  =$row1['quantity'];
			}
			elseif ($row0[1]==3) // per PFT
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*($ps1*$row1['quantity']);
				$quan  =$ps1;
			}
			elseif ($row0[1]==4) // per SQFT
			{
				$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1 =mssql_query($qry1);
				$row1 =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']*$ps2;
				$quan  =$ps2;
			}
			elseif ($row0[1]==5) // Base+ PFT (Fixed Base + amt per pft)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =($row1['bprice']*1)+(($ps1-$row1['hrange'])*$row1['quantity']);
				$quan  =$ps1;
			}
			elseif ($row0[1]==6) // Base+ SQFT (Fixed Base + amt per sqft)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				if ($ps2<=$row1['lrange'])
				{
					$bcsub =$row1['bprice'];
				}
				elseif ($ps2 > $row1['lrange'])
				{
					$bcsub =($row1['bprice']*1)+(($ps2-$row1['hrange'])*$row1['quantity']);
				}
				$quan  =$ps2;
			}
			elseif ($row0[1]==7) // Base+ IA
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);
				$row1  =mssql_fetch_array($res1);

				$bcsub =$row1['bprice']+(($row1['lrange'])*$row1['quantity']);
				$quan  =$iarea;
			}
			elseif ($row0[1]==9) // Bracket PFT (ranges)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);

				while ($row1=mssql_fetch_array($res1))
				{
					if ($ps1 >= $row1['lrange'] && $ps1 <= $row1['hrange'])
					{
						$bcsub =$row1['bprice'];
						$quan  =$ps1;
					}
					elseif ($ps1 > $row1['hrange'])
					{
						$bcsub =$row1['bprice']+(($ps1-$row1['hrange'])*$row1['quantity']);
						$quan  =$ps1;
					}
				}
			}
			elseif ($row0[1]==10) // Bracket SQFT (ranges)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$res1  =mssql_query($qry1);

				while ($row1=mssql_fetch_array($res1))
				{
					if ($ps2 >= $row1['lrange'] && $ps2 <= $row1['hrange'])
					{
						$bcsub =$row1['bprice'];
						$quan  =$ps2;
					}
					elseif ($ps2 > $row1['hrange'])
					{
						$bcsub =$row1['bprice']+(($ps2-$row1['hrange'])*$row1['quantity']);
						$quan  =$ps2;
					}
				}
				//echo $qry1."<br>";
			}
			elseif ($row0[1]==11) // Bracket IA
			{
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$respre1 = mssql_query($qrypre1);
				$rowpre1 = mssql_fetch_row($respre1);

				if ($iarea < $rowpre1[0])
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);
					$row1  =mssql_fetch_array($res1);

					$bcsub =$row1['bprice']*$row1['lrange'];
					$quan  =$row1['lrange'];
				}
				elseif ($iarea > $rowpre1[3])
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND hrange='".$rowpre1[3]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);
					$row1  =mssql_fetch_array($res1);

					$bcsub =($row1['bprice']*$rowpre1[3])+(($iarea-$rowpre1[3])*$row1['quantity']);
					$quan  =$iarea;
				}
				else
				{
					$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
					$res1  =mssql_query($qry1);

					while ($row1  =mssql_fetch_array($res1))
					{
						if ($iarea >= $row1['lrange'] && $iarea <= $row1['hrange'])
						{
							$bcsub =$row1['bprice']*$iarea;
							$quan  =$iarea;
						}
					}
				}
			}
			elseif ($row0[1]==30) // Fixed per PFT
			{
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
				$respre1 = mssql_query($qrypre1);
				$rowpre1 = mssql_fetch_row($respre1);

				if ($ps1 < $rowpre1[0])
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['bprice'];
					$quan  =$ps1;
				}
				elseif ($ps1 > $rowpre1[1])
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[1]."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['bprice']+(($ps1-$rowpre1[1])*$row1['quantity']);
					$quan  =$ps1;
				}
				else
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$ps1."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$bcsub =$row1['bprice'];
					$quan  =$ps1;
				}
			}
			elseif ($row0[1]==53) // Permit
			{
				if ($rowpre0[5]==1)
				{
					$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1;";
					$res1 =mssql_query($qry1);
					$row1 =mssql_fetch_array($res1);

					$qry1a ="SELECT custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
					$res1a =mssql_query($qry1a);
					$row1a =mssql_fetch_array($res1a);

					$qry1b ="SELECT scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$row1a[0]."';";
					$res1b =mssql_query($qry1b);
					$row1b =mssql_fetch_array($res1b);

					$qry2 ="SELECT permit,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1b[0]."';";
					$res2 =mssql_query($qry2);
					$row2 =mssql_fetch_array($res2);

					$bcsub =$row2['permit'];
					$item  ="Permit (".$row2['city'].")";
					$a1    ="";
					$quan  =1;
				}
			}


			if ($row0[1]==53)
			{
				$id   	=$row1['id'];
				$accid  	=$row1['accid'];
				$phsid	=$row1['qtype'];
				$matid	=$row1['qtype'];
				$qtype	=$row1['qtype'];
				$mtype	=$row1['mtype'];
				$bprice	=$row1['bprice'];
				$lrange	=$row1['lrange'];
				$hrange	=$row1['hrange'];
				$quan		=$quan;
				$supplier=$row1['supplier'];
				$super	=$row1['supercedes'];
				$code		=$row1['code'];
			}
			else
			{
				$id   	=$row1['id'];
				$accid  	=$row1['accid'];
				$phsid	=$row1['phsid'];
				$matid	=$row1['matid'];
				$qtype	=$row1['qtype'];
				$mtype	=$row1['mtype'];
				$bprice	=$bcsub;
				$lrange	=$row1['lrange'];
				$hrange	=$row1['hrange'];
				$quan		=$quan;
				$supplier=$row1['supplier'];
				$super	=$row1['supercedes'];
				$code		=$row1['code'];

				$item  =$row1['item'];
				$a1    =$row1['atrib1'];
			}

			$fbprice    =number_format($bprice, 2, '.', '');

			if ($ecnt!=1)
			{
				$p=$id.":".$accid.":".$phsid.":".$matid.":".$qtype.":".$mtype.":".$fbprice.":".$item.":".$a1.":".$lrange.":".$hrange.":".$quan.":".$supplier.":".$super.":".$code.",";
			}
			else
			{
				$p=$id.":".$accid.":".$phsid.":".$matid.":".$qtype.":".$mtype.":".$fbprice.":".$item.":".$a1.":".$lrange.":".$hrange.":".$quan.":".$supplier.":".$super.":".$code;
			}
			$p_out=$p_out.$p;
			//echo $p_out."<BR>";
			$ecnt--;
		}
		//echo "AFTER: ".$ecnt."<br>";
		//echo $p_out;

		$qryZ  = "UPDATE jdetail SET bcostdata_l='".$p_out."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_POST['jobid']."' AND jadd='".$_POST['jadd']."';";
		$resZ  = mssql_query($qryZ);
	}
}

function store_material_baseitemsold($jobid,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$viewarray,$bc;

	$officeid=$_SESSION['officeid'];

	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];

	$qry   ="SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND baseitem=1 ORDER by seqnum;";
	$res   =mssql_query($qry);
	$nrows =mssql_num_rows($res);

	if ($nrows > 0)
	{
		$p_out='';
		$ecnt=$nrows;

		while($row=mssql_fetch_array($res))
		{
			$id   	=$row['invid'];
			$accid  	=$row['accid'];
			$raccid  =$row['raccid'];
			$phsid	=$row['qtype'];
			$matid	=$row['qtype'];
			$qtype	=$row['qtype'];
			$mtype	=$row['mtype'];
			$bprice	=$row['bprice'];
			$quan		=$row['quan_calc'];
			$vpno		=$row['vpno'];
			$item		=$row['item'];
			$a1		=$row['atrib1'];


			if ($ecnt!=1)
			{
				$p=$id.":".$accid.":".$raccid.":".$phsid.":".$matid.":".$qtype.":".$mtype.":".$bprice.":".$item.":".$a1.":0:0:".$quan.":0:0:".$vpno.",";
			}
			else
			{
				$p=$id.":".$accid.":".$raccid.":".$phsid.":".$matid.":".$qtype.":".$mtype.":".$bprice.":".$item.":".$a1.":0:0:".$quan.":0:0:".$vpno;
			}
			$p_out=$p_out.$p;
			$ecnt--;
		}
		$qryZ  = "UPDATE jdetail SET bcostdata_m='".$p_out."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_POST['jobid']."' AND jadd='".$_POST['jadd']."';";
		$resZ  = mssql_query($qryZ);
	}
}

function calc_cubic_feet($pft,$sqft,$shallow,$middle,$deep)
{
	$cf=$sqft*(($shallow+$middle+$deep)/3);
	return $cf;
}

function calc_internal_area($pft,$sqft,$shallow,$middle,$deep)
{
	//$ia=(($pft*($shallow+$middle+$middle+$deep))/4)+$sqft;
	$ia=((($shallow+$middle+$deep)/3)*$pft)+$sqft;

	if (is_float($ia))
	{
		$ia=round($ia);
	}
	return $ia;
}

function calc_gallons($pft,$sqft,$shallow,$middle,$deep)
{
	//$gals=($sqft*($shallow+$middle+$middle+$deep)/4)*7.5;
	$gals=((($shallow+$middle+$deep)/3)*$sqft)*7.5;

	if (is_float($gals))
	{
		$gals=round($gals);
	}
	return $gals;
}

function calc_adjusts()
{
	global $discount,$viewarray;
	$tadj=0;

	if ($_SESSION['action']=="est")
	{
		$qrypre = "SELECT estid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
		$respre = mssql_query($qrypre);
		$rowpre = mssql_fetch_array($respre);

		$qryA  = "SELECT * FROM est_discounts WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
		//echo "EST: ".$qryA."<br>";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qrypre = "SELECT estid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_POST['jobid']."';";
		$respre = mssql_query($qrypre);
		$rowpre = mssql_fetch_array($respre);

		$qryA  = "SELECT * FROM jdiscounts WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_POST['jobid']."' AND jadd='0';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qrypre = "SELECT estid,jobid,njobid,mas_prep FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_POST['njobid']."';";
		$respre = mssql_query($qrypre);
		$rowpre = mssql_fetch_array($respre);

		$qryA  = "SELECT * FROM jdiscounts WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_POST['njobid']."' AND jadd='0';";
	}
	$resA  = mssql_query($qryA);
	$nrowA  = mssql_num_rows($resA);

	if ($nrowA > 0)
	{
		while ($rowA  = mssql_fetch_array($resA))
		{
			if ($_SESSION['action']=="contract"||$_SESSION['action']=="job")
			{
				$adj  =$rowA['disc_amt'];
				$desc	=$rowA['disc_desc'];
			}
			else
			{
				$adj  =$rowA['discount'];
				$desc	=$rowA['descrip'];
			}

			$fadj =number_format($adj, 2, '.', '');;
			echo "           <tr>\n";
			echo "              <td class=\"lg\" valign=\"bottom\" align=\"right\"><b>Pricebook Adjust</b></td>\n";
			echo "              <td class=\"lg\" valign=\"bottom\" align=\"left\">".$desc."</td>\n";
            echo "              <td NOWRAP class=\"lg\" align=\"right\"></td>\n";
            echo "              <td NOWRAP class=\"lg\" align=\"right\"></td>\n";
			echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">";

			if ($adj < 0)
			{
				echo "<font color=\"red\">$fadj</font>";
			}
			else
			{
				echo $fadj;
			}

			echo "</td>\n";
			echo "              <td NOWRAP class=\"lg\" align=\"right\"></td>\n";
			echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"center\">\n";

			if ($_SESSION['action']!="job")
			{

				if ($_SESSION['subq']=="print")
				{
					echo "<div class=\"noPrint\">\n";
				}

				echo "						<input class=\"transnb\" type=\"checkbox\" name=\"aaa".$rowA['id']."\" value=\"".$rowA['id']."\">\n";

				if ($_SESSION['subq']=="print")
				{
					echo "</div>\n";
				}
			}
			echo "					</td>\n";
			echo "           </tr>\n";
			$tadj=$tadj+$adj;
		}
		$ftadj =number_format($tadj, 2, '.', '');
	}

	if (!isset($ftadj))
	{
		$ftadj="0.00";
	}

	if ($_SESSION['action']=="est")
	{
		echo "                                </form>\n"; // Inplace for Item Deletes
		echo "                                <tr>\n";
		echo "                                <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
		echo "                                <input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "                                <input type=\"hidden\" name=\"call\" value=\"adjins\">\n";
		echo "                                <input type=\"hidden\" name=\"estid\" value=\"".$_SESSION['estid']."\">\n";
		echo "                                <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "                                <input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "					<b>New Pricebook Adjust</b>\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>";
		echo "              	<td NOWRAP class=\"wh\" align=\"left\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "					<input class=\"bboxnobl\" name=\"descrip\" type=\"text\" size=\"80\" maxlength=\"100\" alt=\"Enter a Contract Adjust Description here. Blank Descriptions are accepted.\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "<div class=\"noPrint\">\n";
		}

		echo "						<input class=\"bboxnobl\" name=\"adjamt\" type=\"text\" size=\"6\" maxlength=\"9\" alt=\"Enter a Contract Adjust Amount here. Adjustments can be positive OR negative\">\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}

		echo "					</td>";
		echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
		echo "              <td NOWRAP class=\"lg\" align=\"center\">\n";

        if (isset($_SESSION['demomode']) && $_SESSION['demomode']==0)
        {
            if ($_SESSION['subq']=="print")
            {
                echo "<div class=\"noPrint\">\n";
            }
    
            if ($_SESSION['action']=="est")
            {
                if ($rowpre['jobid']!=0||$rowpre['njobid']!=0)
                {
                    echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Adjust\" DISABLED>\n";
                    //echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
                }
                else
                {
                    echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Adjust\">\n";
                    //echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Apply\">\n";
                }
            }
            elseif ($_SESSION['action']=="contracts")
            {
                if ($rowpre['njobid']!=0)
                {
                    //echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
                    echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Adjust\" DISABLED>\n";
                }
                else
                {
                    echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Adjust\">\n";
                    //echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Apply\">\n";
                }
            }
            elseif ($_SESSION['action']=="jobs")
            {
                if ($rowpre['mas_prep'] >= 1)
                {
                    //echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Apply\" DISABLED>\n";
                    echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Adjust\" DISABLED>\n";
                }
                else
                {
                    echo "					<input class=\"transnb\" type=\"image\" src=\"images/save.gif\" alt=\"Save Adjust\">\n";
                    //echo "						<input class=\"buttondkgry\" type=\"submit\" value=\"Apply\">\n";
                }
            }
    
            if ($_SESSION['subq']=="print")
            {
                echo "</div>\n";
            }
        }
		echo "					</td>\n";
		echo "           </form>\n";
		echo "           </tr>\n";

		if ($_SESSION['subq']=="print")
		{
			echo "</div>\n";
		}
	}

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Total Contract Adjusts</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">";

	if ($tadj < 0)
	{
		echo "<font color=\"red\">".$ftadj."</font>";
	}
	else
	{
		echo $ftadj;
	}

	echo "</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	$discount=$tadj;
}

function calcbyacc($estdata,$filters)
{
	$MAS=$_SESSION['pb_code'];
	global $bctotal,$rctotal,$cctotal,$tacc_price,$phsbcrc,$tbullets,$viewarray;

	$viewarray=$_SESSION['viewarray'];
	$camt	=$viewarray['camt'];
	$ps1	=$viewarray['ps1'];
	$ps2	=$viewarray['ps2'];
	$ps4	=$viewarray['tzone'];
	$ps5	=$viewarray['ps5'];
	$ps6	=$viewarray['ps6'];
	$ps7	=$viewarray['ps7'];
	$spa1	=$viewarray['spa1'];
	$spa2	=$viewarray['spa2'];
	$spa3	=$viewarray['spa3'];

	if (!isset($showdetail))
	{
		$showdetail=0;
	}

	//echo $ps1."<br>";
	//echo $ps2."<br>---<br>";
	//echo $estdata."<br>";

	if (strlen($estdata) >=6)
	{
		$estAarray=explode(",",$estdata);
		if (is_array($estAarray))
		{
			$tdata_price=0;
			$tcomm=0;
            $icnt=2;
			foreach($estAarray as $n1=>$v1)
			{
                $icnt++;
                if ($icnt%2)
                {
                    $tbg='whlist';
                }
                else
                {
                    $tbg='ltgraylist';
                }
                
				$v1array=explode(":",$v1);
				
				//echo "<pre>";
				//print_r($v1array);
				//echo "<br>";
				//echo "</pre>";

				if (empty($v1array[6]))
				{
					$ctype=0;
					$crate=0;
				}
				else
				{
					$ctype=$v1array[6];
					$crate=$v1array[5];
				}
				
				//for backward compat 3/5/07
				if (!isset($v1array[7]))
				{
					$v1array[7]=0;
				}
				
				if (!isset($v1array[8]))
				{
					$v1array[8]=0;
				}
				
				if (!isset($v1array[9]))
				{
					$v1array[9]=0;
				}
				// End backward compat
				
				$itemfromdb=form_element_calc_ACC($v1array[0],$v1array[2],$v1array[4],$v1array[3],$crate,$ctype,$v1array[7],$v1array[9]);

				$qry0 = "SELECT item,atrib1,atrib2,atrib3,qtype,catid,bullet FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$v1array[0]."';";
				$res0 = mssql_query($qry0);
				$row0 = mssql_fetch_array($res0);

				//echo "-------(".$v1array[0].")--------<br>";
				//show_array_vars($row0);

				if ($row0['qtype']!=32)
				{
					$x1="xxx".$v1array[0];

					$data_price=$itemfromdb[0];

					$qry1 = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row0['catid']."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					$strlen=strlen($row1['name']);
					$textout=wordwrap($row1['name'],23,"<br>",1);

					echo "           <tr>\n";
					echo "              <td NOWRAP class=\"".$tbg."\" align=\"left\" width=\"90\">".$textout."</td>\n";
					echo "              <td NOWRAP class=\"".$tbg."\" align=\"left\">\n";

					@showdescrip($row0['item'],$row0['atrib1'],$row0['atrib2'],$row0['atrib3'],$v1array[0]);

					if ($row0['qtype']==33)
					{
						$data_price=$v1array[3];
						//$data_price=$itemfromdb[0];

						if ($_SESSION['action']=="est")
						{
							$qry2 = "SELECT estid,bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid=".$_SESSION['estid']." AND bidaccid='".$v1array[0]."';";
							//echo "TEST EST";
						}
						elseif ($_SESSION['action']=="contract")
						{
							$qry2 = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd='".$viewarray['jadd']."' AND dbid='".$v1array[0]."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qry2 = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd='".$viewarray['jadd']."' AND dbid='".$v1array[0]."';";
						}
						//echo "TEST JOB";
						//echo $qry;
						$res2 = mssql_query($qry2);
						$row2 = mssql_fetch_array($res2);

						$textout=wordwrap(str_replace("\\", "",trim($row2['bidinfo'])),75,"<BR>");
                        
                        echo "                        <table align=\"left\" width=\"100%\" border=0>\n";
                        echo "                           <tr>\n";
                        echo "                              <td align=\"left\">\n";

						echo $textout;
                        //echo $row2['bidinfo'];
                        
                        echo "                              </td>\n";
                        echo "                           </tr>\n";
                        echo "                        </table>\n";
						
					}
					elseif ($row0['qtype']==20)
					{
						$qry2 = "SELECT item,code,atrib1,rp FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$itemfromdb[5]."';";
						$res2 = mssql_query($qry2);
						$row2 = mssql_fetch_array($res2);

						echo "                        <table align=\"left\" width=\"100%\" border=0>\n";
                        echo "                           <tr>\n";
                        echo "                              <td align=\"left\">\n";
                        
                        echo $row2['item'];
                        
                        echo "                              </td>\n";
                        echo "                           </tr>\n";
                        echo "                        </table>\n";
					}

					$comm=$itemfromdb[1];
					//$fdata_price=number_format($data_price, 2, '.', '');
					//$fcomm=number_format($comm, 2, '.', '');

					echo "              </td>\n";
					echo "              <td NOWRAP class=\"".$tbg."\" align=\"center\" width=\"30\">".$itemfromdb[2]."</td>\n";
					echo "              <td NOWRAP class=\"".$tbg."\" align=\"center\" width=\"30\">".$itemfromdb[4]."</td>\n";
					echo "              <td NOWRAP class=\"".$tbg."\" align=\"right\" width=\"65\">\n";
                    
                    if ($_SESSION['action']=="est" && isset($viewarray['esttype']) && $viewarray['esttype']=='Q')
                    {
                        $qry1  = "select aid,accid,ppb_price,adj_price,var_price from [jest].[dbo].[acc_price_adjusts] where oid=".$_SESSION['officeid']." and estid=".$_SESSION['estid'].";";
                        $res1  = mssql_query($qry1);
                        $nrow1 = mssql_num_rows($res1);
                        
                        if ($nrow1 > 0)
                        {
                            while($row1  = mssql_fetch_array($res1))
                            {
                                $acc_ar[$row1['accid']]=array($row1['aid'],$row1['ppb_price'],$row1['adj_price'],$row1['var_price']);
                            }
                        }

                        if (isset($acc_ar) && array_key_exists($v1array[0],$acc_ar))
                        {
                            $data_price=$acc_ar[$v1array[0]][2];
                            $fdata_price=number_format($data_price, 2, '.', '');
                            $fcomm=number_format($comm, 2, '.', '');
                            echo "                  <input type=\"hidden\" name=\"acc_pb_src[".$v1array[0]."][0]\" value=\"".number_format($acc_ar[$v1array[0]][1], 2, '.', '')."\">\n";
                            echo "                  <input class=\"bboxnobrb\" type=\"text\" name=\"acc_pb_src[".$v1array[0]."][1]\" value=\"".$fdata_price."\" size=\"7\" title=\"This is Amount Adjusted from the Original PriceBook Amount\">\n";
                        }
                        else
                        {
                            $fdata_price=number_format($data_price, 2, '.', '');
                            $fcomm=number_format($comm, 2, '.', '');
                            echo "                  <input type=\"hidden\" name=\"acc_pb_src[".$v1array[0]."][0]\" value=\"".$fdata_price."\">\n";
                            echo "                  <input class=\"bboxnobr\" type=\"text\" name=\"acc_pb_src[".$v1array[0]."][1]\" value=\"".$fdata_price."\" size=\"7\">\n";
                        }
                    }
                    else
                    {
                        $fdata_price=number_format($data_price, 2, '.', '');
                        $fcomm=number_format($comm, 2, '.', '');
                        echo $fdata_price;
                    }
                    
                    echo "              </td>\n";
                    
                    if (isset($viewarray['esttype']) && $viewarray['esttype']=='E')
                    {
                        echo "                     <td NOWRAP class=\"".$tbg."\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
                    }
                    

					if ($comm!=0)
					{
                        if (isset($viewarray['esttype']) && $viewarray['esttype']=='Q')
                        {	
                            echo "              <td NOWRAP class=\"".$tbg."\" align=\"right\" width=\"55\"><img src=\"../images/pixel.gif\"></td>\n";
                        }   
                        else
                        {
                            echo "              <td NOWRAP class=\"".$tbg."\" align=\"right\" width=\"55\"><div id=\"comm\">".$fcomm."</div></td>\n";
                        }
					}
					else
					{
						echo "              <td NOWRAP class=\"".$tbg."\" align=\"right\" width=\"55\"><img src=\"../images/pixel.gif\"></td>\n";
					}

					echo "              <td NOWRAP class=\"".$tbg."\" align=\"center\" width=\"25\">\n";

                    if ($row0['qtype'] < 48 || $row0['qtype'] > 52)
                    {
                        if ($_SESSION['subq']=="print")
                        {
                            echo "<div class=\"noPrint\">\n";
                        }

                        if ($_SESSION['action']=="est")
                        {
                            echo "                 <input class=\"transnb\" type=\"checkbox\" name=\"$x1\" value=\"".$v1array[0]."\" title=\"Check this Item to Remove from Estimate\">\n";
                        }

                        if ($_SESSION['subq']=="print")
                        {
                            echo "</div>\n";
                        }
                    }
                    else
                    {
                        echo "<img src=\"../images/pixel.gif\">\n";
                    }
                    
					echo "              </td>\n";
					echo "           </tr>\n";
					
					if ($row0['qtype']==33) //Bid Items: for Estimate Cost Drilldetail
					{
						if (!isset($_SESSION['estbidretail'][$v1array[0]]))
						{
							//echo "CREATED!<br>";
							$_SESSION['estbidretail'][$v1array[0]]=$fdata_price;
						}
					}
					
					if ($row0['qtype']==55||$row0['qtype']==72)
					{
						if ($viewarray['status']==2||$viewarray['status']==3)
						{
							stored_package_items($v1array[0],$filters);
							//echo "STORED<br>";
							//echo $filters."<br>";
						}
						elseif ($viewarray['status']==5)
						{
							stored_package_items($v1array[0],$filters);
							//echo "STORED<br>";
							//echo $filters."<br>";
						}
						elseif ($viewarray['status']==1)
						{
							package_items($v1array[0],$estdata);
							//echo "NOT STORED: ADD<br>";
						}
						else
						{
							package_items_quote($v1array[0],$estdata,$tbg);
							//echo "NOT STORED: EST";
						}
					}

					if ($row0['bullet'] > 0)
					{
						$tbullets=$tbullets+$row0['bullet'];
					}

					$tdata_price=$tdata_price+$data_price;
					$tcomm=$tcomm+$comm;
				}
			}
		}

		$rctotal=$rctotal+$tdata_price;
		$cctotal=$cctotal+$tcomm;
		$tacc_price=$tdata_price;
		//echo "RETAIL: ".$rctotal."<br>";
	}
}

function package_items_quote($rid,$estdata,$tbg)
{
   global $rctotal,$viewarray;
   
   $MAS=$_SESSION['pb_code'];

   $qry = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
   $res = mssql_query($qry);
   $row = mssql_fetch_array($res);

   $qry0 = "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$rid."';";
   $res0 = mssql_query($qry0);
   $nrow0= mssql_num_rows($res0);

   if ($nrow0 > 0)
   {
      while ($row0 = mssql_fetch_array($res0))
      {
        $qry1 = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row0['iid']."';";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);

        $qry2 = "SELECT * FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row1['catid']."';";
        $res2 = mssql_query($qry2);
        $row2 = mssql_fetch_array($res2);

        $qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
        $res3 = mssql_query($qry3);
        $row3 = mssql_fetch_array($res3);

        $adjquan=package_quan_set($row1['qtype'],$row1['quan_calc'],$row0['adjquan'],$viewarray['ps1'],$viewarray['ps2'],$viewarray['tzone'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7'],$viewarray['spa1'],$viewarray['spa2'],$viewarray['spa3'],$viewarray['deck']);
        $adjamt=$row0['adjamt'];

        if ($row0['adjtype']==1) // Adjusts
        {
           $adjquan=$row1['quan_calc']+$row0['adjquan'];
           $adjamt=$row1['rp']+$row0['adjamt'];
        }
        elseif ($row0['adjtype']==2) // Price Percent Adjust
        {
           $adjamt=($row1['rp']*$row0['adjamt'])*$adjquan;
        }
        elseif ($row0['adjtype']==3)
        {
           $adjquan=$row1['quan_calc']+$row0['adjquan'];
        }
        elseif ($row0['adjtype']==4) // Zero Price
        {
           $adjamt=($row1['rp']+($row1['rp'] * -1))*$row0['adjquan'];
        }
        elseif ($row0['adjtype']==5)
        {
           $adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
        }
        elseif ($row0['adjtype']==6)
        {
           $adjamt=($row1['rp']+($row1['rp'] * -1))*$row0['adjquan'];
           $adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
        }

        $fadjamt=number_format($adjamt, 2, '.', '');

        echo "                  <tr>\n";
        echo "                     <td NOWRAP class=\"".$tbg."\" align=\"right\" width=\"90\">Pkg Item</td>\n";
        echo "                     <td NOWRAP class=\"".$tbg."\" valign=\"top\" align=\"left\">\n";
        echo "                        <table align=\"left\" width=\"100%\" border=0>\n";
        echo "                           <tr>\n";
        echo "                              <td class=\"transbackfill\" align=\"center\" width=\"40\"></td>\n";
        echo "                              <td align=\"left\">".$row1['item']."</td>\n";
        echo "                           </tr>\n";
        echo "                        </table>\n";
        echo "                     </td>\n";
        echo "                     <td NOWRAP class=\"".$tbg."\" align=\"center\" width=\"30\">".$adjquan."</td>\n";
        echo "                     <td NOWRAP class=\"".$tbg."\" align=\"center\" width=\"30\">".$row3['abrv']."</td>\n";
        echo "                     <td NOWRAP class=\"".$tbg."\" align=\"right\" width=\"65\">\n";
        
        if (isset($viewarray['esttype']) && $viewarray['esttype']=='Q')
        {
            echo "                  <input type=\"hidden\" name=\"acc_pb_src[".$row0['iid']."][0]\" value=\"".$fadjamt."\">\n";
            echo "                  <input class=\"bboxnobr\" type=\"text\" name=\"acc_pb_src[".$row0['iid']."][1]\" value=\"".$fadjamt."\" size=\"7\">\n";
        }
        else
        {
            echo $fadjamt;
        }
        
        echo "                     </td>\n";
        
        if (isset($viewarray['esttype']) && $viewarray['esttype']=='E')
        {
            //echo "                     <td NOWRAP class=\"wh_undsidesr\" align=\"right\" width=\"55\"><div id=\"adjts\"><img src=\"images/pixel.gif\"></div></td>\n";
        }
        
        echo "                     <td NOWRAP class=\"".$tbg."\" align=\"right\" width=\"55\"><img src=\"../images/pixel.gif\"></td>\n";
        echo "                     <td NOWRAP class=\"".$tbg."\" align=\"right\" width=\"25\"><img src=\"../images/pixel.gif\"></td>\n";
        echo "                  </tr>\n";
        $rctotal=$rctotal+$adjamt;
      }
   }
}

function calcbyacc_add($estdata,$filters)
{
	$MAS=$_SESSION['pb_code'];
	global $bctotal,$rctotal,$cctotal,$tacc_price,$phsbcrc,$tbullets;

	$cdate=getdate();
	$viewarray=$_SESSION['viewarray'];
	$camt	=$viewarray['camt'];
	$ps1	=$viewarray['ps1'];
	$ps2	=$viewarray['ps2'];
	$ps4	=$viewarray['tzone'];
	$ps5	=$viewarray['ps5'];
	$ps6	=$viewarray['ps6'];
	$ps7	=$viewarray['ps7'];
	$spa1	=$viewarray['spa1'];
	$spa2	=$viewarray['spa2'];
	$spa3	=$viewarray['spa3'];

	if (!isset($showdetail))
	{
		$showdetail=0;
	}

	if (strlen($estdata) >=6)
	{
		$estAarray=explode(",",$estdata);
		if (is_array($estAarray))
		{
			$tdata_price=0;
			$tcomm=0;
			foreach($estAarray as $n1=>$v1)
			{
				$v1array=explode(":",$v1);
				//print_r($v1array);
				//echo $viewarray['added'];
				
				/*
				if ($viewarray['added'] >= '3/14/2006')
				{
				echo "ADDDED;";
				}
				*/

				if (empty($v1array[6]))
				{
					$ctype=0;
					$crate=0;
				}
				else
				{
					$ctype=$v1array[6];
					$crate=$v1array[5];
				}
	
				if (empty($v1array[7]))
				{
					$v1array[7]=0;
				}
				
				if (empty($v1array[9]))
				{
					$v1array[9]=0;
				}

				$itemfromdb=form_element_calc_ACC($v1array[0],$v1array[2],$v1array[4],$v1array[3],$crate,$ctype,$v1array[7],$v1array[9]);

				//$comm=$itemfromdb[1]*$itemfromdb[2];

				$qry0 = "SELECT item,atrib1,atrib2,atrib3,qtype,catid,bullet FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$v1array[0]."';";
				$res0 = mssql_query($qry0);
				$row0 = mssql_fetch_array($res0);

				if ($row0['qtype']!=32)
				{
					$x1="xxx".$v1array[0];

					$data_price=$itemfromdb[0];

					$qry1 = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row0['catid']."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					echo "           <tr>\n";
					echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\">".$row1['name']."</td>\n";
					echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\">\n";

					showdescrip($row0['item'],$row0['atrib1'],$row0['atrib2'],$row0['atrib3']);

					if ($row0['qtype']==33)
					{
						$getbidinfo=getbidinfo($v1array[0],$data_price);
						echo $getbidinfo[0];
					}
					elseif ($row0['qtype']==20)
					{
						$qry2 = "SELECT item,code,atrib1,rp FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$itemfromdb[5]."';";
						$res2 = mssql_query($qry2);
						$row2 = mssql_fetch_array($res2);

						echo "\n".$row2['item']."\n";
					}

					$comm=$itemfromdb[1];

					$fdata_price	=number_format($data_price, 2, '.', '');
					$fcomm		=number_format($comm, 2, '.', '');

					echo "              </td>\n";
					//echo strtotime($viewarray['added'])."<br>";
					//echo strtotime('3/14/2006');
					/*
					if (strtotime($viewarray['added']) >= strtotime('3/14/2006'))
					{
					echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">".$v1array[7]."XX</td>\n";
					}
					else
					{
					*/
					echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">".$itemfromdb[2]."</td>\n";
					//}

					echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">".$itemfromdb[4]."</td>\n";
					echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fdata_price."</td>\n";

					if ($comm!=0)
					{
						echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fcomm."</td>\n";
					}
					else
					{
						echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">&nbsp</td>\n";
					}

					echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"center\">\n";

					if ($row0['qtype'] < 48 || $row0['qtype'] > 52)
					{
						if ($_SESSION['subq']=="print")
						{
							echo "<div class=\"noPrint\">\n";
						}

						if ($_SESSION['action']!="job")
						{
							echo "                 <input class=\"transnb\" type=\"checkbox\" name=\"".$x1."\" value=\"".$v1array[0]."\">\n";
						}


						if ($_SESSION['subq']=="print")
						{
							echo "</div>\n";
						}

					}

					echo "              </td>\n";
					echo "           </tr>\n";

					if ($row0['qtype']==55||$row0['qtype']==72)
					{
						if ($viewarray['status']==2||$viewarray['status']==3)
						{
							stored_package_items($v1array[0],$filters);
							//echo "STORED<br>";
							//echo $filters."<br>";
						}
						elseif ($viewarray['status']==1)
						{
							package_items($v1array[0],$estdata);
							//echo "NOT STORED: ADD<br>";
						}
						else
						{
							package_items($v1array[0],$estdata);
							//echo "NOT STORED: EST";
						}
					}

					if ($row0['bullet'] > 0)
					{
						$tbullets=$tbullets+$row0['bullet'];
					}

					$tdata_price=$tdata_price+$data_price;
					$tcomm=$tcomm+$comm;
				}
			}
		}

		$rctotal=$rctotal+$tdata_price;
		$cctotal=$cctotal+$tcomm;
		$tacc_price=$tdata_price;
		//echo "RETAIL: ".$rctotal."<br>";
	}
}

function calcbyphsL($data,$bdata,$fdata,$job)
{
	global $bctotal,$bcadjtotal,$rctotal,$phsbcrc,$phsid,$phsnum,$phsitem;
	$MAS		=$_SESSION['pb_code'];
	$viewarray	=$_SESSION['viewarray'];
	$officeid   =$_SESSION['officeid'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$tcomm      =$viewarray['tcomm'];
	
	$showdetail	=0;
	
	//print_r($viewarray);
	//echo "<br>---<br>";

	if ($job!=1)
	{
		$costitems	=setcostitemlist($data,"L");
	}
	else
	{
		$costitems	=$data;
	}
	//print_r($costitems);

	$qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryC = "SELECT id,quan,price FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$ps1."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$pbaseprice=$rowC[2]-$discount;
	$pbaseprice=number_format($pbaseprice, 2, '.', '');

	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"50\"><b>Code</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"left\" width=\"100\"><b>Phase</b></td>\n";

	if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
	{
		echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Labor Items</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b>Quant</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"70\">";
		
		if ($_SESSION['call']!='view_wo')
		{
			echo "<b>Cost</b>";
		}
		
		echo "</td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"left\"><b>Labor Items</b></td>\n";
	}
	
	//echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"100\"><b>Total</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"70\">";
		
	if ($_SESSION['call']!='view_wo')
	{
		echo "<b>Total</b>";
	}
		
	echo "</td>\n";

	if ($_SESSION['manphsadj']==1)
	{
		echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Adjust</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Adj Total</b></td>\n";
	}

	echo "           </tr>\n";

	while($rowA = mssql_fetch_row($resA))
	{
		if ($rowA[1]=="503L" && $_SESSION['call']!='view_wo')
		{
			$comm		=round($tcomm);
			$fcomm	    =number_format($comm, 2, '.', '');
			echo "           <tr>\n";
			echo "              <td NOWRAP align=\"center\" class=\"wh\"><b>".$rowA[1]."</b></td>\n";
			echo "              <td NOWRAP align=\"left\" class=\"wh\"><b>".$rowA[2]."</b></td>\n";

			if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
			{
				echo "              <td NOWRAP align=\"right\" class=\"wh\">&nbsp</td>\n";
				echo "              <td NOWRAP align=\"right\" class=\"wh\">&nbsp</td>\n";
				echo "              <td NOWRAP align=\"right\" class=\"wh\" width=\"70\">&nbsp</td>\n";
			}
			else
			{
				echo "              <td NOWRAP colspan=\"3\" align=\"right\" class=\"wh\">&nbsp</td>\n";
			}

			echo "              <td NOWRAP align=\"right\" class=\"wh\"><b>".$fcomm."</b></td>\n";

			if ($_SESSION['manphsadj']==1)
			{
				echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
				echo "              <td NOWRAP align=\"right\" class=\"wh\"><b>".$fcomm."</b></td>\n";
			}

			echo "           </tr>\n";
			$bctotal=$bctotal+$comm;
			$rctotal=$rctotal;
		}
        /*elseif ($rowA[1]=="504L" && $_SESSION['call']!='view_wo')
		{
			$comm		=round($tcomm);
			$fcomm	    =number_format($comm, 2, '.', '');
			echo "           <tr>\n";
			echo "              <td NOWRAP align=\"center\" class=\"wh\"><b>".$rowA[1]."</b></td>\n";
			echo "              <td NOWRAP align=\"left\" class=\"wh\"><b>".$rowA[2]."</b></td>\n";

			if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
			{
				echo "              <td NOWRAP align=\"right\" class=\"wh\">&nbsp</td>\n";
				echo "              <td NOWRAP align=\"right\" class=\"wh\">&nbsp</td>\n";
				echo "              <td NOWRAP align=\"right\" class=\"wh\" width=\"70\">&nbsp</td>\n";
			}
			else
			{
				echo "              <td NOWRAP colspan=\"3\" align=\"right\" class=\"wh\">&nbsp</td>\n";
			}

			echo "              <td NOWRAP align=\"right\" class=\"wh\"><b>".$fcomm."</b></td>\n";

			if ($_SESSION['manphsadj']==1)
			{
				echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
				echo "              <td NOWRAP align=\"right\" class=\"wh\"><b>".$fcomm."</b></td>\n";
			}

			echo "           </tr>\n";
			$bctotal=$bctotal+$comm;
			$rctotal=$rctotal;
		}*/
		elseif ($rowA[1]=="505L" && $_SESSION['call']!='view_wo')
		{
			if ($job!=1)
			{
				$royalty		=calc_royalty_est($costitems,$rowA[0]);
			}
			else
			{
				$royalty		=calc_royalty_job($costitems,$rowA[0]);
			}
			$froyalty	=number_format($royalty, 2, '.', '');

			if (isset($viewarray['royrel']) && $viewarray['royrel'] > 0)
			{
				$tbg="yel";
			}
			else
			{
				$tbg="wh";
			}

			echo "           <tr>\n";
			echo "              <td NOWRAP align=\"center\" class=\"".$tbg."\"><b>".$rowA[1]."</b></td>\n";
			echo "              <td NOWRAP align=\"left\" class=\"".$tbg."\"><b>".$rowA[2]."</b></td>\n";

			if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
			{
				echo "              <td NOWRAP align=\"right\" class=\"".$tbg."\">&nbsp</td>\n";
				echo "              <td NOWRAP align=\"right\" class=\"".$tbg."\">&nbsp</td>\n";
				echo "              <td NOWRAP align=\"right\" class=\"".$tbg."\" width=\"70\">&nbsp</td>\n";
			}
			else
			{
				echo "              <td NOWRAP colspan=\"3\" align=\"right\" class=\"".$tbg."\">&nbsp</td>\n";
			}

			echo "              <td NOWRAP align=\"right\" class=\"".$tbg."\"><b>".$froyalty."</b></td>\n";

			if ($_SESSION['manphsadj']==1)
			{
				echo "              <td NOWRAP align=\"right\" class=\"".$tbg."\"></td>\n";
				echo "              <td NOWRAP align=\"right\" class=\"".$tbg."\"><b>".$froyalty."</b></td>\n";
			}

			echo "           </tr>\n";
			$bctotal=$bctotal+$royalty;
			$rctotal=$rctotal;
		}
		elseif ($rowA[1]!=0)
		{
			if ($job!=1)
			{
				phscalc($rowA[0],$rowA[1],$rowA[2],$costitems);
			}
			else
			{
				$adjamt=get_adj_amt($rowA[0]);
				$fdata=preg_replace("/,\Z/","",$fdata);
				jobphscalc($rowA[0],$rowA[1],$rowA[2],$costitems,$bdata,$fdata,$adjamt);
			}
			
			$bctotal		=$bctotal+$phsbcrc[0];
			$rctotal		=$rctotal+$phsbcrc[1];
			$bcadjtotal	=$bcadjtotal+$phsbcrc[2];
			$phsbcrc[0]	=0;
			$phsbcrc[1]	=0;
			$phsbcrc[2]	=0;
		}
		else
		{
			echo "           <tr>\n";
			echo "              <td NOWRAP class=\"wh\" align=\"left\"></td>\n";
			echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
			echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
			echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
			echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
			echo "           </tr>\n";
		}
	}

	//if (!empty($_POST['jobid'])||!empty($_POST['njobid']))
	if ($_SESSION['action']!="est" && !empty($viewarray['jobid'])||!empty($viewarray['njobid']))
	{
		// Addendum Display
		if ($_SESSION['action']=="contract")
		{
			$qryW = "SELECT MAX(jadd) AS mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
			$qryV = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd!=0;";
			$qryT = "SELECT taxrate FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
		}
		elseif ($_SESSION['action']=="job")
		{
			$qryW = "SELECT MAX(jadd) AS mjadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
			$qryV = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd!=0;";
			$qryT = "SELECT taxrate FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."';";
		}
		$resV = mssql_query($qryV);
		$nrowV= mssql_num_rows($resV);

		$resW = mssql_query($qryW);
		$rowW = mssql_fetch_array($resW);

		$resT = mssql_query($qryT);
		$rowT = mssql_fetch_array($resT);

		if ($nrowV > 0)
		{
			//echo $qryV."<br>";
			$addprf		=0;
			$addprl		=0;
			$addprm		=0;
			$xaddpr		=0;
			$ftaddpr		=0;
			$subxaddpr	=0;
			//$shw_pmas_add="";

			while ($rowV= mssql_fetch_array($resV))
			{
				if ($rowV['add_type']==0)
				{
					$add_type="Cust";
				}
				else
				{
					$add_type="GM";
				}

				if ($rowV['post_add']==0)
				{
					$padd_type="";
				}
				else
				{
					$padd_type=" (Post MAS)";
				}

				if ($_SESSION['action']=="contract")
				{
					$shw_pmas_add=show_postmas_add($viewarray['jobid'],$rowV['jadd'],$rowV['post_add'],$padd_type);
				}
				else
				{
					$shw_pmas_add=show_postmas_add($viewarray['njobid'],$rowV['jadd'],$rowV['post_add'],$padd_type);
				}
				$viewarray['phsjadd']=$rowV['jadd'];

				if (strlen($rowV['pcostlabdiff']) >= 3)
				{
					$addprf=addendum_filter_labor_cost($rowV['pcostlabdiff'],$rowV['jadd'],$rowW['mjadd']);
					//$addprf=addendum_labor_cost($rowV['filtersdiff'],$rowV['jadd'],$rowW['mjadd']);
					$xaddpr=$addprf[0];
				}

				if (strlen($rowV['pcostmatdiff']) >= 3)
				{
					$addprf=addendum_filter_mat_cost($rowV['pcostmatdiff'],$rowV['jadd'],$rowW['mjadd']);
					//$addprf=addendum_labor_cost($rowV['filtersdiff'],$rowV['jadd'],$rowW['mjadd']);
					$xaddpr=$addprf[0];
				}

				if (strlen($rowV['costlabdiff']) >= 3)
				{
					$addprl=addendum_labor_cost($rowV['costlabdiff'],$rowV['jadd'],$rowW['mjadd']);
					$xaddpr=$xaddpr+$addprl[0];
				}

				if (strlen($rowV['costmatdiff']) >= 3)
				{
					$addprm=addendum_mat_cost($rowV['costmatdiff'],$rowV['jadd'],$rowW['mjadd']);
					$xaddpr=$xaddpr+$addprm[0];
				}

				$maddcm	=$rowV['raddncm_man'];

				$ftaxamt=0;
				if ($rowB['stax']==1)
				{
					if (!empty($rowV['taxrate']) && $rowV['taxrate']!='0.0')
					{
						$ctaxrate=$rowV['taxrate'];
					}
					else
					{
						$ctaxrate=$rowT['taxrate'];
					}

					$fctaxrate	=number_format($ctaxrate, 3, '.', '');
					$ftaxamt	=number_format($rowV['raddnpr_man']*$ctaxrate, 2, '.', '');
					$xaddpr	=$xaddpr+$ftaxamt;
					//echo		"1: ".$xaddpr,"<br>";
					if (empty($_POST['showtotals']))
					{
						echo "           <tr>\n";
						echo "              <td NOWRAP class=\"gray\" align=\"center\">60".$rowV['jadd']."L</td>\n";
						echo "              <td NOWRAP align=\"left\" class=\"gray\">&nbspMisc (530L)</td>\n";
						echo "              <td NOWRAP align=\"left\" class=\"gray\">&nbsp&nbspSales Tax</td>\n";
						echo "              <td NOWRAP align=\"center\" class=\"gray\">".$fctaxrate."</td>\n";
						echo "              <td NOWRAP align=\"right\" class=\"gray\">".$ftaxamt."&nbsp</td>\n";
						echo "              <td NOWRAP width=\"65\" class=\"gray\" align=\"right\"></td>\n";
						echo "           </tr>\n";
					}
				}

				// 
				$bi=disp_cost_biditems(0,$rowV['jadd']);
				$xaddpr	=$xaddpr+$bi;
				
				$ma=disp_mpa_cost(0,$rowV['jadd']);
				$xaddpr	=$xaddpr+$ma;
				
				if ($rowV['post_add']!=1)
				{
					$faddpr 	=number_format(round($xaddpr), 2, '.', '');
					$tx=1;
					//$xaddpr	=$xaddpr+$faddpr;
				}
				else
				{
					$faddpr 	=number_format(round($rowV['raddncs_man']), 2, '.', '');
					$xaddpr	=$xaddpr+$faddpr;
					$tx=2;
				}
				//echo		"3: ".$xaddpr,"<br>";
				echo "           <tr>\n";
				echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>60".$rowV['jadd']."L</b></td>\n";
				echo "              <td NOWRAP align=\"left\" class=\"wh\"><b>".$add_type." Addendum<b></td>\n";
				echo "              <td NOWRAP colspan=\"3\" align=\"left\" class=\"wh\">".$shw_pmas_add."</td>\n";
				echo "              <td NOWRAP width=\"70\" class=\"wh\" align=\"right\"><b>";
				
				if ($_SESSION['call']!='view_wo')
				{
					echo number_format($xaddpr, 2, '.', '');
				}
				
				echo "</b></td>\n";
				echo "           </tr>\n";
				$subxaddpr	=$subxaddpr+$xaddpr;
				//echo		"4: ".$xaddpr,"<br>";
				$xaddpr		=0;
				$faddpr		=0;
			}
			$bctotal		=$bctotal+$subxaddpr;
		}
	}
}

function calcbyphsM($data,$fdata,$job)
{
	global $bmtotal,$bmadjtotal,$rmtotal,$cmtotal,$phsbcrc,$phsid,$phsnum,$phsitem;
	
	$MAS			=$_SESSION['pb_code'];
	$viewarray	=$_SESSION['viewarray'];
	$officeid   =$_SESSION['officeid'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$tcomm      =$viewarray['tcomm'];
	$showdetail	=0;

	if ($job!=1)
	{
		$costitems	=setcostitemlist($data,"M");
	}
	else
	{
		$costitems	=$data;
	}
	//print_r($costitems);

	$qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phstype='M' ORDER BY seqnum ASC;";
	$resA = mssql_query($qryA);

	$qryC = "SELECT id,quan,price FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$ps1."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"50\"><b>Code</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"left\" width=\"100\"><b>Phase</b></td>\n";

	if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
	{
		echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Material Items</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b>Quant</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"70\">";
		
		if ($_SESSION['call']!='view_wo')
		{
			echo "<b>Cost</b>";
		}
			
		echo "</td>\n";
	}
	else
	{
		echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"left\"><b>Material Items</b></td>\n";
	}

	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"70\">";
		
	if ($_SESSION['call']!='view_wo')
	{
		echo "<b>Total</b>";
	}
		
	echo "</td>\n";

	if ($_SESSION['manphsadj']==1)
	{
		echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Adjust</b></td>\n";
		echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Adj Total</b></td>\n";
	}

	echo "           </tr>\n";

	while($rowA = mssql_fetch_row($resA))
	{
		if ($rowA[1]!=0)
		{
			if ($job!=1)
			{
				phsMcalc($rowA[0],$rowA[1],$rowA[2],$costitems);
			}
			else
			{
				$adjamt=get_adj_amt($rowA[0]);
				jobphsMcalc($rowA[0],$rowA[1],$rowA[2],$costitems,$fdata,$adjamt);
			}
			$bmtotal		=$bmtotal+$phsbcrc[0];
			$rmtotal		=$rmtotal+$phsbcrc[1];
			//echo "Phase: ".$rowA[0]."| P Total".$phsbcrc[0]." | G Total: ".$bmtotal."<br>";
			$bmadjtotal	=$bmadjtotal+$phsbcrc[2];
		}
		else
		{
			echo "           <tr>\n";
			echo "              <td NOWRAP align=\"right\" class=\"wh\"><b>$rowA[4] Total</b></td>\n";
			echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
			echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
			echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
			echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
			echo "           </tr>\n";
		}
	}
}

function deckcalc($ps1,$tdeck)
{
	$c=2.16;
	$cant=$ps1*$c;
	$rdeck=$tdeck-$cant;
	$deckar=array(0=>$cant,1=>$rdeck); //[0]=Included Deck, [1]=Deck Chrg
	return $deckar;
}

function comm_calc($rc,$pcomm,$comm)
{
	if ($pcomm!=0)
	{
		$tcomm=$rc*$pcomm;
	}
	else
	{
		$tcomm=$comm;
	}
	return $tcomm;
}

function form_element_calc_ACC($id,$quan,$code,$amt,$ctype,$crate,$mquan,$chgproc)
{
	//echo "CT: ".$ctype."<br>";
	$MAS=$_SESSION['pb_code'];
	global $rc,$rcexport,$invarray;

	$viewarray	=$_SESSION['viewarray'];
	$officeid   =$_SESSION['officeid'];
	$camt		=$viewarray['camt'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];

	$ia			=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gl			=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
	$spa_ia		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$spa_gl		=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	$qryA 		= "SELECT * FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND id='".$id."';";
	$resA 		= mssql_query($qryA);
	$rowA 		= mssql_fetch_array($resA);
    
    if (isset($viewarray['esttype']) && $viewarray['esttype']=='Q')
    {
        $qryB   = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$viewarray['estid']."';";
        $resB   = mssql_query($qryB);
        $rowB   = mssql_fetch_array($resB);
        
        //$estdata = $rowB['estdata'];
        //echo "estdata: ".$estdata."<br>";
        $estd_ar=array();
        $r_estdata=explode(",",$rowB['estdata']);
        
        foreach ($r_estdata as $n=>$v)
        {
            $v1=explode(":",$v);
            $estd_ar[$v1[0]]=array($v1[0],$v1[1],$v1[2],$v1[3],$v1[4],$v1[5],$v1[6],$v1[7]);
        }
        
        /*echo '<pre>';
        
        print_r($estd_ar);
        
        echo '<pre>';*/
    }
    
	//echo "PS1: ".$ps1."<br>";
	//echo "PS2: ".$ps2."<br>---<br>";
	//echo "IID: ".$id."<br>---<br>";
    /*
	echo "STAT: ".$viewarray['status']."<br>";
	echo "QTYP: ".$rowA['qtype']."<br>";
	echo "ITEM: ".$rowA['item']."<br>";
	echo "AMOT: ".$amt."<br>";
	echo "QUAN: ".$quan."<br>---<br>";
    */
	
	if ($viewarray['status']==2 || $viewarray['status']==3 || $viewarray['status']==5)
	{
		//echo "JOB<br>";
		$rprice	=$amt;
		$rcrate	=$crate;
		$rctype	=$ctype;
	}
	else
	{
        if (isset($viewarray['esttype']) && $viewarray['esttype']=='Q' && isset($estd_ar[$id]) && is_array($estd_ar[$id]))
        {
            $rprice	=$estd_ar[$id][3];
            //echo "Quote<br>";
        }
        else
        {
            $rprice	=$rowA['rp'];
        }

		if ($rowA['commtype'] >= 1)
		{
			$rcrate	=$rowA['crate'];
			$rctype	=$rowA['commtype'];
		}
		else
		{
			$rcrate	=0;
			$rctype	=0;
		}
	}

	//echo "Rate: ".$rcrate."<br>";
	//echo "Type: ".$rctype."<br>";

	if ($rowA['mtype']!=0)
	{
		$qryC = "SELECT abrv FROM mtypes WHERE mid=".$rowA['mtype'].";";
		$resC = mssql_query($qryC);
		$rowC = mssql_fetch_array($resC);

		$uom  =$rowC['abrv'];
	}
	else
	{
		$uom  ="n/a";
	}

	//Gets CODE info
	if ($rowA['qtype']==18||$rowA['qtype']==19||$rowA['qtype']==20||$rowA['qtype']==21||$rowA['qtype']==22||$rowA['qtype']==23)
	{
		$qryB = "SELECT * FROM material_master WHERE officeid=".$_SESSION['officeid']." AND code=".$code.";";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);
		$nrowB= mssql_num_rows($resB);

		if ($nrowB > 1)
		{
			$rc_code   =0;
			$cc_code   =0;
			$name_code ="Multi";
		}
		else
		{
			$rc_code   =$rowB['rp'];
		}
	}

	//echo "PR: ".$rprice."<br>";
	//echo "QU: ".$quan."<br>";

	// Calculation Loop for Retail
	//echo "OMQUAN: ".$mquan."<br>";
	//echo "OCHGPROC: ".$chgproc."<br>";
	$calc_out	=uni_calc_loop($rowA['qtype'],0,$rprice,$rowA['lrange'],$rowA['hrange'],$quan,$rowA['quan_calc'],$ia,$gl,$spa_ia,$spa_gl,$code,0,0,0,$mquan,$chgproc);
    $rc			=$calc_out[1];
	$quan_out	=$calc_out[2];

	/*
	echo "<pre>";
	print_r($calc_out);
	echo "</pre>";
	*/

	if ($rowA['supplier']!=0)
	{
		$qryX = "SELECT com_rate FROM offices WHERE officeid='".$_SESSION['officeid']."';";
		$resX = mssql_query($qryX);
		$rowX = mssql_fetch_array($resX);

		$cc=$rprice*$rowX['com_rate'];
		//echo "HIT";
	}
	else
	{
		//echo "NOT HIT: ".$rctype;
		if ($rctype==1)
		{
			//echo "TYPE 1<br>";
			if ($rowA['qtype']==33)
			{
				$cc=($amt*$rcrate)*$quan_out;
			}
			elseif ($rowA['qtype']==20)
			{
				$cc=($rc_code*$rcrate)*$quan_out;
			}
			elseif ($rowA['qtype']==5 || $rowA['qtype']==6 || $rowA['qtype']==7 || $rowA['qtype']==58)
			{
				$cc=$rc*$rcrate;
				//echo "ICOMM: ".$cc."($rc)($rprice*$rcrate)*$quan_out<br>";
			}
			else
			{
				$cc=($rprice*$rcrate)*$quan_out;
				//echo "ICOMM: ".$cc."($rctype)($rprice*$rcrate)*$quan_out<br>";
			}
		}
		elseif ($rctype==2)
		{
			//echo "TYPE 2<br>";
			$cc=$rcrate*$quan_out;
		}
		elseif ($rctype==3)
		{
			//echo "TYPE 2<br>";
			$cc=$rcrate;
		}
		else
		{
			//echo "TYPE 0<br>";
			$cc=0;
		}
	}
	//echo "ICOMM: ".$cc."($rctype)()<br>";

	//echo "RC: ".$rc."<br>";
	$rcexport= array(0=>$rc,1=>$cc,2=>$quan_out,3=>0,4=>$uom,5=>$code);
	return $rcexport;
}

function phscalc($phsid,$phsnum,$phsitem,$costitems)
{
	//echo "TEST";
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;

	$eeid=$_SESSION['estid'];

	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];

	$iarea		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals			=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	labor_baseitems_calc($phsid,0);

	// *** ADD Accessory Cost Calcs Here ***
	if ($costitems[0] > 0)
	{
		foreach ($costitems as $pre_n=>$pre_v)
		{
			$quan	=$pre_v[1];
			$code	=0;
			$qryB = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND id='".$pre_v[0]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			if ($rowB['phsid']==$phsid)
			{
				if ($rowB['rinvid']!=0)  // Credit Code Loop
				{
					//echo "CRED1 QUAN: ".$quan."<BR>";
					$cr_out		=lab_credititem($rowB['rinvid'],$rowB['id'],$phsid,$quan,0);
					$bp			=$cr_out[0];
					$bc			=$bc+$bp;
				}

				if ($rowB['qtype']!=33)
				{
					if ($rowB['qtype'] >= 9 && $rowB['qtype'] <= 12)
					{
						if ($rowB['qtype'] == 9)
						{
							$cdquan=$ps1;
						}
						elseif ($rowB['qtype'] == 10)
						{
							$cdquan=$ps2;
						}
						elseif ($rowB['qtype'] == 11)
						{
							$cdquan=$iarea;
						}
						elseif ($rowB['qtype'] == 12)
						{
							$cdquan=$gals;
						}

						$code		=$rowB['accid'];
						$specout	=getspecaccpbook($code,$cdquan,$rowB['quantity']);

						if ($specout[0]==0)
						{
							$bprice	=0;
							$quan	=0;
						}
						else
						{
							$bprice	=$specout[0];
						}
						$lrange	=$specout[1];
						$hrange	=$specout[2];
						$quantity=$rowB['quantity'];
					}
					else
					{
						$code		=$code;
						$bprice	=$rowB['bprice'];
						$lrange	=$rowB['lrange'];
						$hrange	=$rowB['hrange'];
						$quantity=$rowB['quantity'];
					}

					//$calc_out	=uni_calc_loop($rowB['qtype'],$rowB['bprice'],0,$rowB['lrange'],$rowB['hrange'],$quan,$rowB['quantity'],$iarea,$gals,0,0,$code,0,0,0,0,0);
					$calc_out	=uni_calc_loop($rowB['qtype'],$bprice,0,$lrange,$hrange,$quan,$quantity,$iarea,$gals,0,0,$code,0,0,0,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$bc			=$bc+$bp;

					if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
					{
						showitem($bp,0,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan_out,0,$pre_v[3]);
					}
				}
				/*
				elseif ($rowB['qtype']==33) // Bid Item
				{
					
					//echo "<pre>";
					//print_r($pre_v);
					//echo "</pre>";
					$qryC = "SELECT rid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND cid='".$pre_v[0]."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);
					$nrowC= mssql_num_rows($resC);

					//echo "LINKS: ".$qryC." ($nrowC)<br>";

					if ($nrowC > 0)
					{
						//echo "EST TEST";
						$qryCa = "SELECT * FROM bid_breakout WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND rdbid='".$pre_v[3]."' AND cdbid='".$pre_v[0]."';";
						$resCa = mssql_query($qryCa);
						$nrowCa= mssql_num_rows($resCa);

						//echo "BRKS: ".$qryCa."<br>";

						if ($nrowCa > 0)
						{
							$subbp=0;

							$qryCb = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$pre_v[3]."';";
							$resCb = mssql_query($qryCb);
							$rowCb = mssql_fetch_array($resCb);

							while($rowCa = mssql_fetch_array($resCa))
							{
								//echo "BRKS: ".$qryCa."<br>";
								$bp=$rowCa['bprice'];
								$subbp=$subbp+$bp;
							}
							//echo ":WITH";
							if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
							{
								showbiditem($subbp,0,$phsid,"Bid Item: ".$rowCb['bidinfo']." (Click <b>Edit</b> for Detail)",'','','',$quan,0,$pre_v[3],$pre_v[0],$_SESSION['estid'],$nrowCa);
							}
							$bc=$bc+$subbp;
						}
						else
						{
							//$qryD = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$rowC['rid']."';";
							$qryD = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$pre_v[3]."';";
							$resD = mssql_query($qryD);
							$rowD = mssql_fetch_array($resD);

							$qryE = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
							$resE = mssql_query($qryE);
							$rowE = mssql_fetch_array($resE);

							//echo "NBRKS: ".$qryD."<br>";

							$Xarray=explode(",",$rowE['estdata']);
							foreach ($Xarray as $n=>$v)
							{
								$subXarray=explode(":",$v);
								//if ($subXarray[0]==$rowC['rid'])
								if ($subXarray[0]==$pre_v[3])
								{
									$Xbp=$subXarray[3];
								}
							}

							$subbp=$Xbp;
							$subrp=0;
							$bc=$bc+$subbp;

							//echo ":WITHOUT";
							if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
							{
								showbiditem($subbp,$subrp,$rowB['phsid'],"Bid Item: ".$rowD['bidinfo'],'','','',$quan,0,$pre_v[3],$pre_v[0],$_SESSION['estid'],$nrowCa);
							}
						}
					}
				}
				*/
			}
		}
	}

	$bi=disp_cost_biditems($phsid,0);
	$bc=$bc+$bi;
	
	$ma=disp_mpa_cost($phsid,0);
	$bc=$bc+$ma;

	if ($phsid==8)
	{
		$viewarray['custallow']=$viewarray['custallow']+$bc;
	}

	if ($phsid==41 && $rowpre0['stax']==1)
	{
		$subbp=showtaxitem();
		$bc=$bc+$subbp;
	}

	$bc=round($bc);
	$adjamt=0;
	displayall($bc,0,$phsid,$phsitem,$adjamt);
	$phsbcrc=array(0=>$bc,0,0);
	return $phsbcrc;
}

function jobphscalc($phsid,$phsnum,$phsitem,$costitems,$bdata,$fdata,$adjamt)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;

	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];
	
	if (!isset($viewarray['custallow']))
	{
		$viewarray['custallow']=0;
	}

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	labor_baseitems_job_calc($phsid,$bdata,1);

	//echo "PRE: (".$fdata.")<br>";
	labor_filteritems_calc($phsid,$phsitem,$fdata);

	// *** ADD Accessory Cost Calcs Here ***
	$costitems=preg_replace("/,\Z/","",$costitems);
	if ($costitems > 0)
	{
		//echo "COSTS: ";
		//print_r($costitems);
		$edata=explode(",",$costitems);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			//echo "IV: ".$pre_iv."<br>";
			$pre_v=explode(":",$pre_iv);
			//echo "<pre>";
			//print_r($pre_v);
			//echo "</pre>";

			$rid     =$pre_v[0];
			$cid     =$pre_v[1];
			$quan		=$pre_v[2];
			$cost		=$pre_v[3];
			$qtype	=$pre_v[4];
			$code		=$pre_v[5];
			$lrange	=$pre_v[6];
			$hrange	=$pre_v[7];
			$iphsid  =$pre_v[8];
			$rinvid  =$pre_v[9];
			$quancalc=$pre_v[10];

			$qryB = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$iphsid."' AND id='".$pre_v[1]."' AND baseitem!=1";
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

			if ($phsid==$iphsid)
			{
				//if ($rid==319||$rid==321)
				//{
				//		echo "RID: ".$rid." (".$phsid.") (".$iphsid.") (".$qtype.") ".$qryB."<br>";
				//}

				//echo $rid."<br>";
				if ($rinvid!=0)  // Credit Code Loop
				{
					$cr_out		=lab_credititem($rinvid,$cid,$iphsid,$quan,0);
					$bp			=$cr_out[0];
					$bc			=$bc+$bp;
				}

				if ($rowB['qtype']!=33)
				{
					$calc_out	=uni_calc_loop($qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,$iarea,$gals,0,0,0,$a1,$a2,$a3,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$bc			=$bc+$bp;
					//showitem($bp,0,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan_out,0,$pre_v[3]);
					if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
					{
						//	echo $item.":".$rid.":";
						showitem($bp,0,$iphsid,$item,$a1,$a2,$a3,$quan_out,0,$rid);
					}
				}
				/*
				elseif ($qtype==33) // Bid Item
				{
					//echo $qryB."<br>";
					$qryC = "SELECT rid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND cid='".$pre_v[1]."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);
					$nrowC= mssql_num_rows($resC);

					//echo "LINKS: ".$qryC."<BR>";
					//echo "JOB TEST: ".$pre_v[0].":".$pre_v[3]."<br>";
					if ($nrowC > 0)
					{
						//echo "CNT: ".$nrowC."<br>";
						if ($_SESSION['action']=="contract")
						{
							$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND rdbid='".$pre_v[0]."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND rdbid='".$pre_v[0]."';";
						}
						$resCa = mssql_query($qryCa);
						$nrowCa= mssql_num_rows($resCa);

						//echo $qryCa."<BR>";

						if ($nrowCa > 0)
						{
							$subbp=0;
							while($rowCa = mssql_fetch_array($resCa))
							{
								$bp=$rowCa['bprice'];
								$subbp=$subbp+$bp;
							}

							if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
							{
								if ($_SESSION['action']=="contract")
								{
									showbiditem($subbp,0,$phsid,"Bid Item: ".$rowCa['sdesc'],'','','',$quan,0,$pre_v[0],$pre_v[1],$viewarray['jobid'],$nrowCa);
								}
								elseif ($_SESSION['action']=="job")
								{
									showbiditem($subbp,0,$phsid,"Bid Item: ".$rowCa['sdesc'],'','','',$quan,0,$pre_v[0],$pre_v[1],$viewarray['njobid'],$nrowCa);
								}
							}

							//echo ":WITH";
							$bc=$bc+$subbp;
						}
						else
						{
							if ($_SESSION['action']=="contract")
							{
								$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND dbid='".$pre_v[0]."';";
							}
							elseif ($_SESSION['action']=="job")
							{
								$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND dbid='".$pre_v[0]."';";
							}

							$resD = mssql_query($qryD);
							$rowD = mssql_fetch_array($resD);

							if ($_SESSION['action']=="contract")
							{
								$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd='".$viewarray['jadd']."';";
							}
							elseif ($_SESSION['action']=="job")
							{
								$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd='".$viewarray['jadd']."';";
							}

							$resE = mssql_query($qryE);
							$rowE = mssql_fetch_array($resE);

							$Xarray=explode(",",$rowE['estdata']);
							foreach ($Xarray as $n=>$v)
							{
								$subXarray=explode(":",$v);
								//if ($subXarray[0]==$rowC['rid'])
								if ($subXarray[0]==$pre_v[0])
								{
									$Xbp=$subXarray[3];
								}
							}

							$subbp=$Xbp;
							$subrp=0;
							$bc=$bc+$subbp;
							//echo ":WITHOUT";

							if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
							{
								if ($_SESSION['action']=="contract")
								{
									showbiditem($subbp,$subrp,$rowB['phsid'],"Bid Item: ".$rowD['bidinfo'],'','','',$quan,0,$pre_v[0],$pre_v[1],$viewarray['jobid'],0);
								}
								elseif ($_SESSION['action']=="job")
								{
									showbiditem($subbp,$subrp,$rowB['phsid'],"Bid Item: ".$rowD['bidinfo'],'','','',$quan,0,$pre_v[0],$pre_v[1],$viewarray['njobid'],0);
								}
							}
						}
					}
				}
				*/
			}
		}
	}
	
	$bi=disp_cost_biditems($phsid,$viewarray['jadd']);
	$bc=$bc+$bi;
	
	$ma=disp_mpa_cost($phsid,$viewarray['jadd']);
	$bc=$bc+$ma;

	if ($phsid==8)
	{
		$viewarray['custallow']=$viewarray['custallow']+$bc;
	}

	if ($phsid==41 && $rowpre0['stax']==1)
	{
		$subbp=showtaxitem();
		$bc=$bc+$subbp;
	}

	$bc=round($bc);
	displayall($bc,0,$phsid,$phsitem,$adjamt);
	$phsbcrc=array(0=>$bc,1=>0,2=>$adjamt);
	return $phsbcrc;
}

function addendum_filter_labor_cost($costitems,$anum,$tanum)
{
	//$anum=0;
	$MAS=$_SESSION['pb_code'];
	global $tchrg,$taxrate;
	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];
	$bc			=0;

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	// *** ADD Accessory Cost Calcs Here ***
	if ($costitems[0] > 0)
	{
		//echo "ADD COSTS: ";
		//print_r($costitems);
		$edata=explode(",",$costitems);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			$pre_v=explode(":",$pre_iv);
			//echo "<pre>";
			//print_r($pre_v);
			//echo "</pre>";

			$qryB = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$pre_v[5]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			$nrowB= mssql_num_rows($resB);

			//echo $qryB."<br>";

			$rid     =$pre_v[0];
			$cid     =$pre_v[5];
			$quan		=$pre_v[4];
			$cost		=$pre_v[6];
			//$qtype	=$pre_v[4];
			$qtype	=$rowB['qtype'];
			$code		=$pre_v[5];
			$lrange	=$rowB['lrange'];
			$hrange	=$rowB['hrange'];
			$iphsid  =$rowB['phsid'];
			$rinvid  =$rowB['rinvid'];
			$quancalc=$rowB['quantity'];

			if ($nrowB > 0)
			{
				$item	=$rowB['item'];
				$a1	=$rowB['atrib1'];
				$a2	=$rowB['atrib2'];
				$a3	=$rowB['atrib3'];
				
				if ($rowB['qtype']!=33)
				{
					$calc_out	=uni_calc_loop($qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,$iarea,$gals,0,0,0,$a1,$a2,$a3,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$bc			=$bc+$bp;
	
					if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
					{
						showadditem($bp,0,$iphsid,$item,$a1,$a2,$a3,$quan_out,0,$rid,$anum);
					}
				}
			}
			/*
			else
			{
				$item	='<b>Unlinked Entry</b>';
				$a1	='';
				$a2	='';
				$a3	='';
			}
			*/
/*
			if ($rowB['qtype']!=33)
			{
				$calc_out	=uni_calc_loop($qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,$iarea,$gals,0,0,0,$a1,$a2,$a3,0,0);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				$bc			=$bc+$bp;

				if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
				{
					showadditem($bp,0,$iphsid,$item,$a1,$a2,$a3,$quan_out,0,$rid,$anum);
				}
			}
*/			
		}
	}

	$bc=round($bc);
	$dout=array(0=>$bc,0,0);
	return $dout;
}

function addendum_filter_mat_cost($costitems,$anum,$tanum)
{
	//$anum=0;
	$MAS=$_SESSION['pb_code'];
	global $tchrg,$taxrate;
	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];
	$bc			=0;

	$iarea	=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals		=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	// *** ADD Accessory Cost Calcs Here ***
	if ($costitems[0] > 0)
	{
		//echo "ADD COSTS: ";
		//print_r($costitems);
		$edata=explode(",",$costitems);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			$pre_v=explode(":",$pre_iv);
			//echo "<pre>";
			//print_r($pre_v);
			//echo "</pre>";

			$qryB = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$pre_v[5]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			$nrowB= mssql_num_rows($resB);

			//echo $qryB."<br>";

			$rid     =$pre_v[0];
			$cid     =$pre_v[5];
			$quan		=$pre_v[4];
			$cost		=$pre_v[6];
			//$qtype	=$pre_v[4];
			$qtype	=$rowB['qtype'];
			$code		=$pre_v[5];
			$lrange	=0;
			$hrange	=0;
			$iphsid  =$rowB['phsid'];
			$rinvid  =$rowB['rinvid'];
			$quancalc=$rowB['quan_calc'];

			if ($nrowB > 0)
			{
				$item	=$rowB['item'];
				$a1	=$rowB['atrib1'];
				$a2	=$rowB['atrib2'];
				$a3	=$rowB['atrib3'];
				
				if ($rowB['qtype']!=33)
				{
					$calc_out	=uni_calc_loop($qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,$iarea,$gals,0,0,0,$a1,$a2,$a3,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$bc			=$bc+$bp;
	
					if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
					{
						showadditem($bp,0,$iphsid,$item,$a1,$a2,$a3,$quan_out,0,$rid,$anum);
					}
				}
			}
			/*
			else
			{
				$item	='<b>Unlinked Entry</b>';
				$a1	='';
				$a2	='';
				$a3	='';
			}
			*/

/*
			if ($rowB['qtype']!=33)
			{
				$calc_out	=uni_calc_loop($qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,$iarea,$gals,0,0,0,$a1,$a2,$a3,0,0);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				$bc			=$bc+$bp;

				if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
				{
					showadditem($bp,0,$iphsid,$item,$a1,$a2,$a3,$quan_out,0,$rid,$anum);
				}
			}
*/			
		}
	}

	$bc=round($bc);
	$dout=array(0=>$bc,0,0);
	return $dout;
}

function addendum_labor_cost($costitems,$anum,$tanum)
{
	//$anum=0;
	$MAS=$_SESSION['pb_code'];
	global $tchrg,$taxrate;

	//print_r($viewarray);
	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];
	$bc			=0;

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	// *** ADD Accessory Cost Calcs Here ***
	if ($costitems[0] > 0)
	{
		//echo "ADD COSTS: ";
		//print_r($costitems);
		$edata=explode(",",$costitems);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			$pre_v=explode(":",$pre_iv);
			//echo "<pre>";
			//print_r($pre_v);
			//echo "</pre>";

			$qryB = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$pre_v[1]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			$nrowB= mssql_num_rows($resB);

			$rid     =$pre_v[0];
			$cid     =$pre_v[1];
			$quan		=$pre_v[2];
			$cost		=$pre_v[3];
			$qtype	=$pre_v[4];
			$code		=$pre_v[5];
			$lrange	=$rowB['lrange'];
			$hrange	=$rowB['hrange'];
			$iphsid  =$rowB['phsid'];
			$rinvid  =$rowB['rinvid'];
			$quancalc=$rowB['quantity'];
			
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

			//echo "<pre>";
			//print_r($pre_v);
			//echo "</pre>";
			if ($nrowB > 0)
			{
				$item	=$rowB['item'];
				$a1	=$rowB['atrib1'];
				$a2	=$rowB['atrib2'];
				$a3	=$rowB['atrib3'];
			//}
			/*
			else
			{
				$item	='<b>Unlinked Entry</b>';
				$a1	='';
				$a2	='';
				$a3	='';
			}
			*/

				if ($rowB['qtype']!=33)
				{
					//$calc_out	=uni_calc_loop($qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,$iarea,$gals,0,0,0,$a1,$a2,$a3,0,0);
					$calc_out	=uni_calc_loop($qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,$iarea,$gals,0,0,0,$a1,$a2,$a3,$pre_v[7],$pre_v[9]);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$bc			=$bc+$bp;
	
					if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
					{
						showadditem($bp,0,$iphsid,$item,$a1,$a2,$a3,$quan_out,0,$rid,$anum);
					}
				}
				/*
				elseif ($qtype==33) // Bid Item
				{
					$qryC = "SELECT rid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND cid='".$pre_v[1]."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);
					$nrowC= mssql_num_rows($resC);
	
					//echo "LINKS: ".$qryC."<BR>";
					//echo "JOB TEST: ".$pre_v[0].":".$pre_v[3]."<br>";
					if ($nrowC > 0)
					{
						if ($_SESSION['action']=="contract")
						{
							$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND dbid='".$pre_v[0]."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND dbid='".$pre_v[0]."';";
						}
						$resD = mssql_query($qryD);
						$rowD = mssql_fetch_array($resD);
	
						if ($_SESSION['action']=="contract")
						{
							$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND rdbid='".$pre_v[0]."' AND jadd='".$viewarray['phsjadd']."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND rdbid='".$pre_v[0]."' AND jadd='".$viewarray['phsjadd']."';";
						}
	
						$resCa = mssql_query($qryCa);
						$nrowCa= mssql_num_rows($resCa);
	
						//echo "AJBIDS: ($anum) ($nrowCa)".$qryCa."<BR>";
	
						if ($nrowCa > 0)
						{
							$subbp=0;
							while($rowCa = mssql_fetch_array($resCa))
							{
								//echo $qryCa;
								$bp=$rowCa['bprice'];
								//$bp=$rowCa['bidamt'];
								$subbp=$subbp+$bp;
							}
	
							if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
							{
								if ($_SESSION['action']=="contract")
								{
									showbiditemadd($subbp,0,$iphsid,"Bid Item: ".$rowD['bidinfo'],'','','',$quan,0,$pre_v[0],$pre_v[1],$_POST['jobid'],$nrowCa);
								}
								elseif ($_SESSION['action']=="job")
								{
									showbiditemadd($subbp,0,$iphsid,"Bid Item: ".$rowD['bidinfo'],'','','',$quan,0,$pre_v[0],$pre_v[1],$_POST['njobid'],$nrowCa);
								}
							}
	
							//echo ":WITH";
							$bc=$bc+$subbp;
						}
						else
						{
							//$Xbp=0;
							if ($quan < 0)
							{
								$anum=$anum-1;
							}
	
							if ($_SESSION['action']=="contract")
							{
								$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND dbid='".$pre_v[0]."';";
							}
							elseif ($_SESSION['action']=="job")
							{
								$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND dbid='".$pre_v[0]."';";
							}
							$resD = mssql_query($qryD);
							$rowD = mssql_fetch_array($resD);
							//echo $qryD."<BR>";
	
							if ($_SESSION['action']=="contract")
							{
								$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."' AND jadd='".$anum."';";
							}
							elseif ($_SESSION['action']=="job")
							{
								$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$viewarray['njobid']."' AND jadd='".$anum."';";
							}
							$resE = mssql_query($qryE);
							$rowE = mssql_fetch_array($resE);
	
							//echo $anum.":".$tanum."=ANUM:TANUM<br>";
							$Xarray=explode(",",$rowE['estdata']);
							foreach ($Xarray as $n=>$v)
							{
								$subXarray=explode(":",$v);
								if ($subXarray[0]==$pre_v[0])
								{
									$Xbp=$subXarray[3];
								}
							}
	
							$subbp=$Xbp*$quan;
							$subrp=0;
							$bc=$bc+$subbp;
							// echo ":WITHOUT";
	
							if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
							{
								if ($_SESSION['action']=="contract")
								{
									showbiditemadd($subbp,$subrp,$iphsid,"Bid Item: ".$rowD['bidinfo'],'','','',$quan,0,$pre_v[0],$pre_v[1],$viewarray['jobid'],$nrowCa);
								}
								elseif ($_SESSION['action']=="job")
								{
									showbiditemadd($subbp,$subrp,$iphsid,"Bid Item: ".$rowD['bidinfo'],'','','',$quan,0,$pre_v[0],$pre_v[1],$viewarray['njobid'],$nrowCa);
								}
							}
						}
					}
				}
				*/
			}
		}
	}

	//echo "ADD: ".$anum."<br>";
	//$bi=disp_cost_biditems($phsid,$anum);
	//$bc=$bc+$bi;

	$bc=round($bc);
	$dout=array(0=>$bc,0,0);
	return $dout;
}

function addendum_mat_cost($costitems,$anum,$tanum)
{
	$MAS=$_SESSION['pb_code'];
	//global $viewarray;

	$officeid	=$_SESSION['officeid'];
	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$bc			=0;

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	// Option Calcs
	if ($costitems[0] > 0)
	{
		$edata=explode(",",$costitems);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			$pre_v=explode(":",$pre_iv);

			$qryB = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$pre_v[1]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			$rid			=$pre_v[0];
			$quan			=$pre_v[2];
			$subbprice  =$pre_v[3];
			$subqtype   =$pre_v[4];
			$code       =$pre_v[5];
			$subphsid	=$pre_v[6];
			$subitem		=$rowB['item'];
			$subquan_c  =$rowB['quan_calc'];
			$subatrib1	=$rowB['atrib1'];
			$subatrib2	=$rowB['atrib2'];
			$subatrib3	=$rowB['atrib3'];
			$subquan		=$quan;

			//if ($subphsid==$phsid)
			//{
			//$subrp =0; // Deprecated, remove on code cleanup
			//$rc    =0; // Deprecated, remove on code cleanup

			/*
			if ($rowB['rinvid']!=0)  // Credit Code Loop
			{
			//mat_credititem($rowB['rinvid'],$phsid,$quan);
			$cr_out		=mat_credititem($rowB['rinvid'],$phsid,$quan);
			$bp			=$cr_out[0];
			$bc			=$bc+$bp;
			}
			*/
			if ($rowB['qtype']!=33)
			{
				$calc_out	=uni_calc_loop($subqtype,$subbprice,0,0,0,$subquan,$subquan_c,$iarea,$gals,0,0,$code,$subatrib1,$subatrib2,$subatrib3,0,0);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				$bc			=$bc+$bp;

				if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
				{
					showaddMitem($bp,0,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$quan_out,0,$rid,$anum);
				}
			}
			/*
			elseif ($rowB['qtype']==33) // Bid Item
			{
			$qryC = "SELECT raccid FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			$qryD = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$rowC['raccid']."';";
			$resD = mssql_query($qryD);
			$rowD = mssql_fetch_array($resD);

			$qryE = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
			$resE = mssql_query($qryE);
			$rowE = mssql_fetch_array($resE);

			$Xarray=explode(",",$rowE['estdata']);

			foreach ($Xarray as $n=>$v)
			{
			$subXarray=explode(":",$v);

			if ($subXarray[0]==$rowC['raccid'])
			{
			$Xbp=$subXarray[3];
			}
			}

			$subitem="Bid Item<br><font class=\"7pt\">- ".$rowD['bidinfo']."</font>";
			$subatrib1='';
			$subatrib2='';
			$subatrib3='';
			$subbp=$Xbp;
			$bc=$bc+$subbp;
			}
			*/
			//}
		}
	}

	$dout=array(0=>$bc,0,0);
	return $dout;
}

function phsMcalc($phsid,$phsnum,$phsitem,$costitems)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$bc;

	$officeid=$_SESSION['officeid'];

	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];

	$iarea	=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals		=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	$qry		="SELECT bprice,rprice,invid,item,commtype,crate,atrib1,atrib2,atrib3,phsid,rinvid,quan_calc,seqnum,matid FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND baseitem=1 ORDER by seqnum;";
	$res		=mssql_query($qry);
	$nrows	=mssql_num_rows($res);

	$bc=0;
	$rc=0;

	if ($nrows > 0)
	{
		while($row=mssql_fetch_row($res))
		{
			if ($row[13]!=0)
			{
				$qrya		="SELECT bp,rp FROM material_master WHERE id='".$row[13]."';";
				$resa		=mssql_query($qrya);
				$rowa	   =mssql_fetch_array($resa);

				$bcsub=$rowa[0];
				$rcsub=$rowa[1];
			}
			else
			{
				$bcsub=$row[0];
				$rcsub=$row[1];
			}
			$quan =$row[11];
			//echo $qry."<br>";
			if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
			{
				showMitem($bcsub,$rcsub,$row[9],$row[3],$row[6],$row[7],$row[8],$quan,0,0);
			}
			//echo $qry."<br>";
			$bc=$bc+$bcsub;
			$rc=$rc+$rcsub;
			$cc=0;
		}
	}
	else
	{
		$bc=0;
		$rc=0;
		$cc=0;
	}

	// Option Calcs
	if ($costitems[0] > 0)
	{
		foreach ($costitems as $pre_n=>$pre_v)
		{
			//echo 'M_inv:'.$pre_v[0].'<br>';
			$qryB = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND invid='".$pre_v[0]."' AND baseitem!=1";
			//echo $qryB.'<br>';
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			if ($rowB['matid']!=0)
			{
				$qryBa		="SELECT bp FROM material_master WHERE id='".$rowB['matid']."';";
				$resBa		=mssql_query($qryBa);
				$rowBa	   =mssql_fetch_array($resBa);

				if ($rowB['qtype']!=56)
				{
					$subbprice	=$rowBa[0];
					$subatrib3	=$rowB['atrib3'];
				}
				else
				{
					$subbprice	=$rowB['bprice'];
					$subatrib3	=$rowBa[0]; // Used for IA Div by Calc Base+ Method
				}
			}
			else
			{
				$subbprice=$rowB['bprice'];
				$subatrib3	=$rowB['atrib3'];
			}

			$quan			=$pre_v[1];
			$subqtype   =$rowB['qtype'];
			$subphsid	=$rowB['phsid'];
			$subitem		=$rowB['item'];
			$subquan_c  =$rowB['quan_calc'];
			$subatrib1	=$rowB['atrib1'];
			$subatrib2	=$rowB['atrib2'];
			//$subatrib3	=$rowB['atrib3'];
			$subquan		=$quan;
			$rid			=$pre_v[3];
			$code			=$pre_v[2];

			if ($rowB['phsid']==$phsid)
			{
				//show_array_vars($rowB);
				$subrp =0; // Deprecated, remove on code cleanup
				$rc    =0; // Deprecated, remove on code cleanup

				if ($rowB['rinvid']!=0)  // Credit Code Loop
				{
					//echo "QRYB: ".$qryB."<br>";
					//ECHO "SUBP: ".$subbprice."<br>";
					//ECHO "RINV: ".$rowB['rinvid']."<br>";
					$cr_out		=mat_credititem($rowB['rinvid'],$phsid,$quan);
					$bp			=$cr_out[0];
					$bc			=$bc+$bp;
				}

				if ($rowB['qtype']!=33)
				{
					/*if ($rowB['qtype']==56)
					{
						echo "IAREA: ".$iarea."<br>";
						echo "SUBBP: ".$subbprice."<br>";
						echo "SUBA3: ".$subatrib3."<br>";
					}*/
					
					//echo $qryB."<br>";
					$calc_out		=uni_calc_loop($subqtype,$subbprice,0,0,0,$subquan,$subquan_c,$iarea,$gals,0,0,$code,$subatrib1,$subatrib2,$subatrib3,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$bc			=$bc+$bp;

					if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
					{
						//echo 'M_bp:'.$bp.'<br>';
						showMitem($bp,0,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$quan_out,0,$rid);
					}
				}
				/*
				elseif ($rowB['qtype']==33) // Bid Item
				{
					$qryC = "SELECT raccid FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$pre_v[0]."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);

					$qryD = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$rowC['raccid']."';";
					$resD = mssql_query($qryD);
					$rowD = mssql_fetch_array($resD);

					$qryE = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
					$resE = mssql_query($qryE);
					$rowE = mssql_fetch_array($resE);

					$Xarray=explode(",",$rowE['estdata']);

					foreach ($Xarray as $n=>$v)
					{
						$subXarray=explode(":",$v);

						if ($subXarray[0]==$rowC['raccid'])
						{
							$Xbp=$subXarray[3];
						}
					}

					$subitem="Bid Item<br><font class=\"7pt\">- ".$rowD['bidinfo']."</font>";
					$subatrib1='';
					$subatrib2='';
					$subatrib3='';
					$subbp=$Xbp;
					$bc=$bc+$subbp;
				}
				*/
			}
		}
	}

	$bi=disp_cost_biditems($phsid,$viewarray['jadd']);
	$bc=$bc+$bi;
	
	$ma=disp_mpa_cost($phsid,0);
	$bc=$bc+$ma;

	$bc=round($bc);
	$adjamt=0;
	displayMall($bc,$rc,$cc,$phsid,$phsitem,$adjamt);
	$phsbcrc=array(0=>$bc,$rc,$cc);
	return $phsbcrc;
}

function jobphsMcalc($phsid,$phsnum,$phsitem,$costitems,$fdata,$adjamt)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$bc;

	$officeid=$_SESSION['officeid'];

	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	$qry   ="SELECT bprice,rprice,invid,item,commtype,crate,atrib1,atrib2,atrib3,phsid,rinvid,quan_calc,seqnum FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND baseitem=1 ORDER by seqnum;";
	$res   =mssql_query($qry);
	$nrows =mssql_num_rows($res);

	$bc=0;
	$rc=0;

	if ($nrows > 0)
	{
		while($row=mssql_fetch_row($res))
		{
			$bcsub=$row[0];
			$rcsub=$row[1];
			$quan =$row[11];

			if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
			{
				showMitem($bcsub,$rcsub,$row[9],$row[3],$row[6],$row[7],$row[8],$quan,0,0);
			}

			$bc=$bc+$bcsub;
			$rc=$rc+$rcsub;
			$cc=0;
		}
	}
	else
	{
		$bc=$bc+0;
		$rc=$rc+0;
		$cc=0;
	}

	// Package Item Calcs
	mat_filteritems_calc($phsid,$phsitem,$fdata,1);

	// Option Calcs
	$costitems=preg_replace("/,\Z/","",$costitems);
	if ($costitems > 0)
	{
		$edata=explode(",",$costitems);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			//echo $pre_iv."<br>";
			$pre_v=explode(":",$pre_iv);

			$qryB = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND invid='".$pre_v[1]."' AND baseitem!=1";
			//$qryB = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$pre_v[6]."' AND invid='".$pre_v[1]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			//echo "QRYB: ".$qryB."<BR>";

			if ($rowB['matid']!=0)
			{
				$qryBa		="SELECT bp FROM material_master WHERE id='".$rowB['matid']."';";
				$resBa		=mssql_query($qryBa);
				$rowBa	   =mssql_fetch_array($resBa);

				//echo $qryB."<BR>";

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

			$rid			=$pre_v[0];
			$quan		=$pre_v[2];
			$subbprice	=$pre_v[3];
			$subqtype	=$pre_v[4];
			$code		=$pre_v[5];
			$subphsid		=$pre_v[6];
			//$rinvid		=$pre_v[6];
			$subitem		=$rowB['item'];
			$subquan_c  =$rowB['quan_calc'];
			$subatrib1	=$rowB['atrib1'];
			$subatrib2	=$rowB['atrib2'];
			$subatrib3	=$rowB['atrib3'];
			$subquan		=$quan;

			//echo "<pre>";
			//print_r($pre_v);
			//echo "</pre>";

			if ($subphsid==$phsid)
			{
				//echo "QRYB: ".$qryB."<br>";
				//echo "SUBP: ".$subbprice."<br>";
				//echo "RINV: ".$rowB['rinvid']."<br>";
				$subrp =0; // Deprecated, remove on code cleanup
				$rc    =0; // Deprecated, remove on code cleanup

				if ($rowB['rinvid']!=0)  // Credit Code Loop
				//if ($rinvid!=0)  // Credit Code Loop
				{
					//echo "QRYB: ".$qryB."<br>";
					//echo "SUBP: ".$subbprice."<br>";
					//echo "RINV: ".$rowB['rinvid']."<br>";
					//echo $qryB."<BR>";
					//echo "<pre>";
					//print_r($pre_v);
					//echo "</pre>";
					//mat_credititem($rowB['rinvid'],$phsid,$quan);
					//$cr_out		=mat_credititem_job($rowB['rinvid'],$phsid,$quan);
					$cr_out		=mat_credititem_job($rowB['rinvid'],$pre_v);
					//$cr_out		=mat_credititem($rinvid,$phsid,$quan);
					$bp			=$cr_out[0];
					$bc			=$bc+$bp;
					//echo $bc."<br>";
				}

				if ($rowB['qtype']!=33)
				{
					/*if ($rowB['qtype']==56)
					{
						echo "IAREA: ".$iarea."<br>";
						echo "SUBBP: ".$subbprice."<br>";
						echo "SUBA3: ".$subatrib3."<br>";
						print_r($pre_v);
					}*/
;
					$calc_out		=uni_calc_loop($subqtype,$subbprice,0,0,0,$subquan,$subquan_c,$iarea,$gals,0,0,$code,$subatrib1,$subatrib2,$subatrib3,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					//$bc			=$bc+round($bp);
					$bc			=$bc+$bp;

					if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
					{
						showMitem($bp,0,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$quan_out,0,$rid);
					}
				}
				/*
				elseif ($rowB['qtype']==33) // Bid Item
				{
					$qryC = "SELECT raccid FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);

					$qryD = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$rowC['raccid']."';";
					$resD = mssql_query($qryD);
					$rowD = mssql_fetch_array($resD);

					$qryE = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
					$resE = mssql_query($qryE);
					$rowE = mssql_fetch_array($resE);

					$Xarray=explode(",",$rowE['estdata']);

					foreach ($Xarray as $n=>$v)
					{
						$subXarray=explode(":",$v);

						if ($subXarray[0]==$rowC['raccid'])
						{
							$Xbp=$subXarray[3];
						}
					}

					$subitem="Bid Item<br><font class=\"7pt\">- ".$rowD['bidinfo']."</font>";
					$subatrib1='';
					$subatrib2='';
					$subatrib3='';
					$subbp=$Xbp;
					$bc=$bc+$subbp;
				}
				*/
			}
		}
	}
	
	$bi=disp_cost_biditems($phsid,$viewarray['jadd']);
	$bc=$bc+$bi;
	
	$ma=disp_mpa_cost($phsid,$viewarray['jadd']);
	$bc=$bc+$ma;
	
	//$bc=round($bc);
	//$adjamt=0;
	displayMall($bc,$rc,$cc,$phsid,$phsitem,$adjamt);
	$phsbcrc=array(0=>$bc,1=>0,2=>$adjamt);
	return $phsbcrc;
}

function labor_filteritems_calc($phsid,$phsitem,$fdata)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;

	$viewarray	=$_SESSION['viewarray'];	
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];

	$vcnt=1;

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
	//echo "B FDATA: ".$fdata." ($phsid) $phsitem<br>";
	// *** Package Cost Calcs ***
	if (!empty($fdata) && strlen($fdata) > 3)
	//if (!empty($fdata))
	{
		//echo "A FDATA: ".$fdata." ($phsid) $phsitem<br>";
		$edata=explode(",",$fdata);
		foreach ($edata as $pre_en=>$pre_ev)
		{
			//echo "EV: ".$pre_ev."<br>";
			$idata=explode(":",$pre_ev);

			if ($vcnt==0)
			{
				echo "<pre>";
				print_r($idata);
				echo "</pre>";
			}

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

			$qryB = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND id='".$idata[5]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			if ($rowB['phsid']==$phsid)
			{
				if ($rowB['rinvid']!=0)  // Credit Code Loop
				{
					$cr_out	=lab_credititem($rowB['rinvid'],$rowB['id'],$phsid,$quan,0);
					$bp		=$cr_out[0];
					$bc		=$bc+$bp;
				}

				//$calc_out	=uni_calc_loop($rowB['qtype'],$rowB['bprice'],0,$rowB['lrange'],$rowB['hrange'],$quan,$rowB['quantity'],$iarea,$gals,0,0,$code,0,0,0,0,0);
				//$calc_out	=uni_calc_loop($rowB['qtype'],$subbp,0,$rowB['lrange'],$rowB['hrange'],$quan,$rowB['quantity'],$iarea,$gals,0,0,$code,0,0,0,0,0);
				$calc_out	=uni_calc_loop($qtype,$subbp,0,$rowB['lrange'],$rowB['hrange'],$quan,$rowB['quantity'],$iarea,$gals,0,0,$code,0,0,0,0,0);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				$bc			=$bc+$bp;

				if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
				{
					showitem($bp,0,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan_out,0,$idata[0]);
				}
			}
			//$vcnt++;
		}
	}
	//displayall($bc,0,$phsid,$phsitem);
	$phsbcrc=array(0=>$bc,0,0);
	return $phsbcrc;
}

function mat_filteritems_calc($phsid,$phsitem,$fdata)
{
	$MAS=$_SESSION['pb_code'];
	global $phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;

	$viewarray	=$_SESSION['viewarray'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$ps5        =$viewarray['ps5'];
	$ps6        =$viewarray['ps6'];
	$ps7        =$viewarray['ps7'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$deck       =$viewarray['deck'];

	$iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gals=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	// *** Package Cost Calcs ***
	//if (isset($fdata))
	if (strlen($fdata) > 1)
	{
		//echo $fdata."<br>";
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
			$subbp=$idata[8];

			$qryB = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND invid='".$idata[5]."' AND baseitem!=1";
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
					$cr_out	=mat_credititem($rowB['rinvid'],$phsid,$quan);
					$bp		=$cr_out[0];
					$bc		=$bc+$bp;
				}

				$calc_out	=uni_calc_loop($rowB['qtype'],$subbp,0,0,0,$quan,$rowB['quan_calc'],$iarea,$gals,0,0,$code,$rowB['atrib1'],$rowB['atrib2'],$subatrib3,0,0);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				$bc			=$bc+$bp;

				if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
				{
					showMitem($bp,0,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$subatrib3,$quan_out,0,$idata[0]);
				}
			}
		}
	}
	//displayall($bc,0,$phsid,$phsitem);
	$phsbcrc=array(0=>$bc,0,0);
	return $phsbcrc;
}

?>