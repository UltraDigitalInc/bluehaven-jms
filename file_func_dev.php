<?php

function FileBaseMatrix()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	//display_array($_REQUEST);
	
	if (isset($_SESSION['call']) && $_SESSION['call']=="list_file_OFF")
	{
		list_file_OFF();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="upload_file_OFF")
	{
		upload_file_OFF();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="add_folder_OFF")
	{
		add_folder_OFF();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="deactivate_folder_OFF")
	{
		deactivate_folder_OFF();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="delete_folder_OFF")
	{
		delete_folder_OFF();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="delete_file_OFF")
	{
		delete_file_OFF();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="undelete_file_OFF")
	{
		undelete_file_OFF();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="deactivate_file_OFF")
	{
		deactivate_file_OFF();
	}
	elseif(isset($_SESSION['call']) && $_SESSION['call']=="change_folder_OFF")
	{
		change_folder_OFF();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="list_file_ENT")
	{
		list_file_ENT();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="upload_file")
	{
		upload_file();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="delete_file")
	{
		delete_file();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="undelete_file")
	{
		undelete_file();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="list_file_CID")
	{
		list_file_CID();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="upload_file_CID")
	{
		upload_file_CID();
	}
	elseif(isset($_SESSION['call']) && $_SESSION['call']=="change_cat_CID")
	{
		change_cat_CID();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="delete_file_CID")
	{
		delete_file_CID();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="undelete_file_CID")
	{
		undelete_file_CID();
	}
	elseif (isset($_SESSION['call']) && $_SESSION['call']=="deactivate_file_CID")
	{
		deactivate_file_CID();
	}
	
	ini_set('display_errors','Off');
}

function test_file($f)
{
	if ($f == 1)
	{
		$out=array($f,'File Upload Error: File too Large (INI Directive)');
	}
	elseif ($f == 2)
	{
		$out=array($f,'File Upload Error: File too Large (Form Directive)');
	}
	elseif ($f == 3)
	{
		$out=array($f,'File Upload Error: Partial Upload');
	}
	elseif ($f == 4)
	{
		$out=array($f,'File Upload Error: No File');
	}
	elseif ($f == 6)
	{
		$out=array($f,'File Upload Error: TMP Dir Missing');
	}
	elseif ($f == 7)
	{
		$out=array($f,'File Upload Error: Failed Disk Write');
	}
	else
	{
		$out=array($f,'');
	}
	
	return $out;
}

function upload_file_CID()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	//$inv_type_ar=array('application/octet-stream');
	$img_type_ar=array('image/jpeg','image/gif','image/pjpeg','image/png','image/x-png');
	$app_type_ar=array('application/pdf');
	
	$prc_cnt=0;
	$err_cnt=0;
	$prc_txt='';
	$err_txt='Error Detail:<br>';
	
	if (isset($_REQUEST['cid']) && $_REQUEST['cid']!=0)
	{
		$cid		= $_REQUEST['cid'];
	}
	else
	{
		echo 'Transition Error: Invalid CID<br>';
		exit;
	}
	
	if (isset($_SESSION['officeid']) && $_SESSION['officeid'] != 0)
	{
		$oid		= $_SESSION['officeid'];
	}
	else
	{
		$oid		= 0;
	}

	if (isset($_FILES) && count($_FILES['userfile']) > 0)
	{
		foreach ($_FILES['userfile']['error'] as $n => $v)
		{
			if ($v==0)
			{
				if (in_array($_FILES['userfile']['type'][$n],$app_type_ar) || in_array($_FILES['userfile']['type'][$n],$img_type_ar))
				{				
					$qryA = "select docid from jest..jestFileStore where oid=".$oid." and cid=".$cid." and active=1 and filename='".trim($_FILES['userfile']['name'][$n])."';";
					$resA = mssql_query($qryA);
					$nrowA= mssql_num_rows($resA);
					
					if ($nrowA==0)
					{
						$fstore=storefile_FS($_FILES['userfile']['name'][$n],$_FILES['userfile']['tmp_name'][$n],$_FILES['userfile']['size'][$n],$_FILES['userfile']['type'][$n],$cid,$oid,$_REQUEST['fscid'],$_REQUEST['uid']);

						if (!$fstore)
						{
							$err_cnt++;
							$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: Storage Error<br>Result: No Action Taken<p />";	
						}
					}
					else
					{
						$err_cnt++;
						$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: File Exists in Customer File Cabinet<br>Result: No Action Taken<p />";	
					}
				}
				else
				{
					$err_cnt++;
					$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: Invalid MIME Type (".$_FILES['userfile']['type'][$n].")<br> File Upload must be PDF, JPG, GIF, or PNG format.<br>Result: No Action Taken<p />";
					//echo $err_txt;
				}
			}
			elseif ($v!=4)
			{
				$terr=test_file($v);
				$err_cnt++;
				$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: ".$terr[1]."<br>Result: No Action Taken<p />";
				//echo $terr[1];
			}
		}
		
		if ($err_cnt > 0)
		{
			file_error($err_txt);
		}
	}
	else
	{
		echo 'No File detected<br>';
	}
	
	list_file_CID();
}

function file_error($etxt)
{
	echo "<table width=\"700px\">\n";
	echo "	<tr>\n";
	echo "		<td>\n";
	echo "<div id=\"errortext\" title=\"File Upload Error\">\n";
	echo "	<div class=\"ui-widget\">\n";
	echo "		<div class=\"ui-state-error ui-corner-all\" style=\"padding: 0 .7em;\">\n";
	echo "			<p>\n";
	echo "				<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span>\n";
	
	echo $etxt;
	
	echo "				</span>\n";
	echo "			</p>\n";
	echo "		</div>\n";
	echo "	</div>\n";
	echo "</div>\n";
	echo "		</td>\n";
	echo "	</tr>\n";
	echo "</table>\n";
}

function upload_file()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$inv_type_ar=array('application/octet-stream');
	$prc_cnt=0;
	$err_cnt=0;
	$prc_txt='';
	$err_txt='Error Detail:<br>';
	
	if (isset($_REQUEST['cid']) && $_REQUEST['cid']!=0)
	{
		$cid		= $_REQUEST['cid'];
	}
	else
	{
		$cid		= 0;
	}
	
	if (isset($_REQUEST['foid']) && $_REQUEST['foid']!=0)
	{
		$oid		= $_REQUEST['foid'];
	}
	else
	{
		$oid		= 0;
	}

	if (isset($_FILES) && count($_FILES['userfile']) > 0)
	{
		foreach ($_FILES['userfile']['error'] as $n => $v)
		{
			if ($v==0)
			{
				if (!in_array($_FILES['userfile']['type'][$n],$inv_type_ar))
				{
					$fstore=storefile_FS($_FILES['userfile']['name'][$n],$_FILES['userfile']['tmp_name'][$n],$_FILES['userfile']['size'][$n],$_FILES['userfile']['type'][$n],$cid,$oid,$_REQUEST['fscid'],$_REQUEST['uid']);
					
					if ($fstore)
					{
						$prc_cnt++;
						$prc_txt=$prc_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Result: File Stored<p />";
					}
					else
					{
						$err_cnt++;
						$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: Storage Error<br>Result: No Action Taken<p />";	
					}
				}
				else
				{
					$err_cnt++;
					$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: Invalid MIME Type (".$_FILES['userfile']['type'][$n].")<br>Result: No Action Taken<p />";
					//echo $err_txt;
				}
			}
			elseif ($v!=4)
			{
				$terr=test_file($v);
				$err_cnt++;
				$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: ".$terr[1]."<br>Result: No Action Taken<p />";
				//echo $terr[1];
			}
		}
		
		if ($prc_cnt > 0)
		{
			//echo "<div id=\"resulttext\" title=\"File Upload Result\">\n";
			//echo $prc_txt;
			//echo "</div>\n";
		}
		
		if ($err_cnt > 0)
		{
			/*
			echo "<div id=\"errortext\" title=\"File Upload Error\">\n";
			echo "	<div class=\"ui-widget\">\n";
			echo "		<div class=\"ui-state-error ui-corner-all\" style=\"padding: 0 .7em;\">\n";
			echo "			<p>\n";
			echo "				<span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span>\n";
			*/

			//echo $err_txt;
			
			/*
			echo "				</span>\n";
			echo "			</p>\n";
			echo "		</div>\n";
			echo "	</div>\n";
			echo "</div>\n";
			*/
		}
	}
	else
	{
		echo 'No File detected<br>';
	}
	
	list_file();
}

function upload_file_OFF()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$inv_type_ar=array('application/octet-stream');
	$prc_cnt=0;
	$err_cnt=0;
	$prc_txt='';
	$err_txt='Error Detail:<br>';
	
	if (isset($_REQUEST['cid']) && $_REQUEST['cid']!=0)
	{
		$cid	= $_REQUEST['cid'];
	}
	else
	{
		$cid	= 0;
	}
	
	if (isset($_REQUEST['foid']) && $_REQUEST['foid']!=0)
	{
		$oid		= $_REQUEST['foid'];
	}
	else
	{
		$oid		= 0;
	}

	if (isset($_FILES) && count($_FILES['userfile']) > 0)
	{
		foreach ($_FILES['userfile']['error'] as $n => $v)
		{
			if ($v==0)
			{
				if (!in_array($_FILES['userfile']['type'][$n],$inv_type_ar))
				{
					$qryA = "select docid from jest..jestFileStore where oid=".$oid." and filename='".trim($_FILES['userfile']['name'][$n])."' and active=1;";
					$resA = mssql_query($qryA);
					$nrowA= mssql_num_rows($resA);
					
					if ($nrowA==0)
					{
						$fstore=storefile_FS($_FILES['userfile']['name'][$n],$_FILES['userfile']['tmp_name'][$n],$_FILES['userfile']['size'][$n],$_FILES['userfile']['type'][$n],$cid,$oid,$_REQUEST['fscid'],$_REQUEST['uid']);

						if (!$fstore)
						{
							$err_cnt++;
							$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: Storage Error<br>Result: No Action Taken<p />";	
						}
					}
					else
					{
						$err_cnt++;
						$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: File Exists in Office File Cabinet<br>Result: No Action Taken<p />";	
					}
				}
				else
				{
					$err_cnt++;
					$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: Invalid MIME Type (".$_FILES['userfile']['type'][$n].")<br>Result: No Action Taken<p />";
					//echo $err_txt;
				}
			}
			elseif ($v!=4)
			{
				$terr=test_file($v);
				$err_cnt++;
				$err_txt=$err_txt."File: ".$_FILES['userfile']['name'][$n]."<br>Error: ".$terr[1]."<br>Result: No Action Taken<p />";
				//echo $terr[1];
			}
		}
		
		if ($err_cnt > 0)
		{
			file_error($err_txt);
		}
	}
	else
	{
		echo 'No File detected<br>';
	}
	
	list_file_OFF();
}

