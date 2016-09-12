<?php

function insert_number()
{
   //show_post_vars();
   $err=0;
   $qry0a = "SELECT * FROM IVR_Stats..tollfreetoDID WHERE tollfree='".$_REQUEST['tollfree']."';";
   $res0a = mssql_query($qry0a);
   $nrow0a = mssql_num_rows($res0a);
   
   if ($nrow0a > 0)
   {
	  echo "Toll Free Number Exists.\n";
	  $err++;
   }
   
   $qry0b = "SELECT * FROM IVR_Stats..tollfreetoDID WHERE did='".$_REQUEST['did']."';";
   $res0b = mssql_query($qry0b);
   $nrow0b = mssql_num_rows($res0b);
   
   if ($nrow0b > 0)
   {
	  echo "DID Number Exists.\n";
	  $err++;
   }
   
   if (!isset($_REQUEST['description']) || strlen($_REQUEST['description']) == 0)
   {
	  echo "Description is Blank.\n";
	  $err++;
   }
   
   if ($err == 0)
   {
	  $qryINS  = "INSERT INTO IVR_Stats..tollfreetoDID (";
	  $qryINS .= "displaytollfree,";
	  $qryINS .= "tollfree,";
	  $qryINS .= "did,";
	  $qryINS .= "description";
	  $qryINS .= ") VALUES ( ";
	  $qryINS .= "'".$_REQUEST['displaytollfree']."',";
	  $qryINS .= "'".$_REQUEST['tollfree']."',";
	  $qryINS .= "'".$_REQUEST['did']."',";
	  $qryINS .= "'".$_REQUEST['description']."'";
	  $qryINS .= ");";
	  $resINS = mssql_query($qryINS);
   }
   
   list_numbers();
}


function add_number()
{
   $qry0 = "SELECT * FROM IVR_Stats..tollfreetoDID order by tollfree;";
   $res0 = mssql_query($qry0);
   $row0 = mssql_fetch_array($res0);
   
   //echo $qry0."<br>";
   
   $qry1 = "SELECT officeid,name FROM jest..offices WHERE active=1 ORDER BY grouping,name ASC;";
   $res1 = mssql_query($qry1);
   
   $qry2 = "SELECT officeid,name FROM jest..offices WHERE officeid='".$row0['defaultringto']."';";
   $res2 = mssql_query($qry2);
   $row2 = mssql_fetch_array($res2);
   
   $qry3 = "SELECT securityid,lname,fname FROM jest..security WHERE securityid='".$row0['updtby']."';";
   $res3 = mssql_query($qry3);
   $row3 = mssql_fetch_array($res3);
   
   $qry4 = "SELECT * FROM IVR_Stats..tIVR_roles ORDER BY descrip ASC;";
   $res4 = mssql_query($qry4);
   
   $qry5 = "SELECT * FROM jest..leadstatuscodes WHERE active > 0 AND access = 9 and statusid != 0 ORDER BY name ASC;";
   $res5 = mssql_query($qry5);
   
   echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
   echo "         <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
   echo "         <input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
   echo "         <input type=\"hidden\" name=\"subq\" value=\"insert_new_number\">\n";
   echo "<table align=\"center\" width=\"30%\" border=0>\n";
   echo "	<tr>\n";
   echo "		<td>\n";
   echo "			<table class=\"outer\" width=\"100%\" align=\"left\">\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"left\"><b>Add IVR/800/Call Log Number</b></td>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>".date("m/d/Y",time())."</b></td>\n";
   echo "				</tr>\n";
   echo "			</table>\n";
   echo "		</td>\n";
   echo "	</tr>\n";
   echo "	<tr>\n";
   echo "		<td>\n";
   echo "			<table class=\"outer\" width=\"100%\" align=\"left\">\n";
   echo "   			<tr>\n";
   echo "   			<tr>\n";
   echo "	  				<td class=\"gray\" colspan=\"2\" align=\"right\">\n";
   echo "					</td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Displayed Number:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"displaytollfree\" size=\"40\" maxlength=\"12\"></td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Logic Number:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"tollfree\" size=\"40\" maxlength=\"10\"></td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>DID Number:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"did\" size=\"40\" maxlength=\"10\"></td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Description:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"description\" size=\"40\"></td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" colspan=\"2\" align=\"right\"><input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Save\"></td>\n";
   echo "   			</tr>\n";
   echo "			</table>\n";
   echo "		</td>\n";
   echo "	</tr>\n";
   echo "</table>\n";
   echo "      </form>\n";
}

function save_default()
{
   $qry0 = "SELECT officeid,name,ivr_mar_default FROM jest..offices WHERE ivr_mar_default=1;";
	$res0 = mssql_query($qry0);
   $row0 = mssql_fetch_array($res0);
   
   $qry1 = "SELECT officeid,name,ivr_csl_default FROM jest..offices WHERE ivr_csl_default=1;";
	$res1 = mssql_query($qry1);
   $row1 = mssql_fetch_array($res1);
   
   $qry2 = "SELECT officeid,name,ivr_fin_default FROM jest..offices WHERE ivr_fin_default=1;";
	$res2 = mssql_query($qry2);
   $row2 = mssql_fetch_array($res2);
   
   // Marketing UPDATEs
   if ($_POST['ivr_mar_default']!=0 && $_POST['ivr_mar_default']!=$row0['officeid'])
   {
      $qry0a  = "UPDATE jest..offices SET ";
      $qry0a .= "ivr_mar_default='0'";
      $res0a = mssql_query($qry0a);
      
      $qry0aa  = "UPDATE jest..offices SET ";
      $qry0aa .= "ivr_mar_default='1'";
      $qry0aa .= " WHERE officeid='".$_POST['ivr_mar_default']."';";
      $res0aa = mssql_query($qry0aa);
      //echo $qry0aa."<br>";
   }
   elseif ($_POST['ivr_mar_default']==0)
   {
      $qry0a  = "UPDATE jest..offices SET ";
      $qry0a .= "ivr_mar_default='0'";
      $res0a = mssql_query($qry0a);
      //echo $qry0a."<br>";
   }
   
   // Customer Service UPDATEs
   if ($_POST['ivr_csl_default']!=0 && $_POST['ivr_csl_default']!=$row1['officeid'])
   {
      $qry1a  = "UPDATE jest..offices SET ";
      $qry1a .= "ivr_csl_default='0'";
      $res1a = mssql_query($qry1a);
      
      $qry1aa  = "UPDATE jest..offices SET ";
      $qry1aa .= "ivr_csl_default='1'";
      $qry1aa .= " WHERE officeid='".$_POST['ivr_csl_default']."';";
      $res1aa = mssql_query($qry1aa);
      //echo $qry1aa."<br>";
   }
   elseif ($_POST['ivr_csl_default']==0)
   {
      $qry1a  = "UPDATE jest..offices SET ";
      $qry1a .= "ivr_csl_default='0'";
      $res1a = mssql_query($qry1a);
      //echo $qry1aa."<br>";
   }
   
   // Financing UPDATEs
   if ($_POST['ivr_fin_default']!=0 && $_POST['ivr_fin_default']!=$row2['officeid'])
   {
      $qry2a  = "UPDATE jest..offices SET ";
      $qry2a .= "ivr_fin_default='0'";
      $res2a = mssql_query($qry2a);
      
      $qry2aa  = "UPDATE jest..offices SET ";
      $qry2aa .= "ivr_fin_default='1'";
      $qry2aa .= " WHERE officeid='".$_POST['ivr_fin_default']."';";
      $res2aa = mssql_query($qry2aa);
      //echo $qry1aa."<br>";
   }
   elseif ($_POST['ivr_fin_default']==0)
   {
      $qry2a  = "UPDATE jest..offices SET ";
      $qry2a .= "ivr_fin_default='0'";
      $res2a = mssql_query($qry2a);
      //echo $qry2aa."<br>";
   }
   
   list_numbers();
}

