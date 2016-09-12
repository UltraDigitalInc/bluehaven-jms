<?php

function base_inclusionold()
{
	 global $viewarray;
	 
	 $qry = "SELECT * FROM acc WHERE officeid='".$_SESSION['officeid']."' AND qtype BETWEEN '48' AND '52';";
	 $res = mssql_query($qry);
	 
	 while ($row = mssql_fetch_array($res))
	 {
		  $amt=form_element_calc_ACC($row['id'],$row['quan_calc'],0);
		  $qryA = "SELECT abrv FROM mtypes WHERE mid='".$row['mtype']."';";
		  $resA = mssql_query($qryA);
		  $rowA = mssql_fetch_array($resA);
		  
		  echo $row['item']." (".$amt[2]." ".$rowA['abrv']."), ";
	 }
}

function base_inclusion()
{
	 global $viewarray;
	 
	 $qry = "SELECT * FROM acc WHERE officeid='".$_SESSION['officeid']."' AND qtype BETWEEN '48' AND '52';";
	 $res = mssql_query($qry);
	 
	 while ($row = mssql_fetch_array($res))
	 {
		  $amt=form_element_calc_ACC($row['id'],$row['quan_calc'],0);
		  $qryA = "SELECT abrv FROM mtypes WHERE mid='".$row['mtype']."';";
		  $resA = mssql_query($qryA);
		  $rowA = mssql_fetch_array($resA);
		  
		  echo "           <tr>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\">Base</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\">".$row['item']."</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$amt[2]."</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$rowA['abrv']."</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">Incl.</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\"></td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"center\"></td>\n";
		  echo "           </tr>\n";
	 }
}

