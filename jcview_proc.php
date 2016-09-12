<?php

function getcodeitem($code)
{
	$officeid=$_GET['oid'];
	$qryA  = "SELECT * FROM material_master WHERE officeid='".$officeid."' AND code='".$code."';";
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

function showtaxitem()
{
	global $phsbcrc,$viewarray;

	$pb_code=$viewarray['pb_code'];
	$qry0 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='41';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$sbc		= $viewarray['tax'];
	$rate	=number_format($viewarray['taxrate'], 3, '.', '');
	$were	= '';
	//$were	= $viewarray['were'];
	$sbc		=round($sbc);
	$bc		=number_format($sbc, 2, '.', '');

	/*
	if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
	{
		echo "			<tr>\n";
		echo "				<td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">".$row0['phscode']."</td>\n";
		echo "				<td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">".$row0['extphsname']."</td>\n";
		echo "				<td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
		echo "					<table width=\"100%\" border=0>\n";
		echo "						<tr>\n";
		echo "							<td NOWRAP valign=\"top\" align=\"left\" width=\"225\" class=\"lg\">Sales Tax</td>";
		echo "              			<td NOWRAP valign=\"top\" align=\"left\" width=\"175\" class=\"lg\">".$were."</td>";
		echo "              		</tr>\n";
		echo "              	</table>\n";
		echo "				</td>\n";
		echo "				<td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">".$rate."</td>\n";

		if ($_SESSION['jlev'] >= 5)
		{
			echo "				<td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">".$bc."</td>\n";
		}

		echo "				<td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\"></td>\n";
		echo "			</tr>\n";
	}
	*/
	return $bc;
}

function addendum_labor_cost($costitems,$anum,$tanum)
{
	//$anum=0;
	global $viewarray,$tchrg,$taxrate;

	//print_r($viewarray);
	$officeid=$_GET['oid'];
	$pb_code	=$viewarray['pb_code'];
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
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$officeid."';";
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

			$qryB = "SELECT * FROM [".$pb_code."accpbook] WHERE officeid='".$officeid."' AND id='".$pre_v[1]."' AND baseitem!=1";
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


			if ($nrowB > 0)
			{
				$item	=$rowB['item'];
				$a1	=$rowB['atrib1'];
				$a2	=$rowB['atrib2'];
				$a3	=$rowB['atrib3'];
			}
			else
			{
				$item	='<b>Unlinked Entry</b>';
				$a1	='';
				$a2	='';
				$a3	='';
			}

			if ($rowB['qtype']!=33)
			{
				$calc_out	=uni_calc_loop($qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,$iarea,$gals,0,0,0,$a1,$a2,$a3);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				$bc			=$bc+$bp;

				
				/*
				if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
				{
					showadditem($bp,0,$iphsid,$item,$a1,$a2,$a3,$quan_out,0,$rid,$anum);
				}
				*/
			}
			elseif ($qtype==33) // Bid Item
			{
				$qryC = "SELECT rid FROM [".$pb_code."rclinks_l] WHERE officeid='".$officeid."' AND cid='".$pre_v[1]."';";
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_array($resC);
				$nrowC= mssql_num_rows($resC);

				//echo "LINKS: ".$qryC."<BR>";
				//echo "JOB TEST: ".$pre_v[0].":".$pre_v[3]."<br>";
				if ($nrowC > 0)
				{
					if ($_SESSION['action']=="contract")
					{
						$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$officeid."' AND jobid='".$viewarray['jobid']."' AND dbid='".$pre_v[0]."';";
					}
					elseif ($_SESSION['action']=="job")
					{
						$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND dbid='".$pre_v[0]."';";
					}
					$resD = mssql_query($qryD);
					$rowD = mssql_fetch_array($resD);

					if ($_SESSION['action']=="contract")
					{
						$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$officeid."' AND jobid='".$viewarray['jobid']."' AND rdbid='".$pre_v[0]."' AND jadd='".$viewarray['phsjadd']."';";
					}
					elseif ($_SESSION['action']=="job")
					{
						$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND rdbid='".$pre_v[0]."' AND jadd='".$viewarray['phsjadd']."';";
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

						/*
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
						*/
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
							$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$officeid."' AND jobid='".$viewarray['jobid']."' AND dbid='".$pre_v[0]."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND dbid='".$pre_v[0]."';";
						}
						$resD = mssql_query($qryD);
						$rowD = mssql_fetch_array($resD);
						//echo $qryD."<BR>";

						if ($_SESSION['action']=="contract")
						{
							$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$officeid."' AND jobid='".$viewarray['jobid']."' AND jadd='".$anum."';";
						}
						elseif ($_SESSION['action']=="job")
						{
							$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND jadd='".$anum."';";
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

						/*
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
						*/
					}
				}
			}
		}
	}

	$bc=round($bc);
	$dout=array(0=>$bc,0,0);
	return $dout;
}