function storefile_FS($fileName,$tempName,$fileSize,$fileType,$rcid,$roid,$fscid,$ruid)
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$qryA = "select docid from jest..jestFileStore where oid=".$roid." and filename='".$fileName."' and uid='".$ruid."';";
	$resA = mssql_query($qryA);
	$nrowA= mssql_num_rows($resA);
	
	if (isset($roid) && $nrowA == 0)
	{	
		$permPath	= FILESTORE.'\\'.$roid.'\\';
		$permStore	= $permPath . $ruid;
		
		if (!file_exists($permPath))
		{
			mkdir($permPath);
		}
	
		if (move_uploaded_file($tempName, $permStore))
		{
			$sPath='\\'.$roid.'\\';
			$qry = "INSERT INTO jest..jestFileStore (oid,sid,cid,aid,filename,fsfilename,filetype,filesize,filestore,fscid,uid,active,udate) VALUES (".$roid.",".$_SESSION['securityid'].",".$rcid.",0,'".$fileName."','".$ruid."','".$fileType."',".$fileSize.",'".$sPath."',".$fscid.",'".$ruid."',1,getdate());";
			$res = mssql_query($qry);
			
			//echo $qry.'<br>';
			
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

function stubfileimages($f)
{
	$out = '';

	if (
		substr($f,0,5)=='image'
		//$f == 'image/jpeg' ||
		//$f == 'image/pjpeg' ||
		//$f == 'image/gif' ||
		//$f == 'image/x-png' ||
		//$f == 'image/png'
		)
	{
		$out="<img src=\"images/page_white_camera.png\">";
	}
	elseif ($f == 'plain/text')
	{
		$out = "<img src=\"images/page_white_text.png\">";
	}
	elseif ($f == 'application/pdf')
	{
		$out = "<img src=\"images/page_white_acrobat.png\">";
	}
	elseif (
			$f == 'application/vnd.ms-excel' ||
			$f == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			)
	{
		$out="<img src=\"images/page_white_excel.png\">";
	}
	elseif (
			$f == 'application/vnd.ms-word' ||
			$f == 'application/msword' ||
			$f == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
			)
	{
		$out = "<img src=\"images/page_white_word.png\">";
	}
	else
	{
		$out = "<img src=\"images/page_white.png\">";
	}
	
	return $out;
}

function stubfileimages_tree($f)
{
	$out = '';

	if (
		substr($f,0,5)=='image'
		//$f == 'image/jpeg' ||
		//$f == 'image/pjpeg' ||
		//$f == 'image/gif' ||
		//$f == 'image/x-png' ||
		//$f == 'image/png'
		)
	{
		$out="<img src=\"images/page_white_camera.png\">";
	}
	elseif ($f == 'plain/text')
	{
		$out = "<img src=\"images/page_white_text.png\">";
	}
	elseif ($f == 'application/pdf')
	{
		$out = "<img src=\"images/page_white_acrobat.png\">";
	}
	elseif (
			$f == 'application/vnd.ms-excel' ||
			$f == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
			)
	{
		$out="<img src=\"images/page_white_excel.png\">";
	}
	elseif (
			$f == 'application/vnd.ms-word' ||
			$f == 'application/msword' ||
			$f == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
			)
	{
		$out = "<img src=\"images/page_white_word.png\">";
	}
	else
	{
		$out = "<img src=\"images/page_white.png\">";
	}
	
	return $out;
}

function delete_file()
{
	if (isset($_REQUEST['docid']) && $_REQUEST['docid'] != 0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT docid from jest..jestFileStore where docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "UPDATE jest..jestFileStore SET active=0,udate=getdate() WHERE docid=".$_REQUEST['docid'].";";
			$res1 = mssql_query($qry1);
		}
	}
	
	list_file();
}

function delete_file_CID()
{
	if (isset($_REQUEST['docid']) && $_REQUEST['docid'] != 0)
	{
		$qry0 = "SELECT docid,filestore,fsfilename,filename from jest..jestFileStore where docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$row0 = mssql_fetch_array($res0);
			$permFile=FILESTORE.$row0['filestore'].$row0['fsfilename'];
			
			if (file_exists($permFile))
			{
				if (unlink($permFile))
				{
					$qry1 = "DELETE FROM jest..jestFileStore WHERE docid=".$_REQUEST['docid'].";";
					$res1 = mssql_query($qry1);
				}
			}
		}
	}
	
	list_file_CID();
}

function delete_file_OFF()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	//echo FILESTORE.'<br>';
	$err_cnt=0;
	$err_txt='';
	
	if (isset($_REQUEST['docid']) && $_REQUEST['docid'] != 0)
	{
		//echo '1<br>';
		$qry0 = "SELECT docid,filestore,fsfilename from jest..jestFileStore where docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			//$qry0 = "update jest..jestFileStore set active=0,updated=getdate() where docid=".$_REQUEST['docid'].";";
			//$res0 = mssql_query($qry0);
			//$nrow0= mssql_num_rows($res0);
			//echo '2<br>';
			$row0 = mssql_fetch_array($res0);
			$permFile=FILESTORE.$row0['filestore'].$row0['fsfilename'];
			
			//echo $permFile.'<br>';
			if (file_exists($permFile))
			{
				//echo '3<BR>';
				if (unlink($permFile))
				{
					//echo '4<BR>';
					$qry1 = "DELETE FROM jest..jestFileStore WHERE docid=".$_REQUEST['docid'].";";
					$res1 = mssql_query($qry1);
					
					//echo $qry1.'<br>';
				}
				else
				{
					$err_cnt++;
					$err_txt='Error. Delete Failed, contact Management if this Error persists.<br>';
				}
			}
			else
			{
				$qry1 = "DELETE FROM jest..jestFileStore WHERE docid=".$_REQUEST['docid'].";";
				$res1 = mssql_query($qry1);
				
				//$err_cnt++;
				//$err_txt='Error. DocID not Found in File System.<br>';
			}
		}
		else
		{
			$err_cnt++;
			$err_txt='Error. DocID not Found in DB.<br>';
		}
	}
	else
	{
		$err_cnt++;
		$err_txt='Error. DocID not Set or Incorrect.<br>';
	}
	
	if ($err_cnt > 0)
	{
		file_error($err_txt);
	}
	
	list_file_OFF();
}

function deactivate_file_CID()
{
	if (isset($_REQUEST['docid']) && $_REQUEST['docid']!=0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT docid,filestore,fsfilename,filename from jest..jestFileStore where docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "UPDATE jest..jestFileStore SET active=0,udate=getdate() WHERE docid=".$_REQUEST['docid'].";";
			$res1 = mssql_query($qry1);
		}
	}
	
	list_file_CID();
}

function deactivate_file_OFF()
{
	if (isset($_REQUEST['docid']) && $_REQUEST['docid']!=0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT docid from jest..jestFileStore where docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "UPDATE jest..jestFileStore SET active=0,udate=getdate() WHERE docid=".$_REQUEST['docid'].";";
			$res1 = mssql_query($qry1);
		}
	}
	
	list_file_OFF();
}

function undelete_file()
{
	if (isset($_REQUEST['docid']) && $_REQUEST['docid']!=0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT docid,active from jest..jestFileStore where docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "UPDATE jest..jestFileStore SET active=1,udate=getdate() WHERE docid=".$_REQUEST['docid'].";";
			$res1 = mssql_query($qry1);
		}
	}
	
	list_file();
}

function change_cat_CID()
{
	if (isset($_REQUEST['docid']) && $_REQUEST['docid']!=0 && isset($_REQUEST['fscid']) && $_REQUEST['fscid']!=0)
	{
		echo 'Changing Category...';
		$qry0 = "SELECT docid FROM jest..jestFileStore WHERE docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "UPDATE jest..jestFileStore SET fscid=".$_REQUEST['fscid'].",udate=getdate() WHERE docid=".$_REQUEST['docid'].";";
			$res1 = mssql_query($qry1);
		}
	}
	
	list_file_CID();
}

function change_folder_OFF()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	echo 'Changing Folder<br>';
	
	if (isset($_REQUEST['docid']) && $_REQUEST['docid']!=0 && isset($_REQUEST['fscid']) && $_REQUEST['fscid']!=0)
	{
		$qry0 = "SELECT docid FROM jest..jestFileStore WHERE docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "UPDATE jest..jestFileStore SET fscid=".$_REQUEST['fscid'].",udate=getdate() WHERE docid=".$_REQUEST['docid'].";";
			$res1 = mssql_query($qry1);
			
			echo 'Folder Changed<br>';
		}
	}
	
	list_file_OFF();
}

function undelete_file_CID()
{
	if (isset($_REQUEST['docid']) && $_REQUEST['docid']!=0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT docid,active from jest..jestFileStore where docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "UPDATE jest..jestFileStore SET active=1,udate=getdate() WHERE docid=".$_REQUEST['docid'].";";
			$res1 = mssql_query($qry1);
		}
	}
	
	list_file_CID();
}

function undelete_file_OFF()
{
	if (isset($_REQUEST['docid']) && $_REQUEST['docid']!=0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT docid,active from jest..jestFileStore where docid=".$_REQUEST['docid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$qry1 = "UPDATE jest..jestFileStore SET active=1,udate=getdate() WHERE docid=".$_REQUEST['docid'].";";
			$res1 = mssql_query($qry1);
		}
	}
	
	list_file_OFF();
}

