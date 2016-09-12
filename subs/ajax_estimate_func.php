<?php

function getPriceBook() {
    $out ='';
	$oid =(isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)?$_REQUEST['oid']:$_SESSION['officeid'];
    $cat =array();
    $sat =array();
    
    $qry = "SELECT officeid,name,def_per,def_sqft,def_s,def_m,def_d,pft_sqft,finan_from,pb_code FROM offices WHERE officeid=".(int) $oid.";";
	$res = mssql_query($qry);
	$row = mssql_fetch_array($res);
    
    $oname=$row['name'];
    $MAS =($row['pb_code']!='0')?$row['pb_code']:'';

	$qry1 = "SELECT a.catid,a.name,a.seqn FROM AC_cats AS a WHERE a.officeid=".(int) $oid." AND a.active=1 AND a.privcat!=1 AND a.salestype=0 ORDER BY a.seqn ASC;";
	$res1 = mssql_query($qry1);

	while ($row1 = mssql_fetch_array($res1)) {
        $cat[$row1['catid']]=array('name'=>$row1['name'],'seqn'=>$row1['seqn'],'subcat'=>array());
	}
    
    foreach ($cat as $n=>$v) {
        $qry2a  = "SELECT id,qtype,item,atrib1,atrib2,bp,rp,crate,quan_calc,mtype,matid,catid,seqn FROM [".$MAS."acc] WHERE officeid=".(int) $oid." AND catid=".(int) $n." AND qtype=32 AND disabled!='1' ORDER BY seqn;";
        $res2a  = mssql_query($qry2a);
        
        while ($row2a=mssql_fetch_array($res2a)) {
            $cat[$n]['subcat'][$row2a['id']]=array(
                'id'=>$row2a['id'],
                'qtype'=>$row2a['qtype'],
                'item'=>$row2a['item'],
                'atrib1'=>$row2a['atrib1'],
                'atrib2'=>$row2a['atrib2'],
                'bprice'=>$row2a['bp'],
                'rprice'=>$row2a['rp'],
                'crate'=>$row2a['crate'],
                'quan_calc'=>$row2a['quan_calc'],
                'mtype'=>$row2a['mtype'],
                'matid'=>$row2a['matid'],
                'catid'=>$row2a['catid'],
                'seqn'=>$row2a['seqn'],
                'items'=>array()
            );
            
            $qry3  = "SELECT id,qtype,item,atrib1,atrib2,bp,rp,crate,quan_calc,mtype,matid,catid,seqn,bullet FROM [".$MAS."acc] WHERE officeid=".(int) $oid." AND catid=".(int) $n." AND pid=".(int) $row2a['id']." AND disabled!='1' ORDER BY seqn;";
            $res3  = mssql_query($qry3);
            
            while ($row3=mssql_fetch_array($res3)) {
                $cat[$n]['subcat'][$row2a['id']]['items'][$row3['id']]=array(
                    'id'=>$row3['id'],
                    'qtype'=>$row3['qtype'],
                    'item'=>$row3['item'],
                    'atrib1'=>$row3['atrib1'],
                    'atrib2'=>$row3['atrib2'],
                    'bprice'=>$row3['bp'],
                    'rprice'=>$row3['rp'],
                    'crate'=>$row3['crate'],
                    'quan_calc'=>$row3['quan_calc'],
                    'mtype'=>$row3['mtype'],
                    'matid'=>$row3['matid'],
                    'catid'=>$row3['catid'],
                    'seqn'=>$row3['seqn'],
                    'bullet'=>$row3['bullet'],
                    'items'=>array()
                );
            }
        }
    }
    
    if (is_array($cat) and count($cat) > 0) {
        $out.='<div class="textcenter pbtitle">'.$oname.' Pricebook</div>';
        $out.='<ul class="pbcontainer">';
        
        foreach ($cat as $no=>$vo) {            
            $out.='<li>';
            $out.='<div class="pbcategory">'.$vo['name'].' <a href="#top" class="topNavLink">Top</a></div>';
            //$out.='<div class="pbcategory">'.$vo['name'].'</div>';
            
            if (is_array($vo['subcat']) and count($vo['subcat']) > 0) {
                $out.='<ul>';
                
                foreach ($vo['subcat'] as $no1=>$vo1) {
                    $out.='<li>';
                    $out.='<div class="pbsubcategory">'.$vo1['item'].'</div>';
                    
                    if (isset($vo1['atrib1']) and strlen($vo1['atrib1']) > 1) {
                        $out.='<div>'.$vo1['atrib1'].'</div>';
                    }
                    
                    if (is_array($vo1['items']) and count($vo1['items']) > 0) {
                        $out.="<ul>";
                        
                        foreach ($vo1['items'] as $no2=>$vo2) {
                            $out.='<li>';
                            $out.=pbItemDisplay($vo2);
                            $out.='</li>';
                        }
                        
                        $out.="</ul>";
                    }

                    $out.='</li>';
                }
            
                $out.="</ul>";
            }
            
            $out.='</li>';
        }
    
        $out.="</ul>";
    }
    
    return $out;
}

