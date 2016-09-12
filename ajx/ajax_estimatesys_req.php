<?php
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);
//echo 'START<br>';

include ('../subs/ajax_common_func.php');

//get_Request_Info();

function get_RetailCategories($oid)
{
	$out=array();
	
	$qryA  = "SELECT officeid as oid,pb_code,active FROM [jest]..[offices] WHERE officeid=".(int) $oid.";";
	$resA  = mssql_query($qryA);
	$rowA  = mssql_fetch_array($resA);
	
	// Builds a list of exisiting categories in the retail accessory table by office
	$qryB  = "SELECT DISTINCT a.catid,a.name,a.seqn ";
	$qryB .= "FROM [jest]..[AC_cats] AS a INNER JOIN [jest]..[".trim($rowA['pb_code'])."acc] AS b ";
	$qryB .= "ON a.catid=b.catid ";
	$qryB .= "AND a.officeid=".(int) $oid." ";
	$qryB .= "AND a.active=1 ";
	$qryB .= "AND a.privcat!=1 ";
	$qryB .= "ORDER BY a.seqn ASC;";
	$resB = mssql_query($qryB);

	while ($rowB = mssql_fetch_array($resB))
	{
		$out[]=array('catid'=>$rowB['catid'],'catname'=>$rowB['name']);
	}
	
	return $out;
}

function get_RetailItems($oid,$catid,$estid)
{
	$out=array();
	
	$estdata = estdata($oid,$estid);

	$qryA  = "SELECT officeid as oid,pb_code,active FROM [jest]..[offices] WHERE officeid=".(int) $oid.";";
	$resA  = mssql_query($qryA);
	$rowA  = mssql_fetch_array($resA);
	
	$qryB   = "SELECT ";
	$qryB  .= "A.id as rid,A.qtype,A.item as ritem,A.rp as rprice,A.mtype,A.commtype,A.crate,A.quan_calc as qcalc, ";
	$qryB  .= "(SELECT abrv FROM jest..mtypes WHERE mid=A.mtype) as mabrv ";
	$qryB  .= "FROM [".trim($rowA['pb_code'])."acc] as A ";
	$qryB  .= "WHERE A.officeid=".(int) $oid." AND A.catid=".(int) $catid." AND A.qtype!=33 AND A.disabled!='1' ORDER BY A.seqn;";
	$resB  = mssql_query($qryB);
	$nrowB = mssql_num_rows($resB);

	if ($nrowB > 0)
	{
		while ($rowB=mssql_fetch_array($resB))
		{
			$out[$rowB['rid']]=array(
									 'rid'=>$rowB['rid'],
									 'qtype'=>$rowB['qtype'],
									 'ritem'=>$rowB['ritem'],
									 'mabrv'=>$rowB['mabrv'],
									 'rprice'=>number_format($rowB['rprice'],2,'.',''),
									 'commtype'=>$rowB['commtype'],
									 'qcalc'=>$rowB['qcalc'],
									 'crate'=>$rowB['crate'],
									 'estinfo'=>array(),
									 'bidinfo'=>array(),
									 );
			
			if (array_key_exists($rowB['rid'],$estdata))
			{
				$out[$rowB['rid']]['estinfo']=$estdata[$rowB['rid']];
			}
			
			if ($rowB['qtype']==33)
			{
				$qryC  = "SELECT bidinfo FROM est_bids WHERE officeid=".(int) $oid." AND estid=".(int) $estid." AND bidaccid=".(int) $rowB['rid'].";";
				$resC  = mssql_query($qryC);
				$rowC  = mssql_fetch_array($resC);
				$nrowC = mssql_num_rows($resC);
				
				if ($nrowC==1)
				{
					$out[$rowB['rid']]['bidinfo']=array('bidinfo'=>$rowC['bidinfo']);
				}
			}
		}
	}
	
	return $out;
}