function addendum_mat_cost($costitems,$anum,$tanum)
{
	global $viewarray;

	$officeid=$_GET['oid'];
	$pb_code	=$viewarray['pb_code'];
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
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d FROM offices WHERE officeid='".$officeid."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	// Option Calcs
	if ($costitems[0] > 0)
	{
		$edata=explode(",",$costitems);
		foreach ($edata as $pre_n=>$pre_iv)
		{
			$pre_v=explode(":",$pre_iv);

			$qryB = "SELECT * FROM [".$pb_code."inventory] WHERE officeid='".$officeid."' AND invid='".$pre_v[1]."' AND baseitem!=1";
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

			if ($rowB['qtype']!=33)
			{
				$calc_out	=uni_calc_loop($subqtype,$subbprice,0,0,0,$subquan,$subquan_c,$iarea,$gals,0,0,$code,$subatrib1,$subatrib2,$subatrib3);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				$bc			=$bc+$bp;

				/*
				if (empty($_POST['showtotals'])||$_POST['showtotals']==0)
				{
					showaddMitem($bp,0,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$quan_out,0,$rid,$anum);
				}
				*/
			}
		}
	}

	$dout=array(0=>$bc,0,0);
	return $dout;
}

function mat_credititem_job($rinvid,$pre_v)
{
	global $phsbcrc,$brexport,$invarray,$viewarray,$bc;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$pb_code	=$viewarray['pb_code'];
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
	$gals	=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	$qry = "SELECT * FROM [".$pb_code."inventory] WHERE officeid='".$officeid."' AND phsid='".$pre_v[6]."' AND invid='".$rinvid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1 = "SELECT rid FROM [".$pb_code."rclinks_m] WHERE officeid='".$officeid."' AND cid='".$rinvid."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	if ($row['matid']!=0)
	{
		$qrya = "SELECT bp FROM material_master WHERE id='".$row['matid']."';";
		$resa = mssql_query($qrya);
		$rowa = mssql_fetch_array($resa);

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
	$subquan    =$pre_v[2];
	$cr         =1;
	$code       =0;

	$calc_out		=uni_calc_loop($row['qtype'],$subbp,0,0,0,$subquan,$row['quan_calc'],$iarea,$gals,0,0,$code,0,0,0);
	$bp			=$calc_out[0];
	$quan_out	=$calc_out[2];

	$phsbcrc=array(0=>$bp,0,0);
	return $phsbcrc;
}

function lab_credititem($id,$oid,$phsid,$quan,$rid)
{
	global $phsbcrc,$brexport,$viewarray,$bc;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$pb_code		=$viewarray['pb_code'];
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

	$qry 			= "SELECT * FROM [".$pb_code."accpbook] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND id='".$id."';";
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
	$code		=0;

	$calc_out		=uni_calc_loop($row['qtype'],$subbp,0,$lr,$hr,$quan,$row['quantity'],$iarea,$gals,0,0,$code,0,0,0);
	$bp			=$calc_out[0]*-1;
	$quan_out	=$calc_out[2];

	$phsbcrc=array(0=>$bp,0,0);
	return $phsbcrc;
}

function calc_royalty_job($costitems)
{
	global $viewarray;

	$officeid=$_GET['oid'];
	$pb_code		=$viewarray['pb_code'];
	$subctr	=0;
	$subroyt	=0;
	$subroyn	=0;
	$phsid	=8;

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

			$qryB = "SELECT * FROM [".$pb_code."accpbook] WHERE officeid='".$officeid."' AND phsid='".$iphsid."' AND id='".$pre_v[1]."' AND baseitem!=1";
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
					$qryC = "SELECT rid FROM [".$pb_code."rclinks_l] WHERE officeid='".$officeid."' AND cid='".$pre_v[1]."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);
					$nrowC= mssql_num_rows($resC);

					if ($nrowC > 0)
					{
						$qryCa = "SELECT * FROM jbids_breakout WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND rdbid='".$pre_v[0]."';";
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
							$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND dbid='".$pre_v[0]."';";
							$resD = mssql_query($qryD);
							$rowD = mssql_fetch_array($resD);

							$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND jadd='".$viewarray['jadd']."';";
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
						}

						if ($rowB['royrelease']==1)
						{
							$viewarray['royrel']=$subbp;
						}
					}
				}
			}
		}
	}
	$royalty		=($viewarray['camt']-$viewarray['royrel'])*.03;
	$royalty=round($royalty);
	return $royalty;
}

