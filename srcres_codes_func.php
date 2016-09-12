<?php

function BaseMatrix()
{
    /*$qry = "select * from leadsourcecodes where active=1 order by name asc";
    $res  = mssql_query($qry);
    $nrow = mssql_num_rows($res);*/

    echo "<table width=\"750\">\n";
    echo "  <tr>\n";
    echo "      <td>\n";
	echo "          <table class=\"outer\" width=\"100%\" align=\"right\">\n";
	echo "   			<tr>\n";
    echo "      			<td class=\"gray\" align=\"left\"><b>Source/Result Codes</b></td>\n";
	echo "      			<td class=\"gray\" align=\"right\">\n";
    echo "                      <form method=\"post\">\n";
	echo "						    <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
    echo "						    <input type=\"hidden\" name=\"call\" value=\"srcrescodes\">\n";
    echo "						    <input type=\"hidden\" name=\"subq\" value=\"list\">\n";
    
    /*if ($nrow > 0)
    {
        echo "                          <select name=\"lsource\">\n";
        echo "                              <option value=\"NA\">All</option>\n";
        
        while ($row = mssql_fetch_array($res))
        {
            if (isset($_REQUEST['lsource']) && $_REQUEST['lsource']==$row['srcid'])
            {
                echo "                              <option value=\"".$row['srcid']."\" SELECTED>".$row['name']."</option>\n";
            }
            else
            {
                echo "                              <option value=\"".$row['srcid']."\">".$row['name']."</option>\n";
            }
        }
        
        echo "                          </select>\n";
    }*/
    
    echo "                          <select name=\"active\" onChange=\"this.form.submit();\">\n";
    echo "                              <option value=\"0\">All</option>\n";
    
    if (isset($_REQUEST['active']) && $_REQUEST['active']==2)
    {
        echo "                              <option value=\"2\" SELECTED>Source</option>\n";
        echo "                              <option value=\"1\">Result</option>\n";
    }
    elseif (isset($_REQUEST['active']) && $_REQUEST['active']==1)
    {
        echo "                              <option value=\"2\">Source</option>\n";
        echo "                              <option value=\"1\" SELECTED>Result</option>\n";
    }
    else
    {
        echo "                              <option value=\"2\">Source</option>\n";
        echo "                              <option value=\"1\">Result</option>\n";
    }
    
    echo "                          <input class=\"transnb\" type=\"image\" src=\"images/arrow_refresh_small.png\" title=\"Refresh List\">\n";
    echo "                          </select>\n";
    echo "                      </form>\n";
	echo "					</td>\n";
	echo "         		</form>\n";
    echo "          </table>\n";
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "      <td>\n";
    
    if (isset($_REQUEST['subq']) && $_REQUEST['subq']=='list')
    {
        ListCodes();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='view')
    {
        ViewCode();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='add')
    {
        AddCode();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='edit')
    {
        EditCode();
    }
    elseif (isset($_REQUEST['subq']) && $_REQUEST['subq']=='save')
    {
        SaveCode();
    }
    
    echo "      </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
}