function save_number()
{
   if (!isset($_POST['tfid']))
   {
      echo "Error TF ID not set!<br>";
      exit;
   }
   
   if (!isset($_POST['tollfree']) || strlen($_POST['tollfree'])!=10)
   {
      echo "Error on the Logic Tollfree Number. Click back, correct, and submit again.<br>";
      exit;
   }
   
   $qry0 = "SELECT * FROM IVR_Stats..tollfreetoDID WHERE id='".$_POST['tfid']."';";
	$res0 = mssql_query($qry0);
   $row0 = mssql_fetch_array($res0);
   
   $qry1  = "UPDATE IVR_Stats..tollfreetoDID SET ";
   $qry1 .= "active='".$_POST['active']."',";
   $qry1 .= "category='".$_POST['category']."',";
   $qry1 .= "description='".$_POST['description']."',";
   $qry1 .= "role='".$_POST['role']."',";
   $qry1 .= "displaytollfree='".$_POST['displaytollfree']."',";
   $qry1 .= "tollfree='".$_POST['tollfree']."',";
   $qry1 .= "did='".$_POST['did']."',";
   $qry1 .= "defaultringto='".$_POST['defaultringto']."', ";
   $qry1 .= "leadcode='".$_POST['leadcode']."', ";
   $qry1 .= "rpt_display='".$_POST['rpt_display']."', ";
   $qry1 .= "updtby='".$_SESSION['securityid']."', ";
   $qry1 .= "updated=getdate() ";
   $qry1 .= " WHERE id='".$_POST['tfid']."';";
	$res1 = mssql_query($qry1);
   
   //echo $qry1."<br>";
   
   list_numbers();
}

