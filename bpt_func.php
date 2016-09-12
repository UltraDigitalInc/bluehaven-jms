<?php

function delete_base_meas()
{
   $qry = "DELETE FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' AND id='".$_POST['id']."';";
   $res = mssql_query($qry);
   
   //echo $qry;

	showbpt();
}

function update_base_meas()
{
   $qry = "UPDATE offices SET pft_sqft='".$_POST['defmeas']."' WHERE officeid='".$_SESSION['officeid']."';";
   $res = mssql_query($qry);

   showbpt();
}

function base_meas_config()
{
   $qry = "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
   $res = mssql_query($qry);
   $row = mssql_fetch_array($res);

	//echo "Costing:\n";
   echo "      <table align=\"right\" width=\"100%\">\n";
   echo "         <tr>\n";
   echo "            <td align=\"center\"><b>Default Pool Measurement</b></td>\n";
   echo "         </tr>\n";
   echo "         <tr>\n";
   echo "            <td NOWRAP valign=\"top\" align=\"center\">\n";
   echo "               <table width=\"100%\">\n";
   echo "                  <tr>\n";
   echo "                     <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "                     <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
   echo "                     <input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
   echo "                     <input type=\"hidden\" name=\"subq\" value=\"defmeas\">\n";
   echo "                     <td align=\"right\">\n";
   echo "                        <select name=\"defmeas\">\n";
   
   if ($row['pft_sqft']=="p")
   {
   	echo "                        	<option value=\"p\">Perimeter Foot</option>\n";
   	echo "                        	<option value=\"s\">Surface Area</option>\n";
   }
   else 
   {
   	echo "                        	<option value=\"s\">Surface Area</option>\n";
   	echo "                        	<option value=\"p\">Perimeter Foot</option>\n";
   }
   
   echo "                        </select>\n";
   echo "                     </td>\n";
   echo "      					<td align=\"left\">\n";
   echo "								<input class=\"buttondkgry\" type=\"submit\" value=\"Change\">\n";
   echo "      					</td>\n";
   echo "                     </form>\n";
   echo "                  </tr>\n";
   echo "               </table>\n";
   echo "            </td>\n";
   echo "         </tr>\n";
   echo "      </table>\n";
}

function updatebp_perc_form()
{
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
   echo "<input type=\"hidden\" name=\"subq\" value=\"updatebp_perc\">\n";
   echo "<table width=\"100%\">\n";
   echo "   <tr>\n";
   echo "      <th valign=\"top\" align=\"left\">Base Price Increase:</th>\n";
	echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td valign=\"top\" align=\"right\"> by Percentage (+/-)\n";
	echo "			<input type=\"text\" name=\"upflt\" size=\"5\">\n";
   echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
   echo "      <td align=\"right\">\n";
   echo "         <input class=\"buttondkgry\" type=\"submit\" value=\"Update\">\n";
   echo "      </td>\n";
   echo "   </tr>\n";
	echo "</table>\n";
	echo "</form>\n";
   
   /*
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
   echo "<input type=\"hidden\" name=\"subq\" value=\"updatebpcm_perc\">\n";
   echo "<table width=\"100%\">\n";
   echo "   <tr>\n";
   echo "      <th valign=\"top\" align=\"left\">BP Commission Increase:</th>\n";
	echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td valign=\"top\" align=\"right\"> by Percentage (+/-)\n";
	echo "			<input type=\"text\" name=\"upflt\" size=\"5\">\n";
   echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
   echo "      <td align=\"right\">\n";
   echo "         <input class=\"buttondkgry\" type=\"submit\" value=\"Update\">\n";
   echo "      </td>\n";
   echo "   </tr>\n";
	echo "</table>\n";
	echo "</form>\n";
   */
}

function update_base_descrip()
{
   $qry = "UPDATE base_description SET descrip='".$_POST['descrip']."' WHERE officeid='".$_SESSION['officeid']."';";
   $res = mssql_query($qry);

   showbpt();
}

