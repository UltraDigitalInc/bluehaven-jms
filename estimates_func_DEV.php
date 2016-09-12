<?php
if (CODEACCESS!=1){die('Direct Access not Permitted');}

function EstimateSearch()
{
	//ini_set('display_errors','On');
	$acclist=explode(",",$_SESSION['aid']);
	$qry = "SELECT * FROM leadstatuscodes WHERE active=2 ORDER BY name ASC;";
	$res = mssql_query($qry);

	$qry0 = "SELECT * FROM leadstatuscodes WHERE active=1 ORDER BY name ASC;";
	$res0 = mssql_query($qry0);

	$qry1 = "SELECT securityid,lname,fname,slevel FROM security WHERE officeid=".(int) $_SESSION['officeid']." order by SUBSTRING(slevel,13,1) desc,lname ASC;";
	$res1 = mssql_query($qry1);

    echo "<script type=\"text/javascript\" src=\"js/jquery_estimates_DEV.js?".time()."\"></script>\n";
	echo "<table width=\"950px\">\n";
    echo "  <tr>\n";
	echo "      <td colspan=\"2\">\n";
    echo "          <div class=\"outerrnd\">\n";
    echo "              <table width=\"100%\">\n";
	echo "                  <tr>\n";    
    echo "                      <td><b>Estimate Search</b></td>\n";
    echo "                  </tr>\n";
    echo "              </table>\n";
    echo "          </div>\n";
    echo "      </td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td width=\"200px\" valign=\"top\">\n";
	echo "          <table border=\"0\" width=\"100%\">\n";
	echo "              <tr>\n";
	echo "                  <td valign=\"bottom\">\n";
    echo "                      <div class=\"outerrnd\">\n";
    echo "                      <form id=\"EST_STRING_SEARCH_FRM\" method=\"post\">\n";
	echo "                      <input type=\"hidden\" name=\"call\" value=\"getEstimateSearchResult\">\n";
    echo "                      <input type=\"hidden\" name=\"subq\" value=\"last_name\">\n";
	echo "                      <table border=\"0\" width=\"100%\">\n";
	echo "                          <tr>\n";
	echo "                              <td align=\"right\"><b>LastName</b>\n";
    echo "                              <td align=\"left\"><input type=\"text\" id=\"sval_input\" name=\"sval\" size=\"15\" title=\"Enter Full or Partial Customer Name in this Field\"></td>\n";
	echo "                          </tr>\n";
	echo "                          <tr>\n";
    echo "                              <td align=\"right\"><b>Type</b></td>\n";
    echo "                              <td align=\"left\">\n";
	echo "                                  <select name=\"etype\">\n";
	echo "                                      <option value=\"E\">Estimate</option>\n";
	echo "                                      <option value=\"Q\">Quote</option>\n";
	echo "                                  </select>\n";
	echo "                              </td>\n";
    echo "                          </tr>\n";
	echo "                          <tr>\n";
    echo "                              <td align=\"right\"><b>Reno Only</b></td>\n";
	echo "                              <td align=\"left\">\n";
    echo "                                  <select name=\"renov\">\n";
    echo "                                      <option value=\"0\">No</option>\n";
    echo "                                      <option value=\"1\">Yes</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                          </tr>\n";
	echo "                          <tr>\n";
    echo "                              <td align=\"right\"><b>Sort</b></td>\n";
	echo "                              <td align=\"left\">\n";
	echo "                                  <select name=\"order\">\n";
	echo "                                      <option value=\"a.estid\" SELECTED>Estimate #</option>\n";
	echo "                                 		<option value=\"a.added\">Created</option>\n";
    echo "                                 		<option value=\"a.updated\">Updated</option>\n";
	echo "                                 		<option value=\"b.clname\">Last Name</option>\n";
	echo "                                    </select><br/>\n";
    echo "                                    <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>A</option>\n";
	echo "                                 		<option value=\"DESC\">D</option>\n";
	echo "                                    </select>\n";
	echo "                              </td>\n";
    echo "                          </tr>\n";
	echo "                          <tr>\n";
	echo "                              <td colspan=\"2\" align=\"right\"><button>Search</button></td>\n";
    echo "                          </tr>\n";
    echo "                      </table>\n";
    echo "                      </form>\n";
    echo "                      </div>\n";
    echo "                  </td>\n";
	echo "              </tr>\n";
	echo "              <tr>\n";
    echo "                  <td>\n";
    echo "                      <div class=\"outerrnd\">\n";
    echo "                      <form id=\"EST_SREP_SEARCH_FRM\" method=\"post\">\n";
	echo "                      <input type=\"hidden\" name=\"call\" value=\"getEstimateSearchResult\">\n";
	echo "                      <input type=\"hidden\" name=\"subq\" value=\"salesman\">\n";
    echo "                      <table>\n";
    echo "                          <tr>\n";
	echo "                              <td align=\"right\" valign=\"bottom\"><b>Salesman</b></td>\n";
	echo "                              <td align=\"left\" valign=\"bottom\">\n";
	echo "                                  <select name=\"assigned\">\n";

	while ($row1 = mssql_fetch_array($res1)) {
		if (in_array($row1['securityid'],$acclist)) {
			$secl=explode(",",$row1['slevel']);
            $ostyle=($secl[6]==0)?"fontred":"fontblack";
			echo "                                      <option value=\"".$row1['securityid']."\" class=\"".$ostyle."\">".$row1['lname'].", ".$row1['fname']."</option>\n";
		}
	}

	echo "                                  </select>\n";
	echo "                              </td>\n";
    echo "                          </tr>\n";
    echo "                          <tr>\n";
    echo "                              <td align=\"right\"><b>Type</b></td>\n";
	echo "                              <td align=\"left\">\n";
	echo "										<select name=\"etype\">\n";
	echo "											<option value=\"E\">Estimate</option>\n";
	echo "											<option value=\"Q\">Quote</option>\n";
	echo "										</select>\n";
	echo "                              </td>\n";
    echo "                          </tr>\n";
    echo "                          <tr>\n";
    echo "                              <td align=\"right\"><b>Reno Only</b></td>\n";
	echo "                              <td align=\"left\">\n";
    echo "                                  <select name=\"renov\">\n";
    echo "                                      <option value=\"0\">No</option>\n";
    echo "                                      <option value=\"1\">Yes</option>\n";
    echo "                                  </select>\n";
    echo "                              </td>\n";
    echo "                          </tr>\n";
    echo "                          <tr>\n";
    echo "                              <td align=\"right\"><b>Sort</b></td>\n";
	echo "                              <td align=\"left\" valign=\"bottom\">\n";
	echo "                                  <select name=\"order\">\n";
	echo "                                      <option value=\"a.estid\" SELECTED>Estimate #</option>\n";
	echo "                                 		<option value=\"a.added\">Created</option>\n";
    echo "                                 		<option value=\"a.updated\">Updated</option>\n";
	echo "                                 		<option value=\"b.clname\">Last Name</option>\n";
	echo "                                  </select><br/>\n";
    echo "                                  <select name=\"ascdesc\">\n";
	echo "                                 		<option value=\"ASC\" SELECTED>A</option>\n";
	echo "                                 		<option value=\"DESC\">D</option>\n";
	echo "                                  </select>\n";
	echo "                              </td>\n";
    echo "                          </tr>\n";
    echo "                          <tr>\n";
	echo "                              <td colspan=\"2\" align=\"right\"><button>Search</button></td>\n";
	echo "                          </tr>\n";
	echo "                      </table>\n";
    echo "                      </form>\n";
    echo "                      </div>\n";
	echo "                  </td>\n";
	echo "              </tr>\n";
	echo "          </table>\n";
	echo "		</td>\n";
    echo "		<td valign=\"top\">\n";
    echo "          <div class=\"outerrnd\" id=\"EstimateSearchResults\" style=\"display:none;\"></div>\n";
    echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function EstimateCinfo($cdata) {
	echo "                  <table>\n";
    echo "  <tr>\n";
	echo "      <td colspan=\"2\" align=\"left\"><b>Customer</b></td>\n";
	echo "  </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\" width=\"80px\"><b>Name</b></td>\n";
	echo "                        <td align=\"left\">".str_replace('\\','',$cdata['cinfo']['cfname'])."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\" width=\"80px\"></td>\n";
	echo "                        <td align=\"left\">".str_replace('\\','',$cdata['cinfo']['clname'])."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Site Addr</b></td>\n";
	echo "                        <td align=\"left\">".$cdata['cinfo']['saddr1']."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>City</b></td>\n";
	echo "                        <td align=\"left\">".$cdata['cinfo']['scity']."</td>\n";
	echo "                     </tr>\n";
	echo "                     <tr>\n";
	echo "                        <td align=\"right\"><b>Zip</b></td>\n";
	echo "                        <td align=\"left\">".$cdata['cinfo']['szip1']."</td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
}

function EstimatePoolData($cdata) {
    
    $qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid=".$cdata['office']['oid']." ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$bpset		=select_base_pool_new($cdata);
	$set_deck   =deckcalc($cdata['ps1'],$cdata['ps2']);
	$incdeck    =round($set_deck[0]);
	$set_ia     =calc_internal_area($cdata['ps1'],$cdata['ps2'],$cdata['ps5'],$cdata['ps6'],$cdata['ps7']);
	$set_gals   =calc_gallons($cdata['ps1'],$cdata['ps2'],$cdata['ps5'],$cdata['ps6'],$cdata['ps7']);
    
    $reno       =($cdata['renov']==1)?'<b>Renovation</b>':'';
    $ptype1     =($bpset[6]=='pft')?'<b>Perimeter</b>':'<b>Surface Area</b>';
    $ptype2     =($bpset[6]=='pft')?'<b>Surface Area</b>':'<b>Perimeter</b>';

	echo "<table width=\"100%\">\n";
    echo "  <tr>\n";
	echo "      <td colspan=\"2\" align=\"left\"><b>Pool Dimensions</b></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td colspan=\"2\" align=\"center\">".$reno."</td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"80px\">".$ptype1."</td>\n";
	echo "      <td align=\"left\">\n";

	if ($bpset[7] > 0) {
		if ($bpset[6]=="pft") {
			echo "          <input class=\"bboxbc\" type=\"text\" name=\"ps1\" id=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$cdata['ps1']."\">\n";
		}
		else {
			echo "          <input class=\"bboxbc\" type=\"text\" name=\"ps2\" id=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$cdata['ps2']."\">\n";
		}
	}
	else {
		if ($bpset[6]=="pft") {
			echo "          <select name=\"ps1\" id=\"ps1\" class=\"chngval\" style=\"text-align:center;\">\n";
            echo "              <option value=\"0\" style=\"text-align:left;\">Select...</option>\n";
		}
		else {
			echo "          <select name=\"ps2\" id=\"ps2\" class=\"chngval\" style=\"text-align:center;\">\n";
            echo "              <option value=\"0\" style=\"text-align:left;\">Select...</option>\n";
		}

		while($rowA = mssql_fetch_row($resA)) {
			if ($rowA[1]==$bpset[5])
			{
				echo "              <option value=\"$rowA[1]\" style=\"text-align:center;\" SELECTED>$rowA[1]</option>\n";
			}
			else
			{
				echo "              <option value=\"$rowA[1]\" style=\"text-align:center;\">$rowA[1]</option>\n";
			}
		}

		echo "          </select>\n";
	}
    
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "      <td align=\"right\" width=\"80px\">".$ptype2."</td>\n";
	echo "      <td align=\"left\">\n";

	if ($bpset[6]=="pft") {
		echo "          <input class=\"chngval\" type=\"text\" name=\"ps2\" id=\"ps2\" size=\"5\" maxlength=\"5\" value=\"".$cdata['ps2']."\" style=\"text-align:center;\">\n";
	}
	else {
		echo "          <input class=\"chngval\" type=\"text\" name=\"ps1\" id=\"ps1\" size=\"5\" maxlength=\"5\" value=\"".$cdata['ps1']."\" style=\"text-align:center;\">\n";
	}

	echo "      </td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"80px\"><b>Depths</b></td>\n";
	echo "      <td align=\"left\">\n";
	echo "          <input class=\"chngval\" type=\"text\" name=\"ps5\" id=\"ps5\" size=\"1\" maxlength=\"3\" value=\"".$cdata['ps5']."\" title=\"Shallow\" style=\"text-align:center;\">\n";
	echo "          <input class=\"chngval\" type=\"text\" name=\"ps6\" id=\"ps6\" size=\"1\" maxlength=\"3\" value=\"".$cdata['ps6']."\" title=\"Middle\" style=\"text-align:center;\">\n";
	echo "          <input class=\"chngval\" type=\"text\" name=\"ps7\" id=\"ps7\" size=\"1\" maxlength=\"3\" value=\"".$cdata['ps7']."\" title=\"Deep\" style=\"text-align:center;\">\n";
	echo "      </td>\n";
    echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"80px\"><b>Internal Area</b></td>\n";
	echo "      <td align=\"left\"><span id=\"fia\">".$set_ia."</span></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"80px\"><b>Gallons</b></td>\n";
	echo "      <td align=\"left\"><span id=\"fgl\">".$set_gals."</span></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
}

function EstimateHeader($cdata) {
    echo "			<form id=\"updateest\" method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "			<input type=\"hidden\" name=\"estid\" id=\"qestid\" value=\"".$cdata['estid']."\">\n";
	echo "			<input type=\"hidden\" name=\"securityid\" id=\"sid1\" value=\"".$cdata['srep']['sid']."\">\n";
	echo "			<input type=\"hidden\" name=\"custid\" value=\"".$cdata['cid']."\">\n";
	echo "			<input type=\"hidden\" name=\"cid\" value=\"".$cdata['cid']."\">\n";
	echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$cdata['office']['oid']."\">\n";
	echo "			<input type=\"hidden\" name=\"discount\" value=\"".$cdata['discount']."\">\n";
	echo "			<input type=\"hidden\" name=\"contractamt\" value=\"".$cdata['fctramt']."\">\n";
	echo "			<input type=\"hidden\" name=\"spa1\" value=\"".$cdata['spa1']."\">\n";
	echo "			<input type=\"hidden\" name=\"spa2\" value=\"".$cdata['spa2']."\">\n";
	echo "			<input type=\"hidden\" name=\"spa3\" value=\"".$cdata['spa3']."\">\n";
	echo "			<input type=\"hidden\" name=\"status\" value=\"".$cdata['status']."\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"update\">\n";
	echo "			<input type=\"hidden\" name=\"esttype\" value=\"".$cdata['esttype']."\">\n";
	echo "			<input type=\"hidden\" name=\"qecnt\" id=\"ecnt\" value=\"1\">\n";
	
	echo "<table width=\"100%\">\n";
	echo "  <tr>\n";
	echo "      <td valign=\"top\" align=\"center\" colspan=\"2\">\n";
    echo "          <div class=\"outerrnd\">\n";
	echo "          <table width=\"100%\">\n";
	echo "              <tr>\n";
    echo "                  <td align=\"left\"><b>Retail Estimate</b></td>\n";
	echo "                  <td align=\"right\"><b>\n";
	?>
		
		<script type="text/javascript">
            setLocalTime();
        </script>
		
	<?php
	echo "                  </b></td>\n";
	echo "              </tr>\n";
	echo "          </table>\n";
    echo "          </div>\n";
	echo "      </td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "          <div class=\"outerrnd\">\n";

	cinfo_display_DEV($cdata);

	echo "          </div>\n";
	echo "          <p>\n";
	echo "          <div class=\"outerrnd\">\n";
	
	pool_detail_display_DEV($cdata);

	echo "          </div>\n";
    echo "          <p>\n";
    echo "          <div class=\"outerrnd\">\n";
    
    EstimateControl($cdata);
    
    echo "          </div>\n";
	echo "      </td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
	echo "</form>\n";
}

function EstimateData($estid=null,$oid=null) {
    error_reporting(E_ALL);
	ini_set('display_errors','On');
    
    $out    =array();

	$qry  = "SELECT
                    E.*
                    ,(select jobid from jobs where officeid=E.officeid and estid=E.estid) as dbjobid
                    ,(select njobid from jobs where officeid=E.officeid and estid=E.estid) as dbnjobid
                    ,C.cfname,C.clname
                    ,(select modcomm from security where securityid=E.securityid) as dbmodcom
                    ,(select newcommdate from security where securityid=E.securityid) as dbncommdate
                    ,(select sidm from security where securityid=E.securityid) as dbsidm
                    ,(select com_rate from security where securityid=E.securityid) as dbcom_rate
                    ,(select fname from security where securityid=E.securityid) as srfname
                    ,(select lname from security where securityid=E.securityid) as srlname
                    ,(select fname from security where securityid=E.sidm) as smfname
                    ,(select lname from security where securityid=E.sidm) as smlname
                    ,(select fname from security where securityid=E.updateby) as upfname
                    ,(select lname from security where securityid=E.updateby) as uplname
                FROM est AS E
                INNER JOIN cinfo as C
                ON E.cid=C.cid
                WHERE E.officeid=".(int) $oid." AND E.estid=".(int) $estid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
    
    $qry0 = "SELECT officeid,name,stax,sm,gm,bullet_rate,bullet_cnt,over_split,pft_sqft,encost,enest,com_rate,newcommdate,vgp,otype_code,pb_code FROM offices WHERE officeid=".(int) $oid.";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
    
    $office=array(
        'oid'=>$row0['officeid'],
        'name'=>$row0['name'],
        'stax'=>$row0['stax'],
        'sm'=>$row0['sm'],
        'gm'=>$row0['gm'],
        'bullet_rate'=>$row0['bullet_rate'],
        'bullet_cnt'=>$row0['bullet_cnt'],
        'over_split'=>$row0['over_split'],
        'pft_sqft'=>$row0['pft_sqft'],
        'encost'=>$row0['encost'],
        'enest'=>$row0['enest'],
        'com_rate'=>$row0['com_rate'],
        'newcommdate'=>$row0['newcommdate'],
        'vgp'=>$row0['vgp'],
        'otype_code'=>$row0['otype_code'],
        'pb_code'=>$row0['pb_code']
    );
    
    $qry1 = "SELECT estdata FROM est_acc_ext WHERE officeid=".(int) $oid." AND estid=".(int) $row['estid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
    
    $qry2 = "SELECT cid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell,cid,officeid,added,(select label_masoff_code from offices where officeid=C.officeid) as olabel FROM cinfo AS C WHERE C.officeid=".(int) $oid." AND C.cid=".(int) $row['cid'].";";
    $res2 = mssql_query($qry2);
	$row2 = mssql_fetch_array($res2);
    
    $qry3 = "SELECT id,city FROM taxrate WHERE officeid=".(int) $oid." ORDER BY city ASC";
	$res3 = mssql_query($qry3);
    
    $taxes=array();
    while ($row3 = mssql_fetch_array($res3)) {
        $taxes[$row3['id']]=array('id'=>$row3['id'],'city'=>$row3['city']);
    }
    
    $cinfo=array(
        'cid'=>$row2['cid'],
        'cfname'=>$row2['cfname'],
        'clname'=>$row2['clname'],
        'chome'=>$row2['chome'],
        'scounty'=>$row2['scounty'],
        'saddr1'=>$row2['saddr1'],
        'scity'=>$row2['scity'],
        'sstate'=>$row2['sstate'],
        'szip1'=>$row2['szip1'],
        'ccell'=>$row2['ccell'],
        'added'=>$row2['added']
    );

	$out=array(
        'estid'=>	$row['estid'],
        'cid'=>		$row['cid'],
        'jobid'=>	$row['dbjobid'],
        'njobid'=>	$row['dbnjobid'],
        'ps1'=>		$row['pft'],
        'ps2'=>		$row['sqft'],
        'spa1'=>	$row['spatype'],
        'spa2'=>	$row['spa_pft'],
        'spa3'=>	$row['spa_sqft'],
        'tzone'=>	$row['tzone'],
        'camt'=>	$row['contractamt'],
        'comt'=>	0,
        'com_rate'=>$row['com_rate'],
        'ovrsplit'=>$row['over_split'],
        'cfname'=>	$row['cfname'],
        'clname'=>	$row['clname'],
        'status'=>	$row['status'],
        'ps5'=>		$row['shal'],
        'ps6'=>		$row['mid'],
        'ps7'=>		$row['deep'],
        'deck'=>	$row['deck1'],
        'erun'=>	$row['erun'],
        'prun'=>	$row['prun'],
        'comadj'=>	$row['comadj'],
        'dbocomm'=>	$row['comm'],
        'sidm'=>	$row['sidm'],
        'buladj'=>	$row['buladj'],
        'applyov'=>	$row['applyov'],
        'applybu'=>	$row['applybu'],
        'refto'=>	$row['refto'],
        'ps1a'=>	$row['apft'],
        'jadd'=>	0,
        'mjadd'=>	0,
        'custallow'=>0,
        'renov'=>	$row['renov'],
        'esttype'=>	$row['esttype'],
        'discount'=>0,
        'royrel'=>	0,
        'allowdel'=>0,
        'tcomm'=>	0,
        'added'=>	strtotime($row['added']),
        'updated'=>	(!is_null($row['updated']) and strlen($row['updated']))?date('m/d/y',strtotime($row['updated'])):'',
        'cdate'=>	strtotime($row['added']),
        'defmeas'=> ($row0['pft_sqft']=="p")?$row['pft']:$row['sqft'],
        'ncommdate'=>$row['dbncommdate'],
        'srep'=>    array('sid'=>$row['sidm'],'fname'=>$row['srfname'],'lname'=>$row['srlname']),
        'smanager'=>array('sid'=>$row['sidm'],'fname'=>$row['smfname'],'lname'=>$row['smlname']),
        'lupdate'=> array('sid'=>$row['updateby'],'fname'=>$row['upfname'],'lname'=>$row['uplname']),
        'estdata'=> $row1['estdata'],
        'office'=>  $office,
        'commarray'=>
            array(
                'jobid'=>       $row['dbjobid'],
                'njobid'=>      $row['dbnjobid'],
                'applyov'=>     $row['applyov'],
                'com_rate'=>    $row['com_rate'],
                'over_split'=>  $row['over_split'],
                'sid'=>         $row['securityid'],
                'sysdate'=>     strtotime($row['added']),
                'renov'=>       $row['renov'],
                'contdate'=>    $row['added']
            ),
        'cinfo'=>   $cinfo
    );
    
	$qryO = "SELECT * FROM jest..CommissionSchedule WHERE oid=".(int) $oid." AND estid=".(int) $estid.";";
	$resO = mssql_query($qryO);
	$nrowO= mssql_num_rows($resO);
	
	if ($nrowO > 0) {
		$out['commarray']['commschedcnt']=$nrowO;
		
		while($rowO = mssql_fetch_array($resO)) {
			if ($rowO['type']==1) {// Base
				$ctype='base';
			}
			elseif ($rowO['type']==2) {// Manual Adjust
				$ctype='man';
			}
			elseif ($rowO['type']==3) {
				$ctype='over';
			}
			
			$out['commarray']['commsched'][$ctype]=array(
                'csid'=>$rowO['csid'],
                'oid'=>$rowO['oid'],
                'estid'=>$rowO['estid'],
                'type'=>$rowO['type'],
                'rate'=>$rowO['rate'],
                'amt'=>$rowO['amt'],
                'secid'=>$rowO['secid'],
                'cbtype'=>$rowO['cbtype']
			);
		}
	}
	
	$tbullets=0;
    
	$qryQ = "select cmid,rwdrate,ctgry from jest..CommissionBuilder where oid=".$_SESSION['officeid']." and active=1 and ctgry=1 and renov=".(int) $out['renov'].";";
	$resQ = mssql_query($qryQ);
	$rowQ = mssql_fetch_array($resQ);
    $nrowQ= mssql_num_rows($resQ);
	
	$qryQa  = "select cmid,rwdrate,ctgry from jest..CommissionBuilder where oid=".(int) $oid." and active=1 and secid=".(int) $out['srep']['sid']." and ctgry=1;";
	$resQa = mssql_query($qryQa);
	$rowQa = mssql_fetch_array($resQa);
    $nrowQa= mssql_num_rows($resQa);
    
	$out['com_base_rate']=($nrowQa > 0)?$rowQa['rwdrate']:$rowQ['rwdrate'];

	// Sets Tax Rate
    /*
	if ($rowC[2]==1) {
		$qryJ = "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ = mssql_query($qryJ);
		$rowJ = mssql_fetch_row($resJ);

		$taxrate=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid='".$_SESSION['officeid']."' ORDER BY city ASC";
		$resK = mssql_query($qryK);
	}
	*/
	
	$poolcomm_adj	=detect_package($row1['estdata']);
	$set_deck   	=deckcalc($out['ps1'],$out['deck']);
	$incdeck    	=round($set_deck[0]);
	$set_ia     	=calc_internal_area($out['ps1'],$out['ps2'],$out['ps5'],$out['ps6'],$out['ps7']);
	$set_gals   	=calc_gallons($out['ps1'],$out['ps2'],$out['ps5'],$out['ps6'],$out['ps7']);
	$vdiscnt    	=$out['discount'];
	$bpset			=select_base_pool_new($out);
	$pbaseprice 	=$bpset[3];

	if ($poolcomm_adj >= 1) {
        $bcomm=0;
	}
	else {
		if (isset($out['com_base_rate']) && $out['com_base_rate']!=0) {
			$bcomm=$bpset[3] * $out['com_base_rate'];
		}
		else {
			$bcomm=0;
		}
	}

	//$uid			=md5(session_id().time().$rowI[10]).".".$_SESSION['securityid'];
    
    $out['bpset']       =$bpset;
    $out['bcomm']       =$bcomm;
	$out['fpbaseprice'] =number_format($pbaseprice, 2, '.', '');
	$out['fbcomm']		=number_format($bcomm, 2, '.', '');
	$out['ctramt']		=$row['contractamt'];
	$out['fctramt']	    =number_format($row['contractamt'], 2, '.', '');
	$out['commarray']['fctramt']=number_format($row['contractamt'], 2, '.', '');
    return $out;
}


function select_base_pool_new($data) {
    $bpar=array();
    
	if ($data['office']['pft_sqft']=="p") {
		$psize=$data['ps1'];
		$ptext="pft";
	}
	else {
		$psize=$data['ps2'];
		$ptext="sqft";
	}

	if ($data['renov']==1) {
		$rbtable="rbpricep_renov";
	}
	else {
		$rbtable="rbpricep";
	}

	$qry	= "SELECT SUM(quan1) as quan1t FROM ".$rbtable." WHERE officeid=".(int) $data['office']['oid'].";";
	$res	= mssql_query($qry);
	$row	= mssql_fetch_array($res);

	if ($row['quan1t'] > 0) {
		$bi	=0;
		$bq	=0;
		$bq1=0;
		$bp	=0;
		$bc	=0;

		$qry1	= "SELECT * FROM ".$rbtable." WHERE officeid=".(int) $data['office']['oid']." ORDER BY quan ASC;";
		$res1	= mssql_query($qry1);

		while ($row1 = mssql_fetch_array($res1)) {
			if ($psize >= $row1['quan'] && $psize <= $row1['quan1']) {
				//echo "HIT";
				$bi	=$row1['id'];
				$bq	=$row1['quan'];
				$bq1=$row1['quan1'];
				$bp	=$row1['price'];
				$bc	=$row1['comm'];
			}
		}
	}
	else {
		$qry1	= "SELECT * FROM ".$rbtable." WHERE officeid=".(int) $data['office']['oid']." and quan='".$psize."';";
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

function EstimateDetailLoad($estData) {
    $dbg=0;
    if (isset($dbg) && $dbg==1 && $_SESSION['securityid']==26) {
            echo "<pre>";
            print_r($estData);
            echo "</pre>";
            exit;
    }

    $qry0 = "SELECT
                E.*,
                (select name from AC_Cats where officeid=E.oid and catid=E.catid) AS catname,
                (select abrv from mtypes where mid=E.mtype) AS mname,
                (select seqn from AC_cats where officeid=E.oid and catid=E.catid) as catseqn
            FROM EstimateDetail AS E WHERE E.oid=".(int) $estData['office']['oid']." AND E.estid=".(int) $estData['estid']." ORDER BY catseqn ASC,E.seqn ASC;";
	$res0 = mssql_query($qry0);	
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 0) {
        while ($row0 = mssql_fetch_array($res0)) {
            //$bullet=(isset($row0['bullet']) and $row0['bullet'] > 0)?'<img src="images/bullet_green.png" title="'.$row0['bullet'].' Bullet(s)">':'';
            $bullet=(isset($row0['bullet']) and $row0['bullet'] > 0)?'cartbulletitemicon':'';
            $pft=(isset($row0['mtype']) and $row0['mtype']==4)?'<span class="crtpft" style="display:none;">'.$row0['mtype'].'</span>':'';
            $bidtext=((isset($row0['qtype']) and $row0['qtype']==33) and strlen($row0['atrib1']) >= 1)?htmlspecialchars_decode($row0['atrib1']).htmlspecialchars_decode($row0['atrib2']).htmlspecialchars_decode($row0['atrib3']):'';
            echo "           <tr class=\"CartLineItem\">\n";
            echo "              <td class=\"wh\" align=\"left\">".$row0['catname']."</td>\n";
            echo "              <td class=\"wh ".$bullet."\" align=\"left\">\n";
        
            showdescrip($row0['item'],$row0['atrib1'],$row0['atrib2'],$row0['atrib3']);
            
            /*
            if ($row0['qtype']==33) {
                $data_price=$v1array[3];
        
                if ($_SESSION['action']=="est") {
                    $qry2 = "SELECT estid,bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid=".$_SESSION['estid']." AND bidaccid='".$v1array[0]."';";
                }
                elseif ($_SESSION['action']=="contract") {
                    $qry2 = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$cdata['jobid']."' AND jadd='".$cdata['jadd']."' AND dbid='".$v1array[0]."';";
                }
                elseif ($_SESSION['action']=="job") {
                    $qry2 = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$cdata['njobid']."' AND jadd='".$cdata['jadd']."' AND dbid='".$v1array[0]."';";
                }
        
                $res2 = mssql_query($qry2);
                $row2 = mssql_fetch_array($res2);
        
                $textout=wordwrap(str_replace("\\", "",$row2['bidinfo']),75,"<BR>");
        
                echo "\n".$textout."\n";
                
            }
            
            if (isset($cdata['com_base_rate']) && $cdata['com_base_rate']!=0 && $row0['qtype']!=33) {
                $comm=$itemfromdb[0] * $cdata['com_base_rate'];
            }
            else {
                $comm=$itemfromdb[1];
            }
        
            $fdata_price=number_format($data_price, 2, '.', '');
            $fcomm=($comm!=0)?number_format($comm, 2, '.', ''):'<img src="images/pixel.gif">';
            */
            //echo $bullet;
            echo "              </td>\n";
            echo "              <td class=\"wh\" align=\"center\" width=\"30\">".$row0['calcqn']."</td>\n";
            echo "              <td class=\"wh\" align=\"center\" width=\"30\">".$row0['mname']."</td>\n";
            echo "              <td class=\"wh\" align=\"right\" width=\"60\"><span class=\"ItemPrice\">".number_format($row0['calcrp'], 2, '.', '')."</span></td>\n";
            echo "              <td class=\"wh\" align=\"right\" width=\"60\"><span class=\"ItemComm\">".number_format($row0['calccm'], 2, '.', '')."</span></td>\n";
            echo "              <td class=\"wh pbinfo\" align=\"center\" width=\"20px\">\n";
            echo "                  <span class=\"pbid\" style=\"display:none;\">".$row0['srcid']."</span>\n";
            echo "                  <span class=\"crtid\" style=\"display:none;\">".$row0['edid']."</span>\n";
            echo "                  <span class=\"crtunique\" style=\"display:none;\">".$row0['poolcalc']."</span>\n";
            echo $pft;
            echo "                  <span class=\"CartItemDelete setpointer noPrint\"><img src=\"images/action_delete.gif\"></span>\n";
            echo "              </td>\n";
            echo "           </tr>\n";
        }
    }
}

function EstimateCartItems($cdata,$filters)
{
	if ($_SESSION['securityid']==2699999999999999999) {
		error_reporting(E_ALL);
		ini_set('display_errors','On');
	}
	//global $bctotal,$rctotal,$cctotal,$tacc_price,$phsbcrc,$tbullets;
    $rctotal=0;
    $bctotal=0;
    $cctotal=0;
    
    $oid    =$cdata['office']['oid'];
    $MAS    =$cdata['office']['pb_code'];
	$camt	=$cdata['camt'];
	$status	=$cdata['status'];
	$ps1	=$cdata['ps1'];
	$ps2	=$cdata['ps2'];
	$ps4	=$cdata['tzone'];
	$ps5	=$cdata['ps5'];
	$ps6	=$cdata['ps6'];
	$ps7	=$cdata['ps7'];
	$spa1	=$cdata['spa1'];
	$spa2	=$cdata['spa2'];
	$spa3	=$cdata['spa3'];

	if (!isset($showdetail)) {
		$showdetail=0;
	}

	//echo $estdata."<br>";
	
	if (strlen($cdata['estdata']) >=6) {
		$estAarray=explode(",",$cdata['estdata']);
		if (is_array($estAarray)) {
			$tdata_price=0;
			$tcomm=0;
			foreach($estAarray as $n1=>$v1) {
				$v1array=explode(":",$v1);
				
				if ($_SESSION['securityid']==269999999999)
				{
					display_array($v1array);
				}

				if (empty($v1array[6])) {
					$ctype=0;
					$crate=0;
				}
				else {
					$ctype=$v1array[6];
					$crate=$v1array[5];
				}
				
				//for backward compat 3/5/07
				if (!isset($v1array[7])) {
					$v1array[7]=0;
				}
				
				if (!isset($v1array[8])) {
					$v1array[8]=0;
				}
				
				if (!isset($v1array[9])) {
					$v1array[9]=0;
				}
				// End backward compat
				
				$itemfromdb=form_calc_DEV($cdata,$v1array[0],$v1array[2],$v1array[4],$v1array[3],$crate,$ctype,$v1array[7],$v1array[9]);
				
				if ($_SESSION['securityid']==269999999999999999999999999) {
					display_array($itemfromdb);
				}

				$qry0 = "SELECT item,atrib1,atrib2,atrib3,qtype,catid,bullet FROM [".$MAS."acc] WHERE officeid='".$_SESSION['officeid']."' AND id='".$v1array[0]."';";
				$res0 = mssql_query($qry0);
				$row0 = mssql_fetch_array($res0);

				//echo "-------(".$v1array[0].")--------<br>";
				//show_array_vars($row0);

				if ($row0['qtype']!=32) {
					$x1="xxx".$v1array[0];

					$data_price=$itemfromdb[0];
					$bullet=(isset($row0['bullet']) and $row0['bullet'] > 0)?"<img src=\"images/bullet_green.png\" title=\"SmartFeature (".$row0['bullet'].")\">":'';

					$qry1 = "SELECT catid,name FROM AC_cats WHERE officeid=".(int) $oid." AND catid=".(int) $row0['catid'].";";
					$res1 = mssql_query($qry1);
					$row1 = mssql_fetch_array($res1);

					$strlen=strlen($row1['name']);
					$textout=wordwrap($row1['name'],23,"<br>",1);

					echo "           <tr class=\"CartLineItem_".$v1array[0]."\">\n";
					echo "              <td class=\"wh\" valign=\"bottom\" align=\"left\">".$textout."</td>\n";
					echo "              <td class=\"wh\" valign=\"bottom\" align=\"left\">\n";

					showdescrip($row0['item'],$row0['atrib1'],$row0['atrib2'],$row0['atrib3']);

					if ($row0['qtype']==33) {
						$data_price=$v1array[3];

						if ($_SESSION['action']=="est") {
							$qry2 = "SELECT estid,bidinfo FROM est_bids WHERE officeid='".$_SESSION['officeid']."' AND estid=".$_SESSION['estid']." AND bidaccid='".$v1array[0]."';";
						}
						elseif ($_SESSION['action']=="contract") {
							$qry2 = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND jobid='".$cdata['jobid']."' AND jadd='".$cdata['jadd']."' AND dbid='".$v1array[0]."';";
						}
						elseif ($_SESSION['action']=="job") {
							$qry2 = "SELECT bidinfo FROM jbids WHERE officeid='".$_SESSION['officeid']."' AND njobid='".$cdata['njobid']."' AND jadd='".$cdata['jadd']."' AND dbid='".$v1array[0]."';";
						}

						$res2 = mssql_query($qry2);
						$row2 = mssql_fetch_array($res2);

						$textout=wordwrap(str_replace("\\", "",$row2['bidinfo']),75,"<BR>");

						echo "\n".$textout."\n";
						
					}
					elseif ($row0['qtype']==20) {
						$qry2 = "SELECT item,code,atrib1,rp FROM material_master WHERE officeid='".$_SESSION['officeid']."' AND code='".$itemfromdb[5]."';";
						$res2 = mssql_query($qry2);
						$row2 = mssql_fetch_array($res2);

						echo "\n".$row2['item']."\n";
					}
					
					if (isset($cdata['com_base_rate']) && $cdata['com_base_rate']!=0 && $row0['qtype']!=33) {
						$comm=$itemfromdb[0] * $cdata['com_base_rate'];
					}
					else {
						$comm=$itemfromdb[1];
					}
				
					$fdata_price=number_format($data_price, 2, '.', '');
					$fcomm=($comm!=0)?number_format($comm, 2, '.', ''):'<img src="images/pixel.gif">';
                
					echo $bullet;
					echo "              </td>\n";
					echo "              <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"30\">".$itemfromdb[2]."</td>\n";
					echo "              <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"30\">".$itemfromdb[4]."</td>\n";
					echo "              <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"60\"><span class=\"ItemPrice\" id=\"pbitem_".$v1array[0]."\">".$fdata_price."</span></td>\n";
                    echo "              <td class=\"wh\" valign=\"bottom\" align=\"right\" width=\"60\"><span class=\"ItemComm\" id=\"pbcomm_".$v1array[0]."\">".$fcomm."</span></td>\n";
					echo "              <td class=\"wh\" valign=\"bottom\" align=\"center\" width=\"20px\">\n";

					if ($row0['qtype'] < 48 || $row0['qtype'] > 52) {
						if ($_SESSION['action']=="est" and (isset($status) and $status < 2)) {
                            echo "<span id=\"pbitemdelete_".$v1array[0]."\" class=\"CartItemDelete setpointer noPrint\"><img src=\"images/action_delete.gif\"></span>";
                            /*
							echo "			<form method=\"post\">\n";
							echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$cdata['office']['oid']."\">\n";
							echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
							echo "			<input type=\"hidden\" name=\"call\" value=\"remove_acc\">\n";
							echo "			<input type=\"hidden\" name=\"estid\" value=\"".$cdata['estid']."\">\n";
							echo "			<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
							echo "			<input class=\"transnb\" type=\"checkbox\" name=\"".$x1."\" value=\"".$v1array[0]."\" title=\"Remove Item\" onClick=\"this.form.submit();\">\n";
							//echo "			<input class=\"transnb\" type=\"image\" src=\"../images/action_delete.gif\" name=\"".$x1."\" value=\"".$v1array[0]."\" title=\"Delete Item\" onClick=\"this.form.submit();\">\n";
							echo "			</form>\n";
							*/
						}
					}

					echo "              </td>\n";
					echo "           </tr>\n";
					
					if ($row0['qtype']==33) {//Bid Items: for Estimate Cost Drilldetail
						if (!isset($_SESSION['estbidretail'][$v1array[0]])) {
							//echo "CREATED!<br>";
							$fdata_cost=0;
							if ($_SESSION['action']=="est") {
								$qryBIDc = "SELECT bprice as fdata_cost FROM bid_breakout WHERE officeid=".$_SESSION['officeid']." AND estid=".$_SESSION['estid']." AND rdbid=".$v1array[0].";";
								$resBIDc = mssql_query($qryBIDc);
								while ($rowBIDc = mssql_fetch_array($resBIDc))
								{
									$fdata_cost=$fdata_cost + $rowBIDc['fdata_cost'];
								}
							}
							
							$_SESSION['estbidretail'][$v1array[0]]=array($fdata_price,$fdata_cost);
						}
					}
					
					if ($row0['qtype']==55||$row0['qtype']==72) {
						EstimatePackageItems($v1array[0],$cdata);
					}

					if ($row0['bullet'] > 0) {
						$tbullets=$tbullets+$row0['bullet'];
					}

					$tdata_price=$tdata_price+$data_price;
					$tcomm=$tcomm+$comm;
				}
			}
		}

		$rctotal=$rctotal+$tdata_price;
		$cctotal=$cctotal+$tcomm;
		$tacc_price=$tdata_price;
	}
}

function EstimatePackageItems($rid,$cdata) {
    $oid=$cdata['office']['oid'];
    $MAS=$cdata['office']['pb_code'];
    
    $qry = "SELECT item FROM [".$MAS."acc] WHERE officeid=".(int) $oid." AND id=".(int) $rid.";";
    $res = mssql_query($qry);
    $row = mssql_fetch_array($res);
    
    $qry0 = "SELECT * FROM [".$MAS."plinks] WHERE officeid=".(int) $oid." AND rid=".(int) $rid.";";
    $res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 0) {
        while ($row0 = mssql_fetch_array($res0)) {
            $qry1 = "SELECT * FROM [".$MAS."acc] WHERE officeid=".(int) $oid." AND id=".(int) $row0['iid'].";";
            $res1 = mssql_query($qry1);
            $row1 = mssql_fetch_array($res1);
       
            $qry2 = "SELECT * FROM AC_cats WHERE officeid=".(int) $oid." AND catid=".(int) $row1['catid'].";";
            $res2 = mssql_query($qry2);
            $row2 = mssql_fetch_array($res2);
       
            $qry3 = "SELECT abrv FROM mtypes WHERE mid=".(int) $row1['mtype'].";";
            $res3 = mssql_query($qry3);
            $row3 = mssql_fetch_array($res3);
       
            $adjquan=package_quan_set($row1['qtype'],$row1['quan_calc'],$row0['adjquan'],$cdata['ps1'],$cdata['ps2'],$cdata['tzone'],$cdata['ps5'],$cdata['ps6'],$cdata['ps7'],$cdata['spa1'],$cdata['spa2'],$cdata['spa3'],$cdata['deck']);
            $adjamt=$row0['adjamt'];
       
            if ($row0['adjtype']==1) {// Adjusts
               $adjquan=$row1['quan_calc']+$row0['adjquan'];
               $adjamt=$row1['rp']+$row0['adjamt'];
            }
            elseif ($row0['adjtype']==2) { // Price Percent Adjust
               $adjamt=($row1['rp']*$row0['adjamt'])*$adjquan;
            }
            elseif ($row0['adjtype']==3) {
               $adjquan=$row1['quan_calc']+$row0['adjquan'];
            }
            elseif ($row0['adjtype']==4) {// Zero Price
               $adjamt=($row1['rp']+($row1['rp'] * -1))*$row0['adjquan'];
            }
            elseif ($row0['adjtype']==5) {
               $adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
            }
            elseif ($row0['adjtype']==6) {
               $adjamt=($row1['rp']+($row1['rp'] * -1))*$row0['adjquan'];
               $adjquan=$row1['quan_calc']+($row1['quan_calc'] * -1);
            }
       
            $fadjamt=number_format($adjamt, 2, '.', '');
       
            echo "                  <tr class=\"CartLineItem_".$rid."\">\n";
            echo "                     <td class=\"wh\" align=\"right\" width=\"90\"></td>\n";
            echo "                     <td class=\"wh\" valign=\"top\" align=\"left\">\n";
            echo "                        <table align=\"left\" width=\"100%\">\n";
            echo "                           <tr>\n";
            echo "                              <td class=\"transbackfill\" align=\"center\" width=\"20px\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                              <td align=\"left\">".$row1['item']."</td>\n";
            echo "                           </tr>\n";
            echo "                        </table>\n";
            echo "                     </td>\n";
            echo "                     <td class=\"wh\" align=\"center\" width=\"30\">".$adjquan."</td>\n";
            echo "                     <td class=\"wh\" align=\"center\" width=\"30\">".$row3['abrv']."</td>\n";
            echo "                     <td class=\"wh\" align=\"right\" width=\"65\">\n";
            
            if (isset($cdata['esttype']) && $cdata['esttype']=='Q') {
                echo "                  <input type=\"hidden\" name=\"acc_pb_src[".$row0['iid']."][0]\" value=\"".$fadjamt."\">\n";
                echo "                  <input class=\"bboxnobr\" type=\"text\" name=\"acc_pb_src[".$row0['iid']."][1]\" value=\"".$fadjamt."\" size=\"7\">\n";
            }
            else {
                echo $fadjamt;
            }
            
            echo "                     </td>\n";            
            echo "                     <td class=\"wh\" align=\"right\" width=\"55\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                     <td class=\"wh\" align=\"right\" width=\"25\"><img src=\"images/pixel.gif\"></td>\n";
            echo "                  </tr>\n";
        }
    }
}

function form_calc_DEV($cdata,$id,$quan,$code,$amt,$ctype,$crate,$mquan,$chgproc)
{
	if ($_SESSION['securityid']==269999999999999999999999)
	{
		error_reporting(E_ALL);
		ini_set('display_errors','On');
		echo 'Entered:'.$quan.':'.$mquan.'<br>';
	}

    $MAS        =$cdata['office']['pb_code'];
	$officeid   =$cdata['office']['oid'];
	$camt		=$cdata['camt'];
	$ps1        =$cdata['ps1'];
	$ps2        =$cdata['ps2'];
	$ps4        =$cdata['tzone'];
	$ps5        =$cdata['ps5'];
	$ps6        =$cdata['ps6'];
	$ps7        =$cdata['ps7'];
	$spa1       =$cdata['spa1'];
	$spa2       =$cdata['spa2'];
	$spa3       =$cdata['spa3'];
	
	if ($_SESSION['securityid']==26666666666666666666666666666666)
	{
		display_array($viewarray);
	}

	$ia			=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$gl			=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);
	$spa_ia		=calc_internal_area($ps1,$ps2,$ps5,$ps6,$ps7);
	$spa_gl		=calc_gallons($ps1,$ps2,$ps5,$ps6,$ps7);

	$qryA 		= "SELECT * FROM [".$MAS."acc] WHERE officeid='".$officeid."' AND id='".$id."';";
	$resA 		= mssql_query($qryA);
	$rowA 		= mssql_fetch_array($resA);

	if ($cdata['status']==2 || $cdata['status']==3 || $cdata['status']==5)
	{
		//echo "JOB<br>";
		$rprice	=$amt;
		$rcrate	=$crate;
		$rctype	=$ctype;
	}
	else
	{
		//echo "EST<br>";
		$rprice	=$rowA['rp'];

		if ($rowA['commtype'] >= 1)
		{
			$rcrate	=$rowA['crate'];
			$rctype	=$rowA['commtype'];
		}
		else
		{
			$rcrate	=0;
			$rctype	=0;
		}
	}

	//echo "Rate: ".$rcrate."<br>";
	//echo "Type: ".$rctype."<br>";

	if ($rowA['mtype']!=0)
	{
		$qryC = "SELECT abrv FROM mtypes WHERE mid=".$rowA['mtype'].";";
		$resC = mssql_query($qryC);
		$rowC = mssql_fetch_array($resC);

		$uom  =$rowC['abrv'];
	}
	else
	{
		$uom  ="n/a";
	}

	//Gets CODE info
	if ($rowA['qtype']==18||$rowA['qtype']==19||$rowA['qtype']==20||$rowA['qtype']==21||$rowA['qtype']==22||$rowA['qtype']==23)
	{
		$qryB = "SELECT * FROM material_master WHERE officeid=".$_SESSION['officeid']." AND code=".$code.";";
		$resB = mssql_query($qryB);
		$rowB = mssql_fetch_array($resB);
		$nrowB= mssql_num_rows($resB);

		if ($nrowB > 1)
		{
			$rc_code   =0;
			$cc_code   =0;
			$name_code ="Multi";
		}
		else
		{
			$rc_code   =$rowB['rp'];
		}
	}

	//echo "PR: ".$rprice."<br>";
	//echo "QU: ".$quan."<br>";

	// Calculation Loop for Retail
	//echo "OMQUAN: ".$mquan."<br>";
	//echo "OCHGPROC: ".$chgproc."<br>";
	if ($_SESSION['securityid']==2699999999999999999999999999)
	{
		echo 'Entered:'.$quan.':'.$mquan.'<br>';
	}
	$calc_out	=uni_calc_loop($rowA['qtype'],0,$rprice,$rowA['lrange'],$rowA['hrange'],$quan,$rowA['quan_calc'],$ia,$gl,$spa_ia,$spa_gl,$code,0,0,0,$mquan,$chgproc);
	$rc			=$calc_out[1];
	$quan_out	=$calc_out[2];

	if ($_SESSION['securityid']==269999999999999999)
	{
		display_array($calc_out);
	}

	if ($rowA['supplier']!=0)
	{
		$qryX = "SELECT com_rate FROM offices WHERE officeid='".$_SESSION['officeid']."';";
		$resX = mssql_query($qryX);
		$rowX = mssql_fetch_array($resX);

		$cc=$rprice*$rowX['com_rate'];
		//echo "HIT";
	}
	else
	{
		//echo "NOT HIT: ".$rctype;
		if ($rctype==1)
		{
			//echo "TYPE 1<br>";
			if ($rowA['qtype']==33)
			{
				$cc=($amt*$rcrate)*$quan_out;
			}
			elseif ($rowA['qtype']==20)
			{
				$cc=($rc_code*$rcrate)*$quan_out;
			}
			elseif ($rowA['qtype']==5 || $rowA['qtype']==6 || $rowA['qtype']==7 || $rowA['qtype']==58)
			{
				$cc=$rc*$rcrate;
				//echo "ICOMM: ".$cc."($rc)($rprice*$rcrate)*$quan_out<br>";
			}
			else
			{
				$cc=($rprice*$rcrate)*$quan_out;
				//echo "ICOMM: ".$cc."($rctype)($rprice*$rcrate)*$quan_out<br>";
			}
		}
		elseif ($rctype==2)
		{
			//echo "TYPE 2<br>";
			$cc=$rcrate*$quan_out;
		}
		elseif ($rctype==3)
		{
			//echo "TYPE 2<br>";
			$cc=$rcrate;
		}
		else
		{
			//echo "TYPE 0<br>";
			$cc=0;
		}
	}
	//echo "ICOMM: ".$cc."($rctype)()<br>";

	//echo "RC: ".$rc."<br>";
	$rcexport= array(0=>$rc,1=>$cc,2=>$quan_out,3=>0,4=>$uom,5=>$code);
	
	if ($_SESSION['securityid']==2699999999999999)
	{
		display_array($rcexport);
	}
	
	return $rcexport;
}

function EstimateControl($cdata) {
    
    $qry1 = "SELECT securityid,fname,lname,slevel FROM security WHERE officeid=".(int) $cdata['office']['oid']." AND admstaff!='1' ORDER BY substring(slevel,13,1) desc,lname ASC;";
	$res1 = mssql_query($qry1);
    
    echo "<table width=\"100%\" border=0>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" valign=\"top\" width=\"80px\"><b>Sales Rep</b></td>\n";
	echo "      <td align=\"left\">\n";

	if ($_SESSION['elev'] >= 4 and $cdata['jobid']=='0') {
		echo "      <select id=\"sid2\" name=\"securityid\">\n";
		
		while($row1 = mssql_fetch_array($res1)) {
			if (in_array($row1['securityid'],$cdata['acl'])) {
				$secl=explode(",",$row1['slevel']);
                $ostyle=($secl[6]==0)?"fontred":"fontblack";

				if ($cdata['srep']['sid']==$row1['securityid']) {
					echo "<option value=\"".$row1['securityid']."\" class=\"".$ostyle."\" SELECTED>".$row1['fname']." ".$row1['lname']."</option>\n";
				}
				else {
					echo "<option value=\"".$row1['securityid']."\" class=\"".$ostyle."\">".$row1['fname']." ".$row1['lname']."</option>\n";
				}
			}
		}
        
		echo "      </select>\n";
	}
	else {
		echo $cdata['srep']['fname']." ".$cdata['srep']['lname'];
		echo "      <input type=\"hidden\" name=\"securityid\" value=\"".$cdata['srep']['sid']."\">\n";
	}

	echo "      </td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" valign=\"top\" width=\"80px\"><b>Manager</b></td>\n";
	echo "      <td align=\"left\">\n";
	
	if ($cdata['smanager']['sid']!=0) {
		echo $cdata['smanager']['fname']." ".$cdata['smanager']['lname'];
	}
	else {
		echo 'None Assigned';
	}
	
	echo "          <input type=\"hidden\" name=\"sidm\" value=\"".$cdata['smanager']['sid']."\">\n";
	echo "      </td>\n";
	echo "  </tr>\n";
    echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"80px\"><b>Referral</b></td>\n";
	echo "      <td align=\"left\"><input type=\"text\" name=\"refto\" id=\"refto\" class=\"chngval\" value=\"".trim($cdata['refto'])."\" size=\"12\"></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"center\" colspan=\"2\"><hr width=\"90%\"></td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"80px\"><b>Added</b></td>\n";
	echo "      <td align=\"left\">".date('m/d/y',$cdata['added'])."</td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";	
	echo "      <td align=\"right\" width=\"80px\"><b>Updated</b></td>\n";
	echo "      <td align=\"left\">".$cdata['updated']."</td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "      <td align=\"right\" width=\"80px\"><b>Update by</b></td>\n";
	echo "      <td align=\"left\">".$cdata['lupdate']['fname']." ".$cdata['lupdate']['lname']."</td>\n";
	echo "  </tr>\n";
	echo "</table>\n";
}

function CreateEstimate($icid=null) {
    // Creates Stub Estimate Record and Assets
    $out=array('oid'=>0,'estid'=>0);
    
    if (!is_null($icid) and $icid!=0) {
        $qry  = "SELECT C.cid,C.officeid as oid,C.securityid as sid,C.sidm FROM cinfo as C WHERE C.cid=".(int) $icid.";";
        $res = mssql_query($qry);
        $row = mssql_fetch_array($res);
        $nrow= mssql_num_rows($res);
        
        if ($nrow!=0) {
            $oid=$row['oid'];
            $cid=$row['cid'];
            $sid=$row['sid'];
            $sidm=$row['sidm'];
        
            $qryA   = "exec sp_insertest ";
            $qryA  .= "@officeid=".$oid.", ";
            $qryA  .= "@securityid=".$sid.", ";
            $qryA  .= "@sidm=".$sidm.", ";
            $qryA  .= "@status='0', ";
            $qryA  .= "@pft='0', ";
            $qryA  .= "@sqft='0', ";
            $qryA  .= "@apft='0', ";
            $qryA  .= "@shal='0', ";
            $qryA  .= "@mid='0', ";
            $qryA  .= "@deep='0', ";
            $qryA  .= "@deck='0', ";
            $qryA  .= "@spa_pft='0', ";
            $qryA  .= "@spa_sqft='0', ";
            $qryA  .= "@spatype='0', ";
            $qryA  .= "@tzone='0', ";
            $qryA  .= "@erun='0', ";
            $qryA  .= "@prun='0', ";
            $qryA  .= "@renov='0', ";
            $qryA  .= "@btchrg='0', ";
            $qryA  .= "@rtchrg='0', ";
            $qryA  .= "@contractamt='0', ";
            $qryA  .= "@refto='', ";
            $qryA  .= "@est_cost='0', ";
            $qryA  .= "@cid=".$cid.", ";
            $qryA  .= "@unique_id='0', ";
            $qryA  .= "@estAdata='';";
            $resA   = mssql_query($qryA);
            $rowA   = mssql_fetch_row($resA);
            
            //echo $qryA.'<br>';
            
            if (isset($rowA[0]) and $rowA[0]!=0) {
                $out=array('oid'=>$oid,'estid'=>$rowA[0]);
            }
        }
    }
    
    return $out;
}

function EstimateView($oid=null,$estid=null) {
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	echo __FUNCTION__.'<br>';
    
    //$estid  =(!is_null($estid) and $estid!=0)?$estid:(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:null;
    //$oid    =(!is_null($oid) and $oid!=0)?$oid:(isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)?$_REQUEST['oid']:null;
    
    if ((!is_null($estid) and is_numeric($estid) and $estid!=0) and (!is_null($oid) and is_numeric($oid) and $oid!=0)) {
        $bctotal=0;
        $rctotal=0;
        $cctotal=0;
        $bmtotal=0;
        $rmtotal=0;
        $cmtotal=0;
        
        $estData=EstimateData($estid,$oid);
        
        $dbg=0;
        if (isset($dbg) && $dbg==1 && $_SESSION['securityid']==26) {
            echo "<pre>";
            print_r($estData);
            echo "</pre>";
            exit;
        }
    
        $uid    =md5($estData['cid'].'-'.time()).'.'.$_SESSION['securityid'];
        $status =(isset($estData['status']) and $estData['status'] != 0)?'<span id="eststatus" style="color:red;font-weight:bold;" title="You cannot make updates to this Estimate">Locked</span>':'';
        $camt   =(isset($estData['camt']) and $estData['camt']!=0)?number_format($estData['camt'], 2, '.', ''):'0.00';
        
        echo "<script type=\"text/javascript\" src=\"js/jquery_estimates_DEV.js\"></script>\n";
        echo "<input type=\"hidden\" id=\"sysoid\" value=\"".$estData['office']['oid']."\">\n";
        echo "<input type=\"hidden\" id=\"sysestid\" value=\"".$estData['estid']."\">\n";
        echo "<div id=\"statusmessage\"></div>\n";
        echo "<table width=\"950px\" class=\"noPrint\">\n";
        echo "  <tr>\n";
        echo "      <td align=\"right\">\n";
        echo "          <table>\n";
        echo "              <tr>\n";
        echo "                  <td align=\"right\">\n";
        echo "                      <a name=\"top\"></a>\n";
        echo "                  </td>\n";
        echo "                  <td align=\"right\">\n";
        echo "                      <form method=\"post\">\n";
        echo "                      <input type=\"hidden\" value=\"leads\" name=\"action\">\n";
        echo "                      <input type=\"hidden\" value=\"chistory\" name=\"call\">\n";
        echo "                      <input type=\"hidden\" value=\"view\" name=\"rcall\">\n";
        echo "                      <input type=\"hidden\" value=\"".$uid."\" name=\"uid\">\n";
        echo "                      <input type=\"hidden\" value=\"".$estData['cid']."\" name=\"cid\">\n";
        echo "                      <input type=\"hidden\" value=\"".$estData['cid']."\" name=\"custid\">\n";
        echo "                      <button class=\"btnsysmenu\">OneSheet</button>\n";
        echo "                      </form>\n";
        echo "                  </td>\n";
        echo "                  <td align=\"right\">\n";
        echo "						<form method=\"post\" id=\"ViewEstCost\">\n";
		echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
		echo "						<input type=\"hidden\" name=\"call\" value=\"EstimateCost\">\n";
		echo "						<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
		echo "						<input type=\"hidden\" name=\"estid\" value=\"".$estData['estid']."\">\n";
		echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
		echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
        //echo "                      <button class=\"btnsysmenu\">Cost</button>\n";
        echo "                      </form>\n";
        echo "                  </td>\n";
        echo "                  <td align=\"right\"><button id=\"PriceBookControl\" class=\"OpenPB btnsysmenu\">Pricebook</button></td>\n";
        echo "              </tr>\n";
        echo "          </table>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
        echo "<table width=\"950px\">\n";
        echo "  <tr>\n";
        echo "      <td colspan=\"3\">\n";
        echo "          <div class=\"outerrnd\">\n";
        echo "          <table width=\"100%\">\n";
        echo "              <tr>\n";
        echo "                  <td align=\"left\"><b>Retail Estimate</b> ".$estData['estid']."</td>\n";
        echo "                  <td align=\"right\">".$status."</td>\n";
        echo "              </tr>\n";
        echo "          </table>\n";
        echo "          </div>\n";
        echo "      </td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "      <td valign=\"top\" width=\"200px\">\n";
        echo "          <div class=\"outerrnd\">\n";
    
        EstimateCinfo($estData);
    
        echo "          </div>\n";
        echo "          <p>\n";
        echo "          <div class=\"outerrnd\">\n";
        
        EstimatePoolData($estData);
    
        echo "          </div>\n";
        echo "          <p>\n";
        echo "          <div class=\"outerrnd\">\n";
        
        EstimateControl($estData);
        
        echo "          </div>\n";
        echo "      </td>\n";
        echo "      <td valign=\"top\">\n";
        echo "          <div class=\"outerrnd\" id=\"CartDisplayContainer\">\n";
        echo "          <div id=\"CartDisplay\">\n";
        echo "          <table id=\"CartTable\" class=\"breakdown\" width=\"100%\">\n";
        echo "              <tr>\n";
        echo "                  <td class=\"tblhd\" align=\"left\"><b>Category</b></td>\n";
        echo "                  <td class=\"tblhd\" align=\"left\"><b>Item</b></td>\n";
        echo "                  <td class=\"tblhd\" align=\"center\"><b>Quan</b></td>\n";
        echo "                  <td class=\"tblhd\" align=\"center\"><b>Units</b></td>\n";
        echo "                  <td class=\"tblhd\" align=\"center\"><b>Retail</b></td>\n";
        echo "                  <td class=\"tblhd\" align=\"center\"><b>Comm</b></td>\n";
        echo "                  <td class=\"tblhd\" align=\"center\" width=\"20px\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              </tr>\n";
    
        //EstimateCartItems($estData,0);
        EstimateDetailLoad($estData);
        
        echo "          </table>\n";
        echo "          </div>\n";
        echo "          <div id=\"PriceBookDisplay\" style=\"display:none;\"></div>\n";
        echo "      </td>\n";
        echo "      <td valign=\"top\" width=\"200px\">\n";
        echo "          <div class=\"outerrnd tblTotals\">\n";
        echo "          <table class=\"breakdown\" width=\"100%\">\n";
        echo "              <tr id=\"TotalsHeaderLine\">\n";
        echo "              	<td class=\"tblhd\" align=\"left\" valign=\"bottom\"><b>Totals</b></td>\n";
        echo "              	<td class=\"tblhd\" align=\"left\" valign=\"bottom\"></td>\n";
        echo "              	<td class=\"wh\" align=\"center\"><img id=\"refreshCartTotal\" class=\"noPrint setpointer\" src=\"images/arrow_refresh_small.png\" title=\"Refresh Cart Total\"></td>\n";
        echo "			    </tr>\n";
        echo "              <tr id=\"CatalogTotalLine\">\n";
        echo "              	<td class=\"wh\" width=\"85px\" align=\"right\" valign=\"bottom\">Pricebook</td>\n";
        echo "              	<td class=\"wh\" align=\"right\" valign=\"bottom\" style=\"height:30px\"><span id=\"CartCatalogTotal\" style=\"font-size:larger;\">0.00</span></td>\n";
        echo "              	<td class=\"wh\" width=\"20px\" align=\"center\" valign=\"bottom\"><img src=\"images/pixel.gif\"></td>\n";
        echo "			    </tr>\n";
        echo "              <tr id=\"ContractTotalLine\">\n";
        echo "              	<td class=\"wh\" width=\"85px\" align=\"right\" valign=\"bottom\">Contract</td>\n";
        echo "              	<td class=\"wh\" align=\"right\" valign=\"bottom\" style=\"height:30px\"><span id=\"CartContractTotal\" contenteditable=\"true\" style=\"font-size:larger;\">".$camt."</span></td>\n";
        echo "              	<td class=\"wh\" width=\"20px\" align=\"center\" valign=\"bottom\"><img id=\"ContractTotalSave\" class=\"setpointer noPrint\" src=\"images/save.gif\" title=\"Click to modify Contract Total\"></td>\n";
        echo "			    </tr>\n";
        echo "              <tr id=\"DifferenceLine\">\n";
        echo "              	<td class=\"wh\" width=\"85px\" align=\"right\" valign=\"bottom\">Overage<span id=\"OUCalcVar\" style=\"display:none;\">50</span></td>\n";
        echo "              	<td class=\"wh\" align=\"right\" valign=\"bottom\" style=\"height:30px\"><span id=\"CartDiffTotal\" style=\"font-size:larger;\">0.00</span></td>\n";
        echo "              	<td class=\"wh\" width=\"20px\" align=\"center\" valign=\"bottom\"><img src=\"images/pixel.gif\"></td>\n";
        echo "			    </tr>\n";
        //echo "              <tr id=\"GrandTotalLine\">\n";
        //echo "              	<td class=\"wh\" width=\"85px\" align=\"right\" valign=\"bottom\">Over/<span style=\"color:red;\">Under</span></td>\n";
        //echo "              	<td class=\"wh\" align=\"right\" valign=\"bottom\" style=\"height:35px\"><span id=\"CartGrandTotal\" style=\"font-size:larger;\">0.00</span></td>\n";
        //echo "              	<td class=\"wh\" width=\"20px\" align=\"center\" valign=\"bottom\"><img src=\"images/pixel.gif\"></td>\n";
        //echo "			    </tr>\n";
        echo "          </table>\n";
        echo "          </div>\n";
        echo "          <p>\n";
        echo "          <div class=\"outerrnd tblCommissions\">\n";
        echo "          <table class=\"breakdown\" width=\"100%\">\n";
        echo "              <tr id=\"CommHeaderLine\">\n";
        echo "              	<td class=\"tblhd\" align=\"left\"><b>Commissions</b></td>\n";
        echo "              	<td class=\"tblhd\" align=\"left\"></td>\n";
        echo "              	<td class=\"wh\" align=\"center\"><img id=\"ManCommAdd\" class=\"noPrint setpointer\" src=\"images/action_add.gif\" title=\"Add Manual Commission Adjust\"></td>\n";
        echo "			    </tr>\n";
        echo "              <tr id=\"BaseCommLine\">\n";
        echo "              	<td class=\"wh\" width=\"85px\" align=\"right\" valign=\"bottom\">Base</td>\n";
        echo "              	<td class=\"wh\" align=\"right\" valign=\"bottom\" style=\"height:30px\"><span id=\"BaseCommTotal\" style=\"font-size:larger;\">0.00</span></td>\n";
        echo "              	<td class=\"wh\" width=\"20px\" align=\"center\" valign=\"bottom\"><img src=\"images/pixel.gif\"></td>\n";
        echo "			    </tr>\n";
        echo "              <tr id=\"OUTotalLine\">\n";
        echo "              	<td class=\"wh\" width=\"85px\" align=\"right\" valign=\"bottom\">Overage Split</td>\n";
        echo "              	<td class=\"wh\" align=\"right\" valign=\"bottom\" style=\"height:30px\"><span id=\"CartOUTotal\" style=\"font-size:larger;\">0.00</span></td>\n";
        echo "              	<td class=\"wh\" width=\"20px\" align=\"center\" valign=\"bottom\"><img src=\"images/pixel.gif\"></td>\n";
        echo "			    </tr>\n";
        echo "              <tr id=\"CommTotalLine\">\n";
        echo "              	<td class=\"wh\" width=\"85px\" align=\"right\" valign=\"bottom\">Total</td>\n";
        echo "              	<td class=\"wh\" align=\"right\" valign=\"bottom\" style=\"height:35px\"><span id=\"CommGrandTotal\" style=\"font-size:larger;\">0.00</span></td>\n";
        echo "              	<td class=\"wh\" width=\"20px\" valign=\"bottom\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "			    </tr>\n";
        echo "          </table>\n";
        echo "          </div>\n";
        echo "		</td>\n";
        echo "	</tr>\n";
        echo "</table>\n";
    
        /*
        // Totals Table Calcs
        $bccost  =$bctotal;
        $rccost  =$rctotal;
        $cccost  =$cctotal;
        $bmcost  =$bmtotal;
        $rmcost  =$rmtotal;
        $trccost =$rccost+$rmcost;
        $cmcost  =$cmtotal;
        $tbcost  =$bccost+$bmcost;
        $trcost  =$pbaseprice+$trccost+$tbid;
        $tccost  =$cccost+$cmcost;
        $trcomm  =$bcomm+$tccost;
        $prof    =($trcost-$tbcost)-$trcomm;
        
        if ($prof!=0)
        {
            $perprof =$prof/$trcost;
        }
        else
        {
            $perprof =0;
        }
    
        if ($rowC[2]==1)
        {
            $rtax    =$ctramt*$taxrate[1];
            $grtcost =$ctramt+$rtax;
            $frtax   =number_format($rtax, 2, '.', '');
            $fgrtcost=number_format($grtcost, 2, '.', '');
        }
    
        $fbccost		=number_format($bccost, 2, '.', '');
        $fbmcost		=number_format($bmcost, 2, '.', '');
        $fcccost		=number_format($cccost, 2, '.', '');
        $frccost		=number_format($rccost, 2, '.', '');
        $frmcost		=number_format($rmcost, 2, '.', '');
        $fcmcost		=number_format($cmcost, 2, '.', '');
        $ftbcost		=number_format($tbcost, 2, '.', '');
        $ftrcost		=number_format($trcost, 2, '.', '');
        $ftccost		=number_format($tccost, 2, '.', '');
        $ftrcomm		=number_format($trcomm, 2, '.', '');
    
        echo "           <tr>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><b>Price per Book</b></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><div id=\"ppbook\">".$ftrcost."</div></td>\n";
        echo "              <td class=\"wh\" align=\"right\">".$ftrcomm."</td>\n";
        echo "              <td class=\"wh\" align=\"center\">\n";
    
        //echo 'C:'.$rowC[14];
    
        if ($rowC[14] == 2) // Adjust PpB Enable for Franchises / Blocked for P&A
        {
            $adjupdate=6;
        }
        else
        {
            $adjupdate=7;
        }
        
        if ((isset($_SESSION['modcomm']) and $_SESSION['modcomm'] >= 1) and (isset($rowpreAa['mas_prep']) and $rowpreAa['mas_prep'] < 1) and $cdata['jobid']=='0')
        {
            echo "				<span class=\"JMStooltip noPrint\" id=\"OpenPBAdjustDialog\" title=\"Adjust Price per Book\"><a href=\"#\"><img src=\"../images/calculator_edit.png\"></a></span>\n";
        }
        else
        {
            echo "				<img src=\"images/pixel.gif\">\n";
        }
        
        echo "				</td>\n";
        echo "           </tr>\n";
        
        calc_adjusts_EXIST($estData['com_base_rate']);
        
        if (!isset($rowC[5])||!is_numeric($rowC[5]))
        {
            $bullet_rate=0;
        }
        else
        {
            $bullet_rate=$rowC[5];
        }
    
        $adjbookamt	=$trcost+$discount;
        $fadjbookamt=number_format($adjbookamt, 2, '.', '');
        $commarray['fadjbookamt']=$fadjbookamt;
    
        if ($cdata['renov']==1)
        {
            $adjctramt	=0;
        }
        else
        {
            $adjctramt	=$ctramt-$adjbookamt;
        }
        
        $fadjctramt	=number_format($adjctramt, 2, '.', '');
        
        $adjcomm	=0;
    
        $ou_out		=calc_ou($adjctramt,$adjcomm,$tbullets,$rowC[6],$estData['applyov'],$estData['comadj'],$bullet_rate,$rowC[7]);
    
        $foucomm	=number_format($ou_out[0], 2, '.', '');
        $fadjcomm	=number_format($ou_out[1], 2, '.', '');
        $commarray['fadjcomm']=$fadjcomm;
    
        if ($cdata['applyov']==1)
        {
            $tadjcomm	=$trcomm+$fadjcomm;
        }
        else
        {
            $tadjcomm	=$trcomm;
        }
    
        // Set commission for global
        $cdata['comt']	=$tadjcomm;
        $ftadjcomm			=number_format($tadjcomm, 2, '.', '');
    
        //echo "			<table class=\"outer\" background=\"white\" bordercolor=\"gray\" border=1>\n";
        echo "           <tr>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><b>Adjusted Book Price</b></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><div id=\"apbook\">".$fadjbookamt."</div></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
        echo "           </tr>\n";
        echo "           <tr>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><b>Retail Contract Price</b></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\" valign=\"bottom\">\n";
        
        if ($estData['status'] <= 1)
        {
            echo "			<form id=\"AdjRetailPrice\" method=\"post\">\n";
            echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
            echo "			<input type=\"hidden\" name=\"call\" value=\"update_contract_amt\">\n";
            echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
            echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
            echo "			<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
            echo "			<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
            echo "			<input class=\"transnbtextright formatCurrency\" type=\"text\" id=\"c_amt\" name=\"c_amt\" size=\"6\" maxlength=\"10\" value=\"".$fctramt."\">\n";
            echo "			</form>\n";
        }
        else
        {
            echo $fctramt;
        }
        
        echo "              </td>\n";
        echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"center\">\n";
        echo "					<div class=\"noPrint\">\n";
        
        if ($cdata['status'] <= 1)
        {
            echo "                  <img class=\"transnb_button\" src=\"images/save.gif\" id=\"SubmitAdjRetailPrice\" title=\"Save Retail Amount\">\n";
        }
    
        echo "					</div>\n";
        echo "              </td>\n";
        echo "           </tr>\n";
        echo "           <tr>\n";
        echo "				<td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."px\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[1]."px\"><b>Over/<font color=\"red\">Under</font> Book</b></td>\n";
        echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."px\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[3]."px\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."px\">\n";
        
        if (($commarray['fctramt'] - $commarray['fadjbookamt']) < 0)
        {
            echo "					<font color=\"red\"><div id=\"oubook\">".number_format(($commarray['fctramt'] - $commarray['fadjbookamt']), 2, '.', '')."</div></font>\n";
        }
        else
        {
            echo "					<div id=\"oubook\">".number_format(($commarray['fctramt'] - $commarray['fadjbookamt']), 2, '.', '')."</div>\n";
        }
        
        echo "				</td>\n";
        echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."\"><img src=\"images/pixel.gif\"></td>\n";
        echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><img src=\"images/pixel.gif\"></td>\n";
        echo "           </tr>\n";
        
        $cdata['ou']=number_format(($commarray['fctramt'] - $commarray['fadjbookamt']), 2, '.', '');
        
        if ($rowC[2]==1)
        {
            echo "			<tr>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "				<td class=\"wh\" align=\"right\"><b>Tax (".$taxrate[1]."):</b></td>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "				<td class=\"wh\" align=\"right\">".$frtax."</td>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "			</tr>\n";
            echo "			<tr>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "				<td class=\"wh\" align=\"right\"><b>Total</b></td>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "				<td class=\"wh\" align=\"right\">".$fgrtcost."</td>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
            echo "			</tr>\n";
            $commarray['frtax']=$frtax;
        }
    
        $estData['commarray']['tbullets']			=$tbullets;
        $estData['commarray']['estidret']			=$estidret;
        //$estData['commarray']['sidm']				=$cdata['sidm'];
        $estData['commarray']['taxtrig']			=$rowC[2];
        $estData['missing_bid_items']	=array();
        
        if (isset($fctramt) and $fctramt > 0)
        {
            if ($nrowQ > 0)
            {
                if ($cdata['jobid']!='0')
                {
                    if ($_REQUEST['call']='est')
                    {
                        $tadjcomm=CommissionScheduleRO_After_Contract_Est($commarray,$col_struct);
                    }
                    else
                    {
                        $tadjcomm=CommissionScheduleRO_After_Contract($commarray,$col_struct);
                    }
                }
                else
                {
                    if (isset($_SESSION['modcomm']) and $_SESSION['modcomm'] >= 1) //Commission Edit Ability
                    {
                        $tadjcomm=retail_csched_ro($commarray,$col_struct);
                    }
                    else
                    {
                        $tadjcomm=CommissionScheduleRO_NEW($commarray,$col_struct);
                    }
                }
            }
            else
            {
                echo "				<tr>\n";
                echo "					<td colspan=\"7\" align=\"center\"><b>Commission Schedules have not been created for this Office. Contact BH National Management.</b></td>\n";
                echo "				</tr>\n";
            }
        }
        
        echo "         			</table>\n";
        echo "				</td>\n";
        echo "            </tr>\n";
        echo "         </table>\n";
        echo "      </td>\n";
        echo "		<td valign=\"top\" align=\"left\">\n";
        echo "			<div class=\"noPrint\">\n";
        echo "			<table class=\"transnb\" width=\"70px\" border=0>\n";
        echo "				<tr>\n";
        echo "					<td align=\"left\">\n";	
        
        if ($cdata['status'] >= 2)
        {
            echo "                  	<input class=\"LockedEst buttondkgrypnl60\" value=\"Edit Items\">\n";
        }
        else
        {
            echo "						<form method=\"post\">\n";
            echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
            echo "						<input type=\"hidden\" name=\"call\" value=\"view_addnew\">\n";
            echo "						<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
            echo "						<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
            echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
            echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
            echo "                  	<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Edit Items\">\n";
            echo "						</form>\n";
        }
    
        echo "					</td>\n";
        echo "				</tr>\n";
        echo "				<tr>\n";
        echo "					<td align=\"left\">\n";
        echo "					<form method=\"post\">\n";
        echo "					<input type=\"hidden\" name=\"action\" value=\"est\">\n";
        echo "					<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
        echo "					<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
        echo "					<input type=\"hidden\" name=\"rcall\" value=\"".$_REQUEST['call']."\">\n";
        echo "					<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
        echo "					<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
        echo "					<input type=\"hidden\" name=\"cid\" value=\"".$rowI[10]."\">\n";
        echo "					<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"OneSheet\">\n";
        echo "					</form>\n";
        echo "					</td>\n";
        echo "				</tr>\n";
    
        if ($_SESSION['elev'] >= 1)
        {
            if ($rowI[11]=='0' and $rowI[12]=='0')
            {
                echo "            <tr>\n";
                echo "               <td align=\"left\">\n";
                echo "					<hr width=\"90%\">\n";
                echo "				</td>\n";
                echo "            </tr>\n";
                echo "            <tr>\n";
                echo "					<td align=\"left\">\n";
                echo "				<form method=\"POST\">\n";
                echo "					<input type=\"hidden\" name=\"action\" value=\"est\">\n";
                echo "					<input type=\"hidden\" name=\"call\" value=\"delete_est1\">\n";
                echo "					<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
                echo "					<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
                echo "         			<input type=\"hidden\" name=\"uid\" value=\"XXX\">\n";
                echo "					<input class=\"buttondkgrypnl60\" type=\"submit\" value=\"Delete Est\">\n";
                echo "				</form>\n";
                echo "					</td>\n";
                echo "            </tr>\n";
            }
        }
        
        if ($cdata['jobid']!='0' || $cdata['njobid']!='0')
        {
            $cdata['allowdel']	=1;
        }
        else
        {
            $cdata['allowdel']	=0;
        }
    
        if ($_SESSION['elev'] >= 6 || $_SESSION['clev'] >= 6 || $_SESSION['jlev'] >= 6)
        {
            echo "            <tr>\n";
            echo "               <td align=\"left\">\n";
            echo "					<hr width=\"90%\">\n";
            echo "				</td>\n";
            echo "            </tr>\n";
            echo "            <tr>\n";
            echo "               <td align=\"left\">\n";
            echo "						<form method=\"post\" id=\"ViewEstCost\">\n";
            echo "						<input type=\"hidden\" name=\"action\" value=\"est\">\n";
            echo "						<input type=\"hidden\" name=\"call\" value=\"view_cost\">\n";
            echo "						<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
            echo "						<input type=\"hidden\" name=\"estid\" value=\"".$estidret."\">\n";
            echo "						<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
            echo "						<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
            echo "						<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
    
            if ($rowC[9]==1)
            {
                echo "                  <input class=\"buttondkgrypnl60\" type=\"submit\" value=\"View Cost\">\n";
            }
    
            echo "						</form>\n";
            echo "               </td>\n";
            echo "            </tr>\n";
        }
    
        echo "			</table>\n";
        echo "			</div>\n";
        //echo "		</td>\n";

        echo "		</td>\n";
        echo "	</tr>\n";
        echo "</table>\n";
        echo "		</td>\n";
        echo "	</tr>\n";
        echo "</table>\n";        
        
        if ($_SESSION['modcomm'] >= 1)
        {
            echo "<span id=\"PBAdjustDialog\" title=\"Adjust Price per Book\">\n";
            echo "	<form id=\"SubmitPBAdjust\" method=\"post\">\n";
            echo "		<input type=\"hidden\" name=\"action\" value=\"est\">\n";
            echo "		<input type=\"hidden\" name=\"call\" value=\"adjins\">\n";
            echo "		<input type=\"hidden\" name=\"estid\" value=\"".$_SESSION['estid']."\">\n";
            echo "		<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
            echo "		<input type=\"hidden\" name=\"securityid\" value=\"".$_SESSION['securityid']."\">\n";
            echo "		<input type=\"hidden\" name=\"tranid\" value=\"".$uid."\">\n";
            echo "		Adjust Amount<br>";
            echo "		<input class=\"bboxbr\" name=\"adjamt\" id=\"PBadjamt\" value=\"0.00\" type=\"text\" size=\"6\" maxlength=\"9\"><br>\n";
            echo "		Comment<br>\n";
            echo "		<textarea name=\"descrip\" type=\"text\" cols=\"45\" rows=\"2\"></textarea>\n";
            echo "	</form>\n";
            echo "</span>\n";
        }
        
        if ($_SESSION['modcomm'] >= 1)
        {
            
            echo "<span id=\"BaseCommAdjustDialog\" title=\"Adjust Base Commission\">\n";
            echo "<br>\n";
            echo "<span id=\"origbaseamt\">".$commarray['fadjbookamt']."</span>\n";
            echo "<table align=\"center\">\n";
            //echo "<tr><td align=\"right\"></td><td align=\"right\">Reset</td><td align=\"center\"><img id=\"BaseCommAdjustReset\" src=\"images/arrow_refresh_small.png\"></td></tr>\n";
            
            if ($commarray['tbullets'] >= 3)
            {
                echo "<tr><td align=\"right\">Price per Book</td><td align=\"right\"><span id=\"baseamt\">".$commarray['fadjbookamt']."</span></td><td></td></tr>\n";
            }
            else
            {
                if (isset($commarray['fctramt']) and $commarray['fctramt']!=0)
                {
                    echo "<tr><td align=\"right\">Contract Amount</td><td align=\"right\"><span id=\"baseamt\">".$commarray['fadjbookamt']."</span></td><td></td></tr>\n";
                }
                else
                {
                    echo "<tr><td align=\"right\">Contract Amount</td><td align=\"right\"><span id=\"baseamt\">0</span></td><td></td></tr>\n";
                }
            }
            
            //echo "<tr><td align=\"right\">Rate</td><td align=\"right\"><input class=\"bboxbc\" type=\"text\" id=\"baserate\" value=\"0\" size=\"2\"></td><td><img id=\"baserateinc\" src=\"images/arrow_up.png\"><img id=\"baseratedec\" src=\"images/arrow_down.png\"></td></tr>\n";
            echo "<tr><td align=\"right\">Rate</td><td align=\"right\"><span id=\"baserate\">0</span></td><td>%</td></tr>\n";
            echo "<tr><td align=\"right\">Adj Base Comm</td><td align=\"right\"><span id=\"basecommadj\">0</span></td><td></td></tr>\n";
            echo "</table>\n";
            echo "</span>\n";
        }
        
        $cdata['tcomm']		=$tadjcomm;
        $cdata['tretail']	=$adjbookamt;
        $cdata['tcontract']	=$ctramt;
        $cdata['acctotal']	=$trccost;
        $cdata['discount']	=$vdiscnt;
        $cdata['royrel']	=0;
        $cdata['custallow']	=0;
        */
    }
    else {
    	die("Fatal Error: Estimate ID not set!");
	}
}

function EstimateCost($oid,$estid) {
	$MAS		=$_SESSION['pb_code'];
	global 		$bctotal,$rctotal,$cctotal,$bmtotal,$rmtotal,$cmtotal,$showdetail,$callow,$ref1,$ref2,$discount,$invarray,$estidret,$taxrate;

	//$viewarray	=$_SESSION['viewarray'];
	$securityid =$_SESSION['securityid'];

	if (!isset($estid) and $estid!=0) {
		echo "Fatal Error: var estid not set!";
		exit;
	}
	
	if ($_SESSION['securityid']==26999999999999999999999999999999999999999) {
		echo '<pre>';
		
		print_r($_REQUEST);
		
		echo '</pre>';
	}

	$qrypreA = "SELECT
                    estid,pft,sqft,spatype,spa_pft,spa_sqft,tzone,
                    contractamt,cfname,clname,phone,status,comments,
                    shal,mid,deep,cid,securityid,deck1,erun,prun,
                    jobid,comadj,sidm,buladj,applyov,applybu,refto,
                    apft,added,updated,updateby,ccid
                FROM est WHERE officeid=".(int) $oid." AND estid=".(int) $estid.";";
	$respreA = mssql_query($qrypreA);
	$rowpreA = mssql_fetch_array($respreA);

	$jsecurityid =$rowpreA['securityid'];

	$qrypreD = "SELECT estdata FROM est_acc_ext WHERE officeid=".(int) $oid." AND estid=".(int) $estid.";";
	$respreD = mssql_query($qrypreD);
	$rowpreD = mssql_fetch_row($respreD);

	$ps1        =$rowpreA[1];
	$ps2        =$rowpreA[2];
	$spa1       =$rowpreA[3];
	$spa2       =$rowpreA[4];
	$spa3       =$rowpreA[5];
	$tzone      =$rowpreA[6];
	$contractamt=$rowpreA[7];
	$cfname     =$rowpreA[8];
	$clname     =$rowpreA[9];
	$phone      =$rowpreA[10];
	$status     =$rowpreA[11];
	$ps5        =$rowpreA[13];
	$ps6        =$rowpreA[14];
	$ps7        =$rowpreA[15];
	
	if (isset($viewarray['acctotal']) and $viewarray['acctotal']!=0) {
		$acctotal=$viewarray['acctotal'];
	}
	else {
		$acctotal=0;
	}

	$qryC = "SELECT officeid,name,stax,sm,gm,psched,psched_perc,pft_sqft FROM offices WHERE officeid=".(int) $oid.";";
	$resC = mssql_query($qryC);
	$rowC = mssql_fetch_row($resC);

	if ($rowC[7]=="p") {
		$defmeas=$rowpreA['pft'];
	}
	else {
		$defmeas=$rowpreA['sqft'];
	}

	$qryA = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid=".(int) $oid." ORDER BY quan ASC;";
	$resA = mssql_query($qryA);

	$qryB = "SELECT id,quan,price,comm FROM rbpricep WHERE officeid=".(int) $oid." AND quan='".$defmeas."';";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);

	$qryD = "SELECT securityid,fname,lname FROM security WHERE securityid='".$jsecurityid."';";
	$resD = mssql_query($qryD);
	$rowD = mssql_fetch_row($resD);

	$qryE = "SELECT typeid,name FROM spatypes ORDER BY typeid ASC;";
	$resE = mssql_query($qryE);

	$qryF = "SELECT snum,cat,description FROM status_codes WHERE snum=".$rowpreA['status'].";";
	$resF = mssql_query($qryF);
	$rowF = mssql_fetch_row($resF);

	$qryG = "SELECT zid,name FROM zoneinfo WHERE officeid=".(int) $oid." ORDER BY zid ASC;";
	$resG = mssql_query($qryG);

	$qryI = "SELECT custid,cfname,clname,chome,scounty,saddr1,scity,sstate,szip1,ccell FROM cinfo WHERE officeid=".(int) $oid." AND custid=".(int) $rowpreA['cid'].";";
	$resI = mssql_query($qryI);
	$rowI = mssql_fetch_array($resI);

	$qryL = "SELECT securityid,fname,lname FROM security WHERE securityid=".(int) $rowpreA['sidm'].";";
	$resL = mssql_query($qryL);
	$rowL = mssql_fetch_row($resL);

	if ($rowpreA['updateby']!=0)
	{
		$qryM = "SELECT securityid,fname,lname FROM security WHERE securityid=".(int) $rowpreA['updateby'].";";
		$resM = mssql_query($qryM);
		$rowM = mssql_fetch_array($resM);

		$lupdatestr=$rowM['fname']." ".$rowM['lname'];
	}
	else
	{
		$lupdatestr="";
	}

	// Sets Tax Rate
	if ($rowC[2]==1)
	{
		$qryJ 	= "SELECT taxrate FROM taxrate WHERE id='".$rowI[4]."';";
		$resJ 	= mssql_query($qryJ);
		$rowJ 	= mssql_fetch_row($resJ);

		$taxrate	=array(0=>$rowI[4],1=>$rowJ[0]);

		$qryK = "SELECT id,city FROM taxrate WHERE officeid=".(int) $oid." ORDER BY city ASC";
		$resK = mssql_query($qryK);

		$viewarray['taxrate']	=$taxrate[1];
		$viewarray['tax']		=$rowpreA['contractamt']*$taxrate[1];
		$viewarray['were']		="from Dynamic";
	}

	if (!empty($rowpreA['added']))
	{
		$atime=date("m/d/Y", strtotime($rowpreA['added']));
	}
	else
	{
		$atime="";
	}

	if (!empty($rowpreA['updated']))
	{
		$utime=date("m/d/Y", strtotime($rowpreA['updated']));
	}
	else
	{
		$utime="";
	}

	$set_ia		=calc_internal_area($rowpreA['pft'],$rowpreA['sqft'],$rowpreA['shal'],$rowpreA['mid'],$rowpreA['deep']);
	$set_gals	=calc_gallons($rowpreA['pft'],$rowpreA['sqft'],$rowpreA['shal'],$rowpreA['mid'],$rowpreA['deep']);
	$estidret   =$rowpreA['estid'];
	$vdiscnt    =$viewarray['discount'];
	$pbaseprice =$rowB['price'];
	$bcomm      =$rowB['comm'];
	$fpbaseprice=number_format($pbaseprice, 2, '.', '');
	$brdr=0;

	echo "<script type=\"text/javascript\" src=\"js/jquery_estimate_func.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"js/jquery_estimate_cost_func.js\"></script>\n";
	echo "<table width=\"950px\">\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "            <tr>\n";
	echo "               <td colspan=\"3\" align=\"right\" >\n";
	echo "                  <table class=\"outer\" width=\"100%\" border=\"".$brdr."\">\n";
	echo "                     <tr>\n";
	echo "							<td class=\"gray\" align=\"left\" valign=\"top\"><b>Cost Estimate</b> ".$rowC[1]."</td>\n";
	echo "							<td class=\"gray\" align=\"right\"><b>\n";
	?>
		
		<script type="text/javascript">
            setLocalTime();
        </script>
		
	<?php
	echo "							</b></td>\n";
	echo "                     </tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"25%\">\n";
	echo "                  <table class=\"outer\" width=\"100%\" height=\"200\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" valign=\"top\">\n";

	//	Customer Display Start
	cinfo_display($rowpreA['cid'],$rowC[2]);
	// Customer Display End

	echo "							</td>\n";
	echo "						</tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"50%\">\n";
	echo "                  <table class=\"outer\" width=\"100%\" height=\"200\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" valign=\"top\">\n";

	// Pool Display Start
	pool_detail_display($estid);
	// Pool Display End

	echo "							</td>\n";
	echo "						</tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "               <td valign=\"top\" align=\"right\" width=\"25%\">\n";
	echo "                  <table class=\"outer\" width=\"100%\" height=\"200\">\n";
	echo "						<tr>\n";
	echo "							<td class=\"gray\" valign=\"top\">\n";
	echo "								<table width=\"100%\">\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Estimate</b></td>\n";
	echo "										<td align=\"left\">".$estidret."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>SalesRep</b></td>\n";
	echo "										<td align=\"left\">".$rowD[1]." ".$rowD[2]."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "				                        <td align=\"right\"><b>Sales Manager</b></td>\n";
	
	if ($rowpreA['sidm']!=0)
	{
		echo "				                        <td align=\"left\">".$rowL[1]." ".$rowL[2]."</td>\n";
	}
	else
	{
		echo '<td align=\"left\">None Assigned</td>';
	}
	
	echo "				                     </tr>\n";
	echo "									<tr>\n";
	echo "				                        <td colspan=\"2\" align=\"center\"><hr width=\"90%\"></td>\n";
	echo "				                     </tr>\n";
	echo "				                     <tr>\n";
	echo "										<td align=\"right\"><b>Added</b>&nbsp</td>\n";
	echo "										<td align=\"left\">".$atime."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Updated</b>&nbsp</td>\n";
	echo "										<td align=\"left\">".$utime."</td>\n";
	echo "									</tr>\n";
	echo "									<tr>\n";
	echo "										<td align=\"right\"><b>Update by</b></td>\n";
	echo "										<td align=\"left\">".$lupdatestr."</td>\n";
	echo "									</tr>\n";
	echo "                  			</table>\n";
	echo "							</td>\n";
	echo "						</tr>\n";
	echo "                  </table>\n";
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"bottom\" align=\"left\">\n";
	echo "			<div class=\"noPrint\">\n";
	echo "			<form method=\"post\">\n";
	echo "			<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "			<input type=\"hidden\" name=\"call\" value=\"EstimateView\">\n";
	echo "			<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "			<input type=\"hidden\" name=\"estid\" value=\"".$estid."\">\n";
	echo "			<input type=\"hidden\" name=\"officeid\" value=\"".$oid."\">\n";
	echo "			<input type=\"hidden\" name=\"securityid\" value=\"".$securityid."\">\n";
	echo "			<input type=\"hidden\" name=\"refto\" value=\"none\">\n";
	echo "			<input class=\"buttondkgrypnl\" type=\"submit\" value=\"Retail\">\n";
	echo "			</form>\n";
	echo "			</div>\n";
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"left\">\n";
	echo "         <table align=\"center\" width=\"100%\" border=".$brdr.">\n";
	echo "            <tr>\n";
	echo "               <td valign=\"top\" align=\"left\" width=\"100%\">\n";

	//	Bids Rollup Display
	costadj_rollup_disp($oid,$rowpreA['cid'],$estid,0,"e");
	
	echo "               </td>\n";
	echo "            </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "		<td></td>\n";
	echo "   </tr>\n";
	echo "   <tr>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"gray\" border=1>\n";

	calcbyphsL($rowpreD[0],0,0,0);
	$bccost  =$bctotal;
	$fbccost =number_format(round($bccost), 2, '.', '');

	echo "           <tr>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td colspan=\"3\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
	}
	else
	{
		echo "              <td colspan=\"5\" class=\"wh\" align=\"right\"><b>Labor Total</b></td>\n";
	}

	echo "              <td class=\"wh\" align=\"right\"><b>".$fbccost."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	echo "         <br>\n";
	echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"gray\" border=1>\n";

	calcbyphsM($rowpreD[0],0,0);
	$bmcost  =$bmtotal;
	$fbmcost =number_format(round($bmcost), 2, '.', '');

	echo "           <tr>\n";

	if (empty($_REQUEST['showtotals'])||$_REQUEST['showtotals']==0)
	{
		echo "              <td colspan=\"3\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
		echo "              <td class=\"wh\" align=\"right\"></td>\n";
	}
	else
	{
		echo "              <td colspan=\"5\" class=\"wh\" align=\"right\"><b>Material Total</b></td>\n";
	}

	echo "              <td class=\"wh\" align=\"right\"><b>".$fbmcost."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";

	echo "         <br>\n";

	// Total Table
	$custallow	=$viewarray['custallow'];
	$tcustallow	=$custallow*-1;
	$tcontract	=0;
	$tcontract	=$viewarray['camt'];
	$tbcost		=round($bccost+$bmcost);

	if ($rowC[2]==1)
	{
		$tax			=$tcontract*$taxrate[1];
		//$tax			=round($tax);
		$tcontract	=$tcontract+$tax;
	}

	if ($tcustallow != 0)
	{
		$tadjcontract	=$tcontract+$tcustallow;
	}
	else
	{
		$tadjcontract	=$tcontract;
	}

	if ($tcustallow != 0)
	{
		$tadjbcost		=round($tbcost+$tcustallow);
	}
	else
	{
		$tadjbcost		=round($tbcost);
	}

	//$tgross		=$tbcost;

	if ($tcustallow != 0)
	{
		$tprofit		=$tadjcontract-$tadjbcost;
	}
	else
	{
		$tprofit		=$tcontract-$tbcost;
	}

	if ($tcontract!=0)
	{
		if ($tcustallow != 0)
		{
			$netper  =$tprofit/$tadjcontract;
		}
		else
		{
			$netper  =$tprofit/$tcontract;
		}
	}
	else
	{
		$netper  =0;
	}

	$ftcustallow	=number_format($tcustallow, 2, '.', '');
	$ftcontract 	=number_format($tcontract, 2, '.', '');
	$ftadjcontract 	=number_format($tadjcontract, 2, '.', '');
	$ftbcost		=number_format($tbcost, 2, '.', '');
	$ftadjbcost		=number_format($tadjbcost, 2, '.', '');
	$ftprofit		=number_format($tprofit, 2, '.', '');
	$fnetper 		=round($netper, 2)*100;

	echo "         <table class=\"outer\" width=\"100%\" bordercolor=\"gray\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td colspan=\"2\" class=\"wh\" align=\"left\"><b>Totals</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Retail Contract Price</b></td>\n";
	echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftcontract."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td width=\"65\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Adjusted Contract Price</b></td>\n";
		echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftadjcontract."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Construction Total</b></td>\n";
	echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftbcost."</b></td>\n";
	echo "           </tr>\n";

	if ($tcustallow != 0)
	{
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Customer Allowance</b></td>\n";
		echo "              <td width=\"65\" class=\"wh\" align=\"right\"><font color=\"red\">".$ftcustallow."</font></td>\n";
		echo "           </tr>\n";
		echo "           <tr>\n";
		echo "              <td class=\"wh\" align=\"right\"><b>Adjusted Construction Total</b></td>\n";
		echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftadjbcost."</b></td>\n";
		echo "           </tr>\n";
	}

	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Net</b></td>\n";
	echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$ftprofit."</b></td>\n";
	echo "           </tr>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Net %</b></td>\n";
	echo "              <td width=\"65\" class=\"wh\" align=\"right\"><b>".$fnetper."</b></td>\n";
	echo "           </tr>\n";
	echo "         </table>\n";
	echo "      </td>\n";
	echo "      <td valign=\"top\" align=\"center\">\n";
	
	_show_hide_objects();
	
	echo "      </td>\n";
	echo "   </tr>\n";
	echo "</table>\n";
	
	$_SESSION['viewarray']=$viewarray;
}