function base_inclusion_ptr()
{
	 global $viewarray;
	 
	 $qry = "SELECT * FROM acc WHERE officeid='".$_SESSION['officeid']."' AND qtype BETWEEN '48' AND '52';";
	 $res = mssql_query($qry);
	 
	 while ($row = mssql_fetch_array($res))
	 {
		  $amt=form_element_calc_ACC($row['id'],$row['quan_calc'],0);
		  $qryA = "SELECT abrv FROM mtypes WHERE mid='".$row['mtype']."';";
		  $resA = mssql_query($qryA);
		  $rowA = mssql_fetch_array($resA);
		  
		  echo "           <tr>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\">Base</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\">".$row['item']."</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$amt[2]."</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$rowA['abrv']."</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">Incl.</td>\n";
		  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\"></td>\n";
		  echo "           </tr>\n";
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

function update_contract_amt($estid)
{
	$qry = "UPDATE est SET contractamt='".$_POST['c_amt']."' WHERE estid='".$estid."';";
	$res = mssql_query($qry);
	
	viewest_retail($estid);
}

function addadj_init($estid)
{
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"adjins\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
   echo "<table class=\"outer\" align=\"center\" width=\"700px\" border=0>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\">\n";
   echo "         <table align=\"center\">\n";
   echo "            <tr>\n";
   echo "               <th colspan=\"2\" align=\"left\">Add Retail Adjustment for Estimate ID: ".$estid."</th>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td class=\"gray\" valign=\"top\" align=\"right\"><b>Description:</b></td>\n";
   echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><textarea name=\"descrip\" rows=\"5\" cols=\"50\"></textarea></td>\n";
   echo "            <tr>\n";
   echo "            <tr>\n";
   echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><b>Discount Amount:</b></td>\n";
   echo "               <td class=\"gray\" valign=\"top\" align=\"right\">\n";
   echo "                  <input class=\"bbox\" type=\"text\" name=\"adjamt\" value=\"0.00\">\n";
   echo "               </td>\n";
   echo "            </tr>\n";
   echo "               <td class=\"gray\" colspan=\"2\" valign=\"top\" align=\"right\">\n";
   echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Add Discount\">\n";
   echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      <td>\n";
   echo "   <tr>\n";
   echo "</table>\n";
   echo "</form>\n";
}

function addadj_ins($estid)
{
	if ($_POST['adjamt']=="0.00"||$_POST['adjamt']==0)
	{
      echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
      echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
      echo "<input type=\"hidden\" name=\"call\" value=\"adjins\">\n";
      echo "<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
      echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
      echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
      echo "<table class=\"outer\" align=\"center\" width=\"700px\" border=0>\n";
      echo "   <tr>\n";
      echo "      <td class=\"gray\">\n";
      echo "         <table align=\"center\">\n";
      echo "            <tr>\n";
      echo "               <th colspan=\"2\" align=\"left\">Add Retail Adjustment for Estimate ID: ".$estid."</th>\n";
      echo "            </tr>\n";
      echo "            <tr>\n";
      echo "               <td></td>\n";
      echo "               <td align=\"left\"><font color=\"red\">Amount must +/- 0.00</font></td>\n";
      echo "            </tr>\n";
      echo "            <tr>\n";
      echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><b>Discount Amount:</b></td>\n";
      echo "               <td class=\"gray\" valign=\"top\" align=\"left\">\n";
	   echo "                  <input class=\"bboxl\" type=\"text\" name=\"adjamt\" value=\"0.00\">\n";
	   echo "               </td>\n";
      echo "            </tr>\n";
      echo "            <tr>\n";
      echo "               <td class=\"gray\" valign=\"top\" align=\"right\"><b>Description:</b></td>\n";
      echo "               <td class=\"gray\" valign=\"top\" align=\"left\"><textarea name=\"descrip\" rows=\"5\" cols=\"50\">".$_POST['descrip']."</textarea></td>\n";
      echo "            <tr>\n";
      echo "               <td class=\"gray\" colspan=\"2\" valign=\"top\" align=\"right\">\n";
	   echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Add Discount\">\n";
	   echo "               </td>\n";
      echo "            </tr>\n";
      echo "         </table>\n";
      echo "      <td>\n";
      echo "   <tr>\n";
      echo "</table>\n";
      echo "</form>\n";
   }
   else
   {
   	$qryA  = "INSERT INTO est_discounts ";
		$qryA .= "(estid,officeid,descrip,discount) ";
		$qryA .= "VALUES ";
		$qryA .= "('".$_SESSION['estid']."','".$_SESSION['officeid']."','".$_POST['descrip']."','".$_POST['adjamt']."');";
      $resA  = mssql_query($qryA);
   	
   	viewest_retail($_SESSION['estid']);
   }
}

function calc_adjusts($estid)
{
	global $discount;
	$tadj=0;
	$qryA  = "SELECT * FROM est_discounts WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
   $resA  = mssql_query($qryA);
   $nrowA  = mssql_num_rows($resA);
   
   if ($nrowA > 0)
   {
   	while ($rowA  = mssql_fetch_array($resA))
   	{
   		$adj  =$rowA['discount'];
   		$fadj =number_format($adj, 2, '.', '');;
   		echo "           <tr>\n";
   		echo "              <td class=\"lg\" valign=\"bottom\" align=\"left\"><b>Discount</b></td>\n";
         echo "              <td colspan=\"3\" class=\"lg\" valign=\"bottom\" align=\"left\">".$rowA['descrip']."</td>\n";
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
         //echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
         echo "              <td NOWRAP valign=\"bottom\" align=\"center\" width=\"40px\"><input class=\"checkboxgry\" type=\"checkbox\" name=\"aaa".$rowA['id']."\" value=\"".$rowA['id']."\"></td>\n";
         echo "           </tr>\n";
         $tadj=$tadj+$adj;
   	}
   	$ftadj =number_format($tadj, 2, '.', '');;
   	echo "           <tr>\n";
      echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Total Discount:</b></td>\n";
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
   }
	$discount=$tadj;
}

function calc_adjusts_ptr($estid)
{
	global $discount;
	$tadj=0;
	$qryA  = "SELECT * FROM est_discounts WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
   $resA  = mssql_query($qryA);
   $nrowA  = mssql_num_rows($resA);

   if ($nrowA > 0)
   {
   	while ($rowA  = mssql_fetch_array($resA))
   	{
   		$adj  =$rowA['discount'];
   		$fadj =number_format($adj, 2, '.', '');;
   		echo "           <tr>\n";
   		echo "              <td class=\"lg\" valign=\"bottom\" align=\"left\"><b>Discount</b></td>\n";
         echo "              <td colspan=\"3\" class=\"lg\" valign=\"bottom\" align=\"left\">".$rowA['descrip']."</td>\n";
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
         echo "           </tr>\n";
         $tadj=$tadj+$adj;
   	}
   	$ftadj =number_format($tadj, 2, '.', '');;
   	echo "           <tr>\n";
      echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Total Discount:</b></td>\n";
      echo "              <td NOWRAP class=\"wh\" align=\"right\">";

		if ($tadj < 0)
		{
			echo "<font color=\"red\">$ftadj</font>";
		}
		else
		{
			echo $ftadj;
		}

		echo "</td>\n";
      echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
      echo "           </tr>\n";
   }
	$discount=$tadj;
}

function remove_acc()
{
	$i=0;
	$a=0;
	$b=0;
	$qryA  = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
   $resA  = mssql_query($qryA);
   $rowA  = mssql_fetch_array($resA);
   
	//print_r($_POST);

   foreach ($_POST as $n=>$v)
   {
      if (substr($n,0,3)=="xxx")
      {
      	$idata=substr($n,3);
      	$postarray[]=$idata;
      	$i++;
      }
      elseif (substr($n,0,3)=="aaa")
      {
      	$adata=substr($n,3);
      	$apostarray[]=$adata;
      	$a++;
      }
      elseif (substr($n,0,3)=="bbb")
      {
      	$bdata=substr($n,3);
      	$bpostarray[]=$bdata;
      	$b++;
      }
   }

	if ($i > 0)
	{
	   foreach ($postarray as $n=>$v)
      {
	      $dbarray=explode(",",$rowA[0]);
         foreach ($dbarray as $n1 => $v1)
         {
            $itemdata=explode(":",$v1);
            if ($itemdata[0]==$v)
            {
            	// Removes Bid Items from est_bids
            	$qryB  = "DELETE FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$v."';";
               $resB  = mssql_query($qryB);

      	      $diffarray[]=$v1;
	         }
	      }
      }

      $rarray=array_diff($dbarray,$diffarray);
      $racnt=count($rarray);
      $outdata="";
   
      foreach ($rarray as $n => $v)
      {
   	   if (!isset($outdata))
   	   {
   	      $outdata="";
   	   }
   	
   	   if ($racnt!=1)
   	   {
   	      $outdata=$outdata.$v.",";
   	   }
   	   else
   	   {
   		   $outdata=$outdata.$v;
   	   }
   	   $racnt--;
      }
	
	   $qryB  = "UPDATE est_acc_ext SET estdata='".$outdata."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
      $resB = mssql_query($qryB);
   }
   
   if ($a > 0)
   {
   	foreach ($apostarray AS $na => $va)
   	{
   	   $qryC  = "DELETE FROM est_discounts WHERE id='".$va."' AND officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
         $resC = mssql_query($qryC);
      }
   }
   
   if ($b > 0)
   {
   	foreach ($bpostarray AS $nb => $vb)
   	{
   	   $qryD  = "DELETE FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$vb."';";
         $resD = mssql_query($qryD);
      }
   }
	viewest_retail($_SESSION['estid']);
}

function showdescrip($i,$a1,$a2,$a3)
{
	if (strlen($i) > 1)
	{
	   //echo "<b>$i</b><br>\n";
	   echo "$i<br>\n";
	}

	if (strlen($a1) > 1)
	{
		echo "- <font class=\"7pt\">$a1</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "<br>- <font class=\"7pt\">$a2</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "<br>- <font class=\"7pt\">$a3</font>\n";
	}
}

function showdescrip_hdr($i,$a1,$a2,$a3)
{
	if (strlen($i) > 1)
	{
	   echo "<font color=\"blue\"><b>$i</b></font><br>\n";
	}

	if (strlen($a1) > 1)
	{
		echo "- <font class=\"7pt\">$a1</font>\n";
	}
	if (strlen($a2) > 1)
	{
		echo "<br>- <font class=\"7pt\">$a2</font>\n";
	}
	if (strlen($a3) > 1)
	{
		echo "<br>- <font class=\"7pt\">$a3</font>\n";
	}
}

function getcodeitem($code)
{
	$qryA  = "SELECT * FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$code."';";
	//$qryA  = "SELECT * FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='1139';";
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


function calc_cubic_feet($pft,$sqft,$shallow,$middle,$deep)
{
	$cf=$sqft*(($shallow+$middle+$deep)/3);
   return $cf;
}

function calc_internal_area($pft,$sqft,$shallow,$middle,$deep)
{
	$ia=(($pft*($shallow+$middle+$middle+$deep))/4)+$sqft;
	
	if (is_float($ia))
	{
		$ia=round($ia);
	}
	
   return $ia;
}

function calc_gallons($pft,$sqft,$shallow,$middle,$deep)
{
	$gals=($sqft*($shallow+$middle+$middle+$deep)/4)*7.5;
	
	if (is_float($gals))
	{
		$gals=round($gals);
	}
	
   return $gals;
}

function setitemlist($data,$searchval)
{
   	//This function takes a multidimension Array ($data) with cell/content delimiters and returns a match based
	   $celldelim=",";
	   $contdelim=":";
	   $data1=explode($celldelim,$data);
	   foreach ($data1 as $n1=>$v1)
	   {
	      $v1array=explode($contdelim,$v1);
	      foreach($v1array as $key=>$row)
         {
		      if ($row==$searchval)
		      {
		   	   //$itemar[]=array(0=>$v1array[$key+1],1=>$v1array[$key-4]);
		   	   $itemar[]=array(0=>$v1array[$key+1],1=>$v1array[2],2=>$v1array[3],3=>$v1array[4]);
		   	   //$itemar[]=$v1array[$key-4];
		      }
		   }
 	   }
 	   if (empty($itemar[0]))
 	   {
 	   	$itemar=array(0=>0);
      }
      //echo "InterAR: <pre>";
		//print_r($itemar);
		//echo "</pre>";
      return $itemar;
}

function estAdata_init_new()
{
   //print_r($_POST);
   $icount=0;
   if (is_array($_POST))
   {
      $estout='';
      foreach ($_POST as $n=>$v)
      {
         if (substr($n,0,4)=="code")
         {
            $asid=substr($n,4);
            if ($_POST['code'.$asid] > 0)
            {
               $icount++;
            }
         }
      }
      //echo $icount;

      foreach ($_POST as $n=>$v)
      {
         if (substr($n,0,4)=="code")
         {
            $asid=substr($n,4);
            if ($_POST['code'.$asid] > 0)
            {
            	if (strpos($_POST['code'.$asid], ",")) //Code delimiter search and ACC_Code/MET_Code setup
            	{
            		$acc_data=explode(",",$_POST['code'.$asid]);
            		$acc_code=$acc_data[0];
            		$met_code=$acc_data[1];
            	}
            	else
            	{
            		$acc_data=array(0=>$_POST['code'.$asid],1=>0);
            		$acc_code=$acc_data[0];
            		$met_code=$acc_data[1];
            	}
            	
               $qryA  = "SELECT id,qtype FROM acc WHERE officeid='".$_SESSION['officeid']."' AND aid='".$acc_code."';";
               $resA  = mssql_query($qryA);
               $nrowA = mssql_num_rows($resA);
               
               //echo $qryA;
               //echo "<br>";
               //echo $nrowA;
               //echo "<br>";
               //Error Code Setup (0=No Error, 1=Multi ACC codes, 2=ACC Code not exist, 3=Quantity Req, 4=Mat Code req
               if ($nrowA==0)
               {
               	$ecode=2;
               }
               elseif ($nrowA==1)
               {
               	$rowA = mssql_fetch_array($resA);
               	//echo $rowA['qtype'];
               	//echo "<br>";
               	if ($rowA['qtype'] >=18 && $rowA['qtype'] <=23 && $met_code==0)
               	{
							$ecode=4;
               	}
               	elseif ($rowA['qtype']==2||$rowA['qtype']==20||$rowA['qtype']==25)
               	{
               		if ($_POST['quan'.$asid]==0)
               		{
               		   $ecode=3;
               		}
               		else
               		{
               			$ecode=0;
               		}
               	}
               	else
               	{
               	   $ecode=0;
               	}
               }
               elseif ($nrowA > 1)
               {
               	$ecode=1;
               }
               else
               {
               	$ecode=0;
               }

               if ($icount==1)
               {
                  $estd=$asid.':'.$acc_code.':'.$met_code.':'.$_POST['quan'.$asid].':'.$ecode;
               }
               else
               {
                  $estd=$asid.':'.$acc_code.':'.$met_code.':'.$_POST['quan'.$asid].':'.$ecode.',';
               }
               $estout=$estout.$estd;
               $icount--;
            }
         }
      }
   }
   //echo "L-EstOut: $estout<br>\n";
   return $estout;
}

function estAdata_init()
{
   //print_r($_POST);
   // aaaa = item id
   // bbba = quantity
   // ccca = spaitem (0=no 1=yes)
   // ddda = Retail Price
   // xxx+ = Accessory Members

   $icount=0;
   if (is_array($_POST))
   {
      $estout='';
      foreach ($_POST as $n=>$v)
      {
         if (substr($n,0,4)=="bbba")
         {
            $asid=substr($n,4);
            if ($_POST['bbba'.$asid] > 0)
            {
               $icount++;
            }
         }
      }

      foreach ($_POST as $n=>$v)
      {
         if (substr($n,0,4)=="bbba")
         {
            $asid=substr($n,4);
            if ($_POST['bbba'.$asid] > 0)
            {
               if (array_key_exists("aaaa".$asid,$_POST))
               {
                  if (array_key_exists("ccca".$asid,$_POST))
                  {
                  	if (array_key_exists("ddda".$asid,$_POST))
                  	{
                  		$c_cnt=0;
                  		$qryA  = "SELECT cid FROM rclinks_l WHERE officeid='".$_SESSION['officeid']."' AND rid='".$_POST['aaaa'.$asid]."';";
                        $resA  = mssql_query($qryA);
                        $nrowA = mssql_num_rows($resA);
                        
                        $qryB  = "SELECT cid FROM rclinks_m WHERE officeid='".$_SESSION['officeid']."' AND rid='".$_POST['aaaa'.$asid]."';";
                        $resB  = mssql_query($qryB);
                        $nrowB = mssql_num_rows($resB);
                        
                        $c_cnt=$nrowA+$nrowB;
                        
                        if ($nrowA > 0)
                        {
                        	$Litems=$nrowA;
                           while ($rowA  = mssql_fetch_row($resA))
                           {
                           	if ($Litems==$nrowA)
                           	{
                        	      $L_citems=":L:$rowA[0]";
                        	   }
										else
										{
											$L_citems=$L_citems.":L:$rowA[0]";
										}
                        	   $Litems--;
                  	      }
                  	   }

                        if ($nrowB > 0)
                        {
                        	$Mitems=$nrowB;
                           while ($rowB  = mssql_fetch_row($resB))
                           {
                           	if ($Mitems==$nrowB)
                           	{
                           		$M_citems=":M:$rowB[0]";
                           	}
                           	else
                           	{
                        	      $M_citems=$M_citems.":M:$rowB[0]";
                        	   }
                        	   $Mitems--;
                  	      }
                  	   }
                  	   
                  	   if ($nrowA > 0 && $nrowB > 0)
                  	   {
                  	   	$ritems=$L_citems.$M_citems;
                  	   }
                  	   elseif ($nrowA > 0 && $nrowB < 1)
                  	   {
                  	   	$ritems=$L_citems;
                  	   }
                  	   elseif ($nrowA < 1 && $nrowB > 0)
                  	   {
                  	   	$ritems=$M_citems;
                  	   }
                  	   
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
                        	if ($c_cnt > 0)
                        	{
                              $estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.':'.$c_cnt.$ritems;
                           }
                           else
                           {
                           	$estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.':'.$c_cnt;
                           }
                        }
                        else
                        {
                        	if ($c_cnt > 0)
                        	{
                              $estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.':'.$c_cnt.$ritems.',';
                           }
                           else
                           {
                              $estd=$_POST['aaaa'.$asid].':'.$_POST['ccca'.$asid].':'.$_POST['bbba'.$asid].':'.$_POST['ddda'.$asid].':'.$code.':'.$c_cnt.',';
                           }
                        }
                        $estout=$estout.$estd;
                        $icount--;
                     }
                  }
               }
               // Writing Bid Items
               //if (array_key_exists("eeea".$asid,$_POST))
               //{
               //	echo $asid." : ".$_POST['eeea'.$asid];
               //}
               
            }
         }
      }
   }
   //echo "L-EstOut: $estout<br>\n";
   return $estout;
}

function estLdata_init()
{
	global $tchrg;
   //print_r($_POST);
   // aaal = item id
   // bbbl = quantity
   // cccl = base item status (0=baseitem 1=option)
   $icount=0;
   if (is_array($_POST))
   {
      $estout='';
      foreach ($_POST as $n=>$v)
      {
         if (substr($n,0,4)=="bbbl")
         {
            $asid=substr($n,4);
            if ($_POST['bbbl'.$asid] > 0)
            {
               $icount++;
            }
         }
      }
      
      foreach ($_POST as $n=>$v)
      {
         if (substr($n,0,4)=="bbbl")
         {
            $asid=substr($n,4);
            if ($_POST['bbbl'.$asid] > 0)
            {
               if (array_key_exists("aaal".$asid,$_POST))
               {
                  if (array_key_exists("cccl".$asid,$_POST))
                  {
                     if (array_key_exists("eeel".$asid,$_POST))
                     {
                        if ($_POST['eeel'.$asid]!=1)
                        {
                           $relitem=setrelatedLitem($_POST['aaal'.$asid]);
                        }
                        else
                        {
                           $relitem=0;
                        }
                     }
                     else
                     {
                        $relitem=setrelatedLitem($_POST['aaal'.$asid]);
                     }
                     
                     if ($icount==1)
                     {
                        if ($relitem!=0)
                        {
                           $estd=$_POST['aaal'.$asid].':'.$_POST['cccl'.$asid].':L:'.$_POST['bbbl'.$asid].':'.$asid.','.$relitem;
                        }
                        else
                        {
                           $estd=$_POST['aaal'.$asid].':'.$_POST['cccl'.$asid].':L:'.$_POST['bbbl'.$asid].':'.$asid;
                        }
                     }
                     else
                     {
                        if ($relitem!=0)
                        {
                           $estd=$_POST['aaal'.$asid].':'.$_POST['cccl'.$asid].':L:'.$_POST['bbbl'.$asid].':'.$asid.','.$relitem.',';
                        }
                        else
                        {
                           $estd=$_POST['aaal'.$asid].':'.$_POST['cccl'.$asid].':L:'.$_POST['bbbl'.$asid].':'.$asid.',';
                        }
                     }
                     $estout=$estout.$estd;
                     $icount--;
                  }
               }
            }
         }
      }
   }
   
   if (!empty($_POST['tzone'])) // Sets Travel Charges
   {
      $qry = "SELECT bprice,rprice,zcharge FROM accpbook WHERE officeid=".$_SESSION['officeid']." AND phsid=40 AND zcharge=".$_POST['tzone'].";";
      $res  = mssql_query($qry);
      $row  = mssql_fetch_row($res);
      $tchrg=array(0=>$row[0],1=>$row[1]);
   }
   //echo "L-Icount: $icount<br>\n";
   //echo "L-EstOut: $estout<br>\n";
   return $estout;
}

function estMdata_init()
{
   //print_r($_POST);
   // aaam = item id
   // bbbm = quantity
   // cccm = base item status (0=baseitem 1=option)
   $icount=0;
   if (is_array($_POST))
   {
      $estout='';
      foreach ($_POST as $n=>$v)
      {
         if (substr($n,0,4)=="bbbm")
         {
            $asid=substr($n,4);
            if ($_POST['bbbm'.$asid] > 0)
            {
               $icount++;
            }
         }
      }

      //echo "L-Icount:.$icount<br>\n";
      
      foreach ($_POST as $n=>$v)
      {
         if (substr($n,0,4)=="bbbm")
         {
            $asid=substr($n,4);
            if ($_POST['bbbm'.$asid] > 0)
            {
               if (array_key_exists("aaam".$asid,$_POST))
               {
                  if (array_key_exists("cccm".$asid,$_POST))
                  {
                     if ($icount==1)
                     {
                        $estd=$_POST['aaam'.$asid].':'.$_POST['cccm'.$asid].':M:'.$_POST['bbbm'.$asid].':'.$asid;
                     }
                     else
                     {
                        $estd=$_POST['aaam'.$asid].':'.$_POST['cccm'.$asid].':M:'.$_POST['bbbm'.$asid].':'.$asid.',';
                     }
                     $estout=$estout.$estd;
                     $icount--;
                  }
               }
            }
         }
      }
   }
   //echo "L-Icount: $icount<br>\n";
   //echo "L-EstOut: $estout<br>\n";
   return $estout;
}

function setrelatedLitem($id)
{
   $qry  = "SELECT id,raccid,quantity FROM accpbook WHERE id='$id'; ";
   $res  = mssql_query($qry);
   $row  = mssql_fetch_row($res);

   if ($row[1]!=0)
   {
      $qryA  = "SELECT id,quantity,accid FROM accpbook WHERE id='$row[1]'; ";
      $resA  = mssql_query($qryA);
      $rowA  = mssql_fetch_row($resA);

      $setval="$rowA[0]:0:L:1:$rowA[2]";
      return $setval;
   }
   else
   {
      return 0;
   }
}

function savecust()
{
	//echo "<br>Starting Estimating Matrix...";
      $qryA   = "exec sp_insert_cinfo ";
      $qryA  .= "@securityid='".$_SESSION['securityid']."', ";
      $qryA  .= "@officeid='".$_SESSION['officeid']."', ";
      $qryA  .= "@recdate='".$_POST['recdate']."', ";
      $qryA  .= "@cfname='".$_POST['cfname']."', ";
      $qryA  .= "@clname='".$_POST['clname']."', ";
      $qryA  .= "@caddr1='".$_POST['caddr1']."', ";
      $qryA  .= "@ccity='".$_POST['ccity']."', ";
      $qryA  .= "@cstate='".$_POST['cstate']."', ";
      $qryA  .= "@czip1='".$_POST['czip1']."', ";
      $qryA  .= "@czip2='".$_POST['czip2']."', ";
      $qryA  .= "@ccounty='".$_POST['ccounty']."', ";
      $qryA  .= "@cmap='".$_POST['cmap']."', ";
      
      if (empty($_POST['ssame']))
      {
      	$qryA  .= "@ssame='0', ";
      	$qryA  .= "@saddr1='".$_POST['saddr1']."', ";
         $qryA  .= "@scity='".$_POST['scity']."', ";
         $qryA  .= "@sstate='".$_POST['sstate']."', ";
         $qryA  .= "@szip1='".$_POST['szip1']."', ";
         $qryA  .= "@szip2='".$_POST['szip2']."', ";
         $qryA  .= "@scounty='".$_POST['scounty']."', ";
         $qryA  .= "@smap='".$_POST['smap']."', ";
      }
      else
      {
      	$qryA  .= "@ssame='".$_POST['ssame']."', ";
         $qryA  .= "@saddr1='".$_POST['caddr1']."', ";
         $qryA  .= "@scity='".$_POST['ccity']."', ";
         $qryA  .= "@sstate='".$_POST['cstate']."', ";
         $qryA  .= "@szip1='".$_POST['czip1']."', ";
         $qryA  .= "@szip2='".$_POST['czip2']."', ";
         $qryA  .= "@scounty='".$_POST['ccounty']."', ";
         $qryA  .= "@smap='".$_POST['cmap']."', ";
      }
      
      $qryA  .= "@chome='".$_POST['chome']."', ";
      $qryA  .= "@cwork='".$_POST['cwork']."', ";
      $qryA  .= "@ccell='".$_POST['ccell']."', ";
      $qryA  .= "@cfax='".$_POST['cfax']."', ";
      $qryA  .= "@cemail='".$_POST['cemail']."', ";
      $qryA  .= "@cconph='".$_POST['cconph']."', ";
      $qryA  .= "@ccontime='".$_POST['ccontime']."', ";
      $qryA  .= "@comments='".$_POST['comments']."';";
      $resA   = mssql_query($qryA);
      $rowA   = mssql_fetch_row($resA);

	/*
   echo "Row Info:<br>\n";
   echo $qryA;
	echo "Entered Customer Info:<br>\n";
	echo "<pre>\n";
	print_r($rowA);
   echo "</pre>\n";
   echo "<br>Building your pool...";
   */
   matrix0($rowA[0]);
}

function pop_updateest($estid)
{
   $officeid=$_SESSION['officeid'];
   $securityid=$_SESSION['securityid'];
   
   if ($_POST['subq']=="L")
   {
      $estdata=estLdata_init();
   }
   elseif  ($_POST['subq']=="M")
   {
      $estdata=estMdata_init();
   }
   
   if ($_POST['subq']=="L")
   {
      $qryA   = "exec sp_update_est_labor_ext ";
   }
   elseif  ($_POST['subq']=="M")
   {
      $qryA   = "exec sp_update_est_inv_ext ";
   }
   $qryA  .= "@officeid='$officeid', ";
   $qryA  .= "@securityid='$securityid', ";
   $qryA  .= "@estid='$estid', ";
   $qryA  .= "@estdata='$estdata'; ";
   $resA   = mssql_query($qryA);
   $rowA   = mssql_fetch_row($resA);
   
   //return $rowA[0];
   echo "<b>Updating Items for: $rowA[0]</b>";
   //echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=est&call=view&estid=$estid\">";
}

function saveest0($uid1)
{
	global $tchrg,$estid;
	$estAdata_init =estAdata_init();
	$uid2				=session_id().".".$_SESSION['securityid'];
   
   if (!isset($uid1)||$uid1!=$uid2)
   {
		echo "<b>Transition Error Occured!</b>";
		exit;
   }
   else
   {
      $uid=md5($uid2.".".time().".".$_SERVER['REMOTE_ADDR']);
      //$uid=$uid2;
      //echo $uid."<br>";
   }
   
   $qry  = "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND unique_id='".$uid."'; ";
   $res  = mssql_query($qry);
   $row  = mssql_fetch_row($res);
   $nrow = mssql_num_rows($res);
   
   //echo $qry."<br>";
   
   if (!isset($_POST['phone']))
   {
      $phone="";
   }
   else
   {
      $phone=$_POST['phone'];
   }
   if (!isset($_POST['refto']))
   {
      $refto="";
   }
   else
   {
      $refto=$_POST['refto'];
   }

   if ($nrow==0)
   {
   	$qryC   = "exec sp_insert_cinfo ";
      $qryC  .= "@securityid='".$_SESSION['securityid']."', ";
      $qryC  .= "@officeid='".$_SESSION['officeid']."', ";
      $qryC  .= "@recdate='".$_POST['recdate']."', ";
      $qryC  .= "@cfname='".$_POST['cfname']."', ";
      $qryC  .= "@clname='".$_POST['clname']."', ";
      $qryC  .= "@caddr1='".$_POST['caddr1']."', ";
      $qryC  .= "@ccity='".$_POST['ccity']."', ";
      $qryC  .= "@cstate='".$_POST['cstate']."', ";
      $qryC  .= "@czip1='".$_POST['czip1']."', ";
      $qryC  .= "@czip2='".$_POST['czip2']."', ";
      $qryC  .= "@ccounty='".$_POST['ccounty']."', ";
      $qryC  .= "@cmap='".$_POST['cmap']."', ";

      if (empty($_POST['ssame']))
      {
      	$qryC  .= "@ssame='0', ";
      	$qryC  .= "@saddr1='".$_POST['saddr1']."', ";
         $qryC  .= "@scity='".$_POST['scity']."', ";
         $qryC  .= "@sstate='".$_POST['sstate']."', ";
         $qryC  .= "@szip1='".$_POST['szip1']."', ";
         $qryC  .= "@szip2='".$_POST['szip2']."', ";
         $qryC  .= "@scounty='".$_POST['scounty']."', ";
         $qryC  .= "@smap='".$_POST['smap']."', ";
      }
      else
      {
      	$qryC  .= "@ssame='".$_POST['ssame']."', ";
         $qryC  .= "@saddr1='".$_POST['caddr1']."', ";
         $qryC  .= "@scity='".$_POST['ccity']."', ";
         $qryC  .= "@sstate='".$_POST['cstate']."', ";
         $qryC  .= "@szip1='".$_POST['czip1']."', ";
         $qryC  .= "@szip2='".$_POST['czip2']."', ";
         $qryC  .= "@scounty='".$_POST['ccounty']."', ";
         $qryC  .= "@smap='".$_POST['cmap']."', ";
      }

      $qryC  .= "@chome='".$_POST['chome']."', ";
      $qryC  .= "@cwork='".$_POST['cwork']."', ";
      $qryC  .= "@ccell='".$_POST['ccell']."', ";
      $qryC  .= "@cfax='".$_POST['cfax']."', ";
      $qryC  .= "@cemail='".$_POST['cemail']."', ";
      $qryC  .= "@cconph='".$_POST['cconph']."', ";
      $qryC  .= "@ccontime='".$_POST['ccontime']."', ";
      $qryC  .= "@comments='".$_POST['comments']."';";
      $resC   = mssql_query($qryC);
      $rowC   = mssql_fetch_row($resC);
      
   	
      $qryA   = "exec sp_insertest ";
      $qryA  .= "@officeid='".$_SESSION['officeid']."', ";
      $qryA  .= "@securityid='".$_SESSION['securityid']."', ";
      $qryA  .= "@status='0', ";
      $qryA  .= "@pft='".$_POST['ps1']."', ";
      $qryA  .= "@sqft='".$_POST['ps2']."', ";
      $qryA  .= "@shal='".$_POST['ps5']."', ";
      $qryA  .= "@mid='".$_POST['ps6']."', ";
      $qryA  .= "@deep='".$_POST['ps7']."', ";
      $qryA  .= "@deck='".$_POST['deck']."', ";
      $qryA  .= "@spa_pft='".$_POST['spa2']."', ";
      $qryA  .= "@spa_sqft='".$_POST['spa3']."', ";
      $qryA  .= "@spatype='".$_POST['spa1']."', ";
      $qryA  .= "@tzone='".$_POST['tzone']."', ";
		$qryA  .= "@erun='".$_POST['erun']."', ";
		$qryA  .= "@prun='".$_POST['prun']."', ";
      $qryA  .= "@btchrg='".$tchrg[0]."', ";
      $qryA  .= "@rtchrg='".$tchrg[1]."', ";
      $qryA  .= "@contractamt='".$_POST['contractamt']."', ";
      $qryA  .= "@refto='$refto', ";
      $qryA  .= "@est_cost='0', ";
      $qryA  .= "@cid='".$rowC[0]."', ";
      $qryA  .= "@unique_id='".$uid."', ";
      $qryA  .= "@estAdata='".$estAdata_init."';";
      $resA   = mssql_query($qryA);
      $rowA   = mssql_fetch_row($resA);
      
		$_SESSION['estid']=$rowA[0];
		
      // Writing Bid Items
      foreach ($_POST as $n=>$v)
      {
         if (substr($n,0,4)=="bbba")
         {
            $asid=substr($n,4);
            if ($_POST['bbba'.$asid] > 0)
            {
               if (array_key_exists("eeea".$asid,$_POST))
               {
               	$qryB  = "INSERT INTO est_bids (officeid,estid,bidinfo,bidaccid) VALUES ('".$_SESSION['officeid']."','".$_SESSION['estid']."','".$_POST['eeea'.$asid]."','$asid');";
                  $resB  = mssql_query($qryB);
               }
            }
         }
      }
      echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=est&call=view_retail&estid=".$_SESSION['estid']."\">";
      //viewest_retail($_SESSION['estid']);
   }
   else
   {
      echo "<b>This estimate has already been submitted. Please do not Refresh this page or hit F5. Click <a href=\"".$_SERVER['PHP_SELF']."?action=est&call=new\">Here</a> to create a New Estimate.</b><br>";
      exit;
   }
}

function saveest1()
{
	   $estAdata_init =$_POST['estAdata'];
      $estLdata_init =estLdata_init();
      $estMdata_init =estMdata_init();

      $qryA   = "exec sp_saveest0 ";
      $qryA  .= "@officeid='".$_SESSION['officeid']."', ";
      $qryA  .= "@securityid='".$_SESSION['securityid']."', ";
      $qryA  .= "@estid='".$_POST['estid']."', ";
      $qryA  .= "@cid='".$_POST['cid']."', ";
      $qryA  .= "@unique_id='$post_unique_id', ";
      $qryA  .= "@estAdata='$estAdata_init', ";
      $qryA  .= "@estLdata='$estLdata_init', ";
      $qryA  .= "@estMdata='$estMdata_init';";
      $resA   = mssql_query($qryA);
      $rowA   = mssql_fetch_row($resA);

		//echo "Qry Data:<br>";
		//echo $qryA;
		//echo "Row Data:<br>";
		//print_r($rowA);

      matrix2($rowA[0]);
}

function updateest($estid)
{
	$qry = "SELECT bprice,rprice,zcharge FROM accpbook WHERE officeid=".$_SESSION['officeid']." AND phsid=40 AND zcharge=".$_POST['tzone'].";";
   $res  = mssql_query($qry);
   $row  = mssql_fetch_row($res);
   $tchrg=array(0=>$row[0],1=>$row[1]);
	
   if (!isset($_POST['cfname']))
   {
      $cfname="";
   }
   else
   {
      $cfname=$_POST['cfname'];
   }
   
   if (!isset($_POST['clname']))
   {
      $clname="";
   }
   else
   {
      $clname=$_POST['clname'];
   }
   
   if (!isset($_POST['phone']))
   {
      $phone="";
   }
   else
   {
      $phone=$_POST['phone'];
   }
   
   if (!isset($_POST['refto']))
   {
      $refto="";
   }
   else
   {
      $refto=$_POST['refto'];
   }
   
   if (!isset($_POST['est_cost']))
   {
      $est_cost=0;
   }
   else
   {
      $est_cost=$_POST['est_cost'];
   }
   
      $qryA  = "exec sp_updateest ";
      $qryA  .= "@estid='$estid', ";
      $qryA  .= "@officeid='".$_SESSION['officeid']."', ";
      $qryA  .= "@securityid='".$_SESSION['securityid']."', ";
      $qryA  .= "@cfname='".$_POST['cfname']."', ";
      $qryA  .= "@clname='".$_POST['clname']."', ";
      $qryA  .= "@saddr1='".$_POST['saddr1']."', ";
      $qryA  .= "@scity='".$_POST['scity']."', ";
      $qryA  .= "@sstate='".$_POST['sstate']."', ";
      $qryA  .= "@szip1='".$_POST['szip1']."', ";
      $qryA  .= "@scounty='".$_POST['scounty']."', ";
      $qryA  .= "@chome='".$_POST['chome']."', ";
      $qryA  .= "@ccell='".$_POST['ccell']."', ";
      $qryA  .= "@status='".$_POST['status']."', ";
      $qryA  .= "@pft='".$_POST['ps1']."', ";
      $qryA  .= "@sqft='".$_POST['ps2']."', ";
      $qryA  .= "@shal='".$_POST['ps5']."', ";
      $qryA  .= "@mid='".$_POST['ps6']."', ";
      $qryA  .= "@deep='".$_POST['ps7']."', ";
      $qryA  .= "@deck='".$_POST['deck']."', ";
      $qryA  .= "@spa_pft='".$_POST['spa2']."', ";
      $qryA  .= "@spa_sqft='".$_POST['spa3']."', ";
      $qryA  .= "@spatype='".$_POST['spa1']."', ";
      $qryA  .= "@tzone='".$_POST['tzone']."', ";
		$qryA  .= "@erun='".$_POST['erun']."', ";
		$qryA  .= "@prun='".$_POST['prun']."', ";
      $qryA  .= "@btchrg='".$tchrg[0]."', ";
      $qryA  .= "@rtchrg='".$tchrg[1]."', ";
      $qryA  .= "@contractamt='".$_POST['contractamt']."', ";
      $qryA  .= "@refto='$refto', ";
      $qryA  .= "@est_cost='$est_cost', ";
      $qryA  .= "@updateby='".$_SESSION['securityid']."'; ";
      $resA   = mssql_query($qryA);

		//print_r($_POST);
		//echo $qryA;
      //echo "Updating Estimate Info...";
      echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=est&call=view_retail&estid=".$estid."\">";
}

function add_acc_items($estid)
{
	$estdata=estAdata_init();
	
	$qryA  = "sp_updateest_ext @estid='".$estid."',@officeid='".$_SESSION['officeid']."',@estdata='".$estdata."';";
   $resA   = mssql_query($qryA);
   
   foreach ($_POST as $n=>$v)
   {
      if (substr($n,0,4)=="bbba")
      {
         $asid=substr($n,4);
         if ($_POST['bbba'.$asid] > 0)
            {
               if (array_key_exists("eeea".$asid,$_POST))
               {
               	$qryB  = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
                  $resB  = mssql_query($qryB);
                  $rowB  = mssql_fetch_array($resB);
                  $nrowB = mssql_num_rows($resB);
                  
                  //echo $_POST['eeea'.$asid]."<br>";
                  //echo $rowB['bidinfo']."<br>";
                  //echo $nrowB."<br>";
                  
                  if ($nrowB < 1)
                  {
                  	$qryC  = "INSERT INTO est_bids (officeid,estid,bidinfo,bidaccid) VALUES ('".$_SESSION['officeid']."','".$_SESSION['estid']."','".$_POST['eeea'.$asid]."','".$asid."');";
                     $resC  = mssql_query($qryC);
                  }
                  elseif ($_POST['eeea'.$asid]!=$rowB['bidinfo'])
                  {
                  	$qryC  = "UPDATE est_bids SET bidinfo='".$_POST['eeea'.$asid]."' WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$asid."';";
                     $resC  = mssql_query($qryC);
                  }
               }
            }
      }
   }
   echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$_SERVER['PHP_SELF']."?action=est&call=view_retail&estid=$estid\">";
}

function listest()
{
   $officeid=$_SESSION['officeid'];
   $securityid=$_SESSION['securityid'];
   
   if (isset($_GET['order']))
   {
      if ($_GET['order']=="added"||$_GET['order']=="updated"||$_GET['order']=="submitted")
      {
         $order=$_GET['order'];
         $dir="DESC";
      }
      else
      {
         $order=$_GET['order'];
         $dir="ASC";
      }
   }
   else
   {
      $order="estid";
      $dir="ASC";
   }

   if ($_SESSION['jlev'] >= 9)
   {
      $qry   = "SELECT estid,added,updated,status,submitted,id,cfname,clname,phone,cid,securityid,contractamt,jobid FROM est WHERE officeid='".$_SESSION['officeid']."' ORDER BY ".$order." ".$dir.";";
   }
   else
   {
      $qry   = "SELECT estid,added,updated,status,submitted,id,cfname,clname,phone,cid,securityid,contractamt,jobid FROM est WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$_SESSION['securityid']."' ORDER BY ".$order." ".$dir.";";
   }

   $res   = mssql_query($qry);
   $nrows = mssql_num_rows($res);
   
   if ($nrows < 1)
   {
   	echo "<table align=\"center\" width=\"60%\">\n";
      echo "   <tr>\n";
      echo "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
      echo "   <input type=\"hidden\" name=\"action\" value=\"est\">\n";
      echo "   <input type=\"hidden\" name=\"call\" value=\"new\">\n";
      echo "      <td class=\"gray\">\n";
      echo "         <h3>No Estimates on File!</h3><br>Click <input type=\"submit\" class=\"buttondkgry\" value=\"Here\"> to Create an Estimate.\n";
      echo "      </td>\n";
      echo "   </form>\n";
      echo "   </tr>\n";
      echo "</table>\n";
   }
   else
   {
      echo "<table align=\"center\" width=\"85%\">\n";
      echo "   <tr>\n";
      echo "      <td class=\"gray\">\n";
      echo "         <table width=\"100%\">\n";
      echo "            <tr>\n";
      echo "               <td align=\"left\">\n";
      echo "                  <table width=\"100%\">\n";
      echo "                     <tr>\n";
      echo "                        <td align=\"right\" class=\"und\"><b>Estimate</b> Status Codes:</td>\n";
      echo "                        <td align=\"center\" class=\"wh_und\" width=\"100\"><b>Unsubmitted</b></td>\n";
      echo "                        <td align=\"center\" class=\"blu_und\" width=\"100\"><b>Submitted</b></td>\n";
      echo "                        <td align=\"center\" class=\"grn_und\" width=\"100\"><b>Addendum</b></td>\n";
      echo "                        <td align=\"center\" class=\"red_und\" width=\"100\"><b>Rejected</b></td>\n";
      echo "                     </tr>\n";
      echo "                   </table>\n";
      echo "                </td>\n";
      echo "            </tr>\n";
      echo "            <tr>\n";
      echo "               <td align=\"left\">\n";
      echo "                  <table width=\"100%\" bgcolor=\"white\">\n";
      echo "                  <tr>\n";
      echo "                     <td align=\"right\"><a href=\"".$_SERVER['PHP_SELF']."?action=est&call=list&order=id\">Est ID</a></td>\n";
      echo "                     <td align=\"right\"></td>\n";
      echo "                     <td align=\"left\"><a href=\"".$_SERVER['PHP_SELF']."?action=est&call=list&order=clname\">&nbsp Customer</a></td>\n";
      echo "                     <td align=\"left\"><a href=\"".$_SERVER['PHP_SELF']."?action=est&call=list&order=phone\">&nbsp Phone</a></td>\n";
      echo "                     <td align=\"center\">&nbsp Cont Amt</td>\n";
      echo "                     <td align=\"left\"><a href=\"".$_SERVER['PHP_SELF']."?action=est&call=list&order=phone\">&nbsp Salesperson</a></td>\n";
      echo "                     <td align=\"left\"><a href=\"".$_SERVER['PHP_SELF']."?action=est&call=list&order=added\">&nbsp Date Added</a></td>\n";
      echo "                     <td align=\"left\"><a href=\"".$_SERVER['PHP_SELF']."?action=est&call=list&order=updated\">&nbsp Date Updated</a></td>\n";
      echo "                     <td align=\"left\"><a href=\"".$_SERVER['PHP_SELF']."?action=est&call=list&order=submitted\">&nbsp Date Submitted</a></td>\n";
      echo "                     <td align=\"center\"><a href=\"".$_SERVER['PHP_SELF']."?action=est&call=list\">Status</a></td>\n";
      echo "                     <td align=\"center\"></td>\n";
      echo "                     <td align=\"right\"></td>\n";
      echo "                  </tr>\n";
   
      while($row=mssql_fetch_row($res))
      {
      	/*
      	if ($_SESSION['jlev'] >= 9)
         {
            $qryA   = "SELECT estid,added,updated,status,submitted,id,cfname,clname,phone,estaddid FROM est_addendum WHERE estid=$row[0] AND officeid=$officeid ORDER BY $order $dir;";
         }
         else
         {
            $qryA   = "SELECT estid,added,updated,status,submitted,id,cfname,clname,phone,estaddid FROM est_addendum WHERE estid=$row[0] AND officeid=$officeid AND securityid=$securityid ORDER BY $order $dir";
         }

         $resA   = mssql_query($qryA);
         $nrowsA = mssql_num_rows($resA);
         */
         $nrowsA =0;
         // temp JID
         //$jid=43;
         $qryB = "SELECT cfname,clname,chome FROM cinfo WHERE custid='".$row[9]."'";
         $resB = mssql_query($qryB);
         $rowB = mssql_fetch_row($resB);
         
         $qryC = "SELECT fname,lname FROM security WHERE securityid='".$row[10]."'";
         $resC = mssql_query($qryC);
         $rowC = mssql_fetch_row($resC);
   	
         echo "                  <tr>\n";
			echo "                     <td class=\"und\" align=\"right\">".$row[0]."</td>\n";
         echo "                     <td class=\"und\" align=\"left\">&nbsp</td>\n";
         echo "                     <td class=\"und\" align=\"left\">&nbsp $rowB[1] $rowB[0]</td>\n";
         echo "                     <td class=\"und\" align=\"left\">&nbsp $rowB[2]</td>\n";
         echo "                     <td class=\"und\" align=\"right\">&nbsp $row[11]</td>\n";
         echo "                     <td class=\"und\" align=\"left\">&nbsp $rowC[1]</td>\n";
         echo "                     <td class=\"und\" align=\"left\">&nbsp $row[1]</td>\n";
         echo "                     <td class=\"und\" align=\"left\">&nbsp $row[2]</td>\n";
         echo "                     <td class=\"und\" align=\"left\">&nbsp $row[4]</td>\n";

         if ($row[3]==0)
         {
            echo "                     <td class=\"wh_und\" align=\"left\">&nbsp;</td>\n";
         }
         elseif ($row[3]==1)
         {
            echo "                     <td class=\"red_und\" align=\"left\">&nbsp;</td>\n";
         }
         elseif ($row[3]==2)
         {
            echo "                     <td class=\"blu_und\" align=\"left\">&nbsp;</td>\n";
         }
         elseif ($row[3]==3)
         {
            echo "                     <td class=\"grn_und\" align=\"left\">&nbsp;</td>\n";
         }
			
         echo "                     <td class=\"und\" align=\"center\">\n";
         echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
         echo "                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
         echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
         echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$row[0]."\">\n";
			
			if ($row[12]==0)
			{
            echo "                           <input class=\"buttondkgry\" type=\"submit\" value=\"View Est\">\n";
		   }
			
         echo "                        </form>\n";
			echo "                     </td>\n";
         echo "                     <td class=\"und\" align=\"center\">\n";
         echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
         echo "                           <input type=\"hidden\" name=\"action\" value=\"job\">\n";
         echo "                           <input type=\"hidden\" name=\"estid\" value=\"".$row[0]."\">\n";
         echo "                           <input type=\"hidden\" name=\"custid\" value=\"".$row[9]."\">\n";
         
         if ($row[3] == 0)
			{
				echo "                           <input type=\"hidden\" name=\"call\" value=\"create_job\">\n";
				echo "                           <input class=\"buttondkgry\" type=\"submit\" value=\"Create Job\">\n";
         }
         else
			{
				echo "                           <input type=\"hidden\" name=\"jobid\" value=\"".$row[12]."\">\n";
				echo "                           <input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
				echo "                           <input class=\"buttondkgry\" type=\"submit\" value=\"View Job\">\n";
         }

         echo "                        </form>\n";
			echo "                     </td>\n";
         echo "                  </tr>\n";

   		if ($nrowsA > 0)
   		{
   		   while($rowA=mssql_fetch_row($resA))
            {
            	echo "                  <tr>\n";
            	echo "                     <td class=\"ltgray_und\" align=\"right\"></td>\n";
            	echo "                     <td class=\"ltgray_und\" align=\"right\"></td>\n";
            	echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
            	echo "                     <td class=\"ltgray_und\" align=\"left\"></td>\n";
            	echo "                     <td class=\"ltgray_und\" align=\"left\">&nbsp $rowA[1]</td>\n";
            	echo "                     <td class=\"ltgray_und\" align=\"left\">&nbsp $rowA[2]</td>\n";
            	echo "                     <td class=\"ltgray_und\" align=\"left\">&nbsp $rowA[4]</td>\n";

            	if ($rowA[3]==0)
            	{
               	echo "                     <td class=\"wh_und\" align=\"left\">&nbsp;</td>\n";
            	}
            	elseif ($rowA[3]==1)
            	{
               	echo "                     <td class=\"grn_und\" align=\"left\">&nbsp;</td>\n";
            	}
            	elseif ($rowA[3]==2)
            	{
               	echo "                     <td class=\"blu_und\" align=\"left\">&nbsp;</td>\n";
            	}
            	echo "                  </tr>\n";
            }
         }
      }
   
      echo "                  </table>\n";
      echo "               </td>\n";
      echo "            </tr>\n";
      echo "         </table>\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "</table>\n";
   }
}

function form_element_ACC($id)
{
   $officeid=$_SESSION['officeid'];
   $qryA = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3,quan_calc FROM acc WHERE officeid='".$officeid."' AND id='".$id."' ORDER BY seqn ASC";
   $resA = mssql_query($qryA);
   $rowA = mssql_fetch_row($resA);
   
   $qryB = "SELECT mid,abrv FROM mtypes WHERE mid='".$rowA[10]."'";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_row($resB);

   if ($_SESSION['call']=='view_addnew')
   {
   	$qryC = "SELECT estdata FROM est_acc_ext WHERE estid='".$_SESSION['estid']."'";
      $resC = mssql_query($qryC);
      $rowC = mssql_fetch_row($resC);
      //echo "EstData: ".$rowC[0]."<br>";
      
      if (strlen($rowC[0]) < 2)
      {
      	$db_id=0;
	      $db_qn=0;
	      $db_rp=0;
	      $db_cd=0;
	   }
	   else
	   {
		$edata=explode(",",$rowC[0]);
		foreach($edata as $n1 => $v1)
		{
			$idata=explode(":",$v1);
			$rdata[]=$idata[0];
			$qdata[]=$idata[2];
			$pdata[]=$idata[3];
			$cdata[]=$idata[4];
			//print_r($idata);
			//echo "<br>";
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
	   }
	   }
   }

   $s0=$rowA[0];
   $s1="aaaa".$s0; // Acc ID
   $s2="bbba".$s0; // Quantity
   $s3="ccca".$s0; // Spaitem (DEPRECATED)
   $s4="ddda".$s0; // Price
   $s5="code".$s0; // Material Code
   $s6="eeea".$s0; // Bid Item


   if ($rowA[5]==0)
   {
      // Disabled
      echo "                     <tr>\n";
      echo "                        <td valign=\"bottom\" align=\"left\">";
      echo                            $rowA[3];
      echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
      echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
      echo "                        </td>\n";
      echo "                        <td valign=\"bottom\" align=\"right\">\n";
		echo "                        </td>\n";
      echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>\n";
		echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rowA[7]\">\n";
		echo "                        </td>\n";
		echo "	                     <td width=\"20px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		echo                            $rowB[1];
		echo "                        </td>\n";
      echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>\n";
      echo "                           <input type=\"hidden\" name=\"$s2\" value=\"1\">\n";
      echo "                        </td>\n";
      echo "                     </tr>\n";
   }
   elseif (
	        $rowA[5]==2||
	        $rowA[5]==39
	       )
   {
      // Quantity - NoCharge (Quantity)
      echo "                     <tr>\n";
      echo "                        <td width=\"350\" valign=\"bottom\" align=\"left\">";
		showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
		echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rowA[7]\">\n";
		echo "                        </td>\n";
		echo "                        <td valign=\"bottom\" align=\"right\"></td>\n";
		echo "	                     <td valign=\"bottom\" align=\"right\">$rowA[7]</td>\n";
		echo "	                     <td valign=\"bottom\" align=\"right\">$rowB[1]</td>\n";
      echo "                        <td valign=\"bottom\" align=\"right\">\n";
      
      if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
		   echo "                           <input class=\"bbox\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
		}
		else
		{
         echo "                           <input class=\"bbox\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
		}
		
		echo "                        </td>\n";
      echo "                     </tr>\n";
   }
   elseif ($rowA[5]==32)
   {
      // Sub Header (Display Only)
      echo "                     <tr>\n";
      echo "                        <td valign=\"bottom\" align=\"left\" colspan=\"5\">\n";
		showdescrip_hdr($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
		echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s2\" value=\"1\">\n";
		echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rowA[7]\">\n";
		echo "                        </td>\n";
      echo "                     </tr>\n";
   }
   elseif  (
	           $rowA[5]==1||
			     $rowA[5]==3||
			     $rowA[5]==4||
			     $rowA[5]==5||
			     $rowA[5]==6||
			     $rowA[5]==7||
			     $rowA[5]==8||
			     $rowA[5]==9||
			     $rowA[5]==10||
			     $rowA[5]==11||
			     $rowA[5]==12||
			     $rowA[5]==13||
			     $rowA[5]==14||
			     $rowA[5]==15||
			     $rowA[5]==16||
			     $rowA[5]==17||
			     $rowA[5]==34||
			     $rowA[5]==35||
			     $rowA[5]==36||
			     $rowA[5]==37||
			     $rowA[5]==38||
			     $rowA[5]==41||
			     $rowA[5]==42||
			     $rowA[5]==43||
			     $rowA[5]==45||
			     $rowA[5]==46||
			     $rowA[5]==47
			  )
   {
      // PFT - SQFT - Fixed - Checkbox - Base+ (All) - Bracket (All)
		// Deck - NoCharge (PFT,SQFT,IA,Gals,Fixed and Base+ Variants)
		// IA (Div by CalcAmt) - IA (Mult by CalcAmt)
      echo "                     <tr>\n";
      echo "                        <td width=\"350\" valign=\"bottom\" align=\"left\">\n";
      showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
		echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rowA[7]\">\n";
		echo "                        </td>\n";
		echo "                        <td valign=\"bottom\" align=\"right\"></td>\n";
		echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>$rowA[7]</td>\n";
		echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>$rowB[1]</td>\n";
      echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>\n";
      
      if ($_SESSION['call']=='view_addnew' && $db_id==$id)
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
	          $rowA[5]==18||
			    $rowA[5]==19||
			    $rowA[5]==21||
			    $rowA[5]==22||
			    $rowA[5]==40
			 )
   {
      // Code (PFT - SQFT - IA - Gallons - No Charge)
      echo "                     <tr>\n";
      echo "                        <td valign=\"bottom\" align=\"left\">\n";
      showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
		echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[5]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rowA[7]\">\n";
		echo "                        </td>\n";
		echo "                        <td width=\"30px\" valign=\"bottom\" align=\"right\">\n";
		
		if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
		   echo "                           <input class=\"bbox\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
		}
		else
		{
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"0\">\n";
		}
		
		echo "                        </td>\n";
		echo "                        <td valign=\"bottom\" align=\"left\">\n";
		echo "                        </td>\n";
		echo "	                     <td width=\"20px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		echo                            $rowB[1];
		echo "                        </td>\n";
      echo "	                     <td width=\"30px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		
		if ($_SESSION['call']=='view_addnew' && $db_id==$id)
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
   elseif ($rowA[5]==20)
   {
   	if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
   	   $qryCODE = "SELECT item,rp FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$db_cd."';";
         $resCODE = mssql_query($qryCODE);
         $rowCODE = mssql_fetch_array($resCODE);
      }
   	
      // Code (Quantity)
      echo "                     <tr>\n";
      echo "                        <td valign=\"bottom\" align=\"left\">\n";
		//echo "                           $rowA[3]\n";
      showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
      
      if (!empty($rowCODE['item']))
      {
         echo " (".$rowCODE['item'].")";
      }
      
		echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[5]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rowA[7]\">\n";
		echo "                        </td>\n";
		echo "                        <td width=\"30px\" valign=\"bottom\" align=\"right\">\n";
		
		if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
		   echo "                           <input class=\"bbox\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
		}
		else
		{
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\">\n";
		}
		
		echo "                        </td>\n";
		echo "                        <td valign=\"bottom\" align=\"right\">\n";
		
		if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
		   echo                            $rowCODE['rp'];
		}
		
		echo "                        </td>\n";
		echo "	                     <td width=\"20px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		
		if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
		   echo                            $rowB[1];
		}
		
		echo "                        </td>\n";
      echo "                        <td valign=\"bottom\" align=\"right\" NOWRAP>\n";
      
		if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
		   echo "                           <input class=\"bbox\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"".$db_qn."\">\n";
		}
		else
		{
         echo "                           <input class=\"bbox\" type=\"text\" name=\"$s2\" size=\"2\" maxlength=\"5\" value=\"0\">\n";
		}
		
		echo                            $rowA[4];
		echo "                        </td>\n";
      echo "                     </tr>\n";
   }
   elseif ($rowA[5]==23)
   {
      // Code (Checkbox)
      echo "                     <tr>\n";
      echo "                        <td valign=\"bottom\" align=\"left\">\n";
		showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
		echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[5]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rowA[7]\">\n";
		echo "                        </td>\n";
		echo "                        <td width=\"30px\" valign=\"bottom\" align=\"right\">\n";
		
		if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
		   echo "                           <input class=\"bbox\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"".$db_cd."\">\n";
		}
		else
		{
			echo "                           <input class=\"bbox\" type=\"text\" name=\"$s5\" size=\"5\" maxlength=\"6\" value=\"0\">\n";
		}
		
		echo "                        </td>\n";
		echo "                        <td valign=\"bottom\" align=\"left\">\n";
		echo "                        </td>\n";
		echo "	                     <td width=\"20px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		echo                            $rowB[1];
		echo "                        </td>\n";
      echo "	                     <td width=\"30px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		
		if ($_SESSION['call']=='view_addnew' && $db_id==$id)
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
	       $rowA[5]==24||
			 $rowA[5]==25||
			 $rowA[5]==27||
			 $rowA[5]==28||
			 $rowA[5]==29
			 )
   {
      // Multiple Choice (PFT - SQFT - IA - Gallons - Checkbox)
      $qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM accpbook WHERE officeid=$officeid AND phsid=$phsid AND accid=$accid ORDER BY accid";
      $resC = mssql_query($qryC);

      echo "                     <tr>\n";
      echo "                        <td valign=\"bottom\" align=\"left\">\n";
      echo "                           <select name=\"$s1\">\n";

      while($rowC = mssql_fetch_row($resC))
      {
         echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
      }

      echo "                           </select>\n";
      echo "                        </td>\n";
      echo "                        <td width=\"30px\" valign=\"bottom\" align=\"right\">\n";
		echo "                        </td>\n";
      echo "	                     <td width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		//echo "                           <input class=\"bbox\" type=\"text\" name=\"$s4\" value=\"$rowA[7]\" size=\"6\" maxlength=\"8\">\n";
		echo "                        </td>\n";
		echo "	                     <td width=\"20px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		echo                            $rowB[1];
		echo "                        </td>\n";
      echo "                        <td width=\"30px\" valign=\"bottom\" align=\"left\" NOWRAP>\n";
      echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
      echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
      echo "                        </td>\n";
      echo "                     </tr>\n";
   }
   elseif ($rowA[5]==26)
   {
      // Multiple Choice (Quantity)
      $qryC = "SELECT id,phsid,accid,item,uom,baseitem,qtype,quantity FROM accpbook WHERE officeid=$officeid AND phsid=$phsid AND accid=$accid ORDER BY accid";
      $resC = mssql_query($qryC);

      echo "                     <tr>\n";
      echo "                        <td valign=\"bottom\" align=\"left\">\n";
      echo "                           <select name=\"$s1\">\n";

      while($rowC = mssql_fetch_row($resC))
      {
         echo "                              <option value=\"$rowC[0]\">$rowC[3]</option>\n";
      }

      echo "                           </select>\n";
      echo "                        </td>\n";
      echo "                        <td width=\"30px\" valign=\"bottom\" align=\"right\">\n";
		echo "                        </td>\n";
      echo "	                     <td width=\"50px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		echo "                        </td>\n";
		echo "	                     <td width=\"20px\" valign=\"bottom\" align=\"right\" NOWRAP>\n";
		echo                            $rowB[1];
		echo "                        </td>\n";
      echo "                        <td width=\"30px\" valign=\"bottom\" align=\"left\" NOWRAP>\n";
      echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
      echo "                           <input class=\"bbox\" type=\"text\" name=\"$s2\" size=\"4\" maxlength=\"5\" value=\"0\"> $rowA[4]\n";
      echo "                        </td>\n";
      echo "                     </tr>\n";
   }
   elseif  ($rowA[5]==33)
   {
      // Bid Items
      echo "                     <tr>\n";
      echo "                        <td width=\"350\" valign=\"bottom\" align=\"left\">\n";
      showdescrip($rowA[3],$rowA[11],$rowA[12],$rowA[13]);
      echo "                           <textarea name=\"$s6\" rows=\"2\" cols=\"60\">";

		if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
      	$qryC = "SELECT estid,bidinfo,bidaccid FROM est_bids WHERE officeid=".$_SESSION['officeid']." AND estid=".$_SESSION['estid']." AND bidaccid=".$rowA[0].";";
         $resC = mssql_query($qryC);
         $rowC = mssql_fetch_array($resC);

			echo $rowC[1];
      }

		echo "</textarea>\n";
		echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
		echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
		//echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rowA[7]\">\n";
		//echo "                           <input type=\"hidden\" name=\"$s4\" value=\"4995.00\">\n";
		echo "                        </td>\n";
		echo "                        <td valign=\"bottom\" align=\"right\"></td>\n";
		//echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>$rowA[7]</td>\n";

      if ($_SESSION['call']=='view_addnew' && $db_id==$id)
      {
      	echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"4\" maxlength=\"20\" value=\"$db_rp\"></td>\n";
		   echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>$rowB[1]</td>\n";
         echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>\n";
		   echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\" CHECKED>\n";
		}
		else
		{
			echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP><input class=\"bbox\" type=\"text\" name=\"$s4\" size=\"4\" maxlength=\"20\" value=\"$rowA[7]\"></td>\n";
		   echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>$rowB[1]</td>\n";
         echo "	                     <td valign=\"bottom\" align=\"right\" NOWRAP>\n";
			echo "                           <input class=\"checkboxgry\" type=\"checkbox\" name=\"$s2\" value=\"1\">\n";
		}

		echo "                        </td>\n";
      echo "                     </tr>\n";
	 }
	 elseif  
	 (	  $rowA[5]==48||
		  $rowA[5]==49||
		  $rowA[5]==50||
		  $rowA[5]==51||
		  $rowA[5]==52)
	 {
		  // PFT - SQFT - Fixed (Hidden)
		  echo "                           <input type=\"hidden\" name=\"$s1\" value=\"$rowA[0]\">\n";
		  echo "                           <input type=\"hidden\" name=\"$s3\" value=\"$rowA[9]\">\n";
		  echo "                           <input type=\"hidden\" name=\"$s4\" value=\"$rowA[7]\">\n";
		  echo "                           <input type=\"hidden\" name=\"$s2\" value=\"$rowA[14]\">\n";
		  //echo "POP: ".$rowA[3]." (".$rowA[14].")<br>";
	 }
	 else
	 {
		  echo "<!---                     <tr>\n";
		  echo "                        <td colspan=\"2\" valign=\"bottom\" align=\"left\">** CODE NOT INCLUDED **</td>\n";
		  echo "                     </tr> --->\n";
	 }
}


