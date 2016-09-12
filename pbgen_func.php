<?php

function pb_today($t)
{
	$i=0;
	$qry = "SELECT cdate from pb_exports WHERE officeid='".$_SESSION['officeid']."' AND ftype='".$t."';";
	$res = mssql_query($qry);

	while ($row= mssql_fetch_array($res))
	{
		if (substr($row['cdate'],0,2)==date("m", time())&& substr($row['cdate'],3,2)==date("d", time())&&substr($row['cdate'],6,4)==date("Y", time()))
		{
			$i++;
		}
	}
	return $i;
}

function list_avail_ret()
{
	//$pb_today=pb_today(0);
	
	$pb_today=0;

	if ($pb_today > 0)
	{
		$pbset=" DISABLED";
	}
	else
	{
		$pbset="";
	}

	$qry = "SELECT * from pb_exports WHERE officeid='".$_SESSION['officeid']."' AND ftype='0' ORDER BY cdate DESC;";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	echo "<table class=\"outer\" width=\"50%\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" colspan=\"3\" align=\"left\"><b>Price Book Generation History for ".$_SESSION['offname']."</b></td>\n";
	echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"exportpb\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"gen_ret\">\n";
	echo "					<td class=\"ltgray_und\" align=\"right\">Remove Commission:\n";
	echo "						<input type=\"checkbox\" class=\"checkboxwht\" name=\"nocomm\" value=\"1\" title=\"Check this box to prevent Commission Data from displaying on the Pricebook\">\n";
	echo "                  				<input class=\"buttondkgry\" type=\"submit\" value=\"Generate New PB\"$pbset>\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\"><b>Date Created</b></td>\n";
	echo "					<td class=\"ltgray_und\"><b>Created By</b></td>\n";
	echo "					<td class=\"ltgray_und\"><b>Office</b></td>\n";
	echo "					<td class=\"ltgray_und\"></td>\n";
	echo "				</tr>\n";

	if ($nrow > 0)
	{
		while($row= mssql_fetch_array($res))
		{
			$qryA = "SELECT fname,lname FROM security WHERE securityid='".$row['securityid']."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);

			$qryB = "SELECT code,name FROM offices WHERE officeid='".$row['officeid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			echo "				<tr>\n";
			echo "					<td class=\"wht_und\">".$row['cdate']."</td>\n";
			echo "					<td class=\"wht_und\">".$rowA['lname'].", ".$rowA['fname']."</td>\n";
			echo "					<td class=\"wht_und\">".$rowB['name']."</td>\n";
			//echo "					<td class=\"wht_und\">".$rowB['code']."</td>\n";
			echo "						<form action=\".\pb_exports\\".$row['filename']."\" method=\"post\" target=\"new\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\" >\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"exportpb\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"view_ret\">\n";
			echo "					<td class=\"wht_und\" align=\"right\">\n";
			echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
			echo "					</td>\n";
			echo "						</form>\n";
			echo "				</tr>\n";
		}
	}
	else
	{
		echo "				<tr>\n";
		echo "					<td class=\"wht_und\" colspan=\"5\"><b>No Price Books Generated</b></td>\n";
		echo "				</tr>\n";
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function list_avail_cst()
{
	$pb_today=pb_today(1);

	//$pb_today=0;

	if ($pb_today > 0)
	{
		$pbset=" DISABLED";
	}
	else
	{
		$pbset="";
	}

	$qry = "SELECT * from pb_exports WHERE officeid='".$_SESSION['officeid']."' AND ftype='1' ORDER BY cdate DESC;";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	echo "<table class=\"outer\" width=\"50%\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" colspan=\"3\" align=\"left\"><b>Cost Price Sheet Generation History for ".$_SESSION['offname']."</b></td>\n";
	echo "						<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"exportpb\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"gen_cst\">\n";
	echo "					<td class=\"ltgray_und\" align=\"right\">\n";
	echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"Generate New Cost\"$pbset>\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\"><b>Date Created</b></td>\n";
	echo "					<td class=\"ltgray_und\"><b>Created By</b></td>\n";
	echo "					<td class=\"ltgray_und\"><b>Office</b></td>\n";
	echo "					<td class=\"ltgray_und\"></td>\n";
	echo "				</tr>\n";

	if ($nrow > 0)
	{
		while($row= mssql_fetch_array($res))
		{
			$qryA = "SELECT fname,lname FROM security WHERE securityid='".$row['securityid']."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);

			$qryB = "SELECT code,name FROM offices WHERE officeid='".$row['officeid']."';";
			$resB = mssql_query($qryB);
			$rowB = mssql_fetch_array($resB);

			echo "				<tr>\n";
			echo "					<td class=\"wht_und\">".$row['cdate']."</td>\n";
			echo "					<td class=\"wht_und\">".$rowA['lname'].", ".$rowA['fname']."</td>\n";
			echo "					<td class=\"wht_und\">".$rowB['name']."</td>\n";
			//echo "					<td class=\"wht_und\">".$rowB['code']."</td>\n";
			echo "						<form action=\".\pb_exports\\".$row['filename']."\" method=\"post\" target=\"new\">\n";
			echo "						<input type=\"hidden\" name=\"action\" value=\"reports\" >\n";
			echo "						<input type=\"hidden\" name=\"call\" value=\"exportpb\">\n";
			echo "						<input type=\"hidden\" name=\"subq\" value=\"view_cst\">\n";
			echo "					<td class=\"wht_und\" align=\"right\">\n";
			echo "                  <input class=\"buttondkgry\" type=\"submit\" value=\"View\">\n";
			echo "					</td>\n";
			echo "						</form>\n";
			echo "				</tr>\n";
		}
	}
	else
	{
		echo "				<tr>\n";
		echo "					<td class=\"wht_und\" colspan=\"5\"><b>No Cost Price Sheets Generated</b></td>\n";
		echo "				</tr>\n";
	}

	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function generate_ret()
{
	define('FPDF_FONTPATH','./FPDF/font/');
	require('./FPDF/fpdf.php');
	$MAS=$_SESSION['pb_code'];

	class PDF extends FPDF
	{
		//Load data
		function GetCats($officeid)
		{
			$MAS=$_SESSION['pb_code'];
			$qryA  = "SELECT DISTINCT a.catid AS cat,a.seqn AS seq ";
			$qryA .= "	FROM AC_cats AS a INNER JOIN [".$MAS."acc] AS b ";
			$qryA .= "	ON a.catid=b.catid ";
			$qryA .= "	AND a.officeid='".$officeid."' ";
			$qryA .= "	AND a.active=1 ";
			$qryA .= "	ORDER BY a.seqn ASC; ";
			$resA = mssql_query($qryA);

			$catseq=array();
			while ($rowA = mssql_fetch_array($resA))
			{
				$catseq[]=$rowA['cat'];
			}
			return $catseq;
		}

		function CoverSheet($officeid,$date)
		{
			$this->SetFont('Arial','B',14);
			$qryA = "SELECT * FROM offices WHERE officeid='".$officeid."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);

			$itext1	="BLUE HAVEN POOLS";
			$itext2	="RETAIL PRICE BOOK";
			$itext3	="FOR";
			$itext4	=$rowA['name'];
			$itext5	=substr($date,0,10);

			//$this->Image('../images/bh_logo.jpg',70,15,65);
			//$this->Ln();
			//$this->Ln();
			//$this->Ln();
			$this->Cell(175,6,$itext1,'',0,'C');
			$this->Ln();
			$this->Cell(175,6,$itext2,'',0,'C');
			$this->Ln();
			$this->Cell(175,6,$itext3,'',0,'C');
			$this->Ln();
			$this->Cell(175,6,$itext4,'',0,'C');
			$this->Ln();
			$this->Cell(175,6,$itext5,'',0,'C');
			$this->Ln();
			//$this->Cell(175,0,'','T');
		}

		function PeriPrices($officeid,$date)
		{
			$w=array(20,60,20);

			if (isset($_POST['nocomm']) && $_POST['nocomm']==1)
			{
				$header=array('Quantity','Price','');
			}
			else
			{
				$header=array('Quantity','Price','Comm');
			}

			// Category Title
			$this->SetFont('Arial','B',9);
			$this->Cell(65,7,'Pool Perimeter Prices',0,0,'C');
			$this->Cell(20,7,substr($date,0,10),0,0,'C');
			$this->Cell(15,7,$this->PageNo(),0,0,'C');
			$this->Ln();

			for($i=0;$i<count($header);$i++)
			{
				$this->Cell($w[$i],7,$header[$i],1,0,'C');
			}
			$this->Ln();

			$qryA  = "SELECT * FROM rbpricep WHERE officeid='".$officeid."' ORDER BY quan ASC;";
			$resA = mssql_query($qryA);

			while ($rowA = mssql_fetch_array($resA))
			{
				$quan=$rowA['quan'];
				$price=number_format($rowA['price'], 2, '.', ',');

				if (isset($_POST['nocomm']) && $_POST['nocomm']==1)
				{
					$comm="";
				}
				else
				{
					$comm=number_format($rowA['comm'], 2, '.', ',');
				}

				$this->Cell($w[0],4,$quan,'LR',0,'R');
				$this->Cell($w[1],4,$price,'LR',0,'R');
				$this->Cell($w[2],4,$comm,'LR',0,'R');
				$this->Ln();
			}
			//Closure line
			$this->Cell(array_sum($w),0,'','T');
		}

		function PriceBookTable($header,$officeid,$cid,$date)
		{
			$MAS=$_SESSION['pb_code'];
			$qryA  = "SELECT name FROM AC_cats WHERE officeid='".$officeid."' AND catid='".$cid."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);

			// Category Title
			$this->SetFont('Arial','B',9);
			$this->Cell(130,7,$rowA['name'],0,0,'L');
			$this->Cell(30,7,substr($date,0,10),0,0,'C');
			$this->Cell(15,7,$this->PageNo(),0,0,'C');
			$this->Ln();

			//Column widths
			$w=array(15,100,15,30,15);
			//Header
			for($i=0;$i<count($header);$i++)
			{
				$this->Cell($w[$i],7,$header[$i],1,0,'C');
			}
			$this->Ln();

			$qryB  = "SELECT * FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND catid='".$cid."' AND disabled!=1 ORDER BY seqn ASC;";
			$resB = mssql_query($qryB);

			while ($rowB = mssql_fetch_array($resB))
			{
				$qryBa = "SELECT abrv FROM mtypes WHERE mid='".$rowB['mtype']."';";
				$resBa = mssql_query($qryBa);
				$rowBa = mssql_fetch_array($resBa);

				if ($rowB['qtype']==32)
				{
					$this->SetFont('Arial','B',8);
					$price='';
					$code='';
					$unit='';
					$comm='';
				}
				else
				{
					$this->SetFont('Arial','',8);
					$price=number_format($rowB['rp'], 2, '.', ',');
					$code=$rowB['aid'];
					$unit=$rowBa['abrv'];

					if (isset($_POST['nocomm']) && $_POST['nocomm']==1)
					{
						$comm='';
					}
					else
					{
						if ($rowB['commtype']!=0)
						{
							$comm=$rowB['crate'];
						}
						else
						{
							$comm='';
						}
					}
				}

				$this->Cell($w[0],4,chop($code),'LR',0,'R');
				$this->Cell($w[1],4,chop($rowB['item']),'LR',0,'L');
				$this->Cell($w[2],4,chop($unit),'LR',0,'R');
				$this->Cell($w[3],4,chop($price),'LR',0,'R');
				$this->Cell($w[4],4,chop($comm),'LR',0,'R');
				$this->Ln();

				if (strlen(chop($rowB['atrib1'])) > 0)
				{
					$this->SetFont('Arial','',7);
					$this->Cell($w[0],4,'','LR');
					$this->Cell($w[1],4,chop($rowB['atrib1']),'LR');
					$this->Cell($w[2],4,'','LR');
					$this->Cell($w[3],4,'','LR');
					$this->Cell($w[4],4,'','LR');
					$this->Ln();
				}

				if (strlen(chop($rowB['atrib2'])) > 0)
				{
					$this->SetFont('Arial','',7);
					$this->Cell($w[0],4,'','LR');
					$this->Cell($w[1],4,chop($rowB['atrib2']),'LR');
					$this->Cell($w[2],4,'','LR');
					$this->Cell($w[3],4,'','LR');
					$this->Cell($w[4],4,'','LR');
					$this->Ln();
				}

				if (strlen(chop($rowB['atrib3'])) > 0)
				{
					$this->SetFont('Arial','',7);
					$this->Cell($w[0],4,'','LR');
					$this->Cell($w[1],4,chop($rowB['atrib3']),'LR');
					$this->Cell($w[2],4,'','LR');
					$this->Cell($w[3],4,'','LR');
					$this->Cell($w[4],4,'','LR');
					$this->Ln();
				}
			}
			//Closure line
			$this->Cell(array_sum($w),0,'','T');

		}

		function BuildDoc($officeid,$cats,$date)
		{
			//Creates Cover Sheet
			$this->AddPage();
			$this->CoverSheet($officeid,$date);

			//Creates Perimeter Price List
			$this->AddPage();
			$this->PeriPrices($officeid,$date);

			//Creates Pricebook, 1 Category per Page.
			
			if (isset($_POST['nocomm']) && $_POST['nocomm']==1)
			{
				$header=array('Code','Item','Units','Price','');
			}
			else
			{
				$header=array('Code','Item','Units','Price','Comm');
			}
			
			//$header=array('Code','Item','Units','Price','Comm');
			foreach($cats as $cid)
			{
				$this->AddPage();
				$this->PriceBookTable($header,$officeid,$cid,$date);
			}
		}
	}

	$pdf=new PDF();
	$officeid=$_SESSION['officeid'];
	$cats=$pdf->GetCats($officeid);
	$date=date("m-d-Y-H-i-s", time());
	$seed=time();
	$fname=$officeid."_".$seed."_".$date.".pdf";
	$sdir="./pb_exports/";
	$file=$sdir.$fname;
	$pdf->SetCreator('Blue Haven PB Generator');
	$pdf->SetAuthor('Blue Haven Estimating System');
	$pdf->SetTitle('Blue Haven Retail Price Book');
	$pdf->SetAutoPageBreak('true', 10);
	$pdf->BuildDoc($officeid,$cats,$date);
	$pdf->Output($file,'F');
	//$pdf->Output();

	$qryZ  = "INSERT INTO pb_exports (cdate,securityid,officeid,filename) ";
	$qryZ .= "VALUES ('".$date."','".$_SESSION['securityid']."','".$_SESSION['officeid']."','".$fname."');";
	$resZ = mssql_query($qryZ);

	list_avail_ret();
}

function generate_cst()
{
	define('FPDF_FONTPATH','./FPDF/font/');
	require('./FPDF/fpdf.php');
	$MAS=$_SESSION['pb_code'];

	class PDF extends FPDF
	{
		//Load data
		function GetPhs($officeid)
		{
			$MAS=$_SESSION['pb_code'];
			$qryA  = "SELECT phsid FROM phasebase WHERE costing=1 AND phstype='V' OR phstype='S' ORDER BY seqnum ASC;";
			$resA = mssql_query($qryA);

			$phsseq=array();
			while ($rowA = mssql_fetch_array($resA))
			{
				$phsseq[]=$rowA['phsid'];
			}
			return $phsseq;
		}

		function CoverSheet($officeid,$date)
		{
			$this->SetFont('Arial','B',14);
			$qryA = "SELECT * FROM offices WHERE officeid='".$officeid."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);

			$itext1	="BLUE HAVEN POOLS";
			$itext2	="COST PRICESHEET";
			$itext3	="FOR";
			$itext4	=$rowA['name'];
			$itext5	=substr($date,0,10);

			//$this->Image('../images/bh_logo.jpg',70,15,65);
			//$this->Ln();
			//$this->Ln();
			//$this->Ln();
			$this->Cell(175,6,$itext1,'',0,'C');
			$this->Ln();
			$this->Cell(175,6,$itext2,'',0,'C');
			$this->Ln();
			$this->Cell(175,6,$itext3,'',0,'C');
			$this->Ln();
			$this->Cell(175,6,$itext4,'',0,'C');
			$this->Ln();
			$this->Cell(175,6,$itext5,'',0,'C');
			$this->Ln();
			//$this->Cell(175,0,'','T');
		}

		function PriceBookTable($header,$officeid,$phsid,$date)
		{
			$MAS=$_SESSION['pb_code'];
			//echo "TEST";
			$qryA  = "SELECT phsname,phscode FROM phasebase WHERE phsid='".$phsid."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);

			// Phase Title
			$this->SetFont('Arial','B',9);
			$this->Cell(125,7,"Phase: ".$rowA['phsname']." (".$rowA['phscode'].")",0,0,'L');
			$this->Cell(30,7,"Generated on: ".substr($date,0,10),0,0,'C');
			$this->Cell(15,7,'',0,0,'C');
			$this->Cell(20,7,$this->PageNo(),0,0,'C');
			$this->Ln();

			//Column widths
			$w=array(15,110,25,15,20);
			//Header
			for($i=0;$i<count($header);$i++)
			{
				$this->Cell($w[$i],7,$header[$i],1,0,'C');
			}
			$this->Ln();

			$qryB  = "SELECT * FROM [".$MAS."accpbook] WHERE officeid='".$officeid."' AND phsid='".$phsid."' ORDER BY accid ASC;";
			$resB = mssql_query($qryB);

			while ($rowB = mssql_fetch_array($resB))
			{
				$qryBa = "SELECT abrv FROM mtypes WHERE mid='".$rowB['mtype']."';";
				$resBa = mssql_query($qryBa);
				$rowBa = mssql_fetch_array($resBa);

				$qryBb = "SELECT cname FROM qtypes WHERE qid='".$rowB['qtype']."';";
				$resBb = mssql_query($qryBb);
				$rowBb = mssql_fetch_array($resBb);

				$this->SetFont('Arial','',8);
				$this->Cell($w[0],4,chop($rowB['accid']),'LTR',0,'R');
				$this->Cell($w[1],4,chop($rowB['item']),'LTR',0,'L');
				$this->Cell($w[2],4,chop($rowBb['cname']),'LTR',0,'L');
				$this->Cell($w[3],4,chop($rowBa['abrv']),'LTR',0,'L');
				$this->Cell($w[4],4,chop(number_format($rowB['bprice'], 2, '.', ',')),'LTR',0,'R');
				$this->Ln();

				//if ($rowB['rinvid']!=0)
				//{
				$qryBc = "SELECT officeid,rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rowB['id']."';";
				$resBc = mssql_query($qryBc);
				$nrowBc= mssql_num_rows($resBc);

				if ($nrowBc > 0)
				{
					while($rowBc = mssql_fetch_array($resBc))
					{
						$qryBd = "SELECT id,item,aid,qtype,mtype FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$rowBc['rid']."';";
						$resBd = mssql_query($qryBd);
						$rowBd = mssql_fetch_array($resBd);

						$qryBe = "SELECT abrv FROM mtypes WHERE mid='".$rowBd['mtype']."';";
						$resBe = mssql_query($qryBe);
						$rowBe = mssql_fetch_array($resBe);

						$qryBf = "SELECT cname FROM qtypes WHERE qid='".$rowBd['qtype']."';";
						$resBf = mssql_query($qryBf);
						$rowBf = mssql_fetch_array($resBf);

						$this->Cell($w[0],4,"- ".chop($rowBd['aid']),'LR',0,'R');
						$this->Cell($w[1],4,"- ".chop($rowBd['item']),'LR',0,'L');
						$this->Cell($w[2],4,"- ".chop($rowBf['cname']),'LR',0,'L');
						$this->Cell($w[3],4,"- ".chop($rowBe['abrv']),'LR',0,'L');
						$this->Cell($w[4],4,'','LR',0,'R');
						$this->Ln();
					}
				}
				//}
			}
			//Closure line
			$this->Cell(array_sum($w),0,'','T');
		}

		function BuildDoc($officeid,$phs,$date)
		{
			//Creates Cover Sheet
			$this->AddPage();
			$this->CoverSheet($officeid,$date);

			////Creates Perimeter Price List
			//$this->AddPage();
			//$this->PeriPrices($officeid,$date);

			//echo "TEST1<BR>";
			//print_r($phs);
			//Creates Pricesheet, 1 Category per Page.
			//$header=array('Code','Item','Units','Price','Comm');
			$header=array('Code','Cost Item','Calc Type','Units','Price');
			foreach($phs as $phsid)
			{
				$this->AddPage();
				$this->PriceBookTable($header,$officeid,$phsid,$date);
			}
		}
	}

	$pdf=new PDF();
	$officeid=$_SESSION['officeid'];
	$phs=$pdf->GetPhs($officeid);
	$date=date("m-d-Y-H-i-s", time());
	$seed=time();
	$fname=$officeid."_".$seed."_".$date.".pdf";
	$sdir="./pb_exports/";
	$file=$sdir.$fname;
	$pdf->SetCreator('Blue Haven PB Generator');
	$pdf->SetAuthor('Blue Haven Estimating System');
	$pdf->SetTitle('Blue Haven Cost Pricesheet');
	$pdf->SetAutoPageBreak('true', 10);
	$pdf->BuildDoc($officeid,$phs,$date);
	$pdf->Output($file,'F');
	//$pdf->Output();

	$qryZ  = "INSERT INTO pb_exports (cdate,securityid,officeid,filename,ftype) ";
	$qryZ .= "VALUES ('".$date."','".$_SESSION['securityid']."','".$_SESSION['officeid']."','".$fname."','1');";
	$resZ = mssql_query($qryZ);

	list_avail_cst();
}

function delete_pb()
{

}

?>