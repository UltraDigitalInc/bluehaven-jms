<?php



if (!isset(COSTPHASE['timestamp']) || (time() - COSTPHASE['timestamp']) > 7200)
{
    echo time();
    $cp=array();
    
    $cp['timestamp']=time();
    
    $qryA = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phstype!='M' AND costing=1 ORDER BY seqnum ASC;";
    $resA = mssql_query($qryA);
    while($rowA = mssql_fetch_array($resA))
    {
        $cp['Labor'][$rowA['phsid']]=array('phsid'=>$rowA['phsid'],'phscode'=>$rowA['phscode'],'phsname'=>$rowA['phsname'],'seqnum'=>$rowA['seqnum'],'extphsname'=>$rowA['extphsname']);
    }
    
    $qryB = "SELECT phsid,phscode,phsname,seqnum,extphsname FROM phasebase WHERE phstype='M' AND costing=1 ORDER BY seqnum ASC;";
    $resB = mssql_query($qryB);
    while($rowB = mssql_fetch_array($resB))
    {
        $cp['Material'][$rowB['phsid']]=array('phsid'=>$rowB['phsid'],'phscode'=>$rowB['phscode'],'phsname'=>$rowB['phsname'],'seqnum'=>$rowB['seqnum'],'extphsname'=>$rowB['extphsname']);
    }
    
    define("COSTPHASE", $cp);
}

?>