function form_element_calc_ACC($id,$quan,$code)
{
   global $rc,$rcexport,$invarray,$viewarray;

   $officeid   =$_SESSION['officeid'];
   $camt		   =$viewarray['camt'];
   $ps1        =$viewarray['ps1'];
   $ps2        =$viewarray['ps2'];
	$ps4        =$viewarray['tzone'];
   $ps5        =$viewarray['ps5'];
   $ps6        =$viewarray['ps6'];
   $ps7        =$viewarray['ps7'];
   $spa1       =$viewarray['spa1'];
   $spa2       =$viewarray['spa2'];
   $spa3       =$viewarray['spa3'];
   //$estdata    =$viewarray['estLdata'];

   $ia=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
   $gl=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

   $qryA = "SELECT * FROM acc WHERE officeid='".$officeid."' AND id='".$id."';";
   $resA = mssql_query($qryA);
   $rowA = mssql_fetch_array($resA);

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
      	//echo $code;
      	//$cc   =$rowB['crate'];
      	//$rc_code   =$rowB['rp'];
      	//$cc_code   =$rowB['crate'];
      	//$name_code ="<u>".$rowA['item']."</u><br>".$rowB['name']." ".$rowB['atrib1']." ".$rowB['atrib2'];
      	//showformelementdescrip($rowA['item'],$rowB['name'],$rowB['atrib1'],$rowB['atrib2']);
      }
   }

   // Tests QTYPE (Question Type)
   if ($rowA['qtype']==1||$rowA['qtype']==38) // Fixed - Nocharge Fixed
   {
      $rc=$rowA['rp'];
      $quan_out=1;
   }
   elseif ($rowA['qtype']==2||$rowA['qtype']==39) // Quantity - Nocharge Quantity
   {
      $rc=$rowA['rp']*$quan;
      $quan_out=$quan;
   }
   elseif ($rowA['qtype']==3||$rowA['qtype']==34) // PFT - No Charge PFT
   {
      $rc=$rowA['rp']*$ps1;
      $quan_out=$ps1;
   }
   elseif ($rowA['qtype']==4||$rowA['qtype']==35) // SQFT - No Charge SQFT
   {
      $rc=$rowA['rp']*$ps2;
      $quan_out=$ps2;
   }
   elseif ($rowA['qtype']==8) // Base+ (Fixed)
   {
   	if ($quan > $rowA['hrange'])
   	{
         $rc=$rowA['rp']*($quan-$rowA['hrange']);
         $quan_out=$quan;
      }
      else
		{
			$rc=$rowA['rp'];
         $quan_out=$quan;
      }
      
   }
   elseif ($rowA['qtype']==5||$rowA['qtype']==41) // Base+ (PFT) - NO Charge
   {
   	if ($ps1 > $rowA['hrange'])
   	{
         $rc=$rowA['rp']+(($ps1-$rowA['hrange'])*$rowA['quan_calc']);
      }
      else
      {
      	$rc=$rowA['rp'];
      }
      $quan_out=$ps1;
   }
   elseif ($rowA['qtype']==6||$rowA['qtype']==42) // Base+ (SQFT) - No Charge
   {
   	if ($ps2 > $rowA['hrange'])
   	{
         $rc=$rowA['rp']+(($ps2-$rowA['hrange'])*$rowA['quan_calc']);
      }
      else
      {
      	$rc=$rowA['rp'];
      }
      $quan_out=$ps2;
   }
   elseif ($rowA['qtype']==7||$rowA['qtype']==43) // Base+ (IA) - No Charge
   {
   	if ($ia > $rowA['hrange'])
   	{
         $rc=$rowA['rp']+(($ia-$rowA['hrange'])*$rowA['quan_calc']);
      }
      else
      {
      	$rc=$rowA['rp'];
      }
      $quan_out=$ia;
   }
   elseif ($rowA['qtype']==13) // Checkbox (PFT)
   {
      $rc=$rowA['rp']*$ps1;
      $quan_out=1;
   }
   elseif ($rowA['qtype']==14) // Checkbox (SQFT)
   {
      $rc=$rowA['rp']*$ps2;
      $quan_out=1;
   }
   elseif ($rowA['qtype']==15) // Checkbox (Quantity)
   {
      $rc=$rowA['rp']*$rowA['quan_calc'];
      $quan_out=$rowA['quan_calc'];
   }
   elseif ($rowA['qtype']==16||$rowA['qtype']==36) // Checkbox (IA) - No Charge
   {
      $rc=$rowA['rp']*$ia;
      $quan_out=1;
   }
   elseif ($rowA['qtype']==17||$rowA['qtype']==37) // Checkbox (Gallons) - No Charge
   {
      $rc=$rowA['rp']*$gl;
      $quan_out=1;
   }
   elseif ($rowA['qtype']==18) // Code (PFT)
	{
		$rc=$rc_code*$ps1;
		$quan_out=$ps1;
   }
   elseif ($rowA['qtype']==19) // Code (SQFT)
   {
      $rc=$rc_code*$ps2;
      $quan_out=$ps2;
   }
   elseif ($rowA['qtype']==20||$rowA['qtype']==40) // Code (Quantity) - No Charge
   {
   	$rc=$rc_code*$quan;
   	$quan_out=$quan;
   }
   elseif ($rowA['qtype']==21) // Code (IA)
   {
   	$rc=$rc_code*$ia;
   	$quan_out=$ia;
   }
   elseif ($rowA['qtype']==22) // Code (Gallons)
   {
   	$rc=$rc_code*$gl;
   	$quan_out=$gl;
   }
   elseif ($rowA['qtype']==23) // Code (Checkbox)
   {
   	$rc=$rc_code*1;
   	$quan_out=1;
   }
   elseif ($rowA['qtype']==32) // sub Header (Display Only)
   {
   	$rc=0;
   	$quan_out=0;
   }
   elseif ($rowA['qtype']==33) // Bid Item
   {
   	$rc=$rowA['rp'];
   	$quan_out=1;
   }
   elseif ($rowA['qtype']==45) // Deck calc
   {
		$deckar=deckcalc($viewarray['ps1'],$viewarray['deck']);
   	$rc=round($deckar[1],0)*$rowA['quan_calc'];
   	$quan_out=round($deckar[1],0);
   }
   elseif ($rowA['qtype']==48) // Base Inclusion
   {
   	$rc=0;
   	$quan_out=$quan;
   }
	elseif ($rowA['qtype']==49) // Base Inclusion (Deck)
   {
		$deckar=deckcalc($viewarray['ps1'],$viewarray['deck']);
   	//$rc=round($deckar[0],0)*$rowA['quan_calc'];
   	$quan_out=round($deckar[0],0);
   }
	elseif ($rowA['qtype']==50) // Base Inclusion (PFT)
   {
   	$rc=0;
   	$quan_out=$ps1;
   }
	elseif ($rowA['qtype']==51) // Base Inclusion (SQFT)
   {
   	$rc=0;
   	$quan_out=$ps2;
   }
	elseif ($rowA['qtype']==52) // Base Inclusion (IA)
   {
   	$rc=0;
   	$quan_out=$ia;
   }
	
	
	
   /*
   elseif ($rowA['qtype']==999) // Hidden (x1)
   {
      if ($rowB[11]==$ps4)
      {
         $rc=$rowA['rp']*1;
      }
   }
   else
   {
      if ($rowB[11]==0)
      {
         $rc=$rowA['rp']*($calc_val-$rowB[9]);
      }
   }
   */
   if ($rowA['supplier']!=0)
   {
   	$qryX = "SELECT com_rate FROM offices WHERE officeid='".$_SESSION['officeid']."';";
      $resX = mssql_query($qryX);
      $rowX = mssql_fetch_array($resX);
      
   	$cc=$rowA['rp']*$rowX['com_rate'];
   }
   else
   {
      if ($rowA['commtype']==1)
      {
   	   $cc=$rowA['rp']*$rowA['crate'];
      }
      elseif ($rowA['commtype']==2)
      {
   	   $cc=$rowA['crate'];
      }
      else
      {
   	   $cc=0;
      }
   }
   
   $rcexport= array(0=>$rc,1=>$cc,2=>$quan_out,3=>0,4=>$uom,5=>$code);
   
   //if ($rowA['qtype']==6)
   //{
   //   show_array_vars($rcexport);
   //}
   return $rcexport;
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

function displayall($bc,$rc,$phsid,$phsitem)
{
   global $estidret;
   
   $qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phsid='".$phsid."';";
   $resA = mssql_query($qryA);
   $rowA = mssql_fetch_array($resA);
   
   $bc=number_format($bc, 2, '.', '');
   $rc=number_format($rc, 2, '.', '');
   $tdc="wh";

   echo "           <tr>\n";
   echo "              <td NOWRAP align=\"center\" class=\"$tdc\"><b>".$rowA['phscode']."</b></td>\n";
   echo "              <td NOWRAP align=\"left\" class=\"$tdc\"><b>".$rowA['extphsname']."</b></td>\n";
   echo "              <td NOWRAP align=\"right\" class=\"$tdc\"><b>Total</b></td>\n";
   echo "              <td NOWRAP align=\"right\" class=\"$tdc\"></td>\n";
   echo "              <td NOWRAP align=\"right\" class=\"$tdc\"><b>".$bc."</b></td>\n";
   echo "           </tr>\n";
}

function displayMall($bc,$rc,$cc,$phsid,$phsitem)
{
   global $estidret;
   
   $qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phsid='".$phsid."';";
   $resA = mssql_query($qryA);
   $rowA = mssql_fetch_array($resA);
   
   $bc=number_format($bc, 2, '.', '');
   $rc=number_format($rc, 2, '.', '');
   $cc=number_format($cc, 2, '.', '');
   $tdc="wh";

   echo "           <tr>\n";
   echo "              <td NOWRAP align=\"center\" class=\"$tdc\"><b>".$rowA['phscode']."</b></td>\n";
   echo "              <td NOWRAP align=\"left\" class=\"$tdc\"><b>".$rowA['extphsname']."</b></td>\n";
   echo "              <td NOWRAP align=\"right\" class=\"$tdc\"><b><b>Total</b></td>\n";
   echo "              <td NOWRAP align=\"right\" class=\"$tdc\"></td>\n";
	echo "              <td NOWRAP align=\"right\" class=\"$tdc\"><b>".$bc."</b></td>\n";
   echo "           </tr>\n";
}

