<?php

function JobData($oid,$jid)
{
    //echo 'Start<br>';
    //echo __FUNCTION__.': '.$pm.'<br>';
	$db=array('hostname'=>'CORP-DB02','username'=>'sa','password'=>'date1995','dbname'=>'jest');
    $si=array();
	$cst=array();
    $jadd=0;
	
	$link=mssql_connect($db['hostname'],$db['username'],$db['password']) or trigger_error("Could not connect to MSSQL database", E_USER_ERROR);
	mssql_select_db($db['dbname']) or die("Table unavailable");
	
	$qry  = "select	c.cid,c.jobid,c.cfname,c.clname, ";
	$qry .= "	c.saddr1,c.scity,c.sstate,c.szip1,c.chome, ";
	$qry .= "	(select digdate from jobs where officeid=c.officeid and jobid=c.jobid) as digdate, ";
	$qry .= "	(select renov from jobs where officeid=c.officeid and jobid=c.jobid) as renov, ";
	$qry .= "	(select njobid from jobs where officeid=c.officeid and jobid=c.jobid) as njobid, ";
	$qry .= "	(select contractamt from jdetail where officeid=c.officeid and jobid=c.jobid and jadd=0) as camt, ";
	$qry .= "	(select pb_code from offices where officeid=c.officeid) as officecode, ";
	$qry .= "	(select mas_div from security where securityid=c.securityid) as mas_div, ";
	$qry .= "	(select rmas_div from security where securityid=c.securityid) as rmas_div, ";
	$qry .= "	(select fname from security where securityid=c.securityid) as srfname, ";
	$qry .= "	(select lname from security where securityid=c.securityid) as srlname ";
	$qry .= "from cinfo as c where c.officeid=".$oid." and c.jobid='".$jid."';";
	$res  = mssql_query($qry);
	$row  = mssql_fetch_array($res);
	
	if ($row['renov']==1 && $row['rmas_div']!=0)
	{
		$dispjobid  =disp_mas_div_jobid($row['rmas_div'],$row['njobid']);
	}
	else
	{
		$dispjobid  =disp_mas_div_jobid($row['mas_div'],$row['njobid']);
	}
	
	$cst=array(
			   'cid'=>$row['cid'],
			   'offcode'=>$row['officecode'],
			   'jid'=>$dispjobid[0],
			   'cfname'=>$row['cfname'],
			   'clname'=>$row['clname'],
			   'saddr1'=>$row['saddr1'],
			   'scity'=>$row['scity'],
			   'sstate'=>$row['sstate'],
			   'szip1'=>$row['szip1'],
			   'chome'=>$row['chome'],
			   'type'=>$row['renov'] ? 'Renovation':'New Build',
			   'salesrep'=>trim($row['srfname']).' '.trim($row['srlname']),
			   'salesrepi'=>substr(trim($row['srfname']),0,1).substr(trim($row['srlname']),0,1),
			   'digdate'=>date('m/d/Y',strtotime($row['digdate'])),
			   'camt'=>number_format($row['camt'], 2, '.', ''),
			   'jobname'=>htmlspecialchars_decode($row['clname']).':'.$dispjobid[0]
			   );
	
    $qryAa = "SELECT * FROM jdetail WHERE officeid=".$oid." AND jobid='".$jid."' AND jadd=".$jadd.";";
	$resAa = mssql_query($qryAa);
	$rowAa = mssql_fetch_array($resAa);
    $nrowAa= mssql_num_rows($resAa);
    
    if ($nrowAa==1)
    {
        include('./ajax_calc_func.php');
        
        $qryC = "SELECT phsid,phscode FROM phasebase;";
        $resC = mssql_query($qryC);
        
        while ($rowC = mssql_fetch_array($resC))
        {
            $phs_ar[$rowC['phsid']]=$rowC['phscode'];
        }

        $qryAb = "SELECT * FROM jobs WHERE officeid=".$oid." AND jobid='".$jid."';";
        $resAb = mssql_query($qryAb);
        $rowAb = mssql_fetch_array($resAb);
    
        $qryBa = "SELECT officeid as oid,pb_code,def_per,def_sqft,def_s,def_m,def_d,stax FROM offices WHERE officeid=".$oid.";";
        $resBa = mssql_query($qryBa);
        $rowBa = mssql_fetch_array($resBa);
        
        $qryBb = "SELECT securityid as sid,sidm FROM security WHERE securityid=".$rowAb['securityid'].";";
        $resBb = mssql_query($qryBb);
        $rowBb = mssql_fetch_array($resBb);
        
        $jd = array(
                    'camt'=>$rowAa['contractamt'],
                    'pft'=>$rowAa['pft'],
                    'sqft'=>$rowAa['sqft'],
                    'tzone'=>$rowAa['tzone'],
                    'shal'=>$rowAa['shal'],
                    'mid'=>$rowAa['mid'],
                    'deep'=>$rowAa['deep'],
                    'spa_pft'=>$rowAa['spa_pft'],
                    'spa_sqft'=>$rowAa['spa_sqft'],
                    'spa_type'=>$rowAa['spa_type'],
                    'pb_code'=>$rowBa['pb_code'],
                    'sid'=>$rowBb['sid'],
                    'sidm'=>$rowBb['sidm'],
					'royrel'=>0,
					'jobname'=>$cst['jobname'],
					'jid'=>$cst['jid']
                    );
        
        $jd['iarea']=calc_internal_area($jd['pft'],$jd['sqft'],$jd['shal'],$jd['mid'],$jd['deep']);
        $js['gals']=calc_gallons($jd['pft'],$jd['sqft'],$jd['shal'],$jd['mid'],$jd['deep']);
    
        $jd['def_off_set']=array(
                              'pft'=>$rowBa['def_per'],
                              'sqft'=>$rowBa['def_sqft'],
                              'shal'=>$rowBa['def_s'],
                              'mid'=>$rowBa['def_m'],
                              'deep'=>$rowBa['def_d'],
                              'stax'=>$rowBa['stax']
                              );
        
        $cl = $rowAa['costdata_l'];
        $cm = $rowAa['costdata_m'];
        $bl = $rowAa['bcostdata_l'];
        $bm = $rowAa['bcostdata_m'];
        $pl = preg_replace("/,\Z/","",$rowAa['pcostdata_l']);
        $pm = preg_replace("/,\Z/","",$rowAa['pcostdata_m']);
        
        $lab=start_labor_calc($oid,$jid,$jadd,$jd,$cl,$bl,$pl,$si);
        $mat=start_material_calc($oid,$jid,$jadd,$jd,$cm,$bm,$pm,$si);
        $add=start_addendum_calc($oid,$jid,$jd,$si);
        
        $sout=array('cst'=>$cst,'lab'=>$lab,'mat'=>$mat,'add'=>$add);
        //$sout=array('lab'=>$lab);
        //$sout=array('mat'=>$mat);
        //$sout=array('add'=>$add);
        return $sout;
    }
    else
    {
        header('HTTP/1.1 400 Bad Request');
    }
}

