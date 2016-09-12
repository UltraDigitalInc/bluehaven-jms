<?php
session_cache_limiter('private_no_expire');
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);

if (!isset($_SESSION['securityid'])) {
    echo 'Log into the JMS first';
    exit;
}

include_once ('../connect_db.php');

if (isset($_REQUEST['json']) and $_REQUEST['json']=='1') {
    echo procJSON();
}
else {
    echo '<html>';
    echo '<head>';
    echo '  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>';
    echo '  <script type="text/javascript" src="/js/pbgen2.js"></script>';
    echo '</head>';
    echo '<body>';
    
    if (isset($_REQUEST['a']) and strlen($_REQUEST['a']) > 0) {
        switch($_REQUEST['a']) {
            case 'select':
                echo showOfficeSelect();
            break;
        
            case 'review':
                unset($_SESSION['pbupdate_json']);
                echo reviewPBUpdate();
            break;
        
            case 'process':
                echo processPBUpdate();
            break;
        
            case 'json':
                echo procJSON();
            break;
        
            default:
                echo showOfficeSelect();
            break;
        }
    }
    else {    
        echo showOfficeSelect();
    }
    
    echo '</body>';
    echo '</html>';
}

function processPBUpdate() {
    //echo '<pre>';
    //print_r($_REQUEST);    
    //echo '</pre>';
    
    if (isset($_REQUEST['pbretailitem'])) {
        $o=getOfficeInfo($_REQUEST['oid']);
        
        if ($o['oid']!=0) {
            $pbcode=(isset($o['pbcode']) and $o['pbcode']!='0')?$o['pbcode']:'';            
            
            $i=array();
            foreach($_REQUEST['pbretailitem'] as $k=>$v) {
                if (isset($_REQUEST['pbretailitem'][$k]['inc']))
                {
                    $i[]="UPDATE [".$pbcode."acc] SET rp=convert(money,'".$_REQUEST['pbretailitem'][$k]['rp']."') WHERE id=".(int) $k.";";
                }
            }
            
            $c=0;
            if (count($i) >0) {                
                foreach ($i as $kp=>$vp) {
                    //echo $vp.'<br>';
                    $qry = $vp;
                    $res = mssql_query($qry);
                    $c++;
                }
            }
            else {
                echo 'No items selected. Nothing to do...<br>';
            }
            
            echo $c.' Items Processed<br>';
            echo '<a href="/subs/generatepbupdate.php">Return</a><br>'; 
        }
        else {
            echo 'Nothing to Process: Invalid Office ('.__LINE__.')';
        }
    }
    else {
        echo 'Nothing to Process: No Items ('.__LINE__.')';
    }
}

