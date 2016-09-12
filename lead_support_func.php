<?php

function cform_TRACK()
{
	$officeid 	=$_SESSION['officeid'];
	$dates		=dateformat();
	$uid		=md5(session_id().time()).".".$_SESSION['securityid'];
	$acclist	=explode(",",$_SESSION['aid']);
	$curryr		=date("Y");
	$futyr 		=$curryr+1;

	$qryA = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
    $rowA = mssql_fetch_row($resA);
	$nrowsA = mssql_num_rows($resA);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT * FROM jest..states ORDER BY abrev ASC;";
	$resC = mssql_query($qryC);
	$nrowsC = mssql_num_rows($resC);

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=2 AND statusid!=0 AND access!=9 AND provided=0 ORDER by name ASC;";
	$resG = mssql_query($qryG);

	$hlpnd=1;

	echo "<script type=\"text/javascript\" src=\"js/validate_form.js\"></script>\n";
	echo "<table width=\"950px\" align=\"center\" border=0>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "		<table width=\"100%\" align=\"center\">\n";
	echo "			<tr>\n";
	echo "				<td>\n";
	echo "                  <form method=\"post\" onSubmit=\"return FormValidate();\">\n";
	echo "                  <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "                  <input type=\"hidden\" name=\"call\" value=\"add\">\n";
	echo "                  <input type=\"hidden\" name=\"recdate\" value=\"". time() ."\">\n";
	echo "                  <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "                  <input type=\"hidden\" name=\"comments\" value=\"\">\n";
    echo "                  <input type=\"hidden\" name=\"officeid\" value=\"".$rowA[0]."\">\n";
    echo "                  <input type=\"hidden\" name=\"estorig\" value=\"2087\">\n";
    echo "                  <input type=\"hidden\" name=\"opt1\" value=\"0\">\n";
	echo "                  <input type=\"hidden\" name=\"opt2\" value=\"0\">\n";
	echo "                  <input type=\"hidden\" name=\"opt3\" value=\"0\">\n";
	echo "                  <input type=\"hidden\" name=\"opt4\" value=\"0\">\n";
	echo "					<input type=\"hidden\" name=\"ccounty\">\n";
	echo "					<input type=\"hidden\" name=\"cmap\">\n";
	echo "					<table border=\"0\" width=\"100%\">\n";
	echo "						<tr>\n";
	echo "							<td>\n";
	echo "								<table border=\"0\" width=\"100%\">\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" align=\"left\"><b>Date</b> ". date('m/d/Y g:i A',time()) ."</td>\n";
	echo "													<td class=\"gray\" align=\"right\"><b>Office</b> ".$rowA[1]."</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "												<tr class=\"tblhd\">\n";
	echo "													<td height=\"20px\"><b>Contact</b></td>\n";
	echo "													<td height=\"20px\" align=\"right\">\n";

	HelpNode('cformatrackddcustomerpanel',$hlpnd++);

	echo "													</td>\n";
	echo "												</tr>\n";
    echo "												<tr>\n";
	echo "													<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
    echo "											            <table width=\"100%\">\n";
    echo "												<tr>\n";
    echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Company Name</td>\n";
    echo "													<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"cpname\" id=\"cpname\"></td>\n";
    echo "												</tr>\n";
    echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">First Name</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"cfname\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Last Name</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"clname\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Phone</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Cell</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Fax</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">E-Mail</td>\n";
	echo "													<td class=\"gray\" align=\"left\"><input type=\"text\" name=\"cemail\" id=\"cemail\" size=\"30\"></td>\n";
	echo "												</tr>\n";
    echo "											            </table>\n";
    echo "												    </tr>\n";
    echo "										        </td>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "										<td valign=\"top\" align=\"left\">\n";
	echo "											<table class=\"outer\" border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "												<tr class=\"tblhd\">\n";
	echo "													<td height=\"20px\" ><b>Address</b></td>\n";
	echo "													<td height=\"20px\" align=\"right\">\n";

	HelpNode('cformtrackaddaddresspanel',$hlpnd++);

	echo "													</td>\n";
	echo "												</tr>\n";
    echo "												<tr>\n";
	echo "													<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
    echo "											            <table width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Street</td>\n";
	echo "													<td class=\"gray\"><input type=\"text\" size=\"50\" name=\"caddr1\"></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">City</td>\n";
	echo "													<td class=\"gray\"><input type=\"text\" size=\"20\" name=\"ccity\"></td>\n";
	echo "												</tr>\n";
    echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">State</td>\n";
	echo "													<td class=\"gray\">\n";
	echo "														<select name=\"cstate\">\n";
	
	while ($rowC=mssql_fetch_array($resC))
	{
		echo "														<option value=\"".$rowC['abrev']."\">".$rowC['abrev']."</option>\n";
	}
	
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td class=\"gray\" width=\"100px\" align=\"right\">Zip</td>\n";
	echo "													<td class=\"gray\"><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\"></td>\n";
	echo "												</tr>\n";
	echo "                          					<tr>\n";
    echo "                              					<td class=\"gray\" align=\"center\" colspan=\"2\"><hr width=\"90%\"></td>\n";
    echo "                          					</tr>\n";
	echo "                          					<tr>\n";
    echo "                              					<td class=\"gray\" width=\"100px\" align=\"right\">Market</td>\n";
    echo "                              					<td class=\"gray\" align=\"left\">\n";
	echo "														<input type=\"text\" size=\"40\" name=\"market\">\n";
	echo "													</td>\n";
    echo "                          					</tr>\n";
	echo "                          					<tr>\n";
    echo "                              					<td class=\"gray\" width=\"100px\" align=\"right\">Type</td>\n";
    echo "                              					<td class=\"gray\" align=\"left\">\n";
	echo "														<input type=\"text\" size=\"10\" name=\"cptype\">\n";
	echo "													</td>\n";
    echo "                          					</tr>\n";
	echo "                          					<tr>\n";
    echo "                              					<td class=\"gray\" width=\"100px\" align=\"right\">Service</td>\n";
    echo "                              					<td class=\"gray\" align=\"left\">\n";
	echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackservice\" value=\"1\">\n";	echo "													</td>\n";
    echo "                          					</tr>\n";
	echo "                          					<tr>\n";
    echo "                              					<td class=\"gray\" width=\"100px\" align=\"right\">Renovations</td>\n";
    echo "                              					<td class=\"gray\" align=\"left\">\n";
	echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackrepair\" value=\"1\">\n";
	echo "													</td>\n";
    echo "                          					</tr>\n";
	echo "											            </table>\n";
    echo "												    </tr>\n";
    echo "										        </td>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"left\" valign=\"top\">\n";
	echo "											<table border=0 class=\"outer\" width=\"100%\" height=\"100\">\n";
	echo "                           				    <tr class=\"tblhd\">\n";
	echo "												    <td height=\"20px\" ><b>Appointment / Source</b></td>\n";
	echo "													<td height=\"20px\" align=\"right\">\n";

	HelpNode('cformaddappointmentpanel',$hlpnd++);

	echo "													</td>\n";
	echo "                           					</tr>\n";
	echo "                     							<tr>\n";
	echo "                        						    <td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
	echo "                           					    	<table border=\"0\" width=\"100%\">\n";
	echo "															<tr>\n";
	echo "																<td align=\"right\">Date</td>\n";
	echo "																<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "																	<table border=0>\n";
	echo "																		<tr>\n";
	echo "																			<td valign=\"top\">\n";
	echo "                                             								    <select name=\"appt_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		echo "                                             											<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             								    </select>\n";
	echo "																			</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">\n";
	echo "                                             									<select name=\"appt_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		echo "                                             											<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             									</select>\n";
	echo "																			</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "																			<td valign=\"left\" valign=\"top\">\n";
	echo "                                             									<select name=\"appt_yr\">\n";
	echo "                                             										<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr; $yr<=$futyr; $yr++)
	{
		echo "                                             											<option value=\"".$yr."\">".$yr."</option>\n";
	}

	echo "                                             									</select>\n";
	echo "																			</td>\n";
	echo "																		</tr>\n";
	echo "																	</table>\n";
	echo "                           									</td>\n";
	echo "                           								</tr>\n";
	echo "                           								<tr>\n";
	echo "																<td align=\"right\">Time</td>\n";
	echo "																<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "																	<table border=0>\n";
	echo "																		<tr>\n";
	echo "																			<td align=\"left\" valign=\"top\">\n";
	echo "                                             									<select name=\"appt_hr\">\n";

	for ($hr=0; $hr<=12; $hr++)
	{
		echo "                                             											<option value=\"".$hr."\">".$hr."</option>\n";
	}

	echo "                                             									</select>\n";
	echo "                                             								</td>\n";
	echo "                                             								<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "                                             								<td valign=\"left\" valign=\"top\">\n";
	echo "                                             									<select name=\"appt_mn\">\n";

	for ($mn=0; $mn<=60; $mn++)
	{
		echo "                                             											<option value=\"".$mn."\">".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
	}

	echo "                                             									</select>\n";
	echo "                                             								</td>\n";
	echo "                                             								<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "                                             								<td valign=\"left\" valign=\"top\">\n";
	echo "                                             									<select name=\"appt_pa\">\n";
	echo "                                             										<option value=\"1\">AM</option>\n";
	echo "                                             										<option value=\"2\">PM</option>\n";
	echo "                                             									</select>\n";
	echo "                                             								</td>\n";
	echo "                                             							</tr>\n";
	echo "                                             						</table>\n";
	echo "                                             					</td>\n";
	echo "                                             				</tr>\n";
	echo "                                             				<tr>\n";
	echo "                                             					<td align=\"right\">Lead Source</td>\n";
	echo "                                             					<td align=\"left\">\n";
	echo "                                             						<select name=\"source\">\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		echo "                                             								<option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
	}

	echo "                                             						</select>\n";
	echo "                                             					</td>\n";
	echo "                                             				</tr>\n";
	echo "                                             			</table>\n";
	echo "                                             		</td>\n";
	echo "                                             	</tr>\n";
	echo "                                          </table>\n";
	echo "                                      </td>\n";
	echo "                                      <td colspan=\"2\" align=\"right\" valign=\"top\">\n";
	echo "                                          <table border=\"0\" class=\"outer\" width=\"100%\" height=\"100\">\n";
	echo "                                             	<tr class=\"tblhd\">\n";
	echo "                                             		<td height=\"20px\"><b>Contact Comments</b></td>\n";
	echo "                                                  <td height=\"20px\" align=\"right\">\n";

	HelpNode('cformaddcommentpanel',$hlpnd++);

	echo "                                                  </td>\n";
	echo "                                              </tr>\n";
	echo "                                              <tr>\n";
	echo "                                                  <td colspan=\"2\" class=\"gray\" valign=\"top\" align=\"left\">\n";
	echo "                                                      <textarea name=\"ccomments\" rows=\"5\" cols=\"75\"></textarea>\n";
	echo "                                                  </td>\n";
	echo "                                              </tr>\n";
	echo "                                          </table>\n";
	echo "                                      </td>\n";
	echo "                                  </tr>\n";
	echo "                              </table>\n";
	echo "                          </td>\n";
	echo "                      </tr>\n";
	echo "                      </table>\n";
	echo "  				</td>\n";
	echo "  			</tr>\n";
//    echo "              <tr>\n";
//	echo "                  <td colspan=\"2\" valign=\"top\">\n";
//	echo "                      <table border=\"0\" width=\"100%\">\n";
//	echo "							<tr>\n";
//	echo "                        		<td colspan=\"2\" valign=\"top\">\n";
//	echo "                          	   	<table width=\"100%\">\n";
//	echo "										<tr>\n";
//	echo "											<td>\n";
//	echo "												<table class=\"outer\" width=\"100%\">\n";
//    echo "                          						<tr class=\"tblhd\">\n";
//    echo "                              						<td height=\"20px\" colspan=\"2\" align=\"left\"><b>Marketing</b></td>\n";
//    echo "                          						</tr>\n";
////	echo "                          						<tr>\n";
////    echo "                              						<td class=\"gray\" width=\"100px\" align=\"right\">Market Area</td>\n";
////    echo "                              						<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"market\"></td>\n";
////    echo "                          						</tr>\n";
//    echo "                          						<tr>\n";
//    echo "                          						    <td class=\"gray\" width=\"100px\" align=\"right\" valign=\"top\">Comments</td>\n";
//    echo "                          						    <td class=\"gray\" align=\"left\"><textarea name=\"mrktproc\" rows=\"5\" cols=\"100\"></textarea></td>\n";
//    echo "                          						</tr>\n";
//	echo "												</table>\n";
//	echo "                                      	</td>\n";
//	echo "                                  	</tr>\n";
//	echo "                              	</table>\n";
//	echo "								</td>\n";
//	echo "							</tr>\n";
//	echo "						</table>\n";
//    echo "  				</td>\n";
//	echo "  			</tr>\n";
	echo "  		</table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "  		<table border=0>\n";
	echo "  			<tr>\n";
	echo "  				<td valign=\"top\">\n";
	
	if ($_SESSION['officeid']!=194)
	{
		echo "  					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Save\">\n";
	}
	
	echo "                  </td>\n";
	echo "                  </form>\n";
	echo "              </tr>\n";
	echo "          </table>\n";
	echo "      </td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
}

function cform_VENDOR()
{
	$officeid 	=$_SESSION['officeid'];
	$dates		=dateformat();
	$uid		=md5(session_id().time()).".".$_SESSION['securityid'];
	$acclist	=explode(",",$_SESSION['aid']);
	$curryr		=date("Y");
	$futyr 		=$curryr+1;

	$qryA = "SELECT officeid,name,stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resA = mssql_query($qryA);
    $rowA = mssql_fetch_row($resA);
	$nrowsA = mssql_num_rows($resA);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT * FROM jest..states ORDER BY abrev ASC;";
	$resC = mssql_query($qryC);
	$nrowsC = mssql_num_rows($resC);

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=2 AND statusid!=0 AND access!=9 AND provided=0 ORDER by name ASC;";
	$resG = mssql_query($qryG);

	$hlpnd=1;

	echo "<script type=\"text/javascript\" src=\"js/jquery_vendor_func.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"js/validate_form.js\"></script>\n";
	echo "<table><tr><td align=\"left\">\n";
	echo "                  <form method=\"post\" onSubmit=\"return FormValidate();\">\n";
	echo "                  <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "                  <input type=\"hidden\" name=\"call\" value=\"add\">\n";
	echo "                  <input type=\"hidden\" name=\"recdate\" value=\"". time() ."\">\n";
	echo "                  <input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "                  <input type=\"hidden\" name=\"comments\" value=\"\">\n";
    echo "                  <input type=\"hidden\" name=\"officeid\" value=\"".$rowA[0]."\">\n";
    echo "                  <input type=\"hidden\" name=\"estorig\" value=\"2087\">\n";
    echo "                  <input type=\"hidden\" name=\"opt1\" value=\"0\">\n";
	echo "                  <input type=\"hidden\" name=\"opt2\" value=\"0\">\n";
	echo "                  <input type=\"hidden\" name=\"opt3\" value=\"0\">\n";
	echo "                  <input type=\"hidden\" name=\"opt4\" value=\"0\">\n";
	echo "					<input type=\"hidden\" name=\"ccounty\">\n";
	echo "					<input type=\"hidden\" name=\"cmap\">\n";
	echo "										<div id=\"vendor-tabs\">\n";
	echo "											<ul>\n";
	echo "												<li><a href=\"#name_tab\">Contact Info</a></li>\n";
	echo "												<li><a href=\"#address_tab\">Address</a></li>\n";
	echo "											</ul>\n";
	echo "											<div id=\"name_tab\">\n";
	echo "												<fieldset>\n";
    echo "													<label for=\"cpname\">Company Name</label><input class=\"jform\" type=\"text\" size=\"40\" name=\"cpname\" id=\"cpname\"><br>\n";
	echo "													<label for=\"cfname\">First Name</label><input class=\"jform\" type=\"text\" size=\"40\" name=\"cfname\"><br>\n";
	echo "													<label for=\"clname\">Last Name</label><input class=\"jform\" type=\"text\" size=\"40\" name=\"clname\"><br>\n";
	echo "													<label for=\"cwork\">Phone</label><input class=\"jform\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cwork\"><br>\n";
	echo "													<label for=\"ccell\">Cell</label><input class=\"jform\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"ccell\"><br>\n";
	echo "													<label for=\"cfax\">Fax</label><input class=\"jform\" type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\"><br>\n";
	echo "													<label for=\"cemail\">E-Mail</label><input class=\"jform\" type=\"text\" name=\"cemail\" id=\"cemail\" size=\"30\"><br>\n";
	echo "													<label for=\"ccomments\">Comments</label><textarea class=\"jform\" name=\"ccomments\" rows=\"5\" cols=\"40\"></textarea><br>\n";
	echo "												</fieldset>\n";
	echo "											</div>\n";
	echo "											<div id=\"address_tab\">\n";
	echo "												<fieldset>\n";
	echo "													<label for=\"caddr1\">Street</label><input class=\"jform\" type=\"text\" size=\"40\" name=\"caddr1\"><br>\n";
	echo "													<label for=\"ccity\">City</label><input class=\"jform\" type=\"text\" size=\"20\" name=\"ccity\"><br>\n";
	echo "													<label for=\"cstate\">State</label>\n";
	echo "														<select name=\"cstate\" class=\"jform\">\n";
	
	while ($rowC=mssql_fetch_array($resC))
	{
		echo "														<option value=\"".$rowC['abrev']."\">".$rowC['abrev']."</option>\n";
	}
	
	echo "														</select><br>\n";
	echo "													<label for=\"czip1\">Zip</label><input class=\"jform\" type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\"> - <input type=\"text\" size=\"6\" maxlength=\"4\" name=\"czip2\"><br>\n";
	echo "  												<input type=\"submit\" value=\"Save\"><br>\n";
	echo "												</fieldset>\n";
	echo "											</div>\n";
	echo "										</div>\n";
	echo "              					    </form>\n";
	echo "</td></tr></table>\n";
}

function cform_add_VENDOR()
{
	error_reporting(E_ALL);
		
    $qryC   = "exec sp_insert_cinfo ";
    $qryC  .= "@securityid='".$_REQUEST['estorig']."', ";
    $qryC  .= "@officeid='".$_SESSION['officeid']."', ";
    $qryC  .= "@srcoffice='".$_SESSION['officeid']."', ";
    $qryC  .= "@recdate='".$_REQUEST['recdate']."', ";
    $qryC  .= "@cfname='".htmlspecialchars((ucwords(trim($_REQUEST['cfname']))),ENT_QUOTES)."', ";
    $qryC  .= "@clname='".htmlspecialchars(ucwords(trim($_REQUEST['clname'])),ENT_QUOTES)."', ";
    $qryC  .= "@caddr1='".htmlspecialchars(trim($_REQUEST['caddr1']),ENT_QUOTES)."', ";
    $qryC  .= "@ccity='".htmlspecialchars($_REQUEST['ccity'],ENT_QUOTES)."', ";
    $qryC  .= "@cstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
    $qryC  .= "@czip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
    $qryC  .= "@czip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
    $qryC  .= "@ccounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
    $qryC  .= "@cmap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
    $qryC  .= "@ssame='0', ";
    $qryC  .= "@saddr1='".htmlspecialchars($_REQUEST['caddr1'],ENT_QUOTES)."', ";
    $qryC  .= "@scity='".htmlspecialchars($_REQUEST['ccity'],ENT_QUOTES)."', ";
    $qryC  .= "@sstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
    $qryC  .= "@szip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
    $qryC  .= "@szip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
    $qryC  .= "@scounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
    $qryC  .= "@smap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
    $qryC  .= "@chome='".htmlspecialchars($_REQUEST['cwork'],ENT_QUOTES)."', ";
    $qryC  .= "@cwork='".htmlspecialchars($_REQUEST['cwork'],ENT_QUOTES)."', ";
    $qryC  .= "@ccell='".htmlspecialchars($_REQUEST['ccell'],ENT_QUOTES)."', ";
    $qryC  .= "@cfax='".htmlspecialchars($_REQUEST['cfax'],ENT_QUOTES)."', ";
    $qryC  .= "@source='".$_REQUEST['source']."', ";
    $qryC  .= "@cemail='".replacequote($_REQUEST['cemail'])."', ";
    $qryC  .= "@cconph='', ";
    $qryC  .= "@ccontime='', ";
    $qryC  .= "@appt_mo='".$_REQUEST['appt_mo']."', ";
    $qryC  .= "@appt_da='".$_REQUEST['appt_da']."', ";
    $qryC  .= "@appt_yr='".$_REQUEST['appt_yr']."', ";
    $qryC  .= "@appt_hr='".$_REQUEST['appt_hr']."', ";
    $qryC  .= "@appt_mn='".$_REQUEST['appt_mn']."', ";
    $qryC  .= "@appt_pa='".$_REQUEST['appt_pa']."', ";
    $qryC  .= "@opt1='0', ";
    $qryC  .= "@opt2='0', ";
    $qryC  .= "@opt3='0', ";
    $qryC  .= "@opt4='0', ";
    $qryC  .= "@comments=''; ";

    $resC   = mssql_query($qryC);
    $rowC   = mssql_fetch_row($resC);
    
    if (isset($rowC[0]) && $rowC[0] != 0)
    {
        if ($_SESSION['officeid']==193)
        {
			if (isset($_REQUEST['trackservice']) && $_REQUEST['trackservice']==1)
			{
				$trksrv=1;
			}
			else
			{
				$trksrv=0;
			}
			
			if (isset($_REQUEST['trackrepair']) && $_REQUEST['trackrepair']==1)
			{
				$trkrep=1;
			}
			else
			{
				$trkrep=0;
			}
			
            //$qryCa  = "UPDATE jest..cinfo SET cpname='".htmlspecialchars($_REQUEST['cpname'],ENT_QUOTES)."',cptype='".htmlspecialchars($_REQUEST['cptype'],ENT_QUOTES)."',mrktproc='".htmlspecialchars($_REQUEST['mrktproc'],ENT_QUOTES)."',trackservice='".$trksrv."',trackrepair='".$trkrep."', WHERE cid=".$rowC[0].";";
			$qryCa  = "UPDATE jest..cinfo SET cpname='".htmlspecialchars($_REQUEST['cpname'],ENT_QUOTES)."',market='".htmlspecialchars($_REQUEST['market'],ENT_QUOTES)."',cptype='".htmlspecialchars($_REQUEST['cptype'],ENT_QUOTES)."',trackservice=".$trksrv.",trackrepair=".$trkrep." WHERE cid=".$rowC[0].";";
            $resCa  = mssql_query($qryCa);
        }
        
        if (!empty($_REQUEST['ccomments']) && strlen($_REQUEST['ccomments']) >= 2 && $rowA['ccnt'] == 0)
        {
            $qryB   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
            $qryB  .= "VALUES ";
            $qryB  .= "('".$rowC[0]."','".$_SESSION['officeid']."','".$_SESSION['securityid']."','leads','".htmlspecialchars($_REQUEST['ccomments'],ENT_QUOTES)."','".$_REQUEST['uid']."')";
            $resB  = mssql_query($qryB);
        }
        
        cform_view_TRACK($rowC[0]);
    }
    else
    {
        echo "<b><font color=\"red\">Error!</font> <br><br>The Lead information you attempted to submit did not save. Click back, check your entry and resubmit</b>";
        exit;
    }
}

function cform_add_TRACK()
{
	error_reporting(E_ALL);
		
    $qryC   = "exec sp_insert_cinfo ";
    $qryC  .= "@securityid='".$_REQUEST['estorig']."', ";
    $qryC  .= "@officeid='".$_SESSION['officeid']."', ";
    $qryC  .= "@srcoffice='".$_SESSION['officeid']."', ";
    $qryC  .= "@recdate='".$_REQUEST['recdate']."', ";
    $qryC  .= "@cfname='".htmlspecialchars((ucwords(trim($_REQUEST['cfname']))),ENT_QUOTES)."', ";
    $qryC  .= "@clname='".htmlspecialchars(ucwords(trim($_REQUEST['clname'])),ENT_QUOTES)."', ";
    $qryC  .= "@caddr1='".htmlspecialchars(trim($_REQUEST['caddr1']),ENT_QUOTES)."', ";
    $qryC  .= "@ccity='".htmlspecialchars($_REQUEST['ccity'],ENT_QUOTES)."', ";
    $qryC  .= "@cstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
    $qryC  .= "@czip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
    $qryC  .= "@czip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
    $qryC  .= "@ccounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
    $qryC  .= "@cmap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
    $qryC  .= "@ssame='0', ";
    $qryC  .= "@saddr1='".htmlspecialchars($_REQUEST['caddr1'],ENT_QUOTES)."', ";
    $qryC  .= "@scity='".htmlspecialchars($_REQUEST['ccity'],ENT_QUOTES)."', ";
    $qryC  .= "@sstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
    $qryC  .= "@szip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
    $qryC  .= "@szip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
    $qryC  .= "@scounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
    $qryC  .= "@smap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
    $qryC  .= "@chome='".htmlspecialchars($_REQUEST['cwork'],ENT_QUOTES)."', ";
    $qryC  .= "@cwork='".htmlspecialchars($_REQUEST['cwork'],ENT_QUOTES)."', ";
    $qryC  .= "@ccell='".htmlspecialchars($_REQUEST['ccell'],ENT_QUOTES)."', ";
    $qryC  .= "@cfax='".htmlspecialchars($_REQUEST['cfax'],ENT_QUOTES)."', ";
    $qryC  .= "@source='".$_REQUEST['source']."', ";
    $qryC  .= "@cemail='".replacequote($_REQUEST['cemail'])."', ";
    $qryC  .= "@cconph='', ";
    $qryC  .= "@ccontime='', ";
    $qryC  .= "@appt_mo='".$_REQUEST['appt_mo']."', ";
    $qryC  .= "@appt_da='".$_REQUEST['appt_da']."', ";
    $qryC  .= "@appt_yr='".$_REQUEST['appt_yr']."', ";
    $qryC  .= "@appt_hr='".$_REQUEST['appt_hr']."', ";
    $qryC  .= "@appt_mn='".$_REQUEST['appt_mn']."', ";
    $qryC  .= "@appt_pa='".$_REQUEST['appt_pa']."', ";
    $qryC  .= "@opt1='0', ";
    $qryC  .= "@opt2='0', ";
    $qryC  .= "@opt3='0', ";
    $qryC  .= "@opt4='0', ";
    $qryC  .= "@comments=''; ";

    $resC   = mssql_query($qryC);
    $rowC   = mssql_fetch_row($resC);
    
    if (isset($rowC[0]) && $rowC[0] != 0)
    {
        if ($_SESSION['officeid']==193)
        {
			if (isset($_REQUEST['trackservice']) && $_REQUEST['trackservice']==1)
			{
				$trksrv=1;
			}
			else
			{
				$trksrv=0;
			}
			
			if (isset($_REQUEST['trackrepair']) && $_REQUEST['trackrepair']==1)
			{
				$trkrep=1;
			}
			else
			{
				$trkrep=0;
			}
			
            //$qryCa  = "UPDATE jest..cinfo SET cpname='".htmlspecialchars($_REQUEST['cpname'],ENT_QUOTES)."',cptype='".htmlspecialchars($_REQUEST['cptype'],ENT_QUOTES)."',mrktproc='".htmlspecialchars($_REQUEST['mrktproc'],ENT_QUOTES)."',trackservice='".$trksrv."',trackrepair='".$trkrep."', WHERE cid=".$rowC[0].";";
			$qryCa  = "UPDATE jest..cinfo SET cpname='".htmlspecialchars($_REQUEST['cpname'],ENT_QUOTES)."',market='".htmlspecialchars($_REQUEST['market'],ENT_QUOTES)."',cptype='".htmlspecialchars($_REQUEST['cptype'],ENT_QUOTES)."',trackservice=".$trksrv.",trackrepair=".$trkrep." WHERE cid=".$rowC[0].";";
            $resCa  = mssql_query($qryCa);
        }
        
        if (!empty($_REQUEST['ccomments']) && strlen($_REQUEST['ccomments']) >= 2 && $rowA['ccnt'] == 0)
        {
            $qryB   = "INSERT INTO chistory (custid,officeid,secid,act,mtext,tranid) ";
            $qryB  .= "VALUES ";
            $qryB  .= "('".$rowC[0]."','".$_SESSION['officeid']."','".$_SESSION['securityid']."','leads','".htmlspecialchars($_REQUEST['ccomments'],ENT_QUOTES)."','".$_REQUEST['uid']."')";
            $resB  = mssql_query($qryB);
        }
        
        cform_view_TRACK($rowC[0]);
    }
    else
    {
        echo "<b><font color=\"red\">Error!</font> <br><br>The Lead information you attempted to submit did not save. Click back, check your entry and resubmit</b>";
        exit;
    }
}