function exportJob($output,$type,$opts)
{
	//echo '<pre>';
	//print_r($output);
	//echo '</pre>';
	//exit;
	
	if (is_array($output) and ($type=='Customer' or $type=='Cost'))
	{		
		$fext='.csv';
		
		if ($type=='Customer')
		{
			$filename='CustomerInfo-'. $output['cst']['jid'] .'-'.time().$fext;
		}
		else
		{
			$filename='JobCost-'. $output['cst']['jid'] .'-'.time().$fext;
		}

		//header('Content-Type: text/csv; charset=utf-8');

		header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-type: application-download");
        header("Content-Disposition: attachment; filename=".$filename);
        header("Content-Transfer-Encoding: binary");
		
		$f = fopen('php://output','w');
		
		if ($type=='Customer')
		{
			$costout=0;
			
			foreach($output as $n1 => $v1)
			{
				if ($n1!='cst')
				{
					foreach($v1 as $v2)
					{
						foreach($v2 as $v3)
						{
							$costout+=$v3['amt'];
						}
					}
				}
			}
			
			fputcsv($f, array('Customer Number', 'Office Code', 'Job Number', 'First Name','Last Name','Address','City','State','Zip','Phone','Job Type','Contract Amount','Cost Total'), ',', '"');
			fputcsv($f,
						array(
							  'cid'=>$output['cst']['cid'],
							  'oid'=>$output['cst']['offcode'],
							  'jid'=>$output['cst']['jid'],
							  'firstname'=>htmlspecialchars_decode($output['cst']['cfname']),
							  'lastname'=>htmlspecialchars_decode($output['cst']['clname']),
							  'addr1'=>htmlspecialchars_decode($output['cst']['saddr1']),
							  'city'=>$output['cst']['scity'],
							  'state'=>$output['cst']['sstate'],
							  'zip'=>$output['cst']['szip1'],
							  'phone'=>$output['cst']['chome'],
							  'type'=>$output['cst']['type'],
							  'retailprice'=>number_format($output['cst']['camt'], 2, '.', ''),
							  'cost_amt'=>number_format($costout, 2, '.', '')
							  ),
						',', '"');
		}
		else
		{
			fputcsv($f, array('Customer','Item', 'Description', 'Quantity', 'Price'), ',', '"');
			
			$i=0;
			foreach($output as $n1 => $v1)
			{
				if ($n1!='cst')
				{
					foreach($v1 as $v2)
					{
						foreach($v2 as $v3)
						{
							if ($v3['amt']!=0)
							{
								$i++;
								//fputcsv($f, array($v3['jobname'],$v3['phase'].'-'.$i,$v3['item'],$v3['quan'],$v3['amt']), ',', '"');
								fputcsv($f, array($v3['jobname'],$v3['phase'].'-'.$i,$v3['item'],1,$v3['amt']), ',', '"');
							}
						}
					}
				}
			}
		}
		
		fclose($f);
	}
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