<?php

function format_div($div)
{
	if (strlen($div)==1)
	{
		$div="0".$div;
	}

	return $div;
}

function get_qbwcXML_config($oid)
{
	$dout='';
	$qry = "SELECT * FROM qbwcConfig WHERE oid=".$oid.";";
	$res = mssql_query($qry);
	$row= mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	if ($nrow > 0)
	{		
		$dout .= generate_qbwcXML($row);
	}
	else
	{
		$dout .= '<Error>No qbwc Record found</Error>';
	}
	
	return $dout;
}

function generate_qbwcXML($qbwcdata)
{
	$xmlout='';
	
	if (is_array($qbwcdata))
	{
		//echo '<pre>';
		//echo 'INARRAY';
		
		$xmlout .= "<?xml version=\"1.0\"?>" . SYS_CR_LF;
		$xmlout .= '<QBWCXML>' . SYS_CR_LF;
		
		if (isset($qbwcdata['AppName']) and strlen($qbwcdata['AppName']) > 1)
		{
			$xmlout .= "\t" . '<AppName>' . htmlspecialchars(trim($qbwcdata['AppName'])) . '</AppName>' . SYS_CR_LF;
		}
		
		if (isset($qbwcdata['AppID']) and strlen($qbwcdata['AppID']) > 1)
		{
			$xmlout .= "\t" . '<AppID>' . htmlspecialchars(trim($qbwcdata['AppID'])) . '</AppID>' . SYS_CR_LF;
		}
		
		if (isset($qbwcdata['AppURL']) and strlen($qbwcdata['AppURL']) > 1)
		{
			$xmlout .= "\t" . '<AppURL>' . htmlspecialchars(trim($qbwcdata['AppURL'])) . '</AppURL>' . SYS_CR_LF;
		}
		
		if (isset($qbwcdata['AppDescription']) and strlen($qbwcdata['AppDescription']) > 1)
		{
			$xmlout .= "\t" . '<AppDescription>' . htmlspecialchars(trim($qbwcdata['AppDescription'])) . '</AppDescription>' . SYS_CR_LF;
		}
		
		if (isset($qbwcdata['AppSupport']) and strlen($qbwcdata['AppSupport']) > 1)
		{
			$xmlout .= "\t" . '<AppSupport>' . htmlspecialchars(trim($qbwcdata['AppSupport'])) . '</AppSupport>' . SYS_CR_LF;
		}
		
		if (isset($qbwcdata['UserName']) and strlen($qbwcdata['UserName']) > 1)
		{
			$xmlout .= "\t" . '<UserName>' . htmlspecialchars(trim($qbwcdata['UserName'])) . '</UserName>' . SYS_CR_LF;
		}
		
		if (isset($qbwcdata['OwnerID']) and strlen($qbwcdata['OwnerID']) > 1)
		{
			$xmlout .= "\t" . '<OwnerID>' . trim($qbwcdata['OwnerID']) . '</OwnerID>' . SYS_CR_LF;
		}
		
		if (isset($qbwcdata['FileID']) and strlen($qbwcdata['FileID']) > 1)
		{
			$xmlout .= "\t" . '<FileID>' . trim($qbwcdata['FileID']) . '</FileID>' . SYS_CR_LF;
		}
		
		if (isset($qbwcdata['QBType']) and strlen($qbwcdata['QBType']) > 1)
		{
			$xmlout .= "\t" . '<QBType>' . trim($qbwcdata['QBType']) . '</QBType>' . SYS_CR_LF;
		}
			
		if (isset($qbwcdata['PersonalDataPref']) and strlen($qbwcdata['PersonalDataPref']) > 1)
		{
			$xmlout .= "\t" . '<PersonalDataPref>' . trim($qbwcdata['PersonalDataPref']) . '</PersonalDataPref>' . SYS_CR_LF;
		}
			
		if (isset($qbwcdata['UnattendedModePref']) and strlen($qbwcdata['UnattendedModePref']) > 1)
		{
			$xmlout .= "\t" . '<UnattendedModePref>' . trim($qbwcdata['UnattendedModePref']) . '</UnattendedModePref>' . SYS_CR_LF;
		}
			
		if (isset($qbwcdata['AuthFlags']) and strlen($qbwcdata['AuthFlags']) > 1)
		{
			$xmlout .= "\t" . '<AuthFlags>' . trim($qbwcdata['AuthFlags']) . '</AuthFlags>' . SYS_CR_LF;
		}
		
		/*
		if ($qbwcdata['Notify']==3)
		{
			$xmlout .= "\t" . '<Notify>true</Notify>' . SYS_CR_LF;
		}
		else
		{
			$xmlout .= "\t" . '<Notify>false</Notify>' . SYS_CR_LF;
		}
		*/
			
		if (isset($qbwcdata['AppDisplayName']) and strlen($qbwcdata['AppDisplayName']) > 1)
		{
			$xmlout .= "\t" . '<AppDisplayName>' . trim($qbwcdata['AppDisplayName']) . '</AppDisplayName>' . SYS_CR_LF;
		}
			
		if (isset($qbwcdata['AppUniqueName']) and strlen($qbwcdata['AppUniqueName']) > 1)
		{
			$xmlout .= "\t" . '<AppUniqueName>' . trim($qbwcdata['AppUniqueName']) . '</AppUniqueName>' . SYS_CR_LF;
		}
		
		/*
		if ((int) $qbwcdata['Scheduler'] > 0 and (int) $qbwcdata['Scheduler'] < 60)
		{
			$xmlout .= "\t" . '<Scheduler>' . SYS_CR_LF;
			$xmlout .= "\t" . "\t" . '<RunEveryNSeconds>' . (int) trim($qbwcdata['Scheduler']) . '</RunEveryNSeconds>' . SYS_CR_LF;
			$xmlout .= "\t" . '</Scheduler>' . SYS_CR_LF;
		}
		elseif ((int) $qbwcdata['Scheduler'] >= 60)
		{
			$xmlout .= "\t" . '<Scheduler>' . SYS_CR_LF;
			$xmlout .= "\t" . "\t" . '<RunEveryNMinutes>' . floor(trim($qbwcdata['Scheduler']) / 60) . '</RunEveryNMinutes>' . SYS_CR_LF;
			$xmlout .= "\t" . '</Scheduler>' . SYS_CR_LF;
		}
		*/
			
		if ($qbwcdata['IsReadOnly']==1)
		{
			$xmlout .= "\t" . '<IsReadOnly>true</IsReadOnly>' . SYS_CR_LF;
		}
		else
		{
			$xmlout .= "\t" . '<IsReadOnly>false</IsReadOnly>' . SYS_CR_LF;
		}
		
		$xmlout .= '</QBWCXML>';
		
	}
	
	//echo $xmlout;
	
	return trim($xmlout);
}

function XML_poll()
{
	$qry0 = "SELECT * FROM masstatus WHERE sstatus <='1' AND tattempt <='3' AND njobid!='0';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		while ($row0= mssql_fetch_array($res0))
		{
			XML_content($row0['officeid'],$row0['njobid']);
			UPDATE_job_send_status($row0['officeid'],$row0['njobid'],2);
		}
	}
	else
	{
		declare_XML("1.0","ISO-8859-1");
		single_element("ERROR","No Jobs");
	}
}

function calc_comm_XML($jobid,$oid)
{
	$qry0 = "SELECT * FROM jobs WHERE officeid='".$oid."' AND njobid='".$jobid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT isNull(SUM(amt),0) as tamt FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$row0['jobid']."' and cbtype!=4;";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	if ($row1['tamt'] > 0)
	{
		$adjcomm	=$row1['tamt'];
	}
	else
	{
		$adjcomm	=$row0['comm']+$row0['ovcommission'];	
	}
	
	return $adjcomm;
}

function calc_smcomm_XML($jobid,$oid)
{
	$qry0 = "SELECT * FROM jobs WHERE officeid='".$oid."' AND njobid='".$jobid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT isNull(SUM(amt),0) as tamt FROM jest..CommissionSchedule WHERE oid='".$_SESSION['officeid']."' AND jobid='".$row0['jobid']."' and cbtype=4;";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	if ($row1['tamt'] > 0)
	{
		$adjcomm	=number_format($row1['tamt'], 2, '.', '');
	}
	else
	{
		$adjcomm	=number_format(0, 2, '.', '');
	}
	
	return $adjcomm;
}

