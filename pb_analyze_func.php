<?php

function displayroycomm($trp,$comm,$relid)
{
	$Cclass="wh_und";
	$lCclass="wh_undsidesl";
	$rCclass="wh_undsidesr";

	if ($trp!=0)
	{
		$frp=$trp * .03;
		$ftrp	=number_format($frp, 2, '.', '');
		
		//echo $_POST['Racc'.$relid]."<br>";
		//echo $trp."<br>";
		
		if (array_key_exists("Racc".$relid,$_POST) && $_POST['Racc'.$relid] != $trp)
		{
			$vCclass="yel_und";
			$nftrp=$_POST['Racc'.$relid] *.03;
		}
		else
		{
			$vCclass="wh_und";
		}
		
		echo "		<tr>\n";
		echo "			<td align=\"right\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;ROY</td>\n";
		echo "			<td align=\"center\" class=\"".$Cclass."\">DLC</td>\n";
		echo "			<td align=\"left\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Royalty</td>\n";
		echo "			<td align=\"left\" class=\"".$Cclass."\">Royalty</td>\n";
		echo "			<td align=\"center\" class=\"".$Cclass."\"></td>\n";
		echo "			<td align=\"center\" class=\"".$Cclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$Cclass."\">".$ftrp."</td>\n";
		echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$rCclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
		
		if (array_key_exists("Racc".$relid,$_POST) && $_POST['Racc'.$relid] != $trp)
		{
			echo "			<td align=\"right\" class=\"".$vCclass."\">".number_format($nftrp, 2, '.', '')."</td>\n";
			echo "			<input type=\"hidden\" name=\"CRacc".$relid."\" value=\"".number_format($nftrp, 2, '.', '')."\"></td>\n";
		}
		else
		{
			echo "			<td align=\"right\" class=\"".$vCclass."\">".$ftrp."</td>\n";
		}
		
		echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
		echo " 		</tr>\n";
	}

	if ($comm!=0)
	{
		$ftcm	=number_format($comm, 2, '.', '');
		
		if (array_key_exists("CCacc".$relid,$_POST) && $_POST['CCacc'.$relid] != $ftcm)
		{
			$vCclass="yel_und";
		}
		else
		{
			$vCclass="ltgray_und";
		}
		
		echo "		<tr>\n";
		echo "			<td align=\"right\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;COMM</td>\n";
		echo "			<td align=\"center\" class=\"".$Cclass."\">DLC</td>\n";
		echo "			<td align=\"left\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Commission</td>\n";
		echo "			<td align=\"left\" class=\"".$Cclass."\">Commission</td>\n";
		echo "			<td align=\"center\" class=\"".$Cclass."\"></td>\n";
		echo "			<td align=\"center\" class=\"".$Cclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$Cclass."\">".$ftcm."</td>\n";
		echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$rCclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
		
		if (array_key_exists("CCacc".$relid,$_POST) && $_POST['CCacc'.$relid] != $ftcm)
		{	
			echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CCacc".$relid."\" value=\"".$_POST['CCacc'.$relid]."\" size=\"5\" maxlength=\"9\"></td>\n";
		}
		else
		{
			echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CCacc".$relid."\" value=\"".$ftcm."\" size=\"5\" maxlength=\"9\"></td>\n";
		}
		
		echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
		echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
		echo " 		</tr>\n";
	}
}