function estdata($oid,$estid)
{
	$out=array();
	
	if ($estid!=0)
	{		
		$qry = "SELECT estdata FROM est_acc_ext WHERE officeid=".(int) $oid." AND estid=".(int) $estid.";";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		
		if (strlen($row['estdata']) > 2)
		{
			$e=explode(",",trim($row['estdata']));
			foreach($e as $n1 => $v1)
			{
				$i=explode(":",$v1);
				$out[$i[0]]=array('id'=>$i[0],'qn'=>$i[2],'rp'=>number_format(($i[2]*$i[3]),2,'.',''),'cd'=>$i[4]);
			}
		}
	}
	
	return $out;
}

function select_base_pool()
{
	$viewarray=$_SESSION['viewarray'];
	$qrypre	= "SELECT pft_sqft FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre	= mssql_query($qrypre);
	$rowpre	= mssql_fetch_array($respre);

	//display_array($viewarray);

	if ($rowpre['pft_sqft']=="p")
	{
		$psize=$viewarray['ps1'];
		$ptext="pft";
	}
	else
	{
		$psize=$viewarray['ps2'];
		$ptext="sqft";
	}

	if ($viewarray['renov']==1)
	{
		$rbtable="rbpricep_renov";
	}
	else
	{
		$rbtable="rbpricep";
	}

	$qry	= "SELECT SUM(quan1) as quan1t FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."';";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);

	if ($row['quan1t'] > 0)
	{
		$bi	=0;
		$bq	=0;
		$bq1=0;
		$bp	=0;
		$bc	=0;

		$qry1	= "SELECT * FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."' ORDER BY quan ASC;";
		$res1	= mssql_query($qry1);

		while ($row1 = mssql_fetch_array($res1))
		{
			if ($psize >= $row1['quan'] && $psize <= $row1['quan1'])
			{
				//echo "HIT";
				$bi	=$row1['id'];
				$bq	=$row1['quan'];
				$bq1=$row1['quan1'];
				$bp	=$row1['price'];
				$bc	=$row1['comm'];
			}
		}
	}
	else
	{
		$qry1	= "SELECT * FROM ".$rbtable." WHERE officeid='".$_SESSION['officeid']."' and quan='".$psize."';";
		$res1	= mssql_query($qry1);
		$row1 	= mssql_fetch_array($res1);
		$nrow1 	= mssql_num_rows($res1);

		if ($nrow1 > 0)
		{
			$bi	=$row1['id'];
			$bq	=$row1['quan'];
			$bq1=$row1['quan1'];
			$bp	=$row1['price'];
			$bc	=$row1['comm'];
		}
		else
		{
			$bi	=0;
			$bq	=0;
			$bq1=0;
			$bp	=0;
			$bc	=0;
		}
	}

	$bpar=array(0=>$bi,1=>$bq,2=>$bq1,3=>$bp,4=>$bc,5=>$psize,6=>$ptext,7=>$row['quan1t']);
	return $bpar;
}

function calc_internal_area($pft,$sqft,$shallow,$middle,$deep)
{
	$ia=((($shallow+$middle+$deep)/3)*$pft)+$sqft;

	if (is_float($ia))
	{
		$ia=round($ia);
	}
	return $ia;
}

function calc_gallons($pft,$sqft,$shallow,$middle,$deep)
{
	$gals=((($shallow+$middle+$deep)/3)*$sqft)*7.5;

	if (is_float($gals))
	{
		$gals=round($gals);
	}
	return $gals;
}