function base_pool_calc_settings()
{
	if (empty($officeid))
	{
      $officeid=$_SESSION['officeid'];
   }
   $securityid=$_SESSION['securityid'];

   $qry = "SELECT * FROM offices WHERE officeid=$officeid";
   $res = mssql_query($qry);
   $row = mssql_fetch_array($res);

   $qryA = "SELECT securityid,lname,fname FROM security WHERE officeid=$officeid";
   $resA = mssql_query($qryA);
   //$rowA = mssql_fetch_array($resA);

   $qryD = "SELECT securityid,lname,fname FROM security WHERE officeid=$officeid";
   $resD = mssql_query($qryD);

   $qryB = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['gm']."';";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_array($resB);

   $qryC = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row['sm']."';";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_array($resC);

   $qryE = "SELECT * FROM base_calc_types ORDER BY name ASC;";
   $resE = mssql_query($qryE);
   $qryF = "SELECT * FROM base_calc_types ORDER BY name ASC;";
   $resF = mssql_query($qryF);
   $qryG = "SELECT * FROM base_calc_types ORDER BY name ASC;";
   $resG = mssql_query($qryG);
   $qryH = "SELECT * FROM base_calc_types ORDER BY name ASC;";
   $resH = mssql_query($qryH);
   $qryI = "SELECT * FROM base_calc_types ORDER BY name ASC;";
   $resI = mssql_query($qryI);
   $qryJ = "SELECT * FROM base_calc_types ORDER BY name ASC;";
   $resJ = mssql_query($qryJ);

   if ($_SESSION['tlev'] > 4)
   {
      echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
      echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
      echo "<input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
      echo "<input type=\"hidden\" name=\"subq\" value=\"update_base_pool_calcs\">\n";
      echo "<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
   }
   
   echo "<table width=\"100%\">\n";
   echo "   <tr>\n";
   echo "      <td valign=\"top\" align=\"left\">\n";
   echo "         <table align=\"center\" width=\"100%\" border=0>\n";
   echo "            <tr>\n";
   echo "               <th colspan=\"2\"><b>Pool Base Calculation Settings</b></th>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td align=\"right\"><b>Default Perimeter Foot:</b></td>\n";
   echo "               <td><input class=\"bboxl\" type=\"text\" name=\"def_per\" value=\"".$row['def_per']."\" size=\"5\"></td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td align=\"right\"><b>Default Square Foot:</b></td>\n";
   echo "               <td><input class=\"bboxl\" type=\"text\" name=\"def_sqft\" value=\"".$row['def_sqft']."\" size=\"5\"></td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td align=\"right\"><b>Default Shallow Depth:</b></td>\n";
   echo "               <td><input class=\"bboxl\" type=\"text\" name=\"def_s\" value=\"".$row['def_s']."\" size=\"5\"></td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td align=\"right\"><b>Default Middle Depth:</b></td>\n";
   echo "               <td><input class=\"bboxl\" type=\"text\" name=\"def_m\" value=\"".$row['def_m']."\" size=\"5\"></td>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
   echo "               <td align=\"right\"><b>Default Deep Depth:</b></td>\n";
   echo "               <td><input class=\"bboxl\" type=\"text\" name=\"def_d\" value=\"".$row['def_d']."\" size=\"5\"></td>\n";
   echo "            </tr>\n";
   
   if ($_SESSION['tlev'] > 4)
   {
      echo "   <tr>\n";
      echo "      <td colspan=\"2\" align=\"right\"><input class=\"buttondkgry\" type=\"submit\" value=\"Update\"></td>\n";
      echo "   </tr>\n";
   }
   
   echo "         </table>\n";
   echo "</form>\n";
}

function addbp()
{
   $officeid=$_SESSION['officeid'];
   $qryA = "SELECT id FROM rbpricep WHERE officeid='$officeid';";
   $resA = mssql_query($qryA);
   $nrowsA = mssql_num_rows($resA);

   $qryB = "SELECT MAX(quan) FROM rbpricep WHERE officeid='$officeid';";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_row($resB);

   if ($nrowsA < 1)
   {
      $upquan = "";
   }
   else
   {
      $upquan = $rowB[0] + 1;
   }

      echo "<table align=\"center\">\n";
      echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
      echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
      echo "<input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
      echo "<input type=\"hidden\" name=\"subq\" value=\"add\">\n";
      echo "<input type=\"hidden\" name=\"officeid\" value=\"$officeid\">\n";
      echo "<tr><td></td><td><b>Add Pricing:</b></td></tr>\n";
      echo "   <tr>\n";
      echo "      <td align=\"right\"><i>Size 1</i></td>\n";
      echo "      <td>\n";
      echo "         <input type=\"text\" name=\"quan\" value=\"$upquan\">\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "   <tr>\n";
      echo "      <td align=\"right\"><i>Size 2</i></td>\n";
      echo "      <td>\n";
      echo "         <input type=\"text\" name=\"quan1\" value=\"0\">\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "   <tr>\n";
      echo "      <td align=\"right\"><i>Price:</i></td>\n";
      echo "      <td>\n";
      echo "         <input type=\"text\" name=\"price\">\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "   <tr>\n";
      echo "      <td><i>Comm:</i></td>\n";
      echo "      <td>\n";
      echo "         <input type=\"text\" name=\"comm\">\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "   <tr>\n";
      echo "      <td colspan=\"2\" align=\"right\">\n";
      echo "         <input class=\"buttondkgry\" type=\"submit\" value=\"Add Base Price\">\n";
      echo "      </td>\n";
      echo "   </tr>\n";
      echo "</form>\n";
      echo "</table>\n";
}

