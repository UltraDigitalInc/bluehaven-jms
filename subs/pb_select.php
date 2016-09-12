<?php

session_start();
error_reporting(E_ALL);

//print_r($_SESSION);

if (!isset($_SESSION['officeid']) || !isset($_SESSION['estid']))
{
	exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include ('../connect_db.php');
include ('../calc_func.php');
//include ('../display_func.php');

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

function form_element_ACC_new($id,$aid,$officeid,$item,$accpbook,$qtype,$seqn,$rp,$bp,$spaitem,$mtype,$atrib1,$atrib2,$atrib3,$quan_calc,$commtype,$crate,$disabled,$r_estdata)
{
	error_reporting(E_ALL);
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

	if (strlen($r_estdata) < 2)
	{
		$db_id=0;
		$db_qn=0;
		$db_rp=0;
		$db_cd=0;
		$db_ct=0;
		$db_ca=0;
	}
	else
	{
		$edata=explode(",",$r_estdata);
		foreach($edata as $n1 => $v1)
		{
			$idata=explode(":",$v1);
			
			$rdata[]=$idata[0];
			$qdata[]=$idata[2];
			$pdata[]=$idata[3];
			$cdata[]=$idata[4];
		}
		
		$arkey=array_search($id,$rdata);

		if ($id==$rdata[$arkey])
		{
			$db_id=$rdata[$arkey];
			$db_qn=$qdata[$arkey];
			$db_rp=$pdata[$arkey];
			$db_cd=$cdata[$arkey];
		}
		else
		{
			$db_id=0;
			$db_qn=0;
			$db_rp=0;
			$db_cd=0;
			$db_ct=0;
			$db_ca=0;
		}
	}

	$s0	=$id;
	$s1	="aaaa".$s0;                // Acc ID
	$s2	="bbba".$s0;                // Quantity
	$s3	="ccca".$s0;                // Orig Price
	$s4	="ddda".$s0;                // Price
	$s5	="code".$s0;                // Material Code
	$s6	="eeea".$s0;                // Bid Item
	$s7	="fffa".$s0;                // Question Type Code
	$s8	="ggga".$s0;                // Comm Type Code
	$s9	="hhha".$s0;                // Comm Rate
	$s10="iiia".$s0;                // Quan Calc
	
	
	if (isset($db_rp) && $db_rp!=0)
	{
		$rp	=number_format($db_rp, 2, '.', ''); // BP from Quote
	}
	else
	{
		$rp	=number_format($rp, 2, '.', ''); // BP from DB
	}

	if ($disabled==1)
	{
		if ($db_id==$id)
		{
			echo "                           <input type=\"hidden\" name=\"".$s1."\" value=\"".$s0."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s2."\" value=\"".$db_qn."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s3."\" value=\"".$spaitem."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s4."\" value=\"".$rp."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s7."\" value=\"".$qtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s8."\" value=\"".$commtype."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s9."\" value=\"".$crate."\">\n";
			echo "                           <input type=\"hidden\" name=\"".$s10."\" value=\"".$quan_calc."\">\n";
		}
	}
	else
	{
		if ($qtype==0)
		{
			// Disabled
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			echo                            $item;
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";			
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s2\" value=\"1\">\n";
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif (
		$qtype==2||
		$qtype==39||
		$qtype==55||
		$qtype==58
		)
		{
			// Quantity - NoCharge (Quantity) - Package (Quantity)
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			//echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\">\n";
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s4\" value=\"$rp\" size=\"6\" maxlength=\"15\">\n";
			//$rp
			echo "						  </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif  (
		$qtype==1||
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
		$qtype==41||
		$qtype==42||
		$qtype==43||
		$qtype==45||
		$qtype==46||
		$qtype==47||
		$qtype==69||
		$qtype==70||
		$qtype==72||
		$qtype==77
		)
		{
			// PFT - SQFT - Fixed - Depth - Checkbox - Base+ (All) - Bracket (All)
			// Deck - NoCharge (PFT,SQFT,IA,Gals,Fixed and Base+ Variants)
			// IA (Div by CalcAmt) - IA (Mult by CalcAmt) - Package (Checkbox)
			
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
	
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
	
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\">\n";
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s4\" value=\"$rp\" size=\"6\" maxlength=\"15\">\n";
			//$rp
			echo "						  </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
			}
			else
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif (
		$qtype==18||
		$qtype==19||
		$qtype==21||
		$qtype==22||
		$qtype==40
		)
		{
			// Code (PFT - SQFT - IA - Gallons - No Charge)
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"0\">\n";
			}
	
			//echo "                        </td>\n";
			echo "                      <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"left\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "						<td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "						<td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" SELECTED>\n";
			}
			else
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif ($qtype==20)
		{
			if ($db_id==$id)
			{
				$qryCODE = "SELECT item,rp FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$db_cd."';";
				$resCODE = mssql_query($qryCODE);
				$rowCODE = mssql_fetch_array($resCODE);
			}
	
			// Code (Quantity)
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";

			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
	
			if (!empty($rowCODE['item']))
			{
				echo " (".$rowCODE['item'].")";
			}
	
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\">\n";
			}
	
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
			{
				echo                            $rowCODE['rp'];
			}
	
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
			if ($db_id==$id)
			{
				echo                            $fmtype;
			}
	
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo                            $accpbook;
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif ($qtype==23)
		{
			// Code (Checkbox)
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"left\">\n";
			echo "                        </td>\n";
			echo "                        <td width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" SELECTED>\n";
			}
			else
			{
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif (
		$qtype==24||
		$qtype==25||
		$qtype==27||
		$qtype==28||
		$qtype==29
		)
		{
			// Multiple Choice (PFT - SQFT - IA - Gallons - Checkbox)
			$qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM [".$MAS."accpbook] WHERE officeid='".$officeid."' AND phsid='".$phsid."' AND accid='".$accid."' ORDER BY accid";
			$resC = mssql_query($qryC);
	
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			echo "                           <select name=\"$s1\">\n";
	
			while($rowC = mssql_fetch_row($resC))
			{
				echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
			}
	
			echo "                           </select>\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><img src=\"../images/pixel.gif\"></td>\n";;
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo                            $fmtype;
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"left\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif ($qtype==26)
		{
			// Multiple Choice (Quantity)
			$qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM [".$MAS."accpbook] WHERE officeid=$officeid AND phsid=$phsid AND accid=$accid ORDER BY accid";
			$resC = mssql_query($qryC);
	
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			echo "                           <select name=\"$s1\">\n";
	
			while($rowC = mssql_fetch_row($resC))
			{
				echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
			}
	
			echo "                           </select>\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"20\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo                            $fmtype;
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"left\" NOWRAP>\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s2\" size=\"4\" maxlength=\"5\" value=\"0\"> $accpbook\n";
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
		elseif  ($qtype==33)
		{
			// Bid Items
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">\n";
			echo "							<table width=\"100%\">\n";
			echo "								<tr>\n";
			echo "									<td>\n";
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "									</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>\n";
			echo "                           			<textarea name=\"$s6\" rows=\"2\" cols=\"35\">";
	
			if ($db_id==$id)
			{
				if (isset($_REQUEST['jobid']) && isset($_REQUEST['jadd']))
				{
					$qryC = "SELECT jobid,bidinfo,dbid FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_REQUEST['jobid']."' AND jadd='".$_REQUEST['jadd']."' AND dbid='".$id."';";
				}
				elseif (isset($_REQUEST['njobid']) && isset($_REQUEST['jadd']))
				{
					$qryC = "SELECT jobid,bidinfo,dbid FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_REQUEST['njobid']."' AND jadd='".$_REQUEST['jadd']."' AND dbid='".$id."';";
				}
				else
				{
					$qryC = "SELECT estid,bidinfo,bidaccid FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$id."';";
				}
				$resC = mssql_query($qryC);
				$rowC = mssql_fetch_array($resC);
				echo str_replace("\\", "", $rowC[1]);
			}
	
			echo "										</textarea>\n";
			echo "									</td>\n";
			echo "								</tr>\n";
			echo "							</table>\n";
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
	
			if ($db_id==$id)
			{
				echo "                             <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
				echo "                             <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\" NOWRAP>n/a</td>\n";
				echo "                             <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
				echo "                        		</td>\n";
			}
			else
			{
				echo "                             <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"6\" maxlength=\"20\" value=\"$rp\"></td>\n";
				echo "                             <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\" NOWRAP>n/a</td>\n";
				echo "                             <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
				echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
				echo "                        		</td>\n";
			}
	
			echo "                     </tr>\n";
		}
		elseif  ($qtype==54)
		{
			// Referral
			echo "                     <tr>\n";
			echo "                        <td class=\"$tbg\" valign=\"bottom\" align=\"left\">";
			showdescrip_quote($item,$atrib1,$atrib2,$atrib3);
			echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$id\">\n";
			echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$spaitem\">\n";
			echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rp\">\n";
			echo "                           <input type=\"hidden\" name=\"$s7\" value=\"$qtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s8\" value=\"$commtype\">\n";
			echo "                           <input type=\"hidden\" name=\"$s9\" value=\"$crate\">\n";
			echo "                           <input type=\"hidden\" name=\"$s10\" value=\"$quan_calc\">\n";
			echo "							 <input type=\"hidden\" name=\"#PBi_".trim($id)."\">\n";
			echo "                        </td>\n";
			echo "                        <td class=\"$tbg\" width=\"75\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"60\" valign=\"bottom\" align=\"right\"><img src=\"../images/pixel.gif\"></td>\n";
			echo "                        <td class=\"$tbg\" width=\"25\" valign=\"bottom\" align=\"center\">".$fmtype."</td>\n";
			echo "                        <td class=\"$tbg\" width=\"50\" valign=\"bottom\" align=\"right\">\n";
	
			if ($db_id==$id)
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
			}
			else
			{
				echo "                           <input class=\"bboxl\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
			}
	
			echo "                        </td>\n";
			echo "                     </tr>\n";
		}
	}
}


function pbmatrix()
{
	error_reporting(E_ALL);
	//$MAS	=$_SESSION['pb_code'];
	$_SESSION['pbupdate']++;

	$qry0 = "SELECT estid,esttype FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$qryA = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	echo "<form name=\"updateest\" action=\"../index.php\" method=\"post\" target=\"_parent\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"esttype\" value=\"".$row0['esttype']."\">\n";
	//echo "<input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"add_acc_items\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['viewarray']['estsecid']."\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$_SESSION['estid']."\">\n";
	echo "<input type=\"hidden\" name=\"cid\" value=\"".$_SESSION['viewarray']['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$_SESSION['viewarray']['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"contractamt\" value=\"0.00\">\n";
	echo "<input type=\"hidden\" name=\"showdetail\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"ps1a\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"tzone\" value=\"0\">\n";
	
	if (isset($_REQUEST['esttype']))
	{
		echo "<input type=\"hidden\" name=\"esstype\" value=\"".$_REQUEST['esttype']."\">\n";
	}
	
	echo "<input type=\"hidden\" name=\"#Top\">\n";
	//echo "	<div id=\"masterdiv\">\n";
	echo "		<table class=\"transnb\" width=\"100%\" border=0>\n";
	
	if (isset($_SESSION['pricebookdata']))
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
			echo "					<td class=\"wh_und\" colspan=\"3\">\n";
			echo "						<img src=\"../images/pixel.gif\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"center\">\n";
			echo "						<input class=\"checkboxwh\" type=\"image\" src=\"../images/save.gif\" alt=\"Save Selections\">\n";
			echo "					</td>\n";
			echo "					<td class=\"wh_und\" align=\"right\">\n";
			echo "						<a href=\"pb_select.php?a=r\"><img style=\"border:white\" src=\"../images/arrow_refresh_small.png\" title=\"Refresh Pricebook\"></a>\n";
			echo "					</td>\n";
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
							echo "              <tr>\n";
							echo "					<td class=\"gray\" valign=\"top\" align=\"left\" colspan=\"5\">\n";
		
							showdescrip_hdratribs(trim($pv[11]),trim($pv[12]),trim($pv[13]));
	
							echo "					</td>\n";
							echo "				</tr>\n";
						}

						form_element_ACC_new(trim($pv[0]),trim($pv[1]),trim($pv[2]),
										 trim($pv[3]),trim($pv[4]),trim($pv[5]),
										 trim($pv[6]),trim($pv[7]),trim($pv[8]),
										 trim($pv[9]),trim($pv[10]),trim($pv[11]),
										 trim($pv[12]),trim($pv[13]),trim($pv[14]),
										 trim($pv[15]),trim($pv[16]),trim($pv[17]),
										 trim($rowA['estdata']));
					}
				}
			}
	
			echo "  		</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
	}
	
	//echo 'XX<BR>';
	echo "		</table>\n";
	//echo "	</div>\n";
	echo "</form>\n";
}

echo "<html>\n";
echo "<head>\n";
echo "	<title>JMS PriceBook Select</title>\n";
echo "	<link rel=\"stylesheet\" type=\"text/css\" href=\"../bh_embed.css\" />\n";
echo "	<script language=\"Javascript\" type=\"text/javascript\" src=\"../js/extension.js\"></script>";
echo "</head>\n";
echo "   <body bgcolor=\"#B9D3EE\">\n";

pbmatrix();	

echo "   </body>\n";
echo "</html>\n";

?>