function ListCodes()
{
    ini_set('display_errors','On');
    error_reporting(E_ALL);
    
    $emtemp_ar=array();
    
    $qry  = "
            select
                L1.id,
                L1.statusid,
                L1.name as L1name,
                L1.active as L1active,
                L1.access as L1access,
                L1.lsource,
                L1.oid as L1oid,
                L2.lid,
                L2.srcid,
                L2.name as L2name,
                L2.active as L2active,
                L2.oid as L2oid,
                (select name from offices where officeid=L1.oid) as oname,
                L1.etid,
                (select name from EmailTemplate where etid=L1.etid) as etidname
            from
                leadstatuscodes as L1
            inner join
                leadsourcecodes as L2
            on L1.lsource=L2.srcid
            ";
    
    if ($_REQUEST['active']!=0)
    {
        $qry .= "
            where
                L1.active=".$_REQUEST['active']." ";
    }
    
    $qry .= " order by L2.name,L1.name asc;";
    $res  = mssql_query($qry);
    $nrow = mssql_num_rows($res);
    
    //echo $qry.'<br>';
    
    $qry1 = "select etid,name from EmailTemplate where oid=0 or oid=".$_SESSION['officeid']." order by name;";
    $res1 = mssql_query($qry1);
    
    while ($row1 = mssql_fetch_array($res1))
    {
        $emtemp_ar[$row1['etid']]=$row1['name'];
    }
    
    if ($nrow > 0)
    {
        echo "          <table class=\"outer\" width=\"100%\" align=\"right\">\n";
        echo "   			<tr>\n";
        echo "      			<td class=\"ltgray_und\"></td>\n";
        echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Name</b></td>\n";
        echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Category</b></td>\n";
        echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Assigned Office</b></td>\n";
        echo "      			<td class=\"ltgray_und\" align=\"center\"><b>Type</b></td>\n";
        echo "      			<td class=\"ltgray_und\" align=\"center\"><b>Code</b></td>\n";
        echo "      			<td class=\"ltgray_und\" align=\"center\"><b>Active</b></td>\n";
        echo "      			<td class=\"ltgray_und\" align=\"center\"><b>Access</b></td>\n";
        echo "      			<td class=\"ltgray_und\" align=\"left\"><b>Email Template</b></td>\n";
        //echo "      			<td class=\"ltgray_und\"></td>\n";
        echo "   			</tr>\n";
    
        $srcnt=1;
        while ($row = mssql_fetch_array($res))
        {
            if ($row['statusid']!=1)
            {
                if ($srcnt%2)
                {
                    $tbg='wh_und';
                }
                else
                {
                    $tbg='ltgray_und';
                }
                
                echo "   			<tr>\n";
                echo "      			<td class=\"".$tbg."\" align=\"right\">".$srcnt++.".</td>\n";
                echo "      			<td class=\"".$tbg."\" align=\"left\">\n";
                
                if ($row['statusid']==0)
                {
                    echo 'bluehaven.com';
                }
                else
                {
                    echo $row['L1name'];
                }
                
                echo "                  </td>\n";
                echo "      			<td class=\"".$tbg."\" align=\"left\">".$row['L2name']."</td>\n";
                echo "      			<td class=\"".$tbg."\" align=\"left\">".$row['oname']."</td>\n";
                echo "      			<td class=\"".$tbg."\" align=\"center\">\n";
                
                if ($row['L1active']==2)
                {
                    echo 'Source';
                }
                elseif ($row['L1active']==1)
                {
                    echo 'Result';
                }
                
                echo "                  </td>\n";
                echo "      			<td class=\"".$tbg."\" align=\"center\">".$row['statusid']."</td>\n";
                echo "      			<td class=\"".$tbg."\" align=\"center\">".$row['L1active']."</td>\n";
                echo "      			<td class=\"".$tbg."\" align=\"center\">".$row['L1access']."</td>\n";
                echo "      			<td class=\"".$tbg."\" align=\"left\">\n";
                
                if ($row['etid'] != 0)
                {
                    echo "                      <table width=\"100%\">\n";
                    echo "   		            	<tr>\n";
                    echo "      	            		<td>".$row['etidname']."</td>\n";
                    echo "      	            		<td>\n";
                    echo "                                  <form name=\"viewemailtemplate\" method=\"post\">\n";
                    echo "						            <input type=\"hidden\" name=\"action\" value=\"maint\">\n";
                    echo "						            <input type=\"hidden\" name=\"call\" value=\"emailtemplate\">\n";
                    echo "						            <input type=\"hidden\" name=\"subq\" value=\"view\">\n";
                    echo "						            <input type=\"hidden\" name=\"etid\" value=\"".$row['etid']."\">\n";
                    echo "                                  <input class=\"transnb\" type=\"image\" src=\"images/arrow_right.png\" title=\"View Email Template\">\n";
                    echo "                                  </form>\n";
                    echo "                              </td>\n";
                    echo "   			            </tr>\n";
                    echo "                      </table>\n";
                }
               
                echo "                  </td>\n";
                echo "   			</tr>\n";
            }
        }
        
        echo "          </table>\n";
    }
}

?>