function edit_number()
{
   $qry0 = "SELECT * FROM IVR_Stats..tollfreetoDID WHERE id='".$_POST['tfid']."';";
   $res0 = mssql_query($qry0);
   $row0 = mssql_fetch_array($res0);
   
   //echo $qry0."<br>";
   
   $qry1 = "SELECT officeid,name FROM jest..offices WHERE active=1 ORDER BY grouping,name ASC;";
   $res1 = mssql_query($qry1);
   
   $qry2 = "SELECT officeid,name FROM jest..offices WHERE officeid='".$row0['defaultringto']."';";
   $res2 = mssql_query($qry2);
   $row2 = mssql_fetch_array($res2);
   
   $qry3 = "SELECT securityid,lname,fname FROM jest..security WHERE securityid='".$row0['updtby']."';";
   $res3 = mssql_query($qry3);
   $row3 = mssql_fetch_array($res3);
   
   $qry4 = "SELECT * FROM IVR_Stats..tIVR_roles ORDER BY descrip ASC;";
   $res4 = mssql_query($qry4);
   
   $qry5 = "SELECT * FROM jest..leadstatuscodes WHERE active > 0 AND access = 9 and statusid != 0 ORDER BY name ASC;";
   $res5 = mssql_query($qry5);
   
   echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
   echo "         <input type=\"hidden\" name=\"subq\" value=\"save\">\n";
	echo "         <input type=\"hidden\" name=\"tfid\" value=\"".$row0['id']."\">\n";
   echo "<table align=\"center\" width=\"30%\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" width=\"100%\" align=\"left\">\n";
	echo "   			<tr>\n";
	echo "   				<td class=\"gray\" align=\"left\"><b>IVR/800 Number Edit</b></td>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>".date("m/d/Y",time())."</b></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
   echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" width=\"100%\" align=\"left\">\n";
	echo "   			<tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" colspan=\"2\" align=\"right\">\n";
   
   if (isset($row0['updtby']) && $row0['updtby']!=0)
   {
      echo "Last Update by: ";
      echo $row3['lname'].", ".$row3['fname'];
      echo " on ";
      echo date("m/d/Y",strtotime($row0['updated']));
   }
   else
   {
      echo "&nbsp";
   }
   
   echo "               </td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Active:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\">\n";
   echo "                  <select name=\"active\">\n";
   
   if ($row0['active']==0)
   {
      echo "                     <option value=\"0\" SELECTED>No</option>\n";
      echo "                     <option value=\"1\">Yes</option>\n";
   }
   else
   {
      echo "                     <option value=\"0\">No</option>\n";
      echo "                     <option value=\"1\" SELECTED>Yes</option>\n";
   }
   
   echo "                  </select>\n";
   echo "               </td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Viewable on Report:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\">\n";
   echo "                  <select name=\"rpt_display\">\n";
   
   if ($row0['rpt_display']==0)
   {
      echo "                     <option value=\"0\" SELECTED>No</option>\n";
      echo "                     <option value=\"1\">Yes</option>\n";
   }
   else
   {
      echo "                     <option value=\"0\">No</option>\n";
      echo "                     <option value=\"1\" SELECTED>Yes</option>\n";
   }
   
   echo "                  </select>\n";
   echo "               </td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
	echo "   				<td class=\"gray\" align=\"right\"><b>Type:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\">\n";
   echo "                  <select name=\"category\">\n";
   
   if ($row0['category']==0)
   {
      echo "                     <option value=\"0\" SELECTED></option>\n";
      echo "                     <option value=\"1\">NAT-OPT</option>\n";
      echo "                     <option value=\"2\">LOCAL</option>\n";
      echo "                     <option value=\"3\">NAT-AUTO</option>\n";
      echo "                     <option value=\"4\">Winners</option>\n";
   }
   elseif ($row0['category']==1)
   {
      echo "                     <option value=\"0\"></option>\n";
      echo "                     <option value=\"1\" SELECTED>NAT-OPT</option>\n";
      echo "                     <option value=\"2\">LOCAL</option>\n";
      echo "                     <option value=\"3\">NAT-AUTO</option>\n";
      echo "                     <option value=\"4\">Winners</option>\n";
   }
   elseif ($row0['category']==2)
   {
      echo "                     <option value=\"0\"></option>\n";
      echo "                     <option value=\"1\">NAT-OPT</option>\n";
      echo "                     <option value=\"2\" SELECTED>LOCAL</option>\n";
      echo "                     <option value=\"3\">NAT-AUTO</option>\n";
      echo "                     <option value=\"4\">Winners</option>\n";
   }
   elseif ($row0['category']==3)
   {
      echo "                     <option value=\"0\"></option>\n";
      echo "                     <option value=\"1\">NAT-OPT</option>\n";
      echo "                     <option value=\"2\">LOCAL</option>\n";
      echo "                     <option value=\"3\" SELECTED>NAT-AUTO</option>\n";
      echo "                     <option value=\"4\">Winners</option>\n";
   }
   elseif ($row0['category']==4)
   {
      echo "                     <option value=\"0\"></option>\n";
      echo "                     <option value=\"1\">NAT-OPT</option>\n";
      echo "                     <option value=\"2\">LOCAL</option>\n";
      echo "                     <option value=\"3\">NAT-AUTO</option>\n";
      echo "                     <option value=\"4\" SELECTED>Winners</option>\n";
   }
   
   echo "                  </select>\n";
   echo "               </td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Description:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"description\" value=\"".$row0['description']."\" size=\"40\"></td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Role:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\">\n";
   echo "                  <select name=\"role\">\n";
   echo "                     <option value=\"0\">Default</option>\n";
   
   while ($row4 = mssql_fetch_array($res4))
   {
      if ($row4['id']==$row0['role'])
      {
         echo "                     <option value=\"".$row4['id']."\" SELECTED>".$row4['descrip']." (".$row4['abrev'].")</option>\n";
      }
      else
      {
         echo "                     <option value=\"".$row4['id']."\">".$row4['descrip']." (".$row4['abrev'].")</option>\n";
      }   
   }
   
   echo "                  </select>\n";
   echo "               </td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\" title=\"Auto Inject Routing\"><b>AI Lead Routing:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\">\n";
   echo "                  <select name=\"leadcode\">\n";
   echo "                     <option value=\"999\">Disabled</option>\n";
   
   while ($row5 = mssql_fetch_array($res5))
   {
      if ($row5['statusid']==$row0['leadcode'])
      {
         echo "                     <option value=\"".$row5['statusid']."\" SELECTED>".$row5['name']."</option>\n";
      }
      else
      {
         echo "                     <option value=\"".$row5['statusid']."\">".$row5['name']."</option>\n";
      }   
   }
   
   echo "                  </select>\n";
   echo "               </td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Display TollFree:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"displaytollfree\" value=\"".$row0['displaytollfree']."\"></td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Logic TollFree:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"tollfree\" value=\"".$row0['tollfree']."\" size=\"40\" maxlength=\"10\"></td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>DID:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"did\" value=\"".$row0['did']."\" size=\"40\" maxlength=\"10\"></td>\n";
   echo "   			</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>Force Ring:</b></td>\n";
   echo "   				<td class=\"gray\" align=\"left\">\n";
   echo "                  <select name=\"defaultringto\">\n";
   
   if ($row0['defaultringto']==0)
   {
      echo "                     <option value=\"0\" SELECTED>Matrix</option>\n";
   }
   else
   {
      echo "                     <option value=\"0\">Matrix</option>\n";
   }
   
   while ($row1 = mssql_fetch_array($res1))
   {
      if ($row0['defaultringto']==$row1['officeid'])
      {
         echo "                     <option value=\"".$row1['officeid']."\" SELECTED>".$row1['name']."</option>\n";
      }
      else
      {
         echo "                     <option value=\"".$row1['officeid']."\">".$row1['name']."</option>\n";
      }   
   }
   
   echo "                  </select>\n";
   echo "               </td>\n";
	echo "				</tr>\n";
   echo "   			<tr>\n";
   echo "   				<td class=\"gray\" colspan=\"2\" align=\"right\"><input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Save\"></td>\n";
   echo "   			</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
   echo "</table>\n";
	echo "      </form>\n";
}

