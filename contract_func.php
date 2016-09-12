<?php

function basematrix()
{
	include ('contract_support_func.php');
	
	//if ($_SESSION['securityid']==26)
	//{
	//	//echo __FILE__.'<br>';
	//}
	
	if (!isset($_SESSION['call'])||$_SESSION['call']=="None")
	{
		//echo "<font color=\"red\">Error!</font>\$call not set.";
	}
	elseif ($_SESSION['call']=="create_job")
	{
		create_job();
	}
	elseif ($_SESSION['call']=="create_job_chk")
	{
		create_job_chk();
	}
	elseif ($_SESSION['call']=="post_create_job")
	{
		post_create_job();
	}
	elseif ($_SESSION['call']=="view_retail")
	{
		view_job_retail();
	}
	elseif ($_SESSION['call']=="view_jadd_retail")
	{
		view_job_retail();
	}
	elseif ($_SESSION['call']=="view_retail_var")
	{
		view_job_retail_var();
	}
	elseif ($_SESSION['call']=="view_cost")
	{
		view_job_cost();
	}
	elseif ($_SESSION['call']=="view_cost_int")
	{
		view_job_cost_int();
	}
	elseif ($_SESSION['call']=="view_jadd_cost")
	{
		view_job_cost();
	}
	elseif ($_SESSION['call']=="view_cost_var")
	{
		view_job_cost_var();
	}
	elseif ($_SESSION['call']=="list")
	{
		list_jobs();
	}
	elseif ($_SESSION['call']=="create_add")
	{
		create_addendum();
	}
	elseif ($_SESSION['call']=="build_add")
	{
		build_addendum();
	}
	elseif ($_SESSION['call']=="save_add")
	{
		build_addendum_save();
	}
	elseif ($_SESSION['call']=="post_save_add")
	{
		insert_add();
	}
	elseif ($_SESSION['call']=="view_job_addendum_retail")
	{
		view_job_addendum_retail();
	}
	elseif ($_SESSION['call']=="view_job_addendum_cost")
	{
		view_job_addendum_cost();
	}
	elseif ($_SESSION['call']=="delete_job1")
	{
		delete_job($_REQUEST['jobid'],$_REQUEST['jadd']);
	}
	elseif ($_SESSION['call']=="delete_job2")
	{
		delete_job($_REQUEST['jobid'],$_REQUEST['jadd']);
	}
	elseif ($_SESSION['call']=="view_bid_jobmode")
	{
		//echo "Contract VBJM<br>";
		view_bid_job_mode();
	}
	elseif ($_SESSION['call']=="edit_bid_jobmode")
	{
		//echo "Contract VBJM<br>";
		edit_bid_job_mode();
	}
	elseif ($_SESSION['call']=="edit_bid_jobmode_add")
	{
		//echo "Contract VBJM<br>";
		edit_bid_jobmode_add();
	}
	elseif ($_SESSION['call']=="edit_bid_jobmode_delete")
	{
		//echo "Contract VBJM<br>";
		edit_bid_jobmode_delete();
	}
	elseif ($_SESSION['call']=="edit_mpa_jobmode_add")
	{
		edit_mpa_jobmode_add();
	}
	elseif ($_SESSION['call']=="edit_mpa_jobmode_delete")
	{
		//echo "Contract VBJM<br>";
		edit_mpa_jobmode_delete();
	}
	elseif ($_SESSION['call']=="edit_add_price")
	{
		edit_add_price();
	}
	elseif ($_SESSION['call']=="applyov")
	{
		apply_overage();
	}
	elseif ($_SESSION['call']=="deleteov")
	{
		delete_overage();
	}
	elseif ($_SESSION['call']=="applybu")
	{
		apply_bullet($_SESSION['estid']);
	}
	elseif ($_SESSION['call']=="deletebu")
	{
		delete_bullet($_SESSION['estid']);
	}
	elseif ($_SESSION['call']=="inscostadj")
	{
		add_phs_cost_adj();
	}
	elseif ($_SESSION['call']=="edit_bid")
	{
		edit_bid();
	}
	elseif ($_SESSION['call']=="edit_bid_add")
	{
		edit_bid_add();
	}
	elseif ($_SESSION['call']=="edit_bid_update")
	{
		edit_bid_update();
	}
	elseif ($_SESSION['call']=="edit_bid_delete")
	{
		edit_bid_delete();
	}
	elseif ($_SESSION['call']=="search")
	{
		contr_search();
	}
	elseif ($_SESSION['call']=="search_results")
	{
		list_jobs();
	}
	elseif ($_SESSION['call']=="chistory_add")
	{
		chistory_add();
	}
	elseif ($_SESSION['call']=="chistory")
	{
		//echo "HISTORY";
		chistory_list();
	}
	elseif ($_SESSION['call']=="set_digdate")
	{
		set_digdate();
	}
	elseif ($_SESSION['call']=="set_clsdate")
	{
		set_clsdate();
	}
	elseif ($_SESSION['call']=="set_condate")
	{
		set_condate();
	}
	elseif ($_SESSION['call']=="biddel")
	{
		edit_bid_jobmode_delete();
	}
	elseif ($_SESSION['call']=="mpadel")
	{
		edit_mpa_jobmode_delete();
	}
	elseif ($_SESSION['call']=="updateMA")
	{
		updateMA();
	}
}

function add_phs_cost_adj()
{
	//global $viewarray;

	//echo "Accepted: ";
	$adjout='';
	foreach ($_POST as $n=>$v)
	{
		//echo $n."<br>";
		if (substr($n,0,4)=="adjX")
		{
			$asid=substr($n,4);
			//echo $asid."<br>";
			$adjd=$asid.':'.$_REQUEST['adjX'.$asid].',';
			$adjout=$adjout.$adjd;
		}
	}

	$t_adjout=preg_replace("/,\Z/","",$adjout);

	$qry0 = "UPDATE jobs SET manphscostadj='".$t_adjout."' WHERE officeid='".$_SESSION['officeid']."' and jobid='".$_REQUEST['jobid']."';";
	$res0 = mssql_query($qry0);

	//echo $qry0;
}

