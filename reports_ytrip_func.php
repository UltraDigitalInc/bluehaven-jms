<?php

function ye_add()
{
	//show_post_vars();
	//echo "<br>";
	if (!empty($_POST['sid']) && $_POST['sid']!=0)
	{
		$qry = "SELECT sid,passnum FROM tyearlytrip WHERE sid='".$_POST['sid']."' AND tyear='".$_POST['yeyear']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		$nrow= mssql_num_rows($res);
		
		if ($row['sid']==$_POST['sid'] || $row['passnum']==$_POST['passnum'])
		{
			echo "<font color=\"red\"><b>Error</b></font><br>Attendee already exists for ".$_POST['yeyear']."<br>";
			exit;
		}
		
		if (empty($_POST['dob']) || !valid_date($_POST['dob']))
		{
			echo "<font color=\"red\"><b>Error</b></font><br>Incorrect DoB Date Format<br>";
			exit;
		}
		
		if (empty($_POST['passexp']) || !valid_date($_POST['passexp']))
		{
			echo "<font color=\"red\"><b>Error</b></font><br>Incorrect Passport Date Format<br>";
			exit;
		}
		
		if (empty($_POST['passnum']) || strlen($_POST['passnum']) < 9)
		{
			echo "<font color=\"red\"><b>Error</b></font><br>Incorrect Passport Number Info<br>";
			exit;
		}
		
		$qry1 = "SELECT lname,fname FROM security WHERE securityid='".$_POST['sid']."';";
		$res1 = mssql_query($qry1);
		$row1 = mssql_fetch_array($res1);
		
		$qry0  = "INSERT INTO tyearlytrip (";
		$qry0 .= "oid,sid,lname,fname,dob,citizen,passnum,pexpdate,no_digs,buyin,tyear,tentative,locked,assoc,lupdate,lupdateby";
		$qry0 .= ") VALUES (";
		$qry0 .= "'".$_POST['oid']."',";
		$qry0 .= "'".$_POST['sid']."',";
		$qry0 .= "'".$row1['lname']."',";
		$qry0 .= "'".$row1['fname']."',";
		$qry0 .= "'".$_POST['dob']."',";
		$qry0 .= "'".$_POST['citizen']."',";
		$qry0 .= "'".$_POST['passnum']."',";
		$qry0 .= "'".$_POST['passexp']."',";
		$qry0 .= "'".$_POST['no_digs']."',";
		$qry0 .= "'".$_POST['buyin']."',";
		$qry0 .= "'".$_POST['yeyear']."',";
		$qry0 .= "'".$_POST['tentative']."',";
		$qry0 .= "'".$_POST['locked']."',";
		$qry0 .= "'".$_POST['assoc']."',";
		$qry0 .= "getdate(),";
		$qry0 .= "'".$_SESSION['securityid']."'";
		$qry0 .= ");";
		$res0 = mssql_query($qry0);
		//$row0 = mssql_fetch_array($res0);
		
	}
	else
	{
		$qry = "SELECT sid,lname,fname FROM tyearlytrip WHERE passnum='".$_POST['passnum']."' AND tyear='".$_POST['yeyear']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		$nrow= mssql_num_rows($res);
		
		if ($nrow > 0)
		{
			echo "<font color=\"red\"><b>Error</b></font><br>".$row['lname'].", ".$row['fname']." already exists for ".$_POST['yeyear']."<br>";
			exit;
		}
		
		if (empty($_POST['dob']) 	|| !valid_date($_POST['dob']))
		{
			echo "<font color=\"red\"><b>Error</b></font><br>Incorrect DoB Date Format<br>";
			exit;
		}
		
		if (empty($_POST['passexp']) || !valid_date($_POST['passexp']))
		{
			echo "<font color=\"red\"><b>Error</b></font><br>Incorrect Passport Date Format<br>";
			exit;
		}
		
		if (empty($_POST['passnum']) || strlen($_POST['passnum']) < 9)
		{
			echo "<font color=\"red\"><b>Error</b></font><br>Incorrect Passport Number Info<br>";
			exit;
		}
		
		$qry0  = "INSERT INTO tyearlytrip (";
		$qry0 .= "oid,sid,lname,fname,dob,citizen,passnum,";
		$qry0 .= "pexpdate,no_digs,buyin,tyear,tentative,locked,assoc,lupdate,lupdateby";
		$qry0 .= ") VALUES (";
		$qry0 .= "'".$_POST['oid']."',";
		$qry0 .= "'".$_POST['sid']."',";
		$qry0 .= "'".removequote($_POST['lname'])."',";
		$qry0 .= "'".removequote($_POST['fname'])."',";
		$qry0 .= "'".$_POST['dob']."',";
		$qry0 .= "'".$_POST['citizen']."',";
		$qry0 .= "'".removequote($_POST['passnum'])."',";
		$qry0 .= "'".$_POST['passexp']."',";
		$qry0 .= "'".removequote($_POST['no_digs'])."',";
		$qry0 .= "'".$_POST['buyin']."',";
		$qry0 .= "'".$_POST['yeyear']."',";
		$qry0 .= "'".$_POST['tentative']."',";
		$qry0 .= "'".$_POST['locked']."',";
		$qry0 .= "'".$_POST['assoc']."',";
		$qry0 .= "getdate(),";
		$qry0 .= "'".$_SESSION['securityid']."'";
		$qry0 .= ");";
		$res0 = mssql_query($qry0);
	}
	
	ye_lists();
}

