<?php
ini_set('display_errors','On');
error_reporting(E_ALL|E_STRICT);

/*
echo '<pre>';
print_r($_REQUEST);
echo '</pre>';
exit;
*/
if (isset($_REQUEST['paction']) or !empty($_REQUEST['paction']))
{
	if (isset($_REQUEST['oid']) and $_REQUEST['oid'] != 0)
	{		
		$nrow=0;
		
		mssql_connect('CORP-DB02','sa','date1995') or trigger_error('Could not connect to MSSQL database', E_USER_ERROR);
		mssql_select_db('jest') or die("Table unavailable");
		
		$row0 = mssql_fetch_array(mssql_query("select officeid,pb_code from jest..offices where officeid=".(int) $_REQUEST['oid']));
		
		$base_url='http://'.$_SERVER['SERVER_NAME'].'/qb/bhsoap/QB_Process_PID.php?qact='.$_REQUEST['paction'].'&showout=1&oid='.(int) $_REQUEST['oid'];
		
		if ($_REQUEST['paction']=='ItemServiceAdd')
		{
			$qry1 =	"SELECT id as iid FROM jest..[".trim($row0['pb_code'])."accpbook] where officeid=".$row0['officeid']." and ListID = '0';";
			$res1 = mssql_query($qry1);
			$nrow = mssql_num_rows($res1);
		}
		elseif ($_REQUEST['paction']=='ItemInventoryAdd' or $_REQUEST['paction']=='ItemNonInventoryAdd')
		{
			$qry1 =	"SELECT invid as iid FROM [".trim($row0['pb_code'])."inventory] where officeid=".$row0['officeid']." and matid!=0 and ListID = '0';";
			$res1 = mssql_query($qry1);
			$nrow = mssql_num_rows($res1);
		}
		/*
		elseif ($_REQUEST['paction']=='ItemNonInventoryAdd')
		{
			$qry1 =	"SELECT invid as iid FROM jest..[".trim($row0['pb_code'])."inventory] where officeid=".$row0['officeid']." and phsid=".$eqpphs." and matid=0 and ListID = '0';";
			$res1 = mssql_query($qry1);
			$nrow = mssql_num_rows($res1);
		}
		*/
		
		//echo $qry1.'<br>';
		
		if ($nrow > 0)
		{
			$url_str='';
			
			while ($row1 = mssql_fetch_array($res1))
			{
				$url_str=$url_str.'&pid[]='.$row1['iid'];
			}

			//echo $base_url.$url_str.'<br>';
			header("Location: ".$base_url.$url_str);
		}
		else
		{
			echo 'No PB Items '.__LINE__;
		}
	}
	else
	{
		echo 'Incomplete Request '.__LINE__;
	}
}
else
{
	echo 'Incomplete Request '.__LINE__;
}

?>