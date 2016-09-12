<?php

function date_range($sd,$ed)
{
   // Input: Start Date & End Date (Text)
   // Output (Array):
	//		Text Date Range (Array), Digital Date Range (Array), Date Range Count (Int)
   
   $vout	=array(); // Output
   $txt_ar	=array(); // Textual Dates
   $dig_ar	=array(); // Digital Dates
   $s			=strtotime($sd); // Start
   $e			=strtotime($ed); // End
   
   if ($e >= $s)
   {
      array_push($txt_ar,date('m/d/Y', $s));
      while ($s < $e)
      {
         $s+=86400;
         array_push($txt_ar,date('m/d/Y', $s));
			array_push($dig_ar,$s);
      }
   }
   
   $vout =array($txt_ar,$dig_ar,count($txt_ar));
   return $vout;
}

function disp_mas_div_jobid($div,$id)
{
	$comp=0;
	if (strlen($div) > 2)
	{
		$ndiv=0;
		$comp++;
	}
	elseif (strlen($div)==1)
	{
		$ndiv=str_pad($div, 2, "0", STR_PAD_LEFT);
	}
	else
	{
		//$ndiv=$div."-";
		$ndiv=$div;
	}

	if ($id==0 || strlen($id) > 6)
	{
		//$nid=" INCOMP";
		$nid=$id;
		$comp++;
	}
	elseif (strlen($id) == 6)
	{
		if (strpos($id,1)==0)
		{
			$nid=substr($id, -5);
		}
		else
		{
			//$nid=" INCOMP";
			$nid=$id;
			$comp++;
		}
	}
	elseif (strlen($id) == 5)
	{
		$nid=$id;
	}
	else
	{
		$nid=str_pad($id, 5, "0", STR_PAD_LEFT);
	}

	$sjid=array($ndiv.$nid,$comp);
	return $sjid;
}

function view_EmailTemplate_RO()
{
    $qry0 = "
            select
                E.*
                ,(select lname from security where securityid=E.aid) as aidlname
                ,(select fname from security where securityid=E.aid) as aidfname
                ,(select lname from security where securityid=E.uid) as uidlname
                ,(select fname from security where securityid=E.uid) as uidfname
            from
                EmailTemplate as E
            where
                E.etid=".$_REQUEST['etid'].";
            ";
    $res0 = mssql_query($qry0);
    $row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 0)
    {       
        echo "          <table class=\"outer\" width=\"100%\">\n";
        echo "              <tr>\n";
        echo "                  <td class=\"gray\" align=\"right\" valign=\"top\"><b>Template Name</b></td>\n";
        echo "                  <td class=\"gray\" align=\"left\">".trim($row0['name'])."</td>\n";
        echo "              </tr>\n";
        echo "              <tr>\n";
        echo "                  <td class=\"gray\" align=\"right\" valign=\"top\"><b>Subject</b></td>\n";
        echo "                  <td class=\"gray\" align=\"left\">".trim($row0['esubject'])."</td>\n";
        echo "              </tr>\n";
        echo "              <tr>\n";
        echo "                  <td class=\"gray\" align=\"right\" valign=\"top\"><b>Body</b></td>\n";
        echo "                  <td class=\"gray\" align=\"left\">".trim($row0['ebody'])."\n";
        echo "              </tr>\n";
        echo "          </table>\n";
        
    }
}

function drill_view_ds_salesrepold()
{
	if (!isset($_GET['sid']) || !isset($_GET['ssid']) || !isset($_GET['byr']))
	{
		echo "Invalid Request";
		exit;
	}
	
	$hostname = "192.168.100.45";
	$username = "jestadmin";
	$password = "into99black";
	$dbname   = "jest";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to Database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
   
   $qryA = "SELECT securityid,officeid,fname,lname,altid FROM security WHERE securityid='".$_GET['ssid']."';";
   $resA = mssql_query($qryA);
   $rowA = mssql_fetch_array($resA);
   
   
   
   $qryAa = "SELECT * FROM secondaryids WHERE securityid='".$_GET['ssid']."';";
   $resAa = mssql_query($qryAa);
   $nrowAa= mssql_num_rows($resAa);
   
   //echo $nrowAa."<br>";
   
   if ($nrowAa == 0)
   {
      $qryB = "SELECT officeid,rept_mo,rept_yr,no_digs,jtext FROM digreport_main WHERE officeid='".$rowA['officeid']."' and brept_yr='".$_GET['byr']."';";
      $resB = mssql_query($qryB);
      $nrowB= mssql_num_rows($resB);
      
      //echo $nrowB."<br>";
      if ($nrowB > 0)
      {
         echo "<table width=\"100%\">\n";
			echo "	<tr>\n";
			echo "		<td class=\"gray\">\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td align=\"left\" valign=\"bottom\"><b>Registered Job History</b></td><td align=\"right\" valign=\"bottom\"><b>".$_GET['byr']." Season</b></td>\n";
			echo "				</tr>\n";
         echo "				<tr>\n";
			echo "			   	<td colspan=\"2\" align=\"left\" valign=\"bottom\"><b>Sales Rep:</b> ".$rowA['lname'].", ".$rowA['fname']."</td>\n";
			echo "			   </tr>\n";
			echo "				<tr>\n";
			echo "					<td colspan=\"2\" align=\"left\">\n";
			echo "						<table width=\"100%\">\n";
			
         $jcnt=1;
         $tsales=0;
         while ($rowB = mssql_fetch_array($resB))
         {
            $ij=explode(",",$rowB['jtext']);
            
            echo "					<tr>\n";
            echo "					   <td class=\"yellow\" align=\"center\"></td>\n";
            echo "					   <td class=\"yellow\" colspan=\"5\" align=\"left\"><b>".$rowB['rept_mo']." / ".$rowB['rept_yr']."</b></td>\n";
            echo "			      </tr>\n";
            echo "					<tr>\n";
            echo "					   <td class=\"ltgray_und\" align=\"center\"></td>\n";
            echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Job #</b></td>\n";
            echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Customer</b></td>\n";
            echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Contract Date</b></td>\n";
            echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Dig Date</b></td>\n";
            echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Contract Amt</b></td>\n";
            echo "			      </tr>\n";
            
            //if (count($ij) > 0)
            //{
               foreach ($ij as $ijn => $ijv)
               {
                  $iijv=explode(":",$ijv);
                  if ($iijv[8]==$rowA['securityid'])
                  {
                     //$jcnt++;
                     echo "					<tr>\n";
                     echo "					   <td class=\"wh_und\" align=\"center\">".$jcnt++."</td>\n";
                     echo "					   <td class=\"wh_und\" align=\"left\">\n";
                     echo $iijv[0];
                     echo "					   </td>\n";
                     echo "					   <td class=\"wh_und\" align=\"left\">\n";
                     echo $iijv[9];
                     echo "					   </td>\n";
                     echo "					   <td class=\"wh_und\" align=\"center\">\n";
                     echo $iijv[7];
                     echo "					   </td>\n";
                     echo "					   <td class=\"wh_und\" align=\"center\">\n";
                     echo $iijv[6];
                     echo "					   </td>\n";
                     echo "					   <td class=\"wh_und\" align=\"right\">\n";
                     echo number_format($iijv[2],2);
                     echo "					   </td>\n";
                     echo "			      </tr>\n";
                     $tsales=$tsales+$iijv[2];
                  }
               }
            //}
         }
         
         echo "					<tr>\n";
         echo "					   <td class=\"ltgray_und\" colspan=\"5\" align=\"right\"><b>Total</b></td>\n";
         echo "					   <td class=\"ltgray_und\" align=\"right\">\n";
         echo number_format($tsales,2);
         echo "					   </td>\n";
         echo "			      </tr>\n";
         echo "				</table>\n";
      }
      else
      {
         echo "No Dig Reports stored";
         exit;
      }
   }
   /*
   elseif ($nrowAa >= 1)
   {
      while ($rowAa = mssql_fetch_array($resAa))
      {
         $qryB = "SELECT * FROM digreport_main WHERE officeid='".$_GET['oid']."' and ;";
         $resB = mssql_query($qryB);
         $rowB = mssql_fetch_array($resB);
         
         
      }
   }
   */
}

