<?php
ini_set('display_errors','On');
error_reporting(E_ALL);

function sleveldisplay($csl)
{
	if (!is_array($csl))
	{
		$csl=array(0,0,0,0,0,0,0);
	}
	
	$nulltxt='';
	
	$temptxt="
	Level 1 - Sales Rep<br>
	Level 5 - Sales Manager<br>
	Level 6 - General Manager<br>
	";
	
	$esttxt="
	<b>Estimates</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Create/Read/Update/Delete Estimates on Leads directly assigned<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Create/Read/Update/Delete Estimates on Leads directly Assigned and Leads for Sales Reps directly assigned<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- Create/Read/Update/Delete Estimates on Leads for entire Office<br>
	- View Cost
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Same as General Manager
	</p>
	";
	
	$contxt="
	<b>Contracts</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Read Contracts for directly assigned Leads/Customers<br>
	- Cannot Create Contracts<br>
	- Create/Read Addendums<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Create/Read/Update/Delete Contracts for directly assigned Leads/Customers and Leads/Customers for Sales Reps directly assigned<br>
	- Create/Read/Update/Delete Addendums for directly assigned Leads/Customers and Leads/Customers for Sales Reps directly assigned<br>
	- Cannot Create GM Adjusts<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- Create/Read/Update/Delete Contracts for entire Office<br>
	- Create/Read/Update/Delete Addendums for entire Office<br>
	- Create/Read/Update/Delete GM Adjust for entire Office<br>
	- View Cost
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Same as General Manager
	</p>
	";
	
	$jobtxt="
	<b>Jobs</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Read Jobs for directly assigned Leads/Customers<br>
	- Cannot Create Jobs<br>
	- Create/Read Addendums<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Create/Read/Update/Delete Jobs for directly assigned Leads/Customers and Leads/Customers for Sales Reps directly assigned<br>
	- Create/Read/Update/Delete Addendums for directly assigned Leads/Customers and Leads/Customers for Sales Reps directly assigned<br>
	- Cannot Create GM Adjusts<br>
	- Cannot View Cost
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- Create/Read/Update/Delete Jobs for entire Office<br>
	- Create/Read/Update/Delete Addendums for entire Office<br>
	- Create/Read/Update/Delete GM Adjust for entire Office<br>
	- MAS Ready/Not Ready Jobs<br>
	- View Cost</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Same as General Manager
	</p>
	";
	
	$ldstxt="
	<b>Leads</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Create/Read/Update directly assigned Leads
	</p><br>
	<p>
	Level 4 - Sales Manager<br>
	- Create/Read/Update directly assigned Leads and Leads for directly assigned Sales Reps<br>
	- Move Leads between directly assigned Sales Reps or Staff
	</p><br>
	<p>
	Level 5 - General Manager<br>
	- Create/Read/Update Leads for entire Office<br>
	- Move Leads to any Staff within Office<br>
	- Return BHNM Provided Leads to Management or other Offices (see System Security)
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Create/Read/Update Leads for entire System<br>
	- Move Leads to any Office/Staff within System<br>
	</p>
	";
	
	$rpttxt="
	<b>Reports</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Dig Standings<br>
	- Sales & Commission (Self Only)
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Dig Standings<br>
	- Lead Source (Default Office Only)<br>
	- Zip Report<br>
	- Sales & Commission (Self & assigned Sales Reps)
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- All Level 5 Reports<br>
	- CSR Report (requires Funcational Access setup)<br>
	- Dig Reports<br>
	- Job Progress<br>
	- Operating Reports (enable GM/Operating Reports under Functional Access)<br>
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- All Reports (some may require Functional Access setting)
	</p>
	";
	
	$msgtxt="
	<b>Messages</b><br>
	<p>
	Level 1 - Sales Rep<br>
	- Send and Receive Message from others in their Office<br>
	- Receive from BHNM
	</p><br>
	<p>
	Level 5 - Sales Manager<br>
	- Send to and Receive from others in their Office<br>
	- Send to and Receive from BHNM
	</p><br>
	<p>
	Level 6 - General Manager<br>
	- Send to and Receive from others in their Office<br>
	- Send to and Receive from BHNM
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Send to and Receive from all Users and Resource Accounts
	</p>
	";
	
	$systxt="
	<b>System</b><br>
	<p>
	Level 1 - Standard Access<br>
	- Allows login to Default Office
	</p><br>
	<p>
	Level 7 - Alternate Office Access<br>
	- Access to Offices other than the Default Office (Alternate Office Access List Setup Required)<br>
	</p><br>
	<p>
	Level 9 - Support/Admin<br>
	- Unrestricted Access to all Offices
	</p>
	";
	
	
	$modules=array(
					array('Estimates',$esttxt),
					array('Contracts',$contxt),
					array('Jobs',$jobtxt),
					array('Leads',$ldstxt),
					array('Reports',$rpttxt),
					array('Messages',$msgtxt),
					array('System',$systxt)
					);
	
	echo "<table>";
	
	for ($x=0;$x<=6;$x++)
	{
		echo "<tr>";
		echo "	<td align=\"right\">";
		echo $csl[$x];
		echo "	</td>";
		echo "	<td align=\"left\">";
		echo '<b>'.$modules[$x][0].'</b> ';		
		echo "	</td>";
		echo "	<td align=\"left\">";
		
		if (!empty($modules[$x][1]))
		{
			echo "<span class=\"JMStooltip\" title=\"".$modules[$x][1]."\"><img src=\"images/info.gif\"></span>";
		}
		
		echo "	</td>";
		echo "</tr>";
	}
	
	echo "</table>";
}

function slevelform($csl)
{
	if (!is_array($csl))
	{
		//$csl=array(0,0,0,0,0,0,0);
		$csl=array(1,1,1,1,1,1,1);
	}
	
	$modules=array('Estimates','Contracts','Jobs','Leads','Reports','Messages','System');
	$smodules=array('usr_Estimates','usr_Contracts','usr_Jobs','usr_Leads','usr_Reports','usr_Messages','usr_System');
	
	echo "<table>\n";
	
	//for ($x=0;$x<=count($modules);$x++)
	for ($x=0;$x<=6;$x++)
	{
		echo "<tr>\n";
		echo "	<td align=\"right\">\n";
		echo "		<select name=\"".$smodules[$x]."\" id=\"".$smodules[$x]."\">\n";
		
		for ($y=0;$y<=9;$y++)
		{
			if ($y==$csl[$x])
			{
				echo "				<option value=\"".$y."\" SELECTED>".$y."</option>\n";
			}
			else
			{
				echo "				<option value=\"".$y."\">".$y."</option>\n";
			}
			//echo "				<option value=\"".$y."\">".$y."</option>\n";
		}

		echo "		</select>\n";
		echo "	</td>\n";
		echo "	<td align=\"left\">\n";
		echo $modules[$x];
		echo "	</td>\n";
		echo "</tr>\n";
	}
	
	echo "</table>\n";
}

function slevelformflat($csl,$oid)
{
	if (!is_array($csl))
	{
		$csl=array(0,0,0,0,0,0,0);
	}
	
	$modules=array('E','C','J','L','R','M','S');
	
	echo "<table>\n";
	echo "	<tr>\n";
	
	for ($z=0;$z<=6;$z++)
	{
		echo "	<td align=\"center\">\n";
		echo $modules[$z];
		echo "	</td>\n";
	}
	
	echo "	</tr>";
	echo "	<tr>\n";
	
	for ($x=0;$x<=6;$x++)
	{
		echo "	<td align=\"center\">\n";
		echo "	<div>\n";
		echo "		<input class=\"security_module_oid\" type=\"hidden\" value=\"".$oid."\">\n";
		echo "		<input class=\"security_module_type\" type=\"hidden\" value=\"".$modules[$x]."\">\n";
		echo "		<select class=\"security_module_select\" name=\"altlevel".$oid."[]\">\n";
		
		for ($y=0;$y<=9;$y++)
		{
			if ($y==$csl[$x])
			{
				echo "				<option value=\"".$y."\" SELECTED>".$y."</option>\n";
			}
			else
			{
				echo "				<option value=\"".$y."\">".$y."</option>\n";
			}
			//echo "				<option value=\"".$y."\">".$y."</option>\n";
		}

		echo "		</select>\n";
		echo "	</div>\n";
		echo "	</td>\n";
	}
	
	echo "	</tr>";
	echo "</table>";
}

function slevelformflat_blank()
{
	$csl=array(0,0,0,0,0,0,0);
	
	$modules=array('E','C','J','L','R','M','S');
	
	echo "<table>\n";
	echo "	<tr>\n";
	
	for ($z=0;$z<=6;$z++)
	{
		echo "	<td align=\"center\">\n";
		echo $modules[$z];
		echo "	</td>\n";
	}
	
	echo "	</tr>";
	echo "	<tr>\n";
	
	for ($x=0;$x<=6;$x++)
	{
		echo "	<td align=\"center\">\n";
		echo "		<select class=\"security_module_".$modules[$x]."\" name=\"altlevel\">\n";
		
		for ($y=0;$y<=9;$y++)
		{
			echo "				<option value=\"".$y."\">".$y."</option>\n";
		}

		echo "		</select>\n";
		echo "	</td>\n";
	}
	
	echo "	</tr>";
	echo "</table>";
}

function addslevelformflat()
{
	$csl=array(0,0,0,0,0,0,0);
	
	$modules=array('E','C','J','L','R','M','S');
	
	echo "<table>\n";
	echo "	<tr>\n";
	
	for ($z=0;$z<=6;$z++)
	{
		echo "	<td align=\"center\">\n";
		echo $modules[$z];
		echo "	</td>\n";
	}
	
	echo "	</tr>";
	echo "	<tr>\n";
	
	for ($x=0;$x<=6;$x++)
	{
		echo "	<td valign=\"bottom\" align=\"center\">\n";
		echo "		<select id=\"security_module_select_".$modules[$x]."\" name=\"module_level_".$modules[$x]."\">\n";
		
		for ($y=0;$y<=9;$y++)
		{
			echo "				<option value=\"".$y."\">".$y."</option>\n";
		}

		echo "		</select>\n";
		echo "	</td>\n";
	}
	
	echo "	</tr>";
	echo "</table>";
}