function showitem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan)
{
   $qry2 = "SELECT phsname as extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
   $res2 = mssql_query($qry2);
   $row2 = mssql_fetch_array($res2);
   
   $bc=number_format($bc, 2, '.', '');
   $rc=number_format($rc, 2, '.', '');
   $tdc="lg";

   if (isset($_POST['showdetail'])||$_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print')
   {
      echo "           <tr>\n";
      echo "              <td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
      echo "              <td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">".$row2['extphsname']."</td>\n";
      echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
		
		if (strlen($i) > 1)
	   {
	      echo "$i<br>\n";
	   }
	   if (strlen($a1) > 1)
	   {
		   echo "- <font class=\"7pt\">$a1</font>\n";
	   }
	   if (strlen($a2) > 1)
	   {
		   echo "<br>- <font class=\"7pt\">$a2</font>\n";
	   }
	   if (strlen($a3) > 1)
	   {
		   echo "<br>- <font class=\"7pt\">$a3</font>\n";
	   }
	   
		echo "              </td>\n";
      echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"30\">";
      if ($quan!=0)
      {
      	echo "<input type=\"hidden\" name=\"ddd$id\" value=\"$quan\">";
         echo $quan;
         //echo "<input type=\"text\" class=\"bbox\" name=\"ddd$id\" value=\"$quan\" size=\"3\">";
      }
      echo "</td>\n";
      echo "              <td NOWRAP align=\"right\" valign=\"bottom\" class=\"lg\" width=\"70\">$bc</td>\n";
      echo "           </tr>\n";
   }
}

function showMitem($bc,$rc,$id,$i,$a1,$a2,$a3,$quan,$cr)
{
   $qry2 = "SELECT extphsname,phscode FROM phasebase WHERE phsid='".$id."';";
   $res2 = mssql_query($qry2);
   $row2 = mssql_fetch_array($res2);
   
   $quan=round($quan);
   
   $bc =number_format($bc, 2, '.', '');
   $rc =number_format($rc, 2, '.', '');
   //$cc =number_format($cc, 2, '.', '');

   if (isset($_POST['showdetail'])||$_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print')
   {
      echo "           <tr>\n";
      echo "              <td NOWRAP valign=\"bottom\" align=\"center\" class=\"lg\">".$row2['phscode']."</td>\n";
      echo "              <td NOWRAP valign=\"bottom\" align=\"left\" class=\"lg\">";
      
      if ($cr==0)
      {
		   echo $row2['extphsname'];
		}
		else
		{
			echo "<font color=\"blue\">".$row2['extphsname']."</font>";
		}

		echo "</td>\n";
      echo "              <td NOWRAP valign=\"top\" align=\"left\" class=\"lg\">\n";
		//echo $item;
		
		if (strlen($i) > 1)
	   {
	   	if ($cr==0)
	   	{
	         echo "$i<br>\n";
	      }
	      else
	      {
	      	echo "<font color=\"blue\">$i (Credit)</font><br>\n";
	      }
	   }
	   if (strlen($a1) > 1)
	   {
		   echo "- <font class=\"7pt\">$a1</font>\n";
	   }
	   if (strlen($a2) > 1)
	   {
		   echo "<br>- <font class=\"7pt\">$a2</font>\n";
	   }
	   if (strlen($a3) > 1)
	   {
		   echo "<br>- <font class=\"7pt\">$a3</font>\n";
	   }
		
		echo "              </td>\n";
      echo "              <td NOWRAP valign=\"bottom\" align=\"right\" class=\"lg\" width=\"30\">";
      if ($quan!=0)
      {
         echo $quan;
         echo "<input type=\"hidden\" name=\"ddd$id\" value=\"$quan\">";
      }
      echo "</td>\n";
      if ($_SESSION['jlev'] > 8)
      {
         echo "              <td valign=\"bottom\" NOWRAP align=\"right\" class=\"lg\" width=\"70\">";
         if ($bc!=0)
         {
         	if ($cr==0)
         	{
               echo $bc;
            }
            else
            {
            	echo "<font color=\"blue\">-$bc</font>";
            }
         }
         echo "</td>\n";
      }
      echo "           </tr>\n";
   }
}

function phscalc($phsid,$phsnum,$phsitem,$costitems)
{
   global $phsbcrc,$brexport,$invarray,$viewarray,$tchrg,$taxrate;
   
   //echo "<pre>";
	//print_r($costitems);
	//echo "</pre>";

   //$officeid=$_SESSION['officeid'];
   
   if ($_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print'||$_SESSION['call']=='remove_acc')
   {
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
   }
   else
   {
      $discount   =$_POST['discount'];
      $ps1        =$_POST['ps1'];
      $ps2        =$_POST['ps2'];
      $ps4        =$_POST['tzone'];
      $ps5        =$_POST['ps5'];
      $ps6        =$_POST['ps6'];
      $ps7        =$_POST['ps7'];
      $spa1       =$_POST['spa1'];
      $spa2       =$_POST['spa2'];
      $spa3       =$_POST['spa3'];
   }

	/*
   if ($spa1==0) // Sets calc methods for Spa inclusion (O=None,1=Internal,2=External,3=Spa Only)
   {
      $ps1=$ps1;
      $ps2=$ps2;
   }
   elseif ($spa1==1)
   {
      $ps1=$ps1+$spa2;
      $ps2=$ps2+$spa3;
   }
   elseif ($spa1==2)
   {
      $ps1=$ps1+$spa2;
      $ps2=$ps2+$spa3;
   }
   elseif ($spa1==3)
   {
      $ps1=$spa2;
      $ps2=$spa3;
   }
   */
   
   // Calculation Settings
   $qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d FROM offices WHERE officeid='".$_SESSION['officeid']."';";
   $respre0 =mssql_query($qrypre0);
   $rowpre0 =mssql_fetch_array($respre0);
   
   //Pulls Total List of Base Labor Items within a phase based upon DISTINCT accid's
   $qry0    ="SELECT DISTINCT(accid),qtype FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND baseitem=1;";
   $res0    =mssql_query($qry0);
   $nrow0   =mssql_num_rows($res0);
   
   if ($nrow0 > 0)
   {
   	$bc=0;
   	$rc=0;
   	while($row0=mssql_fetch_row($res0))
   	{
   		//echo $qry0."<br>";
   		if ($row0[1]==1) // Fixed
			{
				$qry1 ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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

            //echo $item." Fixed";
			}
   		elseif ($row0[1]==2) // Quantity
			{
				$qry1 ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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

            //echo $item." xQuantity";
         }
   		elseif ($row0[1]==3) // per PFT
			{
				$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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

            //echo $item." per PFT";
         }
         elseif ($row0[1]==4) // per SQFT
			{
				$qry1 ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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

            //echo $item." per SQFT";
         }
         elseif ($row0[1]==5) // Base+ PFT (Fixed Base + amt per pft)
			{
				$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
            $res1  =mssql_query($qry1);
            $row1  =mssql_fetch_array($res1);

            $bcsub =($row1['bprice']*1)+(($ps1-$row1['lrange'])*$row1['quantity']);
            $rcsub =0;
            $id    =$row1['phsid'];
            $item  =$row1['item'];
            $a1    =$row1['atrib1'];
            $a2    =$row1['atrib2'];
            $a3    =$row1['atrib3'];
            $quan  =$ps1;

            //echo $item." Base+ PFT";
         }
         elseif ($row0[1]==6) // Base+ SQFT (Fixed Base + amt per sqft)
			{
				$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
            $res1  =mssql_query($qry1);
            $row1  =mssql_fetch_array($res1);

            $bcsub =($row1['bprice']*1)+(($ps2-$row1['lrange'])*$row1['quantity']);
            $rcsub =0;
            $id    =$row1['phsid'];
            $item  =$row1['item'];
            $a1    =$row1['atrib1'];
            $a2    =$row1['atrib2'];
            $a3    =$row1['atrib3'];
            $quan  =$ps2;

            //echo $item." Base+ SQFT";
         }
         elseif ($row0[1]==7) // Base+ IA
			{
				$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
				$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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

            //echo $item." Base+ IA";
			}
         elseif ($row0[1]==9) // Bracket PFT (ranges)
			{
				$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
            $res1  =mssql_query($qry1);

            while ($row1=mssql_fetch_array($res1))
				{
					//echo $row1['qtype'];
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
               	//$qry2  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
                  //$res2  =mssql_query($qry2);
               	//$row2  =mssql_fetch_array($res2);

               	$bcsub =$row1['bprice']+(($ps1-$row1['hrange'])*$row1['quantity']);
                  //$rcsub =$row1['rprice']+(($ps1-$row1['hrange'])*$row1['quantity']);
                  $rcsub =0;
                  $id    =$row1['phsid'];
                  $item  =$row1['item'];
                  $a1    =$row1['atrib1'];
                  $a2    =$row1['atrib2'];
                  $a3    =$row1['atrib3'];
                  $quan  =$ps1;
               }
            }
            //echo $item." Bracket PFT";
         }
         elseif ($row0[1]==10) // Bracket SQFT (ranges)
			{
				$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
               }
            }
            //echo $item." Bracket SQFT";
         }
         elseif ($row0[1]==11) // Bracket IA
			{
				$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
            $respre1 = mssql_query($qrypre1);
            $rowpre1 = mssql_fetch_row($respre1);

				if ($iarea < $rowpre1[0])
            {
               	$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
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
                  //echo $item." Bracket IA (Lower)";
            }
            elseif ($iarea > $rowpre1[3])
            {
               	$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND hrange='".$rowpre1[3]."' AND baseitem=1;";
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
                  //echo $item." Bracket IA (Upper)";
            }
				else
            {
            	$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
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
                     //echo $item." Bracket IA (Within)";
                  }
               }
            }
				/*
				$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
            $res1  =mssql_query($qry1);
            while ($row1  =mssql_fetch_array($res1))
            {
               if ($iarea >= $row1['lrange'] && $iarea <= $row1['hrange'])
               {
                  $bcsub =$row1['bprice']*$iarea;
                  $rcsub =0;
                  $id    =$row1['id'];
                  $item  =$row1['item'];
                  $quan  =$iarea;
               }
               elseif ($iarea < $rowpre1[0])
               {
               	$qry2  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
                  $res2  =mssql_query($qry2);
                  $row2  =mssql_fetch_array($res2);

                  $bcsub =$row2['bprice']*$iarea;
                  $rcsub =0;
                  $id    =$row2['id'];
                  $item  =$row2['item'];
                  $quan  =$iarea;
               }
               elseif ($iarea > $rowpre1[3])
               {
               	$qry2  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND hrange='".$rowpre1[3]."' AND baseitem=1;";
                  $res2  =mssql_query($qry2);
                  $row2  =mssql_fetch_array($res2);

                  $bcsub =($row2['bprice']*$rowpre1[3])+(($iarea-$rowpre1[3])*$row2['quantity']);
                  $rcsub =0;
                  $id    =$row2['id'];
                  $item  =$row2['item'];
                  $quan  =$iarea;
               }
            }
            */
            //echo $item." Bracket IA";
			}
         elseif ($row0[1]==30) // Fixed per PFT
			{
				$qrypre1 = "SELECT MIN(lrange),MAX(lrange),MIN(hrange),MAX(hrange) FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
            $respre1 = mssql_query($qrypre1);
            $rowpre1 = mssql_fetch_row($respre1);
				
				if ($ps1 < $rowpre1[0])
				{
					$qry1 ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[0]."' AND baseitem=1;";
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
					$qry1 ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$rowpre1[1]."' AND baseitem=1;";
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
					$qry1 ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND lrange='".$ps1."' AND baseitem=1;";
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
            //echo $item." xQuantity";
         }
         /*
         elseif ($row0[1]==17) // Fixed per SQFT
			{
			}
			elseif ($row0[1]==18) // Base+ Linear FT *** Needs Revision, not working correctly ***
			{
				$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
            $res1  =mssql_query($qry1);
            $row1  =mssql_fetch_array($res1);

            $bcsub =$row1['bprice']+(($row1['lrange'])*$row1['quantity']);
            $rcsub =0;
            $id    =$row1['id'];
            $item  =$row1['item'];
            $quan  =$ps1;

            //echo $item." Base+ Linear FT";
			}
			elseif ($row0[1]==19) // Bracket Linear FT *** Needs Revision, not working correctly ***
			{
				$qry1  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
            $res1  =mssql_query($qry1);

            while ($row1=mssql_fetch_array($res1))
				{
					if ($ps1 >= $row1['lrange'] && $ps1 <= $row1['hrange'])
					{
                  $bcsub =$row1['bprice'];
                  $rcsub =0;
                  $id    =$row1['id'];
                  $item  =$row1['item'];
                  $quan  =$ps1;
               }
               elseif ($ps1 > $row1['hrange'])
               {
               	//$qry2  ="SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND accid='".$row0[0]."' AND baseitem=1;";
                  //$res2  =mssql_query($qry2);
               	//$row2  =mssql_fetch_array($res2);

               	$bcsub =$row1['bprice']+(($ps1-$row1['hrange'])*$row1['quantity']);
                  $rcsub =0;
                  $id    =$row1['id'];
                  $item  =$row1['item'];
                  $quan  =$ps1;
               }
            }
            //echo $item." Bracket Linear FT";
				
			}
			*/
         else
         {
         }
         //echo $item."<br>";
         showitem($bcsub,$rcsub,$id,$item,$a1,$a2,$a3,$quan);
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
   
   // *** ADD Accessory Cost Calcs Here ***
	if ($costitems[0] > 0)
	{
		//echo "<pre>";
	   //print_r($costitems);
	   //echo "</pre>";
	   
	   foreach ($costitems as $pre_n=>$pre_v)
	   {
	      $quan=$pre_v[1];
	      
         $qryB = "SELECT * FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND id='".$pre_v[0]."' AND baseitem!=1";
         $resB = mssql_query($qryB);
         $rowB = mssql_fetch_array($resB);
				
         if ($rowB['phsid']==$phsid)
         {
            if ($rowB['qtype']==1) // Fixed
            {
            	$bc=$bc+$rowB['bprice'];
            	$rc=$rc+$rowB['rprice'];
               showitem($rowB['bprice'],$rowB['rprice'],$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==2) // Quantity
            {
               $subbp=$rowB['bprice']*$quan;
               $subrp=$rowB['rprice']*$quan;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==3) // PFT
            {
               $subbp=$rowB['bprice']*$ps1;
               $subrp=$rowB['rprice']*$ps1;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==4) // SQFT
            {
               $subbp=$rowB['bprice']*$ps2;
               $subrp=$rowB['rprice']*$ps2;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==5) // Base+ (PFT)
            {
            	if ($ps1 <= $rowB['hrange'])
            	{
                  $subbp=$rowB['bprice'];
                  $subrp=$rowB['rprice'];
               }
               elseif ($ps1 > $rowB['hrange'])
               {
               	$subbp=($rowB['bprice'])+(($ps1-$rowB['hrange'])*$rowB['quantity']);
                  $subrp=($rowB['rprice'])+(($ps1-$rowB['hrange'])*$rowB['quantity']);
               }
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps1);
            }
            elseif ($rowB['qtype']==6) // Base+ (SQFT)
            {
               if ($ps2 <= $rowB['hrange'])
            	{
                  $subbp=$rowB['bprice'];
                  $subrp=$rowB['rprice'];
               }
               elseif ($ps2 > $rowB['hrange'])
               {
               	$subbp=$rowB['bprice']+(($ps2-$rowB['hrange'])*$rowB['quantity']);
                  $subrp=$rowB['rprice']+(($ps2-$rowB['hrange'])*$rowB['quantity']);
               	//$subbp=($rowB['bprice']*$rowB['hrange'])+(($ps2-$rowB['hrange'])*$rowB['quantity']);
                  //$subrp=($rowB['rprice']*$rowB['hrange'])+(($ps2-$rowB['hrange'])*$rowB['quantity']);
               }
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps2);
            }
            elseif ($rowB['qtype']==7) // Base+ (IA)
            {
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               if ($iarea <= $rowB['hrange'])
            	{
                  $subbp=$rowB['bprice'];
                  $subrp=$rowB['rprice'];
               }
               elseif ($iarea > $rowB['hrange'])
               {
               	$subbp=($rowB['bprice'])+(($iarea-$rowB['hrange'])*$rowB['quantity']);
                  $subrp=($rowB['rprice'])+(($iarea-$rowB['hrange'])*$rowB['quantity']);
               }
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$iarea);
            }
            elseif ($rowB['qtype']==38) // Base+ (Fixed)
            {
               if ($ps2 <= $rowB['hrange'])
            	{
                  $subbp=$rowB['bprice'];
                  $subrp=$rowB['rprice'];
               }
               elseif ($ps2 > $rowB['hrange'])
               {
               	$subbp=$rowB['bprice']+(($ps2-$rowB['hrange'])*$rowB['quantity']);
                  $subrp=$rowB['rprice']+(($ps2-$rowB['hrange'])*$rowB['quantity']);
               	//$subbp=($rowB['bprice']*$rowB['hrange'])+(($ps2-$rowB['hrange'])*$rowB['quantity']);
                  //$subrp=($rowB['rprice']*$rowB['hrange'])+(($ps2-$rowB['hrange'])*$rowB['quantity']);
               }
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps2);
            }
            elseif ($rowB['qtype']==9) // Bracket (PFT)
            {
               $subbp=$rowB['bprice']*$ps2;
               $subrp=$rowB['rprice']*$ps2;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps1);
            }
            elseif ($rowB['qtype']==10) // Bracket (SQFT)
            {
               $subbp=$rowB['bprice']*$ps2;
               $subrp=$rowB['rprice']*$ps2;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps2);
            }
            elseif ($rowB['qtype']==11) // Bracket (IA)
            {
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               $subbp=$rowB['bprice']*$ps2;
               $subrp=$rowB['rprice']*$ps2;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$iarea);
            }
            elseif ($rowB['qtype']==12) // Bracket (Gallons)
            {
               $subbp=$rowB['bprice']*$ps2;
               $subrp=$rowB['rprice']*$ps2;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$gals);
            }
            elseif ($rowB['qtype']==13) // Checkbox (PFT)
            {
               $subbp=$rowB['bprice']*$ps1;
               $subrp=$rowB['rprice']*$ps1;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps1);
            }
            elseif ($rowB['qtype']==14) // Checkbox (SQFT)
            {
               $subbp=$rowB['bprice']*$ps2;
               $subrp=$rowB['rprice']*$ps2;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps2);
            }
            elseif ($rowB['qtype']==15) // Checkbox (Quantity)
            {
               $subbp=$rowB['bprice']*$rowB['quantity'];
               $subrp=$rowB['rprice']*$rowB['quantity'];
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$rowB['quantity']);
            }
            elseif ($rowB['qtype']==16) // Checkbox (IA)
            {
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               $subbp=$rowB['bprice']*$iarea;
               $subrp=$rowB['rprice']*$iarea;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$iarea);
            }
            elseif ($rowB['qtype']==17) // Checkbox (Gallons)
            {
            	$gals=calc_gallons($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               $subbp=$rowB['bprice']*$gals;
               $subrp=$rowB['rprice']*$gals;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$gals);
            }
            elseif ($rowB['qtype']==18) // Code (PFT)
            {
            	$scode=getcodeitem($code);
               $subbp=$rowB['bprice']*$ps1;
               $subrp=$rowB['rprice']*$ps1;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps1);
            }
            elseif ($rowB['qtype']==19) // Code (SQFT)
            {
            	$scode=getcodeitem($code);
               $subbp=$rowB['bprice']*$ps2;
               $subrp=$rowB['rprice']*$ps2;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps2);
            }
            elseif ($rowB['qtype']==20) // Code (Quantity)
            {
            	$scode=getcodeitem($code);
               $subbp=$rowB['bprice']*$quan;
               $subrp=$rowB['rprice']*$quan;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==21) // Code (IA)
            {
            	$scode=getcodeitem($code);
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               $subbp=$rowB['bprice']*$iarea;
               $subrp=$rowB['rprice']*$iarea;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$iarea);
            }
            elseif ($rowB['qtype']==22) // Code (Gallons)
            {
            	$scode=getcodeitem($code);
            	$gals=calc_gallons($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               $subbp=$rowB['bprice']*$gals;
               $subrp=$rowB['rprice']*$gals;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$gals);
            }
            elseif ($rowB['qtype']==23) // Code (Checkbox)
            {
            	$scode=getcodeitem($code);
            	$sitem="<u>".$rowB['name']."</u><br>".$scode[1];
               $subbp=$scode[2];
               $subrp=$scode[3];
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$sitem,$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==33) // Bid Item
            {
            	//echo $pre_v[0];
            	$qryC = "SELECT rid FROM rclinks_l WHERE officeid='".$_SESSION['officeid']."' AND cid='".$pre_v[0]."';";
               $resC = mssql_query($qryC);
               $rowC = mssql_fetch_array($resC);
               
               $qryD = "SELECT bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."' AND bidaccid='".$rowC['rid']."';";
               $resD = mssql_query($qryD);
               $rowD = mssql_fetch_array($resD);
               
               $qryE = "SELECT estdata FROM est_acc_ext WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_SESSION['estid']."';";
               $resE = mssql_query($qryE);
               $rowE = mssql_fetch_array($resE);
               
               $Xarray=explode(",",$rowE['estdata']);

	            foreach ($Xarray as $n=>$v)
               {
               	$subXarray=explode(":",$v);
               	
               	if ($subXarray[0]==$rowC['rid'])
               	{
               	   $Xbp=$subXarray[3];
               	}
               }

               $subbp=$Xbp;
               $subrp=0;
               $bc=$bc+$subbp;
               //showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
               showitem($subbp,$subrp,$rowB['phsid'],"Bid Item<br><font class=\"7pt\">- ".$rowD['bidinfo']."</font>",'','','',$quan);
            }
				elseif ($rowB['qtype']==45) // Deck 
            {
					 //$deckar=deckcalc($viewarray['ps1'],$viewarray['deck']);
					 //$rc=round($deckar[1],0)*$rowB['quantity'];
					 //$rc=round($deck)*$rowB['quantity'];
					 //$quan=round($deckar[1],0);
					 $quan=$deck;
            	//$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
            	//$quan=$iarea/$rowB['quan_calc'];
					 $subbp=$rowB['bprice']*$quan;
					 $subrp=$rowB['rprice']*$quan;
					 $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==46) // IA (Div by CalcAmt)
            {
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
            	$quan=$iarea/$rowB['quan_calc'];
               $subbp=$rowB['bprice']*$quan;
               $subrp=$rowB['rprice']*$quan;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==47) // IA (Mult by CalcAmt)
            {
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
            	$quan=$iarea*$rowB['quan_calc'];
               $subbp=$rowB['bprice']*$quan;
               $subrp=$rowB['rprice']*$quan;
               $bc=$bc+$subbp;
               showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
		   }
	   }
   }
   
   displayall($bc,$rc,$phsid,$phsitem);
   $phsbcrc=array(0=>$bc,$rc,$cc);
   return $phsbcrc;
}

function mat_credititem($id,$phsid,$quan)
{
	global $phsbcrc,$brexport,$invarray,$viewarray,$bc;

   $officeid=$_SESSION['officeid'];

   if ($_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print'||$_SESSION['call']=='remove_acc')
   {
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
   }
   else
   {
      $discount   =$_POST['discount'];
      $ps1        =$_POST['ps1'];
      $ps2        =$_POST['ps2'];
      $ps4        =$_POST['tzone'];
      $ps5        =$_POST['ps5'];
      $ps6        =$_POST['ps6'];
      $ps7        =$_POST['ps7'];
      $spa1       =$_POST['spa1'];
      $spa2       =$_POST['spa2'];
      $spa3       =$_POST['spa3'];
   }
   $iarea=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
   
   $qry = "SELECT * FROM inventory WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND invid='".$id."';";
   $res = mssql_query($qry);
   $row = mssql_fetch_array($res);

	$subrp      =0;
   $subphsid 	=$row['phsid'];
	$subitem  	=$row['item'];
	$subatrib1	=$row['atrib1'];
	$subatrib2	=$row['atrib2'];
	$subatrib3	=$row['atrib3'];
	$subquan		=$quan;
	$cr			=1;
	
	if ($row['qtype']==1) // Fixed
   {
      $subbp=$row['bprice'];
      $bc=$bc-$subbp;
   }
   elseif ($row['qtype']==2) // Quantity
   {
      $subbp=$row['bprice']*$quan;
      $bc=$bc-$subbp;
   }
   elseif ($row['qtype']==46) // IA (Div by CalcAmt)
   {
   	$subquan=$iarea/$row['quan_calc'];
      $subbp=$row['bprice']*$subquan;
      $bc=$bc-$subbp;
   }
   showMitem($subbp,$subrp,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$subquan,$cr);
}

function phsMcalc($phsid,$phsnum,$phsitem,$costitems)
{
   global $phsbcrc,$brexport,$invarray,$viewarray,$bc;
   
   $officeid=$_SESSION['officeid'];
   
   if ($_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print'||$_SESSION['call']=='remove_acc')
   {
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
   }
   else
   {
      $discount   =$_POST['discount'];
      $ps1        =$_POST['ps1'];
      $ps2        =$_POST['ps2'];
      $ps4        =$_POST['tzone'];
      $ps5        =$_POST['ps5'];
      $ps6        =$_POST['ps6'];
      $ps7        =$_POST['ps7'];
      $spa1       =$_POST['spa1'];
      $spa2       =$_POST['spa2'];
      $spa3       =$_POST['spa3'];
   }

	/*
   if ($spa1==0) // Sets calc methods for Spa inclusion (O=None,1=Internal,2=External,3=Spa Only)
   {
      $ps1=$ps1;
      $ps2=$ps2;
   }
   elseif ($spa1==1)
   {
      $ps1=$ps1+$spa2;
      $ps2=$ps2+$spa3;
   }
   elseif ($spa1==2)
   {
      $ps1=$ps1+$spa2;
      $ps2=$ps2+$spa3;
   }
   elseif ($spa1==3)
   {
      $ps1=$spa2;
      $ps2=$spa3;
   }
   */

   // Calculation Settings
   $qrypre0 ="SELECT def_per,def_sqft,def_s,def_m,def_d FROM offices WHERE officeid='".$_SESSION['officeid']."';";
   $respre0 =mssql_query($qrypre0);
   $rowpre0 =mssql_fetch_array($respre0);

   $qry   ="SELECT bprice,rprice,invid,item,commtype,crate,atrib1,atrib2,atrib3,phsid,rinvid,quan_calc FROM inventory WHERE officeid=$officeid AND phsid=$phsid AND baseitem=1";
   $res   =mssql_query($qry);
   $nrows =mssql_num_rows($res);
   
   $bc=0;
   $rc=0;
   $cc=0;

   while($row=mssql_fetch_row($res))
   {
   	//echo $qry."<br>";
   	
      $bcsub=$row[0];
      $rcsub=$row[1];
      $quan =$row[11];
      
      if ($row[4]!=".00")
      {
         $ccsub=$row[4]*$rcsub;
      }
      elseif ($row[5]>0)
      {
         $ccsub=$row[5]*1;
      }
      else
      {
         $ccsub=0;
      }

      if ($nrows!=0)
      {
         showMitem($bcsub,$rcsub,$row[9],$row[3],$row[6],$row[7],$row[8],$quan,0);
      }
      
      $bc=$bc+$bcsub;
   	$rc=$rc+$rcsub;
   	$cc=$cc+$ccsub;
   }
   
   if ($nrows==0)
   {
      $bc=0;
      $rc=0;
      $cc=0;
   }
   
   // Option Calcs
   if ($costitems[0] > 0)
	{
		//echo "<pre>";
	   //print_r($costitems);
	   //echo "</pre>";

	   foreach ($costitems as $pre_n=>$pre_v)
	   {
	      $quan=$pre_v[1];

         $qryB = "SELECT * FROM inventory WHERE officeid='".$_SESSION['officeid']."' AND phsid='".$phsid."' AND invid='".$pre_v[0]."' AND baseitem!=1";
         $resB = mssql_query($qryB);
         $rowB = mssql_fetch_array($resB);
         
         $subphsid 	=$rowB['phsid'];
			$subitem  	=$rowB['item'];
			$subatrib1	=$rowB['atrib1'];
			$subatrib2	=$rowB['atrib2'];
			$subatrib3	=$rowB['atrib3'];
			$subquan		=$quan;
         
         //echo "<pre>";
	      //print_r($pre_v);
	      //echo "</pre>";
	      
	      if ($rowB['phsid']==$phsid)
         {
         	//show_array_vars($rowB);
         	$subrp =0; // Deprecated, remove on code cleanup
         	$rc    =0; // Deprecated, remove on code cleanup
         	
         	if ($rowB['rinvid']!=0)  // Credit Code Loop
            {
               mat_credititem($rowB['rinvid'],$phsid,$quan);
            }

            if ($rowB['qtype']==1) // Fixed
            {
            	$subbp=$rowB['bprice'];
            	$bc=$bc+$subbp;
               //showMitem($rowB['bprice'],$rowB['rprice'],$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==2) // Quantity
            {
               $subbp=$rowB['bprice']*$quan;
               $bc=$bc+$subbp;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==3) // PFT
            {
               $subbp=$rowB['bprice']*$ps1;
               $bc=$bc+$subbp;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==4) // SQFT
            {
               $subbp=$rowB['bprice']*$ps2;
               $bc=$bc+$subbp;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==5) // Base+ (PFT)
            {
            	if ($ps1 <= $rowB['hrange'])
            	{
                  $subbp=$rowB['bprice'];
               }
               elseif ($ps1 > $rowB['hrange'])
               {
               	$subbp=($rowB['bprice'])+(($ps1-$rowB['hrange'])*$rowB['quantity']);
               }
               $bc=$bc+$subbp;
               $subquan=$ps1;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps1);
            }
            elseif ($rowB['qtype']==6) // Base+ (SQFT)
            {
               if ($ps2 <= $rowB['hrange'])
            	{
                  $subbp=$rowB['bprice'];
               }
               elseif ($ps2 > $rowB['hrange'])
               {
               	$subbp=$rowB['bprice']+(($ps2-$rowB['hrange'])*$rowB['quantity']);
               }
               $bc=$bc+$subbp;
               $subquan=$ps2;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps2);
            }
            elseif ($rowB['qtype']==7) // Base+ (IA)
            {
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               if ($iarea <= $rowB['hrange'])
            	{
                  $subbp=$rowB['bprice'];
               }
               elseif ($iarea > $rowB['hrange'])
               {
               	$subbp=($rowB['bprice'])+(($iarea-$rowB['hrange'])*$rowB['quantity']);
               }
               $bc=$bc+$subbp;
               $subquan=$iarea;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$iarea);
            }
            //elseif ($rowB['qtype']==8) // Base+ (Fixed)
            //{
            //   $subbp=$rowB['bprice']*$ps2;
            //   $subrp=$rowB['rprice']*$ps2;
            //   $bc=$bc+$subbp;
            //   showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$quan);
            //}
            elseif ($rowB['qtype']==9) // Bracket (PFT)
            {
               $subbp=$rowB['bprice']*$ps2;
               $bc=$bc+$subbp;
               $subquan=$ps1;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps1);
            }
            elseif ($rowB['qtype']==10) // Bracket (SQFT)
            {
               $subbp=$rowB['bprice']*$ps2;
               $bc=$bc+$subbp;
               $subquan=$ps2;
               //showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps2);
            }
            elseif ($rowB['qtype']==11) // Bracket (IA)
            {
               $subbp=$rowB['bprice']*$ps2;
               $bc=$bc+$subbp;
               $subquan=$iarea;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$iarea);
            }
            elseif ($rowB['qtype']==12) // Bracket (Gallons)
            {
               $subbp=$rowB['bprice']*$ps2;
               $bc=$bc+$subbp;
               $subquan=$gals;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$gals);
            }
            elseif ($rowB['qtype']==13) // Checkbox (PFT)
            {
               $subbp=$rowB['bprice']*$ps1;
               $bc=$bc+$subbp;
               $subquan=$ps1;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps1);
            }
            elseif ($rowB['qtype']==14) // Checkbox (SQFT)
            {
               $subbp=$rowB['bprice']*$ps2;
               $bc=$bc+$subbp;
               $subquan=$ps2;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps2);
            }
            elseif ($rowB['qtype']==15) // Checkbox (Quantity)
            {
               $subbp=$rowB['bprice']*$rowB['quantity'];
               $bc=$bc+$subbp;
               $subquan=$rowB['quantity'];
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$rowB['quantity']);
            }
            elseif ($rowB['qtype']==16) // Checkbox (IA)
            {
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               $subbp=$rowB['bprice']*$iarea;
               $bc=$bc+$subbp;
               $subquan=$iarea;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$iarea);
            }
            elseif ($rowB['qtype']==17) // Checkbox (Gallons)
            {
            	$gals=calc_gallons($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               $subbp=$rowB['bprice']*$gals;
               $bc=$bc+$subbp;
               $subquan=$gals;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$gals);
            }
            elseif ($rowB['qtype']==18) // Code (PFT)
            {
            	$scode=getcodeitem($code);
               $subbp=$rowB['bprice']*$ps1;
               $bc=$bc+$subbp;
               $subquan=$ps1;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps1);
            }
            elseif ($rowB['qtype']==19) // Code (SQFT)
            {
            	$scode=getcodeitem($code);
               $subbp=$rowB['bprice']*$ps2;
               $bc=$bc+$subbp;
               $subquan=$ps2;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$ps2);
            }
            elseif ($rowB['qtype']==20) // Code (Quantity)
            {
            	$scode=getcodeitem($pre_v[3]);
            	$subitem=$subitem." ".$scode[1];
               $subbp=$scode[2]*$quan;
               $bc=$bc+$subbp;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==21) // Code (IA)
            {
            	$scode=getcodeitem($pre_v[3]);
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               $subbp=$rowB['bprice']*$iarea;
               $bc=$bc+$subbp;
               $subquan=$iarea;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$iarea);
            }
            elseif ($rowB['qtype']==22) // Code (Gallons)
            {
            	$scode=getcodeitem($pre_v[3]);
            	$gals=calc_gallons($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
               $subbp=$rowB['bprice']*$gals;
               $bc=$bc+$subbp;
               $subquan=$gals;
               //showMitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$gals);
            }
            elseif ($rowB['qtype']==23) // Code (Checkbox)
            {
            	$scode=getcodeitem($pre_v[3]);
            	$sitem="<u>".$rowB['name']."</u><br>".$scode[1];
               $subbp=$scode[2];
               $bc=$bc+$subbp;
               //showMitem($subbp,$subrp,$rowB['phsid'],$sitem,$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==33) // Bid Item
            {
            	$qryC = "SELECT raccid FROM accpbook WHERE officeid='".$_SESSION['officeid']."' AND id='".$pre_v[0]."';";
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
               //showitem($subbp,$subrp,$rowB['phsid'],"Bid Item<br><font class=\"7pt\">- ".$rowD['bidinfo']."</font>",'','','',$quan);
            }
            elseif ($rowB['qtype']==46) // IA (Div by CalcAmt)
            {
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
            	$subquan=$iarea/$rowB['quan_calc'];
               $subbp=$rowB['bprice']*$subquan;
               $subrp=$rowB['rprice']*$subquan;
               $bc=$bc+$subbp;
               //showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            elseif ($rowB['qtype']==47) // IA (Mult by CalcAmt)
            {
            	$iarea=calc_internal_area($ps1,$ps2,$rowpre0[2],$rowpre0[3],$rowpre0[4]);
            	$subquan=$iarea*$rowB['quan_calc'];
               $subbp=$rowB['bprice']*$subquan;
               $subrp=$rowB['rprice']*$subquan;
               $bc=$bc+$subbp;
               //showitem($subbp,$subrp,$rowB['phsid'],$rowB['item'],$rowB['atrib1'],$rowB['atrib2'],$rowB['atrib3'],$quan);
            }
            showMitem($subbp,$subrp,$subphsid,$subitem,$subatrib1,$subatrib2,$subatrib3,$subquan,0);
         }
      }
   }
   displayMall($bc,$rc,$cc,$phsid,$phsitem);
   $phsbcrc=array(0=>$bc,$rc,$cc);
   return $phsbcrc;
}