function calc_comm_XML_contract_add($jobid,$oid)
{
	$adjcomm=0;
	//$qry0 = "SELECT raddncm AS comm FROM jdetail WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd > 0;";
	//$res0 = mssql_query($qry0);
	//$nrow0= mssql_num_rows($res0);

	//echo "CON: ".$qry0."<br>";

	//if ($nrow0 > 0)
	//{
	//	while ($row0= mssql_fetch_array($res0))
	//	{
	//		$adjcomm=$adjcomm+$row0['comm'];
	//	}
	//}

	return $adjcomm;
}

function calc_comm_XML_job_add($jobid,$oid)
{
	$adjcomm=0;
	$tmpadj=0;
	$qry0 = "SELECT raddncm,raddncm_man FROM jdetail WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd > 0;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	//echo "JOB: ".$qry0."<br>";

	if ($nrow0 > 0)
	{
		while ($row0= mssql_fetch_array($res0))
		{
			if ($row0['raddncm_man']==0)
			{
				$tmpadj=$row0['raddncm'];
			}
			else
			{
				$tmpadj=$row0['raddncm_man'];
			}

			$adjcomm=$adjcomm+$tmpadj;
		}
	}

	//echo "ADJ: ".$adjcomm."<br>";
	return $adjcomm;
}

function calc_royalty_XML($camt,$pcode,$jobid,$jadd,$oid)
{
	$troyrel=0;

	$qry0 = "SELECT * FROM jbids WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd='".$jadd."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			$qry1 = "SELECT * FROM [".$pcode."rclinks_l] WHERE officeid='".$oid."' AND rid='".$row0['dbid']."';";
			$res1 = mssql_query($qry1);
			$nrow1= mssql_num_rows($res1);

			//echo "<pre>".$qry1."</pre>";

			if ($nrow1 > 0)
			{
				while ($row1 = mssql_fetch_array($res1))
				{
					$qry2 = "SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$oid."' AND id='".$row1['cid']."';";
					$res2 = mssql_query($qry2);
					$nrow2= mssql_num_rows($res2);

					if ($nrow2 > 0)
					{
						$row2 = mssql_fetch_array($res2);
						if ($row2['royrelease']==1)
						{
							$troyrel=$troyrel+$row0['bidamt'];
						}
					}
				}
			}
		}

		$roy		=($camt-$troyrel)*.03;
	}
	else
	{
		$roy		=$camt*.03;
	}

	$roy=round($roy);
	return $roy;
}

function declare_XML($ver,$enc)
{
	$XML_text	="<?xml version='".$ver."' encoding='".$enc."' ?>";
	return $XML_text;
}

function multi($elem)
{
	$XML_text	="<".$elem.">";
	//$XML_text	="[".$elem."]";
	return $XML_text;
}

function multi_id($elem,$id)
{
	if (strlen($id) < 1)
	{
		$XML_text	="<".$elem." id=\"000000\">";
		//$XML_text	="[".$elem." id=\"000000\"]";
	}
	else
	{
		$XML_text	="<".$elem." id=\"".$id."\">";
		//$XML_text	="[".$elem." id=\"".$id."\"]";
	}
	return $XML_text;
}

function multi_start($elem)
{
	$XML_text	="<".$elem.">";
	//$XML_text	="[".$elem."]";
	return $XML_text;
}

function multi_stop($elem)
{
	$XML_text	="</".$elem.">";
	//$XML_text	="[/".$elem."]";
	return $XML_text;
}

function single_element($elem,$data)
{
	$XML_text	="<".$elem.">".replaceamp($data)."</".$elem.">";
	//$XML_text	="[".$elem."]".replaceamp($data)."[/".$elem."]";
	return $XML_text;
}

function single_element_nstrip($elem,$data)
{
	$XML_text	="<".$elem.">".$data."</".$elem.">";
	//$XML_text	="[".$elem."]".$data."[/".$elem."]";
	return $XML_text;
}

function pay_sched($phs,$amt,$oid,$jobid)
{
	$psched_phs=explode(",",$phs);
	$psched_amt=explode(",",$amt);

	if (is_array($psched_phs))
	{
		$qry0 = "SELECT * FROM jdetail WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd!='0' ORDER by jadd ASC;";
		$res0 = mssql_query($qry0);
		$nrow0 = mssql_num_rows($res0);

		$XML_text	=multi("PAYMENT_SCHEDULE");

		foreach ($psched_phs as $n => $v)
		{
			//Start Loop
			$XML_text	.=multi_id("Phase_ID",$v);
			$XML_text	.=	single_element("Total",trim($psched_amt[$n]));
			$XML_text	.=multi("/Phase_ID");
		}

		if ($nrow0 > 0)
		{
			while ($row0 = mssql_fetch_array($res0))
			{
				$addid="60".$row0['jadd']."L";
				$XML_text	.=multi_id("Phase_ID",$addid);
				$XML_text	.=	single_element("Total",trim($row0['psched_adj']));
				$XML_text	.=multi("/Phase_ID");
			}
		}

		$XML_text	.=multi("/PAYMENT_SCHEDULE");
	}
	return $XML_text;
}

function lab_credititem_XML($id,$quan,$pb_code,$offid,$lab,$plab)
{
	global $viewarray;

	$bpi=0;

	if ($pb_code==0)
	{
		$pcode="";
	}
	else
	{
		$pcode=$pb_code;
	}

	//echo $lab."<br>";

	$do1		=explode(",",$lab);
	foreach($do1 as $n1 => $v1)
	{
		$di1=explode(":",$v1);
		//echo "LV1 - ".$v1." ($id)<br>";
		if ($di1[1]==$id)
		{
			$bpi=$di1[3];
		}
	}

	$do2		=explode(",",$plab);
	foreach($do2 as $n2 => $v2)
	{
		$di2=explode(":",$v2);
		//echo "PLV2 - ".$v2." ($id)<br>";
		if ($di2[5]==$id)
		{
			$bpi=$di2[8];
		}
	}

	$iarea		=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$gals		=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);

	$qry 			= "SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$offid."' AND id='".$id."';";
	$res 			= mssql_query($qry);
	$row 		= mssql_fetch_array($res);

	$qry1 		= "SELECT phscode FROM phasebase WHERE phsid='".$row['phsid']."';";
	$res1 		= mssql_query($qry1);
	$row1 		= mssql_fetch_array($res1);

	$qry2 		= "SELECT abrv FROM mtypes WHERE mid='".$row['mtype']."';";
	$res2 		= mssql_query($qry2);
	$row2 		= mssql_fetch_array($res2);

	$subbp      	= $bpi;
	$fsubbp		= number_format($subbp, 2, '.', '');

	$subitem		=$row['item']." (Credit)";
	$subquan		=$quan;
	$lr			=$row['lrange'];
	$hr			=$row['hrange'];
	$cr			=1;
	$code		=0;

	$calc_out		=uni_calc_loop($row['qtype'],$subbp,0,$lr,$hr,$quan,$row['quantity'],$iarea,$gals,0,0,$code,0,0,0);
	$bp			=round($calc_out[0]);
	$quan_out	=$calc_out[2];

	$bp      		=$bp*-1;

	//echo "BP: (".$bpi.") (".$bp.")<br>";
	
	$fbp			=number_format($bp, 2, '.', '');

	$XML_test=cost_elem($row['accid'],$row1['phscode'],$subitem,$fsubbp,$fbp,$subquan,$row2['abrv']);

	$phsbcrc=array(0=>$bp,1=>$quan_out,2=>$subitem,3=>$XML_test);
	return $phsbcrc;
}