function pbItemDisplay($i) {
    $out ='';
    $out.='<table class="pbitem" width="100%">';
    $out.='<tr class="irow"><td class="iitem"><span class="iname">'.$i['item'].'</span>';
    $out.=($i['qtype']==72)?' <img class="pkg_tline" src="images/package.png" title="Package Item">':'';
    $out.=($i['qtype']==33)?' <img class="exp_tline setpointer" src="images/bullet_toggle_plus.png" title="Expand to Add Description">':'';
    $out.=($i['bullet']!=0)?' <img class="blt_tline setpointer" src="images/bullet_green.png" title="'.$i['bullet'].' Bullet(s)">':'';
    $out.='</td>';
    $out.='<td class="iinfo" width="125px">';
    $out.=(isset($i['rprice']) and $i['rprice']!=0)?'<input type="text" class="iquan" value="0" size="1">':'';
    $out.=($i['qtype']!=33)?(isset($i['rprice']) and $i['rprice']!=0)?'<span class="iprice">'.number_format($i['rprice'], 2, '.', '').'</span>':'':'<input type="text" class="iprice" style="margin-right:63px; text-align:right;" value="0.00" size="4">';
    $out.='<span class="iqtype" style="display:none;">'.$i['qtype'].'</span>';
    $out.='<span class="iid" style="display:none;">'.$i['id'].'</span>';
    $out.='</td>';
    $out.='<td width="20px">';
    $out.='<span class="CartItemAdd status"><img src="images/cart_put.png" title="Add to Breakdown"></span>';
    $out.='</td></tr>';
    $out.=(isset($i['atrib1']) and strlen($i['atrib1']) > 1)?'<tr><td class="iatrib1">'.$i['atrib1'].'</td></tr>':'';
    $out.=($i['qtype']==33)?'<tr class="bidtextline" style="display:none;"><td class="bidtext"><textarea class="ibidtext" rows="1" cols="55" maxlength="185" placeholder="185 Character Maximum"></textarea></td></tr>':'';
    $out.='</table>';
    
    return $out;
}

