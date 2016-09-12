<?php

error_reporting(E_ALL);
include ("..\connect_db.php");

function chkrequest_gen()
{
	header('Content-type: application/pdf');
	
	define('FPDF_FONTPATH','../FPDF/font/');
	require('../FPDF/fpdf.php');

	class PDF extends FPDF
	{
		function chkReq($officeid,$date,$header,$reptid)
		{
			$qryAa = "SELECT officeid,name,acctfee,pacctfee,consfee,code FROM offices WHERE officeid='".$officeid."';";
			$resAa = mssql_query($qryAa);
			$rowAa = mssql_fetch_array($resAa);

			$this->SetFont('Arial','B',10);

			$itext1	="Check Request";
			$itext2	="Office:";
			$itext3	="Req Date:";
			$itext4	="Vendor ID";
			$itext5	=$rowAa['name'];
			$itext6	=substr($date,0,10);
			$itext7	="BHNMI";
			$itext8	="Pay To:";
			$itext9	="Blue Haven National Management";
			//$itextn	=PageNo();

			//$this->Cell(35,4,'',0,0,'L');
			//$this->Cell(80,4,'',0,0,'L');
			//$this->Cell(25,4,'',0,0,'R');
			//$this->Cell(40,4,$itextn,'B',0,'L');
			//$this->Ln();
			$this->Cell(35,4,$itext1,0,0,'L');
			$this->Cell(80,4,'',0,0,'L');
			$this->Cell(25,4,$itext2,0,0,'R');
			$this->Cell(40,4,$itext5,'B',0,'L');
			$this->Ln();
			$this->Cell(35,4,$itext4,0,0,'C');
			$this->Cell(20,4,$itext8,0,0,'R');
			$this->Cell(60,4,$itext9,'B',0,'L');
			$this->Cell(25,4,'',0,0,'R');
			$this->Cell(40,4,'',0,0,'L');
			$this->Ln();
			$this->Cell(35,4,$itext7,'B',0,'C');
			$this->Cell(20,4,'',0,0,'R');
			$this->Cell(60,4,'','B',0,'L');
			$this->Cell(25,4,'',0,0,'C');
			$this->Cell(40,4,'',0,0,'L');
			$this->Ln();

			//$this->Cell(180,8,'',0,0,'C');
			$this->Ln();

			$this->SetFont('Arial','',7);

			//CR Table
			//Column widths
			$w=array(8,18,18,28,28,18,18,18,8,18);

			//Header
			for($i=0;$i<count($header);$i++)
			{
				$this->Cell($w[$i],6,$header[$i],1,0,'C');
			}
			$this->Ln();

			$qryA  = "SELECT * FROM digreport_main WHERE officeid='".$officeid."' AND id='".$reptid."';";
			$resA = mssql_query($qryA);
			$rowA = mssql_fetch_array($resA);
			$nrowA = mssql_num_rows($resA);

			if ($nrowA > 0)
			{
				$ry			=0;
				$cj			=0;
				$cf			=0;
				$jtext		=explode(",",$rowA['jtext']);
				$jtc		=count($jtext);
				$jtotal		=0;
				$jtotal1	=0;
				$jtotal2	=0;
				$jtotal3	=0;


				//$itext10	="ADM"."606";
				$itext10	="ADM".$rowA['rept_mo'].substr($rowA['rept_yr'],2,2);
				$itext11	="ACC".$rowA['rept_mo'].substr($rowA['rept_yr'],2,2);
				$itext12	="505L";
				$itext13	="506L";
				$itext14	="CON".$rowA['rept_mo'].substr($rowA['rept_yr'],2,2);
				$itext15	="507L";
				$itext16	="712-".$rowAa['code'];

				//Royalty Fee
				//if ($rowA['no_digs']!=0)
				if ($rowA['no_digs']!=0 || $rowA['no_rens']!=0)
				{
					foreach ($jtext as $nj1=>$vj2)
					{
						$ctext2=explode(":",$vj2);
						
						if (isset($ctext2[20]) && $ctext2[20]==1)
						{
							$ry++;
						}
						
						$cj++;
						$this->Cell($w[0],4,$cj.".",1,0,'R');
						$this->Cell($w[1],4,$itext10,1,0,'C');
						$this->Cell($w[2],4,$itext6,1,0,'C');
						$this->Cell($w[3],4,'',1,0,'L');
						$this->Cell($w[4],4,$ctext2[9],1,0,'L');
						$this->Cell($w[5],4,$ctext2[0],1,0,'C');
						$this->Cell($w[6],4,$itext12,1,0,'C');
						$this->Cell($w[7],4,$ctext2[3],1,0,'R');
						$this->Cell($w[8],4,'C',1,0,'C');
						$this->Cell($w[9],4,'',1,0,'R');
						$this->Ln();
						$jtotal1=$jtotal1+$ctext2[3];
						$jtotal=$jtotal+$ctext2[3];
					}

					$fjtotal1=number_format($jtotal1, 2, '.', ',');
					$this->Cell($w[0],4,'',1,0,'R');
					$this->Cell($w[1],4,'',1,0,'C');
					$this->Cell($w[2],4,'',1,0,'C');
					$this->Cell($w[3],4,'Total Admin Fee',1,0,'L');
					$this->Cell($w[4],4,'',1,0,'L');
					$this->Cell($w[5],4,'',1,0,'C');
					$this->Cell($w[6],4,'',1,0,'C');
					$this->Cell($w[7],4,'',1,0,'R');
					$this->Cell($w[8],4,'',1,0,'C');
					$this->Cell($w[9],4,$fjtotal1,1,0,'R');
					$this->Ln();

					//Accting Fee
					foreach ($jtext as $nj3=>$vj3)
					{
						$ctext3=explode(":",$vj3);
						if (empty($ctext3[20]) || $ctext3[20]==0) // Test for Renovations
						{
							if ($ctext3[4]!=0)
							{
								$cj++;
								$this->Cell($w[0],4,$cj.".",1,0,'R');
								$this->Cell($w[1],4,$itext11,1,0,'C');
								$this->Cell($w[2],4,$itext6,1,0,'C');
								$this->Cell($w[3],4,'',1,0,'L');
								$this->Cell($w[4],4,$ctext3[9],1,0,'L');
								$this->Cell($w[5],4,$ctext3[0],1,0,'C');
								$this->Cell($w[6],4,$itext13,1,0,'C');
								$this->Cell($w[7],4,$ctext3[4],1,0,'R');
								$this->Cell($w[8],4,'C',1,0,'C');
								$this->Cell($w[9],4,'',1,0,'R');
								$this->Ln();
								$jtotal2=$jtotal2+$ctext3[4];
								$jtotal=$jtotal+$ctext3[4];
							}
						}
					}
				}

				// Monthly Acct Fee, if any
				if ($rowA['macct_fee']!=0 || $rowAa['acctfee']!=0)
				{
					if ($rowA['macct_fee']!=0)
					{
						$macctfee=$rowA['macct_fee'];
					}
					else
					{
						$macctfee=$rowAa['acctfee'];
					}

					$fmacctfee=number_format($macctfee, 2, '.', ',');

					$cj++;
					$this->Cell($w[0],4,$cj.".",1,0,'R');
					$this->Cell($w[1],4,$itext11,1,0,'C');
					$this->Cell($w[2],4,$itext6,1,0,'C');
					$this->Cell($w[3],4,'Monthly Accting Fee',1,0,'L');
					$this->Cell($w[4],4,'',1,0,'L');
					$this->Cell($w[5],4,$itext16,1,0,'C');
					$this->Cell($w[6],4,'',1,0,'C');
					$this->Cell($w[7],4,$fmacctfee,1,0,'R');
					$this->Cell($w[8],4,'C',1,0,'C');
					$this->Cell($w[9],4,'',1,0,'R');
					$this->Ln();
					$jtotal2=$jtotal2+$macctfee;
					$jtotal=$jtotal+$macctfee;
				}

				if ($jtotal2!=0)
				{
					$fjtotal2=number_format($jtotal2, 2, '.', ',');
					$this->Cell($w[0],4,'',1,0,'R');
					$this->Cell($w[1],4,'',1,0,'C');
					$this->Cell($w[2],4,'',1,0,'C');
					$this->Cell($w[3],4,'Total Accting Fee',1,0,'L');
					$this->Cell($w[4],4,'',1,0,'L');
					$this->Cell($w[5],4,'',1,0,'C');
					$this->Cell($w[6],4,'',1,0,'C');
					$this->Cell($w[7],4,'',1,0,'R');
					$this->Cell($w[8],4,'',1,0,'C');
					$this->Cell($w[9],4,$fjtotal2,1,0,'R');
					$this->Ln();
				}

				//Consult
				foreach ($jtext as $nj4=>$vj4)
				{
					$ctext4=explode(":",$vj4);
					if (empty($ctext4[20]) || $ctext4[20]==0) // Test for Renovations
					{
						if ($rowAa['consfee']!=0)
						{
							$cj++;
							$cf++;
							$this->Cell($w[0],4,$cj.".",1,0,'R');
							$this->Cell($w[1],4,$itext14,1,0,'C');
							$this->Cell($w[2],4,$itext6,1,0,'C');
							$this->Cell($w[3],4,'',1,0,'L');
							$this->Cell($w[4],4,$ctext4[9],1,0,'L');
							$this->Cell($w[5],4,$ctext4[0],1,0,'C');
							$this->Cell($w[6],4,$itext15,1,0,'C');
							$this->Cell($w[7],4,$rowAa['consfee'],1,0,'R');
							$this->Cell($w[8],4,'C',1,0,'C');
							//$this->Cell($w[9],4,'',1,0,'R');
							$this->Cell($w[9],4,'',1,0,'R');
							$this->Ln();
							$jtotal3=$jtotal3+$rowAa['consfee'];
							$jtotal=$jtotal+$rowAa['consfee'];
						}
					}
				}

				if ($cf > 0)
				{
					$fjtotal3=number_format($jtotal3, 2, '.', ',');
					$this->Cell($w[0],4,'',1,0,'R');
					$this->Cell($w[1],4,'',1,0,'C');
					$this->Cell($w[2],4,'',1,0,'C');
					$this->Cell($w[3],4,'Total Consulting Fee',1,0,'L');
					$this->Cell($w[4],4,'',1,0,'L');
					$this->Cell($w[5],4,'',1,0,'C');
					$this->Cell($w[6],4,'',1,0,'C');
					$this->Cell($w[7],4,'',1,0,'R');
					$this->Cell($w[8],4,'',1,0,'C');
					$this->Cell($w[9],4,$fjtotal3,1,0,'R');
					$this->Ln();
				}

				$fjtotal=number_format($jtotal, 2, '.', ',');
				//Closure line
				$this->Cell($w[0],4,'',0,0,'R');
				$this->Cell($w[1],4,'',0,0,'C');
				$this->Cell($w[2],4,'',0,0,'C');
				$this->Cell($w[3],4,'',0,0,'L');
				$this->Cell($w[4],4,'Total Payment',1,0,'L');
				$this->Cell($w[5],4,'','B',0,'C');
				$this->Cell($w[6],4,'','B',0,'C');
				$this->Cell($w[7],4,'','B',0,'R');
				$this->Cell($w[8],4,'','B',0,'C');
				$this->Cell($w[9],4,$fjtotal,1,0,'R');
				$this->Ln();
				//$this->Cell(array_sum($w),0,'','T');
			}
		}

		function BuildDoc($officeid,$date,$reptid)
		{
			//Creates chkReq
			$header=array('','Invoice #','Date','Description','Customer','Job #','Phase','Amount','C','Total');
			$this->AddPage();
			$this->chkReq($officeid,$date,$header,$reptid);
		}
	}

	$pdf=new PDF();
	$officeid=$_REQUEST['officeid'];
	$reptid=$_REQUEST['rept_id'];
	$date=date("m-d-Y-H-i", time());
	//$date=time();
	$fdateset=time();
	$seed=time();
	//$fname=$officeid."_".$seed."_".$date."chkrequest.pdf";
	$fname=$fdateset."chkrequest.pdf";
	$sdir="./";
	$file=$sdir.$fname;
	$pdf->SetCreator('JMS Doc Generator');
	$pdf->SetAuthor('JMS Sys');
	$pdf->SetTitle('Check Request');
	$pdf->SetAutoPageBreak('true', 10);
	$pdf->BuildDoc($officeid,$date,$reptid);
	$pdf->Output($fname,'D');

	$qryZ  = "UPDATE digreport_main SET chkreqid='".$_REQUEST['chkreqid']."',chkreqdate=getdate(),locked=1 ";
	$qryZ .= "WHERE officeid='".$_REQUEST['officeid']."' AND id='".$_REQUEST['rept_id']."';";
	$resZ = mssql_query($qryZ);
}



chkrequest_gen();

?>