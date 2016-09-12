<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <body>
        <table>
            <tr>
                <th colspan="2" align="left">Blue Haven Vendor Directory</th>
            </tr>    
<?php
    //error_reporting(E_ALL);
    include ('../connect_mas_db.php');
    
    $qry  = "SELECT ";
    $qry .= "	 distinct([PhoneNumber]) as phone,[VendorName] as name ";
    $qry .= "FROM [MAS_VCP].[dbo].[AP1_VendorMaster] ";
    $qry .= "where [PhoneNumber] is not null ";
    $qry .= "order by VendorName ";
	$res = mssql_query($qry);
	$nrow= mssql_num_rows($res);

    $dir_ar=array();
    while ($row = mssql_fetch_array($res))
    {
		$phcln=preg_replace('/ /i','',$row['phone']);
		$phcln=preg_replace('/-/i','',$phcln);
		$phcln=preg_replace('/\(/i','',$phcln);
		$phcln=preg_replace('/\)/i','',$phcln);
        $dir_ar[]=array(0=>$row['name'],1=>$row['phone'],2=>$phcln);
    }

    if (count($dir_ar > 0))
    {   
?>
        <tr>
            <td align="left">Vendor</td>
            <td align="left">Number</td>
        </tr>
<?php
        foreach ($dir_ar as $n => $v)
        {
?>
        <tr>
            <td><?php echo preg_replace('/&/i','',$v[0]) ?></td>
            <td><a href="tel://81<?php echo $v[2]; ?>"><?php echo $v[1] ?></a></td>
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
