<?php
/**
 * Created by PhpStorm.
 * User: Allen Do
 * Date: 9/1/2015
 * Time: 9:54 PM
 */

function autosort_ZIP()
{
    echo '\nSORT_ZIP<br>';
    //ini_set('display_errors','On');
    //error_reporting(E_ALL);

    $recdate	=time();
    $cdate		=time();
    $secid      =1797;
    $out_ar		=array();
    $cid_ar		=array();

    $qry		= "SELECT * FROM lead_inc WHERE sorted!=1 and source not in (select statusid from leadstatuscodes where active=2 and oid!=0);";
    $res		= mssql_query($qry);
    $nrow		= mssql_num_rows($res);

    $qry0	= "SELECT * FROM offices WHERE active=1 AND am!='0';";
    $res0	= mssql_query($qry0);
    $nrow0	= mssql_num_rows($res0);

    $ap=0;
    if ($nrow0 > 0)
    {
        $sarray=array(0=>0);
        while($row0=mssql_fetch_array($res0))
        {
            $sarray[$row0['officeid']]=0;
        }
    }
    else
    {
        echo "<font color=\"red\"><b>ERROR!</b> no active Offices!</font>\n";
    }

    if ($nrow > 0)
    {
        //$cid_ar=array();
        while($row=mssql_fetch_array($res))
        {
            $inscnt	=0;
            $isdupe	=check_dupe($row['url_ref'],$row['email'],$row['phone'],$row['addr']);
            //$isdupe = false;
            $trzip	=trim($row['zip']);
            if (strlen($trzip) == 5 and !$isdupe[0])
            {
                $qryA	= "SELECT * FROM zip_to_zip WHERE czip='".$trzip."';";
                $resA	= mssql_query($qryA);
                $nrowA	= mssql_num_rows($resA);

                if ($nrowA == 1)
                {
                    while($rowA=mssql_fetch_array($resA))
                    {
                        if ($inscnt==0)
                        {
                            $qryB	= "SELECT officeid,am,name,active,leadforward FROM offices WHERE zip='".$rowA['ozip']."';";
                            $resB	= mssql_query($qryB);
                            $rowB	= mssql_fetch_array($resB);

                            if ($rowB['leadforward']==0)
                            {
                                echo 'ORG';
                                if ($rowB['am']!=0 && $rowB['active']==1)
                                {
                                    //echo $row['phone']."(".$split[0].") SUB<br>";
                                    if (preg_match("/\\s/",trim($row['lname'])))
                                    {
                                        $ndata=splitonspace(($row['lname']));
                                    }
                                    else
                                    {
                                        $ndata=array($row['fname'],$row['lname']);
                                    }

                                    $qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowB['officeid']."';";
                                    $resCa = mssql_query($qryCa);
                                    $rowCa = mssql_fetch_row($resCa);
                                    $nrowCa= mssql_num_rows($resCa);

                                    if ($nrowCa==0)
                                    {
                                        $ncid=1;
                                    }
                                    else
                                    {
                                        $ncid=$rowCa[0]+1;
                                    }

                                    $qryC	= "INSERT INTO cinfo ";
                                    $qryC .= "(added,updated,officeid,securityid,cfname,clname,";
                                    $qryC .= "caddr1,ccity,cstate,czip1,";
                                    $qryC .= "saddr1,scity,sstate,szip1,ssame,";
                                    $qryC .= "cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
                                    $qryC .= "VALUES (";
                                    $qryC .= "'".$row['submitted']."',getdate(),'".$rowB['officeid']."','".$rowB['am']."','".replacequote($ndata[0])."','".replacequote($ndata[1])."',";
                                    $qryC .= "'".replacequote(trim($row['addr']))."','".replacequote(trim($row['city']))."','".replacequote(trim($row['state']))."','".trim($row['zip'])."',";
                                    $qryC .= "'".replacequote(trim($row['addr']))."','".replacequote(trim($row['city']))."','".replacequote(trim($row['state']))."','".trim($row['zip'])."',1,";
                                    $qryC .= "'".trim($row['bphone'])."',";

                                    if ($row['bphone']=="wk")
                                    {
                                        $qryC .= "'','".trim($row['phone'])."',";
                                    }
                                    else
                                    {
                                        $qryC .= "'".trim($row['phone'])."','',";
                                    }

                                    $qryC .= "'".replacequote(trim($row['email']))."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
                                    $qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."'); select @@IDENTITY as cidid;";
                                    $resC	= mssql_query($qryC);
                                    $rowC  = mssql_fetch_array($resC);

                                    $cid_ar[]=$rowC['cidid'];

                                    if (isset($rowC['cidid']) and $rowC['cidid']!=0)
                                    {
                                        $qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowB['officeid']."',secid='".$secid."',cid=".$rowC['cidid']." WHERE lid='".$row['lid']."';";
                                        $resD	= mssql_query($qryD);

                                        if (!empty($row['comments']))
                                        {
                                            $qryDa	= "
														INSERT INTO jest..chistory (officeid,custid,secid,act,mtext)
														VALUES (".$rowB['officeid'].",".$rowC['cidid'].",".$secid.",'leads','".replacequote($row['comments'])."');";
                                            $resDa	= mssql_query($qryDa);
                                        }

                                        $qryE	= "INSERT INTO jest..EmailIntrosSent (cid) VALUES (".$rowC['cidid'].");";
                                        $resE	= mssql_query($qryE);

                                        $oid=$rowB['officeid'];

                                        if (is_array($sarray))
                                        {
                                            $sarray[$oid]=$sarray[$oid]+1;
                                        }
                                        else
                                        {
                                            $sarray=array($oid=>1);
                                        }
                                        $inscnt++;
                                    }
                                }
                            }
                            else
                            {
                                echo 'FWD';
                                $qryBa	= "SELECT officeid,am,name,active FROM offices WHERE officeid='".$rowB['leadforward']."';";
                                $resBa	= mssql_query($qryBa);
                                $rowBa	= mssql_fetch_array($resBa);

                                if ($rowBa['am']!=0 && $rowBa['active']==1)
                                {
                                    //$ndata=array($row['fname'],$row['lname']);
                                    if (preg_match("/\\s/",trim($row['lname'])))
                                    {
                                        $ndata=splitonspace(($row['lname']));
                                    }
                                    else
                                    {
                                        $ndata=array($row['fname'],$row['lname']);
                                    }

                                    $qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$rowBa['officeid']."';";
                                    $resCa = mssql_query($qryCa);
                                    $rowCa = mssql_fetch_row($resCa);
                                    $nrowCa= mssql_num_rows($resCa);

                                    if ($nrowCa==0)
                                    {
                                        $ncid=1;
                                    }
                                    else
                                    {
                                        $ncid=$rowCa[0] + 1;
                                    }

                                    $qryC	= "INSERT INTO cinfo ";
                                    $qryC .= "(added,updated,officeid,securityid,cfname,clname,";
                                    $qryC .= "caddr1,ccity,cstate,czip1,";
                                    $qryC .= "saddr1,scity,sstate,szip1,ssame,";
                                    $qryC .= "cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
                                    $qryC .= "VALUES (";
                                    $qryC .= "'".$row['submitted']."',getdate(),'".$rowBa['officeid']."','".$rowBa['am']."','".replacequote($ndata[0])."','".replacequote($ndata[1])."',";
                                    $qryC .= "'".replacequote(trim($row['addr']))."','".replacequote(trim($row['city']))."','".replacequote(trim($row['state']))."','".trim($row['zip'])."',";
                                    $qryC .= "'".replacequote(trim($row['addr']))."','".replacequote(trim($row['city']))."','".replacequote(trim($row['state']))."','".trim($row['zip'])."',1,";
                                    $qryC .= "'".trim($row['bphone'])."',";

                                    if ($row['bphone']=="wk")
                                    {
                                        $qryC .= "'','".trim($row['phone'])."',";
                                    }
                                    else
                                    {
                                        $qryC .= "'".trim($row['phone'])."','',";
                                    }

                                    $qryC .= "'".replacequote(trim($row['email']))."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
                                    $qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."'); select @@IDENTITY as cidid;";
                                    $resC  = mssql_query($qryC);
                                    $rowC  = mssql_fetch_array($resC);

                                    echo $qryC.'<br>';

                                    $cid_ar[]=$rowC['cidid'];

                                    if (isset($rowC['cidid']) and $rowC['cidid']!=0)
                                    {
                                        $qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$rowBa['officeid']."',secid='".$secid."',cid=".$rowC['cidid']." WHERE lid='".$row['lid']."';";
                                        $resD	= mssql_query($qryD);

                                        if (!empty($row['comments']))
                                        {
                                            $qryDa	= "
														INSERT INTO jest..chistory (officeid,custid,secid,act,mtext)
														VALUES (".$rowB['officeid'].",".$rowC['cidid'].",".$secid.",'leads','".replacequote($row['comments'])."');";
                                            $resDa	= mssql_query($qryDa);
                                        }

                                        $qryE	= "INSERT INTO jest..EmailIntrosSent (cid) VALUES (".$rowC['cidid'].");";
                                        $resE	= mssql_query($qryE);

                                        $oid=$rowBa['officeid'];

                                        if (is_array($sarray))
                                        {
                                            $sarray[$oid]=$sarray[$oid]+1;
                                        }
                                        else
                                        {
                                            $sarray=array($oid=>1);
                                        }
                                        $inscnt++;
                                    }
                                }
                            }
                        }
                    }
                }
                else
                {
                    $qryX	= "UPDATE lead_inc SET syscomment=':No Matching Zip Code:' WHERE lid='".$row['lid']."';";
                    $resX	= mssql_query($qryX);
                }
            }
            else
            {
                if (is_array($isdupe[1]))
                {
                    $sysmsg='';
                    foreach ($isdupe[1] as $v)
                    {
                        $sysmsg=$sysmsg.' : '.$v;
                    }

                    $qryX	= "UPDATE lead_inc SET syscomment='".$sysmsg."' WHERE lid='".$row['lid']."';";
                    $resX	= mssql_query($qryX);
                }
            }
        }
    }

    return $cid_ar;
    //if (isset($inscnt))
    //{
    //	echo $inscnt . ' Leads Processed'.chr(13);
    //}
}