function cost_phase_select()
{
   $qryA = "SELECT phsid,phscode,phsname,phstype FROM phasebase WHERE phstype='V' OR phstype='S' ORDER BY phsname;";
   $resA = mssql_query($qryA);

   $qryB = "SELECT phsid,phscode,phsname,phstype FROM phasebase WHERE phstype='M' ORDER BY phsname;";
   $resB = mssql_query($qryB);

	//echo "Costing:\n";
   echo "      <table align=\"center\" width=\"100%\">\n";
   echo "         <tr>\n";
   echo "            <th NOWRAP colspan=\"2\"><b>Cost Item Maintenance</b></th>\n";
   echo "         </tr>\n";
   echo "         <tr>\n";
   echo "            <td NOWRAP align=\"right\"><b>Labor:</b></td>\n";
   echo "            <td NOWRAP valign=\"top\">\n";
   echo "               <table width=\"100%\">\n";
   echo "                  <tr>\n";
   echo "                     <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "                     <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
   echo "                     <input type=\"hidden\" name=\"call\" value=\"cost\">\n";
   echo "                     <input type=\"hidden\" name=\"subq\" value=\"acc\">\n";
   echo "                     <td NOWRAP>\n";
   echo "                        <select name=\"phsid\" OnChange=\"this.form.submit();\">\n";

   while($rowA = mssql_fetch_row($resA))
   {
      echo "                        <option value=\"$rowA[0]\">$rowA[2]</option>\n";
   }

   echo "                        </select>\n";
   echo "                     </td>\n";
   echo "                     </form>\n";
   echo "                  </tr>\n";
   echo "               </table>\n";
   echo "            </td>\n";
   echo "         </tr>\n";
   echo "         <tr>\n";
   echo "            <td NOWRAP align=\"right\"><b>Material:</b></td>\n";
   echo "            <td NOWRAP valign=\"top\">\n";
   echo "               <table width=\"100%\">\n";
   echo "                  <tr>\n";
   echo "                     <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "                     <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
   echo "                     <input type=\"hidden\" name=\"call\" value=\"inv\">\n";
   echo "                     <input type=\"hidden\" name=\"subq\" value=\"inv\">\n";
   echo "                     <td NOWRAP>\n";
   echo "                        <select name=\"phsid\" OnChange=\"this.form.submit();\">\n";

   while($rowB = mssql_fetch_row($resB))
   {
      echo "                        <option value=\"$rowB[0]\">$rowB[2]</option>\n";
   }

   echo "                        </select>\n";
   echo "                     </td>\n";
   echo "                     </form>\n";
   echo "                  </tr>\n";
   echo "               </table>\n";
   echo "            </td>\n";

/*
   echo "            <td NOWRAP><b>Accessories:</b></td>\n";
   echo "            <td NOWRAP valign=\"top\">\n";
   echo "               <table width=\"100%\">\n";
   echo "                  <tr>\n";
   echo "                     <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "                     <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
   echo "                     <input type=\"hidden\" name=\"call\" value=\"acc\">\n";
   echo "                     <input type=\"hidden\" name=\"subq\" value=\"list\">\n";
   echo "                     <td NOWRAP><input type=\"submit\" name=\"submit\" value=\"submit\"></td>\n";
   echo "                     </form>\n";
   echo "                  </tr>\n";
   echo "               </table>\n";
   echo "            </td>\n";
*/
   echo "         </tr>\n";
   echo "      </table>\n";
}