function labor_baseitems_job_calc($phsid,$bdata,$jtag)
{
	global $viewarray,$phsbcrc,$brexport,$invarray,$tchrg,$taxrate,$bc;

	$bc=0;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$pb_code		=$viewarray['pb_code'];
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
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$officeid."';";
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
			$cid		=$pre_v[0];
			$accid	=$pre_v[1];
			$iphsid	=$pre_v[2];
			$matid	=$pre_v[3];
			$qtype	=$pre_v[4];
			$cost	=$pre_v[6];
			$item	=$pre_v[7];
			$code	=$pre_v[8];
			$lrange	=$pre_v[9];
			$hrange	=$pre_v[10];
			$quan	=$pre_v[11];
			//$rinvid  =$pre_v[9];
			$quancalc=$pre_v[13];
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
						$qry1a ="SELECT custid FROM jobs WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."';";
						$res1a =mssql_query($qry1a);
						$row1a =mssql_fetch_array($res1a);

						$qry1b ="SELECT scounty FROM cinfo WHERE officeid='".$officeid."' AND custid='".$row1a[0]."';";
						$res1b =mssql_query($qry1b);
						$row1b =mssql_fetch_array($res1b);

						$qry1 ="SELECT permit,city FROM taxrate WHERE officeid='".$officeid."' AND id='".$row1b[0]."';";
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
					$bc=$bc+$bp;
					$rc=0;
				}
				elseif ($qtype!=33) // All other qtypes
				{
					//echo "ITEM: ".$item."<br>";
					$bp			=$cost;
					$quan_out	=$quan;
					$bc=$bc+$bp;
					$rc=0;
				}
			}
		}
	}
}

function labor_filteritems_calc($phsid,$phsitem,$fdata)
{
	global $phsbcrc,$brexport,$invarray,$viewarray,$tchrg,$taxrate,$bc;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$pb_code		=$viewarray['pb_code'];
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

			$qryB = "SELECT * FROM [".$pb_code."accpbook] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND id='".$idata[5]."' AND baseitem!=1";
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

				$calc_out		=uni_calc_loop($qtype,$subbp,0,$rowB['lrange'],$rowB['hrange'],$quan,$rowB['quantity'],$iarea,$gals,0,0,$code,0,0,0);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				$bc			=$bc+$bp;
			}
		}
	}
	$phsbcrc=array(0=>$bc,0,0);
	return $phsbcrc;
}

function uni_calc_loop($qtype,$bp,$rp,$lr,$hr,$quan,$def_quan,$iarea,$gals,$spa_ia,$spa_gl,$code,$a1,$a2,$a3)
{
	global $viewarray;
	error_reporting(E_ALL);

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
		$quan_out=$viewarray['ps1'];
		$subbp=$bp*$quan_out;
		$subrp=$rp*$quan_out;
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
	elseif ($qtype==6) // Base+ (SQFT)
	{
		$quan_out=$viewarray['ps2'];
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
		// $subbp." ".$tt." ($hr)<br>";
	}
	elseif ($qtype==8) // Base+ (Fixed)
	{
		$quan_out=$quan;
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
			$qrypst0 ="SELECT stax FROM offices WHERE officeid='".$officeid."';";
			$respst0 =mssql_query($qrypst0);
			$rowpst0 =mssql_fetch_array($respst0);

			if ($rowpst0['stax']==1)
			{


				if ($_SESSION['action']=="contract")
				{
					$qry1a ="SELECT custid FROM jobs WHERE officeid='".$officeid."' AND jobid='".$viewarray['jobid']."';";
				}
				elseif ($_SESSION['action']=="job")
				{
					$qry1a ="SELECT custid FROM jobs WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."';";
				}
				elseif ($_SESSION['action']=="est")
				{
					$qry1a ="SELECT cid FROM est WHERE officeid='".$officeid."' AND estid='".$_SESSION['estid']."';";
				}
				else
				{
					$qry1a ="SELECT permit,city FROM taxrate WHERE officeid='".$officeid."';";
				}

				$res1a =mssql_query($qry1a);
				$row1a =mssql_fetch_array($res1a);

				$qry1b ="SELECT scounty FROM cinfo WHERE officeid='".$officeid."' AND custid='".$row1a[0]."';";
				$res1b =mssql_query($qry1b);
				$row1b =mssql_fetch_array($res1b);

				$qry1 ="SELECT permit,city FROM taxrate WHERE officeid='".$officeid."' AND id='".$row1b[0]."';";
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

	else // Catch Bucket
	{
		$quan_out	=0;
		$subbp		=0;
		$subrp		=0;
	}

	$ar_out=array(0=>round($subbp),1=>$subrp,2=>$quan_out);
	return $ar_out;
}

function get_adj_amt($phsid)
{
	global $viewarray;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$dout=0;

	$qry0	= "SELECT manphscostadj FROM jobs WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."';";
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
			}
		}
	}
	return $dout;
}