function select_file($ftype,$fsaccess)
{
	//echo __FUNCTION__.'<br>';
	
	$qryA = "SELECT FILEUPLOADS from master..bhest_config;";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	$qryB = "SELECT min(slevel) as slevel from jest..jestFileStoreCategory where fctype = ".$ftype." and slevel != 0 and active = 1;";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
	
	$qry1 = "select officeid,name,fslimit from offices where officeid = ".$_SESSION['officeid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);

	if (isset($rowA['FILEUPLOADS']) && $rowA['FILEUPLOADS'] == 0)
	{
		echo '<b>File Uploads disabled</b><br>';
	}
	elseif ($rowB['slevel'] > $fsaccess)
	{
		echo '<b>File Uploads disabled<br><br>Access Level below minimum</b><br>';
	}
	else
	{
		$admin_offs=array(89);	
		
		$qry0 = "SELECT fscid,fscatname from jest..jestFileStoreCategory where fctype = ".$ftype." and slevel <= ".$fsaccess." and active = 1 order by fscatname asc;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		$qry2 = "select isnull(sum(filesize),0) as cfilesize from jest..jestFileStore where oid = ".$_SESSION['officeid']." and fscid = 0;";
		$res2 = mssql_query($qry2);
		$row2 = mssql_fetch_array($res2);
		$nrow2= mssql_num_rows($res2);
		
		$fslimit	=$row1['fslimit'] * 1000000;
		$fscurrent	=$row2['cfilesize'];
		$fsremain	=$fslimit - $fscurrent;
		
		if ($fscurrent < 1)
		{
			$fsperc	=0;
		}
		else
		{
			$fsperc	=$fscurrent / $fslimit;
		}
		
		if (in_array($_SESSION['officeid'],$admin_offs))
		{
			$qry2a = "select isnull(sum(filesize),0) as cfilesize from jest..jestFileStore;";
			$res2a = mssql_query($qry2a);
			$row2a = mssql_fetch_array($res2a);
			$nrow2a= mssql_num_rows($res2a);
			
			$fslimitENT		=10000 * 1000000;
			$fscurrentENT	=$row2a['cfilesize'];
			$fsremainENT	=$fslimitENT - $fscurrentENT;
			
			if ($fscurrentENT==0)
			{
				$fspercENT	=0;
			}
			else
			{
				$fspercENT	=$fscurrentENT / $fslimitENT;
			}
		}
		
		$uid		= md5(session_id().'.'.time().'.'.$_SESSION['securityid']);
		$num_files	= 1;
		
		echo "<div class=\"noPrint\">\n";
		echo "	<table width=\"800px\">\n";
		echo "		<tr>\n";
		echo "			<td>\n";
		echo "			<div id=\"fileselect\">\n";
		echo "				<ul>\n";
		echo "					<li><a href=\"#FSelect\">File Upload</a></li>\n";
		echo "					<li><a href=\"#SDetail\">Storage Detail</a></li>\n";
		echo "				</ul>\n";
		echo "				<div id=\"FSelect\">\n";
		echo "					<table width=\"575px\">\n";
		echo "						<tr>\n";
		echo "							<td align=\"right\">\n";
		echo "								<form id=\"fileupfrm\" enctype=\"multipart/form-data\" method=\"post\">\n";
		echo "								<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		echo "								<input type=\"hidden\" name=\"call\" value=\"upload_file\">\n";
		
		if (isset($_REQUEST['subq']) && !empty($_REQUEST['subq']))
		{
			echo "								<input type=\"hidden\" name=\"subq\" value=\"".$_REQUEST['subq']."\">\n";
		}
		
		echo "								<input type=\"hidden\" name=\"storetype\" value=\"file\">\n";
		echo "								<input type=\"hidden\" name=\"fsremain\" value=\"".$fsremain."\">\n";
		echo "								<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
		echo "								<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10000000\" />\n";
		echo "								<table>\n";
		echo "									<tr>\n";
		echo "										<td><img src=\"images/pixel.gif\"></td>\n";
		echo "										<td align=\"left\"><b>Owner</b></td>\n";
		echo "										<td align=\"left\"><b>Category</b></td>\n";
		echo "										<td align=\"left\"><b>File</b></td>\n";
		echo "										<td><img src=\"images/pixel.gif\"></td>\n";
		echo "									</tr>\n";
		echo "									<tr>\n";
		echo "										<td align=\"right\"><b>File Upload</b></td>\n";
		echo "										<td>\n";
		echo "											<select name=\"foid\" id=\"foid\">\n";
		echo "												<option value=\"".$row1['officeid']."\">".$row1['name']."</option>\n";
		
		if (in_array($_SESSION['officeid'],$admin_offs))
		{
			echo "												<option value=\"0\">Blue Haven</option>\n";
		}
		
		echo "											</select>\n";
		echo "										</td>\n";
		echo "										<td>\n";
		echo "											<select name=\"fscid\" id=\"fscid\">\n";
		echo "												<option value=\"0\">Select...</option>\n";
		
		while ($row0 = mssql_fetch_array($res0))
		{
			echo "											<option value=\"".$row0['fscid']."\">".$row0['fscatname']."</option>\n";
		}
		
		echo "											</select>\n";
		echo "										</td>\n";
		echo "										<td>\n";
		echo "											<input class=\"buttondkgraypnl\" type=\"file\" name=\"userfile[]\" id=\"userfile\">\n";
		echo "										</td>\n";
		echo "										<td align=\"right\">\n";
		echo "											<input class=\"transnb\" type=\"image\" src=\"images/folder_go.png\" onClick=\"return FileUploadCheck('fscid','userfile','Category','File');\" alt=\"Upload File\">\n";
		echo "										</td>\n";
		echo "									</tr>\n";
		echo "								</table>\n";
		echo "								</form>\n";
		echo "							</td>\n";
		echo "						</tr>\n";
		echo "					</table>\n";
		echo "				</div>\n";
		echo "				<div id=\"SDetail\">\n";
		echo "					<div id=\"storageselect\">\n";
		echo "						<ul>\n";
		echo "							<li><a href=\"#FOffice\">Office</a></li>\n";
		
		if (in_array($_SESSION['officeid'],$admin_offs))
		{
			echo "							<li><a href=\"#FEnterprise\">Enterprise</a></li>\n";
		}
		
		echo "						</ul>\n";
		echo "						<div id=\"FOffice\">\n";
		
		?>
	
		<SCRIPT type=text/javascript>
		$(function() {
			
			$("#fsprogressbar").progressbar({
				value: <?php echo $fsperc; ?>
			});
	
		});
		</SCRIPT>
	
		<?php
		
		echo "					<table width=\"500px\">\n";
		echo "								<tr>\n";
		echo "									<td>Storage Limit</td>\n";
		echo "									<td align=\"right\">\n";
		echo number_format(($fslimit / 1000000));
		echo "									Mb</td>\n";
		echo "								</tr>\n";
		echo "								<tr>\n";
		echo "									<td>Storage Current</td>\n";
		echo "									<td align=\"right\">\n";
		echo number_format(($fscurrent / 1000000));
		echo "									Mb</td>\n";
		echo "								</tr>\n";
		echo "								<tr>\n";
		echo "									<td>Storage Remaining</td>\n";
		echo "									<td align=\"right\">\n";
		echo number_format(($fsremain / 1000000));
		echo "									Mb</td>\n";
		echo "								</tr>\n";
		echo "								<tr>\n";
		echo "									<td>Storage Capacity Percent</td>\n";
		echo "									<td align=\"right\">\n";
		echo $fsperc;
		echo "									%</td>\n";
		echo "								</tr>\n";
		echo "								<tr>\n";
		echo "									<td colspan=\"2\">\n";
		echo "										<div id=\"fsprogressbar\"></div>\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</div>\n";
		
		if (in_array($_SESSION['officeid'],$admin_offs))
		{
			echo "						<div id=\"FEnterprise\">\n";
			
			?>
	
			<SCRIPT type=text/javascript>
			$(function() {
				
				$("#fsprogressbarENT").progressbar({
					value: <?php echo $fspercENT; ?>
				});
			});
			</SCRIPT>
	
			<?php
			
			echo "					<table width=\"500px\">\n";
			//echo "								<tr>\n";
			//echo "									<td colspan=\"2\" align=\"left\">Enterprise Storage</td>\n";	
			//echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>Storage Limit</td>\n";
			echo "									<td align=\"right\">\n";
			echo number_format(($fslimitENT / 1000000));
			echo "									Mb</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>Storage Current</td>\n";
			echo "									<td align=\"right\">\n";
			echo number_format(($fscurrentENT / 1000000));
			echo "									Mb</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>Storage Remaining</td>\n";
			echo "									<td align=\"right\">\n";
			echo number_format(($fsremainENT / 1000000));
			echo "									Mb</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>Storage Capacity Percent</td>\n";
			echo "									<td align=\"right\">\n";
			echo $fspercENT;
			echo "									%</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td colspan=\"2\">\n";
			echo "										<div id=\"fsprogressbarENT\"></div>\n";
			echo "									</td>\n";
			echo "								</tr>\n";
			echo "							</table>\n";
			echo "						</div>\n";
		}
		
		echo "					</div>\n";
		echo "				</div>\n";
		echo "				</div>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";
		echo "</div>\n";
	}
}

function select_file_OFF($fsaccess)
{
	//echo __FUNCTION__.'<br>';
	
	$qryA = "SELECT FILEUPLOADS from master..bhest_config;";
	$resA = mssql_query($qryA);
	$rowA = mssql_fetch_array($resA);
	
	$qryB = "SELECT min(slevel) as slevel from jest..jestFileStoreCategory where fctype = 0 and slevel != 0 and active = 1;";
	$resB = mssql_query($qryB);
	$rowB = mssql_fetch_array($resB);
	
	$qry1 = "select officeid,name,fslimit from offices where officeid = ".$_SESSION['officeid'].";";
	$res1 = mssql_query($qry1);
	$row1 = mssql_fetch_array($res1);
	$nrow1= mssql_num_rows($res1);

	if (isset($rowA['FILEUPLOADS']) && $rowA['FILEUPLOADS'] == 0)
	{
		echo '<b>File Uploads disabled</b><br>';
	}
	elseif ($rowB['slevel'] > $fsaccess)
	{
		echo '<b>File Uploads disabled<br><br>Access Level below minimum</b><br>';
	}
	else
	{
		$admin_offs=array(89);
		
		$qry0 = "SELECT fscid,fscatname from jest..jestFileStoreCategory where oid=".$_SESSION['officeid']." and fctype = 0 and slevel <= ".$fsaccess." and active = 1 order by fscatname asc;";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			while ($row0 = mssql_fetch_array($res0))
			{
				$fscat_ar[$row0['fscid']]=$row0['fscatname'];
			}
		}
		else
		{
			$fscat_ar=array();
		}
		
		$qry2 = "select isnull(sum(filesize),0) as cfilesize from jest..jestFileStore as F1 inner join jest..jestFileStoreCategory as F2 on F1.fscid=F2.fscid where F1.oid = ".$_SESSION['officeid']." and F2.fctype = 0;";
		$res2 = mssql_query($qry2);
		$row2 = mssql_fetch_array($res2);
		$nrow2= mssql_num_rows($res2);
		
		$fslimit	=$row1['fslimit'] * 1000000;
		$fscurrent	=$row2['cfilesize'];
		$fsremain	=$fslimit - $fscurrent;
		$ufsperc	=round(($fscurrent/$fslimit),2);
		
		if ($fscurrent < 1)
		{
			$fsperc	=0;
		}
		else
		{
			$fsperc	=($ufsperc * 100);
		}
		
		$uid		= md5(session_id().'.'.time().'.'.$_SESSION['securityid']);
		$num_files	= 1;
		$max_file_size=10000000;
		
		//echo "<script type=\"text/javascript\" src=\"js/jquery_file_func.js\"></script>\n";
		echo "<div class=\"noPrint\">\n";
		echo "	<table width=\"700px\">\n";
		echo "		<tr>\n";
		echo "			<td>\n";
		echo "			<div id=\"fileselect\">\n";
		echo "				<ul>\n";
		echo "					<li><a href=\"#FSelect\">File Upload</a></li>\n";
		
		if ($fsaccess >= 6)
		{
			echo "					<li><a href=\"#CFolder\">Create Folder</a></li>\n";
		}
		
		echo "					<li><a href=\"#SSpace\">Storage Space</a></li>\n";
		echo "				</ul>\n";
		echo "				<div id=\"FSelect\">\n";
		echo "					<table width=\"575px\">\n";
		echo "						<tr>\n";
		echo "							<td align=\"right\">\n";
		
		if ($fsremain < 1)
		{
			echo "File Storage Limit Exceeded. Contact BH National Management.\n";
		}
		else
		{
			echo "								<form id=\"fileupfrm\" enctype=\"multipart/form-data\" method=\"post\">\n";
			echo "								<input type=\"hidden\" name=\"action\" value=\"file\">\n";
			echo "								<input type=\"hidden\" name=\"call\" value=\"upload_file_OFF\">\n";
			
			if (isset($_REQUEST['subq']) && !empty($_REQUEST['subq']))
			{
				echo "								<input type=\"hidden\" name=\"subq\" value=\"".$_REQUEST['subq']."\">\n";
			}
			
			echo "								<input type=\"hidden\" name=\"storetype\" value=\"file\">\n";
			echo "								<input type=\"hidden\" name=\"fsremain\" value=\"".$fsremain."\">\n";
			echo "								<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
			echo "								<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".$max_file_size."\" />\n";
			echo "								<input type=\"hidden\" name=\"foid\" value=\"".$_SESSION['officeid']."\">\n";
			echo "								<table>\n";
			echo "									<tr>\n";
			echo "										<td><img src=\"images/pixel.gif\"></td>\n";
			echo "										<td align=\"left\"><b>Folder</b></td>\n";
			echo "										<td align=\"left\"><b>File</b></td>\n";
			echo "										<td><img src=\"images/pixel.gif\"></td>\n";
			echo "									</tr>\n";
			echo "									<tr>\n";
			echo "										<td align=\"right\"><b>File Upload</b></td>\n";		
			echo "										<td>\n";
			echo "											<select name=\"fscid\" id=\"fscid\">\n";
			echo "												<option value=\"0\">Select...</option>\n";
			
			foreach ($fscat_ar as $n =>$v)
			{
				echo "											<option value=\"".$n."\">".$v."</option>\n";
			}
			
			echo "											</select>\n";
			echo "										</td>\n";
			echo "										<td>\n";
			echo "											<input class=\"buttondkgraypnl\" type=\"file\" name=\"userfile[]\" id=\"userfile\">\n";
			echo "										</td>\n";
			echo "										<td align=\"right\">\n";
			echo "											<input class=\"transnb\" type=\"image\" src=\"images/folder_go.png\" onClick=\"return FileUploadCheck('fscid','userfile','Folder','File');\" alt=\"Upload File\">\n";
			echo "										</td>\n";
			echo "									</tr>\n";
			echo "								</table>\n";
		}
		
		echo "								</form>\n";
		echo "							</td>\n";
		echo "						</tr>\n";
		echo "					</table>\n";
		echo "				</div>\n";
		
		if ($fsaccess >= 9)
		{
			echo "				<div id=\"CFolder\">\n";
			echo "					<table width=\"575px\">\n";
			echo "						<tr>\n";
			echo "							<td align=\"right\">\n";
			
			if ($fsremain < 1)
			{
				echo "File Storage Limit Exceeded. Contact BH National Management.\n";
			}
			else
			{
				echo "								<form id=\"foldercreate\" enctype=\"multipart/form-data\" method=\"post\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"file\">\n";
				echo "								<input type=\"hidden\" name=\"call\" value=\"add_folder_OFF\">\n";
				echo "								<input type=\"hidden\" name=\"foid\" value=\"".$_SESSION['officeid']."\">\n";
				echo "								<table>\n";
				echo "									<tr>\n";
				echo "										<td><img src=\"images/pixel.gif\"></td>\n";
				echo "										<td align=\"left\"></td>\n";
				echo "										<td align=\"left\"><b>Folder Name</b></td>\n";
				echo "										<td><img src=\"images/pixel.gif\"></td>\n";
				echo "									</tr>\n";
				echo "									<tr>\n";
				echo "										<td align=\"right\"><b><b>Parent Folder</b></b></td>\n";		
				echo "										<td>\n";
				echo "											<select name=\"parentid\" id=\"parentid\">\n";
				echo "												<option value=\"0\">Home</option>\n";
				
				foreach ($fscat_ar as $n =>$v)
				{
					echo "											<option value=\"".$n."\">".$v."</option>\n";
				}
				
				echo "											</select>\n";
				echo "										</td>\n";
				echo "										<td>\n";
				echo "											<input class=\"bboxb\" type=\"text\" name=\"foldername\" id=\"foldername\" maxlength=\"25\" size=\"25\">\n";
				echo "										</td>\n";
				echo "										<td align=\"right\">\n";
				echo "											<input class=\"transnb_button\" type=\"image\" src=\"images/add.png\" onClick=\"return FolderCheck('parentid','foldername');\" title=\"Create Folder\">\n";
				echo "										</td>\n";
				echo "									</tr>\n";
				echo "								</table>\n";
				echo "								</form>\n";
			}
			
			echo "							</td>\n";
			echo "						</tr>\n";
			echo "					</table>\n";
			echo "				</div>\n";
		}
		
		echo "				<div id=\"SSpace\">\n";
		echo "					<div id=\"storageselect\">\n";
		echo "						<ul>\n";
		echo "							<li><a href=\"#FOffice\">Office</a></li>\n";
		echo "						</ul>\n";
		echo "						<div id=\"FOffice\">\n";
		
		?>
	
		<SCRIPT type=text/javascript>
		$(function() {
			
			$("#fsprogressbar").progressbar({
				value: <?php echo $fsperc; ?>
			});
	
		});
		</SCRIPT>
	
		<?php
		
		echo "							<table width=\"500px\">\n";
		echo "								<tr>\n";
		echo "									<td>Storage Limit</td>\n";
		echo "									<td align=\"right\">\n";
		echo number_format(($fslimit / 1000000));
		echo "									Mb</td>\n";
		echo "								</tr>\n";
		echo "								<tr>\n";
		echo "									<td>Storage Current</td>\n";
		echo "									<td align=\"right\">\n";
		echo number_format(($fscurrent / 1000000));
		echo "									Mb</td>\n";
		echo "								</tr>\n";
		echo "								<tr>\n";
		echo "									<td>Storage Remaining</td>\n";
		echo "									<td align=\"right\">\n";
		echo number_format(($fsremain / 1000000));
		echo "									Mb</td>\n";
		echo "								</tr>\n";
		echo "								<tr>\n";
		echo "									<td>Storage Capacity Percent</td>\n";
		echo "									<td align=\"right\">\n";
		echo $fsperc;
		echo "									%</td>\n";
		echo "								</tr>\n";
		echo "								<tr>\n";
		echo "									<td colspan=\"2\">\n";
		echo "										<div id=\"fsprogressbar\"></div>\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "						</div>\n";
		echo "					</div>\n";
		echo "				</div>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";
		echo "</div>\n";
	}
}