function CartItemRemove() {
    $out=array();
    $out['err']=1;
    
    $oid   =(isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)?$_REQUEST['oid']:null;
    $eid   =(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:null;
    $cid   =(isset($_REQUEST['crtid']) and $_REQUEST['crtid']!=0)?$_REQUEST['crtid']:null;
    
    if (!is_null($oid) and !is_null($eid) and !is_null($cid)) {
        $qry0 = "SELECT edid,estid,oid FROM EstimateDetail WHERE oid=".(int) $oid." AND estid=".(int) $eid." AND edid=".(int) $cid.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        $nrow0= mssql_num_rows($res0);
        
        if ($nrow0!=0) {
            $qry1 = "DELETE FROM EstimateDetail WHERE oid=".(int) $row0['oid']." AND estid=".(int) $row0['estid']." AND edid=".(int) $row0['edid'].";";
            $res1 = mssql_query($qry1);
            $out['err']=0;
        }
    }
    
    return $out;
}

function CartItemAdd() {
    $out=array();
    
    $oid   =(isset($_SESSION['officeid']) and $_SESSION['officeid']!=0)?$_SESSION['officeid']:null;
    $iid   =(isset($_REQUEST['iid']) and $_REQUEST['iid']!=0)?$_REQUEST['iid']:null;
    
    if (!is_null($oid) and !is_null($iid)) {
        $ivals=array('oid'=>$oid,'estid'=>$_REQUEST['eid'],'ps1'=>$_REQUEST['ps1'],'ps2'=>$_REQUEST['ps2'],'ps5'=>$_REQUEST['ps5'],'ps6'=>$_REQUEST['ps6'],'ps7'=>$_REQUEST['ps7'],'ia'=>$_REQUEST['fia'],'gals'=>$_REQUEST['fgl']);
        
        $item=array();
        $qry0 = "SELECT officeid as oid,pb_code FROM offices WHERE officeid=".(int) $oid.";";
        $res0 = mssql_query($qry0);
        $row0 = mssql_fetch_array($res0);
        $pbc=($row0['pb_code']=='0')?'':$row0['pb_code'];
        
        $qry1 = "SELECT
                    A.id,A.qtype,A.item,A.atrib1 as a1,A.atrib2 as a2,A.atrib3 as a3,
                    A.bp,A.rp,A.crate,A.quan_calc,A.mtype,A.lrange,A.hrange,A.bullet,
                    A.matid,A.catid,A.commtype,A.seqn,A.poolcalc,A.pid,A.def_quan,A.royrelease,
                    (select name from AC_cats where officeid=A.officeid and catid=A.catid) as catname,
                    (select abrv from mtypes where mid=A.mtype) as mname
                FROM [".$pbc."acc] AS A
                WHERE A.officeid=".(int) $oid." AND A.id=".(int) $iid.";";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_array($res1);
        $nrow1= mssql_num_rows($res1);
        
        if ($nrow1 > 0) {
            if (isset($row1['poolcalc']) and $row1['poolcalc']==1) {
                $pc=1;
                $iqn=$ivals['ps1'];
            }
            else {
                $pc=0;
                $iqn=(isset($_REQUEST['bqn']) and $_REQUEST['bqn']!=0)?$_REQUEST['bqn']:null;
            }
            
            $irp=(isset($row1['qtype']) and $row1['qtype']==33)?$_REQUEST['bpr']:$row1['rp'];
            
            $odata  =ajaxCalcLoop($ivals,$row1['qtype'],$row1['bp'],$irp,$row1['lrange'],$row1['hrange'],$iqn,$row1['quan_calc'],$row1['a1'],$row1['a2'],$row1['a3'],$pc);
            //print_r($odata);
            if (isset($odata['calccm']) and $odata['calccm']!=0) {
                $calccm=$odata['calccm'];
                //echo 'HIT';
            }
            else {
                if ($row1['commtype']==3) {
                    $calccm=$row1['crate'];
                }
                elseif ($row1['commtype']==2) {
                    $calccm=($row1['crate'] * $iqn);
                }
                elseif ($row1['commtype']==1){
                    $calccm=($row1['crate'] * $odata['calcrp']);
                }
                else {
                    $calccm=0;
                }
            }
            
            if (isset($_REQUEST['bdt']) and strlen($_REQUEST['bdt']) > 1) {
                //$bdt    =htmlspecialchars($_REQUEST['bdt']);
                $bdt    =trim($_REQUEST['bdt']);
                $a1     =substr($bdt,0,62);
                $a2     =substr($bdt,63,125);
                $a3     =substr($bdt,126,185);
                $aout   =$a1.$a2.$a3;
            }
            else {
                $a1     =$row1['a1'];
                $a2     =$row1['a2'];
                $a3     =$row1['a3'];
                $aout   =$a1.$a2.$a3;
            }
            
            $item=array(
                'iid'=>$row1['id'],
                'qtype'=>$row1['qtype'],
                'item'=>$row1['item'],
                'atrib'=>$aout,
                'atrib1'=>$a1,
                'atrib2'=>$a2,
                'atrib3'=>$a3,
                'lrange'=>$row1['lrange'],
                'hrange'=>$row1['hrange'],
                'bullet'=>$row1['bullet'],
                'royrelease'=>$row1['royrelease'],
                'bp'=>number_format($row1['bp'], 2, '.', ''),
                'calcbp'=>number_format($odata['calcbp'], 2, '.', ''),
                'rp'=>number_format($row1['rp'], 2, '.', ''),
                'calcrp'=>number_format($odata['calcrp'], 2, '.', ''),
                'calcqn'=>$odata['calcqn'],
                'crate'=>$row1['crate'],
                'calccm'=>number_format($calccm, 2, '.', ''),
                'def_quan'=>$row1['def_quan'],
                'quan_calc'=>$row1['quan_calc'],
                'mtype'=>$row1['mtype'],
                'mname'=>$row1['mname'],
                'matid'=>$row1['matid'],
                'catid'=>$row1['catid'],
                'catname'=>$row1['catname'],
                'commtype'=>$row1['commtype'],
                'seqn'=>$row1['seqn'],
                'poolcalc'=>$pc,
                'pid'=>$row1['pid']
            );
            
            $out=$item;
            
            //Store Detail
            if (isset($ivals['estid']) and $ivals['estid']!=0) {
                $out['edid']=StoreEstimateDetail($ivals,$item);
            }

            $out['err']=0;
        }
    }
    return $out;
}

function StoreEstimateDetail($ivals,$item) {
    $out  = 0;
    $uid  = $_SESSION['securityid'];
    $qry0 = "SELECT * FROM EstimateDetail WHERE oid=".(int) $ivals['oid']." AND estid=".(int) $ivals['estid']." AND srcid=".(int) $item['iid'].";";
    $res0 = mssql_query($qry0);
    $row0 = mssql_fetch_array($res0);
    $nrow0= mssql_num_rows($res0);
    
    if ($nrow0 > 1) {
        
    }
    elseif ($nrow0 == 1) {
        //Update
        $qry1 = "UPDATE EstimateDetail SET calcrp=CONVERT(money,'".$item['calcrp']."'),calcqn=".$item['calcqn'].",udate=getdate() WHERE oid=".(int) $ivals['oid']." AND estid=".(int) $ivals['estid']." AND edid=".(int) $row0['edid'].";";
        $res1 = mssql_query($qry1);
        //echo $qry1;
        $out=$row0['edid'];
    }
    else {
        //Insert
        $qry1 = "INSERT INTO [jest]..[EstimateDetail]
                    ([oid],[estid],[verid],[srcid],[catid],[matid],[item],[atrib1],[atrib2],[atrib3],
                    [bp],[calcbp],[rp],[calcrp],[calcqn],[calccm],[commtype],[crate],[qtype],[quan_calc],[mtype],[lrange],[hrange],[seqn],
                    [bullet],[def_quan],[royrelease],[poolcalc],[udate],[uid],[pid])
                VALUES
                    (".$ivals['oid'].",".$ivals['estid'].",0,".$item['iid'].",".$item['catid'].",".$item['matid'].",'".$item['item']."','".$item['atrib1']."','".$item['atrib2']."','".$item['atrib3']."',
                    CONVERT(money,'".$item['bp']."'),CONVERT(money,'".$item['calcbp']."'),CONVERT(money,'".$item['rp']."'),CONVERT(money,'".$item['calcrp']."'),".$item['calcqn'].",
                    CONVERT(money,'".$item['calccm']."'),".$item['commtype'].",'".$item['crate']."',".$item['qtype'].",'".$item['quan_calc']."',".$item['mtype'].",
                    ".$item['lrange'].",".$item['hrange'].",".$item['seqn'].",".$item['bullet'].",".$item['def_quan'].",".$item['royrelease'].",".$item['poolcalc'].",getdate(),".$uid.",".$item['pid'].");
                    SELECT @@IDENTITY;";
        $res1 = mssql_query($qry1);
        $row1 = mssql_fetch_row($res1);
        
        $out=$row1[0];
    }
    
    return $out;
}

function updatePoolDimensions() {
    $out=array();
    $out['err']=1;
    
    $oid=(isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)?$_REQUEST['oid']:null;
    $eid=(isset($_REQUEST['eid']) and $_REQUEST['eid']!=0)?$_REQUEST['eid']:null;
    
    if (!is_null($oid) and !is_null($eid)) {
        $ifld=(isset($_REQUEST['frmfld']) and strlen($_REQUEST['frmfld'])!=0)?$_REQUEST['frmfld']:null;
        $ival=(isset($_REQUEST['fldval']) and strlen($_REQUEST['fldval'])!=0)?$_REQUEST['fldval']:null;
        
        if (!is_null($ifld) and !is_null($ival)) {
            if ($ifld=='ps1') {
                $fld='[pft]';
            }
            elseif ($ifld=='ps2') {
                $fld='[sqft]';
            }
            elseif ($ifld=='ps5') {
                $fld='[shal]';
            }
            elseif ($ifld=='ps6') {
                $fld='[mid]';
            }
            elseif ($ifld=='ps7') {
                $fld='[deep]';
            }
            elseif ($ifld=='refto') {
                $fld='[refto]';
            }
        
            $qry1 = "UPDATE [est] SET ".$fld."='".$ival."' WHERE officeid=".(int) $oid." AND estid=".(int) $eid.";";
            $res1 = mssql_query($qry1);
            //$out=$row0['edid'];
        
            $pd=calcDimensionsInternal($oid,$eid);
            $out['ia']=$pd['ia'];
            $out['gl']=$pd['gl'];
        
            $out['err']=0;
        }
    }
    
    return $out;
}

function updateContractAmt() {
    $out=array();
    $out['err']=1;
    
    $oid=(isset($_REQUEST['oid']) and $_REQUEST['oid']!=0)?$_REQUEST['oid']:null;
    $eid=(isset($_REQUEST['eid']) and $_REQUEST['eid']!=0)?$_REQUEST['eid']:null;
    $cat=(isset($_REQUEST['camt']))?number_format($_REQUEST['camt'], 2, '.', ''):null;
    
    if (!is_null($oid) and !is_null($eid)) {
        $qry1 = "UPDATE [est] SET contractamt='".$cat."' WHERE officeid=".(int) $oid." AND estid=".(int) $eid.";";
        $res1 = mssql_query($qry1);
        //echo $qry1;
        
        $out['err']=0;
    }
    
    return $out;
}

function calcDimensionsInternal($oid,$estid) {
    $out=array('ia'=>0,'gl'=>0);
    
    $qry0 = "SELECT * FROM est WHERE officeid=".(int) $oid." AND estid=".(int) $estid.";";
    $res0 = mssql_query($qry0);
    $row0 = mssql_fetch_array($res0);
    
    if ($row0['pft']!=0 and $row0['sqft']!=0 and $row0['shal']!=0 and $row0['mid']!=0 and $row0['deep']) {
        $out['ia']=calc_internal_area($row0['pft'],$row0['sqft'],$row0['shal'],$row0['mid'],$row0['deep']);
        $out['gl']=calc_gallons($row0['pft'],$row0['sqft'],$row0['shal'],$row0['mid'],$row0['deep']);
    }
    
    return $out;
}

function calcDimensions() {
    $out=array('err'=>1,'ia'=>0,'gl'=>0);
    $pft    =(isset($_REQUEST['pft']) and $_REQUEST['pft']!=0)?$_REQUEST['pft']:0;
    $sqft   =(isset($_REQUEST['sqft']) and $_REQUEST['sqft']!=0)?$_REQUEST['sqft']:0;
    $shal   =(isset($_REQUEST['shal']) and $_REQUEST['shal']!=0)?$_REQUEST['shal']:0;
    $mid    =(isset($_REQUEST['mid']) and $_REQUEST['mid']!=0)?$_REQUEST['mid']:0;
    $deep   =(isset($_REQUEST['deep']) and $_REQUEST['deep']!=0)?$_REQUEST['deep']:0;
    
    if ($pft!=0 and $sqft!=0 and $shal!=0 and $mid!=0 and $deep!=0) {
        $out['err']=0;
        $out['ia']=calc_internal_area($pft,$sqft,$shal,$mid,$deep);
        $out['gl']=calc_gallons($pft,$sqft,$shal,$mid,$deep);
    }
    
    return $out;
}

function EstimateSearchResult() {
    $out='';
    //$out.='<pre>';
    //$out.=print_r($_REQUEST);
    //$out.='</pre><br>';
	$officeid=$_SESSION['officeid'];
	$securityid=$_SESSION['securityid'];
	$acclist=explode(",",$_SESSION['aid']);
    
    $order  =(isset($_REQUEST['order']))?$_REQUEST['order']:'estid';
    $dir    =(isset($_REQUEST['ascdesc']))?$_REQUEST['ascdesc']:'ASC';
    $etype  =(isset($_REQUEST['etype']) && $_REQUEST['etype']=='E')?'Estimates':'Quotes';

    if ($_REQUEST['subq']=="salesman") {
        $qry   = "SELECT ";
        $qry   .= "a.estid AS aestid, ";
        $qry   .= "a.officeid AS aoid,";
        $qry   .= "b.securityid AS asec,";
        $qry   .= "a.cid AS acid,";
        $qry   .= "a.contractamt AS acontr,";
        $qry   .= "a.added AS aadd,";
        $qry   .= "a.updated AS aup,";
        $qry   .= "a.submitted AS asub, ";
        $qry   .= "b.cfname AS bcfname, ";
        $qry   .= "b.clname AS bclname, ";
        $qry   .= "b.chome AS bchome, ";
        $qry   .= "b.custid AS bcustid, ";
        $qry   .= "b.estid AS bestid, ";
        $qry   .= "a.renov AS renov, ";
        $qry   .= "a.esttype AS esttype ";
        $qry  .= "FROM [est] AS a ";
        $qry  .= "INNER JOIN [cinfo] AS b ";
        $qry  .= "ON a.estid=b.estid ";
        $qry  .= "AND a.officeid=b.officeid ";
        $qry  .= "WHERE b.officeid=".(int) $_SESSION['officeid']." ";
        $qry  .= "AND b.jobid='0' ";
        $qry  .= "AND b.njobid='0' ";
        $qry  .= "AND b.securityid=".(int) $_REQUEST['assigned']." ";
        $qry   .="AND a.esttype = '".$_REQUEST['etype']."'  ";
        
        if (isset($_REQUEST['renov']) && $_REQUEST['renov']==1)
        {
            $qry   .="AND a.renov = ".(int) $_REQUEST['renov']."  ";
        }

        $qry  .= "ORDER BY ".$order." ".$dir.";";
    }
    elseif ($_REQUEST['subq']=="last_name") {
        if (empty($_REQUEST['sval'])) {
            echo "<b><font color=\"red\">Error!</font> Search String required.</b>";
            exit;
        }

        $qry   = "SELECT ";
        $qry   .= "a.estid AS aestid, ";
        $qry   .= "a.officeid AS aoid,";
        $qry   .= "b.securityid AS asec,";
        $qry   .= "a.cid AS acid,";
        $qry   .= "a.contractamt AS acontr,";
        $qry   .= "a.added AS aadd,";
        $qry   .= "a.updated AS aup,";
        $qry   .= "a.submitted AS asub, ";
        $qry   .= "b.cfname AS bcfname, ";
        $qry   .= "b.clname AS bclname, ";
        $qry   .= "b.chome AS bchome, ";
        $qry   .= "b.custid AS bcustid, ";
        $qry   .= "b.estid AS bestid, ";
        $qry   .= "a.renov AS renov, ";
        $qry   .= "a.esttype AS esttype ";
        $qry  .= "FROM [est] AS a ";
        $qry  .= "INNER JOIN [cinfo] AS b ";
        $qry  .= "ON a.estid=b.estid ";
        $qry  .= "AND a.officeid=b.officeid ";
        $qry  .= "WHERE b.officeid=".(int) $_SESSION['officeid']." ";
        $qry  .= "AND b.jobid='0' ";
        $qry  .= "AND b.njobid='0' ";
        $qry  .= "AND b.clname LIKE '".$_REQUEST['sval']."%' ";
        $qry   .="AND a.esttype = '".$_REQUEST['etype']."'  ";
        
        if (isset($_REQUEST['renov']) && $_REQUEST['renov']==1) {
            $qry   .="AND a.renov = ".(int) $_REQUEST['renov']." ";
        }
        
        $qry  .= "ORDER BY ".$order." ".$dir.";";
    }

	$res   = mssql_query($qry);
	$nrows = mssql_num_rows($res);
	
	if ($nrows < 1) {
		$out.="<table width=\"700px\">\n";
		$out.="   <tr>\n";
		$out.="   <form method=\"post\">\n";
		$out.="   <input type=\"hidden\" name=\"action\" value=\"leads\">\n";
		$out.="   <input type=\"hidden\" name=\"call\" value=\"new\">\n";
		$out.="      <td align=\"center\">\n";
		$out.=($_REQUEST['call']=="search_results")?"<h4><b>".$etype." Search did not produce any Results!</h4>":"<h4>No ".$etype."  on File!</h4>";
		$out.="      </td>\n";
		$out.="   </form>\n";
		$out.="   </tr>\n";
		$out.="</table>\n";
	}
	else {
		$out.="         <table style=\"width:735px;\">\n";
		$out.="            <tr>\n";
		$out.="               <td align=\"left\">\n";
		$out.="                  <table width=\"100%\" bgcolor=\"white\">\n";
		$out.="                  <tr>\n";
		$out.="                     <td class=\"tblhd\" align=\"center\"><b></b></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"center\"><b>#</b></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"center\"><b>Renov</b></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"left\"><b>Customer</b></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"center\"><b>Phone</b></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"center\"><b>Contract Amt</b></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"left\"><b>SalesRep</b></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"center\"><b>Created</b></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"center\"><b>Updated</b></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"left\"></td>\n";
		$out.="                     <td class=\"tblhd\" align=\"center\"><b>#</b></td>\n";
		$out.="                  </tr>\n";

		$tcon=0;
		$xi=0;
		while($row=mssql_fetch_array($res)) {
			$xi++;
			$tbg = ($xi%2)?"even":"odd";

			$qryC = "SELECT fname,lname,securityid,sidm,slevel FROM security WHERE securityid='".$row['asec']."'";
			$resC = mssql_query($qryC);
			$rowC = mssql_fetch_array($resC);

			$secl=explode(",",$rowC['slevel']);
			$fstyle=($secl[6]==0)?"red":"black";

			if (in_array($row['asec'],$acclist)||$_SESSION['jlev'] >= 6) {
				$tcon			=$tcon+$row['acontr'];
				$fconamt		=number_format($row['acontr'], 2, '.', ',');
				$odate =(isset($row['aadd']))?date("m-d-Y", strtotime($row['aadd'])):'';
				$udate =(isset($row['aup']))?date("m-d-Y", strtotime($row['aup'])):'';
				$sdate = (isset($row['asub']))?date("m-d-Y", strtotime($row['asub'])):'';
				$renov=($row['renov']==1)?"R":'';

				$out.="                  <tr class=\"".$tbg."\">\n";
				$out.="                     <td align=\"right\">".$xi.".</td>\n";
				$out.="                     <td align=\"right\">".$row['aestid']."</td>\n";
				$out.="                     <td align=\"center\"><b>".$renov."</b></td>\n";	
				$out.="                     <td align=\"left\"><b>".$row['bclname']."</b>, ".$row['bcfname']."</td>\n";
				$out.="                     <td align=\"left\">";
                $out.=format_phonenumber(preg_replace('/\.|-|\s/i','$1$2$3',trim($row['bchome'])));
                $out.="                      </td>\n";
				$out.="                     <td align=\"right\">".$fconamt."</td>\n";
				$out.="                     <td align=\"left\"><font class=\"".$fstyle."\">".$rowC['lname'].", ".$rowC['fname']."</font></td>\n";
				$out.="                     <td align=\"center\">".$odate."</td>\n";
				$out.="                     <td align=\"center\">".$udate."</td>\n";
				$out.="                     <td align=\"center\">\n";
				$out.="                        <form amethod=\"POST\">\n";
				$out.="                           <input type=\"hidden\" name=\"action\" value=\"est\">\n";
				$out.="                           <input type=\"hidden\" name=\"call\" value=\"EstimateView\">\n";
                $out.="                           <input type=\"hidden\" name=\"oid\" value=\"".$row['aoid']."\">\n";
				$out.="                           <input type=\"hidden\" name=\"estid\" value=\"".$row['aestid']."\">\n";
				$out.="                           <input type=\"hidden\" name=\"esttype\" value=\"".$row['esttype']."\">\n";
				
				if ($row['esttype']=='E')
				{
                    $out.="                          <input class=\"transnb JMStooltip\" type=\"image\" src=\"images/application_form_magnify.png\" title=\"Open Estimate\">\n";
				}
				else
				{
                    $out.="                          <input class=\"transnb JMStooltip\" type=\"image\" src=\"images/application_form_magnify.png\" title=\"Open Quote\">\n";
					$out.="                           <input class=\"buttondkgrypnl80\" type=\"submit\" value=\"View Quote\">\n";
				}
				
				$out.="                        </form>\n";
				$out.="                     </td>\n";
				$out.="                     <td align=\"right\">".$xi."</td>\n";
				$out.="                  </tr>\n";
			}
		}

		$out.="                  </table>\n";
		$out.="               </td>\n";
		$out.="            </tr>\n";
		$out.="         </table>\n";
	}
    
    return $out;
}

function format_phonenumber($n) {
	$out='';
	
	$n=preg_replace('/\.|-|\s/i','$1$2$3',trim($n));
	
	if (strlen($n)==10) {
		$out=substr($n,0,3).'-'.substr($n,3,3).'-'.substr($n,6,4);
	}
	elseif (strlen($n)==7) {
		$out=substr($n,0,3).'-'.substr($n,3,4);
	}
	else {
		$out=$n;
	}
	
	return $out;
}

function saveManualCommissionAdjust() {
    $out=array();
    $out['error']=true;
    //$out['result']=__LINE__;
    
    $estid  =(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:null;
    $cbtype =(isset($_REQUEST['cbtype']) and $_REQUEST['cbtype']!=0)?$_REQUEST['cbtype']:null;
    $ctype  =(isset($_REQUEST['ctype']) and $_REQUEST['ctype']!=0)?$_REQUEST['ctype']:null;
    $rwdamt =(isset($_REQUEST['rwdamt']) and $_REQUEST['rwdamt']!=0)?$_REQUEST['rwdamt']:0;
    $rwdrate=(isset($_REQUEST['rwdrate']) and $_REQUEST['rwdrate']!=0)?$_REQUEST['rwdrate']:0;
    $label  =(isset($_REQUEST['label']) and strlen($_REQUEST['label'])!=0)?trim($_REQUEST['label']):null;
    $notes  =(isset($_REQUEST['notes']) and strlen($_REQUEST['notes'])!=0)?trim($_REQUEST['notes']):null;
    
    if (!is_null($estid) and !is_null($cbtype)) {
        $oid=$_SESSION['officeid'];
        $sid=$_SESSION['securityid'];
        
        $qry = "SELECT modcomm FROM security WHERE securityid=".(int) $sid.";";
		$res = mssql_query($qry);
        $row = mssql_fetch_array($res);
        
        if (isset($row['modcomm']) and $row['modcomm']!=0) {
            $qry0 = "SELECT * FROM est WHERE officeid=".(int) $oid." AND estid=".(int) $estid.";";
            $res0 = mssql_query($qry0);
            $nrow0= mssql_num_rows($res0);
            
            if ($nrow0!=0) {                
                $qry1 = "SELECT * FROM CommissionSchedule WHERE oid=".(int) $oid." AND estid=".(int) $estid." and cbtype=".(int) $cbtype.";";
                $res1 = mssql_query($qry1);
                $nrow1= mssql_num_rows($res1);
                
                if ($nrow1==0) {
                    $qry1 = "INSERT INTO CommissionSchedule (oid,secid,estid,cbtype,type,amt,rate,label,notes) VALUES (".(int) $oid.",".(int) $sid.",".(int) $estid.",".(int) $cbtype.",".(int) $ctype.",cast('".$rwdamt."' as money),'".$rwdrate."','".(string) $label."','".(string) $notes."');SELECT @@IDENTITY;";
                    $res1 = mssql_query($qry1);
                    $row1 = mssql_fetch_array($res1);
                    
                    $out['error']=false;
                    $out['result']=$row1[0];
                }
                else {
                    $out['result']='Manual Commission Adjust already exists for this Estimate ('.__LINE__.')';
                }
            }
            else {
                $out['result']='No Estimate Found ('.__LINE__.')';
            }
        }
        else {
            $out['result']='Unauthorized ('.__LINE__.')';
        }
    }
    else {
        $out['result']='Invalid Parameter(s) ('.__LINE__.')';
    }
    
    return $out;
}

function deleteManualCommissionAdjust() {
    $out=array();
    
    $estid  =(isset($_REQUEST['estid']) and $_REQUEST['estid']!=0)?$_REQUEST['estid']:null;
    
    if (!is_null($estid)) {
        $oid=$_SESSION['officeid'];
        $sid=$_SESSION['securityid'];
        
        $qry = "SELECT modcomm FROM security WHERE securityid=".(int) $sid.";";
		$res = mssql_query($qry);
        $row = mssql_fetch_array($res);
        
        if (isset($row['modcomm']) and $row['modcomm']!=0) {
            $qry0 = "SELECT * FROM est WHERE officeid=".(int) $oid." AND estid=".(int) $estid.";";
            $res0 = mssql_query($qry0);
            $nrow0= mssql_num_rows($res0);
            
            if ($nrow0!=0) {
                $qry1 = "DELETE FROM CommissionSchedule WHERE oid=".(int) $oid." AND estid=".(int) $estid." AND (cbtype=10 OR cbtype=11);";
                $res1 = mssql_query($qry1);
                $out['error']=false;
            }
            else {
                $out['result']='No Estimate Found ('.__LINE__.')';
            }
        }
        else {
            $out['result']='Unauthorized ('.__LINE__.')';
        }
    }
    else {
        $out['result']='Invalid Parameter(s) ('.__LINE__.')';
    }
    
    return $out;
}