function align_pricing($estdata)
{
	$MAS=$_SESSION['pb_code'];
	$dout='';
	$est_in=explode(",",$estdata);
	foreach ($est_in as $in_n => $in_v)
	{
		$p=explode(":",$in_v);
		$qry0 	="SELECT id,rp,qtype,crate,commtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$p[0]."';";
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

function store_labor_baseitems($jobid,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	//global $phsbcrc,$brexport,$invarray,$viewarray,$tchrg,$taxrate,$bc;
	global $viewarray;
	$p_out='';
	$data_out=array();

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
	$qrypre0 	="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 	=mssql_query($qrypre0);
	$rowpre0 	=mssql_fetch_array($respre0);

	//Pulls Total List of Base Labor Items within a phase based upon DISTINCT accid's
	$qry0    ="SELECT DISTINCT(accid),qtype,seqnum,phsid FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND baseitem=1 ORDER BY seqnum;";
	$res0    =mssql_query($qry0);
	$nrow0   =mssql_num_rows($res0);

	$ecnt		=0;
	if ($nrow0 > 0)
	{
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
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1 ORDER by lrange;";
				$res1  =mssql_query($qry1);

				while ($row1=mssql_fetch_array($res1))
				{
					if ($ps1 >= $row1['lrange'] && $ps1 <= $row1['hrange'])
					{
						$bprice 	=$row1['bprice'];
						$quan  	=$ps1;
						$id   	=$row1['id'];
						$accid  	=$row1['accid'];
						$phsid	=$row1['phsid'];
						$matid	=$row1['matid'];
						$qtype	=$row1['qtype'];
						$mtype	=$row1['mtype'];
						$lrange	=$row1['lrange'];
						$hrange	=$row1['hrange'];
						$code		=$row1['code'];
						$item  	=$row1['item'];
						$a1   	=$row1['atrib1'];
						$supplier=$row1['supplier'];
						$super	=$row1['supercedes'];
					}
					elseif ($ps1 > $row1['hrange'])
					{
						$bprice 	=$row1['bprice']+(($ps1-$row1['hrange'])*$row1['quantity']);
						$quan  	=$ps1;
						$id   	=$row1['id'];
						$accid  	=$row1['accid'];
						$phsid	=$row1['phsid'];
						$matid	=$row1['matid'];
						$qtype	=$row1['qtype'];
						$mtype	=$row1['mtype'];
						$lrange	=$row1['lrange'];
						$hrange	=$row1['hrange'];
						$code		=$row1['code'];
						$item  	=$row1['item'];
						$a1   	=$row1['atrib1'];
						$supplier=$row1['supplier'];
						$super	=$row1['supercedes'];
					}
				}
			}
			elseif ($row0[1]==10) // Bracket SQFT (ranges)
			{
				$qry1  ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND baseitem=1 ORDER by lrange;";
				$res1  =mssql_query($qry1);

				while ($row1=mssql_fetch_array($res1))
				{
					if ($ps2 >= $row1['lrange'] && $ps2 <= $row1['hrange'])
					{
						//echo "TEST1<BR>";
						$bprice 	=$row1['bprice'];
						$quan  	=$ps2;
						$id   	=$row1['id'];
						$accid  	=$row1['accid'];
						$phsid	=$row1['phsid'];
						$matid	=$row1['matid'];
						$qtype	=$row1['qtype'];
						$mtype	=$row1['mtype'];
						$lrange	=$row1['lrange'];
						$hrange	=$row1['hrange'];
						$code		=$row1['code'];
						$item  	=$row1['item'];
						$a1   	=$row1['atrib1'];
						$supplier=$row1['supplier'];
						$super	=$row1['supercedes'];

					}
					elseif ($ps2 > $row1['hrange'])
					{
						//echo "TEST2<BR>";
						$bprice 	=$row1['bprice']+(($ps2-$row1['hrange'])*$row1['quantity']);
						$quan  	=$ps2;
						$id   	=$row1['id'];
						$accid  	=$row1['accid'];
						$phsid	=$row1['phsid'];
						$matid	=$row1['matid'];
						$qtype	=$row1['qtype'];
						$mtype	=$row1['mtype'];
						$lrange	=$row1['lrange'];
						$hrange	=$row1['hrange'];
						$code		=$row1['code'];
						$item  	=$row1['item'];
						$a1   	=$row1['atrib1'];
						$supplier=$row1['supplier'];
						$super	=$row1['supercedes'];
					}
				}
				//echo "PS2: ".$ps2."<br>";
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

				while ($rowpre1 = mssql_fetch_row($respre1))
				{
					if ($ps1 < $rowpre1[0])
					{
						$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bprice =$row1['bprice'];
						$quan  =$ps1;
					}
					elseif ($ps1 > $rowpre1[1])
					{
						$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[1]."' AND baseitem=1;";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bprice =$row1['bprice']+(($ps1-$rowpre1[1])*$row1['quantity']);
						$quan  =$ps1;
					}
					else
					{
						$qry1 ="SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$row0[3]."' AND accid='".$row0[0]."' AND lrange='".$ps1."' AND baseitem=1;";
						$res1 =mssql_query($qry1);
						$row1 =mssql_fetch_array($res1);

						$bprice =$row1['bprice'];
						$quan  =$ps1;
					}
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
				$phsid	=$row1['phsid'];
				$matid	=$row1['matid'];
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
			elseif ($row0[1]==9)
			{
			}
			elseif ($row0[1]==10)
			{
			}
			else
			{
				$id   	=$row1['id'];
				$accid  	=$row1['accid'];
				$phsid	=$row1['phsid'];
				$matid	=$row1['matid'];
				$qtype	=$row1['qtype'];
				$mtype	=$row1['mtype'];
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
			//echo "(".$row0[1].") ".$p."<br>";
			$ecnt--;
		}
	}

	$p_out=preg_replace("/,\Z/","",$p_out);
	if (isset($p_out) && strlen($p_out) > 3)
	{
		$data_out=array(0=>$p_out);
	}
	else
	{
		$data_out=array(0=>'0');
	}
	return $data_out;
}

function store_material_baseitems($jobid,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$p_out='';
	$data_out=array();
	$officeid=$_SESSION['officeid'];

	//$discount   =$viewarray['discount'];
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
		//$p_out='';
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
	}

	$p_out=preg_replace("/,\Z/","",$p_out);
	if (isset($p_out) && strlen($p_out) > 3)
	{
		$data_out=array(0=>$p_out);
	}
	else
	{
		$data_out=array(0=>'0');
	}
	return $data_out;
}

function stored_package_itemsold($rid,$filters)
{
	//echo "Stored Package Items: <br>";
	global $rctotal,$viewarray;
	$MAS=$_SESSION['pb_code'];

	$qry = "SELECT item FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$edata=explode(",",$filters);
	foreach ($edata as $en1 => $ev1)
	{
		//echo $ev1."<br>";
		$idata=explode(":",$ev1);
		//echo $idata[0]."<br>";
		if ($idata[0]==$rid)
		{
			$qry1 = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$idata[1]."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);

			$qry2 = "SELECT * FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row1['catid']."';";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);

			$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
			$res3 = mssql_query($qry3);
			$row3 = mssql_fetch_array($res3);

			$adjquan=package_quan_set($idata[3],$row1['quan_calc'],$idata[8],$viewarray['ps1'],$viewarray['ps2'],$viewarray['tzone'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7'],$viewarray['spa1'],$viewarray['spa2'],$viewarray['spa3'],$viewarray['deck']);
			$adjamt=$idata[7];

			if ($idata[6]==1) // Adjusts
			{
				$adjquan=$row1['quan_calc']+$idata[8];
				$adjamt=$row1['rp']+$idata[7];
			}
			elseif ($idata[6]==2) // Price Percent Adjust
			{
				$adjamt=($row1['rp']*$idata[7])*$adjquan;
			}
			elseif ($idata[6]==3)
			{
				$adjquan=$row1['quan_calc']+$idata[8];
			}
			elseif ($idata[6]==4) // Zero Price
			{
				$adjamt=($row1['rp']+($row1['rp'] * -1))*$idata[8];
			}
			elseif ($idata[6]==5)
			{
				$adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
			}
			elseif ($idata[6]==6)
			{
				$adjamt=($idata[2]+($idata[2] * -1))*$idata[8];
				$adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
			}

			$fadjamt=number_format($adjamt, 2, '.', '');

			echo "                  <tr>\n";
			echo "                     <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\"></td>\n";
			echo "                     <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\">\n";
			echo "								<table align=\"left\" width=\"100%\" border=0>\n";
			echo "								   <tr>\n";
			echo "								   	<td align=\"left\">".$row1['item']."</td>\n";
			echo "								   	<td align=\"right\">(".$row['item'].")</td>\n";
			echo "								   </tr>\n";
			echo "								</table>\n";
			echo "							</td>\n";
			echo "                     <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">".$adjquan."</td>\n";
			echo "                     <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">".$row3['abrv']."</td>\n";
			echo "                     <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"60\">".$fadjamt."</td>\n";
			echo "                     <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"60\"></td>\n";
			echo "                     <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"60\"></td>\n";
			echo "                  </tr>\n";
			$rctotal=$rctotal+$adjamt;
		}
	}
}

function test_payment_schedule()
{
	$out	=0;
	$iphs	=0;
	$p_ar	=array();
	$pcnt	=0;
	
	//echo count($_REQUEST['payschedule']).'<br>';
	
	if (isset($_REQUEST['payschedule']) && count($_REQUEST['payschedule']) > 0)
	{
		return false;
	}
	else
	{
		foreach ($_REQUEST as $n=>$v)
		{
			if (substr($n,0,4)=="phs_")
			{
				$phsid=substr($n,4);
				if (array_key_exists("per_".$phsid,$_REQUEST))
				{
					$p_ar[]=$_REQUEST['per_'.$phsid];
				}
			}
		}
		
		if (empty($_REQUEST['camt']))
		{
			return true;
		}
		else
		{
			if (!empty($_REQUEST['salestx']))
			{
				$camt=$_REQUEST['camt']+$_REQUEST['salestx'];
			}
			else
			{
				$camt=$_REQUEST['camt'];
			}
			
			$camtL=round($camt) - (count($p_ar)-2);
			$camtH=round($camt) + (count($p_ar)-2);
		}
		
		if (array_sum($p_ar) > $camtH || array_sum($p_ar) < $camtL)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//return $out;
}

function add_finan_cust($oid,$orig_oid,$cid,$sid,$uid)
{
	//echo "Adding WinFin<br>";
	error_reporting(E_ALL);
	$uid	= md5($_SESSION['securityid']);
	$nsecid	= 0;
	$fsecid	= 0;
	$qry  	= "SELECT cid FROM cinfo WHERE officeid='".$orig_oid."' AND cid='".$cid."';";
	$res  	= mssql_query($qry);
	$row  	= mssql_fetch_array($res);
	$nrow 	= mssql_num_rows($res);
	
	$qry1  	= "SELECT cid FROM tfinan_detail WHERE cid='".$cid."';";
	$res1  	= mssql_query($qry1);
	$row1  	= mssql_fetch_array($res1);
	$nrow1 	= mssql_num_rows($res1);
		
	//echo $qry."<br>";
	
	if ($nrow==1 && $nrow1 == 0)
	{
		$qry0  	= "SELECT name,gm,am,finan_from,finan_rep as fsecid FROM offices as o WHERE officeid='".$oid."';";
		$res0  	= mssql_query($qry0);
		$row0  	= mssql_fetch_array($res0);
		
		$qry0a 	= "SELECT name,gm,am,finan_from,finan_rep as fsecid FROM offices as o WHERE officeid='".$orig_oid."';";
		$res0a 	= mssql_query($qry0a);
		$row0a 	= mssql_fetch_array($res0a);
		
		/*if ($_SESSION['securityid']==26)
		{
			echo $qry0a."<br>";
		}*/
		
		$ctext  = "System Message - Finance Office Assigned: ".$row0['name'];

		if ($row0['gm']!=0)
		{
			$nsecid=$row0['gm'];
		}
		else
		{
			$nsecid=$row0['am'];
		}
		
		if (isset($row0a['fsecid']) && $row0a['fsecid']!=0)
		{
			$fsecid=$row0a['fsecid'];
		}
		else
		{
			$fsecid=0;
		}

		$qry1   = "UPDATE cinfo SET finan_from='".$oid."',finan_sec='".$nsecid."',finan_src='".$_REQUEST['finan']."',finan_date=getdate() WHERE officeid=".$orig_oid." AND cid=".$cid.";";
		$res1   = mssql_query($qry1);
		//echo $qry1."<br>";
		
		$qry1a  = "INSERT INTO tfinan_detail (cid,officeid,finan_from,financlose,recdate,uid,assigned) VALUES ('".$cid."','".$orig_oid."','".$oid."',0,getdate(),'".$uid."','".$fsecid."');";
		$res1a  = mssql_query($qry1a);
		
		/*if ($_SESSION['securityid']==26)
		{
			echo $qry1a."<br>";
		}*/
		
		$qry2   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
		$qry2  .= "VALUES ";
		$qry2  .= "('".$cid."','".$orig_oid."','".$_SESSION['securityid']."','contract','".$ctext."','".$uid."')";
		$res2  = mssql_query($qry2);
	}
}

function store_payment_schedule()
{
	if (isset($_REQUEST['payschedule']))
	{
		$amt='';
		$phs='';
		$per='';
		$ptype='';
		$icnt=1;
		
		foreach ($_REQUEST['payschedule'] as $n => $v)
		{
			if ($v['perc'] > 0)
			{
				if ($icnt!==count($_REQUEST['payschedule']))
				{
					$phs=$phs.$n.',';
					$per=$per.$v['perc'].',';
					$amt=$amt.$v['amt'].',';
				}
				else
				{
					$phs=$phs.$n;
					$per=$per.$v['perc'];
					$amt=$amt.$v['amt'];
				}
			}
			$icnt++;
		}
		
		$sched=array(1=>$phs,2=>$amt,3=>$per);
	}
	else
	{
		$iphs=0;
		$iper=0;
		$phs='';
		$per='';
		$ptype='';
	
		foreach ($_REQUEST as $n=>$v)
		{
			if (substr($n,0,4)=="phs_")
			{
				$phsid=substr($n,4);
				if (array_key_exists("per_".$phsid,$_REQUEST))
				{
					$phs=$phs.$phsid.",";
					$per=$per.removecomma($_REQUEST['per_'.$phsid]).",";
				}
			}
		}
	
		$phs=preg_replace("/,\Z/","",$phs);
		$per=preg_replace("/,\Z/","",$per);
	
		$sched=array(0=>$iphs,1=>$phs,2=>$per,3=>'');
	}
	
	if ($_SESSION['securityid']==269999999999999)
	{
		echo '<pre>';
		print_r($_REQUEST['payschedule']);
		echo '</pre>';
		
		echo '<pre>';
		print_r($sched);
		echo '</pre>';
	}
	
	return $sched;
}

function store_payment_sched_qb($va,$s)
{
	$db=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest');
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$pcnt=0;
	$ptype='';
	//echo '<pre>';
	//print_r($viewarray).'<br>';
	//print_r($_REQUEST['payschedule']).'<br>';
	//print_r($s).'<br>';
	
	foreach ($_REQUEST['payschedule'] as $nn => $vv)
	{
		if ($vv['amt'] > 0)
		{
			$qry	="INSERT INTO payment_schedule (oid,cid,phsid,amount,sid) VALUES (".$_SESSION['officeid'].",".$va['cid'].",'".$vv['phsid']."',cast('".$vv['amt']."' as money),".$_SESSION['securityid'].");";
			$res 	= mssql_query($qry);
			$pcnt++;
			//echo $qry.'<br>';
		}
	}
	
	//echo '</pre>';
	
	if ($pcnt > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function edit_add_price()
{
	if (empty($_REQUEST['royadj']))
	{
		$royadj=0;
	}
	else
	{
		$royadj=$_REQUEST['royadj'];
	}

	$prmanadj	=$_REQUEST['prmanadj'];
	$cmmanadj	=$_REQUEST['cmmanadj'];
	$pschadj	=$_REQUEST['pschadj'];

	$qry 	= "UPDATE jdetail SET ";
	$qry .= "raddnpr_man='".$prmanadj."',";
	$qry .= "raddncm_man='".$cmmanadj."',";
	$qry .= "raddnroy_man='".$royadj."',";
	$qry .= "psched_adj='".$pschadj."' ";
	$qry .= "WHERE officeid='".$_SESSION['officeid']."' ";
	$qry .= "AND jobid='".$_REQUEST['jobid']."' ";
	$qry .= "AND jadd='".$_REQUEST['jadd']."';";
	$res 	= mssql_query($qry);

	view_job_addendum_retail();
}

function parse_diffs($old,$new)
{
	// This function detects Cost ADDs, DELs, CHNGs for Addendums
	global $viewarray;

	//print_r($viewarray);

	$diff_out	=1;
	$ar_price	=0;
	$dr_price	=0;
	$cr_price	=0;
	$ac_price	=0;
	$dc_price	=0;
	$cc_price	=0;
	$c_cnt		=0;
	$t_achg_ar	="";
	$t_dchg_ar	="";
	$t_cchg_ar	="";
	$old_ar		=array();
	$new_ar		=array();

	$old=preg_replace("/,\Z/","",$old);
	$new=preg_replace("/,\Z/","",$new);
	//echo "OLD: ".$old."<br>";
	//echo "NEW: ".$new."<br>";

	//Start Variance Detection
	$ex_old=explode(",",$old);
	foreach ($ex_old as $n1 => $v1)
	{
		$in_old=explode(":",$v1);
		if (!in_array($in_old[0],$old_ar))
		{
			if (strlen($in_old[0]) >= 1)
			{
				$old_ar[]=$in_old[0];
			}
		}
	}

	$ex_new=explode(",",$new);
	foreach ($ex_new as $n2 => $v2)
	{
		$in_new=explode(":",$v2);
		if (!in_array($in_new[0],$new_ar))
		{
			if (strlen($in_new[0]) >= 1)
			{
				$new_ar[]=$in_new[0];
			}
		}
	}

	/*
	echo "<pre>";
	echo "OLD AR: <br>";
	print_r($old_ar);
	echo "NEW AR: <br>";
	print_r($new_ar);
	echo "</pre>";
	*/

	$add_ar_diff=array_diff($new_ar,$old_ar);
	$del_ar_diff=array_diff($old_ar,$new_ar);
	$inter_ar=array_intersect($old_ar,$new_ar);

	//unset($new_ar);
	//unset($old_ar);

	/*
	echo "<pre>";
	echo "ADD: <br>";
	print_r($add_ar_diff);
	echo "DEL: <br>";
	print_r($del_ar_diff);
	echo "INT: <br>";
	print_r($inter_ar);
	echo "</pre>";

	if (in_array("",$add_ar_diff)||in_array(0,$add_ar_diff))
	{
	echo "Found Empty in ADD<br>";
	}

	if (in_array("",$del_ar_diff)||in_array(0,$del_ar_diff))
	{
	echo "Found Empty in DEL<br>";
	}

	if (in_array("",$inter_ar))
	{
	echo "Found Empty in INT<br>";
	}
	*/

	foreach ($add_ar_diff as $nA1 => $vA1)
	{
		foreach ($ex_new as $nA2 => $vA2)
		{
			$in_nA2=explode(":",$vA2);
			if ($vA1==$in_nA2[0])
			{
				$achg_ar=$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[2].":".$in_nA2[10].":0,";
				//echo "ADD: ".$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6]."<br>";
				$t_achg_ar=$t_achg_ar.$achg_ar;
				$c_cnt++;
			}
		}
	}

	//DEL Diffs;
	foreach ($del_ar_diff as $nD1 => $vD1)
	{
		foreach ($ex_old as $nD2 => $vD2)
		{
			$in_nD2=explode(":",$vD2);
			if ($vD1==$in_nD2[0])
			{
				$Dquan=$in_nD2[2]*-1;
				$dchg_ar=$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6].":".$in_nD2[2].":".$in_nD2[10].":0,";
				//echo "DEL: ".$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6]."<br>";
				$t_dchg_ar=$t_dchg_ar.$dchg_ar;
				$c_cnt++;
			}
		}
	}

	//CHG Diffs
	//echo "CHG Diffs:<br>";
	//echo "<pre>";
	//print_r($inter_ar);
	//echo "</pre>";
	foreach ($inter_ar as $n3 => $v3)
	{
		$ch_ar1=array();
		foreach ($ex_old as $on3i => $ov3i)
		{
			$c_old=explode(":",$ov3i);
			if ($c_old[0]==$v3)
			{
				//if ($c_old[0]==1222)
				//{
				//	echo "OLD: ".$c_old[0]." - ".$c_old[1]." - ".$c_old[3]."<br>";
				//}
				foreach ($ex_new as $nn3i => $nv3i)
				{
					$c_new=explode(":",$nv3i);

					//if ($c_new[0]==1222)
					//{
					//	echo "NEW: ".$c_new[0]." - ".$c_new[1]." - ".$c_new[3]."<br>";
					//}
					if ($c_old[0]==$c_new[0])
					{
						if ($c_old[1]==$c_new[1])
						{
							if ($c_old[2]!=$c_new[2])
							{
								if ($c_old[0]==$c_new[0] && !in_array($c_new[1],$ch_ar1))
								{
									//echo "HIT<br>";
									$nquan=$c_new[2]-$c_old[2];
									$cchg_ar=$c_new[0].":".$c_new[1].":".$nquan.":".$c_new[3].":".$c_new[4].":".$c_new[5].":".$c_old[6].":".$c_new[2].":".$c_new[10].":1,";
									//echo "CHG: ".$c_new[0].":".$c_new[1].":".$nquan.":".$c_old[3].":".$c_new[4].":".$c_new[5].":".$c_old[6]."<br>";
									//echo "CHG: ".$c_new[0].":".$c_new[1].":".$nquan.":".$c_new[3].":".$c_new[4].":".$c_new[5].":".$c_old[6]."<br>";
									$t_cchg_ar=$t_cchg_ar.$cchg_ar;
									$ch_ar1[]=$c_new[1];
									$c_cnt++;
								}
							}
						}
					}
				}
			}
		}
	}

	//echo "# of Changes: ".$c_cnt."<br>";
	$tt_chg_ar=$t_achg_ar.$t_dchg_ar.$t_cchg_ar;
	$t_achg_ar=preg_replace("/,\Z/","",$t_achg_ar);
	$t_dchg_ar=preg_replace("/,\Z/","",$t_dchg_ar);
	$t_cchg_ar=preg_replace("/,\Z/","",$t_cchg_ar);
	$tt_chg_ar=preg_replace("/,\Z/","",$tt_chg_ar);
	$diff_out=array(0=>$t_achg_ar,1=>$t_dchg_ar,2=>$t_cchg_ar,3=>$c_cnt,4=>$tt_chg_ar);
	return $diff_out;
}

function parse_filter_diffs($old,$new)
{
	// This function detects Cost ADDs, DELs, CHNGs for Addendums
	global $viewarray;

	$c_cnt		=0;
	$cchg_ar		='';
	$tt_chg_ar	='';
	$old=preg_replace("/,\Z/","",$old);
	$new=preg_replace("/,\Z/","",$new);
	$ex_old=explode(",",$old);
	$ex_new=explode(",",$new);
	$ar_diff=filter_diffs($ex_new,$ex_old);

	/*
	echo "<pre>";
	echo "DIFFS: <br>";
	array2table($ar_diff);
	echo "----<br>";
	print_r($ar_diff);
	echo "</pre>";
	echo "<br>";
	*/

	foreach ($ar_diff as $vA2)
	{
		foreach ($vA2 as $vA3)
		{
			$in_nA2=explode(":",$vA3);
			if ($in_nA2[0])
			{
				$cchg_ar=$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[7].":".$in_nA2[8].",";
				//echo "FILTERS: ".$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[7].":".$in_nA2[8]."<br>";
				$tt_chg_ar=$tt_chg_ar.$cchg_ar;
				$c_cnt++;
			}
		}
	}

	$tt_chg_ar=preg_replace("/,\Z/","",$tt_chg_ar);
	$diff_out=array(0=>0,1=>0,2=>0,3=>$c_cnt,4=>$tt_chg_ar);
	return $diff_out;
}

function parse_filter_cost_diffs($filters)
{
	$MAS			=$_SESSION['pb_code'];
	$filters		=preg_replace("/,\Z/","",$filters);
	$c_cnt		=0;
	$l_chg		='';
	$m_chg		='';
	$tl_chg		='';
	$tm_chg		='';

	//echo "C: ".$costs."<br>";
	//echo "F: ".$filters."<br>";

	$ifilters	=explode(",",$filters);
	foreach ($ifilters as $n1=>$v1)
	{
		$in_v1	=explode(":",$v1);
		$qry1		="SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$in_v1[1]."';";
		$res1		=mssql_query($qry1);
		$row1		=mssql_fetch_array($res1);
		$nrow1	=mssql_num_rows($res1);

		if ($nrow1 > 0)
		{
			//echo $qry1."<br>";
			$qry1a	= "SELECT bprice FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1['cid']."';";
			$res1a	= mssql_query($qry1a);
			$row1a	= mssql_fetch_array($res1a);
			$nrow1a	= mssql_num_rows($res1a);

			if ($nrow1a > 0)
			{
				//echo $qry1a."<br>";
				$lbp	=number_format($row1a['bprice'], 2, '.', '');
			}
			else
			{
				$lbp	="0.00";
			}
			//echo "LRID: ".$row1['rid']."<br>";
			//echo "LCID: ".$row1['cid']."<br>----<br>";
			//1411:2343:4:0.00:7:982:0.00
			//echo "LAB: ".$in_v1[0].":".$in_v1[1].":".$in_v1[6].":".$in_v1[7].":".$in_v1[8].":".$row1['cid'].":".$in_v1[2]."<br>";
			$l_chg	=$in_v1[0].":".$in_v1[1].":".$in_v1[6].":".$in_v1[7].":".$in_v1[8].":".$row1['cid'].":".$lbp.",";
			$tl_chg	=$tl_chg.$l_chg;
		}
	}

	//echo "<hr>";

	foreach ($ifilters as $n2=>$v2)
	{
		$in_v2	=explode(":",$v2);
		$qry2		="SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$in_v2[1]."';";
		$res2		=mssql_query($qry2);
		$row2		=mssql_fetch_array($res2);
		$nrow2	=mssql_num_rows($res2);

		if ($nrow2 > 0)
		{
			//echo $qry2."<br>";
			$qry2a	="SELECT bprice,matid FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row2['cid']."';";
			$res2a	=mssql_query($qry2a);
			$row2a	=mssql_fetch_array($res2a);
			$nrow2a	=mssql_num_rows($res2a);

			if ($nrow2a > 0)
			{
				//echo $qry2a."<br>";
				if ($row2a['matid']!=0)
				{
					$qry2aa	="SELECT bp,id FROM [material_master] WHERE id='".$row2a['matid']."';";
					$res2aa	=mssql_query($qry2aa);
					$row2aa	=mssql_fetch_array($res2aa);
					$nrow2aa	=mssql_num_rows($res2aa);

					//echo $qry2aa."<br>";
					$mbp		=number_format($row2aa['bp'], 2, '.', '');
				}
				else
				{
					$mbp	=number_format($row2a['bprice'], 2, '.', '');
				}
			}
			else
			{
				$mbp	="0.00";
			}
			//echo "MRID: ".$row2['rid']."<br>";
			//echo "MCID: ".$row2['cid']."<br>----<br>";
			//1411:2343:4:0.00:7:982:0.00
			//echo "MAT: ".$in_v2[0].":".$in_v2[1].":".$in_v2[6].":".$in_v2[7].":".$in_v2[8].":".$row2['cid'].":".$in_v2[2]."<br>";
			$m_chg	=$in_v2[0].":".$in_v2[1].":".$in_v2[6].":".$in_v2[7].":".$in_v2[8].":".$row2['cid'].":".$mbp.",";
			$tm_chg	=$tm_chg.$m_chg;
		}
	}

	$tl_chg		=preg_replace("/,\Z/","",$tl_chg);
	$tm_chg		=preg_replace("/,\Z/","",$tm_chg);

	//echo $tl_chg."<br>";
	//echo $tm_chg."<br>";
	$diff_out=array(0=>0,1=>0,2=>0,3=>$c_cnt,4=>$tl_chg,5=>$tm_chg);
	return $diff_out;
}

function peri_sa_direct_costitem_diffs($old,$new,$ps1a,$ps1b,$ps2a,$ps2b)
{
	//$qtype_ar	=array(3,4,5,6,7,9,10,11,13,14,18,19,30);
	$ocp_cnt		=0;
	$ocs_cnt		=0;
	$ncp_cnt		=0;
	$ncs_cnt		=0;
	$tcp_cnt		=0;
	$tcs_cnt		=0;
	$qtype_ar	=array(3,4,9,10,11,13,14,18,19,30);
	$old_rw		="";
	$new_rw		="";
	$achg_oldp	="";
	$achg_olds	="";
	$achg_newp	="";
	$achg_news	="";
	$achg_cngp	="";
	$achg_cngs	="";
	$t_achg_top	="";
	$t_achg_tos	="";
	$t_achg_tnp	="";
	$t_achg_tns	="";
	$t_achg_tcp	="";
	$t_achg_tcs	="";

	$ps1_diff	=$ps1b-$ps1a;
	$ps2_diff	=$ps2b-$ps2a;

	echo "D_PS1: ".$ps1_diff."<br>";
	echo "D_PS2: ".$ps2_diff."<br><br>";

	$item_old=explode(",",$old);
	echo "IN_OLD:<br>";

	foreach ($item_old as $n1 => $v1)
	{
		echo $v1."<br>";
		$in_old=explode(":",$v1);
		if (in_array($in_old[7],$qtype_ar))
		{
			if (strlen($in_old[0]) >= 1)
			{
				if ($in_old[7]==3||$in_old[7]==9||$in_old[7]==13||$in_old[7]==18||$in_old[7]==30)
				{
					//$achg_old	=$in_old[0].":".$in_old[1].":".$in_old[2].":".$in_old[3].":".$in_old[4].":".$in_old[5].":".$in_old[6].":".$in_old[7].":".$in_old[8].":".$in_old[9].":".$in_old[10].",";
					$achg_oldp	=$in_old[0].":".$in_old[1].":".$in_old[2].":".$in_old[3].":".$ps1a.":".$in_old[5].":".$in_old[6].":".$in_old[7].":".$in_old[8].":".$in_old[9].":".$in_old[10].",";
					$t_achg_top=$t_achg_top.$achg_oldp;
					$ocp_cnt++;
				}
				else
				{
					//$achg_old	=$in_old[0].":".$in_old[1].":".$in_old[2].":".$in_old[3].":".$in_old[4].":".$in_old[5].":".$in_old[6].":".$in_old[7].":".$in_old[8].":".$in_old[9].":".$in_old[10].",";
					$achg_olds	=$in_old[0].":".$in_old[1].":".$in_old[2].":".$in_old[3].":".$ps2a.":".$in_old[5].":".$in_old[6].":".$in_old[7].":".$in_old[8].":".$in_old[9].":".$in_old[10].",";
					$t_achg_tos=$t_achg_tos.$achg_olds;
					$ocs_cnt++;
				}
			}
		}
	}

	$item_new=explode(",",$new);
	echo "IN_NEW:<br>";
	foreach ($item_new as $n1n => $v1n)
	{
		echo $v1n."<br>";
		$in_new=explode(":",$v1n);
		if (in_array($in_new[7],$qtype_ar))
		{
			if (strlen($in_new[0]) >= 1)
			{
				if ($in_new[7]==3||$in_new[7]==9||$in_new[7]==13||$in_new[7]==18||$in_new[7]==30)
				{
					//$achg_new	=$in_new[0].":".$in_new[1].":".$in_new[2].":".$in_new[3].":".$in_new[4].":".$in_new[5].":".$in_new[6].":".$in_new[7].":".$in_new[8].":".$in_new[9].":".$in_new[10].",";
					$achg_newp	=$in_new[0].":".$in_new[1].":".$in_new[2].":".$in_new[3].":".$ps1b.":".$in_new[5].":".$in_new[6].":".$in_new[7].":".$in_new[8].":".$in_new[9].":".$in_new[10].",";
					$t_achg_tnp=$t_achg_tnp.$achg_newp;
					$ncp_cnt++;
				}
				else
				{
					$achg_news	=$in_new[0].":".$in_new[1].":".$in_new[2].":".$in_new[3].":".$ps2b.":".$in_new[5].":".$in_new[6].":".$in_new[7].":".$in_new[8].":".$in_new[9].":".$in_new[10].",";
					$t_achg_tns=$t_achg_tns.$achg_news;
					$ncs_cnt++;
				}
			}
		}
	}

	$old_rwp=preg_replace("/,\Z/","",$t_achg_top);
	$old_rws=preg_replace("/,\Z/","",$t_achg_tos);
	$new_rwp=preg_replace("/,\Z/","",$t_achg_tnp);
	$new_rws=preg_replace("/,\Z/","",$t_achg_tns);

	echo "OP1: ".$old_rwp."<br>";
	echo "NP1: ".$new_rwp."<br><br>";
	echo "OS1: ".$old_rws."<br>";
	echo "NS1: ".$new_rws."<br><hr><br>";

	// Create Peri Diff Array
	if ($ocp_cnt==$ncp_cnt)
	{
		$oold_rwp	=explode(",",$old_rwp);
		foreach ($oold_rwp as $n1op => $v1op)
		{
			//echo "OLD SPLIT<BR>";
			if ($tcp_cnt != $ncp_cnt)
			{
				$in_ooldp=explode(":",$v1op);
				if ($in_ooldp[0])
				{
					$in_newp=explode(",",$new_rwp);
					foreach ($in_newp as $n1np => $v1np)
					{
						$in_nnewp=explode(":",$v1np);
						if ($in_nnewp[4]!=$in_ooldp[4])
						{
							$np_quan		=$in_nnewp[4] - $in_ooldp[4];
							$achg_cngp	=$in_nnewp[0].":".$in_nnewp[1].":".$in_nnewp[2].":".$in_nnewp[3].":".$np_quan.":".$in_nnewp[5].":".$in_nnewp[6].":".$in_nnewp[7].":".$in_nnewp[8].":".$in_nnewp[9].":".$in_nnewp[10].",";
							//echo 			 $in_nnewp[0].":".$in_nnewp[1].":".$in_nnewp[2].":".$in_nnewp[3].":".$np_quan.":".$in_nnewp[5].":".$in_nnewp[6].":".$in_nnewp[7].":".$in_nnewp[8].":".$in_nnewp[9].":".$in_nnewp[10]."<BR>";
							$t_achg_tcp=$t_achg_tcp.$achg_cngp;
							$tcp_cnt++;
						}
					}
				}
			}
		}
	}
	$t_achg_tcp=preg_replace("/,\Z/","",$t_achg_tcp);

	// Create SA Diff Array
	if ($ocs_cnt==$ncs_cnt)
	{
		$oold_rws	=explode(",",$old_rws);
		foreach ($oold_rws as $n1os => $v1os)
		{
			//echo "OLD SPLIT<BR>";
			if ($tcs_cnt != $ncs_cnt)
			{
				$in_oolds=explode(":",$v1os);
				if ($in_oolds[0])
				{
					$in_news=explode(",",$new_rws);
					foreach ($in_news as $n1ns => $v1ns)
					{
						$in_nnews=explode(":",$v1ns);
						if ($in_nnews[4]!=$in_oolds[4])
						{
							$ns_quan		=$in_nnews[4] - $in_oolds[4];
							$achg_cngs	=$in_nnews[0].":".$in_nnews[1].":".$in_nnews[2].":".$in_nnews[3].":".$ns_quan.":".$in_nnews[5].":".$in_nnews[6].":".$in_nnews[7].":".$in_nnews[8].":".$in_nnews[9].":".$in_nnews[10].",";
							//echo 			 $in_nnews[0].":".$in_nnews[1].":".$in_nnews[2].":".$in_nnews[3].":".$ns_quan.":".$in_nnews[5].":".$in_nnews[6].":".$in_nnews[7].":".$in_nnews[8].":".$in_nnews[9].":".$in_nnews[10]."<BR>";
							$t_achg_tcs=$t_achg_tcs.$achg_cngs;
							$tcs_cnt++;
						}
					}
				}
			}
		}
	}
	$t_achg_tcs=preg_replace("/,\Z/","",$t_achg_tcs);
	$t_changes	=$t_achg_tcp.$t_achg_tcs;

	$diffs=array(0=>0,1=>0,2=>0,3=>0,4=>$t_changes);
	return $diffs;
}

function countcostitems($data,$type)
{
	$MAS=$_SESSION['pb_code'];
	$ecnt=0;

	if (!empty($data))
	{
		$edata=explode(",",$data);
		foreach ($edata as $en1 => $ev1)
		{
			$idata=explode(":",$ev1);
			$qry = "SELECT id FROM [".$MAS."rclinks_".$type."] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$idata[0]."';";
			$res = mssql_query($qry);
			$nrow= mssql_num_rows($res);
			$ecnt=$ecnt+$nrow;
		}
	}
	return $ecnt;
}

function countpackageitems($data)
{
	$MAS=$_SESSION['pb_code'];
	$ecnt=0;

	if (!empty($data))
	{
		$edata=explode(",",$data);
		foreach ($edata as $en1 => $ev1)
		{
			$idata=explode(":",$ev1);
			$qry0 = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$idata[0]."';";
			$res0 = mssql_query($qry0);
			$row0	= mssql_fetch_array($res0);

			if ($row0['qtype']==55||$row0['qtype']==72)
			{
				$qry = "SELECT id FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row0['id']."';";
				$res = mssql_query($qry);
				$nrow= mssql_num_rows($res);
				$ecnt=$ecnt+$nrow;
			}
		}
	}
	return $ecnt;
}

function countpackagecostitems($data,$type)
{
	$MAS=$_SESSION['pb_code'];
	//echo "DATA: ".$data;
	$ecnt=0;

	if (!empty($data))
	{
		$edata=explode(",",$data);
		foreach ($edata as $en1 => $ev1)
		{
			$idata=explode(":",$ev1);
			$qry = "SELECT id FROM [".$MAS."rclinks_".$type."] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$idata[1]."';";
			$res = mssql_query($qry);
			$nrow= mssql_num_rows($res);
			$ecnt=$ecnt+$nrow;
		}
	}

	return $ecnt;
}

function store_packages($jobid,$jadd,$estdata)
{
	//echo "(Internal)<br>";
	// Takes an Estimate Data input, extrapolates Main Package Objects and related package items and filters and writes them to
	// a mulidimensional Text array in jdetail (filters)
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$p_arout='';
	$pcl_arout='';
	$pcm_arout='';
	$data_out=array();
	$excnt   =countpackageitems($estdata);
	$edata	=explode(",",$estdata);
	foreach ($edata as $en1 => $ev1)
	{
		$idata=explode(":",$ev1);

		$qry0 = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$idata[0]."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);

		if ($row0['qtype']==55||$row0['qtype']==72)
		{
			//echo "PDATA<br>";
			$qry1 = "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row0['id']."';";
			$res1 = mssql_query($qry1);
			$nrow1= mssql_num_rows($res1);

			//echo $qry1."<br>";
			/*
			if ($rowl['adjquan']==0)
			{
			$adjquan=$rowl['adjquan'];
			}
			else
			{
			$adjquan=1;
			}
			*/

			if ($nrow1 > 0)
			{
				while ($row1 = mssql_fetch_array($res1))
				{
					$qry2 = "SELECT id,rp,qtype,commtype,crate FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1['iid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$frpfil    =number_format($row2['rp'], 2, '.', '');

					if ($excnt!=1)
					{
						$p_ar=$idata[0].":".$row2['id'].":".$frpfil.":".$row2['qtype'].":".$row2['commtype'].":".$row2['crate'].":".$row1['adjtype'].":".$row1['adjamt'].":".$row1['adjquan'].",";
					}
					else
					{
						$p_ar=$idata[0].":".$row2['id'].":".$frpfil.":".$row2['qtype'].":".$row2['commtype'].":".$row2['crate'].":".$row1['adjtype'].":".$row1['adjamt'].":".$row1['adjquan'];
					}
					$p_arout=$p_arout.$p_ar;
					$excnt--;
				}
			}
		}
	}

	// Stores Package Related Labor Cost items
	$fxcnt		=countpackagecostitems($p_arout,'l');
	//echo "PI: ".$fxcnt."<br>";
	if (!empty($fxcnt)||$fxcnt > 0)
	{
		//echo "FXCNT: ".$fxcnt."<br>";
		$pcl_arout	='';
		$fdata		=explode(",",$p_arout);
		foreach ($fdata as $fn1 => $fv1)
		{
			$jdata=explode(":",$fv1);
			$qry4 = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$jdata[1]."';";
			$res4 = mssql_query($qry4);
			$row4 = mssql_fetch_array($res4);

			//echo $qry4."<br>";

			$qry5 = "SELECT * FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row4['id']."';";
			$res5 = mssql_query($qry5);
			$nrow5= mssql_num_rows($res5);

			if ($nrow5 > 0)
			{
				while ($row5 = mssql_fetch_array($res5))
				{
					$qry6 = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row5['cid']."';";
					$res6 = mssql_query($qry6);
					$row6 = mssql_fetch_array($res6);

					if ($row6['qtype'] >= 9 && $row6['qtype'] <= 12)
					{
						//echo "HIT<br>";
						//echo $qry6."<br>";
						if ($row6['qtype'] == 9)
						{
							$cdquan=$viewarray['ps1'];
						}
						elseif ($row6['qtype'] == 10)
						{
							$cdquan=$viewarray['ps2'];
						}
						elseif ($row6['qtype'] == 11)
						{
							$cdquan=$viewarray['ia'];
						}
						elseif ($row6['qtype'] == 12)
						{
							$cdquan=$viewarray['gals'];
						}

						$code		=$row6['accid'];
						$specout	=getspecaccpbook($code,$cdquan,$row6['quantity']);

						if ($specout[0]==0)
						{
							$flabbp	=0;
							$quan		=0;
						}
						else
						{
							$flabbp	=number_format($specout[0], 2, '.', '');
						}

						$lrange	=$specout[1];
						$hrange	=$specout[2];
						$quantity=$row6['quantity'];
					}
					else
					{
						//echo "NHIT<br>";
						$lrange	=$row6['lrange'];
						$hrange	=$row6['hrange'];
						$flabbp    =number_format($row6['bprice'], 2, '.', '');
					}
					//$flabbp    =number_format($row6['bprice'], 2, '.', '');

					if ($fxcnt!=1)
					{
						//breakout (package retail ID:retail item ID:Adjust Type:Adjust Amt:Adjust Quan:Cost Item ID:Retail Price:Cost Item qtype:Cost Price:0:0)
						$pcl_ar=$jdata[0].":".$jdata[1].":".$jdata[6].":".$jdata[7].":".$jdata[8].":".$row6['id'].":".$jdata[2].":".$row6['qtype'].":".$flabbp.":0:0".",";
					}
					else
					{
						$pcl_ar=$jdata[0].":".$jdata[1].":".$jdata[6].":".$jdata[7].":".$jdata[8].":".$row6['id'].":".$jdata[2].":".$row6['qtype'].":".$flabbp.":0:0";
					}
					///echo "PCL ".$pcl_ar."<br>";
					$pcl_arout=$pcl_arout.$pcl_ar;
					$fxcnt--;
				}
			}
		}
	}

	// Stores Package Related Material Cost items
	$gxcnt		=countpackagecostitems($p_arout,'m');
	if (!empty($gxcnt)||$gxcnt > 0)
	{
		//echo "FXCNT: ".$gxcnt."<br>";
		$pcm_arout	='';
		$gdata		=explode(",",$p_arout);
		foreach ($gdata as $gn1 => $gv1)
		{
			$kdata=explode(":",$gv1);

			$qry8 = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$kdata[1]."';";
			$res8 = mssql_query($qry8);
			$row8 = mssql_fetch_array($res8);

			$qry9 = "SELECT * FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row8['id']."';";
			$res9 = mssql_query($qry9);
			$nrow9= mssql_num_rows($res9);

			if ($nrow9 > 0)
			{
				while ($row9 = mssql_fetch_array($res9))
				{
					$qry10 = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row9['cid']."';";
					$res10 = mssql_query($qry10);
					$row10 = mssql_fetch_array($res10);

					if ($row10['matid']!=0)
					{
						$qry10a = "SELECT bp FROM material_master WHERE id='".$row10['matid']."';";
						$res10a = mssql_query($qry10a);
						$row10a = mssql_fetch_array($res10a);

						if ($row10['qtype']==56)
						{
							$fbp	=$row10['bprice'];
						}
						else
						{
							$fbp	=$row10a['bp'];
						}
					}
					else
					{
						$fbp=$row10['bprice'];
					}

					$ffbp	=number_format($fbp, 2, '.', '');

					if ($gxcnt!=1)
					{
						//breakout (package retail ID:retail item ID:Adjust Type:Adjust Amt:Adjust Quan:Cost Item ID:Retail Price:Cost Item qtype: Cost Price:0:0)
						//$pcm_ar=$kdata[0].":".$kdata[1].":".$kdata[6].":".$kdata[7].":".$kdata[8].":".$row10['invid'].":".$kdata[2].":".$row10['qtype'].":".$ffbp.":0:0".",";
						$pcm_ar=$kdata[0].":".$kdata[1].":".$kdata[6].":".$kdata[7].":".$kdata[8].":".$row10['invid'].":".$kdata[2].":".$row10['qtype'].":".$ffbp.":0:0".",";
					}
					else
					{
						//$pcm_ar=$kdata[0].":".$kdata[1].":".$kdata[6].":".$kdata[7].":".$kdata[8].":".$row10['invid'].":".$kdata[2].":".$row10['qtype'].":".$ffbp.":0:0";
						$pcm_ar=$kdata[0].":".$kdata[1].":".$kdata[6].":".$kdata[7].":".$kdata[8].":".$row10['invid'].":".$kdata[2].":".$row10['qtype'].":".$ffbp.":0:0";
					}
					//echo "PCM ".$pcm_ar."<br>";
					$pcm_arout=$pcm_arout.$pcm_ar;
					$gxcnt--;
				}
			}
		}
	}

	$data_out=array(0=>$p_arout,1=>$pcl_arout,2=>$pcm_arout);
	return $data_out;
}

function store_labor_cost_items($jobid,$jadd,$estdata)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$p_arout='';
	$data_out=array();
	$edata=explode(",",$estdata);
	$ecnt=countcostitems($estdata,"l");

	//print_r($viewarray);

	foreach ($edata as $en1 => $ev1)
	{
		$idata=explode(":",$ev1);

		$qry0 = "SELECT id,rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$idata[0]."';";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		if ($nrow0 > 0)
		{
			while ($row0 = mssql_fetch_array($res0))
			{
				$qry1 = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row0['cid']."';";
				$res1 = mssql_query($qry1);
				$row1 = mssql_fetch_array($res1);

				//echo $qry1."<br>";

				if ($row1['qtype'] >= 9 && $row1['qtype'] <= 12)
				{
					//echo $qry1."<br>";
					if ($row1['qtype'] == 9)
					{
						$cdquan=$viewarray['ps1'];
					}
					elseif ($row1['qtype'] == 10)
					{
						$cdquan=$viewarray['ps2'];
					}
					elseif ($row1['qtype'] == 11)
					{
						$cdquan=$viewarray['ia'];
					}
					elseif ($row1['qtype'] == 12)
					{
						$cdquan=$viewarray['gals'];
					}

					$code		=$row1['accid'];
					$specout	=getspecaccpbook($code,$cdquan,$row1['quantity']);

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
					$qry2 .= "WHERE 		b.officeid='".$_SESSION['officeid']."' ";
					$qry2 .= "AND	 		b.estid='".$_SESSION['estid']."';";
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

				if ($ecnt!=1)
				{
					$p_ar=$idata[0].":".$row1['id'].":".$idata[2].":".$fbprice.":".$row1['qtype'].":".$idata[4].":".$lrange.":".$hrange.":".$row1['phsid'].":".$row1['rinvid'].":".$row1['quantity'].",";
				}
				else
				{
					$p_ar=$idata[0].":".$row1['id'].":".$idata[2].":".$fbprice.":".$row1['qtype'].":".$idata[4].":".$lrange.":".$hrange.":".$row1['phsid'].":".$row1['rinvid'].":".$row1['quantity'];
				}
				//echo $p_ar."<br>";
				$p_arout=$p_arout.$p_ar;
				$ecnt--;
				
				if (isset($viewarray['enqb']) and $viewarray['enqb']==1)
				{
					//echo $p_ar."<br>";
					if ($row1['qtype']!=33)
					{
						$viewarray['jc_ar']['service'][]=array(
															   'srvid'=>$row1['id'],
															   'oid'=>$_SESSION['officeid'],
															   'jobid'=>$viewarray['jobid'],
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
					else
					{
						
					}
				}
			}
		}
	}

	if (strlen($p_arout) > 3)
	{
		$data_out=array(0=>$p_arout);
	}
	else
	{
		$data_out=array(0=>0);
	}

	return $data_out;
}

function store_material_cost_items($jobid,$jadd,$estdata)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$qry = "SELECT estdata FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jadd."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$p_arout='';
	$data_out=array();
	//$edata=explode(",",$row['estdata']);
	//$ecnt=countcostitems($row['estdata'],"m");
	$edata=explode(",",$estdata);
	$ecnt=countcostitems($estdata,"m");
	foreach ($edata as $en1 => $ev1)
	{
		$idata=explode(":",$ev1);

		$qry0 = "SELECT id,rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$idata[0]."';";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);

		if ($nrow0 > 0)
		{
			while ($row0 = mssql_fetch_array($res0))
			{
				$qry1 = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row0['cid']."';";
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

				if ($ecnt!=1)
				{
					$p_ar=$idata[0].":".$row1['invid'].":".$idata[2].":".$fbprice.":".$row1['qtype'].":".$idata[4].":".$row1['phsid'].":".$row1['rinvid'].",";
				}
				else
				{
					$p_ar=$idata[0].":".$row1['invid'].":".$idata[2].":".$fbprice.":".$row1['qtype'].":".$idata[4].":".$row1['phsid'].":".$row1['rinvid'];
				}
				$p_arout=$p_arout.$p_ar;
				
				if (isset($viewarray['enqb']) and $viewarray['enqb']==1)
				{
					if ($row1['qtype']!=33)
					{
						if ($row1['matid']!=0)
						{
							$viewarray['jc_ar']['inventory'][]=array(
																   'invid'=>$row1['invid'],
																   'oid'=>$_SESSION['officeid'],
																   'jobid'=>$viewarray['jobid'],
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
						else
						{
							$viewarray['jc_ar']['material'][]=array(
																   'invid'=>$row1['invid'],
																   'oid'=>$_SESSION['officeid'],
																   'jobid'=>$viewarray['jobid'],
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
				
				$ecnt--;
			}
		}
	}

	//$qry3 = "UPDATE jdetail SET costdata_m='".$p_arout."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jadd."';";
	//$res3 = mssql_query($qry3);
	if (strlen($p_arout) > 3)
	{
		$data_out=array(0=>$p_arout);
	}
	else
	{
		$data_out=array(0=>0);
	}
	return $data_out;
}

function delete_job($jobid,$jadd)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	if ($_REQUEST['call']=="delete_job1")
	{
		$qryA = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		$nrowA= mssql_num_rows($resA);
		
		$qryAa = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);
		$nrowAa= mssql_num_rows($resAa);
		
		if ($rowA['custid']!=$rowAa['cid'])
		{
			$cid=$rowAa['cid'];
		}
		else
		{
			$cid=$rowAa['cid'];
		}

		$qryAb = "SELECT jobid,jadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jadd."';";
		//$qryAb	= "SELECT J.id,J.jobid,J.jadd,(select estid from jobs where officeid=J.officeid and jobid=J.jobid) as estid FROM jdetail AS J WHERE J.officeid='".$_SESSION['officeid']."' AND J.jobid='".$jobid."' AND J.jadd='".$jadd."';";
		$resAb = mssql_query($qryAb);
		$rowAb = mssql_fetch_array($resAb);
		$nrowAb= mssql_num_rows($resAb);

		$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);

		$acclist=explode(",",$_SESSION['aid']);
		if (!in_array($rowA['securityid'],$acclist))
		{
			echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to Delete this Job</b>";
			exit;
		}
		elseif ($rowA['njobid']!='0')
		{
			echo "<br><font color=\"red\"><b>Delete Error</b></font><br><b>This Contract still has a Job attached.<br>Delete the Job before attempting to delete the contract.</b>";
			exit;
		}

		echo "<form method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "<input type=\"hidden\" name=\"call\" value=\"delete_job2\">\n";
		echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowA['securityid']."\">\n";
		echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowA['sidm']."\">\n";
		echo "<input type=\"hidden\" name=\"jobid\" value=\"".$rowA['jobid']."\">\n";
		echo "<input type=\"hidden\" name=\"jadd\" value=\"".$rowAb['jadd']."\">\n";
		echo "<input type=\"hidden\" name=\"cid\" value=\"".$rowA['custid']."\">\n";
		echo "<input type=\"hidden\" name=\"estid\" value=\"".$rowA['estid']."\">\n";
		echo "<table class=\"outer\" align=\"center\" width=\"300px\" border=0>\n";
		echo "   <tr>\n";
		echo "      <th class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"left\">\n";

		if ($jadd > 0)
		{
			echo "Confirm Delete Addendum:";
		}
		else
		{
			echo "Confirm Revert Contract to Estimate:";
		}

		echo "		</th>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <th class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"left\">\n";

		if ($nrowAb > 1 && $jadd > 0)
		{
			echo "         <font color=\"red\">!ERROR! This Job cannot be Deleted, Addendum exists!</font>\n";
		}

		echo "		</th>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Contract #:</b></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
		echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowA['jobid']."\" DISABLED>\n";
		echo "      </td>\n";
		echo "   </tr>\n";

		if ($jadd > 0)
		{
			echo "   <tr>\n";
			echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Addn Id:</b></td>\n";
			echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
			echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowAb['jadd']."\" DISABLED>\n";
			echo "      </td>\n";
			echo "   </tr>\n";
		}

		echo "   <tr>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Customer:</b></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
		echo "         <input class=\"bboxl\" type=\"text\" value=\"".$rowB['cfname']." ".$rowB['clname']."\">\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\">\n";

		if ($nrowAb > 1 && $jadd > 0)
		{
			echo "         <button type=\"submit\" DISABLED>Approve</button>\n";
		}
		else
		{
			echo "         <button type=\"submit\">Approve</button>\n";
		}

		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
		echo "</form>\n";

	}
	elseif ($_REQUEST['call']=="delete_job2")
	{
		$qry	= "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
		$res	= mssql_query($qry);
		$row	= mssql_fetch_array($res);
		$nrow	= mssql_num_rows($res);

		$qryA	= "SELECT J.id,J.jobid,J.jadd,(select estid from jobs where officeid=J.officeid and jobid=J.jobid) as estid FROM jdetail AS J WHERE J.officeid='".$_SESSION['officeid']."' AND J.jobid='".$jobid."' AND J.jadd='".$jadd."';";
		$resA	= mssql_query($qryA);
		$rowA	= mssql_fetch_array($resA);
		$nrowA= mssql_num_rows($resA);

		$acclist=explode(",",$_SESSION['aid']);
		if (!in_array($row['securityid'],$acclist))
		{
			echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have appropriate Access Rights to Delete this Job</b>";
			exit;
		}

		if ($nrow > 0 && $nrowA == 1)
		{
			$qryB	= "exec dbo.sp_deletejob @officeid='".$_SESSION['officeid']."',@cid='".$row['cid']."',@estid='".$rowA['estid']."',@jobid='".$rowA['jobid']."',@jadd='".$rowA['jadd']."';";
			$resB	= mssql_query($qryB);
			
			// Commission History & Schedule Tables
			if ($rowA['jadd']!=0)
			{
				$qryCa	= "DELETE FROM jest..CommissionHistory WHERE oid='".$_SESSION['officeid']."' AND jobid='".$jobid."' and jadd=".$rowA['jadd'].";";
				$resCa	= mssql_query($qryCa);
				
				$qryCb	= "DELETE FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$jobid."' and jadd=".$rowA['jadd']." AND (cbtype!=10 AND cbtype!=11);";
				$resCb	= mssql_query($qryCb);
				
				$qryCc	= "UPDATE jest..CommissionSchedule SET jobid='0' WHERE oid='".$_SESSION['officeid']."' AND estid='".$rowA['estid']."' and jadd=".$rowA['jadd']." AND (cbtype!=10 OR cbtype!=11);";
				$resCc	= mssql_query($qryCc);
			}
			else
			{
				$qryCa	= "DELETE FROM jest..CommissionHistory WHERE oid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND cbtype=4;";
				$resCa	= mssql_query($qryCa);
				
				$qryCb	= "DELETE FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND (cbtype!=10 AND cbtype!=11);";
				$resCb	= mssql_query($qryCb);
				
				$qryCc	= "UPDATE jest..CommissionSchedule SET jobid='0' WHERE oid='".$_SESSION['officeid']."' AND estid='".$rowA['estid']."' AND (cbtype!=10 OR cbtype!=11);";
				$resCc	= mssql_query($qryCc);
			}
			
			// Construction Dates Table
			$qryD	= "delete from jest..constructiondates where cid=".$row['cid'].";";
			$resD	= mssql_query($qryD);
			
			// Quickbooks Staging Tables
			$qryE = "delete from [jest]..[JobCost_Service] where oid=".$_SESSION['officeid']." and jobid='".$jobid."';";
			$resE	= mssql_query($qryE);
			
			$qryF = "delete from [jest]..[JobCost_Inventory] where oid=".$_SESSION['officeid']." and jobid='".$jobid."';";
			$resF	= mssql_query($qryF);
			
			$qryG = "delete from [jest]..[payment_schedule] where oid=".$_SESSION['officeid']." and cid=".$row['cid']." and phsid!=0;";
			$resG	= mssql_query($qryG);
	
			if ($jadd > 0)
			{
				view_job_retail();
			}
			else
			{
				$_SESSION['action']	="est";
				$_SESSION['estid']	=$rowA['estid'];
				include ("./estimatematrix_func.php");
				include ("./estimatematrix_support_func.php");
				viewest_retail();	
			}
		}
	}
}

function store_retail_package_items($id,$quan,$code,$jobid,$jadd,$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck,$oitem,$rseq)
{
	$MAS=$_SESSION['pb_code'];
	$qry0		= "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$id."';";
	$res0		= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			//print_r($row0);
			$qry1		= "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row0['iid']."';";
			$res1		= mssql_query($qry1);
			$nrow1	= mssql_num_rows($res1);

			if ($nrow1 > 0)
			{
				while ($row1 = mssql_fetch_array($res1))
				{
					$items	=retail_item_calc($row1['id'],$quan,$code,$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

					if ($row0['adjtype']==2) //Percent Price Adjusts
					{
						$adjquan	=package_quan_set($row1['qtype'],$quan,$row0['adjquan'],$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);
						$sadjamt	=$items[12];
						$adjamt	=($sadjamt*$row0['adjamt'])*$adjquan;

						//echo "ADJtype".$row0['adjtype']."<br>";
						//echo "pADJquan".$row0['adjamt']."<br>";
						//echo "ADJquan: ".$adjquan."<br>";
						//echo "sADJamt: ".$sadjamt."<br>";
						//echo "ADJamt: ".$adjamt."<br>";

					}
					elseif ($row0['adjtype']==4) // Zero Price Adjusts
					{

						$adjquan	=package_quan_set($row1['qtype'],$quan,$row0['adjquan'],$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);
						$sadjamt	="0.00";
						$adjamt	="0.00";
					}
					else
					{
						$sadjamt	=$items[12];
						$adjamt	=$items[12];
						$adjquan	=$items[2];
					}
					/*
					echo "<hr><br>";
					echo "POSTADJtype".$row0['adjtype']."<br>";
					echo "POSTpADJquan".$row0['adjamt']."<br>";
					echo "POSTADJquan: ".$adjquan."<br>";
					echo "POSTsADJamt: ".$sadjamt."<br>";
					echo "POSTADJamt: ".$adjamt."<br>";
					*/

					$qry2		= "INSERT INTO jretailitems ";
					$qry2	  .= "(officeid,jobid,jadd,dbid,phsid,catid,qtype,mtype,catname,item,atrib1,atrib2,atrib3,rp,trp,comm,tcomm,commtype,quantity,lrange,hrange,calcval,code,seqn) ";
					$qry2	  .= "VALUES ";
					$qry2	  .= "('".$_SESSION['officeid']."',";
					$qry2	  .= "'".$jobid."','".$jadd."','".$id."',";
					$qry2	  .= "'0','".$items[8]."','".$items[9]."','".$items[4]."',";
					$qry2	  .= "'catname','".$items[6]." (".$oitem.")','".$items[7]."',";
					//$qry2	  .= "'atrib2','atrib3','".$items[12]."','".$adjamt."',";
					$qry2	  .= "'atrib2','atrib3','".$sadjamt."','".$adjamt."',";
					$qry2	  .= "'0','0','0','".$adjquan."',";
					$qry2	  .= "'".$items[13]."','".$items[14]."','".$items[15]."','".$items[5]."','".$rseq."');";
					$res2		= mssql_query($qry2);

				}
			}
		}
	}
}

function array_diff_deep_search($v1,$v2)
{
	//if (array_diff($v1[0],$v2[0]))
	//{
	//
	//}
	$diff1=array_diff($v1[0],$v2[0]);
	show_array_vars($diff1);
	echo "<br>";
	$diff2=array_diff($v1[1],$v2[1]);
	show_array_vars($diff2);
	echo "<br>";
}

function display_cinfo($custid)
{
	$qry0		= "SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0		= mssql_query($qry0);
	$row0	   = mssql_fetch_array($res0);

	$qry1		= "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	$res1		= mssql_query($qry1);
	$nrow1	   = mssql_num_rows($res1);

	$mjadds=$nrow1-1;

	$qry4		= "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$custid."';";
	$res4		= mssql_query($qry4);
	$row4	   = mssql_fetch_array($res4);

	if ($row0[0]==1)
	{
		$qry6		= "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$row4['scounty']."';";
		$res6		= mssql_query($qry6);
		$row6	   = mssql_fetch_array($res6);
		$city    =$row6['city'];
	}
	else
	{
		$city    =$row4['scounty'];
	}

	echo "                  <table width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Customer # </b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"><b>".$custid."</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\" width=\"80\"><b>Name</b> </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"cfname\" size=\"15\" maxlength=\"20\" value=\"".$row4['cfname']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"clname\" size=\"29\" maxlength=\"42\" value=\"".$row4['clname']."\">\n";
	echo "                        </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Site</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"saddr1\" size=\"50\" maxlength=\"42\" value=\"".$row4['saddr1']."\">\n";
	echo "                        </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>City</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"scity\" size=\"20\" maxlength=\"42\" value=\"".$row4['scity']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"sstate\" size=\"5\" maxlength=\"42\" value=\"".$row4['sstate']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"szip1\" size=\"10\" maxlength=\"42\" value=\"".$row4['szip1']."\">\n";
	echo "                        </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Phone</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"chome\" size=\"15\" maxlength=\"42\" value=\"".$row4['chome']."\"> home\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ccell\" size=\"15\" maxlength=\"42\" value=\"".$row4['ccell']."\"> cell\n";
	echo "                        </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Twnshp/Cnty</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"scounty\" size=\"25\" maxlength=\"30\" value=\"".$city."\">\n";
	echo "                        </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"></td>\n";
	echo "                        <td valign=\"bottom\" align=\"right\">\n";
	echo "                        </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function display_pool_details()
{
	if ($_REQUEST['call']=="view_jadd_retail"||$_REQUEST['call']=="post_save_add")
	{
		$jaddn	=$_REQUEST['jadd'];
	}
	else
	{
		$jaddn	=0;
	}

	$qry0		= "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	$res0		= mssql_query($qry0);
	$row0	   = mssql_fetch_array($res0);

	$qry1		= "SELECT securityid,fname,lname,officeid FROM security WHERE securityid='".$row0['securityid']."';";
	$res1		= mssql_query($qry1);
	$row1	   = mssql_fetch_array($res1);

	$qry2		= "SELECT officeid,name,sm,gm FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res2		= mssql_query($qry2);
	$row2	   = mssql_fetch_array($res2);

	$qry3		= "SELECT securityid,fname,lname FROM security WHERE securityid='".$row0['sidm']."';";
	$res3		= mssql_query($qry3);
	$row3	   = mssql_fetch_array($res3);

	$qry5		= "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$jaddn."';";
	$res5		= mssql_query($qry5);
	$row5	   = mssql_fetch_array($res5);

	$qry7		= "SELECT * FROM status_codes WHERE snum='".$row0['status']."';";
	$res7		= mssql_query($qry7);
	$row7	   = mssql_fetch_array($res7);

	$set_gals=calc_gallons($row5['pft'],$row5['sqft'],$row5['shal'],$row5['mid'],$row5['deep']);
	$set_ia	=calc_internal_area($row5['pft'],$row5['sqft'],$row5['shal'],$row5['mid'],$row5['deep']);
	$set_deck=deckcalc($row5['pft'],$row5['deck']);
	$incdeck	=round($set_deck[0]);
	$cdate = date("m-d-Y", strtotime($row5['contractdate']));

	echo "         <table align=\"center\" width=\"100%\" border=0>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"left\" NOWRAP>\n";
	echo "                  <table width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"left\"><b>Job #".$row0['jobid']." Breakdown";

	if ($_REQUEST['call']=="view_jadd_retail")
	{
		echo " Addendum # ".$row5['jadd']." ";
	}

	echo "               for ".$row2[1]."</b>\n";
	echo "	                     </td>\n";
	echo "                        <td align=\"right\"><b>Contract Date</b> <input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$cdate."\"> </td>";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "               <td rowspan=\"2\" class=\"gray\" align=\"right\" NOWRAP>\n";
	echo "                  <table width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>SalesRep</b></td>\n";
	echo "                        <td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row1[1]." ".$row1[2]."\"></td>\n";
	echo "                        <td align=\"right\"><b>Sales Manager</b></td>\n";
	echo "                        <td align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row3[1]." ".$row3[2]."\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Perimeter</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$row5['pft']."\">\n";
	echo "                        </td>\n";
	echo "	                     <td align=\"right\"><b>Gallons</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$set_gals."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Surface Area</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$row5['sqft']."\">\n";
	echo "                        </td>\n";
	echo "	                     <td align=\"right\"><b>Internal Area</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$set_ia."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Depths</b></td>\n";
	echo "	                     <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$row5['shal']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"".$row5['mid']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"".$row5['deep']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Electrical Run</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"5\" maxlength=\"5\" value=\"".$row5['erun']."\">\n";
	echo "                        </td>\n";
	echo "	                     <td align=\"right\"><b>Plumbing Run</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"5\" maxlength=\"5\" value=\"".$row5['prun']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Deck</b></td>\n";
	echo "	                     <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"deck\" size=\"4\" maxlength=\"4\" value=\"".$row5['deck']."\"> \n";

	if ($row5['pft'] > 0)
	{
		echo " (<b>$incdeck</b> sqft Deck Incl.)";
	}

	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Travel</b></td>\n";
	echo "	                     <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$row5['tzone']."\">\n";
	echo "                        </td>\n";
	echo "	                     <td align=\"right\"><b>Referral</b></td>\n";
	echo "	                     <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"refto\" size=\"15\" value=\"".$row5['refto']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Status</b></td>\n";
	echo "                        <td colspan=\"3\" class=\"gray\" align=\"left\">\n";
	echo "                           <i>".$row7['description']."</i>\n";
	echo "	                     </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"400\">\n";

	display_cinfo($row0['custid']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
}

function display_pool_details_var()
{
	//if ($_REQUEST['call']=="view_jadd_retail"||$_REQUEST['call']=="post_save_add")
	// {
	$jaddn1	=$_REQUEST['jadd1'];
	$jaddn2	=$_REQUEST['jadd2'];
	// }
	// else
	//{
	//	  $jaddn	=0;
	//}

	$qry0		= "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	$res0		= mssql_query($qry0);
	$row0	   = mssql_fetch_array($res0);

	$qry1		= "SELECT securityid,fname,lname,officeid FROM security WHERE securityid='".$row0['securityid']."';";
	$res1		= mssql_query($qry1);
	$row1	   = mssql_fetch_array($res1);

	$qry2		= "SELECT officeid,name,sm,gm FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res2		= mssql_query($qry2);
	$row2	   = mssql_fetch_array($res2);

	$qry3		= "SELECT securityid,fname,lname FROM security WHERE securityid='".$row2['sm']."';";
	$res3		= mssql_query($qry3);
	$row3	   = mssql_fetch_array($res3);

	$qry5		= "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$jaddn1."';";
	$res5		= mssql_query($qry5);
	$row5	   = mssql_fetch_array($res5);

	$qry5a	= "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$jaddn2."';";
	$res5a	= mssql_query($qry5a);
	$row5a	= mssql_fetch_array($res5a);

	$qry7		= "SELECT * FROM status_codes WHERE snum='".$row0['status']."';";
	$res7		= mssql_query($qry7);
	$row7	   = mssql_fetch_array($res7);

	$set_gals1=calc_gallons($row5['pft'],$row5['sqft'],$row5['shal'],$row5['mid'],$row5['deep']);
	$set_gals2=calc_gallons($row5a['pft'],$row5a['sqft'],$row5a['shal'],$row5a['mid'],$row5a['deep']);
	$set_ia1	=calc_internal_area($row5['pft'],$row5['sqft'],$row5['shal'],$row5['mid'],$row5['deep']);
	$set_ia2	=calc_internal_area($row5a['pft'],$row5a['sqft'],$row5a['shal'],$row5a['mid'],$row5a['deep']);
	$set_deck1=deckcalc($row5['pft'],$row5['deck']);
	$set_deck2=deckcalc($row5a['pft'],$row5a['deck']);
	$incdeck1	=round($set_deck1[0]);
	$incdeck2	=round($set_deck2[0]);
	$cdate1 = date("m-d-Y", strtotime($row5['contractdate']));
	$cdate2 = date("m-d-Y", strtotime($row5a['contractdate']));

	echo "         <table align=\"center\" width=\"100%\" border=1>\n";
	echo "            <tr>\n";
	echo "               <td class=\"gray\" align=\"left\" NOWRAP>\n";
	echo "                  <table width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"left\"><b>Job #".$row0['jobid']." Addendum Variance Display";

	if ($_REQUEST['call']=="view_jadd_retail")
	{
		echo " Addendum # ".$row5['jadd']." ";
	}

	echo "               for ".$row2[1]."</b>\n";
	echo "	                     </td>\n";
	echo "                        <td align=\"right\"><b>Contract Date</b> <input class=\"bboxl\" type=\"text\" size=\"15\" value=\"";

	if ($cdate1==$cdate2)
	{
		echo $cdate1;
	}
	else
	{
		echo "$cdate1 ($cdate2)";
	}

	echo "\"> </td>";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "               <td rowspan=\"2\" class=\"gray\" align=\"right\" NOWRAP>\n";
	echo "                  <table width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>SalesRep</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row1[1]." ".$row1[2]."\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Sales Manager</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row3[1]." ".$row3[2]."\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Peri</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"";

	if ($row5['pft']==$row5a['pft'])
	{
		echo $row5['pft'];
	}
	else
	{
		echo "".$row5['pft']." (".$row5a['pft'].")";
	}

	echo "\">\n";
	echo "                        </td>\n";
	echo "	                     <td align=\"right\"><b>Gal</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"";

	if ($set_gals1==$set_gals2)
	{
		echo $set_gals1;
	}
	else
	{
		echo $set_gals1." (".$set_gals2.")";
	}

	echo "\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>SA</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"";

	if ($row5['sqft']==$row5a['sqft'])
	{
		echo $row5['sqft'];
	}
	else
	{
		echo "".$row5['sqft']." (".$row5a['sqft'].")";
	}

	echo "\">\n";
	echo "                        </td>\n";
	echo "	                     <td align=\"right\"><b>IA</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"";

	if ($set_ia1==$set_ia2)
	{
		echo $set_ia1;
	}
	else
	{
		echo $set_ia1." (".$set_ia2.")";
	}

	echo "\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>S/M/D</b></td>\n";
	echo "	                     <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"";

	if ($row5['shal']==$row5a['shal'])
	{
		echo $row5['shal'];
	}
	else
	{
		echo "".$row5['shal']." (".$row5a['shal'].")";
	}

	echo "\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"";

	if ($row5['mid']==$row5a['mid'])
	{
		echo $row5['mid'];
	}
	else
	{
		echo "".$row5['mid']." (".$row5a['mid'].")";
	}

	echo "\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"";

	if ($row5['deep']==$row5a['deep'])
	{
		echo $row5['deep'];
	}
	else
	{
		echo "".$row5['deep']." (".$row5a['deep'].")";
	}

	echo "\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>E. Run</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"5\" maxlength=\"5\" value=\"";

	if ($row5['erun']==$row5a['erun'])
	{
		echo $row5['erun'];
	}
	else
	{
		echo "".$row5['erun']." (".$row5a['erun'].")";
	}

	echo "\">\n";
	echo "                        </td>\n";
	echo "	                     <td align=\"right\"><b>P. Run</b></td>\n";
	echo "                        <td align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"5\" maxlength=\"5\" value=\"";

	if ($row5['prun']==$row5a['prun'])
	{
		echo $row5['prun'];
	}
	else
	{
		echo "".$row5['prun']." (".$row5a['prun'].")";
	}

	echo "\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Deck</b></td>\n";
	echo "	                     <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"deck\" size=\"4\" maxlength=\"4\" value=\"";

	if ($row5['deck']==$row5a['deck'])
	{
		echo $row5['deck'];
		echo "\"> \n";
	}
	else
	{
		echo "".$row5['deck']." (".$row5a['deck'].")";
		echo "\"> \n";
	}

	if ($row5['pft'] > 0)
	{
		if ($incdeck1==$incdeck2)
		{
			echo " (<b>$incdeck1</b> sqft Deck Incl.)";
		}
		else
		{
			echo " (<b>$incdeck1 ($incdeck2)</b> sqft Deck Incl.)";
		}
	}


	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Travel</b></td>\n";
	echo "	                     <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"";

	if ($row5['tzone']==$row5a['tzone'])
	{
		echo $row5['tzone'];
	}
	else
	{
		echo "".$row5['tzone']." (".$row5a['tzone'].")";
	}

	echo "\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Status</b></td>\n";
	echo "                        <td colspan=\"3\" class=\"gray\" align=\"left\">\n";
	echo "                           <i>".$row7['description']."</i>\n";
	echo "	                     </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"400\">\n";

	display_cinfo($row0['custid']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
}

function create_addendum()
{
	build_addendum_start();
}

function create_job()
{
	//$MAS=$_SESSION['offcode'];
	$qrypre1		= "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
	$respre1		= mssql_query($qrypre1);
	$rowpre1		= mssql_fetch_array($respre1);

	$qrypre2		= "SELECT psched,psched_perc,code,stax,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2		= mssql_query($qrypre2);
	$rowpre2		= mssql_fetch_array($respre2);
	
	$qrypre3		= "SELECT cid,clname,cfname FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rowpre1['ccid']."' ;";
	$respre3		= mssql_query($qrypre3);
	$rowpre3		= mssql_fetch_array($respre3);
	
	
	if ($_SESSION['securityid']==26)
	{
		//echo $qrypre3.'<br>';
	}

	if ($rowpre2['stax']==1)
	{
		if ($rowpre1['tax']=="0.00")
		{
			$contractamt=$rowpre1['contractamt'];
			$salestx		=$_REQUEST['salestax'];
			$camt			=$contractamt+$salestx;

		}
		else
		{
			$contractamt=$rowpre1['contractamt'];
			$salestx		=$rowpre1['tax'];
			$camt			=$contractamt+$salestx;
		}
	}
	else
	{
		$camt			=$rowpre1['contractamt'];
	}

	$fcamt	=number_format($camt, 2, '.', '');

	$tdate	=date("m/d/Y", time());
	$sdate	=date("m/d/Y", time());
	$cdate	=date("mdy", time());

	$contractcode=$rowpre1['estid'].".".$rowpre2['code'].".".$cdate;

	//echo $qrypre1."<br>";
	echo "<form name=\"chkjob1\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"create_job_chk\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowpre1['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowpre1['sidm']."\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$rowpre1['estid']."\">\n";
	echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowpre1['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$rowpre3['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"jobid\" value=\"".$contractcode."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"camt\" value=\"".$fcamt."\">\n";
	echo "<input type=\"hidden\" name=\"renov\" value=\"".$rowpre1['renov']."\">\n";
	echo "<input type=\"hidden\" name=\"overunder\" value=\"".$_REQUEST['overunder']."\">\n";

	if ($rowpre2['stax']==1)
	{
		echo "<input type=\"hidden\" name=\"salestx\" value=\"".$salestx."\">\n";
	}

	echo "<table class=\"outer\" align=\"center\" width=\"35%\" border=0>\n";
	echo "   <tr>\n";
	echo "      <th class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"left\">Create New Contract</th>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>System Insert Date:</b></td>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">".$sdate."</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Customer Name:</b></td>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">".$rowpre3['clname'].", ".$rowpre3['cfname']."</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Contract #:</b></td>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	//echo "         <input class=\"bboxl\" type=\"text\" name=\"jobid\" value=\"".$contractcode."\" DISABLED>\n";
	echo $contractcode;
	
	if ($rowpre1['renov']==1)
	{
		echo " <b>Renovation</b>";
	}
		
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Contract Date:</b></td>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	//echo "         <input class=\"bboxl\" type=\"text\" name=\"cdate\">\n";
	echo "					<input class=\"bboxl\" type=\"text\" name=\"cdate\" id=\"cdate\">\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Estimate #:</b></td>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	
	echo $rowpre1['estid'];
	
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"><b>Contract Amount:</b></td>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	
	echo $fcamt;

	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"></td>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";

	if ($rowpre2['stax']==1)
	{
		echo " Sales Tax Included.";
	}

	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"right\"></td>\n";
	echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\">\n";
	echo "         <table>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"gray\" align=\"left\"><input class=\"bboxl\" name=\"dep\" type=\"text\" value=\"0.00\"></td>\n";
	echo "              <td NOWRAP class=\"gray\" align=\"left\"><b>Down Payment</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"gray\" align=\"left\"><input class=\"bboxl\" name=\"r2p\" type=\"text\" value=\"0.00\"></td>\n";
	echo "              <td NOWRAP class=\"gray\" align=\"left\"><b>Secondary Payee</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	
	if ($rowpre2['finan_from']!=0)
	{
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Financing</b></td>\n";
		echo "		<td class=\"gray\" align=\"left\">\n";
		echo "			<select name=\"finan\">\n";
		echo "				<option value=\"0\"></option>\n";
		
		if ($rowpre2['finan_from']!=9999)
		{
			echo "				<option value=\"4\">BH Finance</option>\n";
		}
		
		echo "				<option value=\"2\">Customer Finance</option>\n";
		echo "				<option value=\"3\">Cash</option>\n";
		echo "			</select>\n";
		echo "		</td>\n";
		echo "   </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td colspan=\"2\" NOWRAP class=\"gray\" align=\"left\"><hr width=\"100%\"></td>\n";
	echo "           </tr>\n";

	// Errors
	$keys=array_search(0,$rowpre1);

	$errinp=0;

	if ($rowpre1['pft']==0)
	{
		$errinp++;
	}

	if ($rowpre1['sqft']==0)
	{
		$errinp++;
	}

	if ($rowpre1['erun']==0)
	{
		$errinp++;
	}

	if ($rowpre1['prun']==0)
	{
		$errinp++;
	}

	if ($rowpre1['contractamt']==0)
	{
		$errinp++;
	}

	//if ($rowpre1['applyov']==0)
	//{
	//	$errinp++;
	//}

	if ($errinp > 0)
	{
		echo "   <tr>\n";
		echo "      <td class=\"gray\" valign=\"top\" align=\"right\"><b>Errors:</b></td>\n";
		echo "      <td class=\"gray\" valign=\"bottom\" align=\"left\"><br>\n";
		echo "			<font color=\"red\">";

		if ($rowpre1['pft']==0)
		{
			echo "				Perimeter<br>";
			$errinp++;
		}

		if ($rowpre1['sqft']==0)
		{
			echo "				Surface Area Not Set<br>";
			$errinp++;
		}

		if ($rowpre1['erun']==0)
		{
			echo "				Electrical Run Not Set<br>";
			$errinp++;
		}

		if ($rowpre1['prun']==0)
		{
			echo "				Plumbing Run Not Set<br>";
			$errinp++;
		}

		if ($rowpre1['contractamt']==0)
		{
			echo "				Contract Amount Not Set<br>";
			$errinp++;
		}

		echo "			</font>\n";
		echo "		</td>\n";
		echo "   </tr>\n";
	}

	echo "   <tr>\n";
	echo "      <td class=\"gray\" colspan=\"2\" valign=\"bottom\" align=\"right\">\n";

	if ($errinp > 0)
	{
		echo "         <button type=\"submit\" DISABLED>Validate Contract</button>\n";
	}
	else
	{
		echo "         <button type=\"submit\">Validate Contract</button>\n";
	}

	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function create_job_chk()
{
	$qrypre1		= "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
	$respre1		= mssql_query($qrypre1);
	$rowpre1		= mssql_fetch_array($respre1);

	$qrypre2		= "SELECT psched,psched_perc,stax,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2		= mssql_query($qrypre2);
	$rowpre2		= mssql_fetch_array($respre2);

	$rdays		=7;
	$isvaliddate=valid_date($_REQUEST['cdate']);
	$dateoutofrange=dateoutofrange($_REQUEST['cdate'],$rdays);

	$sdate	=date("m / d / Y", time());
	
	$uid		=time().".".$_SESSION['securityid'];

	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"post_create_job\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowpre1['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowpre1['sidm']."\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$rowpre1['estid']."\">\n";
	echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowpre1['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$_REQUEST['tcid']."\">\n";
	echo "<input type=\"hidden\" name=\"camt\" value=\"".$rowpre1['contractamt']."\">\n";
	echo "<input type=\"hidden\" name=\"overunder\" value=\"".$_REQUEST['overunder']."\">\n";

	if ($rowpre2['stax']==1)
	{
		echo "<input type=\"hidden\" name=\"salestx\" value=\"".$_REQUEST['salestx']."\">\n";
	}

	//echo "<input type=\"hidden\" name=\"salestx\" value=\"".$_REQUEST['salestx']."\">\n";
	//echo "<input type=\"hidden\" name=\"camt\" value=\"".$_REQUEST['camt']."\">\n";
	echo "<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
	echo "<input type=\"hidden\" name=\"cdate\" value=\"".$_REQUEST['cdate']."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"renov\" value=\"".$_REQUEST['renov']."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";

	echo "<table class=\"outer\" align=\"center\" width=\"35%\" border=0>\n";
	echo "   <tr>\n";
	echo "      <th class=\"gray\" colspan=\"2\" align=\"left\"><b>Create New Contract (Validate)</b></th>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>System Insert Date:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">".$sdate."</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Contract #:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input class=\"bboxl\" type=\"text\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\" DISABLED>\n";
	
	if ($rowpre1['renov']==1)
	{
		echo " <b>Renovation</b>";
	}
	
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Contract Date:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input class=\"bboxl\" type=\"text\" name=\"cdate\" value=\"".$_REQUEST['cdate']."\" DISABLED>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Estimate #:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input class=\"bboxl\" type=\"text\" name=\"estid\" value=\"".$rowpre1['estid']."\" DISABLED>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" align=\"right\"><b>Contract Amount:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";
	echo "         <input class=\"bboxl\" type=\"text\" value=\"".$_REQUEST['camt']."\" DISABLED>\n";

	if ($rowpre2['stax']==1)
	{
		echo " Sales Tax Included.";
	}

	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"top\" align=\"right\"><b>Suggested Payment Schedule:</b></td>\n";
	echo "      <td class=\"gray\" align=\"left\">\n";

	if ($rowpre2[0]!="0" && $rowpre2[1]!="0")
	{
		//$depres	=$rowpre1['contractamt']-$_REQUEST['dep'];
		$depres	=$_REQUEST['camt']-($_REQUEST['dep']+$_REQUEST['r2p']);
		$fr2p		=number_format($_REQUEST['r2p'], 2, '.', '');
		$fdep		=number_format($_REQUEST['dep'], 2, '.', '');
		$fdepres	=number_format($depres, 2, '.', '');
		$pdep		=round(($_REQUEST['dep']/$_REQUEST['camt']) * 100);
		$sdep		=round(($_REQUEST['r2p']/$_REQUEST['camt']) * 100);
		
		//echo $_REQUEST['camt']."<br>";
		//echo $_REQUEST['dep']."<br>";
		//echo $_REQUEST['r2p']."<br>";
		//echo $depres."<br>";
		//echo $fdepres."<br>";
		
		echo "         <table width=\"100%\">\n";
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"gray\" align=\"left\"><input class=\"bboxl\" name=\"per_501L\" type=\"text\" value=\"".$fdep."\"></td>\n";
		echo "              <input name=\"phs_501L\" type=\"hidden\" value=\"501L\">\n";
		echo "              <td NOWRAP class=\"gray\" align=\"left\">501L</td>\n";
		echo "              <td NOWRAP class=\"gray\" align=\"left\">Down Payment</td>\n";
		//echo "              <td NOWRAP class=\"gray\" align=\"left\">(".$pdep."%)</td>\n";
		echo "              <td NOWRAP class=\"gray\" align=\"left\"></td>\n";
		echo "           </tr>\n";

		$phsar=explode(",",$rowpre2[0]);
		$perar=explode(",",$rowpre2[1]);

		if (count($phsar)==count($perar))
		{
			foreach ($phsar as $an => $pc)
			{
				$qryZ = "SELECT phscode,phsname,extphsname FROM phasebase WHERE phscode='".$pc."';";
				$resZ = mssql_query($qryZ);
				$rowZ = mssql_fetch_array($resZ);

				$paymnt	=$depres*$perar[$an];
				$fpaymnt	=number_format($paymnt, 2, '.', '');
				$fperc	=$perar[$an]*100;
				//$fperc	=round(($paymnt/$_REQUEST['camt'])*100);
				echo "           <tr>\n";
				echo "              <td NOWRAP class=\"gray\" align=\"left\"><input class=\"bboxl\" name=\"per_".$rowZ['phscode']."\" type=\"text\" value=\"".$fpaymnt."\"></td>\n";
				echo "              <input name=\"phs_".$rowZ['phscode']."\" type=\"hidden\" value=\"".$rowZ['phscode']."\">\n";
				echo "              <td NOWRAP class=\"gray\" align=\"left\">".$rowZ['phscode']."</td>\n";
				echo "              <td NOWRAP class=\"gray\" align=\"left\">".$rowZ['extphsname']."</td>\n";
				echo "              <td NOWRAP class=\"gray\" align=\"left\">(".$fperc."%)</td>\n";
				echo "           </tr>\n";
			}
		}
	
		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"gray\" align=\"left\"><input class=\"bboxl\" name=\"per_531L\" type=\"text\" value=\"".$fr2p."\"></td>\n";
		echo "              <input name=\"phs_531L\" type=\"hidden\" value=\"531L\">\n";
		echo "              <td NOWRAP class=\"gray\" align=\"left\">531L</td>\n";
		echo "              <td NOWRAP class=\"gray\" align=\"left\">Secondary Payee</td>\n";
		//echo "              <td NOWRAP class=\"gray\" align=\"left\">(".$sdep."%)</td>\n";
		echo "              <td NOWRAP class=\"gray\" align=\"left\"></td>\n";
		echo "           </tr>\n";
		echo "         </table>\n";
	}

	echo "      </td>\n";
	echo "   </tr>\n";
	
	if ($rowpre2['finan_from']!=0)
	{
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\"><b>Financing:</b></td>\n";
		echo "		<td class=\"gray\" align=\"left\">\n";
		
		if ($_REQUEST['finan']==1)
		{
			echo "Winners";
		}
		elseif ($_REQUEST['finan']==2)
		{
			echo "Customer Finance";
		}
		elseif ($_REQUEST['finan']==3)
		{
			echo "Cash";
		}
		elseif ($_REQUEST['finan']==4)
		{
			echo "BH Finance";
		}
		
		echo "			<input name=\"finan\" type=\"hidden\" value=\"".$_REQUEST['finan']."\">\n";
		echo "		</td>\n";
		echo "   </tr>\n";
		echo "           <tr>\n";
		echo "              <td colspan=\"4\" NOWRAP class=\"gray\" align=\"left\"><hr width=\"100%\"></td>\n";
		echo "           </tr>\n";
	}
	
	$errinp=0;

	if ($isvaliddate==0)
	{
		$errinp++;
	}

	if ($dateoutofrange==1)
	{
		$errinp++;
	}
	
	if (empty($_REQUEST['jobid']) || strlen($_REQUEST['jobid']) < 3)
	{
		$errinp++;
	}

	if ($rowpre2['finan_from']!=0 && $_REQUEST['finan']==0)
	{
		$errinp++;
	}

	if ($errinp > 0)
	{
		echo "   <tr>\n";
		echo "      <td class=\"gray\" align=\"right\" valign=\"top\"><b>Errors:</b></td>\n";
		echo "      <td class=\"gray\" align=\"left\">\n";
		echo "			<font color=\"red\">";

		// Errors
		$keys=array_search(0,$rowpre1);

		if ($isvaliddate==0)
		{
			echo "Date Format Error or Date Incorrect<br>";
			echo "Format should be 01/01/2005 or 01-01-2005<br>";
		}

		if ($dateoutofrange==1)
		{
			echo " Contract Date is Greater than ".$rdays." days in the future.<br>";
		}
		
		if (empty($_REQUEST['jobid']) || strlen($_REQUEST['jobid']) < 3)
		{
			echo "Job ID Error<br>";
		}

		if ($rowpre2['finan_from']!=0 && $_REQUEST['finan']==0)
		{
			echo "Financing Option not Set!<br>";
		}

		echo "			</font>\n";
		echo "		</td>\n";
		echo "   </tr>\n";
	}

	echo "   <tr>\n";
	echo "      <td class=\"gray\" colspan=\"2\" align=\"right\">\n";

	if ($errinp > 0)
	{
		echo "         <button type=\"submit\" DISABLED>Create Contract</button>\n";
	}
	else
	{
		echo "         <button type=\"submit\">Create Contract</button>\n";
	}

	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function post_create_job()
{
	//echo 'Create Contract<br>';
	if ($_SESSION['securityid']==269999999999999999999999999999)
	{
		echo '<pre>';
		print_r($_REQUEST);
		echo '</pre>';
	}
		
		
	$tpay=test_payment_schedule();
	
	//echo "PAY: ".var_dump($tpay)."<br>";
	if (empty($_REQUEST['jobid'])||$_REQUEST['jobid']=='')
	{
		echo "<font color=\"red\"><b>ERROR!</b></font>: No Contract ID! Please go Back and enter a Job ID.";
		exit;
	}
	elseif ($tpay)
	{
		echo "<font color=\"red\"><b>ERROR!</b></font>: Payment Schedule is out of Balance! Please go Back and Correct.";
		exit;
	}
	else
	{
		$qry1	= "SELECT jobid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."'";
		$res1	= mssql_query($qry1);
		$nrow1	= mssql_num_rows($res1);
		
		if ($_SESSION['securityid']==26)
		{
			///echo $qry1.'<br>';
		}

		if ($nrow1 > 0)
		{
			//echo "<font color=\"red\"><b>ERROR!</b></font>: Job Already Exists!\n";
			//exit;
			
			view_job_retail();
		}
		else
		{
			insert_job();
		}
	}
}

function insert_job()
{
	if ($_SESSION['securityid']==26) {
		ini_set('display_errors','On');
		error_reporting(E_ALL);
	}
	
	global $viewarray;
	//error_reporting(E_ALL);
	
	$finanset=0;
	$vacnt=0;

	$qry		= "SELECT officeid,pft_sqft,stax,finan_from,accountingsystem,enquickbooks FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res		= mssql_query($qry);
	$row		= mssql_fetch_array($res);
	
	if ($row['finan_from']!=0)
	{
		if (empty($_REQUEST['finan'])||$_REQUEST['finan']==0)
		{
			echo "<font color=\"red\"><b>ERROR</b></font><br>Financing not indicated!<br>Click BACK and SELECT the appropriate Financing.\n";
			exit;
		}
		else
		{
			$finanset=1;
		}
	}

	$qry0	= "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
	$res0	= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);

	if ($nrow0!=1)
	{
		echo "<font color=\"red\"><b>ERROR!</b></font>: Duplicate Estimates!";
		exit;
	}
	else
	{
		$row0	   = mssql_fetch_array($res0);

		$set_ia     =calc_internal_area($row0['pft'],$row0['sqft'],$row0['shal'],$row0['mid'],$row0['deep']);
		$set_gals   =calc_gallons($row0['pft'],$row0['sqft'],$row0['shal'],$row0['mid'],$row0['deep']);

		$viewarray	=array(
		'ps1'	=>$row0['pft'],
		'ps2'	=>$row0['sqft'],
		'ps4'	=>$row0['tzone'],
		'ps5'	=>$row0['shal'],
		'ps6'	=>$row0['mid'],
		'ps7'	=>$row0['deep'],
		'spa1'	=>$row0['spatype'],
		'spa2'	=>$row0['spa_pft'],
		'spa3'	=>$row0['spa_sqft'],
		'deck'	=>$row0['deck1'],
		'tzone'	=>$row0['tzone'],
		'ia'	=>$set_ia,
		'gals'	=>$set_gals,
		'renov'	=>$row0['renov'],
		'cid'	=>$row0['cid'],
		'jobid'	=>$_REQUEST['jobid'],
		'enqb'	=>$row['enquickbooks']
		);

		$qry1	= "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res1	= mssql_query($qry1);
		$row1	= mssql_fetch_array($res1);

		$qry2	= "SELECT * FROM est_discounts WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res2	= mssql_query($qry2);
		$row2	= mssql_fetch_array($res2);

		$qry3	= "SELECT * FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res3	= mssql_query($qry3);
		$row3	= mssql_fetch_array($res3);

		if ($viewarray['renov']==1)
		{
			$rbtable="rbpricep_renov";
		}
		else
		{
			$rbtable="rbpricep";
		}

		$qry4a	= "SELECT SUM(quan1) as quan1t FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."';";
		$res4a	= mssql_query($qry4a);
		$row4a	= mssql_fetch_array($res4a);

		if ($row['pft_sqft']=="p")
		{
			$defmeas=$row0['pft'];
		}
		else
		{
			$defmeas=$row0['sqft'];
		}

		if ($row4a['quan1t'] > 0)
		{
			$qry4		= "SELECT quan,quan1,price,comm FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan;";
			$res4		= mssql_query($qry4);

			while ($row4 = mssql_fetch_array($res4))
			{
				if ($defmeas >= $row4['quan'] && $defmeas <= $row4['quan1'])
				{
					$bp	=$row4['price'];
					$bc	=$row4['comm'];
				}
			}
		}
		else
		{
			$qry4	= "SELECT price,comm FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
			$res4	= mssql_query($qry4);
			$row4	= mssql_fetch_array($res4);

			$bp	=$row4['price'];
			$bc	=$row4['comm'];
		}

		if ($row[2]==1)
		{
			$qry0a 	="SELECT scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' and cid='".$_REQUEST['custid']."';";
			$res0a 	=mssql_query($qry0a);
			$row0a 	=mssql_fetch_array($res0a);

			$qry0b 	="SELECT taxrate FROM taxrate WHERE id='".$row0a['scounty']."';";
			$res0b 	=mssql_query($qry0b);
			$row0b 	=mssql_fetch_array($res0b);

			$taxrate	=$row0b['taxrate'];
			$tax		=$_REQUEST['camt']*$taxrate;
		}
		else
		{
			$taxrate	="0.00";
			$tax		="0.00";
		}
		
		$psched		=store_payment_schedule();
		$verifiedpr	=align_pricing($row1['estdata']);
		$pkgdata	=store_packages($_REQUEST['jobid'],0,$verifiedpr);
		$lbrcost	=store_labor_cost_items($_REQUEST['jobid'],0,$verifiedpr);
		$matcost	=store_material_cost_items($_REQUEST['jobid'],0,$verifiedpr);
		$lbrbcost	=store_labor_baseitems($_REQUEST['jobid'],0);
		$matbcost	=store_material_baseitems($_REQUEST['jobid'],0);
		$insstatus	=2;
		$sid		=0;

		//if ($_SESSION['securityid']!=26)
		//{
		$qry5		= "exec dbo.sp_save_est_to_contr ";
		$qry5	  .= "@officeid='".$_SESSION['officeid']."',";
		$qry5	  .= "@jobid='".$_REQUEST['jobid']."',";
		$qry5	  .= "@estid='".$_REQUEST['estid']."',";
		$qry5	  .= "@custid='".$_REQUEST['custid']."',";
		$qry5	  .= "@status='".$insstatus."',";
		//$qry5	  .= "@sid='".$sid."',";
		$qry5	  .= "@securityid='".$_REQUEST['securityid']."',";
		$qry5	  .= "@sidm='".$_REQUEST['sidm']."',";
		$qry5	  .= "@pft='".$row0['pft']."',";
		$qry5	  .= "@sqft='".$row0['sqft']."',";
		$qry5	  .= "@shal='".$row0['shal']."',";
		$qry5	  .= "@mid='".$row0['mid']."',";
		$qry5	  .= "@deep='".$row0['deep']."',";
		$qry5	  .= "@spa_pft='".$row0['spa_pft']."',";
		$qry5	  .= "@spa_sqft='".$row0['spa_sqft']."',";
		$qry5	  .= "@spatype='".$row0['spatype']."',";
		$qry5	  .= "@deck='".$row0['deck1']."',";
		$qry5	  .= "@erun='".$row0['erun']."',";
		$qry5	  .= "@prun='".$row0['prun']."',";
		$qry5	  .= "@tzone='".$row0['tzone']."',";
		$qry5	  .= "@bcomm='".$row0['bcomm']."',";
		$qry5	  .= "@comm='".$row0['comm']."',";
		$qry5	  .= "@applyov='".$row0['applyov']."',";
		$qry5	  .= "@comadj='".$row0['comadj']."',";
		$qry5	  .= "@estdata='".$verifiedpr."',";
		$qry5	  .= "@bpprice='".$bp."',";
		$qry5	  .= "@bpcomm='".$bc."',";
		$qry5	  .= "@renov='".$row0['renov']."',";
		$qry5	  .= "@camt='".$_REQUEST['camt']."',";
		$qry5	  .= "@cdate='".$_REQUEST['cdate']."',";
		$qry5	  .= "@psched1='".$psched[1]."',";
		$qry5	  .= "@psched2='".$psched[2]."',";
		$qry5	  .= "@refto='".$row0['refto']."',";
		$qry5	  .= "@refamt='".$row0['refamt']."',";
		$qry5	  .= "@tax='".$tax."',";
		$qry5	  .= "@taxrate='".$taxrate."',";
		$qry5	  .= "@filters='".$pkgdata[0]."',";
		$qry5	  .= "@pcostdata_l='".$pkgdata[1]."',";
		$qry5	  .= "@pcostdata_m='".$pkgdata[2]."',";
		$qry5	  .= "@costdata_l='".$lbrcost[0]."',";
		$qry5	  .= "@costdata_m='".$matcost[0]."',";
		$qry5	  .= "@bcostdata_l='".$lbrbcost[0]."',";
		$qry5	  .= "@bcostdata_m='".$matbcost[0]."';";
		$res5		= mssql_query($qry5);
		
		store_dis_items($_REQUEST['estid'],$_REQUEST['jobid'],0);

		store_bid_items($_REQUEST['estid'],$_REQUEST['jobid'],0);
		
		store_mpa_items($_REQUEST['estid'],$_REQUEST['jobid'],0);
		
		store_com_items($_REQUEST['estid'],$_REQUEST['jobid'],0,$_REQUEST['securityid'],$_REQUEST['sidm'],$_REQUEST['cdate'],$_REQUEST['custid']);

		if ($finanset==1 && $row['finan_from']!=0 && !empty($_REQUEST['uid']))
		{
			//echo "SET FINAN<br>";
			add_finan_cust($row['finan_from'],$_SESSION['officeid'],$_REQUEST['tcid'],$_REQUEST['securityid'],$_REQUEST['uid']);
		}
		
		if (isset($_REQUEST['overunder']) && strlen($_REQUEST['overunder']) >= 1)
		{
			$qryZa	= "update jobs set overunder=cast('".$_REQUEST['overunder']."' as money) WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."'";
			$resZa	= mssql_query($qryZa);
		}
		
		if (isset($_REQUEST['adjbook']) && strlen($_REQUEST['adjbook']) >= 1)
		{
			$qryZb	= "update jobs set adjbook=cast('".$_REQUEST['adjbook']."' as money) WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."'";
			$resZb	= mssql_query($qryZb);
		}

		if (isset($_REQUEST['payschedule']) && is_array($_REQUEST['payschedule']))
		{
			foreach ($_REQUEST['payschedule'] as $pn => $pv)
			{
				//echo 'PN:'.$pn.''.'<br>';
				if ($pn=='501L')
				{
					if (isset($pv['amt']) && $pv['amt']!=0)
					{
						$qryZd	= "select id from jest..constructiondates where cid=".$_REQUEST['custid']." and jobid='".trim($_REQUEST['jobid'])."' and phsid=48 and dtype=3;";
						$resZd	= mssql_query($qryZd);
						$nresZd	= mssql_num_rows($resZd);
						
						if ($nresZd == 0)
						{
							$qryZc	= "insert into jest..constructiondates (cid,phsid,jobid,cdate,dtype,ramt,auid) values (".$_REQUEST['custid'].",48,'".trim($_REQUEST['jobid'])."',getdate(),3,convert(money,'".$pv['amt']."'),".$_SESSION['securityid'].");";
							$resZc	= mssql_query($qryZc);
						}
					}
				}
			}
		}
		
		/*
		if (isset($viewarray['enqb']) and $viewarray['enqb'] == 1)
		{
			quickbooks_customerxmit($_REQUEST['custid']);
			store_payment_sched_qb($viewarray,$psched);
		}
		*/
		
		$qryZ	= "SELECT jobid FROM jobs WHERE officeid=".$_SESSION['officeid']." AND jobid='".$_REQUEST['jobid']."'";
		$resZ	= mssql_query($qryZ);
		$nrowZ	= mssql_num_rows($resZ);
		
		if ($nrowZ > 0)
		{
			view_job_retail();
		}
	}
}

function quickbooks_customerxmit($cid)
{
	global $viewarray;
	
	$qry	= "SELECT cid,officeid as oid FROM cinfo WHERE cid=".$cid.";";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);
	$nrow	= mssql_num_rows($res);

	if ($nrow > 0 and $cid!=0)
	{
		set_include_path(get_include_path() . PATH_SEPARATOR .'E:\www\htdocs\QB');
		include('bhsoap/QB_Support.php');
		
		$a	='CustomerAdd';
		$oid=$row['oid'];
		$pid=array($row['cid']);
		$sid=$_SESSION['securityid'];
		
		request_multi_process($pid,$a,$sid,$oid);
		
		return true;
	}
	else
	{
		return false;
	}
}

function quickbooks_jobcost_datastore()
{
	global $viewarray;
	
	$out=false;
	
	$tsrv=count($viewarray['jc_ar']['service']);
	$srvcnt=0;
	$srvqry='';
	
	$tmat=count($viewarray['jc_ar']['material']);
	$matcnt=0;
	$matqry='';
	
	$tinv=count($viewarray['jc_ar']['inventory']);
	$invcnt=0;
	$invqry='';
	
	$tbid=count($viewarray['jc_ar']['bids']);
	$bidcnt=0;
	$bidqry='';
	
	$tadj=count($viewarray['jc_ar']['adjusts']);
	$adjcnt=0;
	$adjqry='';
	
	$preqry='BEGIN TRANSACTION';
	$pstqry='COMMIT TRANSACTION';
	
	// Clear Previous Data
	$nrowS=mssql_num_rows(mssql_query("select iid from jest..JobCost_Service where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';"));
	$nrowM=mssql_num_rows(mssql_query("select iid from jest..JobCost_Material where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';"));
	$nrowI=mssql_num_rows(mssql_query("select iid from jest..JobCost_Inventory where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';"));
	$nrowB=mssql_num_rows(mssql_query("select iid from jest..JobCost_BidCost where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';"));
	$nrowA=mssql_num_rows(mssql_query("select iid from jest..JobCost_Adjusts where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';"));
	
	if ($nrowS > 0)
	{
		mssql_query("delete from jest..JobCost_Service where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';");
		//echo 'PreProcess Service Delete<br>';
	}
	
	if ($nrowM > 0)
	{
		mssql_query("delete from jest..JobCost_Material where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';");
		//echo 'PreProcess Inventory Delete<br>';
	}
	
	if ($nrowI > 0)
	{
		mssql_query("delete from jest..JobCost_Inventory where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';");
		//echo 'PreProcess Inventory Delete<br>';
	}
	
	if ($nrowB > 0)
	{
		mssql_query("delete from jest..JobCost_BidCost where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';");
		//echo 'PreProcess Inventory Delete<br>';
	}
	
	if ($nrowA > 0)
	{
		mssql_query("delete from jest..JobCost_Adjusts where oid=".$_SESSION['officeid']." and jobid='".trim($viewarray['jobid'])."';");
		//echo 'PreProcess Inventory Delete<br>';
	}
	
	//echo '<pre>';
	//print_r($viewarray['jc_ar']['service']);
	foreach ($viewarray['jc_ar']['service'] as $ns=>$vs)
	{
		$srvqry=$srvqry."
		INSERT INTO [jest]..[JobCost_Service] (
		[srvid], [oid], [jobid], [phsid],
		[ListID], [EditSequence], [code],
		[itemname], [itemattrib1], [itemattrib2],
		[unitprice], [totalprice], [tquantity]
		) VALUES (
		".$vs['srvid'].", ".$vs['oid'].", '".$vs['jobid']."', ".$vs['phsid'].",
		'".$vs['ListID']."', '".$vs['EditSequence']."', '".$vs['code']."',
		'".substr(trim($vs['itemname']),0,30)."', '".substr(trim($vs['itemattrib1']),0,30)."','".substr(trim($vs['itemattrib2']),0,30)."',
		cast('".$vs['unitprice']."' as money), cast('".$vs['totalprice']."' as money), ".$vs['tquantity'].");
		";
		$srvcnt++;
	}
	
	//print_r($viewarray['jc_ar']['material']);
	foreach ($viewarray['jc_ar']['material'] as $nm=>$vm)
	{
		$matqry=$matqry."
		INSERT INTO [jest]..[JobCost_Material] (
		[invid], [oid], [jobid], [phsid],
		[matid], [vpno], [ListID], [EditSequence], [code],
		[itemname], [itemattrib1], [itemattrib2],
		[unitprice], [totalprice], [tquantity]
		) VALUES (
		".$vm['invid'].", ".$vm['oid'].", '".$vm['jobid']."', ".$vm['phsid'].",
		".$vm['matid'].", '".$vm['vpno']."','".$vm['ListID']."', '".$vm['EditSequence']."', '".$vm['code']."',
		'".substr(trim($vm['itemname']),0,30)."', '".substr(trim($vm['itemattrib1']),0,30)."','".substr(trim($vm['itemattrib2']),0,30)."',
		cast('".$vm['unitprice']."' as money), cast('".$vm['totalprice']."' as money), ".$vm['tquantity'].");
		";
		$matcnt++;
	}
	
	foreach ($viewarray['jc_ar']['inventory'] as $ni=>$vi)
	{
		$invqry=$invqry."
		INSERT INTO [jest]..[JobCost_Inventory] (
		[invid], [oid], [jobid], [phsid],
		[matid], [vpno], [ListID], [EditSequence], [code],
		[itemname], [itemattrib1], [itemattrib2],
		[unitprice], [totalprice], [tquantity]
		) VALUES (
		".$vi['invid'].", ".$vi['oid'].", '".$vi['jobid']."', ".$vi['phsid'].",
		".$vi['matid'].", '".$vi['vpno']."','".$vi['ListID']."', '".$vi['EditSequence']."', '".$vi['code']."',
		'".substr(trim($vi['itemname']),0,30)."', '".substr(trim($vi['itemattrib1']),0,30)."','".substr(trim($vi['itemattrib2']),0,30)."',
		cast('".$vi['unitprice']."' as money), cast('".$vi['totalprice']."' as money), ".$vi['tquantity'].");
		";
		$invcnt++;
	}
	
	foreach ($viewarray['jc_ar']['bids'] as $nb=>$vb)
	{
		$bidqry=$bidqry."
		INSERT INTO [jest]..[JobCost_BidCost] (
		[bid], [oid], [jobid], [phsid],
		[matid], [vpno], [ListID], [EditSequence], [code],
		[itemname], [itemattrib1], [itemattrib2],
		[unitprice], [totalprice], [tquantity]
		) VALUES (
		".$vb['bid'].", ".$vb['oid'].", '".$vb['jobid']."', ".$vb['phsid'].",
		".$vb['matid'].", '".$vb['vpno']."','".$vb['ListID']."', '".$vb['EditSequence']."', '".$vb['code']."',
		'".substr(trim($vb['itemname']),0,30)."', '".substr(trim($vb['itemattrib1']),0,30)."','".substr(trim($vb['itemattrib2']),0,30)."',
		cast('".$vb['unitprice']."' as money), cast('".$vb['totalprice']."' as money), ".$vb['tquantity'].");
		";
		$bidcnt++;
	}
	
	foreach ($viewarray['jc_ar']['adjusts'] as $na=>$va)
	{
		$adjqry=$adjqry."
		INSERT INTO [jest]..[JobCost_Adjusts] (
		[aid], [oid], [jobid], [phsid],
		[matid], [vpno], [ListID], [EditSequence], [code],
		[itemname], [itemattrib1], [itemattrib2],
		[unitprice], [totalprice], [tquantity]
		) VALUES (
		".$va['aid'].", ".$va['oid'].", '".$va['jobid']."', ".$va['phsid'].",
		".$va['matid'].", '".$va['vpno']."','".$va['ListID']."', '".$va['EditSequence']."', '".$va['code']."',
		'".substr(trim($va['itemname']),0,30)."', '".substr(trim($va['itemattrib1']),0,30)."','".substr(trim($va['itemattrib2']),0,30)."',
		cast('".$va['unitprice']."' as money), cast('".$va['totalprice']."' as money), ".$va['tquantity'].");
		";
		$adjcnt++;
	}
	
	//echo 'Service Items:<br>'.$srvqry.'<br>';
	//echo 'Material Items:<br>'.$matqry.'<br>';
	//echo 'Inventory Items:<br>'.$invqry.'<br>';
	//echo 'Bid Items:<br>'.$bidqry.'<br>';
	//echo 'Adj Items:<br>'.$adjqry.'<br>';
	
	//echo $tsrv.':'.$srvcnt.'<br>';
	//echo $tmat.':'.$matcnt.'<br>';
	//echo $tinv.':'.$invcnt.'<br>';
	//echo $tbid.':'.$bidcnt.'<br>';
	//echo $tadj.':'.$adjcnt.'<br>';
	
	if ($tsrv==$srvcnt)
	{
		mssql_query($preqry.$srvqry.$pstqry);
		//echo $preqry.$srvqry.$pstqry;
	}
	
	if ($tmat==$matcnt)
	{
		mssql_query($preqry.$matqry.$pstqry);
		//echo $preqry.$matqry.$pstqry;
	}
	
	if ($tinv==$invcnt)
	{
		mssql_query($preqry.$invqry.$pstqry);
		//echo $preqry.$invqry.$pstqry;
	}
	
	if ($tbid==$bidcnt)
	{
		mssql_query($preqry.$bidqry.$pstqry);
		//echo $preqry.$invqry.$pstqry;
	}
	
	if ($tadj==$adjcnt)
	{
		mssql_query($preqry.$adjqry.$pstqry);
		//echo $preqry.$invqry.$pstqry;
	}
	
	//echo '</pre>';
	return true;
}

function post_create_add()
{
	if (empty($_REQUEST['jobid'])||$_REQUEST['jobid']=='')
	{
		echo "<font color=\"red\"><b>ERROR!</b></font>: No JobID! Please go Back and enter a Job ID.";
		exit;
	}
	elseif (empty($_REQUEST['jadd'])||$_REQUEST['jadd']=='')
	{
		echo "<font color=\"red\"><b>CRITICAL ERROR!</b></font>: No Addendum ID!";
		exit;
	}
	else
	{
		$qry1		= "SELECT jobid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."'";
		$res1		= mssql_query($qry1);
		$nrow1	= mssql_num_rows($res1);

		if ($nrow1 < 1)
		{
			echo "<font color=\"red\"><b>CRITICAL ERROR!</b></font>: Job does not exist!\n";
			exit;
		}
		else
		{
			//$qry2		= "INSERT INTO jobs ";
			//$qry2	  .= "(officeid,jobid,estid,custid,status,sid,securityid,sidm) ";
			//$qry2	  .= "VALUES ";
			//$qry2	  .= "('".$_SESSION['officeid']."','".$_REQUEST['jobid']."','".$_REQUEST['estid']."','".$_REQUEST['custid']."','2','0','".$_REQUEST['securityid']."','".$_REQUEST['sidm']."')";
			$qry2	  	= "UPDATE jobs SET jadd='".$_REQUEST['jadd']."',status='2' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
			$res2		= mssql_query($qry2);
			//$row2	   = mssql_fetch_row($res2);

			$qry3		= "UPDATE est SET status='2' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."'";
			$res3		= mssql_query($qry3);
			//$row3	   = mssql_fetch_row($res3);

			insert_add();
		}
	}
}

function insert_add()
{
	$qry0		= "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
	$res0		= mssql_query($qry0);
	$nrow0	= mssql_num_rows($res0);

	if ($nrow0!=1)
	{
		echo "Duplicate Estimates!";
		exit;
	}
	else
	{
		$row0	   = mssql_fetch_array($res0);

		$qry1		= "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res1		= mssql_query($qry1);
		$row1	   = mssql_fetch_array($res1);

		$qry2		= "SELECT * FROM est_discounts WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res2		= mssql_query($qry2);
		$row2	   = mssql_fetch_array($res2);

		$qry3		= "SELECT * FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res3		= mssql_query($qry3);
		$row3	   = mssql_fetch_array($res3);

		$qry4		= "SELECT price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$row0['pft']."';";
		$res4		= mssql_query($qry4);
		$row4	   = mssql_fetch_array($res4);

		//$qry4		= "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$qry4		= "UPDATE jobs SET jadd='".$_REQUEST['jadd']."',status='2' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res4		= mssql_query($qry4);

		$qry6		= "UPDATE est SET status='2' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
		$res6		= mssql_query($qry6);

		/*
		$qry5		= "INSERT INTO jdetail";
		$qry5	  .= "(officeid,";
		$qry5	  .= "jobid,";
		$qry5	  .= "jadd,";
		$qry5	  .= "pft,";
		$qry5	  .= "sqft,";
		$qry5	  .= "shal,";
		$qry5	  .= "mid,";
		$qry5	  .= "deep,";
		$qry5	  .= "spa_pft,";
		$qry5	  .= "spa_sqft,";
		$qry5	  .= "spa_type,";
		$qry5	  .= "deck,";
		$qry5	  .= "erun,";
		$qry5	  .= "prun,";
		$qry5	  .= "tzone,";
		$qry5	  .= "contractamt,";
		$qry5	  .= "contractdate,";
		$qry5	  .= "refto,";
		$qry5	  .= "refamt) ";
		$qry5	  .= "VALUES ";
		$qry5	  .= "('".$_SESSION['officeid']."',";
		$qry5	  .= "'".$_REQUEST['jobid']."',";
		$qry5	  .= "'".$_REQUEST['jadd']."',";
		$qry5	  .= "'".$row0['pft']."',";
		$qry5	  .= "'".$row0['sqft']."',";
		$qry5	  .= "'".$row0['shal']."',";
		$qry5	  .= "'".$row0['mid']."',";
		$qry5	  .= "'".$row0['deep']."',";
		$qry5	  .= "'".$row0['spa_pft']."',";
		$qry5	  .= "'".$row0['spa_sqft']."',";
		$qry5	  .= "'".$row0['spatype']."',";
		$qry5	  .= "'".$row0['deck1']."',";
		$qry5	  .= "'".$row0['erun']."',";
		$qry5	  .= "'".$row0['prun']."',";
		$qry5	  .= "'".$row0['tzone']."',";
		$qry5	  .= "'".$_REQUEST['camt']."',";
		$qry5	  .= "'".$_REQUEST['cdate']."',";
		$qry5	  .= "'".$row0['refto']."',";
		$qry5	  .= "'".$row0['refamt']."');";
		$res5		= mssql_query($qry5);
		*/

		$qry5		= "INSERT INTO jdetail";
		$qry5	  .= "(officeid,";
		$qry5	  .= "jobid,";
		$qry5	  .= "jadd,";
		$qry5	  .= "pft,";
		$qry5	  .= "sqft,";
		$qry5	  .= "shal,";
		$qry5	  .= "mid,";
		$qry5	  .= "deep,";
		$qry5	  .= "spa_pft,";
		$qry5	  .= "spa_sqft,";
		$qry5	  .= "spa_type,";
		$qry5	  .= "deck,";
		$qry5	  .= "erun,";
		$qry5	  .= "prun,";
		$qry5	  .= "tzone,";
		$qry5	  .= "bcomm,";
		$qry5	  .= "ouadj,";
		$qry5	  .= "estdata,";
		$qry5	  .= "bpprice,";
		$qry5	  .= "bpcomm,";
		$qry5	  .= "contractamt,";
		$qry5	  .= "contractdate,";
		$qry5	  .= "refto,";
		$qry5	  .= "refamt) ";
		$qry5	  .= "VALUES ";
		$qry5	  .= "('".$_SESSION['officeid']."',";
		$qry5	  .= "'".$_REQUEST['jobid']."',";
		$qry5	  .= "'".$_REQUEST['jadd']."',";
		$qry5	  .= "'".$row0['pft']."',";
		$qry5	  .= "'".$row0['sqft']."',";
		$qry5	  .= "'".$row0['shal']."',";
		$qry5	  .= "'".$row0['mid']."',";
		$qry5	  .= "'".$row0['deep']."',";
		$qry5	  .= "'".$row0['spa_pft']."',";
		$qry5	  .= "'".$row0['spa_sqft']."',";
		$qry5	  .= "'".$row0['spatype']."',";
		$qry5	  .= "'".$row0['deck1']."',";
		$qry5	  .= "'".$row0['erun']."',";
		$qry5	  .= "'".$row0['prun']."',";
		$qry5	  .= "'".$row0['tzone']."',";
		$qry5	  .= "'".$row0['bcomm']."',";
		$qry5	  .= "'".$row0['comadj']."',";
		$qry5	  .= "'".$row1['estdata']."',";
		$qry5	  .= "'".$row4['price']."',";
		$qry5	  .= "'".$row4['comm']."',";
		$qry5	  .= "'".$_REQUEST['camt']."',";
		$qry5	  .= "'".$_REQUEST['cdate']."',";
		$qry5	  .= "'".$row0['refto']."',";
		$qry5	  .= "'".$row0['refamt']."');";
		$res5		= mssql_query($qry5);

		//echo $qry5;
		//show_array_vars($row5);

		//Build Accessory List
		//list_jobs();
		//echo $row4['estdata'];
		//store_job_items($row1['estdata_add'],$_REQUEST['estid'],$_REQUEST['jobid'],$_REQUEST['jadd'],$row0['pft'],$row0['sqft'],$row0['tzone'],$row0['shal'],$row0['mid'],$row0['deep'],$row0['spa_pft'],$row0['spa_sqft'],$row0['spatype'],$row0['deck1']);
		//store_dis_items($_REQUEST['estid'],$_REQUEST['jobid'],$_REQUEST['jadd']);
		//store_bid_items($row1['estdata'],$_REQUEST['estid'],$_REQUEST['jobid'],$_REQUEST['jadd'],$row0['pft'],$row0['sqft'],$row0['tzone'],$row0['shal'],$row0['mid'],$row0['deep'],$row0['spa_pft'],$row0['spa_sqft'],$row0['spatype'],$row0['deck1']);

		store_packages($_REQUEST['jobid'],$_REQUEST['jadd'],$row1['estdata']);
		store_dis_items($_REQUEST['estid'],$_REQUEST['jobid'],$_REQUEST['jadd']);
		store_bid_items($_REQUEST['estid'],$_REQUEST['jobid'],$_REQUEST['jadd']);
		view_job_retail();
	}
}

function store_royalty($jobid,$jadd)
{
	$qry0		= "SELECT contractamt FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jadd."';";
	$res0		= mssql_query($qry0);
	$row0	   = mssql_fetch_row($res0);

	$troyal=$row0[0]*.03;

	$qry1	 = "INSERT INTO jlcostitems ";
	$qry1	.= "(officeid,jobid,jadd,dbid,rdbid,phsid,qtype,mtype,item,atrib1,atrib2,atrib3,bp,tbp,quantity,lrange,hrange,calcval,code,seqn) ";
	$qry1	.= "VALUES ";
	$qry1	.= "('".$_SESSION['officeid']."','$jobid','$jadd','999999','0','5','1','1','Royalty','atrib1','atrib2','atrib3','$troyal','$troyal','1','0','0','0','0','0');";
	$res1	 = mssql_query($qry1);
}

function store_job_items($estdata,$estid,$jobid,$jadd,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck)
{
	$MAS=$_SESSION['pb_code'];
	$qry		= "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$pft."';";
	$res		= mssql_query($qry);
	$row	   = mssql_fetch_row($res);

	$qrya		= "INSERT INTO jretailitems ";
	$qrya	  .= "(officeid,jobid,jadd,dbid,phsid,catid,qtype,mtype,catname,item,atrib1,atrib2,atrib3,rp,trp,comm,tcomm,commtype,quantity,lrange,hrange,calcval,code,seqn) ";
	$qrya	  .= "VALUES ";
	$qrya	  .= "('".$_SESSION['officeid']."','$jobid','$jadd','$row[0]','0','19','1','4','General','Base Pool','','','','$row[2]','$row[2]','$row[3]','$row[3]','1','$pft','0','0','0','0','1');";
	$resa		= mssql_query($qrya);

	$rseq=2;
	$cseq=1;
	$edata=explode(",",$estdata);

	// Stores Retail Elements
	foreach ($edata as $n1 => $v1)
	{
		$subedata=explode(":",$v1);
		$qry0		= "SELECT qtype,catid FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$subedata[0]."';";
		$res0		= mssql_query($qry0);
		$row0	   = mssql_fetch_row($res0);

		if ($row0[0]!=32)
		{
			$items=retail_item_calc($subedata[0],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

			$qry1		= "INSERT INTO jretailitems ";
			$qry1	  .= "(officeid,jobid,jadd,dbid,phsid,catid,qtype,mtype,catname,item,atrib1,atrib2,atrib3,rp,trp,comm,tcomm,commtype,quantity,lrange,hrange,calcval,code,seqn) ";
			$qry1	  .= "VALUES ";
			$qry1	  .= "('".$_SESSION['officeid']."','$jobid','$jadd','".$subedata[0]."','0','$items[8]','$items[9]','$items[4]','catname','$items[6]','$items[7]','atrib2','atrib3','$items[12]','$items[0]','$items[1]','$items[11]','0','$items[2]','$items[13]','$items[14]','$items[15]','$items[5]','$rseq');";
			$res1		= mssql_query($qry1);

			if ($row0[0]==55||$row0[0]==72)
			{
				store_retail_package_items($subedata[0],$subedata[2],$subedata[4],$jobid,$jadd,$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck,$items[6],$rseq);
			}
			elseif ($row0[0]==33)
			{
				$qry2		= "SELECT * FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."' AND bidaccid='".$subedata[0]."';";
				$res2		= mssql_query($qry2);
				$row2	   = mssql_fetch_row($res2);

				$qry3		= "INSERT INTO jbids ";
				$qry3	  .= "(officeid,jobid,jadd,dbid,catid,catname,bidinfo,bidamt) ";
				$qry3	  .= "VALUES ";
				$qry3	  .= "('".$_SESSION['officeid']."','$jobid','0','".$subedata[0]."','$row0[1]','0','$row2[3]','$subedata[3]');";
				$res3		= mssql_query($qry3);
				//echo "RETAIL: ".$qry3."<br>";
			}
			$rseq++;
		}
	}

	store_royalty($jobid,$jadd);

	// Stores Some Base Labor Cost Elements - will be removed upon Base inclusion rewrite.
	store_base_labor_job_items($jobid,$jadd,$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

	// Stores Labor Cost Elements
	foreach ($edata as $n1 => $v1)
	{
		$subedata=explode(":",$v1);
		$qry0		= "SELECT qtype,catid FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$subedata[0]."';";
		$res0		= mssql_query($qry0);
		$row0	   = mssql_fetch_row($res0);

		if ($row0[0]!=32)
		{
			if ($row0[0]==55||$row0[0]==72)
			{
				$qry1a	= "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$subedata[0]."';";
				$res1a	= mssql_query($qry1a);
				$nrow1a	= mssql_num_rows($res1a);

				if ($nrow1a > 0)
				{
					while ($row1a = mssql_fetch_array($res1a))
					{
						$qry1		= "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row1a['iid']."';";
						$res1		= mssql_query($qry1);
						$nrow1	= mssql_num_rows($res1);

						if ($nrow1 > 0)
						{
							while ($row1 = mssql_fetch_row($res1))
							{
								$qry1b	= "SELECT qtype,rinvid FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1[1]."';";
								$res1b	= mssql_query($qry1b);
								$row1b	= mssql_fetch_row($res1b);

								if ($row1b[1]!=0)
								{
									if ($row1a['adjtype']==4)
									{
										$citems	=cost_item_calc($row1[1],$row1a['adjquan'],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);
										$adjamt	=$citems[0]*-1;
										$adjquan	=package_quan_set($row1b[0],$citems[2],$row1a['adjquan'],$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);
									}
									else
									{
										$citems	=cost_item_calc($row1[1],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);
										$adjamt	=$citems[12]*-1;
										$adjquan	=$citems[2];
									}

									$citems=cost_item_calc($row1b[1],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

									$qry2	 = "INSERT INTO jlcostitems ";
									$qry2	.= "(officeid,jobid,jadd,dbid,rdbid,phsid,qtype,mtype,item,atrib1,atrib2,atrib3,bp,tbp,quantity,lrange,hrange,calcval,code,seqn) ";
									$qry2	.= "VALUES ";
									$qry2	.= "('".$_SESSION['officeid']."','$jobid','$jadd','".$row1[1]."','".$subedata[0]."','$citems[8]','$citems[9]','$citems[4]','$citems[6]','$citems[7]','atrib2','atrib3','$citems[12]','$adjamt','$adjquan','$citems[13]','$citems[14]','$citems[15]','$citems[5]','$cseq');";
									$res2		= mssql_query($qry2);
									$cseq++;
								}

								if ($row1a['adjtype']==4)
								{
									$citems	=cost_item_calc($row1[1],$row1a['adjquan'],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);
									$adjamt	=$citems[0];
									$adjquan	=package_quan_set($row1b[0],$citems[2],$row1a['adjquan'],$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);
								}
								else
								{
									$citems	=cost_item_calc($row1[1],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);
									$adjamt	=$citems[12];
									$adjquan	=$citems[2];
								}

								$qry2	 = "INSERT INTO jlcostitems ";
								$qry2	.= "(officeid,jobid,jadd,dbid,rdbid,phsid,qtype,mtype,item,atrib1,atrib2,atrib3,bp,tbp,quantity,lrange,hrange,calcval,code,seqn) ";
								$qry2	.= "VALUES ";
								$qry2	.= "('".$_SESSION['officeid']."','$jobid','$jadd','".$row1[1]."','".$subedata[0]."','$citems[8]','$citems[9]','$citems[4]','$citems[6]','$citems[7]','atrib2','atrib3','$citems[12]','$adjamt','$adjquan','$citems[13]','$citems[14]','$citems[15]','$citems[5]','$cseq');";
								$res2		= mssql_query($qry2);
								$cseq++;
							}
						}
					}
				}
			}
			else
			{
				$qry1		= "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$subedata[0]."';";
				$res1		= mssql_query($qry1);
				$nrow1	= mssql_num_rows($res1);

				if ($nrow1 > 0)
				{
					while ($row1 = mssql_fetch_row($res1))
					{
						$qry1b	= "SELECT qtype,rinvid FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row1[1]."';";
						$res1b	= mssql_query($qry1b);
						$row1b	= mssql_fetch_row($res1b);

						if ($row1b[1]!=0)
						{
							$citems=cost_item_calc($row1b[1],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

							$cramt=$citems[0] * -1;

							$qry2	 = "INSERT INTO jlcostitems ";
							$qry2	.= "(officeid,jobid,jadd,dbid,rdbid,phsid,qtype,mtype,item,atrib1,atrib2,atrib3,bp,tbp,quantity,lrange,hrange,calcval,code,seqn) ";
							$qry2	.= "VALUES ";
							$qry2	.= "('".$_SESSION['officeid']."','$jobid','$jadd','".$row1b[1]."','".$subedata[0]."','$citems[8]','$citems[9]','$citems[4]','$citems[6] (Credit)','$citems[7]','atrib2','atrib3','$citems[12]','$cramt','$citems[2]','$citems[13]','$citems[14]','$citems[15]','$citems[5]','$cseq');";
							$res2		= mssql_query($qry2);
							$cseq++;
						}

						$citems=cost_item_calc($row1[1],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

						$qry2	 = "INSERT INTO jlcostitems ";
						$qry2	.= "(officeid,jobid,jadd,dbid,rdbid,phsid,qtype,mtype,item,atrib1,atrib2,atrib3,bp,tbp,quantity,lrange,hrange,calcval,code,seqn) ";
						$qry2	.= "VALUES ";
						$qry2	.= "('".$_SESSION['officeid']."','$jobid','$jadd','".$row1[1]."','".$subedata[0]."','$citems[8]','$citems[9]','$citems[4]','$citems[6]','$citems[7]','atrib2','atrib3','$citems[12]','$citems[0]','$citems[2]','$citems[13]','$citems[14]','$citems[15]','$citems[5]','$cseq');";
						$res2		= mssql_query($qry2);
						$cseq++;
					}
				}
			}
		}
	}

	// Stores Some Base Material Cost Elements - will be removed upon Base inclusion rewrite.
	store_base_mat_job_items($jobid,$jadd,$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

	// Stores Material Cost Elements
	foreach ($edata as $n1 => $v1)
	{
		$subedata=explode(":",$v1);
		$qry0		= "SELECT qtype,catid FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$subedata[0]."';";
		$res0		= mssql_query($qry0);
		$row0	   = mssql_fetch_row($res0);

		if ($row0[0]!=32)
		{
			if ($row0[0]==55||$row0[0]==72)
			{
				$qry1a	= "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$subedata[0]."';";
				$res1a	= mssql_query($qry1a);
				$nrow1a	= mssql_num_rows($res1a);

				if ($nrow1a > 0)
				{
					while ($row1a = mssql_fetch_array($res1a))
					{
						$qry1		= "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row1a['iid']."';";
						$res1		= mssql_query($qry1);
						$nrow1	= mssql_num_rows($res1);

						if ($nrow1 > 0)
						{
							while ($row1 = mssql_fetch_row($res1))
							{
								$qry1b	= "SELECT qtype,rinvid FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row1[1]."';";
								$res1b	= mssql_query($qry1b);
								$row1b	= mssql_fetch_row($res1b);

								if ($row1b[1]!=0)
								{
									$citems=cost_mat_calc($row1b[1],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

									$cramt=$citems[0] * -1;

									$qry2	 = "INSERT INTO jmcostitems ";
									$qry2	.= "(officeid,jobid,jadd,dbid,rdbid,phsid,qtype,mtype,item,atrib1,atrib2,atrib3,bp,tbp,quantity,lrange,hrange,calcval,code,seqn) ";
									$qry2	.= "VALUES ";
									$qry2	.= "('".$_SESSION['officeid']."','$jobid','$jadd','".$row1b[1]."','".$subedata[0]."','$citems[8]','$citems[9]','$citems[4]','$citems[6] (Credit)','$citems[7]','atrib2','atrib3','$citems[12]','$cramt','$citems[2]','$citems[13]','$citems[14]','$citems[15]','$citems[5]','$cseq');";
									$res2		= mssql_query($qry2);
									$cseq++;
								}

								$citems=cost_mat_calc($row1[1],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

								$qry2	 = "INSERT INTO jmcostitems ";
								$qry2	.= "(officeid,jobid,jadd,dbid,rdbid,phsid,qtype,mtype,item,atrib1,atrib2,atrib3,bp,tbp,quantity,lrange,hrange,calcval,code,seqn) ";
								$qry2	.= "VALUES ";
								$qry2	.= "('".$_SESSION['officeid']."','$jobid','$jadd','".$row1[1]."','".$subedata[0]."','$citems[8]','$citems[9]','$citems[4]','$citems[6]','$citems[7]','atrib2','atrib3','$citems[12]','$citems[0]','$citems[2]','$citems[13]','$citems[14]','$citems[15]','$citems[5]','$cseq');";
								$res2		= mssql_query($qry2);
								$cseq++;
							}
						}
					}
				}
			}
			else
			{
				$qry1		= "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$subedata[0]."';";
				$res1		= mssql_query($qry1);
				$nrow1	= mssql_num_rows($res1);

				if ($nrow1 > 0)
				{
					while ($row1 = mssql_fetch_row($res1))
					{
						$qry1b	= "SELECT qtype,rinvid FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row1[1]."';";
						$res1b	= mssql_query($qry1b);
						$row1b	= mssql_fetch_row($res1b);

						if ($row1b[1]!=0)
						{
							$citems=cost_mat_calc($row1b[1],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

							$cramt=$citems[0] * -1;

							$qry2	 = "INSERT INTO jmcostitems ";
							$qry2	.= "(officeid,jobid,jadd,dbid,rdbid,phsid,qtype,mtype,item,atrib1,atrib2,atrib3,bp,tbp,quantity,lrange,hrange,calcval,code,seqn) ";
							$qry2	.= "VALUES ";
							$qry2	.= "('".$_SESSION['officeid']."','$jobid','$jadd','".$row1b[1]."','".$subedata[0]."','$citems[8]','$citems[9]','$citems[4]','$citems[6] (Credit)','$citems[7]','atrib2','atrib3','$citems[12]','$cramt','$citems[2]','$citems[13]','$citems[14]','$citems[15]','$citems[5]','$cseq');";
							$res2		= mssql_query($qry2);
							$cseq++;
						}

						$citems=cost_mat_calc($row1[1],$subedata[2],$subedata[4],$estid,$pft,$sqft,$tzone,$shal,$mid,$deep,$spa_pft,$spa_sqft,$spatype,$deck);

						$qry2	 = "INSERT INTO jmcostitems ";
						$qry2	.= "(officeid,jobid,jadd,dbid,rdbid,phsid,qtype,mtype,item,atrib1,atrib2,atrib3,bp,tbp,quantity,lrange,hrange,calcval,code,seqn) ";
						$qry2	.= "VALUES ";
						$qry2	.= "('".$_SESSION['officeid']."','$jobid','$jadd','".$row1[1]."','".$subedata[0]."','$citems[8]','$citems[9]','$citems[4]','$citems[6]','$citems[7]','atrib2','atrib3','$citems[12]','$citems[0]','$citems[2]','$citems[13]','$citems[14]','$citems[15]','$citems[5]','$cseq');";
						$res2		= mssql_query($qry2);
						$cseq++;
					}
				}
			}
		}
	}
}

function store_bid_items($estid,$jobid,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	//echo " (Internal) ";
	$qry	= "SELECT * FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
	$res	= mssql_query($qry);
	$nrow = mssql_num_rows($res);

	if ($nrow > 0)
	{
		$qryA	= "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
		$resA	= mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);

		//echo $rowA['estdata']."<br>";

		while ($row	= mssql_fetch_array($res))
		{
			$edata=explode(",",$rowA['estdata']);
			foreach ($edata as $en => $ev)
			{
				$idata=explode(":",$ev);
				if ($idata[0]==$row['bidaccid'])
				{
					// Stores Retail Bids
					$qry1		= "INSERT INTO jbids ";
					$qry1	  .= "(officeid,jobid,jadd,bidinfo,bidamt,dbid) ";
					$qry1	  .= "VALUES ";
					$qry1	  .= "('".$_SESSION['officeid']."','".$jobid."','".$jadd."','".replacequote($row['bidinfo'])."','".$idata[3]."','".$idata[0]."');";
					$res1		= mssql_query($qry1);
				}
			}
		}
	
		$qry2	= "SELECT * FROM bid_breakout WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
		$res2	= mssql_query($qry2);
		$nrow2= mssql_num_rows($res2);
	
		if ($nrow2 > 0)
		{
			while ($row2 = mssql_fetch_array($res2))
			{
				// Stores Bid Cost Breakouts, if any
				$qry3		= "INSERT INTO jbids_breakout ";
				$qry3	  .= "(officeid,estid,jobid,jadd,phsid,smisc,sdesc,rdbid,cdbid,bprice,vendor,partno,uid) ";
				$qry3	  .= "VALUES ";
				$qry3	  .= "('".$_SESSION['officeid']."','".$estid."','".$jobid."','".$jadd."','".$row2['phsid']."','".replacequote($row2['smisc'])."','".replacequote($row2['sdesc'])."','".$row2['rdbid']."','".$row2['cdbid']."','".$row2['bprice']."','".replacequote($row2['vendor'])."','".replacequote($row2['partno'])."','".replacequote($row2['uid'])."');";
				$res3		= mssql_query($qry3);
			}
		}
	
		if (isset($viewarray['enqb']) and $viewarray['enqb']==1)
		{
			if ($MAS=='0')
			{
				$pb_code='';
			}
			else
			{
				$pb_code=$MAS;
			}
			
			$qry3	= "
						SELECT
							J1.*
							,(select item from [".$pb_code."acc] where officeid=J1.officeid and id=J1.rdbid) AS idesc
							,(select top 1 ListID from [".$pb_code."accpbook] where officeid=J1.officeid and phsid=J1.phsid) AS iListID
							,(select top 1 EditSequence from [".$pb_code."accpbook] where officeid=J1.officeid and phsid=J1.phsid) AS iEditSequence
						FROM
							jbids_breakout AS J1
						WHERE
							J1.officeid='".$_SESSION['officeid']."'
							AND J1.jobid='".$jobid."'
						;";
			$res3	= mssql_query($qry3);
			$nrow3  = mssql_num_rows($res3);
		
			if ($nrow3 > 0)
			{
				while ($row3 = mssql_fetch_array($res3))
				{
					// Stores Bid Cost Breakouts, if any
					if ((isset($row3['iListID']) and $row3['iListID']!='0') and (isset($row3['iEditSequence']) and $row3['iEditSequence']!='0'))
					{
						$viewarray['jc_ar']['bids'][]=array(
													   'bid'=>$row3['id'],
													   'oid'=>$row3['officeid'],
													   'jobid'=>$row3['jobid'],
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
						$viewarray['jc_ar']['bids_errors'][]=array(
													   'bid'=>$row3['id'],
													   'oid'=>$row3['officeid'],
													   'jobid'=>$row3['jobid'],
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
}

function store_mpa_items($estid,$jobid,$jadd)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$qry	= "SELECT * FROM man_phs_adj WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
	$res	= mssql_query($qry);
	$nrow = mssql_num_rows($res);

	if ($nrow > 0)
	{
		while ($row	= mssql_fetch_array($res))
		{	
			$qryB	= "UPDATE man_phs_adj SET jobid='".$jobid."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
			$resB	= mssql_query($qryB);
		}
	}
	
	if (isset($viewarray['enqb']) and $viewarray['enqb']==1)
	{
		$qry3	= "SELECT M.* FROM man_phs_adj AS M WHERE M.officeid=".$_SESSION['officeid']." AND M.jobid='".$jobid."' and jadd=".$jadd.";";
		$res3	= mssql_query($qry3);
		$nrow3  = mssql_num_rows($res3);
	
		if ($nrow3 > 0)
		{
			while ($row3 = mssql_fetch_array($res3))
			{
				// Stores Bid Cost Breakouts, if any
				$viewarray['jc_ar']['adjusts'][]=array(
											   'srvid'=>$row3['id'],
											   'oid'=>$row3['officeid'],
											   'jobid'=>$row3['jobid'],
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
}

function store_dis_items($estid,$jobid,$jadd)
{
	$qry	= "SELECT * FROM est_discounts WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
	$res	= mssql_query($qry);
	$nrow	= mssql_num_rows($res);

	if ($nrow > 0)
	{
		while ($row	= mssql_fetch_array($res))
		{
			$qry1		= "INSERT INTO jdiscounts ";
			$qry1	  .= "(officeid,jobid,jadd,disc_amt,disc_desc) ";
			$qry1	  .= "VALUES ";
			$qry1	  .= "('".$_SESSION['officeid']."','".$jobid."','".$jadd."','".$row['discount']."','".replacequote($row['descrip'])."');";
			$res1		= mssql_query($qry1);
		}
	}
}

function store_com_items($estid,$jobid,$jadd,$estsecid,$sidm,$trandate,$cid)
{
	//$err=0;
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	
	if (isset($_REQUEST['csched']))
	{		
		if ($jadd==0)
		{
			$qry	= "SELECT csid,oid,estid,jobid,jadd,uid FROM CommissionSchedule WHERE oid=".(int) $_SESSION['officeid']." AND (jobid='".(string) $jobid."' OR estid=".(int) $estid.");";
		}
		else
		{
			$qry	= "SELECT csid,oid,estid,jobid,jadd,uid FROM CommissionSchedule WHERE oid=".(int) $_SESSION['officeid']." AND (jobid='".(string) $jobid."' OR estid=".(int) $estid.") and jadd=".$jadd.";";
		}
		
		$res	= mssql_query($qry);
		$nrow	= mssql_num_rows($res);
		
		//echo 'QRY: '.$qry.'<br>';
		
		if ($nrow > 0)
		{
			if ($jadd==0)
			{
				$qry1	= "DELETE FROM CommissionSchedule WHERE oid=".(int) $_SESSION['officeid']." AND (jobid='".(string) $jobid."' OR estid=".(int) $estid.");";
			}
			else
			{
				$qry1	= "DELETE FROM CommissionSchedule WHERE oid=".(int) $_SESSION['officeid']." AND (jobid='".(string) $jobid."' OR estid=".(int) $estid.") and jadd=".$jadd.";";
			}
			
			$res1	= mssql_query($qry1);
			
			//echo $nrow.' Commissions Found & Removed<br>';
		}

		foreach ($_REQUEST['csched'] as $n => $v)
		{
			/*
			if ($_SESSION['tester']!=0) {
				echo '<pre>';
				print_r($v);
				echo '</pre>';
			}
			*/
			
			$qry2	= "INSERT INTO CommissionSchedule ";
			$qry2  .= "(oid,estid,jobid,jadd,type,rate,amt,secid,uid,cbtype";
			
			if (isset($v['label']) && strlen($v['label']) >= 3)
			{
				$qry2  .= ",label";
			}
			
			if (isset($v['notes']) && strlen($v['notes']) >= 3)
			{
				$qry2  .= ",notes";
			}
			
			$qry2  .= ") ";
			$qry2  .= "VALUES ";
			$qry2  .= "('".$_SESSION['officeid']."',";
			$qry2  .= "'".$estid."',";
			$qry2  .= "'".$jobid."',";
			$qry2  .= "'".$jadd."',";
			$qry2  .= "".$v['ctype'].",";
			$qry2  .= "convert(float,'".$v['rwdrate']."'),";
			$qry2  .= "convert(money,'".$v['rwdamt']."'),";
			
			if ($v['catid']==4)
			{
				$qry2  .= "".$sidm.",";
			}
			else
			{
				$qry2  .= "".$estsecid.",";
			}
			
			$qry2  .= "'".$v['uid']."',";
			$qry2  .= "".$v['catid']."";
			
			if (isset($v['label']) && strlen($v['label']) >= 3)
			{
				$qry2  .= ",'".replacequote($v['label'])."'";
			}
			
			if (isset($v['notes']) && strlen($v['notes']) >= 3)
			{
				$qry2  .= ",'".replacequote($v['notes'])."'";
			}
			
			$qry2  .= ");";
			$res2	= mssql_query($qry2);
			
			if ($jadd!=0 && $v['catid']==3) // CommissionHistory Insert Addn Commission
			{
				$qry2a  = "INSERT INTO CommissionHistory (";
				$qry2a .= "drid,";
				$qry2a .= "oid,";
				$qry2a .= "njobid,";
				$qry2a .= "jobid,";
				$qry2a .= "jadd,";
				$qry2a .= "secid,";
				$qry2a .= "amt,";
				$qry2a .= "trandate,";
				$qry2a .= "descrip,";
				$qry2a .= "cid,";
				$qry2a .= "cbtype,";
				$qry2a .= "rate,";
				$qry2a .= "ratetype,";
				$qry2a .= "htype,";
				$qry2a .= "uid) VALUES ";
				$qry2a .= "(0,".$_SESSION['officeid'].",'0','".$jobid."',".$jadd.",".$estsecid.",convert(money,'".$v['rwdamt']."'),";
				$qry2a .= "'".$trandate."','".replacequote($v['label'])."',".$cid.",".$v['catid'].",".$v['rwdrate'].",";
				$qry2a .= "".$v['ctype'].",'N',".$_SESSION['securityid'].");";
				$res2a  = mssql_query($qry2a);
				
				//echo $qry2a.'<br>';
				
				$qry2b	= "update jdetail set raddncm_man='".$v['rwdamt']."' WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' and jadd=".$jadd.";";
				$res2b  = mssql_query($qry2b);
				
				//echo $qry2b.'<br>';
			}
			
			if ($jadd==0)
			{
				$qry3	= "UPDATE jobs SET applyov=1 WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."'";
				$res3	= mssql_query($qry3);
			}
		}
	}
	
	//return $err;
}

function updateMA()
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	$qry0 = "select MAX(jadd) as mjadd from jest..jdetail where officeid=".$_SESSION['officeid']." and jobid='".$_REQUEST['jobid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	if ($_SESSION['securityid']==26)
	{
		echo $_REQUEST;
	}
	
	if ($row0['mjadd']==0)
	{
		$qry1 = "update jest..CommissionSchedule set amt=convert(money,'".$_REQUEST['amt']."') where csid='".$_REQUEST['csid']."';";
		$res1 = mssql_query($qry1);
	}

	view_job_retail();
}

function build_addendum_start()
{
	global $viewarray,$bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$estidret,$taxrate;
	$MAS=$_SESSION['pb_code'];

	//echo $_REQUEST['estid']." EST<br>";
	//echo $_REQUEST['jobid']." JOB<br>";
	//echo $_REQUEST['jadd']." JOB<br>";

	if (!isset($_REQUEST['estid'])||$_REQUEST['estid']==''||$_REQUEST['estid']==0)
	{
		echo "Fatal Error: Estimate ID Error!";
		exit;
	}

	if (!isset($_REQUEST['jobid'])||$_REQUEST['jobid']=='')
	{
		echo "Fatal Error: Contract ID Error!";
		exit;
	}

	if (!isset($_REQUEST['jadd'])||$_REQUEST['jadd']=='')
	{
		echo "Fatal Error: Addn ID Error!";
		exit;
	}

	if (!isset($_REQUEST['add_type'])||$_REQUEST['add_type']=='0')
	{
		$add_type="Customer";
		$add_typeint=0;
	}
	else
	{
		$add_type="GM";
		$add_typeint=1;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreB = "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$qrypreC = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
	$respreC = mssql_query($qrypreC);
	$rowpreC = mssql_fetch_array($respreC);
	
	$qrypreCa = "SELECT estid,added FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$rowpreC['estid']."';";
	$respreCa = mssql_query($qrypreCa);
	$rowpreCa = mssql_fetch_array($respreCa);

	if ($rowpreC['applyov']!=1)
	{
		echo "<font color=\"red\"><b>Error!</b></font><br>Commision Adjustment not Applied.<br> Click the BACK button and Apply a Commission Adjustment";
		exit;
	}

	// Builds a list of exisiting categories in the retail accessory table by office
	$qrypreE  = "SELECT DISTINCT a.catid,a.seqn ";
	$qrypreE .= "FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
	$qrypreE .= "ON a.catid=b.catid ";
	$qrypreE .= "AND a.officeid='".$_SESSION['officeid']."' ";
	$qrypreE .= "AND a.active=1 ";
	$qrypreE .= "ORDER BY a.seqn ASC;";
	$respreE = mssql_query($qrypreE);

	$type=1; // Est=0 Job=1

	//echo $numrowpreB." Adds<br>";
	$addcnt=$_REQUEST['jadd']+1;

	while ($rowpreE = mssql_fetch_row($respreE))
	{
		$catarray[]=$rowpreE[0];
	}

	$viewarray=array(
	'ps1'=>$rowpreA['pft'],
	'ps2'=>$rowpreA['sqft'],
	'spa1'=>$rowpreA['spa_type'],
	'spa2'=>$rowpreA['spa_pft'],
	'spa3'=>$rowpreA['spa_sqft'],
	'tzone'=>$rowpreA['tzone'],
	'camt'=>$rowpreA['contractamt'],
	'status'=>$rowpreC['status'],
	'ps5'=>$rowpreA['shal'],
	'ps6'=>$rowpreA['mid'],
	'ps7'=>$rowpreA['deep'],
	'estsecid'=>$rowpreC['securityid'],
	'jobsecid'=>$rowpreC['securityid'],
	'deck'=>$rowpreA['deck'],
	'erun'=>$rowpreA['erun'],
	'prun'=>$rowpreA['prun'],
	'jobid'=>$rowpreA['jobid'],
	'refto'=>$rowpreA['refto'],
	'ps1a'=>$rowpreA['apft']
	//'eadded'=>strtotime($rowpreCa['added'])
	);

	if ($rowpreB['pft_sqft']=="p")
	{
		$defmeas=$viewarray['ps1'];
	}
	else
	{
		$defmeas=$viewarray['ps2'];
	}

	$qryA = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryB1 = "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
	$resB1 = mssql_query($qryB1);
	$rowB1 = mssql_fetch_array($resB1);

	$qryC = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	$qryD = "SELECT securityid,fname,lname,newcommdate FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
	$resF = mssql_query($qryF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT cid,cfname,clname,chome,scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
	}
	
	$qryK = "SELECT cmid FROM CommissionBuilder WHERE oid=".$_SESSION['officeid'].";";
	$resK = mssql_query($qryK);
	$nrowK= mssql_num_rows($resK);

	$adate=date("m/d/Y");
	$estidret   =$rowpreC['estid'];
	$vdiscnt    =0;
	$pbaseprice =$rowB['price'];
	$bcomm      =$rowB['comm'];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	
	//display_array($viewarray);

	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "<form method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"save_add\">\n";
	echo "<input type=\"hidden\" name=\"jobid\" value=\"".$_REQUEST['jobid']."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"".$addcnt."\">\n";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"secid\" value=\"".$viewarray['jobsecid']."\">\n";
	echo "<input type=\"hidden\" name=\"add_type\" value=\"".$add_typeint."\">\n";
	echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table class=\"transnb\" align=\"center\" width=\"900\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td colspan=\"5\" valign=\"top\" align=\"left\">\n";
	echo "			<table align=\"center\" width=\"100%\" border=0>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"5\" align=\"left\">\n";
	
	echo "         			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "   						<tr>\n";
	echo "      						<td class=\"gray\" align=\"right\" NOWRAP></td>\n";
	echo "      						<td class=\"gray\" align=\"right\" NOWRAP>\n";
	echo "									<font size=\"2\"><a href=\".\subs\drilldetail.php?sid=".md5($_SESSION['securityid'])."&call=hp&hpc=AW\" target=\"winName\" onMouseOver=\"window.status='';return true;\" onMouseOut=\"window.status=''; return true;\" onclick=\"window.open('','winName','HEIGHT=400,WIDTH=500,scrollbars=yes,dependent=yes,resizable=yes,toolbar=no,menubar=no,location=no,directories=no'); window.status=''; return true;\">HELP</a></font>\n";
	echo "         							<input class=\"buttondkgry\" type=\"submit\" value=\"Save Addendum\" onClick=\"return AddnAlert('comadj','payadj','secid','nsecid')\">\n";
	echo "      						</td>\n";
	echo "   						</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"5\" align=\"left\">\n";
	
	echo "         			<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "            			<tr>\n";
	echo "               			<td colspan=\"2\" class=\"gray\" align=\"left\"><b>".$add_type." Addendum Worksheet</b></td>\n";
	echo "			               	<td class=\"gray\" align=\"right\"><b>Date:</b> ".$adate."</td>\n";
	echo "							<td colspan=\"2\" class=\"gray\" align=\"right\"><b>Addendum # <font color=\"red\">".$addcnt."</font> for Contract # <font color=\"red\">".$viewarray['jobid']."</font></b></td>\n";
	echo "							</td>\n";
	echo "            			</tr>\n";
	echo "            			<tr>\n";
	echo "			                <td class=\"gray\" align=\"right\"><b>Customer:</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\">".$rowI['clname'].", ".$rowI['cfname']."</td>\n";
	echo "			               	<td class=\"gray\" align=\"right\"></td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>Salesman:</b></td>\n";
	echo "							<td class=\"gray\" align=\"left\"> ".$rowD['lname'].", ".$rowD['fname']."</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "              <td colspan=\"5\" align=\"left\">\n";
	
	echo "					<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "            			<tr>\n";
	echo "               			<td class=\"gray\" lign=\"left\"><b>Addendum Description</b></td>\n";
	echo "               			<td class=\"gray\" align=\"left\"><b>Commission & Pay Schedule Adjust</b></td>\n";
	echo "						</tr>\n";
	echo "            			<tr>\n";
	
	echo "               			<td class=\"gray\" align=\"center\">\n";
	echo "								<textarea name=\"comments\" rows=\"5\" cols=\"70\"></textarea>\n";
	echo "							</td>\n";
	echo "               			<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	
	if ($nrowK > 0)
	{
		$cadjperc=.05;
		echo "								<table border=0>\n";
		echo "            						<tr>\n";
		echo "               						<td align=\"right\"><b>Pay Schedule</b></td>\n";
		echo "				               			<td align=\"right\">\n";
		echo "              							<input class=\"brdrtxtrght JMStooltip\" type=\"text\" name=\"paysadj\" id=\"payadj\" value=\"".number_format(0, 2, '.', '')."\" size=\"7\" onChange=\"return updPercAddn('payadj','cadjperc','comadjdiv','comadj');\" title=\"Enter the amount of the Retail Contract Adjustment here\">\n";
		echo "										</td>\n";
		echo "               						<td align=\"left\"></td>\n";
		echo "									</tr>\n";
		echo "            						<tr>\n";
		echo "               						<td align=\"right\"><b>Commission</b> ".($cadjperc * 100)."%</td>\n";
		echo "				               			<td align=\"right\">\n";
		echo "											<div id=\"comadjdiv\">0.00</div>\n";
		echo "              							<input type=\"hidden\" name=\"csched[3][rwdamt]\" id=\"comadj\" value=\"\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][label]\" value=\"SRA".$addcnt."\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][catid]\" value=\"3\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][ctype]\" value=\"2\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][rwdrate]\" id=\"cadjperc\" value=\"".($cadjperc * 100)."\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][uid]\" value=\"".md5(session_id().time().$viewarray['jobsecid']).".".$_SESSION['securityid']."\">\n";
	}
	else
	{
		echo "								<table border=0>\n";
		echo "            						<tr>\n";
		echo "               						<td align=\"right\"><b>Pay Schedule</b></td>\n";
		echo "				               			<td align=\"right\">\n";
		echo "              							<input class=\"brdrtxtrght JMStooltip\" type=\"text\" name=\"paysadj\" id=\"payadj\" value=\"".number_format(0, 2, '.', '')."\" size=\"7\" title=\"Enter the amount of the Retail Contract Adjustment here\">\n";
		echo "										</td>\n";
		echo "               						<td align=\"left\"></td>\n";
		echo "									</tr>\n";
		echo "            						<tr>\n";
		echo "               						<td align=\"right\"><b>Commission</b></td>\n";
		echo "				               			<td align=\"right\">\n";
		echo "              							<input class=\"brdrtxtrght\" type=\"text\" name=\"csched[3][rwdamt]\" id=\"comadj\" value=\"".number_format(0, 2, '.', '')."\" size=\"7\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][label]\" value=\"SRA".$addcnt."\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][catid]\" value=\"3\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][ctype]\" value=\"1\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][rwdrate]\" value=\"0\">\n";
		echo "											<input type=\"hidden\" name=\"csched[3][uid]\" value=\"".md5(session_id().time().$viewarray['jobsecid']).".".$_SESSION['securityid']."\">\n";
	}
	
	echo "										</td>\n";
	echo "               						<td align=\"left\">\n";
	echo "											<select class=\"JMStooltip\" name=\"nsecid\" title=\"Select the recipient of the Commission\">\n";
	
	$qryDz = "SELECT securityid,fname,lname,substring(slevel,13,1) as slev FROM security WHERE officeid=".$_SESSION['officeid']." and substring(slevel,13,1) >=1 order by lname asc;";
	$resDz = mssql_query($qryDz);
	$nrowDz= mssql_num_rows($resDz);

	while ($rowDz = mssql_fetch_array($resDz))
	{
		if ($rowDz['securityid']==$viewarray['jobsecid'])
		{
			echo "<option value=\"".$rowDz['securityid']."\" SELECTED>".ucwords($rowDz['lname']).", ".ucwords($rowDz['fname'])."</option>\n";
		}
		else
		{
			echo "<option value=\"".$rowDz['securityid']."\">".ucwords($rowDz['lname']).", ".ucwords($rowDz['fname'])."</option>\n";
		}
	}
	
	echo "											</select>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
	echo "							</td>\n";
	
	/*
	echo "               			<td class=\"gray\" align=\"center\">\n";
	echo "								<textarea name=\"comments\" rows=\"5\" cols=\"70\"></textarea>\n";
	echo "							</td>\n";
	echo "               			<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "								<table border=0>\n";
	echo "            						<tr>\n";
	echo "               						<td align=\"right\"><b>Commission</b></td>\n";
	echo "				               			<td align=\"right\">\n";
	echo "              							<input class=\"brdrtxtrght\" type=\"text\" name=\"csched[3][rwdamt]\" id=\"comadj\" value=\"".number_format(0, 2, '.', '')."\" size=\"7\">\n";
	echo "											<input type=\"hidden\" name=\"csched[3][label]\" value=\"SRA".$addcnt."\">\n";
	echo "											<input type=\"hidden\" name=\"csched[3][catid]\" value=\"3\">\n";
	echo "											<input type=\"hidden\" name=\"csched[3][ctype]\" value=\"1\">\n";
	echo "											<input type=\"hidden\" name=\"csched[3][rwdrate]\" value=\"0\">\n";
	echo "											<input type=\"hidden\" name=\"csched[3][uid]\" value=\"".md5(session_id().time().$viewarray['jobsecid']).".".$_SESSION['securityid']."\">\n";
	echo "										</td>\n";	
	echo "               						<td align=\"left\">\n";
	echo "											<select name=\"sidm\">\n";
	
	$qryDz = "SELECT securityid,fname,lname,substring(slevel,13,1) as slev FROM security WHERE officeid=".$_SESSION['officeid']." order by substring(slevel,13,1) desc, lname asc;";
	$resDz = mssql_query($qryDz);
	$nrowDz= mssql_num_rows($resDz);

	while ($rowDz = mssql_fetch_array($resDz))
	{
		if ($rowDz['securityid']==$rowpreC['sidm'])
		{
			echo "<option value=\"".$rowDz['securityid']."\" SELECTED>".$rowDz['lname'].", ".$rowDz['fname']."</option>\n";
		}
		else
		{
			echo "<option value=\"".$rowDz['securityid']."\">".$rowDz['lname'].", ".$rowDz['fname']."</option>\n";
		}
	}
	
	echo "											</select>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "            						<tr>\n";
	echo "               						<td align=\"right\"><b>Pay Schedule</b></td>\n";
	echo "				               			<td align=\"right\">\n";
	echo "              							<input class=\"brdrtxtrght\" type=\"text\" name=\"paysadj\" id=\"payadj\" value=\"".number_format(0, 2, '.', '')."\" size=\"7\">\n";
	echo "										</td>\n";
	echo "               						<td align=\"left\"></td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
	echo "							</td>\n";
	*/
	echo "						</tr>\n";
	echo "					</table>\n";
	
	echo "				</td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"5\" align=\"left\">\n";
	
	echo "               	<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                  	<tr>\n";
	echo "                     		<td class=\"gray\" colspan=\"12\" align=\"left\" valign=\"bottom\"><b>POOL DIMENSIONS</b></td>\n";
	echo "                     	</tr>\n";
	echo "                     	<tr>\n";

	if ($rowpreB['pft_sqft']=="p")
	{
		echo "                     	<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Perimeter</b></td>\n";
	}
	else
	{
		echo "                     	<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Surface Area</b></td>\n";
	}

	echo "                     	<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";

	if ($rowB1['quan1t'] > 0)
	{
		if ($rowpreB['pft_sqft']=="p")
		{
			echo "                        	<input class=\"bboxl\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps1']."\"></td>\n";
		}
		else
		{
			echo "                        	<input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps2']."\"></td>\n";
		}
	}
	else
	{

		if ($rowpreB['pft_sqft']=="p")
		{
			echo "                        	<select name=\"ps1\">\n";
		}
		else
		{
			echo "                        	<select name=\"ps2\">\n";
		}

		while($rowA = mssql_fetch_array($resA))
		{
			if ($rowA['quan']==$rowB['quan'])
			{
				echo "                        		<option value=\"".$rowA['quan']."\" SELECTED>".$rowA['quan']."</option>\n";
			}
			else
			{
				echo "                        		<option value=\"".$rowA['quan']."\">".$rowA['quan']."</option>\n";
			}
		}

		echo "									</select>\n";
	}
	echo "								</td>\n";

	if ($rowpreB['pft_sqft']=="p")
	{
		echo "                     	<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Surface Area</b></td>\n";
	}
	else
	{
		echo "                     	<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Perimeter</b></td>\n";
	}

	echo "                        <td class=\"gray\" align=\"left\" valign=\"bottom\">\n";

	if ($rowpreB['pft_sqft']=="p")
	{
		echo "                        	<input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps2']."\"></td>\n";
	}
	else
	{
		echo "                        	<input class=\"bboxl\" type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps1']."\"></td>\n";
	}

	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Depth</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps5']."\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps6']."\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps7']."\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Electrical Run</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['erun']."\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Plumbing Run</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['prun']."\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Total Deck</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"deck\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['deck']."\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"5\" align=\"left\" valign=\"bottom\">\n";
	
	echo "               		<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                  		<tr>\n";
	echo "                     			<td class=\"gray\" colspan=\"5\" align=\"left\" valign=\"bottom\"><b>SPA DIMENSIONS</b></td>\n";
	echo "                     			<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>REFERRAL</b></td>\n";
	echo "                     			<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>TRAVEL</b></td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "									<select name=\"spa1\">\n";

	while($rowE = mssql_fetch_row($resE))
	{
		echo "										<option value=\"".$rowE[0]."\">".$rowE[1]."</option>\n\n";
	}

	echo "									</select>\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Spa Perimeter</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['spa2']."\">\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\"><b>Spa Surface Area</b></td>\n";
	echo "								<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "                  				<input class=\"bboxl\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['spa3']."\">\n";
	echo "								</td>\n";
	echo "            					<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "                  				<input class=\"bboxl\" type=\"text\" name=\"refto\" value=\"".$viewarray['refto']."\" size=\"15\">\n";
	echo "               				</td>\n";
	echo "								<td class=\"gray\" align=\"left\" valign=\"bottom\">\n";
	echo "                  				<input class=\"bboxl\" type=\"text\" name=\"tzone\" value=\"".$viewarray['tzone']."\" size=\"15\">\n";
	echo "               				</td>\n";
	echo "            				</tr>\n";
	echo "         				</table>\n";
	
	echo "      			</td>\n";
	echo "   			</tr>\n";
	echo "   			<tr>\n";
	echo "      			<td colspan=\"5\">\n";
	
	echo "         				<table class=\"outer\" width=\"100%\">\n";
	/*
	echo "         					<tr>\n";
	echo "            					<td class=\"wh\" colspan=\"5\" valign=\"top\">\n";

	$ecnt=1;
	foreach ($catarray as $n=>$v)
	{
		$qryJ = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."' AND active=1;";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		if ($rowJ[0]!=0)
		{
			if ($ecnt==count($catarray))
			{
				echo "<a href=\"#".$rowJ[0]."\">".$rowJ[1]."</a>";
			}
			else
			{
				echo "<a href=\"#".$rowJ[0]."\">".$rowJ[1]."</a> - ";
			}
			$ecnt++;
		}
	}

	echo "            					</td>\n";
	echo "            				</tr>\n";
	*/
	
	// POOL RETAIL ACC ITEM Loop
	foreach ($catarray as $n=>$v)
	{
		$qryJ = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		if ($v!=0)
		{
			echo "            				<tr>\n";
			echo "            					<td class=\"wh\" colspan=\"4\" align=\"left\" valign=\"top\"><input type=\"hidden\" name=\"#".$rowJ[0]."\"><b>".$rowJ[1]."</b></td>\n";
			echo "            					<td class=\"wh\" align=\"right\" valign=\"top\"><a href=\"#Top\">Up</a></td>\n";
			echo "            				</tr>\n";
			echo "            				<tr>\n";
			echo "            					<td colspan=\"5\" class=\"gray\" valign=\"top\">\n";

			$qryM  = "SELECT id,qtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."' AND disabled!=1 ORDER BY seqn;";
			$resM  = mssql_query($qryM);
			$nrowM = mssql_num_rows($resM);

			$qcnt=0;

			while ($rowM=mssql_fetch_row($resM))
			{
				$qcnt++;

				if ($qcnt==1)
				{
					form_element_ACC($rowM[0],1,$rowpreA['estdata'],$type);
				}
				elseif ($qcnt==$nrowM)
				{
					form_element_ACC($rowM[0],2,$rowpreA['estdata'],$type);
				}
				else
				{
					form_element_ACC($rowM[0],0,$rowpreA['estdata'],$type);
				}
			}

			echo "         						</td>\n";
			echo "         					</tr>\n";
		}
	}

	echo "         				</table>\n";
	
	echo "         			</td>\n";
	echo "   			</tr>\n";
	/*echo "				<tr>\n";
	echo "					<td colspan=\"5\" align=\"left\">\n";
	
	echo "						<table class=\"outer\" align=\"center\" width=\"100%\" border=0>\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" NOWRAP>\n";
	echo "									<input class=\"buttondkgry\" type=\"submit\" value=\"Save Addendum\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	
	echo "					</td>\n";
	echo "				</tr>\n";*/
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	echo "</div>\n";
}

function build_addendum_save()
{
	if ($_SESSION['securityid']==26)
	{
		ini_set('display_errors','On');
		error_reporting(E_ALL);
	}
	
	/*if ($_SESSION['securityid']==26)
	{
		echo $_REQUEST['csched'][3]['rwdamt'].'<br>';
		display_array($_REQUEST['csched'][3]);
		//display_array($_REQUEST);
		exit;
	}*/
	
	$estAdata_init =estAdata_init();
	global $viewarray,$t_chg_ar;

	if (empty($_REQUEST['uid']))
	{
		echo "<b>Transition Error Occured!</b>";
		exit;
	}

	$qry  	= "SELECT jadd FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND unique_id='".$_REQUEST['uid']."'; ";
	$res  	= mssql_query($qry);
	$row  	= mssql_fetch_row($res);
	$nrow 	= mssql_num_rows($res);

	$qry1	= "SELECT officeid,pft_sqft,stax FROM offices WHERE officeid='".$_SESSION['officeid']."'; ";
	$res1	= mssql_query($qry1);
	$row1	= mssql_fetch_array($res1);

	$qry2	= "SELECT SUM(quan1) as quan1t FROM rbpricep WHERE officeid='".$_SESSION['officeid']."'; ";
	$res2 	= mssql_query($qry2);
	$row2	= mssql_fetch_array($res2);

	$viewarray	=array(
	'ps1'=>	$_REQUEST['ps1'],
	'ps2'=>	$_REQUEST['ps2'],
	'ps5'=>	$_REQUEST['ps5'],
	'ps6'=>	$_REQUEST['ps6'],
	'ps7'=>	$_REQUEST['ps7'],
	'spa1'=>	$_REQUEST['spa1'],
	'spa2'=>	$_REQUEST['spa2'],
	'spa3'=>	$_REQUEST['spa3'],
	'deck'=>	$_REQUEST['deck'],
	'tzone'=>	$_REQUEST['tzone']
	);

	if ($row1['pft_sqft']=="p")
	{
		$defmeas=$_REQUEST['ps1'];
	}
	else
	{
		$defmeas=$_REQUEST['ps2'];
	}

	if (empty($_REQUEST['refto']))
	{
		$refto="";
	}
	else
	{
		$refto=$_REQUEST['refto'];
	}

	if ($nrow > 0)
	{
		echo "<b>This Addendum has already been Submitted!</b>";
		exit;
	}
	elseif ($nrow==0)
	{
		if ($_REQUEST['jadd'] > 1)
		{
			$jaddn=$_REQUEST['jadd']-1;
		}
		else
		{
			$jaddn=0;
		}

		$qryXa  = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$jaddn."' ;";
		$resXa  = mssql_query($qryXa);
		$rowXa  = mssql_fetch_array($resXa);

		if ($row1['pft_sqft']=="p")
		{
			$defmeasa=$rowXa['pft'];
		}
		else
		{
			$defmeasa=$rowXa['sqft'];
		}

		$tr_price	=0;
		$cm_price	=0;
		$c_cnt		=0;
		$t_chg_ar	="";

		if ($row1['stax']==1)
		{
			$qry4  = "SELECT scounty FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
			$res4  = mssql_query($qry4);
			$row4  = mssql_fetch_array($res4);

			$qry5  = "SELECT taxrate FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$row4['scounty']."';";
			$res5  = mssql_query($qry5);
			$row5  = mssql_fetch_array($res5);

			if (!empty($row5['taxrate']))
			{
				$atrate=$row5['taxrate'];
			}
			else
			{
				$qry6  = "SELECT taxrate FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
				$res6  = mssql_query($qry6);
				$row6  = mssql_fetch_array($res6);

				$atrate=$row6['taxrate'];
			}
		}
		else
		{
			$atrate="0.0";
		}

		//echo "~TRP: ".$tr_price."<br>";
		//echo "~CMP: ".$cm_price."<br>";

		if ($rowXa['estdata']!=$estAdata_init||$_REQUEST['ps1']!=$rowXa['pft']||$_REQUEST['ps2']!=$rowXa['sqft']||strlen($_REQUEST['comments']) > 1)
		{
			if ($_REQUEST['ps1']!=$rowXa['pft']||$_REQUEST['ps2']!=$rowXa['sqft'])
			{
				if ($row2['quan1t'] > 0)
				{
					$b_price_c	=0;
					$b_comm_c	=0;
					$b_price_d	=0;
					$b_comm_d	=0;
					//echo "SUM<br>";
					//echo $"<br>";
					$qryXc  = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan;";
					$resXc  = mssql_query($qryXc);

					//echo $qryXc."<br>";

					while ($rowXc  = mssql_fetch_array($resXc))
					{
						if ($defmeas >= $rowXc['quan'] && $defmeas <= $rowXc['quan1'])
						{
							//echo "SUM1<br>";
							$b_price_c	=$rowXc['price'];
							$b_comm_c	=$rowXc['comm'];
						}
					}

					$b_price_d	=$rowXa['bpprice'];
					$b_comm_d	=$rowXa['bpcomm'];
				}
				else
				{
					//echo "LIST<br>";
					$qryXc  = "SELECT * FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$defmeas."';";
					$resXc  = mssql_query($qryXc);
					$rowXc  = mssql_fetch_array($resXc);

					$b_price_c	=$rowXc['price'];
					$b_comm_c	=$rowXc['comm'];

					$b_price_d	=$rowXa['bpprice'];
					$b_comm_d	=$rowXa['bpcomm'];
				}

				$tr_price=$tr_price+($b_price_c-$b_price_d);
				$cm_price=$cm_price+($b_comm_c-$b_comm_d);
			}
			else
			{
				$tr_price=$rowXa['bpprice'];
				$cm_price=$rowXa['bpcomm'];

				$b_price_c=$rowXa['bpprice'];
				$b_comm_c=$rowXa['bpcomm'];
			}

			//$verifiedpr	=align_pricing($estAdata_init);
			$pkgdata		=store_packages($_REQUEST['jobid'],$_REQUEST['jadd'],$estAdata_init);
			//echo "PKG<BR>";
			$lbrcost		=store_labor_cost_items($_REQUEST['jobid'],$_REQUEST['jadd'],$estAdata_init);
			//echo "LBR<BR>";
			$matcost		=store_material_cost_items($_REQUEST['jobid'],$_REQUEST['jadd'],$estAdata_init);
			//echo "MAT<BR>";
			$lbrbcost	=store_labor_baseitems($_REQUEST['jobid'],$_REQUEST['jadd']);
			//echo "BLBR<BR>";
			$matbcost	=store_material_baseitems($_REQUEST['jobid'],$_REQUEST['jadd']);
			//echo "BMAT<BR>";

			// Store Addn as Est.
			$qryA   = "INSERT INTO jdetail ";
			$qryA  .= "(officeid,jobid,jadd,pft,sqft,";
			$qryA  .= "shal,mid,deep,spa_pft,spa_sqft,";
			$qryA  .= "spa_type,deck,erun,prun,tzone,bpprice,";
			$qryA  .= "bpcomm,add_type,refto,contractamt,estdata,comments,";
			$qryA  .= "filters,pcostdata_l,pcostdata_m,costdata_l,costdata_m,bcostdata_l,bcostdata_m,taxrate,psched_adj,raddncm_man,";			
			$qryA  .= "unique_id)";
			$qryA  .= " VALUES ";
			$qryA  .= "(";
			$qryA  .= "'".$_SESSION['officeid']."',";
			$qryA  .= "'".$_REQUEST['jobid']."',";
			$qryA  .= "'".$_REQUEST['jadd']."',";
			$qryA  .= "'".$_REQUEST['ps1']."', ";
			$qryA  .= "'".$_REQUEST['ps2']."', ";
			$qryA  .= "'".$_REQUEST['ps5']."', ";
			$qryA  .= "'".$_REQUEST['ps6']."', ";
			$qryA  .= "'".$_REQUEST['ps7']."', ";
			$qryA  .= "'".$_REQUEST['spa2']."', ";
			$qryA  .= "'".$_REQUEST['spa3']."', ";
			$qryA  .= "'".$_REQUEST['spa1']."', ";
			$qryA  .= "'".$_REQUEST['deck']."', ";
			$qryA  .= "'".$_REQUEST['erun']."', ";
			$qryA  .= "'".$_REQUEST['prun']."', ";
			$qryA  .= "'".$_REQUEST['tzone']."', ";
			$qryA  .= "'".$b_price_c."', ";
			$qryA  .= "'".$b_comm_c."', ";
			$qryA  .= "'".$_REQUEST['add_type']."', ";
			$qryA  .= "'".$refto."', ";
			$qryA  .= "'".$rowXa['contractamt']."', ";
			$qryA  .= "'".$estAdata_init."',";
			$qryA  .= "'".replacequote($_REQUEST['comments'])."', ";
			$qryA  .= "'".$pkgdata[0]."',";
			$qryA  .= "'".$pkgdata[1]."',";
			$qryA  .= "'".$pkgdata[2]."',";
			$qryA  .= "'".$lbrcost[0]."',";
			$qryA  .= "'".$matcost[0]."',";
			$qryA  .= "'".$lbrbcost[0]."',";
			$qryA  .= "'".$matbcost[0]."',";
			$qryA  .= "'".$atrate."',";
			
			if (isset($_REQUEST['paysadj']) && $_REQUEST['paysadj']!=0)
			{
				$qryA  .= "'".$_REQUEST['paysadj']."',";
			}
			else
			{
				$qryA  .= "'0.00',";
			}
			
			if (isset($_REQUEST['csched'][3]['rwdamt']) && $_REQUEST['csched'][3]['rwdamt']!=0)
			{
				$qryA  .= "'".$_REQUEST['csched'][3]['rwdamt']."',";
			}
			else
			{
				$qryA  .= "'0.00',";
			}
			
			$qryA  .= "'".$_REQUEST['uid']."'";
			$qryA  .= ");";
			$resA   = mssql_query($qryA);
			
			/*if ($_SESSION['securityid']==26)
			{
				echo $qryA.'<br>';
			}*/
			
			$qryXy  = "SELECT securityid,estid,sidm,custid FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."';";
			$resXy  = mssql_query($qryXy);
			$rowXy  = mssql_fetch_array($resXy);
			
			$qryXya  = "SELECT estid,added FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$rowXy['estid']."';";
			$resXya  = mssql_query($qryXya);
			$rowXya  = mssql_fetch_array($resXya);
			
			$qryXyb  = "SELECT securityid,sidm,newcommdate FROM security WHERE securityid='".$rowXy['securityid']."';";
			$resXyb  = mssql_query($qryXyb);
			$rowXyb  = mssql_fetch_array($resXyb);
			
			if (strtotime($rowXya['added']) >= strtotime($rowXyb['newcommdate']))
			{
				if (isset($_REQUEST['sidm']) && $_REQUEST['sidm']!=$rowXy['sidm'])
				{
					$nsidm=$_REQUEST['sidm'];
				}
				else
				{
					$nsidm=$rowXy['sidm'];
				}
				
				store_com_items($rowXy['estid'],$_REQUEST['jobid'],$_REQUEST['jadd'],$rowXy['securityid'],$nsidm,date('m/d/y',time()),$rowXy['custid']);
			}

			// Writing New Bid Items
			foreach ($_POST as $n=>$v)
			{
				if (substr($n,0,4)=="bbba")
				{
					$asid=substr($n,4);
					if ($_REQUEST['bbba'.$asid] > 0)
					{
						if (array_key_exists("eeea".$asid,$_POST))
						{
							$qryB  = "INSERT INTO jbids (officeid,jobid,jadd,bidinfo,bidamt,dbid) VALUES ('".$_SESSION['officeid']."','".$_REQUEST['jobid']."','".$_REQUEST['jadd']."','".replacequote($_REQUEST['eeea'.$asid])."','".replacequote($_REQUEST['ddda'.$asid])."','$asid');";
							$resB  = mssql_query($qryB);
							//echo $qryB;
						}
					}
				}
			}

			$qryXb  = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."' ;";
			$resXb  = mssql_query($qryXb);
			$rowXb  = mssql_fetch_array($resXb);

			$odata=explode(",",$rowXa['estdata']);
			$ndata=explode(",",$rowXb['estdata']);

			//Start Variance Detection
			foreach ($odata as $n1 => $v1)
			{
				$in_o=explode(":",$v1);
				$o_ar[]=$in_o[0];
			}

			foreach ($ndata as $n2 => $v2)
			{
				$in_n=explode(":",$v2);
				$n_ar[]=$in_n[0];
			}

			$add_ar_diff=array_diff($n_ar,$o_ar);
			$del_ar_diff=array_diff($o_ar,$n_ar);
			$inter_ar=array_intersect($o_ar,$n_ar);

			//print_r($o_ar);
			//print_r($n_ar);
			//print_r($add_ar_diff);
			//print_r($del_ar_diff);
			//print_r($inter_ar);

			$ar_price=0;
			$dr_price=0;
			$cr_price=0;
			$ac_price=0;
			$dc_price=0;
			$cc_price=0;

			//echo "1: ".$t_chg_ar."<br>";
			//ADD Diffs;
			foreach ($add_ar_diff as $nA1 => $vA1)
			{
				//echo $vA1;
				//echo "<br>";
				foreach ($ndata as $nA2 => $vA2)
				{
					$in_nA2=explode(":",$vA2);
					if ($vA1==$in_nA2[0])
					{
						$chg_ar=$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6].":".$in_nA2[7].":0,";
						//echo "ADD: ".$in_nA2[0].":".$in_nA2[1].":".$in_nA2[2].":".$in_nA2[3].":".$in_nA2[4].":".$in_nA2[5].":".$in_nA2[6]."<br>";
						$t_chg_ar=$t_chg_ar.$chg_ar;
						$ar_price=$ar_price+($in_nA2[2]*$in_nA2[3]);

						if ($in_nA2[5]==1)
						{
							$ac_price=$ac_price+(($in_nA2[2]*$in_nA2[3])*$in_nA2[6]);
						}
						else
						{
							$ac_price=$ac_price+($in_nA2[2]*$in_nA2[6]);
						}

						//echo $ac_price."<br>";
						$c_cnt++;
					}
				}
			}

			//echo "2: ".$t_chg_ar."<br>";
			//DEL Diffs;
			foreach ($del_ar_diff as $nD1 => $vD1)
			{
				//echo $vD1;
				//echo "<br>";
				foreach ($odata as $nD2 => $vD2)
				{
					$in_nD2=explode(":",$vD2);
					if ($vD1==$in_nD2[0])
					{
						$Dquan=$in_nD2[2]*-1;
						$chg_ar=$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6].":".$in_nD2[7].":0,";
						//echo "DEL: ".$in_nD2[0].":".$in_nD2[1].":".$Dquan.":".$in_nD2[3].":".$in_nD2[4].":".$in_nD2[5].":".$in_nD2[6]."<br>";
						$t_chg_ar=$t_chg_ar.$chg_ar;
						$dr_price=$dr_price+($Dquan*$in_nD2[3]);

						if ($in_nD2[5]==1)
						{
							$dc_price=$dc_price+(($Dquan*$in_nD2[3])*$in_nD2[6]);
						}
						else
						{
							$dc_price=$dc_price+($Dquan*$in_nD2[6]);
						}

						$c_cnt++;
					}
				}
			}
			//echo "3: ".$t_chg_ar."<br>";
			
			array_walk($odata,'tst_vals',$ndata);
		
			//echo "4: ".$t_chg_ar."<BR>";
			$chng_rt	=$ar_price+$dr_price+$cr_price;
			$chng_cm	=$ac_price+$dc_price+$cc_price;
			$t_chg_ar=preg_replace("/,\Z/","",$t_chg_ar);

			//echo "5: ".$t_chg_ar." <br>";
			if ($rowXa['costdata_l']!=$rowXb['costdata_l'])
			{
				//echo "<b>Cost Lab DIFF:</b><br>";
				if (strlen($rowXa['costdata_l']) < 3||strlen($rowXb['costdata_l']) < 3)
				{
					$cldiff=array(0=>0,1=>0,2=>0,3=>0,4=>0);
				}
				else
				{
					$cldiff=parse_diffs($rowXa['costdata_l'],$rowXb['costdata_l']);
				}
			}
			else
			{
				$cldiff=array(0=>0,1=>0,2=>0,3=>0,4=>0);
			}

			if ($rowXa['costdata_m']!=$rowXb['costdata_m'])
			{
				//echo "<b>Cost Mat DIFF:</b><br>";
				if (strlen($rowXa['costdata_m']) < 3||strlen($rowXb['costdata_m']) < 3)
				{
					$cmdiff=array(0=>0,1=>0,2=>0,3=>0,4=>0);
				}
				else
				{
					$cmdiff=parse_diffs($rowXa['costdata_m'],$rowXb['costdata_m']);
				}
			}
			else
			{
				$cmdiff=array(0=>0,1=>0,2=>0,3=>0,4=>0);
			}

			//echo "BCOSTo: ".$rowXa['bcostdata_l']." |<br>";
			//echo "BCOSTn: ".$rowXb['bcostdata_l']." |<br>";
			if ($rowXa['bcostdata_l']!=$rowXb['bcostdata_l'])
			{
				//echo "<b>Base Cost Lab DIFF:</b><br>";
				if (strlen($rowXa['bcostdata_l']) < 3||strlen($rowXb['bcostdata_l']) < 3)
				{
					$bcldiff=array(0=>0,1=>0,2=>0,3=>0,4=>0);
				}
				else
				{
					$bcldiff=parse_diffs($rowXa['bcostdata_l'],$rowXb['bcostdata_l']);
				}
			}
			else
			{
				$bcldiff=array(0=>0,1=>0,2=>0,3=>0,4=>0);
			}

			if ($rowXa['bcostdata_m']!=$rowXb['bcostdata_m'])
			{
				//echo "<b>Base Cost Mat DIFF:</b><br>";
				//echo "<b>A |".$rowXa['bcostdata_m']."| A</b><br>";
				//echo "<b>B |".$rowXb['bcostdata_m']."| B</b><br>";
				if (strlen($rowXa['bcostdata_m']) < 3 || strlen($rowXb['bcostdata_m']) < 3)
				{
					$bcmdiff=array(0=>0,1=>0,2=>0,3=>0,4=>0);
				}
				else
				{
					$bcmdiff=parse_diffs($rowXa['bcostdata_m'],$rowXb['bcostdata_m']);
				}
			}
			else
			{
				$bcmdiff=array(0=>0,1=>0,2=>0,3=>0,4=>0);
			}

			//Detection for Package Filter Cost Item Diffs
			if ($rowXa['filters']!=$rowXb['filters'])
			{
				//echo "<b>Package Cost Mat DIFF:</b><br>";
				if (strlen($rowXa['filters']) < 3||strlen($rowXb['filters']) < 3)
				{
					$fdiff=array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0);
				}
				else
				{
					$prefdiff	=parse_filter_diffs($rowXa['filters'],$rowXb['filters']);
					$fdiff		=parse_filter_cost_diffs($prefdiff[4]);
				}
			}
			else
			{
				$fdiff=array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0);
			}

			$qryXz  = "UPDATE jdetail SET ";
			$qryXz .= "raddnacc='".$t_chg_ar."',raddnpr='".$chng_rt."',raddncm='".$chng_cm."',";
			$qryXz .= "costlabdiff='".$cldiff[4]."',costmatdiff='".$cmdiff[4]."',";
			$qryXz .= "bcostlabdiff='".$bcldiff[4]."',bcostmatdiff='".$bcmdiff[4]."',";
			$qryXz .= "pcostlabdiff='".$fdiff[4]."',pcostmatdiff='".$fdiff[5]."'";
			$qryXz .= " WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."' ;";
			$resXz  = mssql_query($qryXz);

			//echo $qryXz."<br>";

			view_job_addendum_retail();
			//view_job_retail();
		}
		else
		{
			echo "<b>No changes detected. <font color=\"red\">Addendum not saved!</font></b>";
		}

		echo "</td></tr>\n";
		echo "</table>\n";
	}
}

function view_job_addendum_retail()
{
	//echo "Addendum";
	global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate,$tbid,$tbullets,$viewarray;
	
	$viewarray	=$_SESSION['viewarray'];
	$jobid		=$_REQUEST['jobid'];
	$jaddn		=$_REQUEST['jadd'];
	$securityid =$_SESSION['securityid'];
	$officeid   =$_SESSION['officeid'];
	$fname      =$_SESSION['fname'];
	$lname      =$_SESSION['lname'];

	if (!isset($jobid)||$jobid=='')
	{
		echo "Fatal Error: Job ID (".$jobid.") not set!";
		exit;
	}

	if ($jaddn >= 1)
	{
		$ojaddn=$jaddn-1;
	}
	else
	{
		$ojaddn=0;
	}

	$qrypreA = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$jaddn."';";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$qrypreAa = "SELECT * FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='".$ojaddn."';";
	$respreAa = mssql_query($qrypreAa);
	$rowpreAa = mssql_fetch_array($respreAa);

	$qrypreAb = "SELECT contractdate,added FROM jdetail WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."' AND jadd='0';";
	$respreAb = mssql_query($qrypreAb);
	$rowpreAb = mssql_fetch_array($respreAb);

	$qrypreB = "SELECT * FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$respreB = mssql_query($qrypreB);
	$rowpreB = mssql_fetch_array($respreB);

	$acclist	= explode(",",$_SESSION['aid']);

	if (!in_array($rowpreB['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Job</b>";
		exit;
	}

	$viewarray=array(
	'ps1'=>		$rowpreA['pft'],
	'ps2'=>		$rowpreA['sqft'],
	'spa1'=>		$rowpreA['spa_pft'],
	'spa2'=>		$rowpreA['spa_sqft'],
	'spa3'=>		$rowpreA['spa_type'],
	'tzone'=>	$rowpreA['tzone'],
	'camt'=>		$rowpreA['contractamt'],
	'cdate'=>	$rowpreAb['contractdate'],
	'status'=>	$rowpreB['status'],
	'ps5'=>		$rowpreA['shal'],
	'ps6'=>		$rowpreA['mid'],
	'ps7'=>		$rowpreA['deep'],
	//'custid'=>	$rowpreB['custid'],
	'estsecid'=>$rowpreB['securityid'],
	'deck'=>		$rowpreA['deck'],
	'erun'=>		$rowpreA['erun'],
	'prun'=>		$rowpreA['prun'],
	'jobid'=>	$rowpreB['jobid'],
	'comadj'=>	$rowpreA['ouadj'],
	'sidm'=>		$rowpreB['sidm'],
	'applyou'=>	1,
	'refto'=>	$rowpreA['refto'],
	'ps1a'=>		$rowpreA['apft'],
	'bpprice'=>	$rowpreA['bpprice'],
	'bpcomm'=>	$rowpreA['bpcomm'],
	'jadd'=>		$rowpreA['jadd'],
	'added'=>	$rowpreA['added']
	);

	$r_estdata = $rowpreA['estdata'];

	$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft,encost FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	//$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$viewarray['ps1']."';";
	//$resB = mssql_query($qryB);
	//$rowB = mssql_fetch_row($resB);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['estsecid']."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_array($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,mas_prep,cid,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$viewarray['jobid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$viewarray['cid']	=$rowI['cid'];

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['sidm']."';";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_array($resL);

	$qryN = "SELECT securityid,fname,lname FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1';";
	$resN = mssql_query($qryN);

	$qryP = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' AND admstaff!='1';";
	$resP = mssql_query($qryP);

	$qryO = "SELECT MAX(jadd) as mjadd FROM jdetail WHERE  officeid='".$_SESSION['officeid']."' AND jobid='".$jobid."';";
	$resO = mssql_query($qryO);
	$rowO = mssql_fetch_array($resO);

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}

	if ($rowC[8]=="p")
	{
		$defmeas		=$viewarray['ps1'];
		$defmeasa	=$rowpreAa['pft'];
	}
	else
	{
		$defmeas		=$viewarray['ps2'];
		$defmeasa	=$rowpreAa['sqft'];
	}

	if ($rowpreA['raddnroy_man']=="1")
	{
		$ck="CHECKED";
	}
	else
	{
		$ck="";
	}

	if ($rowI[10]==1)
	{
		$tbg		="magenta";
	}
	else
	{
		$tbg		="gray";
	}

	$sdate			=date("m-d-Y", strtotime($rowpreA['added']));
	$cdate 			=date("m-d-Y", strtotime($viewarray['cdate']));
	$poolcomm_adj	=detect_package($r_estdata);
	$set_deck 		=deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck		=round($set_deck[0]);
	$set_ia			=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals		=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$tbullets   	=0;
	$estidret   	=$jobid;
	$vdiscnt    	=$viewarray['camt'];
	$pbaseprice 	=0;
	$ctramt     	=$viewarray['camt'];
	$fctramt    	=number_format($ctramt, 2, '.', '');
	$brdr				=0;

	//$cdate=getdate();

	//print_r($cdate);
	//echo "<form id=\"UpdateContractAddn\" method=\"post\">\n";
	echo "<table class=\"transnb\" width=\"950px\">\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "			<table width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" colspan=\"3\">\n";

	info_display_job($tbg,$rowC[1],$estidret,$viewarray['jadd'],$rowD['fname'],$rowD['lname'],$rowL['fname'],$rowL['lname'],"Retail","Contract",$viewarray['estsecid'],'');

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	cinfo_display_job($rowpreB['officeid'],$viewarray['cid'],$rowC[2]);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"33%\">\n";

	pool_detail_display_job($viewarray['jobid'],$viewarray['jadd']);

	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"33%\">\n";

	dates_display_job($viewarray['cid']);

	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\">\n";
	echo "         <table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "              	<td class=\"gray\" align=\"left\">\n";
	echo "         			<table width=\"100%\">\n";
	echo "           				<tr>\n";
	echo "              				<td class=\"gray\" align=\"left\"><b>Addendum Comments:</b></td>\n";
	echo "           				</tr>\n";
	echo "           				<tr>\n";
	echo "              				<td class=\"gray\" align=\"left\">".$rowpreA['comments']."</td>\n";
	echo "           				</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" width=\"100%\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"left\" width=\"100\"><b>Category</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Item</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b>Quan.</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"30\"><b>Units</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"><b>Retail</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"><b>Comm</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" valign=\"bottom\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";

	if ($defmeas!=$defmeasa||$viewarray['bpprice']!=$rowpreAa['bpprice']||$viewarray['bpcomm']!=$rowpreAa['bpcomm'])
	{
		//echo "DIFF<br>";
		$bquan		=$defmeas-$defmeasa;
		//echo "p1: ".$viewarray['bpprice']."<br>";
		//echo "p2: ".$rowpreAa['bpprice']."<br>";

		if ($viewarray['bpprice']!=$rowpreAa['bpprice']||$viewarray['bpcomm']!=$rowpreAa['bpcomm'])
		{
			$pbaseprice	=$viewarray['bpprice']-$rowpreAa['bpprice'];
			$bcomm		=$viewarray['bpcomm']-$rowpreAa['bpcomm'];
		}
		else
		{
			$pbaseprice	=0;
			$bcomm		=0;
		}
		$fpbaseprice=number_format($pbaseprice, 2, '.', '');
		$fbcomm     =number_format($bcomm, 2, '.', '');

		echo "           <tr>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\" width=\"100\">Base</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\"><b>Basic Pool Perimeter Change</b></td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">".$bquan."</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"30\">pft</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"60\">".$fpbaseprice."</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\" width=\"60\">".$fbcomm."</td>\n";
		echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"center\" width=\"60\"></td>\n";
		echo "           </tr>\n";
	}
	else
	{
		$bcomm		=0;
	}

	//echo "EST: ".$rowpreA['estdata']."<br>FIL: ".$rowpreA['filters']."<br>";
	calcbyacc_add($rowpreA['raddnacc'],0);

	// Totals Table Calcs
	$bccost  =$bctotal;
	$rccost  =$rctotal;
	$cccost  =$cctotal;
	$bmcost  =$bmtotal;
	$rmcost  =$rmtotal;
	$trccost =$rccost+$rmcost;
	$cmcost  =$cmtotal;
	$tbcost  =$bccost+$bmcost;
	$trcost  =$pbaseprice+$trccost+$tbid;
	$tccost  =$cccost+$cmcost;
	$trcomm  =$bcomm+$tccost;
	//$trcomm  =$tccost;
	$ftrcost    =number_format($trcost, 2, '.', '');
	$ftccost    =number_format($tccost, 2, '.', '');
	$ftrcomm    =number_format($trcomm, 2, '.', '');
	$ftrprman		=number_format($rowpreA['raddnpr_man'], 2, '.', '');
	$ftrcmman	=number_format($rowpreA['raddncm_man'], 2, '.', '');
	$fpschadj	=number_format($rowpreA['psched_adj'], 2, '.', '');

	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Addendum Price per Book:</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftrcost."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$ftrcomm."</td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Addendum Retail Price Adjust:</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";
	echo "					<form method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"edit_add_price\">\n";
	echo "					<input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
	echo "					<input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";
	echo "					<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "					<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
	echo "					<input type=\"hidden\" name=\"cmmanadj\" value=\"".$rowpreA['raddncm_man']."\">";
	echo "					<input class=\"bbox\" type=\"text\" name=\"prmanadj\" size=\"8\" maxlength=\"10\" value=\"".$ftrprman."\"></td>\n";

	if ($_SESSION['clev'] >= 4)
	{
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\"><input class=\"bbox\" type=\"text\" name=\"cmmanadj\" size=\"8\" maxlength=\"10\" value=\"".$ftrcmman."\"></td>\n";
	}
	else
	{
		echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\">".$ftrcmman." <input type=\"hidden\" name=\"cmmanadj\" value=\"".$ftrcmman."\"></td>\n";
	}

	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"right\"><b>Payment Schedule Amount:</b></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\"><input class=\"bbox\" type=\"text\" name=\"pschadj\" size=\"8\" maxlength=\"10\" value=\"".$fpschadj."\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"right\" width=\"60\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td NOWRAP colspan=\"6\" class=\"wh\" align=\"right\"><input type=\"hidden\" name=\"royadj\" value=\"0\"></td>\n";
	echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"60\"><input class=\"buttondkgry\" type=\"submit\" value=\"Apply Adjust\"></form></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table>\n";

	if  ($viewarray['jadd'] >= 1)
	{
		echo "            <tr>\n";
		echo "               <td align=\"left\">\n";
		echo "               	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		echo "                           <input type=\"hidden\" name=\"action\" value=\"contract\">\n";
		echo "                           <input type=\"hidden\" name=\"call\" value=\"delete_job1\">\n";
		echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$viewarray['jobid']."\">\n";
		echo "                           <input type=\"hidden\" name=\"jadd\" value=\"".$viewarray['jadd']."\">\n";

		if ($rowI['njobid']!=0 ||  $rowO['mjadd']!=$viewarray['jadd'])
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete Addn\" DISABLED>\n";
		}
		else
		{
			echo "                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Delete Addn\">\n";
		}

		echo "					</form>\n";
		echo "               <td>\n";
		echo "            </tr>\n";
		echo "            <tr>\n";
		echo "               <td align=\"center\">\n";
		echo "						<div class=\"noPrint\">\n";
		echo "						<hr width=\"90%\">\n";
		echo "						</div>\n";
		echo "					</td>\n";
		echo "            </tr>\n";

	}

	echo "            <tr>\n";
	echo "               <td align=\"left\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "					<form method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "					<input type=\"hidden\" name=\"jobid\" value=\"".$estidret."\">\n";
	echo "					<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
	echo "                  <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Retail\">\n";
	echo "					</form>\n";
	echo "					</div>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
}

?>