function mat_credititem($id,$phsid,$quan)
{
	global $phsbcrc,$brexport,$invarray,$viewarray,$bc;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$pb_code		=$viewarray['pb_code'];
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

	$qry = "SELECT * FROM [".$pb_code."inventory] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND invid='".$id."';";
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
	$calc_out	=uni_calc_loop($row['qtype'],$subbp,0,$lr,$hr,$quan,$row['quan_calc'],$iarea,$gals,0,0,$code,0,0,0);
	$bp			=$calc_out[0];
	$quan_out	=$calc_out[2];

	$phsbcrc=array(0=>$bp,0,0);
	return $phsbcrc;
}

function mat_filteritems_calc($phsid,$phsitem,$fdata)
{
	global $phsbcrc,$brexport,$invarray,$viewarray,$tchrg,$taxrate,$bc;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$pb_code		=$viewarray['pb_code'];
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

			$qryB = "SELECT * FROM [".$pb_code."inventory] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND invid='".$idata[5]."' AND baseitem!=1";
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

				$calc_out	=uni_calc_loop($rowB['qtype'],$subbp,0,0,0,$quan,$rowB['quan_calc'],$iarea,$gals,0,0,$code,$rowB['atrib1'],$rowB['atrib2'],$subatrib3);
				$bp			=$calc_out[0];
				$quan_out	=$calc_out[2];
				$bc			=$bc+$bp;
			}
		}
	}
	//displayall($bc,0,$phsid,$phsitem);
	$phsbcrc=array(0=>$bc,0,0);
	return $phsbcrc;
}

function jobphscalc($phsid,$phsnum,$phsitem,$costitems,$bdata,$fdata,$adjamt)
{
	global $phsbcrc,$brexport,$invarray,$viewarray,$tchrg,$taxrate,$bc;

	error_reporting(E_ALL);
	$officeid		=$_GET['oid'];
	$pb_code		=$viewarray['pb_code'];
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
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$officeid."';";
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
			$pre_v=explode(":",$pre_iv);

			$rid		=$pre_v[0];
			$cid		=$pre_v[1];
			$quan	=$pre_v[2];
			$cost	=$pre_v[3];
			$qtype	=$pre_v[4];
			$code	=$pre_v[5];
			$lrange	=$pre_v[6];
			$hrange	=$pre_v[7];
			$iphsid	=$pre_v[8];
			$rinvid	=$pre_v[9];
			$quancalc=$pre_v[10];

			$qryB = "SELECT qtype,item,atrib1,atrib2,atrib3 FROM [".$pb_code."accpbook] WHERE officeid='".$officeid."' AND phsid='".$iphsid."' AND id='".$pre_v[1]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
			$nrowB= mssql_num_rows($resB);
			
			//echo $qryB."<br>";

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
				if ($rinvid!=0)  // Credit Code Loop
				{
					$cr_out		=lab_credititem($rinvid,$cid,$iphsid,$quan,0);
					$bp			=$cr_out[0];
					$bc			=$bc+$bp;
				}

				if ($rowB['qtype']!=33)
				{
					$calc_out	=uni_calc_loop($qtype,$cost,0,$lrange,$hrange,$quan,$quancalc,$iarea,$gals,0,0,0,$a1,$a2,$a3);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$bc			=$bc+$bp;
				}
				elseif ($qtype==33) // Bid Item
				{
					//echo $qryB."<br>";
					$qryC = "SELECT rid FROM [".$pb_code."rclinks_l] WHERE officeid='".$officeid."' AND cid='".$pre_v[1]."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);
					$nrowC= mssql_num_rows($resC);

					//echo "LINKS: ".$qryC."<BR>";
					//echo "JOB TEST: ".$pre_v[0].":".$pre_v[3]."<br>";
					if ($nrowC > 0)
					{
						//echo "CNT: ".$nrowC."<br>";
						$qryCa = "SELECT bprice FROM jbids_breakout WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND rdbid='".$pre_v[0]."';";
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
							$bc=$bc+$subbp;
						}
						else
						{

							$qryD = "SELECT bidinfo FROM jbids WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND dbid='".$pre_v[0]."';";
							$resD = mssql_query($qryD);
							$rowD = mssql_fetch_array($resD);

							$qryE = "SELECT estdata FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND jadd='".$viewarray['jadd']."';";
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
						}
					}
				}
			}
		}
	}

	if ($phsid==8)
	{
		$viewarray['custallow']=$viewarray['custallow']+$bc;
	}

	if ($phsid==41 && $rowpre0['stax']==1)
	{
		$subbp=showtaxitem();
		$bc=$bc+$subbp;
	}

	//print_r($viewarray);
	//echo "<br>";
	
	$bc=round($bc);
	//displayall($bc,0,$phsid,$phsitem,$adjamt);
	$phsbcrc=array(0=>$bc,1=>0,2=>$adjamt);
	return $phsbcrc;
}