function mat_credititem_XML($id,$quan,$pb_code,$offid)
{
	global $viewarray;

	if ($pb_code==0)
	{
		$pcode="";
	}
	else
	{
		$pcode=$pb_code;
	}

	$iarea		=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$gals			=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);

	$qry = "SELECT * FROM [".$pcode."inventory] WHERE officeid='".$offid."' AND invid='".$id."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1 = "SELECT phscode FROM phasebase WHERE phsid='".$row['phsid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	$qry2 = "SELECT abrv FROM mtypes WHERE mid='".$row['mtype']."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	if ($row['matid']!=0)
	{
		$qrya 	= "SELECT bp,vpnum FROM material_master WHERE id='".$row['matid']."';";
		$resa 	= mssql_query($qrya);
		$rowa 	= mssql_fetch_array($resa);

		$subbp   =round($rowa['bp']*-1);
		$matpn   =$rowa['vpnum'];
		//echo "MATPn355: ".$matpn."<br>";
	}
	else
	{
		$subbp   =round($row['bprice']*-1);
		$matpn   ="0";
	}

	$fsubbp		=number_format($subbp, 2, '.', '');
	$subrp      =0;
	$subphsid   =$row['phsid'];
	$subitem    =$row['item']." (Credit)";
	$subatrib1  =$row['atrib1'];
	$subatrib2  =$row['atrib2'];
	$subatrib3  =$row['atrib3'];
	$subquan    =$quan;
	$lr			=0;
	$hr			=0;
	$cr         =1;
	$code       =0;

	$calc_out	=uni_calc_loop($row['qtype'],$subbp,0,$lr,$hr,$quan,$row['quan_calc'],$iarea,$gals,0,0,$code,0,0,0);
	$bp			=round($calc_out[0]);
	$quan_out	=$calc_out[2];

	$fbp			=number_format($bp, 2, '.', '');

	$XML_test	=cost_elem_mat($row['accid'],$row1['phscode'],$subitem,$fsubbp,$fbp,$subquan,$row2['abrv'],$matpn);

	$phsbcrc=array(0=>$bp,1=>$quan_out,2=>$subitem,3=>$XML_test);
	return $phsbcrc;
}

