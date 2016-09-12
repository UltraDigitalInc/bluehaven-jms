<?php

session_start();

if (isset($_SESSION['plogin']))
{
	header("Cache-control: private");
	include (".\connect_db.php");
	include (".\common_func.php");

	//Begin Special code looop for Quotes
	// *Note* Remove when integration is complete
	if (isset($_REQUEST['call']) and $_REQUEST['call']=='view_retail' and isset($_REQUEST['estid'])) {
		$qryE= "select estid,officeid,esttype from jest..est where officeid=".$_SESSION['officeid']." and estid=".$_REQUEST['estid'].";";
		$resE= mssql_query($qryE);
		$rowE= mssql_fetch_array($resE);
		$nrowE=mssql_num_rows($resE);
		
		if ($nrowE > 0)
		{
			$_SESSION['etype']=$rowE['esttype'];
		}
	}
	elseif (isset($_REQUEST['esttype']) && $_REQUEST['esttype']=='Q') {
		$_SESSION['etype']='Q';
	}
	elseif (isset($_REQUEST['esttype']) && $_REQUEST['esttype']=='E') {
		$_SESSION['etype']='E';
	}
	else {
		unset($_SESSION['etype']);
	}
	
	if (isset($_REQUEST['action']) && $_REQUEST['action']=='est' && isset($_SESSION['etype']) && $_SESSION['etype']=='Q') {
		//ini_set('display_errors','On');
		include (".\calc_func_quote.php");
		include (".\display_func_quote.php");
	}
	else {
		include (".\calc_func.php");
		include (".\display_func.php");
	}
	//End Quote Special Code Loop
	
	if (timeout()==1) {
		last_access();
		do_website();
	}
	else {
		sess_expired();
	}
}
else
{
//echo 'TEST1<br>';
	if (!isset($_REQUEST['plogin']) || strlen($_REQUEST['plogin']) < 4) {
		header("Cache-control: private");
		include (".\connect_db.php");
		?>
		<!doctype html>
		<html>
			<head>
				<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
				<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
				<link rel="stylesheet" type="text/css" href="css/bh_front.css">
				<title>Blue Haven Pools and Spas Job Management System</title>
			</head>
			<body onLoad="window.name = 'JMSauth';">
				<div class="outerrnd" style="width:450px; margin:25px auto;">
					<table align="center" width="400px">						
						<tr>
							<td align="center"><br>
								<img class="img-rounded" src="./images/bh_logo.jpg">
							</td>
							<td valign="bottom" width="300px">
								<p>
									<h4>Terms of Use</h4>
									<span id="tos">
									Any information collected, downloaded and/or created on this system is the exclusive property of Blue Haven Pools & Spas
									and may not be copied or transmitted to any outside party or used for any purpose not directly related to the business of the company.
									</span>
								</p>
							</td>
						</tr>
						<tr>
							<td valign="top" align="center">All access logged<br> and monitored.</td>
							<td valign="bottom" align="left">
								<p>
								<form class="form-horizontal" role="form" id="logform" method="post" target="_top">
									<div class="form-group">
										<div class="col-lg-7">
											<input class="form-control input-sm" id="inp_login" type="text" name="plogin" size="10" maxlength="8" align="left" AUTOCOMPLETE="off">
										</div>
										<label for="inp_login" class="col-lg-3 control-label">User ID</label><br>
									</div>
									<div class="form-group">
										<div class="col-lg-7">
											<input class="form-control input-sm" id="inp_passwd" type="password" name="pswd" size="10" maxlength="8" align="left" AUTOCOMPLETE="off">
										</div>
										<label for="inp_passwd" class="col-lg-3 control-label">Password</label>
									</div>
									<?php
									if (!isset($_COOKIE['bhsysterms']) || $_COOKIE['bhsysterms']!=1) {
										echo "<input class=\"transnb\" id=\"inp_accept\"type=\"checkbox\" name=\"accept\" value=\"1\" align=\"left\" AUTOCOMPLETE=\"off\" title=\"Check this box to accept the Terms of Use. Required for Login.\"><label for=\"inp_accept\">I Accept</label><br>\n";
									}
									else {
										echo "<input type=\"hidden\" name=\"accept\" value=\"1\">\n";
									}
									?>
									<button type="submit" class="btn btn-default">Login</button>
								</form>
								</p>
							</td>
						</tr>
					</table>
				</div>
				<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
				<script src="bootstrap/js/bootstrap.min.js"></script>
				<script src="js/jquery.front.js"></script>
			</body>
		</html>
		<?php
	}
	elseif (isset($_REQUEST['plogin'])) {
		if (!isset($_COOKIE['bhsysterms']) || $_COOKIE['bhsysterms'] != 1) {
			if (isset($_REQUEST['accept']) && $_REQUEST['accept']==1) {
				// Terms Acceptance Cookie expires after 90 days
				setcookie('bhsysterms','1',time() + (60*60*24*90));
			}
		}
		
		header("Cache-control: private");
		
		include (".\connect_db.php");
		include (".\common_func.php");
		include (".\display_func.php");

		$qry = "SELECT SYS_NAME,SYS_VER,SYS_ENV FROM jest..jest_config;";
		$res  = mssql_query($qry);
		$row  = mssql_fetch_array($res);

		$plogin = strip_tags($_REQUEST['plogin']);

		$qry  = "SELECT * FROM security WHERE login='".$plogin."';";
		$res  = mssql_query($qry);
		$row  = mssql_fetch_array($res);
		$nrow = mssql_num_rows($res);

		$slevel=explode(",",$row['slevel']);
		$pswd	= strip_tags($_REQUEST['pswd']);
		$pswd	= md5($pswd);

		if ($nrow==0) // Logon Error 1 (Not Exist)
		{
			echo "<html>\n";
			echo "	<head>\n";
			echo "		<title>JMS Logon Error (Error Type: 1)</title>\n";
			echo "		<link rel=\"stylesheet\" type=\"text/css\" href=\"./bh_front.css\" />\n";
			echo "	</head>\n";
			echo "	<body onLoad=\"window.name = 'JMSmain'\">\n";
			echo "<table align=\"center\" border=\"0\">";
			echo "   <tr><td><font color=\"red\"><b>Logon ID Not found</b></font></td></tr>";
			echo "   <tr><td>Click <a href=\"".$_SERVER['PHP_SELF']."\" target=\"_top\">HERE</a> to try again</td></tr>";
			echo "   <tr><td>Contact <b>Management</b> if this Error persists. 619-233-3522 x10111</td></tr>";
			echo "</table>";
		}
		elseif ($slevel[6]==0) // Logon Error 2 (Disabled)
		{
			echo "<html>\n";
			echo "	<head>\n";
			echo "		<title>JMS Logon Error (Error Type: 2)</title>\n";
			echo "		<link rel=\"stylesheet\" type=\"text/css\" href=\"./bh_front.css\" />\n";
			echo "	</head>\n";
			echo "	<body onLoad=\"window.name = 'JMSmain'\">\n";
			echo "<table align=\"center\" border=\"0\">";
			echo "   <tr><td><font color=\"red\"><b>Your account has been disabled</b></font></td></tr>";
			echo "   <tr><td>Contact <b>Management</b> if this Error persists. 619-233-3522 x10111</td></tr>";
			echo "</table>";
		}
		elseif ($pswd!=$row['pswd']) // Logon Error Type 4 (Authentication)
		{
			echo "<html>\n";
			echo "	<head>\n";
			echo "		<title>JMS Logon Error (Error Type: 4)</title>\n";
			echo "		<link rel=\"stylesheet\" type=\"text/css\" href=\"./bh_front.css\" />\n";
			echo "	</head>\n";
			echo "	<body onLoad=\"window.name = 'JMSmain'\">\n";
			echo "<table align=\"center\" border=\"0\">";
			echo "   <tr><td><font color=\"red\"><b>Password Incorrect</b></font></td></tr>";
			echo "   <tr><td>Click <a href=\"".$_SERVER['PHP_SELF']."\" target=\"_top\">HERE</a> to try again</td></tr>";
			echo "   <tr><td>Contact <b>Management</b> if this Error persists. 619-233-3522 x10111</td></tr>";
			echo "</table>";
		}
		elseif (isset($_REQUEST['action']) && $_REQUEST['action']=="reset")
		{
			$qryA = "DELETE FROM logstate WHERE securityid='".$row['securityid']."';";
			$resA = mssql_query($qryA);

			echo "<font><b>Login Reset!</b> Stand By for Login...</font>";

			deletesessionid($row['sessionid']);

			echo "<META HTTP-EQUIV=REFRESH CONTENT=\"1;URL=".$_SERVER['PHP_SELF']."\">";
		}
		else
		{
			$qry0  = "SELECT * FROM logstate WHERE securityid=".$row['securityid'].";";
			$res0  = mssql_query($qry0);
			$row0  = mssql_fetch_array($res0);
			$nrow0 = mssql_num_rows($res0);

			if ($nrow0 > 0) // Logon Error 3 (Reset)
			{
				echo "<html>\n";
				echo "	<head>\n";
				echo "		<title>JMS Logon Error (Error Type: 3)</title>\n";
				echo "		<link rel=\"stylesheet\" type=\"text/css\" href=\"./bh_front.css\" />\n";
				echo "	</head>\n";
				echo "	<body onLoad=\"window.name = 'JMSmain'\">\n";
				echo "<table  align=\"center\" border=\"0\">\n";
				echo "   <tr>\n";
				echo "		<td><b>Logon Error</b> (Error Type: 3)</td>\n";
				echo "	</tr>";
				echo "   <tr>\n";
				echo "		<td>You are logged in already.</td>\n";
				echo "	</tr>\n";
				echo "   <tr>\n";
				echo "   <td>Contact <b>Management</b> if this Error persists. 619-233-3522 x10111</td>";
				echo "	</tr>";
				echo "   <tr>\n";
				echo "		<td>Enter your Password to Reset Login:</td>\n";
				echo "	</tr>\n";
				//echo "		<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
				echo "		<form method=\"post\">\n";
				echo "   <tr>\n";
				echo "		<td>\n";
				echo "         <input type=\"hidden\" name=\"action\" value=\"reset\">\n";
				echo "         <input type=\"hidden\" name=\"plogin\" value=\"".$plogin."\">\n";
				echo "         <input type=\"password\" name=\"pswd\" size=\"10\" maxlength=\"8\">\n";
				echo "         <button type=\"submit\">Reset Login</button>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "		</form>\n";
				echo "</table>";
			}
			elseif (!isset($_REQUEST['accept']) || $_REQUEST['accept']!=1) // Logon Error 5 (Use Policy)
			{
				echo "<html>\n";
				echo "	<head>\n";
				echo "		<title>JMS Logon Error (Error Type: 5)</title>\n";
				echo "		<link rel=\"stylesheet\" type=\"text/css\" href=\"./bh_front.css\" />\n";
				echo "	</head>\n";
				echo "	<body onLoad=\"window.name = 'JMSmain'\">\n";
				echo "<table align=\"center\" border=\"0\">";
				echo "   <tr><td><b>Logon Error</b> (Error Type: 5)</td></tr>";
				echo "   <tr><td>Please read the Terms of Use Policy and check the \"I Accept\" box to log into the system.</td></tr>";
				echo "   <tr><td>Click <a href=\"".$_SERVER['PHP_SELF']."\" target=\"_top\">HERE</a> to try again</td></tr>";
				echo "</table>";
			}
			else
			{
				$pswd   = strip_tags($_REQUEST['pswd']);
				$pswd   = md5($pswd);

				if ($row['pswd']!=$pswd) // Logon Error 5 (Authentication)
				{
					authfailed($plogin);
					exit;
				}
				else
				{
					//$sessid	=session_id();
					set_session_state($row['securityid']);
					postlogin();

					if (timeout()==1)
					{
						do_website();
					}
					else
					{
						sess_expired();
					}

					echo "<br>";
					exit;
				}
			}
		}

		echo "</body>\n";
		echo "</html>";
	}
//echo 'TEST2<br>';
}