function comm_calcpa($qtype,$rctype,$rprice,$rcrate,$quan_out)
{
	if ($rctype==1)
	{
		//echo "TYPE 1<br>";
		if ($qtype==33)
		{
			$cc=($rprice*$rcrate)*$quan_out;
		}
		elseif ($qtype==20)
		{
			$cc=($rc_code*$rcrate)*$quan_out;
		}
		elseif ($qtype==5 || $qtype==6 || $qtype==7 || $qtype==58)
		{
			$cc=$rprice*$rcrate;
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

	return $cc;
}

function quan_set()
{
	// This function sets default quanities if no input quantities are detected

	if (isset($_POST['pft']) && is_numeric($_POST['pft']))
	{
		$_SESSION['pba_pft']=$_POST['pft'];
	}
	else
	{
		$_SESSION['pba_pft']=80;
	}

	if (isset($_POST['sqft']) && is_numeric($_POST['sqft']))
	{
		$_SESSION['pba_sqft']=$_POST['sqft'];
	}
	else
	{
		$_SESSION['pba_sqft']=400;
	}

	if (isset($_POST['shal']) && is_numeric($_POST['shal']))
	{
		$_SESSION['pba_shal']=$_POST['shal'];
	}
	else
	{
		$_SESSION['pba_shal']=3;
	}

	if (isset($_POST['mid']) && is_numeric($_POST['mid']))
	{
		$_SESSION['pba_mid']=$_POST['mid'];
	}
	else
	{
		$_SESSION['pba_mid']=4;
	}

	if (isset($_POST['deep']) && is_numeric($_POST['deep']))
	{
		$_SESSION['pba_deep']=$_POST['deep'];
	}
	else
	{
		$_SESSION['pba_deep']=5;
	}

	if (isset($_POST['erun']) && is_numeric($_POST['erun']))
	{
		$_SESSION['pba_erun']=$_POST['erun'];
	}
	else
	{
		$_SESSION['pba_erun']=100;
	}

	if (isset($_POST['prun']) && is_numeric($_POST['prun']))
	{
		$_SESSION['pba_prun']=$_POST['prun'];
	}
	else
	{
		$_SESSION['pba_prun']=50;
	}

	if (isset($_POST['dquan']) && is_numeric($_POST['dquan']))
	{
		$_SESSION['pba_dquan']=$_POST['dquan'];
	}
	else
	{
		$_SESSION['pba_dquan']=1;
	}

	$out	=array(
						$_SESSION['pba_pft'],
						$_SESSION['pba_sqft'],
						$_SESSION['pba_shal'],
						$_SESSION['pba_mid'],
						$_SESSION['pba_deep'],
						$_SESSION['pba_erun'],
						$_SESSION['pba_prun'],
						$_SESSION['pba_dquan']
					);
	return $out;
}

function getrelatedcost($id,$def_quan,$lr,$hr,$pft,$sqft,$shal,$mid,$deep,$erun,$prun,$squan,$trp,$comm,$relid)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$out			=array(0=>0,1=>0);
	$tlbp			=0;
	$tmbp			=0;
	$quan_out	=0;
	//$comm		=0;
	//$roy		=0;

	if ($trp!=0)
	{
		$ftrp		=$trp * .03;
		$out[0]	=$out[0]+$ftrp;
	}

	if ($comm!=0)
	{
		$ftcm	=$comm;
		$out[0]	=$out[0]+$ftcm;
	}

	$qry0 = "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$id."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		//echo "LLINKS: ".$nrow0."<br>";
		while ($row0 = mssql_fetch_array($res0))
		{
			$qry0a = "SELECT id,qtype,bprice,quantity,accid,atrib1,atrib2,atrib3,item FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row0['cid']."' AND baseitem!=1;";
			$res0a = mssql_query($qry0a);
			$nrow0a= mssql_num_rows($res0a);

			if ($nrow0a > 0)
			{
				while($row0a = mssql_fetch_array($res0a))
				{
					$amts	=get_def_calc_amt($row0a['qtype'],$pft,$sqft,$shal,$mid,$deep,$row0a['quantity'],0,$squan,$row0a['atrib1'],$row0a['atrib2'],$row0a['atrib3']);

					if ($row0a['qtype']==9)
					{
						$setbprice=getspecaccpbook($row0a['accid'],$pft,$row0a['quantity']);
						$bprice	=$setbprice[0];
					}
					elseif ($row0a['qtype']==10)
					{
						$setbprice=getspecaccpbook($row0a['accid'],$sqft,$row0a['quantity']);
						$bprice	=$setbprice[0];
					}
					else
					{
						$bprice	=$row0a['bprice'];
					}

					$calc_out	=uni_calc_loop($row0a['qtype'],$bprice,0,$lr,$hr,$amts[0],$amts[6],$amts[7],$amts[8],0,0,0,$row0a['atrib1'],$row0a['atrib2'],$row0a['atrib3'],0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$out[0]		=$out[0]+$bp;
					//echo "LAB1:".$bp." ($out[0]) ".$row0a['item']."<br>";
				}
			}
		}
	}

	$qry1 = "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$id."';";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);

	if ($nrow1 > 0)
	{
		//echo "MLINKS: ".$nrow1."<br>";
		while ($row1 = mssql_fetch_array($res1))
		{
			$qry1a = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row1['cid']."' AND baseitem!=1;";
			$res1a = mssql_query($qry1a);
			$nrow1a= mssql_num_rows($res1a);

			if ($nrow1a > 0)
			{
				$mbp=0;
				while ($row1a = mssql_fetch_array($res1a))
				{
					if ($row1a['matid']!=0)
					{
						$qry1b = "SELECT id,bp FROM [material_master] WHERE id='".$row1a['matid']."';";
						$res1b = mssql_query($qry1b);
						$row1b = mssql_fetch_array($res1b);

						$mbp=$row1b['bp'];
					}
					else
					{
						$mbp=$row1a['bprice'];
					}

					$amts		=get_def_calc_amt($row1a['qtype'],$pft,$sqft,$shal,$mid,$deep,$row1a['quan_calc'],0,$squan,$row1a['atrib1'],$row1a['atrib2'],$row1a['atrib3']);
					$calc_out	=uni_calc_loop($row1a['qtype'],$mbp,0,$lr,$hr,$amts[0],$amts[6],$amts[7],$amts[8],0,0,0,0,0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$out[0]		=$out[0]+$bp;
					//$tmbp		=$tmbp+$bp;
					//$out[0]=$out[0]+$mbp;
					//echo "MAT1:".$bp." ($out[0]) ".$row1a['item']."<br>";
				}
			}
		}
	}

	$qry2 = "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$id."';";
	$res2 = mssql_query($qry2);
	$nrow2= mssql_num_rows($res2);

	if ($nrow2 > 0)
	{
		//echo "PLINKS: ".$nrow2."<br>";
		while ($row2 = mssql_fetch_array($res2))
		{
			$qry2a = "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row2['iid']."';";
			$res2a = mssql_query($qry2a);
			$nrow2a= mssql_num_rows($res2a);

			//echo $qry2a."<br>";

			if ($nrow2a > 0)
			{
				//echo "PLLINKS: ".$nrow2a."<br>";
				while ($row2a = mssql_fetch_array($res2a))
				{
					$qry2b = "SELECT id,qtype,bprice,quantity,item,atrib1,atrib2,atrib3,lrange,hrange FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row2a['cid']."' AND baseitem!=1;";
					$res2b = mssql_query($qry2b);
					$nrow2b= mssql_num_rows($res2b);

					//echo $qry2b."<br>";
					if ($nrow2b > 0)
					{
						while($row2b = mssql_fetch_array($res2b))
						{
							$adjquan		=package_quan_set($row2b['qtype'],$row2b['quantity'],$row2['adjquan'],$pft,$sqft,0,$shal,$mid,$deep,0,0,0,0);
							$adjtype		=$row2['adjtype'];
							$adjamt		=$row2['adjamt'];

							if ($adjtype==1) // Adjusts
							{
								$adjquan=$row2b['quantity']+$row2['adjquan'];
								$adjamt=$row2b['bprice']+$row2['adjamt'];
							}
							elseif ($adjtype==2) // Price Percent Adjust
							{
								$adjamt=($row2b['bprice']*$row2['adjamt'])*$adjquan;
							}
							elseif ($adjtype==3)
							{
								$adjquan=$row2b['quantity']+$row2['adjquan'];
							}
							elseif ($adjtype==4) // Zero Price
							{
								$adjamt=($row2b['bprice']+($row2b['bprice'] * -1))*$row2['adjquan'];
							}
							elseif ($adjtype==5)
							{
								$adjquan=$row2b['quan_calc']+($row2b['quan_calc'] * -1);
							}
							elseif ($adjtype==6)
							{
								$adjamt=($row2b['bprice']+($row2b['bprice'] * -1))*$row2['adjquan'];
								$adjquan=$row2b['quantity']+($row2b['quantity'] * -1);
							}

							$amts		=get_def_calc_amt($row2b['qtype'],$pft,$sqft,$shal,$mid,$deep,$adjquan,0,0,$row2b['atrib1'],$row2b['atrib2'],$row2b['atrib3']);
							$calc_out	=uni_calc_loop($row2b['qtype'],$row2b['bprice'],0,$row2b['lrange'],$row2b['hrange'],$adjquan,$row2b['quantity'],$amts[7],$amts[8],0,0,0,0,0,0);
							//echo "PLABADJ:".$adjquan."<br>";
							//$calc_out		=uni_calc_loop($row2b['qtype'],$adjamt,0,$row2b['lrange'],$row2b['hrange'],$adjquan,$row2b['quantity'],$amts[7],$amts[8],0,0,0,0,0,0);
							$bp			=$calc_out[0];
							$quan_out	=$calc_out[2];
							$out[0]		=$out[0]+$bp;
							//echo "PLAB1:".$bp." ($out[0]) ".$row2b['item']."<br>";
						}
					}
				}
			}

			$qry2c = "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row2['iid']."';";
			$res2c = mssql_query($qry2c);
			$nrow2c= mssql_num_rows($res2c);

			if ($nrow2c > 0)
			{
				//echo "MLINKS: ".$nrow1."<br>";
				while ($row2c = mssql_fetch_array($res2c))
				{
					$qry2d = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row2c['cid']."' AND baseitem!=1;";
					$res2d= mssql_query($qry2d);
					$nrow2d= mssql_num_rows($res2d);

					if ($nrow2d > 0)
					{
						$mbp=0;
						while ($row2d = mssql_fetch_array($res2d))
						{
							$adjquan		=package_quan_set($row2d['qtype'],$row2d['quan_calc'],$row2['adjquan'],$pft,$sqft,0,$shal,$mid,$deep,0,0,0,0);
							$adjtype		=$row2['adjtype'];
							$adjamt		=$row2['adjamt'];

							if ($row2d['matid']!=0 && $row2d['qtype']!=56)
							{
								$qry2e = "SELECT id,bp FROM [material_master] WHERE id='".$row2d['matid']."';";
								$res2e = mssql_query($qry2e);
								$row2e = mssql_fetch_array($res2e);

								//echo "M<br>";
								$mbp=$row2e['bp'];
							}
							else
							{
								//echo "NM<br>";
								$mbp=$row2d['bprice'];
							}

							if ($adjtype==1) // Adjusts
							{
								$adjquan=$row2d['quan_calc']+$row2['adjquan'];
								$adjamt=$mbp+$row2['adjamt'];
							}
							elseif ($adjtype==2) // Price Percent Adjust
							{
								$adjamt=($mbp*$row2['adjamt'])*$adjquan;
							}
							elseif ($adjtype==3)
							{
								$adjquan=$row2d['quan_calc']+$row2['adjquan'];
							}
							elseif ($adjtype==4) // Zero Price
							{
								$adjamt=($mbp+($mbp * -1))*$row2['adjquan'];
							}
							elseif ($adjtype==5)
							{
								$adjquan=$row2d['quan_calc']+($row2d['quan_calc'] * -1);
							}
							elseif ($adjtype==6)
							{
								$adjamt=($mbp+($mbp * -1))*$row2['adjquan'];
								$adjquan=$row2d['quan_calc']+($row2d['quan_calc'] * -1);
							}

							$amts		=get_def_calc_amt($row2d['qtype'],$pft,$sqft,$shal,$mid,$deep,$adjquan,0,0,$row2d['atrib1'],$row2d['atrib2'],$row2d['atrib3']);
							$calc_out		=uni_calc_loop($row2d['qtype'],$mbp,0,0,0,$adjquan,$row2d['quan_calc'],$amts[7],$amts[8],0,0,0,$row2d['atrib1'],$row2d['atrib2'],$row2d['atrib3']);
							$bp			=$calc_out[0];
							$quan_out	=$calc_out[2];
							$out[0]		=$out[0]+$bp;
							//echo "MAT1:".$mbp." ($out[0]) ".$row2d['item']."<br>";
						}
					}
				}
			}
		}
	}

	//echo "TL: ".$tlbp."<br>";
	//echo "TM: ".$tmbp."<br>";
	//$out=array(0=>$out[0],1=>1,2=>$nrow0,3=>$nrow1);
	$out=array(0=>$out[0],1=>$quan_out,2=>$nrow0,3=>$nrow1);
	return $out;
}

function displayrelatedlabcost($id,$def_quan,$lr,$hr,$pft,$sqft,$shal,$mid,$deep,$erun,$prun,$squan,$trp,$comm,$relid)
{
	//echo $pft.":".$sqft."<br>";
	$MAS=$_SESSION['pb_code'];
	global $viewarray;
	$out=array(0=>0,1=>0);
	$tlbp=0;
	$Cclass="ltgray_und";
	
	//echo "At DRL<br>";
	//print_r($_SESSION['viewarray']);

	$qry0 = "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$id."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	//echo $qry0."<br>";
	$icnt=0;
	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			$qry0a = "SELECT id,qtype,bprice,quantity,item,accid,atrib1,atrib2,atrib3,phsid,mtype FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row0['cid']."' AND baseitem!=1;";
			$res0a = mssql_query($qry0a);
			$nrow0a= mssql_num_rows($res0a);

			if ($nrow0a > 0)
			{
				while($row0a = mssql_fetch_array($res0a))
				{
					$amts		=get_def_calc_amt($row0a['qtype'],$pft,$sqft,$shal,$mid,$deep,$row0a['quantity'],0,$squan,$row0a['atrib1'],$row0a['atrib2'],$row0a['atrib3']);

					if ($row0a['qtype']==9 || $row0a['qtype']==10)
					{
						$qry0b  = "SELECT bprice,lrange,hrange FROM specaccpbook WHERE officeid='".$_SESSION['officeid']."' AND linkid='".$row0a['accid']."' ORDER BY hrange ASC;";
						$res0b  = mssql_query($qry0b);
						$nrow0b = mssql_num_rows($res0b);

						if ($nrow0b > 0)
						{
							while ($row0b=mssql_fetch_array($res0b))
							{
								if ($amts[0] >= $row0b['lrange'] && $amts[0] <= $row0b['hrange'])
								{
									$bprice =$row0b['bprice'];
								}
								elseif ($amts[0] > $row0b['hrange'])
								{
									$bprice =$row0b['bprice']+(($amts[0]-$row0b['hrange'])*$row0a['quantity']);
								}
							}
						}
					}
					else
					{
						$bprice		=$row0a['bprice'];
					}

					$qry0c = "SELECT phsid,phsname FROM phasebase WHERE phsid='".$row0a['phsid']."';";
					$res0c = mssql_query($qry0c);
					$row0c= mssql_fetch_array($res0c);
					
					$qry0d = "SELECT abrv FROM mtypes WHERE mid='".$row0a['mtype']."';";
					$res0d  = mssql_query($qry0d);
					$row0d  = mssql_fetch_array($res0d);

					$calc_out	=uni_calc_loop($row0a['qtype'],$bprice,0,$lr,$hr,$amts[0],$amts[6],$amts[7],$amts[8],0,0,0,$row0a['atrib1'],$row0a['atrib2'],$row0a['atrib3'],0,0);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];

					$fbp	=number_format($bp, 2, '.', '');
					
					/*if ($row0a['qtype']==3)
					{
						echo $row0a['qtype']." : ".$amts[0]." : ".$bprice." : ".$fbp."<BR>";
					}*/
					
					$Cclass="wh_und";
					$lCclass="wh_undsidesl";
					$rCclass="wh_undsidesr";
					
					if (array_key_exists("CLacc".$relid,$_POST) && $_POST['CLacc'.$relid][$icnt] != $fbp)
					{
						$vCclass="yel_und";
					}
					else
					{
						$vCclass="wh_und";
					}
					
					echo "		<tr>\n";
					echo "			<td align=\"right\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;".$row0a['accid']."</td>\n";
					echo "			<td align=\"center\" class=\"".$Cclass."\">DLC</td>\n";
					echo "			<td align=\"left\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row0a['item']."</td>\n";
					echo "			<td align=\"left\" class=\"".$Cclass."\">".$row0c['phsname']."</td>\n";
					echo "			<td align=\"center\" class=\"".$Cclass."\">".$row0d['abrv']."</td>\n";
					echo "			<td align=\"center\" class=\"".$Cclass."\">".round($quan_out)."</td>\n";
					echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$Cclass."\">".$fbp."</td>\n";
					echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$rCclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
					
					if (array_key_exists("CLacc".$relid,$_POST) && $_POST['CLacc'.$relid][$icnt] != $fbp)
					{	
						echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CLacc".$relid."[]\" value=\"".$_POST['CLacc'.$relid][$icnt]."\" size=\"5\" maxlength=\"9\"></td>\n";
					}
					else
					{
						echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CLacc".$relid."[]\" value=\"".$fbp."\" size=\"5\" maxlength=\"9\"></td>\n";
					}
					
					echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
					echo " 		</tr>\n";
					$icnt++;
				}
			}
		}
	}

	$qry2 = "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$id."';";
	$res2 = mssql_query($qry2);
	$nrow2= mssql_num_rows($res2);

	//echo $qry2."<br>";
	//echo $nrow2;
	if ($nrow2 > 0)
	{
		//echo "DPLINKS: ".$nrow2."<br>";
		while ($row2 = mssql_fetch_array($res2))
		{
			$qry2a = "SELECT rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row2['iid']."';";
			$res2a = mssql_query($qry2a);
			$nrow2a= mssql_num_rows($res2a);

			//echo $qry2a."<br>";

			if ($nrow2a > 0)
			{
				//echo "PLLINKS: ".$nrow2a."<br>";
				while ($row2a = mssql_fetch_array($res2a))
				{
					$qry2b = "SELECT id,qtype,bprice,quantity,item,accid,atrib1,atrib2,atrib3,lrange,hrange,phsid,mtype FROM [".$MAS."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND id='".$row2a['cid']."' AND baseitem!=1;";
					$res2b = mssql_query($qry2b);
					$nrow2b= mssql_num_rows($res2b);

					//echo $qry2b."<br>";
					//$icnt=$icnt;
					if ($nrow2b > 0)
					{
						while($row2b = mssql_fetch_array($res2b))
						{
							$adjquan		=package_quan_set($row2b['qtype'],$row2b['quantity'],$row2['adjquan'],$pft,$sqft,0,$shal,$mid,$deep,0,0,0,0);
							$adjtype		=$row2['adjtype'];
							$adjamt		=$row2['adjamt'];

							if ($adjtype==1) // Adjusts
							{
								$adjquan=$row2b['quantity']+$row2['adjquan'];
								$adjamt=$row2b['bprice']+$row2['adjamt'];
							}
							elseif ($adjtype==2) // Price Percent Adjust
							{
								$adjamt=($row2b['bprice']*$row2['adjamt'])*$adjquan;
							}
							elseif ($adjtype==3)
							{
								$adjquan=$row2b['quantity']+$row2['adjquan'];
							}
							elseif ($adjtype==4) // Zero Price
							{
								$adjamt=($row2b['bprice']+($row2b['bprice'] * -1))*$row2['adjquan'];
							}
							elseif ($adjtype==5)
							{
								$adjquan=$row2b['quan_calc']+($row2b['quan_calc'] * -1);
							}
							elseif ($adjtype==6)
							{
								$adjamt=($row2b['bprice']+($row1['bprice'] * -1))*$row2['adjquan'];
								$adjquan=$row2b['quantity']+($row2b['quantity'] * -1);
							}

							$qry2c = "SELECT phsid,phsname FROM phasebase WHERE phsid='".$row2b['phsid']."';";
							$res2c = mssql_query($qry2c);
							$row2c= mssql_fetch_array($res2c);

							$amts			=get_def_calc_amt($row2b['qtype'],$pft,$sqft,$shal,$mid,$deep,$adjquan,0,0,$row2b['atrib1'],$row2b['atrib2'],$row2b['atrib3']);
							$calc_out	=uni_calc_loop($row2b['qtype'],$row2b['bprice'],0,$row2b['lrange'],$row2b['hrange'],$adjquan,$row2b['quantity'],$amts[7],$amts[8],0,0,0,0,0,0);
							$bp			=$calc_out[0];
							$quan_out	=$calc_out[2];
							//$out[0]		=$out[0]+$bp;

							//echo "DPADJAMT:".$adjamt."<br>";
							$fbp	=number_format($bp, 2, '.', '');
							
							$Cclass="wh_und";
							$lCclass="wh_undsidesl";
							$rCclass="wh_undsidesr";
							
							if (array_key_exists("CLacc".$relid,$_POST) && $_POST['CLacc'.$relid][$icnt] != $fbp)
							{
								$vCclass="yel_und";
							}
							else
							{
								$vCclass="wh_und";
							}
							
							$qry2d = "SELECT abrv FROM mtypes WHERE mid='".$row2b['mtype']."';";
							$res2d  = mssql_query($qry2d);
							$row2d  = mssql_fetch_array($res2d);
							
							echo "		<tr>\n";
							echo "			<td align=\"right\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;".$row2b['accid']."</td>\n";
							echo "			<td align=\"center\" class=\"".$Cclass."\">PLC</td>\n";
							echo "			<td align=\"left\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row2b['item']."</td>\n";
							echo "			<td align=\"left\" class=\"".$Cclass."\">".$row2c['phsname']."</td>\n";
							echo "			<td align=\"center\" class=\"".$Cclass."\">".$row2d['abrv']."</td>\n";
							echo "			<td align=\"center\" class=\"".$Cclass."\">".round($quan_out)."</td>\n";
							echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$Cclass."\">".$fbp."</td>\n";
							echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$rCclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
							
							if (array_key_exists("CLacc".$relid,$_POST) && $_POST['CLacc'.$relid][$icnt] != $fbp)
							{	
								echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CLacc".$relid."[]\" value=\"".$_POST['CLacc'.$relid][$icnt]."\" size=\"5\" maxlength=\"9\"></td>\n";
							}
							else
							{
								echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CLacc".$relid."[]\" value=\"".$fbp."\" size=\"5\" maxlength=\"9\"></td>\n";
							}
							
							echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
							echo " 		</tr>\n";
							$icnt++;
						}
					}
				}
			}
		}
	}
}

