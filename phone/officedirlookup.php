<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <body>
        <table>
            <tr>
                <th colspan="2" align="left">Blue Haven Office Directory</th>
            </tr>    
<?php
    //error_reporting(E_ALL);
    include ('../connect_db.php');
    
    $qry  = "SELECT ";
	$qry .= "	 rtrim(o.name) as name,rtrim(o.phone) as phone,rtrim(o.ringto) as ringto ";
	$qry .= "FROM  ";
	$qry .= "	jest..offices as o ";
	$qry .= "WHERE ";
	$qry .= "	o.active=1 ";
    $qry .= "	and o.grouping=0 ";
	$qry .= "ORDER BY ";
	$qry .= "	o.name asc; ";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

    $dir_ar=array();
    while ($row = mssql_fetch_array($res))
    {
        $dir_ar[]=array(0=>$row['name'],1=>$row['phone']);
    }

    if (count($dir_ar > 0))
    {   
?>
        <tr>
            <td align="left">Office</td>
            <td align="left">Number</td>
        </tr>
<?php
        foreach ($dir_ar as $n => $v)
        {
?>
        <tr>
            <td><?php echo preg_replace('/&/i','',$v[0]) ?></td>
            <td><a href="tel://81<?php echo preg_replace('/-/i','',$v[1]); ?>"><?php echo $v[1] ?></a></td>
        </tr>      
<?php
        }
    }
    else
    {
?>
        <tr>
            <td>No Entries</td>
        </tr>
<?php
      
    }
?>
        </table>
    </body>
</html>