function cost_items($oid,$pb_code,$jobid,$jadd,$camt,$lab,$mat,$blab,$bmat,$plab,$pmat)
{
	global $viewarray;
	
	//echo '<pre>';
	
	//print_r($viewarray);
	
	//echo '</pre>';*/
	
	error_reporting(0);
	//error_reporting(E_ALL);

	$iarea	=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$gals	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);

	$bc		=0;
	$bcl	=0;
	$bcm	=0;
	$bclp	=0;
	$bcmp	=0;
	$bclb	=0;
	$bcmb	=0;
	$matpn="0";

	//echo " LAB: ".$lab."<br>";
	//echo " MAT: ".$mat."<br>";
	//echo "BLAB: ".$blab."<br>";
	//echo "BMAT: ".$bmat."<br>";
	//echo "PLAB: ".$plab."<br>";
	//echo "PMAT: ".$pmat."<br>";

	if ($pb_code==0)
	{
		$pcode="";
	}
	else
	{
		$pcode=$pb_code;
	}

	if ($lab=="Array")
	{
		$lab="";
	}

	if ($mat=="Array")
	{
		$mat="";
	}

	if ($blab=="Array")
	{
		$blab="";
	}

	if ($bmat=="Array")
	{
		$bmat="";
	}

	if ($plab=="Array")
	{
		$plab="";
	}

	if ($pmat=="Array")
	{
		$pmat="";
	}

	//multi("COST_ITEMS");
	//Start Loop

	$XML_text="";

	$qryA = "SELECT phsid,phscode FROM phasebase WHERE costing=1 ORDER by XMLseq;";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);

	if ($nrowA > 0)
	{
		while ($rowA = mssql_fetch_array($resA))
		{
			$XML_text	.=multi_id("PHS_CODE",$rowA['phscode']);

			if (strlen($lab) > 3) // Labor Items
			{
				$do=explode(",",$lab);
				//echo "<lab>".$lab."</lab>";

				foreach($do as $n1 => $v1)
				{
					$di=explode(":",$v1);

					$qry1 = "SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$oid."' AND phsid='".$rowA['phsid']."' AND id='".$di[1]."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);
					//echo $qry1."<br>";
					$qry2 = "SELECT phscode FROM phasebase WHERE phsid='".$row1['phsid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);
					
					if (!empty($row1['accid']) && $row1['qtype']!=33)
					{
						if ($di[9]!=0)  // Credit Code Loop
						{
							$cr_out		=lab_credititem_XML($di[9],$di[2],$pcode,$oid,$lab,$plab);
							$bpl		=$cr_out[0];
							$bcl		=$bcl+$bpl;
							$XML_text	.=$cr_out[3];
						}

						$calc_out	=uni_calc_loop($row1['qtype'],$di[3],0,$row1['lrange'],$row1['hrange'],$di[2],$row1['quantity'],$viewarray['ia'],$viewarray['gl'],0,0,0,0,0,0);
						$bpl		=$calc_out[0];
						$quan_out	=$calc_out[2];
						$fbpl		=number_format($bpl, 2, '.', '');
						$bcl		=$bcl+$bpl;
						$XML_text	.=cost_elem($row1['accid'],$row2[0],$row1['item'],$di[3],$fbpl,$quan_out,$row3['abrv']);
					}
				}
			}

			if (strlen($plab) > 3) // Package Labor Items
			{
				$do=explode(",",$plab);
				//echo "<Plab>".$plab."</Plab>";

				foreach($do as $n1 => $v1)
				{
					$di=explode(":",$v1);
					//cost_elem($id,$p,$i,$i1,$i2,$i3,$urp,$trp,$q,$u)

					//print_r($di);
					//echo "<BR>";

					$qry1 = "SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$oid."' AND phsid='".$rowA['phsid']."' AND id='".$di[5]."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					//echo $qry1."<br>";

					$qry2 = "SELECT phscode FROM phasebase WHERE phsid='".$row1['phsid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);

					if ($row1['qtype']!=33)
					{
						if (!empty($row1['accid']))
						{
							//ECHO "<font color=\"blue\">PABOR: ".$row1['accid'].":".$row1['rinvid']."</font><br>";
							if ($row1['rinvid']!=0)  // Credit Code Loop
							{
								//ECHO "<font color=\"red\">CREDIT</font><br>";
								$cr_out		=lab_credititem_XML($row1['rinvid'],$di[2],$pcode,$oid,$lab,$plab);
								$bpl			=$cr_out[0];
								$bcl			=$bcl+$bpl;
								$XML_text	.=$cr_out[3];
							}

							//echo $row1['item']." (PLAB)<br>";
							//print_r($di);
							//echo "<p>";
							//uni_calc_loop($qtype,$bp,$rp,$lr,$hr,$quan,$def_quan,$iarea,$gals,$spa_ia,$spa_gl,$code,$a1,$a2,$a3)
							//$calc_out	=uni_calc_loop($row1['qtype'],$di[8],0,$row1['lrange'],$row1['hrange'],$di[2],$row1['quantity'],0,0,0,0,0,0,0,0);
							//$calc_out	=uni_calc_loop($row1['qtype'],$di[8],0,$row1['lrange'],$row1['hrange'],$di[4],$row1['quantity'],0,0,0,0,0,0,0,0);
							$calc_out	=uni_calc_loop($di[7],$di[8],0,$row1['lrange'],$row1['hrange'],$di[4],$row1['quantity'],$viewarray['ia'],$viewarray['gl'],0,0,0,0,0,0);
							$bplp			=$calc_out[0];
							$quan_out	=$calc_out[2];
							$fbplp		=number_format(round($bplp), 2, '.', '');
							$bclp			=$bclp+$bplp;
							//print_r($calc_out);
							//echo "<p>";

							$XML_text	.=cost_elem($row1['accid'],$row2[0],$row1['item'],$di[8],$fbplp,$quan_out,$row3['abrv']);
							//cost_elem($row1['accid'],$row2[0],$row1['item'],$di[8],$fbplp,$quan_out,$v1);
						}
					}
					else
					{
						$qryAa = "SELECT * FROM jbids_breakout WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd='".$jadd."' AND rdbid='".$di[0]."' AND cdbid='".$di[1]."';";
						$resAa = mssql_query($qryAa);
						$nrowAa= mssql_num_rows($resAa);

						//echo $nrowA;

						if ($nrowAa > 0)
						{
							while ($rowAa = mssql_fetch_array($resAa))
							{
								$XML_text	.=cost_elem($row1['accid'],$row2[0],"Bid Item: ".removequote($rowAa['sdesc']),$rowAa['bprice'],$rowAa['bprice'],1,"fx");
								$bcl	=$bcl+$rowAa['bprice'];
								//echo "Bid Item: ".$rowAa['sdesc']." (PLAB (BID BREAKOUT))<br>";
								//print_r($di);
								//echo "<p>";
							}
						}
						else
						{
							$qryBa = "SELECT * FROM jbids WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd='".$jadd."' AND dbid='".$di[0]."';";
							$resBa = mssql_query($qryBa);
							$nrowBa= mssql_num_rows($resBa);

							if ($nrowBa > 0)
							{
								while ($rowBa = mssql_fetch_array($resBa))
								{
									$XML_text	.=cost_elem($row1['accid'],$row2[0],"Bid Item: ".removequote($rowBa['bidinfo']),$rowBa['bidamt'],$rowBa['bidamt'],1,"fx");
									$bcl	=$bcl+$rowBa['bidamt'];
									//echo "Bid Item: ".$rowB['bidinfo']." (PLAB (BID))<br>";
									//print_r($di);
									//echo "<p>";
								}
							}
						}
					}
				}
			}

			if (strlen($mat) > 3) // Material Items
			{
				$do=explode(",",$mat);
				//echo "<mat>".$mat."</mat>";

				foreach($do as $n1 => $v1)
				{
					$di=explode(":",$v1);
					//cost_elem($id,$p,$i,$i1,$i2,$i3,$urp,$trp,$q,$u)

					$qry1 = "SELECT * FROM [".$pcode."inventory] WHERE officeid='".$oid."' AND phsid='".$rowA['phsid']."' AND invid='".$di[1]."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					$qry2 = "SELECT phscode FROM phasebase WHERE phsid='".$row1['phsid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);

					if (!empty($row1['accid']))
					{
						if ($row1['matid']!=0)
						{
							$qry4 = "SELECT * FROM material_master WHERE id='".$row1['matid']."';";
							$res4 = mssql_query($qry4);
							$row4 = mssql_fetch_array($res4);

							$item	=$row4['item'];
							$bp	=round($row4['bp']);
							$matpn=$row4['vpnum'];
							//echo "MATPn: ".$matpn."<br>";
						}
						else
						{
							$item	=$row1['item'];
							$bp	=round($di[3]);
							$matpn="0";
						}

						//ECHO "<font color=\"blue\">MAT  : ".$row1['accid'].":".$row1['rinvid']."</font><br>";
						if ($row1['rinvid']!=0)  // Credit Code Loop
						{
							//ECHO "<font color=\"red\">CREDIT</font><br>";
							$cr_out		=mat_credititem_XML($row1['rinvid'],$di[2],$pcode,$oid);
							$bpm			=$cr_out[0];
							$bcm			=$bcm+$bpm;
							$XML_text	.=$cr_out[3];
						}

						//uni_calc_loop($qtype,$bp,$rp,$lr,$hr,$quan,$def_quan,$iarea,$gals,$spa_ia,$spa_gl,$code,$a1,$a2,$a3)
						$calc_out	=uni_calc_loop($row1['qtype'],$di[3],0,0,0,$di[2],$row1['quan_calc'],$viewarray['ia'],$viewarray['gl'],0,0,0,0,0,0);
						$bpm			=$calc_out[0];
						$quan_out	=$calc_out[2];
						$fbpm			=number_format($bpm, 2, '.', '');
						$bcm			=$bcm+$bpm;
						//print_r($calc_out);
						//echo "<p>";
						//cost_elem_mat($id,$p,$i,$urp,$trp,$q,$u,$m)
						$XML_text	.=cost_elem_mat($row1['accid'],$row2[0],$item,$di[3],$fbpm,$quan_out,$row3['abrv'],$matpn);
						//cost_elem($row1['accid'],$row2[0],$item,$di[3],$fbpm,$quan_out,$v1);
					}
				}

			}

			if (strlen($pmat) > 3) // Package Material Items
			{
				$do=explode(",",$pmat);
				//echo "<Pmat>".$pmat."</Pmat>";

				foreach($do as $n1 => $v1)
				{
					$di=explode(":",$v1);
					//cost_elem($id,$p,$i,$i1,$i2,$i3,$urp,$trp,$q,$u)

					$qry1 = "SELECT * FROM [".$pcode."inventory] WHERE officeid='".$oid."' AND phsid='".$rowA['phsid']."' AND invid='".$di[5]."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					$qry2 = "SELECT phscode FROM phasebase WHERE phsid='".$row1['phsid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);

					if (!empty($row1['accid']))
					{
						if ($row1['matid']!=0)
						{
							$qry4 = "SELECT * FROM material_master WHERE id='".$row1['matid']."';";
							$res4 = mssql_query($qry4);
							$row4 = mssql_fetch_array($res4);

							$item	=$row4['item'];
							$item1	="";
							$item2	="";
							$item3	="";
							$matpn	=$row4['vpnum'];
							//$bpmp	=$row4['bp'];
							//echo "MATPn: ".$matpn."<br>";
						}
						else
						{
							$item	=$row1['item'];
							$item1	=$row1['atrib1'];
							$item2	=$row1['atrib2'];
							$item3	=$row1['atrib3'];
							$matpn	="0";
							//$bpmp	=$row1['bprice'];
						}
						//ECHO "<font color=\"blue\">MAT  : ".$row1['accid'].":".$row1['rinvid']."</font><br>";
						if ($row1['rinvid']!=0)  // Credit Code Loop
						{
							//ECHO "<font color=\"red\">CREDIT</font><br>";
							$cr_out		=mat_credititem_XML($row1['rinvid'],$di[2],$pcode,$oid);
							$bpmp		=$cr_out[0];
							$bcmp		=$bcmp+$bpmp;
							$XML_text	.=$cr_out[3];
						}
						
						if ($di[4]==0)
						{
							$diq=1;
						}
						else
						{
							$diq=$di[4];
						}

						//uni_calc_loop($qtype,$bp,$rp,$lr,$hr,$quan,$def_quan,$iarea,$gals,$spa_ia,$spa_gl,$code,$a1,$a2,$a3)
						$calc_out	=uni_calc_loop($row1['qtype'],$di[8],0,0,0,$diq,$row1['quan_calc'],$viewarray['ia'],$viewarray['gl'],0,0,0,0,0,0);
						$bpmp		=$calc_out[0];
						$quan_out	=$calc_out[2];
						$fbpmp		=number_format(round($bpmp), 2, '.', '');
						$bcmp		=$bcmp+$bpmp;
						//print_r($calc_out);
						//echo "<p>";
						//cost_elem_mat($id,$p,$i,$urp,$trp,$q,$u,$m)
						$XML_text	.=cost_elem_mat($row1['accid'],$row2[0],$item,$di[8],$fbpmp,$quan_out,$row3['abrv'],$matpn);
					}
				}
			}

			// Add per Phase Bid Items
			$qryZ = "SELECT * FROM jbids_breakout WHERE officeid='".$oid."' and njobid='".$jobid."' and jadd=0 and phsid='".$rowA['phsid']."';";
			$resZ = mssql_query($qryZ);
			$nrowZ= mssql_num_rows($resZ);
			
			if ($nrowZ > 0)
			{
				while ($rowZ = mssql_fetch_array($resZ))
				{
					$qryZa = "SELECT item FROM [".$pb_code."acc] WHERE officeid='".$oid."' and id='".$rowZ['rdbid']."';";
					$resZa = mssql_query($qryZa);
					$rowZa = mssql_fetch_array($resZa);
					
					$XML_text	.=cost_elem('BC'.$rowZ['id'],$row2[0],"BC: ".removequote($rowZa['item'])." : ".removequote($rowZ['comments']),$rowZ['bprice'],$rowZ['bprice'],1,"fx");
					$bcl		=$bcl+$rowZ['bprice'];
				}
			}
			
			// Add Manual Phase Adjust Items
			$qryY = "SELECT * FROM man_phs_adj WHERE officeid='".$oid."' and njobid='".$jobid."' and jadd=0 and phsid='".$rowA['phsid']."';";
			$resY = mssql_query($qryY);
			$nrowY= mssql_num_rows($resY);
			
			if ($nrowY > 0)
			{
				while ($rowY = mssql_fetch_array($resY))
				{					
					$XML_text	.=cost_elem('MPA'.$rowY['id'],$row2[0],"MPA: ".removequote($rowY['sdesc'])." : ".removequote($rowY['comments']),$rowY['bprice'],$rowY['bprice'],1,"fx");
					$bcl		=$bcl+$rowY['bprice'];
				}
			}

			$XML_text	.=multi_stop("PHS_CODE");
		}
	}

	if ($jadd==0)
	{
		// Commission Cost
		$crp		=calc_comm_XML($jobid,$oid);
		$accrp		=calc_comm_XML_contract_add($jobid,$oid);
		$ajcrp		=calc_comm_XML_job_add($jobid,$oid);
		
		//echo "ADD0: ".$crp."<br>";
		//echo "ADD+: ".$acrp."<br>";

		$fcrp		 =number_format($crp+$accrp+$ajcrp, 2, '.', '');
		$bc			 =$bc+$crp;
		$XML_text	.=multi_id("PHS_CODE","503L");
		$XML_text	.=cost_elem_COMM("30XML",0,"Commission",$fcrp,$fcrp,"1","fx");
		$XML_text	.=multi_stop("PHS_CODE");
		
		if (isset($viewarray['ncommdate']) && $viewarray['adate'] < $viewarray['ncommdate'])
		{
			$crpSM		 =calc_smcomm_XML($jobid,$oid);
			if ($crpSM > 0)
			{
				$bc			 =$bc+$crpSM;
				$XML_text	.=multi_id("PHS_CODE","504L");
				$XML_text	.=cost_elem_COMM("40XML",0,"SM Commission",$crpSM,$crpSM,"1","fx");
				$XML_text	.=multi_stop("PHS_CODE");
			}
		}

		// Royalty Cost
		$rrp		=calc_royalty_XML($camt,$pcode,$jobid,$jadd,$oid);
		$frrp		=number_format($rrp, 2, '.', '');
		$bc			 =$bc+$rrp;
		$XML_text	.=multi_id("PHS_CODE","505L");
		$XML_text	.=cost_elem("50XML",0,"Royalty",$frrp,$frrp,"1","fx");
		$XML_text	.=multi_stop("PHS_CODE");

		if (!empty($viewarray['stax']) && $viewarray['stax']==1)
		{
			// Sales Tax
			$rtp		=$viewarray['tax'];
			$frtp		=number_format($rtp, 2, '.', '');
			$bc			 =$bc+$rrp;
			$XML_text	.=multi_id("PHS_CODE","530L");
			$XML_text	.=cost_elem("53XML",0,"Sales Tax (".$viewarray['taxrate'].")",$frtp,$frtp,"1","tx");
			$XML_text	.=multi_stop("PHS_CODE");
		}
	}

	//ECHO $XML_text;
	return $XML_text;
}