function displayrelatedmatcost($id,$def_quan,$lr,$hr,$pft,$sqft,$shal,$mid,$deep,$erun,$prun,$squan,$trp,$relid)
{
	$MAS=$_SESSION['pb_code'];
	global $viewarray;

	$out=array(0=>0,1=>0);
	$tmbp=0;
	$icnt=0;
	$Cclass="ltgray_und";

	$qry1 = "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$id."';";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);

	if ($nrow1 > 0)
	{
		$icnt=0;
		while ($row1 = mssql_fetch_array($res1))
		{
			$qry1a = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row1['cid']."' AND baseitem!=1;";
			$res1a = mssql_query($qry1a);
			$nrow1a= mssql_num_rows($res1a);

			if ($nrow1a > 0)
			{
				$mbp=0;
				while ($row1a = mssql_fetch_array($res1a))
				{
					if ($row1a['matid']!=0 && $row1a['qtype']!=56)
					{
						$qry1b = "SELECT id,bp FROM [material_master] WHERE id='".$row1a['matid']."';";
						$res1b = mssql_query($qry1b);
						$row1b = mssql_fetch_array($res1b);

						$mbp=$row1b['bp'];
					}
					else
					{
						$mbp=$row1a['bprice'];
					}

					$qry1c 		= "SELECT phsid,phsname FROM phasebase WHERE phsid='".$row1a['phsid']."';";
					$res1c 		= mssql_query($qry1c);
					$row1c		= mssql_fetch_array($res1c);

					$amts			=get_def_calc_amt($row1a['qtype'],$pft,$sqft,$shal,$mid,$deep,$row1a['quan_calc'],0,$squan,$row1a['atrib1'],$row1a['atrib2'],$row1a['atrib3']);
					$calc_out	=uni_calc_loop($row1a['qtype'],$mbp,0,$lr,$hr,$amts[0],$amts[6],$amts[7],$amts[8],0,0,0,0,0,0);

					//$amts		=get_def_calc_amt($row1a['qtype'],$pft,$sqft,$shal,$mid,$deep,$row1a['quan_calc'],0,0,$row1a['atrib1'],$row1a['atrib2'],$row1a['atrib3']);
					//$calc_out		=uni_calc_loop($row1a['qtype'],$mbp,0,0,0,$amts[0],$amts[6],$amts[7],$amts[8],0,0,0,$row1a['atrib1'],$row1a['atrib2'],$row1a['atrib3']);
					$bp			=$calc_out[0];
					$quan_out	=$calc_out[2];
					$fbp			=number_format($bp, 2, '.', '');
					
					$Cclass="wh_und";
					$lCclass="wh_undsidesl";
					$rCclass="wh_undsidesr";
					
					if (array_key_exists("CMacc".$relid,$_POST) && $_POST['CMacc'.$relid][$icnt] != $fbp)
					{
						$vCclass="yel_und";
					}
					else
					{
						$vCclass="wh_und";
					}
					
					$qry1d = "SELECT abrv FROM mtypes WHERE mid='".$row1a['mtype']."';";
					$res1d  = mssql_query($qry1d);
					$row1d  = mssql_fetch_array($res1d);
					
					echo "		<tr>\n";
					echo "			<td align=\"right\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;".$row1a['accid']."</td>\n";
					echo "			<td align=\"center\" class=\"".$Cclass."\">DMC</td>\n";
					echo "			<td align=\"left\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row1a['item']."</td>\n";
					echo "			<td align=\"left\" class=\"".$Cclass."\">".$row1c['phsname']."</td>\n";
					echo "			<td align=\"center\" class=\"".$Cclass."\">".$row1d['abrv']."</td>\n";
					echo "			<td align=\"center\" class=\"".$Cclass."\">".round($quan_out)."</td>\n";
					echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$Cclass."\">".$fbp."</td>\n";
					echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$rCclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
					
					if (array_key_exists("CMacc".$relid,$_POST) && $_POST['CMacc'.$relid][$icnt] != $fbp)
					{	
						echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CMacc".$relid."[]\" value=\"".$_POST['CMacc'.$relid][$icnt]."\" size=\"5\" maxlength=\"9\"></td>\n";
					}
					else
					{
						echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CMacc".$relid."[]\" value=\"".$fbp."\" size=\"5\" maxlength=\"9\"></td>\n";
					}
					
					echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
					echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
					echo " 		</tr>\n";
					$icnt++;
				}
			}
		}
	}

	$qry2 = "SELECT * FROM [".$MAS."plinks] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$id."';";
	$res2 = mssql_query($qry2);
	$nrow2= mssql_num_rows($res2);

	if ($nrow2 > 0)
	{
		//echo "PLINKS: ".$nrow2."<br>";
		while ($row2 = mssql_fetch_array($res2))
		{
			$adjquan	=$row2['adjquan'];
			$adjtype	=$row2['adjtype'];
			$adjamt	=$row2['adjamt'];

			$qry2c = "SELECT rid,cid FROM [".$MAS."rclinks_m] WHERE officeid='".$_SESSION['officeid']."' AND rid='".$row2['iid']."';";
			$res2c = mssql_query($qry2c);
			$nrow2c= mssql_num_rows($res2c);

			if ($nrow2c > 0)
			{
				//echo "DMLINKS: ".$nrow1."<br>";
				while ($row2c = mssql_fetch_array($res2c))
				{
					$qry2d = "SELECT * FROM [".$MAS."inventory] WHERE officeid='".$_SESSION['officeid']."' AND invid='".$row2c['cid']."' AND baseitem!=1;";
					$res2d= mssql_query($qry2d);
					$nrow2d= mssql_num_rows($res2d);

					if ($nrow2d > 0)
					{
						$mbp=0;
						while ($row2d = mssql_fetch_array($res2d))
						{
							$adjquan		=package_quan_set($row2d['qtype'],$row2d['quan_calc'],$row2['adjquan'],$pft,$sqft,0,$shal,$mid,$deep,0,0,0,0);
							$adjtype		=$row2['adjtype'];
							$adjamt		=$row2['adjamt'];

							if ($row2d['matid']!=0 && $row2d['qtype']!=56)
							{
								$qry2e = "SELECT id,bp FROM [material_master] WHERE id='".$row2d['matid']."';";
								$res2e = mssql_query($qry2e);
								$row2e = mssql_fetch_array($res2e);

								$mbp=$row2e['bp'];
							}
							else
							{
								$mbp=$row2d['bprice'];
							}

							if ($adjtype==1) // Adjusts
							{
								$adjquan=$row2d['quan_calc']+$row2['adjquan'];
								$adjamt=$mbp+$row2['adjamt'];
							}
							elseif ($adjtype==2) // Price Percent Adjust
							{
								$adjamt=($mbp*$row2['adjamt'])*$adjquan;
							}
							elseif ($adjtype==3)
							{
								$adjquan=$row2d['quan_calc']+$row2['adjquan'];
							}
							elseif ($adjtype==4) // Zero Price
							{
								$adjamt=($mbp+($mbp * -1))*$row2['adjquan'];
							}
							elseif ($adjtype==5)
							{
								$adjquan=$row2d['quan_calc']+($row2d['quan_calc'] * -1);
							}
							elseif ($adjtype==6)
							{
								$adjamt=($mbp+($mbp * -1))*$row2['adjquan'];
								$adjquan=$row2d['quan_calc']+($row2d['quan_calc'] * -1);
							}

							$qry2f = "SELECT phsid,phsname FROM phasebase WHERE phsid='".$row2d['phsid']."';";
							$res2f = mssql_query($qry2f);
							$row2f= mssql_fetch_array($res2f);
							
							$qry2g = "SELECT abrv FROM mtypes WHERE mid='".$row2d['mtype']."';";
							$res2g  = mssql_query($qry2g);
							$row2g  = mssql_fetch_array($res2g);

							$amts			=get_def_calc_amt($row2d['qtype'],$pft,$sqft,$shal,$mid,$deep,$adjquan,0,0,$row2d['atrib1'],$row2d['atrib2'],$row2d['atrib3']);
							$calc_out	=uni_calc_loop($row2d['qtype'],$mbp,0,0,0,$adjquan,$row2d['quan_calc'],$amts[7],$amts[8],0,0,0,$row2d['atrib1'],$row2d['atrib2'],$row2d['atrib3']);
							//$amts		=get_def_calc_amt($row2d['qtype'],$pft,$sqft,$shal,$mid,$deep,$row2d['quan_calc'],0,0,$row2d['atrib1'],$row2d['atrib2'],$row2d['atrib3']);
							//$calc_out	=uni_calc_loop($row2d['qtype'],$mbp,0,0,0,$amts[0],$amts[6],$amts[7],$amts[8],0,0,0,$row2d['atrib1'],$row2d['atrib2'],$row2d['atrib3']);
							$bp			=$calc_out[0];
							$quan_out	=$calc_out[2];
							$out[0]		=$out[0]+$bp;

							//echo "MAT1:".$mbp." ($id)($nrow1)<br>";
							$fbp	=number_format($bp, 2, '.', '');
							
							$Cclass="wh_und";
							$lCclass="wh_undsidesl";
							$rCclass="wh_undsidesr";
							
							if (array_key_exists("CMacc".$relid,$_POST) && $_POST['CMacc'.$relid][$icnt] != $fbp)
							{
								$vCclass="yel_und";
							}
							else
							{
								$vCclass="wh_und";
							}
							
							echo "		<tr>\n";
							echo "			<td align=\"right\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;".$row2d['accid']."</td>\n";
							echo "			<td align=\"center\" class=\"".$Cclass."\">PMC</td>\n";
							echo "			<td align=\"left\" class=\"".$Cclass."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row2d['item']."</td>\n";
							echo "			<td align=\"left\" class=\"".$Cclass."\">".$row2f['phsname']."</td>\n";
							echo "			<td align=\"center\" class=\"".$Cclass."\">".$row2g['abrv']."</td>\n";
							echo "			<td align=\"center\" class=\"".$Cclass."\">".round($quan_out)."</td>\n";
							echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$Cclass."\">".$fbp."</td>\n";
							echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$Cclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$rCclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
							
							if (array_key_exists("CMacc".$relid,$_POST) && $_POST['CMacc'.$relid][$icnt] != $fbp)
							{	
								echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CMacc".$relid."[]\" value=\"".$_POST['CMacc'.$relid][$icnt]."\" size=\"5\" maxlength=\"9\"></td>\n";
							}
							else
							{
								echo "			<td align=\"right\" class=\"".$vCclass."\"><input class=\"bbox\" type=\"text\" name=\"CMacc".$relid."[]\" value=\"".$fbp."\" size=\"5\" maxlength=\"9\"></td>\n";
							}
							
							echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$vCclass."\"></td>\n";
							echo "			<td align=\"right\" class=\"".$lCclass."\"></td>\n";
							echo " 		</tr>\n";
							$icnt++;
						}
					}
				}
			}
		}
	}
}