function maintsecelements($array)
{
	if (is_array($array))
	{
		$elemcnt=9;

		echo "<table width=\"100%\">\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_plev\" id=\"usr_updt_m_plev\">\n";

		$e=0;
		while ($e <= $elemcnt)
		{

			if ($e==$array[0])
			{
				echo "         <option value=\"$e\" SELECTED>$e</option>\n";
			}
			else
			{
				echo "         <option value=\"$e\">$e</option>\n";
			}
			$e++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">Pricebook</td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_llev\" id=\"usr_updt_m_llev\">\n";

		$l=0;
		while ($l <= $elemcnt)
		{

			if ($l==$array[1])
			{
				echo "         <option value=\"$l\" SELECTED>$l</option>\n";
			}
			else
			{
				echo "         <option value=\"$l\">$l</option>\n";
			}
			$l++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">Leads</td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_ulev\" id=\"usr_updt_m_ulev\">\n";

		$r=0;
		while ($r <= $elemcnt)
		{

			if ($r==$array[2])
			{
				echo "         <option value=\"$r\" SELECTED>$r</option>\n";
			}
			else
			{
				echo "         <option value=\"$r\">$r</option>\n";
			}
			$r++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">User/Office</td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_mlev\" id=\"usr_updt_m_mlev\">\n";

		$m=0;
		while ($m <= $elemcnt)
		{

			if ($m==$array[3])
			{
				echo "         <option value=\"$m\" SELECTED>$m</option>\n";
			}
			else
			{
				echo "         <option value=\"$m\">$m</option>\n";
			}
			$m++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">Messages</td>\n";
		echo "   </tr>\n";
		echo "   <tr>\n";
		echo "      <td valign=\"top\" align=\"right\">\n";
		echo "         <select name=\"updt_m_tlev\" id=\"usr_updt_m_tlev\">\n";

		$t=0;
		while ($t <= $elemcnt)
		{

			if ($t==$array[4])
			{
				echo "         <option value=\"$t\" SELECTED>$t</option>\n";
			}
			else
			{
				echo "         <option value=\"$t\">$t</option>\n";
			}
			$t++;
		}

		echo "         </select>\n";
		echo "      </td>\n";
		echo "      <td valign=\"top\" align=\"left\">Reserved</td>\n";
		echo "   </tr>\n";
		echo "</table>\n";
	}
}

function query_EmployeeQBSConfig($oid,$sid,$ListID,$EditSequence,$lname,$db)
{
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	
    $q="mssql://".$db['username'].":".$db['password']."@".$db['hostname']."/".$db['dbname'];
    
    require_once '../QB/QuickBooks.php';
    
    $queue = new QuickBooks_Queue($q);
	$queue->enqueue('EmployeeQuery', (string) $sid, 10, $oid);
	
	return 'Request Queued';
}

function query_SalesRepQBSConfig($oid,$sid,$ListID,$EditSequence,$lname,$db)
{
	ini_set('display_errors','On');
	error_reporting(E_ALL);
	
    $q="mssql://".$db['username'].":".$db['password']."@".$db['hostname']."/".$db['dbname'];
    
    require_once '../QB/QuickBooks.php';
    
    $queue = new QuickBooks_Queue($q);
	$queue->enqueue('SalesRepQuery', (string) $sid, 10, $oid);
	
	return 'Request Queued';
}

function remove_EmployeeQueryResults($sid,$qid,$qact,$db)
{
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$qry    = "DELETE FROM jest_ext..qb_query_response where qid=".$qid.";";
	$res	= mssql_query($qry);
	
	//query_EmployeeQueryResults($sid,0,$qact,$db);
}


function get_QueryResults_List($sid,$db)
{
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
	$nodeout="<table width=\"100%\">";
	$nodeout=$nodeout."<thead><tr><td>ID</td><td>QUERY RESULT DATE</td><td>Type</td><td></td></tr></thead>\n";
	
	$qry1	= "SELECT * FROM jest_ext..qb_query_response where qbxml_response like '%Employee%' and pid=".$sid.";";
	$res1	= mssql_query($qry1);
	$nrow1	= mssql_num_rows($res1);
	
	$qry2	= "SELECT * FROM jest_ext..qb_query_response where qbxml_response like '%SalesRep%' and pid=".$sid.";";
	$res2	= mssql_query($qry2);
	$nrow2	= mssql_num_rows($res2);
	
	if ($nrow1 > 0)
	{
		while ($row1 = mssql_fetch_array($res1))
		{
			$nodeout=$nodeout.
			"<tr><td>".$row1['qid']."</td><td>".date('m/d/Y G:mA',strtotime($row1['qdate']))."</td><td>Employee</td><td align=\"right\">". QBEPresultsButtonMatrix($row1['qid']) ."</td></tr>\n";
		}
	}
	
	if ($nrow2 > 0)
	{
		while ($row2 = mssql_fetch_array($res2))
		{
			$nodeout=$nodeout.
			"<tr><td>".$row2['qid']."</td><td>".date('m/d/Y G:mA',strtotime($row2['qdate']))."</td><td>SalesRep</td><td align=\"right\">". QBSRresultsButtonMatrix($row2['qid']) ."</td></tr>\n";
		}
	}
	
	$nodeout=$nodeout."</table>\n";
	
	return $nodeout;
}

function get_QBSQueryResult($sid,$qid,$dtag,$db)
{
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$nodeout='';
	
	if (isset($qid) and $qid!=0)
	{
		$qry    = "SELECT * FROM jest_ext..qb_query_response where qid=".$qid.";";
		$res	= mssql_query($qry);
		$row    = mssql_fetch_array($res);
		$nrow   = mssql_num_rows($res);
			
		$nodeout=$nodeout."<table width=\"100%\">";
		
		if ($nrow==0)
		{
			$nodeout=$nodeout."<tr><td>No Previous Query</td></tr>\n";
		}
		else
		{
			$nodeout=$nodeout."<tr><td align=\"right\"></td><td align=\"right\">". QBEPresultsButtonMatrix($row['qid']) ."</td></tr>\n";
			
			$xmlDoc = new DOMDocument();
			$xmlDoc->loadXML($row['qbxml_response']);
			
			$x = $xmlDoc->getElementsByTagName($dtag);
			foreach ($x as $x)
			{
				if ($x->childNodes->length)
				{
					foreach ($x->childNodes as $i)
					{
						if ($i->nodeName!='#text')
						{
							$nodeout=$nodeout."<tr><td align=\"right\"><b>".$i->nodeName . "</b></td><td align=\"left\">" . $i->nodeValue . "</td></tr>\n";
						}
					}
				}
			}
		}
		
		$nodeout=$nodeout."</table>\n";
	}
	else
	{
		$nodeout=$nodeout."<table width=\"100%\">";
		$nodeout=$nodeout."<tr><td>No Previous Query</td></tr>\n";
		$nodeout=$nodeout."</table>\n";
	}
	
	return $nodeout;
}

function get_EmployeeQueryResults($sid,$qid,$qact,$db)
{
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
	$nodeout='';
	
	if (isset($qid) and $qid!=0)
	{
		$qry    = "SELECT * FROM jest_ext..qb_query_response where qid=".$qid.";";
		$res	= mssql_query($qry);
		$row    = mssql_fetch_array($res);
		$nrow   = mssql_num_rows($res);
			
		$nodeout=$nodeout."<table width=\"100%\">";
		
		if ($nrow==0)
		{
			$nodeout=$nodeout."<tr><td>No Previous Query</td></tr>\n";
		}
		else
		{
			$nodeout=$nodeout."<tr><td align=\"right\"></td><td align=\"right\">". QBEPresultsButtonMatrix($row['qid']) ."</td></tr>\n";
			
			$xmlDoc = new DOMDocument();
			$xmlDoc->loadXML($row['qbxml_response']);
			
			$x = $xmlDoc->getElementsByTagName('EmployeeRet');
			foreach ($x as $x)
			{
				if ($x->childNodes->length)
				{
					foreach ($x->childNodes as $i)
					{
						if ($i->nodeName!='#text')
						{
							$nodeout=$nodeout."<tr><td align=\"right\"><b>".$i->nodeName . "</b></td><td align=\"left\">" . $i->nodeValue . "</td></tr>\n";
						}
					}
				}
			}
		}
		
		$nodeout=$nodeout."</table>\n";
	}
	else
	{
		$nodeout=$nodeout."<table width=\"100%\">";
		$nodeout=$nodeout."<tr><td>No Previous Query</td></tr>\n";
		$nodeout=$nodeout."</table>\n";
	}
	
	return $nodeout;
}

function get_SalesReps_JSON($oid,$db)
{
	$out=array('SalesReps'=>'');
	
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$qry    = "
		SELECT 
			securityid as sid, lname, fname
		FROM 
			security 
		where 
			officeid=". (int) $oid."
			and srep=1
			and substring(slevel,13,1) > 0
		order by
			lname asc;";
	$res	= mssql_query($qry);
	$row    = mssql_fetch_array($res);
	$nrow   = mssql_num_rows($res);
	
	if ($nrow > 0)
	{
		$out['SalesReps'][]=$row;
	}
	
	return $out;
}

function get_SalesRepQueryResults($sid,$qid,$qact,$db)
{
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
    
	$nodeout='';
	
	if (isset($qid) and $qid!=0)
	{
		$qry    = "SELECT * FROM jest_ext..qb_query_response where qid=".$qid.";";
		$res	= mssql_query($qry);
		$row    = mssql_fetch_array($res);
		$nrow   = mssql_num_rows($res);
			
		$nodeout=$nodeout."<table width=\"100%\">";
		
		if ($nrow==0)
		{
			$nodeout=$nodeout."<tr><td>No Previous Query</td></tr>\n";
		}
		else
		{
			$nodeout=$nodeout."<tr><td align=\"right\"></td><td align=\"right\">". QBSRresultsButtonMatrix($row['qid']) ."</td></tr>\n";
			
			$xmlDoc = new DOMDocument();
			$xmlDoc->loadXML($row['qbxml_response']);
			
			$x = $xmlDoc->getElementsByTagName('SalesRepRet');
			foreach ($x as $x)
			{
				if ($x->childNodes->length)
				{
					foreach ($x->childNodes as $i)
					{
						if ($i->nodeName!='#text')
						{
							$nodeout=$nodeout."<tr><td align=\"right\"><b>".$i->nodeName . "</b></td><td align=\"left\">" . $i->nodeValue . "</td></tr>\n";
						}
					}
				}
			}
		}
		
		$nodeout=$nodeout."</table>\n";
	}
	else
	{
		$nodeout=$nodeout."<table width=\"100%\">";
		$nodeout=$nodeout."<tr><td>No Previous Query</td></tr>\n";
		$nodeout=$nodeout."</table>\n";
	}
	
	return $nodeout;
}

function QBEPresultsButtonMatrix($qid)
{
	return "<button class=\"remove_EmployeeQBQR\" value=\"".$qid."\">Delete <img src=\"images/delete.png\"></button><button class=\"process_EmployeeQBQR\" value=\"".$qid."\">Process <img src=\"images/application_get.png\"></button><button class=\"view_EmployeeQBQR\" value=\"".$qid."\">View <img src=\"images/folder.png\"></button>";
}

function QBSRresultsButtonMatrix($qid)
{
	return "<button class=\"remove_EmployeeQBQR\" value=\"".$qid."\">Delete <img src=\"images/delete.png\"></button><button class=\"process_SalesRepQBQR\" value=\"".$qid."\">Process <img src=\"images/application_get.png\"></button><button class=\"view_SalesRepQBQR\" value=\"".$qid."\">View <img src=\"images/folder.png\"></button>";
}

function send_EmployeeAdd($o,$s)
{
	$qryA = "select securityid,srep,ListID,EditSequence,SR_ListID,SR_EditSequence, from jest..security as S where S.login like '".$t."%' order by sactive desc,S.login asc;";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	if ($nrowA == 1)
	{
		$rowA = mssql_fetch_array($resA);
		
		if ($row['ListID']=='0')
		{
			
		}
		
		if ($row['SR_ListID']=='0')
		{
			
		}
	}
}

function get_systemLogIds($t)
{
	$out='';
	
	if (isset($t) && $t != '')
	{
		$qryA = "select S.login,substring(S.slevel,13,1) as sactive,(select name from jest..offices where officeid=S.officeid) as oname from jest..security as S where S.login like '".$t."%' order by sactive desc,S.login asc;";
		$resA = mssql_query($qryA);
		$nrowA= mssql_num_rows($resA);
		
		if ($nrowA > 0)
		{
			while ($rowA = mssql_fetch_array($resA))
			{
				if ($rowA['sactive']==0)
				{
					$out=$out.'<font color="red">'.$rowA['login'].'</font> - '.$rowA['oname'].' - (<font color="red">Inactive</font>)<br>';
				}
				else
				{
					$out.$out=$rowA['login'].' - '.$rowA['oname'].' (Active)<br>';
				}
			}
			
			//$out.$out='<span id="procHalt"></span>';
		}
		else
		{
			$out=$out.'Login ID: <b>'.$t."</b> Not Found";
		}
	}
	
	return $out;
}

function get_JMSUserInfo($sid)
{
	$altid_ar=array();

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt, ";
	$qry .= "ListID, ";
	$qry .= "EditSequence, ";
	$qry .= "SR_ListID, ";
	$qry .= "SR_EditSequence ";
	$qry .= " FROM security WHERE securityid='".$sid."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);
	
	$qry0 = "SELECT officeid,code,name,gm,sm,accountingsystem FROM offices WHERE officeid=".$row[1].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid='".$row[1]."';";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}
	
	echo "<form id=\"frm_UpdateJMSUserInfo\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"usr_sid\" id=\"usr_sid\" value=\"".$sid."\">\n";
	echo "								<table width=\"350px\">\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\" colspan=\"2\"><a id=\"submit_updateJMSUserInfo\" href=\"#\">Save <img src=\"images/save.gif\"></a></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Login ID</b></td>\n";
	echo "										<td align=\"left\"><font color=\"blue\"><b>".$row[6]."</b></font></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Security ID</b></td>\n";
	echo "										<td align=\"left\"><font color=\"blue\"><b>".$row[0]."</b></font></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Auth Token</b></td>\n";
	echo "										<td align=\"left\"><font color=\"blue\"><b>".md5($row[0])."</b></font></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>First Name</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"usr_fname\" id=\"usr_fname\" value=\"".trim($row[2])."\" size=\"30\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Last Name</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"usr_lname\" id=\"usr_lname\" value=\"".trim($row[3])."\" size=\"30\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Title/Role</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"usr_stitle\" id=\"usr_stitle\" value=\"".trim($row[45])."\" size=\"30\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Hire Date</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<input class=\"bboxb\" type=\"text\" name=\"usr_hdate\" id=\"usr_hdate\" value=\"".trim($hdate)."\" size=\"15\">\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\" valign=\"top\"><b>Manager</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<select name=\"usr_sidm\" id=\"usr_sidm\">\n";

	while ($row1 = mssql_fetch_row($res1))
	{
		$secl=explode(",",$row1[4]);
		if ($secl[3] >= 4 || $secl[4] >= 4)
		{
			if ($row1[0]==$row[11])
			{
				echo "											<option value=\"".$row1[0]."\" SELECTED>".$row1[2].", ".$row1[1]."</option>\n";
			}
			else
			{
				echo "											<option value=\"".$row1[0]."\">".$row1[2].", ".$row1[1]."</option>\n";
			}
		}
	}

	echo "											</select>\n";
	echo "										</td>\n";
	echo "									</tr>\n";

	$seclA=explode(",",$row[5]);
	if ($seclA[0] >= 4||$seclA[1] >= 4||$seclA[2] >= 4||$seclA[3] >= 4)
	{
		echo "									<tr>\n";
		echo "										<td align=\"right\" valign=\"top\"><b>Assitant</b></td>\n";
		echo "										<td align=\"left\">\n";
		echo "											<select name=\"usr_assistant\" id=\"usr_assistant\">\n";
		echo "												<option value=\"0\">None</option>\n";

		while ($row4 = mssql_fetch_row($res4))
		{
			$seclAsub=explode(",",$row4[3]);
			if ($seclAsub[0] <= $seclA[0]||$seclAsub[1] <= $seclA[1]||$seclAsub[2] <= $seclA[2]||$seclAsub[3] <= $seclA[3])
			{
				if ($row4[0]==$row[13])
				{
					echo "												<option value=\"".$row4[0]."\" SELECTED>".$row4[2].", ".$row4[1]."</option>\n";
				}
				else
				{
					echo "												<option value=\"".$row4[0]."\">".$row4[2].", ".$row4[1]."</option>\n";
				}
			}
		}

		echo "											</select>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	else
	{
		echo "								<input type=\"hidden\" name=\"usr_assistant\" id=\"usr_assistant\" value=\"0\">\n";
	}
	
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Phone</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"usr_phone\" id=\"usr_phone\" value=\"".trim($row[36])."\" size=\"15\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Extension</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"usr_extn\" id=\"usr_extn\" value=\"".trim($row[37])."\" size=\"15\"></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Email</b></td>\n";
	echo "										<td align=\"left\"><input class=\"bboxb\" type=\"text\" name=\"usr_email\" id=\"usr_email\" value=\"".trim($row[31])."\" size=\"40\"></td>\n";
	echo "									</tr>\n";	
	echo "								</table>\n";
	echo "</form>";
}

function update_JMSUserInfo($sid)
{
	//echo 'Updating...';
	
	$qry1  = "UPDATE security SET ";
	$qry1 .= "fname='".$_REQUEST['usr_fname']."',lname='".$_REQUEST['usr_lname']."',sidm='".$_REQUEST['usr_sidm']."',";
	$qry1 .= "assistant='".$_REQUEST['usr_assistant']."',phone='".$_REQUEST['usr_phone']."',ext='".$_REQUEST['usr_extn']."',email='".$_REQUEST['usr_email']."',";
	$qry1 .= "hdate='".$_REQUEST['usr_hdate']."',stitle='".$_REQUEST['usr_stitle']."',adminid='".$_SESSION['securityid']."',admindate=getdate() ";
	$qry1 .= "WHERE securityid=".$sid.";";
	$res1  = mssql_query($qry1);
	
	get_JMSUserInfo($sid);
}

function insert_JMSUserProfile($sid,$asid)
{
	if ((isset($sid) and $sid!=0) and (isset($asid) and $asid!=0))
	{
		$qry1  = "INSERT INTO secondaryids (securityid,secid,addby) VALUES (".$sid.",".$asid.",".$_SESSION['securityid'].");";
		$res1  = mssql_query($qry1);
		//echo $qry1;
	}
	
	get_JMSProfilesInfo($sid);
}

function insert_Alt_Security($oid,$sid,$levels,$jms_db)
{
	//var_dump($levels);
	$slevels=$levels['E'].','.$levels['C'].','.$levels['J'].','.$levels['L'].','.$levels['R'].','.$levels['M'].','.$levels['S'];
	$qry1  = "INSERT INTO alt_security_levels (sid,oid,slevel) VALUES (".$sid.",".$oid.",'".$slevels."');";
	$res1  = mssql_query($qry1);
	//echo $qry1;
	
	//get_JMSAltOfficeAccessInfo($sid);
}

function delete_Alt_Security($oid,$sid,$jms_db)
{
	$qry1  = "DELETE FROM alt_security_levels WHERE oid=".$oid." AND sid=".$sid.";";
	$res1  = mssql_query($qry1);
	//echo $qry1;
}

function update_Alt_Security($oid,$sid,$mod,$lvl,$jms_db)
{
	$qry1  = "SELECT id,slevel FROM alt_security_levels WHERE oid=".$oid." AND sid=".$sid.";";
	$res1  = mssql_query($qry1);
	$row1  = mssql_fetch_array($res1);
	$nrow1 = mssql_num_rows($res1);
	
	if ($nrow1 == 1)
	{
		$modpick=array(0=>'E',1=>'C',2=>'J',3=>'L',4=>'R',5=>'M',6=>'S');
		$key=array_search($mod,$modpick);
		
		//echo $qry1;
		//echo $key;
		
		$clevels	=explode(',',$row1['slevel']);
		$clevels[$key]=$lvl;
		$nlevels=implode(',',$clevels);
		
		//echo $nlevels;
		$qry2  = "UPDATE alt_security_levels SET slevel='".$nlevels."' WHERE oid=".$oid." AND sid=".$sid.";";
		$res2  = mssql_query($qry2);
		//echo $qry2;
	}
}

function delete_JMSUserProfile($sid,$iid)
{
	if ((isset($sid) and $sid!=0) and (isset($iid) and $iid!=0))
	{
		$qry1  = "DELETE FROM secondaryids WHERE id=".$iid.";";
	
		echo $qry1.'<br>';
		echo $sid.'<br>';
	}
	get_JMSProfilesInfo($sid);	
}

function get_JMSSecurityInfo($sid)
{
	$altid_ar=array();

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);
	
	$qry0 = "SELECT officeid,code,name,gm,sm FROM offices WHERE officeid=".$row[1].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid=".$row[1].";";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid='".$row[0]."'";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!='".$row[0]."' and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid='".$row[0]."'";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row[0]."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	//echo $qry9."<br>";

	$sarray=explode(",",$row[5]);
	$marray=explode(",",$row[15]);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}
	
	echo "<form id=\"frm_UpdateJMSSecurityInfo\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"usr_sid\" id=\"usr_sid\" value=\"".$sid."\">\n";
	echo "								<table width=\"350px\">\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\" colspan=\"2\"><a href=\"#\" id=\"submit_updateJMSSecurityInfo\">Save <img src=\"images/save.gif\"></a></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td colspan=\"2\">\n";
	echo "											<table width=\"100%\">\n";
	echo "												<tr>\n";
	echo "													<td align=\"left\"><b>Current</b> <a href=\"#\"><span class=\"JMStooltip\" title=\"Current Security Levels\"><img src=\"images/info.gif\"></span></a></td>";
	echo "													<td align=\"left\"><b>Set</b> <a href=\"#\"><span class=\"JMStooltip\" title=\"These settings will not be applied until the next time the User logs into the JMS\"><img src=\"images/info.gif\"></span></a></td>";
	echo "													<td align=\"left\">\n";
	
	if (explode(",",$row[5]) >= 2)
	{
		echo "<b>Maintenance</b>";
	}
	
	echo "													</td>";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td valign=\"top\">";

	sleveldisplay(explode(",",$row[5]));

	echo "													</td>\n";
	echo "													<td valign=\"top\">";

	slevelform(explode(",",$row[5]));

	echo "													</td>\n";
	echo "													<td valign=\"top\">";

	if (explode(",",$row[5]) >= 2)
	{
		maintsecelements($marray);
	}
	else
	{
		echo "											<input type=\"hidden\" name=\"updt_m_plev\" value=\"0\"></td>\n";
		echo "											<input type=\"hidden\" name=\"updt_m_llev\" value=\"0\"></td>\n";
		echo "											<input type=\"hidden\" name=\"updt_m_ulev\" value=\"0\"></td>\n";
		echo "											<input type=\"hidden\" name=\"updt_m_mlev\" value=\"0\"></td>\n";
		echo "											<input type=\"hidden\" name=\"updt_m_tlev\" value=\"0\"></td>\n";
	}

	echo "													</td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
	echo "</form>";
}

function update_JMSSecurityInfo($sid)
{
	/*
	echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';
	*/
	
	if (isset($_REQUEST['usr_System']) and $_REQUEST['usr_System']==0)
	{
		$slevel='0,0,0,0,0,0,0';
	}
	else
	{
		$slevel=$_REQUEST['usr_Estimates'].",".$_REQUEST['usr_Contracts'].",".$_REQUEST['usr_Jobs'].",".$_REQUEST['usr_Leads'].",".$_REQUEST['usr_Reports'].",".$_REQUEST['usr_Messages'].",".$_REQUEST['usr_System'];
	}

	$mlevel=$_REQUEST['usr_updt_m_plev'].",".$_REQUEST['usr_updt_m_llev'].",".$_REQUEST['usr_updt_m_ulev'].",".$_REQUEST['usr_updt_m_mlev'].",".$_REQUEST['usr_updt_m_tlev'];
	
	$qry1  = "UPDATE security SET ";
	$qry1 .= "slevel='".$slevel."',mlevel='".$mlevel."',adminid='".$_SESSION['securityid']."',admindate=getdate() ";
	$qry1 .= "WHERE securityid=".$sid.";";
	$res1  = mssql_query($qry1);
	
	get_JMSSecurityInfo($sid);
}


function get_JMSProfilesInfo($sid)
{
	$altid_ar=array();

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt, ";
	$qry .= "ListID, ";
	$qry .= "EditSequence, ";
	$qry .= "SR_ListID, ";
	$qry .= "SR_EditSequence ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);
	
	$qry0 = "SELECT officeid,code,name,gm,sm,accountingsystem FROM offices WHERE officeid=".$row[1].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid='".$row[1]."';";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid='".$row[0]."'";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!='".$row[0]."' and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid='".$row[0]."'";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	echo "								<table width=\"350px\">\n";
	
	if ($nrow9 > 0)
	{
		echo "									<tr>\n";
		echo "										<td align=\"right\"><b>Tied as Alt to:</b></td>\n";
		echo "										<td align=\"left\">\n";
		echo "      									<table>\n";
		
		$qry9a = "SELECT s.securityid,s.officeid,s.fname,s.lname,(SELECT name from offices where officeid=s.officeid) as oname FROM security as s WHERE securityid='".$row9['securityid']."'";
		$res9a = mssql_query($qry9a);
		$row9a = mssql_fetch_array($res9a);
		
		echo "      										<tr>\n";
		echo "													<td align=\"left\">".$row9a['lname'].", ".$row9a['fname']."</td>\n";
		echo "													<td align=\"center\">(".$row9a['oname'].")</td>\n";
		echo "													<td align=\"right\">(".$row9a['securityid'].")</td>";
		echo "      										</tr>\n";
		echo "      									</table>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	else
	{
		echo "									<tr>\n";
		echo "										<td align=\"right\" colspan=\"2\">\n";
		echo "											<input type=\"hidden\" name=\"usr_sid\" id=\"usr_sid\" value=\"".$sid."\">\n";
		echo "											<a href=\"#\" id=\"submit_insertJMSUserProfile\">Save <img src=\"images/save.gif\"></a>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "										<td align=\"right\"><b>Select Alternate</b></td>\n";
		echo "										<td align=\"left\">\n";	
		echo "											<select name=\"altid\" id=\"usr_asid\" >\n";
		echo "											<option value=\"0\" SELECTED>None</option>\n";
	
		while ($row7 = mssql_fetch_row($res7))
		{
			if (!in_array($row7[0],$altid_ar))
			{
				if ($row7[4]>=1)
				{
					echo "											<option class=\"fontblack\" value=\"".$row7[0]."\">".$row7[1].", ".$row7[2]." (".$row7[3]	.")(".$row7[0].")</option>\n";
				}
				else
				{
					echo "											<option class=\"fontred\" value=\"".$row7[0]."\">".$row7[1].", ".$row7[2]." (".$row7[3]	.")(".$row7[0].")</option>\n";
				}
			}
		}
		
		echo "											</select>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		
		if (count($altid_ar) > 0)
		{
			echo "									<tr>\n";
			echo "										<td align=\"right\" valign=\"top\" title=\"Existing Alternate IDs\"><b>Alternate Accounts Tied</b></td>\n";
			echo "										<td align=\"left\"><br>\n";
			echo "      									<table>\n";
			
			foreach ($altid_ar as $n => $v)
			{
				$qry8a = "SELECT s.securityid,s.officeid,s.fname,s.lname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname FROM security as s WHERE securityid='".$v."'";
				$res8a = mssql_query($qry8a);
				$row8a = mssql_fetch_array($res8a);
				
				echo "      									<tr>\n";
				echo "												<td align=\"left\">".$row8a['lname'].", ".$row8a['fname']."</td>\n";
				echo "												<td align=\"center\">(".$row8a['oname'].")</td>\n";
				echo "												<td align=\"right\">(".$row8a['securityid'].")</td>";
				echo "												<td align=\"left\">\n";
				echo "													<div>\n";
				echo "														<input type=\"hidden\" id=\"sys_iid\" value=\"".$row8a['securityid']."\">\n";
				echo "														<img class=\"submit_deleteJMSUserProfile\" src=\"images/delete.png\" title=\"Click to delete this entry.\">\n";
				echo "													</div>\n";
				echo "												</td>";
				echo "      									</tr>\n";
			}
			
			echo "      									</table>\n";
		}
		
		echo "											</td>\n";
		echo "										</tr>\n";
	}
	
	echo "								</table>\n";
}

function get_JMSSalesRepInfo($sid)
{
	$altid_ar=array();

	$qry  = "SELECT ";
	$qry .= "securityid, ";
	$qry .= "hdate, ";
	$qry .= "srep, ";
	$qry .= "newcommdate, ";
	$qry .= "slevel, ";
	$qry .= "mlevel, ";
	$qry .= "SR_ListID, ";
	$qry .= "SR_EditSequence ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	$sarray=explode(",",$row['slevel']);
	$marray=explode(",",$row['mlevel']);

	if (isset($row['hdate']) && strlen($row['hdate']) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row['hdate']));
	}
	else
	{
		$hdate="";
	}
	
	echo "<form id=\"frm_UpdateJMSSalesRepInfo\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"usr_sid\" id=\"usr_sid\" value=\"".$sid."\">\n";
	echo "								<table width=\"500px\">\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><a href=\"#\" id=\"submit_updateJMSSalesRepInfo\">Save <img src=\"images/save.gif\"></a></td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\" valign=\"top\">\n";
	echo "											<table>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Sales Rep</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select name=\"usr_salesrep\" id=\"usr_salesrep\">\n";
	
	if ($row['srep']==1)
	{
		echo "														<option value=\"1\" SELECTED>Yes</option>\n";
		echo "														<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "														<option value=\"1\">Yes</option>\n";
		echo "														<option value=\"0\" SELECTED>No</option>\n";
	}
	
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "      										<tr>\n";
	echo "   												<td align=\"right\" title=\"Set Date to Activate New Commission Tracking\"><b>New Commissions</b></td>\n";
	echo "   												<td align=\"left\">\n";
	echo "   													<input class=\"bboxb\" type=\"text\" name=\"usr_newcommdate\" id=\"usr_newcommdate\" value=\"".trim(date('m/d/Y',strtotime($row['newcommdate'])))."\" size=\"15\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	
	if (isset($row['srep']) && $row['srep']==1)
	{
		echo "									<tr>\n";
		echo "										<td align=\"left\" colspan=\"3\"><b>Sales Rep Beginning Balance</b></td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "										<td valign=\"top\" align=\"center\"  colspan=\"3\">\n";
		echo "											<iframe src=\"subs/srepbeginbalance.php?a=list&bbsid=".$row['securityid']."\" frameborder=\"0\" scrolling=\"auto\" width=\"100%\" height=\"100%\" align=\"right\"></iframe>\n";
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	else
	{
		echo "									<tr>\n";
		echo "										<td valign=\"top\" align=\"center\"  colspan=\"3\">\n";
		echo 'This Account is not flagged as a Sales Rep';
		echo "										</td>\n";
		echo "									</tr>\n";
	}
	
	echo "											</table>\n";
	echo "										</td>\n";
	echo "										<td valign=\"top\">\n";
	echo "											<table width=\"200px\">\n";
	echo "												<tr>\n";
	echo "													<td align=\"left\"><b>Update Status:</b></td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"left\"><div id=\"status_srep\"></div></td>\n";
	echo "												</tr>\n";
	echo "											</table>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "								</table>\n";
	echo "</form>";
}

function update_JMSSalesRepInfo($sid,$db)
{
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$qry1  = "UPDATE security SET ";
	$qry1 .= "srep='".$_REQUEST['usr_salesrep']."',newcommdate='".$_REQUEST['usr_newcommdate']."',adminid='".$_SESSION['securityid']."',admindate=getdate() ";
	$qry1 .= "WHERE securityid=".$sid.";";
	$res1  = mssql_query($qry1);
	
	//return $qry1;
	
	//get_JMSSecurityInfo($sid);
}

function get_JMSUserSysInfo($sid)
{
	$qry  = "SELECT ";
	$qry .= "securityid, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "adminid, ";
	$qry .= "admindate ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid=".$row['adminid'].";";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);
	
	$qry6 = "SELECT securityid FROM logstate WHERE securityid=".$row['securityid'].";";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row['securityid']."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	echo "								<table>\n";
	echo "									<tr>\n";
	echo "   									<td align=\"right\"><b>Logged In</b></td>\n";
	echo "										<td align=\"left\">\n";

	if ($nrow6 >= 1)
	{
		echo "<font color=\"red\">Yes</font>";
	}
	else
	{
		echo "No";
	}

	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Last Login</b></td>\n";
	echo "										<td align=\"left\">";
	
	if (strtotime($row['curr_login']) < strtotime('1/1/2004'))
	{
		echo "<font color=\"red\">Never</font>";
	}
	else
	{
		echo date('m/d/y g:iA',strtotime($row['curr_login']));
	}
	
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Date Added</b></td>\n";
	echo "										<td align=\"left\">";
	
	echo date('m/d/y g:iA',strtotime($row['added']));
		
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Date Updated</b></td>\n";
	echo "										<td align=\"left\">";
	
	if (strtotime($row['admindate']) < strtotime('1/1/2004'))
	{
		echo "<font color=\"red\">Never</font>";
	}
	else
	{
		echo date('m/d/y g:iA',strtotime($row['admindate']));
	}
	
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Updated By</b></td>\n";
	echo "										<td align=\"left\">";
	
	if (strtotime($row['admindate']) >= strtotime('1/1/2004'))
	{
		echo "".$row5['fname']." ".$row5['lname']."";
	}
	
	echo "										</td>\n";
	//echo "										<td align=\"left\">".$row5['fname']." ".$row5['lname']."</td>\n";
	
	if ($row10['Logons'] > 0)
	{
		$userrate=round(($row10['Logoffs']/$row10['Logons']) * 100);
		//$userrate=round((($row10['Logoffs'] + $row10['Events'])/($row10['Logons'] + $row10['Events'])) * 100);
	}
	else
	{
		$userrate='NA';
	}
	
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>User Rating</b></td>\n";
	echo "										<td align=\"left\">".$userrate."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Logons</b></td>\n";
	echo "										<td align=\"left\">".$row10['Logons']."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Logoffs</b></td>\n";
	echo "										<td align=\"left\">".$row10['Logoffs']."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Events</b></td>\n";
	echo "										<td align=\"left\">".$row10['Events']."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td><img src=\"images/pixel.gif\"></td>\n";
	echo "										<td><img src=\"images/pixel.gif\"></td>\n";
	echo "									</tr>\n";
	echo "							</table>\n";
}

function get_MASAccountingInfoOLD($sid)
{
	$altid_ar=array();

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);
	
	//echo $qry;
	$qry0 = "SELECT officeid,code,name,gm,sm,accountingsystem,enmas FROM offices WHERE officeid=".$row[1].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);
	
	if (isset($row0[6]) and $row0[6]==1)
	{
		$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid=".$row[1].";";
		$res1 = mssql_query($qry1);
	
		$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
		$res2 = mssql_query($qry2);
	
		$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
		$res3 = mssql_query($qry3);
		$nrow3= mssql_num_rows($res3);
	
		$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid=".$row[1]." ORDER BY lname ASC;";
		$res4 = mssql_query($qry4);
	
		$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
		$res5 = mssql_query($qry5);
		$row5 = mssql_fetch_array($res5);
	
		$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
		$odbc_add	=	"67.154.183.30";
		$odbc_db	=	"master"; #the name of the database
		$odbc_user	=	"MAS_REPORTS"; #a valid username
		$odbc_pass	=	"reports"; #a password for the username
	
		$qry6 = "SELECT securityid FROM logstate WHERE securityid=".$row[0].";";
		$res6 = mssql_query($qry6);
		$nrow6= mssql_num_rows($res6);
	
		$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!=".$row[0]." and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
		$res7 = mssql_query($qry7);
		$nrow7= mssql_num_rows($res7);
		
		//echo $qry7."<br>";
		
		$qry8 = "SELECT * FROM secondaryids WHERE securityid=".$row[0].";";
		$res8 = mssql_query($qry8);
		$nrow8= mssql_num_rows($res8);
		
		if ($nrow8 > 0)
		{
			while ($row8 = mssql_fetch_array($res8))
			{
				$altid_ar[]=$row8['secid'];
			}
		}
		
		$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
		$res9 = mssql_query($qry9);
		$row9 = mssql_fetch_array($res9);
		$nrow9= mssql_num_rows($res9);
		
		$qry10 = "
		select 
			distinct(E.sid)
			,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
			,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
			,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
		from 
			jest_stats..events as E 
		where 
			E.evdate >= (getdate() - 30)
			and sid=".$row[0]."
		order by E.sid asc;
		";
		$res10 = mssql_query($qry10);
		$row10 = mssql_fetch_array($res10);
		
		//echo $qry9."<br>";
	
		$sarray=explode(",",$row[5]);
		$marray=explode(",",$row[15]);
	
		if (isset($row[22]) && strlen($row[22]) > 3)
		{
			$hdate = date("m/d/Y", strtotime($row[22]));
		}
		else
		{
			$hdate="";
		}
	
		$brdr=0;
		$hlpnd=1;
		
		echo "<input type=\"hidden\" name=\"usr_sid\" id=\"usr_sid\" value=\"".$sid."\">\n";
		
		echo "								<table>\n";
		echo "									<tr>\n";
		echo "										<td align=\"right\" colspan=\"2\"><a href=\"#\" id=\"update_MASAccountingInfo\">Save <img src=\"images/save.gif\"></a></td>\n";
		echo "									</tr>\n";
		echo "      							<tr>\n";
		echo "   									<td align=\"right\"><b>Accounting Office</b></td>\n";
		echo "   									<td align=\"left\">\n";
		echo "   										<input class=\"bboxb\" type=\"text\" name=\"mas_office\" id=\"usr_mas_office\" value=\"".trim($row[17])."\" size=\"5\">\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "      								<tr>\n";
		echo "   									<td align=\"right\"><b>Accounting Payroll ID</b></td>\n";
		echo "   									<td align=\"left\">\n";
		echo "   										<input class=\"bboxb\" type=\"text\" name=\"mas_prid\" id=\"usr_mas_prid\" value=\"".trim($row[35])."\" size=\"7\">\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "   										<input type=\"hidden\" name=\"mas_div\" id=\"usr_mas_div\" value=\"".trim($row[18])."\">\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>New Construction Link</b></td>\n";
		echo "   									<td align=\"left\">\n";
		
		echo $row[16].':'.$row[17];
	
		if ($row[17]==0)
		{
			echo "   										<input class=\"bboxb\" type=\"text\" name=\"masid\" id=\"usr_masid\" value=\"".trim($row[16])."\" size=\"5\" maxlength=\"5\" DISABLED>\n";
			echo "   										<input type=\"hidden\" name=\"masid\" id=\"usr_masid\" value=\"".trim($row[16])."\">\n";
		}
		else
		{
			$odbc_conn1	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","");
			$odbc_qry1	=	"SELECT SalespersonNumber,Name,Division FROM MAS_".$row[17].".dbo.ARD_SalespersonMasterFile WHERE Name LIKE '%".substr($row[3],0,3)."%';";
			$odbc_res1	=	odbc_exec($odbc_conn1, $odbc_qry1);
	
			//echo $odbc_qry1."<br>";
	
			echo "   										<select name=\"masid\" id=\"usr_masid\">\n";
			echo "   											<option value=\"0\">None</option>\n";
	
			while(odbc_fetch_row($odbc_res1))
			{
				$odbc_ret11 = odbc_result($odbc_res1, 1);
				$odbc_ret12 = odbc_result($odbc_res1, 2);
				$odbc_ret13 = odbc_result($odbc_res1, 3);
	
				if ($odbc_ret13==$row[18] && $odbc_ret11==$row[16])
				{
					echo "   											<option value=\"".$odbc_ret11.":".$odbc_ret13."\" SELECTED>(".$odbc_ret13.") (".$odbc_ret11.") ".$odbc_ret12."</option>\n";
				}
				else
				{
					echo "   											<option value=\"".$odbc_ret11.":".$odbc_ret13."\">(".$odbc_ret13.") (".$odbc_ret11.") ".$odbc_ret12."</option>\n";
				}
			}
	
			odbc_free_result($odbc_res1);
			odbc_close($odbc_conn1);
	
			echo "   										</select>\n";
		}
	
		//odbc_close($odbc_conn);
	
		echo "   									</td>\n";
		echo "   								</tr>\n";
		echo "   									<input type=\"hidden\" name=\"rmas_div\" id=\"usr_rmas_div\" value=\"".$row[30]."\">\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "   									<td align=\"right\"><b>Renovation Link</b></td>\n";
		echo "   									<td align=\"left\">\n";
		
		if ($row[17]==0)
		{
			echo "   										<input class=\"bboxb\" type=\"text\" name=\"masid\" id=\"usr_masid\" value=\"".trim($row[29])."\" size=\"5\" maxlength=\"5\" DISABLED>\n";
			echo "   										<input type=\"hidden\" name=\"rmasid\" id=\"usr_rmasid\" value=\"".trim($row[29])."\">\n";
		}
		else
		{
			//echo $row[17]."<br>";
			//echo $row[29]."<br>";
			//echo $row[30]."<br>";
			
			$odbc_conn2	=	odbc_connect("DRIVER=SQL Server;SERVER=".$odbc_ser.";UID=".$odbc_user.";PWD=".$odbc_pass.";DATABASE=".$odbc_db.";Address=".$odbc_add.",1433","","");
			//$odbc_qry1	=	"SELECT SalespersonNumber,Name,Division FROM MAS_".$row[17].".dbo.ARD_SalespersonMasterFile WHERE Division='".$row[18]."';";
			$odbc_qry2	=	"SELECT SalespersonNumber,Name,Division FROM MAS_".$row[17].".dbo.ARD_SalespersonMasterFile WHERE Name LIKE '%".substr($row[3],0,3)."%';";
			$odbc_res2	=	odbc_exec($odbc_conn2, $odbc_qry2);
	
			//echo $odbc_qry1."<br>";
			
			echo "   										<select name=\"rmasid\" id=\"usr_rmasid\">\n";
			echo "   											<option value=\"0\">None</option>\n";
	
			while(odbc_fetch_row($odbc_res2))
			{
				$odbc_ret21 = odbc_result($odbc_res2, 1);
				$odbc_ret22 = odbc_result($odbc_res2, 2);
				$odbc_ret23 = odbc_result($odbc_res2, 3);
	
				if ($odbc_ret23==$row[30] && $odbc_ret21==$row[29])
				{
					echo "   											<option value=\"".$odbc_ret21.":".$odbc_ret23."\" SELECTED>(".$odbc_ret23.") (".$odbc_ret21.") ".$odbc_ret22."</option>\n";
				}
				else
				{
					echo "   											<option value=\"".$odbc_ret21.":".$odbc_ret23."\">(".$odbc_ret23.") (".$odbc_ret21.") ".$odbc_ret22."</option>\n";
				}
			}
	
			odbc_free_result($odbc_res2);
			odbc_close($odbc_conn2);
	
			echo "   										</select>\n";
		}
	
		//odbc_close($odbc_conn);
	
		echo "   									</td>\n";
		echo "   								</tr>\n";
		echo "								</table>\n";
	}
	else
	{
		echo 'MAS not enabled for this Company';	
	}
}