function select_file_CID($ftype,$fscat_ar)
{
	if (isset($_REQUEST['cid']) && $_REQUEST['cid'] != 0)
	{
		$qry = "select isnull(sum(filesize),0) as tfsize from jest..jestFileStore where cid=".$_REQUEST['cid']." and active=1;";
		$res = mssql_query($qry);
		$row = mssql_fetch_array($res);
		$nrow= mssql_num_rows($res);
		
		$max_cstore	= 10000000;
		$uid		= md5(session_id().'.'.time().'.'.$_SESSION['securityid']);
		$num_files	= 1;
	
		echo "<script type=\"text/javascript\" src=\"js/jquery_file_func.js\"></script>\n";
		echo "<div class=\"noPrint\">\n";
		echo "	<br />\n";
		echo "	<table class=\"outer\" width=\"700px\">\n";
		echo "		<tr>\n";
		echo "			<td class=\"gray\" align=\"right\">\n";
		echo "				<table width=\"100%\">\n";
		echo "					<tr>\n";
		
		if (($row['tfsize'] / $max_cstore) > .9)
		{
			echo "						<td align=\"left\" valign=\"bottom\"><b>Total Storage</b> <font color=\"red\"><div title=\"File Store for this Customer is greater than 90%\">".number_format($row['tfsize'])." kb</div></font> / ".number_format($max_cstore)." kb</td>\n";
		}
		else
		{
			echo "						<td align=\"left\" valign=\"bottom\"><b>Total Storage</b> ".number_format($row['tfsize'])." kb / ".number_format($max_cstore)." kb</td>\n";
		}
		
		echo "						<td align=\"right\">\n";
		
		if (($row['tfsize'] / $max_cstore) > 1)
		{
			echo "<font color=\"red\"><b>This Customer is at or over the file storage limit!</b></font>";
			echo "							<form id=\"fileupfrm\" enctype=\"multipart/form-data\" method=\"post\" DISABLED>\n";
		}
		else
		{
			echo "							<form id=\"fileupfrm\" enctype=\"multipart/form-data\" method=\"post\">\n";
		}
		
		echo "							<input type=\"hidden\" name=\"action\" value=\"file\">\n";
		echo "							<input type=\"hidden\" name=\"call\" value=\"upload_file_CID\">\n";
		echo "							<input type=\"hidden\" name=\"storetype\" value=\"file\">\n";
		echo "							<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">\n";
		echo "							<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
		echo "							<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"1000100\" />\n";
		echo "							<table>\n";
		echo "								<tr>\n";
		echo "									<td align=\"left\"><img src=\"images/pixel.gif\"></td>\n";
		echo "									<td align=\"left\"><b>Category/Folder</b></td>\n";
		echo "									<td align=\"left\"><b>File</b></td>\n";
		echo "									<td><img src=\"images/pixel.gif\"></td>\n";
		echo "								</tr>\n";
		echo "								<tr>\n";
		echo "									<td align=\"right\"><b>File Upload</b></td>\n";
		echo "									<td>\n";
		echo "										<select name=\"fscid\" id=\"fscid\">\n";
		echo "											<option value=\"0\">Select...</option>\n";
		
		foreach ($fscat_ar as $fn => $fv)
		{
			echo "								<option value=\"".$fn."\">".$fv."</option>\n";
		}
		
		echo "										</select>\n";
		echo "									</td>\n";
		echo "									<td>\n";
		echo "										<input class=\"buttondkgraypnl\" type=\"file\" name=\"userfile[]\" id=\"userfile\">\n";
		echo "									</td>\n";
		echo "									<td align=\"right\">\n";
		echo "										<input class=\"transnb\" type=\"image\" src=\"images/folder_go.png\" onClick=\"return FileUploadCheck('fscid','userfile','Category','File');\" alt=\"Upload File\">\n";
		echo "									</td>\n";
		echo "								</tr>\n";
		echo "							</table>\n";
		echo "							</form>\n";
		echo "						</td>\n";
		echo "					</tr>\n";
		echo "				</table>\n";
		echo "			</td>\n";
		echo "		</tr>\n";
		echo "	</table>\n";
		echo "				</div>\n";
	}
	else
	{
		echo 'Transition Error: Invalid CID<br>';
	}
	
}

function parse_tree_test()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	//echo '<code>';
	echo "<ul id=\"file_tree\" class=\"filetree\">\n";
	echo "	<li><span class=\"folder\">Folder 1</span>\n";
	echo "		<ul>\n";
	echo "			<li><span class=\"folder\">Folder 1.1</span>\n";
	echo "				<ul>\n";
	echo "					<li><span class=\"folder\">Folder 1.1.1</span>\n";
	echo "						<ul>\n";
	echo "							<li><span class=\"file\">Item 1.1.1.0</span></li>\n";
	echo "							<li><span class=\"file\">Item 1.1.1.1</span></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
	echo "					<li><span class=\"file\">Item 1.1.2</span></li>\n";
	echo "					<li><span class=\"file\">Item 1.1.3</span></li>\n";
	echo "				</ul>\n";
	echo "			</li>\n";
	echo "			<li><span class=\"folder\">Folder 1.2</span>\n";
	echo "				<ul>\n";
	echo "					<li><span class=\"folder\">Folder 1.2.1</span>\n";
	echo "						<ul>\n";
	echo "							<li><span class=\"file\">Item 1.2.1.0</span></li>\n";
	echo "							<li><span class=\"file\">Item 1.2.1.1</span></li>\n";
	echo "							<li><span class=\"file\">Item 1.2.1.2</span></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
	echo "					<li><span class=\"folder\">Folder 1.2.2</span>\n";
	echo "						<ul>\n";
	echo "							<li><span class=\"file\">Item 1.2.2.0</span></li>\n";
	echo "							<li><span class=\"file\">Item 1.2.2.1</span></li>\n";
	echo "							<li><span class=\"file\">Item 1.2.2.2</span></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
	echo "					<li><span class=\"file\">Item 1.2.3</span></li>\n";
	echo "				</ul>\n";
	echo "			</li>\n";
	echo "			<li><span class=\"file\">Item 1.3</span></li>\n";
	echo "		</ul>\n";
	echo "	</li>\n";
	echo "	<li><span class=\"folder\">Folder 2</span>\n";
	echo "		<ul>\n";
	echo "			<li><span class=\"folder\">Folder 2.1</span>\n";
	echo "				<ul>\n";
	echo "					<li><span class=\"folder\">Folder 2.1.1</span>\n";
	echo "						<ul>\n";
	echo "							<li><span class=\"file\">Item 2.1.1.0</span></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
	echo "				</ul>\n";
	echo "			</li>\n";
	echo "			<li><span class=\"folder\">Folder 2.2</span>\n";
	echo "				<ul>\n";
	echo "					<li><span class=\"folder\">Folder 2.2.1</span>\n";
	echo "						<ul>\n";
	echo "							<li><span class=\"file\">Item 2.2.1.0</span></li>\n";
	echo "							<li><span class=\"file\">Item 2.2.1.1</span></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
	echo "					<li><span class=\"folder\">Folder 2.2.2</span>\n";
	echo "						<ul>\n";
	echo "							<li><span class=\"file\">Item 2.2.2.0</span></li>\n";
	echo "							<li><span class=\"file\">Item 2.2.2.1</span></li>\n";
	echo "							<li><span class=\"file\">Item 2.2.2.2</span></li>\n";
	echo "						</ul>\n";
	echo "					</li>\n";
	echo "				</ul>\n";
	echo "			</li>\n";
	echo "		</ul>\n";
	echo "	</li>\n";
	echo "	<li><span class=\"file\">Item 3</span></li>\n";
	echo "</ul>\n";
}

function parse_tree_live($t_ar,$fsaccess,$addfldr)
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);

	echo "<div id=\"treecontrol\">\n";
	echo "<a href=\"?#\"><img src=\"images/folder-closed.gif\" title=\"Collapse All\"></a>";
	echo " | <a href=\"?#\"><img src=\"images/folder.gif\" title=\"Expand All\"></a>";
	
	if ($addfldr and $fsaccess >= 5)
	{
		echo " | <img class=\"ShowFileEditControl\" src=\"images/folder_edit.png\" height=\"14px\" width=\"14px\" title=\"Toggle Folder & File Edit On/Off\">";
		echo "<div id=\"EditOn\">Editting On</div>\n";
	}
	
	echo "</div>\n";
	echo "HOME";
	
	if ($addfldr and $fsaccess >= 6)
	{
		echo "<div class=\"FileEditControl\">\n";
		echo "	<img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"0\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\">\n";
		echo "</div>\n";
	}
	
	echo "<ul id=\"file_tree\" class=\"filetree\">\n";

	foreach ($t_ar as $n => $v) // 1st Tier Kick-off
	{
		if (is_array($v))
		{
			echo "<li>";
			
			if (isset($v[0][1]) and strlen($v[0][1]) > 3)
			{
				echo "<span class=\"folder JMStooltip\" title=\"".$v[0][1]."\">";
			}
			else
			{
				echo "<span class=\"folder\">";
			}
			
			echo $v[0][0];
			echo "</span>\n";
			
			if ($addfldr and $fsaccess >= 6)
			{
				echo "<div class=\"FileEditControl\">\n";
				
				echo "	<img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\">\n";
				
				if ($fsaccess >= 6 and (count($v[1]) == 0 and count($v[2]) == 0))
				{
					echo "	<img class=\"FolderDelete\" src=\"images/bin.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to Delete this Folder\">\n";
				}
				
				if ($fsaccess >= 5)
				{
					echo "	<img class=\"FileAdd\" src=\"images/folder_go.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to add a File to this Folder\">\n";
				}
				
				echo "</div>\n";
			}
			
			echo "<ul>\n";
			
			if (is_array($v[1]))
			{
				parse_tree_node($v[1],$fsaccess,$addfldr);
			}
			
			if (is_array($v[2]) and count($v[2]) > 0)
			{
				foreach ($v[2] as $fn => $fv)
				{
					echo "<li>\n";
					echo "	<span class=\"file\">".$fv[0]." ";
					echo "		<a class=\"JMStooltip\" href=\"https://jms.bhnmi.com/export/fileout.php?docid=".$fn."&storetype=file\" target=\"_new\" title=\"Click to Download\">".stubfileimages_tree($fv[1])."</a>\n";
					
					if ($addfldr and $fsaccess >= 6)
					{
						echo "<div class=\"FileEditControl\">\n";
						echo "	<img class=\"FileDelete\" src=\"images/bin.png\" id=\"".$fn."\" height=\"14px\" width=\"14px\" title=\"Click to Delete this File\">\n";
						echo "</div>\n";
					}
					
					echo "	</span>\n";
					
					echo "</li>\n";
				}
			}
			
			echo "</ul>\n";
			echo "</li>\n";
		}
	}
	
	echo "</ul>\n";
}