function cform_edit_TRACK()
{
	error_reporting(E_ALL);
	
	if ($_SESSION['securityid']==26)
	{
	    ini_set('display_errors','On');
	}
	
	$acclist=explode(",",$_SESSION['aid']);
	$ex_status_codes=array();

	$qry = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1 = "SELECT am,finan_from,leadmail FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	$qry2 = "SELECT am,leadmail FROM offices WHERE officeid='89';"; //BHNM:Active
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	$qry3 = "SELECT am,name,leadmail FROM offices WHERE officeid='".$_REQUEST['site']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);

	$qry4 = "SELECT securityid,sidm FROM security WHERE securityid='".$row['securityid']."';";
	$res4 = mssql_query($qry4);
	$row4 = mssql_fetch_array($res4);
	
	$qry5 = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$res5 = mssql_query($qry5);
	
	while ($row5 = mssql_fetch_array($res5))
	{
		$ex_status_codes[]=$row5['statusid'];
	}

	if (!in_array($row['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to Update this Lead</b>";
		exit;
	}
	else
	{
		if (isset($_REQUEST['hold']) && $_REQUEST['hold']==1)
		{
			$hold=1;
		}
		else
		{
			$hold=0;
		}
		
		if (isset($_REQUEST['trackservice']) && $_REQUEST['trackservice']==1)
		{
			$trksrv=1;
		}
		else
		{
			$trksrv=0;
		}
		
		if (isset($_REQUEST['trackrepair']) && $_REQUEST['trackrepair']==1)
		{
			$trkrep=1;
		}
		else
		{
			$trkrep=0;
		}
		
        $udate_id=$_REQUEST['estorig'];
        
		$qryA  = "UPDATE cinfo SET ";
		$qryA  .= "securityid='".$_REQUEST['estorig']."', ";
		$qryA  .= "cfname='".htmlspecialchars(ucwords($_REQUEST['cfname']),ENT_QUOTES)."', ";
		$qryA  .= "clname='".htmlspecialchars(ucwords($_REQUEST['clname']),ENT_QUOTES)."', ";
        $qryA  .= "cpname='".htmlspecialchars(ucwords($_REQUEST['cpname']),ENT_QUOTES)."', ";
		$qryA  .= "caddr1='".htmlspecialchars(ucwords($_REQUEST['caddr1']),ENT_QUOTES)."', ";
		$qryA  .= "ccity='".htmlspecialchars(ucwords($_REQUEST['ccity']),ENT_QUOTES)."', ";
		$qryA  .= "cstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
		$qryA  .= "czip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
		//$qryA  .= "czip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
		$qryA  .= "ccounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
		$qryA  .= "cmap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
        $qryA  .= "ssame='0', ";
        $qryA  .= "saddr1='".htmlspecialchars(ucwords($_REQUEST['caddr1']),ENT_QUOTES)."', ";
        $qryA  .= "scity='".htmlspecialchars(ucwords($_REQUEST['ccity']),ENT_QUOTES)."', ";
        $qryA  .= "sstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
        $qryA  .= "szip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
        //$qryA  .= "szip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
        $qryA  .= "scounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
        $qryA  .= "smap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
		//$qryA  .= "chome='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['chome']),ENT_QUOTES)."', ";
		$qryA  .= "cwork='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['cwork']),ENT_QUOTES)."', ";
		$qryA  .= "ccell='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['ccell']),ENT_QUOTES)."', ";
		$qryA  .= "cfax='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['cfax']),ENT_QUOTES)."', ";
		$qryA  .= "cemail='".replacequote($_REQUEST['cemail'])."', ";
		$qryA  .= "cptype='".htmlspecialchars($_REQUEST['cptype'],ENT_QUOTES)."', ";
		$qryA  .= "trackservice='".$trksrv."', ";
		$qryA  .= "trackrepair='".$trkrep."', ";
		//$qryA  .= "cconph='".htmlspecialchars($_REQUEST['cconph'],ENT_QUOTES)."', ";
		//$qryA  .= "ccontime='".htmlspecialchars($_REQUEST['ccontime'],ENT_QUOTES)."', ";
		$qryA  .= "appt_mo='".$_REQUEST['appt_mo']."', ";
		$qryA  .= "appt_da='".$_REQUEST['appt_da']."', ";
		$qryA  .= "appt_yr='".$_REQUEST['appt_yr']."', ";
		$qryA  .= "appt_hr='".$_REQUEST['appt_hr']."', ";
		$qryA  .= "appt_mn='".$_REQUEST['appt_mn']."', ";
		$qryA  .= "appt_pa='".$_REQUEST['appt_pa']."', ";
		$qryA  .= "hold='".$hold."', ";
        $qryA  .= "market='".htmlspecialchars($_REQUEST['market'],ENT_QUOTES)."', ";
        $qryA  .= "mrktproc='".htmlspecialchars($_REQUEST['mrktproc'],ENT_QUOTES)."', ";
		
		if ($hold==1)
		{
			$qryA  .= "hold_mo='".$_REQUEST['hold_mo']."', ";
			$qryA  .= "hold_da='".$_REQUEST['hold_da']."', ";
			$qryA  .= "hold_yr='".$_REQUEST['hold_yr']."', ";
			$qryA  .= "callback='". old_date_disp($_REQUEST['hold_mo'],$_REQUEST['hold_da'],$_REQUEST['hold_yr'],'00','00','1') ."',";
		}

		if ($row['mas_prep']==0 || $_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==SYS_ADMIN)
		{
			$qryA  .= "stage='".$_REQUEST['stage']."', ";
			$qryA  .= "dupe='".$_REQUEST['dupe']."', ";
		}
		
		if (!in_array($row['source'],$ex_status_codes))
		{
			$qryA  .= "source='".$_REQUEST['source']."', ";
			$qryA  .= "opt1='0', ";
			$qryA  .= "opt2='0', ";
			$qryA  .= "opt3='0', ";
			$qryA  .= "opt4='0', ";
		}
		
		if ($row['ccontact']==0 && !empty($_REQUEST['ccontact']) && $_REQUEST['ccontact']==1)
		{
			$qryA  .= "ccontact='".$_REQUEST['ccontact']."', ";
			$qryA  .= "ccontactdate=getdate(), ";
			$qryA  .= "ccontactby='".$_SESSION['securityid']."', ";
		}
		
		if (
				isset($_REQUEST['appt_yr']) && isset($_REQUEST['appt_mo']) && isset($_REQUEST['appt_da']) &&
				$_REQUEST['appt_mo']!='0' && $_REQUEST['appt_da']!='0' && $_REQUEST['appt_yr']!='0000'
			)
		{
			//echo "OK!!<br>";
			$qryA  .= "apptmnt='". old_date_disp($_REQUEST['appt_mo'],$_REQUEST['appt_da'],$_REQUEST['appt_yr'],$_REQUEST['appt_hr'],$_REQUEST['appt_mn'],$_REQUEST['appt_pa']) ."',";
		}
		
		$qryA  .= "updated=getdate() ";
		$qryA  .= "WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
		$resA  = mssql_query($qryA);
		
		// Adds Comment
		if (isset($_REQUEST['addcomment']) && strlen($_REQUEST['addcomment']) >= 2)
		{
			$qryA1 = "SELECT id FROM jest..chistory WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['cid']."' and tranid='".$_REQUEST['tranid']."';";
			$resA1 = mssql_query($qryA1);
			$nrowA1 = mssql_num_rows($resA1);
			
			if ($nrowA1 == 0)
			{
				$inputtext=removequote($_REQUEST['addcomment']);
				$complaint=0;
				$cservice=0;
				$followup=0;
				$resolve=0;
				$relid=0;
				
				$qryA2  = "INSERT INTO jest..chistory (officeid,secid,custid,act,tranid,mtext,complaint,followup,resolved,relatedcomplaint,cservice) ";
				$qryA2 .= "VALUES ";
				$qryA2 .= "('".$_SESSION['officeid']."','".$_SESSION['securityid']."','".$_REQUEST['cid']."','leads','".$_REQUEST['tranid']."','".htmlspecialchars(removequote($inputtext),ENT_QUOTES)."',".$complaint.",".$followup.",".$resolve.",".$relid.",".$cservice.");";
				$resA2  = mssql_query($qryA2);
			}
		}
		
    	//Create Entry in Lead History table
		$qryB   = "INSERT INTO jest..leadhistory (cinfo_id,officeid,owner,uby,source,result,clname,cfname,caddr1,czip1,saddr1,szip1,chome,ccell,cwork,appt) ";
		$qryB  .= "VALUES ";
		$qryB  .= "('".$row['cid']."','".$row['officeid']."','".$udate_id."','".$_SESSION['securityid']."','".$row['source']."','".$row['stage']."','".$row['clname']."','".$row['cfname']."','".$row['caddr1']."','".$row['czip1']."','".$row['saddr1']."','".$row['szip1']."','".$row['chome']."','".$row['ccell']."','".$row['cwork']."','".$row['apptmnt']."')";
		$resB  = mssql_query($qryB);

		//Update chistory table for inter-office moves
		if ($_SESSION['officeid']!=$_REQUEST['site'])
		{
			$qryC	= "UPDATE chistory SET officeid='".$_REQUEST['site']."' WHERE custid='".$_REQUEST['cid']."';";
			$resC	= mssql_query($qryC);
		}

		cform_view_TRACK(0);
	}
}

function cform_edit_VENDOR()
{
	error_reporting(E_ALL);
	
	if ($_SESSION['securityid']==26)
	{
	    ini_set('display_errors','On');
	}
	
	$acclist=explode(",",$_SESSION['aid']);
	$ex_status_codes=array();

	$qry = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$qry1 = "SELECT am,finan_from,leadmail FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);

	$qry2 = "SELECT am,leadmail FROM offices WHERE officeid='89';"; //BHNM:Active
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);

	$qry3 = "SELECT am,name,leadmail FROM offices WHERE officeid='".$_REQUEST['site']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);

	$qry4 = "SELECT securityid,sidm FROM security WHERE securityid='".$row['securityid']."';";
	$res4 = mssql_query($qry4);
	$row4 = mssql_fetch_array($res4);
	
	$qry5 = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$res5 = mssql_query($qry5);
	
	while ($row5 = mssql_fetch_array($res5))
	{
		$ex_status_codes[]=$row5['statusid'];
	}

	if (!in_array($row['securityid'],$acclist))
	{
		echo "<br><font color=\"red\"><b>Access Error</b></font><br><b>You do not have approriate Access Rights to Update this Lead</b>";
		exit;
	}
	else
	{
		if (isset($_REQUEST['hold']) && $_REQUEST['hold']==1)
		{
			$hold=1;
		}
		else
		{
			$hold=0;
		}
		
		if (isset($_REQUEST['trackservice']) && $_REQUEST['trackservice']==1)
		{
			$trksrv=1;
		}
		else
		{
			$trksrv=0;
		}
		
		if (isset($_REQUEST['trackrepair']) && $_REQUEST['trackrepair']==1)
		{
			$trkrep=1;
		}
		else
		{
			$trkrep=0;
		}
		
        $udate_id=$_REQUEST['estorig'];
        
		$qryA  = "UPDATE cinfo SET ";
		$qryA  .= "securityid='".$_REQUEST['estorig']."', ";
		$qryA  .= "cfname='".htmlspecialchars(ucwords($_REQUEST['cfname']),ENT_QUOTES)."', ";
		$qryA  .= "clname='".htmlspecialchars(ucwords($_REQUEST['clname']),ENT_QUOTES)."', ";
        $qryA  .= "cpname='".htmlspecialchars(ucwords($_REQUEST['cpname']),ENT_QUOTES)."', ";
		$qryA  .= "caddr1='".htmlspecialchars(ucwords($_REQUEST['caddr1']),ENT_QUOTES)."', ";
		$qryA  .= "ccity='".htmlspecialchars(ucwords($_REQUEST['ccity']),ENT_QUOTES)."', ";
		$qryA  .= "cstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
		$qryA  .= "czip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
		//$qryA  .= "czip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
		$qryA  .= "ccounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
		$qryA  .= "cmap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
        $qryA  .= "ssame='0', ";
        $qryA  .= "saddr1='".htmlspecialchars(ucwords($_REQUEST['caddr1']),ENT_QUOTES)."', ";
        $qryA  .= "scity='".htmlspecialchars(ucwords($_REQUEST['ccity']),ENT_QUOTES)."', ";
        $qryA  .= "sstate='".htmlspecialchars($_REQUEST['cstate'],ENT_QUOTES)."', ";
        $qryA  .= "szip1='".htmlspecialchars($_REQUEST['czip1'],ENT_QUOTES)."', ";
        //$qryA  .= "szip2='".htmlspecialchars($_REQUEST['czip2'],ENT_QUOTES)."', ";
        $qryA  .= "scounty='".htmlspecialchars($_REQUEST['ccounty'],ENT_QUOTES)."', ";
        $qryA  .= "smap='".htmlspecialchars($_REQUEST['cmap'],ENT_QUOTES)."', ";
		//$qryA  .= "chome='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['chome']),ENT_QUOTES)."', ";
		$qryA  .= "cwork='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['cwork']),ENT_QUOTES)."', ";
		$qryA  .= "ccell='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['ccell']),ENT_QUOTES)."', ";
		$qryA  .= "cfax='".htmlspecialchars(preg_replace('/\.|-|\s/i','$1$2$3',$_REQUEST['cfax']),ENT_QUOTES)."', ";
		$qryA  .= "cemail='".replacequote($_REQUEST['cemail'])."', ";
		$qryA  .= "cptype='".htmlspecialchars($_REQUEST['cptype'],ENT_QUOTES)."', ";
		$qryA  .= "trackservice='".$trksrv."', ";
		$qryA  .= "trackrepair='".$trkrep."', ";
		//$qryA  .= "cconph='".htmlspecialchars($_REQUEST['cconph'],ENT_QUOTES)."', ";
		//$qryA  .= "ccontime='".htmlspecialchars($_REQUEST['ccontime'],ENT_QUOTES)."', ";
		$qryA  .= "appt_mo='".$_REQUEST['appt_mo']."', ";
		$qryA  .= "appt_da='".$_REQUEST['appt_da']."', ";
		$qryA  .= "appt_yr='".$_REQUEST['appt_yr']."', ";
		$qryA  .= "appt_hr='".$_REQUEST['appt_hr']."', ";
		$qryA  .= "appt_mn='".$_REQUEST['appt_mn']."', ";
		$qryA  .= "appt_pa='".$_REQUEST['appt_pa']."', ";
		$qryA  .= "hold='".$hold."', ";
        $qryA  .= "market='".htmlspecialchars($_REQUEST['market'],ENT_QUOTES)."', ";
        $qryA  .= "mrktproc='".htmlspecialchars($_REQUEST['mrktproc'],ENT_QUOTES)."', ";
		
		if ($hold==1)
		{
			$qryA  .= "hold_mo='".$_REQUEST['hold_mo']."', ";
			$qryA  .= "hold_da='".$_REQUEST['hold_da']."', ";
			$qryA  .= "hold_yr='".$_REQUEST['hold_yr']."', ";
			$qryA  .= "callback='". old_date_disp($_REQUEST['hold_mo'],$_REQUEST['hold_da'],$_REQUEST['hold_yr'],'00','00','1') ."',";
		}

		if ($row['mas_prep']==0 || $_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==SYS_ADMIN)
		{
			$qryA  .= "stage='".$_REQUEST['stage']."', ";
			$qryA  .= "dupe='".$_REQUEST['dupe']."', ";
		}
		
		if (!in_array($row['source'],$ex_status_codes))
		{
			$qryA  .= "source='".$_REQUEST['source']."', ";
			$qryA  .= "opt1='0', ";
			$qryA  .= "opt2='0', ";
			$qryA  .= "opt3='0', ";
			$qryA  .= "opt4='0', ";
		}
		
		if ($row['ccontact']==0 && !empty($_REQUEST['ccontact']) && $_REQUEST['ccontact']==1)
		{
			$qryA  .= "ccontact='".$_REQUEST['ccontact']."', ";
			$qryA  .= "ccontactdate=getdate(), ";
			$qryA  .= "ccontactby='".$_SESSION['securityid']."', ";
		}
		
		if (
				isset($_REQUEST['appt_yr']) && isset($_REQUEST['appt_mo']) && isset($_REQUEST['appt_da']) &&
				$_REQUEST['appt_mo']!='0' && $_REQUEST['appt_da']!='0' && $_REQUEST['appt_yr']!='0000'
			)
		{
			//echo "OK!!<br>";
			$qryA  .= "apptmnt='". old_date_disp($_REQUEST['appt_mo'],$_REQUEST['appt_da'],$_REQUEST['appt_yr'],$_REQUEST['appt_hr'],$_REQUEST['appt_mn'],$_REQUEST['appt_pa']) ."',";
		}
		
		$qryA  .= "updated=getdate() ";
		$qryA  .= "WHERE officeid='".$_SESSION['officeid']."' AND cid='".$_REQUEST['cid']."';";
		$resA  = mssql_query($qryA);
		
		// Adds Comment
		if (isset($_REQUEST['addcomment']) && strlen($_REQUEST['addcomment']) >= 2)
		{
			$qryA1 = "SELECT id FROM jest..chistory WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['cid']."' and tranid='".$_REQUEST['tranid']."';";
			$resA1 = mssql_query($qryA1);
			$nrowA1 = mssql_num_rows($resA1);
			
			if ($nrowA1 == 0)
			{
				$inputtext=removequote($_REQUEST['addcomment']);
				$complaint=0;
				$cservice=0;
				$followup=0;
				$resolve=0;
				$relid=0;
				
				$qryA2  = "INSERT INTO jest..chistory (officeid,secid,custid,act,tranid,mtext,complaint,followup,resolved,relatedcomplaint,cservice) ";
				$qryA2 .= "VALUES ";
				$qryA2 .= "('".$_SESSION['officeid']."','".$_SESSION['securityid']."','".$_REQUEST['cid']."','leads','".$_REQUEST['tranid']."','".htmlspecialchars(removequote($inputtext),ENT_QUOTES)."',".$complaint.",".$followup.",".$resolve.",".$relid.",".$cservice.");";
				$resA2  = mssql_query($qryA2);
			}
		}
		
    	//Create Entry in Lead History table
		$qryB   = "INSERT INTO jest..leadhistory (cinfo_id,officeid,owner,uby,source,result,clname,cfname,caddr1,czip1,saddr1,szip1,chome,ccell,cwork,appt) ";
		$qryB  .= "VALUES ";
		$qryB  .= "('".$row['cid']."','".$row['officeid']."','".$udate_id."','".$_SESSION['securityid']."','".$row['source']."','".$row['stage']."','".$row['clname']."','".$row['cfname']."','".$row['caddr1']."','".$row['czip1']."','".$row['saddr1']."','".$row['szip1']."','".$row['chome']."','".$row['ccell']."','".$row['cwork']."','".$row['apptmnt']."')";
		$resB  = mssql_query($qryB);

		//Update chistory table for inter-office moves
		if ($_SESSION['officeid']!=$_REQUEST['site'])
		{
			$qryC	= "UPDATE chistory SET officeid='".$_REQUEST['site']."' WHERE custid='".$_REQUEST['cid']."';";
			$resC	= mssql_query($qryC);
		}

		cform_view_TRACK(0);
	}
}

