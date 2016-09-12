<?php
//echo 'test';
session_start();
ini_set('display_errors','On');
error_reporting(E_ALL);

if (isset($_SESSION['securityid']) and (isset($_REQUEST['docid']) or isset($_REQUEST['filename']))) {
   //echo 'Request Error (1)';
   //exit;
   if (isset($_REQUEST['storetype'])) {
	  include ('../connect_db.php');
	  $fe=false;
	  
	  if ($_REQUEST['storetype']=='file') {
		 $qry = "	SELECT
					 F.filename,F.filetype,F.filestore,F.fsfilename,C.slevel,F.docid
				  FROM
					 jest..jestFileStore AS F
				  INNER JOIN
					 jest..jestFileStoreCategory AS C
				  ON
					 F.fscid=C.fscid
				  WHERE
					 F.docid =".$_REQUEST['docid'].";";
		 $res = mssql_query($qry);
		 $row = mssql_fetch_array($res);
		 $nrow= mssql_num_rows($res);
		 
		 $qry0 = "SELECT securityid,filestoreaccess from jest..security where securityid =".$_SESSION['securityid'].";";
		 $res0 = mssql_query($qry0);
		 $row0 = mssql_fetch_array($res0);
		 
		 if ($row0['filestoreaccess'] >= $row['slevel']) {
			$ff=FILESTORE.$row['filestore'].$row['fsfilename'];	  
			$fe=(file_exists(addslashes($ff)))?true:false;
			
			//echo $ff;
			
			$docid=$row['docid'];
			$fname=$row['filename'];
			$ftype=$row['filetype'];
		 }
	  }
	  elseif ($_REQUEST['storetype']=='file_fc') {
		 if (isset($_REQUEST['cid']) and $_REQUEST['cid']!=0) {
			$ff=FILESTORE.'\\CustomerEmailFiles\\'.$_REQUEST['cid'].'\\'.$_REQUEST['filename'];
			$fe=(file_exists(addslashes($ff)))?true:false;
			$docid=0;
			$fname=$_REQUEST['filename'];
			$ftype='';
			
			//echo $ff;
		 }
	  }
	  elseif ($_REQUEST['storetype']=='file_ex') {
		 $oid=0;
		 $ff=FILESTORE."\\".$oid."\\CustomerNotFoundEmailFiles\\".$_REQUEST['filename'];
		 $fe=(file_exists($ff))?true:false;
		 $docid=0;
		 $fname=$_REQUEST['filename'];
		 $ftype='';
			
		 //var_dump($fe);
		 echo $ff;
	  }
	  	  
	  if ($fe) {
		 $qry1 = "INSERT INTO jest_stats..ResourceAccessDates (did,rid,sid,ip,name) VALUES (".$docid.",1,".$_SESSION['securityid'].",'".getenv("REMOTE_ADDR")."','".$fname."');";
		 $res1 = mssql_query($qry1);
		 
		 header("Content-Description: File Transfer");
		 header("Content-Type: ".$ftype."");
		 header('Content-Disposition: attachment; filename='.basename($fname));
		 header('Content-Transfer-Encoding: binary');
		 header('Expires: 0');
		 header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		 header('Pragma: public');
		 ob_clean();
		 flush();
		 readfile(addslashes($ff));
		 exit;
	  }
	  else {
		 echo 'No File';
	  }
   }
}
