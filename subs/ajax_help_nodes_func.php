<?php

function get_HelpNode($nodeid)
{
    $out='';
    
    $qry0 = "SELECT nid,nodeid,nodetitle,nodetext,nodefoot,imgtext FROM jest_doc..HelpNode WHERE nodeid='".$nodeid."';";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
    
    if ($nrow0 == 1)
    {
        $row0=mssql_fetch_array($res0);
        $out=$row0['nodetext'];
    }
	else
	{
		$out='Help not found for this Topic';
	}
    
    return $out;
}

?>