function ye_delete()
{
	if (!empty($_POST['rid']) && $_POST['rid']!=0)
	{
		$qry0 = "SELECT id,sid FROM tyearlytrip WHERE id='".$_POST['rid']."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "SELECT id FROM tyearlytrip WHERE assoc='".$row0['sid']."';";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);
			$nrow1= mssql_num_rows($res1);	
			
			if ($nrow1 > 0)
			{
				$qry2 = "DELETE FROM tyearlytrip WHERE assoc='".$row0['sid']."';";
				$res2 = mssql_query($qry2);
			}
			
			$qry3  = "DELETE FROM tyearlytrip WHERE id='".$row0['id']."';";
			$res3 = mssql_query($qry3);
		}
	}
	
	ye_lists();
}

function ye_edit()
{
	if (!empty($_POST['rid']) && $_POST['rid']!=0)
	{
		$qry = "SELECT * FROM tyearlytrip WHERE id='".$_POST['rid']."';";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		$nrow= mssql_num_rows($res);
		
		if ($nrow > 0)
		{
			if (empty($_POST['dob']) || !valid_date($_POST['dob']))
			{
				echo "<font color=\"red\"><b>Error</b></font><br>Incorrect DoB Date Format<br>";
				exit;
			}
			
			if (empty($_POST['passexp']) || !valid_date($_POST['passexp']))
			{
				echo "<font color=\"red\"><b>Error</b></font><br>Incorrect Passport Date Format<br>";
				exit;
			}
			
			if (empty($_POST['passnum']) || strlen($_POST['passnum']) < 9)
			{
				echo "<font color=\"red\"><b>Error</b></font><br>Incorrect Passport Number Info<br>";
				exit;
			}
			
			$qry0  = "UPDATE tyearlytrip SET ";
			
			if ($row['sid']==0)
			{
				$qry0 .= "lname='".removequote($_POST['lname'])."',";
				$qry0 .= "fname='".removequote($_POST['fname'])."',";
			}
			
			$qry0 .= "dob='".$_POST['dob']."',";
			$qry0 .= "citizen='".$_POST['citizen']."',";
			$qry0 .= "passnum='".removequote($_POST['passnum'])."',";
			$qry0 .= "pexpdate='".$_POST['passexp']."',";
			$qry0 .= "no_digs='".removequote($_POST['no_digs'])."',";
			$qry0 .= "buyin='".$_POST['buyin']."',";
			$qry0 .= "locked='".$_POST['locked']."',";
			$qry0 .= "tentative='".$_POST['tentative']."',";
			//$qry0 .= "assoc='".$_POST['assoc']."',";
			$qry0 .= "lupdate=getdate(),";
			$qry0 .= "lupdateby='".$_SESSION['securityid']."'";
			$qry0 .= " WHERE id='".$row['id']."';";
			$res0 = mssql_query($qry0);
			//$row0 = mssql_fetch_array($res0);
			
			//echo $qry0."<br>";
		}
	}
	
	ye_lists();
}