function parse_tree_node($t_ar,$fsaccess,$addfldr)
{
	foreach ($t_ar as $n => $v)
	{
		if (is_array($v))
		{
			echo "<li>";
			
			//echo "<span class=\"folder\">";
			
			if (isset($v[0][1]) and strlen($v[0][1]) > 3)
			{
				echo "<span class=\"folder JMStooltip\" title=\"".$v[0][1]."\">";
			}
			else
			{
				echo "<span class=\"folder\">";
			}
			
			echo $v[0][0];
			echo "</span>\n";
			
			if ($addfldr and $fsaccess >= 6)
			{
				echo "<div class=\"FileEditControl\">\n";
				
				echo "	<img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\">\n";
				
				if ($fsaccess >= 5 and (count($v[1]) == 0 and count($v[2]) == 0))
				{
					echo "	<img class=\"FolderDelete\" src=\"images/bin.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to Delete this Folder\">\n";
				}
				
				if ($fsaccess >= 5)
				{
					echo "	<img class=\"FileAdd\" src=\"images/folder_go.png\" id=\"".$n."\" height=\"14px\" width=\"14px\" title=\"Click to add a File to this Folder\">\n";
				}
				
				echo "</div>\n";
			}
			
			echo "<ul>\n";
			
			if (is_array($v[1]))
			{
				parse_tree_node($v[1],$fsaccess,$addfldr);
			}
			
			if (is_array($v[2]) and count($v[2]) > 0)
			{
				foreach ($v[2] as $fn => $fv)
				{
					echo "<li>\n";
					echo "	<span class=\"file\"> ".$fv[0]." ";
					echo "		<a class=\"JMStooltip\" href=\"https://jms.bhnmi.com/export/fileout.php?docid=".$fn."&storetype=file\" target=\"_new\" title=\"Click to Download\">".stubfileimages_tree($fv[1])."</a>\n";
					
					if ($fsaccess >= 6)
					{
						echo "<div class=\"FileEditControl\">\n";
						echo "	<img class=\"FileDelete\" src=\"images/bin.png\" id=\"".$fn."\" height=\"14px\" width=\"14px\" title=\"Click to Delete this File\">\n";
						echo "</div>\n";
					}
					
					echo "	</span>\n";
					echo "</li>\n";
				}
			}
			
			echo "</ul>\n";
			echo "</li>\n";
		}
	}
}

function build_tree_array($oid,$sl,$fc,$act,$incfiles)
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	$out	=array();
	$fc_ar	=array();
	
	// Begin Special code to prevent P&A Offices (except BHNM: Active & Shared Files)
	// from seeing the Shared Files Rebates Folder.
	$pa_off_ar = array();
	$blocked_doc = 223;
	
	$qry = "select officeid as oid from offices where otype_code=1 and (officeid!=89 or officeid!=197) order by officeid;";
	$res = mssql_query($qry);
	
	while ($row = mssql_fetch_array($res))
	{
		$pa_off_ar[] = $row['oid'];
	}
	
	$pa_off_ar = array_diff($pa_off_ar,array(89,197)); // BHNM & Shared Files Office exceptions
	//End Special Code
	
	
	$qry0 = "select fsid,fscid,parentid,fscatname,comment from jest..jestFileStoreCategory where parentid=0 and oid=".$oid." and fctype=".$fc." and slevel <= ".$sl." and active=".$act." order by fscatname asc;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 > 0) // Build Folder Array
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			if (in_array($_SESSION['officeid'],$pa_off_ar) and $row0['fsid']==$blocked_doc)
			{
			}
			else
			{
				$subar=array();
				
				$fc_ar[$row0['fscid']][0]=array($row0['fscatname'],$row0['comment']);
				$fc_ar[$row0['fscid']][1]=build_tree_subarray($row0['fscid'],$oid,$act,$incfiles);
				
				if ($incfiles)
				{
					$qry1 = "select docid,filename,filetype from jest..jestFileStore where oid=".$oid." and fscid=".$row0['fscid']." and active=".$act." order by filename asc;";
					$res1 = mssql_query($qry1);
					$nrow1= mssql_num_rows($res1);
					
					if ($nrow1 > 0)
					{
						while ($row1 = mssql_fetch_array($res1))
						{
							$subar[$row1['docid']]=array($row1['filename'],$row1['filetype']);
						}
					}
				}
				
				$fc_ar[$row0['fscid']][2]=$subar;
			}
		}
	}
	
	return $out=$fc_ar;
}

function build_tree_subarray($pid,$oid,$act,$incfiles)
{
	$out =array();
	
	$qry0 = "select fsid,fscid,parentid,fscatname,comment from jest..jestFileStoreCategory where parentid=".$pid." and active=".$act." order by fscatname asc;";
	$res0 = mssql_query($qry0);
	$nrow0= mssql_num_rows($res0);
	
	if ($nrow0 > 0)
	{
		while ($row0 = mssql_fetch_array($res0))
		{
			$subar=array();

			$out[$row0['fscid']][0]=array($row0['fscatname'],$row0['comment']);
			$out[$row0['fscid']][1]=build_tree_subarray($row0['fscid'],$oid,$act,$incfiles);
			
			if ($incfiles)
			{
				$qry1 = "select docid,filename,filetype from jest..jestFileStore where oid=".$oid." and fscid=".$row0['fscid']." and active=".$act." order by filename asc;";
				$res1 = mssql_query($qry1);
				$nrow1= mssql_num_rows($res1);
				
				//echo $qry1.'<br>';
				
				if ($nrow1 > 0)
				{
					while ($row1 = mssql_fetch_array($res1))
					{
						$subar[$row1['docid']]=array($row1['filename'],$row1['filetype']);
					}
				}
			}
			
			$out[$row0['fscid']][2]=$subar;
		}
	}
	
	return $out;
}

function add_folder_OFF()
{
	if ((isset($_REQUEST['call']) and $_REQUEST['call']=='add_folder_OFF') and (isset($_REQUEST['foldername']) and !empty($_REQUEST['foldername'])))
	{
		$qryA = "select officeid,securityid,filestoreaccess from security where securityid=".$_SESSION['securityid'].";";
		$resA = mssql_query($qryA);
		$rowA = mssql_fetch_array($resA);
		
		if (isset($rowA['filestoreaccess']) and $rowA['filestoreaccess'] >= 6)
		{
			$qry0 = "select fsid from jest..jestFileStoreCategory
					where oid=".$_SESSION['officeid']." and parentid=".$_REQUEST['parentid']." and fscatname='".trim($_REQUEST['foldername'])."';";
			$res0 = mssql_query($qry0);
			$nrow0= mssql_num_rows($res0);
			
			if ($nrow0==0)
			{		
				$qry1 = "
						begin transaction
						declare @tfscid int
						set @tfscid=(select max(fscid) from jestFileStoreCategory) + 1
						
						insert into jest..jestFileStoreCategory
						(
							 fscid
							,fscatname
							,fctype
							,slevel
							,active
							,oid
							,parentid
							,sid
						) values (
							 @tfscid
							,'".trim($_REQUEST['foldername'])."'
							,0
							,5
							,1
							,".$_SESSION['officeid']."
							,".$_REQUEST['parentid']."
							,".$_SESSION['securityid']."
						);
						
						commit
						";
				
				//echo $qry1.'<br>';
				$res1 = mssql_query($qry1);
				//$row1= mssql_num_rows($res1);
			}
			else
			{
				file_error('Folder already Exists');
			}
		}
		else
		{
			file_error('Inappropriate Access Level');
		}
	}
	else
	{
		file_error('Folder Name not set');
	}
	
	list_file_OFF();
}

function delete_folder_OFF()
{
	$err_cnt=0;
	$err_txt='';
	
	if (isset($_REQUEST['fscid']) && $_REQUEST['fscid']!=0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT fscid from jest..jestFileStoreCategory where fscid=".$_REQUEST['fscid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$row0= mssql_fetch_array($res0);
			
			$qry0a = "SELECT fscid from jest..jestFileStoreCategory where parentid=".$row0['fscid'].";";
			$res0a = mssql_query($qry0a);
			$nrow0a= mssql_num_rows($res0a);
			
			$qry0b = "SELECT docid from jest..jestFileStore where fscid=".$row0['fscid'].";";
			$res0b = mssql_query($qry0b);
			$nrow0b= mssql_num_rows($res0b);
			
			if ($nrow0a==0 and $nrow0b==0)
			{
				$qry1 = "DELETE from jest..jestFileStoreCategory WHERE fscid=".$row0['fscid'].";";
				$res1 = mssql_query($qry1);
				//echo $qry1.'<br>';
			}
			else
			{
				$err_txt=$err_txt.'Folder not Empty<br>';
				$err_cnt++;
			}
		}
		else
		{
			$err_txt=$err_txt.'Folder ID not Found<br>';
			$err_cnt++;
		}
	}
	else
	{
		$err_txt=$err_txt.'Invalid Folder ID<br>';
		$err_cnt++;
	}
	
	if ($err_cnt > 0)
	{
		file_error($err_txt);
	}
	
	list_file_OFF();
}

function deactivate_folder_OFF()
{
	$err_cnt=0;
	$err_txt='';
	if (isset($_REQUEST['folderid']) && $_REQUEST['folderid']!=0)
	{
		//echo 'Deactivating File...';
		$qry0 = "SELECT fscid from jest..jestFileStoreCategory where fscid=".$_REQUEST['folderid'].";";
		$res0 = mssql_query($qry0);
		$nrow0= mssql_num_rows($res0);
		
		if ($nrow0 > 0)
		{
			$row0= mssql_fetch_array($res0);
			
			$qry0a = "SELECT fscid from jest..jestFileStoreCategory where parentid=".$row0['fscid'].";";
			$res0a = mssql_query($qry0a);
			$nrow0a= mssql_num_rows($res0a);
			
			$qry0b = "SELECT docid from jest..jestFileStore where fscid=".$row0['fscid'].";";
			$res0b = mssql_query($qry0b);
			$nrow0b= mssql_num_rows($res0b);
			
			//echo $nrow0a.'<br>';
			//echo $nrow0b.'<br>';
			
			if ($nrow0a==0 and $nrow0b==0)
			{
				$qry1 = "UPDATE jest..jestFileStoreCategory SET active=0,udate=getdate() WHERE fscid=".$row0['fscid'].";";
				$res1 = mssql_query($qry1);
				//echo $qry1.'<br>';
			}
			else
			{
				$err_txt=$err_txt.'Folder not Empty<br>';
				$err_cnt++;
			}
		}
		else
		{
			$err_txt=$err_txt.'Folder ID not Found<br>';
			$err_cnt++;
		}
	}
	else
	{
		$err_txt=$err_txt.'Invalid Folder ID<br>';
		$err_cnt++;
	}
	
	if ($err_cnt > 0)
	{
		file_error($err_txt);
	}
	
	list_file_OFF();
}