function frm_hdr_csched($cinar)
{
	echo "<form method=\"post\" id=\"frmCreateContract\">\n";
	echo "	<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "	<input type=\"hidden\" name=\"estid\" value=\"".$cinar['estidret']."\">\n";
	echo "	<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "	<input type=\"hidden\" name=\"call\" value=\"CreateContract\">\n";
	echo "	<input type=\"hidden\" name=\"adjbook\" value=\"".number_format($cinar['fadjbookamt'], 2, '.', '')."\">\n";
	echo "	<input type=\"hidden\" name=\"oubook\" value=\"".number_format(($cinar['fctramt'] - $cinar['fadjbookamt']), 2, '.', '')."\">\n";

	if ($cinar['taxtrig']==1)
	{
		echo "	<input type=\"hidden\" name=\"salestax\" value=\"".$cinar['frtax']."\">\n";
	}
}

function col_hdr_csched($col_struct)
{
	//echo "			<table class=\"outer\" background=\"white\" bordercolor=\"gray\" border=1>\n";
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[0]."\"><b>Commissions</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[1]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[2]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[3]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[4]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" width=\"".$col_struct[5]."\"><img src=\"images/pixel.gif\"></td>\n";
	//echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\" width=\"".$col_struct[6]."\"><span class=\"JMStooltip noPrint\" id=\"OpenMCODialog\" title=\"Add Manual Commission\"><a href=\"#\"><img src=\"../images/action_add.gif\"></a></span></td>\n";
	echo "           </tr>\n";
}