function cost_items_add($oid,$pb_code,$jobid,$jadd,$camt,$lab,$mat,$blab,$bmat,$plab,$pmat)
{
	global $viewarray;
	error_reporting(0);
	//error_reporting(E_ALL);

	$bc	=0;
	$bcl	=0;
	$bcm	=0;
	$bclp	=0;
	$bcmp	=0;
	$bclb	=0;
	$bcmb	=0;

	/*
	echo " LAB(ADD): ".$lab."<br>";
	echo " MAT(ADD): ".$mat."<br>";
	echo "BLAB(ADD): ".$blab."<br>";
	echo "BMAT(ADD): ".$bmat."<br>";
	echo "PLAB(ADD): ".$plab."<br>";
	echo "PMAT(ADD): ".$pmat."<br>";
	*/

	if ($pb_code==0)
	{
		$pcode="";
	}
	else
	{
		$pcode=$pb_code;
	}

	if ($lab=="Array")
	{
		$lab="";
	}

	if ($mat=="Array")
	{
		$mat="";
	}

	if ($blab=="Array")
	{
		$blab="";
	}

	if ($bmat=="Array")
	{
		$bmat="";
	}

	if ($plab=="Array")
	{
		$plab="";
	}

	if ($pmat=="Array")
	{
		$pmat="";
	}

	//multi("COST_ITEMS");
	//Start Loop

	$XML_text="";

	$qryA = "SELECT phsid,phscode FROM phasebase WHERE costing=1 ORDER by XMLseq;";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);

	if ($nrowA > 0)
	{
		while ($rowA = mssql_fetch_array($resA))
		{
			$XML_text	.=multi_id("PHS_CODE",$rowA['phscode']);

			if (strlen($blab) > 3) // Base Labor Items
			{
				$blab=preg_replace("/,\Z/","",$blab);
				$do=explode(",",$blab);
				//echo "+blab+ ".$blab." +blab+";

				foreach($do as $n1 => $v1)
				{
					$di=explode(":",$v1);
					//cost_elem($id,$p,$i,$i1,$i2,$i3,$urp,$trp,$q,$u)

					$qry1 = "SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$oid."' AND phsid='".$rowA['phsid']."' AND id='".$di[0]."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					$qry2 = "SELECT phscode FROM phasebase WHERE phsid='".$row1['phsid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);

					$bplb			=$di[6];
					$quan_out	=$di[11];
					$fbplb		=number_format($bplb, 2, '.', '');
					$bclb			=$bclb+$bplb;

					if (!empty($row1['accid']))
					{
						//ECHO "HERE+<BR>";
						$XML_text	.=cost_elem($row1['accid'],$row2[0],$row1['item'],$fbplb,$fbplb,$quan_out,$row3['abrv']);
					}
					else
					{
						//ECHO "HERE-<BR>";
						$exar[]=array(0=>$row1['item'],1=>$quan_out,2=>$row3['abrv']);
					}
					//cost_elem($row1['accid'],$row2[0],$row1['item'],$row1['atrib1'],$row1['atrib2'],$row1['atrib3'],$di[3],$bpl,$quan_out,$row1['qtype']);
				}
			}

			if (strlen($plab) > 3) // Package Labor Items
			{
				$do=explode(",",$plab);
				//echo "<Plab>".$plab."</Plab>";

				foreach($do as $n1 => $v1)
				{
					$di=explode(":",$v1);
					//cost_elem($id,$p,$i,$i1,$i2,$i3,$urp,$trp,$q,$u)

					$qry1 = "SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$oid."' AND phsid='".$rowA['phsid']."' AND id='".$di[5]."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					$qry2 = "SELECT phscode FROM phasebase WHERE phsid='".$row1['phsid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);

					if ($row1['qtype']!=33)
					{
						if (!empty($row1['accid']))
						{
							if ($row1['rinvid']!=0)  // Credit Code Loop
							{
								$cr_out		=lab_credititem_XML($row1['rinvid'],$di[2],$pcode,$oid,$lab,$plab);
								$bpl			=$cr_out[0];
								$bcl			=$bcl+$bpl;
							}

							//uni_calc_loop($qtype,$bp,$rp,$lr,$hr,$quan,$def_quan,$iarea,$gals,$spa_ia,$spa_gl,$code,$a1,$a2,$a3)
							$calc_out	=uni_calc_loop($row1['qtype'],$di[8],0,$row1['lrange'],$row1['hrange'],$di[2],$row1['quantity'],$viewarray['ia'],$viewarray['gl'],0,0,0,0,0,0);
							$bplp			=$calc_out[0];
							$quan_out	=$calc_out[2];
							$fbplp		=number_format(round($bplp), 2, '.', '');
							$bclp			=$bclp+$bplp;

							$XML_text	.=cost_elem($row1['accid'],$row2[0],$row1['item'],$di[8],$fbplp,$quan_out,$row3['abrv']);
							//cost_elem($row1['accid'],$row2[0],$row1['item'],$di[8],$fbplp,$quan_out,$v1);
						}
					}
					/*
					else
					{
						$qryA = "SELECT * FROM jbids_breakout WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd='".$jadd."' AND rdbid='".$di[0]."' AND cdbid='".$di[1]."';";
						$resA = mssql_query($qryA);
						$nrowA= mssql_num_rows($resA);

						if ($nrowA > 0)
						{
							while ($rowA = mssql_fetch_array($resA))
							{
								$XML_text	.=cost_elem($row1['accid'],$row2[0],"Bid Item: ".removequote($rowA['sdesc']),$rowA['bprice'],$rowA['bprice'],1,"fx");
								$bcl	=$bcl+$rowA['bprice'];
								//echo "<jbids_breakout_labor_package>".$v1."</jbids_breakout_labor_package>";
							}
						}
						else
						{
							$qryB = "SELECT * FROM jbids WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd='".$jadd."' AND dbid='".$di[0]."';";
							$resB = mssql_query($qryB);
							$nrowB= mssql_num_rows($resB);

							if ($nrowB > 0)
							{
								while ($rowB = mssql_fetch_array($resB))
								{
									$XML_text	.=cost_elem($row1['accid'],$row2[0],"Bid Item: ".removequote($rowB['bidinfo']),$rowB['bidamt'],$rowB['bidamt'],1,"fx");
									$bcl	=$bcl+$rowA['bidamt'];
									//echo "<jbids_labor_package>".$v1."</jbids_labor_package>";
								}
							}
						}
					}
					*/
				}

			}

			if (strlen($lab) > 3) // Labor Items
			{
				$do=explode(",",$lab);
				//echo "LABI: ".$lab."<br>";

				foreach($do as $n1 => $v1)
				{
					$di=explode(":",$v1);
					//cost_elem($id,$p,$i,$i1,$i2,$i3,$urp,$trp,$q,$u)

					$qry1 = "SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$oid."' AND phsid='".$rowA['phsid']."' AND id='".$di[1]."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					$qry2 = "SELECT phscode FROM phasebase WHERE phsid='".$row1['phsid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);

					if ($row1['qtype']!=33)
					{
						//echo "LABIQ: ".$lab."<br>";
						if (!empty($row1['accid']))
						{
							if ($row1['rinvid']!=0)  // Credit Code Loop
							{
								$cr_out		=lab_credititem_XML($row1['rinvid'],$di[2],$pcode,$oid,$lab,$plab);
								$bpl			=$cr_out[0];
								$bcl			=$bcl+$bpl;
							}

							//echo $row1['item']." (LAB)<br>";
							//print_r($di);
							//echo "<br>";

							//uni_calc_loop($qtype,$bp,$rp,$lr,$hr,$quan,$def_quan,$iarea,$gals,$spa_ia,$spa_gl,$code,$a1,$a2,$a3)
							$calc_out	=uni_calc_loop($row1['qtype'],$di[3],0,$row1['lrange'],$row1['hrange'],$di[2],$row1['quantity'],$viewarray['ia'],$viewarray['gl'],0,0,0,0,0,0);
							$bpl			=$calc_out[0];
							$quan_out	=$calc_out[2];
							$fbpl			=number_format($bpl, 2, '.', '');
							$bcl			=$bcl+$bpl;
							//print_r($calc_out);
							//echo "<p>";

							$XML_text	.=cost_elem($row1['accid'],$row2[0],$row1['item'],$di[3],$fbpl,$quan_out,$row3['abrv']);
							//cost_elem($row1['accid'],$row2[0],$row1['item'],$di[3],$fbpl,$quan_out,$v1);
						}
					}
					/*
					else
					{
						$qryA = "SELECT * FROM jbids_breakout WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd='".$jadd."' AND rdbid='".$di[0]."' AND cdbid='".$di[1]."';";
						$resA = mssql_query($qryA);
						$nrowA= mssql_num_rows($resA);

						if ($nrowA > 0)
						{
							while ($rowA = mssql_fetch_array($resA))
							{
								$XML_text	.=cost_elem($row1['accid'],$row2[0],"Bid Item: ".removequote($rowA['sdesc']),$rowA['bprice'],$rowA['bprice'],1,"fx");
								$bcl	=$bcl+$rowA['bprice'];
								//echo "<jbids_breakout_labor>".$v1."</jbids_breakout_labor>";
							}
						}
						else
						{
							$qryB = "SELECT * FROM jbids WHERE officeid='".$oid."' AND njobid='".$jobid."' AND jadd='".$jadd."' AND dbid='".$di[0]."';";
							$resB = mssql_query($qryB);
							$nrowB= mssql_num_rows($resB);

							if ($nrowB > 0)
							{
								while ($rowB = mssql_fetch_array($resB))
								{
									$XML_text	.=cost_elem($row1['accid'],$row2[0],"Bid Item: ".removequote($rowB['bidinfo']),$rowB['bidamt'],$rowB['bidamt'],1,"fx");
									$bcl	=$bcl+$rowB['bidamt'];
									//echo "<jbids_labor>".$v1."</jbids_labor>";
								}
							}
						}
					}
					*/
				}
			}

			if (strlen($mat) > 3) // Material Items
			{
				$do=explode(",",$mat);
				//echo "<mat>".$mat."</mat>";

				foreach($do as $n1 => $v1)
				{
					$di=explode(":",$v1);
					//cost_elem($id,$p,$i,$i1,$i2,$i3,$urp,$trp,$q,$u)

					$qry1 = "SELECT * FROM [".$pcode."inventory] WHERE officeid='".$oid."' AND phsid='".$rowA['phsid']."' AND invid='".$di[1]."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					$qry2 = "SELECT phscode FROM phasebase WHERE phsid='".$row1['phsid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);

					if (!empty($row1['accid']))
					{
						if ($row1['matid']!=0)
						{
							$qry4 = "SELECT * FROM material_master WHERE id='".$row1['matid']."';";
							$res4 = mssql_query($qry4);
							$row4 = mssql_fetch_array($res4);

							$item	=$row4['item'];
							$bp	=round($row4['bp']);
						}
						else
						{
							$item	=$row1['item'];
							$bp	=round($di[3]);
						}

						if ($row1['rinvid']!=0)  // Credit Code Loop
						{
							$cr_out		=mat_credititem_XML($row1['rinvid'],$di[2],$pcode,$oid);
							$bpm			=$cr_out[0];
							$bcm			=$bcm+$bpm;
						}

						$calc_out	=uni_calc_loop($row1['qtype'],$di[3],0,0,0,$di[2],$row1['quan_calc'],$viewarray['ia'],$viewarray['gl'],0,0,0,0,0,0);
						$bpm			=$calc_out[0];
						$quan_out	=$calc_out[2];
						$fbpm			=number_format($bpm, 2, '.', '');
						$bcm			=$bcm+$bpm;

						$XML_text	.=cost_elem($row1['accid'],$row2[0],$item,$di[3],$fbpm,$quan_out,$row3['abrv']);
						//cost_elem($row1['accid'],$row2[0],$item,$di[3],$fbpm,$quan_out,$v1);
					}
				}

			}

			if (strlen($pmat) > 3) // Package Material Items
			{
				$do=explode(",",$pmat);
				//echo "<Pmat>".$pmat."</Pmat>";

				foreach($do as $n1 => $v1)
				{
					$di=explode(":",$v1);
					//cost_elem($id,$p,$i,$i1,$i2,$i3,$urp,$trp,$q,$u)

					$qry1 = "SELECT * FROM [".$pcode."inventory] WHERE officeid='".$oid."' AND phsid='".$rowA['phsid']."' AND invid='".$di[5]."';";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					$qry2 = "SELECT phscode FROM phasebase WHERE phsid='".$row1['phsid']."';";
					$res2 = mssql_query($qry2);
					$row2 = mssql_fetch_array($res2);

					$qry3 = "SELECT abrv FROM mtypes WHERE mid='".$row1['mtype']."';";
					$res3 = mssql_query($qry3);
					$row3 = mssql_fetch_array($res3);

					if (!empty($row1['accid']))
					{
						if ($row1['matid']!=0)
						{
							$qry4 = "SELECT * FROM material_master WHERE id='".$row1['matid']."';";
							$res4 = mssql_query($qry4);
							$row4 = mssql_fetch_array($res4);

							$item	=$row4['item'];
							$item1="";
							$item2="";
							$item3="";
							//$bpmp	=$row4['bp'];
						}
						else
						{
							$item	=$row1['item'];
							$item1=$row1['atrib1'];
							$item2=$row1['atrib2'];
							$item3=$row1['atrib3'];
							//$bpmp	=$row1['bprice'];
						}

						if ($row1['rinvid']!=0)  // Credit Code Loop
						{
							$cr_out		=mat_credititem_XML($row1['rinvid'],$di[2],$pcode,$oid);
							$bpmp			=$cr_out[0];
							$bcmp			=$bcmp+$bpmp;
						}

						$calc_out	=uni_calc_loop($row1['qtype'],$di[8],0,0,0,$di[7],$row1['quan_calc'],$viewarray['ia'],$viewarray['gl'],0,0,0,0,0,0);
						$bpmp			=$calc_out[0];
						$quan_out	=$calc_out[2];
						$fbpmp		=number_format(round($bpmp), 2, '.', '');
						$bcmp			=$bcmp+$bpmp;

						$XML_text	.=cost_elem($row1['accid'],$row2[0],$row1['item'],$di[8],$fbpmp,$quan_out,$row3['abrv']);
					}
				}

			}
			
			// Add per Phase Bid Items
			$qryZ = "SELECT * FROM jbids_breakout WHERE officeid='".$oid."' and njobid='".$jobid."' and jadd='".$jadd."' and phsid='".$rowA['phsid']."';";
			$resZ = mssql_query($qryZ);
			$nrowZ= mssql_num_rows($resZ);
			
			if ($nrowZ > 0)
			{
				while ($rowZ = mssql_fetch_array($resZ))
				{
					$qryZa = "SELECT item FROM [".$pb_code."acc] WHERE officeid='".$oid."' and id='".$rowZ['rdbid']."';";
					$resZa = mssql_query($qryZa);
					$rowZa = mssql_fetch_array($resZa);
					
					//echo "<br>ADD Item<br>";
					$XML_text	.=cost_elem('BID'.$rowZ['id'],$row2[0],"BC: ".removequote($rowZa['item'])." : ".removequote($rowZ['comments']),$rowZ['bprice'],$rowZ['bprice'],1,"fx");
					$bcl			 =$bcl+$rowZ['bprice'];
				}
			}
			
			//Add per Phase Manual Phase Adjust Items
			$qryY = "SELECT * FROM man_phs_adj WHERE officeid='".$oid."' and njobid='".$jobid."' and jadd='".$jadd."' and phsid='".$rowA['phsid']."';";
			$resY = mssql_query($qryY);
			$nrowY= mssql_num_rows($resY);
			
			if ($nrowY > 0)
			{
				while ($rowY = mssql_fetch_array($resY))
				{
					//echo "<br>ADD Item<br>";
					$XML_text	.=cost_elem('MPA'.$rowY['id'],$row2[0],"MPA: ".removequote($rowY['comments']),$rowY['bprice'],$rowY['bprice'],1,"fx");
					$bcl			 =$bcl+$rowY['bprice'];
				}
			}

			$XML_text	.=multi_stop("PHS_CODE");
		}
	}

	return $XML_text;
}

function cost_elem($id,$p,$i,$urp,$trp,$q,$u)
{

	$XML_text1	 =multi_id("Item",trim($id));
	$XML_text1	.=	single_element("desc",trim($i));
	$XML_text1	.=	single_element("t_rp",number_format(round(trim($trp)), 2, '.', ''));
	$XML_text1	.=	single_element("quan",trim($q));
	$XML_text1	.=multi("/Item");

	return $XML_text1;
}

function cost_elem_COMM($id,$p,$i,$urp,$trp,$q,$u)
{

	$XML_text1	 =multi_id("Item",$id);
	$XML_text1	.=	single_element("desc",$i);
	$XML_text1	.=	single_element("t_rp",number_format($trp, 2, '.', ''));
	$XML_text1	.=	single_element("quan",$q);
	$XML_text1	.=multi("/Item");

	return $XML_text1;
}

function cost_elem_mat($id,$p,$i,$urp,$trp,$q,$u,$m)
{
	if (strlen($m) < 1)
	{
		$m="0";
	}

	$XML_text1	 =multi_id("Item",$id);
	$XML_text1	.=	single_element("desc",$i);
	$XML_text1	.=	single_element("t_rp",number_format(round($trp), 2, '.', ''));
	$XML_text1	.=	single_element("quan",$q);
	$XML_text1	.=	single_element("ManPN",$m);
	$XML_text1	.=multi("/Item");

	return $XML_text1;
}

function XML_content($oid,$njobid)
{
	global $viewarray;

	$qry0 = "SELECT * FROM jobs WHERE officeid='".$oid."' AND njobid='".$njobid."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);

	if ($nrow0 > 0)
	{
		$qry0a = "SELECT estid,added FROM est WHERE officeid='".$oid."' AND estid='".$row0['estid']."';";
		$res0a = mssql_query($qry0a);
		$row0a = mssql_fetch_array($res0a);
		
		$qry1 = "SELECT * FROM jdetail WHERE officeid='".$row0['officeid']."' AND njobid='".$row0['njobid']."' AND jadd='0';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);

		$qry1a = "SELECT * FROM jdetail WHERE officeid='".$row0['officeid']."' AND njobid='".$row0['njobid']."' AND jadd!='0' ORDER by jadd ASC;";
		$res1a = mssql_query($qry1a);
		$nrow1a= mssql_num_rows($res1a);

		$qry1b = "SELECT MAX(jadd) AS mjadd FROM jdetail WHERE officeid='".$row0['officeid']."' AND njobid='".$row0['njobid']."';";
		$res1b = mssql_query($qry1b);
		$row1b = mssql_fetch_array($res1b);

		$qry1c= "SELECT pft,sqft,shal,mid,deep,spa_pft,spa_sqft,spa_type,deck,erun,prun FROM jdetail WHERE officeid='".$row0['officeid']."' AND njobid='".$row0['njobid']."' AND jadd='".$row1b['mjadd']."';";
		$res1c= mssql_query($qry1c);
		$row1c= mssql_fetch_array($res1c);

		$qry2 = "SELECT securityid,fname,lname,mas_office,mas_div,masid,officeid,rmas_div,newcommdate FROM security WHERE officeid='".$row0['officeid']."' AND securityid='".$row0['securityid']."';";
		$res2 = mssql_query($qry2);
		$row2 = mssql_fetch_array($res2);

		$qry3 = "SELECT securityid,fname,lname,mas_office,mas_div,masid,officeid FROM security WHERE officeid='".$row0['officeid']."' AND securityid='".$row0['sidm']."';";
		$res3 = mssql_query($qry3);
		$row3 = mssql_fetch_array($res3);

		//$qry4 = "SELECT * FROM cinfo WHERE officeid='".$row0['officeid']."' AND estid='".$row0['estid']."';";
		$qry4 = "SELECT * FROM cinfo WHERE officeid='".$row0['officeid']."' AND njobid='".$row0['njobid']."';";
		$res4 = mssql_query($qry4);
		$row4 = mssql_fetch_array($res4);

		$qry5 = "SELECT officeid,pb_code,stax FROM offices WHERE officeid='".$row0['officeid']."';";
		$res5 = mssql_query($qry5);
		$row5 = mssql_fetch_array($res5);

		$qry6 = "SELECT code FROM offices WHERE officeid='".$row2['officeid']."';";
		$res6 = mssql_query($qry6);
		$row6 = mssql_fetch_array($res6);

		$ia	=calc_internal_area($row1['pft'],$row1['sqft'],$row1['shal'],$row1['mid'],$row1['deep']);
		$gl	=calc_gallons($row1['pft'],$row1['sqft'],$row1['shal'],$row1['mid'],$row1['deep']);

		if ($row0['renov']==1 && $row2['rmas_div']!=0 && strtotime($row0['added']) >= strtotime('9/28/07'))
		{
			$fsdiv=format_div($row2['rmas_div']);
		}
		else
		{
			$fsdiv=format_div($row2['mas_div']);
		}
		
		$fmdiv=format_div($row3['mas_div']);

		$viewarray= array(
		'ps1'=>$row1['pft'],
		'ps2'=>$row1['sqft'],
		'ps5'=>$row1['shal'],
		'ps6'=>$row1['mid'],
		'ps7'=>$row1['deep'],
		'ia'=>$ia,
		'gl'=>$gl,
		'stax'=>$row5['stax'],
		'tax'=>$row0['tax'],
		'taxrate'=>$row0['taxrate'],
		'secid'=>$row2['securityid'],
		'sidm'=>$row3['securityid'],
		'adate'=>strtotime($row0a['added']),
		'ncommdate'=>strtotime($row2['newcommdate'])
		);

		$ctramt=$row1['contractamt']+$row0['tax'];
		
		//$XML_text	 =declare_XML("1.0","ISO-8859-1");
		$XML_text	 ="";
		$XML_text	.=multi("JOB");
		$XML_text	.=single_element("MAS_CODE",trim($row2['mas_office']));
		$XML_text	.=single_element("JOB_NUMBER",trim($row0['njobid']));
		$XML_text	.=single_element("CONTRACT_DATE",trim($row1['contractdate']));
		$XML_text	.=single_element("CONTRACT_AMT",trim($ctramt));
		$XML_text	.=multi("SALESMAN");
		$XML_text	.=single_element("Division",trim($fsdiv));
		$XML_text	.=single_element("SalespersonNumber",trim($row2['masid']));
		$XML_text	.=multi("/SALESMAN");
		$XML_text	.=multi("MANAGER");
		$XML_text	.=single_element("Division",trim($fmdiv));
		$XML_text	.=single_element("SalespersonNumber",trim($row3['masid']));
		$XML_text	.=multi("/MANAGER");
		$XML_text	.=multi("CUSTOMER");
		$XML_text	.=single_element("First_Name",trim($row4['cfname']));
		$XML_text	.=single_element("Last_Name",trim($row4['clname']));
		$XML_text	.=single_element("Address_1",trim($row4['saddr1']));
		$XML_text	.=single_element("Address_2",trim($row4['saddr2']));
		$XML_text	.=single_element("City",trim($row4['scity']));
		$XML_text	.=single_element("State",trim($row4['sstate']));
		$XML_text	.=single_element("Zip1",trim($row4['szip1']));
		$XML_text	.=single_element("Zip2",trim($row4['szip2']));
		$XML_text	.=single_element("County",trim($row4['scounty']));
		$XML_text	.=single_element("Home_Ph",trim($row4['chome']));
		$XML_text	.=single_element("Work_Ph",trim($row4['cwork']));
		$XML_text	.=single_element("Cell_Ph",trim($row4['ccell']));
		$XML_text	.=single_element("Email",trim($row4['cemail']));
		$XML_text	.=multi("/CUSTOMER");
		$XML_text	.=multi("SITE");
		$XML_text	.=single_element("Address_1",trim($row4['saddr1']));
		$XML_text	.=single_element("Address_2",trim($row4['saddr2']));
		$XML_text	.=single_element("City",trim($row4['scity']));
		$XML_text	.=single_element("State",trim($row4['sstate']));
		$XML_text	.=single_element("Zip1",trim($row4['szip1']));
		$XML_text	.=single_element("Zip2",trim($row4['szip2']));
		$XML_text	.=multi("/SITE");
		$XML_text	.=multi("POOL_PARAM");
		$XML_text	.=single_element("sqft",trim($row1c['sqft']));
		$XML_text	.=single_element("pft",trim($row1c['pft']));
		$XML_text	.=single_element("shal",trim($row1c['shal']));
		$XML_text	.=single_element("mid",trim($row1c['mid']));
		$XML_text	.=single_element("deep",trim($row1c['deep']));
		$XML_text	.=single_element("spa_pft",trim($row1c['spa_pft']));
		$XML_text	.=single_element("spa_sqft",trim($row1c['spa_sqft']));
		$XML_text	.=single_element("erun",trim($row1c['erun']));
		$XML_text	.=single_element("prun",trim($row1c['prun']));
		$XML_text	.=single_element("ia",trim($ia));
		$XML_text	.=single_element("gl",trim($gl));
		$XML_text	.=multi("/POOL_PARAM");

		// Main Job Items
		$XML_text	.=multi_id("ADDENDUM",0);
		$XML_text	.=cost_items($row5['officeid'],$row5['pb_code'],$row0['njobid'],$row1['jadd'],$row1['contractamt'],$row1['costdata_l'],$row1['costdata_m'],$row1['bcostdata_l'],$row1['bcostdata_m'],$row1['pcostdata_l'],$row1['pcostdata_m']);
		$XML_text	.=multi("/ADDENDUM");

		//echo "<pre>".$nrow1a."</pre>";

		// Addendum Items
		if ($nrow1a > 0)
		{
			while($row1a = mssql_fetch_array($res1a))
			{
				//echo "QRY (".$row1a['jadd'].")".$qry1a."</pre>";
				$XML_text	.=multi_id("ADDENDUM",$row1a['jadd']);
				$XML_text	.=cost_items_add($row5['officeid'],$row5['pb_code'],$row0['njobid'],$row1a['jadd'],$row1a['contractamt'],$row1a['costlabdiff'],$row1a['costmatdiff'],$row1a['bcostlabdiff'],$row1a['bcostmatdiff'],$row1a['pcostlabdiff'],$row1a['pcostmatdiff']);
				$XML_text	.=multi("/ADDENDUM");
			}
		}

		$XML_text	.=pay_sched($row1['psched'],$row1['psched_perc'],$row0['officeid'],$row0['njobid']);

		$XML_text	.=multi("/JOB");
	}

	//echo $XML_text;
	return $XML_text;
}

function labor_baseitems_calc_XML($phsid,$pb_code,$jtag)
{
	global $viewarray;

	if ($pb_code==0)
	{
		$pcode="";
	}
	else
	{
		$pcode=$pb_code;
	}

	//print_r($viewarray);

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

	$iarea=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$gals=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	// Calculation Settings
	$qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre0 =mssql_query($qrypre0);
	$rowpre0 =mssql_fetch_array($respre0);

	//Pulls Total List of Base Labor Items within a phase based upon DISTINCT accid's
	$qry0    ="SELECT DISTINCT(accid),qtype,seqnum FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND baseitem=1 ORDER BY seqnum;";
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
				$qry1 ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
				$qry1 ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
				$qry1  ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
				$qry1 ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
				$qry1  ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
				$qry1  ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
				$qry1  ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
				$qry1  ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1 order by lrange ASC;";
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
				$qry1  ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1 order by lrange ASC;";
				$res1  =mssql_query($qry1);

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
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$respre1 = mssql_query($qrypre1);
				$rowpre1 = mssql_fetch_row($respre1);

				if ($iarea < $rowpre1[0])
				{
					$qry1  = "SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
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
					$qry1  ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND hrange='".$rowpre1[3]."' AND baseitem=1;";
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
					$qry1  ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
				$respre1 = mssql_query($qrypre1);
				$rowpre1 = mssql_fetch_row($respre1);

				if ($ps1 < $rowpre1[0])
				{
					$qry1 ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
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
					$qry1 ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[1]."' AND baseitem=1;";
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
					$qry1 ="SELECT * FROM [".$pcode."accpbook] WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$ps1."' AND baseitem=1;";
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
				}
			}

			$bc=$bc+$bcsub;
			$rc=$rc+$rcsub;
		}
	}
	else
	{
		$bc=0;
		$rc=0;
	}

	$dout=array(0=>$bc,1=>$rc);
	return $dout;
}

?>