function list_numbers()
{
   error_reporting(E_ALL);
   $qry0 = "SELECT * FROM IVR_Stats..tollfreetoDID ORDER BY active DESC,description ASC;";
	$res0 = mssql_query($qry0);
   $nrow0= mssql_num_rows($res0);
   
   $qry0a = "SELECT officeid,name,ivr_mar_default FROM jest..offices WHERE ivr_mar_default=1;";
	$res0a = mssql_query($qry0a);
   $row0a = mssql_fetch_array($res0a);
   
   $qry0b = "SELECT officeid,name,ivr_fin_default FROM jest..offices WHERE ivr_fin_default=1;";
	$res0b = mssql_query($qry0b);
   $row0b = mssql_fetch_array($res0b);
   
   $qry0c = "SELECT officeid,name,ivr_csl_default FROM jest..offices WHERE ivr_csl_default=1;";
	$res0c = mssql_query($qry0c);
   $row0c = mssql_fetch_array($res0c);
   
   $qry1 = "SELECT officeid,name FROM jest..offices WHERE active=1 ORDER BY grouping,name ASC;";
	$res1 = mssql_query($qry1);
   
   $qry2 = "SELECT officeid,name FROM jest..offices WHERE active=1 ORDER BY grouping,name ASC;";
	$res2 = mssql_query($qry2);
   
   $qry3 = "SELECT officeid,name FROM jest..offices WHERE active=1 ORDER BY grouping,name ASC;";
	$res3 = mssql_query($qry3);
   
   echo "<table align=\"center\" width=\"85%\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" width=\"100%\" align=\"left\">\n";
	echo "   			<tr>\n";
	echo "   				<td class=\"gray\" align=\"left\"><b>IVR/800 Configuration System</b></td>\n";
   echo "   				<td class=\"gray\" align=\"center\">&nbsp</td>\n";
   echo "   				<td class=\"gray\" align=\"right\"><b>".date("m/d/Y",time())."</b></td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
   
   if ($nrow0 > 0)
   {
      echo "<table align=\"center\" width=\"85%\" border=0>\n";
   	echo "	<tr>\n";
      echo "		<td valign=\"top\" width=\"25%\">\n";
      echo "			<table class=\"outer\" width=\"100%\" align=\"left\">\n";
      echo "            <tr>\n";
      echo "         		<td class=\"gray\" colspan=\"3\"><b>IVR Defaults Config</b></td>\n";
      echo "         	</tr>\n";
      echo "            <tr>\n";
      echo "         		<td class=\"ltgray_und\" colspan=\"3\" align=\"center\">Default Time of Day Ringto</td>\n";
      echo "         	</tr>\n";
      echo "            <tr>\n";
      echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
      echo "         <input type=\"hidden\" name=\"subq\" value=\"savedef\">\n";
      echo "         		<td class=\"gray\" align=\"right\"><b>Bus Hours:</b></td>\n";
      echo "         		<td class=\"gray\" align=\"left\">\n";
      echo "                  <select name=\"ivr_mar_default\">\n";
      echo "                     <option value=\"0\">None</option>\n";
      
      while ($row1 = mssql_fetch_array($res1))
      {
         if ($row0a['officeid']==$row1['officeid'])
         {
            echo "                     <option value=\"".$row1['officeid']."\" SELECTED>".$row1['name']."</option>\n";
         }
         else
         {
            echo "                     <option value=\"".$row1['officeid']."\">".$row1['name']."</option>\n";
         }   
      }
      
      echo "                  </select>\n";
      echo "               </td>\n";
      echo "         		<td class=\"gray\" rowspan=\"2\" valign=\"center\" align=\"left\"><font title=\"Time of Day Logic Factors\"><b>T<br>o<br>D</b></font></td>\n";
      echo "         	</tr>\n";
      echo "            <tr>\n";
      echo "         		<td class=\"gray\" align=\"right\"><b>Off Hours:</b></td>\n";
      echo "         		<td class=\"gray\" align=\"left\">\n";
      echo "                  <select name=\"ivr_csl_default\">\n";
      echo "                     <option value=\"0\">None</option>\n";
      
      while ($row2 = mssql_fetch_array($res2))
      {
         if ($row0c['officeid']==$row2['officeid'])
         {
            echo "                     <option value=\"".$row2['officeid']."\" SELECTED>".$row2['name']."</option>\n";
         }
         else
         {
            echo "                     <option value=\"".$row2['officeid']."\">".$row2['name']."</option>\n";
         }   
      }
      
      echo "                  </select>\n";
      echo "               </td>\n";
      echo "         	</tr>\n";
      /*
      echo "   			<tr>\n";
      echo "   				<td class=\"gray\" colspan=\"3\" align=\"center\"><input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Save\"></td>\n";
      echo "   			</tr>\n";
      echo "      </form>\n";
      echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		echo "         <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		echo "         <input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
      echo "         <input type=\"hidden\" name=\"subq\" value=\"savedef\">\n";
      */
      echo "            <tr>\n";
      echo "         		<td class=\"gray\" colspan=\"3\" align=\"center\">&nbsp</td>\n";
      echo "         	</tr>\n";
      echo "            <tr>\n";
      echo "         		<td class=\"ltgray_und\" colspan=\"3\" align=\"center\">Default Role Based Ringto</td>\n";
      echo "         	</tr>\n";
      echo "            <tr>\n";
      echo "         		<td class=\"gray\" align=\"right\"><b>Finance:</b></td>\n";
      echo "         		<td class=\"gray\" align=\"left\">\n";
      echo "                  <select name=\"ivr_fin_default\">\n";
      echo "                     <option value=\"0\">None</option>\n";
      
      while ($row3 = mssql_fetch_array($res3))
      {
         if ($row0b['officeid']==$row3['officeid'])
         {
            echo "                     <option value=\"".$row3['officeid']."\" SELECTED>".$row3['name']."</option>\n";
         }
         else
         {
            echo "                     <option value=\"".$row3['officeid']."\">".$row3['name']."</option>\n";
         }   
      }
      
      echo "                  </select>\n";
      echo "               </td>\n";
      echo "         		<td class=\"gray\" align=\"right\"></td>\n";
      echo "         	</tr>\n";
      
      if (SYS_ADMIN==$_SESSION['securityid'] || MTRX_ADMIN==$_SESSION['securityid'])
      {
         echo "   			<tr>\n";
         echo "   				<td class=\"gray\" colspan=\"3\" align=\"right\">\n";
         //echo "                     <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Save\">\n";
         echo "						<input class=\"checkboxgry\" type=\"image\" src=\"images/save.gif\" alt=\"Save\">\n";
         echo "                  </td>\n";
         echo "   			</tr>\n";
      }
      
      echo "      </form>\n";
      echo "         </table>\n";
      echo "		</td>\n";
      echo "		<td valign=\"top\" width=\"75%\">\n";
      echo "			<table class=\"outer\" width=\"100%\" align=\"left\">\n";
      echo "            <tr>\n";
      echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
	  echo "         <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
	  echo "         <input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
      echo "         <input type=\"hidden\" name=\"subq\" value=\"add_number\">\n";
	  echo "         		<td class=\"gray\" colspan=\"2\"><input class=\"checkboxgry\" type=\"image\" src=\"images/action_add.gif\" alt=\"New Number Config\"></td>\n";
	  echo "      </form>\n";
      echo "         		<td class=\"gray\" colspan=\"2\"><b>Inbound Number Configuration</b></td>\n";
      echo "   				<td class=\"gray\" colspan=\"2\" align=\"center\"><font color=\"red\">RED</font> = Inactive Entries</td>\n";
      echo "   				<td class=\"gray\" colspan=\"5\" align=\"right\"><font color=\"blue\">".$nrow0."</font> Number(s) Found</td>\n";
      echo "         	</tr>\n";
      echo "            <tr>\n";
      echo "         		<td class=\"ltgray_und\"></td>\n";
      echo "         		<td class=\"ltgray_und\" align=\"center\" title=\"Local or National Advertising\"><b>Type</td>\n";
      echo "         		<td class=\"ltgray_und\" align=\"left\"><b>Description</td>\n";
      echo "         		<td class=\"ltgray_und\" align=\"left\" title=\"Routing Methodology. Default Role is based upon Sales Office Territories via Zip Code input. Finance Role is based upon the Finance Office associated with a Sales Office.\"><b>Role</td>\n";
      echo "         		<td class=\"ltgray_und\" align=\"center\"><b>Toll Free</td>\n";
      //echo "         		<td class=\"ltgray_und\" align=\"center\"><b>Logic Tollfree</td>\n";
      echo "         		<td class=\"ltgray_und\" align=\"center\"><b>DID</td>\n";
      echo "         		<td class=\"ltgray_und\" align=\"center\"><b>Viewable</td>\n";
      echo "         		<td class=\"ltgray_und\" align=\"center\" title=\"Auto Inject Routing\"><b>AI Routing</td>\n";
      echo "         		<td class=\"ltgray_und\" align=\"left\"><b>Force Ring</td>\n";
      echo "         		<td class=\"ltgray_und\"></td>\n";
      echo "         		<td class=\"ltgray_und\"></td>\n";
      echo "         	</tr>\n";
      
      $cnt=0;
      while ($row0 = mssql_fetch_array($res0))
      {
         if ($row0['active']==0)
         {
            $fstyle="red";
         }
         else
         {
            $fstyle="black";
         }
         
         if ($row0['defaultringto']==0)
         {
            $dringto="Matrix";
         }
         else
         {
            $qry1z = "SELECT officeid,name FROM jest..offices WHERE officeid='".$row0['defaultringto']."';";
            $res1z = mssql_query($qry1z);
            $row1z = mssql_fetch_array($res1z);
            
            $dringto=$row1z['name'];
         }
         
         if ($row0['role']!=0)
         {
            $qryZ = "SELECT * FROM IVR_Stats..tIVR_roles WHERE id='".$row0['role']."';";
            $resZ = mssql_query($qryZ);
            $rowZ = mssql_fetch_array($resZ);
            
            $drole=$rowZ['descrip'];
         }
         else
         {
            $drole="Default";
         }

         $cnt++;
         echo "   <tr>\n";
         echo "		<td class=\"wh_und\" align=\"right\"><font color=\"".$fstyle."\">". $cnt .".</font></td>\n";
         echo "		<td class=\"wh_und\" align=\"right\"><font color=\"".$fstyle."\">\n";
         
         if ($row0['category']==0)
			{
				echo "&nbsp";
			}
			elseif ($row0['category']==1)
			{
				echo "NAT-OPT:";
			}
			elseif ($row0['category']==2)
			{
				echo "LOCAL:";
			}
         elseif ($row0['category']==3)
			{
				echo "NAT-AUTO:";
			}
         elseif ($row0['category']==4)
			{
				echo "Winners:";
			}
         
         echo "      </font></td>\n";
         echo "		<td class=\"wh_und\" align=\"left\"><font color=\"".$fstyle."\">". trim($row0['description']) ."</font></td>\n";
         echo "		<td class=\"wh_und\" align=\"left\"><font color=\"".$fstyle."\">". trim($drole) ."</font></td>\n";
         echo "		<td class=\"wh_und\" align=\"center\"><font color=\"".$fstyle."\">". trim($row0['displaytollfree']) ."</font></td>\n";
         //echo "		<td class=\"wh_und\" align=\"center\"><font color=\"".$fstyle."\">". trim($row0['tollfree']) ."</font></td>\n";
         echo "		<td class=\"wh_und\" align=\"center\"><font color=\"".$fstyle."\">". trim($row0['did']) ."</font></td>\n";
         echo "		<td class=\"wh_und\" align=\"center\">";
         
         if (!empty($row0['rpt_display']) && $row0['rpt_display']==1)
         {
            echo "<font color=\"black\">Yes</font>";
         }
         else
         {
            echo "<font color=\"red\">No</font>";
         }
         
         echo "     </td>\n";
         echo "		<td class=\"wh_und\" align=\"center\">";
         
         if (!empty($row0['leadcode']) && $row0['leadcode']!=999)
         {
            echo "<font color=\"black\">Yes</font>";
         }
         else
         {
            echo "<font color=\"red\">No</font>";
         }
         
         echo "     </td>\n";
         echo "		<td class=\"wh_und\" align=\"left\"><font color=\"".$fstyle."\">". $dringto ."</font></td>\n";
         echo "      <form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n";
		 echo "         <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		 echo "         <input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
         echo "         <input type=\"hidden\" name=\"subq\" value=\"edit\">\n";
		 echo "         <input type=\"hidden\" name=\"tfid\" value=\"".$row0['id']."\">\n";
         echo "		<td class=\"wh_und\" align=\"center\"><font color=\"".$fstyle."\">\n";
         
         if (SYS_ADMIN==$_SESSION['securityid'] || MTRX_ADMIN==$_SESSION['securityid'])
         {
            //echo "         <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Edit\">\n";
            echo "			<input class=\"checkboxwh\" type=\"image\" src=\"images/folder_open.gif\" alt=\"Open this Record\">\n";
         }
         
         echo "      </font></td>\n";
         echo "      </form>\n";
         echo "		<td class=\"wh_und\" align=\"right\"><font color=\"".$fstyle."\">". $cnt ."</font></td>\n";
         echo "	</tr>\n";
      }
      
      echo "			</table>\n";
      echo "		</td>\n";
      echo "	</tr>\n";   
   }
   
   echo "</table>\n";
}