function listbp()
{
   //show_post_vars();
   $qry = "SELECT officeid,pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
   $res = mssql_query($qry);
   $row = mssql_fetch_array($res);
   
   $qry0 = "SELECT id,officeid,quan,quan1,price,comm FROM rbpricep WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
   $res0 = mssql_query($qry0);

   echo "<table class=\"outer\" align=\"center\" width=\"700px\">\n";
   echo "   <tr>\n";
	echo "      <td class=\"gray\" valign=\"top\" width=\"50%\">\n";
   echo "         <table align=\"center\" border=0>\n";
   echo "            <tr>\n";
	echo "               <th colspan=\"2\" valign=\"bottom\" align=\"center\">\n";
	
	if ($row['pft_sqft']=="p")
	{
   	echo "                  <b>Retail Base Pool Pricing by Perimeter Foot</b>\n";
	}
	else 
	{
		echo "                  <b>Retail Base Pool Pricing by Surface Area</b>\n";
	}
   
   echo "               </th>\n";
   echo "            </tr>\n";
   echo "            <tr>\n";
	echo "               <td class=\"gray\" valign=\"top\" rowspan=\"2\">\n";
   echo "                  <table border=0>\n";
   echo "                     <tr>\n";
   echo "                        <td class=\"gray\" align=\"left\" NOWRAP><i>Size 1</i></td>\n";
   echo "                        <td class=\"gray\" align=\"left\" NOWRAP><i>Size 2</i></td>\n";
   echo "                        <td align=\"left\" NOWRAP><i>Price</i></td>\n";
   echo "                        <td align=\"left\" NOWRAP><i>Comm.</i></td>\n";
   echo "                     </tr>\n";

   $altc="1";	 
   while($row0 = mssql_fetch_array($res0))
   {
      // Flips Cell Colors
      if ($altc%2)
      { 
         $tdc = "wh"; 
         $altc = "2"; 
      } 
      else
      { 
         $tdc = "lg"; 
         $altc = "1"; 
      }
      echo "                     <tr>\n";
      echo "                        <td class=\"$tdc\" align=\"right\" NOWRAP>".$row0['quan']."</td>\n";
      echo "                        <td class=\"$tdc\" align=\"right\" NOWRAP>".$row0['quan1']."</td>\n";
      echo "                        <td class=\"$tdc\" align=\"right\" NOWRAP>".$row0['price']."</td>\n";
      echo "                        <td class=\"$tdc\" align=\"right\" NOWRAP>".$row0['comm']."</td>\n";
      echo "                        <td align=\"left\">\n";
      echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
      echo "                           <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
      echo "                           <input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
      echo "                           <input type=\"hidden\" name=\"subq\" value=\"edit\">\n";
      echo "                           <input type=\"hidden\" name=\"id\" value=\"".$row0['id']."\">\n";
      echo "                           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
      echo "                           <input class=\"buttondkgry\" type=\"submit\" value=\"Edit\">\n";
      echo "                        </form>\n";
      echo "                        </td>\n";
      echo "                        <td align=\"left\">\n";
      echo "                        <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
      echo "                           <input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
      echo "                           <input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
      echo "                           <input type=\"hidden\" name=\"subq\" value=\"delete\">\n";
      echo "                           <input type=\"hidden\" name=\"id\" value=\"".$row0['id']."\">\n";
      echo "                           <input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
      echo "                           <input class=\"buttondkgry\" type=\"submit\" value=\"Delete\">\n";
      echo "                        </form>\n";
      echo "                        </td>\n";
      echo "                     </tr>\n";
   }
   echo "                  </table>\n";
   echo "               </td>\n";
   echo "               <td class=\"gray\" valign=\"top\" align=\"right\">\n";

   addbp();
   updatebp_perc_form();

   echo "               </td>\n";
   echo "            </tr>\n";
   echo "         </table>\n";
   echo "      </td>\n";
   echo "      <td class=\"gray\" align=\"right\" valign=\"top\" width=\"50%\">\n";
   
   base_pool_calc_settings();
   base_meas_config();
   
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</table>\n";
}