function jobphsMcalc($phsid,$phsnum,$phsitem,$costitems,$fdata,$adjamt)
{
	global $phsbcrc,$brexport,$invarray,$viewarray,$bc;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$pb_code		=$viewarray['pb_code'];
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
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d FROM offices WHERE officeid='".$officeid."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	$qry   ="SELECT bprice,rprice,invid,item,commtype,crate,atrib1,atrib2,atrib3,phsid,rinvid,quan_calc,seqnum FROM [".$pb_code."inventory] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND baseitem=1 ORDER by seqnum;";
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
			$pre_v=explode(":",$pre_iv);

			$qryB = "SELECT * FROM [".$pb_code."inventory] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND invid='".$pre_v[1]."' AND baseitem!=1";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			if ($rowB['matid']!=0)
			{
				$qryBa		="SELECT bp FROM material_master WHERE id='".$rowB['matid']."';";
				$resBa		=mssql_query($qryBa);
				$rowBa	   =mssql_fetch_array($resBa);

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
				$subrp =0; // Deprecated, remove on code cleanup
				$rc    =0; // Deprecated, remove on code cleanup

				if ($rowB['rinvid']!=0)  // Credit Code Loop
				{
					$cr_out		=mat_credititem_job($rowB['rinvid'],$pre_v);
					$bp			=$cr_out[0];
					$bc			=$bc+$bp;
					//echo $bc."<br>";
				}

				if ($rowB['qtype']!=33)
				{
					$calc_out		=uni_calc_loop($subqtype,$subbprice,0,0,0,$subquan,$subquan_c,$iarea,$gals,0,0,$code,$subatrib1,$subatrib2,$subatrib3);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$bc			=$bc+$bp;
				}
				elseif ($rowB['qtype']==33) // Bid Item
				{
					$qryC = "SELECT raccid FROM [".$pb_code."accpbook] WHERE officeid='".$officeid."' AND id='".$rid."';";
					$resC = mssql_query($qryC);
					$rowC = mssql_fetch_array($resC);

					$qryD = "SELECT bidinfo FROM est_bids WHERE officeid='".$officeid."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$rowC['raccid']."';";
					$resD = mssql_query($qryD);
					$rowD = mssql_fetch_array($resD);

					$qryE = "SELECT estdata FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$_SESSION['estid']."';";
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
			}
		}
	}

	//print_r($viewarray);
	//echo "<br>";
	$phsbcrc=array(0=>$bc,1=>0,2=>$adjamt);
	return $phsbcrc;
}

function calcbyphsL($data,$bdata,$fdata,$job)
{
	global $bctotal,$bcadjtotal,$rctotal,$phsbcrc,$phsid,$phsnum,$phsitem,$viewarray;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$pb_code	=$viewarray['pb_code'];
	$discount	=$viewarray['discount'];
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
	//$showdetail	=0;
	//$showdetail	=0;

	if ($job!=1)
	{
		$costitems	=setcostitemlist($data,"L");
	}
	else
	{
		$costitems	=$data;
	}

	$qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT stax FROM offices WHERE officeid='".$officeid."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryC = "SELECT id,quan,price FROM rbpricep WHERE officeid='".$officeid."' AND quan='".$ps1."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$pbaseprice=$rowC[2]-$discount;
	$pbaseprice=number_format($pbaseprice, 2, '.', '');

	while($rowA = mssql_fetch_row($resA))
	{
		if ($rowA[1]=="503L")
		{
			$comm		=round($tcomm);
			$fcomm	=number_format($comm, 2, '.', '');
			$bctotal=$bctotal+$comm;
			$rctotal=$rctotal;
		}
		elseif ($rowA[1]=="505L")
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
	}

	if (!empty($viewarray['njobid']))
	{
		// Addendum Display
		$qryW = "SELECT MAX(jadd) AS mjadd FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."';";
		$qryV = "SELECT * FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND jadd!=0;";
		$qryT = "SELECT taxrate FROM jobs WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."';";
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
				//$shw_pmas_add=show_postmas_add($_GET['njobid'],$rowV['jadd'],$rowV['post_add'],$padd_type);
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
				}

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

				$subxaddpr	=$subxaddpr+$xaddpr;
				$xaddpr		=0;
				$faddpr		=0;
			}
			$bctotal		=$bctotal+$subxaddpr;
		}
	}
}

