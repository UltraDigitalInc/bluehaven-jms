<?php

error_reporting(E_ALL);

function generate_cst()
{
	header('Content-type: application/pdf');

	include ("..\connect_db.php");
	define('FPDF_FONTPATH','../FPDF/font/');
	require('../FPDF/fpdf.php');
	//$MAS=$_REQUEST['pb_code'];

	class PDF extends FPDF
	{
		//Load data
		function GetPhs($officeid)
		{
			$MAS=$_REQUEST['pb_code'];
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
			$MAS=$_REQUEST['pb_code'];
			//echo "TEST";
			$qryA  = "SELECT phsname,phscode FROM phasebase WHERE phsid='".$phsid."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);

			// Phase Title
			$fontsize=9+$_REQUEST['adjfont'];
			$this->SetFont('Arial','B',$fontsize);
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

				$fontsize=8+$_REQUEST['adjfont'];
				$this->SetFont('Arial','',$fontsize);
				$this->Cell($w[0],4,chop($rowB['accid']),'LTR',0,'R');
				$this->Cell($w[1],4,chop($rowB['item']),'LTR',0,'L');
				$this->Cell($w[2],4,chop($rowBb['cname']),'LTR',0,'L');
				$this->Cell($w[3],4,chop($rowBa['abrv']),'LTR',0,'L');
				$this->Cell($w[4],4,chop(number_format($rowB['bprice'], 2, '.', ',')),'LTR',0,'R');
				$this->Ln();

				//if ($rowB['rinvid']!=0)
				//{
				$qryBc = "SELECT officeid,rid,cid FROM [".$MAS."rclinks_l] WHERE officeid='".$_REQUEST['officeid']."' AND cid='".$rowB['id']."';";
				$resBc = mssql_query($qryBc);
				$nrowBc= mssql_num_rows($resBc);

				if ($nrowBc > 0)
				{
					while($rowBc = mssql_fetch_array($resBc))
					{
						$qryBd = "SELECT id,item,aid,qtype,mtype FROM [".$MAS."acc] WHERE officeid='".$_REQUEST['officeid']."' AND id='".$rowBc['rid']."';";
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
	$officeid=$_REQUEST['officeid'];
	$phs=$pdf->GetPhs($officeid);
	$date=date("m-d-Y-H-i-s", time());
	$seed=time();
	$fname=$officeid."_".$seed."_".$date.".pdf";
	$sdir="./pb_exports/";
	$file=$sdir.$fname;
	$pdf->SetCreator('JMS Cost Generator');
	$pdf->SetAuthor('JMS Sys');
	$pdf->SetTitle('Blue Haven Cost Sheet');
	$pdf->SetAutoPageBreak('true', 10);
	$pdf->BuildDoc($officeid,$phs,$date);
	$pdf->Output($file,'I');
}

function list_cost_pb()
{
	$qry = "SELECT * from pb_exports WHERE officeid='".$_SESSION['officeid']."' AND ftype='0' ORDER BY cdate DESC;";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

	echo "<table class=\"outer\" width=\"50%\">\n";
	echo "	<tr>\n";
	echo "		<td class=\"gray\">\n";
	echo "			<table width=\"100%\">\n";
	echo "				<tr>\n";
	echo "					<td class=\"ltgray_und\" colspan=\"3\" align=\"left\"><b>Cost Sheet Generation for ".$_SESSION['offname']."</b></td>\n";
	echo "						<form action=\"./pdf/costpb_gen_func.php\" method=\"post\" target=\"_new\">\n";
	echo "						<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
	echo "						<input type=\"hidden\" name=\"call\" value=\"showcstpb\">\n";
	echo "						<input type=\"hidden\" name=\"subq\" value=\"view_cst\">\n";
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
	echo "					<td class=\"ltgray_und\" align=\"right\">\n";
	echo "                  				<input class=\"buttondkgry\" type=\"submit\" value=\"Generate Cost Sheet\">\n";
	echo "					</td>\n";
	echo "						</form>\n";
	echo "				</tr>\n";
	echo "			</table>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

if ($_REQUEST['subq']=="list_cst")
{
	list_cost_pb();
}
elseif ($_REQUEST['subq']=="view_cst")
{
	generate_cst();
}
else
{
	echo "Malformed Request";
}

?>