function cform_view_OLD($tcid)
{
	unset($_SESSION['ifcid']); // Security Setting for embedded frames and AJAX
	$src_ex=array();
	$acclist=explode(",",$_SESSION['aid']);

	if (empty($_REQUEST['uid']))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error occured.\n";
		exit;
	}

	if (isset($tcid) && $tcid!=0)
	{
			$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$tcid."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$cid=$row0['cid'];	
	}
	else
	{
		if (isset($_REQUEST['subq']) && $_REQUEST['subq']=="custid")
		{
			$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['custid']."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$cid=$row0['cid'];
		}
		else
		{
			$cid=$_REQUEST['cid'];
		}
	}

	if (empty($cid))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> You must provide a Valid Customer ID number.\n";
		exit;
	}

	$dates	=dateformat();

	if ($_SESSION['llev'] >= 5)
	{
		if ($_SESSION['officeid']==89)
		{
			//echo "Not Admin<br>";
			$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE active=1 ORDER BY grouping,name ASC;";
		}
		else
		{
			//echo "Admin<br>";
			$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE active=1 AND adminonly!=1 ORDER BY grouping,name ASC;";
		}
	}
	else
	{
		$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	}
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	$qryAa = "SELECT officeid,name,stax,enest,encon,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
	$nrowsAa = mssql_num_rows($resAa);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,sidm,slevel,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY substring(slevel,13,13) desc, lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT stax,enest,encon,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[0]!=0)
	{
		$qryD = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resD = mssql_query($qryD);

		$qryE = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resE = mssql_query($qryE);
	}

	$qryF = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=1 AND access!=0 ORDER by name ASC;";
	$resG = mssql_query($qryG);
	
	//$qryGa = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$qryGa = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$resGa = mssql_query($qryGa);
	
	while ($rowGa = mssql_fetch_array($resGa))
	{
		$src_ex[]=$rowGa['statusid'];
	}

	$qryH = "SELECT * FROM leadstatuscodes WHERE active=2 AND access!=0  AND access!=9 ORDER by name ASC;";
	$resH = mssql_query($qryH);

	$qryI = "SELECT securityid,fname,lname,sidm FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowF['securityid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryJ = "SELECT securityid,sidm,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowF['sidm']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);

	$qryK = "SELECT MIN(appt_yr) as minyr FROM cinfo WHERE officeid='".$_SESSION['officeid']."';";
	$resK = mssql_query($qryK);
	$rowK = mssql_fetch_array($resK);

	$qryL = "SELECT C1.*,(SELECT lname FROM security WHERE securityid=C1.secid) as slname,(SELECT fname FROM security WHERE securityid=C1.secid) as sfname FROM chistory AS C1 WHERE C1.custid='".$cid."' ORDER BY C1.mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);

	$qryM = "SELECT securityid,emailtemplateaccess,filestoreaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resM = mssql_query($qryM);
	$rowM = mssql_fetch_array($resM);

	$adate = date("m-d-Y (g:i A)", strtotime($rowF['added']));
	//$sdate = date("m-d-Y (g:i A)", strtotime($rowF['submitted']));
	$curryr=date("Y");
	$futyr =$curryr+2;

	if ($_REQUEST['uid']=="XXX")
	{
		$dis="DISABLED";
	}
	//elseif ($_SESSION['llev']==1 && $rowF['estid']!=0)
	/*elseif ($rowF['jobid']!='0')
	{
		$dis="DISABLED";
	}*/
	else
	{
		$dis="";
	}

	/*if ($_SESSION['securityid']===26)
	{
		echo $_SESSION['aid']."<br>";
	}*/

	if ($_SESSION['llev'] < 9 && !in_array($rowI['securityid'],$acclist))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font><br>You do not have appropriate Access to view this Information.\n";
		exit;
	}

	$appt_dt	="";
	if ($rowF['appt_mo']!="00" && $rowF['appt_da']!="00" && $rowF['appt_yr']!="0000")
	{
		$appt_dt=old_date_disp($rowF['appt_mo'],$rowF['appt_da'],$rowF['appt_yr'],$rowF['appt_hr'],$rowF['appt_mn'],$rowF['appt_pa']);
	}

	$_SESSION['ifcid']=$rowF['cid'];
	$cmaplink=maplink($rowF['caddr1'],$rowF['ccity'],$rowF['cstate'],$rowF['czip1']);
	$smaplink=maplink($rowF['saddr1'],$rowF['scity'],$rowF['sstate'],$rowF['szip1']);
	$tranid=time().".".$cid.".".$_SESSION['securityid'];
	
	$hlpnd=1;
	
	$lwidth='340px';
	$rwidth='530px';
	
	echo "<script type=\"text/javascript\" src=\"js/jquery_lead_view.js\"></script>\n";
	echo "<div id=\"masterdiv\">\n";
	echo "<table width=\"950px\" align=\"center\">\n";
	echo "   <tr>\n";
	echo "      <td>\n";
	echo "		<table width=\"100%\" align=\"center\" border=0>\n";
	echo "         	<tr>\n";
	echo "            	<td align=\"right\">\n";
	echo "					<div class=\"noPrint\">\n";
	echo "					<table>\n";
	echo "						<tr>\n";
	echo "							<td align=\"right\">Feedback</td>\n";
	echo "							<td align=\"left\">\n";
	echo "         						<form method=\"post\">\n";
	echo "								<input type=\"hidden\" name=\"action\" value=\"message\">\n";
	echo "								<input type=\"hidden\" name=\"call\" value=\"new_feedback\">\n";
	echo "								<input class=\"transnb\" type=\"image\" src=\"images/pencil.png\" alt=\"Feedback\">\n";
	echo "      				   		</form>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "					</table>\n";
	echo "					</div>\n";
	echo "            	</td>\n";
	echo "         	</tr>\n";
	echo "   		<tr>\n";
	echo "      	<td>\n";
	echo "      	<form name=\"cview1\" method=\"post\" ".$dis.">\n";
	echo "         <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "         <input type=\"hidden\" name=\"call\" value=\"edit\">\n";
	echo "         <input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "         <input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "         <input type=\"hidden\" name=\"tranid\" value=\"".$tranid."\">\n";
	echo "         <input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
	echo "         	<table border=\"0\" width=\"100%\">\n";
	echo "         		<tr>\n";
	echo "            		<td>\n";
	echo "               	<table border=\"0\" width=\"100%\">\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"left\">\n";
	echo "               			<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                     			<tr>\n";
	echo "                        				<td class=\"gray\" align=\"left\"><b>Lead # <font color=\"blue\">".$rowF['custid']."</font></b></td>\n";
	echo "                        				<td class=\"gray\" align=\"right\">\n";

	if ($_SESSION['llev'] >= 5)
	{
		if ($rowF['estid']==0)
		{
			echo "<b>Status:</b> <select name=\"dupe\">\n";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"dupe\" value=\"".$rowF['dupe']."\">\n";
			echo "<b>Status:</b>       <select name=\"stage\" DISABLED>\n";
		}

		//echo "                        	<b>Status:</b> <select name=\"dupe\">\n";

		if ($rowF['dupe']==1)
		{
			echo "<option value=\"1\" SELECTED>Inactive</option>\n";
			echo "<option value=\"0\">Active</option>\n";
		}
		else
		{
			echo "<option value=\"1\">Inactive</option>\n";
			echo "<option value=\"0\" SELECTED>Active</option>\n";
		}
	}
	else
	{
		echo "         <input type=\"hidden\" name=\"dupe\" value=\"0\">\n";
	}

	echo "                        				</select>\n";
	echo "							</td>\n";
	echo "                                 			<td class=\"gray\" align=\"right\" width=\"20px\">\n";
	
	HelpNode('CformViewHeadPanel',$hlpnd++);
	
	echo "							</td>\n";
	echo "                    				</tr>\n";
	echo "                    			</table>\n";
	echo "								</td>\n";
	echo "                    	</tr>\n";
	echo "                     <tr>\n";
	echo "                        <td colspan=\"2\" align=\"right\">\n";
	echo "                           <table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "                           	<tr>\n";
	echo "                              	<td class=\"gray\" align=\"right\"><b>Date:</b>\n";
	echo "                                 <td class=\"gray\" align=\"left\">".$adate."</td>\n";
	echo "                                 <td class=\"gray\" align=\"right\"><b>Office: </b></td>\n";
	echo "                                 <td class=\"gray\" align=\"left\">\n";

	if ($rowF['estid']!=0)
	{
		$rowAa = mssql_fetch_array($resAa);
		echo "                                 	".$rowAa['name']."<input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
	}
	else
	{
		if ($_SESSION['llev'] >= 6)
		{
			echo "                                 	<select name=\"site\">\n";
			while ($rowA = mssql_fetch_array($resA))
			{
				if ($_SESSION['officeid']==$rowA['officeid'])
				{
					echo "                                 		<option value=\"".$rowA['officeid']."\" SELECTED>".$rowA['name']."</option>\n";
				}
				else
				{
					echo "                                 		<option value=\"".$rowA['officeid']."\">".$rowA['name']."</option>\n";
				}
			}
			echo "                                 	</select>\n";
		}
		elseif ($_SESSION['llev'] == 5)
		{
			if ($_SESSION['officeid']==89 || $_SESSION['officeid']==138) // Z&E Active or Z&E: Supplies Direct
			{
				if ($rowF['stage']==29)
				{
					echo "                                 	<select name=\"site\">\n";
					while ($rowA = mssql_fetch_array($resA))
					{
						if ($_SESSION['officeid']==$rowA['officeid'])
						{
							echo "                                 		<option value=\"".$rowA['officeid']."\" SELECTED>".$rowA['name']."</option>\n";
						}
						elseif ($rowA['officeid']==89)
						{
							echo "                                 		<option value=\"".$rowA['officeid']."\">".$rowA['name']."</option>\n";
						}
					}
					echo "                                 	</select>\n";
				}
				else
				{
					$rowAa = mssql_fetch_array($resAa);
					echo "                                 	".$rowAa['name']."<input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
				}
			}
			else
			{
				if ($rowF['source']==0 && $rowF['stage']==29)
				{
					echo "                                 	<select name=\"site\">\n";
					while ($rowA = mssql_fetch_array($resA))
					{
						if ($_SESSION['officeid']==$rowA['officeid'])
						{
							echo "                                 		<option value=\"".$rowA['officeid']."\" SELECTED>".$rowA['name']."</option>\n";
						}
						elseif ($rowA['officeid']==89)
						{
							echo "                                 		<option value=\"".$rowA['officeid']."\">".$rowA['name']."</option>\n";
						}
					}
					echo "                                 	</select>\n";
				}
				else
				{
					$rowAa = mssql_fetch_array($resAa);
					echo "                                 	".$rowAa['name']."<input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
				}
			}
		}
		else
		{
			$rowAa = mssql_fetch_array($resAa);
			echo "                                 	".$rowAa['name']."<input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
		}
	}

	echo "                                 </td>\n";
	echo "                                 <td class=\"gray\" align=\"right\"><b>Sales Rep:</b>\n";

	if ($_SESSION['llev'] == 4) // Sales Manager List
	{
		if ($rowF['estid']==0)
		{
			echo "                                 	<select name=\"estorig\">\n";
			
			while ($rowB = mssql_fetch_row($resB))
			{
				if (in_array($rowB[0],$acclist))
				{
					$slev=explode(",",$rowB[4]);
					
					if ($slev[6]==0)
					{
						$ostyle="fontred";
					}
					else
					{
						$ostyle="fontblack";
					}
	
					if ($rowF['securityid']==$rowB[0])
					{
						echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
					}
					else
					{
						echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\">".$rowB[1]." ".$rowB[2]."</option>\n";
					}
				}
			}
			echo "                                 	</select>\n";
		}
		else
		{
			/*echo "                                 	<select name=\"estorig\" DISABLED>\n";
			echo "<input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";*/
			echo "                                 ".$rowI['fname']." ".$rowI['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
		}
	}
	elseif ($_SESSION['llev'] >= 5) // General Manager List
	{
		//echo "                                 	<select name=\"estorig\">\n";
		if ($rowF['estid']==0)
		{
			echo "                                 	<select name=\"estorig\">\n";
			
			while ($rowB = mssql_fetch_row($resB))
			{
				$slev=explode(",",$rowB[4]);
				if ($slev[6]==0)
				{
					$ostyle="fontred";
					//$ostyle="style=\"background-color:red\"";
				}
				else
				{
					$ostyle="fontblack";
					//$ostyle="";
				}
	
				if ($rowF['securityid']==$rowB[0])
				{
					echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\" SELECTED>".$rowB[1]." ".$rowB[2]."</option>\n";
				}
				else
				{
					echo "                                 	<option value=\"".$rowB[0]."\" class=\"".$ostyle."\">".$rowB[1]." ".$rowB[2]."</option>\n";
				}
			}
	
			echo "                                 	</select>\n";
		}
		else
		{
			/*echo "                                 	<select name=\"estorig\" DISABLED>\n";
			echo "<input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";*/
			echo "                                 ".$rowI['fname']." ".$rowI['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
		}
	}
	else
	{
		echo "                                 ".$rowI['fname']." ".$rowI['lname']."<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
	}

	echo "                                 </td>\n";
	echo "                                 			<td class=\"gray\" align=\"right\" width=\"20px\">\n";
	
	HelpNode('CformViewDatePanel',$hlpnd++);
	
	echo "							</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"".$lwidth."\" height=\"200\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" valign=\"top\">\n";
	
	echo "											<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "											<tr>\n";
	echo "											<td class=\"gray\" valign=\"top\"><b>Customer</b></td>\n";
	echo "											<td class=\"gray\" align=\"right\">\n";
	
	HelpNode('CformViewCustomerPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">First Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"30\" name=\"cfname\" value=\"".trim($rowF['cfname'])."\" ".$dis."></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Last Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"30\" name=\"clname\" value=\"".trim($rowF['clname'])."\" ".$dis."></td>\n";
	echo "											</tr>\n";
    echo "											<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Home Phone</td>\n";
	
	if (isset($rowF['chome']) && strlen($rowF['chome']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"chome\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['chome'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['chome'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['chome'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"chome\" ".$dis."></td>\n";
	}
	
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Work Phone</td>\n";
	
	if (isset($rowF['cwork']) && strlen($rowF['cwork']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" ".$dis."></td>\n";
	}
	
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Cell Phone</td>\n";
	
	if (isset($rowF['ccell']) && strlen($rowF['ccell']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" ".$dis."></td>\n";
	}
	
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Fax</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"12\" name=\"cfax\" value=\"".$rowF['cfax']."\" ".$dis."></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Best Phone</td>\n";
	echo "											<td class=\"gray\" align=\"left\">\n";
	echo "												<select name=\"cconph\">\n";

	if ($rowF['cconph']=="hm")
	{
		echo "													<option value=\"hm\" SELECTED>Home</option>\n";
		echo "													<option value=\"wk\">Work</option>\n";
		echo "													<option value=\"ce\">Cell</option>\n";
	}
	elseif ($rowF['cconph']=="wk")
	{
		echo "													<option value=\"hm\">Home</option>\n";
		echo "													<option value=\"wk\" SELECTED>Work</option>\n";
		echo "													<option value=\"ce\">Cell</option>\n";
	}
	elseif ($rowF['cconph']=="ce")
	{
		echo "													<option value=\"hm\">Home</option>\n";
		echo "													<option value=\"wk\">Work</option>\n";
		echo "													<option value=\"ce\" SELECTED>Cell</option>\n";
	}
	else
	{
		echo "													<option value=\"hm\" SELECTED>Home</option>\n";
		echo "													<option value=\"wk\">Work</option>\n";
		echo "													<option value=\"ce\">Cell</option>\n";
	}

	echo "												</select>\n";
	echo "											</td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Email</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" name=\"cemail\" size=\"30\" value=\"".$rowF['cemail']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Contact Time</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"30\" name=\"ccontime\" value=\"".$rowF['ccontime']."\"></td>\n";
	echo "											</tr>\n";
	echo "											</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"".$rwidth."\" height=\"200\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" valign=\"top\">\n";
	echo "											<table class=\"transnb\" border=\"0\" width=\"100%\">\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" valign=\"top\"><b>Current Address</b></td>\n";
	echo "												<td class=\"gray\" align=\"right\">\n";
	
	HelpNode('CformViewAddressPanel',$hlpnd++);
	
	echo "												</td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Street</td>\n";
	echo "												<td class=\"gray\"><input type=\"text\" size=\"39\" name=\"caddr1\" value=\"".$rowF['caddr1']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">City</td>\n";
	echo "												<td class=\"gray\"><input type=\"text\" size=\"20\" name=\"ccity\" value=\"".$rowF['ccity']."\"> State: <input type=\"text\" size=\"3\" maxlength=\"2\" name=\"cstate\" value=\"".$rowF['cstate']."\"></td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Zip</td>\n";
	echo "												<td class=\"gray\"><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".$rowF['czip1']."\">-<input type=\"text\" size=\"5\" maxlength=\"4\" name=\"czip2\" value=\"".$rowF['czip2']."\"> ".$cmaplink."</td>\n";
	echo "											</tr>\n";
	echo "											<tr>\n";
	echo "												<td class=\"gray\" align=\"right\">Cnty/Twnshp</td>\n";
	echo "												<td class=\"gray\">\n";

	if ($rowC[0]==0)
	{
		echo "												<input type=\"text\" size=\"18\" name=\"ccounty\" value=\"".$rowF['ccounty']."\">\n";
	}
	elseif ($rowC[0]==1)
	{
		echo "												<select name=\"ccounty\">\n";
		while ($rowD = mssql_fetch_row($resD))
		{
			if ($rowD[0]==$rowF['ccounty'])
			{
				echo "												<option value=\"".$rowD[0]."\" SELECTED>".$rowD[2]."</option>\n";
			}
			else
			{
				echo "												<option value=\"".$rowD[0]."\">".$rowD[2]."</option>\n";
			}
		}
		echo "												</select>\n";
	}

	echo "												Map <input type=\"text\" size=\"10\" name=\"cmap\" value=\"".$rowF['cmap']."\">\n";
	echo "												</td>\n";
	echo "											</tr>\n";

	if ($rowF['jobid']=='0' || $_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==FDBK_ADMIN)
	{
		echo "											<tr>\n";

		if ($rowF['ssame']==1)
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED> Same as above</td>\n";
		}
		else
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\"> Same as above</td>\n";
		}

		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Street</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"39\" name=\"saddr1\" value=\"".$rowF['saddr1']."\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">City</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\"> State <input type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\"></td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Zip</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\">-<input type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\"> ".$smaplink."</td>\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Cnty/Twnshp</td>\n";
		echo "												<td class=\"gray\">\n";

		if ($rowC[0]==0)
		{
			echo "													<input type=\"text\" size=\"18\" name=\"scounty\" value=\"".$rowF['scounty']."\">\n";
		}
		elseif ($rowC[0]==1)
		{
			echo "													<select name=\"scounty\">\n";
			while ($rowE = mssql_fetch_row($resE))
			{
				if ($rowE[0]==$rowF['scounty'])
				{
					echo "												<option value=\"".$rowE[0]."\" SELECTED>".$rowE[2]."</option>\n";
				}
				else
				{
					echo "												<option value=\"".$rowE[0]."\">".$rowE[2]."</option>\n";
				}
			}
			echo "														</select>\n";
		}

		echo "											Map <input type=\"text\" size=\"10\" name=\"smap\" value=\"".htmlspecialchars_decode($rowF['smap'])."\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
	}
	else
	{
		echo "											<tr>\n";

		if ($rowF['ssame']==1)
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Pool Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" CHECKED DISABLED> Same as above</td>\n";
			echo "<input type=\"hidden\" name=\"ssame\" value=\"1\">\n";
		}
		else
		{
			echo "												<td class=\"gray\" colspan=\"2\" valign=\"top\"><b>Pool Site Address</b> <input class=\"checkbox\" type=\"checkbox\" name=\"ssame\" value=\"1\" DISABLED> Same as above</td>\n";
			echo "<input type=\"hidden\" name=\"ssame\" value=\"0\">\n";
		}

		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Street:</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"39\" name=\"saddr1\" value=\"".$rowF['saddr1']."\" DISABLED></td>\n";
		echo "<input type=\"hidden\" name=\"saddr1\" value=\"".$rowF['saddr1']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">City:</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"20\" name=\"scity\" value=\"".$rowF['scity']."\" DISABLED> State: <input class=\"bboxl\" type=\"text\" size=\"3\" maxlength=\"2\" name=\"sstate\" value=\"".$rowF['sstate']."\" DISABLED></td>\n";
		echo "<input type=\"hidden\" name=\"scity\" value=\"".$rowF['scity']."\"><input type=\"hidden\" name=\"sstate\" value=\"".$rowF['sstate']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Zip:</td>\n";
		echo "												<td class=\"gray\"><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"szip1\" value=\"".$rowF['szip1']."\" DISABLED>-<input class=\"bboxl\" type=\"text\" size=\"5\" maxlength=\"4\" name=\"szip2\" value=\"".$rowF['szip2']."\" DISABLED> ".$smaplink."</td>\n";
		echo "<input type=\"hidden\" name=\"szip1\" value=\"".$rowF['szip1']."\"><input type=\"hidden\" name=\"szip2\" value=\"".$rowF['szip2']."\">\n";
		echo "											</tr>\n";
		echo "											<tr>\n";
		echo "												<td class=\"gray\" align=\"right\">Cnty/Twnshp:</td>\n";
		echo "												<td class=\"gray\">\n";

		if ($rowC[0]==0)
		{
			echo "													<input type=\"text\" size=\"18\" name=\"scounty\" value=\"".$rowF['scounty']."\" DISABLED>\n";
			echo "<input type=\"hidden\" name=\"scounty\" value=\"".$rowF['scounty']."\">\n";
		}
		elseif ($rowC[0]==1)
		{
			echo "													<select name=\"scounty\" DISABLED>\n";
			while ($rowE = mssql_fetch_row($resE))
			{
				if ($rowE[0]==$rowF['scounty'])
				{
					echo "												<option value=\"".$rowE[0]."\" SELECTED>".$rowE[2]."</option>\n";
				}
				else
				{
					echo "												<option value=\"".$rowE[0]."\">".$rowE[2]."</option>\n";
				}
			}
			echo "														</select>\n";
			echo "<input type=\"hidden\" name=\"scounty\" value=\"".$rowF['scounty']."\">\n";
		}

		echo "											Map: <input type=\"text\" size=\"10\" name=\"smap\" value=\"".$rowF['smap']."\" DISABLED>\n";
		echo "<input type=\"hidden\" name=\"smap\" value=\"".$rowF['smap']."\">\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
	}

	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"".$lwidth."\" height=\"220px\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" valign=\"top\"><b>Appointment/Source/Result</b></td>\n";
	echo "											<td class=\"gray\" align=\"right\">\n";
	
	HelpNode('CformViewApptPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" colspan=\"2\" align=\"center\" valign=\"top\">\n";
	echo "												<table border=0>\n";
	echo "													<tr>\n";
	echo "                        			<td align=\"right\"><b>Lead Contacted</b></td>\n";
	echo "                        			<td align=\"left\" colspan=\"5\">\n";

	if ($rowF['ccontact']==1)
	{
		if (!empty($rowF['ccontactby']) && $rowF['ccontactby']!=0)
		{
			$qryFz = "SELECT securityid,lname,fname,slevel FROM security WHERE securityid='".$rowF['ccontactby']."';";
			$resFz = mssql_query($qryFz);
			$rowFz = mssql_fetch_array($resFz);
			
			$scon	= explode(",",$rowFz['slevel']);
			
			//print_r($scon);
			
			if ($scon[6]==0)
			{
				$cconby=" by <font color=\"red\">".$rowFz['lname'].", ".$rowFz['fname']."</font>";
			}
			else
			{
				$cconby=" by ".$rowFz['lname'].", ".$rowFz['fname'];
			}
		}
		else
		{
			$cconby="";
		}
		
		echo date("m/d/Y",strtotime($rowF['ccontactdate']))." ".$cconby;
		echo "<input type=\"hidden\" name=\"ccontact\" value=\"1\">\n";
	}
	else
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"ccontact\" value=\"1\" title=\"Check this box to indicate the Customer has been contacted.\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "											<td align=\"right\"><b> Appt. Date</b></td>\n";
	echo "														<td valign=\"top\" align=\"left\">\n";
	echo "                                             <select name=\"appt_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		if ($rowF['appt_mo']==$mo)
		{
			echo "																<option value=\"".$mo."\" SELECTED>".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td align=\"left\" valign=\"top\">/</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		if ($rowF['appt_da']==$da)
		{
			echo "																<option value=\"".$da."\" SELECTED>".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td align=\"left\" valign=\"top\">/</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_yr\">\n";
	echo "																<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr-1; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['appt_yr'])
		{
			echo "																<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		}
		else
		{
			//echo "																<option value=\"".$yr."\">".$yr." ($curryr ".$rowF['appt_yr'].")</option>\n";
			echo "																<option value=\"".$yr."\">".$yr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\"><b>Appt. Time</b></td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_hr\">\n";

	for ($hr=0; $hr<=12; $hr++)
	{
		if ($rowF['appt_hr']==$hr)
		{
			echo "																<option value=\"".$hr."\" SELECTED>".$hr."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$hr."\">".$hr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td align=\"left\" valign=\"top\">:</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_mn\">\n";

	for ($mn=0; $mn<=59; $mn++)
	{
		if ($rowF['appt_mn']==$mn)
		{
			echo "																<option value=\"".$mn."\" SELECTED>".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mn."\">".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td align=\"left\" valign=\"top\">:</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_pa\">\n";

	if ($rowF['appt_pa']==1)
	{
		echo "																<option value=\"1\" SELECTED>AM</option>\n";
		echo "																<option value=\"2\">PM</option>\n";
	}
	else
	{
		echo "																<option value=\"1\">AM</option>\n";
		echo "																<option value=\"2\" SELECTED>PM</option>\n";
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\"><b>Lead Source</b></td>\n";

	if (in_array($rowF['source'],$src_ex))
	{
		if ($rowF['source']==0)
		{
			echo "													<td colspan=\"5\" align=\"left\" valign=\"top\">bluehaven.com</td>\n";
			echo "         											<input type=\"hidden\" name=\"source\" value=\"0\">\n";
		}
		else
		{
			$qryGaa = "SELECT statusid,name FROM leadstatuscodes WHERE statusid=".$rowF['source'].";";
			$resGaa = mssql_query($qryGaa);
			$rowGaa = mssql_fetch_array($resGaa);
			echo "													<td colspan=\"5\" align=\"left\" valign=\"top\">".$rowGaa['name']."</td>\n";
			echo "         											<input type=\"hidden\" name=\"source\" value=\"".$rowGaa['statusid']."\">\n";
		}
	}
	else
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";
		echo "                                             				<select name=\"source\">\n";
		
		while ($rowH = mssql_fetch_array($resH))
		{
			if ($_SESSION['llev'] >= $rowH['access'])
			{
				if ($rowH['statusid']==$rowF['source'])
				{
					echo "                                             <option value=\"".$rowH['statusid']."\" SELECTED>".$rowH['name']."</option>\n";
				}
				else
				{
					echo "                                             <option value=\"".$rowH['statusid']."\">".$rowH['name']."</option>\n";
				}
			}
		}

		echo "                                             </select>\n";
		echo "														</td>\n";
	}

	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\"><b>Lead Result</b></td>\n";
	echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";

	if ($rowF['jobid']=='0')
	{
		echo "                                             <select name=\"stage\">\n";
	}
	else
	{
		echo "         										<input type=\"hidden\" name=\"stage\" value=\"".$rowF['stage']."\">\n";
		echo "                                             <select name=\"stage\" DISABLED>\n";
	}

	echo "                                             	<option value=\"1\"></option>\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		if ($rowG['statusid']==$rowF['stage'])
		{
			echo "                                             <option value=\"".$rowG['statusid']."\" SELECTED>".$rowG['name']."</option>\n";
		}
		else
		{
			echo "                                             <option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
		}
	}

	echo "                                             </select>\n";	
	echo "														</td>\n";
	echo "                                 </tr>\n";
	
	if ($_SESSION['emailtemplates'] >= 1 && valid_email_addr(trim($rowF['cemail'])))
	{
		//if ($_SESSION['llev'] >= 5)
		//{			
			echo "                                 <tr>\n";
			echo "                        				<td align=\"right\"><b>Send Email</b></td>\n";
			echo "                        				<td align=\"left\" colspan=\"5\">\n";
			
			unset($_SESSION['et_uid']);
			$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
			
			echo "											<input type=\"hidden\" name=\"etcid[]\" value=\"".$cid."\">\n";
			echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid ."\">\n";
			echo "											<input type=\"hidden\" name=\"chistory\" value=\"1\">\n";
			echo "											<input type=\"hidden\" name=\"etest\" value=\"0\">\n";
			
			selectemailtemplate($rowF['officeid'],$rowF['securityid'],$rowF['cid'],1);
			
			echo "                        				</td>\n";
			echo "                        			</tr>\n";
		//}
	}

	// Call Back Selects
	echo "                                 <tr>\n";
	echo "                        			<td align=\"right\"><b>Call Back</b></td>\n";
	echo "                        			<td align=\"left\" colspan=\"5\">\n";

	if ($rowF['hold']==1)
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" value=\"1\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "                        			<td align=\"right\"><b>on</b></td>\n";
	echo "														<td valign=\"top\">\n";
	
	echo "                                             <select name=\"hold_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		if ($rowF['hold_mo']==$mo)
		{
			echo "																<option value=\"".$mo."\" SELECTED>".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}
	
	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		if ($rowF['hold_da']==$da)
		{
			echo "																<option value=\"".$da."\" SELECTED>".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_yr\">\n";
	echo "																<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr-1; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['hold_yr'])
		{
			echo "																<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$yr."\">".$yr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	
	if ($rowC[2]!=1 && $rowC[3]!=1 && $rowC[4]!=0)
	{	
		if (isset($rowF['finan_src']) && $rowF['finan_src'] > 0)
		{
			$disfr=" DISABLED ";
		}
		else
		{
			$disfr='';
		}
		
		echo "                             			   <tr>\n";
		echo "                        						<td align=\"right\"><b>Finance Release</b></td>\n";
		echo "                        						<td align=\"left\" colspan=\"5\">\n";
		echo "                                    			<select name=\"finansrc\" ".$disfr." title=\"Set the Finance Source\">\n";
		
		if (!isset($rowF['finan_src']) || $rowF['finan_src']==0)
		{
			echo "                                    	<option value=\"0\">Select...</option>\n";
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==1)
		{
			echo "                                    	<option value=\"1\" SELECTED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==2)
		{
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\" selected>Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==3)
		{
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\" selected>Cash</option>\n";
			echo "                                    	<option value=\"4\">BH Finance</option>\n";
		}
		elseif ($rowF['finan_src']==4)
		{
			//echo "                                    	<option value=\"1\" DISABLED>Winners</option>\n";
			echo "                                    	<option value=\"2\">Cust Finan</option>\n";
			echo "                                    	<option value=\"3\">Cash</option>\n";
			echo "                                    	<option value=\"4\" SELECTED>BH Finance</option>\n";
		}
		
		echo "                                    			</select>\n";
		
		if (isset($rowF['finan_src']) && $rowF['finan_src'] > 0)
		{
			echo "												<input type=\"hidden\" name=\"finansrc\" value=\"".$rowF['finan_src']."\">\n";
		}
		
		echo "                        						</td>\n";
		echo "                        					</tr>\n";	
	}
	
	echo "												</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td align=\"right\" valign=\"top\" rowspan=\"3\">\n";
	echo "									<table class=\"outer\" width=\"".$rwidth."\" height=\"525px\">\n";
	echo "										<tr>\n";
	echo "											<td height=\"20px\" class=\"gray\" valign=\"top\" align=\"left\"><b>Comments/Directions</b></td>\n";
	echo "											<td height=\"20px\" width=\"20px\" class=\"gray\" valign=\"top\" align=\"right\">\n";
	echo "												<div id=\"ShowNewCommentBlock\"><img src=\"images/note_add.png\" title=\"Click to Add a Comment\"></div>";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" valign=\"top\" align=\"left\" colspan=\"2\">\n";
	echo "												<table align=\"left\" width=\"100%\">\n";
	echo "													<tr>\n";
	echo "				              							<td class=\"gray\" align=\"center\" valign=\"top\" colspan=\"2\">\n";
	echo "			        										<span id=\"NewCommentBlock\">\n";
	echo "																<textarea name=\"addcomment\" cols=\"85\" rows=\"2\"></textarea>\n";
	echo "		        											</span>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "												</table>\n";
	
	if ($nrowL > 0)
	{
		echo "<table align=\"left\" width=\"100%\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" class=\"gray\" width=\"90px\"><b>Date</b></td>\n";
		echo "      <td align=\"left\" class=\"gray\" width=\"60px\"><b>Name</b></td>\n";
		echo "      <td align=\"center\" class=\"gray\" width=\"30px\"><b>Stage</b></td>\n";
		echo "      <td align=\"center\" class=\"gray\" width=\"30px\"><b>Ticket</b></td>\n";
		echo "      <td align=\"left\" class=\"gray\" width=\"300px\"><b>Comments</b></td>\n";
		echo "   </tr>\n";
	
		$cmntcnt=0;
		while ($rowL = mssql_fetch_array($resL))
		{
			$cmntcnt++;				
			$stage='';
			
			if ($rowL['act']=="leads")
			{
				$stage="<div title=\"Lead\">L</div>";
			}
			elseif ($rowL['act']=="est")
			{
				$stage="<div title=\"Estimate\">E</div>";
			}
			elseif ($rowL['act']=="contract")
			{
				$stage="<div title=\"Contract\">C</div>";
			}
			elseif ($rowL['act']=="jobs")
			{
				$stage="<div title=\"Job\">J</div>";
			}
			elseif ($rowL['act']=="mas")
			{
				$stage="<div title=\"MAS\">M</div>";
			}
			elseif ($rowL['act']=="reports")
			{
				$stage="<div title=\"Reports\">R</div>";
			}
			elseif ($rowL['act']=="fin")
			{
				$stage="<div title=\"Finance\">F</div>";
			}
			elseif ($rowL['act']=="Complaint")
			{
				$stage="<div title=\"Complaint\">CP</div>";
			}
			elseif ($rowL['act']=="Followup")
			{
				$stage="<div title=\"Followup\">FL</div>";
			}
			elseif ($rowL['act']=="Resolved")
			{
				$stage="<div title=\"Resolved\">RS</div>";
			}
			elseif ($rowL['act']=="cresp")
			{
				$stage="<div title=\"Email Response\">ER</div>";
			}
			
			if ($rowL['act']=="Complaint")
			{
				$stage="<div title=\"Complaint\">CP</div>";
				$cmt_tbg="ltred_und";
			}
			elseif ($rowL['act']=="Followup")
			{
				$stage="<div title=\"Followup\">FL</div>";
				$cmt_tbg="ltred_und";
			}
			elseif ($rowL['act']=="Resolved")
			{
				$stage="<div title=\"Resolved\">RS</div>";
				$cmt_tbg="ltgrn_und";
			}
			else
			{
				if ($cmntcnt%2)
				{
					$cmt_tbg="white";
				}
				else
				{
					$cmt_tbg="ltblue";
				}
			}
	
			echo "   <tr>\n";
			echo "      <td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\" NOWRAP><table width=\"100%\"><tr><td align=\"left\">".date('m/d/y',strtotime($rowL['mdate']))."</td><td align=\"right\">".date('g:ia',strtotime($rowL['mdate']))."</td></tr></table></td>\n";
			echo "      <td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\" NOWRAP>".substr($rowL['slname'],0,6)." ".substr($rowL['sfname'],0,1)."</td>\n";
			echo "      <td align=\"center\" valign=\"top\" class=\"".$cmt_tbg."\" NOWRAP>".$stage."</td>\n";
			echo "      <td align=\"left\" valign=\"top\" class=\"".$cmt_tbg."\">\n";
		
			if ($rowL['complaint']==1 && $rowL['followup']==0 && $rowL['resolved']==0)
			{
				echo $rowL['id'];
			}
			elseif ($rowL['complaint']==1 && $rowL['followup']==1 && $rowL['resolved']==0)
			{
				echo $rowL['relatedcomplaint'];
			}
			elseif ($rowL['complaint']==1 && $rowL['followup']==1 && $rowL['resolved']==1)
			{
				echo $rowL['relatedcomplaint'];
			}
	
			echo "		</td>\n";
			echo "      <td align=\"left\" class=\"".$cmt_tbg."\">\n";
	
			echo htmlspecialchars_decode($rowL['mtext']);
	
			echo "		</td>\n";
			echo "   </tr>\n";
		}
	
		echo "</table>\n";
	}
	//}
	//else
	//{
	//	echo "												<iframe src=\"https://jms.bhnmi.com/subs/comments.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"right\"></iframe>\n";
	//}
	
	echo "												<input type=\"hidden\" name=\"comments\" value=\"".$rowF['comments']."\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<table border=0 class=\"outer\" width=\"".$lwidth."\">\n";
	echo "                           			<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" valign=\"top\"><b>Privacy Policy</b></td>\n";
	echo "											<td class=\"gray\" valign=\"top\" align=\"right\">\n";
	
	HelpNode('CformViewPrivacyPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "                           			</tr>\n";
	echo "                           			<tr>\n";
	echo "											<td class=\"gray\" width=\"75px\" valign=\"top\" align=\"right\">\n";
	
	if ($rowF['opt1']==1)
	{
		if ($rowF['source']==0)
		{
			echo "												<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\" title=\"Cannot be Modified. This Lead was sourced from bluehaven.com\" CHECKED DISABLED>\n";
			echo "												<input type=\"hidden\" name=\"opt1\" value=\"1\">\n";
		}
		else
		{
			echo "												<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\" CHECKED>\n";
		}
	}
	else
	{
		echo "												<input class=\"checkbox\" type=\"checkbox\" name=\"opt1\" value=\"1\">\n";
	}

	echo "												<input type=\"hidden\" name=\"opt2\" value=\"0\">\n";
	echo "												<input type=\"hidden\" name=\"opt3\" value=\"0\">\n";
	echo "												<input type=\"hidden\" name=\"opt4\" value=\"0\">\n";
	echo "											</td>\n";
	echo "											<td class=\"gray\" align=\"left\">Customer does not wish to receive any future information about updates, special offers, or other communications regarding Blue Haven-related products, supplies, or services.</td>\n";	
	echo "                           			</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"".$lwidth."\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" valign=\"top\" align=\"left\"><b>Marketing Data</b></td>\n";
	echo "											<td class=\"gray\" valign=\"top\" align=\"right\">\n";

	HelpNode('CformViewMarketProcPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td colspan=\"2\" class=\"gray\" valign=\"top\" align=\"left\">\n";
		
	if (!empty($rowF['mrktproc']))
	{
		echo "												<pre>".wordwrap($rowF['mrktproc'],45)."</pre>\n";
	}
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";

	if (!empty($_REQUEST['subq']) && $_REQUEST['subq']=="history")
	{
		echo "				<tr>\n";
		echo "					<td>\n";
		echo "						<table class=\"outer\" width=\"100%\" height=\"200\">\n";
		echo "							<tr>\n";
		echo "								<td height=\"20px\" class=\"gray\" valign=\"top\"><b>Lead Update History</b></td>\n";
		echo "								<td class=\"gray\" valign=\"top\" align=\"right\">\n";
	
		HelpNode('CformViewLeadHistoryPanel',$hlpnd++);
		
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td colspan=\"2\" class=\"gray\" valign=\"top\" align=\"center\">\n";
		echo "									<iframe src=\"subs/lhistory.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"left\"></iframe>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
	}
	
	echo "	</table>\n";
	echo "	</td>\n";
	echo "	<td align=\"left\" valign=\"top\">\n";
	echo "		<div class=\"noPrint\">\n";
	echo "		<table border=0>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	echo "					<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Update\">\n";
	echo "					</form>\n";
	echo "				</td>\n";	
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_REQUEST['uid']!="XXX")
	{
		echo "      		<form method=\"post\">\n";
		echo "         			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "         			<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
		echo "         			<input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
		echo "         			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
		echo "         			<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
		echo "         			<input type=\"hidden\" name=\"custid\" value=\"".$rowF['custid']."\">\n";
		echo "					<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"OneSheet\"><br>\n";
		echo "				</form>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['llev'] >= 4 && $_REQUEST['uid']!="XXX")
	{
		echo "      		<form method=\"post\">\n";
		echo "         			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "         			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
		echo "         			<input type=\"hidden\" name=\"subq\" value=\"history\">\n";
		echo "         			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
		echo "         			<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
		echo "					<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"History\"><br>\n";
		echo "				</form>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";

	if ($_SESSION['elev'] >= 1 && $rowC[1]==1)
	{
		if ($rowF['dupe']==0 && $rowF['jobid']=='0')
		{
			echo "			<tr>\n";
			echo "				<td valign=\"top\">\n";
			echo "      			<form method=\"post\">\n";
			echo "         				<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "         				<input type=\"hidden\" name=\"call\" value=\"new\">\n";
			echo "         				<input type=\"hidden\" name=\"esttype\" value=\"Q\">\n";
			echo "         				<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
			echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Quote\"><br>\n";
			echo "					</form>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
		}
		
		if ($rowF['dupe']==0 && $rowF['jobid']=='0')
		{
			echo "			<tr>\n";
			echo "				<td valign=\"top\">\n";
			echo "      			<form method=\"post\">\n";
			echo "         				<input type=\"hidden\" name=\"action\" value=\"est\">\n";
			echo "         				<input type=\"hidden\" name=\"call\" value=\"matrix0\">\n";
			echo "         				<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
			echo "         				<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"estorig\" value=\"".$rowF['securityid']."\">\n";
			echo "         				<input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
			echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Estimate\"><br>\n";
			echo "					</form>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
		}
	}
	
	if (isset($rowM['filestoreaccess']) && $rowM['filestoreaccess'] >= 1)
	{
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "					<form method=\"POST\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
		echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
		echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Files\"><br>\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}

	if (isset($_SESSION['tqry']))
	{
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "         			<form name=\"tsearch1\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
		echo "						<input class=\"buttondkgrypnl70\" type=\"submit\" value=\"Search Results\" title=\"Click here to Return to the Last Search Results\">\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}

	echo "		<tr>\n";
	echo "		<td valign=\"top\" align=\"center\">\n";
	
	HelpNode('CformViewLeadButtonsPanel',$hlpnd++);
	
	echo "		</td>\n";
	echo "		</table>\n";
	echo "		</div>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	
	$qryXX	= "UPDATE jest..cinfo SET viewed=getdate(), viewedby=".$_SESSION['securityid']." WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resXX	= mssql_query($qryXX);
	
	/*$qryZ	= "INSERT INTO jest_stats..cinfo_views (oid,sid,cid,vdate) VALUES (".$_SESSION['officeid'].",".$_SESSION['securityid'].",".$cid.",getdate());";
	$resZ	= mssql_query($qryZ);*/
}

function cform_view_TRACK($tcid)
{
	unset($_SESSION['ifcid']); // Security Setting for embedded frames and AJAX
	$src_ex=array();
	$acclist=explode(",",$_SESSION['aid']);

	if (empty($_REQUEST['uid']))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error occured.\n";
		exit;
	}

	if (isset($tcid) && $tcid!=0)
	{
			$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$tcid."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$cid=$row0['cid'];	
	}
	else
	{
		if (isset($_REQUEST['subq']) && $_REQUEST['subq']=="custid")
		{
			$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['custid']."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$cid=$row0['cid'];
		}
		else
		{
			$cid=$_REQUEST['cid'];
		}
	}

	if (empty($cid))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> You must provide a Valid Customer ID number.\n";
		exit;
	}

	$dates	=dateformat();

	if ($_SESSION['llev'] >= 5)
	{
		if ($_SESSION['officeid']==89)
		{
			//echo "Not Admin<br>";
			$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE active=1 ORDER BY grouping,name ASC;";
		}
		else
		{
			//echo "Admin<br>";
			$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE active=1 AND adminonly!=1 ORDER BY grouping,name ASC;";
		}
	}
	else
	{
		$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	}
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	$qryAa = "SELECT officeid,name,stax,enest,encon,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
    $rowAa = mssql_fetch_array($resAa);
	$nrowsAa = mssql_num_rows($resAa);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,sidm,slevel,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY substring(slevel,13,13) desc, lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT stax,enest,encon,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[0]!=0)
	{
		$qryD = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resD = mssql_query($qryD);

		$qryE = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resE = mssql_query($qryE);
	}

	$qryF = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=1 AND access!=0 ORDER by name ASC;";
	$resG = mssql_query($qryG);
	
	//$qryGa = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$qryGa = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$resGa = mssql_query($qryGa);
	
	while ($rowGa = mssql_fetch_array($resGa))
	{
		$src_ex[]=$rowGa['statusid'];
	}

	$qryH = "SELECT * FROM leadstatuscodes WHERE active=2 AND access!=0  AND access!=9 ORDER by name ASC;";
	$resH = mssql_query($qryH);

	$qryI = "SELECT securityid,fname,lname,sidm FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowF['securityid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryJ = "SELECT securityid,sidm,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowF['sidm']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);

	$qryK = "SELECT MIN(appt_yr) as minyr FROM cinfo WHERE officeid='".$_SESSION['officeid']."';";
	$resK = mssql_query($qryK);
	$rowK = mssql_fetch_array($resK);

	$qryL = "SELECT * FROM chistory WHERE officeid='".$_SESSION['officeid']."' AND custid='".$cid."' ORDER BY mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);

	$qryM = "SELECT securityid,emailtemplateaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resM = mssql_query($qryM);
	$rowM = mssql_fetch_array($resM);

	$adate = date("m/d/Y g:i A", strtotime($rowF['added']));
	$udate = date("m/d/Y g:i A", strtotime($rowF['updated']));
	//$sdate = date("m-d-Y (g:i A)", strtotime($rowF['submitted']));
	$curryr=date("Y");
	$futyr =$curryr+2;

	if ($_REQUEST['uid']=="XXX")
	{
		$dis="DISABLED";
	}
	else
	{
		$dis="";
	}

	if ($_SESSION['llev'] < 9 && !in_array($rowI['securityid'],$acclist))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font><br>You do not have appropriate Access to view this Information.\n";
		exit;
	}

	$appt_dt	="";
	if ($rowF['appt_mo']!="00" && $rowF['appt_da']!="00" && $rowF['appt_yr']!="0000")
	{
		
		$appt_dt=old_date_disp($rowF['appt_mo'],$rowF['appt_da'],$rowF['appt_yr'],$rowF['appt_hr'],$rowF['appt_mn'],$rowF['appt_pa']);
	}

	$_SESSION['ifcid']=$rowF['cid'];
	$cmaplink=maplink($rowF['caddr1'],$rowF['ccity'],$rowF['cstate'],$rowF['czip1']);
	$smaplink=maplink($rowF['saddr1'],$rowF['scity'],$rowF['sstate'],$rowF['szip1']);
	$tranid=time().".".$cid.".".$_SESSION['securityid'];
	
	$hlpnd=1;
        
	echo "<div id=\"masterdiv\">\n";
	echo "<table width=\"950px\" align=\"center\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table width=\"100%\" align=\"center\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td>\n";
	echo "      	<form name=\"cview1\" method=\"post\" ".$dis.">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"edit\">\n";
	echo "			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "			<input type=\"hidden\" name=\"tranid\" value=\"".$tranid."\">\n";
	echo "			<input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
    echo "          <input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
    echo "			<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
	echo "			<input type=\"hidden\" name=\"ccounty\" value=\"".$rowF['ccounty']."\">\n";
	//echo "			<input type=\"hidden\" name=\"mrktproc\" value=\"".$rowF['mrktproc']."\">\n";
	echo "			<input type=\"hidden\" name=\"cmap\" value=\"".trim($rowF['cmap'])."\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "				            	<td>\n";
	echo "					               	<table border=\"0\" width=\"100%\">\n";
	echo "					                     <tr>\n";
	echo "					                        <td colspan=\"2\" align=\"left\">\n";
	echo "						               			<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "					                     			<tr>\n";
	echo "					                        			<td class=\"gray\" align=\"left\"><b>Contact Information</b></td>\n";
	echo "					                        			<td class=\"gray\" align=\"right\">\n";

	if ($_SESSION['llev'] >= 5)
	{
		if ($rowF['estid']==0)
		{
			echo "<b>Status</b> <select name=\"dupe\">\n";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"dupe\" value=\"".$rowF['dupe']."\">\n";
			echo "<b>Status</b> <select name=\"stage\" DISABLED>\n";
		}

		//echo "                        	<b>Status:</b> <select name=\"dupe\">\n";

		if ($rowF['dupe']==1)
		{
			echo "<option value=\"1\" SELECTED>Inactive</option>\n";
			echo "<option value=\"0\">Active</option>\n";
		}
		else
		{
			echo "<option value=\"1\">Inactive</option>\n";
			echo "<option value=\"0\" SELECTED>Active</option>\n";
		}
	}
	else
	{
		echo "         <input type=\"hidden\" name=\"dupe\" value=\"0\">\n";
	}

	echo "					                        				</select>\n";
	echo "					                        			</td>\n";
	echo "					                        			<td class=\"gray\" align=\"right\" width=\"20px\">\n";
	
	HelpNode('CformViewHeadPanel',$hlpnd++);
	
	echo "					                        			</td>\n";
	echo "					                        		</tr>\n";
	echo "					                        	</table>\n";
	echo "											</td>\n";
	echo "                    					</tr>\n";
	echo "                     					<tr>\n";
	echo "                        					<td colspan=\"2\" align=\"right\">\n";
	echo "												<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "													<tr>\n";
	echo "														<td class=\"gray\" align=\"left\"><b>Date Added</b> ".$adate."</td>\n";
	echo "														<td class=\"gray\" align=\"left\"><b>Last Update</b> ".$udate."</td>\n";
	echo "														<td class=\"gray\" align=\"right\"><b>Office</b> ".$rowAa['name']."</td>\n";
	echo "													</tr>\n";
	echo "												</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "								    <table class=\"outer\" border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "										<tr class=\"tblhd\">\n";
	echo "											<td height=\"20px\"><b>Contact</b> (".$rowF['cid'].")</td>\n";
	echo "											<td height=\"20px\" align=\"right\">\n";
	
	HelpNode('CformViewCustomerPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
    echo "									    <tr>\n";
	echo "											<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
    echo "                                              <table width=\"100%\">\n";
    echo "										<tr>\n";
    echo "											<td class=\"gray\" width=\"100px\" align=\"right\">Company Name</td>\n";
    echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"cpname\" value=\"".trim($rowF['cpname'])."\"></td>\n";
    echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">First Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"cfname\" value=\"".trim($rowF['cfname'])."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Last Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"clname\" value=\"".trim($rowF['clname'])."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Phone</td>\n";
	
	if (isset($rowF['cwork']) && strlen($rowF['cwork']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" ".$dis."></td>\n";
	}
	
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Cell</td>\n";
	
	if (isset($rowF['ccell']) && strlen($rowF['ccell']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" ".$dis."></td>\n";
	}
	
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Fax</td>\n";
	
	if (isset($rowF['cfax']) && strlen($rowF['cfax']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cfax\" value=\"".htmlspecialchars_decode($rowF['cfax'])."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cfax\" ".$dis."></td>\n";
	}
	
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Email</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" name=\"cemail\" size=\"30\" value=\"".trim($rowF['cemail'])."\"></td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
    echo "											</td>\n";
	echo "										</tr>\n";
    echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "										<tr class=\"tblhd\">\n";
	echo "											<td height=\"20px\"><b>Address</b></td>\n";
	echo "											<td height=\"20px\" align=\"right\">\n";
	
	HelpNode('CformViewAddressPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
    echo "										<tr>\n";
	echo "											<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
    echo "											    <table width=\"100%\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Street</td>\n";
	echo "											<td class=\"gray\"><input type=\"text\" size=\"50\" name=\"caddr1\" value=\"".trim($rowF['caddr1'])."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">City</td>\n";
	echo "											<td class=\"gray\"><input  type=\"text\" size=\"20\" name=\"ccity\" value=\"".trim($rowF['ccity'])."\"></td>\n";
	echo "										</tr>\n";
    echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">State</td>\n";
	echo "											<td class=\"gray\"><input  type=\"text\" size=\"20\" name=\"cstate\" value=\"".trim($rowF['cstate'])."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Zip</td>\n";
	echo "											<td class=\"gray\"><input  type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".trim($rowF['czip1'])."\"> ".$cmaplink."</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
    echo "											<td class=\"gray\"align=\"center\" colspan=\"2\"><hr width=\"90%\"></td>\n";
    echo "										</tr>\n";
	echo "										<tr>\n";
    echo "											<td class=\"gray\" width=\"100px\" align=\"right\">Market</td>\n";
    echo "											<td class=\"gray\" valign=\"top\"><input type=\"text\" size=\"40\" name=\"market\" value=\"".htmlspecialchars_decode(trim($rowF['market']))."\"></td>\n";
    echo "										</tr>\n";
	echo "										<tr>\n";
    echo "											<td class=\"gray\" width=\"100px\" align=\"right\">Type</td>\n";
    echo "											<td class=\"gray\" valign=\"top\"><input type=\"text\" size=\"40\" name=\"cptype\" value=\"".htmlspecialchars_decode(trim($rowF['cptype']))."\"></td>\n";
    echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Service</td>\n";
	echo "											<td class=\"gray\">\n";
	
	if (isset($rowF['trackservice']) && $rowF['trackservice']==1)
	{
		echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackservice\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackservice\" value=\"1\">\n";
	}
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Renovation</td>\n";
	echo "											<td class=\"gray\">\n";
	
	if (isset($rowF['trackrepair']) && $rowF['trackrepair']==1)
	{
		echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackrepair\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackrepair\" value=\"1\">\n";
	}
	
	echo "											</td>\n";
	echo "										</tr>\n";
    echo "									</table>\n";
    echo "											</td>\n";
	echo "										</tr>\n";
    echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
    echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"220\">\n";
	echo "										<tr class=\"tblhd\">\n";
	echo "											<td height=\"20px\" align=\"left\"><b>Appointment/Source/Result</b></td>\n";
	echo "											<td height=\"20px\" align=\"right\">\n";
	
	HelpNode('CformViewApptPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" colspan=\"2\" align=\"center\" valign=\"top\">\n";
	echo "												<table border=0>\n";
	echo "													<tr>\n";
	echo "                                                      <tr>\n";
	echo "                        			                        <td align=\"right\">Contacted</td>\n";
	echo "                        			                        <td align=\"left\" colspan=\"5\">\n";

	if ($rowF['ccontact']==1)
	{
		if (!empty($rowF['ccontactby']) && $rowF['ccontactby']!=0)
		{
			$qryFz = "SELECT securityid,lname,fname,slevel FROM security WHERE securityid='".$rowF['ccontactby']."';";
			$resFz = mssql_query($qryFz);
			$rowFz = mssql_fetch_array($resFz);
			
			$scon	= explode(",",$rowFz['slevel']);
			
			//print_r($scon);
			
			if ($scon[6]==0)
			{
				$cconby=" by <font color=\"red\">".$rowFz['lname'].", ".$rowFz['fname']."</font>";
			}
			else
			{
				$cconby=" by ".$rowFz['lname'].", ".$rowFz['fname'];
			}
		}
		else
		{
			$cconby="";
		}
		
		echo date("m/d/Y",strtotime($rowF['ccontactdate']))." ".$cconby;
		echo "<input type=\"hidden\" name=\"ccontact\" value=\"1\">\n";
	}
	else
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"ccontact\" value=\"1\" title=\"Check this box to indicate the Customer has been contacted.\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "											<td align=\"right\">Appt. Date</td>\n";
	echo "														<td valign=\"top\">\n";
	echo "                                             <select name=\"appt_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		if ($rowF['appt_mo']==$mo)
		{
			echo "																<option value=\"".$mo."\" SELECTED>".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		if ($rowF['appt_da']==$da)
		{
			echo "																<option value=\"".$da."\" SELECTED>".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_yr\">\n";
	echo "																<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr-1; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['appt_yr'])
		{
			echo "																<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		}
		else
		{
			//echo "																<option value=\"".$yr."\">".$yr." ($curryr ".$rowF['appt_yr'].")</option>\n";
			echo "																<option value=\"".$yr."\">".$yr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\">Appt. Time</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_hr\">\n";

	for ($hr=0; $hr<=12; $hr++)
	{
		if ($rowF['appt_hr']==$hr)
		{
			echo "																<option value=\"".$hr."\" SELECTED>".$hr."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$hr."\">".$hr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_mn\">\n";

	for ($mn=0; $mn<=59; $mn++)
	{
		if ($rowF['appt_mn']==$mn)
		{
			echo "																<option value=\"".$mn."\" SELECTED>".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mn."\">".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_pa\">\n";

	if ($rowF['appt_pa']==1)
	{
		echo "																<option value=\"1\" SELECTED>AM</option>\n";
		echo "																<option value=\"2\">PM</option>\n";
	}
	else
	{
		echo "																<option value=\"1\">AM</option>\n";
		echo "																<option value=\"2\" SELECTED>PM</option>\n";
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\">Lead Source</td>\n";

	if (in_array($rowF['source'],$src_ex))
	{
		if ($rowF['source']==0)
		{
			echo "													<td colspan=\"5\" align=\"left\" valign=\"top\">bluehaven.com</td>\n";
			echo "         											<input type=\"hidden\" name=\"source\" value=\"0\">\n";
		}
		else
		{
			$qryGaa = "SELECT statusid,name FROM leadstatuscodes WHERE statusid=".$rowF['source'].";";
			$resGaa = mssql_query($qryGaa);
			$rowGaa = mssql_fetch_array($resGaa);
			echo "													<td colspan=\"5\" align=\"left\" valign=\"top\">".$rowGaa['name']."</td>\n";
			echo "         											<input type=\"hidden\" name=\"source\" value=\"".$rowGaa['statusid']."\">\n";
		}
	}
	else
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";
		echo "                                             				<select name=\"source\">\n";
		
		while ($rowH = mssql_fetch_array($resH))
		{
			if ($_SESSION['llev'] >= $rowH['access'])
			{
				if ($rowH['statusid']==$rowF['source'])
				{
					echo "                                             <option value=\"".$rowH['statusid']."\" SELECTED>".$rowH['name']."</option>\n";
				}
				else
				{
					echo "                                             <option value=\"".$rowH['statusid']."\">".$rowH['name']."</option>\n";
				}
			}
		}

		echo "                                             </select>\n";
		echo "														</td>\n";
	}

	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\">Lead Result</td>\n";
	echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";

	if ($rowF['jobid']=='0')
	{
		echo "                                             <select name=\"stage\">\n";
	}
	else
	{
		echo "         										<input type=\"hidden\" name=\"stage\" value=\"".$rowF['stage']."\">\n";
		echo "                                             <select name=\"stage\" DISABLED>\n";
	}

	echo "                                             	<option value=\"1\"></option>\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		if ($rowG['statusid']==$rowF['stage'])
		{
			echo "                                             <option value=\"".$rowG['statusid']."\" SELECTED>".$rowG['name']."</option>\n";
		}
		else
		{
			echo "                                             <option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
		}
	}

	echo "                                             </select>\n";	
	echo "														</td>\n";
	echo "                                 </tr>\n";
	
	if ($_SESSION['emailtemplates'] >= 1 && valid_email_addr(trim($rowF['cemail'])))
	{
		//if ($_SESSION['llev'] >= 5)
		//{			
			echo "                                 <tr>\n";
			echo "                        				<td align=\"right\">Send Email</td>\n";
			echo "                        				<td align=\"left\" colspan=\"5\">\n";
			
			unset($_SESSION['et_uid']);
			$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
			
			echo "											<input type=\"hidden\" name=\"etcid[]\" value=\"".$cid."\">\n";
			echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid ."\">\n";
			echo "											<input type=\"hidden\" name=\"chistory\" value=\"1\">\n";
			echo "											<input type=\"hidden\" name=\"etest\" value=\"0\">\n";
			
			selectemailtemplate($rowF['officeid'],$rowF['securityid'],$rowF['cid'],1);
			
			echo "                        				</td>\n";
			echo "                        			</tr>\n";
		//}
	}

	// Call Back Selects
	echo "                                 <tr>\n";
	echo "                        			<td align=\"right\">Call Back</td>\n";
	echo "                        			<td align=\"left\" colspan=\"5\">\n";

	if ($rowF['hold']==1)
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" value=\"1\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "                        			<td align=\"right\">on</td>\n";
	echo "														<td valign=\"top\">\n";
	
	echo "                                             <select name=\"hold_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		if ($rowF['hold_mo']==$mo)
		{
			echo "																<option value=\"".$mo."\" SELECTED>".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}
	
	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		if ($rowF['hold_da']==$da)
		{
			echo "																<option value=\"".$da."\" SELECTED>".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_yr\">\n";
	echo "																<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr-1; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['hold_yr'])
		{
			echo "																<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$yr."\">".$yr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "												</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td align=\"right\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"220\">\n";
	echo "										<tr class=\"tblhd\">\n";
	echo "											<td height=\"20px\" width=\"90%\"><b>Comments</b></td>\n";
	echo "											<td height=\"20px\" width=\"5%\" valign=\"top\" align=\"right\">\n";
	echo "												<div class=\"expdiv\" onclick=\"SwitchMenu('newcomment')\"><img src=\"images/note_add.png\" title=\"Click to Add a Comment\"></div>";
	echo "											</td>\n";
	echo "											<td height=\"20px\" width=\"5%\" valign=\"top\" align=\"right\">\n";
	
	HelpNode('CformViewCommentPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "              							<td class=\"gray\" align=\"center\" valign=\"top\" colspan=\"3\">\n";
	echo "        										<span class=\"submenu\" id=\"newcomment\">\n";
	echo "												    <textarea name=\"addcomment\" cols=\"60\" rows=\"3\"></textarea>\n";
	echo "        										</span>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" valign=\"top\" align=\"center\" colspan=\"3\">\n";
	
	echo "												<iframe src=\"subs/comments.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"right\"></iframe>\n";
	echo "												<input type=\"hidden\" name=\"comments\" value=\"".trim($rowF['comments'])."\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
    echo "								<td colspan=\"2\" align=\"right\" valign=\"top\">\n";
    echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"150\">\n";
	echo "										<tr class=\"tblhd\">\n";
    echo "											<td height=\"20px\"><b>Marketing</b></td>\n";
    echo "											<td height=\"20px\" valign=\"top\" align=\"right\">\n";

    HelpNode('CformViewMarketProcPanel',$hlpnd++);
    
    echo "											</td>\n";
    echo "										</tr>\n";
    echo "										<tr>\n";
	echo "											<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
    echo "											    <table width=\"100%\">\n";
    echo "													<tr>\n";
    echo "														<td class=\"gray\" width=\"100px\" align=\"right\" valign=\"top\">Comments</td>\n";
    echo "														<td class=\"gray\" valign=\"top\"><textarea name=\"mrktproc\" cols=\"100\" rows=\"5\">".htmlspecialchars_decode(trim($rowF['mrktproc']))."</textarea></td>\n";
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

	if (!empty($_REQUEST['subq']) && $_REQUEST['subq']=="history")
	{
		echo "				<tr>\n";
		echo "					<td>\n";
		echo "						<table class=\"outer\" width=\"100%\" height=\"200\">\n";
		echo "							<tr>\n";
		echo "								<td height=\"20px\" class=\"gray\" valign=\"top\"><b>Lead Update History</b></td>\n";
		echo "								<td class=\"gray\" valign=\"top\" align=\"right\">\n";
	
		HelpNode('CformViewLeadHistoryPanel',$hlpnd++);
		
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td colspan=\"2\" class=\"gray\" valign=\"top\" align=\"center\">\n";
		echo "									<iframe src=\"subs/lhistory.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"left\"></iframe>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
	}
	
	echo "	</table>\n";
	echo "	</td>\n";
	echo "	<td align=\"left\" valign=\"top\">\n";
	echo "		<table border=0>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	
	if ($_SESSION['officeid']!=194)
	{
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\">\n";
	}
	
	echo "			</form>\n";
	echo "				</td>\n";	
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['llev'] >= 4 && $_REQUEST['uid']!="XXX")
	{
		echo "      		<form method=\"post\">\n";
		echo "         			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "         			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
		echo "         			<input type=\"hidden\" name=\"subq\" value=\"history\">\n";
		echo "         			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
		echo "         			<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"History\"><br>\n";
		echo "				</form>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";

	if (isset($_SESSION['tqry']))
	{
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "         			<form name=\"tsearch1\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search Results\" title=\"Click here to Return to the Last Search Results\">\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}

	echo "		<tr>\n";
	echo "		<td valign=\"top\" align=\"center\">\n";
	
	HelpNode('CformViewLeadButtonsPanel',$hlpnd++);
	
	echo "		</td>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	
	$qryXX	= "UPDATE jest..cinfo SET viewed=getdate(), viewedby=".$_SESSION['securityid']." WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resXX	= mssql_query($qryXX);
	
	/*$qryZ	= "INSERT INTO jest_stats..cinfo_views (oid,sid,cid,vdate) VALUES (".$_SESSION['officeid'].",".$_SESSION['securityid'].",".$cid.",getdate());";
	$resZ	= mssql_query($qryZ);*/
}

function cform_view_VENDOR($tcid)
{
	unset($_SESSION['ifcid']); // Security Setting for embedded frames and AJAX
	$src_ex=array();
	$acclist=explode(",",$_SESSION['aid']);

	if (empty($_REQUEST['uid']))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> A transition Error occured.\n";
		exit;
	}

	if (isset($tcid) && $tcid!=0)
	{
			$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$tcid."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$cid=$row0['cid'];	
	}
	else
	{
		if (isset($_REQUEST['subq']) && $_REQUEST['subq']=="custid")
		{
			$qry0 = "SELECT cid,custid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$_REQUEST['custid']."';";
			$res0 = mssql_query($qry0);
			$row0 = mssql_fetch_array($res0);

			$cid=$row0['cid'];
		}
		else
		{
			$cid=$_REQUEST['cid'];
		}
	}

	if (empty($cid))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font> You must provide a Valid Customer ID number.\n";
		exit;
	}

	$dates	=dateformat();

	if ($_SESSION['llev'] >= 5)
	{
		if ($_SESSION['officeid']==89)
		{
			//echo "Not Admin<br>";
			$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE active=1 ORDER BY grouping,name ASC;";
		}
		else
		{
			//echo "Admin<br>";
			$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE active=1 AND adminonly!=1 ORDER BY grouping,name ASC;";
		}
	}
	else
	{
		$qryA = "SELECT officeid,name,stax,enest FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	}
	$resA = mssql_query($qryA);
	$nrowsA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	$qryAa = "SELECT officeid,name,stax,enest,encon,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resAa = mssql_query($qryAa);
    $rowAa = mssql_fetch_array($resAa);
	$nrowsAa = mssql_num_rows($resAa);

	if ($_SESSION['llev'] >= 4)
	{
		$qryB = "SELECT securityid,fname,lname,sidm,slevel,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' ORDER BY substring(slevel,13,13) desc, lname ASC;";
		$resB = mssql_query($qryB);
		$nrowsB = mssql_num_rows($resB);
	}

	$qryC = "SELECT stax,enest,encon,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[0]!=0)
	{
		$qryD = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resD = mssql_query($qryD);

		$qryE = "SELECT * FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC;";
		$resE = mssql_query($qryE);
	}

	$qryF = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_array($resF);

	$qryG = "SELECT * FROM leadstatuscodes WHERE active=1 AND access!=0 ORDER by name ASC;";
	$resG = mssql_query($qryG);
	
	//$qryGa = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$qryGa = "SELECT statusid FROM leadstatuscodes WHERE access=9;";
	$resGa = mssql_query($qryGa);
	
	while ($rowGa = mssql_fetch_array($resGa))
	{
		$src_ex[]=$rowGa['statusid'];
	}

	$qryH = "SELECT * FROM leadstatuscodes WHERE active=2 AND access!=0  AND access!=9 ORDER by name ASC;";
	$resH = mssql_query($qryH);

	$qryI = "SELECT securityid,fname,lname,sidm FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowF['securityid']."';";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryJ = "SELECT securityid,sidm,assistant FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowF['sidm']."';";
	$resJ = mssql_query($qryJ);
	$rowJ = mssql_fetch_array($resJ);

	$qryK = "SELECT MIN(appt_yr) as minyr FROM cinfo WHERE officeid='".$_SESSION['officeid']."';";
	$resK = mssql_query($qryK);
	$rowK = mssql_fetch_array($resK);

	$qryL = "SELECT * FROM chistory WHERE officeid='".$_SESSION['officeid']."' AND custid='".$cid."' ORDER BY mdate DESC;";
	$resL = mssql_query($qryL);
	$nrowL= mssql_num_rows($resL);

	$qryM = "SELECT securityid,emailtemplateaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$resM = mssql_query($qryM);
	$rowM = mssql_fetch_array($resM);

	$adate = date("m/d/Y g:i A", strtotime($rowF['added']));
	$udate = date("m/d/Y g:i A", strtotime($rowF['updated']));
	//$sdate = date("m-d-Y (g:i A)", strtotime($rowF['submitted']));
	$curryr=date("Y");
	$futyr =$curryr+2;

	if ($_REQUEST['uid']=="XXX")
	{
		$dis="DISABLED";
	}
	else
	{
		$dis="";
	}

	if ($_SESSION['llev'] < 9 && !in_array($rowI['securityid'],$acclist))
	{
		echo "<font color=\"red\"><b>ERROR!</b></font><br>You do not have appropriate Access to view this Information.\n";
		exit;
	}

	$appt_dt	="";
	if ($rowF['appt_mo']!="00" && $rowF['appt_da']!="00" && $rowF['appt_yr']!="0000")
	{
		
		$appt_dt=old_date_disp($rowF['appt_mo'],$rowF['appt_da'],$rowF['appt_yr'],$rowF['appt_hr'],$rowF['appt_mn'],$rowF['appt_pa']);
	}

	$_SESSION['ifcid']=$rowF['cid'];
	$cmaplink=maplink($rowF['caddr1'],$rowF['ccity'],$rowF['cstate'],$rowF['czip1']);
	$smaplink=maplink($rowF['saddr1'],$rowF['scity'],$rowF['sstate'],$rowF['szip1']);
	$tranid=time().".".$cid.".".$_SESSION['securityid'];
	
	$hlpnd=1;
        
	echo "<div id=\"masterdiv\">\n";
	echo "<table width=\"950px\" align=\"center\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table width=\"100%\" align=\"center\" border=0>\n";
	echo "				<tr>\n";
	echo "					<td>\n";
	echo "      	<form name=\"cview1\" method=\"post\" ".$dis.">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"edit\">\n";
	echo "			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$rowF['cid']."\">\n";
	echo "			<input type=\"hidden\" name=\"tranid\" value=\"".$tranid."\">\n";
	echo "			<input type=\"hidden\" name=\"securityid\" value=\"".$rowF['securityid']."\">\n";
    echo "          <input type=\"hidden\" name=\"site\" value=\"".$rowAa['officeid']."\">\n";
    echo "			<input type=\"hidden\" name=\"estorig\" value=\"".$rowI['securityid']."\">\n";
	echo "			<input type=\"hidden\" name=\"ccounty\" value=\"".$rowF['ccounty']."\">\n";
	//echo "			<input type=\"hidden\" name=\"mrktproc\" value=\"".$rowF['mrktproc']."\">\n";
	echo "			<input type=\"hidden\" name=\"cmap\" value=\"".trim($rowF['cmap'])."\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "				            	<td>\n";
	echo "					               	<table border=\"0\" width=\"100%\">\n";
	echo "					                     <tr>\n";
	echo "					                        <td colspan=\"2\" align=\"left\">\n";
	echo "						               			<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "					                     			<tr>\n";
	echo "					                        			<td class=\"gray\" align=\"left\"><b>Contact Information</b></td>\n";
	echo "					                        			<td class=\"gray\" align=\"right\">\n";

	if ($_SESSION['llev'] >= 5)
	{
		if ($rowF['estid']==0)
		{
			echo "<b>Status</b> <select name=\"dupe\">\n";
		}
		else
		{
			echo "<input type=\"hidden\" name=\"dupe\" value=\"".$rowF['dupe']."\">\n";
			echo "<b>Status</b> <select name=\"stage\" DISABLED>\n";
		}

		//echo "                        	<b>Status:</b> <select name=\"dupe\">\n";

		if ($rowF['dupe']==1)
		{
			echo "<option value=\"1\" SELECTED>Inactive</option>\n";
			echo "<option value=\"0\">Active</option>\n";
		}
		else
		{
			echo "<option value=\"1\">Inactive</option>\n";
			echo "<option value=\"0\" SELECTED>Active</option>\n";
		}
	}
	else
	{
		echo "         <input type=\"hidden\" name=\"dupe\" value=\"0\">\n";
	}

	echo "					                        				</select>\n";
	echo "					                        			</td>\n";
	echo "					                        			<td class=\"gray\" align=\"right\" width=\"20px\">\n";
	
	HelpNode('CformViewHeadPanel',$hlpnd++);
	
	echo "					                        			</td>\n";
	echo "					                        		</tr>\n";
	echo "					                        	</table>\n";
	echo "											</td>\n";
	echo "                    					</tr>\n";
	echo "                     					<tr>\n";
	echo "                        					<td colspan=\"2\" align=\"right\">\n";
	echo "												<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "													<tr>\n";
	echo "														<td class=\"gray\" align=\"left\"><b>Date Added</b> ".$adate."</td>\n";
	echo "														<td class=\"gray\" align=\"left\"><b>Last Update</b> ".$udate."</td>\n";
	echo "														<td class=\"gray\" align=\"right\"><b>Office</b> ".$rowAa['name']."</td>\n";
	echo "													</tr>\n";
	echo "												</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "							<tr>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "								    <table class=\"outer\" border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "										<tr class=\"tblhd\">\n";
	echo "											<td height=\"20px\"><b>Vendor</b> (".$rowF['cid'].")</td>\n";
	echo "											<td height=\"20px\" align=\"right\">\n";
	
	HelpNode('CformViewCustomerPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
    echo "									    <tr>\n";
	echo "											<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
    echo "                                              <table width=\"100%\">\n";
    echo "										<tr>\n";
    echo "											<td class=\"gray\" width=\"100px\" align=\"right\">Company Name</td>\n";
    echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"cpname\" value=\"".trim($rowF['cpname'])."\"></td>\n";
    echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">First Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"cfname\" value=\"".trim($rowF['cfname'])."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Last Name</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"40\" name=\"clname\" value=\"".trim($rowF['clname'])."\" ".$dis."></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Phone</td>\n";
	
	if (isset($rowF['cwork']) && strlen($rowF['cwork']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['cwork'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cwork\" ".$dis."></td>\n";
	}
	
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Cell</td>\n";
	
	if (isset($rowF['ccell']) && strlen($rowF['ccell']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" value=\"".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($rowF['ccell'])),6,4)."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"ccell\" ".$dis."></td>\n";
	}
	
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Fax</td>\n";
	
	if (isset($rowF['cfax']) && strlen($rowF['cfax']) > 3)
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cfax\" value=\"".htmlspecialchars_decode($rowF['cfax'])."\" ".$dis."></td>\n";	
	}
	else
	{
		echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" size=\"13\" maxlength=\"20\" name=\"cfax\" ".$dis."></td>\n";
	}
	
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Email</td>\n";
	echo "											<td class=\"gray\" align=\"left\"><input type=\"text\" name=\"cemail\" size=\"30\" value=\"".trim($rowF['cemail'])."\"></td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
    echo "											</td>\n";
	echo "										</tr>\n";
    echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td valign=\"top\" align=\"left\">\n";
	echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"220\">\n";
	echo "										<tr class=\"tblhd\">\n";
	echo "											<td height=\"20px\"><b>Address</b></td>\n";
	echo "											<td height=\"20px\" align=\"right\">\n";
	
	HelpNode('CformViewAddressPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
    echo "										<tr>\n";
	echo "											<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
    echo "											    <table width=\"100%\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Street</td>\n";
	echo "											<td class=\"gray\"><input type=\"text\" size=\"50\" name=\"caddr1\" value=\"".trim($rowF['caddr1'])."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">City</td>\n";
	echo "											<td class=\"gray\"><input  type=\"text\" size=\"20\" name=\"ccity\" value=\"".trim($rowF['ccity'])."\"></td>\n";
	echo "										</tr>\n";
    echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">State</td>\n";
	echo "											<td class=\"gray\"><input  type=\"text\" size=\"20\" name=\"cstate\" value=\"".trim($rowF['cstate'])."\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Zip</td>\n";
	echo "											<td class=\"gray\"><input  type=\"text\" size=\"6\" maxlength=\"5\" name=\"czip1\" value=\"".trim($rowF['czip1'])."\"> ".$cmaplink."</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
    echo "											<td class=\"gray\"align=\"center\" colspan=\"2\"><hr width=\"90%\"></td>\n";
    echo "										</tr>\n";
	echo "										<tr>\n";
    echo "											<td class=\"gray\" width=\"100px\" align=\"right\">Market</td>\n";
    echo "											<td class=\"gray\" valign=\"top\"><input type=\"text\" size=\"40\" name=\"market\" value=\"".htmlspecialchars_decode(trim($rowF['market']))."\"></td>\n";
    echo "										</tr>\n";
	echo "										<tr>\n";
    echo "											<td class=\"gray\" width=\"100px\" align=\"right\">Type</td>\n";
    echo "											<td class=\"gray\" valign=\"top\"><input type=\"text\" size=\"40\" name=\"cptype\" value=\"".htmlspecialchars_decode(trim($rowF['cptype']))."\"></td>\n";
    echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Service</td>\n";
	echo "											<td class=\"gray\">\n";
	
	if (isset($rowF['trackservice']) && $rowF['trackservice']==1)
	{
		echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackservice\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackservice\" value=\"1\">\n";
	}
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"right\">Renovation</td>\n";
	echo "											<td class=\"gray\">\n";
	
	if (isset($rowF['trackrepair']) && $rowF['trackrepair']==1)
	{
		echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackrepair\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "														<input class=\"transnb\" type=\"checkbox\" name=\"trackrepair\" value=\"1\">\n";
	}
	
	echo "											</td>\n";
	echo "										</tr>\n";
    echo "									</table>\n";
    echo "											</td>\n";
	echo "										</tr>\n";
    echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
    echo "							<tr>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"220\">\n";
	echo "										<tr class=\"tblhd\">\n";
	echo "											<td height=\"20px\" align=\"left\"><b>Appointment/Source/Result</b></td>\n";
	echo "											<td height=\"20px\" align=\"right\">\n";
	
	HelpNode('CformViewApptPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" colspan=\"2\" align=\"center\" valign=\"top\">\n";
	echo "												<table border=0>\n";
	echo "													<tr>\n";
	echo "                                                      <tr>\n";
	echo "                        			                        <td align=\"right\">Contacted</td>\n";
	echo "                        			                        <td align=\"left\" colspan=\"5\">\n";

	if ($rowF['ccontact']==1)
	{
		if (!empty($rowF['ccontactby']) && $rowF['ccontactby']!=0)
		{
			$qryFz = "SELECT securityid,lname,fname,slevel FROM security WHERE securityid='".$rowF['ccontactby']."';";
			$resFz = mssql_query($qryFz);
			$rowFz = mssql_fetch_array($resFz);
			
			$scon	= explode(",",$rowFz['slevel']);
			
			//print_r($scon);
			
			if ($scon[6]==0)
			{
				$cconby=" by <font color=\"red\">".$rowFz['lname'].", ".$rowFz['fname']."</font>";
			}
			else
			{
				$cconby=" by ".$rowFz['lname'].", ".$rowFz['fname'];
			}
		}
		else
		{
			$cconby="";
		}
		
		echo date("m/d/Y",strtotime($rowF['ccontactdate']))." ".$cconby;
		echo "<input type=\"hidden\" name=\"ccontact\" value=\"1\">\n";
	}
	else
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"ccontact\" value=\"1\" title=\"Check this box to indicate the Customer has been contacted.\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "											<td align=\"right\">Appt. Date</td>\n";
	echo "														<td valign=\"top\">\n";
	echo "                                             <select name=\"appt_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		if ($rowF['appt_mo']==$mo)
		{
			echo "																<option value=\"".$mo."\" SELECTED>".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		if ($rowF['appt_da']==$da)
		{
			echo "																<option value=\"".$da."\" SELECTED>".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_yr\">\n";
	echo "																<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr-1; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['appt_yr'])
		{
			echo "																<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		}
		else
		{
			//echo "																<option value=\"".$yr."\">".$yr." ($curryr ".$rowF['appt_yr'].")</option>\n";
			echo "																<option value=\"".$yr."\">".$yr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\">Appt. Time</td>\n";
	echo "														<td align=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_hr\">\n";

	for ($hr=0; $hr<=12; $hr++)
	{
		if ($rowF['appt_hr']==$hr)
		{
			echo "																<option value=\"".$hr."\" SELECTED>".$hr."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$hr."\">".$hr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_mn\">\n";

	for ($mn=0; $mn<=59; $mn++)
	{
		if ($rowF['appt_mn']==$mn)
		{
			echo "																<option value=\"".$mn."\" SELECTED>".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mn."\">".str_pad($mn,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">:</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"appt_pa\">\n";

	if ($rowF['appt_pa']==1)
	{
		echo "																<option value=\"1\" SELECTED>AM</option>\n";
		echo "																<option value=\"2\">PM</option>\n";
	}
	else
	{
		echo "																<option value=\"1\">AM</option>\n";
		echo "																<option value=\"2\" SELECTED>PM</option>\n";
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\">Lead Source</td>\n";

	if (in_array($rowF['source'],$src_ex))
	{
		if ($rowF['source']==0)
		{
			echo "													<td colspan=\"5\" align=\"left\" valign=\"top\">bluehaven.com</td>\n";
			echo "         											<input type=\"hidden\" name=\"source\" value=\"0\">\n";
		}
		else
		{
			$qryGaa = "SELECT statusid,name FROM leadstatuscodes WHERE statusid=".$rowF['source'].";";
			$resGaa = mssql_query($qryGaa);
			$rowGaa = mssql_fetch_array($resGaa);
			echo "													<td colspan=\"5\" align=\"left\" valign=\"top\">".$rowGaa['name']."</td>\n";
			echo "         											<input type=\"hidden\" name=\"source\" value=\"".$rowGaa['statusid']."\">\n";
		}
	}
	else
	{
		echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";
		echo "                                             				<select name=\"source\">\n";
		
		while ($rowH = mssql_fetch_array($resH))
		{
			if ($_SESSION['llev'] >= $rowH['access'])
			{
				if ($rowH['statusid']==$rowF['source'])
				{
					echo "                                             <option value=\"".$rowH['statusid']."\" SELECTED>".$rowH['name']."</option>\n";
				}
				else
				{
					echo "                                             <option value=\"".$rowH['statusid']."\">".$rowH['name']."</option>\n";
				}
			}
		}

		echo "                                             </select>\n";
		echo "														</td>\n";
	}

	echo "													</tr>\n";
	echo "													<tr>\n";
	echo "														<td align=\"right\">Lead Result</td>\n";
	echo "														<td colspan=\"5\" align=\"left\" valign=\"top\">\n";

	if ($rowF['jobid']=='0')
	{
		echo "                                             <select name=\"stage\">\n";
	}
	else
	{
		echo "         										<input type=\"hidden\" name=\"stage\" value=\"".$rowF['stage']."\">\n";
		echo "                                             <select name=\"stage\" DISABLED>\n";
	}

	echo "                                             	<option value=\"1\"></option>\n";

	while ($rowG = mssql_fetch_array($resG))
	{
		if ($rowG['statusid']==$rowF['stage'])
		{
			echo "                                             <option value=\"".$rowG['statusid']."\" SELECTED>".$rowG['name']."</option>\n";
		}
		else
		{
			echo "                                             <option value=\"".$rowG['statusid']."\">".$rowG['name']."</option>\n";
		}
	}

	echo "                                             </select>\n";	
	echo "														</td>\n";
	echo "                                 </tr>\n";
	
	if ($_SESSION['emailtemplates'] >= 1 && valid_email_addr(trim($rowF['cemail'])))
	{
		//if ($_SESSION['llev'] >= 5)
		//{			
			echo "                                 <tr>\n";
			echo "                        				<td align=\"right\">Send Email</td>\n";
			echo "                        				<td align=\"left\" colspan=\"5\">\n";
			
			unset($_SESSION['et_uid']);
			$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
			
			echo "											<input type=\"hidden\" name=\"etcid[]\" value=\"".$cid."\">\n";
			echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid ."\">\n";
			echo "											<input type=\"hidden\" name=\"chistory\" value=\"1\">\n";
			echo "											<input type=\"hidden\" name=\"etest\" value=\"0\">\n";
			
			selectemailtemplate($rowF['officeid'],$rowF['securityid'],$rowF['cid'],1);
			
			echo "                        				</td>\n";
			echo "                        			</tr>\n";
		//}
	}

	// Call Back Selects
	echo "                                 <tr>\n";
	echo "                        			<td align=\"right\">Call Back</td>\n";
	echo "                        			<td align=\"left\" colspan=\"5\">\n";

	if ($rowF['hold']==1)
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" value=\"1\" CHECKED>\n";
	}
	else
	{
		echo "<input class=\"transnb\" type=\"checkbox\" name=\"hold\" value=\"1\">\n";
	}

	echo "                        			</td>\n";
	echo "                        		</tr>\n";
	echo "                        		<tr>\n";
	echo "                        			<td align=\"right\">on</td>\n";
	echo "														<td valign=\"top\">\n";
	
	echo "                                             <select name=\"hold_mo\">\n";

	for ($mo=0; $mo<=12; $mo++)
	{
		if ($rowF['hold_mo']==$mo)
		{
			echo "																<option value=\"".$mo."\" SELECTED>".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$mo."\">".str_pad($mo,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}
	
	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_da\">\n";

	for ($da=0; $da<=31; $da++)
	{
		if ($rowF['hold_da']==$da)
		{
			echo "																<option value=\"".$da."\" SELECTED>".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$da."\">".str_pad($da,2,"0",STR_PAD_LEFT)."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">/</td>\n";
	echo "														<td valign=\"left\" valign=\"top\">\n";
	echo "                                             <select name=\"hold_yr\">\n";
	echo "																<option value=\"0000\">0000</option>\n";

	for ($yr=$curryr-1; $yr<=$futyr; $yr++)
	{
		if ($yr==$rowF['hold_yr'])
		{
			echo "																<option value=\"".$yr."\" SELECTED>".$yr."</option>\n";
		}
		else
		{
			echo "																<option value=\"".$yr."\">".$yr."</option>\n";
		}
	}

	echo "                                             </select>\n";
	echo "														</td>\n";
	echo "													</tr>\n";
	echo "												</table>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td align=\"right\" valign=\"top\">\n";
	echo "									<table class=\"outer\" width=\"100%\" height=\"220\">\n";
	echo "										<tr class=\"tblhd\">\n";
	echo "											<td height=\"20px\" width=\"90%\"><b>Comments</b></td>\n";
	echo "											<td height=\"20px\" width=\"5%\" valign=\"top\" align=\"right\">\n";
	echo "												<div class=\"expdiv\" onclick=\"SwitchMenu('newcomment')\"><img src=\"images/note_add.png\" title=\"Click to Add a Comment\"></div>";
	echo "											</td>\n";
	echo "											<td height=\"20px\" width=\"5%\" valign=\"top\" align=\"right\">\n";
	
	HelpNode('CformViewCommentPanel',$hlpnd++);
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "              							<td class=\"gray\" align=\"center\" valign=\"top\" colspan=\"3\">\n";
	echo "        										<span class=\"submenu\" id=\"newcomment\">\n";
	echo "												    <textarea name=\"addcomment\" cols=\"60\" rows=\"3\"></textarea>\n";
	echo "        										</span>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" valign=\"top\" align=\"center\" colspan=\"3\">\n";
	
	echo "												<iframe src=\"subs/comments.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"right\"></iframe>\n";
	echo "												<input type=\"hidden\" name=\"comments\" value=\"".trim($rowF['comments'])."\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";
    echo "								<td colspan=\"2\" align=\"right\" valign=\"top\">\n";
    echo "									<table class=\"outer\" border=\"0\" width=\"100%\" height=\"150\">\n";
	echo "										<tr class=\"tblhd\">\n";
    echo "											<td height=\"20px\"><b>Marketing</b></td>\n";
    echo "											<td height=\"20px\" valign=\"top\" align=\"right\">\n";

    HelpNode('CformViewMarketProcPanel',$hlpnd++);
    
    echo "											</td>\n";
    echo "										</tr>\n";
    echo "										<tr>\n";
	echo "											<td colspan=\"2\" class=\"gray\" valign=\"top\">\n";
    echo "											    <table width=\"100%\">\n";
    echo "													<tr>\n";
    echo "														<td class=\"gray\" width=\"100px\" align=\"right\" valign=\"top\">Comments</td>\n";
    echo "														<td class=\"gray\" valign=\"top\"><textarea name=\"mrktproc\" cols=\"100\" rows=\"5\">".htmlspecialchars_decode(trim($rowF['mrktproc']))."</textarea></td>\n";
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

	if (!empty($_REQUEST['subq']) && $_REQUEST['subq']=="history")
	{
		echo "				<tr>\n";
		echo "					<td>\n";
		echo "						<table class=\"outer\" width=\"100%\" height=\"200\">\n";
		echo "							<tr>\n";
		echo "								<td height=\"20px\" class=\"gray\" valign=\"top\"><b>Lead Update History</b></td>\n";
		echo "								<td class=\"gray\" valign=\"top\" align=\"right\">\n";
	
		HelpNode('CformViewLeadHistoryPanel',$hlpnd++);
		
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td colspan=\"2\" class=\"gray\" valign=\"top\" align=\"center\">\n";
		echo "									<iframe src=\"subs/lhistory.php\" frameborder=\"0\" width=\"100%\" height=\"100%\" align=\"left\"></iframe>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "				</tr>\n";
	}
	
	echo "	</table>\n";
	echo "	</td>\n";
	echo "	<td align=\"left\" valign=\"top\">\n";
	echo "		<table border=0>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";
	
	if ($_SESSION['officeid']!=194)
	{
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Update\">\n";
	}
	
	echo "			</form>\n";
	echo "				</td>\n";	
	echo "			</tr>\n";
	echo "			<tr>\n";
	echo "				<td valign=\"top\">\n";

	if ($_SESSION['llev'] >= 4 && $_REQUEST['uid']!="XXX")
	{
		echo "      		<form method=\"post\">\n";
		echo "         			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "         			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
		echo "         			<input type=\"hidden\" name=\"subq\" value=\"history\">\n";
		echo "         			<input type=\"hidden\" name=\"uid\" value=\"".$_REQUEST['uid']."\">\n";
		echo "         			<input type=\"hidden\" name=\"cid\" value=\"".$cid."\">\n";
		echo "					<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"History\"><br>\n";
		echo "				</form>\n";
	}

	echo "				</td>\n";
	echo "			</tr>\n";

	if (isset($_SESSION['tqry']))
	{
		echo "			<tr>\n";
		echo "				<td valign=\"top\">\n";
		echo "         			<form name=\"tsearch1\" method=\"post\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "						<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
		echo "						<input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search Results\" title=\"Click here to Return to the Last Search Results\">\n";
		echo "					</form>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
	}

	echo "		<tr>\n";
	echo "		<td valign=\"top\" align=\"center\">\n";
	
	HelpNode('CformViewLeadButtonsPanel',$hlpnd++);
	
	echo "		</td>\n";
	echo "		</table>\n";
	echo "	</td>\n";
	echo "	</tr>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	
	$qryXX	= "UPDATE jest..cinfo SET viewed=getdate(), viewedby=".$_SESSION['securityid']." WHERE officeid='".$_SESSION['officeid']."' AND cid='".$cid."';";
	$resXX	= mssql_query($qryXX);
	
	/*$qryZ	= "INSERT INTO jest_stats..cinfo_views (oid,sid,cid,vdate) VALUES (".$_SESSION['officeid'].",".$_SESSION['securityid'].",".$cid.",getdate());";
	$resZ	= mssql_query($qryZ);*/
}

function display_CB_AP_TRACK()
{
	//$dev_ar= array(SYS_ADMIN);
	$dev_ar= array(2699999999999999999999999);
	
	echo "<table class=\"transnb\" align=\"center\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"transnb\" align=\"left\">\n";
	echo "			<div id=\"AP_CB_menu\" class=\"yui-navset\">\n";
	echo "				<ul class=\"yui-nav\">\n";
	
	if (isset($_REQUEST['shownm']) && $_REQUEST['shownm']==1)
	{
		echo "					<li><a href=\"#ap\"><em>Appointments</em></a></li>\n";
		echo "			    	<li><a href=\"#cb\"><em>Callbacks</em></a></li>\n";
		
		//if ($_SESSION['llev'] >= 5)
		//{
		//	echo "			    	<li class=\"selected\"><a href=\"#nm\"><em>New from BHNM</em></a></li>\n";
		//}
		
		/*if ($_SESSION['securityid']==SYS_ADMIN ||$_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==1950 || $_SESSION['securityid']==1550)
		{
			echo "			    	<li><a href=\"#hl\"><em>Followup</em></a></li>\n";
		}*/
		
		//if (in_array($_SESSION['securityid'],$dev_ar))
		//{
		//	echo "			    	<li><a href=\"#sc\"><em>Search</em></a></li>\n";
		//}
	}
	elseif (isset($_REQUEST['showcb']) && $_REQUEST['showcb']==1)
	{
		echo "					<li><a href=\"#ap\"><em>Appointments</em></a></li>\n";
		echo "			    	<li class=\"selected\"><a href=\"#cb\"><em>Callbacks</em></a></li>\n";
		
		//if ($_SESSION['llev'] >= 5)
		//{
		//	echo "			    	<li><a href=\"#nm\"><em>New from BHNM</em></a></li>\n";
		//}
		
		/*if ($_SESSION['securityid']==SYS_ADMIN ||$_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==1950 || $_SESSION['securityid']==1550)
		{
			echo "			    	<li><a href=\"#hl\"><em>Followup</em></a></li>\n";
		}*/
		
		//if (in_array($_SESSION['securityid'],$dev_ar))
		//{
		//	echo "			    	<li><a href=\"#sc\"><em>Search</em></a></li>\n";
		//}
	}
	else
	{
		echo "					<li class=\"selected\"><a href=\"#ap\"><em>Appointments</em></a></li>\n";
		echo "			    	<li><a href=\"#cb\"><em>Callbacks</em></a></li>\n";
		
		//if ($_SESSION['llev'] >= 5)
		//{
		//	echo "			    	<li><a href=\"#nm\"><em>New from BHNM</em></a></li>\n";
		//}
		//
		///*if ($_SESSION['securityid']==SYS_ADMIN ||$_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==1950 || $_SESSION['securityid']==1550)
		//{
		//	echo "			    	<li><a href=\"#hl\"><em>Followup</em></a></li>\n";
		//}*/
		//
		//if (in_array($_SESSION['securityid'],$dev_ar))
		//{
		//	echo "			    	<li><a href=\"#sc\"><em>Search</em></a></li>\n";
		//}
	}
	
	echo "				</ul>\n";
	echo "				<div class=\"yui-content\">\n";
    echo "				    <div id=\"ap\">\n";
	echo "						<p>\n";
	echo "			<table class=\"transnb\" width=\"100%\">\n";
	echo "				<tr>\n";
	
	$nph_ar= array('**********','0000000000','none','N/A','na');
	
	$qryAP = "
		select 
			cid,officeid,securityid,lname,fname,sidm,clname,cfname,saddr1,scity,sstate,szip1,added,updated,
			apptmnt,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,
			chome,cwork,ccell 
		from 
			list_cinfo 
		where
			officeid=".$_SESSION['officeid']."
			";
		
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			$qryAP  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qryAP  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
		}
	}		
	elseif ($_SESSION['llev'] < 4)
	{
		$qryAP .= "	and securityid=".$_SESSION['securityid']." ";
	}
	
	$qryAP .= " 	
			and apptmnt BETWEEN '".date('m/d/y',time())."' and (getdate()+14)
			and appt_yr!=0
			and dupe!=1
		order by
			apptmnt asc
	";
	$resAP = mssql_query($qryAP);
	$nrowAP= mssql_num_rows($resAP);
	
	if ($nrowAP > 0)
	{
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"lightgreen\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
		echo "					<td align=\"left\" class=\"lightgreen\" width=\"150\"><b>Customer</b></td>\n";
		echo "					<td align=\"left\" class=\"lightgreen\"><b>City</b></td>\n";
		echo "					<td align=\"center\" class=\"lightgreen\"><b>Zip</b></td>\n";
		echo "					<td align=\"left\" class=\"lightgreen\"><b>SalesRep</b></td>\n";
		echo "					<td align=\"center\" class=\"lightgreen\"><b>Appt Date/Time</b></td>\n";
		echo "					<td align=\"center\" class=\"lightgreen\"><b>Phone</b></td>\n";
		echo "					<td align=\"center\" class=\"lightgreen\">\n";
		
		HelpNode('AppointPanel',7);
		
		echo "					</td>\n";
		echo "				</tr>\n";
		
		$rcntAP=1;
		while ($rowAP= mssql_fetch_array($resAP))
		{
			$ccntAP++;
			if ($ccntAP%2)
			{
				$tbgAP = 'white';
			}
			else
			{
				$tbgAP = 'ltgray';
			}
			
			$uidAP  =md5(session_id().time().$rowAP['cid']).".".$_SESSION['securityid'];
			echo "				<tr>\n";
			echo "					<td align=\"right\" class=\"".$tbgAP."\">".$rcntAP++.".</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgAP."\">".trim($rowAP['clname']).", ".trim($rowAP['cfname'])."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgAP."\">".trim($rowAP['scity'])."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgAP."\">".$rowAP['szip1']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgAP."\">".$rowAP['lname'].", ".$rowAP['fname']."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgAP."\">\n";
			echo "						<table width=\"100\">\n";
			echo "							<tr>\n";
			echo "								<td align=\"left\">\n";
			echo "						".str_pad($rowAP['appt_mo'],2,'0',STR_PAD_LEFT)."/".str_pad($rowAP['appt_da'],2,'0',STR_PAD_LEFT)."/".$rowAP['appt_yr']." ";	
			echo "								</td>\n";
			echo "								<td align=\"right\">\n";
			echo "						".$rowAP['appt_hr'].":". str_pad(trim($rowAP['appt_mn']), 2, STR_PAD_LEFT) ."";
			
			if ($rowAP['appt_pa']==2)
			{
				echo 'PM';
			}
			else
			{
				echo 'AM';
			}
			
			echo "								</td>\n";
			echo "							</tr>\n";
			echo "						</table>\n";
			echo "					</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgAP."\">";
			
			if (isset($rowAP['chome']) && !in_array($rowAP['chome'],$nph_ar) && strlen($rowAP['chome']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowAP['chome']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowAP['chome'])));
			}
			elseif (isset($rowAP['ccell']) && !in_array($rowAP['ccell'],$nph_ar) && strlen($rowAP['ccell']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowAP['ccell']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowAP['ccell'])));
			}
			elseif (isset($rowAP['cwork']) && !in_array($rowAP['cwork'],$nph_ar) && strlen($rowAP['cwork']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowAP['cwork']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowAP['cwork'])));
			}
			
			echo "					</td>\n";
			echo "					<td class=\"".$tbgAP."\" align=\"center\">\n";
			echo "					<form method=\"POST\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowAP['cid']."\">\n";
			echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uidAP."\">\n";
			
			if ($_SESSION['officeid']==$rowAP['officeid'])
			{
				echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
			}
			
			echo "					</form>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			
			if (in_array($_SESSION['securityid'],$dev_ar))
			{
				echo "				<tr>\n";
				echo "					<td align=\"left\" class=\"".$tbgAP."\"><img src=\"images/pixel.gif\"></td>\n";
				echo "					<td colspan=\"9\" align=\"left\" class=\"".$tbgAP."\">\n";
				
				@gen_CustLinkNode($rowAP['cid'],$uidAP,$rowAP['saddr1'],$rowAP['scity'],$rowAP['sstate'],$rowAP['szip1']);
				
				echo "					</td>\n";
				echo "				</tr>\n";
			}
		}
	}
	else
	{
		echo "				<tr>\n";
		echo "					<td align=\"center\"><b>None</b></td>\n";
		echo "				</tr>\n";
	}
	
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "						</p>\n";
	echo "				    </div>\n";
	echo "				    <div id=\"cb\">\n";
	echo "						<p>\n";
	echo "			<table class=\"transnb\" width=\"100%\">\n";
	echo "				<tr>\n";
	
	$qryCB  = "
		select 
			cid,officeid,securityid,lname,fname,sidm,clname,cfname,saddr1,scity,sstate,szip1,szip1,added,updated,
			hold,hold_until,hold_mo,hold_da,hold_yr,
			chome,cwork,ccell,cemail
		from 
			list_cinfo 
		where
			officeid=".$_SESSION['officeid']."
			and hold=1 ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			//$qryCB  .= "	AND sidm='".$_SESSION['assto']."' OR securityid='".$_SESSION['assto']."' OR securityid='".$_SESSION['securityid']."' ";
			$qryCB  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qryCB  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			//$qryCB  .= "	AND sidm='".$_SESSION['securityid']."' OR securityid='".$_SESSION['securityid']."' ";
		}
	}		
	elseif ($_SESSION['llev'] < 4)
	{
		$qryCB .= "	and securityid=".$_SESSION['securityid']." ";
	}
			
	$qryCB .= "
			and callback BETWEEN (getdate() - 2) and (getdate()+14)
			and dupe!=1
		order by
			callback asc
	";
	$resCB = mssql_query($qryCB);
	$nrowCB= mssql_num_rows($resCB);
	
	if ($nrowCB > 0)
	{
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"magenta\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
		echo "					<td align=\"left\" class=\"magenta\" width=\"150\"><b>Customer</b></td>\n";
		echo "					<td align=\"left\" class=\"magenta\"><b>City</b></td>\n";
		echo "					<td align=\"left\" class=\"magenta\"><b>Zip</b></td>\n";
		echo "					<td align=\"left\" class=\"magenta\"><b>SalesRep</b></td>\n";
		echo "					<td align=\"center\" class=\"magenta\"><b>Callback Date</b></td>\n";
		echo "					<td align=\"center\" class=\"magenta\"><b>Phone</b></td>\n";
		echo "					<td align=\"center\" class=\"magenta\">\n";
		
		HelpNode('CallbackPanel',8);
		
		echo "					</td>\n";
		echo "				</tr>\n";	
		
		$rcntCB=1;
		while ($rowCB= mssql_fetch_array($resCB))
		{
			$ccntCB++;
			if ($ccntCB%2)
			{
				$tbgCB = 'white';
			}
			else
			{
				$tbgCB = 'ltgray';
			}
			
			$uidCB  =md5(session_id().time().$rowCB['cid']).".".$_SESSION['securityid'];
			echo "				<tr>\n";
			echo "					<td align=\"right\" class=\"".$tbgCB."\">".$rcntCB++.".</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['clname'].", ".$rowCB['cfname']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['scity']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['szip1']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['lname'].", ".$rowCB['fname']."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgCB."\">".$rowCB['hold_mo']."/".$rowCB['hold_da']."/".$rowCB['hold_yr']."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgCB."\">";
			
			if (isset($rowCB['chome']) && !in_array($rowCB['chome'],$nph_ar) && strlen($rowCB['chome']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowCB['chome']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowCB['chome'])));
			}
			elseif (isset($rowCB['ccell']) && !in_array($rowCB['ccell'],$nph_ar) && strlen($rowCB['ccell']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowCB['ccell']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowCB['ccell'])));
			}
			elseif (isset($rowCB['cwork']) && !in_array($rowCB['cwork'],$nph_ar) && strlen($rowCB['cwork']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowCB['cwork']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowCB['cwork'])));
			}
			
			echo "					</td>\n";
			echo "					<td class=\"".$tbgCB."\" align=\"center\">\n";
			echo "					<form method=\"POST\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowCB['cid']."\">\n";
			echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uidCB."\">\n";
			
			if ($_SESSION['officeid']==$rowCB['officeid'])
			{
				echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
			}
			
			echo "					</form>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			
			if (in_array($_SESSION['securityid'],$dev_ar))
			{
				echo "				<tr>\n";
				echo "					<td align=\"left\" class=\"".$tbgCB."\"><img src=\"images/pixel.gif\"></td>\n";
				echo "					<td colspan=\"9\" align=\"left\" class=\"".$tbgCB."\">\n";
				
				@gen_CustLinkNode($rowCB['cid'],$uidCB,$rowCB['saddr1'],$rowCB['scity'],$rowCB['sstate'],$rowCB['szip1']);
				
				echo "					</td>\n";
				echo "				</tr>\n";
			}
		}
	}
	else
	{
		echo "				<tr>\n";
		echo "					<td align=\"center\"><b>None</b></td>\n";
		echo "				</tr>\n";
	}
	
	echo "			</table>\n";
	echo "						</p>\n";
	echo "				    </div>\n";
	
	if ($_SESSION['llev'] >= 99999)
	{
		echo "				    <div id=\"nm\">\n";
		echo "						<p>\n";
		echo "			<table class=\"transnb\" width=\"100%\">\n";
		echo "				<tr>\n";
		
		$sdate	 =set_sdate();
		
		$nph_ar= array('**********','0000000000','none','N/A','na');
		
		$qryNM = "
			select 
				cid,officeid,securityid,lname,fname,sidm,clname,cfname,saddr1,scity,sstate,szip1,szip1,added,updated,
				apptmnt,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,source,(select name from leadstatuscodes where statusid=LC.source) as lsource,
				chome,cwork,ccell 
			from 
				list_cinfo AS LC
			where
				officeid=".$_SESSION['officeid']."
				";
			
		if ($_SESSION['llev'] == 4)
		{
			if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
			{
				$qryNM  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
			}
			else
			{
				$qryNM  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			}
		}		
		elseif ($_SESSION['llev'] < 4)
		{
			$qryNM .= "	and securityid=".$_SESSION['securityid']." ";
		}
		
		$qryNM .= " 	
				and added >= '".$sdate[0]."'
				and source in (select statusid from leadstatuscodes where provided=1)
			order by
				added asc
		";
		$resNM = mssql_query($qryNM);
		$nrowNM= mssql_num_rows($resNM);
		
		//echo $qryNM.'<br>';
		
		if ($nrowNM > 0)
		{
			echo "				<tr>\n";
			echo "					<td align=\"center\" class=\"gray\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
			echo "					<td align=\"left\" class=\"gray\" width=\"150\"><b>Customer</b></td>\n";
			echo "					<td align=\"left\" class=\"gray\"><b>City</b></td>\n";
			echo "					<td align=\"center\" class=\"gray\"><b>Zip</b></td>\n";
			echo "					<td align=\"left\" class=\"gray\"><b>Assigned</b></td>\n";
			echo "					<td align=\"left\" class=\"gray\"><b>Source</b></td>\n";
			echo "					<td align=\"center\" class=\"gray\"><b>Source Date</b></td>\n";
			echo "					<td align=\"center\" class=\"gray\"><b>Phone</b></td>\n";
			echo "					<td align=\"center\" class=\"gray\">\n";
		
			HelpNode('NewfromBHNMPanel',9);
		
			echo "					</td>\n";
			echo "				</tr>\n";
			
			$rcntNM=1;
			while ($rowNM= mssql_fetch_array($resNM))
			{
				$ccntNM++;
				if ($ccntNM%2)
				{
					$tbgNM = 'white';
				}
				else
				{
					$tbgNM = 'ltgray';
				}
				
				$uidNM  =md5(session_id().time().$rowNM['cid']).".".$_SESSION['securityid'];
				echo "				<tr>\n";
				echo "					<td align=\"right\" class=\"".$tbgNM."\">".$rcntNM++.".</td>\n";
				echo "					<td align=\"left\" class=\"".$tbgNM."\">".trim($rowNM['clname']).", ".trim($rowNM['cfname'])."</td>\n";
				echo "					<td align=\"left\" class=\"".$tbgNM."\">".trim($rowNM['scity'])."</td>\n";
				echo "					<td align=\"center\" class=\"".$tbgNM."\">".$rowNM['szip1']."</td>\n";				
				echo "					<td align=\"left\" class=\"".$tbgNM."\">".trim($rowNM['lname']).", ".trim($rowNM['fname'])."</td>\n";
				
				if ($rowNM['source']==0)
				{
					echo "					<td align=\"left\" class=\"".$tbgNM."\">bluehaven.com</td>\n";
				}
				else
				{
					echo "					<td align=\"left\" class=\"".$tbgNM."\">".$rowNM['lsource']."</td>\n";
				}
				
				echo "					<td align=\"center\" class=\"".$tbgNM."\">\n";
				echo "						<table width=\"90\">\n";
				echo "							<tr>\n";
				echo "								<td align=\"left\">\n";
				echo date('m/d/y',strtotime($rowNM['added']));
				echo "								</td>\n";
				echo "								<td align=\"right\">\n";
				echo date('g:iA',strtotime($rowNM['added']));
				echo "								</td>\n";
				echo "							</tr>\n";
				echo "						</table>\n";
				echo "					</td>\n";
				echo "					<td align=\"center\" class=\"".$tbgNM."\">";
				
				if (isset($rowNM['chome']) && !in_array($rowNM['chome'],$nph_ar) && strlen($rowNM['chome']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowNM['chome']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowNM['chome'])));
				}
				elseif (isset($rowNM['ccell']) && !in_array($rowNM['ccell'],$nph_ar) && strlen($rowNM['ccell']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowNM['ccell']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowNM['ccell'])));
				}
				elseif (isset($rowNM['cwork']) && !in_array($rowNM['cwork'],$nph_ar) && strlen($rowNM['cwork']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowNM['cwork']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowNM['cwork'])));
				}
				
				echo "					</td>\n";
				echo "					<td class=\"".$tbgNM."\" align=\"center\">\n";
				echo "					<form method=\"POST\">\n";
				echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
				echo "						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
				echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowNM['cid']."\">\n";
				echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uidNM."\">\n";
				
				if ($_SESSION['officeid']==$rowNM['officeid'])
				{
					echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
				}
				
				echo "					</form>\n";
				echo "					</td>\n";
				echo "				</tr>\n";
				
				if (in_array($_SESSION['securityid'],$dev_ar))
			{
					echo "				<tr>\n";
					echo "					<td align=\"left\" class=\"".$tbgNM."\"><img src=\"images/pixel.gif\"></td>\n";
					echo "					<td colspan=\"9\" align=\"left\" class=\"".$tbgNM."\">\n";
					
					@gen_CustLinkNode($rowNM['cid'],$uidNM,$rowNM['saddr1'],$rowNM['scity'],$rowNM['sstate'],$rowNM['szip1']);
					
					echo "					</td>\n";
					echo "				</tr>\n";
				}
			}
		}
		else
		{
			echo "				<tr>\n";
			echo "					<td align=\"center\"><b>None</b></td>\n";
			echo "				</tr>\n";
		}
		
		echo "			</table>\n";
		echo "						</p>\n";
		echo "				    </div>\n";
	}
	
	if ($_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==1950 || $_SESSION['securityid']==1550)
	{
		/*echo "				    <div id=\"hl\">\n";
		echo "						<p>\n";
		echo "			<table class=\"transnb\" width=\"100%\">\n";
		echo "				<tr>\n";
		
		$sdate	 =set_sdate();
		
		$nph_ar= array('**********','0000000000','none','N/A','na');
		
		$qryHL = "
			select 
				cid,officeid,securityid,saddr1,scity,sstate,szip1,(select lname from security where securityid=LC.securityid) as lname,(select fname from security where securityid=LC.securityid) as fname,sidm,clname,cfname,scity,szip1,added,updated,
				apptmnt,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,source,(select name from leadstatuscodes where statusid=LC.source) as lsource,
				chome,cwork,ccell,(select count(id) from chistory where custid=LC.cid) as lcnt
			from 
				cinfo AS LC
			where
				officeid=".$_SESSION['officeid']."
				";
			
		if ($_SESSION['llev'] == 4)
		{
			if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
			{
				$qryHL  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
			}
			else
			{
				$qryHL  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			}
		}		
		elseif ($_SESSION['llev'] < 4)
		{
			$qryHL .= "	and securityid=".$_SESSION['securityid']." ";
		}
		
		$qryHL .= " 
				and added between DATEADD(dd,-14,getdate()) and getdate()
				and stage <= 1
			order by
				added asc
		";
		$resHL = mssql_query($qryHL);
		$nrowHL= mssql_num_rows($resHL);
		
		if ($_SESSION['securityid']==26999999999999999)
		{
			echo $qryHL.'<br>';
		}
		
		if ($nrowHL > 0)
		{
			echo "				<tr>\n";
			echo "					<td align=\"center\" class=\"lightcoral\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
			echo "					<td align=\"left\" class=\"lightcoral\" width=\"150\"><b>Customer</b></td>\n";
			echo "					<td align=\"left\" class=\"lightcoral\"><b>City</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><b>Zip</b></td>\n";
			echo "					<td align=\"left\" class=\"lightcoral\"><b>Assigned</b></td>\n";
			echo "					<td align=\"left\" class=\"lightcoral\"><b>Source</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><b>Source Date</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><b>Phone</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><b>Cmnts</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><img src=\"images/help.png\" title=\"Lead(s) added in the last 30 days without a Result Code\"></td>\n";
			echo "				</tr>\n";
			
			$rcntHL=1;
			while ($rowHL= mssql_fetch_array($resHL))
			{
				$ccntHL++;
				if ($ccntHL%2)
				{
					$tbgHL = 'white';
				}
				else
				{
					$tbgHL = 'ltgray';
				}
				
				$uidHL  =md5(session_id().time().$rowHL['cid']).".".$_SESSION['securityid'];
				echo "				<tr>\n";
				echo "					<td align=\"right\" class=\"".$tbgHL."\">".$rcntHL++.".</td>\n";
				echo "					<td align=\"left\" class=\"".$tbgHL."\">".trim($rowHL['clname']).", ".trim($rowHL['cfname'])."</td>\n";
				echo "					<td align=\"left\" class=\"".$tbgHL."\">".trim($rowHL['scity'])."</td>\n";
				echo "					<td align=\"center\" class=\"".$tbgHL."\">".$rowHL['szip1']."</td>\n";				
				echo "					<td align=\"left\" class=\"".$tbgHL."\">".trim($rowHL['lname']).", ".trim($rowHL['fname'])."</td>\n";
				
				if ($rowHL['source']==0)
				{
					echo "					<td align=\"left\" class=\"".$tbgHL."\">bluehaven.com</td>\n";
				}
				else
				{
					echo "					<td align=\"left\" class=\"".$tbgHL."\">".$rowHL['lsource']."</td>\n";
				}
				
				echo "					<td align=\"center\" class=\"".$tbgHL."\">\n";
				echo "						<table width=\"90\">\n";
				echo "							<tr>\n";
				echo "								<td align=\"left\">\n";
				echo date('m/d/y',strtotime($rowHL['added']));
				echo "								</td>\n";
				echo "								<td align=\"right\">\n";
				echo date('g:iA',strtotime($rowHL['added']));
				echo "								</td>\n";
				echo "							</tr>\n";
				echo "						</table>\n";
				echo "					</td>\n";
				echo "					<td align=\"center\" class=\"".$tbgHL."\">";
				
				if (isset($rowHL['chome']) && !in_array($rowHL['chome'],$nph_ar) && strlen($rowHL['chome']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowHL['chome']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowHL['chome'])));
				}
				elseif (isset($rowHL['ccell']) && !in_array($rowHL['ccell'],$nph_ar) && strlen($rowHL['ccell']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowHL['ccell']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowHL['ccell'])));
				}
				elseif (isset($rowHL['cwork']) && !in_array($rowHL['cwork'],$nph_ar) && strlen($rowHL['cwork']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowHL['cwork']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowHL['cwork'])));
				}
				
				echo "					</td>\n";
				echo "					<td class=\"".$tbgHL."\" align=\"center\">".$rowHL['lcnt']."</td>\n";
				echo "					<td class=\"".$tbgHL."\" align=\"center\">\n";
				echo "					<form method=\"POST\">\n";
				echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
				echo "						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
				echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowHL['cid']."\">\n";
				echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uidHL."\">\n";
				
				if ($_SESSION['officeid']==$rowHL['officeid'])
				{
					echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
				}
				
				echo "					</form>\n";
				echo "					</td>\n";
				echo "				</tr>\n";
				
				if (in_array($_SESSION['securityid'],$dev_ar))
				{
					echo "				<tr>\n";
					echo "					<td align=\"left\" class=\"".$tbgHL."\"><img src=\"images/pixel.gif\"></td>\n";
					echo "					<td colspan=\"9\" align=\"left\" class=\"".$tbgHL."\">\n";
					
					@gen_CustLinkNode($rowHL['cid'],$uidHL,$rowHL['saddr1'],$rowHL['scity'],$rowHL['sstate'],$rowHL['szip1']);
					
					echo "					</td>\n";
					echo "				</tr>\n";
				}
			}
		}
		else
		{
			echo "				<tr>\n";
			echo "					<td align=\"center\"><b>None</b></td>\n";
			echo "				</tr>\n";
		}
		
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "						</p>\n";
		echo "				    </div>\n";*/
		
		if (in_array($_SESSION['securityid'],$dev_ar))
		{
			echo "				    <div id=\"sc\">\n";
			echo "						<p>\n";
			
			search_panel();
			
			echo "						</p>\n";
			echo "				    </div>\n";
		}
		
	}
	
	echo "				</div>\n";
	echo "			</div>\n";
	echo "
	
	<script> 
	(function() {
		var tabView = new YAHOO.widget.TabView('AP_CB_menu');
	})();
	</script>
	
	";
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function display_CB_AP_VENDOR()
{
	//$dev_ar= array(SYS_ADMIN);
	$dev_ar= array(2699999999999999999999999);
	
	echo "<table class=\"transnb\" align=\"center\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"transnb\" align=\"left\">\n";
	echo "			<div id=\"AP_CB_menu\" class=\"yui-navset\">\n";
	echo "				<ul class=\"yui-nav\">\n";
	
	if (isset($_REQUEST['shownm']) && $_REQUEST['shownm']==1)
	{
		echo "					<li><a href=\"#ap\"><em>Appointments</em></a></li>\n";
		echo "			    	<li><a href=\"#cb\"><em>Callbacks</em></a></li>\n";
		
		//if ($_SESSION['llev'] >= 5)
		//{
		//	echo "			    	<li class=\"selected\"><a href=\"#nm\"><em>New from BHNM</em></a></li>\n";
		//}
		
		/*if ($_SESSION['securityid']==SYS_ADMIN ||$_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==1950 || $_SESSION['securityid']==1550)
		{
			echo "			    	<li><a href=\"#hl\"><em>Followup</em></a></li>\n";
		}*/
		
		//if (in_array($_SESSION['securityid'],$dev_ar))
		//{
		//	echo "			    	<li><a href=\"#sc\"><em>Search</em></a></li>\n";
		//}
	}
	elseif (isset($_REQUEST['showcb']) && $_REQUEST['showcb']==1)
	{
		echo "					<li><a href=\"#ap\"><em>Appointments</em></a></li>\n";
		echo "			    	<li class=\"selected\"><a href=\"#cb\"><em>Callbacks</em></a></li>\n";
		
		//if ($_SESSION['llev'] >= 5)
		//{
		//	echo "			    	<li><a href=\"#nm\"><em>New from BHNM</em></a></li>\n";
		//}
		
		/*if ($_SESSION['securityid']==SYS_ADMIN ||$_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==1950 || $_SESSION['securityid']==1550)
		{
			echo "			    	<li><a href=\"#hl\"><em>Followup</em></a></li>\n";
		}*/
		
		//if (in_array($_SESSION['securityid'],$dev_ar))
		//{
		//	echo "			    	<li><a href=\"#sc\"><em>Search</em></a></li>\n";
		//}
	}
	else
	{
		echo "					<li class=\"selected\"><a href=\"#ap\"><em>Appointments</em></a></li>\n";
		echo "			    	<li><a href=\"#cb\"><em>Callbacks</em></a></li>\n";
		
		//if ($_SESSION['llev'] >= 5)
		//{
		//	echo "			    	<li><a href=\"#nm\"><em>New from BHNM</em></a></li>\n";
		//}
		//
		///*if ($_SESSION['securityid']==SYS_ADMIN ||$_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==1950 || $_SESSION['securityid']==1550)
		//{
		//	echo "			    	<li><a href=\"#hl\"><em>Followup</em></a></li>\n";
		//}*/
		//
		//if (in_array($_SESSION['securityid'],$dev_ar))
		//{
		//	echo "			    	<li><a href=\"#sc\"><em>Search</em></a></li>\n";
		//}
	}
	
	echo "				</ul>\n";
	echo "				<div class=\"yui-content\">\n";
    echo "				    <div id=\"ap\">\n";
	echo "						<p>\n";
	echo "			<table class=\"transnb\" width=\"100%\">\n";
	echo "				<tr>\n";
	
	$nph_ar= array('**********','0000000000','none','N/A','na');
	
	$qryAP = "
		select 
			cid,officeid,securityid,lname,fname,sidm,clname,cfname,saddr1,scity,sstate,szip1,added,updated,
			apptmnt,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,
			chome,cwork,ccell 
		from 
			list_cinfo 
		where
			officeid=".$_SESSION['officeid']."
			";
		
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			$qryAP  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qryAP  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
		}
	}		
	elseif ($_SESSION['llev'] < 4)
	{
		$qryAP .= "	and securityid=".$_SESSION['securityid']." ";
	}
	
	$qryAP .= " 	
			and apptmnt BETWEEN '".date('m/d/y',time())."' and (getdate()+14)
			and appt_yr!=0
			and dupe!=1
		order by
			apptmnt asc
	";
	$resAP = mssql_query($qryAP);
	$nrowAP= mssql_num_rows($resAP);
	
	if ($nrowAP > 0)
	{
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"lightgreen\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
		echo "					<td align=\"left\" class=\"lightgreen\" width=\"150\"><b>Customer</b></td>\n";
		echo "					<td align=\"left\" class=\"lightgreen\"><b>City</b></td>\n";
		echo "					<td align=\"center\" class=\"lightgreen\"><b>Zip</b></td>\n";
		echo "					<td align=\"left\" class=\"lightgreen\"><b>SalesRep</b></td>\n";
		echo "					<td align=\"center\" class=\"lightgreen\"><b>Appt Date/Time</b></td>\n";
		echo "					<td align=\"center\" class=\"lightgreen\"><b>Phone</b></td>\n";
		echo "					<td align=\"center\" class=\"lightgreen\">\n";
		
		HelpNode('AppointPanel',7);
		
		echo "					</td>\n";
		echo "				</tr>\n";
		
		$rcntAP=1;
		while ($rowAP= mssql_fetch_array($resAP))
		{
			$ccntAP++;
			if ($ccntAP%2)
			{
				$tbgAP = 'white';
			}
			else
			{
				$tbgAP = 'ltgray';
			}
			
			$uidAP  =md5(session_id().time().$rowAP['cid']).".".$_SESSION['securityid'];
			echo "				<tr>\n";
			echo "					<td align=\"right\" class=\"".$tbgAP."\">".$rcntAP++.".</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgAP."\">".trim($rowAP['clname']).", ".trim($rowAP['cfname'])."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgAP."\">".trim($rowAP['scity'])."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgAP."\">".$rowAP['szip1']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgAP."\">".$rowAP['lname'].", ".$rowAP['fname']."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgAP."\">\n";
			echo "						<table width=\"100\">\n";
			echo "							<tr>\n";
			echo "								<td align=\"left\">\n";
			echo "						".str_pad($rowAP['appt_mo'],2,'0',STR_PAD_LEFT)."/".str_pad($rowAP['appt_da'],2,'0',STR_PAD_LEFT)."/".$rowAP['appt_yr']." ";	
			echo "								</td>\n";
			echo "								<td align=\"right\">\n";
			echo "						".$rowAP['appt_hr'].":". str_pad(trim($rowAP['appt_mn']), 2, STR_PAD_LEFT) ."";
			
			if ($rowAP['appt_pa']==2)
			{
				echo 'PM';
			}
			else
			{
				echo 'AM';
			}
			
			echo "								</td>\n";
			echo "							</tr>\n";
			echo "						</table>\n";
			echo "					</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgAP."\">";
			
			if (isset($rowAP['chome']) && !in_array($rowAP['chome'],$nph_ar) && strlen($rowAP['chome']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowAP['chome']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowAP['chome'])));
			}
			elseif (isset($rowAP['ccell']) && !in_array($rowAP['ccell'],$nph_ar) && strlen($rowAP['ccell']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowAP['ccell']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowAP['ccell'])));
			}
			elseif (isset($rowAP['cwork']) && !in_array($rowAP['cwork'],$nph_ar) && strlen($rowAP['cwork']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowAP['cwork']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowAP['cwork'])));
			}
			
			echo "					</td>\n";
			echo "					<td class=\"".$tbgAP."\" align=\"center\">\n";
			echo "					<form method=\"POST\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowAP['cid']."\">\n";
			echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uidAP."\">\n";
			
			if ($_SESSION['officeid']==$rowAP['officeid'])
			{
				echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
			}
			
			echo "					</form>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			
			if (in_array($_SESSION['securityid'],$dev_ar))
			{
				echo "				<tr>\n";
				echo "					<td align=\"left\" class=\"".$tbgAP."\"><img src=\"images/pixel.gif\"></td>\n";
				echo "					<td colspan=\"9\" align=\"left\" class=\"".$tbgAP."\">\n";
				
				@gen_CustLinkNode($rowAP['cid'],$uidAP,$rowAP['saddr1'],$rowAP['scity'],$rowAP['sstate'],$rowAP['szip1']);
				
				echo "					</td>\n";
				echo "				</tr>\n";
			}
		}
	}
	else
	{
		echo "				<tr>\n";
		echo "					<td align=\"center\"><b>None</b></td>\n";
		echo "				</tr>\n";
	}
	
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "						</p>\n";
	echo "				    </div>\n";
	echo "				    <div id=\"cb\">\n";
	echo "						<p>\n";
	echo "			<table class=\"transnb\" width=\"100%\">\n";
	echo "				<tr>\n";
	
	$qryCB  = "
		select 
			cid,officeid,securityid,lname,fname,sidm,clname,cfname,saddr1,scity,sstate,szip1,szip1,added,updated,
			hold,hold_until,hold_mo,hold_da,hold_yr,
			chome,cwork,ccell,cemail
		from 
			list_cinfo 
		where
			officeid=".$_SESSION['officeid']."
			and hold=1 ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
		{
			//$qryCB  .= "	AND sidm='".$_SESSION['assto']."' OR securityid='".$_SESSION['assto']."' OR securityid='".$_SESSION['securityid']."' ";
			$qryCB  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
		}
		else
		{
			$qryCB  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			//$qryCB  .= "	AND sidm='".$_SESSION['securityid']."' OR securityid='".$_SESSION['securityid']."' ";
		}
	}		
	elseif ($_SESSION['llev'] < 4)
	{
		$qryCB .= "	and securityid=".$_SESSION['securityid']." ";
	}
			
	$qryCB .= "
			and callback BETWEEN (getdate() - 2) and (getdate()+14)
			and dupe!=1
		order by
			callback asc
	";
	$resCB = mssql_query($qryCB);
	$nrowCB= mssql_num_rows($resCB);
	
	if ($nrowCB > 0)
	{
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"magenta\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
		echo "					<td align=\"left\" class=\"magenta\" width=\"150\"><b>Customer</b></td>\n";
		echo "					<td align=\"left\" class=\"magenta\"><b>City</b></td>\n";
		echo "					<td align=\"left\" class=\"magenta\"><b>Zip</b></td>\n";
		echo "					<td align=\"left\" class=\"magenta\"><b>SalesRep</b></td>\n";
		echo "					<td align=\"center\" class=\"magenta\"><b>Callback Date</b></td>\n";
		echo "					<td align=\"center\" class=\"magenta\"><b>Phone</b></td>\n";
		echo "					<td align=\"center\" class=\"magenta\">\n";
		
		HelpNode('CallbackPanel',8);
		
		echo "					</td>\n";
		echo "				</tr>\n";	
		
		$rcntCB=1;
		while ($rowCB= mssql_fetch_array($resCB))
		{
			$ccntCB++;
			if ($ccntCB%2)
			{
				$tbgCB = 'white';
			}
			else
			{
				$tbgCB = 'ltgray';
			}
			
			$uidCB  =md5(session_id().time().$rowCB['cid']).".".$_SESSION['securityid'];
			echo "				<tr>\n";
			echo "					<td align=\"right\" class=\"".$tbgCB."\">".$rcntCB++.".</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['clname'].", ".$rowCB['cfname']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['scity']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['szip1']."</td>\n";
			echo "					<td align=\"left\" class=\"".$tbgCB."\">".$rowCB['lname'].", ".$rowCB['fname']."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgCB."\">".$rowCB['hold_mo']."/".$rowCB['hold_da']."/".$rowCB['hold_yr']."</td>\n";
			echo "					<td align=\"center\" class=\"".$tbgCB."\">";
			
			if (isset($rowCB['chome']) && !in_array($rowCB['chome'],$nph_ar) && strlen($rowCB['chome']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowCB['chome']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowCB['chome'])));
			}
			elseif (isset($rowCB['ccell']) && !in_array($rowCB['ccell'],$nph_ar) && strlen($rowCB['ccell']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowCB['ccell']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowCB['ccell'])));
			}
			elseif (isset($rowCB['cwork']) && !in_array($rowCB['cwork'],$nph_ar) && strlen($rowCB['cwork']) > 2)
			{
				//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowCB['cwork']);
				echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowCB['cwork'])));
			}
			
			echo "					</td>\n";
			echo "					<td class=\"".$tbgCB."\" align=\"center\">\n";
			echo "					<form method=\"POST\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowCB['cid']."\">\n";
			echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uidCB."\">\n";
			
			if ($_SESSION['officeid']==$rowCB['officeid'])
			{
				echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
			}
			
			echo "					</form>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			
			if (in_array($_SESSION['securityid'],$dev_ar))
			{
				echo "				<tr>\n";
				echo "					<td align=\"left\" class=\"".$tbgCB."\"><img src=\"images/pixel.gif\"></td>\n";
				echo "					<td colspan=\"9\" align=\"left\" class=\"".$tbgCB."\">\n";
				
				@gen_CustLinkNode($rowCB['cid'],$uidCB,$rowCB['saddr1'],$rowCB['scity'],$rowCB['sstate'],$rowCB['szip1']);
				
				echo "					</td>\n";
				echo "				</tr>\n";
			}
		}
	}
	else
	{
		echo "				<tr>\n";
		echo "					<td align=\"center\"><b>None</b></td>\n";
		echo "				</tr>\n";
	}
	
	echo "			</table>\n";
	echo "						</p>\n";
	echo "				    </div>\n";
	
	if ($_SESSION['llev'] >= 99999)
	{
		echo "				    <div id=\"nm\">\n";
		echo "						<p>\n";
		echo "			<table class=\"transnb\" width=\"100%\">\n";
		echo "				<tr>\n";
		
		$sdate	 =set_sdate();
		
		$nph_ar= array('**********','0000000000','none','N/A','na');
		
		$qryNM = "
			select 
				cid,officeid,securityid,lname,fname,sidm,clname,cfname,saddr1,scity,sstate,szip1,szip1,added,updated,
				apptmnt,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,source,(select name from leadstatuscodes where statusid=LC.source) as lsource,
				chome,cwork,ccell 
			from 
				list_cinfo AS LC
			where
				officeid=".$_SESSION['officeid']."
				";
			
		if ($_SESSION['llev'] == 4)
		{
			if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
			{
				$qryNM  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
			}
			else
			{
				$qryNM  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			}
		}		
		elseif ($_SESSION['llev'] < 4)
		{
			$qryNM .= "	and securityid=".$_SESSION['securityid']." ";
		}
		
		$qryNM .= " 	
				and added >= '".$sdate[0]."'
				and source in (select statusid from leadstatuscodes where provided=1)
			order by
				added asc
		";
		$resNM = mssql_query($qryNM);
		$nrowNM= mssql_num_rows($resNM);
		
		//echo $qryNM.'<br>';
		
		if ($nrowNM > 0)
		{
			echo "				<tr>\n";
			echo "					<td align=\"center\" class=\"gray\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
			echo "					<td align=\"left\" class=\"gray\" width=\"150\"><b>Customer</b></td>\n";
			echo "					<td align=\"left\" class=\"gray\"><b>City</b></td>\n";
			echo "					<td align=\"center\" class=\"gray\"><b>Zip</b></td>\n";
			echo "					<td align=\"left\" class=\"gray\"><b>Assigned</b></td>\n";
			echo "					<td align=\"left\" class=\"gray\"><b>Source</b></td>\n";
			echo "					<td align=\"center\" class=\"gray\"><b>Source Date</b></td>\n";
			echo "					<td align=\"center\" class=\"gray\"><b>Phone</b></td>\n";
			echo "					<td align=\"center\" class=\"gray\">\n";
		
			HelpNode('NewfromBHNMPanel',9);
		
			echo "					</td>\n";
			echo "				</tr>\n";
			
			$rcntNM=1;
			while ($rowNM= mssql_fetch_array($resNM))
			{
				$ccntNM++;
				if ($ccntNM%2)
				{
					$tbgNM = 'white';
				}
				else
				{
					$tbgNM = 'ltgray';
				}
				
				$uidNM  =md5(session_id().time().$rowNM['cid']).".".$_SESSION['securityid'];
				echo "				<tr>\n";
				echo "					<td align=\"right\" class=\"".$tbgNM."\">".$rcntNM++.".</td>\n";
				echo "					<td align=\"left\" class=\"".$tbgNM."\">".trim($rowNM['clname']).", ".trim($rowNM['cfname'])."</td>\n";
				echo "					<td align=\"left\" class=\"".$tbgNM."\">".trim($rowNM['scity'])."</td>\n";
				echo "					<td align=\"center\" class=\"".$tbgNM."\">".$rowNM['szip1']."</td>\n";				
				echo "					<td align=\"left\" class=\"".$tbgNM."\">".trim($rowNM['lname']).", ".trim($rowNM['fname'])."</td>\n";
				
				if ($rowNM['source']==0)
				{
					echo "					<td align=\"left\" class=\"".$tbgNM."\">bluehaven.com</td>\n";
				}
				else
				{
					echo "					<td align=\"left\" class=\"".$tbgNM."\">".$rowNM['lsource']."</td>\n";
				}
				
				echo "					<td align=\"center\" class=\"".$tbgNM."\">\n";
				echo "						<table width=\"90\">\n";
				echo "							<tr>\n";
				echo "								<td align=\"left\">\n";
				echo date('m/d/y',strtotime($rowNM['added']));
				echo "								</td>\n";
				echo "								<td align=\"right\">\n";
				echo date('g:iA',strtotime($rowNM['added']));
				echo "								</td>\n";
				echo "							</tr>\n";
				echo "						</table>\n";
				echo "					</td>\n";
				echo "					<td align=\"center\" class=\"".$tbgNM."\">";
				
				if (isset($rowNM['chome']) && !in_array($rowNM['chome'],$nph_ar) && strlen($rowNM['chome']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowNM['chome']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowNM['chome'])));
				}
				elseif (isset($rowNM['ccell']) && !in_array($rowNM['ccell'],$nph_ar) && strlen($rowNM['ccell']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowNM['ccell']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowNM['ccell'])));
				}
				elseif (isset($rowNM['cwork']) && !in_array($rowNM['cwork'],$nph_ar) && strlen($rowNM['cwork']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowNM['cwork']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowNM['cwork'])));
				}
				
				echo "					</td>\n";
				echo "					<td class=\"".$tbgNM."\" align=\"center\">\n";
				echo "					<form method=\"POST\">\n";
				echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
				echo "						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
				echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowNM['cid']."\">\n";
				echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uidNM."\">\n";
				
				if ($_SESSION['officeid']==$rowNM['officeid'])
				{
					echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
				}
				
				echo "					</form>\n";
				echo "					</td>\n";
				echo "				</tr>\n";
				
				if (in_array($_SESSION['securityid'],$dev_ar))
			{
					echo "				<tr>\n";
					echo "					<td align=\"left\" class=\"".$tbgNM."\"><img src=\"images/pixel.gif\"></td>\n";
					echo "					<td colspan=\"9\" align=\"left\" class=\"".$tbgNM."\">\n";
					
					@gen_CustLinkNode($rowNM['cid'],$uidNM,$rowNM['saddr1'],$rowNM['scity'],$rowNM['sstate'],$rowNM['szip1']);
					
					echo "					</td>\n";
					echo "				</tr>\n";
				}
			}
		}
		else
		{
			echo "				<tr>\n";
			echo "					<td align=\"center\"><b>None</b></td>\n";
			echo "				</tr>\n";
		}
		
		echo "			</table>\n";
		echo "						</p>\n";
		echo "				    </div>\n";
	}
	
	if ($_SESSION['securityid']==SYS_ADMIN || $_SESSION['securityid']==FDBK_ADMIN || $_SESSION['securityid']==1950 || $_SESSION['securityid']==1550)
	{
		/*echo "				    <div id=\"hl\">\n";
		echo "						<p>\n";
		echo "			<table class=\"transnb\" width=\"100%\">\n";
		echo "				<tr>\n";
		
		$sdate	 =set_sdate();
		
		$nph_ar= array('**********','0000000000','none','N/A','na');
		
		$qryHL = "
			select 
				cid,officeid,securityid,saddr1,scity,sstate,szip1,(select lname from security where securityid=LC.securityid) as lname,(select fname from security where securityid=LC.securityid) as fname,sidm,clname,cfname,scity,szip1,added,updated,
				apptmnt,appt_mo,appt_da,appt_yr,appt_hr,appt_mn,appt_pa,source,(select name from leadstatuscodes where statusid=LC.source) as lsource,
				chome,cwork,ccell,(select count(id) from chistory where custid=LC.cid) as lcnt
			from 
				cinfo AS LC
			where
				officeid=".$_SESSION['officeid']."
				";
			
		if ($_SESSION['llev'] == 4)
		{
			if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
			{
				$qryHL  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
			}
			else
			{
				$qryHL  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			}
		}		
		elseif ($_SESSION['llev'] < 4)
		{
			$qryHL .= "	and securityid=".$_SESSION['securityid']." ";
		}
		
		$qryHL .= " 
				and added between DATEADD(dd,-14,getdate()) and getdate()
				and stage <= 1
			order by
				added asc
		";
		$resHL = mssql_query($qryHL);
		$nrowHL= mssql_num_rows($resHL);
		
		if ($_SESSION['securityid']==26999999999999999)
		{
			echo $qryHL.'<br>';
		}
		
		if ($nrowHL > 0)
		{
			echo "				<tr>\n";
			echo "					<td align=\"center\" class=\"lightcoral\" width=\"20\"><img src=\"images/pixel.gif\"></td>\n";
			echo "					<td align=\"left\" class=\"lightcoral\" width=\"150\"><b>Customer</b></td>\n";
			echo "					<td align=\"left\" class=\"lightcoral\"><b>City</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><b>Zip</b></td>\n";
			echo "					<td align=\"left\" class=\"lightcoral\"><b>Assigned</b></td>\n";
			echo "					<td align=\"left\" class=\"lightcoral\"><b>Source</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><b>Source Date</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><b>Phone</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><b>Cmnts</b></td>\n";
			echo "					<td align=\"center\" class=\"lightcoral\"><img src=\"images/help.png\" title=\"Lead(s) added in the last 30 days without a Result Code\"></td>\n";
			echo "				</tr>\n";
			
			$rcntHL=1;
			while ($rowHL= mssql_fetch_array($resHL))
			{
				$ccntHL++;
				if ($ccntHL%2)
				{
					$tbgHL = 'white';
				}
				else
				{
					$tbgHL = 'ltgray';
				}
				
				$uidHL  =md5(session_id().time().$rowHL['cid']).".".$_SESSION['securityid'];
				echo "				<tr>\n";
				echo "					<td align=\"right\" class=\"".$tbgHL."\">".$rcntHL++.".</td>\n";
				echo "					<td align=\"left\" class=\"".$tbgHL."\">".trim($rowHL['clname']).", ".trim($rowHL['cfname'])."</td>\n";
				echo "					<td align=\"left\" class=\"".$tbgHL."\">".trim($rowHL['scity'])."</td>\n";
				echo "					<td align=\"center\" class=\"".$tbgHL."\">".$rowHL['szip1']."</td>\n";				
				echo "					<td align=\"left\" class=\"".$tbgHL."\">".trim($rowHL['lname']).", ".trim($rowHL['fname'])."</td>\n";
				
				if ($rowHL['source']==0)
				{
					echo "					<td align=\"left\" class=\"".$tbgHL."\">bluehaven.com</td>\n";
				}
				else
				{
					echo "					<td align=\"left\" class=\"".$tbgHL."\">".$rowHL['lsource']."</td>\n";
				}
				
				echo "					<td align=\"center\" class=\"".$tbgHL."\">\n";
				echo "						<table width=\"90\">\n";
				echo "							<tr>\n";
				echo "								<td align=\"left\">\n";
				echo date('m/d/y',strtotime($rowHL['added']));
				echo "								</td>\n";
				echo "								<td align=\"right\">\n";
				echo date('g:iA',strtotime($rowHL['added']));
				echo "								</td>\n";
				echo "							</tr>\n";
				echo "						</table>\n";
				echo "					</td>\n";
				echo "					<td align=\"center\" class=\"".$tbgHL."\">";
				
				if (isset($rowHL['chome']) && !in_array($rowHL['chome'],$nph_ar) && strlen($rowHL['chome']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowHL['chome']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowHL['chome'])));
				}
				elseif (isset($rowHL['ccell']) && !in_array($rowHL['ccell'],$nph_ar) && strlen($rowHL['ccell']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowHL['ccell']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowHL['ccell'])));
				}
				elseif (isset($rowHL['cwork']) && !in_array($rowHL['cwork'],$nph_ar) && strlen($rowHL['cwork']) > 2)
				{
					//echo preg_replace('/\.|-|\s/i','$1$2$3',$rowHL['cwork']);
					echo format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($rowHL['cwork'])));
				}
				
				echo "					</td>\n";
				echo "					<td class=\"".$tbgHL."\" align=\"center\">".$rowHL['lcnt']."</td>\n";
				echo "					<td class=\"".$tbgHL."\" align=\"center\">\n";
				echo "					<form method=\"POST\">\n";
				echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
				echo "						<input type=\"hidden\" name=\"call\" value=\"view\">\n";
				echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowHL['cid']."\">\n";
				echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uidHL."\">\n";
				
				if ($_SESSION['officeid']==$rowHL['officeid'])
				{
					echo "						<input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View Lead\">\n";
				}
				
				echo "					</form>\n";
				echo "					</td>\n";
				echo "				</tr>\n";
				
				if (in_array($_SESSION['securityid'],$dev_ar))
				{
					echo "				<tr>\n";
					echo "					<td align=\"left\" class=\"".$tbgHL."\"><img src=\"images/pixel.gif\"></td>\n";
					echo "					<td colspan=\"9\" align=\"left\" class=\"".$tbgHL."\">\n";
					
					@gen_CustLinkNode($rowHL['cid'],$uidHL,$rowHL['saddr1'],$rowHL['scity'],$rowHL['sstate'],$rowHL['szip1']);
					
					echo "					</td>\n";
					echo "				</tr>\n";
				}
			}
		}
		else
		{
			echo "				<tr>\n";
			echo "					<td align=\"center\"><b>None</b></td>\n";
			echo "				</tr>\n";
		}
		
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "						</p>\n";
		echo "				    </div>\n";*/
		
		if (in_array($_SESSION['securityid'],$dev_ar))
		{
			echo "				    <div id=\"sc\">\n";
			echo "						<p>\n";
			
			search_panel();
			
			echo "						</p>\n";
			echo "				    </div>\n";
		}
		
	}
	
	echo "				</div>\n";
	echo "			</div>\n";
	echo "
	
	<script> 
	(function() {
		var tabView = new YAHOO.widget.TabView('AP_CB_menu');
	})();
	</script>
	
	";
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function listleads_TRACK()
{
	$unxdt		=time();
	
	$qry0 = "SELECT securityid,emailtemplateaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$qry   = "DECLARE @pdate varchar(10) ";
	$qry  .= "SET @pdate = (CAST(DATEPART(m,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(d,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(yy,(getdate() - 30)) AS varchar)) ";
	$qry  .= "SELECT ";
	$qry  .= "		* ";
	$qry  .= "FROM ";
	$qry  .= "	list_cinfo ";
	$qry  .= "WHERE ";
	$qry  .= "	officeid='".$_SESSION['officeid']."' ";
	
	if (isset($_REQUEST['showdupe']) && $_REQUEST['showdupe']==2)
	{
	}
	elseif (isset($_REQUEST['showdupe']) && $_REQUEST['showdupe']==1)
	{
		$qry  .= "	AND dupe=1 ";
	}
	else
	{
		$qry  .= "	AND dupe=0 ";
	}
	
	if (isset($_REQUEST['d1']) && !empty($_REQUEST['d1']) && isset($_REQUEST['d2']) && !empty($_REQUEST['d2']))
	{
		$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' ";
	}
	elseif (isset($_REQUEST['d3']) && !empty($_REQUEST['d3']) && isset($_REQUEST['d4']) && !empty($_REQUEST['d4']))
	{
		$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d3']."' AND '".$_REQUEST['d4']." 23:59:59' ";
	}
	elseif (isset($_REQUEST['d5']) && !empty($_REQUEST['d5']) && isset($_REQUEST['d6']) && !empty($_REQUEST['d6']))
	{
		$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d5']."' AND '".$_REQUEST['d6']." 23:59:59' ";
	}
	elseif (isset($_REQUEST['d7']) && !empty($_REQUEST['d7']) && isset($_REQUEST['d8']) && !empty($_REQUEST['d8']))
	{
		$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d7']."' AND '".$_REQUEST['d8']." 23:59:59' ";
	}
	elseif (isset($_REQUEST['d9']) && !empty($_REQUEST['d9']) && isset($_REQUEST['d10']) && !empty($_REQUEST['d10']))
	{
		$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d9']."' AND '".$_REQUEST['d10']." 23:59:59' ";
	}
	elseif (isset($_REQUEST['d11']) && !empty($_REQUEST['d11']) && isset($_REQUEST['d12']) && !empty($_REQUEST['d12']))
	{
		$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d11']."' AND '".$_REQUEST['d12']." 23:59:59' ";
	}
	else
	{
		if (isset($_REQUEST['showaged']) && $_REQUEST['showaged']==0)
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN @pdate AND getdate() ";
		}
	}
	
	if ($_REQUEST['call']=="search_results" && $_REQUEST['subq']=="sstring")
	{
		$qry  .= "	AND ".$_REQUEST['field']." LIKE '".htmlspecialchars_decode($_REQUEST['ssearch'])."%' ";
		
		if ($_SESSION['llev'] == 4)
		{
			if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
			{
				$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
			}
			else
			{
				$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			}
		}
		elseif ($_SESSION['llev'] < 4)
		{
			$qry  .= "	AND securityid='".$_SESSION['securityid']."' ";
		}
	}
	else
	{
		$qry  .= "	AND ".$_REQUEST['field']."='".$_REQUEST['ssearch']."' ";
		
		if ($_SESSION['llev'] == 4 && isset($_REQUEST['field']) && $_REQUEST['field']!="securityid")
		{
			if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
			{
				$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
			}
			else
			{
				$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			}
		}
		elseif ($_SESSION['llev'] < 4 && isset($_REQUEST['field']) && $_REQUEST['field']!="securityid")
		{
			$qry  .= " AND securityid='".$_SESSION['securityid']."' ";
		}
	}
	
	$qry  .= "ORDER BY ";
	
	if (isset($_REQUEST['order']) && ($_REQUEST['order']=='cstate' || $_REQUEST['order']=='market'))
	{
		$qry  .= "	".$_REQUEST['order']." ".$_REQUEST['dir'].",cpname asc;";
	}
	else
	{
		$qry  .= "	".$_REQUEST['order']." ".$_REQUEST['dir'].";";
	}
	
	if (isset($_SESSION['tqry']) && trim($_SESSION['tqry'])===trim($qry))
	{
		//echo "ZERO<br>";
		$qry	=$_SESSION['tqry'];
		echo "			<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>NOTE:</b> These Search Results are based upon previously entered Search parameters. Click <b>New Search</b> to clear this condition.</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
	}
	
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//echo "BEFORE: ".$_SESSION['tqry']."<br>";

	$_SESSION['tqry']=$qry;

	//echo "AFTER: ".$_SESSION['tqry']."<br>";
	
	//echo $nrows."<br>";
	//exit;
	if ($nrows == 0)
	{
		echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\" class=\"gray\">\n";
		echo "         <b>Your search returned ".$nrows." results.</b>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		echo "<table align=\"center\" width=\"950px\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"left\" class=\"gray\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "					<td align=\"right\" class=\"gray\">".date('m/d/Y g:i A',time())."</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>\n";
		echo "         <table class=\"outer\" width=\"100%\">\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" align=\"left\">\n";
		echo "                  <table id=\"myTrackTable\" class=\"tablesorter\" cellpadding=\"1\" width=\"100%\">\n";
		echo "						<thead>\n";
		echo "                  	<tr>\n";
		echo "							<th align=\"center\"><img src=\"images/pixel.gif\"></th>\n";
        echo "							<th align=\"left\" width=\"200\"><b>Company Name</b></th>\n";
		echo "							<th align=\"left\" width=\"175\"><b>Contact Name</b></th>\n";
		echo "                     		<th align=\"left\" width=\"75\"><b>Phone</b></th>\n";
		echo "                     		<th align=\"left\" width=\"100\"><b>Market</b></th>\n";
		echo "                     		<th align=\"left\" width=\"20\"><b>St</b></th>\n";
		echo "							<th align=\"center\" width=\"75\"><b>Updated</b></th>\n";
		echo "							<th align=\"center\"><b>Type</b></th>\n";
		echo "							<th align=\"center\" width=\"15\" title=\"Service\"><b>S</b></th>\n";
		echo "							<th align=\"center\" width=\"15\" title=\"Renovations\"><b>R</b></th>\n";
		echo "							<th align=\"left\" width=\"100\"><b>Result</b></th>\n";
		echo "            	        	<th colspan=\"2\" align=\"right\">".$nrows." Result(s)</th>\n";
		echo "                  	</tr>\n";
		echo "						</thead>\n";
		echo "						<tbody>\n";

		$etemp_ar=array();
		$nph_ar= array('0000000000','none','N/A');
		$age30=2592000; //30 Days
		$age15=1296000; //15 Days
		$age07=604800; // 7 Days
		$age01=86400; // 7 Days
		$ts_tdate=getdate();
		$lcnt=0;
		$altdtext="";
		while($row=mssql_fetch_array($res))
		{
			if ($row['estid']!=0)
			{
				$qryU   = "update jest..est set ccid=".$row['cid']." where officeid=".$row['officeid']." and estid=".$row['estid'].";";
				$resU   = mssql_query($qryU);
			}
			
			$nrowsA =0;
			$adate ="";
			if (strlen($row['caddr1']) >= 3)
			{
				$altdtext=$row['caddr1'].", ".$row['ccity'].", ".$row['cstate'].", ".$row['czip1'];
			}
			elseif (strlen($row['saddr1']) >= 3)
			{
				$altdtext=$row['saddr1'].", ".$row['scity'].", ".$row['sstate'].", ".$row['szip1'];
			}

			$secl=explode(",",$row['slevel']);

			if ($secl[6]==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}

			$uid  =md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];

			if (!empty($row['added']))
			{
				$ts_odate=strtotime($row['added']);
				$odate = date("m/d/Y", strtotime($row['added']));
			}
			else
			{
				$ts_odate=0;
				$odate = "";
			}

			if (!empty($row['updated'])||$row['updated']!="")
			{
				$ts_udate=strtotime($row['updated']);
				
				if ($row['updated']!=$row['added'])
				{
					$udate = date("m/d/Y", strtotime($row['updated']));
				}
				else
				{
					$udate = "";
				}
			}
			else
			{
				$ts_udate=0;
				$udate = "";
			}

			if ($row['appt_mo']!=0)
			{
				if ($row['appt_pa']==1)
				{
					$pa="AM";
				}
				else
				{
					$pa="PM";
				}
				
				$adate = "<table width=\"100%\"><tr><td align=\"left\">".str_pad($row['appt_mo'],2,"0",STR_PAD_LEFT)."/".str_pad($row['appt_da'],2,"0",STR_PAD_LEFT)."/".$row['appt_yr']."</td><td align=\"right\">".$row['appt_hr'].":".str_pad($row['appt_mn'],2,"0",STR_PAD_LEFT)." ".$pa."</td</tr></table>";
			}

			$udiff_date=$ts_tdate[0]-$ts_udate;
			$odiff_date=$ts_tdate[0]-$ts_odate;

			$hdate = str_pad($row['hold_mo'],2,"0",STR_PAD_LEFT)."/".str_pad($row['hold_da'],2,"0",STR_PAD_LEFT)."/".$row['hold_yr'];
			$ts_hdate=strtotime($hdate);
			$hdiff_date=$ts_hdate-$ts_tdate[0];
			
			$lcnt++;

			if ($row['dupe']==1)
			{
				$tbg='ltred';
			}
			else
			{
				if ($lcnt%2)
				{
					$tbg='white';
				}
				else
				{
					$tbg='ltgray';
				}
			}
			
			$cphone	=substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($row['cwork'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($row['cwork'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($row['cwork'])),6,4);

			echo "                  <tr>\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">".$lcnt.".</td>\n";
            echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"200\">".htmlspecialchars_decode($row['cpname'])."</td>\n";
			echo "						<td class=\"".$tbg."\" align=\"left\" width=\"175\">\n";
			
			echo htmlspecialchars_decode($row['cfname'])." ".htmlspecialchars_decode($row['clname']);
			
			echo "						</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"75\">".$cphone."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"100\">".htmlspecialchars_decode($row['market'])."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\" width=\"20\">".htmlspecialchars_decode($row['cstate'])."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\" width=\"75\">".$udate."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\">".htmlspecialchars_decode($row['cptype'])."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\" width=\"15\">\n";
			
			if (isset($row['trackservice']) && $row['trackservice']==1)
			{
				echo 'Y';
			}
			
			echo "						</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\" width=\"15\">\n";
			
			if (isset($row['trackrepair']) && $row['trackrepair']==1)
			{
				echo 'Y';
			}
			
			echo "						</td>\n";

			if ($row['stage']==6)
			{
				echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"100\"><b>".$row['resname']."</b></td>\n";
			}
			else
			{
				echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"100\">".$row['resname']."</td>\n";
			}
			
			echo "                     	<td class=\"".$tbg."\" align=\"right\">\n";
			echo "							<div class=\"noPrint\">\n";
			echo "                     		<form method=\"POST\">\n";
			echo "                     			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "                     			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "                     			<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
			echo "                     			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			echo "						        <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
			echo "                     		</form>\n";
			echo "							</div>\n";
			echo "                     	</td>\n";
			//echo "                     	<td class=\"".$tbg."\" align=\"center\"></td>\n";
			echo "                  </tr>\n";
			
			if (isset($_REQUEST['cmtcnt']) && $_REQUEST['cmtcnt'] > 0)
			{
				$qryCMT  = "SELECT TOP ".$_REQUEST['cmtcnt']." * ";
				$qryCMT .= ",(SELECT lname FROM security WHERE securityid=ch.secid) AS lsname ";
				$qryCMT .= ",(SELECT fname FROM security WHERE securityid=ch.secid) AS fsname ";
				$qryCMT .= "FROM chistory AS ch WHERE custid='".$row['cid']."' ORDER by mdate DESC;";
				$resCMT  = mssql_query($qryCMT);
				$nrowCMT = mssql_num_rows($resCMT);
			}
			
			if (isset($_REQUEST['incaddr']) && $_REQUEST['incaddr']==1)
			{
				echo "                  <tr>\n";
				echo "                     <td class=\"gray\" align=\"right\" colspan=\"7\"><b>Address:</b></td>\n";
				echo "                     <td class=\"wh_undsidesl\"  align=\"left\"></td>\n";
				echo "                     <td class=\"wh_und\"  align=\"left\" colspan=\"5\">".$altdtext."</td>\n";
				echo "					</tr>\n";
			}
			
			if (isset($_REQUEST['cmtcnt']) && $_REQUEST['cmtcnt'] > 0 && $nrowCMT > 0)
			{
				if ($nrowCMT > 0)
				{
					$snt=1;
					while ($rowCMT = mssql_fetch_array($resCMT))
					{
						echo "                  <tr>\n";
						echo "                     <td class=\"gray\" align=\"right\" valign=\"top\" colspan=\"7\"><img src=\"images/pixel.gif\"></td>\n";
						echo "                     <td class=\"wh_undsidesl\" align=\"left\" valign=\"top\"><table width=\"100%\"><tr><td align=\"left\">".date("m/d/Y",strtotime($rowCMT['mdate']))."</td</tr></table></td>\n";
						echo "                     <td class=\"wh_und\" align=\"left\" valign=\"top\">".$rowCMT['lsname'].", ".$rowCMT['fsname']."</td>\n";
						echo "                     <td class=\"wh_undsidesr\" align=\"left\" valign=\"top\" colspan=\"4\" width=\"200px\">".htmlspecialchars_decode($rowCMT['mtext'])."</td>\n";
						echo "                  </tr>\n";
						$snt++;
					}
				}
			}
		}
		
		echo "						</tbody>\n";
		echo "                  </table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function listleads_VENDOR()
{
	$unxdt		=time();
	
	$qry0 = "SELECT securityid,emailtemplateaccess FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);

	$qry   = "DECLARE @pdate varchar(10) ";
	$qry  .= "SET @pdate = (CAST(DATEPART(m,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(d,(getdate() - 30)) AS varchar) + '/' + CAST(DATEPART(yy,(getdate() - 30)) AS varchar)) ";
	$qry  .= "SELECT ";
	$qry  .= "		* ";
	$qry  .= "FROM ";
	$qry  .= "	list_cinfo ";
	$qry  .= "WHERE ";
	$qry  .= "	officeid='".$_SESSION['officeid']."' ";
	
	if (isset($_REQUEST['cattribs']) && count($_REQUEST['cattribs']) > 0)
	{
		$qry  .= "	AND cid IN (SELECT ccid FROM ContactAttribMembers WHERE caid = ".$_REQUEST['cattribs'][0].") ";
	}
	
	if (isset($_REQUEST['showdupe']) && $_REQUEST['showdupe']==1)
	{
		$qry  .= "	AND dupe=1 ";
	}
	else
	{
		$qry  .= "	AND dupe=0 ";
	}
	
	if (isset($_REQUEST['d1']) && !empty($_REQUEST['d1']) && isset($_REQUEST['d2']) && !empty($_REQUEST['d2']))
	{
		$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN '".$_REQUEST['d1']."' AND '".$_REQUEST['d2']." 23:59:59' ";
	}
	else
	{
		if (isset($_REQUEST['showaged']) && $_REQUEST['showaged']==0)
		{
			$qry  .= "	AND ".$_REQUEST['dtype']." BETWEEN @pdate AND getdate() ";
		}
	}
	
	if ($_REQUEST['call']=="search_results" && $_REQUEST['subq']=="sstring")
	{
		$qry  .= "	AND ".$_REQUEST['field']." LIKE '".htmlspecialchars_decode($_REQUEST['ssearch'])."%' ";
		
		if ($_SESSION['llev'] == 4)
		{
			if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
			{
				$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
			}
			else
			{
				$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			}
		}
		elseif ($_SESSION['llev'] < 4)
		{
			$qry  .= "	AND securityid='".$_SESSION['securityid']."' ";
		}
	}
	else
	{
		$qry  .= "	AND ".$_REQUEST['field']."='".$_REQUEST['ssearch']."' ";
		
		if ($_SESSION['llev'] == 4 && isset($_REQUEST['field']) && $_REQUEST['field']!="securityid")
		{
			if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=0 && $_SESSION['asstto']!=$_SESSION['securityid'])
			{
				$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sidm='".$_SESSION['asstto']."' OR sid='".$_SESSION['asstto']."' OR sid='".$_SESSION['securityid']."' OR sidm='".$_SESSION['securityid']."') ";
			}
			else
			{
				$qry  .= "	AND securityid IN (select sid from list_secid_sidm where sid=".$_SESSION['securityid']." or sidm=".$_SESSION['securityid'].") ";
			}
		}
		elseif ($_SESSION['llev'] < 4 && isset($_REQUEST['field']) && $_REQUEST['field']!="securityid")
		{
			$qry  .= " AND securityid='".$_SESSION['securityid']."' ";
		}
	}
	
	$qry  .= "ORDER BY ";
	
	if (isset($_REQUEST['order']) && $_REQUEST['order']=='cstate')
	{
		$qry  .= "	".$_REQUEST['order']." ".$_REQUEST['dir'].",cpname asc;";
	}
	else
	{
		$qry  .= "	".$_REQUEST['order']." ".$_REQUEST['dir'].";";
	}
	
	//if ($_SESSION['securityid']==26)
	//{
	//	//echo $qry."<br>";
	//	//show_post_vars();
	//}
	
	if (isset($_SESSION['tqry']) && trim($_SESSION['tqry'])===trim($qry))
	{
		//echo "ZERO<br>";
		$qry	=$_SESSION['tqry'];
		echo "			<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"center\" class=\"gray\"><b>NOTE:</b> These Search Results are based upon previously entered Search parameters. Click <b>New Search</b> to clear this condition.</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
	}	
	
	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);

	//echo "BEFORE: ".$_SESSION['tqry']."<br>";

	$_SESSION['tqry']=$qry;

	//echo "AFTER: ".$_SESSION['tqry']."<br>";
	
	//echo $nrows."<br>";
	//exit;
	if ($nrows == 0)
	{
		echo "<table class=\"outer\" align=\"center\" width=\"950px\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"center\" class=\"gray\">\n";
		echo "         <b>Your search returned ".$nrows." results.</b>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
	else
	{
		echo "<table align=\"center\" width=\"950px\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "	<tr>\n";
		echo "		<td align=\"left\">\n";
		echo "			<table class=\"outer\" width=\"100%\">\n";
		echo "				<tr>\n";
		echo "					<td align=\"left\" class=\"gray\"><b>".$_SESSION['offname']."</b></td>\n";
		echo "					<td align=\"right\" class=\"gray\">".date('m/d/Y g:i A',time())."</td>\n";
		echo "				</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>\n";
		echo "         <table class=\"outer\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "            <tr>\n";
		echo "               <td class=\"gray\" align=\"left\">\n";
		echo "                  <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
		echo "                  	<tr class=\"tblhd\">\n";
		echo "							<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "							<td align=\"left\" width=\"200\"><b>Company Name</b></td>\n";
		echo "							<td align=\"left\" width=\"175\"><b>Contact Name</b></td>\n";
		echo "                     		<td align=\"left\" width=\"75\"><b>Phone</b></td>\n";
		echo "                     		<td align=\"left\" width=\"100\"><b>Market</b></td>\n";
		echo "                     		<td align=\"left\" width=\"20\"><b>St</b></td>\n";
		echo "							<td align=\"center\" width=\"75\"><b>Updated</b></td>\n";
		echo "							<td align=\"center\"><b>Type</b></td>\n";
		echo "							<td align=\"center\" width=\"15\" title=\"Service\"><b>S</b></td>\n";
		echo "							<td align=\"center\" width=\"15\" title=\"Renovations\"><b>R</b></td>\n";
		echo "							<td align=\"left\" width=\"100\"><b>Result</b></td>\n";
		echo "            	        	<td colspan=\"2\" align=\"right\">".$nrows." Result(s)</td>\n";
		echo "                  	</tr>\n";

		$etemp_ar=array();
		$nph_ar= array('0000000000','none','N/A');
		$age30=2592000; //30 Days
		$age15=1296000; //15 Days
		$age07=604800; // 7 Days
		$age01=86400; // 7 Days
		$ts_tdate=getdate();
		$lcnt=0;
		$altdtext="";
		while($row=mssql_fetch_array($res))
		{
			if ($row['estid']!=0)
			{
				$qryU   = "update jest..est set ccid=".$row['cid']." where officeid=".$row['officeid']." and estid=".$row['estid'].";";
				$resU   = mssql_query($qryU);
			}
			
			$nrowsA =0;
			$adate ="";
			if (strlen($row['caddr1']) >= 3)
			{
				$altdtext=$row['caddr1'].", ".$row['ccity'].", ".$row['cstate'].", ".$row['czip1'];
			}
			elseif (strlen($row['saddr1']) >= 3)
			{
				$altdtext=$row['saddr1'].", ".$row['scity'].", ".$row['sstate'].", ".$row['szip1'];
			}

			$secl=explode(",",$row['slevel']);

			if ($secl[6]==0)
			{
				$fstyle="red";
			}
			else
			{
				$fstyle="black";
			}

			$uid  =md5(session_id().time().$row['cid']).".".$_SESSION['securityid'];

			if (!empty($row['added']))
			{
				$ts_odate=strtotime($row['added']);
				$odate = date("m/d/Y", strtotime($row['added']));
			}
			else
			{
				$ts_odate=0;
				$odate = "";
			}

			if (!empty($row['updated'])||$row['updated']!="")
			{
				$ts_udate=strtotime($row['updated']);
				
				if ($row['updated']!=$row['added'])
				{
					$udate = date("m/d/Y", strtotime($row['updated']));
				}
				else
				{
					$udate = "";
				}
			}
			else
			{
				$ts_udate=0;
				$udate = "";
			}

			if ($row['appt_mo']!=0)
			{
				if ($row['appt_pa']==1)
				{
					$pa="AM";
				}
				else
				{
					$pa="PM";
				}
				
				$adate = "<table width=\"100%\"><tr><td align=\"left\">".str_pad($row['appt_mo'],2,"0",STR_PAD_LEFT)."/".str_pad($row['appt_da'],2,"0",STR_PAD_LEFT)."/".$row['appt_yr']."</td><td align=\"right\">".$row['appt_hr'].":".str_pad($row['appt_mn'],2,"0",STR_PAD_LEFT)." ".$pa."</td</tr></table>";
			}

			$udiff_date=$ts_tdate[0]-$ts_udate;
			$odiff_date=$ts_tdate[0]-$ts_odate;

			$hdate = str_pad($row['hold_mo'],2,"0",STR_PAD_LEFT)."/".str_pad($row['hold_da'],2,"0",STR_PAD_LEFT)."/".$row['hold_yr'];
			$ts_hdate=strtotime($hdate);
			$hdiff_date=$ts_hdate-$ts_tdate[0];
			
			$lcnt++;

			if ($lcnt%2)
			{
				$tbg='white';
			}
			else
			{
				$tbg='ltgray';
			}
			
			$cphone	=substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($row['cwork'])),0,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($row['cwork'])),3,3)."-".substr(preg_replace('/\.|-|\s/i','$1$2$3',htmlspecialchars_decode($row['cwork'])),6,4);

			echo "                  <tr>\n";
			echo "                     <td class=\"".$tbg."\" align=\"right\">".$lcnt.".</td>\n";
            echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"200\">".htmlspecialchars_decode($row['cpname'])."</td>\n";
			echo "						<td class=\"".$tbg."\" align=\"left\" width=\"175\">\n";
			
			//echo htmlspecialchars_decode($row['cfname'])." ".htmlspecialchars_decode($row['clname']);
			echo htmlspecialchars_decode($row['fullname']);
			
			echo "						</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"75\">".$cphone."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"100\">".htmlspecialchars_decode($row['market'])."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\" width=\"20\">".htmlspecialchars_decode($row['cstate'])."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\" width=\"75\">".$udate."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\">".htmlspecialchars_decode($row['cptype'])."</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\" width=\"15\">\n";
			
			if (isset($row['trackservice']) && $row['trackservice']==1)
			{
				echo 'Y';
			}
			
			echo "						</td>\n";
			echo "                     <td class=\"".$tbg."\" align=\"center\" width=\"15\">\n";
			
			if (isset($row['trackrepair']) && $row['trackrepair']==1)
			{
				echo 'Y';
			}
			
			echo "						</td>\n";

			if ($row['stage']==6)
			{
				echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"100\"><b>".$row['resname']."</b></td>\n";
			}
			else
			{
				echo "                     <td class=\"".$tbg."\" align=\"left\" width=\"100\">".$row['resname']."</td>\n";
			}
			
			echo "                     	<td class=\"".$tbg."\" align=\"right\">\n";
			echo "							<div class=\"noPrint\">\n";
			echo "                     		<form method=\"POST\">\n";
			echo "                     			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "                     			<input type=\"hidden\" name=\"call\" value=\"view\">\n";
			echo "                     			<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
			echo "                     			<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			echo "						        <input class=\"transnb\" type=\"image\" src=\"images/folder_open.gif\" alt=\"View\">\n";
			echo "                     		</form>\n";
			echo "							</div>\n";
			echo "                     	</td>\n";
			//echo "                     	<td class=\"".$tbg."\" align=\"center\"></td>\n";
			echo "                  </tr>\n";
			
			if (isset($_REQUEST['cmtcnt']) && $_REQUEST['cmtcnt'] > 0)
			{
				$qryCMT  = "SELECT TOP ".$_REQUEST['cmtcnt']." * ";
				$qryCMT .= ",(SELECT lname FROM security WHERE securityid=ch.secid) AS lsname ";
				$qryCMT .= ",(SELECT fname FROM security WHERE securityid=ch.secid) AS fsname ";
				$qryCMT .= "FROM chistory AS ch WHERE custid='".$row['cid']."' ORDER by mdate DESC;";
				$resCMT  = mssql_query($qryCMT);
				$nrowCMT = mssql_num_rows($resCMT);
			}
			
			if (isset($_REQUEST['incaddr']) && $_REQUEST['incaddr']==1)
			{
				echo "                  <tr>\n";
				echo "                     <td class=\"gray\" align=\"right\" colspan=\"7\"><b>Address:</b></td>\n";
				echo "                     <td class=\"wh_undsidesl\"  align=\"left\"></td>\n";
				echo "                     <td class=\"wh_und\"  align=\"left\" colspan=\"5\">".$altdtext."</td>\n";
				echo "					</tr>\n";
			}
			
			if (isset($_REQUEST['cmtcnt']) && $_REQUEST['cmtcnt'] > 0 && $nrowCMT > 0)
			{
				if ($nrowCMT > 0)
				{
					$snt=1;
					while ($rowCMT = mssql_fetch_array($resCMT))
					{
						echo "                  <tr>\n";
						echo "                     <td class=\"gray\" align=\"right\" valign=\"top\" colspan=\"7\"><img src=\"images/pixel.gif\"></td>\n";
						echo "                     <td class=\"wh_undsidesl\" align=\"left\" valign=\"top\"><table width=\"100%\"><tr><td align=\"left\">".date("m/d/Y",strtotime($rowCMT['mdate']))."</td</tr></table></td>\n";
						echo "                     <td class=\"wh_und\" align=\"left\" valign=\"top\">".$rowCMT['lsname'].", ".$rowCMT['fsname']."</td>\n";
						echo "                     <td class=\"wh_undsidesr\" align=\"left\" valign=\"top\" colspan=\"4\" width=\"200px\">".htmlspecialchars_decode($rowCMT['mtext'])."</td>\n";
						echo "                  </tr>\n";
						$snt++;
					}
				}
			}
		}
		
		echo "                  </table>\n";
		echo "               </td>\n";
		echo "            </tr>\n";
		echo "         </table>\n";
		echo "      </td>\n";
		echo "   </tr>\n";
		
		if ($_SESSION['securityid']==26 && $_REQUEST['call']=='search_results' && $row0['emailtemplateaccess'] >= 1 && isset($_REQUEST['etid']) && $_REQUEST['etid']!=0 && count($etemp_ar) > 0)
		{
			$qryET1 = "select etid,name from jest..EmailTemplate where etid=".$_REQUEST['etid'].";";
			$resET1 = mssql_query($qryET1);
			$rowET1 = mssql_fetch_array($resET1);
			
			echo "	<tr>\n";
			echo "		<td align=\"left\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td align=\"right\">\n";
			echo "						<form method=\"POST\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"procemaillist\">\n";
			echo "						<input type=\"hidden\" name=\"etid\" value=\"".$_REQUEST['etid']."\">\n";
			echo "						<input type=\"hidden\" name=\"et_uid\" value=\"".$_REQUEST['et_uid']."\">\n";
			echo "						<input type=\"hidden\" name=\"chistory\" value=\"1\">\n";
			echo "						<input type=\"hidden\" name=\"etest\" value=\"1\">\n";
			
			foreach ($etemp_ar as $nET => $vET)
			{
				echo "						<input type=\"hidden\" name=\"etcid[]\" value=\"".$vET."\">\n";
			}
			
			echo "							<table width=\"100%\">\n";
			echo "								<tr>\n";
			echo "									<td align=\"left\" class=\"gray\"><b>Email List Processing</b></td>\n";
			echo "									<td align=\"center\" class=\"gray\"><b>".count($etemp_ar)."</b> recipient(s) will receive the <b>".$rowET1['name']."</b> Email.</td>\n";
			echo "									<td align=\"right\" class=\"gray\">Process Email List? <input class=\"transnb\" type=\"checkbox\" id=\"confirmemaillist\" name=\"confirmemaillist\" value=\"1\" title=\"Confirm\"><input class=\"transnb\" type=\"image\" src=\"images/table_go.png\" alt=\"Process Email List\" onClick=\"return ConfirmChecked('confirmemaillist');\"></td>\n";
			echo "								</tr>\n";
			echo "							</table>\n";
			echo "						</form>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		
		echo "</table>\n";
	}
}

function search_panel_TRACK()
{
	$dev_ar= array(SYS_ADMIN);
	
	unset($_SESSION['tqry']);
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
	unset($_SESSION['d3']);
	unset($_SESSION['d4']);
	unset($_SESSION['d5']);
	unset($_SESSION['d6']);
	unset($_SESSION['d7']);
	unset($_SESSION['d8']);
	unset($_SESSION['et_uid']);
	
	$cr_ar=array();
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 AND ivr!=1 AND (oid=0 OR oid=".$_SESSION['officeid'].") ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 AND (oid=0 OR oid=".$_SESSION['officeid'].") ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	//$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid='".$_SESSION['officeid']."' order by SUBSTRING(slevel,13,13) DESC,lname ASC;";
	$qry1  = "SELECT  ";
	$qry1 .= "	 s.securityid ";
	$qry1 .= "	,s.lname ";
	$qry1 .= "	,s.fname ";
	$qry1 .= "	,s.slevel ";
	$qry1 .= "	,(SELECT count(cid) FROM cinfo WHERE dupe=0 AND jobid='0' AND securityid=S.securityid) as lcnt ";
	$qry1 .= "FROM  ";
	$qry1 .= "	security AS s ";
	$qry1 .= "WHERE  ";
	$qry1 .= "	s.officeid=".$_SESSION['officeid']." ";
	//$qry1 .= "	and s.srep=1 ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=$_SESSION['securityid'] && $_SESSION['asstto']!=0)
		{
			$qry1 .= "	and s.sidm=".$_SESSION['asstto']." OR s.securityid=".$_SESSION['securityid']." ";
		}
		else
		{
			$qry1 .= "	and s.sidm=".$_SESSION['securityid']." OR s.securityid=".$_SESSION['securityid']." ";
		}
	}
	elseif ($_SESSION['llev'] <= 3)
	{
		$qry1 .= "	and s.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry1 .= "ORDER BY ";
	$qry1 .= "	SUBSTRING(s.slevel,13,1) DESC, ";
	$qry1 .= "	s.lname ASC; ";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	$qry2 = "SELECT ldexport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
	
	$qry3 = "SELECT securityid,lname,fname,slevel,gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
	
	//$acclist=explode(",",$_SESSION['aid']);
	
	if (in_array($_SESSION['securityid'],$dev_ar))
	{
		$tbgS='transnb';
	}
	else
	{
		$tbgS='gray';
	}

	$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	$hlpnd=1;

	echo "<script type=\"text/javascript\" src=\"js/jquery_extend.js\"></script>\n";
	echo "<table width=\"950px\" align=\"center\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr class=\"tblhd\">\n";
	echo "					<td align=\"left\"><b>Lead Search</b></td>\n";
	echo "					<td align=\"right\">\n";

	HelpNode('LeadSearchPanel',$hlpnd++);

	echo "					</td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td colspan=\"2\" class=\"gray\">\n";
	echo "						<table border=\"0\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td colspan=\"2\">\n";
	echo "									<table border=\"0\" width=\"100%\">\n";
	echo "										<tr>\n";
	echo "											<td class=\"gray\" align=\"left\" colspan=\"2\"><b>Search Type</b></td>\n";
	echo "											<td class=\"gray\" align=\"left\"><strong>Data Field</strong></td>\n";
	echo "											<td class=\"gray\" align=\"left\"></td>\n";
	echo "											<td class=\"gray\" align=\"left\"><b>Sort by</b></td>\n";
	echo "											<td class=\"gray\" align=\"left\"><b>Direction</b></td>\n";
	echo "											<td class=\"gray\" align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">Aged 30+</td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "											<td class=\"gray\" align=\"center\">Inactive</td>\n";
	}
	
	echo "											<td class=\"gray\" align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">Address</td>\n";
	echo "											<td class=\"gray\" align=\"center\" title=\"Select the number of comments to display\">Comments</td>\n";
	echo "											<td class=\"gray\" align=\"left\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	
	// String Search
	echo "         			<form name=\"tsearch1\" method=\"post\">\n";
	echo "					<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "					<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "					<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "											<td align=\"right\">\n";

	HelpNode('LeadSearchPanelText',$hlpnd++);

	echo "											</td>\n";
	echo "											<td align=\"right\">\n";
	echo "												<select name=\"field\">\n";
	echo "                                    				<option value=\"caddr1\">Address</option>\n";
	echo "                                    				<option value=\"ccell\">Cell</option>\n";
	echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
    echo "                                    				<option value=\"cemail\">Email</option>\n";
	echo "													<option value=\"cfname\">First Name</option>\n";
	echo "													<option value=\"clname\">Last Name</option>\n";
	echo "													<option value=\"market\">Market</option>\n";
	echo "                                    				<option value=\"cwork\">Phone</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\"><input type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Information in this Field\"></td>\n";
	echo "											<td align=\"left\"></td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<select name=\"order\">\n";
	echo "													<option value=\"caddr1\">Address</option>\n";
	echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
	echo "													<option value=\"added\">Date Added</option>\n";
	echo "													<option value=\"clname\">Last Name</option>\n";
	echo "													<option value=\"updated\">Last Update</option>\n";
	echo "													<option value=\"market\">Market</option>\n";
	echo "													<option value=\"cstate\">State</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<select name=\"dir\">\n";
	echo "													<option value=\"asc\" SELECTED>Ascending</option>\n";
	echo "													<option value=\"desc\">Descending</option>\n";
	echo "												</select>\n";
	echo "											</td>";
	echo "											<td align=\"center\"></td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "											<td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
		echo "												<select name=\"showdupe\">\n";
		echo "													<option value=\"0\" SELECTED>No</option>\n";
		echo "													<option value=\"1\">Yes</option>\n";
		echo "													<option value=\"2\">All</option>\n";
		echo "												</select>\n";
		echo "											</td>\n";
	}

	echo "											<td align=\"center\" title=\"Check this box to include the Address and Email in the displayed search results\">\n";
	echo "												<select name=\"incaddr\">\n";
	echo "													<option value=\"0\">No</option>\n";
	echo "													<option value=\"1\">Yes</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"center\">\n";
	echo "												<select name=\"cmtcnt\">\n";
	echo "													<option value=\"0\">0</option>\n";
	echo "													<option value=\"1\">1</option>\n";
	echo "													<option value=\"2\">2</option>\n";
	echo "													<option value=\"3\">3</option>\n";
	echo "													<option value=\"4\">4</option>\n";
	echo "													<option value=\"5\">5</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
	echo "											<td align=\"right\">\n";
	echo "												<select name=\"dtype\">\n";
	echo "													<option value=\"added\">Date Added</option>\n";
	echo "													<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "											<td align=\"left\" colspan=\"8\">\n";
	echo "												<input type=\"text\" name=\"d1\" id=\"d1\" size=\"11\">\n";
	echo "												<input type=\"text\" name=\"d2\" id=\"d2\" size=\"11\">\n";
	echo "												(Date Optional)\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "         			</form>\n";	
	echo "										<tr>\n";
	echo "                              	<td align=\"center\" colspan=\"11\"><hr width=\"100%\"></td>\n";
	echo "										</tr>\n";
	
	// Lead Source
	echo "										<tr>\n";
	echo "											<td align=\"right\">\n";

	HelpNode('LeadSearchPanelSource',$hlpnd++);

	echo "											</td>\n";
	echo "         								<form name=\"tsearch2\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"srcstatus\">\n";
	echo "											<input type=\"hidden\" name=\"field\" value=\"source\">\n";
	echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid."\">\n";
	echo "                              	<td align=\"right\"><b>Source Code</b>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"ssearch\">\n";

	while ($row = mssql_fetch_array($res))
	{
		if ($row['statusid']==0)
		{
			echo "                                    	<option value=\"".$row['statusid']."\">bluehaven.com</option>\n";
		}
		elseif ($row['statusid']==1)
		{
			echo "                                    	<option value=\"".$row['statusid']."\">Manual</option>\n";
		}
		else
		{
			if ($row['oid']==0 || $row['oid']==$_SESSION['officeid'])
			{
				echo "                                    	<option value=\"".$row['statusid']."\">".$row['name']."</option>\n";
			}
		}
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\"></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "													<option value=\"caddr1\">Address</option>\n";
	echo "													<option value=\"cpname\">Company Name</option>\n";
	echo "													<option value=\"added\">Date Added</option>\n";
	echo "													<option value=\"clname\">Last Name</option>\n";
	echo "													<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "													<option value=\"clname\">Market</option>\n";
	echo "													<option value=\"cstate\">State</option>\n";
	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\">\n";
	echo "                                    <select name=\"dir\">\n";
	echo "                                    	<option value=\"asc\">Ascending</option>\n";
	echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "                                    </select>\n";
	echo "									</td>";
	echo "                                 <td align=\"center\" title=\"Select Yes to include Leads that have not been updated within the last 30 days\">\n";
	echo "										<select name=\"showaged\">\n";
	echo "											<option value=\"0\">No</option>\n";
	echo "											<option value=\"1\">Yes</option>\n";
	echo "										</select>\n";
	echo "								   </td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
		echo "										<select name=\"showdupe\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	}

	echo "                                 <td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">\n";
	echo "										<select name=\"incaddr\">\n";
	echo "											<option value=\"0\">No</option>\n";
	echo "											<option value=\"1\">Yes</option>\n";
	echo "										</select>\n";
	echo "								   </td>\n";
	echo "                                 <td align=\"center\">\n";
	echo "                                    <select name=\"cmtcnt\">\n";
	echo "                                    	<option value=\"0\">0</option>\n";
	echo "                                    	<option value=\"1\">1</option>\n";
	echo "                                    	<option value=\"2\">2</option>\n";
	echo "                                    	<option value=\"3\">3</option>\n";
	echo "                                    	<option value=\"4\">4</option>\n";
	echo "                                    	<option value=\"5\">5</option>\n";
	echo "                                    </select>\n";
	echo "								   </td>\n";
	echo "                                	<td align=\"left\">\n";
	echo "										<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "									</td>\n";
	echo "								<tr>\n";
	echo "											<td align=\"right\">\n";

	echo "											</td>\n";
	echo "                              	<td align=\"right\">\n";
	echo "                                   	<select name=\"dtype\">\n";
	echo "                                    		<option value=\"added\">Date Added</option>\n";
	echo "                                    		<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "                                    	</select>\n";
	echo "									</td>\n";
	echo "                              	<td align=\"left\" colspan=\"8\">\n";
	echo "										<input type=\"text\" name=\"d3\" id=\"d3\" size=\"11\">\n";
	echo "										<input type=\"text\" name=\"d4\" id=\"d4\" size=\"11\">\n";
	echo "										(Date Optional)\n";
	echo "									</td>\n";
	echo "								</tr>\n";
	echo "         						</form>\n";
	echo "										<tr>\n";
	echo "                              			<td align=\"center\" colspan=\"11\"><hr width=\"100%\"></td>\n";
	echo "										</tr>\n";
	
	// Lead Result
	echo "										<tr>\n";
	echo "											<td align=\"right\">\n";

	HelpNode('LeadSearchPanelResult',$hlpnd++);

	echo "											</td>\n";
	echo "         								<form name=\"tsearch3\" method=\"post\">\n";
	echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "											<input type=\"hidden\" name=\"subq\" value=\"resstatus\">\n";
	echo "											<input type=\"hidden\" name=\"field\" value=\"stage\">\n";
	echo "											<input type=\"hidden\" name=\"et_uid\" value=\"".$et_uid."\">\n";
	echo "                              			<td align=\"right\"><b>Result Code</b>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"ssearch\">\n";

	while ($row0 = mssql_fetch_array($res0))
	{
		echo "                                    	<option value=\"".$row0['statusid']."\">".$row0['name']."</option>\n";
	}

	echo "                                    </select>\n";
	echo "											</td>\n";
	echo "                                 <td align=\"left\"></td>\n";
	echo "                              	<td align=\"left\">\n";
	echo "                                    <select name=\"order\">\n";
	echo "													<option value=\"caddr1\">Address</option>\n";
	echo "													<option value=\"cpname\">Company Name</option>\n";
	echo "													<option value=\"added\">Date Added</option>\n";
	echo "													<option value=\"clname\">Last Name</option>\n";
	echo "													<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "													<option value=\"clname\">Market</option>\n";
	echo "													<option value=\"cstate\">State</option>\n";
	echo "                                    </select>\n";
	echo "									</td>\n";
	echo "                                 <td align=\"left\">\n";
	echo "                                    <select name=\"dir\">\n";
	echo "                                    	<option value=\"asc\">Ascending</option>\n";
	echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
	echo "                                    </select>\n";
	echo "								   </td>";
	echo "                                 <td align=\"center\" title=\"Check this box to include Leads that have not been updated within the last 30 days\">\n";
	echo "										<select name=\"showaged\">\n";
	echo "											<option value=\"0\">No</option>\n";
	echo "											<option value=\"1\">Yes</option>\n";
	echo "										</select>\n";
	echo "								   </td>\n";

	if ($_SESSION['llev'] >= 5)
	{
		echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
		echo "										<select name=\"showdupe\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	}

	echo "                                 <td align=\"center\" title=\"Select Yes to include the Address and Email in the displayed search results\">\n";
	echo "										<select name=\"incaddr\">\n";
	echo "											<option value=\"0\">No</option>\n";
	echo "											<option value=\"1\">Yes</option>\n";
	echo "										</select>\n";
	echo "								   </td>\n";
	echo "                                 <td align=\"center\">\n";
	echo "                                    <select name=\"cmtcnt\">\n";
	echo "                                    	<option value=\"0\">0</option>\n";
	echo "                                    	<option value=\"1\">1</option>\n";
	echo "                                    	<option value=\"2\">2</option>\n";
	echo "                                    	<option value=\"3\">3</option>\n";
	echo "                                    	<option value=\"4\">4</option>\n";
	echo "                                    	<option value=\"5\">5</option>\n";
	echo "                                    </select>\n";
	echo "								   </td>\n";
	echo "                                 <td align=\"left\">\n";
	echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "									</td>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\">\n";
	
	echo "											</td>\n";
	echo "                              	<td align=\"right\">\n";
	echo "                                    <select name=\"dtype\">\n";
	echo "                                    	<option value=\"added\">Date Added</option>\n";
	echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
	echo "                                    </select>\n";
	echo "									</td>\n";
	echo "                              	<td align=\"left\" colspan=\"8\">\n";
	echo "											<input type=\"text\" name=\"d5\" id=\"d5\" size=\"11\">\n";
	echo "											<input type=\"text\" name=\"d6\" id=\"d6\" size=\"11\">\n";
	echo "											(Date Optional)\n";
	echo "									</td>\n";
	echo "										</tr>\n";
	echo "         								</form>\n";
	
	if ($nrow1 > 0)
	{
		// SalesRep
		echo "										<tr>\n";
		echo "                              			<td align=\"center\" colspan=\"11\"><hr width=\"100%\"></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"right\">\n";

		HelpNode('LeadSearchPanelSalesRep',$hlpnd++);

		echo "											</td>\n";
		echo "         								<form name=\"tsearch4\" method=\"post\">\n";
		echo "											<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		echo "											<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
		echo "											<input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
		echo "											<input type=\"hidden\" name=\"field\" value=\"securityid\">\n";
		echo "                              	<td align=\"right\" title=\"JMS recognized Sales Reps. The number in parenthesis represents the total number of leads allocated to that Sales Rep. This number does not include Leads that have gone to contract. If this list is empty or a name is missing please contact BHNM IT Support \"><b>Sales Rep</b></td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"ssearch\">\n";
	
		while ($row1 = mssql_fetch_array($res1))
		{
			$secl=explode(",",$row1['slevel']);
			if ($secl[6]==0)
			{
				echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontred\">".$row1['lname'].", ".$row1['fname']." ".$dis."</option>\n";
			}
			else
			{
				echo "                                    	<option value=\"".$row1['securityid']."\" class=\"fontblack\">".$row1['lname'].", ".$row1['fname']."".$dis."</option>\n";					
			}
		}
	
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"right\"></td>\n";
		echo "                              	<td align=\"left\">\n";
		echo "                                    <select name=\"order\">\n";
		echo "													<option value=\"caddr1\">Address</option>\n";
		echo "													<option value=\"cpname\">Company Name</option>\n";
		echo "													<option value=\"added\">Date Added</option>\n";
		echo "													<option value=\"clname\">Last Name</option>\n";
		echo "													<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "													<option value=\"clname\">Market</option>\n";
		echo "													<option value=\"cstate\">State</option>\n";
		echo "                                    </select>\n";
		echo "									</td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "                                    <select name=\"dir\">\n";
		echo "                                    	<option value=\"asc\">Ascending</option>\n";
		echo "                                    	<option value=\"desc\" SELECTED>Descending</option>\n";
		echo "                                    </select>\n";
		echo "									</td>";
		echo "                                 <td align=\"center\" title=\"Check this box to include Leads that have not been updated within the last 30 days\">\n";
		echo "										<select name=\"showaged\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
	
		if ($_SESSION['llev'] >= 5)
		{
			echo "                                 <td align=\"center\" title=\"Select Yes to include Inactive Leads\">\n";
			echo "										<select name=\"showdupe\">\n";
			echo "											<option value=\"0\">No</option>\n";
			echo "											<option value=\"1\">Yes</option>\n";
			echo "										</select>\n";
			echo "								   </td>\n";
		}
	
		echo "                                 <td align=\"center\" title=\"Check this box to include the Address and Email in the displayed search results\">\n";
		echo "										<select name=\"incaddr\">\n";
		echo "											<option value=\"0\">No</option>\n";
		echo "											<option value=\"1\">Yes</option>\n";
		echo "										</select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"center\">\n";
		echo "                                    <select name=\"cmtcnt\">\n";
		echo "                                    	<option value=\"0\">0</option>\n";
		echo "                                    	<option value=\"1\">1</option>\n";
		echo "                                    	<option value=\"2\">2</option>\n";
		echo "                                    	<option value=\"3\">3</option>\n";
		echo "                                    	<option value=\"4\">4</option>\n";
		echo "                                    	<option value=\"5\">5</option>\n";
		echo "                                    </select>\n";
		echo "								   </td>\n";
		echo "                                 <td align=\"left\">\n";
		echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "					<tr>\n";
		echo "											<td align=\"right\">\n";

		echo "											</td>\n";
		echo "                              	<td align=\"right\">\n";
		echo "                                    <select name=\"dtype\">\n";
		echo "                                    	<option value=\"added\">Date Added</option>\n";
		echo "                                    	<option value=\"updated\" SELECTED>Last Update</option>\n";
		echo "                                    </select>\n";
		echo "											</td>\n";
		echo "                              	<td align=\"left\" colspan=\"8\">\n";
		echo "										<input type=\"text\" name=\"d7\" id=\"d7\" size=\"11\">\n";
		echo "										<input type=\"text\" name=\"d8\" id=\"d8\" size=\"11\">\n";
		echo "										(Date Optional)\n";
		echo "									</td>\n";
		echo "										</tr>\n";
		echo "         								</form>\n";
	}
	
	if ($row3['gmreports']==1 && $row2['ldexport']==1 && $_SESSION['securityid']==9999999999999999999999999999)
	{
		$qryLS = "SELECT * FROM leadstatuscodes WHERE active=2 AND ivr!=1 ORDER BY name ASC;";
		$resLS = mssql_query($qryLS);
	
		$qryRC = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
		$resRC = mssql_query($qryRC);
		
		echo "										<tr>\n";
		echo "                              			<td align=\"center\" colspan=\"11\"><hr width=\"100%\"></td>\n";
		echo "										</tr>\n";
		echo "         								<form name=\"tsearch5\" action=\"export/ldexport.php\" method=\"post\" target=\"_new\">\n";
		//echo "         								<form name=\"tsearch5\" action=\"subs\test.txt\" method=\"post\" target=\"_new\">\n";
		echo "											<input type=\"hidden\" name=\"oid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "											<input type=\"hidden\" name=\"sid\" value=\"".$_SESSION['securityid']."\">\n";
		echo "										<tr>\n";
		echo "                              			<td class=\"gray\" align=\"left\"><div id=\"exsearch\"><img src=\"images/help.png\"></td>\n";
		echo "                              			<td align=\"right\"><b>Export by:</b>\n";
		
		if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332)
		{
		
			echo "                                    			<select name=\"dtype\">\n";
			echo "                                    				<option value=\"added\" SELECTED>Date Added</option>\n";
			echo "                                    				<option value=\"updated\">Last Update</option>\n";
			echo "                                    			</select>\n";
		
		}
		else
		{
			echo "											<input type=\"hidden\" name=\"dtype\" value=\"added\">\n";
		}
		
		echo "											</td>\n";
		echo "                              			<td align=\"left\">\n";
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
		echo "												<a href=\"javascript:cal9.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set Begin Date\"></a>\n";
		echo "												<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
		echo "												<a href=\"javascript:cal10.popup();\"><img src=\"img/cal.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Click Here to Set End Date\"></a>\n";
		echo "											</td>\n";
		
		if ($_SESSION['securityid']==26 || $_SESSION['securityid']==332)
		{
			//echo "                                 			<td align=\"left\">\n";
			//echo "												<input class=\"checkbox\" type=\"checkbox\" name=\"incsource\" value=\"1\">\n";
			//echo "											</td>\n";
			echo "                                 			<td align=\"left\">\n";
			echo "												<input class=\"checkbox\" type=\"checkbox\" name=\"incsource\" value=\"1\" title=\"Check this box to Export to the selected Source code\"> Source Only<br>\n";
			echo "                                    			<select name=\"lssearch\">\n";
			
			while ($rowLS = mssql_fetch_array($resLS))
			{
				if ($rowLS['statusid']==0)
				{
					echo "                                    		<option value=\"".$rowLS['statusid']."\">bluehaven.com</option>\n";
				}
				elseif ($rowLS['statusid']!=1)
				{
					echo "                                    		<option value=\"".$rowLS['statusid']."\">".$rowLS['name']."</option>\n";
				}
			}
		
			echo "                                    			</select>\n";
			echo "											</td>\n";
			echo "                                 			<td align=\"left\">\n";
			echo "												<input class=\"checkbox\" type=\"checkbox\" name=\"incsreps\" value=\"1\">\n";
			echo "											</td>\n";
		}
		else
		{
			echo "                                 			<td align=\"left\"></td>\n";
			echo "                                 			<td align=\"left\"></td>\n";
		}
		
		echo "                                 			<td align=\"right\" colspan=\"4\">\n";
		echo "&nbspBy checking this box you certify that the exported information <br>will be used for the sole interest of Blue Haven Pools & Spas";
		/*
		echo "															<select name=\"expfields[]\" MULTIPLE size=\"1\" title=\"Hold down CTRL and left mouse click to select multiple fields\">\n";
		echo "																<option></option>\n";
		echo "																<option value=\"cfname\">First Name</option>\n";
		echo "																<option value=\"clname\">Last Name</option>\n";
		echo "																<option value=\"caddr1\">Cust Address</option>\n";
		echo "																<option value=\"saddr1\">Pool Address</option>\n";
		echo "																<option value=\"cstate\">Cust State</option>\n";
		echo "																<option value=\"sstate\">Pool State</option>\n";
		echo "															</select>\n";
		*/
		echo "														</td>\n";
		echo "                             			    	<td align=\"center\"><input class=\"checkbox\" type=\"checkbox\" name=\"certify\" value=\"1\" title=\"By checking this box you certify that the exported information will be used for the sole interest of Blue Haven Pools & Spas\"></td>\n";
		echo "                                 			<td align=\"left\">\n";
		echo "												<input class=\"transnb\" type=\"image\" src=\"images/page_excel.png\" alt=\"Export\">\n";
		echo "											</td>\n";
		//echo "                            			   	<td align=\"left\"><input class=\"buttondkgrypnl80\" type=\"submit\" name=\"export\" value=\"Export\" title=\"This button will create a comma de-limited text file with Customer Information originated in the JMS within Date Range indicated.\"></td>\n";
		echo "										</tr>\n";
		echo "       								</form>\n";
	
		echo "         						<script language=\"JavaScript\">\n";
		echo "         						var cal9 = new calendar2(document.forms['tsearch5'].elements['d1']);\n";
		echo "         						cal9.year_scroll = false;\n";
		echo "         						cal9.time_comp = false;\n";
		echo "         						var cal10 = new calendar2(document.forms['tsearch5'].elements['d2']);\n";
		echo "         						cal10.year_scroll = false;\n";
		echo "         						cal10.time_comp = false;\n";
		echo "         						//-->\n";
		echo "         						</script>\n";
	}
	
	/*
	echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>Unique Zip Codes:</b>\n";
	echo "                                 <td align=\"right\"></td>\n";
	echo "                                 <td align=\"left\">Aged 30+ <input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\">Holds <input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\">Duplicates <input class=\"checkbox\" type=\"checkbox\" name=\"showdupes\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "                              	<td align=\"right\"><b>Unique Last Names:</b>\n";
	echo "                                 <td align=\"right\"></td>\n";
	echo "                                 <td align=\"left\">Aged 30+ <input class=\"checkbox\" type=\"checkbox\" name=\"showaged\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\">Holds <input class=\"checkbox\" type=\"checkbox\" name=\"showhold\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\">Duplicates <input class=\"checkbox\" type=\"checkbox\" name=\"showdupes\" value=\"1\"></td>\n";
	echo "                                 <td align=\"left\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Search\"></td>\n";
	echo "										</tr>\n";
	*/
	echo "									</table>\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}


function search_panel_VENDOR()
{
	$dev_ar= array(SYS_ADMIN);
	
	unset($_SESSION['tqry']);
	unset($_SESSION['d1']);
	unset($_SESSION['d2']);
	unset($_SESSION['et_uid']);
	
	$cr_ar=array();
	
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 AND ivr!=1 AND (oid=0 OR oid=".$_SESSION['officeid'].") ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 AND (oid=0 OR oid=".$_SESSION['officeid'].") ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1  = "SELECT  ";
	$qry1 .= "	 s.securityid ";
	$qry1 .= "	,s.lname ";
	$qry1 .= "	,s.fname ";
	$qry1 .= "	,s.slevel ";
	$qry1 .= "	,(SELECT count(cid) FROM cinfo WHERE dupe=0 AND jobid='0' AND securityid=S.securityid) as lcnt ";
	$qry1 .= "FROM  ";
	$qry1 .= "	security AS s ";
	$qry1 .= "WHERE  ";
	$qry1 .= "	s.officeid=".$_SESSION['officeid']." ";
	
	if ($_SESSION['llev'] == 4)
	{
		if (isset($_SESSION['asstto']) && $_SESSION['asstto']!=$_SESSION['securityid'] && $_SESSION['asstto']!=0)
		{
			$qry1 .= "	and s.sidm=".$_SESSION['asstto']." OR s.securityid=".$_SESSION['securityid']." ";
		}
		else
		{
			$qry1 .= "	and s.sidm=".$_SESSION['securityid']." OR s.securityid=".$_SESSION['securityid']." ";
		}
	}
	elseif ($_SESSION['llev'] <= 3)
	{
		$qry1 .= "	and s.securityid=".$_SESSION['securityid']." ";
	}
	
	$qry1 .= "ORDER BY ";
	$qry1 .= "	SUBSTRING(s.slevel,13,1) DESC, ";
	$qry1 .= "	s.lname ASC; ";
	$res1 = mssql_query($qry1);
	$nrow1= mssql_num_rows($res1);
	
	$qry2 = "SELECT ldexport FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
	
	$qry3 = "SELECT securityid,lname,fname,slevel,gmreports FROM security WHERE securityid='".$_SESSION['securityid']."';";
	$res3 = mssql_query($qry3);
	$row3 = mssql_fetch_array($res3);
	
	$qry4 = "select * from ContactAttributes order by longname asc;";
	$res4 = mssql_query($qry4);
	
	while ($row4 = mssql_fetch_array($res4))
	{
		$caar[$row4['aid']]=$row4['longname'];
	}

	$et_uid  =md5(session_id().".".time().".".$_SESSION['officeid'].".".$_SESSION['securityid']);
	
	$hlpnd=1;

	echo "<script type=\"text/javascript\" src=\"js/jquery_extend.js\"></script>\n";
	echo "<table align=\"center\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" border=\"0\" width=\"100%\">\n";
	echo "				<tr class=\"tblhd\" colspan=\"2\">\n";
	echo "					<td align=\"left\"><b>Vendor Search</b></td>\n";
	echo "					<td align=\"right\">\n";

	HelpNode('VendorSearchPanel',$hlpnd++);

	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<form name=\"tsearch1\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"leads\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"search_results\">\n";
	echo "			<input type=\"hidden\" name=\"subq\" value=\"sstring\">\n";
	echo "						<table class=\"outer\" width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td class=\"gray\" valign=\"top\">\n";
	echo "									<table width=\"100%\">\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\"><b>Search Type</b></td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<select name=\"field\">\n";
	echo "                                    				<option value=\"caddr1\">Address</option>\n";
	echo "                                    				<option value=\"ccell\">Cell</option>\n";
	echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
    echo "                                    				<option value=\"cemail\">Email</option>\n";
	echo "													<option value=\"cfname\">First Name</option>\n";
	echo "													<option value=\"clname\">Last Name</option>\n";
	echo "													<option value=\"market\">Market</option>\n";
	echo "                                    				<option value=\"cwork\">Phone</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\"><b>Data Field</b></td>\n";
	echo "											<td align=\"left\"><input type=\"text\" name=\"ssearch\" size=\"25\" maxlength=\"40\" title=\"Enter Full or Partial Information in this Field\"></td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\"><b>Sort by</b></td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<select name=\"order\">\n";
	echo "													<option value=\"caddr1\">Address</option>\n";
	echo "													<option value=\"cpname\" SELECTED>Company Name</option>\n";
	echo "													<option value=\"added\">Date Added</option>\n";
	echo "													<option value=\"clname\">Last Name</option>\n";
	echo "													<option value=\"updated\">Last Update</option>\n";
	echo "													<option value=\"clname\">Market</option>\n";
	echo "													<option value=\"cstate\">State</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\"><b>Direction</b></td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<select name=\"dir\">\n";
	echo "													<option value=\"asc\" SELECTED>Ascending</option>\n";
	echo "													<option value=\"desc\">Descending</option>\n";
	echo "												</select>\n";
	echo "											</td>";
	echo "										</tr>\n";
	echo "										<tr>\n";
	echo "											<td align=\"right\"><b>Inactive</b></td>\n";
	echo "											<td align=\"left\" title=\"Select Yes to include Inactive Leads\">\n";
	echo "												<select name=\"showdupe\">\n";
	echo "													<option value=\"0\">No</option>\n";
	echo "													<option value=\"1\">Yes</option>\n";
	echo "												</select>\n";
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td class=\"gray\" valign=\"top\">\n";
	echo "									<table width=\"100%\">\n";
	echo "										<tr>\n";
	echo "											<td align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "											<td align=\"left\">\n";
	echo "												<table width=\"100%\">\n";
	
	foreach ($caar as $n => $v)
	{
		echo "												<tr>\n";
		echo "													<td align=\"center\">\n";
		echo "														<input type=\"checkbox\" class=\"transnb\" name=\"cattribs[]\" value=\"".$n."\">\n";
		echo "													</td>\n";
		echo "													<td align=\"left\">".$v."</td>\n";
		echo "												</tr>\n";
	}
	
	echo "											</td>\n";
	echo "										</tr>\n";
	echo "									</table>\n";
	echo "								</td>\n";
	echo "								<td align=\"left\" valign=\"top\">\n";
	echo "									<input class=\"transnb\" type=\"image\" src=\"images/search.gif\" alt=\"Search\">\n";
	echo "								</td>\n";
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function search_panel_NEW()
{
	unset($_SESSION['tqry']);
	unset($_SESSION['et_uid']);
	
	$hlpnd=1;

	//HelpNode('LeadSearchPanel',$hlpnd++);

	//echo "		<table width=\"410px\"><tr><td>";
	echo "<div class=\"searchtabs\" id=\"searchtabs\">\n";
	echo "	<ul>\n";
	echo "		<li><a href=\"#Searches\">Searches</a></li>\n";
	echo "		<li><a href=\"#Results\">Results</a></li>\n";
	echo "		<li><a href=\"#Quick\">Quick</a></li>\n";
	echo "	</ul>\n";
	echo "	<div id=\"Searches\">\n";
	echo "		<div class=\"searchaccordion\" id=\"searchaccordion\">\n";
	echo "			<h3><a href=\"#\">String Search</a></h3>\n";
	echo "			<div id=\"a-tab-panel\">\n";
	echo "				<p>\n";
	
	search_leads_string();
	
	echo "				</p>\n";
	echo "			</div>\n";
	
	echo "			<h3><a href=\"#\">Source Code Search</a></h3>\n";
	echo "			<div id=\"a-tab-panel\">\n";
	echo "				<p>\n";
	
	search_leads_source();
	
	echo "				</p>\n";
	echo "			</div>\n";
	
	echo "			<h3><a href=\"#\">Result Code Search</a></h3>\n";
	echo "			<div id=\"a-tab-panel\"\n";
	echo "				<p>\n";
	
	search_leads_result();
	
	echo "				</p>\n";
	echo "			</div>\n";
	
	echo "			<h3><a href=\"#\">Sales Rep Search</a></h3>\n";
	echo "			<div id=\"a-tab-panel\">\n";
	echo "				<p>\n";
	
	search_leads_srep();
	
	echo "				</p>\n";
	echo "			</div>\n";
	
	//echo "			<h3><a href=\"#\">Network Search</a></h3>\n";
	//echo "			<div>\n";
	//echo "				<p>\n";
	//
	//search_net_string();
	//
	//echo "				</p>\n";
	//echo "			</div>\n";
	echo "		</div>\n";
	echo "	</div>\n";
	echo "	<div id=\"Results\">\n";
	echo "		<div id=\"displayResult\">\n";
	echo "		</div>\n";
	echo "	</div>\n";
	echo "	<div id=\"Quick\">\n";
	
	display_CB_AP_NEW();
	
	echo "	</div>\n";
	echo "</div>\n";
	//echo "<td></tr></table>\n";
}

?>