function calcbyacc($estdata)
{
   global $bctotal,$rctotal,$cctotal,$tacc_price,$phsbcrc,$viewarray,$tbullets;

      $camt		   =$viewarray['camt'];
      $ps1        =$viewarray['ps1'];
      $ps2        =$viewarray['ps2'];
      $ps4        =$viewarray['tzone'];
      $ps5        =$viewarray['ps5'];
      $ps6        =$viewarray['ps6'];
      $ps7        =$viewarray['ps7'];
      $spa1       =$viewarray['spa1'];
      $spa2       =$viewarray['spa2'];
      $spa3       =$viewarray['spa3'];
      
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
            $itemfromdb=form_element_calc_ACC($v1array[0],$v1array[2],$v1array[4]);

	   	   $comm=$itemfromdb[1]*$itemfromdb[2];
	   	   
	   	   $qry0 = "SELECT item,atrib1,atrib2,atrib3,qtype,catid,bullet FROM acc WHERE officeid='".$_SESSION['officeid']."' AND id='".$v1array[0]."';";
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
               	$data_price=$v1array[3];
               	
                  $qry2 = "SELECT estid,bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid=".$_SESSION['estid']." AND bidaccid='".$v1array[0]."';";
                  $res2 = mssql_query($qry2);
                  $row2 = mssql_fetch_array($res2);
                  
                  echo "\n".$row2['bidinfo']."\n";
               }
               elseif ($row0['qtype']==20)
               {
                  $qry2 = "SELECT item,code,atrib1,rp FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$itemfromdb[5]."';";
                  $res2 = mssql_query($qry2);
                  $row2 = mssql_fetch_array($res2);

                  echo "\n".$row2['item']."\n";
               }
               
	   	      $comm=$itemfromdb[1]*$itemfromdb[2];
               
               $fdata_price=number_format($data_price, 2, '.', '');
	   	      $fcomm=number_format($comm, 2, '.', '');

			      echo "              </td>\n";
               echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$itemfromdb[2]."</td>\n";
               echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$itemfromdb[4]."</td>\n";
               echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fdata_price."</td>\n";
            
               if ($comm!=0)
               {
                  echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fcomm."</td>\n";
               }
               else
               {
            	   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\"></td>\n";
               }
					
					echo "              <td NOWRAP valign=\"bottom\" align=\"center\">\n";
               
					 if ($row0['qtype'] < 48)
					 {
						  echo "                 <input class=\"checkboxgry\" type=\"checkbox\" name=\"$x1\" value=\"".$v1array[0]."\">\n";
					 }
					 
					 echo "              </td>\n";
					 echo "           </tr>\n";
               
               if ($row0['bullet']==1)
               {
						$tbullets++;
               }
               
               $tdata_price=$tdata_price+$data_price;
               $tcomm=$tcomm+$comm;
               //echo "<pre>";
					//print_r($viewarray);
					//echo "</pre>";
					
            }
	      }
  	   }

  	   $rctotal=$rctotal+$tdata_price;
  	   $cctotal=$cctotal+$tcomm;
  	   $tacc_price=$tdata_price;
   }
   else
   {
      echo "           <tr>\n";
      echo "              <td NOWRAP colspan=\"6\" class=\"wh\" align=\"center\"><b>No Accessories Selected</b></td>\n";
      echo "           </tr>\n";
   }
}

function calcbyacc_ptr($estdata)
{
   global $bctotal,$rctotal,$cctotal,$tacc_price,$phsbcrc,$viewarray,$tbullets;

      $camt		   =$viewarray['camt'];
      $ps1        =$viewarray['ps1'];
      $ps2        =$viewarray['ps2'];
      $ps4        =$viewarray['tzone'];
      $ps5        =$viewarray['ps5'];
      $ps6        =$viewarray['ps6'];
      $ps7        =$viewarray['ps7'];
      $spa1       =$viewarray['spa1'];
      $spa2       =$viewarray['spa2'];
      $spa3       =$viewarray['spa3'];

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
            $itemfromdb=form_element_calc_ACC($v1array[0],$v1array[2],$v1array[4]);
	   	   
	   	   if ($itemfromdb[0]!=$v1array[3])
            {
				   $data_price=$itemfromdb[0];
				}
				else
				{
	   	      $data_price=$v1array[3];
	   	   }
	   	   
	   	   $comm=$itemfromdb[1]*$itemfromdb[2];
	   	   $fdata_price=number_format($data_price, 2, '.', '');
	   	   $fcomm=number_format($comm, 2, '.', '');

	   	   $qry0 = "SELECT item,atrib1,atrib2,atrib3,qtype,catid,bullet FROM acc WHERE officeid='".$_SESSION['officeid']."' AND id='".$v1array[0]."';";
            $res0 = mssql_query($qry0);
            $row0 = mssql_fetch_array($res0);

            if ($row0['qtype']!=32)
            {
               $x1="xxx".$v1array[0];
               
               $qry1 = "SELECT catid,name FROM AC_cats WHERE officeid='".$_SESSION['officeid']."' AND catid='".$row0['catid']."';";
               $res1 = mssql_query($qry1);
               $row1 = mssql_fetch_array($res1);

	   	      echo "           <tr>\n";
	   	      echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\">".$row1['name']."</td>\n";
               echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\">\n";

               showdescrip($row0['item'],$row0['atrib1'],$row0['atrib2'],$row0['atrib3']);
               
               if ($row0['qtype']==33)
               {
                  $qry2 = "SELECT estid,bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid=".$_SESSION['estid']." AND bidaccid='".$v1array[0]."';";
                  $res2 = mssql_query($qry2);
                  $row2 = mssql_fetch_array($res2);

                  echo "\n".$row2['bidinfo']."\n";
               }
               elseif ($row0['qtype']==20)
               {
                  $qry2 = "SELECT item,code,atrib1,rp FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$itemfromdb[5]."';";
                  $res2 = mssql_query($qry2);
                  $row2 = mssql_fetch_array($res2);

                  echo "\n".$row2['item']."\n";
               }

			      echo "              </td>\n";
               echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$itemfromdb[2]."</td>\n";
               echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$itemfromdb[4]."</td>\n";
               echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fdata_price."</td>\n";

               if ($comm!=0)
               {
               echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fcomm."</td>\n";
               }
               else
               {
            	echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\"></td>\n";
               }
            
               echo "           </tr>\n";
               
               if ($row0['bullet']==1)
               {
						$tbullets++;
               }
               
               $tdata_price=$tdata_price+$data_price;
               $tcomm=$tcomm+$comm;
            }
	      }
  	   }

  	   $rctotal=$rctotal+$tdata_price;
  	   $cctotal=$cctotal+$tcomm;
  	   $tacc_price=$tdata_price;
   }
   else
   {
      echo "           <tr>\n";
      echo "              <td NOWRAP colspan=\"6\" class=\"wh\" align=\"center\"><b>No Accessories Selected</b></td>\n";
      echo "           </tr>\n";
   }
}