function editbp()
{
   $qry = "SELECT id,officeid,quan,quan1,price,comm FROM rbpricep WHERE id='".$_POST['id']."';";
   $res = mssql_query($qry);
   $row = mssql_fetch_array($res);
   
   echo "<table align=\"center\">\n";
   echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "<input type=\"hidden\" name=\"action\" value=\"pbconfig\">\n";
   echo "<input type=\"hidden\" name=\"call\" value=\"bpool\">\n";
   echo "<input type=\"hidden\" name=\"subq\" value=\"update\">\n";
   echo "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">\n";
   echo "<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
   echo "   <tr>\n";
   echo "      <th colspan=\"2\" align=\"left\"><b>Edit Base Price:</b></th>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td>Size 1:</td>\n";
   echo "      <td>\n";
   echo "         <input type=\"text\" name=\"quan\" value=\"".$row['quan']."\">\n";
   //echo "         <b>".$row['quan']."</b><input type=\"hidden\" name=\"quan\" value=\"".$row['quan']."\">\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td>Size 2:</td>\n";
   echo "      <td>\n";
   echo "         <input type=\"text\" name=\"quan1\" value=\"".$row['quan1']."\">\n";
   //echo "         <b>".$row['quan1']."</b><input type=\"hidden\" name=\"quan\" value=\"".$row['quan1']."\">\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td>Price:</td>\n";
   echo "      <td>\n";
   echo "         <input type=\"text\" name=\"price\" value=\"".$row['price']."\">\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td>Commission:</td>\n";
   echo "      <td>\n";
   echo "         <input type=\"text\" name=\"comm\" value=\"".$row['comm']."\">\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "   <tr>\n";
   echo "      <td colspan=\"2\" align=\"right\">\n";
   echo "         <button type=\"submit\">Update Base Price</button>\n";
   echo "      </td>\n";
   echo "   </tr>\n";
   echo "</form>\n";
   echo "</table>\n";
}

function insertbp()
{
   $qryB = "INSERT INTO rbpricep (officeid,quan,quan1,price,comm) VALUES ('".$_SESSION['officeid']."','".$_POST['quan']."','".$_POST['quan1']."',CONVERT(money,'".$_POST['price']."'),'".$_POST['comm']."');";
	$resB = mssql_query($qryB);

	showbpt();
}

function updatebp()
{
   $qry = "UPDATE rbpricep SET quan='".$_POST['quan']."',quan1='".$_POST['quan1']."',price=CONVERT(money,'".$_POST['price']."'),comm='".$_POST['comm']."' WHERE id='".$_POST['id']."';";
   $res = mssql_query($qry);
   
   showbpt();
}

function updatebp_perc()
{
   //echo "IN<br>";
   
	if (isset($_POST['upflt']) && is_numeric($_POST['upflt']))
	{
		$qry = "UPDATE rbpricep SET price=round(((price * ".$_POST['upflt'].") + price),0) WHERE officeid='".$_SESSION['officeid']."' and price!=0;";
		$res = mssql_query($qry);
      
      //echo $qry."<br>";
		
		showbpt();
	}
}

function updatebpcm_perc()
{
   error_reporting(E_ALL);
   
	if (isset($_POST['upflt']) && is_numeric($_POST['upflt']))
	{
		$qry = "UPDATE rbpricep SET comm=round(((comm * ".$_POST['upflt'].") + comm),0) WHERE officeid='".$_SESSION['officeid']."' and comm!=0;";
		$res = mssql_query($qry);
      
      //echo $qry."<br>";
		
		showbpt();
	}
}


function showbpt()
{
	if ($_SESSION['tlev'] < 8)
	{
		echo "<b>You do not have the appropriate Access Level to manage that resource</b>";
		exit;
	}
	
   $qry = "SELECT id FROM rbpricep WHERE officeid='".$_SESSION['officeid']."';";
   $res = mssql_query($qry);
   $nrows = mssql_num_rows($res);

   //echo $nrows;
	/*
   if ($type=='p')
   {
      $ptype="Perimeter";
   }
   else
   {
      $ptype="Square";
   }
	*/
	
   if ($nrows < 1)
   {
      addbp();
   }
   else
   {
      listbp();
   }
}

function update_base_pool_calc_settings()
{
	$qry  = "UPDATE offices SET ";
	$qry .= "def_per='".$_POST['def_per']."',";
	$qry .= "def_sqft='".$_POST['def_sqft']."',";
	$qry .= "def_s='".$_POST['def_s']."',";
	$qry .= "def_m='".$_POST['def_m']."',";
	$qry .= "def_d='".$_POST['def_d']."'";
	$qry .= " WHERE officeid=".$_SESSION['officeid'].";";
   $res  = mssql_query($qry);

	showbpt();
}

1;
?>
