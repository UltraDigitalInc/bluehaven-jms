<?php
session_start();

if (!isset($_REQUEST['foid']) || !isset($_REQUEST['d1']) || !isset($_REQUEST['d2']) || !isset($_SESSION['securityid']))
{
   exit;
}

/*
if ($_SESSION['securityid']!=26)
{
   echo 'Financial Status Offline.';
   exit;
}
*/


if (isset($_REQUEST['textonly']) and $_REQUEST['textonly']==1)
{
   header("Content-type: application/text"); 
   header("Content-Disposition: attachment; filename=fnexport_".date("mdY").".txt");  
}
else
{
   header("Content-type: application/vnd.ms-excel");
   header("Content-Disposition: ".$_REQUEST['disp']."; filename=fnstatus_".date("m-d-Y").".xls");
}

header("Pragma: no-cache"); 
header("Expires: 0");

include('../connect_db.php');

function removespec($data)
{
	$out=str_replace(",","",$data);
	$out=str_replace("'","",$data);
	$out=str_replace("\t","",$data);
	$out=str_replace("\r","",$data);
	$out=str_replace("\n","",$data);
	$out=str_replace("\015\012","",$data);
	return $out;
}
	
	$qry0 = "SELECT officeid,name,finan_off,finan_from FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	
	$qry1 = "SELECT name FROM offices WHERE officeid='".$_REQUEST['foid']."';";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	
	$qry2 = "SELECT finan_off,finan_from FROM offices WHERE officeid='".$_REQUEST['oid']."';";
	$res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
	
	$qry2 = "SELECT * FROM offices WHERE finan_from='".$_SESSION['officeid']."' order by name ASC;";
	$res2 = mssql_query($qry2);
	
	if ($_REQUEST['iactive']==0)
	{
		$iactivetxt	="Yearly";
	}
	else
	{
		$iactivetxt	="Active";
	}
	
	$qry  = "SELECT ";
	$qry .= "	C.cid, ";
	$qry .= "	C.securityid, ";
	$qry .= "	C.clname, ";
	$qry .= "	C.cfname, ";
	$qry .= "	C.ccity, ";
	$qry .= "	C.scity, ";
	$qry .= "	C.finan_src, ";
	$qry .= "	C.estid, ";
	$qry .= "	C.jobid, ";
	$qry .= "	C.njobid, ";
	$qry .= "	F.*, ";
	$qry .= "	(SELECT name FROM offices WHERE officeid=F.officeid) as rfoid, ";
	$qry .= "	(SELECT lenderabbrev FROM tlender WHERE lid=F.lender) as rlndnm, ";
	$qry .= "	(SELECT rcode FROM tfinanresultcodes WHERE rid=F.reasnotclosed) as rsm, ";
	$qry .= "	(SELECT lname FROM security WHERE securityid=C.securityid) as sname, ";
    $qry .= "	(select lname from security where securityid=F.assigned) as aslname, ";
	$qry .= "	(SELECT contractamt FROM jdetail WHERE officeid=C.officeid AND jobid=C.jobid AND jadd=0) as rctamt, ";
	$qry .= "	(SELECT contractdate FROM jdetail WHERE officeid=C.officeid AND jobid=C.jobid AND jadd=0) as rctdt, ";
    $qry .= "	(select lenderabbrev from tlender where lid=F.lender) as lender, ";
	$qry .= "	(SELECT digdate FROM jobs WHERE officeid=C.officeid AND jobid=C.jobid) as rdgdt ";
	$qry .= "FROM ";
	$qry .= "	cinfo as C ";
	$qry .= "INNER JOIN ";
	$qry .= "	tfinan_detail as F ";
	$qry .= "ON ";
	$qry .= "	C.cid=F.cid ";
	$qry .= "WHERE ";
	$qry .= "	F.inclstatreport='".$_REQUEST['iactive']."' ";
	
	if ($_SESSION['rlev'] < 5 && $_SESSION['llev'] < 5 && $row2['finan_off']==0)
	{
      $qry .= "	and F.closer='".$_SESSION['securityid']."' ";	
	}
    
    if ($row0['finan_off']==1)
	{
      if (isset($_REQUEST['assigned']) && $_REQUEST['assigned']!=0)
      {
         $qry .= "	and F.assigned='".$_REQUEST['assigned']."' ";
      }
	}
	
	if ($_REQUEST['finansrc']!=0)
	{
		$qry .= "	and C.finan_src='".$_REQUEST['finansrc']."' ";
	}
   
   if ($_REQUEST['lientype']!=0)
	{
		$qry .= "	and F.lientype='".$_REQUEST['lientype']."' ";
	}
	
	$qry .= "	and ".$_REQUEST['field']." LIKE '".$_REQUEST['ssearch']."%' ";
	
	if (!empty($_REQUEST['oid']) && $_REQUEST['oid']!=0)
	{
		$qry .= "	and C.officeid='".$_REQUEST['oid']."' ";	
	}
	else
	{
		if ($row0['finan_off']==1)
		{
			$qry .= "	and C.finan_from='".$_SESSION['officeid']."' ";
		}
		else
		{
			$qry .= "	and C.officeid='".$_SESSION['officeid']."' ";
		}
	}
	
	if (!empty($_REQUEST['d1']) && !empty($_REQUEST['d2']))
	{
		$qry .= "	and F.recdate >='".$_REQUEST['d1']."' ";
		$qry .= "	and F.recdate <='".$_REQUEST['d2']." 11:59:59' ";
		$dtext="Date Range: ".date("m/d/y",strtotime($_REQUEST['d1']))." - ".date("m/d/y",strtotime($_REQUEST['d2']));
	}
	else
	{
		$dtext="";
	}
	
	$qry .= "ORDER BY ";
   
   if (!empty($_REQUEST['group']) && strlen($_REQUEST['group']) >= 4)
   {
      $qry .= "	".$_REQUEST['group']." ".$_REQUEST['ascdesc1'].", ";     
   }
   
	$qry .= " ".$_REQUEST['order']." ".$_REQUEST['ascdesc2'].";";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
   $csv_output   = "
            <table border=\"1\">
					<tr>
						<td align=\"left\" colspan=\"8\"><b>".$iactivetxt." Status Report: ".$row0['name']."</td>
                        <td align=\"left\" colspan=\"4\"><b>Generated on: ".date('m/d/Y',time())."</b></td>
					</tr>
					<tr>
						<td align=\"left\"><b>Last Name</b></td>
                        <td align=\"left\"><b>First Name</b></td>
						<td align=\"left\"><b>City</b></td>
						<td align=\"left\"><b>Sales Rep</b></td>
						<td align=\"center\"><b>Contr Date</b></td>
						<td align=\"center\"><b>Finan Rec'd</b></td>
						<td align=\"center\"><b>Apprv Date</b></td>
						<td align=\"center\"><b>Ctr Amt</b></td>
						<td align=\"center\"><b>Fin Amt</b></td>
						<td align=\"center\"><b>Cls Dt</b></td>
						<td align=\"center\"><b>Dig Dt</b></td>
                        <td align=\"center\"><b>Comments</b></td>
					</tr>
   ";
   
	if ($nrow > 0)
	{
		$rcnt=0;
		while ($row = mssql_fetch_array($res))
		{
		 //echo $row['cid']."\n";
			$rcnt++;
			
			if (!empty($row['rctdt']) && strtotime($row['rctdt']) > strtotime('1/1/1980'))
			{
				$rcdt=date("m/d/y",strtotime($row['rctdt']));
			}
			else
			{
				$rcdt="";
			}
			
			if (!empty($row['frecdate']) && strtotime($row['frecdate']) > strtotime('1/1/1980'))
			{
				$frecdate=date("m/d/y",strtotime($row['frecdate']));
			}
			else
			{
				$frecdate="";
			}
			
			if (!empty($row['dateapprove']) && strtotime($row['dateapprove']) > strtotime('1/1/1980'))
			{
				$dateapprove=date("m/d/y",strtotime($row['dateapprove']));
			}
			else
			{
				$dateapprove="";
			}
			
			if (!empty($row['closedate']) && strtotime($row['closedate']) > strtotime('1/1/1980'))
			{
				$closedate=date("m/d/y",strtotime($row['closedate']));
			}
			else
			{
				$closedate="";
			}
			
			if (!empty($row['rdgdt']) && strtotime($row['rdgdt']) > strtotime('1/1/1980'))
			{
				$rdgdt=date("m/d/y",strtotime($row['rdgdt']));
			}
			else
			{
				$rdgdt="";
			}
			
			$csv_output   .= "      <tr>";
			//$csv_output   .= "					<td align=\"right\"><b>".$rcnt.".</b></td>";
			$csv_output   .= "					<td align=\"left\" valign=\"top\">".$row['clname']."</td>";
            $csv_output   .= "					<td align=\"left\" valign=\"top\">".$row['cfname']."</td>";
			
			if (!empty($row['scity']) && strlen($row['scity']) >= 2)
			{
				$csv_output   .= "					<td align=\"left\" valign=\"top\">".$row['scity']."</td>";
			}
			else
			{
				$csv_output   .= "					<td align=\"left\" valign=\"top\">".$row['ccity']."</td>";
			}
			
			$csv_output   .= "					<td align=\"left\" valign=\"top\">".$row['sname']."</td>";
			$csv_output   .= "					<td align=\"center\" valign=\"top\">".$rcdt."</td>";
			$csv_output   .= "					<td align=\"center\" valign=\"top\">".$frecdate."</td>";            
			$csv_output   .= "					<td align=\"center\" valign=\"top\">".$dateapprove."</td>";
			$csv_output   .= "					<td align=\"right\" valign=\"top\">".number_format($row['rctamt'])."</td>";
			$csv_output   .= "					<td align=\"right\" valign=\"top\">".number_format($row['amtfinan'])."</td>";
			$csv_output   .= "					<td align=\"center\" valign=\"top\">".$closedate."</td>";
			$csv_output   .= "					<td align=\"center\" valign=\"top\">".$rdgdt."</td>";
            $csv_output   .= "				   <td align=\"left\">";
			
			if (!empty($_REQUEST['comment']) && is_array($_REQUEST['comment']))
			{
				foreach ($_REQUEST['comment'] as $cn => $cv)
				{
					if ($cv=="f")
					{
						$csv_output   .= $row['fcomment'];
					}
					elseif ($cv=="i")
					{
                  if (isset($_REQUEST['dlimit']) && $_REQUEST['dlimit']==0)
                  {
                     $qry0 = "SELECT adate,mbody FROM tfinanicomments WHERE cid='".$row['cid']."' order by adate desc;";
                  }
                  else
                  {
                     $qry0 = "SELECT TOP ".$_REQUEST['dlimit']." adate,mbody FROM tfinanicomments WHERE cid='".$row['cid']."' order by adate desc;";
                  }
                  
                  $res0 = mssql_query($qry0);
                  $nrow0= mssql_num_rows($res0);
                  
                  if ($nrow0 > 0)
                  {	
                     while ($row0= mssql_fetch_array($res0))
                     {
                        $csv_output   .= " ".date("m/d/y",strtotime($row0['adate']))." - I - ".$row0['mbody']."<br>";
                     }
                  }
					}
               elseif ($cv=="e")
					{
                  if (isset($_REQUEST['dlimit']) && $_REQUEST['dlimit']==0)
                  {
                     $qry0 = "SELECT mdate,mtext FROM chistory WHERE custid='".$row['cid']."' and act!='cresp' order by mdate desc;";
                  }
                  else
                  {
                     $qry0 = "SELECT TOP ".$_REQUEST['dlimit']." mdate,mtext FROM chistory WHERE custid='".$row['cid']."' and act!='cresp' order by mdate desc;";
                  }

                  $res0 = mssql_query($qry0);
                  $nrow0= mssql_num_rows($res0);
                           
                  if ($nrow0 > 0)
                  {	
                     while ($row0= mssql_fetch_array($res0))
                     {
                        $csv_output   .= " ".date("m/d/y",strtotime($row0['mdate']))." - E - ".$row0['mtext']."<br>";
                     }
                  }
					}
				}
			}
         
         $csv_output   .= "				   </td>";
         $csv_output   .= "				</tr>";
		}
	}

$csv_output  .= "
   </table>
   ";
print $csv_output;
exit;

?>