function list_file_OFF()
{
	//ini_set('display_errors','On');
    //error_reporting(E_ALL);
	$qryp0 = "select O.officeid,O.name,O.fsoffice from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);
	
	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if ((isset($rowp0['fsoffice']) and $rowp0['fsoffice']==1) or $row0['officeid']==89)
	{		
		if ($row0['filestoreaccess'] > 5)
		{
			$tree_ar=build_tree_array($_SESSION['officeid'],$row0['filestoreaccess'],0,1,true);
			
			//$showtree=parse_tree_live($tree_ar);
			
			$qry0a = "SELECT fscid,fscatname from jest..jestFileStoreCategory where oid=".$_SESSION['officeid']." and fctype = 0 and slevel <= ".$row0['filestoreaccess']." and active = 1 order by fscatname asc;";
			$res0a = mssql_query($qry0a);
			$nrow0a= mssql_num_rows($res0a);
			
			$fscat_ar=array();
			while ($row0a = mssql_fetch_array($res0a))
			{
				$fscat_ar[$row0a['fscid']]=$row0a['fscatname'];
			}
			
			$qry0b = "SELECT count(F1.docid) as doccnt from jest..jestFileStore as F1 inner join jest..jestFileStoreCategory as F2 on F1.fscid=F2.fscid where F1.oid=".$_SESSION['officeid']." and F2.fctype=0;";
			$res0b = mssql_query($qry0b);
			$row0b = mssql_fetch_array($res0b);
			
			$qry0c = "SELECT fscid,fscatname from jest..jestFileStoreCategory where oid=".$_SESSION['officeid']." and fctype = 0 and active = 0 order by fscatname asc;";
			$res0c = mssql_query($qry0c);
			$nrow0c= mssql_num_rows($res0c);
			
			$dfscat_ar=array();
			while ($row0c = mssql_fetch_array($res0c))
			{
				$dfscat_ar[$row0c['fscid']]=$row0c['fscatname'];
			}
			
			$qry1 = "select officeid,name,fslimit from offices where officeid = ".$_SESSION['officeid'].";";
			$res1 = mssql_query($qry1);
			$row1 = mssql_fetch_array($res1);
			$nrow1= mssql_num_rows($res1);
			
			$qry2 = "select isnull(sum(filesize),0) as cfilesize from jest..jestFileStore as F1 inner join jest..jestFileStoreCategory as F2 on F1.fscid=F2.fscid where F1.oid = ".$_SESSION['officeid']." and F2.fctype = 0;";
			$res2 = mssql_query($qry2);
			$row2 = mssql_fetch_array($res2);
			$nrow2= mssql_num_rows($res2);
			
			$fslimit	=$row1['fslimit'] * 1000000;
			$fscurrent	=$row2['cfilesize'];
			$fsremain	=$fslimit - $fscurrent;
			$ufsperc	=round(($fscurrent/$fslimit),2);
			
			if ($fscurrent < 1)
			{
				$fsperc	=0;
			}
			else
			{
				$fsperc	=($ufsperc * 100);
			}
			
			//$uid		= md5(session_id().'.'.time().'.'.$_SESSION['securityid']);
			$num_files	= 1;
			$max_file_size=10000000;
			
			$qry = "
				SELECT
					 F.filename
					,F.filetype
					,F.filesize
					,F.filestore
					,F.adate
					,F.udate
					,F.oid
					,(select name from offices where officeid=F.oid) as foname
					,F.sid
					,F.cid
					,F.docid
					,F.fscid
					,F.active
					,C.parentid
					,(select fscatname from jestFileStoreCategory where fscid=F.fscid) as fscat
					,(select slevel from jestFileStoreCategory where fscid=F.fscid) as slevel
				from
					jest..jestFileStore as F
				inner join
					jest..jestFileStoreCategory as C
				on
					F.fscid=C.fscid
				where
					F.oid=".$_SESSION['officeid']."
					and C.fctype = 0 
				";
			
			if (isset($_REQUEST['fscid']) && $_REQUEST['fscid'] != 0)
			{
				$qry .= "and C.fscid = ".$_REQUEST['fscid']." ";
			}
		
			if (isset($_REQUEST['factive']) && $_REQUEST['factive']==0)
			{
				$qry .= "and F.active = 0 ";
				$_SESSION['fvlist']=1;
			}
			else
			{
				$qry .= "and F.active = 1 ";
				$_SESSION['fvlist']=1;
			}
			
			$qry .= "			
				order by
					 fscat asc
					,F.filename	asc;
				";
				
			$res = mssql_query($qry);
			$nrow= mssql_num_rows($res);
			
			echo "<script type=\"text/javascript\" src=\"js/jquery_file_func.js\"></script>\n";
			echo "<table class=\"outer\" width=\"950px\">\n";
			echo "	<tr>\n";
			
			if ($_SESSION['officeid']==197)
			{
				echo "		<td class=\"gray\" align=\"left\"><b>".$_SESSION['offname']." File Cabinet</b></td>\n";
				echo "		<td class=\"gray\" align=\"right\"><b>ATTENTION:</b> All active files in the Corporate File Share are viewable by Blue Haven Office Admins</td>\n";
			}
			else
			{
				echo "		<td class=\"gray\" align=\"left\"><b>".$_SESSION['offname']." File Cabinet</b></td>\n";
				echo "		<td class=\"gray\" align=\"right\">Access Level ".$row0['filestoreaccess']."</td>\n";
			}
			
			echo "	</tr>\n";
			echo "</table>\n";
			
			//select_file_OFF($row0['filestoreaccess']);
			
			echo "<table class=\"transnb\" width=\"950px\">\n";
			echo "	<tr>\n";
			echo "		<td align=\"left\">\n";
			echo "			<div id=\"filelisting_OFF\">\n";
			echo "				<ul>\n";
			echo "					<li><a href=\"#FTree\">Tree View</a></li>\n";
	
			if ($row0['filestoreaccess'] >= 5 and (((isset($row0b['doccnt']) and $row0b['doccnt'] > 0)) or $nrow0a > 0))
			{
				echo "					<li><a href=\"#FList\">List View</a></li>\n";
			}
			
			echo "					<li><a href=\"#SSpace\">Storage Space</a></li>\n";
			echo "				</ul>\n";
			echo "				<div id=\"FTree\">\n";
			echo "					<p>\n";
			
			if (isset($tree_ar) and is_array($tree_ar) and count($tree_ar) > 0)
			{
				parse_tree_live($tree_ar,$row0['filestoreaccess'],true);
			}
			else
			{
				if ($row0['filestoreaccess'] >= 6)
				{
					echo "	<img class=\"FolderAdd\" src=\"images/folder_add.png\" id=\"0\" height=\"14px\" width=\"14px\" title=\"Click to add a Folder\">\n";
				}
			}
			
			echo "					</p>\n";
			echo "				</div>\n";

			if ($row0['filestoreaccess'] >= 5 and (((isset($row0b['doccnt']) and $row0b['doccnt'] > 0)) or $nrow0a > 0))
			{
				$pfile_ar=array();
				while ($row = mssql_fetch_array($res))
				{
					$pfile_ar[$row['docid']]=$row;
				}
				
				echo "				<div id=\"FList\">\n";
				echo "					<p>\n";
				echo "					<table width=\"900px\">\n";
				echo "						<tr>\n";
				echo "							<td colspan=\"2\" align=\"left\"><b>Files & Folders</b></td>\n";
				echo "							<td colspan=\"7\" align=\"right\">\n";
				echo "								<form method=\"post\">\n";
				echo "								<input type=\"hidden\" name=\"action\" value=\"file\">\n";
				echo "								<input type=\"hidden\" name=\"call\" value=\"list_file_OFF\">\n";
				echo "								<table width=\"100%\">\n";
				echo "									<tr>\n";
				echo "										<td align=\"right\" valign=\"bottom\">\n";
				
				if ($row0['filestoreaccess'] == 9)
				{
					echo "											<select name=\"factive\" id=\"factive\" onChange=\"this.form.submit();\">\n";
			
					if (isset($_REQUEST['factive']) && $_REQUEST['factive']==0)
					{
						echo "											<option value=\"1\">Active</option>\n";
						
						if ($row0['filestoreaccess'] >= 6)
						{
							echo "											<option value=\"0\" SELECTED>Purge List</option>\n";
						}
					}
					else
					{
						echo "											<option value=\"1\" SELECTED>Active</option>\n";
						
						if ($row0['filestoreaccess'] >= 6)
						{
							echo "											<option value=\"0\">Purge List</option>\n";
						}
					}
					
					echo "												</select>\n";
				}
				else
				{
					echo "								<input type=\"hidden\" name=\"factive\" value=\"1\">\n";	
				}

				echo "											</td>\n";
				echo "											<td width=\"20px\"><input id=\"frefresh\" class=\"transnb\" type=\"image\" src=\"images/arrow_refresh_small.png\" title=\"Refresh\"></td>\n";
				echo "										</tr>\n";
				echo "									</table>\n";
				echo "								</form>\n";
				
				//display_array($dfscat_ar);
				
				echo "							</td>\n";
				echo "						</tr>\n";
				echo "						<tr class=\"tblhd\">\n";
				echo "							<td><img src=\"images/pixel.gif\"></td>\n";
				echo "							<td align=\"left\" width=\"175px\">Name</td>\n";
				echo "							<td align=\"left\">Loc</td>\n";
				echo "							<td align=\"center\">Size</td>\n";
				echo "							<td align=\"center\">Uploaded</td>\n";
				echo "							<td align=\"center\">Updated</td>\n";
				echo "							<td><img src=\"images/pixel.gif\"></td>\n";
				echo "							<td><img src=\"images/pixel.gif\"></td>\n";
				echo "							<td><img src=\"images/pixel.gif\"></td>\n";
				echo "						</tr>\n";
				
				$lcnt=0;
				
				if ((isset($_REQUEST['factive']) and $_REQUEST['factive']==0) and $row0['filestoreaccess'] >= 6)
				{
					foreach ($dfscat_ar as $nfl => $vfl)
					{
						$lcnt++;
					
						if ($lcnt%2)
						{
							$tbg='white';
						}
						else
						{
							$tbg='ltgray';
						}
						
						echo "						<tr>\n";
						echo "							<td class=\"".$tbg."\"><img src=\"images/pixel.gif\"></td>\n";
						echo "							<td class=\"".$tbg."\" align=\"left\" width=\"175px\">".$vfl."</td>\n";
						echo "							<td class=\"".$tbg."\" align=\"left\">Folder</td>\n";
						echo "							<td class=\"".$tbg."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						echo "							<td class=\"".$tbg."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						echo "							<td class=\"".$tbg."\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
						echo "							<td class=\"".$tbg."\"><img src=\"images/pixel.gif\"></td>\n";
						echo "							<td class=\"".$tbg."\"><img src=\"images/pixel.gif\"></td>\n";
						echo "							<td class=\"".$tbg."\" align=\"center\" width=\"20px\">\n";
						echo "								<form method=\"post\">\n";
						echo "								<input type=\"hidden\" name=\"action\" value=\"file\">\n";
						echo "								<input type=\"hidden\" name=\"call\" value=\"delete_folder_OFF\">\n";
						echo "								<input type=\"hidden\" name=\"fscid\" value=\"".$nfl."\">\n";
						echo "								<input type=\"hidden\" name=\"factive\" value=\"0\">\n";	
						echo "								<input class=\"transnb\" type=\"image\" src=\"images/cross.png\" title=\"Delete Folder\">\n";
						echo "								</form>\n";
						echo "							</td>\n";
						echo "						</tr>\n";
					}
				}

				foreach ($pfile_ar as $nf => $vf)
				{
					$lcnt++;
					
					if ($lcnt%2)
					{
						$tbg='white';
					}
					else
					{
						$tbg='ltgray';
					}
					
					echo "			<tr>\n";
					echo "				<td class=\"".$tbg."\" align=\"center\">\n";
					
					echo stubfileimages(trim($vf['filetype']));
					
					echo "				</td>\n";
					echo "				<td class=\"".$tbg."\" align=\"left\">\n";
					
					if (strlen($vf['filename']) > 25)
					{
						echo "<div title=\"".$vf['filename']."\">".substr($vf['filename'],0,25)."...</div>\n";
					}
					else
					{
						echo "<div title=\"".$vf['filename']."\">".$vf['filename']."</div>\n";
					}
					
					echo 				"</td>\n";
					echo "				<td class=\"".$tbg."\">\n";
					
					if ($row0['filestoreaccess'] >= 6)
					{
						echo "					<form method=\"post\">\n";
						echo "					<input type=\"hidden\" name=\"action\" value=\"file\">\n";
						echo "					<input type=\"hidden\" name=\"call\" value=\"change_folder_OFF\">\n";
						echo "					<input type=\"hidden\" name=\"docid\" value=\"".$nf."\">\n";
						
						if (isset($_REQUEST['factive']))
						{
							echo "					<input type=\"hidden\" name=\"factive\" value=\"".$_REQUEST['factive']."\">\n";
						}
						
						echo "					<select name=\"fscid\" id=\"fscid\" onChange=\"this.form.submit();\">\n";
						
						foreach ($fscat_ar as $fn => $fv)
						{
							if ($vf['fscid']==$fn)
							{
								echo "								<option value=\"".$fn."\" SELECTED>".$fv."</option>\n";
							}
							else
							{
								echo "								<option value=\"".$fn."\">".$fv."</option>\n";
							}
						}
						
						echo "					</select>\n";
						echo "					</form>\n";
					}
					else
					{
						echo $row['fscat'];
					}
					
					echo "				</td>\n";
					//echo "				<td class=\"".$tbg."\">".$vf['fscat']."</td>\n";
					echo "				<td class=\"".$tbg."\" align=\"right\">".number_format($vf['filesize'])." kb</td>\n";
					echo "				<td class=\"".$tbg."\" align=\"center\">".date('m/d/Y G:i A',strtotime($vf['adate']))."</td>\n";
					echo "				<td class=\"".$tbg."\" align=\"center\">".date('m/d/Y G:i A',strtotime($vf['udate']))."</td>\n";
					echo "				<td class=\"".$tbg."\" align=\"center\" width=\"20px\">\n";
				
					if ($vf['active']==0 && $row0['filestoreaccess'] >= 9)
					{
						echo "					<form method=\"post\">\n";
						echo "					<input type=\"hidden\" name=\"action\" value=\"file\">\n";
						echo "					<input type=\"hidden\" name=\"docid\" value=\"".$vf['docid']."\">\n";
						echo "					<input type=\"hidden\" name=\"call\" value=\"delete_file_OFF\">\n";
						
						if (isset($_REQUEST['factive']))
						{
							echo "					<input type=\"hidden\" name=\"factive\" value=\"".$_REQUEST['factive']."\">\n";
						}
						
						echo "					<input class=\"transnb\" type=\"image\" src=\"images/delete.png\" onClick=\"return ConfirmDeleteFile();\" title=\"Purge File\">\n";
						echo "					</form>\n";
					}
					
					echo "				</td>\n";
					echo "				<td class=\"".$tbg."\" align=\"center\" width=\"20px\">\n";
					echo "					<form method=\"post\">\n";
					echo "					<input type=\"hidden\" name=\"action\" value=\"file\">\n";
					echo "					<input type=\"hidden\" name=\"docid\" value=\"".$vf['docid']."\">\n";
					
					if (isset($_REQUEST['subq']) && !empty($_REQUEST['subq']))
					{
						echo "								<input type=\"hidden\" name=\"subq\" value=\"".$_REQUEST['subq']."\">\n";
					}
					
					if (isset($_REQUEST['factive']))
					{
						echo "					<input type=\"hidden\" name=\"factive\" value=\"".$_REQUEST['factive']."\">\n";
					}
					
					if ($row0['filestoreaccess'] >= 6)
					{
						if ($vf['active']==1)
						{
							echo "					<input type=\"hidden\" name=\"call\" value=\"deactivate_file_OFF\">\n";
							echo "					<input class=\"transnb\" type=\"image\" src=\"images/bin.png\" onClick=\"return ConfirmDeactivateFile();\" alt=\"Deactivate File\">\n";
						}
						else
						{
							echo "					<input type=\"hidden\" name=\"call\" value=\"undelete_file_OFF\">\n";
							echo "					<input class=\"transnb\" type=\"image\" src=\"images/accept.png\" onClick=\"return ConfirmRestoreFile();\" alt=\"Restore File\">\n";
						}
					}
					
					echo "					</form>\n";
					echo "				</td>\n";
					echo "				<td class=\"".$tbg."\" align=\"center\" width=\"20px\">\n";
					
					if ($vf['active']==1)
					{
						if (substr(trim($vf['filetype']),0,5) != 'image')
						{
							echo "					<form action=\"https://jms.bhnmi.com/export/fileout.php\" target=\"_new\" method=\"post\">\n";
							echo "					<input type=\"hidden\" name=\"docid\" value=\"".$vf['docid']."\">\n";
							echo "					<input type=\"hidden\" name=\"storetype\" value=\"file\">\n";
							echo "					<input class=\"transnb\" type=\"image\" src=\"images/download.gif\" alt=\"Download File\">\n";
							echo "					</form>\n";
						}
						else
						{
							echo "<a href=\"https://jms.bhnmi.com/subs/showimage.php?docid=".$vf['docid']."\"><img class=\"JMSimgtooltip\" src=\"export/fileout.php?storetype=file&docid=".$vf['docid']."\" height=\"16px\" width=\"16px\" title=\"".$vf['filename']."\"></a>\n";
						}
					}
					
					echo "				</td>\n";
					echo "			</tr>\n";
				}
				
				
					
				echo "						</table>\n";
				echo "						</p>\n";
				echo "					</div>\n";
			}

			echo "				<div id=\"SSpace\">\n";
			echo "					<div id=\"storageselect\">\n";
			echo "						<ul>\n";
			echo "							<li><a href=\"#FOffice\">Office</a></li>\n";
			echo "						</ul>\n";
			echo "						<div id=\"FOffice\">\n";
			
			?>
		
			<SCRIPT type=text/javascript>
			$(function() {
				
				$("#fsprogressbar").progressbar({
					value: <?php echo $fsperc; ?>
				});
		
			});
			</SCRIPT>
		
			<?php
			
			
			echo "							<table width=\"750px\">\n";
			echo "								<tr>\n";
			echo "									<td>Storage Limit</td>\n";
			echo "									<td align=\"right\">\n";
			echo number_format(($fslimit / 1000000));
			echo "									Mb</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>Storage Current $fscurrent</td>\n";
			echo "									<td align=\"right\">\n";
			echo number_format(($fscurrent / 1000000));
			echo "									Mb</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>Storage Remaining</td>\n";
			echo "									<td align=\"right\">\n";
			echo number_format(($fsremain / 1000000));
			echo "									Mb</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td>Storage Capacity Percent</td>\n";
			echo "									<td align=\"right\">\n";
			echo $fsperc;
			echo "									%</td>\n";
			echo "								</tr>\n";
			echo "								<tr>\n";
			echo "									<td colspan=\"2\">\n";
			echo "										<div id=\"fsprogressbar\"></div>\n";
			echo "									</td>\n";
			echo "								</tr>\n";
			echo "							</table>\n";
			echo "						</div>\n";
			echo "					</div>\n";

			echo "			</div>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			
			if ($row0['filestoreaccess'] >= 6)
			{
				echo "<div id=\"AddFolderDialog\" title=\"Add a New Folder\">
						Folder Name<br>
						<form id=\"AddFolderForm\" method=\"POST\">
						<input type=\"hidden\" name=\"action\" value=\"file\">
						<input type=\"hidden\" name=\"call\" value=\"add_folder_OFF\">
						<div id=\"DivAddFolderFormElement\"></div>
						<input type=\"text\" name=\"foldername\" id=\"newfoldername\" size=\"25\" maxlength=\"25\">
						</form>
					</div>";
					
				echo "<div id=\"DeleteFolderDialog\" title=\"Confirm Folder Delete\">
						Confirm Delete Folder? 
						<form id=\"DeleteFolderForm\" method=\"POST\">
						<input type=\"hidden\" name=\"action\" value=\"file\">
						<input type=\"hidden\" name=\"call\" value=\"deactivate_folder_OFF\">
						<div id=\"DivDeleteFolderFormElement\"></div>
						</form>
					</div>";
					
				echo "<div id=\"AddFileDialog\" title=\"File Upload\">
						Select File for upload<br>
						<form id=\"AddFileForm\" enctype=\"multipart/form-data\" method=\"post\">
						<input type=\"hidden\" name=\"action\" value=\"file\">
						<input type=\"hidden\" name=\"call\" value=\"upload_file_OFF\">
						<div id=\"DivAddFileFormElement\"></div>
						<input type=\"hidden\" name=\"storetype\" value=\"file\">
						<input type=\"hidden\" name=\"fsremain\" value=\"".$fsremain."\">
						<input type=\"hidden\" name=\"uid\" value=\"".md5(session_id().'.'.time().'.'.$_SESSION['securityid'])."\">
						<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".$max_file_size."\" />
						<input type=\"hidden\" name=\"foid\" value=\"".$_SESSION['officeid']."\">
						<input class=\"buttondkgraypnl\" type=\"file\" name=\"userfile[]\" id=\"userfile\">
						</form>
					</div>";
					
				echo "<div id=\"DeleteFileDialog\" title=\"File Delete\">
						Confirm Delete File? 
						<form id=\"DeleteFileForm\" method=\"post\">
						<input type=\"hidden\" name=\"action\" value=\"file\">
						<input type=\"hidden\" name=\"call\" value=\"deactivate_file_OFF\">
						<div id=\"DivDeleteFileFormElement\"></div>
						</form>
					</div>";
			}
		}
		else
		{
			echo "No Files Found";	
		}
	}
	else
	{
		echo 'You do not have appropriate access rights to the Office File Cabinet<br>';
	}
}