function add_zip()
{
	//show_post_vars();
	if (strlen(trim($_POST['czip']))!=5 || !is_numeric(trim($_POST['czip'])))
	{
		echo "<font color=\"red\"><b>Error!</b></font><br>";
		echo "<b>Customer ZIP Code (".$_POST['czip']."): Invalid Format</b><br>";
	}
	else
	{
		$qry = "SELECT czip FROM zip_to_zip WHERE czip='".$_POST['czip']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		$nrow= mssql_num_rows($res);
		
		//echo $nrow."<br>";
		
		if ($nrow == 0 && !empty($_POST['czip']) && strlen(trim($_POST['czip']))==5)
		{
			//echo "ADDED!<BR>";
			if (!empty($_POST['u']) && $_POST['u']==1)
			{
				$pu=1;
			}
			else
			{
				$pu=0;
			}
			
			$qry1 = "INSERT INTO zip_to_zip (ozip,czip,careacode,ccity,ccounty,cstate,u,updtby,updated,q) values ('".$_POST['ozip']."','".$_POST['czip']."','".$_POST['careacode']."','".$_POST['ccity']."','".$_POST['ccounty']."','".$_POST['cstate']."','".$pu."','".$_SESSION['securityid']."',getdate(),'0');";
			$res1 = mssql_query($qry1);
         //echo $qry1."<br>";
		}
		else
		{
			$qry1 = "SELECT o.name,o.zip FROM zip_to_zip as z inner join offices as o on z.ozip=o.zip WHERE z.czip='".$_POST['czip']."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);
			$nrow1= mssql_num_rows($res1);
	
			//echo $qry1."<br>";
			//echo $nrow1."<br>";
			
			if ($nrow1 > 0)
			{
				echo "<font color=\"red\"><b>Error!</b></font><br>";
				echo "<b>Customer ZIP Code (".$_POST['czip']."): Already routing to ".$row1['name']." (".$row1['zip'].")</b><br>";
			}
		}
	}
	zip_search();
}

