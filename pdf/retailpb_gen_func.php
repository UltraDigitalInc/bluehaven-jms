<?php

error_reporting(E_ALL);

//show_post_vars();

function generate_ret()
{
	header('Content-type: application/pdf');

	//echo "TEST<BR>";
	include ("..\connect_db.php");
	define('FPDF_FONTPATH','../FPDF/font/');
	require('../FPDF/fpdf.php');

	class PDF extends FPDF
	{
		//Load data
		
		function GetCats($officeid)
		{
			$MAS=$_REQUEST['pb_code'];
			//$MAS=$_REQUEST['pb_code'];
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
			$fontsize=9+$_REQUEST['adjfont'];
			if (isset($_REQUEST['nocomm']) && $_REQUEST['nocomm']==1)
			{
				$header=array('Quantity','Price','');
			}
			else
			{
				$header=array('Quantity','Price','Comm');
			}

			// Category Title
			$this->SetFont('Arial','B',$fontsize);
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

				if (isset($_REQUEST['nocomm']) && $_REQUEST['nocomm']==1)
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
			$fontsize=9+$_REQUEST['adjfont'];
			$MAS=$_REQUEST['pb_code'];
			$qryA  = "SELECT name FROM AC_cats WHERE officeid='".$officeid."' AND catid='".$cid."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);

			// Category Title
			$this->SetFont('Arial','B',$fontsize);
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
					$fontsize=8+$_REQUEST['adjfont'];
					$this->SetFont('Arial','B',$fontsize);
					$price='';
					$code='';
					$unit='';
					$comm='';
				}
				else
				{
					$fontsize=8+$_REQUEST['adjfont'];
					$this->SetFont('Arial','',$fontsize);
					$price=number_format($rowB['rp'], 2, '.', ',');
					$code=$rowB['aid'];
					$unit=$rowBa['abrv'];

					if (isset($_REQUEST['nocomm']) && $_REQUEST['nocomm']==1)
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

				$fontsize=7+$_REQUEST['adjfont'];
				
				if (strlen(chop($rowB['atrib1'])) > 0)
				{
					$this->SetFont('Arial','',$fontsize);
					$this->Cell($w[0],4,'','LR');
					$this->Cell($w[1],4,chop($rowB['atrib1']),'LR');
					$this->Cell($w[2],4,'','LR');
					$this->Cell($w[3],4,'','LR');
					$this->Cell($w[4],4,'','LR');
					$this->Ln();
				}

				if (strlen(chop($rowB['atrib2'])) > 0)
				{
					$this->SetFont('Arial','',$fontsize);
					$this->Cell($w[0],4,'','LR');
					$this->Cell($w[1],4,chop($rowB['atrib2']),'LR');
					$this->Cell($w[2],4,'','LR');
					$this->Cell($w[3],4,'','LR');
					$this->Cell($w[4],4,'','LR');
					$this->Ln();
				}

				if (strlen(chop($rowB['atrib3'])) > 0)
				{
					$this->SetFont('Arial','',$fontsize);
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

			if (isset($_REQUEST['nocomm']) && $_REQUEST['nocomm']==1)
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
	$officeid=$_REQUEST['officeid'];
	$cats=$pdf->GetCats($officeid);
	$date=date("m-d-Y-H-i-s", time());
	$seed=time();
	$fname=$officeid."_".$seed."_".$date.".pdf";
	$sdir="./pb_exports/";
	$file=$sdir.$fname;
	$pdf->SetCreator('JMS PB Generator');
	$pdf->SetAuthor('JMS Sys');
	$pdf->SetTitle('Blue Haven Retail Price Book');
	$pdf->SetAutoPageBreak('true', 10);
	$pdf->BuildDoc($officeid,$cats,$date);
	$pdf->Output($file,'I');
}

function list_retail_pb()
{
	$qry = "SELECT * from pb_exports WHERE officeid='".$_SESSION['officeid']."' AND ftype='0' ORDER BY cdate DESC;";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	echo "<table class=\"outer\" width=\"50%\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" colspan=\"3\" align=\"left\"><b>Price Book Generation for ".$_SESSION['offname']."</b></td>\n";
	echo "						<form action=\"./pdf/retailpb_gen_func.php\" method=\"post\" target=\"_new\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"showretpb\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"view_ret\">\n";
	echo "						<input type=\"hidden\" name=\"pb_code\" value=\"".$_SESSION['pb_code']."\">\n";
	echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "					<td class=\"ltgray_und\" align=\"right\">Adjust Font:\n";
	echo "						<select name=\"adjfont\">\n";
	echo "							<option value=\"2\">+2</option>\n";
	echo "							<option value=\"1\">+1</option>\n";
	echo "							<option value=\"0\" SELECTED>0</option>\n";
	echo "							<option value=\"-1\">-1</option>\n";
	echo "							<option value=\"-2\">-2</option>\n";
	echo "						</select>\n";
	echo "					</td>\n";
	echo "					<td class=\"ltgray_und\" align=\"right\">Remove Commission:\n";
	echo "						<input type=\"checkbox\" class=\"checkboxwh\" name=\"nocomm\" value=\"1\" title=\"Check this box to prevent Commission Data from displaying on the Pricebook\">\n";
	echo "					</td>\n";
	echo "					<td class=\"ltgray_und\" align=\"right\">\n";
	echo "                  				<input class=\"buttondkgry\" type=\"submit\" value=\"Generate Pricebook\">\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

if ($_REQUEST['subq']=="list_ret")
{
	list_retail_pb();
}
elseif ($_REQUEST['subq']=="view_ret")
{
	generate_ret();
}

?>