function proc_csched($cinar,$commcat_ar)
{
	$tcomm=0;
	$default_csched=array(1,2,6,8,9);
	$csched=get_csched_items($_SESSION['officeid'],$cinar);
	$disp_ar=array();
	$diff=0;
	
	if ($_SESSION['securityid']==26)
	{
		//show_array_pre($csched);
		//show_array_pre($cinar);
	}
	
	if (is_array($csched))
	{
		foreach($csched as $cn => $cv)
		{
			//echo '<pre>';
			//print_r($cv);		
			//echo '</pre><br><br>';

			if (in_array($cn,$default_csched))
			{
				if ($cn==1) // Base Comm
				{
					$active_cs=$cv[0][0];
					$rate_disp=($active_cs['rate'] * 100);
					$ctype_proc=$active_cs['ctype'];
					
					if ($active_cs['trgval'] == 1)
					{
						$amt=$cinar['fctramt'] * $active_cs['rate'];
					}
					elseif ($active_cs['trgval'] == 3) // Adjusted Price per Book
					{
						$amt=$cinar['fadjbookamt'] * $active_cs['rate'];
					}
					else
					{
						$amt=$cinar['fctramt'] * $active_cs['rate'];
					}
					
					$tcomm=$tcomm + $amt;
					$disp_ar[]=$cn;
					
					$det_ar=array(
								'cmid'=>$cn,
								'secid'=>$active_cs['secid'],
								'catid'=>$cn,
								'label'=>$active_cs['label'],
								'label_disp'=>$commcat_ar[$cn]['fullname'],
								'ctype'=>$active_cs['ctype'],
								'ctype_disp'=>set_ctype_display($ctype_proc),
								'rate'=>$active_cs['rate'],
								'rate_disp'=>$rate_disp,
								'amt'=>$amt,
								'amt_disp'=>number_format($amt, 2, '.', ''),
								'diff'=>$diff,
								'diff_disp'=>number_format($diff, 2, '.', ''),
								'd1'=>$active_cs['d1'],
								'd2'=>$active_cs['d2'],
								'thresh'=>$active_cs['thresh'],
								'tcomm'=>$tcomm);
				}
				elseif ($cn==2) // O/U Comm
				{					
					if (($cinar['fctramt'] - $cinar['fadjbookamt']) != 0)
					{
						$active_cs=$cv[0][0];
						$rate_disp=($active_cs['rate'] * 100);
						$ctype_proc=$active_cs['ctype'];
						
						$amt=($cinar['fctramt'] - $cinar['fadjbookamt']) * $active_cs['rate'];
						$disp_ar[]=$cn;
						
						$tcomm=$tcomm + $amt;
						$det_ar=array(
								'cmid'=>$cn,
								'secid'=>$active_cs['secid'],
								'catid'=>$cn,
								'label'=>$active_cs['label'],
								'label_disp'=>$commcat_ar[$cn]['fullname'],
								'ctype'=>$active_cs['ctype'],
								'ctype_disp'=>set_ctype_display($ctype_proc),
								'rate'=>$active_cs['rate'],
								'rate_disp'=>$rate_disp,
								'amt'=>$amt,
								'amt_disp'=>number_format($amt, 2, '.', ''),
								'diff'=>$diff,
								'diff_disp'=>number_format($diff, 2, '.', ''),
								'd1'=>$active_cs['d1'],
								'd2'=>$active_cs['d2'],
								'thresh'=>$active_cs['thresh'],
								'tcomm'=>$tcomm);
					}
				}
				elseif ($cn==6) // Bullet Comm
				{
					$active_cs=$cv;
					
					if (is_array($active_cs) and (isset($cinar['tbullets']) and $cinar['tbullets'] > 0) and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
					{
						//echo '<br>TBULLETS: '. $cinar['tbullets'];
						foreach ($active_cs as $gn1 => $gv1)
						{
							$tsval=0;
							$cblock=false;
							foreach ($gv1 as $gn2 => $gv2)
							{
								//show_array_vars($gv2);
								if ($gv2['thresh'] <= $cinar['tbullets'] and !$cblock)
								{
									//show_array_vars($gv2);
									if ($gv2['trgsrc']==4 || $gv2['trgsrc']==6)
									{
										//show_array_vars($gv2);
										$tbamt=0;
										if ($gv2['ctype']==1) //Fixed
										{
											$rate	=0;
											$amt 	=$gv2['amt'];
										}
										elseif ($gv2['ctype']==2) // Percent
										{
											if ($gv2['trgval']==1) //Contract Amt
											{
												$amt=($cinar['fadjbookamt'] * $gv2['rate']);
											}
											elseif ($gv2['trgval']==2) //
											{
												$amt=(($cinar['fctramt']-$cinar['fadjbookamt']) * ($gv2['rate'] * .01));
											}
										}
										
										$ctype_proc=$gv2['ctype'];
										$rate_disp=($gv2['rate'] * 10);
										$tcomm=$tcomm+$amt;
										
										$det_ar=array(
											'cmid'=>$cn,
											'secid'=>$gv2['secid'],
											'catid'=>$cn,
											'label'=>$gv2['label'],
											'label_disp'=>$commcat_ar[$cn]['fullname'],
											'ctype'=>$gv2['ctype'],
											'ctype_disp'=>set_ctype_display($ctype_proc),
											'rate'=>$gv2['rate'],
											'rate_disp'=>$rate_disp,
											'amt'=>$amt,
											'amt_disp'=>number_format($amt, 2, '.', ''),
											'diff'=>$diff,
											'diff_disp'=>number_format($diff, 2, '.', ''),
											'd1'=>$gv2['d1'],
											'd2'=>$gv2['d2'],
											'thresh'=>$gv2['thresh'],
											'tcomm'=>$tcomm);
									
										$disp_ar[]=$cn;
										$cblock=true;
										//show_array_vars($det_ar);
									}
								}
							}
						}
					}
				}
				elseif ($cn==9) // Tiered Bonus Comm
				{
					$active_cs=$cv;
					
					if (is_array($active_cs) and ($cinar['fctramt'] - $cinar['fadjbookamt']) > 0)
					{
						$tblock=false;
						foreach ($active_cs as $tn1 => $tv1)
						{
							$tsvalt=0;
							foreach ($tv1 as $tn2 => $tv2)
							{
								if (!$tblock and ($cinar['fctramt'] >= $tv2['thresh']) and (time() >= $tv2['d1'] and time() < $tv2['d2']))
								{
									$tbamtt=0;
									if ($tv2['ctype']==1) //Fixed
									{
										$tbamtt =$tv2['amt'];
										$disp_ar[]=$cn;
									}
									elseif ($tv2['ctype']==2) // Percent
									{
										if ($tv2['trgval']==7) //Contract Amt
										{
											$tbamtt =($cinar['fctramt'] * $tv2['rate']);
											$tblock=true;
											$disp_ar[]=$cn;
										}
										else
										{
											$amt=0;
										}
									}
									
									$rate_disp=($tv2['rate'] * 100);
									$ctype_proc=$tv2['ctype'];
									$amt=$tbamtt;
									$tcomm=$tcomm+$amt;
									
									$det_ar=array(
										'cmid'=>$tv2['cmid'],
										'secid'=>$tv2['secid'],
										'catid'=>$cn,
										'label'=>$tv2['label'],
										'label_disp'=>$commcat_ar[$cn]['fullname'],
										'ctype'=>$tv2['ctype'],
										'ctype_disp'=>set_ctype_display($ctype_proc),
										'rate'=>$tv2['rate'],
										'rate_disp'=>$rate_disp,
										'amt'=>$amt,
										'amt_disp'=>number_format($amt, 2, '.', ''),
										'diff'=>$diff,
										'diff_disp'=>number_format($diff, 2, '.', ''),
										'd1'=>$tv2['d1'],
										'd2'=>$tv2['d2'],
										'thresh'=>$tv2['thresh'],
										'tcomm'=>$tcomm);
								}
							}
						}
					}
				}
				elseif ($cn==8)
				{
					$active_cs=$cv[0][0];
					
					//show_array_pre($active_cs);	
					//echo '<br>';
					
					if ($tcomm < $active_cs['amt']) //Forced Override Entries (Always LAST!)
					{
						$rate_disp=($active_cs['rate'] * 100);
						$ctype_proc=$active_cs['ctype'];
						
						$precom=$tcomm;
						$diff=$active_cs['amt'] + ($tcomm * -1);
						$tcomm=$precom + $diff;
						$amt=$precom;
						
						$det_ar=array(
								'cmid'=>$cn,
								'secid'=>$active_cs['secid'],
								'catid'=>$cn,
								'label'=>$active_cs['label'],
								'label_disp'=>$commcat_ar[$cn]['fullname'],
								'ctype'=>$active_cs['ctype'],
								'ctype_disp'=>set_ctype_display($ctype_proc),
								'rate'=>$active_cs['rate'],
								'rate_disp'=>$rate_disp,
								'amt'=>$amt,
								'amt_disp'=>number_format($amt, 2, '.', ''),
								'diff'=>$diff,
								'diff_disp'=>number_format($diff, 2, '.', ''),
								'd1'=>$active_cs['d1'],
								'd2'=>$active_cs['d2'],
								'thresh'=>$active_cs['thresh'],
								'tcomm'=>$tcomm);
						
						$disp_ar[]=$cn;
					}
					else
					{
						$amt=0;	
					}
				}
				
				//echo 'AMT: '.$amt.'<br>';
				//echo 'TOTE: '.$tcomm.'<br>';
				//echo '-------------------<br>';
				
				if (in_array($cn,$disp_ar)) {
					if ($cn == 8) {
						//echo 'DISP OVR';
						display_override_line($det_ar,$tcomm);
					}
					else {
						//echo 'DISP OTH';
						display_csched_line($det_ar,$tcomm);
					}
				}
			}
		}
	}
	else
	{
		echo 'Commission Schedule Source Error! ('. __LINE__ .')';
	}
	
	if (isset($cinar['commsched']) and count($cinar['commsched']) > 0) {
		foreach ($cinar['commsched'] as $nm=>$vm) {
			$tquan=($vm['rate']!=0)?$vm['rate']:'';
			$ttype=($vm['type']==2)?'%':'f/x';
			$tremv=(isset($_SESSION['elev']) and $_SESSION['elev']>=6)?"<span class=\"JMStooltip noPrint csMan_".$vm['csid']."\" id=\"MCOdel\" title=\"Delete Commission\"><a href=\"#\"><img src=\"images/action_delete.gif\"></a></span>":'';
			echo "			<tr id=\"MCOdisp\">\n";
			echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
			echo "				<td class=\"wh\" align=\"right\"><b>Manual Override</b></td>\n";
			echo "				<td class=\"wh\" align=\"center\">".$tquan."</td>\n";
			echo "				<td class=\"wh\" align=\"center\">".$ttype."</td>\n";
			echo "				<td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
			echo "				<td class=\"wh\" align=\"right\"><span class=\"csamt\">".$vm['amt']."</span></td>\n";
			echo "				<td class=\"wh\" align=\"center\">".$tremv."</td>\n";
			echo "			</tr>\n";
		}
	}
	
	total_csched($tcomm,$cinar);
}

function total_csched($tcomm,$cinar)
{
	$errbiditems=bid_item_cost_test($_SESSION['officeid'],$cinar['estidret']);
	
	echo "           <tr id=\"csched_total_line\">\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>Total</b></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($tcomm < 0)
	{
		echo "					<font color=\"red\"><span id=\"TotalAmtDisplay\">".number_format($tcomm, 2, '.', '')."</span></font>";
	}
	else
	{
		echo "<span id=\"TotalAmtDisplay\">".number_format($tcomm, 2, '.', '')."</span>";
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "				<span id=\"OrigTotalAmt\">".number_format($tcomm, 2, '.', '')."</span>";
	
	if ($_SESSION['clev'] >= 5)
	{
		echo "					<div class=\"noPrint\">\n";
		
		if ($cinar['jobid']=='0')
		{
			if ($_SESSION['clev'] >= 9)
			{
				echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract Override: Your security level allows you to override control protocols\">\n";
			}
			else
			{
				if (isset($errbiditems['no_ret']) and $errbiditems['no_ret'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Retail Price on one or more Bid Items\">\n";
					
					jquery_notify_popup('overridetext','<b>Create Contract Disabled!</b><br><br>Missing Bid Item Retail Price on one or more Bid Items');
					
				}
				elseif (isset($errbiditems['no_cst']) and $errbiditems['no_cst'] > 0)
				{
					echo "                  <img class=\"transnb_button\" src=\"images/table_add.png\" title=\"Create Contract Disabled: Missing Bid Item Cost on one or more Bid Items\">\n";
					
					jquery_notify_popup('overridetext','<b>Create Contract Disabled!</b><br><br>Missing Bid Item Cost on one or more Bid Items');
				}
				elseif (isset($errbiditems['th_cst']) and $errbiditems['th_cst'] > 0)
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Warning: Bid Item Cost too high on one or more Bid Items\">\n";
					
					jquery_notify_popup('overridetext','Bid Item Cost too high on one or more Bid Items');
				}
				else
				{
					echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Create Contract\">\n";
				}
			}
		}
		else
		{
			echo "                  <input class=\"transnb_button\" id=\"CreateContractButton\" type=\"image\" src=\"images/table_add.png\" value=\"Create\" title=\"Contract exists for this Estimate\" DISABLED>\n";
		}
		
		echo "					</div>\n";
	}
	
	echo "				</td>\n";
	echo "           </tr>\n";
}

function get_csched_items($oid,$cinar)
{
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	$cactive=1;
	
	$out=array();
	
	//$qry = "select * from jest..CommissionBuilder where oid=". (int) $oid ." and active=1 and renov=". (int) $renov .";";
	$qry = "select
				C1.cmid,
				C1.oid,
				C1.sid,
				C1.secid,
				C1.ctgry as catid,
				C1.ctype,
				C1.rwdrate as rate,
				C1.rwdamt as amt,
				C1.d1,
				C1.d2,
				C1.name as label,
				C1.dupeproc,
				C1.linkid,
				C1.trgsrc,
				C1.trgsrcval as trgval,
				C1.trgwght as thresh,
				C1.renov
			from
				jest..CommissionBuilder as C1
			inner join
				jest..CommissionBuilderCategory as C2
			ON
				C1.ctgry=C2.catid
			where
				C1.oid=". (int) $oid ."
				and C1.secid=0
				and C1.active=".(int) $cactive ."
				and C1.renov=". (int) $cinar['renov'] ."
			order by C2.proc_order asc,trgwght desc;";
	$res = mssql_query($qry);
	$nro = mssql_num_rows($res);
	
	//echo $qry;
	
	if ($nro > 0)
	{
		while ($row = mssql_fetch_array($res))
		{
			$out[$row['catid']][$row['linkid']][]=array(
				'cmid'=>$row['cmid'],
				'secid'=>$row['secid'],
				'catid'=>$row['catid'],
				'ctype'=>$row['ctype'],
				'rate'=>$row['rate'],
				'amt'=>$row['amt'],
				'd1'=>strtotime($row['d1']),
				'd2'=>strtotime($row['d2']),
				'label'=>$row['label'],
				'trgsrc'=>$row['trgsrc'],
				'trgval'=>$row['trgval'],
				'thresh'=>$row['thresh'],
				'linkid'=>$row['linkid'],
				'renov'=>$row['renov']
			);
		}
	}
	
	$qryB = "select
				C1.cmid,
				C1.oid,
				C1.sid,
				C1.secid,
				C1.ctgry as catid,
				C1.ctype,
				C1.rwdrate as rate,
				C1.rwdamt as amt,
				C1.d1,
				C1.d2,
				C1.name as label,
				C1.dupeproc,
				C1.linkid,
				C1.trgsrc,
				C1.trgsrcval as trgval,
				C1.trgwght as thresh,
				C1.renov
			from
				jest..CommissionBuilder as C1
			inner join
				jest..CommissionBuilderCategory as C2
			ON
				C1.ctgry=C2.catid
			where
				C1.oid=". (int) $oid ."
				and C1.secid=". (int) $cinar['estsecid'] ."
				and C1.active=".(int) $cactive ."
				and C1.renov=". (int) $cinar['renov'] ."
			order by C2.proc_order asc,trgwght desc;";
	$resB = mssql_query($qryB);
	$nroB = mssql_num_rows($resB);
	
	if ($nroB > 0)
	{
		while ($rowB = mssql_fetch_array($resB))
		{
			unset($out[$rowB['catid']]);
			
			$out[$rowB['catid']][$rowB['linkid']][0]=array(
				'cmid'=>$rowB['cmid'],
				'secid'=>$rowB['secid'],
				'catid'=>$rowB['catid'],
				'ctype'=>$rowB['ctype'],
				'rate'=>$rowB['rate'],
				'amt'=>$rowB['amt'],
				'd1'=>strtotime($rowB['d1']),
				'd2'=>strtotime($rowB['d2']),
				'label'=>$rowB['label'],
				'trgsrc'=>$rowB['trgsrc'],
				'trgval'=>$rowB['trgval'],
				'thresh'=>$rowB['thresh'],
				'linkid'=>$rowB['linkid'],
				'renov'=>$rowB['renov']
			);
		}
		
		ksort($out);
	}
	
	//echo '<pre>';
	//print_r($out);
	//echo '</pre>';
	
	return $out;
}

function set_ctype_display($ctype)
{
	$out='';
	
	if ($ctype==1)
	{
		$out='fx';
	}
	elseif ($ctype==2)
	{
		$out='%';
	}
	
	return $out;
}

function display_csched_line($csar,$tcomm)
{
	//show_array_pre($csar);
	
	echo "           <tr>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>". $csar['label_disp'] ."</b></td>\n";	
	echo "              <td class=\"wh\" align=\"center\">\n";
	
	if ($csar['catid']==1) {
		if ($csar['secid']!=0) {
			echo "              <span class=\"JMStooltip\" id=\"BCratedisplay\" title=\"Sales Rep Commission\">".$csar['rate_disp']."</span>";
		}
		else {
			echo "              <span class=\"JMStooltip\" id=\"BCratedisplay\" title=\"Default Commission\">".$csar['rate_disp']."</span>";
		}
	}
	elseif ($csar['catid']==6) {
		if ($csar['secid']!=0) {
			echo "              <span class=\"JMStooltip\" id=\"BCratedisplay\" title=\"Sales Rep Bullet Threshold\">".$csar['thresh']."</span>";
		}
		else {
			echo "              <span class=\"JMStooltip\" id=\"BCratedisplay\" title=\"Default Bullet Threshold\">".$csar['thresh']."</span>";
		}
	}
	else {
		if ($csar['secid']!=0) {
			echo "              <span class=\"JMStooltip\" id=\"BCratedisplay\" title=\"Sales Rep Commission\">".$csar['rate_disp']."</span>";
		}
		else {
			echo "              <span class=\"JMStooltip\" id=\"BCratedisplay\" title=\"Default  Commission\">".$csar['rate_disp']."</span>";
		}
	}

	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">".$csar['ctype_disp']."</td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\">\n";
	
	if ($csar['catid']==1) {
		if ($csar['amt'] < 0) {
			echo "              <font color=\"red\"><span class=\"csamt\" id=\"BCamtdisplay\">".number_format($csar['amt'], 2, '.', '')."</span></font>\n";
		}
		else {
			echo "				<span class=\"csamt\" id=\"BCamtdisplay\">".number_format($csar['amt'], 2, '.', '')."</span>\n";
		}
	}
	else {
		if ($csar['amt'] < 0) {
			echo "              <font color=\"red\"><span class=\"csamt\">".number_format($csar['amt'], 2, '.', '')."</span></font>\n";
		}
		else {
			echo "              <span class=\"csamt\">".number_format($csar['amt'], 2, '.', '')."</span>\n";
		}
	}
	
	echo "				</td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	
	if ($csar['catid']==1) {
		echo "				<input type=\"hidden\" name=\"csched[".$csar['cmid']."][contrsrc]\" value=\"book\">\n";
		echo "				<span class=\"JMStooltip noPrint\" id=\"OpenBaseCommAdjustDialog\" title=\"Adjust Base Commission\"><a href=\"#\"><img src=\"../images/calculator_edit.png\"></a></span>\n";
		echo "				<span id=\"OrigBCAmt\">".number_format($csar['amt'], 2, '.', '')."</span>";
	}
	
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][cmid]\" value=\"".$csar['cmid']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][rwdamt]\" value=\"".number_format($csar['amt'], 2, '.', '')."\" id=\"BCrwdamt\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][secid]\" value=\"".$csar['secid']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][catid]\" value=\"".$csar['catid']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][ctype]\" value=\"".$csar['ctype']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][trgwght]\" value=\"".$csar['thresh']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][d1]\" value=\"".$csar['d1']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][d2]\" value=\"".$csar['d2']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][label]\" value=\"".$csar['label']."\">\n";
	
	if ($csar['catid']==1)
	{
		echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][rwdrate]\" value=\"".$csar['rate']."\" id=\"BCrwdrate\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][rwdrateorig]\" value=\"".$csar['rate']."\" id=\"BCrwdrateorig\">\n";
	}
	else
	{
		echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][rwdrate]\" value=\"".$csar['rate']."\">\n";
		echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][rwdrateorig]\" value=\"".$csar['rate']."\">\n";
	}
	
	echo "				</td>\n";
	echo "           </tr>\n";
}