function admintool()
{
	$brdr=1;
	global $viewarray;
	//pbvalperiod();
	
	if (isset($_POST['subq']))
	{
		echo "<table border=\"0\" width=\"70%\" align=\"center\">\n";
		echo "   <tr>\n";
		echo "   	<td>\n";

		if ($_POST['subq']=="list")
		{
			//show_post_vars();
			acc_code_list();
		}

		echo "   	</td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function quan_sess_set()
{	
	echo "					<b><i>Pool Settings:</i></b> ";
	echo " 	     			<b>Per</b><input class=\"bboxbc\" type=\"text\" name=\"pft\" value=\"".$_SESSION['pba_pft']."\" size=\"2\" maxlength=\"3\">\n";
	echo " 	     			<b>SA</b><input class=\"bboxbc\" type=\"text\" name=\"sqft\" value=\"".$_SESSION['pba_sqft']."\" size=\"3\" maxlength=\"4\">\n";
	echo "      			<b>S</b><input class=\"bboxbc\" type=\"text\" name=\"shal\" value=\"".$_SESSION['pba_shal']."\" size=\"2\" maxlength=\"3\">\n";
	echo "      			<b>M</b><input class=\"bboxbc\" type=\"text\" name=\"mid\" value=\"".$_SESSION['pba_mid']."\" size=\"2\" maxlength=\"3\">\n";
	echo "      			<b>D</b><input class=\"bboxbc\" type=\"text\" name=\"deep\" value=\"".$_SESSION['pba_deep']."\" size=\"2\" maxlength=\"3\">\n";
	echo "      			<b>Def Quan</b><input class=\"bboxbc\" type=\"text\" name=\"dquan\" value=\"".$_SESSION['pba_dquan']."\" size=\"4\" maxlength=\"7\">\n";
}

function acc_code_list()
{
	if (isset($_SESSION['m_plev']) and $_SESSION['m_plev'] >= 1)
	{
		$brdr=0;
		$MAS=$_SESSION['pb_code'];
		global $viewarray;
		
		$qryA	= "SELECT pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
		$resA	= mssql_query($qryA);
		$rowA	= mssql_fetch_array($resA);
	
		quan_set();
	
		// Sets Quantities
		$q1	=$_SESSION['pba_pft'];
		$q2	=$_SESSION['pba_sqft'];
		$q3	=$_SESSION['pba_shal'];
		$q4	=$_SESSION['pba_mid'];
		$q5	=$_SESSION['pba_deep'];
		$q6	=$_SESSION['pba_erun'];
		$q7	=$_SESSION['pba_prun'];
		$q8	=$_SESSION['pba_dquan'];
	
		$viewarray= array(
		'ps1' =>$q1,
		'ps2' =>$q2,
		'ps3' =>$q3,
		'ps4' =>$q4,
		'ps5' =>$q5,
		'ps6' =>$q6,
		'ps7' =>$q7,
		'ps8' =>$q8
		);
	
		$_SESSION['viewarray']=$viewarray;
		//print_r($viewarray);
		$qryB	= "SELECT catid,name,officeid FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND active=1 AND catid!=0 ORDER BY seqn;";
		$resB	= mssql_query($qryB);
		$nrowB= mssql_num_rows($resB);
	
		if ($nrowB < 1)
		{
			echo "<font color=\"red\"><b>Error!</b></font> You must have at least 1 Active Category!\n";
			exit;
		}
	
		if (isset($_POST['catid']) && !empty($_POST['catid']))
		{
			$pcatid   =$_POST['catid'];
	
		}
		else
		{
			$pcatid   =0;
		}
	
		$qry   = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$pcatid."' AND qtype!=33 AND disabled!=1 ORDER BY seqn;";
		$res   = mssql_query($qry);
		$nrows = mssql_num_rows($res);
	
		if ($nrows < 1)
		{
			echo "      <table class=\"outer\" border=\"".$brdr."\" width=\"950px\" align=\"center\">\n";
			echo "      		<form method=\"post\">\n";
			echo "      		<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "      		<input type=\"hidden\" name=\"call\" value=\"pbanalyze\">\n";
			echo "      		<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
			echo "      		<input type=\"hidden\" name=\"order\" value=\"seqn\">\n";
			echo "      		<input type=\"hidden\" name=\"disabled\" value=\"1\">\n";
			echo "	      <tr>\n";
			echo "            <td class=\"gray\" align=\"left\" ><b>Pricebook Analysis for ".$_SESSION['offname']."</b></td>\n";
			echo "            <td class=\"gray\" align=\"center\" >\n";
			
			quan_sess_set();
			
			echo "			  </td>\n";
			echo "            <td class=\"gray\" align=\"right\" >\n";
			echo "               <select name=\"catid\">\n";
			echo "                  <option value=\"0\">None</option>\n";
	
			while ($rowB = mssql_fetch_array($resB))
			{
				if (isset($_POST['catid']) && !empty($_POST['catid']) && $_POST['catid']==$rowB[0])
				{
					echo "                  <option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]."</option>\n";
				}
				else
				{
					echo "                  <option value=\"".$rowB[0]."\">".$rowB[1]."</option>\n";
				}
			}
	
			echo "               </select>\n";
			echo "            </td>\n";
			echo "            <td class=\"gray\" align=\"right\" >\n";
			echo "					<input class=\"buttondkgry\" type=\"submit\" value=\"Select\">\n";
			echo "      		</form>\n";
			echo "            </td>\n";
			echo "         </tr>\n";
			echo "      </table>\n";
		}
		else
		{
			$typetext="R = Retail Item, RP = Package Item, DLC = Direct Labor Cost, DMC = Direct Material Cost, PLC = Package Labor Cost, PMC = Package Material Cost";
	
			$qryK   = "SELECT MAX(seqn) as mseqn FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid='".$_POST['catid']."';";
			$resK   = mssql_query($qryK);
			$rowK   = mssql_fetch_array($resK);
	
			$qryZ = "SELECT SUM(quan1) as quan1sum FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
			$resZ  = mssql_query($qryZ);
			$rowZ  = mssql_fetch_array($resZ);
	
			//echo $rowZ['quan1sum']."<br>";
	
			if ($rowA['pft_sqft']=="p")
			{
				$qnt=$q1;
			}
			else
			{
				$qnt=$q2;
			}
	
			if ($rowZ['quan1sum'] > 0)
			{
				if ($rowA['pft_sqft']=="p")
				{
					$qryY = "SELECT price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan >= '".$qnt."' AND quan1 <= '".$qnt."';";
				}
				else
				{
					$qryY = "SELECT price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan <= '".$qnt."' AND quan1 >= '".$qnt."';";
				}
	
				$resY  = mssql_query($qryY);
				$rowY  = mssql_fetch_array($resY);
			}
			else
			{
				$qryY = "SELECT price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$qnt."';";
				$resY  = mssql_query($qryY);
				$rowY  = mssql_fetch_array($resY);
			}
	
			echo "	<table class=\"outer\" width=\"950px\" align=\"center\">\n";
			echo "		<tr>\n";
			echo "			<td colspan=\"16\">\n";
			echo "				<table width=\"100%\" align=\"center\">\n";
			echo "      			<form method=\"post\">\n";
			echo "      			<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
			echo "     				<input type=\"hidden\" name=\"call\" value=\"pbanalyze\">\n";
			echo "      			<input type=\"hidden\" name=\"subq\" value=\"list\">\n";
			echo "					<tr>\n";
			echo "						<td class=\"ltgray_und\" colspan=\"5\" align=\"right\">\n";
			echo "							<table align=\"right\">\n";
			echo "								<tr>\n";
			echo "									<td align=\"right\">&nbsp<b>Color Codes:</b></td>\n";
			echo "									<td align=\"center\" class=\"blu_und\"><b>Category</b></td>\n";
			echo "									<td align=\"center\" class=\"gray_und\"><b>Retail Item</b></td>\n";
			echo "									<td align=\"center\" class=\"wh_und\"><b>Cost Item</b></td>\n";
			echo "									<td align=\"center\" class=\"yel_und\"><b>Changed</b></td>\n";
			echo "									<td align=\"center\" class=\"red_und\"><b>Alarm</b></td>\n";
			echo "								</tr>\n";
			echo "							</table>\n";
			echo "						</td>\n";
			echo "			 		</tr>\n";
			echo "					<tr>\n";
			echo "						<td align=\"left\" class=\"gray\"><b>Pricebook Analysis for ".$_SESSION['offname']."</b></td>\n";
			echo "            			<td class=\"gray\" align=\"center\">\n";
			
			quan_sess_set();
			
			echo "						</td>\n";
			echo "						<td align=\"right\" class=\"gray\">\n";
			echo "               					<select name=\"catid\" onChange=\"this.form.submit();\">\n";
			echo "                  						<option value=\"0\">None</option>\n";
	
			while ($rowB = mssql_fetch_array($resB))
			{
				if (isset($_POST['catid']) && !empty($_POST['catid']) && $_POST['catid']==$rowB[0])
				{
					echo "                  						<option value=\"".$rowB[0]."\" SELECTED>".$rowB[1]."</option>\n";
				}
				else
				{
					echo "                  						<option value=\"".$rowB[0]."\">".$rowB[1]."</option>\n";
				}
			}
	
			echo "            			</td>\n";
			echo "            			<td class=\"gray\" align=\"right\" >\n";
			echo "   						<input class=\"transnb_button\" type=\"image\" src=\"images/arrow_refresh.png\" alt=\"Refresh\">\n";
			echo "						</td>\n";
			echo "					</tr>\n";
			echo " 				</table>\n";
			echo "			</td>\n";
			echo "		</tr>\n";
			echo "		<tr>\n";
			echo "			<td align=\"left\" class=\"ltgray_und\" colspan=\"6\">&nbsp</td>\n";
			echo "			<td align=\"center\" class=\"ltgray_sidesb\" colspan=\"5\"><b>Current Pricebook</b></td>\n";
			echo "			<td align=\"center\" class=\"ltgray_sidesb\" colspan=\"4\"><b>Profit Analyzer</b></td>\n";
			echo "			<td align=\"left\" class=\"ltgray_und\" >&nbsp</td>\n";
			echo " 		</tr>\n";
			echo "		<tr>\n";
			echo "			<td align=\"center\" class=\"ltgray_und\"><b>Code</b></td>\n";
			echo "			<td align=\"center\" class=\"ltgray_und\" title=\"".$typetext."\"><b>Type</b></td>\n";
			echo "			<td align=\"left\" class=\"ltgray_und\"><b>Name</b></td>\n";
			echo "			<td align=\"left\" class=\"ltgray_und\"><b>Phase</b></td>\n";
			echo "			<td align=\"center\" class=\"ltgray_und\"><b>Units</b>&nbsp</td>\n";
			echo "			<td align=\"center\" class=\"ltgray_und\"><b>Quan</b>&nbsp</td>\n";
			echo "			<td align=\"right\" class=\"ltgray_sidesbl\"><b>Retail</b></td>\n";
			echo "			<td align=\"right\" class=\"ltgray_und\"><b>Cost</b></td>\n";
			echo "			<td align=\"right\" class=\"ltgray_und\"><b>Profit</b></td>\n";
			echo "			<td align=\"right\" class=\"ltgray_und\"><b>GP</b>&nbsp</td>\n";
			echo "			<td align=\"right\" class=\"ltgray_und\"><b>Alarm</b>&nbsp</td>\n";
			echo "			<td align=\"right\" class=\"ltgray_sidesbl\"><b>Retail</b></td>\n";
			echo "			<td align=\"right\" class=\"ltgray_und\"><b>Cost</b></td>\n";
			echo "			<td align=\"right\" class=\"ltgray_und\"><b>Profit</b></td>\n";
			echo "			<td align=\"right\" class=\"ltgray_sidesbr\"><b>GP</b>&nbsp</td>\n";
			echo "			<td align=\"left\" class=\"ltgray_und\" >&nbsp</td>\n";
			echo " 		</tr>\n";
			
			$icnt=0;
			while($row = mssql_fetch_array($res))
			{
				$icnt++;
				$Cclass="ltgray_und";
				$Sclass="ltgray_nound";
				
				$qryX1 = "SELECT abrv FROM mtypes WHERE mid='".$row['mtype']."';";
				$resX1  = mssql_query($qryX1);
				$rowX1  = mssql_fetch_array($resX1);
	
				if ($row['qtype'] == 55 || $row['qtype'] == 72)
				{
					if ($row['poolcalc'] == 1)
					{
						$rp		=$rowY['price']+$row['rp'];
						$comm		=$rowY['comm'];
					}
					else
					{
						$rp		=$row['rp'];
						$comm		=comm_calcpa($row['qtype'],$row['commtype'],$row['rp'],$row['crate'],$quan_out);
					}
					$quan_out=1;
				}
				else
				{
					$amts	 =get_def_calc_amt($row['qtype'],$q1,$q2,$q3,$q4,$q5,$row['quan_calc'],0,$q8,$row['atrib1'],$row['atrib2'],$row['atrib3']);
					$calc_out=uni_calc_loop($row['qtype'],0,$row['rp'],$row['lrange'],$row['hrange'],$amts[0],$row['quan_calc'],$amts[7],$amts[8],0,0,0,$row['atrib1'],$row['atrib2'],$row['atrib3'],0,0);
					$rp		=$calc_out[1];
					$quan_out=$calc_out[2];
					$comm		=comm_calcpa($row['qtype'],$row['commtype'],$row['rp'],$row['crate'],$quan_out);
				}
	
				$bp	=$row['bp'];
	
				if ($rp!=0)
				{
					$rbp	=getrelatedcost($row['id'],$row['def_quan'],$row['lrange'],$row['hrange'],$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$rp,$comm,$row['id']);
					$df	=$rp-$rbp[0];
					$gp	=$df/$rp;
				}
				else
				{
					$rbp	=array(0,0);
					$df	=0;
					$gp	=0;
				}
	
				$frp	=number_format($rp, 2, '.', '');
				$fbp	=number_format($rbp[0], 2, '.', '');
				$fdf	=number_format($df, 2, '.', '');
				$fgp	=round($gp, 2)*100;
	
				if ($row['qtype']!=32)
				{
					$Rclass="gray_und";
				}
				else
				{
					$Rclass="blu_und";
				}
	
				echo "         <tr>\n";
				echo "            <td align=\"right\" class=\"".$Rclass."\">\n";
	
				if ($row['qtype']!=32)
				{
					echo $row['aid'];
				}
				else
				{
					echo "Category";
				}
	
				echo "		</td>\n";
				echo "            <td align=\"center\" class=\"".$Rclass."\">\n";
	
				if ($row['qtype']!=32)
				{
					if ($row['qtype']==55 || $row['qtype']==72)
					{
						echo "RP";
					}
					else
					{
						echo "R";
					}
				}
				
				echo "				</td>\n";
				echo "            <td align=\"left\" class=\"".$Rclass."\">\n";
	
				if ($row['qtype']==32)
				{
					echo "<b>".$row['item']."</b>";
				}
				else
				{
					echo "&nbsp;&nbsp;&nbsp;".$row['item'];
				}
	
				echo "				</td>\n";
				echo "            <td align=\"right\" class=\"".$Rclass."\"></td>\n";
				echo "            <td align=\"center\" class=\"".$Rclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					echo $rowX1['abrv'];
				}
	
				echo "		</b></td>\n";
				echo "            <td align=\"center\" class=\"".$Rclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					echo $quan_out;
				}
	
				echo "		</b></td>\n";
				
				if ($row['qtype']!=32)
				{
					$lRclass="gray_undsidesl";
				}
				else
				{
					$lRclass="blu_undsidesl";
				}
				// Current Pricebook
				echo "            <td align=\"right\" class=\"".$lRclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					echo $frp;
				}
	
				echo "		</b></td>\n";
				echo "            <td align=\"right\" class=\"".$Rclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					echo $fbp;
				}
	
				echo "	</b></td>\n";
				echo "	<td align=\"right\" class=\"".$Rclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					echo $fdf;
				}
	
				echo "		</b></td>\n";
				echo "            <td align=\"right\" class=\"".$Rclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					echo $fgp."%";
				}
	
				echo "		</b></td>\n";
				
				if ($row['qtype']!=32)
				{
					$rRclass="gray_undsidesr";
				}
				else
				{
					$rRclass="blu_undsidesr";
				}
				
				echo "            <td align=\"right\" class=\"".$rRclass."\"><b>\n";
	
				if ($row['qtype']!=32 && $row['profperc']!=0)
				{
					echo ($row['profperc'] * 100)."%";
				}
	
				echo "		</b></td>\n";
				
				// Profit Analyzer
				$afrp	=0;
				$afclp=0;
				$afcmp=0;
				$afccp=0;
				$afcrp=0;
				$afbp =0;
				$afrc	=0;
				$aprf	=0;
				$aprc	=0;
				// Retail
				if (array_key_exists("Racc".$row['id'],$_POST))
				{
					$afrp=$_POST['Racc'.$row['id']];
					$afrc=$_POST['Racc'.$row['id']]*.03;
					
					//Labor Cost
					if (array_key_exists("CLacc".$row['id'],$_POST))
					{
						if (is_array($_POST['CLacc'.$row['id']]))
						{
							$afclp	=array_sum($_POST['CLacc'.$row['id']]);
						}
						else
						{
							$afclp	=$_POST['CLacc'.$row['id']];
						}
					}
					
					//Material Cost
					if (array_key_exists("CMacc".$row['id'],$_POST))
					{
						if (is_array($_POST['CMacc'.$row['id']]))
						{
							$afcmp	=array_sum($_POST['CMacc'.$row['id']]);
						}
						else
						{
							$afcmp	=$_POST['CMacc'.$row['id']];
						}
					}
					
					if (array_key_exists("CCacc".$row['id'],$_POST))
					{
						if (is_array($_POST['CCacc'.$row['id']]))
						{
							$afccp	=array_sum($_POST['CCacc'.$row['id']]);
						}
						else
						{
							$afccp	=$_POST['CCacc'.$row['id']];
						}
					}
					
					$afbp	=$afclp+$afcmp+$afccp+$afrc;
					$aprf =$afrp - $afbp;
					$aprc =round(($aprf/$afrp), 2) * 100;
					//echo "TESTR : ".$afrp."<br>";
					//echo "TESTCL: ".$afclp."<br>";
					//echo "TESTCM: ".$afcmp."<br>";
					//echo "TESTCC: ".$afccp."<br>";
					//echo "TESTCR: ".$afrc."<br>";
					//echo "TESTCS: ".$afbp."<br>";
					//echo "TESTPR: ".$aprf."<br>";
					//echo "TESTCP: ".$aprc."<br>----<br>";
					
				}
				
				if ($row['qtype']!=32)
				{
					if (array_key_exists("Racc".$row['id'],$_POST) && $aprc < ($row['profperc'] * 100))
					{
						$pRclass="red_und";
					}
					elseif (array_key_exists("Racc".$row['id'],$_POST) && $_POST['Racc'.$row['id']] != $frp)
					{
						$pRclass="yel_und";
					}
					else
					{
						$pRclass="gray_und";
					}
				}
				else
				{
					$pRclass="blu_und";
				}
				
				echo "            <td align=\"right\" class=\"".$pRclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					if ($afrp != 0 && $afrp != $frp)
					{
						echo "<input class=\"bboxbr\" type=\"text\" name=\"Racc".$row['id']."\" value=\"".number_format($afrp, 2, '.', '')."\" size=\"6\" maxlength=\"9\">\n";
					}
					else
					{
						echo "<input class=\"bboxbr\" type=\"text\" name=\"Racc".$row['id']."\" value=\"".$frp."\" size=\"6\" maxlength=\"9\">\n";
					}
					//echo $frp;
				}
	
				echo "		</b></td>\n";
				echo "            <td align=\"right\" class=\"".$pRclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					if ($afbp != 0 && $afbp != $fbp)
					{
						echo number_format($afbp, 2, '.', '');
					}
					else
					{
						echo $fbp;
					}
				}
	
				echo "	</b></td>\n";
				echo "	<td align=\"right\" class=\"".$pRclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					if ($aprf != 0 && $aprf != $fdf)
					{
						echo number_format($aprf, 2, '.', '');
					}
					else
					{
						echo $fdf;
					}
				}
	
				echo "		</b></td>\n";
				echo "            <td align=\"right\" class=\"".$pRclass."\"><b>\n";
	
				if ($row['qtype']!=32)
				{
					if ($aprc != 0 && $aprc != $fgp)
					{
						//echo "HIT";
						echo $aprc."%";
					}
					else
					{
						echo $fgp."%";
					}
				}
				
				echo "		</b></td>\n";
				echo "            <td align=\"center\" class=\"".$lRclass."\">\n";
	
				if ($row['qtype']!=32)
				{
					//echo "            <input class=\"buttondkgry\" type=\"submit\" value=\"Analyze\">\n";
					echo "   			<input class=\"transnb_button\" type=\"image\" src=\"images/arrow_refresh.png\" alt=\"Analyze\">\n";
				}
	
				echo "				</td>\n";
				echo "         </tr>\n";
				// End Profit Analyzer Calcs
	
				if(isset($rbp[2]) || isset($rbp[3]))
				{
					if(isset($rbp[2]) || isset($rbp[3]))
					{
						displayroycomm($frp,$comm,$row['id']);
					}
	
					// Labor Alloc
					if(isset($rbp[2]) && $rbp[2] != 0)
					{
						displayrelatedlabcost($row['id'],$row['def_quan'],$row['lrange'],$row['hrange'],$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$frp,$comm,$row['id']);
					}
	
					// Material Alloc
					if(isset($rbp[3]) && $rbp[3] != 0)
					{
						displayrelatedmatcost($row['id'],$row['def_quan'],$row['lrange'],$row['hrange'],$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$frp,$row['id']);
					}
	
					/*echo "		<tr>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo "			<td align=\"left\" class=\"".$Sclass."\">&nbsp</td>\n";
					echo " 		</tr>\n";*/
				}
			}
			echo "      </table>\n";
			echo "            </form>\n";
		}
	}
	else
	{
		echo "<b>You do not have the appropriate Access Level to view this resource</b>";
		exit;
	}
}

?>