function drill_view_ds_salesrep()
{
	if (!isset($_GET['sid']) || !isset($_GET['ssid']) || !isset($_GET['byr']))
	{
		echo "Invalid Request";
		exit;
	}
	
   //$hostname = "192.168.1.59";
	$hostname = "192.168.100.45";
	$username = "jestadmin";
	$password = "into99black";
	$dbname   = "jest";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to Database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
   
   $secids=array();
   
   $qryA = "SELECT securityid,officeid,fname,lname,altid FROM security WHERE securityid='".$_GET['ssid']."';";
   $resA = mssql_query($qryA);
   $rowA = mssql_fetch_array($resA);
   
   $qryB = "SELECT securityid,officeid,fname,lname,altid,digstandingrpt FROM security WHERE securityid=".$_SESSION['securityid'].";";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_array($resB);
   
   if ($rowB['digstandingrpt'] < 9)
   {
      echo "You are not authorized to view this information.";
      exit;
   }
   
   $secids[]=$rowA['securityid'];
   
   $qryAa = "SELECT * FROM secondaryids WHERE securityid='".$_GET['ssid']."';";
   $resAa = mssql_query($qryAa);
   $nrowAa= mssql_num_rows($resAa);
   
   while ($rowAa = mssql_fetch_array($resAa))
   {
      $secids[]=$rowAa['secid'];
   }
   
   //echo "ID: ".count($secids)."<br>";
   if (count($secids) > 0)
   {
      echo "<table width=\"100%\">\n";
      echo "	<tr>\n";
      echo "		<td>\n";
      echo "			<table class=\"outer\" width=\"100%\">\n";
      echo "				<tr>\n";
      echo "					<td align=\"left\" valign=\"bottom\"><b>Registered Job History</b></td><td align=\"right\" valign=\"bottom\"><b>".$_GET['byr']." Season</b></td>\n";
      echo "				</tr>\n";
      echo "				<tr>\n";
      echo "			   	<td colspan=\"2\" align=\"left\" valign=\"bottom\"><b>Sales Rep:</b> ".$rowA['lname'].", ".$rowA['fname']."</td>\n";
      echo "			   </tr>\n";
      echo "				<tr>\n";
      echo "					<td colspan=\"2\" align=\"left\">\n";
      echo "						<table width=\"100%\">\n";
      echo "					<tr>\n";
      echo "					   <td class=\"ltgray_und\" align=\"center\"></td>\n";
      echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Job #</b></td>\n";
      echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Office</b></td>\n";
      echo "					   <td class=\"ltgray_und\" align=\"center\"><b>MO/YR</b></td>\n";
      echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Customer</b></td>\n";
      echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Contract Date</b></td>\n";
      echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Dig Date</b></td>\n";
      echo "					   <td class=\"ltgray_und\" align=\"center\"><b>Contract Amt</b></td>\n";
      echo "			      </tr>\n";
      
      $jcnt=1;
      $tsales=0;      
      foreach ($secids as $sn => $sv)
      {
         $qryAc = "SELECT securityid,officeid,fname,lname,altid FROM security WHERE securityid='".$sv."';";
         $resAc = mssql_query($qryAc);
         $rowAc = mssql_fetch_array($resAc);
         
         //echo $qryAc."<br>";
         
         $qryB = "SELECT officeid,rept_mo,rept_yr,no_digs,jtext,(select name from offices where officeid=dr.officeid) as name FROM digreport_main as dr WHERE officeid='".$rowAc['officeid']."' and brept_yr='".$_GET['byr']."' and no_digs > 0;";
         $resB = mssql_query($qryB);
         $nrowB= mssql_num_rows($resB);
         
         //echo $qryB."<br>";
         if ($nrowB > 0)
         {
            while ($rowB = mssql_fetch_array($resB))
            {
               $ij=explode(",",$rowB['jtext']);
               foreach ($ij as $ijn => $ijv)
               {
                  $iijv=explode(":",$ijv);
                  if ($iijv[8]==$sv)
                  {
					 if (isset($iijv[20]) && $iijv[20] > 0)
                     {
                     }
                     else
                     {
						//$jcnt++;
						echo "					<tr>\n";
						echo "					   <td class=\"wh_undsidesr\" align=\"center\">".$jcnt++.".</td>\n";
						echo "					   <td class=\"wh_undsidesr\" align=\"center\">\n";
						echo $iijv[0];
						echo "					   </td>\n";
						echo "					   <td class=\"wh_undsidesr\" align=\"center\">\n";
						echo $rowB['name'];
						echo "					   </td>\n";
						echo "					   <td class=\"wh_undsidesr\" align=\"center\">\n";
						echo $rowB['rept_mo']." / ".$rowB['rept_yr'];
						echo "					   </td>\n";
						echo "					   <td class=\"wh_undsidesr\" align=\"left\">\n";
						echo $iijv[9];
						echo "					   </td>\n";
						echo "					   <td class=\"wh_undsidesr\" align=\"center\">\n";
						echo $iijv[7];
						echo "					   </td>\n";
						echo "					   <td class=\"wh_undsidesr\" align=\"center\">\n";
						echo $iijv[6];
						echo "					   </td>\n";
						echo "					   <td class=\"wh_und\" align=\"right\">\n";
						echo number_format($iijv[2],2);
						echo "					   </td>\n";
						echo "			      </tr>\n";
						$tsales=$tsales+$iijv[2];
					 }
                  }
               }
            }
         }
         //else
         //{
         //   echo "No Dig Reports stored";
         //   exit;
         //}
      }
      
      echo "					<tr>\n";
      echo "					   <td class=\"ltgray_und\" colspan=\"7\" align=\"right\"><b>Total</b></td>\n";
      echo "					   <td class=\"ltgray_und\" align=\"right\">\n";
      echo number_format($tsales,2);
      echo "					   </td>\n";
      echo "			      </tr>\n";
      echo "				</table>\n";
   }
}