function calcbyphsL($estAdata)
{
   global $bctotal,$rctotal,$phsbcrc,$phsid,$phsnum,$phsitem,$viewarray;

   $officeid   =$_SESSION['officeid'];
   
   if ($_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print'||$_SESSION['call']=='remove_acc')
   {
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
   }
   else
   {
      $discount   =$_POST['discount'];
      $ps1        =$_POST['ps1'];
      $ps2        =$_POST['ps2'];
      $ps4        =$_POST['tzone'];
      $ps5        =$_POST['ps5'];
      $ps6        =$_POST['ps6'];
      $ps7        =$_POST['ps7'];
      $spa1       =$_POST['spa1'];
      $spa2       =$_POST['spa2'];
      $spa3       =$_POST['spa3'];
   }

   if (!isset($showdetail))
   {
      $showdetail=0;
   }
   
   //echo $estAdata;
   
   $costitems=setitemlist($estAdata,"L");
   
   //show_array_vars($costitems);

   $qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum ASC;";
   $resA = mssql_query($qryA);

   $qryC = "SELECT id,quan,price FROM rbpricep WHERE officeid='".$officeid."' AND quan='".$ps1."';";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_row($resC);

   $pbaseprice=$rowC[2]-$discount;
   $pbaseprice=number_format($pbaseprice, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"50\"><b>Code</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"left\" width=\"100\"><b>Phase</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Labor Items</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Quant</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Cost</b></td>\n";
   echo "           </tr>\n";
   
   while($rowA = mssql_fetch_row($resA))
   {
   	//echo $rowA[1]."<br>";
   	if ($rowA[1]=="505L")
      {
      	$royalty		=$_POST['tcontract']*.03;
      	$froyalty	=number_format($royalty, 2, '.', '');
      	//$bctotal=$bctotal+$royalty;
         //$rctotal=$rctotal;
         echo "           <tr>\n";
         echo "              <td NOWRAP align=\"center\" class=\"wh\"><b>".$rowA[1]."</b></td>\n";
         echo "              <td NOWRAP align=\"left\" class=\"wh\"><b>".$rowA[2]."</b></td>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"><b>Total</b></td>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"><b>".$froyalty."</b></td>\n";
         echo "           </tr>\n";
         $bctotal=$bctotal+$royalty;
         $rctotal=$rctotal;
      }
      elseif ($rowA[1]!=0)
      {
         phscalc($rowA[0],$rowA[1],$rowA[2],$costitems);
         $bctotal=$bctotal+$phsbcrc[0];
         $rctotal=$rctotal+$phsbcrc[1];
         $phsbcrc[0]=0;
         $phsbcrc[1]=0;
         //secho "Phase: ".$rowA[2]."| P Total".$phsbcrc[0]." | G Total: ".$bctotal."<br>";
      }
      else
      {
         echo "           <tr>\n";
         echo "              <td NOWRAP class=\"wh\" align=\"left\"></td>\n";
         echo "              <td NOWRAP align=\"left\" class=\"wh\"><b>$rowA[4] Total</b></td>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
         echo "           </tr>\n";
      }
   }
}

function calcbyphsM($estAdata)
{
   global $bmtotal,$rmtotal,$cmtotal,$phsbcrc,$phsid,$phsnum,$phsitem,$viewarray;
   
   $officeid   =$_SESSION['officeid'];

   if ($_SESSION['call']=='view_retail'||$_SESSION['call']=='view_cost'||$_SESSION['call']=='view_cost_print'||$_SESSION['call']=='remove_acc')
   {
      $discount   =$viewarray['discount'];
      $ps1        =$viewarray['ps1'];
      $ps2        =$viewarray['ps2'];
      $ps4        =$viewarray['tzone'];
      $spa1       =$viewarray['spa1'];
      $spa2       =$viewarray['spa2'];
      $spa3       =$viewarray['spa3'];
   }
   else
   {
      $discount   =$_POST['discount'];
      $ps1        =$_POST['ps1'];
      $ps2        =$_POST['ps2'];
      $ps4        =$_POST['tzone'];
      $spa1       =$_POST['spa1'];
      $spa2       =$_POST['spa2'];
      $spa3       =$_POST['spa3'];
   }

   if (!isset($showdetail))
   {
      $showdetail=0;
   }

   $costitems=setitemlist($estAdata,"M");
   //print_r($costitems);

   $qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phstype='M' ORDER BY seqnum ASC";
   $resA = mssql_query($qryA);

   $qryC = "SELECT id,quan,price FROM rbpricep WHERE officeid=$officeid AND quan='$ps1'";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_row($resC);

   echo "           <tr>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"center\" width=\"50\"><b>Code</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"left\" width=\"100\"><b>Phase</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Material Items</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Quant</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"center\"><b>Cost</b></td>\n";
   echo "           </tr>\n";

   while($rowA = mssql_fetch_row($resA))
   {
      if ($rowA[1]!=0)
      {
         phsMcalc($rowA[0],$rowA[1],$rowA[2],$costitems);
         $bmtotal=$bmtotal+$phsbcrc[0];
         $rmtotal=$rmtotal+$phsbcrc[1];
         $cmtotal=$cmtotal+$phsbcrc[2];
      }
      else
      {
         echo "           <tr>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"><b>$rowA[4] Total</b></td>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
         echo "              <td NOWRAP align=\"right\" class=\"wh\"></td>\n";
         echo "           </tr>\n";
      }
   }
}

function cform()
{
   $officeid	=$_SESSION['officeid'];
   $dates		=dateformat();
   //$uid			=md5(time().$_SERVER['REMOTE_ADDR'].microtime());
   $uid			=session_id().".".$_SESSION['securityid'];
   
   if ($_SESSION['jlev'] >= 6)
   {
      $qryA = "SELECT officeid,name,stax FROM offices ORDER BY name ASC;";
   }
   else
   {
   	$qryA = "SELECT officeid,name,stax FROM offices WHERE officeid='$officeid';";
   }
   $resA = mssql_query($qryA);
   $nrowsA = mssql_num_rows($resA);
   
   if ($_SESSION['jlev'] >= 6)
   {
      $qryB = "SELECT securityid,fname,lname FROM security ORDER BY lname ASC;";
      $resB = mssql_query($qryB);
      $nrowsB = mssql_num_rows($resB);
   }
   
   $qryC = "SELECT stax FROM offices WHERE officeid='".$officeid."';";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_row($resC);
   
   if ($rowC[0]!=0)
   {
   	$qryD = "SELECT * FROM taxrate WHERE officeid='".$officeid."' ORDER BY city ASC;";
      $resD = mssql_query($qryD);
      
      $qryE = "SELECT * FROM taxrate WHERE officeid='".$officeid."' ORDER BY city ASC;";
      $resE = mssql_query($qryE);
   }
   
   //print_r($_SESSION);
   //echo $uid."<br>";
   
   echo "<table class=\"outer\" width=\"700px\" align=\"center\">\n";
   echo "   <tr>\n";
   echo "      <td>\n";
   echo "		   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" onsubmit=\"return validateForm(this)\">\n";
   echo "		   <input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "			<input type=\"hidden\" name=\"call\" value=\"matrix0\">\n";
   echo "			<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
   echo "			<input type=\"hidden\" name=\"recdate\" value=\"".$dates[1]."\">\n";
   echo "			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
   echo "			<table border=\"0\" width=\"100%\">\n";
   echo "			   <tr>\n";
   echo "				   <td bgcolor=\"#d3d3d3\">\n";
   echo "				      <table border=\"0\" width=\"100%\">\n";
   //echo "				         <tr>\n";
   //echo "				            <td colspan=\"2\" valign=\"bottom\" align=\"right\"><button type=\"submit\">Pool Details</button></td>\n";
   //echo "				         </tr>\n";
   echo "                     <tr>\n";
	echo "                        <td align=\"left\"><b>Estimate:</b> (Customer Entry)</td>\n";
	echo "				            <td valign=\"bottom\" align=\"right\"><button type=\"submit\">Build Pool</button></td>\n";
	echo "                    </tr>\n";
   echo "						   <tr>\n";
   echo "							   <td colspan=\"2\" valign=\"top\"><hr width=\"100%\" noshade size=\"2\"></td>\n";
   echo "							</tr>\n";
   echo "                     <tr>\n";
   echo "                        <td colspan=\"2\" valign=\"bottom\">\n";
   echo "                           <table border=\"0\" width=\"100%\">\n";
   echo "										<tr>\n";
   echo "										   <td align=\"right\" valign=\"bottom\"><b>Date:</b>\n";
	echo "										   <td align=\"left\" valign=\"bottom\">$dates[0]</td>\n";
   echo "                                 <td align=\"right\" valign=\"bottom\"><b>Office: </b></td>\n";
   echo "											<td align=\"left\" valign=\"bottom\">\n";

   if ($_SESSION['jlev'] >= 6)
   {
      echo "												<select name=\"site\">\n";
      while ($rowA = mssql_fetch_row($resA))
      {
      	if ($_SESSION['officeid']==$rowA[0])
      	{
            echo "													<option value=\"$rowA[0]\" SELECTED>$rowA[1]</option>\n";
         }
         else
         {
         	echo "													<option value=\"$rowA[0]\">$rowA[1]</option>\n";
         }
      }
      echo "												</select>\n";
   }
   else
   {
   	$rowA = mssql_fetch_row($resA);
   	echo "													$rowA[1]<input type=\"hidden\" name=\"officeid\" value=\"$rowA[0]\">\n";
   }

   echo "											</td>\n";
   echo "											<td align=\"right\" valign=\"bottom\"><b>Salesperson:</b></td>\n";
   echo "											<td align=\"left\" valign=\"bottom\">\n";
   if ($_SESSION['jlev'] >= 6)
   {
   	echo "												<select name=\"estorig\">\n";
      while ($rowB = mssql_fetch_row($resB))
      {
      	if ($_SESSION['securityid']==$rowB[0])
      	{
            echo "													<option value=\"$rowB[0]\" SELECTED>$rowB[1] $rowB[2]</option>\n";
         }
         else
         {
         	echo "													<option value=\"$rowB[0]\">$rowB[1] $rowB[2]</option>\n";
         }
      }
      echo "												</select>\n";
   	
   }
   else
   {
	   echo "											   ".$_SESSION['fname']." ".$_SESSION['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$_SESSION['securityid']."\">\n";
	}
	
	echo "                                 </td>\n";
   echo "										</tr>\n";
   echo "									</table>\n";
   echo "								</td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "							   <td colspan=\"2\">\n";
   echo "								   <table border=\"0\">\n";
   echo "									   <tr>\n";
   echo "										   <td valign=\"top\" bgcolor=\"#d3d3d3\">\n";
   echo "											   <table border=\"0\">\n";
   echo "													<tr>\n";
   echo "													   <td colspan=\"2\" valign=\"top\"><hr width=\"100%\" noshade size=\"2\"></td>\n";
   echo "													</tr>\n";
   echo "													<tr>\n";
   echo "													   <td>\n";
   echo "														   <table border=\"0\">\n";
   echo "												            <tr>\n";
   echo "													            <td colspan=\"2\" valign=\"bottom\" NOWRAP><b>Customer:</b></td>\n";
   echo "													         </tr>\n";
   echo "															   <tr>\n";
   echo "																   <td NOWRAP><b><i>Name</i></b></td>\n";
   echo "																	<td align=\"right\" NOWRAP>First <input class=\"bboxl\" type=\"text\" size=\"30\" name=\"cfname\" value=\"Test\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP></td>\n";
   echo "																	<td align=\"right\" NOWRAP>Last <input class=\"bboxl\" type=\"text\" size=\"30\" name=\"clname\" value=\"User\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																   <td NOWRAP><b><i>Phone</i></b></td>\n";
   echo "																	<td align=\"right\" NOWRAP>Home <input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"chome\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP></td>\n";
   echo "																	<td align=\"right\" NOWRAP>Work <input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP></td>\n";
   echo "																	<td align=\"right\" NOWRAP>Cell <input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP></td>\n";
   echo "																	<td align=\"right\" NOWRAP>Fax <input class=\"bboxl\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP><b><i>Best Ph:</i></b></td>\n";
   echo "																	<td align=\"right\" NOWRAP>\n";
   echo "																	   <select name=\"cconph\">\n";
   echo "																		   <option value=\"hm\">Home</option>\n";
   echo "																			<option value=\"wk\">Work</option>\n";
   echo "																			<option value=\"ce\">Cell</option>\n";
   echo "																		</select>\n";
   echo "																	</td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																   <td align=\"right\" NOWRAP><b><i>Email:</i></b></td>\n";
   echo "																	<td align=\"right\" NOWRAP><input class=\"bboxl\" type=\"text\" name=\"cemail\" size=\"30\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP><b><i>Contact Time:</i></b></td>\n";
	echo "																	<td align=\"right\" NOWRAP><input class=\"bboxl\" type=\"text\" size=\"30\" name=\"ccontime\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "															</table>\n";
   echo "														</td>\n";
   echo "													   <td valign=\"top\">\n";
   echo "														   <table border=\"0\">\n";
   echo "													         <tr>\n";
   echo "													            <td colspan=\"2\" valign=\"top\" NOWRAP><b>Current Address:</b></td>\n";
   echo "													         </tr>\n";
   echo "															   <tr>\n";
   echo "																   <td align=\"right\" NOWRAP>Street:</td>\n";
   echo "																	<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"caddr1\" value=\"12345 Nostreet\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP>City:</td>\n";
   echo "																	<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"ccity\" value=\"\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP>Zip:</td>\n";
   echo "																	<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"99999\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																   <td align=\"right\" NOWRAP>Cnty/Twnshp:</td>\n";
   echo "																	<td NOWRAP>\n";
   
   if ($rowC[0]==0)
	{
      echo "																	<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"ccounty\" value=\"\">\n";
   }
   elseif ($rowC[0]==1)
	{
		echo "                                                   <select name=\"ccounty\">\n";
		while ($rowD = mssql_fetch_row($resD))
      {
         echo "                                                      <option value=\"$rowD[0]\">$rowD[2]</option>\n";
      }
      echo "                                                   </select>\n";
      //echo "																	<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"18\" name=\"ccounty\" value=\"\">  Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"cmap\" value=\"\"></td>\n";
   }
   else
	{
      echo "																	<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"ccounty\" value=\"\">\n";
   }
   
   echo "                                              Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"cmap\" value=\"\">\n";
   echo "                                                   </td>\n";
   echo "																</tr>\n";
   echo "												            <tr>\n";
   echo "													            <td colspan=\"2\" valign=\"top\" NOWRAP><b>Pool Site Address:</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
   echo "													         </tr>\n";
   echo "															   <tr>\n";
   echo "																   <td align=\"right\" NOWRAP>Street:</td>\n";
   echo "																	<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"50\" name=\"saddr1\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP>City:</td>\n";
   echo "																	<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"20\" name=\"scity\" value=\"\"> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																	<td align=\"right\" NOWRAP>Zip:</td>\n";
   echo "																	<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"\">-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "																<tr>\n";
   echo "																   <td align=\"right\" NOWRAP>Cnty/Twnshp:</td>\n";
   echo "																	<td NOWRAP>\n";

   if ($rowC[0]==0)
	{
      echo "																	<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\" value=\"\">\n";
   }
   elseif ($rowC[0]==1)
	{
		echo "                                                   <select name=\"scounty\">\n";
		while ($rowE = mssql_fetch_row($resE))
      {
         echo "                                                      <option value=\"$rowE[0]\">$rowE[2]</option>\n";
      }
      echo "                                                   </select>\n";
      //echo "																	<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"18\" name=\"ccounty\" value=\"\">  Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"cmap\" value=\"\"></td>\n";
   }
   else
	{
      echo "																	<input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\" value=\"\">\n";
   }

   echo "                                              Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"smap\" value=\"\">\n";
   echo "                                                   </td>\n";
   //echo "																	<td NOWRAP><input class=\"bboxl\" type=\"text\" size=\"18\" name=\"scounty\" value=\"\">  Map: <input class=\"bboxl\" type=\"text\" size=\"10\" name=\"smap\" value=\"\"></td>\n";
   echo "																</tr>\n";
   echo "															</table>\n";
   echo "														</td>\n";
   echo "													</tr>\n";
   echo "													<tr>\n";
   echo "													   <td colspan=\"2\" valign=\"top\"><hr width=\"100%\" noshade size=\"2\"></td>\n";
   echo "													</tr>\n";
   echo "													<tr>\n";
   echo "													   <td colspan=\"2\">\n";
   echo "														   <table width=\"100%\">\n";
   echo "															   <tr>\n";
   echo "																   <td valign=\"top\"><b>Comments/Directions:</b></td>\n";
	echo "         														<td><textarea name=\"comments\" cols=\"50\" rows=\"3\"></textarea></td>\n";
   echo "																</tr>\n";
   echo "															</table>\n";
   echo "														</td>\n";
   echo "													</tr>\n";
   echo "												</table>\n";
   echo "											</td>\n";
   echo "										</tr>\n";
   echo "									</table>\n";
   echo "								</td>\n";
   echo "							</tr>\n";
   echo "						</table>\n";
   echo "					</td>\n";
   echo "				</tr>\n";
   echo "			</table>\n";
   echo "			</form>\n";
   echo "		</td>\n";
   echo "	</tr>\n";
   echo "</table>\n";
   echo "</body>\n";
   echo "</html>\n";
}

function matrix0($uid1)
{
   $secid    	=$_SESSION['securityid'];
   $officeid 	=$_SESSION['officeid'];
   $fname    	=$_SESSION['fname'];
   $lname    	=$_SESSION['lname'];
   $uid2			=session_id().".".$_SESSION['securityid'];
   
   if($uid1!=$uid2||$_POST['clname']=="")
   {
		echo "<b>Error Occured! Click <a href=\"".$_SERVER['PHP_SELF']."?action=est&call=new\">HERE</a> to enter a Customer.</b>\n";
		exit;
   }
	else
	{
		$uid=$uid2;
	}

   $qrypre2 = "SELECT officeid,name,def_per,def_sqft,def_s,def_m,def_d FROM offices WHERE officeid=$officeid";
   $respre2 = mssql_query($qrypre2);
   $rowpre2 = mssql_fetch_row($respre2);
   
   // Builds a list of exisiting categories in the retail accessory table by office
   $qrypre3  = "SELECT DISTINCT a.catid,a.seqn ";
   $qrypre3 .= "FROM AC_cats AS a INNER JOIN acc AS b ";
   $qrypre3 .= "ON a.catid=b.catid ";
   $qrypre3 .= "AND b.officeid='".$officeid."' ";
   $qrypre3 .= "ORDER BY a.seqn ASC;";
   $respre3 = mssql_query($qrypre3);
   while ($rowpre3 = mssql_fetch_row($respre3))
   {
      $catarray[]=$rowpre3[0];
   }

   $qryA = "SELECT quan FROM rbpricep WHERE officeid=$officeid ORDER BY quan ASC";
   $resA = mssql_query($qryA);

   $qryB = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum";
   $resB = mssql_query($qryB);

   $qryC = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype='M' AND costing=1 ORDER BY seqnum";
   $resC = mssql_query($qryC);

   $qryD = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC";
   $resD = mssql_query($qryD);

   $qryE = "SELECT zid,name FROM zoneinfo ORDER BY zid ASC";
   $resE = mssql_query($qryE);
   
   $qryF  = "SELECT id FROM acc WHERE officeid='".$_SESSION['officeid']."' AND spaitem!=1";
   $resF  = mssql_query($qryF);
   $nrowF = mssql_num_rows($resF);
   
   $qryH  = "SELECT id FROM acc WHERE officeid='".$_SESSION['officeid']."' AND spaitem!=1 AND phsid=0 ORDER BY seqn ASC";
   $resH  = mssql_query($qryH);
   $nrowH = mssql_num_rows($resH);
   
   $qryI  = "SELECT id,phsid FROM acc WHERE officeid='".$_SESSION['officeid']."' AND spaitem!=1 AND phsid!=0 ORDER BY seqn ASC";
   $resI  = mssql_query($qryI);
   $nrowI = mssql_num_rows($resI);
   
   $qryG  = "SELECT id FROM acc WHERE officeid='".$_SESSION['officeid']."' AND spaitem=1";
   $resG  = mssql_query($qryG);
   $nrowG = mssql_num_rows($resG);
   
   $qryK  = "SELECT id FROM acc WHERE officeid='".$_SESSION['officeid']."' AND spaitem=1 AND phsid=0 ORDER BY seqn ASC";
   $resK  = mssql_query($qryK);
   $nrowK = mssql_num_rows($resK);

   $qryL  = "SELECT id,phsid FROM acc WHERE officeid='".$_SESSION['officeid']."' AND spaitem=1 AND phsid!=0 ORDER BY seqn ASC";
   $resL  = mssql_query($qryL);
   $nrowL = mssql_num_rows($resL);
	
	//$qryM = "SELECT id,aid,officeid,item,accpbook,qtype,seqn,rp,bp,spaitem,mtype,atrib1,atrib2,atrib3 FROM acc WHERE officeid='".$officeid."' AND id='".$id."' ORDER BY seqn ASC";
   //$resM = mssql_query($qryM);
   //$rowM = mssql_fetch_row($resM);
   
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"".$officeid."\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"".$secid."\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"matrix1\">\n";
   echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
   echo "<input type=\"hidden\" name=\"cfname\" value=\"".$_POST['cfname']."\">\n";
   echo "<input type=\"hidden\" name=\"clname\" value=\"".$_POST['clname']."\">\n";
   echo "<input type=\"hidden\" name=\"chome\" value=\"".$_POST['chome']."\">\n";
   echo "<input type=\"hidden\" name=\"cwork\" value=\"".$_POST['cwork']."\">\n";
   echo "<input type=\"hidden\" name=\"ccell\" value=\"".$_POST['ccell']."\">\n";
   echo "<input type=\"hidden\" name=\"cfax\" value=\"".$_POST['cfax']."\">\n";
   echo "<input type=\"hidden\" name=\"cconph\" value=\"".$_POST['cconph']."\">\n";
   echo "<input type=\"hidden\" name=\"ccontime\" value=\"".$_POST['ccontime']."\">\n";
   echo "<input type=\"hidden\" name=\"cemail\" value=\"".$_POST['cemail']."\">\n";
   echo "<input type=\"hidden\" name=\"contime\" value=\"".$_POST['ccontime']."\">\n";
   echo "<input type=\"hidden\" name=\"caddr1\" value=\"".$_POST['caddr1']."\">\n";
   echo "<input type=\"hidden\" name=\"ccity\" value=\"".$_POST['ccity']."\">\n";
   echo "<input type=\"hidden\" name=\"cstate\" value=\"".$_POST['cstate']."\">\n";
   echo "<input type=\"hidden\" name=\"czip1\" value=\"".$_POST['czip1']."\">\n";
   echo "<input type=\"hidden\" name=\"czip2\" value=\"".$_POST['czip2']."\">\n";
   echo "<input type=\"hidden\" name=\"ccounty\" value=\"".$_POST['ccounty']."\">\n";
   echo "<input type=\"hidden\" name=\"cmap\" value=\"".$_POST['cmap']."\">\n";
   echo "<input type=\"hidden\" name=\"ssame\" value=\"".$_POST['ssame']."\">\n";
   echo "<input type=\"hidden\" name=\"saddr1\" value=\"".$_POST['saddr1']."\">\n";
   echo "<input type=\"hidden\" name=\"scity\" value=\"".$_POST['scity']."\">\n";
   echo "<input type=\"hidden\" name=\"sstate\" value=\"".$_POST['sstate']."\">\n";
   echo "<input type=\"hidden\" name=\"szip1\" value=\"".$_POST['szip1']."\">\n";
   echo "<input type=\"hidden\" name=\"szip2\" value=\"".$_POST['szip2']."\">\n";
   echo "<input type=\"hidden\" name=\"scounty\" value=\"".$_POST['scounty']."\">\n";
   echo "<input type=\"hidden\" name=\"smap\" value=\"".$_POST['smap']."\">\n";
   echo "<input type=\"hidden\" name=\"comments\" value=\"".$_POST['comments']."\">\n";
   echo "<input type=\"hidden\" name=\"recdate\" value=\"".$_POST['recdate']."\">\n";
   echo "<input type=\"hidden\" name=\"showdetail\" value=\"1\">\n";
	
	// Base Item Loop
	
	
	
   echo "<input type=\"hidden\" name=\"#Top\">\n";
   echo "<table align=\"center\" class=\"outer\" width=700px>\n";
   echo "   <tr>\n";
   echo "      <td colspan=\"2\" class=\"gray\" align=\"left\">\n";
   echo "         <table border=\"0\" width=\"100%\">\n";
   echo "            <tr>\n";
   echo "               <td valign=\"bottom\" align=\"left\"><b>Retail Estimate for ".$rowpre2[1]." Office</b></td>\n";
	echo "               <td valign=\"bottom\" align=\"left\">Salesperson: <b>".$fname." ".$lname."</b></td>\n";
   echo "	            <td NOWRAP valign=\"bottom\" align=\"right\">\n";
   echo "                  <b>Contract Amount:</b>\n";
   echo "                  <input class=\"bbox\" type=\"text\" name=\"contractamt\" size=\"5\" maxlength=\"20\" value=\"0.00\">\n";
	echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Estimate\">\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td colspan=\"2\" align=\"left\">\n";
	echo "                  <b>Customer</b>\n";
	echo "                  <input class=\"bboxl\" type=\"text\" name=\"cfname\" size=\"20\" value=\"".$_POST['cfname']."\">\n";
	echo "                  <input class=\"bboxl\" type=\"text\" name=\"clname\" size=\"32\" value=\"".$_POST['clname']."\">\n";
	echo "               </td>\n";
	echo "	            <td align=\"right\">\n";
   echo "	               <table border=\"0\" width=\"100%\">\n";
   echo "	                  <tr>\n";
   echo "	                     <td><b>Spa</b>:</td>\n";
   echo "	                     <td>\n";
   echo "	                        <select name=\"spa1\">\n";

   while($rowD = mssql_fetch_row($resD))
   {
      echo "	                           <option value=\"$rowD[0]\">$rowD[1]</option>\n";
   }

   echo "	                        </select>\n";
   echo "	                     </td>\n";
   echo "	                     <td>\n";
   echo "                           P\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
   echo "                           S\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
   echo "                        </td>\n";
   echo "	                  </tr>\n";
   echo "	               </table>\n";
   echo "	            </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td colspan=\"2\" class=\"gray\">\n";
   echo "         <table border=\"0\" width=\"100%\">\n";
   echo "            <tr>\n";
   echo "               <td align=\"left\"><b>Pool</b>:\n";
   echo "                 P <select name=\"ps1\">\n";

   while($rowA = mssql_fetch_row($resA))
   {
      if ($rowA[0]==$rowpre2[2])
      {
         echo "<option value=\"$rowA[0]\" SELECTED>$rowA[0]</option>\n";
      }
      else
      {
         echo "<option value=\"$rowA[0]\">$rowA[0]</option>\n";
      }
   }

   echo "                  </select>\n";
   echo "               </td>\n";
   echo "	            <td align=\"left\">SA <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$rowpre2[3]\"></td>\n";
   echo "	            <td align=\"left\">\n";
	echo "                  S<input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[4]\">\n";
	echo "                  M<input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[5]\">\n";
	echo "                  D<input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"$rowpre2[6]\">\n";
	echo "               </td>\n";
	echo "	            <td align=\"left\">\n";
	echo "                  <b>E.Run</b>: <input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
	echo "                  <b>P.Run</b>: <input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"1\" maxlength=\"3\" value=\"0\">\n";
   echo "                  <b>Deck</b>: <input class=\"bboxl\" type=\"text\" name=\"deck\" size=\"5\" maxlength=\"5\" value=\"0\">\n";
	echo "               </td>\n";
   echo "	            <td align=\"right\">\n";
   echo "                  <select name=\"tzone\">\n";

   while ($rowE = mssql_fetch_row($resE))
   {
      if ($rowE[0]==1)
      {
         echo "                     <option value=\"$rowE[0]\" SELECTED>$rowE[1]</option>";
      }
      else
      {
         echo "                     <option value=\"$rowE[0]\">$rowE[1]</option>";
      }
   }

   echo "                  </select>\n";
   echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   
   if ($nrowF > 0 ||$nrowG > 0)
   {
      echo "   <tr>\n";
      echo "      <td class=\"gray\" align=\"left\" valign=\"top\" width=\"50%\">\n";
      echo "         <table border=1 class=\"inner_borders\" width=\"100%\">\n";
      echo "         <tr>\n";
      echo "            <td class=\"wh\" colspan=\"5\" valign=\"top\">\n";

		$ecnt=1;
		foreach ($catarray as $n=>$v)
      {
      	$qryJ = "SELECT catid,name FROM AC_cats WHERE catid='".$v."';";
         $resJ = mssql_query($qryJ);
         $rowJ = mssql_fetch_row($resJ);
         
         if ($rowJ[0]!=0)
         {
         	if ($ecnt==count($catarray))
         	{
               echo "<a href=\"#$rowJ[0]\">$rowJ[1]</a>";
            }
            else
            {
            	echo "<a href=\"#$rowJ[0]\">$rowJ[1]</a> - ";
            }
            $ecnt++;
         }
      }
      
		echo "            </td>\n";
      echo "         </tr>\n";
      echo "         <tr>\n";
      echo "            <td class=\"gray\" valign=\"top\" align=\"left\"><i>Item</i></td>\n";
      echo "            <td class=\"gray\" valign=\"top\" align=\"right\"><i>Code</i></td>\n";
      echo "            <td class=\"gray\" valign=\"top\" align=\"right\"><i>Price</i></td>\n";
      echo "            <td class=\"gray\" valign=\"top\" align=\"right\"><i>Units</i></td>\n";
      echo "            <td class=\"gray\" valign=\"top\" align=\"right\"><i>Quantity</i></td>\n";
      echo "         </tr>\n";

      // POOL RETAIL ACC ITEM Loop
      foreach ($catarray as $n=>$v)
      {
      	$qryJ = "SELECT catid,name FROM AC_cats WHERE catid='".$v."';";
         $resJ = mssql_query($qryJ);
         $rowJ = mssql_fetch_row($resJ);
         
         if ($v==0)
         {
         }
         else
         {
            echo "         <tr>\n";
            echo "            <td class=\"wh\" colspan=\"4\" align=\"center\" valign=\"top\"><input type=\"hidden\" name=\"#$rowJ[0]\"><b>$rowJ[1]</b></td>\n";
            echo "            <td class=\"wh\" align=\"center\" valign=\"top\"><a href=\"#Top\">Up</a></td>\n";
            echo "         </tr>\n";
         }

         $qryM  = "SELECT id FROM acc WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."' ORDER BY seqn;";
         $resM  = mssql_query($qryM);

         while ($rowM=mssql_fetch_row($resM))
         {
			   form_element_ACC($rowM[0]);
         }
      }

      echo "         </table>\n";
      echo "      </td>\n";
      echo "   </tr>\n";
   }
   echo "</table>\n";
   echo "</form>\n";
}

function matrix1($estid)
{
   $curr_unique_id=md5(time().$_SERVER['REMOTE_ADDR'].microtime());

   if (empty($estid)||$estid==0)
   {
      echo "<font color=\"red\">ERROR</font>(estid=0 or NULL)!<br>Go <a href=\"".$_SERVER['PHP_SELF']."?action=est&call=new\">HERE</a> to Start a New Estimate.\n";
		exit;
   }
   
   $qrypre0 = "SELECT * FROM est WHERE estid='".$estid."';";
   $respre0 = mssql_query($qrypre0);
   $rowpre0 = mssql_fetch_array($respre0);
   
   $qrypre0a = "SELECT * FROM est_acc_ext WHERE estid='".$rowpre0['estid']."';";
   $respre0a = mssql_query($qrypre0a);
   $rowpre0a = mssql_fetch_array($respre0a);
   
   $qrypre1 = "SELECT * FROM cinfo WHERE estid='".$rowpre0['estid']."';";
   $respre1 = mssql_query($qrypre1);
   $rowpre1 = mssql_fetch_array($respre1);

   $qrypre2 = "SELECT officeid,name FROM offices WHERE officeid='".$rowpre0['officeid']."';";
   $respre2 = mssql_query($qrypre2);
   $rowpre2 = mssql_fetch_array($respre2);
   
   $qrypre3 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowpre0['securityid']."';";
   $respre3 = mssql_query($qrypre3);
   $rowpre3 = mssql_fetch_array($respre3);

   $qryA = "SELECT quan FROM rbpricep WHERE officeid='".$rowpre2['officeid']."' ORDER BY quan ASC";
   $resA = mssql_query($qryA);

   $qryB = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum";
   $resB = mssql_query($qryB);

   $qryC = "SELECT phsid,phscode,phstype,phsname,seqnum FROM phasebase WHERE phstype='M' AND costing=1 ORDER BY seqnum";
   $resC = mssql_query($qryC);

   $qryD = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC";
   $resD = mssql_query($qryD);

   $qryE = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC";
   $resE = mssql_query($qryE);

	//$estAarray=explode(",",$rowpre0a['estdata']);
	echo "Retail Accessory Codes: ";
	print_r($rowpre0a['estdata']);
   //echo session_id();
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"".$rowpre2['officeid']."\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowpre3['securityid']."\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"job\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"matrix2\">\n";
   echo "<input type=\"hidden\" name=\"cid\" value=\"".$rowpre1['cid']."\">\n";
   echo "<input type=\"hidden\" name=\"curr_unique_id\" value=\"".$curr_unique_id."\">\n";
   echo "<input type=\"hidden\" name=\"unique_id\"      value=\"".$rowpre0['unique_id']."\">\n";
   echo "<input type=\"hidden\" name=\"estAdata\" value=\"".$rowpre0a['estdata']."\">\n";
   echo "<input type=\"hidden\" name=\"showdetail\" value=\"1\">\n";
   echo "<table class=\"outer\" align=\"center\" width=700px>\n";
   echo "   <tr>\n";
   echo "      <td colspan=\"2\" class=\"gray\" align=\"left\">\n";
   echo "         <table width=\"100%\">\n";
   echo "            <tr>\n";
   echo "	            <td NOWRAP colspan=\"2\" valign=\"bottom\" align=\"right\">\n";
	echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"View Breakdown\">\n";
	echo "               </td>\n";
	echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td valign=\"bottom\" align=\"left\"><b>Cost Estimate for ".$rowpre2['name']." Office</b></td>\n";
   echo "               <td valign=\"bottom\" align=\"right\">Salesperson: <input class=\"bboxl\" type=\"text\" size=\"25\" maxlength=\"25\" value=\"".$rowpre3['fname']." ".$rowpre3['lname']."\"></td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td align=\"left\"><b>Customer:</b> ".$rowpre1['cfname']." ".$rowpre1['clname']."</td>\n";
   echo "               <td align=\"left\">\n";
	echo "                  <a href=\"javascript:popUp('customer_pop_func.php?call=edit&cid=".$rowpre1['cid']."')\">View Customer Info</a>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td colspan=\"2\" class=\"gray\">\n";
   echo "         <table width=\"100%\">\n";
   echo "            <tr>\n";
   echo "               <td align=\"left\"><b>Perimeter:</b>\n";
   echo "                 <select name=\"ps1\">\n";

   while($rowA = mssql_fetch_row($resA))
   {
      if ($rowA[0]==$rowpre0['pft'])
      {
         echo "<option value=\"$rowA[0]\" SELECTED>$rowA[0]</option>\n";
      }
      else
      {
         echo "<option value=\"$rowA[0]\">$rowA[0]</option>\n";
      }
   }

   echo "                  </select>\n";
   echo "               </td>\n";
   echo "	            <td align=\"left\"><b>Surface Area:</b> <input class=\"bbox\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"420\"></td>\n";
   echo "	            <td align=\"right\">\n";
   echo "	               <table>\n";
   echo "	                  <tr>\n";
   echo "	                     <td><b>Spa:</b></td>\n";
   echo "	                     <td>\n";
   echo "	                        <select name=\"spa1\">\n";

   while($rowD = mssql_fetch_row($resD))
   {
   	if ($rowD[0]==$rowpre0['spatype'])
   	{
         echo "	                           <option value=\"$rowD[0]\" SELECTED>$rowD[1]</option>\n";
      }
      else
      {
      	echo "	                           <option value=\"$rowD[0]\">$rowD[1]</option>\n";
      }
   }

   echo "	                        </select>\n";
   echo "	                     </td>\n";
   echo "	                     <td>\n";
   echo "                           pft:\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"spa2\" size=\"5\" maxlength=\"5\" value=\"".$rowpre0['spa_pft']."\">\n";
   echo "                           sqft:\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"spa3\" size=\"5\" maxlength=\"5\" value=\"".$rowpre0['spa_sqft']."\">\n";
   echo "                        </td>\n";
   echo "	                  </tr>\n";
   echo "	               </table>\n";
   echo "	            </td>\n";
   echo "	            <td align=\"right\">\n";
   echo "                  <select name=\"tzone\">\n";

   while ($rowE = mssql_fetch_row($resE))
   {
      if ($rowE[0]==$rowpre0['tzone'])
      {
         echo "                     <option value=\"$rowE[0]\" SELECTED>$rowE[1]</option>";
      }
      else
      {
         echo "                     <option value=\"$rowE[0]\">$rowE[1]</option>";
      }
   }

   echo "                  </select>\n";
   echo "               </td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td NOWRAP colspan=\"2\" valign=\"bottom\" align=\"left\">Base Discount $<input class=\"bbox\" type=\"text\" name=\"discount\" value=\"".$rowpre0['discount']."\"  size=\"20\" maxlength=\"20\"></td>\n";
   echo "	            <td NOWRAP valign=\"bottom\" align=\"right\">\n";
	echo "   Referral Fee $<input class=\"bbox\" type=\"text\" name=\"bbbl20001\" value=\"0\" size=\"8\" maxlength=\"8\">\n";
	echo "                  <input type=\"hidden\" name=\"aaal20001\" value=\"54\"> \n";
	echo "         Paid to: <input class=\"bboxl\" type=\"text\" name=\"refpaidto\" value=\"".$rowpre0['refto']."\" size=\"20\" maxlength=\"20\">\n";
	echo "               </td>\n";
   //echo "	            <td NOWRAP valign=\"bottom\" align=\"right\">\n";
	//echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"View Breakdown\">\n";
	//echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" colspan=\"2\" valign=\"top\" width=\"100%\"><b>Detailed Options:</b></td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" valign=\"top\" width=\"50%\">\n";
   echo "         <table border=1 class=\"inner_borders\" width=\"100%\">\n";

   // Labor Form OPTIONS Loop
   while($rowB = mssql_fetch_row($resB))
   {
      $qryD = "SELECT DISTINCT(accid),seqnum FROM accpbook WHERE officeid='".$rowpre0['officeid']."' AND phsid=$rowB[0] AND baseitem!=1 AND spaitem!=1 ORDER BY seqnum";
      $resD = mssql_query($qryD);
      $nrowsD =mssql_num_rows($resD);

      $qryF = "SELECT DISTINCT(accid),seqnum FROM accpbook WHERE officeid='".$rowpre0['officeid']."' AND phsid=$rowB[0] AND baseitem!=1 AND spaitem=1 ORDER BY seqnum";
      $resF = mssql_query($qryF);
      $nrowsF =mssql_num_rows($resF);

      if ($nrowsD > 0)
      {
         echo "            <tr>\n";
         echo "               <td class=\"und\" colspan=\"2\" valign=\"top\" align=\"left\"><b>$rowB[3]</b> ($rowB[1])</td>\n";
         echo "            </tr>\n";

         while($rowD = mssql_fetch_row($resD))
         {
            form_element($rowB[0],$rowD[0],$rowpre0a['estdata']);
         }

         echo "            <tr>\n";
         echo "               <td valign=\"top\" align=\"left\" colspan=\"2\">&nbsp;</td>\n";
         echo "            </tr>\n";
      }

      if ($nrowsF > 0)
      {
         echo "            <tr>\n";
         echo "               <td class=\"und\" colspan=\"2\" valign=\"top\" align=\"left\"><b>$rowB[3] Spa</b> ($rowB[1])</td>\n";
         echo "            </tr>\n";

         while($rowF = mssql_fetch_row($resF))
         {
            form_element($rowB[0],$rowF[0],$rowpre0a['estdata']);
         }

         echo "            <tr>\n";
         echo "               <td valign=\"top\" align=\"left\" colspan=\"2\">&nbsp;</td>\n";
         echo "            </tr>\n";
      }
   }

   echo "         </table>\n";
   echo "      </td>\n";
   echo "      <td class=\"gray\" valign=\"top\" width=\"50%\">\n";
   echo "         <table border=1 class=\"inner_borders\" width=\"100%\">\n";

   // Material Form Elements Loop
   while($rowC = mssql_fetch_row($resC))
   {
      $qryE = "SELECT DISTINCT(accid),seqnum FROM inventory WHERE officeid='".$rowpre0['officeid']."' AND phsid=$rowC[0] AND active=1 ORDER BY seqnum";
      $resE = mssql_query($qryE);

      $qryG = "SELECT DISTINCT(accid),seqnum FROM inventory WHERE officeid='".$rowpre0['officeid']."' AND phsid=$rowC[0] AND active=1 ORDER BY seqnum";
      $resG = mssql_query($qryG);
      $nrowsG =mssql_num_rows($resG);

      if ($nrowsG > 0)
      {
         echo "            <tr>\n";
         echo "               <td class=\"und\" colspan=\"2\" valign=\"top\" align=\"left\"><b>$rowC[3]</b> ($rowC[1])</td>\n";
         echo "            </tr>\n";

         while($rowE = mssql_fetch_row($resE))
         {
            form_elementM($rowC[0],$rowE[0],$rowpre0a['estdata']);
         }

         echo "            <tr>\n";
         echo "               <td valign=\"top\" align=\"left\" colspan=\"2\">&nbsp;</td>\n";
         echo "            </tr>\n";
      }
   }

   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</table>\n";
   echo "</form>\n";
}

function viewest_retail($estid)
{
   global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate,$tbid,$tbullets;
   
   $securityid =$_SESSION['securityid'];	
   $officeid   =$_SESSION['officeid'];
   $fname      =$_SESSION['fname'];
   $lname      =$_SESSION['lname'];
   
   if (!isset($estid)||$estid=='')
   {
      echo "Fatal Error: \$$estid not set!";
      exit;
   }
   
   $qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$estid."';";
   $respreA = mssql_query($qrypreA);
   $rowpreA = mssql_fetch_row($respreA);
   
   //$qrypreB = "SELECT estdata FROM est_labor_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   //$respreB = mssql_query($qrypreB);
   //$rowpreB = mssql_fetch_row($respreB);
   
   //$qrypreC = "SELECT estdata FROM est_inv_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   //$respreC = mssql_query($qrypreC);
   //$rowpreC = mssql_fetch_row($respreC);
   
   $qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreD = mssql_query($qrypreD);
   $rowpreD = mssql_fetch_row($respreD);

   $viewarray=array(
                    'ps1'=>$rowpreA[1],
                    'ps2'=>$rowpreA[2],
                    'spa1'=>$rowpreA[3],
                    'spa2'=>$rowpreA[4],
                    'spa3'=>$rowpreA[5],
                    'tzone'=>$rowpreA[6],
                    'camt'=>$rowpreA[7],
                    'cfname'=>$rowpreA[8],
                    'clname'=>$rowpreA[9],
                    'phone'=>$rowpreA[10],
                    'status'=>$rowpreA[11],
                    'ps5'=>$rowpreA[13],
                    'ps6'=>$rowpreA[14],
                    'ps7'=>$rowpreA[15],
                    'custid'=>$rowpreA[16],
                    'estsecid'=>$rowpreA[17],
                    'deck'=>$rowpreA[18],
						  'erun'=>$rowpreA[19],
						  'prun'=>$rowpreA[20]
                    );
   
   $qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split FROM offices WHERE officeid='".$_SESSION['officeid']."';";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_row($resC);

   $idarray=array(0=>$viewarray['estsecid'],1=>$rowC[3],2=>$rowC[4],3=>26); // Last Array value is Admin Account for testing **Fix to include ALL SysAdmins **
   
   if (!in_array($_SESSION['securityid'],$idarray))
   {
   	/*
		echo "<pre>";
		print_r($viewarray);
		echo "</pre>";
		echo "<pre>";
		print_r($idarray);
		echo "</pre>";
		echo $_SESSION['securityid'];
		*/
   	echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Estimate</b>";
   	exit;
   }
   
   $qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
   $resA = mssql_query($qryA);

   $qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND quan='".$viewarray['ps1']."';";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_row($resB);

   $qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['estsecid']."';";
   $resD = mssql_query($qryD);
   $rowD = mssql_fetch_row($resD);
   
   $qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
   $resE = mssql_query($qryE);
   
   $qryF = "SELECT snum,cat,description FROM status_codes WHERE snum='".$viewarray['status']."';";
   $resF = mssql_query($qryF);
   $rowF = mssql_fetch_row($resF);
   
   $qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$_SESSION['officeid']."' ORDER BY zid ASC;";
   $resG = mssql_query($qryG);
   
   //$qryH  = "SELECT estid,estaddid FROM est_addendum WHERE estid='".$estidret."' AND estaddid=1;";
   //$resH  = mssql_query($qryH);
   //$nrowsH= mssql_num_rows($resH);
   
   $qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$rowpreA[16]."';";
   $resI = mssql_query($qryI);
   $rowI = mssql_fetch_row($resI);
   
   $qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowC[3]."';";
   $resL = mssql_query($qryL);
   $rowL = mssql_fetch_row($resL);
   
   $qryM = "SELECT officeid,descrip FROM base_description WHERE officeid='".$_SESSION['officeid']."';";
   $resM = mssql_query($qryM);
   $rowM = mssql_fetch_row($resM);
   
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

	$set_deck   =deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    =round($set_deck[0]);
	$set_ia		=calc_internal_area($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$set_gals	=calc_gallons($viewarray['ps1'],$viewarray['ps2'],$viewarray['ps5'],$viewarray['ps6'],$viewarray['ps7']);
	$tbullets   =0;
   $estidret   =$rowpreA[0];
   $vdiscnt    =$viewarray['camt'];
   $pbaseprice =$rowB[2];
   $bquan      =$rowB[1];
   $bcomm      =$rowB[3];
   $fpbaseprice=number_format($pbaseprice, 2, '.', '');
   $fbcomm		=number_format($bcomm, 2, '.', '');
   $ctramt     =$viewarray['camt'];
   $fctramt		=number_format($ctramt, 2, '.', '');
   
   //print_r($viewarray);
   
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"".$viewarray['estsecid']."\">\n";
   echo "<input type=\"hidden\" name=\"discount\" value=\"".$vdiscnt."\">\n";
   echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
   echo "<input type=\"hidden\" name=\"contractamt\" value=\"".$fctramt."\">\n";
   echo "<input type=\"hidden\" name=\"spa1\" value=\"0\">\n";
   echo "<input type=\"hidden\" name=\"spa2\" value=\"0\">\n";
   echo "<input type=\"hidden\" name=\"spa3\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"status\" value=\"".$viewarray['status']."\">\n";
   
   if ($viewarray['status'] <= 2)
   {
      echo "<input type=\"hidden\" name=\"call\" value=\"update\">\n";
   }
   elseif ($viewarray['status'] == 3)
   {
      echo "<input type=\"hidden\" name=\"call\" value=\"addm\">\n";
   }
   
   echo "<table align=\"center\" width=\"700px\" border=0>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" valign=\"top\" align=\"left\">\n";
   echo "         <table align=\"center\" width=\"100%\" border=0>\n";
   echo "            <tr>\n";
   echo "               <td class=\"gray\" align=\"left\" NOWRAP><b>Retail Estimate #".$estidret." for ".$rowC[1]."</b></td>\n";
   echo "               <td rowspan=\"2\" class=\"gray\" align=\"left\" NOWRAP>\n";
   echo "                  <table width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Salesperson</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowD[1]." ".$rowD[2]."\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Sales Manager</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowL[1]." ".$rowL[2]."\"></td>\n";
	echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "                        <td align=\"right\"><b>Peri</b></td>\n";
   echo "                        <td align=\"left\">\n";
	echo "                           <select name=\"ps1\">\n";

   while($rowA = mssql_fetch_row($resA))
   {
      if ($rowA[1]==$viewarray['ps1'])
      {
         echo "                           <option value=\"$rowA[1]\" SELECTED>$rowA[1]</option>\n";
      }
      else
      {
         echo "                           <option value=\"$rowA[1]\">$rowA[1]</option>\n";
      }
   }

   echo "                           </select>\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>Gal</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$set_gals."\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>SA</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['ps2']."\">\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>IA</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$set_ia."\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>S/M/D</b></td>\n";
   echo "	                     <td colspan=\"3\" align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps5']."\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps6']."\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"".$viewarray['ps7']."\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
	echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>E. Run</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['erun']."\">\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>P. Run</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['prun']."\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Deck</b></td>\n";
   echo "	                     <td colspan=\"3\" align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"deck\" size=\"4\" maxlength=\"4\" value=\"".$viewarray['deck']."\"> \n";
   
   if ($viewarray['ps1'] > 0)
   {
   	echo " (<b>$incdeck</b> sqft Deck Incl.)";
   }
   
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Travel</b></td>\n";
   echo "	                     <td colspan=\"3\" align=\"left\">\n";
   echo "                           <select name=\"tzone\">\n";

   while ($rowG = mssql_fetch_row($resG))
   {
      if ($rowG[0]==$viewarray['tzone'])
      {
         echo "                           <option value=\"".$rowG[0]."\" SELECTED>".$rowG[1]."</option>\n";
      }
      else
      {
         echo "                           <option value=\"".$rowG[0]."\">".$rowG[1]."</option>\n";
      }
   }

   echo "                           </select>\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Status</b></td>\n";
   echo "                        <td colspan=\"3\" class=\"gray\" align=\"left\">\n";
   echo "                           <b>$rowF[2]</b>\n";
   echo "	                     </td>\n";
   echo "                     </tr>\n";
   echo "                  </table>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td valign=\"top\" align=\"left\" width=\"400\">\n";
   echo "                  <table width=\"100%\" border=0>\n";
   echo "                     <tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Customer # </b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"><b>".$rowI[0]."</b></td>\n";
	echo "                     </tr>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\" width=\"80\"><b>Name</b> </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"cfname\" size=\"15\" maxlength=\"20\" value=\"".$rowI[1]."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"clname\" size=\"29\" maxlength=\"42\" value=\"".$rowI[2]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Site</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"saddr1\" size=\"50\" maxlength=\"42\" value=\"".$rowI[5]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>City</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"scity\" size=\"20\" maxlength=\"42\" value=\"".$rowI[6]."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"sstate\" size=\"5\" maxlength=\"42\" value=\"".$rowI[7]."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"szip1\" size=\"10\" maxlength=\"42\" value=\"".$rowI[8]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Phone</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"chome\" size=\"15\" maxlength=\"42\" value=\"".$rowI[3]."\"> home\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ccell\" size=\"15\" maxlength=\"42\" value=\"".$rowI[9]."\"> cell\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Twnshp/Cnty</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";

	if ($rowC[2]==1)
   {
	   echo "                           <select name=\"scounty\">\n";
	   echo "                              <option value=\"0\">None</option>\n";

	   while($rowK = mssql_fetch_row($resK))
      {
         if ($rowK[0]==$rowI[4])
         {
            echo "                           <option value=\"".$rowK[0]."\" SELECTED>".$rowK[1]."</option>\n";
         }
         else
         {
            echo "                           <option value=\"".$rowK[0]."\">".$rowK[1]."</option>\n";
         }
      }
      echo "                           </select>\n";
   }
   else
	{
	   echo "                           <input class=\"bboxl\" type=\"text\" name=\"scounty\" size=\"25\" maxlength=\"30\" value=\"".$rowI[4]."\">\n";
	}

	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "      <td valign=\"bottom\" align=\"right\"> \n";
   
   if ($viewarray['status'] <= 2)
   {
      echo "<input class=\"buttondkgrypnl\" type=\"submit\" value=\"Update\">\n";
   }
   elseif ($viewarray['status'] <= 3)
   {
      echo "<input class=\"buttondkgrypnl\" type=\"submit\" value=\"Addendum\">\n";
   }
   
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</form>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" valign=\"top\" align=\"center\">\n";
   echo "         <table cellpadding=0 cellspacing=0 bordercolor=\"black\" border=1>\n";
   echo "           <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
   echo "           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "           <input type=\"hidden\" name=\"call\" value=\"remove_acc\">\n";
   echo "           <input type=\"hidden\" name=\"estid\" value=\"".$_SESSION['estid']."\">\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Category</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"left\" width=\"350\"><b>Item</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Quan.</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Units</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Retail</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Comm</b></td>\n";
   echo "              <td NOWRAP valign=\"bottom\" align=\"center\"><input class=\"buttondkgry\" type=\"submit\" value=\"Rem\"></td>\n";
   echo "           </tr>\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\">Base</td>\n";
   //echo "              <td class=\"lg\" valign=\"top\" align=\"left\"><b>Basic Pool</b><br>Includes: <font size=\"6\">".$rowM[1]."</font></td>\n";
	//echo "              <td class=\"lg\" valign=\"top\" align=\"left\"><b>Basic Pool</b> Includes:<br>\n";
	
	//base_inclusion();
	
	//echo "              </td>\n";
	
	echo "              <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\"><b>Basic Pool</b> Includes:</td>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$bquan."</td>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">pft</td>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fpbaseprice."</td>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">".$fbcomm."</td>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"center\"></td>\n";
   echo "           </tr>\n";
	
	//base_inclusion();
   
   calcbyacc($rowpreD[0]);
   
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
   $prof    =($trcost-$tbcost)-$trcomm;
   $perprof =$prof/$trcost;
   
   if ($rowC[2]==1)
	{
      //$rtax    =$trcost*$taxrate[1];
	   //$grtcost =$trcost+$rtax;
	   $rtax    =$ctramt*$taxrate[1];
	   $grtcost =$ctramt+$rtax;
	   $frtax   =number_format($rtax, 2, '.', '');
      $fgrtcost=number_format($grtcost, 2, '.', '');
	}

   $fbccost    =number_format($bccost, 2, '.', '');
   $fbmcost    =number_format($bmcost, 2, '.', '');
   $fcccost    =number_format($cccost, 2, '.', '');
   $frccost    =number_format($rccost, 2, '.', '');
   $frmcost    =number_format($rmcost, 2, '.', '');
   $fcmcost    =number_format($cmcost, 2, '.', '');
   $ftbcost    =number_format($tbcost, 2, '.', '');
   $ftrcost    =number_format($trcost, 2, '.', '');
   $ftccost    =number_format($tccost, 2, '.', '');
   $ftrcomm    =number_format($trcomm, 2, '.', '');
   
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b> Pool Price per Book:</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftrcost."</td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftrcomm."</td>\n";
   echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
   echo "           </tr>\n";
   
   calc_adjusts($rowpreA[0]);
   
   $adjbookamt	 =$trcost+$discount;
   $fadjbookamt =number_format($adjbookamt, 2, '.', '');
   
   $adjctramt	 =$ctramt-$adjbookamt;
   $fadjctramt	 =number_format($adjctramt, 2, '.', '');
   //$ndisccomm	 =$ctramt-$trcost;      // Questionable
   $adjcomm     =$adjctramt*$rowC[7];  //
   $fadjcomm	 =number_format($adjcomm, 2, '.', '');
   
   if ($tbullets >= $rowC[6]) // Bullet Adjustment
   {
      $tadjcomm    =$trcomm+$adjcomm+$rowC[5];
   }
   else
   {
   	$tadjcomm    =$trcomm+$adjcomm;
   }
   $ftadjcomm	 =number_format($tadjcomm, 2, '.', '');
   
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Adjusted Book Price:</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fadjbookamt."</td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
   echo "           </tr>\n";
   echo "</form>\n";
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"update_contract_amt\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Actual Contract Price:</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">\n";
   echo "                 <input class=\"bbox\" type=\"text\" name=\"c_amt\" size=\"6\" maxlength=\"10\" value=\"".$fctramt."\">\n";
	echo "              </td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "              <td NOWRAP class=\"lg\" align=\"center\">\n";
   echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Go\">\n";
	echo "              </td>\n";
   echo "           </tr>\n";
   echo "</form>\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Overage/Underage:</b></td>\n";
   
   if ($adjctramt < 0)
   {
      echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\">".$fadjctramt."</font></td>\n";
   }
   else
   {
   	echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fadjctramt."</td>\n";
   }
   
   echo "              <td NOWRAP class=\"wh\" align=\"right\">".$fadjcomm."</td>\n";
   echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
   echo "           </tr>\n";
   
   if ($tbullets >= $rowC[6])
   {
      echo "           <tr>\n";
      echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Bullet (".$tbullets.") Commission:</b></td>\n";
      echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
      echo "              <td NOWRAP class=\"wh\" align=\"right\">".$rowC[5]."</td>\n";
      echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
      echo "           </tr>\n";
   }
   
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Total Commission:</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">".$ftadjcomm."</td>\n";
   echo "              <td NOWRAP class=\"lg\" align=\"center\"></td>\n";
   echo "           </tr>\n";
   
   if ($rowC[2]==1)
   {
      echo "            <tr>\n";
      echo "               <td colspan=\"4\" class=\"wh\" align=\"right\"><b>Tax (".$taxrate[1]."):</b></td>\n";
      echo "               <td align=\"right\" class=\"wh\" width=\"60\">".$frtax."</td>\n";
      echo "               <td class=\"wh\" align=\"right\"></td>\n";
      echo "               <td class=\"lg\" align=\"right\"></td>\n";
      echo "            </tr>\n";
      echo "            <tr>\n";
      echo "               <td colspan=\"4\" class=\"wh\" align=\"right\"><b>Total:</b></td>\n";
      echo "               <td align=\"right\" class=\"wh\" width=\"60\">".$fgrtcost."</td>\n";
      echo "               <td class=\"wh\" align=\"right\"></td>\n";
      echo "               <td class=\"lg\" align=\"right\"></td>\n";
      echo "            </tr>\n";
   }
   
   echo "         </table>\n";
   echo "      </td>\n";
   echo "      <td valign=\"top\" align=\"right\">\n";
   echo "         <table width=\"80px\" cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";
	echo "            <tr>\n";
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"view_addnew\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
   echo "               <td align=\"right\">\n";
   echo "                  <input class=\"buttondkgrypnl\" type=\"submit\" value=\"Edit Items\">\n";
   echo "               </td>\n";
   echo "</form>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"addadj\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
   echo "               <td align=\"right\">\n";
   echo "                  <input class=\"buttondkgrypnl\" type=\"submit\" value=\"Discounts\">\n";
   echo "               </td>\n";
   echo "</form>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"view_retail_print\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
   echo "               <td align=\"right\">\n";
   echo "                  <input class=\"buttondkgrypnl\" type=\"submit\" value=\"Print View\">\n";
   echo "               </td>\n";
   echo "</form>\n";
   echo "            </tr>\n";
   
   if ($_SESSION['jlev'] > 6)
   {
   	echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
      echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
      echo "<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
   	echo "<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
      echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
      echo "<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
      echo "<input type=\"hidden\" name=\"tcomm\" value=\"".$tadjcomm."\">\n";
      echo "<input type=\"hidden\" name=\"tretail\" value=\"".$adjbookamt."\">\n";
      echo "<input type=\"hidden\" name=\"tcontract\" value=\"".$ctramt."\">\n";
      echo "<input type=\"hidden\" name=\"discount\" value=\"".$vdiscnt."\">\n";
      echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
      echo "<input type=\"hidden\" name=\"acctotal\" value=\"".$trccost."\">\n";
      echo "            <tr>\n";
      echo "               <td align=\"right\">\n";
      echo "                  <input class=\"buttondkgrypnl\" type=\"submit\" value=\"View Cost\">\n";
      echo "               </td>\n";
      echo "</form>\n";
      echo "            </tr>\n";
   }
   echo "         </table>\n";
   echo "<input type=\"hidden\" name=\"comments\" value=\"".$rowpreA[12]."\">\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</table>\n";
}

function viewest_addnew($estid)
{
   global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate;

   $securityid =$_SESSION['securityid'];
   $officeid   =$_SESSION['officeid'];
   $fname      =$_SESSION['fname'];
   $lname      =$_SESSION['lname'];

   if (!isset($estid)||$estid==''||$estid==0)
   {
      echo "Fatal Error: \$$estid not set, or is Zero!";
      exit;
   }

   $qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid FROM est WHERE officeid='".$officeid."' AND estid='".$estid."';";
   $respreA = mssql_query($qrypreA);
   $rowpreA = mssql_fetch_row($respreA);

	/*
   $qrypreB = "SELECT estdata FROM est_labor_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreB = mssql_query($qrypreB);
   $rowpreB = mssql_fetch_row($respreB);

   $qrypreC = "SELECT estdata FROM est_inv_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreC = mssql_query($qrypreC);
   $rowpreC = mssql_fetch_row($respreC);
   */

   $qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreD = mssql_query($qrypreD);
   $rowpreD = mssql_fetch_row($respreD);
   
   // Builds a list of exisiting categories in the retail accessory table by office
   $qrypreE  = "SELECT DISTINCT a.catid,a.seqn ";
   $qrypreE .= "FROM AC_cats AS a INNER JOIN acc AS b ";
   $qrypreE .= "ON a.catid=b.catid ";
   $qrypreE .= "AND b.officeid='".$officeid."' ";
   $qrypreE .= "ORDER BY a.seqn ASC;";
   $respreE = mssql_query($qrypreE);
   
   while ($rowpreE = mssql_fetch_row($respreE))
   {
      $catarray[]=$rowpreE[0];
   }

   $ps1        =$rowpreA[1];
   $ps2        =$rowpreA[2];
   $spa1       =$rowpreA[3];
   $spa2       =$rowpreA[4];
   $spa3       =$rowpreA[5];
   $tzone      =$rowpreA[6];
   $discount   =$rowpreA[7];
   $cfname     =$rowpreA[8];
   $clname     =$rowpreA[9];
   $phone      =$rowpreA[10];
   $status     =$rowpreA[11];
   $ps5        =$rowpreA[13];
   $ps6        =$rowpreA[14];
   $ps7        =$rowpreA[15];

   $viewarray=array(
                    'ps1'=>$rowpreA[1],
                    'ps2'=>$rowpreA[2],
                    'spa1'=>$rowpreA[3],
                    'spa2'=>$rowpreA[4],
                    'spa3'=>$rowpreA[5],
                    'tzone'=>$rowpreA[6],
                    'discount'=>$rowpreA[7],
                    'cfname'=>$rowpreA[8],
                    'clname'=>$rowpreA[9],
                    'phone'=>$rowpreA[10],
                    //'estLdata'=>$rowpreB[0],
                    //'estMdata'=>$rowpreC[0],
                    'status'=>$rowpreA[11],
                    'ps5'=>$rowpreA[13],
                    'ps6'=>$rowpreA[14],
                    'ps7'=>$rowpreA[15]
                    );

   $qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
   $resA = mssql_query($qryA);

   $qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='$ps1';";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_row($resB);

   $qryC = "SELECT officeid,name,stax FROM offices WHERE officeid='".$officeid."';";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_row($resC);

   $qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$securityid."';";
   $resD = mssql_query($qryD);
   $rowD = mssql_fetch_row($resD);

   $qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
   $resE = mssql_query($qryE);

   $qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
   $resF = mssql_query($qryF);

   $qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC;";
   $resG = mssql_query($qryG);

	/*
   $qryH  = "SELECT estid,estaddid FROM est_addendum WHERE estid='".$estidret."' AND estaddid=1;";
   $resH  = mssql_query($qryH);
   $nrowsH= mssql_num_rows($resH);
   */

   $qryI = "SELECT cid,cfname,clname,chome,scounty FROM cinfo WHERE custid='".$rowpreA[16]."';";
   $resI = mssql_query($qryI);
   $rowI = mssql_fetch_row($resI);

   // Sets Tax Rate
   if ($rowC[2]==1)
   {
      $qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
      $resJ = mssql_query($qryJ);
      $rowJ = mssql_fetch_row($resJ);

      $taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
   }

   $estidret   =$rowpreA[0];
   $vdiscnt    =$viewarray['discount'];
   $pbaseprice =$rowB[2]-$discount;
   $bcomm      =$rowB[3];
   $fpbaseprice=number_format($pbaseprice, 2, '.', '');

   echo "<input type=\"hidden\" name=\"#Top\">\n";
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"acc_adds\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"$estidret\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
   echo "<input type=\"hidden\" name=\"discount\" value=\"$vdiscnt\">\n";
   echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
   echo "<table class=\"outer\" align=\"center\" width=\"700px\" border=0>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" colspan=\"4\" valign=\"top\" align=\"left\">\n";
   echo "         <table align=\"center\" width=\"100%\" border=0>\n";
   echo "            <tr>\n";
   echo "               <td colspan=\"5\" class=\"gray\" align=\"left\"><b>Retail Estimate for $rowC[1] Office</b> (EstID: <b>$estidret</b>)</td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td colspan=\"3\" align=\"left\"><b>Customer:</b> $rowI[1] $rowI[2]</td>\n";
	echo "               <td align=\"left\"><b>Perimeter:</b> $ps1  <b>Surface:</b> $ps2  <b>Shallow:</b> $rowpreA[13]  <b>Middle:</b> $rowpreA[14]  <b>Deep:</b> $rowpreA[15]</td>\n";
	echo "	            <td class=\"gray\" valign=\"bottom\" align=\"right\">\n";
	echo "                  Salesperson: \n";
	echo "                  <b>$rowD[1] $rowD[2]</b>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td colspan=\"4\" class=\"gray\" align=\"right\" NOWRAP>\n";
   echo "         <input class=\"buttondkgry\" type=\"submit\" value=\"Update Items\">\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td colspan=\"4\" class=\"gray\" align=\"right\" NOWRAP>\n";
   echo "         <table border=1 class=\"inner_borders\" width=\"100%\">\n";
   echo "         <tr>\n";
   echo "            <td class=\"wh\" colspan=\"5\" valign=\"top\">\n";
   
   	$ecnt=1;
		foreach ($catarray as $n=>$v)
      {
      	$qryJ = "SELECT catid,name FROM AC_cats WHERE catid='".$v."';";
         $resJ = mssql_query($qryJ);
         $rowJ = mssql_fetch_row($resJ);

         if ($rowJ[0]!=0)
         {
         	if ($ecnt==count($catarray))
         	{
               echo "<a href=\"#$rowJ[0]\">$rowJ[1]</a>";
            }
            else
            {
            	echo "<a href=\"#$rowJ[0]\">$rowJ[1]</a> - ";
            }
            $ecnt++;
         }
      }

	echo "            </td>\n";
   echo "         </tr>\n";
   echo "         <tr>\n";
   echo "            <td class=\"gray\" valign=\"top\"><i>Item</i></td>\n";
   echo "            <td class=\"gray\" valign=\"top\"><i>Code</i></td>\n";
   echo "            <td class=\"gray\" valign=\"top\"><i>Price</i></td>\n";
   echo "            <td class=\"gray\" valign=\"top\"><i>Units</i></td>\n";
   echo "            <td class=\"gray\" valign=\"top\"><i>Quantity</i></td>\n";
   echo "         </tr>\n";

   // POOL RETAIL ACC ITEM Loop
   foreach ($catarray as $n=>$v)
   {
      	$qryJ = "SELECT catid,name FROM AC_cats WHERE catid='".$v."';";
         $resJ = mssql_query($qryJ);
         $rowJ = mssql_fetch_row($resJ);

         if ($v==0)
         {
         }
         else
         {
         	echo "         <tr>\n";
            echo "            <td class=\"wh\" colspan=\"4\" align=\"center\" valign=\"top\"><input type=\"hidden\" name=\"#$rowJ[0]\"><b>$rowJ[1]</b></td>\n";
            echo "            <td class=\"wh\" align=\"center\" valign=\"top\"><a href=\"#Top\">Up</a></td>\n";
            echo "         </tr>\n";
            //echo "         <tr>\n";
            //echo "            <td class=\"wh\" colspan=\"5\" align=\"left\" valign=\"top\"><b>$rowJ[1]</b></td>\n";
            //echo "         </tr>\n";
         }

         $qryM  = "SELECT id FROM acc WHERE officeid='".$_SESSION['officeid']."' AND catid='".$v."' ORDER BY seqn;";
         $resM  = mssql_query($qryM);

         while ($rowM=mssql_fetch_row($resM))
         {
			   form_element_ACC($rowM[0]);
         }
   }

   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
	echo "</form>\n";
   echo "</table>\n";
}

function viewest_retail_print($estid)
{
   global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate,$tbullets;

   $securityid =$_SESSION['securityid'];
   $officeid   =$_SESSION['officeid'];
   $fname      =$_SESSION['fname'];
   $lname      =$_SESSION['lname'];

   if (!isset($estid)||$estid=='')
   {
      echo "Fatal Error: \$$estid not set!";
      exit;
   }

   $qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun FROM est WHERE officeid='".$officeid."' AND estid='".$estid."';";
   $respreA = mssql_query($qrypreA);
   $rowpreA = mssql_fetch_row($respreA);
/*
   $qrypreB = "SELECT estdata FROM est_labor_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreB = mssql_query($qrypreB);
   $rowpreB = mssql_fetch_row($respreB);

   $qrypreC = "SELECT estdata FROM est_inv_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreC = mssql_query($qrypreC);
   $rowpreC = mssql_fetch_row($respreC);
*/
   $qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreD = mssql_query($qrypreD);
   $rowpreD = mssql_fetch_row($respreD);

   $ps1        =$rowpreA[1];
   $ps2        =$rowpreA[2];
   $spa1       =$rowpreA[3];
   $spa2       =$rowpreA[4];
   $spa3       =$rowpreA[5];
   $tzone      =$rowpreA[6];
   $discount   =$rowpreA[7];
   $cfname     =$rowpreA[8];
   $clname     =$rowpreA[9];
   $phone      =$rowpreA[10];
   $status     =$rowpreA[11];
   $ps5        =$rowpreA[13];
   $ps6        =$rowpreA[14];
   $ps7        =$rowpreA[15];

   $viewarray=array(
                    'ps1'=>$rowpreA[1],
                    'ps2'=>$rowpreA[2],
                    'spa1'=>$rowpreA[3],
                    'spa2'=>$rowpreA[4],
                    'spa3'=>$rowpreA[5],
                    'tzone'=>$rowpreA[6],
                    'camt'=>$rowpreA[7],
                    'cfname'=>$rowpreA[8],
                    'clname'=>$rowpreA[9],
                    'phone'=>$rowpreA[10],
                    //'estLdata'=>$rowpreB[0],
                    //'estMdata'=>$rowpreC[0],
                    'status'=>$rowpreA[11],
                    'ps5'=>$rowpreA[13],
                    'ps6'=>$rowpreA[14],
                    'ps7'=>$rowpreA[15],
                    'cid'=>$rowpreA[16],
                    'estsecid'=>$rowpreA[17],
                    'deck'=>$rowpreA[18],
						  'erun'=>$rowpreA[19],
						  'prun'=>$rowpreA[20]
                    );
                    
   $qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split FROM offices WHERE officeid='".$_SESSION['officeid']."';";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_row($resC);

   $idarray=array(0=>$viewarray['estsecid'],1=>$rowC[3],2=>$rowC[4],3=>26); // Last Array value is Admin Account for testing **Fix to include ALL SysAdmins **

   if (!in_array($_SESSION['securityid'],$idarray))
   {
   	echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to view this Estimate</b>";
   	exit;
   }

   $qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
   $resA = mssql_query($qryA);
   $rowA = mssql_fetch_row($resA);

   $qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='$ps1';";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_row($resB);

   //$qryC = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split FROM offices WHERE officeid='".$officeid."';";
   //$resC = mssql_query($qryC);
   //$rowC = mssql_fetch_row($resC);

   $qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$viewarray['estsecid']."';";
   $resD = mssql_query($qryD);
   $rowD = mssql_fetch_row($resD);

   $qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
   $resE = mssql_query($qryE);

   $qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum='".$status."';";
   $resF = mssql_query($qryF);
   $rowF = mssql_fetch_row($resF);

   $qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' AND zid='".$tzone."';";
   $resG = mssql_query($qryG);
   $rowG = mssql_fetch_row($resG);

   //$qryH  = "SELECT estid,estaddid FROM est_addendum WHERE estid='".$estidret."' AND estaddid=1;";
   //$resH  = mssql_query($qryH);
   //$nrowsH= mssql_num_rows($resH);

   $qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell FROM cinfo WHERE custid='".$rowpreA[16]."';";
   $resI = mssql_query($qryI);
   $rowI = mssql_fetch_row($resI);
   
   $qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowC[3]."';";
   $resL = mssql_query($qryL);
   $rowL = mssql_fetch_row($resL);
   
   $qryM = "SELECT officeid,descrip FROM base_description WHERE officeid='".$_SESSION['officeid']."';";
   $resM = mssql_query($qryM);
   $rowM = mssql_fetch_row($resM);

   // Sets Tax Rate
   if ($rowC[2]==1)
   {
      $qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
      $resJ = mssql_query($qryJ);
      $rowJ = mssql_fetch_row($resJ);

      $taxrate=array(0=>$rowI[4],1=>$rowJ[0]);
      
      $qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' AND id='".$rowI[4]."';";
      $resK = mssql_query($qryK);
      $rowK = mssql_fetch_row($resK);
   }
   
   $set_deck   =deckcalc($viewarray['ps1'],$viewarray['deck']);
	$incdeck    =round($set_deck[0]);
   $set_ia		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$set_gals	=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
	$tbullets   =0;
   $estidret   =$rowpreA[0];
   $vdiscnt    =$viewarray['camt'];
   $pbaseprice =$rowB[2];
   $bquan      =$rowB[1];
   $bcomm      =$rowB[3];
   $fpbaseprice=number_format($pbaseprice, 2, '.', '');
   $fbcomm		=number_format($bcomm, 2, '.', '');
   $ctramt     =$viewarray['camt'];
   $fctramt		=number_format($ctramt, 2, '.', '');
   
   $brdr=0;

   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"$estidret\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
   echo "<input type=\"hidden\" name=\"discount\" value=\"$vdiscnt\">\n";
   echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
   echo "<input type=\"hidden\" name=\"contractamt\" value=\"".$fctramt."\">\n";
   echo "<input type=\"hidden\" name=\"spa1\" value=\"0\">\n";
   echo "<input type=\"hidden\" name=\"spa2\" value=\"0\">\n";
   echo "<input type=\"hidden\" name=\"spa3\" value=\"0\">\n";
   echo "<table align=\"center\" width=\"700px\" border=0>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" valign=\"top\" align=\"left\">\n";
   echo "         <table align=\"center\" width=\"100%\" border=$brdr>\n";
   echo "            <tr>\n";
   echo "               <td class=\"gray\" align=\"left\" NOWRAP><b>Retail Estimate #$estidret for $rowC[1]</b></td>\n";
   echo "               <td rowspan=\"2\" class=\"gray\" align=\"right\" NOWRAP>\n";
   echo "                  <table width=\"100%\" border=$brdr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Salesperson</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowD[1]." ".$rowD[2]."\"></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Sales Manager</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowL[1]." ".$rowL[2]."\"></td>\n";
	echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "                        <td align=\"right\"><b>Peri</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"".$ps1."\">\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>Gal</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$set_gals\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>SA</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$ps2\">\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>IA</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$set_ia\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>S/M/D</b></td>\n";
   echo "	                     <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"$rowpreA[13]\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"$rowpreA[14]\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"$rowpreA[15]\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>E. Run</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['erun']."\">\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>P. Run</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['prun']."\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
	echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Deck</b></td>\n";
   echo "	                     <td colspan=\"3\" align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"4\" maxlength=\"4\" value=\"".$viewarray['deck']."\"> \n";
   
   if ($viewarray['ps1'] > 0)
   {
   	echo " (<b>$incdeck</b> sqft Deck Incl.)";
   }

   echo "                        </td>\n";
   echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Travel</b></td>\n";
	echo "	                     <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"6\" value=\"$rowG[1]\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Status</b></td>\n";
   echo "                        <td colspan=\"3\" class=\"gray\" align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" size=\"8\" value=\"$rowF[2]\">\n";
   echo "	                     </td>\n";
   echo "                     </tr>\n";
   echo "                  </table>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td valign=\"top\" align=\"left\" width=\"400\">\n";
   echo "                  <table width=\"100%\" border=$brdr>\n";
   echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Customer #:</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"><b>".$rowI[0]."</b></td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\" width=\"80\"><b>Name:</b> </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"cfname\" size=\"15\" maxlength=\"20\" value=\"".$rowI[1]."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"clname\" size=\"29\" maxlength=\"42\" value=\"".$rowI[2]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Site Address:</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"saddr1\" size=\"50\" maxlength=\"42\" value=\"".$rowI[5]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"scity\" size=\"20\" maxlength=\"42\" value=\"".$rowI[6]."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"sstate\" size=\"5\" maxlength=\"42\" value=\"".$rowI[7]."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"szip1\" size=\"10\" maxlength=\"42\" value=\"".$rowI[8]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Phone:</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"chome\" size=\"10\" maxlength=\"42\" value=\"".$rowI[3]."\"> home\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"chome\" size=\"10\" maxlength=\"42\" value=\"".$rowI[9]."\"> cell\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
   echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Twnshp/Cnty</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"25\" maxlength=\"30\" value=\"".$rowK[1]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</form>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" valign=\"top\" align=\"center\">\n";
   echo "         <table width=\"100%\" cellpadding=0 cellspacing=0 bordercolor=\"black\" border=1>\n";
   echo "           <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
   echo "           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "           <input type=\"hidden\" name=\"call\" value=\"remove_acc\">\n";
   echo "           <input type=\"hidden\" name=\"estid\" value=\"".$_SESSION['estid']."\">\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"left\"><b>Category</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"left\" width=\"350\"><b>Item</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Quan.</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Units</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Retail</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Comm</b></td>\n";
   echo "           </tr>\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"left\">Base</td>\n";
   //echo "              <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\"><b>Basic Pool</b><br>Includes: <font size=\"6\">$rowM[1]</font></td>\n";
	echo "              <td NOWRAP class=\"lg\" valign=\"top\" align=\"left\"><b>Basic Pool</b></td>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">$bquan</td>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">pft</td>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">$fpbaseprice</td>\n";
   echo "              <td NOWRAP class=\"lg\" valign=\"bottom\" align=\"right\">$fbcomm</td>\n";
   echo "           </tr>\n";
	
	//base_inclusion_ptr();

   calcbyacc_ptr($rowpreD[0]);

   // Totals Table Calcs
   $bccost  =$bctotal;
   $rccost  =$rctotal;
   $cccost  =$cctotal;
   $bmcost  =$bmtotal;
   $rmcost  =$rmtotal;
   $trccost =$rccost+$rmcost;
   $cmcost  =$cmtotal;
   $tbcost  =$bccost+$bmcost;
   $trcost  =$pbaseprice+$trccost;
   $tccost  =$cccost+$cmcost;
   $trcomm  =$bcomm+$tccost;
   $prof    =($trcost-$tbcost)-$trcomm;
   $perprof =$prof/$trcost;

   if ($rowC[2]==1)
	{
	   $rtax    =$ctramt*$taxrate[1];
	   $grtcost =$ctramt+$rtax;
	   $frtax   =number_format($rtax, 2, '.', '');
      $fgrtcost=number_format($grtcost, 2, '.', '');
	}

   $fbccost    =number_format($bccost, 2, '.', '');
   $fbmcost    =number_format($bmcost, 2, '.', '');
   $fcccost    =number_format($cccost, 2, '.', '');
   $frccost    =number_format($rccost, 2, '.', '');
   $frmcost    =number_format($rmcost, 2, '.', '');
   $fcmcost    =number_format($cmcost, 2, '.', '');
   $ftbcost    =number_format($tbcost, 2, '.', '');
   $ftrcost    =number_format($trcost, 2, '.', '');
   $ftccost    =number_format($tccost, 2, '.', '');
   $ftrcomm    =number_format($trcomm, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Pool Price per Book:</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">$ftrcost</td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">$ftrcomm</td>\n";
   echo "           </tr>\n";

   calc_adjusts_ptr($rowpreA[0]);

   $adjbookamt	 =$trcost+$discount;
   $fadjbookamt =number_format($adjbookamt, 2, '.', '');

   $adjctramt	 =$ctramt-$adjbookamt;
   $fadjctramt	 =number_format($adjctramt, 2, '.', '');
   $ndisccomm	 =$ctramt-$trcost;      // Questionable
   //$adjcomm     =$ndisccomm*$rowC[7];  //
   $adjcomm     =$adjctramt*$rowC[7];
   $fadjcomm	 =number_format($adjcomm, 2, '.', '');
   
   if ($tbullets >= $rowC[6]) // Bullet Adjustment
   {
      $tadjcomm    =$trcomm+$adjcomm+$rowC[5];
   }
   else
   {
   	$tadjcomm    =$trcomm+$adjcomm;
   }
   
   $ftadjcomm	 =number_format($tadjcomm, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Adjusted Book Price:</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">$fadjbookamt</td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "           </tr>\n";
   echo "</form>\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Actual Contract Price:</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">$fctramt</td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "           </tr>\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Overage/Underage:</b></td>\n";
   
   if ($adjctramt < 0)
   {
      echo "              <td NOWRAP class=\"wh\" align=\"right\"><font color=\"red\">$fadjctramt</font></td>\n";
   }
   else
   {
   	echo "              <td NOWRAP class=\"wh\" align=\"right\">$fadjctramt</td>\n";
   }
   
   echo "              <td NOWRAP class=\"wh\" align=\"right\">$fadjcomm</td>\n";
   echo "           </tr>\n";
   
   if ($tbullets >= $rowC[6])
   {
      echo "           <tr>\n";
      echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Bullet ($tbullets) Commission:</b></td>\n";
      echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
      echo "              <td NOWRAP class=\"wh\" align=\"right\">$rowC[5]</td>\n";
      echo "           </tr>\n";
   }
   
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"4\" class=\"wh\" align=\"right\"><b>Total Commission:</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\">$ftadjcomm</td>\n";
   echo "           </tr>\n";
   
   if ($rowC[2]==1)
   {
      echo "            <tr>\n";
      echo "               <td colspan=\"4\" class=\"wh\" align=\"right\"><b>Tax ($taxrate[1]):</b></td>\n";
      echo "               <td align=\"right\" class=\"wh\" width=\"60\">\$$frtax</td>\n";
      echo "               <td class=\"wh\" align=\"right\"></td>\n";
      //echo "               <td class=\"lg\" align=\"right\"></td>\n";
      echo "            </tr>\n";
      echo "            <tr>\n";
      echo "               <td colspan=\"4\" class=\"wh\" align=\"right\"><b>Total:</b></td>\n";
      echo "               <td align=\"right\" class=\"wh\" width=\"60\">\$$fgrtcost</td>\n";
      echo "               <td class=\"wh\" align=\"right\"></td>\n";
      //echo "               <td class=\"lg\" align=\"right\"></td>\n";
      echo "            </tr>\n";
   }
   
   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</table>\n";
}

function viewest_cost($estid)
{
   global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate;

   $securityid =$_SESSION['securityid'];
   $officeid   =$_SESSION['officeid'];
   $fname      =$_SESSION['fname'];
   $lname      =$_SESSION['lname'];

   if (!isset($estid)||$estid=='')
   {
      echo "Fatal Error: var estid not set!";
      exit;
   }

	//show_array_vars($_POST);

   $qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,deck1,erun,prun FROM est WHERE officeid='".$officeid."' AND estid='".$estid."';";
   $respreA = mssql_query($qrypreA);
   $rowpreA = mssql_fetch_row($respreA);
   
   $jsecurityid =$rowpreA[17];

	/*
   $qrypreB = "SELECT estdata FROM est_labor_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreB = mssql_query($qrypreB);
   $rowpreB = mssql_fetch_row($respreB);

   $qrypreC = "SELECT estdata FROM est_inv_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreC = mssql_query($qrypreC);
   $rowpreC = mssql_fetch_row($respreC);
   */

   $qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreD = mssql_query($qrypreD);
   $rowpreD = mssql_fetch_row($respreD);

   $ps1        =$rowpreA[1];
   $ps2        =$rowpreA[2];
   $spa1       =$rowpreA[3];
   $spa2       =$rowpreA[4];
   $spa3       =$rowpreA[5];
   $tzone      =$rowpreA[6];
   $contractamt=$rowpreA[7];
   $cfname     =$rowpreA[8];
   $clname     =$rowpreA[9];
   $phone      =$rowpreA[10];
   $status     =$rowpreA[11];
   $ps5        =$rowpreA[13];
   $ps6        =$rowpreA[14];
   $ps7        =$rowpreA[15];

   $viewarray=array(
                    'ps1'=>$rowpreA[1],
                    'ps2'=>$rowpreA[2],
                    'spa1'=>$rowpreA[3],
                    'spa2'=>$rowpreA[4],
                    'spa3'=>$rowpreA[5],
                    'tzone'=>$rowpreA[6],
                    'discount'=>$rowpreA[7],
                    'cfname'=>$rowpreA[8],
                    'clname'=>$rowpreA[9],
                    'phone'=>$rowpreA[10],
                    //'estLdata'=>$rowpreB[0],
                    //'estMdata'=>$rowpreC[0],
                    'status'=>$rowpreA[11],
                    'ps5'=>$rowpreA[13],
                    'ps6'=>$rowpreA[14],
                    'ps7'=>$rowpreA[15],
                    'deck'=>$rowpreA[18],
						  'erun'=>$rowpreA[19],
						  'prun'=>$rowpreA[20]
                    );

   if (isset($_POST['acctotal'])||$_POST['acctotal']!=0)
   {
   	$acctotal=$_POST['acctotal'];
   }
   else
   {
   	$acctotal=0;
   }

   $qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
   $resA = mssql_query($qryA);

   $qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='$ps1';";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_row($resB);

   $qryC = "SELECT officeid,name,stax,sm,gm FROM offices WHERE officeid='".$officeid."';";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_row($resC);

   $qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$jsecurityid."';";
   $resD = mssql_query($qryD);
   $rowD = mssql_fetch_row($resD);

   $qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
   $resE = mssql_query($qryE);

   $qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
   $resF = mssql_query($qryF);

   $qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC;";
   $resG = mssql_query($qryG);

	/*
   $qryH  = "SELECT estid,estaddid FROM est_addendum WHERE estid='".$estidret."' AND estaddid=1;";
   $resH  = mssql_query($qryH);
   $nrowsH= mssql_num_rows($resH);
   */

   $qryI = "SELECT * FROM cinfo WHERE custid='".$rowpreA[16]."';";
   $resI = mssql_query($qryI);
   $rowI = mssql_fetch_array($resI);
   
   $qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowC[3]."';";
   $resL = mssql_query($qryL);
   $rowL = mssql_fetch_row($resL);

   // Sets Tax Rate
   if ($rowC[2]==1)
   {
      $qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
      $resJ = mssql_query($qryJ);
      $rowJ = mssql_fetch_row($resJ);

      $taxrate=array(0=>$rowI[4],1=>$rowJ[0]);

      //echo "City: ".$taxrate[0]."<br>Tax Rate: ".$taxrate[1];
   }

   $set_ia		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$set_gals	=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
   $estidret   =$rowpreA[0];
   $vdiscnt    =$viewarray['discount'];
   $pbaseprice =$rowB[2];
   $bcomm      =$rowB[3];
   $fpbaseprice=number_format($pbaseprice, 2, '.', '');
   $brdr=0;
   
	//show_post_vars();
   
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"$estidret\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
   echo "<input type=\"hidden\" name=\"discount\" value=\"$vdiscnt\">\n";
   echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
   echo "<table align=\"center\" width=\"700px\" border=$brdr>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" valign=\"top\" align=\"left\">\n";
   echo "         <table align=\"center\" width=\"100%\" border=$brdr>\n";
   echo "            <tr>\n";
   echo "               <td class=\"gray\" align=\"left\" valign=\"top\" NOWRAP><b>Cost Estimate #$estidret for $rowC[1]</b></td>\n";
   echo "               <td rowspan=\"2\" class=\"gray\" align=\"right\" NOWRAP>\n";
   echo "                  <table width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Salesperson</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowD[1]." ".$rowD[2]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Sales Manager</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowL[1]." ".$rowL[2]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "                        <td align=\"right\"><b>Peri</b></td>\n";
   echo "                        <td align=\"left\">\n";
	echo "                           <select name=\"ps1\">\n";

   while($rowA = mssql_fetch_row($resA))
   {
      if ($rowA[1]==$ps1)
      {
         echo "                           <option value=\"$rowA[1]\" SELECTED>$rowA[1]</option>\n";
      }
      else
      {
         echo "                           <option value=\"$rowA[1]\">$rowA[1]</option>\n";
      }
   }

   echo "                           </select>\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>Gal</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$set_gals\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>SA</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$ps2\">\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>IA</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$set_ia\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>S/M/D</b></td>\n";
   echo "	                     <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"$rowpreA[13]\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"$rowpreA[14]\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"$rowpreA[15]\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>E. Run</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['erun']."\">\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>P. Run</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['prun']."\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
	echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Deck</b></td>\n";
   echo "	                     <td colspan=\"3\" align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"4\" maxlength=\"4\" value=\"".$viewarray['deck']."\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Travel</b></td>\n";
	echo "	                     <td colspan=\"3\" align=\"left\">\n";
   echo "                           <select name=\"tzone\">\n";

   while ($rowG = mssql_fetch_row($resG))
   {
      if ($rowG[0]==$tzone)
      {
         echo "                           <option value=\"$rowG[0]\" SELECTED>$rowG[1]</option>\n";
      }
      else
      {
         echo "                           <option value=\"$rowG[0]\">$rowG[1]</option>\n";
      }
   }

   echo "                           </select>\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Status</b></td>\n";
   echo "                        <td colspan=\"3\" class=\"gray\" align=\"left\">\n";

   if ($status >= 2)
   {
      echo "                        <select name=\"status\" DISABLED>\n";
   }
   else
   {
      echo "                        <select name=\"status\">\n";
   }

   while($rowF = mssql_fetch_row($resF))
   {
      if ($rowF[0]==$status)
      {
         echo "                           <option value=\"$rowF[0]\" SELECTED>$rowF[2]</option>\n";
      }
      elseif ($rowF[0]==2)
      {
      }
      else
      {
         echo "                           <option value=\"$rowF[0]\">$rowF[2]</option>\n";
      }
   }

   echo "                        </select>\n";
   echo "	                     </td>\n";
   echo "                     </tr>\n";
   echo "                  </table>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td valign=\"top\" align=\"left\" width=\"400\">\n";
   echo "                  <table width=\"100%\" border=$brdr>\n";
   echo "                     <tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Customer #:</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"><b>".$rowI['custid']."</b></td>\n";
	echo "                     </tr>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\" width=\"80\"><b>Name:</b> </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"cfname\" size=\"15\" maxlength=\"20\" value=\"".$rowI['cfname']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"clname\" size=\"29\" maxlength=\"42\" value=\"".$rowI['clname']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Site:</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"saddr1\" size=\"50\" maxlength=\"42\" value=\"".$rowI['saddr1']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"scity\" size=\"20\" maxlength=\"42\" value=\"".$rowI['scity']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"sstate\" size=\"5\" maxlength=\"42\" value=\"".$rowI['sstate']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"szip1\" size=\"10\" maxlength=\"42\" value=\"".$rowI['szip1']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Phone:</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"chome\" size=\"15\" maxlength=\"42\" value=\"".$rowI['chome']."\"> home\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ccell\" size=\"15\" maxlength=\"42\" value=\"".$rowI['ccell']."\"> cell\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "      <td valign=\"bottom\" align=\"right\"> \n";
   echo "         <input class=\"buttondkgrypnl\" type=\"submit\" value=\"View Retail\">\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</form>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" valign=\"top\" align=\"left\">\n";
   echo "         <table width=\"625\" bordercolor=\"black\" border=1>\n";

   calcbyphsL($rowpreD[0]);

   $bccost  =$bctotal;
   $fbccost =number_format($bccost, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fbccost."</b></td>\n";
   echo "           </tr>\n";
   echo "         </table>\n";
   //echo "         <div class=\"pagebreak\">\n";
	echo "         <hr width=\"100%\">\n";
   echo "         <table width=\"625\" bordercolor=\"black\" border=1>\n";

   calcbyphsM($rowpreD[0]);
   
   $bmcost  =$bmtotal;
   $fbmcost =number_format($bmcost, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fbmcost."</b></td>\n";
   echo "           </tr>\n";
   echo "         </table>\n";
   //echo "         </div>\n";
   //echo "         <div class=\"pagebreak\">\n";
	echo "         <hr width=\"100%\">\n";
   // Total Table
   echo "         <table width=\"625\" bordercolor=\"black\" border=1>\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"left\"><b>Totals</b></td>\n";
   echo "           </tr>\n";
   
   $tcontract  =$_POST['tcontract'];
   $ftcontract =number_format($tcontract, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Contract Price</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftcontract."</b></td>\n";
   echo "           </tr>\n";

   $tretail  =$_POST['tretail'];
   $ftretail =number_format($tretail, 2, '.', '');

   //echo "           <tr>\n";
   //echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Price per Book</b></td>\n";
   //echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftretail."</b></td>\n";
   //echo "           </tr>\n";
   
   $tbcost  =$bccost+$bmcost;
   $ftbcost =number_format($tbcost, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Construction Total</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftbcost."</b></td>\n";
   echo "           </tr>\n";

   $tcomm  =$_POST['tcomm'];
   $ftcomm =number_format($tcomm, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Commission</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftcomm."</b></td>\n";
   echo "           </tr>\n";
   
   $tgross  =$tbcost+$_POST['tcomm'];
   $ftgross =number_format($tgross, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Total Cost</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftgross."</b></td>\n";
   echo "           </tr>\n";

   $tprofit  =$tcontract-$tgross;
   $ftprofit =number_format($tprofit, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Net</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftprofit."</b></td>\n";
   echo "           </tr>\n";

   $netper  =$tprofit/$tgross;
   $fnetper =round($netper, 2);

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Net %</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fnetper."</b></td>\n";
   echo "           </tr>\n";
   echo "         </table>\n";
   //echo "         </div>\n";
   echo "      </td>\n";
   echo "      <td valign=\"top\">\n";
   echo "         <table width=\"80px\" cellpadding=0 cellspacing=0 bordercolor=\"black\" border=0>\n";
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"view_cost_print\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"$estidret\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
   echo "<input type=\"hidden\" name=\"tcomm\" value=\"".$_POST['tcomm']."\">\n";
   echo "<input type=\"hidden\" name=\"tretail\" value=\"".$_POST['tretail']."\">\n";
   echo "<input type=\"hidden\" name=\"tcontract\" value=\"".$_POST['tcontract']."\">\n";
   echo "<input type=\"hidden\" name=\"discount\" value=\"$vdiscnt\">\n";
   echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
   echo "<input type=\"hidden\" name=\"acctotal\" value=\"".$_POST['acctotal']."\">\n";
   echo "            <tr>\n";
   echo "               <td align=\"right\">\n";
   echo "                  <input class=\"buttondkgrypnl\" type=\"submit\" value=\"Print View\">\n";
   echo "               </td>\n";
   echo "</form>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</table>\n";
}

function viewest_cost_print($estid)
{
   global $bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$viewarray,$invarray,$estidret,$taxrate;

   $securityid =$_SESSION['securityid'];
   $officeid   =$_SESSION['officeid'];
   $fname      =$_SESSION['fname'];
   $lname      =$_SESSION['lname'];

   if (!isset($estid)||$estid=='')
   {
      echo "Fatal Error: var estid not set!";
      exit;
   }
   
   //echo "<pre>";
	//print_r($_POST);
	//echo "</pre>";

   $qrypreA = "SELECT estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,contractamt,cfname,clname,phone,status,comments,shal,mid,deep,cid,securityid,erun,prun FROM est WHERE officeid='".$officeid."' AND estid='".$estid."';";
   $respreA = mssql_query($qrypreA);
   $rowpreA = mssql_fetch_row($respreA);
   
   $jsecurityid =$rowpreA[17];
/*
   $qrypreB = "SELECT estdata FROM est_labor_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreB = mssql_query($qrypreB);
   $rowpreB = mssql_fetch_row($respreB);

   $qrypreC = "SELECT estdata FROM est_inv_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreC = mssql_query($qrypreC);
   $rowpreC = mssql_fetch_row($respreC);
*/
   $qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid='".$officeid."' AND estid='".$rowpreA[0]."';";
   $respreD = mssql_query($qrypreD);
   $rowpreD = mssql_fetch_row($respreD);

   $ps1        =$rowpreA[1];
   $ps2        =$rowpreA[2];
   $spa1       =$rowpreA[3];
   $spa2       =$rowpreA[4];
   $spa3       =$rowpreA[5];
   $tzone      =$rowpreA[6];
   $contractamt=$rowpreA[7];
   $cfname     =$rowpreA[8];
   $clname     =$rowpreA[9];
   $phone      =$rowpreA[10];
   $status     =$rowpreA[11];
   $ps5        =$rowpreA[13];
   $ps6        =$rowpreA[14];
   $ps7        =$rowpreA[15];

   $viewarray=array(
                    'ps1'=>$rowpreA[1],
                    'ps2'=>$rowpreA[2],
                    'spa1'=>$rowpreA[3],
                    'spa2'=>$rowpreA[4],
                    'spa3'=>$rowpreA[5],
                    'tzone'=>$rowpreA[6],
                    'discount'=>$rowpreA[7],
                    'cfname'=>$rowpreA[8],
                    'clname'=>$rowpreA[9],
                    'phone'=>$rowpreA[10],
                    //'estLdata'=>$rowpreB[0],
                    //'estMdata'=>$rowpreC[0],
                    'status'=>$rowpreA[11],
                    'ps5'=>$rowpreA[13],
                    'ps6'=>$rowpreA[14],
                    'ps7'=>$rowpreA[15],
						  'erun'=>$rowpreA[18],
						  'prun'=>$rowpreA[19]
                    );

   if (isset($_POST['acctotal'])||$_POST['acctotal']!=0)
   {
   	$acctotal=$_POST['acctotal'];
   }
   else
   {
   	$acctotal=0;
   }

   $qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
   $resA = mssql_query($qryA);

   $qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid='".$officeid."' AND quan='$ps1';";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_row($resB);

   $qryC = "SELECT officeid,name,stax,sm,gm FROM offices WHERE officeid='".$officeid."';";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_row($resC);

   $qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$jsecurityid."';";
   $resD = mssql_query($qryD);
   $rowD = mssql_fetch_row($resD);

   $qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
   $resE = mssql_query($qryE);

   $qryF = "SELECT snum,cat,description FROM status_codes WHERE cat='est' AND snum <= 2;";
   $resF = mssql_query($qryF);

   $qryG = "SELECT zid,name FROM zoneinfo WHERE officeid='".$officeid."' ORDER BY zid ASC;";
   $resG = mssql_query($qryG);

   $qryH  = "SELECT estid,estaddid FROM est_addendum WHERE estid='".$estidret."' AND estaddid=1;";
   $resH  = mssql_query($qryH);
   $nrowsH= mssql_num_rows($resH);

   $qryI = "SELECT * FROM cinfo WHERE custid='".$rowpreA[16]."';";
   $resI = mssql_query($qryI);
   $rowI = mssql_fetch_array($resI);

   $qryL = "SELECT securityid,fname,lname FROM security WHERE securityid='".$rowC[3]."';";
   $resL = mssql_query($qryL);
   $rowL = mssql_fetch_row($resL);

   // Sets Tax Rate
   if ($rowC[2]==1)
   {
      $qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
      $resJ = mssql_query($qryJ);
      $rowJ = mssql_fetch_row($resJ);

      $taxrate=array(0=>$rowI[4],1=>$rowJ[0]);

      //echo "City: ".$taxrate[0]."<br>Tax Rate: ".$taxrate[1];
   }

   $set_ia		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$set_gals	=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
   $estidret   =$rowpreA[0];
   $vdiscnt    =$viewarray['discount'];
   $pbaseprice =$rowB[2];
   $bcomm      =$rowB[3];
   $fpbaseprice=number_format($pbaseprice, 2, '.', '');
   $brdr=0;

   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"est\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
   echo "<input type=\"hidden\" name=\"estid\" value=\"$estidret\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
   echo "<input type=\"hidden\" name=\"securityid\" value=\"$securityid\">\n";
   echo "<input type=\"hidden\" name=\"discount\" value=\"$vdiscnt\">\n";
   echo "<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
   echo "<table align=\"center\" width=\"700px\" border=$brdr>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" valign=\"top\" align=\"left\">\n";
   echo "         <table align=\"center\" width=\"100%\" border=$brdr>\n";
   echo "            <tr>\n";
   echo "               <td class=\"gray\" align=\"left\" valign=\"top\" NOWRAP><b>Cost Estimate #$estidret for $rowC[1]</b></td>\n";
   echo "               <td rowspan=\"2\" class=\"gray\" align=\"right\" NOWRAP>\n";
   echo "                  <table width=\"100%\" border=0>\n";
	echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Salesperson</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowD[1]." ".$rowD[2]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\" NO WRAP><b>Sales Man.</b></td>\n";
	echo "                        <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$rowL[1]." ".$rowL[2]."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "                        <td align=\"right\"><b>Peri</b></td>\n";
   echo "                        <td align=\"left\">\n";
	echo "                           <select name=\"ps1\">\n";

   while($rowA = mssql_fetch_row($resA))
   {
      if ($rowA[1]==$ps1)
      {
         echo "                           <option value=\"$rowA[1]\" SELECTED>$rowA[1]</option>\n";
      }
      else
      {
         echo "                           <option value=\"$rowA[1]\">$rowA[1]</option>\n";
      }
   }

   echo "                           </select>\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>Gal</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$set_gals\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>SA</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$ps2\">\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>IA</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"$set_ia\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>S/M/D</b></td>\n";
   echo "	                     <td colspan=\"3\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps5\" size=\"1\" maxlength=\"3\" value=\"$rowpreA[13]\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps6\" size=\"1\" maxlength=\"3\" value=\"$rowpreA[14]\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ps7\" size=\"1\" maxlength=\"3\" value=\"$rowpreA[15]\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>E. Run</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"erun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['erun']."\">\n";
   echo "                        </td>\n";
   echo "	                     <td align=\"right\"><b>P. Run</b></td>\n";
   echo "                        <td align=\"left\">\n";
   echo "                           <input class=\"bboxl\" type=\"text\" name=\"prun\" size=\"5\" maxlength=\"5\" value=\"".$viewarray['prun']."\">\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "	                     <td align=\"right\"><b>Travel</b></td>\n";
	echo "	                     <td colspan=\"3\" align=\"left\">\n";
   echo "                           <select name=\"tzone\">\n";

   while ($rowG = mssql_fetch_row($resG))
   {
      if ($rowG[0]==$tzone)
      {
         echo "                           <option value=\"$rowG[0]\" SELECTED>$rowG[1]</option>\n";
      }
      else
      {
         echo "                           <option value=\"$rowG[0]\">$rowG[1]</option>\n";
      }
   }

   echo "                           </select>\n";
   echo "                        </td>\n";
   echo "                     </tr>\n";
   echo "                     <tr>\n";
   echo "	                     <td align=\"right\"><b>Status</b></td>\n";
   echo "                        <td colspan=\"3\" class=\"gray\" align=\"left\">\n";

   if ($status >= 2)
   {
      echo "                        <select name=\"status\" DISABLED>\n";
   }
   else
   {
      echo "                        <select name=\"status\">\n";
   }

   while($rowF = mssql_fetch_row($resF))
   {
      if ($rowF[0]==$status)
      {
         echo "                           <option value=\"$rowF[0]\" SELECTED>$rowF[2]</option>\n";
      }
      elseif ($rowF[0]==2)
      {
      }
      else
      {
         echo "                           <option value=\"$rowF[0]\">$rowF[2]</option>\n";
      }
   }

   echo "                        </select>\n";
   echo "	                     </td>\n";
   echo "                     </tr>\n";
   echo "                  </table>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td valign=\"top\" align=\"left\" width=\"100%\">\n";
   echo "                  <table width=\"100%\" border=$brdr>\n";
   echo "                     <tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Customer #:</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\"><b>".$rowI['custid']."</b></td>\n";
	echo "                     </tr>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\" width=\"80\"><b>Name:</b> </td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"cfname\" size=\"15\" maxlength=\"20\" value=\"".$rowI['cfname']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"clname\" size=\"29\" maxlength=\"42\" value=\"".$rowI['clname']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Site:</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"saddr1\" size=\"50\" maxlength=\"42\" value=\"".$rowI['saddr1']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"scity\" size=\"20\" maxlength=\"42\" value=\"".$rowI['scity']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"sstate\" size=\"5\" maxlength=\"42\" value=\"".$rowI['sstate']."\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"szip1\" size=\"10\" maxlength=\"42\" value=\"".$rowI['szip1']."\">\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td valign=\"bottom\" align=\"right\"><b>Phone:</b></td>\n";
	echo "                        <td valign=\"bottom\" align=\"left\">\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"chome\" size=\"15\" maxlength=\"42\" value=\"".$rowI['chome']."\"> home\n";
	echo "                           <input class=\"bboxl\" type=\"text\" name=\"ccell\" size=\"15\" maxlength=\"42\" value=\"".$rowI['ccell']."\"> cell\n";
	echo "                        </td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   /*
   echo "      <td valign=\"bottom\" align=\"right\"> \n";
   echo "         <input class=\"buttondkgrypnl\" type=\"submit\" value=\"View Retail\">\n";
   echo "      </td>\n";
   */
   echo "   </tr>\n";
   echo "</form>\n";
   echo "   <tr>\n";
   echo "      <td class=\"gray\" valign=\"top\" align=\"left\">\n";
   echo "         <table width=\"100%\" bordercolor=\"black\" border=1>\n";

   calcbyphsL($rowpreD[0]);

   $bccost  =$bctotal;
   $fbccost =number_format($bccost, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fbccost."</b></td>\n";
   echo "           </tr>\n";
   echo "         </table>\n";
   echo "         <div class=\"pagebreak\">\n";
	echo "         <hr width=\"100%\">\n";
   echo "         <table width=\"100%\" bordercolor=\"black\" border=1>\n";

   calcbyphsM($rowpreD[0]);

   $bmcost  =$bmtotal;
   $fbmcost =number_format($bmcost, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"3\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fbmcost."</b></td>\n";
   echo "           </tr>\n";
   echo "         </table>\n";
   echo "         </div>\n";
   echo "         <div class=\"pagebreak\">\n";
	echo "         <hr width=\"100%\">\n";
   // Total Table
   echo "         <table width=\"100%\" bordercolor=\"black\" border=1>\n";
   echo "           <tr>\n";
   echo "              <td NOWRAP colspan=\"2\" class=\"wh\" align=\"left\"><b>Totals</b></td>\n";
   echo "           </tr>\n";


   $tcontract  =$_POST['tcontract'];
   $ftcontract =number_format($tcontract, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Contract Price</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftcontract."</b></td>\n";
   echo "           </tr>\n";

   $tretail  =$_POST['tretail'];
   $ftretail =number_format($tretail, 2, '.', '');

   //echo "           <tr>\n";
   //echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Price per Book</b></td>\n";
   //echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftretail."</b></td>\n";
   //echo "           </tr>\n";

   $tbcost  =$bccost+$bmcost;
   $ftbcost =number_format($tbcost, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>Construction Total</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftbcost."</b></td>\n";
   echo "           </tr>\n";

   $tcomm  =$_POST['tcomm'];
   $ftcomm =number_format($tcomm, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Commission</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftcomm."</b></td>\n";
   echo "           </tr>\n";

   $tgross  =$tbcost+$_POST['tcomm'];
   $ftgross =number_format($tgross, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Total Cost</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftgross."</b></td>\n";
   echo "           </tr>\n";

   $tprofit  =$tcontract-$tgross;
   $ftprofit =number_format($tprofit, 2, '.', '');

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Net</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$ftprofit."</b></td>\n";
   echo "           </tr>\n";

   $netper  =$tprofit/$tgross;
   $fnetper =round($netper, 2);

   echo "           <tr>\n";
   echo "              <td NOWRAP width=\"490\" class=\"wh\" align=\"right\"><b>Net %</b></td>\n";
   echo "              <td NOWRAP class=\"wh\" align=\"right\"><b>".$fnetper."</b></td>\n";
   echo "           </tr>\n";
   echo "         </table>\n";
   echo "         </div>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</table>\n";
}

1;
?>