function upd_zip()
{
	//show_post_vars();
	$qry = "SELECT id FROM zip_to_zip WHERE id='".$_POST['coid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow= mssql_num_rows($res);
	
	if (!empty($_POST['u']) && $_POST['u']==1)
	{
		$pu=1;
	}
	else
	{
		$pu=0;
	}
	
	//echo $qry."<br>";
	
	if ($nrow > 0 && !empty($_POST['ozip']) && !empty($_POST['czip']))
	{
		$qry1 = "UPDATE
						zip_to_zip SET ozip='".$_POST['ozip']."',
						czip='".$_POST['czip']."',
						careacode='".$_POST['careacode']."',
						ccity='".$_POST['ccity']."',
						ccounty='".$_POST['ccounty']."',
						cstate='".$_POST['cstate']."',
						q='0',
						u='".$pu."',
						updtby='".$_SESSION['securityid']."',
						updated=getdate()
					WHERE id='".$_POST['coid']."';";
		$res1 = mssql_query($qry1);
		
		//echo $qry1."<br>";
	}
	
	zip_search();
}

function zip_maint()
{
	//echo "TEST";
	//error_reporting(E_ALL);
   
   if ($_SESSION['officeid']!=89)
   {
      exit;
   }
	
	$qry0 = "SELECT SYS_ADMIN,MTRX_ADMIN FROM master..bhest_config;";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	//echo $qry0."<br>";
	
   $qry1 = "SELECT officeid,name,zip,ringto,active FROM offices ORDER BY grouping,name ASC;";
   $res1 = mssql_query($qry1);
   $nrow1= mssql_num_rows($res1);

   echo "<table>\n";
   echo "	<tr>\n";
   echo "		<td align=\"left\" valign=\"top\">\n";
   echo "			<table class=\"outer\" width=\"100%\">\n";
   echo "				<tr>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>Matrix Maintenance Tool</b></td>\n";
   echo "				</tr>\n";
   echo "			</table>\n";
   echo "		</td>\n";
   echo "	</tr>\n";
   echo "	<tr>\n";
   echo "		<td align=\"left\" valign=\"top\">\n";
   echo "			<table class=\"outer\" width=\"100%\">\n";
   echo "				<tr>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>Search</b></td>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"left\"><b>Parameter</b></td>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"center\"><b>Text</b></td>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"right\"></td>\n";
   echo "				</tr>\n";
   echo "				<tr>\n";
   echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
   echo "								<input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
   echo "								<input type=\"hidden\" name=\"subq\" value=\"zip_search\">\n";
   echo "								<input type=\"hidden\" name=\"type\" value=\"off\">\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"right\"><b>Offices</b></td>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"right\">\n";
   echo "								<select name=\"sdata\">\n";

   while ($row1 = mssql_fetch_array($res1))
   {		
      $qry3 = "SELECT count(id) as cnt FROM zip_to_zip WHERE ozip='".$row1['zip']."';";
      $res3 = mssql_query($qry3);
      $row3 = mssql_fetch_array($res3);
      
      if ($row1['active']==0)
      {
         $ostyle="fontred";
      }
      else
      {
         $ostyle="fontblack";
      }
      
      echo "                           <option value=\"".$row1['zip']."\" class=\"".$ostyle."\">".$row1['name']." (".$row3['cnt'].")</option>\n";
   }

   echo "								</select>\n";
   echo "								<input type=\"hidden\" name=\"dsrc\" value=\"zip\">\n";
   echo "					</td>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
   echo "						<input class=\"checkboxgry\" type=\"checkbox\" name=\"textmode\" value=\"1\" title=\"Check to display in plain textmode\">\n";
   echo "					</td>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
   echo "						<input class=\"checkboxgry\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
   echo "					</td>\n";
   echo "         					</form>\n";
   echo "				</tr>\n";
   echo "				<tr>\n";
   echo "					<td colspan=\"4\" bgcolor=\"#d3d3d3\" align=\"center\"><hr width=\"100%\"></td>\n";
   echo "				</tr>\n";
   echo "				<tr>\n";
   echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
   echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
   echo "								<input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
   echo "								<input type=\"hidden\" name=\"subq\" value=\"zip_search\">\n";
   echo "								<input type=\"hidden\" name=\"dsrc\" value=\"zip\">\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"right\"><b>Matrix</b></td>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"right\">\n";
   echo "								<input class=\"bboxl\" type=\"text\" name=\"sdata\" size=\"25\" maxlength=\"5\">\n";
   echo "								<select name=\"type\">\n";
   echo "                           <option value=\"czip\">Client Zip</option>\n";
   echo "                           <option value=\"ozip\">Office Zip</option>\n";
   //echo "                           <option value=\"ringto\">Ring to</option>\n";
   echo "								</select>\n";
   echo "					</td>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"center\">\n";
   echo "						<input class=\"checkboxgry\" type=\"checkbox\" name=\"textmode\" value=\"1\" title=\"Check to display in plain textmode\">\n";
   echo "					</td>\n";
   echo "					<td bgcolor=\"#d3d3d3\" align=\"right\">\n";
   echo "						<input class=\"checkboxgry\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
   //echo "								<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\">\n";
   echo "					</td>\n";
   echo "         					</form>\n";
   echo "				</tr>\n";
   echo "			</table>\n";
   echo "		</td>\n";
   echo "	</tr>\n";
   echo "</table>\n";
}

function zip_search()
{
	if (!empty($_POST['type']) && $_POST['type']=="off")
	{
		$qry = "SELECT id,czip,ozip as zip,ccity,ccounty,cstate,u,q,careacode FROM zip_to_zip WHERE ozip='".$_POST['sdata']."' order by czip ASC;";
	}
	else
	{
		$qry = "SELECT id,czip,ozip as zip,ccity,ccounty,cstate,u,q,careacode FROM zip_to_zip WHERE ".$_POST['type']."='".$_POST['sdata']."' order by czip ASC;";
	}

	$res	= mssql_query($qry);
	$nrow = mssql_num_rows($res);
	
	$res0 = mssql_query($qry);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
   
   $qry0a = "SELECT SYS_ADMIN,MTRX_ADMIN FROM master..bhest_config;";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
	
	//$qry1 = "SELECT officeid,name,zip,ringto FROM offices WHERE zip='".$_POST['sdata']."';";
	$qry1 = "SELECT officeid,name,zip,ringto FROM offices WHERE zip='".$row0['zip']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);
	//echo $qry."<br>";

	if ($nrow > 0)
	{
      if (!empty($_POST['textmode']) && $_POST['textmode']==1)
      {
         //echo "<pre>\n";
        echo "<table border=0>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" valign=\"top\">\n";
		echo "						<table border=1 bordercolor=\"black\" width=\"100%\">\n";
        //echo "	<tr>\n";
		//echo "		<td align=\"left\" valign=\"top\">\n";
		
		if ($nrow1 > 0)
		{
			echo "				<tr>\n";
			echo "								<td align=\"left\" valign=\"bottom\"><b>Office</b></td>\n";
			echo "								<td align=\"center\" valign=\"bottom\"><b>Office Zip</b></td>\n";
			echo "								<td align=\"center\" valign=\"bottom\"><b>Client Zip</b></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"><b>Area Code</b></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"><b>City</b></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"><b>County</b></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"><b>State</b></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"><b>Unofficial</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "								<td align=\"left\">".$row1['name']."</td>\n";
			echo "								<td align=\"center\">".$row1['zip']."</td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"></td>\n";
            echo "								<td align=\"center\" valign=\"bottom\"></td>\n";
			echo "				</tr>\n";
		}
		
		//echo "		</td>\n";
		//echo "	</tr>\n";

		$zcnt=0;
		while ($row = mssql_fetch_array($res))
		{
			echo "							<tr>\n";         
            echo "								<td align=\"left\"></td>\n";
            echo "								<td align=\"center\"></td>\n";
            echo "								<td align=\"center\">".$row['czip']."</td>\n";
            echo "								<td align=\"center\">".$row['careacode']."</td>\n";
            echo "								<td align=\"center\">".$row['ccity']."</td>\n";
            echo "								<td align=\"center\">".$row['ccounty']."</td>\n";
            echo "								<td align=\"center\">".$row['cstate']."</td>\n";
            echo "								<td align=\"center\">\n";
               
            if (!empty($row['u']) && $row['u']==1)
            {
               echo "X";
            }
               
            echo "								</td>\n";         
			echo "							</tr>\n";
		}

		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
        //echo "</pre>\n";
      }
      else
      {
		echo "<table width=\"50%\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"bottom\"><b>ZIP to ZIP Routing Matrix</b> Search Results</td>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"right\" valign=\"bottom\">\n";
		echo "						<font color=\"red\"><b>".$nrow."</font> Entries(s) Found</b>";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		
		 if (isset($_POST['type']) && $_POST['type']=="czip" && $nrow1==0)
		 {
			echo "	<tr>\n";
			echo "		<td align=\"left\" valign=\"top\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td bgcolor=\"#d3d3d3\" align=\"center\" valign=\"bottom\"><font color=\"red\"><b>NOTE:</b></font><br>This Client Zip is not tied to an Office.</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";	 
		 }
		
		
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		
		if ($nrow1 > 0)
		{
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "								<td class=\"ltgray_und\" align=\"left\" valign=\"bottom\"><b>Office</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Office Zip</b></td>\n";
			echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Ring To</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "								<td class=\"gray\" align=\"left\">".$row1['name']."</td>\n";
			echo "								<td class=\"gray\" align=\"center\">".$row1['zip']."</td>\n";
			echo "								<td class=\"gray\" align=\"center\">".$row1['ringto']."</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
		}
		
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" valign=\"top\">\n";
		echo "						<table width=\"100%\" class=\"outer\">\n";
		echo "							<tr>\n";
		
		if ($_SESSION['securityid']==MTRX_ADMIN||$_SESSION['securityid']==SYS_ADMIN)
		{
		 echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
		 echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Office Zip</b></td>\n";
		}
		else
		{
		 echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
		 echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"></td>\n";
		}
		
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Client Zip</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Area Code</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>City</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>County</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>State</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"center\" valign=\"bottom\"><b>Unofficial</b></td>\n";
		echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\">&nbsp</td>\n";
		echo "							</tr>\n";

		$zcnt=0;
		while ($row = mssql_fetch_array($res))
		{
			if (!empty($row['zip']))
			{
				$pozip=$row['zip'];
			}
			else
			{
				$pozip="";
			}
			
         if (SYS_ADMIN==$_SESSION['securityid'] || MTRX_ADMIN==$_SESSION['securityid'])
         {
            if ($zcnt == 0 && !empty($pozip))
            {
               echo "							<tr>\n";
               echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			   echo "								<td class=\"wh_und\" align=\"left\"></td>\n";
			   echo "								<td class=\"wh_und\" align=\"center\">".$row['zip']."</td>\n";
               echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" name=\"czip\"></td>\n";
               echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" name=\"careacode\"></td>\n";
               echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"15\" name=\"ccity\"></td>\n";
               echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"15\" name=\"ccounty\"></td>\n";
               echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"3\" name=\"cstate\" maxlength=\"2\"></td>\n";
               echo "								<td class=\"wh_und\" align=\"center\"><input class=\"checkboxwh\" type=\"checkbox\" value=\"1\" name=\"u\"></td>\n";
               echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
               echo "								<input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
               echo "								<input type=\"hidden\" name=\"subq\" value=\"add_zip\">\n";
               echo "								<input type=\"hidden\" name=\"ozip\" value=\"".$pozip."\">\n";
               echo "								<input type=\"hidden\" name=\"oringto\" value=\"".$row1['ringto']."\">\n";
               echo "								<input type=\"hidden\" name=\"officeid\" value=\"".$row1['officeid']."\">\n";
               echo "								<input type=\"hidden\" name=\"type\" value=\"".$_POST['type']."\">\n";
               echo "								<input type=\"hidden\" name=\"sdata\" value=\"".$_POST['sdata']."\">\n";
               echo "								<input type=\"hidden\" name=\"dsrc\" value=\"".$_POST['dsrc']."\">\n";
               echo "								<td class=\"wh_und\" align=\"right\">\n";
               echo "									<input class=\"checkboxwh\" type=\"image\" src=\"images/action_add.gif\" alt=\"Add\">\n";
               echo "								</td>\n";
               echo "         					</form>\n";
               echo "							</tr>\n";
               echo "							<tr>\n";
               echo "								<td class=\"gray\" colspan=\"9\" align=\"center\">&nbsp</td>\n";
               echo "							</tr>\n";
               $zcnt++;
            }
         }
			
			echo "							<tr>\n";
			echo "         					<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
         
         if (SYS_ADMIN==$_SESSION['securityid'] || MTRX_ADMIN==$_SESSION['securityid'])
         {
            echo "								<input type=\"hidden\" value=\"".$pozip."\" name=\"ozip\">\n";
            echo "								<td class=\"wh_und\" align=\"left\"></td>\n";
            echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" value=\"".$pozip."\" name=\"ozip\"></td>\n";
            echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" value=\"".$row['czip']."\" name=\"czip\"></td>\n";
            echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" size=\"10\" value=\"".$row['careacode']."\" name=\"careacode\"></td>\n";
            echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row['ccity']."\" name=\"ccity\"></td>\n";
            echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"15\" value=\"".$row['ccounty']."\" name=\"ccounty\"></td>\n";
            echo "								<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" size=\"3\" value=\"".$row['cstate']."\" name=\"cstate\" maxlength=\"2\"></td>\n";
            echo "								<td class=\"wh_und\" align=\"center\">\n";
               
            if (!empty($row['u']) && $row['u']==1)
            {
               echo "	<input class=\"checkboxwh\" type=\"checkbox\" value=\"1\" name=\"u\" CHECKED>\n";
            }
            else
            {
               echo "	<input class=\"checkboxwh\" type=\"checkbox\" value=\"1\" name=\"u\">\n";
            }
            
            echo "								</td>\n";
         }
         else
         {
            echo "								<td class=\"wh_und\" align=\"left\"></td>\n";
            echo "								<td class=\"wh_und\" align=\"center\"></td>\n";
            echo "								<td class=\"wh_und\" align=\"center\">".$row['czip']."</td>\n";
            echo "								<td class=\"wh_und\" align=\"center\">".$row['careacode']."</td>\n";
            echo "								<td class=\"wh_und\" align=\"center\">".$row['ccity']."</td>\n";
            echo "								<td class=\"wh_und\" align=\"center\">".$row['ccounty']."</td>\n";
            echo "								<td class=\"wh_und\" align=\"center\">".$row['cstate']."</td>\n";
            echo "								<td class=\"wh_und\" align=\"center\">\n";
               
            if (!empty($row['u']) && $row['u']==1)
            {
               echo "X";
            }
               
            echo "								</td>\n";
         }
         
			echo "								<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
			echo "								<input type=\"hidden\" name=\"call\" value=\"IVR\">\n";
			echo "								<input type=\"hidden\" name=\"subq\" value=\"upd_zip\">\n";
			echo "								<input type=\"hidden\" name=\"coid\" value=\"".$row['id']."\">\n";
			echo "								<input type=\"hidden\" name=\"oringto\" value=\"".$row1['ringto']."\">\n";
			echo "								<input type=\"hidden\" name=\"officeid\" value=\"".$row1['officeid']."\">\n";
			echo "								<input type=\"hidden\" name=\"type\" value=\"".$_POST['type']."\">\n";
			echo "								<input type=\"hidden\" name=\"sdata\" value=\"".$_POST['sdata']."\">\n";
			echo "								<input type=\"hidden\" name=\"dsrc\" value=\"".$_POST['dsrc']."\">\n";
			echo "								<td class=\"wh_und\" align=\"right\">\n";
         
         if (SYS_ADMIN==$_SESSION['securityid'] || MTRX_ADMIN==$_SESSION['securityid'])
         {
            echo "									<input class=\"checkboxwh\" type=\"image\" src=\"images/save.gif\" alt=\"Save\">\n";
         }
         
			echo "								</td>\n";
			echo "         					</form>\n";
			echo "							</tr>\n";
		}

		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
      }
	}
	else
	{
		echo "<table width=\"40%\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\" valign=\"top\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td bgcolor=\"#d3d3d3\" align=\"left\" valign=\"top\">No Entries(s) Found for <b>".$_POST['sdata']."</b></td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function sys_matrix()
{
   //echo "Listing 800 Maintenance Routine";
   if ($_SESSION['subq']=="list")
	{
		list_numbers();
	}
	elseif ($_SESSION['subq']=="add_number")
   {
	  add_number();
   }
   elseif ($_SESSION['subq']=="insert_new_number")
   {
	  insert_number();
   }
   elseif ($_SESSION['subq']=="edit")
	{
		edit_number();
	}
   elseif ($_SESSION['subq']=="save")
	{
		save_number();
	}
   elseif ($_SESSION['subq']=="savedef")
	{
		save_default();
	}
   elseif ($_SESSION['subq']=="savefin")
	{
		save_finance();
	}
   elseif ($_SESSION['subq']=="zip_maint")
   {
      zip_maint();
   }
   elseif ($_SESSION['subq']=="zip_search")
   {
      zip_search();
   }
   elseif ($_SESSION['subq']=="add_zip")
   {
      add_zip();
   }
   elseif ($_SESSION['subq']=="upd_zap")
   {
      upd_zap();
   }
   elseif ($_SESSION['subq']=="upd_zip")
   {
      upd_zip();
   }
}

?>