function autosort_DIRECT()
{
    $recdate	=time();
    $cdate		=time();
    $secid      =1797;
    $cid_ar		=array();

    $qry		= "SELECT * FROM lead_inc WHERE sorted!=1 and source in (select statusid from leadstatuscodes where oid!=0);";
    $res		= mssql_query($qry);
    $nrow		= mssql_num_rows($res);

    $inscnt=0;
    if ($nrow > 0)
    {
        while($row=mssql_fetch_array($res))
        {
            $qry0	= "SELECT oid FROM leadstatuscodes WHERE statusid=".$row['source'].";";
            $res0	= mssql_query($qry0);
            $row0	= mssql_fetch_array($res0);

            $qry1	= "SELECT officeid,leadforward,am,active FROM offices WHERE officeid=".$row0['oid'].";";
            $res1	= mssql_query($qry1);
            $row1	= mssql_fetch_array($res1);

            if ($row1['active']==1)
            {
                if ($row1['leadforward']==0)
                {
                    $offid	=$row1['officeid'];
                    $am		=$row1['am'];
                }
                else
                {
                    $qry1a	= "SELECT officeid,leadforward,am,active FROM offices WHERE officeid=".$row1['leadforward'].";";
                    $res1a	= mssql_query($qry1a);
                    $row1a	= mssql_fetch_array($res1a);

                    $offid	=$row1a['officeid'];
                    $am		=$row1a['am'];
                }
            }
            else
            {
                $qry1z	= "SELECT officeid,leadforward,am,active FROM offices WHERE officeid=89;";
                $res1z	= mssql_query($qry1z);
                $row1z	= mssql_fetch_array($res1z);

                $offid	=$row1z['officeid'];
                $am		=$row1z['am'];
            }

            if (isset($offid) && $offid!=0)
            {
                $ndata=splitonspace($row['lname']);

                $qryCa = "SELECT MAX(custid) FROM cinfo WHERE officeid='".$offid."';";
                $resCa = mssql_query($qryCa);
                $rowCa = mssql_fetch_row($resCa);
                $nrowCa= mssql_num_rows($resCa);

                if ($nrowCa==0)
                {
                    $ncid=1;
                }
                else
                {
                    $ncid=$rowCa[0]+1;
                }

                $qryC	= "INSERT INTO cinfo ";
                $qryC .= "(added,updated,officeid,securityid,cfname,clname,";
                $qryC .= "caddr1,ccity,cstate,czip1,";
                $qryC .= "saddr1,scity,sstate,szip1,ssame,";
                $qryC .= "cconph,chome,cwork,cemail,mrktproc,recdate,custid,source,opt1,opt2,opt3,opt4) ";
                $qryC .= "VALUES (";
                $qryC .= "'".$row['submitted']."',getdate(),'".$offid."','".$am."','".$ndata[0]."','".replacequote($ndata[1])."',";
                $qryC .= "'".replacequote($row['addr'])."','".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."',";
                $qryC .= "'".replacequote($row['addr'])."','".replacequote($row['city'])."','".replacequote($row['state'])."','".$row['zip']."',1,";
                $qryC .= "'".$row['bphone']."',";

                if ($row['bphone']=="wk")
                {
                    $qryC .= "'','".$row['phone']."',";
                }
                else
                {
                    $qryC .= "'".$row['phone']."','',";
                }

                $qryC .= "'".replacequote($row['email'])."','".replacequote($row['comments'])."','".$recdate."','".$ncid."','".$row['source']."',";
                $qryC .= "'".$row['opt1']."','".$row['opt2']."','".$row['opt3']."','".$row['opt4']."'); SELECT @@IDENTITY as cidid;";
                $resC	= mssql_query($qryC);
                $rowC  = mssql_fetch_array($resC);

                $cid_ar[]=$rowC['cidid'];

                $qryD	= "UPDATE lead_inc SET sorted=1,proctype=1,tooffice='".$offid."',secid=".$secid." WHERE lid='".$row['lid']."';";
                $resD	= mssql_query($qryD);

                $qryE	= "INSERT INTO jest..EmailIntrosSent (cid) VALUES (".$rowC['cidid'].");";
                $resE	= mssql_query($qryE);

                $inscnt++;
            }
        }
    }

    //echo $inscnt . ' Leads Processed'.chr(13);
    return $cid_ar;
}


?>