function get_CustomerInfo($oid,$cid)
{
	$out='';
	
	$qry = "SELECT C.* FROM jest..cinfo AS C WHERE C.officeid=".(int) $oid." and C.cid=".(int) $cid.";";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);
	
	if ($nrow > 0)
	{
		$row = mssql_fetch_array($res);

		$out.="<table width=\"210px\" class=\"transnb\" border=0>\n";
		$out.="	<tr>\n";
		$out.="		<td align=\"right\" width=\"80\"><b>Name</b></td>\n";
		$out.="		<td align=\"left\">".str_replace('\\','',$row['cfname'])." ".str_replace('\\','',$row['clname'])."</td>\n";
		$out.="	</tr>\n";
		$out.="	<tr>\n";
		$out.="		<td align=\"right\" width=\"80\"><b>Last Name</b></td>\n";
		$out.="	<td align=\"left\">".str_replace('\\','',$row['clname'])."</td>\n";
		$out.="	</tr>\n";
		$out.="	<tr>\n";
		$out.="		<td align=\"right\"><b>Site Addr</b></td>\n";
		$out.="		<td align=\"left\">".$row['saddr1']."</td>\n";
		$out.="	</tr>\n";
		$out.="	<tr>\n";
		$out.="		<td align=\"right\"><b>City</b></td>\n";
		$out.="		<td align=\"left\">".$row['scity']."</td>\n";
		$out.="	</tr>\n";
		$out.="	<tr>\n";
		$out.="		<td align=\"right\"><b>State</b></td>\n";
		$out.="		<td align=\"left\">".$row['sstate']."</td>\n";
		$out.="	</tr>\n";
		$out.="  <tr>\n";
		$out.="  	<td align=\"right\"><b>Zip</b></td>\n";
		$out.="  	<td align=\"left\">".$row['szip1']."</td>\n";
		$out.="  </tr>\n";
		$out.="  <tr>\n";
		$out.="  	<td align=\"right\"><b>Home Phone</b></td>\n";
		$out.="  	<td align=\"left\"></td>\n";
		$out.="  </tr>\n";
		$out.="</table>\n";
	}
	else
	{
		$out.='Customer not found';
	}
	
	return $out;
}