function display_override_line($csar,$tcomm)
{
	//show_array_pre($csar);	
	//echo '<br>';
	//echo 'TOTD: '.$tcomm.'<br>';
	
	echo "           <tr class=\"mincomm\">\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><b>sub-Total</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><span id=\"bcommSubTotal\">";

	if ($csar['amt_disp'] < 0)
	{
		echo "					<font color=\"red\">". $csar['amt_disp'] ."</font>";
	}
	else
	{
		echo $csar['amt_disp'];
	}
	
	echo "				</span></td>\n";
	echo "              <td class=\"wh\" align=\"center\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr class=\"mincomm\">\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\" height=\"12px\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "           </tr>\n";
	echo "           <tr class=\"mincomm\">\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\" title=\"Adjustment required for Minimum Commission\"><b>".$csar['label_disp']."</b></td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"center\">". $csar['ctype_disp'] ."</td>\n";
	echo "              <td class=\"wh\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
	echo "              <td class=\"wh\" align=\"right\"><span id=\"mincommAdj\">". $csar['diff_disp'] ."</span></td>\n";
	echo "              <td class=\"wh\" align=\"center\">\n";
	echo "					<div class=\"noPrint\"><img id=\"minimumoverride\" src=\"images/information.png\" width=\"11px\" height=\"11px\" title=\"Commission Minimum Override enabled\"></div>\n";
	
	/*
	jquery_notify_popup(
						'overridetext',
						'<b>Commission Automatic Override enabled!</b><br><br>
						Commission sub-Total is below the Minimum Commission of $'.number_format($csar['amt'], 2, '.', '').'.<br><br>
						Commission Total has been adjusted by $'.number_format($tamt, 2, '.', '').' to meet the minimum.'
						);
	*/
	
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][cmid]\" id=\"OVcmid\" value=\"".$csar['cmid']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][rwdamt]\" id=\"OVrwdamt\" value=\"".number_format($csar['diff_disp'], 2, '.', '')."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][secid]\" id=\"OVsecid\" value=\"".$csar['secid']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][catid]\" id=\"OVcatid\" value=\"".$csar['catid']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][ctype]\" id=\"OVctype\" value=\"".$csar['ctype']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][rwdrate]\" id=\"OVrwdrate\" value=\"".$csar['rate']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][trgwght]\" id=\"OVthresh\" value=\"".$csar['thresh']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][d1]\" id=\"OVd1\" value=\"".$csar['d1']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][d2]\" id=\"OVd2\" value=\"".$csar['d2']."\">\n";
	echo "					<input type=\"hidden\" name=\"csched[".$csar['cmid']."][label]\" id=\"OVlabel\" value=\"".$csar['label']."\">\n";
	echo "				</td>\n";
	echo "           </tr>\n";
}

