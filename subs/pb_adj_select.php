<?php

session_start();
error_reporting(E_ALL);

//print_r($_SESSION);

if (!isset($_SESSION['officeid'])  || !isset($_SESSION['securityid']) || !isset($_SESSION['pb_code']))
{
	die('Session Expired!');
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include ('../connect_db.php');
include ('../calc_func.php');


function showdescrip_hdr($i,$a1,$a2,$a3)
{
	if (strlen($i) > 1)
	{
		echo "                                                <font color=\"blue\"><b>$i</b></font><br>\n";
	}

	if (strlen($a1) > 1)
	{
		echo "                                         - <font class=\"7pt\">$a1</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "                                         <br>- <font class=\"7pt\">$a2</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "                                         <br>- <font class=\"7pt\">$a3</font>\n";
	}
}

function showdescrip_subhdr($i)
{
	if (strlen($i) > 1)
	{
		echo "<img src=\"../images/plus.gif\" style=\"border:white\" alt=\"Click to Expand\"><font color=\"blue\"><b>$i</b></font>";
	}
}

function showdescrip_hdratribs($a1,$a2,$a3)
{
	if (strlen($a1) > 1)
	{
		echo "                                                - <font class=\"7pt\">$a1</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "                                                <br>- <font class=\"7pt\">$a2</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "                                                <br>- <font class=\"7pt\">$a3</font>\n";
	}
}

function showdescrip_quote($i,$a1,$a2,$a3)
{
	echo "                        <table align=\"left\" width=\"100%\" border=0>\n";
	
	if (strlen($i) > 1)
	{
		echo "                           <tr>\n";
		echo "                              <td colspan=\"2\" align=\"left\">\n";
		
		if (isset($id) && $id!=0)
		{
			echo "									<a href=\"./subs/pb_select.php#PBi_".$id."\" target=\"PBSelect\">".trim($i)."</a>\n";
		}
		else
		{
			echo "									".trim($i)."\n";
		}
		
		echo "								</td>\n";
		echo "                           </tr>\n";
	}

	if (strlen($a1) > 1)
	{
		echo "                           <tr>\n";
        echo "                              <td class=\"transbackfill\" align=\"right\" width=\"20\"></td>\n";
        echo "                              <td align=\"left\">".trim($a1)."</td>\n";
        echo "                           </tr>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "                           <tr>\n";
        echo "                              <td class=\"transbackfill\" align=\"right\" width=\"20\"></td>\n";
        echo "                              <td align=\"left\">".trim($a2)."</td>\n";
        echo "                           </tr>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "                           <tr>\n";
        echo "                              <td class=\"transbackfill\" align=\"right\" width=\"20\"></td>\n";
        echo "                              <td align=\"left\">".trim($a3)."</td>\n";
        echo "                           </tr>\n";
	}
	
	echo "                        </table>\n";
}

function form_element_ACCBLANK($id,$aid,$officeid,$item,$accpbook,$qtype,$seqn,$rp,$bp,$spaitem,$mtype,$atrib1,$atrib2,$atrib3,$quan_calc,$commtype,$crate,$disabled)
{
	echo "                     <tr>\n";
	echo "                        <td align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
	echo "                        <td align=\"left\" colspan=\"4\">\n";	
	echo "							</td>\n";
	echo "                     </tr>\n";
}

function form_element_ACCTEST($id,$aid,$officeid,$item,$accpbook,$qtype,$seqn,$rp,$bp,$spaitem,$mtype,$atrib1,$atrib2,$atrib3,$quan_calc,$commtype,$crate,$disabled,$r_estdata)
{
	error_reporting(E_ALL);
	$tbg="gray_undsidesr";
	
	echo "                     <tr>\n";
	echo "                        <td align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
	echo "                        <td align=\"left\" colspan=\"4\">\n";
	
	echo "<pre>";
	echo var_dump($id);
	echo '<br>';
	echo var_dump($aid);
	echo '<br>';
	echo var_dump($officeid);
	echo '<br>';
	echo var_dump($item);
	echo '<br>';
	echo var_dump($accpbook);
	echo '<br>';
	echo var_dump($qtype);
	echo '<br>';
	echo var_dump($seqn);
	echo '<br>';
	echo var_dump($rp);
	echo '<br>';
	echo var_dump($bp);
	echo '<br>';
	echo var_dump($spaitem);
	echo '<br>';
	echo var_dump($mtype);
	echo '<br>';
	echo var_dump($atrib1);
	echo '<br>';
	echo var_dump($atrib2);
	echo '<br>';
	echo var_dump($atrib3);
	echo '<br>';
	echo var_dump($quan_calc);
	echo '<br>';
	echo var_dump($commtype);
	echo '<br>';
	echo var_dump($crate);
	echo '<br>';
	echo var_dump($disabled);
	echo '<br>';
	echo "</pre>";
	
	echo "							</td>\n";
	echo "                     </tr>\n";

}

function form_element_ACC_adj($id,$aid,$officeid,$item,$accpbook,$qtype,$seqn,$rp,$bp,$spaitem,$mtype,$atrib1,$atrib2,$atrib3,$quan_calc,$commtype,$crate,$disabled,$priv)
{
	error_reporting(E_ALL);	

	if ($disabled==0)
	{
		if  (
		$qtype==1||
		$qtype==2||
		$qtype==3||
		$qtype==4||
		$qtype==5||
		$qtype==6||
		$qtype==7||
		$qtype==8||
		$qtype==9||
		$qtype==10||
		$qtype==11||
		$qtype==12||
		$qtype==13||
		$qtype==14||
		$qtype==15||
		$qtype==16||
		$qtype==17||
		$qtype==34||
		$qtype==35||
		$qtype==36||
		$qtype==37||
		$qtype==38||
		$qtype==39||
		$qtype==41||
		$qtype==42||
		$qtype==43||
		$qtype==45||
		$qtype==46||
		$qtype==47||
		$qtype==55||
		$qtype==58||
		$qtype==69||
		$qtype==70||
		$qtype==72||
		$qtype==77
		)
		{
			// PFT - SQFT - Fixed - Depth - Checkbox - Base+ (All) - Bracket (All)
			// Deck - NoCharge (PFT,SQFT,IA,Gals,Fixed and Base+ Variants)
			// IA (Div by CalcAmt) - IA (Mult by CalcAmt) - Package (Checkbox)
			
			$tbg="gray_whtbrdr";

			if (isset($mtype) && $mtype!=0)
			{
				$qryB = "SELECT mid,abrv FROM mtypes WHERE mid='".$mtype."'";
				$resB = mssql_query($qryB);
				$rowB = mssql_fetch_array($resB);
				$fmtype=$rowB['abrv'];
			}
			else
			{
				$fmtype='';
			}
			
			$qryC = "SELECT * FROM acc_price_pad WHERE oid=".$_SESSION['officeid']." and sid=".$_SESSION['securityid']." and iid=".$id.";";
			$resC = mssql_query($qryC);
			$nrowC= mssql_num_rows($resC);
			
			if ($nrowC != 0)
			{
				$rowC 		= mssql_fetch_array($resC);
				$pid		=$rowC['pid'];
				$adj_price	=number_format($rowC['adj_price'], 2, '.', '');
			}
			else
			{
				$pid		=0;
				$adj_price	='0.00';
			}
			
			$rp	=number_format($rp, 2, '.', '');// BP from DB
			
			echo "                     <tr>\n";
			echo "                        <td class=\"".$tbg."\" valign=\"bottom\" align=\"left\">\n";
	
			if ($priv=='Private Items')
			{
				showdescrip_quote($item,'','','');
			}
			else
			{
				showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			}
			
			echo "							 <input type=\"hidden\" name=\"adjitem[".$id."][id]\" value=\"".$id."\" size=\"10\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"".$tbg."\" width=\"75\" valign=\"bottom\" align=\"right\">\n";
			echo "							<input class=\"bbox\" type=\"text\" name=\"adjitem[".$id."][ap]\" value=\"".$adj_price."\" size=\"10\">\n";
			echo "						  </td>\n";
			echo "                        <td class=\"".$tbg."\" width=\"60\" valign=\"bottom\" align=\"right\">".$rp."</td>\n";
			echo "                        <td class=\"".$tbg."\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "                        <td class=\"".$tbg."\" width=\"50\" valign=\"bottom\" align=\"right\">\n";
			echo "							<table width=\"100%\">\n";
			echo "								<tr>\n";
			echo "									<td align=\"center\">Yes</td>\n";
			echo "									<td align=\"center\">No</td>\n";
			echo "								</tr>\n";
	
			if ($nrowC!=0 && $rowC['active']==1)
			{
				echo "								<tr>\n";
				echo "									<td align=\"center\"><input class=\"transnb\" type=\"radio\" name=\"adjitem[".$id."][active]\" value=\"1\" CHECKED></td>\n";
				echo "									<td align=\"center\"><input class=\"transnb\" type=\"radio\" name=\"adjitem[".$id."][active]\" value=\"0\"></td>\n";
				echo "								</tr>\n";
			}
			else
			{
				echo "								<tr>\n";
				echo "									<td align=\"center\"><input class=\"transnb\" type=\"radio\" name=\"adjitem[".$id."][active]\" value=\"1\"></td>\n";
				echo "									<td align=\"center\"><input class=\"transnb\" type=\"radio\" name=\"adjitem[".$id."][active]\" value=\"0\" CHECKED></td>\n";
				echo "								</tr>\n";
			}
		
			echo "							</table>\n";
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
	}
}

function add_adj_items()
{
	if (isset($_REQUEST['adjitem']) && is_array($_REQUEST['adjitem']))
	{
		foreach ($_REQUEST['adjitem'] as $n => $v)
		{
			if (isset($v['ap']))
			{
				/*echo '<pre>';
				//echo $n.'<br>';
				//echo $n1.':'.$v1.'<br>';
				print_r($v);
				//show_array_vars($_REQUEST['adjitem'][$n]);
				//echo $_REQUEST['adjitem'][$n]['id'].'<br>';
				echo '</pre>';*/
				
				$qry0  = "select * from [jest]..[acc_price_pad] where oid=".$_SESSION['officeid']." and sid=".$_SESSION['securityid']." and iid=".$v['id'].";";
				$res0  = mssql_query($qry0);
				$row0  = mssql_fetch_array($res0);
				$nrow0 = mssql_num_rows($res0);
				
				if ($nrow0 == 1 && number_format($v['ap'], 2, '.', '') != number_format($row0['adj_price'], 2, '.', ''))
				{
					$qry0a   = "update [jest].[dbo].[acc_price_pad] set ";
					$qry0a  .= "adj_price=cast('".$v['ap']."' as money), ";
					$qry0a  .= "active=".$v['active'].", ";
					$qry0a  .= "udate=getdate(),udateby=".$_SESSION['securityid']." ";
					$qry0a  .= "where pid=".$row0['pid']." and sid=".$_SESSION['securityid'].";";
					$res0a   = mssql_query($qry0a);
				}
				elseif ($nrow0 == 1 && $v['active'] != $row0['active'])
				{
					$qry0a   = "update [jest].[dbo].[acc_price_pad] set ";
					$qry0a  .= "adj_price=cast('".$v['ap']."' as money), ";
					$qry0a  .= "active=".$v['active'].", ";
					$qry0a  .= "udate=getdate(),udateby=".$_SESSION['securityid']." ";
					$qry0a  .= "where pid=".$row0['pid']." and sid=".$_SESSION['securityid'].";";
					$res0a   = mssql_query($qry0a);
				}
				elseif ($nrow0 == 0 && $v['ap']!=0)
				{					
					$qry0a   = "INSERT INTO [jest].[dbo].[acc_price_pad] (";
					$qry0a  .= " [oid] ";
					$qry0a  .= ",[sid] ";
					$qry0a  .= ",[iid] ";
					$qry0a  .= ",[ppb_price] ";
					$qry0a  .= ",[adj_price] ";
					$qry0a  .= ",[active] ";
					$qry0a  .= ",[udateby] ";
					$qry0a  .= ") VALUES (";
					$qry0a  .= " ".$_SESSION['officeid']." ";
					$qry0a  .= ",".$_SESSION['securityid']." ";
					$qry0a  .= ",".$v['id']." ";
					$qry0a  .= ",cast('0' as money) ";
					$qry0a  .= ",cast('".$v['ap']."' as money) ";
					$qry0a  .= ",".$v['active']." ";
					$qry0a  .= ",".$_SESSION['securityid']." ";
					$qry0a  .= ");";
					$res0a   = mssql_query($qry0a);
				}
			}
		}		
	}
	
	$qryB = "SELECT id,catid,name FROM AC_Cats WHERE officeid='".$_SESSION['officeid']."' and active=1 and privcat=1;";
	$resB = mssql_query($qryB);	
	
}

function pbmatrix()
{
	error_reporting(E_ALL);
	//$MAS	=$_SESSION['pb_code'];
	//$_SESSION['pbupdate']++;

	echo "<form name=\"updateadj_items\" action=\"pb_adj_select.php\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"pr\" value=\"add_acc_adj\">\n";
	echo "<input type=\"hidden\" name=\"#Top\">\n";
	echo "	<div id=\"masterdiv\">\n";
	echo "		<table width=\"100%\" border=0>\n";
	
	if (isset($_SESSION['pricebookdata']) && strlen($_SESSION['pricebookdata']) > 3)
	{
		$pbdata_ar=json_decode($_SESSION['pricebookdata'],true);
		
		if ($pbdata_ar[array_rand($pbdata_ar)][2][0][2]!=$_SESSION['officeid'])
		{
			load_pricebook_data();
			$pbdata_ar=json_decode($_SESSION['pricebookdata'],true);
		}

		echo "	<tr>\n";
		echo "		<td>\n";
		echo "			<table cellspacing=0 border=0 width=\"100%\">\n";

		if (is_array($pbdata_ar))
		{
			$qryB = "SELECT id,catid,name FROM AC_Cats WHERE officeid='".$_SESSION['officeid']."' and active=1 and privcat=1;";
			$resB = mssql_query($qryB);
			$nrowB= mssql_num_rows($resB);
			
			if ($nrowB > 0)
			{
				while($rowB = mssql_fetch_array($resB))
				{
					$qryC = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc,commtype,crate,disabled FROM [".$_SESSION['pb_code']."acc] WHERE officeid='".$_SESSION['officeid']."' AND catid=".$rowB['catid']." AND phsid=".$_SESSION['securityid']." AND disabled=0 ORDER BY seqn;";
					$resC = mssql_query($qryC);
					$nrowC= mssql_num_rows($resC);
					
					if ($nrowC > 0)
					{
						//echo 'LOADing Pers PB <br>';
						$pbdata_ar[$rowB['catid']]=   array(
											0=>	$rowB['catid'],
											1=>	$rowB['name']
											);
							
						while($rowC = mssql_fetch_array($resC))
						{
							$pbdata_ar[$rowB['catid']][2][]=   array(
														$rowC['id'],
														$rowC['aid'],
														$rowC['officeid'],
														$rowC['item'],
														$rowC['accpbook'],
														$rowC['qtype'],
														$rowC['seqn'],
														$rowC['rp'],
														$rowC['bp'],
														$rowC['spaitem'],
														$rowC['mtype'],
														$rowC['atrib1'],
														$rowC['atrib2'],
														$rowC['atrib3'],
														$rowC['quan_calc'],
														$rowC['commtype'],
														$rowC['crate'],
														$rowC['disabled']
														);
						}
					}
				}
				//echo "<pre>";
				//print_r($pbdata_ar);
				//echo "</pre>";
			}
			
			$ecnt=1;
			echo "				<tr>\n";
			echo "					<td class=\"gray\" valign=\"top\">\n";
			//echo 'IN<br>';	
			foreach ($pbdata_ar as $n=>$v)
			{
				if ($v[0]!=0)
				{
					if ($ecnt==count($pbdata_ar))
					{
						echo "<a href=\"#".trim($v[0])."\">".trim($v[1])."</a>";
					}
					else
					{
						echo "<a href=\"#".trim($v[0])."\">".trim($v[1])."</a> - ";
					}
					$ecnt++;
				}
			}
			
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "  		</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td valign=\"top\">\n";
			echo "			<table cellspacing=0 border=0 width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td class=\"wh_und\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "					<td class=\"wh_und\" align=\"center\"><b>Adjustment</b></td>\n";
			echo "					<td class=\"wh_und\" align=\"center\"><b>PB Price</b></td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">\n";
			echo "						<img src=\"../images/pixel.gif\">\n";
			//echo "						<input class=\"checkboxwh\" type=\"image\" src=\"../images/save.gif\" alt=\"Save Selections\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\"><b>Active</b></td>\n";
			echo "				</tr>\n";
			
			foreach ($pbdata_ar as $no => $vo)
			{
				if ($vo[0]!=0)
				{
					echo "				<tr>\n";
					echo "					<td class=\"drkgray\" colspan=\"5\" align=\"left\" valign=\"top\">\n";
					echo "						<input type=\"hidden\" name=\"#".trim($vo[0])."\"><b>".trim($vo[1])."</b>\n";
					echo "					</td>\n";
					echo "				</tr>\n";
	
					foreach($vo[2] as $pn => $pv)
					{
						if ($pv[5]==32)
						{							
							echo "				<tr>\n";
							echo "					<td class=\"wh_und\" colspan=\"3\">\n";
							echo "						<font color=\"blue\"><b>".trim($pv[3])."</b></font>\n";
							echo "					</td>\n";
							echo "					<td class=\"wh_und\" align=\"center\">\n";
							echo "						<input class=\"checkboxwh\" type=\"image\" src=\"../images/save.gif\" alt=\"Save Selections\">\n";
							echo "					</td>\n";
							echo "					<td class=\"wh_und\" align=\"right\"><a href=\"#Top\"><img style=\"border:white\" src=\"../images/scrollup.gif\" alt=\"to Top\"></a></td>\n";
							echo "				</tr>\n";
							
							if (strlen(trim($pv[11])) > 2)
							{
								echo "              <tr>\n";
								echo "					<td class=\"gray_und\" valign=\"top\" align=\"left\" colspan=\"5\">\n";

								showdescrip_hdratribs(trim($pv[11]),trim($pv[12]),trim($pv[13]));
		
								echo "					</td>\n";
								echo "				</tr>\n";
							}
						}

						form_element_ACC_adj(trim($pv[0]),trim($pv[1]),trim($pv[2]),
										 trim($pv[3]),trim($pv[4]),trim($pv[5]),
										 trim($pv[6]),trim($pv[7]),trim($pv[8]),
										 trim($pv[9]),trim($pv[10]),trim($pv[11]),
										 trim($pv[12]),trim($pv[13]),trim($pv[14]),
										 trim($pv[15]),trim($pv[16]),trim($pv[17]),trim($vo[1]));
					}
				}
			}
	
			echo "  		</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
	}
	else
	{
		load_pricebook_data();
		
		echo "<table class=\"outer\" width=\"100%\" border=0>\n";
		echo "	<tr>\n";
		echo "		<td class=\"wh\" colspan=\"3\">\n";
		echo "			Loading PriceBook Data...";
		echo "		<a href=\"pb_adj_select.php\">Click here to refresh</a>";
		echo "		</td>\n";
		echo "	</tr>\n";
	}
	
	echo "		</table>\n";
	echo "	</div>\n";
	echo "</form>\n";
}

echo "<html>\n";
echo "<head>\n";
echo "	<title>JMS PriceBook Select</title>\n";
echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"../yui/build/reset-fonts/reset-fonts.css\">\n";
echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"../bh_embed.css\" />\n";
echo "	<script language=\"Javascript\" type=\"text/javascript\" src=\"../js/extension.js\"></script>";
echo "</head>\n";
echo "   <body>\n";

if (isset($_REQUEST['pr']) && $_REQUEST['pr']=='add_acc_adj')
{
	//echo 'Proc Add Adjs<br>';
	add_adj_items();
}

pbmatrix();

echo "   </body>\n";
echo "</html>\n";

?>