function showOfficeSelect() {
    $o=getOffices();
    $oid=(isset($_SESSION['officeid']) and $_SESSION['officeid']!=0)?$_SESSION['officeid']:null;
    
    $out="PriceBook Retail Pricing Update<br><small>(Based upon Cost per Item)</small><br>";
    $out.="<form action=\"/subs/generatepbupdate.php\" method=\"post\">\n";
    $out.="<input type=\"hidden\" name=\"a\" value=\"review\">";    
    $out.="<table>\n";
    $out.="<tr>\n";
    $out.="<td>Office</td>\n";
    
    if (!is_null($oid)) {
        $out.="<td>".$_SESSION['offname']." <input type=\"hidden\" name=\"o\" value=\"".$oid."\"></td>\n";
    }
    else {
        $out.="<td><select name=\"o\">\n";
    
        foreach ($o as $k=>$v) {
            if (isset($_REQUEST['o']) and $_REQUEST['o']==$v['oid']) {
                $out.="<option value=\"".$v['oid']."\" SELECTED>".$v['name']."</option>\n";
            }
            else {
                $out.="<option value=\"".$v['oid']."\">".$v['name']."</option>\n";
            }
        }
        
        $out.="</select></td>\n";
    }
    
    $out.="</tr>\n";
    
    if (!is_null($oid)) {
        $cat=getPBCats($oid);
        $out.="<tr>\n";
        $out.="<td>Category</td>\n";
        $out.="<td><select name=\"s\">\n";
        $out.="<option value=\"0\">All</option>\n";
    
        foreach ($cat as $kc=>$vc) {
            if (isset($_REQUEST['s']) and $_REQUEST['s']==$vc['catid']) {
                $out.="<option value=\"".$vc['catid']."\" SELECTED>".$vc['name']."</option>\n";
            }
            else {
                $out.="<option value=\"".$vc['catid']."\">".$vc['name']."</option>\n";
            }
        }
        
        $out.="</select></td>\n";
        $out.="</tr>\n";
    }
    
    $out.="<tr>\n";
    $out.="<td>Percent Change</td>\n";
    $out.="<td><select name=\"p\">\n";

    for ($x=50;$x>=-10;$x--) {
        if (isset($_REQUEST['p']) and $_REQUEST['p']==$x) {
            $out.="<option value=\"".$x."\" SELECTED>".$x."</option>\n";
        }
        else {
            $out.="<option value=\"".$x."\">".$x."</option>\n";
        }
    }
    
    $out.="</select>%</td>\n";
    $out.="</tr>\n";
    
    if ($_SESSION['securityid']==26) {
        $out.="<tr>\n";
        $out.="<td>JSON Debug</td>\n";
        $out.="<td>\n";
        
        if (isset($_REQUEST['json']) and $_REQUEST['json']==1) {
            $out.="<input type=\"checkbox\" name=\"json\" value=\"1\" checked=\"checked\">\n";
        }
        else {
            $out.="<input type=\"checkbox\" name=\"json\" value=\"1\">\n";
        }
        
        $out.="</td>\n";
        $out.="</tr>\n";
    
    $out.="<tr>\n";
    $out.="<td>Show $0 Retail:</td>\n";
    $out.="<td>\n";
    
    if (isset($_REQUEST['zdir']) and $_REQUEST['zdir']==1) {
        $out.="<input type=\"checkbox\" name=\"zdir\" value=\"1\" checked=\"checked\">\n";
    }
    else {
        $out.="<input type=\"checkbox\" name=\"zdir\" value=\"1\">\n";
    }
    
    $out.="</td>\n";
    $out.="</tr>\n";
    
    $out.="<tr>\n";
    $out.="<td>Show $0 Cost:</td>\n";
    $out.="<td>\n";
    
    if (isset($_REQUEST['zdic']) and $_REQUEST['zdic']==1) {
        $out.="<input type=\"checkbox\" name=\"zdic\" value=\"1\" checked=\"checked\">\n";
    }
    else {
        $out.="<input type=\"checkbox\" name=\"zdic\" value=\"1\">\n";
    }
    
    $out.="</td>\n";
    $out.="</tr>\n";
    }
    
    $out.="<tr>\n";
    $out.="<td><a href=\"/subs/generatepbupdate.php\">Reset</a></td>\n";
    $out.="<td>\n";
    $out.="<button>Review</button>";    
    $out.="<td>\n";
    $out.="</tr>\n";
    $out.="</table>";
    $out.="</form>";
    
    return $out;
}

function getPBCats($oid=null) {
    $out=array();
    
    $qry  = "select catid,name from AC_cats where officeid=".(int) $oid." and active=1 order by seqn";
    $res  = mssql_query($qry);    
    
    while ($row  = mssql_fetch_array($res)) {
        $out[]=array('catid'=>$row['catid'],'name'=>$row['name']);
    }
    
    return $out;
}

function getOffices() {
    $out=array();
    
    $qry  = "SELECT officeid as oid,name FROM offices WHERE active=1 and enest=1 ORDER BY name ASC";
    $res  = mssql_query($qry);    
    
    while ($row  = mssql_fetch_array($res)) {
        $out[]=array('oid'=>$row['oid'],'name'=>$row['name']);
    }
    
    return $out;
}

function getOfficeInfo($o) {
    $out=array();
    
    $qry  = "SELECT officeid as oid,name,pb_code as pbcode FROM offices WHERE officeid=".(int) $o.";";
    $res  = mssql_query($qry);    
    
    while ($row  = mssql_fetch_array($res)) {
        $out=array('oid'=>$row['oid'],'name'=>$row['name'],'pbcode'=>$row['pbcode']);
    }
    
    return $out;
}

function reviewPBUpdate() {
    $o=showOfficeSelect();
    $i=getPBItems();
    $v=(isset($i['error']['errno']) and $i['error']['errno']==0)?htmloutput($i):$i['error']['result'];
    $_SESSION['pbupdate_json']=json_encode($i);
    
    //echo $_SESSION['pbupdate_json'];
    //echo '<pre>';
    //print_r($i);
    //echo '</pre>';
    
    $out='';    
    $out.="<table>\n";
    $out.="<tr><td valign=\"top\">".$o."</td><td valign=\"top\">".$v."</td></tr>\n";
    $out.="</table>\n";
    
    return $out;
}

