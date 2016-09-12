<?php

    error_reporting(E_ALL);
    
	session_start();
	
	if (isset($_SESSION['securityid']) && isset($_REQUEST['sid']))
	{
		include ('../connect_db.php');
		
        $MRG_ar =array();
        $tbal   =0;
        $begbal =0;
        $cnt    =0;
        
        $qryA = "   SELECT
                        *,
                        (select name from jest..offices where officeid=S.officeid) as oname,
                        (select label_masoff_code from offices where officeid=S.officeid) as olabel
                    FROM
                        security as S
                    WHERE
                        securityid='".$_REQUEST['sid']."';";
        $resA = mssql_query($qryA);
        $rowA = mssql_fetch_array($resA);
        $nrowA= mssql_num_rows($resA);
        
        // Get Commission/Sales Beginning Balance
        $qryB  = "SET ANSI_WARNINGS ON ";
        $qryB .= "exec jest..tlh_SRBeginBalance @sid=".$_REQUEST['sid'].";";
        $resB  = mssql_query($qryB);
        $rowB  = mssql_fetch_array($resB);
        $nrowB = mssql_num_rows($resB);
        
        // Get Commission/Sales Data
        $qry1  = "SET ANSI_WARNINGS ON ";
        $qry1 .= "exec jest..tlh_SalesRepPage @sid=".$_REQUEST['sid'].",@pid=".$_REQUEST['sid'].";";
        $res1  = mssql_query($qry1);
        $nrow1 = mssql_num_rows($res1);
		
		$qryC  = "select * from secondaryids where secid=".$_REQUEST['sid'].";";
        $resC  = mssql_query($qryC);
        $nrowC = mssql_num_rows($resC);
		
		
		if ($nrow1 > 0)
        {
			$icnt=0;
			while ($row1 = mssql_fetch_array($res1))
            {
				$icnt++;
				$cont_ar[]=array(
									'icnt'=>$icnt,
									'Division'=>$row1['Division'],
									'SalesPersonCode'=>$row1['SalesPersonCode'],
									'SequenceNumber'=>$row1['SequenceNumber'],
									'Type'=>$row1['Type'],
									'Date'=>$row1['Date'],
									'ReferenceNumber'=>$row1['ReferenceNumber'],
									'JournalSource'=>$row1['JournalSource'],
									'Description'=>$row1['Description'],
									'Amount'=>$row1['Amount'],
									'JobNumber'=>$row1['JobNumber'],
								);
			}
			
			//$cont_json=json_encode($cont_ar);
		}
	
		//$nrowC=0;
	    // Runs if Secondary IDs exist and are enabled from the Report Interface
        if ($nrowC > 0)
        {
			$qry2  = "select * from secondaryids where securityid=".$_REQUEST['sid'].";";
			$res2  = mssql_query($qry2);
			$nrow2 = mssql_num_rows($res2);
			//echo "INCSEC: ".$nrow2."<BR>";
			if ($nrow2 > 0)
			{
				while ($row2 = mssql_fetch_array($res2))
				{
					$qry2A = "   SELECT
									*,
									(select name from jest..offices where officeid=S.officeid) as oname,
									(select label_masoff_code from offices where officeid=S.officeid) as olabel
								FROM
									security as S
								WHERE
									securityid='".$row2['secid']."';";
					$res2A = mssql_query($qry2A);
					$row2A = mssql_fetch_array($res2A);
					$nrow2A= mssql_num_rows($res2A);
					
					// Get Commission/Sales Data
					$qry21  = "SET ANSI_WARNINGS ON ";
					$qry21 .= "exec jest..tlh_SalesRepPage @sid=".$row2['secid'].",@pid=".$row2['securityid'].";";
					$res21  = mssql_query($qry21);
					$nrow21 = mssql_num_rows($res21);
					//echo "INCSEC: ".$row2['secid']."<BR>";
					
					if ($nrow21 > 0)
					{
						while ($row21 = mssql_fetch_array($res21))
						{
							//$cont_ar[]=$row21;
							$cont_ar[]=array(
								'icnt'=>$icnt,
								'Division'=>$row21['Division'],
								'SalesPersonCode'=>$row21['SalesPersonCode'],
								'SequenceNumber'=>$row21['SequenceNumber'],
								'Type'=>$row21['Type'],
								'Date'=>$row21['Date'],
								'ReferenceNumber'=>$row21['ReferenceNumber'],
								'JournalSource'=>$row21['JournalSource'],
								'Description'=>$row21['Description'],
								'Amount'=>$row21['Amount'],
								'JobNumber'=>$row21['JobNumber'],
							);
						}
					}
				}
			}
        }

		$cont_json=json_encode($cont_ar);
		echo $cont_json;
		/*echo '<br />';
		$cont_djson=json_decode($cont_json);
		echo '<pre>';
		print_r( $cont_djson );
		echo '</pre>';*/
	}
?>