function bid_item_cost_test($oid,$est)
{
	$mbid_ar=array();
	
	if ($est!=0 and isset($_SESSION['estbidretail']) and is_array($_SESSION['estbidretail']))
	{
		//if ($_SESSION['securityid']==26)
		//{
		$qryA = "SELECT officeid,vgp FROM offices WHERE officeid=".$oid.";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		if (isset($rowA['vgp']) and $rowA['vgp'] > 0)
		{
			$vgp=($rowA['vgp'] * .01);
		}
		else
		{
			$vgp=.3;
		}
		
		$no_ret=0; // Zero Retail
		$no_cst=0; // Zero Cost
		$th_cst=0; // Cost Threshold
		foreach ($_SESSION['estbidretail'] as $n => $v)
		{
			if ($v[0]!=0)
			{
				if ($v[1]==0)
				{
					$no_cst++;
					//$mbid_ar[]=array(0,1,0);
					//echo ($v[0] - ($v[0] * $vgp)).':'.$v[1].'<br>';
				}
				elseif ($v[1] > ($v[0] - ($v[0] * $vgp)))
				{
					$th_cst++;
					//echo ($v[0] - ($v[0] * $vgp)).':'.$v[1].'<br>';
					//$mbid_ar[]=array(0,0,1);
				}
			}
			else
			{
				$no_ret++;
				//$mbid_ar[]=array(1,0,0);
			}
		}
		
		if ($no_ret > 0 or $no_cst > 0 or $th_cst > 0)
		{
			$mbid_ar=array('no_ret'=>$no_ret,'no_cst'=>$no_cst,'th_cst'=>$th_cst);
		}
		//display_array($_SESSION['estbidretail']);
		//echo '<br>';
		//display_array($mbid_ar);
		//}
	}
	
	//display_array($mbid_ar);
	//echo '<br>-----<br>';
	return $mbid_ar;
}