function list_file_ENT()
{
	//ini_set('display_errors','On');
    //error_reporting(E_ALL);
	
	$qryp0 = "select O.officeid,O.name,O.fsshared from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);
	
	// View Only mode for Corporate Shared Files
	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if ((isset($rowp0['fsshared']) and $rowp0['fsshared']==1) or $row0['officeid']==89)
	{		
		if ($row0['filestoreaccess'] >= 5)
		{		
			$tree_ar=build_tree_array(197,$row0['filestoreaccess'],0,1,true);
			echo "<script type=\"text/javascript\" src=\"js/jquery_file_func.js\"></script>\n";
			echo "<table class=\"outer\" width=\"950px\">\n";
			echo "	<tr>\n";
			echo "		<td class=\"gray\" align=\"left\"><b>Shared Files</b></td>\n";
			echo "		<td class=\"gray\" align=\"right\"><img class=\"JMStooltip\" src=\"images/help.png\" title=\"Browse the Tree below to access Documents provided by BHNM. Click on the Icon to Download.\"></td>\n";
			echo "	</tr>\n";
			echo "</table>\n";
			
			if (isset($tree_ar) and is_array($tree_ar) and count($tree_ar) > 0)
			{
				echo "<table class=\"transnb\" width=\"950px\">\n";
				echo "	<tr>\n";
				echo "		<td align=\"left\">\n";
				echo "			<div id=\"filelisting\">\n";
				echo "				<ul>\n";
				echo "					<li><a href=\"#FTree\">Tree View</a></li>\n";		
				echo "				</ul>\n";
				echo "				<div id=\"FTree\">\n";
				echo "					<p>\n";
			
				parse_tree_live($tree_ar,$row0['filestoreaccess'],false);
				
				echo "					</p>\n";
				echo "				</div>\n";
				echo "			</div>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "</table>\n";
			
			}
			else
			{
				echo "No Files Available at this time";
			}
		}
		else
		{
			//echo "No Access";
			echo 'You do not have appropriate access rights to the Shared File Cabinet<br>';
		}
	}
	else
	{
		echo 'Shared File Cabinet disabled for this Office<br>';
	}
}

function list_file()
{
	ini_set('display_errors','On');
    error_reporting(E_ALL);
	
	//echo var_dump(FILEUPLOAD).'<br>';
	
	$qry0 = "select securityid,filestoreaccess,officeid from security where securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);

	if ($_SESSION['securityid']==26)
	{
		if ($_SESSION['officeid']==197)
		{
			if ($row0['filestoreaccess'] >= 9 and $row0['officeid']==89)
			{
				select_file_ENT($row0['filestoreaccess']);
				
				echo "	<br />\n";
			}
			
			list_file_ENT();
		}
		else
		{
			select_file_OFF($row0['filestoreaccess']);
		
			echo "	<br />\n";
			
			list_file_OFF();
		}
	}
	else
	{
		echo 'Office Mode Offline';
	}
}