function getPBItems() {
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    $oid    =(isset($_REQUEST['o']) and $_REQUEST['o']!=0)?$_REQUEST['o']:null;
    $ctype  =(isset($_REQUEST['c']) and ($_REQUEST['c']=='l' or $_REQUEST['c']=='m'))?$_REQUEST['c']:null;
    $perc   =(isset($_REQUEST['p']) and is_numeric($_REQUEST['p']))?($_REQUEST['p'] *.01):null;
    $out    =array();
    
    $out['error']['errno']=__LINE__;
    $out['error']['result']='Not Processed';
    $out['result']=array();
    
    //exit;
    if (!is_null($oid) and is_float($perc)) {
        include_once('../calc_func.php');
        $qry  = "SELECT officeid as oid,pb_code as pbcode FROM offices WHERE officeid=".(int) $oid;
        $res  = mssql_query($qry);
        $row  = mssql_fetch_array($res);
        $nrow = mssql_num_rows($res);
        //echo 'Test<br>';
        
        if ($nrow > 0) {
            $catid =(isset($_REQUEST['s']) and $_REQUEST['s']!=0)?$_REQUEST['s']:0;
            $pbcode=(isset($row['pbcode']) and $row['pbcode']!='0')?$row['pbcode']:'';
            $ritems=array();
            
            if ($catid==0) {
                $qry1  = "SELECT a.id,a.aid,a.item,a.rp,a.qtype,a.def_quan,a.quan_calc,a.lrange,a.hrange FROM [".$pbcode."acc] AS a WHERE a.officeid=".(int) $oid." AND (a.qtype!=32 AND a.qtype!=33) ORDER BY a.id;";
            }
            else {
                $qry1  = "SELECT a.id,a.aid,a.item,a.rp,a.qtype,a.def_quan,a.quan_calc,a.lrange,a.hrange FROM [".$pbcode."acc] AS a WHERE a.officeid=".(int) $oid." AND (a.qtype!=32 AND a.qtype!=33) AND a.catid=".(int) $catid." ORDER BY a.id;";
            }
            
            $res1  = mssql_query($qry1);
            $nrow1 = mssql_num_rows($res1);
            
            if ($nrow1 > 0) {
                //echo '<pre>';
                $q=array('pft'=>80,'sqft'=>400,'s'=>3,'m'=>4,'d'=>5,'dquan'=>1);                
                while ($row1  = mssql_fetch_array($res1)) {
                    $ncalc=uni_calc_loop($row1['qtype'],0,$row1['rp'],$row1['lrange'],$row1['hrange'],$q['dquan'],$row1['def_quan'],0,0,0,0,0,0,0,0,0,0);
                    //$ncalc[3]=$row1['rp'];
                    //$ncalc[4]=$row1['qtype'];
                    //print_r($ncalc);
                    $ritems[$row1['id']]=array('aid'=>$row1['aid'],'name'=>$row1['item'],'retail'=>$ncalc[1],'tcost'=>0,'update'=>0,'total'=>0);
                }
                //echo '</pre>';
            }
            
            //print_r($ritems);
            
            if (count($ritems) > 0) {
                //$tc=0;
                //$tc_perc=0;
                //$tc_total=0;
                
                foreach ($ritems as $nr => $vr) {
                    // Labor
                    $qry2l  = "SELECT cid FROM [".$pbcode."rclinks_l] WHERE rid=".(int) $nr;
                    $res2l  = mssql_query($qry2l);
                    $nrow2l = mssql_num_rows($res2l);
                    $rpl=array();
                    $tcl=0;
                    $tclu=0;
                    $tclt=0;
                    $rpm=array();
                    $tcm=0;
                    $tcmu=0;
                    $tcmt=0;
                    //echo '<pre>';
                    if ($nrow2l > 0) {
                        while ($row2l  = mssql_fetch_array($res2l)) {
                            $qry3l  = "SELECT id,bprice,qtype,lrange,hrange,quantity FROM [".$pbcode."accpbook] WHERE id=".(int) $row2l['cid'];
                            $res3l  = mssql_query($qry3l);
                            $row3l  = mssql_fetch_array($res3l);
                            $rpl    = uni_calc_loop($row3l['qtype'],$row3l['bprice'],0,$row3l['lrange'],$row3l['hrange'],$q['dquan'],$row3l['quantity'],0,0,0,0,0,0,0,0,0,0);
                            //print_r($rpl);
                            //$tcl=($tcl+$row3l['bprice']);
                            $tcl=($tcl+$rpl[0]);
                            $tclu=($tcl * $perc);
                            $tclt=($tcl + $tclu);
                            
                            $ritems[$nr]['cost']['lab'][$row2l['cid']]=array('bp'=>$row3l['bprice'],'qtype'=>$row3l['qtype']);
                        }
                    }
                    
                    // Material
                    $qry2m  = "SELECT cid FROM [".$pbcode."rclinks_m] WHERE rid=".(int) $nr;
                    $res2m  = mssql_query($qry2m);
                    $nrow2m = mssql_num_rows($res2m);
                    
                    if ($nrow2m > 0) {
                        while ($row2m  = mssql_fetch_array($res2m)) {
                            $qry3m  = "SELECT invid,bprice,qtype,quan_calc FROM [".$pbcode."inventory] WHERE invid=".(int) $row2m['cid'];
                            $res3m  = mssql_query($qry3m);
                            $row3m  = mssql_fetch_array($res3m);
                            $rpm    = uni_calc_loop($row3m['qtype'],$row3m['bprice'],0,0,0,$q['dquan'],$row3m['quan_calc'],0,0,0,0,0,0,0,0,0,0);
                            //print_r($rpm);
                            //$tcm=($tcm+$row3m['bprice']);
                            $tcm=($tcm+$rpm[1]);
                            $tcmu=($tcm * $perc);
                            $tcmt=($tcm + $tcmu);
                            
                            $ritems[$nr]['cost']['mat'][$row2m['cid']]=array('bp'=>$row3m['bprice'],'qtype'=>$row3m['qtype']);
                        }
                    }
                    //echo '</pre>';
                    $ritems[$nr]['tcost']=$tcl+$tcm;
                    $ritems[$nr]['update']=$tclu+$tcmu;
                    $ritems[$nr]['total']=$tclt+$tcmt;
                }
            
                $out['result']=$ritems;
                $out['error']['errno']=0;
                $out['error']['result']='';
            }
            else {
                $out['error']['errno']=__LINE__;
                $out['error']['result']='Error: no Retail Items '.__LINE__;
            }
        }
    }
    else {
        $out['error']['errno']=__LINE__;
        $out['error']['result']='Error: Missing or Invalid Parameter '.__LINE__;
    }
    
    return $out;
}