function retail_csched_ro($cinar,$col_struct)
{
	// Commission Schedule: Read Only
	// This function pulls and displays comm schedule data
	error_reporting(E_ALL);
    ini_set('display_errors','On');
	
	$qry0  = "select catid,label,descrip,fullname from jest..CommissionBuilderCategory;";
	$res0 = mssql_query($qry0);
    $nrow0= mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			$commcat_ar[$row0['catid']]=array('label'=>$row0['label'],'descrip'=>$row0['descrip'],'fullname'=>$row0['fullname']);
		}
	}
	
	$qry0a  = "select officeid,gm,sm,am from jest..offices where officeid = ".$_SESSION['officeid'].";";
	$res0a = mssql_query($qry0a);
	$row0a = mssql_fetch_array($res0a);
    $nrow0a= mssql_num_rows($res0a);
	
	if ($nrow0a > 0)
	{
		$office_ar=array('oid'=>$row0a['officeid'],'gm'=>$row0a['gm'],'sm'=>$row0a['sm'],'am'=>$row0a['am']);
	}
	
	frm_hdr_csched($cinar);
	
	col_hdr_csched($col_struct);
	
	proc_csched($cinar,$commcat_ar);
	
	frm_ftr();

	//return number_format($tcomm, 2, '.', '');
}

