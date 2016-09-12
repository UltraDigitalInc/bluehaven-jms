<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <body>
        <table>
            <tr>
                <th colspan="3" align="left">Zip Code Matrix Directory</th>
            </tr>    
<?php
    
	if (isset($_REQUEST['a']) && $_REQUEST['a']=='start')
	{
		?>
		<tr>
            <td colspan="2" align="left"><form action="http://jms.bluehaven.local/phone/zipcodelookup.php?a=query" method="post">Zip Code Input <input type="hidden" name="a" value="query"/><input type="text" name="zc" length="5" maxlength="5" /><input type="submit" value="Query" /></form></td>
        </tr>
		<?php
	}
	elseif (isset($_REQUEST['a']) && $_REQUEST['a']=='query')
	{
		if (isset($_REQUEST['zc']) && strlen($_REQUEST['zc']) == 5)
		{
			include ('../connect_db.php');
			
			$qry  = "SELECT ";
			$qry .= "	 o.ringto as phone,o.name as name,o.zip as ozc,z.ozip as zozc,z.czip as zczc ";
			$qry .= "FROM jest..zip_to_zip as z ";
			$qry .= "INNER JOIN jest..offices as o ";
			$qry .= "ON z.ozip=o.zip ";
			$qry .= "where z.czip = '".$_REQUEST['zc']."' ";
			$res = mssql_query($qry);
			$nrow= mssql_num_rows($res);
		
			$dir_ar=array();
			while ($row = mssql_fetch_array($res))
			{
				$dir_ar[]=array(0=>$row['name'],1=>$row['phone'],2=>$row['ozc'],3=>$row['zozc'],4=>$row['zczc']);
			}
		
			if (count($dir_ar > 0))
			{   
?>
        <tr>
            <td align="left">Office</td>
            <td align="left">Ringto</td>
			<td align="left">Cust Zip</td>
        </tr>
<?php
			//echo $qry;
				foreach ($dir_ar as $n => $v)
				{
				
?>
        
		<tr>
            <td align="left"><?php echo $v[0] ?></td>
            <td align="left"><?php echo $v[1] ?></td>
			<td align="left"><?php echo $v[4] ?></td>
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
		}
		else
		{
?>
        <tr>
            <td>Improper Zip Code</td>
        </tr>			
<?php
		}
	}
?>
        </table>
    </body>
</html>