function drill_bid_add()
{
   //echo "Bid Add<br>";
   error_reporting(E_ALL);
   $hostname = "192.168.100.45";
   $username = "jestadmin";
   $password = "into99black";
   $dbname   = "jest";

   mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to Database", E_USER_ERROR);
   mssql_select_db($dbname) or die("Table unavailable");
   
   $MAS=$_GET['pb_code'];
   
   $uid = md5(session_id().time()).".".$_SESSION['securityid'];
   
   $qry = "SELECT stax,pb_code,accountingsystem FROM offices WHERE officeid='".$_GET['officeid']."';";
   $res = mssql_query($qry);
   $row = mssql_fetch_array($res);
   
   //echo $qry;

   if ($_GET['action']=="est")
   {
	  $qryA = "SELECT cid,securityid FROM cinfo WHERE officeid='".$_GET['officeid']."' AND cid='".$_GET['cid']."';";
   }
   elseif ($_GET['action']=="contract")
   {
	  $qryA  =  "SELECT  ";
	  $qryA .= "	c.cid, ";
	  $qryA .= "	j1.officeid, ";
	  $qryA .= "	j1.securityid, ";
	  $qryA .= "	j1.renov  ";
	  $qryA .= "FROM  ";
	  $qryA .= "	jobs as j1 ";
	  $qryA .= "INNER JOIN ";
	  $qryA .= "	cinfo as c ";
	  $qryA .= "ON ";
	  $qryA .= "	j1.jobid= ";
	  $qryA .= "	c.jobid AND ";
	  $qryA .= "	j1.officeid= ";
	  $qryA .= "	c.officeid ";
	  $qryA .= "WHERE  ";
	  $qryA .= "	j1.officeid='".$_GET['officeid']."' AND  ";
	  $qryA .= "	j1.jobid='".$_GET['jid']."'; ";
   }
   elseif ($_GET['action']=="job")
   {
	  $qryA  =  "SELECT  ";
	  $qryA .= "	c.cid, ";
	  $qryA .= "	j1.officeid, ";
	  $qryA .= "	j1.securityid, ";
	  $qryA .= "	j1.renov  ";
	  $qryA .= "FROM  ";
	  $qryA .= "	jobs as j1 ";
	  $qryA .= "INNER JOIN ";
	  $qryA .= "	cinfo as c ";
	  $qryA .= "ON ";
	  $qryA .= "	j1.njobid= ";
	  $qryA .= "	c.njobid AND ";
	  $qryA .= "	j1.officeid= ";
	  $qryA .= "	c.officeid ";
	  $qryA .= "WHERE  ";
	  $qryA .= "	j1.officeid='".$_GET['officeid']."' AND  ";
	  $qryA .= "	j1.njobid='".$_GET['jid']."'; ";
   }

   $resA = mssql_query($qryA);
   $rowA = mssql_fetch_array($resA);

   $qryB = "SELECT * FROM cinfo WHERE officeid='".$_GET['officeid']."' AND cid='".$rowA['cid']."';";
   $resB = mssql_query($qryB);
   $rowB = mssql_fetch_array($resB);

   $qryC = "SELECT * FROM [".$row['pb_code']."acc] WHERE officeid='".$_GET['officeid']."' AND id='".$_GET['rdbid']."';";
   $resC = mssql_query($qryC);
   $rowC = mssql_fetch_array($resC);
   
   $qryCa = "SELECT mas_div,rmas_div FROM security WHERE securityid='".$rowA['securityid']."';";
   $resCa = mssql_query($qryCa);
   $rowCa = mssql_fetch_array($resCa);
   
   if ($row['accountingsystem']==2)
   {
	  $qryCb = "
		 if object_id('tempdb..#tbidphsacc') is not null
			begin
				drop table #tbidphsacc
			end
			
		 if object_id('tempdb..#tbidphsinv') is not null
			begin
				drop table #tbidphsinv
			end
			
		 select
			distinct(phsid)
		into #tbidphsacc
		from
			[".$row['pb_code']."accpbook] AS A
		where
			qtype=80
		
		select
			distinct(phsid)
		into #tbidphsinv
		from
			[".$row['pb_code']."inventory] AS A
		where
			qtype=80
		
		SELECT 
			P.phsid,P.phscode,P.phsname 
		FROM 
			phasebase AS P
		WHERE 
			P.costing=1 and P.phsid!=3 and P.phsid!=5
			and (
				P.phsid in (select phsid from #tbidphsacc)
				or P.phsid in (select phsid from #tbidphsinv)
			)
		order by 
			P.seqnum 
		asc;
	  ";
   }
   else
   {
	  $qryCb = "SELECT phsid,phscode,phsname FROM phasebase WHERE costing=1 and phsid!=3 and phsid!=5 order by seqnum asc;";  
   }
   $resCb = mssql_query($qryCb);
   $nrowCb =mssql_num_rows($resCb);
   
   //echo $qryCb."<br>";
   
   if ($_GET['action']=="est")
   {
	   $qryD = "SELECT * FROM est_bids WHERE officeid='".$_GET['officeid']."' AND estid='".$_GET['jid']."' AND bidaccid='".$_GET['rdbid']."';";
   }
   elseif ($_GET['action']=="contract")
   {
	   $qryD = "SELECT * FROM bid_breakout WHERE officeid='".$_GET['officeid']."' AND jobid='".$_GET['jid']."' AND jadd='".$_GET['jadd']."' AND cdbid='".$_GET['cdbid']."' AND rdbid='".$_GET['rdbid']."';";
   }
   elseif ($_GET['action']=="job")
   {
	   $qryD = "SELECT * FROM jbids_breakout WHERE officeid='".$_GET['officeid']."' AND njobid='".$_GET['jid']."' AND jadd='".$_GET['jadd']."' AND cdbid='".$_GET['cdbid']."' AND rdbid='".$_GET['rdbid']."';";
   }
   $resD = mssql_query($qryD);
   $nrowD= mssql_num_rows($resD);

   //echo $qryD."<br>";

   if ($_GET['action']=="job")
   {
	  if ($rowA['renov']==1 && $rowCa['rmas_div']!=0)
	  {
		 $disp_jid=disp_mas_div_jobid($rowCa['rmas_div'],$_GET['jid']);
	  }
	  else
	  {
		 $disp_jid=disp_mas_div_jobid($rowCa['mas_div'],$_GET['jid']);
	  }
   }
   else
   {
	  $disp_jid[]=$_GET['jid'];
   }

   echo "<table width=\"250px\">\n";
   echo "	<tr>\n";
   echo "		<td>\n";
   echo "			<table class=\"outer\" width=\"100%\">\n";
   echo "				<tr>\n";
   echo "					<td class=\"gray\" align=\"left\">\n";
   echo "						<table>\n";
   echo "							<tr>\n";
   echo "								<td align=\"right\" valign=\"bottom\" width=\"125px\"><b>Retail Bid Item:</b></td>\n";
   echo "								<td align=\"left\" valign=\"bottom\">".$rowC['item']."</td>\n";
   echo "							</tr>\n";
   echo "						</table>\n";
   echo "					</td>\n";
   echo "				</tr>\n";
   echo "			</table>\n";
   echo "		</td>\n";
   echo "	</tr>\n";
   echo "	<tr>\n";
   echo "		<td>\n";
   echo "			<table class=\"outer\" width=\"100%\">\n";
   echo "				<tr>\n";
   echo "					<td align=\"left\">\n";
   
   if ($nrowCb > 0)
   {
	  echo "						<form id=\"BidCostAddForm\" action=\"http://jms.bhnmi.com/index.php\" method=\"post\">\n";
	  echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_GET['officeid']."\">\n";
	  echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_GET['sid']."\">\n";
	  echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowA['cid']."\">\n";
	  echo "						<input type=\"hidden\" name=\"rdbid\" value=\"".$_GET['rdbid']."\">\n";
	  echo "						<input type=\"hidden\" name=\"cdbid\" value=\"".$_GET['costid']."\">\n";
	  echo "						<input type=\"hidden\" name=\"costid\" value=\"".$_GET['costid']."\">\n";
	  echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	  echo "						<input type=\"hidden\" name=\"call\" value=\"edit_bid_jobmode_add\">\n";
   
	  if ($_GET['action']=="est")
	  {
		  echo "							<input type=\"hidden\" name=\"jid\" value=\"".$_GET['jid']."\">\n";
		  echo "							<input type=\"hidden\" name=\"estid\" value=\"".$_GET['jid']."\">\n";
		  echo "							<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	  }
	  elseif ($_GET['action']=="contract")
	  {
		  echo "							<input type=\"hidden\" name=\"jobid\" value=\"".$_GET['jid']."\">\n";
		  echo "							<input type=\"hidden\" name=\"jid\" value=\"".$_GET['jid']."\">\n";
		  echo "							<input type=\"hidden\" name=\"jadd\" value=\"".$_GET['jadd']."\">\n";
		  echo "							<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	  }
	  elseif ($_GET['action']=="job")
	  {
		  echo "							<input type=\"hidden\" name=\"njobid\" value=\"".$_GET['jid']."\">\n";
		  echo "							<input type=\"hidden\" name=\"jid\" value=\"".$_GET['jid']."\">\n";
		  echo "							<input type=\"hidden\" name=\"jadd\" value=\"".$_GET['jadd']."\">\n";
		  echo "							<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	  }
	  
	  echo "						<table class=\"gray\" width=\"100%\">\n";
   }
   else
   {
	  echo "						<table class=\"gray\" width=\"100%\">\n";
	  echo "							<tr>\n";
	  echo "								<td colspan=\"2\" align=\"left\">ERROR! Your office has no Bid Cost Items. Contact Management to Setup BID Cost Items.</td>\n";
	  echo "							</tr>\n";
   }
   
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"125px\"><b>Phase:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
   
   if ($nrowCb > 0)
   {
	  echo "									<select name=\"phsid\">\n";
	  
	  while ($rowCb = mssql_fetch_array($resCb))
	  {
		 echo "<option value=\"".$rowCb['phsid']."\">(".$rowCb['phscode'].") ".$rowCb['phsname']."</option>\n";
	  }
	  
	  echo "									</select>\n";
   }
   
   echo "								</td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"125px\"><b>Item Name:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" name=\"sdesc\" size=\"20\"></td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"125px\"><b>Description:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><textarea name=\"comments\" cols=\"30\" rows=\"2\"></textarea></td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"125px\"><b>Vendor:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" name=\"vendor\" size=\"20\"></td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"125px\"><b>Part #:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" name=\"partno\" size=\"20\"></td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"125px\"><b>Cost:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" name=\"bprice\" size=\"20\"></td>\n";
   echo "							</tr>\n";
   
   if ($nrowCb > 0)
   {
	  //echo "							<tr>\n";
	  //echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\" colspan=\"2\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add\"></td>\n";
	  //echo "							</tr>\n";
   }
   
   echo "						</table>\n";
   
   if ($nrowCb > 0)
   {
	  echo "						</form>\n";
   }
   
   echo "					</td>\n";
   echo "				</tr>\n";
   echo "			</table>\n";
   echo "		</td>\n";
   echo "	</tr>\n";
   echo "</table>\n";   
}

function drill_view_bid_cost()
{
	//echo "Bid Add<br>";
	
	if (!isset($_GET['sid']) || !isset($_GET['oid']) || !isset($_GET['jid']) || !isset($_GET['rdbid']))
	{
		echo "Invalid Request";
		exit;
	}
	
	$MAS=$_GET['pb_code'];
	
   //$hostname = "192.168.1.59";
	$hostname = "192.168.100.45";
	$username = "jestadmin";
	$password = "into99black";
	$dbname   = "jest";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to Database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
	$qry = "SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($_SESSION['action']=="est")
	{
		$qryAa = "SELECT cid as custid,estid,securityid,renov FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_GET['jid']."';";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qryAa = "SELECT custid as custid,jobid,securityid,renov FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_GET['jid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryAa = "SELECT custid as custid,njobid,securityid,renov FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_GET['jid']."';";
	}

	//echo $qryAa."<br>";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);
	$nrowAa= mssql_num_rows($resAa);

	if ($nrowAa >= 1)
	{
		if ($_SESSION['action']=="est")
		{
			$qryAb = "SELECT * FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_GET['jid']."' AND bidaccid='".$_GET['rdbid']."';";
		}
		elseif ($_SESSION['action']=="contract")
		{
			$qryAb = "SELECT * FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_GET['jid']."' AND dbid='".$_GET['rdbid']."' order by jadd asc;";
		}
		elseif ($_SESSION['action']=="job")
		{
			$qryAb = "SELECT * FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_GET['jid']."' AND dbid='".$_GET['rdbid']."' order by jadd asc;";
		}
	
		//echo $qryAb."<br>";
	
		$resAb = mssql_query($qryAb);
		$rowAb = mssql_fetch_array($resAb);
		$nrowAb= mssql_num_rows($resAb);
		
		if ($nrowAb > 0)
		{
			$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rowAa['custid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
		
			//echo $qryB."<br>";
			
			$qryC = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_GET['rdbid']."';";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);
	
			$qryCa = "SELECT mas_div,rmas_div FROM security WHERE securityid='".$rowAa['securityid']."';";
			$resCa = mssql_query($qryCa);
			$rowCa = mssql_fetch_array($resCa);
			
			$qryCb = "SELECT phsid,phscode,phsname FROM phasebase WHERE costing=1 order by seqnum asc;";
			$resCb = mssql_query($qryCb);
			
			//echo $qryCb."<br>";
		
			if ($_SESSION['action']=="job")
			{
				if ($rowAa['renov']==1 && $rowCa['rmas_div']!=0)
				{
					$disp_jid=disp_mas_div_jobid($rowCa['rmas_div'],$_GET['jid']);
				}
				else
				{
					$disp_jid=disp_mas_div_jobid($rowCa['mas_div'],$_GET['jid']);
				}
			}
			else
			{
				$disp_jid[]=$_GET['jid'];
			}
			
			if ($_SESSION['action']=="est")
			{
				$qryD  = "SELECT *, ";
				$qryD .= "	(SELECT phscode FROM phasebase WHERE phsid=J.phsid) as bphs ";
				$qryD .= "FROM bid_breakout as J WHERE J.officeid='".$_SESSION['officeid']."' AND J.estid='".$_GET['jid']."' AND J.rdbid='".$_GET['rdbid']."' order by J.jadd,J.phsid;";
			}
			if ($_SESSION['action']=="contract")
			{
				$qryD  = "SELECT *, ";
				$qryD .= "	(SELECT phscode FROM phasebase WHERE phsid=J.phsid) as bphs ";
				$qryD .= "FROM jbids_breakout as J WHERE J.officeid='".$_SESSION['officeid']."' AND J.jobid='".$_GET['jid']."' AND J.rdbid='".$_GET['rdbid']."' order by J.jadd,J.phsid;";
			}
			elseif ($_SESSION['action']=="job")
			{
				$qryD  = "SELECT *, ";
				$qryD .= "	(SELECT phscode FROM phasebase WHERE phsid=J.phsid) as bphs ";
				$qryD .= "FROM jbids_breakout as J WHERE J.officeid='".$_SESSION['officeid']."' AND J.njobid='".$_GET['jid']."' AND J.rdbid='".$_GET['rdbid']."' order by J.jadd,J.phsid;";
			}
			$resD = mssql_query($qryD);
			$nrowD= mssql_num_rows($resD);
		
			//echo $qryD."<br>";
			
			//var_dump($_SESSION['estbidretail']);
			echo "<table width=\"100%\">\n";
			echo "	<tr>\n";
			echo "		<td>\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"left\">\n";
			echo "						<table width=\"100%\">\n";
			echo "							<tr>\n";
			echo "								<td align=\"right\" valign=\"bottom\" width=\"85px\"><b>Customer:</b></td><td align=\"left\" valign=\"bottom\">".$rowB['clname'].", ".$rowB['cfname']."</td>\n";
			echo "							</tr>\n";
			echo "							<tr>\n";
		
			if ($_SESSION['action']=="est")
			{
				echo "								<td align=\"right\" valign=\"bottom\" width=\"85px\"><b>Estimate:</b></td><td align=\"left\" valign=\"bottom\">".$disp_jid[0]."</td>\n";
				
				if (isset($_SESSION['estbidretail'][$_GET['rdbid']][0]) and $_SESSION['estbidretail'][$_GET['rdbid']][0] != 0)
				{
				  $bidamt=$_SESSION['estbidretail'][$_GET['rdbid']][0];
				}
				else
				{
				  $bidamt=0;
				}
			}
			elseif ($_SESSION['action']=="contract")
			{
				echo "								<td align=\"right\" valign=\"bottom\" width=\"85px\"><b>Contract:</b></td><td align=\"left\" valign=\"bottom\">".$disp_jid[0]."</td>\n";
				$bidamt=$rowAb['bidamt'];
			}
			elseif ($_SESSION['action']=="job")
			{
				echo "								<td align=\"right\" valign=\"bottom\" width=\"85px\"><b>Job:</b></td><td align=\"left\" valign=\"bottom\">".$disp_jid[0]."</td>\n";
				$bidamt=$rowAb['bidamt'];
			}
			
			echo "							</tr>\n";
			echo "						</table>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td>\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"left\">\n";
			echo "						<table>\n";
			echo "							<tr>\n";
			echo "								<td align=\"right\" valign=\"bottom\" width=\"85px\"><b>Retail Bid Item:</b></td>\n";
			echo "								<td align=\"left\" valign=\"bottom\">".$rowC['item']."</td>\n";
			echo "							</tr>\n";
			echo "							<tr>\n";
			echo "								<td align=\"right\" valign=\"top\" width=\"85px\"><b>Description:</b></td>\n";
			echo "								<td align=\"left\" valign=\"top\">".$rowAb['bidinfo']."</td>\n";
			echo "							</tr>\n";
			echo "							<tr>\n";
			echo "								<td align=\"right\" valign=\"bottom\" width=\"85px\"><b>Retail Bid Amt:</b></td>\n";
			echo "								<td align=\"left\" valign=\"bottom\">".number_format($bidamt, 2, '.', '')."</td>\n";
			echo "							</tr>\n";
			echo "						</table>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			
			if ($nrowD > 0)
			{
				$tprice	=0;
				$tvar		=0;
				echo "	<tr>\n";
				echo "		<td>\n";
				echo "			<table class=\"outer\" width=\"100%\">\n";
				echo "				<tr>\n";
				echo "					<td class=\"ltgray_und\" align=\"right\" width=\"85px\"><b>Cost Breakout:</b></td>\n";
				echo "					<td class=\"ltgray_und\" align=\"left\" colspan=\"5\"><b></b></td>\n";
				echo "				</tr>\n";
				echo "				<tr>\n";
				echo "					<td class=\"wh_und\" align=\"left\"><b>Phase (Addn)</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"center\"><b>Date</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"left\"><b>Descrip</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"left\"><b>Vendor</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"left\"><b>Part #</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"right\"><b>Cost</b></td>\n";
				echo "				</tr>\n";
				
				while ($rowD = mssql_fetch_array($resD))
				{
					if ($rowD['jadd']!=0)
					{
						$phst=$rowD['bphs']." (60".$rowD['jadd']."L)";
					}
					else
					{
						$phst=$rowD['bphs'];
					}
					
					echo "				<tr>\n";
					echo "					<td class=\"wh_und\" align=\"left\">".$phst."</td>\n";
					echo "					<td class=\"wh_und\" align=\"center\">".date("m/d/y",strtotime($rowD['added']))."</td>\n";
					echo "					<td class=\"wh_und\" align=\"left\">".$rowD['sdesc']."</td>\n";
					echo "					<td class=\"wh_und\" align=\"left\">".$rowD['vendor']."</td>\n";
					echo "					<td class=\"wh_und\" align=\"left\">".$rowD['partno']."</td>\n";
					echo "					<td class=\"wh_und\" align=\"right\">".number_format($rowD['bprice'], 2, '.', '')."</td>\n";
					echo "				</tr>\n";
					$tprice=$tprice+$rowD['bprice'];
				}
				
				$tvar=$bidamt-$tprice;
				echo "						<tr>\n";
				echo "							<td class=\"gray\" align=\"right\" colspan=\"5\"><b>Cost Total:</b></td>\n";
				echo "							<td class=\"wh_und\" align=\"right\">".number_format($tprice, 2, '.', '')."</td>\n";
				echo "						</tr>\n";
				echo "						<tr>\n";
				echo "							<td class=\"gray\" align=\"right\" colspan=\"5\"><b>Variance:</b></td>\n";
				echo "							<td class=\"wh_und\" align=\"right\">".number_format($tvar, 2, '.', '')."</td>\n";
				echo "						</tr>\n";
				echo "					</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			
			// 
			echo "</table>\n";
		}
	}

	//print_r($_SESSION);
}

function drill_mpa_add()
{
	error_reporting(E_ALL);
   //$hostname = "192.168.1.59";
	$hostname = "192.168.100.45";
	$username = "jestadmin";
	$password = "into99black";
	$dbname   = "jest";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to Database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
	$MAS=$_GET['pb_code'];
	
	$uid = md5(session_id().time()).".".$_SESSION['securityid'];
	
	$qry = "SELECT stax,pb_code,accountingsystem FROM offices WHERE officeid='".$_GET['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($_GET['action']=="est")
	{
		$qryA = "SELECT cid,securityid FROM cinfo WHERE officeid='".$_GET['officeid']."' AND cid='".$_GET['cid']."';";
	}
	elseif ($_GET['action']=="contract")
	{
		$qryA  =  "SELECT  ";
		$qryA .= "	c.cid, ";
		$qryA .= "	j1.officeid, ";
		$qryA .= "	j1.securityid, ";
		$qryA .= "	j1.renov  ";
		$qryA .= "FROM  ";
		$qryA .= "	jobs as j1 ";
		$qryA .= "INNER JOIN ";
		$qryA .= "	cinfo as c ";
		$qryA .= "ON ";
		$qryA .= "	j1.jobid= ";
		$qryA .= "	c.jobid AND ";
		$qryA .= "	j1.officeid= ";
		$qryA .= "	c.officeid ";
		$qryA .= "WHERE  ";
		$qryA .= "	j1.officeid='".$_GET['officeid']."' AND  ";
		$qryA .= "	j1.jobid='".$_GET['jid']."'; ";
		//$qryA = "SELECT cid,securityid,renov FROM jobs WHERE officeid='".$_GET['officeid']."' AND jobid='".$_GET['jid']."';";
	}
	elseif ($_GET['action']=="job")
	{
		$qryA  =  "SELECT  ";
		$qryA .= "	c.cid, ";
		$qryA .= "	j1.officeid, ";
		$qryA .= "	j1.securityid, ";
		$qryA .= "	j1.renov  ";
		$qryA .= "FROM  ";
		$qryA .= "	jobs as j1 ";
		$qryA .= "INNER JOIN ";
		$qryA .= "	cinfo as c ";
		$qryA .= "ON ";
		$qryA .= "	j1.njobid= ";
		$qryA .= "	c.njobid AND ";
		$qryA .= "	j1.officeid= ";
		$qryA .= "	c.officeid ";
		$qryA .= "WHERE  ";
		$qryA .= "	j1.officeid='".$_GET['officeid']."' AND  ";
		$qryA .= "	j1.njobid='".$_GET['jid']."'; ";
		//$qryA = "SELECT cid,securityid,renov FROM jobs WHERE officeid='".$_GET['officeid']."' AND njobid='".$_GET['jid']."';";
	}

	//echo $qryA."<br>";

	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	$qryB = "SELECT * FROM cinfo WHERE officeid='".$_GET['officeid']."' AND cid='".$rowA['cid']."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	//echo $qryB."<br>";
	
	$qryCa = "SELECT mas_div,rmas_div FROM security WHERE securityid='".$rowA['securityid']."';";
	$resCa = mssql_query($qryCa);
	$rowCa = mssql_fetch_array($resCa);
	
	if ($row['accountingsystem']==2)
   {
	  $qryCb = "
		 if object_id('tempdb..#tmpaphsacc') is not null
			begin
				drop table #tmpaphsacc
			end
			
		 if object_id('tempdb..#tmpaphsinv') is not null
			begin
				drop table #tmpaphsinv
			end
			
		 select
			distinct(phsid)
		into #tmpaphsacc
		from
			[".$row['pb_code']."accpbook] AS A
		where
			qtype=79
		
		select
			distinct(phsid)
		into #tmpaphsinv
		from
			[".$row['pb_code']."inventory] AS A
		where
			qtype=79
		
		SELECT 
			P.phsid,P.phscode,P.phsname 
		FROM 
			phasebase AS P
		WHERE 
			P.costing=1 and P.phsid!=3 and P.phsid!=5
			and (
				P.phsid in (select phsid from #tmpaphsacc)
				or P.phsid in (select phsid from #tmpaphsinv)
			)
		order by 
			P.seqnum 
		asc;
	  ";
   }
   else
   {
	  $qryCb = "SELECT phsid,phscode,phsname FROM phasebase WHERE costing=1 and phsid!=3 and phsid!=5 order by seqnum asc;";  
   }
   $resCb = mssql_query($qryCb);
   $nrowCb =mssql_num_rows($resCb);
	
	//echo $qryCb."<br>";
	
	/*
	if ($_GET['action']=="est")
	{
		$qryD = "SELECT * FROM est_bids WHERE officeid='".$_GET['officeid']."' AND estid='".$_GET['jid']."' AND bidaccid='".$_GET['rdbid']."';";
	}
	elseif ($_GET['action']=="contract")
	{
		$qryD = "SELECT * FROM bid_breakout WHERE officeid='".$_GET['officeid']."' AND jobid='".$_GET['jid']."' AND jadd='".$_GET['jadd']."' AND cdbid='".$_GET['cdbid']."' AND rdbid='".$_GET['rdbid']."';";
	}
	elseif ($_GET['action']=="job")
	{
		$qryD = "SELECT * FROM jbids_breakout WHERE officeid='".$_GET['officeid']."' AND njobid='".$_GET['jid']."' AND jadd='".$_GET['jadd']."' AND cdbid='".$_GET['cdbid']."' AND rdbid='".$_GET['rdbid']."';";
	}
	$resD = mssql_query($qryD);
	$nrowD= mssql_num_rows($resD);

	//echo $qryD."<br>";
	*/

	if ($_GET['action']=="job")
	{
		if ($rowA['renov']==1 && $rowCa['rmas_div']!=0)
		{
			$disp_jid=disp_mas_div_jobid($rowCa['rmas_div'],$_GET['jid']);
		}
		else
		{
			$disp_jid=disp_mas_div_jobid($rowCa['mas_div'],$_GET['jid']);
		}
	}
	else
	{
		$disp_jid[]=$_GET['jid'];
	}
	
	echo "<table width=\"275px\">\n";
	/*
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Manual Phase Adjust</b></td>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"gray\" align=\"left\">\n";
	echo "						<table width=\"100%\">\n";
	echo "							<tr>\n";
	echo "								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Customer:</b></td><td align=\"left\" valign=\"bottom\">".$rowB['clname'].", ".$rowB['cfname']."</td>\n";
	echo "							</tr>\n";
	echo "							<tr>\n";

	if ($_GET['action']=="est")
	{
		echo "								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Estimate:</b></td><td align=\"left\" valign=\"bottom\">".$disp_jid[0]."</td>\n";
	}
	elseif ($_GET['action']=="contract")
	{
		echo "								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Contract:</b></td><td align=\"left\" valign=\"bottom\">".$disp_jid[0]."</td>\n";
	}
	elseif ($_GET['action']=="job")
	{
		echo "								<td align=\"right\" valign=\"bottom\" width=\"75px\"><b>Job:</b></td><td align=\"left\" valign=\"bottom\">".$disp_jid[0]."</td>\n";
	}
	
	echo "							</tr>\n";
	echo "						</table>\n";
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	*/
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "			<table class=\"outer\" width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td align=\"left\">\n";
	
   if ($nrowCb > 0)
   {
	//echo "						<form action=\"../index.php\" method=\"post\" target=\"JMSmain\" onSubmit=\"JavaScript: window.close()\">\n";
	echo "						<form id=\"MPAAddCostForm\" action=\"../index.php\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_GET['officeid']."\">\n";
	echo "						<input type=\"hidden\" name=\"sid\" value=\"".$_GET['sid']."\">\n";
	echo "						<input type=\"hidden\" name=\"cid\" value=\"".$rowA['cid']."\">\n";
	echo "						<input type=\"hidden\" name=\"rdbid\" value=\"0\">\n";
	echo "						<input type=\"hidden\" name=\"cdbid\" value=\"0\">\n";
	echo "						<input type=\"hidden\" name=\"costid\" value=\"0\">\n";
	echo "						<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"edit_mpa_jobmode_add\">\n";

	if ($_GET['action']=="est")
	{
		echo "							<input type=\"hidden\" name=\"jid\" value=\"".$_GET['jid']."\">\n";
		echo "							<input type=\"hidden\" name=\"estid\" value=\"".$_GET['jid']."\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	}
	elseif ($_GET['action']=="contract")
	{
		echo "							<input type=\"hidden\" name=\"jobid\" value=\"".$_GET['jid']."\">\n";
		echo "							<input type=\"hidden\" name=\"jid\" value=\"".$_GET['jid']."\">\n";
		echo "							<input type=\"hidden\" name=\"jadd\" value=\"".$_GET['jadd']."\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	}
	elseif ($_GET['action']=="job")
	{
		echo "							<input type=\"hidden\" name=\"njobid\" value=\"".$_GET['jid']."\">\n";
		echo "							<input type=\"hidden\" name=\"jid\" value=\"".$_GET['jid']."\">\n";
		echo "							<input type=\"hidden\" name=\"jadd\" value=\"".$_GET['jadd']."\">\n";
		echo "							<input type=\"hidden\" name=\"action\" value=\"job\">\n";
	}
	
	echo "						<table class=\"gray\" width=\"100%\">\n";
   }
   else
   {
	  echo "						<table class=\"gray\" width=\"100%\">\n";
	  echo "							<tr>\n";
	  echo "								<td colspan=\"2\" align=\"left\">ERROR! Your office has no Manual Phase Adjust Cost Items. Contact Management to Setup Manual Phase Adjust Cost Items.</td>\n";
	  echo "							</tr>\n";
   }
	
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Phase:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\">\n";
	
   if ($nrowCb > 0)
   {
	  echo "									<select name=\"phsid\">\n";
	  
	  $phsexc_ar=array('506L','507L');
	  while ($rowCb = mssql_fetch_array($resCb))
	  {
		 if (!in_array($rowCb['phscode'],$phsexc_ar))
		 {
			echo "<option value=\"".$rowCb['phsid']."\">(".$rowCb['phscode'].") ".$rowCb['phsname']."</option>\n";
		 }
	  }
   }
	
   echo "									</select>\n";
   echo "								</td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Item Name:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" name=\"sdesc\" size=\"20\"></td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Descrip:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><textarea name=\"comments\" cols=\"30\" rows=\"2\"></textarea></td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Vendor:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" name=\"vendor\" size=\"20\"></td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Part #:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" name=\"partno\" size=\"20\"></td>\n";
   echo "							</tr>\n";
   echo "							<tr>\n";
   echo "								<td class=\"ltgray_und\" align=\"right\" valign=\"bottom\" width=\"75px\"><b>Cost:</b></td>\n";
   echo "								<td class=\"wh_und\" align=\"left\" valign=\"bottom\"><input type=\"text\" class=\"bboxl\" name=\"bprice\" size=\"20\"></td>\n";
   echo "							</tr>\n";
	
   if ($nrowCb > 0)
   {
	  //echo "							<tr>\n";
	  //echo "								<td class=\"gray\" align=\"right\" valign=\"bottom\" colspan=\"2\"><input class=\"buttondkgrypnl80\" type=\"submit\" value=\"Add\"></td>\n";
	  //echo "							</tr>\n";
   }
   
   echo "						</table>\n";
	
   if ($nrowCb > 0)
   {
	  echo "						</form>\n";
   }
   
	echo "					</td>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	
	//print_r($_GET);	
}

function drill_view_mpa_cost()
{
	if (!isset($_GET['sid']) || !isset($_GET['oid']) || !isset($_GET['jid']))
	{
		echo "Invalid Request";
		exit;
	}
	
	$MAS=$_GET['pb_code'];
	$hostname = "192.168.100.45";
	$username = "jestadmin";
	$password = "into99black";
	$dbname   = "jest";

	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to Database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");
	
	$qry = "SELECT stax FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);

	if ($_SESSION['action']=="est")
	{
		$qryAa = "SELECT cid as custid,securityid,renov FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_GET['jid']."';";
	}
	elseif ($_SESSION['action']=="contract")
	{
		$qryAa = "SELECT custid,jobid,securityid,renov FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$_GET['jid']."';";
	}
	elseif ($_SESSION['action']=="job")
	{
		$qryAa = "SELECT custid,njobid,securityid,renov FROM jobs WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$_GET['jid']."';";
	}

	//echo $qryAa."<br>";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);
	$nrowAa= mssql_num_rows($resAa);

	if ($nrowAa >= 1)
	{	
			$qryB = "SELECT * FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND custid='".$rowAa['custid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);
		
			//echo $qryB."<br>";
			
			//$qryC = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$_GET['rdbid']."';";
			//$resC = mssql_query($qryC);
			//$rowC = mssql_fetch_array($resC);
	
			$qryCa = "SELECT mas_div,rmas_div FROM security WHERE securityid='".$rowAa['securityid']."';";
			$resCa = mssql_query($qryCa);
			$rowCa = mssql_fetch_array($resCa);
			
			$qryCb = "SELECT phsid,phscode,phsname FROM phasebase WHERE costing=1 order by seqnum asc;";
			$resCb = mssql_query($qryCb);
			
			//echo $qryCb."<br>";
		
			if ($_SESSION['action']=="est")
			{
				$disp_jid[]=$_GET['jid'];
			}
			elseif ($_SESSION['action']=="contract")
			{
				$disp_jid[]=$_GET['jid'];
			}
			elseif ($_SESSION['action']=="job")
			{
				if ($rowAa['renov']==1 && $rowCa['rmas_div']!=0)
				{
					$disp_jid=disp_mas_div_jobid($rowCa['rmas_div'],$_GET['jid']);
				}
				else
				{
					$disp_jid=disp_mas_div_jobid($rowCa['mas_div'],$_GET['jid']);
				}
			}
			
			if ($_SESSION['action']=="est")
			{
				$qryD  = "SELECT *, ";
				$qryD .= "	(SELECT phscode FROM phasebase WHERE phsid=J.phsid) as bphs ";
				$qryD .= "FROM man_phs_adj as J WHERE J.officeid='".$_SESSION['officeid']."' AND J.estid='".$_GET['jid']."' order by J.jadd,J.phsid;";
			}
			elseif ($_SESSION['action']=="contract")
			{
				$qryD  = "SELECT *, ";
				$qryD .= "	(SELECT phscode FROM phasebase WHERE phsid=J.phsid) as bphs ";
				$qryD .= "FROM man_phs_adj as J WHERE J.officeid='".$_SESSION['officeid']."' AND J.jobid='".$_GET['jid']."' order by J.jadd,J.phsid;";
			}
			elseif ($_SESSION['action']=="job")
			{
				$qryD  = "SELECT *, ";
				$qryD .= "	(SELECT phscode FROM phasebase WHERE phsid=J.phsid) as bphs ";
				$qryD .= "FROM man_phs_adj as J WHERE J.officeid='".$_SESSION['officeid']."' AND J.njobid='".$_GET['jid']."' order by J.jadd,J.phsid;";
			}
			$resD = mssql_query($qryD);
			$nrowD= mssql_num_rows($resD);
		
			//echo $qryD."<br>";
			
			echo "<table width=\"275px\">\n";
			/*
			echo "	<tr>\n";
			echo "		<td>\n";
			echo "			<table class=\"outer\" width=\"100%\">\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"left\" valign=\"bottom\"><b>Manual Phase Adjust History</b></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td class=\"gray\" align=\"left\">\n";
			echo "						<table width=\"100%\">\n";
			echo "							<tr>\n";
			echo "								<td align=\"right\" valign=\"bottom\" width=\"85px\"><b>Customer:</b></td><td align=\"left\" valign=\"bottom\">".$rowB['clname'].", ".$rowB['cfname']."</td>\n";
			echo "							</tr>\n";
			echo "							<tr>\n";
		
			if ($_SESSION['action']=="contract")
			{
				echo "								<td align=\"right\" valign=\"bottom\" width=\"85px\"><b>Contract:</b></td><td align=\"left\" valign=\"bottom\">".$disp_jid[0]."</td>\n";
			}
			elseif ($_SESSION['action']=="job")
			{
				echo "								<td align=\"right\" valign=\"bottom\" width=\"85px\"><b>Job:</b></td><td align=\"left\" valign=\"bottom\">".$disp_jid[0]."</td>\n";
			}
			
			echo "							</tr>\n";
			echo "						</table>\n";
			echo "					</td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			*/
			
			if ($nrowD > 0)
			{
				$tprice	=0;
				$tvar	=0;
				echo "	<tr>\n";
				echo "		<td>\n";
				echo "			<table class=\"outer\" width=\"100%\">\n";
				/*
				echo "				<tr>\n";
				echo "					<td class=\"ltgray_und\" align=\"right\" width=\"85px\"><b>History:</b></td>\n";
				echo "					<td class=\"ltgray_und\" align=\"left\" colspan=\"5\"><b></b></td>\n";
				echo "				</tr>\n";
				*/
				echo "				<tr>\n";
				echo "					<td class=\"wh_und\" align=\"left\"><b>Phase (Addn)</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"center\"><b>Date</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"left\"><b>Descrip</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"left\"><b>Vendor</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"left\"><b>Part #</b></td>\n";
				echo "					<td class=\"wh_und\" align=\"right\"><b>Cost</b></td>\n";
				echo "				</tr>\n";
				
				while ($rowD = mssql_fetch_array($resD))
				{
					if ($rowD['jadd']!=0)
					{
						$phst=$rowD['bphs']." (60".$rowD['jadd']."L)";
					}
					else
					{
							$phst=$rowD['bphs'];
					}
					
					echo "				<tr>\n";
					echo "					<td class=\"wh_und\" align=\"left\">".$phst."</td>\n";
					echo "					<td class=\"wh_und\" align=\"center\">".date("m/d/y",strtotime($rowD['added']))."</td>\n";
					echo "					<td class=\"wh_und\" align=\"left\">".$rowD['sdesc']."</td>\n";
					echo "					<td class=\"wh_und\" align=\"left\">".$rowD['vendor']."</td>\n";
					echo "					<td class=\"wh_und\" align=\"left\">".$rowD['partno']."</td>\n";
					echo "					<td class=\"wh_und\" align=\"right\">".number_format($rowD['bprice'], 2, '.', '')."</td>\n";
					echo "				</tr>\n";
					$tprice=$tprice+$rowD['bprice'];
				}
				
				//$tvar=$rowAb['bidamt']-$tprice;
				//echo "						<tr>\n";
				//echo "							<td class=\"gray\" align=\"right\" colspan=\"4\"><b>MPA Cost Total:</b></td>\n";
				//echo "							<td class=\"wh_und\" align=\"right\">".number_format($tprice, 2, '.', '')."</td>\n";
				//echo "						</tr>\n";
				echo "					</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			
			// 
			echo "</table>\n";
	}

	//print_r($_SESSION);
	
}

function drill_hp_ind()
{
	if ($_GET['call']=="hp")
	{
		if ($_GET['hpc']=="CA")
		{
			echo "<br><b>Customer Addendum</b><br><br>";
			echo "The customer addendum feature is used to modify the contract amount at the customer’s request. This feature brings you to the retail price book.";
		}
		elseif ($_GET['hpc']=="GA")
		{
			echo "<br><b>GM Adjust</b><br><br>";
			echo "The GM Adjust is used when the customer’s contract amount is not affected but the job cost and/or the Designers commission needs to be adjusted. This feature brings you to the retail price book.";
		}
		elseif ($_GET['hpc']=="AW")
		{
			echo "<br><b>Addendum Worksheet</b><br><br>";
			echo "Modifying the Job entails that you use the retail price book items under the blue sub headers listed below.<br><br>";
			echo "Addendum notes will only supply the user with notes pertaining to the addendum it will not adjust the cost or commission. <br><br>";

			echo "The price book items that are chosen pertaining to addendum will - <br>";
			echo "1. Suggest a book price<br>";
			echo "2. Incure a cost on the job<br>";
			echo "3. Suggest a designer’s commission<br><br>";

			echo "The save addendum button will save your items that have been chosen pertaining to your addendum and forward you to the retail breakdown of the addendum. <br><br>";

			echo "At the retail breakdown of the addendum you decide if the Addendum Retail Price Adjust field needs to be filled in.<br><br>";
			echo "The ADDENDUM RETAIL PRICE ADJUST FIELD is used to adjust the retail contract amount. The amount entered in this field is the amount you sold the addendum to the customer for. Or the amount you’re crediting to the customer. It won’t necessarily match the Addendum price per book. <br><br>";

			echo "The PAYMENT SCHEDULE AMOUNT FIELD is used to adjust the payment schedule to the adjusted contract amount. This field will match the amount you will collect from the customer or the amount your crediting the customer for the addendum at hand. <br><br>";

			echo "The COMM. Column will offer an addendum price per book commission amount. If the designer has earned this amount or a different amount the amount needs to carried over to the addendum retail price adjust commission column. If the COMM. second field is left blank the net commission will not be effected. <br><br>";

			echo "Apply adjust will save the amounts entered into pertaining fields.<br><br>";

			echo "View retail will bring you to the retail Breakdown of the Job.<br><br>";
			echo "<b>IMPORTANT:</b><br>";
			echo "1. Scroll to the bottom of the retail breakdown and check to make sure the addendum was applied as intended. If there is an error you can use the view button to edit or delete your addendum.<br>";
			echo "2. View Cost and make sure the appropriate cost have been applied to the job pertaining to you addendum. You will find these cost at cost code 601L.<br>";
			echo "3. At the cost side of your breakdown the payment schedule needs to be checked to insure that the Addendum adjusted contract amount is correct.<br>";
		}
		elseif ($_GET['hpc']=="FCS")
		{
			echo "<br><b>Financing Contact Search</b><br><br>";
			echo "<ul><b>Field Descriptons</b><br>";
			echo "<li><b>Search By:</b><br>Select the data field you wish to search via the Drop down box, then type in the parameters in the next Box.</li>";
			echo "<li><b>Offices:</b><br>Selecting an Office will restrict the search to that office.</li>";
			echo "<li><b>Sort By:</b><br>Selects the order which the Results will be returned.</li>";
			echo "<li><b>Date Range:</b><br>The Date Range fields allow the search to be narrowed to the Date Range input. This is an optional parameter.</li>";
			echo "<li><b>*NOTE*:</b><br>The <b>Search by</b> field is optional if the Date Range fields are filled in.</li>";
			echo "</ul>";
		}
	}
}

function valid_date($strDate)
{
	$isValid = false;

	if (ereg('^([0-9]{1,2})[-,/]([0-9]{1,2})[-,/](([0-9]{2})|([0-9]{4}))$', $strDate))
	{
		$dateArr = split('[-,/]', $strDate);
		$m=$dateArr[0]; $d=$dateArr[1]; $y=$dateArr[2];
		$isValid = checkdate($m, $d, $y);
	}
	return $isValid;
}

function drill_ivr_range()
{
	error_reporting(E_ALL);
	
	if (valid_date($_GET['d1']) && valid_date($_GET['d2']))
	{	
		//$hostname = "192.168.1.59";
		$hostname = "CORP-DB02";
		$username = "jestadmin";
		$password = "into99black";
		$dbname   = "jest";

		mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
		mssql_select_db($dbname) or die("Table unavailable");
		
		$ndays=0;
		$bdar = strtotime($_GET['d1']);
		$edar = strtotime($_GET['d2']);
		
		if ($bdar > $edar)
		{
			echo "Unsupported Date Range.";
			exit;
		}
		else
		{
			$drar=date_range($_GET['d1'],$_GET['d2']);
			foreach ($drar[0] as $na => $va)
			{
				//echo $va."<br>";				
				$qry1  = "SELECT  ";
				$qry1 .= "	COUNT(I.id) ";
				$qry1 .= "FROM ";
				$qry1 .= "	IVR_stats..tIVR_events as I ";
				$qry1 .= "INNER JOIN ";
				$qry1 .= "	IVR_stats..TollfreetoDID as T ";
				$qry1 .= "ON ";
				$qry1 .= "	SUBSTRING(I.tollfree,1,10)=T.tollfree ";
				$qry1 .= "WHERE ";
				//$qry1 .= "	I.did is not null and ";
				$qry1 .= "	T.tollfree='".$_GET['tfn']."' and ";
				$qry1 .= "	I.indate >= '".$va."' and ";
				$qry1 .= "	I.indate <= '".$va." 23:59:59' ";
				
				if (isset($_GET['oid']) && $_GET['oid']!=0)
				{
					$qry1 .= "	and I.oid = '".$_GET['oid']."';";
				}
				
				$res1  = mssql_query($qry1);
				$row1  = mssql_fetch_row($res1);
				
				//echo $qry1."<br>---<br>";
				
				$dsetar[]=array($va,$row1[0]);
				//$dcar[]	=$row1[0];
			}
		}
		
		$qry0 = "SELECT description,category FROM IVR_stats..TollfreetoDID WHERE tollfree='".$_GET['tfn']."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		
		echo "<table align=\"center\" width=\"100%\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
		echo "         <table class=\"outer\" width=\"100%\">\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>IVR Matrix Call Report Detail</b></td>\n";
		echo "      			<td class=\"gray\" align=\"right\"></td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>\n";
		
		if ($row0['category']==0)
		{
			echo "&nbsp";
		}
		elseif ($row0['category']==1)
		{
			echo "NAT: ";
		}
		elseif ($row0['category']==2)
		{
			echo "LOCAL: ";
		}
					
		echo $row0['description']."</b> ".$_GET['tfn'];
		
		echo "					</td>\n";
		echo "      			<td class=\"gray\" align=\"right\">\n";
		echo "         			<table width=\"100%\">\n";
		echo "   						<tr>\n";
		echo "      						<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";
	
		if (!empty($_GET['d1']))
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\" value=\"".$_GET['d1']."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
		}
	
		echo " to ";
	
		if (!empty($_GET['d2']))
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\" value=\"".$_GET['d2']."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
		}

		echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
		echo "      						</td>\n";
		echo "      						<td class=\"gray\" align=\"left\">\n";
		echo "      						</td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		
		if (count($dsetar) > 0)
		{
			echo "<table align=\"center\" width=\"75%\">\n";
			echo "   <tr>\n";
			echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
			echo "         <table class=\"outer\" width=\"100%\">\n";
			echo "   			<tr>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Day</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Date</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Calls</b></td>\n";
			echo "   			</tr>\n";
			
			$cnt=0;
			foreach ($dsetar as $na => $va)
			{
				//echo $qry1;
				//echo "CNT: ".$row1[0]."<br>";
				$dow	=date("D",strtotime($va[0]));
				$cnt=$cnt+$va[1];
				echo "   			<tr>\n";
				echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>&nbsp".$dow."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>&nbsp".$va[0]."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>&nbsp".$va[1]."</td>\n";
				echo "   			</tr>\n";
			}
			
			echo "   			<tr>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp</td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Total</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp".$cnt."</td>\n";
			echo "   			</tr>\n";
			echo "			</td>\n";
			echo "   	</tr>\n";
			echo "	</table>\n";
			
			/*
			$getstrC="";
			$getstrD="";
			//print_r($dcar);
			$a="&";
			$e="=";
			$c="C";
			$d="D";
			$x=0;
			foreach($dsetar as $nc => $vc)
			{
				$x++;
				$getstrC=$getstrC.$c.$x.$e.$vc[1].$a;
				$getstrD=$getstrD.$d.$x.$e.date("m/d",strtotime($vc[0])).$a;
			}
			*/
			//echo $getstrC."<br>";
			//echo $getstrD."<br>";
			
			//echo $_GET['tfn']."<br>";
			
			//echo "<img src=\"jpimg.php?tfn=\"".$_GET['tfn']."".$getstr."\" border=0 align=center width =400 height=200>\n";
			
			echo "<center>";
			//echo "<img src=\"jpimg.php?".$getstrC.$a.$getstrD."\" border=0 align=center width=400 height=300>\n";
			//echo "<img src=\"jpimg.php?".$getstrC."\" border=0 align=center width=400 height=300>\n";
			echo "</center>";
			//echo "<a href=\"jpimg.php?".$getstrC.$a.$getstrD."\">TEST</a>\n";
			//echo "<pre>";
			//echo "jpimg.php?tfn=\"".$_GET['tfn']."".$getstr."\" TEST\n";
			//echo "</pre>";
		}
	}
	else
	{
		echo "Invalid Date Range!";
	}
}

function drill_ivr_detail()
{
	error_reporting(E_ALL);
	
	if (valid_date($_GET['d1']) && valid_date($_GET['d2']))
	{	
		$hostname = "192.168.100.45";
		$username = "jestadmin";
		$password = "into99black";
		$dbname   = "jest";

		mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
		mssql_select_db($dbname) or die("Table unavailable");
		
		$ndays=0;
		$bdar = split('[-,/]', $_GET['d1']);
		$edar = split('[-,/]', $_GET['d2']);
		$b		=strtotime($_GET['d1']);
		$e		=strtotime($_GET['d2']);
		
		if ($b > $e)
		{
			echo "Invalid Date Range";
			exit;
		}
		else
		{
			$drar=date_range($_GET['d1'],$_GET['d2']);
			$ndays=$drar[2];
		}

		$qry1  = "SELECT  ";
		$qry1 .= "I.indate, ";
		$qry1 .= "I.cani, ";
		$qry1 .= "I.czip, ";
		$qry1 .= "I.ringto, ";
		$qry1 .= "(SELECT name FROM offices WHERE officeid=I.oid) AS oname ";
		//$qry1 .= "T.category ";
		$qry1 .= "FROM ";
		$qry1 .= "	IVR_stats..tIVR_events as I ";
		$qry1 .= "INNER JOIN ";
		$qry1 .= "	IVR_stats..TollfreetoDID as T ";
		$qry1 .= "ON ";
		$qry1 .= "	SUBSTRING(I.tollfree,1,10)=T.tollfree ";
		$qry1 .= "WHERE ";
		$qry1 .= "	T.tollfree='".$_GET['tfn']."' and ";
		$qry1 .= "	I.indate >= '".$_GET['d1']."' and ";
		$qry1 .= "	I.indate <= '".$_GET['d2']." 23:59:59' ";
				
		if (isset($_GET['oid']) && $_GET['oid']!=0)
		{
			$qry1 .= "	and I.oid = '".$_GET['oid']."';";
		}
				
		$res1  = mssql_query($qry1);
		$ndays = mssql_num_rows($res1);
		
		$qry0 = "SELECT description,category FROM IVR_stats..TollfreetoDID WHERE tollfree='".$_GET['tfn']."';";
		$res0 = mssql_query($qry0);
		$row0 = mssql_fetch_array($res0);
		
		//echo $qry1."<br>";
		
		echo "<table align=\"center\" width=\"100%\">\n";
		echo "   <tr>\n";
		echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
		echo "         <table class=\"outer\" width=\"100%\">\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP>&nbsp<b>IVR Matrix Call Report Detail</b></td>\n";
		echo "      			<td class=\"gray\" align=\"right\">Time is PST unless otherwise noted</td>\n";
		echo "   			</tr>\n";
		echo "   			<tr>\n";
		echo "      			<td class=\"gray\" align=\"left\" NOWRAP><b>\n";
		
		if ($row0['category']==0)
		{
			echo "&nbsp";
		}
		elseif ($row0['category']==1)
		{
			echo "NAT: ";
		}
		elseif ($row0['category']==2)
		{
			echo "LOCAL: ";
		}

		echo $row0['description']."</b> ".$_GET['tfn'];
		
		echo "					</td>\n";
		echo "      			<td class=\"gray\" align=\"right\">\n";
		echo "         			<table width=\"100%\">\n";
		echo "   						<tr>\n";
		echo "      						<td class=\"gray\" align=\"right\">&nbsp<b>Date Range</b></font>\n";
	
		if (!empty($_GET['d1']))
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\" value=\"".$_GET['d1']."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d1\" size=\"11\">\n";
		}
	
		echo " to ";
	
		if (!empty($_GET['d2']))
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"10\" maxlength=\"10\" value=\"".$_GET['d2']."\">\n";
		}
		else
		{
			echo "									<input class=\"bboxl\" type=\"text\" name=\"d2\" size=\"11\">\n";
		}

		echo "									<input type=\"hidden\" name=\"full\" value=\"1\">\n";
		echo "      						</td>\n";
		echo "      						<td class=\"gray\" align=\"left\">\n";
		echo "      						</td>\n";
		echo "   						</tr>\n";
		echo "						</table>\n";
		echo "					</td>\n";
		echo "   			</tr>\n";
		echo "			</table>\n";
		
		if ($ndays > 0)
		{
			echo "<table align=\"center\" width=\"75%\">\n";
			echo "   <tr>\n";
			echo "      <td align=\"left\" valign=\"top\" width=\"100%\">\n";
			echo "         <table class=\"outer\" width=\"100%\">\n";
			echo "   			<tr>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp</td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Date</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Caller</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Entry</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Rang To</b></td>\n";
			echo "      			<td class=\"ltgray_und\" align=\"center\" NOWRAP>&nbsp<b>Office</b></td>\n";
			echo "   			</tr>\n";
			
			$cnt=0;
			while($row1  = mssql_fetch_array($res1))
			{
				$cnt++;
				echo "   			<tr>\n";
				echo "      			<td class=\"wh_und\" align=\"right\" NOWRAP>&nbsp".$cnt.".</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>&nbsp".date("m/d/y h:i A",strtotime($row1['indate']))."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>&nbsp".preg_replace('/@bhcorp.local/','',$row1['cani'])."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>&nbsp".$row1['czip']."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"center\" NOWRAP>&nbsp".$row1['ringto']."</td>\n";
				echo "      			<td class=\"wh_und\" align=\"left\" NOWRAP>&nbsp".$row1['oname']."</td>\n";
				echo "   			</tr>\n";
			}
			
			echo "			</td>\n";
			echo "   	</tr>\n";
			echo "	</table>\n";
		}
	}
	else
	{
		echo "Invalid Date Range!";
	}
}

function gl_pull_simple($ccode,$cdiv,$mdiv,$glacc)
{
	$out	=0;
	$bb	=0;

	if ($mdiv==1)
	{
		$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc.$cdiv."%' AND FiscalYr < 2002";
	}
	else
	{
		$qry = "SELECT ISNULL(SUM(BegBalTypeActualOnly),0) AS bbamt FROM [MAS_".trim($ccode)."].[dbo].[GL8_BudgetAndHistory] WHERE AccountNumber LIKE '".$glacc."%' AND FiscalYr < 2002";
	}

	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	$nrow = mssql_num_rows($res);

	if ($nrow > 0)
	{
		$bb=$row['bbamt'];
	}

	if ($mdiv==1)
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$cdiv."%';";
	}
	else
	{
		$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc."%';";
	}

	/*
	if ($glacc==109)
	{
	echo $qryA."<br>";
	}
	*/

	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);

	$out	=$rowA['pstamt']+$bb;
	return $out;
}

function drill_cb_ind()
{
	if (!isset($_SESSION['securityid']) && $_GET['a'] != 0)
	{
		die('You do not have appropriate Access for this resource.');
	}
	
	if (empty($_GET['s']) || empty($_GET['a']))
	{
		die('Invalid Request 1.');
	}
	
	if (md5($_GET['a']) != $_GET['x'])
	{
		die('Invalid Request 2.');
	}
   
   $d=   explode(":",$_GET['d']);
	
	$cdate = date("m/d/Y",$d[1]);
	$pdate = date("m/d/Y",$d[0]);
	
	$qry = "SELECT * FROM ZE_Stats..divtocomp WHERE division='".$_GET['a']."' and company='".$_GET['c']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	//$qryA = "SELECT TransactionDate,RefDescription,BatchNumber,PostingAmount FROM MAS_".$row['company']."..GL5_DetailPosting WHERE AccountNumber LIKE '".$_GET['s'].$row['division']."%' AND TransactionDate >='".$pdate." 00:00:00' AND TransactionDate <='".$cdate." 11:59:59' ORDER BY TransactionDate,BatchNumber,SeqCounter ASC;";
	$qryA = "SELECT TransactionDate,RefDescription,BatchNumber,PostingAmount FROM MAS_".$row['company']."..GL5_DetailPosting WHERE AccountNumber LIKE '".$_GET['s'].$row['division']."%' AND TransactionDate >='".$pdate." 00:00:00' AND TransactionDate <='".$cdate." 11:59:59' ORDER BY TransactionDate ASC;";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA."<br>";
	
   /*if ($_SESSION['securityid']==26)
   {
	  echo $qryA.'<br>';
   }*/
	
	if ($nrowA > 0)
	{
		$tcnt=0;
		echo "<table class=\"outer\" width=\"670\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"2\" class=\"gray\" align=\"left\"><b>Cash in Bank: GL".$_GET['s']."</b></td>\n";
		echo "		<td colspan=\"4\" class=\"gray\" align=\"center\">Period Date Range: <b>".$pdate." - ".$cdate."</b></td>\n";
		echo "		<td colspan=\"2\" class=\"gray\" align=\"right\">Print Date:<b>".date("m/d/Y h:i A",time())." PST</b></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Tran Date</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Description</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Batch</b></td>\n";
      //echo "		<td class=\"ltgray_und\" align=\"center\"><b>Beg Balance</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Debit</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Credit</b></td>\n";
      echo "		<td class=\"ltgray_und\" align=\"center\"><b>Balance</b></td>\n";
		echo "	</tr>\n";
		
      $rb=0;
		while ($rowA = mssql_fetch_array($resA))
		{
			$tcnt++;
			if ($tcnt%2)
			{
				$tbg = "wh_und";
			}
			else
			{
				$tbg = "gray_und";
			}
         
         if ($tcnt==1)
         {
            $rb=$_GET['b'];
            //$rb=$bb;
            echo "	<tr>\n";
            echo "		<td class=\"".$tbg."\" align=\"right\">".$tcnt.".</td>\n";
            echo "		<td class=\"".$tbg."\" align=\"right\"></td>\n";
            echo "		<td class=\"".$tbg."\" align=\"left\">Beginning Balance</td>\n";
            echo "		<td class=\"".$tbg."\" align=\"left\"></td>\n";
            echo "		<td class=\"".$tbg."\" align=\"right\"></td>\n";
            echo "		<td class=\"".$tbg."\" align=\"right\"></td>\n";
            echo "		<td class=\"".$tbg."\" align=\"right\">".number_format($rb,2,'.',',')."</td>\n";
            echo "	</tr>\n";
            $tcnt++;
         }

			$crd	="";
			$deb	="";
			//print_r($rowA);
			//echo $tcnt."<br>";
			
			if ($rowA['PostingAmount'] < 0)
			{
				$deb = number_format($rowA['PostingAmount'],2,'.',',');
			}
			else
			{
				$crd = number_format($rowA['PostingAmount'],2,'.',',');
			}
         
         $rb = $rb + $rowA['PostingAmount'];  
         
         $fbal= number_format($rb,2,'.',',');
         
         
         
			echo "	<tr>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".$tcnt.".</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"center\">".date("m/d/Y",strtotime($rowA['TransactionDate']))."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"left\">".$rowA['RefDescription']."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"center\">".$rowA['BatchNumber']."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".$deb."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".$crd."</td>\n";
         echo "		<td class=\"".$tbg."\" align=\"right\">".$fbal."</td>\n";
			echo "	</tr>\n";
		}
		
		echo "</table>\n";
	}
	else
	{
		//echo "<b> No Transactions in the Last ".$txt." days.";
	}
		
	//print_r($row);
}

function drill_cb_indold2()
{
	if (!isset($_SESSION['securityid']) && $_GET['a'] != 0)
	{
		die('You do not have appropriate Access for this resource.');
	}
	
	if (empty($_GET['s']) || empty($_GET['a']))
	{
		die('Invalid Request 1.');
	}
	
	if (md5($_GET['a']) != $_GET['x'])
	{
		die('Invalid Request 2.');
	}
   
   $d=   explode(":",$_GET['d']);
	
	$cdate = date("m/d/Y",$d[1]);
	$pdate = date("m/d/Y",$d[0]);
	
	$qry = "SELECT * FROM ZE_Stats..divtocomp WHERE division='".$_GET['a']."' and company='".$_GET['c']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qryA = "SELECT TransactionDate,RefDescription,BatchNumber,PostingAmount FROM MAS_".$row['company']."..GL5_DetailPosting WHERE AccountNumber LIKE '".$_GET['s'].$row['division']."%' AND TransactionDate BETWEEN '".$pdate."' AND '".$cdate."' ORDER BY TransactionDate,BatchNumber,SeqCounter ASC;";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA."<br>";
	
	if ($nrowA > 0)
	{
		$tcnt=0;
		echo "<table class=\"outer\" width=\"100%\">\n";
		echo "		<td colspan=\"3\" class=\"gray\" align=\"left\"><b>Cash in Bank: GL".$_GET['s']."</b></td>\n";
		echo "		<td colspan=\"3\" class=\"gray\" align=\"right\"><b>".date("m/d/Y h:i A",time())." PST</b></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Tran Date</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Description</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Batch</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Debit</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Credit</b></td>\n";
		echo "	</tr>\n";
		
		while ($rowA = mssql_fetch_array($resA))
		{
			$tcnt++;
			if ($tcnt%2)
			{
				$tbg = "wh_und";
			}
			else
			{
				$tbg = "gray_und";
			}
			
			$crd	="";
			$deb	="";
			//print_r($rowA);
			//echo $tcnt."<br>";
			
			if ($rowA['PostingAmount'] < 0)
			{
				$deb = number_format($rowA['PostingAmount'], 2, '.', '');
			}
			else
			{
				$crd = number_format($rowA['PostingAmount'], 2, '.', '');
			}
			
			echo "	<tr>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".$tcnt.".</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"center\">".date("m/d/Y",strtotime($rowA['TransactionDate']))."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"left\">".$rowA['RefDescription']."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"center\">".$rowA['BatchNumber']."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".$deb."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".$crd."</td>\n";
			echo "	</tr>\n";
		}
		
		echo "</table>\n";
	}
	else
	{
		//echo "<b> No Transactions in the Last ".$txt." days.";
	}
		
	//print_r($row);
}

function drill_cb_indold()
{
	if (!isset($_SESSION['securityid']) && $_GET['a'] != 0)
	{
		die('You do not have appropriate Access for this resource.');
	}
	
	if (empty($_GET['s']) || empty($_GET['a']))
	{
		die('Invalid Request 1.');
	}
	
	if (md5($_GET['a']) != $_GET['x'])
	{
		die('Invalid Request 2.');
	}
	
	$cdate = date("m/d/Y",time());
	//$pdate = date("m/d/Y",(time() - 2592000));
	$pdate = date("m/d/Y",(time() - 5184000)); // 60 day window
	
	$qry = "SELECT * FROM ZE_Stats..divtocomp WHERE division='".$_GET['a']."' and company='".$_GET['c']."';";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	//if ($mdiv==1)
	//{
	//	$qryA = "SELECT ISNULL(SUM(PostingAmount),0) AS pstamt FROM MAS_".trim($ccode)."..GL5_DetailPosting WHERE AccountNumber LIKE '".$glacc.$cdiv."%';";
	//}
	//else
	//{
		$qryA = "SELECT TransactionDate,RefDescription,BatchNumber,PostingAmount FROM MAS_".$row['company']."..GL5_DetailPosting WHERE AccountNumber LIKE '".$_GET['s'].$row['division']."%' AND TransactionDate BETWEEN '".$pdate."' AND '".$cdate."' ORDER BY TransactionDate ASC;";
	//}

	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	//echo $qryA."<br>";
	
	if ($nrowA > 0)
	{
		$tcnt=0;
		//echo $nrowA."<br>";
		
		echo "<table class=\"outer\" width=\"100%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"5\" class=\"gray\" align=\"left\"><b>Cash in Bank: GL".$_GET['s'].":</b> Last 60 Days of Activity</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Tran Date</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Description</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Batch</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Amount</b></td>\n";
		echo "	</tr>\n";
		
		while ($rowA = mssql_fetch_array($resA))
		{
			$tcnt++;
			//print_r($rowA);
			//echo $tcnt."<br>";
			
			echo "	<tr>\n";
			echo "		<td class=\"wh_und\" align=\"right\">".$tcnt.".</td>\n";
			echo "		<td class=\"wh_und\" align=\"center\">".date("m/d/Y",strtotime($rowA['TransactionDate']))."</td>\n";
			echo "		<td class=\"wh_und\" align=\"left\">".$rowA['RefDescription']."</td>\n";
			echo "		<td class=\"wh_und\" align=\"center\">".$rowA['BatchNumber']."</td>\n";
			echo "		<td class=\"wh_und\" align=\"right\">".number_format($rowA['PostingAmount'], 2, '.', '')."</td>\n";
			echo "	</tr>\n";
		}
		
		echo "</table>\n";
	}
	else
	{
		echo "<b> No Transactions in the Last 30 days.";
	}
		
	//print_r($row);
}

function drill_pd_ind()
{
	error_reporting(E_ALL);
	
	//echo "PD Drill Down<br>";
	$hostname	=	"192.168.100.45";
	$dbname		=	"jest"; #the name of the database
	$username	=	"jestadmin"; #a valid username
	$password	=	"into99black"; #a password for the username
	
	mssql_connect($hostname,$username,$password) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($dbname) or die("Table unavailable");	
	
	$qry = "SELECT * FROM recognized_digs WHERE mdiv='".$_GET['a']."' AND trandate BETWEEN '".$_GET['d0']."' AND '".$_GET['d1']."' AND reno=0 ORDER by jid ASC;";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	//echo $qry."<br>";
	
	if ($nrow > 0)
	{
		echo "<table class=\"outer\" width=\"100%\">\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\" colspan=\"7\" align=\"left\"><b>Recognized Pools Dug:</b> ".$_GET['d0']." - ".$_GET['d1']."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Job ID</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Customer</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>508L Date</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Contract Amt</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>Cost</b></td>\n";
		echo "		<td class=\"ltgray_und\" align=\"center\"><b>TGP</b></td>\n";
		echo "	</tr>\n";
		
		$tjid=0;
		$tvcs=0;
		$tdcc=0;
		while ($row = mssql_fetch_array($res))
		{
			$tjid++;
         
			if ($tjid%2)
			{
				$tbg = "gray_und";
			}
			else
			{
				$tbg = "wh_und";
			}
         
			$gp=$row['vcs']-$row['dcc'];
			echo "	<tr>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".$tjid.".</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"center\">".$row['jid']."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"left\">".$row['cst']."</td>\n";
         //echo "		<td class=\"".$tbg."\" align=\"left\">Xxxx Xxxxxxxx</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"center\">".date("m/d/Y",strtotime($row['trandate']))."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".number_format($row['vcs'])."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".number_format($row['dcc'])."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".number_format($gp)."</td>\n";
			echo "	</tr>\n";
			$tvcs=$tvcs+$row['vcs'];
			$tdcc=$tdcc+$row['dcc'];
		}

		$tgp=$tvcs-$tdcc;
		echo "	<tr>\n";
		echo "		<td class=\"wh_und\" align=\"right\" colspan=\"4\">&nbsp</td>\n";
		echo "		<td class=\"wh_und\" align=\"right\">".number_format($tvcs)."</td>\n";
		echo "		<td class=\"wh_und\" align=\"right\">".number_format($tdcc)."</td>\n";
		echo "		<td class=\"wh_und\" align=\"right\">".number_format($tgp)."</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

function drill_ov_ind()
{
	$tpost=0;
	$qryA = "SELECT * FROM MAS_".$_GET['c']."..GL5_DetailPosting WHERE AccountNumber='".$_GET['a']."' AND TransactionDate BETWEEN '".$_GET['d0']."' AND '".$_GET['d1']."' ORDER by TransactionDate ASC;";
	$resA = mssql_query($qryA);
	$nrowA = mssql_num_rows($resA);

	//echo $qryA."<br>";

	if ($nrowA > 0)
	{
		$qryAa = "SELECT * FROM MAS_".$_GET['c']."..GL1_Accounts WHERE AccountNumber='".$_GET['a']."';";
		$resAa = mssql_query($qryAa);
		$rowAa = mssql_fetch_array($resAa);

		$qryAb = "SELECT CompanyName FROM MAS_".$_GET['c']."..SY0_CompanyParameters WHERE CompanyCode=convert(varchar,'".$_GET['c']."');";
		$resAb = mssql_query($qryAb);
		$rowAb = mssql_fetch_array($resAb);

		echo "<table class=\"outer\" width=\"100%\">\n";
		echo "	<tr>\n";
		echo "		<td colspan=\"5\" class=\"gray\">\n";
		echo "			<table width=\"100%\">\n";
		echo "				<tr>\n";
      //echo "					<td class=\"gray\" align=\"left\"><b>Company:</b></td><td class=\"gray\" align=\"left\">XXXXXXXXXXXXXXX (XXX)</td>\n";
		echo "					<td class=\"gray\" align=\"left\"><b>Company:</b></td><td class=\"gray\" align=\"left\">".$rowAb['CompanyName']." (".$_GET['c'].")</td>\n";
		echo "					<td class=\"gray\" align=\"right\"><b>Dates:</b></td><td class=\"gray\" align=\"left\">".date("m/d/Y",strtotime($_GET['d0']))." - ".date("m/d/Y",strtotime($_GET['d1']))."</td>\n";
		echo "         			</tr>\n";
		echo "				<tr>\n";
		echo "					<td class=\"gray\" align=\"left\"><b>Account:</b></td><td class=\"gray\" align=\"left\">".$rowAa['AccountDescription']."</td>\n";
		echo "					<td class=\"gray\" align=\"right\"><b>Acct #:</b></td><td class=\"gray\" align=\"left\">".$_GET['a']."</td>\n";
		echo "         			</tr>\n";
		echo "         		</table>\n";
		echo "		</td>\n";
		echo "         </tr>\n";
		echo "	<tr>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Date</b></td>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Source</b></td>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Description</b></td>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Vendor Ref</b></td>\n";
		echo "		<td class=\"gray\" align=\"center\"><b>Posting Amount</b></td>\n";
		echo "         </tr>\n";

		$vendorname="";
		$ccnt=0;
		while ($rowA = mssql_fetch_array($resA))
		{
			$ccnt++;
			if ($ccnt%2)
			{
				$tbg = "wh_und";
			}
			else
			{
				$tbg = "gray_und";
			}
			
			if ($rowA['SourceJournal']=="AP" || $rowA['SourceJournal']=="PR")
			{
				$srd	=explode(" ",substr($rowA['RefDescription'],2));
				$qryB = "SELECT VendorName FROM MAS_".$_GET['c']."..AP1_VendorMaster WHERE VendorNumber='".$srd[0]."';";
				$resB = mssql_query($qryB);
				$nrowB = mssql_num_rows($resB);

				//echo $qryB."<br>";
				if ($nrowB > 0)
				{
					$rowB = mssql_fetch_array($resB);

					$vendorname=$rowB['VendorName'];
				}
			}

			echo "	<tr>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".date("m/d/Y",strtotime($rowA['TransactionDate']))."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"center\">".$rowA['SourceJournal']."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"left\">".$rowA['RefDescription']."</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"left\">".$vendorname."</td>\n";
         //echo "		<td class=\"".$tbg."\" align=\"left\">Xxxx Xxxxxxx</td>\n";
			//echo "		<td class=\"".$tbg."\" align=\"left\">XXXXXXXXX</td>\n";
			echo "		<td class=\"".$tbg."\" align=\"right\">".number_format($rowA['PostingAmount'], 2, '.', '')."</td>\n";
			echo "	</tr>\n";
			$vendorname="";
			$tpost=$tpost+$rowA['PostingAmount'];
		}

		echo "	<tr>\n";
		echo "		<td class=\"wh_und\" align=\"right\"></td>\n";
		echo "		<td class=\"wh_und\" align=\"center\"></td>\n";
		echo "		<td class=\"wh_und\" align=\"center\"></td>\n";
		echo "		<td class=\"wh_und\" align=\"right\"><b>Total</b></td>\n";
		echo "		<td class=\"wh_und\" align=\"right\">".number_format($tpost, 2, '.', '')."</td>\n";
		echo "	</tr>\n";
		echo "</table>\n";
	}
}

?>