function CreateContractwTAX()
{
	error_reporting(E_ALL);
	//echo __FUNCTION__.'<br>';
	
	$qrypre1	= "SELECT * FROM est WHERE officeid='".$_SESSION['officeid']."' AND estid='".$_REQUEST['estid']."';";
	$respre1	= mssql_query($qrypre1);
	$rowpre1	= mssql_fetch_array($respre1);
	
	if ($rowpre1['contractamt'] < 1)
	{
		echo 'Contract Amount must be greater than 0.00<br>';
		exit;
	}

	$qrypre2	= "SELECT psched,psched_perc,code,stax,finan_from,com_rate,over_split FROM offices WHERE officeid='".$_SESSION['officeid']."';";
	$respre2	= mssql_query($qrypre2);
	$rowpre2	= mssql_fetch_array($respre2);
	
	$qrypre3	= "SELECT cid,clname,cfname,jobid,njobid FROM cinfo WHERE officeid='".$_SESSION['officeid']."' AND cid='".$rowpre1['ccid']."' ;";
	$respre3	= mssql_query($qrypre3);
	$rowpre3	= mssql_fetch_array($respre3);
	
	$qrypre4	= "SELECT securityid,sidm,com_rate,over_split FROM security WHERE officeid='".$_SESSION['officeid']."' AND securityid='".$rowpre1['securityid']."';";
	$respre4	= mssql_query($qrypre4);
	$rowpre4	= mssql_fetch_array($respre4);
	
	if ($rowpre3['jobid']!='0')
	{
		echo "<b>Contract ".$rowpre3['jobid']." already exists for this Estimate.</b>";
		exit;
	}
	
	if ($rowpre4['com_rate']==0)
	{
		$base_rate=$rowpre2['com_rate'];
	}
	else
	{
		$base_rate=$rowpre4['com_rate'];
	}
	
	if ($rowpre4['over_split']==0)
	{
		$over_split=$rowpre2['over_split'];
	}
	else
	{
		$over_split=$rowpre4['over_split'];
	}
	
	if (isset($_REQUEST['oubook']) && $_REQUEST['oubook'] != 0)
	{
		$oubook=$_REQUEST['oubook'];
	}
	else
	{
		$oubook=0;
	}
	
	if (isset($_REQUEST['adjbook']) && $_REQUEST['adjbook'] != 0)
	{
		$adjbook=$_REQUEST['adjbook'];
	}
	else
	{
		$adjbook=0;
	}
	
	$comm_ar=array(
						'fctramt'=>$rowpre1['contractamt'],
						'estid'=>$rowpre1['estid'],
						'base_rate'=>$base_rate,
						'over_split'=>$over_split,
						'oubook'=>$oubook,
						'adjbook'=>$adjbook,
						'sidm'=>$rowpre1['sidm']
					);

	if ($rowpre2['stax']==1)
	{
		if ($rowpre1['tax']=="0.00")
		{
			$contractamt	=$rowpre1['contractamt'];
			$salestx		=0;
			$camt			=$contractamt+$salestx;

		}
		else
		{
			$contractamt	=$rowpre1['contractamt'];
			$salestx		=$rowpre1['tax'];
			$camt			=$contractamt+$salestx;
		}
	}
	else
	{
		$camt			=$rowpre1['contractamt'];
	}

	$fcamt	=number_format($camt, 2, '.', '');
	$fouamt	=number_format($comm_ar['oubook'], 2, '.', '');

	$tdate	=date("m/d/Y", time());
	$sdate	=date("m/d/Y", time());
	$cdate	=date("mdy", time());

	$contractcode=$rowpre1['estid'].".".$rowpre2['code'].".".$cdate;

	echo "<script type=\"text/javascript\" src=\"js/jquery_contract_create_revwTAX.js\"></script>\n";
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td>";
	
	if ($rowpre1['renov']==1)
	{
		echo "<b>Create New Renovation</b>";
	}
	else
	{
		echo "<b>Create New Contract</b>";
	}
	
	echo "		</td>\n";
	echo "		<td align=\"right\">\n";
	echo "			<form name=\"viewest\" method=\"POST\">\n";
	echo "				<input type=\"hidden\" name=\"action\" value=\"est\">\n";
	echo "				<input type=\"hidden\" name=\"call\" value=\"view_retail\">\n";
	echo "				<input type=\"hidden\" name=\"estid\" value=\"".$rowpre1['estid']."\">\n";
	echo "				<input type=\"hidden\" name=\"esttype\" value=\"E\">\n";
	echo "				<button title=\"Return to Estimate\">Return to Estimate</button>\n";
	echo "			</form>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "<p>\n";
	
	echo "<form id=\"submitContract\" name=\"createcontract\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"contract\">\n";
	echo "<input type=\"hidden\" name=\"call\" value=\"post_create_job\">\n";
	echo "<input type=\"hidden\" name=\"officeid\" value=\"".$_SESSION['officeid']."\">\n";
	echo "<input type=\"hidden\" name=\"securityid\" value=\"".$rowpre1['securityid']."\">\n";
	echo "<input type=\"hidden\" name=\"sidm\" value=\"".$rowpre1['sidm']."\">\n";
	echo "<input type=\"hidden\" name=\"estid\" value=\"".$rowpre1['estid']."\">\n";
	echo "<input type=\"hidden\" name=\"custid\" value=\"".$rowpre1['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"tcid\" value=\"".$rowpre3['cid']."\">\n";
	echo "<input type=\"hidden\" name=\"jobid\" value=\"".$contractcode."\">\n";
	echo "<input type=\"hidden\" name=\"jadd\" value=\"0\">\n";
	echo "<input type=\"hidden\" name=\"camt\" id=\"camt\" value=\"".$fcamt."\">\n";
	echo "<input type=\"hidden\" name=\"renov\" value=\"".$rowpre1['renov']."\">\n";
	echo "<input type=\"hidden\" name=\"overunder\" value=\"".$comm_ar['oubook']."\">\n";
	echo "<input type=\"hidden\" name=\"adjbook\" value=\"".$comm_ar['adjbook']."\">\n";

	if ($rowpre2['stax']==1)
	{
		echo "<input type=\"hidden\" name=\"salestx\" value=\"".$salestx."\">\n";
	}
	
	echo "<table class=\"outer\" width=\"950px\">\n";
	echo "	<tr>\n";
	echo "		<td align=\"left\"><b>Contract Detail</b></td>\n";
	echo "		<td align=\"left\"><b>Commission Schedule</b></td>\n";
	echo "		<td align=\"left\"><b>Payment Schedule</b></td>\n";
	echo "	</tr>\n";
	
	echo "	<tr>\n";
	echo "		<td align=\"center\" valign=\"top\">\n";
	
	ContractDetail($rowpre1['estid']);
	
	echo "		</td>\n";
	echo "		<td align=\"center\" valign=\"top\">\n";

	CommissionScheduleRW_NEW($comm_ar);
	CommissionScheduleRO_GMSM($comm_ar);
	
	echo "		</td>\n";
	echo "		<td align=\"center\" valign=\"top\">\n";
	
	PaymentScheduleRWwTAX($rowpre3['cid'],$rowpre1['contractamt'],$rowpre2[0],$rowpre2[1]);
	
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td align=\"right\" colspan=\"3\"><button id=\"AcceptSubmit\" title=\"Save Contract\" onClick=\"return CreateContractAlerts()\">Create Contract</button></td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	/*
	echo "	<td>\n";

	// Errors
	$keys=array_search(0,$rowpre1);
	$errinp=0;

	if ($rowpre1['pft']==0)
	{
		$errinp++;
		echo 'Error: Perimeter<br>';
		
	}

	if ($rowpre1['sqft']==0)
	{
		$errinp++;
		echo 'Error: Surface Area<br>';
	}

	if ($rowpre1['erun']==0 && $rowpre1['renov']!=1)
	{
		echo 'Error: Electrical Run<br>';
		$errinp++;
	}

	if ($rowpre1['prun']==0 && $rowpre1['renov']!=1)
	{
		echo 'Error: Plumbing Run<br>';
		$errinp++;
	}

	if ($rowpre1['contractamt']==0)
	{
		echo 'Error: Contract Amount<br>';
		$errinp++;
	}

	echo "</form>\n";
	echo "			<table>\n";
	echo "				<tr>\n";
	echo "					<td align=\"right\"><b>Save Contract</b></td>\n";
	echo "					<td align=\"right\" width=\"20\">\n";

	if ($errinp > 0)
	{
		echo "                  <input class=\"transnb\" type=\"image\" id=\"AcceptSubmit\" src=\"images/save.gif\" value=\"Apply\" title=\"Save Contract\" DISABLED>\n";
	}
	else
	{
		echo "                  <input class=\"transnb\" type=\"image\" id=\"AcceptSubmit\" src=\"images/save.gif\" value=\"Apply\" title=\"Save Contract\" onClick=\"return CreateContractAlerts()\">\n";
	}

	echo "					</td>\n";
	echo " 		  		</tr>\n";
	echo "			</table>\n";
	
	echo "					</td>\n";
	*/
}