function calcbyphsM($data,$fdata,$job)
{
	global $officeid,$bmtotal,$bmadjtotal,$rmtotal,$cmtotal,$phsbcrc,$phsid,$phsnum,$phsitem,$viewarray;

	error_reporting(E_ALL);
	$officeid=$_GET['oid'];
	$pb_code		=$viewarray['pb_code'];
	$discount   =$viewarray['discount'];
	$ps1        =$viewarray['ps1'];
	$ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
	$spa1       =$viewarray['spa1'];
	$spa2       =$viewarray['spa2'];
	$spa3       =$viewarray['spa3'];
	$showdetail	=0;

	if ($job!=1)
	{
		$costitems	=setcostitemlist($data,"M");
	}
	else
	{
		$costitems	=$data;
	}

	$qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phstype='M' ORDER BY seqnum ASC;";
	$resA = mssql_query($qryA);

	$qryC = "SELECT id,quan,price FROM rbpricep WHERE officeid='".$officeid."' AND quan='".$ps1."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

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
			$bmadjtotal	=$bmadjtotal+$phsbcrc[2];
		}
	}
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

function view_job_cost($officeid,$njobid)
{
	error_reporting(E_ALL);
	global $bctotal,$bcadjtotal,$rctotal,$cctotal,$bmtotal,$bmadjtotal,$rmtotal,$cmtotal,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate,$tbid,$tbullets;
	//global $viewarray,$bctotal,$bcadjtotal,$bmtotal,$bmadjtotal;
	$officeid		=$_GET['oid'];
	$manphsadj	=0;

	if (!isset($njobid)||$njobid=='')
	{
		echo "Fatal Error: Job ID not set!";
		exit;
	}

	$qrypreAb = "SELECT MAX(jadd) as maxjadd FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$njobid."';";
	$respreAb = mssql_query($qrypreAb);
	$rowpreAb = mssql_fetch_array($respreAb);

	$jadd		=$rowpreAb['maxjadd'];

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$njobid."' AND jadd='".$jadd."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreAa = "SELECT contractdate,added FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$njobid."' AND jadd='0';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreB = "SELECT * FROM jobs WHERE officeid='".$officeid."' AND njobid='".$njobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$tcomm	=$rowpreB['comm']+$rowpreB['ovcommission'];

	$qrypreC = "SELECT costdata_l,costdata_m FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$njobid."' AND jadd='".$jadd."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_row($respreC);

	$qrypreD = "SELECT officeid,pft_sqft,pb_code FROM offices WHERE officeid='".$officeid."';";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_array($respreD);

	if ($rowpreD['pb_code']==0)
	{
		$pb_code='';
	}
	else
	{
		$pb_code=$rowpreD['pb_code'];
	}

	$jsecurityid =$rowpreB['securityid'];

	$viewarray=array(
	'ps1'=>		$rowpreA['pft'],
	'ps2'=>		$rowpreA['sqft'],
	'spa1'=>		$rowpreA['spa_pft'],
	'spa2'=>		$rowpreA['spa_sqft'],
	'spa3'=>		$rowpreA['spa_type'],
	'tzone'=>		$rowpreA['tzone'],
	'camt'=>		$rowpreA['contractamt'],
	'cdate'=>		$rowpreAa['contractdate'],
	'status'=>		$rowpreB['status'],
	'ps5'=>		$rowpreA['shal'],
	'ps6'=>		$rowpreA['mid'],
	'ps7'=>		$rowpreA['deep'],
	'custid'=>		$rowpreB['custid'],
	'custallow'=>	0,
	'estsecid'=>	$rowpreB['sid'],
	'deck'=>		$rowpreA['deck'],
	'erun'=>		$rowpreA['erun'],
	'prun'=>		$rowpreA['prun'],
	'njobid'=>		$rowpreB['njobid'],
	'comadj'=>	$rowpreA['ouadj'],
	'sidm'=>		$rowpreB['sidm'],
	'tax'=>		$rowpreB['tax'],
	'taxrate'=>		$rowpreB['taxrate'],
	'applyou'=>	1,
	'refto'=>		$rowpreA['refto'],
	'ps1a'=>		$rowpreA['apft'],
	'bpprice'=>	$rowpreA['bpprice'],
	'bpcomm'=>	$rowpreA['bpcomm'],
	'addnpr'=>	$rowpreA['raddnpr_man'],
	'addncm'=>	$rowpreA['raddncm_man'],
	'royadj'=>		$rowpreA['raddnroy_man'],
	'jadd'=>		$rowpreA['jadd'],
	'maxjadd'=>	$rowpreAb['maxjadd'],
	'tcomm'=>		$tcomm,
	'pb_code'=>	$pb_code,
	'securityid'=>	$rowpreB['securityid'],
	'phsjadd'=>	0,
	'royrel'=>		0,
	'mas_prep'=>	0,
	'discount'=>	0
	);

	if ($rowpreD['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$c_jobdata_l = $rowpreA['costdata_l'];
	$c_jobdata_m = $rowpreA['costdata_m'];
	$b_jobdata_l = $rowpreA['bcostdata_l'];
	$b_jobdata_m = $rowpreA['bcostdata_m'];
	$p_jobdata_l = $rowpreA['pcostdata_l'];
	$p_jobdata_m = $rowpreA['pcostdata_m'];

	/*
	if (isset($acctotal)||$acctotal!=0)
	{
	$acctotal=$acctotal;
	}
	else
	{
	$acctotal=0;
	}
	*/

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryC = "SELECT officeid,name,stax,sm,gm FROM offices WHERE officeid='".$officeid."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_array($resC);

	$qryD = "SELECT securityid,fname,lname,mas_div FROM security WHERE securityid='".$jsecurityid."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,mas_prep,cid FROM cinfo WHERE officeid='".$officeid."' AND custid='".$viewarray['custid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$viewarray['mas_prep']	=$rowI['mas_prep'];
	$viewarray['cid']		=$rowI['cid'];

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_array($resL);

	if ($rowC[2]==1)
	{
		if (!empty($viewarray['taxrate']) && $viewarray['taxrate']!="0.00")
		{
			$taxrate			=array(0=>$viewarray['tax'],1=>$viewarray['taxrate']);
		}
		else
		{
			$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI['scounty']."';";
			$resJ = mssql_query($qryJ);
			$rowJ = mssql_fetch_row($resJ);

			$viewarray['taxrate']	=$rowJ[0];
			$viewarray['tax']	=$viewarray['camt']*$viewarray['taxrate'];
			$taxrate			=array(0=>$viewarray['tax'],1=>$viewarray['taxrate']);
		}

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$officeid."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}

	$sdate		=date("m/d/Y", strtotime($rowpreAa['added']));
	$cdate 		=date("m/d/Y", strtotime($viewarray['cdate']));

	$set_ia		=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals		=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$estidret   	=$viewarray['njobid'];
	$vdiscnt    	=$viewarray['discount'];
	$pbaseprice 	=$rowB['price'];
	$bcomm      	=$rowB['comm'];

	calcbyphsL($c_jobdata_l,$b_jobdata_l,$p_jobdata_l,1);

	$bcestcost	=$bctotal;
	$bcadjcost	=$bcadjtotal;
	$tbccost		=$bcestcost+$bcadjcost;

	calcbyphsM($c_jobdata_m,$p_jobdata_m,1);

	$bmestcost	=$bmtotal;
	$bmadjcost	=$bmadjtotal;
	$tbmcost		=$bmestcost+$bmadjcost;

	if ($rowpreA['psched']!=0)
	{
		$taretail=0;

		$phsar=explode(",",$rowpreA['psched']);
		$perar=explode(",",$rowpreA['psched_perc']);

		if (count($phsar)==count($perar))
		{
			foreach ($phsar as $an => $pc)
			{
				$qryZ = "SELECT phscode,extphsname FROM phasebase WHERE phscode='".$pc."';";
				$resZ = mssql_query($qryZ);
				$rowZ = mssql_fetch_array($resZ);

				$paymnt	=$perar[$an];
				//$fpaymnt	=number_format($paymnt, 2, '.', '');
				$taretail	=$taretail+$paymnt;
			}
		}
		else
		{
			$taretail=$viewarray['camt'];
		}

		$ocontract  =$taretail;
		$focontract =number_format($ocontract, 2, '.', '');

		$qryX = "SELECT jadd,psched_adj,add_type,post_add FROM jdetail WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."' AND jadd!=0;";
		$resX = mssql_query($qryX);
		$nrowX= mssql_num_rows($resX);

		if ($nrowX > 0)
		{
			while ($rowX = mssql_fetch_array($resX))
			{
				if ($rowX['jadd'] >=1)
				{
					//$fpsched_adj	=number_format($rowX['psched_adj'], 2, '.', '');
					$taretail		=$taretail+$rowX['psched_adj'];
				}
			}
		}

		$ftaretail		=number_format($taretail, 2, '.', '');
	}
	else
	{
		$taretail		=$viewarray['camt'];
	}

	$custallow		=$viewarray['custallow'];
	$tcustallow	=$custallow*-1;
	$tcontract  	=$taretail;
	//$ftcontract 	=number_format($tcontract, 2, '.', '');
	//$tretail  		=$tretail;
	//$ftretail 		=number_format(round($tretail), 2, '.', '');

	if ($manphsadj==1)
	{
		$tbcost  =$tbccost+$tbmcost;
	}
	else
	{
		$tbcost  =$bcestcost+$bmestcost;
	}

	if ($tcustallow != 0)
	{
		$tadjcontract	=$tcontract+$tcustallow;
	}
	else
	{
		$tadjcontract	=$tcontract;
	}

	if ($tcustallow != 0)
	{
		$tadjbcost		=round($tbcost+$tcustallow);
	}
	else
	{
		$tadjbcost		=round($tbcost);
	}

	if ($tcustallow != 0)
	{
		$tprofit		=$tadjcontract-$tadjbcost;
	}
	else
	{
		$tprofit		=$tcontract-$tbcost;
	}

	if ($tcontract!=0)
	{
		if ($tcustallow != 0)
		{
			$netper  =$tprofit/$tadjcontract;
		}
		else
		{
			$netper  =$tprofit/$tcontract;
		}
	}
	else
	{
		$netper  =0;
	}

	if ($tcustallow != 0)
	{
		$qryY = "UPDATE jobs SET tgp='".$netper."', jcost='".$tadjbcost."', jprof='".$tprofit."' WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."';";
	}
	else
	{
		$qryY = "UPDATE jobs SET tgp='".$netper."', jcost='".$tbcost."', jprof='".$tprofit."' WHERE officeid='".$officeid."' AND njobid='".$viewarray['njobid']."';";
	}
	$resY = mssql_query($qryY);
	echo $qryY." <br>";
}

function proc_jobs()
{
	error_reporting(E_ALL);
	include (".\connect_db.php");
	include (".\common_func.php");
	$t		=0;
	$tt		=0;
	$qry0	= "select officeid,securityid,njobid,convert(money,comm) + convert(money,ovcommission) as tcomm from jobs where officeid=".$_GET['oid']." and njobid!='0' and digdate >= '8/1/06' and jcost='0.00';";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);

	//echo $qry0."<br>";

	echo $nrow0." Jobs Found<br>";
	
	if ($nrow0 > 0)
	{
		$qry0a	= "select pb_code from offices where officeid=".$_GET['oid'].";";
		$res0a	= mssql_query($qry0a);
		$row0a	= mssql_fetch_array($res0a);

		if ($row0a['pb_code']!=0)
		{
			$pb_code=$row0a['pb_code'];
		}
		else
		{
			$pb_code='';
		}

		echo "------------------<br>";
		while ($row0 = mssql_fetch_array($res0))
		{
			$t++;
			//if ($tt <= 32)
			//{
				
				view_job_cost($row0['officeid'],$row0['njobid']);
				echo "Complete: (".$t.") (".$row0['officeid'].") (".$row0['njobid'].") (".date("m/d/Y H:i",time()).")<br>";
				unset($GLOBALS['bctotal']);
				unset($GLOBALS['bctotal']);
				unset($GLOBALS['bcadjtotal']);
				unset($GLOBALS['rctotal']);
				unset($GLOBALS['cctotal']);
				unset($GLOBALS['bmtotal']);
				unset($GLOBALS['bmadjtotal']);
				unset($GLOBALS['rmtotal']);
				unset($GLOBALS['cmtotal']);
				unset($GLOBALS['callow']);
				unset($GLOBALS['discount']);
				unset($GLOBALS['viewarray']);
				unset($GLOBALS['invarray']);
				unset($GLOBALS['estidret']);
				unset($GLOBALS['taxrate']);
				unset($GLOBALS['tbid']);
				unset($GLOBALS['tbullets']);
				//echo "<br>";
				
			//}
		}
		echo "------------------<br>";
		echo $t." Jobs Processed<br>";
	}
}

proc_jobs();

?>