function ye_lists()
{
	$brdr	=0;	
	$qry0a = "SELECT entripinfo FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
	
	$qry0b = "SELECT gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res0b = mssql_query($qry0b);
	$row0b = mssql_fetch_array($res0b);
	
	if ($row0a['entripinfo'] == 1 && $row0b['gmreports']==1)
	{
		if (!empty($_POST['yeyear']) && is_numeric($_POST['yeyear']))
		{
			$yeyear=$_POST['yeyear'];
		}
		else
		{
			$yeyear="";
		}
		
		if (!empty($_POST['addatt']) && is_numeric($_POST['addatt']))
		{
			$addatt=$_POST['addatt'];
		}
		else
		{
			$addatt=0;
		}
		
		if ($_SESSION['officeid']==89)
		{
			//$qry1 = "SELECT * FROM tyearlytrip order by lname ASC;";
			$qry1  = "SELECT ";
			$qry1 .= "	y.*, ";
			$qry1 .= "	(SELECT lname FROM security WHERE securityid=y.sid) as slname, ";
			$qry1 .= "	(SELECT fname FROM security WHERE securityid=y.sid) as sfname, ";
			$qry1 .= "	o.name ";
			$qry1 .= "FROM ";
			$qry1 .= "	tyearlytrip as y ";
			$qry1 .= "INNER JOIN ";
			$qry1 .= "	offices as o ";
			$qry1 .= "ON ";
			$qry1 .= "	y.oid=o.officeid ";
			$qry1 .= "WHERE ";
			$qry1 .= "	y.sid!=0 ";
			$qry1 .= "ORDER BY ";
			$qry1 .= "	o.name, ";
			$qry1 .= "	lname, ";
			$qry1 .= "	assoc ";
			$qry1 .= "ASC;";
		}
		else
		{
			//$qry1 = "SELECT * FROM tyearlytrip WHERE oid='".$_SESSION['officeid']."' order by lname ASC;";
			$qry1  = "SELECT ";
			$qry1 .= "	y.*, ";
			$qry1 .= "	(SELECT lname FROM security WHERE securityid=y.sid) as slname, ";
			$qry1 .= "	(SELECT fname FROM security WHERE securityid=y.sid) as sfname, ";
			$qry1 .= "	o.name ";
			$qry1 .= "FROM ";
			$qry1 .= "	tyearlytrip as y ";
			$qry1 .= "INNER JOIN ";
			$qry1 .= "	offices as o ";
			$qry1 .= "ON ";
			$qry1 .= "	y.oid=o.officeid ";
			$qry1 .= "WHERE ";
			$qry1 .= "	y.oid='".$_SESSION['officeid']."' ";
			$qry1 .= "	and y.sid!=0 ";
			$qry1 .= "ORDER BY ";
			$qry1 .= "	o.name, ";
			$qry1 .= "	lname, ";
			$qry1 .= "	assoc ";
			$qry1 .= "ASC;";
		}
		
		$res1 = mssql_query($qry1);
		$nrow1 = mssql_num_rows($res1);
		
		if ($_SESSION['officeid']==89)
		{
			//echo $qry1."<br>";
		}
		
		echo "<table width=\"100%\">\n";
		echo "   <tr>\n";
		echo "   	<td>\n";
		echo "			<table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
		echo "   			<tr>\n";
		
		if ($_SESSION['officeid']==89)
		{
			echo " 			  		<td class=\"gray\" align=\"left\">&nbsp<b>Year End Trip Registration Info for </b>&nbsp&nbspAll Offices</td>\n";
		}
		else
		{
			echo " 			  		<td class=\"gray\" align=\"left\">&nbsp<b>Year End Trip Registration Info for </b>&nbsp&nbsp".$_SESSION['offname']."</td>\n";
		}
		
		echo "   			</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		 
		if ($nrow1 > 0)
		{
			echo "   <tr>\n";
			echo "   	<td>\n";
			echo "			<table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\"><b>Office</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\"><b>Last Name</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\"><b>First Name</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>DoB</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Citizenship</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Passport Number</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Passport Expiration</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b># of Digs</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Tentative?</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Buy In?</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Locked?</b></td>\n";	
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"></td>\n";
			echo "   			</tr>\n";
			
			$ycnt=0;
			while ($row1 = mssql_fetch_array($res1))
			{
				$ycnt++;
				$qry1a = "SELECT officeid,name FROM offices WHERE officeid='".$row1['oid']."';";
				$res1a = mssql_query($qry1a);
				$row1a = mssql_fetch_array($res1a);
				
				echo "        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "					<input type=\"hidden\" name=\"call\" value=\"yearlytrip\">\n";
				echo "					<input type=\"hidden\" name=\"subq\" value=\"ye_edit\">\n";
				echo "					<input type=\"hidden\" name=\"rid\" value=\"".$row1['id']."\">\n";
				echo "					<input type=\"hidden\" name=\"yeyear\" value=\"".$yeyear."\">\n";
				echo "					<input type=\"hidden\" name=\"addatt\" value=\"".$addatt."\">\n";
				echo "   			<tr>\n";
				echo " 			  		<td class=\"wh_und\" align=\"right\">".$ycnt.".</td>\n";
				echo " 			  		<td class=\"wh_und\" align=\"left\" width=\"125px\">".$row1a['name']."</td>\n";
				
				if ($row1['sid']!=0)
				{
					$qry1b = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row1['sid']."';";
					$res1b = mssql_query($qry1b);
					$row1b = mssql_fetch_array($res1b);
					
					echo " 			  		<td class=\"wh_und\" align=\"left\" width=\"125px\">".$row1b['lname']."</td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"left\" width=\"125px\">".$row1b['fname']."</td>\n";
				}
				else
				{
					echo " 			  		<td class=\"wh_und\" align=\"left\"><input class=\"bboxbl\" type=\"text\" name=\"lname\" value=\"".$row1['lname']."\" size=\"20\" maxlength=\"32\"></td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"left\"><input class=\"bboxbl\" type=\"text\" name=\"fname\" value=\"".$row1['fname']."\" size=\"20\" maxlength=\"32\"></td>\n";
				}
				
				echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" name=\"dob\" value=\"".$row1['dob']."\" size=\"12\" maxlength=\"11\"></td>\n";
				echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
				echo "						<select name=\"citizen\">\n";
				
				if ($row1['citizen']==1)
				{
					echo "							<option value=\"0\">No</option>\n";
					echo "							<option value=\"1\" SELECTED>Yes</option>\n";
				}
				else
				{
					echo "							<option value=\"0\" SELECTED>No</option>\n";
					echo "							<option value=\"1\">Yes</option>\n";
				}
				
				echo "						</select>\n";
				echo "					</td>\n";
				echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" name=\"passnum\" value=\"".$row1['passnum']."\" size=\"10\" maxlength=\"9\"></td>\n";
				echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" name=\"passexp\" value=\"".$row1['pexpdate']."\" size=\"12\" maxlength=\"11\"></td>\n";
				echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" name=\"no_digs\" value=\"".$row1['no_digs']."\" size=\"4\" maxlength=\"3\"></td>\n";
				echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
				echo "						<select name=\"tentative\">\n";
				
				if ($row1['tentative']==1)
				{
					echo "							<option value=\"0\">No</option>\n";
					echo "							<option value=\"1\" SELECTED>Yes</option>\n";
				}
				else
				{
					echo "							<option value=\"0\" SELECTED>No</option>\n";
					echo "							<option value=\"1\">Yes</option>\n";
				}
				
				echo "						</select>\n";
				echo "					</td>\n";
				echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
				echo "						<select name=\"buyin\">\n";
				
				if ($row1['buyin']==1)
				{
					echo "							<option value=\"0\">No</option>\n";
					echo "							<option value=\"1\" SELECTED>Yes</option>\n";
				}
				else
				{
					echo "							<option value=\"0\" SELECTED>No</option>\n";
					echo "							<option value=\"1\">Yes</option>\n";
				}
				
				echo "						</select>\n";
				echo "					</td>\n";
				echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
				echo "						<select name=\"locked\">\n";
				
				if ($row1['locked']==1)
				{
					echo "							<option value=\"0\">No</option>\n";
					echo "							<option value=\"1\" SELECTED>Yes</option>\n";
				}
				else
				{
					echo "							<option value=\"0\" SELECTED>No</option>\n";
					echo "							<option value=\"1\">Yes</option>\n";
				}
				
				echo "						</select>\n";
				echo "					</td>\n";
				echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
				
				if ($row1['locked']==1)
				{
					if ($_SESSION['rlev'] >= 9)
					{
						echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Edit\">\n";
					}
				}
				else
				{
					echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Edit\">\n";
				}
					
				echo "					</td>\n";
				echo "   			</form>\n";
				echo "        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "					<input type=\"hidden\" name=\"call\" value=\"yearlytrip\">\n";
				echo "					<input type=\"hidden\" name=\"subq\" value=\"ye_delete\">\n";
				echo "					<input type=\"hidden\" name=\"rid\" value=\"".$row1['id']."\">\n";
				echo "					<input type=\"hidden\" name=\"yeyear\" value=\"".$yeyear."\">\n";
				echo "					<input type=\"hidden\" name=\"addatt\" value=\"".$addatt."\">\n";
				echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
				
				if ($row1['locked']==1)
				{
					if ($_SESSION['rlev'] >= 9)
					{
						echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Delete\">\n";
					}
				}
				else
				{
					echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Delete\">\n";
				}
				
				echo "					</td>\n";
				echo "   			</form>\n";
				echo "   			</tr>\n";
				
				$qry2  = "SELECT ";
				$qry2 .= "	y.*, ";
				$qry2 .= "	(SELECT lname FROM security WHERE securityid=y.sid) as slname, ";
				$qry2 .= "	(SELECT fname FROM security WHERE securityid=y.sid) as sfname, ";
				$qry2 .= "	o.name ";
				$qry2 .= "FROM ";
				$qry2 .= "	tyearlytrip as y ";
				$qry2 .= "INNER JOIN ";
				$qry2 .= "	offices as o ";
				$qry2 .= "ON ";
				$qry2 .= "	y.oid=o.officeid ";
				$qry2 .= "WHERE ";
				$qry2 .= "	y.assoc='".$row1['sid']."' ";
				$qry2 .= "	and y.oid='".$row1['oid']."' ";
				$qry2 .= "	and y.assoc!='0' ";
				$qry2 .= "ORDER BY ";
				$qry2 .= "	o.name, ";
				$qry2 .= "	lname, ";
				$qry2 .= "	assoc ";
				$qry2 .= "ASC;";
				$res2 = mssql_query($qry2);
				$nrow2 = mssql_num_rows($res2);
				
				if ($nrow2 > 0)
				{
					while ($row2 = mssql_fetch_array($res2))
					{
						$ycnt++;
						echo "        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
						echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
						echo "					<input type=\"hidden\" name=\"call\" value=\"yearlytrip\">\n";
						echo "					<input type=\"hidden\" name=\"subq\" value=\"ye_edit\">\n";
						echo "					<input type=\"hidden\" name=\"rid\" value=\"".$row2['id']."\">\n";
						echo "					<input type=\"hidden\" name=\"yeyear\" value=\"".$yeyear."\">\n";
						echo "					<input type=\"hidden\" name=\"addatt\" value=\"".$addatt."\">\n";
						echo "   			<tr>\n";
						echo " 			  		<td class=\"wh_und\" align=\"right\">".$ycnt.".</td>\n";
						echo " 			  		<td class=\"wh_und\" align=\"left\" width=\"125px\">".$row2a['name']."</td>\n";
						
						if ($row2['sid']!=0)
						{
							$qry2b = "SELECT securityid,lname,fname FROM security WHERE securityid='".$row2['sid']."';";
							$res2b = mssql_query($qry2b);
							$row2b = mssql_fetch_array($res2b);
							
							echo " 			  		<td class=\"wh_und\" align=\"left\" width=\"125px\">".$row2b['lname']."</td>\n";
							echo " 			  		<td class=\"wh_und\" align=\"left\" width=\"125px\">".$row2b['fname']."</td>\n";
						}
						else
						{
							echo " 			  		<td class=\"wh_und\" align=\"left\"><input class=\"bboxbl\" type=\"text\" name=\"lname\" value=\"".$row2['lname']."\" size=\"20\" maxlength=\"32\"></td>\n";
							echo " 			  		<td class=\"wh_und\" align=\"left\"><input class=\"bboxbl\" type=\"text\" name=\"fname\" value=\"".$row2['fname']."\" size=\"20\" maxlength=\"32\"></td>\n";
						}
						
						echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" name=\"dob\" value=\"".$row2['dob']."\" size=\"12\" maxlength=\"11\"></td>\n";
						echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
						echo "						<select name=\"citizen\">\n";
						
						if ($row2['citizen']==1)
						{
							echo "							<option value=\"0\">No</option>\n";
							echo "							<option value=\"1\" SELECTED>Yes</option>\n";
						}
						else
						{
							echo "							<option value=\"0\" SELECTED>No</option>\n";
							echo "							<option value=\"1\">Yes</option>\n";
						}
						
						echo "						</select>\n";
						echo "					</td>\n";
						echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" name=\"passnum\" value=\"".$row2['passnum']."\" size=\"10\" maxlength=\"9\"></td>\n";
						echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" name=\"passexp\" value=\"".$row2['pexpdate']."\" size=\"12\" maxlength=\"11\"></td>\n";
						echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxbc\" type=\"text\" name=\"no_digs\" value=\"".$row2['no_digs']."\" size=\"4\" maxlength=\"3\"></td>\n";
						echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
						echo "						<select name=\"tentative\">\n";
						
						if ($row2['tentative']==1)
						{
							echo "							<option value=\"0\">No</option>\n";
							echo "							<option value=\"1\" SELECTED>Yes</option>\n";
						}
						else
						{
							echo "							<option value=\"0\" SELECTED>No</option>\n";
							echo "							<option value=\"1\">Yes</option>\n";
						}
						
						echo "						</select>\n";
						echo "					</td>\n";
						echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
						echo "						<select name=\"buyin\">\n";
						
						if ($row2['buyin']==1)
						{
							echo "							<option value=\"0\">No</option>\n";
							echo "							<option value=\"1\" SELECTED>Yes</option>\n";
						}
						else
						{
							echo "							<option value=\"0\" SELECTED>No</option>\n";
							echo "							<option value=\"1\">Yes</option>\n";
						}
						
						echo "						</select>\n";
						echo "					</td>\n";
						echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
						echo "						<select name=\"locked\">\n";
						
						if ($row2['locked']==1)
						{
							echo "							<option value=\"0\">No</option>\n";
							echo "							<option value=\"1\" SELECTED>Yes</option>\n";
						}
						else
						{
							echo "							<option value=\"0\" SELECTED>No</option>\n";
							echo "							<option value=\"1\">Yes</option>\n";
						}
						
						echo "						</select>\n";
						echo "					</td>\n";
						echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
						
						if ($row2['locked']==1)
						{
							if ($_SESSION['rlev'] >= 9)
							{
								echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Edit\">\n";
							}
						}
						else
						{
							echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Edit\">\n";
						}
							
						echo "					</td>\n";
						echo "   			</form>\n";
						echo "        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
						echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
						echo "					<input type=\"hidden\" name=\"call\" value=\"yearlytrip\">\n";
						echo "					<input type=\"hidden\" name=\"subq\" value=\"ye_delete\">\n";
						echo "					<input type=\"hidden\" name=\"rid\" value=\"".$row2['id']."\">\n";
						echo "					<input type=\"hidden\" name=\"yeyear\" value=\"".$yeyear."\">\n";
						echo "					<input type=\"hidden\" name=\"addatt\" value=\"".$addatt."\">\n";
						echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
						
						if ($row2['locked']==1)
						{
							if ($_SESSION['rlev'] >= 9)
							{
								echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Delete\">\n";
							}
						}
						else
						{
							echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Delete\">\n";
						}
						
						echo "					</td>\n";
						echo "   			</form>\n";
						echo "   			</tr>\n";
					}
				}
			}
		}
		else
		{
			echo "   <tr>\n";
			echo "   	<td>\n";
			echo "			<table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"gray\" align=\"left\">&nbspNo Scheduled Year End Trip Atttendees for ".$_POST['yeyear']."</td>\n";
			echo "   			</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		
		if (!empty($_POST['addatt']) && $_POST['addatt']==1) // Add logic to exclude this option for BHNM
		{
			echo "   <tr>\n";
			echo "   	<td>\n";
			echo "			<hr width=\"80%\">\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			
			$nsid_ar=array();
			$qry9b = "SELECT sid FROM tyearlytrip WHERE oid='".$_SESSION['officeid']."' AND tyear='".$_POST['yeyear']."';";
			$res9b = mssql_query($qry9b);
			
			while($row9b = mssql_fetch_array($res9b))
			{
				$nsid_ar[]=$row9b['sid'];
			}
			
			//echo $qry9b."<br>";
			$qry9a = "SELECT securityid,lname,fname FROM security WHERE officeid='".$_SESSION['officeid']."' and SUBSTRING(slevel,13,13) >= 1 order by lname ASC;";
			$res9a = mssql_query($qry9a);
			$nrow9a= mssql_num_rows($res9a);
			
			//echo $qry9a."<br>";
			echo "   <tr>\n";
			echo "   	<td>\n";
			echo "			<table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"gray\" colspan=\"6\" align=\"left\">&nbsp<b>Unscheduled Staff Attendees</b></td>\n";
			echo " 			  		<td class=\"gray\" colspan=\"7\" align=\"center\">&nbsp<b>All Blank Fields must be filled in.</b></td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" width=\"125px\">&nbsp</td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\" width=\"125px\"><b>Last Name</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"left\" width=\"125px\"><b>First Name</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>DoB</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Citizenship</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Passport Number</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Passport Expiration</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b># of Digs</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Tentative?</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Buy In?</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"><b>Locked?</b></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"></td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\"></td>\n";
			echo "   			</tr>\n";
			
			//print_r($nsid_ar);
			
			//while($row9a = mssql_fetch_array($res9a))
			//{
				//if (!in_array($row9a['securityid'],$nsid_ar))
				//{
					echo "        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
					echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
					echo "					<input type=\"hidden\" name=\"call\" value=\"yearlytrip\">\n";
					echo "					<input type=\"hidden\" name=\"subq\" value=\"ye_add\">\n";
					echo "					<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
					echo "					<input type=\"hidden\" name=\"yeyear\" value=\"".$yeyear."\">\n";
					echo "					<input type=\"hidden\" name=\"addatt\" value=\"".$addatt."\">\n";
					echo "   			<tr>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\">&nbsp</td>\n";
					echo " 			  		<td colspan=\"2\" class=\"wh_und\" align=\"left\">\n";
					echo "						<select name=\"sid\" width=\"110px\">\n";
					
					while($row9a = mssql_fetch_array($res9a))
					{
						if (!in_array($row9a['securityid'],$nsid_ar))
						{
							echo "							<option value=\"".$row9a['securityid']."\">".$row9a['lname'].",&nbsp&nbsp&nbsp&nbsp".$row9a['fname']."</option>\n";	
						}
					}
					
					echo "						</select>\n";
					echo "					</td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" name=\"dob\" size=\"12\" maxlength=\"11\"></td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
					echo "						<select name=\"citizen\">\n";
					echo "							<option value=\"1\" DEFAULT>Yes</option>\n";
					echo "							<option value=\"0\">No</option>\n";
					echo "						</select>\n";
					echo "					</td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" name=\"passnum\" size=\"10\" maxlength=\"9\"></td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" name=\"passexp\" size=\"12\" maxlength=\"11\"></td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" name=\"no_digs\" size=\"4\" maxlength=\"3\"></td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
					echo "						<select name=\"tentative\">\n";
					echo "							<option value=\"0\">No</option>\n";
					echo "							<option value=\"1\">Yes</option>\n";
					echo "						</select>\n";
					echo "					</td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
					echo "						<select name=\"buyin\">\n";
					echo "							<option value=\"0\">No</option>\n";
					echo "							<option value=\"1\">Yes</option>\n";
					echo "						</select>\n";
					echo "					</td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
					echo "						<select name=\"locked\">\n";
					echo "							<option value=\"0\">No</option>\n";
					echo "							<option value=\"1\">Yes</option>\n";
					echo "						</select>\n";
					echo "					</td>\n";
					echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
					echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Add\">\n";
					echo "					</td>\n";
					echo " 			  		<td class=\"ltgray_und\" align=\"center\" width=\"60px\">&nbsp</td>\n";
					echo "   			</tr>\n";
					echo "   			</form>\n";
				//}
			//}
			
			$nsid2_ar=array();
			$qry9c = "SELECT sid FROM tyearlytrip WHERE oid='".$_SESSION['officeid']."' AND sid!='0';";
			$res9c = mssql_query($qry9c);
			
			while($row9c = mssql_fetch_array($res9c))
			{
				$nsid2_ar[]=$row9c['sid'];
			}
			
			echo "   			<tr>\n";
			echo " 			  		<td class=\"gray\" colspan=\"13\" align=\"left\">&nbsp</td>\n";
			echo "   			</tr>\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"gray\" colspan=\"13\" align=\"left\"><b>Unscheduled Non Staff Attendees</b></td>\n";
			echo "   			</tr>\n";
			echo "        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
			echo "					<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
			echo "					<input type=\"hidden\" name=\"call\" value=\"yearlytrip\">\n";
			echo "					<input type=\"hidden\" name=\"subq\" value=\"ye_add\">\n";
			echo "					<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "					<input type=\"hidden\" name=\"sid\" value=\"0\">\n";
			echo "					<input type=\"hidden\" name=\"yeyear\" value=\"".$yeyear."\">\n";
			echo "					<input type=\"hidden\" name=\"addatt\" value=\"".$addatt."\">\n";
			echo "   			<tr>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
			echo "						<select name=\"assoc\">\n";
			echo "							<option value=\"0\"></option>\n";
			
			foreach ($nsid2_ar as $n2 => $v2)
			{
				$qry9d = "SELECT securityid,lname,fname FROM security WHERE securityid='".$v2."';";
				$res9d = mssql_query($qry9d);
				$row9d = mssql_fetch_array($res9d);
				
				echo "							<option value=\"".$row9d['securityid']."\">".$row9d['lname'].",&nbsp&nbsp&nbsp&nbsp".$row9d['fname']."</option>\n";	
			}
			
			echo "						</select>\n";
			echo "					</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"lname\" size=\"20\" maxlength=\"32\"></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"left\"><input class=\"bboxl\" type=\"text\" name=\"fname\" size=\"20\" maxlength=\"32\"></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" name=\"dob\" size=\"12\" maxlength=\"11\"></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
			echo "						<select name=\"citizen\">\n";
			echo "							<option value=\"1\" DEFAULT>Yes</option>\n";
			echo "							<option value=\"0\">No</option>\n";
			echo "						</select>\n";
			echo "					</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" name=\"passnum\" size=\"10\" maxlength=\"9\"></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" name=\"passexp\" size=\"12\" maxlength=\"11\"></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\"><input class=\"bboxl\" type=\"text\" name=\"no_digs\" size=\"4\" maxlength=\"3\"></td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
			echo "						<select name=\"tentative\">\n";
			echo "							<option value=\"0\">No</option>\n";
			echo "							<option value=\"1\">Yes</option>\n";
			echo "						</select>\n";
			echo "					</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
			echo "						<select name=\"buyin\">\n";
			echo "							<option value=\"0\">No</option>\n";
			echo "							<option value=\"1\">Yes</option>\n";
			echo "						</select>\n";
			echo "					</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
			echo "						<select name=\"locked\">\n";
			echo "							<option value=\"0\">No</option>\n";
			echo "							<option value=\"1\">Yes</option>\n";
			echo "						</select>\n";
			echo "					</td>\n";
			echo " 			  		<td class=\"wh_und\" align=\"center\">\n";
			echo "						<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Add\">\n";
			echo "					</td>\n";
			echo " 			  		<td class=\"ltgray_und\" align=\"center\" width=\"60px\">&nbsp</td>\n";
			echo "   			</tr>\n";
			echo "   			</form>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		
		echo "</table>\n";
	}
}

function sm_lists()
{
	
}

function trpmenu()
{
	$brdr=0;

	if (!empty($_POST['yeyear']) && is_numeric($_POST['yeyear']))
	{
		$yeyear=$_POST['yeyear'];
	}
	else
	{
		$yeyear="";
	}
	
	if (!empty($_POST['smyear']) && is_numeric($_POST['smyear']))
	{
		$smyear=$_POST['smyear'];
	}
	else
	{
		$smyear="";
	}

	if (isset($_POST['print']) && $_POST['print']==1)
	{
		echo "<div class=\"noPrint\">\n";
	}

	echo "<table class=\"outer\" width=\"100%\">\n";
	echo "   <tr>\n";
	echo "   	<td class=\"gray\">\n";
	echo "			<table width=\"100%\" border=\"".$brdr."\">\n";
	echo "   			<tr>\n";
	echo " 			  		<td align=\"left\" valign=\"bottom\">&nbsp<b>Trip Menu: </b>&nbsp&nbsp".$_SESSION['offname']."</td>\n";
	echo "			   	<td align=\"right\">\n";
	echo "						<table border=\"".$brdr."\">\n";
	echo "   						<tr>\n";
	echo "      						<td align=\"center\" valign=\"bottom\" colspan=\"4\">&nbsp</td>\n";
	echo "      						<td align=\"center\">Add</td>\n";
	echo "   						</tr>\n";
	echo "   						<tr>\n";
	echo " 				        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "									<input type=\"hidden\" name=\"call\" value=\"yearlytrip\">\n";
	echo "									<input type=\"hidden\" name=\"subq\" value=\"listyecurrent\">\n";
	echo "      						<td align=\"right\" valign=\"bottom\"><b>Year End Trip for Year:</b></td>\n";
	echo "      						<td align=\"center\" valign=\"bottom\"><b>2007</b>\n";
	//echo "									<input class=\"bboxl\" type=\"text\" name=\"yeyear\" value=\"".$yeyear."\" size=\"8\" maxlength=\"4\" title=\"Enter Year\">\n";
	echo "									<input type=\"hidden\" name=\"yeyear\" value=\"2007\">\n";
	echo "								</td>\n";
	echo "      						<td align=\"right\" valign=\"bottom\">order by</td>\n";
	echo "      						<td align=\"center\" valign=\"bottom\">\n";
	echo "									<select name=\"order\">\n";
	echo "										<option value=\"last\">Last Name</option>\n";
	echo "									</select>\n";
	echo "								</td>\n";
	echo "      						<td align=\"center\" valign=\"bottom\">\n";
	
	if (!empty($_POST['addatt']) && $_POST['addatt']==1)
	{
		echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"addatt\" value=\"1\" title=\"Check box to Add Attendees\" CHECKED>\n";
	}
	else
	{
		echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"addatt\" value=\"1\" title=\"Check box to Add Attendees\">\n";
	}
	
	echo "								</td>\n";
	echo "      						<td align=\"center\" valign=\"bottom\">\n";
	echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"List Attendees\">\n";
	echo "								</td>\n";
	echo "         					</form>\n";
	
	/*
	echo " 				        		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "									<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "									<input type=\"hidden\" name=\"call\" value=\"yearlytrip\">\n";
	echo "									<input type=\"hidden\" name=\"subq\" value=\"listsmcurrent\">\n";
	echo "      						<td align=\"right\" valign=\"bottom\"><b>Sales Meeting for Year:</b></td>\n";
	echo "      						<td align=\"center\" valign=\"bottom\">\n";
	echo "									<input class=\"bboxl\" type=\"text\" name=\"smyear\" value=\"".$smyear."\" size=\"8\" maxlength=\"4\" title=\"Enter Year\">\n";
	echo "								</td>\n";
	echo "      						<td align=\"right\" valign=\"bottom\">order by</td>\n";
	echo "      						<td align=\"center\" valign=\"bottom\">\n";
	echo "									<select name=\"order\">\n";
	echo "										<option value=\"last\">Last Name</option>\n";
	echo "									</select>\n";
	echo "								</td>\n";
	echo "      						<td align=\"center\" valign=\"bottom\">\n";
	
	if (!empty($_POST['addatt']) && $_POST['addatt']==1)
	{
		echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"addatt\" value=\"1\" title=\"Check box to Add Attendees\" CHECKED>\n";
	}
	else
	{
		echo "									<input class=\"checkboxgry\" type=\"checkbox\" name=\"addatt\" value=\"1\" title=\"Check box to Add Attendees\">\n";
	}
	
	echo "								</td>\n";
	echo "      						<td align=\"center\" valign=\"bottom\">\n";
	echo "									<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"List Attendees\">\n";
	echo "								</td>\n";
	echo "         					</form>\n";
	*/
	
	echo "   						</tr>\n";
	echo "						</table>\n";
	echo "   				</td>\n";
	echo "		   	</tr>\n";
	echo "			</table>\n";
	echo "   	</td>\n";
	echo "   </tr>\n";
	echo "</table>\n";

	if (isset($_POST['print']) && $_POST['print']==1)
	{
		echo "</div>\n";
	}
	//echo "&nbsp";
}

function base_matrix()
{
	$brdr=1;
	error_reporting(E_ALL);
	
	$qry = "SELECT entripinfo FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$_SESSION['entripinfo']	=$row['entripinfo'];
	
	trpmenu();

	if (isset($_POST['subq']))
	{
		echo "<table width=\"80%\">\n";
		//echo "<table class=\"outer\">\n";
		echo "   <tr>\n";
		echo "   	<td>\n";

		if ($_POST['subq']=="listyecurrent")
		{
			ye_lists();
		}
		elseif ($_POST['subq']==="listsmcurrent")
		{
			sm_lists();
		}
		elseif ($_POST['subq']==="ye_add")
		{
			ye_add();
		}
		elseif ($_POST['subq']==="ye_edit")
		{
			ye_edit();
		}
		elseif ($_POST['subq']==="ye_delete")
		{
			ye_delete();
		}

		echo "   	</td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}

	echo "</table>\n";
	//echo "</div>\n";
}

?>