function get_MASAccountingInfo($sid)
{
	//echo __FUNCTION__.'<br>';
	$altid_ar=array();

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "mas_prid ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	//echo $qry;
	$qry0 = "SELECT officeid,code,name,gm,sm,accountingsystem,enmas FROM offices WHERE officeid=".$row['officeid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
		
	$brdr=0;
	$hlpnd=1;
		
	echo "							<form id=\"MASAcctForm\">\n";
	echo "							<input type=\"hidden\" name=\"usr_sid\" id=\"usr_sid\" value=\"".$sid."\">\n";
	echo "   						<input type=\"hidden\" id=\"usr_mas_office\" name=\"mas_office\" value=\"".trim($row['mas_office'])."\">\n";
	echo "								<table>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\" colspan=\"2\"><a href=\"#\" id=\"update_MASAccountingInfo\">Save <img src=\"images/save.gif\"></a></td>\n";
	echo "									</tr>\n";
	//echo "      							<tr>\n";
	//echo "   									<td align=\"right\"><b>Accounting Office</b></td>\n";
	//echo "   									<td align=\"left\">\n";
	//echo "   										<input class=\"bboxb\" type=\"text\" id=\"usr_mas_office\" name=\"mas_office\" value=\"".trim($row['mas_office'])."\" size=\"5\">\n";
	//echo "										</td>\n";
	//echo "									</tr>\n";
	echo "      							<tr>\n";
	echo "   									<td align=\"right\"><b>Accounting Payroll ID</b></td>\n";
	echo "   									<td align=\"left\">\n";
	echo "   										<input class=\"bboxb\" type=\"text\" id=\"usr_mas_prid\" name=\"mas_prid\" value=\"".trim($row['mas_prid'])."\" size=\"7\">\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "   									<td align=\"right\"><b>New Construction Division/ID</b></td>\n";
	echo "   									<td align=\"left\">\n";
	echo "   										<input class=\"bboxb\" type=\"text\" id=\"usr_mas_div\" name=\"mas_div\" value=\"".trim($row['mas_div'])."\" size=\"2\" maxlength=\"2\"> /\n";
	echo "   										<input class=\"bboxb\" type=\"text\" id=\"usr_masid\" name=\"masid\" value=\"".trim($row['masid'])."\" size=\"5\" maxlength=\"5\">\n";
	echo "   									</td>\n";
	echo "   								</tr>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "   									<td align=\"right\"><b>Renovation Division/ID</b></td>\n";
	echo "   									<td align=\"left\">\n";
	echo "   										<input class=\"bboxb\" type=\"text\" id=\"usr_rmas_div\" name=\"rmas_div\" value=\"".$row['rmas_div']."\" size=\"2\" maxlength=\"2\"> /\n";
	echo "   										<input class=\"bboxb\" type=\"text\" id=\"usr_rmasid\" name=\"rmasid\" value=\"".trim($row['rmasid'])."\" size=\"5\" maxlength=\"5\">\n";
	echo "   									</td>\n";
	echo "   								</tr>\n";
	echo "								</table>\n";
	echo "							</form>\n";
}

function get_QBSAccountingInfo($sid)
{
	$qry  = "SELECT ";
	$qry .= "officeid, ";
	$qry .= "securityid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "ListID, ";
	$qry .= "EditSequence, ";
	$qry .= "SR_ListID, ";
	$qry .= "SR_EditSequence, ";
	$qry .= "srep ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qry1 = "SELECT officeid,enquickbooks FROM offices WHERE officeid=".$row['officeid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	if (isset($row1['enquickbooks']) and $row1['enquickbooks']==1)
	{
		echo "   					<input type=\"hidden\" name=\"sys_fname_qbs\" id=\"sys_fname_qbs\" value=\"".trim($row['fname'])."\">\n";
		echo "   					<input type=\"hidden\" name=\"sys_lname_qbs\" id=\"sys_lname_qbs\" value=\"".trim($row['lname'])."\">\n";
		echo "   					<input type=\"hidden\" name=\"sys_ListID_qbs\" id=\"sys_ListID_qbs\" value=\"".trim($row['ListID'])."\">\n";
		echo "   					<input type=\"hidden\" name=\"sys_EditS_qbs\" id=\"sys_EditS_qbs\" value=\"".trim($row['EditSequence'])."\">\n";
		echo "   					<input type=\"hidden\" name=\"sys_SR_ListID_qbs\" id=\"sys_SR_ListID_qbs\" value=\"".trim($row['SR_ListID'])."\">\n";
		echo "   					<input type=\"hidden\" name=\"sys_SR_EditS_qbs\" id=\"sys_SR_EditS_qbs\" value=\"".trim($row['SR_EditSequence'])."\">\n";
		echo "						<table>\n";
		echo "							<tr>\n";
		echo "								<td align=\"left\">\n";
		echo "									<table width=\"100%\">\n";
		echo "										<tr>\n";
		echo "											<td><b>JMS Stored Data</b> <img src=\"images/help.png\" title=\"Data Stored in the JMS\"></td>\n";
		echo "											<td align=\"right\">\n";
		echo "												<button id=\"refresh_QBEmployeeConfig\">Refresh <img src=\"images/arrow_refresh_small.png\"></button>\n";
		echo "												<button id=\"submit_sendEmployeeConfig\">Send <img src=\"images/save.png\"></button>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "								<td>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td valign=\"top\">\n";
		echo "									<table class=\"outer\" width=\"400px\">\n";
		echo "										<tr>\n";
		echo "   										<td align=\"right\" width=\"100px\"><b>Name</b></td>\n";
		echo "   										<td align=\"left\">".$row['fname']." ".$row['lname']."</td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "   										<td align=\"right\" width=\"100px\"><b>Sales Rep</b></td>\n";
		echo "   										<td align=\"left\">\n";
		
		if ($row['srep']==1)
		{
			echo "   										<input type=\"hidden\" name=\"sys_srep\" id=\"sys_srep\" value=\"1\">\n";
			echo 'Yes';
		}
		else
		{
			echo "   										<input type=\"hidden\" name=\"sys_srep\" id=\"sys_srep\" value=\"0\">\n";
			echo 'No';
		}
		
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "   										<td align=\"right\" width=\"100px\"><b>Employee ListID</b></td>\n";
		echo "   										<td align=\"left\">".$row['ListID']."</td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "   										<td align=\"right\" width=\"100px\"><b>Employee ES</b></td>\n";
		echo "   										<td align=\"left\">".$row['EditSequence']."</td>\n";
		echo "										</tr>\n";
		echo "      								<tr>\n";
		echo "   										<td align=\"right\" width=\"100px\"><b>SalesRep ID</b></td>\n";
		echo "   										<td align=\"left\">".$row['SR_ListID']."</td>\n";
		echo "										</tr>\n";
		echo "      								<tr>\n";
		echo "   										<td align=\"right\" width=\"100px\"><b>SalesRep ES</b></td>\n";
		echo "   										<td align=\"left\">".$row['SR_EditSequence']."</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "								<td valign=\"top\">\n";
		echo "									<table width=\"200px\">\n";
		echo "										<tr>\n";
		echo "											<td align=\"left\"><b>Status:</b></td>\n";
		echo "										</tr>\n";
		echo "										<tr>\n";
		echo "											<td align=\"left\"><div id=\"status_accounting_qbs\"></div></td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td align=\"left\">\n";
		echo "									<table width=\"100%\">\n";
		echo "										<tr>\n";
		echo "											<td><b>Quickbooks Query Data</b> <img src=\"images/help.png\" title=\"Data Requested from Quickbooks\"></td>\n";
		echo "											<td align=\"right\">\n";
		echo "												<button class=\"view_QBQR_List\" value=\"0\">List <img src=\"images/application_view_list.png\"></button>\n";
		echo "												<button id=\"query_EmployeeQBSConfig\">Query <img src=\"images/magnifier.png\"></button>\n";
		echo "											</td>\n";
		echo "										</tr>\n";
		echo "									</table>\n";
		echo "								</td>\n";
		echo "								<td>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "							<tr>\n";
		echo "								<td>\n";
		echo "									<div id=\"status_accounting_qbs\"></div></td>\n";
		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
	}
	else
	{
		echo 'Quickbooks not enabled for this Company';	
	}
}

function get_EmployeeStatus($sid,$db)
{
	//echo $sid;
	$out = '<table>';
	mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$qry  = "SELECT * FROM quickbooks_queue WHERE ident=".$sid." and (qb_action='EmployeeAdd' OR qb_action='SalesRepAdd');";
	$res = mssql_query($qry);

	while($row = mssql_fetch_array($res))
	{
		$out=$out.'<tr><td>';
		$out=$out.$row['qb_action'];
		
		if ($row['qb_status']=='q')
		{
			$out=$out.': Queued ';
		}
		elseif ($row['qb_status']=='e')
		{
			$out=$out.' : Error ';
		}
		elseif ($row['qb_status']=='i')
		{
			$out=$out.' : Incomplete ';
		}
		elseif ($row['qb_status']=='s')
		{
			$out=$out.' : Processed ';
		}
		
		if ($row['qb_status']=='e' or $row['qb_status']=='i')
		{
			$out=$out.' : '.$row['msg'];
		}
		
		$out=$out.'</td></tr>';
	}
	
	$out = $out.'</table>';
	return $out;
}

function get_JMSAltOfficeAccessInfo($sid)
{
 	$qry  = "SELECT securityid,officeid,slevel,altoffices FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	//echo $qry;
	
	$tsec=explode(",",$row['slevel']);
	if ($tsec[6] != 7)
	{
		echo 'Alt Office Config N/A for this User';
	}
	else
	{
		$alloffs=array();
		$qry4 = "SELECT officeid,name,active,grouping FROM offices where active=1 ORDER BY grouping ASC,name ASC;";
		$res4 = mssql_query($qry4);
		$nrow4= mssql_num_rows($res4);
		
		if ($nrow4 > 0)
		{
			while ($row4 = mssql_fetch_array($res4))
			{
				$alloffs[$row4['officeid']]=array('oid'=>$row4['officeid'],'oname'=>$row4['name'],'active'=>$row4['active'],'grouping'=>$row4['grouping']);
			}
		}
		
		$altoffs=array();
		$qry5 = "SELECT * FROM jest..alt_security_levels WHERE sid='".$row[0]."';";
		$res5 = mssql_query($qry5);
		$nrow5= mssql_num_rows($res5);
		
		if ($nrow5 > 0)
		{
			while ($row5 = mssql_fetch_array($res5))
			{
				$altoffs[$row5['oid']]=array('oid'=>$row5['oid'],'slevel'=>$row5['slevel']);
			}
		}
		
		echo "<table>\n";
		echo "<tr>\n";
		echo "   <td>\n";
		echo "		<table width=\"100%\">\n";
		echo "			<tr>\n";
		echo "				<td class=\"ltgray_und\" align=\"left\" colspan=\"2\">\n";
		echo "					<table width=\"100%\">\n";
		echo "						<tr>\n";
		echo "							<td align=\"left\"><b>Current Alternate Office Access:</b></td>\n";
		echo "							<td align=\"right\"><div id=\"alt_security_update_status\"></div></td>\n";
		echo "						</tr>\n";
		echo "					</table>\n";
		echo "				</td>\n";
		echo "			</tr>\n";

		//$altoffs=explode(",",$row['altoffices']);
		//while ($row4 = mssql_fetch_array($res4))
		$home_office_set=false;
		foreach ($alloffs as $on=>$ov)
		{
			$cellclass='wh_und';
			
			if ($ov['grouping']==4)
			{
				$cellclass='ltred_und';
			}
			
			if ($ov['active']==0)
			{
				$cellclass='red_und';
			}

			if (is_array($altoffs) && array_key_exists($on,$altoffs))
			{
				echo "			<tr>\n";
				echo "				<td class=\"".$cellclass."\" align=\"left\">".$ov['oname']."</td>\n";
				echo "				<td class=\"blu_und\" align=\"left\" width=\"300px\">\n";
				echo "					<div class=\"alt_security_profile\">\n";
				echo "						<table>\n";
				echo "							<tr>\n";
				echo "								<td align=\"left\">\n";
				
				if ($row['officeid']==$on)
				{
					$home_office_set=true;
					echo "									<div class=\"alt_security_levels\" id=\"home_office\">\n";
				}
				else
				{
					echo "									<div class=\"alt_security_levels\">\n";	
				}
				
				slevelformflat(explode(",",$altoffs[$on]['slevel']),$altoffs[$on]['oid']);
				
				echo "									</div>\n";
				echo "								</td>\n";
				echo "								<td align=\"center\"></td>\n";
				echo "							</tr>\n";
				echo "						</table>\n";
				echo "					</div>\n";
				echo "				</td>\n";
				echo "			</tr>\n";
			}
		}

		echo "			<tr>\n";
		echo "				<td align=\"center\" colspan=\"2\"><hr width=\"75%\"></td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td class=\"ltgray_und\" align=\"left\"><b>Add Alternate Office Access:</b></td>\n";
		echo "				<td class=\"ltgray_und\" align=\"right\">\n";
		
		if ($home_office_set)
		{
			echo "					Copy Home Office settings: <input id=\"replicate_home_office_profile\" type=\"checkbox\" value=\"1\">\n";
		}
		else
		{
			echo "					<img src=\"images/pixel.gif\">\n";
		}
		
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			<tr>\n";
		echo "				<td valign=\"bottom\" align=\"right\">\n";
		echo "					<select id=\"add_security_oid\">\n";
		echo "						<option value=\"0\">Select...</option>\n";
		
		foreach ($alloffs as $nn => $nv)
		{
			if (!array_key_exists($nn,$altoffs))
			{
				echo "						<option value=\"".$nn."\">".$nv['oname']."</option>\n";
			}
		}
		
		echo "					</select>\n";
		echo "				</td>\n";
		echo "				<td align=\"left\" width=\"300px\">\n";
		echo "					<div class=\"add_security_profile\">\n";
		echo "						<table>\n";
		echo "							<tr>\n";
		echo "								<td align=\"left\">\n";
		echo "									<div class=\"add_security_levels\">\n";
		
		addslevelformflat();
		
		echo "									</div>\n";
		echo "								</td>\n";
		echo "								<td valign=\"bottom\" align=\"center\"><a href=\"#\" id=\"submit_add_alt_security_profile\"><img src=\"images/save.gif\"></a></td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</div>\n";
		echo "				</td>\n";
		echo "			</tr>\n";
		echo "			</table>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function get_JMSAltOfficeAccessInfoOld($sid)
{
	$qry  = "SELECT ";
	$qry .= "securityid, ";
	$qry .= "officeid, ";
	$qry .= "slevel, ";
	$qry .= "altoffices ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	//echo $qry;
	
	$tsec=explode(",",$row['slevel']);
	if ($tsec[6] != 7)
	{
		echo 'Alt Office Config N/A for this User';
	}
	else
	{
		$altoffs=array();
		$qry4 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
		$res4 = mssql_query($qry4);
		$nrow4= mssql_num_rows($res4);
		
		$qry5 = "SELECT * FROM jest..alt_security_levels WHERE sid='".$row[0]."';";
		$res5 = mssql_query($qry5);
		$nrow5= mssql_num_rows($res5);
		
		if ($nrow5 > 0)
		{
			while ($row5 = mssql_fetch_array($res5))
			{
				$altoffs[$row5['oid']]=array('oid'=>$row5['oid'],'slevel'=>$row5['slevel']);
			}
		}
		
		//echo '<pre>';
		//print_r($altoffs);
		//echo '</pre>';

		//echo "<form method=\"post\">\n";
		//echo "<input type=\"hidden\" name=\"action\" value=\"maint\">\n";
		//echo "<input type=\"hidden\" name=\"call\" value=\"users\">\n";
		//echo "<input type=\"hidden\" name=\"subq\" value=\"set_offlist\">\n";
		//echo "<input type=\"hidden\" name=\"userid\" value=\"".$row['securityid']."\">\n";
		//echo "<input type=\"hidden\" name=\"officeid\" value=\"".$row['officeid']."\">\n";
		//echo "<input type=\"hidden\" name=\"alevel\" value=\"".$row['slevel']."\">\n";
		echo "<table class=\"outer\">\n";
		echo "<tr>\n";
		echo "   <td>\n";
		echo "		<table width=\"100%\">\n";
		echo "			<tr>\n";
		echo "				<td class=\"ltgray_und\" align=\"left\" colspan=\"3\">\n";
		echo "					<table width=\"100%\">\n";
		echo "						<tr>\n";
		echo "							<td align=\"left\"><b>Office Access List</b></td>\n";
		echo "							<td align=\"right\">Replicate?</td>\n";
		echo "							<td align=\"left\"><input class=\"transnb\" type=\"checkbox\" name=\"replicate\" value=\"1\" title=\"Check this box to replicate security levels\"></td>\n";
		//echo "				<td class=\"ltgray_und\" align=\"right\"><input class=\"buttondkgry\" type=\"submit\" value=\"Set Access\"></td>\n";
		echo "						</tr>\n";
		echo "					</table>\n";
		echo "				</td>\n";
		echo "			</tr>\n";

		//$altoffs=explode(",",$row['altoffices']);
		while ($row4 = mssql_fetch_array($res4))
		{
			echo "				<tr>\n";

			if (is_array($altoffs) && array_key_exists($row4['officeid'],$altoffs))
			{
				//$qry5 = "SELECT * FROM jest..alt_security_levels WHERE oid='".$row4['officeid']."' and sid='".$row[0]."';";
				//$res5 = mssql_query($qry5);
				//$nrow5= mssql_num_rows($res5);
				
				echo "				<td class=\"blu_und\" colspan=\"2\" align=\"left\">".$row4['name']."</td>\n";
				echo "				<td class=\"blu_und\" align=\"center\">\n";
				echo "					<input class=\"transnb\" type=\"checkbox\" name=\"chk".$row4['officeid']."\" value=\"".$row4['officeid']."\" CHECKED>\n";
				echo "				</td>\n";
				echo "				<td class=\"blu_und\" align=\"center\">\n";
				
				slevelformflat(explode(",",$altoffs[$row4['officeid']]['slevel']),$altoffs[$row4['officeid']]['oid']);
				
				/*
				if ($nrow5 > 1)
				{
					echo "Security Error Occured";
				}
				elseif ($nrow5 == 1)
				{
					//$row5 = mssql_fetch_array($res5);
					//slevelformflat(explode(",",$row5['slevel']),$row5['oid']);
					slevelformflat(explode(",",$altoffs[$row4['officeid']]['slevel']),$altoffs[$row4['officeid']]['oid']);
				}
				else
				{
					slevelformflat('',$row4['officeid']);
				}
				*/
				
				echo "				</td>\n";
			}
			else
			{
				echo "				<td class=\"wh_und\" colspan=\"2\" align=\"left\">".$row4['name']."</td>\n";
				echo "				<td class=\"wh_und\" align=\"center\">\n";
				echo "					<input class=\"checkboxwh\" type=\"checkbox\" name=\"chk".$row4['officeid']."\" value=\"".$row4['officeid']."\">\n";
				echo "				</td>\n";
				echo "				<td class=\"wh_und\" align=\"left\"></td>\n";
			}

			echo "				</tr>\n";
		}

		echo "			</table>\n";
		//echo "			</form>\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function get_JMSFunctionalInfo($sid)
{
	$altid_ar=array();
	
	if (!isset($sid) or $sid==0)
	{
		echo 'ERROR Loading User Funtion Config';
		exit;
	}

	$qry  = "SELECT ";
	$qry .= "securityid, ";  
	$qry .= "officeid, ";
	$qry .= "fname, ";
	$qry .= "lname, ";
	$qry .= "role, ";
	$qry .= "slevel, ";
	$qry .= "login, ";
	$qry .= "logstate, ";
	$qry .= "added, ";
	$qry .= "curr_login, ";
	$qry .= "admstaff, ";
	$qry .= "sidm, ";
	$qry .= "altoffices, ";
	$qry .= "assistant, ";
	$qry .= "sub_officeid, ";
	$qry .= "mlevel, ";
	$qry .= "masid, ";
	$qry .= "mas_office, ";
	$qry .= "mas_div, ";
	$qry .= "adminid, ";
	$qry .= "admindate, ";
	$qry .= "off_demo, ";
	$qry .= "hdate, ";
	$qry .= "dsgfperiod, ";
	$qry .= "dsgfarray, ";
	$qry .= "altid, ";
	$qry .= "excmess, ";
	$qry .= "devmode, ";
	$qry .= "gmreports, ";
	$qry .= "rmasid, ";
	$qry .= "rmas_div, ";
	$qry .= "email, ";
	$qry .= "srep, ";
	$qry .= "emailchrg, ";
	$qry .= "csrep, ";
	$qry .= "mas_prid, ";
	$qry .= "phone, ";
	$qry .= "ext, ";
	$qry .= "admindigreport, ";
	$qry .= "tester, ";
	$qry .= "newcommdate, ";
	$qry .= "menutype, ";
	$qry .= "modcomm, ";
	$qry .= "emailtemplateaccess, ";
	$qry .= "contactlist, ";
	$qry .= "stitle, ";
	$qry .= "networkaccess, ";
	$qry .= "filestoreaccess, ";
	$qry .= "officelist, ";
	$qry .= "constructdateaccess, ";
	$qry .= "enotify, ";
	$qry .= "JobCommEdit, ";
	$qry .= "returntolist, ";
	$qry .= "PurchaseOrder, ";
	$qry .= "acctngrelease, ";
	$qry .= "conspiperpt, ";
	$qry .= "passcnt, ";
	$qry .= "digstandingrpt, ";
	$qry .= "testerenable, ";
	$qry .= "jobprogress, ";
	$qry .= "screport ";
	$qry .= " FROM security WHERE securityid=".$sid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_row($res);
	
	$qry0 = "SELECT officeid,code,name,gm,sm FROM offices WHERE officeid=".$row[1].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_row($res0);

	$qry1 = "SELECT securityid,fname,lname,sidm,slevel FROM security WHERE officeid=".$row[1].";";
	$res1 = mssql_query($qry1);

	$qry2 = "SELECT officeid,name FROM offices WHERE active=1 ORDER BY name ASC;";
	$res2 = mssql_query($qry2);

	$qry3 = "SELECT lname,fname,slevel FROM security WHERE sidm='".$row[0]."' ORDER BY substring(slevel,13,1) desc, lname ASC;";
	$res3 = mssql_query($qry3);
	$nrow3= mssql_num_rows($res3);

	$qry4 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid='".$row[1]."' ORDER BY lname ASC;";
	$res4 = mssql_query($qry4);

	$qry5 = "SELECT securityid,fname,lname FROM security WHERE securityid='".$row[19]."';";
	$res5 = mssql_query($qry5);
	$row5 = mssql_fetch_array($res5);

	$odbc_ser	=	"67.154.183.30"; #the name of the SQL Server
	$odbc_add	=	"67.154.183.30";
	$odbc_db	=	"master"; #the name of the database
	$odbc_user	=	"MAS_REPORTS"; #a valid username
	$odbc_pass	=	"reports"; #a password for the username

	$qry6 = "SELECT securityid FROM logstate WHERE securityid='".$row[0]."'";
	$res6 = mssql_query($qry6);
	$nrow6= mssql_num_rows($res6);

	$qry7 = "SELECT s.securityid,s.lname,s.fname,(SELECT substring(name,1,3) from offices where officeid=s.officeid) as oname,substring(s.slevel,13,1) as slevel FROM security as s WHERE right(s.slevel,1)!=0  and s.securityid!='".$row[0]."' and s.srep=1 ORDER BY substring(s.slevel,13,1) desc,s.lname ASC;";
	$res7 = mssql_query($qry7);
	$nrow7= mssql_num_rows($res7);
	
	//echo $qry7."<br>";
	
	$qry8 = "SELECT * FROM secondaryids WHERE securityid='".$row[0]."'";
	$res8 = mssql_query($qry8);
	$nrow8= mssql_num_rows($res8);
	
	if ($nrow8 > 0)
	{
		while ($row8 = mssql_fetch_array($res8))
		{
			$altid_ar[]=$row8['secid'];
		}
	}
	
	$qry9 = "SELECT * FROM secondaryids WHERE secid='".$row[0]."'";
	$res9 = mssql_query($qry9);
	$row9 = mssql_fetch_array($res9);
	$nrow9= mssql_num_rows($res9);
	
	$qry10 = "
	select 
		distinct(E.sid)
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=1) as 'Logons'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=2) as 'Logoffs'
		,(select count(id) from jest_stats..events where evdate >= (getdate() - 30) and sid=E.sid and status=3) as 'Events'
	from 
		jest_stats..events as E 
	where 
		E.evdate >= (getdate() - 30)
		and sid=".$row[0]."
	order by E.sid asc;
	";
	$res10 = mssql_query($qry10);
	$row10 = mssql_fetch_array($res10);
	
	//echo $qry9."<br>";

	$sarray=explode(",",$row[5]);
	$marray=explode(",",$row[15]);

	if (isset($row[22]) && strlen($row[22]) > 3)
	{
		$hdate = date("m/d/Y", strtotime($row[22]));
	}
	else
	{
		$hdate="";
	}

	$brdr=0;
	$hlpnd=1;
	
	echo "<form id=\"frm_UpdateJMSFunctionalInfo\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"usr_sid\" id=\"usr_sid\" value=\"".$sid."\">\n";
	echo "								<table width=\"350px\">\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\" colspan=\"2\"><a href=\"#\" id=\"submit_updateJMSFunctionalInfo\">Save <img src=\"images/save.gif\"></a></td>\n";
	echo "									</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Account Lockout</b></td>\n";
	echo "													<td align=\"left\">\n";
	
	if ($row[56] >= 5)
	{
		echo "<font color=\"red\"><b>Yes</b></font>";
	}
	else
	{
		echo 'No';
	}

	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Account Lockout Clear</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<input type=\"checkbox\" class=\"transnb JMStooltip\" name=\"passcnt\" id=\"usr_passcnt\" value=\"0\" title=\"Check this box to clear Account Lockout\">\n";
	echo "													</td>\n";
	echo "												</tr>\n";	
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Accounting Release</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<select class=\"JMStooltip\" name=\"acctngrelease\" id=\"usr_acctngrelease\" title=\"Enables the ability to approve Jobs for release to Accounting (MAS Ready / MAS Not Ready)\">\n";

	if ($row[54]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "											</select>\n";
	echo "										</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Developer Access</b></td>\n";
	echo "										<td align=\"left\">\n";
	echo "											<select class=\"JMStooltip\" name=\"devmode\" id=\"usr_devmode\" title=\"Grants Developer access to the JMS\">\n";

	if ($row[27]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Tester Access</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"tester\" id=\"usr_tester\" title=\"Grants access to features under Development for Testing purposes\">\n";

	if ($row[39]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}
	
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Tester Self Enable</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"testerenable\" id=\"usr_testerenable\" title=\"Grants the ability to change their Tester Access in Maintenance -> Options\">\n";

	if ($row[58]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}
	
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Job Progress Report</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"jobprogress\" id=\"usr_jobprogress\" title=\"Grants the access to the Job Progress Report\">\n";

	if ($row[59]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}
	
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "													<td align=\"right\"><b>Sales & Commission Report</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"screport\" id=\"usr_screport\" title=\"Grants the access to the Sales & Commission Report\">\n";

	if ($row[60]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}
	
	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "									<tr>\n";
	echo "   									<td align=\"right\"><b>Admin Comment Level</b></td>\n";
	echo "   									<td align=\"left\">\n";
	echo "      									<select class=\"JMStooltip\" name=\"admstaff\" id=\"usr_admstaff\" title=\"Grants access to Office Comments\">\n";

	if ($row[10]==0)
	{
		echo "      									<option value=\"0\" SELECTED>None</option>\n";
		echo "      									<option value=\"1\">Low</option>\n";
		echo "      									<option value=\"2\">Medium</option>\n";
		echo "      									<option value=\"3\">High</option>\n";
		
	}
	elseif ($row[10]==1)
	{
		echo "      									<option value=\"0\">None</option>\n";
		echo "      									<option value=\"1\" SELECTED>Low</option>\n";
		echo "      									<option value=\"2\">Medium</option>\n";
		echo "      									<option value=\"3\">High</option>\n";
	}
	elseif ($row[10]==2)
	{
		echo "      									<option value=\"0\">None</option>\n";
		echo "      									<option value=\"1\">Low</option>\n";
		echo "      									<option value=\"2\" SELECTED>Medium</option>\n";
		echo "      									<option value=\"3\">High</option>\n";
	}
	elseif ($row[10]==3)
	{
		echo "      									<option value=\"0\">None</option>\n";
		echo "      									<option value=\"1\">Low</option>\n";
		echo "      									<option value=\"2\">Medium</option>\n";
		echo "      									<option value=\"3\" SELECTED>High</option>\n";
	}

	echo "      									</select>\n";
	echo "      								</td>\n";
	echo "									</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Modify Price/Commissions</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"modcomm\" id=\"usr_modcomm\" title=\"Allows User to Adjust Price per Book and Base Commission on Estimate Retail Breakdown\">\n";

	if ($row[42]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Excl Messaging</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"excmess\" id=\"usr_excmess\" title=\"Excludes User from System Messages (Defunct)\">\n";

	if ($row[26]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>GM/Operating Reports</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"gmreports\" id=\"usr_gmreports\" title=\"Grants access to General Manager Operating reports\">\n";

	if ($row[28]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Dig Standings</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"digstandingrpt\" id=\"usr_digstandingrpt\" title=\"Grants access to Dig Report\">\n";

	if ($row[57] == 1)
	{
		echo "												<option value=\"1\" SELECTED>Basic View</option>\n";
		echo "												<option value=\"9\">Admin View</option>\n";
		echo "												<option value=\"0\">Disabled</option>\n";
	}
	elseif ($row[57] == 9)
	{
		echo "												<option value=\"1\">Basic View</option>\n";
		echo "												<option value=\"9\" SELECTED>Admin View</option>\n";
		echo "												<option value=\"0\">Disabled</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Basic View</option>\n";
		echo "												<option value=\"9\">Admin View</option>\n";
		echo "												<option value=\"0\" SELECTED>Disabled</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Admin Dig Report</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"admindigreport\" id=\"usr_admindigreport\" title=\"Grants access to the Administrative Dig Report\">\n";

	if ($row[38]==2)
	{
		echo "												<option value=\"2\" SELECTED>Create</option>\n";
		echo "												<option value=\"1\">View</option>\n";
		echo "												<option value=\"0\">No Access</option>\n";
	}
	elseif ($row[38]==1)
	{
		echo "												<option value=\"2\">Create</option>\n";
		echo "												<option value=\"1\" SELECTED>View</option>\n";
		echo "												<option value=\"0\">No Access</option>\n";
	}
	elseif ($row[38]==0)
	{
		echo "												<option value=\"2\">Create</option>\n";
		echo "												<option value=\"1\">View</option>\n";
		echo "												<option value=\"0\" SELECTED>No Access</option>\n";
	}
	else
	{
		echo "												<option value=\"0\">No Access</option>\n";
		echo "												<option value=\"1\">View</option>\n";
		echo "												<option value=\"2\">Create</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Contact List</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select name=\"contactlist\" id=\"usr_contactlist\">\n";

	if ($row[44]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "   												<td align=\"right\"><b>After Lead Update</b></td>\n";
	echo "   												<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"returntolist\" id=\"usr_returntolist\" title=\"Sets the default action taken after Updating a Lead\">\n";
	
	if ($row[52]==1)
	{
		echo "			<option value=\"0\">Return to Lead</option>\n";
		echo "			<option value=\"1\" SELECTED>Return to List</option>\n";
	}
	else
	{
		echo "			<option value=\"0\" SELECTED>Return to Lead</option>\n";
		echo "			<option value=\"1\">Return to List</option>\n";
	}
	
	echo "														</select>\n"; 
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Office List</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"officelist\" id=\"usr_officelist\" title=\"Set the sort order of the Office Listing for Users who have access to switch or edit Offices\">\n";

		if ($row[48]=='A')
		{
			echo "												<option value=\"A\" SELECTED>Alpha</option>\n";
			echo "												<option value=\"N\">Numeric</option>\n";
		}
		else
		{
			echo "												<option value=\"A\">Alpha</option>\n";
			echo "												<option value=\"N\" SELECTED>Numeric</option>\n";
		}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Email Notify</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"enotify\" id=\"usr_enotify\" title=\"Enables Email Notifications from the JMS to User (Requires valid Email Address)\">\n";

	if ($row[50]==1)
	{
		echo "												<option value=\"1\" SELECTED>Yes</option>\n";
		echo "												<option value=\"0\">No</option>\n";
	}
	else
	{
		echo "												<option value=\"1\">Yes</option>\n";
		echo "												<option value=\"0\" SELECTED>No</option>\n";
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>CS Rep Level</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"csrep\" id=\"usr_csrep\" title=\"Grants access to Customer Service Module\">\n";

	for ($cs=9;$cs>=0;$cs--)
	{
		if ($row[34]==$cs)
		{
			echo "												<option value=\"".$cs."\" SELECTED>".$cs."</option>\n";	
		}
		else
		{
			echo "												<option value=\"".$cs."\">".$cs."</option>\n";	
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Email Templates</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"emailtemplateaccess\" id=\"usr_emailtemplateaccess\" title=\"Grants access to Email Template Module\">\n";

	for ($et=9;$et>=0;$et--)
	{
		if ($row[43]==$et)
		{
			echo "												<option value=\"".$et."\" SELECTED>".$et."</option>\n";	
		}
		else
		{
			echo "												<option value=\"".$et."\">".$et."</option>\n";	
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Network Leads</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"networkaccess\" id=\"usr_networkaccess\" title=\"Grants access to Network Leads Module\">\n";

	for ($nwa=0;$nwa<=9;$nwa++)
	{
		if ($row[46]==$nwa)
		{
			echo "												<option value=\"".$nwa."\" SELECTED>".$nwa."</option>\n";
		}
		else
		{
			echo "												<option value=\"".$nwa."\">".$nwa."</option>\n";
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>File Cabinet</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"filestoreaccess\" id=\"usr_filestoreaccess\" title=\"Grants access to JMS File Cabinet (Office Configuration also required)\">\n";

	$fstar=array(0=>' - No Access',1=>' - View Only',2=>'',3=>'',4=>'',5=>' - Add',6=>' - Delete',7=>'',8=>'',9=>' - Delete All');
	
	for ($fsa=0;$fsa<=9;$fsa++)
	{
		if ($row[47]==$fsa)
		{
			echo "												<option value=\"".$fsa."\" SELECTED>".$fsa.$fstar[$fsa]."</option>\n";
		}
		else
		{
			echo "												<option value=\"".$fsa."\">".$fsa.$fstar[$fsa]."</option>\n";
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Construction Dates</b></td>\n";
	echo "													<td align=\"left\">\n";		
	echo "														<select class=\"JMStooltip\" name=\"constructdateaccess\" id=\"usr_constructdateaccess\" title=\"Grants access to the Construction Dates Module on the Customer OneSheet\">\n";

	for ($cda=0;$cda<=9;$cda++)
	{
		if ($row[49]==$cda)
		{
			echo "												<option value=\"".$cda."\" SELECTED>".$cda."</option>\n";
		}
		else
		{
			echo "												<option value=\"".$cda."\">".$cda."</option>\n";
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Purchase Order</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"PurchaseOrder\" id=\"usr_PurchaseOrder\" title=\"Grants access to Purchasing Module (in Development)\">\n";

	for ($po=9;$po>=0;$po--)
	{
		if ($row[53]==$po)
		{
			echo "												<option value=\"".$po."\" SELECTED>".$po."</option>\n";	
		}
		else
		{
			echo "												<option value=\"".$po."\">".$po."</option>\n";	
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "												<tr>\n";
	echo "													<td align=\"right\"><b>Pipeline Report</b></td>\n";
	echo "													<td align=\"left\">\n";
	echo "														<select class=\"JMStooltip\" name=\"conspiperpt\" id=\"usr_conspiperpt\" title=\"Grants access to Office Pipeline Report<br>NOTE currently only activates report for viewing at level 6 or above\">\n";

	for ($opr=9;$opr>=0;$opr--)
	{
		if ($row[55]==$opr)
		{
			echo "												<option value=\"".$opr."\" SELECTED>".$opr."</option>\n";
		}
		else
		{
			echo "												<option value=\"".$opr."\">".$opr."</option>\n";
		}
	}

	echo "														</select>\n";
	echo "													</td>\n";
	echo "												</tr>\n";
	echo "								</table>\n";
	echo "</form>\n";
}

function update_JMSFunctionalInfo($sid)
{
	/*
	echo '<pre>';
	print_r($_REQUEST);
	echo '</pre>';
	*/
	
	$qry1  = "UPDATE security SET ";
	$qry1 .= "devmode='".$_REQUEST['usr_devmode']."', excmess='".$_REQUEST['usr_excmess']."',digstandingrpt='".$_REQUEST['usr_digstandingrpt']."',";
	$qry1 .= "gmreports='".$_REQUEST['usr_gmreports']."',csrep='".$_REQUEST['usr_csrep']."',admindigreport='".$_REQUEST['usr_admindigreport']."',";
	$qry1 .= "tester='".$_REQUEST['usr_tester']."',testerenable='".$_REQUEST['usr_testerenable']."',modcomm='".$_REQUEST['usr_modcomm']."', ";
	$qry1 .= "emailtemplateaccess='".$_REQUEST['usr_emailtemplateaccess']."',contactlist='".$_REQUEST['usr_contactlist']."', ";
	$qry1 .= "networkaccess='".$_REQUEST['usr_networkaccess']."',filestoreaccess='".$_REQUEST['usr_filestoreaccess']."',officelist='".$_REQUEST['usr_officelist']."', ";
	$qry1 .= "jobprogress='".$_REQUEST['usr_jobprogress']."',screport='".$_REQUEST['usr_screport']."',constructdateaccess='".$_REQUEST['usr_constructdateaccess']."',enotify='".$_REQUEST['usr_enotify']."', ";
	
	if (isset($_REQUEST['usr_passcnt']) and $_REQUEST['usr_passcnt']==0)
	{
		$qry1 .= "passcnt=0,";
	}
	
	$qry1 .= "PurchaseOrder='".$_REQUEST['usr_PurchaseOrder']."',returntolist='".$_REQUEST['usr_returntolist']."',";
	$qry1 .= "acctngrelease='".$_REQUEST['usr_acctngrelease']."',conspiperpt='".$_REQUEST['usr_conspiperpt']."', ";
	$qry1 .= "adminid='".$_SESSION['securityid']."',admindate=getdate() ";
	$qry1 .= "WHERE securityid=".$sid.";";
	$res1  = mssql_query($qry1);
	
	//echo $qry1;
	get_JMSFunctionalInfo($sid);

}

function update_MASAccountingInfo($sid)
{	
	//echo 'Updating...<br>';	
	$qry1  = "UPDATE security SET ";
	$qry1 .= "masid='".trim($_REQUEST['usr_masid'])."',mas_div='".trim($_REQUEST['usr_mas_div'])."',rmasid='".trim($_REQUEST['usr_rmasid'])."',rmas_div='".trim($_REQUEST['usr_rmas_div'])."',";
	$qry1 .= "mas_prid='".$_REQUEST['usr_mas_prid']."',mas_office='".$_REQUEST['usr_mas_office']."' ";
	$qry1 .= "WHERE securityid=".$sid.";";
	$res1  = mssql_query($qry1);
	
	get_MASAccountingInfo($sid);
}

?>