function list_file_CID()
{
	//ini_set('display_errors','On');
    //error_reporting(E_ALL);
	
	$qryp0 = "select O.officeid,O.name,O.fscustomer from offices as O where O.officeid=".$_SESSION['officeid'].";";
	$resp0 = mssql_query($qryp0);
	$rowp0 = mssql_fetch_array($resp0);
	
	$qry0 = "select S.securityid,S.officeid,S.filestoreaccess from security as S where S.securityid=".$_SESSION['securityid'].";";
	$res0 = mssql_query($qry0);
	$row0 = mssql_fetch_array($res0);
	$nrow0= mssql_num_rows($res0);
	
	if ((isset($rowp0['fscustomer']) and $rowp0['fscustomer']==1) or $row0['officeid']==89)
	{
		if (isset($row0['filestoreaccess']) and $row0['filestoreaccess'] >= 1)
		{
			if (isset($_REQUEST['cid']) && $_REQUEST['cid'] != 0)
			{
				$fuid  =md5(session_id().time().$_REQUEST['cid']).".".$_SESSION['securityid'];
				
				$qry0a = "SELECT fscid,fscatname from jest..jestFileStoreCategory where fctype = 1 and slevel <= ".$row0['filestoreaccess']." and active = 1 order by fscatname asc;";
				$res0a = mssql_query($qry0a);
				$nrow0a= mssql_num_rows($res0a);
				
				$fscat_ar=array();
				while ($row0a = mssql_fetch_array($res0a))
				{
					$fscat_ar[$row0a['fscid']]=$row0a['fscatname'];
				}
				
				$qry1 = "select cid,clname,cfname from cinfo where cid=".$_REQUEST['cid'].";";
				$res1 = mssql_query($qry1);
				$row1 = mssql_fetch_array($res1);
				$nrow1= mssql_num_rows($res1);
				
				$qry = "
					SELECT
						 F.docid
						,F.filename
						,F.filetype
						,F.filesize
						,F.filestore
						,F.adate
						,F.udate
						,F.oid
						,F.sid
						,F.cid
						,F.docid
						,F.fscid
						,F.active
						,F.slevel
						,C.slevel
						,(select fctype from jestFileStoreCategory where fscid=F.fscid) as fctype
						,(select fscatname from jestFileStoreCategory where fscid=F.fscid) as fscat
					from
						jest..jestFileStore as F
					inner join
						jest..jestFileStoreCategory as C
					on
						F.fscid=C.fscid
					where
						fctype=1
						and F.cid=".$_REQUEST['cid']."
					";
					
				if ($row0['filestoreaccess'] <= 6)
				{
					$qry .= "and C.slevel <= ".$row0['filestoreaccess']." ";
				}
		
				if (isset($_REQUEST['nfscid']) && $_REQUEST['nfscid'] != 0)
				{
					$qry .= "and C.fscid = ".$_REQUEST['nfscid']." ";
				}
		
				if (isset($_REQUEST['factive']) && $_REQUEST['factive']==0)
				{
					$qry .= "and F.active = 0 ";
				}
				else
				{
					$qry .= "and F.active = 1 ";
				}
				
				$qry .= "			
					order by
						 fscat asc
						,F.filename	asc;
					";
				$res = mssql_query($qry);
				$nrow= mssql_num_rows($res);
				
				//echo $qry.'<br>';
				
				echo "<table class=\"outer\" width=\"700px\">\n";
				echo "	<tr>\n";
				echo "		<td class=\"gray\" align=\"left\"><b>".$row1['cfname']." ".$row1['clname']." File Cabinet</b></td>\n";
				echo "		<td class=\"gray\" align=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "		<td class=\"gray\" align=\"right\">\n";
				echo "			<table>\n";
				echo "				<tr>\n";
				echo "					<td class=\"gray\">Customer OneSheet</td>\n";
				echo "					<td class=\"gray\">\n";
				echo "						<div class=\"noPrint\">\n";
				echo "							<form method=\"POST\">\n";
				echo "							<input type=\"hidden\" name=\"action\" value=\"reports\">\n";
				echo "							<input type=\"hidden\" name=\"call\" value=\"chistory\">\n";
				echo "							<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
				echo "							<input type=\"hidden\" name=\"uid\" value=\"".$fuid."\">\n";
				echo "							<input class=\"transnb\" type=\"image\" src=\"images/application_view_list.png\" alt=\"Customer OneSheet\">\n";
				echo "							</form>\n";
				echo "						</div>\n";
				echo "					</td>\n";
				echo "				</tr>\n";
				echo "			</table>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "</table>\n";
				
				if (isset($row0['filestoreaccess']) && $row0['filestoreaccess'] >= 5)
				{
					select_file_CID(1,$fscat_ar);
				}
				
				//display_array($fscat_ar);
				echo "<br />\n";
				echo "<div class=\"noPrint\">\n";
				echo "<form method=\"post\">\n";
				echo "<input type=\"hidden\" name=\"action\" value=\"file\">\n";
				echo "<input type=\"hidden\" name=\"call\" value=\"list_file_CID\">\n";
				echo "<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
				echo "	<table width=\"700px\">\n";
				echo "		<tr>\n";
				echo "			<td align=\"left\" valign=\"center\"><b><div title=\"Security Level ".$row0['filestoreaccess']."\">File Listing</div></b></td>\n";
				echo "			<td align=\"left\" valign=\"center\"><img src=\"images/pixel.gif\"></td>\n";
				echo "			<td align=\"right\" valign=\"bottom\">\n";
				echo "				<table>\n";
				echo "					<tr>\n";
				echo "						<td>\n";
				
				if (isset($_REQUEST['ipreview']) && $_REQUEST['ipreview']==1)
				{
					echo "Image Preview <input class=\"transnb\" type=\"checkbox\" name=\"ipreview\" value=\"1\" CHECKED>";
				}
				else
				{
					echo "Image Preview <input class=\"transnb\" type=\"checkbox\" name=\"ipreview\" value=\"1\">";
				}
				
				echo "						</td>\n";
				echo "						<td>\n";
				echo "							<select name=\"factive\" id=\"factive\" onChange=\"this.form.submit();\">\n";
				
				if (isset($_REQUEST['factive']) && $_REQUEST['factive']==0)
				{
					echo "					<option value=\"1\">Active</option>\n";
					
					if ($row0['filestoreaccess'] >= 6)
					{
						echo "					<option value=\"0\" SELECTED>Inactive</option>\n";
					}
				}
				else
				{
					echo "					<option value=\"1\" SELECTED>Active</option>\n";
					
					if ($row0['filestoreaccess'] >= 6)
					{
						echo "					<option value=\"0\">Inactive</option>\n";
					}
				}
				
				echo "							</select>\n";
				echo "						</td>\n";
				echo "						<td align=\"right\" valign=\"bottom\">\n";
				echo "							<select name=\"nfscid\" id=\"nfscid\" onChange=\"this.form.submit();\">\n";
				echo "								<option value=\"0\">All</option>\n";
				
				foreach ($fscat_ar as $fn => $fv)
				{
					if (isset($_REQUEST['nfscid']) && $_REQUEST['nfscid']==$fn)
					{
						echo "								<option value=\"".$fn."\" SELECTED>".$fv."</option>\n";
					}
					else
					{
						echo "								<option value=\"".$fn."\">".$fv."</option>\n";
					}
				}
				
				echo "							</select>\n";
				echo "						</td>\n";
				echo "						<td><input class=\"transnb\" type=\"image\" src=\"images/arrow_refresh_small.png\" alt=\"Refresh\"></td>\n";
				echo "					</tr>\n";
				echo "				</table>\n";
				echo "			</td>\n";
				echo "		</tr>\n";
				echo "	</table>\n";
				echo "</form>\n";
				echo "</div>\n";
				
				if ($nrow > 0)
				{
					//echo "	<br />\n";
					echo "	<table class=\"outer\" width=\"700px\">\n";
					echo "		<thead>\n";
					echo "			<tr>\n";
					echo "				<th><img src=\"images/pixel.gif\"></th>\n";
					echo "				<th><img src=\"images/pixel.gif\"></th>\n";
					echo "				<th align=\"center\">Name</th>\n";
					echo "				<th align=\"center\">Category/Folder</th>\n";
					echo "				<th align=\"center\">Size</th>\n";
					echo "				<th align=\"center\">Uploaded</th>\n";
					echo "				<th align=\"center\">Updated</th>\n";
					echo "				<th><img src=\"images/pixel.gif\"></th>\n";
					echo "				<th><img src=\"images/pixel.gif\"></th>\n";
					echo "				<th><img src=\"images/pixel.gif\"></th>\n";
					echo "			</tr>\n";
					echo "		</thead>\n";
					echo "		<tbody>\n";
					
					$lcnt=0;
					while ($row=mssql_fetch_array($res))
					{
						$lcnt++;
						if ($lcnt%2)
						{
							$tbg='white';
						}
						else
						{
							$tbg='ltgray';
						}
						
						echo "			<tr>\n";
						echo "				<td class=\"".$tbg."\" align=\"right\">".$lcnt.".</td>\n";
						echo "				<td class=\"".$tbg."\" align=\"center\">\n";
						
						echo stubfileimages(trim($row['filetype']));
						
						echo "				</td>\n";
						echo "				<td class=\"".$tbg."\" align=\"left\">\n";
					
						if (strlen($row['filename']) > 25)
						{
							echo "<div title=\"".$row['filename']."\">".substr($row['filename'],0,25)."...</div>\n";
						}
						else
						{
							echo "<div title=\"".$row['filename']."\">".$row['filename']."</div>\n";
						}
						
						echo 				"</td>\n";
						echo "				<td class=\"".$tbg."\">\n";
						
						if ($row0['filestoreaccess'] >= 6)
						{
							echo "					<form method=\"post\">\n";
							echo "					<input type=\"hidden\" name=\"action\" value=\"file\">\n";
							echo "					<input type=\"hidden\" name=\"call\" value=\"change_cat_CID\">\n";
							echo "					<input type=\"hidden\" name=\"docid\" value=\"".$row['docid']."\">\n";
							echo "					<input type=\"hidden\" name=\"cid\" value=\"".$row['cid']."\">\n";
							
							if (isset($_REQUEST['factive']))
							{
								echo "					<input type=\"hidden\" name=\"factive\" value=\"".$_REQUEST['factive']."\">\n";
							}
							
							echo "					<select name=\"fscid\" id=\"fscid\" onChange=\"this.form.submit();\">\n";
							
							foreach ($fscat_ar as $fn => $fv)
							{
								if ($row['fscid']==$fn)
								{
									echo "								<option value=\"".$fn."\" SELECTED>".$fv."</option>\n";
								}
								else
								{
									echo "								<option value=\"".$fn."\">".$fv."</option>\n";
								}
							}
							
							echo "					</select>\n";
							echo "					</form>\n";
						}
						else
						{
							echo $row['fscat'];
						}
						
						echo "				</td>\n";
						echo "				<td class=\"".$tbg."\" align=\"right\">".number_format($row['filesize'])." kb</td>\n";
						echo "				<td class=\"".$tbg."\" align=\"center\">".date('m/d/Y G:i A',strtotime($row['adate']))."</td>\n";
						echo "				<td class=\"".$tbg."\" align=\"center\">".date('m/d/Y G:i A',strtotime($row['udate']))."</td>\n";
						echo "				<td class=\"".$tbg."\" align=\"center\" width=\"20px\">\n";
						
						if ($row['active']==0 && $row0['filestoreaccess'] >= 9)
						{
							echo "					<form method=\"post\">\n";
							echo "					<input type=\"hidden\" name=\"action\" value=\"file\">\n";
							echo "					<input type=\"hidden\" name=\"docid\" value=\"".$row['docid']."\">\n";
							echo "					<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
							echo "					<input type=\"hidden\" name=\"call\" value=\"delete_file_CID\">\n";
							
							if (isset($_REQUEST['factive']))
							{
								echo "					<input type=\"hidden\" name=\"factive\" value=\"".$_REQUEST['factive']."\">\n";
							}
							
							echo "					<input class=\"transnb\" type=\"image\" src=\"images/delete.png\" onClick=\"return ConfirmDeleteFile();\" alt=\"Delete File\">\n";
							echo "					</form>\n";
						}
						
						echo "				</td>\n";
						echo "				<td class=\"".$tbg."\" align=\"center\" width=\"20px\">\n";
						echo "					<form method=\"post\">\n";
						echo "					<input type=\"hidden\" name=\"action\" value=\"file\">\n";
						echo "					<input type=\"hidden\" name=\"docid\" value=\"".$row['docid']."\">\n";
						echo "					<input type=\"hidden\" name=\"cid\" value=\"".$_REQUEST['cid']."\">\n";
						
						if (isset($_REQUEST['factive']))
						{
							echo "					<input type=\"hidden\" name=\"factive\" value=\"".$_REQUEST['factive']."\">\n";
						}
						
						if ($row0['filestoreaccess'] >= 6)
						{
							if ($row['active']==1)
							{
								echo "					<input type=\"hidden\" name=\"call\" value=\"deactivate_file_CID\">\n";
								echo "					<input class=\"transnb\" type=\"image\" src=\"images/bin.png\" onClick=\"return ConfirmDeactivateFile();\" alt=\"Deactivate File\">\n";
							}
							else
							{
								echo "					<input type=\"hidden\" name=\"call\" value=\"undelete_file_CID\">\n";
								echo "					<input class=\"transnb\" type=\"image\" src=\"images/accept.png\" onClick=\"return ConfirmRestoreFile();\" alt=\"Restore File\">\n";
							}
						}
						
						echo "					</form>\n";
						echo "				</td>\n";
						echo "				<td class=\"".$tbg."\" align=\"center\" width=\"20px\">\n";
						
						if ($row['active']==1)
						{
							if (substr(trim($row['filetype']),0,5) != 'image')
							{
								echo "					<form action=\"https://jms.bhnmi.com/export/fileout.php\" target=\"_new\" method=\"post\">\n";
								echo "					<input type=\"hidden\" name=\"docid\" value=\"".$row['docid']."\">\n";
								echo "					<input type=\"hidden\" name=\"storetype\" value=\"file\">\n";
								echo "					<input class=\"transnb\" type=\"image\" src=\"images/download.gif\" alt=\"Download File\">\n";
								echo "					</form>\n";
							}
							else
							{
								echo "<a href=\"https://jms.bhnmi.com/subs/showimage.php?docid=".$row['docid']."\"><img class=\"JMSimgtooltip\" src=\"export/fileout.php?storetype=file&docid=".$row['docid']."\" height=\"16px\" width=\"16px\" title=\"".$row['filename']."\"></a>\n";
							}
						}
						
						echo "				</td>\n";
						echo "			</tr>\n";
					}
					
					echo "		</tbody>\n";
				}
				else
				{
					echo "No Files Found";	
				}
				
				echo "	</table>\n";
			}
		}
		else
		{
			echo 'You do not have appropriate access rights to Customer File Cabinet<br>';
		}
	}
	else
	{
		echo 'Customer File Cabinet disabled for this Office<br>';
	}
}

?>