function procJSON() {
    $in=getPBItems();
    header('Content-type: application/json');
    $out=json_encode($in);
    return $out;
}

function procOutput($in=null) {
    if (isset($_REQUEST['t']) and $_REQUEST['t']=='json') {
        header('Content-type: application/json');
        $out=json_encode($out);
        echo $out;
    }
    else {
        echo htmloutput($out);
    }
}

function htmloutput($i) {
    $zdir    =(isset($_REQUEST['zdir']) and $_REQUEST['zdir']==1)?true:false;
    $zdic    =(isset($_REQUEST['zdic']) and $_REQUEST['zdic']==1)?true:false;
    $out='';
    //print_r($i);
    
    $out.='<form id="updateItemsForm" method="post">';
    $out.='<input type="hidden" name="a" value="process">';
    $out.='<input type="hidden" name="oid" value="'.$_REQUEST['o'].'">';
    $out.='<table border="1">';
    $out.='<tr><td>Code</td><td>Item</td><td>Current Retail</td><td>Current Cost</td><td>Increase</td><td>New Retail</td><td><a id="includeAll" href="#">Select All</a>/<a id="exincludeAll" href="#">Unselect All</a></td></tr>';
    
    foreach ($i['result'] as $n => $v) {
        $out.='<tr>';
        $out.='<td>'.$v['aid'].'</td>';
        $out.='<td>'.$v['name'].'</td>';
        $out.='<td>'.number_format($v['retail'],2,'.','').'</td>';
        $out.='<td>'.number_format($v['tcost'],2,'.','').'</td>';
        $out.='<td>'.number_format($v['update'],2,'.','').'</td>';
        $out.='<td>'.number_format($v['total'],2,'.','').'<input class="pbitem" type="hidden" name="pbretailitem['.$n.'][rp]" value="'.number_format($v['total'],2,'.','').'"></td>';
        //$out.='<td>'.number_format($v['total'],2,'.','').'<input class="pbitem" type="hidden" name="pbretailitem_'.$n.'_rp" value="'.number_format($v['total'],2,'.','').'"></td>';
        $out.='<td><input class="pbitem" type="checkbox" name="pbretailitem['.$n.'][inc]" value="'.$n.'"></td>';
        $out.='</tr>';
    }
    
    $out.='</table>';
    
    if ($_SESSION['securityid']==26 or $_SESSION['securityid']==332) {
        $out.='<button id="btn_updateItem">Process</button>';
    }
    $out.='</form>';
    
    return $out;
}