function get_BuildBase($oid)
{
	$qry = "SELECT officeid,name,def_per,def_sqft,def_s,def_m,def_d,pft_sqft,finan_from FROM jest..offices WHERE officeid=".(int) $oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
	
	$qryA = "SELECT quan FROM jest..rbpricep WHERE officeid=".(int) $oid." ORDER BY quan ASC";
	$resA = mssql_query($qryA);
	
	$qryAa = "SELECT SUM(quan1) as quan1t FROM jest..rbpricep WHERE officeid=".(int) $oid.";";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);
	
	$qryB = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC";
	$resB = mssql_query($qryB);
	
	if ($row['pft_sqft']=="p")
	{
		$defmeas=$row['def_per'];
	}
	else
	{
		$defmeas=$row['def_sqft'];
	}
	
	/*
	$bpset		=select_base_pool();
	$set_deck   =deckcalc($row['pft'],$row['sqft']);
	$incdeck    =round($set_deck[0]);
	$set_ia     =calc_internal_area($row['pft'],$row['sqft'],$row['shal'],$row['mid'],$row['deep']);
	$set_gals   =calc_gallons($row['pft'],$row['sqft'],$row['shal'],$row['mid'],$row['deep']);
	*/
	$out ="				<label for=\"renov\">Pool Type:</label> <select id=\"renov\"><option value=\"0\">New</option><option value=\"1\">Renovation</option></select><br>\n";
	
	if ($row['pft_sqft']=="p")
	{
		$out.="<label for=\"peri\">Pool Perimeter</label>";
	}
	else
	{
		$out.="<label for=\"peri\">Pool Surface Area</label>";
	}
	
	if ($rowAa['quan1t'] > 0)
	{
		if ($row['pft_sqft']=="p")
		{
			$out.=" <input type=\"text\" id=\"peri\" size=\"5\" maxlength=\"5\" value=\"".$row['def_sqft']."\"><br>\n";
		}
		else
		{
			$out.=" <input type=\"text\" id=\"peri\" size=\"5\" maxlength=\"5\" value=\"".$row['def_per']."\"><br>\n";
		}
	}
	else
	{
		$out.="<select id=\"peri\">\n";

		while($rowA = mssql_fetch_array($resA))
		{
			if ($rowA['quan']==$defmeas)
			{
				$out.="		<option value=\"".$rowA['quan']."\" SELECTED>".$rowA['quan']."</option>\n";
			}
			else
			{
				$out.="		<option value=\"".$rowA['quan']."\">".$rowA['quan']."</option>\n";
			}
		}

		$out.="</select><br>\n";
	}
	
	if ($row['pft_sqft']=="p")
	{
		$out.="<label for=\"peri\">Surface Area</label>";
	}
	else
	{
		$out.="<label for=\"peri\">Perimeter</label>";
	}
	
	if ($row['pft_sqft']=="p")
	{
		$out.=" <input type=\"text\" name=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$row['def_sqft']."\"><br>\n";
	}
	else
	{
		$out.=" <input type=\"text\" name=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$row['def_per']."\"><br>\n";
	}
	
	$out.="			<label for=\"shal\">Depth</label> <input type=\"text\" id=\"shal\" size=\"1\" maxlength=\"3\" value=\"".$row['def_s']."\">\n";
	$out.="					<input type=\"text\" id=\"mid\" size=\"1\" maxlength=\"3\" value=\"".$row['def_m']."\">\n";
	$out.="					<input type=\"text\" id=\"deep\" size=\"1\" maxlength=\"3\" value=\"".$row['def_d']."\"><br>\n";
	$out.="			<label for=\"erun\">Electrical Run</label> <input type=\"text\" id=\"erun\" size=\"1\" maxlength=\"3\" value=\"0\"><br>\n";
	$out.="			<label for=\"prun\">Plumbing Run</label> <input type=\"text\" id=\"prun\" size=\"1\" maxlength=\"3\" value=\"0\"><br>\n";
	$out.="			<label for=\"deck\">Total Deck</label> <input type=\"text\" name=\"deck\" size=\"5\" maxlength=\"5\" value=\"0\"><br>\n";
	/*
	$out.="			Spa Type <select name=\"spa_type\">\n";

	while($rowB = mssql_fetch_array($resB))
	{
		$out.="							<option value=\"".$rowB['typeid']."\">".$rowB['name']."</option>\n\n";
	}

	$out.="				</select><br>\n";
	$out.="				Spa Perimeter <input type=\"text\" id=\"spa2\" size=\"5\" maxlength=\"5\" value=\"0\"><br>\n";
	$out.="				Spa Surface Area <input type=\"text\" id=\"spa3\" size=\"5\" maxlength=\"5\" value=\"0\"><br>\n";
	*/
	
	return $out;
}


function basematrix()
{
	$data='';
	if (isset($_SESSION['securityid']) and !empty($_SESSION['securityid']) and $_SESSION['securityid']!=0)
	{
		include ('../connect_db.php');
		
		ajaxEventProc(0);
		
		if (isset($_REQUEST['call']) and $_REQUEST['call']=='get_RetailCategories')
		{
			$data=get_RetailCategories($_REQUEST['goid']);
		}
		elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='get_RetailItems')
		{
			$data=get_RetailItems($_REQUEST['goid'],$_REQUEST['catid'],$_REQUEST['estid']);
		}
		elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='get_CustomerInfo')
		{
			$data=get_CustomerInfo($_REQUEST['oid'],$_REQUEST['cid']);
		}
		elseif (isset($_REQUEST['call']) and $_REQUEST['call']=='get_BuildBase')
		{
			$data=get_BuildBase($_REQUEST['oid']);
		}
		else
		{
			$data=array('errcnt'=>1,'error'=>"Malformed Request (" . __LINE__ . ")");
		}
	}
	else
	{
		$data=array('errcnt'=>1,'error'=>"Unauthorized (" . __LINE__ . ")");
	}
	
	if (isset($_REQUEST['optype']) and $_REQUEST['optype']=='json')
	{
		echo json_encode($data);
	}
	elseif (isset($_REQUEST['optype']) and $_REQUEST['optype']=='test')
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	else
	{
		echo $data;
	}
}

basematrix();

?>